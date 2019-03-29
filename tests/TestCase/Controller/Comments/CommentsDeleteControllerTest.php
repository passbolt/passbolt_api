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

namespace App\Test\TestCase\Controller\Comments;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CommentsDeleteControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Comments'];

    public function testCommentsDeleteSuccess()
    {
        $this->authenticateAs('irene');
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->deleteJson("/comments/$commentId.json?api-version=2");
        $this->assertSuccess();
        $Comments = TableRegistry::getTableLocator()->get('Comments');
        $deletedComment = $Comments->find('all')->where(['Comments.id' => $commentId])->first();
        $this->assertempty($deletedComment);
    }

    public function testCommentsDeleteErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('irene');
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->delete("/comments/$commentId.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testCommentsDeleteErrorNotValidId()
    {
        $this->authenticateAs('irene');
        $commentId = 'invalid-id';
        $this->deleteJson("/comments/$commentId.json");
        $this->assertError(400, 'The comment id is not valid.');
    }

    public function testCommentsDeleteErrorCommentsNotFound()
    {
        $this->authenticateAs('irene');
        $commentId = UuidFactory::uuid();
        $this->deleteJson("/comments/$commentId.json");
        $this->assertError(404, 'The comment does not exist.');
    }

    public function testCommentsDeleteErrorCommentsNotOwner()
    {
        $this->authenticateAs('jean');
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->deleteJson("/comments/$commentId.json");
        $this->assertError(404, 'The comment does not exist.');
    }

    public function testCommentsDeleteErrorNotAuthenticated()
    {
        $commentId = UuidFactory::uuid('comment.id.apache-1');
        $this->deleteJson("/comments/$commentId.json?api-version=2");
        $this->assertAuthenticationError();
    }
}
