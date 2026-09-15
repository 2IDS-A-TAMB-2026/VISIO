<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UsuarioModel
 * Responsável por todas as operações da tabela USUARIO.
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
        'NOME',
        'EMAIL',
        'SENHA',
        'CARTAO',
        'DATA_NASCIMENTO',
        'TELEFONE',
        'FOTO',
    ];

    /**
     * Busca um usuário pelo e-mail para o sistema de login.
     */
    public function buscarPorEmail(string $email)
    {
        return $this->where('EMAIL', $email)->first();
    }

    /**
     * ADICIONADO (item 8/9 do pedido — login automático por RFID).
     *
     * Busca um usuário pelo valor de USUARIO.CARTAO (o UID do cartão
     * RFID). Usa sempre o mesmo formato canônico definido em
     * normalizarUid() para a comparação, para que diferenças de
     * maiúsculas/minúsculas ou espaços (seja do ESP32, seja de alguém
     * digitando manualmente na tela de admin) não façam o sistema
     * tratar o MESMO cartão físico como se fossem cartões diferentes.
     */
    public function buscarPorCartao(string $uid)
    {
        return $this->where('CARTAO', self::normalizarUid($uid))->first();
    }

    /**
     * Formato canônico do campo CARTAO: hexadecimal maiúsculo, sem
     * espaços/separadores — mesmo formato usado no exemplo do próprio
     * pedido ("UID RFID = A1B2C3D4"). Usado tanto ao gravar (cadastro/
     * edição do cartão) quanto ao comparar (login via RFID), para que
     * o mesmo cartão físico sempre produza o mesmo valor armazenado,
     * não importa a origem (ESP32 ou digitação manual).
     */
    public static function normalizarUid(string $uid): string
    {
        return strtoupper(preg_replace('/[^0-9A-Fa-f]/', '', $uid));
    }
}