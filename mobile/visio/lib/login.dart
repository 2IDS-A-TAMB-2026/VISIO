import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'cadastro.dart';
import 'perfil.dart';
import 'senha.dart';
import 'services/auth_service.dart';

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
    _emailCtrl = TextEditingController();
    _senhaCtrl = TextEditingController();
  }

  Future<void> _login() async {
    final now = DateTime.now();
    if (_lastLoginAttempt != null &&
        now.difference(_lastLoginAttempt!).inSeconds < 1) {
      return;
    }
    _lastLoginAttempt = now;

    if (!_formKey.currentState!.validate()) return;

    if (_loading) return;

    setState(() => _loading = true);

    try {
      final resultado = await AuthService.instance.loginUsuario(
        email: _emailCtrl.text,
        senha: _senhaCtrl.text,
      );


      if (!mounted) return;

      setState(() => _loading = false);

      if (!mounted) return;

      if (resultado.sucesso) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Login realizado com sucesso!')),
        );
      
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (_) => const PerfilPage()),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(resultado.mensagemErro ?? 'Não foi possível entrar.'),
            backgroundColor: AppColors.danger,
          ),
        );
      }
    } catch (e) {
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
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/logos/Logo/LogoDark2.png', height: 40),
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
                'assets/images/logos/Logo/LogoDark.png',
                width: 350,
                height: 350,
              ),
              const SizedBox(height: 1),
              const Text(
                'Bem-vindo ao VISIO',
                style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 6),
              Text(
                'Entre com suas credenciais para acessar a plataforma.',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 13, color: context.textMuted),
              ),
              const SizedBox(height: 32),

              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: context.cardBg,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: context.borderColor),
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
                      const SizedBox(height: 16),

                      TextFormField(
                        controller: _emailCtrl,
                        keyboardType: TextInputType.emailAddress,
                        decoration: const InputDecoration(
                          labelText: 'E-mail',
                          prefixIcon: Icon(Icons.email_outlined, size: 20),
                        ),
                        validator: (v) {
                          if (v == null || v.isEmpty) return 'Campo obrigatório';
                          if (!AuthService.instance.emailValido(v)) {
                            return 'E-mail inválido';
                          }
                          return null;
                        },
                      ),
                      const SizedBox(height: 14),

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
                        validator: (v) {
                          if (v == null || v.isEmpty) return 'Campo obrigatório';
                          if (v.length < 6) return 'Mínimo 6 caracteres';
                          return null;
                        },
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

    
              _linkRow(
                'Não tem conta?',
                'Cadastre-se',
                () => Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => const CadastroPage()),
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
          style: TextStyle(color: context.textMuted, fontSize: 13),
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
  
    _emailCtrl.dispose();
    _senhaCtrl.dispose();
    super.dispose();
  }
}
