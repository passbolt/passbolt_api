<?php
/**
 * GpgKey Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.GpgKeyFixture
 * @since       version 2.12.9
 */
class GpgKeyFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Gpgkey';

	public function init() {
		$this->records = array(
			array(
				'id' => '0208f3a4-cccc-0000-a0c5-080027796c4c',
				'user_id' => 'bbd56042-cccc-11e1-a0c5-080027796c4a',
				'key_id' => 'E513B181',
				'bits' => '2048',
				'type' => 'RSA',
				'uid' => 'Lisa Simpson <lisa@passbolt.com>',
				'fingerprint' => 'EA1B5DDF504D669DB3DD3B8218A0ED3DE513B181',
				'created' => '2012-08-25 13:39:25',
				'modified' => '2012-08-25 13:39:25',
				'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
				'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
				'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG v1.4.10 (GNU/Linux)

mQENBFA40kUBCADBaiQ6NRhaiXyvCuenGtkTVaHcd/ViD7yNOhYSbV3Yy4Fr+I+G
Zzhsk/TI4nJfT+iGV7jj4wMmQahznEggfWClN/sf39a5E0Ldq2n/FwJNbKgDxy+H
eO1zw7NxE5nvpmAJH1dmBt4+DP8Xo3xLm5Xs74xYEQH3DvoyDhPxM9j6NBK+G9ND
gWmpRflQQDPqeBM2Bdw2JkXGnpQU3kBEoxObyanqXfm7j0BevgRhn4t7x2CmuO/N
GwVtmaXD4AK8D2jhE7nk+h23PowDEFtdTXkNONqrePwM07qAAyGyWuF5zAAmoWDT
XD/Z+VsJE0e8vXaP/o9VfwwRKCa5EPCNcYfHABEBAAG0IExpc2EgU2ltcHNvbiA8
bGlzYUBwYXNzYm9sdC5jb20+iQE4BBMBAgAiBQJQONJFAhsDBgsJCAcDAgYVCAIJ
CgsEFgIDAQIeAQIXgAAKCRAYoO095ROxgR73B/9CoNudtgsnTqglc+WMx248MjKf
nTzvJp2n9TxixqhhB5pokHIJUdBY4grJxQQcLIxK+6yFf5m0NOCz36DIepffn+uX
qTlf301N7ocq8KBL+5cC+bDxVFTLCfz7kypz/3rt+mGZ/inEZwAie+q5IRkYYgw8
fiFdjWVWwb8LK4lKLDm2cxy5Le10rlUwA+ChDcAZWOWx7Jd63Jg5qUXGQ4htonxq
0IsIBBTKUitNrKGcnFip19stAXvnBHogLSs2YHAdDCczvtwbwhPrXdFIeRtxjVL/
Nw2XSqpFuoTv5IAwPOmuRmFchkPXjZap028uhuogzZKCXGmZyjBXSgD+NgNzuQEN
BFA40kUBCACcLzCtGhNKr8Xyr7J1rkGDUnA8cwwEGZmEv9Pen3Nu4CGjZt2nfqYz
6AjUMOCoPrXhNZQyuBAYUmxkuciK25n4LoTb+EowvavbTf4sTYn6DczsmMWrO5mw
foJnD3OJpAZI47UWm/OhrVDW12e0inaqIV/AaSM3LVC10Csd+QafLdBuyekO7vXy
wRzVbqZLXWnDY1DfsQvfr0zN+RP8UMt+noWTIAl6aoXjof61Mw14uWwQ1lOnSL08
PGvCPL4LBPhS8fOfNEt7XQB0jpy/zZVztbuEXD3b9PkjY+b8hi5LQdt6AzZhQFhr
27m7i3VmtDosRtze8WguRWYDa0SKRuPTABEBAAGJAR8EGAECAAkFAlA40kUCGwwA
CgkQGKDtPeUTsYEL+Qf/doPzcVzoYYwOofEQQxz6Q1oS3bzg/5fgMDddQ6+vMhCV
1+1vGWNe5u0TySzrZvPUf4lnWfdUltNXc/0MgvlDB0akttrb8F5dPmhkI1dXvCKn
wV0j7LdjvSOVr32fntr7JKD3lnMQ8HxuKgY9kJbf61l4cEhMERHdhIWAQA/qy291
nYF5RNr4TVgt5WKsxWePFMIXFKvn4FTF2LwZNMQ9mxiJX3y29dHfszzrGLKe7dml
G7UcYlF813gwg+MhUMiHuQ/dGmwMuokTD4dnQITNKhE7CugZRGeEK9if+4S+SgYt
kPfdJVaSJWwdeOFMB165rxD9+kI62nTtmnwkadt5FA==
=erod
-----END PGP PUBLIC KEY BLOCK-----'
			),
		);
		parent::init();
	}
}
