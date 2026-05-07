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
 * @since         3.11.0
 */

namespace Passbolt\SsoRecover\Test\TestCase\Controller\Azure;

use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\SsoRecover\Test\Lib\SsoRecoverIntegrationTestCase;

/**
 * @covers \Passbolt\SsoRecover\Controller\Azure\AzureRecoverSuccessController
 */
class AzureRecoverSuccessControllerTest extends SsoRecoverIntegrationTestCase
{
    public function testAzureRecoverSuccessController_ErrorJson(): void
    {
        $this->getJson('/sso/recover/azure/success.json');
        $this->assertError(400, 'not supported');
    }

    public function testAzureRecoverSuccessController_ErrorLoggedIn(): void
    {
        $this->logInAsUser();
        $this->get('/sso/recover/azure/success');

        $this->assertResponseCode(403);

        $this->assertResponseContains('The user should not be logged in.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testAzureRecoverSuccessController_ErrorNoToken(): void
    {
        $this->get('/sso/recover/azure/success');

        $this->assertResponseCode(400);

        $this->assertResponseContains('The token is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testAzureRecoverSuccessController_ErrorInvalidToken(): void
    {
        $this->get('/sso/recover/azure/success?token=nope');

        $this->assertResponseCode(400);

        $this->assertResponseContains('The token is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testAzureRecoverSuccessController_ErrorTokenDeleted(): void
    {
        $authToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_RECOVER)
            ->inactive()
            ->persist();

        $this->get('/sso/recover/azure/success?token=' . $authToken->token);

        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token does not exist or has been deleted.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testAzureRecoverSuccessController_ErrorTokenExpired(): void
    {
        $authToken = SsoAuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_RECOVER)->expired()->persist();

        $this->get('/sso/recover/azure/success?token=' . $authToken->token);

        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token has been expired.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testAzureRecoverSuccessController_Success(): void
    {
        $authToken = SsoAuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_RECOVER)->active()->persist();

        $this->get('/sso/recover/azure/success?token=' . $authToken->token);

        $this->assertResponseCode(200);
        $this->assertResponseContains('success');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-success"');
    }
}
