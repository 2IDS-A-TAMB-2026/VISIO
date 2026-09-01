import 'package:http/http.dart' as http;

/// Mobile/desktop: cliente padrão. Nada de especial com credentials aqui —
/// a sessão continua sendo mantida manualmente por `AuthService`
/// (captura do header Set-Cookie, reenvio como header Cookie), exatamente
/// como já funcionava antes desta mudança.
http.Client criarHttpClient() => http.Client();
