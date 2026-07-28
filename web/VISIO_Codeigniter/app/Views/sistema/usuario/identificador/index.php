<?= view('sistema/layout/header') ?>

<style>
    /* === PALETA DE CORES === */
    :root {
        --color-surface-dark: #17182c;
        --color-surface-card: #131b4f;
        --color-surface-btn: #2a3472;
        --color-surface-btn-hover: #515b99;
        --color-primary: #1e6be7;
        --color-primary-hover: #47cdfd;
        --color-primary-dark: #1557c0;
        --color-primary-accent: #2662d9;
        --color-accent-adm: #3a86ff;
        --color-btn-grad-a: #0b1b3d;
        --color-btn-grad-b: #08142b;
        --color-btn-grad-text: #d4e0f7;
        --color-btn-grad-before: #8592ad;
    }

    .identificador-main {
        position: relative;
        z-index: 1;
        margin: 0 auto;
        padding: 40px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 30px;
    }

    .identificador-main::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image:
            linear-gradient(rgba(30, 107, 231, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(30, 107, 231, 0.05) 1px, transparent 1px);
        background-size: 60px 60px;
        pointer-events: none;
        z-index: 0;
    }

    .identificador-main h1 {

        color: #1e293b;
        text-align: center;
        margin: 0;
    }

    #video {
        transform: scaleX(-1);
    }

    #camera-container {
        position: relative;
        width: 100%;
        max-width: 700px;
        aspect-ratio: 4 / 3;
        border-radius: 16px;
        overflow: hidden;
        box-shadow:
            0 0 0 1px rgba(0, 0, 0, 0.1),
            0 15px 40px rgba(0, 0, 0, 0.1),
            0 0 60px rgba(30, 107, 231, 0.1);
        border: 3px solid var(--color-primary-accent);
        background: #000;
    }

    #camera-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .identificador-btn {
        padding: 16px 48px;
        border-radius: 12px;
        font-size: 1.125rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        color: #ffffff;
        box-shadow: 0 4px 20px rgba(30, 107, 231, 0.3);
    }

    .identificador-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(30, 107, 231, 0.4);
        background: var(--color-primary-dark);
    }

    #result {
        width: 100%;
        max-width: 700px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        text-align: center;
        color: #475569;
        font-size: 1rem;
    }

    #result h3 {
        color: var(--color-primary);
        font-size: 1.5rem;
        margin: 0 0 15px 0;
    }

    #result p {
        margin: 10px 0;
        line-height: 1.6;
    }

    #result strong {
        color: #1e293b;
    }

    #result img {
        border-radius: 12px;
        margin-top: 15px;
        border: 2px solid #e2e8f0;
    }

    @media (prefers-color-scheme: dark) {
        .identificador-main::before {
            background-image:
                linear-gradient(rgba(30, 107, 231, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30, 107, 231, 0.08) 1px, transparent 1px);
        }

        .identificador-main h1 {
            color: var(--color-btn-grad-text);
        }

        #camera-container {
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.1),
                0 20px 50px rgba(0, 0, 0, 0.5),
                0 0 80px rgba(30, 107, 231, 0.2);
        }

        .identificador-btn {
            color: var(--color-btn-grad-text);
            border: 1px solid var(--color-primary);
        }

        .identificador-btn:hover {
            background: var(--color-surface-btn);
            border-color: var(--color-primary-hover);
        }

        #result {
            background: var(--color-surface-card);
            border: 1px solid var(--color-primary-dark);
            box-shadow: none;
            color: var(--color-btn-grad-before);
        }

        #result h3 {
            color: var(--color-primary-hover);
        }

        #result p {
            color: var(--color-btn-grad-before);
        }

        #result strong {
            color: var(--color-btn-grad-text);
        }

        #result img {
            border-color: var(--color-primary-dark);
        }
    }

    @media (max-width: 768px) {
        .identificador-main h1 {
            font-size: 1.75rem;
        }

        #camera-container {
            max-width: 100%;
        }

        .identificador-btn {
            width: 100%;
            padding: 16px 24px;
        }
    }
</style>

<body class="body_identificador">

    <main class="identificador-main">
        <br><br>
        <h1>Identificação de dispositivos via câmera</h1>

        <section id="camera-container">
            <video id="video" autoplay muted playsinline></video>
            <canvas id="canvas" style="display:none;"></canvas>
        </section>

        <button id="btn-identificar" class="identificador-btn">
            <i class="fa-solid fa-camera"></i> Identificar
        </button>

        <section id="result">
            Aguardando...
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
    <script>
        const MODEL_URL = "https://teachablemachine.withgoogle.com/models/G7gJB1a1F/";
        const BASE_URL = "<?= base_url() ?>";

        let model;
        const video = document.getElementById("video");
        const canvas = document.getElementById("canvas");
        const result = document.getElementById("result");

        async function startCamera() {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            await new Promise(resolve => {
                video.onloadedmetadata = () => { video.play(); resolve(); };
            });
        }

        async function loadModel() {
            model = await tmImage.load(
                MODEL_URL + "model.json",
                MODEL_URL + "metadata.json"
            );
        }

        async function identificar() {
            if (!model) {
                result.textContent = "Modelo não carregado";
                return;
            }

            result.textContent = "Analisando...";

            console.log("videoWidth:", video.videoWidth, "videoHeight:", video.videoHeight);

            canvas.width = video.videoWidth || 224;
            canvas.height = video.videoHeight || 224;
            canvas.getContext("2d").drawImage(video, 0, 0, canvas.width, canvas.height);

            let prediction;
            try {
                prediction = await model.predict(canvas);
                console.log("prediction:", prediction);
            } catch (err) {
                console.error("Erro no predict:", err);
                result.textContent = "Erro no predict: " + err.message;
                return;
            }

            let best = prediction[0];
            for (let i = 1; i < prediction.length; i++) {
                if (prediction[i].probability > best.probability) {
                    best = prediction[i];
                }
            }

            console.log("Melhor classe:", best.className, "Confiança:", best.probability);

            if (best.probability < 0.7) {
                result.textContent = "Não reconhecido com confiança suficiente";
                return;
            }

            try {
                const response = await fetch("<?= base_url('identificador/buscar-sensor') ?>", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({ nome: best.className.toLowerCase().trim() })
                });

                console.log("Status do fetch:", response.status);
                const data = await response.json();
                console.log("Resposta do banco:", data);

                if (!data.success) {
                    result.innerHTML = `
                <strong>${best.className}</strong><br>
                Confiança: ${(best.probability * 100).toFixed(1)}%<br><br>
                Sensor identificado, mas não encontrado no banco.
            `;
                    return;
                }

                const s = data.sensor;
                result.innerHTML = `
            <h3>${s.NOME}</h3>
            <p><strong>Confiança:</strong> ${(best.probability * 100).toFixed(1)}%</p>
            <p><strong>Descrição:</strong><br>${s.DESCRICAO}</p>
            <p><strong>Circuito:</strong><br>${s.CIRCUITO}</p>
            ${s.FOTO ? `<img src="${BASE_URL}${s.FOTO}" alt="${s.NOME}" style="max-width:300px;">` : ''}
        `;

            } catch (err) {
                console.error("Erro no fetch:", err);
                result.textContent = "Erro ao consultar o banco de dados.";
            }
        }

        document.getElementById("btn-identificar").addEventListener("click", identificar);

        async function init() {
            try {
                await startCamera();
                await loadModel();
            } catch (err) {
                console.error(err);
                result.textContent = `Erro: ${err.name} - ${err.message}`;
            }
        }

        init();
    </script>

    <?= view('sistema/layout/footer') ?>