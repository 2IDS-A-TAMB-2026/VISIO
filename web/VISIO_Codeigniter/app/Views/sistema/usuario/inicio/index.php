<?= view('sistema/layout/header') ?>

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

        <a href="<?= base_url('/identificador') ?>" class="animated-button1">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            Identificar
        </a>
    </section>

    <div class="container-geral">
        <div class="carrossel">
            <div class="carrossel-interno">
                <div class="item">
                    <img class="theme-img" src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
                        data-light="<?= base_url('assets/images/logos/Logo/LogoLight.png') ?>"
                        data-dark="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>" alt="Logo">
                </div>

                <div class="item">
                    <img class="theme-img" src="<?= base_url('assets/images/Carrossel/identifique2.png') ?>"
                        data-light="<?= base_url('assets/images/Carrossel/identifique2.png') ?>"
                        data-dark="<?= base_url('assets/images/Carrossel/identifique.png') ?>" alt="Identifique">
                </div>

                <div class="item">
                    <img class="theme-img" src="<?= base_url('assets/images/Carrossel/aprender2.png') ?>"
                        data-light="<?= base_url('assets/images/Carrossel/aprender2.png') ?>"
                        data-dark="<?= base_url('assets/images/Carrossel/aprender.png') ?>" alt="Aprender">
                </div>

                <div class="item">
                    <img class="theme-img" src="<?= base_url('assets/images/Carrossel/educacao2.png') ?>"
                        data-light="<?= base_url('assets/images/Carrossel/educacao2.png') ?>"
                        data-dark="<?= base_url('assets/images/Carrossel/educacao.png') ?>" alt="Educação">
                </div>

                <div class="item">
                    <img class="theme-img" src="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
                        data-light="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>"
                        data-dark="<?= base_url('assets/images/logos/Logo/LogoDark.png') ?>" alt="Logo">
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

    <video autoplay muted loop playsinline class="video-bg theme-video"
        src="<?= base_url('assets/images/Videos/video_black.mp4') ?>"
        data-light="<?= base_url('assets/images/Videos/video_white.mp4') ?>"
        data-dark="<?= base_url('assets/images/Videos/video_black.mp4') ?>">
    </video>

    <div class="container">
        <h2 class="title" style="color: #FFF">Funcionalidades do Sistema</h2>
        <div class="grid">

            <section class="emp-section">
                <div class="emp-grid-top">

                    <div class="emp-card">
                        <div class="emp-icon">
                            📷
                        </div>
                        <h3>Identificação por Visão Computacional</h3>
                        <p>Reconhecimento automático de sensores por meio de captura de imagem e
                            processamento com
                            modelos de
                            Inteligência Artificial treinados para classificação de dispositivos.</p>
                    </div>
                    <div class="emp-card">
                        <div class="emp-icon">
                            🏢
                        </div>
                        <h3>Gestão de Sensores IoT</h3>
                        <p>Registro, consulta e acompanhamento do status dos sensores em ambiente
                            digital
                            centralizado, permitindo
                            organização e rastreabilidade.</p>
                    </div>

                    <div class="emp-card">
                        <div class="emp-icon">
                            📖
                        </div>
                        <h3>Apoio Educacional</h3>
                        <p>Ferramenta didática voltada ao aprendizado prático de Internet das
                            Coisas, automação e
                            identificação de
                            componentes eletrônicos.</p>
                    </div>

                    <div class="emp-card">
                        <div class="emp-icon">
                            ✏️
                        </div>
                        <h3>Aplicação</h3>
                        <p>Organização e controle de sensores utilizados em atividades práticas,
                            experimentos e
                            projetos acadêmicos.</p>
                    </div>

                    <div class="emp-card">
                        <div class="emp-icon">
                            🔐
                        </div>
                        <h3>Autenticação e Segurança</h3>
                        <p>Implementação de identificação digital única por sensor e proteção das
                            informações
                            registradas no
                            sistema.</p>
                    </div>

                    <div class="emp-card">
                        <div class="emp-icon">
                            📱
                        </div>
                        <h3>Web e Mobile</h3>
                        <p>Acesso via navegador e aplicativo mobile multiplataforma.</p>
                    </div>
                </div>
        </div>
</section>
</div>
</div>
</section>
<hr>

<section class="visio-hero">
    
    <!-- Fundo com elementos tecnológicos -->
    <div class="hero-bg">
        <div class="grid-lines"></div>
        <div class="glow glow-1"></div>
        <div class="glow glow-2"></div>
    </div>

    <div class="hero-content">
        
        <!-- Título principal -->
        <div class="hero-title">
            <h1>Conheça a Plataforma <span class="highlight">VISIO</span></h1>
            <p class="subtitle">Tecnologia inteligente para identificação de sensores IoT</p>
        </div>

        <!-- Container do vídeo -->
        <div class="video-wrapper">
            <div class="video-card">
                <div class="video-glow"></div>
                <video autoplay muted loop playsinline class="video">
                    <source src="<?= base_url('assets/images/Videos/VISIO.mp4') ?>" type="video/mp4">
                </video>
                <div class="video-overlay">
                    <span class="badge"><i class="fa-solid fa-play"></i> Vídeo demonstrativo</span>
                </div>
            </div>
        </div>

        <!-- Card de descrição -->
        <div class="description-card">
            <div class="card-content">
                <h3 style="text-align: center">Inteligência Artificial aplicada</h3>
                <p style="text-align: center">
                    A plataforma VISIO utiliza visão computacional avançada e tecnologias de IA 
                    para identificação inteligente de sensores IoT, tornando a automação industrial 
                    mais eficiente e precisos.
                </p>
            </div>
            <ul class="features-list">
    <li><i class="fa-solid fa-check"></i> Detecção em tempo real</li>
    <li><i class="fa-solid fa-check"></i> Integração com sistemas IoT</li>
    <li><i class="fa-solid fa-check"></i> Análise de dados inteligente</li>
</ul>
        </div>

    </div>
</section>

<style>
    .features-list{
    text-align: center;
    list-style: none;
    padding: 0;
}
    /* === PALETA DE CORES === */
    :root {
        /* Superfícies */
        --color-surface-dark: #17182c;
        --color-surface-card: #131b4f;
        --color-surface-btn: #2a3472;
        --color-surface-btn-hover: #515b99;

        /* Azul */
        --color-primary: #1e6be7;
        --color-primary-hover: #47cdfd;
        --color-primary-dark: #1557c0;
        --color-primary-accent: #2662d9;
        --color-accent-adm: #3a86ff;

        /* Botão */
        --color-btn-grad-a: #0b1b3d;
        --color-btn-grad-b: #08142b;
        --color-btn-grad-text: #d4e0f7;
        --color-btn-grad-before: #8592ad;
    }

    /* Fundo principal */
    .visio-hero {
        position: relative;
        width: 100%;
        min-height: auto;
        background: linear-gradient(135deg, var(--color-surface-dark) 0%, var(--color-surface-card) 100%);
        padding: 80px 20px 100px;
        overflow: hidden;
    }

    /* Grid de linhas tecnológicas */
    .visio-hero .hero-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
    }

    .visio-hero .grid-lines {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            linear-gradient(rgba(30, 107, 231, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(30, 107, 231, 0.05) 1px, transparent 1px);
        background-size: 60px 60px;
    }

    /* Brilhos suaves */
    .visio-hero .glow {
        position: absolute;
        border-radius: 50%;
        filter: blur(120px);
        opacity: 0.3;
    }

    .visio-hero .glow-1 {
        width: 500px;
        height: 500px;
        background: var(--color-primary);
        top: -200px;
        right: -100px;
    }

    .visio-hero .glow-2 {
        width: 400px;
        height: 400px;
        background: var(--color-primary-hover);
        bottom: -100px;
        left: -100px;
    }

    /* Conteúdo principal */
    .visio-hero .hero-content {
        position: relative;
        z-index: 1;
        max-width: 1100px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 40px;
    }

    /* Título */
    .visio-hero .hero-title {
        text-align: center;
    }

    .visio-hero .hero-title h1 {
        font-size: 2.5rem;
       
        margin: 0 0 12px 0;
        line-height: 1.2;
    }

    .visio-hero .hero-title .highlight {
        background: linear-gradient(135deg, var(--color-primary), var(--color-primary-hover));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .visio-hero .hero-title .subtitle {
        font-size: 1.25rem;
        margin: 0;
    }

    /* Vídeo maior */
    .visio-hero .video-wrapper {
        width: 100%;
        max-width: 1000px;
    }

    .visio-hero .video-card {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 
            0 0 0 1px rgba(0,0,0,0.1),
            0 20px 50px rgba(0,0,0,0.15),
            0 0 100px rgba(30, 107, 231, 0.15);
        border: 1px solid var(--color-primary-accent);
    }

    .visio-hero .video-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60%;
        height: 60%;
        background: radial-gradient(ellipse, rgba(30, 107, 231, 0.3), transparent 70%);
        pointer-events: none;
    }

    .visio-hero .video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .visio-hero .video-overlay {
        position: absolute;
        top: 20px;
        left: 20px;
    }

    .visio-hero .badge {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.875rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(0,0,0,0.1);
    }

    /* Card de descrição */
    .visio-hero .description-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 30px;
        max-width: 800px;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
  

    .visio-hero .card-icon i {
        color: #ffffff;
    }

    .visio-hero .card-content h3 {
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 10px 0;
    }

    .visio-hero .card-content p {
        font-size: 1rem;
        color: #475569;
        line-height: 1.7;
        margin: 0;
    }

    .visio-hero .features-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .visio-hero .features-list li {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        font-size: 0.9375rem;
    }

    .visio-hero .features-list li i {
        color: var(--color-primary);
    }

    /* Botões de ação */
    .visio-hero .cta-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px;
    }

    .visio-hero .btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .visio-hero .btn-primary {
        background: linear-gradient(135deg, var(--color-primary), var(--color-primary-accent));
        color: #ffffff;
        border: none;
        box-shadow: 0 4px 15px rgba(30, 107, 231, 0.3);
    }

    .visio-hero .btn-primary:hover {
        background: var(--color-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 107, 231, 0.4);
    }

    .visio-hero .btn-secondary {
        background: #f1f5f9;
        color: #1e293b;
        border: 1px solid #e2e8f0;
    }

    .visio-hero .btn-secondary:hover {
        background: #e2e8f0;
    }

    .visio-hero .btn-outline {
        background: transparent;
        color: var(--color-primary);
        border: 2px solid var(--color-primary);
    }

    .visio-hero .btn-outline:hover {
        background: var(--color-primary);
        color: #ffffff;
    }

    /* Responsivo */
    @media (max-width: 768px) {
        .visio-hero .hero-title h1 {
            font-size: 2rem;
        }
        
        .visio-hero .hero-title .subtitle {
            font-size: 1rem;
        }
        
        .visio-hero .cta-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .visio-hero .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
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