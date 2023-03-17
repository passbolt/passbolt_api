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

use App\Error\Exception\ValidationException;
use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;

class LocaleService
{
    use LocatorAwareTrait;

    public const SETTING_PROPERTY = 'locale';
    public const REQUEST_DATA_KEY = 'value';

    /**
     * Read in configuration the locales available
     *
     * @return array|\ArrayAccess
     */
    public static function getSystemLocales()
    {
        return Hash::extract(
            Configure::readOrFail('passbolt.plugins.locale.options'),
            '{n}.locale'
        );
    }

    /**
     * Detect if a local is found in the array keys of the available locales.
     * Both underscored and dashed notation are considered valid.
     *
     * @param string|null $locale Locale to validate
     * @return bool
     */
    public function isValidLocale(?string $locale = ''): bool
    {
        return in_array($this->dasherizeLocale($locale), static::getSystemLocales());
    }

    /**
     * Throw an exception if the locale is not valid.
     *
     * @param string $locale Locale to validate
     * @return void
     * @throws \App\Error\Exception\ValidationException
     */
    public function assertIsValidLocale(string $locale)
    {
        if (!$this->isValidLocale($locale)) {
            throw new ValidationException(__('This is not a valid locale.'));
        }
    }

    /**
     * Underscore and set the locale.
     *
     * @param string $locale Locale is set.
     * @return void
     */
    public function setLocale(string $locale): void
    {
        $locale = $this->underscoreLocale($locale);
        I18n::setLocale($locale);
    }

    /**
     * Checks if the locale is supported by the system.
     * If so, send the locale to self::setLocale,
     * which will dasherize the locale and set it in I18n.
     *
     * @param string|null $locale The locale to set.
     * @return void
     */
    public function setLocaleIfIsValid(?string $locale = ''): void
    {
        if (empty($locale)) {
            return;
        }
        if ($this->isValidLocale($locale)) {
            $this->setLocale($locale);
        } else {
            Log::warning(__d('log', 'The locale {0} is not valid or not supported.', $locale));
        }
    }

    /**
     * @param string|null $locale Locale to dasherize
     * @return string
     */
    public function dasherizeLocale(?string $locale = ''): string
    {
        return str_replace('_', '-', $locale ?? '');
    }

    /**
     * @param string|null $locale Locale to underscore
     * @return string
     */
    public function underscoreLocale(?string $locale = ''): string
    {
        return str_replace('-', '_', $locale ?? '');
    }

    /**
     * Translates a string in a given locale.
     * This is useful for example when translating the subject of
     * emails in the recipient's locale.
     *
     * The callable should return a string.
     * Example:
     *  $subject = (new LocaleService())->translateString(
     *       $user->locale,
     *       function () use ($user) {
     *           return __('Welcome to passbolt, {0}!', $user->profile->first_name);
     *       }
     *   );
     *
     * @param string $locale Destination locale
     * @param callable $callable returns the __("string to be translated")
     * @return string
     */
    public function translateString(string $locale, callable $callable): string
    {
        $backupLocale = I18n::getLocale();
        $this->setLocaleIfIsValid($locale);
        $subject = $callable();
        $this->setLocaleIfIsValid($backupLocale);

        return $subject;
    }
}
