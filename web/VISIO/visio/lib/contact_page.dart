import 'package:flutter/material.dart';

class ContatoScreen extends StatefulWidget {
  const ContatoScreen({super.key});

  @override
  State<ContatoScreen> createState() => _ContatoScreenState();
}

class _ContatoScreenState extends State<ContatoScreen> {
  final _formKey = GlobalKey<FormState>();

  final _nomeController = TextEditingController();
  final _emailController = TextEditingController();
  final _mensagemController = TextEditingController();

  bool _isLoading = false;

  void _enviarMensagem() async {
    if (_formKey.currentState!.validate()) {
      setState(() => _isLoading = true);

      await Future.delayed(const Duration(seconds: 2));

      setState(() => _isLoading = false);

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Mensagem enviada com sucesso!"),
          backgroundColor: Colors.green,
        ),
      );

      _formKey.currentState!.reset();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        backgroundColor: const Color(0xFF17182c),
        title: const Text("Contato"),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              "Entre em Contato",
              style: TextStyle(
                fontSize: 26,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 10),
            const Text(
              "Tem dúvidas sobre a plataforma VISIO? Quer aplicar em sua instituição? Fale conosco.",
              style: TextStyle(color: Colors.white70),
            ),
            const SizedBox(height: 30),

            /// Informações
            _infoItem(Icons.email, "contato@visio.com"),
            _infoItem(Icons.phone, "(00) 00000-0000"),
            _infoItem(Icons.location_on, "Brasil"),

            const SizedBox(height: 40),

            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: const Color(0xFF121212),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Form(
                key: _formKey,
                child: Column(
                  children: [
                    _buildInput(
                      controller: _nomeController,
                      label: "Seu nome",
                      validator: (value) =>
                          value!.isEmpty ? "Informe seu nome" : null,
                    ),
                    const SizedBox(height: 20),
                    _buildInput(
                      controller: _emailController,
                      label: "Seu email",
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return "Informe seu email";
                        }
                        if (!value.contains("@")) {
                          return "Email inválido";
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 20),
                    _buildInput(
                      controller: _mensagemController,
                      label: "Sua mensagem",
                      maxLines: 4,
                      validator: (value) =>
                          value!.isEmpty ? "Digite uma mensagem" : null,
                    ),
                    const SizedBox(height: 30),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _isLoading ? null : _enviarMensagem,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF1e6be7),
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(14),
                          ),
                        ),
                        child: _isLoading
                            ? const SizedBox(
                                height: 22,
                                width: 22,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2,
                                  color: Colors.white,
                                ),
                              )
                            : const Text(
                                "Enviar Mensagem",
                                style: TextStyle(
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                      ),
                    )
                  ],
                ),
              ),
            ),

            const SizedBox(height: 40),
            const Center(
              child: Text(
                "© 2026 VISIO",
                style: TextStyle(
                  fontSize: 12,
                  color: Colors.white38,
                ),
              ),
            )
          ],
        ),
      ),
    );
  }

  Widget _buildInput({
    required TextEditingController controller,
    required String label,
    String? Function(String?)? validator,
    int maxLines = 1,
  }) {
    return TextFormField(
      controller: controller,
      validator: validator,
      maxLines: maxLines,
      style: const TextStyle(color: Colors.white),
      decoration: InputDecoration(
        labelText: label,
        labelStyle: const TextStyle(color: Colors.white70),
        filled: true,
        fillColor: const Color(0xFF1a1a1a),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: BorderSide.none,
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(
            color: Color(0xFF1e6be7),
          ),
        ),
      ),
    );
  }

  Widget _infoItem(IconData icon, String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 15),
      child: Row(
        children: [
          Icon(icon, color: const Color(0xFF47cdfd)),
          const SizedBox(width: 10),
          Text(
            text,
            style: const TextStyle(color: Colors.white70),
          )
        ],
      ),
    );
  }
}