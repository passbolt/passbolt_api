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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Test\Utility;

use App\Model\Entity\User;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;

/**
 * A helper to get test GPG keys, encrypted messages, etc.
 */
trait GpgMetadataKeysTestTrait
{
    /**
     * @var array|null cached private keys
     */
    private static $keycache = null;

    /**
     * Returns info related to Maki's key.
     *
     * @return array
     */
    public function getUserKeyInfo(): array
    {
        return [
            'armored_key' => file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'maki_public.key'), // ecc, curve25519
            'private_key' => file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'maki_private.key'),
            'fingerprint' => '3EED5E73EA34C95198A904067B28D501637D5102',
            'email' => 'maki@passbolt.com',
            'passphrase' => 'maki@passbolt.com',
        ];
    }

    /**
     * Returns info related to the metadata key
     *
     * @return array
     */
    public function getMetadataKeyInfo(): array
    {
        return [
            'public_key' => file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'metadata_public.key'), // ecc, curve25519
            'private_key' => file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'metadata_private.key'), // ecc, curve25519
            'passphrase' => '',
            'fingerprint' => '75E953F48EC5C1FCFFE575BB1BD05459D565666B',
            'email' => 'unit-tests@passbolt.com',
        ];
    }

    /**
     * Returns info related to Metadata shared key.
     *
     * @return array
     */
    public function getExpiredKeyInfo(): array
    {
        return [
            'armored_key' => file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'expired_public.key'),
            'fingerprint' => '7997026C7DE2B04044C98604A98D5FCDBFC94281',
            'email' => 'expired_key@passbolt.test',
        ];
    }

    /**
     * Returns info related to Metadata shared key.
     *
     * @return array
     */
    public function getInvalidAlgKeyInfo(): array
    {
        return [
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'elgamal_public.key'),
            'fingerprint' => 'A0F8C364CDBF24A0B08705B9E26A323B3F4E4124',
            'email' => 'elgamal@passbolt.com',
        ];
    }

    /**
     * Message encrypted for Maki (maki@passbolt.com) and signed with server public key (unsecure.key).
     *
     * @return false|string
     */
    public function getEncryptedMetadataPrivateKeyFoUser()
    {
        return file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'metadata_private_key_for_maki.msg');
    }

    /**
     * Message encrypted for Maki (maki@passbolt.com), it's different from getEncryptedMetadataPrivateKeyFoUser().
     *
     * @return false|string
     */
    public function getEncryptedMetadataPrivateKeyFoUserDifferent()
    {
        return file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'metadata_private_key_for_maki_2.msg');
    }

    /**
     * Message encrypted with Server Key(unsecure.key), unsigned.
     *
     * @return false|string
     */
    public function getEncryptedMetadataPrivateKeyForServerKey()
    {
        return file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'msg_for_server_key.msg');
    }

    /**
     * Session key data encrypted for Server Key.
     *
     * @return false|string
     */
    public function getEncryptedMetadataSessionKeyForServerKey()
    {
        return file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'metadata_session_key_for_server_key.msg');
    }

    /**
     * Session key data encrypted for Maki.
     *
     * @return false|string
     */
    public function getEncryptedMetadataSessionKeyForMaki()
    {
        return file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'metadata_session_key_for_maki.msg');
    }

    /**
     * Cleartext version of self::getEncryptedMetadataPrivateKeyFoUser()
     *
     * @return array
     */
    public function clearTextMetadataPrivateKeyDataForUser(): array
    {
        return [
            'object_type' => 'PASSBOLT_METADATA_PRIVATE_KEY',
            'domain' => 'https://passbolt.test',
            'fingerprint' => '3CAFE4CF16C5CC76878F9DB43679575AB19C3F00',
            'armored_key' => '-----BEGIN PGP PRIVATE KEY BLOCK-----

xYYEZtBWmRYJKwYBBAHaRw8BAQdA/cuMUXv/NKla7dLwSgZCoOOLNpU+JXkF
yehJitu+0qv+CQMIk6Vaa4f5YuPgzv4MYe5GoH8UaO1v+C0/QTeU0R3LEFPe
+PN2kdur1wbYyV6imPIq9QFYcqme1pa3IEE5YM2HTCc86lPpL9TDBDLwr7Ud
hs0xUGFzc2JvbHQgTWV0YWRhdGEgS2V5IDxtZXRhZGF0YWtleUBwYXNzYm9s
dC50ZXN0PsKMBBAWCgA+BYJm0FaZBAsJBwgJkDZ5V1qxnD8AAxUICgQWAAIB
AhkBApsDAh4BFiEEPK/kzxbFzHaHj520NnlXWrGcPwAAAGCuAQDveqdbp/2z
s6gFKQ5YNwOuD+cKPQNe0XKABE79HXA/zwD7B6Mp4jfv87khNAKy1YWBhquM
u+dvgq+2ZWYQFXPPwg3HiwRm0FaZEgorBgEEAZdVAQUBAQdApC6/VaLbDuHC
siYf0zzAwhirrqTXmQz0I190AXat4TYDAQgH/gkDCFOHi4PzzzNp4KIE1gls
4FUbuf5eNo4BL3D0LOx2YjoqtmgGtplq04FRwbfMdngFantgM1/bSC5Iz7p1
sPzcc/sNnxBUVODMSryYeMtu2pzCeAQYFgoAKgWCZtBWmQmQNnlXWrGcPwAC
mwwWIQQ8r+TPFsXMdoePnbQ2eVdasZw/AAAAWy0A/2bGt1NgzoIpQqtH6s0t
jfdmXB1QN3Sbu6P6QxNYD8LXAQC9txjUC8EgikieEAUCcAXczY3FtlW1q9C2
Ggmm+WO4Cw==
=tjOB
-----END PGP PRIVATE KEY BLOCK-----',
            'passphrase' => 'metadatakey@passbolt.test',
        ];
    }

    /**
     * Cleartext version of self::getEncryptedMetadataPrivateKeyFoUserDifferent()
     *
     * @return array
     */
    public function clearTextMetadataPrivateKeyDataForUserDifferent(): array
    {
        return [
            'object_type' => 'PASSBOLT_METADATA_PRIVATE_KEY',
            'domain' => 'https://passbolt.test',
            'fingerprint' => 'AA47738ABBD0D9E9FE60DAD207B2A1DC00F49417',
            'armored_key' => '-----BEGIN PGP PRIVATE KEY BLOCK-----

xYYEZtBYjRYJKwYBBAHaRw8BAQdAVkMSZcZxaEnkp0SlqGOmV9yB3H9KO/Ua
hN7s8YDMjLv+CQMIAVlaGEHCpqngiNRHSN7mlGC+aLciFP4cWC1DqYFVJ3PD
g+xW23qIvKANMjiiFBhUN7KgZq1Zcgo56TSw8fpvVU2ybRNLxWH3slwiitvr
fs0xUGFzc2JvbHQgTWV0YWRhdGEgS2V5IDxtZXRhZGF0YWtleUBwYXNzYm9s
dC50ZXN0PsKMBBAWCgA+BYJm0FiNBAsJBwgJkAeyodwA9JQXAxUICgQWAAIB
AhkBApsDAh4BFiEEqkdzirvQ2en+YNrSB7Kh3AD0lBcAAMt5AQDUumsicwP7
jfM8Q0nIYK+OeqF1jBoVIWIwBFpwbrlqHwEAhpId1/SoIOP9tXB8oNHWpS+O
I8mk7E0k9UoTCQrRMwbHiwRm0FiNEgorBgEEAZdVAQUBAQdAvk49ErXVannH
mSv67eiPoyF5snB/zBK5QtDENXHBL2EDAQgH/gkDCAfANJZxZmQF4M6rSkLU
K/wf8wP1hNV0rGCrTmQMLVX8PiP7+AftXj48p45rZDDmnsmnA3fcAjp4Amqo
AdXFkLon67Kc25KWWV/+i/idH/TCeAQYFgoAKgWCZtBYjQmQB7Kh3AD0lBcC
mwwWIQSqR3OKu9DZ6f5g2tIHsqHcAPSUFwAALJoBAL1OyNYaeGdoiV1wrmeV
lxxKZ9kVIDqGPBLf3jW6grjuAP0TC06qJC9Ys8aFVbNUepZV2X51GtrBZ+go
Spb9xQz3BA==
=ZsTy
-----END PGP PRIVATE KEY BLOCK-----',
            'passphrase' => 'metadatakey@passbolt.test',
        ];
    }

    /**
     * Cleartext version of self::getEncryptedMetadataPrivateKeyForServerKey()
     *
     * @return array
     */
    public function clearTextMetadataPrivateKeyDataForServer(): array
    {
        return [
            'object_type' => 'PASSBOLT_METADATA_PRIVATE_KEY',
            'domain' => 'https://passbolt.test',
            'fingerprint' => '66453926843D575AB3F65389EFC27032BBF6EECB',
            'armored_key' => '-----BEGIN PGP PRIVATE KEY BLOCK-----

xYYEZtBflhYJKwYBBAHaRw8BAQdAjlV8Y5Qa7+Jvtymuk3ljiTFciWbe1W1p
D7bGh7/G0+f+CQMIKgoGxS5orNvgwjPKfhPP2gdWvoeDLH7uQxpfIjfbqpLd
BXYBqSMkcXtXAnnQLIz51loCyegKjeXAITVHczdfNLvT9DBrfExOBlXTGxiN
qs1HU2VydmVyIE1ldGFkYXRhIFByaXZhdGUgS2V5IDxzZXJ2ZXJfbWV0YWRh
dGFfcHJpdmF0ZV9rZXlAcGFzc2JvbHQudGVzdD7CjAQQFgoAPgWCZtBflgQL
CQcICZDvwnAyu/buywMVCAoEFgACAQIZAQKbAwIeARYhBGZFOSaEPVdas/ZT
ie/CcDK79u7LAABtOwD+KV5Jvby8YOhLti83VT3QJq9wjvQc0frhKKtneQbM
I6AA/3yLD9cDcHDLoMMC/SxepnVWJKErpvU2PPScZR9gQ9cDx4sEZtBflhIK
KwYBBAGXVQEFAQEHQEcwl2Lq8vc5FmvPmJ0yPzXeSoY+4JbY68Vz6RTW4Shk
AwEIB/4JAwjPGa5IDUrVheAnBZsqYPouAGk1iINWKcobfXA/FXTFgwGp+X06
a7M0mcer8UHaMFrQBtJz4L2XqwJWip+M8lLRxeK1h0DioD9Szmv4UwUhwngE
GBYKACoFgmbQX5YJkO/CcDK79u7LApsMFiEEZkU5JoQ9V1qz9lOJ78JwMrv2
7ssAAJVZAQC309l57P8TZIwXclLNW9C6fSIxa3vpsi3OQCLlHahlfwEAi5nH
qHRRyS/qHxm+91OQZ99KGhkYMNNE5Xa7HQKDTgY=
=A9iB
-----END PGP PRIVATE KEY BLOCK-----',
            'passphrase' => 'server_metadata_private_key@passbolt.test',
        ];
    }

    /**
     * Cleartext version of self::getEncryptedMetadataSessionKeyForMaki()
     *
     * @return array
     */
    public function clearTextMetadataSessionKeyForMaki(): array
    {
        return [
            'foreign_model' => 'secret', // can be: resource, secret, folder, etc.
            'foreign_id' => '903a009d-2e58-4c28-bd61-52bc7c864816',
            'session_key' => '9:1AEACC37D64E6CC77E7AA7D17B0BE3067890586BBC4BCD3D99051BD2D4459B99',
        ];
    }

    /**
     * Cleartext version of self::getEncryptedMetadataSessionKeyForServerKey()
     *
     * @return array
     */
    public function clearTextMetadataSessionKeyForServerKey(): array
    {
        return [
            'foreign_model' => 'resource', // can be: resource, secret, folder, etc.
            'foreign_id' => '0012adea-e778-4893-833e-65d7502452f1',
            'session_key' => '9:1AEACC37D64E6CC77E7AA7D17B0BE3067890586BBC4BCD3D99051BD2D4457F28',
        ];
    }

    /**
     * A valid OpenPGP message encrypted for maki_public.key and signed with unsecure_private.key.
     *
     * @deprecated NOT A VALID PRIVATE KEY MESSAGE
     * @return string
     */
    public function getDummyPrivateKeyOpenPGPMessage(): string
    {
        // Decrypted message: super secret message
        return "-----BEGIN PGP MESSAGE-----

wV4DrGC7ooPDztsSAQdAln93/614xeFEl9aaP1VVTFZtbqF7+vle6L+kzSc+BU8w
jk/YF9DJ9h1ovB64DKi4z0rxplOSR+d1FEBZBnDLHD5N2npyFxtGuAQ6vOoloJPD
0sHCAXm7PcQeEN2dMhL7ctRWfOTP3F1OF9CG3dUbumKkKDDPf9uHqT17ij7Ifavn
c3sii0LRDDlknva30jxtfwmJilX6LiWqAI+HzPeSwK1FLBhd5tM8Tknr2kh8pCKF
lxLInZJZQbOCUJ1mQ6oW9IcV3Eu6n+BkeT26l/kGseuqITnDfo13X6FQCpHO7uLR
993AN6Lf0kUNbcYVmyA/o1Fbz/PLgRGIzJRwWB/DTjUJ9vfwl3DLNz+25FGr+zxL
NhyuchytmtY8ozO49YZp+l3d8N8yJvg2b++KG3PFB+JCfzlbLoTjD14hBig907Ez
eC8n5Zmg6uIBY4CXVspCA5JoPZcGWii+jxhX4GnK82k2TPVMsIwkiBAWqqT80FWP
ssMIWA23BDAA7DojZIUf/s+Tv05xtoEfNIPeuUP72g6K7bBaTloL116eEuzq7ctj
JpilQqzgQuIx/UpxkXg+XYnbLCT/kxvaf4pjwepsm3R4kbt8acpB6VkVeM/Va0eI
Ucuo2SD00yK5DTV/OMmS8ERYSD+N3lwzMbo9WBrpz3UYX37b2mnDMisXaUu54xGX
n8pIxxyYdb6dVwxzJpvINvAiVUxC6wSDu+1u0Urh1ZV8sdN85qXCZMCn1af6RmQ6
VmUhzIwATp4OkNJMSIvwMcVZ9UCfN33xLrn3Vo+7Bm2u08Q5CpLGuRSMeVgySikj
MDkFSiznzXL0gQ4U1f8pDcY4+HIBItVtew/5fUNUkzNKA+JXqb9eOgavRAZIbb4d
I4okmMzJpJrQJ7zEzOh8g3eIjBInevhcaaJqSwt9JGphoSND+b0XCIV1XOehLwEe
dT/PmTWE57npBIIz4kQQcHOziFAG
=vK1i
-----END PGP MESSAGE-----
";
    }

    /**
     * @return array
     */
    public function getValidPrivateKeyCleartext(): array
    {
        $key = $this->getMetadataKeyInfo();

        return [
            'object_type' => 'PASSBOLT_METADATA_PRIVATE_KEY',
            'domain' => Router::url('/', true),
            'armored_key' => $key['private_key'],
            'fingerprint' => $key['fingerprint'],
            'passphrase' => $key['passphrase'],
        ];
    }

    public function getValidPrivateKeyDataForServer(): string
    {
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        if (!isset(static::$keycache[$fingerprint])) {
            $gpg = OpenPGPBackendFactory::get();
            $gpg->importServerKeyInKeyring();
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
            $gpg->setSignKeyFromFingerprint($fingerprint, Configure::read('passbolt.gpg.serverKey.passphrase'));
            self::$keycache[$fingerprint] = $gpg->encryptSign($this->getValidPrivateKeyCleartextJson());
            $gpg->clearKeys();
        }

        return self::$keycache[$fingerprint];
    }

    public function getValidPrivateKeyData(string $publicKey): string
    {
        $gpg = OpenPGPBackendFactory::get();
        $encryptKeyInfo = $gpg->getPublicKeyInfo($publicKey);
        $fingerprint = $encryptKeyInfo['fingerprint'];
        if (!isset(static::$keycache[$fingerprint])) {
            $gpg = OpenPGPBackendFactory::get();
            $gpg->setEncryptKey($publicKey);
            $gpg->setSignKeyFromFingerprint(
                Configure::read('passbolt.gpg.serverKey.fingerprint'),
                Configure::read('passbolt.gpg.serverKey.passphrase')
            );
            self::$keycache[$fingerprint] = $gpg->encrypt($this->getValidPrivateKeyCleartextJson());
            $gpg->clearKeys();
        }

        return self::$keycache[$fingerprint];
    }

    public function getValidPrivateKeyCleartextJson(): string
    {
        return json_encode($this->getValidPrivateKeyCleartext());
    }

    /**
     * @param string $data Data to encrypt.
     * @return string Encrypted data
     */
    public function encryptForMetadataKey(string $data): string
    {
        $metadataKeyInfo = $this->getMetadataKeyInfo();
        $fingerprint = $metadataKeyInfo['fingerprint'];
        $passphrase = $metadataKeyInfo['passphrase'];
        $armoredKey = $metadataKeyInfo['public_key'];

        $gpg = OpenPGPBackendFactory::get();
        try {
            $gpg->importKeyIntoKeyring($armoredKey);
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
            $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
        } catch (\Exception $exception) {
            try {
                // Try to import the key in keyring again
                $gpg->importServerKeyInKeyring();
                $gpg->importKeyIntoKeyring($metadataKeyInfo['private_key']);

                $gpg->setEncryptKeyFromFingerprint($fingerprint);
                $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
            } catch (\Exception $exception) {
                $msg = __('Could not import the user OpenPGP key.');
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        $encryptedData = $gpg->encryptSign($data);
        $gpg->clearKeys();

        return $encryptedData;
    }

    /**
     * @param string $data Data to encrypt
     * @param User $user User entity.
     * @param array $keyInfo Key information.
     * @return string Encrypted data.
     */
    private function encryptForUser(string $data, User $user, array $keyInfo): string
    {
        $fingerprint = $user->gpgkey->fingerprint;
        $gpg = OpenPGPBackendFactory::get();
        $gpg->importServerKeyInKeyring();
        $gpg->importKeyIntoKeyring($keyInfo['privateKey']);
        $gpg->setEncryptKeyFromFingerprint($fingerprint);
        $gpg->setSignKeyFromFingerprint($fingerprint, $keyInfo['passphrase']);
        $encryptedData = $gpg->encryptSign($data);
        $gpg->clearKeys();

        return $encryptedData;
    }

    /**
     * @param string $ciphertext Message to decrypt.
     * @param array $keyInfo Key information - fingerprint, passphrase, armored_key(private key).
     * @return string
     */
    private function decryptOpenPGPMessage(string $ciphertext, array $keyInfo): string
    {
        $gpg = OpenPGPBackendFactory::get();
        $gpg->clearKeys();

        $fingerprint = $keyInfo['fingerprint'];
        $passphrase = $keyInfo['passphrase'];

        try {
            $gpg->setVerifyKeyFromFingerprint($fingerprint);
            $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
        } catch (\Exception $exception) {
            $gpg->importKeyIntoKeyring($keyInfo['armored_key']);
            $gpg->setVerifyKeyFromFingerprint($fingerprint);
            $gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
        }

        return $gpg->decrypt($ciphertext);
    }
}
