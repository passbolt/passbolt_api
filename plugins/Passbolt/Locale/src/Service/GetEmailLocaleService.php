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

use Cake\ORM\TableRegistry;
use Passbolt\Locale\Utility\LocaleUtility;

class GetEmailLocaleService
{
    /**
     * @var string
     */
    private $email;

    /**
     * @param string $email The Email.
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Read the locale in:
     * - the recipient's locale
     * - the organization locale
     * - the default locale
     *
     * @return string
     */
    public function getLocale(): string
    {
        $locale = $this->readRecipientLocale();
        if ($locale) {
            return $locale;
        }

        return LocaleUtility::getOrganizationLocale();
    }

    /**
     * Read the email recipient's locale
     *
     * @return string|null
     */
    private function readRecipientLocale(): ?string
    {
        if (empty($this->email)) {
            return null;
        }

        $user = TableRegistry::getTableLocator()->get('Users')
            ->find()
            ->where(['Users.username' => $this->email])
            ->first();

        if ($user) {
            $setting = TableRegistry::getTableLocator()
                ->get('Passbolt/AccountSettings.AccountSettings')
                ->getByProperty($user->get('id'), LocaleUtility::SETTING_PROPERTY);
        } else {
            return null;
        }

        if ($setting) {
            return $setting->get('value');
        }

        return null;
    }
}
