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

  static const Color bgBaseLight = Color(0xFFF5F6FA);
  static const Color bgCardAltLight = Color(0xFFFFFFFF);
  static const Color surfaceLight = Color(0xFFFFFFFF);
  static const Color textPrimaryLight = Color(0xFF121212);
  static const Color textMutedLight = Color(0xFF5A5A5A);
  static const Color textSoftLight = Color(0xFF46536B);
  static const Color borderLight = Color(0xFFE0E0E0);
}

extension AppColorsContext on BuildContext {
  bool get _isDarkMode => Theme.of(this).brightness == Brightness.dark;

  Color get bgBase => _isDarkMode ? AppColors.bgBase : AppColors.bgBaseLight;

  Color get cardBg =>
      _isDarkMode ? AppColors.bgCardAlt : AppColors.bgCardAltLight;

  Color get surface =>
      _isDarkMode ? AppColors.surfaceDark : AppColors.surfaceLight;

  Color get textPrimary =>
      _isDarkMode ? AppColors.textPrimary : AppColors.textPrimaryLight;

  Color get textMuted =>
      _isDarkMode ? AppColors.textMuted : AppColors.textMutedLight;

  Color get textSoft =>
      _isDarkMode ? AppColors.textSoft : AppColors.textSoftLight;

  Color get borderColor =>
      _isDarkMode ? AppColors.border : AppColors.borderLight;
}
