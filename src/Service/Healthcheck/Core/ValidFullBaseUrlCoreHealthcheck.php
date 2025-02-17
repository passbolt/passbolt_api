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

namespace App\Service\Healthcheck\Core;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Core\Configure;

class ValidFullBaseUrlCoreHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * @var mixed $url
     */
    private $url;

    /**
     * @param mixed $url url
     */
    public function __construct($url = null)
    {
        $fullBaseUrl = $url ?? Configure::read('App.fullBaseUrl');
        if (!is_string($fullBaseUrl)) {
            $fullBaseUrl = gettype($fullBaseUrl);
        }

        $this->url = $fullBaseUrl;
    }

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->status = false;
        if (is_string($this->url)) {
            $this->status = (strpos($this->url, 'https://') === 0) || (strpos($this->url, 'http://') === 0);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_CORE;
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
        return __('App.fullBaseUrl validation OK.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('App.fullBaseUrl does not validate. A valid URL/IP is accepted, but found "{0}".', $this->url);
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __('Edit App.fullBaseUrl in {0}', CONFIG . 'passbolt.php'),
            __('Select a valid domain name as defined by section 2.3.1 of http://www.ietf.org/rfc/rfc1035.txt'),
            __('IMPORTANT: Using an empty App.fullBaseUrl can lead to host header injection attack: https://owasp.org/www-project-web-security-testing-guide/v42/4-Web_Application_Security_Testing/07-Input_Validation_Testing/17-Testing_for_Host_Header_Injection'), // phpcs:ignore
        ];
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_CORE;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'validFullBaseUrl';
    }
}
