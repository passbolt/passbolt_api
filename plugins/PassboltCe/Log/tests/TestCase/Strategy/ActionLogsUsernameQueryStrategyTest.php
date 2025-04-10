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
 * @since         5.0.0
 */

namespace Passbolt\Log\Test\TestCase\Strategy;

use App\Model\Entity\Permission;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\Log\Formatter\JsonFormatter;
use Cake\Log\Log;
use Cake\Utility\Hash;
use Passbolt\Log\Strategy\ActionLogsUsernameQueryStrategy;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * Class ActionLogsUsernameQueryStrategyTest
 *
 * @covers \Passbolt\Log\Strategy\ActionLogsUsernameQueryStrategy
 */
class ActionLogsUsernameQueryStrategyTest extends LogIntegrationTestCase
{
    /**
     * @var string
     */
    protected string $fileName;

    public function setUp(): void
    {
        parent::setUp();
        $config = Log::getConfig('actionLogsOnFile');
        $this->fileName = 'testActionLogsUsernameQueryStrategy_' . rand(1, 1000);
        Log::drop('actionLogsOnFile');
        $config['enabled'] = true;
        $config['path'] = TMP . 'tests' . DS . 'logs' . DS;
        $config['file'] = $this->fileName;
        $config['strategy'] = ActionLogsUsernameQueryStrategy::class;
        $config['formatter'] = JsonFormatter::class;
        Log::setConfig('actionLogsOnFile', $config);
        Configure::write('passbolt.v5.enabled', true);
    }

    public function tearDown(): void
    {
        if (file_exists($this->getLogFilePath())) {
            unlink($this->getLogFilePath());
        }
        unset($this->fileName);
        parent::tearDown();
    }

    protected function getLogFilePath(): string
    {
        return Log::getConfig('actionLogsOnFile')['path'] . $this->fileName . '.log';
    }

    public function testActionLogsUsernameQueryStrategy_View_V4_Resource()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->withSecretsFor([$user])
            ->persist();
        $this->getJson("/resources/$resource->id.json?contain[secret]=1");
        $this->assertSuccess();

        $logs = json_decode(file_get_contents($this->getLogFilePath()), true);
        $message = json_decode($logs['message'], true);
        $this->assertSame($user->username, $message['user']);
        $this->assertSame('password_access', $message['action']);
        $context = $user->profile->full_name . " ($user->username) accessed password";
        $this->assertSame($context, $message['context']);
        $this->assertSame(1, $message['status']);
        $this->assertSame($resource->id, $message['resource_id']);
        $this->assertSame($resource->name, $message['resource_name']);
        $this->assertSame($resource->username, $message['resource_username']);
        $this->assertSame($resource->uri, $message['resource_uri']);
    }

    public function testActionLogsUsernameQueryStrategy_View_V5_Resource()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->v5Fields()
            ->withPermissionsFor([$user])
            ->withSecretsFor([$user])
            ->persist();
        $this->getJson("/resources/$resource->id.json?contain[secret]=1");
        $this->assertSuccess();

        $logs = json_decode(file_get_contents($this->getLogFilePath()), true);
        $message = json_decode($logs['message'], true);
        $this->assertSame($user->username, $message['user']);
        $this->assertSame('password_access', $message['action']);
        $context = $user->profile->full_name . " ($user->username) accessed password";
        $this->assertSame($context, $message['context']);
        $this->assertSame(1, $message['status']);
        $this->assertSame($resource->id, $message['resource_id']);
        $this->assertSame($resource->name, $message['resource_name']);
        $this->assertSame($resource->username, $message['resource_username']);
        $this->assertSame($resource->uri, $message['resource_uri']);
    }

    public function testActionLogsUsernameQueryStrategy_Share_V4_Resource()
    {
        [$user, $edith] = UserFactory::make(2)->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $resourceId = $resource->id;
        // Add an owner permission for the user Edith
        $data = [
            'permissions' => [
                ['aro' => 'User', 'aro_foreign_key' => $edith->id, 'type' => Permission::OWNER],
            ],
            'secrets' => [
                ['user_id' => $edith->id, 'data' => Hash::get(self::getDummySecretData(), 'data')],
            ],
        ];
        $this->logInAs($user);

        $this->putJson("/share/resource/$resourceId.json", $data);

        $this->assertSuccess();

        $logs = json_decode(file_get_contents($this->getLogFilePath()), true);
        $message = json_decode($logs['message'], true);
        $this->assertSame($user->username, $message['user']);
        $this->assertSame('share', $message['action']);
        $context = ' shared password with: ' . $edith->username . " ({$edith->profile->full_name}) OWNER";
        $this->assertSame($context, $message['context']);
        $this->assertSame(1, $message['status']);
        $this->assertSame($resource->id, $message['resource_id']);
        $this->assertSame($resource->name, $message['resource_name']);
    }

    public function testActionLogsUsernameQueryStrategy_Share_V5_Resource()
    {
        [$user, $edith] = UserFactory::make(2)->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->v5Fields(true)
            ->withCreatorAndPermission($user)
            ->with('ResourceTypes', ResourceTypeFactory::make()->v5Default())
            ->persist();
        $resourceId = $resource->id;
        // Add an owner permission for the user Edith
        $data = [
            'permissions' => [
                ['aro' => 'User', 'aro_foreign_key' => $edith->id, 'type' => Permission::OWNER],
            ],
            'secrets' => [
                ['user_id' => $edith->id, 'data' => Hash::get(self::getDummySecretData(), 'data')],
            ],
        ];
        $this->logInAs($user);

        $this->putJson("/share/resource/$resourceId.json", $data);

        $this->assertSuccess();

        $logs = json_decode(file_get_contents($this->getLogFilePath()), true);
        $message = json_decode($logs['message'], true);
        $this->assertSame($user->username, $message['user']);
        $this->assertSame('share', $message['action']);
        $context = ' shared password with: ' . $edith->username . " ({$edith->profile->full_name}) OWNER";
        $this->assertSame($context, $message['context']);
        $this->assertSame(1, $message['status']);
        $this->assertSame($resource->id, $message['resource_id']);
        $this->assertSame($resource->name, $message['resource_name']);
    }
}
