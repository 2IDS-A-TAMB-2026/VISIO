import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:mask_text_input_formatter/mask_text_input_formatter.dart';
import 'appcolor.dart';
import 'services/api_client.dart';
import 'services/tts_service.dart';
import 'widgets/accessibility_panel.dart';

class CadastroPage extends StatefulWidget {
  const CadastroPage({super.key});

  @override
  State<CadastroPage> createState() => _CadastroPageState();
}

class _CadastroPageState extends State<CadastroPage> {
  final _formKey = GlobalKey<FormState>();

  final nomeCtrl = TextEditingController();
  final cpfCtrl = TextEditingController();
  final emailCtrl = TextEditingController();
  final dataNascCtrl = TextEditingController();
  final telefoneCtrl = TextEditingController();
  final senhaCtrl = TextEditingController();

  bool _obscureSenha = true;
  bool _loading = false;

  final cpfMask = MaskTextInputFormatter(
    mask: '###.###.###-##',
    filter: {"#": RegExp(r'[0-9]')},
  );

  final telefoneMask = MaskTextInputFormatter(
    mask: '(##) #####-####',
    filter: {"#": RegExp(r'[0-9]')},
  );

  final dataMask = MaskTextInputFormatter(
    mask: '##/##/####',
    filter: {"#": RegExp(r'[0-9]')},
  );

  Future<void> _cadastrar() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _loading = true);

    try {
      final corpo = await ApiClient.postForm('usuarios', {
        'nome': nomeCtrl.text.trim(),
        'cpf': cpfCtrl.text.trim(),
        'email': emailCtrl.text.trim(),
        'data_nascimento': _brParaIso(dataNascCtrl.text) ?? '',
        'telefone': telefoneCtrl.text.trim(),
        'senha': senhaCtrl.text,
      });

      if (!mounted) return;
      setState(() => _loading = false);

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            (corpo is Map ? corpo['message'] as String? : null) ??
                'Conta criada com sucesso!',
          ),
        ),
      );
      Navigator.pop(context);
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

  String? _brParaIso(String br) {
    final limpo = br.replaceAll(RegExp(r'\D'), '');
    if (limpo.length != 8) return null;
    return '${limpo.substring(4, 8)}-${limpo.substring(2, 4)}-${limpo.substring(0, 2)}';
  }

  bool _emailValido(String email) {
    return RegExp(r'^[^@\s]+@[^@\s]+\.[^@\s]+$').hasMatch(email.trim());
  }

  bool _cpfValido(String cpfComMascara) {
    final cpf = cpfComMascara.replaceAll(RegExp(r'\D'), '');
    if (cpf.length != 11) return false;
    if (RegExp(r'^(\d)\1{10}$').hasMatch(cpf)) return false;

    for (var posicaoDigito = 9; posicaoDigito <= 10; posicaoDigito++) {
      var soma = 0;
      for (var i = 0; i < posicaoDigito; i++) {
        soma += int.parse(cpf[i]) * ((posicaoDigito + 1) - i);
      }
      final resto = soma % 11;
      final digitoEsperado = (resto < 2) ? 0 : 11 - resto;

      if (int.parse(cpf[posicaoDigito]) != digitoEsperado) return false;
    }
    return true;
  }

  @override
  void dispose() {
    nomeCtrl.dispose();
    cpfCtrl.dispose();
    emailCtrl.dispose();
    dataNascCtrl.dispose();
    telefoneCtrl.dispose();
    senhaCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    TtsService.instance.definirTextoDaPagina(
      'Tela de cadastro. Preencha nome, CPF, e-mail, telefone, data de '
      'nascimento e senha para criar sua conta VISIO.',
    );

    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

    // Definição dinâmicas de cores para Dark/Light Mode
    final bgColor = isDark ? const Color(0xFF030712) : const Color(0xFFF1F5F9);
    final cardBgColor = isDark ? const Color(0xFF0F172A) : Colors.white;
    final borderColor = isDark
        ? const Color(0xFF1E293B)
        : const Color(0xFFE2E8F0);
    final titleTextColor = isDark ? Colors.white : const Color(0xFF0F172A);
    final bodyTextColor = isDark
        ? const Color(0xFF94A3B8)
        : const Color(0xFF64748B);

    return Scaffold(
      backgroundColor: bgColor,
      body: Stack(
        children: [
          Column(
            children: [
              _buildTopNavBar(
                context,
                isDark,
                titleTextColor,
                bodyTextColor,
                borderColor,
              ),
              Expanded(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 16,
                    vertical: 24,
                  ),
                  child: Center(
                    child: Container(
                      constraints: const BoxConstraints(maxWidth: 1000),
                      padding: const EdgeInsets.all(24),
                      decoration: BoxDecoration(
                        color: cardBgColor,
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(color: borderColor),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withValues(
                              alpha: isDark ? 0.4 : 0.05,
                            ),
                            blurRadius: 20,
                            offset: const Offset(0, 8),
                          ),
                        ],
                      ),
                      child: LayoutBuilder(
                        builder: (context, constraints) {
                          bool isWide = constraints.maxWidth > 700;
                          return isWide
                              ? Row(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Expanded(
                                      flex: 6,
                                      child: _buildFormSide(
                                        isDark,
                                        titleTextColor,
                                        bodyTextColor,
                                        borderColor,
                                      ),
                                    ),
                                    const SizedBox(width: 32),
                                  ],
                                )
                              : Column(
                                  children: [
                                    _buildFormSide(
                                      isDark,
                                      titleTextColor,
                                      bodyTextColor,
                                      borderColor,
                                    ),
                                    const SizedBox(height: 32),
                                  ],
                                );
                        },
                      ),
                    ),
                  ),
                ),
              ),
            ],
          ),
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
    Color borderColor,
  ) {
    return Container(
      height: 60,
      padding: const EdgeInsets.symmetric(horizontal: 16),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF030712) : Colors.white,
        border: Border(bottom: BorderSide(color: borderColor)),
      ),
      child: Row(
        children: [
          Image.asset('assets/images/logos/Logo/LogoDark2.png', height: 28),
          const SizedBox(width: 8),
        ],
      ),
    );
  }


  Widget _buildFormSide(
    bool isDark,
    Color titleColor,
    Color bodyColor,
    Color borderColor,
  ) {
    return Form(
      key: _formKey,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Criar Conta',
            style: TextStyle(
              fontSize: 28,
              fontWeight: FontWeight.bold,
              color: titleColor,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            'Cadastre-se para acessar o ecossistema VISIO.',
            style: TextStyle(color: bodyColor, fontSize: 14),
          ),
          const SizedBox(height: 24),

          _buildCustomField(
            controller: nomeCtrl,
            label: 'Nome Completo',
            hint: 'Ex: João Silva',
            icon: Icons.person_outline,
            isDark: isDark,
            titleColor: titleColor,
            borderColor: borderColor,
          ),

          LayoutBuilder(
            builder: (context, constraints) {
              bool isCompact = constraints.maxWidth < 340;
              if (isCompact) {
                return Column(
                  children: [
                    _buildCustomField(
                      controller: cpfCtrl,
                      label: 'CPF',
                      hint: '000.000.000-00',
                      icon: Icons.badge_outlined,
                      keyboard: TextInputType.number,
                      inputFormatters: [cpfMask],
                      isDark: isDark,
                      titleColor: titleColor,
                      borderColor: borderColor,
                      validator: (v) {
                        if (v == null || v.isEmpty) return 'Campo obrigatório';
                        if (!_cpfValido(v)) return 'CPF inválido';
                        return null;
                      },
                    ),
                    _buildCustomField(
                      controller: telefoneCtrl,
                      label: 'Telefone',
                      hint: '(00) 00000-0000',
                      icon: Icons.phone_android_outlined,
                      keyboard: TextInputType.phone,
                      inputFormatters: [telefoneMask],
                      isDark: isDark,
                      titleColor: titleColor,
                      borderColor: borderColor,
                    ),
                  ],
                );
              }
              return Row(
                children: [
                  Expanded(
                    child: _buildCustomField(
                      controller: cpfCtrl,
                      label: 'CPF',
                      hint: '000.000.000-00',
                      icon: Icons.badge_outlined,
                      keyboard: TextInputType.number,
                      inputFormatters: [cpfMask],
                      isDark: isDark,
                      titleColor: titleColor,
                      borderColor: borderColor,
                      validator: (v) {
                        if (v == null || v.isEmpty) return 'Campo obrigatório';
                        if (!_cpfValido(v)) return 'CPF inválido';
                        return null;
                      },
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: _buildCustomField(
                      controller: telefoneCtrl,
                      label: 'Telefone',
                      hint: '(00) 00000-0000',
                      icon: Icons.phone_android_outlined,
                      keyboard: TextInputType.phone,
                      inputFormatters: [telefoneMask],
                      isDark: isDark,
                      titleColor: titleColor,
                      borderColor: borderColor,
                    ),
                  ),
                ],
              );
            },
          ),

          _buildCustomField(
            controller: emailCtrl,
            label: 'E-mail',
            hint: 'nome@empresa.com',
            icon: Icons.email_outlined,
            keyboard: TextInputType.emailAddress,
            isDark: isDark,
            titleColor: titleColor,
            borderColor: borderColor,
            validator: (v) {
              if (v == null || v.isEmpty) return 'Campo obrigatório';
              if (!_emailValido(v)) return 'E-mail inválido';
              return null;
            },
          ),

          LayoutBuilder(
            builder: (context, constraints) {
              bool isCompact = constraints.maxWidth < 340;
              if (isCompact) {
                return Column(
                  children: [
                    _buildCustomField(
                      controller: dataNascCtrl,
                      label: 'Nascimento',
                      hint: 'dd/mm/aaaa',
                      icon: Icons.calendar_today_outlined,
                      keyboard: TextInputType.number,
                      inputFormatters: [dataMask],
                      isDark: isDark,
                      titleColor: titleColor,
                      borderColor: borderColor,
                    ),
                  ],
                );
              }
              return Row(
                children: [
                  Expanded(
                    child: _buildCustomField(
                      controller: dataNascCtrl,
                      label: 'Nascimento',
                      hint: 'dd/mm/aaaa',
                      icon: Icons.calendar_today_outlined,
                      keyboard: TextInputType.number,
                      inputFormatters: [dataMask],
                      isDark: isDark,
                      titleColor: titleColor,
                      borderColor: borderColor,
                    ),
                  ),
                  const SizedBox(width: 12),
                ],
              );
            },
          ),

          _buildPasswordField(isDark, titleColor, borderColor),

          const SizedBox(height: 16),

          SizedBox(
            width: double.infinity,
            height: 46,
            child: _loading
                ? const Center(child: CircularProgressIndicator())
                : ElevatedButton.icon(
                    onPressed: _cadastrar,
                    icon: const Icon(Icons.person_add, size: 18),
                    label: const Text(
                      'Finalizar Cadastro',
                      style: TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF0284C7),
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(10),
                      ),
                      elevation: 0,
                    ),
                  ),
          ),

          const SizedBox(height: 16),

          Center(
            child: TextButton.icon(
              onPressed: () => Navigator.pop(context),
              icon: const Icon(
                Icons.arrow_back,
                size: 16,
                color: Color(0xFF0284C7),
              ),
              label: const Text(
                'Voltar para o login',
                style: TextStyle(color: Color(0xFF0284C7), fontSize: 13),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCustomField({
    required TextEditingController controller,
    required String label,
    required String hint,
    required IconData icon,
    required bool isDark,
    required Color titleColor,
    required Color borderColor,
    TextInputType keyboard = TextInputType.text,
    bool required = true,
    List<TextInputFormatter>? inputFormatters,
    String? Function(String?)? validator,
  }) {
    final inputFillColor = isDark
        ? const Color(0xFF020617)
        : const Color(0xFFF8FAFC);
    final inputTextColor = isDark ? Colors.white : const Color(0xFF0F172A);
    final hintColor = isDark
        ? const Color(0xFF475569)
        : const Color(0xFF94A3B8);

    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.circle, size: 6, color: Color(0xFF0284C7)),
              const SizedBox(width: 6),
              Text(
                label,
                style: TextStyle(
                  color: titleColor,
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
          const SizedBox(height: 6),
          TextFormField(
            controller: controller,
            keyboardType: keyboard,
            inputFormatters: inputFormatters,
            style: TextStyle(color: inputTextColor, fontSize: 13),
            decoration: InputDecoration(
              hintText: hint,
              hintStyle: TextStyle(color: hintColor, fontSize: 13),
              filled: true,
              fillColor: inputFillColor,
              contentPadding: const EdgeInsets.symmetric(
                horizontal: 12,
                vertical: 12,
              ),
              enabledBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
                borderSide: BorderSide(color: borderColor),
              ),
              focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
                borderSide: const BorderSide(color: Color(0xFF0284C7)),
              ),
              errorBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
                borderSide: BorderSide(color: AppColors.danger),
              ),
              focusedErrorBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
                borderSide: BorderSide(color: AppColors.danger),
              ),
            ),
            validator:
                validator ??
                (required
                    ? (v) => v == null || v.isEmpty ? 'Campo obrigatório' : null
                    : null),
          ),
        ],
      ),
    );
  }

  Widget _buildPasswordField(bool isDark, Color titleColor, Color borderColor) {
    final inputFillColor = isDark
        ? const Color(0xFF020617)
        : const Color(0xFFF8FAFC);
    final inputTextColor = isDark ? Colors.white : const Color(0xFF0F172A);
    final hintColor = isDark
        ? const Color(0xFF475569)
        : const Color(0xFF94A3B8);

    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.circle, size: 6, color: Color(0xFF0284C7)),
              const SizedBox(width: 6),
              Text(
                'Senha',
                style: TextStyle(
                  color: titleColor,
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
          const SizedBox(height: 6),
          TextFormField(
            controller: senhaCtrl,
            obscureText: _obscureSenha,
            style: TextStyle(color: inputTextColor, fontSize: 13),
            decoration: InputDecoration(
              hintText: '••••••••',
              hintStyle: TextStyle(color: hintColor, fontSize: 13),
              filled: true,
              fillColor: inputFillColor,
              contentPadding: const EdgeInsets.symmetric(
                horizontal: 12,
                vertical: 12,
              ),
              suffixIcon: IconButton(
                icon: Icon(
                  _obscureSenha
                      ? Icons.visibility_off_outlined
                      : Icons.visibility_outlined,
                  size: 18,
                  color: hintColor,
                ),
                onPressed: () => setState(() => _obscureSenha = !_obscureSenha),
              ),
              enabledBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
                borderSide: BorderSide(color: borderColor),
              ),
              focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
                borderSide: const BorderSide(color: Color(0xFF0284C7)),
              ),
              errorBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
                borderSide: BorderSide(color: AppColors.danger),
              ),
              focusedErrorBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
                borderSide: BorderSide(color: AppColors.danger),
              ),
            ),
            validator: (v) {
              if (v == null || v.isEmpty) return 'Campo obrigatório';
              if (v.length < 6) return 'Mínimo 6 caracteres';
              return null;
            },
          ),
        ],
      ),
    );
  }
}
