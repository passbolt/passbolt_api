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
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;

/**
 * Message Validation Service
 *
 * Provide another layer of abstraction on top of backends to perform operations on OpenPGP message
 * in order ot provide high level validation.
 *
 * Throws @CustomValidationException so that errors can be presented in an API response in a similar
 * fashion than models.
 */
class MessageValidationService
{
    public const IS_PARSABLE_ARMORED_MESSAGE_RULE = 'isParsableArmoredMessageRule';
    public const HAS_NO_EXTRA_BREAK_LINE_RULE = 'hasNoExtraBreakLineRule';
    public const HAS_SYMMETRIC_PACKET_RULE = 'hasSymmetricPacketRule';
    public const HAS_ASYMMETRIC_PACKET_RULE = 'hasAsymmetricPacketRule';
    public const HAS_EXACTLY_ONE_RECIPIENT = 'hasExactlyOneRecipient';

    /**
     * Extend set of rules that should now be used as default
     *
     * @return array of string, the rules names
     */
    public static function getDefaultRules(): array
    {
        return [
            self::HAS_NO_EXTRA_BREAK_LINE_RULE,
        ];
    }

    /**
     * Extend set of rules that should now be used for checking symmetric messages
     *
     * @return array of string, the rules names
     */
    public static function getSymmetricMessageRules(): array
    {
        return array_merge(self::getDefaultRules(), [
            self::HAS_SYMMETRIC_PACKET_RULE,
        ]);
    }

    /**
     * Extend set of rules that should now be used for checking asymmetric messages
     *
     * @return array of string, the rules names
     */
    public static function getAsymmetricMessageRules(): array
    {
        return array_merge(self::getDefaultRules(), [
            self::HAS_ASYMMETRIC_PACKET_RULE,
            self::HAS_EXACTLY_ONE_RECIPIENT,
        ]);
    }

    /**
     * @param string $armoredMessage user provided data
     * @param array|null $rules to override default rules
     * @return array key information (see OpenPGPBackendInterface::getKeyInfo)
     */
    public static function parseAndValidateMessage(string $armoredMessage, ?array $rules = null): array
    {
        $rules = $rules ?? self::getDefaultRules();
        if (!count($rules)) {
            throw new InternalErrorException('Invalid request, message validation rules are missing.');
        }

        // Parsing check is mandatory and always done first
        // We don't even try the other rules if this one fails
        try {
            $messageInfo = self::getMessageInfo($armoredMessage);
        } catch (\Exception $exception) {
            throw new CustomValidationException(__('The public key could not be parsed.'), [
                'data' => [
                    self::IS_PARSABLE_ARMORED_MESSAGE_RULE => $exception->getMessage(),
                ],
            ]);
        }

        // Other rules are recommended but not mandatory
        // As one may want to see what's inside the message info for debugging purpose
        $validationErrors = [];
        foreach ($rules as $i => $ruleName) {
            switch ($ruleName) {
                case self::HAS_NO_EXTRA_BREAK_LINE_RULE:
                    if (self::hasExtraBreakline($armoredMessage)) {
                        $msg = __('The armored message must not contain an extra line before the end block.');
                        $validationErrors[$ruleName] = $msg;
                    }
                    break;
                case self::HAS_ASYMMETRIC_PACKET_RULE:
                    if (!$messageInfo['asymmetric']) {
                        $validationErrors[$ruleName] = __('The message must contain an asymmetric packet.');
                    }
                    break;
                case self::HAS_SYMMETRIC_PACKET_RULE:
                    if (!$messageInfo['symmetric']) {
                        $validationErrors[$ruleName] = __('The message must contain a symmetric packet.');
                    }
                    break;
                case self::HAS_EXACTLY_ONE_RECIPIENT:
                    if (count($messageInfo['recipients']) !== 1) {
                        $validationErrors[$ruleName] = __('The message must contain only one recipient.');
                    }
                    break;
                default:
                    throw new InternalErrorException(__('Unknown key validation rule: {0}', $ruleName));
            }
        }

        // Wrap all errors together in a custom validation exception
        if (count($validationErrors)) {
            $debug = 'The armored message could not be validated' . "\n";
            $debug .= $armoredMessage . "\n" . json_encode($validationErrors);
            Log::error($debug);
            throw new CustomValidationException(__('The armored message could not be validated.'), [
                'data' => $validationErrors,
            ]);
        }

        return $messageInfo;
    }

    /**
     * Get Message Info
     *
     * @param string $armoredMessage user provided data
     * @return array see OpenPGPBackendInterface::getMessageInfo
     */
    public static function getMessageInfo(string $armoredMessage): array
    {
        return OpenPGPBackendFactory::get()->getMessageInfo($armoredMessage);
    }

    /**
     * Key with extra breakline after checksum and before the end block
     * are known to cause compatibility issues with gopenpgp
     *
     * @param string $armoredKey armored key block
     * @return bool
     */
    public static function hasExtraBreakLine(string $armoredKey): bool
    {
        return OpenPGPBackendFactory::get()->hasExtraBreakLine($armoredKey);
    }

    /**
     * Custom validation rule to check if armored message block is parsable
     *
     * @param string|null $armoredMsg user provided data
     * @return bool
     */
    public static function isParsableArmoredMessage(?string $armoredMsg = null): bool
    {
        if (!isset($armoredMsg)) {
            return false;
        }

        return OpenPGPBackendFactory::get()->isValidMessage($armoredMsg);
    }
}
