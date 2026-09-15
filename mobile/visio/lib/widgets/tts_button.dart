import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../services/tts_service.dart';


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
    final tts = context.watch<TtsService>();
    if (!tts.enabled) return const SizedBox.shrink();

    return IconButton(
      tooltip: tooltip,
      iconSize: size,
      icon: Icon(
        tts.isSpeaking ? Icons.stop_circle_outlined : Icons.volume_up_outlined,
      ),
      onPressed: () {
        if (tts.isSpeaking) {
          TtsService.instance.stop();
        } else {
          TtsService.instance.speak(text);
        }
      },
    );
  }
}
