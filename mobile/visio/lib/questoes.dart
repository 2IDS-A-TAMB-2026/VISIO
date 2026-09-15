import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'login.dart';
import 'services/api_client.dart';
import 'services/auth_service.dart';
import 'widgets/accessibility_panel.dart';

class QuizPage extends StatefulWidget {
  const QuizPage({super.key});

  @override
  State<QuizPage> createState() => _QuizPageState();
}

class _QuizPageState extends State<QuizPage> {
  bool _carregando = true;
  String? _erro;

  Map<String, dynamic>? _pergunta;
  int _indice = 0;
  int _total = 0;
  bool _ultima = false;
  Map<String, dynamic>? _feedback;
  int? _opcaoSelecionadaId;
  bool _acaoEmAndamento = false;

  bool _finalizado = false;
  int _acertosFinal = 0;
  int _totalFinal = 0;

  final List<Map<String, dynamic>> _resumoRespostas = [];

  @override
  void initState() {
    super.initState();
    if (AuthService.instance.estaLogadoComoUsuario) {
      _iniciarQuiz();
    } else {
      _carregando = false;
    }
  }

  Color _nivelCor(String? nivel) {
    switch (nivel) {
      case 'Fácil':
        return AppColors.success;
      case 'Médio':
        return AppColors.warning;
      case 'Difícil':
        return AppColors.danger;
      default:
        return AppColors.primary;
    }
  }

  Future<void> _iniciarQuiz() async {
    setState(() {
      _carregando = true;
      _erro = null;
      _finalizado = false;
      _resumoRespostas.clear();
      _feedback = null;
      _opcaoSelecionadaId = null;
    });

    try {
      await ApiClient.get('quiz');
      await _carregarPergunta();
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

  Future<void> _carregarPergunta() async {
    final corpo = await ApiClient.get('quiz/pergunta') as Map<String, dynamic>;

    if (!mounted) return;
    setState(() {
      _pergunta = corpo['pergunta'] as Map<String, dynamic>;
      _indice = (corpo['indice'] as num).toInt();
      _total = (corpo['total'] as num).toInt();
      _ultima = corpo['ultima'] as bool? ?? false;
      _feedback = corpo['feedback'] as Map<String, dynamic>?;
      _opcaoSelecionadaId = _feedback != null
          ? _feedback!['escolhida'] as int?
          : null;
      _carregando = false;
    });
  }

  Future<void> _selecionar(int idAlternativa) async {
    if (_feedback != null || _acaoEmAndamento) return;
    setState(() {
      _opcaoSelecionadaId = idAlternativa;
    });
  }

  Future<void> _confirmarResposta() async {
    if (_opcaoSelecionadaId == null || _feedback != null || _acaoEmAndamento)
      return;

    setState(() => _acaoEmAndamento = true);

    try {
      final corpo =
          await ApiClient.postForm('quiz/responder', {
                'id_alternativa': _opcaoSelecionadaId.toString(),
              })
              as Map<String, dynamic>;

      if (!mounted) return;
      final feedback = corpo['feedback'] as Map<String, dynamic>;
      setState(() {
        _feedback = feedback;
        _acaoEmAndamento = false;
      });

      _resumoRespostas.add({
        'pergunta': _pergunta?['DESCRICAO'] as String? ?? '',
        'acertou': feedback['acertou'] as bool? ?? false,
      });
    } on ApiException catch (e) {
      if (!mounted) return;
      setState(() => _acaoEmAndamento = false);
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text(e.mensagem)));
    } catch (e) {
      if (!mounted) return;
      setState(() => _acaoEmAndamento = false);
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Erro de conexão: $e')));
    }
  }

  Future<void> _proxima() async {
    if (_feedback == null || _acaoEmAndamento) return;
    setState(() => _acaoEmAndamento = true);

    try {
      final corpo =
          await ApiClient.postForm('quiz/avancar', {}) as Map<String, dynamic>;
      final terminou = corpo['terminou'] as bool? ?? false;

      if (terminou) {
        await _carregarResultado();
      } else {
        _feedback = null;
        _opcaoSelecionadaId = null;
        await _carregarPergunta();
      }

      if (!mounted) return;
      setState(() => _acaoEmAndamento = false);
    } on ApiException catch (e) {
      if (!mounted) return;
      setState(() => _acaoEmAndamento = false);
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text(e.mensagem)));
    } catch (e) {
      if (!mounted) return;
      setState(() => _acaoEmAndamento = false);
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Erro de conexão: $e')));
    }
  }

  Future<void> _carregarResultado() async {
    final corpo = await ApiClient.get('quiz/resultado') as Map<String, dynamic>;
    if (!mounted) return;
    setState(() {
      _acertosFinal = (corpo['acertos'] as num).toInt();
      _totalFinal = (corpo['total'] as num).toInt();
      _finalizado = true;
    });
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

    // Cores dinâmicas
    final bgColor = isDark ? const Color(0xFF030712) : const Color(0xFFF1F5F9);
    final cardBgColor = isDark ? const Color(0xFF1E293B) : Colors.white;
    final borderColor = isDark
        ? const Color(0xFF334155)
        : const Color(0xFFE2E8F0);
    final titleTextColor = isDark ? Colors.white : const Color(0xFF0F172A);
    final bodyTextColor = isDark
        ? const Color(0xFF94A3B8)
        : const Color(0xFF64748B);

    return Scaffold(
      backgroundColor: bgColor,
      body: Stack(
        children: [
          Column(
            children: [
              _buildTopNavBar(
                context,
                isDark,
                titleTextColor,
                bodyTextColor,
                borderColor,
              ),
              Expanded(
                child: SafeArea(
                  child: _buildCorpo(
                    context,
                    isDark,
                    cardBgColor,
                    borderColor,
                    titleTextColor,
                    bodyTextColor,
                  ),
                ),
              ),
            ],
          ),
          const AccessibilityPanel(),
        ],
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
        ],
      ),
    );
  }

  Widget _buildCorpo(
    BuildContext context,
    bool isDark,
    Color cardBg,
    Color borderColor,
    Color titleColor,
    Color textColor,
  ) {
    if (!AuthService.instance.estaLogadoComoUsuario) {
      return _buildPrecisaLogin(
        context,
        cardBg,
        borderColor,
        titleColor,
        textColor,
      );
    }
    if (_carregando) {
      return const Center(child: CircularProgressIndicator());
    }
    if (_erro != null) {
      return Center(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(
                Icons.error_outline,
                size: 48,
                color: AppColors.danger,
              ),
              const SizedBox(height: 12),
              Text(
                _erro!,
                textAlign: TextAlign.center,
                style: TextStyle(color: textColor),
              ),
              const SizedBox(height: 20),
              ElevatedButton(
                onPressed: _iniciarQuiz,
                child: const Text('Tentar novamente'),
              ),
            ],
          ),
        ),
      );
    }
    if (_finalizado) {
      return _buildResultado(
        context,
        cardBg,
        borderColor,
        titleColor,
        textColor,
      );
    }
    if (_pergunta == null) return const SizedBox.shrink();
    return _buildQuiz(
      context,
      isDark,
      cardBg,
      borderColor,
      titleColor,
      textColor,
    );
  }

  Widget _buildPrecisaLogin(
    BuildContext context,
    Color cardBg,
    Color borderColor,
    Color titleColor,
    Color textColor,
  ) {
    return Center(
      child: Container(
        constraints: const BoxConstraints(maxWidth: 500),
        margin: const EdgeInsets.all(24),
        padding: const EdgeInsets.all(32),
        decoration: BoxDecoration(
          color: cardBg,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: borderColor),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.quiz_outlined, size: 64, color: textColor),
            const SizedBox(height: 20),
            Text(
              'Faça login para jogar',
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: titleColor,
              ),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 10),
            Text(
              'O quiz exige uma conta, assim como no site — é o que permite guardar seu histórico e sua pontuação.',
              textAlign: TextAlign.center,
              style: TextStyle(color: textColor, fontSize: 13, height: 1.4),
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              height: 44,
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF007BFF),
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
                onPressed: () async {
                  await Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const LoginPage()),
                  );
                  if (!mounted) return;
                  if (AuthService.instance.estaLogadoComoUsuario) {
                    _iniciarQuiz();
                  } else {
                    setState(() {});
                  }
                },
                child: const Text('Entrar'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildQuiz(
    BuildContext context,
    bool isDark,
    Color cardBg,
    Color borderColor,
    Color titleColor,
    Color textColor,
  ) {
    final pergunta = _pergunta!;
    final nivel = pergunta['NIVEL_DIFICULDADE'] as String?;
    final alternativas = (pergunta['alternativas'] as List)
        .cast<Map<String, dynamic>>();
    final feedback = _feedback;

    final corretaIdRaw = feedback?['correta_id'];
    final corretaId = corretaIdRaw == null
        ? null
        : (corretaIdRaw is int
              ? corretaIdRaw
              : int.parse(corretaIdRaw.toString()));
    final acertou = feedback?['acertou'] as bool? ?? false;

    return Center(
      child: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 24),
        child: Container(
          constraints: const BoxConstraints(maxWidth: 600),
          padding: const EdgeInsets.all(28),
          decoration: BoxDecoration(
            color: cardBg,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: borderColor),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(isDark ? 0.3 : 0.05),
                blurRadius: 15,
                offset: const Offset(0, 6),
              ),
            ],
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    'Pergunta $_indice de $_total',
                    style: TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.w600,
                      color: textColor,
                    ),
                  ),
                  if (nivel != null)
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 10,
                        vertical: 4,
                      ),
                      decoration: BoxDecoration(
                        color: _nivelCor(nivel).withOpacity(0.1),
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(
                          color: _nivelCor(nivel).withOpacity(0.3),
                        ),
                      ),
                      child: Text(
                        nivel,
                        style: TextStyle(
                          fontSize: 12,
                          color: _nivelCor(nivel),
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                ],
              ),
              const SizedBox(height: 16),

              Text(
                pergunta['DESCRICAO'] as String? ?? '',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: titleColor,
                  height: 1.3,
                ),
              ),
              const SizedBox(height: 24),

              ...alternativas.map((alt) {
                return _opcaoWidget(
                  context,
                  isDark,
                  alt,
                  corretaId,
                  titleColor,
                  borderColor,
                );
              }),

              const SizedBox(height: 16),

              if (feedback != null) ...[
                Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: acertou
                        ? AppColors.success.withOpacity(0.1)
                        : AppColors.danger.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(
                      color: acertou
                          ? AppColors.success.withOpacity(0.3)
                          : AppColors.danger.withOpacity(0.3),
                    ),
                  ),
                  child: Row(
                    children: [
                      Icon(
                        acertou ? Icons.check_circle : Icons.cancel,
                        color: acertou ? AppColors.success : AppColors.danger,
                        size: 20,
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Text(
                          acertou
                              ? 'Correto! Muito bem!'
                              : 'Incorreto. Veja a resposta correta acima.',
                          style: TextStyle(
                            color: acertou
                                ? AppColors.success
                                : AppColors.danger,
                            fontSize: 13,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 16),
              ],

              SizedBox(
                width: double.infinity,
                height: 46,
                child: _acaoEmAndamento
                    ? const Center(child: CircularProgressIndicator())
                    : ElevatedButton(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF007BFF),
                          foregroundColor: Colors.white,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                          elevation: 0,
                        ),
                        onPressed: feedback == null
                            ? (_opcaoSelecionadaId != null
                                  ? _confirmarResposta
                                  : null)
                            : _proxima,
                        child: Text(
                          feedback == null
                              ? 'Responder'
                              : (_ultima ? 'Ver resultado' : 'Próxima questão'),
                          style: const TextStyle(
                            fontSize: 15,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _opcaoWidget(
    BuildContext context,
    bool isDark,
    Map<String, dynamic> alt,
    int? corretaId,
    Color titleColor,
    Color borderColor,
  ) {
    final idAlt = alt['ID_ALTERNATIVA'] is int
        ? alt['ID_ALTERNATIVA'] as int
        : int.parse(alt['ID_ALTERNATIVA'].toString());
    final respondido = _feedback != null;
    final selecionado = idAlt == _opcaoSelecionadaId;

    Color itemBorderColor = isDark
        ? const Color(0xFF334155)
        : const Color(0xFFE2E8F0);
    Color itemBgColor = isDark
        ? const Color(0xFF0F172A)
        : const Color(0xFFF8FAFC);

    if (respondido) {
      if (idAlt == corretaId) {
        itemBorderColor = AppColors.success;
        itemBgColor = AppColors.success.withOpacity(0.1);
      } else if (selecionado) {
        itemBorderColor = AppColors.danger;
        itemBgColor = AppColors.danger.withOpacity(0.1);
      }
    } else if (selecionado) {
      itemBorderColor = const Color(0xFF007BFF);
      itemBgColor = const Color(0xFF007BFF).withOpacity(0.1);
    }

    return GestureDetector(
      onTap: () => _selecionar(idAlt),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        margin: const EdgeInsets.only(bottom: 12),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        decoration: BoxDecoration(
          color: itemBgColor,
          borderRadius: BorderRadius.circular(8),
          border: Border.all(
            color: itemBorderColor,
            width: selecionado || (respondido && idAlt == corretaId) ? 2 : 1,
          ),
        ),
        child: Row(
          children: [
            Container(
              width: 18,
              height: 18,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: selecionado
                    ? const Color(0xFF007BFF)
                    : Colors.transparent,
                border: Border.all(
                  color: selecionado
                      ? const Color(0xFF007BFF)
                      : (isDark ? Colors.white54 : Colors.black38),
                  width: 2,
                ),
              ),
              child: selecionado
                  ? const Center(child: ContainerCircle())
                  : null,
            ),
            const SizedBox(width: 14),
            Expanded(
              child: Text(
                alt['DESCRICAO'] as String? ?? '',
                style: TextStyle(fontSize: 14, color: titleColor),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildResultado(
    BuildContext context,
    Color cardBg,
    Color borderColor,
    Color titleColor,
    Color textColor,
  ) {
    final pct = _totalFinal > 0
        ? (_acertosFinal / _totalFinal * 100).round()
        : 0;
    final Color cor = pct >= 80
        ? AppColors.success
        : pct >= 50
        ? AppColors.warning
        : AppColors.danger;
    final String msg = pct >= 80
        ? 'Excelente! 🎉'
        : pct >= 50
        ? 'Bom trabalho! 👍'
        : 'Continue praticando! 💪';

    return Center(
      child: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Container(
          constraints: const BoxConstraints(maxWidth: 500),
          padding: const EdgeInsets.all(32),
          decoration: BoxDecoration(
            color: cardBg,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: borderColor),
          ),
          child: Column(
            children: [
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: cor.withOpacity(0.1),
                  shape: BoxShape.circle,
                ),
                child: Text(
                  '$pct%',
                  style: TextStyle(
                    fontSize: 36,
                    fontWeight: FontWeight.bold,
                    color: cor,
                  ),
                ),
              ),
              const SizedBox(height: 20),
              Text(
                msg,
                style: TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                  color: titleColor,
                ),
              ),
              const SizedBox(height: 10),
              Text(
                '$_acertosFinal de $_totalFinal questões corretas',
                style: TextStyle(color: textColor, fontSize: 14),
              ),
              const SizedBox(height: 8),
              Text(
                'Salvo no seu histórico.',
                style: TextStyle(
                  color: textColor,
                  fontSize: 12,
                  fontStyle: FontStyle.italic,
                ),
              ),
              const SizedBox(height: 28),

              ..._resumoRespostas.map((r) {
                final acertou = r['acertou'] as bool;
                return Container(
                  margin: const EdgeInsets.only(bottom: 10),
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: cardBg,
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(color: borderColor),
                  ),
                  child: Row(
                    children: [
                      Icon(
                        Icons.circle,
                        size: 10,
                        color: acertou ? AppColors.success : AppColors.danger,
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Text(
                          r['pergunta'] as String,
                          style: TextStyle(fontSize: 13, color: titleColor),
                        ),
                      ),
                    ],
                  ),
                );
              }),

              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                height: 44,
                child: ElevatedButton.icon(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF007BFF),
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  onPressed: _iniciarQuiz,
                  icon: const Icon(Icons.refresh, size: 18),
                  label: const Text('Tentar novamente'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class ContainerCircle extends StatelessWidget {
  const ContainerCircle({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 6,
      height: 6,
      decoration: const BoxDecoration(
        shape: BoxShape.circle,
        color: Colors.white,
      ),
    );
  }
}
