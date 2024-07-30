<?php

use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();

$configs = json_encode($config);

if (trim($_GET['t'] ?? '')) {
    $this->jsObject['recoveryMode']['status'] = true;
    $this->jsObject['recoveryMode']['token'] = $_GET['t']; 
}

$flag = 'login';

$templates = [
    'login' => function () use ($configs) {
        return "<login config='$configs'></login>";
    },

    'login-wizard' => function () use ($configs) {
        return "<login-wizard config='$configs'></login-wizard>";
    }
];

if ($config['wizard'] == true){
    $flag = 'login-wizard';
}

$this->import($flag);

echo $templates[$flag]();
?>

