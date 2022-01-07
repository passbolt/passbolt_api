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
 * @since         3.4.0
 */

namespace App\Test\TestCase\Model\Table\Avatars;

use App\Model\Table\AvatarsTable;
use App\Test\Factory\UserFactory;
use Cake\Datasource\ModelAwareTrait;
use Cake\TestSuite\TestCase;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class AddContainAvatarTest extends TestCase
{
    use ModelAwareTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadModel('Users');
    }

    public function testAvatarsTableAddContainAvatar_Should_Not_Retrieve_Avatar_Data()
    {
        $user = UserFactory::make()->withAvatar()->user()->persist();

        /** @var \App\Model\Entity\User $retrievedUser */
        $retrievedUser = $this->Users->find()
            ->where(['Users.id' => $user->id])
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar(),
            ])
            ->firstOrFail();

        $this->assertSame($user->profile->avatar->id, $retrievedUser->profile->avatar->id);
        $this->assertNotNull($user->profile->avatar->data);
        $this->assertNull($retrievedUser->profile->avatar->data ?? null);
    }

    public function testAvatarsTableAddContainAvatar_On_Empty_Avatar()
    {
        $user = UserFactory::make()->user()->persist();

        /** @var \App\Model\Entity\User $retrievedUser */
        $retrievedUser = $this->Users->find()
            ->where(['Users.id' => $user->id])
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar(),
            ])
            ->firstOrFail();

        $this->assertNull($user->profile->avatar->id ?? null);
        $this->assertNull($user->profile->avatar->data ?? null);
        $this->assertNull($retrievedUser->profile->avatar->id ?? null);
        $this->assertNull($retrievedUser->profile->avatar->data ?? null);
        $this->assertIsArray($retrievedUser->profile->avatar->url);
    }
}
