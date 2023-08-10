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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\AccountRecoveryPrivateKeyPasswords;

use App\Test\Lib\Utility\PaginationTestTrait;
use Cake\Chronos\Chronos;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryPrivateKeyPasswordsIndexControllerTest extends AccountRecoveryIntegrationTestCase
{
    use PaginationTestTrait;

    public function testAccountRecoveryPrivateKeyPasswordsIndexController_SuccessEmpty()
    {
        $this->logInAsAdmin();
        $this->getJson('/account-recovery/private-key-passwords.json');
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertTrue(is_array($this->_responseJsonBody));
        $this->assertTrue(empty($this->_responseJsonBody));
    }

    public function testAccountRecoveryPrivateKeyPasswordsIndexController_SuccessPaginate()
    {
        $this->logInAsAdmin();

        AccountRecoveryPrivateKeyPasswordFactory::make($this->getArrayOfDistinctRandomPastDates(5, 'modified'))->persist();

        $options = 'limit=3&sort=AccountRecoveryPrivateKeyPasswords.modified&direction=desc';
        $this->getJson('/account-recovery/private-key-passwords.json?' . $options);

        // Check response is ok
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertTrue(is_array($this->_responseJsonBody));

        // Check limit
        $this->assertTrue(count($this->_responseJsonBody) === 3);

        // Check which fields are returned
        $r1 = $this->_responseJsonBody[0];
        $this->assertTrue(Validation::uuid($r1->id));
        $this->assertNotEmpty($r1->recipient_fingerprint);
        $this->assertNotEmpty($r1->recipient_foreign_model);
        $this->assertNotEmpty($r1->data);
        $this->assertNotEmpty($r1->modified);
        $this->assertNotEmpty($r1->created);
        $this->assertTrue(Validation::uuid($r1->created_by));
        $this->assertTrue(Validation::uuid($r1->modified_by));

        // Check sort order
        $d1 = new Chronos($r1->modified);
        $d2 = new Chronos($this->_responseJsonBody[1]->modified);
        $this->assertTrue($d1->gt($d2));
    }

    public function testAccountRecoveryPrivateKeyPasswordsIndexController_ErrorForbidden()
    {
        $this->logInAsUser();
        $this->getJson('/account-recovery/private-key-passwords.json');
        $this->assertError(403);
    }

    public function testAccountRecoveryPrivateKeyPasswordsIndexController_ErrorNotAuthorized()
    {
        $this->getJson('/account-recovery/private-key-passwords.json');
        $this->assertError(401);
    }
}
