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
 * @since         4.0.0
 */

namespace Passbolt\Sso\Model\Dto;

/**
 * Data Transfer Object class for SSO Google provider.
 */
class SsoSettingsGoogleDataDto implements SsoSettingsDataDtoInterface
{
    /**
     * @var string
     */
    public $client_id;

    /**
     * @var string
     */
    public $client_secret;

    /**
     * Constructor.
     *
     * @param array $data with
     *  - client_id string uuid
     *  - client_secret string
     * @return void
     */
    public function __construct(array $data)
    {
        $this->client_id = $data['client_id'] ?? '';
        $this->client_secret = $data['client_secret'] ?? '';
    }

    /**
     * @return array Containing client_id and client_secret string.
     */
    public function toArray(): array
    {
        return [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ];
    }
}
