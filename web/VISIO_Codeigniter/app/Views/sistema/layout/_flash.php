<?php
/**
 * _flash.php — Partial reutilizável de mensagens de feedback

 * Converte os flashdata do CodeIgniter em toasts SweetAlert2.
 * Inclua com: <?= view('sistema/layout/_flash') ?>
 * Pode ser colocado em qualquer view — normalmente logo após o <main>.

 * Tipos suportados:
 *   session()->getFlashdata('sucesso') → toast verde  (success)
 *   session()->getFlashdata('erro')    → toast vermelho (error)
 *   session()->getFlashdata('aviso')   → toast amarelo  (warning)
 *   session()->getFlashdata('info')    → toast azul     (info)

 * Os toasts aparecem no canto superior direito e somem após 4 segundos.
 * O usuário também pode fechar manualmente clicando no X.
 */

$flashSucesso = session()->getFlashdata('sucesso');
$flashErro    = session()->getFlashdata('erro');
$flashAviso   = session()->getFlashdata('aviso');
$flashInfo    = session()->getFlashdata('info');

// Só carrega o SweetAlert2 e o script se houver alguma mensagem para exibir
if ($flashSucesso || $flashErro || $flashAviso || $flashInfo):
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    'use strict';

    /* Configuração padrão do Toast VISIO */
    const Toast = Swal.mixin({
        toast:            true,
        position:         'top-end',
        showConfirmButton: false,
        timer:            4000,
        timerProgressBar: true,
        showCloseButton:  true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    /* Fila de mensagens para exibir em sequência */
    const fila = [];

    <?php if ($flashSucesso): ?>
    fila.push({
        icon:  'success',
        title: <?= json_encode(esc($flashSucesso)) ?>
    });
    <?php endif; ?>

    <?php if ($flashErro): ?>
    fila.push({
        icon:  'error',
        title: <?= json_encode(esc($flashErro)) ?>
    });
    <?php endif; ?>

    <?php if ($flashAviso): ?>
    fila.push({
        icon:  'warning',
        title: <?= json_encode(esc($flashAviso)) ?>
    });
    <?php endif; ?>

    <?php if ($flashInfo): ?>
    fila.push({
        icon:  'info',
        title: <?= json_encode(esc($flashInfo)) ?>
    });
    <?php endif; ?>

    /* Dispara os toasts em sequência com 300ms de intervalo */
    fila.forEach(function (msg, i) {
        setTimeout(function () {
            Toast.fire({
                icon:  msg.icon,
                title: msg.title
            });
        }, i * 300);
    });
})();
</script>
<?php endif; ?>
