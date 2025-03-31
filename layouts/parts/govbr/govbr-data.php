<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 *
 */

use MapasCulturais\i;

$current_user = $app->user;
$configs = json_encode($app->config['auth.config']);
$seal = $app->repo('Seal')->find($app->config['auth.config']['strategies']['govbr']['applySealId']);
$seal_relation = $app->repo('SealRelation')->findOneBy(['seal' => $seal, 'agent' => $current_user->profile->id]);
$has_govbr_seal = $seal_relation ? true : false;

$this->import('
    login-govbr
');
?>

<?php if (!$current_user->is('guest') && $this->controller->requestedEntity->id == $current_user->profile->id):
    if ($has_govbr_seal): ?>
        <div class="user-mail__config col-12" style="width: max-content">
            <h4 class="user-mail__config-title bold"><?= i::__('gov.br') ?></h4>
            <div class="user-mail__config-content">
                <mc-icon name="check" class="success__color"></mc-icon>
                <span><?= i::__('Usuário vinculado ao gov.br') ?></span>
            </div>
        </div>
    <?php else: ?>
        <login-govbr config='<?= $configs; ?>' :binding='true' small></login-govbr>
    <?php endif;
endif; ?>