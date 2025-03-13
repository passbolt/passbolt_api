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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Test\TestCase\Model\Table;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Entity\MetadataSessionKey;
use Passbolt\Metadata\Test\Factory\MetadataSessionKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable
 */
class MetadataSessionKeysTableTest extends AppTestCaseV5
{
    use FormatValidationTrait;
    use GpgMetadataKeysTestTrait;

    /**
     * Test subject
     *
     * @var \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable
     */
    protected $MetadataSessionKeys;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(MetadataPlugin::class);
        $this->MetadataSessionKeys = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataSessionKeys');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->MetadataSessionKeys);

        parent::tearDown();
    }

    public function testMetadataSessionKeysTable_Success(): void
    {
        $keyInfo = $this->getUserKeyInfo();
        $gpgkey = GpgkeyFactory::make(['armored_key' => $keyInfo['armored_key'], 'fingerprint' => $keyInfo['fingerprint']]);
        $user = UserFactory::make()
            ->with('Gpgkeys', $gpgkey)
            ->admin()
            ->active()
            ->persist();

        $entity = $this->buildEntity([
            'user_id' => $user->get('id'),
            'data' => $this->getEncryptedMetadataSessionKeyForMaki(),
        ]);
        $result = $this->MetadataSessionKeys->save($entity);

        $this->assertInstanceOf(MetadataSessionKey::class, $result);
        $this->assertEmpty($entity->getErrors());
        $this->assertNotEmpty($result->get('id'));
        $this->assertNotEmpty($result->get('data'));
        $this->assertSame($user->get('id'), $result->get('user_id'));
        $this->assertInstanceOf(\Cake\I18n\DateTime::class, $result->get('created'));
        $this->assertInstanceOf(\Cake\I18n\DateTime::class, $result->get('modified'));
        $this->assertCount(1, MetadataSessionKeyFactory::find()->toArray());
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable::validationDefault()
     */
    public function testMetadataSessionKeysTable_ValidationDefault_ID(): void
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->MetadataSessionKeys,
            'id',
            $this->getDummyMetadataSessionKeysData(),
            $this->getEntityFieldOptions(),
            $testCases
        );
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable::validationDefault()
     */
    public function testMetadataSessionKeysTable_ValidationDefault_UserID(): void
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'notEmptyString' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->MetadataSessionKeys,
            'user_id',
            $this->getDummyMetadataSessionKeysData(),
            $this->getEntityFieldOptions(),
            $testCases
        );
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable::validationDefault()
     */
    public function testMetadataSessionKeysTable_ValidationDefault_Data(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmptyString' => self::getNotEmptyTestCases(),
            'isValidOpenPGPMessage' => [
                'rule_name' => 'isValidOpenPGPMessage',
                'test_cases' => [
                    'foo-bar' => false,
                    1 => false,
                    false => false,
                    $this->getEncryptedMetadataSessionKeyForMaki() => true,
                ],
            ],
        ];
        $this->assertFieldFormatValidation(
            $this->MetadataSessionKeys,
            'data',
            $this->getDummyMetadataSessionKeysData(),
            $this->getEntityFieldOptions(),
            $testCases
        );
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable::buildRules()
     */
    public function testMetadataSessionKeysTable_BuildRules_UserIdExistsInRule(): void
    {
        $dummyData = $this->getDummyMetadataSessionKeysData();

        $entity = $this->buildEntity([
            'user_id' => UuidFactory::uuid(), // user not exists
            'data' => $dummyData['data'],
        ]);
        $result = $this->MetadataSessionKeys->save($entity);

        $this->assertFalse($result);
        $this->assertNotEmpty($entity->getErrors());
        $this->assertArrayHasKey('_existsIn', $entity->getErrors()['user_id']);
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable::buildRules()
     */
    public function testMetadataSessionKeysTable_BuildRules_UserIdIsNotSoftDeleted(): void
    {
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->deleted()
            ->persist();
        $dummyData = $this->getDummyMetadataSessionKeysData();

        $entity = $this->buildEntity([
            'user_id' => $user->get('id'),
            'data' => $dummyData['data'],
        ]);
        $result = $this->MetadataSessionKeys->save($entity);

        $this->assertFalse($result);
        $this->assertNotEmpty($entity->getErrors());
        $this->assertCount(1, $entity->getErrors()['user_id']);
        $this->assertArrayHasKey('user_is_soft_deleted', $entity->getErrors()['user_id']);
    }

    /**
     * Data is asymmetrically encrypted for the correct user key.
     *
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable::buildRules()
     */
    public function testMetadataSessionKeysTable_BuildRules_DataIsEncryptedForTheCorrectKey(): void
    {
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $dummyData = $this->getDummyMetadataSessionKeysData();

        $entity = $this->buildEntity(['user_id' => $user->get('id'), 'data' => $dummyData['data']]);
        $result = $this->MetadataSessionKeys->save($entity);

        $this->assertFalse($result);
        $this->assertNotEmpty($entity->getErrors());
        $this->assertCount(1, $entity->getErrors()['data']);
        $this->assertArrayHasKey('isValidEncryptedMetadataSessionKey', $entity->getErrors()['data']);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function getDummyMetadataSessionKeysData(): array
    {
        $keyInfo = $this->getUserKeyInfo();
        $gpgkey = GpgkeyFactory::make(['armored_key' => $keyInfo['armored_key'], 'fingerprint' => $keyInfo['fingerprint']]);
        $user = UserFactory::make()
            ->with('Gpgkeys', $gpgkey)
            ->admin()
            ->active()
            ->persist();
        $data = $this->getEncryptedMetadataSessionKeyForMaki();

        return ['user_id' => $user->get('id'), 'data' => $data];
    }

    private function getEntityFieldOptions(): array
    {
        return [
            'checkRules' => true,
            'accessibleFields' => ['user_id' => true, 'data' => true],
        ];
    }

    private function buildEntity(array $data): Entity
    {
        return $this->MetadataSessionKeys->newEntity($data, $this->getEntityFieldOptions());
    }
}
