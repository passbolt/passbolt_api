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
 * @since         3.3.0
 */

namespace App\Test\TestCase\Model\Table\AuthenticationTokens;

use App\Model\Entity\AuthenticationToken;
use App\Model\Table\AuthenticationTokensTable;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class SetExpiredActiveTokenToInactiveTest extends AppTestCase
{
    /**
     * @var AuthenticationTokensTable $AuthenticationTokens
     */
    public $AuthenticationTokens;
    public $autoFixtures = false;
    public $fixtures = [];

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AuthenticationTokens') ?
            [] : ['className' => AuthenticationTokensTable::class];
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens', $config);
    }

    public function testAuthenticationTokensTableSetActiveExpiredTokenToInactive(): void
    {
        $type = AuthenticationToken::TYPE_RECOVER;
        AuthenticationTokenFactory::make()->type($type)->active()->expired()->persist();
        AuthenticationTokenFactory::make()->type($type)->inactive()->expired()->persist();
        AuthenticationTokenFactory::make()->type($type)->inactive()->persist();
        AuthenticationTokenFactory::make()->type($type)->active()->persist();

        $this->AuthenticationTokens->setActiveExpiredTokenToInactive($type);

        $found = $this->AuthenticationTokens->find()->where(['type' => $type])->toArray();
        $this->assertTrue(count($found) === 4);

        $found = Hash::extract($found, '{n}[active=false]');
        $this->assertTrue(count($found) === 3);
    }

    public function testAuthenticationTokensTableSetAllActiveExpiredTokenToInactive(): void
    {
        foreach (AuthenticationTokensTable::ALLOWED_TYPES as $type) {
            AuthenticationTokenFactory::make()->type($type)->active()->expired()->persist();
            AuthenticationTokenFactory::make()->type($type)->inactive()->expired()->persist();
            AuthenticationTokenFactory::make()->type($type)->inactive()->persist();
            AuthenticationTokenFactory::make()->type($type)->active()->persist();
        }

        $this->AuthenticationTokens->setAllActiveExpiredTokenToInactive();

        $found = $this->AuthenticationTokens->find()->all()->toArray();
        $this->assertTrue(count($found) === count(AuthenticationTokensTable::ALLOWED_TYPES) * 4);

        $found = Hash::extract($found, '{n}[active=false]');
        $this->assertTrue(count($found) === count(AuthenticationTokensTable::ALLOWED_TYPES) * 3);
    }

    public function testAuthenticationTokensTableSetAllActiveExpiredTokenToInactiveEmpty(): void
    {
        $this->AuthenticationTokens->setAllActiveExpiredTokenToInactive();
        $found = $this->AuthenticationTokens->find()->all()->toArray();
        $this->assertTrue(count($found) === 0);
    }
}
