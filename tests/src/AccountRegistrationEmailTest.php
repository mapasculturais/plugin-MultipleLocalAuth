<?php

namespace Tests\MultipleLocalAuth;

use MapasCulturais\App;
use MapasCulturais\Request;
use MultipleLocalAuth\Provider;
use Tests\Abstract\TestCase;
use Tests\Mailer\TestTransport;
use Tests\Traits\RequestFactory;

/**
 * Cobre o cadastro, que delega a geração do token e o envio do e-mail aos métodos
 * extraídos de dentro dele.
 *
 * Sem este teste, trocar os argumentos ou a ordem dessas chamadas passaria pela
 * suíte: os demais testes exercitam os métodos diretamente, nunca pelo cadastro.
 */
class AccountRegistrationEmailTest extends TestCase
{
    use RequestFactory;

    private ?Request $originalRequest = null;
    private array $originalPost = [];

    protected function setUp(): void
    {
        parent::setUp();

        TestTransport::reset();

        $this->app->clearHooks('mailer.transport');
        $this->app->hook('mailer.transport', function (&$transport) {
            $transport = new TestTransport();
        });

        // App::reset() não restaura a requisição, que precisa existir para o doRegister.
        $this->originalRequest = $this->app->request;
        $this->originalPost = $_POST;
    }

    protected function tearDown(): void
    {
        App::i()->request = $this->originalRequest;
        $_POST = $this->originalPost;

        TestTransport::reset();

        parent::tearDown();
    }

    private function register(string $email): array
    {
        $payload = [
            'name' => 'Cadastro de Teste',
            'email' => $email,
            'cpf' => '111.444.777-35',
            'password' => 'Senha@123',
            'confirm_password' => 'Senha@123',
        ];

        // Request::post() lê a superglobal $_POST, não o corpo da requisição PSR-7,
        // então é ela que precisa ser preenchida para exercitar o cadastro.
        $_POST = $payload;
        $this->app->request = $this->requestFactory->mapasPOST('auth', 'register', [], [], $payload);

        $result = $this->app->auth->doRegister();

        $this->assertTrue(
            $result['success'] ?? false,
            'Cadastro rejeitado: ' . json_encode($result['errors'] ?? [], JSON_UNESCAPED_UNICODE)
        );

        return $result;
    }

    private function lastEmailBody(): string
    {
        return TestTransport::getLastMessage()->getOriginalMessage()->getHtmlBody();
    }

    function testCadastroEnviaUmEmailDeValidacaoParaOEnderecoInformado()
    {
        $email = 'cadastro-' . uniqid() . '@example.test';

        $result = $this->register($email);

        $this->assertTrue($result['success']);
        $this->assertSame(1, TestTransport::getMessagesCount());

        $message = TestTransport::getLastMessage()->getOriginalMessage();

        $this->assertSame($email, $message->getTo()[0]->getAddress());
    }

    /**
     * Amarra as duas extrações: o token que vai no link tem que ser o mesmo que
     * ficou gravado no usuário, senão o link enviado não valida conta nenhuma.
     */
    function testTokenDoLinkEOMesmoQueFicaGravadoNoUsuario()
    {
        $email = 'cadastro-' . uniqid() . '@example.test';

        $this->register($email);

        $user = $this->app->repo('User')->findOneBy(['email' => $email]);
        $token = $user->getMetadata(Provider::$tokenVerifyAccountMetadata);

        $this->assertNotEmpty($token);
        $this->assertStringContainsString('confirma-email?token=' . $token, $this->lastEmailBody());
    }

    function testContaNasceAguardandoValidacao()
    {
        $email = 'cadastro-' . uniqid() . '@example.test';

        $this->register($email);

        $user = $this->app->repo('User')->findOneBy(['email' => $email]);

        $this->assertSame('0', $user->getMetadata(Provider::$accountIsActiveMetadata));
        $this->assertFalse($this->app->auth->isAccountValidated($user));
    }
}
