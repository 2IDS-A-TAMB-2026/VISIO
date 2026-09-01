import 'package:flutter/material.dart';
import 'appcolor.dart';

/// NÃO INTEGRADO DE PROPÓSITO: ao contrário das outras telas, este
/// formulário de contato não tem NENHUM endpoint correspondente no backend
/// CodeIgniter (não existe ContatoController nem rota "/contato" — só
/// SobreController, que é uma página estática). Como a tarefa era deixar o
/// app "igual ao site", e o site não tem esse recurso, mantivemos a
/// simulação local em vez de inventar um endpoint nos dois projetos.
class ContactPage extends StatefulWidget {
  const ContactPage({super.key});

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

  Future<void> _enviar() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _loading = true);
    await Future.delayed(const Duration(milliseconds: 1000));
    setState(() {
      _loading = false;
      _enviado = true;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/logos/Logo/LogoDark2.png', height: 40),
            SizedBox(width: 8),
            Text('Contato'),
          ],
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: _enviado ? _buildSucesso(context) : _buildConteudo(context),
        ),
      ),
    );
  }

  Widget _buildConteudo(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Entre em Contato',
          style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 6),
        Text(
          'Tem dúvidas sobre a plataforma VISIO? Fale com a gente.',
          style: TextStyle(color: context.textMuted, fontSize: 13, height: 1.5),
        ),
        const SizedBox(height: 24),

        // Info cards
        Row(
          children: [
            Expanded(
              child: _contactInfo(
                context,
                Icons.email_outlined,
                'E-mail',
                'visio@gmail.com',
              ),
            ),
            const SizedBox(width: 10),
            Expanded(
              child: _contactInfo(
                context,
                Icons.phone_outlined,
                'Telefone',
                '(00) 00000-0000',
              ),
            ),
          ],
        ),
        const SizedBox(height: 10),
        _contactInfo(
          context,
          Icons.location_on_outlined,
          'Localização',
          'Brasil',
          full: true,
        ),

        const SizedBox(height: 28),

        const Text(
          'Enviar mensagem',
          style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 14),

        Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                controller: nomeCtrl,
                decoration: const InputDecoration(
                  labelText: 'Seu nome',
                  prefixIcon: Icon(Icons.person_outline, size: 20),
                ),
                validator: (v) => v!.isEmpty ? 'Informe seu nome' : null,
              ),
              const SizedBox(height: 14),
              TextFormField(
                controller: emailCtrl,
                keyboardType: TextInputType.emailAddress,
                decoration: const InputDecoration(
                  labelText: 'Seu e-mail',
                  prefixIcon: Icon(Icons.email_outlined, size: 20),
                ),
                validator: (v) {
                  if (v!.isEmpty) return 'Informe seu e-mail';
                  if (!v.contains('@')) return 'E-mail inválido';
                  return null;
                },
              ),
              const SizedBox(height: 14),
              TextFormField(
                controller: msgCtrl,
                maxLines: 5,
                decoration: const InputDecoration(
                  labelText: 'Sua mensagem',
                  alignLabelWithHint: true,
                  prefixIcon: Padding(
                    padding: EdgeInsets.only(bottom: 80),
                    child: Icon(Icons.message_outlined, size: 20),
                  ),
                ),
                validator: (v) => v!.isEmpty ? 'Escreva sua mensagem' : null,
              ),
              const SizedBox(height: 20),
              SizedBox(
                width: double.infinity,
                child: _loading
                    ? const Center(child: CircularProgressIndicator())
                    : ElevatedButton.icon(
                        onPressed: _enviar,
                        icon: const Icon(Icons.send_outlined, size: 18),
                        label: const Text('Enviar Mensagem'),
                      ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _contactInfo(
    BuildContext context,
    IconData icon,
    String label,
    String value, {
    bool full = false,
  }) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: context.cardBg,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: context.borderColor),
      ),
      child: Row(
        children: [
          Icon(icon, color: AppColors.primary, size: 18),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  label,
                  style: TextStyle(fontSize: 11, color: context.textMuted),
                ),
                Text(
                  value,
                  style: const TextStyle(
                    fontSize: 13,
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSucesso(BuildContext context) {
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
            Icons.check_circle_outline,
            size: 56,
            color: AppColors.success,
          ),
        ),
        const SizedBox(height: 24),
        const Text(
          'Mensagem enviada!',
          style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 10),
        Text(
          'Obrigado pelo contato. Nossa equipe responderá em breve.',
          textAlign: TextAlign.center,
          style: TextStyle(color: context.textMuted, fontSize: 14, height: 1.5),
        ),
        const SizedBox(height: 40),
        SizedBox(
          width: double.infinity,
          child: ElevatedButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Voltar'),
          ),
        ),
      ],
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
