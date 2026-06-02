<?= view('sistema/layout/header') ?>


    <main class="questoes-page">
        <div class="questoes-container questoes-layout">
            <div class="questao-box">

                <?php if (session()->getFlashdata('erro')): ?>
                    <p class="contato-feedback contato-feedback--erro"><?= session()->getFlashdata('erro') ?></p>
                <?php endif; ?>

                <p class="questoes-progresso">Pergunta <?= $indice ?> de <?= $total ?></p>
                <h1 class="questoes-titulo"><?= esc($pergunta['DESCRICAO']) ?></h1>

                <form action="<?= base_url('/quiz/responder') ?>" method="post" class="alternativas-form">
                    <?= csrf_field() ?>
                    <?php foreach ($pergunta['alternativas'] as $alt): ?>
                        <label class="alternativa-item">
                            <input type="radio" name="id_alternativa" value="<?= $alt['ID_ALTERNATIVA'] ?>" required>
                            <?= esc($alt['DESCRICAO']) ?>
                        </label>
                    <?php endforeach; ?>
                    <button type="submit" style="color:#fff; margin-top: 1rem;">Responder</button>
                </form>

            </div>
        </div>
    </main>


<?= view('sistema/layout/footer') ?>
