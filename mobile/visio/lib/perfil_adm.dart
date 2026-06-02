import 'dart:typed_data';
import 'dart:io';

import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:image_picker/image_picker.dart';
import 'package:path_provider/path_provider.dart';

import 'appcolor.dart';

class PerfilAdminPage extends StatefulWidget {
  const PerfilAdminPage({super.key});

  @override
  State<PerfilAdminPage> createState() => _PerfilAdminPageState();
}

class _PerfilAdminPageState extends State<PerfilAdminPage> {
  final nomeCtrl = TextEditingController();
  final telefoneCtrl = TextEditingController();
  final emailCtrl = TextEditingController();

  final ImagePicker _picker = ImagePicker();

  Uint8List? _fotoBytes;
  String? _fotoPath;

  bool _loading = true;
  bool _salvando = false;
  bool _salvo = false;

  @override
  void initState() {
    super.initState();
    _carregar();
  }

  Future<void> _carregar() async {
    final prefs = await SharedPreferences.getInstance();

    nomeCtrl.text = prefs.getString('nome_adm') ?? '';
    telefoneCtrl.text = prefs.getString('tel_adm') ?? '';
    emailCtrl.text = prefs.getString('email_adm') ?? '';

    _fotoPath = prefs.getString('foto_adm');

    if (_fotoPath != null) {
      final file = File(_fotoPath!);
      if (await file.exists()) {
        _fotoBytes = await file.readAsBytes();
      }
    }

    if (!mounted) return;
    setState(() => _loading = false);
  }

  Future<void> _salvar() async {
    setState(() => _salvando = true);

    final prefs = await SharedPreferences.getInstance();

    await prefs.setString('nome_adm', nomeCtrl.text.trim());
    await prefs.setString('tel_adm', telefoneCtrl.text.trim());
    await prefs.setString('email_adm', emailCtrl.text.trim());

    if (_fotoPath != null) {
      await prefs.setString('foto_adm', _fotoPath!);
    }

    if (!mounted) return;

    setState(() {
      _salvando = false;
      _salvo = true;
    });

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Perfil atualizado com sucesso!')),
    );

    await Future.delayed(const Duration(seconds: 2));

    if (mounted) {
      setState(() => _salvo = false);
    }
  }

  Future<void> _selecionarFoto() async {
    try {
      final image = await _picker.pickImage(source: ImageSource.gallery);
      if (image == null) return;

      final bytes = await image.readAsBytes();

      final dir = await getApplicationDocumentsDirectory();
      final file = File('${dir.path}/foto_adm.png');

      await file.writeAsBytes(bytes);

      if (!mounted) return;

      setState(() {
        _fotoBytes = bytes;
        _fotoPath = file.path;
      });
    } catch (_) {
      if (!mounted) return;

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao selecionar imagem')),
      );
    }
  }

  void _logout() {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        backgroundColor: AppColors.bgCardAlt,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Sair da conta'),
        content: const Text(
          'Tem certeza que deseja sair?',
          style: TextStyle(color: AppColors.textMuted),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancelar'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () => Navigator.popUntil(context, (r) => r.isFirst),
            child: const Text('Sair'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false,
        title: const Text('Meu Perfil'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout, color: AppColors.danger),
            onPressed: _logout,
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            GestureDetector(
              onTap: _selecionarFoto,
              child: Stack(
                alignment: Alignment.center,
                children: [
                  Container(
                    width: 104,
                    height: 104,
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      color: AppColors.primary.withOpacity(0.15),
                      border: Border.all(color: AppColors.border),
                    ),
                    child: ClipOval(
                      child: _fotoBytes != null
                          ? Image.memory(
                              _fotoBytes!,
                              fit: BoxFit.cover,
                              width: 104,
                              height: 104,
                            )
                          : const Icon(Icons.admin_panel_settings, size: 40),
                    ),
                  ),

                  Positioned(
                    bottom: 0,
                    right: 0,
                    child: Container(
                      padding: const EdgeInsets.all(6),
                      decoration: BoxDecoration(
                        color: AppColors.primary,
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(
                        Icons.camera_alt,
                        size: 14,
                        color: Colors.white,
                      ),
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 16),

            TextField(
              controller: nomeCtrl,
              decoration: const InputDecoration(labelText: 'Nome'),
            ),

            const SizedBox(height: 12),

            TextField(
              controller: emailCtrl,
              keyboardType: TextInputType.emailAddress,
              decoration: const InputDecoration(labelText: 'Email'),
            ),

            const SizedBox(height: 12),

            TextField(
              controller: telefoneCtrl,
              keyboardType: TextInputType.phone,
              decoration: const InputDecoration(labelText: 'Telefone'),
            ),

            const SizedBox(height: 24),

            SizedBox(
              width: double.infinity,
              child: _salvando
                  ? const Center(child: CircularProgressIndicator())
                  : ElevatedButton(
                      onPressed: _salvar,
                      child: Text(_salvo ? 'Salvo!' : 'Salvar'),
                    ),
            ),
          ],
        ),
      ),
    );
  }

  @override
  void dispose() {
    nomeCtrl.dispose();
    telefoneCtrl.dispose();
    emailCtrl.dispose();
    super.dispose();
  }
}
