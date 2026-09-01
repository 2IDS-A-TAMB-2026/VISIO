import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'services/api_client.dart';

/// Gestão de perguntas do quiz (admin) — CORRIGIDO/INTEGRADO: antes eram 23
/// perguntas fixas no código (nem chegavam a aparecer na tela sem uma
/// conversão de formato local). Agora usa `GET /admin/perguntas`,
/// `POST admin/pergunta/inserir`, `POST admin/pergunta/atualizar/:id` e
/// `POST admin/pergunta/excluir/:id` (`AdminController`), exatamente como o
/// painel administrativo do site.
class QuestoesAdminPage extends StatefulWidget {
  const QuestoesAdminPage({super.key});

  @override
  State<QuestoesAdminPage> createState() => _QuestoesAdminPageState();
}

class _QuestoesAdminPageState extends State<QuestoesAdminPage> {
  bool _carregando = true;
  String? _erro;
  List<Map<String, dynamic>> questoes = [];

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
      final corpo = await ApiClient.get('admin/perguntas') as List;
      if (!mounted) return;
      setState(() {
        questoes = corpo.cast<Map<String, dynamic>>();
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

  Future<void> _excluir(int id) async {
    final confirmar = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        backgroundColor: context.cardBg,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Excluir pergunta'),
        content: Text(
          'Tem certeza? Isso também remove as alternativas dessa pergunta.',
          style: TextStyle(color: context.textMuted),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context, false), child: const Text('Cancelar')),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Excluir'),
          ),
        ],
      ),
    );
    if (confirmar != true) return;

    try {
      await ApiClient.postForm('admin/pergunta/excluir/$id', {});
      if (!mounted) return;
      setState(() => questoes.removeWhere((q) => q['ID_PERGUNTA'] == id));
    } on ApiException catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.mensagem)));
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Erro de conexão: $e')));
    }
  }

  void _abrirFormulario({Map<String, dynamic>? questao}) {
    final descCtrl = TextEditingController(text: questao?['DESCRICAO'] as String? ?? '');
    final alternativasExistentes = (questao?['alternativas'] as List?)?.cast<Map<String, dynamic>>();

    final altCtrls = List.generate(
      4,
      (i) => TextEditingController(
        text: alternativasExistentes != null && i < alternativasExistentes.length
            ? (alternativasExistentes[i]['DESCRICAO'] as String? ?? '')
            : '',
      ),
    );

    String nivel = questao?['NIVEL_DIFICULDADE'] as String? ?? 'Fácil';

    int correta = 0;
    if (alternativasExistentes != null) {
      final idx = alternativasExistentes.indexWhere((a) => (a['IS_CORRETA'] as num?)?.toInt() == 1);
      correta = idx >= 0 ? idx : 0;
    }

    bool salvando = false;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: context.cardBg,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (sheetContext) => StatefulBuilder(
        builder: (ctx, setModal) => Padding(
          padding: EdgeInsets.only(
            left: 20,
            right: 20,
            top: 20,
            bottom: MediaQuery.of(ctx).viewInsets.bottom + 20,
          ),
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  questao == null ? "Nova questão" : "Editar questão",
                  style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
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
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                              borderRadius: BorderRadius.circular(20),
                              border: Border.all(color: nivel == n ? _nivelCor(n) : ctx.borderColor),
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
                          decoration: InputDecoration(hintText: 'Alternativa ${String.fromCharCode(65 + i)}'),
                        ),
                      ),
                    ],
                  );
                }),

                const SizedBox(height: 16),

                SizedBox(
                  width: double.infinity,
                  child: salvando
                      ? const Center(child: CircularProgressIndicator())
                      : ElevatedButton(
                          onPressed: () async {
                            if (descCtrl.text.isEmpty || altCtrls.any((c) => c.text.isEmpty)) {
                              ScaffoldMessenger.of(
                                ctx,
                              ).showSnackBar(const SnackBar(content: Text('Preencha todos os campos')));
                              return;
                            }

                            setModal(() => salvando = true);

                            try {
                              Map<String, dynamic> corpo;

                              if (questao == null) {
                                // NOVA pergunta: backend espera alt_a..alt_d
                                // fixos + 'correta' como letra (a/b/c/d).
                                corpo = await ApiClient.postForm('admin/pergunta/inserir', {
                                  'descricao': descCtrl.text.trim(),
                                  'nivel': nivel,
                                  'alt_a': altCtrls[0].text.trim(),
                                  'alt_b': altCtrls[1].text.trim(),
                                  'alt_c': altCtrls[2].text.trim(),
                                  'alt_d': altCtrls[3].text.trim(),
                                  'correta': String.fromCharCode(97 + correta), // 'a'..'d'
                                }) as Map<String, dynamic>;
                              } else {
                                // EDITAR pergunta: backend espera arrays
                                // id_alternativa[]/alternativa[] em paralelo
                                // + 'correta' como o ID da alternativa certa.
                                final idsExistentes = alternativasExistentes!
                                    .map((a) => (a['ID_ALTERNATIVA'] as num).toString())
                                    .toList();

                                corpo = await ApiClient.postForm('admin/pergunta/atualizar/${questao['ID_PERGUNTA']}', {
                                  'descricao': descCtrl.text.trim(),
                                  'nivel': nivel,
                                  'id_alternativa[]': idsExistentes,
                                  'alternativa[]': altCtrls.map((c) => c.text.trim()).toList(),
                                  'correta': idsExistentes[correta],
                                }) as Map<String, dynamic>;
                              }

                              final perguntaAtualizada = corpo['pergunta'] as Map<String, dynamic>?;

                              if (!mounted) return;
                              setState(() {
                                if (perguntaAtualizada != null) {
                                  if (questao == null) {
                                    questoes.add(perguntaAtualizada);
                                  } else {
                                    final index = questoes.indexWhere(
                                      (q) => q['ID_PERGUNTA'] == questao['ID_PERGUNTA'],
                                    );
                                    if (index != -1) questoes[index] = perguntaAtualizada;
                                  }
                                }
                              });

                              if (!sheetContext.mounted) return;
                              Navigator.pop(sheetContext);
                            } on ApiException catch (e) {
                              setModal(() => salvando = false);
                              if (!ctx.mounted) return;
                              ScaffoldMessenger.of(ctx).showSnackBar(SnackBar(content: Text(e.mensagem)));
                            } catch (e) {
                              setModal(() => salvando = false);
                              if (!ctx.mounted) return;
                              ScaffoldMessenger.of(ctx).showSnackBar(SnackBar(content: Text('Erro de conexão: $e')));
                            }
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

  Widget _questaoCard(BuildContext context, Map<String, dynamic> q) {
    final nivel = q['NIVEL_DIFICULDADE'] as String? ?? 'Médio';
    final alts = (q['alternativas'] as List?) ?? [];

    if (alts.isEmpty) return const SizedBox();

    final corretaIdx = alts.indexWhere((a) => (a['IS_CORRETA'] as num?)?.toInt() == 1);

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: context.cardBg,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: context.borderColor),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: AppColors.primary.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: const Icon(Icons.quiz_outlined, color: AppColors.primary, size: 20),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      q['DESCRICAO'] as String? ?? '',
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                    ),
                    Text(
                      "#${q['ID_PERGUNTA']}",
                      style: TextStyle(color: context.textMuted, fontSize: 11),
                    ),
                  ],
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                decoration: BoxDecoration(
                  color: _nivelCor(nivel).withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Text(
                  nivel,
                  style: TextStyle(color: _nivelCor(nivel), fontSize: 11, fontWeight: FontWeight.bold),
                ),
              ),
            ],
          ),

          const SizedBox(height: 12),
          const Divider(height: 0),
          const SizedBox(height: 10),

          Column(
            children: alts.asMap().entries.map((e) {
              final isCorreta = e.key == corretaIdx;
              return Container(
                margin: const EdgeInsets.only(bottom: 6),
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
                decoration: BoxDecoration(
                  color: isCorreta ? AppColors.success.withValues(alpha: 0.08) : Colors.transparent,
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Row(
                  children: [
                    Icon(
                      isCorreta ? Icons.check_circle : Icons.radio_button_unchecked,
                      size: 14,
                      color: isCorreta ? AppColors.success : context.borderColor,
                    ),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        e.value['DESCRICAO'] as String? ?? '',
                        style: TextStyle(
                          fontSize: 12,
                          color: isCorreta ? AppColors.success : context.textMuted,
                          fontWeight: isCorreta ? FontWeight.w600 : FontWeight.normal,
                        ),
                      ),
                    ),
                  ],
                ),
              );
            }).toList(),
          ),

          const SizedBox(height: 12),

          Row(
            children: [
              Expanded(
                child: OutlinedButton.icon(
                  onPressed: () => _abrirFormulario(questao: q),
                  icon: const Icon(Icons.edit_outlined, size: 15),
                  label: const Text('Editar', style: TextStyle(fontSize: 13)),
                  style: OutlinedButton.styleFrom(padding: const EdgeInsets.symmetric(vertical: 9)),
                ),
              ),
              const SizedBox(width: 8),
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () => _excluir(q['ID_PERGUNTA'] as int),
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
          IconButton(onPressed: () => _abrirFormulario(), icon: const Icon(Icons.add)),
        ],
      ),
      body: _carregando
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
          : questoes.isEmpty
          ? const Center(child: Text("Nenhuma questão cadastrada"))
          : RefreshIndicator(
              onRefresh: _carregar,
              child: ListView.builder(
                padding: const EdgeInsets.all(16),
                itemCount: questoes.length,
                itemBuilder: (_, i) => _questaoCard(context, questoes[i]),
              ),
            ),
    );
  }
}
