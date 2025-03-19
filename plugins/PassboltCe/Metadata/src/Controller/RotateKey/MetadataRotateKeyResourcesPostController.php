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
namespace Passbolt\Metadata\Controller\RotateKey;

use App\Controller\AppController;
use App\Database\Type\ISOFormatDateTimeType;
use App\Utility\Pagination\PaginatePropertyAwareTrait;
use Passbolt\Metadata\Service\MetadataResourcesRenderService;
use Passbolt\Metadata\Service\RotateKey\MetadataRotateKeyResourcesUpdateService;

/**
 * @property \App\Controller\Component\ApiPaginationComponent $ApiPagination
 */
class MetadataRotateKeyResourcesPostController extends AppController
{
    use PaginatePropertyAwareTrait;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

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

        (new MetadataRotateKeyResourcesUpdateService())->updateMany(
            $this->User->getAccessControl(),
            $this->getRequest()->getData()
        );

        // Performance improvement: map query result datetime properties to string.
        ISOFormatDateTimeType::mapDatetimeTypesToMe();
        $resources = $this->Resources->findMetadataRotateKeyIndex();
        $this->paginate($resources);
        $resources = $resources->all();
        ISOFormatDateTimeType::remapDatetimeTypesToDefault();

        $resources = (new MetadataResourcesRenderService())->renderResources($resources->toArray());
        $this->success(__('The operation was successful.'), $resources);
    }
}
