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
namespace Passbolt\Sso\Test\Lib;

use App\Model\Entity\User;
use App\Utility\UuidFactory;
use Cake\Routing\Router;
use Passbolt\Sso\Model\Entity\SsoState;

trait PingOneProviderTestTrait
{
    /**
     * @param \App\Model\Entity\User $user User entity
     * @param string $state State token
     * @param array $options Optional overrides
     * @return string
     */
    public function getDummyPingOneAuthorizationUrl(User $user, string $state, array $options = []): string
    {
        $environmentId = $options['environment_id'] ?? UuidFactory::uuid();
        unset($options['environment_id']);

        $defaultOptions = [
            'client_id' => UuidFactory::uuid(),
            'response_type' => 'code',
            'login_hint' => $user->username,
            'scope' => 'openid email profile',
            'redirect_uri' => Router::url('/sso/pingone/redirect', true),
            'state' => $state,
            'nonce' => SsoState::generate(),
        ];
        $options = array_merge($defaultOptions, $options);

        if (!$options['login_hint']) {
            unset($options['login_hint']);
        }

        $queryParams = http_build_query($options, '', null, PHP_QUERY_RFC3986);

        return 'https://auth.pingone.com/' . $environmentId . '/as/authorize?' . $queryParams;
    }
}
