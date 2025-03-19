<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;

$configs = json_encode($app->config['auth.config']);
$seal = $app->repo('Seal')->find($app->config['auth.config']['strategies']['govbr']['applySealId']);
$seal_relation = $app->repo('SealRelation')->findOneBy(['seal' => $seal, 'agent' => $app->user->profile->id]);
$has_govbr_seal = $seal_relation ? true : false;


$this->import('
    login-govbr
');
?>

<div class="user-mail__config">
    <div class="user-mail__config-title">
        <?= i::__('Gov.br') ?> :
    </div>
    <div class="user-mail__config-content">
        <?php if ($has_govbr_seal): ?>
            <login-govbr config='<?= $configs; ?>' small></login-govbr>
        <?php else: ?>
            <b><mc-icon name="check"></mc-icon> Usuário vinculado ao Gov.br</b>
        <?php endif; ?>
    </div>
</div>