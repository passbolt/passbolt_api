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

namespace App\Test\TestCase\Model\Table\Avatars;

use App\Model\Table\AvatarsTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class FindTest extends AppTestCase
{
    public $Avatars;

    public $fixtures = ['app.Base/Users', 'app.Base/Profiles', 'app.Base/Avatars'];

    public function setUp()
    {
        parent::setUp();
        $this->Avatars = TableRegistry::getTableLocator()->get('Avatars');
    }

    public function tearDown()
    {
        unset($this->Avatars);

        parent::tearDown();
    }

    public function testAvatarFindContainDefaultAvatar()
    {
        $Users = TableRegistry::getTableLocator()->get('Users');
        $adminUser = $Users->find()
            ->where(['Users.id' => UuidFactory::uuid('user.id.admin')])
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar()
            ])
            ->first();

        $adminUser = $adminUser->toArray();
        $this->assertTrue(!empty(Hash::get($adminUser, 'profile.avatar.url.medium')));
        $this->assertTrue(!empty(Hash::get($adminUser, 'profile.avatar.url.small')));
        $this->assertEquals(Hash::get($adminUser, 'profile.avatar.url.small'), Configure::read('FileStorage.imageDefaults.Avatar.small'));
        $this->assertEquals(Hash::get($adminUser, 'profile.avatar.url.medium'), Configure::read('FileStorage.imageDefaults.Avatar.medium'));
    }

    public function testFindContainExistingAvatar()
    {
        $this->loadPlugins(['Burzum/FileStorage', 'Burzum/Imagine']);
        $Users = TableRegistry::getTableLocator()->get('Users');
        $adaUser = $Users->find()
            ->where(['Users.id' => UuidFactory::uuid('user.id.ada')])
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar()
            ])
            ->first();
        $this->assertTrue(!empty(Hash::get($adaUser, 'profile.avatar.url.medium')));
        $this->assertTrue(!empty(Hash::get($adaUser, 'profile.avatar.url.small')));
        $this->assertNotEquals(Hash::get($adaUser, 'profile.avatar.url.small'), Configure::read('FileStorage.imageDefaults.Avatar.small'));
        $this->assertNotEquals(Hash::get($adaUser, 'profile.avatar.url.medium'), Configure::read('FileStorage.imageDefaults.Avatar.medium'));
        $this->assertTextContains('img/public/Avatar', Hash::get($adaUser, 'profile.avatar.url.medium'));
    }
}
