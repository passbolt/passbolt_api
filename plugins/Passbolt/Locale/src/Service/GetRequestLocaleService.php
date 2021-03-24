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
use Cake\Utility\Hash;
use Passbolt\Locale\Utility\LocaleUtility;
use Psr\Http\Message\ServerRequestInterface;

class GetRequestLocaleService
{
    public const QUERY_KEY = 'locale';

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private $request;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Read the locale in:
     * - the url passed in the request
     * - the user's locale
     * - the organization locale
     * - the default locale
     *
     * @return string
     */
    public function getLocale(): string
    {
        $locale = $this->readUriLocale();
        if ($locale) {
            return $locale;
        }
        $locale = $this->readAccountLocale();
        if ($locale) {
            return $locale;
        }

        return LocaleUtility::getOrganizationLocale();
    }

    /**
     * Read the locale in the request's url.
     * The underscore locale format is supported.
     *
     * @return string|null
     */
    private function readUriLocale(): ?string
    {
        $locale = $this->getRequest()->getQueryParams()[self::QUERY_KEY] ?? '';
        if (LocaleUtility::localeIsValid($locale)) {
            return LocaleUtility::dasherizeLocale($locale);
        }

        return null;
    }

    /**
     * Read the user's locale
     *
     * @return string|null
     */
    private function readAccountLocale(): ?string
    {
        if (empty($this->getUserId())) {
            return null;
        }
        try {
            $setting = TableRegistry::getTableLocator()
                ->get('Passbolt/AccountSettings.AccountSettings')
                ->getByProperty($this->getUserId(), LocaleUtility::SETTING_PROPERTY);
        } catch (\Exception $e) {
            // Do nothing if the table is not found.
            return null;
        }

        if ($setting) {
            return $setting->get('value');
        }

        return null;
    }

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    private function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * Get the authenticated user id, or null if not authenticated.
     *
     * @return string
     */
    private function getUserId(): ?string
    {
        $result = $this->getRequest()->getAttribute('authenticationResult');
        if (empty($result)) {
            return null;
        }

        $user = $result->getData();
        if (empty($user)) {
            return null;
        }

        return Hash::get($user, 'id');
    }
}
