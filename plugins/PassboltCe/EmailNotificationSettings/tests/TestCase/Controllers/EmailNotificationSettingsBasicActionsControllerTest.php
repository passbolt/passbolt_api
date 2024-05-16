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
 * @since         4.7.0
 */

namespace Passbolt\EmailNotificationSettings\Test\TestCase\Controllers;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\EmailNotificationSettings\Test\Factory\EmailNotificationSettingFactory;

class EmailNotificationSettingsBasicActionsControllerTest extends AppIntegrationTestCase
{
    public function testEmailNotificationSettingsBasicActionsController_View_Settings_On_Invalid_Email_Settings(): void
    {
        $invalidJsonString = '{foo: ';
        EmailNotificationSettingFactory::make()->setField('value', $invalidJsonString)->persist();
        $this->get('/settings.json');
        $this->assertResponseOk();
    }

    public function testEmailNotificationSettingsBasicActionsController_View_Avatar_On_Invalid_Email_Settings(): void
    {
        $invalidJsonString = '{foo: ';
        EmailNotificationSettingFactory::make()->setField('value', $invalidJsonString)->persist();
        $this->get('/avatars/view/75dc2799-a85c-47c1-8dff-c4ab3efb19ca/small.jpg');
        $this->assertResponseOk();
    }

    /**
     * This action triggers an email. Therefore, if the settings in the DB are not consistent, an error
     * is triggered
     */
    public function testEmailNotificationSettingsBasicActionsController_Create_Resource_On_Invalid_Email_Settings(): void
    {
        $invalidJsonString = '{foo: ';
        EmailNotificationSettingFactory::make()->setField('value', $invalidJsonString)->persist();

        $this->logInAsUser();
        $data = $this->getDummyResourcesPostData([
            'name' => '新的專用資源名稱',
            'username' => 'username@domain.com',
            'uri' => 'https://www.域.com',
            'description' => '新的資源描述',
        ]);
        $this->postJson('/resources.json', $data);
        $this->assertResponseError('Could not validate resource data.');
    }
}
