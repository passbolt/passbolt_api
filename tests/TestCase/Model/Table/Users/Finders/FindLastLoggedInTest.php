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
 * @since         2.14.0
 */

namespace App\Test\TestCase\Model\Table\Users\Finders;

use App\Model\Table\UsersTable;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Test\Lib\Traits\ActionLogsTestTrait;

class FindLastLoggedInTest extends AppTestCase
{
    use ActionLogsTestTrait;

    /**
     * @var UsersTable
     */
    private $usersTable;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->usersTable = TableRegistry::getTableLocator()->get('Users', $config);
    }

    /**
     * Tear down
     */
    public function tearDown(): void
    {
        unset($this->usersTable);

        parent::tearDown();
    }

    /**
     * @dataProvider findLastLoggedInValuesProvider
     * @param DateTime|null $value Last logged in value.
     * @return void
     */
    public function testFindLastLoggedIn(?DateTime $value): void
    {
        $user = UserFactory::make()->user()->active()->lastLoggedIn($value)->persist();
        $userId = $user->get('id');

        $result = $this->usersTable->findById($userId)->find('lastLoggedIn')->first();

        $this->assertObjectHasAttribute('last_logged_in', $result);
        if (is_null($value)) {
            $this->assertNull($result->get('last_logged_in'));
        } else {
            $this->assertSame($value->toIso8601String(), $result->get('last_logged_in')->toIso8601String());
        }
    }

    public static function findLastLoggedInValuesProvider(): array
    {
        return [
            [null],
            [DateTime::now()->subMonths(1)],
        ];
    }
}
