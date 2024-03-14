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

class NotSelfSignedSslHealthcheck extends AbstractBaseSslHealthcheck
{
    /**
     * @inheritDoc
     */
    protected array $helpMessage = [
        'Check https://help.passbolt.com/faq/hosting/troubleshoot-ssl',
    ];

    /**
     * @inheritDoc
     */
    protected function getClientOptions(): array
    {
        return [
            'ssl_verify_peer' => true,
            'ssl_verify_host' => true,
            'ssl_allow_self_signed' => false,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('Not using a self-signed certificate.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('Using a self-signed certificate.');
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'notSelfSigned';
    }
}
