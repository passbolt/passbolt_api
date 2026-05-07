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
namespace Passbolt\Sso\Test\Lib;

use App\Model\Entity\User;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Routing\Router;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;

trait SsoProviderTestTrait
{
    /**
     * @param array $responsesQueue Response queue.
     * @return Client
     * @link https://docs.guzzlephp.org/en/stable/testing.html
     */
    public function mockHttpClientResponse(array $responsesQueue): Client
    {
        $mockHandler = new MockHandler($responsesQueue);
        $handlerStack = HandlerStack::create($mockHandler);

        return new Client(['handler' => $handlerStack]);
    }

    public function getDummyAdfsAuthorizationUrl(User $user, string $state, array $options = []): string
    {
        $defaultOptions = [
            'client_id' => UuidFactory::uuid(),
            'response_type' => 'code',
            'response_mode' => Configure::read(AbstractSsoService::SSO_SECURITY_REDIRECT_METHOD_CONFIG) === 'POST' ? 'form_post' : 'query',
            'login_hint' => $user->username,
            'scope' => 'openid profile email',
            'redirect_uri' => Router::url('/sso/adfs/redirect', true),
            'state' => $state,
            'nonce' => SsoState::generate(),
        ];
        $options = array_merge($defaultOptions, $options);
        // login hint is disabled
        if (!$options['login_hint']) {
            unset($options['login_hint']);
        }

        $queryParams = http_build_query($options, '', null, PHP_QUERY_RFC3986);

        return 'https://adfs.passbolt.test/authorize?' . $queryParams;
    }
}
