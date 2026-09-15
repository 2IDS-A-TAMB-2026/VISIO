<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * LoginCartaoModel
 *
 * ADICIONADO (item 8/9 do pedido — login automático por RFID).
 *
 * Responsável pela tabela LOGIN_CARTAO, criada seguindo o MESMO padrão
 * já usado por RESET_SENHA no schema deste projeto (token/registro
 * temporário, com FK para USUARIO, flag USADO e expiração por tempo).
 * Não é uma tabela de "donos de cartão" — isso já existe em
 * USUARIO.CARTAO e não foi duplicado aqui.
 *
 * PARA QUE SERVE: o ESP32 e o navegador são dois clientes HTTP
 * completamente separados, sem cookie/sessão em comum. Quando o ESP32
 * confirma um cartão válido, não há como a requisição DELE criar a
 * sessão de um navegador de outra pessoa. Esta tabela é o ponto de
 * encontro entre as duas partes:
 *   1) ESP32 confirma o cartão → grava aqui "usuário X foi autorizado
 *      agora" (ver APICartaoController::verificar).
 *   2) O navegador, após clicar em "Entrar com cartão", consulta esta
 *      tabela repetidamente (polling) → ao encontrar um registro
 *      recente e não usado, é a PRÓPRIA requisição do navegador que
 *      cria a sessão dele (ver UsuarioController::loginPorCartao).
 *
 * LIMITAÇÃO CONHECIDA E ACEITA: como o hardware (MFRC522 + LED +
 * buzzer, sem teclado/tela) não permite parear um navegador específico
 * a uma leitura específica, qualquer navegador que esteja com o
 * polling ativo no momento da leitura será autenticado — não é uma
 * falha desta implementação, é um limite físico do hardware descrito
 * no pedido.
 */
class LoginCartaoModel extends Model
{
    protected $table = 'LOGIN_CARTAO';
    protected $primaryKey = 'ID_LOGIN_CARTAO';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'FK_CPF',
        'USADO',
        'EXPIRA_EM',
    ];

    // Janela de validade de uma autorização pendente: tempo suficiente
    // para a pessoa clicar "Entrar com cartão" e aproximar o cartão do
    // leitor, sem deixar uma autorização antiga "flutuando" por tempo
    // demais à espera de qualquer navegador com polling ativo.
    private const VALIDADE_SEGUNDOS = 30;

    /**
     * Chamado pelo APICartaoController quando o ESP32 confirma um
     * cartão válido — registra que o usuário $cpf acabou de ser
     * autorizado, com uma janela curta de validade.
     */
    public function registrarAutorizacao(string $cpf): void
    {
        $this->insert([
            'FK_CPF'    => $cpf,
            'USADO'     => 0,
            'EXPIRA_EM' => date('Y-m-d H:i:s', time() + self::VALIDADE_SEGUNDOS),
        ]);
    }

    /**
     * Chamado pelo UsuarioController::loginPorCartao a cada consulta
     * de polling do navegador. Busca a autorização mais recente ainda
     * válida (não usada e não expirada), marca como usada (para que
     * não sirva de novo para outro navegador que consulte em seguida)
     * e devolve o CPF correspondente — ou null se não há nada pendente
     * no momento.
     */
    public function consumirMaisRecente(): ?string
    {
        $pendente = $this->where('USADO', 0)
            ->where('EXPIRA_EM >', date('Y-m-d H:i:s'))
            ->orderBy('CRIADO_EM', 'DESC')
            ->first();

        if (!$pendente) {
            return null;
        }

        $this->update($pendente['ID_LOGIN_CARTAO'], ['USADO' => 1]);

        return $pendente['FK_CPF'];
    }
}
