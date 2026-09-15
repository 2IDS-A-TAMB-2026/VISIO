import 'package:flutter/foundation.dart';
import 'package:flutter_tts/flutter_tts.dart';
class TtsService extends ChangeNotifier {
  TtsService._internal() {
   
    _prontoFuture = _configure();
  }

  static final TtsService instance = TtsService._internal();

  late final Future<void> _prontoFuture;

  final FlutterTts _flutterTts = FlutterTts();

  bool _isSpeaking = false;
  bool get isSpeaking => _isSpeaking;

  bool _enabled = true;
  bool get enabled => _enabled;

  Future<void> setEnabled(bool valor) async {
    _enabled = valor;
    notifyListeners();
    if (!valor) {
      await stop();
    } else {
      await speak('Modo de leitura ativado.');
    }
  }

  Future<void> _configure() async {
    await _flutterTts.setLanguage('pt-BR');
    await _flutterTts.setSpeechRate(0.5);
    await _flutterTts.setVolume(1.0);
    await _flutterTts.setPitch(1.0);


    await _flutterTts.setQueueMode(0);

    _flutterTts.setStartHandler(() {
      _isSpeaking = true;
      notifyListeners();
    });
    _flutterTts.setCompletionHandler(() {
      _isSpeaking = false;
      notifyListeners();
    });
    _flutterTts.setCancelHandler(() {
      _isSpeaking = false;
      notifyListeners();
    });
    _flutterTts.setErrorHandler((dynamic message) {
      _isSpeaking = false;
      notifyListeners();
    });
  }

  // CORRIGIDO (ciclo do botão "Ler página" quebrava em cliques rápidos):
  //
  // Causa raiz: sem `awaitSpeakCompletion(true)` (que o próprio pacote
  // recomenda evitar — issue conhecido de travamento), o Future de
  // `_flutterTts.speak()` resolve quase imediatamente, bem antes da
  // fala realmente começar no motor nativo. Quem avisa que a fala
  // começou de verdade é o `setStartHandler`, mas ele é assíncrono e
  // dispara DEPOIS do speak() retornar — em alguns motores Android,
  // esse atraso pode passar de 100ms. Nesse intervalo, `_isSpeaking`
  // ainda ficava `false`, então um segundo clique rápido via o app
  // "parado" e chamava speak() de novo em vez de stop(): em vez do
  // ciclo iniciar→parar→iniciar, o app tentava iniciar duas leituras.
  //
  // Correção, em duas partes:
  //   1) `_isSpeaking` agora é marcado de forma SÍNCRONA e OTIMISTA no
  //      exato momento em que speak()/stop() é chamado pelo botão —
  //      antes de qualquer `await` — então mesmo um clique seguinte
  //      instantâneo já enxerga o estado correto, sem depender do
  //      handler nativo assíncrono para saber "o que o usuário quer".
  //   2) `_fila` serializa a parte que realmente fala com a engine:
  //      cada speak()/stop() só aciona a engine depois que a chamada
  //      anterior terminou, nunca em paralelo — e sempre chama stop()
  //      antes de speak(), garantindo que uma nova leitura comece do
  //      início e nunca hajam duas leituras simultâneas.
  //
  // Os handlers nativos (setStartHandler/setCompletionHandler/
  // setCancelHandler/setErrorHandler) continuam existindo: eles não
  // decidem mais o que o clique do usuário quis dizer, mas continuam
  // necessários para o caso em que a LEITURA TERMINA SOZINHA (chegou
  // ao fim do texto) sem que o usuário tenha clicado em nada — aí sim
  // são a única forma do app saber que deve voltar ao estado "parado".
  Future<void> _fila = Future.value();

  Future<void> speak(String text) {
    if (!_enabled || text.trim().isEmpty) {
      return stop();
    }

    _isSpeaking = true;
    notifyListeners();

    final operacao = _fila.then((_) async {
      await _prontoFuture;
      // Sempre para qualquer leitura anterior antes de iniciar uma
      // nova — garante que uma nova leitura sempre comece do início,
      // nunca continue de onde a anterior parou.
      await _flutterTts.stop();
      await _flutterTts.speak(text);
    });

    _fila = operacao;
    return operacao;
  }

  Future<void> stop() {
    _isSpeaking = false;
    notifyListeners();

    final operacao = _fila.then((_) async {
      await _prontoFuture;
      await _flutterTts.stop();
    });

    _fila = operacao;
    return operacao;
  }
}
