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
 * @since         3.8.0
 */
namespace App\Authenticator;

use App\Utility\OpenPGP\OpenPGPBackendInterface;
use Cake\Http\Exception\BadRequestException;

trait GpgAuthenticatorTrait
{
    /**
     * @param \App\Utility\OpenPGP\OpenPGPBackendInterface $gpg GPG instance
     * @param mixed $gpgMessage GPG message
     * @param string $errorMessage Error message to throw if GPG message is not valid
     * @throws \Cake\Http\Exception\BadRequestException If GPG message is not valid
     * @return void
     */
    public function assertGpgMessageIsValid(?OpenPGPBackendInterface $gpg, $gpgMessage, string $errorMessage)
    {
        if (
            !isset($gpgMessage) ||
            !is_string($gpgMessage) ||
            !$gpg->isValidMessage($gpgMessage)
        ) {
            throw new BadRequestException($errorMessage);
        }
    }
}
