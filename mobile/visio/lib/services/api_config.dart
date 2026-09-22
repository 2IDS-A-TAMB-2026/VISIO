class ApiConfig {
  ApiConfig._();

  static const String baseUrl = 'http://10.141.129.9/VISIO_Codeigniter/public/index.php/api';

  /// Raiz pública do CodeIgniter (sem 'index.php/api'), onde os uploads
  /// (perfil, sensores, admin) e as imagens em assets/images/... ficam
  /// acessíveis como arquivo estático — ex.: .../public/uploads/perfil/x.jpg
  /// ou .../public/assets/images/Sensores/ESP-32.png.
  static const String _baseUrlPublico =
      'http://10.141.129.9/VISIO_Codeigniter/public';

  static String? resolverUrlImagem(String? caminho) {
    if (caminho == null || caminho.trim().isEmpty) return null;

    final valor = caminho.trim();

    if (valor.startsWith('http://') || valor.startsWith('https://')) {
      return valor;
    }

    var caminhoRelativo = valor;
    while (caminhoRelativo.startsWith('/')) {
      caminhoRelativo = caminhoRelativo.substring(1);
    }

    return '$_baseUrlPublico/$caminhoRelativo';
  }
}
