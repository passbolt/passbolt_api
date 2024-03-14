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
 * @since         4.7.0
 */

namespace App\Service\Healthcheck\Gpg;

use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\Healthcheck\SkipHealthcheckInterface;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Exception\CakeException;

class CanDecryptGpgHealthcheck extends AbstractGpgHealthcheck implements SkipHealthcheckInterface
{
    private CanEncryptGpgHealthcheck $canEncryptGpgHealthcheck;

    private bool $isSkipped = false;

    /**
     * @param \App\Service\Healthcheck\Gpg\CanEncryptGpgHealthcheck $canEncryptGpgHealthcheck service
     */
    public function __construct(CanEncryptGpgHealthcheck $canEncryptGpgHealthcheck)
    {
        $this->canEncryptGpgHealthcheck = $canEncryptGpgHealthcheck;
    }

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        if (!$this->canEncryptGpgHealthcheck->isPassed()) {
            return $this;
        }

        $gpg = OpenPGPBackendFactory::get();
        $messageToEncrypt = 'test message';
        try {
            $fingerprint = $this->getServerKeyFingerprint();
            $passphrase = $this->getServerKeyPassphrase();
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
            $encryptedMessage = $gpg->encrypt($messageToEncrypt);
            $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
            $decryptedMessage = $gpg->decrypt($encryptedMessage);
            if ($decryptedMessage === $messageToEncrypt) {
                $this->status = true;
            }
        } catch (CakeException $e) {
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The private key can be used to decrypt a message.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The private key cannot be used to decrypt a message');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function markAsSkipped(): void
    {
        $this->isSkipped = true;
    }

    /**
     * @inheritDoc
     */
    public function isSkipped(): bool
    {
        return $this->isSkipped;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'canDecrypt';
    }
}
