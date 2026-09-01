import 'package:flutter/material.dart';
import 'lista.dart';
import 'cadastro_questao.dart';
import 'cadastro_sensor.dart';
import 'perfil_adm.dart';
import 'appcolor.dart';
import 'login.dart';
import 'services/api_client.dart';
import 'services/auth_service.dart';
import 'widgets/accessibility_panel.dart';

class AdminShell extends StatefulWidget {
  const AdminShell({super.key});

  @override
  State<AdminShell> createState() => _AdminShellState();
}

class _AdminShellState extends State<AdminShell> {
  int _index = 0;

  final List<Widget> _screens = const [
    DashboardPage(),
    UsuariosPage(),
    QuestoesAdminPage(),
    SensoresAdminPage(),
    PerfilAdminPage(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        children: [
          IndexedStack(index: _index, children: _screens),
          const AccessibilityPanel(),
        ],
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _index,
        onTap: (i) => setState(() => _index = i),
        type: BottomNavigationBarType.fixed,
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.dashboard_outlined),
            activeIcon: Icon(Icons.dashboard),
            label: 'Dashboard',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.people_outline),
            activeIcon: Icon(Icons.people),
            label: 'Usuários',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.quiz_outlined),
            activeIcon: Icon(Icons.quiz),
            label: 'Questões',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.memory_outlined),
            activeIcon: Icon(Icons.memory),
            label: 'Sensores',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.manage_accounts_outlined),
            activeIcon: Icon(Icons.manage_accounts),
            label: 'Perfil',
          ),
        ],
      ),
    );
  }
}

/// CORRIGIDO/INTEGRADO: antes os 4 cards eram números fixos no código
/// (128 sensores, "02" alertas críticos, "84%" uso da rede, 312 usuários),
/// sem nenhuma relação com o backend. Agora busca os dados reais em
/// `GET /admin/dashboard` (`AdminController::dashboard`).
///
/// "Alertas críticos" e "Uso da rede" foram REMOVIDOS: eram conceitos só do
/// protótipo local, sem nenhuma coluna equivalente no backend (que calcula
/// estatísticas do quiz, não monitoramento de rede). Foram substituídos por
/// "Respostas" e "Taxa de acerto", que são exatamente o que o dashboard do
/// site mostra.
class DashboardPage extends StatefulWidget {
  const DashboardPage({super.key});

  @override
  State<DashboardPage> createState() => _DashboardPageState();
}

class _DashboardPageState extends State<DashboardPage> {
  bool _carregando = true;
  String? _erro;
  Map<String, dynamic>? _dados;

  @override
  void initState() {
    super.initState();
    _carregar();
  }

  Future<void> _carregar() async {
    setState(() {
      _carregando = true;
      _erro = null;
    });

    try {
      final corpo = await ApiClient.get('admin/dashboard') as Map<String, dynamic>;
      if (!mounted) return;
      setState(() {
        _dados = corpo;
        _carregando = false;
      });
    } on ApiException catch (e) {
      if (!mounted) return;
      setState(() {
        _carregando = false;
        _erro = e.mensagem;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _carregando = false;
        _erro = 'Erro de conexão com o servidor: $e';
      });
    }
  }

  Future<void> _sair() async {
    await AuthService.instance.logout();
    if (!mounted) return;
    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (_) => const LoginPage()),
      (route) => false,
    );
  }

  @override
  Widget build(BuildContext context) {
    final admin = _dados?['admin'] as Map<String, dynamic>?;
    final nomeAdmin = admin?['NOME'] as String? ?? 'Administrador';

    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false,
        title: Row(
          children: [
            Image.asset('assets/images/logos/Logo/LogoDark2.png', height: 40),
            const SizedBox(width: 8),
            const Text('Admin'),
          ],
        ),
        actions: [
          PopupMenuButton(
            icon: const CircleAvatar(
              radius: 15,
              backgroundColor: AppColors.primary,
              child: Icon(Icons.person, size: 18, color: Colors.white),
            ),
            itemBuilder: (_) => [
              const PopupMenuItem(
                value: 'sair',
                child: Row(
                  children: [
                    Icon(Icons.logout, size: 18),
                    SizedBox(width: 8),
                    Text('Sair'),
                  ],
                ),
              ),
            ],
            onSelected: (v) {
              if (v == 'sair') _sair();
            },
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: SafeArea(
        child: _carregando
            ? const Center(child: CircularProgressIndicator())
            : _erro != null
            ? Center(
                child: Padding(
                  padding: const EdgeInsets.all(24),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(Icons.error_outline, size: 48, color: AppColors.danger),
                      const SizedBox(height: 12),
                      Text(_erro!, textAlign: TextAlign.center, style: TextStyle(color: context.textMuted)),
                      const SizedBox(height: 20),
                      ElevatedButton(onPressed: _carregar, child: const Text('Tentar novamente')),
                    ],
                  ),
                ),
              )
            : RefreshIndicator(
                onRefresh: _carregar,
                child: SingleChildScrollView(
                  physics: const AlwaysScrollableScrollPhysics(),
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        padding: const EdgeInsets.all(18),
                        decoration: BoxDecoration(
                          gradient: const LinearGradient(
                            colors: [AppColors.bgCardDark, AppColors.surfaceDark],
                            begin: Alignment.topLeft,
                            end: Alignment.bottomRight,
                          ),
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(color: AppColors.border),
                        ),
                        child: Row(
                          children: [
                            const CircleAvatar(
                              radius: 26,
                              backgroundColor: AppColors.primary,
                              child: Icon(
                                Icons.admin_panel_settings,
                                color: Colors.white,
                                size: 26,
                              ),
                            ),
                            const SizedBox(width: 14),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    'Olá, $nomeAdmin!',
                                    style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                                  ),
                                  const SizedBox(height: 3),
                                  const Text(
                                    'Painel de controle VISIO',
                                    style: TextStyle(color: AppColors.textMuted, fontSize: 12),
                                  ),
                                ],
                              ),
                            ),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                              decoration: BoxDecoration(
                                color: AppColors.success.withValues(alpha: 0.1),
                                borderRadius: BorderRadius.circular(10),
                                border: Border.all(color: AppColors.success.withValues(alpha: 0.3)),
                              ),
                              child: const Text(
                                'Online',
                                style: TextStyle(color: AppColors.success, fontSize: 11, fontWeight: FontWeight.bold),
                              ),
                            ),
                          ],
                        ),
                      ),

                      const SizedBox(height: 20),

                      const Text('Visão geral', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold)),
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          Expanded(
                            child: _kpiCard(
                              context,
                              '${_dados?['total_usuarios'] ?? 0}',
                              'Usuários',
                              Icons.people_outline,
                              AppColors.success,
                            ),
                          ),
                          const SizedBox(width: 10),
                          Expanded(
                            child: _kpiCard(
                              context,
                              '${_dados?['total_sensores'] ?? 0}',
                              'Sensores no catálogo',
                              Icons.memory,
                              AppColors.primary,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 10),
                      Row(
                        children: [
                          Expanded(
                            child: _kpiCard(
                              context,
                              '${_dados?['total_respostas'] ?? 0}',
                              'Respostas no quiz',
                              Icons.fact_check_outlined,
                              AppColors.warning,
                            ),
                          ),
                          const SizedBox(width: 10),
                          Expanded(
                            child: _kpiCard(
                              context,
                              '${_dados?['taxa_acerto'] ?? 0}%',
                              'Taxa de acerto',
                              Icons.trending_up,
                              AppColors.success,
                            ),
                          ),
                        ],
                      ),

                      const SizedBox(height: 24),
                      _buildAtividadesRecentes(context),
                      const SizedBox(height: 24),
                    ],
                  ),
                ),
              ),
      ),
    );
  }

  Widget _buildAtividadesRecentes(BuildContext context) {
    final atividades = (_dados?['atividades_recentes'] as List?)?.cast<Map<String, dynamic>>() ?? [];

    if (atividades.isEmpty) return const SizedBox.shrink();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text('Atividades recentes', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold)),
        const SizedBox(height: 12),
        ...atividades.map((a) {
          final acertou = (a['IS_CORRETA'] as num?)?.toInt() == 1;
          return Container(
            margin: const EdgeInsets.only(bottom: 8),
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: context.cardBg,
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: context.borderColor),
            ),
            child: Row(
              children: [
                Icon(
                  acertou ? Icons.check_circle : Icons.cancel,
                  size: 18,
                  color: acertou ? AppColors.success : AppColors.danger,
                ),
                const SizedBox(width: 10),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        '${a['NOME'] ?? ''} respondeu "${a['PERGUNTA_TEXTO'] ?? ''}"',
                        style: const TextStyle(fontSize: 12),
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                      ),
                      const SizedBox(height: 2),
                      Text(
                        a['TEMPO_RELATIVO'] as String? ?? '',
                        style: TextStyle(fontSize: 11, color: context.textMuted),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          );
        }),
      ],
    );
  }

  Widget _kpiCard(
    BuildContext context,
    String value,
    String label,
    IconData icon,
    Color color,
  ) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: context.cardBg,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: color.withValues(alpha: 0.2)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: color, size: 20),
          const SizedBox(height: 10),
          Text(
            value,
            style: TextStyle(
              fontSize: 22,
              fontWeight: FontWeight.bold,
              color: color,
            ),
          ),
          const SizedBox(height: 3),
          Text(label, style: TextStyle(fontSize: 11, color: context.textMuted)),
        ],
      ),
    );
  }
}
