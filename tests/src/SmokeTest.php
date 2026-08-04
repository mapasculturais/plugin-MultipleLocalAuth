<?php

namespace Tests\MultipleLocalAuth;

use MultipleLocalAuth\Plugin;
use MultipleLocalAuth\Provider;
use Tests\Abstract\TestCase;
use Tests\Traits\UserDirector;

/**
 * Verifica que a suíte carrega o core com este plugin como provedor de autenticação.
 *
 * É o teste de fundação: se ele falhar, nenhum outro teste do plugin é confiável.
 */
class SmokeTest extends TestCase
{
    use UserDirector;

    function testPluginEstaAtivo()
    {
        $this->assertArrayHasKey('MultipleLocalAuth', $this->app->plugins);
        $this->assertInstanceOf(Plugin::class, $this->app->plugins['MultipleLocalAuth']);
    }

    function testProvedorDeAutenticacaoEODoPlugin()
    {
        $this->assertInstanceOf(Provider::class, $this->app->auth);
    }

    function testValidacaoDeContaPorEmailEstaExigida()
    {
        $this->assertTrue($this->app->config['auth.config']['userMustConfirmEmailToUseTheSystem']);
    }

    function testAutenticacaoDaSuiteFuncionaComOProvedorDoPlugin()
    {
        $user = $this->userDirector->createUser();

        $this->login($user);

        $this->assertSame($user->id, $this->app->user->id);
    }
}
