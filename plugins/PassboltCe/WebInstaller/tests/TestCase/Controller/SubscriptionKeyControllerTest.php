<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * SubscriptionKeyd under GNU Affero General Public SubscriptionKey version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL SubscriptionKey
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Test\TestCase\Controller;

use Cake\Core\Configure;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class SubscriptionKeyControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    protected function mockSubscriptionKeyIssuerKey()
    {
        Configure::load('Passbolt/Ee.config', 'default', true);
        $licenseDevPublicKey = PLUGINS . DS . 'PassboltEe' . DS . 'Ee' . DS . 'tests' . DS . 'data' . DS . 'gpg' . DS . 'subscription_dev_public.key';
        Configure::write('passbolt.plugins.ee.subscriptionKey.public', $licenseDevPublicKey);
    }

    protected function checkPluginSubscriptionKeyExists()
    {
        return file_exists(PLUGINS . DS . 'PassboltEe' . DS . 'Ee');
    }

    public function testWebInstallerSubscriptionKeyViewSuccess()
    {
        $this->get('/install/subscription');
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('Passbolt Pro activation.', $data);
    }

    public function testWebInstallerSubscriptionKeyPostSuccess()
    {
        if ($this->checkPluginSubscriptionKeyExists()) {
            $this->mockSubscriptionKeyIssuerKey();
            $postData = [
                'subscription_key' => file_get_contents(PLUGINS . DS . 'PassboltEe' . DS . 'Ee' . DS . 'tests' . DS . 'data' . DS . 'subscription' . DS . 'subscription_dev'),
            ];
            $this->post('/install/subscription', $postData);
            $this->assertResponseCode(302);
            $this->assertRedirectContains('/install/database');
            $this->assertSession($postData, 'webinstaller.subscription');
        }
        $this->assertTrue(true);
    }

    public function testWebInstallerSubscriptionKeyPostError_InvalidData()
    {
        if ($this->checkPluginSubscriptionKeyExists()) {
            $this->mockSubscriptionKeyIssuerKey();
            $postData = [
                'subscription_key' => 'invalid-format',
            ];
            $this->post('/install/subscription', $postData);
            $data = $this->_getBodyAsString();
            $this->assertResponseOk();
            $this->assertStringContainsString('The subscription format is not valid', $data);
        }
        $this->assertTrue(true);
    }

    public function testWebInstallerSubscriptionKeyPostError_SubscriptionKeyExpired()
    {
        if ($this->checkPluginSubscriptionKeyExists()) {
            $this->mockSubscriptionKeyIssuerKey();
            $postData = [
                'subscription_key' => file_get_contents(PLUGINS . DS . 'PassboltEe' . DS . 'Ee' . DS . 'tests' . DS . 'data' . DS . 'subscription' . DS . 'subscription_expired'),
            ];
            $this->post('/install/subscription', $postData);
            $data = $this->_getBodyAsString();
            $this->assertResponseOk();
            $this->assertStringContainsString('The subscription format is not valid', $data);
            $this->assertStringContainsString('The subscription is expired', $data);
        }
        $this->assertTrue(true);
    }
}
