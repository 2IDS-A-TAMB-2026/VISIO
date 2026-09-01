import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';

import 'api_config.dart';
import 'auth_service.dart';
import 'http_client_provider.dart';

class ApiException implements Exception {
  final String mensagem;
  final int? statusCode;

  ApiException(this.mensagem, {this.statusCode});

  @override
  String toString() => mensagem;
}

class ApiClient {
  ApiClient._();

  static Uri _uri(String path) => Uri.parse('${ApiConfig.baseUrl}/$path');

  static Map<String, String> _headers({bool jsonBody = false}) {
    final headers = <String, String>{...AuthService.instance.headersComSessao};

    if (jsonBody) {
      headers['Content-Type'] = 'application/json';
    }

    return headers;
  }

  static Future<dynamic> get(String path) async {
    final client = criarHttpClient();

    try {
      final resposta = await client.get(_uri(path), headers: _headers());

      return _handle(resposta);
    } on ApiException {
      rethrow;
    } catch (e) {
      throw ApiException('Erro de conexão com o servidor: $e');
    } finally {
      client.close();
    }
  }

  static Future<dynamic> postJson(
    String path,
    Map<String, dynamic> body,
  ) async {
    final client = criarHttpClient();

    try {
      final resposta = await client.post(
        _uri(path),
        headers: _headers(jsonBody: true),
        body: json.encode(body),
      );

      return _handle(resposta);
    } on ApiException {
      rethrow;
    } catch (e) {
      throw ApiException('Erro de conexão com o servidor: $e');
    } finally {
      client.close();
    }
  }

  static Future<dynamic> postForm(
    String path,
    Map<String, dynamic> fields,
  ) async {
    final client = criarHttpClient();

    try {
      final corpoCodificado = Uri(queryParameters: fields).query;

      final resposta = await client.post(
        _uri(path),
        headers: {
          ..._headers(),
          'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
        },
        body: corpoCodificado,
      );

      return _handle(resposta);
    } on ApiException {
      rethrow;
    } catch (e) {
      throw ApiException('Erro de conexão com o servidor: $e');
    } finally {
      client.close();
    }
  }

  static Future<dynamic> postMultipart(
    String path,
    Map<String, String> fields, {
    String fotoFieldName = 'foto',
    XFile? foto,
  }) async {
    final client = criarHttpClient();

    try {
      final request = http.MultipartRequest('POST', _uri(path));

      request.headers.addAll(_headers());
      request.fields.addAll(fields);

      if (foto != null) {
        final bytes = await foto.readAsBytes();

        request.files.add(
          http.MultipartFile.fromBytes(
            fotoFieldName,
            bytes,
            filename: foto.name,
          ),
        );
      }

      final streamed = await client.send(request);

      final resposta = await http.Response.fromStream(streamed);

      return _handle(resposta);
    } on ApiException {
      rethrow;
    } catch (e) {
      throw ApiException('Erro de conexão com o servidor: $e');
    } finally {
      client.close();
    }
  }

  static dynamic _handle(http.Response resposta) {
    final body = resposta.body.trim();

    // Exibe no console exatamente o que o backend retornou.
    print('========================================');
    print('API STATUS: ${resposta.statusCode}');
    print('API BODY: $body');
    print('========================================');

    // Status HTTP de erro
    if (resposta.statusCode < 200 || resposta.statusCode >= 300) {
      dynamic corpo;

      try {
        corpo = body.isNotEmpty ? json.decode(body) : null;
      } catch (_) {
        throw ApiException(
          'Servidor retornou uma resposta inválida '
          '(${resposta.statusCode}): $body',
          statusCode: resposta.statusCode,
        );
      }

      String mensagem =
          'Erro ao comunicar com o servidor '
          '(${resposta.statusCode}).';

      if (corpo is Map) {
        if (corpo['mensagem'] is String) {
          mensagem = corpo['mensagem'];
        } else if (corpo['message'] is String) {
          mensagem = corpo['message'];
        }
      }

      throw ApiException(mensagem, statusCode: resposta.statusCode);
    }

    // HTTP 2xx mas sem conteúdo
    if (body.isEmpty) {
      throw ApiException(
        'O servidor retornou uma resposta vazia '
        'para esta requisição.',
      );
    }

    // Tenta decodificar o JSON
    try {
      return json.decode(body);
    } catch (e) {
      throw ApiException(
        'O servidor retornou um JSON inválido.\n\n'
        'Resposta recebida:\n$body',
      );
    }
  }
}
