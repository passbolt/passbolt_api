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

namespace App\Test\TestCase\Model\Table\Comments;

use App\Model\Table\CommentsTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class DeleteTest extends AppTestCase
{
    public $Comments;

    public $fixtures = ['app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Comments', 'app.Base/Permissions'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Comments') ? [] : ['className' => CommentsTable::class];
        $this->Comments = TableRegistry::getTableLocator()->get('Comments', $config);
    }

    public function tearDown()
    {
        unset($this->Comments);

        parent::tearDown();
    }

    public function testSuccess()
    {
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $comment = $this->Comments->get($commentId);
        $delete = $this->Comments->delete($comment, ['Comments.user_id' => UuidFactory::uuid('user.id.irene')]);
        $this->assertTrue($delete);

        try {
            $this->Comments->get($commentId);
            $this->assertFalse(true);
        } catch (RecordNotFoundException $e) {
            $this->assertTrue(true);
        }
    }

    public function testDeleteErrorIsOwnerRule()
    {
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $comment = $this->Comments->get($commentId);
        $delete = $this->Comments->delete($comment, ['Comments.user_id' => UuidFactory::uuid('user.id.ada')]);
        $this->assertFalse($delete);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty(Hash::get($errors, 'user_id.is_owner'));
    }
}
