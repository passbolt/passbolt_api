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
 * @since         4.9.0
 */

namespace App\Test\TestCase\Controller\Share;

use App\Model\Entity\Permission;
use App\Test\Factory\ResourceFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class ShareControllerWithFactoriesTest extends AppIntegrationTestCase
{
    /**
     * @var \App\Model\Table\UsersTable|null
     */
    public $Users = null;

    /**
     * @var \App\Utility\OpenPGP\Backends\Gnupg|null
     */
    public $gpg = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->gpg = OpenPGPBackendFactory::get();
    }

    public function testShareController_Error_NotValidResourceId_WithFactories(): void
    {
        $this->logInAsUser();
        $resourceId = 'invalid-id';
        $this->putJson("/share/resource/$resourceId.json");
        $this->assertError(400, 'The resource identifier should be a valid UUID.');
    }

    public function testShareController_Error_DoesNotExistResource_WithFactories(): void
    {
        $this->logInAsUser();
        $resourceId = UuidFactory::uuid();
        $this->putJson("/share/resource/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testShareController_Error_AccessDenied_WithFactories(): void
    {
        $user = $this->logInAsUser();
        $testCases = [
            'Cannot share a resource if no permission' => [
                'resourceId' => ResourceFactory::make()->persist()->id],
            'Cannot share a resource with only read access' => [
                'resourceId' => ResourceFactory::make()->withPermissionsFor([$user], Permission::READ)->persist()->id],
            'Cannot share a resource with only update access' => [
                'resourceId' => ResourceFactory::make()->withPermissionsFor([$user], Permission::UPDATE)->persist()->id],
        ];

        foreach ($testCases as $testCase) {
            $resourceId = $testCase['resourceId'];
            $this->putJson("/share/resource/$resourceId.json");
            $this->assertError(403, 'You are not authorized to share this resource.');
        }
    }

    public function testShareController_Error_NotAuthenticated_WithFactories(): void
    {
        $resourceId = ResourceFactory::make()->persist()->id;
        $this->putJson("/share/resource/$resourceId.json");
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testShareController_Error_NotJson_WithFactories(): void
    {
        // Define actors of this tests
        $user = $this->logInAsUser();
        $resourceId = ResourceFactory::make()->withPermissionsFor([$user])->persist()->id;

        // Build the changes.
        $data = ['permissions' => []];
        $this->put("/share/resource/$resourceId", $data);
        $this->assertResponseCode(404);
    }
}
