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

class PrivateKeyReadableAndParsableGpgHealthcheck extends AbstractGpgHealthcheck
{
    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        if (!$this->isPrivateServerKeyReadable()) {
            return $this;
        }

        $privateKeyData = file_get_contents($this->getPrivateServerKey());
        $blockStart = '-----BEGIN PGP PRIVATE KEY BLOCK-----';
        $this->status = strpos($privateKeyData, $blockStart) === 0;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The private key file is defined in {0} and readable.', CONFIG . 'passbolt.php');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The private key file is not defined in {0} or not readable.', CONFIG . 'passbolt.php');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __('Ensure the private key file is defined by the variable passbolt.gpg.serverKey.private in {0}.', CONFIG . 'passbolt.php'),// phpcs:ignore
            __('Ensure there is a private key armored block in the key file.'),
            __('Ensure the private key defined in {0} exists and is accessible by the webserver user.', CONFIG . 'passbolt.php'),// phpcs:ignore
            __('See. https://www.passbolt.com/help/tech/install#toc_gpg'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'gpgKeyPrivateBlock';
    }
}
