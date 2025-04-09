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

$button_class = $button_class ?? '';
$seal_class = $seal_class ?? '';
?>

<?php if ($has_govbr_seal): ?>
    <seal-govbr class="<?= $seal_class ?>"></seal-govbr>
<?php else: ?>
    <?php $configs = json_encode($app->config['auth.config']); ?>
    <login-govbr class="<?= $button_class ?>" config='<?= $configs; ?>' binding small></login-govbr>
<?php endif; ?>
