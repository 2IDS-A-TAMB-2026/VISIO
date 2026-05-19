<?= view('sistema/layout/header') ?>

<body>
        <main class="sensores-page-main">
            <h1 class="sensores-page-title">Tipos de sensores IoT</h1>
            <p class="sensores-page-lead">Conheça exemplos de sensores usados em projetos de Internet das Coisas e automação.</p>

        <div class="sensores-grid">
            <!-- SENSOR TEMPERATURA -->
        <div class="cardi" onclick="mostrarSensor()">
            <h3>Sensor de Temperatura</h3>
            <img src="./assets/images/Sensores/sensor_temperatura.jpg" alt="Sensor de temperatura">
            <p>Identifica variações de calor ou frio em um ambiente ou objeto.</p>
        </div>

        <!-- SENSOR PROXIMIDADE -->
        <div class="cardi" onclick="mostrarProximidade()">
            <h3>Sensor de Proximidade</h3>
            <img src="./assets/images/Sensores/sensor_proximidade.jpg" alt="Sensor de proximidade">
            <p>Detecta quando um objeto está próximo sem precisar de contato físico direto.</p>
        </div>

        <!-- SENSOR UMIDADE -->
        <div class="cardi" onclick="mostrarUmidade()">
            <h3>Sensor de Umidade</h3>
            <img src="./assets/images/Sensores/sensor_umidade.jpg" alt="Sensor de umidade">
            <p>Mede a quantidade de vapor de água presente no ar do ambiente.</p>
        </div>

        <!-- SENSOR LUZ -->
        <div class="cardi" onclick="mostrarLuz()">
            <h3>Sensor de Luz</h3>
            <img src="./assets/images/Sensores/sensor_luz.jpg.jpeg" alt="Sensor de luz">
            <p>Mede a intensidade da luz no ambiente para ajustar brilho ou iluminação.</p>
        </div>

        <!-- SENSOR MOVIMENTO -->
        <div class="cardi" onclick="mostrarMovimento()">
            <h3>Sensor de Movimento</h3>
            <img src="./assets/images/Sensores/sensor_movimento.jpg.jpeg" alt="Sensor de movimento">
            <p>Detecta presença de pessoas através da variação de calor corporal.</p>
        </div>

        <!-- SENSOR ULTRASSÔNICO -->
        <div class="cardi" onclick="mostrarUltrassonico()">
            <h3>Sensor Ultrassônico</h3>
            <img src="./assets/images/Sensores/sensor_ultrassonico.jpg" alt="Sensor ultrassônico">
            <p>Calcula a distância até objetos usando ondas sonoras refletidas.</p>
        </div>

        <!-- SENSOR GÁS -->
        <div class="cardi" onclick="mostrarGas()">
            <h3>Sensor de Gás/Fumaça</h3>
            <img src="./assets/images/Sensores/sensor_gas.jpg.jpeg" alt="Sensor de gás">
            <p>Identifica a presença de gases ou fumaça que podem indicar perigo.</p>
        </div>

        <!-- SENSOR PRESSÃO -->
        <div class="cardi" onclick="mostrarPressao()">
            <h3>Sensor de Pressão (Barômetro)</h3>
            <img src="./assets/images/Sensores/sensor_pressao.jpg" alt="Sensor de pressão">
            <p>Mede a pressão do ar para indicar clima ou altitude.</p>
        </div>

        <!-- SENSOR TOQUE -->
        <div class="cardi" onclick="mostrarToque()">
            <h3>Sensor de Toque</h3>
            <img src="./assets/images/Sensores/sensor_toque.jpg.jpeg" alt="Sensor de toque">
            <p>Reconhece o contato físico direto em superfícies sensíveis ao toque.</p>
        </div>

                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <script>
                function popupSensor(titulo, texto, imagem){
                Swal.fire({
                    title: titulo,
                    html: `
                    <div style="text-align:left; font-size:16px; line-height:1.8; padding:10px;">
                        ${texto}
                    </div>
                    `,
                    imageUrl: imagem,
                    imageWidth: 420,
                    imageHeight: 250,
                    confirmButtonText: "Fechar",
                    background: "#ffffff",
                    color: "#222",
                    width: "650px",
                    padding: "1.5em",
                    confirmButtonColor:  "#3a86ff",
                    customClass: {
                    title: "titulo-alerta",
                    popup: "popup-bonito"
                    }
                });
                }

                function mostrarSensor(){
                popupSensor("Sensor de Temperatura", `
                <p><b>Função:</b> Mede a temperatura de ambientes, objetos ou sistemas.</p>
                <p><b>Uso:</b> Muito utilizado em casas inteligentes, indústrias, estufas e automação.</p>
                <p><b>Exemplo:</b> Acionar ventiladores quando a temperatura estiver alta.</p>
                <p><b>Benefício:</b> Ajuda no controle térmico e economia de energia.</p>
                `, "./assets/images/Sensores/sensor_temperatura.jpg");
                }

                function mostrarProximidade(){
                popupSensor("Sensor de Proximidade", `
                <p><b>Função:</b> Detecta objetos próximos sem contato físico.</p>
                <p><b>Uso:</b> Celulares, portas automáticas e robótica.</p>
                <p><b>Exemplo:</b> Apagar tela do celular durante chamadas.</p>
                <p><b>Benefício:</b> Mais praticidade e automação.</p>
                `, "./assets/images/Sensores/sensor_proximidade.jpg");
                }

                function mostrarUmidade(){
                popupSensor("Sensor de Umidade", `
                <p><b>Função:</b> Mede a umidade do ar.</p>
                <p><b>Uso:</b> Agricultura, estufas e meteorologia.</p>
                <p><b>Exemplo:</b> Controlar irrigação automática.</p>
                <p><b>Benefício:</b> Melhora conforto e produção.</p>
                `, "./assets/images/Sensores/sensor_umidade.jpg");
                }

                function mostrarLuz(){
                popupSensor("Sensor de Luz", `
                <p><b>Função:</b> Mede intensidade luminosa.</p>
                <p><b>Uso:</b> Celulares e iluminação inteligente.</p>
                <p><b>Exemplo:</b> Ajustar brilho da tela automaticamente.</p>
                <p><b>Benefício:</b> Economia de energia.</p>
                `, "./assets/images/Sensores/sensor_luz.jpg.jpeg");
                }

                function mostrarMovimento(){
                popupSensor("Sensor de Movimento", `
                <p><b>Função:</b> Detecta presença humana.</p>
                <p><b>Uso:</b> Alarmes e luzes automáticas.</p>
                <p><b>Exemplo:</b> Acender luz ao entrar no cômodo.</p>
                <p><b>Benefício:</b> Segurança e praticidade.</p>
                `, "./assets/images/Sensores/sensor_movimento.jpg.jpeg");
                }

                function mostrarUltrassonico(){
                popupSensor("Sensor Ultrassônico", `
                <p><b>Função:</b> Mede distância por ondas sonoras.</p>
                <p><b>Uso:</b> Carros e robôs.</p>
                <p><b>Exemplo:</b> Sensor de ré.</p>
                <p><b>Benefício:</b> Evita colisões.</p>
                `, "./assets/images/Sensores/sensor_ultrassonico.jpg");
                }

                function mostrarGas(){
                popupSensor("Sensor de Gás/Fumaça", `
                <p><b>Função:</b> Detecta gases perigosos e fumaça.</p>
                <p><b>Uso:</b> Casas e empresas.</p>
                <p><b>Exemplo:</b> Alarme de vazamento.</p>
                <p><b>Benefício:</b> Segurança contra acidentes.</p>
                `, "./assets/images/Sensores/sensor_gas.jpg.jpeg");
                }

                function mostrarPressao(){
                popupSensor("Sensor de Pressão", `
                <p><b>Função:</b> Mede pressão atmosférica.</p>
                <p><b>Uso:</b> Meteorologia e altitude.</p>
                <p><b>Exemplo:</b> Previsão do tempo.</p>
                <p><b>Benefício:</b> Informações climáticas precisas.</p>
                `, "./assets/images/Sensores/sensor_pressao.jpg");
                }

                function mostrarToque(){
                popupSensor("Sensor de Toque", `
                <p><b>Função:</b> Detecta toque físico.</p>
                <p><b>Uso:</b> Telas touch e botões digitais.</p>
                <p><b>Exemplo:</b> Smartphone.</p>
                <p><b>Benefício:</b> Interface moderna e rápida.</p>
                `, "./assets/images/Sensores/sensor_toque.jpg.jpeg");
                }
                </script>

                <style>
                .popup-bonito{
                border-radius:20px;
                box-shadow:0 10px 30px rgba(0,0,0,0.20);
                }

                .titulo-alerta{
                text-align:center;
                font-size:28px;
                font-weight:bold;
                color: #3a86ff;
                }

                .cardi{
                cursor:pointer;
                }
                </style>

                </div> <!-- FECHA sensores-grid -->
            </main> <!-- FECHA main -->
        <script src="JS/theme.js"></script>
    </body>
</html>

<?= view('sistema/layout/footer') ?>