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

use App\Error\Exception\CustomValidationException;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class PatchEntitiesWithChangesTest extends AppTestCase
{
    use FormatValidationTrait;

    public $Resources;

    public $fixtures = ['app.Base/Permissions', 'app.Base/Resources', 'app.Base/Secrets'];

    public function setUp()
    {
        parent::setUp();
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function tearDown()
    {
        unset($this->Resources);

        parent::tearDown();
    }

    protected function getValidSecret()
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

    public function testValidationSuccessOnAddSecrets()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $userEId = UuidFactory::uuid('user.id.edith');
        $userPId = UuidFactory::uuid('user.id.ping');
        $data = [
            ['user_id' => $userEId, 'data' => $this->getValidSecret()],
            ['user_id' => $userPId, 'data' => $this->getValidSecret()],
        ];

        // Retrieve the resource and its permissions to share.
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);

        // Patch the resource secrets.
        try {
            $resource->secrets = $this->Secrets->patchEntitiesWithChanges($resource->id, $resource->secrets, $data);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertEmpty($errors, 'Expect no error ' . json_encode($errors));
        }

        // Assert there is no error, and the changes are well applied.
        $errors = $resource->getErrors();
        $this->assertEmpty($errors);

        // The secret of edith is well added.
        $extract = Hash::extract($resource->secrets, "{n}[user_id={$data[0]['user_id']}]");
        $this->assertNotEmpty($extract);
        $this->assertEquals($resource->id, $extract[0]['resource_id']);
        $this->assertEquals($data[0]['data'], $extract[0]['data']);

        // The ping of ping is well added.
        $extract = Hash::extract($resource->secrets, "{n}[user_id={$data[1]['user_id']}]");
        $this->assertNotEmpty($extract);
        $this->assertEquals($resource->id, $extract[0]['resource_id']);
        $this->assertEquals($data[1]['data'], $extract[0]['data']);
    }

    public function testValidationSuccessOnDeleteSecret()
    {
        $data = [
            UuidFactory::uuid("user.id.ada"),
            UuidFactory::uuid("user.id.betty")];

        // Retrieve the resource and its secrets to share.
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);

        // Patch the resource secrets.
        try {
            $resource->secrets = $this->Secrets->patchEntitiesWithChanges($resource->id, $resource->secrets, [], $data);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertEmpty($errors, 'Expect no error ' . json_encode($errors));
        }

        // Assert there is no error, and the changes are well applied.
        $errors = $resource->getErrors();
        $this->assertEmpty($errors);

        // The secret of ada is well deleted.
        $extract = Hash::extract($resource->secrets, "{n}[user_id={$data[0]}]");
        $this->assertEmpty($extract);

        // The secret of betty is well deleted.
        $extract = Hash::extract($resource->secrets, "{n}[user_id={$data[1]}]");
        $this->assertEmpty($extract);
    }

    //
    public function testValidationErrorOnAddSecret()
    {
        $testCases = [
            'user_id is required' => [
                'errorField' => '0.user_id._required',
                'add' => [['data' => $this->getValidSecret()]]
            ],
            'user_id is invalid' => [
                'errorField' => '0.user_id.uuid',
                'add' => [['user_id' => 'not-valid', 'data' => $this->getValidSecret()]]
            ],
            'data is required' => [
                'errorField' => '0.data._required',
                'add' => [['user_id' => UuidFactory::uuid()]]
            ],
            'data is invalid' => [
                'errorField' => '0.data.isValidGpgMessage',
                'add' => [['user_id' => UuidFactory::uuid(), 'data' => 'not-valid']]
            ],
        ];

        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->_executeErrorCases($testCases, $resourceId);
    }

    public function testValidationErrorOnDeleteSecret()
    {
        $testCases = [
            'secret does not exist for unknown user' => [
                'errorField' => 'secret_exists',
                'delete' => [UuidFactory::uuid()]
            ],
            'secret does not exist for invalid user uuid' => [
                'errorField' => 'secret_exists',
                'delete' => ['invalid-id']
            ],
        ];

        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->_executeErrorCases($testCases, $resourceId);
    }

    protected function _executeErrorCases($testCases, $resourceId)
    {
        foreach ($testCases as $caseLabel => $case) {
            $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
            try {
                $resource->secrets = $this->Secrets->patchEntitiesWithChanges(
                    $resourceId,
                    $resource->secrets,
                    Hash::get($case, 'add', []),
                    Hash::get($case, 'delete', [])
                );
            } catch (CustomValidationException $e) {
                $this->assertEntityError($e, $case['errorField']);
            }
        }
    }
}
