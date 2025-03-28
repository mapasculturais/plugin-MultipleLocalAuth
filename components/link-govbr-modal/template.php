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
    <mc-modal ref="linkGovbrModal" title="<?= i::esc_attr_e('Vincule sua conta ao Gov.br') ?>" classes="link-govbr__modal" @close="disableModal($event, false)">
        <template #default>
            <div class="link-govbr__content">
                <p><?= i::__('Você pode vincular sua conta ao Gov.br para facilitar o acesso a serviços do governo. A vinculação é opcional e pode ser feita a qualquer momento.') ?></p>
                <p><?= i::__('Clique abaixo para vincular sua conta agora.') ?></p>
                
                <a class="button button--icon button--large button--md button--primary" href="<?php echo $app->createUrl('panel', 'my-account') ?>" @click.prevent="disableModal($event)">                                 
                    <?= i::__('Vincular') ?>                            
                </a>
            </div>
        </template>

        <template #button="modal">
            <span></span>
        </template>
    </mc-modal>
</div>