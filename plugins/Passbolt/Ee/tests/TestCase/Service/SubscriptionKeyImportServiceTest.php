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

namespace Passbolt\Ee\Test\TestCase\Service;

use App\Test\Factory\UserFactory;
use Cake\Datasource\ModelAwareTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionException;
use Passbolt\Ee\Model\Entity\Subscription;
use Passbolt\Ee\Service\SubscriptionKeyImportService;
use Passbolt\Ee\Test\Lib\DummySubscriptionTrait;

/**
 * Class SubscriptionsTableTest
 *
 * @package Passbolt\Ee\Test\TestCase\Model\Table
 * @property \Passbolt\Ee\Model\Table\SubscriptionsTable $Subscriptions
 */
class SubscriptionKeyImportServiceTest extends TestCase
{
    use DummySubscriptionTrait;
    use ModelAwareTrait;
    use TruncateDirtyTables;

    /**
     * @var SubscriptionKeyImportService
     */
    public $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SubscriptionKeyImportService();
        $this->setUpPathAndPublicSubscriptionKey();
        $this->loadModel('Passbolt/Ee.Subscriptions');
    }

    /**
     * Import a valid subscription file
     */
    public function testSubscriptionKeyImportServiceImportValidFilename(): void
    {
        UserFactory::make()->admin()->persist();
        $filename = $this->getValidSubscriptionFileName();

        $this->service->import($filename);

        $this->assertInstanceOf(
            Subscription::class,
            $this->Subscriptions->getOrFail()
        );
    }

    /**
     * Import an invalid subscription file
     */
    public function testSubscriptionKeyImportServiceImportInvalidFilename(): void
    {
        UserFactory::make()->admin()->persist();
        $filename = $this->getExpiredSubscriptionKey();

        $this->expectException(SubscriptionException::class);

        $this->service->import($filename);

        $this->assertSame(
            0,
            $this->Subscriptions->find()->count()
        );
    }
}
