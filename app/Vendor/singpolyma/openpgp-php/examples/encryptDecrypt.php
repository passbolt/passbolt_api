<?php

require_once dirname(__FILE__).'/../lib/openpgp.php';
require_once dirname(__FILE__).'/../lib/openpgp_crypt_rsa.php';
require_once dirname(__FILE__).'/../lib/openpgp_crypt_symmetric.php';

$key = OpenPGP_Message::parse(file_get_contents(dirname(__FILE__) . '/../tests/data/helloKey.gpg'));
$data = new OpenPGP_LiteralDataPacket('This is text.', array('format' => 'u', 'filename' => 'stuff.txt'));
$encrypted = OpenPGP_Crypt_Symmetric::encrypt($key, new OpenPGP_Message(array($data)));

// Now decrypt it with the same key
$decryptor = new OpenPGP_Crypt_RSA($key);
$decrypted = $decryptor->decrypt($encrypted);

var_dump($decrypted);
