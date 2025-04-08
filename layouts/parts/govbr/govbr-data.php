<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 */

use MapasCulturais\i;

$this->import('
    login-govbr
    seal-govbr
');
?>

<?php if (!$current_user->is('guest') && ($this->controller->id === 'panel' || $this->controller->requestedEntity->id == $current_user->profile->id)):
    if ($has_govbr_seal): ?>
        <seal-govbr></seal-govbr>
    <?php else: ?>
        <?php $configs = json_encode($app->config['auth.config']); ?>
        <login-govbr config='<?= $configs; ?>' :binding='true' small></login-govbr>
    <?php endif;
endif; ?>
