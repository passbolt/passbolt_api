<?php
namespace App\Utility\OpenPGP;

interface OpenPGPBackend {

    public function setEncryptKey(string $armoredKey);
    public function setEncryptKeyFromFingerprint(string $fingerprint);

    public function setDecryptKey(string $armoredKey, string $passphrase);
    public function setDecryptKeyFromFingerprint(string $fingerprint, string $passphrase);

    public function setSignKey(string $armoredKey, string $passphrase);
    public function setSignKeyFromFingerprint(string $fingerprint, string $passphrase);

    public function importKeyIntoKeyring(string $armoredKey);

    public function isParsableArmoredPublicKey(string $armoredKey);
    public function isParsableArmoredPrivateKey(string $armoredKey);
    public function isValidMessage(string $armored);

    public function getKeyInfo(string $armoredKey);
    public function getPublicKeyInfo(string $armoredKey);
    public function getKeyInfoFromKeyring(string $fingerprint);
    public function isKeyInKeyring(string $fingerprint);

    public function encrypt(string $text, bool $sign = false);
    public function decrypt(string $text, bool $verifySignature = false, array &$signatureInfo = []);

    public function setUpServerKey(string $fingerprint = null, string $keyFilePath = null);

}