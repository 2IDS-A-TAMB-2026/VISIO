import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'services/api_client.dart';
import 'services/tts_service.dart';
import 'widgets/accessibility_panel.dart';

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
      final corpo =
          await ApiClient.postForm(_rotaSolicitar, {
                'email': emailCtrl.text.trim(),
              })
              as Map<String, dynamic>;

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
    TtsService.instance.definirTextoDaPagina(
      'Tela de recuperação de senha. Informe seu e-mail cadastrado para '
      'gerar o link de redefinição.',
    );

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
              // BARRA SUPERIOR ADAPTÁVEL
              _buildTopNavBar(context, isDark, titleTextColor, bodyTextColor),

              // CONTEÚDO PRINCIPAL
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
                            color: Colors.black.withValues(
                              alpha: isDark ? 0.4 : 0.08,
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
                                  child: _enviado
                                      ? _buildSuccessSide(
                                          titleTextColor,
                                          bodyTextColor,
                                        )
                                      : _buildFormSide(
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
                                _enviado
                                    ? _buildSuccessSide(
                                        titleTextColor,
                                        bodyTextColor,
                                      )
                                    : _buildFormSide(
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
          Image.asset('assets/images/logos/Logo/LogoDark2.png', height: 28),
          const SizedBox(width: 8),
          Text(
            'VISIO',
            style: TextStyle(
              color: titleColor,
              fontWeight: FontWeight.bold,
              fontSize: 18,
            ),
          ),
          const SizedBox(width: 16),
         
        ],
      ),
    );
  }

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
            color: Colors.black.withValues(alpha: 0.05),
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
              'Recuperar Senha',
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
            const SizedBox(height: 12),
            Text(
              'Digite seu e-mail cadastrado para receber as instruções de redefinição.',
              style: TextStyle(color: bodyColor, fontSize: 13, height: 1.4),
            ),
            const SizedBox(height: 20),

            _buildFieldLabel('E-mail', Icons.email_outlined, titleColor),
            const SizedBox(height: 6),
            TextFormField(
              controller: emailCtrl,
              keyboardType: TextInputType.emailAddress,
              style: TextStyle(color: titleColor, fontSize: 14),
              decoration: _inputStyle('nome@empresa.com', inputBg, inputBorder),
              validator: (v) {
                if (v == null || v.isEmpty) return 'Informe seu e-mail';
                if (!v.contains('@')) return 'E-mail inválido';
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
                      onPressed: _recuperar,
                      icon: const Icon(
                        Icons.send_rounded,
                        size: 16,
                        color: Colors.white,
                      ),
                      label: const Text(
                        'Enviar link de recuperação',
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
            const SizedBox(height: 16),

            Center(
              child: TextButton.icon(
                onPressed: () => Navigator.pop(context),
                icon: const Icon(
                  Icons.arrow_back,
                  size: 14,
                  color: Color(0xFF0284C7),
                ),
                label: const Text(
                  'Lembrou a senha? Entrar',
                  style: TextStyle(
                    color: Color(0xFF0284C7),
                    fontWeight: FontWeight.bold,
                    fontSize: 12,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSuccessSide(Color titleColor, Color bodyColor) {
    return Container(
      margin: const EdgeInsets.all(16),
      padding: const EdgeInsets.all(24),
      child: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: AppColors.success.withValues(alpha: 0.1),
              shape: BoxShape.circle,
            ),
            child: const Icon(
              Icons.mark_email_read_outlined,
              size: 48,
              color: AppColors.success,
            ),
          ),
          const SizedBox(height: 20),
          Text(
            'Pedido registrado!',
            style: TextStyle(
              fontSize: 22,
              fontWeight: FontWeight.bold,
              color: titleColor,
            ),
          ),
          const SizedBox(height: 12),
          Text(
            _mensagem ??
                'Se o e-mail informado estiver cadastrado, as instruções foram geradas.',
            textAlign: TextAlign.center,
            style: TextStyle(color: bodyColor, fontSize: 13, height: 1.5),
          ),
          const SizedBox(height: 24),
          if (_token != null) ...[
            SizedBox(
              width: double.infinity,
              height: 44,
              child: ElevatedButton.icon(
                onPressed: () => Navigator.pushReplacement(
                  context,
                  MaterialPageRoute(
                    builder: (_) => RedefinirSenhaPage(token: _token!),
                  ),
                ),
                icon: const Icon(
                  Icons.lock_reset,
                  size: 18,
                  color: Colors.white,
                ),
                label: const Text('Definir nova senha agora'),
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF0284C7),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(25),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 12),
          ],
          SizedBox(
            width: double.infinity,
            height: 44,
            child: OutlinedButton(
              onPressed: () => Navigator.pop(context),
              style: OutlinedButton.styleFrom(
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(25),
                ),
              ),
              child: const Text('Voltar ao login'),
            ),
          ),
        ],
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
      final corpo =
          await ApiClient.postForm(_rotaRedefinir, {
                'token': widget.token,
                'senha': _senhaCtrl.text,
                'confirma_senha': _confirmaCtrl.text,
              })
              as Map<String, dynamic>;

      if (!mounted) return;
      setState(() => _loading = false);

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            corpo['message'] as String? ?? 'Senha redefinida com sucesso!',
          ),
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
    TtsService.instance.definirTextoDaPagina(
      'Tela para definir uma nova senha. Digite a nova senha e confirme.',
    );

    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

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
          Center(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 24),
              child: Container(
                width: 480,
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
                      color: Colors.black.withValues(
                        alpha: isDark ? 0.4 : 0.08,
                      ),
                      blurRadius: 30,
                      offset: const Offset(0, 10),
                    ),
                  ],
                ),
                child: Container(
                  margin: const EdgeInsets.all(16),
                  padding: const EdgeInsets.all(24),
                  decoration: BoxDecoration(
                    color: formBgColor,
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(color: formBorderColor),
                  ),
                  child: Form(
                    key: _formKey,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Defina sua nova senha',
                          style: TextStyle(
                            fontSize: 22,
                            fontWeight: FontWeight.bold,
                            color: titleTextColor,
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

                        Row(
                          children: [
                            const Icon(
                              Icons.lock_outline,
                              size: 15,
                              color: Color(0xFF0284C7),
                            ),
                            const SizedBox(width: 6),
                            Text(
                              'Nova senha',
                              style: TextStyle(
                                color: titleTextColor,
                                fontSize: 13,
                                fontWeight: FontWeight.w600,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _senhaCtrl,
                          obscureText: _obscure,
                          style: TextStyle(color: titleTextColor, fontSize: 14),
                          decoration: InputDecoration(
                            hintText: '••••••••',
                            filled: true,
                            fillColor: inputBgColor,
                            contentPadding: const EdgeInsets.symmetric(
                              horizontal: 16,
                              vertical: 10,
                            ),
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(20),
                              borderSide: BorderSide(color: inputBorderColor),
                            ),
                            enabledBorder: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(20),
                              borderSide: BorderSide(color: inputBorderColor),
                            ),
                            focusedBorder: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(20),
                              borderSide: const BorderSide(
                                color: Color(0xFF0284C7),
                              ),
                            ),
                            suffixIcon: IconButton(
                              icon: Icon(
                                _obscure
                                    ? Icons.visibility_off_outlined
                                    : Icons.visibility_outlined,
                                color: bodyTextColor,
                                size: 18,
                              ),
                              onPressed: () =>
                                  setState(() => _obscure = !_obscure),
                            ),
                          ),
                          validator: (v) {
                            if (v == null || v.isEmpty) {
                              return 'Campo obrigatório';
                            }
                            if (v.length < 6) return 'Mínimo 6 caracteres';
                            return null;
                          },
                        ),
                        const SizedBox(height: 16),

                        Row(
                          children: [
                            const Icon(
                              Icons.lock_outline,
                              size: 15,
                              color: Color(0xFF0284C7),
                            ),
                            const SizedBox(width: 6),
                            Text(
                              'Confirmar nova senha',
                              style: TextStyle(
                                color: titleTextColor,
                                fontSize: 13,
                                fontWeight: FontWeight.w600,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _confirmaCtrl,
                          obscureText: _obscure,
                          style: TextStyle(color: titleTextColor, fontSize: 14),
                          decoration: InputDecoration(
                            hintText: '••••••••',
                            filled: true,
                            fillColor: inputBgColor,
                            contentPadding: const EdgeInsets.symmetric(
                              horizontal: 16,
                              vertical: 10,
                            ),
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(20),
                              borderSide: BorderSide(color: inputBorderColor),
                            ),
                            enabledBorder: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(20),
                              borderSide: BorderSide(color: inputBorderColor),
                            ),
                            focusedBorder: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(20),
                              borderSide: const BorderSide(
                                color: Color(0xFF0284C7),
                              ),
                            ),
                          ),
                          validator: (v) {
                            if (v == null || v.isEmpty) {
                              return 'Campo obrigatório';
                            }
                            if (v != _senhaCtrl.text) {
                              return 'As senhas não coincidem';
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: 24),

                        SizedBox(
                          width: double.infinity,
                          height: 44,
                          child: _loading
                              ? const Center(child: CircularProgressIndicator())
                              : ElevatedButton(
                                  onPressed: _redefinir,
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: const Color(0xFF0284C7),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(25),
                                    ),
                                  ),
                                  child: const Text(
                                    'Redefinir senha',
                                    style: TextStyle(
                                      color: Colors.white,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),
          const AccessibilityPanel(),
        ],
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
