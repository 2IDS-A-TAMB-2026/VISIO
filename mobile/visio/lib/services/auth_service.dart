import 'dart:async';
import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

import 'api_config.dart';
import 'http_client_provider.dart';

class AuthResult {
  final bool sucesso;
  final String? mensagemErro;

  const AuthResult.sucesso() : sucesso = true, mensagemErro = null;

  const AuthResult.falha(String mensagem)
    : sucesso = false,
      mensagemErro = mensagem;
}

class AuthService extends ChangeNotifier {
  AuthService._();

  static final AuthService instance = AuthService._();

  static const String _chaveCookie = 'visio_session_cookie';
  static const String _chaveTipo = 'visio_session_tipo';

  String? _sessionCookie;
  String? _tipoSessao; // 'usuario' | null

  Future<void> carregarSessaoSalva() async {
    final prefs = await SharedPreferences.getInstance();
    _sessionCookie = prefs.getString(_chaveCookie);
    _tipoSessao = prefs.getString(_chaveTipo);
    notifyListeners();
  }

  bool get estaLogado => _sessionCookie != null;
  bool get estaLogadoComoUsuario => estaLogado && _tipoSessao == 'usuario';
  bool get estaLogadoComoAdmin => estaLogado && _tipoSessao == 'admin';

  String? get sessionCookie => _sessionCookie;

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

  static const String _marcadorSessaoWeb = 'sessao-navegador-web';

  String? _extrairCookie(http.Response resposta) {
    if (kIsWeb) {
      return _marcadorSessaoWeb;
    }
    final setCookie = resposta.headers['set-cookie'];
    if (setCookie == null || setCookie.isEmpty) return null;
    return setCookie.split(';').first;
  }

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
