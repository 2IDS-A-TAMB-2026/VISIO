import 'package:flutter/material.dart';
import 'appcolor.dart';
import 'services/api_client.dart';

/// Gestão de usuários (admin) — CORRIGIDO/INTEGRADO: antes era uma lista
/// local com um único usuário fixo, sem nenhuma chamada de rede. Agora usa
/// `GET /admin/usuarios`, `POST admin/usuario/atualizar/:cpf` e
/// `POST admin/usuario/excluir/:cpf` (`AdminController`), exatamente como o
/// painel administrativo do site.
///
/// O identificador real do usuário é o CPF (chave primária no backend), não
/// um "id" sintético como na versão local anterior.
class UsuariosPage extends StatefulWidget {
  const UsuariosPage({super.key});

  @override
  State<UsuariosPage> createState() => _UsuariosPageState();
}

class _UsuariosPageState extends State<UsuariosPage> {
  String _busca = '';
  bool _carregando = true;
  String? _erro;
  List<Map<String, dynamic>> usuarios = [];

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
      final corpo = await ApiClient.get('admin/usuarios') as List;
      if (!mounted) return;
      setState(() {
        usuarios = corpo.cast<Map<String, dynamic>>();
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

  List<Map<String, dynamic>> get _filtrados => usuarios
      .where(
        (u) =>
            ((u['EMAIL'] as String?) ?? '').toLowerCase().contains(_busca.toLowerCase()) ||
            ((u['NOME'] as String?) ?? '').toLowerCase().contains(_busca.toLowerCase()) ||
            ((u['CPF'] as String?) ?? '').contains(_busca),
      )
      .toList();

  Future<void> _excluir(String cpf) async {
    final confirmar = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        backgroundColor: context.cardBg,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Confirmar exclusão'),
        content: Text(
          'Tem certeza que deseja excluir este usuário?',
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
            child: const Text('Excluir'),
          ),
        ],
      ),
    );

    if (confirmar != true) return;

    try {
      await ApiClient.postForm('admin/usuario/excluir/$cpf', {});
      if (!mounted) return;
      setState(() => usuarios.removeWhere((u) => u['CPF'] == cpf));
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text('Usuário excluído')));
    } on ApiException catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.mensagem)));
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Erro de conexão: $e')));
    }
  }

  String? _isoParaBr(String? iso) {
    if (iso == null || iso.isEmpty) return null;
    final partes = iso.split('-');
    if (partes.length != 3) return null;
    return '${partes[2]}/${partes[1]}/${partes[0]}';
  }

  String? _brParaIso(String br) {
    final limpo = br.replaceAll(RegExp(r'\D'), '');
    if (limpo.length != 8) return null;
    return '${limpo.substring(4, 8)}-${limpo.substring(2, 4)}-${limpo.substring(0, 2)}';
  }

  void _editar(Map<String, dynamic> usuario) {
    final nomeCtrl = TextEditingController(text: usuario['NOME'] as String? ?? '');
    final emailCtrl = TextEditingController(text: usuario['EMAIL'] as String? ?? '');
    final telCtrl = TextEditingController(text: usuario['TELEFONE'] as String? ?? '');
    final cartaoCtrl = TextEditingController(text: usuario['CARTAO'] as String? ?? '');
    final dataCtrl = TextEditingController(
      text: _isoParaBr(usuario['DATA_NASCIMENTO'] as String?) ?? '',
    );
    bool salvando = false;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: context.cardBg,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (sheetContext) => StatefulBuilder(
        builder: (sheetContext, setSheetState) => Padding(
          padding: EdgeInsets.only(
            left: 24,
            right: 24,
            top: 24,
            bottom: MediaQuery.of(sheetContext).viewInsets.bottom + 24,
          ),
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
              Text(
                'Editar usuário',
                style: const TextStyle(fontSize: 17, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 4),
              Text(
                'CPF: ${usuario['CPF']}',
                style: TextStyle(color: context.textMuted, fontSize: 12),
              ),
              const SizedBox(height: 20),
              TextField(
                controller: nomeCtrl,
                decoration: const InputDecoration(
                  labelText: 'Nome',
                  prefixIcon: Icon(Icons.person_outline, size: 18),
                ),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: emailCtrl,
                decoration: const InputDecoration(
                  labelText: 'E-mail',
                  prefixIcon: Icon(Icons.email_outlined, size: 18),
                ),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: dataCtrl,
                decoration: const InputDecoration(
                  labelText: 'Data de nascimento',
                  hintText: 'DD/MM/AAAA',
                  prefixIcon: Icon(Icons.calendar_today_outlined, size: 18),
                ),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: telCtrl,
                decoration: const InputDecoration(
                  labelText: 'Telefone',
                  prefixIcon: Icon(Icons.phone_outlined, size: 18),
                ),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: cartaoCtrl,
                decoration: const InputDecoration(
                  labelText: 'Cartão IoT',
                  prefixIcon: Icon(Icons.credit_card_outlined, size: 18),
                ),
              ),
              const SizedBox(height: 20),
              SizedBox(
                width: double.infinity,
                child: salvando
                    ? const Center(child: CircularProgressIndicator())
                    : ElevatedButton(
                        onPressed: () async {
                          setSheetState(() => salvando = true);
                          try {
                            final cpf = usuario['CPF'] as String;
                            await ApiClient.postForm('admin/usuario/atualizar/$cpf', {
                              'nome': nomeCtrl.text.trim(),
                              'email': emailCtrl.text.trim(),
                              'telefone': telCtrl.text.trim(),
                              'cartao': cartaoCtrl.text.trim(),
                              'data_nascimento': _brParaIso(dataCtrl.text) ?? '',
                            });

                            setState(() {
                              usuario['NOME'] = nomeCtrl.text.trim();
                              usuario['EMAIL'] = emailCtrl.text.trim();
                              usuario['TELEFONE'] = telCtrl.text.trim();
                              usuario['CARTAO'] = cartaoCtrl.text.trim();
                              usuario['DATA_NASCIMENTO'] = _brParaIso(dataCtrl.text);
                            });

                            if (!sheetContext.mounted) return;
                            Navigator.pop(sheetContext);
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(content: Text('Usuário atualizado!')),
                            );
                          } on ApiException catch (e) {
                            setSheetState(() => salvando = false);
                            if (!sheetContext.mounted) return;
                            ScaffoldMessenger.of(
                              sheetContext,
                            ).showSnackBar(SnackBar(content: Text(e.mensagem)));
                          } catch (e) {
                            setSheetState(() => salvando = false);
                            if (!sheetContext.mounted) return;
                            ScaffoldMessenger.of(
                              sheetContext,
                            ).showSnackBar(SnackBar(content: Text('Erro de conexão: $e')));
                          }
                        },
                        child: const Text('Salvar alterações'),
                      ),
              ),
            ],
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
        title: const Text('Usuários'),
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
                      const Icon(Icons.error_outline, size: 48, color: AppColors.danger),
                      const SizedBox(height: 12),
                      Text(_erro!, textAlign: TextAlign.center, style: TextStyle(color: context.textMuted)),
                      const SizedBox(height: 20),
                      ElevatedButton(onPressed: _carregar, child: const Text('Tentar novamente')),
                    ],
                  ),
                ),
              )
            : Column(
                children: [
                  Padding(
                    padding: const EdgeInsets.fromLTRB(16, 14, 16, 8),
                    child: TextField(
                      onChanged: (v) => setState(() => _busca = v),
                      decoration: InputDecoration(
                        hintText: 'Buscar por nome, e-mail ou CPF...',
                        prefixIcon: const Icon(Icons.search, size: 20),
                        suffixIcon: _busca.isNotEmpty
                            ? IconButton(
                                icon: const Icon(Icons.clear, size: 18),
                                onPressed: () => setState(() => _busca = ''),
                              )
                            : null,
                        contentPadding: const EdgeInsets.symmetric(vertical: 12),
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
                    child: Row(
                      children: [
                        Text(
                          '${_filtrados.length} usuário${_filtrados.length == 1 ? '' : 's'}',
                          style: TextStyle(fontSize: 12, color: context.textMuted),
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
                                const SizedBox(height: 100),
                                Center(
                                  child: Text(
                                    'Nenhum usuário encontrado',
                                    style: TextStyle(color: context.textMuted),
                                  ),
                                ),
                              ],
                            )
                          : ListView.separated(
                              padding: const EdgeInsets.fromLTRB(16, 8, 16, 24),
                              itemCount: _filtrados.length,
                              separatorBuilder: (_, _) => const SizedBox(height: 10),
                              itemBuilder: (_, i) => _userCard(context, _filtrados[i]),
                            ),
                    ),
                  ),
                ],
              ),
      ),
    );
  }

  Widget _userCard(BuildContext context, Map<String, dynamic> u) {
    final nome = u['NOME'] as String? ?? '';
    final email = u['EMAIL'] as String? ?? '';
    final inicial = nome.isNotEmpty ? nome[0].toUpperCase() : (email.isNotEmpty ? email[0].toUpperCase() : '?');
    final cartao = u['CARTAO'] as String? ?? '';

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
              CircleAvatar(
                radius: 20,
                backgroundColor: AppColors.primary.withValues(alpha: 0.1),
                child: Text(
                  inicial,
                  style: const TextStyle(
                    color: AppColors.primary,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      nome.isNotEmpty ? nome : (email.isNotEmpty ? email : '-'),
                      style: const TextStyle(
                        fontWeight: FontWeight.w600,
                        fontSize: 14,
                      ),
                    ),
                    Text(
                      '${email.isNotEmpty ? email : '-'} · CPF: ${u['CPF']}',
                      style: TextStyle(color: context.textMuted, fontSize: 11),
                    ),
                  ],
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
              _infoChip(context, Icons.phone_outlined, (u['TELEFONE'] as String?)?.isNotEmpty == true ? u['TELEFONE'] as String : '-'),
              _infoChip(
                context,
                Icons.calendar_today_outlined,
                _isoParaBr(u['DATA_NASCIMENTO'] as String?) ?? '-',
              ),
              if (cartao.isNotEmpty) _infoChip(context, Icons.credit_card_outlined, cartao),
            ],
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: OutlinedButton.icon(
                  onPressed: () => _editar(u),
                  icon: const Icon(Icons.edit_outlined, size: 16),
                  label: const Text('Editar', style: TextStyle(fontSize: 13)),
                ),
              ),
              const SizedBox(width: 8),
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () => _excluir(u['CPF'] as String),
                  icon: const Icon(Icons.delete_outline, size: 16),
                  label: const Text('Excluir', style: TextStyle(fontSize: 13)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.danger,
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _infoChip(BuildContext context, IconData icon, String label) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 13, color: context.textMuted),
        const SizedBox(width: 4),
        Text(label, style: TextStyle(fontSize: 12, color: context.textMuted)),
      ],
    );
  }
}
