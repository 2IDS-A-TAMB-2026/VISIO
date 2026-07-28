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

        <?php
            // Dados fornecidos por RespostaController::historico() (chave 'respostas')
            $lista  = $respostas ?? $historico ?? [];
            $qtd    = count($lista);
            $acertos = 0;
            foreach ($lista as $r) {
                if (!empty($r['IS_CORRETA'])) $acertos++;
            }
        ?>

        <p style="margin-bottom:1rem;">
            Total de respostas: <strong><?= $qtd ?></strong> |
            Acertos: <strong><?= $acertos ?></strong>
            <?php if ($qtd > 0): ?>
                | Taxa: <strong><?= round(($acertos / $qtd) * 100) ?>%</strong>
            <?php endif; ?>
        </p>

        <?php if (empty($lista)): ?>
            <p style="color:var(--color-text-secondary);">Nenhuma resposta registrada ainda. Faça o quiz para começar!</p>
            <a href="<?= base_url('/quiz') ?>" class="animated-button" style="margin-top:1rem;display:inline-block;">Iniciar Quiz</a>
        <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="adm-table" style="width:100%;margin-top:1rem;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:12px;border-bottom:2px solid var(--color-border);">#</th>
                        <th style="text-align:left;padding:12px;border-bottom:2px solid var(--color-border);">Pergunta</th>
                        <th style="text-align:left;padding:12px;border-bottom:2px solid var(--color-border);">Resposta dada</th>
                        <th style="text-align:left;padding:12px;border-bottom:2px solid var(--color-border);">Nível</th>
                        <th style="text-align:left;padding:12px;border-bottom:2px solid var(--color-border);">Resultado</th>
                        <th style="text-align:left;padding:12px;border-bottom:2px solid var(--color-border);">Data/Hora</th>
                        <th style="text-align:left;padding:12px;border-bottom:2px solid var(--color-border);">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista as $i => $r): ?>
                        <tr style="border-bottom:1px solid var(--color-border);">
                            <td style="padding:12px;"><?= $i + 1 ?></td>
                            <td style="padding:12px;"><?= esc($r['PERGUNTA_TEXTO'] ?? '—') ?></td>
                            <td style="padding:12px;"><?= esc($r['ALTERNATIVA_TEXTO'] ?? '—') ?></td>
                            <td style="padding:12px;"><?= esc($r['NIVEL_DIFICULDADE'] ?? '—') ?></td>
                            <td style="padding:12px;">
                                <?= !empty($r['IS_CORRETA']) ? '✅ Acerto' : '❌ Erro' ?>
                            </td>
                            <td style="padding:12px;"><?= esc($r['RESPONDIDO_EM'] ?? '—') ?></td>
                            <td style="padding:12px;">
                                <form action="<?= base_url('/resposta/excluir/' . esc($r['ID_RESPONDE'])) ?>"
                                      method="POST"
                                      onsubmit="return confirm('Remover esta resposta do histórico?')"
                                      style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                            style="color:#ef4444;font-weight:600;background:none;border:none;cursor:pointer;padding:0;font-size:inherit;font-family:inherit;">
                                        <i class="fa-solid fa-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<?= view('sistema/layout/footer') ?>
