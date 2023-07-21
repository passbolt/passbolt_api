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
 * @since         3.11.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\Duo;

use App\Model\Entity\AuthenticationToken;
use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoGetSdkClientService;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaDuoGetSdkClientServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public function testMfaDuoGetSdkClientService_InvalidSettings()
    {
        $settings = new MfaOrgSettingsDuoService([
            MfaSettings::PROVIDER_DUO => [
                MfaOrgSettings::DUO_CLIENT_ID => '',
                MfaOrgSettings::DUO_CLIENT_SECRET => '',
                MfaOrgSettings::DUO_API_HOSTNAME => '',
            ],
        ]);
        $service = new MfaDuoGetSdkClientService();

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('Could not validate the Duo settings.');
        $service->getOrFail($settings, AuthenticationToken::TYPE_MFA_SETUP);
    }

    public function testMfaDuoGetSdkClientService_getCallbackUrl_Success_Setup()
    {
        $service = new MfaDuoGetSdkClientService();
        $expectedUrl = Router::url('/mfa/setup/duo/callback', true);
        $url = $service->getCallbackRedirectUrl(AuthenticationToken::TYPE_MFA_SETUP);

        $this->assertEquals($expectedUrl, $url);
    }

    public function testMfaDuoGetSdkClientService_getCallbackUrl_Success_Verify()
    {
        $service = new MfaDuoGetSdkClientService();
        $expectedUrl = Router::url('/mfa/verify/duo/callback', true);
        $url = $service->getCallbackRedirectUrl(AuthenticationToken::TYPE_MFA_VERIFY);

        $this->assertEquals($expectedUrl, $url);
    }

    public function testMfaDuoGetSdkClientService_getCallbackUrl_Error_Invalid_Token_Type()
    {
        $service = new MfaDuoGetSdkClientService();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The authentication token type should be one of the following: mfa_setup, mfa_verify.');
        $service->getCallbackRedirectUrl('invalid_token_type');
    }
}
