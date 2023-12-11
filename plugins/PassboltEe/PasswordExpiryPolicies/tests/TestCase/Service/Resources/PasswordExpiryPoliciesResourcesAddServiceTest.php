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
use App\Service\Resources\ResourcesAddService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\FrozenDate;
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesValidationService;
use Passbolt\PasswordExpiryPolicies\Service\Settings\PasswordExpiryPoliciesGetSettingsService;
use Passbolt\PasswordExpiryPolicies\Test\Factory\PasswordExpiryPoliciesSettingFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class PasswordExpiryPoliciesResourcesAddServiceTest extends AppTestCase
{
    public ResourcesAddService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ResourcesAddService(
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

    /**
     * Ensures that the expires field is not assignable
     * if password expiry is not enabled in DB
     */
    public function testPasswordExpiryPoliciesResourcesAddService_Pwd_Expiry_Settings_Disabled_Should_Throw_Exception()
    {
        // Arrange
        $uac = UserFactory::make()->user()->persistedUAC();
        $payload = [
            PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE => 'Foo',
        ];
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Password expiry is not enabled.');
        $this->service->add($uac, $payload);
    }

    public function testPasswordExpiryPoliciesResourcesAddService_Pwd_Expiry_With_Expiry_Date_Null()
    {
        // Arrange
        $uac = UserFactory::make()->user()->persistedUAC();
        // Enable the pwd expiry in settings
        PasswordExpiryPoliciesSettingFactory::make()->persist();

        $payload = $this->getDummyResourcesPostData([
            'name' => 'Nouveau nom de resource privée',
            'username' => 'username@domain.com',
            'uri' => 'https://www.mon-domain.com',
            'description' => 'Nouvelle description de resource privée',
            'expired' => null,
        ]);
        $this->service->add($uac, $payload);

        // Assert
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::find()->firstOrFail();
        $this->assertSame(1, ResourceFactory::count());
        $this->assertNull($resource->expired);
    }

    public function testPasswordExpiryPoliciesResourcesAddService_Pwd_Expiry_With_Expiry_Date_In_The_Future()
    {
        // Arrange
        $uac = UserFactory::make()->user()->persistedUAC();
        // Enable the pwd expiry in settings
        PasswordExpiryPoliciesSettingFactory::make()->persist();

        $expiryDate = FrozenDate::tomorrow();
        $payload = $this->getDummyResourcesPostData([
            'name' => 'Nouveau nom de resource privée',
            'username' => 'username@domain.com',
            'uri' => 'https://www.mon-domain.com',
            'description' => 'Nouvelle description de resource privée',
            'expired' => $expiryDate->toDateTimeString(),
        ]);
        $this->service->add($uac, $payload);

        // Assert
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::find()->firstOrFail();
        $this->assertSame(1, ResourceFactory::count());
        $this->assertEquals($expiryDate, $resource->expired);
    }

    /**
     * Expiry date in the future is a success
     */
    public function testPasswordExpiryPoliciesResourcesAddService_Pwd_Expiry_With_Expiry_Date_Not_Set()
    {
        // Arrange
        $uac = UserFactory::make()->user()->persistedUAC();
        // Enable the pwd expiry in settings
        PasswordExpiryPoliciesSettingFactory::make()->persist();

        $payload = $this->getDummyResourcesPostData([
            'name' => 'Nouveau nom de resource privée',
            'username' => 'username@domain.com',
            'uri' => 'https://www.mon-domain.com',
            'description' => 'Nouvelle description de resource privée',
        ]);

        $this->service->add($uac, $payload);

        // Assert
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::find()->firstOrFail();
        $this->assertSame(1, ResourceFactory::count());
        $this->assertNull($resource->expired);
    }

    /**
     * Ensures that the expires field is set to null or a date
     */
    public function testPasswordExpiryPoliciesResourcesAddService_Pwd_Expiry_With_Expiry_Date_Not_A_Date()
    {
        // Arrange
        $uac = UserFactory::make()->user()->persistedUAC();
        // Enable the pwd expiry in settings
        PasswordExpiryPoliciesSettingFactory::make()->persist();

        $payload = $this->getDummyResourcesPostData([
            PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE => 'Foo',
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The expiration date should be null or a valid datetime.');
        $this->service->add($uac, $payload);
    }
}
