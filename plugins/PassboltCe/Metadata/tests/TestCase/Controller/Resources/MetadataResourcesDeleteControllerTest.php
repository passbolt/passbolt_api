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
 * @since         4.12.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller\Resources;

use App\Model\Entity\Permission;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\Purifier;
use Cake\Event\EventList;
use Cake\Event\EventManager;

/**
 * @covers \App\Controller\Resources\ResourcesDeleteController
 */
class MetadataResourcesDeleteControllerTest extends AppIntegrationTestCaseV5
{
    use EmailQueueTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
        // Enable event tracking for emails
        EventManager::instance()->setEventList(new EventList());
    }

    public function testMetadataResourcesDeleteController_Success(): void
    {
        $this->disableErrorHandlerMiddleware();
        /** @var \App\Model\Entity\User $owner */
        $owner = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->active()->persist();
        $this->logInAs($owner);
        $resourceId = ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$user], Permission::READ)
            ->v5Fields(true, ['metadata' => json_encode([]), 'metadata_key_id' => $owner->gpgkey->id])
            ->persist()
            ->get('id');

        $this->deleteJson("/resources/{$resourceId}.json");

        $this->assertSuccess();
        $this->assertEmailQueueCount(1);
        $this->assertEmailSubject($user->username, sprintf('%s deleted a password', Purifier::clean($owner->profile->first_name)));
        $this->assertEmailInBatchContains(sprintf('%s deleted a password', $owner->profile->full_name), $user->username);
    }
}
