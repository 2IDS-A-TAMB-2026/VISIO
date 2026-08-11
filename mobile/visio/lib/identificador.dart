import 'dart:io';
import 'dart:typed_data';
import 'dart:ui' as ui;
import 'package:flutter/material.dart';
import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:camera/camera.dart';
import 'package:image_picker/image_picker.dart';
import 'package:permission_handler/permission_handler.dart';

import 'appcolor.dart';
import 'widgets/tts_button.dart';

class SensorResult {
  final String nome;
  final String descricao;
  final String imagemUrl;

  const SensorResult({
    required this.nome,
    required this.descricao,
    required this.imagemUrl,
  });
}

class AiSensorService {
  // Mesmo catálogo de 9 sensores exibido em sensores.dart, para manter
  // consistência entre as duas telas.
  static const List<SensorResult> _catalogo = [
    SensorResult(
      nome: 'Sensor de Temperatura',
      descricao:
          'Identifica variações de calor ou frio em um ambiente ou objeto.',
      imagemUrl: 'assets/images/Sensores/sensor_temperatura.png',
    ),
    SensorResult(
      nome: 'Sensor de Proximidade',
      descricao: 'Detecta quando um objeto está próximo sem contato físico.',
      imagemUrl: 'assets/images/Sensores/sensor_proximidade.png',
    ),
    SensorResult(
      nome: 'Sensor de Umidade',
      descricao: 'Mede a quantidade de vapor de água presente no ar.',
      imagemUrl: 'assets/images/Sensores/sensor_umidade.png',
    ),
    SensorResult(
      nome: 'Sensor de Luz (LDR)',
      descricao: 'Mede a intensidade luminosa do ambiente.',
      imagemUrl: 'assets/images/Sensores/sensor_luz.png',
    ),
    SensorResult(
      nome: 'Sensor de Movimento (PIR)',
      descricao:
          'Detecta presença através da variação de calor corporal (PIR).',
      imagemUrl: 'assets/images/Sensores/sensor_movimento.png',
    ),
    SensorResult(
      nome: 'Sensor Ultrassônico (HC-SR04)',
      descricao:
          'Mede distâncias enviando ondas sonoras de alta frequência e '
          'calculando o tempo que levam para retornar após colidir com um '
          'objeto.',
      imagemUrl: 'assets/images/Sensores/sensor_ultrassonico.png',
    ),
    SensorResult(
      nome: 'Sensor de Gás / Fumaça',
      descricao: 'Identifica gases inflamáveis ou fumaça no ambiente.',
      imagemUrl: 'assets/images/Sensores/sensor_gas.png',
    ),
    SensorResult(
      nome: 'Sensor de Pressão',
      descricao:
          'Mede a pressão atmosférica para indicar clima ou altitude.',
      imagemUrl: 'assets/images/Sensores/sensor_pressao.png',
    ),
    SensorResult(
      nome: 'Sensor de Toque',
      descricao: 'Reconhece o contato físico direto na superfície.',
      imagemUrl: 'assets/images/Sensores/sensor_toque.png',
    ),
  ];

  /// ATENÇÃO: isto NÃO é um modelo de visão computacional treinado — é uma
  /// heurística simples baseada na cor/brilho médio da imagem, usada
  /// apenas para que o resultado varie conforme a foto tirada. Antes deste
  /// método, a "análise" ignorava completamente a imagem recebida e sempre
  /// devolvia o mesmo sensor fixo (Sensor Ultrassônico), para qualquer
  /// foto. Uma integração real de IA/visão computacional (ex.: um modelo
  /// TFLite embarcado, ou uma chamada a um serviço de inferência) deve
  /// substituir a lógica de [_indicePorHeuristica] no futuro — a assinatura
  /// pública de [analisarImagem] não precisa mudar para isso.
  Future<SensorResult> analisarImagem(XFile imagem) async {
    await Future.delayed(const Duration(seconds: 2)); // latência simulada

    int indice = 0;
    try {
      final bytes = await imagem.readAsBytes();
      indice = await _indicePorHeuristica(bytes);
    } catch (_) {
      // Se a decodificação falhar por qualquer motivo, cai para um
      // resultado padrão em vez de propagar a exceção para a tela.
      indice = 0;
    }

    return _catalogo[indice % _catalogo.length];
  }

  Future<int> _indicePorHeuristica(Uint8List bytes) async {
    // Decodifica em uma miniatura minúscula (8x8) só para ler a cor média —
    // suficiente para variar o resultado sem custo de processamento real.
    final codec = await ui.instantiateImageCodec(
      bytes,
      targetWidth: 8,
      targetHeight: 8,
    );
    final frame = await codec.getNextFrame();
    final byteData = await frame.image.toByteData(
      format: ui.ImageByteFormat.rawRgba,
    );
    frame.image.dispose();

    if (byteData == null || byteData.lengthInBytes < 4) {
      return bytes.length % _catalogo.length;
    }

    final pixels = byteData.buffer.asUint8List();
    final totalPixels = pixels.length ~/ 4;
    if (totalPixels == 0) return bytes.length % _catalogo.length;

    var somaR = 0, somaG = 0, somaB = 0;
    for (var i = 0; i + 3 < pixels.length; i += 4) {
      somaR += pixels[i];
      somaG += pixels[i + 1];
      somaB += pixels[i + 2];
    }

    final mediaR = somaR ~/ totalPixels;
    final mediaG = somaG ~/ totalPixels;
    final mediaB = somaB ~/ totalPixels;

    // Combina os canais de cor com o tamanho do arquivo para variar o
    // índice escolhido de foto para foto.
    return (mediaR + mediaG * 2 + mediaB * 3 + bytes.length) %
        _catalogo.length;
  }
}

class IdentificadorPage extends StatefulWidget {
  /// Controlado pelo MainShell (ver main.dart): true apenas quando esta é
  /// a aba atualmente visível. Usado para pausar a câmera quando o usuário
  /// navega para outra aba — antes, por ficar em um IndexedStack, a câmera
  /// permanecia ativa (capturando frames) mesmo em outras abas, pois seu
  /// dispose() só era chamado ao fechar o app inteiro.
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

  XFile? _imageFile; // <--- Alterado de File para XFile
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
      // Usuário saiu desta aba: libera a câmera em vez de deixá-la
      // capturando frames em segundo plano.
      _pausarCamera();
    } else if (!oldWidget.isActive && widget.isActive) {
      // Usuário voltou para esta aba: reabre a câmera.
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
    if (_cameraController != null) return; // já inicializada

    // Na Web, a permissão é solicitada automaticamente pelo navegador ao tentar acessar a câmera.
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
          _errorMessage = null; // Limpa erros anteriores se tiver sucesso
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
          () =>
              _errorMessage = "Erro ao identificar o sensor. Tente novamente.",
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
      await _processarImagem(foto); // <--- Passando o XFile diretamente
    } catch (e) {
      setState(() => _errorMessage = "Erro ao capturar a foto.");
    }
  }

  Future<void> _abrirGaleria() async {
    try {
      final XFile? foto = await _picker.pickImage(source: ImageSource.gallery);
      if (foto != null) {
        await _processarImagem(foto); // <--- Passando o XFile diretamente
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
      backgroundColor: AppColors.bgBase,
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
                    color: AppColors.danger.withOpacity(0.2),
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

              if (_resultado != null) _buildResultadoCard(),
            ],
          ),
        ),
      ),
    );
  }

  // ─────────────────────────────────────────────
  // COMPONENTES PRESERVADOS E ADAPTADOS
  // ─────────────────────────────────────────────

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

    // Exibe a mensagem de erro da Web (ou mobile) no centro do quadro
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

  Widget _buildResultadoCard() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.surfaceDark,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppColors.primary.withOpacity(0.5)),
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
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: AppColors.textPrimary,
                  ),
                ),
              ),
              TtsButton(
                text: '${_resultado!.nome}. ${_resultado!.descricao}',
                tooltip: 'Ouvir resultado',
              ),
            ],
          ),
          const SizedBox(height: 16),
          Center(
            child: ClipRRect(
              borderRadius: BorderRadius.circular(8),
              child: Image.asset(
                _resultado!.imagemUrl,
                height: 120,
                fit: BoxFit.contain,
                errorBuilder: (c, e, s) => const Icon(
                  Icons.image_not_supported,
                  size: 60,
                  color: Colors.white54,
                ),
              ),
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
            style: const TextStyle(color: AppColors.textSoft, height: 1.4),
          ),
        ],
      ),
    );
  }
}
