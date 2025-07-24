<?php
declare(strict_types=1);

namespace Passbolt\Scim\Utility;

use App\Model\Entity\OrganizationSetting;
use App\Model\Entity\User;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Passbolt\Scim\Service\ScimBaseSettingsService;

/**
 * Utility class
 */
class ScimTools
{
    public const API_URL_PLACEHOLDER = '{scimUrl}';
    public const API_FORMAT_DATETIME = 'Y-m-d\TH:i:s.v\Z';

    /**
     * @param string $json
     * @param string $settingId
     * @return string
     */
    public static function replacePlaceholders(string $json, string $settingId): string
    {
        return str_replace(self::API_URL_PLACEHOLDER, Router::url('scim/v2/' . $settingId, true), $json);
    }

    /**
     * @param \Cake\I18n\DateTime $dateTime
     * @return string
     */
    public static function formatDateTimeToScim(DateTime $dateTime): string
    {
        return $dateTime->format(self::API_FORMAT_DATETIME);
    }

    /**
     * @return \App\Model\Entity\OrganizationSetting|null
     */
    public static function getScimOrgSettings(): ?OrganizationSetting
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');

        return $OrganizationSettings->getByProperty(ScimBaseSettingsService::SCIM_SETTINGS_PROPERTY_NAME);
    }

    /**
     * @return \App\Model\Entity\User|null
     */
    public static function getScimSettingsSelectedUser(): ?User
    {
        $organizationSetting = self::getScimOrgSettings();
        if (!$organizationSetting) {
            return null;
        }
        $data = json_decode($organizationSetting->value, associative: true);
        if (empty($data['scim_user_id'])) {
            return null;
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        return $usersTable
            ->find()
            ->contain(['Roles'])
            ->where([$usersTable->aliasField('id') => $data['scim_user_id']])
            ->first();
    }
}
