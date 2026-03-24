import 'package:flutter/material.dart';

class SobreScreen extends StatelessWidget {
  const SobreScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF0D0D0D),
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        title: const Text("Sobre o VISIO"),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildCard(
              titulo: "Missão",
              conteudo:
                  "Criar uma plataforma de identificação de sensores IoT que ensine de forma simples, interativa e inspiradora.",
            ),

            _buildCard(
              titulo: "Visão",
              conteudo:
                  "Ser referência global em clareza e precisão, tornando tecnologia algo transparente, acessível e confiável.",
            ),

            _buildValoresCard(),

            const SizedBox(height: 20),

            const Text(
              "Sobre a Plataforma",
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: Color(0xFF1E6BE7),
              ),
            ),

            const SizedBox(height: 10),

            _buildDescricao(),

            const SizedBox(height: 30),

            const Text(
              "Equipe",
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: Color(0xFF1E6BE7),
              ),
            ),

            const SizedBox(height: 15),

            _buildTeamSection(),
          ],
        ),
      ),
    );
  }

  Widget _buildCard({required String titulo, required String conteudo}) {
    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFF121212),
        borderRadius: BorderRadius.circular(15),
        border: Border.all(color: const Color(0xFF2A2A2A)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            titulo,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: Color(0xFF1E6BE7),
            ),
          ),
          const SizedBox(height: 10),
          Text(
            conteudo,
            style: const TextStyle(color: Colors.grey, height: 1.6),
          ),
        ],
      ),
    );
  }

  Widget _buildValoresCard() {
    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFF121212),
        borderRadius: BorderRadius.circular(15),
        border: Border.all(color: const Color(0xFF2A2A2A)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: const [
          Text(
            "Valores",
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: Color(0xFF1E6BE7),
            ),
          ),
          SizedBox(height: 10),
          Text("• Inovação", style: TextStyle(color: Colors.grey)),
          Text("• Segurança", style: TextStyle(color: Colors.grey)),
          Text("• Educação", style: TextStyle(color: Colors.grey)),
          Text("• Eficiência", style: TextStyle(color: Colors.grey)),
        ],
      ),
    );
  }

  Widget _buildDescricao() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFF121212),
        borderRadius: BorderRadius.circular(15),
      ),
      child: const Text(
        "O VISIO combina visão computacional e IoT para identificar sensores de forma automática, segura e didática. "
        "Nosso objetivo é facilitar a gestão de sensores em ambientes educacionais e industriais.",
        style: TextStyle(color: Colors.grey, height: 1.6),
      ),
    );
  }

  Widget _buildTeamSection() {
    final team = [
      {"nome": "Isabela Tessarin", "funcao": "Full-Stack & Product Owner"},
      {"nome": "Matheus Neri", "funcao": "Full-Stack Developer"},
      {"nome": "Fernanda Amaral", "funcao": "Back-End Developer"},
      {"nome": "Emily Maiara", "funcao": "Back-End & Scrum Master"},
      {"nome": "Guilherme Staconi", "funcao": "Analista & Designer"},
      {"nome": "Lorrana Generoso", "funcao": "Analista & Designer"},
      {"nome": "Sophia Peron", "funcao": "Analista & Designer"},
    ];

    return SizedBox(
      height: 160,
      child: ListView.builder(
        scrollDirection: Axis.horizontal,
        itemCount: team.length,
        itemBuilder: (context, index) {
          final membro = team[index];
          return Container(
            width: 200,
            margin: const EdgeInsets.only(right: 15),
            padding: const EdgeInsets.all(15),
            decoration: BoxDecoration(
              color: const Color(0xFF121212),
              borderRadius: BorderRadius.circular(15),
              border: Border.all(color: const Color(0xFF2A2A2A)),
            ),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const CircleAvatar(
                  radius: 30,
                  backgroundColor: Color(0xFF1E6BE7),
                  child: Icon(Icons.person, color: Colors.white),
                ),
                const SizedBox(height: 10),
                Text(
                  membro["nome"]!,
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 5),
                Text(
                  membro["funcao"]!,
                  textAlign: TextAlign.center,
                  style: const TextStyle(color: Colors.grey, fontSize: 12),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
