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
 * @since         3.11.0
 */

namespace Passbolt\DirectorySync\Test\TestCase\Service\DirectorySettings;

use App\Model\Entity\Role;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Passbolt\DirectorySync\Service\DirectorySettings\UpdateDirectorySettingsService;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

/**
 * @see UpdateDirectorySettingsService
 */
class UpdateDirectorySettingsServiceTest extends AppTestCase
{
    /**
     * @var UpdateDirectorySettingsService
     */
    private UpdateDirectorySettingsService $service;

    /**
     * @var Table
     */
    private Table $table;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new UpdateDirectorySettingsService();
        $this->table = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $oldConfig = [
            'enabled' => true,
            'userPath' => 'CN=Operations',
            'defaultUser' => 'admin@passbolt.com',
            'defaultGroupAdminUser' => 'ada@passbolt.com',
            'groupObjectClass' => 'posixGroup',
            'jobs' => [
                'users' => [
                    'create' => true,
                    'delete' => false,
                    'update' => true,
                ],
                'groups' => [
                    'create' => true,
                    'delete' => false,
                    'update' => true,
                ],
            ],
            'ldap' => [
                'domains' => [
                    'org_domain' => [
                        'domain_name' => 'passbolt.local',
                        'username' => 'root',
                        'password' => 'password',
                        'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
                        'servers' => ['127.0.0.1'],
                        'port' => 636,
                        'use_ssl' => true,
                        'use_tls' => false,
                        'ldap_type' => 'ad',
                        'use_sasl' => false,
                    ],
                ],
            ],
        ];
        $this->updateSettings($oldConfig);
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testUpdateDirectorySettingsService_updateSettings_emptySettings(): void
    {
        $conditions = ['property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY];
        $this->table->deleteAll($conditions);
        $this->service->updateSettings();
        $this->assertNull($this->table->find()->where($conditions)->first());
    }

    /**
     * Update settings onto db
     *
     * @param array $settings
     * @return void
     */
    protected function updateSettings(array $settings)
    {
        $uac = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $directoryOrgSettings = new DirectoryOrgSettings($settings);
        $directoryOrgSettings->save($uac);
    }

    /**
     * Test wrong format settings
     *
     * @return void
     */
    public function testUpdateDirectorySettingsService_updateSettings_wrongFormatSettings(): void
    {
        $settings = $this->table->find()->where(['property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY])->first();
        $settings->set('value', 'wrong_json');
        $this->table->save($settings);
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(__('Directory Settings are invalid. Please check your config and try again.'));
        $this->service->updateSettings();
    }

    /**
     * Test expected format settings
     *
     * @return void
     */
    public function testUpdateDirectorySettingsService_updateSettings_expectedFormatSettings(): void
    {
        /** @var \App\Model\Entity\OrganizationSetting $settings */
        $settings = $this->table->find()->where(['property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY])->first();
        $data = json_decode($settings->value, true);
        $this->assertArrayHasKey('servers', $data['ldap']['domains']['org_domain']);
        $this->assertArrayNotHasKey('hosts', $data['ldap']['domains']['org_domain']);
        $servers = $data['ldap']['domains']['org_domain']['servers'];
        $groupObjectClass = $data['groupObjectClass'];
        $this->service->updateSettings();

        /** @var \App\Model\Entity\OrganizationSetting $settings */
        $settings = $this->table->find()->where(['property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY])->first();
        $data = json_decode($settings->value, true);
        $this->assertArrayHasKey('hosts', $data['ldap']['domains']['org_domain']);
        $this->assertArrayNotHasKey('servers', $data['ldap']['domains']['org_domain']);
        $this->assertSame($servers, $data['ldap']['domains']['org_domain']['hosts']);
        $this->assertSame($groupObjectClass, $data['groupObjectClass']);
        $this->assertFalse($data['enabled']);
    }

    /**
     * Test expected format settings
     *
     * @return void
     */
    public function testUpdateDirectorySettingsService_updateSettings_emptyGroupObjectClass(): void
    {
        /** @var \App\Model\Entity\OrganizationSetting $settings */
        $settings = $this->table->find()->where(['property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY])->first();
        $data = json_decode($settings->value, true);
        unset($data['groupObjectClass']);
        $settings->set('value', json_encode($data));
        $this->table->save($settings);
        $this->service->updateSettings();

        /** @var \App\Model\Entity\OrganizationSetting $settings */
        $settings = $this->table->find()->where(['property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY])->first();
        $data = json_decode($settings->value, true);
        $this->assertSame('groupOfNames', $data['groupObjectClass']);
    }
}
