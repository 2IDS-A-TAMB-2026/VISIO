<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\UsuarioModel;
use App\Models\SensorModel;
use App\Models\PerguntaModel;
use App\Models\AlternativaModel;
use App\Models\RespondeModel;

/**
 * AdminController
 * Painel administrativo. Todas as rotas são protegidas pelo filtro 'adminAuth'.
 */
class AdminController extends BaseController
{
    // ACHADO NA AUDITORIA DO FLUTTER (Etapa 4): TODO o painel admin do app
    // (inicio_adm.dart, lista.dart, cadastro_sensor.dart, cadastro_questao.dart,
    // perfil_adm.dart) chama admin/dashboard, admin/usuarios, admin/sensores,
    // admin/perguntas, admin/perfil etc. — mas nenhuma dessas rotas nunca
    // existiu dentro do grupo 'api' (só fora dele, como admin/... protegido
    // por adminAuth, usado pelo site). Como ApiConfig.baseUrl sempre prefixa
    // "/api", TODA chamada do app pra esse painel recebia 404. Mesma causa
    // raiz do quiz e do histórico, só que aqui em ~13 endpoints de uma vez.
    // Nenhum método deste Controller respondia em JSON — corrigido método a
    // método, reaproveitando exatamente os mesmos dados já montados pra
    // view(), sem duplicar lógica nova.
    private function querJson(): bool
    {
        return $this->request->isAJAX()
            || str_contains($this->request->getHeaderLine('Accept'), 'json')
            || str_contains($this->request->getHeaderLine('Content-Type'), 'json');
    }

    // ---------------------------------------------------------------
    // DASHBOARD — com dados reais do banco
    // ---------------------------------------------------------------

    public function dashboard()
    {
        // CORRIGIDO (erro 1): antes o gráfico "Desempenho semanal" só
        // conseguia mostrar a semana atual, porque nada no Controller nem
        // no Model aceitava um parâmetro de qual semana exibir.
        // ?semana=0 (padrão) = semana atual, ?semana=1 = semana anterior, etc.
        // Valores negativos ou não numéricos caem em 0 (nunca "semana futura").
        $semanaOffset = max(0, (int) ($this->request->getGet('semana') ?? 0));

        $respondeModel = new RespondeModel();
        $totalRespostas = $respondeModel->countAllResults();
        $totalAcertos = 0;

        if ($totalRespostas > 0) {
            $row = $respondeModel->db->table('RESPONDE r')
                ->selectSum('a.IS_CORRETA', 'acertos')
                ->join('ALTERNATIVA a', 'a.ID_ALTERNATIVA = r.FK_ID_ALTERNATIVA')
                ->get()->getRow();
            $totalAcertos = $row ? (int) $row->acertos : 0;
        }

        $taxaAcerto = $totalRespostas > 0
            ? round(($totalAcertos / $totalRespostas) * 100)
            : 0;

        $atividadesRecentes = $respondeModel->atividadesRecentes(4);
        foreach ($atividadesRecentes as &$atividade) {
            $atividade['TEMPO_RELATIVO'] = $this->tempoRelativo($atividade['RESPONDIDO_EM']);
        }

        $adminModel = new AdminModel();
        $admin = $adminModel->find(session()->get('admin_cnpj'));

        $dados = [
            'admin' => $admin,
            'total_usuarios' => (new UsuarioModel())->countAllResults(),
            'total_sensores' => (new SensorModel())->countAllResults(),
            'total_perguntas' => (new PerguntaModel())->countAllResults(),
            'total_perguntas_dificeis' => (new PerguntaModel())->contarPorNivel('Difícil'),
            'total_respostas' => $totalRespostas,
            'total_acertos' => $totalAcertos,
            'total_erros' => $totalRespostas - $totalAcertos,
            'taxa_acerto' => $taxaAcerto,
            'desempenho_semanal' => $respondeModel->desempenhoSemanal($semanaOffset),
            'semana_offset' => $semanaOffset,
            'semana_atual'  => $semanaOffset === 0,
            // Texto do intervalo de datas exibido (ex.: "26/08 a 01/09"), calculado
            // pela mesma âncora/regra de desempenhoSemanal() acima — usado pela
            // navegação por setas do gráfico "Desempenho dos alunos" para deixar
            // claro qual semana está sendo mostrada.
            'periodo_semana' => $respondeModel->periodoSemana($semanaOffset),
            'perguntas_mais_acertadas' => $respondeModel->perguntasPorTaxaAcerto(5, 'DESC'),
            'perguntas_mais_erradas' => $respondeModel->perguntasPorTaxaAcerto(5, 'ASC'),
            'ranking_usuarios' => $respondeModel->rankingUsuarios(3),
            'atividades_recentes' => $atividadesRecentes,
        ];

        if ($this->querJson()) {
            return $this->response->setJSON($dados);
        }

        return view('sistema/admin/index', $dados);
    }

    /**
     * Converte um datetime ('Y-m-d H:i:s') em texto relativo ("Há 5 minutos", "Há 2 dias" etc.)
     * Usado na seção "Atividades recentes" do dashboard.
     */
    private function tempoRelativo(string $datetime): string
    {
        $diferenca = time() - strtotime($datetime);

        if ($diferenca < 60) {
            return 'Agora mesmo';
        }
        if ($diferenca < 3600) {
            $min = (int) floor($diferenca / 60);
            return 'Há ' . $min . ' minuto' . ($min > 1 ? 's' : '');
        }
        if ($diferenca < 86400) {
            $horas = (int) floor($diferenca / 3600);
            return 'Há ' . $horas . ' hora' . ($horas > 1 ? 's' : '');
        }
        $dias = (int) floor($diferenca / 86400);
        return 'Há ' . $dias . ' dia' . ($dias > 1 ? 's' : '');
    }

    // ---------------------------------------------------------------
    // GESTÃO DE USUÁRIOS
    // ---------------------------------------------------------------

    public function usuarios()
    {
        // CORRIGIDO (erro 2): a listagem só trazia os dados cadastrais do
        // usuário, nunca o aproveitamento no quiz. Os métodos que calculam
        // isso (totalPorUsuario/totalAcertosPorUsuario) já existiam no
        // RespondeModel e já eram usados em UsuarioController::perfil() —
        // reaproveitados aqui em vez de criar uma lógica de cálculo nova.
        $usuarios = (new UsuarioModel())->findAll();
        $respondeModel = new RespondeModel();

        foreach ($usuarios as &$usuario) {
            $total = $respondeModel->totalPorUsuario($usuario['CPF']);
            $acertos = $respondeModel->totalAcertosPorUsuario($usuario['CPF']);

            $usuario['QUIZ_TOTAL'] = $total;
            $usuario['QUIZ_ACERTOS'] = $acertos;
            $usuario['QUIZ_ERROS'] = $total - $acertos;
            $usuario['QUIZ_APROVEITAMENTO'] = $total > 0 ? round(($acertos / $total) * 100) : 0;
            unset($usuario['SENHA']);
        }
        unset($usuario);

        if ($this->querJson()) {
            // lista.dart (Flutter) espera um array puro, não envolvido em
            // objeto — mesmo formato de APIUsuarioController::index().
            return $this->response->setJSON($usuarios);
        }

        return view('sistema/admin/usuarios/index', [
            'usuarios' => $usuarios,
        ]);
    }

    public function atualizarUsuario(string $cpf)
    {
        $model = new UsuarioModel();
        $usuario = $model->where('CPF', $cpf)->first();

        if (!$usuario) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(404)->setJSON(['message' => 'Usuário não encontrado.']);
            }
            return redirect()->to('/admin/usuarios')
                ->with('erro', 'Usuário não encontrado.');
        }

        $email = $this->request->getPost('email');

        $emailExistente = $model->where('EMAIL', $email)
            ->where('CPF !=', $cpf)
            ->first();
        if ($emailExistente) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(409)->setJSON(['message' => 'E-mail já está em uso por outro usuário.']);
            }
            return redirect()->to('/admin/usuarios')
                ->with('erro', 'E-mail já está em uso por outro usuário.');
        }

        // CORRIGIDO (erro 4): o campo CPF era lido em lugar nenhum e nunca
        // entrava no update() — não importava o que o Admin digitasse no
        // formulário, o CPF nunca mudava no banco. Validação de formato e
        // unicidade seguem o mesmo padrão já usado acima para o e-mail.
        // Mesma máscara aplicada em validacaocadastro.js (000.000.000-00).
        // AJUSTADO: o formulário de editar usuário do app Mobile
        // (lista.dart) não manda campo "cpf" — só nome/email/telefone/
        // cartao/data_nascimento (a versão Web é que expõe a edição de
        // CPF). Tratar ausência como "não mudar o CPF" em vez de erro
        // obrigatório evita quebrar o app quando esta rota também passa a
        // responder em JSON (ver Routes.php). Mesmo princípio já usado
        // abaixo para "cartao": campo ausente preserva o valor atual.
        $cpfEnviado = $this->request->getPost('cpf');
        $novoCpf = ($cpfEnviado === null || trim($cpfEnviado) === '')
            ? $cpf
            : trim($cpfEnviado);

        if (!preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $novoCpf)) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(400)->setJSON(['message' => 'CPF em formato inválido. Use o padrão 000.000.000-00.']);
            }
            return redirect()->to('/admin/usuarios')
                ->with('erro', 'CPF em formato inválido. Use o padrão 000.000.000-00.');
        }

        if ($novoCpf !== $cpf) {
            $cpfExistente = $model->where('CPF', $novoCpf)->first();
            if ($cpfExistente) {
                if ($this->querJson()) {
                    return $this->response->setStatusCode(409)->setJSON(['message' => 'Este CPF já está cadastrado para outro usuário.']);
                }
                return redirect()->to('/admin/usuarios')
                    ->with('erro', 'Este CPF já está cadastrado para outro usuário.');
            }
        }

        $model->update($cpf, [
            'CPF' => $novoCpf,
            'NOME' => $this->request->getPost('nome'),
            'EMAIL' => $email,
            // CORRIGIDO: antes caía em '' quando o formulário não mandasse
            // "cartao" — como CARTAO é UNIQUE, isso arriscava apagar o
            // cartão real do usuário e colidir com o próximo Admin que
            // editasse outro usuário sem esse campo. Mantém o valor atual
            // se nada for enviado, em vez de zerar.
            'CARTAO' => $this->request->getPost('cartao') ?? $usuario['CARTAO'],
            'DATA_NASCIMENTO' => $this->request->getPost('data_nascimento'),
            'TELEFONE' => $this->request->getPost('telefone'),
        ]);

        if ($this->querJson()) {
            $usuarioAtualizado = $model->where('CPF', $novoCpf)->first();
            unset($usuarioAtualizado['SENHA']);
            return $this->response->setJSON([
                'message' => 'Usuário atualizado com sucesso!',
                'usuario' => $usuarioAtualizado,
            ]);
        }

        return redirect()->to('/admin/usuarios')
            ->with('sucesso', 'Usuário ' . $novoCpf . ' atualizado com sucesso!');
    }

    public function excluirUsuario(string $cpf)
    {
        (new UsuarioModel())->delete($cpf);

        if ($this->querJson()) {
            return $this->response->setJSON(['message' => 'Usuário removido com sucesso.']);
        }

        return redirect()->to('/admin/usuarios')
            ->with('sucesso', 'Usuário removido com sucesso.');
    }

    // ---------------------------------------------------------------
    // GESTÃO DE SENSORES
    // ---------------------------------------------------------------

    public function sensores()
    {
        $sensores = (new SensorModel())->findAll();

        if ($this->querJson()) {
            return $this->response->setJSON($sensores);
        }

        return view('sistema/admin/sensores/index', [
            'sensores' => $sensores,
        ]);
    }

    public function novoSensorForm(): string
    {
        return view('sistema/admin/sensores/novo_sensor');
    }

    public function inserirSensor()
    {
        $foto = '';
        $arquivo = $this->request->getFile('foto');

        if ($arquivo && $arquivo->isValid() && !$arquivo->hasMoved()) {
            if ($arquivo->getSize() > 2 * 1024 * 1024) {
                if ($this->querJson()) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'message' => 'A imagem deve ter no máximo 2 MB.',
                    ]);
                }
                return redirect()->to('/admin/sensor/novo')
                    ->with('erro', 'A imagem deve ter no máximo 2 MB.');
            }
            $novoNome = $arquivo->getRandomName();
            $arquivo->move(ROOTPATH . 'public/uploads/sensores', $novoNome);
            $foto = 'uploads/sensores/' . $novoNome;
        }

        $model = new SensorModel();
        $id = $model->insert([
            'NOME' => $this->request->getPost('nome'),
            'DESCRICAO' => $this->request->getPost('descricao'),
            'CIRCUITO' => $this->request->getPost('circuito') ?? '',
            'FOTO' => $foto,
        ]);

        if ($this->querJson()) {
            return $this->response->setJSON([
                'message' => 'Sensor cadastrado com sucesso.',
                'sensor' => $model->find($id),
            ]);
        }

        return redirect()->to('/admin/sensores')
            ->with('sucesso', 'Sensor cadastrado com sucesso.');
    }

    public function editarSensorForm(int $id): string
    {
        return view('sistema/admin/sensores/editar_sensor', [
            'sensor' => (new SensorModel())->find($id),
        ]);
    }

    public function atualizarSensor(int $id)
    {
        $model = new SensorModel();
        $sensor = $model->find($id);
        $foto = $sensor['FOTO'] ?? '';

        $arquivo = $this->request->getFile('foto');
        if ($arquivo && $arquivo->isValid() && !$arquivo->hasMoved()) {
            if ($arquivo->getSize() > 2 * 1024 * 1024) {
                if ($this->querJson()) {
                    return $this->response->setStatusCode(400)->setJSON(['message' => 'A imagem deve ter no máximo 2 MB.']);
                }
                return redirect()->to('/admin/sensor/editar/' . $id)
                    ->with('erro', 'A imagem deve ter no máximo 2 MB.');
            }
            if (!empty($sensor['FOTO']) && file_exists(ROOTPATH . 'public/' . $sensor['FOTO'])) {
                unlink(ROOTPATH . 'public/' . $sensor['FOTO']);
            }
            $novoNome = $arquivo->getRandomName();
            $arquivo->move(ROOTPATH . 'public/uploads/sensores', $novoNome);
            $foto = 'uploads/sensores/' . $novoNome;
        }

        $model->update($id, [
            'NOME' => $this->request->getPost('nome'),
            'DESCRICAO' => $this->request->getPost('descricao'),
            'CIRCUITO' => $this->request->getPost('circuito') ?? '',
            'FOTO' => $foto,
        ]);

        if ($this->querJson()) {
            return $this->response->setJSON([
                'message' => 'Sensor atualizado.',
                'sensor' => $model->find($id),
            ]);
        }

        return redirect()->to('/admin/sensores')
            ->with('sucesso', 'Sensor atualizado.');
    }

    public function excluirSensor(int $id)
    {
        $model = new SensorModel();
        $sensor = $model->find($id);

        if ($sensor && !empty($sensor['FOTO']) && file_exists(ROOTPATH . 'public/' . $sensor['FOTO'])) {
            unlink(ROOTPATH . 'public/' . $sensor['FOTO']);
        }

        $model->delete($id);

        if ($this->querJson()) {
            return $this->response->setJSON(['message' => 'Sensor removido.']);
        }

        return redirect()->to('/admin/sensores')
            ->with('sucesso', 'Sensor removido.');
    }

    // ---------------------------------------------------------------
    // GESTÃO DE PERGUNTAS E ALTERNATIVAS
    // ---------------------------------------------------------------

    public function perguntas()
    {
        $perguntas = (new PerguntaModel())->listarComAlternativas();

        if ($this->querJson()) {
            return $this->response->setJSON($perguntas);
        }

        return view('sistema/admin/quiz/index', [
            'perguntas' => $perguntas,
        ]);
    }

    public function novaPerguntaForm(): string
    {
        return view('sistema/admin/quiz/nova_pergunta');
    }

    public function inserirPergunta()
    {
        $perguntaModel = new PerguntaModel();
        $alternativaModel = new AlternativaModel();

        $idPergunta = $perguntaModel->insert([
            'DESCRICAO' => $this->request->getPost('descricao'),
            'NIVEL_DIFICULDADE' => $this->request->getPost('nivel'),
        ]);

        if ($idPergunta) {
            $correta = $this->request->getPost('correta');
            $alternativas = [
                ['letra' => 'a', 'texto' => $this->request->getPost('alt_a')],
                ['letra' => 'b', 'texto' => $this->request->getPost('alt_b')],
                ['letra' => 'c', 'texto' => $this->request->getPost('alt_c')],
                ['letra' => 'd', 'texto' => $this->request->getPost('alt_d')],
            ];

            $lote = [];
            foreach ($alternativas as $alt) {
                $lote[] = [
                    'DESCRICAO' => $alt['texto'],
                    'IS_CORRETA' => ($alt['letra'] === $correta) ? 1 : 0,
                    'FK_ID_PERGUNTA' => $idPergunta,
                ];
            }
            $alternativaModel->insertBatch($lote);
        }

        if ($this->querJson()) {
            return $this->response->setJSON([
                'message' => 'Pergunta e alternativas cadastradas com sucesso.',
                'pergunta' => $perguntaModel->buscarComAlternativas($idPergunta),
            ]);
        }

        return redirect()->to('/admin/perguntas')
            ->with('sucesso', 'Pergunta e alternativas cadastradas com sucesso.');
    }

    public function editarPerguntaForm(int $id): string
    {
        return view('sistema/admin/quiz/editar_questao', [
            'pergunta' => (new PerguntaModel())->buscarComAlternativas($id),
        ]);
    }

    public function atualizarPergunta(int $id)
    {
        $perguntaModel = new PerguntaModel();
        $alternativaModel = new AlternativaModel();

        $perguntaModel->update($id, [
            'DESCRICAO' => $this->request->getPost('descricao'),
            'NIVEL_DIFICULDADE' => $this->request->getPost('nivel'),
        ]);

        $idsAlternativas = $this->request->getPost('id_alternativa');
        $textos = $this->request->getPost('alternativa');
        $correta = $this->request->getPost('correta');

        if (is_array($idsAlternativas)) {
            foreach ($idsAlternativas as $i => $idAlt) {
                $alternativaModel->update((int) $idAlt, [
                    'DESCRICAO' => $textos[$i],
                    'IS_CORRETA' => ($idAlt == $correta) ? 1 : 0,
                ]);
            }
        }

        if ($this->querJson()) {
            return $this->response->setJSON([
                'message' => 'Pergunta atualizada com sucesso.',
                'pergunta' => $perguntaModel->buscarComAlternativas($id),
            ]);
        }

        return redirect()->to('/admin/perguntas')
            ->with('sucesso', 'Pergunta atualizada com sucesso.');
    }

    public function excluirPergunta(int $id)
    {
        (new PerguntaModel())->delete($id);

        if ($this->querJson()) {
            return $this->response->setJSON(['message' => 'Pergunta e alternativas removidas com sucesso.']);
        }

        return redirect()->to('/admin/perguntas')
            ->with('sucesso', 'Pergunta e alternativas removidas com sucesso.');
    }

    // ---------------------------------------------------------------
    // PERFIL DO ADMINISTRADOR
    // ---------------------------------------------------------------

    public function perfil()
    {
        $cnpj = session()->get('admin_cnpj');
        $model = new AdminModel();
        $admin = $model->find($cnpj);

        if ($this->querJson()) {
            unset($admin['SENHA']);
            return $this->response->setJSON(['admin' => $admin]);
        }

        return view('sistema/admin/perfil_adm', [
            'admin' => $admin,
        ]);
    }

    public function atualizarPerfilAdmin()
    {
        $cnpj  = session()->get('admin_cnpj');
        $model = new AdminModel();
        $email = $this->request->getPost('email');

        $emailExistente = $model->where('EMAIL', $email)->where('CNPJ !=', $cnpj)->first();
        if ($emailExistente) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(409)->setJSON(['message' => 'Este e-mail já está em uso por outro administrador.']);
            }
            return redirect()->to('/admin/perfil')
                ->with('erro', 'Este e-mail já está em uso por outro administrador.');
        }

        $dados = [
            'NOME'     => $this->request->getPost('nome'),
            'EMAIL'    => $email,
            'TELEFONE' => $this->request->getPost('telefone') ?? '',
        ];

        // Upload da foto de perfil (mesmo padrão de UsuarioController::atualizarPerfil)
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $validacao = $this->validate([
                'foto' => [
                    'uploaded[foto]',
                    'mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]',
                    'max_size[foto,2048]', // Máximo 2MB
                ],
            ]);

            if (!$validacao) {
                if ($this->querJson()) {
                    return $this->response->setStatusCode(400)->setJSON(['message' => 'Arquivo inválido. Escolha uma imagem PNG, JPG ou WEBP de até 2MB.']);
                }
                return redirect()->to('/admin/perfil')
                    ->with('erro', 'Arquivo inválido. Escolha uma imagem PNG, JPG ou WEBP de até 2MB.');
            }

            $novoNome = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/admin/', $novoNome);

            // Remove a foto antiga do servidor, se existir
            $adminAtual = $model->find($cnpj);
            if (!empty($adminAtual['FOTO']) && file_exists(ROOTPATH . 'public/' . $adminAtual['FOTO'])) {
                if (!unlink(ROOTPATH . 'public/' . $adminAtual['FOTO'])) {
                    log_message('warning', 'Não foi possível remover a foto antiga do admin ' . $cnpj . ': ' . $adminAtual['FOTO']);
                }
            }

            $dados['FOTO'] = 'uploads/admin/' . $novoNome;
        }

        $novaSenha = $this->request->getPost('senha');
        if (!empty($novaSenha)) {
            $dados['SENHA'] = password_hash($novaSenha, PASSWORD_BCRYPT);
        }

        $model->update($cnpj, $dados);

        if ($this->querJson()) {
            $adminAtualizado = $model->find($cnpj);
            unset($adminAtualizado['SENHA']);
            return $this->response->setJSON([
                'message' => 'Perfil atualizado com sucesso!',
                'admin' => $adminAtualizado,
            ]);
        }

        return redirect()->to('/admin/perfil')
            ->with('sucesso', 'Perfil atualizado com sucesso!');
    }
}