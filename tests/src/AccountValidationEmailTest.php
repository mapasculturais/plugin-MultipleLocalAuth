<?php

namespace Tests\MultipleLocalAuth;

use MapasCulturais\Entities\User;
use MultipleLocalAuth\Provider;
use Tests\Abstract\TestCase;
use Tests\Mailer\TestTransport;
use Tests\MultipleLocalAuth\Doubles\TestableProvider;
use Tests\Traits\UserDirector;

/**
 * Cobre o contrato de validação de conta implementado por este provedor e o e-mail
 * que ele envia.
 */
class AccountValidationEmailTest extends TestCase
{
    use UserDirector;

    protected function setUp(): void
    {
        parent::setUp();

        TestTransport::reset();

        // App::reset() não limpa hooks; sem o clear, cada teste acumularia um listener.
        $this->app->clearHooks('mailer.transport');
        $this->app->hook('mailer.transport', function (&$transport) {
            $transport = new TestTransport();
        });
    }

    protected function tearDown(): void
    {
        TestTransport::reset();

        parent::tearDown();
    }

    private function setAccountIsActive(User $user, string $value): void
    {
        $this->app->disableAccessControl();
        $user->setMetadata(Provider::$accountIsActiveMetadata, $value);
        $user->saveMetadata(true);
        $this->app->enableAccessControl();
    }

    /**
     * O corpo vai como HTML (App::createMailMessage converte 'body' em html()), e
     * getHtmlBody() devolve o conteúdo original — toString() traria quoted-printable,
     * com o '=' virando '=3D' e quebras de linha no meio da URL.
     */
    private function lastEmailBody(): string
    {
        return TestTransport::getLastMessage()->getOriginalMessage()->getHtmlBody();
    }

    function testProvedorSuportaValidacaoDeContaQuandoAConfirmacaoEExigida()
    {
        $this->assertTrue($this->app->auth->supportsAccountValidation());
    }

    function testProvedorNaoSuportaValidacaoDeContaQuandoAConfirmacaoNaoEExigida()
    {
        $provider = new TestableProvider(['userMustConfirmEmailToUseTheSystem' => false]);

        $this->assertFalse($provider->supportsAccountValidation());
    }

    /**
     * O default da chave é false, então uma instalação que não a configura não
     * oferece o reenvio.
     */
    function testProvedorNaoSuportaValidacaoDeContaSemAConfiguracao()
    {
        $provider = new TestableProvider([]);

        $this->assertFalse($provider->supportsAccountValidation());
    }

    function testContaComMetadadoZeroNaoEstaValidada()
    {
        $user = $this->userDirector->createUser();
        $this->setAccountIsActive($user, '0');

        $this->assertFalse($this->app->auth->isAccountValidated($user));
    }

    function testContaComMetadadoUmEstaValidada()
    {
        $user = $this->userDirector->createUser();
        $this->setAccountIsActive($user, '1');

        $this->assertTrue($this->app->auth->isAccountValidated($user));
    }

    /**
     * Usuários anteriores à validação por e-mail não têm o metadado e não são
     * bloqueados no login, então não podem ser tratados como pendentes.
     */
    function testContaSemOMetadadoEstaValidada()
    {
        $user = $this->userDirector->createUser();

        $this->assertTrue($this->app->auth->isAccountValidated($user));
    }

    function testReenvioEnviaEmailParaOEnderecoDoUsuario()
    {
        $user = $this->userDirector->createUser();
        $this->setAccountIsActive($user, '0');

        $this->assertTrue($this->app->auth->resendAccountValidationEmail($user));
        $this->assertSame(1, TestTransport::getMessagesCount());

        $message = TestTransport::getLastMessage()->getOriginalMessage();

        $this->assertSame($user->email, $message->getTo()[0]->getAddress());
        $this->assertStringContainsString('Bem-vindo ao', $message->getSubject());
    }

    function testReenvioUsaOTokenJaExistente()
    {
        $user = $this->userDirector->createUser();
        $this->setAccountIsActive($user, '0');

        $this->app->disableAccessControl();
        $user->setMetadata(Provider::$tokenVerifyAccountMetadata, 'token-existente');
        $user->saveMetadata(true);
        $this->app->enableAccessControl();

        $this->app->auth->resendAccountValidationEmail($user);

        $this->assertSame('token-existente', $user->getMetadata(Provider::$tokenVerifyAccountMetadata));
        $this->assertStringContainsString('confirma-email?token=token-existente', $this->lastEmailBody());
    }

    function testReenvioGeraEPersisteTokenQuandoOUsuarioNaoTemNenhum()
    {
        $user = $this->userDirector->createUser();
        $this->setAccountIsActive($user, '0');

        $this->assertNull($user->getMetadata(Provider::$tokenVerifyAccountMetadata));

        $this->app->auth->resendAccountValidationEmail($user);

        $token = $user->getMetadata(Provider::$tokenVerifyAccountMetadata);

        $this->assertNotEmpty($token);
        $this->assertStringContainsString('confirma-email?token=' . $token, $this->lastEmailBody());
    }

    /**
     * O e-mail é apenas esvaziado em memória: a coluna usr.email é NOT NULL, e
     * persistir o estado inválido fecharia o EntityManager para os demais testes.
     */
    function testReenvioNaoEnviaNadaSemEnderecoDeEmail()
    {
        $user = $this->userDirector->createUser();
        $user->email = '';

        $this->assertFalse($this->app->auth->resendAccountValidationEmail($user));
        $this->assertSame(0, TestTransport::getMessagesCount());
    }

    function testEmailDeValidacaoIdentificaOUsuarioPeloNomeDoPerfil()
    {
        $user = $this->userDirector->createUser();
        $this->setAccountIsActive($user, '0');

        $this->app->auth->resendAccountValidationEmail($user);

        $this->assertStringContainsString($user->profile->name, $this->lastEmailBody());
    }
}
