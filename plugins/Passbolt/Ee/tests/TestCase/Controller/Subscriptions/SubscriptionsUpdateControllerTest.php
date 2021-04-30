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
 * @uses \Passbolt\Ee\Controller\Subscriptions\SubscriptionsUpdateController
 */
class SubscriptionsUpdateControllerTest extends SubscriptionControllerTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->persistValidSubscription();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsUpdateController::update
     * @Given No user is logged in
     * @When accessing the endpoint
     * @Then an authentication error is returned
     */
    public function testSubscriptionsUpdateControllerError_NotLoggedIn()
    {
        $this->putJson('/ee/subscription/key.json');
        $this->assertAuthenticationError();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsUpdateController::update
     * @Given a non admin user is logged in
     * @When accessing the endpoint
     * @Then an authentication error is returned
     */
    public function testSubscriptionsUpdateControllerError_NotAdmin()
    {
        $this->authenticateAs('ada');
        $this->putJson('/ee/subscription/key.json');
        $this->assertForbiddenError('You are not allowed to access this location.');
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsUpdateController::update
     * @Given An admin is logged in and a subscription exists
     * @When Updating with a valid subscription
     * @Then the response is successful and contains the required fields
     */
    public function testSubscriptionsUpdateControllerSuccess()
    {
        $this->authenticateAs('admin');
        $data = $this->getValidSubscriptionKey();

        $this->putJson('/ee/subscription/key.json', compact('data'));
        $this->assertResponseSuccess();
        $this->assertSubscriptionExists();
        $this->assessSubscriptionResponseContent();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsUpdateController::update
     * @Given An admin is logged in and a subscription exists
     * @When Updating with an expired subscription
     * @Then a payment required error is returned
     */
    public function testSubscriptionsUpdateControllerError_ReplaceValidWithExpired()
    {
        $this->authenticateAs('admin');
        $data = $this->getExpiredSubscriptionKey();
        $this->putJson('/ee/subscription/key.json', compact('data'));
        $this->assertPaymentRequiredError('The subscription is expired.');
        $this->assertSubscriptionExists();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsUpdateController::update
     * @Given An admin is logged in and a subscription exists
     * @When Updating with a subscription with too few users
     * @Then a payment required error is returned
     */
    public function testSubscriptionsUpdateControllerError_ReplaceValidByTooManyActiveUsers()
    {
        $this->authenticateAs('admin');
        $data = $this->getValidSubscriptionKey();
        UserFactory::make(50)->user()->active()->persist();
        $this->putJson('/ee/subscription/key.json', compact('data'));
        $this->assertPaymentRequiredError('The users limit is exceeded.');
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsUpdateController::update
     * @Given An admin user is logged in
     * @When A non verified subscription is created
     * @Then An internal error is returned
     */
    public function testSubscriptionsUpdateControllerError_GpgError()
    {
        Configure::delete('passbolt.plugins.ee.subscriptionKey.public');
        $this->authenticateAs('admin');
        $data = $this->getValidSubscriptionKey();
        $this->postJson('/ee/subscription/key.json', compact('data'));
        $this->assertBadRequestError(SubscriptionSignatureException::MESSAGE);
        $this->assertSubscriptionExists();
    }
}
