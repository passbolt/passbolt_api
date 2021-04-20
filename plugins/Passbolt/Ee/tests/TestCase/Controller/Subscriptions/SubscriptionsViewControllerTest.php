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
 * @since         3.1.0
 */
namespace Passbolt\Ee\Test\TestCase\Controller\Subscriptions;

use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionSignatureException;
use Passbolt\Ee\Test\Lib\SubscriptionControllerTestCase;

/**
 * Class SubscriptionsViewControllerTest
 *
 * @package Passbolt\Ee\Test\TestCase\Controller\Subscriptions
 * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController::view
 * @uses \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController
 */
class SubscriptionsViewControllerTest extends SubscriptionControllerTestCase
{
    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController::view
     * @Given No user is logged in
     * @When accessing the endpoint
     * @Then an authentication error is returned
     */
    public function testSubscriptionsViewControllerError_NotAuthenticated()
    {
        $this->getJson('/ee/subscription/key.json');
        $this->assertAuthenticationError();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController::view
     * @Given a non admin user is logged in
     * @When accessing the endpoint
     * @Then an authentication error is returned
     */
    public function testSubscriptionsViewControllerError_NotAdmin()
    {
        $this->authenticateAs('ada');
        $this->getJson('/ee/subscription/key.json');
        $this->assertForbiddenError('You are not allowed to access this location.');
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController::view
     * @Given an admin is logged in and a valid subscription exists
     * @When viewing the subscription
     * @Then the response is successful and contains the required fields
     */
    public function testSubscriptionsViewControllerSuccess()
    {
        $this->persistValidSubscription();
        $this->authenticateAs('admin');
        $this->getJson('/ee/subscription/key.json');
        $this->assertResponseSuccess('The subscription is valid.');
        $this->assertSubscriptionExists();
        $this->assessSubscriptionResponseContent();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController::view
     * @Given an admin is logged in and an expired subscription exists
     * @When viewing the subscription
     * @Then a payment required error is returned
     */
    public function testSubscriptionsViewControllerError_Expired()
    {
        $this->persistExpiredSubscription();
        $this->authenticateAs('admin');
        $this->getJson('/ee/subscription/key.json');
        $this->assertPaymentRequiredError('The subscription is expired.');
        $this->assertResponseContains('customer_id');
        $this->assertResponseContains('subscription_id');
        $this->assertSubscriptionExists();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController::view
     * @Given an admin is logged in and a valid subscription exists for too few active users
     * @When viewing the subscription
     * @Then a payment required error is returned
     */
    public function testSubscriptionsViewControllerError_TooFewUsers()
    {
        $this->persistExpiredSubscription();
        UserFactory::make(50)->user()->active()->persist();
        $this->authenticateAs('admin');
        $this->getJson('/ee/subscription/key.json');
        $this->assertPaymentRequiredError('The users limit is exceeded.');
        $this->assertResponseContains('customer_id');
        $this->assertResponseContains('subscription_id');
        $this->assertSubscriptionExists();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController::view
     * @Given an admin is logged in and an invalid subscription exists
     * @When viewing the subscription
     * @Then an internal error is returned
     */
    public function testSubscriptionsViewControllerError_Invalid()
    {
        $this->persistInvalidSubscription();
        $this->authenticateAs('admin');
        $this->getJson('/ee/subscription/key.json');
        $this->assertBadRequestError(SubscriptionSignatureException::MESSAGE);
        $this->assertSubscriptionExists();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController::view
     * @Given an non verified is logged in and a valid subscription exists
     * @When viewing the subscription
     * @Then an internal error is returned
     */
    public function testSubscriptionsViewControllerError_GpgError()
    {
        Configure::delete('passbolt.plugins.ee.subscriptionKey.public');
        $this->persistValidSubscription();
        $this->authenticateAs('admin');
        $this->getJson('/ee/subscription/key.json');
        $this->assertBadRequestError(SubscriptionSignatureException::MESSAGE);
        $this->assertSubscriptionExists();
    }
}
