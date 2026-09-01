import 'dart:typed_data';

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:mask_text_input_formatter/mask_text_input_formatter.dart';

import 'appcolor.dart';
import 'historico.dart';
import 'login.dart';
import 'services/api_client.dart';
import 'services/api_config.dart';
import 'services/auth_service.dart';

class PerfilPage extends StatefulWidget {
  const PerfilPage({super.key});

  @override
  State<PerfilPage> createState() => _PerfilPageState();
}

class _PerfilPageState extends State<PerfilPage> {
  final _formKey = GlobalKey<FormState>();
  final _nomeCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _cartaoCtrl = TextEditingController();
  final _dataNascCtrl = TextEditingController();
  final _telefoneCtrl = TextEditingController();
  final _senhaCtrl = TextEditingController();

  final _telefoneMask = MaskTextInputFormatter(
    mask: '(##) #####-####',
    filter: {"#": RegExp(r'[0-9]')},
  );

  final _cartaoMask = MaskTextInputFormatter(
    mask: '#### #### #### ####',
    filter: {"#": RegExp(r'[0-9]')},
  );

  final _dataMask = MaskTextInputFormatter(
    mask: '##/##/####',
    filter: {"#": RegExp(r'[0-9]')},
  );

  final ImagePicker _picker = ImagePicker();

  XFile? _novaFoto;
  Uint8List? _novaFotoBytes;
  String? _fotoAtualPath;

  bool _carregando = true;
  bool _salvando = false;
  bool _obscureSenha = true;
  String? _erroCarregar;

  final int _total = 0;
  final int _acertos = 0;
  final int _percentual = 0;

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
      final resposta = await ApiClient.get('perfil');

      if (resposta == null) {
        throw ApiException('O servidor não retornou dados do perfil.');
      }

      if (resposta is! Map) {
        throw ApiException('Formato de resposta inválido para o perfil.');
      }

      final usuario = Map<String, dynamic>.from(resposta);

      _nomeCtrl.text = usuario['NOME']?.toString() ?? '';
      _emailCtrl.text = usuario['EMAIL']?.toString() ?? '';
      _cartaoCtrl.text = usuario['CARTAO']?.toString() ?? '';
      _telefoneCtrl.text = usuario['TELEFONE']?.toString() ?? '';

      _dataNascCtrl.text =
          _isoParaBr(usuario['DATA_NASCIMENTO']?.toString()) ?? '';

      _fotoAtualPath = usuario['FOTO']?.toString() ?? '';

      if (!mounted) return;

      setState(() {
        _carregando = false;
      });
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
        _erroCarregar = 'Erro ao carregar perfil: $e';
      });
    }
  }

  Future<void> _selecionarFoto() async {
    try {
      final foto = await _picker.pickImage(
        source: ImageSource.gallery,
        maxWidth: 1024,
      );

      if (foto == null) return;

      final bytes = await foto.readAsBytes();

      if (!mounted) return;

      setState(() {
        _novaFoto = foto;
        _novaFotoBytes = bytes;
      });
    } catch (_) {
      if (!mounted) return;

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro ao selecionar imagem')),
      );
    }
  }

  Future<void> _salvar() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _salvando = true);

    final dataIso = _brParaIso(_dataNascCtrl.text);

    final campos = <String, String>{
      'nome': _nomeCtrl.text.trim(),
      'email': _emailCtrl.text.trim(),
      'cartao': _cartaoCtrl.text.trim(),
      'data_nascimento': dataIso ?? '',
      'telefone': _telefoneCtrl.text.trim(),
    };

    if (_senhaCtrl.text.isNotEmpty) {
      campos['senha'] = _senhaCtrl.text;
    }

    try {
      final resposta =
          await ApiClient.postMultipart('perfil', campos, foto: _novaFoto)
              as Map<String, dynamic>;

      if (!mounted) return;

      // O backend retorna o usuário diretamente.
      final usuarioAtualizado = resposta;

      setState(() {
        _salvando = false;
        _senhaCtrl.clear();
        _novaFoto = null;
        _novaFotoBytes = null;

        _fotoAtualPath = usuarioAtualizado['FOTO']?.toString();
      });

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Perfil atualizado com sucesso!')),
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
        SnackBar(
          content: Text('Erro de conexão: $e'),
          backgroundColor: AppColors.danger,
        ),
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
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Cancelar'),
          ),
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
      (route) => route.isFirst,
    );
  }

  String? _isoParaBr(String? iso) {
    if (iso == null || iso.isEmpty) return null;

    final partes = iso.split('-');

    if (partes.length != 3) return null;

    return '${partes[2]}/${partes[1]}/${partes[0]}';
  }

  String? _brParaIso(String br) {
    final limpo = br.replaceAll(RegExp(r'\D'), '');

    if (limpo.length != 8) return null;

    final dia = limpo.substring(0, 2);
    final mes = limpo.substring(2, 4);
    final ano = limpo.substring(4, 8);

    return '$ano-$mes-$dia';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Meu Perfil'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout, color: AppColors.danger),
            tooltip: 'Sair',
            onPressed: _logout,
          ),
        ],
      ),
      body: SafeArea(
        child: _carregando
            ? const Center(child: CircularProgressIndicator())
            : _erroCarregar != null
            ? _buildErro(context)
            : _buildConteudo(context),
      ),
    );
  }

  Widget _buildErro(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.error_outline, size: 48, color: AppColors.danger),
            const SizedBox(height: 12),
            Text(
              _erroCarregar!,
              textAlign: TextAlign.center,
              style: TextStyle(color: context.textMuted),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: _carregar,
              child: const Text('Tentar novamente'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildConteudo(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Form(
        key: _formKey,
        child: Column(
          children: [
            _buildFoto(context),
            const SizedBox(height: 24),

            _buildEstatisticas(context),
            const SizedBox(height: 24),

            TextFormField(
              controller: _nomeCtrl,
              decoration: const InputDecoration(
                labelText: 'Nome',
                prefixIcon: Icon(Icons.person_outline, size: 20),
              ),
              validator: (v) =>
                  (v == null || v.isEmpty) ? 'Campo obrigatório' : null,
            ),

            const SizedBox(height: 14),

            TextFormField(
              controller: _emailCtrl,
              keyboardType: TextInputType.emailAddress,
              decoration: const InputDecoration(
                labelText: 'E-mail',
                prefixIcon: Icon(Icons.email_outlined, size: 20),
              ),
              validator: (v) {
                if (v == null || v.isEmpty) {
                  return 'Campo obrigatório';
                }

                if (!AuthService.instance.emailValido(v)) {
                  return 'E-mail inválido';
                }

                return null;
              },
            ),

            const SizedBox(height: 14),

            TextFormField(
              controller: _dataNascCtrl,
              keyboardType: TextInputType.number,
              inputFormatters: [_dataMask],
              decoration: const InputDecoration(
                labelText: 'Data de nascimento',
                hintText: 'DD/MM/AAAA',
                prefixIcon: Icon(Icons.calendar_today_outlined, size: 20),
              ),
            ),

            const SizedBox(height: 14),

            TextFormField(
              controller: _telefoneCtrl,
              keyboardType: TextInputType.phone,
              inputFormatters: [_telefoneMask],
              decoration: const InputDecoration(
                labelText: 'Telefone',
                prefixIcon: Icon(Icons.phone_outlined, size: 20),
              ),
            ),

            const SizedBox(height: 14),

            TextFormField(
              controller: _cartaoCtrl,
              inputFormatters: [_cartaoMask],
              decoration: const InputDecoration(
                labelText: 'Cartão IoT (opcional)',
                prefixIcon: Icon(Icons.credit_card_outlined, size: 20),
              ),
            ),

            const SizedBox(height: 14),

            TextFormField(
              controller: _senhaCtrl,
              obscureText: _obscureSenha,
              decoration: InputDecoration(
                labelText: 'Nova senha (deixe em branco para manter)',
                prefixIcon: const Icon(Icons.lock_outline, size: 20),
                suffixIcon: IconButton(
                  icon: Icon(
                    _obscureSenha
                        ? Icons.visibility_off_outlined
                        : Icons.visibility_outlined,
                    size: 20,
                  ),
                  onPressed: () {
                    setState(() {
                      _obscureSenha = !_obscureSenha;
                    });
                  },
                ),
              ),
              validator: (v) {
                if (v == null || v.isEmpty) return null;

                if (v.length < 6) {
                  return 'Mínimo 6 caracteres';
                }

                return null;
              },
            ),

            const SizedBox(height: 24),

            SizedBox(
              width: double.infinity,
              child: _salvando
                  ? const Center(child: CircularProgressIndicator())
                  : ElevatedButton(
                      onPressed: _salvar,
                      child: const Text('Salvar alterações'),
                    ),
            ),

            const SizedBox(height: 12),

            SizedBox(
              width: double.infinity,
              child: OutlinedButton.icon(
                onPressed: () => Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => const HistoricoPage()),
                ),
                icon: const Icon(Icons.history, size: 18),
                label: const Text('Ver histórico de respostas'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFoto(BuildContext context) {
    ImageProvider? imagem;

    if (_novaFotoBytes != null) {
      imagem = MemoryImage(_novaFotoBytes!);
    } else if (_fotoAtualPath != null && _fotoAtualPath!.isNotEmpty) {
      imagem = NetworkImage('${ApiConfig.baseUrl}/$_fotoAtualPath');
    }

    return GestureDetector(
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
              image: imagem != null
                  ? DecorationImage(image: imagem, fit: BoxFit.cover)
                  : null,
            ),
            child: imagem == null ? const Icon(Icons.person, size: 40) : null,
          ),
          Positioned(
            bottom: 0,
            right: 0,
            child: Container(
              padding: const EdgeInsets.all(6),
              decoration: const BoxDecoration(
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
    );
  }

  Widget _buildEstatisticas(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: context.cardBg,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: context.borderColor),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
        children: [
          _estatItem(context, '$_total', 'Respostas'),
          Container(width: 1, height: 32, color: context.borderColor),
          _estatItem(context, '$_acertos', 'Acertos'),
          Container(width: 1, height: 32, color: context.borderColor),
          _estatItem(context, '$_percentual%', 'Aproveitamento'),
        ],
      ),
    );
  }

  Widget _estatItem(BuildContext context, String valor, String label) {
    return Column(
      children: [
        Text(
          valor,
          style: const TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: AppColors.primary,
          ),
        ),
        const SizedBox(height: 2),
        Text(label, style: TextStyle(fontSize: 11, color: context.textMuted)),
      ],
    );
  }

  @override
  void dispose() {
    _nomeCtrl.dispose();
    _emailCtrl.dispose();
    _cartaoCtrl.dispose();
    _dataNascCtrl.dispose();
    _telefoneCtrl.dispose();
    _senhaCtrl.dispose();
    super.dispose();
  }
}
