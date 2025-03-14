<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;
?>

<div v-if="configs.strategies.govbr?.visible" class="login-govbr">
    <mc-modal title="<?= I::esc_attr_e('Boas vindas!') ?>" subtitle="<?= i::esc_attr_e('Entre em sua conta do Mapas Culturais.') ?>" classes="login-govbr__modal">
        <template #default>
            <div class="login-govbr__content">
                <p class="login-govbr__explain">
                    <?= i::__('O Mapas Culturais está integrado ao') ." <a href='#'>Gov.br</a> ". i::__('porque garante segurança ao seu acesso, respeitando os ') ." <a href='#'>termos de uso</a> ". i::__('e as') ." <a href='#'>políticas de privacidade</a>". i::__('.') ?>
                </p>
                    
                <a v-if="configs.strategies.govbr?.visible" class="button button--icon button--large button--md govbr" href="<?php echo $app->createUrl('auth', 'govbr') ?>">                                
                    <?= i::__('Entrar com') ?>                            
                    <div class="img"> <img height="16" class="br-sign-in-img" src="<?php $this->asset('img/govbr-white.png'); ?>" /> </div>                                
                </a>

                <p> <?= i::__('Ao prosseguir você será direcionado ao site') ." <a href='#'>Gov.br</a> ". i::__('para identificação e autenticação digital do cidadão através do seu navegador de internet.') ?></p>
            </div>
        </template>

        <template #button="modal">
            <a v-if="configs.strategies.govbr?.visible" class="button button--icon button--large button--md govbr" @click="modal.open()">                                
                <?= i::__('Entrar com') ?>                            
                <div class="img"> <img height="16" class="br-sign-in-img" src="<?php $this->asset('img/govbr-white.png'); ?>" /> </div>                                
            </a>
            <span v-else></span>
        </template>
    </mc-modal>
</div>

<!--  -->