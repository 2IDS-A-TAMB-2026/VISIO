<?php

namespace App\Controllers;

use App\Models\SensorModel;

/**
 * Sensor
 * Catálogo público de sensores (somente leitura).
 *
 * CORRIGIDO: passa a responder em JSON quando a requisição pede JSON (usado
 * pelo app Flutter, ver `sensores.dart`), mantendo a view HTML como
 * comportamento padrão para navegação normal no navegador.
 *
 * A detecção inclui o header `Accept` além de `Content-Type`/`isAJAX()`
 * (padrão já usado em AuthController::loginUsuario) porque estas são
 * chamadas GET sem corpo — `ApiClient.get()`, no Flutter, manda apenas
 * `Accept: application/json`; nunca envia Content-Type nem
 * X-Requested-With numa requisição sem corpo.
 *
 * Tipo de retorno `: string` removido dos dois métodos — o branch JSON
 * devolve um objeto Response, não uma string.
 */
class SensorController extends BaseController
{
    private function querJson(): bool
    {
        return $this->request->isAJAX()
            || str_contains($this->request->getHeaderLine('Accept'), 'json')
            || str_contains($this->request->getHeaderLine('Content-Type'), 'json');
    }

    public function index()
    {
        $sensores = (new SensorModel())->listar();

        if ($this->querJson()) {
            return $this->response->setJSON($sensores);
        }

        return view('sistema/usuario/sensores/index', [
            'sensores' => $sensores,
        ]);
    }

    public function detalhe(int $id)
    {
        $sensor = (new SensorModel())->buscarPorId($id);

        if (!$sensor) {
            if ($this->querJson()) {
                return $this->response->setStatusCode(404)->setJSON([
                    'message' => 'Sensor não encontrado.',
                ]);
            }
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->querJson()) {
            return $this->response->setJSON($sensor);
        }

        return view('sistema/usuario/sensores/detalhe', ['sensor' => $sensor]);
    }
}
