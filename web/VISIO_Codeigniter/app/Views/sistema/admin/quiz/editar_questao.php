<?= view('sistema/layout/header_adm') ?>

<div class="layout">
    <?= view('sistema/admin/_sidebar', ['ativo' => 'perguntas']) ?>

    <div class="main">
        <div class="content">

            <div class="topbar">
                <h1>Editar Questão</h1>
                <p>
                    <a href="<?= base_url('/admin/perguntas') ?>" style="color: var(--primary);">
                        <i class="fa-solid fa-arrow-left"></i> Voltar à lista
                    </a>
                </p>
            </div>

            <?= view('sistema/layout/_flash') ?>

            <?php if (!$pergunta): ?>
                <div style="background: #fee2e2; padding: 14px 18px; border-radius: 12px; color: #991b1b;">
                    Questão não encontrada.
                </div>
            <?php else: ?>
            
                <section class="card">
                    <form action="<?= base_url('/admin/pergunta/atualizar/' . $pergunta['ID_PERGUNTA']) ?>" method="post" class="form-grid">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label>Enunciado da pergunta</label>
                            <textarea name="descricao" rows="4" required><?= esc($pergunta['DESCRICAO']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Nível de dificuldade</label>
                            <select name="nivel">
                                <option value="Fácil" <?= $pergunta['NIVEL_DIFICULDADE'] === 'Fácil' ? 'selected' : '' ?>>Fácil</option>
                                <option value="Médio" <?= $pergunta['NIVEL_DIFICULDADE'] === 'Médio' ? 'selected' : '' ?>>Médio</option>
                                <option value="Difícil" <?= $pergunta['NIVEL_DIFICULDADE'] === 'Difícil' ? 'selected' : '' ?>>Difícil</option>
                            </select>
                        </div>

                        <div class="section-title">
                            <h3>Alternativas</h3>
                            <p class="hint">Marque o radio ao lado da alternativa correta.</p>
                        </div>

                        <?php
                        $letras = ['A', 'B', 'C', 'D'];
                        foreach ($pergunta['alternativas'] as $i => $alt):
                        ?>
                        <div class="alternativa-row">
                            <label class="radio-container">
                                <input type="radio" name="correta" value="<?= esc($alt['ID_ALTERNATIVA']) ?>" <?= $alt['IS_CORRETA'] ? 'checked' : '' ?> required>
                                <span class="radio-custom"></span>
                            </label>
                            <input type="hidden" name="id_alternativa[]" value="<?= esc($alt['ID_ALTERNATIVA']) ?>">
                            <span class="letra"><?= $letras[$i] ?? ($i+1) ?>)</span>
                            <input type="text" name="alternativa[]" value="<?= esc($alt['DESCRICAO']) ?>" placeholder="Digite a alternativa <?= $letras[$i] ?? ($i+1) ?>" required>
                        </div>
                        <?php endforeach; ?>

                        <div class="form-group" style="margin-top: 10px;">
                            <button type="submit" class="btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i> Salvar alterações
                            </button>
                        </div>
                    </form>
                </section>

            <?php endif; ?>

        </div><!-- /.content -->
    </div><!-- /.main -->
</div><!-- /.layout -->

<style>
/* Classes de layout antigas — mantidas apenas para não quebrar possíveis refs; HTML usa .layout/.main */
.layout-container { display: flex; min-height: 100vh; width: 100%; }
.sidebar-col { width: 280px; flex-shrink: 0; }
.main-content { flex: 1; padding: 30px; overflow-y: auto; background: var(--bg); }
.content-wrapper { width: 100%; max-width: 900px; margin: 0 auto; }

/* Topbar */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}
.topbar h1 { margin: 0; font-size: 28px; color: var(--text); }
.topbar p  { margin: 0; }

/* Card */
.card {
    background: var(--card);
    border-radius: 20px;
    padding: 30px;
    box-shadow: var(--shadow, 0 4px 20px rgba(0,0,0,.06));
    border: 1px solid var(--border);
}

/* Formulário */
.form-grid { display: flex; flex-direction: column; gap: 20px; }
.form-group { width: 100%; }
.form-group label {
    display: block; margin-bottom: 8px; font-weight: 600;
    color: var(--text); font-size: 14px;
}
.form-group input[type="text"],
.form-group textarea,
.form-group select {
    width: 100%; padding: 12px 15px; border: 1px solid var(--border);
    border-radius: 10px; font-size: 15px;
    background: var(--bg); color: var(--text);
    box-sizing: border-box; transition: border-color 0.2s;
}
.form-group input[type="text"]:hover,
.form-group textarea:hover,
.form-group select:hover { border-color: #93c5fd; }
.form-group input[type="text"]:focus,
.form-group textarea:focus,
.form-group select:focus { outline: none; border-color: #1e6be7; }
.form-group textarea { min-height: 100px; resize: vertical; }

/* Seção de alternativas */
.section-title {
    margin-top: 10px; padding-bottom: 10px;
    border-bottom: 1px solid var(--border);
}
.section-title h3 { margin: 0 0 5px 0; font-size: 18px; color: var(--text); }
.section-title .hint { margin: 0; font-size: 13px; color: var(--text2); }

/* Linha de alternativa */
.alternativa-row {
    display: flex; align-items: center; gap: 12px; padding: 10px;
    background: var(--bg); border-radius: 10px; border: 1px solid var(--border);
    transition: border-color .2s;
}
.alternativa-row:hover { border-color: #1e6be7; }
.alternativa-row .letra { font-weight: 700; min-width: 25px; color: var(--text); }
.alternativa-row input[type="text"] {
    flex: 1; padding: 10px 12px; border: 1px solid var(--border);
    border-radius: 8px; font-size: 14px;
    background: var(--card); color: var(--text);
    transition: border-color .2s;
}
.alternativa-row input[type="text"]:focus { outline: none; border-color: #1e6be7; }

/* Radio button */
.radio-container {
    position: relative; display: flex; align-items: center;
    justify-content: center; cursor: pointer;
}
.radio-container input { position: absolute; opacity: 0; cursor: pointer; }
.radio-custom {
    width: 20px; height: 20px; border: 2px solid var(--border);
    border-radius: 50%; background: var(--bg); transition: all 0.2s;
}
.radio-container:hover .radio-custom { border-color: #1e6be7; }
.radio-container input:checked + .radio-custom { border-color: #1e6be7; background: #1e6be7; }
.radio-container input:checked + .radio-custom::after {
    content: ''; position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%); width: 8px; height: 8px;
    background: #fff; border-radius: 50%;
}

/* Botão */
.btn-primary {
    display: inline-flex; align-items: center; gap: 10px;
    background: #1e6be7; color: #fff; border: none;
    padding: 14px 24px; border-radius: 10px; cursor: pointer;
    font-size: 15px; font-weight: 600; transition: background 0.2s;
}
.btn-primary:hover { background: #1557c4; }
</style>

<?= view('sistema/layout/footer_adm') ?>