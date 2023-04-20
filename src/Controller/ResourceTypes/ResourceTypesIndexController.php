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
 * @since         3.0.0
 */

namespace App\Controller\ResourceTypes;

use App\Controller\AppController;
use App\Service\ResourceTypes\ResourceTypesFinderService;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;
use Passbolt\TotpResourceType\TotpResourceTypePlugin;

class ResourceTypesIndexController extends AppController
{
    use FeaturePluginAwareTrait;

    /**
     * Resource Types Index action
     *
     * @throws \Cake\Http\Exception\NotFoundException if plugin is disabled by admin
     * @return void
     */
    public function index()
    {
        if (!Configure::read('passbolt.plugins.resourceTypes.enabled')) {
            throw new NotFoundException();
        }
        $resourceTypeFinderService = new ResourceTypesFinderService();
        $resourceTypes = $resourceTypeFinderService->find();

        if (!$this->isFeaturePluginEnabled(TotpResourceTypePlugin::class)) {
            $resourceTypes = $resourceTypeFinderService->withoutTotp($resourceTypes);
        }

        $this->success(__('The operation was successful.'), $resourceTypes->all());
    }
}
