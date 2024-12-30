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

use App\Database\Type\ISOFormatDateTimeType;
use Passbolt\Metadata\Service\MetadataResourcesRenderService;

class MetadataUpgradeResourcesIndexController extends BaseMetadataResourcesIndexController
{
    /**
     * @var array
     */
    public $paginate = [
        'order' => [
            'Resources.created' => 'asc', // Default sorted field
        ],
    ];

    /**
     * @return void
     */
    public function index()
    {
        $this->assertJson();
        $this->User->assertIsAdmin();

        // Retrieve and sanity the query options.
        $whitelist = ['filter' => ['is-shared',],];
        $options = $this->QueryString->get($whitelist);

        // Performance improvement: map query result datetime properties to string.
        ISOFormatDateTimeType::mapDatetimeTypesToMe();
        $resources = $this->Resources->findMetadataUpgradeIndex($options);
        $this->paginate($resources);
        $resources = $resources->all();
        ISOFormatDateTimeType::remapDatetimeTypesToDefault();

        $resources = (new MetadataResourcesRenderService())->renderResources($resources->toArray());
        if (isset($options['filter']['is-shared'])) {
            foreach ($resources as &$resource) {
                unset($resource['count_permissions']);
            }
        }
        $this->success(__('The operation was successful.'), $resources);
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultPaginationConfigurationKey(): string
    {
        return 'passbolt.plugins.metadata.upgrade.defaultPaginationLimit';
    }

    /**
     * @inheritDoc
     */
    protected function getInvalidPaginationConfigurationMessage(): string
    {
        // To fix this, adjust `passbolt.plugins.metadata.upgrade.defaultPaginationLimit` or `PASSBOLT_PLUGINS_METADATA_ROTATE_KEY_DEFAULT_PAGINATION_LIMIT`
        return __('Invalid pagination limit set for metadata upgrade endpoint.');
    }
}
