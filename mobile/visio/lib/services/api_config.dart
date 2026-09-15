class ApiConfig {
  ApiConfig._();

  static const String baseUrl = 'http://10.141.130.113/VISIO_Codeigniter/public/index.php/api';

  static String? resolverUrlImagem(String? caminho) {
    if (caminho == null || caminho.trim().isEmpty) return null;

    final valor = caminho.trim();

    if (valor.startsWith('http://') || valor.startsWith('https://')) {
      return valor;
    }

    if (valor.startsWith('assets/')) {
      return valor;
    }

    // CORRIGIDO (imagens de sensores e foto de perfil não apareciam no
    // MOBILE): antes, esta função montava o caminho estático direto
    // (ex.: http://10.141.130.113/VISIO_Codeigniter/public/uploads/...).
    // Em Flutter Web (flutter run -d web-server --web-hostname 10.141.130.113
    // --web-port 5000), isso faz o navegador tratar a imagem como
    // cross-origin (10.141.130.113:5000 vs 10.141.130.113:80) e o CanvasKit
    // bloqueia por falta de cabeçalho CORS — um arquivo estático puro
    // nunca passa pelo filtro cors do CodeIgniter, que só roda em
    // requisições roteadas por index.php.
    //
    // Agora a URL passa pela rota api/imagem/(:any), que já está dentro
    // do grupo 'api' do CodeIgniter e herda o filtro cors global — sem
    // duplicar o arquivo nem exigir nenhuma mudança na WEB, que continua
    // usando o caminho estático normalmente.
    var caminhoRelativo = valor;
    while (caminhoRelativo.startsWith('/')) {
      caminhoRelativo = caminhoRelativo.substring(1);
    }

    return '$baseUrl/imagem/$caminhoRelativo';
  }
}
