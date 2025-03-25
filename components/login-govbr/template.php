<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;

$cpf_duplicado = false;
$cpf_diferente = false;

if(isset($_SESSION['strategy-error'])) {
    foreach($_SESSION['strategy-error'] as $key => $value) {
        if('cpf-duplicado' == ($key ?? false)) {
            $cpf_duplicado = true;
            unset($_SESSION['strategy-error'][$key]);
        }
        
        if('cpf-diferente' == ($key ?? false)) {
            $cpf_diferente = true;
            unset($_SESSION['strategy-error'][$key]);
        }
    }
}

$this->import('
    mc-alert
');

?>

<div v-if="configs.strategies.govbr?.visible" class="login-govbr">
    <mc-modal title="<?= i::esc_attr_e('Boas vindas!') ?>" subtitle="<?= i::esc_attr_e('Entre em sua conta do Mapas Culturais.') ?>" classes="login-govbr__modal">
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
            <a v-if="configs.strategies.govbr?.visible" class="button button--icon button--md govbr" :class="[{'button--sm' : small}, {'button--large': large}]" @click="modal.open()">                                
                <?= i::__('Entrar com') ?>                            
                <div class="img"> <img height="16" class="br-sign-in-img" src="<?php $this->asset('img/govbr-white.png'); ?>" /> </div>                                
            </a>

            <?php if($cpf_duplicado): ?>
                <mc-alert class="col-12" type="warning">
                    <?= i::__('Ops! seu CPF está duplicado na plataforma. Por favor, entre em contato com o suporte.') ?>
                </mc-alert>
            <?php endif; ?>

            <?php if($cpf_diferente): ?>
                <mc-alert class="col-12" type="warning">
                    <?= i::__('Ops! seu CPF está diferente na plataforma. Por favor, entre em contato com o suporte.') ?>
                </mc-alert>
            <?php endif; ?>
        </template>
    </mc-modal>
</div>