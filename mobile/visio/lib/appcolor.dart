import 'package:flutter/material.dart';

class AppColors {
  static const Color bgBase = Color(0xFF000000);
  static const Color bgCardDark = Color(0xFF020321);
  static const Color bgCardAlt = Color(0xFF121212);
  static const Color surfaceDark = Color(0xFF17182C);

  static const Color primary = Color(0xFF1E6BE7);
  static const Color primaryLight = Color(0xFF47CDFD);

  static const Color textPrimary = Color(0xFFFFFFFF);
  static const Color textMuted = Color(0xFFBBBBBB);
  static const Color textSoft = Color(0xFFCFD8E3);

  static const Color border = Color(0xFF2A2A2A);

  static const Color success = Color(0xFF22C55E);
  static const Color warning = Color(0xFFF59E0B);
  static const Color danger = Color(0xFFEF4444);

  // ---------------------------------------------------------------------
  // Variantes para o tema claro (Light Mode).
  // Adicionadas para suportar a alternância de tema em AppTheme.
  // As constantes acima (usadas pelo tema escuro) permanecem inalteradas.
  // ---------------------------------------------------------------------
  static const Color bgBaseLight = Color(0xFFF5F6FA);
  static const Color bgCardAltLight = Color(0xFFFFFFFF);
  static const Color surfaceLight = Color(0xFFFFFFFF);
  static const Color textPrimaryLight = Color(0xFFF5F6FA);
  static const Color textMutedLight = Color(0xFF121212);
  static const Color borderLight = Color(0xFFE0E0E0);
}
