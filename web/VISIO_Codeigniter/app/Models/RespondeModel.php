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
    protected $table            = 'RESPONDE';
    protected $primaryKey       = 'ID_RESPONDE';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'FK_CPF_USUARIO',
        'FK_ID_ALTERNATIVA',
    ];

    // ---------------------------------------------------------------
    // REGISTRAR RESPOSTA
    // Usado no QuizController::responder()
    // ---------------------------------------------------------------
    public function registrar(string $cpf, int $idAlternativa): int|bool
    {
        return $this->insert([
            'FK_CPF_USUARIO'    => $cpf,
            'FK_ID_ALTERNATIVA' => $idAlternativa,
        ]);
    }

    // ---------------------------------------------------------------
    // HISTÓRICO POR USUÁRIO (com JOIN)
    // Usado no RespostaController::historico()
    // ---------------------------------------------------------------
    public function historicoPorUsuario(string $cpf): array
    {
        $rows = $this->db->table('RESPONDE r')
            ->select('
                r.ID_RESPONDE,
                r.RESPONDIDO_EM,
                p.DESCRICAO AS PERGUNTA_TEXTO,
                p.NIVEL_DIFICULDADE,
                a.DESCRICAO AS ALTERNATIVA_TEXTO,
                a.IS_CORRETA
            ')
            ->join('ALTERNATIVA a', 'a.ID_ALTERNATIVA = r.FK_ID_ALTERNATIVA')
            ->join('PERGUNTA p', 'p.ID_PERGUNTA = a.FK_ID_PERGUNTA')
            ->where('r.FK_CPF_USUARIO', $cpf)
            ->orderBy('r.RESPONDIDO_EM', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['ID_RESPONDE'] = (int) $row['ID_RESPONDE'];
            $row['IS_CORRETA'] = (int) $row['IS_CORRETA'];
        }
        unset($row);

        return $rows;
    }

    // ---------------------------------------------------------------
    // TOTAL DE RESPOSTAS DO USUÁRIO
    // ---------------------------------------------------------------
    public function totalPorUsuario(string $cpf): int
    {
        return $this->where('FK_CPF_USUARIO', $cpf)->countAllResults();
    }

    // ---------------------------------------------------------------
    // TOTAL DE ACERTOS DO USUÁRIO
    // ---------------------------------------------------------------
    public function totalAcertosPorUsuario(string $cpf): int
    {
        return (int) $this->db->table('RESPONDE r')
            ->join('ALTERNATIVA a', 'a.ID_ALTERNATIVA = r.FK_ID_ALTERNATIVA')
            ->where('r.FK_CPF_USUARIO', $cpf)
            ->where('a.IS_CORRETA', 1)
            ->countAllResults();
    }

    // ---------------------------------------------------------------
    // ADICIONADO — TAXA MÉDIA DE ACERTOS GERAL (todas as respostas, de
    // todos os usuários, desde sempre). Usado no card "Taxa média de
    // acertos" da tela inicial do Mobile (antes era "Tipos de
    // sensores"). Mesmo padrão de perguntasPorTaxaAcerto()/
    // rankingUsuarios() logo abaixo, só que sem agrupar por pergunta
    // ou usuário — um único percentual geral.
    // ---------------------------------------------------------------
    public function taxaMediaAcertos(): float
    {
        $row = $this->db->table('RESPONDE r')
            ->select('COUNT(*) AS total, SUM(a.IS_CORRETA) AS acertos')
            ->join('ALTERNATIVA a', 'a.ID_ALTERNATIVA = r.FK_ID_ALTERNATIVA')
            ->get()
            ->getRowArray();

        $total   = (int) ($row['total'] ?? 0);
        $acertos = (int) ($row['acertos'] ?? 0);

        return $total > 0 ? round(($acertos / $total) * 100) : 0;
    }

    // ---------------------------------------------------------------
    // DESEMPENHO DE UMA JANELA DE 7 DIAS (para o gráfico "Desempenho dos alunos")
    // Retorna um array com 7 posições (mais antigo -> mais recente),
    // cada uma com a taxa de acerto (%) do dia.
    public function desempenhoSemanal(int $semanasAtras = 0): array
    {
        $semanasAtras = max(0, $semanasAtras);
        $diasBase     = $semanasAtras * 7;

        $fim    = strtotime("-{$diasBase} days", strtotime('today'));
        $inicio = strtotime("-" . ($diasBase + 6) . " days", strtotime('today'));

        $rows = $this->db->table('RESPONDE r')
            ->select("DATE(r.RESPONDIDO_EM) AS dia, COUNT(*) AS total, SUM(a.IS_CORRETA) AS acertos")
            ->join('ALTERNATIVA a', 'a.ID_ALTERNATIVA = r.FK_ID_ALTERNATIVA')
            ->where('r.RESPONDIDO_EM >=', date('Y-m-d 00:00:00', $inicio))
            ->where('r.RESPONDIDO_EM <', date('Y-m-d 00:00:00', strtotime('+1 day', $fim)))
            ->groupBy('DATE(r.RESPONDIDO_EM)')
            ->get()
            ->getResultArray();

        $porDia = [];
        foreach ($rows as $row) {
            $porDia[$row['dia']] = $row;
        }

        $diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

        $resultado = [];
        for ($i = 6; $i >= 0; $i--) {
            $data = date('Y-m-d', strtotime("-{$i} days", $fim));
            $total = isset($porDia[$data]) ? (int) $porDia[$data]['total'] : 0;
            $acertos = isset($porDia[$data]) ? (int) $porDia[$data]['acertos'] : 0;

            $resultado[] = [
                'data'       => $data,
                'label'      => $diasSemana[(int) date('w', strtotime($data))],
                'total'      => $total,
                'acertos'    => $acertos,
                'percentual' => $total > 0 ? round(($acertos / $total) * 100) : 0,
            ];
        }

        return $resultado;
    }

    // ---------------------------------------------------------------
    // TEXTO DO PERÍODO EXIBIDO NO GRÁFICO "Desempenho dos alunos"
    // (ex.: "26/08 a 01/09"). Usa a mesma âncora e a mesma regra de
    // deslocamento de desempenhoSemanal(), para que os dois métodos
    // concordem sempre sobre qual intervalo de datas está sendo mostrado.
    // Mantido como método separado (em vez de mudar o retorno de
    // desempenhoSemanal) para não alterar o formato já consumido via
    // array_column() na view do dashboard.
    // ---------------------------------------------------------------
    public function periodoSemana(int $semanasAtras = 0): string
    {
        $semanasAtras = max(0, $semanasAtras);
        $diasBase     = $semanasAtras * 7;

        $fim    = strtotime("-{$diasBase} days", strtotime('today'));
        $inicio = strtotime("-" . ($diasBase + 6) . " days", strtotime('today'));

        return date('d/m', $inicio) . ' a ' . date('d/m', $fim);
    }

    // ---------------------------------------------------------------
    // PERGUNTAS ORDENADAS PELA TAXA DE ACERTO
    // $direcao = 'DESC' -> mais acertadas | 'ASC' -> mais erradas
    // Considera apenas perguntas que já foram respondidas.
    // ---------------------------------------------------------------
    public function perguntasPorTaxaAcerto(int $limite, string $direcao = 'DESC'): array
    {
        $rows = $this->db->table('RESPONDE r')
            ->select('p.ID_PERGUNTA, p.DESCRICAO, COUNT(*) AS total, SUM(a.IS_CORRETA) AS acertos')
            ->join('ALTERNATIVA a', 'a.ID_ALTERNATIVA = r.FK_ID_ALTERNATIVA')
            ->join('PERGUNTA p', 'p.ID_PERGUNTA = a.FK_ID_PERGUNTA')
            ->groupBy('p.ID_PERGUNTA')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['total']   = (int) $row['total'];
            $row['acertos'] = (int) $row['acertos'];
            $row['taxa']    = $row['total'] > 0 ? round(($row['acertos'] / $row['total']) * 100) : 0;
        }

        usort($rows, function ($a, $b) use ($direcao) {
            return $direcao === 'ASC'
                ? $a['taxa'] <=> $b['taxa']
                : $b['taxa'] <=> $a['taxa'];
        });

        return array_slice($rows, 0, $limite);
    }

    // ---------------------------------------------------------------
    // RANKING DE USUÁRIOS POR TAXA DE ACERTO
    // Considera apenas usuários que já responderam ao menos 1 questão.
    // ---------------------------------------------------------------
    public function rankingUsuarios(int $limite = 3): array
    {
        $rows = $this->db->table('RESPONDE r')
            ->select('u.CPF, u.NOME, u.EMAIL, u.FOTO, COUNT(*) AS total, SUM(a.IS_CORRETA) AS acertos')
            ->join('USUARIO u', 'u.CPF = r.FK_CPF_USUARIO')
            ->join('ALTERNATIVA a', 'a.ID_ALTERNATIVA = r.FK_ID_ALTERNATIVA')
            ->groupBy('u.CPF')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['total']   = (int) $row['total'];
            $row['acertos'] = (int) $row['acertos'];
            $row['taxa']    = $row['total'] > 0 ? round(($row['acertos'] / $row['total']) * 100) : 0;
        }

        usort($rows, fn ($a, $b) => $b['taxa'] <=> $a['taxa']);

        return array_slice($rows, 0, $limite);
    }

    // ---------------------------------------------------------------
    // ATIVIDADES RECENTES (últimas respostas registradas)
    // ---------------------------------------------------------------
    public function atividadesRecentes(int $limite = 4): array
    {
        $rows = $this->db->table('RESPONDE r')
            ->select('r.RESPONDIDO_EM, u.NOME, u.EMAIL, p.DESCRICAO AS PERGUNTA_TEXTO, a.IS_CORRETA')
            ->join('USUARIO u', 'u.CPF = r.FK_CPF_USUARIO')
            ->join('ALTERNATIVA a', 'a.ID_ALTERNATIVA = r.FK_ID_ALTERNATIVA')
            ->join('PERGUNTA p', 'p.ID_PERGUNTA = a.FK_ID_PERGUNTA')
            ->orderBy('r.RESPONDIDO_EM', 'DESC')
            ->limit($limite)
            ->get()
            ->getResultArray();

        // PREVENTIVO: mesma causa raiz do bug relatado no Quiz.
        foreach ($rows as &$row) {
            $row['IS_CORRETA'] = (int) $row['IS_CORRETA'];
        }
        unset($row);

        return $rows;
    }

    // ---------------------------------------------------------------
    // EXCLUIR RESPOSTA PELO ID
    // ---------------------------------------------------------------
    public function excluir(int $id): bool
    {
        return $this->delete($id);
    }

    // ---------------------------------------------------------------
    // HISTÓRICO COMPLETO COM DETALHES (ADMIN)
    // ---------------------------------------------------------------
    public function listarComDetalhes(): array
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