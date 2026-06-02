<?= view('sistema/layout/header_logado') ?>

<p id="index-login-msg" class="contato-feedback index-banner" hidden></p>
<main class="hero">
    <section class="intro">
        <h1>Identificação Inteligente de Sensores IoT</h1>
        <p>
            Sistema acadêmico desenvolvido como Trabalho de Conclusão de Curso que utiliza
            visão computacional e Inteligência Artificial para identificar automaticamente
            sensores IoT físicos, promovendo organização, rastreabilidade e apoio ao ensino
            de Internet das Coisas e automação.
        </p>

        <a href="<?= base_url('/identificador') ?>" class="animated-button azul">
            Identificar
        </a>
    </section>

    <div class="container-geral">
        <div class="carrossel">
            <div class="carrossel-interno">
                <div class="item">
                    <img src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>" alt="Logo">
                </div>
                <div class="item">
                    <img src="<?= base_url('assets/images/carrossel/identifique.png') ?>" alt="Identifique">
                </div>
                <div class="item">
                    <img src="<?= base_url('assets/images/carrossel/aprender.png') ?>" alt="Aprender">
                </div>
                <div class="item">
                    <img src="<?= base_url('assets/images/carrossel/educacao.png') ?>" alt="Educação">
                </div>
                <div class="item">
                    <img src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>" alt="Logo">
                </div>
            </div>
        </div>
        <div class="indicadores">
            <span class="bolinha b1"></span>
            <span class="bolinha b2"></span>
            <span class="bolinha b3"></span>
            <span class="bolinha b4"></span>
        </div>
    </div>

</main>

<section class="services">
    <video autoplay muted loop playsinline class="video-bg">
        <source src="<?= base_url('assets/images/Videos/fun_video.mp4') ?>" type="video/mp4">
    </video>

    <div class="container">
        <h2 class="title">Funcionalidades do Sistema</h2>
        <div class="grid">

            <section class="emp-section">
                <div class="emp-grid-top">

                    <div class="emp-card">
                        <div class="emp-icon">
                            📷
                        </div>
                        <h3 style="color: #000">Identificação por Visão Computacional</h3>
                        <p style="color: #000">Reconhecimento automático de sensores por meio de captura de imagem e
                            processamento com
                            modelos de
                            Inteligência Artificial treinados para classificação de dispositivos.</p>
                    </div>
                    <div class="emp-card">
                        <div class="emp-icon">
                            🏢
                        </div>
                        <h3 style="color: #000">Gestão de Sensores IoT</h3>
                        <p style="color: #000">Registro, consulta e acompanhamento do status dos sensores em ambiente
                            digital
                            centralizado, permitindo
                            organização e rastreabilidade.</p>
                    </div>

                    <div class="emp-card">
                        <div class="emp-icon">
                            📖
                        </div>
                        <h3 style="color: #000">Apoio Educacional</h3>
                        <p style="color: #000">Ferramenta didática voltada ao aprendizado prático de Internet das
                            Coisas, automação e
                            identificação de
                            componentes eletrônicos.</p>
                    </div>

                    <div class="emp-card">
                        <div class="emp-icon">
                            ✏️
                        </div>
                        <h3 style="color: #000">Aplicação</h3>
                        <p style="color: #000">Organização e controle de sensores utilizados em atividades práticas,
                            experimentos e
                            projetos acadêmicos.</p>
                    </div>

                    <div class="emp-card">
                        <div class="emp-icon">
                            🔐
                        </div>
                        <h3 style="color: #000">Autenticação e Segurança</h3>
                        <p style="color: #000">Implementação de identificação digital única por sensor e proteção das
                            informações
                            registradas no
                            sistema.</p>
                    </div>

                    <div class="emp-card">
                        <div class="emp-icon">
                            📱
                        </div>
                        <h3 style="color: #000">Web e Mobile</h3>
                        <p style="color: #000">Acesso via navegador e aplicativo mobile multiplataforma.</p>
                    </div>
                </div>
        </div>
</section>
</div>
</div>
</section>
<hr>

<section>
    <br>
    <br>
    <h2 class="title">Vídeo institucional</h2>
    <div class="video-container">

        <div class="fundo-branco"></div>

        <video autoplay muted loop playsinline class="video">
            <source src="<?= base_url('assets/images/Videos/VISIO.mp4') ?>" type="video/mp4">
        </video>

    </div>
</section>

<hr>

<section class="portfolio">
    <div class="container">
        <h2 class="title">Aplicações do Sistema</h2>
        <div class="grid">
            <div class="card1">
                <img src="<?= base_url('assets/images/Aplicacoes/identificacao.automatica.png') ?>"
                    alt="Identificação de Sensor">
                <h3 style="text-align: center;">Identificação Automatica de Sensores</h3>
            </div>

            <div class="card1">
                <img src="<?= base_url('assets/images/Aplicacoes/aplicacao.educacional.png') ?>"
                    alt="Aplicação Educacional">
                <h3 style="text-align: center;">Aplicação Educacional</h3>
            </div>

            <div class="card1">
                <img src="<?= base_url('assets/images/Aplicacoes/gestaoeorganizacao.png') ?>"
                    alt="Gestão de Sensores IoT">
                <h3 style="text-align: center;">Gestão e Organização</h3>
            </div>

            <div class="card1">
                <img src="<?= base_url('assets/images/Aplicacoes/interfaceegerenciamento.png') ?>"
                    alt="Aplicação Industrial">
                <h3 style="text-align: center;">Interface de Gerenciamento</h3>
            </div>

            <div class="card1">
                <img src="<?= base_url('assets/images/Aplicacoes/registroerastreamento.png') ?>"
                    alt="Plataforma Web e Mobile">
                <h3 style="text-align: center;">Registro e Rastreamento</h3>
            </div>

            <div class="card1">
                <img src="<?= base_url('assets/images/Aplicacoes/segurancaeautenticacao.png') ?>"
                    alt="Segurança e Autenticação">
                <h3 style="text-align: center;">Segurança e Autenticação</h3>
            </div>
        </div>
    </div>
</section>

<?= view('sistema/layout/footer') ?>