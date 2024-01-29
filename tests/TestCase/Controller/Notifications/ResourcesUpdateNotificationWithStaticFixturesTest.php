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
 * @since         4.3.0
 */

namespace App\Test\TestCase\Controller\Notifications;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class ResourcesUpdateNotificationWithStaticFixturesTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Gpgkeys',
        'app.Base/Favorites', 'app.Base/Profiles', 'app.Base/Roles',
        'app.Base/GroupsUsers', 'app.Base/Permissions',
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

    public function testResourcesUpdateNotificationSuccess_Secrets(): void
    {
        $this->setEmailNotificationSetting('send.password.update', true);
        // Get and update resource
        $resourceId = UuidFactory::uuid('resource.id.apache');
        // Prepare secrets
        $bettyId = UuidFactory::uuid('user.id.betty');
        $bettyEncryptedSecret = $this->encryptMessageFor($bettyId, 'R1 secret updated');
        $carolId = UuidFactory::uuid('user.id.carol');
        $carolEncryptedSecret = $this->encryptMessageFor($carolId, 'R1 secret updated');
        $dameId = UuidFactory::uuid('user.id.dame');
        $dameEncryptedSecret = $this->encryptMessageFor($dameId, 'R1 secret updated');
        $adaId = UuidFactory::uuid('user.id.ada');
        $adaEncryptedSecret = $this->encryptMessageFor($adaId, 'R1 secret updated');
        $data = [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
            'secrets' => [
                ['user_id' => $bettyId, 'data' => $bettyEncryptedSecret],
                ['user_id' => $carolId, 'data' => $carolEncryptedSecret],
                ['user_id' => $dameId, 'data' => $dameEncryptedSecret],
                ['user_id' => $adaId, 'data' => $adaEncryptedSecret],
            ],
        ];
        $this->authenticateAs('betty');

        $this->putJson("/resources/{$resourceId}.json", $data);

        $this->assertSuccess();
        // Assert email contents
        $this->assertEmailInBatchContains('edited the password', 'ada@passbolt.com');
        $this->assertEmailInBatchContains('edited the password', 'betty@passbolt.com');
        $this->assertEmailInBatchContains('edited the password', 'carol@passbolt.com');
        $this->assertEmailInBatchContains('edited the password', 'dame@passbolt.com');
    }
}
