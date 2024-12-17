<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$configs = json_encode($config);

if (trim($_GET['t'] ?? '')) {
    $this->jsObject['recoveryMode']['status'] = true;
    $this->jsObject['recoveryMode']['token'] = $_GET['t']; 
}

$loginMode = 'login';
if (isset($config['wizard'])) {
    if ($config['wizard'] == 'true') {
        $loginMode = 'login-wizard';
    }
}

$this->import($loginMode);

echo "<$loginMode config='$configs'></$loginMode>"

?>
