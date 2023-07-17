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

namespace Passbolt\Sso\Test\TestCase\Service\SsoKeys;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\ExtendedUserAccessControl;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\SsoKeys\SsoKeysGetService;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\Sso\Test\Factory\SsoKeysFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;

class SsoKeysGetServiceTest extends SsoTestCase
{
    public function testSsoKeysGetServiceTest_Success(): void
    {
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        $user = UserFactory::make()->active()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();
        $ssoAuthToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_GET_KEY)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => $ip,
                'user_agent' => $ua,
            ])
            ->persist();

        $uac = new ExtendedUserAccessControl(Role::GUEST, $user->id, $user->username, $ip, $ua);
        $result = (new SsoKeysGetService())->get($uac, $ssoAuthToken->token, $key->id);

        $this->assertEquals($key->id, $result->id);
        $this->assertEquals($key->user_id, $result->user_id);
        $this->assertEquals($key->data, $result->data);
        $this->assertEquals($key->created_by, $result->created_by);
        $this->assertEquals($key->modified_by, $result->modified_by);
        $this->assertTrue(Validation::datetime($result->created));
        $this->assertTrue(Validation::datetime($result->modified));
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $updateSsoAuthToken */
        $updateSsoAuthToken = SsoAuthenticationTokenFactory::find()->firstOrFail();
        $this->assertFalse($updateSsoAuthToken->active);
    }

    public function testSsoKeysGetServiceTest_Error_NotActiveSettings(): void
    {
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        SsoSettingsFactory::make()->azure()->draft()->persist();
        $user = UserFactory::make()->active()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();
        $token = UuidFactory::uuid();
        $uac = new ExtendedUserAccessControl(Role::GUEST, $user->id, $user->username, $ip, $ua);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The SSO settings do not exist.');
        (new SsoKeysGetService())->get($uac, $token, $key->id);
    }

    public function testSsoKeysGetServiceTest_Error(): void
    {
        $this->markTestIncomplete();
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        SsoSettingsFactory::make()->azure()->active()->persist();
        $user = UserFactory::make()->active()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();
        $token = UuidFactory::uuid();
        $uac = new ExtendedUserAccessControl(Role::GUEST, $user->id, $user->username, $ip, $ua);

        $this->expectException(RecordNotFoundException::class);
        $this->expectExceptionMessage('The SSO settings do not exist.');
        (new SsoKeysGetService())->get($uac, $token, $key->id);
    }
}
