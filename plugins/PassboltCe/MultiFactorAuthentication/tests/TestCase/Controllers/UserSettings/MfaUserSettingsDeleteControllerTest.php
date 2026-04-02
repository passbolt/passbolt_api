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

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaUserSettingsDeleteControllerTest extends MfaIntegrationTestCase
{
    public const TESTED_ROUTE = '/mfa/setup/%s.json?api-version=v2';

    public function testMfaUserSettingsDeleteNeedsAuthenticatedUser()
    {
        $this->deleteJson(sprintf(self::TESTED_ROUTE, UuidFactory::uuid()));
        $this->assertResponseCode(403);
        $this->assertResponseError('You need to login to access this location.');
    }

    public function testMfaUserSettingsDeleteIsAllowedIfUserIsAdmin()
    {
        RoleFactory::make()->guest()->persist();
        $this->logInAsAdmin();
        $this->deleteJson(sprintf(self::TESTED_ROUTE, UuidFactory::uuid()));
        $this->assertResponseCode(400);
    }

    public function testMfaUserSettingsDeleteIsAllowedIfUserIsAccountOwner()
    {
        RoleFactory::make()->guest()->persist();
        $data = [
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO],
            MfaSettings::PROVIDER_DUO => [],
        ];
        $user = UserFactory::make()->user()->persist();
        $this->mockMfaAccountSettings($this->makeUac($user), $data);

        $this->logInAs($user);
        $this->deleteJson(sprintf(self::TESTED_ROUTE, $user->get('id')));
        $this->assertResponseCode(200);
    }

    public function testMfaUserSettingsDeleteIsNotAllowedIfNotAccountOwnerOrAdmin()
    {
        $this->logInAsUser();
        $this->deleteJson(sprintf(self::TESTED_ROUTE, UuidFactory::uuid()));
        $this->assertResponseCode(403);
        $this->assertResponseError('You are not allowed to access this location.');
    }

    public function testMfaUserSettingsDeleteCheckIfGivenUserIdIsValid()
    {
        $this->logInAsAdmin();
        $this->deleteJson(sprintf(self::TESTED_ROUTE, 'fake-uuid'));
        $this->assertResponseCode(400);
        $this->assertResponseError('The user id is not valid.');
    }

    public function testMfaUserSettingsDeleteIsSuccessIfNoSettingsWerePresentForUser()
    {
        RoleFactory::make()->guest()->persist();
        $this->logInAsAdmin();
        $user = UserFactory::make()->user()->persist();
        $this->deleteJson(sprintf(self::TESTED_ROUTE, $user->get('id')));
        $this->assertResponseCode(200);
        $this->assertResponseContains('No multi-factor authentication settings defined for the user.');
    }

    public function testMfaUserSettingsDeleteIsSuccessWhenAllConditionsAreFullfiled()
    {
        RoleFactory::make()->guest()->persist();
        $data = [
            MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO],
            MfaSettings::PROVIDER_DUO => [MfaAccountSettings::VERIFIED => DateTime::now()],
        ];
        $user = UserFactory::make()->user()->persist();
        $this->mockMfaAccountSettings($this->makeUac($user), $data);

        $this->logInAsAdmin();
        $this->deleteJson(sprintf(self::TESTED_ROUTE, $user->get('id')));
        $this->assertResponseCode(200);
        $this->assertResponseContains('The multi-factor authentication settings for the user were deleted.');
    }
}
