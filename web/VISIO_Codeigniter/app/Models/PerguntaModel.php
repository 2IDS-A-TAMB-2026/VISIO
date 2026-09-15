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
     * Conta quantas perguntas existem para um determinado nível de dificuldade.
     * Usado no dashboard administrativo (card "Questões difíceis").
     */
    public function contarPorNivel(string $nivel): int
    {
        return $this->where('NIVEL_DIFICULDADE', $nivel)->countAllResults();
    }

    /**
     * Busca uma pergunta específica e anexa as suas alternativas
     * Usado no QuizController
     */
    public function buscarComAlternativas($idPergunta)
    {
        $pergunta = $this->find($idPergunta);

        if ($pergunta) {
            // CORRIGIDO — bug relatado pelo usuário testando o app:
            // "TypeError: '53': type 'String' is not a subtype of type
            // 'int'" na tela de Quiz.
            //
            // Causa raiz: o driver do banco (MySQLi) devolve colunas
            // numéricas como STRING em PHP (ex.: '53' em vez de 53) a
            // menos que sejam convertidas explicitamente. Sem essa
            // conversão, o JSON saía com "ID_ALTERNATIVA": "53" (texto)
            // em vez de "ID_ALTERNATIVA": 53 (número) — e questoes.dart
            // (Flutter) faz um cast direto pra int
            // (alt['ID_ALTERNATIVA'] as int), que quebra ao receber uma
            // string em vez de um número.
            $pergunta['ID_PERGUNTA'] = (int) $pergunta['ID_PERGUNTA'];

            $alternativas = $this->db->table('ALTERNATIVA')
                ->where('FK_ID_PERGUNTA', $idPergunta)
                ->get()
                ->getResultArray();

            foreach ($alternativas as &$alt) {
                $alt['ID_ALTERNATIVA'] = (int) $alt['ID_ALTERNATIVA'];
                $alt['FK_ID_PERGUNTA'] = (int) $alt['FK_ID_PERGUNTA'];
                $alt['IS_CORRETA'] = (int) $alt['IS_CORRETA'];
            }
            unset($alt);

            $pergunta['alternativas'] = $alternativas;
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
            // Mesma correção de tipos de buscarComAlternativas() acima.
            $p['ID_PERGUNTA'] = (int) $p['ID_PERGUNTA'];

            $alternativas = $this->db->table('ALTERNATIVA')
                ->where('FK_ID_PERGUNTA', $p['ID_PERGUNTA'])
                ->get()
                ->getResultArray();

            foreach ($alternativas as &$alt) {
                $alt['ID_ALTERNATIVA'] = (int) $alt['ID_ALTERNATIVA'];
                $alt['FK_ID_PERGUNTA'] = (int) $alt['FK_ID_PERGUNTA'];
                $alt['IS_CORRETA'] = (int) $alt['IS_CORRETA'];
            }
            unset($alt);

            $p['alternativas'] = $alternativas;
        }
        unset($p);

        return $perguntas;
    }
}