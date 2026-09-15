<?php

namespace App\Controllers\api_controller;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UsuarioModel;
use App\Models\LoginCartaoModel;

/**
 * APICartaoController
 *
 * ADICIONADO (item 8/9 do pedido — login automático por RFID).
 *
 * Endpoint chamado PELO ESP32 (não pelo navegador) sempre que um
 * cartão é aproximado do leitor MFRC522. Responsabilidade única:
 * receber o UID, procurar em USUARIO.CARTAO e responder um resultado
 * que o firmware consegue interpretar sem ambiguidade (nunca tratando
 * um HTTP 2xx genérico como "login realizado" — a semântica está no
 * corpo da resposta, não só no status HTTP).
 *
 * Este endpoint NÃO cria a sessão do navegador diretamente — não tem
 * como, já que o ESP32 e o navegador são clientes HTTP separados, sem
 * cookie em comum. Ele só registra em LOGIN_CARTAO que o usuário foi
 * autorizado; quem efetivamente cria a sessão do navegador é
 * UsuarioController::loginPorCartao, chamado pelo POLLING da própria
 * tela de login (ver comentário completo em LoginCartaoModel.php).
 *
 * Rota: POST api/cartao/verificar (dentro do grupo 'api', que já
 * aplica o filtro global 'cors' — irrelevante para o ESP32 em si, que
 * não é um navegador, mas não atrapalha nada mantê-lo).
 */
class APICartaoController extends ResourceController
{
    protected $format = 'json';

    public function verificar()
    {
        $corpo = $this->request->getJSON(true) ?? [];
        $uidBruto = $corpo['uid'] ?? null;

        if (empty($uidBruto) || !is_string($uidBruto)) {
            return $this->respond([
                'success'       => false,
                'authenticated' => false,
                'message'       => 'UID do cartão não informado ou em formato inválido.',
            ], 400);
        }

        $uid = UsuarioModel::normalizarUid($uidBruto);

        if ($uid === '') {
            return $this->respond([
                'success'       => false,
                'authenticated' => false,
                'message'       => 'UID do cartão inválido.',
            ], 400);
        }

        try {
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->buscarPorCartao($uid);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao consultar cartão RFID: {msg}', ['msg' => $e->getMessage()]);

            // Erro interno do servidor — diferente de "cartão não
            // cadastrado". O ESP32 deve tratar isso como falha de
            // comunicação/servidor, nunca como acesso autorizado nem
            // como recusa definitiva do cartão.
            return $this->failServerError('Erro interno ao verificar o cartão.');
        }

        if (!$usuario) {
            // Cartão não cadastrado — recusa explícita, nunca um erro
            // silencioso nem um HTTP 2xx ambíguo.
            return $this->respond([
                'success'       => false,
                'authenticated' => false,
                'message'       => 'Cartão não cadastrado.',
            ], 200);
        }

        try {
            $loginCartaoModel = new LoginCartaoModel();
            $loginCartaoModel->registrarAutorizacao($usuario['CPF']);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao registrar autorização de cartão RFID: {msg}', ['msg' => $e->getMessage()]);

            return $this->failServerError('Erro interno ao processar a autorização do cartão.');
        }

        // Cartão autorizado. O login automático em si só se completa
        // quando o navegador que está com o polling ativo em
        // login/cartao consumir este registro (ver LoginCartaoModel).
        return $this->respond([
            'success'       => true,
            'authenticated' => true,
            'message'       => 'Cartão autorizado.',
        ], 200);
    }
}
