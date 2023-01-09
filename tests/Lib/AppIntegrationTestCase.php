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
 * @since         2.0.0
 */
namespace App\Test\Lib;

use App\Middleware\CsrfProtectionMiddleware;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\AvatarsModelTrait;
use App\Test\Lib\Model\GpgkeysModelTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Model\ProfilesModelTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Model\RolesModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Test\Lib\Model\UsersModelTrait;
use App\Test\Lib\Utility\ArrayTrait;
use App\Test\Lib\Utility\CookieTestTrait;
use App\Test\Lib\Utility\EntityTrait;
use App\Test\Lib\Utility\ErrorIntegrationTestTrait;
use App\Test\Lib\Utility\JsonRequestTrait;
use App\Test\Lib\Utility\LoginTestTrait;
use App\Test\Lib\Utility\ObjectTrait;
use App\Test\Lib\Utility\UserAgentTestTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UserAction;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\EmailDigest\Utility\Digest\DigestsPool;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

abstract class AppIntegrationTestCase extends TestCase
{
    use ArrayTrait;
    use AvatarsModelTrait;
    use EntityTrait;
    use ErrorIntegrationTestTrait;
    use FeaturePluginAwareTrait;
    use GpgkeysModelTrait;
    use IntegrationTestTrait;
    use JsonRequestTrait;
    use ObjectTrait;
    use PermissionsModelTrait;
    use ProfilesModelTrait;
    use ResourcesModelTrait;
    use RolesModelTrait;
    use ScenarioAwareTrait;
    use SecretsModelTrait;
    use TruncateDirtyTables;
    use UsersModelTrait;
    use LoginTestTrait;
    use UserAgentTestTrait;
    use CookieTestTrait;

    /**
     * Setup.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->cleanup();
        $this->enableCsrfToken();
        $this->loadRoutes();

        // Disable feature plugins listed in default.php
        $this->disableFeaturePlugin('Tags');
        $this->disableFeaturePlugin('MultiFactorAuthentication');
        $this->disableFeaturePlugin('Log');
//        $this->disableFeaturePlugin('Folders');
//        $this->disableFeaturePlugin('AccountRecovery');
//        $this->disableFeaturePlugin('Sso');

        Configure::write(CsrfProtectionMiddleware::PASSBOLT_SECURITY_CSRF_PROTECTION_ACTIVE_CONFIG, true);
        OpenPGPBackendFactory::reset();
        UserAction::destroy();
        DigestsPool::clearInstance();
        EmailNotificationSettings::flushCache();
    }

    /**
     * Tear down
     */
    public function tearDown(): void
    {
        $this->clearPlugins();
        parent::tearDown();
    }

    /**
     * Authenticate as a user.
     *
     * @param string $userFirstName The user first name.
     * @return void
     * @deprecated use logInAs.
     */
    public function authenticateAs($userFirstName)
    {
        $userId = UuidFactory::uuid('user.id.' . $userFirstName);
        $Users = TableRegistry::getTableLocator()->get('Users');
        $user = $Users->find()
            ->where(['Users.id' => $userId])
            ->contain(['Profiles', 'Roles'])
            ->first();

        if ($user === null) {
            $user = UserFactory::make([
                'id' => $userId,
                'username' => $userFirstName . '@passbolt.com',
                'profile' => [
                    'first_name' => $userFirstName,
                    'last_name' => 'testing',
                ],
            ]);
            if ($userFirstName === 'admin') {
                $user->admin();
            } else {
                $user->user();
            }

            $user = $user->persist();
        }

        $this->logInAs($user);
    }

    /**
     * Calling this method will remove the CSRF token from the request.
     *
     * @return void
     */
    public function disableCsrfToken()
    {
        $this->_csrfToken = false;
    }
}
