<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PerguntaModel
 * Responsável por todas as operações da tabela PERGUNTA.
 * Inclui agrupamento com ALTERNATIVA quando necessário.
 */
class PerguntaModel extends Model
{
    protected $table = 'PERGUNTA';
    protected $primaryKey = 'ID_PERGUNTA';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'DESCRICAO',
        'NIVEL_DIFICULDADE',
    ];

    public function listarAleatorio($limite = 10)
    {
        return $this->orderBy('RAND()')->findAll($limite);
    }

    /**
     * Busca uma pergunta específica e anexa as suas alternativas
     * Usado no QuizController
     */
    public function buscarComAlternativas($idPergunta)
    {
        $pergunta = $this->find($idPergunta);

        if ($pergunta) {
            $pergunta['alternativas'] = $this->db->table('ALTERNATIVA')
                ->where('FK_ID_PERGUNTA', $idPergunta)
                ->get()
                ->getResultArray();
        }

        return $pergunta;
    }

    /**
     * Lista todas as perguntas e anexa as suas alternativas
     * Usado no AdminController para gerir as questões
     */
    public function listarComAlternativas()
    {
        $perguntas = $this->findAll();

        foreach ($perguntas as &$p) {
            $p['alternativas'] = $this->db->table('ALTERNATIVA')
                ->where('FK_ID_PERGUNTA', $p['ID_PERGUNTA'])
                ->get()
                ->getResultArray();
        }

        return $perguntas;
    }
}