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

use Cake\Core\Configure;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionSignatureException;
use Passbolt\Ee\Test\Lib\SubscriptionControllerTestCase;

/**
 * Class SubscriptionsViewControllerTest
 *
 * @package Passbolt\Ee\Test\TestCase\Controller\Subscriptions
 * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController::view
 * @uses \Passbolt\Ee\Controller\Subscriptions\SubscriptionsCreateController
 */
class SubscriptionsCreateControllerTest extends SubscriptionControllerTestCase
{
    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsViewController::view
     * @Given No user is logged in
     * @When accessing the endpoint
     * @Then an authentication error is returned
     */
    public function testSubscriptionsCreateControllerError_NotLoggedIn()
    {
        $this->postJson('/ee/subscription/key.json');
        $this->assertAuthenticationError();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsCreateController::create
     * @Given a non admin user is logged in
     * @When accessing the endpoint
     * @Then an authentication error is returned
     */
    public function testSubscriptionsCreateControllerError_NotAdmin()
    {
        $this->logInAsUser();
        $this->postJson('/ee/subscription/key.json');
        $this->assertForbiddenError('You are not allowed to access this location.');
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsCreateController::create
     * @Given An admin user is logged in
     * @When A valid subscription is created
     * @Then the response is successful and contains the required fields
     */
    public function testSubscriptionsCreateControllerSuccess()
    {
        $this->logInAsAdmin();
        $data = $this->getValidSubscriptionKey();
        $this->postJson('/ee/subscription/key.json', compact('data'));
        $this->assertResponseSuccess();
        $this->assertResponseContains('The subscription was created.');
        $this->assertSubscriptionExists();
        $this->assessSubscriptionResponseContent();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsCreateController::create
     * @Given An admin user is logged in
     * @When An expired subscription is created
     * @Then a payment required error is returned
     */
    public function testSubscriptionsCreateControllerError_ExpiredSubscription()
    {
        $this->authenticateAs('admin');
        $data = $this->getExpiredSubscriptionKey();
        $this->postJson('/ee/subscription/key.json', compact('data'));
        $this->assertPaymentRequiredError('The subscription is expired.');
        $this->assertSubscriptionDoesNotExist();
    }

    /**
     * @covers \Passbolt\Ee\Controller\Subscriptions\SubscriptionsCreateController::create
     * @Given An admin user is logged in
     * @When A non verified subscription is created
     * @Then An internal error is returned
     */
    public function testSubscriptionsCreateControllerError_GpgError()
    {
        Configure::delete('passbolt.plugins.ee.subscriptionKey.public');
        $this->authenticateAs('admin');
        $data = $this->getValidSubscriptionKey();
        $this->postJson('/ee/subscription/key.json', compact('data'));
        $this->assertBadRequestError(SubscriptionSignatureException::MESSAGE);
        $this->assertSubscriptionDoesNotExist();
    }
}
