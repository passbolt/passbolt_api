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
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\Metadata\Service\MetadataResourcesRenderService;
use Passbolt\Metadata\Service\RotateKey\MetadataRotateKeyResourcesUpdateService;

/**
 * @property \App\Controller\Component\ApiPaginationComponent $ApiPagination
 */
class MetadataRotateKeyResourcesPostController extends AppController
{
    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    /**
     * @var array
     */
    public $paginate = [
        'order' => [
            'Resources.name' => 'asc', // Default sorted field
        ],
    ];

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('ApiPagination', [
            'model' => 'Resources',
        ]);
        $this->Resources = $this->fetchTable('Resources');
        $this->setPaginationOptions();
        $this->unsetDisallowedPaginationParams();
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

    /**
     * Set pagination options.
     *
     * @return void
     */
    private function setPaginationOptions(): void
    {
        $limit = Configure::read('passbolt.plugins.metadata.rotateKey.defaultPaginationLimit');

        if (!is_int($limit)) {
            // To fix this adjust `passbolt.plugins.metadata.rotateKey.defaultPaginationLimit` or `PASSBOLT_PLUGINS_METADATA_ROTATE_KEY_DEFAULT_PAGINATION_LIMIT`
            throw new InternalErrorException(__('Invalid pagination limit set for metadata rotate key endpoint. Please contact your administrator.')); // phpcs:ignore
        }

        $this->paginate['limit'] = $limit;
        if ($limit > MetadataRotateKeyResourcesIndexController::MAX_PAGINATION_LIMIT) {
            $this->paginate['limit'] = MetadataRotateKeyResourcesIndexController::MAX_PAGINATION_LIMIT;
        } elseif ($limit < MetadataRotateKeyResourcesIndexController::MIN_PAGINATION_LIMIT) {
            $this->paginate['limit'] = MetadataRotateKeyResourcesIndexController::MIN_PAGINATION_LIMIT;
        }

        $this->paginate['maxLimit'] = MetadataRotateKeyResourcesIndexController::MAX_PAGINATION_LIMIT;
    }

    /**
     * Remove pagination query parameters those are not controlled by administrators for security reasons.
     *
     * @return void
     */
    private function unsetDisallowedPaginationParams(): void
    {
        $params = $this->getRequest()->getQueryParams();
        unset($params['page']);
        unset($params['limit']);
        $request = $this->getRequest()->withQueryParams($params);
        $this->setRequest($request);
    }
}
