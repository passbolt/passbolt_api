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

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;

class SecretsDataTask extends \PassboltTestData\Shell\Task\Base\SecretsDataTask
{
    public $entityName = 'Secrets';

    /**
     * Get encrypted secrets
     *
     * @return array
     */
    public function getData()
    {
        $secrets = [];

        $this->loadModel('Users');
        $this->loadModel('Resources');

        $user = $this->Users->findById(UuidFactory::uuid('user.id.user_0'))->first();
        $password = 'Nous sommes partout';
        $armoredPassword = $this->_encrypt($password, $user);

        $resources = $this->Resources->findIndex($user->id);
        foreach ($resources as $resource) {
            $secrets[] = [
                'id' => UuidFactory::uuid("secret.id.{$resource->id}-{$user->id}"),
                'user_id' => $user->id,
                'resource_id' => $resource->id,
                'data' => $armoredPassword,
                'created_by' => $user->id
            ];
        }

        return $secrets;
    }
}
