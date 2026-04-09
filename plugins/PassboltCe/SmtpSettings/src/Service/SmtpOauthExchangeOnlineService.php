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
namespace Passbolt\SmtpSettings\Service;

use Cake\Http\Client;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;
use Cake\Validation\Validation;

class SmtpOauthExchangeOnlineService
{
    /**
     * Microsoft OAuth2 token endpoint.
     * The __TENANT_ID__ placeholder is replaced at runtime.
     *
     * @see https://learn.microsoft.com/en-us/entra/identity-platform/v2-oauth2-client-creds-grant-flow#get-a-token
     */
    public const LOGIN_TOKEN_URL = 'https://login.microsoftonline.com/__TENANT_ID__/oauth2/v2.0/token';

    /**
     * The scope required for SMTP send on Office 365.
     */
    public const SCOPE = 'https://outlook.office365.com/.default';

    /**
     * OAuth2 configuration fields expected in the config array.
     */
    public const OAUTH2_FIELDS = ['tenant_id', 'client_id', 'client_secret', 'oauth_username'];

    /**
     * @var \Cake\Http\Client
     */
    private Client $httpClient;

    /**
     * @var string
     */
    private string $tenantId;

    /**
     * @var string
     */
    private string $clientId;

    /**
     * @var string
     */
    private string $clientSecret;

    /**
     * @var string
     */
    private string $username;

    /**
     * @param array $config OAuth2 configuration with keys: tenant_id, client_id, client_secret, oauth_username.
     * @param \Cake\Http\Client|null $httpClient Optional HTTP client (for testing/DI).
     */
    public function __construct(array $config, ?Client $httpClient = null)
    {
        $this->assertConfiguration($config);

        $this->tenantId = $config['tenant_id'];
        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->username = $config['oauth_username'];
        // default timeout is 30 (same) but added here for more visibility
        $this->httpClient = $httpClient ?? new Client(['timeout' => 30]);
    }

    /**
     * Add basic data validation check to reduce SSRF risk.
     * We are not using form class as it can create overhead in this scenario.
     *
     * @param array $config Configuration to check.
     * @return void
     */
    private function assertConfiguration(array $config): void
    {
        if (!Validation::uuid($config['tenant_id'])) {
            throw new InternalErrorException(__('Tenant ID should be a valid UUID.'));
        }
        if (!Validation::uuid($config['client_id'])) {
            throw new InternalErrorException(__('Client ID should be a valid UUID.'));
        }
    }

    /**
     * Fetch an OAuth2 access token from Microsoft using client credentials grant.
     *
     * @return string The access token.
     * @throws \Cake\Http\Exception\InternalErrorException If the token request fails.
     * @see https://learn.microsoft.com/en-us/entra/identity-platform/v2-oauth2-client-creds-grant-flow#get-a-token
     */
    public function getAccessToken(): string
    {
        $tokenUrl = str_replace('__TENANT_ID__', $this->tenantId, self::LOGIN_TOKEN_URL);

        $response = $this->httpClient->post($tokenUrl, [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => self::SCOPE,
        ]);

        if (!$response->isOk()) {
            $body = $response->getJson();
            $error = $body['error_description'] ?? $body['error'] ?? 'Unknown error';
            Log::error(sprintf('SMTP OAuth2 token fetch failed: %s', $error));
            throw new InternalErrorException(__('Failed to obtain SMTP OAuth2 access token.'));
        }

        $body = $response->getJson();
        if (empty($body['access_token'])) {
            throw new InternalErrorException(
                __('SMTP OAuth2 token response from Microsoft did not contain an access token.')
            );
        }

        return $body['access_token'];
    }

    /**
     * Get the OAuth2 username (email address of the sending mailbox).
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Check if the given SMTP settings array contains valid OAuth2 client credentials configuration.
     *
     * @param array $smtpSettings SMTP settings array.
     * @return bool
     */
    public static function isOauth2ClientCredentials(array $smtpSettings): bool
    {
        foreach (self::OAUTH2_FIELDS as $field) {
            if (empty($smtpSettings[$field])) {
                return false;
            }
        }

        return true;
    }
}
