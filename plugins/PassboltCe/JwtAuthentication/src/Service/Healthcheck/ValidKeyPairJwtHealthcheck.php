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

namespace Passbolt\JwtAuthentication\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtKeyPairService;

class ValidKeyPairJwtHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    protected JwtKeyPairService $jwtKeyPairService;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->jwtKeyPairService = new JwtKeyPairService();
        try {
            $this->jwtKeyPairService->validateKeyPair();
            $this->status = true;
        } catch (\Throwable $e) {
            // Do nothing
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_JWT;
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
        return __('A valid JWT key pair was found.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('A valid JWT key pair is missing.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array
    {
        return [
            __('Run the create JWT keys script to create a valid JWT secret and public key pair:'),
            'sudo su -s /bin/bash -c "' . $this->jwtKeyPairService->getCreateJwtKeysCommand() . '" ' . PROCESS_USER,
        ];
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_JWT;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'keyPairValid';
    }
}
