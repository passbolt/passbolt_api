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
 * @since         3.0.0
 */
namespace Passbolt\Ee\Test\Lib;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

/**
 * Class SubscriptionsViewControllerTest
 *
 * @package Passbolt\Ee\Test\TestCase\Controller\Subscriptions
 * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController::view
 */
class SubscriptionControllerTestCase extends AppIntegrationTestCase
{
    use DummySubscriptionTrait;

    /**
     * @var \Passbolt\Ee\Model\Table\SubscriptionsTable
     */
    public $Subscriptions;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpPathAndPublicSubscriptionKey();
        $this->Subscriptions = TableRegistry::getTableLocator()->get('Passbolt/Ee.Subscriptions');
        MfaSettings::clear();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Subscriptions);
    }

    protected function assessSubscriptionResponseContent()
    {
        $subscriptionKey = trim($this->getValidSubscriptionKey());
        $this->assertResponseContains($subscriptionKey);
        $this->assertResponseContains('customer_id');
        $this->assertResponseContains('subscription_id');
        $this->assertResponseContains('users');
        $this->assertResponseContains('email');
        $this->assertResponseContains('expiry');
        $this->assertResponseContains('created');
    }
}
