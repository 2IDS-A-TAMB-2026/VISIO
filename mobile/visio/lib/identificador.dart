import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:camera/camera.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:permission_handler/permission_handler.dart';

import 'appcolor.dart';
import 'services/api_config.dart';
import 'services/teachable_machine_service.dart';
import 'services/tts_service.dart';
import 'widgets/tts_button.dart';

class SensorResult {
  final String nome;
  final String descricao;
  final String circuito;

  final double confianca;

  final String imagemUrl;

  const SensorResult({
    required this.nome,
    required this.descricao,
    required this.circuito,
    required this.confianca,
    required this.imagemUrl,
  });
}

class IdentificacaoException implements Exception {
  final String mensagem;
  const IdentificacaoException(this.mensagem);

  @override
  String toString() => mensagem;
}

class AiSensorService {
  static const double _confiancaMinima = 0.7;

  Future<SensorResult> analisarImagem(XFile imagem) async {
    if (!TeachableMachineService.instance.disponivel) {
      throw const IdentificacaoException(
        'Identificação por imagem está disponível apenas na versão Web '
        'deste aplicativo.',
      );
    }

    final bytes = await imagem.readAsBytes();
    final previsao = await TeachableMachineService.instance.melhorPrevisao(
      bytes,
    );

    if (previsao == null) {
      throw const IdentificacaoException(
        'Modelo de identificação não carregado. Tente novamente.',
      );
    }

    if (previsao.probability < _confiancaMinima) {
      throw const IdentificacaoException(
        'Sensor não reconhecido com confiança suficiente.',
      );
    }
    final nomeParaBusca = previsao.className.toLowerCase().trim();

    http.Response resposta;
    try {
      resposta = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/identificador/buscar-sensor'),
        headers: {'Accept': 'application/json'},
        body: {'nome': nomeParaBusca},
      );
    } catch (_) {
      throw const IdentificacaoException(
        'Erro ao consultar o banco de dados. Verifique sua conexão e '
        'tente novamente.',
      );
    }

    if (resposta.statusCode != 200) {
      throw const IdentificacaoException(
        'Erro ao consultar o banco de dados. Verifique sua conexão e '
        'tente novamente.',
      );
    }

    final corpo = json.decode(resposta.body) as Map<String, dynamic>;

    if (corpo['success'] != true || corpo['sensor'] is! Map) {
      throw IdentificacaoException(
        'Sensor identificado (${previsao.className}), mas não encontrado '
        'no banco de dados.',
      );
    }

    final sensor = corpo['sensor'] as Map<String, dynamic>;
    final foto = sensor['FOTO'] as String?;

    return SensorResult(
      nome: sensor['NOME'] as String? ?? previsao.className,
      descricao: sensor['DESCRICAO'] as String? ?? '',
      circuito: sensor['CIRCUITO'] as String? ?? '',
      confianca: previsao.probability,
      imagemUrl: ApiConfig.resolverUrlImagem(foto) ?? '',
    );
  }
}

class IdentificadorPage extends StatefulWidget {
  final bool isActive;

  const IdentificadorPage({super.key, this.isActive = true});

  @override
  State<IdentificadorPage> createState() => _IdentificadorPageState();
}

class _IdentificadorPageState extends State<IdentificadorPage> {
  CameraController? _cameraController;
  final ImagePicker _picker = ImagePicker();
  final AiSensorService _aiService = AiSensorService();

  bool _isCameraInitialized = false;
  bool _isPermissionDenied = false;
  bool _isLoading = false;

  XFile? _imageFile;
  SensorResult? _resultado;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    if (widget.isActive) {
      _initCamera();
    }
  }

  @override
  void didUpdateWidget(covariant IdentificadorPage oldWidget) {
    super.didUpdateWidget(oldWidget);

    if (oldWidget.isActive && !widget.isActive) {
      _pausarCamera();
    } else if (!oldWidget.isActive && widget.isActive) {
      _initCamera();
    }
  }

  Future<void> _pausarCamera() async {
    final controller = _cameraController;
    _cameraController = null;
    if (mounted) {
      setState(() => _isCameraInitialized = false);
    }
    await controller?.dispose();
  }

  Future<void> _initCamera() async {
    if (_cameraController != null) return; 

    if (!kIsWeb) {
      final status = await Permission.camera.request();
      if (!status.isGranted) {
        if (mounted) setState(() => _isPermissionDenied = true);
        return;
      }
    }

    try {
      final cameras = await availableCameras();
      if (cameras.isEmpty) {
        if (mounted) {
          setState(() {
            _errorMessage = "Nenhuma câmera encontrada no dispositivo.";
            _isCameraInitialized = false;
          });
        }
        return;
      }

      _cameraController = CameraController(
        cameras.first,
        ResolutionPreset.high,
        enableAudio: false,
      );

      await _cameraController!.initialize();
      if (mounted) {
        setState(() {
          _isCameraInitialized = true;
          _isPermissionDenied = false;
          _errorMessage = null;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _isCameraInitialized = false;
          if (kIsWeb) {
            _errorMessage =
                "Câmera bloqueada ou indisponível.\nCertifique-se de estar usando 'localhost' ou 'https://' e permita o acesso no navegador.";
          } else {
            _errorMessage = "Erro ao carregar câmera: $e";
          }
        });
      }
    }
  }

  @override
  void dispose() {
    // CORRIGIDO (item 1 do pedido: leitura continuava em segundo plano
    // após sair da tela): TtsService é um singleton que sobrevive além
    // do ciclo de vida desta tela, então uma leitura em andamento nunca
    // parava sozinha ao navegar para outra página. Chamado sem "await"
    // de propósito — dispose() é síncrono e o widget já está sendo
    // destruído, então só precisamos disparar o stop(), não esperá-lo.
    TtsService.instance.stop();
    _cameraController?.dispose();
    super.dispose();
  }

  Future<void> _processarImagem(XFile image) async {
    setState(() {
      _imageFile = image;
      _isLoading = true;
      _errorMessage = null;
      _resultado = null;
    });

    try {
      final res = await _aiService.analisarImagem(image);
      if (mounted) {
        setState(() => _resultado = res);
      }
    } catch (e) {
      if (mounted) {
        setState(
          () => _errorMessage = e is IdentificacaoException
              ? e.mensagem
              : "Erro ao identificar o sensor. Tente novamente.",
        );
      }
    } finally {
      if (mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  Future<void> _tirarFoto() async {
    if (_cameraController == null || !_cameraController!.value.isInitialized) {
      return;
    }

    try {
      final XFile foto = await _cameraController!.takePicture();
      await _processarImagem(foto);
    } catch (e) {
      setState(() => _errorMessage = "Erro ao capturar a foto.");
    }
  }

  Future<void> _abrirGaleria() async {
    try {
      final XFile? foto = await _picker.pickImage(source: ImageSource.gallery);
      if (foto != null) {
        await _processarImagem(foto);
      }
    } catch (e) {
      setState(() => _errorMessage = "Erro ao abrir a galeria.");
    }
  }

  void _limparResultado() {
    setState(() {
      _imageFile = null;
      _resultado = null;
      _errorMessage = null;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/logos/Logo/LogoDark2.png', height: 40),
            const SizedBox(width: 10),
            const Text('Identificador'),
          ],
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              if (_errorMessage != null)
                Container(
                  padding: const EdgeInsets.all(12),
                  margin: const EdgeInsets.only(bottom: 16),
                  decoration: BoxDecoration(
                    color: AppColors.danger.withValues(alpha: 0.2),
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(color: AppColors.danger),
                  ),
                  child: Text(
                    _errorMessage!,
                    style: const TextStyle(color: Colors.white),
                  ),
                ),

              _buildCameraCard(),
              const SizedBox(height: 20),

              _buildPrimaryButton(),
              const SizedBox(height: 10),

              _buildSecondaryButton(),
              const SizedBox(height: 20),

              if (_resultado != null) _buildResultadoCard(context),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildCameraCard() {
    return Container(
      height: 260,
      decoration: BoxDecoration(
        color: AppColors.bgCardAlt,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppColors.border),
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(16),
        child: _buildCameraContent(),
      ),
    );
  }

  Widget _buildCameraContent() {
    if (_imageFile != null) {
      return Stack(
        fit: StackFit.expand,
        children: [
          kIsWeb
              ? Image.network(_imageFile!.path, fit: BoxFit.cover)
              : Image.file(File(_imageFile!.path), fit: BoxFit.cover),

          if (_isLoading)
            Container(
              color: Colors.black54,
              child: const Center(
                child: CircularProgressIndicator(color: AppColors.primary),
              ),
            ),
          if (!_isLoading)
            Positioned(
              top: 8,
              right: 8,
              child: IconButton(
                icon: const Icon(Icons.close, color: Colors.white),
                style: IconButton.styleFrom(backgroundColor: Colors.black54),
                onPressed: _limparResultado,
              ),
            ),
        ],
      );
    }

    if (_isPermissionDenied && !kIsWeb) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.videocam_off, size: 60, color: Colors.white54),
            const SizedBox(height: 12),
            const Text(
              'Permissão de câmera negada',
              style: TextStyle(color: Colors.white54),
            ),
            TextButton(
              onPressed: () => openAppSettings(),
              child: const Text(
                'Abrir configurações',
                style: TextStyle(color: AppColors.primaryLight),
              ),
            ),
          ],
        ),
      );
    }

    if (_errorMessage != null && !_isCameraInitialized) {
      return Center(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.error_outline, size: 50, color: Colors.white54),
              const SizedBox(height: 12),
              Text(
                _errorMessage!,
                textAlign: TextAlign.center,
                style: const TextStyle(color: Colors.white54, fontSize: 14),
              ),
            ],
          ),
        ),
      );
    }

    if (!_isCameraInitialized) {
      return const Center(
        child: CircularProgressIndicator(color: AppColors.primary),
      );
    }

    return CameraPreview(_cameraController!);
  }

  Widget _buildPrimaryButton() {
    return SizedBox(
      width: double.infinity,
      child: ElevatedButton.icon(
        onPressed: (_isLoading || _imageFile != null) ? null : _tirarFoto,
        icon: const Icon(Icons.camera_alt),
        label: const Text('Identificar sensor'),
      ),
    );
  }

  Widget _buildSecondaryButton() {
    return SizedBox(
      width: double.infinity,
      child: OutlinedButton.icon(
        onPressed: _isLoading ? null : _abrirGaleria,
        icon: const Icon(Icons.photo),
        label: const Text('Usar galeria'),
      ),
    );
  }

  Widget _imagemResultado(String caminho) {
    Widget errorBuilder(BuildContext c, Object e, StackTrace? s) =>
        const Icon(Icons.image_not_supported, size: 60, color: Colors.white54);

    if (caminho.isEmpty) {
      return errorBuilder(context, '', null);
    }

    return Image.network(
      caminho,
      height: 120,
      fit: BoxFit.contain,
      errorBuilder: errorBuilder,
    );
  }

  Widget _buildResultadoCard(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: context.surface,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppColors.primary.withValues(alpha: 0.5)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.check_circle, color: AppColors.success),
              const SizedBox(width: 8),
              Expanded(
                child: Text(
                  _resultado!.nome,
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: context.textPrimary,
                  ),
                ),
              ),
              TtsButton(
                text:
                    '${_resultado!.nome}. ${_resultado!.descricao}'
                    '${_resultado!.circuito.isNotEmpty ? '. Circuito: ${_resultado!.circuito}' : ''}',
                tooltip: 'Ouvir resultado',
              ),
            ],
          ),
          const SizedBox(height: 4),
          Text(
            'Confiança: ${(_resultado!.confianca * 100).toStringAsFixed(1)}%',
            style: TextStyle(color: context.textSoft, fontSize: 13),
          ),
          const SizedBox(height: 16),
          Center(
            child: ClipRRect(
              borderRadius: BorderRadius.circular(8),
              child: _imagemResultado(_resultado!.imagemUrl),
            ),
          ),
          const SizedBox(height: 16),
          const Text(
            'Como funciona:',
            style: TextStyle(
              fontWeight: FontWeight.w600,
              color: AppColors.primaryLight,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            _resultado!.descricao,
            style: TextStyle(color: context.textSoft, height: 1.4),
          ),
          if (_resultado!.circuito.isNotEmpty) ...[
            const SizedBox(height: 16),
            const Text(
              'Circuito:',
              style: TextStyle(
                fontWeight: FontWeight.w600,
                color: AppColors.primaryLight,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              _resultado!.circuito,
              style: TextStyle(color: context.textSoft, height: 1.4),
            ),
          ],
        ],
      ),
    );
  }
}
