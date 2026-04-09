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

namespace App\Test\TestCase\Model\Table\Comments;

use App\Model\Table\CommentsTable;
use App\Test\Factory\CommentFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class DeleteTest extends AppTestCase
{
    public $Comments;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Comments') ? [] : ['className' => CommentsTable::class];
        $this->Comments = TableRegistry::getTableLocator()->get('Comments', $config);
    }

    public function tearDown(): void
    {
        unset($this->Comments);

        parent::tearDown();
    }

    public function testSuccess()
    {
        $user = UserFactory::make()->persist();
        $commentId = CommentFactory::make()->withUser($user)->persist()->id;
        $comment = $this->Comments->get($commentId);
        $delete = $this->Comments->delete($comment, ['Comments.user_id' => $user->id]);
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
        $owner = UserFactory::make()->persist();
        $otherUser = UserFactory::make()->persist();
        $commentId = CommentFactory::make()->withUser($owner)->persist()->id;
        $comment = $this->Comments->get($commentId);
        $delete = $this->Comments->delete($comment, ['Comments.user_id' => $otherUser->id]);
        $this->assertFalse($delete);
        $errors = $comment->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty(Hash::get($errors, 'user_id.is_owner'));
    }
}
