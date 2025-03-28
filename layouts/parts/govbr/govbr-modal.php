<?php
/**
 * @var \MapasCulturais\Themes\BaseV2\Theme $this
 * @var \MapasCulturais\App $app
 * 
 */

use MapasCulturais\i;

$configs = json_encode($app->config['auth.config']);

$this->import('
    link-govbr-modal
');
?>

<div>
    <link-govbr-modal config='<?= $configs; ?>' ></link-govbr-modal>
</div>