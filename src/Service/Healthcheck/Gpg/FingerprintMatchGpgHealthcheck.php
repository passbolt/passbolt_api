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
use App\Utility\OpenPGP\OpenPGPBackendFactory;

class FingerprintMatchGpgHealthcheck extends AbstractGpgHealthcheck
{
    private bool $isPrivateKeyInfoOK = false;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $fingerprint = $this->getServerKeyFingerprint();
        if ($this->isPublicServerKeyReadable() && $this->isPrivateServerKeyReadable() && is_string($fingerprint)) {
            $gpg = OpenPGPBackendFactory::get();
            $privateKeyData = file_get_contents($this->getPrivateServerKey());
            $privateKeyInfo = $gpg->getKeyInfo($privateKeyData);
            $this->isPrivateKeyInfoOK = ($privateKeyInfo['fingerprint'] === $fingerprint);
            $publicKeyData = file_get_contents($this->getPublicServerKey());
            $publicKeyInfo = $gpg->getPublicKeyInfo($publicKeyData);
            $isPublicKeyInfoOK = ($publicKeyInfo['fingerprint'] === $fingerprint);

            $this->status = $isPublicKeyInfoOK && $this->isPrivateKeyInfoOK;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The server key fingerprint matches the one defined in {0}.', CONFIG . 'passbolt.php');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The server key fingerprint doesn\'t match the one defined in {0}.', CONFIG . 'passbolt.php');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __('Double check the key fingerprint, example: '),
            'sudo su -s /bin/bash -c "gpg --list-keys --fingerprint --home ' . $this->getGpgHome() . '" ' . PROCESS_USER . ' | grep -i -B 2 \'SERVER_KEY_EMAIL\'',// phpcs:ignore
            __('SERVER_KEY_EMAIL: The email you used when you generated the server key.'),
            __('See. https://www.passbolt.com/help/tech/install#toc_gpg'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'gpgKeyPublicFingerprint';
    }

    /**
     * @deprecated Just here to keep BC
     * @return bool
     */
    public function isPrivateKeyInfoOK(): bool
    {
        return $this->isPrivateKeyInfoOK;
    }

    /**
     * @deprecated Just here to keep BC
     * @return bool
     */
    public function gpgKeyPublicReadable(): bool
    {
        return $this->isPublicServerKeyReadable();
    }

    /**
     * @deprecated Just here to keep BC
     * @return bool
     */
    public function gpgKeyPrivateReadable(): bool
    {
        return $this->isPublicServerKeyReadable();
    }

    /**
     * @deprecated Just here to keep BC
     * @return string|null
     */
    public function gpgHome(): ?string
    {
        return $this->getGpgHome();
    }
}
