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
 * @since         4.9.0
 */

namespace App\Test\TestCase\Controller\Gpgkeys;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;

/**
 * @covers \App\Controller\Gpgkeys\GpgkeysIndexController
 */
class GpgkeysIndexControllerHasUsersFilterTest extends AppIntegrationTestCase
{
    public function testGpgkeysIndexControllerHasUsersFilter_Success(): void
    {
        UserFactory::make(10)->user()->with('Gpgkeys')->persist();
        // Find two gpgkeys to filter users with
        $gpgkeys = GpgkeyFactory::find()->limit(2)->toArray();
        [$userId1, $userId2] = [$gpgkeys[0]['user_id'], $gpgkeys[1]['user_id']];

        $this->logInAsUser();
        $this->getJson("/gpgkeys.json?filter[has-users][]={$userId1}&filter[has-users][]={$userId2}");

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        $this->assertCount(2, $responseArray);
        foreach ($responseArray as $response) {
            $this->assertTrue(in_array($response['user_id'], [$userId1, $userId2]));
            $this->assertArrayHasAttributes(['id', 'armored_key', 'bits', 'uid', 'key_id', 'fingerprint'], $response);
        }
    }

    public function testGpgkeysIndexControllerHasUsersFilter_Success_UserDoesNotExist(): void
    {
        $users = UserFactory::make(2)->user()->with('Gpgkeys')->persist();
        $this->logInAs($users[0]);
        $userId = $users[0]['id'];
        $userIdNotFound = UuidFactory::uuid();

        $this->getJson("/gpgkeys.json?filter[has-users][]={$userId}&filter[has-users][]={$userIdNotFound}");

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        $this->assertCount(1, $responseArray);
        $this->assertEquals($userId, $responseArray[0]['user_id']);
        $this->assertArrayHasAttributes(['id', 'armored_key', 'bits', 'uid', 'key_id', 'fingerprint'], $responseArray[0]);
    }

    public function testGpgkeysIndexControllerHasUsersFilter_Error_NotLoggedIn(): void
    {
        $users = UserFactory::make(2)->user()->with('Gpgkeys')->persist();
        $userId = $users[0]['gpgkey']['user_id'];

        $this->getJson("/gpgkeys.json?filter[has-users][]={$userId}");

        $this->assertAuthenticationError();
    }

    public function testGpgkeysIndexControllerHasUsersFilter_Error_NotValidUuid(): void
    {
        $users = UserFactory::make(2)->user()->with('Gpgkeys')->persist();
        $this->logInAs($users[0]);

        $this->getJson('/gpgkeys.json?filter[has-users][]=foo');

        $this->assertBadRequestError('Invalid filter. "foo" is not a valid user id for filter has-users.');
    }
}
