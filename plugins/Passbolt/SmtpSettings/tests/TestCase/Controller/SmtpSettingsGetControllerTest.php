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
 * @since         3.8.0
 */

namespace Passbolt\SmtpSettings\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use Passbolt\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

class SmtpSettingsGetControllerTest extends AppIntegrationTestCase
{
    use GpgAdaSetupTrait;
    use SmtpSettingsTestTrait;

    public function testSmtpSettingsGetController_Success()
    {
        $this->gpgSetup();
        $this->logInAsAdmin();

        $data = $this->getSmtpSettingsData();
        $this->gpgSetup();
        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $encryptedSettings = $this->gpg->encrypt(json_encode($data));
        SmtpSettingFactory::make()->value($encryptedSettings)->persist();

        $this->getJson('/smtp/settings.json');
        $this->assertSuccess();
        $retrievedData = (array)$this->_responseJsonBody;
        $expectedData = $data + ['source' => 'db'];

        $this->assertSame($expectedData, $retrievedData);
    }

    public function testSmtpSettingsGetController_Not_Admin_Should_Have_No_Access()
    {
        $this->logInAsUser();
        $this->getJson('/smtp/settings.json');
        $this->assertForbiddenError('Access restricted to administrators.');
    }

    public function testSmtpSettingsGetController_Guest_Should_Have_No_Access()
    {
        $this->getJson('/smtp/settings.json');
        $this->assertAuthenticationError();
    }
}
