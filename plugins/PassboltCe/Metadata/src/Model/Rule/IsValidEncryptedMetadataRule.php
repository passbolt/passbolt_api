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
use Passbolt\Metadata\Model\Entity\MetadataKey;

class IsValidEncryptedMetadataRule
{
    /**
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        $metadataKeyType = $entity->get('metadata_key_type');
        $metadataKeyId = $entity->get('metadata_key_id');
        try {
            $armoredKey = $this->getArmoredKey($metadataKeyType, $metadataKeyId);
        } catch (RecordNotFoundException $e) {
            return false;
        }

        if (!is_string($armoredKey)) {
            return false;
        }

        try {
            $rules = MessageValidationService::getAsymmetricMessageRules();
            $msgInfo = MessageValidationService::parseAndValidateMessage($entity->get('metadata'), $rules);
        } catch (CustomValidationException $exception) {
            if (Configure::read('debug')) {
                Log::error('The message must contain an asymmetric packet. Error: ' . $exception->getMessage());
            }

            return false;
        }

        $keyInfo = PublicKeyValidationService::getPublicKeyInfo($armoredKey);
        if (!MessageRecipientValidationService::isMessageForRecipient($msgInfo, $keyInfo)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $metadataKeyType User identifier.
     * @param string $metadataKeyId User gpgkey / metadata key identifier.
     * @return string|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When user is not found.
     */
    private function getArmoredKey(string $metadataKeyType, string $metadataKeyId)
    {
        if ($metadataKeyType === MetadataKey::TYPE_SHARED_KEY) {
            $metadataKeysTable = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataKeys');
            /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey */
            $metadataKey = $metadataKeysTable
                ->find()
                ->where(['id' => $metadataKeyId, 'deleted IS NULL'])
                ->firstOrFail();
            $armoredKey = $metadataKey->armored_key;
        } else {
            // user_key
            $gpgkeysTable = TableRegistry::getTableLocator()->get('Gpgkeys');
            /** @var \App\Model\Entity\Gpgkey $key */
            $key = $gpgkeysTable->find()->where(['id' => $metadataKeyId, 'deleted' => false])->firstOrFail();
            /** @var string|null $armoredKey */
            $armoredKey = $key->armored_key;
        }

        return $armoredKey;
    }
}
