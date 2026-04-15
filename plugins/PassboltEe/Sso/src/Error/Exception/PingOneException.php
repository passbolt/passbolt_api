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
 * @since         5.11.0
 */
namespace Passbolt\Sso\Error\Exception;

use Cake\Log\Log;

class PingOneException extends OAuth2Exception
{
    /**
     * @see https://docs.pingidentity.com/pingoneforenterprise/p14e_c_error_codes.html
     * @return array<string>
     */
    public function allowedErrors(): array
    {
        return [
            /*
             * invalid_request
             * The request is missing a required parameter, includes an invalid parameter value,
             * includes a parameter more than once, or is otherwise malformed.
             */
            'invalid_request',

            /*
             * unauthorized_client
             * The client is not authorized to request an authorization code using this method.
             */
            'unauthorized_client',

            /*
             * access_denied
             * The resource owner or authorization server denied the request.
             */
            'access_denied',

            /*
             * unsupported_response_type
             * The authorization server does not support obtaining an authorization code using this method.
             */
            'unsupported_response_type',

            /*
             * invalid_scope
             * The requested scope is invalid, unknown, or malformed.
             */
            'invalid_scope',

            /*
             * server_error
             * The authorization server encountered an unexpected condition that prevented it
             * from fulfilling the request.
             */
            'server_error',

            /*
             * temporarily_unavailable
             * The authorization server is currently unable to handle the request due to a
             * temporary overloading or maintenance of the server.
             */
            'temporarily_unavailable',
        ];
    }

    /**
     * @return void
     */
    protected function logError(): void
    {
        Log::error('Unknown PingOne error: ' . $this->error);
    }
}
