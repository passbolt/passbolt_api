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
 * @since         5.7.0
 */

namespace App\Test\TestCase\Model\Table\Secrets;

use App\Model\Table\SecretsTable;
use App\Test\Factory\SecretFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;

class DeletedValidationTest extends AppTestCase
{
    public SecretsTable $Secrets;

    public function setUp(): void
    {
        parent::setUp();
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
    }

    public function tearDown(): void
    {
        unset($this->Secrets);

        parent::tearDown();
    }

    /* FORMAT VALIDATION TESTS */

    public function testDeletedValidationData_No_Error_On_Creation()
    {
        $secret = SecretFactory::make()->getEntity();
        $this->Secrets->patchEntity($secret, ['deleted' => null]);

        $errors = $secret->getErrors();
        $this->assertArrayNotHasKey('deleted', $errors);
    }

    public function testDeletedValidationData_No_Error_On_Update()
    {
        $secret = SecretFactory::make()->persist();
        $this->Secrets->patchEntity($secret, ['deleted' => '2000-01-01 00:00:00']);

        $errors = $secret->getErrors();
        $this->assertArrayNotHasKey('deleted', $errors);
    }

    public function testDeletedValidationData_Wrong_Format()
    {
        $secret = SecretFactory::make()->getEntity();
        $this->Secrets->patchEntity($secret, ['deleted' => 'foo']);

        $errors = $secret->getErrors();
        $this->assertNotEmpty($errors['deleted']['dateTime']);
    }

    public function testDeletedValidationData_Not_Null_On_Create()
    {
        $secret = SecretFactory::make()->getEntity();
        $this->Secrets->patchEntity($secret, ['deleted' => '2000-01-01 00:00:00']);

        $errors = $secret->getErrors();
        $this->assertNotEmpty($errors['deleted']['isNullOnCreate']);
    }
}
