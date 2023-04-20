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
 * @since         2.2.0
 */
namespace App\Utility;

use App\Model\Entity\Role;
use App\Model\Validation\EmailValidationRule;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;

/**
 * Class UserAccessControl
 *
 * Immutable object that allows taking a snapshot of the current
 * user id and role for a given action.
 *
 * @package App\Utility
 */
class UserAccessControl
{
    /**
     * @var string|null
     */
    private $userId;

    /**
     * @var string
     */
    private $roleName;

    /**
     * @var string|null
     */
    private $username;

    /**
     * UserAccessControl constructor.
     *
     * @param string $roleName The role name
     * @param string|null $userId the user uuid
     * @param string|null $username the user email
     */
    public function __construct(string $roleName, ?string $userId = null, ?string $username = null)
    {
        if (isset($userId) && !Validation::uuid($userId)) {
            throw new InternalErrorException('Invalid UserControl user id.');
        }
        if (isset($username) && !EmailValidationRule::check($username)) {
            throw new InternalErrorException('Invalid UserControl username.');
        }
        $this->userId = $userId;
        $this->roleName = $roleName;
        $this->username = $username;
    }

    /**
     * Get the user id
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->userId;
    }

    /**
     * Get the user role name
     *
     * @return string
     */
    public function roleName(): string
    {
        return $this->roleName;
    }

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->roleName() === Role::ADMIN;
    }

    /**
     * Check if the user is a guest
     *
     * @return bool
     */
    public function isGuest(): bool
    {
        return $this->roleName() === Role::GUEST;
    }

    /**
     * Check if the given user is the current user.
     *
     * @param string $userId the user uuid
     * @return bool
     */
    public function is(string $userId): bool
    {
        return $this->getId() === $userId;
    }

    /**
     * Convert the UserAccessControl data in array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'userId' => $this->userId,
            'rolename' => $this->roleName,
        ];
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
