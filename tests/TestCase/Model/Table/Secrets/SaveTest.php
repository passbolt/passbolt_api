<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Secrets;

use App\Model\Table\SecretsTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;

    public $Secrets;

    public $fixtures = ['app.resources', 'app.secrets', 'app.users'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Secrets') ? [] : ['className' => SecretsTable::class];
        $this->Secrets = TableRegistry::get('Secrets', $config);
    }

    public function tearDown()
    {
        unset($this->Secrets);

        parent::tearDown();
    }

    protected function getEntityDefaultOptions()
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                'resource_id' => true, // Make it accessible for the test, it shouldn't when saving in association to resource.
                'user_id' => true,
                'data' => true
            ],
        ];
    }

    protected function getGpgMessageTestCases()
    {
        return [
            'rule_name' => 'isValidGpgMessage',
            'test_cases' => [
                '!#*' => false,
                // Message without gpg markers shouldn't be valid
                'hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF' => false,
                // Corrupted message
                '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
-----END PGP MESSAGE-----' => false,
                '-----BEGIN PGP MESSAGE-----
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
-----END PGP MESSAGE-----' => true,
            ],
        ];
    }

    /* ************************************************************** */
    /* FORMAT VALIDATION TESTS */
    /* ************************************************************** */

    public function testValidationUserId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Secrets, 'user_id', self::getDummySecret(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationResourceId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Secrets, 'resource_id', self::getDummySecret(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationData()
    {
        $testCases = [
            'isValidGpgMessage' => $this->getGpgMessageTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Secrets, 'data', self::getDummySecret(), self::getEntityDefaultOptions(), $testCases);
    }

    /* ************************************************************** */
    /* LOGIC VALIDATION TESTS */
    /* ************************************************************** */

    public function testSuccess()
    {
        $data = self::getDummySecret();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);
        $save = $this->Secrets->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The secret save operation failed.');

        // Check that the resource and its sub-models are saved as expected.
        $secret = $this->Secrets->find()
            ->where(['Secrets.id' => $save->id])
            ->first();

        // Check the resource attributes.
        $this->assertSecretAttributes($secret);
        $this->assertEquals($data['user_id'], $secret->user_id);
        $this->assertEquals($data['resource_id'], $secret->resource_id);
        $this->assertEquals($data['data'], $secret->data);
    }

    public function testErrorRuleSecretUnique()
    {
        $data = self::getDummySecret();
        $data['user_id'] = UuidFactory::uuid('user.id.ada');
        $data['resource_id'] = UuidFactory::uuid('resource.id.apache');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);

        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['secret_unique']);
    }

    public function testErrorRuleUserExists()
    {
        $data = self::getDummySecret();
        $data['user_id'] = UuidFactory::uuid();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);

        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_exists']);
    }

    public function testErrorRuleUserIsNotSoftDeleted()
    {
        $data = self::getDummySecret();
        $data['user_id'] = UuidFactory::uuid('user.id.sofia');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);

        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_is_not_soft_deleted']);
    }

    public function testErrorRuleResourceExists()
    {
        $data = self::getDummySecret();
        $data['resource_id'] = UuidFactory::uuid();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);

        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['resource_id']['resource_exists']);
    }

    public function testErrorRuleResourceIsNotSoftDeleted()
    {
        $data = self::getDummySecret();
        $data['resource_id'] = UuidFactory::uuid('resource.id.jquery');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);

        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['resource_id']['resource_is_not_soft_deleted']);
    }
}
