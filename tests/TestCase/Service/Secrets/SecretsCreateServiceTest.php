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
 * @since         4.7.0
 */

namespace App\Test\TestCase\Service\Secrets;

use App\Error\Exception\ValidationException;
use App\Service\Secrets\SecretsCreateService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class SecretsCreateServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public SecretsCreateService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SecretsCreateService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    /**
     * Return a valid ASCII-armored OpenPGP message for testing
     *
     * @return string
     */
    protected function OpenPGPMessage(): string
    {
        return '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----';
    }

    /**
     * Test create method with valid data - Success Case
     */

    public function testSecretCreateService_Success()
    {
        // create user and resource
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->withSecretRevisions()
            ->persist();

        $data = [
            'resource_id' => $resource->id,
            'user_id' => $user->id,
            'data' => $this->OpenPGPMessage(),
            'secret_revision_id' => $resource->secret_revisions[0]->id,
        ];

        $createdSecret = $this->service->create($data);

        // Check that the groups and its sub-models are saved as expected.
        $secretData = SecretFactory::find()->where(['id' => $createdSecret->id])->first();

        // Assert returned entity values
        $this->assertEquals($data['resource_id'], $secretData->resource_id);
        $this->assertEquals($data['user_id'], $secretData->user_id);
        $this->assertEquals($data['data'], $secretData->data);
        $this->assertEquals($data['secret_revision_id'], $secretData->secret_revision_id);
    }

    /**
     * Test create method with non openpgp standard data
     */
    public function testSecretCreateService_ErrorValidationException()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();

        $data = [
            'resource_id' => $resource->id,
            'user_id' => $user->id,
            'data' => 'invalid data format',
        ];

        // Assert that a ValidationException is thrown
        $this->expectException(ValidationException::class);
        $this->service->create($data);
    }

    /**
     * Test case with invalid resource_id - non UUID format
     */
    public function testSecretCreateService_ErrorInvalidResourceId()
    {
        // create user
        $user = UserFactory::make()->user()->persist();

        $data = [
            'resource_id' => 'testBadUuid',
            'user_id' => $user->id,
            'data' => $this->OpenPGPMessage(),
        ];

        // Assert that a ValidationException is thrown
        $this->expectException(ValidationException::class);
        $this->service->create($data);
    }

    /**
     * Test case with invalid user_id - non UUID format
     */
    public function testSecretCreateService_ErrorInvalidUserId()
    {
        // create user
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();

        $data = [
            'resource_id' => $resource->id,
            'user_id' => 'testBadUuid',
            'data' => $this->OpenPGPMessage(),
        ];

        // Assert that a ValidationException is thrown
        $this->expectException(ValidationException::class);
        $this->service->create($data);
    }

    /**
     * Test case with Empty data message
     */
    public function testSecretCreateService_ErrorEmptyData()
    {
        // create user
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();

        $data = [
            'resource_id' => $resource->id,
            'user_id' => $user->id,
            'data' => '',
        ];

        // Assert that a ValidationException is thrown
        $this->expectException(ValidationException::class);
        $this->service->create($data);
    }

    /**
     * Test create method with empty data array
     */

    public function testSecretCreateService_ErrorEmptyDataArray()
    {
        // create user
        $user = UserFactory::make()->user()->persist();
        ResourceFactory::make()->withCreatorAndPermission($user)->persist();

        $data = [];

        // Assert that a ValidationException is thrown
        $this->expectException(ValidationException::class);
        $this->service->create($data);
    }

    /**
     * Test to check if the user has permission to access the resource.
     * create two users and corresponding resources and data for user 1, then user 2 tries to access user 1's secret
     */

    public function testSecretCreateService_ErrorUserDoesNotHavePermission()
    {
        //create two users
        [$user1,$user2] = UserFactory::make(2)->user()->persist();
        $resource1 = ResourceFactory::make()->withCreatorAndPermission($user1)->persist();
        $data = [
            'resource_id' => $resource1->id,
            'user_id' => $user2->id,
            'data' => $this->OpenPGPMessage(),
        ];

        // Assert that a ValidationException is thrown
        $this->expectException(ValidationException::class);
        $this->service->create($data);
    }
}
