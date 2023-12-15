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
 * @since         4.6.0
 */

namespace Passbolt\Sso\Model\Dto;

use Passbolt\Sso\Model\Entity\SsoSetting;

/**
 * Data Transfer Object class for Microsoft AD FS provider.
 * Extended from generic OpenID/Oauth2 provider since there are not much different between both.
 */
class SsoSettingsAdfsDataDto extends SsoSettingsOAuth2DataDto
{
    /**
     * @var string
     */
    public $email_claim;

    /**
     * Constructor.
     *
     * @param array $data with
     *  - url string
     *  - client_id string
     *  - client_secret string
     *  - openid_configuration_path string
     *  - scope string
     *  - email_claim string
     * @return void
     */
    public function __construct(array $data)
    {
        // Set common fields
        parent::__construct($data);

        // Set ADFS specific fields
        $this->email_claim = $data['email_claim'] ?? SsoSetting::ADFS_EMAIL_CLAIM_UPN;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        // Append ADFS specific fields
        $data['email_claim'] = $this->email_claim;

        return $data;
    }
}
