import 'package:flutter/material.dart';
import 'appcolor.dart';

class QuizPage extends StatefulWidget {
  const QuizPage({super.key});

  @override
  State<QuizPage> createState() => _QuizPageState();
}

class _QuizPageState extends State<QuizPage> {
  int _questaoAtual = 0;
  int? _opcaoSelecionada;
  bool _respondido = false;
  int _acertos = 0;
  bool _finalizado = false;

  /// Guarda, na mesma ordem das perguntas respondidas, se cada uma foi
  /// acertada ou não — usado no resumo final. Antes o resumo marcava as
  /// primeiras `_acertos` perguntas como certas por posição, o que não
  /// refletia quais perguntas foram de fato acertadas.
  final List<bool> _respostasCorretas = [];

  final List<Map<String, dynamic>> questoes = const [
    {
      'nivel': 'Fácil',
      'pergunta': 'O que mede um sensor LDR?',
      'opcoes': [
        'Variação da resistência conforme a luz',
        'Intensidade sonora ambiente',
        'Temperatura superficial',
        'Umidade relativa do ar',
      ],
      'correta': 0,
    },
    {
      'nivel': 'Fácil',
      'pergunta': 'Para que serve o sensor PIR?',
      'opcoes': [
        'Detectar variações de radiação infravermelha',
        'Medir pressão atmosférica',
        'Controlar corrente elétrica',
        'Emitir sinais ultrassônicos',
      ],
      'correta': 1,
    },
    {
      'nivel': 'Médio',
      'pergunta': 'O que é IoT?',
      'opcoes': [
        'Rede de dispositivos conectados que trocam dados',
        'Protocolo de comunicação serial',
        'Sistema operacional embarcado',
        'Arquitetura de microcontroladores',
      ],
      'correta': 2,
    },
    {
      'nivel': 'Médio',
      'pergunta': 'Como funciona um sensor ultrassônico?',
      'opcoes': [
        'Calcula distância pelo tempo de retorno do som',
        'Mede intensidade luminosa refletida',
        'Detecta variação de tensão elétrica',
        'Utiliza campo magnético para leitura',
      ],
      'correta': 3,
    },
    {
      'nivel': 'Médio',
      'pergunta': 'O que significa GPIO?',
      'opcoes': [
        'Pinos configuráveis para entrada e saída digital',
        'Interface de comunicação analógica',
        'Barramento exclusivo de sensores',
        'Protocolo de rede embarcada',
      ],
      'correta': 0,
    },
    {
      'nivel': 'Médio',
      'pergunta': 'Qual a função do sensor MQ-2?',
      'opcoes': [
        'Detectar concentração de gases combustíveis',
        'Medir pressão de fluidos',
        'Monitorar temperatura interna',
        'Detectar presença por infravermelho',
      ],
      'correta': 1,
    },
    {
      'nivel': 'Difícil',
      'pergunta': 'O que é PWM?',
      'opcoes': [
        'Modulação da largura de pulso para controle de potência',
        'Protocolo síncrono de comunicação digital',
        'Conversão de sinal analógico para digital',
        'Método de compressão de dados',
      ],
      'correta': 2,
    },
  ];

  Color _nivelCor(String nivel) {
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

  void _selecionar(int opcao) {
    if (_respondido) return;
    final acertou = opcao == questoes[_questaoAtual]['correta'];
    setState(() {
      _opcaoSelecionada = opcao;
      _respondido = true;
      if (acertou) _acertos++;
      _respostasCorretas.add(acertou);
    });
  }

  void _proxima() {
    if (_questaoAtual < questoes.length - 1) {
      setState(() {
        _questaoAtual++;
        _opcaoSelecionada = null;
        _respondido = false;
      });
    } else {
      setState(() => _finalizado = true);
    }
  }

  void _reiniciar() {
    setState(() {
      _questaoAtual = 0;
      _opcaoSelecionada = null;
      _respondido = false;
      _acertos = 0;
      _finalizado = false;
      _respostasCorretas.clear();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/logos/Logo/LogoDark2.png', height: 40),
       
            Text('Quiz IoT'),
          ],
        ),
        actions: [
          if (!_finalizado)
            Padding(
              padding: const EdgeInsets.only(right: 16),
              child: Center(
                child: Text(
                  '${_questaoAtual + 1}/${questoes.length}',
                  style: const TextStyle(
                    color: AppColors.textMuted,
                    fontSize: 13,
                  ),
                ),
              ),
            ),
        ],
      ),
      body: SafeArea(child: _finalizado ? _buildResultado() : _buildQuiz()),
    );
  }

  Widget _buildQuiz() {
    final q = questoes[_questaoAtual];
    final nivel = q['nivel'] as String;
    final correta = q['correta'] as int;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(4),
            child: LinearProgressIndicator(
              value: (_questaoAtual + 1) / questoes.length,
              backgroundColor: AppColors.border,
              color: AppColors.primary,
              minHeight: 5,
            ),
          ),
          const SizedBox(height: 20),

          Row(
            children: [
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 4,
                ),
                decoration: BoxDecoration(
                  color: _nivelCor(nivel).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: _nivelCor(nivel).withOpacity(0.3)),
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
                'Questão ${_questaoAtual + 1}',
                style: const TextStyle(
                  fontSize: 12,
                  color: AppColors.textMuted,
                ),
              ),
            ],
          ),

          const SizedBox(height: 20),

          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: const Color.fromARGB(255, 29, 27, 27),
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: AppColors.border),
            ),
            child: Text(
              q['pergunta'] as String,
              style: const TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                height: 1.5,
              ),
            ),
          ),

          const SizedBox(height: 20),

          ...(q['opcoes'] as List<String>).asMap().entries.map((e) {
            final index = e.key;
            final texto = e.value;
            return _opcaoWidget(index, texto, correta);
          }),

          const SizedBox(height: 20),

          if (_respondido)
            AnimatedContainer(
              duration: const Duration(milliseconds: 300),
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: _opcaoSelecionada == correta
                    ? AppColors.success.withOpacity(0.1)
                    : AppColors.danger.withOpacity(0.1),
                borderRadius: BorderRadius.circular(12),
                border: Border.all(
                  color: _opcaoSelecionada == correta
                      ? AppColors.success.withOpacity(0.3)
                      : AppColors.danger.withOpacity(0.3),
                ),
              ),
              child: Row(
                children: [
                  Icon(
                    _opcaoSelecionada == correta
                        ? Icons.check_circle
                        : Icons.cancel,
                    color: _opcaoSelecionada == correta
                        ? AppColors.success
                        : AppColors.danger,
                    size: 20,
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      _opcaoSelecionada == correta
                          ? 'Correto! Muito bem!'
                          : 'Incorreto. A resposta certa é:\n"${(q['opcoes'] as List<String>)[correta]}"',
                      style: TextStyle(
                        color: _opcaoSelecionada == correta
                            ? AppColors.success
                            : AppColors.danger,
                        fontSize: 13,
                        height: 1.4,
                      ),
                    ),
                  ),
                ],
              ),
            ),

          if (_respondido) ...[
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _proxima,
                child: Text(
                  _questaoAtual < questoes.length - 1
                      ? 'Próxima questão'
                      : 'Ver resultado',
                ),
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _opcaoWidget(int index, String texto, int correta) {
    Color borderColor = AppColors.border;
    Color bgColor = const Color.fromARGB(241, 26, 24, 24);
    IconData? trailingIcon;

    if (_respondido) {
      if (index == correta) {
        borderColor = AppColors.success;
        bgColor = AppColors.success.withOpacity(0.08);
        trailingIcon = Icons.check_circle;
      } else if (index == _opcaoSelecionada) {
        borderColor = AppColors.danger;
        bgColor = AppColors.danger.withOpacity(0.08);
        trailingIcon = Icons.cancel;
      }
    } else if (index == _opcaoSelecionada) {
      borderColor = AppColors.primary;
      bgColor = AppColors.primary.withOpacity(0.08);
    }

    return GestureDetector(
      onTap: () => _selecionar(index),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        margin: const EdgeInsets.only(bottom: 10),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        decoration: BoxDecoration(
          color: bgColor,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: borderColor,
            width: _respondido && index == correta ? 1.5 : 1,
          ),
        ),
        child: Row(
          children: [
            Container(
              width: 28,
              height: 28,
              decoration: BoxDecoration(
                color: borderColor.withOpacity(0.1),
                shape: BoxShape.circle,
                border: Border.all(color: borderColor),
              ),
              child: Center(
                child: Text(
                  String.fromCharCode(65 + index),
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                    color: borderColor,
                  ),
                ),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                texto,
                style: const TextStyle(fontSize: 14, height: 1.3),
              ),
            ),
            if (trailingIcon != null)
              Icon(trailingIcon, size: 18, color: borderColor),
          ],
        ),
      ),
    );
  }

  Widget _buildResultado() {
    final pct = (_acertos / questoes.length * 100).round();
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
            style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 10),
          Text(
            '$_acertos de ${questoes.length} questões corretas',
            style: const TextStyle(color: AppColors.textMuted, fontSize: 14),
          ),
          const SizedBox(height: 32),

          ...questoes.asMap().entries.map((e) {
            final i = e.key;
            final q = e.value;
            return Container(
              margin: const EdgeInsets.only(bottom: 10),
              padding: const EdgeInsets.all(14),
              decoration: BoxDecoration(
                color: AppColors.bgCardAlt,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: AppColors.border),
              ),
              child: Row(
                children: [
                  Icon(
                    Icons.circle,
                    size: 10,
                    color: (i < _respostasCorretas.length && _respostasCorretas[i])
                        ? AppColors.success
                        : AppColors.danger,
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      q['pergunta'] as String,
                      style: const TextStyle(fontSize: 13),
                    ),
                  ),
                ],
              ),
            );
          }),

          const SizedBox(height: 24),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: _reiniciar,
              icon: const Icon(Icons.refresh, size: 18),
              label: const Text('Tentar novamente'),
            ),
          ),
        ],
      ),
    );
  }
}
