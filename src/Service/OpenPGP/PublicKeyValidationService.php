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
namespace App\Service\OpenPGP;

use App\Error\Exception\CustomValidationException;
use App\Model\Validation\EmailValidationRule;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;

/**
 * Public Key Validation Service
 *
 * Provide another layer of abstraction on top of backends to perform operations public keys
 * Mostly helpers for validation in other services. It works like a model but because there is
 * not DB and a bunch of extra parsing and OpenPGP service it's not in a model.
 *
 * Throws @CustomValidationException so that errors can be presented in an API response in a similar
 * fashion than models.
 */
class PublicKeyValidationService
{
    // Mandatory validation rule
    public const IS_PARSABLE_ARMORED_KEY_RULE = 'isParsableArmoredKeyRule';

    // Other supported validation rules
    public const IS_VALID_ALGORITHM_RULE = 'isValidAlgorithmRule';
    public const IS_VALID_ALGORITHM_STRICT_RULE = 'isValidAlgorithmStrictRule';
    public const IS_VALID_FINGERPRINT_RULE = 'isValidFingerprintRule';
    public const IS_VALID_KEY_ID_RULE = 'isValidKeyIdRule';
    public const IS_VALID_KEY_SIZE_RULE = 'isValidKeySizeRule';
    public const IS_NOT_CREATED_IN_THE_FUTURE_RULE = 'isNotCreatedInTheFutureRule';
    public const IS_NOT_EXPIRED_RULE = 'isNotExpiredRule';
    public const IS_NOT_REVOKED_RULE = 'isNotRevokedRule';
    public const IS_REVOKED_RULE = 'isRevokedRule';
    public const HAS_NO_EXPIRY_DATE_RULE = 'hasNoExpiryDateRule';
    public const HAS_NO_EXTRA_BREAK_LINE_RULE = 'hasNoExtraBreakLineRule';
    public const IS_VALID_KEY_SIZE_STRICT_RULE = 'isValidKeySizeStrictRule';

    // Literal packet containing multiple public keys are not supported by gopenpgp
    // On API and Webext we use the first public key package found and ignore the rest and a user
    // can only have one key. Its probably better to just break and refuse the key.
    public const HAS_MULTIPLE_MAIN_PACKETS_RULE = 'hasMultipleMainPacketsRule';

    /**
     * This is the historical set of rules used for user public keys
     * See extended rules for better future usage
     *
     * @return array of string, the rules names
     */
    public static function getHistoricalRules(): array
    {
        return [
            self::IS_VALID_ALGORITHM_RULE,
            self::IS_VALID_FINGERPRINT_RULE,
            self::IS_VALID_KEY_ID_RULE,
            self::IS_VALID_KEY_SIZE_RULE,
            self::IS_NOT_CREATED_IN_THE_FUTURE_RULE,
            self::IS_NOT_EXPIRED_RULE,
        ];
    }

    /**
     * Extend set of rules that should now be used as default
     *
     * @return array of string, the rules names
     */
    public static function getDefaultRules(): array
    {
        return array_merge(self::getHistoricalRules(), [
            self::IS_NOT_REVOKED_RULE,
            self::HAS_NO_EXPIRY_DATE_RULE,
            self::HAS_NO_EXTRA_BREAK_LINE_RULE,
            self::HAS_MULTIPLE_MAIN_PACKETS_RULE,
        ]);
    }

    /**
     * Extend set of rules that should be used in the future
     *
     * @return array of string, the rules names
     */
    public static function getStrictRules(): array
    {
        return array_merge(self::getDefaultRules(), [
            self::IS_VALID_KEY_SIZE_STRICT_RULE,
            self::IS_VALID_ALGORITHM_STRICT_RULE,
        ]);
    }

    /**
     * Extend default set for revoked key
     *
     * @return array
     */
    public static function getRevokedKeyRules(): array
    {
        return array_merge(self::getHistoricalRules(), [
            self::IS_VALID_KEY_SIZE_STRICT_RULE,
            self::HAS_NO_EXTRA_BREAK_LINE_RULE,
            self::IS_REVOKED_RULE,
        ]);
    }

    /**
     * @param string $armoredKey user provided data
     * @param array|null $rules to override default rules
     * @return array key information (see OpenPGPBackendInterface::getKeyInfo)
     */
    public static function parseAndValidatePublicKey(string $armoredKey, ?array $rules = null): array
    {
        $rules = $rules ?? self::getDefaultRules();
        if (!count($rules)) {
            throw new InternalErrorException('Invalid public key validation rules are missing.');
        }

        // Parsing check is mandatory and always done first
        // We don't even try the other rules if this one fails
        if (!self::isParsableArmoredPublicKey($armoredKey)) {
            throw new CustomValidationException(__('A valid OpenPGP key must be provided.'), [
                'armored_key' => [
                    self::IS_PARSABLE_ARMORED_KEY_RULE => __('The public key could not be parsed.'),
                ],
            ]);
        }

        // Other rules are recommended but not mandatory
        // As one may want to see what's inside the key info for debugging purpose
        $keyInfo = self::getPublicKeyInfo($armoredKey);
        $validationErrors = [];
        foreach ($rules as $ruleName) {
            switch ($ruleName) {
                case self::IS_VALID_ALGORITHM_RULE:
                    if (!self::isValidAlgorithm($keyInfo['type'], false)) {
                        $validationErrors[$ruleName] = __('The algorithm is invalid.');
                    }
                    break;
                case self::IS_VALID_ALGORITHM_STRICT_RULE:
                    if (!self::isValidAlgorithm($keyInfo['type'])) {
                        $validationErrors[$ruleName] = __('The algorithm is invalid.');
                    }
                    break;
                case self::IS_VALID_FINGERPRINT_RULE:
                    if (!self::isValidFingerprint($keyInfo['fingerprint'])) {
                        $validationErrors[$ruleName] = __('The fingerprint id is invalid.');
                    }
                    break;
                case self::IS_VALID_KEY_ID_RULE:
                    if (!self::isValidKeyId($keyInfo['key_id'])) {
                        $validationErrors[$ruleName] = __('The public key id is invalid.');
                    }
                    break;
                case self::IS_VALID_KEY_SIZE_RULE:
                    if (!self::isRecommendedSize($keyInfo['type'], $keyInfo['bits'], false)) {
                        $validationErrors[$ruleName] = __('The key size is not valid.');
                    }
                    break;
                case self::IS_VALID_KEY_SIZE_STRICT_RULE:
                    if (!self::isRecommendedSize($keyInfo['type'], $keyInfo['bits'])) {
                        $validationErrors[$ruleName] = __('The key size must match security recommendations.');
                    }
                    break;
                case self::IS_NOT_CREATED_IN_THE_FUTURE_RULE:
                    if (self::isDateInFuture($keyInfo['key_created'])) {
                        $validationErrors[$ruleName] = __('The key creation date must not be in the future.');
                    }
                    break;
                case self::IS_NOT_EXPIRED_RULE:
                    if (isset($keyInfo['expires']) && !self::isDateInFuture($keyInfo['expires'])) {
                        $validationErrors[$ruleName] = __('The key must not be expired.');
                    }
                    break;
                case self::HAS_NO_EXPIRY_DATE_RULE:
                    if (isset($keyInfo['expires'])) {
                        $validationErrors[$ruleName] = __('The key must not include an expiry date.');
                    }
                    break;
                case self::IS_REVOKED_RULE:
                    if (!$keyInfo['revoked']) {
                        $validationErrors[$ruleName] = __('The key must be revoked.');
                    }
                    break;
                case self::IS_NOT_REVOKED_RULE:
                    if ($keyInfo['revoked']) {
                        $validationErrors[$ruleName] = __('The key must not be revoked.');
                    }
                    break;
                case self::HAS_NO_EXTRA_BREAK_LINE_RULE:
                    if (self::hasExtraBreakline($armoredKey)) {
                        $msg = __('The armored key must not contain an extra line before the end block.');
                        $validationErrors[$ruleName] = $msg;
                    }
                    break;
                case self::HAS_MULTIPLE_MAIN_PACKETS_RULE:
                    if ($keyInfo['public_key_packet_counts'] > 1 || $keyInfo['secret_key_packet_counts'] > 1) {
                        $msg = __('The armored key must not contain multiple keys.');
                        $validationErrors[$ruleName] = $msg;
                    }
                    break;
                default:
                    throw new InternalErrorException(__('Unknown key validation rule: {0}', $ruleName));
            }
        }

        // Wrap all errors together in a custom validation exception
        if (count($validationErrors)) {
            throw new CustomValidationException(__('The public key could not be validated.'), [
                'armored_key' => $validationErrors,
            ]);
        }

        // Or return key information for further use
        // for example for it to be saved in a model
        return $keyInfo;
    }

    /**
     * Return true if the date is in the future, false if in the past or not a valid date
     *
     * @param string|int|null $datetimeString user provider data
     * @return bool
     */
    public static function isDateInFuture($datetimeString = null): bool
    {
        if (!isset($datetimeString) || (!is_string($datetimeString) && !is_int($datetimeString))) {
            return false;
        }

        $frozenTime = new FrozenTime($datetimeString);

        return $frozenTime->greaterThan(FrozenTime::now());
    }

    /**
     * Returns true if the key is of the recommended size
     *
     * @param string $algorithm RSA, ECC, etc.
     * @param int $keySize 2048, etc.
     * @param bool $strict default true
     * @return bool if key size is valid
     */
    public static function isRecommendedSize(string $algorithm, int $keySize, bool $strict = true): bool
    {
        if ($algorithm === 'RSA') {
            if ($strict) {
                // As recommended in CURE53 audit PBL-01-005 and ANSSI
                // Ref. https://www.ssi.gouv.fr/entreprise/guide/mecanismes-cryptographiques/
                return $keySize == 3072 || $keySize == 4096;
            } else {
                return $keySize == 2048 || $keySize == 3072 || $keySize == 4096;
            }
        } else {
            // No preferences yet for other algorithms such as ECC
            return true;
        }
    }

    /**
     * Custom validation rule to validate key id
     *
     * /!\ Note that short key ids are considered insecure
     * In any case, we recommend using fingerprint instead.
     * We use this method as a sanity check only
     *
     * @param string|null $keyId user provided data
     * @param bool $strict default false for backward compatibility
     * @return bool
     */
    public static function isValidKeyId(?string $keyId = null, bool $strict = false): bool
    {
        if (!$strict) {
            return self::isValidShortKeyId($keyId) || self::isValidLongKeyId($keyId);
        } else {
            return self::isValidLongKeyId($keyId);
        }
    }

    /**
     * Custom validation rule to validate short key id
     * Note: short key ids are considered insecure
     *
     * @param string|null $keyId user provided data
     * @return bool
     */
    public static function isValidShortKeyId(?string $keyId = null): bool
    {
        if (!isset($keyId)) {
            return false;
        }

        return preg_match('/^[A-F0-9]{8}$/', $keyId) === 1;
    }

    /**
     * Custom validation rule to validate key id
     *
     * @param string|null $keyId user provided data
     * @return bool
     */
    public static function isValidLongKeyId(?string $keyId = null): bool
    {
        if (!isset($keyId)) {
            return false;
        }

        return preg_match('/^[A-F0-9]{16}$/', $keyId) === 1;
    }

    /**
     * Custom validation rule to check if armored key block is parsable
     *
     * @param string|null $armoredKey user provided data
     * @return bool
     */
    public static function isParsableArmoredPublicKey(?string $armoredKey = null): bool
    {
        if (!isset($armoredKey)) {
            return false;
        }

        return OpenPGPBackendFactory::get()->isParsableArmoredPublicKey($armoredKey);
    }

    /**
     * Get Public Key Info
     *
     * @param string $armoredKey user provided data
     * @return array see OpenPGPBackendInterface::getKeyInfo
     * @throws \Cake\Core\Exception\CakeException if the armored key cannot be parsed
     */
    public static function getPublicKeyInfo(string $armoredKey): array
    {
        return OpenPGPBackendFactory::get()->getPublicKeyInfo($armoredKey);
    }

    /**
     * Return true if string is a valid fingerprint
     *
     * @param string|null $fingerprint user provided data
     * @return bool
     */
    public static function isValidFingerprint(?string $fingerprint = null): bool
    {
        return OpenPGPBackendFactory::get()->isValidFingerprint($fingerprint);
    }

    /**
     * Custom validation rule to validate email inside a OpenPGP key UID string
     *
     * @param string|null $value openpgp key uid
     * @return bool
     */
    public static function uidContainValidEmail(?string $value = null): bool
    {
        if (!isset($value)) {
            return false;
        }
        preg_match('/<(\S+@\S+)>$/', $value, $matches);
        if (isset($matches[1])) {
            return EmailValidationRule::check($matches[1]);
        }

        return false;
    }

    /**
     * Get email from Uid
     *
     * @param string|null $value openpgp key uid
     * @return string|bool
     */
    public static function getEmailFromUid(?string $value = null)
    {
        if (!isset($value)) {
            return false;
        }
        preg_match('/<(\S+@\S+)>$/', $value, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        return false;
    }

    /**
     * Return true if email address is included in key uid
     *
     * @param string|null $email email
     * @param string|null $uid uid
     * @return bool
     */
    public static function isEmailInUid(?string $email, ?string $uid): bool
    {
        if (!empty($email) && EmailValidationRule::check($email)) {
            $uidEmail = self::getEmailFromUid($uid);
            if ($uidEmail !== false) {
                return $email == $uidEmail;
            }
        }

        return false;
    }

    /**
     * Custom validation rule to validate key algorithm
     *
     * @param string|null $algorithm RSA, ECC, EdDSA, etc. see OpenPGP_PublicKeyPacket::$algorithms.
     * @param bool $strict exclude DSA and ELGAMAL, default true
     * @return bool
     */
    public static function isValidAlgorithm(?string $algorithm = null, bool $strict = true): bool
    {
        if (!isset($algorithm)) {
            return false;
        }
        $supported = \OpenPGP_PublicKeyPacket::$algorithms;
        if ($strict === true) {
            // Minus legacy items such as DSA, ELGAMAL
            // Default in openpgp.js v5
            unset($supported[16]);
            unset($supported[17]);
        }
        foreach ($supported as $i => $a) {
            if ($algorithm === $a) {
                return true;
            }
        }

        return false;
    }

    /**
     * Key with extra breakline after checksum and before the end block
     * are known to cause compatibility issues with gopenpgp
     *
     * @param string $publicKey armored format
     * @return bool
     */
    public static function hasExtraBreakLine(string $publicKey): bool
    {
        return OpenPGPBackendFactory::get()->hasExtraBreakLine($publicKey);
    }
}
