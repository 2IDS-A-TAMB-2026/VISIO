<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * RespondeModel
 * Responsável por todas as operações da tabela RESPONDE.
 * Registra o histórico de respostas dos usuários no quiz.
 */
class RespondeModel extends Model
{
    protected $table = 'RESPONDE';
    protected $primaryKey = 'ID_RESPONDE';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'FK_CPF_USUARIO',
        'FK_ID_ALTERNATIVA',
        // RESPONDIDO_EM é preenchido automaticamente pelo banco de dados
    ];

    /**
     * Registra uma nova resposta no banco de dados
     * Usado no QuizController
     */
    public function registrar($cpf, $idAlternativa)
    {
        return $this->insert([
            'FK_CPF_USUARIO' => $cpf,
            'FK_ID_ALTERNATIVA' => $idAlternativa
        ]);
    }

    /**
     * Traz o histórico detalhado de todos os usuários
     * Usado no AdminController
     */
    public function listarComDetalhes()
    {
        return $this->db->table('RESPONDE r')
            ->select('
                r.ID_RESPONDE,
                r.RESPONDIDO_EM,
                u.CPF AS USUARIO_CPF,
                u.EMAIL AS USUARIO_EMAIL,
                p.DESCRICAO AS PERGUNTA_TEXTO,
                a.DESCRICAO AS ALTERNATIVA_TEXTO,
                a.IS_CORRETA
            ')
            ->join('USUARIO u', 'u.CPF = r.FK_CPF_USUARIO')
            ->join('ALTERNATIVA a', 'a.ID_ALTERNATIVA = r.FK_ID_ALTERNATIVA')
            ->join('PERGUNTA p', 'p.ID_PERGUNTA = a.FK_ID_PERGUNTA')
            ->orderBy('r.RESPONDIDO_EM', 'DESC')
            ->get()
            ->getResultArray();
    }
}