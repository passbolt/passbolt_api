<?php
/**
 * Secret Fixture
 */
class SecretFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'resource_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'data' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '020810d8-6793-4410-afb9-370c58aa7f42',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+L98+o5B7yglzK1kKvArwhdwN+Wy9mLt9P+nxsDGzSNI6
bOWMzliG6h9CJ8hiR1ci8Z9auqjAYy5RCayhkZsyhPP/vmRW5BcFEZ4ELPNaykBM
mDoFNwl97gwebXfb8z+ba0v3P6oku+RPqmEqUn342qoNqsLoFCJQV9lyq1ffOtAL
YJBJIRbykZAHVfpNRNsVtlblXWWcL+vFrxgyIBqwStFsdV6zDaSk9rp8LEcjuZxL
DF//sy3j/0MVa7M5Oa2Lx2KPRpnn9jOxYyeX4MhvUr+W62oaAg/c2mYtrErrJK79
nQ8XtcRueaDkON5DYCzKFkQ20gfSLjhPPiVEWmJtEjVIRvwlGninTqhFuMiunkij
Xu0QAK/XIxuFaPs2Yr7m52sQSP10j1a93zs/52x7lhGAcrVHoxxWMsiHhFONY18f
gHQdwdZi2khDrFkGEt/8f3RHXj1QXaVUhxNIuFqmcnccD4+/O6/TCFJ6kSRu23ZV
YWRp4T9RSEsdG1n/MFmH0deYkGeZiocRrY+sC6BvgERxtsmAaa36SZKbq3WgKka5
mGXNSWTYs7tjVixK3Xl02CHf3IsokJj1oSnAdYxAUOnwgCp3ne6sGzDr/XQW/K5n
tk8pUD4vdtYsowb7jfiOMfaIUIf44arfHKfVTsXaisel9z19eqQaoCKyaG1fqtLS
QgFEYzrCUfZQ+f9+PHR0HJTW4sloX4IBkgkNA4eN+ES1Eyjh2Q/BRrZvUYV/4UxZ
VHuFUatlcv7UWs9VsgMUQfHcaA==
=ZtU7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '037ec065-d463-437d-aa6d-92e23cbd7496',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAx339XytCk+xEh1XmCKTYpm94XPKVVZUsaQhMZvsJ49gq
5NM8dyVfbvKT888BtWnksrDdKW8fGvhwLaBNyLC2HCI2ExeamY5HpMr6hszZkJxF
WfPcF8pYh2T2VKHw/h/ZFo1OAs+YNaY6kkr/Ner9X7CZ11KcEUb+MApRTRqP+CpL
UxTu21zG9TE6/0Xcr1AkRNKG4sPWIz1PKu6EJXpjrKmOzlb9Dapgh1+e7Lzsg0Rt
9ZWQflzvrMLeOjlH8asaEol1O+S75atAgajv9aIMf0cGPEUPrQBez2wkJIhOn8Jq
63jEwQtPg3GVllxGom9viZpdBg8ypDx/lTJ0LnScG3uXcsZnUQFCr0Cak6rV856R
zWB8S+x/Ji3Ye0cX35QfGp1DfntgTsBO9V/xoV9AXap/4AnIrHcnBTDqvxcaqaXV
EkcttW9+d+aiSzTimUu1gSrO2rpH9y06lNVYF5QRbrK3EULRZncUFFFt6rzWiABO
GA8vy2MOxLo9VgcdmhkeD5PQTGXbK8z8mnjHf4pH3e86YBXucezwkQLT5idAHZ8Q
m0Bbw7DaxhzJscoNgubjChjUyhTiEXoIxp4erCQkxHRrbi5IkYlLebsFAGDuBtVp
AXCVhddvgpx/GKRCoByBnjgbnXB5JTQGd4tUKwTP/WMvOfa14dncRympefVUCpLS
QwGVI99ozDvlzPMZWc9WlJK0z/MJhne4DCcBMn5MliDtRQQ6oC6fbZmDhobeV/NE
XgoFDKEgr8Fbn3fv/YJUmGXpYao=
=zd1b
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '05526122-b2e5-44f4-a62e-34e977500b66',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+M8EOMmWiCdtulAyzEhZWaQ18xxOcrCvGW3dKRuLfx6Vj
ii8itwSTos50gTK9egC8P5/tTsBDX3j+WJp1wAUZfF6ea673wKQNZVL/02c7WWr3
7kyOoep9Ay4kclD4phbJLvL8bpZdLMzft9x62B08bJn+KgDo7tWj5CrKejc9wHTr
Vu0uNDR/tL3iagTB5hhkDAt8xxx6pjGSHVEl3qcmM5MTCHbLPQxBjIiulS6UjpGc
/W0O77QrB8yT1HCUdE7H+zV4mly+f8zifHFgcgESsqyBGBFFnHF7dxvvACgMX26y
B1pG0wTgrU2b+W9SQkCOVqOXEiR7dNPsVLoU6CDb5ZYqZQyU7i8ILJs+F+L6yUi5
qdZxcHXfhskdEr5YbZE+WOaI6d5YromxhaYyQT/5/uHuc+nWVq0XRL2Mgp8TugQh
btmKnkPnRG/SuhsmAPduTWlq4iQAcIDhvFPzeyhtfPgxD68ejGxdc0p+JH8Xmegi
Hod+eM6NRPrLobfMB4gB+DTuQ4ae5iY3aJCTCCHv/Y0TfEIKebDs9JFUjwEHDl+h
WfcVgJsc+R+HPKpztI9W5FdhqvBnnAPw2OVZTILX2WjqNjwXjhIUpm4o74LcnkGD
q763Ys7ScUHEq5ePVcpzm8JomN9Uee5nFtxmE8kQxBoOQ8h95FZUVwBL0Y5XsQnS
QQGBwESKx8gj12wRTEIYgQIj7vF7ahoOATIdSouSkCUmTdww5//fFaD2091F6HHJ
x9WZu/udUxXp45ail7duN1J7
=aHUC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0a59bef0-294d-421a-ac80-70a661a4f147',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAuFe4ZPRM/EFTlsBgzKdRypNDryz3fZ3Y6lt7Dyim8DLH
SPz6oe8hjmVqYwTpHaWPntmoH9bYl+nrxpWVpUaVBqvjFpE7NfxekDkuovw29rpP
H+B8w6ho3G9MZgDBlDjwTZwWXBbO4M7JcqEhpvSpJpbia3Ln0DTSVsanq0IvcVAi
QI6RwL2PWCJpFZzaKFo70dDhr+RIsobkVzk75RCcqAJTQY+SHa882/EVmiOMZGev
tyH6kR5riGXYmBCfg4Qu4sW/hD73I7JvlzhcBaYLH878YiUgNhD64TJYtXmS5aKg
ICIPSFt9OnTsHU4TeitcZEhhd/7DLmTetxJEjIKN6KJGVGxDwlqPIrBXLdevnTAt
U9Ipt7LWOX8edR4rUZNWNpbEfpcVWMiEyoJvgbTs43AgLYSvx5BNGwlWu+Yv0Hwv
bBaVa5Xe0eVlywRhlzSbQKdB1CWVEHlCFfDqZGnTke1WykGFGELriF6sQag/3cCV
FEhJyaHhTxLAPJQhJ3pqOahDhR0cqEoFKOgEPIReTRBbfMlhot9KJGE3KwoVMX7I
41IooNo9bdo9UR1F8G2y1X7BvtsMcBR2UY8XcVzyfkua4u12WG7z5qM8bQb96Tne
X/1jsPrtvv4ZyRrcwnPAe54BBO6DFsIFy98MkrwroEzFiKmNGd8jibaWHGtaQJ7S
QwGdUUWt7OFm0SjT/jHiYgYkZEOA3F5eS4FHZPOk0lPufIiLy9FZaBWhIbM4mQ2E
fstuiJU9LEQ5R2s52y10R8Xveg0=
=64cI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0edf8966-ffbd-4428-afc1-c8b75189cd2e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAyVhsc5NwFLiy4ab3bKDQQwR490EbnObmxK0U7r4K8vzF
5sYVdmjafL00w3dMeiVHIx+1ksNUuKyU0afrpQGcFlCvtbrBaPkotbmYps+yF02+
LkZtpDe/RfBz4IpwLXVE+6WnaEnJqByWQzZ9oKilmdkLydiKhB1Fiyl5/XyT9FAZ
a+GI3QWuOAaG5p9cxCLG+qywD80QyINaHgxeaqIieAhZ1uTi3LjqQy08Nyw7xXRm
OuRWrH/uEAK0mcN1htkJzrneOronGMd469qtfzhvH89ajPFnGHagSigSYpJqX2+z
1Lzv2E6PnIKEjWqv8BsIL9OMVMi2p1j6LUejQkJTDuNNw/OcXfzlrBeuwQI8ha9y
BADc0Qo860k9F1PFzzFkKC6khQOhQxeNyaofZlLs8hYwRTBXfyT/ARABUXH3x8Yo
8HPQAv2OQ7vBLftW/jM8TMSYzsoNHjInHLq3D4gVdthJFHR4bvbqZXcecBCPm1cQ
L7EelJqVZ8zJEIbeb5M4yNKye2oUQJjyhlkm7UbGcJ1GnpJRdJde2jrCNJMFO4YL
6oVHRPDk5CIlegLbsamUCRHIsXImpYyisktUI91n2EGiqETnZLhrErf1+NW75xT+
u9pBauyeppHLZIuxWJheYAwAzKdBFc+G92Ksi2NuHeOQ5dwmaasH0U5NQDsQOJ3S
PQE4C5SMY79fd8Uic6pT8CD8iYwj09fpG3u4hIglOF/y4WFuw2Todjt6k01d/IGN
mZa02k8L4UMkpIFUW0s=
=7uNz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '154ec505-9bfa-4a28-a1de-3511b31e3497',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//Wd1XgKylDM+r2MGRZYlPUhpzHA7xk6pwbMKoiQw6bPu+
QX7SrG5BFUKYsncSL+6awA+w/tTwbcL3lpxW3y6tnZ6vATwv4+LY5JNF6egzeXYq
mKu0qGSchOIWqiJrEwUZHkwfI643imPLakLrC4JS0BLzX8/g8x0QEh2VEkzCLEyA
1r9Byk3WDkfyPjJcb4GIJgbGH6cOvBjnuv5GQwjTc/KltMUo4kysxAwl082WyTfn
lfdgUhPcTERjn1NnkeT53cKcyf8YHog0h99piUW43ryrirzgM4F7WrLEzWR365rU
V8wyck69UcvWSU0jDtjmcXVe+oSznpfsPxv3/49F3zJSCaamdRl+MLfRoLT8dMyS
uFcEX4mOCErZeY/MkUGitOTz+aSwKVoDfRr+g5Y46OVkXh5ZmTURUxzMUkvMPd3O
M5D4q5x4ggQZemLQF+jaZhYcj920yGTOKOh+BkpHnsqenzXEGX21mIDqm6CNJrkH
UD+KqqvhQvgqKRkIZwZceSeuVbwC+igRcU6E03jELA6mH6hfJaeHilbqW7Wn6ADE
zoh289VkXxS3k6A+sdl+a9e16XU2+IMtvfRG+L3Gd95zzOvknuLErkkri3Cjlmnd
4Csu4gPuuz8IKudKhrcumVHMw+RYmKWZ/EbPsRwzas914ntwpR/OvEtarBH4hNvS
QAGqbK1y4y1+5rwt7DNwJOmvNNNBrgxWcmY6XpY/0JmGxYoBqwqFO7MUtAaOw5mk
lyPe5xoTcVT4IS2nwOsJASA=
=i5KI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '18a810dc-a9ca-48b5-a189-71dbd8f9ac93',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+KJHlkoo3Nsl7O/we5BhKfESYyKI+Ip0kmP+fNskJE2iL
dUVEPfBrU2D4IYaOFXyPsSyGmLuEMAb2k7lsVk57tC67nApHODVHDkFwZU0UwmMV
Him233xth7p5cve8XsP4y/daxPg8ezKfsRpSS/j/F+iNKSDwddb9MHBJ1yPhr+ce
ObQssYDK8BW5zSXkq4nrLoHJAr13gfuxtoXYjLS2ThMLIwvVR8c9/LMdp29p5Q95
fQQfCh8xzjUOHLM6uMVTRO4mFweCrPVbYMIL/WupmyTad1ZN3d6aw9GhCv7E/RwE
I3rw9iBDAdSpqJDV/Hj8b+qgOMYlbLSJGgcfhcnqsvRfwpoxwWQl2xhA1waphTqf
0k0qyMNpjeZXQsRqouapr6ndb4mIeJthN7+bmy6sM+EneJG4OyHirts+aeJGCnep
FxdArLje7JkxXMLI30Da8gTAVKnbf6WuLov01Rp16S0MIyfM214AKYtoqP36WXlG
mZx3adglXuPfS0CFw7dZk3VRD721RKinNrJ/zbPkr5oCjr9aaDEgM92GAkqShZls
Wl6fkxUI28lHJkYXqPDezrxecHKA26HqISUUkejmuNyjWvUDZYaaIjdYVwtVPZsF
dFZ03zOpuQd9bxSftnYshzCYglLo1muUPpMUG5AHwNTANCts11QXNpAgeAeoTwjS
QAFOhopdsteo6kuzA9ZVXR9YJVXryL1oe3nvNe+atyvK5kICQrY/Gws9du+3bR8d
05y9qvnKxZryE5ldcDJ4akM=
=Yv96
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '191a4d61-68ce-43ad-a297-bc05c99c3a68',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQILA1P90Qk1JHA+AQ/2JJRXbKr+aBjfRjcUTgGgqldeR+14xYuu+0G5ga+boouI
RL1ePRtQgjWl57vGUEiWzN2YA8ZEbQGyBNnvBn/kxC6ZF/2O8WSQ3kUc9VE3Pi9C
Nbiox2WtXF0SY+1LCg6dTPl9dfZ32D63l24Pp7DC5ABMtpZ8smGcUZ9PdS+jC6Tv
Qs5amTlJg5u+vKM/gMdSBYBfvE12OkZMH3niBn3j7j87brdxd8JGWCiMF5tX/AIM
LPGzmFfjr5lT1KyreLXzerm3aIP/xNeUm6Yux95HX7Qv7WK+RwAMa0jzVZZ+dDYh
Yei724VfJ0qlYh5yQ2P/Bfny/sz8YJZ6xdw5p03N/o2UKq53l9WOokk71vD3ddbD
hE1XCzEzVaPHX0L0/pv1WFy5uG1KnDc5wPx5i7kuaQxhNZV8uudEH5133/rtELVA
PdvMlfNzLJOEBUso/wVPZoU15/os+i7mATdaMFfArwiROCGyXrFhxW0FkLm6ZW04
/YZtASMX4i/sgPUhUFYQkQmA4ky3eR46f72QiXnuGmWEzy4P0TUBqYHrNFM/YZiX
y/xI2LoCky8MtKarK/6EGCxHs3c8cUtbs9MYpM3TxRz9xnmBNR0qgbCsetgGH+jW
Zi3ILftYq/a00u+vbxX5DAB8iEV2c8wV7TvgQp4Sf3grW/2F1u/1YkiNKMFz29JD
AXvm3bRPhBuD73PjtTk0DVXl7ZQ//OWpcbhrMA51e3H4hEdxSeYmal4+nKB7y9ra
MoK7VQWNm3f3xtwWrGA05lKfdg==
=lsEX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1d298d13-d4d5-4a40-a8aa-912ebc71766f',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+K+17l3atZsha0olmohmMiigGdvHCY/T08YKw7sgQFdZe
1eoqBzmRDs078rdAarZFA2ekgiLEFqfc4m/7rmJXni6abK0dc4n3Y40tVYlZN3Fc
04zCgITDGcydMYwh8Hnw1hxGBPQRSG3X2gSKvHgEWywe0qshIPXeHZGA+GhWhfuZ
lPcUzdo+CVt59wyZilo6PQys+ZGG04QqmhNzlS34LdcnStlVTUNjBaGnwIi2Vas/
D0mwLGmXU5qo4EtfyV9waYvfCtQE10YYYV/eJBaSgGvvYYt5ze7kaDXD3C6BNLzx
krIlAv6Qbu2XcXkTiOG52ysdbATEhvlaaRt1ed7YPdJDAWqErrfLamf4lWQOG6gW
VvcaJu6687uAnQwE2Q5rNh0BVHA3oCULOxrmsRb6j/g5TfOeYMqfwkQaWRR1kphU
GbLbcg==
=UPPe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '20368230-7b22-43d9-a758-d06b5d555204',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAlcrUoQe9zwQxDy2zeFW96wHEGnh3wnoUio3xcBBtThQ4
u06hsiuH8ukTWJuGhyF0LhYLqXln3kWZ2KL+Sgqtv3SS1OJ6pk1K4HyxPViw0s0Y
Nj6CCerpp3q2mk/5mdU8axOP9+cu/lylvJ7aDXmowkLfelzqSd+M3ApmKVqo0U3y
ncxbvzNRTU+qIW+NX+PIM39o3P8Mdjnoucjwg78AzW6iS+SxCD+bPYqa6l7XT8WO
FxvPMX/wlzDnNyBFAhrcsFWppqBh12K5bDE2GG7W0RVzEPvNU2rG/ifOOTQhudfc
JRuaVfWs8hQVEjiK6oyY4nVOhWIzJNFdcDnseahnpjn6xn7EHB5mcBVAhIomVTfe
rLYvt09P+HhNgLJYtf0amekqNVhREIstP8rHQwvRJJ/yHEt5YvYwpPA3Y5wCYZzB
Br5l5hE2tBPEeBMJoMxNMktN0A5wnRdJYoIN/ypz38zNr4Wmye6KiCQDY6zy9U3b
iOQzR0ijVI9nWYq3p48GLMjb/ggCVnkL39buoRO4KDBWs63ztvOqWafT4/8S9VDr
7uwdgGypICVuvkcmbO3+bKwmoG4zaIxRzUCRQPTElDNB0vmrvxSi/RylaeO+yZLF
nL/dtIDpeWhUtOVIcN3zwKYh7SXASECqW9UfIjVFkX4U67/UxUv52yzFhgg8qdTS
QQFwtFK7sYEiGH8EePLBBby5qRljKaRk4pBe52Nngcrkj88zd3Gz/X3eUE4//UFw
wLe4QsK0DbP+88/V9vUHL9lL
=KD/H
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '232e4058-2882-4b5f-ab36-480111adcc2d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+PY7KitUdFdVKRYRTncsT0k5Qjd/29KAWELng8prW76tE
SNuH3Kg+Jqo8Fa/QvB7mZab351euM6F5VULG9F03WHQlwBS/WzQaNQpdMRyz8rRy
b5ZWdW7wEwQe2mTxAHwjDmv55XiTIwyru6HTs7fTjtnLzfeTNRuTV1Z5lJiwUd27
/CAs9hPKty8vos/jTlVHXGbXR9HONk0G3MrzSFbNdd6soTh590o0RBl8ktFawFTx
xO7PUHpOjQEsqEbX3QU42gB/R5XwxcGfhn/PYQqEZY9UkHZr44BRo7D6q7ZDRdFz
6luptNmiLv5ISuG1PV7Oh5H0ArMeR4c+yQkjDcL4Q+EcG2U6luKiFmu+xOF7hXSD
ZX2XTZ11uHNqk/DIN/71e2Eki6Z3aMFpyx0cZIyB3PyuKQ34KZVkuXYSphqt8hxr
PI0R0fbdlaLdMenFbIMCE9y9YWksiPCCcsYq4FL1oA+286EreTmHrZxaPpU/mZdt
JC8FB/FUSNNmL9xOAVVe1Imy87IXHQD2RRK3ZUbdHotfnsFovH/eMGgGtFpCkubz
NuyqdLKGEihoqfbpFU66uwb46RBm4llpz05MNGRd2X29X+QdCUOHULo1hjzrNYne
zHXcgMoZWLt3vGJoU3NcWqS90zk0Ya68SKMm/eoY22hZeX9R2EM14Pzf/i0EwfbS
QQG0kGYUMqXpt7WiaUshGhzW0WUIVSDJFiciW5hjx7wBhQUSzHtReeEJ4aLJzDVd
CXjy+GZmsRa3sFFcQoi1ubjy
=eMOe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '235914bc-ed08-40e2-aefd-6c2d9b6349c8',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//cXWi5oB5q1T0OLtsCk96536N8fwiGYjD/wbuUEovxpvp
AdgOniCCUWkHhdKhfmtpikOt+cVrRnFpxNfBL8i1q4UordEpz5Uix315bbhrjaYZ
0MX5ES8nddRQZP5AodnRudWpRRcm1HPfpZTyfm8QLrCi+wpLklrH2GTFss996BUC
9oADKFaD9pMUCPenR3LnkwRl+JTLZKshOrOaNde6PQVSmLNfjAwr2gn98OjUmemo
SxotnkgmKkNxN5fX/FFWYh+soqnvWN7NmpxRE0S6umRqe4d+Qo9K7FUG4vRA2jGD
HmfAmQseRhQIYDI96RfknQoYkIOgZ09eV2AGSfYnmhwaXTETv/Ab0GRfE8PBqgE0
JqhRfwdiNX0SZ8ajBl9QAdXg/pGyYFeFU0CFRUq7WGR/VLBsNBeUuzz9XOojBJQJ
3LEpk9XgHj7zj+V0QH5a++eKyovMYn31QiZCq1Q2EmzkkEkSanWocNKxd/TjIvrw
EK060eDaB5kHq7y3Xi7mhXMGkEPp6AXhAMX/AmKzEUVmTCRIoFUm08pMHkvTI5yZ
4sYsjpb3Bg4i2xPOMLnpgDL24wpEyzyVf5FVLO8e84oZsssLvP0/fEytVAtviyLE
++/afclrGNUXy9D7jXnYMqhjeMvq3UJWwAD5AL0bmMHtikLD7X7amKUdZ8GFMATS
RAGABqCJT/6ncEeAnFV8NWURwnfOmbq61CGF4JjQWNoRxzog4qsCDAOksT/W3bTV
/tFQHHOqVBACW1zWVCyZ5y7CaRQ8
=6fe2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '32be7971-20ad-4564-a56c-f588a5b4763b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAnuYYddVcyAh4fcL9IGb9I4pW03Shgq8VF9kf45HgvmRv
pprr83kKnWYSuSLzPaKst54ftQXRsA65rXH/8zCVOZRJuwlw3Z5eZQmUlmYPD8Og
6CrXSHZ75+g4o7x8RDQ1eHdAFomw7YSqSe1qfTVsjBFQUSl+Z99uKs/f/7CNcz/H
s4NtsJdXkR/lMGMRzZ3wqP4Ez3VgSnBVpklGMTwn3NWiiZ6s6YyVUfYe1u+tRib2
NePGL66IBHKmp7LffrLACsTM3WN9OardDkEbbPPPwzzkMJCeXKCqXVzU8LBz1p9Y
cWrWbVc/oiInPWU/W6pqYAXplL1UGEZR0E/FE25nbugoarAuffEFJ2dopxHPEald
q5HYlhFEgUZi5NffcqXtS7JEi2dguHggJA+1zP5XXjUlZYNwLTeVisT76qE7e2e0
5QK86e9Z/qDqcb56ihTjv4PU3TTOzZ+A5VC5qL1chejf+QHTumAecEz1dZxLtWhq
MaxVv32jetPZAcrkWSMkPmBB3C0z699IK9jFXPMtyo909xtH6e1xGJSsVnkZ0Jtx
2LRAGBJJblkURnu3WohWnpznkyVRsPTu9gsjI/WE/stbwkFDyPic9bM4XMM7YoP7
E9qTpNZjpws39zqSTBcCIyoYhTdviUW1X/eLB0uUUVkkY7ggwlKwGEn8SgGsm+TS
QQF67pZJsuZDfaIZVDTFMYJjR7aI8lga1X+g8LNoTCUqjp3XmIAcBWL2CYgrD8IT
CT8ti1ja7hzyG5/g/zCMIIUp
=o91V
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '33c77329-55df-4ab5-a14c-3623bab43215',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Or5ecbUZkFIq7FwcoVvzf1M06NBD17Ngan8fFXSvdG8x
LufPqSmnuW4xLH7jXA4Y9UUYMsw19mKHpK/PcVz+Cf/HTE/htCtsb67FOL25KGWB
H2Yec2mCyVKGSLRrmh0x+NsOrIzpYZ12/A7hGJP5RaOYGYf0C1HvclD7PTFJCbUs
YZ0QS7bhrpZE7T4DudKz8Gi2zwZCL9UWuPMXUeSM1zwZ81c4lr+5VBeXK6jAHroE
2QigsH3RxrdJatORSGUR+yL0QlEfRvKE36UG+EpdTuooliDgrgPCh0WjjhTuz4Vk
IdlUIevwwY2ue8oAfzpd/cDl2RXozFaOGrEKuweTfEHKnXGAhYBXxGlT8k6taBar
cXN3eZLfRsJKtSGIg97ESZ/l6okxmwUufxoWx3UzGfURRJm+6nWoBWnChY/8AYUT
qB+nhU7N5wJnXvnvuC2rcg4PX+uQjWL8ZAFMzeW8ax9SmiplUdFUP9YVlReZvcpx
eHX08TBvtZ5HxxrUHt7/7xuw7JqrK+bxNWLHH9J8SS/+IHNTmDVZdwXJwsZKJeZY
WYRgpSmk4+tEUrGR760druekgR7hS2LgYGit7NSVEoePEYuBuoZjEeh5q6sqex25
tUSyoHYotGGRytJ1Uw7l8mAiZZS8fmZRmSiZjy7oSuLsnsCBtsTdHWIV1h3aQVjS
QwFQ1r1/vJLFXb/A+KApktHluCGweU/iNnFzNwl9VS6Ws3GqZLHGGsUvQltNkTtz
Nhtz0XzFWJ2lfU/xIRQVDKg77J8=
=j99N
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3e657556-72ec-4871-a8b1-d5f1eea1cbd2',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//WG3Vit0oHAKRfwHRS4z89VB3L7P9IQud8386+pc601iC
OvteRbJm9J2LO0zVEw3xre+ADztAy6bHM2P2XSDwEHWHLpHJqJWzngW3HjyXA/m0
rzTJiYcGwbXigJxZi2vbb3qoba7j5GnFfncsm+qq0RedCk5OUkUZLntLRKo765gL
43weNOlA655znvUwDApKmKM8+jy/QuHWfTNqvAy1P+uGAzSNmEarf40UJ/1cKyMo
c2FeaxBRw9+MLSL3JD/Yj1ymRU5I55SmLo3Cav54EC/omzAEyHPaybhHdBPWlnX8
EqlbeKlDlsYD3SX87WxxBkSLDdezBpzEokjGwOWEOyryP/yr6vGAYUoa/oLLRfB3
v5lR9xnBpPDmyPOMe/LsucTm4OO4lpI9MFcr9YsOc4aMQ/swq3CZVi/rfvA5sOug
r7aLxZBGlYhCiQsbDCD8b7KM7AC9oF75xT+JRbcN3+dL1m2Vp/sE54Hqda4vtVLw
yrFTZWjpP3XaU6UfZMMLWmOA+458agsLbu19qSxGtzN4XngD3kau843UHhr5Gho/
z6d7gBoY/Ps9lzSbeahDyL1jcDbngIlISg/qdpwCSD8f8i59umd8auBTbxSVio92
INLPvj1GF9mvMoN4BfAJaN7Syytmxe3jOELygFoFNZtkYkLGq8pGXuao+ROinBTS
QwEMa+tmEAXkAXcJjYNLthciL0Qeqe1NWuy/clO9mWl74r4eKn3W52nTHOIzblTG
1M82hbxTvsw3VLRf8PXcfe0hk3o=
=L2Lk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '41997908-6bbd-4046-a42f-c67fdb10a070',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAgyWuQcQ9yoVBWiaUuFgs1O9A3FwHTYb1Ydkd3GFPYipl
ZfD6NHIS1BXX4iTTBlWMYbdBjkJbq521npIqIS1v7lH9PghtzIs5E/b+Jz1Vw8QO
+kXn+83IVHb0dAuD4PCeMehpSI7eOJsdR0iMSxtPfIsFnvB4onYbrFnb9IBSX7NY
HFGENZWiKodo2Qfh+TCYAGLJTT0v4mFeS4QH2UcvcZKDzZ1bUq79o7reahuVINXY
lk+rED9uKZvqoaTx9wrFwPyNkcM+FGVS94llUa0dCznHO0ZVSmcvwJasQ0rrMBTu
rzf/R5rZL6YCAEr5BiDSrR+P0nN6HjQxB7kyfc5FJoC4G5mHx/UqijsW5D+RfAfG
4syKP/0npU2Bf4gr2NoXlyHu16QDgu6YfsGe5XQRx6/cd8D8KrXTRag0/oD8wgTN
AdAqmYTU+wUFj7eLZV4HwTykMEcBevLV4ecmXOM0JSXALFjH32vUwIQ/AOl1NuTr
Rw9YthzYPaRf7MLc/F1LiTS9XpUqxYqdqVW4wyctZhQH7vb4bj/7pjntJtL8gzgl
LY7zc7iVy8pom6MrfrWzAn+SlIMMiTHYVfZOck7d001TkiEZqemn43zr01CH/Bke
IkiESxBaIDwEK2MrXHhKz/GmKQ9KEyeJMqmT+eGJCuNcAhhJbuvFrmxqORVK4yXS
QAHKq8hjjp1OSGvZLXiBrc8H40BQHCTRKQJI82uzQoJhCpNnSpkQ0IgmaOMBFTPU
FxYWSne4vgmAgR9/RXcYrfE=
=L5ws
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '434cf9e0-24af-45eb-a2fc-83146e239ee4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwXmzLNbL4TX4ivGhN3pVDwmKrLFJ0QhJfLD90AsmWFr6
HIN4ykoWhLpnFEJ5aGcc/GfeCxXKQrtRoFaHGz393zZtlBwtljZ6Urw4NK0xjcaN
zsboPtclRd+NF2o0wwftfoIgnwvlXrF8lMSbYsD9FcE6JMRe5q7r6oCWhl/n0wsU
7jqtNaEH/+T9Rby0goK9GnCAa0RaT32DsXf47aucQNHpvbUoI1x0Rp8Sed29hhbC
tO8eZuU1zGHSqm0uxyQhLHVanhSO7od0MoCKDYh1F2/i9pHTuF+7q+fJc4DVT/KI
RGj8lSQFQvQp9inC41vtzzSbpFKd5tMnLVRz9vbydmHZd8yalNasqzvjOGbazhdy
6gsc2KOZoHNSj8Md6Wd+wK1lGYlF84oZq14xXHODtaIENBQH8PuxP5gZ4SU5r+gf
+DoKOX8Neotfc7VTpI6Qb9wmzo3HfMMvmZwIlBqUZT7S7AM/9BboAvu0UmfE7hWQ
91AaUPryW0xC+QTagorlIIlAvFSrRS2rivEiQdIy4k5rN/GnL3FRl1EFL3V7gRaj
rsThuc1isFouh9sIMoPDIDXoit3jkq/QGV4ziuWq5iWQT5whH4nvOcynY+SX9SVe
cG780jd/jeTTwAJwzRzdB2LcJLacq3BGa3/x/V/8cfrOqXDgauOTlRmlYI4Kr4bS
QwHOS/lsdKNdq59gnKwNy5GpnXGzA9qoKx+OtadqEO4YohVl+1LkbLOnVrCJv+QS
jWESEjcxRJ39GkkL2AtjrrEs/Xc=
=m/EP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '444d4820-b189-48a6-a2e3-6a35702c714b',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAsOGHWX5gG+zX0ERY9PXfoXnp7lyDDw+7mdjpfRm6c7kX
+0X68ZRb+V1yLgJpm/eudEcMXmxY2KdD9stewt7jbKzM4XqfCtmaTBNI2AcmPiwC
r8/pwnhD4ZpoSYV2w/zexl1/W+3bGlHtgEI9Bo3esRZPUxtx5/e24456svjCXwJi
M38ZVFDGF/pANAHhv1sMvWys4F8UWzHaRfPtLkLmWFkG9Dxb9Ef9bq3Ept6E7nUt
dvheZgkM0T3J6kE3xgrUPv+YLsdtPMYQ/3KQLsjp9fLxyQRzokqrRv4bCiK1/16g
kQYLiNk4sZZ8n3Z+h8xhrDRSepXYidNZwJvjQ8lbO3sf5KC5osOhefYv77+7ys10
rfxIW2pTv4xdBgQJ643jnHfSm+w0TXPqC4WrDtwyWbRqLxDk9YPOwsl2TgM2i/c0
Kj1CWO/GPquGSKYNnm7MhuKsYRL3MTDbbmMESVxOwYYo319a2+LYxNXXClsBlENI
LHSUs5iz1Mx5O7UsORuuxZbW5niAlEXEKbY8DdKf5p4k6+dvpzniVypQVFqceLKz
0wSaH/whA9xWsc1FEiY4RQDXhPfqtruMSYgBjmbH3CwyKdTYg78LCx76ukTnYer2
qsSQV2C9JMQ9Z+gePwpVd1SiWjVk3E1HDn0+ArAsJ2hMVqVgzd9qkHkGxtLnO6bS
QQGJJeLVAEQChNvKsX9sIWs0yuT2w3XU/3Hv0cAr/5jGOAxYkhtxVfdcFNtnbXV/
UIvSKHpgIwdEQ4cEqqiBjyYO
=eIuh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '47057ca9-08fe-4b3c-ac5c-983330ff3c06',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//YfovlV0V6FG7UjYTj2Z4mKkaGPHGaxxhx6GOiBs5dXBO
HPuD4U6DcBEhtBH9aQ0nuqGoUpnE8wZo5ByWPkyxFmWR3oEh6pyQ8kMaZWBrv0PX
v4RDvjQK5o1qq/xjj3uN/Xq4u0Dp/pa8BYTnuBYdBIG+htypK1ZasApvIXlA/K4N
aR1pf0Sm8yrs+zc8+jBlDDnPBhvdoXlHnO6Nv9a3YTt/9yBhgdgl1YKKv3HmgGOo
dVZEaQYH4s+7+g1owcAFfs+7RjWSaLNkv8Qe+7uIbm55aTlKzN0RIZS+TgzNukKv
0mo+4/ldi3IZgsF3JWiOnZ5uXkJZFRmA2Rvn0ixX6x1aWkmwtcOtr1+LF9Gvwprn
6/V9sJlrtn4a8i/75TL1ujSoFtsFCOleYGjMhvwAIpyOXDgkkTK9zq0YS6NKvBm6
Ctt0k4jlfUvVt4mOyUNQCPZp0F8EqQK4q6oWtrp3OfqPFCm1CIo5OzNpTucunthK
oBxcJTRTayy6LbpwjulZtsK56TnCGuo2JVwtuHZ8V+0Z9cvt+b/ppAvqg8InL/ak
5rlT8yurIWsjjrRJbJElZG+2BOQN6b47TVqxcQrK5rY5q8+VZCf9QnY8vVmFH81h
KM06J1u2lMPDiq5wiYlITFrBh3mAVYaL4OhJiZanYRj4Jqin8A2BAjgSdwHyekbS
QAHF4jb+sF9X2BXddebHiiu5R6Oc6IaL8ZUATYPx6afuu8XT8ZPqUy21ibncDdh3
NWRBObJzm0bifSoPd6X7F+s=
=f6Ib
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '517ef984-94dc-4625-a83c-8b90a1870e2d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8DGAKmnt7nA4pYi/EckDhmhdE0tRRNCtMZ9yTJvsEpwEv
tkZ+8WwkG0HnhIUg1eg6r0SIhrJMbl00YK8c3Mpcsw/FlvzH4+RK20W3HBv5VXV6
F/k4tmy019aljCvYAhKLMvf5b8nzKVcUEYBSZ4+rAU8euRuTadX0CBxJV6/CIEk8
3cq1SBjhIoQO+bDKhDKbtsIWOGkrPsw6drqVEzobyed/XFIC+vJissp7v8ROxArM
WEku2ukQxUSVbu9C9IdEfKc4p8WWtUrLy4GY4zL05J9BjLysoAWHoOWDwqjrVJCZ
/noAveNujb6QX3bDMN+x/BaNc1Vbr1Qm9ondjb1Wltjz4viwp4z9con/zXi6swCT
YihjcmVn1wYOeYQzulnZDu1xcxuCOiAvNQCAEZukVCIkTfSkW4DYuPaWE9pJeMdZ
cv92OZEp938iqKaiqK3BfAslQrjyhA7LnOu6TaZdMCBBZc291JsAekxaBu3VBnVL
xyf6pfz/PSv3S4Q12jUmaJLLP1UhonkAyQhhnLqx5ISwpt6o7sZtkwA2KL+fanYG
JsRu+dJ8eTfrQ9vaufBwdvC2DKGsZwzPxkintr1Q0OPcmgopFMNQAQrinIXbLVzB
GUu0/qJhJe8x1ZJjHcDTHcmNxDYfRBVl+C0JP3sWFQ5MQi71n4GS/dAsVXwHQ1PS
QQHQalDh83Lt4GzZGmr/6aLaHC1S5KRmR2+jUfnSfW8I0vQq1+dk3txMSTxUU4FD
eEPhhBXUsXn44ykUo4tWNEVb
=kYNM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '538f11a3-6c3d-4c58-a246-ac7009b62044',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAwKiOqsoEXdGEQdei/f6CpsIJe9pAVycVyGc+nyGFGL2/
kDujAY/CQffpR6k818Ip5H9jNSFml9uAu8rxqY6tKRz1YaR/1aCrnPqHjH8lGQz8
O+/dJyLH93Vrfvn3nYqj/Z7FRZTUrOUWGtWMJghCii8WsidkA4fqgMNUH23SkzNY
wuoBKpP9Cjdd3qAfez6tPx5gM0J0wzlOs+6NcF/fbSEPjJjNBFwIQkcsXLgBodvT
1Jqnu6EKb+3GoECtXG+r/cOMR3sjog/4WKlodSl4Njx+1Qanx1mjFq0Ky6L/oh3L
yAjbs/wehkJkvo1y8LPXf//t1olZ197ddJWkHsgQ0I4AZymgsTSEjGyqVU3DigQl
Gd+tFEF6b7BFKAoLTpK0BxOEsBAzyW9NNuCBizak+NguDinevK3jpAwVp1xNBaBU
3fuB3jGbgW2Cjks9ya+gdd7783tEYV8TGovvscrW9KVni4kX1a1FRr2Bys8xdM41
IEz3EVrt88DG4XTXdpXHTPh3oRKcs/TRmWbTc8wHsS3zbz55uhohGcTIFaRpHyyf
NYLQ8XrmJ3liEeWC8PmQdeXhMF0zjtV8sl/tegZnExfPsaTnSPctdI4sOb8T7bOq
/xiloy1SfGooInWNDbeKCm6Dy+WHhplEQR3SAzwZbib2Y6U8u2hC6QqvrhPr1obS
QQGL3lTYvE3j0/TZB8ifOnV3khXkzkWK7RsGCEMQ2S4/Pwm+yP4usBdZu59zCYdj
GcZ4ElAcO60lG+i4eYtHK+I3
=cbGB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5c1ae12f-1b8b-4b56-a652-121582c6f43d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA5sFmde3OFSZnU3YxY9J0OPXvMyOwM1DBbmHMuBMQh7gy
6XpjEv807Xccl8kek1h719q6sI+kPCnxGB/KOpWD5+zsl8+j5E71CwECEIugMBJ4
zinAI0Dih06RCvGnsps16uHy0IfCA1w2bl02L0fMVgcZ2S6s0WtO2LtsequMR9Hj
XxaDCMQNqkhv42i6/e82MxRBMrl9DfaFqIue99u2pWRDPsaz6hLm4EceGf7kgu+X
qvtUTHEalb38Wj6RX3IsmAexpN+rS7+BFgMzg//9srrsIRfHiHdp9wKD340fVOh/
8lLzKC3TWGJGvSlg8XiM2F2NPb4kE3wkK5e15QK2qWU6rakZGnNvmHsnawa50xKw
C7rPpf7/uePRpOEl61OU5DE0mSvULzeEEq6sKvK0oov/97MhubxPrQaOhph1hdCV
xCJCU+jPR3X+HotneJhm+mbxUdVsI2E1T93vhaMJiHQDZ9X2YPVda6mpZTJZEKyi
OUiMrjM4hQFvPCwBhAMvQEzMODF6maXH4zhlFmEqz2pp6vx5/rtJBLeVDtYz+tsU
4Bd+Qh0OYlH1jZAcFjiDGtdt55yNdjlVt0B/M9Gd15cAF2PQVIHKoKZ8zX1OwffK
LahksQFl32Zqixqw8HlQINF2oCLhgX1XByO6W9BjR80bFdvqsAaPV534I5U68L3S
QwEB9d+8f23bd/4vnNu6SpbZ5rPOC/sgY8mYcwBv3tvQUnD4J6H09aaJqcAlfFxB
aOIZiGc0qqb52wAWvjduzdj72BY=
=TMI7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ffd52b7-a228-4b56-ac94-d3d5f03a49bb',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//eDqJSPolo/fLi27oBW7eIjs+Jn9/di2ON8PktCL8piiu
z9VwYa5BIEu0uikGSkI1w0JRfcdcdQ84w6Zd198QhGiuOfoXI6L9GGMxiKrFk5LH
D/YPwoLQ0Y48xYKtU9uISF+hNxZKd91vLJB1ra+/ujXu7fspaQYhXIxm5deSYx+O
Ol+8mbmvf6xrffClzO1UCLbbzcFuqy6Qj7geMW+ivsm9XwtYUlMUVbRJWuEohtv6
+4N4wQ/917Hk7RyF+pgBmdk96vgOCFQzfiQ4OzLONOH5E++lQmu3km9sYNKpLFYl
9phI35bUrYiVHnsScjCD4wHcUnTy0Si0bLect5UCbuQkKw5y0rf6rrkjoMETRJDb
Qk4ZgqkTraafRQ/107VdYQrxNhifduZfg5Y/JDceS490aBrKTmOS9TDpf++Md2/n
mGJWo4kRaGgz85qsM++hf5GXKu5/mCjyQxhLaozyiwxPGVF0por7OryhmRnMXYos
ATuDZGIDdDTQQORB0EBuv5f9/NZK59f1RYOpXHTeoHKkCDTd7hteD10sWmRj0LQb
V0PFb1rVm506FCPyaYeK7XnlTXnzoupVXr5dYPhRqLclT1uyY1SuN+o449mv03XU
rEahU+kQUaN9iJw3ua+W78viyNHrZ3VIwdlW6RTtU4dbAr5sjiYGjdu5Y9A3TnDS
QQEBNlcjK5q29pdA0afh17jDa6ahd7E2UPOp2a//uKdjY449OBeuq5tODcdtp6wy
WSpi3TliEsPiJpB02GhEaH/V
=Wzi9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '60de855f-2deb-4fdc-af5f-db02db5adac4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+JvQmM/BFPZI0Rd2SeTfm8zHzhCIZnPc8og5QknmXMbuc
/dOWS/Y+W2IpZ5HV/XQxSuBjR0+7B8/dz/sTYPWCBGtz5WEhVyCB+IUz/6KhjeR0
wvAiWWgCR9Pgz8DUgmoU4JlmgeCaSHBCVLry3RcHTpA/hECaOzjjEI8QIeHVdHla
gXrhsA7DrqRQ4q6Vxp3KARWwtQT/EFw1vijPFEqU2OV6dKEXXQUcDEnYz000qpzM
sUNBNIzfq8zscqRy5ehh1C6UEgbk1uyEaftitEBqovpd6gPGtqH+yNXVbV3xG3/e
fX1GPiBpBNSGq3Dl342Fxm1PVgnRlgUHTaPg5SAtHUQThXnVmFrihCRsoBXtrgAL
F//BAOxcn4ZMQQIpXh1D6cw0o2MfzY5jy66DzxfGFr6kHehtTD8AaGCJLuItDAga
qsbMwovcewAex/01UfWa3d5PRUT9qu7TFicL3I51GfD5cNV1Xry6KaenoiyvPVjT
vaCJEqJcJHZMuo39Vty3ZrBeWd53LP6thT0pRzXcHAxjAF9XEYuu+Lbsnd//9prN
jn2C4qRLGJ8fpM8YAgwhRbAcAB3pePzyllAG+aiym4piQwSRjGlwsqdoRq4i40rX
48uKyPgdG+d09l5HvKkPPJA1z+zqdYEyfEaPMICV54nlHnXRM2HKssriyYQUISfS
QQFTIUNjZSmOzwyjR4kNvXBEGMQLTQMc+fW0WGsD2d5aEUVGDVrf92oKaGHtetga
r4LXfJMvgBnHOq9JBuAyrOJY
=zq70
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '700390f8-2e43-44b1-ae2d-d3a9441f71c7',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAmzv5dTcXq7J94nUgBjDvrjQ322s1m3DTLrt32c3PGAGb
x/8JBZYfYtQUXCN8Y6IR3P7gWoNb/BHeiXKMTEtumGA8t6YBRx9D682+jwEzr7E6
lAGQ4OyFFS1Gp8Oc3Jk/SJoAdFLmLYl7LA9xOACl+JwZ8Zlb8BaI54OPQ0+AKTt7
/Y1L/S8zOFQBTBBE1A7PE/CCQfMAGyZgD7jtWgNw5PNCn0crhFp298l2cRYZxYvu
E8MF2QdJd2hxXYBWpnfDl3T51xtPAeLqU314/BwAqIHFmxbz3cMx4OIaEUofaMJf
t7nL+puut+yBF+HV9cjS6+HIXliN+d5MHIjZSzalaY54jXlu6MTRcgbdB5StwMAm
i5RUazlhiIaO9coZw27JTyrpny3gv8eKcAwyNvG3xoFveIZ+edmJ0myb661NSqj6
wNTRr527mh/iDtTJdNLMJirjbH99iQPCAf/khfvwzBjej8xmNuRGi22R6FOQQziv
Gd4eUL8LljeJfmYpAozvYqLRvD04l8UkNC/uH1R0AK+1N/XSGgbY4Vz2CbE9/yaX
EDRSFzn8mfN3wq4kkfVzUmd3Uu20zR9VAFcHS5U5kisqaCX1Q627shq6VPIsQ5jR
Inp3/t07hvwbiB0T3WUOJ+hqo2gScrn9bBG1A7eSPTTNosx2QTOjdybtPRDU1TDS
QQEirktqbCllrcvKkEUa0Inuc+iIN09ohphHEH+G0sHklCmaP8wSbdIJzEvYA0pO
TgP4u4Sl+zCA9nqGqg9lP/Aw
=oADY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '72286394-1965-4a0e-ac7a-02802a0752ca',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAg9PCDEI2B3SZ84tl7taqb30np9xIfTydA1wnIvrRzM5l
sGoD1xmGtVc4crXJylb5PhkRzmaQYYtNwQli2jpagvliYOzPFtru8mZfV8GUqpMx
neOD0KSLMKzrJUgKHblNYNtZxm+IKbohTXV6UNyh3hhFwnxQZCMyc57lqCtc/oJf
nJ/EZjgy4rmLivzafCiX2bYceZ5MCo6k7wVQmNP6LUpMPo5bFwJjrC5wLV4Lt3oF
x8vIfXj6hrwFkytSR8cQyuYU4SAjewAB3WAZ833N5sljxY5w+M/rHwGJKEnr9zPp
4maw5YtDduhFpeQD5WFGt2KGlALMSkMG/dZvlmYm1aEfYW61sSleigsRpDpqA38q
UdwlZCgrlpk+pXE6CCe54HosAFJRInBDWsyEG+1nIC+pyrgvEoqd/MpGlyX9KQJ5
z/x/lWowoMXKdHJ/dIqQSqTYvkH2ZKqBOwDLnwNzwBmfmYLJcYD5/Hv6UGl8gYoE
ISaDaa6u6QdgeYskkM0C2Jf/wdP4nmMgKcL8Y7mabRzqdOdyIg6pJKq3UQI8nZoa
w1wSWr6g/nRpOzBquzZvygHkSnRpJRGHHed7zL7wJ63w0JTaDkits3JHd6NDL9OV
dxz0o18v5e6v+p+oUDjcqYU1uHQ3otBQR1xPhPZpuzcpOV2/InCHAS6fDOBEqa/S
QAHsRim3BmKxTb2fqsAdi8XpFioEyc0IXXS9ijp6aAV262WwH1qauqTPU7GazdcS
z0n29eXVhxf4FLg87p56Kv8=
=PLsu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '72dc34e4-fe8f-4be4-aa47-8ae3fd7ac923',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsOQudSR/3YoowOFqVkZrjRTnkR/cPsjS6GaLktHourSm
6RjuStVG4SFBbMPJlhn8+SaTkv5n8mSpiBTXHt+9wM1mPxb/htOsdCtUg81jHMCv
rEq3pLb/YhZa8SO5UK5wBoeSK19hFXa99j6qlvBZkwGFmmQxknq6UIeGpRF630mi
QtoIYbkkjezrnk/Pbos/60KMtGO3s69yiGETj8OdkhgaFhlPi6d+dqIL4xQO2uX3
RKYFLievDpOCG2zy+Fg9V85zP/pXOIER8M21ijqDiHCu89x5/B94X+1Pj4qyQByV
YbOMeNYKaZhnSsiCsGM3+H/xi2GwLfE7fHWbetNw3tRFrVvSwjXBAG9RDymbFk4z
ifvhoM7GxgWV9ItwA1YKddkewAczbu7sgiamUIIFZlpEgJ4a9i2Xd+YA4/KZsKhb
GoWjgi9lS/7HF+7A4pb7ojQFKl/qPTDJAuBggpU3Q7LGoWCn2mtbBIRnNgJlpEIu
6JlAAqCZci/IUOV6tCa69wSFI3V4RkKowKj/Kfy2if4jPD8mVOTQP9dRpXeQNtji
q/rs+4ZLXkgym31aYo3cVAhLb1zUnUr8S/ElHqklZZTus4T0QsBDAG/w39PGaPvo
DAr+TXr/uGD8S939tZf2CiNZw23ZH+reORPQ61kgKLPlxHpX4gMJ87kQIf7pfEfS
QgHRUBUSp98QFFeWHksuJ+VeIMczfBEHZkC5m7w/lcqap8A2oT18T+w+eULzIJB0
pfFHvPlCHl/uH7yDPVprYg9qfQ==
=TR8m
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7450fa23-4d0f-41f3-a86f-ba77e1ae24ef',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAi/jr+lhHlenUgbTG6n3sKZcHveZXgqRH51GdwiN2yse3
Sr44e6ENURLfPEfoSONDagsDVc6AQhUje+L9ta38G5wjFziW/HlYf6Ww7tQpZV07
JQTD1AqhRr9RRKDfUN1Ygz2mC4+EVfsu7oWe3XbBwLB7O0UcCMAK3oRYKT1SDN/7
TkQjYBJcYa8/v/JJxehAWQP5TpcqxeA+f3DWQkzgXe+ZR5YcMs3/YEKH0wRuh1jF
QYDcDsENDdpywvjvpU5v+YBtV3SUmQKNQyGfRT65ivK7WqbmbjdFHJcLtqiQ4oFA
tT8mvlM1+taP57W3Iaya6Lmea0sGH91FwlBMlqhEZ+WFK7fXdGDkgVwySFB4i/It
4/O7VupnK2WaF2+D88qfWQjHCA8q7BIamL63IsWjhhK5erYg93dsZ1TypBVEBrNa
lnGcdiBNX5yhgID531WCh+ZBZjSwLXt1QFMvV4cnr3y2hq/MyLI9qOQan+chxLv8
/OhjQbjsssGnR0t8u70A3TRdqcXzBETZ7nBfYS6Dzgun34ej7wnsn+JoCol+0+Jw
Lz4SacNJXf1VbuTQYYhSgL/vygFJ0wh5wxcYNhtZMcTnnR41iqOoeO2oLo2olCIM
BEGSA/mLSXKf2z1YGc0OHvEJxuvlJnJIpH6KiwzNDUZnAbQhYx9FvARNV7Bmyr7S
QAGCEvK1h6q5WlcrcTdjRPlFLmMSnJoYrFWidYmOexYvKRgMoOHQvvSCgM9+SLG0
IIOXMwQQay+7/Ers8WedNKc=
=9/56
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '78d49764-fb33-43f3-ae1c-486c9908f94b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+Iy82BvKZfXiDLyMJzdRRJEBEU/lL7GvbFcAAEUbDu+ei
JDKRw1mBAno4NCzvd4hgXU2EYp3pv0jjjMW/RJSizVYLgxnQsrDf2nqNeFN2U1Qp
Uer5dJZCzF9Hr2Eo8weiw0EtaeJKMzYJL1OuWp7xx6KrNE1yQw8lD9IlcWXsoprM
Hs0TK1dTSL6/yspHz1ldUPsoPMbokgLujAF0Tfip+s6z47fEdMS4OlT2G8klp7Ey
3/37FiwLl0PI467QsHVBb0c2g/NRQkfyBBUF6JYiow3NDfjJv8zOefCYx17Lr0+N
NG8pxvWHPH8/hRl8OrKRpOAYuVqpZqyhHzl7BY3JegNdobFaTzP/IOzR6qVUIRwi
x90dijOPCNl04hZXiLqQUALR+dr9vIz7Vvlv0UWJr6DX4gQUJ/9M780cPx5VXF4m
C+FExG7E1+Fq9FqWUyR4ddoIPkJMQzhUL/fU1SG0lWH5og97MLGbxkWHGy6bBD2M
6Kzjjpc5S22Wfjy73Jm/+Zg7xFUuXWu1LQnqg5QE5AoAI9sST9BfBP/fKFIrwt6s
MuGGNj91I/uxpi3xcYxLzXYX3M2K5p+XHq6RU1A8yi7FxEpdpvb+tIQY9SvR0Di4
tx57d4WA1AUvwGN4EwwAtmovlPx/QRxOENWA/SfENMGo/pejNYQydaCQm6CCEQnS
QQFhzxM3XJe6NjKa/gTBJgpKjqIugBlhQ5M6sYhwPFI3WUNFcLfH9cY+LZH4pLLB
bvTGRsQMrcc8VvUvPtPesXCv
=z+d5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7f87dbc8-9d0a-417e-a6a5-60bd4961166e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAmIKiiy1J/Gf5KG3fDmt7mqU4Wa6g7wy80KfmHNNn8TOA
ifzZb0giZopM058Yuvhfc/JHYG0lUbxC0b9zh67pOG5kjcBOSV2lWd7fjmsmIb8G
Hh7yfdDBJTePvuLMCu3QjhiypSNo1ifdc2NukzNUWgwVNK8oFzEKHWnPgsRxCaIh
9H+aRi/leDugvQLmKhP3yprC+V/cS5lB+g5hzecYyEhKyevSuztyJawGmNzXBemF
2Z38dYOHQlg0dWjYnnQPrIhPTfmU6lQPxN0R1HI5CbetgUv7peU5D0m0wPWJdwar
xaKB76L/KbJV5VuDeB3D5P3OQ8FgDYO1YEIYkyH3nG7HVnmFEl1riAYShRyCHhV3
C6C/h9ArHbg18AiNeDJ7GGsgxLlzRTrQ6yrFrEhu4xP9ZCHMPcVy7YhaUnIr14xX
prBi5dEiwV6hhLYjjN+xZULeajn/PuZ1IGrPQUt1ZYA829ocJOIxY23wwQG/3hEu
sv2oDjpF88z0m/bdMyidp+1Qyl2HtGcMcQEBHhEW2Lwk+C6zdyw9JbrhDzqQvvYG
2r25xgn+ifSoxSmG9DEYByZeEkZrYRiK1cf6gA7DpuOA5+NvcPq3uIiItOHG122E
4YRlwzPaQjhFWZ13Y7JJZOTlcsA0mKM+g59ze4JqKGtelB40bIwjeY4uwDcU7pHS
PQGoLOCrzeqWOyBnLckRsrbvvmPgduVFjJTEfVW6DSuncmydt83BVpXtMF1Hv6VB
4CLfeUSYhvF7Q/Pn9HM=
=R5Ua
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7fb0178b-ff27-4fcc-a864-47972283b70b',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/8DI3mrDjetvI43vssP9yoi+e7DVLFzI87NAgCzAxPqMi6
EQdLIEh658cphk44AXomFzYt8+VFNklpUZBG0sr+Yy+I1TfxPb7k81EBxhkJb/vr
q75sChYgriLzf9Nmj/JtKWnt2l+C7UscFPNP7WFSm2fK4DzmGjtwjwZZN5FxUsqJ
ZmVDwF1snqWv08Q9/KFhIfK3zP6elp4VRomoTvPtDdTSVPvDB0GiR5qN0cfkGEN+
MD1SlPct/iGo/xIVZ756XH+VbDfMjvovc4oPpJ8MJHM/mfYcwzdL33f3mu07p2aY
u4fKIaI0RAKwwkyfvBRo3fErwf2o72c5quwxRi9c8R2HJemK7utEgweaP4wP2GmB
rTfAXp9xRFWECQplKQXDJyTlkHO1aFjS7ml8VJzfSmlNGobCzNGHeuPBQd0KCu/w
HTLKfoIuGoeN6DgFOOSuhPMFLCfkZXcK3FZULrQ3628cDGNXq6vo28wOD5FrMEB1
xpL0l3BRJvi9BK0D6QoItO0A75XQlPVA9IdyLkGzMTC64UtUc3Zm8VcNznS/ws7Y
Zyu+FS7OIpU+lNBoaGNJnuZO2lmnTeVWT3LqrBYlsQtrF2WhW+XV5Buda51+MTDE
RisGqTQ6UGJvrT6hiYr+R6owUz1HbAgEsgIgtYvHKtqJJlhoN0RyTjRoP789fOfS
QwHdlJVPm1n7hYRHJqkHHji/Gk5GaCWC8wRmZIaWeT9RKmLhjdB5kzsWN+/Dt/bg
Ax1bVhDgebj8uu49H1b2JVg5dIo=
=0+p7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '82010b1c-62f9-4712-a566-2f1df408b690',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//WkXRBWw4eq4zWneQLpKewnzKK0J4Yd07Ye1Ux2ABc3lW
ApUf5bDhNTIYXeJMI2aB6dkWLF6LU5HLiDRcM6/2RkWyOfaAGyHmMH6fer/1/iC8
iyRwMiAMiacpHuNMgH+aoRIh5KBI8SZXCb8Wu6X0zO2fV6YSVwXMqLy8ZdGmBL1t
AAD118U0ukL3JiZQpd4s5J2NLI1DGMcpvxi2VD7SBGXadljT2x+CDGUdVRveLCPB
KEKeeHFMhM4KLxGdCX58tbluZYfWnDz2dQJhL8e1rt/+wInBAbFjZZrTmibMBdw9
Vfw30Ws4S5sRc8/d0cOti5RrjG/A9GJeh5POW747dCCZwmHwvrq4klU8bpWOTT+9
1KPsRhVEOw7ZOKOcAeLlYs826tnfPIH+mdxucWlSNOGGNQPDA79prKV3rqMIezoa
CQEEVMCD9TxylD4qia53QYpF3HhAe+nAikPAeYKtuo0GXGvzVUNkIaRe+Zt/U/u0
hzwdzY6JpADv/ji6vwC5yii/skjr+tUbjCDpCCgIvPfiVcQ3xesQe7WMVXGCRzSI
wMSqQ9DSd3yoXt0ST+TiQNqmu3ECGXvbOg0fEenSZmIX/r3gAQoqksl+lEwrayB4
KG8ZxQ9rpVvSAEaKoAFMgEUohgkEHV6b0mNHIalK4jJlOoqbKTAZt0yjVLYnRGnS
QgEdND/eVUlMT2KLCCON34+m28UjX40i8M6LTcSalUE/lbZ1zitnzYFbZo+hAvLP
AW/t0q/MNquColtAxy9Y86XYKw==
=bgzS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8af9d451-ec8b-43a7-a335-64a002bfdd5e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8Dr2nmQTbEyLbtUoeN2nmSsrNW0DuApsji0Mt/XI6w1W1
A5TUnzB/MzIOf+2ujy0Nj71TJFrByRbS/gGevMDwJGukaNoRDMuaNc1nRAIaoDR+
fVKBbP059us2u3ghXZebw21RLFuJoHd6Z8QoKY2VsQIrMsQcNkN2VW1mtsSXLrpi
42JxPCWUEPyOJQueIJ+YRBQJs0enfGvAxCTVIUa1TpG6Hw+wfLfdTuytPqflE7xn
uwhGVW4Cy+qpviHGND3KoGkckdxzK7D52kiX0qyVyOR+MXXeC/XSyXM9/+WZgW0c
KaUS8dRGW9r5pBSIxNb9WRgLxj23frHEiia59aygJUBnxdZ/Xhx2QTNAemM3O9EW
7dGLOFRgGRmJQ140zr9Ucb5MgCxHWXiQrrleGW422yBDgnjfWbsXQ3IoVvJnQJuT
yKwslqAmSCENaoHAvcsQVDX7jJsU9Q7tHnyfvdmdP7A5CWFC0XWpHz8VJMdH33dB
72C5WAQQzd/9wBv0xY3nuQgS2cgk/8XbsyHod8me7Nwz0aYAyBJSt5Sxxq59y8Of
6BY9WdlsWsbaTzXJXeI8UrlZlNlxGIvZU3LTjKH8QocNXI3LUjcYhI4QOybMjWHj
vzRAI1Fz5jLObjFqtAZxV1eTH73dTNBrMMQ7VYpjnIIgAR7iFjwmnW0GC9S+Y1nS
QAHXsS0/VHVLau8Tn9kNZkOCe1884nWK9Xy+ndKndUz9M7UOrjHBuQ3hvZep2qwJ
usgYTspKhH+4SDBBHaBHygI=
=eh4T
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b84e528-ee47-406c-a284-ad6afbe2114e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAn6IPs8I7C6gmjB0eDQlzflAWBKKmlBLTWhsyNlpwr45e
XnX4lDWEaNovoa8hI5TLI3HXf/b4enJM3HiRrxoIzjHMuQWtoF3Se1c0bwlJFmzj
jW8Vgi/yYWrRgljJoReHUgTLmFvDG5xt6uDiqGUJiyPT/WxwHz4ZBQYZLIwRafHr
FYEH7zGZn6fMlixzlXq3PvdLccY9ZwMfv22y2ful6Ehxsptvsf0TSNW05FSU5KDN
NuQnm+H0hCT+YvOaBJwfBzCyQlceUow8zzcQ6cEsj4uNfPX+Urscd/X6o7in+tD0
/RLPZ99dDfcBXZqKDZOVWB9X9DXkO2r59AlrBzWw2l1tQdeipr3sFfh+5m5B+BmV
hCoiEpMTShaV6ysoWlLzmZD79lrhRt8LD+m9b+bhjg+SqI6bJJJ/a2L/bWwj/JnK
34z2eFTf5R7DKJbWVQ0u2Zk5oN99fO8vEzZx1i/h6hIhbu6dtQXdFnWABrhfNRP+
65qUEuPtkgfmDaxaiOJqfXgOcKNsHmyz2jxLC7Sp/W1zEAJ5hEe3nUUQIuJWoHO/
65dMIXltf8nnvtcDMA4FQaPp6sTUD8ZxNQVXrk7GzhI3NxClqDY4vP7zJjTN+ySt
S5y4ZZgC5r91sRf+c5B34xjWtNba+VyISAdRdtKVbtDUKXcVQOCCOG1hCALcO77S
QQEVOcJPCQ9rYtXqKku/jPmRrP3u3U7RTTFfLIcOhBa5db2lomkcjhn9f5Fo+Cn+
0Pess26lptjgN5qQ9WGNfcE3
=Im9H
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8f349718-8a59-446d-ae3f-c0969beb4aa9',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/c9aj8xQhKIBSQ3JIYCzdWi5QwP5hZdyNTl+Fj9wdW/qf
8+eVFZ5j3NBwaD+M5+tlO34BYsLg5q/eGR1HhDQVya9EFP/HBCYSS1/0jZY+kSEc
ZDa6aILCen6tTmTcbMlI7zSQYZHFg5dPvvHgb27JitWeqRDypPaFhbYReOsBJeuQ
LiNkd3KlJNHTmzl8mE/EUNzInmQidV6zt7fxlODqFbmAlpz5h2LmmABEBCWv6kjs
Oem1Bu2zmxYpJR52feYyuOBK0dCROWmog3CraqXCLNCQ6dIIHBW3PNo9k3UYbwOn
31p1PbVbwzWZm9SmxOhnFEoR3ogGubAkgwgCmzUqvtI9AYpimjlqrbChqwwBFUSZ
31XCWeiWsIjphz0KDzOEM5uFDKdHOTeZmI0Es6iTid64GYe9qceDJ6phNEH6GQ==
=RsXn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '914a9135-da8a-4824-ada7-1dcf30963dd4',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//bMfWcDz3+iNuffYR4e0pltLvUbn6rEPF08n7gRrVQSXw
az9tr2PhQdqUg0SVrETKyuWt2brD58TfIYj+F6YfJSn0444shbbPEia3FPlmLPcX
zi/e/of6kL/t/1M7YtjQGEAB0hZCLB8fOxS7FgkA95KwxuYTfr5SWvGR+qGNAuDu
i1O7eYuwIOxJUm95Zn44x04xKMRIL/dzKqyOVIH70QxNaodlhBCgENcOBukI5m/Y
NNdbz/xGCjBdWsmVBvsCBHctR9Z/CLbZ287S69LXKoyz7NGqPZVOZbYM++cvCh8P
pAwKpX2X+rb/lriAJ6vTz0nNHLcap4LILfnUq1iUDyy/SvanWEMyktPLxk1cMq1y
To7EwLeAh8UaKHYSXSP4UAZCUood5bRQHftljNcmx38HYLPzqijIcUbtQyfCzGRd
8KhUEp/qwoq530rBweAEoj69z5j6msRt3YS+yUfHksZ5uuUVfYjpY9RzPgU69NDe
Xo/PXCC8jjhEx0UgJ9YnqzALyS285AYbtde368UAspN3xaixQyZdWiOPosec1sgo
+ZYvzM9AH33lyUn1Cm5AAXEgYwj70e82kPsffxZ/1ihg/7TUnzp6XcRkLIq2ZJxK
6magI1C7pRBexWVGJavMw8FuI9MSxNKPz31hI/33WFLoY3kk6tkveg0fCrSUZ3/S
QwFLmer9++H9zgUktRPLNtcrwbOD6fnEpiUYxwJT9rbdykfAhc6BJDuIOrb+oZ04
A+QauhwG9L1qB7lu1X2NH4mAnKc=
=NfYp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9876a15d-9c53-4a9b-a10c-8f98c044b9b5',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//en1W0qEYUSvtsdQdK+Fs+vogSAu60Ay72Lc/GvLxnCbH
vfs4Tag5Yv+78yX0YJjoX31KGO873Qy1KKok37logogcG8L+PJvWsZneaHTXMX6y
WXIGt96YhhsUE4wOQkkaiz7KUhKbMDZBT5tVEwZandiiQRFn9ERo+3mq2ClM2ft7
m6pg2m5WM1NkuKcz8QKjHrFpDLszlt1Et/E6g//VN2eXCGShFoT+U1KE5WyUBInf
cJX8O19zz6DQZdlNmtMt9Hg1CFsvoEDeXiH5K7qVCALh53Tu2dxhnTo66I+CfEMz
+kmwkF3+bxFngCpMwDl1AYMUawlS8WzqxSrZXb+EDoFdxDN4G3YqKe1tA/7MCzYk
Np78AuZLMfxK0chXs8+u5iHmR7wTQF9S20jBh3T1dhIt5Sg8/iZ9dXvGYvj8bp/p
luFYtDowyp7GOGKZ0x0NlIHblWFc9UAP8b1wVI8DzgoqA6MhWUkBQPdoXzbG9mDL
tl6qYn3OGouchyO5BUlQjpZ1mRPxFO/ozYR4/cbiZkIz8GCTLIxnMbWpHBDndxP8
OhHM5qVmZl11DDatXDk5PG5Ca6g/p3AJfSm9aZF2cwVteChJ7Op6qBz9oSdyyRvo
ayAGHs28SZ2CBQ+9TSgfKYRKSbv7Qq9lgvvluLpsTWA2Bfi4VcUv3SgAKe36ed/S
QAHymZ/V6an91eaT3/cz/pgxp78A0D8VhzpClxe8ut9FFMhwFxF2boOd97PB+4eB
L5rbiIFqsrsbSTAbKfzr+NI=
=hoLJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9e7eb931-9d92-49c7-a0e1-4077f750b596',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAzD9cWs9S/rxtff3bkc7Mw9s+oNWGlrqIShvwCvn+yAbh
mmAJqIrEXEqQBY+XEnzjmM3GQvGAAuEX7YKMzNoFiKFy1CLbJYz2338dJwreSCxM
Ju3j/L2auPzho4fPaeTdjvlzhdR6za7cmVeDbYARAQd+e0SxUs6+hA7Ab9j72wW/
RazWI74JDX50I4DxLEbv3u92sBSEXrgSRssBMdeyCfIaiL72QfL7xivLC3CcjOHS
k+cLeTy3usxV53FppOls7yFULE5tTsyl8++MQIb/gJ3F8GPfZ8RqA9mgq4yC56iL
glqCyksIRKI2FLWiwxd1V5C1/TG1oyFZzyeg/q/jEcP+98rATi4Tt40vtkiIQpjg
wPeKB0tNelSm/QSTXM6r411AbRd/ECfCVHmhQ+qT3iFThcYgVSmqELdDdd5sTE/f
JGeQ3pcLONmEPYEmmT15nBsFDThtGGakijTPJrOgaV2wI4MCEqm47rR2/tmAvQEW
npEH5RE+gV4BH9gaB4DjV8hTC6bTh4wMQxy4e09maGpTbiFnTkRDKUuqOPGmKGh1
CGmkA6SlIfwm5JFLvQaCiAuH7uDHBCtAUrD4NMsiFFnKrv4rUig/eiJBlu/z8gd0
51UYeUahpO/sLxP/fMneILyOZGeLoE2IgK5ZBCXh9Ifqd1pn6DJL06xUXDt3cdXS
QQGpzhGFXlPNmOcCAD/JHsYxAbt7KYzODRKDoGx6eyvj4OT+wUgTsgQT+Mzf01Qk
GTEw+iT1dxBlzNYyk6WeDRJf
=oOU5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a607570c-f945-433d-aea8-13bba6d12451',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//dmjG+YBeaIkgVMz4S3lGhfMbQAORA2BunnHA6cML2P5a
159GcSRWUScu8L/KixVFsQZanfryX4d6RGOoIAkIlm/B9UrNiXvDl4Wz24kxA/q0
IjzhiHvquI/LWFvs7KIPY80VJ3l/6vs04rQR9i+2N75L/XHEB5OJwE0ek2uAcXw8
Ujh35dTI5TuD1Aht+UqMHhTUZNEdfkYd1c6Cp1Qnmzic7Tj4CphUbKUNIgUNDuev
5upEYwp5bq5A46zZzlom3tTwxdBqNHpLsnORVyoOurxydN2yvG6wTT2bkq0yBj36
XQR5YgOPXD/hVGGqSOUdJMutyvUtFeEEyL69Px6cH3TOYxLfxI6Fu233pIneoJjx
gN9DC/6Qq2ICSOyPF7EUvZt9ipZv8tfLb4GK5VZIWIyL7Al6O9dksFau18756kYT
r1P3CDJopqe6HBsvFp5NnHOPylwSi38D+qlthdb2mRGw3pye3BjWC+mk9SooVHfK
m2WULvlHXOjkr1kmDNfIaGlpYcJEdjYYmC7HYXo3SZhlgorxgJVm2m3LxLjPYaP2
16LftQhjnisN90H7vcKHT+wIUPNGT0HJFQqPUTz4Dyw6eXXezJ4HgxOmoOvXoyaZ
b+Vt6b9eDdAGnYOEhWzJQ86Mb6O5iSbpHTJ+HppH12MzidP9O6NlOU0t/nel+YHS
QQFoJ2bJc5Ej+K2HnKj7keIRBcxYVg4A5sf0oZ5Ekv2bvqNHdpsJ+vMKo9ahODb/
HKBRZhJpslA6hlkaNr5Umvvp
=kzQe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a664ab83-e376-4334-a0bd-c7ef011a4c40',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/W3YnPRXFAx9IDU7cwH8ccM/btK+WMsR6S0Z8sef/WSU9
cPbbHfPQdi/BxV52m3joMEWIH2yhqvV5vcoxybSu7+Ws0hQ3Hgl+FVuRQjpsIGQP
Hnszv7vtEwFzbIdhGFh6q5GqZQhXFQT9xbB5OcSl0Xqi65vY/17iOMmCL3196M/R
jfuR0jz0dTTuPjU4p3PU6AlwifkN+/h0m2hHMaog0VQddX6pDAeLAq0Bz7jN7sAr
mIGIMCUACb269vcLG0NXFjdfPCFUjtThld0RfMr8mvWRWeK1Puud8c0scuCR2FG/
xY8dwgeakRAaA+G2L10c9JfCOOj6z6owsZ3bsHkj09JBAfqg+rbZWAampC4RiiUd
RLgvI4P8fni3arhQWrc44wbANWrkLurWIUh+JaUolYTlMEYm5LTUlXPqduQCAEYA
Ohs=
=waKA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a7e1ecc5-cd5a-442d-a459-a58d753a70d7',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf5ARWNnK/UwTKdlS3zf6ueImb6D5cDmr7uQwkT7zEoN88N
B5Kj2fOr4Lt6o3b10ya64a3AWarCpotM/7xmuwaPDEr3MMXazKtEdvXdZD2ya4ft
Oj+zvV5gks1k+U8PpU1nNMds7rpX80arjq7VIgRFhwT3/QqzQbn99IBot9b9Db1x
gbreYjjD3FnZOYRuUNR/jFmBINnXflVOUa+0tFYfLjipPHGC0p9fQOFlWfm1lhAF
CPmiC8iwUUoxHMPH5uxQ7sSWdXcwx6D0S1Pg94mBaapG+qi75r+j8kdsvaPpAHSq
HYlgm7qyvCqrwAxw0qYRK5k0M9vPKV/EuSZjq63jTtJBAfFWxJjnG6diBoFq475S
PotXSz5ICMhVTAK+Y/8xkl7Cij3V+Tl5f0KYGgbyVCFgi02LFDkUYqZr+kEwy1Mx
SJo=
=ppmi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b0496141-dfc1-4b14-acfd-d1efae65eb71',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAyCskrEwWH5w2oA7WS4w5/NK9+8r9+3t8P/kwh0V/1deF
gcqOnB/J9H8H2GnsSSNHZ/mEU+g2P4Q6JkVaR2wbZNhBXgvYuKQmpro5ANPTgoFg
r4Mke31w2H9YI0Cj5KG7wt3M7TKv4QPAC1048iMJH6K6oIzFlggxKFBfxXMzy3fv
fOEhPhZejGtJEcKvXK6QWd9ygkBdoeXL+W6Wut2mGIh8rOcMaWejZNEJG6rjryGr
vWM5egIOX00wTvRbYdhsLMF5S2n75C4ybkaRFDTIl5VomD1A6slXn4z4cg46LUEW
T+HumNo+R/+ml2qPSFEzw17t+7v2a+qE2X6zZm/v8WUzPLKhIHLZhtu2ylB5ehsd
WF0x10kfFSkZnzSaa1wJ2TGgOlDi2P2J78z32uWwA2YHZF0SYT7nBw5tUKSuTZx4
7dJnq0ec5xsNYueySswn2j/0Ji28DxWXUuCX+FwiL3r7m6FCND6WuPkeLMNXOHd5
d/fMXQwuTqcd0Y1GFuLp9znNfGoSaGQ2AUdsBp0JDvFjB8npke02O8gfF6XDyaz8
ZyVraQ3hsOiWxAi2zd02Cbyrh0zXXloSZu+mfyDgBPk4vJxEBhX1/fiyjX6D9K2q
LY46mdblvCV+1TkHUvRseFNes83ti+Gc991+3ZW8nu6LitpjDVaMKpbR8NW18SnS
QQGk+2epD2UIW0YQ8SZrIBqwZag5eGPS81uA6libO42RibIJKNWPOSxy+WHf20qW
+jQsvccT373AfuLkiWkvt5CO
=LboV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b1b67ecf-883b-4f3d-a2ef-89914919e386',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAArVL1qe1k0sqTF0RtnxkdBtNaGP014zyjhLLcAdGSVFfG
JMeQvADYfvAmfZYph9K5GKq+cy8QH81eIHeIzh3ZFzTOOABis1K9eCO5l3Nin8es
+sDT1c1JxM0kmtdQa8lpGrZLJThldg8/AWf8O+gLiFatNH8QVD4ud6h5Hoy/9i91
+6qawKgvULuvYjCINgpSMlHNcRfTNSTeZghZgwMeMAY1+h9FZwuddEhH9Ds19d49
4FZ8RAoMzBAJRRhZLO0H3ZE64Il4tW5fyIT1Yjl0TIOBEmk6IgQLQhwGGFBk+QeS
o29fXNNl+EBcutaWUa/f93oCFJST0SCtZyQW2jlKj3ZrX8E0M/4A5miBYiNmvvd+
WgqAUOjaGPMeqJ7pn1Cvd9b7omwxkTaytYTNdoxH5YaDKNKj1sKwPlfOC1/lUPHH
e+WnuVo/GUoEXVXeqr63halsDeqkxsQJyrIjyehDjvQnjaCVOMAmj4QFKuKgXNzt
MCQ3Dxq6+ZsddR30VzAPC88Sa2n4bgZ4QQRAJkhoMlTpouOoRj25kQjhmR7R/6Tb
i9D48esCm7j8jeKAG9zwbiNFP/gv55GH6WRIn4FXKu19ZiiMqJ7HIQQFPRrwsKmz
0R0QOvf1E8kIpSj3c71bakOO8cjorWM7B/GyG0rEszRsmCfx+k/YXW92PCgLmHrS
QQFmWimsgZPme4gmR0CPf7Dx5BCJnAwleOU54JifYDGwx0Z23A0X959z13RMaq/q
noUEtVg7wxQMi2bn8fT5uIlC
=SDXO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c184f886-5b04-4aaa-a855-b6fc223d20f6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAp1scNiWkbvJ8AlglIxeEqmDcJc8R6fGJoPSvkHi8sLej
GDhAqts9RTos911IxBMjO8p3zOCameocEaluDXiebjHFdnuFHEit1oN4uG14CCaj
0Yl4rlfeEzwqiTtra9G7kE59DX1lXeDfp1FLSL2BGKIBH0eh+dxicvlhezhzTjqt
DN49UvsILRomXcVd4nHc6F4Q/PrPnObF52tqWwBxRep0UB4lLxSHVyRJIpSle+wx
/qRtwCIXfQMt2r2lYHv72bUT8YIB6uXrdGMNKo5u3JR3UL7sczIxaSHZb+HdUiZv
s+yidhlLIyxNj8cwwT5zrADryK64txqdhjjDCOhJ0czwnqjFcdVjulcUMS6qYhMu
D+w+9MFE+jIoFidnaCho7d7ekpn4b29M5H1++wlFmtzHItX2n2M8dXO5Fl0+f+oL
T46c02JNGMnylSSJmZjYNq5TculoRc19GBUjwtMCE+kb5+7BZlSg8CHlktXwzT+g
PZYcFc0xoaXw9W/m12x1uCXAcyVdQbfHMqc0CIRGHiTo0VlnTVRXqdywY95aFMsR
YyZYP/3XkkNoQIEMt6w1gU092DQgOKuc0aSA+rEHK/Ezsd+2wHknldWHn34hDBSw
vzlev9IVlhokivDPPG+plUJpmolI5VN2qDYx9q+Asjx0K91RFwMX2L/lrSFciTvS
QwGuaO6hGwH202k26CFp+xXzpvDJM4R7pM/HYJmtmyQcngZ3iP6pdbkuJd1cKqWx
f/KdDLyK3iwFzDR1SEk6vKZVTUQ=
=lYdY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c398efa9-52ac-436a-a857-9a7009aa5b65',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9F6vpH+FR2T11mH3+2AIxT/iCbm4DfFpP6dy24lu3glwY
PaJqOutQtyX33qeFSqbbQ/ltMwk8n4HTIDVl4kuKvpBG4N/V6E20lm1O6O5WYhsi
I+peiko2D1ACHw178vdp6VhVWSNgTuVI8lK2rRiaIzQr1rmYqTdWBLUNTKfJGYCq
Q5qSKgHTrUpDJMDsRZo7ZzJDA+iIenvEBjrAymuYd8COwYKHYISmKeqNwCGXNPhQ
o4SZY1jl6xSAhRET22qU71K/W2M52l9AMJnf8jL/NQ9WeQioNtu0UCJyxe9gsr7u
peXv9jwsTu3rzR3jkDpXSYHAVlukHm5elRfQxO7DXMV7r6+HaF6s9CeV6m9cDH2W
4d5ypR9cO50Uin3sof6XdqrbNDUflTdE6QlImZWCdkqrxq0dfo80lxRuizdC4MHR
JrKtYejfIfMKMEwL4G2AMNwioRUw+XuZE1yZk83rnNyXxGSVPke7yeqc3Dv0WZbm
DkuXIbRXlSucYxdAoXwh1jrmJNcwD+r8Yn4rk6nDnMUYScT6XS949GnKclL7SrP4
RP/deBnC8o1KUrhM+V3f/1Vbz4smp4aq4G4r62KIOTdomO0HMlzDP6TOiRWSoupu
nI8oIGafi5d7Vrx9w3m+SN3kIuHA0aO0b4nOpix3C/shl+QRcuiSXjUvDHofhIPS
QAGNBHX7VhzmDTJoGbW3BYte8EjKQWKp9SrE12XMBrzDbmZPQAtJJvDkxgEFbg7c
ClqJAxLOj98TWVFcZ0boU8E=
=tB42
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cd5148ed-32e3-44e2-a801-6943cf282df4',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9FfK63Yc1Yq/mhZrADHgpMEtcJ9RvBsP6KPJdmoY1lYO9
3nP6adfSJMjcoAb52OiigDpw+8FwOHB18qmAAflWIB5Uiq//dhGRbz1RTV9bvbhC
l9QThNkMVhp5YbnGCDjFLiboFYBxvL450V+B9iC5w6xIXsG6k4eFhaK2+7S0LGgQ
pHDaGGsBR5Q3AXJ+PXKBLwAM29TgAWsoa3tcGv8eNzJuj63BDmunmNnqAq4tAhNa
4nowM44x/cSgv7KKL2L5iM48Txls/h+09wxOGgGqWAANoUYqtAOQo9h2wlVF0nE3
eG02IqNlwFuDbdkSGueOsRa8SR9HG21I+v4qkN3Fkvo+EsPVhJzgoczPKBuZ9W77
kQja6T8bJI0NY5iFgoOcXNcktRpKsR3fTth9qa/jdTNeA7Zrp/6qKZkNXXV68EbU
MqzkXaY5Ol4qnBGmDPn7d1X+yGtiXOo/Y8WtJ06kqaHwSOvrEwX/wCaXM55zn5YP
oy8Hkn/lIF5QZykHVWOyTcAuionIIwvehAWt98JhCdC8o6fyJ0jLUU0ODisesy0y
yh0Nq2oMUzSx0ng1PFAwB8VvfpfKljCPjxs2+gig6+WWevOCq/qlBEg+x7yD6m4X
bdxLmMXLciFGImOer0E2i5djhQjjbZBQGnPxxNZLnqu+hWakEhdYc53EXmB1Qy7S
PQGMc3XW/DHPJLE0IYrv7P1swGuoNOlx+tJfJxK5kUncstyC00rBrbCBbxYwHLt2
FFe7C1BTRStl1Nqjf7c=
=hWV7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd736d451-cc37-4a45-a70a-7602daed709d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//fgzrI9AmCm2Xp03WHRoPKeZ94S7a4696ThoagJWDB7+h
3G5KsPSUhGFea0+r88E81LmKlOcEyDiHD8MPMRBkZSokXJj5+tDvv6B+ig4DmN+B
PRNiKfmHXIdStRSqJ3d1XwmAIi7BVvlAzUAENj3W6LBfwM2RX0M1/P/gdxJcdhiu
8QXR/cawbZBQ6fnZoODH4kSehF1/D3HG+1qbXcAGS+O3JwoAyEiEuy/PiC21eOKC
r1bKn7Q/BPb9EucfyHyCWvvYN8viu0H/sobl3NMI1RofIrsBGShJ1dhMQKQp8L1r
sLieZ50FdlXZf3M+wNn+lXmTlRiwIl6NH3efaY/oDAPoICCBVSOMvjyXF4ozjJgG
sv1qWHehC6YhGkr67bny4RfnGOt9JdkZrTN6Qzc9/PfMOYHH19x09SS9Z9P6mmkK
ATRGdV5+E9ySYjXFlBPhqGc1qg954M8+BpAUZB6W77YDV+TVMAut/DCm2z3/kqNE
pIdYL/av9OdTxU8u9gFf2VERarpW+CJBLSdwLJ6rWTXPJhd2FK6z/GdCdDSrn9/D
aETt8p6248cLsrs3hrj6JAn3f4Fg/2N3gVA0d3q5A7gvzvRC+ExpDylYP0cy1Ohi
dDyC7nOkoEdUHNL0jnrVo3Fyv/YZdl0pJujJCjXyETNOGu32rSrvf1h7Zov7QgbS
QwFN4bKXQL+69BXyjd4M/ZUDWNhcvilB4rgEQ1P19l1k6DKUPng3rT9iKqJKAIQS
xCE9fjlfJF2jwQxw+20vZ2hGmo8=
=2hqZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd8dd8161-8920-4ed6-ac6a-5eb1cda6efa7',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+KrIreBzVu8PmKLHVcYsRBZXCcB/GnE1vjOOYesOPOqX/
9raBrDLJ8w0zcoAeFGmOQL7cypOsUYBql0QKMDDce4uK23bGhopJ8159HcfVVLR7
tUt2mEWjjnAOA9rkuAYkl17+orIXN5XaOgE0mrZZzdsODTqjNuNRtPcAxVrHAWNK
3DApL/qzxljNgcqn+5moP4v33fmHBC+vnXcpv3jwuZs2vUDCIFHTdZNAQS7PAOZN
xL3uKRf/PmI3dz3Y4dWW1uIF1dzSStO3/uqu3wsBAyEJEDEBqi3/5982It4Xfalo
lvjnNTyRJmsWbNzv5jhn0XHtssINKEWd2qkR7I6m4QnTPE6ke6TZFPWohiyE0lfY
FQrVRS92AOvrxKqvY20GJpWXEYYGk2d6XQivHRysFXrWC+45GtmPYRjcvBlgcD1V
dUYJeZKmTip7iBGazUsnAaFGbAjLNU/BAAXeySGiWCNHRAcDCA750qk5ZfufsZFS
urrPwgUOjqEec9B0Up7nGew21qhwU4UAkvFdpmxWdHI3W0VWdstswB7OdyULGO7x
XgiV/cIqwYwJrww4ZoQjdzVIjK8Ioutr9aUEkS7ke8jzsdu7x0xZBk9j1HgTBE8g
UEiQMYCAD0rIU2PY1R7kuubRr3T3J5iAoafMNdZGSjF00qwj77cMAVp8/mntPwbS
QwEiEFzGeVIRKjqJf8VDnsmuvXoKMeiZ6+t2e0+8OaGCaeywK6O0BS+33C3Q9L74
Gy738I1CsbBfbpBbHySbI7grJfI=
=ooAx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'df38df94-ec9d-4387-a089-e6e3db8f0ca5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Z12CGUxBxUZjZzFnRq5hS7N8ZBmKLaUo3xN6ilNi/1r6
M0cQs1F49KC2sKz6C+q1HKwrLwlgSRZGj4mjnYhCguhaovSASZxuesRCxVjhDdPL
x9R9vorwtqI1CZi92M1F0S3IuxTHV5Mn/SukcVRAyHpFw92gi1iqlB5EwEtsDOgb
DB4vDSlqK0GiPXZuPaLni5aJey1sAOktn8zwZ2QlVGDIPQbj0lVqFpMhMqQ89x6b
uSVKwIJRAaIOg2JPt08w8bo++LxlG85lZdEPSfY+wXjUfWfQ9sNthD4aIxv7RizO
+yDTZKaqlB0xNo8lcYcmhZyVT3MOjnPYPuX3mqiOqBmqxpEl3nZ34bZ4imri6tvC
FaJkhlzUL+QnLJrpbWo4PNA0i+mbqkb0q47eVkmHCVSrTA6rljAwlKGpTCpH+Nx8
Mu/sPEQpfSl2hzdfUVovX7Rlf3Z/gFe8Uzz/T4isAcLgTxihKbTaSdlTiRBxLAjT
BFZVnTv4MpIqc8H73zjXlcNQE9DrggTs8OlUF/KyHXgPY3lR2lUgyEifUnHcGfnO
B8YF6EG42qeiWAxzHP+sth5UTULdpu2YDh2XFkW30D8HO5SOi54zu5SFEHt2Rx/+
OTSes6L53dtqt24D1C9b5PyggqJifEp6S9yx+9jkmY5TtkzuiykAW/Rxj31NFuTS
QwEWWQShNGBmAVJ/Ytpd6LmbNi0sSbOccr+eJTpo3+JLMzfJ4NfGYzBnj0xgK/g8
kjtV+fveqmjubgGtMF6rq4HqGDQ=
=UPwn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ee91fa89-cb5b-4d31-afb9-02f9867e854b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+NDzOEqTlsaH9/SMqwq6TYWfnZCHhM9e2YOkwwJsLOBld
7PMNAJx67sGvmfIWzIZKwTnreEHeY8rkDUl024btGvjWfSiL6Ni9qFjW8zRU4A7M
dVEVfb3xX436mHyQHaP9ir75T2AAeaySLVVEF90+6K4YkbMMhV6oX9xwEc5qkrmb
IYwy6BU23lXukpeKLyDF0D2ZKPPcFxpsBcifgJvQM93MVriXyBJiMw4+amEjXSZn
lWoHKxq37JaV6Sujl/wXMVjDUbz5mjjpzKjIEmIBu0VaY+2wigyOae2HjNPaCHD5
UscLKyU7tx60xNA1C2LuVna1E1Q+NHLSjzeBfz5AYqPvRb7EBNCL+00K6Gs3aED+
diK01iyaoNXyATm4/uRUYFvOHrR1AoTSFph74HKgSj5W1xhZWvBTJhURNfgViSTX
o5cFSS7/ttr29MoY6dZmHqGqUck1rT9IYdtiYLWFRv8tvE+kjEbtxhNyrXk1b6+Z
KCojKSF+rLwkhB4ayJzpHg0f+XqopZtGpALGUdcDeNXJSZ6g8ZRvI8ZVT+5gGJBw
dGiPzPiCHbyqImqA9SS3S28A1jF3k1xyssXLTxE+IyR/I25Ig5PQJWJkl8M+fPhL
s/DjULyWI6nWg2sx4TEVbY16dkbarG6L0v6fA420qfbtufVZHUMRtLkpklrl9TvS
QQGsk6nOwnnFEHtFzkTG3BWucV0qtGeffnoxL9jqt6QzU7FI0mTwBWAOHyjiLYVx
m78wtrDSj+jn5r56g7S/TXkd
=HTWV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f3e4e7c9-3531-4e86-aaa4-1e0469e59527',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAuf00uye/mJbs4LPYEgDa1Z0fmFGy65Y13737Af4tcFvs
yVBBLNaZMZYSl2mcRXKgSAN/vzT8EijPkhEfuAEtLh0G/BTb7kFZMD3Lu4bsDEme
/ZOJgwNACzwgKxzICmT8/2SzULVxCbR73LVKL6lfkRzGVEdPf+8Ly8rOk/mCykKB
mqGgB02JHqJxRRxtKedHwi49q2TDn8Z++XslI7TpHMhuOgZ3KUh0e3Tmx5mREKJQ
apf5jbGONQDbxrPq2rk1rgJeMA96Lo7UwZ/tgECNUE/di+FAcMxExIfprgjMNj5o
JudtUrA27fvX5sF1uhvHfw9W4S8x05OUxdLVVSWaMbsXfy0d0+Hj9Aja1L8XPuxH
S9MhOzr6iiQxNEg5shAuVFvqTHw6jaBIu06SXGA0+PX74Gi0T/+N+2DQNKtw9loE
ZCwNotsJsW2OpQFuHXMxj07N4NfxqrERpLoQ7sVfElDe2PghcqdJWjOOgYJuQByA
iu4xkm/CGvLHTz1wawCF5g7p7Pza2lasX16ltS5P+0scR9eVdZ4rtYkfb2G/+6ef
9T7V4ls2XurlWEl0Q4Uh6OJLj76kTyGaLtJ96vg5rPMl8xAeFk0CmbHFLrnTG+cg
qoGdAAaAzdBJPPkFecnuhn44IsA+3UXMfLjNn3n/JyOYs9c5y+VVH6Ar/ITEn/jS
QgFUin45YtLIfclaxuwGmJQLJUYCDMy18mdH/85mtrLq5XRPsaJwjjdUub1ev3uB
TS7faSE/v+Pvp8SRZELq/+8Oyg==
=FQtU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f7be29ac-1188-4b1c-a3d2-9a3863b2dd1a',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAArXDL3QVL6LrP74+npqqYtJ92fg2KS3nCRrH6UWeVeokV
wrfyOAjCAIDZmwNQaFd2tKy0wrtBs/qYTJoUO0fUrhEl+VucdHMFiOZCH+g69ZwN
17INo6aAk3uT7OcDLIN83nqg5W2ZrElfOlSmQdOwERJ/iH743vaSgDDq4IFkOujV
pnjIJNzShW1BN1s+cd5zbixnN3DTJoKbA+r+kkng4G7DSQKaLh/609tYK5wjD28z
57E/+ErBvYpUwyI18t8JSbEz7EZAYFrnyjT8sA/mIBDEdndX5W+LcmSS7gGhAPKx
JdiYhHbZjLuH4n3o+EhYkk+9H0ulhbbt1t7cvzfPOZ6DDljKZYD0PQvANnIKpfG/
0B5kemzlOwjS1Djx3gSsybZLBjulBSCxTAb3qTMsamkD85iU+6EyL5YPYpvGvXNI
D6nDSwhvzs/qvrgJkf9089QZpaDAL9MZkBC8TptLzDUYSAI2fLuj6QjiIPEzTQ7B
lpUxI/ydg4FXpXfHxU8Va4b+pm8w985u9RQxOSWmnIyBrUUHLnxjMU97akngKmbG
2Tupg3ping979hZ7NVxFaKhaUWMCATMX108DrXI0ut9MRd6yYn9oEBcpyMYnfHga
ZotB3v8aQomj59FFFPGq4zZWATqWW2meoRHK7MomMI5uy+0FxvlqMOdWWVa+i83S
QQFeucxgEf2QGU2WWKJPUg3MgglI6ElPBfXoaSAuYCgjXHtAxZmF9lnyhuM5IBtw
aB+zGfSasDK7KnSK8D+ybSV6
=FK5t
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f85303ac-32b9-41b5-a53e-416264864820',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA0NtkGw01uCryUndCezh68WRniKjhQPAWhNd50GC2GGde
h5c7PsDSyKA/tY6crHpHLJLc9qF5R1mO+ieH1newFQPEzms9Bv38sdh5h3W3fYN2
sL26sILFHXaBvPNosw6GJ3+11LBmRhe8+xIu6CLILeLqA2OLvo8UtfEefw7/CcEL
JtQTkAoOQs8DVPK6LUqYb8Ada9Rvy/ldLlEVw1fnp3pbQPrM8Q2KyTOL8p6O1i8Z
mtquJp6qxKaQ1/NOFz8x60cwq39RvhzU5FfGtLpxKRZyuC6SxXI/AaAf3OSmk+Jq
7KcuSViJiQoy/Du/skNqiE1lJQG8kSDvZi4KYaX1g2JIwSCFzIHouQiIxDKJFgD2
vO+wPc0f4nf4BEb20LNchXuDIkcEPvQUP58/a2L4JycgIeg6btFCqWhpEPzb9lrC
99YfSmBp22AxPzn62cbONQ60uexzrn1IDUaK3e+Rb8HA9PPmFQVkveeeIKV6FPm9
Ml0y8i8A7U9tqwG+NCb/3XNjxStQVi3EsYGakJmgJF388PfU2RAylTj247k0mBL0
DnPB/eYYlS1VGdBBYrLNb+u7iWgqYX+RYQShSzyR0JPrgWidgmxKsh4K1p646Awk
KsnFLxYezpFxHXHYXW0UbAzObkKm/owsVgvI03ixKOSD7I/gn0SG+V+lWiRlqB3S
QQHWFEgk68vbJLZRjRmVgCnNxSjXyjRmVphJSNWr+RTBi4qGoAl9Tcizpti/J5oP
oyjZkl8+5sXFwpS0LzG8i0gy
=PXo7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
