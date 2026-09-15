import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'cadastro.dart';
import 'perfil.dart';
import 'senha.dart';
import 'services/auth_service.dart';
import 'widgets/accessibility_panel.dart';

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
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;
    final isDesktop = MediaQuery.of(context).size.width > 800;

    // CORES ADAPTÁVEIS AO TEMA
    final backgroundColor = isDark
        ? const Color(0xFF030712)
        : const Color(0xFFF1F5F9);
    final mainCardGradient = isDark
        ? const [Color(0xFF0B132B), Color(0xFF1C2A4A)]
        : [Colors.white, const Color(0xFFE2E8F0)];
    final mainCardBorder = isDark
        ? const Color(0xFF1E293B)
        : const Color(0xFFCBD5E1);

    final formBgColor = isDark ? const Color(0xFF0D1B3A) : Colors.white;
    final formBorderColor = isDark
        ? const Color(0xFF1E3A8A)
        : const Color(0xFFE2E8F0);

    final titleTextColor = isDark ? Colors.white : const Color(0xFF0F172A);
    final bodyTextColor = isDark
        ? const Color(0xFF94A3B8)
        : const Color(0xFF475569);
    final inputBgColor = isDark
        ? const Color(0xFF070F26)
        : const Color(0xFFF8FAFC);
    final inputBorderColor = isDark
        ? const Color(0xFF1E293B)
        : const Color(0xFFCBD5E1);

    return Scaffold(
      backgroundColor: backgroundColor,
      body: Stack(
        children: [
          Column(
            children: [
              _buildTopNavBar(context, isDark, titleTextColor, bodyTextColor),

              Expanded(
                child: Center(
                  child: SingleChildScrollView(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 16,
                      vertical: 24,
                    ),
                    child: Container(
                      width: 900,
                      decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(24),
                        gradient: LinearGradient(
                          colors: mainCardGradient,
                          begin: Alignment.topLeft,
                          end: Alignment.bottomRight,
                        ),
                        border: Border.all(color: mainCardBorder),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withOpacity(
                              isDark ? 0.4 : 0.08,
                            ),
                            blurRadius: 30,
                            offset: const Offset(0, 10),
                          ),
                        ],
                      ),
                      child: isDesktop
                          ? Row(
                              crossAxisAlignment: CrossAxisAlignment.center,
                              children: [
                                Expanded(
                                  child: _buildBannerSide(
                                    titleTextColor,
                                    bodyTextColor,
                                    isDark,
                                  ),
                                ),
                                Expanded(
                                  child: _buildFormSide(
                                    formBgColor,
                                    formBorderColor,
                                    titleTextColor,
                                    bodyTextColor,
                                    inputBgColor,
                                    inputBorderColor,
                                  ),
                                ),
                              ],
                            )
                          : Column(
                              children: [
                                _buildBannerSide(
                                  titleTextColor,
                                  bodyTextColor,
                                  isDark,
                                ),
                                _buildFormSide(
                                  formBgColor,
                                  formBorderColor,
                                  titleTextColor,
                                  bodyTextColor,
                                  inputBgColor,
                                  inputBorderColor,
                                ),
                              ],
                            ),
                    ),
                  ),
                ),
              ),
            ],
          ),

          // PAINEL DE ACESSIBILIDADE
          const AccessibilityPanel(),
        ],
      ),
    );
  }

  // BARRA DE NAVEGAÇÃO SUPERIOR
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
        color: isDark ? const Color(0xFF030712) : Colors.white,
        border: Border(
          bottom: BorderSide(
            color: isDark ? const Color(0xFF1E293B) : const Color(0xFFE2E8F0),
          ),
        ),
      ),
      child: Row(
        children: [
          IconButton(
            onPressed: () => Navigator.pop(context),
            icon: Icon(Icons.arrow_back_rounded, color: titleColor, size: 22),
            tooltip: 'Voltar',
          ),

          Image.asset(
            isDark
                ? 'assets/images/logos/Logo/LogoDark2.png'
                : 'assets/images/logos/Logo/LogoLight2.png',
            height: 28,
          ),

          const SizedBox(width: 8),
          const SizedBox(width: 16),
        ],
      ),
    );
  }

  Widget _buildBannerSide(Color titleColor, Color bodyColor, bool isDark) {
    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Center(
            child: Image.asset(
              isDark
                  ? 'assets/images/logos/Logo/LogoDark2.png'
                  : 'assets/images/logos/Logo/LogoLight2.png',
              height: 160,
              fit: BoxFit.contain,
            ),
          ),
          const SizedBox(height: 20),
          Text(
            'Bem-vindo!',
            style: TextStyle(
              fontSize: 28,
              fontWeight: FontWeight.bold,
              color: titleColor,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Acesse a plataforma VISIO para gerenciar seus sensores IoT e dashboards em tempo real.',
            style: TextStyle(fontSize: 13, color: bodyColor, height: 1.4),
          ),
        ],
      ),
    );
  }

  // FORMULÁRIO DINÂMICO
  Widget _buildFormSide(
    Color bgColor,
    Color borderColor,
    Color titleColor,
    Color bodyColor,
    Color inputBg,
    Color inputBorder,
  ) {
    return Container(
      margin: const EdgeInsets.all(16),
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: borderColor),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Form(
        key: _formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Entrar',
              style: TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: titleColor,
              ),
            ),
            const SizedBox(height: 4),
            Container(
              height: 3,
              width: 28,
              decoration: BoxDecoration(
                color: const Color(0xFF0284C7),
                borderRadius: BorderRadius.circular(2),
              ),
            ),
            const SizedBox(height: 20),

            _buildFieldLabel('E-mail', Icons.email_outlined, titleColor),
            const SizedBox(height: 6),
            TextFormField(
              controller: _emailCtrl,
              keyboardType: TextInputType.emailAddress,
              style: TextStyle(color: titleColor, fontSize: 14),
              decoration: _inputStyle('seu@email.com', inputBg, inputBorder),
              validator: (v) {
                if (v == null || v.isEmpty) return 'Campo obrigatório';
                if (!AuthService.instance.emailValido(v))
                  return 'E-mail inválido';
                return null;
              },
            ),
            const SizedBox(height: 16),

            _buildFieldLabel('Senha', Icons.lock_outline, titleColor),
            const SizedBox(height: 6),
            TextFormField(
              controller: _senhaCtrl,
              obscureText: _obscure,
              style: TextStyle(color: titleColor, fontSize: 14),
              decoration: _inputStyle('••••••••', inputBg, inputBorder)
                  .copyWith(
                    suffixIcon: IconButton(
                      icon: Icon(
                        _obscure
                            ? Icons.visibility_off_outlined
                            : Icons.visibility_outlined,
                        color: bodyColor,
                        size: 18,
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
            const SizedBox(height: 20),

            SizedBox(
              width: double.infinity,
              height: 44,
              child: _loading
                  ? const Center(child: CircularProgressIndicator())
                  : ElevatedButton.icon(
                      onPressed: _login,
                      icon: const Icon(
                        Icons.login_rounded,
                        size: 18,
                        color: Colors.white,
                      ),
                      label: const Text(
                        'Entrar',
                        style: TextStyle(
                          fontSize: 14,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      ),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF0284C7),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(25),
                        ),
                        elevation: 2,
                      ),
                    ),
            ),
            const SizedBox(height: 10),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Text(
                  'Não tem conta?',
                  style: TextStyle(color: bodyColor, fontSize: 12),
                ),
                TextButton.icon(
                  onPressed: () => Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const CadastroPage()),
                  ),
                  icon: const Icon(
                    Icons.person_add_alt_1,
                    size: 14,
                    color: Color(0xFF0284C7),
                  ),
                  label: const Text(
                    'Cadastre-se',
                    style: TextStyle(
                      color: Color(0xFF0284C7),
                      fontWeight: FontWeight.bold,
                      fontSize: 12,
                    ),
                  ),
                ),
              ],
            ),

            Center(
              child: TextButton.icon(
                onPressed: () => Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => const ForgotPage()),
                ),
                icon: Icon(Icons.key, size: 14, color: bodyColor),
                label: Text(
                  'Esqueceu a senha?',
                  style: TextStyle(color: bodyColor, fontSize: 12),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFieldLabel(String label, IconData icon, Color color) {
    return Row(
      children: [
        Icon(icon, size: 15, color: const Color(0xFF0284C7)),
        const SizedBox(width: 6),
        Text(
          label,
          style: TextStyle(
            color: color,
            fontSize: 13,
            fontWeight: FontWeight.w600,
          ),
        ),
      ],
    );
  }

  InputDecoration _inputStyle(String hint, Color bg, Color border) {
    return InputDecoration(
      hintText: hint,
      hintStyle: const TextStyle(color: Color(0xFF94A3B8), fontSize: 13),
      filled: true,
      fillColor: bg,
      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(20),
        borderSide: BorderSide(color: border),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(20),
        borderSide: BorderSide(color: border),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(20),
        borderSide: const BorderSide(color: Color(0xFF0284C7)),
      ),
    );
  }

  @override
  void dispose() {
    _emailCtrl.dispose();
    _senhaCtrl.dispose();
    super.dispose();
  }
}
