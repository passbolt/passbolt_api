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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Pages;

use App\Test\Lib\AppIntegrationTestCase;

class HomeControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'plugin.Passbolt/AccountSettings.AccountSettings',
    ];

    public function testHomeNotLoggedInError()
    {
        $this->get('/app/passwords');
        $this->assertRedirect('/auth/login?redirect=%2Fapp%2Fpasswords');
    }

    public function testHomeSuccess()
    {
        $this->authenticateAs('ada');
        $this->get('/app/passwords');
        $this->assertResponseOk();
        $this->assertResponseContains('skeleton');
    }
}
