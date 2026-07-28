<?= view('sistema/layout/header_adm') ?>

<div class="layout">
    <?= view('sistema/admin/_sidebar', ['ativo' => 'sensores']) ?>

    <div class="main">
        <div class="content">

            <div class="topbar">
                <h1>Editar Sensor</h1>
                <p>
                    <a href="<?= base_url('/admin/sensores') ?>" style="color: var(--primary);">
                        <i class="fa-solid fa-arrow-left"></i> Voltar à lista
                    </a>
                </p>
            </div>

            <?= view('sistema/layout/_flash') ?>

            <?php if (!$sensor): ?>
                <div style="background: #fee2e2; padding: 14px 18px; border-radius: 12px; color: #991b1b;">
                    Sensor não encontrado.
                </div>
            <?php else: ?>
            
                <section class="card sensor-card">
                    <form
                        action="<?= base_url('/admin/sensor/atualizar/' . $sensor['ID_SENSOR']) ?>"
                        method="post"
                        enctype="multipart/form-data"
                        class="form-grid"
                    >
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label>Nome do sensor</label>
                            <input type="text" name="nome" value="<?= esc($sensor['NOME']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Descrição</label>
                            <input type="text" name="descricao" value="<?= esc($sensor['DESCRICAO']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Circuito / Montagem</label>
                            <textarea name="circuito" rows="4"><?= esc($sensor['CIRCUITO'] ?? '') ?></textarea>
                        </div>

                        <?php if (!empty($sensor['FOTO'])): ?>
                            <img src="<?= base_url($sensor['FOTO']) ?>" alt="Foto do sensor" style="width: 150px; border-radius: 12px; margin-top: 10px;">
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Nova foto (opcional)</label>
                            <input type="file" name="foto" accept="image/*">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Salvar alterações
                            </button>
                        </div>
                    </form>
                </section>

            <?php endif; ?>

        </div><!-- /.content -->
    </div><!-- /.main -->
</div><!-- /.layout -->

<style>
/* Layout Container */
.layout-container { display: flex; min-height: 100vh; width: 100%; }
.sidebar-col { width: 280px; flex-shrink: 0; }
.main-content { flex: 1; padding: 30px; overflow-y: auto; background: var(--bg); }
.content-wrapper { width: 100%; max-width: 1000px; margin: 0 auto; }

/* Card */
.card {
    background: var(--card);
    border-radius: 20px;
    padding: 25px;
    box-shadow: var(--shadow, 0 5px 20px rgba(0,0,0,.08));
    border: 1px solid var(--border);
    margin-top: 20px;
}

/* Formulário */
.form-grid { display: flex; flex-direction: column; gap: 15px; }
.form-group { width: 100%; }
.form-group label {
    display: block; margin-bottom: 6px; font-weight: 600; color: var(--text);
}
.form-group input,
.form-group textarea {
    width: 100%; padding: 12px; border: 1px solid var(--border);
    border-radius: 12px; font-size: 15px; box-sizing: border-box;
    background: var(--bg); color: var(--text);
    transition: border-color .2s;
}
.form-group input:hover,
.form-group textarea:hover { border-color: #93c5fd; }
.form-group input:focus,
.form-group textarea:focus { outline: none; border-color: #1e6be7; }
.form-group textarea { min-height: 100px; }

/* Botão */
.btn-primary {
    display: inline-flex; align-items: center; gap: 10px;
    background: #1e6be7; color: #fff; border: none;
    padding: 12px 20px; border-radius: 12px; cursor: pointer;
    font-size: 15px; font-weight: 600;
}
.btn-primary:hover { background: #1557c4; }

/* Topbar */
.topbar {
    display: flex; justify-content: space-between;
    align-items: center; margin-bottom: 20px;
}
.topbar h1 { margin: 0; font-size: 28px; color: var(--text); }
.topbar p  { margin: 0; }
</style>

<?= view('sistema/layout/footer_adm') ?>