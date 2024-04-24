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
use Cake\Core\Configure;

class HomeVariableDefinedGpgHealthcheck extends AbstractGpgHealthcheck
{
    protected ?string $gpgHome;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->gpgHome = $this->getGpgHome();
        if (is_null($this->gpgHome)) {
            return $this;
        }

        switch (Configure::read('passbolt.gpg.backend')) {
            case OpenPGPBackendFactory::GNUPG:
                $this->status = file_exists($this->getGpgHome());
                break;
            case OpenPGPBackendFactory::HTTP:
                $this->status = true;
                break;
            default:
                break;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The environment variable GNUPGHOME is set to {0}.', $this->gpgHome);
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __(
            'The environment variable GNUPGHOME is set to {0}, but the directory does not exist.',
            $this->gpgHome
        );
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __('Ensure the keyring location exists and is accessible by the webserver user.'),
            __('you can try:'),
            'sudo mkdir -p ' . $this->gpgHome,
            'sudo chown -R ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . $this->gpgHome,
            'sudo chmod 700 ' . $this->gpgHome,
            __('You can change the location of the keyring by editing the GPG.env.setenv and GPG.env.home variables in {0}.', CONFIG . 'passbolt.php'),// phpcs:ignore
        ];
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'gpgHome';
    }
}
