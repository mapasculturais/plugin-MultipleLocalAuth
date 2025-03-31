<?php

/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 *
 */

use MapasCulturais\i;

$this->import('
    mc-modal
');

?>

<div v-if="configs.strategies.govbr?.visible" class="link-govbr-modal">
    <mc-modal ref="linkGovbrModal" title="<?= i::esc_attr_e('Vincular conta gov.br') ?>" classes="link-govbr__modal login-govbr" @close="disableModal($event, false)">
        <template #default>
            <div class="link-govbr__content login-govbr__content">
                <p><?= sprintf(
                    i::__('Você pode vincular sua conta ao %s para facilitar seu acesso a esse serviço. A vinculação é opcional e pode ser feita a qualquer momento.'),
                    '<a href="https://www.gov.br/" target="_blank">' . i::__('gov.br') . '</a>'
                ) ?></p>
                <p><?= i::__('Clique abaixo para vincular sua conta agora.') ?></p>

                <a class="button button--icon button--large button--md govbr" href="<?php echo $app->createUrl('panel', 'my-account') ?>" @click.prevent="disableModal($event)">
                    <img height="16" src="<?php $this->asset('img/govbr-white.svg'); ?>" alt="">
                    <?= i::__('Vincular conta gov.br') ?>
                </a>
            </div>
        </template>

        <template #button="modal">
            <span></span>
        </template>
    </mc-modal>
</div>