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

namespace Passbolt\Locale\Test\TestCase\Notification;

use App\Model\Entity\Permission;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\ResourceTypeFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\Locale\Service\GetOrgLocaleService;

/**
 * Class ResourcesAddAndShareControllerTest
 */
class ResourcesAddAndShareControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        ResourceTypeFactory::make()->default()->persist();
        GetOrgLocaleService::clearOrganisationLocale();
        $this->setEmailNotificationsSetting('password.create', true);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->restoreEmailNotificationsSettings();
    }

    public function testResourcesAdd_Should_Send_Email_In_User_Locale()
    {
        $frenchLocale = 'fr-FR';
        $frenchUser = UserFactory::make()->user()->withLocale($frenchLocale)->persist();

        $this->logInAs($frenchUser);

        $data = $this->getDummyResourcesPostData([
            'name' => '新的專用資源名稱',
            'username' => 'username@domain.com',
            'uri' => 'https://www.域.com',
            'description' => '新的資源描述',
        ]);

        $this->postJson('/resources.json?api-version=2', $data);
        $this->assertSuccess();
        $this->assertEmailQueueCount(1);
        $this->assetEmailLocale($frenchUser->username, $frenchLocale);
    }

    public function testResourcesShare_Should_Send_Email_In_User_Locale()
    {
        $frenchLocale = 'fr-FR';
        $englishLocale = GetOrgLocaleService::DEFAULT_LOCALE;
        $this->assertSame($englishLocale, GetOrgLocaleService::getLocale());

        $defaultUser = UserFactory::make()->user()->persist();
        $frenchUser = UserFactory::make()->user()->withLocale($frenchLocale)->persist();
        $englishUser = UserFactory::make()->user()->withLocale($englishLocale)->persist();
        $frenchUser2 = UserFactory::make()->user()->withLocale($frenchLocale)->persist();

        $resource = ResourceFactory::make()->withCreatorAndPermission($frenchUser)->persist();

        $data = [];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $defaultUser->id, 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $englishUser->id, 'type' => Permission::OWNER];
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $frenchUser2->id, 'type' => Permission::OWNER];
        $data['secrets'][] = ['user_id' => $defaultUser->id, 'data' => SecretFactory::make()->getEntity()->data];
        $data['secrets'][] = ['user_id' => $englishUser->id, 'data' => SecretFactory::make()->getEntity()->data];
        $data['secrets'][] = ['user_id' => $frenchUser2->id, 'data' => SecretFactory::make()->getEntity()->data];

        $this->logInAs($frenchUser);
        $this->putJson("/share/resource/{$resource->id}.json?api-version=v2", $data);
        $this->assertResponseOk();

        $this->assertEmailQueueCount(3);
        $this->assetEmailLocale($defaultUser->username, $englishLocale);
        $this->assetEmailLocale($englishUser->username, $englishLocale);
        $this->assetEmailLocale($frenchUser2->username, $frenchLocale);
    }
}
