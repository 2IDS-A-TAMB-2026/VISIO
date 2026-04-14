import 'package:flutter/material.dart';
import 'main.dart';

class IdentificadorPage extends StatefulWidget {
  const IdentificadorPage({super.key});

  @override
  State<IdentificadorPage> createState() => _IdentificadorPageState();
}

class _IdentificadorPageState extends State<IdentificadorPage>
    with SingleTickerProviderStateMixin {
  bool _analisando = false;
  bool _temResultado = false;
  late AnimationController _pulseCtrl;
  late Animation<double> _pulseAnim;

  final _resultado = {
    'nome': 'Sensor de Temperatura DHT11',
    'confianca': 92,
    'categoria': 'Temperatura / Umidade',
    'descricao':
        'Sensor digital de temperatura e umidade. Amplamente utilizado em projetos IoT e automação residencial.',
    'pinos': '3 pinos: VCC, GND, DATA',
    'tensao': '3.3V – 5V',
    'cor': AppColors.danger,
  };

  @override
  void initState() {
    super.initState();
    _pulseCtrl = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1200),
    )..repeat(reverse: true);
    _pulseAnim = Tween(
      begin: 0.9,
      end: 1.05,
    ).animate(CurvedAnimation(parent: _pulseCtrl, curve: Curves.easeInOut));
  }

  Future<void> _identificar() async {
    setState(() {
      _analisando = true;
      _temResultado = false;
    });
    await Future.delayed(const Duration(seconds: 2));
    setState(() {
      _analisando = false;
      _temResultado = true;
    });
  }

  void _limpar() {
    setState(() {
      _temResultado = false;
      _analisando = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.bgBase,
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/LogoDark.png', height: 60),
            SizedBox(width: 8),
            Text('Identificador'),
          ],
        ),
        actions: [
          if (_temResultado)
            IconButton(
              icon: const Icon(Icons.refresh),
              tooltip: 'Nova identificação',
              onPressed: _limpar,
            ),
        ],
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Column(
            children: [
              const Text(
                'Identificação via Câmera',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 6),
              const Text(
                'Aponte a câmera para um sensor IoT e pressione identificar.',
                textAlign: TextAlign.center,
                style: TextStyle(color: AppColors.textMuted, fontSize: 13),
              ),
              const SizedBox(height: 24),

              AnimatedBuilder(
                animation: _pulseAnim,
                builder: (_, child) => Transform.scale(
                  scale: _analisando ? _pulseAnim.value : 1.0,
                  child: child,
                ),
                child: Container(
                  width: double.infinity,
                  height: 260,
                  decoration: BoxDecoration(
                    color: Colors.black,
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(
                      color: _analisando
                          ? AppColors.primaryLight
                          : AppColors.primary,
                      width: _analisando ? 2.5 : 2,
                    ),
                    boxShadow: [
                      BoxShadow(
                        color:
                            (_analisando
                                    ? AppColors.primaryLight
                                    : AppColors.primary)
                                .withOpacity(0.2),
                        blurRadius: 20,
                        spreadRadius: 2,
                      ),
                    ],
                  ),
                  child: Stack(
                    children: [
                      ..._viewfinderCorners(),

                      Center(
                        child: _analisando
                            ? Column(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  const CircularProgressIndicator(
                                    color: AppColors.primaryLight,
                                  ),
                                  const SizedBox(height: 16),
                                  const Text(
                                    'Analisando...',
                                    style: TextStyle(
                                      color: AppColors.primaryLight,
                                    ),
                                  ),
                                ],
                              )
                            : _temResultado
                            ? Column(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  const Icon(
                                    Icons.check_circle_outline,
                                    size: 48,
                                    color: AppColors.success,
                                  ),
                                  const SizedBox(height: 8),
                                  const Text(
                                    'Sensor identificado!',
                                    style: TextStyle(color: AppColors.success),
                                  ),
                                ],
                              )
                            : Column(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Icon(
                                    Icons.camera_alt_outlined,
                                    size: 48,
                                    color: AppColors.primary.withOpacity(0.5),
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    'Câmera',
                                    style: TextStyle(
                                      color: AppColors.textMuted.withOpacity(
                                        0.6,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                      ),

                      Positioned(
                        top: 12,
                        left: 12,
                        child: Container(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 10,
                            vertical: 4,
                          ),
                          decoration: BoxDecoration(
                            color: AppColors.primary.withOpacity(0.85),
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: const Text(
                            'VISIO AI',
                            style: TextStyle(
                              fontSize: 11,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 20),

              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: _analisando ? null : _identificar,
                  icon: const Icon(Icons.document_scanner_outlined, size: 20),
                  label: Text(
                    _analisando ? 'Analisando...' : 'Identificar Sensor',
                  ),
                ),
              ),

              if (!_temResultado) ...[
                const SizedBox(height: 12),
                OutlinedButton.icon(
                  onPressed: () {},
                  icon: const Icon(Icons.photo_library_outlined, size: 18),
                  label: const Text('Usar da galeria'),
                  style: OutlinedButton.styleFrom(
                    minimumSize: const Size(double.infinity, 48),
                  ),
                ),
              ],

              if (_temResultado) ...[
                const SizedBox(height: 24),
                _buildResultado(),
              ],

              if (!_temResultado && !_analisando) ...[
                const SizedBox(height: 28),
                _buildDicas(),
              ],
            ],
          ),
        ),
      ),
    );
  }

  List<Widget> _viewfinderCorners() {
    const size = 24.0;
    const thickness = 2.5;
    final color = AppColors.primary;
    return [
      _corner(
        top: 12,
        left: 12,
        border: Border(
          top: BorderSide(color: color, width: thickness),
          left: BorderSide(color: color, width: thickness),
        ),
        size: size,
      ),
      _corner(
        top: 12,
        right: 12,
        border: Border(
          top: BorderSide(color: color, width: thickness),
          right: BorderSide(color: color, width: thickness),
        ),
        size: size,
      ),
      _corner(
        bottom: 12,
        left: 12,
        border: Border(
          bottom: BorderSide(color: color, width: thickness),
          left: BorderSide(color: color, width: thickness),
        ),
        size: size,
      ),
      _corner(
        bottom: 12,
        right: 12,
        border: Border(
          bottom: BorderSide(color: color, width: thickness),
          right: BorderSide(color: color, width: thickness),
        ),
        size: size,
      ),
    ];
  }

  Widget _corner({
    double? top,
    double? left,
    double? right,
    double? bottom,
    required Border border,
    required double size,
  }) {
    return Positioned(
      top: top,
      left: left,
      right: right,
      bottom: bottom,
      child: Container(
        width: size,
        height: size,
        decoration: BoxDecoration(border: border),
      ),
    );
  }

  Widget _buildResultado() {
    final confianca = _resultado['confianca'] as int;
    final color = _resultado['cor'] as Color;

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: AppColors.bgCardAlt,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppColors.success.withOpacity(0.3)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: color.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Icon(Icons.thermostat_outlined, color: color, size: 22),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      _resultado['nome'] as String,
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 15,
                      ),
                    ),
                    Text(
                      _resultado['categoria'] as String,
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppColors.textMuted,
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
                  color: AppColors.success.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  '$confianca%',
                  style: const TextStyle(
                    color: AppColors.success,
                    fontWeight: FontWeight.bold,
                    fontSize: 14,
                  ),
                ),
              ),
            ],
          ),

          const SizedBox(height: 16),
          const Divider(),
          const SizedBox(height: 12),

          _infoRow(
            Icons.info_outline,
            'Descrição',
            _resultado['descricao'] as String,
          ),
          const SizedBox(height: 10),
          _infoRow(
            Icons.cable_outlined,
            'Pinos',
            _resultado['pinos'] as String,
          ),
          const SizedBox(height: 10),
          _infoRow(
            Icons.electrical_services_outlined,
            'Tensão',
            _resultado['tensao'] as String,
          ),

          const SizedBox(height: 16),

          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'Confiança da IA',
                    style: TextStyle(fontSize: 12, color: AppColors.textMuted),
                  ),
                  Text(
                    '$confianca%',
                    style: const TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.bold,
                      color: AppColors.success,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 6),
              ClipRRect(
                borderRadius: BorderRadius.circular(4),
                child: LinearProgressIndicator(
                  value: confianca / 100,
                  backgroundColor: AppColors.border,
                  color: AppColors.success,
                  minHeight: 6,
                ),
              ),
            ],
          ),

          const SizedBox(height: 16),

          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: () {},
              icon: const Icon(Icons.save_outlined, size: 18),
              label: const Text('Salvar no sistema'),
            ),
          ),
        ],
      ),
    );
  }

  Widget _infoRow(IconData icon, String label, String value) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(icon, size: 16, color: AppColors.primary),
        const SizedBox(width: 8),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                label,
                style: const TextStyle(
                  fontSize: 11,
                  color: AppColors.textMuted,
                ),
              ),
              Text(value, style: const TextStyle(fontSize: 13, height: 1.4)),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildDicas() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.primary.withOpacity(0.05),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.primary.withOpacity(0.15)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            children: [
              Icon(
                Icons.tips_and_updates_outlined,
                size: 16,
                color: AppColors.primary,
              ),
              SizedBox(width: 6),
              Text(
                'Dicas para melhor resultado',
                style: TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.bold,
                  color: AppColors.primary,
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          ...[
            'Boa iluminação do ambiente',
            'Sensor centralizado na câmera',
            'Distância de 10–30 cm',
            'Fundo neutro (branco ou cinza)',
          ].map(
            (dica) => Padding(
              padding: const EdgeInsets.only(bottom: 6),
              child: Row(
                children: [
                  const Icon(Icons.check, size: 14, color: AppColors.success),
                  const SizedBox(width: 8),
                  Text(
                    dica,
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppColors.textSoft,
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  @override
  void dispose() {
    _pulseCtrl.dispose();
    super.dispose();
  }
}
