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

namespace App\Test\TestCase\Controller\Notifications;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\Core\Configure;

class CommentsAddNotificationTest extends AppIntegrationTestCase
{
    public $Comments;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/resources', 'app.Base/comments', 'app.Base/profiles',
        'app.Alt0/permissions', 'app.Alt0/groups_users', 'app.Base/roles',
        'app.Base/email_queue', 'app.Base/avatars'
    ];

    public function testCommentsAddNotificationGroupSuccess()
    {
        Configure::write('passbolt.email.send.comment.add', true);
        $this->authenticateAs('dame');
        $postData = ['Comment' => ['content' => 'this is a test']];
        $this->postJson('/comments/resource/' . UuidFactory::uuid('resource.id.docker') . '.json?api-version=v1', $postData);
        $this->assertSuccess();

        // Every member of the group should get notification
        $this->get('/seleniumtests/showLastEmail/edith@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('commented on Docker');
        $this->assertResponseContains('this is a test');
        $this->get('/seleniumtests/showLastEmail/frances@passbolt.com');
        $this->assertResponseCode(200);
        $this->get('/seleniumtests/showLastEmail/grace@passbolt.com');
        $this->assertResponseCode(200);

        // except Dame
        $this->get('/seleniumtests/showLastEmail/dame@passbolt.com');
        $this->assertResponseCode(500);
    }

    public function testCommentsAddNotificationUserSuccess()
    {
        Configure::write('passbolt.email.send.comment.add', true);
        $this->authenticateAs('betty');
        $postData = ['Comment' => ['content' => 'this is a test']];
        $this->postJson('/comments/resource/' . UuidFactory::uuid('resource.id.bower') . '.json?api-version=v1', $postData);
        $this->assertSuccess();

        // Every users with direct permissions should get notified
        $this->get('/seleniumtests/showLastEmail/dame@passbolt.com');
        $this->assertResponseCode(200);
        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(200);
        $this->get('/seleniumtests/showLastEmail/frances@passbolt.com');
        $this->assertResponseCode(200);

        // except Dame
        $this->get('/seleniumtests/showLastEmail/betty@passbolt.com');
        $this->assertResponseCode(500);
    }

    public function testCommentsAddNotificationDoNotShowContent()
    {
        Configure::write('passbolt.email.send.comment.add', true);
        Configure::write('passbolt.email.show.comment', false);
        $this->authenticateAs('betty');
        $postData = ['Comment' => ['content' => 'this is a test']];
        $this->postJson('/comments/resource/' . UuidFactory::uuid('resource.id.bower') . '.json?api-version=v1', $postData);
        $this->assertSuccess();

        // Every users with direct permissions should get notified
        $this->get('/seleniumtests/showLastEmail/dame@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseNotContains('this is a test');
    }

    public function testCommentsAddNotificationDisabled()
    {
        Configure::write('passbolt.email.send.comment.add', false);
        $this->authenticateAs('betty');
        $postData = ['Comment' => ['content' => 'this is a test']];
        $this->postJson('/comments/resource/' . UuidFactory::uuid('resource.id.bower') . '.json?api-version=v1', $postData);
        $this->assertSuccess();

        // Nobody should get notifications
        $this->get('/seleniumtests/showLastEmail/dame@passbolt.com');
        $this->assertResponseCode(500);
        $this->get('/seleniumtests/showLastEmail/ada@passbolt.com');
        $this->assertResponseCode(500);
    }
}
