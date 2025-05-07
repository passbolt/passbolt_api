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
 * @since         5.1.0
 */
namespace Passbolt\PasswordExpiry\Test\TestCase\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;

class PasswordExpirySetupCompleteControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(PasswordExpiryPlugin::class);
    }

    public function testPasswordExpirySetupCompleteController_Success(): void
    {
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', UserFactory::make()->admin()->inactive())
            ->persist();
        $user = $token->user;
        $url = '/setup/complete/' . $user->id . '.json';
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
        $data = [
            'authentication_token' => [
                'token' => $token->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
        ];
        $this->postJson($url, $data);
        $this->assertSuccess();

        $this->assertSame(1, OrganizationSettingFactory::count());
        $organizationSetting = OrganizationSettingFactory::firstOrFail();
        $setting = json_decode($organizationSetting->value, true);
        $expectedSettings = [
            PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
            PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
            PasswordExpirySettingsDto::POLICY_OVERRIDE => false,
            PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => null,
        ];
        $this->assertSame($expectedSettings, $setting);
        $this->assertSame($user->id, $organizationSetting->created_by);
        $this->assertSame($user->id, $organizationSetting->modified_by);
    }
}
