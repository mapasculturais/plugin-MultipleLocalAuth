<?php

// Restaura este plugin como provedor de autenticação da suíte, sobrescrevendo o
// 'auth.provider' => 'Test' que o core define em tests/config.d/auth.php. Sem isso,
// $app->auth não é a instância de MultipleLocalAuth\Provider e nada do plugin é exercitado.
return [
    'auth.provider' => '\MultipleLocalAuth\Provider',

    // O construtor do Provider usa `$config += [...]`, então as chaves definidas aqui
    // preservam seus valores e as demais recebem os defaults.
    'auth.config' => [
        'salt' => 'multiplelocalauth-tests',

        // Sem estratégias, usingSocialLogin() retorna false e o Opauth não é instanciado.
        // O construtor do Opauth lê $_SERVER['HTTP_HOST'] e $_SERVER['REQUEST_URI'] sem
        // guard, que não existem sob CLI e poluiriam o bootstrap do PHPUnit com warnings.
        'strategies' => [],

        // Exige validação de conta por e-mail, que é o comportamento sob teste.
        'userMustConfirmEmailToUseTheSystem' => true,
    ],
];
