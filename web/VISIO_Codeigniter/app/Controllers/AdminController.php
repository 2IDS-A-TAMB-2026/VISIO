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
    // ---------------------------------------------------------------
    // DASHBOARD — com dados reais do banco
    // ---------------------------------------------------------------

    public function dashboard(): string
    {
        
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

        return view('sistema/admin/index', [
            'admin' => $admin,
            'total_usuarios' => (new UsuarioModel())->countAllResults(),
            'total_sensores' => (new SensorModel())->countAllResults(),
            'total_perguntas' => (new PerguntaModel())->countAllResults(),
            'total_perguntas_dificeis' => (new PerguntaModel())->contarPorNivel('Difícil'),
            'total_respostas' => $totalRespostas,
            'total_acertos' => $totalAcertos,
            'total_erros' => $totalRespostas - $totalAcertos,
            'taxa_acerto' => $taxaAcerto,
            'desempenho_semanal' => $respondeModel->desempenhoSemanal(),
            'perguntas_mais_acertadas' => $respondeModel->perguntasPorTaxaAcerto(5, 'DESC'),
            'perguntas_mais_erradas' => $respondeModel->perguntasPorTaxaAcerto(5, 'ASC'),
            'ranking_usuarios' => $respondeModel->rankingUsuarios(3),
            'atividades_recentes' => $atividadesRecentes,
        ]);
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

    public function usuarios(): string
    {
        return view('sistema/admin/usuarios/index', [
            'usuarios' => (new UsuarioModel())->findAll(),
        ]);
    }

    public function atualizarUsuario(string $cpf)
    {
        $model = new UsuarioModel();
        $usuario = $model->where('CPF', $cpf)->first();

        if (!$usuario) {
            return redirect()->to('/admin/usuarios')
                ->with('erro', 'Usuário não encontrado.');
        }

        $email = $this->request->getPost('email');

        $emailExistente = $model->where('EMAIL', $email)
            ->where('CPF !=', $cpf)
            ->first();
        if ($emailExistente) {
            return redirect()->to('/admin/usuarios')
                ->with('erro', 'E-mail já está em uso por outro usuário.');
        }

        $model->update($cpf, [
            'NOME' => $this->request->getPost('nome'),
            'EMAIL' => $email,
            'CARTAO' => $this->request->getPost('cartao') ?? '',
            'DATA_NASCIMENTO' => $this->request->getPost('data_nascimento'),
            'TELEFONE' => $this->request->getPost('telefone'),
        ]);

        return redirect()->to('/admin/usuarios')
            ->with('sucesso', 'Usuário ' . $cpf . ' atualizado com sucesso!');
    }

    public function excluirUsuario(string $cpf)
    {
        (new UsuarioModel())->delete($cpf);
        return redirect()->to('/admin/usuarios')
            ->with('sucesso', 'Usuário removido com sucesso.');
    }

    // ---------------------------------------------------------------
    // GESTÃO DE SENSORES
    // ---------------------------------------------------------------

    public function sensores(): string
    {
        return view('sistema/admin/sensores/index', [
            'sensores' => (new SensorModel())->findAll(),
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
                return redirect()->to('/admin/sensor/novo')
                    ->with('erro', 'A imagem deve ter no máximo 2 MB.');
            }
            $novoNome = $arquivo->getRandomName();
            $arquivo->move(ROOTPATH . 'public/uploads/sensores', $novoNome);
            $foto = 'uploads/sensores/' . $novoNome;
        }

        (new SensorModel())->insert([
            'NOME' => $this->request->getPost('nome'),
            'DESCRICAO' => $this->request->getPost('descricao'),
            'CIRCUITO' => $this->request->getPost('circuito') ?? '',
            'FOTO' => $foto,
        ]);

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
        return redirect()->to('/admin/sensores')
            ->with('sucesso', 'Sensor removido.');
    }

    // ---------------------------------------------------------------
    // GESTÃO DE PERGUNTAS E ALTERNATIVAS
    // ---------------------------------------------------------------

    public function perguntas(): string
    {
        return view('sistema/admin/quiz/index', [
            'perguntas' => (new PerguntaModel())->listarComAlternativas(),
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

        return redirect()->to('/admin/perguntas')
            ->with('sucesso', 'Pergunta atualizada com sucesso.');
    }

    public function excluirPergunta(int $id)
    {
        (new PerguntaModel())->delete($id);
        return redirect()->to('/admin/perguntas')
            ->with('sucesso', 'Pergunta e alternativas removidas com sucesso.');
    }

    // ---------------------------------------------------------------
    // PERFIL DO ADMINISTRADOR
    // ---------------------------------------------------------------

    public function perfil(): string
    {
        $cnpj = session()->get('admin_cnpj');
        $model = new AdminModel();
        return view('sistema/admin/perfil_adm', [
            'admin' => $model->find($cnpj),
        ]);
    }

    public function atualizarPerfilAdmin()
    {
        $cnpj  = session()->get('admin_cnpj');
        $model = new AdminModel();
        $email = $this->request->getPost('email');

        $emailExistente = $model->where('EMAIL', $email)->where('CNPJ !=', $cnpj)->first();
        if ($emailExistente) {
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

        return redirect()->to('/admin/perfil')
            ->with('sucesso', 'Perfil atualizado com sucesso!');
    }
}