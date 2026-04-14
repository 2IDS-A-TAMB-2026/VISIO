import 'package:flutter/material.dart';
import 'main.dart';

class SensoresAdminPage extends StatefulWidget {
  const SensoresAdminPage({super.key});

  @override
  State<SensoresAdminPage> createState() => _SensoresAdminPageState();
}

class _SensoresAdminPageState extends State<SensoresAdminPage> {
  List<Map<String, dynamic>> sensores = [
    {
      'id': 1,
      'nome': 'DHT11',
      'tipo': 'Temperatura/Umidade',
      'sala': 'Laboratório 1',
      'status': 'ativo',
      'tensao': '3.3V - 5V',
      'mac': 'AA:BB:CC:DD:EE:01',
    },
    {
      'id': 2,
      'nome': 'HC-SR04',
      'tipo': 'Ultrassônico',
      'sala': 'Laboratório 2',
      'status': 'ativo',
      'tensao': '5V',
      'mac': 'AA:BB:CC:DD:EE:02',
    },
    {
      'id': 3,
      'nome': 'PIR HC-SR501',
      'tipo': 'Movimento',
      'sala': 'Sala 3',
      'status': 'inativo',
      'tensao': '5V - 20V',
      'mac': 'AA:BB:CC:DD:EE:03',
    },
  ];

  Color _statusCor(String status) =>
      status == 'ativo' ? AppColors.success : AppColors.danger;

  void _excluir(int id) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        backgroundColor: AppColors.bgCardAlt,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Excluir sensor'),
        content: const Text('Tem certeza que deseja excluir este sensor?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancelar'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () {
              setState(() => sensores.removeWhere((s) => s['id'] == id));
              Navigator.pop(context);
              ScaffoldMessenger.of(
                context,
              ).showSnackBar(const SnackBar(content: Text('Sensor excluído')));
            },
            child: const Text('Excluir'),
          ),
        ],
      ),
    );
  }

  void _novoSensor() {
    final nomeCtrl = TextEditingController();
    final tipoCtrl = TextEditingController();
    final salaCtrl = TextEditingController();
    final tensaoCtrl = TextEditingController();
    final macCtrl = TextEditingController();
    String status = 'ativo';

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
                  'Cadastrar sensor',
                  style: TextStyle(fontSize: 17, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 18),
                _modalField(nomeCtrl, 'Nome do sensor', Icons.memory_outlined),
                const SizedBox(height: 12),
                _modalField(
                  tipoCtrl,
                  'Tipo / categoria',
                  Icons.category_outlined,
                ),
                const SizedBox(height: 12),
                _modalField(
                  salaCtrl,
                  'Localização / sala',
                  Icons.location_on_outlined,
                ),
                const SizedBox(height: 12),
                _modalField(
                  tensaoCtrl,
                  'Tensão de operação',
                  Icons.electrical_services_outlined,
                ),
                const SizedBox(height: 12),
                _modalField(macCtrl, 'Endereço MAC', Icons.cable_outlined),
                const SizedBox(height: 14),
                const Text(
                  'Status',
                  style: TextStyle(fontSize: 12, color: AppColors.textMuted),
                ),
                const SizedBox(height: 8),
                Row(
                  children: ['ativo', 'inativo']
                      .map(
                        (s) => Padding(
                          padding: const EdgeInsets.only(right: 10),
                          child: GestureDetector(
                            onTap: () => setModal(() => status = s),
                            child: Container(
                              padding: const EdgeInsets.symmetric(
                                horizontal: 18,
                                vertical: 8,
                              ),
                              decoration: BoxDecoration(
                                color: status == s
                                    ? _statusCor(s).withOpacity(0.1)
                                    : Colors.transparent,
                                borderRadius: BorderRadius.circular(20),
                                border: Border.all(
                                  color: status == s
                                      ? _statusCor(s)
                                      : AppColors.border,
                                  width: 1.5,
                                ),
                              ),
                              child: Text(
                                s[0].toUpperCase() + s.substring(1),
                                style: TextStyle(
                                  color: status == s
                                      ? _statusCor(s)
                                      : AppColors.textMuted,
                                  fontWeight: status == s
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
                const SizedBox(height: 20),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () {
                      if (nomeCtrl.text.isEmpty || tipoCtrl.text.isEmpty) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(
                            content: Text('Preencha os campos obrigatórios'),
                          ),
                        );
                        return;
                      }
                      setState(() {
                        sensores.add({
                          'id': sensores.length + 1,
                          'nome': nomeCtrl.text,
                          'tipo': tipoCtrl.text,
                          'sala': salaCtrl.text,
                          'status': status,
                          'tensao': tensaoCtrl.text,
                          'mac': macCtrl.text,
                        });
                      });
                      Navigator.pop(context);
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Sensor cadastrado!')),
                      );
                    },
                    child: const Text('Cadastrar sensor'),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _modalField(TextEditingController ctrl, String label, IconData icon) {
    return TextField(
      controller: ctrl,
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
            onPressed: _novoSensor,
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
        child: sensores.isEmpty
            ? const Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      Icons.memory_outlined,
                      size: 48,
                      color: AppColors.textMuted,
                    ),
                    SizedBox(height: 12),
                    Text(
                      'Nenhum sensor cadastrado',
                      style: TextStyle(color: AppColors.textMuted),
                    ),
                  ],
                ),
              )
            : ListView.separated(
                padding: const EdgeInsets.all(16),
                itemCount: sensores.length,
                separatorBuilder: (_, __) => const SizedBox(height: 12),
                itemBuilder: (_, i) => _sensorCard(sensores[i]),
              ),
      ),
    );
  }

  Widget _sensorCard(Map<String, dynamic> s) {
    final status = s['status'] as String;
    final corStatus = _statusCor(status);

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
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: AppColors.primary.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: const Icon(
                  Icons.memory,
                  color: AppColors.primary,
                  size: 20,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      s['nome'] as String,
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 15,
                      ),
                    ),
                    Text(
                      s['tipo'] as String,
                      style: const TextStyle(
                        color: AppColors.textMuted,
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 5,
                ),
                decoration: BoxDecoration(
                  color: corStatus.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Text(
                  status == 'ativo' ? 'Ativo' : 'Inativo',
                  style: TextStyle(
                    color: corStatus,
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
          Wrap(
            spacing: 16,
            runSpacing: 6,
            children: [
              _chip(Icons.location_on_outlined, s['sala'] as String),
              _chip(Icons.electrical_services_outlined, s['tensao'] as String),
              if ((s['mac'] as String).isNotEmpty)
                _chip(Icons.cable_outlined, s['mac'] as String),
            ],
          ),
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
                  onPressed: () => _excluir(s['id'] as int),
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

  Widget _chip(IconData icon, String label) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 13, color: AppColors.textMuted),
        const SizedBox(width: 4),
        Text(
          label,
          style: const TextStyle(fontSize: 12, color: AppColors.textMuted),
        ),
      ],
    );
  }
}
