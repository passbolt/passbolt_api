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
 * @since         2.1.2
 */
namespace App\Utility\Healthchecks;

use App\Model\Entity\Gpgkey;
use App\Model\Rule\Gpgkeys\GopengpgFormatRule;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;

class GpgHealthchecks
{
    /**
     * Run all healthchecks
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function all(?array $checks = []): array
    {
        $checks = self::gpgLib($checks);
        $checks = self::gpgNotDefault($checks);
        $checks = self::gpgHome($checks);
        $checks = self::gpgKeyFile($checks);
        $checks = self::gpgFingerprint($checks);
        $checks = self::gpgKeyInKeyring($checks);
        $checks = self::gpgCanSign($checks);
        $checks = self::gpgCanEncrypt($checks);
        $checks = self::gpgCanEncryptSign($checks);
        $checks = self::gpgCanDecrypt($checks);
        $checks = self::gpgCanDecryptVerify($checks);
        $checks = self::gpgCanVerify($checks);
        $checks = self::gpgIsFormatGopengpgValid($checks);

        return $checks;
    }

    /**
     * Check gpg php module is installed and enabled
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgLib(?array $checks = []): array
    {
        try {
            OpenPGPBackendFactory::get();
            $checks['gpg']['lib'] = true;
        } catch (InternalErrorException $e) {
            $checks['gpg']['lib'] = false;
        }

        return $checks;
    }

    /**
     * Check fingerprint is set
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgNotDefault(?array $checks = []): array
    {
        $checks['gpg']['gpgKey'] = (Configure::read('passbolt.gpg.serverKey.fingerprint') !== null);
        $default = '2FC8945833C51946E937F9FED47B0811573EE67E';
        $checks['gpg']['gpgKeyNotDefault'] = (Configure::read('passbolt.gpg.serverKey.fingerprint') !== $default);

        return $checks;
    }

    /**
     * Check gnupg home is set and usable
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgHome(?array $checks = []): array
    {
        switch (Configure::read('passbolt.gpg.backend')) {
            case OpenPGPBackendFactory::GNUPG:
                // If no keyring location has been set, use the default one ~/.gnupg.
                $gnupgHome = getenv('GNUPGHOME');
                if (empty($gnupgHome)) {
                    $uid = posix_getuid();
                    $user = posix_getpwuid($uid);
                    $gnupgHome = $user['dir'] . '/.gnupg';
                }
                $checks['gpg']['info']['gpgHome'] = $gnupgHome;
                $checks['gpg']['gpgHome'] = file_exists($checks['gpg']['info']['gpgHome']);
                $checks['gpg']['gpgHomeWritable'] = is_writable($checks['gpg']['info']['gpgHome']);
                break;
            case OpenPGPBackendFactory::HTTP:
                // using cache for local keyring
                $checks['gpg']['gpgHome'] = true;
                $checks['gpg']['gpgHomeWritable'] = true;
                $checks['gpg']['info']['gpgHome'] = 'Cache engine';
                break;
            default:
                // unknown backend
                $checks['gpg']['gpgHome'] = false;
                $checks['gpg']['gpgHome'] = false;
                break;
        }

        return $checks;
    }

    /**
     * Check key file exist and are readable
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgKeyFile(?array $checks = []): array
    {
        $checks['gpg']['gpgKeyPublic'] = (Configure::read('passbolt.gpg.serverKey.public') !== null);
        $checks['gpg']['gpgKeyPublicReadable'] = is_readable(Configure::read('passbolt.gpg.serverKey.public'));
        if ($checks['gpg']['gpgKeyPublicReadable']) {
            $publicKeydata = file_get_contents(Configure::read('passbolt.gpg.serverKey.public'));
            $blockStart = '-----BEGIN PGP PUBLIC KEY BLOCK-----';
            $checks['gpg']['gpgKeyPublicBlock'] = (strpos($publicKeydata, $blockStart) === 0);
        }
        $checks['gpg']['gpgKeyPrivate'] = (Configure::read('passbolt.gpg.serverKey.private') !== null);
        $checks['gpg']['info']['gpgKeyPrivate'] = Configure::read('passbolt.gpg.serverKey.private');
        $checks['gpg']['gpgKeyPrivateReadable'] = is_readable(Configure::read('passbolt.gpg.serverKey.private'));
        if ($checks['gpg']['gpgKeyPrivateReadable']) {
            $privateKeydata = file_get_contents(Configure::read('passbolt.gpg.serverKey.private'));
            $blockStart = '-----BEGIN PGP PRIVATE KEY BLOCK-----';
            $checks['gpg']['gpgKeyPrivateBlock'] = (strpos($privateKeydata, $blockStart) === 0);
        }

        return $checks;
    }

    /**
     * Check that the private key match the fingerprint
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgFingerprint(?array $checks = []): array
    {
        $checks = array_replace_recursive(
            [
                'gpg' => [
                    'gpgKeyPrivateFingerprint' => false,
                    'gpgKeyPublicFingerprint' => false,
                    'gpgKeyPublicEmail' => false,
                    'gpgKeyPublicReadable' => false,
                    'gpgKeyPrivateReadable' => false,
                    'gpgKey' => false,
                ],
            ],
            $checks
        );
        $areKeysReadable = $checks['gpg']['gpgKeyPublicReadable'] && $checks['gpg']['gpgKeyPrivateReadable'];
        if ($areKeysReadable && $checks['gpg']['gpgKey']) {
            $gpg = OpenPGPBackendFactory::get();
            $privateKeydata = file_get_contents(Configure::read('passbolt.gpg.serverKey.private'));
            $privateKeyInfo = $gpg->getKeyInfo($privateKeydata);
            if ($privateKeyInfo['fingerprint'] === Configure::read('passbolt.gpg.serverKey.fingerprint')) {
                $checks['gpg']['gpgKeyPrivateFingerprint'] = true;
            }
            $publicKeydata = file_get_contents(Configure::read('passbolt.gpg.serverKey.public'));
            $publicKeyInfo = $gpg->getPublicKeyInfo($publicKeydata);
            if ($publicKeyInfo['fingerprint'] === Configure::read('passbolt.gpg.serverKey.fingerprint')) {
                $checks['gpg']['gpgKeyPublicFingerprint'] = true;
            }
            /** @var \App\Model\Table\GpgkeysTable $Gpgkeys */
            $Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');
            $checks['gpg']['gpgKeyPublicEmail'] = is_string($publicKeyInfo['uid']) &&
                $Gpgkeys->uidContainValidEmailRule($publicKeyInfo['uid']);
        }

        return $checks;
    }

    /**
     * Check that the server public/private keys are present in the keyring.
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgKeyInKeyring(?array $checks = []): array
    {
        $checks = array_replace_recursive(
            [
                'gpg' => [
                    'gpgHome' => false,
                    'gpgKeyPublicInKeyring' => false,
                ],
            ],
            $checks
        );
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        if (!$checks['gpg']['gpgHome'] || $fingerprint === null) {
            return $checks;
        }
        $gpg = OpenPGPBackendFactory::get();
        if (!$gpg->isKeyInKeyring($fingerprint)) {
            return $checks;
        }
        $checks['gpg']['gpgKeyPublicInKeyring'] = true;

        return $checks;
    }

    /**
     * Check if it can encrypt
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgCanEncrypt(?array $checks = []): array
    {
        $checks = array_replace_recursive(
            [
                'gpg' => [
                    'canEncrypt' => false,
                    'gpgKeyPublicInKeyring' => false,
                ],
            ],
            $checks
        );
        if ($checks['gpg']['gpgKeyPublicInKeyring']) {
            $_gpg = OpenPGPBackendFactory::get();
            $messageToEncrypt = 'test message';
            try {
                $_gpg->setEncryptKeyFromFingerprint(Configure::read('passbolt.gpg.serverKey.fingerprint'));
                $_gpg->encrypt($messageToEncrypt);
                $checks['gpg']['canEncrypt'] = true;
            } catch (Exception $e) {
                $checks['gpg']['canEncrypt'] = false;
            }
        }

        return $checks;
    }

    /**
     * Check if it can encrypt and sign
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgCanEncryptSign(?array $checks = []): array
    {
        $checks = array_replace_recursive(
            [
                'gpg' => [
                    'canEncryptSign' => false,
                    'gpgKeyPublicInKeyring' => false,
                ],
            ],
            $checks
        );
        if ($checks['gpg']['gpgKeyPublicInKeyring']) {
            $_gpg = OpenPGPBackendFactory::get();
            $messageToEncrypt = 'test message';
            try {
                $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
                $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
                $_gpg->setEncryptKeyFromFingerprint($fingerprint);
                $_gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
                $_gpg->encrypt($messageToEncrypt, true);
                $checks['gpg']['canEncryptSign'] = true;
            } catch (Exception $e) {
                $checks['gpg']['canEncryptSign'] = false;
            }
        }

        return $checks;
    }

    /**
     * Check if it can decrypt
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgCanDecrypt(?array $checks = []): array
    {
        $checks = array_replace_recursive(
            [
                'gpg' => [
                    'canEncrypt' => false,
                    'canDecrypt' => false,
                    'gpgKeyPublicInKeyring' => false,
                ],
            ],
            $checks
        );
        if ($checks['gpg']['gpgKeyPublicInKeyring']) {
            if ($checks['gpg']['canEncrypt']) {
                $_gpg = OpenPGPBackendFactory::get();
                $messageToEncrypt = 'test message';
                try {
                    $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
                    $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
                    $_gpg->setEncryptKeyFromFingerprint($fingerprint);
                    $encryptedMessage = $_gpg->encrypt($messageToEncrypt);
                    $_gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
                    $decryptedMessage = $_gpg->decrypt($encryptedMessage);
                    if ($decryptedMessage === $messageToEncrypt) {
                        $checks['gpg']['canDecrypt'] = true;
                    }
                } catch (Exception $e) {
                }
            }
        }

        return $checks;
    }

    /**
     * Check if it can decrypt and verify signature
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgCanDecryptVerify(?array $checks = []): array
    {
        $checks = array_replace_recursive(
            [
                'gpg' => [
                    'canDecryptVerify' => false,
                    'gpgKeyPublicInKeyring' => false,
                ],
            ],
            $checks
        );
        if ($checks['gpg']['gpgKeyPublicInKeyring']) {
            $_gpg = OpenPGPBackendFactory::get();
            $messageToEncrypt = 'test message';
            try {
                $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
                $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
                $_gpg->setEncryptKeyFromFingerprint($fingerprint);
                $_gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
                $encryptedMessage2 = $_gpg->encrypt($messageToEncrypt, true);
                $_gpg->setVerifyKeyFromFingerprint($fingerprint);
                $_gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
                $decryptedMessage2 = $_gpg->decrypt($encryptedMessage2, true);
                if ($decryptedMessage2 === $messageToEncrypt) {
                    $checks['gpg']['canDecryptVerify'] = true;
                }
            } catch (Exception $e) {
            }
        }

        return $checks;
    }

    /**
     * Check if it can verify
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgCanSign(?array $checks = []): array
    {
        $checks = array_replace_recursive(
            [
                'gpg' => [
                    'gpgKeyPublicInKeyring' => false,
                    'canSign' => false,
                ],
            ],
            $checks
        );
        if ($checks['gpg']['gpgKeyPublicInKeyring']) {
            $_gpg = OpenPGPBackendFactory::get();
            $_gpg->setSignKeyFromFingerprint(
                Configure::read('passbolt.gpg.serverKey.fingerprint'),
                Configure::read('passbolt.gpg.serverKey.passphrase')
            );
            $messageToEncrypt = 'test message';
            try {
                $_gpg->sign($messageToEncrypt);
                $checks['gpg']['canSign'] = true;
            } catch (Exception $e) {
                $checks['gpg']['canSign'] = false;
            }
        }

        return $checks;
    }

    /**
     * Check if it can verify
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgCanVerify(?array $checks = []): array
    {
        $checks = array_replace_recursive(
            [
                'gpg' => [
                    'canDecryptVerify' => false,
                    'canVerify' => false,
                ],
            ],
            $checks
        );
        if ($checks['gpg']['canDecryptVerify']) {
            $_gpg = OpenPGPBackendFactory::get();
            $messageToEncrypt = 'test message';

            try {
                $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
                $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
                $_gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
                $signedMessage = $_gpg->sign($messageToEncrypt);

                try {
                    $_gpg->setVerifyKeyFromFingerprint($fingerprint);
                    $_gpg->verify($signedMessage);
                    $checks['gpg']['canVerify'] = true;
                } catch (Exception $e) {
                }
            } catch (Exception $e) {
            }
        }

        return $checks;
    }

    /**
     * Check if both server keys are Gopengpg readable.
     * There should for example be no empty line before the end of
     * the block.
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function gpgIsFormatGopengpgValid(?array $checks = []): array
    {
        // Gpg keys should have only one return line
        $publicKey = new Gpgkey();
        $publicKey->armored_key = file_get_contents(Configure::read('passbolt.gpg.serverKey.public'));
        $privateKey = new Gpgkey();
        $privateKey->armored_key = file_get_contents(Configure::read('passbolt.gpg.serverKey.private'));
        $rule = new GopengpgFormatRule();
        $checks['gpg']['isPublicServerKeyGopengpgCompatible'] = $rule($publicKey);
        $checks['gpg']['isPrivateServerKeyGopengpgCompatible'] = $rule($privateKey);

        return $checks;
    }
}
