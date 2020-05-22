<?php
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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\Lib;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

class MfaIntegrationTestCase extends AppIntegrationTestCase
{
    use MfaAccountSettingsTestTrait;
    use MfaDuoSettingsTestTrait;
    use MfaOrgSettingsTestTrait;
    use MfaTotpSettingsTestTrait;
    use MfaYubikeySettingsTestTrait;
    use UserAccessControlTrait;

    /**
     * @var array
     */
    public $fixtures = [
        'app.Base/OrganizationSettings',
        'plugin.Passbolt/AccountSettings.AccountSettings',
        'app.Base/AuthenticationTokens', 'app.Base/Users',
        'app.Base/Roles',
    ];

    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.multiFactorAuthentication.enabled', true);
    }

    /**
     * @param string $user firstname
     * @param string|null $provider provider
     */
    public function mockMfaVerified(string $user = 'ada', string $provider = null, $remember = true)
    {
        if (!isset($provider)) {
            throw new InternalErrorException('Cannot mock mfa verification without provider.');
        }
        $uac = $this->mockUserAccessControl($user);
        $token = MfaVerifiedToken::get($uac, $provider, uniqid(), $remember);
        $this->cookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS, $token);
    }
}
