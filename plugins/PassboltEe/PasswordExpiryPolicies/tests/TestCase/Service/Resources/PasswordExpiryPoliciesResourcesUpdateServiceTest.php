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

namespace Passbolt\PasswordExpiryPolicies\Test\TestCase\Service\Resources;

use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use App\Service\Resources\ResourcesUpdateService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\FrozenTime;
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesValidationService;
use Passbolt\PasswordExpiryPolicies\Service\Settings\PasswordExpiryPoliciesGetSettingsService;
use Passbolt\PasswordExpiryPolicies\Test\Factory\PasswordExpiryPoliciesSettingFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class PasswordExpiryPoliciesResourcesUpdateServiceTest extends AppTestCase
{
    public ResourcesUpdateService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ResourcesUpdateService(
            new PasswordExpiryPoliciesValidationService(
                new PasswordExpiryPoliciesGetSettingsService()
            )
        );
        ResourceTypeFactory::make()->default()->persist();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testPasswordExpiryPoliciesResourcesUpdateService_Update_With_Expiry_Date_Set_To_Null()
    {
        // Enable the pwd expiry in settings
        PasswordExpiryPoliciesSettingFactory::make()->persist();

        // Arrange
        $owner = UserFactory::make()->user()->persist();
        // Create an expired resource
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->expired()
            ->withPermissionsFor([$owner])
            ->persist();
        $this->assertTrue($resource->isExpired());

        $newName = 'Nouveau nom de resource privée';
        $payload = [
            'name' => $newName,
            PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE => null,
        ];

        $this->service->update($this->makeUac($owner), $resource->id, $payload);

        // Assert
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::find()->firstOrFail();
        $this->assertFalse($resource->isExpired());
        $this->assertSame($newName, $resource->name);
    }

    public function testPasswordExpiryPoliciesResourcesUpdateService_Update_With_Expiry_Date_Set_To_Future()
    {
        // Enable the pwd expiry in settings
        PasswordExpiryPoliciesSettingFactory::make()->persist();

        // Arrange
        $owner = UserFactory::make()->user()->persist();
        // Create an expired resource
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->expired()
            ->withPermissionsFor([$owner])
            ->persist();
        $this->assertTrue($resource->isExpired());

        $newName = 'Nouveau nom de resource privée';
        $payload = [
            'name' => $newName,
            PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE => FrozenTime::tomorrow()->toAtomString(),
        ];

        $this->service->update($this->makeUac($owner), $resource->id, $payload);

        // Assert
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::find()->firstOrFail();
        $this->assertFalse($resource->isExpired());
        $this->assertSame($newName, $resource->name);
    }

    public function testPasswordExpiryPoliciesResourcesUpdateService_Update_With_Expiry_Date_ISO()
    {
        // Enable the pwd expiry in settings
        PasswordExpiryPoliciesSettingFactory::make()->persist();

        // Arrange
        $owner = UserFactory::make()->user()->persist();
        // Create an expired resource
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->persist();

        // Time in the format used by the Bext
        $expiryDate = '2000-01-01T12:30:52.689Z';

        $newName = 'Nouveau nom de resource privée';
        $payload = [
            'name' => $newName,
            PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE => $expiryDate,
        ];

        $this->service->update($this->makeUac($owner), $resource->id, $payload);

        // Assert
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::find()->firstOrFail();
        $this->assertTrue($resource->isExpired());
        $this->assertSame($newName, $resource->name);
    }

    public function testPasswordExpiryPoliciesResourcesUpdateService_Update_With_Expiry_Not_Null()
    {
        // Enable the pwd expiry in settings
        PasswordExpiryPoliciesSettingFactory::make()->persist();

        // Arrange
        $owner = UserFactory::make()->user()->persist();
        // Create an expired resource
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->expired()
            ->withPermissionsFor([$owner])
            ->persist();
        $this->assertTrue($resource->isExpired());

        $payload = [
            PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE => 'Foo',
        ];

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The expiration date should be null or a valid datetime.');
        $this->service->update($this->makeUac($owner), $resource->id, $payload);
    }

    public function testPasswordExpiryPoliciesResourcesUpdateService_Update_With_Settings_Not_Set()
    {
        // Arrange
        $owner = UserFactory::make()->user()->persist();
        // Create an expired resource
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->expired()
            ->withPermissionsFor([$owner])
            ->persist();
        $this->assertTrue($resource->isExpired());

        $payload = [
            PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE => null,
        ];

        $resource = $this->service->update($this->makeUac($owner), $resource->id, $payload);
        $this->assertTrue($resource->isExpired());
    }
}
