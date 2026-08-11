import 'package:flutter/foundation.dart';
import 'package:flutter_tts/flutter_tts.dart';

/// Serviço único de leitura de texto (Text-to-Speech) para toda a
/// aplicação.
///
/// Qualquer tela pode solicitar a leitura de um texto chamando
/// `TtsService.instance.speak(texto)`, sem precisar configurar o motor de
/// TTS novamente. Toda a inicialização do `flutter_tts` e o controle de
/// estado (falando/parado) vivem em um único lugar, evitando duplicação de
/// código entre telas. O widget [TtsButton] (em
/// `lib/widgets/tts_button.dart`) já encapsula o padrão de uso mais comum
/// (botão que lê um texto e alterna para "parar" enquanto fala).
class TtsService extends ChangeNotifier {
  TtsService._internal() {
    _configure();
  }

  static final TtsService instance = TtsService._internal();

  final FlutterTts _flutterTts = FlutterTts();

  bool _isSpeaking = false;
  bool get isSpeaking => _isSpeaking;

  Future<void> _configure() async {
    await _flutterTts.setLanguage('pt-BR');
    await _flutterTts.setSpeechRate(0.5);
    await _flutterTts.setVolume(1.0);
    await _flutterTts.setPitch(1.0);

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

  /// Lê o [text] informado em voz alta. Se já houver uma leitura em
  /// andamento, ela é interrompida antes de iniciar a nova.
  Future<void> speak(String text) async {
    if (text.trim().isEmpty) return;
    if (_isSpeaking) {
      await _flutterTts.stop();
    }
    await _flutterTts.speak(text);
  }

  /// Interrompe a leitura em andamento, se houver.
  Future<void> stop() async {
    await _flutterTts.stop();
    _isSpeaking = false;
    notifyListeners();
  }
}
