<?php
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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Secrets;

use App\Model\Table\SecretsTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class UpdateTest extends AppTestCase
{
    use FormatValidationTrait;

    public $Secrets;

    public $fixtures = ['app.Base/Resources', 'app.Base/Secrets', 'app.Base/Users'];

    public function setUp()
    {
        parent::setUp();
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
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
                'data' => true // Only data should be accessible when updating a secret.
            ],
        ];
    }

    protected function _getValidGpgMessage()
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

    /* ************************************************************** */
    /* FORMAT VALIDATION TESTS */
    /* ************************************************************** */

    public function testValidationData()
    {
        $testCases = [
            'isValidGpgMessage' => self::getGpgMessageTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];

        $resourceId = UuidFactory::uuid('resource.id.apache');
        $userId = UuidFactory::uuid('user.id.ada');
        $entityData = ['id' => UuidFactory::uuid("secret.id.$resourceId-$userId")];
        $this->assertFieldFormatValidation($this->Secrets, 'data', $entityData, self::getEntityDefaultOptions(), $testCases);
    }

    /* ************************************************************** */
    /* LOGIC VALIDATION TESTS */
    /* ************************************************************** */

    public function testSuccess()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $userId = UuidFactory::uuid('user.id.ada');
        $secret = $this->Secrets->get(UuidFactory::uuid("secret.id.$resourceId-$userId"));
        $data = ['data' => $this->_getValidGpgMessage()];
        $options = self::getEntityDefaultOptions();

        $entity = $this->Secrets->patchEntity($secret, $data, $options);
        $save = $this->Secrets->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The secret save operation failed.');

        // Check that the resource and its sub-models are saved as expected.
        $secret = $this->Secrets->find()
            ->where(['Secrets.id' => $save->id])
            ->first();

        // Check the resource attributes.
        $this->assertSecretAttributes($secret);
        $this->assertEquals($data['data'], $secret->data);
    }
}
