import 'package:flutter/material.dart';
import 'contato.dart';
import 'package:url_launcher/url_launcher.dart';

class AboutPage extends StatelessWidget {
  const AboutPage({super.key});

  Future<void> _openLink(BuildContext context, String url) async {
    final uri = Uri.parse(url);
    bool ok = false;
    try {
      ok = await launchUrl(uri, mode: LaunchMode.externalApplication);
    } catch (_) {
      ok = false;
    }

    if (!ok && context.mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Não foi possível abrir o link: $url')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

    // Cores dinâmicas para suportar Light e Dark Mode
    final bgColor = isDark ? const Color(0xFF030712) : const Color(0xFFF1F5F9);
    final cardBgColor = isDark ? const Color(0xFF0D1117) : Colors.white;
    final borderColor = isDark
        ? const Color(0xFF1F2937)
        : const Color(0xFFE2E8F0);
    final titleTextColor = isDark
        ? const Color(0xFF38BDF8)
        : const Color(0xFF0284C7);
    final bodyTextColor = isDark
        ? const Color(0xFF9CA3AF)
        : const Color(0xFF475569);
    final headingColor = isDark ? Colors.white : const Color(0xFF0F172A);

    return Scaffold(
      backgroundColor: bgColor,
      body: SafeArea(
        child: Column(
          children: [
            _buildTopNavBar(
              context,
              isDark,
              headingColor,
              bodyTextColor,
              borderColor,
            ),
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(
                  horizontal: 20,
                  vertical: 24,
                ),
                child: Center(
                  child: Container(
                    constraints: const BoxConstraints(maxWidth: 1000),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        // Card Principal Hero
                        _buildHeroCard(
                          context,
                          cardBgColor,
                          borderColor,
                          headingColor,
                          bodyTextColor,
                          isDark,
                        ),
                        const SizedBox(height: 24),

                        // Missão, Visão e Valores (Layout em 3 Colunas/Grid)
                        _buildIdentidadeGrid(
                          context,
                          cardBgColor,
                          borderColor,
                          titleTextColor,
                          bodyTextColor,
                        ),
                        const SizedBox(height: 24),

                        // Tecnologias Utilizadas
                        _buildSectionCard(
                          cardBgColor,
                          borderColor,
                          'Tecnologias utilizadas',
                          titleTextColor,
                          child: Center(
                            child: Wrap(
                              spacing: 10,
                              runSpacing: 10,
                              alignment: WrapAlignment.center,
                              children: [
                                'HTML',
                                'CSS',
                                'JavaScript',
                                'PHP',
                                'API REST (JSON)',
                                'IoT / sensores',
                                'Flutter',
                                'Dart',
                                'MySQL',
                                'Python',
                              ].map((tech) => _techChip(tech, isDark)).toList(),
                            ),
                          ),
                        ),
                        const SizedBox(height: 24),

                        // Sobre o VISIO
                        _buildSectionCard(
                          cardBgColor,
                          borderColor,
                          'Sobre o VISIO',
                          titleTextColor,
                          child: Text(
                            'O VISIO é uma plataforma inovadora que combina visão computacional e IoT para identificar sensores de forma automática, segura e didática. Nosso objetivo é facilitar a gestão de sensores em ambientes educacionais e industriais, garantindo eficiência, segurança e aprendizado prático.',
                            textAlign: TextAlign.center,
                            style: TextStyle(
                              fontSize: 14,
                              color: bodyTextColor,
                              height: 1.6,
                            ),
                          ),
                        ),
                        const SizedBox(height: 24),

                        // Integrantes
                        _buildSectionCard(
                          cardBgColor,
                          borderColor,
                          'Integrantes',
                          titleTextColor,
                          child: _teamGrid(
                            context,
                            bodyTextColor,
                            headingColor,
                          ),
                        ),
                        const SizedBox(height: 32),

                        // Banner de Contato
                        _buildContactCard(
                          context,
                          cardBgColor,
                          borderColor,
                          headingColor,
                          bodyTextColor,
                        ),
                        const SizedBox(height: 24),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
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
          Image.asset(
            isDark
                ? 'assets/images/logos/Logo/LogoDark2.png'
                : 'assets/images/logos/Logo/LogoLight2.png',
            height: 28,
          ),
          const SizedBox(width: 8),

          const SizedBox(width: 12),
        ],
      ),
    );
  }

  Widget _buildHeroCard(
  BuildContext context,
  Color cardBg,
  Color borderColor,
  Color headingColor,
  Color bodyTextColor,
  bool isDark,
) {
  return Container(
    width: double.infinity,
    padding: const EdgeInsets.all(28),
    decoration: BoxDecoration(
      color: cardBg,
      borderRadius: BorderRadius.circular(16),
      border: Border.all(color: borderColor),
    ),
    child: Column(
      children: [
        Image.asset(
          isDark
              ? 'assets/images/logos/Simbolo/SimboloDark2.png'
              : 'assets/images/logos/Simbolo/SimboloLight2.png',
          height: 250,
          width: 250,
        ),

          const SizedBox(height: 16),
          Text(
            'Plataforma Inteligente para Identificação e Gestão de Sensores IoT',
            textAlign: TextAlign.center,
            style: TextStyle(
              color: bodyTextColor,
              fontSize: 14,
              height: 1.5,
              fontWeight: FontWeight.w500,
            ),
          ),
          const SizedBox(height: 16),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
            decoration: BoxDecoration(
              color: const Color(0xFF007BFF).withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(20),
              border: Border.all(
                color: const Color(0xFF007BFF).withValues(alpha: 0.3),
              ),
            ),
            child: const Text(
              'TCC — 2026',
              style: TextStyle(
                color: Color(0xFF007BFF),
                fontSize: 12,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildIdentidadeGrid(
    BuildContext context,
    Color cardBg,
    Color borderColor,
    Color titleColor,
    Color bodyTextColor,
  ) {
    return LayoutBuilder(
      builder: (context, constraints) {
        bool isWide = constraints.maxWidth > 700;
        if (isWide) {
          return Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: _infoCard(
                  cardBg,
                  borderColor,
                  'Missão',
                  'Desenvolvemos uma solução interativa de identificação de sensores IoT que simplifica conceitos complexos. Nossa missão é oferecer uma jornada educacional envolvente, despertando o interesse tecnológico de forma intuitiva.',
                  titleColor,
                  bodyTextColor,
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: _infoCard(
                  cardBg,
                  borderColor,
                  'Visão',
                  'Ser a referência global em clareza e precisão, transformando a complexidade da identificação de valores em uma experiência simples, transparente e confiável para todos.',
                  titleColor,
                  bodyTextColor,
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: _valoresCard(
                  cardBg,
                  borderColor,
                  titleColor,
                  bodyTextColor,
                ),
              ),
            ],
          );
        }

        return Column(
          children: [
            _infoCard(
              cardBg,
              borderColor,
              'Missão',
              'Desenvolvemos uma solução interativa de identificação de sensores IoT que simplifica conceitos complexos. Nossa missão é oferecer uma jornada educacional envolvente, despertando o interesse tecnológico de forma intuitiva.',
              titleColor,
              bodyTextColor,
            ),
            const SizedBox(height: 16),
            _infoCard(
              cardBg,
              borderColor,
              'Visão',
              'Ser a referência global em clareza e precisão, transformando a complexidade da identificação de valores em uma experiência simples, transparente e confiável para todos.',
              titleColor,
              bodyTextColor,
            ),
            const SizedBox(height: 16),
            _valoresCard(cardBg, borderColor, titleColor, bodyTextColor),
          ],
        );
      },
    );
  }

  Widget _infoCard(
    Color cardBg,
    Color borderColor,
    String title,
    String text,
    Color titleColor,
    Color textColor,
  ) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: cardBg,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: borderColor),
      ),
      child: Column(
        children: [
          Text(
            title,
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: titleColor,
            ),
          ),
          const SizedBox(height: 12),
          Text(
            text,
            textAlign: TextAlign.center,
            style: TextStyle(color: textColor, fontSize: 13, height: 1.5),
          ),
        ],
      ),
    );
  }

  Widget _valoresCard(
    Color cardBg,
    Color borderColor,
    Color titleColor,
    Color textColor,
  ) {
    final valores = [
      {
        't': 'Inovação',
        'd': 'Aplicar tecnologias avançadas para resolver problemas reais.',
      },
      {
        't': 'Segurança',
        'd': 'Garantir integridade e confiabilidade dos dados.',
      },
      {
        't': 'Educação',
        'd': 'Apoiar o ensino de IoT e automação de forma didática.',
      },
      {'t': 'Eficiência', 'd': 'Otimizar processos e reduzir erros.'},
    ];

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: cardBg,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: borderColor),
      ),
      child: Column(
        children: [
          Text(
            'Valores',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: titleColor,
            ),
          ),
          const SizedBox(height: 12),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: valores.map((item) {
              return Padding(
                padding: const EdgeInsets.only(bottom: 8),
                child: RichText(
                  text: TextSpan(
                    style: TextStyle(
                      color: textColor,
                      fontSize: 12,
                      height: 1.4,
                    ),
                    children: [
                      TextSpan(
                        text: '• ${item['t']}: ',
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          color: textColor,
                        ),
                      ),
                      TextSpan(text: item['d']),
                    ],
                  ),
                ),
              );
            }).toList(),
          ),
        ],
      ),
    );
  }

  Widget _buildSectionCard(
    Color cardBg,
    Color borderColor,
    String title,
    Color titleColor, {
    required Widget child,
  }) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: cardBg,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: borderColor),
      ),
      child: Column(
        children: [
          Text(
            title,
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: titleColor,
            ),
          ),
          const SizedBox(height: 16),
          child,
        ],
      ),
    );
  }

  Widget _techChip(String label, bool isDark) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF1E293B) : const Color(0xFFE2E8F0),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Text(
        label,
        style: TextStyle(
          fontSize: 12,
          color: isDark ? Colors.white : const Color(0xFF0F172A),
          fontWeight: FontWeight.w500,
        ),
      ),
    );
  }

  Widget _teamGrid(BuildContext context, Color textColor, Color headingColor) {
    final membros = [
      (
        'Lorrana Generoso',
        'Analista de sistema e designer\n& Product Owner',
        'assets/images/Grupo/lorrana.png',
        'https://github.com/LorranaG',
      ),
      (
        'Matheus Neri',
        'Desenvolvedor Full-Stack',
        'assets/images/Grupo/matheus.png',
        'https://github.com/NeriMH',
      ),
      (
        'Fernanda Amaral',
        'Programadora Back-End',
        'assets/images/Grupo/fernanda.png',
        'https://github.com/fernandaamaral',
      ),
      (
        'Emily Maiara',
        'Programadora Back-End',
        'assets/images/Grupo/emily.png',
        'https://github.com/maiaraemily',
      ),
      (
        'Guilherme Staconi',
        'Analista de sistema e designer',
        'assets/images/Grupo/guilherme.png',
        'https://github.com/guizim-GitFF',
      ),
      (
        'Isabela Tessarin',
        'Desenvolvedora Full-Stack',
        'assets/images/Grupo/isabela.png',
        'https://github.com/isinhaT',
      ),
      (
        'Sophia Peron',
        'Analista de sistema e designer\n& Scrum Master',
        'assets/images/Grupo/sophia.png',
        'https://github.com/SosoPeron',
      ),
    ];

    return Wrap(
      spacing: 24,
      runSpacing: 24,
      alignment: WrapAlignment.center,
      children: membros
          .map(
            (m) => _memberCard(
              context,
              m.$1,
              m.$2,
              m.$3,
              m.$4,
              textColor,
              headingColor,
            ),
          )
          .toList(),
    );
  }

  Widget _memberCard(
    BuildContext context,
    String nome,
    String cargo,
    String imagePath,
    String github,
    Color textColor,
    Color headingColor,
  ) {
    return SizedBox(
      width: 160,
      child: Column(
        children: [
          CircleAvatar(
            radius: 45,
            backgroundColor: Colors.transparent,
            child: ClipOval(
              child: Image.asset(
                imagePath,
                width: 90,
                height: 90,
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) => Container(
                  color: Colors.grey.shade800,
                  child: const Icon(
                    Icons.person,
                    color: Colors.white,
                    size: 40,
                  ),
                ),
              ),
            ),
          ),
          const SizedBox(height: 10),
          Text(
            nome,
            style: TextStyle(
              fontWeight: FontWeight.bold,
              fontSize: 13,
              color: headingColor,
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 2),
          Text(
            cargo,
            style: TextStyle(fontSize: 10, color: textColor, height: 1.3),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 6),
          IconButton(
            constraints: const BoxConstraints(),
            padding: EdgeInsets.zero,
            icon: const Icon(Icons.code, size: 18),
            color: const Color(0xFF007BFF),
            onPressed: () => _openLink(context, github),
            tooltip: 'GitHub',
          ),
        ],
      ),
    );
  }

  Widget _buildContactCard(
    BuildContext context,
    Color cardBg,
    Color borderColor,
    Color headingColor,
    Color bodyTextColor,
  ) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: cardBg,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: borderColor),
      ),
      child: Column(
        children: [
          const Icon(Icons.mail_outline, color: Color(0xFF007BFF), size: 28),
          const SizedBox(height: 10),
          Text(
            'Tem alguma dúvida?',
            style: TextStyle(
              fontWeight: FontWeight.bold,
              fontSize: 15,
              color: headingColor,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            'Entre em contato com nossa equipe.',
            style: TextStyle(color: bodyTextColor, fontSize: 13),
          ),
          const SizedBox(height: 14),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF007BFF),
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
              onPressed: () => Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const ContactPage()),
              ),
              child: const Text('Falar com a equipe'),
            ),
          ),
        ],
      ),
    );
  }
}
