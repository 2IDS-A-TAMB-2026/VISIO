import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'services/api_client.dart';
import 'services/api_config.dart';

class SensoresPage extends StatefulWidget {
  const SensoresPage({super.key});

  @override
  State<SensoresPage> createState() => _SensoresPageState();
}

class _SensoresPageState extends State<SensoresPage> {
  String _busca = '';
  bool _carregando = true;
  String? _erro;
  List<Map<String, dynamic>> _sensores = [];

  static const List<Color> _paleta = [
    Color(0xFFEF4444),
    Color(0xFF8B5CF6),
    Color(0xFF1E6BE7),
    Color(0xFFF59E0B),
    Color(0xFF22C55E),
    Color(0xFF06B6D4),
    Color(0xFFFF7043),
    Color(0xFF7C3AED),
    Color(0xFFEC4899),
  ];

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
      final resposta = await ApiClient.get('sensores') as Map<String, dynamic>;

     
      if (resposta['erro'] == true) {
        if (!mounted) return;

        setState(() {
          _carregando = false;
          _erro =
              resposta['mensagem']?.toString() ??
              'Erro ao carregar sensores.';
        });

        return;
      }

      final dados = resposta['dados'];

      if (dados is! List) {
        if (!mounted) return;

        setState(() {
          _carregando = false;
          _erro = 'Formato de resposta inválido.';
        });

        return;
      }

      if (!mounted) return;

      setState(() {
        _sensores = dados
            .map((item) => Map<String, dynamic>.from(item as Map))
            .toList();

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

  Color _corDoSensor(String nome) =>
      _paleta[nome.hashCode.abs() % _paleta.length];

  List<Map<String, dynamic>> get _filtrados => _sensores
      .where(
        (s) => (s['NOME'] as String? ?? '').toLowerCase().contains(
          _busca.toLowerCase(),
        ),
      )
      .toList();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/logos/Logo/LogoDark2.png', height: 40),
            const Text('Sensores IoT'),
          ],
        ),
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
            : Column(
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
                    padding: const EdgeInsets.symmetric(
                      horizontal: 16,
                      vertical: 4,
                    ),
                    child: Row(
                      children: [
                        Text(
                          '${_filtrados.length} sensor${_filtrados.length == 1 ? '' : 'es'} encontrado${_filtrados.length == 1 ? '' : 's'}',
                          style: TextStyle(
                            fontSize: 12,
                            color: context.textMuted,
                          ),
                        ),
                      ],
                    ),
                  ),

                  Expanded(
                    child: RefreshIndicator(
                      onRefresh: _carregar,
                      child: _filtrados.isEmpty
                          ? ListView(
                              children: [
                                SizedBox(height: 120),
                                Center(
                                  child: Column(
                                    children: [
                                      Icon(
                                        _sensores.isEmpty
                                            ? Icons.sensors_off
                                            : Icons.search_off,
                                        size: 48,
                                        color: context.textMuted,
                                      ),
                                      const SizedBox(height: 12),
                                      Text(
                                        _sensores.isEmpty
                                            ? 'Nenhum sensor cadastrado ainda'
                                            : 'Nenhum sensor encontrado',
                                        style: TextStyle(
                                          color: context.textMuted,
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            )
                          : GridView.builder(
                              padding: const EdgeInsets.fromLTRB(16, 8, 16, 24),
                              gridDelegate:
                                  const SliverGridDelegateWithFixedCrossAxisCount(
                                    crossAxisCount: 2,
                                    crossAxisSpacing: 12,
                                    mainAxisSpacing: 12,
                                    childAspectRatio: 0.4,
                                  ),
                              itemCount: _filtrados.length,
                              itemBuilder: (_, i) =>
                                  _sensorCard(context, _filtrados[i]),
                            ),
                    ),
                  ),
                ],
              ),
      ),
    );
  }

  Widget _sensorCard(BuildContext context, Map<String, dynamic> sensor) {
    final nome = sensor['NOME'] as String? ?? 'Sensor';
    final descricao = sensor['DESCRICAO'] as String? ?? '';
    final foto = sensor['FOTO'] as String?;
    final color = _corDoSensor(nome);

    return GestureDetector(
      onTap: () => _showDetails(sensor),
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: context.cardBg,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: color.withValues(alpha: 0.3), width: 1),
          boxShadow: [
            BoxShadow(
              color: color.withValues(alpha: 0.07),
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
                color: color.withValues(alpha: 0.1),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Icon(Icons.sensors, color: color, size: 24),
            ),
            const SizedBox(height: 12),
            Text(
              nome,
              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
            ),
            const SizedBox(height: 6),
            Expanded(
              child: Text(
                descricao,
                style: TextStyle(
                  fontSize: 11,
                  color: context.textMuted,
                  height: 1.4,
                ),
                maxLines: 3,
                overflow: TextOverflow.ellipsis,
              ),
            ),

            const SizedBox(height: 8),

            ClipRRect(
              borderRadius: BorderRadius.circular(8),

              child: (foto != null && foto.isNotEmpty)
                  ? _imagemSensor(foto, color)
                  : _semFoto(color),
            ),
            const SizedBox(height: 7),
            Align(
              alignment: Alignment.centerRight,
              child: Icon(
                Icons.arrow_forward_ios,
                size: 19,
                color: color.withValues(alpha: 0.6),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _imagemSensor(String foto, Color color) {
    Widget errorBuilder(BuildContext c, Object e, StackTrace? s) =>
        _semFoto(color);

  
    final urlResolvida = ApiConfig.resolverUrlImagem(foto);
    if (urlResolvida == null) {
      return _semFoto(color);
    }

    final ImageProvider provider = urlResolvida.startsWith('assets/')
        ? AssetImage(urlResolvida)
        : NetworkImage(urlResolvida);

    return Image(
      image: provider,
      height: 150,
      width: double.infinity,
      fit: BoxFit.cover,
      errorBuilder: (c, e, s) => errorBuilder(c, e, s),
    );
  }

  Widget _semFoto(Color color) {
    return Container(
      height: 150,
      width: double.infinity,
      color: color.withValues(alpha: 0.08),
      child: Icon(
        Icons.image_not_supported_outlined,
        color: color.withValues(alpha: 0.5),
      ),
    );
  }

  Future<void> _showDetails(Map<String, dynamic> sensorResumo) async {
    final id = sensorResumo['ID_SENSOR'];
    final color = _corDoSensor(sensorResumo['NOME'] as String? ?? '');

 
    Map<String, dynamic> sensor = sensorResumo;
    try {
      final corpo = await ApiClient.get('sensor/$id') as Map<String, dynamic>;

      final dados = corpo['dados'];
      if (corpo['erro'] != true && dados is Map) {
        sensor = Map<String, dynamic>.from(dados);
      }
     
    } catch (_) {
    }

    if (!mounted) return;

    showModalBottomSheet(
      context: context,
      backgroundColor: context.cardBg,
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
                  color: context.borderColor,
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
                    color: color.withValues(alpha: 0.1),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Icon(Icons.sensors, color: color, size: 28),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Text(
                    sensor['NOME'] as String? ?? 'Sensor',
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),
            Text(
              'Descrição',
              style: TextStyle(
                fontSize: 12,
                color: context.textMuted,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 6),
            Text(
              (sensor['DESCRICAO'] as String?)?.isNotEmpty == true
                  ? sensor['DESCRICAO'] as String
                  : 'Sem descrição cadastrada.',
              style: const TextStyle(fontSize: 14, height: 1.5),
            ),
            if ((sensor['CIRCUITO'] as String?)?.isNotEmpty == true) ...[
              const SizedBox(height: 16),
              Text(
                'Circuito',
                style: TextStyle(
                  fontSize: 12,
                  color: context.textMuted,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 6),
              Text(
                sensor['CIRCUITO'] as String,
                style: TextStyle(
                  fontSize: 14,
                  color: Theme.of(context).textTheme.bodyMedium?.color,
                  height: 1.5,
                ),
              ),
            ],
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
