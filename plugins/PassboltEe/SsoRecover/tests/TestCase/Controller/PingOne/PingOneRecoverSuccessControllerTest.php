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
 * @since         5.11.0
 */

namespace Passbolt\SsoRecover\Test\TestCase\Controller\PingOne;

use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\SsoRecover\Test\Lib\SsoRecoverIntegrationTestCase;

/**
 * @covers \Passbolt\SsoRecover\Controller\PingOne\PingOneRecoverSuccessController
 */
class PingOneRecoverSuccessControllerTest extends SsoRecoverIntegrationTestCase
{
    public function testPingOneRecoverSuccessController_ErrorJson(): void
    {
        $this->getJson('/sso/recover/pingone/success.json');
        $this->assertError(400, 'not supported');
    }

    public function testPingOneRecoverSuccessController_ErrorLoggedIn(): void
    {
        $this->logInAsUser();
        $this->get('/sso/recover/pingone/success');

        $this->assertResponseCode(403);
        $this->assertResponseContains('The user should not be logged in.');
    }

    public function testPingOneRecoverSuccessController_ErrorNoToken(): void
    {
        $this->get('/sso/recover/pingone/success');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The token is required in URL parameters.');
    }

    public function testPingOneRecoverSuccessController_ErrorInvalidToken(): void
    {
        $this->get('/sso/recover/pingone/success?token=nope');

        $this->assertResponseCode(400);
        $this->assertResponseContains('The token is required in URL parameters.');
    }

    public function testPingOneRecoverSuccessController_ErrorTokenDeleted(): void
    {
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $authToken */
        $authToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_RECOVER)
            ->inactive()
            ->persist();

        $this->get('/sso/recover/pingone/success?token=' . $authToken->token);

        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token does not exist or has been deleted.');
    }

    public function testPingOneRecoverSuccessController_ErrorTokenExpired(): void
    {
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $authToken */
        $authToken = SsoAuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_RECOVER)->expired()->persist();

        $this->get('/sso/recover/pingone/success?token=' . $authToken->token);

        $this->assertResponseCode(400);
        $this->assertResponseContains('The authentication token has been expired.');
    }

    public function testPingOneRecoverSuccessController_Success(): void
    {
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $authToken */
        $authToken = SsoAuthenticationTokenFactory::make()->type(SsoState::TYPE_SSO_RECOVER)->active()->persist();

        $this->get('/sso/recover/pingone/success?token=' . $authToken->token);

        $this->assertResponseCode(200);
        $this->assertResponseContains('success');
        $this->assertResponseContains('/js/app/api-feedback.js');
        $this->assertResponseContains('id="api-success"');
    }
}
