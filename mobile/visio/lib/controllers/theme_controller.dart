import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

/// Controla o tema (claro/escuro) usado em toda a aplicação e persiste a
/// escolha do usuário entre execuções através do [SharedPreferences].
///
/// Este controller é registrado uma única vez, na raiz do app (veja
/// `main.dart`), através de um `ChangeNotifierProvider`. Qualquer tela pode
/// ler o tema atual ou solicitar a troca sem precisar receber esse estado
/// por parâmetro e sem duplicar a lógica de persistência — basta chamar
/// `context.watch<ThemeController>()` ou `context.read<ThemeController>()`.
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

  /// Alterna entre claro e escuro.
  Future<void> toggleTheme() async {
    await setThemeMode(
      _themeMode == ThemeMode.dark ? ThemeMode.light : ThemeMode.dark,
    );
  }

  /// Define explicitamente o tema e persiste a escolha.
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
