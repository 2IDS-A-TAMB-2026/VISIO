import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

import 'appcolor.dart';

class IdentificadorPage extends StatefulWidget {
  const IdentificadorPage({super.key});

  @override
  State<IdentificadorPage> createState() => _IdentificadorPageState();
}

class _IdentificadorPageState extends State<IdentificadorPage> {
  bool _analisando = false;
  bool _temResultado = false;

  Uint8List? _imagemBytes;
  final ImagePicker _picker = ImagePicker();

  // ─────────────────────────────────────────────
  // AÇÕES
  // ─────────────────────────────────────────────

  Future<void> _identificar() async {
    if (_analisando) return;

    try {
      final file = await _picker.pickImage(
        source: ImageSource.camera,
        imageQuality: 90,
      );

      if (file == null) return;

      final bytes = await file.readAsBytes();

      await _processarImagem(bytes);
    } catch (_) {
      _mostrarErro('Erro ao abrir câmera');
    }
  }

  Future<void> _usarGaleria() async {
    try {
      final file = await _picker.pickImage(
        source: ImageSource.gallery,
        imageQuality: 90,
      );

      if (file == null) return;

      final bytes = await file.readAsBytes();
      await _processarImagem(bytes);
    } catch (_) {
      _mostrarErro('Erro ao acessar galeria');
    }
  }

  Future<void> _processarImagem(Uint8List bytes) async {
    setState(() {
      _analisando = true;
      _temResultado = false;
      _imagemBytes = bytes;
    });

    await Future.delayed(const Duration(seconds: 2));

    if (!mounted) return;

    setState(() {
      _analisando = false;
      _temResultado = true;
    });
  }

  void _limpar() {
    setState(() {
      _temResultado = false;
      _analisando = false;
      _imagemBytes = null;
    });
  }

  void _mostrarErro(String msg) {
    if (!mounted) return;

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(msg), backgroundColor: AppColors.danger),
    );
  }

  // ─────────────────────────────────────────────
  // UI
  // ─────────────────────────────────────────────

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.bgBase,
      appBar: AppBar(
        title: const Text('Identificador'),
        centerTitle: true,
        actions: [
          if (_temResultado)
            IconButton(icon: const Icon(Icons.refresh), onPressed: _limpar),
        ],
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              _buildCameraCard(),
              const SizedBox(height: 20),

              _buildPrimaryButton(),
              const SizedBox(height: 10),

              _buildSecondaryButton(),

              if (_temResultado) ...[
                const SizedBox(height: 20),
                _buildResultadoCard(),
              ],
            ],
          ),
        ),
      ),
    );
  }

  // ─────────────────────────────────────────────
  // COMPONENTES
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
    if (_analisando && _imagemBytes != null) {
      return Stack(
        fit: StackFit.expand,
        children: [
          Image.memory(_imagemBytes!, fit: BoxFit.cover),
          Container(color: Colors.black54),
          const Center(child: CircularProgressIndicator()),
        ],
      );
    }

    if (_temResultado && _imagemBytes != null) {
      return Image.memory(_imagemBytes!, fit: BoxFit.cover);
    }

    return const Center(
      child: Icon(Icons.camera_alt, size: 48, color: Colors.white54),
    );
  }

  Widget _buildPrimaryButton() {
    return SizedBox(
      width: double.infinity,
      child: ElevatedButton.icon(
        onPressed: _analisando ? null : _identificar,
        icon: Icon(_analisando ? Icons.hourglass_top : Icons.camera_alt),
        label: Text(_analisando ? 'Analisando...' : 'Identificar sensor'),
      ),
    );
  }

  Widget _buildSecondaryButton() {
    return SizedBox(
      width: double.infinity,
      child: OutlinedButton.icon(
        onPressed: _usarGaleria,
        icon: const Icon(Icons.photo),
        label: const Text('Usar galeria'),
      ),
    );
  }

  Widget _buildResultadoCard() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.bgCardAlt,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: const [
          Text(
            'Sensor identificado',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
          SizedBox(height: 10),
          Text('DHT11'),
          Text('Confiança: 92%'),
          Text('Categoria: Temperatura / Umidade'),
          Text('Tensão: 3.3V – 5V'),
        ],
      ),
    );
  }
}
