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

namespace App\Service\Healthcheck;

interface HealthcheckServiceInterface
{
    /**
     * Performs the actual check and returns itself.
     *
     * @return $this
     */
    public function check(): HealthcheckServiceInterface;

    /**
     * Health check domain key this check belongs to.
     *
     * @return string
     */
    public function domain(): string;

    /**
     * If health check is passed or not.
     *
     * @return bool
     */
    public function isPassed(): bool;

    /**
     * The severity level of this check.
     *
     * @return string
     */
    public function level(): string;

    /**
     * Returns the message to display when check is passed.
     *
     * @return string
     */
    public function getSuccessMessage(): string;

    /**
     * Returns the message to display when check is failed.
     *
     * @return string
     */
    public function getFailureMessage(): string;

    /**
     * Returns the message to display additional info to users.
     *
     * @return string|array|null
     */
    public function getHelpMessage();

    /**
     * Returns the array key used when returning check result.
     *
     * @deprecated As of v4.7.0, this is mostly used to keep BC.
     * @return string
     */
    public function getLegacyArrayKey(): string;
}
