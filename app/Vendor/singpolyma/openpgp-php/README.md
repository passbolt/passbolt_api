OpenPGP.php: OpenPGP for PHP
============================

This is a pure-PHP implementation of the OpenPGP Message Format (RFC 4880).

* <http://github.com/bendiken/openpgp-php>

### About OpenPGP

OpenPGP is the most widely-used e-mail encryption standard in the world. It
is defined by the OpenPGP Working Group of the Internet Engineering Task
Force (IETF) Proposed Standard RFC 4880. The OpenPGP standard was originally
derived from PGP (Pretty Good Privacy), first created by Phil Zimmermann in
1991.

* <http://tools.ietf.org/html/rfc4880>
* <http://www.openpgp.org/>

Features
--------

* Encodes and decodes ASCII-armored OpenPGP messages.
* Parses OpenPGP messages into their constituent packets.
  * Supports both old-format (PGP 2.6.x) and new-format (RFC 4880) packets.
* Helper class for verifying, signing, encrypting, and decrypting messages using Crypt_RSA from <http://phpseclib.sourceforge.net>
* Helper class for encrypting and decrypting messages and keys using Crypt_AES and Crypt_TripleDES from <http://phpseclib.sourceforge.net>

Users
-----

OpenPGP.php is currently being used in the following projects:

* <http://drupal.org/project/openpgp>

Download
--------

To get a local working copy of the development repository, do:

    % git clone git://github.com/bendiken/openpgp-php.git

Alternatively, you can download the latest development version as a tarball
as follows:

    % wget http://github.com/bendiken/openpgp-php/tarball/master

Authors
-------

* [Arto Bendiken](mailto:arto.bendiken@gmail.com) - <http://ar.to/>
* [Stephen Paul Weber](mailto:singpolyma@singpolyma.net) - <http://singpolyma.net/>

License
-------

OpenPGP.php is free and unencumbered public domain software. For more
information, see <http://unlicense.org/> or the accompanying UNLICENSE file.
