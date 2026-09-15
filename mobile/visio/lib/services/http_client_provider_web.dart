import 'package:http/browser_client.dart' as browser;
import 'package:http/http.dart' as http;
http.Client criarHttpClient() {
  final cliente = browser.BrowserClient();
  cliente.withCredentials = true;
  return cliente;
}
