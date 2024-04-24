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

class SmtpSettingsSettingsSourceHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    private string $source = '';

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
        $getService = new SmtpSettingsGetService($this->passboltFileName);

        try {
            $smtpSettings = $getService->getSettings();
            $source = $smtpSettings['source'];
        } catch (FormValidationException $e) {
            $source = $e->getForm()->getData('source');
        } catch (InternalErrorException $e) {
            $source = SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_DB;
        } catch (\Throwable $e) {
            $source = SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_UNDEFINED;
        }

        $this->source = $this->mapSource($source) ?? __('not found');
        $isInDb = $source === SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_DB;
        $this->status = $isInDb;

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
        return HealthcheckServiceCollector::LEVEL_WARNING;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The SMTP Settings source is: {0}.', $this->source);
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return $this->getSuccessMessage();
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): string
    {
        return __('It is recommended to set the SMTP Settings in the database through the administration section.');
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
     * The case where the source is not defined should code-based never occur, but just in case
     * and in order preserve the healthcheck integrity we cover the un defined case.
     *
     * @param string $source source to map into human intelligible string
     * @return string|null
     */
    protected function mapSource(string $source): ?string
    {
        $map = [
            SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_DB => __('database'),
            SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_FILE => CONFIG . 'passbolt.php',
            SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_ENV => __('env variables'),
            SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_UNDEFINED => __('undefined'),
        ];

        return $map[$source] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'isInDb';
    }
}
