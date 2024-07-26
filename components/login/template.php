<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;

$this->import('
    mc-card
    password-strongness
');
?>

<div class="login">

    <!-- Login action -->

    <div v-if="!recoveryRequest && !recoveryMode" class="login__action">
        <div class="login__card">
            <div class="login__card__header">
                <h3 v-if="!showPassword && !passwordResetRequired && !userNotFound"> <?= $this->text('welcome', i::__('Boas vindas!')) ?> </h3>
                <h3 v-if="showPassword"> <?= $this->text('welcome', i::__('Boas vindas de volta!')) ?> </h3>
                <h3 v-if="passwordResetRequired"> <?= $this->text('welcome', i::__('Boas vindas de volta!')) ?> </h3>
                <h3 v-if="userNotFound"> <?= $this->text('welcome', i::__('Boas vindas ao Mapas Culturais!')) ?> </h3>
                <h6 v-if="!showPassword && !passwordResetRequired && !userNotFound"> <?= sprintf($this->text('greeting', i::__('Informe seu e-mail ou CPF e iremos verificar se já possui um cadastro no %s')), $app->siteName) ?> </h6>
                <h6 v-if="showPassword && !passwordResetRequired"> <?= i::__('Digite sua senha do Mapas Culturais para avançar') ?> </h6>
                <h6 v-if="passwordResetRequired"> <?= i::__('Verificamos que você já possui cadastro no Mapas com este e-mail ou CPF informado. 
                Porém, como é seu primeiro acesso nesta versão, será necessário criar uma nova senha. Vamos lá?') ?> </h6>
            </div>

            <div class="login__card__content">
                <form class="login__form" @submit.prevent="showPasswordField">
                    <div class="login__fields">
                        <div class="field" v-if="!showPassword && !passwordResetRequired && !userNotFound">
                            <label for="email"> <?= i::__('E-mail ou CPF') ?> </label>
                            <input type="text" name="email" id="email" v-model="email" autocomplete="off" />
                        </div>

                        <div v-if="showPassword && !passwordResetRequired" class="field password">
                            <label for="password"> <?= i::__('Senha') ?> </label>
                            <input type="password" name="password" id="password" v-model="password" autocomplete="off" />
                            <a id="multiple-login-recover" class="login__recover-link" @click="recoveryRequest = true"> <?= i::__('Esqueci minha senha') ?> </a>
                            <div class="seePassword" @click="togglePassword('password', $event)"></div>
                        </div>


                    </div>

                    <VueRecaptcha v-if="configs['google-recaptcha-sitekey'] && !showPassword && !passwordResetRequired && !userNotFound" :sitekey="configs['google-recaptcha-sitekey']" @verify="verifyCaptcha" @expired="expiredCaptcha" @render="expiredCaptcha" class="g-recaptcha"></VueRecaptcha>

                    <div class="login__buttons">
                        <button v-if="!showPassword && !passwordResetRequired && !userNotFound" class="button button--primary button--large button--md" type="submit"> <?= i::__('Próximo') ?> </button>
                        <button v-if="showPassword && !passwordResetRequired" class="button button--primary button--large button--md" type="button" @click="doLogin"> <?= i::__('Entrar') ?> </button>
                        <button v-if="passwordResetRequired" class="button button--primary button--large button--md" @click="recoveryRequest = true"> <?= i::__('Gerar nova senha') ?> </button>
                        <button  v-if="passwordResetRequired || showPassword" class="button button--secondary button--large button--md" @click="resetLoginState"> <?= i::__('Voltar') ?> </button>
                    </div>


                    <div v-if="userNotFound" class="create">
                        <h5 class="bold"> <?= i::__('Usuário não encontrado. Por favor, realize seu cadastro.') ?> </h5>
                        <a class="button button--primary button--large button--md" href="<?php echo $app->createUrl('auth', 'register') ?>"> 
                            <?= i::__('Fazer cadastro') ?>
                        </a>
                        <button class="button button--secondary button--large button--md" @click="resetLoginState"> <?= i::__('Voltar') ?> </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Recovery request -->
    <div v-if="recoveryRequest" class="login__recovery--request">
        <div class="login__card" v-if="!recoveryEmailSent">
            <div class="login__card__header">
                <h3> <?= i::__('Alteração de senha') ?> </h3>
                <h6> <?= i::__('Digite seu e-mail para receber instruções e criar uma nova senha.') ?> </h6>
            </div>

            <div class="login__card__content">
                <form class="grid-12" @submit.prevent="requestRecover();">
                    <div class="field col-12">
                        <label for="email"> <?= i::__('E-mail') ?> </label>
                        <input type="email" name="email" id="email" v-model="email" autocomplete="off" />
                    </div>
                    <VueRecaptcha v-if="configs['google-recaptcha-sitekey']" :sitekey="configs['google-recaptcha-sitekey']" @verify="verifyCaptcha" @expired="expiredCaptcha" @render="expiredCaptcha" class="g-recaptcha col-12"></VueRecaptcha>
                    <button class="col-12 button button--primary button--large button--md" type="submit"> <?= i::__('Receber instruções no e-mail') ?> </button>
                    <a @click="recoveryRequest = false" class="col-12 button button--secondarylight button--large button--md"> <?= i::__('Voltar') ?> </a>
                </form>
            </div>
        </div>

        <div class="login__card" v-if="recoveryEmailSent">
            <div class="login__card__content">
                <div class="grid-12">
                    <div class="col-12 header">
                        <label class="header__title"> <?= i::__('Alteração de senha') ?> </label>
                        <mc-icon name="circle-checked" class="header__icon"></mc-icon>
                        <label class="header__label"> <?= i::__('Enviamos as instruções de alteração de senha para seu e-mail.') ?> </label>
                    </div>

                    <button class="col-12 button button--primary button--large button--md" type="submit"> <?= i::__('Não recebi o e-mail') ?> </button>
                    <a @click="recoveryEmailSent = false" class="col-12 button button--secondarylight button--large button--md"> <?= i::__('Voltar') ?> </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recovery action -->
    <div v-if="recoveryMode" class="login__recovery--action">
        <div class="login__card">
            <div class="login__card__header">
                <h3> <?= i::__('Redefinir senha de acesso') ?> </h3>
            </div>

            <div class="login__card__content">
                <form class="grid-12" @submit.prevent="doRecover();">
                    <div class="field col-12 password">
                        <label for="pwd"> <?= i::__('Senha'); ?> </label>
                        <input autocomplete="off" id="pwd" type="password" name="password" v-model="password" />
                    </div>

                    <div class="field col-12 password">
                        <label for="pwd"> <?= i::__('Confirme sua nova senha'); ?> </label>
                        <input autocomplete="off" id="pwd" type="password" name="confirmPassword" v-model="confirmPassword" />
                    </div>

                    <div class="col-12">
                        <password-strongness :password="password"></password-strongness>
                    </div>

                    <button class="col-12 button button--primary button--large button--md" type="submit"> <?= i::__('Redefinir senha') ?> </button>
                </form>
            </div>
        </div>
    </div>
</div>