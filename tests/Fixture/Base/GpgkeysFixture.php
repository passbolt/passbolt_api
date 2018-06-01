<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GpgkeysFixture
 *
 */
class GpgkeysFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'armored_key' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'bits' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '2048', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'uid' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'key_id' => ['type' => 'string', 'length' => 8, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fingerprint' => ['type' => 'string', 'length' => 51, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'type' => ['type' => 'string', 'length' => 16, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'expires' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'key_created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fingerprint' => ['type' => 'index', 'columns' => ['fingerprint'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => '04481719-5d9d-5e22-880a-a6b9270601d2',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

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
GkkO+pcgU1wQ
=Zopv
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4096,
                'uid' => 'Ada Lovelace <ada@passbolt.com>',
                'key_id' => '5D9B054F',
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'type' => 'RSA',
                'expires' => '2019-08-09 12:48:31',
                'key_created' => '2015-08-09 12:48:31',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => '118fd79b-b683-5595-98d5-fe622c679711',
                'user_id' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
RQKw
=8Nu4
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2045,
                'uid' => 'Irene Greif <irene@passbolt.com>',
                'key_id' => '74FE8D31',
                'fingerprint' => 'FDC7DF9AB0C61C33B2D871C25207C56474FE8D31',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2016-05-25 13:25:07',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => '1d3d0565-f075-50d4-b58a-cbb82700e79b',
                'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
t8bJrYvwZzYi
=Wbeg
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Carol Shaw <carol@passbolt.com>',
                'key_id' => '82945D3E',
                'fingerprint' => '57DE7D79ABE733A235EB1F84CDF8FC8682945D3E',
                'type' => 'RSA',
                'expires' => '2019-07-02 11:25:12',
                'key_created' => '2015-07-02 11:25:12',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => '2111bdd2-b19c-55a1-94ae-13e9ae67c1e9',
                'user_id' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
VX5VSIUYQFVe
=UUaB
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4096,
                'uid' => 'Hedy Lamarr <hedy@passbolt.com>',
                'key_id' => 'A812A61E',
                'fingerprint' => 'ED39FA1D15C0B2A81359A872928088DAA812A61E',
                'type' => 'RSA',
                'expires' => '2019-07-03 10:51:31',
                'key_created' => '2015-07-03 10:51:31',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => '596201e6-8d3b-54b9-84e6-3ed6ef99113d',
                'user_id' => '1e73e104-d53e-579d-a0c4-e9aeaca76c56',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
ylomqJXBaNog7cM=
=PkGo
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4096,
                'uid' => 'Lynne Jolitz <lynne@passbolt.com>',
                'key_id' => '8EA58CFC',
                'fingerprint' => 'B5D364ECDAB5B3F79C6879B85F64D13E8EA58CFC',
                'type' => 'RSA',
                'expires' => '2019-07-03 10:56:50',
                'key_created' => '2015-07-03 10:56:50',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => '82eaeed6-32a9-5e56-af93-6bc362a9d62b',
                'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
BzXzQTAO+Vo1Nkyf7B8=
=vRc1
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4095,
                'uid' => 'Frances Allen <frances@passbolt.com>',
                'key_id' => '477FB14C',
                'fingerprint' => '98DA33350692F21BD5F83A17E8DC5617477FB14C',
                'type' => 'RSA',
                'expires' => '2019-07-03 10:48:31',
                'key_created' => '2015-07-03 10:48:31',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => '91d8a7fd-3ab3-5e98-a4a5-0d8694ff23b9',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
/egdKpXSkvk7uOW9taOksqsB76FR
=lYGs
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Passbolt Default Admin <admin@passbolt.com>',
                'key_id' => 'D06426D3',
                'fingerprint' => '0C1D1761110D1E33C9006D1A5B1B332ED06426D3',
                'type' => 'RSA',
                'expires' => '2019-10-31 16:21:43',
                'key_created' => '2015-10-31 16:21:43',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'a1dd3f22-30b1-51f1-a22d-5aeeb00fee1e',
                'user_id' => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi6+SUBCADM9AAyXMBp32rNl4Hz2KqIsBYO7Fi9zFANzSM2jBCEuZVAE0pw
wVRgKOn3DAcis2RtUyd5p1n3v7okuYOEzATDxtRq33vmRX3U39Zr5UzeDD6rM3rd
T7RuaAjXhQA/O+ODw0eOATJf0sYftabVD6BRv93iKmOo1r8SEU56+GX8NrqcpD9R
p40wxcb0fRLfEie9vCex8VcusUaKXzqMARy9hctmFykoKl9cvvHkW0XYYnHWGr9G
KEZEytex2fpHNlkjVv6Ic1PUY+PxZyZ+6V0RgwMqztRdX8yrj07FoYgBi5+yJbBF
WI4LrYMAlQp+/vOsQuPkX2+6vI0RFHuNHehTABEBAAG0IFdhbmcgWGlhb3l1biA8
d2FuZ0BwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJYuvklAhsDBQsJCAcDBRUKCQgL
BRYCAwEAAh4BAheAAAoJEEdQAVv8B5UxId4H/0OD+N+I8+0go+FqWSNiLBKrFVDl
brqwrdcGMBFDWCCy95zQX2xxIpAhtLr0Dnn80ecu6UklzHsw8LbybsHf66v8daa8
Cr9yC5raNQfVGifdB77AkDuA8rjRP4cotAI3CsKlvV2YQRrCGWCRyIFI/u+UtOea
rdM3yv/JasLAHw8ExhDXwxhYkKgNwn3SZciFSaDLL0UwldBS93qi6pjYsmOVA1sq
OgT61Y2dvqgiNhIO5cmiVxlDNJcrHnCEcSepU5nmv0A72kNfmE4mOTnG4rtU3NwF
GdTntH26K5BfyQCDw9aHZklaGofuxVGeZ7ZwPyvSj5HZrPP1WgD7LnMHVJ25AQ0E
WLr5JQEIAKeyT+yEhWyjCQhrH5QUgieFUu6IV2npC+ErafvrtVnUeWLprWPBnDul
qoiTu7QbzEXT/7MbwlgticNq9QwINWsNSHKBvMjOXEFRhOltF4AXYTBJThnyFR/o
huGQezmjV4TpN8yXtpgiy21W+/XYjrY8PaIRM1Adc1hSmMSiuzLu+VtKIs+8tFFa
s8acWmskgKww2vHHJkxQ+ubWl5W1Vxa1Y/kOGjMc+qSHZ0v6jEnkUjaxLd0DW0hH
G8weEunPzBBSJKf7NstZ6c91+4LUCVoNqCYMkq3ymKRITN3vmJSfU+jIXNOd7OoA
+TP8Bsj/RS4opQ6kRrL3tficrX6QzcEAEQEAAYkBHwQYAQoACQUCWLr5JQIbDAAK
CRBHUAFb/AeVMbTTCADCdL5UyXBfCOp4D/T2L0uUL/2d37nyfOU7c2FCbVuviG+s
BAVypL7KaXNF6gp/FGidSUZDVIr+UzD9cv3s+7J0h5OeZZigyPlUbWaNy56CBJEr
YZH5u3+i6S9cc3udbP5DaD9EpASWUbsREi9t37O6hTS/OGBTGxH5KpFsVBIWBTL/
+XQPsd+YMdoBVka3yKSzkFMLRLHt3K0/miwXj7IK1g43mcYHUCrWxjjQFVjUZPBL
QiL18WhPkMO2lCHdGR9skSycwGDnt5WVtGnn0x7+JV0Yk8RpuopfOWo83+oBzq3c
erR4fuZF//Io+VikJe6mKufSflOpCDYfSOscdAA9
=q+Sl
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2047,
                'uid' => 'Wang Xiaoyun <wang@passbolt.com>',
                'key_id' => 'FC079531',
                'fingerprint' => '28C3C228F27C892A0583AF534750015BFC079531',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2017-03-04 17:28:05',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'b2581347-f1e0-5296-99af-baee3240dcf6',
                'user_id' => '8d038399-ecac-55b4-8ad3-b7f0650de2a2',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
w9E+yhN8gIA=
=qtKg
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4095,
                'uid' => 'Orna Berry <orna@passbolt.com>',
                'key_id' => '397EEAB6',
                'fingerprint' => 'E2E98DCC84FB41F69603C346EA62E0B3397EEAB6',
                'type' => 'RSA',
                'expires' => '2020-05-27 09:33:17',
                'key_created' => '2016-05-27 09:33:17',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'b3cdb8ae-c5ab-52e6-a394-8cd800bfaed6',
                'user_id' => 'c92a1885-1644-5bdb-8486-12d751b976ff',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi6+KABCACr10aZS+MSkQAuyducjLJ5Pb9rShxlmIXClTYry2RaoeOe7Pp3
jNcaNLtXNUhxI56AhGSuiRvnKC+yKTYWm4DmOEtLD4o1hEJ1SZSOKx4QLqCEW7dz
gK6+S9fOuZTpQuo78P5rAjtXo7PvI5LfSsZDjZZtlzkaB1i50bpr8u2uzVOC/2O+
TPK04iY+/EXF5kOt6qCgUYJibk1hnzAxQUZ6ORawNzoPAI5OPZwnwXlq1BB1YDSt
5vWgHJjhq+7V086CW8csgWBZwfQHXLH/Wt19NgTkmAkn4Znz/CA7b9fQivxFNZuU
UeS4IV8u8hX59rx0Vlfk2/abaeF+Hbyp+1C5ABEBAAG0I1RoZWxtYSBFc3RyaW4g
PHRoZWxtYUBwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJYuvigAhsDBQsJCAcDBRUK
CQgLBRYCAwEAAh4BAheAAAoJEKcRoHMXh+VNqtYH/i+pb0FDVEtO01HbK05CMQtP
1boWBnO5vv50zLHmAbWRcUTZoQ7KR2lOAhWAH8mAaqL5TEr85haU0Qa9MMRfm8x3
p344cxJrZkViXBxqTCUSaHj53v2npDlkD6o41yHazzhIdZgWKWjM4zdvhNM7Swop
THswRjXp2S/QwzmiFRhwR6rgAEBAxrU4lMxVsCHiam/Hy+U0mJSK3jN0/D5FOcMU
laFaVSC23Gdh6K4ho1dmWhUczMp8avgaqP4M+rk6/8AxKrprdBykkHvvvKFIcWDp
zYdyYYzXq8w4evCRDNpvjEA6CQZIwYvtIsZ3aIFjbbN2UpQuDwJJ4uzAzhE73lK5
AQ0EWLr4oAEIALzPqR1QG576wp/P+uDvrJ0EXqGD4rEG+2F3xclfkWmpRkCKQBWl
Kc40j4J0aNnPbRwclIa19C6GUNBaTIHEQ73tYhdREwku+7h5YO64XWrPH6bHLDCK
+dBpwf42bcCTQzfL5P+M35UGDai32f361xzOwbsXEJDBSkN+5MJlumN/LyPN/Vb0
xAMpx1uBb3qVgIUfq/UacjTJZjc8GjpqNm4vRw6M4/BVoBKPzyfUB5sxMkQum7yr
I+VO0gP+Md6FO5lp4tsXpSR0RSCd1roHdH7+sjy8OG/xXPG+05xitO2Ji3eumBM0
vhK4iynQCU1FHkhC1rzVVICr7UkXjvdJtsUAEQEAAYkBHwQYAQoACQUCWLr4oAIb
DAAKCRCnEaBzF4flTVM/B/9DbHQuNH7t+dKurPFHoJNTXIieMKzGSeoeRIr1R8ap
P/G6+xT4pV6UobPBH70SxzT8mMO591hnuRJ7qRA1Gpmq3EV+hSHD7SmczgjLbC2m
KgvL0uHHtV/a8qDh3CLDrW2/jsIHKGfDHkUP71+bC8wJ6mmRysP7LLJRcy8mtQuS
ZDFzwL3PcSJHaBjjxsk59FCv7zEyVrOSNwq6NOuYNLf6VDp6LSGsGPm4Bjb0odcp
lw8+0yy1CqS6XDpWdxSpAadsy3Fwv2pWZhHcaEw/HK4ppQUy3Rb5CYFoDnxAyZAL
wlPmpnNFp1iLbkFM2+/gVSR+9Ty2DNFPgRAov58Cb+oQ
=eX9A
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2046,
                'uid' => 'Thelma Estrin <thelma@passbolt.com>',
                'key_id' => '1787E54D',
                'fingerprint' => '522E59EAC81C5D8470C45077A711A0731787E54D',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2017-03-04 17:25:52',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'b4ac2e2f-2764-51e6-82c9-2066943733ff',
                'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
gJf4InpB2dxOL4K2Z7c=
=W+0N
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4094,
                'uid' => 'Betty Holberton <betty@passbolt.com>',
                'key_id' => 'E61D7009',
                'fingerprint' => 'A754860C3ADE5AB04599025ED3F1FE4BE61D7009',
                'type' => 'RSA',
                'expires' => '2019-07-02 11:28:17',
                'key_created' => '2015-07-02 11:28:17',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'c4834439-b383-58bc-8386-a2e475d85318',
                'user_id' => 'e1ebc592-b90d-5e22-9f40-50e52911673b',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
1RIzS4UqhfIPH7vdEAQ=
=GnKL
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4096,
                'uid' => 'Marlyn Wescoff <marlyn@passbolt.com>',
                'key_id' => '0735B1C0',
                'fingerprint' => 'E4400EE5E49B86B96FB7D7F48BB0AD490735B1C0',
                'type' => 'RSA',
                'expires' => '2019-07-03 10:58:34',
                'key_created' => '2015-07-03 10:58:34',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'cf85775a-cfb0-5ad5-90b2-ecfde355f7de',
                'user_id' => '0da907bd-5c57-5acc-ba39-c6ebe091f613',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
lHRL/q8pHZye
=2YrD
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4096,
                'uid' => 'Jean Bartik <jean@passbolt.com>',
                'key_id' => '24AA1193',
                'fingerprint' => '8F758E3BDD8445361A8A6AD073BAC28524AA1193',
                'type' => 'RSA',
                'expires' => '2019-07-03 10:54:25',
                'key_created' => '2015-07-03 10:54:25',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'd7c9f849-71ba-5940-a3ca-ab26472c06fb',
                'user_id' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
a09IIZln6LLZOig=
=jibv
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4095,
                'uid' => 'Grace Hopper <grace@passbolt.com>',
                'key_id' => '9BC6A569',
                'fingerprint' => '63452C7A0AE6FAE8C8C309640BD9E2409BC6A569',
                'type' => 'RSA',
                'expires' => '2019-07-03 10:49:21',
                'key_created' => '2015-07-03 10:49:21',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'da315c73-bf77-5aa6-8f10-faa47a579f15',
                'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
b7c81OKzWqN3kkNCTtycnemmZRE=
=f88E
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4095,
                'uid' => 'Kathleen Antonelli <kathleen@passbolt.com>',
                'key_id' => 'A73AE279',
                'fingerprint' => '14D07AFFDE916BC904F17AFB4D203642A73AE279',
                'type' => 'RSA',
                'expires' => '2019-07-03 10:55:55',
                'key_created' => '2015-07-03 10:55:55',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'e0a978e1-fa74-5cf1-803d-25818671e886',
                'user_id' => 'f7e9754a-2f64-5cdd-8ba2-178b33383505',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi69+MBCAC01FHt73wQwudYIPpD6XUtJXOoRWIMnLYFiBy4Y4VSpzySrtr4
TDMmcJFclLHt8eDGPanxMrIZjqVyjy7xPXN5PUpwOZjuqjvBofiVikkDP/wHcyUC
IvAbRw7IQvGF9hAMKEbBL9Yi4+31ZoxNOYbHrRxhW2gzUIPCo5GbKw+aRKCpEd3E
Y/Pp0biqpnfinumBMSrSDFCHg4/xtxr0cmrQrZEmlBwUiN68SsCXtayyg4zkPKNh
5I9BSY3esIqkHedzbZw/uoWBe1a1XEUEDbtme/jxGbphs51eaYJ++y8hBdJXsQ2N
ovl/U6RVQZejtD5RSpeFBIGmn75ch4XPcneNABEBAAG0G1BpbmcgRnUgPHBpbmdA
cGFzc2JvbHQuY29tPokBNwQTAQoAIQUCWLr34wIbAwULCQgHAwUVCgkICwUWAgMB
AAIeAQIXgAAKCRCs5l4WutVKivLEB/4j7cJpZTjtjjUTUNPoqfPPII1JnXk1seMc
fuHE/NDuiHHNhd+7GdbnKJzD39yIAHbecLJgFaCJX0/BqSkWSutihllUi5iq5YyF
0voJeSJWittUOmqV9MmWTNppe8RpPYnoB5eYqz1AvP+0ZyHc3Xn9yHFWjRvmeASX
tO+badiCo7W2hjRrD8K8UxL6stUVAmtLUgTb81LD1E9NKDMKlPH+GGOXdWuaBGOx
5YPdaLaNoDYv679c9JSgPqQ9Y6KsgQOQ/H74B+cZyqmjzB1/uPfVElhyI2q9tz/g
AsVNNGIoe0VOx4cY4veegMleHBFlTGKhi0gMQZF1RwbGs1e5BSRLuQENBFi69+MB
CADWwFXTvotgQn18YVUj0D59i4jJxaI3r2wbEinsz0hzEvN3wjCyRVuuVS67LZuW
eX18hKgO74hAdiiFq1nKfgDsRSywyjcyIVLP97AvUQYFqDZ4aNNWQBrXEyTTPlqz
3ujbXVPJntAArrkpTdl8i+oVmAruz/cSqX2YRmXWm/GWNRU0M3ndrNT6qCi/+1yQ
L2sYeYKG89ujQRbX/H0xFmGDgHEuPgLrc9CrpbcF24FivLW/Sh3Ux6VkORKG9Cku
h878xX8+CMfRgg4rSdFAmnpUeb2n6w03EHJsQKUwlaxYmUZoOIeU95i7QTvHfLnx
8c5fniAEH2CEy+4hP9BHdcfbABEBAAGJAR8EGAEKAAkFAli69+MCGwwACgkQrOZe
FrrVSopDOAf/dbSB9A+o3JD2UEJmO2E8RuxXFIYSH5/veH0yLOLGSnfg3OHcwAaZ
EvxDmCIetr1EvZQoJV2w63l4szm554cIRN0E9quISvqgKMMaE82OUFAhoOBClQEp
46RxmUWP+FTiqIxUdIdVsmOJ8LRYk+NobG9JOFUMQtTxUSiE9AE1TWFUu2/ZdKA2
BAP4d5fxuGlCGwXxJg42p9cw5xCcC8GTmvqemO8iVlkLqRrihMjHYW3R9DBOVUAe
3ZdBR0McqR6ZhvSARWBQG80VjlvMt8/7SzDdeeXgBlCo0US81hAa+Yxmk8yiyEYC
cOv3QJcgi02rfOMbtFrfHqeEPITCru9nZg==
=Aszm
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2046,
                'uid' => 'Ping Fu <ping@passbolt.com>',
                'key_id' => 'BAD54A8A',
                'fingerprint' => 'AABF40FA29BA54073E8BB956ACE65E16BAD54A8A',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2017-03-04 17:22:43',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'e152cd5e-f4fb-593c-aebc-9b06a434cb39',
                'user_id' => 'e7fa0375-61df-5dbc-9e42-e0d363bd0ecf',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFdFk8gBCADsd3uX1zydSNTc3V1G74qXiMTrDi30X4O5SBaFM/UUG7mQcu34
HHqWt6I1YeH87Z7Dxq3hetvFbaWR8ZfvlPAruXIzFHzJEZzlCLU5YnRBFM5gvGkJ
VbeKSKTOr+8oVeXnio+Z44T+UtBxXS+2G7wLYYTNd2UaM2WsiJaATeFiD9jZq22h
TakgoOWKIpUrg4eKD7K8Iu+qm2l4btm7QLRyCxhP4x+d1MtwcXArmMXenUvnr/2a
6MDwfddmIcf23I216sJmNjEDWgWHjHoQibs3tx2pBKzY/GI0y0NzhPZCvTdbNqTZ
XAsOfDHm9OuziuNRCgIrx6WtekGB+iZkon6bABEBAAG0IE5hbmN5IE5hbmN5IDxu
YW5jeUBwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJXRZPIAhsDBQsJCAcDBRUKCQgL
BRYCAwEAAh4BAheAAAoJEA7FR62ef6FSlucIAJzL2Jzzka2FWpSPpC0ijp2uhugZ
GkUpbFqBTx9twO992/BvAJeyU6Kfcss0obqNZP1c5sLl2RMvc35WtpBvjkedSUEQ
poET0FGhNUi2ZyqiU8bdbbm8EnDuLv0GG06x2ZSuQ4AXE+JKn8H4saFnPmX2h8a+
YfhKbtS01msQmMviVaF1ZpxTugTqgFYU6wp39O7IR6DMmeKgSwElW8Gd/gJMWx8H
7pizEq5SQCFzR0soBpIOHg2QMDAIWS91zAHdQQoiaI5wAlAm4IeIIY90KaurRQC0
sTziZcdrp0m65FGxZ6790nGhhqLc7u8N69CcxKNkipong4yKkFqyIErFv7e5AQ0E
V0WTyAEIANdrlla22hZ1rg8wFPgeXKJ06JJRS8SRt9WUXLAuIuO1Xqi5uRKXpril
gcC6t6QuipRg0ypwICcBEI2Iem9hgksKG82GVz0LSWSENAF35EG0eE8fFtssBiop
avvLKjiJa+tm4hqDYPHP1SvCf3Wl1PKNTjwuXwH2gX4bOpGF86rWUIriIgmfVVsH
Fy/A0pQoynuYwKr+C+i4CtmZFl0F/WTahVhVTi+KqxMU4z8a90b4gG52ZWGQ0LAS
OahnyHXnicPQQBm80KdC1Exw6Z9QYMXj+ZMWSUvmKb/58S0PBXRapN2KDyhHovvd
ZGHazOFTLnwZvyCmsReb8SfpyE0dtC0AEQEAAYkBHwQYAQoACQUCV0WTyAIbDAAK
CRAOxUetnn+hUqjUB/9EqjSSK789BAFVnY5fymq/vMnCBkE4U/wEVp3/4/e7c6Vf
dyLmT0ULORfOVOyRPZCaohs3+2mUZJcySRHrK0SCI33H7dWXTAj8wYTE+neznZrW
hI/7COYcdEzRdYmFEe1qRJvmXWiSW6C6TjARZTdF7ScBzGhzRUmcyr7h9KcqJ2N/
bSYDlRHigAbDq57S1aa+cN92RHyAvQkJ8S1TBF3/uqSFz41hY4GekegcEg9h5ATl
X9fv56KCnrIdY4NZx8iYqEmjMOZ/FRz9fpwxWapqn+V7eQl7mpSybNEt3gevuTmc
/RFZsK5btBeTdj8jzgg83/jvsasJnTLgAoOdSv2j
=1k/p
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 2048,
                'uid' => 'Nancy Nancy <nancy@passbolt.com>',
                'key_id' => '9E7FA152',
                'fingerprint' => '459B102D43F683E7EFC523610EC547AD9E7FA152',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2016-05-25 12:00:08',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'e290ed74-a470-5903-8e80-ee25c16fe47f',
                'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
VhJINUw1ojbw3d8=
=5xjf
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4096,
                'uid' => 'Edith Clarke <edith@passbolt.com>',
                'key_id' => '9E67396C',
                'fingerprint' => 'D5FDE007B7B4B9816ECE25F61D67BAA69E67396C',
                'type' => 'RSA',
                'expires' => '2019-07-03 10:47:27',
                'key_created' => '2015-07-03 10:47:27',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'e6ed0cb7-b31d-5b82-85fa-41d74abb6cd0',
                'user_id' => '610b4c1c-3c08-5451-a163-5b2adba8a5cd',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
eUNE1fxScsE6UBlfdvKJapc=
=s/2R
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4096,
                'uid' => 'Sofia Kovalevskaya <sofia@passbolt.com>',
                'key_id' => '08D8B763',
                'fingerprint' => '252B91CB28A96C6D67E8FC139020576F08D8B763',
                'type' => 'RSA',
                'expires' => '2020-05-27 09:34:04',
                'key_created' => '2016-05-27 09:34:04',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'f0df9afb-2f0a-5273-aa1e-1f625f2920a0',
                'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
LhhailjVzD+qRWUEbzscp1Ih
=WyEV
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4095,
                'uid' => 'Dame \'Steve\' Shirley <dame@passbolt.com>',
                'key_id' => '959CF1D0',
                'fingerprint' => '03E6535C52AFD7544C555829DD8E26DB959CF1D0',
                'type' => 'RSA',
                'expires' => '2019-07-02 11:58:41',
                'key_created' => '2015-07-02 11:58:41',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'f2b76b3a-892a-5b4d-9b27-2c051d4d6f4a',
                'user_id' => '5302c3cb-5d33-53b1-82cd-57df36e13acc',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi6+NoBCADDDLnrYnzqJJCCPRtsMy27QNnb13eD69xv15D5vHWzXjl0HoNb
FpxRQ6dBIsfkaxfmEPb8wC8ExGd4/IDB+69wMOy8x387kLEBDHFlwzC9ocRlLBKc
M7Fny8vLluh8ybrzuvqYreJUdBhzQPyYtVlOniU6P+jxnGOItp5Kcuv7fEMazVBo
oZj84ddo3yUuzq2Dc1t+lX6buJ3gMMalAMJSBjuyRabGy1EGod5o33FOtVdncs4D
x1xxAllB85a8voyfju30Xd8fhzGvfSg089D5Zwn6Ceb/WT6wZGdYbQh1pQTQZ2Ja
Lu3FQzpVh9QHLUmgV9llWZkyo7YArUlzGjl5ABEBAAG0I1Vyc3VsYSBNYXJ0aW4g
PHVyc3VsYUBwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJYuvjaAhsDBQsJCAcDBRUK
CQgLBRYCAwEAAh4BAheAAAoJEEA+DkoSiUh0lDsH/jpRKnBrNjy0U/c4zoM71S+x
icFdZ5YONLhN+/vNVEaVhx2w5FZYLUufgR2F2SwsvZ8vmCztPQyArUpSz0QbhTXb
PdTwMdtjJt1xqOgo9O07l904KMSDnpaWAfTp0LjMPOyVAdcM1+NVG6j8TC5547Vh
vlB/WSoge0+dhVyzmkDO4gQA6CPQiJfzuiItYkuX0FWUU7MrkOjL72zUot1uHuZH
GNGBYDtLcONzwauMdfblcZYH8YMW/IXCX0EEY6U8gRC8af0ruanWeY25W0/sJ/H7
MHTQ5/2rAOhRLQb7WiK/nNjGvLwwUiRNwzVZfXWRBeX7hgSvJFZb1QaIHQUCINW5
AQ0EWLr42gEIAMILiYSI8UVcU8LTuRSi2l7ZIMVymTAF1RBcMnaVW4rDIdY/FyNb
4ZoYmcxP9fkbNAedqmrloNJx0/ZnT6CYl1Fu7KQRM8kuunuD7xi3/0Xwp3OMMAns
mUUHkY4GKMiDeZ1+1QGVMN8ACRJioAmK0aTHSdFhx0jOfU+HDwG7/Hj8qo8HmTiE
sZ6SWi8wp2kdItdc+pEt4JO1nxwfHxXdBVgPJaxkWuCyZoEeuaoLz2N8YWbLTTy7
ElHcmcHAXC3noRd0p80Nvb+109H8lwv3pFkzFcrqsdM2y6+dYjtI/9SCDtBSZghX
CelFyDJ0msVfujGBoa1HU8RsivAR64sMBlcAEQEAAYkBHwQYAQoACQUCWLr42gIb
DAAKCRBAPg5KEolIdMSqCADDBO/i8DiNbsKtJwHM9y7dXDPpb8W/Hu3S4EIx68V7
snoUn6E9S/sOdqubq5kuFk+S/a1sE84iq8T/kDBT8i3d2e6LGXcYdxQXRcUEKawI
f8VYyA4J8UKXPLLo1J5A1MN/xc8Duuw7mdLDnbXEjjSFG7G7BBYNC7UBHmdDxOK2
+yt46o8ogqI59iREelIiVg+n1gU1mnhbFIdrhonv1bjr7Fu8nGz/iLynAMlxV4TF
Om211avkc+sUYjZF8k3zWGsNOEz3Cng3kaUFRZ0rmqVRZTRcAJuU1k7M9eo6I2j4
C1bPERQcYXABssLYtGmFVdoC3DLBcCY+tjrJRz7DXtpo
=Z4Om
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2046,
                'uid' => 'Ursula Martin <ursula@passbolt.com>',
                'key_id' => '12894874',
                'fingerprint' => 'DD6A88103741A623F8AB8F43403E0E4A12894874',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2017-03-04 17:26:50',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
            [
                'id' => 'fc8d6501-9391-5cd4-ad17-f46df3443d6f',
                'user_id' => '6aabffc9-f788-58f8-9bc9-f4c102ad2f53',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFRso0cBCAC+J/b4LoML0L9/xlIs3/TZKC9CSVTQ2xljs3hdawvGi/+p210M
doXev6optgaDPj0q61HaCR1XhrCa7gK9jEC54M91LwrRzm5nBT9Fez/wezXn2I0v
56RIAn42k3OcDwWUDdPenzZS+/4/efJPyb/XO7sZMiD+OjjpXwNNu9ezqSvNZ1uo
/VcMHBTkQ0NqETO5Yt5KX9JkrKP2Q0BR2BVHGHp7K/PJiWnN+T8dTFr6RsiZsVWs
dD/5IPSkNAsi8E8fguuWecQtMftled/36QjlaXYgZ/U1kVi2mDUebd6oxTvB85fm
pCvIekFRNqs6TAd4de+pDBsbYY+vsE1tCsxvABEBAAG0JFBhc3Nib2x0IFBHUCA8
cGFzc2JvbHRAcGFzc2JvbHQuY29tPokBPQQTAQoAJwUCVGyjRwIbAwUJB4YfgAUL
CQgHAwUVCgkICwUWAgMBAAIeAQIXgAAKCRBPgZQCX9LZLAk6CACop+n6hgaCrFWU
m5EaT2+XBBw9rEbcISCH8Zeh2Xk1RmLOiTLSYRka8qnUcEBbSq8EOoJsfNdWEK8d
QwhearHZjRCUjrQMPsMwwKhKrkG7RR7VI+hN+7H7Joyq3UDE7S+55vvWd7hSZbPl
buhPWBirviN1Lovk2tZbI7ClW1+Cx9uK3lad1LywlPsxkCKbRfDcWrnLFKk1UnYi
229ZXCYjuJbzfPRWx039nVVt6IoOZnLCil5G9d5AFt5Ro7WFdormTsfP+EehLI7q
szrEVD2ZQgn+rSF8P97DLABDa28+JfTsnivVQn5cyLR6x+XTJp96SSprm5nY0C3+
ybog/dDFuQENBFRso0cBCAC50ryBhhesYxrJEPDvlK8R0E8zCxv7I6fXXgORNyAW
PAsZBUsaQizTUsP9VpO6Y0gOPGxvcGP9xSc+01n1stM9S7/+utCfm8yD4UtP9Ric
mkq/T/w/l9iLFypo6al47HW28mQlMvbUWSkMoK9JXRpB2c2VPmN8UXVQX4cQ++ad
YQNnRgSo3n+VdvIKgSW3rkcQIriGX3P79cciqAA/NzkivNyZSQaVBLJioO+kDkYu
Q+oIstvEusmHIon0Ltggi8B6LM5vAQpBRwQ9dfUgAbpQpfzm8VUkCGmsUr5hnOO3
tmaWOTKZcpXiF5+rW2NrqiAhRhm44s+JipmTE++u/6X9ABEBAAGJASUEGAEKAA8F
AlRso0cCGwwFCQeGH4AACgkQT4GUAl/S2Sx2LQgAoXOxfA5pOCm9UP2f2pQA7hyv
DEppROxkBLVcnZdpVFw4yrVQh/IWHSxcX0rcrTPlBjjFpTos+ACOZ5EKSRCHjIqF
biraG5/2YjKa5cqc7z/W9bSuhmWizPBpXlQk6MohG6jXlw7OyVosisbHGobFa5CW
hF+Kc8tb0mvk9vmqn/eDYnGYcSftapyGB3lq7w4qtKzlvn2g2FlnxJCdnrG3zGtO
Kqusl1GcnrNFuDDtDwZS1G+3T8Y8ZH8tRnTwrSeO3I7hw/cdzCEDg4isqFw371vz
UghWsISL244Umc6ZmTufAs+7/6sNNzFAb5SzwVmpLla1x3jth4bwLcJTGFq/vw==
=GG/Z
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 2048,
                'uid' => 'Passbolt PGP <passbolt@passbolt.com>',
                'key_id' => '5FD2D92C',
                'fingerprint' => '120F87DDE5A438DE89826D464F8194025FD2D92C',
                'type' => 'RSA',
                'expires' => '2018-11-19 14:03:51',
                'key_created' => '2014-11-19 14:03:51',
                'deleted' => false,
                'created' => '2018-05-01 05:25:35',
                'modified' => '2018-05-01 05:25:35'
            ],
        ];
        parent::init();
    }
}
