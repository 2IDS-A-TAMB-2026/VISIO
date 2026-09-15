import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import 'login.dart';
import 'perfil.dart';
import 'sensores.dart';
import 'questoes.dart';
import 'identificador.dart';
import 'sobre.dart';
import 'appcolor.dart';
import 'controllers/theme_controller.dart';
import 'controllers/font_scale_controller.dart';
import 'services/auth_service.dart';
import 'services/tts_service.dart';
import 'theme/app_theme.dart';
import 'widgets/accessibility_panel.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.light,
    ),
  );

  await AuthService.instance.carregarSessaoSalva();
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => ThemeController()),
        ChangeNotifierProvider(create: (_) => FontScaleController()),

        ChangeNotifierProvider.value(value: TtsService.instance),
        ChangeNotifierProvider.value(value: AuthService.instance),
      ],
      child: const MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    final themeController = context.watch<ThemeController>();
    final fontScale = context.watch<FontScaleController>();

    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'VISIO',
      theme: AppTheme.light,
      darkTheme: AppTheme.dark,
      themeMode: themeController.themeMode,
      builder: (context, child) {
        return MediaQuery(
          data: MediaQuery.of(
            context,
          ).copyWith(textScaler: TextScaler.linear(fontScale.scale)),
          child: child!,
        );
      },
      home: const MainShell(),
    );
  }
}

class MainShell extends StatefulWidget {
  const MainShell({super.key});

  @override
  State<MainShell> createState() => _MainShellState();
}

class _MainShellState extends State<MainShell> {
  int _currentIndex = 0;

  List<Widget> _buildScreens() => [
    const HomePage(),
    const SensoresPage(),
    IdentificadorPage(isActive: _currentIndex == 2),
    const QuizPage(),
    const AboutPage(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        children: [
          IndexedStack(index: _currentIndex, children: _buildScreens()),
          const AccessibilityPanel(),
        ],
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        onTap: (i) => setState(() => _currentIndex = i),
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.home_outlined),
            activeIcon: Icon(Icons.home_rounded),
            label: 'Início',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.memory_outlined),
            activeIcon: Icon(Icons.memory),
            label: 'Sensores',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.camera_alt_outlined),
            activeIcon: Icon(Icons.camera_alt),
            label: 'Identificar',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.quiz_outlined),
            activeIcon: Icon(Icons.quiz),
            label: 'Quiz',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.info_outline),
            activeIcon: Icon(Icons.info_rounded),
            label: 'Sobre',
          ),
        ],
      ),
    );
  }
}

class HomePage extends StatelessWidget {
  const HomePage({super.key});

  @override
  Widget build(BuildContext context) {
   
    final logado = context.watch<AuthService>().estaLogadoComoUsuario;

    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/logos/Logo/LogoDark2.png', height: 40),
            SizedBox(width: 8),
          ],
        ),
        actions: [
          TextButton.icon(
            onPressed: () => Navigator.push(
              context,
              MaterialPageRoute(
                builder: (_) => logado ? const PerfilPage() : const LoginPage(),
              ),
            ),
            icon: Icon(
              logado ? Icons.person : Icons.login,
              color: AppColors.primary,
              size: 18,
            ),
            label: Text(
              logado ? 'Meu Perfil' : 'Entrar',
              style: TextStyle(color: AppColors.primary),
            ),
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            _buildHero(context),
            _buildStats(context),
            _buildServices(context),
            _buildPortfolio(),
            _buildFooter(),
          ],
        ),
      ),
    );
  }

  Widget _buildHero(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.fromLTRB(24, 40, 24, 40),
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          colors: [Color.fromARGB(234, 0, 0, 0), Color.fromARGB(234, 0, 0, 33)],
          begin: Alignment.topCenter,
          end: Alignment.bottomCenter,
        ),
      ),
      child: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(2),
            child: Image.asset(
              'assets/images/logos/Logo/LogoDarkD.png',
              height: 350,
            ),
          ),
          const SizedBox(height: 14),
          const Text(
            'Sistema acadêmico com visão computacional e IA para identificar automaticamente sensores IoT físicos.',
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 14,
              color: AppColors.textSoft,
              height: 1.6,
            ),
          ),
          const SizedBox(height: 28),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: () => Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => const IdentificadorPage()),
              ),
              icon: const Icon(Icons.camera_alt, size: 18),
              label: const Text('IDENTIFICAR SENSOR'),
              style: ElevatedButton.styleFrom(
                padding: const EdgeInsets.symmetric(vertical: 25),
              ),
            ),
          ),
          const SizedBox(height: 10),
        ],
      ),
    );
  }

  Widget _buildStats(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 20, 16, 0),
      child: Row(
        children: [
          _statCard(context, '128+', 'Sensores\ncadastrados'),
          const SizedBox(width: 10),
          _statCard(context, '92%', 'Precisão\nde IA'),
          const SizedBox(width: 10),
          _statCard(context, '9', 'Tipos de\nsensores'),
        ],
      ),
    );
  }

  Widget _statCard(BuildContext context, String value, String label) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 8),
        decoration: BoxDecoration(
          color: context.cardBg,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: context.borderColor),
          boxShadow: [
            BoxShadow(
              color: AppColors.primary.withValues(alpha: 0.06),
              blurRadius: 10,
              spreadRadius: 1,
            ),
          ],
        ),
        child: Column(
          children: [
            Text(
              value,
              style: const TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: AppColors.primary,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              label,
              textAlign: TextAlign.center,
              style: TextStyle(
                fontSize: 11,
                color: context.textMuted,
                height: 1.3,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildServices(BuildContext context) {
    final services = [
      (
        Icons.remove_red_eye_outlined,
        'Visão Computacional',
        'Reconhecimento automático via câmera e IA',
      ),
      (
        Icons.memory_outlined,
        'Gestão de Sensores IoT',
        'Cadastro e consulta centralizados',
      ),
      (
        Icons.school_outlined,
        'Apoio Educacional',
        'Ferramenta didática para IoT e automação',
      ),
      (
        Icons.science_outlined,
        'Aplicação em Laboratórios',
        'Controle para experimentos práticos',
      ),
      (
        Icons.lock_outlined,
        'Autenticação e Segurança',
        'Proteção e identificação digital',
      ),
      (Icons.smartphone_outlined, 'Web & Mobile', 'Acesso multiplataforma'),
    ];

    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 28, 16, 0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Funcionalidades',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 14),
          ...services.asMap().entries.map((e) {
            final i = e.key;
            final s = e.value;
            return _serviceItem(
              context,
              (i + 1).toString().padLeft(2, '0'),
              s.$1,
              s.$2,
              s.$3,
            );
          }),
        ],
      ),
    );
  }

  Widget _serviceItem(
    BuildContext context,
    String number,
    IconData icon,
    String title,
    String desc,
  ) {
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: context.cardBg,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: context.borderColor),
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
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Text(
                      number,
                      style: const TextStyle(
                        fontSize: 11,
                        color: AppColors.primary,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(width: 6),
                    Expanded(
                      child: Text(
                        title,
                        style: const TextStyle(
                          fontWeight: FontWeight.bold,
                          fontSize: 13,
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 3),
                Text(
                  desc,
                  style: TextStyle(fontSize: 12, color: context.textMuted),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPortfolio() {
    final items = [
      (
        'assets/images/Aplicacoes/identificacao.automatica.png',
        '1- Identificação Automática',
      ),
      (
        'assets/images/Aplicacoes/aplicacao.educacional.png',
        '2- Aplicação Educacional',
      ),
      (
        'assets/images/Aplicacoes/gestaoeorganizacao.png',
        '3- Gestão e Organização',
      ),
      (
        'assets/images/Aplicacoes/interfaceegerenciamento.png',
        '4- Interface de Gerenciamento',
      ),
    ];

    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 28, 16, 0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Aplicações do Sistema',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 14),
          GridView.count(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            crossAxisCount: 2,
            crossAxisSpacing: 10,
            mainAxisSpacing: 10,
            childAspectRatio: 1,
            children: items
                .map((item) => _portfolioCard(item.$1, item.$2))
                .toList(),
          ),
        ],
      ),
    );
  }

  Widget _portfolioCard(String imagePath, String title) {
    return Container(
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(16),
        image: DecorationImage(image: AssetImage(imagePath), fit: BoxFit.cover),
      ),
      child: Stack(
        children: [
          Container(
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(16),
              color: Colors.black.withValues(alpha: 0.4),
            ),
          ),
          Align(
            alignment: Alignment.bottomLeft,
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Text(
                title,
                style: const TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
            ),
          ),
          Align(
            alignment: Alignment.topRight,
            child: Padding(padding: const EdgeInsets.all(10)),
          ),
        ],
      ),
    );
  }

  Widget _buildFooter() {
    return Container(
      width: double.infinity,
      margin: const EdgeInsets.only(top: 28),
      padding: const EdgeInsets.symmetric(vertical: 1, horizontal: 24),
      color: AppColors.surfaceDark,
      child: Column(
        children: [
          Image.asset('assets/images/logos/Logo/LogoDark.png', height: 60),
          SizedBox(height: 10),
          SizedBox(height: 6),
          Text(
            'Plataforma Inteligente para Identificação\ne Gestão de Sensores IoT',
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 12,
              color: AppColors.textMuted,
              height: 1,
            ),
          ),
          SizedBox(height: 12),
          Text(
            '© 2026 VISIO – Todos os direitos reservados',
            style: TextStyle(fontSize: 11, color: AppColors.primary),
          ),
        ],
      ),
    );
  }
}
