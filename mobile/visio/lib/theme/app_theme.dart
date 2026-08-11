import 'package:flutter/material.dart';

import '../appcolor.dart';

/// Centraliza a definição de todos os temas da aplicação (claro e escuro).
///
/// Antes desta refatoração, o `ThemeData` era construído diretamente dentro
/// de `MyApp`, contemplando apenas a variação escura. Ao mover essa
/// construção para esta classe foi possível adicionar o tema claro e
/// alternar entre os dois em um único lugar (veja [ThemeController]), sem
/// exigir nenhuma alteração nas telas que já utilizam `Theme.of(context)`
/// ou os componentes padrão do Flutter (AppBar, ElevatedButton, Card,
/// SnackBar, etc.) — eles passam a responder à troca de tema
/// automaticamente.
///
/// Observação importante: widgets que usam cores fixas de [AppColors]
/// diretamente (ex.: `AppColors.bgCardAlt`) em vez de `Theme.of(context)`
/// não mudam de cor ao trocar de tema, pois essas constantes não dependem
/// do brightness atual. Isso é esperado e não é alterado por este arquivo;
/// para que uma tela específica siga o tema, ela deve ler as cores via
/// `Theme.of(context).colorScheme` (como fizemos em [AccessibilityPanel]).
class AppTheme {
  AppTheme._();

  static ThemeData get dark => _base(
        brightness: Brightness.dark,
        scaffoldBg: AppColors.bgBase,
        surface: AppColors.surfaceDark,
        cardBg: AppColors.bgCardAlt,
        textPrimary: AppColors.textPrimary,
        textMuted: AppColors.textMuted,
        border: AppColors.border,
      );

  static ThemeData get light => _base(
        brightness: Brightness.light,
        scaffoldBg: AppColors.bgBaseLight,
        surface: AppColors.surfaceLight,
        cardBg: AppColors.bgCardAltLight,
        textPrimary: AppColors.textPrimaryLight,
        textMuted: AppColors.textMutedLight,
        border: AppColors.borderLight,
      );

  static ThemeData _base({
    required Brightness brightness,
    required Color scaffoldBg,
    required Color surface,
    required Color cardBg,
    required Color textPrimary,
    required Color textMuted,
    required Color border,
  }) {
    final isDark = brightness == Brightness.dark;
    return ThemeData(
      brightness: brightness,
      scaffoldBackgroundColor: scaffoldBg,
      primaryColor: AppColors.primary,
      colorScheme: isDark
          ? const ColorScheme.dark(
              primary: AppColors.primary,
              surface: AppColors.surfaceDark,
            )
          : ColorScheme.light(primary: AppColors.primary, surface: surface),
      appBarTheme: AppBarTheme(
        backgroundColor: surface,
        elevation: 0,
        centerTitle: false,
        titleTextStyle: TextStyle(
          color: textPrimary,
          fontSize: 18,
          fontWeight: FontWeight.bold,
        ),
        iconTheme: IconThemeData(color: textPrimary),
      ),
      bottomNavigationBarTheme: BottomNavigationBarThemeData(
        backgroundColor: surface,
        selectedItemColor: AppColors.primary,
        unselectedItemColor: textMuted,
        type: BottomNavigationBarType.fixed,
        elevation: 12,
        selectedLabelStyle: const TextStyle(
          fontSize: 11,
          fontWeight: FontWeight.w600,
        ),
        unselectedLabelStyle: const TextStyle(fontSize: 11),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: cardBg,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(10),
          borderSide: BorderSide(color: border),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(10),
          borderSide: BorderSide(color: border),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(10),
          borderSide: const BorderSide(color: AppColors.primary, width: 2),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(10),
          borderSide: const BorderSide(color: AppColors.danger),
        ),
        focusedErrorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(10),
          borderSide: const BorderSide(color: AppColors.danger, width: 2),
        ),
        labelStyle: TextStyle(color: textMuted),
        hintStyle: TextStyle(color: textMuted, fontSize: 14),
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 16,
          vertical: 14,
        ),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.primary,
          foregroundColor: AppColors.textPrimary,
          padding: const EdgeInsets.symmetric(vertical: 14),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(10),
          ),
          textStyle: const TextStyle(
            fontSize: 15,
            fontWeight: FontWeight.bold,
          ),
          elevation: 0,
        ),
      ),
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          side: const BorderSide(color: AppColors.primary),
          foregroundColor: AppColors.primary,
          padding: const EdgeInsets.symmetric(vertical: 14),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(10),
          ),
          textStyle: const TextStyle(
            fontSize: 15,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
      cardTheme: CardThemeData(
        color: cardBg,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
          side: BorderSide(color: border, width: 0.5),
        ),
        elevation: 0,
        margin: EdgeInsets.zero,
      ),
      dividerTheme: DividerThemeData(color: border, thickness: 0.5),
      snackBarTheme: SnackBarThemeData(
        backgroundColor: surface,
        contentTextStyle: TextStyle(color: textPrimary),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        behavior: SnackBarBehavior.floating,
      ),
    );
  }
}
