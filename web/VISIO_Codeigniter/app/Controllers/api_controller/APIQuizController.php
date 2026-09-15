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

            $pergunta['ID_PERGUNTA'] = (int) $pergunta['ID_PERGUNTA'];

            // Busca usando as colunas reais do Model/Banco
            $alternativas = $alternativaModel
                ->where('FK_ID_PERGUNTA', $pergunta['ID_PERGUNTA'])
                ->findAll();

           
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