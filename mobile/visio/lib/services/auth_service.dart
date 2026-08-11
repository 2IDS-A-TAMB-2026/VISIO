import 'dart:async';

/// Resultado de uma tentativa de autenticação.
class AuthResult {
  final bool sucesso;
  final String? mensagemErro;

  const AuthResult.sucesso() : sucesso = true, mensagemErro = null;

  const AuthResult.falha(String mensagem)
    : sucesso = false,
      mensagemErro = mensagem;
}

/// ATENÇÃO — IMPLEMENTAÇÃO DE DEMONSTRAÇÃO, NÃO É AUTENTICAÇÃO REAL.
///
/// Este projeto (ver auditoria) não tem, hoje, nenhuma integração com um
/// backend: não existe pacote de HTTP no `pubspec.yaml` nem chamada de rede
/// em nenhum arquivo. As telas de login antes simulavam sucesso diretamente
/// dentro do widget, sem nenhuma verificação — qualquer texto não vazio
/// era aceito, inclusive para o painel administrativo.
///
/// Esta classe não resolve isso (não há como, sem um backend real para
/// consultar), mas:
///   1. Tira a lógica de simulação de dentro dos widgets de tela;
///   2. Deixa explícito, em um único lugar, que a "aprovação" é falsa —
///      [isDemoMode] é usado pelas telas para exibir um aviso visível;
///   3. Faz a validação de formato que antes não existia (e-mail bem
///      formado, senha com tamanho mínimo) antes de "aprovar" qualquer
///      tentativa — ou seja, um único caractere deixa de ser suficiente;
///   4. Deixa pronta a substituição por chamadas HTTP reais no futuro:
///      quem for integrar com um backend real só precisa reescrever o
///      corpo de [loginUsuario]/[loginAdmin] (ou os métodos de
///      cadastro/recuperação de senha nas respectivas telas), sem tocar
///      na UI.
class AuthService {
  AuthService._();

  static final AuthService instance = AuthService._();

  /// true enquanto não houver integração real com um backend.
  /// As telas de login usam esta flag para exibir o aviso de demonstração.
  static const bool isDemoMode = true;

  static final RegExp _emailRegex = RegExp(r'^[^@\s]+@[^@\s]+\.[^@\s]+$');

  bool emailValido(String email) => _emailRegex.hasMatch(email.trim());

  Future<AuthResult> loginUsuario({
    required String email,
    required String senha,
  }) async {
    if (!emailValido(email)) {
      return const AuthResult.falha('Informe um e-mail válido.');
    }
    if (senha.length < 6) {
      return const AuthResult.falha('A senha deve ter pelo menos 6 caracteres.');
    }

    // MOCK: simula a latência de uma chamada de rede. Não há verificação
    // real de identidade — qualquer credencial bem formatada é "aceita".
    await Future.delayed(const Duration(milliseconds: 800));

    return const AuthResult.sucesso();
  }

  Future<AuthResult> loginAdmin({
    required String email,
    required String senha,
  }) async {
    if (!emailValido(email)) {
      return const AuthResult.falha('Informe um e-mail válido.');
    }
    if (senha.length < 6) {
      return const AuthResult.falha('A senha deve ter pelo menos 6 caracteres.');
    }

    // MOCK: mesmo aviso do método acima, mas aqui o risco é maior — isto
    // concede acesso ao painel administrativo. Não publique/distribua o
    // app com esta implementação achando que há alguma proteção real.
    await Future.delayed(const Duration(milliseconds: 900));

    return const AuthResult.sucesso();
  }
}
