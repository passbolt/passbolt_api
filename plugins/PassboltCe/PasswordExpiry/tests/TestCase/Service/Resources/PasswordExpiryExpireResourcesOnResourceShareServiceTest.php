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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Test\TestCase\Service\Resources;

use App\Model\Entity\Permission;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\I18n\FrozenTime;
use Cake\Utility\Hash;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesOnResourceShareService;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class PasswordExpiryExpireResourcesOnResourceShareServiceTest extends AppTestCase
{
    public PasswordExpiryExpireResourcesOnResourceShareService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PasswordExpiryExpireResourcesOnResourceShareService();
        EventManager::instance()->setEventList(new EventList());
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function isResourceAlreadyExpired(): array
    {
        return [[false], [true]];
    }

    /**
     * @dataProvider isResourceAlreadyExpired
     */
    public function testPasswordExpiryExpireResourcesOnResourceShareService($isResourceAlreadyExpired)
    {
        PasswordExpirySettingFactory::make()->persist();
        UserFactory::make(5)->user()->persist();
        // Define actors of this tests
        $allUsersWithPermission = [$userLosingPermission, $owner, $editor, $viewer] = UserFactory::make(4)->active()->user()->persist();
        $expiryDate = $isResourceAlreadyExpired ? FrozenTime::yesterday() : FrozenTime::tomorrow();
        $factory = ResourceFactory::make()
            ->withPermissionsFor([$userLosingPermission], Permission::READ)
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$editor], Permission::UPDATE)
            ->withPermissionsFor([$viewer], Permission::READ);

        /** @var \App\Model\Entity\Resource $resourceNeverViewed */
        $resourceNeverViewed = $factory->persist();
        /** @var \App\Model\Entity\Resource $resourceViewed */
        $resourceViewed = $factory->expired($expiryDate)->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userLosingPermission))
            ->withResources(ResourceFactory::make($resourceViewed))
            ->persist();

        $userIdsWithPermissionBeforeShare = Hash::extract($allUsersWithPermission, '{n}.id');
        $userIdsWithPermissionAfterShare = array_slice($userIdsWithPermissionBeforeShare, 1);

        $result = $this->service->expireResourceIfUsersLostPermission(
            $resourceViewed,
            $userIdsWithPermissionBeforeShare,
            $userIdsWithPermissionAfterShare
        );

        if ($isResourceAlreadyExpired) {
            $this->assertFalse($result);
        } else {
            $this->assertTrue($result);
            $this->assertEventFired($this->service::EVENT_EXPIRE_RESOURCE_ON_SHARE);
            $this->assertEventFiredWith(
                $this->service::EVENT_EXPIRE_RESOURCE_ON_SHARE,
                'resourceIds',
                [$resourceViewed->id]
            );
        }

        $resourceViewed = ResourceFactory::get($resourceViewed->id);
        $resourceNeverViewed = ResourceFactory::get($resourceNeverViewed->id);
        $this->assertTrue($resourceViewed->isExpired());
        $this->assertFalse($resourceNeverViewed->isExpired());
    }
}
