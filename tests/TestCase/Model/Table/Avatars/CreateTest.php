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

namespace App\Test\TestCase\Model\Table\Avatars;

use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CreateTest extends AppTestCase
{
    public $Avatars;

    public $fixtures = ['app.Base/users', 'app.Base/profiles', 'app.Base/avatars'];

    public function setUp()
    {
        parent::setUp();
        $this->Avatars = TableRegistry::get('Avatars');
    }

    public function tearDown()
    {
        unset($this->Avatars);
        parent::tearDown();
    }

    private function _createAvatar($name = 'ada')
    {
        $userAvatarFullPath = PASSBOLT_TEST_DATA_AVATAR_PATH . DS . $name . '.png';

        $data = [
            'file' => [
                'tmp_name' => $userAvatarFullPath,
                'name' => $name . '.png',
            ],
            'user_id' => UuidFactory::uuid('user.id.' . $name),
            'foreign_key' => UuidFactory::uuid('profile.id.' . $name),
        ];

        $entity = $this->Avatars->newEntity();
        $entity = $this->Avatars->patchEntity($entity, $data);
        $avatar = $this->Avatars->save($entity);

        return $avatar;
    }

    public function testCreateFileIsCreated()
    {
        $avatar = $this->_createAvatar('ada');
        $errors = $avatar->getErrors();

        $this->assertTrue(empty($errors));
        $avatar = $avatar->toArray();
        $this->assertTrue(file_exists(WWW_ROOT . $avatar['url']['small']));
        $this->assertTrue(file_exists(WWW_ROOT . $avatar['url']['medium']));
    }

    public function testDeleteFormerVersionAfterCreate()
    {
        $avatar = $this->_createAvatar('ada');
        $errors = $avatar->getErrors();
        $this->assertTrue(empty($errors));
        $avatar = $avatar->toArray();
        $this->assertTrue(file_exists(WWW_ROOT . $avatar['url']['small']));
        $this->assertTrue(file_exists(WWW_ROOT . $avatar['url']['medium']));

        $avatar1 = $this->_createAvatar('ada');
        $errors = $avatar1->getErrors();
        $this->assertTrue(empty($errors));
        $avatar1 = $avatar1->toArray();
        $this->assertTrue(file_exists(WWW_ROOT . $avatar1['url']['small']));
        $this->assertTrue(file_exists(WWW_ROOT . $avatar1['url']['medium']));

        // Assert that the previous avatar files have been deleted.
        $this->assertFalse(file_exists(WWW_ROOT . $avatar['url']['small']));
        $this->assertFalse(file_exists(WWW_ROOT . $avatar['url']['medium']));
    }
}
