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
namespace Passbolt\DirectorySync\Service\DirectorySettings;

use App\Model\Entity\OrganizationSetting;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class FixDirectorySyncLegacyFieldsMappingService
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\OrganizationSettingsTable
     */
    private \App\Model\Table\OrganizationSettingsTable $organizationSettingsTable;

    /**
     * @var \Cake\ORM\Table
     */
    private \Cake\ORM\Table $phinxlogTable;

    /**
     * FixDirectorySyncLegacyFieldsMappingService constructor
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->organizationSettingsTable = $this->fetchTable('OrganizationSettings');
        $this->phinxlogTable = $this->fetchTable('Phinxlog', ['table' => 'phinxlog']);
    }

    /**
     * Find the directory sync settings.
     *
     * @return \App\Model\Entity\OrganizationSetting|null
     */
    private function findDirectorySyncSettings()
    {
        /** @var \App\Model\Entity\OrganizationSetting|null */
        return $this->organizationSettingsTable
            ->find()
            ->where([
                'property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY,
            ])->first();
    }

    /**
     * Check if the directory sync settings were created with a v3.
     *
     * @param \App\Model\Entity\OrganizationSetting $directorySyncSetting The directory sync settings
     * @return bool
     * @throws \Exception If the migration V400ChangeLdapServersConfigKey cannot be found
     * @throws \Exception If the migration V400ChangeLdapServersConfigKey format is invalid
     */
    private function isDirectorySyncSettingsCreatedWithV3(OrganizationSetting $directorySyncSetting): bool
    {
        /** @var \Cake\ORM\Entity|null $migration */
        $migration = $this->phinxlogTable->find()
            ->where(['migration_name' => 'V400ChangeLdapServersConfigKey'])
            ->first();

        if (is_null($migration)) {
            throw new \Exception('Unable to retrieve the migration V400ChangeLdapServersConfigKey.');
        }
        if (property_exists($migration, 'end_time')) {
            throw new \Exception('Invalid phinxlog migration entity, end_time property not defined.');
        }

        /** @phpstan-ignore-next-line */
        return $directorySyncSetting->created->lessThan($migration->end_time);
    }

    /**
     * Get and assert the directory sync settings.
     *
     * @param \App\Model\Entity\OrganizationSetting $directorySyncSettings The directory sync settings
     * @return array
     * @throws \UnexpectedValueException If the directory sync settings are invalid
     */
    private function getAndAssertDirectorySyncDefaultV3FieldsMapping(OrganizationSetting $directorySyncSettings): array
    {
        $value = json_decode($directorySyncSettings->value, true);

        if (
            !$value
            || !is_array($value)
            || !isset($value['fieldsMapping'])
            || count($value['fieldsMapping']) !== 2
        ) {
            $errorMessage = "Directory settings are invalid: {$directorySyncSettings->value}";
            throw new \UnexpectedValueException($errorMessage);
        }

        $fieldsMapping = $value['fieldsMapping'];
        $legacyFieldsMapping = self::getLegacyFieldsMapping();
        $v3DiffFieldsMapping = array_diff(Hash::flatten($fieldsMapping), Hash::flatten($legacyFieldsMapping));

        if (!empty($v3DiffFieldsMapping)) {
            $errorMessage = 'Customized v3 directory sync settings fields mapping are not supported: ';
            $errorMessage .= $directorySyncSettings->value;
            throw new \UnexpectedValueException($errorMessage);
        }

        return $value;
    }

    /**
     * Fixes fields mapping in the database for those who upgraded from v3 to v4.
     *
     * @return bool True if the settings where fixed, false otherwise.
     * @throws \UnexpectedValueException If directory settings stored are invalid and cannot be parsed
     * @throws \Cake\ORM\Exception\PersistenceFailedException When the settings couldn't be saved
     * @throws \Exception If the migration V400ChangeLdapServersConfigKey format is invalid or not found
     * @throws \UnexpectedValueException If the directory sync settings are invalid
     */
    public function fix(): bool
    {
        $directorySyncSettings = $this->findDirectorySyncSettings();

        if (!$directorySyncSettings) {
            // Don't do anything if settings are not present in the database.
            return false;
        }

        if (!$this->isDirectorySyncSettingsCreatedWithV3($directorySyncSettings)) {
            // Don't do anything if settings where not created with v3.
            return false;
        }

        $directorySyncSettingsValue = $this->getAndAssertDirectorySyncDefaultV3FieldsMapping($directorySyncSettings);

        $v4FieldsMapping = DirectoryOrgSettings::getDefaultSettings()['fieldsMapping'];
        $directorySyncSettingsValue['fieldsMapping'] = $v4FieldsMapping;

        $directorySyncSettings->set('value', json_encode($directorySyncSettingsValue));
        $this->organizationSettingsTable->saveOrFail($directorySyncSettings);

        return true;
    }

    /**
     * @return array
     */
    public static function getLegacyFieldsMapping(): array
    {
        return [
            'ad' => [
                'user' => [
                    'id' => 'guid',
                    'firstname' => 'firstName',
                    'lastname' => 'lastName',
                    'username' => 'emailAddress',
                    'created' => 'created',
                    'modified' => 'modified',
                    'groups' => 'groups',
                    'enabled' => 'enabled',
                ],
                'group' => [
                    'id' => 'guid',
                    'name' => 'name',
                    'created' => 'created',
                    'modified' => 'modified',
                    'users' => 'members',
                ],
            ],
            'openldap' => [
                'user' => [
                    'id' => 'guid',
                    'firstname' => 'firstName',
                    'lastname' => 'lastName',
                    'username' => 'emailAddress',
                    'created' => 'created',
                    'modified' => 'modified',
                ],
                'group' => [
                    'id' => 'guid',
                    'name' => 'name',
                    'created' => 'created',
                    'modified' => 'modified',
                    'users' => 'members',
                ],
            ],
        ];
    }
}
