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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Controller\V2;

use Exception;
use Passbolt\Scim\Utility\Object\ListResponse;

class ScimIndexController extends AbstractScimController
{
    /**
     * SCIM index action
     *
     * @param string $settingId Org Setting Id
     * @param string $resourceType Resource Type (Users, Groups)
     * @return void
     * @throws \Exception
     */
    public function index(string $settingId, string $resourceType): void
    {
        try {
            $queryParams = $this->getRequest()->getQueryParams();
            $startIndex = null;
            if (isset($queryParams['startIndex'])) {
                $startIndex = (int)$queryParams['startIndex'];
            }
            $count = null;
            if (isset($queryParams['count'])) {
                $count = (int)$queryParams['count'];
            }
            $filter = null;
            if (isset($queryParams['filter'])) {
                $filter = (string)$queryParams['filter'];
            }
            $listResponse = (new ListResponse())
                ->fetchResources(
                    $resourceType,
                    $startIndex,
                    $count,
                    $filter
                );
            $this->processResponse($settingId, $listResponse);
        } catch (Exception $e) {
            $this->processException($settingId, $e);
        }
    }
}
