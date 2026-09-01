<?= view('sistema/layout/header') ?>

<p id="index-login-msg" class="contato-feedback index-banner" hidden></p>

<!-- ============================================================
     SEÇÃO HERO / INTRODUÇÃO
     ============================================================ -->
<main class="hero hero-section-bg"   style="background: 
    radial-gradient(circle at top right, #0055ff6f 0%, transparent 50%),
    radial-gradient(circle at bottom left, #0055ff6f 0%, transparent 50%)">
    <section class="intro" >
        <h1>Identificação Inteligente de Sensores IoT</h1>
        <p >
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

    <div class="container-geral" >
        <div class="carrossel">
            <div class="carrossel-interno">
                <div class="item">
                    <img class="theme-img" src="<?= base_url('assets/images/Carrossel/logoP.png') ?>"
                        data-light="<?= base_url('assets/images/Carrossel/logoB.png') ?>"
                        data-dark="<?= base_url('assets/images/Carrossel/logoP.png') ?>" alt="logo">
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
                    <img class="theme-img" src="<?= base_url('assets/images/Carrossel/logoP.png') ?>"
                        data-light="<?= base_url('assets/images/Carrossel/logoB.png') ?>"
                        data-dark="<?= base_url('assets/images/Carrossel/logoP.png') ?>" alt="logo">
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
<hr>

<!-- ============================================================
     SEÇÃO DE FUNCIONALIDADES (NOVO LAYOUT FLUXO DE RECURSOS)
     ============================================================ -->
<section class="services services-section-bg"   style="background: 
    radial-gradient(circle at top left, #0055ff6f 10%, transparent 50%),
    radial-gradient(circle at bottom right, #0055ff6f 10%, transparent 50%)">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="title services-title-color">Ecossistema de Funcionalidades</h2>
            <p class="subtitle-sec">Arquitetura modular desenhada para a perfeita gestão e identificação de componentes</p>
        </div>
        
        <div class="flow-layout-grid">
            <!-- Item 1 -->
            <div class="flow-step-card">
                <div class="flow-step-header">
                    <span class="flow-num">01</span>
                    <div class="flow-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                    </div>
                </div>
                <div class="flow-body">
                    <h3>Identificação por IA</h3>
                    <p>Reconhecimento automático de sensores por captura de imagem e processamento com modelos avançados de Inteligência Artificial.</p>
                </div>
                <div class="flow-badge">Visão Computacional</div>
            </div>

            <!-- Item 2 -->
            <div class="flow-step-card">
                <div class="flow-step-header">
                    <span class="flow-num">02</span>
                    <div class="flow-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                            <line x1="6" y1="6" x2="6.01" y2="6"></line>
                            <line x1="6" y1="18" x2="6.01" y2="18"></line>
                        </svg>
                    </div>
                </div>
                <div class="flow-body">
                    <h3>Gestão de Sensores IoT</h3>
                    <p>Registro, consulta e acompanhamento centralizado do status dos sensores, promovendo organização e rastreabilidade.</p>
                </div>
                <div class="flow-badge">Controle Centralizado</div>
            </div>

            <!-- Item 3 -->
            <div class="flow-step-card">
                <div class="flow-step-header">
                    <span class="flow-num">03</span>
                    <div class="flow-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flow-body">
                    <h3>Apoio Educacional</h3>
                    <p>Ferramenta didática para o aprendizado prático de Internet das Coisas, automação e componentes eletrônicos.</p>
                </div>
                <div class="flow-badge">Ensino Interativo</div>
            </div>

            <!-- Item 4 -->
            <div class="flow-step-card">
                <div class="flow-step-header">
                    <span class="flow-num">04</span>
                    <div class="flow-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                    </div>
                </div>
                <div class="flow-body">
                    <h3>Aplicação Prática</h3>
                    <p>Organização e controle de componentes em atividades de laboratório, testes e desenvolvimento de projetos acadêmicos.</p>
                </div>
                <div class="flow-badge">Projetos & Aulas</div>
            </div>

            <!-- Item 5 -->
            <div class="flow-step-card">
                <div class="flow-step-header">
                    <span class="flow-num">05</span>
                    <div class="flow-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                </div>
                <div class="flow-body">
                    <h3>Autenticação e Segurança</h3>
                    <p>Identificação digital única por sensor e proteção de acesso às informações integradas ao sistema.</p>
                </div>
                <div class="flow-badge">Criptografia & ID</div>
            </div>

            <!-- Item 6 -->
            <div class="flow-step-card">
                <div class="flow-step-header">
                    <span class="flow-num">06</span>
                    <div class="flow-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                            <line x1="12" y1="18" x2="12.01" y2="18"></line>
                        </svg>
                    </div>
                </div>
                <div class="flow-body">
                    <h3>Web e Mobile</h3>
                    <p>Acesso multiplataforma simplificado através de navegadores web e aplicativo mobile dedicado.</p>
                </div>
                <div class="flow-badge">Multiplataforma</div>
            </div>
        </div>
    </div>
</section>

<hr>

<!-- ============================================================
     SEÇÃO PLATAFORMA VISIO
     ============================================================ -->
<section class="visio-hero">
    <div class="hero-bg">
        <div class="grid-lines"></div>
        <div class="glow glow-1"></div>
        <div class="glow glow-2"></div>
    </div>

    <div class="hero-content">
        <div class="hero-title">
            <h1>Conheça a Plataforma VISIO</h1>
            <p class="subtitle">Tecnologia inteligente para identificação de sensores IoT</p>
        </div>

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

        <div class="description-card">
            <div class="card-content">
                <h3 style="text-align: center">Inteligência Artificial aplicada</h3>
                <p style="text-align: center">
                    A plataforma VISIO utiliza visão computacional avançada e tecnologias de IA 
                    para identificação inteligente de sensores IoT, tornando a automação industrial 
                    mais eficiente e precisa.
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

<hr>

<!-- ============================================================
     SEÇÃO DE APLICAÇÕES DO SISTEMA (3D FLIP CARDS)
     ============================================================ -->
<section class="portfolio portfolio-section-bg">
    <div class="container">
        <h2 class="title">Aplicações do Sistema</h2>
        
        <div class="tech-grid">
            
            <!-- Card 1 -->
            <div class="tech-card">
                <div class="tech-card-inner">
                    <div class="card-front">
                        <div class="tech-img-wrapper">
                            <img src="<?= base_url('assets/images/Aplicacoes/identificacao.automatica.png') ?>" alt="Identificação de Sensor">
                            <div class="tech-img-overlay"></div>
                        </div>
                        <div class="tech-card-content">
                            <h3>Identificação Automática de Sensores</h3>
                            <div class="tech-card-line"></div>
                        </div>
                    </div>
                    <div class="card-back">
                        <div class="hud-corners"><span></span><span></span><span></span><span></span></div>
                        <div class="back-content">
                            <span class="back-tag"><i class="fa-solid fa-microchip"></i> TECNOLOGIA IA</span>
                            <h4>Identificação por Visão</h4>
                            <p>O algoritmo lê a morfologia do sensor, analisa componentes visíveis e classifica o modelo em tempo real com alta precisão.</p>
                            <div class="back-footer">
                                <span><i class="fa-solid fa-bolt"></i> Processamento Instantâneo</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="tech-card">
                <div class="tech-card-inner">
                    <div class="card-front">
                        <div class="tech-img-wrapper">
                            <img src="<?= base_url('assets/images/Aplicacoes/aplicacao.educacional.png') ?>" alt="Aplicação Educacional">
                            <div class="tech-img-overlay"></div>
                        </div>
                        <div class="tech-card-content">
                            <h3>Aplicação Educacional</h3>
                            <div class="tech-card-line"></div>
                        </div>
                    </div>
                    <div class="card-back">
                        <div class="hud-corners"><span></span><span></span><span></span><span></span></div>
                        <div class="back-content">
                            <span class="back-tag"><i class="fa-solid fa-graduation-cap"></i> ENSINO PRÁTICO</span>
                            <h4>Apoio ao Aprendizado</h4>
                            <p>Ideal para laboratórios acadêmicos e alunos de robótica, facilitando o reconhecimento de pinagens e especificações elétricas.</p>
                            <div class="back-footer">
                                <span><i class="fa-solid fa-book-open"></i> Foco na Prática</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="tech-card">
                <div class="tech-card-inner">
                    <div class="card-front">
                        <div class="tech-img-wrapper">
                            <img src="<?= base_url('assets/images/Aplicacoes/gestaoeorganizacao.png') ?>" alt="Gestão de Sensores IoT">
                            <div class="tech-img-overlay"></div>
                        </div>
                        <div class="tech-card-content">
                            <h3>Gestão e Organização</h3>
                            <div class="tech-card-line"></div>
                        </div>
                    </div>
                    <div class="card-back">
                        <div class="hud-corners"><span></span><span></span><span></span><span></span></div>
                        <div class="back-content">
                            <span class="back-tag"><i class="fa-solid fa-boxes-stacked"></i> ORGANIZAÇÃO</span>
                            <h4>Controle de Inventário</h4>
                            <p>Mantém a contagem precisa da quantidade de sensores disponíveis, estado de conservação e localização física nas bancadas.</p>
                            <div class="back-footer">
                                <span><i class="fa-solid fa-database"></i> Estoque Mapeado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="tech-card">
                <div class="tech-card-inner">
                    <div class="card-front">
                        <div class="tech-img-wrapper">
                            <img src="<?= base_url('assets/images/Aplicacoes/interfaceegerenciamento.png') ?>" alt="Aplicação Industrial">
                            <div class="tech-img-overlay"></div>
                        </div>
                        <div class="tech-card-content">
                            <h3>Interface de Gerenciamento</h3>
                            <div class="tech-card-line"></div>
                        </div>
                    </div>
                    <div class="card-back">
                        <div class="hud-corners"><span></span><span></span><span></span><span></span></div>
                        <div class="back-content">
                            <span class="back-tag"><i class="fa-solid fa-chart-line"></i> DASHBOARD</span>
                            <h4>Painel Intuitivo</h4>
                            <p>Acompanhe métricas, gráficos de identificações recentes e gerencie acessos de usuários em um ambiente dinâmico.</p>
                            <div class="back-footer">
                                <span><i class="fa-solid fa-gauge-high"></i> Controle Total</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="tech-card">
                <div class="tech-card-inner">
                    <div class="card-front">
                        <div class="tech-img-wrapper">
                            <img src="<?= base_url('assets/images/Aplicacoes/registroerastreamento.png') ?>" alt="Plataforma Web e Mobile">
                            <div class="tech-img-overlay"></div>
                        </div>
                        <div class="tech-card-content">
                            <h3>Registro e Rastreamento</h3>
                            <div class="tech-card-line"></div>
                        </div>
                    </div>
                    <div class="card-back">
                        <div class="hud-corners"><span></span><span></span><span></span><span></span></div>
                        <div class="back-content">
                            <span class="back-tag"><i class="fa-solid fa-clock-rotate-left"></i> RASTREABILIDADE</span>
                            <h4>Histórico Digital</h4>
                            <p>Gera um diário de utilização por dispositivo, rastreando quem utilizou cada sensor e em qual projeto foi aplicado.</p>
                            <div class="back-footer">
                                <span><i class="fa-solid fa-timeline"></i> Auditável e Seguro</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="tech-card">
                <div class="tech-card-inner">
                    <div class="card-front">
                        <div class="tech-img-wrapper">
                            <img src="<?= base_url('assets/images/Aplicacoes/segurancaeautenticacao.png') ?>" alt="Segurança e Autenticação">
                            <div class="tech-img-overlay"></div>
                        </div>
                        <div class="tech-card-content">
                            <h3>Segurança e Autenticação</h3>
                            <div class="tech-card-line"></div>
                        </div>
                    </div>
                    <div class="card-back">
                        <div class="hud-corners"><span></span><span></span><span></span><span></span></div>
                        <div class="back-content">
                            <span class="back-tag"><i class="fa-solid fa-shield-halved"></i> PROTEÇÃO</span>
                            <h4>Autenticação Digital</h4>
                            <p>Verificação por ID único criptografado, garantindo que apenas módulos autorizados enviem dados à plataforma.</p>
                            <div class="back-footer">
                                <span><i class="fa-solid fa-lock"></i> Criptografia Avançada</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     ESTILOS CSS GERAIS E ADAPTATIVOS
     ============================================================ -->
<style>
    :root {
        /* TEMA ESCURO (PADRÃO) */
        --color-surface-dark: #0a0e1a;
        --color-surface-card: #0d152d;
        --color-primary: #00d8ff;
        --color-primary-hover: #00f0ff;

        --neon-card-bg: rgba(13, 21, 45, 0.7);
        --neon-card-border: rgba(0, 216, 255, 0.15);
        --neon-text-main: #ffffff; /* BRANCO NO ESCURO */
        --neon-text-muted: #94a3b8;

        /* Variáveis da Seção */
        --services-bg-base: #0a0e1a;
        --services-grad-1: rgba(0, 216, 255, 0.12);
        --services-grad-2: rgba(0, 119, 255, 0.12);
        --flow-num-bg: rgba(0, 216, 255, 0.08);
        --flow-num-border: rgba(0, 216, 255, 0.2);
        --flow-icon-bg: rgba(0, 216, 255, 0.06);
        --flow-card-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        --flow-card-shadow-hover: 0 12px 30px -10px rgba(0, 216, 255, 0.2);
    }

    /* TEMA CLARO */
    body.light-theme, 
    body.theme-light, 
    body.light,
    html.light-theme body,
    html[data-theme="light"] body, 
    html[data-bs-theme="light"] body,
    .light-theme,
    [data-theme="light"] {
        --color-surface-dark: #f8fafc;
        --color-surface-card: #ffffff;
        --color-primary: #0066ff;
        --color-primary-hover: #0044cc;

        --neon-card-bg: #ffffff;
        --neon-card-border: rgba(0, 102, 255, 0.15);
        --neon-text-main: #000000; /* PRETO NO CLARO */
        --neon-text-muted: #475569;

        --services-bg-base: #f8fafc;
        --services-grad-1: rgba(0, 102, 255, 0.05);
        --services-grad-2: rgba(0, 102, 255, 0.03);
        --flow-num-bg: rgba(0, 102, 255, 0.08);
        --flow-num-border: rgba(0, 102, 255, 0.18);
        --flow-icon-bg: rgba(0, 102, 255, 0.06);
        --flow-card-shadow: 0 4px 20px rgba(0, 102, 255, 0.06);
        --flow-card-shadow-hover: 0 12px 30px -5px rgba(0, 102, 255, 0.15);
    }

    body, html {
        overflow-x: hidden;
    }

    .text-center { text-align: center; }
    
    /* SUBTÍTULO COM ALTERNÂNCIA DE COR (PRETO NO CLARO / BRANCO NO ESCURO) */
    .subtitle-sec {
        font-size: 1.05rem;
        color: var(--neon-text-main) !important;
        margin-top: 8px;
        margin-bottom: 30px;
        transition: color 0.3s ease;
    }

    /* LAYOUT FLUXO CONECTADO */
    .services {
        padding: 90px 20px;
        position: relative;
    }

    .flow-layout-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-top: 40px;
    }

    .flow-step-card {
        background: var(--neon-card-bg) !important;
        border: 1px solid var(--neon-card-border);
        border-radius: 16px;
        padding: 32px 26px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        backdrop-filter: blur(16px);
        box-shadow: var(--flow-card-shadow);
        
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1), 
                    transform 1.2s cubic-bezier(0.16, 1, 0.3, 1),
                    border-color 0.4s ease,
                    box-shadow 0.4s ease,
                    background-color 0.3s ease;
    }

    .flow-step-card.active-reveal {
        opacity: 1;
        transform: translateY(0);
    }

    .flow-step-card:hover {
        border-color: var(--color-primary);
        box-shadow: var(--flow-card-shadow-hover);
    }

    .flow-step-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .flow-num {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--color-primary);
        font-family: monospace;
        opacity: 0.9;
        background: var(--flow-num-bg);
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid var(--flow-num-border);
    }

    .flow-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--flow-icon-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-primary);
    }

    .flow-icon svg {
        width: 22px;
        height: 22px;
    }

    .flow-body h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--neon-text-main);
        margin: 0 0 10px 0;
    }

    .flow-body p {
        font-size: 0.9rem;
        color: var(--neon-text-muted);
        line-height: 1.6;
        margin: 0 0 24px 0;
    }

    .flow-badge {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--color-primary);
        align-self: flex-start;
    }

    /* RESPONSIVIDADE */
    @media (max-width: 992px) {
        .flow-layout-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .flow-layout-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ESTILOS DA SEÇÃO VISIO */
    .features-list { text-align: center; list-style: none; padding: 0; }
    .visio-hero { position: relative; width: 100%; min-height: auto; background: linear-gradient(135deg, var(--color-surface-dark) 0%, var(--color-surface-card) 100%); padding: 80px 20px 100px; overflow: hidden; transition: background 0.4s ease; }
    .visio-hero .hero-bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; }
    .visio-hero .grid-lines { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: linear-gradient(rgba(0, 216, 255, 0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(0, 216, 255, 0.08) 1px, transparent 1px); background-size: 60px 60px; }
    .visio-hero .glow { position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.4; }
    .visio-hero .glow-1 { width: 500px; height: 500px; background: #00d8ff; top: -200px; right: -100px; }
    .visio-hero .glow-2 { width: 400px; height: 400px; background: #0077ff; bottom: -100px; left: -100px; }
    .visio-hero .hero-content { position: relative; z-index: 1; max-width: 1100px; margin: 0 auto; display: flex; flex-direction: column; align-items: center; gap: 40px; }
    .visio-hero .hero-title { text-align: center; }
    .visio-hero .hero-title h1 { font-size: 2.5rem; margin: 0 0 12px 0; line-height: 1.2; }
    .visio-hero .hero-title .subtitle { font-size: 1.25rem; margin: 0; }
    .visio-hero .video-wrapper { width: 100%; max-width: 1000px; }
    .visio-hero .video-card { position: relative; width: 100%; aspect-ratio: 16 / 9; border-radius: 16px; overflow: hidden; box-shadow: 0 0 15px rgba(0, 216, 255, 0.4), 0 20px 50px rgba(0,0,0,0.2); border: 2px solid #00d8ff; }
    .visio-hero .video-glow { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 60%; height: 60%; background: radial-gradient(ellipse, rgba(0, 216, 255, 0.35), transparent 70%); pointer-events: none; }
    .visio-hero .video { width: 100%; height: 100%; object-fit: cover; }
    .visio-hero .video-overlay { position: absolute; top: 20px; left: 20px; }
    .visio-hero .badge { background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); padding: 8px 16px; border-radius: 20px; font-size: 0.875rem; color: #0f172a; display: flex; align-items: center; gap: 8px; border: 1px solid rgba(0, 216, 255, 0.5); }
    .visio-hero .description-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 30px; max-width: 800px; width: 100%; display: flex; flex-direction: column; gap: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08), 0 0 15px rgba(0, 216, 255, 0.15); }
    .visio-hero .card-content h3 { font-weight: 700; color: #0f172a; margin: 0 0 10px 0; }
    .visio-hero .card-content p { font-size: 1rem; color: #334155; line-height: 1.7; margin: 0; }
    .visio-hero .features-list { list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 20px; }
    .visio-hero .features-list li { display: flex; align-items: center; gap: 8px; color: #334155; font-size: 0.9375rem; }
    .visio-hero .features-list li i { color: #00d8ff; filter: drop-shadow(0 0 5px #00d8ff); }

    /* BACKGROUNDS PADRÃO */
    .hero-section-bg {
        background: radial-gradient(circle at top right, rgba(0, 216, 255, 0.15) 0%, transparent 40%),
                    radial-gradient(circle at bottom left, rgba(0, 119, 255, 0.15) 0%, transparent 40%);
    }

    .services-title-color { color: var(--neon-text-main); }

    .portfolio-section-bg {
        padding: 60px 0;
    }

    /* GRID & CARDS (3D FLIP NEON) */
    .tech-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 35px;
        margin-top: 40px;
        perspective: 1200px;
    }

    .tech-card {
        position: relative;
        border-radius: 16px;
        height: 285px;
        opacity: 0;
        transform: translateY(-100px) scale(0.9);
        transition: opacity 1.8s cubic-bezier(0.25, 1, 0.5, 1), 
                    transform 1.8s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .tech-card.show {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .tech-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: 16px;
        transition: transform 1.2s cubic-bezier(0.25, 1, 0.5, 1);
        transform-style: preserve-3d;
    }

    .tech-card:hover .tech-card-inner {
        transform: rotateY(180deg);
    }

    .card-front, .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 16px;
        backface-visibility: hidden;
        overflow: hidden;
        padding: 0;
        box-sizing: border-box;
        background: var(--neon-card-bg);
        border: 2px solid var(--neon-card-border);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12), 0 0 20px rgba(0, 216, 255, 0.15);
        backdrop-filter: blur(10px);
        transition: background 0.4s ease, border-color 0.4s ease, box-shadow 0.4s ease;
    }

    .tech-card:hover .card-front, 
    .tech-card:hover .card-back {
        border-color: #00f0ff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), 0 0 30px rgba(0, 240, 255, 0.6);
    }

    .card-front {
        display: flex;
        flex-direction: column;
    }

    .card-back {
        transform: rotateY(180deg);
        padding: 25px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .tech-img-wrapper {
        position: relative;
        width: 100%;
        height: 185px;
        overflow: hidden;
    }

    .tech-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .tech-card-content {
        padding: 15px 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-grow: 1;
    }

    .tech-card-content h3 {
        color: var(--neon-text-main);
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        text-align: center;
    }

    .tech-card-line {
        height: 3px;
        width: 35%;
        background: #00d8ff;
        margin: 10px auto 0;
        box-shadow: 0 0 12px #00d8ff, 0 0 5px #00f0ff;
        transition: width 0.8s ease;
        border-radius: 2px;
    }

    .tech-card:hover .tech-card-line {
        width: 85%;
        background: #00f0ff;
        box-shadow: 0 0 18px #00f0ff, 0 0 8px #ffffff;
    }

    .hud-corners span {
        position: absolute;
        width: 12px;
        height: 12px;
        border-color: #00d8ff;
        border-style: solid;
        opacity: 0.9;
        filter: drop-shadow(0 0 4px #00d8ff);
    }
    .hud-corners span:nth-child(1) { top: 8px; left: 8px; border-width: 2px 0 0 2px; }
    .hud-corners span:nth-child(2) { top: 8px; right: 8px; border-width: 2px 2px 0 0; }
    .hud-corners span:nth-child(3) { bottom: 8px; left: 8px; border-width: 0 0 2px 2px; }
    .hud-corners span:nth-child(4) { bottom: 8px; right: 8px; border-width: 0 2px 2px 0; }

    .back-content {
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: space-between;
        z-index: 2;
    }

    .back-tag {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 1.8px;
        color: #0066ff;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    body.dark-theme .back-tag, html[data-theme="dark"] .back-tag {
        color: #00d8ff;
        text-shadow: 0 0 8px rgba(0, 216, 255, 0.6);
    }

    .back-content h4 {
        color: var(--neon-text-main);
        font-size: 1.25rem;
        margin: 6px 0;
        font-weight: 700;
    }

    .back-content p {
        color: var(--neon-text-muted);
        font-size: 0.88rem;
        line-height: 1.55;
        margin: 0;
    }

    .back-footer {
        border-top: 1px dashed rgba(0, 216, 255, 0.4);
        padding-top: 12px;
        font-size: 0.8rem;
        color: #0066ff;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    body.dark-theme .back-footer, html[data-theme="dark"] .back-footer {
        color: #00d8ff;
        text-shadow: 0 0 6px rgba(0, 216, 255, 0.5);
    }
</style>

<!-- ============================================================
     SCRIPTS DE ANIMAÇÃO COM INTERSECTION OBSERVER
     ============================================================ -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    /* Observador para a seção de Aplicações */
    const techCards = document.querySelectorAll(".tech-card");
    const techObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const index = Array.from(techCards).indexOf(entry.target);
                setTimeout(() => {
                    entry.target.classList.add("show");
                }, index * 180);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    techCards.forEach((card) => techObserver.observe(card));

    /* Nova Animação Progressiva de Revelação Vertical Sequencial */
    const flowCards = document.querySelectorAll(".flow-step-card");
    const flowObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const cardsArray = Array.from(flowCards);
                const index = cardsArray.indexOf(entry.target);
                
                // Entrada em cascata suave por índice
                setTimeout(() => {
                    entry.target.classList.add("active-reveal");
                }, index * 140);
                
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    flowCards.forEach((card) => flowObserver.observe(card));
});
</script>

<?= view('sistema/layout/footer') ?>