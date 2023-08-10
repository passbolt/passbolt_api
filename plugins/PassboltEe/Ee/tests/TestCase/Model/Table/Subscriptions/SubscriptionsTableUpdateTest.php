<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */

namespace Passbolt\Ee\Test\TestCase\Model\Table\Subscriptions;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionFormatException;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionRecordNotFoundException;
use Passbolt\Ee\Test\Lib\DummySubscriptionTrait;

/**
 * Class SubscriptionsTableTest
 *
 * @package Passbolt\Ee\Test\TestCase\Model\Table
 * @covers \Passbolt\Ee\Model\Table\SubscriptionsTable
 */
class SubscriptionsTableUpdateTest extends TestCase
{
    use DummySubscriptionTrait;
    use TruncateDirtyTables;

    /**
     * @var \Passbolt\Ee\Model\Table\SubscriptionsTable $Subscriptions
     */
    public $Subscriptions;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpPathAndPublicSubscriptionKey();

        $this->Subscriptions = TableRegistry::getTableLocator()->get('Passbolt/Ee.Subscriptions');
    }

    public function tearDown(): void
    {
        unset($this->Subscriptions);
        parent::tearDown();
    }

    public function testSubscriptionsTableUpdateNonExistent()
    {
        $asciiKey = $this->getValidSubscriptionKey();
        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->update($asciiKey, $this->getDummyAdminUACMock());
    }

    public function testSubscriptionsTableUpdateValidWithInvalidSubscriptionKey()
    {
        $this->expectException(SubscriptionFormatException::class);
        $this->persistValidSubscription();
        $this->Subscriptions->update('', $this->getDummyAdminUACMock());
    }
}
