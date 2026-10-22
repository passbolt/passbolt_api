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
 * @since         5.17.0
 */
namespace Passbolt\Scim\Test\TestCase\Controller\Middleware;

use Cake\Core\Configure;
use Cake\I18n\Date;
use Passbolt\Scim\Service\ScimTokenExpiryChecker;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;
use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestCase;
use Passbolt\Scim\Utility\SchemaIdentifier;
use Passbolt\Scim\Utility\ScimConstants;

/**
 * @covers \Passbolt\Scim\Middleware\ScimUnauthenticatedResponseMiddleware
 */
class ScimUnauthenticatedResponseMiddlewareTest extends ScimApiIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->createSettingsEntry = false;
        parent::setUp();
        $this->settingId = ScimSettingFactory::SCIM_TEST_SETTING_ID;
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        $this->createSettingsEntry = true;
        parent::tearDown();
    }

    public function testScimUnauthenticatedResponseMiddleware_Expired_Returns401ScimError(): void
    {
        $expiredDate = Date::now()->modify('-1 day')->format('Y-m-d');
        ScimSettingFactory::make()->default(['expired' => $expiredDate])->persist();
        Configure::write(ScimTokenExpiryChecker::CONFIG_ENFORCE, true);

        $this->configScimAuth();
        $this->get($this->getScimEndpoint('ServiceProviderConfig'));

        $this->assertResponseCode(401);
        $this->assertContentType(ScimConstants::CONTENT_TYPE);
        $this->assertResponseEquals(
            json_encode([
                'schemas' => [SchemaIdentifier::API_ERROR],
                'status' => 401,
                'detail' => 'The SCIM secret token is expired.',
            ], JSON_PRETTY_PRINT),
        );
    }

    public function testScimUnauthenticatedResponseMiddleware_WrongToken_Returns401GenericDetail(): void
    {
        ScimSettingFactory::make()->default()->persist();
        $this->configRequest(['headers' => ['Authorization' => 'Bearer wrong-token']]);

        $this->get($this->getScimEndpoint('ServiceProviderConfig'));

        $this->assertResponseCode(401);
        $this->assertContentType(ScimConstants::CONTENT_TYPE);
        $this->assertResponseEquals(
            json_encode([
                'schemas' => [SchemaIdentifier::API_ERROR],
                'status' => 401,
                'detail' => 'Authentication failed.',
            ], JSON_PRETTY_PRINT),
        );
    }

    public function testScimUnauthenticatedResponseMiddleware_ValidToken_Returns200(): void
    {
        ScimSettingFactory::make()->default()->persist();

        $this->configScimAuth();
        $this->get($this->getScimEndpoint('ServiceProviderConfig'));

        $this->assertResponseOk();
    }
}
