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

namespace App\Utility\OpenPGP\Traits;

use Cake\Core\Exception\CakeException;

trait OpenPGPBackendGetMessageInfoTrait
{
    /**
     * Get message information
     *
     * Extract the information from the key and return them in an array
     * - recipients : array of long key ids
     * - asymmetric  : bool, true if message contain asymmetric packet
     * - symmetric   : bool, true if message contain symmetric packet
     *
     * @param string $armoredMessage the ASCII armored message block
     * @return array as described above
     */
    public function getMessageInfo(string $armoredMessage): array
    {
        $messageUnarmored = $this->unarmor($armoredMessage, $this->getGpgMarker($armoredMessage));
        if ($messageUnarmored === false) {
            throw new CakeException(__('Invalid key. No OpenPGP package found.'));
        }

        // Get the message.
        $msg = @\OpenPGP_Message::parse($messageUnarmored); // phpcs:ignore
        if (empty($msg->packets)) {
            throw new CakeException(__('Invalid key. No OpenPGP public key package found.'));
        }

        $packets = $msg->packets;
        $symmetric = false;
        $asymmetric = false;
        $recipients = [];

        foreach ($packets as $packet) {
            if ($packet instanceof \OpenPGP_AsymmetricSessionKeyPacket) {
                $asymmetric = true;
                $recipients[] = $packet->keyid;
            } elseif ($packet instanceof \OpenPGP_SymmetricSessionKeyPacket) {
                $symmetric = true;
            }
        }

        if (!$asymmetric && !$symmetric) {
            throw new CakeException('Not enough session key packet found.');
        }
        if ($asymmetric && $symmetric) {
            throw new CakeException('Too many types of session key packet found.');
        }

        return [
            'asymmetric' => $asymmetric,
            'symmetric' => $symmetric,
            'recipients' => $recipients,
        ];
    }
}
