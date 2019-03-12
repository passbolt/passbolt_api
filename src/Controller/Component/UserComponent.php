<?php
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
 * @since         2.0.0
 */
namespace App\Controller\Component;

use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\Controller\Component;
use UserAgentParser\Provider\DonatjUAParser;

class UserComponent extends Component
{

    public $components = ['Auth'];

    /**
     * User agent cache to avoid parsing multiple times per request
     * @var null
     */
    protected $_userAgent = null;

    /**
     * Return the current user id if the user is identified
     *
     * @return string
     */
    public function id()
    {
        return $this->Auth->user('id');
    }

    /**
     * Return the current user role or GUEST if the user is not identified
     *
     * @return string
     */
    public function role()
    {
        $role = $this->Auth->user('role.name');
        if (!isset($role)) {
            return Role::GUEST;
        }

        return $role;
    }

    /**
     * Return true if the current user is an administrator
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role() == Role::ADMIN;
    }

    /**
     * @return UserAccessControl
     */
    public function getAccessControl()
    {
        return new UserAccessControl($this->role(), $this->id());
    }

    /**
     * Get user agent details from name defined in environment variable
     *
     * @return array
     * @throws Exception
     * @throws ValidationException
     */
    public function agent()
    {
        if (!isset($this->_userAgent)) {
            // Parse the user agent string.
            try {
                $agent = env('HTTP_USER_AGENT');
                if ($agent === null) {
                    throw new \Exception(__('undefined user agent'));
                }
                // For now we use the simple DonatjUAParser which allow only a basic parsing to retrieve
                // browser information. Other parser are available, check out the project repository for more information:
                // https://github.com/ThaDafinser/UserAgentParser
                $provider = new DonatjUAParser();
                $parser = $provider->parse($agent);
                $this->_userAgent['Browser']['name'] = $parser->getBrowser()->getName();
                $this->_userAgent['Browser']['version'] = $parser->getBrowser()->getVersion()->getComplete();
            } catch (\Exception $e) {
                // Failure is not an option
                $this->_userAgent['Browser']['name'] = 'invalid';
                $this->_userAgent['Browser']['version'] = 'invalid';
            }
        }

        return $this->_userAgent;
    }
}
