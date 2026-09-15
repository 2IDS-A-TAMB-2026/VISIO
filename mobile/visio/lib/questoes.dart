import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'login.dart';
import 'services/api_client.dart';
import 'services/auth_service.dart';

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
      _opcaoSelecionadaId = _feedback != null ? _feedback!['escolhida'] as int? : null;
      _carregando = false;
    });
  }

  Future<void> _selecionar(int idAlternativa) async {
    if (_feedback != null || _acaoEmAndamento) return;

    setState(() {
      _acaoEmAndamento = true;
      _opcaoSelecionadaId = idAlternativa;
    });

    try {
      final corpo = await ApiClient.postForm('quiz/responder', {
        'id_alternativa': idAlternativa.toString(),
      }) as Map<String, dynamic>;

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
      final corpo = await ApiClient.postForm('quiz/avancar', {}) as Map<String, dynamic>;
      final terminou = corpo['terminou'] as bool? ?? false;

      if (terminou) {
        await _carregarResultado();
      } else {
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
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/logos/Logo/LogoDark2.png', height: 40),
            const Text('Quiz IoT'),
          ],
        ),
        actions: [
          if (!_finalizado && _pergunta != null)
            Padding(
              padding: const EdgeInsets.only(right: 16),
              child: Center(
                child: Text(
                  '$_indice/$_total',
                  style: TextStyle(color: context.textMuted, fontSize: 13),
                ),
              ),
            ),
        ],
      ),
      body: SafeArea(child: _buildCorpo(context)),
    );
  }

  Widget _buildCorpo(BuildContext context) {
    if (!AuthService.instance.estaLogadoComoUsuario) {
      return _buildPrecisaLogin(context);
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
              const Icon(Icons.error_outline, size: 48, color: AppColors.danger),
              const SizedBox(height: 12),
              Text(_erro!, textAlign: TextAlign.center, style: TextStyle(color: context.textMuted)),
              const SizedBox(height: 20),
              ElevatedButton(onPressed: _iniciarQuiz, child: const Text('Tentar novamente')),
            ],
          ),
        ),
      );
    }
    if (_finalizado) return _buildResultado(context);
    if (_pergunta == null) return const SizedBox.shrink();
    return _buildQuiz(context);
  }

  Widget _buildPrecisaLogin(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(32),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.quiz_outlined, size: 64, color: context.textMuted),
            const SizedBox(height: 20),
            const Text(
              'Faça login para jogar',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 10),
            Text(
              'O quiz exige uma conta, assim como no site — é o que permite '
              'guardar seu histórico e sua pontuação.',
              textAlign: TextAlign.center,
              style: TextStyle(color: context.textMuted, fontSize: 13, height: 1.4),
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
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

  Widget _buildQuiz(BuildContext context) {
    final pergunta = _pergunta!;
    final nivel = pergunta['NIVEL_DIFICULDADE'] as String?;
    final alternativas = (pergunta['alternativas'] as List).cast<Map<String, dynamic>>();
    final feedback = _feedback;
   
    final corretaIdRaw = feedback?['correta_id'];
    final corretaId = corretaIdRaw == null
        ? null
        : (corretaIdRaw is int ? corretaIdRaw : int.parse(corretaIdRaw.toString()));
    final acertou = feedback?['acertou'] as bool? ?? false;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(4),
            child: LinearProgressIndicator(
              value: _total > 0 ? _indice / _total : 0,
              backgroundColor: context.borderColor,
              color: AppColors.primary,
              minHeight: 5,
            ),
          ),
          const SizedBox(height: 20),

          Row(
            children: [
              if (nivel != null)
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: _nivelCor(nivel).withValues(alpha: 0.1),
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(color: _nivelCor(nivel).withValues(alpha: 0.3)),
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
              const SizedBox(width: 10),
              Text(
                'Questão $_indice',
                style: TextStyle(fontSize: 12, color: context.textMuted),
              ),
            ],
          ),

          const SizedBox(height: 20),

          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: context.cardBg,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: context.borderColor),
            ),
            child: Text(
              pergunta['DESCRICAO'] as String? ?? '',
              style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w600, height: 1.5),
            ),
          ),

          const SizedBox(height: 20),

          ...alternativas.asMap().entries.map((e) {
            final index = e.key;
            final alt = e.value;
            return _opcaoWidget(context, index, alt, corretaId);
          }),

          const SizedBox(height: 20),

          if (feedback != null)
            AnimatedContainer(
              duration: const Duration(milliseconds: 300),
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: acertou
                    ? AppColors.success.withValues(alpha: 0.1)
                    : AppColors.danger.withValues(alpha: 0.1),
                borderRadius: BorderRadius.circular(12),
                border: Border.all(
                  color: acertou
                      ? AppColors.success.withValues(alpha: 0.3)
                      : AppColors.danger.withValues(alpha: 0.3),
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
                      acertou ? 'Correto! Muito bem!' : 'Incorreto. Veja a resposta certa marcada acima.',
                      style: TextStyle(
                        color: acertou ? AppColors.success : AppColors.danger,
                        fontSize: 13,
                        height: 1.4,
                      ),
                    ),
                  ),
                ],
              ),
            ),

          if (feedback != null) ...[
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: _acaoEmAndamento
                  ? const Center(child: CircularProgressIndicator())
                  : ElevatedButton(
                      onPressed: _proxima,
                      child: Text(_ultima ? 'Ver resultado' : 'Próxima questão'),
                    ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _opcaoWidget(
    BuildContext context,
    int index,
    Map<String, dynamic> alt,
    int? corretaId,
  ) {
    final idAlt = alt['ID_ALTERNATIVA'] is int
        ? alt['ID_ALTERNATIVA'] as int
        : int.parse(alt['ID_ALTERNATIVA'].toString());
    final respondido = _feedback != null;

    Color borderColor = context.borderColor;
    Color bgColor = context.cardBg;
    IconData? trailingIcon;

    if (respondido) {
      if (idAlt == corretaId) {
        borderColor = AppColors.success;
        bgColor = AppColors.success.withValues(alpha: 0.08);
        trailingIcon = Icons.check_circle;
      } else if (idAlt == _opcaoSelecionadaId) {
        borderColor = AppColors.danger;
        bgColor = AppColors.danger.withValues(alpha: 0.08);
        trailingIcon = Icons.cancel;
      }
    } else if (idAlt == _opcaoSelecionadaId) {
      borderColor = AppColors.primary;
      bgColor = AppColors.primary.withValues(alpha: 0.08);
    }

    return GestureDetector(
      onTap: () => _selecionar(idAlt),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        margin: const EdgeInsets.only(bottom: 10),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        decoration: BoxDecoration(
          color: bgColor,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: borderColor,
            width: respondido && idAlt == corretaId ? 1.5 : 1,
          ),
        ),
        child: Row(
          children: [
            Container(
              width: 28,
              height: 28,
              decoration: BoxDecoration(
                color: borderColor.withValues(alpha: 0.1),
                shape: BoxShape.circle,
                border: Border.all(color: borderColor),
              ),
              child: Center(
                child: Text(
                  String.fromCharCode(65 + index),
                  style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: borderColor),
                ),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                alt['DESCRICAO'] as String? ?? '',
                style: const TextStyle(fontSize: 14, height: 1.3),
              ),
            ),
            if (trailingIcon != null) Icon(trailingIcon, size: 18, color: borderColor),
          ],
        ),
      ),
    );
  }

  Widget _buildResultado(BuildContext context) {
    final pct = _totalFinal > 0 ? (_acertosFinal / _totalFinal * 100).round() : 0;
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

    return SingleChildScrollView(
      padding: const EdgeInsets.all(24),
      child: Column(
        children: [
          const SizedBox(height: 20),
          Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              color: cor.withValues(alpha: 0.1),
              shape: BoxShape.circle,
            ),
            child: Text(
              '$pct%',
              style: TextStyle(fontSize: 36, fontWeight: FontWeight.bold, color: cor),
            ),
          ),
          const SizedBox(height: 20),
          Text(msg, style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
          const SizedBox(height: 10),
          Text(
            '$_acertosFinal de $_totalFinal questões corretas',
            style: TextStyle(color: context.textMuted, fontSize: 14),
          ),
          const SizedBox(height: 8),
          Text(
            'Salvo no seu histórico.',
            style: TextStyle(color: context.textMuted, fontSize: 12, fontStyle: FontStyle.italic),
          ),
          const SizedBox(height: 32),

          ..._resumoRespostas.map((r) {
            final acertou = r['acertou'] as bool;
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
                  Icon(
                    Icons.circle,
                    size: 10,
                    color: acertou ? AppColors.success : AppColors.danger,
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Text(r['pergunta'] as String, style: const TextStyle(fontSize: 13)),
                  ),
                ],
              ),
            );
          }),

          const SizedBox(height: 24),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: _iniciarQuiz,
              icon: const Icon(Icons.refresh, size: 18),
              label: const Text('Tentar novamente'),
            ),
          ),
        ],
      ),
    );
  }
}
