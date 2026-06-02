<?= view('sistema/layout/header') ?>


    <main class="sensores-page-main">
        <div class="sensores-grid" style="max-width: 700px; margin: 2rem auto;">
            <h1 class="sensores-page-title"><?= esc($sensor['DESCRICAO']) ?></h1>

            <?php if (!empty($sensor['FOTO'])): ?>
                <img src="<?= base_url($sensor['FOTO']) ?>" alt="Foto do sensor" style="max-width: 100%; border-radius: 8px; margin: 1rem 0;">
            <?php endif; ?>

            <?php if (!empty($sensor['CIRCUITO'])): ?>
                <h2 style="margin-top: 1.5rem;">Circuito</h2>
                <p><?= nl2br(esc($sensor['CIRCUITO'])) ?></p>
            <?php endif; ?>

            <div style="margin-top: 2rem;">
                <a href="<?= base_url('/sensor') ?>" class="animated-button">← Voltar aos sensores</a>
            </div>
        </div>
    </main>


<?= view('sistema/layout/footer') ?>
