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

use App\Model\Table\GpgkeysTable;
use App\Service\OpenPGP\MessageValidationService;
use App\Service\OpenPGP\PublicKeyValidationService;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Exception;

class SecretRecipientValidationService
{
    use LocatorAwareTrait;

    private GpgkeysTable $gpgkeysTable;

    /**
     * @var array<string, string> armored public keys indexed by user id
     */
    private array $armoredKeys = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gpgkeysTable = $this->fetchTable('Gpgkeys');
    }

    /**
     * Returns true if the message is encrypted for the user's current OpenPGP key (primary key or a subkey).
     *
     * @param string $armoredMessage Secret data.
     * @param string $userId Recipient user id.
     * @return bool
     */
    public function isEncryptedForUser(string $armoredMessage, string $userId): bool
    {
        $armoredKey = $this->getArmoredKey($userId);
        if ($armoredKey === null) {
            return false;
        }

        try {
            $messageInfo = MessageValidationService::getMessageInfo($armoredMessage);
            $keyInfo = PublicKeyValidationService::getPublicKeyInfo($armoredKey);
        } catch (Exception $e) {
            $logMessage = 'Could not parse the secret message or the recipient key.';
            if (Configure::read('debug')) {
                $logMessage .= ' ' . $e->getMessage();
            }
            Log::debug($logMessage);

            return false;
        }

        $recipientKeyIds = $this->toUpperCase($messageInfo['recipients']);
        $userKeyIds = $this->toUpperCase($this->getKeyIds($keyInfo));
        $matchingKeyIds = array_intersect($recipientKeyIds, $userKeyIds);

        return count($matchingKeyIds) > 0;
    }

    /**
     * Returns array of key ids with the primary key and of all its subkeys.
     *
     * @param array $keyInfo see PublicKeyValidationService::getPublicKeyInfo
     * @return array<string>
     */
    private function getKeyIds(array $keyInfo): array
    {
        $keyIds = [$keyInfo['key_id']];
        foreach ($keyInfo['sub_keys'] as $subKey) {
            $keyIds[] = $subKey['key_id'];
        }

        return $keyIds;
    }

    /**
     * @param array<string> $keyIds Key ids.
     * @return array<string>
     */
    private function toUpperCase(array $keyIds): array
    {
        return array_map('strtoupper', $keyIds);
    }

    /**
     * @param string $userId User identifer.
     * @return string|null
     */
    private function getArmoredKey(string $userId): ?string
    {
        if (!isset($this->armoredKeys[$userId])) {
            /** @var \App\Model\Entity\Gpgkey|null $gpgkey */
            $gpgkey = $this->gpgkeysTable->find('current', userId: $userId)->first();
            if ($gpgkey === null) {
                return null;
            }
            $this->armoredKeys[$userId] = $gpgkey->armored_key;
        }

        return $this->armoredKeys[$userId];
    }
}
