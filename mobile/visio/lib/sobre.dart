import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'contato.dart';
import 'package:url_launcher/url_launcher.dart';

class AboutPage extends StatelessWidget {
  Future<void> _openLink(String url) async {
    final uri = Uri.parse(url);
    if (!await launchUrl(uri, mode: LaunchMode.externalApplication)) {
      throw Exception('Não foi possível abrir $url');
    }
  }

  const AboutPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/LogoDark.png', height: 60),
            SizedBox(width: 8),
            Text('Sobre Nós'),
          ],
        ),
        
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(16),
                  gradient: const LinearGradient(
                    colors: [AppColors.bgCardDark, AppColors.surfaceDark],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  border: Border.all(color: AppColors.border),
                ),
                child: Column(
                  children: [
                    Image.asset(
                      'assets/images/LogoDark.png',
                      width: 350,
                      height: 350,
                    ),
                    const SizedBox(height: 12),

                    const SizedBox(height: 8),
                    const Text(
                      'Plataforma Inteligente para Identificação\ne Gestão de Sensores IoT',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        color: AppColors.textMuted,
                        fontSize: 13,
                        height: 1.5,
                      ),
                    ),
                    const SizedBox(height: 16),
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 14,
                        vertical: 6,
                      ),
                      decoration: BoxDecoration(
                        color: AppColors.primary.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(
                          color: AppColors.primary.withOpacity(0.3),
                        ),
                      ),
                      child: const Text(
                        'TCC — 2026',
                        style: TextStyle(
                          color: AppColors.primary,
                          fontSize: 12,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 24),

              _sectionTitle('Identidade'),
              const SizedBox(height: 12),
              Row(
                children: [
                  Expanded(
                    child: _infoCard(
                      Icons.flag_outlined,
                      'Missão',
                      'Desenvolver uma solução interativa de identificação de sensores IoT.',
                      const Color(0xFF1E6BE7),
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: _infoCard(
                      Icons.visibility_outlined,
                      'Visão',
                      'Ser referência em clareza e precisão na identificação de sensores.',
                      const Color(0xFF22C55E),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 10),
              _infoCard(
                Icons.diamond_outlined,
                'Valores',
                'Inovação, Segurança, Educação e Eficiência.',
                const Color(0xFF8B5CF6),
                full: true,
              ),

              const SizedBox(height: 24),

              _sectionTitle('Tecnologias utilizadas'),
              const SizedBox(height: 12),
              Wrap(
                spacing: 8,
                runSpacing: 8,
                children: [
                  'HTML',
                  'CSS',
                  'JavaScript',
                  'PHP',
                  'Flutter',
                  'Dart',
                  'API REST',
                  'MySQL',
                  'IoT',
                  'Visão Computacional',
                  'Python',
                ].map((tech) => _techChip(tech)).toList(),
              ),

              const SizedBox(height: 28),

              _sectionTitle('Sobre o Projeto'),
              const SizedBox(height: 12),
              Container(
                padding: const EdgeInsets.all(18),
                decoration: BoxDecoration(
                  color: AppColors.bgCardAlt,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: AppColors.border),
                ),
                child: Text(
                  'O VISIO é um sistema acadêmico desenvolvido como Trabalho de Conclusão de Curso que combina visão computacional e Inteligência Artificial para identificar automaticamente sensores IoT físicos, promovendo organização, rastreabilidade e apoio ao ensino de Internet das Coisas.',
                  style: TextStyle(
                    fontSize: 14,
                    color: Theme.of(context).textTheme.bodyMedium?.color,
                    height: 1.7,
                  ),
                ),
              ),

              const SizedBox(height: 28),

              _sectionTitle('Equipe'),
              const SizedBox(height: 16),
              _teamGrid(),

              const SizedBox(height: 28),

              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: AppColors.primary.withOpacity(0.06),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: AppColors.primary.withOpacity(0.2)),
                ),
                child: Column(
                  children: [
                    const Icon(
                      Icons.mail_outline,
                      color: AppColors.primary,
                      size: 28,
                    ),
                    const SizedBox(height: 10),
                    const Text(
                      'Tem alguma dúvida?',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 15,
                      ),
                    ),
                    const SizedBox(height: 6),
                    const Text(
                      'Entre em contato com nossa equipe.',
                      style: TextStyle(
                        color: AppColors.textMuted,
                        fontSize: 13,
                      ),
                    ),
                    const SizedBox(height: 14),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: () => Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (_) => const ContactPage(),
                          ),
                        ),
                        child: const Text('Falar com a equipe'),
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 24),
            ],
          ),
        ),
      ),
    );
  }

  Widget _sectionTitle(String title) {
    return Row(
      children: [
        Container(
          width: 3,
          height: 18,
          decoration: BoxDecoration(
            color: AppColors.primary,
            borderRadius: BorderRadius.circular(2),
          ),
        ),
        const SizedBox(width: 8),
        Text(
          title,
          style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
        ),
      ],
    );
  }

  Widget _infoCard(
    IconData icon,
    String title,
    String text,
    Color color, {
    bool full = false,
  }) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.bgCardAlt,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: color.withOpacity(0.2)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: color, size: 22),
          const SizedBox(height: 10),
          Text(
            title,
            style: TextStyle(
              fontWeight: FontWeight.bold,
              color: color,
              fontSize: 13,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            text,
            style: const TextStyle(
              color: AppColors.textMuted,
              fontSize: 12,
              height: 1.5,
            ),
          ),
        ],
      ),
    );
  }

  Widget _techChip(String label) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: AppColors.bgCardAlt,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: AppColors.border),
      ),
      child: Text(
        label,
        style: const TextStyle(fontSize: 12, color: AppColors.textSoft),
      ),
    );
  }

  Widget _teamGrid() {
    final membros = [
      (
        'Isabela Tessarin',
        'Full-Stack',
        'assets/images/isabela.png',
        'https://github.com/isinhaT',
        'https://linkedin.com/in/isabela',
      ),
      (
        'Matheus Neri',
        'Full-Stack',
        'assets/images/matheus.png',
        'https://github.com/NeriMH',
        'https://linkedin.com/in/matheusneri',
      ),
      (
        'Fernanda Amaral',
        'Back-End',
        'assets/images/fernanda.png',
        'https://github.com/fernandaamaral',
        'https://linkedin.com/in/fernandaamaral',
      ),
      (
        'Emily Maiara',
        'Back-End',
        'assets/images/emily.png',
        'https://github.com/maiaraemily',
        'https://linkedin.com/in/emilymaiara',
      ),
      (
        'Sophia Peron',
        'Designer',
        'assets/images/sophia.png',
        'https://github.com/SosoPeron',
        'https://linkedin.com/in/sophia-peron',
      ),
      (
        'Lorrana Generoso',
        'Designer',
        'assets/images/lorrana.png',
        'https://github.com/LorranaG',
        'https://linkedin.com/in/lorrana',
      ),
      (
        'Guilherme Staconi',
        'Designer',
        'assets/images/guilherme.png',
        'https://github.com/guizim-GitFF',
        'https://linkedin.com/in/guilhermestaconi',
      ),
    ];

    return GridView.count(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      crossAxisCount: 2,
      crossAxisSpacing: 10,
      mainAxisSpacing: 10,
      childAspectRatio: 0.9,
      children: membros
          .map((m) => _memberCard(m.$1, m.$2, m.$3, m.$4, m.$5))
          .toList(),
    );
  }

  Widget _memberCard(
    String nome,
    String cargo,
    String imagePath,
    String github,
    String linkedin,
  ) {
    final Color cor = cargo.contains('Designer')
        ? const Color(0xFF8B5CF6)
        : cargo.contains('Back')
        ? const Color(0xFF22C55E)
        : AppColors.primary;

    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: AppColors.bgCardAlt,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: cor.withOpacity(0.2)),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          CircleAvatar(
            radius: 80,
            backgroundColor: cor.withOpacity(0.2),
            child: ClipOval(
              child: Image.asset(
                imagePath,
                width: 150,
                height: 150,
                fit: BoxFit.cover,
              ),
            ),
          ),
          const SizedBox(height: 10),
          Text(
            nome.split(' ').first,
            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
            textAlign: TextAlign.center,
          ),
          Text(
            nome.split(' ').length > 1 ? nome.split(' ').last : '',
            style: const TextStyle(fontSize: 11, color: AppColors.textMuted),
          ),
          const SizedBox(height: 4),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
            decoration: BoxDecoration(
              color: cor.withOpacity(0.1),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  cargo,
                  style: TextStyle(
                    fontSize: 10,
                    color: cor,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 6),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    IconButton(
                      icon: const Icon(Icons.code, size: 18),
                      color: cor,
                      onPressed: () => _openLink(github),
                      tooltip: 'GitHub',
                    ),
                    IconButton(
                      icon: const Icon(Icons.business, size: 18),
                      color: cor,
                      onPressed: () => _openLink(linkedin),
                      tooltip: 'LinkedIn',
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
