<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace PassboltTestData\Shell\Task\Security;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\Security\Xss;
use PassboltTestData\Shell\Task\Base\ResourcesDataTask;

class XssResourcesDataTask extends ResourcesDataTask
{
    protected $_truncate = false;

    /**
     * Get the resource data
     *
     * @return array
     */
    public function getData()
    {
        $exploits = Xss::getExploits();
        $resources = [];

        foreach ($exploits as $rule => $exploit) {
            $resources[] = [
                'id' => UuidFactory::uuid('resource.id.xss' . count($resources)),
                'name' => substr($exploit, 0, 64),
                'username' => substr($exploit, 0, 64),
                'uri' => substr($exploit, 0, 255),
                'description' => substr($exploit, 0, 10000),
                'deleted' => 0,
                'created_by' => UuidFactory::uuid('user.id.xss' . count($resources)),
                'modified_by' => UuidFactory::uuid('user.id.xss' . count($resources)),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ];
        }

        return $resources;
    }
}
