import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:mask_text_input_formatter/mask_text_input_formatter.dart';
import 'appcolor.dart';

class CadastroPage extends StatefulWidget {
  const CadastroPage({super.key});

  @override
  State<CadastroPage> createState() => _CadastroPageState();
}

class _CadastroPageState extends State<CadastroPage> {
  final _formKey = GlobalKey<FormState>();

  final cpfCtrl = TextEditingController();
  final emailCtrl = TextEditingController();
  final dataNascCtrl = TextEditingController();
  final telefoneCtrl = TextEditingController();
  final cartaoCtrl = TextEditingController();
  final senhaCtrl = TextEditingController();

  bool _obscureSenha = true;
  bool _loading = false;

  // ✔️ Máscaras
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

  final cartaoMask = MaskTextInputFormatter(
    mask: '#### #### #### ####',
    filter: {"#": RegExp(r'[0-9]')},
  );

  Future<void> _cadastrar() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _loading = true);
    await Future.delayed(const Duration(milliseconds: 900));
    setState(() => _loading = false);

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Conta criada com sucesso!')),
      );
      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.bgBase,
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/logos/Logo/LogoDark2.png', height: 40),
            const SizedBox(width: 8),
            const Text('Criar Conta'),
          ],
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Cadastro',
                style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 4),
              const Text(
                'Preencha os dados para acessar a plataforma.',
                style: TextStyle(color: AppColors.textMuted, fontSize: 13),
              ),
              const SizedBox(height: 24),

              Form(
                key: _formKey,
                child: Column(
                  children: [
                    _field(
                      cpfCtrl,
                      'CPF',
                      Icons.badge_outlined,
                      keyboard: TextInputType.number,
                      inputFormatters: [cpfMask],
                      validator: (v) {
                        if (v == null || v.isEmpty) return 'Campo obrigatório';
                        if (!_cpfValido(v)) return 'CPF inválido';
                        return null;
                      },
                    ),
                    _field(
                      emailCtrl,
                      'E-mail',
                      Icons.email_outlined,
                      keyboard: TextInputType.emailAddress,
                      validator: (v) {
                        if (v == null || v.isEmpty) return 'Campo obrigatório';
                        if (!_emailValido(v)) return 'E-mail inválido';
                        return null;
                      },
                    ),
                    _field(
                      dataNascCtrl,
                      'Data de nascimento',
                      Icons.calendar_today_outlined,
                      keyboard: TextInputType.number,
                      hint: 'DD/MM/AAAA',
                      inputFormatters: [dataMask],
                    ),
                    _field(
                      telefoneCtrl,
                      'Telefone',
                      Icons.phone_outlined,
                      keyboard: TextInputType.phone,
                      inputFormatters: [telefoneMask],
                    ),
                    _passwordField(senhaCtrl, 'Senha', _obscureSenha, () {
                      setState(() => _obscureSenha = !_obscureSenha);
                    }),
                    _field(
                      cartaoCtrl,
                      'Cartão IoT (opcional)',
                      Icons.credit_card_outlined,
                      required: false,
                      inputFormatters: [cartaoMask],
                    ),
                    const SizedBox(height: 8),

                    SizedBox(
                      width: double.infinity,
                      child: _loading
                          ? const Center(child: CircularProgressIndicator())
                          : ElevatedButton(
                              onPressed: _cadastrar,
                              child: const Text('Criar Conta'),
                            ),
                    ),

                    const SizedBox(height: 14),

                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Text(
                          'Já tem conta?',
                          style: TextStyle(
                            color: AppColors.textMuted,
                            fontSize: 13,
                          ),
                        ),
                        TextButton(
                          onPressed: () => Navigator.pop(context),
                          child: const Text(
                            'Fazer login',
                            style: TextStyle(fontSize: 13),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _field(
    TextEditingController ctrl,
    String label,
    IconData icon, {
    TextInputType keyboard = TextInputType.text,
    String? hint,
    bool required = true,
    List<TextInputFormatter>? inputFormatters,
    String? Function(String?)? validator,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 14),
      child: TextFormField(
        controller: ctrl,
        keyboardType: keyboard,
        inputFormatters: inputFormatters,
        decoration: InputDecoration(
          labelText: label,
          hintText: hint,
          prefixIcon: Icon(icon, size: 20),
        ),
        validator: validator ??
            (required
                ? (v) => v!.isEmpty ? 'Campo obrigatório' : null
                : null),
      ),
    );
  }

  /// Valida um e-mail com uma checagem simples de formato.
  bool _emailValido(String email) {
    return RegExp(r'^[^@\s]+@[^@\s]+\.[^@\s]+$').hasMatch(email.trim());
  }

  /// Valida um CPF (com ou sem máscara) pelo algoritmo oficial de dígitos
  /// verificadores — mesma lógica usada no backend web (UsuarioController).
  /// Também rejeita sequências de dígitos repetidos (ex.: 111.111.111-11).
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

  Widget _passwordField(
    TextEditingController ctrl,
    String label,
    bool obscure,
    VoidCallback toggle,
  ) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 14),
      child: TextFormField(
        controller: ctrl,
        obscureText: obscure,
        decoration: InputDecoration(
          labelText: label,
          prefixIcon: const Icon(Icons.lock_outline, size: 20),
          suffixIcon: IconButton(
            icon: Icon(
              obscure
                  ? Icons.visibility_off_outlined
                  : Icons.visibility_outlined,
              size: 20,
            ),
            onPressed: toggle,
          ),
        ),
        validator: (v) {
          if (v!.isEmpty) return 'Campo obrigatório';
          if (label == 'Senha' && v.length < 6) {
            return 'Mínimo 6 caracteres';
          }
          return null;
        },
      ),
    );
  }

  @override
  void dispose() {
    cpfCtrl.dispose();
    emailCtrl.dispose();
    dataNascCtrl.dispose();
    telefoneCtrl.dispose();
    cartaoCtrl.dispose();
    senhaCtrl.dispose();
    super.dispose();
  }
}
