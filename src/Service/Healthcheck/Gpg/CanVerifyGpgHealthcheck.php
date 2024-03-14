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

class CanVerifyGpgHealthcheck extends AbstractGpgHealthcheck implements SkipHealthcheckInterface
{
    private CanDecryptVerifyGpgHealthcheck $canDecryptVerifyGpgHealthcheck;

    private bool $isSkipped = false;

    /**
     * @param \App\Service\Healthcheck\Gpg\CanDecryptVerifyGpgHealthcheck $canDecryptVerifyGpgHealthcheck service
     */
    public function __construct(CanDecryptVerifyGpgHealthcheck $canDecryptVerifyGpgHealthcheck)
    {
        $this->canDecryptVerifyGpgHealthcheck = $canDecryptVerifyGpgHealthcheck;
    }

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        if (!$this->canDecryptVerifyGpgHealthcheck->isPassed()) {
            return $this;
        }

        $gpg = OpenPGPBackendFactory::get();
        try {
            $fingerprint = $this->getServerKeyFingerprint();
            $passphrase = $this->getServerKeyPassphrase();
            $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
            $signedMessage = $gpg->sign('test message');

            try {
                $gpg->setVerifyKeyFromFingerprint($fingerprint);
                $gpg->verify($signedMessage);
                $this->status = true;
            } catch (CakeException $e) {
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
        return __('The public key can be used to verify a signature.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The public key cannot be used to verify a signature.');
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
        return 'canVerify';
    }
}
