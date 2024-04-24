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

namespace App\Service\Healthcheck\Environment;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Utility\Filesystem\DirectoryUtility;

class TmpFolderWritableHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
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
        clearstatcache();

        $this->status = true;

        /** @var \SplFileInfo[] $iterator */
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(TMP),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $name => $fileInfo) {
            if (in_array($fileInfo->getFilename(), ['.', '..', 'empty'])) {
                continue;
            }
            // No file should be executable in tmp
            if ($fileInfo->isFile() && DirectoryUtility::isExecutable($name)) {
                $this->status = false;
            }
            if (!$fileInfo->isWritable()) {
                $this->status = false;
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_ENVIRONMENT;
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
        return __('The temporary directory and its content are writable and not executable.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The temporary directory and its content are not writable, or are executable.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __('Ensure the temporary directory and its content are writable by the webserver user.'),
            __('you can try:'),
            'sudo chown -R ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . TMP,
            'sudo chmod -R 775 $(find ' . TMP . ' -type d)',
            'sudo chmod -R 664 $(find ' . TMP . ' -type f)',
        ];
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_ENVIRONMENT;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'tmpWritable';
    }
}
