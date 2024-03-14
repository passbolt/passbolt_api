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
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Utility\OpenPGP\OpenPGPBackendFactory;

class PublicKeyEmailGpgHealthcheck extends AbstractGpgHealthcheck
{
    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $fingerprint = $this->getServerKeyFingerprint();
        if ($this->isPublicServerKeyReadable() && $this->isPrivateServerKeyReadable() && is_string($fingerprint)) {
            $gpg = OpenPGPBackendFactory::get();
            $publicKeyData = file_get_contents($this->getPublicServerKey());
            $publicKeyInfo = $gpg->getPublicKeyInfo($publicKeyData);
            $this->status = is_string($publicKeyInfo['uid']) &&
                PublicKeyValidationService::uidContainValidEmail($publicKeyInfo['uid']);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('There is a valid email id defined for the server key.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The server key does not have a valid email id.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return 'Edit or generate another key with a valid email id.';
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'gpgKeyPublicEmail';
    }
}
