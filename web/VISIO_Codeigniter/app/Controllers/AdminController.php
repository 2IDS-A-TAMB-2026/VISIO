<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\UsuarioModel;
use App\Models\SensorModel;
use App\Models\PerguntaModel;
use App\Models\AlternativaModel;
use App\Models\RespondeModel;

/**
 * Admin
 * Painel administrativo. Todas as rotas exigem sessão de admin ativa.
 * A verificação de sessão é feita no construtor via initController.
 *
 * Rotas esperadas:
 *   GET  /admin/dashboard
 *   GET  /admin/usuarios
 *   GET  /admin/usuario/excluir/(:cpf)
 *   GET  /admin/sensores          → lista
 *   GET  /admin/sensor/novo       → form
 *   POST /admin/sensor/inserir    → salva
 *   GET  /admin/sensor/editar/(:id)
 *   POST /admin/sensor/atualizar/(:id)
 *   GET  /admin/sensor/excluir/(:id)
 *   GET  /admin/perguntas         → lista
 *   GET  /admin/pergunta/nova
 *   POST /admin/pergunta/inserir
 *   GET  /admin/pergunta/editar/(:id)
 *   POST /admin/pergunta/atualizar/(:id)
 *   GET  /admin/pergunta/excluir/(:id)
 *   GET  /admin/respostas         → histórico geral
 */
class Admin extends BaseController
{
    // Verificação de sessão admin em toda requisição
    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        if (!session()->get('admin_logado')) {
            header('Location: ' . base_url('/login/admin'));
            exit;
        }
    }

    // ---------------------------------------------------------------
    // DASHBOARD
    // ---------------------------------------------------------------

    public function dashboard(): string
    {
        $dados['total_usuarios'] = (new UsuarioModel())->countAll();
        $dados['total_sensores'] = (new SensorModel())->countAll();
        $dados['total_perguntas'] = (new PerguntaModel())->countAll();
        $dados['total_respostas'] = (new RespondeModel())->countAll();

        return view('admin/dashboard', $dados);
    }

    // ---------------------------------------------------------------
    // GESTÃO DE USUÁRIOS
    // ---------------------------------------------------------------

    public function usuarios(): string
    {
        $dados['usuarios'] = (new UsuarioModel())->listar();
        return view('admin/usuarios/index', $dados);
    }

    public function excluirUsuario(string $cpf)
    {
        (new UsuarioModel())->excluir($cpf);
        return redirect()->to('/admin/usuarios')
            ->with('sucesso', 'Usuário removido.');
    }

    // ---------------------------------------------------------------
    // GESTÃO DE SENSORES
    // ---------------------------------------------------------------

    public function sensores(): string
    {
        $dados['sensores'] = (new SensorModel())->listar();
        return view('admin/sensores/index', $dados);
    }

    public function novoSensorForm(): string
    {
        return view('admin/sensores/novo');
    }

    public function inserirSensor()
    {
        $foto = '';
        $arquivo = $this->request->getFile('foto');

        if ($arquivo && $arquivo->isValid() && !$arquivo->hasMoved()) {
            $novoNome = $arquivo->getRandomName();
            $arquivo->move(WRITEPATH . 'uploads/sensores', $novoNome);
            $foto = 'uploads/sensores/' . $novoNome;
        }

        (new SensorModel())->inserir([
            'DESCRICAO' => $this->request->getPost('descricao'),
            'CIRCUITO' => $this->request->getPost('circuito'),
            'FOTO' => $foto,
        ]);

        return redirect()->to('/admin/sensores')
            ->with('sucesso', 'Sensor cadastrado.');
    }

    public function editarSensorForm(int $id): string
    {
        $dados['sensor'] = (new SensorModel())->buscarPorId($id);
        return view('admin/sensores/editar', $dados);
    }

    public function atualizarSensor(int $id)
    {
        $model = new SensorModel();
        $sensor = $model->buscarPorId($id);

        $foto = $sensor['FOTO'] ?? '';

        $arquivo = $this->request->getFile('foto');
        if ($arquivo && $arquivo->isValid() && !$arquivo->hasMoved()) {
            $novoNome = $arquivo->getRandomName();
            $arquivo->move(WRITEPATH . 'uploads/sensores', $novoNome);
            $foto = 'uploads/sensores/' . $novoNome;
        }

        $model->atualizar($id, [
            'DESCRICAO' => $this->request->getPost('descricao'),
            'CIRCUITO' => $this->request->getPost('circuito'),
            'FOTO' => $foto,
        ]);

        return redirect()->to('/admin/sensores')
            ->with('sucesso', 'Sensor atualizado.');
    }

    public function excluirSensor(int $id)
    {
        (new SensorModel())->excluir($id);
        return redirect()->to('/admin/sensores')
            ->with('sucesso', 'Sensor removido.');
    }

    // ---------------------------------------------------------------
    // GESTÃO DE PERGUNTAS
    // ---------------------------------------------------------------

    public function perguntas(): string
    {
        $dados['perguntas'] = (new PerguntaModel())->listarComAlternativas();
        return view('admin/perguntas/index', $dados);
    }

    public function novaPerguntaForm(): string
    {
        return view('admin/perguntas/nova');
    }

    public function inserirPergunta()
    {
        $perguntaModel = new PerguntaModel();
        $alternativaModel = new AlternativaModel();

        $idPergunta = $perguntaModel->inserir([
            'DESCRICAO' => $this->request->getPost('descricao'),
            'NIVEL_DIFICULDADE' => $this->request->getPost('nivel_dificuldade'),
        ]);

        if ($idPergunta) {
            $correta = $this->request->getPost('correta'); // 'a', 'b', 'c' ou 'd'
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
                    'CORRETA_ERRADA' => ($alt['letra'] === $correta) ? 1 : 0,
                    'FK_ID_PERGUNTA' => $idPergunta,
                ];
            }

            $alternativaModel->inserirLote($lote);
        }

        return redirect()->to('/admin/perguntas')
            ->with('sucesso', 'Pergunta cadastrada com suas alternativas.');
    }

    public function editarPerguntaForm(int $id): string
    {
        $dados['pergunta'] = (new PerguntaModel())->buscarComAlternativas($id);
        return view('admin/perguntas/editar', $dados);
    }

    public function atualizarPergunta(int $id)
    {
        $perguntaModel = new PerguntaModel();
        $alternativaModel = new AlternativaModel();

        $perguntaModel->atualizar($id, [
            'DESCRICAO' => $this->request->getPost('descricao'),
            'NIVEL_DIFICULDADE' => $this->request->getPost('nivel_dificuldade'),
        ]);

        // Atualiza cada alternativa individualmente via ID
        $idsAlternativas = $this->request->getPost('id_alternativa');  // array
        $textos = $this->request->getPost('alternativa');      // array
        $correta = $this->request->getPost('correta');          // ID da alternativa correta

        if (is_array($idsAlternativas)) {
            foreach ($idsAlternativas as $i => $idAlt) {
                $alternativaModel->atualizar((int) $idAlt, [
                    'DESCRICAO' => $textos[$i],
                    'CORRETA_ERRADA' => ($idAlt == $correta) ? 1 : 0,
                ]);
            }
        }

        return redirect()->to('/admin/perguntas')
            ->with('sucesso', 'Pergunta atualizada.');
    }

    public function excluirPergunta(int $id)
    {
        // Remove alternativas vinculadas primeiro
        (new AlternativaModel())->excluirPorPergunta($id);
        (new PerguntaModel())->excluir($id);

        return redirect()->to('/admin/perguntas')
            ->with('sucesso', 'Pergunta e alternativas removidas.');
    }

    // ---------------------------------------------------------------
    // HISTÓRICO GERAL DE RESPOSTAS
    // ---------------------------------------------------------------

    public function respostas(): string
    {
        $dados['respostas'] = (new RespondeModel())->listarComDetalhes();
        return view('admin/respostas/index', $dados);
    }
}
