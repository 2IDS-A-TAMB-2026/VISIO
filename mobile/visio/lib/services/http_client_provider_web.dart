import 'package:http/browser_client.dart' as browser;
import 'package:http/http.dart' as http;

/// Flutter Web: cliente com credenciais automáticas (equivalente a
/// `fetch(url, {credentials: 'include'})`). Isso faz o NAVEGADOR guardar e
/// reenviar o cookie PHPSESSID sozinho a cada requisição para o mesmo
/// backend, sem o app Dart precisar ler ou escrever o header
/// Set-Cookie/Cookie manualmente (o que é bloqueado por padrão do
/// navegador em qualquer situação, não só cross-origin).
///
/// NOTA DE VERIFICAÇÃO: `package:http/browser_client.dart` e a propriedade
/// `withCredentials` existem de forma estável há várias versões do pacote
/// `http`, mas este ambiente não tem acesso ao Flutter/Dart SDK nem ao
/// pub.dev para compilar e confirmar contra a versão exata instalada no seu
/// projeto (`http: ^1.6.0` no pubspec.yaml). Rode `flutter pub get` +
/// `flutter build web` (ou `flutter run -d chrome`) para validar. Se o
/// import abaixo não for encontrado nessa versão específica do pacote, o
/// próprio erro do analisador Dart vai indicar o caminho/API correta a
/// usar no lugar — a lógica (withCredentials = true) é o que importa.
http.Client criarHttpClient() {
  final cliente = browser.BrowserClient();
  cliente.withCredentials = true;
  return cliente;
}
