</div><!-- /.main -->

<style>
/* Footer admin sempre escuro independente do tema */
footer {
    background: #0b1120 !important;
    color: #64748b !important;
    text-align: center;
    padding: 16px;
    font-size: 13px;
    border-top: 1px solid #1e293b;
}
</style>
<footer>
    &copy; 2026 VISIO – Plataforma Inteligente para Sensores IoT.
</footer>

<script>
/* ================================================================
   footer_adm.php — script de tema/acessibilidade centralizado
   Incluído por TODAS as views administrativas via view('sistema/layout/footer_adm')
   ================================================================ */
(function () {
    'use strict';

    const toggle = document.getElementById('theme-toggle');

    /* ── Restaura tema salvo ── */
    if (localStorage.getItem('visio_adm_tema') === 'dark') {
        document.body.classList.add('dark');
    }
    /* Atualiza ícone de acordo com o tema atual ao carregar */
    if (toggle) {
        const moon = toggle.querySelector('#icon-moon');
        const sun  = toggle.querySelector('#icon-sun');
        if (document.body.classList.contains('dark')) {
            if (moon) moon.style.display = 'none';
            if (sun)  sun.style.display  = '';
        }

        toggle.addEventListener('click', function () {
            if (document.body.classList.contains('high-contrast')) return;
            document.body.classList.toggle('dark');
            const isDark = document.body.classList.contains('dark');
            localStorage.setItem('visio_adm_tema', isDark ? 'dark' : 'light');
            if (moon) moon.style.display = isDark ? 'none' : '';
            if (sun)  sun.style.display  = isDark ? ''     : 'none';
        });
    }

    /* ── Restaura alto contraste ── */
    if (localStorage.getItem('visio_contraste') === '1') {
        document.body.classList.add('high-contrast');
        if (toggle) {
            toggle.disabled      = true;
            toggle.style.opacity = '0.4';
            toggle.style.cursor  = 'not-allowed';
        }
    }

})();
</script>

</body>
</html>
