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

namespace App\Service\Healthcheck\Gpg;

use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Http\Exception\InternalErrorException;

class PhpGpgModuleInstalledGpgHealthcheck extends AbstractGpgHealthcheck
{
    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        try {
            OpenPGPBackendFactory::get();
            $this->status = true;
        } catch (InternalErrorException $e) {
            // Do nothing
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('PHP GPG Module is installed and loaded.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('PHP GPG Module is not installed or loaded.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return __('Install php-gnupg, see. http://php.net/manual/en/gnupg.installation.php') .
            __('Make sure to add extension=gnupg.so in php ini files for both php-cli and php.');
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'lib';
    }
}
