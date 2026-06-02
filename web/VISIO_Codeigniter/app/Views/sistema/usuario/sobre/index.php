<?= view('sistema/layout/header') ?>
    <main class="about-page">
        <section class="about-cards">
            <video autoplay muted loop playsinline class="video-bg-sobre">
                <source src="assets/images/Videos/fun_video.mp4" type="video/mp4">
            </video>
            <article class="about-card">
                <h2 class="titulo_sobre" style="color: #0084f7;">Missão</h2>
                <p>Desenvolvemos uma solução interativa de identificação de sensores IoT que simplifica conceitos
                    complexos.
                    Nossa missão é oferecer uma jornada educacional envolvente, despertando o interesse tecnológico de
                    forma intuitiva.</p>
            </article>

            <article class="about-card">
                <h2 class="titulo_sobre" style="color: #0084f7;">Visão</h2>
                <p>Ser a referência global em clareza e precisão, transformando a complexidade da identificação de
                    valores em uma experiência simples, transparente e confiável para todos.</p>
            </article>

            <article class="about-card">
                <h2 class="titulo_sobre" style="color: #0084f7;">Valores</h2>
                <ul>
                    <li><strong>Inovação:</strong> Aplicar tecnologias avançadas para resolver problemas reais.</li>
                    <li><strong>Segurança:</strong> Garantir integridade e confiabilidade dos dados.</li>
                    <li><strong>Educação:</strong> Apoiar o ensino de IoT e automação de forma didática.</li>
                    <li><strong>Eficiência:</strong> Otimizar processos e reduzir erros.</li>
                </ul>
            </article>

            <article class="about-card full-width">
                <h2 class="titulo_sobre" style="color: #0084f7;">Tecnologias utilizadas</h2>
                <ul class="tech-tags">
                    <li style="background-color: #e1e0e0da" style="background-color: #e1e0e0da">HTML</li>
                    <li style="background-color: #e1e0e0da">CSS</li>
                    <li style="background-color: #e1e0e0da">JavaScript</li>
                    <li style="background-color: #e1e0e0da">PHP</li>
                    <li style="background-color: #e1e0e0da">API REST (JSON)</li>
                    <li style="background-color: #e1e0e0da">IoT / sensores</li>
                </ul>
            </article>

            <article class="about-card full-width">
                <h2 class="titulo_sobre" style="color: #0084f7;">Sobre o VISIO</h2>
                <p>O VISIO é uma plataforma inovadora que combina visão computacional e IoT para identificar sensores de
                    forma automática, segura e didática. Nosso objetivo é facilitar a gestão de sensores em ambientes
                    educacionais e industriais, garantindo eficiência, segurança e aprendizado prático.</p>
            </article>

            <article class="about-card full-width">
                <h2 class="titulo_sobre" style="color: #0084f7;">Integrantes</h2>
                <div class="team-container">
                    <div class="team-row">
                        <div class="team-member">
                            <img src="assets/images/Grupo/isabela.png" alt="Foto de Isabela Tessarin">
                            <p class="nome">Isabela Tessarin</p>
                            <span class="funcao">Desenvolvedora Full-Stack &amp; Product Owner</span><br>
                            <span> <a href="https://github.com/isinhaT" style="color: #0084f7;"><i class="fa-brands fa-github"></i></a></span>
                        </div>
                        <div class="team-member">
                            <img src="assets/images/Grupo/matheus.png" alt="Foto de Matheus Neri">
                            <p class="nome">Matheus Neri</p>
                            <span class="funcao">Desenvolvedor Full-Stack</span><br>
                            <span> <a href="https://github.com/NeriMH" style="color: #0084f7;"><i class="fa-brands fa-github"></i></a></span>

                        </div>
                        <div class="team-member">
                            <img src="assets/images/Grupo/fernanda.png" alt="Foto de Fernanda Amaral">
                            <p class="nome">Fernanda Amaral</p>
                            <span class="funcao">Programadora Back-End</span> <br>
                            <span> <a href="https://github.com/feramaralll" style="color: #0084f7;"><i
                                        class="fa-brands fa-github"></i></a></span>

                        </div>
                        <div class="team-member">
                            <img src="assets/images/Grupo/emily.png" alt="Foto de Emily Maiara">
                            <p class="nome">Emily Maiara</p>
                            <span class="funcao">Programadora Back-End &amp; Scrum Master</span> <br>
                            <span> <a href="https://github.com/maiaraemily" style="color: #0084f7;"><i
                                        class="fa-brands fa-github"></i></a></span>
                        </div>
                    </div>
                    <div class="team-row">
                        <div class="team-member">
                            <img src="assets/images/Grupo/guilherme.png" alt="Foto de Guilherme Staconi">
                            <p class="nome">Guilherme Staconi</p>
                            <span class="funcao">Analista de sistema e designer</span> <br>
                            <span> <a href="https://github.com/guizim-GitFF" style="color: #0084f7;"><i
                                        class="fa-brands fa-github"></i></a></span>

                        </div>
                        <div class="team-member">
                            <img src="assets/images/Grupo/lorrana.png" alt="Foto de Lorrana Generoso">
                            <p class="nome">Lorrana Generoso</p>
                            <span class="funcao">Analista de sistema e designer</span> <br>
                            <span> <a href="https://github.com/LorranaG" style="color: #0084f7;"><i class="fa-brands fa-github"></i></a></span>
                        </div>
                        <div class="team-member">
                            <img src="assets/images/Grupo/sophia.png" alt="Foto de Sophia Peron">
                            <p class="nome">Sophia Peron</p>
                            <span class="funcao">Analista de sistema e designer</span> <br>
                            <span> <a href="https://github.com/SosoPeron" style="color: #0084f7;"><i class="fa-brands fa-github"></i></a></span>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </main>
</html>

<?= view('sistema/layout/footer') ?>