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
 * @since         2.7.0
 */

namespace App\Test\TestCase\Controller\Secrets;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;

class SecretsViewControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Roles', 'app.Base/Secrets',
    ];

    public function testSecretsViewControllerSuccess()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/secrets/resource/$resourceId.json?api-version=2");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertSecretAttributes($this->_responseJsonBody);
    }

    public function testSecretsViewControllerErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/secrets/resource/$resourceId.json?api-version=2");
        $this->assertAuthenticationError();
    }

    public function testSecretsViewControllerErrorNotValidId()
    {
        $this->authenticateAs('dame');
        $resourceId = 'invalid-id';
        $this->getJson("/secrets/resource/$resourceId.json?api-version=2");
        $this->assertError(400, 'The resource identifier should be a valid UUID.');
    }

    public function testSecretsViewControllerErrorNotFound()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.april');
        $this->getJson("/secrets/resource/$resourceId.json?api-version=2");
        $this->assertError(404, 'The secret does not exist.');
    }
}
