<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;

$configs = json_encode($app->config['auth.config']);

$this->import('
    login-govbr
');
?>

<div class="user-mail__config">
    <div class="user-mail__config-title">
        <?= i::__('Gov.br') ?> :
    </div>
    <div class="user-mail__config-content">
        <!-- TODO: Adicionar verificação se usuário já está vinculado com o gov.br -->
        <login-govbr config='<?= $configs; ?>' small></login-govbr>
        <b><mc-icon name="check"></mc-icon> Usuário vinculado ao Gov.br</b>
    </div>
</div>