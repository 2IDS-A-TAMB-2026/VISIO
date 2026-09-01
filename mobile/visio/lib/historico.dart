import 'package:flutter/material.dart';

import 'appcolor.dart';
import 'services/api_client.dart';

/// Histórico de respostas do quiz — NOVA TELA (não existia no app antes
/// desta integração). Corresponde a `RespostaController::historico()`
/// (GET) e `excluir()` (POST) no backend.
class HistoricoPage extends StatefulWidget {
  const HistoricoPage({super.key});

  @override
  State<HistoricoPage> createState() => _HistoricoPageState();
}

class _HistoricoPageState extends State<HistoricoPage> {
  bool _carregando = true;
  String? _erro;
  List<Map<String, dynamic>> _respostas = [];
  int _total = 0;
  int _totalAcertos = 0;

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
      final corpo = await ApiClient.get('historico') as Map<String, dynamic>;
      final lista = (corpo['respostas'] as List).cast<Map<String, dynamic>>();

      if (!mounted) return;
      setState(() {
        _respostas = lista;
        _total = (corpo['total'] as num?)?.toInt() ?? lista.length;
        _totalAcertos = (corpo['total_acertos'] as num?)?.toInt() ?? 0;
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

  Future<void> _excluir(Map<String, dynamic> resposta) async {
    final confirmar = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        backgroundColor: context.cardBg,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Remover do histórico'),
        content: Text(
          'Deseja remover esta resposta do seu histórico?',
          style: TextStyle(color: context.textMuted),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Cancelar'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Remover'),
          ),
        ],
      ),
    );

    if (confirmar != true) return;

    final id = resposta['ID_RESPONDE'] as int;
    try {
      await ApiClient.postForm('resposta/excluir/$id', {});
      if (!mounted) return;
      setState(() {
        final acertou = (resposta['IS_CORRETA'] as num?)?.toInt() == 1;
        _respostas.removeWhere((r) => r['ID_RESPONDE'] == id);
        _total--;
        if (acertou) _totalAcertos--;
      });
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

  Color _nivelCor(String? nivel) {
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

  String _formatarData(String? isoDatetime) {
    if (isoDatetime == null) return '';
    try {
      final data = DateTime.parse(isoDatetime);
      String dois(int n) => n.toString().padLeft(2, '0');
      return '${dois(data.day)}/${dois(data.month)}/${data.year} ${dois(data.hour)}:${dois(data.minute)}';
    } catch (_) {
      return isoDatetime;
    }
  }

  @override
  Widget build(BuildContext context) {
    final percentual = _total > 0 ? ((_totalAcertos / _total) * 100).round() : 0;

    return Scaffold(
      appBar: AppBar(title: const Text('Histórico')),
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
                      const Icon(Icons.error_outline, size: 48, color: AppColors.danger),
                      const SizedBox(height: 12),
                      Text(_erro!, textAlign: TextAlign.center, style: TextStyle(color: context.textMuted)),
                      const SizedBox(height: 20),
                      ElevatedButton(onPressed: _carregar, child: const Text('Tentar novamente')),
                    ],
                  ),
                ),
              )
            : _respostas.isEmpty
            ? Center(
                child: Padding(
                  padding: const EdgeInsets.all(24),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.history_toggle_off, size: 56, color: context.textMuted),
                      const SizedBox(height: 12),
                      Text(
                        'Você ainda não respondeu nenhuma pergunta do quiz.',
                        textAlign: TextAlign.center,
                        style: TextStyle(color: context.textMuted),
                      ),
                    ],
                  ),
                ),
              )
            : RefreshIndicator(
                onRefresh: _carregar,
                child: ListView(
                  padding: const EdgeInsets.all(16),
                  children: [
                    Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: context.cardBg,
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: context.borderColor),
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                        children: [
                          _estat('$_total', 'Respostas'),
                          Container(width: 1, height: 32, color: context.borderColor),
                          _estat('$_totalAcertos', 'Acertos'),
                          Container(width: 1, height: 32, color: context.borderColor),
                          _estat('$percentual%', 'Aproveitamento'),
                        ],
                      ),
                    ),
                    const SizedBox(height: 16),
                    ..._respostas.map((r) => _buildItem(context, r)),
                  ],
                ),
              ),
      ),
    );
  }

  Widget _estat(String valor, String label) {
    return Column(
      children: [
        Text(
          valor,
          style: const TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: AppColors.primary,
          ),
        ),
        const SizedBox(height: 2),
        Builder(
          builder: (context) =>
              Text(label, style: TextStyle(fontSize: 11, color: context.textMuted)),
        ),
      ],
    );
  }

  Widget _buildItem(BuildContext context, Map<String, dynamic> r) {
    final acertou = (r['IS_CORRETA'] as num?)?.toInt() == 1;
    final nivel = r['NIVEL_DIFICULDADE'] as String?;

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: context.cardBg,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(
          color: acertou ? AppColors.success.withValues(alpha: 0.3) : AppColors.danger.withValues(alpha: 0.3),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              if (nivel != null)
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                  decoration: BoxDecoration(
                    color: _nivelCor(nivel).withValues(alpha: 0.15),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    nivel,
                    style: TextStyle(
                      fontSize: 11,
                      fontWeight: FontWeight.w600,
                      color: _nivelCor(nivel),
                    ),
                  ),
                ),
              const Spacer(),
              Icon(
                acertou ? Icons.check_circle : Icons.cancel,
                size: 18,
                color: acertou ? AppColors.success : AppColors.danger,
              ),
              IconButton(
                icon: const Icon(Icons.delete_outline, size: 18),
                color: context.textMuted,
                onPressed: () => _excluir(r),
                padding: EdgeInsets.zero,
                constraints: const BoxConstraints(),
                visualDensity: VisualDensity.compact,
              ),
            ],
          ),
          const SizedBox(height: 8),
          Text(
            r['PERGUNTA_TEXTO'] as String? ?? '',
            style: TextStyle(fontWeight: FontWeight.w600, color: context.textPrimary),
          ),
          const SizedBox(height: 4),
          Text(
            'Sua resposta: ${r['ALTERNATIVA_TEXTO'] ?? ''}',
            style: TextStyle(fontSize: 13, color: context.textMuted),
          ),
          const SizedBox(height: 6),
          Text(
            _formatarData(r['RESPONDIDO_EM'] as String?),
            style: TextStyle(fontSize: 11, color: context.textMuted),
          ),
        ],
      ),
    );
  }
}
