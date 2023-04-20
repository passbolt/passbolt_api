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

class ResourcesUpdateNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Gpgkeys',
        'app.Base/Favorites', 'app.Base/Profiles', 'app.Base/Roles',
        'app.Base/GroupsUsers', 'app.Base/Permissions',
    ];

    protected function _getGpgMessage()
    {
        return '-----BEGIN PGP MESSAGE-----

hQIMA1P90Qk1JHA+ARAAu3oaLzv/BfeukST6tYAkAID+xbt5dhsv4lxL3oSbo8Nm
qmJQSVe6wmh8nZJjeHN4L7iCq8FEZpdCwrDbX1qIuqBFFO3vx6BJFOURG0JbI/E/
nXtvck00RvxTB1Y30OUbGp21jjEILyuELhWpf11+AQelybY4XKyM8UxGjSncDqaS
X7/yXspCByywci1VfzK7D6+zfcyLy29wQm9Ci5j6I4QqhvlKQPTxl6tWrJh+EyLP
SLZjO8ofc00fbc7mUIH5taDg6Br2VLG/x29HhKCPYdOVzSz3BpUCcUcPgn98mCV0
Qh7ZPE1NNmCWXID5hryuSF71IiAYhxae9u77pOAbVe0PwFgMY6kke/hJQkO6IYJ/
/Q3aL/xHTlY2XtPbpV1in6soc0wJBuoROrwN0AdtvEJOnomclNEH5BPwLjZ1shCr
vuk0zJjj9WcqQiVNEuErs4d7rLc+dB7md+97S8Gtcf8lrlZMH9ooI2UnvxC8HRqX
KzcgW17YF44VtD2TLMymvpnjPV9gruYnmpkQG/1ihnDOWe6xWlFH6jZf5eE4IEVn
osx/D6inZHHMXWbZu9hMiQloKKZ0s8yxTFw9C1wFwaIxRtvJ84qc17rJs7mfcC2n
sG7jLzQBV/GVWtR4hVebstP+q05Sib+sKwLOTZhzWNPKruBsdaBCUTxcmI6qwDHS
QQFgGx0K1xQj2rKiP2j0cDHyGsWIlOITN+4r6Ohx23qRhVo0txPWVOYLpC8JnlfQ
W3AI8+rWjK8MGH2T88hCYI/6
=uahb
-----END PGP MESSAGE-----';
    }

    protected function _getDummyPostData($data = [])
    {
        $defaultData = [
            'Resource' => [
                'name' => 'new resource name',
                'username' => 'username@domain.com',
                'uri' => 'https://www.domain.com',
                'description' => 'new resource description',
            ],
            'Secret' => [
                [
                    'data' => $this->_getGpgMessage(),
                ],
            ],
        ];
        $data = array_merge($defaultData, $data);

        return $data;
    }

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

    public function testResourcesUpdateNotificationDisabled()
    {
        $this->setEmailNotificationSetting('send.password.update', false);

        // Get and update resource
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $data = [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
        ];

        // Post udpated data
        $this->authenticateAs('betty');
        $this->putJson("/resources/$resourceId.json?api-version=2", $data);
        $this->assertSuccess();

        // check email notification
        $this->assertEmailWithRecipientIsInNotQueue('betty@passbolt.com');
    }

    public function testResourcesUpdateNotificationSuccess()
    {
        $this->setEmailNotificationSetting('send.password.update', true);

        // Get and update resource
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $data = [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
        ];

        // Post udpated data
        $this->authenticateAs('betty');
        $this->putJson("/resources/$resourceId.json?api-version=2", $data);
        $this->assertSuccess();

        // check email notification
        $this->assertEmailInBatchContains('updated the password', 'ada@passbolt.com');

        // email should be sent to self as backup
        $this->assertEmailInBatchContains('updated the password', 'betty@passbolt.com');
    }
}
