(function () {
    var root = document.getElementById("quiz-root");
    var questoes = [];
    var indice = 0;
    var acertos = 0;

    function escapeHtml(t) {
        var d = document.createElement("div");
        d.textContent = t;
        return d.innerHTML;
    }

    function render() {
        if (!questoes.length) {
            root.innerHTML = "<p class=\"quiz-empty\">Não há questões disponíveis no momento. Um administrador precisa cadastrá-las.</p>";
            return;
        }

        if (indice >= questoes.length) {
            root.innerHTML =
                "<div class=\"quiz-fim\"><h2>Fim do quiz</h2><p>Você acertou <strong>" +
                acertos +
                "</strong> de <strong>" +
                questoes.length +
                "</strong>.</p><button type=\"button\" class=\"btn-salvar\" id=\"quiz-reiniciar\" style=\"margin-top: 15px; padding: 10px 20px;\">Reiniciar</button></div>";
            document.getElementById("quiz-reiniciar").addEventListener("click", function () {
                indice = 0;
                acertos = 0;
                render();
            });
            return;
        }

        var q = questoes[indice];
        var alts = q.alternativas || [];
        var optsHTML = "";

        // Renderiza as 4 alternativas (a, b, c, d)
        for (var i = 0; i < alts.length; i++) {
            var letra = String.fromCharCode(97 + i); // 97 = 'a', 98 = 'b', etc.
            optsHTML += "<label style='display:block; margin: 12px 0; cursor: pointer;'><input type='radio' name='opcao' value='" + letra + "'> " + letra.toUpperCase() + ") " + escapeHtml(alts[i].texto) + "</label>";
        }

        root.innerHTML =
            "<div class='questao-card' style='background: var(--color-bg-card, #1a1a24); padding: 20px; border-radius: 12px; margin-top: 20px;'>" +
            "<h3 style='margin-bottom: 15px;'>Questão " + (indice + 1) + " de " + questoes.length + " <span style='font-size: 12px; color: #aaa; font-weight: normal;'>(" + escapeHtml(q.nivel) + ")</span></h3>" +
            "<p style='font-size: 16px; margin-bottom: 20px;'>" + escapeHtml(q.descricao) + "</p>" +
            "<div class='alternativas'>" + optsHTML + "</div>" +
            "<button type='button' class='btn-salvar' id='btn-responder' style='margin-top: 20px; padding: 10px 20px; background: #3a86ff; color: white; border: none; border-radius: 8px; cursor: pointer;'>Responder</button>" +
            "</div>";

        document.getElementById("btn-responder").addEventListener("click", function () {
            var sel = document.querySelector("input[name='opcao']:checked");
            if (!sel) {
                alert("Selecione uma alternativa antes de responder!");
                return;
            }
            var escolha = sel.value; // ex: "a", "b"
            var correta = null;

            // Procura no array de alternativas qual era a correta
            for (var j = 0; j < alts.length; j++) {
                if (alts[j].correta === true) {
                    correta = String.fromCharCode(97 + j); // Devolve "a", "b", "c" ou "d"
                    break;
                }
            }

            if (escolha === correta) {
                acertos++;
                alert("✅ Resposta correta!");
            } else {
                alert("❌ Resposta incorreta!\n\nA alternativa correta era a: " + correta.toUpperCase());
            }
            indice++; // Passa para a próxima questão
            render(); // Atualiza a tela
        });
    }

    // Vai buscar as questões à API
    fetch("api/questoes.php")
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!Array.isArray(data)) {
                questoes = [];
            } else {
                // Algoritmo que baralha as questões para que o Quiz seja sempre diferente
                questoes = data.slice();
                for (var i = questoes.length - 1; i > 0; i--) {
                    var j = Math.floor(Math.random() * (i + 1));
                    var t = questoes[i];
                    questoes[i] = questoes[j];
                    questoes[j] = t;
                }
            }
            render();
        })
        .catch(function () {
            root.innerHTML = "<p>Erro ao carregar as questões. Verifique se o servidor PHP está a rodar.</p>";
        });
})();