<style>
/* Footer sempre escuro independente do tema da página */
.footer {
    background: #0b1120 !important;
    color: #94a3b8 !important;
}
.footer h2, .footer h3 { color: #f1f5f9 !important; }
.footer p, .footer a, .footer i { color: #94a3b8 !important; }
.footer a:hover { color: #f1f5f9 !important; }
.footer .social a { color: #94a3b8 !important; }
.footer-bottom { background: #0b1120 !important; color: #64748b !important; }
.footer .linha-vertical { background: #1e293b !important; }
</style>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-left">
            <img class="logo" src="<?= base_url('assets/images/logos/Icone/IconeDark2.png') ?>"
                data-light="<?= base_url('assets/images/logos/Icone/IconeDark2.png') ?>"
                data-dark="<?= base_url('assets/images/logos/Icone/IconeDark2.png') ?>" alt="Logo">
            <div class="linha-vertical"></div>
            <div class="brand-text">
                <h2>VISIO</h2>
                <p>Plataforma educacional focada em tecnologia, sensores e aprendizado interativo.</p>
            </div>
        </div>
        <div class="footer-right">
            <div class="footer-links">
                <h3>Plataforma</h3>
                <a href="<?= base_url('/') ?>">Início</a>
                <a href="<?= base_url('/sobre') ?>">Sobre</a>
                <a href="<?= base_url('/sensor') ?>">Sensores</a>
                <a href="<?= base_url('/quiz') ?>">Questões</a>
                <a href="<?= base_url('/login/admin') ?>">Login ADM</a>
            </div>
            <div class="footer-contact">
                <h3>Contato</h3>

                <p><i class="fa-solid fa-phone"></i> (19) 99890-8934</p>

                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=visio.suporte@gmail.com" target="_blank"
                    rel="noopener" style="text-decoration:none;color:inherit;">
                    <i class="fa-solid fa-envelope"></i> visio.suporte@gmail.com
                </a>
            
                <p>
                <i class="fa-solid fa-location-dot"></i>
                <a href="https://www.google.com.br/maps/place/Tamba%C3%BA,+SP,+13710-000/@-21.706476,-47.284224,3245m/data=!3m1!1e3!4m6!3m5!1s0x94b7ec18c2ffbc5d:0x93064a179e2034ad!8m2!3d-21.7073335!4d-47.2749788!16s%2Fg%2F11bxfwx02s?entry=ttu&g_ep=EgoyMDI2MDcyNi4wIKXMDSoASAFQAw%3D%3D" style="text-decoration: none;"> Tambaú - SP</a>
                </p>

                <div class="social">
                    <a href="https://www.instagram.com/_plataformavisio/" target="_blank" rel="noopener">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://github.com/2IDS-A-TAMB-2026/VISIO" target="_blank" rel="noopener">
                        <i class="fa-brands fa-github"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        © 2026 VISIO • Todos os direitos reservados
    </div>
</footer>
<script src="<?= base_url('assets/js/theme.js') ?>"></script>
<script src="<?= base_url('assets/js/validacaocadastro.js') ?>"></script>
<script src="<?= base_url('assets/js/validacaoLogin.js') ?>"></script>
<script src="<?= base_url('assets/js/validacaoAdm.js') ?>"></script>

</body>

</html>