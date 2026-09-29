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
import 'widgets/accessibility_panel.dart';
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
            // getUserMedia() só funciona em "contexto seguro": HTTPS, ou
            // http://localhost / http://127.0.0.1. Rodando em qualquer
            // outro endereço (ex.: o IP da rede, http://10.x.x.x:5000) o
            // navegador bloqueia a câmera por conta própria — não é algo
            // que o app consiga contornar. Ver:
            // https://developer.mozilla.org/docs/Web/API/MediaDevices/getUserMedia
            final origemInsegura = !Uri.base.isScheme('https') &&
                Uri.base.host != 'localhost' &&
                Uri.base.host != '127.0.0.1';

            final porta = Uri.base.hasPort ? ':${Uri.base.port}' : '';
            final urlLocalhost = 'http://localhost$porta';

            _errorMessage = origemInsegura
                ? "O navegador bloqueia a câmera neste endereço (${Uri.base.host}) "
                    "porque não é uma conexão segura.\n"
                    "Abra o app por $urlLocalhost nesta mesma máquina, ou publique com HTTPS."
                : "Câmera bloqueada ou indisponível.\nCertifique-se de permitir o acesso no navegador.";
          } else {
            _errorMessage = "Erro ao carregar câmera: $e";
          }
        });
      }
    }
  }

  @override
  void dispose() {
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
    if (widget.isActive) {
      TtsService.instance.definirTextoDaPagina(
        _resultado != null
            ? '${_resultado!.nome}. ${_resultado!.descricao}'
                '${_resultado!.circuito.isNotEmpty ? '. Circuito: ${_resultado!.circuito}' : ''}'
            : 'Tela de identificação de sensores por câmera. Aponte a câmera '
                'para um sensor ou escolha uma imagem da galeria para identificar.',
      );
    }

    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;
    final titleTextColor = isDark ? Colors.white : const Color(0xFF0F172A);
    final bodyTextColor = isDark
        ? const Color(0xFF94A3B8)
        : const Color(0xFF475569);

    return Scaffold(
      body: Container(
        decoration: BoxDecoration(
          gradient: isDark
              ? const RadialGradient(
                  center: Alignment(0, -0.4),
                  radius: 1.2,
                  colors: [Color(0xFF0F172A), Color(0xFF030712)],
                )
              : null,
          color: isDark ? null : const Color(0xFFF8FAFC),
        ),
        child: Stack(
          children: [
            Column(
              children: [
                _buildTopNavBar(context, isDark, titleTextColor, bodyTextColor),
                Expanded(
                  child: SingleChildScrollView(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 24,
                      vertical: 32,
                    ),
                    child: Center(
                      child: Container(
                        constraints: const BoxConstraints(maxWidth: 800),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.center,
                          children: [
                            ShaderMask(
                              shaderCallback: (bounds) => const LinearGradient(
                                colors: [
                                  Color(0xFF60A5FA), // Azul suave
                                  Color(0xFF3B82F6), // Azul primário
                                  Color(0xFF06B6D4), // Ciano
                                ],
                                begin: Alignment.topLeft,
                                end: Alignment.bottomRight,
                              ).createShader(bounds),
                              child: Text(
                                'Identificação de dispositivos via câmera',
                                textAlign: TextAlign.center,
                                style: TextStyle(
                                  fontSize: 34,
                                  fontWeight: FontWeight.w800, // Corrigido aqui
                                  letterSpacing: -0.5,
                                  color: Colors.white,
                                  shadows: [
                                    Shadow(
                                      color: const Color(
                                        0xFF2563EB,
                                      ).withValues(alpha: 0.3),
                                      blurRadius: 20,
                                      offset: const Offset(0, 4),
                                    ),
                                  ],
                                ),
                              ),
                            ),
                            const SizedBox(height: 32),

                            if (_errorMessage != null)
                              Container(
                                width: double.infinity,
                                padding: const EdgeInsets.all(12),
                                margin: const EdgeInsets.only(bottom: 20),
                                decoration: BoxDecoration(
                                  color: AppColors.danger.withValues(alpha: 0.2),
                                  borderRadius: BorderRadius.circular(8),
                                  border: Border.all(color: AppColors.danger),
                                ),
                                child: Text(
                                  _errorMessage!,
                                  textAlign: TextAlign.center,
                                  style: const TextStyle(color: Colors.white),
                                ),
                              ),

                            _buildCameraCard(isDark),
                            const SizedBox(height: 24),

                            Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                ElevatedButton.icon(
                                  onPressed: (_isLoading || _imageFile != null)
                                      ? null
                                      : _tirarFoto,
                                  icon: const Icon(Icons.camera_alt, size: 20),
                                  label: const Text(
                                    'Identificar',
                                    style: TextStyle(
                                      fontSize: 16,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: const Color(0xFF2563EB),
                                    foregroundColor: Colors.white,
                                    padding: const EdgeInsets.symmetric(
                                      horizontal: 32,
                                      vertical: 16,
                                    ),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(12),
                                    ),
                                    elevation: 4,
                                  ),
                                ),
                                const SizedBox(width: 16),
                                OutlinedButton.icon(
                                  onPressed: _isLoading ? null : _abrirGaleria,
                                  icon: const Icon(Icons.photo, size: 20),
                                  label: const Text('Galeria'),
                                  style: OutlinedButton.styleFrom(
                                    foregroundColor: titleTextColor,
                                    side: BorderSide(
                                      color: isDark
                                          ? const Color(0xFF334155)
                                          : const Color(0xFFCBD5E1),
                                    ),
                                    padding: const EdgeInsets.symmetric(
                                      horizontal: 24,
                                      vertical: 16,
                                    ),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(12),
                                    ),
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 32),

                            if (_resultado != null)
                              _buildResultadoCard(
                                context,
                                isDark,
                                titleTextColor,
                                bodyTextColor,
                              ),
                          ],
                        ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
            const AccessibilityPanel(),
          ],
        ),
      ),
    );
  }

  Widget _buildTopNavBar(
    BuildContext context,
    bool isDark,
    Color titleColor,
    Color textColor,
  ) {
    return Container(
      height: 60,
      padding: const EdgeInsets.symmetric(horizontal: 16),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF030712).withValues(alpha: 0.8) : Colors.white,
        border: Border(
          bottom: BorderSide(
            color: isDark ? const Color(0xFF1E293B) : const Color(0xFFE2E8F0),
          ),
        ),
      ),
      child: Row(
        children: [
          Image.asset(
            isDark
                ? 'assets/images/logos/Logo/LogoDark2.png'
                : 'assets/images/logos/Logo/LogoLight2.png',
            height: 28,
          ),
          const SizedBox(width: 8),
        ],
      ),
    );
  }

  Widget _buildCameraCard(bool isDark) {
    return Container(
      height: 420,
      width: double.infinity,
      decoration: BoxDecoration(
        color: isDark ? Colors.black : Colors.grey.shade900,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFF2563EB), width: 2),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF2563EB).withValues(alpha: 0.2),
            blurRadius: 16,
            spreadRadius: 2,
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(18),
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
                child: CircularProgressIndicator(color: Color(0xFF2563EB)),
              ),
            ),
          if (!_isLoading)
            Positioned(
              top: 12,
              right: 12,
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
                style: TextStyle(color: Color(0xFF2563EB)),
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
        child: CircularProgressIndicator(color: Color(0xFF2563EB)),
      );
    }

    return CameraPreview(_cameraController!);
  }

  Widget _imagemResultado(String caminho) {
    Widget errorBuilder(BuildContext c, Object e, StackTrace? s) =>
        const Icon(Icons.image_not_supported, size: 60, color: Colors.white54);

    if (caminho.isEmpty) {
      return errorBuilder(context, '', null);
    }

    // Foto do sensor vem do Apache (outra origem que o Flutter Web, sem
    // CORS em arquivos estáticos): usa <img> em vez de baixar os bytes.
    return Image.network(
      caminho,
      height: 140,
      fit: BoxFit.contain,
      webHtmlElementStrategy: WebHtmlElementStrategy.prefer,
      errorBuilder: errorBuilder,
    );
  }

  Widget _buildResultadoCard(
    BuildContext context,
    bool isDark,
    Color titleColor,
    Color textColor,
  ) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF0B132B) : Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFF2563EB).withValues(alpha: 0.5)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: isDark ? 0.4 : 0.05),
            blurRadius: 12,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.check_circle, color: Color(0xFF10B981)),
              const SizedBox(width: 8),
              Expanded(
                child: Text(
                  _resultado!.nome,
                  style: TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                    color: titleColor,
                  ),
                ),
              ),
              // ATUALIZADO — o painel de acessibilidade não tem mais o
              // switch "Modo fala" (virou o botão "Ler página em voz alta",
              // que lê a tela inteira via TtsService.textoPagina, veja
              // AccessibilityPanel). TtsService.enabled não tem mais
              // nenhum controle de UI que o desligue, então este botão de
              // ouvir o resultado agora aparece sempre — TtsButton já
              // observa TtsService sozinho para atualizar o ícone.
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
            style: TextStyle(color: textColor, fontSize: 13),
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
              fontWeight: FontWeight.bold,
              color: Color(0xFF0284C7),
            ),
          ),
          const SizedBox(height: 8),
          Text(
            _resultado!.descricao,
            style: TextStyle(color: textColor, height: 1.5),
          ),
          if (_resultado!.circuito.isNotEmpty) ...[
            const SizedBox(height: 16),
            const Text(
              'Circuito:',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: Color(0xFF0284C7),
              ),
            ),
            const SizedBox(height: 8),
            Text(
              _resultado!.circuito,
              style: TextStyle(color: textColor, height: 1.5),
            ),
          ],
        ],
      ),
    );
  }
}
