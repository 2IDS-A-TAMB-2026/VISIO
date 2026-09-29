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

  // ADICIONADO — texto da tela atualmente visível, para o botão "Ler
  // página" do painel de acessibilidade (substitui o antigo switch
  // "Modo fala"). Cada tela chama definirTextoDaPagina() a cada build
  // enquanto estiver ativa, então o texto acompanha sozinho tanto a
  // navegação entre telas quanto mudanças de conteúdo (ex.: resultado
  // da identificação, estatísticas carregadas). Não chama
  // notifyListeners() de propósito: é só um valor lido no momento do
  // toque no botão, não precisa disparar rebuild em ninguém.
  String _textoPagina = '';
  String get textoPagina => _textoPagina;

  void definirTextoDaPagina(String texto) {
    _textoPagina = texto;
  }

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
    // CORRIGIDO — sem try/catch aqui, uma falha em qualquer chamada
    // abaixo (comum em Flutter Web: setLanguage('pt-BR') pode rejeitar
    // se o navegador ainda não carregou a lista de vozes no momento em
    // que o app abre) deixava _prontoFuture permanentemente rejeitado.
    // Como todo speak()/stop() faz `await _prontoFuture` antes de
    // falar, isso travava a leitura em silêncio para o resto da sessão
    // — o botão continuava reagindo (o toggle de _isSpeaking acontece
    // antes desse await), mas nenhum áudio saía nunca mais.
    try {
      await _flutterTts.setLanguage('pt-BR');
    } catch (e) {
      debugPrint('TtsService: falha ao definir idioma pt-BR: $e');
    }

    try {
      await _flutterTts.setSpeechRate(0.5);
      await _flutterTts.setVolume(1.0);
      await _flutterTts.setPitch(1.0);
      await _flutterTts.setQueueMode(0);
    } catch (e) {
      debugPrint('TtsService: falha ao configurar o flutter_tts: $e');
    }

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
      debugPrint('TtsService: erro do flutter_tts ao falar: $message');
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
      try {
        // Sempre para qualquer leitura anterior antes de iniciar uma
        // nova — garante que uma nova leitura sempre comece do início,
        // nunca continue de onde a anterior parou.
        await _flutterTts.stop();
        await _flutterTts.speak(text);
      } catch (e) {
        // CORRIGIDO — sem este catch, uma falha aqui (ex.: engine de
        // voz indisponível) deixava a fila (_fila) permanentemente
        // rejeitada: toda chamada seguinte a speak()/stop() herdava o
        // erro sem nunca chegar a rodar de novo.
        debugPrint('TtsService: falha ao falar "$text": $e');
        _isSpeaking = false;
        notifyListeners();
      }
    });

    _fila = operacao;
    return operacao;
  }

  Future<void> stop() {
    _isSpeaking = false;
    notifyListeners();

    final operacao = _fila.then((_) async {
      await _prontoFuture;
      try {
        await _flutterTts.stop();
      } catch (e) {
        debugPrint('TtsService: falha ao parar: $e');
      }
    });

    _fila = operacao;
    return operacao;
  }
}
