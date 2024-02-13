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

use Cake\Http\Exception\BadRequestException;
use Cake\Utility\Hash;
use Psr\Http\Message\ServerRequestInterface;

class RequestLocaleParserService extends LocaleService
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
        $locale = $this->getUserLocale();
        if ($locale) {
            return $locale;
        }

        return GetOrgLocaleService::getLocale();
    }

    /**
     * Read the locale in the request's url.
     * The underscore locale format is supported.
     *
     * @return string|null
     */
    protected function readUriLocale(): ?string
    {
        $locale = $this->request->getQueryParams()[self::QUERY_KEY] ?? '';
        if (!is_string($locale)) {
            throw new BadRequestException(__('The locale should be a string.'));
        }
        if ($this->isValidLocale($locale)) {
            return $this->dasherizeLocale($locale);
        }

        return null;
    }

    /**
     * Read the user's locale
     *
     * @return string|null
     */
    protected function getUserLocale(): ?string
    {
        $username = $this->getUsername();
        if ($username === null) {
            return null;
        }

        return (new GetUserLocaleService())->getLocale($username);
    }

    /**
     * Get the authenticated username, or null if not authenticated.
     *
     * @return string
     */
    protected function getUsername(): ?string
    {
        $result = $this->request->getAttribute('authenticationResult');
        if ($result === null) {
            return null;
        }

        $user = $result->getData();
        if (empty($user)) {
            return null;
        }

        $userInSession = Hash::get($user, 'user.username');
        $userInJwtAuthentication = Hash::get($user, 'username');

        return $userInSession ?? $userInJwtAuthentication;
    }
}
