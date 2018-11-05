<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\Lib;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

class MfaIntegrationTestCase extends AppIntegrationTestCase
{
    use MfaAccountSettingsTestTrait;
    use MfaDuoSettingsTestTrait;
    use MfaTotpSettingsTestTrait;
    use MfaYubikeySettingsTestTrait;
    use UserAccessControlTrait;

    /**
     * @var array
     */
    public $fixtures = [
        'app.Base/organization_settings',
        'plugin.passbolt/account_settings.account_settings',
        'app.Base/authentication_tokens', 'app.Base/users',
        'app.Base/roles'
    ];

    /**
     * Setup.
     */
    public function setUp()
    {
        Configure::write('passbolt.plugins', []);
        $this->enableCsrfToken();
        $this->useHttpServer(true);
    }

    /**
     * @param string $user firstname
     * @param string|null $provider provider
     */
    public function mockMfaVerified(string $user = 'ada', string $provider = null)
    {
        if (!isset($provider)) {
            throw new InternalErrorException('Cannot mock mfa verification without provider.');
        }
        $uac = $this->mockUserAccessControl($user);
        $token = MfaVerifiedToken::get($uac, $provider);
        $this->cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, $token);
    }
}
