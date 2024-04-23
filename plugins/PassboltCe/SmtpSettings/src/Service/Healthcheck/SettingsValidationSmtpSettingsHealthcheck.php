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

namespace Passbolt\SmtpSettings\Service\Healthcheck;

use App\Error\Exception\FormValidationException;
use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\SmtpSettings\Service\SmtpSettingsGetService;

class SettingsValidationSmtpSettingsHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    private string $validationError = '';

    private string $passboltFileName;

    /**
     * @param string $passboltFileName The passbolt config file, modifiable for unit test purpose.
     */
    public function __construct(string $passboltFileName = CONFIG . DS . 'passbolt.php')
    {
        $this->passboltFileName = $passboltFileName;
    }

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        try {
            $getService = new SmtpSettingsGetService($this->passboltFileName);
            $getService->getSettings();
            $this->status = true;
        } catch (FormValidationException $e) {
            $this->validationError = json_encode($e->getErrors());
        } catch (InternalErrorException $e) {
            $this->validationError = $e->getMessage();
        } catch (\Throwable $e) {
            $this->validationError = $e->getMessage();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_SMTP_SETTINGS;
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
        return __('SMTP Settings coherent. You may send a test email to validate them.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('SMTP Setting errors: {0}', $this->validationError);
    }

    /**
     * @return string
     */
    public function getValidationError(): string
    {
        return $this->validationError;
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): ?string
    {
        return null;
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_SMTP_SETTINGS;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'errorMessage';
    }
}
