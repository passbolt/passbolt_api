<?php

require_once dirname(__FILE__).'/../lib/openpgp.php';
require_once dirname(__FILE__).'/../lib/openpgp_crypt_rsa.php';

/* Parse secret key from STDIN, the key must not be password protected */
$wkey = OpenPGP_Message::parse(file_get_contents('php://stdin'));
$wkey = $wkey[0];

/* Create a new literal data packet */
$data = new OpenPGP_LiteralDataPacket('This is text.', array('format' => 'u', 'filename' => 'stuff.txt'));

/* Create a signer from the key */
$sign = new OpenPGP_Crypt_RSA($wkey);

/* The message is the signed data packet */
$m = $sign->sign($data);

/* Generate clearsigned data */
$packets = $m->signatures()[0];
echo "-----BEGIN PGP SIGNED MESSAGE-----\nHash: SHA256\n\n";
echo preg_replace("/^-/", "- -",  $packets[0]->data)."\n";
echo OpenPGP::enarmor($packets[1][0]->to_bytes(), "PGP SIGNATURE");

?>
