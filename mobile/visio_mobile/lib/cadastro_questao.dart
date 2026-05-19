import 'package:flutter/material.dart';
import 'appcolor.dart';

class QuestoesAdminPage extends StatefulWidget {
  const QuestoesAdminPage({super.key});

  @override
  State<QuestoesAdminPage> createState() => _QuestoesAdminPageState();
}

class _QuestoesAdminPageState extends State<QuestoesAdminPage> {
  final List<Map<String, dynamic>> questoes = [
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
    {
      'nivel': 'Difícil',
      'pergunta': 'Como funciona um ESP32?',
      'opcoes': [
        'Executa firmware e integra Wi-Fi/Bluetooth em um SoC',
        'Opera exclusivamente como sensor analógico',
        'Funciona apenas como interface de entrada',
        'Atua somente como conversor de sinal',
      ],
      'correta': 3,
    },
    {
      'nivel': 'Difícil',
      'pergunta': 'O que é protocolo MQTT?',
      'opcoes': [
        'Protocolo leve baseado em publish/subscribe',
        'Barramento físico de alta velocidade',
        'Sistema de arquivos distribuído',
        'Interface gráfica embarcada',
      ],
      'correta': 0,
    },
    {
      'nivel': 'Difícil',
      'pergunta': 'Diferença entre Arduino e Raspberry Pi?',
      'opcoes': [
        'Arduino é microcontrolador, Raspberry Pi é SBC',
        'Arduino roda Linux nativamente',
        'Raspberry Pi não executa código',
        'Ambos são apenas sensores programáveis',
      ],
      'correta': 1,
    },
    {
      'nivel': 'Fácil',
      'pergunta': 'O que é um sensor DHT11?',
      'opcoes': [
        'Sensor digital de temperatura e umidade',
        'Sensor de pressão atmosférica',
        'Módulo de comunicação Wi-Fi',
        'Conversor analógico-digital',
      ],
      'correta': 2,
    },
    {
      'nivel': 'Fácil',
      'pergunta': 'O que mede um sensor de temperatura?',
      'opcoes': [
        'Variação térmica do ambiente',
        'Intensidade de corrente elétrica',
        'Pressão do ar',
        'Velocidade angular',
      ],
      'correta': 3,
    },
    {
      'nivel': 'Fácil',
      'pergunta': 'O que é um atuador?',
      'opcoes': [
        'Dispositivo que converte sinal em ação física',
        'Sensor de entrada analógica',
        'Fonte de alimentação regulada',
        'Elemento de armazenamento de dados',
      ],
      'correta': 0,
    },
    {
      'nivel': 'Fácil',
      'pergunta': 'Para que serve um relé?',
      'opcoes': [
        'Comutar circuitos usando sinal de controle',
        'Medir resistência elétrica',
        'Armazenar energia elétrica',
        'Converter sinais digitais',
      ],
      'correta': 1,
    },
    {
      'nivel': 'Fácil',
      'pergunta': 'O que é corrente elétrica?',
      'opcoes': [
        'Fluxo de cargas elétricas em um condutor',
        'Diferença de potencial entre dois pontos',
        'Energia armazenada em campo elétrico',
        'Resistência ao fluxo de elétrons',
      ],
      'correta': 2,
    },
    {
      'nivel': 'Médio',
      'pergunta': 'O que é um microcontrolador?',
      'opcoes': [
        'Sistema integrado com CPU, memória e I/O',
        'Dispositivo exclusivo para armazenamento',
        'Sensor digital programável',
        'Fonte de alimentação inteligente',
      ],
      'correta': 3,
    },
    {
      'nivel': 'Médio',
      'pergunta': 'O que é um circuito integrado?',
      'opcoes': [
        'Conjunto de componentes eletrônicos em um chip',
        'Placa com múltiplos sensores discretos',
        'Sistema operacional embarcado',
        'Barramento de comunicação serial',
      ],
      'correta': 0,
    },
    {
      'nivel': 'Médio',
      'pergunta': 'Como funciona um sensor de gás?',
      'opcoes': [
        'Altera propriedades elétricas na presença de gases',
        'Mede variação de luz refletida',
        'Detecta campo magnético',
        'Converte som em sinal digital',
      ],
      'correta': 1,
    },
    {
      'nivel': 'Médio',
      'pergunta': 'O que é comunicação serial?',
      'opcoes': [
        'Transmissão sequencial de bits por um canal',
        'Envio simultâneo de múltiplos bits',
        'Conversão de sinais analógicos',
        'Processamento paralelo de dados',
      ],
      'correta': 2,
    },
    {
      'nivel': 'Médio',
      'pergunta': 'O que é I2C?',
      'opcoes': [
        'Barramento serial síncrono com múltiplos dispositivos',
        'Protocolo de rede sem fio',
        'Interface gráfica embarcada',
        'Conversor digital dedicado',
      ],
      'correta': 3,
    },
    {
      'nivel': 'Médio',
      'pergunta': 'O que é SPI?',
      'opcoes': [
        'Interface serial síncrona de alta velocidade',
        'Protocolo analógico de comunicação',
        'Sistema de arquivos embarcado',
        'Sensor digital programável',
      ],
      'correta': 0,
    },
    {
      'nivel': 'Médio',
      'pergunta': 'O que é tensão elétrica?',
      'opcoes': [
        'Diferença de potencial entre dois pontos',
        'Fluxo de elétrons em movimento',
        'Resistência de um material',
        'Potência dissipada em circuito',
      ],
      'correta': 1,
    },
    {
      'nivel': 'Difícil',
      'pergunta': 'O que é ADC (Conversor Analógico-Digital)?',
      'opcoes': [
        'Transforma sinal contínuo em representação digital',
        'Amplifica sinais elétricos',
        'Armazena dados binários',
        'Gera sinais analógicos',
      ],
      'correta': 2,
    },
    {
      'nivel': 'Difícil',
      'pergunta': 'O que é DAC (Conversor Digital-Analógico)?',
      'opcoes': [
        'Converte dados digitais em sinal analógico',
        'Filtra ruído de sinais digitais',
        'Armazena sinais contínuos',
        'Amplifica sinais digitais',
      ],
      'correta': 3,
    },
    {
      'nivel': 'Difícil',
      'pergunta': 'O que é interrupção em microcontroladores?',
      'opcoes': [
        'Evento que desvia a execução para uma rotina específica',
        'Erro crítico de hardware',
        'Reinicialização automática do sistema',
        'Atraso intencional no código',
      ],
      'correta': 0,
    },
    {
      'nivel': 'Difícil',
      'pergunta': 'O que é debounce em botões?',
      'opcoes': [
        'Tratamento de ruído em sinais mecânicos',
        'Aumento de frequência de clock',
        'Conversão de sinal analógico',
        'Sincronização de barramento',
      ],
      'correta': 1,
    },
    {
      'nivel': 'Difícil',
      'pergunta': 'O que é consumo de corrente em stand-by?',
      'opcoes': [
        'Corrente consumida em estado inativo',
        'Consumo máximo sob carga',
        'Perda de energia em transmissão',
        'Corrente de curto-circuito',
      ],
      'correta': 2,
    },
    {
      'nivel': 'Difícil',
      'pergunta': 'O que é watchdog timer?',
      'opcoes': [
        'Mecanismo que reinicia o sistema em falhas',
        'Contador de eventos externos',
        'Sensor de tempo real',
        'Clock de alta precisão',
      ],
      'correta': 3,
    },
    {
      'nivel': 'Difícil',
      'pergunta': 'O que é overclock?',
      'opcoes': [
        'Operar acima da frequência nominal',
        'Reduzir consumo energético',
        'Desativar núcleos do processador',
        'Aumentar memória cache',
      ],
      'correta': 0,
    },
    {
      'nivel': 'Difícil',
      'pergunta': 'O que é firmware?',
      'opcoes': [
        'Software embarcado em hardware',
        'Sistema operacional completo',
        'Aplicação de usuário',
        'Driver de dispositivo externo',
      ],
      'correta': 1,
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

  void _excluir(int id) {
    setState(() => questoes.removeWhere((q) => q['id'] == id));
  }

  void _abrirFormulario({Map<String, dynamic>? questao}) {
    final descCtrl = TextEditingController(text: questao?['descricao'] ?? '');

    final altCtrls = List.generate(
      4,
      (i) => TextEditingController(
        text: questao != null
            ? (questao['alternativas']?[i]?['texto'] ?? '')
            : '',
      ),
    );

    String nivel = questao?['nivel'] ?? 'Fácil';

    int correta = 0;

    if (questao != null) {
      final idx = questao['alternativas'].indexWhere(
        (a) => a['correta'] == true,
      );
      correta = idx >= 0 ? idx : 0;
    }

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: AppColors.bgCardAlt,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (_) => StatefulBuilder(
        builder: (ctx, setModal) => Padding(
          padding: EdgeInsets.only(
            left: 20,
            right: 20,
            top: 20,
            bottom: MediaQuery.of(context).viewInsets.bottom + 20,
          ),
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  questao == null ? "Nova questão" : "Editar questão",
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),

                const SizedBox(height: 16),

                TextField(
                  controller: descCtrl,
                  maxLines: 3,
                  decoration: const InputDecoration(labelText: 'Pergunta'),
                ),

                const SizedBox(height: 16),

                Row(
                  children: ['Fácil', 'Médio', 'Difícil']
                      .map(
                        (n) => GestureDetector(
                          onTap: () => setModal(() => nivel = n),
                          child: Container(
                            margin: const EdgeInsets.only(right: 8),
                            padding: const EdgeInsets.symmetric(
                              horizontal: 12,
                              vertical: 6,
                            ),
                            decoration: BoxDecoration(
                              borderRadius: BorderRadius.circular(20),
                              border: Border.all(
                                color: nivel == n
                                    ? _nivelCor(n)
                                    : AppColors.border,
                              ),
                            ),
                            child: Text(n),
                          ),
                        ),
                      )
                      .toList(),
                ),

                const SizedBox(height: 16),

                ...List.generate(4, (i) {
                  return Row(
                    children: [
                      Radio<int>(
                        value: i,
                        groupValue: correta,
                        onChanged: (v) => setModal(() => correta = v!),
                      ),
                      Expanded(
                        child: TextField(
                          controller: altCtrls[i],
                          decoration: InputDecoration(
                            hintText:
                                'Alternativa ${String.fromCharCode(65 + i)}',
                          ),
                        ),
                      ),
                    ],
                  );
                }),

                const SizedBox(height: 16),

                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () {
                      if (descCtrl.text.isEmpty ||
                          altCtrls.any((c) => c.text.isEmpty)) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(
                            content: Text('Preencha todos os campos'),
                          ),
                        );
                        return;
                      }

                      final nova = {
                        'id': questao?['id'] ?? questoes.length + 1,
                        'descricao': descCtrl.text,
                        'nivel': nivel,
                        'alternativas': List.generate(
                          4,
                          (i) => {
                            'texto': altCtrls[i].text,
                            'correta': i == correta,
                          },
                        ),
                      };

                      setState(() {
                        if (questao == null) {
                          questoes.add(nova);
                        } else {
                          final index = questoes.indexWhere(
                            (q) => q['id'] == questao['id'],
                          );
                          if (index != -1) {
                            questoes[index] = nova;
                          }
                        }
                      });

                      Navigator.pop(context);
                    },
                    child: Text(questao == null ? 'Salvar' : 'Atualizar'),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _questaoCard(Map<String, dynamic> q) {
    final nivel = q['nivel'] as String;
    final alts = (q['alternativas'] ?? []) as List;

    if (alts.isEmpty) return const SizedBox();

    final corretaIdx = alts.indexWhere((a) => a['correta'] == true);

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.bgCardAlt,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          /// HEADER (igual sensores)
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: AppColors.primary.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: const Icon(
                  Icons.quiz_outlined,
                  color: AppColors.primary,
                  size: 20,
                ),
              ),
              const SizedBox(width: 12),

              /// título + id
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      q['descricao'],
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 14,
                      ),
                    ),
                    Text(
                      "#${q['id']}",
                      style: const TextStyle(
                        color: AppColors.textMuted,
                        fontSize: 11,
                      ),
                    ),
                  ],
                ),
              ),

              /// badge dificuldade
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 5,
                ),
                decoration: BoxDecoration(
                  color: _nivelCor(nivel).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Text(
                  nivel,
                  style: TextStyle(
                    color: _nivelCor(nivel),
                    fontSize: 11,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),

          const SizedBox(height: 12),
          const Divider(height: 0),
          const SizedBox(height: 10),

          /// ALTERNATIVAS (estilo chips/lista limpa)
          Column(
            children: alts.asMap().entries.map((e) {
              final isCorreta = e.key == corretaIdx;

              return Container(
                margin: const EdgeInsets.only(bottom: 6),
                padding: const EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 8,
                ),
                decoration: BoxDecoration(
                  color: isCorreta
                      ? AppColors.success.withOpacity(0.08)
                      : Colors.transparent,
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Row(
                  children: [
                    Icon(
                      isCorreta
                          ? Icons.check_circle
                          : Icons.radio_button_unchecked,
                      size: 14,
                      color: isCorreta ? AppColors.success : AppColors.border,
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        e.value['texto'],
                        style: TextStyle(
                          fontSize: 12,
                          color: isCorreta
                              ? AppColors.success
                              : AppColors.textMuted,
                          fontWeight: isCorreta
                              ? FontWeight.w600
                              : FontWeight.normal,
                        ),
                      ),
                    ),
                  ],
                ),
              );
            }).toList(),
          ),

          const SizedBox(height: 12),

          /// AÇÕES (igual sensores)
          Row(
            children: [
              Expanded(
                child: OutlinedButton.icon(
                  onPressed: () => _abrirFormulario(questao: q),
                  icon: const Icon(Icons.edit_outlined, size: 15),
                  label: const Text('Editar', style: TextStyle(fontSize: 13)),
                  style: OutlinedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 9),
                  ),
                ),
              ),
              const SizedBox(width: 8),
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () => _excluir(q['id']),
                  icon: const Icon(Icons.delete_outline, size: 15),
                  label: const Text('Excluir', style: TextStyle(fontSize: 13)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.danger,
                    padding: const EdgeInsets.symmetric(vertical: 9),
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Questões"),
        actions: [
          IconButton(
            onPressed: () => _abrirFormulario(),
            icon: const Icon(Icons.add),
          ),
        ],
      ),
      body: questoes.isEmpty
          ? const Center(child: Text("Nenhuma questão"))
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: questoes.length,
              itemBuilder: (_, i) => _questaoCard(questoes[i]),
            ),
    );
  }
}
