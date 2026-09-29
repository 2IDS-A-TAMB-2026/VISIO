import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'services/api_client.dart';

class ContactPage extends StatefulWidget {
  final VoidCallback? onToggleTheme;

  const ContactPage({super.key, this.onToggleTheme});

  @override
  State<ContactPage> createState() => _ContactPageState();
}

class _ContactPageState extends State<ContactPage> {
  final _formKey = GlobalKey<FormState>();
  final nomeCtrl = TextEditingController();
  final emailCtrl = TextEditingController();
  final msgCtrl = TextEditingController();
  bool _loading = false;
  bool _enviado = false;

  // Estados de acessibilidade locais
  final double _textScale = 1.0;
  final bool _highContrast = false;

  Future<void> _enviar() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _loading = true);

    try {
      await ApiClient.postForm('contato/enviar', {
        'nome': nomeCtrl.text.trim(),
        'email': emailCtrl.text.trim(),
        'mensagem': msgCtrl.text.trim(),
      });
      if (!mounted) return;
      setState(() {
        _loading = false;
        _enviado = true;
      });
    } on ApiException catch (e) {
      if (!mounted) return;
      setState(() => _loading = false);
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text(e.mensagem)));
    } catch (_) {
      if (!mounted) return;
      setState(() => _loading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Erro de conexão com o servidor.')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return MediaQuery(
      data: MediaQuery.of(
        context,
      ).copyWith(textScaler: TextScaler.linear(_textScale)),
      child: Scaffold(
        appBar: AppBar(
          elevation: 0,
          title: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Image.asset(
                isDark
                    ? 'assets/images/logos/Logo/LogoDark2.png'
                    : 'assets/images/logos/Logo/LogoLight2.png',
                height: 28,
              ),
              const SizedBox(width: 10),
            ],
          ),
        ),
        body: SafeArea(
          child: Center(
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 1100),
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(
                  horizontal: 24,
                  vertical: 32,
                ),
                child: _enviado
                    ? _buildSucesso(context)
                    : _buildConteudo(context),
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildConteudo(BuildContext context) {
    return LayoutBuilder(
      builder: (context, constraints) {
        bool isWide = constraints.maxWidth > 800;

        if (isWide) {
          return Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(flex: 2, child: _buildInfoSide(context)),
              const SizedBox(width: 32),
              Expanded(flex: 3, child: _buildFormCard(context)),
            ],
          );
        }

        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildInfoSide(context),
            const SizedBox(height: 32),
            _buildFormCard(context),
          ],
        );
      },
    );
  }

  Widget _buildInfoSide(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
          decoration: BoxDecoration(
            color: AppColors.primary.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(20),
          ),
          child: const Text(
            'Fale Conosco',
            style: TextStyle(
              color: AppColors.primary,
              fontSize: 12,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
        const SizedBox(height: 12),
        const Text(
          'Entre em Contato',
          style: TextStyle(
            fontSize: 28,
            fontWeight: FontWeight.bold,
            letterSpacing: -0.5,
          ),
        ),
        const SizedBox(height: 8),
        Text(
          'Tem dúvidas sobre a plataforma VISIO ou quer saber mais sobre nossos serviços? Fale com nossa equipe.',
          style: TextStyle(color: context.textMuted, fontSize: 14, height: 1.6),
        ),
        const SizedBox(height: 28),
        _contactInfoCard(
          context,
          Icons.email_outlined,
          'E-mail',
          'visio.suporte1@gmail.com',
        ),
        const SizedBox(height: 12),
        _contactInfoCard(
          context,
          Icons.phone_outlined,
          'Telefone',
          '(00) 00000-0000',
        ),
        const SizedBox(height: 12),
        _contactInfoCard(
          context,
          Icons.location_on_outlined,
          'Localização',
          'Brasil',
        ),
      ],
    );
  }

  Widget _buildFormCard(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(28),
      decoration: BoxDecoration(
        color: _highContrast ? Colors.black : context.cardBg,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: _highContrast ? Colors.white : context.borderColor,
          width: _highContrast ? 2 : 1,
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.03),
            blurRadius: 15,
            offset: const Offset(0, 5),
          ),
        ],
      ),
      child: Form(
        key: _formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Enviar mensagem',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 20),
            TextFormField(
              controller: nomeCtrl,
              decoration: _inputDecoration('Seu nome', Icons.person_outline),
              validator: (v) => v!.isEmpty ? 'Informe seu nome' : null,
            ),
            const SizedBox(height: 16),
            TextFormField(
              controller: emailCtrl,
              keyboardType: TextInputType.emailAddress,
              decoration: _inputDecoration('Seu e-mail', Icons.email_outlined),
              validator: (v) {
                if (v!.isEmpty) return 'Informe seu e-mail';
                if (!v.contains('@')) return 'E-mail inválido';
                return null;
              },
            ),
            const SizedBox(height: 16),
            TextFormField(
              controller: msgCtrl,
              maxLines: 4,
              decoration: _inputDecoration(
                'Sua mensagem',
                Icons.chat_bubble_outline,
              ),
              validator: (v) => v!.isEmpty ? 'Escreva sua mensagem' : null,
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              height: 48,
              child: _loading
                  ? const Center(child: CircularProgressIndicator())
                  : ElevatedButton.icon(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primary,
                        foregroundColor: Colors.white,
                        elevation: 0,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(10),
                        ),
                      ),
                      onPressed: _enviar,
                      icon: const Icon(Icons.send_rounded, size: 18),
                      label: const Text(
                        'Enviar Mensagem',
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          fontSize: 15,
                        ),
                      ),
                    ),
            ),
          ],
        ),
      ),
    );
  }

  InputDecoration _inputDecoration(String label, IconData icon) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    return InputDecoration(
      labelText: label,
      prefixIcon: Icon(icon, size: 20),
      filled: true,
      fillColor: isDark ? const Color(0xFF1E293B) : const Color(0xFFF8FAFC),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10),
        borderSide: BorderSide.none,
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10),
        borderSide: BorderSide(
          color: _highContrast ? Colors.white : context.borderColor,
        ),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10),
        borderSide: const BorderSide(color: AppColors.primary, width: 1.5),
      ),
    );
  }

  Widget _contactInfoCard(
    BuildContext context,
    IconData icon,
    String label,
    String value,
  ) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: _highContrast ? Colors.black : context.cardBg,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(
          color: _highContrast ? Colors.white : context.borderColor,
          width: _highContrast ? 2 : 1,
        ),
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: AppColors.primary.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(icon, color: AppColors.primary, size: 20),
          ),
          const SizedBox(width: 14),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                label,
                style: TextStyle(fontSize: 11, color: context.textMuted),
              ),
              const SizedBox(height: 2),
              Text(
                value,
                style: const TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildSucesso(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(40),
      decoration: BoxDecoration(
        color: context.cardBg,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: context.borderColor),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: AppColors.success.withValues(alpha: 0.1),
              shape: BoxShape.circle,
            ),
            child: const Icon(
              Icons.check_circle_rounded,
              size: 64,
              color: AppColors.success,
            ),
          ),
          const SizedBox(height: 24),
          const Text(
            'Mensagem Enviada!',
            style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 10),
          Text(
            'Obrigado pelo contato. Nossa equipe analisará sua mensagem e responderá o mais breve possível.',
            textAlign: TextAlign.center,
            style: TextStyle(
              color: context.textMuted,
              fontSize: 14,
              height: 1.5,
            ),
          ),
          const SizedBox(height: 32),
          SizedBox(
            width: 200,
            height: 44,
            child: ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
              onPressed: () => Navigator.pop(context),
              child: const Text('Voltar'),
            ),
          ),
        ],
      ),
    );
  }

  @override
  void dispose() {
    nomeCtrl.dispose();
    emailCtrl.dispose();
    msgCtrl.dispose();
    super.dispose();
  }
}
