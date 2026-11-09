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
 * @since         5.17.0
 */
namespace App\Service\Secrets;

use App\Service\OpenPGP\MessageValidationService;
use App\Service\OpenPGP\PublicKeyValidationService;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Exception;

class SecretRecipientValidationService
{
    public const CONFIG_KEY_VALIDATE_RECIPIENT = 'passbolt.security.secrets.validateRecipient';

    /**
     * @var array Key ids of the users' current key, indexed by user id
     */
    private static array $keyIdsByUserId = [];

    /**
     * @return bool
     */
    public static function isEnabled(): bool
    {
        return Configure::read(self::CONFIG_KEY_VALIDATE_RECIPIENT) === true;
    }

    /**
     * @return void
     */
    public static function reset(): void
    {
        self::$keyIdsByUserId = [];
    }

    /**
     * Load the current key of the given users with one query.
     *
     * @param array $userIds User identifiers.
     * @return void
     */
    public static function preloadKeys(array $userIds): void
    {
        $userIdsToLoad = [];
        foreach ($userIds as $userId) {
            if (Validation::uuid($userId) && !isset(self::$keyIdsByUserId[$userId])) {
                $userIdsToLoad[] = $userId;
            }
        }

        if (empty($userIdsToLoad)) {
            return;
        }

        $gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys')
            ->find()
            ->select(['user_id', 'armored_key'])
            ->where(['user_id IN' => $userIdsToLoad, 'deleted' => false])
            ->orderBy(['created' => 'DESC'])
            ->all();

        /** @var \App\Model\Entity\Gpgkey $gpgkey */
        foreach ($gpgkeys as $gpgkey) {
            if (isset(self::$keyIdsByUserId[$gpgkey->user_id])) {
                continue;
            }

            self::$keyIdsByUserId[$gpgkey->user_id] = self::extractKeyIds($gpgkey->armored_key);
        }
    }

    /**
     * Checks that all recipient key IDs declared in the message belong to the user's current key (primary key or subkeys).
     *
     * Only the declared key IDs are compared. This does not prove the message can be decrypted by the user.
     * It catches client mistakes only, not malicious clients.
     *
     * @param string $armoredMessage Secret data.
     * @param string $userId Recipient user id.
     * @return bool
     */
    public static function recipientKeyIdsMatchOnlyUserKey(string $armoredMessage, string $userId): bool
    {
        self::preloadKeys([$userId]);

        $userKeyIds = self::$keyIdsByUserId[$userId] ?? [];
        if (empty($userKeyIds)) {
            Log::warning(sprintf('No user keys found for %s', $userId));

            return false;
        }

        try {
            /** @var array{recipients: array<string>} $messageInfo */
            $messageInfo = MessageValidationService::getMessageInfo($armoredMessage);
        } catch (Exception $e) {
            $logMessage = 'Could not parse the secret message.';
            if (Configure::read('debug')) {
                $logMessage .= ' ' . $e->getMessage();
            }
            Log::debug($logMessage);

            return false;
        }

        $recipients = $messageInfo['recipients'] ?? [];
        if (empty($recipients)) {
            return false;
        }

        foreach ($recipients as $recipientKeyId) {
            if (!isset($userKeyIds[strtoupper($recipientKeyId)])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the key ids, the primary key and of all its subkeys.
     *
     * @param string $armoredKey Armored public key.
     * @return array
     */
    private static function extractKeyIds(string $armoredKey): array
    {
        try {
            $keyInfo = PublicKeyValidationService::getPublicKeyInfo($armoredKey);
        } catch (Exception $e) {
            $logMessage = 'Could not parse the recipient key.';
            if (Configure::read('debug')) {
                $logMessage .= ' ' . $e->getMessage();
            }
            Log::debug($logMessage);

            return [];
        }

        $keyIds = [strtoupper($keyInfo['key_id']) => true];
        foreach ($keyInfo['sub_keys'] as $subKey) {
            $keyIds[strtoupper($subKey['key_id'])] = true;
        }

        return $keyIds;
    }
}
