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

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;

class CleanupTest extends AppTestCase
{
    use CleanupTrait;

    public function testCleanupSecretsSoftDeletedResourcesSuccess()
    {
        $originalCount = SecretFactory::count();
        $softDeletedResource = ResourceFactory::make()->setDeleted()->persist();
        $user = UserFactory::make()->persist();
        SecretFactory::make(['resource_id' => $softDeletedResource->id, 'user_id' => $user->id])
            ->withSecretRevision()
            ->persist();

        $this->runCleanupChecks('Secrets', 'cleanupSoftDeletedResources', $originalCount);
    }

    public function testCleanupSecretsHardDeletedResourcesSuccess()
    {
        $originalCount = SecretFactory::count();
        $user = UserFactory::make()->persist();
        // Resource hard deleted therefore does not exist and we use random uuid
        SecretFactory::make([
            'resource_id' => UuidFactory::uuid('resource.id.nope'), 'user_id' => $user->id])
            ->withSecretRevision()
            ->persist();
        $this->runCleanupChecks('Secrets', 'cleanupHardDeletedResources', $originalCount);
    }

    public function testCleanupSecretsSoftDeletedUsersSuccess()
    {
        $originalCount = SecretFactory::count();
        $resource = ResourceFactory::make()->persist();
        $softDeletedUser = UserFactory::make()->deleted()->persist();
        SecretFactory::make(['resource_id' => $resource->id, 'user_id' => $softDeletedUser->id])
            ->withSecretRevision()
            ->persist();
        $this->runCleanupChecks('Secrets', 'cleanupSoftDeletedUsers', $originalCount);
    }

    public function testCleanupSecretsHardDeletedUsersSuccess()
    {
        $originalCount = SecretFactory::count();
        $resource = ResourceFactory::make()->persist();
        SecretFactory::make([
            'resource_id' => $resource->id, 'user_id' => UuidFactory::uuid('user.id.nope')])
            ->withSecretRevision()
            ->persist();
        $this->runCleanupChecks('Secrets', 'cleanupHardDeletedUsers', $originalCount);
    }

    public function testCleanupSecretsHardDeletedPermissionsSuccess()
    {
        $originalCount = SecretFactory::count();
        $resource = ResourceFactory::make()->persist();
        $user = UserFactory::make()->persist();
        SecretFactory::make(['resource_id' => $resource->id, 'user_id' => $user->id])
            ->withSecretRevision()
            ->persist();
        $this->runCleanupChecks('Secrets', 'cleanupHardDeletedPermissions', $originalCount);
    }
}
