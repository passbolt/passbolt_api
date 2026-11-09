<?php
declare(strict_types=1);

namespace Passbolt\BrowserAuth\Test\TestCase\Controller;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\TableRegistry;

class BrowserAuthControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin('browserAuth');
        $this->loadRoutes();
        $this->configRequest(['environment' => ['HTTPS' => 'on']]);
    }

    public function testAnonymousCannotAuthorize(): void
    {
        $this->postJson('/browser-auth/authorize.json', []);
        $this->assertAuthenticationError();
    }

    public function testAuthorizeRejectsWrongAccountKey(): void
    {
        $this->logInAs(UserFactory::make()->user()->withValidGpgKey()->persist());
        $this->postJson('/browser-auth/authorize.json', [
            'challenge' => str_repeat('a', 43), 'fingerprint' => str_repeat('A', 40),
        ]);
        $this->assertError(403);
    }

    public function testAuthorizeCreatesBoundGrantWithoutCredentialsInResponse(): void
    {
        $user = UserFactory::make()->user()->withValidGpgKey()->persist();
        $this->logInAs($user);
        $key = TableRegistry::getTableLocator()->get('Gpgkeys')->find()->where(['user_id' => $user->id])->firstOrFail();
        $this->postJson('/browser-auth/authorize.json', [
            'challenge' => str_repeat('a', 43), 'fingerprint' => strtoupper($key->fingerprint),
        ]);
        $this->assertSuccess();
        $body = (array)$this->_responseJsonBody;
        $this->assertSame(['token', 'user_id'], array_keys($body));
        $this->assertSame($user->id, $body['user_id']);
    }

    public function testExchangeRejectsMalformedVerifier(): void
    {
        $this->postJson('/browser-auth/exchange.json', ['verifier' => 'short']);
        $this->assertError(400);
    }
}
