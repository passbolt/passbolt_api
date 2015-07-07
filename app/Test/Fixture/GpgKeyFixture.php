<?php
/**
 * GpgKey Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.GpgKeyFixture
 * @since       version 2.12.9
 */
class GpgkeyFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Gpgkey';

	/**
	 * Fields
	 *
	 * @var array

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 4096, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'bits' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'uid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'key_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 8, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'fingerprint' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 51, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 16, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'expires' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'key_created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	); */

	public function init() {
		$this->records = array(
			array(
				'id' => '0208f3a4-cccc-0000-a0c5-080027796c4c',
				'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
				'key_id' => 'C7FF421A',
				'bits' => '2048',
				'type' => 'RSA',
				'uid' => 'Ada Lovelace <ada@passbolt.com>',
				'fingerprint' => '333788B5464B797FDF10A98F2FE96B47C7FF421A',
				'created' => '2012-08-25 13:39:25',
				'modified' => '2012-08-25 13:39:25',
				'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
				'modified_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
				'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFWK2WIBCADVs92u7xQkFpXwV/ShpQ89hA7HJz0tRTSGi57+SWIZOChKFYlT
kzKqVYoSQa9nTAAnkiO6mDqaBBNx9xtV1q2ITGGz45U3oYm1+1u7BAeGQCeZA4az
Qqfk6+vRq+lBZk6v7rBRqH+mN+CTsink20eygSl+u5YwKdcH6KbYaYMqj+6VTCTm
JN/H+xTQpJbxQfm/pq1ms9GnsYgbdEpOH+NnO23d3HCSMDE2xPWVfF3P71VhD+hg
yW3fLKRZwlMGombGf81YFnoeZWMYjfgZaMAxPapyOQZ/5C6X8TUFAhrf0UTtHLAY
H4XSct4zdV3cGJKqvKY6lIvowWAt0xe6vJQnABEBAAG0M2FkYSBsb3ZlbGFjZSAo
cGFzc2JvbHQgZGVtbyBrZXkpIDxhZGFAcGFzc2JvbHQuY29tPokBNwQTAQoAIQUC
VYrZYgIbAwULCQgHAwUVCgkICwUWAgMBAAIeAQIXgAAKCRAv6WtHx/9CGuBeB/9L
FN2gW0DCsd8099Bjow7NVwgh0+KUKkzlXpOBvn6/qkamzcq+CX4XSaux/F0uU6fj
Y0vsObe6kXGS8RyXpB3Itx/gwZ7R1mwMZVlKypeqDme3P2AnP+HRpomafHqsKs2I
clipwoAS9EwhMer/2QxQMbHp6h50qMxDHoMhnDGmOq5Zcb9Hy8/qHAolTgnk/Y8A
n/9riyzPvDcr+2vsZp3RYOvPxZZPjqwPR3FNQURpyYdhEGl51kpyM9Dc6lwKPA/c
YNVgSHbGLeQd/L6Dfc1eqZI/4GKst1ll734JJOQvYmBnLwVO1FrdvUJDuDn9br5U
IUmFnsvqVv4bUNJvl232uQENBFWK2WIBCADBLSzTzxqY9Lf3+OcHZIc45BnTaZzM
ZnOVJH9wccDeZczDKNEe4xpBdhQtrk8Lre3hcV80pm4jineo7yJtxsHKPejkzhRm
uJSA344mO8SSYMJeN4fyIY+TMwZts+trSi8G0Mw34jVA19JLSnctzM+qP2FK2HLS
RyS7joxblx7MfM5SW5s6NBW1wihMF2xfSYuukc62uzCksYFG1vjVNgdsWqO/d1bH
IVCxnu0a93ElnxR9EtQoTmdKflOMQaJmLWTDTgjZUe12y5uSc0CJVyhJ3Vxf+sqH
rDKdttKE5F2jcKkF6L9nihrrOulyFnm7JDZG09izJhQrjy8+F41/QswpABEBAAGJ
AR8EGAEKAAkFAlWK2WICGwwACgkQL+lrR8f/QhrI3wgApkoy09u13miDLsPJFO4g
2LWbdJBnC+K7K+BiJAab08Q5fDzxF+U02bBwq1z6TWJzKOseW6nBZZnYERteCNxZ
ljiRkcux42hU0hoH3GuZRjj2N+lCupsrmWNo4I2+pRijbdITQT3v8iba3vTrfqrC
+3b+X45qIQKSePcGIhjCeuFZtMamUISCx+O49ltCa+VtToHTC+NqCYi4N8TbS0ad
AIhiF+HjVjLgVYwcBoRxBdEgSdI9789aMpLhjrypV3ARc0JsNjmZiAGCjqPObkVP
ef1dHv+uAIXKttVBEgwjZs9c3Xx7m413yCIj5q5DH213Hne+We0At1l9eijACl+Y
7w==
=TcyZ
-----END PGP PUBLIC KEY BLOCK-----'
			),
		);
		parent::init();
	}
}
