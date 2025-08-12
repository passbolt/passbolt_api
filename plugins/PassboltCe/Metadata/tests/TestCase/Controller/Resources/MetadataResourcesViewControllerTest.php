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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller\Resources;

use App\Test\Factory\ResourceFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class MetadataResourcesViewControllerTest extends AppIntegrationTestCaseV5
{
    public function testResourcesViewController_Metadata_Enabled_Success_V4_Resource(): void
    {
        $user = $this->logInAsUser();
        $resourceV4 = ResourceFactory::make()->withPermissionsFor([$user])->persist();

        $this->getJson("/resources/{$resourceV4->get('id')}.json");
        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertObjectNotHasAttributes(MetadataResourceDto::V5_META_PROPS, $response);
        $this->assertObjectHasAttributes(MetadataResourceDto::V4_META_PROPS, $response);
    }

    public function testResourcesViewController_Metadata_Enabled_Success_V5_Resource(): void
    {
        $user = $this->logInAsUser();
        $resourceV5 = ResourceFactory::make()->withPermissionsFor([$user])->v5Fields()->persist();

        $this->getJson("/resources/{$resourceV5->get('id')}.json");
        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertObjectNotHasAttributes(MetadataResourceDto::V4_META_PROPS, $response);
        $this->assertObjectHasAttributes(MetadataResourceDto::V5_META_PROPS, $response);
    }

    public function testResourcesViewController_V5_Disabled_Success_V5_Resource(): void
    {
        Configure::write('passbolt.v5.enabled', false);
        $user = $this->logInAsUser();
        $resourceV5 = ResourceFactory::make()->withPermissionsFor([$user])->v5Fields()->persist();

        $this->getJson("/resources/{$resourceV5->get('id')}.json");
        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertObjectNotHasAttributes(MetadataResourceDto::V5_META_PROPS, $response);
        $this->assertObjectHasAttributes(MetadataResourceDto::V4_META_PROPS, $response);
    }

    public function testResourcesViewController_Error_V5ResourceWithDeletedResourceType(): void
    {
        $user = $this->logInAsUser();
        $resourceV5 = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->v5Fields()
            ->with('ResourceTypes', ResourceTypeFactory::make()->v5CustomFieldsStandalone()->deleted())
            ->persist();

        $this->getJson("/resources/{$resourceV5->get('id')}.json");

        $this->assertNotFoundError('The resource does not exist');
    }
}
