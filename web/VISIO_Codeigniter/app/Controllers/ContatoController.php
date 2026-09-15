<?php

namespace App\Controllers;

/**
 * ContatoController
 *
 * NOVO (pedido do usuário): "o contato tem que enviar no e-mail do VISIO".
 * O app (contato.dart) só simulava o envio (Future.delayed + sucesso falso),
 * sem nenhum Controller ou rota por trás. Este Controller usa a biblioteca
 * Email nativa do CodeIgniter 4 (\Config\Services::email()) — não precisa
 * de nenhuma dependência nova.
 *
 * IMPORTANTE — CONFIGURAÇÃO NECESSÁRIA (não inventada aqui):
 * Para isto realmente enviar e-mail, o arquivo .env do projeto precisa ter,
 * no mínimo:
 *
 *   email.protocol   = smtp
 *   email.SMTPHost   = <host do provedor de e-mail, ex.: smtp.gmail.com>
 *   email.SMTPUser   = <usuário/e-mail de envio>
 *   email.SMTPPass   = <senha ou senha de app>
 *   email.SMTPPort   = 587
 *   email.SMTPCrypto = tls
 *   email.fromEmail  = <mesmo e-mail de envio, ou um "noreply@..." validado pelo provedor>
 *   email.fromName   = "VISIO"
 *
 * E uma variável própria deste Controller, com o e-mail que deve RECEBER
 * as mensagens do formulário de contato (o "e-mail do VISIO"):
 *
 *   CONTATO_EMAIL_DESTINO = <e-mail que deve receber as mensagens>
 *
 * Sem isso, o envio falha e o endpoint responde erro — não escondo a
 * falha atrás de uma mensagem de "sucesso" falsa.
 */
class ContatoController extends BaseController
{
    private function querJson(): bool
    {
        return $this->request->isAJAX()
            || str_contains($this->request->getHeaderLine('Accept'), 'json')
            || str_contains($this->request->getHeaderLine('Content-Type'), 'json');
    }

    public function enviar()
    {
        $nome = trim($this->request->getPost('nome') ?? '');
        $email = trim($this->request->getPost('email') ?? '');
        $mensagem = trim($this->request->getPost('mensagem') ?? '');

        if (empty($nome) || empty($email) || empty($mensagem)) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => 'Preencha nome, e-mail e mensagem.',
                ]);
            }
            return redirect()->back()->with('erro', 'Preencha nome, e-mail e mensagem.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(400)->setJSON(['message' => 'E-mail inválido.']);
            }
            return redirect()->back()->with('erro', 'E-mail inválido.');
        }

        $destino = env('CONTATO_EMAIL_DESTINO');

        if (empty($destino)) {
            log_message('error', 'ContatoController: variável CONTATO_EMAIL_DESTINO não configurada no .env.');
            if ($this->querJson()) {
                return $this->response->setStatusCode(500)->setJSON([
                    'message' => 'Envio de contato ainda não configurado no servidor.',
                ]);
            }
            return redirect()->back()->with('erro', 'Envio de contato ainda não configurado no servidor.');
        }

        $email_service = \Config\Services::email();
        $email_service->setTo($destino);
        $email_service->setFrom(env('email.fromEmail'), env('email.fromName') ?? 'VISIO');
        $email_service->setReplyTo($email);
        $email_service->setSubject('Contato pelo app VISIO - ' . $nome);
        $email_service->setMessage(
            "Nova mensagem pelo formulário de contato do app VISIO:\n\n" .
            "Nome: {$nome}\n" .
            "E-mail: {$email}\n\n" .
            "Mensagem:\n{$mensagem}"
        );

        $enviado = $email_service->send();

        if (!$enviado) {
            log_message('error', 'ContatoController: falha ao enviar e-mail. ' . $email_service->printDebugger(['headers']));
            if ($this->querJson()) {
                return $this->response->setStatusCode(502)->setJSON([
                    'message' => 'Não foi possível enviar sua mensagem agora. Tente novamente mais tarde.',
                ]);
            }
            return redirect()->back()->with('erro', 'Não foi possível enviar sua mensagem agora. Tente novamente mais tarde.');
        }

        if ($this->querJson()) {
            return $this->response->setJSON([
                'message' => 'Mensagem enviada com sucesso!',
            ]);
        }

        return redirect()->back()->with('sucesso', 'Mensagem enviada com sucesso!');
    }
}
