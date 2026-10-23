<?php
/**
 * Sinalização de verificação de e-mail em Configurações da conta
 * (panel my-account / user-detail).
 *
 * @var MapasCulturais\Theme $this
 */
use MapasCulturais\i;

$this->import('mc-icon');
?>
<div class="email-verification-status">
    <span class="email-verification-status__label"><?= i::__('Verificação de e-mail') ?>:</span>

    <span
        v-if="entity.accountIsActive == '0' || entity.accountIsActive === 0"
        class="email-verification-status__badge email-verification-status__badge--pending"
    >
        <mc-icon name="exclamation"></mc-icon>
        <?= i::__('Pendente de verificação') ?>
    </span>
    <span
        v-else
        class="email-verification-status__badge email-verification-status__badge--verified"
    >
        <mc-icon name="circle-checked"></mc-icon>
        <?= i::__('Conta verificada') ?>
    </span>
</div>
