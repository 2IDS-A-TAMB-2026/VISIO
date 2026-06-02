<?= view('sistema/layout/header') ?>


    <main class="questoes-page">
        <div class="questoes-container questoes-layout">
            <div class="questao-box">
                <h1 class="questoes-titulo">Quiz IoT</h1>
                <p class="questoes-intro">Responda às questões cadastradas no sistema. As perguntas são carregadas
                    dinamicamente.</p>
                <div id="quiz-root"></div>
            </div>
        </div>
    </main>

</html>

<?= view('sistema/layout/footer') ?>