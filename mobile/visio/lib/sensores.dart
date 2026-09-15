import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'services/api_client.dart';
import 'services/api_config.dart';
import 'widgets/accessibility_panel.dart';

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
              resposta['mensagem']?.toString() ?? 'Erro ao carregar sensores.';
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

  List<Map<String, dynamic>> get _filtrados => _sensores
      .where(
        (s) => (s['NOME'] as String? ?? '').toLowerCase().contains(
          _busca.toLowerCase(),
        ),
      )
      .toList();

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;
    final isDesktop = MediaQuery.of(context).size.width > 900;
    final isTablet = MediaQuery.of(context).size.width > 600;

    final backgroundColor = isDark
        ? const Color(0xFF030712)
        : const Color(0xFFF1F5F9);
    final cardBgColor = isDark ? const Color(0xFF0B132B) : Colors.white;
    final cardBorderColor = isDark
        ? const Color(0xFF1E293B)
        : const Color(0xFFE2E8F0);
    final titleTextColor = isDark ? Colors.white : const Color(0xFF0F172A);
    final bodyTextColor = isDark
        ? const Color(0xFF94A3B8)
        : const Color(0xFF475569);

    int crossAxisCount = 1;
    if (isDesktop) {
      crossAxisCount = 3;
    } else if (isTablet) {
      crossAxisCount = 2;
    }

    return Scaffold(
      backgroundColor: backgroundColor,
      body: Stack(
        children: [
          Column(
            children: [
              _buildTopNavBar(context, isDark, titleTextColor, bodyTextColor),
              Expanded(
                child: _carregando
                    ? const Center(child: CircularProgressIndicator())
                    : _erro != null
                    ? _buildErrorView(bodyTextColor)
                    : RefreshIndicator(
                        onRefresh: _carregar,
                        child: SingleChildScrollView(
                          padding: const EdgeInsets.symmetric(
                            horizontal: 24,
                            vertical: 32,
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'Catálogo de Sensores IoT',
                                style: TextStyle(
                                  fontSize: 28,
                                  fontWeight: FontWeight.bold,
                                  color: titleTextColor,
                                ),
                              ),
                              const SizedBox(height: 8),
                              Text(
                                'Explore os sensores cadastrados na plataforma VISIO.',
                                style: TextStyle(
                                  fontSize: 14,
                                  color: bodyTextColor,
                                ),
                              ),
                              const SizedBox(height: 24),
                              SizedBox(
                                width: 400,
                                child: TextField(
                                  onChanged: (v) => setState(() => _busca = v),
                                  style: TextStyle(
                                    color: titleTextColor,
                                    fontSize: 14,
                                  ),
                                  decoration: InputDecoration(
                                    hintText: 'Buscar sensor...',
                                    hintStyle: const TextStyle(
                                      color: Color(0xFF94A3B8),
                                      fontSize: 13,
                                    ),
                                    prefixIcon: const Icon(
                                      Icons.search,
                                      size: 20,
                                      color: Color(0xFF0284C7),
                                    ),
                                    suffixIcon: _busca.isNotEmpty
                                        ? IconButton(
                                            icon: const Icon(
                                              Icons.clear,
                                              size: 18,
                                            ),
                                            onPressed: () =>
                                                setState(() => _busca = ''),
                                          )
                                        : null,
                                    filled: true,
                                    fillColor: isDark
                                        ? const Color(0xFF070F26)
                                        : Colors.white,
                                    contentPadding: const EdgeInsets.symmetric(
                                      vertical: 10,
                                      horizontal: 16,
                                    ),
                                    border: OutlineInputBorder(
                                      borderRadius: BorderRadius.circular(20),
                                      borderSide: BorderSide(
                                        color: cardBorderColor,
                                      ),
                                    ),
                                    enabledBorder: OutlineInputBorder(
                                      borderRadius: BorderRadius.circular(20),
                                      borderSide: BorderSide(
                                        color: cardBorderColor,
                                      ),
                                    ),
                                    focusedBorder: OutlineInputBorder(
                                      borderRadius: BorderRadius.circular(20),
                                      borderSide: const BorderSide(
                                        color: Color(0xFF0284C7),
                                      ),
                                    ),
                                  ),
                                ),
                              ),
                              const SizedBox(height: 24),
                              _filtrados.isEmpty
                                  ? _buildEmptyView(bodyTextColor)
                                  : GridView.builder(
                                      shrinkWrap: true,
                                      physics:
                                          const NeverScrollableScrollPhysics(),
                                      gridDelegate:
                                          SliverGridDelegateWithFixedCrossAxisCount(
                                            crossAxisCount: crossAxisCount,
                                            crossAxisSpacing: 20,
                                            mainAxisSpacing: 20,
                                            mainAxisExtent: 340,
                                          ),
                                      itemCount: _filtrados.length,
                                      itemBuilder: (_, i) => _sensorCard(
                                        context,
                                        _filtrados[i],
                                        cardBgColor,
                                        cardBorderColor,
                                        titleTextColor,
                                        bodyTextColor,
                                        isDark,
                                      ),
                                    ),
                            ],
                          ),
                        ),
                      ),
              ),
            ],
          ),
          const AccessibilityPanel(),
        ],
      ),
    );
  }

  Widget _buildTopNavBar(
    BuildContext context,
    bool isDark,
    Color titleColor,
    Color textColor,
  ) {
    return Container(
      height: 60,
      padding: const EdgeInsets.symmetric(horizontal: 16),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF030712) : Colors.white,
        border: Border(
          bottom: BorderSide(
            color: isDark ? const Color(0xFF1E293B) : const Color(0xFFE2E8F0),
          ),
        ),
      ),
      child: Row(
        children: [
          Image.asset(
            isDark
                ? 'assets/images/logos/Logo/LogoDark2.png'
                : 'assets/images/logos/Logo/LogoLight2.png',
            height: 28,
          ),
          const SizedBox(width: 8),
        ],
      ),
    );
  }

  Widget _sensorCard(
    BuildContext context,
    Map<String, dynamic> sensor,
    Color cardBg,
    Color cardBorder,
    Color titleColor,
    Color bodyColor,
    bool isDark,
  ) {
    final nome = sensor['NOME'] as String? ?? 'Sensor';
    final descricao = sensor['DESCRICAO'] as String? ?? '';
    final foto = sensor['FOTO'] as String?;

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: cardBg,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: cardBorder),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(isDark ? 0.3 : 0.05),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Container(
            height: 140,
            width: double.infinity,
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(12),
            ),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: (foto != null && foto.isNotEmpty)
                  ? _imagemSensor(foto)
                  : _semFoto(),
            ),
          ),
          const SizedBox(height: 10),
          Text(
            nome,
            textAlign: TextAlign.center,
            style: TextStyle(
              fontWeight: FontWeight.bold,
              fontSize: 15,
              color: titleColor,
            ),
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
          ),
          const SizedBox(height: 8),
          Expanded(
            child: Text(
              descricao,
              textAlign: TextAlign.center,
              style: TextStyle(fontSize: 12, color: bodyColor, height: 1.4),
              maxLines: 3,
              overflow: TextOverflow.ellipsis,
            ),
          ),
          TextButton(
            onPressed: () => _showDetails(sensor),
            style: TextButton.styleFrom(
              padding: EdgeInsets.zero,
              minimumSize: const Size(50, 30),
              tapTargetSize: MaterialTapTargetSize.shrinkWrap,
            ),
            child: const Text(
              'Ver detalhes →',
              style: TextStyle(
                color: Color(0xFF0284C7),
                fontSize: 13,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _imagemSensor(String foto) {
    final urlResolvida = ApiConfig.resolverUrlImagem(foto);
    if (urlResolvida == null) return _semFoto();

    final ImageProvider provider = urlResolvida.startsWith('assets/')
        ? AssetImage(urlResolvida)
        : NetworkImage(urlResolvida);

    return Image(
      image: provider,
      fit: BoxFit.contain,
      errorBuilder: (c, e, s) => _semFoto(),
    );
  }

  Widget _semFoto() {
    return Container(
      color: Colors.grey.shade100,
      child: const Icon(
        Icons.image_not_supported_outlined,
        color: Colors.grey,
        size: 32,
      ),
    );
  }

  Widget _buildErrorView(Color textColor) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.error_outline, size: 48, color: AppColors.danger),
            const SizedBox(height: 12),
            Text(
              _erro!,
              textAlign: TextAlign.center,
              style: TextStyle(color: textColor),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: _carregar,
              child: const Text('Tentar novamente'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildEmptyView(Color textColor) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(40),
        child: Column(
          children: [
            Icon(
              _sensores.isEmpty ? Icons.sensors_off : Icons.search_off,
              size: 48,
              color: textColor,
            ),
            const SizedBox(height: 12),
            Text(
              _sensores.isEmpty
                  ? 'Nenhum sensor cadastrado ainda'
                  : 'Nenhum sensor encontrado para esta busca',
              style: TextStyle(color: textColor),
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _showDetails(Map<String, dynamic> sensorResumo) async {
    final id = sensorResumo['ID_SENSOR'];
    Map<String, dynamic> sensor = sensorResumo;

    try {
      final corpo = await ApiClient.get('sensor/$id') as Map<String, dynamic>;
      final dados = corpo['dados'];
      if (corpo['erro'] != true && dados is Map) {
        sensor = Map<String, dynamic>.from(dados);
      }
    } catch (_) {}

    if (!mounted) return;

    showModalBottomSheet(
      context: context,
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
                  color: Colors.grey.shade400,
                  borderRadius: BorderRadius.circular(2),
                ),
              ),
            ),
            const SizedBox(height: 20),
            Text(
              sensor['NOME'] as String? ?? 'Sensor',
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            Text(
              (sensor['DESCRICAO'] as String?)?.isNotEmpty == true
                  ? sensor['DESCRICAO'] as String
                  : 'Sem descrição cadastrada.',
              style: const TextStyle(fontSize: 14, height: 1.5),
            ),
            if ((sensor['CIRCUITO'] as String?)?.isNotEmpty == true) ...[
              const SizedBox(height: 16),
              const Text(
                'Circuito',
                style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 6),
              Text(
                sensor['CIRCUITO'] as String,
                style: const TextStyle(fontSize: 14, height: 1.5),
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
