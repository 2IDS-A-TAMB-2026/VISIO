import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../controllers/font_scale_controller.dart';
import '../controllers/theme_controller.dart';
import '../services/tts_service.dart';


class AccessibilityPanel extends StatefulWidget {
  const AccessibilityPanel({super.key});

  @override
  State<AccessibilityPanel> createState() => _AccessibilityPanelState();
}

class _AccessibilityPanelState extends State<AccessibilityPanel> {
  bool _expanded = false;

  @override
  Widget build(BuildContext context) {
    final fontScale = context.watch<FontScaleController>();
    final theme = context.watch<ThemeController>();
    final tts = context.watch<TtsService>();

    return Positioned(
      right: 12,
      bottom: 12,
     
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 260),
        child: SafeArea(
          child: AnimatedSize(
            duration: const Duration(milliseconds: 200),
            curve: Curves.easeOut,
            alignment: Alignment.bottomRight,
            child: _expanded
                ? _buildExpanded(context, fontScale, theme, tts)
                : _buildCollapsed(context),
          ),
        ),
      ),
    );
  }

  Widget _buildCollapsed(BuildContext context) {
    return _panelSurface(
      context,
      child: IconButton(
        tooltip: 'Acessibilidade',
        icon: const Icon(Icons.accessibility_new),
        onPressed: () => setState(() => _expanded = true),
      ),
    );
  }

  Widget _buildExpanded(
    BuildContext context,
    FontScaleController fontScale,
    ThemeController theme,
    TtsService tts,
  ) {
    return _panelSurface(
      context,
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  'Acessibilidade',
                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
                ),
                IconButton(
                  tooltip: 'Fechar',
                  icon: const Icon(Icons.close, size: 18),
                  onPressed: () => setState(() => _expanded = false),
                ),
              ],
            ),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text('Tamanho da fonte', style: TextStyle(fontSize: 13)),
                Row(
                  children: [
                    IconButton(
                      tooltip: 'Diminuir fonte',
                      icon: const Icon(Icons.remove_circle_outline),
                      onPressed: fontScale.scale <= FontScaleController.minScale
                          ? null
                          : fontScale.decrease,
                    ),
                    Text('${(fontScale.scale * 100).round()}%'),
                    IconButton(
                      tooltip: 'Aumentar fonte',
                      icon: const Icon(Icons.add_circle_outline),
                      onPressed: fontScale.scale >= FontScaleController.maxScale
                          ? null
                          : fontScale.increase,
                    ),
                  ],
                ),
              ],
            ),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text('Tema escuro', style: TextStyle(fontSize: 13)),
                Switch(
                  value: theme.isDarkMode,
                  onChanged: (_) => theme.toggleTheme(),
                ),
              ],
            ),
           
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text('Modo fala', style: TextStyle(fontSize: 13)),
                Switch(
                  value: tts.enabled,
                  onChanged: (valor) => TtsService.instance.setEnabled(valor),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _panelSurface(BuildContext context, {required Widget child}) {
    final scheme = Theme.of(context).colorScheme;
    return Material(
      color: scheme.surface,
      elevation: 6,
      borderRadius: BorderRadius.circular(16),
      child: child,
    );
  }
}
