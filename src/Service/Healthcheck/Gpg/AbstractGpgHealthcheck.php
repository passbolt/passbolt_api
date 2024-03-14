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

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;

abstract class AbstractGpgHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    protected bool $status = false;

    /**
     * @return string|null
     */
    protected function getServerKeyFingerprint(): ?string
    {
        return Configure::read('passbolt.gpg.serverKey.fingerprint');
    }

    /**
     * @return string|null
     */
    protected function getPublicServerKey(): ?string
    {
        return Configure::read('passbolt.gpg.serverKey.public');
    }

    /**
     * @return string|null
     */
    protected function getPrivateServerKey(): ?string
    {
        return Configure::read('passbolt.gpg.serverKey.private');
    }

    /**
     * @return bool
     */
    protected function isPublicServerKeyReadable(): bool
    {
        $publicServerKey = $this->getPublicServerKey();

        return $publicServerKey !== null && is_readable($publicServerKey);
    }

    /**
     * @return bool
     */
    protected function isPrivateServerKeyReadable(): bool
    {
        $privateServerKey = $this->getPrivateServerKey();

        return $privateServerKey !== null && is_readable($privateServerKey);
    }

    /**
     * @return string|null
     */
    protected function getServerKeyPassphrase(): ?string
    {
        return Configure::read('passbolt.gpg.serverKey.passphrase');
    }

    /**
     * @return string|null
     */
    protected function getGpgHome(): ?string
    {
        switch (Configure::read('passbolt.gpg.backend')) {
            case OpenPGPBackendFactory::GNUPG:
                // If no keyring location has been set, use the default one ~/.gnupg.
                $gpgHome = getenv('GNUPGHOME');
                if (empty($gpgHome)) {
                    $uid = posix_getuid();
                    $user = posix_getpwuid($uid);
                    $gpgHome = $user['dir'] . '/.gnupg';
                }
                break;
            case OpenPGPBackendFactory::HTTP:
                // using cache for local keyring
                $gpgHome = 'Cache engine';
                break;
            default:
                // unknown backend
                $gpgHome = null;
                break;
        }

        return $gpgHome;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_GPG;
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
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_GPG;
    }
}
