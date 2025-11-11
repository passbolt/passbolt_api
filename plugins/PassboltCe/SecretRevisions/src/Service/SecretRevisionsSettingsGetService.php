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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Service;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\SecretRevisions\Model\Dto\SecretRevisionsSettingsDto;

class SecretRevisionsSettingsGetService
{
    use LocatorAwareTrait;

    public const ORG_SETTING_PROPERTY = 'secretRevisions';

    /**
     * @var \Passbolt\SecretRevisions\Model\Dto\SecretRevisionsSettingsDto|null
     */
    private static ?SecretRevisionsSettingsDto $settings = null;

    /**
     * @return \Passbolt\SecretRevisions\Model\Dto\SecretRevisionsSettingsDto
     */
    public static function getDefaultSettings(): SecretRevisionsSettingsDto
    {
        $settings = self::defaultSettings();

        return new SecretRevisionsSettingsDto($settings['max_revisions'], $settings['allow_sharing_revisions']);
    }

    /**
     * @return array
     */
    public static function defaultSettings(): array
    {
        return [
            'max_revisions' => 1,
            'allow_sharing_revisions' => false,
        ];
    }

    /**
     * Read the secret revisions settings in the DB, or in file.
     * Validates the setting and return them.
     *
     * @return \Passbolt\SecretRevisions\Model\Dto\SecretRevisionsSettingsDto
     * @throws \App\Error\Exception\FormValidationException if the data does not validate
     */
    public static function getSettings(): SecretRevisionsSettingsDto
    {
        if (!is_null(self::$settings)) {
            return self::$settings;
        }

        try {
            /** @var \App\Model\Table\OrganizationSettingsTable $orgSettingsTable */
            $orgSettingsTable = TableRegistry::getTableLocator()->get('OrganizationSettings');
            $setting = $orgSettingsTable->getFirstSettingOrFail(self::ORG_SETTING_PROPERTY);

            // Deserialize and re-validate the settings
            if (!isset($setting->value) || !is_string($setting->value)) {
                throw new Exception('Invalid setting type');
            }
            $data = json_decode($setting->value, true, 2, JSON_THROW_ON_ERROR);

            self::$settings = (new SecretRevisionsSettingsAssertService())->assert($data);
        } catch (RecordNotFoundException) {
            self::$settings = self::getDefaultSettings();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            self::$settings = self::getDefaultSettings();
        }

        return self::$settings;
    }

    /**
     * Clears cached settings.
     *
     * @return void
     */
    public static function clear(): void
    {
        self::$settings = null;
    }
}
