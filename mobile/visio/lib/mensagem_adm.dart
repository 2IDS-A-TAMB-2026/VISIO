import 'package:flutter/material.dart';
import 'main.dart';

class MensagemAdmPage extends StatefulWidget {
  const MensagemAdmPage({super.key});

  @override
  State<MensagemAdmPage> createState() => _MensagemAdmPageState();
}

class _MensagemAdmPageState extends State<MensagemAdmPage> {
  // Lista simulada de mensagens enviadas
  final List<Map<String, String>> _mensagens = [
    {
      'nome': 'João Silva',
      'email': 'joao@gmail.com',
      'mensagem': 'Gostaria de saber mais sobre sensores IoT.',
      'data': '31/03/2026',
    },
    {
      'nome': 'Maria Oliveira',
      'email': 'maria@gmail.com',
      'mensagem': 'Problema ao acessar a plataforma.',
      'data': '30/03/2026',
    },
    {
      'nome': 'Carlos Santos',
      'email': 'carlos@gmail.com',
      'mensagem': 'A IA identificou meu sensor incorretamente.',
      'data': '29/03/2026',
    },
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Mensagens Recebidas')),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: _mensagens.isEmpty
              ? const Center(
                  child: Text(
                    'Nenhuma mensagem recebida.',
                    style: TextStyle(color: AppColors.textMuted),
                  ),
                )
              : SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: DataTable(
                    columns: const [
                      DataColumn(
                        label: Text(
                          'Nome',
                          style: TextStyle(fontWeight: FontWeight.bold),
                        ),
                      ),
                      DataColumn(
                        label: Text(
                          'E-mail',
                          style: TextStyle(fontWeight: FontWeight.bold),
                        ),
                      ),
                      DataColumn(
                        label: Text(
                          'Mensagem',
                          style: TextStyle(fontWeight: FontWeight.bold),
                        ),
                      ),
                      DataColumn(
                        label: Text(
                          'Data',
                          style: TextStyle(fontWeight: FontWeight.bold),
                        ),
                      ),
                    ],
                    rows: _mensagens
                        .map(
                          (msg) => DataRow(
                            cells: [
                              DataCell(Text(msg['nome']!)),
                              DataCell(Text(msg['email']!)),
                              DataCell(
                                SizedBox(
                                  width: 300, // limita largura da mensagem
                                  child: Text(
                                    msg['mensagem']!,
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                ),
                              ),
                              DataCell(Text(msg['data']!)),
                            ],
                          ),
                        )
                        .toList(),
                  ),
                ),
        ),
      ),
    );
  }
}
