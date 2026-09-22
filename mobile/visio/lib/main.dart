import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import 'login.dart';
import 'perfil.dart';
import 'sensores.dart';
import 'questoes.dart';
import 'identificador.dart';
import 'sobre.dart';
import 'controllers/theme_controller.dart';
import 'controllers/font_scale_controller.dart';
import 'services/auth_service.dart';
import 'services/tts_service.dart';
import 'services/api_client.dart';
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
    HomePage(
      onIdentificar: () {
        setState(() {
          _currentIndex = 2;
        });
      },
    ),
    const SensoresPage(),
    IdentificadorPage(isActive: _currentIndex == 2),
    const QuizPage(),
    const AboutPage(),
  ];

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      body: Stack(
        children: [
          IndexedStack(index: _currentIndex, children: _buildScreens()),
          const AccessibilityPanel(),
        ],
      ),
      bottomNavigationBar: BottomNavigationBar(
        backgroundColor: isDark ? const Color(0xFF030712) : Colors.white,
        selectedItemColor: const Color(0xFF0284C7),
        unselectedItemColor: isDark ? const Color(0xFF64748B) : Colors.grey,
        currentIndex: _currentIndex,
        type: BottomNavigationBarType.fixed,
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

class HomePage extends StatefulWidget {
  final VoidCallback onIdentificar;

  const HomePage({super.key, required this.onIdentificar});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  // Enquanto carrega (ou se a chamada falhar), os cards mostram "—" em vez
  // de travar a tela inicial ou de exibir um número inventado.
  String _sensoresCadastrados = '—';
  String _tiposDeSensores = '—';
  String _usuariosCadastrados = '—';

  @override
  void initState() {
    super.initState();
    _carregarEstatisticas();
  }

  Future<void> _carregarEstatisticas() async {
    try {
      final resposta =
          await ApiClient.get('estatisticas') as Map<String, dynamic>;

      if (resposta['erro'] == true) return;

      final dados = resposta['dados'];
      if (dados is! Map) return;

      if (!mounted) return;
      setState(() {
        _sensoresCadastrados = '${dados['SENSORES_CADASTRADOS'] ?? '—'}';
        _tiposDeSensores = '${dados['TIPOS_DE_SENSORES'] ?? '—'}';
        _usuariosCadastrados = '${dados['USUARIOS_CADASTRADOS'] ?? '—'}';
      });
    } catch (_) {
      // Falha de rede/servidor: mantém "—" — a tela inicial continua
      // usável mesmo sem estatísticas, igual às outras telas do app
      // quando a API está fora do ar.
    }
  }

  @override
  Widget build(BuildContext context) {
    final logado = context.watch<AuthService>().estaLogadoComoUsuario;
    final isDark = Theme.of(context).brightness == Brightness.dark;

    final bgColor = isDark ? const Color(0xFF020617) : const Color(0xFFF8FAFC);
    final textColor = isDark ? Colors.white : const Color(0xFF0F172A);
    final subtextColor = isDark
        ? const Color(0xFF94A3B8)
        : const Color(0xFF475569);

    return Scaffold(
      backgroundColor: bgColor,
      appBar: AppBar(
        backgroundColor: bgColor,
        elevation: 0,
        title: Row(
          children: [
            Image.asset(
              isDark
                  ? 'assets/images/logos/Logo/LogoDark2.png'
                  : 'assets/images/logos/Logo/LogoLight2.png',
              height: 28,
            ),
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
              color: const Color(0xFF0284C7),
              size: 18,
            ),
            label: Text(
              logado ? 'Meu Perfil' : 'Entrar',
              style: const TextStyle(
                color: Color(0xFF0284C7),
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            _buildHero(context, isDark, textColor, subtextColor),
            _buildStats(isDark, subtextColor),
            _buildServices(isDark, textColor, subtextColor),
            _buildPortfolio(isDark, textColor),
            _buildFooter(isDark, subtextColor),
          ],
        ),
      ),
    );
  }

  Widget _buildHero(
    BuildContext context,
    bool isDark,
    Color textColor,
    Color subtextColor,
  ) {
    return Container(
      width: double.infinity,
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: isDark
              ? [
                  const Color(0xFF020617),
                  const Color(0xFF031338),
                  const Color(0xFF020617),
                ]
              : [
                  const Color(0xFFF8FAFC),
                  const Color(0xFFE2E8F0),
                  const Color(0xFFF8FAFC),
                ],
          begin: Alignment.topCenter,
          end: Alignment.bottomCenter,
        ),
      ),
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 30),
      child: Column(
        children: [
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: isDark ? const Color(0xFF0A1329) : Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(
                color: isDark
                    ? const Color(0xFF1E293B)
                    : const Color(0xFFE2E8F0),
              ),
              boxShadow: [
                BoxShadow(
                  color: const Color(0xFF0284C7).withValues(alpha: 0.15),
                  blurRadius: 20,
                  spreadRadius: 2,
                ),
              ],
            ),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: Image.asset(
                isDark
                    ? 'assets/images/logos/Logo/LogoDark2.png'
                    : 'assets/images/logos/Logo/LogoLight2.png',
                height: 180,
                fit: BoxFit.contain,
              ),
            ),
          ),
          const SizedBox(height: 24),
          Text(
            'Identificação Inteligente de Sensores IoT',
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 22,
              fontWeight: FontWeight.bold,
              color: textColor,
              height: 1.25,
            ),
          ),
          const SizedBox(height: 12),
          Text(
            'Sistema acadêmico desenvolvido como Trabalho de Conclusão de Curso que utiliza visão computacional e Inteligência Artificial para identificar automaticamente sensores IoT físicos, promovendo organização, rastreabilidade e apoio ao ensino de Internet das Coisas e automação.',
            textAlign: TextAlign.center,
            style: TextStyle(fontSize: 13, color: subtextColor, height: 1.5),
          ),
          const SizedBox(height: 24),
          RotatingLedButton(onPressed: widget.onIdentificar),
        ],
      ),
    );
  }

  Widget _buildStats(bool isDark, Color subtextColor) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      child: Row(
        children: [
          _statCard(
            _sensoresCadastrados,
            'Sensores\ncadastrados',
            isDark,
            subtextColor,
          ),
          const SizedBox(width: 8),
          _statCard(
            _usuariosCadastrados,
            'Usuários\ncadastrados',
            isDark,
            subtextColor,
          ),
          const SizedBox(width: 8),
          _statCard(
            _tiposDeSensores,
            'Tipos de\nsensores',
            isDark,
            subtextColor,
          ),
        ],
      ),
    );
  }

  Widget _statCard(
    String value,
    String label,
    bool isDark,
    Color subtextColor,
  ) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 8),
        decoration: BoxDecoration(
          color: isDark ? const Color(0xFF0B132B) : Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: isDark ? const Color(0xFF1E293B) : const Color(0xFFE2E8F0),
          ),
        ),
        child: Column(
          children: [
            Text(
              value,
              style: const TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: Color(0xFF0284C7),
              ),
            ),
            const SizedBox(height: 4),
            Text(
              label,
              textAlign: TextAlign.center,
              style: TextStyle(fontSize: 11, color: subtextColor, height: 1.2),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildServices(bool isDark, Color textColor, Color subtextColor) {
    final services = [
      (
        Icons.camera_alt_outlined,
        'Identificação por IA',
        'Reconhecimento automático de sensores por captura de imagem e processamento com modelos avançados de Inteligência Artificial.',
        'VISÃO COMPUTACIONAL',
      ),
      (
        Icons.dns_outlined,
        'Gestão de Sensores IoT',
        'Registro, consulta e acompanhamento centralizado do status dos sensores, promovendo organização e rastreabilidade.',
        'CONTROLE CENTRALIZADO',
      ),
      (
        Icons.developer_board_outlined,
        'Apoio Educacional',
        'Ferramenta didática para o aprendizado prático de Internet das Coisas, automação e componentes eletrônicos.',
        'ENSINO INTERATIVO',
      ),
      (
        Icons.layers_outlined,
        'Aplicação Prática',
        'Organização e controle de componentes em atividades de laboratório, testes e desenvolvimento.',
        'LABORATÓRIO & TESTES',
      ),
      (
        Icons.lock_outlined,
        'Autenticação e Segurança',
        'Identificação digital única por sensor e proteção de dados nas interações do sistema.',
        'SEGURANÇA DIGITAL',
      ),
      (
        Icons.smartphone_outlined,
        'Web e Mobile',
        'Acesso multiplataforma simplificado através de interfaces modernas e responsivas.',
        'MULTIPLATAFORMA',
      ),
    ];

    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 28, 16, 0),
      child: Column(
        children: [
          Text(
            'Ecossistema de Funcionalidades',
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: textColor,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            'Arquitetura modular desenhada para a perfeita gestão e identificação de componentes',
            textAlign: TextAlign.center,
            style: TextStyle(fontSize: 12, color: subtextColor),
          ),
          const SizedBox(height: 20),
          ...services.asMap().entries.map((e) {
            final i = e.key;
            final s = e.value;
            return _serviceItem(
              (i + 1).toString().padLeft(2, '0'),
              s.$1,
              s.$2,
              s.$3,
              s.$4,
              isDark,
              textColor,
              subtextColor,
            );
          }),
        ],
      ),
    );
  }

  Widget _serviceItem(
    String number,
    IconData icon,
    String title,
    String desc,
    String categoryTag,
    bool isDark,
    Color textColor,
    Color subtextColor,
  ) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF0B132B) : Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(
          color: isDark ? const Color(0xFF1E293B) : const Color(0xFFE2E8F0),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 4,
                ),
                decoration: BoxDecoration(
                  color: const Color(0xFF0284C7).withValues(alpha: 0.15),
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Text(
                  number,
                  style: const TextStyle(
                    fontSize: 12,
                    color: Color(0xFF0284C7),
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              const Icon(
                Icons.star_outline,
                color: Color(0xFF0284C7),
                size: 22,
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            title,
            style: TextStyle(
              fontWeight: FontWeight.bold,
              fontSize: 16,
              color: textColor,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            desc,
            style: TextStyle(fontSize: 12, color: subtextColor, height: 1.4),
          ),
          const SizedBox(height: 14),
          Text(
            categoryTag,
            style: const TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.bold,
              color: Color(0xFF0284C7),
              letterSpacing: 1.1,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPortfolio(bool isDark, Color textColor) {
    final items = [
      (
        'assets/images/Aplicacoes/identificacao.automatica.png',
        'Identificação Automática de Sensores',
      ),
      (
        'assets/images/Aplicacoes/aplicacao.educacional.png',
        'Aplicação Educacional',
      ),
      (
        'assets/images/Aplicacoes/gestaoeorganizacao.png',
        'Gestão e Organização',
      ),
      (
        'assets/images/Aplicacoes/interfaceegerenciamento.png',
        'Interface de Gerenciamento',
      ),
    ];

    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 28, 16, 0),
      child: Column(
        children: [
          Text(
            'Aplicações do Sistema',
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: textColor,
            ),
          ),
          const SizedBox(height: 16),
          GridView.count(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            crossAxisCount: 2,
            crossAxisSpacing: 10,
            mainAxisSpacing: 10,
            childAspectRatio: 0.9,
            children: items
                .map((item) => _portfolioCard(item.$1, item.$2, isDark))
                .toList(),
          ),
        ],
      ),
    );
  }

  Widget _portfolioCard(String imagePath, String title, bool isDark) {
    return Container(
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(12),
        border: Border.all(
          color: const Color(0xFF0284C7).withValues(alpha: 0.4),
        ),
        image: DecorationImage(image: AssetImage(imagePath), fit: BoxFit.cover),
      ),
      child: Stack(
        children: [
          Container(
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(12),
              gradient: LinearGradient(
                colors: [
                  Colors.transparent,
                  (isDark ? const Color(0xFF020617) : Colors.black).withValues(
                    alpha: 0.85,
                  ),
                ],
                begin: Alignment.topCenter,
                end: Alignment.bottomCenter,
              ),
            ),
          ),
          Align(
            alignment: Alignment.bottomCenter,
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    title,
                    textAlign: TextAlign.center,
                    style: const TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Container(
                    height: 3,
                    width: 32,
                    decoration: BoxDecoration(
                      color: const Color(0xFF0284C7),
                      borderRadius: BorderRadius.circular(2),
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

  Widget _buildFooter(bool isDark, Color subtextColor) {
    return Container(
      width: double.infinity,
      margin: const EdgeInsets.only(top: 32),
      padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 24),
      color: isDark ? const Color(0xFF020617) : const Color(0xFFE2E8F0),
      child: Column(
        children: [
          Image.asset(
            isDark
                ? 'assets/images/logos/Logo/LogoDark2.png'
                : 'assets/images/logos/Logo/LogoLight2.png',
            height: 45,
          ),
          const SizedBox(height: 12),
          Text(
            'Plataforma Inteligente para Identificação\ne Gestão de Sensores IoT',
            textAlign: TextAlign.center,
            style: TextStyle(fontSize: 12, color: subtextColor, height: 1.3),
          ),
          const SizedBox(height: 12),
          const Text(
            '© 2026 VISIO – Todos os direitos reservados',
            style: TextStyle(fontSize: 11, color: Color(0xFF0284C7)),
          ),
        ],
      ),
    );
  }
}

class RotatingLedButton extends StatefulWidget {
  final VoidCallback onPressed;

  const RotatingLedButton({super.key, required this.onPressed});

  @override
  State<RotatingLedButton> createState() => _RotatingLedButtonState();
}

class _RotatingLedButtonState extends State<RotatingLedButton>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 3),
    )..repeat();
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: _controller,
      builder: (context, child) {
        return Container(
          width: double.infinity,
          height: 52,
          padding: const EdgeInsets.all(2.5),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(10),
            gradient: SweepGradient(
              colors: const [
                Color(0xFF1E40AF),
                Color(0xFF38BDF8),
                Colors.white,
                Color(0xFF38BDF8),
                Color(0xFF1E40AF),
                Color(0xFF0F172A),
                Color(0xFF0F172A),
                Color(0xFF1E40AF),
              ],
              stops: const [0.0, 0.15, 0.2, 0.25, 0.4, 0.6, 0.8, 1.0],
              transform: GradientRotation(
                _controller.value * 2 * 3.141592653589793,
              ),
            ),
            boxShadow: [
              BoxShadow(
                color: const Color(0xFF38BDF8).withValues(alpha: 0.35),
                blurRadius: 15,
                spreadRadius: 1,
              ),
            ],
          ),
          child: Container(
            decoration: BoxDecoration(
              color: const Color(0xFF1D4ED8),
              borderRadius: BorderRadius.circular(8),
            ),
            child: ElevatedButton(
              onPressed: widget.onPressed,
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.transparent,
                shadowColor: Colors.transparent,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
              child: const Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.center_focus_strong,
                    color: Colors.white,
                    size: 20,
                  ),
                  SizedBox(width: 8),
                  Text(
                    'IDENTIFICAR SENSOR',
                    style: TextStyle(
                      fontSize: 13,
                      fontWeight: FontWeight.bold,
                      letterSpacing: 1.2,
                      color: Colors.white,
                    ),
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }
}
