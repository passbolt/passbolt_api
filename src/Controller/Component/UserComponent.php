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
use App\Utility\ExtendedUserAccessControl;
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
     * @var string|null cache for role uuid
     */
    protected ?string $_roleId = null;

    /**
     * User agent cache to avoid parsing multiple times per request
     *
     * @var array|null
     */
    protected ?array $_userAgent = null;

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
     * @return string client ip
     */
    public function ip(): string
    {
        return $this->getController()->getRequest()->clientIp();
    }

    /**
     * @return string user agent
     */
    public function userAgent(): string
    {
        return $this->getController()->getRequest()->getEnv('HTTP_USER_AGENT') ?? 'undefined';
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
     * Return the role id if set or the id of Guest role
     * Return null if database role guest entry is not present (aka fresh)
     *
     * @return string|null
     */
    public function roleId(): ?string
    {
        $roleId = $this->getAuthenticatedUserProperty('role_id') ?? null;

        if ($roleId === null) {
            /** @var \App\Model\Table\RolesTable $Roles */
            $Roles = TableRegistry::getTableLocator()->get('Roles');

            return $Roles->getIdByName(Role::GUEST);
        }

        return $roleId;
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
     * Return true if the current user is a guest
     *
     * @return bool
     */
    public function isGuest(): bool
    {
        return $this->role() == Role::GUEST;
    }

    /**
     * @return \App\Utility\UserAccessControl
     */
    public function getAccessControl(): UserAccessControl
    {
        return new UserAccessControl($this->role(), $this->id(), $this->username());
    }

    /**
     * @return \App\Utility\ExtendedUserAccessControl
     */
    public function getExtendAccessControl(): ExtendedUserAccessControl
    {
        return new ExtendedUserAccessControl(
            $this->role(),
            $this->id(),
            $this->username(),
            $this->ip(),
            $this->userAgent()
        );
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
     * @param ?string $msg Optional message
     * @throws \Cake\Http\Exception\ForbiddenException
     * @return void
     */
    public function assertIsAdmin(?string $msg = null): void
    {
        if (!$this->isAdmin()) {
            throw new ForbiddenException($msg ?? __('Access restricted to administrators.'));
        }
    }

    /**
     * Allow guests only.
     *
     * @throws \Cake\Http\Exception\ForbiddenException
     * @return void
     */
    public function assertIsGuest(): void
    {
        if (!$this->isGuest()) {
            throw new ForbiddenException(__('Access restricted to unauthenticated users.'));
        }
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if user is not guest
     * @return void
     */
    public function assertNotLoggedIn(): void
    {
        if ($this->role() !== Role::GUEST) {
            throw new ForbiddenException(__('The user should not be logged in.'));
        }
    }
}
