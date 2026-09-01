import 'dart:async';
import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

import 'api_config.dart';
import 'http_client_provider.dart';

/// Resultado de uma tentativa de autenticação.
class AuthResult {
  final bool sucesso;
  final String? mensagemErro;

  const AuthResult.sucesso() : sucesso = true, mensagemErro = null;

  const AuthResult.falha(String mensagem)
    : sucesso = false,
      mensagemErro = mensagem;
}

/// LOGIN DE USUÁRIO COMUM e LOGIN DE ADMINISTRADOR — ambos integrados de
/// verdade com o backend (`POST /login` e `POST /login/admin`, em
/// `AuthController::loginUsuario` / `loginAdmin`), que respondem em JSON.
///
/// A autenticação do backend é por SESSÃO PHP (cookie `PHPSESSID`), não por
/// token. Isso é tratado de duas formas diferentes dependendo da
/// plataforma, via `criarHttpClient()` (ver http_client_provider*.dart):
///
/// • Mobile/desktop (`dart:io`): este serviço captura o cookie devolvido no
///   header `Set-Cookie` da resposta de login e o reenvia manualmente em
///   toda requisição futura, através de [sessionCookie] / [ApiClient].
///
class AuthService extends ChangeNotifier {
  AuthService._();

  static final AuthService instance = AuthService._();

  static const String _chaveCookie = 'visio_session_cookie';
  static const String _chaveTipo = 'visio_session_tipo';

  String? _sessionCookie;
  String? _tipoSessao; // 'usuario' | 'admin' | null

  /// Deve ser chamado uma vez na inicialização do app (em `main()`, antes
  /// de `runApp`) para restaurar uma sessão salva de um login anterior.
  Future<void> carregarSessaoSalva() async {
    final prefs = await SharedPreferences.getInstance();
    _sessionCookie = prefs.getString(_chaveCookie);
    _tipoSessao = prefs.getString(_chaveTipo);
    notifyListeners();
  }

  /// True se há qualquer sessão ativa (usuário OU admin).
  bool get estaLogado => _sessionCookie != null;

  /// True se a sessão ativa é de um usuário comum.
  bool get estaLogadoComoUsuario => estaLogado && _tipoSessao == 'usuario';

  /// True se a sessão ativa é de um administrador.
  bool get estaLogadoComoAdmin => estaLogado && _tipoSessao == 'admin';

  /// Cookie de sessão salvo (ou null se não houver login ativo). Usado por
  /// [ApiClient] para anexar automaticamente em toda chamada autenticada.
  String? get sessionCookie => _sessionCookie;

  /// Cabeçalhos prontos para requisições autenticadas manuais. Preferir
  /// [ApiClient], que já usa isto internamente — mantido público para casos
  /// específicos fora do ApiClient.
  ///
  /// No Web nunca anexa um header Cookie manual: além de não haver um valor
  /// real capturado (ver [_extrairCookie]), navegadores bloqueiam a escrita
  /// manual desse header via JavaScript de qualquer forma. O cookie de
  /// sessão de verdade é gerenciado pelo navegador via credentials
  /// automáticas (ver [criarHttpClient]).
  Map<String, String> get headersComSessao {
    final headers = <String, String>{'Accept': 'application/json'};
    if (!kIsWeb) {
      final cookie = _sessionCookie;
      if (cookie != null) headers['Cookie'] = cookie;
    }
    return headers;
  }

  Future<void> _salvarSessao(String cookie, String tipo) async {
    _sessionCookie = cookie;
    _tipoSessao = tipo;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_chaveCookie, cookie);
    await prefs.setString(_chaveTipo, tipo);
    notifyListeners();
  }

  /// Ver [_extrairCookie]: no Web isto nunca é um cookie de verdade, só um
  /// marcador local de "há uma sessão de navegador ativa".
  static const String _marcadorSessaoWeb = 'sessao-navegador-web';

  String? _extrairCookie(http.Response resposta) {
    if (kIsWeb) {
      // O navegador nunca expõe Set-Cookie para o JS (ver comentário da
      // classe). Se chegou aqui é porque o backend respondeu 200 (login
      // OK), então o cookie real já foi guardado pelo navegador sozinho —
      // só precisamos de um marcador não-nulo para estaLogado funcionar.
      return _marcadorSessaoWeb;
    }
    final setCookie = resposta.headers['set-cookie'];
    if (setCookie == null || setCookie.isEmpty) return null;
    return setCookie.split(';').first;
  }

  /// Encerra a sessão. Tenta avisar o backend (`GET /logout`, que também já
  /// responde em JSON), mas limpa o estado local de qualquer forma mesmo se
  /// a chamada de rede falhar — o usuário não deve ficar "preso" logado no
  /// app só porque a conexão caiu.
  Future<void> logout() async {
    final estavaLogado = _sessionCookie != null;
    if (estavaLogado) {
      final client = criarHttpClient();
      try {
        await client.get(
          Uri.parse('${ApiConfig.baseUrl.replaceAll('/api', '')}/logout'),
          headers: headersComSessao,
        );
      } catch (_) {
        // Sem conexão: segue para limpar o estado local mesmo assim.
      } finally {
        client.close();
      }
    }

    _sessionCookie = null;
    _tipoSessao = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_chaveCookie);
    await prefs.remove(_chaveTipo);
    notifyListeners();
  }

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
      return const AuthResult.falha(
        'A senha deve ter pelo menos 6 caracteres.',
      );
    }

    final client = criarHttpClient();
    try {
      final resposta = await client.post(
        Uri.parse('${ApiConfig.baseUrl.replaceAll('', '')}/login'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: json.encode({'email': email, 'senha': senha}),
      );

      Map<String, dynamic>? corpo;
      try {
        corpo = json.decode(resposta.body) as Map<String, dynamic>;
      } catch (_) {
        corpo = null;
      }

      if (resposta.statusCode == 200) {
        final cookie = _extrairCookie(resposta);
        if (cookie != null) {
          await _salvarSessao(cookie, 'usuario');
        }
        return const AuthResult.sucesso();
      }

      return AuthResult.falha(
        corpo?['message'] as String? ?? 'E-mail ou senha incorretos.',
      );
    } catch (e) {
      return AuthResult.falha('Erro de conexão com o servidor: $e');
    } finally {
      client.close();
    }
  }

  /// LOGIN DE ADMINISTRADOR — integrado com `POST /login/admin`
  /// (`AuthController::loginAdmin`), que responde em JSON. Segue exatamente
  /// o mesmo padrão de [loginUsuario]: mesma validação de formato, mesma
  /// captura de cookie/sessão.
  Future<AuthResult> loginAdmin({
    required String email,
    required String senha,
  }) async {
    if (!emailValido(email)) {
      return const AuthResult.falha('Informe um e-mail válido.');
    }
    if (senha.length < 6) {
      return const AuthResult.falha(
        'A senha deve ter pelo menos 6 caracteres.',
      );
    }

    final client = criarHttpClient();
    try {
      final resposta = await client.post(
        Uri.parse('${ApiConfig.baseUrl.replaceAll('/api', '')}/login/admin'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: json.encode({'email': email, 'senha': senha}),
      );

      Map<String, dynamic>? corpo;
      try {
        corpo = json.decode(resposta.body) as Map<String, dynamic>;
      } catch (_) {
        corpo = null;
      }

      if (resposta.statusCode == 200) {
        final cookie = _extrairCookie(resposta);
        if (cookie != null) {
          await _salvarSessao(cookie, 'admin');
        }
        return const AuthResult.sucesso();
      }

      return AuthResult.falha(
        corpo?['message'] as String? ?? 'E-mail ou senha incorretos.',
      );
    } catch (e) {
      return AuthResult.falha('Erro de conexão com o servidor: $e');
    } finally {
      client.close();
    }
  }
}
