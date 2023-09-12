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
 * @since         4.2.0
 */

namespace Passbolt\DirectorySync\Test\TestCase\Service\DirectorySettings;

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Lib\AppTestCase;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\DirectorySync\Service\DirectorySettings\FixDirectorySyncLegacyFieldsMappingService;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

/**
 * @covers \Passbolt\DirectorySync\Service\DirectorySettings\FixDirectorySyncLegacyFieldsMappingService
 */
class FixDirectorySyncLegacyFieldsMappingServiceTest extends AppTestCase
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\DirectorySync\Service\DirectorySettings\FixDirectorySyncLegacyFieldsMappingService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new FixDirectorySyncLegacyFieldsMappingService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testFixDirectorySyncLegacyFieldsMappingService_Success_NoSettingsInTheDatabase(): void
    {
        $fixed = $this->service->fix();

        $this->assertFalse($fixed);

        $result = OrganizationSettingFactory::find()
            ->where(['property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY])
            ->first();
        $this->assertNull($result);
    }

    public function testFixDirectorySyncLegacyFieldsMappingService_Success_SettingsCreatedAfterV4Migration(): void
    {
        $dummySettings = $this->getDummyDirectorySyncSettings('v4');
        // The settings should have been created after the migration to the v4.
        OrganizationSettingFactory::make(['created' => FrozenTime::now()->addDays(1)])
            ->setPropertyAndValue(DirectoryOrgSettings::ORG_SETTINGS_PROPERTY, json_encode($dummySettings))
            ->persist();

        $fixed = $this->service->fix();

        $this->assertFalse($fixed);
    }

    public function testFixDirectorySyncLegacyFieldsMappingService_Success_V3DefaultSettings(): void
    {
        $dummySettings = $this->getDummyDirectorySyncSettings();
        // The settings should have been created before the migration to the v4.
        OrganizationSettingFactory::make(['modified' => FrozenTime::now()->subYear()])
            ->setPropertyAndValue(DirectoryOrgSettings::ORG_SETTINGS_PROPERTY, json_encode($dummySettings))
            ->persist();

        $fixed = $this->service->fix();

        $this->assertTrue($fixed);

        // Assert fields mapping is updated to v4 default settings
        /** @var \App\Model\Entity\OrganizationSetting $result */
        $result = OrganizationSettingFactory::find()->where(
            ['property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY]
        )->first();
        $expectedFieldsMapping = DirectoryOrgSettings::getDefaultSettings()['fieldsMapping'];
        $fieldsMapping = json_decode($result['value'], true)['fieldsMapping'];
        $this->assertEqualsCanonicalizing($expectedFieldsMapping, $fieldsMapping);
    }

    public function testFixDirectorySyncLegacyFieldsMappingService_Error_UnsupportedCustomizedSettings(): void
    {
        $dummySettings = $this->getDummyDirectorySyncSettings();
        $dummySettings['fieldsMapping']['ad']['user']['username'] = 'userPrincipalName';
        // The settings should have been created before the migration to the v4.
        /** @var \App\Model\Entity\OrganizationSetting $directorySyncSetting */
        OrganizationSettingFactory::make(['modified' => FrozenTime::now()->subYear()])
            ->setPropertyAndValue(DirectoryOrgSettings::ORG_SETTINGS_PROPERTY, json_encode($dummySettings))
            ->persist();

        try {
            $this->service->fix();
        } catch (\Throwable $th) {
        }

        $this->assertInstanceOf(\UnexpectedValueException::class, $th);
        $this->assertTextContains('Customized v3 directory sync settings fields mapping are not supported:', $th->getMessage());
    }

    private function getDummyDirectorySyncSettings($fieldsMappingVersion = 'v3'): array
    {
        $fieldsMapping = FixDirectorySyncLegacyFieldsMappingService::getLegacyFieldsMapping();
        if ($fieldsMappingVersion === 'v4') {
            $fieldsMapping = DirectoryOrgSettings::getDefaultSettings()['fieldsMapping'];
        }

        return [
            'enabled' => true,
            'userPath' => 'CN=Operations',
            'defaultUser' => 'admin@passbolt.com',
            'defaultGroupAdminUser' => 'ada@passbolt.com',
            'groupObjectClass' => 'posixGroup',
            'userObjectClass' => 'inetOrgPerson',
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
            'fieldsMapping' => $fieldsMapping,
        ];
    }
}
