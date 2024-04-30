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
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtAbstractService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;

class DirectoryNotWritableJwtHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
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
        $this->status = !is_writable(JwtAbstractService::JWT_CONFIG_DIR);

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
        return __('The {0} directory is not writable.', JwtAbstractService::JWT_CONFIG_DIR);
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The {0} directory should not be writable.', JwtAbstractService::JWT_CONFIG_DIR);
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array
    {
        return [
            'You can try: ',
            'sudo chown -Rf root:' . PROCESS_USER . ' ' . JwtAbstractService::JWT_CONFIG_DIR,
            'sudo chmod 750 ' . JwtAbstractService::JWT_CONFIG_DIR,
            'sudo chmod 640 ' . JwtTokenCreateService::JWT_SECRET_KEY_PATH,
            'sudo chmod 640 ' . JwksGetService::PUBLIC_KEY_PATH,
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
        return 'jwtWritable';
    }
}
