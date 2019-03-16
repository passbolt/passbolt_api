<?php
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

use App\Shell\Task\HealthcheckTask;

/**
 * HealthcheckHtmlHelper
 * Shenanigans to reuse outputs from app/Console/healthcheckTask.php
 */
class HealthcheckHtmlHelper extends HealthcheckTask
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
     * @param string $success message
     * @param string $error message
     * @param string $help optional
     * @return void
     */
    protected function assert($condition, $success, $error, $help = null)
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
     * @param string $success message
     * @param string $warning message
     * @param null $help (optional)
     * @return void
     */
    protected function warning($condition, $success, $warning, $help = null)
    {
        if ($condition) {
            echo '<div class="message success">' . $success . '</div>' . PHP_EOL;
        } else {
            echo '<div class="message warning">' . $warning . '</div>' . PHP_EOL;
        }
    }

    /**
     * Display healthcheck section title
     *
     * @param string $title section title
     * @return void
     */
    protected function title($title)
    {
        echo '<h3>' . $title . '</h3>' . PHP_EOL;
    }
}
