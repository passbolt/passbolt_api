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
 * @since         2.0.0
 */
namespace App\View\Helper;

use App\Command\HealthcheckCommand;

/**
 * HealthcheckHtmlHelper
 * Shenanigans to reuse outputs from app/Console/healthcheckTask.php
 */
class HealthcheckHtmlHelper extends HealthcheckCommand
{
    /**
     * HealthcheckHtmlHelper constructor
     * Does nothing unlike the task
     */
    public function __construct()
    {
    }

    /**
     * Display assert result as HTML formatted section
     *
     * @param bool $condition to check
     * @param string|string[] $success to display when success
     * @param string|string[] $error to display when error
     * @param string|string[]|null $help string optional help message
     * @return void
     */
    protected function assert(bool $condition, $success, $error, $help = null)
    {
        if ($condition) {
            echo '<div class="message success">' . $success . '</div>' . PHP_EOL;
        } else {
            echo '<div class="message error">' . $error . '</div>' . PHP_EOL;
        }
    }

    /**
     * Display warning result as HTML formatted section
     *
     * @param bool $condition to check
     * @param string|string[] $success message to display when success
     * @param string|string[] $warning message to display if fails
     * @param string|string[]|null $help optional help message
     * @return void
     */
    protected function warning(bool $condition, $success, $warning, $help = null)
    {
        if ($condition) {
            echo '<div class="message success">' . $success . '</div>' . PHP_EOL;
        } else {
            echo '<div class="message warning">' . $warning . '</div>' . PHP_EOL;
        }
    }

    /**
     * @inheritDoc
     */
    protected function notice(bool $condition, $success, $fail, $help = null): void
    {
        // Notices, just like help messages, are not displayed
    }

    /**
     * Display healthcheck section title
     *
     * @param string $title section title
     * @return void
     */
    protected function title(string $title)
    {
        echo '<h2>' . $title . '</h2>' . PHP_EOL;
    }
}
