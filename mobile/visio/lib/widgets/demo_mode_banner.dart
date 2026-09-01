import 'package:flutter/material.dart';
import '../appcolor.dart';

/// Aviso visível de que a tela em questão não está de fato validando dados
/// contra nenhum backend — usado nas telas de login enquanto
/// [AuthService.isDemoMode] for true. Ver `services/auth_service.dart`.
class DemoModeBanner extends StatelessWidget {
  final String mensagem;

  const DemoModeBanner({
    super.key,
    this.mensagem =
        'Modo demonstração: este login não verifica credenciais reais.',
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
      decoration: BoxDecoration(
        color: AppColors.warning.withValues(alpha: 0.12),
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: AppColors.warning.withValues(alpha: 0.4)),
      ),
      child: Row(
        children: [
          const Icon(Icons.info_outline, size: 18, color: AppColors.warning),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              mensagem,
              style: const TextStyle(fontSize: 12, color: AppColors.warning),
            ),
          ),
        ],
      ),
    );
  }
}
