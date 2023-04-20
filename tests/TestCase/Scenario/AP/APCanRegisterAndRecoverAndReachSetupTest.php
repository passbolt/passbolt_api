<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.5.0
 */
namespace App\Test\TestCase\Scenario\AP;

use App\Model\Entity\AuthenticationToken;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class APCanRegisterAndRecoverAndReachSetupTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;
    use SelfRegistrationTestTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/Permissions', 'app.Base/Favorites',
        'app.Base/Gpgkeys',
    ];

    /**
     * @var UsersTable
     */
    protected $Users;

    /**
     * @var AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    public function setUp(): void
    {
        // The setup/recover requires a supported user agent.
        $_ENV['HTTP_USER_AGENT'] = 'Firefox';
        parent::setUp();
    }

    public function tearDown(): void
    {
        $_ENV['HTTP_USER_AGENT'] = null;
        parent::tearDown();
    }

    /**
     * Given that I am an anonymous user
     * When I register
     * Then I get an email with the registration token
     * When I can request a new token using a recover
     * Then I can complete the setup
     * And I cannot start the setup again
     */
    public function testAPCanRegisterAndRecoverAndReachSetup()
    {
        $this->setSelfRegistrationSettingsData();
        // Register using signup form
        $email = 'integration@passbolt.com';
        $data = ['username' => $email, 'profile' => ['first_name' => 'integration', 'last_name' => 'test']];
        $this->postJson('/users/register.json', $data);
        $this->assertResponseSuccess();

        // Get and check user
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $user = $this->Users->findByUsername($email)->first();
        $this->assertFalse($user->active);

        // There should be one valid auth tokens
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $tokens = $this->AuthenticationTokens
            ->findByUserId($user->id)->order(['created' => 'DESC'])
            ->all()->toArray();
        $this->assertEquals($tokens[0]['type'], AuthenticationToken::TYPE_REGISTER);

        // Link to install should be present in email
        $url = Router::url('/setup/install/' . $user->id . '/' . $tokens[0]['token']);
        $this->assertEmailInBatchContains($url, 'integration@passbolt.com');

        // Recover to get another token
        $this->post('/users/recover.json', ['username' => $email]);
        $this->assertResponseSuccess();

        // There should be two valid auth tokens
        $tokens = $this->AuthenticationTokens
            ->findByUserId($user->id)->order(['created' => 'DESC'])
            ->all()->toArray();
        $this->assertEquals(count($tokens), 2);
        $this->assertEquals($tokens[0]['type'], AuthenticationToken::TYPE_REGISTER);
        $this->assertEquals($tokens[1]['type'], AuthenticationToken::TYPE_REGISTER);

        // Setup start should work
        $this->get($url);
        $this->assertResponseCode(200);

        // Setup complete should work
        $url = '/setup/complete/' . $user->id . '.json?api-version=v2';
        $this->postJson($url, [
            'authenticationtoken' => ['token' => $tokens[0]['token']],
            'gpgkey' => ['armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key')],
        ]);
        $this->assertSuccess();

        // Try to start setup with other token
        // Url should not work since user is already signed up
        $url = Router::url('/setup/install/' . $user->id . '/' . $tokens[1]['token'] . '.json');
        $this->getJson($url);
        $this->assertResponseCode(400);
    }
}
