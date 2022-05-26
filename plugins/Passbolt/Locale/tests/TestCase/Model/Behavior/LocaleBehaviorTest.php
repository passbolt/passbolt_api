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
 * @since         3.2.0
 */

namespace Passbolt\Locale\Test\TestCase\Model\Behavior;

use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Locale\Service\GetOrgLocaleService;
use Passbolt\Locale\Test\Lib\DummySystemLocaleTestTrait;

class LocaleBehaviorTest extends TestCase
{
    use DummySystemLocaleTestTrait;
    use TruncateDirtyTables;

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * Test the LocaleBehavior on the UsersTable
     */
    public function testFindContainLocale(): void
    {
        GetOrgLocaleService::clearOrganisationLocale();

        UserFactory::make(['username' => 'ada@passbolt.com'])
            ->withLocale('fr-FR')
            ->persist();

        UserFactory::make(['username' => 'betty@passbolt.com'])
            ->withLocale('en-UK')
            ->persist();

        UserFactory::make(['username' => 'carol@passbolt.com'])
            ->persist();

        $user = $this->usersTable->find('locale')
            ->where(['username' => 'ada@passbolt.com'])
            ->contain('Locale')
            ->first();
        $this->assertEquals('fr-FR', $user->get('locale'));

        $user = $this->usersTable->find('locale')
            ->where(['username' => 'betty@passbolt.com'])
            ->contain('Locale')
            ->first();
        $this->assertEquals('en-UK', $user->get('locale'));

        $user = $this->usersTable->find('locale')
            ->where(['username' => 'carol@passbolt.com'])
            ->contain('Locale')
            ->first();
        $this->assertEquals('en-UK', $user->get('locale'));
    }
}
