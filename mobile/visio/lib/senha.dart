import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'services/api_client.dart';

class ForgotPage extends StatefulWidget {
  const ForgotPage({super.key});

  @override
  State<ForgotPage> createState() => _ForgotPageState();
}

class _ForgotPageState extends State<ForgotPage> {
  final _formKey = GlobalKey<FormState>();
  final emailCtrl = TextEditingController();
  bool _loading = false;
  bool _enviado = false;
  String? _mensagem;
  String? _token;

  
  static const _rotaSolicitar = 'usuario/esqueceu_senha';

  Future<void> _recuperar() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _loading = true);

    try {
      final corpo = await ApiClient.postForm(_rotaSolicitar, {
        'email': emailCtrl.text.trim(),
      }) as Map<String, dynamic>;

      if (!mounted) return;
      setState(() {
        _loading = false;
        _enviado = true;
        _mensagem = corpo['message'] as String?;
        _token = corpo['token'] as String?;
      });
    } on ApiException catch (e) {
      if (!mounted) return;
      setState(() => _loading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(e.mensagem), backgroundColor: AppColors.danger),
      );
    } catch (e) {
      if (!mounted) return;
      setState(() => _loading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Erro de conexão: $e'),
          backgroundColor: AppColors.danger,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Row(
          children: [
            Icon(Icons.visibility, color: AppColors.primary, size: 20),
            SizedBox(width: 8),
            Text('Recuperar Senha'),
          ],
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: _enviado ? _buildSuccess(context) : _buildForm(context),
        ),
      ),
    );
  }

  Widget _buildForm(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        Container(
          padding: const EdgeInsets.all(1),

          child: Image.asset(
            'assets/images/logos/Logo/LogoDark.png',
            width: 350,
            height: 350,
          ),
        ),
        const Text(
          'Esqueceu sua senha?',
          style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 8),
        Text(
          'Informe seu e-mail cadastrado. Enviaremos as instruções de recuperação.',
          style: TextStyle(color: context.textMuted, fontSize: 13, height: 1.5),
        ),
        const SizedBox(height: 32),
        Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                controller: emailCtrl,
                keyboardType: TextInputType.emailAddress,
                decoration: const InputDecoration(
                  labelText: 'E-mail cadastrado',
                  prefixIcon: Icon(Icons.email_outlined, size: 20),
                ),
                validator: (v) {
                  if (v == null || v.isEmpty) return 'Informe seu e-mail';
                  if (!v.contains('@')) return 'E-mail inválido';
                  return null;
                },
              ),
              const SizedBox(height: 20),
              SizedBox(
                width: double.infinity,
                child: _loading
                    ? const Center(child: CircularProgressIndicator())
                    : ElevatedButton(
                        onPressed: _recuperar,
                        child: const Text('Registrar pedido'),
                      ),
              ),
              const SizedBox(height: 16),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildSuccess(BuildContext context) {
    return Column(
      children: [
        const SizedBox(height: 60),
        Container(
          padding: const EdgeInsets.all(24),
          decoration: BoxDecoration(
            color: AppColors.success.withValues(alpha: 0.1),
            shape: BoxShape.circle,
          ),
          child: const Icon(
            Icons.mark_email_read_outlined,
            size: 52,
            color: AppColors.success,
          ),
        ),
        const SizedBox(height: 24),
        const Text(
          'Pedido registrado!',
          style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 12),
        Text(
          _mensagem ?? 'Se o e-mail informado estiver cadastrado, as instruções foram geradas.',
          textAlign: TextAlign.center,
          style: TextStyle(color: context.textMuted, fontSize: 14, height: 1.5),
        ),
        const SizedBox(height: 32),
        if (_token != null) ...[
          
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: () => Navigator.pushReplacement(
                context,
                MaterialPageRoute(
                  builder: (_) => RedefinirSenhaPage(token: _token!),
                ),
              ),
              icon: const Icon(Icons.lock_reset, size: 18),
              label: const Text('Definir nova senha agora'),
            ),
          ),
          const SizedBox(height: 12),
        ],
        SizedBox(
          width: double.infinity,
          child: OutlinedButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Voltar ao login'),
          ),
        ),
      ],
    );
  }

  @override
  void dispose() {
    emailCtrl.dispose();
    super.dispose();
  }
}

class RedefinirSenhaPage extends StatefulWidget {
  final String token;

  const RedefinirSenhaPage({super.key, required this.token});

  @override
  State<RedefinirSenhaPage> createState() => _RedefinirSenhaPageState();
}

class _RedefinirSenhaPageState extends State<RedefinirSenhaPage> {
  final _formKey = GlobalKey<FormState>();
  final _senhaCtrl = TextEditingController();
  final _confirmaCtrl = TextEditingController();
  bool _obscure = true;
  bool _loading = false;

  static const _rotaRedefinir = 'usuario/redefinir_senha';

  Future<void> _redefinir() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _loading = true);

    try {
      final corpo = await ApiClient.postForm(_rotaRedefinir, {
        'token': widget.token,
        'senha': _senhaCtrl.text,
        'confirma_senha': _confirmaCtrl.text,
      }) as Map<String, dynamic>;

      if (!mounted) return;
      setState(() => _loading = false);

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(corpo['message'] as String? ?? 'Senha redefinida com sucesso!'),
        ),
      );
      Navigator.popUntil(context, (route) => route.isFirst);
    } on ApiException catch (e) {
      if (!mounted) return;
      setState(() => _loading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(e.mensagem), backgroundColor: AppColors.danger),
      );
    } catch (e) {
      if (!mounted) return;
      setState(() => _loading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Erro de conexão: $e'),
          backgroundColor: AppColors.danger,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Nova senha')),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Form(
            key: _formKey,
            child: Column(
              children: [
                const Icon(Icons.lock_reset, size: 52, color: AppColors.primary),
                const SizedBox(height: 20),
                const Text(
                  'Defina sua nova senha',
                  style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 24),
                TextFormField(
                  controller: _senhaCtrl,
                  obscureText: _obscure,
                  decoration: InputDecoration(
                    labelText: 'Nova senha',
                    prefixIcon: const Icon(Icons.lock_outline, size: 20),
                    suffixIcon: IconButton(
                      icon: Icon(
                        _obscure ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                        size: 20,
                      ),
                      onPressed: () => setState(() => _obscure = !_obscure),
                    ),
                  ),
                  validator: (v) {
                    if (v == null || v.isEmpty) return 'Campo obrigatório';
                    if (v.length < 6) return 'Mínimo 6 caracteres';
                    return null;
                  },
                ),
                const SizedBox(height: 14),
                TextFormField(
                  controller: _confirmaCtrl,
                  obscureText: _obscure,
                  decoration: const InputDecoration(
                    labelText: 'Confirmar nova senha',
                    prefixIcon: Icon(Icons.lock_outline, size: 20),
                  ),
                  validator: (v) {
                    if (v == null || v.isEmpty) return 'Campo obrigatório';
                    if (v != _senhaCtrl.text) return 'As senhas não coincidem';
                    return null;
                  },
                ),
                const SizedBox(height: 24),
                SizedBox(
                  width: double.infinity,
                  child: _loading
                      ? const Center(child: CircularProgressIndicator())
                      : ElevatedButton(
                          onPressed: _redefinir,
                          child: const Text('Redefinir senha'),
                        ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _senhaCtrl.dispose();
    _confirmaCtrl.dispose();
    super.dispose();
  }
}
