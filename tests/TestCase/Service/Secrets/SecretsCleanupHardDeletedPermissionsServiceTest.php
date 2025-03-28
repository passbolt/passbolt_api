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
 * @since         4.2.0
 */

namespace App\Test\TestCase\Service\Secrets;

use App\Service\Secrets\SecretsCleanupHardDeletedPermissionsService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class SecretsCleanupHardDeletedPermissionsServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public SecretsCleanupHardDeletedPermissionsService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SecretsCleanupHardDeletedPermissionsService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSecretsCleanupHardDeletedPermissionsService_Delete()
    {
        [$userWithDirectPermission, $userWithGroupPermission] = UserFactory::make(2)->persist();
        $group = GroupFactory::make()->withGroupsUsersFor([$userWithGroupPermission])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userWithDirectPermission, $group])
            ->withSecretsFor([$userWithDirectPermission, $userWithGroupPermission])->persist();

        // Insert some random secrets to be deleted
        $nSecretsToDelete = 5;
        $nPermissions = 5;
        SecretFactory::make($nSecretsToDelete)->persist();
        PermissionFactory::make($nPermissions)->persist();

        $secretsToDeleteCount = $this->service->cleanupHardDeletedPermissions();

        $this->assertSame($nSecretsToDelete, $secretsToDeleteCount);
        $this->assertSame(2, SecretFactory::count());
        $this->assertSame(2, SecretFactory::find()->where(['resource_id' => $resource->get('id')])->all()->count());
    }

    public function testSecretsCleanupHardDeletedPermissionsService_Dry_Run()
    {
        [$userWithDirectPermission, $userWithGroupPermission] = UserFactory::make(2)->persist();
        $group = GroupFactory::make()->withGroupsUsersFor([$userWithGroupPermission])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userWithDirectPermission, $group])
            ->withSecretsFor([$userWithDirectPermission, $userWithGroupPermission])->persist();

        // Insert some random secrets to be deleted
        $nSecretsToDelete = 5;
        SecretFactory::make($nSecretsToDelete)->persist();

        $secretsToDeleteCount = $this->service->cleanupHardDeletedPermissions(true);

        $this->assertSame($nSecretsToDelete, $secretsToDeleteCount);
        $this->assertSame(2 + $nSecretsToDelete, SecretFactory::count());
        $this->assertSame(2, SecretFactory::find()->where(['resource_id' => $resource->get('id')])->all()->count());
    }
}
