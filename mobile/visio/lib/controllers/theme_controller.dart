import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

class ThemeController extends ChangeNotifier {
  static const _prefsKey = 'app_theme_mode';

  ThemeMode _themeMode = ThemeMode.dark;

  ThemeController() {
    _loadFromPrefs();
  }

  ThemeMode get themeMode => _themeMode;

  bool get isDarkMode => _themeMode == ThemeMode.dark;

  Future<void> _loadFromPrefs() async {
    final prefs = await SharedPreferences.getInstance();
    final saved = prefs.getString(_prefsKey);
    if (saved == 'light') {
      _themeMode = ThemeMode.light;
    } else if (saved == 'dark') {
      _themeMode = ThemeMode.dark;
    }
    notifyListeners();
  }

  // Alterna entre claro e escuro.
  Future<void> toggleTheme() async {
    await setThemeMode(
      _themeMode == ThemeMode.dark ? ThemeMode.light : ThemeMode.dark,
    );
  }

  // Define explicitamente o tema e persiste a escolha.
  Future<void> setThemeMode(ThemeMode mode) async {
    if (_themeMode == mode) return;
    _themeMode = mode;
    notifyListeners();
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(
      _prefsKey,
      _themeMode == ThemeMode.dark ? 'dark' : 'light',
    );
  }
}
