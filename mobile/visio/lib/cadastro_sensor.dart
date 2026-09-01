import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'appcolor.dart';
import 'services/api_client.dart';
import 'services/api_config.dart';

class SensoresAdminPage extends StatefulWidget {
  const SensoresAdminPage({super.key});

  @override
  State<SensoresAdminPage> createState() => _SensoresAdminPageState();
}

class _SensoresAdminPageState extends State<SensoresAdminPage> {
  bool _carregando = true;
  String? _erro;
  List<Map<String, dynamic>> sensores = [];

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
      final corpo = await ApiClient.get('admin/sensores') as List;
      if (!mounted) return;
      setState(() {
        sensores = corpo.cast<Map<String, dynamic>>();
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

  Future<void> _excluir(int id) async {
    final confirmar = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        backgroundColor: context.cardBg,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Excluir sensor'),
        content: const Text(
          'Tem certeza que deseja excluir este sensor do catálogo?',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Cancelar'),
          ),
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
      await ApiClient.postForm('admin/sensor/excluir/$id', {});
      if (!mounted) return;
      setState(() => sensores.removeWhere((s) => s['ID_SENSOR'] == id));
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text('Sensor excluído')));
    } on ApiException catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text(e.mensagem)));
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Erro de conexão: $e')));
    }
  }

  void _abrirModal({Map<String, dynamic>? sensor}) {
    final isEdit = sensor != null;

    final nomeCtrl = TextEditingController(
      text: sensor?['NOME'] as String? ?? '',
    );
    final descricaoCtrl = TextEditingController(
      text: sensor?['DESCRICAO'] as String? ?? '',
    );
    final circuitoCtrl = TextEditingController(
      text: sensor?['CIRCUITO'] as String? ?? '',
    );
    final fotoAtual = sensor?['FOTO'] as String?;

    final picker = ImagePicker();
    XFile? novaFoto;
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
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const SizedBox(height: 18),
                Text(
                  isEdit ? 'Editar sensor' : 'Cadastrar sensor',
                  style: const TextStyle(
                    fontSize: 17,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 18),

                Center(
                  child: GestureDetector(
                    onTap: () async {
                      final foto = await picker.pickImage(
                        source: ImageSource.gallery,
                        maxWidth: 1024,
                      );
                      if (foto != null) setModal(() => novaFoto = foto);
                    },
                    child: Container(
                      width: 120,
                      height: 90,
                      decoration: BoxDecoration(
                        color: AppColors.primary.withValues(alpha: 0.08),
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: ctx.borderColor),
                        image: novaFoto != null
                            ? null // FutureBuilder abaixo cuida da prévia local
                            : (fotoAtual != null && fotoAtual.isNotEmpty)
                            ? DecorationImage(
                                image: NetworkImage(
                                  '${ApiConfig.baseUrl}/$fotoAtual',
                                ),
                                fit: BoxFit.cover,
                              )
                            : null,
                      ),
                      child: novaFoto != null
                          ? ClipRRect(
                              borderRadius: BorderRadius.circular(12),
                              child: FutureBuilder(
                                future: novaFoto!.readAsBytes(),
                                builder: (_, snap) => snap.hasData
                                    ? Image.memory(
                                        snap.data!,
                                        fit: BoxFit.cover,
                                        width: 120,
                                        height: 90,
                                      )
                                    : const Center(
                                        child: CircularProgressIndicator(),
                                      ),
                              ),
                            )
                          : (fotoAtual == null || fotoAtual.isEmpty)
                          ? Icon(
                              Icons.add_photo_alternate_outlined,
                              color: AppColors.primary.withValues(alpha: 0.6),
                            )
                          : null,
                    ),
                  ),
                ),
                const SizedBox(height: 18),

                _modalField(nomeCtrl, 'Nome do sensor', Icons.sensors_outlined),
                const SizedBox(height: 12),
                _modalField(
                  descricaoCtrl,
                  'Descrição',
                  Icons.description_outlined,
                  maxLines: 3,
                ),
                const SizedBox(height: 12),
                _modalField(
                  circuitoCtrl,
                  'Circuito (esquema/montagem)',
                  Icons.developer_board_outlined,
                  maxLines: 3,
                ),

                const SizedBox(height: 20),

                SizedBox(
                  width: double.infinity,
                  child: salvando
                      ? const Center(child: CircularProgressIndicator())
                      : ElevatedButton(
                          onPressed: () async {
                            if (nomeCtrl.text.isEmpty) return;
                            setModal(() => salvando = true);

                            final campos = {
                              'nome': nomeCtrl.text.trim(),
                              'descricao': descricaoCtrl.text.trim(),
                              'circuito': circuitoCtrl.text.trim(),
                            };

                            try {
                              final corpo = isEdit
                                  ? await ApiClient.postMultipart(
                                          'admin/sensor/atualizar/${sensor['ID_SENSOR']}',
                                          campos,
                                          foto: novaFoto,
                                        )
                                        as Map<String, dynamic>
                                  : await ApiClient.postMultipart(
                                          'admin/sensor/inserir',
                                          campos,
                                          foto: novaFoto,
                                        )
                                        as Map<String, dynamic>;

                              final sensorAtualizado =
                                  corpo['sensor'] as Map<String, dynamic>?;

                              if (!mounted) return;
                              setState(() {
                                if (sensorAtualizado != null) {
                                  if (isEdit) {
                                    final index = sensores.indexWhere(
                                      (e) =>
                                          e['ID_SENSOR'] == sensor['ID_SENSOR'],
                                    );
                                    if (index != -1) {
                                      sensores[index] = sensorAtualizado;
                                    }
                                  } else {
                                    sensores.add(sensorAtualizado);
                                  }
                                }
                              });

                              if (!sheetContext.mounted) return;
                              Navigator.pop(sheetContext);
                            } on ApiException catch (e) {
                              setModal(() => salvando = false);
                              if (!ctx.mounted) return;
                              ScaffoldMessenger.of(ctx).showSnackBar(
                                SnackBar(content: Text(e.mensagem)),
                              );
                            } catch (e) {
                              setModal(() => salvando = false);
                              if (!ctx.mounted) return;
                              ScaffoldMessenger.of(ctx).showSnackBar(
                                SnackBar(content: Text('Erro de conexão: $e')),
                              );
                            }
                          },
                          child: Text(
                            isEdit ? 'Salvar alterações' : 'Cadastrar',
                          ),
                        ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _modalField(
    TextEditingController ctrl,
    String label,
    IconData icon, {
    int maxLines = 1,
  }) {
    return TextField(
      controller: ctrl,
      maxLines: maxLines,
      decoration: InputDecoration(
        labelText: label,
        prefixIcon: Icon(icon, size: 18),
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 12,
          vertical: 12,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false,
        title: const Text('Sensores ADM'),
        actions: [
          TextButton.icon(
            onPressed: () => _abrirModal(),
            icon: const Icon(Icons.add, size: 18, color: AppColors.primary),
            label: const Text(
              'Novo',
              style: TextStyle(color: AppColors.primary),
            ),
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
                      const Icon(
                        Icons.error_outline,
                        size: 48,
                        color: AppColors.danger,
                      ),
                      const SizedBox(height: 12),
                      Text(
                        _erro!,
                        textAlign: TextAlign.center,
                        style: TextStyle(color: context.textMuted),
                      ),
                      const SizedBox(height: 20),
                      ElevatedButton(
                        onPressed: _carregar,
                        child: const Text('Tentar novamente'),
                      ),
                    ],
                  ),
                ),
              )
            : sensores.isEmpty
            ? Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      Icons.memory_outlined,
                      size: 48,
                      color: context.textMuted,
                    ),
                    const SizedBox(height: 12),
                    Text(
                      'Nenhum sensor cadastrado',
                      style: TextStyle(color: context.textMuted),
                    ),
                  ],
                ),
              )
            : RefreshIndicator(
                onRefresh: _carregar,
                child: ListView.separated(
                  padding: const EdgeInsets.all(16),
                  itemCount: sensores.length,
                  separatorBuilder: (_, _) => const SizedBox(height: 12),
                  itemBuilder: (_, i) => _sensorCard(context, sensores[i]),
                ),
              ),
      ),
    );
  }

  Widget _sensorCard(BuildContext context, Map<String, dynamic> s) {
    final nome = s['NOME'] as String? ?? '';
    final descricao = s['DESCRICAO'] as String? ?? '';
    final foto = s['FOTO'] as String?;

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
              ClipRRect(
                borderRadius: BorderRadius.circular(10),
                child: (foto != null && foto.isNotEmpty)
                    ? Image.network(
                        '${ApiConfig.baseUrl}/$foto',
                        width: 44,
                        height: 44,
                        fit: BoxFit.cover,
                        errorBuilder: (c, e, s) => _iconeSensor(),
                      )
                    : _iconeSensor(),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      nome,
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 15,
                      ),
                    ),
                    if (descricao.isNotEmpty)
                      Text(
                        descricao,
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: TextStyle(
                          color: context.textMuted,
                          fontSize: 12,
                        ),
                      ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: OutlinedButton.icon(
                  onPressed: () => _abrirModal(sensor: s),
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
                  onPressed: () => _excluir(s['ID_SENSOR'] as int),
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

  Widget _iconeSensor() {
    return Container(
      width: 44,
      height: 44,
      color: AppColors.primary.withValues(alpha: 0.1),
      child: const Icon(Icons.sensors, color: AppColors.primary, size: 20),
    );
  }
}
