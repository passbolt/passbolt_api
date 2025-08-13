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
 * @since         5.4.0
 */

namespace App\Test\TestCase\Service\Users;

use App\Service\Users\PopulateLastLoggedInDateService;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\I18n\DateTime;
use Cake\Utility\Hash;
use Passbolt\Log\Test\Factory\ActionLogFactory;

/**
 * @covers \App\Service\Users\PopulateLastLoggedInDateService
 */
class PopulateLastLoggedInDateServiceTest extends AppTestCase
{
    private $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new PopulateLastLoggedInDateService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testPopulateLastLoggedInDateService(): void
    {
        $admins = UserFactory::make(2)->admin()->lastLoggedIn()->withLogIn()->persist();
        $users = UserFactory::make(2)->user()->lastLoggedIn()->withLogIn()->persist();
        $guests = UserFactory::make(2)->guest()->lastLoggedIn()->withLogIn()->persist();
        $disabled = UserFactory::make()->user()->disabled()->lastLoggedIn()->withLogIn()->persist();
        $deleted = UserFactory::make()->user()->deleted()->lastLoggedIn()->withLogIn()->persist();
        $inactive = UserFactory::make()->user()->inactive()->lastLoggedIn()->withLogIn()->persist();
        $yesterday = DateTime::yesterday();
        $withLastLoggedInDate = DateTime::now()->subDays(10);
        $withLastLoggedIn = UserFactory::make()->user()->active()->lastLoggedIn($yesterday)
            ->with('ActionLogs', ActionLogFactory::make()->created($withLastLoggedInDate)->loginAction())
            ->persist();
        $withoutRelatedActionLogs = UserFactory::make()->user()->active()->lastLoggedIn()->persist();

        $this->sut->populate();

        // Make sure all active, disabled users and admins values are populated with the latest action log date
        $userIds = [$admins[0]->id, $admins[1]->id, $users[0]->id, $users[1]->id, $disabled->id, $deleted->id];
        /** @var \App\Model\Entity\User[] $usersWithDateFilled */
        $usersWithDateFilled = UserFactory::find()->where(['id IN' => $userIds])->all();
        foreach ($usersWithDateFilled as $userWithDateFilled) {
            $this->assertNotNull($userWithDateFilled->last_logged_in);

            // sort action logs
            $actionLogs = ActionLogFactory::find()->where(['user_id' => $userWithDateFilled->id])->disableHydration()->toArray();
            /** @var \Passbolt\Log\Model\Entity\ActionLog $actionLog */
            $actionLog = Hash::sort($actionLogs, '{n}.created', 'desc')[0];
            $this->assertSame($actionLog['created']->toIso8601String(), $userWithDateFilled->last_logged_in->toIso8601String());
        }
        // Make sure guests, inactive users' values are not populated
        $userIds = [$guests[0]->id, $guests[1]->id, $inactive->id, $withoutRelatedActionLogs->id];
        $usersWithEmptyDate = UserFactory::find()->where(['id IN' => $userIds])->all();
        foreach ($usersWithEmptyDate as $userWithEmptyDate) {
            $this->assertNull($userWithEmptyDate->last_logged_in);
        }
        // Make sure if last logged in already present it does get overwritten it
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::find()->where(['id' => $withLastLoggedIn->id])->firstOrFail();
        $this->assertSame($withLastLoggedInDate->toIso8601String(), $user->last_logged_in->toIso8601String());
    }
}
