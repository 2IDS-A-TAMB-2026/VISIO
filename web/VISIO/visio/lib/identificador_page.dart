import 'package:flutter/material.dart';
import 'package:camera/camera.dart';

class IdentificadorScreen extends StatefulWidget {
  const IdentificadorScreen({super.key});

  @override
  State<IdentificadorScreen> createState() => _IdentificadorScreenState();
}

class _IdentificadorScreenState extends State<IdentificadorScreen> {
  CameraController? _controller;
  String resultado = "Aguardando identificação...";

  @override
  void initState() {
    super.initState();
    _iniciarCamera();
  }

  Future<void> _iniciarCamera() async {
    final cameras = await availableCameras();
    final camera = cameras.firstWhere(
      (cam) => cam.lensDirection == CameraLensDirection.back,
    );

    _controller = CameraController(
      camera,
      ResolutionPreset.medium,
      enableAudio: false,
    );

    await _controller!.initialize();

    if (!mounted) return;
    setState(() {});
  }

  @override
  void dispose() {
    _controller?.dispose();
    super.dispose();
  }

  void _simularIdentificacao() {
    setState(() {
      resultado = "Dispositivo identificado:\nSensor IoT XYZ";
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      body: _controller == null || !_controller!.value.isInitialized
          ? const Center(child: CircularProgressIndicator())
          : Stack(
              children: [
                /// CÂMERA FULLSCREEN
                Positioned.fill(child: CameraPreview(_controller!)),

                /// OVERLAY ESCURECIDO
                Positioned.fill(
                  child: Container(color: Colors.black.withOpacity(0.4)),
                ),

                /// ÁREA DE FOCO CENTRAL
                Center(
                  child: Container(
                    width: 250,
                    height: 250,
                    decoration: BoxDecoration(
                      border: Border.all(
                        color: const Color(0xFF1E6BE7),
                        width: 3,
                      ),
                      borderRadius: BorderRadius.circular(20),
                    ),
                  ),
                ),

                /// TEXTO SUPERIOR
                Positioned(
                  top: 60,
                  left: 20,
                  right: 20,
                  child: Column(
                    children: const [
                      Icon(Icons.camera_alt, color: Colors.white, size: 28),
                      SizedBox(height: 10),
                      Text(
                        "Posicione o dispositivo na área destacada",
                        textAlign: TextAlign.center,
                        style: TextStyle(color: Colors.white, fontSize: 16),
                      ),
                    ],
                  ),
                ),

                /// RESULTADO FLUTUANTE
                Positioned(
                  bottom: 120,
                  left: 20,
                  right: 20,
                  child: Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: const Color(0xFF121212),
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(color: const Color(0xFF1E6BE7)),
                    ),
                    child: Text(
                      resultado,
                      textAlign: TextAlign.center,
                      style: const TextStyle(color: Colors.white, fontSize: 16),
                    ),
                  ),
                ),

                /// BOTÃO DE AÇÃO
                Positioned(
                  bottom: 40,
                  left: 0,
                  right: 0,
                  child: Center(
                    child: FloatingActionButton(
                      backgroundColor: const Color(0xFF1E6BE7),
                      onPressed: _simularIdentificacao,
                      child: const Icon(Icons.search),
                    ),
                  ),
                ),
              ],
            ),
    );
  }
}
