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
namespace PassboltTestData\Shell\Task\Large;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use PassboltTestData\Lib\DataTask;

class ResourcesDataTask extends DataTask
{
    public $entityName = 'Resources';

    /**
     * Get the resource data
     *
     * @return array
     */
    public function getData()
    {
        $max = Configure::read('PassboltTestData.scenarios.large.install.count.resources');
        for ($i = 0; $i < $max; $i++) {
            $resources[] = [
                'id' => UuidFactory::uuid("resource.id.resource_$i"),
                'name' => "Resource $i",
                'username' => "username_$i",
                'uri' => 'http://www.passbolt.com/',
                'description' => 'The password manager your team was waiting for.',
                'deleted' => 0,
                'created_by' => UuidFactory::uuid('user.id.user_1'),
                'modified_by' => UuidFactory::uuid('user.id.user_1'),
                'created' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'modified' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ];
        }

        return $resources;
    }
}
