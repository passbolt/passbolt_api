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
use Passbolt\Metadata\Model\Entity\MetadataPrivateKey;
use Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable
 */
class MetadataPrivateKeysTableTest extends AppTestCaseV5
{
    use FormatValidationTrait;
    use GpgMetadataKeysTestTrait;

    /**
     * Test subject
     *
     * @var \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable|null
     */
    protected ?MetadataPrivateKeysTable $MetadataPrivateKeys = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(MetadataPlugin::class);
        $this->MetadataPrivateKeys = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataPrivateKeys');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->MetadataPrivateKeys);

        parent::tearDown();
    }

    public function testMetadataPrivateKeysTable_Success(): void
    {
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($user)->persist();

        $possibleUserIds = [$user->get('id'), null]; // null for server key
        $randomUserKey = array_rand($possibleUserIds);
        $entity = $this->buildEntity([
            'metadata_key_id' => $metadataKey->get('id'),
            'user_id' => $possibleUserIds[$randomUserKey],
            'data' => is_null($possibleUserIds[$randomUserKey]) ? $this->getMessageEncryptedUsingServerKey() : $this->getMessageEncryptedUsingAdaKey(),
        ]);
        $result = $this->MetadataPrivateKeys->save($entity);

        $this->assertInstanceOf(MetadataPrivateKey::class, $result);
        $this->assertEmpty($entity->getErrors());
        $this->assertNotEmpty($result->get('id'));
        $this->assertNotEmpty($result->get('data'));
        $this->assertSame($metadataKey->get('id'), $result->get('metadata_key_id'));
        $this->assertSame($possibleUserIds[$randomUserKey], $result->get('user_id'));
        $this->assertInstanceOf(DateTime::class, $result->get('created'));
        $this->assertInstanceOf(DateTime::class, $result->get('modified'));
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::validationDefault()
     */
    public function testMetadataPrivateKeysTable_ValidationDefault_ID(): void
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->MetadataPrivateKeys,
            'id',
            $this->getDummyMetadataPrivateKeysData(),
            $this->getEntityFieldOptions(),
            $testCases
        );
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::validationDefault()
     */
    public function testMetadataPrivateKeysTable_ValidationDefault_MetadataKeyID(): void
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'notEmptyString' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->MetadataPrivateKeys,
            'metadata_key_id',
            $this->getDummyMetadataPrivateKeysData(),
            $this->getEntityFieldOptions(),
            $testCases
        );
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::validationDefault()
     */
    public function testMetadataPrivateKeysTable_ValidationDefault_UserID(): void
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->MetadataPrivateKeys,
            'user_id',
            $this->getDummyMetadataPrivateKeysData(),
            $this->getEntityFieldOptions(),
            $testCases
        );
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::validationDefault()
     */
    public function testMetadataPrivateKeysTable_ValidationDefault_Data(): void
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
                    $this->getDummyPrivateKeyOpenPGPMessage() => true,
                ],
            ],
        ];
        $this->assertFieldFormatValidation(
            $this->MetadataPrivateKeys,
            'data',
            $this->getDummyMetadataPrivateKeysData(),
            $this->getEntityFieldOptions(),
            $testCases
        );
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::buildRules()
     */
    public function testMetadataPrivateKeysTable_BuildRules_UserIdExist(): void
    {
        $dummyData = $this->getDummyMetadataPrivateKeysData();

        $entity = $this->buildEntity([
            'metadata_key_id' => $dummyData['metadata_key_id'],
            'user_id' => UuidFactory::uuid(), // user not exists
            'data' => $dummyData['data'],
        ]);
        $result = $this->MetadataPrivateKeys->save($entity);

        $this->assertFalse($result);
        $this->assertNotEmpty($entity->getErrors());
        $this->assertArrayHasKey('isUserActiveIfPresent', $entity->getErrors()['user_id']);
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::buildRules()
     */
    public function testMetadataPrivateKeysTable_BuildRules_UserIdIsNotSoftDeleted(): void
    {
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->deleted()
            ->persist();
        $dummyData = $this->getDummyMetadataPrivateKeysData();

        $entity = $this->buildEntity([
            'metadata_key_id' => $dummyData['metadata_key_id'],
            'user_id' => $user->get('id'),
            'data' => $dummyData['data'],
        ]);
        $result = $this->MetadataPrivateKeys->save($entity);

        $this->assertFalse($result);
        $this->assertNotEmpty($entity->getErrors());
        $this->assertCount(1, $entity->getErrors()['user_id']);
        $this->assertArrayHasKey('isUserActiveIfPresent', $entity->getErrors()['user_id']);
    }

    /**
     * If `user_id` is `null` (e.g. server key) there is only one.
     *
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::buildRules()
     */
    public function testMetadataPrivateKeysTable_BuildRules_DuplicateServerKey(): void
    {
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $key */
        $key = MetadataPrivateKeyFactory::make()->serverKey()->withMetadataKey()->persist();

        $entity = $this->buildEntity([
            'metadata_key_id' => $key->metadata_key_id,
            'user_id' => null,
            'data' => $this->getEncryptedMetadataPrivateKeyForServerKey(),
        ]);
        $result = $this->MetadataPrivateKeys->save($entity);

        $this->assertFalse($result);
        $this->assertNotEmpty($entity->getErrors());
        $this->assertCount(1, $entity->getErrors()['user_id']);
        $this->assertArrayHasKey('_isUnique', $entity->getErrors()['user_id']);
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::buildRules()
     */
    public function testMetadataPrivateKeysTable_BuildRules_MetadataKeyAndUserIdUniqueCombination(): void
    {
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $metadataPrivateKey = MetadataPrivateKeyFactory::make()->withUser($user)->withMetadataKey()->persist();
        $dummyData = $this->getDummyMetadataPrivateKeysData();

        $entity = $this->buildEntity([
            'metadata_key_id' => $metadataPrivateKey->get('metadata_key')->get('id'),
            'user_id' => $user->get('id'),
            'data' => $dummyData['data'],
        ]);
        $result = $this->MetadataPrivateKeys->save($entity);

        $this->assertFalse($result);
        $this->assertNotEmpty($entity->getErrors());
        $this->assertCount(1, $entity->getErrors()['user_id']);
        $this->assertArrayHasKey('_isUnique', $entity->getErrors()['user_id']);
    }

    /**
     * Data is asymmetrically encrypted for the correct user key or server key(if user_id is null)
     *
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::buildRules()
     */
    public function testMetadataPrivateKeysTable_BuildRules_DataIsEncryptedForTheCorrectKey(): void
    {
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $dummyData = $this->getDummyMetadataPrivateKeysData();

        $entity = $this->buildEntity([
            'metadata_key_id' => $dummyData['metadata_key_id'],
            'user_id' => $user->get('id'),
            'data' => $dummyData['data'],
        ]);
        $result = $this->MetadataPrivateKeys->save($entity);

        $this->assertFalse($result);
        $this->assertNotEmpty($entity->getErrors());
        $this->assertCount(1, $entity->getErrors()['data']);
        $this->assertArrayHasKey('isValidEncryptedMetadataPrivateKey', $entity->getErrors()['data']);
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::cleanupHardDeletedUsers()
     */
    public function testMetadataPrivateKeysTable_CleanupHardDeletedUsers_DryRun(): void
    {
        $john = UserFactory::make()->user()->persist();
        // metadata key
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier($john)->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($john)->persist();
        $jane = UserFactory::make()->user()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($jane)->persist();
        // metadata private keys with no actual users present
        $noOfUsers = mt_rand(2, 10);
        MetadataPrivateKeyFactory::make(['user_id' => UuidFactory::uuid()], $noOfUsers)->withMetadataKey($metadataKey)->persist();

        $dryRun = true;
        $result = $this->MetadataPrivateKeys->cleanupHardDeletedUsers($dryRun);

        $this->assertSame($noOfUsers, $result);
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::cleanupSoftDeletedUsers()
     */
    public function testMetadataPrivateKeysTable_CleanupSoftDeletedUsers_DryRun(): void
    {
        $john = UserFactory::make()->user()->persist();
        // metadata key
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier($john)->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($john)->persist();
        $jane = UserFactory::make()->user()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($jane)->persist();
        // metadata private keys with soft-deleted user associated
        $noOfUsers = mt_rand(2, 10);
        MetadataPrivateKeyFactory::make($noOfUsers)
            ->withMetadataKey($metadataKey)
            ->with('Users', UserFactory::make()->user()->deleted())
            ->persist();

        $dryRun = true;
        $result = $this->MetadataPrivateKeys->cleanupSoftDeletedUsers($dryRun);

        $this->assertSame($noOfUsers, $result);
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::cleanupHardDeletedUsers()
     */
    public function testMetadataPrivateKeysTable_CleanupHardDeletedUsers(): void
    {
        $john = UserFactory::make()->user()->persist();
        // metadata key
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier($john)->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->serverKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($john)->persist();
        $jane = UserFactory::make()->user()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($jane)->persist();
        // metadata private keys with no actual users present
        $noOfUsers = mt_rand(2, 10);
        MetadataPrivateKeyFactory::make(['user_id' => UuidFactory::uuid()], $noOfUsers)->withMetadataKey($metadataKey)->persist();

        $result = $this->MetadataPrivateKeys->cleanupHardDeletedUsers();

        $this->assertSame($noOfUsers, $result);
        $this->assertSame(3, MetadataPrivateKeyFactory::find()->count());
        $this->assertSame(1, MetadataPrivateKeyFactory::find()->where(['user_id IS NULL'])->count());
    }

    /**
     * @return void
     * @uses \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable::cleanupSoftDeletedUsers()
     */
    public function testMetadataPrivateKeysTable_CleanupSoftDeletedUsers(): void
    {
        $john = UserFactory::make()->user()->persist();
        // metadata key
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier($john)->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->serverKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($john)->persist();
        $jane = UserFactory::make()->user()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($jane)->persist();
        // metadata private keys with soft-deleted user associated
        $noOfUsers = mt_rand(2, 10);
        MetadataPrivateKeyFactory::make($noOfUsers)
            ->withMetadataKey($metadataKey)
            ->with('Users', UserFactory::make()->user()->deleted())
            ->persist();

        $result = $this->MetadataPrivateKeys->cleanupSoftDeletedUsers();

        $this->assertSame($noOfUsers, $result);
        $this->assertSame(3, MetadataPrivateKeyFactory::find()->count());
        $this->assertSame(1, MetadataPrivateKeyFactory::find()->where(['user_id IS NULL'])->count());
    }

    /**
     * @return void
     */
    public function testMetadataPrivateKeysTable_CleanupSoftAndHardTogether(): void
    {
        $john = UserFactory::make()->admin()->persist();
        $jane = UserFactory::make()->user()->persist();
        $bobby = UserFactory::make()->user()->deleted()->persist(); // soft-deleted user
        $adam = UserFactory::make()->admin()->deleted()->persist(); // soft-deleted admin
        // metadata key
        $metadataKey = MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier($john)->persist();
        // metadata private keys
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($john)->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($jane)->persist();
        $metadataKeyOfSoftDeletedUser = MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($bobby)->persist();
        $metadataKeyOfSoftDeletedAdmin = MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($adam)->persist();
        $metadataKeyOfHardDeletedUser = MetadataPrivateKeyFactory::make(['user_id' => UuidFactory::uuid()])->withMetadataKey($metadataKey)->persist();

        $result1 = $this->MetadataPrivateKeys->cleanupHardDeletedUsers();
        $result2 = $this->MetadataPrivateKeys->cleanupSoftDeletedUsers();

        $this->assertSame(3, $result1 + $result2);
        $this->assertSame(2, MetadataPrivateKeyFactory::find()->count());
        $this->assertNull(MetadataPrivateKeyFactory::find()->where(['user_id' => $metadataKeyOfSoftDeletedUser->get('id')])->first());
        $this->assertNull(MetadataPrivateKeyFactory::find()->where(['user_id' => $metadataKeyOfHardDeletedUser->get('id')])->first());
        $this->assertNull(MetadataPrivateKeyFactory::find()->where(['user_id' => $metadataKeyOfSoftDeletedAdmin->get('id')])->first());
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function getDummyMetadataPrivateKeysData(): array
    {
        $factoryData = MetadataPrivateKeyFactory::make()->withMetadataKey()->withUser()->getEntity();

        return [
            'metadata_key_id' => $factoryData->get('metadata_key')->get('id'),
            'user_id' => $factoryData->get('user')->get('id'),
            'data' => $factoryData->get('data'),
        ];
    }

    private function getEntityFieldOptions(): array
    {
        return [
            'checkRules' => true,
            'accessibleFields' => [
                'metadata_key_id' => true,
                'user_id' => true,
                'data' => true,
                'created' => true,
                'modified' => true,
            ],
        ];
    }

    private function buildEntity(array $data): Entity
    {
        return $this->MetadataPrivateKeys->newEntity(
            $data,
            [
                'accessibleFields' => [
                    'metadata_key_id' => true,
                    'user_id' => true,
                    'data' => true,
                    'created' => true,
                    'modified' => true,
                ],
            ]
        );
    }

    /**
     * A message encrypted & signed with ada's keypair.
     *
     * @return string
     */
    private function getMessageEncryptedUsingAdaKey(): string
    {
        return "-----BEGIN PGP MESSAGE-----

wcFMA1P90Qk1JHA+ARAApEO+RT871dtNLdaiCfTCzYq6AMbB0bFOEYRZesZeRUVf
I0I3Jw8cIx1/WZva67kqqJfxRQKjJmgGelQCJm5xNS5lF95szlVZwFTIqF43iycS
LzH22V7WX+yTKKjhenVB8LQh6rhP67puiOoj2b9V+XPKvyXLRu3y7tN5nIuh3BFH
xXTLix4XfxZx6WHPyNaK+ynIIek3waLv7u+s/oPStyukOuYb8q4ktRgO15yCuq/U
uEg25a9y7htAhZwOFlhi+c/FZH3gdwOgOvs/UB+jiGvFqv/j0c7lJx1I6yBN9Zsf
na/Kab1jY2g6Ts18/Vdi/kAGWS9/vq3aMx32arK+8n/NTU0JeyS2RQZ0wFaXENcM
/TSjBmkOjMteZeu23XXSmBvAIK/gjQJoFG/0WpkHWF9JlVlERnxDBxPZBzokIfSb
mZtHsWi5OOVOKNpAYjHP6a9YrhuNITcdX9WgRXWuenwUo+zoQf+n8Qv+Luu/cNRA
DLEDr1ylbahBT/A/Mi6JmVowReq1ZA8KCqCO7rlWsC0EGt92irkUeZ9BhSLqc4HC
jGEexpDQvCStnDcfCt+aiXhU/3usOhnW6OpQ5V3Lb52faXFUhBAFEWqr59Qz42vp
UreRYnSMcqTwiWjE62S8oFako3qd3S/tcJQD06gWcCILoFLY0MC/o/2MrpIkCaLS
wccBsUGzGh9QfhEilXMtw3O9pw2QojWIVWuhqM/eTfgbpKzJD2Jv3JwLUygKQMiQ
uVYpeEMKowA66hsKB8ibtUfV0S85CUvcWq58iW96p1b0G5DFQ8Vsj2txfCmTsoCF
dCrMqKu/OLT02tx9LbVOPx1bw6RvI3JxmVyGMhtsbcDj83/Ud+7lSbFAYbRQetif
Cvgpa2AkHxmDrQ8iZB6dOIHTNFVqc5ZSUEuJpOsFoOxBItXDcVBffYKrwCtTAoxJ
43tqsReNrA0TXzZsYRvhslrKFjUbPj5e1adwL6EMyXLcfpzZOyYyotmduXeYN34p
qm0XCQTgbeA1NXSMMaW95E2IgjeNaFtd47GOoJNlcOVM3Be89CFOeYPTk6OepqFE
OC0kmS8nSORX9v/yj+56Z6QBHwq5O8Ic7+7n+Z0skWvOapmQ5eUGaSqAXJ6Tgefy
/JFoxIsplWwpqC0M9eh8i4yRkcKBrK5w3RIpSG/awcbcuz/BdDc2wRQf88xemqgM
tJoRJ30xYyPC9MMEM+V/tHog4erW7/ehoFR9TLH/X1IFCq19PRTZDmDppoxzANta
wNz7Ee7dkMgrPO8AfzuETFzo7InmCybm6yk5ZRb1afPKexZPjIq0XkxcFMorB/Nf
e6oPfS5ofgdf4sRsTAzsKg+RHMKqybNJpjKlmrNcl5wjcJEufmdOgjFnspJI6FSM
jkMug19+AV5xdhyGp/kFOSXQIcHzSHEWkPHQkFmlIfK4LS5/+DTYk455q2bJfn4U
LjAfsLeoJXSZLLhkYj3MHLCOWop2GwuF6dGY4zzwfyIfecHKIJxS8qTB3V1OmBmm
z8HGmxNM/pWU4rq5b0/BAGIe3GPN2+DSMA==
=A3zs
-----END PGP MESSAGE-----
";
    }

    /**
     * A message encrypted & signed with server key pair.
     *
     * @return string
     */
    private function getMessageEncryptedUsingServerKey(): string
    {
        return "-----BEGIN PGP MESSAGE-----

wcFMA5NJbEXQpdlrAQ//a7Mo7XIYISwPtoWejdCpoumWek42+MldBRz5d55sgHnr
JkD0IyDgZUFfER6Je45ckyYurP6CTcDGuT9gJ7+R9lG8g36Teeu9xlak4uRaJsvY
Q8U1KBOYohS98B5BzoUeXoWj/EP/tmkKe3mgZWeezhDNgu7Puk8R4dD5wtcpAxHk
nA4l+K01vv5+l/H2GXTu7S5ujxo5g0mdXMZZ9alJW6tOne7Rcj3cooWDelqeBEBH
ScFNJeFymD3qxaPCbbUBAuMe7WjIS6ncSS7UjF8tS1FPsUxH0GdRmt+JHTcvJ2fb
AdVLjZieMnKx3mfJWNduejH7f2NEjgPclByuGNIVn1vS0szBXYVLF5UTq2Cmd6AR
7c6NIGkzjTIAbmhk3qtLvQjqnqeir2KP4KGfgIiMgsJaBF8+iOJJVSQOxHjc4+iO
kNUuyHUpfNyMey2MaGoi9blgW1vkQqsPjVolknirmZSwwA+TX2jLshKopwBAZc4r
EEu5rERXzorfTVoQYMlKu8NsH4Ncy+BKqczcjCKR8AckcfJzXF6OfprZ9S6ZVSof
4+MFuqbjonmBpufr/TK7tEbLeaiSS7b5hvY0J4U3feuwTtPrb5kCoSl7Xr387c6v
kWUovHmqTB1ersxPg8W/d046zsicMi3BDtnDVODIeX+kp7GZl292Zi4vZJy+Wx3S
wccBAjCfu6QfyJ/J6UkOBwJnLi29sJxdKZbqipzPmD+1VEJ9b8DZxLhMFnR3Htfx
8rJOiTmXMTHF+/DBNe8nQgKJAxfx0gZnRWmkGx1ee2Lw2m7+3SIKjygPPJDTNi+G
gsmfVNSmpHKHg0q5cFcARuMCaQsodlmSkiGwt2wAW/5Ithp+mDP3GciErIdKCdhe
iM0IOIYb1Yp2D5RjzrbkpeIUvP2VtHujM0SvsVl0qOi39Kx36hgLxvnET0SMCkhH
UNhuzw93c/kFGIvoQwGiD3f/OWidlOGa4Oxy+6fSg5qxPC794bcwvHszrYVYkHNw
CwyOY0dBMCy2E/QSKSZlcukcn85lH0ZvKo4tzwePXKoLZyHLSE4Rf5f+Qxhf4FTe
oHpkYkqekN5Cbg1GbpxxfMG1G+xBLQx3eXXn/CFgMvxZY2uJrrMbcwOXymGtjaz7
98esLK0bpBRCrEhnRg8VJfEJ4CFrXzFTKk4djMBN9q5ZmVCX/kz08hGJsSWporXF
5wcy0ONEODcMh2IRi2jGUoNxaGO5KbUDGemyzYcEET1OQaCk1+7lBEWyT7kv81Sf
BrwmczBxFA+Rn4h3N+ieLCC3+gWayDJo80woboavnmbfXcxzo0O0vrjaRn9YcTtN
J3+Dv94GH8pG1WHy4VvpeDZdwsLQ3ZwC/W6Rk56YVs8SusKSFB4jwOem31TJKOTN
TPUJd2dGhtZBFhWHCYOHQu6lk5sY1P+3oaJ6wzMRqhLDfH91b5mY/SfHQ5++gfF0
qMZ4lWurYYCiDlHv5oHMFEKLjS2Sm1fPJoiqBs5+Tn9/7U8iKnOnvyDAOc/wIycp
t/hv2+B1YHQhlLDlZnEcwaDbr7Vz9tqxuA==
=o2C5
-----END PGP MESSAGE-----
";
    }
}
