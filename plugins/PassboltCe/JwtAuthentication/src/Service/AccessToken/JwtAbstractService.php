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
namespace Passbolt\JwtAuthentication\Service\AccessToken;

use Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException;

abstract class JwtAbstractService
{
    public const USER_ACCESS_TOKEN_KEY = 'access_token';
    public const JWT_CONFIG_DIR = CONFIG . 'jwt' . DS;

    protected string $keyPath;

    /**
     * @param string $path Path to the secret/private key file
     * @return self
     */
    public function setKeyPath(string $path): self
    {
        $this->keyPath = $path;

        return $this;
    }

    /**
     * @return string Path to the secret/private key file
     */
    public function getKeyPath(): string
    {
        return $this->keyPath;
    }

    /**
     * @return string Content of the secret/private key file
     * @throws \Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException if the file is not found or not readable.
     */
    public function readKeyFileContent(): string
    {
        if (!is_readable($this->getKeyPath())) {
            $userErrorMessage = __('The key pair for JWT Authentication is not complete.');
            throw new InvalidJwtKeyPairException($userErrorMessage);
        }

        return file_get_contents($this->getKeyPath());
    }
}
