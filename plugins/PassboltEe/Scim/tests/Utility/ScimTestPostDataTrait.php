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

namespace Passbolt\Scim\Test\Utility;

/**
 * Trait with utility function for testing scim users operations
 */
trait ScimTestPostDataTrait
{
    /**
     * @param string $scimName
     * @param string $externalId
     * @param string $email
     * @return array
     */
    protected function getUserPostData(
        string $scimName = 'user1@username.com',
        string $externalId = '4d36b536-42ba-4a65-9299-c4461222b47f',
        string $email = 'user1@email.com',
        string $firstName = 'User 1',
    ): array
    {
        return [
            'schemas' => [
                'urn:ietf:params:scim:schemas:core:2.0:User',
            ],
            'externalId' => $externalId,
            'userName' => $scimName,
            'active' => true,
            'displayName' => 'User 1 Scim',
            'emails' => [
                [
                    'primary' => true,
                    'type' => 'work',
                    'value' => $email,
                ]
            ],
            'meta' => [
                'resourceType' => 'User',
            ],
            'name' => [
                'formatted' => 'User 1 Scim',
                'familyName' => 'Scim',
                'givenName' => $firstName,
            ],
        ];
    }
}
