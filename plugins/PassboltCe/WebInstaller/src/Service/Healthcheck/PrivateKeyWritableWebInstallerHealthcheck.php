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
 * @since         4.6.0
 */

namespace Passbolt\WebInstaller\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Core\Configure;

class PrivateKeyWritableWebInstallerHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $keyFolderWritable = is_writable(dirname($this->getPrivateKeyPath()));
        $privateKeyPath = $this->getPrivateKeyPath();
        $this->status = file_exists($privateKeyPath) ? is_writable($privateKeyPath) : $keyFolderWritable;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_WEB_INSTALLER;
    }

    /**
     * @inheritDoc
     */
    public function isPassed(): bool
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function level(): string
    {
        return HealthcheckServiceCollector::LEVEL_ERROR;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The server OpenPGP private key file is writable.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The server OpenPGP private key file is not writable.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        $privateKeyPath = $this->getPrivateKeyPath();

        return [
            __('Ensure the file {0} is writable by the webserver user.', CONFIG . 'gpg' . DS . $privateKeyPath),
            __('you can try:'),
            'sudo chown ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . CONFIG . 'gpg',
            'sudo chmod 775 $(find ' . CONFIG . 'gpg -type d)',
        ];
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_WEB_INSTALLER;
    }

    /**
     * Returns public key path from the config.
     *
     * @return string
     */
    private function getPrivateKeyPath(): string
    {
        return Configure::read('passbolt.gpg.serverKey.private');
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'privateKeyWritable';
    }
}
