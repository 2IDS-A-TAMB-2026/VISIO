<?php

namespace App\Controllers\api_controller;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PerguntaModel;
use App\Models\AlternativaModel;
use App\Models\RespondeModel;

class APIQuizController extends ResourceController
{
    protected $format = 'json';

    // GET /api/quiz/perguntas
    public function perguntas()
    {
        $perguntaModel    = new PerguntaModel();
        $alternativaModel = new AlternativaModel();

        $perguntas = $perguntaModel->findAll();

        if (empty($perguntas)) {
            return $this->failNotFound('Nenhuma pergunta cadastrada no sistema.');
        }

        foreach ($perguntas as &$pergunta) {
            // PREVENTIVO: mesma causa raiz do bug relatado no Quiz (ver
            // PerguntaModel::buscarComAlternativas) — faltava aqui também.
            $pergunta['ID_PERGUNTA'] = (int) $pergunta['ID_PERGUNTA'];

            // Busca usando as colunas reais do Model/Banco
            $alternativas = $alternativaModel
                ->where('FK_ID_PERGUNTA', $pergunta['ID_PERGUNTA'])
                ->findAll();

            // ENCONTRADO NA AUDITORIA: este endpoint devolvia IS_CORRETA
            // junto de cada alternativa, ou seja, a resposta certa ficava
            // visível no JSON antes de o usuário responder. QuizController
            // ::pergunta() (fluxo web) já tinha sido corrigido para remover
            // isso, com um comentário explicando exatamente esse motivo —
            // a mesma correção nunca tinha chegado a este endpoint, que é
            // o usado por requisições JSON (app/Flutter).
            $pergunta['alternativas'] = array_map(function ($alt) {
                return [
                    'ID_ALTERNATIVA' => (int) $alt['ID_ALTERNATIVA'],
                    'DESCRICAO'      => $alt['DESCRICAO'],
                ];
            }, $alternativas);
        }

        return $this->respond([
            'status'   => 200,
            'erro'     => false,
            'total'    => count($perguntas),
            'mensagem' => 'Perguntas do quiz recuperadas com sucesso.',
            'dados'    => $perguntas
        ], 200);
    }

    // GET /api/quiz/perguntas/(:num)
    public function showPergunta($id = null)
    {
        $perguntaModel    = new PerguntaModel();
        $alternativaModel = new AlternativaModel();

        $pergunta = $perguntaModel->find($id);

        if (!$pergunta) {
            return $this->failNotFound("Pergunta com ID {$id} não foi encontrada.");
        }

        $alternativas = $alternativaModel
            ->where('FK_ID_PERGUNTA', $id)
            ->findAll();

        // Mesmo motivo de perguntas() acima: não expor a resposta certa
        // antes de o usuário responder.
        $pergunta['alternativas'] = array_map(function ($alt) {
            return [
                'ID_ALTERNATIVA' => (int) $alt['ID_ALTERNATIVA'],
                'DESCRICAO'      => $alt['DESCRICAO'],
            ];
        }, $alternativas);

        return $this->respond([
            'status'   => 200,
            'erro'     => false,
            'mensagem' => 'Pergunta localizada com sucesso.',
            'dados'    => $pergunta
        ], 200);
    }

    // POST /api/quiz/responder
    //
    // ENCONTRADO NA AUDITORIA (não estava na lista de erros, mas é grave):
    // esta rota gravava a resposta com as chaves ID_USUARIO, ID_PERGUNTA e
    // ACERTOU. Nenhuma delas existe na tabela RESPONDE nem em
    // RespondeModel::$allowedFields (que só permite FK_CPF_USUARIO e
    // FK_ID_ALTERNATIVA) — o Model do CodeIgniter descarta silenciosamente
    // qualquer chave fora de $allowedFields por proteção contra mass
    // assignment, então o insert() tentava gravar sem FK_CPF_USUARIO nem
    // FK_ID_ALTERNATIVA (ambas NOT NULL, sem default) e falhava sempre —
    // enquanto a resposta HTTP dizia "sucesso" de qualquer forma, porque o
    // retorno de insert() nunca era conferido. Ou seja: toda resposta de
    // quiz enviada por este endpoint (o usado pelo app/API, diferente do
    // fluxo web em QuizController) parecia funcionar mas nunca era
    // realmente salva. Isso é um candidato forte para o erro 5 (site
    // travando), se for este o caminho que o Flutter usa para responder.
    //
    // Também corrigido: a identidade do usuário agora vem da sessão PHP
    // (session()->get('usuario_cpf')), no mesmo padrão já usado em
    // UsuarioController::perfil()/atualizarPerfil() — antes vinha de
    // ID_USUARIO enviado livremente pelo cliente no corpo da requisição,
    // ou seja, qualquer chamada podia registrar uma resposta em nome de
    // qualquer usuário só informando outro ID.
    public function responder()
    {
        $cpf = session()->get('usuario_cpf');

        if (empty($cpf)) {
            return $this->failUnauthorized('Usuário não autenticado.');
        }

        $json = $this->request->getJSON(true) ?? $this->request->getPost();

        $idPergunta    = $json['ID_PERGUNTA'] ?? $json['id_pergunta'] ?? null;
        $idAlternativa = $json['ID_ALTERNATIVA'] ?? $json['id_alternativa'] ?? null;

        if (!$idPergunta || !$idAlternativa) {
            return $this->failValidationErrors([
                'mensagem' => 'Os campos ID_PERGUNTA e ID_ALTERNATIVA são obrigatórios.'
            ]);
        }

        $alternativaModel = new AlternativaModel();
        $respondeModel    = new RespondeModel();

        $alternativaEscolhida = $alternativaModel->find($idAlternativa);

        // Valida o relacionamento usando FK_ID_PERGUNTA
        if (!$alternativaEscolhida || (int)$alternativaEscolhida['FK_ID_PERGUNTA'] !== (int)$idPergunta) {
            return $this->failNotFound('A alternativa informada não pertence a esta pergunta.');
        }

        // Busca a correta através do campo IS_CORRETA
        $alternativaCorreta = $alternativaModel
            ->where('FK_ID_PERGUNTA', $idPergunta)
            ->where('IS_CORRETA', 1)
            ->first();

        $acertou = ((int)$alternativaEscolhida['IS_CORRETA'] === 1);

        if (!$respondeModel->registrar($cpf, (int) $idAlternativa)) {
            return $this->failServerError('Não foi possível registrar a resposta.');
        }

        return $this->respond([
            'status'   => 200,
            'erro'     => false,
            'mensagem' => $acertou ? 'Resposta correta!' : 'Resposta incorreta.',
            'dados'    => [
                'acertou'     => $acertou,
                'escolhida'   => (int)$idAlternativa,
                'correta_id'  => $alternativaCorreta ? (int)$alternativaCorreta['ID_ALTERNATIVA'] : null,
                'id_pergunta' => (int)$idPergunta,
                'cpf'         => $cpf
            ]
        ], 200);
    }
}