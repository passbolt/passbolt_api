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
namespace Passbolt\Sso\Test\Lib;

use App\Model\Entity\User;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use const PHP_QUERY_RFC3986;

trait AzureProviderTestTrait
{
    public function getDummyAzureAuthorizationUrl(User $user, string $state, array $options = []): string
    {
        $tenantId = UuidFactory::uuid();
        $clientId = UuidFactory::uuid();
        $defaultOptions = [
            'client_id' => $clientId,
            'response_type' => 'code',
            'response_mode' => Configure::read(AbstractSsoService::SSO_SECURITY_REDIRECT_METHOD_CONFIG) === 'POST' ? 'form_post' : 'query',
            'prompt' => SsoSettingsAzureDataForm::PROMPT_LOGIN,
            'login_hint' => $user->username,
            'scope' => 'openid profile email',
            'redirect_uri' => Router::url('/sso/azure/redirect', true),
            'state' => $state,
            'nonce' => SsoState::generate(),
        ];
        $options = array_merge($defaultOptions, $options);
        // prompt should only be set when value is "login"
        if ($options['prompt'] !== SsoSettingsAzureDataForm::PROMPT_LOGIN) {
            unset($options['prompt']);
        }
        // login hint is disabled
        if (!$options['login_hint']) {
            unset($options['login_hint']);
        }
        if (isset($options['tenant_id'])) {
            $tenantId = $options['tenant_id'];
        }

        $queryParams = http_build_query($options, '', null, PHP_QUERY_RFC3986);

        return "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/authorize?" . $queryParams;
    }
}
