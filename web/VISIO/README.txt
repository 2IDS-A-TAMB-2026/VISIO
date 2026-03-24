🏠 index.html

Usar imagens diferentes no carrossel (não repetir a mesma).

Melhorar o footer (contato, redes ou github).

Opcional: adicionar meta description.

👥 sobre.html

Ajustar texto da missão (não focar só em crianças).

Adicionar seção “Tecnologias utilizadas” (HTML, CSS, JS, PHP, API, IoT).

Padronizar melhor as funções da equipe.

📩 contato.html

Formulário não envia → trocar action="#" por backend (api/contato.php).

Adicionar required nos campos.

Mostrar mensagem de sucesso após envio.

📷 identificador.html

Adicionar botão “Capturar sensor”.

Mostrar “Analisando dispositivo…”.

Mostrar resultado “Sensor identificado: ...”.

📝 cadastro.html

Adicionar campo Nome completo.

Usar type="date" para nascimento.

Usar type="tel" para telefone.

Mostrar mensagem de cadastro realizado.

🔑 login.html

Adicionar required nos campos.

Mostrar erro “email ou senha incorretos”.

Redirecionar após login (ex: dashboard ou lista).

🛡 loginAdm.html

Redirecionar admin para lista.php.

Aviso “Área restrita para administradores”.

🔒 senha.html

Trocar botão para “Enviar nova senha”.

Conectar com backend (recuperação de senha real).

Mostrar mensagem “email enviado”.

📋 lista.php

REMOVER coluna senha (segurança).

Corrigir código PHP duplicado.

Usar htmlspecialchars().

Opcional: botão editar usuário.

❓ questoes.html

Corrigir HTML inválido (body dentro de body).

Fazer perguntas sobre IoT e sensores, não história.

Adicionar JavaScript para corrigir respostas.

Colocar mais perguntas (5–10).

⚙ Melhorias gerais

Padronizar menu em todas as páginas.

Usar hash de senha (password_hash).

Melhorar feedback visual do sistema.
1️ Página de sensores
Não existe uma tela para listar ou gerenciar sensores.

2️ Gestão de sensores
Não há funções para cadastrar, editar ou visualizar sensores.

3 Histórico de identificação
O sistema não salva quando um sensor foi identificado.

4️ Resultado da identificação
A câmera abre, mas não mostra um resultado real do sensor identificado.

5️ Exercícios incompletos
A página de questões:

tem só uma pergunta

não corrige a resposta

não mostra pontuação.

6️ Segurança de senha
A senha aparece na lista de usuários e provavelmente não está criptografada.