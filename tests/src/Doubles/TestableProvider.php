<?php

namespace Tests\MultipleLocalAuth\Doubles;

use MultipleLocalAuth\Provider;

/**
 * Provider instanciável com configuração arbitrária.
 *
 * O `_init()` real registra cerca de vinte hooks de rota no App, e o construtor de
 * MapasCulturais\AuthProvider o chama direto — instanciar o Provider real num teste
 * acumularia esses hooks, já que a suíte roda sem isolamento de processo. Este double
 * anula apenas o registro de hooks; toda a lógica sob teste é a da classe original.
 */
class TestableProvider extends Provider
{
    protected function _init() {}
}
