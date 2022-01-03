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
 * @since         2.0.0
 */
namespace App\Controller\Component;

use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use donatj\UserAgent\UserAgentParser;
use Exception;

/**
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class UserComponent extends Component
{
    public $components = ['Authentication.Authentication'];

    /**
     * User agent cache to avoid parsing multiple times per request
     *
     * @var array|null
     */
    protected $_userAgent = null;

    /**
     * Return the current user id if the user is identified
     *
     * @return string|null
     */
    public function id(): ?string
    {
        return $this->getAuthenticatedUserProperty('id');
    }

    /**
     * Return the current username if the user is identified
     *
     * @return string|null
     */
    public function username(): ?string
    {
        return $this->getAuthenticatedUserProperty('username');
    }

    /**
     * Return the current user role or GUEST if the user is not identified
     *
     * @return string|null
     */
    public function role(): ?string
    {
        return $this->getAuthenticatedUserProperty('role.name', Role::GUEST);
    }

    /**
     * Return true if the current user is an administrator
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role() == Role::ADMIN;
    }

    /**
     * @return \App\Utility\UserAccessControl
     */
    public function getAccessControl()
    {
        return new UserAccessControl($this->role(), $this->id(), $this->username());
    }

    /**
     * Get a given property of the authenticated user.
     *
     * @param string $property Property name (e.g. username, or id, or role.name)
     * @param string|null $default Default value if not found
     * @return string|null
     */
    protected function getAuthenticatedUserProperty(string $property, ?string $default = null): ?string
    {
        try {
            // Get the user delivered by the authentication result.
            $data = $this->Authentication->getResult()->getData() ?? null;
            if (!isset($data)) {
                $user = [];
            } elseif (!isset($data['user'])) {
                $user = $data;
            } else {
                $user = $data['user'];
            }
        } catch (Exception $e) {
            $user = [];
        } finally {
            return Hash::get($user, $property, $default);
        }
    }

    /**
     * Get user agent details from name defined in environment variable
     *
     * @return array
     * @throws \Exception
     */
    public function agent()
    {
        if (!isset($this->_userAgent)) {
            // Parse the user agent string.
            try {
                /** @var string|null $agent */
                $agent = env('HTTP_USER_AGENT');
                if ($agent === null) {
                    throw new Exception(__('undefined user agent'));
                }
                $provider = new UserAgentParser();
                $parser = $provider->parse($agent);
                $this->_userAgent['Browser']['name'] = $parser->browser();
                $this->_userAgent['Browser']['version'] = $parser->browserVersion();
            } catch (Exception $e) {
                // Failure is not an option
                $this->_userAgent['Browser']['name'] = 'invalid';
                $this->_userAgent['Browser']['version'] = 'invalid';
            }
        }

        return $this->_userAgent;
    }

    /**
     * Get the user theme
     *
     * @return string|null
     */
    public function theme()
    {
        if (Configure::read('passbolt.plugins.accountSettings')) {
            /** @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings */
            $AccountSettings = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings');
            $theme = $AccountSettings->getTheme($this->id());
            if ($theme) {
                return $theme;
            }
        }

        return null;
    }

    /**
     * Allow admins only.
     *
     * @throws \Cake\Http\Exception\ForbiddenException
     * @return void
     */
    public function assertIsAdmin(): void
    {
        if (!$this->isAdmin()) {
            throw new ForbiddenException(__('Access restricted to administrators.'));
        }
    }
}
