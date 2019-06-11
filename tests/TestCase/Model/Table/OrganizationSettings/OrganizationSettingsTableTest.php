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

namespace Passbolt\OrganizationSettings\Test\TestCase\Model\Table;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use App\Model\Table\OrganizationSettingsTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\TableRegistry;

/**
 * Passbolt\OrganizationSettings\Model\Table\OrganizationSettingsTable Test Case
 */
class OrganizationSettingsTableTest extends AppTestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OrganizationSettingsTable
     */
    public $OrganizationSettings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Base/OrganizationSettings',
    ];

    /**
     * Get test settings.
     * @return array sample settings.
     */
    protected function _getTestSettings()
    {
        $organizationSettings = [
            'passbolt' => [
                'ldap' => [
                    'testSettingOne' => 'value1',
                    'testSettingTwo' => 'value2',
                ],
                'emailNotifications' => [
                    'create' => true,
                ]
            ]
        ];

        return $organizationSettings;
    }

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('OrganizationSettings') ? [] : ['className' => OrganizationSettingsTable::class];
        $this->OrganizationSettings = TableRegistry::get('OrganizationSettings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OrganizationSettings);

        parent::tearDown();
    }

    /**
     * Test create or update settings in normal conditions.
     *
     * @group model
     * @group OrganizationSettings
     * @return void
     */
    public function testOrganizationSettingsCreateOrUpdateSettingsOk()
    {
        $accessControl = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $settingItem = $this->OrganizationSettings->createOrUpdateSetting('test.property', 'testvalue', $accessControl);
        $this->assertNotEmpty($settingItem);
        $this->assertEquals($settingItem->property, 'test.property');
    }

    /**
     * Test create or update settings 2 times with the same property and assert that object is not duplicate.
     *
     * @group model
     * @group OrganizationSettings
     * @return void
     */
    public function testOrganizationSettingsCreateOrUpdateNoDuplicate()
    {
        $accessControl = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $this->OrganizationSettings->createOrUpdateSetting('test.property', 'testvalue', $accessControl);
        $this->OrganizationSettings->createOrUpdateSetting('test.property', 'testvalue1', $accessControl);

        $res = $this->OrganizationSettings->find()->all()->toArray();
        $this->assertEquals(count($res), 1);
        $this->assertEquals($res[0]->value, 'testvalue1');
    }

    /**
     * Test create or update settings with a validation exception.
     *
     * @group model
     * @group OrganizationSettings
     */
    public function testOrganizationSettingsCreateOrUpdateValidationError()
    {
        $accessControl = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $this->expectException(CustomValidationException::class);
        $this->OrganizationSettings->createOrUpdateSetting('test.property1', 'testvalue', $accessControl);
    }

    /**
     * Test create or update settings with an unauthorized user.
     *
     * @group model
     * @group OrganizationSettings
     */
    public function testOrganizationSettingsCreateOrUpdateUnauthorizedValidationError()
    {
        $accessControl = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $this->expectException(UnauthorizedException::class);
        $this->OrganizationSettings->createOrUpdateSetting('test.property1', 'testvalue', $accessControl);
    }

    /**
     * Test get a property that does not exist.
     *
     * @group model
     * @group OrganizationSettings
     */
    public function testOrganizationSettingsGetPropertyNotExist()
    {
        $this->expectException(RecordNotFoundException::class);
        $this->OrganizationSettings->getFirstSettingOrFail('test.property1DoesNotExist');
    }

    /**
     * Test get a property that exists.
     *
     * @group model
     * @group OrganizationSettings
     */
    public function testOrganizationSettingsGetPropertyOk()
    {
        $accessControl = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $this->OrganizationSettings->createOrUpdateSetting('test.property', 'testvalue', $accessControl);
        $property = $this->OrganizationSettings->getFirstSettingOrFail('test.property');
        $this->assertEquals($property->value, 'testvalue');
    }
}
