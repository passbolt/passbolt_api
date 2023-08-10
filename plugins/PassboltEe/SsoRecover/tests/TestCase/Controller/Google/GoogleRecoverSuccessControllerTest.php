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
 * @since         4.0.0
 */

namespace Passbolt\SsoRecover\Test\TestCase\Controller\Google;

use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\SsoRecover\Test\Lib\SsoRecoverIntegrationTestCase;

/**
 * @covers \Passbolt\SsoRecover\Controller\Google\GoogleRecoverSuccessController
 */
class GoogleRecoverSuccessControllerTest extends SsoRecoverIntegrationTestCase
{
    public function testGoogleRecoverSuccessController_ErrorJson(): void
    {
        $this->getJson('/sso/recover/google/success.json');
        $this->assertError(400, 'not supported');
    }

    public function testGoogleRecoverSuccessController_ErrorLoggedIn(): void
    {
        $this->logInAsUser();
        $this->get('/sso/recover/google/success');

        $this->assertResponseCode(403);

        $this->assertResponseContains('The user should not be logged in.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testGoogleRecoverSuccessController_ErrorNoToken(): void
    {
        $this->get('/sso/recover/google/success');

        $this->assertResponseCode(400);

        $this->assertResponseContains('The token is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testGoogleRecoverSuccessController_ErrorInvalidToken(): void
    {
        $this->get('/sso/recover/google/success?token=nope');

        $this->assertResponseCode(400);

        $this->assertResponseContains('The token is required in URL parameters.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testGoogleRecoverSuccessController_ErrorTokenDeleted(): void
    {
        $authToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_RECOVER)
            ->inactive()
            ->persist();

        $this->get('/sso/recover/google/success?token=' . $authToken->token);

        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token does not exist or has been deleted.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testGoogleRecoverSuccessController_ErrorTokenExpired(): void
    {
        $authToken = SsoAuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_RECOVER)->expired()->persist();

        $this->get('/sso/recover/google/success?token=' . $authToken->token);

        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token has been expired.');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-error-details"');
    }

    public function testGoogleRecoverSuccessController_Success(): void
    {
        $authToken = SsoAuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_RECOVER)->active()->persist();

        $this->get('/sso/recover/google/success?token=' . $authToken->token);

        $this->assertResponseCode(200);
        $this->assertResponseContains('success');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-success"');
    }
}
