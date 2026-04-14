import 'package:flutter/material.dart';
import 'main.dart';

class SensoresPage extends StatefulWidget {
  const SensoresPage({super.key});

  @override
  State<SensoresPage> createState() => _SensoresPageState();
}

class _SensoresPageState extends State<SensoresPage> {
  String _busca = '';

  final List<Map<String, dynamic>> sensores = const [
    {
      'titulo': 'Temperatura',
      'icone': Icons.thermostat_outlined,
      'descricao':
          'Identifica variações de calor ou frio em um ambiente ou objeto.',
      'usos': 'Monitoramento climático, geladeiras industriais, sistemas HVAC',
      'cor': Color(0xFFEF4444),
      'imagem': 'assets/images/sensor_temperatura.jpg',
    },
    {
      'titulo': 'Proximidade',
      'icone': Icons.radar_outlined,
      'descricao': 'Detecta quando um objeto está próximo sem contato físico.',
      'usos': 'Automação de portas, segurança, robótica',
      'cor': Color(0xFF8B5CF6),
      'imagem': 'assets/images/sensor_proximidade.jpg',
    },
    {
      'titulo': 'Umidade',
      'icone': Icons.water_drop_outlined,
      'descricao': 'Mede a quantidade de vapor de água presente no ar.',
      'usos': 'Meteorologia, agricultura, controle de ambientes',
      'cor': Color(0xFF1E6BE7),
      'imagem': 'assets/images/sensor_umidade.jpg',
    },
    {
      'titulo': 'Luz',
      'icone': Icons.wb_sunny_outlined,
      'descricao': 'Mede a intensidade luminosa do ambiente.',
      'usos': 'Iluminação automática, painéis solares, fotografia',
      'cor': Color(0xFFF59E0B),
      'imagem': 'assets/images/sensor_luz.jpg.jpeg',
    },
    {
      'titulo': 'Movimento',
      'icone': Icons.directions_run_outlined,
      'descricao':
          'Detecta presença através da variação de calor corporal (PIR).',
      'usos': 'Segurança, automação residencial, câmeras',
      'cor': Color(0xFF22C55E),
      'imagem': 'assets/images/sensor_movimento.jpg.jpeg',
    },
    {
      'titulo': 'Ultrassônico',
      'icone': Icons.graphic_eq_outlined,
      'descricao': 'Calcula distância usando pulsos de ondas sonoras.',
      'usos': 'Mapeamento, robótica, estacionamento',
      'cor': Color(0xFF06B6D4),
      'imagem': 'assets/images/sensor_ultrassonico.jpg',
    },
    {
      'titulo': 'Gás / Fumaça',
      'icone': Icons.cloud_queue_outlined,
      'descricao': 'Identifica gases inflamáveis ou fumaça no ambiente.',
      'usos': 'Alarmes de incêndio, monitoramento industrial',
      'cor': Color(0xFFFF7043),
      'imagem': 'assets/images/sensor_gas.jpg.jpeg',
    },
    {
      'titulo': 'Pressão',
      'icone': Icons.compress_outlined,
      'descricao': 'Mede a pressão atmosférica para indicar clima ou altitude.',
      'usos': 'Meteorologia, drones, dispositivos wearable',
      'cor': Color(0xFF7C3AED),
      'imagem': 'assets/images/sensor_pressao.jpg.jpeg',
    },
    {
      'titulo': 'Toque',
      'icone': Icons.touch_app_outlined,
      'descricao': 'Reconhece o contato físico direto na superfície.',
      'usos': 'Interfaces, botões capacitivos, eletrônicos',
      'cor': Color(0xFFEC4899),
      'imagem': 'assets/images/sensor_toque.jpg.jpeg',
    },
  ];

  List<Map<String, dynamic>> get _filtrados => sensores
      .where((s) => s['titulo'].toLowerCase().contains(_busca.toLowerCase()))
      .toList();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/LogoDark.png', height: 60),
            Text('        Sensores IoT'),
          ],
        ),
      ),
      body: SafeArea(
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
              child: TextField(
                onChanged: (v) => setState(() => _busca = v),
                decoration: InputDecoration(
                  hintText: 'Buscar sensor...',
                  prefixIcon: const Icon(Icons.search, size: 20),
                  suffixIcon: _busca.isNotEmpty
                      ? IconButton(
                          icon: const Icon(Icons.clear, size: 18),
                          onPressed: () => setState(() => _busca = ''),
                        )
                      : null,
                  contentPadding: const EdgeInsets.symmetric(
                    vertical: 12,
                    horizontal: 16,
                  ),
                ),
              ),
            ),

            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
              child: Row(
                children: [
                  Text(
                    '${_filtrados.length} sensor${_filtrados.length == 1 ? '' : 'es'} encontrado${_filtrados.length == 1 ? '' : 's'}',
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppColors.textMuted,
                    ),
                  ),
                ],
              ),
            ),

            Expanded(
              child: _filtrados.isEmpty
                  ? const Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(
                            Icons.search_off,
                            size: 48,
                            color: AppColors.textMuted,
                          ),
                          SizedBox(height: 12),
                          Text(
                            'Nenhum sensor encontrado',
                            style: TextStyle(color: AppColors.textMuted),
                          ),
                        ],
                      ),
                    )
                  : GridView.builder(
                      padding: const EdgeInsets.fromLTRB(16, 8, 16, 24),
                      gridDelegate:
                          const SliverGridDelegateWithFixedCrossAxisCount(
                            crossAxisCount: 2,
                            crossAxisSpacing: 12,
                            mainAxisSpacing: 12,
                            childAspectRatio: 2.5,
                          ),
                      itemCount: _filtrados.length,
                      itemBuilder: (_, i) => _sensorCard(_filtrados[i]),
                    ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _sensorCard(Map<String, dynamic> sensor) {
    final color = sensor['cor'] as Color;
    return GestureDetector(
      onTap: () => _showDetails(sensor),
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: AppColors.bgCardAlt,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: color.withOpacity(0.3), width: 1),
          boxShadow: [
            BoxShadow(
              color: color.withOpacity(0.07),
              blurRadius: 12,
              spreadRadius: 1,
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              padding: const EdgeInsets.all(10),
              decoration: BoxDecoration(
                color: color.withOpacity(0.1),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Icon(sensor['icone'] as IconData, color: color, size: 24),
            ),
            const SizedBox(height: 12),
            Text(
              sensor['titulo'] as String,
              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
            ),
            const SizedBox(height: 6),
            Expanded(
              child: Text(
                sensor['descricao'] as String,
                style: const TextStyle(
                  fontSize: 11,
                  color: AppColors.textMuted,
                  height: 1.4,
                ),
                maxLines: 3,
                overflow: TextOverflow.ellipsis,
              ),
            ),

            SizedBox(height: 8),

            ClipRRect(
              borderRadius: BorderRadius.circular(8),
              child: Image.asset(
                sensor['imagem'],
                height: 60,
                width: double.infinity,
                fit: BoxFit.cover,
              ),
            ),
            Align(
              alignment: Alignment.centerRight,
              child: Icon(
                Icons.arrow_forward_ios,
                size: 12,
                color: color.withOpacity(0.6),
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showDetails(Map<String, dynamic> sensor) {
    final color = sensor['cor'] as Color;
    showModalBottomSheet(
      context: context,
      backgroundColor: AppColors.bgCardAlt,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (_) => Padding(
        padding: const EdgeInsets.all(24),
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
            const SizedBox(height: 20),
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: color.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Icon(
                    sensor['icone'] as IconData,
                    color: color,
                    size: 28,
                  ),
                ),
                const SizedBox(width: 14),
                Text(
                  'Sensor de ${sensor['titulo']}',
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),
            const Text(
              'Descrição',
              style: TextStyle(
                fontSize: 12,
                color: AppColors.textMuted,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 6),
            Text(
              sensor['descricao'] as String,
              style: const TextStyle(fontSize: 14, height: 1.5),
            ),
            const SizedBox(height: 16),
            const Text(
              'Aplicações comuns',
              style: TextStyle(
                fontSize: 12,
                color: AppColors.textMuted,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 6),
            Text(
              sensor['usos'] as String,
              style: const TextStyle(
                fontSize: 14,
                color: AppColors.textSoft,
                height: 1.5,
              ),
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('Fechar'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
