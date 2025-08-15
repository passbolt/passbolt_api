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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Utility;

use App\Model\Entity\User;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Passbolt\Scim\Service\ScimGetSettingsService;

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
     * @return \App\Model\Entity\User|null
     */
    public static function getScimSettingsSelectedUser(): ?User
    {
        $scimConfig = (new ScimGetSettingsService())->getSettingsDecryptedValue();
        if (empty($scimConfig['scim_user_id'])) {
            return null;
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        return $usersTable
            ->find()
            ->contain(['Roles'])
            ->where([$usersTable->aliasField('id') => $scimConfig['scim_user_id']])
            ->first();
    }
}
