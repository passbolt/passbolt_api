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

namespace App\Service\Healthcheck\Application;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Passbolt\SelfRegistration\Service\Healthcheck\SelfRegistrationHealthcheckService;

class SelfRegistrationPublicRemovedApplicationHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface // phpcs:ignore
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * @var \Passbolt\SelfRegistration\Service\Healthcheck\SelfRegistrationHealthcheckService
     */
    private SelfRegistrationHealthcheckService $selfRegistrationHealthcheckService;

    /**
     * @param \Passbolt\SelfRegistration\Service\Healthcheck\SelfRegistrationHealthcheckService $selfRegistrationHealthcheckService Self registration health check service.
     */
    public function __construct(SelfRegistrationHealthcheckService $selfRegistrationHealthcheckService)
    {
        $this->selfRegistrationHealthcheckService = $selfRegistrationHealthcheckService;
    }

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $selfRegistrationChecks = $this->selfRegistrationHealthcheckService->getHealthcheck();
        $this->status = $selfRegistrationChecks['isRegistrationPublicRemovedFromPassbolt'];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_APPLICATION;
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
        return HealthcheckServiceCollector::LEVEL_WARNING;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The deprecated self registration public setting was not found in {0}.', CONFIG . 'passbolt.php');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The deprecated self registration public setting was found in {0}.', CONFIG . 'passbolt.php');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return __('You may remove the "passbolt.registration.public" setting.');
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_APPLICATION;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'registrationClosed.isRegistrationPublicRemovedFromPassbolt';
    }
}
