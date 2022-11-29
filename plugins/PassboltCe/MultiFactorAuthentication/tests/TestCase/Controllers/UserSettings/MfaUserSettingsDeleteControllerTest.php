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
 * @since         2.12.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\UserSettings;

use App\Test\Fixture\Alt0\GroupsUsersFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use Passbolt\AccountSettings\Test\Fixture\AccountSettingsFixture;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaUserSettingsDeleteControllerTest extends MfaIntegrationTestCase
{
    public $fixtures = [
        AccountSettingsFixture::class,
        UsersFixture::class,
        RolesFixture::class,
        ProfilesFixture::class,
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
    ];

    public const TESTED_ROUTE = '/mfa/setup/%s.json?api-version=v2';

    public function testMfaUserSettingsDeleteNeedsAuthenticatedUser()
    {
        $this->deleteJson(sprintf(self::TESTED_ROUTE, UuidFactory::uuid()));
        $this->assertResponseCode(403);
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testMfaUserSettingsDeleteIsAllowedIfUserIsAdmin()
    {
        $this->authenticateAs('admin');
        $this->deleteJson(sprintf(self::TESTED_ROUTE, UuidFactory::uuid()));
        $this->assertResponseCode(400);
    }

    public function testMfaUserSettingsDeleteIsAllowedIfUserIsAccountOwner()
    {
        $data = [
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO],
            MfaSettings::PROVIDER_DUO => [],
        ];
        $this->mockMfaAccountSettings('ada', $data);

        $this->authenticateAs('ada');
        $this->deleteJson(sprintf(self::TESTED_ROUTE, UuidFactory::uuid('user.id.ada')));
        $this->assertResponseCode(200);
    }

    public function testMfaUserSettingsDeleteIsNotAllowedIfNotAccountOwnerOrAdmin()
    {
        $this->authenticateAs('ada');
        $this->deleteJson(sprintf(self::TESTED_ROUTE, UuidFactory::uuid()));
        $this->assertResponseCode(403);
        $this->assertResponseError('You are not allowed to access this location.');
    }

    public function testMfaUserSettingsDeleteCheckIfGivenUserIdIsValid()
    {
        $this->authenticateAs('admin');
        $this->deleteJson(sprintf(self::TESTED_ROUTE, 'fake-uuid'));
        $this->assertResponseCode(400);
        $this->assertResponseError('The user id is not valid.');
    }

    public function testMfaUserSettingsDeleteIsSuccessIfNoSettingsWerePresentForUser()
    {
        $this->authenticateAs('admin');
        $this->deleteJson(sprintf(self::TESTED_ROUTE, UuidFactory::uuid('user.id.ada')));
        $this->assertResponseCode(200);
        $this->assertResponseContains('No multi-factor authentication settings defined for the user.');
    }

    public function testMfaUserSettingsDeleteIsSuccessWhenAllConditionsAreFullfiled()
    {
        $data = [
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO],
            MfaSettings::PROVIDER_DUO => [MfaAccountSettings::VERIFIED => FrozenTime::now()],
        ];
        $this->mockMfaAccountSettings('ada', $data);

        $this->authenticateAs('admin');
        $this->deleteJson(sprintf(self::TESTED_ROUTE, UuidFactory::uuid('user.id.ada')));
        $this->assertResponseCode(200);
        $this->assertResponseContains('The multi-factor authentication settings for the user were deleted.');
    }
}
