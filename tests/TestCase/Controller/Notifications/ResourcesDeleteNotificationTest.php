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
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class ResourcesDeleteNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Resources', 'app.Base/Secrets',
        'app.Base/Favorites', 'app.Base/Profiles', 'app.Base/Roles',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions', 'app.Base/Gpgkeys',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->loadNotificationSettings();
    }

    public function tearDown(): void
    {
        $this->unloadNotificationSettings();
        parent::tearDown();
    }

    public function testResourcesDeleteNotificationDisabled()
    {
        $this->setEmailNotificationSetting('send.password.delete', false);

        $this->authenticateAs('ada');
        $this->deleteJson('/resources/' . UuidFactory::uuid('resource.id.april') . '.json');
        $this->assertSuccess();

        // check email notification
        $this->assertEmailWithRecipientIsInNotQueue('betty@passbolt.com');
    }

    public function testResourcesDeleteNotificationSuccess()
    {
        $this->setEmailNotificationSetting('send.password.delete', true);

        $this->authenticateAs('ada');
        $this->deleteJson('/resources/' . UuidFactory::uuid('resource.id.april') . '.json');
        $this->assertSuccess();

        // check email notification
        $this->assertEmailInBatchContains('deleted the password april', 'betty@passbolt.com');
    }
}
