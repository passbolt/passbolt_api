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

namespace App\Test\TestCase\Controller\Comments;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CommentsDeleteControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/users', 'app.Base/groups', 'app.Base/groups_users', 'app.Base/resources', 'app.Base/secrets', 'app.Base/comments'];

    public function testDeleteSuccess()
    {
        $this->authenticateAs('irene');
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->deleteJson("/comments/$commentId.json?api-version=2");
        $this->assertSuccess();
        $Comments = TableRegistry::get('Comments');
        $deletedComment = $Comments->find('all')->where(['Comments.id' => $commentId])->first();
        $this->assertempty($deletedComment);
    }

    public function testDeleteErrorNotValidId()
    {
        $this->authenticateAs('irene');
        $commentId = 'invalid-id';
        $this->deleteJson("/comments/$commentId.json?api-version=v1");
        $this->assertError(400, 'The comment id is not valid.');
    }

    public function testDeleteErrorCommentsNotFound()
    {
        $this->authenticateAs('irene');
        $commentId = UuidFactory::uuid();
        $this->deleteJson("/comments/$commentId.json?api-version=v1");
        $this->assertError(404, 'The comment does not exist.');
    }

    public function testDeleteErrorCommentsNotOwner()
    {
        $this->authenticateAs('jean');
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->deleteJson("/comments/$commentId.json?api-version=v1");
        $this->assertError(404, 'The comment does not exist.');
    }

    public function testDeleteErrorNotAuthenticated()
    {
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->deleteJson("/comments/$commentId.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
