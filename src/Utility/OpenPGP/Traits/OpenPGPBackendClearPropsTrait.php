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

trait OpenPGPBackendClearPropsTrait
{
    /**
     * Removes all keys which were set for decryption before
     *
     * @return void
     */
    public function clearDecryptKeys(): void
    {
        $this->_decryptKeyFingerprint = null;
    }

    /**
     * Removes all keys which were set for signing before
     *
     * @return void
     */
    public function clearSignKeys(): void
    {
        $this->_signKeyFingerprint = null;
    }

    /**
     * Removes all keys which were set for encryption before
     *
     * @return void
     */
    public function clearEncryptKeys(): void
    {
        $this->_encryptKeyFingerprint = null;
    }

    /**
     * Removes all keys which were set for verify before
     *
     * @return void
     */
    public function clearVerifyKeys(): void
    {
        $this->_verifyKeyFingerprint = null;
    }

    /**
     * Removes all keys which were set before
     *
     * @return void
     */
    public function clearKeys(): void
    {
        $this->clearDecryptKeys();
        $this->clearEncryptKeys();
        $this->clearSignKeys();
        $this->clearVerifyKeys();
    }
}
