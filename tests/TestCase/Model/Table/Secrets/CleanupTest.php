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

namespace App\Test\TestCase\Model\Table\Secrets;

use App\Test\Factory\SecretFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;

class CleanupTest extends AppTestCase
{
    use CleanupTrait;

    public array $fixtures = [
        'app.Base/Users', 'app.Base/GroupsUsers', 'app.Base/Groups', 'app.Base/Permissions',
        'app.Base/Resources', 'app.Base/Secrets',
    ];
    public $options;

    public function setUp(): void
    {
        parent::setUp();
        $this->options = ['accessibleFields' => [
           'resource_id' => true,
           'user_id' => true,
           'data' => true,
        ]];
    }

    public function tearDown(): void
    {
        unset($this->Secrets);
        parent::tearDown();
    }

    public function testCleanupSecretsSoftDeletedResourcesSuccess()
    {
        $originalCount = SecretFactory::count();
        SecretFactory::make(self::getDummySecretData([
            'resource_id' => UuidFactory::uuid('resource.id.jquery'),
            'user_id' => UuidFactory::uuid('user.id.ada')]))
            ->withSecretRevision()
            ->persist();

        $this->runCleanupChecks('Secrets', 'cleanupSoftDeletedResources', $originalCount);
    }

    public function testCleanupSecretsHardDeletedResourcesSuccess()
    {
        $originalCount = SecretFactory::count();
        SecretFactory::make(self::getDummySecretData([
            'resource_id' => UuidFactory::uuid('resource.id.nope'),
            'user_id' => UuidFactory::uuid('user.id.ada')]))
            ->withSecretRevision()
            ->persist();
        $this->runCleanupChecks('Secrets', 'cleanupHardDeletedResources', $originalCount);
    }

    public function testCleanupSecretsSoftDeletedUsersSuccess()
    {
        $originalCount = SecretFactory::count();
        SecretFactory::make(self::getDummySecretData([
            'resource_id' => UuidFactory::uuid('resource.id.jquery'),
            'user_id' => UuidFactory::uuid('user.id.sofia')]))
            ->withSecretRevision()
            ->persist();
        $this->runCleanupChecks('Secrets', 'cleanupSoftDeletedUsers', $originalCount);
    }

    public function testCleanupSecretsHardDeletedUsersSuccess()
    {
        $originalCount = SecretFactory::count();
        SecretFactory::make(self::getDummySecretData([
            'resource_id' => UuidFactory::uuid('resource.id.jquery'),
            'user_id' => UuidFactory::uuid('user.id.nope')]))
            ->withSecretRevision()
            ->persist();
        $this->runCleanupChecks('Secrets', 'cleanupHardDeletedUsers', $originalCount);
    }

    public function testCleanupSecretsHardDeletedPermissionsSuccess()
    {
        $originalCount = SecretFactory::count();
        SecretFactory::make(self::getDummySecretData([
            'resource_id' => UuidFactory::uuid('resource.id.apache'),
            'user_id' => UuidFactory::uuid('user.id.frances')]))
            ->withSecretRevision()
            ->persist();
        $this->runCleanupChecks('Secrets', 'cleanupHardDeletedPermissions', $originalCount);
    }
}
