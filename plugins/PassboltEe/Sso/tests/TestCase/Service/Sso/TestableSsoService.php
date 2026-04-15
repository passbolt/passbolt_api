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

namespace Passbolt\Sso\Test\TestCase\Service\Sso;

use App\Service\Cookie\DefaultSecureCookieService;
use App\Utility\ExtendedUserAccessControl;
use Cake\Http\Cookie\Cookie;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\GenericProvider;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;

class TestableSsoService extends AbstractSsoService
{
    public function __construct(?SsoSettingsDto $settingsDto = null)
    {
        $cookieService = new DefaultSecureCookieService();
        parent::__construct($cookieService, $settingsDto);
    }

    public function createStateCookie(ExtendedUserAccessControl $uac, string $type): Cookie
    {
        $ssoState = SsoStateFactory::make()->withTypeSsoSetSettings()->persist();

        return $this->createHttpOnlySecureCookie($ssoState);
    }

    public function getAuthorizationUrl(ExtendedUserAccessControl $uac): string
    {
        return '/';
    }

    protected function getOAuthProvider(SsoSettingsDto $settings): AbstractProvider
    {
        return new GenericProvider([
            'urlAuthorize' => 'test',
            'urlAccessToken' => 'test',
            'urlResourceOwnerDetails' => 'test',
        ]);
    }

    protected function assertAndGetSsoSettings(): SsoSettingsDto
    {
        $setting = SsoSettingsFactory::make()->persist();

        return new SsoSettingsDto($setting, []);
    }
}
