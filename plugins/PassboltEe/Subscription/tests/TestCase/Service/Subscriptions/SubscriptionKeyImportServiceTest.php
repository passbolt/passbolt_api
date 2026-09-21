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

namespace Passbolt\Subscription\Test\TestCase\Service\Subscriptions;

use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionException;
use Passbolt\Subscription\Model\Entity\Subscription;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyImportService;
use Passbolt\Subscription\Test\DummySubscriptionTrait;

/**
 * @uses \Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyImportService
 */
class SubscriptionKeyImportServiceTest extends TestCase
{
    use DummySubscriptionTrait;
    use LocatorAwareTrait;
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    public SubscriptionKeyImportService $service;

    /**
     * @var \Passbolt\Subscription\Model\Table\SubscriptionsTable
     */
    protected $Subscriptions;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SubscriptionKeyImportService();
        $this->setUpPathAndPublicSubscriptionKey();
        $this->Subscriptions = $this->fetchTable('Passbolt/Subscription.Subscriptions');
    }

    public function testSubscriptionKeyImportServiceImportValidKey(): void
    {
        $subscriptionKey = $this->getValidSubscriptionKey();

        $this->service->import($subscriptionKey, $this->mockAdminAccessControl());

        $this->assertInstanceOf(
            Subscription::class,
            $this->Subscriptions->getOrFail(),
        );
    }

    public function testSubscriptionKeyImportServiceImportInvalidKey(): void
    {
        $subscriptionKey = $this->getExpiredSubscriptionKey();

        $this->expectException(SubscriptionException::class);
        $this->service->import($subscriptionKey, $this->mockAdminAccessControl());
    }

    public function testSubscriptionKeyImportServiceImportValidFilename(): void
    {
        $filename = $this->getValidSubscriptionFileName();

        $this->service->importFromFile($filename, $this->mockAdminAccessControl());

        $this->assertInstanceOf(
            Subscription::class,
            $this->Subscriptions->getOrFail(),
        );
    }

    public function testSubscriptionKeyImportServiceImportInvalidFilename(): void
    {
        $filename = $this->getExpiredSubscriptionKey();

        $this->expectException(SubscriptionException::class);

        $this->service->importFromFile($filename, $this->mockAdminAccessControl());

        $this->assertSame(
            0,
            $this->Subscriptions->find()->all()->count(),
        );
    }
}
