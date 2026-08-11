import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../services/tts_service.dart';

/// Botão reutilizável que lê em voz alta o texto informado em [text].
///
/// Basta colocar este widget ao lado de qualquer trecho de conteúdo para
/// oferecer a funcionalidade de leitura, sem duplicar a lógica de
/// configuração ou controle do `flutter_tts` — toda ela vive em
/// [TtsService]. O ícone alterna automaticamente entre "ouvir" e "parar"
/// conforme o estado de leitura.
class TtsButton extends StatelessWidget {
  const TtsButton({
    super.key,
    required this.text,
    this.tooltip = 'Ouvir texto',
    this.size = 20,
  });

  final String text;
  final String tooltip;
  final double size;

  @override
  Widget build(BuildContext context) {
    final speaking = context.watch<TtsService>().isSpeaking;

    return IconButton(
      tooltip: tooltip,
      iconSize: size,
      icon: Icon(
        speaking ? Icons.stop_circle_outlined : Icons.volume_up_outlined,
      ),
      onPressed: () {
        if (speaking) {
          TtsService.instance.stop();
        } else {
          TtsService.instance.speak(text);
        }
      },
    );
  }
}
