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

use App\Model\Entity\Gpgkey;
use App\Model\Rule\Gpgkeys\GopengpgFormatRule;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\Healthcheck\SkipHealthcheckInterface;

class GopengpgPublicKeyFormatGpgHealthcheck extends AbstractGpgHealthcheck implements SkipHealthcheckInterface
{
    private PublicKeyInKeyringGpgHealthcheck $publicKeyInKeyringGpgHealthcheck;

    private bool $isSkipped = false;

    /**
     * @param \App\Service\Healthcheck\Gpg\PublicKeyInKeyringGpgHealthcheck $publicKeyInKeyringGpgHealthcheck service
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
            $this->markAsSkipped();

            return $this;
        }

        // Gpg keys should have only one return line
        $publicKey = new Gpgkey();
        $publicKey->armored_key = file_get_contents($this->getPublicServerKey());
        $rule = new GopengpgFormatRule();
        $this->status = $rule($publicKey);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The server public key format is Gopengpg compatible.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The server public key format is not Gopengpg compatible.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return 'Remove all empty new lines above the end block line.';
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
        return 'isPublicServerKeyGopengpgCompatible';
    }
}
