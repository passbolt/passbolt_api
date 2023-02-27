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
 * @since         3.11.0
 */

namespace App\Model\Validation;

use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;

final class EmailValidationRule extends PassboltValidationRule
{
    public const MX_CHECK_KEY = 'passbolt.email.validate.mx';
    public const REGEX_CHECK_KEY = 'passbolt.email.validate.regex';

    /**
     * @var bool
     */
    private $skipMxCheck = false;

    /**
     * @var bool
     */
    private $skipRegexCheck = false;

    /**
     * @inheritDoc
     */
    public function defaultErrorMessage($value, $context): string
    {
        return __('The email should be a valid email address.');
    }

    /**
     * Convenient static method to perform some checks outside of model/form validations
     *
     * @param mixed $email Value to check
     * @param ?bool $skipMxCheck Do not check if the Mx check flag is set in configs
     * @param ?bool $skipRegexCheck Do not check if a regex pattern is defined in configs
     * @return bool
     * @throws \Cake\Http\Exception\InternalErrorException if the regex defined in config is not valid
     */
    public static function check($email, ?bool $skipMxCheck = false, ?bool $skipRegexCheck = false): bool
    {
        $instance = (new self());
        if ($skipMxCheck) {
            $instance->withoutMxCheck();
        }
        if ($skipRegexCheck) {
            $instance->withoutRegexCheck();
        }

        return $instance->rule($email, null);
    }

    /**
     * @inheritDoc
     */
    public function rule($value, $context): bool
    {
        $deep = $this->skipMxCheck ? false : Configure::read(self::MX_CHECK_KEY);
        $regex = $this->skipRegexCheck ? null : Configure::read(self::REGEX_CHECK_KEY);

        if ($regex !== null && !is_string($regex)) {
            throw new InternalErrorException(__('The regular expression should be a valid string.'));
        }

        /** @phpstan-ignore-next-line */
        return Validation::email($value, $deep, $regex);
    }

    /**
     * Skip the Mx validation, regardless of the configuration setting
     *
     * @return $this
     */
    public function withoutMxCheck()
    {
        $this->skipMxCheck = true;

        return $this;
    }

    /**
     * Skip the regex validation, regardless of the configuration setting
     *
     * @return $this
     */
    public function withoutRegexCheck()
    {
        $this->skipRegexCheck = true;

        return $this;
    }
}
