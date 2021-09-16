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

namespace Passbolt\PasswordGenerator\Test\TestCase\Service;

use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;
use Passbolt\PasswordGenerator\Plugin;
use Passbolt\PasswordGenerator\Service\GetPasswordGeneratorService;

class GetPasswordGeneratorServiceTest extends TestCase
{
    /**
     * @dataProvider dataForTestGetPasswordGeneratorHappyPath
     */
    public function testGetPasswordGeneratorHappyPath($env, string $generator)
    {
        $service = new GetPasswordGeneratorService();
        Configure::write(Plugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY, $env);
        $this->assertSame($generator, $service->getPasswordGenerator());
    }

    /**
     * @dataProvider dataForTestGetPasswordGeneratorError
     */
    public function testGetPasswordGeneratorError($env)
    {
        Configure::write(Plugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY, $env);
        $service = new GetPasswordGeneratorService();

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('The password generator value "' . $env . '" is not valid.');
        $service->getPasswordGenerator();
    }

    public function dataForTestGetPasswordGeneratorHappyPath(): array
    {
        return [
            [null, GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSWORD],
            ['', GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSWORD],
            [ucfirst(GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSPHRASE), GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSPHRASE],
            [GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSWORD, GetPasswordGeneratorService::PASSWORD_GENERATOR_SETTING_PASSWORD],
        ];
    }

    public function dataForTestGetPasswordGeneratorError(): array
    {
        return [
            ['foo'],
        ];
    }
}
