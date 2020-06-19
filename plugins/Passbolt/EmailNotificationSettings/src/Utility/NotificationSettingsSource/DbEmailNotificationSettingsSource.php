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
 * @since         2.13.0
 */

namespace Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource;

use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;
use function json_decode;

class DbEmailNotificationSettingsSource implements ReadableEmailNotificationSettingsSourceInterface, WriteableEmailNotificationSettingsSourceInterface
{
    const NAMESPACE = 'emailNotification';

    /**
     * @var OrganizationSettingsTable
     */
    private $organizationSettingsTable;

    /**
     * DbEmailNotificationSettingsSource constructor.
     */
    public function __construct()
    {
        $this->organizationSettingsTable = TableRegistry::getTableLocator()->get('OrganizationSettings');
    }

    /**
     * @param array $notificationSettingsData Notification settings data
     * @param UserAccessControl $uac Instance of user access control
     * @return void
     */
    public function write(array $notificationSettingsData, UserAccessControl $uac)
    {
        $data = json_encode($notificationSettingsData);

        // look for invalid structured string
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new InternalErrorException('The Email Notification Settings configs are invalid');
        }

        $this->organizationSettingsTable->createOrUpdateSetting(EmailNotificationSettings::NAMESPACE, $data, $uac);
    }

    /**
     * Return an array of notification settings with notification setting name as key and notification setting value as value.
     * Notification setting names must use the dotted key normalization.
     *
     * Get config setting from the database
     *
     * @return array
     * @throws RecordNotFoundException If a matching DB config doesn't exist
     * @throws InternalErrorException If the DB config is not valid json string
     */
    public function read()
    {
        $notificationSettingFromDb = $this->organizationSettingsTable->getFirstSettingOrFail(static::NAMESPACE);
        $settings = json_decode($notificationSettingFromDb->get('value'), true);

        // look for invalid structured string
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new InternalErrorException('The Email Notification Settings configs are invalid');
        }

        return $settings;
    }

    /**
     * Check if the table exists with a simple query to the database.
     * This check must be done before using this notification settings source to avoid
     * DB exception raised during installation because of queries run against the table when it does exist .
     * @return bool
     */
    public function isAvailable()
    {
        try {
            $this->organizationSettingsTable->exists([]);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }
}
