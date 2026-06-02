<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UsuarioModel
 * Responsável por todas as operações da tabela USUARIO (Sem o campo NOME).
 * Chave primária: CPF (string, sem auto-incremento).
 */
class UsuarioModel extends Model
{
    protected $table = 'USUARIO';
    protected $primaryKey = 'CPF';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';

    protected $allowedFields = [
        'CPF',
        'EMAIL',
        'SENHA',
        'CARTAO',
        'DATA_NASCIMENTO',
        'TELEFONE',
    ];

    /**
     * Busca um usuário pelo e-mail para o sistema de login.
     */
    public function buscarPorEmail(string $email)
    {
        return $this->where('EMAIL', $email)->first();
    }

    /**
     * Cria o histórico de respostas do usuário cruzando as tabelas com JOIN.
     * Esse método é o que alimenta a rota /usuario/historico
     */
    public function historicoPorCpf(string $cpf): array
    {
        return $this->db->table('RESPONDE')
            ->select('
                RESPONDE.ID_RESPONDE, 
                RESPONDE.RESPONDIDO_EM, 
                PERGUNTA.DESCRICAO AS PERGUNTA_TEXTO, 
                PERGUNTA.NIVEL_DIFICULDADE,
                ALTERNATIVA.DESCRICAO AS ALTERNATIVA_TEXTO, 
                ALTERNATIVA.IS_CORRETA
            ')
            ->join('ALTERNATIVA', 'ALTERNATIVA.ID_ALTERNATIVA = RESPONDE.FK_ID_ALTERNATIVA')
            ->join('PERGUNTA', 'PERGUNTA.ID_PERGUNTA = ALTERNATIVA.FK_ID_PERGUNTA')
            ->where('RESPONDE.FK_CPF_USUARIO', $cpf)
            ->orderBy('RESPONDE.RESPONDIDO_EM', 'DESC')
            ->get()
            ->getResultArray();
    }
}