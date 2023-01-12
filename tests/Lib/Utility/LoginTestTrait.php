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
 * @since         3.8.0
 */
namespace App\Test\Lib\Utility;

use App\Model\Entity\User;
use App\Test\Factory\UserFactory;

/**
 * @depends on IntegrationTestTrait
 */
trait LoginTestTrait
{
    /**
     * @param User $user entity
     */
    public function logInAs(User $user): void
    {
        $this->session(['Auth' => compact('user')]);
    }

    /**
     * @return User entity
     * @throws \Exception
     */
    public function logInAsUser(): User
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        return $user;
    }

    /**
     * @return User entity
     * @throws \Exception
     */
    public function logInAsAdmin(): User
    {
        $user = UserFactory::make()->admin()->persist();
        $this->logInAs($user);

        return $user;
    }
}
