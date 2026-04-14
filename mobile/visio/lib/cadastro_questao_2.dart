import 'package:flutter/material.dart';

class NovaQuestaoPage extends StatefulWidget {
  @override
  _NovaQuestaoPageState createState() => _NovaQuestaoPageState();
}

class _NovaQuestaoPageState extends State<NovaQuestaoPage> {
  final TextEditingController descricaoController = TextEditingController();
  final TextEditingController altA = TextEditingController();
  final TextEditingController altB = TextEditingController();
  final TextEditingController altC = TextEditingController();
  final TextEditingController altD = TextEditingController();
  String nivel = "Fácil";
  String correta = "a";

  void salvar() {
    if (descricaoController.text.isEmpty ||
        altA.text.isEmpty ||
        altB.text.isEmpty ||
        altC.text.isEmpty ||
        altD.text.isEmpty) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text("Preencha todos os campos")));
      return;
    }

    print({
      "descricao": descricaoController.text,
      "nivel": nivel,
      "correta": correta,
      "alternativas": {
        "a": altA.text,
        "b": altB.text,
        "c": altC.text,
        "d": altD.text,
      },
    });

    ScaffoldMessenger.of(
      context,
    ).showSnackBar(SnackBar(content: Text("Questão salva com sucesso")));

    Navigator.pop(context);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Color(0xFF0F172A),

      appBar: AppBar(
        title: Text("Nova questão"),
        backgroundColor: Color(0xFF1E293B),
      ),

      body: Row(
        children: [
          Container(
            width: 220,
            color: Color(0xFF020617),
            child: Column(
              children: [
                menuItem(Icons.dashboard, "Dashboard"),
                menuItem(Icons.people, "Usuários"),
                menuItem(Icons.list, "Questões", ativo: true),
                menuItem(Icons.memory, "Sensores"),
              ],
            ),
          ),

          Expanded(
            child: SingleChildScrollView(
              padding: EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "Cadastrar nova questão",
                    style: TextStyle(fontSize: 24, color: Colors.white),
                  ),

                  SizedBox(height: 10),

                  TextButton.icon(
                    onPressed: () => Navigator.pop(context),
                    icon: Icon(Icons.arrow_back),
                    label: Text("Voltar à lista"),
                  ),

                  SizedBox(height: 20),

                  Container(
                    padding: EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: Color(0xFF1E293B),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          "Enunciado da pergunta",
                          style: TextStyle(color: Colors.white),
                        ),

                        SizedBox(height: 8),

                        TextField(
                          controller: descricaoController,
                          maxLines: 3,
                          decoration: InputDecoration(
                            hintText: "Digite a pergunta",
                            filled: true,
                            fillColor: Color(0xFF020617),
                          ),
                        ),

                        SizedBox(height: 20),

                        Text(
                          "Nível de dificuldade",
                          style: TextStyle(color: Colors.white),
                        ),

                        DropdownButton<String>(
                          value: nivel,
                          dropdownColor: Color(0xFF020617),
                          items: ["Fácil", "Médio", "Difícil"]
                              .map(
                                (n) => DropdownMenuItem(
                                  value: n,
                                  child: Text(
                                    n,
                                    style: TextStyle(color: Colors.white),
                                  ),
                                ),
                              )
                              .toList(),
                          onChanged: (value) {
                            setState(() => nivel = value!);
                          },
                        ),

                        SizedBox(height: 20),

                        Text(
                          "Alternativas (marque a correta)",
                          style: TextStyle(color: Colors.white),
                        ),

                        SizedBox(height: 10),

                        alternativa("A", "a", altA),
                        alternativa("B", "b", altB),
                        alternativa("C", "c", altC),
                        alternativa("D", "d", altD),

                        SizedBox(height: 20),

                        SizedBox(
                          width: double.infinity,
                          child: ElevatedButton.icon(
                            onPressed: salvar,
                            icon: Icon(Icons.save),
                            label: Text("Salvar questão"),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: Colors.blue,
                              padding: EdgeInsets.symmetric(vertical: 14),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget alternativa(String letra, String valor, TextEditingController ctrl) {
    return Row(
      children: [
        Radio<String>(
          value: valor,
          groupValue: correta,
          onChanged: (v) {
            setState(() => correta = v!);
          },
        ),
        Text("$letra) ", style: TextStyle(color: Colors.white)),
        Expanded(
          child: TextField(
            controller: ctrl,
            decoration: InputDecoration(
              hintText: "Alternativa $letra",
              filled: true,
              fillColor: Color(0xFF020617),
            ),
          ),
        ),
      ],
    );
  }

  Widget menuItem(IconData icon, String text, {bool ativo = false}) {
    return ListTile(
      leading: Icon(icon, color: ativo ? Colors.blue : Colors.white),
      title: Text(
        text,
        style: TextStyle(color: ativo ? Colors.blue : Colors.white),
      ),
      onTap: () {},
    );
  }
}
