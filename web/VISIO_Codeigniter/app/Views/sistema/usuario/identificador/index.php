    <?= view('sistema/layout/header') ?>
    
    <body class="body_identificador">

        <main class="identificador-main">
            <h1>Identificação de dispositivos via câmera</h1>

            <section id="camera-container">
                <video id="video" autoplay muted playsinline></video>
            </section>

            <button id="btn-identificar" class="identificador-btn">
                Identificar
            </button>

            <section id="result">
                Aguardando...
            </section>
        </main>

        <script src="JS/theme.js"></script>
        <script>
            const URL = "https://teachablemachine.withgoogle.com/models/0Ee9Wvl_p/";

            let model;
            const video = document.getElementById("video");
            const result = document.getElementById("result");

            async function startCamera() {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: "environment" }
                });
                video.srcObject = stream;
            }

            async function loadModel() {
                const modelURL = URL + "model.json";
                const metadataURL = URL + "metadata.json";
                model = await tmImage.load(modelURL, metadataURL);
            }
            function normalizar(str) {
                return str
                    .toLowerCase()
                    .replace(/[_-]/g, " ")
                    .trim();
            }

            const descricoes = {
                "sensor bluetooth": "Dispositivo de comunicação sem fio de curto alcance baseado em Bluetooth, utilizado para troca de dados entre dispositivos e integração com sistemas embarcados.",

                "tracker": "Módulo rastreador utilizado para monitoramento de posição e movimento, geralmente baseado em GPS ou RF, aplicado em localização de objetos ou ativos.",

                "flying fish": "Sensor de detecção de obstáculos/linha baseado em infravermelho (IR), comum em robótica para identificar superfícies, bordas ou presença de objetos."
            };
            async function identificar() {
                if (!model) {
                    result.textContent = "Modelo não carregado";
                    return;
                }

                result.textContent = "Analisando...";

                const prediction = await model.predict(video);

                let best = prediction[0];

                for (let i = 1; i < prediction.length; i++) {
                    if (prediction[i].probability > best.probability) {
                        best = prediction[i];
                    }
                }

                if (best.probability < 0.7) {
                    result.textContent = "Não reconhecido com confiança suficiente";
                    return;
                }

                const key = normalizar(best.className);
                const descricao = descricoes[key] || "Descrição não disponível.";

                result.innerHTML =
                    `<strong>${best.className}</strong><br>
        Confiança: ${(best.probability * 100).toFixed(1)}%<br><br>
        ${descricao}`;
            }

            document
                .getElementById("btn-identificar")
                .addEventListener("click", identificar);

            async function init() {
                try {
                    await startCamera();
                    await loadModel();
                } catch (err) {
                    console.error(err);
                    result.textContent = "Erro ao iniciar câmera ou modelo";
                }
            }
            init();
        </script>
    </body>
</html>

<?= view('sistema/layout/footer') ?>