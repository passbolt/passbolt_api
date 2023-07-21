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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Yubikey;

use App\Test\Factory\AuthenticationTokenFactory;
use Passbolt\MultiFactorAuthentication\Form\Yubikey\YubikeySetupForm;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Yubikey\MfaYubikeyOrganizationOnlyScenario;

class YubikeySetupGetControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupGet
     */
    public function testMfaSetupGetYubikeyNotAuthenticated()
    {
        $this->get('/mfa/setup/yubikey.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testMfaSetupGetYubikey_Success()
    {
        $user = $this->logInAsUser();
        $this->loadFixtureScenario(MfaYubikeyOrganizationOnlyScenario::class);
        $this->mockValidMfaFormInterface(YubikeySetupForm::class, $this->makeUac($user));
        $this->get('/mfa/setup/yubikey?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertSame(0, AuthenticationTokenFactory::count());
    }
}
