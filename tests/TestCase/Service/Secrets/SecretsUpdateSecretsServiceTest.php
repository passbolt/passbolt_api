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
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

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
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withSecretsFor([$userA])
            ->withSecretRevisions()
            ->persist();

        $newSecretRevision = SecretRevisionFactory::make()->persist();
        $data = [
            [
                'user_id' => $userB->id,
                'data' => Hash::get(self::getDummySecretData(), 'data'),
                'secret_revision_id' => $newSecretRevision->id,
            ],
        ];

        $changes = $this->service->updateSecrets($this->makeUac($userA), $r1->id, $data);

        // Assert secrets
        $secrets = $this->Secrets->findByResourceId($r1->id)->toArray();
        $secretAdded = $changes->getAddedEntities()[0];
        $this->assertSame($newSecretRevision->id, $secretAdded->secret_revision_id);
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
        $r1 = ResourceFactory::make()
            ->withSecretRevisions()
            ->withPermissionsFor([$userA, $userB])
            ->withSecretsFor([$userA])
            ->persist();

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
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withSecretsFor([$userA])
            ->withSecretRevisions()
            ->persist();
        $newSecretRevision = SecretRevisionFactory::make()->persist();

        $data = [[
            'user_id' => $userB->id,
            'data' => Hash::get(self::getDummySecretData(), 'data'),
            'secret_revision_id' => $newSecretRevision->id,
        ]];

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
        $r1 = ResourceFactory::make()
            ->withSecretRevisions()
            ->withPermissionsFor([$userA,$userB])
            ->withSecretsFor([$userA])->persist();

        $newSecretRevision = SecretRevisionFactory::make()->persist();

        $data = [[
            'user_id' => $userB->id,
            'data' => Hash::get(self::getDummySecretData(), 'data'),
            'secret_revision_id' => $newSecretRevision->id,
        ]];

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
        $r1 = ResourceFactory::make()
            ->withSecretRevisions()
            ->withPermissionsFor([$userA])
            ->withSecretsFor([$userA,$userB])->persist();
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
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withSecretRevisions()
            ->withSecretsFor([$userA,$userB])
            ->persist();
        $secretToKeepId = $r1->secrets[0]->id;

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

    /**
     * TODO: this should fail once the rule secret_revision_is_not_soft_deleted is in place
     */
    public function testUpdateSecrets_Error_If_Revision_Is_Deleted()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        // Add Betty's permission without secret.
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withSecretsFor([$userA])
            ->withSecretRevisions()
            ->persist();

        $newSecretRevision = SecretRevisionFactory::make()->deleted()->persist();
        $data = [
            [
                'user_id' => $userB->id,
                'data' => Hash::get(self::getDummySecretData(), 'data'),
                'secret_revision_id' => $newSecretRevision->id,
            ],
        ];

        $changes = $this->service->updateSecrets($this->makeUac($userA), $r1->id, $data);

        // Assert secrets
        $secrets = $this->Secrets->findByResourceId($r1->id)->toArray();
        $secretAdded = $changes->getAddedEntities()[0];
        $this->assertSame($newSecretRevision->id, $secretAdded->secret_revision_id);
        $this->assertCount(2, $secrets);
        $this->assertSecretExists($r1->id, $userA->id);
        $this->assertSecretExists($r1->id, $userB->id);
        $secret = $this->Secrets->findByResourceIdAndUserId($r1->id, $userB->id)->first();
        $this->assertEquals($data[0]['data'], $secret->data);
    }
}
