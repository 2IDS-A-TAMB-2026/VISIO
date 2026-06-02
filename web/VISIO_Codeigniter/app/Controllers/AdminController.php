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
    // DASHBOARD
    // ---------------------------------------------------------------

    public function dashboard(): string
    {
        return view('sistema/admin/login_adm', [
            'total_usuarios' => (new UsuarioModel())->countAllResults(), // countAllResults é nativo do CI4
            'total_sensores' => (new SensorModel())->countAllResults(),
            'total_perguntas' => (new PerguntaModel())->countAllResults(),
            'total_respostas' => (new RespondeModel())->countAllResults(),
        ]);
    }

    // ---------------------------------------------------------------
    // GESTÃO DE USUÁRIOS
    // ---------------------------------------------------------------

    public function usuarios(): string
    {
        return view('sistema/admin/usuarios/index', [
            'usuarios' => (new UsuarioModel())->findAll(), // findAll() é nativo do CI4
        ]);
    }

    public function excluirUsuario(string $cpf)
    {
        (new UsuarioModel())->delete($cpf); // delete() é nativo do CI4
        return redirect()->to('/admin/usuarios')
            ->with('sucesso', 'Usuário removido com sucesso.');
    }

    // ---------------------------------------------------------------
    // GESTÃO DE SENSORES
    // ---------------------------------------------------------------

    public function sensores(): string
    {
        return view('sistema/admin/c_sensor/index', [
            'sensores' => (new SensorModel())->findAll(),
        ]);
    }

    public function novoSensorForm(): string
    {
        return view('sistema/admin/c_sensor/novo_sensor');
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

        // CORREÇÃO: Removido 'CIRCUITO' e adicionado 'NOME' (alinhado com o Banco de Dados)
        (new SensorModel())->insert([
            'NOME' => $this->request->getPost('nome'),
            'DESCRICAO' => $this->request->getPost('descricao'),
            'FOTO' => $foto,
        ]);

        return redirect()->to('/admin/sensores')
            ->with('sucesso', 'Sensor cadastrado com sucesso.');
    }

    public function editarSensorForm(int $id): string
    {
        return view('sistema/admin/c_sensor/editar_sensor', [
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
            $novoNome = $arquivo->getRandomName();
            $arquivo->move(WRITEPATH . 'uploads/sensores', $novoNome);
            $foto = 'uploads/sensores/' . $novoNome;
        }

        // CORREÇÃO: Removido 'CIRCUITO' e adicionado 'NOME'
        $model->update($id, [
            'NOME' => $this->request->getPost('nome'),
            'DESCRICAO' => $this->request->getPost('descricao'),
            'FOTO' => $foto,
        ]);

        return redirect()->to('/admin/sensores')
            ->with('sucesso', 'Sensor atualizado com sucesso.');
    }

    public function excluirSensor(int $id)
    {
        (new SensorModel())->delete($id);
        return redirect()->to('/admin/sensores')
            ->with('sucesso', 'Sensor removido.');
    }

    // ---------------------------------------------------------------
    // GESTÃO DE PERGUNTAS E ALTERNATIVAS
    // ---------------------------------------------------------------

    public function perguntas(): string
    {
        return view('sistema/admin/c_questoes/index', [
            'perguntas' => (new PerguntaModel())->listarComAlternativas(), // Necessita método no Model
        ]);
    }

    public function novaPerguntaForm(): string
    {
        return view('sistema/admin/c_questoes/nova_questao');
    }

    public function inserirPergunta()
    {
        $perguntaModel = new PerguntaModel();
        $alternativaModel = new AlternativaModel();

        // CI4 retorna o ID inserido automaticamente se a chave primária for AUTO_INCREMENT
        $idPergunta = $perguntaModel->insert([
            'DESCRICAO' => $this->request->getPost('descricao'),
            'NIVEL_DIFICULDADE' => $this->request->getPost('nivel_dificuldade'),
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
                    'IS_CORRETA' => ($alt['letra'] === $correta) ? 1 : 0, // CORREÇÃO DO NOME DA COLUNA
                    'FK_ID_PERGUNTA' => $idPergunta,
                ];
            }

            // Usando o método nativo do CI4 para inserir múltiplos registros de uma vez
            $alternativaModel->insertBatch($lote);
        }

        return redirect()->to('/admin/perguntas')
            ->with('sucesso', 'Pergunta e alternativas cadastradas com sucesso.');
    }

    public function editarPerguntaForm(int $id): string
    {
        return view('sistema/admin/c_questoes/editar_questao', [
            'pergunta' => (new PerguntaModel())->buscarComAlternativas($id),
        ]);
    }

    public function atualizarPergunta(int $id)
    {
        $perguntaModel = new PerguntaModel();
        $alternativaModel = new AlternativaModel();

        $perguntaModel->update($id, [
            'DESCRICAO' => $this->request->getPost('descricao'),
            'NIVEL_DIFICULDADE' => $this->request->getPost('nivel_dificuldade'),
        ]);

        $idsAlternativas = $this->request->getPost('id_alternativa');
        $textos = $this->request->getPost('alternativa');
        $correta = $this->request->getPost('correta');

        if (is_array($idsAlternativas)) {
            foreach ($idsAlternativas as $i => $idAlt) {
                $alternativaModel->update((int) $idAlt, [
                    'DESCRICAO' => $textos[$i],
                    'IS_CORRETA' => ($idAlt == $correta) ? 1 : 0, // CORREÇÃO DO NOME DA COLUNA
                ]);
            }
        }

        return redirect()->to('/admin/perguntas')
            ->with('sucesso', 'Pergunta atualizada com sucesso.');
    }

    public function excluirPergunta(int $id)
    {
        // OTIMIZAÇÃO: O MySQL cuidará de apagar as alternativas por causa do ON DELETE CASCADE
        (new PerguntaModel())->delete($id);

        return redirect()->to('/admin/perguntas')
            ->with('sucesso', 'Pergunta e alternativas removidas com sucesso.');
    }

    // ---------------------------------------------------------------
    // HISTÓRICO GERAL DE RESPOSTAS
    // ---------------------------------------------------------------

    public function respostas(): string
    {
        return view('sistema/admin/c_questoes_2/index', [
            'respostas' => (new RespondeModel())->listarComDetalhes(), // Necessita método no Model com JOIN
        ]);
    }
}