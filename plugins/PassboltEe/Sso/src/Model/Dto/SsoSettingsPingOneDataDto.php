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

namespace Passbolt\Sso\Model\Dto;

use Passbolt\Sso\Model\Entity\SsoSetting;

/**
 * Data Transfer Object class for PingOne SSO provider.
 * Extended from generic OpenID/OAuth2 provider DTO.
 */
class SsoSettingsPingOneDataDto extends SsoSettingsOAuth2DataDto
{
    /**
     * Default OpenID configuration path for PingOne.
     */
    public const DEFAULT_OPENID_CONFIGURATION_PATH = '/.well-known/openid-configuration';

    /**
     * Default OAuth2 scopes for PingOne.
     */
    public const DEFAULT_SCOPE = 'openid email profile';

    /**
     * @var string
     */
    public string $environment_id;

    /**
     * @var string
     */
    public string $email_claim;

    /**
     * Constructor.
     *
     * @param array $data with
     *  - url string
     *  - environment_id string
     *  - client_id string
     *  - client_secret string
     *  - openid_configuration_path string
     *  - scope string
     *  - email_claim string
     * @return void
     */
    public function __construct(array $data)
    {
        // Set common OAuth2 fields
        parent::__construct($data);

        // Set PingOne specific fields
        $this->environment_id = $data['environment_id'] ?? '';
        $this->email_claim = $data['email_claim'] ?? SsoSetting::PINGONE_EMAIL_CLAIM_EMAIL;

        // Always set PingOne defaults for these fields
        $this->openid_configuration_path = self::DEFAULT_OPENID_CONFIGURATION_PATH;
        $this->scope = self::DEFAULT_SCOPE;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        // Append PingOne specific fields
        $data['environment_id'] = $this->environment_id;
        $data['email_claim'] = $this->email_claim;

        return $data;
    }
}
