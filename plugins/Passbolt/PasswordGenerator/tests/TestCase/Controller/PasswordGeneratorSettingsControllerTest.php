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
 * @since         3.3.0
 */

namespace Passbolt\PasswordGenerator\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Passbolt\PasswordGenerator\Plugin;
use Passbolt\PasswordGenerator\Service\GetPasswordGeneratorService;

class PasswordGeneratorSettingsControllerTest extends AppIntegrationTestCase
{
    public function tearDown(): void
    {
        // Cleanup the env and config variable
        putenv('PASSBOLT_PLUGINS_PASSWORD_GENERATOR_DEFAULT_GENERATOR');
        Configure::delete(Plugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY);

        parent::tearDown();
    }

    /**
     * @dataProvider dataForTestPasswordDefaultGeneratorSettings
     */
    public function testPasswordDefaultGeneratorSettings($env, string $generator)
    {
        putenv("PASSBOLT_PLUGINS_PASSWORD_GENERATOR_DEFAULT_GENERATOR=$env");
        $this->logInAsUser();

        $this->getJson('/password-generator/settings.json');

        if ($generator === 'exception') {
            $this->assertResponseFailure('The password generator value "' . $env . '" is not valid.');
        } else {
            $this->assertResponseSuccess();
            $this->assertSame($generator, $this->_responseJsonBody->default_generator);
        }
    }

    public function dataForTestPasswordDefaultGeneratorSettings(): array
    {
        return [
            [null, GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSWORD],
            ['', GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSWORD],
            ['foo', 'exception'],
            [ucfirst(GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSPHRASE), GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSPHRASE],
            [GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSWORD, GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSWORD],
        ];
    }
}
