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

namespace Passbolt\PasswordGenerator\Service;

use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\PasswordGenerator\Plugin;

class GetPasswordGeneratorService
{
    public const PASSWORD_GENERATOR_SETTING_PASSPHRASE = 'passphrase';

    public const PASSWORD_GENERATOR_SETTING_PASSWORD = 'password';

    /**
     * Read the password generator value in
     * 1. passbolt.php
     * 2. env
     * 3. take default value
     *
     * @return string
     * @throws \Cake\Http\Exception\BadRequestException if the password generator provided is not valid.
     */
    public function getPasswordGenerator(): string
    {
        $envGenerator = strtolower($this->readInConfig());
        $this->validateGenerator($envGenerator);

        return $envGenerator;
    }

    /**
     * Read the password generator in the plugin's config.
     *
     * @return string
     */
    protected function readInConfig(): string
    {
        $envGenerator = Configure::read(Plugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY);
        if (empty($envGenerator)) {
            return $this->getDefaultGenerator();
        }

        return $envGenerator;
    }

    /**
     * Checks that the generator passed is supported by Passbolt.
     *
     * @param string $generator Generator to be validated.
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException if the password generator provided is not valid.
     */
    protected function validateGenerator(string $generator): void
    {
        if (!in_array($generator, $this->getSupportedGenerators())) {
            throw new InternalErrorException(__('The password generator value "{0}" is not valid.', $generator));
        }
    }

    /**
     * Lists the password generators supported by Passbolt.
     *
     * @return string[]
     */
    protected function getSupportedGenerators(): array
    {
        return [
            self::PASSWORD_GENERATOR_SETTING_PASSPHRASE,
            self::PASSWORD_GENERATOR_SETTING_PASSWORD,
        ];
    }

    /**
     * The default password generator.
     *
     * @return string
     */
    protected function getDefaultGenerator(): string
    {
        return self::PASSWORD_GENERATOR_SETTING_PASSWORD;
    }
}
