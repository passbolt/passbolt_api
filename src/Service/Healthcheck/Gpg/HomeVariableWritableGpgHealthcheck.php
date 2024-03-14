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
use Cake\Core\Configure;

class HomeVariableWritableGpgHealthcheck extends HomeVariableDefinedGpgHealthcheck implements SkipHealthcheckInterface
{
    private bool $isSkipped = false;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        // Check if the GPG Home variable is well-defined and file exists
        parent::check();
        if ($this->status === false) {
            $this->markAsSkipped();

            return $this;
        }

        if (Configure::read('passbolt.gpg.backend') === OpenPGPBackendFactory::GNUPG) {
            $this->status = is_writable($this->gpgHome);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The directory {0} containing the keyring is writable by the webserver user.', $this->gpgHome);
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __(
            'The directory {0} containing the keyring is not writable by the webserver user.',
            $this->gpgHome
        );
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __('Ensure the keyring location is accessible by the webserver user.'),
            __('you can try:'),
            'sudo chown -R ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . $this->gpgHome,
            'sudo chmod 700 ' . $this->gpgHome,
        ];
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
        return 'gpgHomeWritable';
    }
}
