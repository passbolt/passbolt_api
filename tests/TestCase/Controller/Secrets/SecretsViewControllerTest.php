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

    public function testSecretsViewController_Success(): void
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/secrets/resource/$resourceId.json");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertSecretAttributes($this->_responseJsonBody);
    }

    public function testSecretsViewController_Error_NotAuthenticated(): void
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/secrets/resource/$resourceId.json");
        $this->assertAuthenticationError();
    }

    public function testSecretsViewController_Error_NotValidId(): void
    {
        $this->authenticateAs('dame');
        $resourceId = 'invalid-id';
        $this->getJson("/secrets/resource/$resourceId.json");
        $this->assertError(400, 'The resource identifier should be a valid UUID.');
    }

    public function testSecretsViewController_Error_NotFound(): void
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.april');
        $this->getJson("/secrets/resource/$resourceId.json");
        $this->assertError(404, 'The secret does not exist.');
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testSecretsViewController_Error_NotJson(): void
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->get("/secrets/resource/$resourceId");
        $this->assertResponseCode(404);
    }
}
