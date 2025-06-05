<?php

/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;
?>

<div class="resent-email-validation regular">
    <div class="resent-email-validation__label resent-email-validation__confirmed" v-if="accountIsActive == true">
        <mc-icon name="check" size="16"></mc-icon>
        <label class="label"><?= i::__('E-mail já confirmado') ?></label>
    </div>
    <span v-else>
        <div v-if="accountIsActive == false && checkEmailWasSent" class="resent-email-validation__label resent-email-validation__sent">
            <mc-icon name="check" size="16"></mc-icon>
            <label><?= i::__('Link de validação de conta reenviado com sucesso') ?></label>
        </div>
        <div v-if="accountIsActive == false && !checkEmailWasSent" class="resent-email-validation__label" @click="resendEmailValidation" style="cursor:pointer;">
            <mc-icon name="send" size="16"></mc-icon>
            <label><?= i::__('Reenviar link de validação de conta') ?></label>
        </div>
    </span>
</div>