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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Controller\Upgrade;

use App\Controller\AppController;
use App\Database\Type\ISOFormatDateTimeType;
use App\Model\Table\ResourcesTable;
use Passbolt\Metadata\Service\MetadataResourcesRenderService;
use Passbolt\Metadata\Service\Upgrade\MetadataUpgradeResourcesUpdateService;

/**
 * @property \App\Controller\Component\ApiPaginationComponent $ApiPagination
 */
class MetadataUpgradeResourcesPostController extends AppController
{
    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected ResourcesTable $Resources;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Resources = $this->fetchTable('Resources');
        $this->loadComponent('Passbolt/Metadata.MetadataPagination', [
            'model' => 'Resources',
            'order' => [
                'Resources.id' => 'asc', // Default sorted field
            ],
        ]);
    }

    /**
     * @return void
     */
    public function post()
    {
        $this->assertJson();
        $this->User->assertIsAdmin();
        $this->assertNotEmptyArrayData();

        (new MetadataUpgradeResourcesUpdateService())->updateMany(
            $this->User->getAccessControl(),
            $this->getRequest()->getData()
        );

        // Retrieve and sanity the query options.
        $whitelist = ['filter' => ['is-shared'], 'contain' => ['permissions']];
        $options = $this->QueryString->get($whitelist);

        // Performance improvement: map query result datetime properties to string.
        ISOFormatDateTimeType::mapDatetimeTypesToMe();
        $resources = $this->Resources->findMetadataUpgradeIndex($options);
        $resources = $this->paginate($resources);
        ISOFormatDateTimeType::remapDatetimeTypesToDefault();

        $resources = (new MetadataResourcesRenderService())->renderResources($resources->toArray());
        $this->success(__('The operation was successful.'), $resources);
    }
}
