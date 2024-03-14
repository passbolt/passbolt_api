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

class CanDecryptVerifyGpgHealthcheck extends AbstractGpgHealthcheck implements SkipHealthcheckInterface
{
    private PublicKeyInKeyringGpgHealthcheck $publicKeyInKeyringGpgHealthcheck;

    private bool $isSkipped = false;

    /**
     * @param \App\Service\Healthcheck\Gpg\PublicKeyInKeyringGpgHealthcheck $publicKeyInKeyringGpgHealthcheck  service
     */
    public function __construct(PublicKeyInKeyringGpgHealthcheck $publicKeyInKeyringGpgHealthcheck)
    {
        $this->publicKeyInKeyringGpgHealthcheck = $publicKeyInKeyringGpgHealthcheck;
    }

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        if (!$this->publicKeyInKeyringGpgHealthcheck->isPassed()) {
            return $this;
        }

        $gpg = OpenPGPBackendFactory::get();
        $messageToEncrypt = 'test message';
        try {
            $fingerprint = $this->getServerKeyFingerprint();
            $passphrase = $this->getServerKeyPassphrase();
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
            $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
            $encryptedMessage2 = $gpg->encrypt($messageToEncrypt, true);
            $gpg->setVerifyKeyFromFingerprint($fingerprint);
            $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
            $decryptedMessage2 = $gpg->decrypt($encryptedMessage2, true);
            if ($decryptedMessage2 === $messageToEncrypt) {
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
        return __('The private key can be used to decrypt and verify a message.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The private key cannot be used to decrypt and verify a message');
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
        return 'canDecryptVerify';
    }
}
