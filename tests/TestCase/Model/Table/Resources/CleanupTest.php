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
 * @since         5.17.0
 */

namespace App\Test\TestCase\Model\Table\Resources;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;

class CleanupTest extends AppTestCase
{
    public function testCleanupResources_cleanupMissingSecret_DryRun(): void
    {
        // a resource without secret
        ResourceFactory::make()->persist();
        // already soft-deleted resource with secret and secret revision
        $softDeletedResource = ResourceFactory::make()->setDeleted()->persist();
        SecretFactory::make(['resource_id' => $softDeletedResource->id])->persist();
        // a resource with secret and secret revision
        $resource = ResourceFactory::make()->persist();
        SecretFactory::make(['resource_id' => $resource->id])
            ->withSecretRevision()
            ->persist();

        /** @var \App\Model\Table\ResourcesTable $table */
        $table = TableRegistry::getTableLocator()->get('Resources');
        $result = $table->cleanupMissingSecret(true);

        $this->assertSame(1, $result);
    }

    public function testCleanupResources_cleanupMissingSecret(): void
    {
        // a resource without secret
        $resourceWithoutSecret = ResourceFactory::make()->persist();
        // already soft-deleted resource with secret and secret revision
        $softDeletedResource = ResourceFactory::make()->setDeleted()->persist();
        SecretFactory::make(['resource_id' => $softDeletedResource->id])->persist();
        // a resource with secret and secret revision
        $resource = ResourceFactory::make()->persist();
        SecretFactory::make(['resource_id' => $resource->id])
            ->withSecretRevision()
            ->persist();

        /** @var \App\Model\Table\ResourcesTable $table */
        $table = TableRegistry::getTableLocator()->get('Resources');
        $result = $table->cleanupMissingSecret();

        $this->assertSame(1, $result);
        $this->assertTrue(ResourceFactory::get($resourceWithoutSecret->id)->get('deleted'));
    }
}
