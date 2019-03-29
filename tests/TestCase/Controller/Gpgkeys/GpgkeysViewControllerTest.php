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

namespace App\Test\TestCase\Controller\Gpgkeys;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;

class GpgkeysViewControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles'];

    public function testGpgkeysViewErrorNotAuthenticated()
    {
        $uuid = UuidFactory::uuid();
        $this->getJson('/gpgkeys/' . $uuid . '.json');
        $this->assertAuthenticationError();
    }

    public function testGpgkeysViewGetSuccess()
    {
        $this->authenticateAs('ada');
        $userId = UuidFactory::uuid('user.id.ada');
        $uuid = UuidFactory::uuid('gpgkey.id.' . $userId);
        $this->getJson('/gpgkeys/' . $uuid . '.json?api-version=2');
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertGpgkeyAttributes($this->_responseJsonBody);
    }

    public function testGpgkeysViewGetApiV1Success()
    {
        $this->authenticateAs('ada');
        $userId = UuidFactory::uuid('user.id.ada');
        $uuid = UuidFactory::uuid('gpgkey.id.' . $userId);
        $this->getJson('/gpgkeys/' . $uuid . '.json');
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        $this->assertObjectHasAttribute('Gpgkey', $this->_responseJsonBody);
        $this->assertGpgkeyAttributes($this->_responseJsonBody->Gpgkey);
    }

    public function testGpgkeysViewInvalidIdError()
    {
        $this->authenticateAs('ada');
        $this->getJson('/gpgkeys/notuuid.json');
        $this->assertError(400, 'The gpg key id should be a uuid.');
    }

    public function testGpgkeysViewGpgkeyDoesNotExistError()
    {
        $this->authenticateAs('ada');
        $uuid = UuidFactory::uuid('gpgkey.id.notagpgkey');
        $this->getJson('/gpgkeys/' . $uuid . '.json');
        $this->assertError(404, 'The gpg key does not exist.');
    }
}
