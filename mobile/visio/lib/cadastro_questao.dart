import 'package:flutter/material.dart';
import 'main.dart';

class QuestoesAdminPage extends StatefulWidget {
  const QuestoesAdminPage({super.key});

  @override
  State<QuestoesAdminPage> createState() => _QuestoesAdminPageState();
}

class _QuestoesAdminPageState extends State<QuestoesAdminPage> {
  List<Map<String, dynamic>> questoes = [
    {
      'id': 1,
      'descricao': 'O que é um sensor de temperatura?',
      'nivel': 'Fácil',
      'alternativas': [
        {'texto': 'Mede calor ou frio', 'correta': true},
        {'texto': 'Mede intensidade de luz', 'correta': false},
        {'texto': 'Detecta movimento', 'correta': false},
        {'texto': 'Mede pressão', 'correta': false},
      ],
    },
    {
      'id': 2,
      'descricao': 'Qual sensor usa ondas sonoras para medir distância?',
      'nivel': 'Médio',
      'alternativas': [
        {'texto': 'Sensor PIR', 'correta': false},
        {'texto': 'Sensor ultrassônico', 'correta': true},
        {'texto': 'Sensor de luz LDR', 'correta': false},
        {'texto': 'Sensor de umidade', 'correta': false},
      ],
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
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        backgroundColor: AppColors.bgCardAlt,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Excluir questão'),
        content: const Text('Tem certeza que deseja excluir esta questão?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancelar'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () {
              setState(() => questoes.removeWhere((q) => q['id'] == id));
              Navigator.pop(context);
              ScaffoldMessenger.of(
                context,
              ).showSnackBar(const SnackBar(content: Text('Questão excluída')));
            },
            child: const Text('Excluir'),
          ),
        ],
      ),
    );
  }

  void _novaQuestao() {
    final descCtrl = TextEditingController();
    final List<TextEditingController> altCtrls = List.generate(
      4,
      (_) => TextEditingController(),
    );
    String nivel = 'Fácil';
    int correta = 0;

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
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Center(
                  child: Container(
                    width: 40,
                    height: 4,
                    decoration: BoxDecoration(
                      color: AppColors.border,
                      borderRadius: BorderRadius.circular(2),
                    ),
                  ),
                ),
                const SizedBox(height: 18),
                const Text(
                  'Nova questão',
                  style: TextStyle(fontSize: 17, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 18),
                TextField(
                  controller: descCtrl,
                  maxLines: 3,
                  decoration: const InputDecoration(
                    labelText: 'Pergunta',
                    alignLabelWithHint: true,
                  ),
                ),
                const SizedBox(height: 14),
                // Nível
                const Text(
                  'Nível de dificuldade',
                  style: TextStyle(fontSize: 12, color: AppColors.textMuted),
                ),
                const SizedBox(height: 8),
                Row(
                  children: ['Fácil', 'Médio', 'Difícil']
                      .map(
                        (n) => Padding(
                          padding: const EdgeInsets.only(right: 8),
                          child: GestureDetector(
                            onTap: () => setModal(() => nivel = n),
                            child: Container(
                              padding: const EdgeInsets.symmetric(
                                horizontal: 16,
                                vertical: 8,
                              ),
                              decoration: BoxDecoration(
                                color: nivel == n
                                    ? _nivelCor(n).withOpacity(0.15)
                                    : AppColors.bgCardAlt,
                                borderRadius: BorderRadius.circular(20),
                                border: Border.all(
                                  color: nivel == n
                                      ? _nivelCor(n)
                                      : AppColors.border,
                                  width: nivel == n ? 1.5 : 1,
                                ),
                              ),
                              child: Text(
                                n,
                                style: TextStyle(
                                  color: nivel == n
                                      ? _nivelCor(n)
                                      : AppColors.textMuted,
                                  fontWeight: nivel == n
                                      ? FontWeight.bold
                                      : FontWeight.normal,
                                  fontSize: 13,
                                ),
                              ),
                            ),
                          ),
                        ),
                      )
                      .toList(),
                ),
                const SizedBox(height: 16),
                const Text(
                  'Alternativas (marque a correta)',
                  style: TextStyle(fontSize: 12, color: AppColors.textMuted),
                ),
                const SizedBox(height: 8),
                ...List.generate(
                  4,
                  (i) => Padding(
                    padding: const EdgeInsets.only(bottom: 10),
                    child: Row(
                      children: [
                        GestureDetector(
                          onTap: () => setModal(() => correta = i),
                          child: Container(
                            width: 28,
                            height: 28,
                            decoration: BoxDecoration(
                              color: correta == i
                                  ? AppColors.success.withOpacity(0.1)
                                  : Colors.transparent,
                              shape: BoxShape.circle,
                              border: Border.all(
                                color: correta == i
                                    ? AppColors.success
                                    : AppColors.border,
                                width: 1.5,
                              ),
                            ),
                            child: correta == i
                                ? const Icon(
                                    Icons.check,
                                    size: 16,
                                    color: AppColors.success,
                                  )
                                : Center(
                                    child: Text(
                                      String.fromCharCode(65 + i),
                                      style: const TextStyle(
                                        fontSize: 12,
                                        color: AppColors.textMuted,
                                      ),
                                    ),
                                  ),
                          ),
                        ),
                        const SizedBox(width: 10),
                        Expanded(
                          child: TextField(
                            controller: altCtrls[i],
                            decoration: InputDecoration(
                              hintText:
                                  'Alternativa ${String.fromCharCode(65 + i)}',
                              contentPadding: const EdgeInsets.symmetric(
                                horizontal: 12,
                                vertical: 10,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 8),
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
                      setState(() {
                        questoes.add({
                          'id': questoes.length + 1,
                          'descricao': descCtrl.text,
                          'nivel': nivel,
                          'alternativas': List.generate(
                            4,
                            (i) => {
                              'texto': altCtrls[i].text,
                              'correta': i == correta,
                            },
                          ),
                        });
                      });
                      Navigator.pop(context);
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Questão adicionada!')),
                      );
                    },
                    child: const Text('Salvar questão'),
                  ),
                ),
                const SizedBox(height: 8),
              ],
            ),
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false,
        title: const Text('Questões'),
        actions: [
          TextButton.icon(
            onPressed: _novaQuestao,
            icon: const Icon(Icons.add, size: 18, color: AppColors.primary),
            label: const Text(
              'Adicionar',
              style: TextStyle(color: AppColors.primary),
            ),
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: SafeArea(
        child: questoes.isEmpty
            ? const Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      Icons.quiz_outlined,
                      size: 48,
                      color: AppColors.textMuted,
                    ),
                    SizedBox(height: 12),
                    Text(
                      'Nenhuma questão cadastrada',
                      style: TextStyle(color: AppColors.textMuted),
                    ),
                  ],
                ),
              )
            : ListView.separated(
                padding: const EdgeInsets.all(16),
                itemCount: questoes.length,
                separatorBuilder: (_, __) => const SizedBox(height: 12),
                itemBuilder: (_, i) => _questaoCard(questoes[i]),
              ),
      ),
    );
  }

  Widget _questaoCard(Map<String, dynamic> q) {
    final nivel = q['nivel'] as String;
    final alts = q['alternativas'] as List;
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
          Row(
            children: [
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 4,
                ),
                decoration: BoxDecoration(
                  color: _nivelCor(nivel).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Text(
                  nivel,
                  style: TextStyle(
                    fontSize: 11,
                    color: _nivelCor(nivel),
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              const SizedBox(width: 8),
              Text(
                '#${q['id']}',
                style: const TextStyle(
                  color: AppColors.textMuted,
                  fontSize: 12,
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          Text(
            q['descricao'] as String,
            style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 14),
          ),
          const SizedBox(height: 10),
          ...alts.asMap().entries.map((e) {
            final isCorreta = e.key == corretaIdx;
            return Padding(
              padding: const EdgeInsets.only(bottom: 4),
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
                      e.value['texto'] as String,
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
          }),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: OutlinedButton.icon(
                  onPressed: () {},
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
                  onPressed: () => _excluir(q['id'] as int),
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
}
