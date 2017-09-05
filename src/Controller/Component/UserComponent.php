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
namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Model\Entity\Role;

class UserComponent extends Component
{

    public $components = ['Auth'];

    /**
     * Return the current user role or GUEST if the user is not identified
     * @return string
     */
    public function role() {
        $role = $this->Auth->user('Role.name');
        if (!isset($role)) {
            return Role::GUEST;
        }
        return $role;
    }
}
