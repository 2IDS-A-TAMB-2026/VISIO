<?= view('sistema/layout/header') ?>

<style>
/* Estilo Base - Gradiente azul mantido para todos os temas */
body {
    background-color: #000000 !important;
    background-image: 
        radial-gradient(circle at top right, #0055ff6f 0%, transparent 40%),
        radial-gradient(circle at bottom left, #0055ff6f 0%, transparent 40%) !important;
    background-attachment: fixed !important;
    color: #ffffff;
    font-family: sans-serif;
    min-height: 100vh;
    margin: 0;
}

/* Tema Claro - Fundo branco mantendo o degradê azul por cima */
body.light {
    background-color: #ffffff !important;
    color: #0f172a;
}

.swal-popup  { border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,.20); }
.swal-titulo { text-align: center; font-size: 26px; font-weight: bold; color: #3a86ff; }
.cardi       { cursor: pointer; }

/* ============================================================
   REGRA DE ALTO CONTRASTE: REMOVE O QUADRADO/FUNDO BRANCO
   ============================================================ */
html.high-contrast,
body.high-contrast,
.high-contrast .sensores-page-main {
    background-color: #000000 !important;
    background-image: none !important;
    color: #ffffff !important;
}

/* Força os cards e as imagens dentro do Alto Contraste a ficarem sem fundo branco */
.high-contrast .cardi {
    background-color: #000000 !important;
    background: #000000 !important;
}

.high-contrast .cardi img {
    background-color: transparent !important;
    background: transparent !important;
    border-radius: 0 !important;
}
</style>

<main class="sensores-page-main">
    <br><br><br>
    <h1 class="sensores-page-title">Catálogo de Sensores IoT</h1>
    <p class="sensores-page-lead">Explore os sensores cadastrados na plataforma VISIO.</p>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    var sensoresData = <?= json_encode(array_map(function($s) {
        return [
            'id'       => $s['ID_SENSOR'],
            'nome'     => $s['NOME'],
            'descricao'=> $s['DESCRICAO'],
            'foto'     => !empty($s['FOTO']) ? base_url($s['FOTO']) : null,
            'circuito' => $s['CIRCUITO'] ?? null,
        ];
    }, $sensores ?? []), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    function mostrarSensor(id) {
        var s = sensoresData.find(function(x){ return x.id == id; });
        if (!s) return;

        var imagemHtml = s.foto
            ? '<img src="' + s.foto + '" style="max-width:100%;max-height:220px;object-fit:contain;border-radius:10px;margin-bottom:1rem;" onerror="this.style.display=\'none\'">'
            : '';

        var circuitoHtml = s.circuito
            ? '<p><b>Circuito:</b> ' + s.circuito.replace(/\n/g, '<br>') + '</p>'
            : '';

        Swal.fire({
            title: s.nome,
            html: imagemHtml +
                '<div style="text-align:left;font-size:15px;line-height:1.8;padding:6px 10px;">' +
                    '<p><b>Descrição:</b> ' + s.descricao + '</p>' +
                    circuitoHtml +
                '</div>',
            confirmButtonText: 'Fechar',
            confirmButtonColor: '#3a86ff',
            background: '#ffffff',
            color: '#222',
            width: '650px',
            padding: '1.5em',
            customClass: {
                title: 'swal-titulo',
                popup: 'swal-popup'
            }
        });
    }
    </script>

    <?php if (empty($sensores)): ?>
        <div style="text-align:center;padding:3rem;color:var(--color-text-secondary);">
            <i class="fa-solid fa-microchip" style="font-size:48px;margin-bottom:1rem;display:block;opacity:.3;"></i>
            <p>Nenhum sensor cadastrado ainda.</p>
            <?php if (session()->get('admin_logado')): ?>
                <a href="<?= base_url('/admin/sensor/novo') ?>" style="margin-top:1rem;display:inline-block;">
                    Cadastrar primeiro sensor
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="sensores-grid">
            <?php foreach ($sensores as $s): ?>
                <div class="cardi" onclick="mostrarSensor(<?= $s['ID_SENSOR'] ?>)">

                    <?php if (!empty($s['FOTO'])): ?>
                        <img src="<?= base_url($s['FOTO']) ?>"
                             alt="<?= esc($s['NOME']) ?>"
                             style="width:100%;max-height:160px;object-fit:cover;border-radius:10px;margin-bottom:10px;"
                             onerror="this.style.display='none'">
                    <?php else: ?>
                        <div style="width:100%;height:80px;display:flex;align-items:center;justify-content:center;opacity:.3;font-size:48px;">
                            <i class="fa-solid fa-microchip"></i>
                        </div>
                    <?php endif; ?>

                    <h3><?= esc($s['NOME']) ?></h3>
                    <p><?= esc($s['DESCRICAO']) ?></p>

                    <span style="display:inline-block;margin-top:8px;font-size:12px;color:var(--color-accent,#3a86ff);font-weight:600;">
                        Ver detalhes →
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?= view('sistema/layout/footer') ?>