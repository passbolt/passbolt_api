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
 * @since         3.6.0
 */
namespace App\Model\Traits\OpenPGP;

use App\Service\OpenPGP\PublicKeyValidationService;
use Cake\Chronos\ChronosInterface;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;

trait PublicKeyValidatorTrait
{
    /**
     * Custom validation rule to validate fingerprint
     *
     * @param string $value fingerprint
     * @return bool
     */
    public function isValidFingerprintRule(string $value): bool
    {
        return PublicKeyValidationService::isValidFingerprint($value);
    }

    /**
     * Custom validation rule to validate key id
     *
     * @param string $value fingerprint
     * @return bool
     */
    public function isValidKeyIdRule(string $value): bool
    {
        return PublicKeyValidationService::isValidKeyId($value);
    }

    /**
     * Custom validation rule to validate key id
     *
     * @param string $value fingerprint
     * @return bool
     */
    public function isParsableArmoredPublicKeyRule(string $value): bool
    {
        return PublicKeyValidationService::isParsableArmoredPublicKey($value);
    }

    /**
     * Check if a key date is set in the past... tomorrow!
     *
     * In a ideal world we should check if a key date is set in the past from 'now'
     * where now is the time of reference of the server. But in practice we
     * allow a next day margin because users had the issue of having keys generated
     * by systems that were ahead of server time. Refs. PASSBOLT-1505.
     *
     * @param \Cake\Chronos\ChronosInterface $value Cake Datetime
     * @return bool
     */
    public function isInFuturePastRule(ChronosInterface $value): bool
    {
        $nowWithMargin = Time::now()->modify('+12 hours');

        return $value->lt($nowWithMargin);
    }

    /**
     * Check if a key date is set in the future
     * Used to check key expiry date
     *
     * @param \Cake\Chronos\ChronosInterface $value Cake Datetime
     * @return bool
     */
    public function isInFutureRule(ChronosInterface $value): bool
    {
        return $value->gt(FrozenTime::now());
    }

    /**
     * Custom validation rule to validate key type
     *
     * @param string $value fingerprint
     * @return bool
     */
    public function isValidKeyTypeRule(string $value): bool
    {
        return PublicKeyValidationService::isValidAlgorithm($value);
    }

    /**
     * Check for valid email inside OpenPGP key UID
     *
     * @param string $value gpg key uid
     * @return bool
     */
    public function uidContainValidEmailRule(string $value): bool
    {
        return PublicKeyValidationService::uidContainValidEmail($value);
    }
}
