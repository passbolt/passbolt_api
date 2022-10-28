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
 * @since         3.2.0
 */

namespace Passbolt\Locale\Service;

use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\AccountSettings\Model\Entity\AccountSetting;

class GetUserLocaleService extends LocaleService
{
    /**
     * @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable
     */
    public $AccountSettings;

    /**
     * It is important here to have a username, and no user or user id.
     * This will be handy when detecting an email's recipient locale.
     *
     * Read the locale in:
     * - the user's locale
     * - the organization locale
     * - the default locale
     *
     * @param  string $username Username
     * @return string
     */
    public function getLocale(string $username): string
    {
        $locale = $this->getLocaleFromUsername($username);
        if ($locale !== null) {
            return $locale;
        }

        return GetOrgLocaleService::getLocale();
    }

    /**
     * Read the user's locale.
     *
     * @param  string $username Username
     * @return string|null
     */
    protected function getLocaleFromUsername(string $username): ?string
    {
        $setting = TableRegistry::getTableLocator()
            ->get('Passbolt/AccountSettings.AccountSettings')
            ->find('byProperty', ['property' => static::SETTING_PROPERTY])
            ->innerJoinWith('Users', function (Query $q) use ($username) {
                return $q->where([
                    'Users.username' => $username,
                    'Users.deleted' => false,
                ]);
            })
            ->first();

        if ($setting instanceof AccountSetting) {
            return $setting->get('value');
        }

        return null;
    }
}
