<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 *
 */

use MapasCulturais\i;

$cpf_duplicado = false;
$cpf_diferente = false;
$message_error = '';
$support_email = $app->config['auth.config']['strategies']['govbr']['supportEmail'] ?? '';

if(isset($_SESSION['strategy-error'])) {
    foreach($_SESSION['strategy-error'] as $key => $value) {
        if('cpf-duplicado' == ($key ?? false)) {
            $cpf_duplicado = true;
            $user_cpf = $_SESSION['strategy-error-cpf'];

            $message_error = sprintf(i::__('Encontramos duas contas cadastradas com este CPF %s. Para solucionar este problema entre em contato com o suporte pelo email %s'), $user_cpf, $support_email);

            unset($_SESSION['strategy-error-cpf']);
            unset($_SESSION['strategy-error'][$key]);
        }

        if('cpf-diferente' == ($key ?? false)) {
            $cpf_diferente = true;

            $message_error = i::__('Sua conta do <a href="https://gov.br">gov.br</a> vinculada apresenta um número de CPF diferente do cadastrado aqui. Verifique as contas para garantir que sejam do mesmo usuário');

            unset($_SESSION['strategy-error'][$key]);
        }
    }
}

$params = [
    'isAuth' => $app->user->is('guest') ? false : true,
    'action' => $this->controller->action,
    'controller' => $this->controller->id
];

$this->import('
    mc-alert
');

?>

<div v-if="configs.strategies.govbr?.visible" class="login-govbr">
    <mc-modal title="<?= i::esc_attr_e('Boas vindas!') ?>" subtitle="<?= i::esc_attr_e('Entre em sua conta do Mapas Culturais.') ?>" classes="login-govbr__modal">
        <template #default>
            <div class="login-govbr__content">
                <p class="login-govbr__explain">
                <?= sprintf(
                    i::__('O Mapas Culturais está integrado ao %s porque garante segurança ao seu acesso, respeitando os ') ." <a href='#'>termos de uso</a> ". i::__('e as') ." <a href='#'>políticas de privacidade</a>". i::__('.'),
                    '<a href="https://www.gov.br/" target="_blank">gov.br</a>',
                    '<a href="#">' . i::__('Termos de Uso') . '</a>',

                ) ?>
                </p>

                <a v-if="configs.strategies.govbr?.visible" class="button button--icon button--large button--md govbr" href="<?php echo $app->createUrl('auth', 'govbr') ?>" data-params='<?= json_encode($params) ?>' @click.prevent="govBrClick($event)">
                    <div class="img"> <img height="16" class="br-sign-in-img" src="<?php $this->asset('img/govbr-white.svg'); ?>" /> </div>
                    <?= i::__('Entrar com gov.br') ?>
                </a>
                <p> <?= sprintf(
                    i::__('Ao prosseguir você será direcionado ao site %s para identificação e autenticação digital do cidadão através do seu navegador de internet.'),
                    '<a href="https://www.gov.br/" target="_blank">gov.br</a>'
                ) ?></p>
            </div>
        </template>

        <template #button="modal">
            <div>
                <a v-if="configs.strategies.govbr?.visible" class="button button--icon button--md govbr" :class="{'button--sm' : small, 'button--large': large}" @click="modal.open()">
                    <div class="img"> <img height="16" class="br-sign-in-img" src="<?php $this->asset('img/govbr-white.svg'); ?>" /> </div>
                    <?= i::__('Entrar com gov.br') ?>
                </a>
            </div>

            <div>
                <?php if($cpf_diferente): ?>
                    <mc-alert class="col-12" type="warning">
                        <?= $message_error ?>
                    </mc-alert>

                <?php elseif($cpf_duplicado): ?>
                    <mc-alert class="col-12" type="warning">
                        <?= $message_error ?>
                    </mc-alert>
                <?php endif; ?>
            </div>
        </template>
    </mc-modal>
</div>