import 'dart:convert';
import 'dart:js_interop';

import 'package:flutter/foundation.dart' show kIsWeb;

class TeachableMachineService {
  TeachableMachineService._();
  static final TeachableMachineService instance = TeachableMachineService._();

  bool get disponivel => kIsWeb;

  Future<List<Map<String, dynamic>>> prever(List<int> bytesImagem) async {
    if (!kIsWeb) return [];

    try {
      final base64 = base64Encode(bytesImagem);
      final resultado = await _visioTmPreverBase64(base64.toJS).toDart;
      final jsonTexto = resultado.toDart;
      final lista = jsonDecode(jsonTexto) as List;
      return lista.cast<Map<String, dynamic>>();
    } catch (e) {
      return [];
    }
  }

  Future<({String className, double probability})?> melhorPrevisao(
    List<int> bytesImagem,
  ) async {
    final previsoes = await prever(bytesImagem);
    if (previsoes.isEmpty) return null;

    var melhor = previsoes.first;
    for (final p in previsoes) {
      if ((p['probability'] as num) > (melhor['probability'] as num)) {
        melhor = p;
      }
    }

    return (
      className: melhor['className'] as String,
      probability: (melhor['probability'] as num).toDouble(),
    );
  }
}

@JS('visioTmPreverBase64')
external JSPromise<JSString> _visioTmPreverBase64(JSString base64);
