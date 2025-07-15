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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Model\Rule;

use App\Error\Exception\CustomValidationException;
use App\Service\OpenPGP\MessageRecipientValidationService;
use App\Service\OpenPGP\MessageValidationService;
use App\Service\OpenPGP\PublicKeyValidationService;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

class IsValidEncryptedMetadataPrivateKey
{
    /**
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        $userId = $entity->get('user_id');
        try {
            $armoredKey = $this->getArmoredKey($userId);
        } catch (RecordNotFoundException $e) {
            return false;
        }

        if (!is_string($armoredKey)) {
            return false;
        }

        try {
            $rules = MessageValidationService::getAsymmetricMessageRules();
            $msgInfo = MessageValidationService::parseAndValidateMessage($entity->get('data'), $rules);
        } catch (CustomValidationException $exception) {
            Log::error('The message must contain an asymmetric packet. Error: ' . $exception->getMessage());

            return false;
        }

        $keyInfo = PublicKeyValidationService::getPublicKeyInfo($armoredKey);
        if (!MessageRecipientValidationService::isMessageForRecipient($msgInfo, $keyInfo)) {
            if (!isset($keyInfo['sub_keys'][0]['key_id'])) {
                Log::error('Issue found with the user key for user id: ' . $userId . ' (No subkey).');
            }

            return false;
        }

        return true;
    }

    /**
     * @param string|null $userId User identifier.
     * @return string|false|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When user is not found.
     */
    private function getArmoredKey(?string $userId): string|false|null
    {
        if (is_null($userId)) {
            $armoredKey = file_get_contents(Configure::read('passbolt.gpg.serverKey.public'));
        } else {
            $usersTable = TableRegistry::getTableLocator()->get('Users');
            /** @var \App\Model\Entity\User $user */
            $user = $usersTable->find()->where(['Users.id' => $userId])->contain(['Gpgkeys'])->firstOrFail();
            /** @var string|null $armoredKey */
            $armoredKey = $user->gpgkey->armored_key;
        }

        return $armoredKey;
    }
}
