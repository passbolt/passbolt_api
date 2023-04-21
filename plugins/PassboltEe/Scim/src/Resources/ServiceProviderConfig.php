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
 * @since         4.1.0
 */

namespace Passbolt\Scim\Resources;

class ServiceProviderConfig
{
    /**
     * User Schema
     */
    public const CONFIG = [
        'schemas' => [
            'urn:ietf:params:scim:schemas:core:2.0:ServiceProviderConfig',
        ],
        'documentationUri' => 'https://help.passbolt.com/scim/',
        'patch' => [
            'supported' => true,
        ],
        'bulk' => [
            'supported' => false,
            'maxOperations' => 0,
            'maxPayloadSize' => 0,
        ],
        'filter' => [
            'supported' => true,
            'maxResults' => 25,
        ],
        'changePassword' => [
            'supported' => false,
        ],
        'sort' => [
            'supported' => false,
        ],
        'etag' => [
            'supported' => false,
        ],
        'authenticationSchemes' => [
            [
                'name' => 'OAuth Bearer Token',
                'description' => 'Authentication scheme using the OAuth Bearer Token Standard',
                'specUri' => 'http://www.rfc-editor.org/info/rfc6750',
                'documentationUri' => 'https://help.passbolt.com/scim/authentication',
                'type' => 'oauthbearertoken',
                'primary' => true,
            ],
        ],
        'meta' => [
            'location' => '{scimUrl}/ServiceProviderConfig',
            'resourceType' => 'ServiceProviderConfig',
            'created' => '2023-04-26T00:00Z',
            'lastModified' => '2023-04-26T00:00Z',
            'version' => '1',
        ],
    ];
}
