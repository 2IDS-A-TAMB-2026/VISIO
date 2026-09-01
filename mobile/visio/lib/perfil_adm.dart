import 'dart:typed_data';

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

import 'appcolor.dart';
import 'login.dart';
import 'services/api_client.dart';
import 'services/api_config.dart';
import 'services/auth_service.dart';

class PerfilAdminPage extends StatefulWidget {
  const PerfilAdminPage({super.key});

  @override
  State<PerfilAdminPage> createState() => _PerfilAdminPageState();
}

class _PerfilAdminPageState extends State<PerfilAdminPage> {
  final nomeCtrl = TextEditingController();
  final telefoneCtrl = TextEditingController();
  final emailCtrl = TextEditingController();
  final senhaCtrl = TextEditingController();

  final ImagePicker _picker = ImagePicker();

  Uint8List? _novaFotoBytes;
  XFile? _novaFoto;
  String? _fotoAtualPath;

  bool _carregando = true;
  String? _erroCarregar;
  bool _salvando = false;
  bool _obscureSenha = true;

  @override
  void initState() {
    super.initState();
    _carregar();
  }

  Future<void> _carregar() async {
    setState(() {
      _carregando = true;
      _erroCarregar = null;
    });

    try {
      final corpo = await ApiClient.get('admin/perfil') as Map<String, dynamic>;
      final admin = corpo['admin'] as Map<String, dynamic>;

      nomeCtrl.text = admin['NOME'] as String? ?? '';
      emailCtrl.text = admin['EMAIL'] as String? ?? '';
      telefoneCtrl.text = admin['TELEFONE'] as String? ?? '';
      _fotoAtualPath = admin['FOTO'] as String?;

      if (!mounted) return;
      setState(() => _carregando = false);
    } on ApiException catch (e) {
      if (!mounted) return;
      setState(() {
        _carregando = false;
        _erroCarregar = e.mensagem;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _carregando = false;
        _erroCarregar = 'Erro de conexão com o servidor: $e';
      });
    }
  }

  Future<void> _salvar() async {
    setState(() => _salvando = true);

    final campos = <String, String>{
      'nome': nomeCtrl.text.trim(),
      'email': emailCtrl.text.trim(),
      'telefone': telefoneCtrl.text.trim(),
    };
    if (senhaCtrl.text.isNotEmpty) {
      campos['senha'] = senhaCtrl.text;
    }

    try {
      final corpo = await ApiClient.postMultipart(
        'admin/perfil',
        campos,
        foto: _novaFoto,
      ) as Map<String, dynamic>;

      if (!mounted) return;
      setState(() {
        _salvando = false;
        senhaCtrl.clear();
      });

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(corpo['message'] as String? ?? 'Perfil atualizado com sucesso!')),
      );
    } on ApiException catch (e) {
      if (!mounted) return;
      setState(() => _salvando = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(e.mensagem), backgroundColor: AppColors.danger),
      );
    } catch (e) {
      if (!mounted) return;
      setState(() => _salvando = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Erro de conexão: $e'), backgroundColor: AppColors.danger),
      );
    }
  }

  Future<void> _selecionarFoto() async {
    try {
      final image = await _picker.pickImage(source: ImageSource.gallery, maxWidth: 1024);
      if (image == null) return;
      final bytes = await image.readAsBytes();

      if (!mounted) return;
      setState(() {
        _novaFoto = image;
        _novaFotoBytes = bytes;
      });
    } catch (_) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao selecionar imagem')),
      );
    }
  }

  Future<void> _logout() async {
    final confirmar = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        backgroundColor: context.cardBg,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Sair da conta'),
        content: Text(
          'Tem certeza que deseja sair?',
          style: TextStyle(color: context.textMuted),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context, false), child: const Text('Cancelar')),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Sair'),
          ),
        ],
      ),
    );

    if (confirmar != true) return;

    await AuthService.instance.logout();
    if (!mounted) return;
    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (_) => const LoginPage()),
      (route) => false,
    );
  }

  @override
  Widget build(BuildContext context) {
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
      body: _carregando
          ? const Center(child: CircularProgressIndicator())
          : _erroCarregar != null
          ? Center(
              child: Padding(
                padding: const EdgeInsets.all(24),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.error_outline, size: 48, color: AppColors.danger),
                    const SizedBox(height: 12),
                    Text(_erroCarregar!, textAlign: TextAlign.center, style: TextStyle(color: context.textMuted)),
                    const SizedBox(height: 20),
                    ElevatedButton(onPressed: _carregar, child: const Text('Tentar novamente')),
                  ],
                ),
              ),
            )
          : SingleChildScrollView(
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
                            color: AppColors.primary.withValues(alpha: 0.15),
                            border: Border.all(color: context.borderColor),
                          ),
                          child: ClipOval(
                            child: _novaFotoBytes != null
                                ? Image.memory(_novaFotoBytes!, fit: BoxFit.cover, width: 104, height: 104)
                                : (_fotoAtualPath != null && _fotoAtualPath!.isNotEmpty)
                                ? Image.network(
                                    '${ApiConfig.baseUrl}/$_fotoAtualPath',
                                    fit: BoxFit.cover,
                                    width: 104,
                                    height: 104,
                                    errorBuilder: (c, e, s) => const Icon(Icons.admin_panel_settings, size: 40),
                                  )
                                : const Icon(Icons.admin_panel_settings, size: 40),
                          ),
                        ),
                        Positioned(
                          bottom: 0,
                          right: 0,
                          child: Container(
                            padding: const EdgeInsets.all(6),
                            decoration: const BoxDecoration(color: AppColors.primary, shape: BoxShape.circle),
                            child: const Icon(Icons.camera_alt, size: 14, color: Colors.white),
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

                  const SizedBox(height: 12),

                  TextField(
                    controller: senhaCtrl,
                    obscureText: _obscureSenha,
                    decoration: InputDecoration(
                      labelText: 'Nova senha (deixe em branco para manter)',
                      suffixIcon: IconButton(
                        icon: Icon(
                          _obscureSenha ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                          size: 20,
                        ),
                        onPressed: () => setState(() => _obscureSenha = !_obscureSenha),
                      ),
                    ),
                  ),

                  const SizedBox(height: 24),

                  SizedBox(
                    width: double.infinity,
                    child: _salvando
                        ? const Center(child: CircularProgressIndicator())
                        : ElevatedButton(onPressed: _salvar, child: const Text('Salvar')),
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
    senhaCtrl.dispose();
    super.dispose();
  }
}
