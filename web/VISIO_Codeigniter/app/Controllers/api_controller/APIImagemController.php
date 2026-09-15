<?php

namespace App\Controllers\api_controller;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;

/**
 * APIImagemController
 *
 * CAUSA RAIZ CORRIGIDA (imagens de sensores e foto de perfil não aparecem
 * no MOBILE, item 3+4 do pedido):
 *
 * As imagens de uploads/perfil e uploads/sensores são arquivos físicos
 * dentro de public/, então o Apache/servidor embutido do PHP os entrega
 * diretamente pelo caminho estático — essa requisição nunca passa pelo
 * index.php do CodeIgniter e, portanto, nunca passa pelo filtro global
 * 'cors' (App\Filters\Cors, ver Config/Filters.php). O filtro cors só
 * roda em requisições roteadas pelo framework (ex.: tudo dentro de
 * api/...).
 *
 * O Flutter Web (flutter run -d web-server --web-hostname localhost
 * --web-port 5000) é servido em http://localhost:5000, enquanto o
 * backend PHP roda em http://localhost (porta 80) — mesmo host, porta
 * diferente, o que para o navegador já conta como origem diferente.
 * O motor de renderização web do Flutter (CanvasKit) carrega
 * NetworkImage via fetch() sujeito a CORS — diferente de uma tag <img>
 * HTML pura — então, sem o cabeçalho Access-Control-Allow-Origin, o
 * navegador bloqueia a imagem. É por isso que a foto aparece
 * normalmente na página WEB (mesma origem do servidor) mas nunca no
 * Flutter Web.
 *
 * Esta rota resolve o problema sem duplicar arquivo nem mexer na WEB:
 * ela serve a MESMA imagem física, só que roteada pelo CodeIgniter, e
 * portanto passa pelo filtro global 'cors' normalmente, herdando os
 * cabeçalhos CORS automaticamente — não precisei repetir a lógica de
 * Access-Control-* aqui.
 *
 * A WEB continua podendo usar base_url($sensor['FOTO']) direto (caminho
 * estático) sem qualquer alteração — só o MOBILE passa a usar esta rota
 * (ver ApiConfig.resolverUrlImagem no Flutter).
 */
class APIImagemController extends BaseController
{
    // Único diretório físico de onde este endpoint tem permissão de ler.
    // Qualquer caminho fora daqui é recusado (proteção contra path
    // traversal, ex.: ../../.env).
    private const DIRETORIO_BASE = 'uploads';

    // GET api/imagem/(:any)
    //
    // IMPORTANTE: por padrão, quando o valor capturado por (:any) contém
    // barras (ex.: "uploads/perfil/abc123.png"), o CodeIgniter NÃO passa
    // isso como uma única string — ele divide em um parâmetro por
    // segmento (aqui: $params = ['uploads', 'perfil', 'abc123.png']).
    // Por isso o método é variádico em vez de receber um único
    // `string $caminho` (que quebraria com múltiplos argumentos assim
    // que o path tivesse mais de um nível).
    public function servir(string ...$segmentos)
    {
        $caminhoRelativo = trim(implode('/', $segmentos), '/');

        if ($caminhoRelativo === '') {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Caminho da imagem não informado.',
            ]);
        }

        // Bloqueia qualquer tentativa de sair do diretório uploads/
        // (ex.: "..", caminho absoluto) antes mesmo de tocar no disco.
        if (
            str_contains($caminhoRelativo, '..')
            || !str_starts_with($caminhoRelativo, self::DIRETORIO_BASE . '/')
        ) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Caminho de imagem inválido.',
            ]);
        }

        $caminhoAbsoluto = realpath(ROOTPATH . 'public/' . $caminhoRelativo);
        $raizPermitida = realpath(ROOTPATH . 'public/' . self::DIRETORIO_BASE);

        // realpath() resolve os "..", então esta segunda checagem (com o
        // caminho já resolvido pelo sistema de arquivos) é a que
        // realmente impede escapar de public/uploads/.
        if (
            $caminhoAbsoluto === false
            || $raizPermitida === false
            || !str_starts_with($caminhoAbsoluto, $raizPermitida)
            || !is_file($caminhoAbsoluto)
        ) {
            return $this->response->setStatusCode(404)->setJSON([
                'message' => 'Imagem não encontrada.',
            ]);
        }

        $arquivo = new File($caminhoAbsoluto);

        return $this->response
            ->setContentType($arquivo->getMimeType())
            ->setHeader('Cache-Control', 'public, max-age=3600')
            ->setBody(file_get_contents($caminhoAbsoluto));
    }
}
