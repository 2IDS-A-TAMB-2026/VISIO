/// Fábrica de `http.Client` sensível à plataforma.
///
/// PROBLEMA QUE ISTO RESOLVE: em builds Flutter Web, o navegador bloqueia,
/// por especificação (não é configuração de CORS), tanto a LEITURA do
/// header `Set-Cookie` de uma resposta quanto a ESCRITA manual de um header
/// `Cookie` numa requisição — em qualquer navegador, mesmo same-origin. A
/// captura manual de cookie que este app fazia (ver o histórico de
/// `auth_service.dart`) por isso nunca poderia funcionar numa build Web,
/// independente de CORS no backend.
///
/// SOLUÇÃO: no Web, usar um cliente HTTP com "credentials" automáticas
/// (equivalente a `fetch(url, {credentials: 'include'})`), para que o
/// PRÓPRIO NAVEGADOR guarde e reenvie o cookie de sessão do CodeIgniter
/// (PHPSESSID) sozinho — sem o app Dart nunca tocar no valor do cookie.
/// Em mobile/desktop nada muda: continua sendo um `http.Client()` comum e a
/// sessão continua sendo controlada manualmente por `AuthService`.
///
/// Exige que o backend responda, nas rotas usadas por este app, com:
///   Access-Control-Allow-Credentials: true
///   Access-Control-Allow-Origin: <origem exata do Flutter Web, nunca '*'>
/// (implementado em `app/Filters/Cors.php` do backend).
library;

export 'http_client_provider_io.dart'
    if (dart.library.html) 'http_client_provider_web.dart';
