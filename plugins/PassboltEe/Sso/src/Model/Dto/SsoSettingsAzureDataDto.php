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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Model\Dto;

use Cake\Chronos\Chronos;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Model\Entity\SsoSetting;

/**
 * AzureDataDto Data Transfer Object
 */
class SsoSettingsAzureDataDto implements SsoSettingsDataDtoInterface
{
    /**
     * @var string $url
     */
    public string $url;

    /**
     * @var string $client_id uuid
     */
    public string $client_id;

    /**
     * @var string $client_secret
     */
    public string $client_secret;

    /**
     * @var \Cake\Chronos\Chronos|string
     */
    public string|Chronos $client_secret_expiry;

    /**
     * @var string $tenant_id
     */
    public string $tenant_id;

    /**
     * @var string
     */
    public string $prompt;

    /**
     * @var string
     */
    public string $email_claim;

    /**
     * @param array $data with
     *  - url string
     *  - client_id string uuid
     *  - tenant_id string uuid
     *  - client_secret string
     *  - client_secret_expiry string|datetime
     *  - prompt string
     */
    public function __construct(array $data)
    {
        $this->url = $data['url'] ?? '';
        $this->client_id = $data['client_id'] ?? '';
        $this->tenant_id = $data['tenant_id'] ?? '';
        $this->client_secret = $data['client_secret'] ?? '';
        $this->client_secret_expiry = $data['client_secret_expiry'] ?? '';
        // BExt BC: Set default value for email claim & prompt, otherwise it can break older installs
        $this->prompt = $data['prompt'] ?? SsoSettingsAzureDataForm::PROMPT_LOGIN;
        $this->email_claim = $data['email_claim'] ?? SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL;
    }

    /**
     * @return array
     *  - url string
     *  - client_id string uuid
     *  - tenant_id string uuid
     *  - client_secret string
     *  - client_secret_expiry string
     *  - prompt string
     */
    public function toArray(): array
    {
        $result = [
            'url' => $this->url,
            'client_id' => $this->client_id,
            'tenant_id' => $this->tenant_id,
            'client_secret' => $this->client_secret,
            'client_secret_expiry' => $this->client_secret_expiry,
            'prompt' => $this->prompt,
            'email_claim' => $this->email_claim,
        ];

        // Serialize date if it's not already a string
        if ($result['client_secret_expiry'] instanceof Chronos) {
            $result['client_secret_expiry'] = $this->client_secret_expiry->toDateTimeString();
        }

        return $result;
    }
}
