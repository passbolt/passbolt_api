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

use App\Error\Exception\ValidationException;
use App\Service\Resources\ResourcesAddService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Chronos\ChronosInterface;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class PasswordExpiryResourcesAddServiceTest extends AppTestCase
{
    public ResourcesAddService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ResourcesAddService();
        ResourceTypeFactory::make()->default()->persist();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    /**
     * Validate the expired date
     */
    public function testPasswordExpiryResourcesAddService_Assign_Expired_Or_Throw_Validation_Error()
    {
        // Arrange
        $uac = UserFactory::make()->user()->persistedUAC();
        $payload = $this->getDummyResourcesPostData([
            'name' => 'Nouveau nom de resource privée',
            'username' => 'username@domain.com',
            'uri' => 'https://www.mon-domain.com',
            'description' => 'Nouvelle description de resource privée',
        ]);

        $valuesParsable = [
            '2000-01-01',
            'today',
            'tomorrow',
            null,
            '',
        ];
        $valuesNotParsable = [
            'foo',
        ];
        foreach ($valuesNotParsable as $expired) {
            $payload['expired'] = $expired;
            try {
                $this->service->add($uac, $payload);
            } catch (ValidationException $e) {
                $this->assertSame('Could not validate resource data.', $e->getMessage());
            }
        }
        foreach ($valuesParsable as $expired) {
            $payload['expired'] = $expired;
            $resource = $this->service->add($uac, $payload);
            if (empty($expired)) {
                $this->assertNull($resource->expired);
            } else {
                $this->assertInstanceOf(ChronosInterface::class, $resource->get('expired'));
            }
        }
        $this->assertSame(count($valuesParsable), ResourceFactory::count());
    }

    public function testPasswordExpiryResourcesAddService_Pwd_Expiry_With_Expiry_Date_Not_Set()
    {
        // Arrange
        $uac = UserFactory::make()->user()->persistedUAC();
        // Enable the pwd expiry in settings
        PasswordExpirySettingFactory::make()->persist();

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
}
