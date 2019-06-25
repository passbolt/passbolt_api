<?php
namespace App\Utility\OpenPGP;

use Cake\Core\Exception\Exception;

trait OpenPGPCommonAssertsTrait
{

    /**
     * Assert an armored message/key marker is present in plaintext
     *
     * @param string $armoredText message or key in ASCII armored format
     * @param string $marker a message delimiter like 'PGP MESSAGE'
     * @throws Exception if the armored message marker does not match the one provided
     * @return bool true if successful
     */
    public function assertGpgMarker(string $armoredText, string $marker)
    {
        $msg = __('This is not a valid OpenPGP armored message/key marker');
        try {
            $m = $this->getGpgMarker($armoredText);
        } catch (Exception $e) {
            throw new Exception($msg);
        }
        if ($m !== $marker) {
            throw new Exception($msg);
        }

        return true;
    }

    /**
     * Assert key is in the keyring
     *
     * @param string $fingerprint fingerprint
     * @return void
     */
    public function assertKeyInKeyring(string $fingerprint)
    {
        if (!$this->isKeyInKeyring($fingerprint)) {
            throw new Exception(__('The key {0} was not found in the keyring', $fingerprint));
        }
    }

    /**
     * Assert the signature key is set
     *
     * @throws Exception if not signature key is set
     * @return void
     */
    public function assertSignKey()
    {
        if (empty($this->_signKeyFingerprint)) {
            throw new Exception(__('Can not sign without a key. Set a sign key first.'));
        }
    }

    /**
     * Assert the verification key is set
     *
     * @throws Exception if not signature key is set
     * @return void
     */
    public function assertVerifyKey()
    {
        if (empty($this->_verifyKeyFingerprint)) {
            throw new Exception(__('Can not verify without a key. Set a verification key first.'));
        }
    }

    /**
     * Check if an encryption key is set
     *
     * @throws Exception if no encryption key is set
     * @return void
     */
    public function assertEncryptKey()
    {
        if (empty($this->_encryptKeyFingerprint)) {
            throw new Exception(__('Can not encrypt without a key. Set a public key first.'));
        }
    }

    /**
     * Check if a decrypt key is set
     *
     * @throws Exception if no decryption key is set
     * @return void
     */
    public function assertDecryptKey()
    {
        if (empty($this->_decryptKeyFingerprint)) {
            throw new Exception(__('Can not decrypt without a key. Set a secret key first.'));
        }
    }
}
