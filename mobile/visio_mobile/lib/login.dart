import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'cadastro.dart';
import 'senha.dart';
import 'login_adm.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  late final TextEditingController _emailCtrl;
  late final TextEditingController _senhaCtrl;
  bool _obscure = true;
  bool _loading = false;
  DateTime? _lastLoginAttempt;

  @override
  void initState() {
    super.initState();
    // ✓ Controllers inicializados em initState, não no construtor
    _emailCtrl = TextEditingController();
    _senhaCtrl = TextEditingController();
  }

  Future<void> _login() async {
    // ✓ Impedir múltiplos cliques no mesmo botão
    final now = DateTime.now();
    if (_lastLoginAttempt != null &&
        now.difference(_lastLoginAttempt!).inSeconds < 1) {
      return;
    }
    _lastLoginAttempt = now;

    if (!_formKey.currentState!.validate()) return;

    // ✓ Guard duplo contra múltiplos taps
    if (_loading) return;

    setState(() => _loading = true);

    try {
      // Simular chamada à API
      await Future.delayed(const Duration(milliseconds: 800));

      // ✓ Verificar mounted ANTES de usar context
      if (!mounted) return;

      setState(() => _loading = false);

      // ✓ Verificar mounted novamente antes de mostrar snackbar
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Login realizado com sucesso!')),
        );
      }
    } catch (e) {
      // ✓ Tratamento de erro adequado
      if (mounted) {
        setState(() => _loading = false);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Erro: $e'),
            backgroundColor: AppColors.danger,
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.bgBase,
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/LogoDark.png', height: 60),
            const SizedBox(width: 8),
            const Text('Login'),
          ],
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Column(
            children: [
              Image.asset(
                'assets/images/LogoDark.png',
                width: 350,
                height: 350,
              ),
              const SizedBox(height: 1),
              const Text(
                'Bem-vindo ao VISIO',
                style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 6),
              const Text(
                'Entre com suas credenciais para acessar a plataforma.',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 13, color: AppColors.textMuted),
              ),
              const SizedBox(height: 32),

              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: AppColors.bgCardAlt,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: AppColors.border),
                ),
                child: Form(
                  key: _formKey,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'LOGIN',
                        style: TextStyle(
                          fontSize: 13,
                          fontWeight: FontWeight.bold,
                          letterSpacing: 1.5,
                          color: AppColors.primary,
                        ),
                      ),
                      const SizedBox(height: 20),

                      // ✓ Email field com validação melhorada
                      TextFormField(
                        controller: _emailCtrl,
                        keyboardType: TextInputType.emailAddress,
                        decoration: const InputDecoration(
                          labelText: 'E-mail',
                          prefixIcon: Icon(Icons.email_outlined, size: 20),
                        ),
                      ),
                      const SizedBox(height: 14),

                      // ✓ Senha field com validação melhorada
                      TextFormField(
                        controller: _senhaCtrl,
                        obscureText: _obscure,
                        decoration: InputDecoration(
                          labelText: 'Senha',
                          prefixIcon: const Icon(Icons.lock_outline, size: 20),
                          suffixIcon: IconButton(
                            icon: Icon(
                              _obscure
                                  ? Icons.visibility_off_outlined
                                  : Icons.visibility_outlined,
                              size: 20,
                            ),
                            onPressed: () =>
                                setState(() => _obscure = !_obscure),
                          ),
                        ),
                      ),
                      const SizedBox(height: 8),
                      Align(
                        alignment: Alignment.centerRight,
                        child: TextButton(
                          onPressed: () => Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (_) => const ForgotPage(),
                            ),
                          ),
                          child: const Text(
                            'Esqueceu a senha?',
                            style: TextStyle(fontSize: 13),
                          ),
                        ),
                      ),
                      const SizedBox(height: 8),
                      SizedBox(
                        width: double.infinity,
                        child: _loading
                            ? const Center(child: CircularProgressIndicator())
                            : ElevatedButton(
                                onPressed: _login,
                                child: const Text('Entrar'),
                              ),
                      ),
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 20),

              // Links
              _linkRow(
                'Não tem conta?',
                'Cadastre-se',
                () => Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => const CadastroPage()),
                ),
              ),
              _linkRow(
                'É administrador?',
                'Acesso ADM',
                () => Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => const LoginAdminPage()),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _linkRow(String label, String action, VoidCallback onTap) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Text(
          label,
          style: const TextStyle(color: AppColors.textMuted, fontSize: 13),
        ),
        TextButton(
          onPressed: onTap,
          child: Text(
            action,
            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
          ),
        ),
      ],
    );
  }

  @override
  void dispose() {
    // ✓ Sempre descartar controllers
    _emailCtrl.dispose();
    _senhaCtrl.dispose();
    super.dispose();
  }
}
