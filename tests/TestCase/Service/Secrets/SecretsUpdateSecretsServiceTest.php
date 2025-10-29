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
 * @since         2.13.0
 */

namespace App\Test\TestCase\Service\Secrets;

use App\Error\Exception\CustomValidationException;
use App\Service\Secrets\SecretsUpdateSecretsService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * \App\Test\TestCase\Service\Secrets\SecretsUpdateSecretsServiceTest Test Case
 *
 * @covers \App\Test\TestCase\Service\Secrets\SecretsUpdateSecretsServiceTest
 */
class SecretsUpdateSecretsServiceTest extends AppTestCase
{
    /**
     * @var \App\Model\Table\SecretsTable
     */
    public $Secrets;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

    /**
     * @var SecretsUpdateSecretsService
     */
    public $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->service = new SecretsUpdateSecretsService();
    }

    /* ADD SECRETS */

    public function testUpdateSecretsSuccess_AddSecrets()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        // Add Betty's permission without secret.
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA, $userB])->withSecretsFor([$userA])->persist();

        $data = [
            ['user_id' => $userB->id, 'data' => Hash::get(self::getDummySecretData(), 'data')],
        ];

        $this->service->updateSecrets($this->makeUac($userA), $r1->id, $data);

        // Assert secrets
        $secrets = $this->Secrets->findByResourceId($r1->id)->toArray();
        $this->assertCount(2, $secrets);
        $this->assertSecretExists($r1->id, $userA->id);
        $this->assertSecretExists($r1->id, $userB->id);
        $secret = $this->Secrets->findByResourceIdAndUserId($r1->id, $userB->id)->first();
        $this->assertEquals($data[0]['data'], $secret->data);
    }

    public function testUpdateSecretsError_AddSecrets_NotAllSecretsProvided()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        // Add Betty's permission without secret.
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA, $userB])->withSecretsFor([$userA])->persist();

        $data = [];

        try {
            $this->service->updateSecrets($this->makeUac($userA), $r1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateSecretsValidationException($e, 'secrets_provided');
        }
    }

    public function testUpdateSecretsError_AddSecrets_ValidationExceptions_UserWithoutAccess()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA])->withSecretsFor([$userA])->persist();

        $data = [['user_id' => $userB->id, 'data' => Hash::get(self::getDummySecretData(), 'data')], ];

        try {
            $this->service->updateSecrets($this->makeUac($userA), $r1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateSecretsValidationException($e, '0.resource_id.has_resource_access');
        }
    }

    private function assertUpdateSecretsValidationException(CustomValidationException $e, string $errorFieldName)
    {
        $this->assertEquals('Could not validate secrets data.', $e->getMessage());
        $error = Hash::get($e->getErrors(), $errorFieldName);
        $this->assertNotNull($error, "Expected error not found : {$errorFieldName}. Errors: " . json_encode($e->getErrors()));
    }

    public function testUpdateSecretsError_AddSecrets_ValidationExceptions_SoftDeletedUser()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->deleted()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA,$userB])->withSecretsFor([$userA])->persist();

        $data = [['user_id' => $userB->id, 'data' => Hash::get(self::getDummySecretData(), 'data')], ];

        try {
            $this->service->updateSecrets($this->makeUac($userA), $r1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateSecretsValidationException($e, '0.user_id.user_is_not_soft_deleted');
        }
    }

    /* DELETE SECRETS */

    public function testUpdateSecretsSuccess_DeleteSecrets()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        // Betty has no permissions but has secret.
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA])->withSecretsFor([$userA,$userB])->persist();
        $secretToKeepId = $r1->secrets[0]->id;

        $data = [];

        $this->service->updateSecrets($this->makeUac($userA), $r1->id, $data);

        // Assert secrets
        $secrets = $this->Secrets->findByResourceId($r1->id)->toArray();
        $this->assertCount(1, $secrets);
        $this->assertSecretExists($r1->id, $userA->id);
        $this->assertSecretNotExist($r1->id, $userB->id);
        $this->assertNull(SecretFactory::get($secretToKeepId)->get('deleted'));
    }

    public function testUpdateSecretsSuccess_DeleteSecrets_Ignore_Deleted_Secrets()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        // Betty has no permissions but has secret.
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA])->withSecretsFor([$userA,$userB])->persist();
        $secretToKeepId = $r1->secrets[0]->id;
        $secretToSoftDeleteId = $r1->secrets[1]->id;

        // Deleted secret to be hard deleted too
        SecretFactory::make()
            ->with('Users', $userB)
            ->with('Resources', $r1)
            ->deleted()
            ->persist();
        $data = [];

        $this->service->updateSecrets($this->makeUac($userA), $r1->id, $data);

        // Assert secrets
        $secrets = $this->Secrets->findByResourceId($r1->id)->toArray();
        $this->assertCount(1, $secrets);
        $this->assertSecretExists($r1->id, $userA->id);
        $this->assertSecretNotExist($r1->id, $userB->id);
        $this->assertNull(SecretFactory::get($secretToKeepId)->get('deleted'));
    }

    /* UPDATE SECRETS */

    public function testUpdateSecretsSuccess_UpdateSecrets()
    {
        $userA = UserFactory::make()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA])->withSecretsFor([$userA])->persist();

        $encryptedSecret = '-----BEGIN PGP MESSAGE-----

hQIMA+p38wQEIh7oARAA01hXtj/fXnMEbilhaL1xihs+2kjJXFROw24/W+GmUQgP
cTr5zfM+CyFLwC2qDffhDnPoAlj8dLLBOyxlHk/+L3pvnLKTpdeDtXKizj/CG9Y1
howFSiql00egivNikd/ZwUW94qXhLlm/0s8CXkKS5ogA3nS9ZE8rbRyO5Qn9GtsS
LiE303+/UTcr5N9ul5zi0Bz1bbch3gaAJ7hYqzKNVveIQCwciZP2nCiBnTQkCUzb
ucQ3lOeGxzpKXHwdGU2KufA+JB9gnGgpzTknxbzqfIjdvbmI0Lobol+sKPHlDtNl
0guQljNcRxRC/I5e/DWVekyuE2IX042SDijgnV3B/thm0otVX5wB3mYiHqw068DK
Cae/ef3jAxafzIb+gJBOyMjLh+ITVpYaleQDl2suR5EKEOmx4+k/ZFWtYsynj+h/
RDIqqpCnEIty+txA4ssIuifBf5wXqRulgpVVdOXpYZBjGRvD7TCos2savhaG/2YH
FQuz1IG9lCTYBWJPHp7iUvqUCiD6nzC20zC/qAn3AIp/mS+yOHceC71jXqKsVMkJ
iOL8/FJm/SwPIgwYO7uYv8/lT+6OYjznXGqt6bwAJlX0MI6NxNYEePBBw9WKaqsY
CyZ/m96d+zxfXDkSsje3cim1U7q6dA7qX3vZ1t3yoNyjM6sE4bL14P6jqDzP0enS
QAEWx5cGIKOwYLS+HQA4w46JQUgJH3uqe8AK26+i20wLKtbWIF7MWW1nfKm9rDiG
URIWI7R+VCewqviRfmezc4M=
=50Mc
-----END PGP MESSAGE-----';
        $data = [
            ['user_id' => $userA->id, 'data' => $encryptedSecret],
        ];

        try {
            $this->service->updateSecrets($this->makeUac($userA), $r1->id, $data);
        } catch (CustomValidationException $validationException) {
            $data = json_encode($data);
            $errors = json_encode($validationException->getErrors());
            $this->fail(__("Failed to update secret.\nData {0}\nErrors {1}", $data, $errors));
        }

        // Assert secrets
        $secrets = $this->Secrets->findByResourceId($r1->id)->toArray();
        $this->assertCount(1, $secrets);
        $this->assertSecretExists($r1->id, $userA->id);
        $secret = $this->Secrets->findByResourceIdAndUserId($r1->id, $userA->id)->first();
        $this->assertEquals($encryptedSecret, $secret->data);
    }

    public function testUpdateSecretsError_UpdateSecrets_ValidationExceptions_InvalidGpgMessage()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA])->withSecretsFor([$userA])->persist();

        $data = [['user_id' => $userB->id, 'data' => 'invalid-message']];

        try {
            $this->service->updateSecrets($this->makeUac($userA), $r1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertUpdateSecretsValidationException($e, '0.data.isValidOpenPGPMessage');
        }
    }
}
