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

namespace App\Service\Healthcheck\Environment;

use App\Service\Healthcheck\HealthcheckServiceInterface;

class IntlHealthcheck implements HealthcheckServiceInterface
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
        $this->status = extension_loaded('intl');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        // TODO: Use a constant
        return 'environment';
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
        return 'error';
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('Intl extension is installed.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('You must enable the intl extension to use Passbolt.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [__('See. https://secure.php.net/manual/en/book.intl.php')];
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return 'environment';
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'intl';
    }
}
