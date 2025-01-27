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
 * @since         4.11.0
 */
namespace Passbolt\AccountRecovery\Test\TestCase\Model\Table\Metadata;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Event\EventManager;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Event\Metadata\MetadataKeysBuildRulesListener;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Model\Table\MetadataKeysTable
 */
class MetadataKeysTableTest extends AppTestCaseV5
{
    use FormatValidationTrait;
    use GpgMetadataKeysTestTrait;

    /**
     * Test subject
     *
     * @var \Passbolt\Metadata\Model\Table\MetadataKeysTable
     */
    protected $MetadataKeys;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->MetadataKeys = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataKeys');
        // Attach listener manually for testing purpose
        EventManager::instance()->on(new MetadataKeysBuildRulesListener());
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->MetadataKeys);

        parent::tearDown();
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataKeysTable::buildRules()
     */
    public function testMetadataKeysTable_BuildRules_IsNotAccountRecoveryFingerprintRule_Fail(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy */
        $policy = AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with(
                'AccountRecoveryOrganizationPublicKeys',
                AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()
            )
            ->persist();
        $orkArmored = $policy->account_recovery_organization_public_key->armored_key;
        $orkFingerprint = $policy->account_recovery_organization_public_key->fingerprint;

        $entity = $this->buildEntity([
            'fingerprint' => $orkFingerprint,
            'armored_key' => $orkArmored,
            'created_by' => $user['id'],
            'modified_by' => $user['id'],
        ]);
        $result = $this->MetadataKeys->save($entity);

        $this->assertFalse($result);
        $this->assertNotEmpty($entity->getErrors());
        $this->assertCount(1, $entity->getErrors()['fingerprint']);
        $this->assertArrayHasKey('isNotAccountRecoveryFingerprint', $entity->getErrors()['fingerprint']);
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataKeysTable::buildRules()
     */
    public function testMetadataKeysTable_BuildRules_IsNotAccountRecoveryFingerprintRule_Pass(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with(
                'AccountRecoveryOrganizationPublicKeys',
                AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()
            )
            ->persist();
        $dummyKey = $this->getUserKeyInfo();

        $entity = $this->buildEntity([
            'fingerprint' => $dummyKey['fingerprint'],
            'armored_key' => $dummyKey['armored_key'],
            'created_by' => $user['id'],
            'modified_by' => $user['id'],
        ]);
        $result = $this->MetadataKeys->save($entity);

        $this->assertEmpty($entity->getErrors());
        $this->assertInstanceOf(MetadataKey::class, $result);
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataKeysTable::buildRules()
     */
    public function testMetadataKeysTable_BuildRules_IsNotAccountRecoveryFingerprintRule_PassWhenNoAccountRecoveryKeyPresent(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $dummyKey = $this->getUserKeyInfo();

        $entity = $this->buildEntity([
            'fingerprint' => $dummyKey['fingerprint'],
            'armored_key' => $dummyKey['armored_key'],
            'created_by' => $user['id'],
            'modified_by' => $user['id'],
        ]);
        $result = $this->MetadataKeys->save($entity);

        $this->assertEmpty($entity->getErrors());
        $this->assertInstanceOf(MetadataKey::class, $result);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function buildEntity(array $data, array $option = []): Entity
    {
        return $this->MetadataKeys->newEntity(
            $data,
            array_merge([
                'accessibleFields' => [
                    'fingerprint' => true,
                    'armored_key' => true,
                    'created_by' => true,
                    'modified_by' => true,
                    'expired' => true,
                ],
            ], $option)
        );
    }
}
