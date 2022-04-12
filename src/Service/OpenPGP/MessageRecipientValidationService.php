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

/**
 * Service to check if an OpenPGP message is intended for a recipient
 */
class MessageRecipientValidationService
{
    /**
     * @param array $messageInfo see MessageValidationService::getMessageInfo
     * @param array $keyInfo see PublicKeyValidationService::getMessageInfo
     * @throw CustomValidationException if the message info or key info are not workable
     * @return bool
     */
    public static function isMessageForRecipient(array $messageInfo, array $keyInfo): bool
    {
        if (!isset($keyInfo['sub_keys'][0]['key_id'])) {
            throw new CustomValidationException(__('Could not validate sub key data.'), [
                'subKeyIdRequired' => __('Public key sub key id could not be found.'),
            ]);
        }
        if (!isset($messageInfo['recipients'][0])) {
            throw new CustomValidationException(__('Could not validate message data.'), [
                'recipientRequired' => __('Recipient information could not be found.'),
            ]);
        }

        foreach ($keyInfo['sub_keys'] as $subKey) {
            if (in_array($subKey['key_id'], $messageInfo['recipients'])) {
                return true;
            }
        }

        return false;
    }
}
