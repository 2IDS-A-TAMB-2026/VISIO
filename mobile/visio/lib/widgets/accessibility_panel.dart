import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../appcolor.dart';
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
      // Um pouco mais afastado da borda da tela do que antes (12 -> 16),
      // para não colar no cantinho e ficar mais fácil de tocar.
      right: 16,
      bottom: 16,
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 280),
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
    // Botão de destaque, na cor de marca do app, em vez de um ícone cinza
    // solto — para deixar claro que é um controle, não um detalhe visual.
    return Material(
      color: AppColors.primary,
      elevation: 6,
      shape: const CircleBorder(),
      child: InkWell(
        customBorder: const CircleBorder(),
        onTap: () => setState(() => _expanded = true),
        child: const Padding(
          padding: EdgeInsets.all(14),
          child: Icon(
            Icons.accessibility_new_rounded,
            color: Colors.white,
            size: 24,
          ),
        ),
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
        padding: const EdgeInsets.fromLTRB(16, 14, 12, 16),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            _buildCabecalho(context),
            const SizedBox(height: 14),
            Divider(height: 1, color: context.borderColor),
            const SizedBox(height: 12),
            _buildLinhaFonte(context, fontScale),
            const SizedBox(height: 14),
            _buildLinhaControle(
              context,
              icon: theme.isDarkMode
                  ? Icons.dark_mode_rounded
                  : Icons.light_mode_rounded,
              label: 'Tema escuro',
              value: theme.isDarkMode,
              onChanged: (_) => theme.toggleTheme(),
            ),
            const SizedBox(height: 10),
            _buildLinhaControle(
              context,
              icon: Icons.record_voice_over_rounded,
              label: 'Modo fala',
              value: tts.enabled,
              onChanged: (valor) => TtsService.instance.setEnabled(valor),
            ),
            const SizedBox(height: 4),
            Padding(
              padding: const EdgeInsets.only(left: 26),
              child: Text(
                'Ative para mostrar botões de ouvir os resultados em voz alta.',
                style: TextStyle(fontSize: 11, color: context.textMuted),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCabecalho(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Row(
          children: [
            Container(
              padding: const EdgeInsets.all(6),
              decoration: BoxDecoration(
                color: AppColors.primary.withValues(alpha: 0.12),
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Icon(
                Icons.accessibility_new_rounded,
                size: 16,
                color: AppColors.primary,
              ),
            ),
            const SizedBox(width: 8),
            Text(
              'Acessibilidade',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 14,
                color: context.textPrimary,
              ),
            ),
          ],
        ),
        InkWell(
          borderRadius: BorderRadius.circular(20),
          onTap: () => setState(() => _expanded = false),
          child: Padding(
            padding: const EdgeInsets.all(4),
            child: Icon(Icons.close_rounded, size: 18, color: context.textMuted),
          ),
        ),
      ],
    );
  }

  Widget _buildLinhaFonte(
    BuildContext context,
    FontScaleController fontScale,
  ) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Row(
          children: [
            Icon(
              Icons.format_size_rounded,
              size: 18,
              color: context.textMuted,
            ),
            const SizedBox(width: 8),
            Text(
              'Tamanho da fonte',
              style: TextStyle(fontSize: 13, color: context.textPrimary),
            ),
          ],
        ),
        Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            _botaoFonte(
              context,
              icon: Icons.remove_rounded,
              tooltip: 'Diminuir fonte',
              onPressed: fontScale.scale <= FontScaleController.minScale
                  ? null
                  : fontScale.decrease,
            ),
            SizedBox(
              width: 38,
              child: Text(
                '${(fontScale.scale * 100).round()}%',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                  color: context.textPrimary,
                ),
              ),
            ),
            _botaoFonte(
              context,
              icon: Icons.add_rounded,
              tooltip: 'Aumentar fonte',
              onPressed: fontScale.scale >= FontScaleController.maxScale
                  ? null
                  : fontScale.increase,
            ),
          ],
        ),
      ],
    );
  }

  Widget _botaoFonte(
    BuildContext context, {
    required IconData icon,
    required String tooltip,
    required VoidCallback? onPressed,
  }) {
    final habilitado = onPressed != null;
    return Tooltip(
      message: tooltip,
      child: InkWell(
        borderRadius: BorderRadius.circular(16),
        onTap: onPressed,
        child: Container(
          width: 28,
          height: 28,
          alignment: Alignment.center,
          decoration: BoxDecoration(
            color: habilitado
                ? AppColors.primary.withValues(alpha: 0.12)
                : Colors.transparent,
            borderRadius: BorderRadius.circular(16),
          ),
          child: Icon(
            icon,
            size: 16,
            color: habilitado ? AppColors.primary : context.borderColor,
          ),
        ),
      ),
    );
  }

  Widget _buildLinhaControle(
    BuildContext context, {
    required IconData icon,
    required String label,
    required bool value,
    required ValueChanged<bool> onChanged,
  }) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Row(
          children: [
            Icon(icon, size: 18, color: context.textMuted),
            const SizedBox(width: 8),
            Text(
              label,
              style: TextStyle(fontSize: 13, color: context.textPrimary),
            ),
          ],
        ),
        Transform.scale(
          scale: 0.85,
          child: Switch(
            value: value,
            onChanged: onChanged,
            activeColor: AppColors.primary,
          ),
        ),
      ],
    );
  }

  Widget _panelSurface(BuildContext context, {required Widget child}) {
    return Material(
      color: context.cardBg,
      elevation: 8,
      borderRadius: BorderRadius.circular(18),
      child: Container(
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(18),
          border: Border.all(color: context.borderColor),
        ),
        child: child,
      ),
    );
  }
}
