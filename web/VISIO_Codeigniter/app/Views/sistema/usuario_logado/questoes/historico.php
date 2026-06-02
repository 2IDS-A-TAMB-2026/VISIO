<?= view('sistema/layout/header_logado') ?>


    <main class="questoes-page">
        <div class="questoes-container">
            <h1 class="questoes-titulo">Histórico de Respostas</h1>

            <?php if (session()->getFlashdata('sucesso')): ?>
                <p class="contato-feedback contato-feedback--ok"><?= session()->getFlashdata('sucesso') ?></p>
            <?php endif; ?>
            <?php if (session()->getFlashdata('erro')): ?>
                <p class="contato-feedback contato-feedback--erro"><?= session()->getFlashdata('erro') ?></p>
            <?php endif; ?>

            <p>Total de respostas: <strong><?= $total ?></strong> | Acertos: <strong><?= $total_acertos ?></strong></p>

            <?php if (empty($respostas)): ?>
                <p>Nenhuma resposta registrada ainda.</p>
            <?php else: ?>
                <table class="adm-table" style="width:100%; margin-top:1rem; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pergunta</th>
                            <th>Resposta</th>
                            <th>Resultado</th>
                            <th>Data</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($respostas as $r): ?>
                            <tr>
                                <td><?= $r['ID_RESPOSTA'] ?? $r['ID'] ?></td>
                                <td><?= esc($r['PERGUNTA'] ?? '—') ?></td>
                                <td><?= esc($r['ALTERNATIVA'] ?? '—') ?></td>
                                <td><?= ($r['CORRETA_ERRADA'] ?? 0) ? '✅ Acerto' : '❌ Erro' ?></td>
                                <td><?= $r['DATA_HORA'] ?? '—' ?></td>
                                <td>
                                    <a href="<?= base_url('/resposta/excluir/' . ($r['ID_RESPOSTA'] ?? $r['ID'])) ?>"
                                       onclick="return confirm('Remover esta resposta?')"
                                       style="color: red;">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>


<?= view('sistema/layout/footer') ?>
