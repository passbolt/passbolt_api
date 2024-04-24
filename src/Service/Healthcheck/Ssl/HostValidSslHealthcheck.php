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

namespace App\Service\Healthcheck\Ssl;

class HostValidSslHealthcheck extends AbstractBaseSslHealthcheck
{
    /**
     * @inheritDoc
     */
    protected function getClientOptions(): array
    {
        return [
            'ssl_verify_peer' => true,
            'ssl_verify_host' => true,
            'ssl_allow_self_signed' => true,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('Hostname is matching in SSL certificate.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('Hostname does not match when validating certificates.');
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'hostValid';
    }
}
