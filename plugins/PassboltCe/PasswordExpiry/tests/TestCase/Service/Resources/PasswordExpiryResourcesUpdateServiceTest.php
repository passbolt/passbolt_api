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

use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use App\Service\Resources\ResourcesUpdateService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\I18n\FrozenTime;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class PasswordExpiryResourcesUpdateServiceTest extends AppTestCase
{
    public ResourcesUpdateService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ResourcesUpdateService();
        ResourceTypeFactory::make()->default()->persist();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function dataProvider()
    {
        return [
            [
                'isFeatureEnabled' => false,
                'expiredFieldInPayload' => null,
                'isExpiredBefore' => true,
                'isExpiredAfter' => false,
            ],
            [
                'isFeatureEnabled' => true,
                'expiredFieldInPayload' => '2000-01-01',
                'isExpiredBefore' => true,
                'isExpiredAfter' => true,
            ],
            [
                'isFeatureEnabled' => true,
                'expiredFieldInPayload' => null,
                'isExpiredBefore' => true,
                'isExpiredAfter' => false,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testPasswordExpiryResourcesUpdateService(
        bool $isFeatureEnabled,
        $expiredFieldInPayload,
        bool $isExpiredBefore,
        bool $isExpiredAfter
    ) {
        if ($isFeatureEnabled) {
            // Enable the pwd expiry in settings
            PasswordExpirySettingFactory::make()->persist();
        }

        // Arrange
        $owner = UserFactory::make()->user()->persist();
        // Create an expired resource
        $originalExpiryDate = $isExpiredBefore ? FrozenTime::yesterday() : FrozenTime::tomorrow();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->expired($originalExpiryDate)
            ->withPermissionsFor([$owner])
            ->persist();

        $originalExpiryDate = $resource->expired;

        $newName = 'Nouveau nom de resource privée';
        $payload = [
            'name' => 'Nouveau nom de resource privée',
            PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE => $expiredFieldInPayload,
        ];

        $this->service->update($this->makeUac($owner), $resource->id, $payload);

        // Assert
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::find()->firstOrFail();
        $this->assertSame($isExpiredAfter, $resource->isExpired());
        $this->assertSame($newName, $resource->name);
        if (!$isExpiredAfter) {
            $this->assertNull($resource->expired);
        } else {
            $this->assertEquals(FrozenTime::parse($expiredFieldInPayload), $resource->expired);
        }
    }
}
