import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';


class FontScaleController extends ChangeNotifier {
  static const _prefsKey = 'app_font_scale';

  static const double minScale = 0.8; 
  static const double maxScale = 1.1; 
  static const double step = 0.1; //% de aumento
  static const double defaultScale = 1.0; //padrão

  double _scale = defaultScale;

  FontScaleController() {
    _loadFromPrefs();
  }

  double get scale => _scale;

  Future<void> _loadFromPrefs() async {
    final prefs = await SharedPreferences.getInstance();
    final saved = prefs.getDouble(_prefsKey);
    if (saved != null) {
      _scale = saved.clamp(minScale, maxScale);
      notifyListeners();
    }
  }

  Future<void> increase() => _setScale(_scale + step);

  Future<void> decrease() => _setScale(_scale - step);

  Future<void> reset() => _setScale(defaultScale);

  Future<void> _setScale(double value) async {
    final clamped = double.parse(
      value.clamp(minScale, maxScale).toStringAsFixed(2),
    );
    if (clamped == _scale) return;
    _scale = clamped;
    notifyListeners();
    final prefs = await SharedPreferences.getInstance();
    await prefs.setDouble(_prefsKey, _scale);
  }
}
