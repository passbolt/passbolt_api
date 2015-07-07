<?php

require_once dirname(__FILE__).'/../lib/openpgp.php';
require_once dirname(__FILE__).'/../lib/openpgp_crypt_rsa.php';

/* Parse public key from STDIN */
$wkey = OpenPGP_Message::parse(file_get_contents('php://stdin'));

/* Parse signed message from file named "t" */
$m = OpenPGP_Message::parse(file_get_contents('t'));

/* Create a verifier for the key */
$verify = new OpenPGP_Crypt_RSA($wkey);

/* Dump verification information to STDOUT */
var_dump($verify->verify($m));

?>
