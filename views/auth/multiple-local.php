<?php

use MapasCulturais\App;

$app = App::i();

$configs = json_encode($config);

if (trim($_GET['t'] ?? '')) {
    $this->jsObject['recoveryMode']['status'] = true;
    $this->jsObject['recoveryMode']['token'] = $_GET['t']; 
}

$this->jsObject['login'] = [
    'wizard' => $config['wizard'] ?? false,
    'hasLocalAuth' => $hasLocalAuth ?? false,
];

$this->import('login');

echo "<login config='$configs'></login>"

?>
