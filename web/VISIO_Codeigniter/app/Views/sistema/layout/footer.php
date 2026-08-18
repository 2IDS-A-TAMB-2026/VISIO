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


    /*STYLE PROS NGC DE INSTA E GIT HUB*/
    .social {
    display: flex;
    gap: 18px;
    }
    .social a {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        background: #0d0920;
        border: 1px solid #19152f;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        transition: 0.3s;
    }
    .social a i {
        font-size: 25px;
    }
    .social a:hover {
        transform: translateY(-3px);
        background: #15102d;
    }

    /*STYLE PRO NGC DAPLATAFORMA */
    .footer-links {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    }
    .footer-links h3 {
        margin: 0 0 25px 0;
        font-size: 18px;
        font-weight: 600;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .footer-links a {
        color: #ffffff;
        text-decoration: none;
        font-size: 17px;
        font-weight: 300;
        margin-bottom: 12px;
        transition: 0.3s;
    }
    .footer-links a:hover {
        color: #aaa;
        transform: translateX(3px);
    }

    /*PRO NGC DO CONTATO AGORA */
    .footer-contact {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    }
    .footer-contact h3 {
        margin: 0 0 28px 0;
        font-size: 17px;
        font-weight: 600;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .footer-contact p,
    .footer-contact > a {
        margin: 0 0 20px 0;
        font-size: 15px;
        font-weight: 400;
        color: #fff;
        text-decoration: none;
    }
    .footer-contact p {
        display: flex;
        align-items: center;
    }
    .footer-contact i {
        margin-right: 10px;
        font-size: 16px;
    }
    .footer-contact p a {
        color: #fff;
        text-decoration: none;
    }


</style>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-left">
            <img class="logo" src="<?= base_url('assets/images/logos/Icone/IconeDark2.png') ?>"
                data-light="<?= base_url('assets/images/logos/Icone/IconeDark2.png') ?>"
                data-dark="<?= base_url('assets/images/logos/Icone/IconeDark2.png') ?>" 
                alt="Logo"
                style="width: 170px; height: auto;">
            <div class="linha-vertical"></div>
            <div class="brand-text">
                <h2>VISIO</h2>
                <p class="Plataforma">Plataforma educacional focada em tecnologia, sensores e aprendizado interativo.</p>
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
    <div class="footer-bottom" style="font-size: 15px">
        © 2026 VISIO • Todos os direitos reservados
    </div>
</footer>
<script src="<?= base_url('assets/js/theme.js') ?>"></script>
<script src="<?= base_url('assets/js/validacaocadastro.js') ?>"></script>
<script src="<?= base_url('assets/js/validacaoLogin.js') ?>"></script>
<script src="<?= base_url('assets/js/validacaoAdm.js') ?>"></script>

</body>

</html>