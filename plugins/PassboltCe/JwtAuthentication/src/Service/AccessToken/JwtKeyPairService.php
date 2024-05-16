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

use App\Utility\UuidFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Passbolt\JwtAuthentication\Command\CreateJwtKeysCommand;
use Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException;

class JwtKeyPairService
{
    protected JwtTokenCreateService $secretService;

    protected JwksGetService $publicService;

    protected int $keyLength = JwtTokenCreateService::JWT_KEY_LENGTH;

    /**
     * CreateJwtKeysService constructor.
     *
     * @param \Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService|null $secretService JWT Secret Service
     * @param \Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService|null $publicService JWT Public Service
     */
    public function __construct(
        ?JwtTokenCreateService $secretService = null,
        ?JwksGetService $publicService = null
    ) {
        $this->secretService = $secretService ?? new JwtTokenCreateService();
        $this->publicService = $publicService ?? new JwksGetService();
    }

    /**
     * Will perform no action if key pair is found and force is set to false,
     *
     * @param bool $force Force the creation of a new pair.
     * @return void
     * @throws \Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException if the pair could not be created.
     */
    public function createKeyPair(bool $force = false): void
    {
        // If pair exists but force to false, exit silently
        if ($this->keyPairExists() && !$force) {
            return;
        }

        $config = [
            'digest_alg' => JwtTokenCreateService::JWT_ALG,
            'private_key_bits' => $this->getKeyLength(),
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
        $secretKeyPath = $this->getSecretKeyPath();
        $publicKeyPath = $this->getPublicKeyPath();

        try {
            $pk = openssl_pkey_new($config);
            if ($pk === false) {
                throw new \Exception('The JWT private key could not be created.');
            }
            $export = openssl_pkey_export_to_file($pk, $secretKeyPath);
            if ($export === false) {
                throw new \Exception('The JWT private key could not be written.');
            }
            $publicKey = openssl_pkey_get_details($pk)['key'] ?? false;
            if ($publicKey === false) {
                throw new \Exception('The JWT public key could not be extracted.');
            }
            $export = file_put_contents($publicKeyPath, $publicKey);
            if ($export === false) {
                throw new \Exception('The JWT public key could not be written.');
            }

            $permission = 0640;
            $res = chmod($secretKeyPath, $permission);
            if (!$res) {
                throw new \Exception("The permission of $secretKeyPath could not be set to $permission.");
            }
            $res = chmod($publicKeyPath, $permission);
            if (!$res) {
                throw new \Exception("The permission of $publicKeyPath could not be set to $permission.");
            }
        } catch (\Throwable $e) {
            throw new InvalidJwtKeyPairException($e->getMessage());
        }
    }

    /**
     * Validate the key pair validity as defined by the public and secret services.
     *
     * @param string|null $uuid Uuid for testing aim
     * @return object
     * @throws \Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException
     */
    public function validateKeyPair(?string $uuid = null)
    {
        // Minimal size of the private key
        $minSecretKeySize = JwtTokenCreateService::JWT_KEY_LENGTH;
        $uuid = $uuid ?? UuidFactory::uuid();
        try {
            if (!is_readable($this->publicService->getKeyPath())) {
                throw new \Exception(__('The JWT public key could not be read or is not valid.'));
            }
            $publicKey = file_get_contents($this->publicService->getKeyPath());
            $secretKeySize = $this->publicService->getSecretKeySize();

            if ($secretKeySize === 0) {
                throw new \Exception(__('The JWT public key could not be read or is not valid.'));
            }

            if ($secretKeySize < $minSecretKeySize) {
                throw new \Exception(__(
                    'The JWT private key should be at least {0} bytes long.',
                    $this->secretService::JWT_KEY_LENGTH
                ));
            }

            $jwt = $this->secretService->createToken($uuid, '2 seconds');

            return JWT::decode($jwt, new Key($publicKey, $this->secretService::JWT_ALG));
        } catch (\Throwable $e) {
            throw new InvalidJwtKeyPairException($e->getMessage());
        }
    }

    /**
     * @return bool if a key pair exists
     */
    public function keyPairExists(): bool
    {
        return is_readable($this->getPublicKeyPath()) && is_readable($this->getSecretKeyPath());
    }

    /**
     * @return string
     */
    public function getPublicKeyPath(): string
    {
        return $this->publicService->getKeyPath();
    }

    /**
     * @return string
     */
    public function getSecretKeyPath(): string
    {
        return $this->secretService->getKeyPath();
    }

    /**
     * @return string
     */
    public function readPublicKey(): string
    {
        return $this->publicService->readKeyFileContent();
    }

    /**
     * @return string
     */
    public function readSecretKey(): string
    {
        return $this->secretService->readKeyFileContent();
    }

    /**
     * @return string
     */
    public function getCreateJwtKeysCommand(): string
    {
        return ROOT . DS . 'bin/cake ' . CreateJwtKeysCommand::defaultName();
    }

    /**
     * @return int
     */
    public function getKeyLength(): int
    {
        return $this->keyLength;
    }

    /**
     * @param int $keyLength Length of the JWT key
     * @return self
     */
    public function setKeyLength(int $keyLength): self
    {
        $this->keyLength = $keyLength;

        return $this;
    }
}
