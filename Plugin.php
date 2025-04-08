<?php
namespace MultipleLocalAuth;

use MapasCulturais\App;
use MapasCulturais\i;

include('Facebook/FacebookStrategy.php');
include('Google/GoogleStrategy.php');
include('LinkedIn/LinkedInStrategy.php');
include('LoginCidadao/LoginCidadaoStrategy.php');
include('GovBr/GovBrStrategy.php');

class Plugin extends \MapasCulturais\Plugin {

    public function _init() {
        $app = App::i();
        $plugin = $this;

        // register translation text domain
        i::load_textdomain( 'multipleLocal', __DIR__ . "/translations" );

        // Load JS & CSS
        $app->hook('GET(<<*>>.<<*>>):before', function() use ($app) {
            $app->view->enqueueStyle('app-v2', 'multipleLocal-v2', 'css/plugin-MultiplLocalAuth.css');
        });

        $app->hook('GET(auth.<<index|register>>)', function() use($app) {
            if (env('GOOGLE_RECAPTCHA_SITEKEY', false)) {
                $app->view->enqueueScript('app-v2', 'multipleLocal-v2', 'https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit');
            }
        });

        $app->hook('template(panel.<<my-account|user-detail>>.user-mail):end ', function() {
            /** @var \MapasCulturais\Theme $this */
            $this->part('password/change-password');
        });

        $app->hook('template(panel.<<my-account|user-detail>>.user-mail):end ', function() use ($app, $plugin) {
            /** @var \MapasCulturais\Theme $this */
            if ($app->config['auth.config']['strategies']['govbr']['visible']) {
                $current_user = $app->user;
                $has_govbr_seal = $plugin->hasGovBrSeal($current_user);
                $this->part('govbr/govbr-data', [
                    'current_user' => $current_user,
                    'has_govbr_seal' => $has_govbr_seal,
                ]);
            }
        });

        $app->hook('template(agent.single.single1-entity-info-mc-share-links):before', function() use ($app, $plugin) {
            /** @var \MapasCulturais\Theme $this */
            if ($app->config['auth.config']['strategies']['govbr']['visible']) {
                $current_user = $app->user;
                $has_govbr_seal = $plugin->hasGovBrSeal($current_user);
                $this->part('govbr/govbr-data', [
                    'current_user' => $current_user,
                    'has_govbr_seal' => $has_govbr_seal,
                ]);
            }
        });

        $app->hook('template(agent.edit.edit1-entity-info-site):after', function() use ($app, $plugin) {
            /** @var \MapasCulturais\Theme $this */
            if ($app->config['auth.config']['strategies']['govbr']['visible']) {
                $current_user = $app->user;
                $has_govbr_seal = $plugin->hasGovBrSeal($current_user);
                $this->part('govbr/govbr-data', [
                    'current_user' => $current_user,
                    'has_govbr_seal' => $has_govbr_seal,
                ]);            }
        });

        $app->hook('entity(User).permissionsList,doctrine.emum(permission_action).values', function (&$permissions) {
            $permissions[] = 'changePassword';
        });

        $app->hook('module(UserManagement).permissionsLabels', function(&$labels) {
            $labels['changePassword'] = i::__('modificar senha');
        });

        $app->hook("controller(seal).render(sealrelation)", function(&$template, $seal) use ($app) {
            $govbr_seal = $app->config['auth.config']['strategies']['govbr']['applySealId'];

            if ($seal['relation']->seal->id == $govbr_seal) {
                $template = "certificado-govbr";
            }
        });

        // Carrega novos ícones 'iconfy' na estrutura default
        $app->hook('component(mc-icon).iconset', function(&$iconset){
            $iconset['cursor-click'] = "mynaui:click-solid";
        });

        $app->hook('template(<<*>>.main-header):after', function() use ($app) {
            $is_admin = $app->user->is('admin');
            $govbr_visible = $app->config['auth.config']['strategies']['govbr']['visible'];
            $has_seen_modal = !$app->user->is('guest') ? $app->user->profile->hasSeenSocialLinkingModal : false;
            $govbr_seal = $app->config['auth.config']['strategies']['govbr']['applySealId'];
            $seal_relation = !$app->user->is('guest') && $govbr_seal ? $app->repo('SealRelation')->findOneBy(['seal' => $govbr_seal, 'agent' => $app->user->profile->id]) : false;

            if ($govbr_visible && !$app->user->is('guest') && !$has_seen_modal && !$seal_relation && !$is_admin) {
                $this->part('govbr/govbr-modal');
            }
        });

        $app->hook('POST(site.desabilitar-modal)', function() use($app) {
            $this->requireAuthentication();

            $agent_id = $this->data['agentId'];
            $agent = $app->repo('Agent')->find($agent_id);

            if($agent) {
                $agent->hasSeenSocialLinkingModal = true;
                $agent->save(true);

                $this->json(true);
            }

            $this->errorJson(false);
        });

        if (php_sapi_name() == "cli") {
            if (!isset($_SERVER['HTTP_HOST'])) {
                $_SERVER['HTTP_HOST'] = 'localhost';
            }
        }
    }

    public function register() {
        $this->registerUserMetadata(Provider::$passMetaName, ['label' => i::__('Senha')]);
        $this->registerUserMetadata(Provider::$recoverTokenMetadata, ['label' => i::__('Token para recuperação de senha')]);
        $this->registerUserMetadata(Provider::$recoverTokenTimeMetadata, ['label' => i::__('Timestamp do token para recuperação de senha')]);
        $this->registerUserMetadata(Provider::$accountIsActiveMetadata, ['label' => i::__('Conta ativa?')]);
        $this->registerUserMetadata(Provider::$tokenVerifyAccountMetadata, ['label' => i::__('Token de verificação')]);
        $this->registerUserMetadata(Provider::$loginAttempMetadata, ['label' => i::__('Número de tentativas de login')]);
        $this->registerUserMetadata(Provider::$timeBlockedloginAttempMetadata, ['label' => i::__('Tempo de bloqueio por excesso de tentativas')]);
        $this->registerAgentMetadata('hasSeenSocialLinkingModal', [
            'label' => i::__('Indica se o usuário já viu o modal de vinculação com redes sociais'),
            'type' => 'boolean',
            'default' => false
        ]);
    }

    public function hasGovBrSeal($user) {
        $app = App::i();
        $seal = $app->repo('Seal')->find($app->config['auth.config']['strategies']['govbr']['applySealId']);
        $seal_relation = $app->repo('SealRelation')->findOneBy(['seal' => $seal, 'agent' => $user->profile->id]);
        return !empty($seal_relation);
    }
}
