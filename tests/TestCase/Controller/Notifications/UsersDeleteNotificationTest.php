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
namespace App\Test\TestCase\Controller\Notifications;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;

class UsersDeleteNotificationTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/Resources', 'app.Base/Favorites', 'app.Base/Secrets',
        'app.Base/GroupsUsers', 'app.Base/Permissions',
    ];

    public function testUsersDeleteNotificationSuccess()
    {
        $this->authenticateAs('admin');
        $francesId = UuidFactory::uuid('user.id.ursula');
        $this->deleteJson('/users/' . $francesId . '.json');
        $this->assertSuccess();

        $this->assertEmailInBatchContains('deleted the user Ursula', 'ping@passbolt.com');
        $this->assertEmailInBatchContains('Human resource', 'ping@passbolt.com');
        $this->assertEmailInBatchContains('IT support', 'ping@passbolt.com');

        $this->assertEmailInBatchContains('deleted the user Ursula', 'thelma@passbolt.com');
        $this->assertEmailInBatchContains('Human resource', 'thelma@passbolt.com');
        $this->assertEmailInBatchNotContains('IT support', 'thelma@passbolt.com');

        $this->assertEmailWithRecipientIsInNotQueue('wang@passbolt.com');

        // Two mails should be in the queue
        $this->assertEmailQueueCount(2);
    }
}
