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

use App\Model\Entity\OrganizationSetting;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionFormatException;
use Passbolt\Ee\Test\Lib\DummySubscriptionTrait;

/**
 * Class SubscriptionsTableTest
 *
 * @package Passbolt\Ee\Test\TestCase\Model\Table
 * @covers \Passbolt\Ee\Model\Table\SubscriptionsTable
 */
class SubscriptionsTableCreateTest extends TestCase
{
    use DummySubscriptionTrait;
    use TruncateDirtyTables;

    /**
     * @var \Passbolt\Ee\Model\Table\SubscriptionsTable
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

    public function testSubscriptionsTableCreateValidSubscriptionKey()
    {
        $uac = $this->getDummyAdminUACMock();

        $asciiKey = $this->getValidSubscriptionKey();
        $this->Subscriptions->create($asciiKey, $uac);

        $subscription = $this->Subscriptions->getOrFail();
        $this->assertInstanceOf(OrganizationSetting::class, $subscription);
    }

    public function testSubscriptionsTableCreateInvalidSubscriptionKey()
    {
        $this->expectException(SubscriptionFormatException::class);

        $this->Subscriptions->create('', $this->getDummyAdminUACMock());
    }
}
