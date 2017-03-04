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
			'id' => '00dcfeb0-d774-4015-ac97-05586964c8db',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//fGgMmpFfJzXTo26OfZXWW8CCEhN4Z6Y8A546eaoIZAOM
f0Ltd4N/me8b0qxgLku3YkiOiAxi3yPq6BaxMZYFq3+jaRDkixaY9a9qw032vICy
31jLIvgTDtI8ovEyD+54oERIMx9wY8gYrMTHAC8QpBnGgcf6smFNK/CMXgcGhj9c
aJs6BLLjImT6viWE9cNbPdYjgOpOwWmYP9zpxbjlpOoVDEvHeRwDbBOWbcs5RAFL
b4H72lj+c8mGZBu0DUmd0KtlcPijYs03fTPjQKMchpMDbYFNlQCx/NicfWolMeFJ
IKJOx+IX9uX5XCboK5ZjVXLnNIgCWxlUIi94EM47GAV7z1Vd896sHJQD1M2buQVt
PIxkNsce6tF/9f1Xta99HrxFJqCmSOY2Efv3pMJZsRnVv4l++qLDhaA1w6D2C+WW
ep3dpCgxYRTqt6d5dQKUZGXKFtW/LJS2OgXTph2bhBMduJifGJIcK4ZDhOECgVWv
XOwUMfvDfrPbJq6fRLF0Jh6Hizy+2TUb1LEpqntB6vBrJO3CNAwAF5lO63UZrFkR
loQ/VfgY3oQ0bcc3XSdW55oQCYm//wDyadEya2WzJSQkffKemjzpyBap6t2b8dtD
pZaxUrdf1JDebfCoiKXJKKNRQEdZx1RlhtC8wllAgO4kWi6rggaV+6MzuDdbVTTS
QQEcVQo3mpwD01UTJSTM8X/HLbXpEGOQwTYqilJhyou0oP7/pNC+FPoG6Wj3+2JP
HbfLwUDe6K0whXZg0nG7jPoU
=wmhw
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '02bf251c-3907-4b01-a1e1-ce8bdca2f12e',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAjmhsC3TBUifBOzZ9dgFNzfJ/V1Xj8y160LIw801oinhB
LdRJsAXLilZ3Eg73H7kUA5oFmL3Izo5k3O9wUb5tWAI6DtjXTAYqPfd8QgKp5yLG
NhGSdcHcrcDrryituyGfb8iIVJgSpiKHRzm0SeGrKUSjFYHukkPJ6rq0HSSijsAc
euEFGINrz/raWMvJ76pKCt2XDOAi9nGcltuodfUa5cJwLYSkoH+GK5fXfTId4ck/
ejoyQmbmcC/aZ5+HFf1FSCfawosUGG0fleNC3IYBHRTopguCTGW3Csml6+SN7wCI
W6wJ8Ka4+fOlQxtI073j05H2/800OMMjxszdBc5taPU37RiACJctQNpEejg5A2pw
jYFWxEL86p7nrNmGow4sgyQupjoUAXzwzTuEcWK32628sH714OkAjAde21uGMShQ
bauYLMvs15VBq68u2o2IyR/e3y07SbGxFEFIcMigOtqypU1EbyKcfpEWpUr67aVm
aPH1UYHoGI5012fjEppEkh5qZ+xeL0kpducIGyVZJfBhrCRrPSeyBrCh2E+EPtcw
7IV3V9nNbj1pvfg7QaJGD6KNLOt468rfBz33y0UOSDjk7Ps5H6+MhP5PD/Ues/Qw
qExbu2oVTnNkoMfApngrYNPzgl7j5UmCQOughVfSkpBAXqkR9nN2eyjoT2D61d7S
QAHjKLzETTweFp3mz4r70K7Qu7t7xHQhzG9XQQEtPT/nj5/SYUjGHJs6cL9TIq1Z
lCWULIUDtlA3V8nVoRBu30M=
=X5+Y
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0384eb5e-4442-4404-a58c-6dcb1fb299ad',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/7BvSDIBGIgsYBGWIO0x9YOpqbz0UFpeBSPY6EvowV8ZY3
M1NgbwAAK+/Ai9HOg8oTFilZMYf7DWpRsg/Wgh18QbD6rKurRst0R08E8UYtYf8V
pL70xoNkrSakwB1kV6qq4Xj+9YIchzE9jwpCz2GLaid4V+rLW+JKYxvzyf2Bn65u
xFToN+a3vPq0ye4Ffs/p663k5sPpz2egvLm0aVrhF0tjTcHBYEaRAMoNzjusVfYU
VAW3qF/J5pM53rIwAIH6+KDJNx2vMHJfT517blwArN2d/9Dm+tQUps0Jeb4mbit5
+bzZftrQDSaJm/vbIV6yIBZaDy0H/GtqX6NHu9hb9fsXD5YBG9lqjQ1s1BC2mGXn
nw/9mTgy1Cxw8PYPHw9lqwff5AhHUvwHDzt9ogtsQT6DJ0DQF7PrAFrarnYPiUZa
Vi6iiBcxYad0DDnt93+VWWLqNUUF+3H/VU9FF3VrH0id3fSEnpM/FV3oPypVHj1N
M3dtf93uyVCAN8/v8JEYhtn2z+a8OHoOAD1ME0bykXIdT4xphksR9FT1Ob6etNz5
6/R6oVmnscMEDQJjl7eRsHQhDBYxKfNCi1vkAiF+WfXR5DA6GH6v38fvngLCsvFW
P3jfO/qAnB5EYz2914T4/DiarDbZmqbV5otkQjJ12l6yFIFihXHEUVVL22sFRL7S
QQEr+LsCzoSj8gW8qajKew9Nxjnz43hwYBNDIhilS3qCiu3B+7hxYVPEN9zpOwNZ
PJS2rAaec//Qy6d4zH8PVB1i
=s916
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '04393371-d2d4-4a95-a919-3b02dc44a01a',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAgo6Elp1ius5u2RN+hffSuKNACYINoTx3jv/WEeTvuCyq
1EedCUYRIT1Zws/Lu0ZZObhFATZKTpQaGyfrpBQEeRBXVCHxAitQ7+EZjiR2BSDd
zLA6/aLqPzhpppkAy6z0azlJRPXlqqi5q6XdLIIqJJ/eW+Q62YBUuL8gB1lmCXxV
mhV62KkZllRS2yvuhwigkdUENqb1ijDoG/e7Z3g/SiifOkfSwZ1KmPmg0KnZtrN0
u0JAQlCBmTHnuA3mkk8+Pu878LVm3GdfvLWlT4gj/oM82jlUOwLEvpGO5lzm42mk
a73SyuJkcyCtsFGLQQZllwLs50l+N7ufZKY1ot7x9iTuFADd4fbrFMSrPwapImC7
xH9+7ep5RoR9On5BeH6sE3f6UqIOwBr6Egui9sKW/kXcXn5F0RS5h6fg2JjCcyFG
JeE4WNS+9gD1bzaHI9/zD0e0YmMJsu6r2yUhGaG1zHHV81dDHqsF+/AW2FI3CCCA
J6YJsfqxmQhdjwAypaBKkVv14F/3ESHClX6Nwubg0FXOTX3CcYHYnbzQ/E03qJs2
IK6PS5sTNdC1BeblizBVSzcmB8L0D65QMUAZ2BgZ7i4hUDpXZ5L+alJqYDz6MdoI
zxlHD91EB/QpA0wzy/17qhTfKM3oZZMvXarCpIebjJVyGScu81QBfuj0ibtNMejS
QQEDYuBdNiP/MBAARgqNuIlyGQwiY8lS6WGwPVn+AjcHdIOZompcAPC0kpLxw1IB
dWDrkteUJteqEuxWzD+wRSk1
=QMZQ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '04d07594-754c-487e-a8ff-3be5bd01ce0d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//e3VMM5A95apiA/zHY7AIDrBLz/FTttya1OgndXzN24v1
VlTaWRZF3THAY/OJVXPH3BeSB9IZXxiU4tEAmTghm/XVJmlkKOJC/C3MxCQ9e13M
48AVTP2Y5bXYsaifrc58vuYGb+84Wi+kv0lNm8CzND2R8BJHzz2nRwiYYgMA2J88
fodxOQ+2gKZNIaipEGHYbbPajQ6eiJ9cMMFJTYUKeJZNbxKvMy/XMChh9WeuKhO5
ezWqZkNrtSSbEtAZxljMWFYWDjpBiCKFinOCTVGS7VgXaDxKEbb+6RY8f9HRZdNb
7ijbjU8ILlpG3xgTCgw3VfLfWMGerIc4UP1IJr3ddMrBu5lAwR1AY8MNw4nwH3Zk
iAr4JgrE667DqnaOLWgp40KOg5I/Ad2cc8AOh7hYvptQVv3qBGaIgvnhW39SChvB
RykwP3O/qQG/0HoUnKko+G7Y6bsu3PXASwh8GvtHjeDjRPLHsWCkjLpunfprAC5K
oJHXmhmcf6IUq6xAQAd46aSKbBLkFtr3c8gMsxnXNyl4I9BrP2uSld2DEkE3Bosl
egTxzHBNyCX8KBiQk1nuE8mDpiDL1YScKNWnxx7XJ8t/58i/WWbxMvkqDhnoMzKP
5o0xUPb3mNWqNvtV8lmu8NrF7g+RlImQg0IFVSmWicKAfEQOydYfx4ET3iqqlAPS
QwHRWY0eoQSOoVsCcg3p/Iqot0hoXYZezIhZi+4Rv7NLv0QXGkCTfYmecLX9ULgZ
VrSUfFQtdW6f7spWOJZGWspQsjk=
=TdTR
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '05d772bc-0edb-4f4a-a137-99f9498a1752',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/Sdx5ejD5G3pPkjHmMKERQtqzjiP9rAEajbXKqC9VmoKh
fW9/5xV7CaEDHcIYsVPLqvoKJNvlS8kXKKwAo+QUTff5wXH7Un4QdvtA7jZRQu1K
NzZDpH9B+Ciq+UUt3MGD4xPWAAYycx7QS1wbAR9riKKRQ3AAY9Jm+IQJUjJ/dm7l
5F5vgLN7iEfqUvBgxuEusHk+8y17nRppjpENyodE0Z/ZrVUBDFNJX3V3bz67kbIM
IgBrQC49mB0Ruse2OjVEYVYKhFMDNuYHUcKccF3XvPsn5EG4fSHTBoY7lLsXH5w6
odhUx6/9OMaSZLOt+K+5uaGPsoDguRHAqzYKBTI099JBAe1m0Icdy6MPHDROJqE5
4cDPM63qzgjFAqu2nHFMmBfz00chtRcHiioMTPOEjG1T7ix51kX0JU1jCGwOZPGH
u3U=
=Kk7P
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:52',
			'modified' => '2017-03-04 16:10:52',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '05e058f4-3756-40dd-a61d-cc332234042c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//aAX2JZnHWg+sOr+xz3lUcyyiaB36Zj51038MApQz2v/u
2HxzApAivzZOfylYx4uAWYugv5fMgt3qZ3Y8GfBP84xw6tUNNbdGJ4w80WzciwVN
lFcCZlLeMgwM0jiPudAD0CEB8Z8uwdGm+j7+TEZdk4dxqt8oq30ObTHb42RNtWeE
xwoxC7JDn6mS57T1N9djPuB3Xojp8Om6n+gjlO4/1V2Gs7ZbvG8kB2wAhXTieks/
+wKwKlXpYg5NjpAfvG9IHEnHeJfe5lM3k5KW/Uwz5HUoZrfLNiIBsAJGa1GHvcjX
uC2WNg0uL804+YE1tmZX2YEFPWalueqFAH3sr+2i3hzZW9R9LsEGra9iWGUhZ0zJ
SewN8hrOIQED94sdT5pdo3RouCbIa2otZRhR9rsEOKFc+H2V8YFF44i1uOZSKIdR
tfZW69+RzZjbU3cuq+qUbRXpkM8VWWp2SMYaaGWz1pl62OAHxe/J/62SwKXllQVS
IFbH39nNYq6KGVjEhjcCXtT/Rgcs2qrOHQiYxdAq0JZp+cMCjKHuWXSZYThZrhAA
PJm5SS24UmG8JZG7gImLyvLkHLixaetPmledCdYjHT15TuEvUE5KLN7wFg1li3BQ
JiArpVeZECrRK0JO8PL8B3fK5o+IU35CcCnnpm2dTBaFEwO9+PpB2jyaYMW2hEPS
RQFvtiCuy6wVwmIDEpteMsy+MydNqxvWVl0pAY1TEi3wgn9ZlUX6ifAFaw5edU38
5SIUEOof0da8SUWPSQH0YCE3b7rFvQ==
=URH5
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '07d42fc3-8011-4ba1-ae26-357625e4f32c',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+PSCgcSzTStyoLTwhECcD248Od/Oz3OTbQhZZGvpgbrH6
pqsYHeLRCqsWCl58nQ3be+w8bLGkMyWLmYdso1Uv1n0dOs5ldyr1IjtZLErtA4lu
np3JYPUbODisOib90FCNPKPC9ewMkoOipCSeaqHNx+pDJek9LRxgyNUht5VgquaS
NolLIW+NxaJFKq4oQUgZ0wwvZPRp9AQwfxmeKoVDpKrDFVUDgNNEh3fg1Mrn4YWt
X0KH40I+ZrvLnTRig/8Wlon5Phb3JJ0hDMdU4pG9yDVQpsqpaEfN4y0QAJVeEple
BqM6XEPljHITQxeX3CzMTgHx2MWCFe36Mc0Lx/OeUgNmj4DqLQ8/ULu5unvbe3gM
agM2ZLff2z5TlEvNZLslaLliWoG+OMM1LuB9wqj3WlDf85s/9Jas6FtFdH/roVTV
oftXRRR+kSNyKHex1Mb2jbxZ9rB0iCbdJIpImx1gc2A9DEz6jAKzhZ3dEVBAUT2G
5unWYGQibKbd6i1BvnEM2hGg5cb+CyUuq6e7wZYqWMhxN8Ff6s53GLjmGG6T8VFl
RNvpuZlt+LpapV0CB3EKKnStHRQNVhEOYx9i96eT0c5AIcAiEnNfInxpw+acGras
eUZ5NUy9XlfIwP1vwsl4/z5LUDuoziVpGP3Q1WOa0T2I05w2hUX3+hQpcl6WUKPS
QQG7kki8+Ij/eerq35MkB0H49YtJgtjyZ8lYDSIHM1aEnQwwser4JRYWdRR9iO+o
xl59r1oXf+zNRMs2gy+rbnSH
=x7/P
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '09f32db0-f62a-445e-a648-f47cabb035ca',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAoyjq17gis4L2wNhorvYT4vNZx5hNcl1gLihQ7wKxnHZ3
XT96qLKUA8Nf7jdOrak5ZLXKYGs75bCOs/dzKfQSwPQv7zy/kTTDaf2av4YOzXtE
O2gRNpbDjIAF4vIoeGOjZo2eRVLyrp+2g+3AtHl+L8hUL4yH4jMAORB9jM9epwkR
82SIjaPkA8OfppVeyGA4QfIJjqKlKF7Stmx7GTVxIcCzQYfzPJajUR0jixOVrGhR
iOLq6VfnTK84GC7VLg62E34Opw9kfu+QKYpf5RbxRETxDbQae3KSj+iWTwycBmed
SbPbaRKizTb6n8fYiIOf1xHpCMg3te1YhauG7MG31DnhccOlfFFAqzNHs/iQuop+
HO08VPep56E+VX1r1jLDfXxrlk8bFJVUlrOUXW2WHiVEZy5gUG+wHJQT9LUHd+Se
u0RPuxD8sSTWtQCdlnqAiFfpBo53eBTtq43mVJc8s7dgnmWZdr3OZ/x8vC2ug8+Q
ZzAhTvlSwcHKNAETr5dkUKp785gAcC0WIcWoxQ1XVp7f15AGuJJ1ViyyROOeQsrW
bsxTvz/rhFEiEluYi5ox7IKCOCHaQQwRusqzj+OCyoNs6+3vF8XufqCLnrvNO3aE
kEqO5nJ+WLlccb4h784SCtdtLe2pN5l/ZN5o17hhvHzICwJh7sF0vBj6rKaII1PS
QwEScGtdvl8eHKgb1R/0cbFlI18QOueMK3/Fb3FpzVfk3J0oa6D2vzcWWTJmvCm8
WbDYKsx6XeRBajEBMTqmPTSk8t0=
=hDFt
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0aa3e701-c8ae-4677-a9fa-0e49ff420169',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+IOe2iiSFWIalX7EW/ucDIxGdrJxG6V3ivRjnq+tOEB8S
MsbYJIGUmfV8jgU5+yjl7CBw28ornlY47cW+J5SMTfqAvF7uJXtXJXG+/GoiO2RJ
91hDPRrcaMzrQgRwrIVS76yJQjrKuUHhJYlm1A4ej9xaa5SN3OMABgQCW/IjJtHV
SRN6yaWyBnkYaJA7yHiC/3j0Uq2gi3XhjEzYQlZ7r4+uPMonqL4cDEn+O3iszbg+
uRiqE/C2nkqNGAaLZPBFwKcqaKrsftw+OUp9lAvcS6VhI9D3YRTg1WDqtAs8MqlK
ZZ46sp2R7IyCB1MMWckFc7e2NJ/0iHBJbqgnHoyF9Ovt7mekwfjTpwEOHejHMqAy
7m+s33t+dGIUg8+RyqcuvPSVrp3EUPFEsXs5TR0O/ZKDUyY2PUl4qkNF6qWbnkjJ
8VOoDQAtMcPRnH38nysjBQrH9YHeYL6Phg1nirUlwH4bTpqrXQX/ImUKZ2r4QejX
RjD/itHEw76r4Z/KH4zlZYvVn3VscisdxK0fB7C0N8beaNCsVlTEsyxe4sBhfIcg
LDoaf67lxHy8uBmsfYmZC3DhRPIDuMDX/C6We+GbZnS4Ztb0F7L0aepBz7rLe1WG
nEXr+KMI0L4R7gpsGgy8fHF86gwUDTb6srgi6c47gEw2YiJUWVEiMlHX5BXkiUHS
QwGa67LJppWqy7DABytcszw61Zx2Ei6xSsuufIRY3b1OlA7BSprerudGM9MVkn/3
cKP+QKswd8h2kCF4Sm9lYQQpKlU=
=AJsm
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0b9b25b7-0de5-4c8d-ac4d-ddb9f124b75e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9HaRbNKaL5u1VPCggRc7Oz4rJgRC3b2PYZy8YZSlDEkGv
747pzUgHsaZLlhZxbIpUVRndVA4RKyc8AeYGVgHy43PJgdsYZFWof10QcwSZggSR
trdrmXG/QbHAjU2tPufDLNALH++KvFs3LPuxbBgmlABDbsUgl/bm96w35bSkbIi7
KtJQXybG1violxnT1R7/WfoAARfIvI/RlCSkQzwhDjOYWwj+4Nly/50ezeQ0+dgZ
18XGKvC3a3KOlt8ERhIyE+5cA6m05eEv/F0Qi8W5S5L5ISvRHvtXfQs2409tUw13
q4gqgvMZOVZN63mxAvErW2gStr0ENou4i6f3Gyl8XYGSMkVA/g9NBYhxgTXP2Af+
LdcmvLDdX732G+vknlgTfFAWPz9G2VgrlSNTErVot2MjM/LqUnUtLMUaztgl05vK
wROhB5V81m6Wetl5Tp+fA57uys+zMuLpQ7WOv8YnFD2uyX3ejpQV+eeNYmXCJOjr
1aXCW3XGAKmhFvW7TrI2DfYk/ehupns05TOZsVubYvCD78llZPFHPxs5LIylnsCx
X31on/f24G/dZp0DBCrr4i1lfM0b+qtwBacAbQ/NVLe7NP9XvguX9qDTVOwAEqKu
LOZvyx+zscLl3tEsMbCVo7KN3RqckYdqhMQSDmP8fNL3gWjOvOhGWm7HXA7TTgHS
QQGCG8zPiMyi0A2ukJN6zcYzL4xauglOBX1MUwIKlgxC6sUl6Kmlv6CetO7Jz1Kg
ERGBC/aNtfeTCiYMtGrf3m5v
=i3tl
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '10362561-ce6b-4f13-a177-95002c0b515f',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/RAmtXn0qHLgaQDLitY0NvsHIgfBzeNGgdpVJvXKfip8B
XNUURJjqgiaFzIHvRNuJoR++fvMARiUIeyOXIthUvDjf99XgKu3uzqp6+716yyYG
2UKNTAYaO/3TJWh2jxBAPjgMVcgBCeSpeXmqrLiMJY64uUW+8ttmcxqjbHFYy8Rj
fttE8T6vIjXNpmyXC1ujEvQmKjUaNtZIck9IaABjlexn3MQXObvWfrJV5DwI1dM5
83QVfuiECIn8oA+u2jHUVLWAPbq8pCepEHTFq1zbZV5gGBLbzdxsZuM0yCU8oeBK
n40+0qO2VKxjiTsNwRjkWkRljU0UnbDBuzbr7+44utJCAffoRg0b7gD6qX6ThqL/
54woUJ5ea1azvjAkj3k9mZidaKCoWNyAooKnGEgILUsJnIS3wo/gvZum6RcLAKK1
DveL
=q2fj
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '10996e8b-69f1-4336-ab4e-3895e88a89fd',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAgvj60SBT62+I7H04XlXEMne2Ra0nyDf7DQSvCnO8GxFn
0Z4wIvO3g6mi85nAfpI9SDy5G5aw91WwrwG0dLUHlG7ou4MuutRVl3NinbsTCUOa
H4lA82FR8iYv5K+kYtVCq63eo0BHOvEfRWvRxuU2mjjIl9myujAqiQMEpyRrGRlJ
OHXfX95jt/6yTBlBHOKUkbrI455APxCqQvNQfAbfMnjfpx51/N+nq+YnLmTxyBiC
Uya+W94F31XOKgyOMIUUdPwdZxFtiD0VAubV6Y64sCAdrqb0O1eDyiyhbpFed9WZ
0rHIh/VrbLbw20W+H1pkOKSgbKMXomJjPlz5ELnfP9veHAVdEtSSBiLKhGfyEuSt
exPNkjLxLYYHr10ulyd+366KjRfLYpx65Y8gelQ81YJH4efAhgkYkcOKwE5FwaMy
tSarPiIkolUQ3u4vfFcjXEettWsoFN5JbNr3cHUNbI5RofwNZdXLmR4Hkt0o7KeX
BHmhBSn7I5YXJ0zeYrJda9UgWJ81W1X3S4Ebi6OqokWtNuTBV/q5rz76mpqxzI9Q
yLQF0GYXqm2WrUegXIOj36tviCX07EnaIIGs5j9a3XdXWtXRba2i++xmWsYTcqNH
rjsNr1P9i3lcfs543kfcNFIusMSc4oDggQUy6ZVzCVyWSm/RXK+gf/0w+Nclp0DS
RQETqlLQnxZKG3TVLXM3LLaeqfwPdfPCf8fhGtN1SiaIi3/W4XcaK3KeCdbbLA9I
hjZEIQKnxpxP9FrsELPmVb5z5Lf6Kw==
=yIcT
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '10facedd-6506-405c-a4a0-8616cfea5212',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//XhijJtbR587v6HSR81GbVzSiQTYvOuoQGSzCn6FqEyOH
8zsakrljdr4EDObo2MPvVfef3S3WqeNI5IBwvkeCpF5+rDYudUs/r1UOh4UFT8RU
dbPhmZbVAouynu7Tc4zsvT7iMUsNgSVX99so3Yk3nWTbT60C01igNCD638JhyGXP
k+1nmo3APyJdcbBIFRfiH3gsIo+5FvGJiqL5M8xAGPQ+0pqBYkx6SlaYOuCyZN7S
myGDAa/nijpKN56r/uEh4EgDyojuacAKuOmeKhptM+VYBXJDhxQkjj5HErEQJfzt
z2uSkpt7LDr5MmhkuJbPUDB5MaH3vK2DJyU5LtmppMRF/E6bwGH38t8aRZah8E7i
S639ZT1AHhO6f1y86nIgyvmiI7HmNa/lvCj2l2K9q4G7u3BbIwP/zI9+b4G+65Cj
JxoyfAEtWemL80zW7hJO/boJWk1yT7PULAXxzjrmUnopoJ2IN70jB/ESHTL9EJkk
orAihrMpVjPD7Mq/UR+Ke14I9C6Bz45798BDtpmMomBdj1NMtlUAYxSbdTgEaIRW
KWDvz8rNc3FLRafP1ZFc6K8oaiyH1jL0rOUNJMix48b1WdN5xtsBzWQzdW3VcrkI
g6nLlmOImXs2EgRWFnaXJMzdNGgnCclVKyPJcDUUz3QHMT2yjCmu2CQArPAjDzPS
QQEc34h58biKmTIIhYa+N++8dEXlEJipOtrj6bH5ddvE7BF85zFWa4D5WsgDg1pR
CtqMRg+xRuUqdCCaMT69DAUV
=n3sz
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1270be23-18dc-4701-aaaf-038c60c93f7e',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAApnumSp2c1zRxp/zuafkQf2hyrpqs3ZBhcxg3RlFV3T0g
/T/tALuVtLeQaCtvsCIJkAhRZYm7Qr4HGacNjrjPHaDrWoXiIJdhRcOpp6w2WVrz
/hfzAxfEkh8cbbX9dpENNNAauMk0XWEd0uQkGTkTqSsdCuTwQHkwd5rz1BOEUoVx
cQsCkhsfpKIHn9s1KfDJMeN+x9CmDv2NEIVut3SFMWQWqbWaSP3JaWwf07RhT0Hq
BFF/iynzDuMWs2iTirLQlk++3sak1dTHHYU3ca8HGruYQuNw7orVaFivHTxoHA/Z
/96WN/YvamfA8JpzRfhm73uiTmIs6EagTLmRktEtzFJkyqqcl5Z0F1Tma49R0uE0
47nnwd2Mi4Cuh//bTWft6QWmUMsMWMJmCFoUTeWDBgTdKxQUzxJLNUAwyVu8uhN/
RSmELVvaPAPNLZrAxiTUJiX7G4Tn2dYLHMX8xwZK2wQkuilKpYvgy8tKmo/gF8xs
uIHGNngrRDQhEnp+7nEJTaUVCxF+AMr82OVlzEvAawOto3Xk5w+HSohACPNOsLDN
gyJB6IOlu6YKCUTOIkHm/lWRIfgdUAqA+Ec/ZKLPZRfXAn+TVhNoajtPRMfdGhaR
HqoFEECq8QBvQrzteM9eh9bIa6+wUYhq15xcNxw8NMYTtoDFkCGqxfh5sBx2ECXS
QwF9xK55Uc0bX/pv/ES5JgzSEG+8i3ucWSXTfd1lCObMvhsT1aqUeFcIlXlDlU0d
l31gMNSZHM4cqkP/9sotITlAMfw=
=GCBT
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '129d90c3-eaf5-46f8-a032-4d13f78750c6',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/8DfHuQM/N4GtKnPNcomUsoZzGcwUF3rgVl41JjpGYjvL6
jZmf7pxRhhufI8FYtOZhqt8kq/AOnzNw3/GS8duNeW+w8pXnAIJvqjUiVfN7EelM
//Iblk1hmvawlP2DlKU9VXp9o2em6smGLvmIwsdLAdUFcwDOJuXrUQejQv2b0vNs
A7mREcCXS1/2G4ysJfaLhFJ/QgjkZ6MnY4FOGhx6Hxrb/bc9pvcKpdk3eS8C5hUC
WWkrMlDKyYeRqeYnzGUtxQBio/rOovYzLMRH6FAm539DS7P91U7NeAEm5A+L7Qld
eI5fSM3uqHEFNVb6gJfQV1VcOVb3OU7viJY96FQWL4R0dnUi3LAk7AlShHuCMVwE
Z2vHyPhRoZPgneOQAuGM+YgUOKN94fpqa2TS2KJm51Xum+t+6U7pvYyptne/0Ond
5FREXsN7Uo0e+9fuyhvHy8r3N6IaXmi6Fn8UcN7rRcLv3wW3b4yuYeCUSYGqDpDT
QB7H58sGDNSgG0vUgUNFDsgUgokX779MgI2DsYf1Coml814zo2GAiXITOY6P40Pv
LEenQtfF1f7VVlFwO386JQwgVi0KNCdiwNzxfy3mRTanxvqFIkiABa7tojN22DiB
pcikIopcPDVSg581esEUiaTYmo7jrEjsfKTBFXb69WhaL7JJrPUrU9otdBUSMNzS
QAHOFBOKaEBrhsIztWyanDZaUwb5CQWaV5EbnBxElcU74oZscDgBG47TmdEZJe9O
5Q4bSh5tBI82DPqryDOWOHc=
=h1a7
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '14b4ad57-15dd-4b4d-aa7d-b446d7b02524',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAuGWfGXhE1I/94TW/C+3xHstOTgHXE8fzZIHXCdj1VLug
vVWgUkol/+wQGhNbjZtHjmJl1Ao7oxKtzHr8jyeqAu5Kmr7XwbmnSnnWkhaH37wg
3vNFK0TWSb3ecyr9T4NMXCEN4EosQFNKKycpESn+an15gNoEs6sEwtmeYSFQ7QXm
iO7rbh7EmoCC09fF0MYF4fiGWSgdKt9XqNcdLRV3lluoc2GKE4oK6zy+4KboQXqo
MN1uGyVuli7GQnv2jnT7yqVkfnQ22gF2r02DrOFnDBgIB+xSYFgq7RZxsQVXaPqn
2uOL+n5RzgAo5kjJNvqQoAqqlre0bUBwe1o4XlGpXKHy+K/pcgann7yxfu3fZEGX
VUCvUuzNwc47WgAo84CLWNVu0TS91ny3sirsVzgSIaDiHvIVrrcINsgAWpOvbXzU
6VAQtfx6yo/iLpMCYxgdU1xf/0IJz8A4Y+W0U3DzxHjV8MLrG+QUE8s89t85nsgX
mWe2OwHx1b6AWBh0m70Uo8OTmZLZlaMrbLF4F9qogBL9KCPaP93JqurKSPBlOAxI
K5eY1huCqnmLdhOOJ5jMflxYwLAJ1O3nIZsiCjsu3iokDxceuqvCTZ7rpQObcxKz
n7nqev18fPnps++yz/NaO7FVPORFsb3RzJgdS6zl4MOkuGtumD1OajOrup3XFHbS
RAHAlPYBAHKf5Q+7Dgv9IndhURVpOSvLE69Ku/F1Z162TCG3In/ZkdC9HpatzlCD
MBfXYIHIK8CKHBPhgf+EBcxij5It
=sNnt
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15cc2655-0c45-4651-a780-3c8f381aa9cc',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAoU/3c8rPT4oUnXVe9nA6VxJyUPa7mQadN9fchE83OMn2
Nun2c9XUccbJl2GJr7ouBcv4Gj0j/175QuVQ/YAAMFTl/zFO265ZGE3s2yv7GQAh
dxKZM7ZlfAylAqIBV4B/Vv0+e/h7XsNsaj06KrmWWRVjqNSMIva+8VPPw2/txYfC
CNQRDFnW+r3OeHGiC/jsWZgSY4N7baL/zUVCVlmGoP/zsQRLtPXFx0I0dOzNzmlK
IyeFkc5C4ISNGyuM0DNaFJy8rtAd2Ejs7+HBmCXvCxm/P5weyqj5zhDeBQkeWpjJ
Rwj081SWuUEpxFoytoj2KSb+GkNbKK809MRGVS7+PdJBAYmPZ0dqmbQglcIgClxS
xTknx++OhU0jEnkXCd5BzjCw0UCdHED1rLQhd62e47wKjVu50d3xiJGRk3ykaTNg
bHk=
=pY5o
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '16b61d65-72b0-4876-a1a5-f5327535cca5',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+IxBjIhu7xSjg6kupI97T9CYif2/Qu4U/PdzmfJCjiN+r
IeuIQEMFF71GWTLHPHio2veNrr+us3AqTSyGp+WltzLOw7t8aEBsY03fudYYnVxM
eo7mNzl/6OpAi1j9cYKxkRa/m/UjEioHeD+FkLEHEpqPHbmMQlVQVdZKi+1JrWE5
PvDq4VKEyINhdB4GbHYayVJLmC8nuN/syG5R5xrHzvDX8A1J3O4WdHE1AofvgX5F
NgTF1Wne+NV0wD6dS1D9sJb0EbkAYneHAz8/PDiLMaPd8sHyoiykeLe2VVfXg2KM
GwisLonvns99Guunec3s22LUUVOGxT4VOZRlkzBEuNJAAYgEBWdVMJhh3qZe7IY6
JetM4edNdTcJigC8m6Y8EUZbSmdi37DytkOEFMYBqOdN39NeBXkxNmnO+y4kATbG
jg==
=jhVR
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '18a3e7a5-6e02-4893-a056-1004ef2fed07',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//S79JqbihdSqf9sUj+B6OnBm+U8mclxFOR+I58pmjwan7
iNT/MkX6CLyGHcJVuVy1BnkqtFHp/hR51Bsfx7Yjqs7QnEZ1tQsix9AHDjk8++IB
xiVqB7AyXVG2wKW9MUljwGIadq+Ey5Bd+pGjkWgx2zLtV9+t20RwEoYFEnBQ0EiK
fGiM7Dz9A72aAPxMUFzSmJ+s+p8o5BwvJLVuWkuxXwXGBaSWcVupHNOZ5N03p5VV
bI6Tm/3pqPUeXk2xPFjTV2SzwjPDRMoZGy/FVTlln0ZtC2vvVVcLEEJvcYQD2pi0
2UZdovZ/vixXBEHifz4RoL4abrzDHEojWLdwZjRavcsc4lbOEgYgLv15Ss0yt0Ns
0KUPna5XjLeLG1gu1bTOGDkqV/dlJJF3aWSyvTcbHWetamhwkSYHIzyJRIXFI8Uq
UnlLQA4w2PhnPIN4lOFxres9IICuBRbbGPrK+3Valm2aSNPMoxVvOdy/wCUx4xU1
yA5DdyF58KyfsCI4hWYhZlSt2Up2C7c3IdlgJoqFtaN1egnIveqxoF2yJGXRAyOj
hG0adQ7veLtZ1sY5Dxw7AO2LXV3yl8rFV/HFHZxO06yapulLElui6U9gzCiNqgRT
ODmuBpdVeTbbXe2sG7r2EEGXk3TEN8JBR72Dj7gaOOVznVvshZm+9Fd47kLSnSPS
PgE8EZ4fG1ePAfE978T/jmJLjyu6upu2WKCyMDmTTpSFeriUscc9aVYrIBWPNAP8
zNWiuZ72D/mB0ZzjFz/P
=7azp
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1970d880-6063-4a96-a07a-2b670c4d5f12',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAgRMVEFn2AOs1pLMdkv6rh2+fKFgmoooPIhe8/SABqPXR
BpwIz8vQqZDctgns9MhsyYIysZXHloy6dj23pmW2uWeWUXGipDfes1uylvleeuzt
hzpoKv4s1C87sW4//FSslpxA5zvpP4pE1QwiCHXauv6VtvKnN/G/lMZ4tzUYBoqw
xIFovx80bOWRVG8anzS2Rfo4WGFtaoEXtXFeINJBopr9Cge8wpPMRe93XPzlj44i
G8+RcpSbaLJfF2uABiOhx0Kuw8TdW6hbBsBMRE1MN1V2Wbq3XHqzlLq69Rtw/r9c
064jhGKMPbJhEOj6LKc9mxRwUNxywdhwUVGWyggpvAKA1TaEPrAmybCGizmelzwM
hQ3X6aX5w3clTVyw7s10bfDdN91dNDVUqoylupqYr3b4HG5h4HCOy4PVQY+bE07f
NopIdLO5jVj4uNdQiVk8CCmbLO4G+Gso96QV1W26EVGuujkpeD3tmZhQ3KM+DKgp
nbok9v+j1YsXqUczaSHaVB637k4GcAeF/aieLn9hAEV9o48mUB3ncpfs03a2t8xG
uD5FyY9n6Jg9BqaFnmD7bfhlV94pUMFeudir0IbPMKa1JSz3kOqGgk0zi272ID8j
2fAMTbW33PLojGOlh/GerDEZqc9FT9vjF0jq0PLfvAjel5wE4w4crQFqcCDJ0ULS
QgGXyPtZUtmdgYF3vAAcKXq9aVmpU07s/DU7nPSyOT9wh55APjNwJHf8p5kIA+Nv
U8xe7OV9mnHWjRFnijcu9wC0Sg==
=czvU
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1a45c7c8-d522-4c87-ac6f-b9e89778f227',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAhYs5AHVHw+ohS+UaiUJ/IhkWhtU7C1OThcRPP5j0W+it
5gSCV17SJIt963pli0is5XMBMff0BaSP4IdBh/JKm1giHNseMhDrC91zKobt/i9Y
QPis4vzIa4aODbIsSrdZyaip2eA/3Z6JUPbP4kwbFXGo0XrLVic8B0Oqoy1mn6Fp
Ak5YmUsYSACUG3dXJ9PglDFiVzSpOix5LBc5j5IPl556PO0LG9TIte36QYAOedAb
UrBXeHkRA52fqevyroEjNcoZ8PseDz65bi4FHjfSflgfl7CiUo1fwCGs2+56/SgN
gU0L3ywvtiJq/3nOzAM43J4kkK3n+SeMPiAiMLDrHdJDAQR/qgwlJkwslZPfWXHI
dOd/JCgqF+Ghe+VvvRC3S8oCEsOqbfCP9d8xDaDi9bTjw44Bt5yPDZlI2CdgDC5E
5mucAg==
=vO9R
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1b6fcf34-739c-4cb5-afe7-3945d010e699',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAArxrW5jIzNXyMuNNKdC5AMtUpqIbDBs0a7FhXWlUuOX14
LhEkImtsZ842jQ7urFnD2QDAjsv8r9OMETGQbbxMaqfmEqhtnvgNFJ6XHplADo4S
Jk7E+aU7/5dSoNCOO+wvk4J0o5WIz1iz2oOc+1u1QxLB+IS3/qlZ+qeJ9UmZj04D
PHmhbxiqhWikLZLy9Iq7Q6P1ghsjcChyGd8eYwR/7C4iIwte54fiiNZdYN3JPjto
wA3Wvq1RVahMn6KTyV8rrLePboPWYScnYNU0oGtDhOSxNSrOQDvtauQc3EHJ5gau
M038q0fvNTKorZ4lTynmzKeOaypB2k9PkKvKpf3RuGOeO/crwJ+uHodFLmdz23ia
UDcoOfhjhWFVdQrTg3pFEXQd7IDyqcoqUxwSB2vzOUNu9IY84ytR0SQMGP8kxE0q
w0Vm+OjCS21AxrfuhM+LGVUEHv90A/03s5Dco8/dBhS1Ve3knly7TN1SF+fBRK+i
JsYGd3u79LO8ZrV+sbj98kO1vYlVCHkXqwEtqbaBlveTYypoHT0jcMGU52W8TlR0
TRlHJUqvGFFHU2lp8VGFgkrDPNe4aFV0Qt/yQ8fgRws7J+gUHOoaDi41C1aRCOT1
Lt1ZEuFDjMAh/pa3+pImKysH+xieaAJohMAWcgckPmex/U/YhtbZrvHtIUnYMHrS
QAFIczAEHOqVyI9aURDv9VjJfxkYXliztDTqL7sUGOrjDYsd8s8cYc6vVqDbArNa
u//uUE8CHZSmjmWhSOGUgHs=
=zH0Q
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1b892d9e-8a31-4ac8-a7ff-00dbf8d7a00b',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/7BVfwjX7rG5POtBFyCOEwKM3BSM4K2kHRIyRJqwnrc62e
e78aTe+e6Py6unvg3lifi3wdBJb6dZHkxP1TzYpsuqD9n49k6T2HZ+pAHS/4aluV
dP1Z3enmZhbZB8EacToAJRIav9v3HasACZ1c1SUkRduWEmu3DfTPXTwmDxMfPp9W
yXR/ifvasef/oSexv+QBmD+XE5XrZLaBWO4Jjcn9osh1TN13yGK75EfaZSyplXYU
bJ+5YhGBXWWaryO6nibktweHUwCwzd3fy8LBuvEu+NgfiC9FMjbboerXX4cv0e3c
Pb2RtZwIpgIdVvmJADd50ROpemJhJchOMiFN/2HTtZ/eAKgkLW/R+LHFslHVASZ8
bH6FvKBo29PmFw/NpW+GRaSYiJc/WGdrBVfCrHI+mw645s0603rWQuKCnMDN3Q9g
c87pWmY9uX5WDZk+w/oRLvdEaQelFkg7AcE7W1YJwe13wP5yc/6S1vDahvF1W/21
u6uqT4JX7Kbv/C1uJRDZJIBqXhPZL+ETosPUVl8BGE7EK4eqiY+BJIZtFYc2v5Bh
kpJ7pWKbeQ9YllIN0RCKgyQaB46wh13yiQqv7FUi6zFf0wMMGg6Px+uJMCM1GtpS
v8adFeo/d5/v2ZlA+mnDWvR7W/Ggi64FDBfSXAjLq2vZFvnFymc3l+ZJ1uYZjxHS
QQEB8qG4xBDxVV0P+p2kadggnDOOE/TWn2+G+NDe4KWIrnB86LvUa99FvFsV6Pnm
lzrp8D8rq/6t0DZs3SrSuIDF
=p84q
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1d515ae6-8e1e-4862-a8ea-79b2db24aaa6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAi6tokOpLxlvvGJG39a/Ddmdx5uh1VuEyqeOtagfWI9H9
3Q2n5ki3UY3ndc20sxYAtQSyiANsTzaqMtai5zhsVlDzELnVM6w7mB9dfYFeUogR
vPwdrH7FxDaFiSviczFvzlerx1KMy9rCBksFz3m7OmU8kyw/HZtcvQ0p7ptm2k+N
ebkmQm0woXh2E4Q03V1Y1BNwSHijYi1WeJdrNOEVgkwjQzSzRVdVEYgnNtOHJAz4
nNj+x37tzQGkj8ThPpmVyFws/gx6zBHFa69RTkfI01ARAF0Y26lvR6jUwy26koKJ
4FBwJNP4aL4ajJlmsc82KnTov3DxxkpOCApNHE7PxuJFE/8ccGdc6A77VrT0sDkz
zqMpVwN/RcBfARyw0HuuMnI25FitLIfkZbilsAxKXhh3qTjcMyGGPP+KrsHqi/kE
mMr8ToxkwJ5SXzLqrnyGoavuE/hszLXoVOI+KBqkbEEUiuh03JmsYrKuun7Dx/R6
XzwiU+io4Ea1Qb6QtpmNqxKC3l8UdylUqV+g9K7JqiHTgRq0/f/zymCiGTEyPOqt
OEZFoeBhwWfoMIRxm8TnfFCPH2CafTqvQgFCqPXZW1JSfhAzN65C6paetWWHzA+j
x/YAUPjedb2C6WZpWWaDnj66pyfhuZaAE3RR/zTSaHeATq8lsHyEHXDLBF8+HefS
RAF/cfTGQ3CLMIjo62Qpz5mnIWp5MNe7G0zfCiIS3YNbqrDiD4wb1RF9VhuwBTRQ
RSkiI6QasogaQ19f4aDmuv/I4JO7
=KOHX
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '236ef617-7cd1-4f7a-a8aa-9ac9923c5e5d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/c+pA9B5MtqrUf8F8iIddAmmAADnHkgqOmJRMq+tGFl0F
Yip3RcKuWoNaVvLAOLGH0sWQQSNMX8oThrGE/N1G4sUvkjEuf3CCZ6zGvtOyjg6w
bjzDCDsYcjO9kk/RYf8TJ8FW8S/C0sBKIlg/TAvqe9pYDCBfpWDE3KGH4oCPUDsH
zAO3xCd9oavm21RTxcQUaaqItZwtoQzBfpheVGQ41A3ZnHsYNEkJnhlWQiVAt6e5
t1Kk+d/IsOYiaaGer/c4C4blitC04kfYh0wSEcD+Tp6AtAScQ8C2lO+G7F97WcEi
Uh27XsKiGRAARqKqyDUG0L9zixnYIn411An0CLPepdJBAeRxx3peGSP1P3x8DozA
SCTel8AYM+kkgwgvAbfASRVAH41XFwmvdGWoDia74DpLWYHTbO20KKq1KnqzhOt7
we0=
=/LxO
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '23a8ab9d-ed73-4ad9-a98e-85656a4d0042',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+IMW5MiZXMcWQJ23eVDUVLVY5yiKHZ6djwjsR1WcXIs7q
ZL4ZnH0NTlvOvMJIWE0+FFg9lZ48xb6N8nwBNnmEZ5bSCDQDTJUkxB363qtYQqVu
jFIHrQOBPrQDKFBdFktiwMhwEfiv+byyY0MsWV/DoDovF85o0QdZhBi8LKIS/kax
Y5unHp9LAxgn17rs+dXBo2LB39GJQOK2SJwo1HqmbomXmgTZibgOK56KVidXNeSE
AygIcdMrgaZXJBF6G/Tda7Vw7AXYSUY5/mztgS9K+QmVEsDETSWSatlwOUjPyQYU
OUp0SzXlXhZ9IEqf/xhEoC3GG5IWTpULP79D/7zkL6zhiznbzs/ae6xOYgUIM1bp
fFDZTVx0iv6289Z3dQVr5A0ZC7fhaXVLUB2uaYKkxYFB4L0BlBvkUmfjhLiv0wA6
znl3ycXAwS+tzq8S6KHNUDR3TZrPnyay2DCaGUndrImiWSYAeks/c+9PJyY6+cYo
m0b07dL3+Dj0YxzgaHHK39AcgKZ+8YnIzY5AkyqytAl9Gtt9AhiTZ8hmr3x7F4yv
fRU93QONI1Rc978+78sgg6yJXgKqyLCa3SQzfrnvVyI1tx/P04ZkAO+Z4hK58bLN
Ia/SkgUXT2vIO28xkGJdbtecIu/gvI/D795buMWXbe5HWAGt8fkgrx7M6ImGrm7S
QQFKMoR3sENwS4EZDJQCpSK2PlnFvUjCE7QVcSOWqz9CtGBsgPvBOETbFVnh5f2i
y8SszagSe3iqbZndZkt7+sBI
=0OiK
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2455661c-06f0-4ed0-a9dd-b79a806c8e81',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAlgpcF3iuvlqtmkC6FLnG8pBFThPRL8Re7LgCHf8e1XQr
cxoRYDpqwpNiBX3Z050CK4kizk2/zZ9ka/Fe1QOujLgWtDLWSp6DMqk49RmQkU3b
1ab6F/vTKntBqkJUyvkSN+pvC/F24h81FkU7oBT+r3EuYr8wA5ZYBF6IOI5K6dQj
Kdsf3j7EuzFAdEn937ol4wLAskdqgSkV8utCR7v9VL7pIHKVZqsYVkk92pgRe/M6
USySWBvVdoihU5mNqW0kXBwFQWa8VZeSnmK2UwRj2qiivgpC/g6nuBpoq3RSL3GB
IGssOjJBuYKZXZPczqevXjFYb0miPZSu1Q/wxGgbd8EgSifk/sgweHjdNl2LYKJP
iYrHDYS8vkLoFLk+97ndl0sTfy9pGD6Z4r+spb1iyP5292fxwdAiTfPi++zfYbKZ
E1fb9cbhG2GMemKD+R04T6hj347rNHZx6rq2uitF/vmuBgOw1ZGFlkArnM6os/rJ
yCQWZzThkwo5KXkv2e1xQO02k7oeSl9OgCwPCSNBrz4/7lYNQBWn40c2H1z9cDIG
5+4Ap8otm0klj+UIeFWPPcLQKHMF3xuiJ2EF2pnDrFcX+sXeN8uRXXKPEvhg/FZ6
474PfYspwATIAEz0WPDJZMmWRdrpdedeQ8QWXZHi3BUAfwnT+QZl600eeAwqwzvS
QwFgYnBTvv4GI725DCItJSfNrHaObkbp48IZFjEOejPBNhpgDEXl/e6Tp6osmWfh
rvGz0rO3UskpROzD78On627DJmU=
=+GIY
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '245ba044-0e1a-44cd-a092-db509bc5f360',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+PXJHQnMksmJ2ylNscuh5D3x2TJp994dQc3J5oP5+HVsn
jXTBDhOmqt43bj7EYUjVxwxu7w2qWnRFkkPPdnukKCU4C6hpa2uwyXD3SO3sCqL+
/sj+cTZn/cTWyKdK6Do4QWm/gfebMDRLRz9/9Vp6AW58Oor/8amUu3zKN7DY+rpq
dPtYegbt6ShIL47zIJZd/td0l4RP4a2MbPa2dRzREc7dgz4JrWfBdQmJKmMBf/sR
GCMtsgXBqBupu82RjaNnV6RgEfi6vBZ/B+bQuvlNtYgifTGpsgzklYc3YXKGgSfG
l+TQiw6kV6/WE5H12UOUqzDihXnXxOG+rLwY2rbVJKiFrnL0U/m/LgROt7tXhWtp
DYoUJSOilUGjf9geCJFXbyUsZcCPDaN9mkUBNfGQAZROdd2bhzsXGttbaVVnGe3s
lXVKUQMXxBPkIQgTjD5x0CEXTASMzT/sip+ydxfl6m+w/5YxfsPm6VEsKx7+4WMC
0rqbTyeM3dITb7IP2dGGoPhLV8CiaT5hoX7PmCYdTyxSE2BQo6TaH+cMxxwrGCfd
Zw+GcRnNourAVBePicVAMBTfdn75P65Qc9iIOmq8PRq8/s1qXzFOsJJCENw4GFeS
4NlU81kEt88mxYbKX7IgF0sTC3dRG25Yco8bybtKAqt4LeKD06jlfXpJ7lb0/7XS
RQFk9vI23qvWPvRLlyfB5wEA7vjjPaivQwL2DENk6paC3ZW8jMbU3tg/iaGdZO8V
khrRnejl2R8il9TG8vhPaz3sKwOy6A==
=7//L
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2534971a-8e4b-44bd-a82b-17c86fb6015c',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAA05SGG9EnikY42z2tjY5hHrGbZ5H7r6xZ5ewrLWc8AekV
wobcO5q8a3ZnK8P+XvonEwoavEVZzYCLUqucGC6Tuurf1rKSQ6JnGS7fOxmQOsQ0
lkIYo155Z29Xllb1Yxef0bslOEY31YTJOux0SR3I7Fl3RPkw4WanYfJWgKaVWl/Q
2JD6LTPUpWRQEzjgExRQ+6fnbEDnROCQX4zQR+cJ92s0FE3sQiCmjaOzQbKAHp48
NiXfXuw/ZEoVEs7vvHi57NoK7KF6XHYy3mFZ8/2Dqt+t3N+aQ+O7yizZeFYVheQk
UOAzyYvJLthiK7nnQ+a/H1P4anx+YtSLNLzoJbD3Vz++XA9WUk/fS6jPnRAHlLtl
lLrY1uuC6x6mQGFatQwKTcM8XR/GW4ULW+pu7GYZKC7ipbMVSRgisTxktEf/EoQp
F/Z43MowOTEPj/e+rmE+jpU0S8X+DuRAggXO7KYO+MCdelARBo+nOepwqrOHJA2s
zu47qYk3YR86/KQyXDi2hdTKBGkT10mfGsrANCnw2n2QXUoTE4VViXLZuVhhCVms
gI2KFxR979eW4T7vbLB2+UKdCwD7ay9KaY9+nuvD2t/GGTHZNU5s36Ic0CcTXRma
C+VZxAfjyuaz0snIVZuRrAziiXOmELF5zSFa3CwhADqEVocyu9QnxwSBjU3g7CXS
RAEG1xDkjDXW6EWB2DaLBYRCQHRXLS8BLMLh8FGH0ISD9Bn3HF+MMm+BnvOsa8Y6
tquYGlBiv3TVInRlV2c2DEo/rSQ/
=5YX7
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '254a3cdd-2f8a-4815-a7e5-f99a285957de',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAml+IRsPbemNnOsjih0R5+x+IDhvGBZRuq5Q8pHgDEHWa
27kp55zfQ83AU42CCxa2rRQ0ApN8lWjQ5M1vGpwDcTnjaecD6vzVrOLX/DroBVA1
mI+bKgfL6sy4FDzEjoJZk6+bGnnVwpU84lrVUIFEfwcef15ki3mrOT06756g0Vck
+LV7M/9BK8/l8XpW2uiNoPJWmUcGgv0rCL5kM9LfBR8Iow6ky+s0bv0VgnJ1fK94
HEdGi4HFFM7VyppNIP7nI8EzXzB6LEL3j0vnutMCQ78vkRmjvSclWDSzbCRnDt5Y
yG+f9tlr6w+UuLLmvys28oo4hOLFd7JKDl/vFEfdkaxEixzI4pca854JFlW9tuZF
hnrQQuu/c7XFSf1nBHnRZtlIZgzU39AaPNA8wFOaq7/htxWCFQRZ8WAwuMBi+gLT
qkhF10uEAtDjCBa9Ks59iBEAyzGe8SFriBcCe0pC5Mr3Ai7N7iFOU08wy6586dt/
WSM/Rksn3nZRMW/LB0TtMwN828pIRrIXDiJS6eZAToGrQYG5NE6zFxoNJrwDR9HX
QlaRSDPMn21jd07Vo0v4FXElD0iHYb4ZG6iCTs2RywWDxcGKVyY1FqkKg2ZFFWuy
JK0TigUiCYwY6KTTgCWjcbX0qGiGThCPKoWnTWkQhC/M/hRxeK7aqjWtVU6fYxPS
QQGvNpMmdOagjrVxJVm/7I4oIkLpiGaXFi2r2+fzyV8nmMxs7JG4Hv00F6B/G9c3
PP+mj4QATGqdNLqWGasPxAov
=yY6G
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2780de9c-871a-4134-af6d-f385ff4284c3',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAqG7eNssVsyPLKb2o/ydBKxDo6FdY/Jr7WHJ+JxSklZEU
hQ4t+oazJy9FKWHctNwH7LHbD3AMJTL5q3G3bS4KA1oIMIo/I7fIegKmRuumVbOo
AeBAxm5Dakvl59hMvUU6EpcAv0vVC4Gy6wJdPlrBRmpZbaQuQe+VaXdIpEpBh9bo
apYdnZzRJOeH47vmnPMZoJAklqOA74Gk3oIv/ueAYaF3cJjvMzDLyPBh7G8zwwpg
0mixTDIa/GdVGfqqJ+1J0RKNCNDFsytDsAD6q8nwAbUNzbd1vkYAtnRo2RIqoh3H
Ha/CULsysE7WzfIdnkev2udaBTnFlJCc4uBa7Y93FGfiSNy2jgjxa80KjioXsUJO
/b/AwGABsH99izyg9Dk/mQvbNtGxZJ2AVJXMIoY5ysq2TBhQqKMFiDI4wJFFpSrY
VX59W5/eVqoesIUe1V6mCpqivHU7T+w7/Hi3p3vcv50QxGnvxeLYnSWRcEIlC9HH
xgrJPnbnCv08yFNameGmNRLlnDFXlQ0LqE8jXr51sNSI3JsooQTmV9bWpi1tyGpQ
BMZh2CgNQKOPIBWQN3IN629G+o07kNf9msPM6W+sOYw9GVCPs26r0zdegNwDAIyR
OILlpZxU86aLW8gQWi2piPk0OjC6fWvkR8+jzBosumAOuAuPCYyG8obBmM1DeDrS
QAGbZaoWDnBHnpE1AlFZyumU57aLFzwLir5v88AmTAeDbRwDItQwgnVO9+VgK4Fv
IVTasK+yS8MEfiBowXwOZBQ=
=sIUi
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '295acc4b-10fb-4917-aa0b-d445e1eecf6e',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAoL7cQJyc5Y04lXfFOkrsrM5B96yOeMMJGLBHMHlsAakq
h+taJGA7dnEzacPReWUYQRGVyXkSHJNxOcx6Ym9hwKKzgkyTCr0HENePyLBdFbZS
9NpajiiD+X9IX8zn9tOFtwB7q+mylNBGRCFjG0JgYKfId/4D4a9LWRN/mZeVgEB8
RXhUd6aC71+10FVNAogVcSZqVghrsazU6sNWk6fMsUtzc8nHCsPKYQPc67gjxXNz
fsc/Ba814EmJnTML2PCYCvNbT3YQX5z+vhKs90kjuBfkQzNXZbbIMwPEqTm9aBdK
nrAslQfiBrp2M/x4Kz5qeMQXunxsUW6iCti6AS/rtCt+bZHXc0A46che3gQOyELk
62CnlilbbGpUcUDncJ5sicMq4zbMM0SPldFJ43del8JcZmqbdzQwg+4jxYMG9WDt
wPLfBmrQklMhDLL5x0ULKPeTtDjIACC0tVA092+n5SRfJYFaiFImUqVVmJE7KJX3
aVsawbbDWx6nYyVoGMjBb+xqOc6XW61Q3gB6IPpWeHq4HGKgh3rf/kvBZwMgnkZl
D+TFpNK9yKyqJ4isrYjeDKHYMyyAYD1CS7as0W8wPwpXwTxL5+d42kQewPK20daF
lbfFXwjvQATdls2p7kl+dCelR/Yia+9ZjeB85NTKvuOVXdpWJZgTAOboawsv4lHS
QgGZ4JlzWQ9jUEAGitGqrfyYU8l/ggTjxR35FhkNeAaoLgqJN5LyoZ4afJNiS9kb
wzTRpIB/GzHMejU4wc38c2olxw==
=cB8n
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '29deb642-570f-4e33-ae5d-d73bbd87c6e7',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+L8r4IXUrH2hSeiPTE66spHc+Cc/fktdRRTI2r6U61DVQ
vAFnhFFMzvwERe55L+KEKexIRmVjjVd/Yfp0ot44M4q/6jmcU1uylHinbOpqVdqY
MTbLFhrnIFD31jSEbgfF8hK5U8d9ddPOPVYErybDdzVzp+HAOsonSltWKxDSLbdk
rF3h00UYTFxYbBXdP2lbefQhq21p72Y1cTgFoa6rSZhkgTtph3c9RPRqGGGQmrXu
5GWgJfpcNlGYYdryfbX2U1Gqq7c3Q7DFoXsy4wzG6MxTTGgM4nJUUZRhiQ8HvxlR
/9jnH13+KrVxkJ6YeXtNcWhfe57WYgEoHFP8xuZ8oYmEouD9aDH+e6Iwe+c8WaR/
QDoO0RxOkbWWQJVNWN7fJPVfKGdVgt8Rux4OZ9Vc/tCO6r+7qD9HaWc7w/KVEPPS
yQF4bgPx2LsNf79yAo+R46WIWuasegElI0d2fvUwdzjN7T4qw0aKsp2POpZ7t7Ru
tX7N5y3zKHTU/N6m8HB1nEOyAW3tMj9v7d59aiKUbThz4zziHIn+G+J9IJ5B46qi
cR5hgp3wpArBkZj5XDlI7s+LJVZRePiVK13tvanH8iCKoZ+mBs5OX6Klw++j0duU
XD08l0S4+cyumyp50DGJsIFPzM1jaGzpbc5zSLWhc8juaSuQtbfv2fWTeTEFJ3HS
QwFlALRbKjpeYTdhoQPNo2W3yJOsPMcEso68ncHJK3mb3fW035ibx3++3etEsPJ2
X9Ef85Yprnl73qdulAinoDfnvZs=
=UPek
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2af682db-94f4-4517-a2c7-7744a1141c36',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/ShHAbQMAHTDHKqGCnTszMCTK96dmAO+fS4QwKOYi7+Vh
hZlVf9VvXqkTRI1s4Ca1Am8GdSvp2gHUsQAOiqwu4nRUKWFr5iA/JIuDX/zY84Zv
LbUNPj16oc6VwPBZtfGXkG7wegw+EIfcrW191Eyq5So4YDrLI55n2XqrK7gmhjyf
VwsEm0EJESk8kSoEe95QkCBwLkl3NNZTf4c6REiY9+IuGiimz2lp31ZN3QRenchV
lvcBFhfl5KAOCM0iG1bhzVTBsZ+ECKZ/4PNrYiJZRCTsY4bcSJAGqdFmLBWP8Fsn
xhGbhH1ItuvxJgDkHnwQ1JujL94eGhDwoR0gzM9z6tI/AatHQxZogVWMj2nahv++
LcsnZ9TkLXPwXK+MMVYI4Ezq8XkV/QL/sQgAQ0E0EedbffXIGQK4TWlgRIj2jsmd
=2UiJ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2b78d992-18cd-4ed6-aa46-19858e403773',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAgwySqk+z1W7jXulS4fFzSElW3Ek/jmEkgci6nu5LMdab
CBHpD8BYs3AnzZybnYwdPn3kVz1L0tMTfvx2466PmIf0qV6zqpye5Vxz1V+wvW89
XAS337Pw0vPws/KqaTSGbrkgHPtxh3kNNfeetIVRLXJJQtldQsyOPQigvHGBAdGa
eU2Qaor/tPV5Mb7KBkiLekomWFQ/IrnDD7w92BcbvYH9jQhv26JLc6zMXS7Nrr1e
JtgADcVQ6Ig5/SjB4KX+vGXSwfEIeLWjhGqzn8260XvTvSWX2D0EencD6cQdxvMC
bwmadHAGyKs2T34dL4bXZ6YfDFdpsyVouTOFawmekLc88CWse7dARGxbct1hdSNv
Px2Ny2SM00wu67wMQ4oLREI/9B/bw84/pOPaWJ69kGnscyE+QetTSlHBpNaU54jc
9NlHIMktVdfBRTYHshtftRJei4fMbzBpqETP9DkO4aaFgP+7iL375pdhTBEvMD1L
Alp6jph3ukl/nZehGvyNyM+OIOgqbW+3zfGUtLX4lQlYqG/mSH2T/HUY/iDGJZyz
tlBTTpPF0FVTtwjc2Cd2MVtJWSusKun8oJXe4a5dUw6YmJ7cZQ+oRA6PCHkqUYxI
s2/VKFlySCQZURV2Xmqq03fdn/mPip8nilSQek7zDTg/JDsvIbcnxK9QmBdH2STS
QQFYFqlQckEra+UF9GcDAbWQgm1mRctxy/zLUGJaSiFmcVn8BsDzNq/qf1k1eNkv
vaRZM9DklEssz041+nzMSokB
=u4H4
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2be70315-c0cd-44bf-a9f6-d0cb82f60e9b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA3pxBfdeFbqt4pzweY5TWMZwNxSg+zQfKVzznYIhxloof
UfL5tXSd1ImwRkweRFvV+eYhWgp0hu1WdEqDmMRhrS1H+Jy+XaSkn5dQ+wyedZgU
EkBvUW7/S1uOQLQcfm1NwKXGqHGwAuVKjAON4tY5b7ptVejW1F5Ex8mVNaSs8Htb
9gToTdQzeoDrtYD5VO/aWid1g8dc1trNEYqkRznf2YHCJE7/b2xPflFq2ZC/sCWp
r38pFRQFwjw1CWkqxG6Aipz+IoV374yhuG6+9+yzNMxuwD0TaR0Q4qzCyKKAXVtU
7sVTZzpyl7U2CoGKQEFS4JYq3aT0DTr9p8MBDCy9JI0Yrbm9ymuSjq6GTZ0ut3Yl
zNIi0pxxMBxpzNtkHsFNiZnQwwNI7BMwC+DJY1dcZz4U0K5CV3/DBooLaf4WFHdO
qTUsS/DmAmXMw4AMGaPIE7BttuakSbhxspvKZ7dQQFAIhOcagkX/DcTFkkiplONo
+0lggViPIC+sEzFxK/boqmmEYwUsK+tucc01+ousEwpgPOlnXk496uxMO0fBJRC0
ugi4hB6RKoQF+R1IteQ9HVgDrauSnlJn267VGwEkV5SWHfIJPTa2pPHtOT+1lL9F
hHmrheqoWIRoFZRppf2rx0ShkIYvn8qfUhRReYi3WWRPWIcOUPE5gEoVu/Jg6GLS
QwFaTmCccbMliTzqaA31oePpX7IZo17p/f5nVnruOIlumPMa95DY02vkgEbtlO44
K+xc/ZaYhwOML4DB8T0BhBoCXYM=
=drII
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2df90f02-7724-40a4-a8e1-9a21b01c7c31',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//djkbHjwU95GXo7yH3sif9NPysu1KV+A7S8HIOZt4koMb
swGQLylRICYDFnwXK8NPLS05MiuhkajxBWu8daCJxSBFVbCgcoguLTnFk7SSz8kz
goxnKpFLXXEO/v+H5gXQO4ZicX59wqXsX7PfX39qDrTp6h9ddAl9NrQ07w3dBn/u
pTawZ0CZ9qNHM0mo+2UA8MiukXjqCd2gRF4CGIyT+SGWby09Is4JrKrPL81igaMt
VX8DcQzwfNYM+okD4buXyR0/a4gdmUpUOONKkhIduD+LM5yTYOC4wYj6irrVi6Y6
5ZqatrgvN2BrpcQ0H8Nb9keXrnALEBKbnCz92Rf6bClG5QCmdnklPnkRHXH94sog
tnJZoQpPZi0Jt0l9KaTzCD5oXUfyS61IyuKDuwMUKCh+8I3ePJIhQkK81vv2APg5
csCZy+B2p/fCVNhunFIrMCLeMMMJIXJlDz815MBZBpG6PGVq7Go9UsotXWxtAbOc
gR07y1vuzaUD8hn+Ee3t6IhedwD7fxGN0uPuHr8tBcvSBwh+MnkIoxDNVr1AinRV
Pzxxeq9o5yDaCO9xW+IBcFxb2p+iw3IPP9qCcJamtFdb1cP1I6Uy3TLswF8gk+6u
NL1h7bEIIo+aA5Ke62KaCaYKTrUIGzs5z3FATO6GLHe82b7By/16cvcHd8SW4CLS
QgGlN5R8RcGmoPITFe9br4w2vQa9VIMgWpmp9W1d9oDmF0VREar5LHQOS2efZZxg
paIl8GFEhPsqDrHsqYV1+icgOg==
=b8rW
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3085689c-7ceb-4fad-a7dc-e04dcc459459',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//cA5pJahT2+TJkWtYuo6OgpTiwU7Ttr6uFSXAn0hPxkWl
boFhNo3kmm7MzHLkLodbEztTpsA1I+YJ9Fl2AMNgR0bygZv0Ry2uglQ3RRYOWMzk
tunjjx7ajR3wZn8/+g+nzG3Iw24sfa5i71UIqGDdj1DfMUQQ5EPyuI7MKJuQWVuV
aJewD7wtdNozmrNNigaiXFTMQiQmHCef3cic13GzhEnsb4023uIzhs45ILrIcmFg
XgGSCau+/SPLhqtKegjdwTXkdfiEk5d1l6Tqv8gzIepe1B+jpgUZEW2k7X6vyKlE
6DoKTuyZmIE701gnSpuDr26kYBsKAuhlh7mTIhhUIwUKSruMdf87ZCOUbIxSsIZc
QbrCASRC6q+PJiopKHNKfcenRT2U6/x5SzEkrvQuagAqm1RYNBluz1k+4or5zqiY
qtSIHnE7IDU1YhmyCTj09RAobwCLbMiSF6qLb+ZiqDOOQ3XUh3/Jauxf4uQYDDTk
Hoxxcncc5HpZsyxcrpsju3Ullz5LcRhjVRG2ytrz5E5JcUPZWQHctMO3mWxty2oz
iknxt81z80rMKnOx1BGLc0pnwL+RODUR8CZovSJbHsohu04KEAl2NYxfEYqPDlSc
QnAX9DPuYP9QXP3MJvfDs8kUhEhfG/BzV3fDLgh1kJUopDmkLIwfYOiaOcOvRvzS
QgHiVbp1VC5IfH8zr+Jlln0a9x0PCeySaYeNOqt/me6o76DwQc1ike+5+jU7eUKY
8Xr5zyxsp45I64qRQepFQo3Yhg==
=Rw8T
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '30e35e6b-89a3-425b-a57b-4b84a665a744',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAnUEACOKwVRIKuPH+fwI77xYDrQUAN6pAPGJw+KscAOIB
rKtD14/QUciHJrFFWOgpcsTBuziq8D7wRYGsiaEBpFWOetubjZobZ0gLxW4H/WRC
f2GQSU8ysPdizs+/4l2KoqVJnbBHc0W/43cfwH/+KRtZTaZ6U8uOeYVEjD+ICcO6
TtYj27TSObGoh8ssVnz28/waPfnpdtSNKXZJKa/Jh/mPrtXUCjYIudYWcIgpvQYH
gPBeF+IamapIfWp2ykcWNdSE6iiLK2pGKuLeEvlgvzj4a4nSkKiGm0T4ytavUixa
/Vbv7yr60ftD6GJ0PgHF2AwjQvNhdKbCYSpOQAaimtJBAXQIJaRZP+VtgfGFJ3c0
moAvR1wEtBTbyqA5nM703i5aF6jBfo1rF1TTF6lwwqpsvZUT+Xi1byBosNlynfox
b5s=
=H7jC
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '33e7da53-4f87-4c56-a933-905f5e4c8055',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAArT1ki12wwSNVc/Vcq/iM4Oa6pdAaQoJKP6Ue7ErUqevZ
KzmJ1CR7dQYee6q5tqkTj4t9BeXSH1DPvOFjogzihkb32Af32TAt8Qzre98yYpPu
9s7q8vzkGQH3hIPrKX1Ic9RFnQLzY+jJmAe/JlwEvsTeduyz7gnhSmHJmOEuaN/y
H5MZL3l3/XPnmCdLl9zqCbG8PrPuvED/RJ0X9fIJrw9lIZf+7qS49ExiUeN2LBlb
ti0FzlVEwUvfhmXME+u8U0bpE4Kgr2i7sJbPZpIMw6Z4ik8cJd6EA2VIl58a33ws
rl9BnCRROKr87LDLsr5EoPufh+VBz7qFJxTvjjMc5wnzQW3etJ0aJgQ7z53ygxVw
OympW9KabcUTMxuaS96uXXJmFojKhwFs302CjedPj5pSsEoJLiHuNdd0SzeM3ZS2
kb4Th3wDsN1qlvAgVC/2A4egpw+Zvp0dXWCAbjqjM5qXgwlW24s72FFG39oGGjmY
Hem/NMEn+KMJYEmfZ2qK48DTbkrEMdFd+myFAZ2lB6VAz8XL+jyJzilO8Gg972yA
dP78L9NZcecGmZXVQLpfYgeBUXP+HY2OTTElopZxLMp4zdgPXNotOoHXKvcq8m7G
AmCy31obKgsJat36BhNt86lhXS5T4JKJ/b745eBOUvLp4HezjJtS8rqT0fID/CzS
QAHVOnr0bvEqJMn5bdaf8bUr+0U/gl01hkDIekgG0iDYGsT2LpXgfSpdoAv/NEUu
tgC5Kwz+lxD8Qav3i9mRuCs=
=XXhu
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3710d8c5-a929-4ba2-ac7d-11b49786a7f4',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//UKaDtL3q9FZMtJYFCFe+JNhoAN4r9Mjt6u8aDI9COQkh
odn9ZiQoCjDDN4aPL6gLJKyyvdfBMKgm1dcdNEmHumWtdJRNWD3Lxp6Xv4mxpaC9
yOeaQKnDtbs9UL+tgY4kXxODZqZ199/bvbfpwGO2jD0emRvEnEIrwhxLfFJmeyPu
wJMVkPiSTaz6Ea6U2neYR4U7Hxo1Xe6puPrG7fwc6+PesaahrD2/fnzKw7W5HpoR
fD8yQNTHCzX2rFz0uBfjBZ5sqqrrPMi99HEQbCiPn90wYJGZA/MuR34j5KzEfFbL
j/AMonLvxqW+Tz7WoXOqiCjXyY25zLI6ymv6ZzajbTsw+KpqqGREt+EhLMLMQAa3
KSuIMIqXba8DWI6b8AUPk4vAIMwQx2YhFAcR/xrBfSGpxpCQY+KHFgkDTOaevhU/
ME25KBHjdWqF2pdEWscHqrRUpJH9rIan5o2Ja5tlDke1OaxmpE7ZHj0KYrBCZDgX
VJuEdDdJzO9rpMOKUHM5s9rr9i6kQJBCA2Zrw7a+ELMnUYW9juT85t4LPTOFp6VJ
6WQcSneNgzs813kizJu0vwOl5ZfM/B6vLf6T4LzZir48TS6aJaooveRU5Ygx14eb
Ht+FxUx50WzukZyxVRfhXm5RoUeq+rnYnNbVzPkb/nmb3FrejXxYejC1IBNx6QTS
PgFqJtCdtDyQANrN57aoZaxUe5+cOl639xcg3R30yjDEyUtGaxGWLiE1Q/cszsU/
wNU27yEfhiFPDd0XV7N9
=/Dt6
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '37774f6c-9a24-4ae4-a30b-ff88b737499f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//UGSbq/t/B2nRn7zF0eLeM/dEtUD62h5z35WrHcDMoVI9
QtF66mw+ZDsKQ8mkCaNNxrkRzsxdFmm4/EJAOeRV5m4Z3jFpUKt0/RL4cqKoKkRi
Xu+Cz1XMEQCXMuF2d7Ngzx3kgpZrALlpQ2yKgddioQK2AnK4ZEfC1TePWa2VbH7I
2sy2dzcdRpbqNMv1dEgB9ML39MmICD8LitMBicEWMKhvorWJIa2eoK7AN96asNn0
rDi0OlUzscx+MMgzU8ZTWtWBDOA/D0S3zMzIciPNJS3b8OYzDhSLu9C0RHsTcLwu
/FuNLjnNy8sz8+SWoFqgYe8885GiqgpZ40+kIwIkqCuS9tc7nBD1YgqBk4stb45R
Rc1idnEssCMJj5tlcNOLGyHhxnh0MHSRUE1AJ+dJlkmw0CjkpmYv7XmNbLAiebdZ
dMAYXx8aMMAPsUzxwPCkoFEk2Rd83mPcW9NNNArg6bN7RRX6D4u+xryLZoxl+THD
2oSLY8qcKjNl2M3FaHjQSvrm1BE0qliVvkqjd125SVKwgz/a+DH7AvxhCWXb4p4R
amydQFa3K3eyy/YdKfr2v2mhbc9R83bbA26IpR2XMGupxMjTCkS8YZMfgAJx+pgU
BOQHHgLJVV2KUXrzcUm34ubDRjXdk45DEIYhYJgplgEBA9zYfXYR71m/DFSANh3S
TQHezO/HbzxpmJ+702oHSy+kffqbstZEmtGyXsz2FMr6t5WrHxX5BptgtWQVJn7B
K4hsfVPLBnYKWUTUa19xnU0hXdDW3+bvP4t6PH34
=ZBxJ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:52',
			'modified' => '2017-03-04 16:10:52',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '386ca411-509b-4ddd-ae97-e12fa7cdc9a1',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAkfzWnuz13nbAUOOPTbV1m/5e8XIibE37QQIDcBDs5mve
SoL5TOWPlqZAPffqnj+Y38zFhY8qaPsqwuzf1/ZSymDXWrL+JjNtxROH/DsJUdCM
56V0q8Ekk0FH6QvDaVxsJ5c/ta1yLgD3zt+unB1we+CSospo6dCRNqLFGQ0m4/lK
g1uM6JSs1oZ/WDvuZV1KWPcVKIaWN1DZi2pYfwY2vMqGh8PtPIGd7EaS0c7Dpego
Ty2C492iWS5h5S+OXmc/tf3xa9c8sdqx5Xb3S6x973NM/VBeX2I1OHL8I5n8B4kL
LpFROSvjTvjVRmlzUxg/mWFXYwEwcDA2nE2BXWqjkZhA/wsm+v60a3LmYUlBuqiC
ca9Otd274GKCqWBe8Cnr42kEEIyRheDjSDEgLdFoSq09fPNW9jbpeHIoq0nWJAk+
4Rwvki3gWnwYr4ATNWdV7jrOmSsJpZd5QssuXaCIBxFo++p/JVRWpEqUx2nKPMHI
1rOceNM8e70GbwIZ+xYKyONXwPLpEzo9EuStbMLkJBQDCUQJsEIJV2eBD6T8FlWu
NFiCDVJ8XxSJQlWzzPYS55Fx4F1FrI5pvBctDcjK/xcJoD0b8y0uWnchE8pZDJu7
lO53ZhiCrCVsFOuYHtveNE4OKw8UOlxQeEMesGOcSxJJv77s1xC74hIQEtJKiVnS
RAFywdhXLFX0UMbPh3OqqYlAWe0FB3yqO62b4xYW733qSffO6k/iAP7+5EXlRoFV
pbrqg9Rm8AbXNtcZ01e94BMBvn1Q
=XwEC
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3c978077-de7f-4fd8-a3ab-998c0f1d829b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+IVWbfC417DpGksuaKgnq2BDoKjopv0YF8UbXid/QBXBE
nRYTfo9UQRc5Y6upcQR9+RFolwKXraE5COvWZksrEBighl/zclwmMV9/PBzLJZ5s
4fIHDaH4e5Ox24hy/HR339LMeRQkSI+of4TAHuCJS6+B46VP5cJkcZmd7BiGRKYk
0jMko8WbuHz1UVccAaZ76kNvZ0u9DdUX/tCkRNKF/36PK34BJJA3kZntppFEXWR0
vLfZTMe3K0l/LpRCTk2PiMIIBveHwqx2g8FY259HwUTtjr0v8xYQWE7UqBhugMc5
Bnm6ORS/PWMKE9t45/O5/UHFghmH6kACdpjK88fnjidjGkv54WBCJqDa3Cgan50w
dIxOu84AXMLxp16TOBIZqvzpViReFAcAmQdzMuGyprMLCUle1gpPpTu1nR+btUCc
c/oETL21+/cCj+HYQS0l0qYL7Wpb3Nsqs96zQVqehCnD8RNBH8V7PCVpzmaar65x
j65sZWvbNWJZSzxmary18R/Gs0Zl81Y2QuNLTKlJr5ubOLys5YoFDovVHTz/50Oc
tdspqhXxoc4PrOdrGb7Vrzh7Hovyb5Bt7QNe3GcXZy5TaOILhWcWrRuuBkr9+xwu
NFe94xg1ybADXinO6/MpynsdKU+UWfPdLnLgslvzIgwkQSRuOMGw0kTEcbrtwGrS
QwHVEJTry/iGgs0pDdpslGwWE/BJkSs9H1UljEHqb9vFAei0ZdL3VKlSdWqlxcaw
15I0q9MJ8l38Fjuci+bzgSLdvm4=
=gUzU
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3cc66a31-4c13-4a7c-afd4-19eaa17f1646',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/6A/jNG0uPmydUFHCMebKUEvoCDFQcnZ8VLgSVnDRjt1qY
qR3ovoVo40srocrqqpMHtSinCe9hqeBGnT+tQIV/ciaIZ69lb/3MIW0xhBJxZBw2
Em8PbRzub9F39t0XJJitic/I/0dppufZz61/7FGp2mW1I4uSgRXlT6rHQ8L/j2ba
cfs4wxKDm9ENqkCdQxMl2KKlGq+rwFqdNRV40DcoBKVNCC7ppVdVIJm6xwewG5OW
ew+Zn+pFP9MlZdKfNTzR5H3wbuc5ACpVATX1FzzUGUQxoX+i4kSa/UqmVYPRU4Rm
lNic8ycJu8c4XN4ReTg1z2VqEtAmlaiX+wVUfYcvdcoATSemtMnCTdfwk1X+jUmY
NLU/Pm7OP22CXvjb4Vdgxf+Ddu1t25NjcqJIjwqQViVreMlEZQ1NALEwUM1n1Ej6
5/RCZGfSf3AjQE5hrIc4oYDdsMmwUvsslCe7dFfL42ZlFrsvhH8EM4wzyPzWiS6I
AvQH7gVS4z5Fnrigzdrr56+1hiM9iLbCABl1pzZGaYwlzX7j1g/2PhuTjt+L+Mum
Q/GxlVsvmgCHbyyUcbH8cl6J3PzAxVACrjFYzRDuXNcH00TOBWiokJ9QkJAkPfiC
w1nrbeQiJ7cO9CY5jQWfGxKmKkiwngtBKY1LB8R7h9y4ho4/mmW6II4xo/pRhOvS
QQEtVjF2M3yWwoknpBNPo5MZM8BGf18qw18aSzR1wT7wPUcmAJyhPamfB5vr+T5u
RhdVBY3AG+UXuBnJtVsfBwmE
=i86x
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3d360808-74ba-4094-a55c-f0a288381960',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//ZPByVMFCmbv8mJoJa4DbTWDS0WwoTIj7tRdODS9Ijjeq
QBe+I6OCV/hHqXyH4pzH4xDZS11Zmnou4fX3eaFRQ/9lDN3WnAJ23m+QGO6LmCkL
JFrn/fYk+9+By5mFeNLSPpVwLRkTOYOdjXET75i0cOOZFV6V0C/2hFfI3HJQTHqA
cZnRlHplhYlwCklmcAZI745zp/2w1f6I7XF+uGMcWgN7K07da265H2d1+3vhUt0c
DT8YodC8gPSEwFkpj+kQuTWsEphBhJdcVIKU9djTgq0lGXZ8ogQij59ujjJgNkyD
JKXGLof7Ri/ijMGNUCHXhov5ou/xnfDnhlLKS8e/UmqfBliqIMYbdBkNqThUGLZ4
q4EOhVfWUrXDqzKeSScYFmWdHq321ou1/wpRY867M63pafhdhgiMU2NaSbuCBYCY
umEhdbr490+c4o7Dc04yiXrezJPyXu/uCoAmYnFXeV409YgWbyAZTNr96pkqPtgH
jhBCOpfJNswmWZsx8eSkNIs/eWuWcb2yA2HLlOw/CEcf4lJWu0GFE/2c1HdVNRmB
qZhncYxfWGrURGOQIrGZJhxx1E7zdIKiGAhykLCNF3voL1X3CSobbNlr5VSL/JMC
fO8WUdxEio3btRm68d+kGGKua69Jm0e58is1nWC0CW2lmmKmYRjl3gc+WahK7DvS
QAFLv3EvqE7OyY207W+zxhsAClTBEd91BofOVAbTkiy84Czvt+nTolRdP/RrCPdU
N0P+afUXgwNioWQoY0rSGy4=
=Xayd
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3e940fae-48fe-4484-a55d-e686eb25d6a1',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAoOw1quTs+8KEKEIb4g2FUw2natvX5b2P97IxtXyNWliq
It5S4AKi2amARjGW/y/CQ96R5rsD3/cLKcLbG6KjWMPqOW3W+qaWhnU+oIZmw8BU
1kIY8nyEvSlNCCIQGwcAglFgPIEfSvtN2utXy/9O7v+8WHjnncp+cZKANHQBnuSX
hxf9yO72qZKVrQWKq3Y3glfizdV4+PXfUeNh6LrSerg0B6Fr8FcQc1bsBXGeaE+j
ExsqRSz+4+JpJ861rDQe1yHth2wqgW0VkZJyNuwuTtwyyIXC1/od5m5jWo2OPhJx
lDZ+ded/FDb12g6GRZGh4ZpK7+qsuXHQyF+iG/ZIGtJBATVAlp9wNqqi0SYgKq7d
rebEt+vsBukkOd6+SqzBlHK3mrbtrqKbVYK3lqXLYDVGn2gPOzk0I/eF/dHjfviu
uuY=
=/c1r
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3fd8d245-fc12-44d1-a861-0dc68ae04a60',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAw9YZ3vZwypWuZ7/FbOhmHRBjx89I0hYNqCcidBfqWjzA
R2PpbGUUNjOHAUYpB4jW1RSirYen69m+9Rfl9JqyI0ednSGHRccBMA7a7wwYFF5p
vBN5lw1KqPVDNpxSrGNOluZWbMspiyazDNzHk72UczmxZAY797u4bYlkKkw2Etwd
MIFLEdhm9Q6ZPEr2+RprMYMBHtoOYlduqiU8o79qNmNgAIHhXcrJDrToF4wIfjr5
yapKWb7LPOCZXapIGXcfDfEs1lRHjCqSe5aNiR7UsUr9eRavaqhQ3k9Ut0O7C+XK
a3UxNVz0BsuWX6whDxd18KCUdzQHz5hWMiX4cXmB4JB1u8inftwbIletmwyOGL+y
J38EnrdLI2b4Mxr0ZvtJpept2+JFyV7KWKFpUI3VhImSpP+qhETKlT7Cgyb9J18M
rR8DatYoH9QzWju9BdsUn2NxWoMFN06cmIkLfU2SN8Qfg/fFPCa2P+T9AnvUwsM+
05qqTwzw1BFWc5jTNQJCSaOgO6jb/LggOGKMGhw8vOZ0XW7O0W0I+oNKRYb5acul
iGFWOpVmmgK6NQUhfaW9Z+ONWhhbfyax8qAPpmw9d20N+R7DScLb80DDYJGmyHCg
F7gDXXwxqRPRGnoEHiYbYqlYFv12cshqfLtphfav+F5GtOHQ7evV4erGYMnmigPS
QwFvL89v761aUlTGHTRNZb5E5Ikuu6SOPzSpo1PLvlkw0dqc6B93SrT4dmRkoUgV
R4z/i9+NT03X+3pxXeVubYLTLhk=
=RkQP
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4071bac1-2c41-4000-ada9-b57b1362dec8',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAjV1xzjoeOcFjRFXd5I1CVnmjKYMgnzXNBRDYIKOd4QtI
zaQ0HJuymUVhu1K2JMgXfqYjm57y0lC7ra1E+fAIHhTvbogPqZ6vEe+iWhcAmL6z
wh7eEyXTI690gi+uN//gEu9pEa9mbN+rPXabH+o3B7OpuDaBT0+R4UoY3gQXQ1Zx
IcqotxMECYtguXNxSVxcOvzYY9Fma8YkH7CGULvzWWNesCODj19jx7m2fCHCV8xM
FjTUnKh0Z2MdXHwRtHy669ljV1O2BhUqe3f1eJd9K6dxiJKjz1vibu96qAIs6JuJ
RsbGJjQCe0A7hfDKgzb1ryUfUggEYAVLg98ecxtenTzE2wMU6qzNT/vBz65Emf7U
CCO/Q3TTiEtkEbHfdKNv4PXY/8+kfd2GZAu0IO2czXsp2Q1ImTrgA95dBiemLmwj
0nMQGz81rsGVbY6qi6b/NFUcep1b9QFECuricwObXLSbhCIAA1l66ZVIGMFhZiNj
eRNYoO3aYF9V4L2rtmd8q1SKOOSaqrqBgcWQR6ccu5sC5xSpYp74O6qHJ1xFrpdA
O2UyuReJ8L04Bnu4vHnd1E6Zn+lHE/Hxulcpm6DIAiqu6g+KPvA5dCiccsFQYy88
a/lqYbpN4ICky8D0IeWnYO+8T/+w/aJYeeqvZ+KU9R4sHCWPR1wT/8yXPUlElTjS
QQHoT8MvlTNl8wpXN4X4OAMm+6JHvE2NSmCPNowmTSM8B3VN8vNqJ0o81JXeOSTK
yI/FGqM2gTBXvmzTdl5n/DKR
=/J1h
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '41b854b2-f468-4957-a2a7-84ab77070d2a',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//XN/BcPJG+dFO22trDMZdZ+yRnEAii3HBfqMcVpC6mELP
wI1y3tdRht0KPR/r2R4SyqeyzRBML+jQU+eWRgw6PEsfwC/byQW7b0wQuaww2b5a
ay5FBR0mPNFpAU519I2/SUN6yFqE02LcVQ0/nfuenK2u3tvGDMLIYi7+C/jwq7JI
Xi4fvrz/bXUq3VHAqskQoVGvnHKndPM8fXLMzCpyfVuCO/D+PK5KyBCY/0CZnTtv
UTcUtU7ZhPbEvfCEfgrk5G1v4h+uZEWxrSswcCJuPjvewR3fKr18YRk7KL/eSsWc
RZP5ngN7jmO3hFQcjiKVkluqdXmdSq+Go4o7m1Sdv+Am1xIMcNcpx2CNWNlEiIoh
KHbIlkxujNgfdaqyLvtoO3h+ytLZWQP54lw474Mhst7JRweAtxWdnz9TbaNmG9W+
kkizW4F8gRZjDcwfw2h2HSzJSo6VYj/NHJyLeqGM2ncvLaXo/WiTVxf9IhxHNNSZ
M57/oLhhEQgJYJJ9xadto6Z7YjVkezJKdQOEuYgygAmBePxn3RzSW9xqvTUJKTE8
pFOlPiSU7KBOy+zbdZrA3qUxHKQKFxSw8eSV1NmKqbUVka+ahSFwKuvyXSQvqT9Z
wdfByxxy3tpOkZfLz5IDD/kvELGPG97ckmGo9WpUdSqDlBCJLg3DFXvV2zQPYzHS
RAHYjmgZt2IJfT+o2aRhF/8/IQghPqhnD5Ox3nPfZa2vIIJfw6D5mZqsecC0Rxb9
vB/EDb689T8OJQlZwTH5qHbO06qM
=dtaf
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '452afb62-45d3-4a0c-a173-5db3620e2344',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9HnWx/u5rNJ0AwCFBNdtDYSP7WvP34P7LdyuZWZRmeDsJ
VbvzzWD9gMBUUo4whCLf9SByaCoh8m+zBsOPZPOMRmuxCO16u/MeHzrC+BNC9WsK
fO8f9tHZswv4ZDSgcr8lwbfvpdBXMrh5ajUOdBnSNmGvKegXA8mP92+nVcwF+FzW
zk/tUwQvj8hbBCfSvRjqeNPHq785VR/pa6MzwpMSeO47eCaSE+mLpb4eAdg1/maF
liq2kmOw6ZZsHGe6SJawTuHF7F4/W6B1yBapy6vKvYBxndcxd+4/e/dCPDcToZ11
Da36mjvm2E9YLNkUZlaNkOcG65X1XuUKXctxrAEe/S57/u+QoAb4E/1O3MUOXdWQ
bw/sz6DthkNgncJtV5LnQUbzc5lpufM+x7a1vqw5ntjjx3eN2s+orHdGhhPURZ0x
1FqZtxy6axcvUBcDoyI8reoMI1xfZCglKJT9MnIUxXcqKrRbwMi6WazpZmTJIn7W
4LhsRJk2oWECVjd8fMVZKU68iYDvJ8owtWohRjSOzVsOGRX26NeHBRPtUZ2AQpEZ
Z+f2fkZdDGf/aoW6gKHn2VON//ps2PXKMG+n83pkAhI43NqKiiPmvRolPTOkktLl
1smEyjKac47yWPV+xhYQxNyw0zS320R6jA+ZMmfFS72+I8Nw/1aJpupHJEyaGDLS
QQFWnEtDwk5cvZm2EQc1eJ3jais+NIMhZRZpEKz2EZXoPEhw5OLC5CgNJ2B6YQcM
Hr4v6ENb/6qxL5Kq1Yq50HXx
=KF2z
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '45fe8d50-1bfb-4a7c-af96-e8a1b33fb84a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8DbUh5HVc3moL5+fQTbTOMDFZAX0XmMbx7FxZwKHSWRwJ
Bx51R6rsNZBvlxEUXTvZprBE4l0FHWtils0bcLpVYzVErYoWJ9Zt8aNIwJMfqZWt
RFe27iLpZS3FUxVYKpJOYZLuhzVfN+JxrDhmTGFiT381zlt4VdYt/Mrj7VMmZ4lH
cwLT6COBMwhdmV/wb7eeYHwLSVwVYSS+81lNR4WzpAEsPKsC4JVC20A/cXRFfIht
95bACzCppzjvCtMU75BZaKrpVEMmWgDou+tZCxJLEv3TD4lz1/ybGs7oEwJf/7y/
BbVKLyoTd11ZdMAEkDtpKxX9CL0curjTse95HrvbwP8t+AffGj6nzGp8hNx7+N2x
zMI5CxVczWrkd9GfkFaJYUU3JCWc2DqpHkc2kjAIDteNwE6Wkxm4w60C2eZw8mwZ
kJmasUiXekP0mH9POboi1yJjZiC5Brjxtc6831bITUuCmCUjjDYnvyIFoA2WiWs9
FiASFyDBvb2GgpSIsVao5S2Ayk63/gB5L4aWjZ9in3K6UhO7L8fSR1Qnh9Xv56ha
nIcdGDQUnDTZeryG5wCaEv2vKa/Dd3m7Qic+70Fqs1p/Ety19ce54KqJjiQLCUtx
TOVtuc49Z+W55VHLXbcS/E+nyQ5/q+f00dkqjQhHVvhJVUJYVIuul87wZuUvBjfS
QwEJsXGuuInky2Cg6ZJmaTkWhOCc8RxHr3jSLI7JE3Ctp+dOCT9d7KRujgry04tJ
YL6MDxJ6IsL0Ux4iRCaDiDsNv5w=
=5AwZ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '46792ef3-e511-42d3-a142-5ce64c96123a',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//Urq+xBK7W5utoF4ibVFQFEB5oBZ6NKq95TG6mV06CYMA
oWlo6e4dztIfYQsrOhgKh/nppDeKtBMZcSz8uhIEKepGf4z52PbpQdiQm782OJnu
+2DcEV3lje0Pl4OcpfS7tqPnuBMypVjcRATHINH4vUDN5RsMuNBnLmBQWX/UXvFQ
WDGAFZthgzU9d4Ge1M6wv/50H+AUE293G436c/+EwcGYFyMWbMP7QP0sX0Lx+T3Y
0Z3vUQng18ZcWZJ9V6FlFyB9ixFUh5zItxRbCmS10OX9rGQYrdMolUrpNW8B/yus
L+HqvB6V4nSlZkVWfZC56urXdMFK5vIr3cOzmaXy5iJXgij2IO0+02SOK03aWXma
g7ZNO1Jex0jTDqmQogPoPAD9YHjtn8w6pxOyGzWg7nx8p6s3vsb1qJuTMCml+IGG
iRfhzIUcQUjXff4I3lgOtcSKe9mDLJ5RE4LJUsbVB0aYnzxDy5XJhr1oJaFFo51o
CLfvd7Ar46dfdf5SWTPuNURGqyQzn/Z9+NVsxyEIOc8qWvffBaJYghv4zUcz9zrA
pKpwnFfUeCaRP8ulecae792Y3qtuhwsujFe+gEZj81VJI3TsOBljrL7xjVcoqid4
Ull1iZPxUY1rk4kk9tTzqiWxSX8kz7tTqz7GUabHHmCPzfBF2FALO3XQ5N8z/vnS
QwEu8+Myf4O3hGAbFC2hptaUPog7KXeedWDvOiX+5lFej5+jIDhGYdOO9Td4trvF
swYn/Ql8J1TAGqFwO72yUIR3mJE=
=blvG
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '46d1ae94-d40e-4950-a6fd-531805b20ff4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwsriFgbnBu9cNh0ZMAcaCX+831VO2K2zfyBV5NkEb5tu
CX21ekX/daXn2r9FxDPtVFFI01CheE9X8ZX6ShvIJc77ZLAyArthIc+F+Y56Izr1
iDzJSvgp9gpVyRB+yMoq7mXXtSjp9Jc/1Bf4XswpMBG6xEN9hsk/ZAXfnJbcT15x
1fJ2p8W4AjeZ4YiIa3stQUOS27V25fMicA7LoxHEtH2UdY8yJGkl94oXkcVQvkqI
Ee/knY+OfN+d2JYYP10dF7rLFzSapUXMyUVpW9DEarpHIlYfYbKgOvbUT+xEblVy
WV5W6/PvJrq3Qi/N9Ne/Nf51OnfXTtzjy257l5CvfXMw73rtpbzMEg/K/afVRwNy
Km8kdcaHYrPJpFiu8XenzLP7jkdRE4AaD/SVw6Rogz7Y5aHMHV8ig199baTV6zYr
0aFM5KRGxjCshE5PR+asS/PCn+sn2h19lDvwYl7UoVxeTFcvTa1GofwROHqc59uQ
H/ASFU06/Boq8SDFclUMT7mfecAbCAcQ3NNTALgiB90NOYi1Zck39XP7FECpkN9m
ST/tMN4d5NSLXEpywaqgVwWKW5rv1AcG36+azCWoyAKfKXH+96XoDTSa4aLGhtoI
ZR06Tw/iH2mUbilMSFoCqZMxIYDbARznfO50L0cAHPdZgui5uArMISDwhOp2G0rS
UgESfT5FF21cbYVqP+wz0XPrNWmvh+u9jFoJmjjukrQ0H5LT+Yfb2aYOJUH/cmab
Zb6dg6ayPxFXx78u0uSB4SKracL/TCqna3enEphxI4poWPQ=
=84cH
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '49ec419b-86a1-4b9b-a596-754abeb8c7aa',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//bVdFhhTp51pLMrXCRVerYOXC3bMY2MDwVKiL3thRsoSb
38fqvgVWPO2KK9+ufx9ZMbtXQEIpNNf1LwDHgSPY+ZCCpoCrIqZzmI6T4tDwVvgn
f0ldQ50i1NqORkFFJOwHxH9xtfRW/IpYi5Ba7P03It94pWp4dOnTpjyElkf1NRt9
DiVV9A2V1DrRn4A0NCtkPRZ6LvF26JrNTJ0OdnpGl80cSlpo58AQAp7IWdqvTfFK
hMNMzsByA5HCFvIhEGRMJzgYV+eVKQGds7lCpY8ZVROu9c83b1F5bXLH5xjr5SEj
0VfA8MO9YN7U3c+iJzvPnTa5U0s9rssy18zxSd9ZUxLwyns8MUduKOYRth5kIo18
yaZg06E59WGhoQ3aD30sfiR33srADZw6Jc/mU3vtTY3Io3iJ4ruxnlg0M0FaB8pj
f8APoOdvkKfQQLXEmbzAcrJOjWsWtAAUB4Hjo4ewYpN3kEoGvZy79ybPCPgNNz+A
AmhioN9boWWXkyAFYYI7mKuRbqJ/wTeCnCOe8HVRBpYMoOU6I9wCoehjyKSEEXCg
jN+bc73K7Na84pCkNbOUrxP5ydxFaJ5ZbUaiWfdLygrqKWYVefSnqn8w8y1YHnPN
LKxq2d6kTwc7NB/ftTPJngSPmFGOMlO2uvpq3zuf+4VVz6xgXceR6YHhQwwaENbS
RAGMvRNa+hs4PD6FO/28LInIzjMnNNxAzgyVfYZl2doLN4VN95RFhq+VfqLau50B
iITS54EPzD90OTTvPUPNUakBnez0
=T89r
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4b11bb2f-6e47-4d0e-a0b8-0320f617cd83',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8Ckt1u9owUFVOOao9RgiLY0jwhIK454JsgR0VZHaQLuQ+
cTp3XwErXk6fddWs9ORN5RRaLTHjX+0Q72ll+Evdj9znluH/NluQj8Wl6oQq81TR
RZhVeIMeTY7LMiNXAMV9l0FI6M3IxFJOhEis73VbAtoC5S4ulsNQLv70PXOhKi3j
lVmQXA5mDXpUOaM02mm23RTFhNCJBDPqR3VXNPA7cPr4KDSC0L1tcWouip/KPQky
yEGqlI7whVitadk/YQCS3VFKAQK+CXnAnssywdU96lc785UQd5YE2DSQ685nYSNG
cNVc7OyLrgqIfi03duUczwgehlHNnatolgF67iH9R6ffliNMjxhXHYZYjklTMFHI
sUuW8wu8eyYiy/5XVjSBHCmghHGo+9uvvIqvj1yUq5o/ZyT3+Ntjl0elJF8oWLDV
HYB6IHktRc4WJNCr7XGIQQONV5LRnMuCQP+IQCsGK8KkH2dE3WkiDMPYOKEB6Kzp
tVr+1cJp1yxtYWctTANDEkdAQbRJ+W8McceFAeKpklHyxnFh0+2dZ3p9GajhQL8G
RyIKiPQJa9tmPoq4lGx8vezthBG2UIDLy71th0/WGDEBe0DfzlMMNt37pQUejzBF
EazAPnPumqmOd693BzzCd/x2ne9KkQ+vIip0PqmAvrH7Vr/fBzL3oM6h5BAkVlPS
PwF0/XogHQvhDx3CTj0/ZA9dwY1FpJO3qaE1zJTUFXaJpkc/4wh8xYOP0SRBPxwP
+SQyOiAQLxcaOE7GEc/qkA==
=Lshc
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4b245752-b4cb-4cfb-a9bb-06be92f6151d',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAiIf9MNqwK+8j9I2JtbnDwDo2FQ+/i303gEvVGcuPrIUl
/Yt/ekMFmwTQwj5kB8SBH7JDQ9a+njDrNYgCFfHBH1DthCEzhdfWh0tif7fOtV6c
QnJNQr99NIaB/6s1XZGHXeBC6CkWgJMsRFTZ8xtGvgG2a9BAM1bLTS8zWhM639nx
F3OULs9r7/bN98PBvtnUmVr5iKkCiRqjQDMc24hg9/Ixbey6D2q0mURn9h+fmzd2
H6ke6VvQpWtBhyzlI9SjIiUWbDG/7NqUkQcOKSGMIG3R+8it4ZIXXgKje74355Mr
NQSPnph/mC5a1PK/aPQ34vwPutsTdaAYsAahgvV48cIvmZmb9pI3svIgpnWKLYqZ
qfLGClc+oz7t8Tnjla+22b1ZRxsM/kBoVX7nr+5ho/qNekCJGDHrMm/GRqaiwaqe
tcywvcgmImOdKKSCg5AA3Fd8QmYBkuKo3mEPcnydq5X94/fed5EOmSdNViv7aJmM
UMcpop6MNai2jMMboFVh2Qp8c5x8yBwxRgWmYrjyB5I8LPlCoznQraCKWHrE7dXk
g06ups+E5yH3JYgtroWG0OxBPqRVvJ4Bfj3aqZQoOlDfnyuacYN417HnD0W5k8gS
OWk95+YcdceLFKzMSNjjdewPQvlwoWW35jZCY4AfZ36v3azwGS88lhbp8NDRl2LS
QQHZ9SEG9aIsjQNC8Y5CwQCMM67Qas11VAjCZ4SzZuvOTTVAeqMH6DLbNcPb9Mek
QS7TaCdKV4WVXaovx14kOyIM
=2rzq
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:52',
			'modified' => '2017-03-04 16:10:52',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4d7760bc-aa25-4f69-a583-373c6fda8528',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9H0b5aqQXI+oqSURl7cfMDPnz3sIW+MC7E4TJwiKqLRpq
5IsYK0HX/3ghORyjf9+/B/ttdWwI39e/1S74PxYIfuOW9UZCjb/94jBgv3z6UWd5
3u/Tw9hcWffKi15SX35BF0Q0EIam0WaxN0tC/yfOfB6avcxjTwONVTjB+Rw1HDch
jSxxR6bV7wP6BXKoc7iC5MikGf40cWRLsoDpCFskdPKv4SZvwY82uM/FOxSn6Q4U
wPFRs726Gibz+ZIq5/Kg6MnuWudLHQnj7j1ybWrCzRzHE4fJgdSP+Il33CcyU3x6
S7Blu9SZtHGA94HdIS4Cp5eLwQl0UD66p8/EdEBhyiWyI+8mUsn0qeir0i8z7Xp8
zTAGomreRwcqClkT6+PHn4+tN4yi+XAcQL/xWwOGRD2fN2SXpOBPcLUkwpi8IDcW
hE56ALS3yAlVvU30D7WdBBhEYD17osm1IEAOtFNHV2/wSInMRIYjTD81FBEJeUPn
emuLBsZpEfftHi+fMxAQhjJcVtvbplBDP0hcdhkmd29SVp7NTU5HpOh711iqSPus
EcZdELbZ8ikxt7U/tiv4/0Mle+138p1SgnFTzrnBEGZOXfTlQHoEPvpBOResScX9
1D/91GSxOfCzk87Uu9+emiDc5enqEekx3BpnOKaGQzBl0YCjS+OB2JuCIgbDRovS
QwFCynDeR2YFmYHvqsjw3wSXbawUtV8zLV8c/4Z9XVWi1Fws1FpB9L3hOr5LDCEh
S51Po4yvQoSj6IKUpcQBQrmAXlA=
=GFAV
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4dce710c-5d25-4bac-a0ef-26b868222565',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAo5038RGRKpvPkS+YJcErLjdFhUTTf7T+iKQK9asR0SiT
ES+rO9jl2JEszuuU2rZHhmi5GhBHMJuVN0YkzHk8esA00p5cs7ABQ/ZqTBmzDyno
5H7Ysd5HP6+MVlSHvxyriZb5Z/c9Va/JK4re/ZQYvebcpzfqzb7gzZmpdhGkpFKt
bWTNdpKWEuvuLJLpAsGc1jfkWOehaE4ZLtPR/hUuI/gi7/b9S52JzsF0Klzdpb/5
lNDE5zNSupEX3sxYwxsoUg8b9lN8QZO4FqQkl9/fEgHfKR5dV50qt7ggOrqPL33+
sVDYE7A6M2ZolTevdYDd0syr2+tq8cwyLZCZuEkFfVv3Gna2T8k6yRT+5AHbs38r
xmCflG0tFZ98wLDZNrMas4Le/aDqRquBSO5tVkZYrbc9J7j69R+oDfn9vGqb3dRV
Rm9mfYJXz/XYAGBb8noRhsS0GLcPfSacsWjQwUynv/bIJp5WghM3HX6oLsY5BEVQ
PLwLP9f3AkfAZHiMX2AKCNb9YvhT6QEylOYJv8t9aHlxm4cLlkB4DVFwx9uAeP8H
+5pu3TP+q7QCt80nSZMSZMPB6+PXTLrjRlcSSl0KHNaEX+IczppHcKkIAyf9qI3W
mzKnDdP89IwI3bTdfAaw2Q4yWSyjOus5KMDfw6e3m0BCC2WHrJtOYVNIiC4vfK/S
PgGMXupjm/nJPgVa8nX6uimGE0pfpt0emwWKb7XnC0ipxo4fO+swfGq7lWo33797
u35LLtILoqlFwRCDpvLb
=RZZa
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4e6b8b46-f4f5-47db-ad15-854a8ef876a9',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAncdyXY08GUK7hoiNk5oWXG9Bz2aWtRPwvebU2lfTlHDl
IQGdWNasY7WoVQ5rXds88/YrGtQPJ+yvsp7W2cMk7Qzws9FgrvRJd6bFi/IgxYob
M/fUyicOKmVD5qm2esJgWwX5mR4+tZ6asuWizV/0PigAcNTNEvzvuEzvJzAsKovk
3wXkpJ7hhSv2tm1dUmUXJgyYwMPslZj5HZ4UlokYd9EZAvEa1hQ9XfLBFD8Lv5Ja
Xlc+LuXTsXAbEX7WMTMQfXABRkyGFr4Vi69SlHQLhEssg7q5ld2TCEtTrkhW4W2w
bHzNsLoenpKoEN9nM/+rZVMchIiMDHyhhG1P62MTIbDNattRL7Bi93KsiKa8p1Cz
8a3vTLFXb47sJ6z/mEtVZPjHZMhz4bA6czd66WeX8STB2nw3hOp6vLAzEs8fVloE
tnQRwe4yjw2oVoPtd3zbBs2GkFJ2gVr5U5MvNx7kzH6gsAJ4jMexIuilCu0ClZCE
AuU/OJVUyfo85P0aIROxbDc+g6MMuMpjSS9HVdvN5Hux589akeOrdLORaBMBmMAr
pbIEwEpl9d1hbi4vCyEz5w5wwk2ZP5VNxRTLRsOS11IdcjqUiAvM9tZQO88E8wjo
QMqAuQ70pXVObvcREIzpqqS21BYZRmK5CgcNrdUT5h7Eb+loNLStmJPP6/rch5vS
QwF5KtR4JTQ3lOAa8l1iTISu4Z0PVr36jtZwNAX4BISlL440eWfg2kWUkif28XzT
Sxsy18myN6nrwmooQBNKctOm50s=
=6PCU
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4e8dcc2a-c383-4e40-a39d-07d5eaea48c6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//anfTpGIkxMmml/83f71woiuC7BLIj8bcBq5OP5wPJOBN
jFG86D+mEfEAcVCCoxQ4AjCRekO/0uthuMqOI4Hpy6w7nba7PCN/XsQbTIMadSwC
scKNYST4nmGJYKUuO3EwjOkXX+iBfAAOSmTWViI1VgiXJj2ehXJBxahFhyLUTjnX
0TG4QWGt2DQMPEUuPoUazwhGH+YKRiC5ALha6UQmJG2KobeUMOYPaOfauRq1aCAP
nv1aIDe3NdSOkbU09Hx7xOLTeJuIYW7HKWFvV84krNGQ2kYdYg3+hI/fvmx0U2sk
RQKH9grdx3BQuwd2IobL68TlJMpkU4e6eTTKAR0l3hzZg5JKmvg+yh5lqKckSQTs
1QLy+5Wr3dnFWHkrHtQksbq/EjAqFLpfn7MvJDvxOHuYe0wrAoH20LzURAMsYOIt
nDtProOKMx6F4d4d+W+p/nNy02OyjGA0+bz1C4z2HbECHMhUKvJY1is365dVlIMP
bnNrtIIQvRmndr9NqbUgK69qlDrICDRNx+z36OP4kqJYneexkchS0I7sG4nvddf9
YIhrwO1NYpnYdS785HSNt7GgqOfmFKn/Rvd8hKwQPS4Av8sbJNM1XUtA6Ak+TIOD
aO2qv7/pDKe5uOOzfMvIpgrNnQWKvsZS2k1MzHRMTjBb99FFDzEgcf1a5Nzbq+TS
QQHj3S2wLiqkHG0q+18yAdT4koeSNZkdJEuNK0gVyeUkcUMSlt+ErSQg5z7y4TNA
JFmMTF5ecu2Atm8qOkS+DEIS
=Kz2p
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4ea7e22e-243a-43c9-a721-d72da92150e8',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAsywhu6mUV1mkP3pWzAMqelpgyUrIROKSzkEJvQkc0UBW
AenHteUteWdOW57NIvIYFZxP9M4EkolESwO7WSx9U/ccm5Ki6hSoJ1EqKY9OnZ4z
wf4HtQ/EiK1eOeoNAJj1zyg+eLMoHmc2qBvmMIplJFk3BwHUYj6m9HEnNUNYLDOu
cFE/vxFVDDZoJPnwJvxih1nLbvKjtMwxrYNwzrD6P92nYacVtzxa76ZYREJ5dgi7
w8TbXeOiYLzFGxOOQda94XCeO1YScgCDCHcbQ3iZlu1Rp8fybDQhI5oy4WG/+cr2
f0599ZTfS4gDD8RAVuMrUeVFG36g8g59Yp/2OLeo4SEx1aaurJof70h0lN/hiWBA
YllJyKPMIYQVmpFrVpK81YevGjo8hXpRaxiYMBZQC2W0yzBkfgEokWN818l8XkCZ
NkI0FGBnik04bnGnnDggQU+BKzR04XpA+25IOpFS6eZefVOQK4qddmdK+oVd0CUO
sgYt6uLN30zyoO8S1wCRNSb5emy5fN6E2Abk2ufA7KcWwUEUeCaOfXu5kSA6mu5B
bVstYi3ikOXciD1O6IteyTeKU2Um/htgy73rihSkPr1ad3r6Qh0abxplsmSdjNjN
VYooQO2dDJC7pgym7MrMj2cW8wiNfWbnM1490b3w/EBgukOQ2COKrATjajlZCPvS
PgFK6SG+8oM3u0OJ/Hs7AaFyzs+cBSxpebkDyeRGKzWRpO7r2izSQX7cmIUqm6+b
cpomLbi1IMMzZ8mkMS2T
=JqrF
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4fc298c7-d88b-4794-a512-8b1bb7e5e44f',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//TVvEYofc6U25taAF/ugzAdbPZFnnk2wBRN/5PxArE9fy
GSebMjKky5ER/XX4lJQ1stmS6T5eKjHWR80/RSU8FJcnDc1xAW6SmMOMtabBjMBh
KVV5FtpT5DAIioni90oHjliq27fEBYQrhalDusCi2Ag3nvqNYmzeikOR/vo3m089
otdkJx9tah5Cvf9IfDA+N/hY+6Rirr3B8cnFIHpr3VtcXUeJsR1wunCohof/YiAR
xqvD+sr7BO99xAUHvqup+dKYjgbwLzcb4GYFYUnp0PkzC5w5ElQkXyuG8XJ5C5OU
pQR1WllzToQx1aR5PWRaRVNrbe4cHP6GYBLhl2gHf19E8VQOAokNiLjtrb+VXeIm
rba8q574MYvM4zcEvkBSE6BCZPi3ZJ45jLaQkrSqy5WQVgQrART7jXaTQz34qqEj
J4FPZVVCPKrEkLWjlcVI7o0oJfYvVklWTIc0Ky5MAl/58Fe/FL1xrllYT8vUSzN8
SVs4OUPMzMY66EemX9z9KnmRoJd/vPQpZwYA/BJy+TD1IvUnHbpZXvfRi3XK73Oc
LCnQ+6JQF3N49lIkFEoq8K7PtO+lfjlzbzI/fUFTyxQxxIfqap3RsTer8SW7gsPU
vt5mx7UclOEC3mn2xHWejjwilotcRlDiG58yol45FMu1nhXqcoxq3NOida9Y4T/S
PwE1z7/UR8d+EtWAOsA0JChv1D8Zf3njWy5jCm5ndnOplyNpdNBssdjnHPMIntos
qF9YYvbjoLoxoZ0XUM3Xww==
=53P9
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4fe762d8-2abf-4123-a07e-2319ad98b1e7',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAgSj4VwXLnYbZEQhIAMxuaqRG9J1iV4CpsLNzmX4Tn1EJ
nCLq/oBQz9QGJqehJSBJka/QcZqXfsYdcaO3vbUMyDqkEuk9HKoPNxG4f2dhvD2F
lWzscNH3GUfbMmKBX5F92UkF70AfCt6EEAlCY7B8owVftz3zR+1Z2uCVqmnM5TIX
344RRhBtd9MZ0bDy6eQFNzxMRmtizIZZXvd8UAlvoumZdXu+bXykBfsi76xXp5K2
hJCT/45nUkacEYfYMAjowU2KuvCE+AuHMXuGBDH2BiWudw6+wAVHKrwMHfj13NfU
+npihlRq/UICy0Tc3mXfq1rmyib5ybFcRBLnbQa1NVqUnlYdHbpMRNuWINAnTlIh
lLlKh04VDuv4GV6nowwaFX2pkHf+KeVFLMB4P/tHpgFWiLvMbY/jcuTCtJNXi/ku
q+HoF9Yv2tZKXwRxkZ1xtV9Fbilul20hIPZO1mWe4N56t2ZK0/2sIfmUEwfWNJB1
32GQ88wGaNYS0TsAfzORwHfGKILLmlxF/LyagS2vlbumqTaO48T76rnNwHg7Lm6X
1rqEyF00fYzhcDfDRjor0mBBrPHuNH5e0EQUzfVFPP26zJHshhHcLTto5DPsNPNu
RHdQz8ZvCz2iu2IAl3ekYDbTkx85wTqRrHT74hTUWv4ekaKdiW0pAfBCIBHzQ7vS
QQEbCWbiWPf+DWvDhGc5FMdAJbkP2Ys3Xn71c585tWXUTpz6D67ugpRBA435UTiQ
3jhycDubLT5tdWDBxNiPmaaL
=r8nS
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '51c9109d-8f92-495d-a821-b26dc90199c4',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAma/zJ8oPzJBGXZ1MCKJNsOBkHMiTMcYALfdE+3rkumrc
U1r8258MRljzH9MO1TvQZ5BJQtTPzGpYvC2vm+AAgBqAwrJubcKUoHSFdw/MtkBS
cBfnddFfWr09lNCbNT1ipvHysxQYZ6LCKsn6axMHuOe/t8LTm79VWc19P/xaX6p4
g0rSg72fub4f3x/A/gcZjcbuQgjFfv9w8D3eRNoQa1+B+wNwfv9q0D30x5ufmCu0
59NLpLIu2J8nAa0b9TZLxQIBMZGaLmv9yOpHPbK0kxBYTkjup37J+Lq/xiWApPFq
Pb91jixtBeDXvH0M394q7FLrUhls26WcyxxCuZoH+Hjtab0yMgOic2jqPVSSxvHu
Hf1Bu0F5+vHCSOdh8q+hqGYrIqbQsKobn6FvEsb4AvB6TM7IKQtbonnSDQP7ud7H
lvkyp87k72YxvrGBByb7TuD3v/duLcJ6fhqbjPyI++Q9dyVXNz4quEkXTnVmTBo9
BnbVriZQiR+IJ1AZG6szntFRAcF7j7dA/9pyGIgJiroKqv83pJDACm9BVQ9j2dPK
9tUuVpMrQJkGRdQVBGYeg+lJf7R4Jp1ohhauUKR/0fS4NFTmfNen71P6ygyX1bWL
s+ZvFROpuY1x3rBx9LduxNkfYmf1waAdQFe9HSf3moPx0TpMPaeyKvZV39SLPurS
QwGrbzZSrdRQgMvq1hOncNugyQh/v4OTygd3ZVrUCmmdTj9/viYk5x4vNX0Frr5i
jlMpWxEMs6dsNAaMphxKGJczTgA=
=91uv
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53afe69d-5b0d-4bc8-aa8c-8d7553668a4e',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//YEZsKp5rOBH3JWNVsg1kIz63dJNFnsyUpNUCwPR3axhs
wHWg3l9eUBq95OKTnL0ehCAZBwPMLe6Oj7xOSfhBHB+rqhc/8vNFz5v3XgBJ9yZn
EB+Yd9UpfJ2zW3MgebI8DpIzfaJe38v0jEgMCxv0XFN+y+pXVvh7n7d09my3VXcl
4M0rw7GKVmj13nOkV9CMyzoaMulr9eoClg9USt2jTjZwV6bsfnmXqk3UjIKON+2S
c/xtH2yrT7FlgU0gpPNznOoAXgyR+kyL7ngG6fwVy132TJLg05zTQiZ3fi6Pyxik
oNw4nWjKbtek38WjCfy0nH5bStJDBxjIe508fMGvHqB+6ircFLmTEdeQC7aymErj
o2jrfDSuVjb/SjVC76cENp1dtKrLz5EnT61hZ3GEYzQoPwSaKpJXuDUwqdyojrQa
RzD8dqXHY5VxDjX/+Xn4Aj2mNt4uzK6SAOvCsdKPeVw9yUCG2dOK/IkL1419UPh2
WYqbyAzMf0Ip8aszs4Hx5gl290Gg1xZYEwwcEBN1m4UN5fxtb0AowZpQo+GH2aHu
5BsJlCQscy/cQeVWPip/ZJeOhG+G7FhQv/0QppcXCkaBVAHO7b/el/FOq4cXVaa5
9pw8wqPEU4bQHobb4fd35iV/kD1PtC6QIiR6RqDikh1dgmv2MYyalIUacmSpBlHS
RAEOuAQ4lcS6JQsG6CkA6VmJelmIHx2ty8cfGDJyud34D+EMziMXuQuAJ+qbTBsY
OjicKH9kGq48rYyiIBZI+KtPXmzJ
=OSiD
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53da1b59-10e0-488c-a93f-673dbe7a4703',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//WxBSvMDC5qwQjXlEVhH6R5jddir/K7jPC6BFmnwVOqNT
wm9fTm0uBe8FRWXS/hTy7euIjYaxJeAFmizysIcmwrWUa0e+S1wVvbcDED3hfOVx
tuU6PdrchQuRpSMPTaKpbF4tpECuM7sqrGh72LBUQRwu2onCZhHBTi1obaSWR9fg
aIvWjAkN0O9YWsWkX8RNXFMEeZhCvFtHSIvLQ1FqGAXBtTpBt5Q2YreVSZw6U3N6
uD+THMt0TaTxYFxuZZCZll/GQGePERHIftkQ558O9joge40A7+h8KEjQpJPP5LDd
at99k1YVsysWHSgq1D8s4Swiu1UguhaLrmtu13yCn575COLD1kGkme3HmEy8wMTz
XortiAJuhWFN154zFu4uJXzaHrV8sEVYCoDl7FsVk6uyac1NpCP/7UW6c1wHIwZ2
olQZbxHpOP9TbALRgOo0V8bXBqjTGsHzeZTJw1jhK/vlFQGQgpd/GsFVEiuy5uRE
ti6Y6+j1TPElyr0mFBjA3lcfSCRUjlhte9lt+ItRG208C5iR8FluosT/5W3qGEet
UTPNZxCTR+KK5nX0mRzOyd7kQyoDlETkdGkViUuhcTU17hI4zGcr523S5ijfhH/V
dW1v2p2DbO6kYmOio/1azr22sLgH/SqQFnapnl2UWRJjyLg/zJejUveNmRUil4nS
QQEVdGOKgiGV71G/A9BXZ55PcNMjlYyxtUhfcRm+G2v1iPGe6n9c7aUIJiwmPyUV
S8cWaDmC0ZrvBKdEy9rfIcMh
=BdWp
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '57c2d6cc-3e4d-4af7-af4f-937b878aced3',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAjAmh3VRE6Ds5i/W1HAmRh2QeGr9TLrXljolx/C3XAXiI
AwWZOq4TZPxHr3NsxILUFvA91kpyeeXTLnWfXrrmJkD3+/D+TnD0ecH8exZwv/t/
EW4dbDXI61H1it78xI71a0sBPj3lWxfpUBDky/b8NmCNw9T2sO6UtWaiZvgPDres
vkEqE5BNwi2HaHEa/Uv9OTSUco19joGou1gN0ZpIgqi4rZhFkbzKEWyMC8ysaqQE
7yQhGzoFFRTFThwMKP8K69Wx6OljNlxFcAd+Rs3m5j/CGJtDjHFywelXJRUhuVed
frand9UMwAg7E2rBB/nR3xUE2MdVXy9OAWCyoOGA+pBX5xrhPs0sbQoYppjR4OVd
shNNLxbZwfmeJIeLA7d1aW6YhR+B1cXmhLyxN5Sb6a9EgiIv9IhU/DGB5JTWdx3C
lZxw2MXtgQZxmQeUt7oqBUT1hQCpS5vysEikLPgGKf3YYvQfiRh9zvorQ23XXKec
+R05XsKDoQJYAEwi6ddMhL6wjLkfg3ETibgJelxutrcFeo2leQAiYNsuH2BACB0J
rnzjp3aomXsp6VK5RWZgSu894HxZvlGLcuhuTK68h3aUuVk0jqcyndRyq2qAqOqf
vSScKj7CQOZv8m+MpRB0J75Qp91EroUWUN8LgUCOLgcz6UVmG7lg+rM+9FLcad7S
QgEzJDdJp3P0Hqztio30Auv51CtlsJcife7Aoqq7JSXqUZrh6M/+tUHV9HmdBZip
6Quwpi599c3PiFns+Ft8PUc21g==
=AUcq
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5885310f-6096-4c8b-a178-f872c3d906c1',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAijtLWoAFXOcVTblk1oaZJgSDTS2ZIOf99aFeAHh9jebw
5z1hYPUrGaMqVOqgT178DDDrCitFFXpEwv/WZ1eikA6ZN7KSM7VrchkTxGKY9s7G
kMTr+vkhcohseq4TdGCkveu5n+TWs8BY21W6Waye+eWJPuwzdS3tgG+AmSV/ytwY
rZVEkUGcX7k5JHmTqPBtrhuLEdehdVygGu7RXiyJw76XJ2rPBlJ8Fqk7Ap+U89rA
aia28F1qJnfZ38KnvUpe0PEf4WH/ww30Gzs5lyn8SWHWWJj3ZeuDkvVknyp4VRIa
jumRegAoabl+Q7sUVhuj48m81Kjd0uZ/CEUUKK7X1Dg3ou/WOtXcVfs59ZZiKcDC
hbHku2fv/w1SAiFWP7JLRv8+P0H2g0Tj3UzJhOGlS7eY/xVuh33AixwhvlcQdA08
nY0aykZ2WRERnAO/8KtRyPXC2TsM86a5yDNKO8075bpgoxn7a82MFA1X7A0JZ6pq
FEFlaeLOR6ZMeerDNlXX/mRtLCnJPNieIdjqmF9i2VnWVlVhwhXGaMAqud541iaR
ZSOmrGHkDmLAudfe1jvqV0YPYWZ0Jp1pwtF2jmaWskN/rBV0GxC3VtREaqhcaPq7
NghAlDYyRUgUAPGPOa6SfsL4yh5pX9iZlwOIGelJErZzyazW2jnHsErop6/OgNHS
QwEr7Z3JrvorqSBZDtO8/5CQb2ZK4Mylp91pAF8ZYzE3XAPcXOsXc+7QpbmWe4Ly
c/pJ55mDX6IPtpEnftkpvsyiBKE=
=S/k7
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '594b1365-b717-46d0-af0c-c310e4a4f63c',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAgKfLHvw66m1oraQoOrhco7e9AK3vPV0zN51OQ8DiomI3
9+SmlAismI8xkt9QBuizT49zNFPM1OHkPjoQIRhyzOnyzd8bvTOvoYoZy6QcJkgw
cPuJgwj6MMfXi00wsrfMAflquts9i1KveOn1rLnJv+l6uGjuvJeoEcgpTwCBPMYE
PI2s1qtIrbs78Nq3M2C6eWPpwx0P9ANKj+W7q1h7L1DI9b4ClGwCZMUdGk3fTmfQ
evIHYk/XZsuUj2IS2lK1Y6jefT/T0c1kbK0TjGcwwufP456FtByz6r8R2qJS5W+z
XnaV2xXFxYwPL9VZF74MYtTzXHQzWdHAUugJ73N6CVInidQgcARATrmEhjPXganG
VjQUiV0ZKF5gpHFm+ckKkyiJEmVg0YXFuBwFYM5TOH2tJZisGboYhToOf/dLfGDZ
aca7MEWTS3/0h4mVdXt9KuaWcC1QuiTK42ijyeg4HfRmZIHmq/ISGroyKb0gsftd
bqmoQGjFJMvGJnBphVHc4tiJ/7uQP3pTvJ5X1BwZuvzoKhtlZ2RJraMTmZGC8ONb
/byliYDJADehBqVfK8YkxsEEsYnT2JG8pTjD2MjUV6tPFh89aXgMIyUaujrCRe03
rKRpNblUYHB4WftwIRAwCaurzC8uG4+1ip9SnUGL8jH7A4gvpEeBfrRtPgWaI6PS
QwGtgKT6kQzt0yIWKhyFgTNME9HW5KQEWEksv1KqvV6UtiHIftO6NV7L+9VC20CS
SvghWb4yT/jO4qRrbZ4ROLTqETM=
=QtOD
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '59c8e705-c4ad-42fb-a1d7-e318495bde31',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+Jy9hKhmnhd3afbL0APi0P6Rws0C5b2lMLXCSDLhNFJDV
KYr7yvoirAm+p1pkWHZDsyO5woUVeG5SVQpOEPDblpbw8VqSbjjuB+HBv0bYobid
LzsozxCJdI/UDfOXxZykqwmEugsUM0ZqqHArE9C1G2gqD41Twu8XoK/JCxgv2zU7
sFmY5hCQ1B6Ab5C0anTQ3slzZcKE0BZ1eysRKc1nT5gCodZuvOULoZr3sapUxndD
BsUIrxIqD75eBtd74ZlRFAuOYHN/1643LL993Cx4VH6yCzRRXlHtb9yBWMWrM3n/
VA2632UMUw4wce1dhDFn7MtcycAJ+fj5r1x/GmQdxE6aXQ0Aprfe12oRo2eVHYNG
Hi7DJYsFSykbb3N7Q3U/5sYbm17lhSQ5FY+R2m4TxEcS7sF5bBL0qXFjZR1Phj8L
hku8c8YM6nCpD+wWE8AL+aEQCrv4AZxZA1aEHUvKWJzWNPef43dtkicLVKs5iuut
ojlR1RUsl4V/okakiuXQY7v7W8Vd57xc+o7Wtd+DKIKzEmVi9oHOt1HzCj3jtSvQ
+QHb6lg/tBVUIJkV74Z0UIALdAG8t49v4vR6A/JpBmkaHopYeSpNlrgcv5Cqw9kq
aOWPJ7QAJhhYQ3h4amTXLC3tXa67fE51e0qdk/LOSBPRbs+FdlBpN+iyz2a0kxbS
RAGikdNMCZnqf86x1tpHpnGSi0ZVAgGO8RkiLlm3ff7U7RRHFL9sAbHWvaiz9iQV
yIGzZ62BO+T9Q8yRJoSRFmxFIJVJ
=5Ws0
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5db30820-3925-4d70-adc5-a26290e1f1ec',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//XPUphLNuL4WJfRPOkfXdnR4Ii3FbyfEY7x7wpt1hwTE4
qXmRmvva/z3m6pcAz8YMrkr8eQPOZKLf0XCdLyOvLyX+0yOYDfVpi9XcEUFbWrwe
psD4wMyguY9ivby5gwoqkRVCv/LcayHFeYo1nhz/mlMnJAKFeoQY/pNHI21UpHSB
L7+i9+RSpCBPKuLn5i8nbkuqDgK+HqsOcfE4wlfo2AToB6CmlQtnopm+G+bRuAMC
ghA0JIH5DIoKI1SRWS1NlQIq2x6NfUEblzur2wyTcE/lBFKMQDkjUQmBO7W2fx3n
u1bAlovc6NT/xEzUW+UJFhcMdcNOkeJDe2kAulzUxkoa2cChhuhCGyityL4UctZa
ondPgcjTlYCdUs6zkCOj3HUMLYya2feRGXGNgJ95D/hvgOSSetBOPbthfHQduPf0
zAYwDPsyFlBwCWet/OcPoTQ2uso1UAmZW5OhjLwNF33vqQ0BEbInbW4Jg2wWuXSz
a2BwK/lp7jHAYi65wevOMJCsxX7uBWMUMLhaY1AISQDnXU/ErmBt1McTuwC+VnzT
RtSEBv9pYITh6yvxYyPJN/irX3wMGhdI6C/xgnNEfxE17mzMVZorAdvrToodCfrD
HTENu9Y4Q6Nfz0z/5gE79YCvHyuL9t9Y97ewkgAX+FZ8chIcuPM3e2vVep4Is0rS
PgFenTd5tFZGxofpgqXbqUzurX8Tu0trngWI/u9eOIETLlAEWwsUfNVV5Rd4RNyx
7IfHJJifdgiZ7WI2zHek
=eKM0
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5decb44b-a1b0-4a35-a068-bdd872db5ad2',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAsLKpxYMZhcVwSQsv7W0DXoAvrfWwFtqNk5doXJMcuook
UEtvaZNeCXiUxzwB7FFVrTfz8l+BEfAQ82ijn8w+fbDfEm5AcvCdgEogblsWHg09
evbMm+dKmNweEN0jh/d4yYc/zP1rOe1mQoS2YtDzFXv/SN5HH6GN1vjcYp2qX3e2
gGbueiHi+xuYpgMhfwBDIB/iGMleVB1Tp2tLOTyE7A41rmd8bLP4QhXrf+8pHN82
8PyIy5T+q9xFKnSLi/rvgAhfn7d3W/7Usu50FoHnQdS2+Wd+Dzg2vML5iuyjfVzz
sDqJVueL09I9c8xm7R/2kc+Ste2MuSftzPEz7lYaYKZvNC5YpA283mdBIp313xc2
BZx8Ur+aVEDLd9kpqIlufbEJFJHiW3klTUpcjOUOlB9neSXcOVDQ/BHLgCmvatRL
VcIXmR3G2eZ5fRWlFnEZo/r0b3gbjuyGB11z/32NDFOI+L54QXmVoagx4ALOEz+0
FVUGfBpxw8tK+TAX2DlAyweda0RrpJT3cvH4jG86HOuJpgBVLuO7Pc0KdNJE8PB4
7Lcy/GKMVYjzQ5aOXUSrv6QGYrOgGWSbMe3NPgK0m+854LVP5kl9WNU2QwYsQaj4
Y6p9h3yJwdTr1TiMWYcYL7CDp8jWuDOji4TGeXBXvo52en5XZCUbIvIu1KmlA27S
QwEP8Dtri5oB14S4Ktw+IfUpYLnzDT1fMOei6KOi5wGuZZyqDikoZdknxuPL6vrE
G3GNJjyl0nSBpNab68Cqb6HdgBM=
=5BE6
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5e466aa2-42a2-459c-a3c5-a2ccb2db2510',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//cdvJyxOV+Wx5n09b5AP84dq8d9/F3nMjHAQGWlXS2pxU
yAwxUWzCr8U22/u/lTEu7zIoxov+N7QUiLHr2DKrkTFn3RcNG7Gt7PAVQflZhOfs
kPukQFFPBNNK/791u+pQKfsABSOwYNDemrK2i2MCcv5ZISwHcGpl21qo3rYW5KOq
pD1HtOpAGTlXZK4vgYXA3a9qTEFzSBVCcRn3bFT+DkGlXv+qndDw+hzBRlGxr7LI
HSsJ6Iz/OOw2r/h7TLhVMWZlX4uQgLXZ6yQwq9w++/CHz/GLluW+qwGlC/NB07BC
j6QCtb3PAhNR0/Ze+YgrabS6+suqoezZjT33raHdH3YYvnTV0PNPAL+VTfffQXfV
sci80ias8ieZbwBHmMhKdRWW/DNPKQLYgGwHF16mV/9SUXgMfIO45OYg0Tbxec9e
Ml6AmBga3EdoA1jA23+z6/P6LfVG+wXvOBMRXuESW3MSxqeDAfyibON7YFfiqvJi
tRClqbqvTE8oA9IVBlrMOIe7J660NStsZG/Nto5mtimEcEB1UbYM2gI8Qzrz0hjV
0Rpjm+4VSlkboOruwdebCoMrGNy7EtO6JYU6tTJuoKxr2g2p/HAwvJyXiNCqvfYB
1kvqSmdqyhgfXbLQtv9oVKWxlhINJ03iCViuoMfT91m0WaomZ0T2qMIuDb2Sx/nS
QwEv+/vcEQVfp6QUHGRtSQzO9d/AOGMPoUVWEMET/RB4CSrNn3i9lgOfsHUSd0Zo
MCciwBGqmjJVOKQ+Jpfx9bgrpO8=
=X/FT
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5effff02-ca02-4fce-ad5d-50271f038ddf',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//Yv4YCtLpzDnJ3/kAXAwdj8+F8jOXb1CczHEcYy2pg46H
sDw1q2LNMRSJcYBJMAZBoRGiw5Nsp70uLvTqHWXsN0aJBjf603MC2huOQnOCTCMo
dodPR6HV++tckyu7bc7+xyS6YHnvfZ8gVk8VCP4U1zrV34EJrGa8UG5Mr5mBY+E1
AfRuvRD8jtwPbcT7g2TkqmavUsmikR4M3ZKAdi+05ofjTVfDT4SzQwjmu8d5S40a
Grqqesc17+veIZv6ba7rO7zbPB+KUS0bfHAd/9z/BIcwMQl03u+7vpRY2GZQpr8/
uSt62fIUHAtW5osSm3zRyrWThHE6kVQAvfqKiB+pb8Fp7wIlYp7zlYQJeQ0U7pJi
cpyiqosLLGiwmV/g5I0Ih9Cgsb2SiT1jlrbxoRB6wdqlh4XmfPFbX20eFs9/7173
OwpHDB5CR8ZlqeF5I0ZTzmuYXdJFqbsI8t4y/XBujoOpuLvLoL64egtz6QB5Lgt8
OQrelWwLr6uJUysGGd0I6b+wruQmz9q9Y4VlqoQG+LQ+Kx+hsg69GanS6rWDM8FM
1I8BE5Jt9f3WfZnhBdeG1kqbb1YovYYhUEYZO1mvXeI8rHC5AKlH4QNVwcGnLEvT
UQydx+7u3MT8QodUU5+2v+mmTXy5Id0id7W9/NzND+FqGSpjW4miYub452FEo7jS
QQE7/hUUfOVre3WsLDgl9HJzgwxI9yPFTuf62i/qzT/tRjSd5BwUIXz1uSNFp7Ik
YCudr2XSvgpo3hCtyl9MkmOV
=dsVi
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:52',
			'modified' => '2017-03-04 16:10:52',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6171ed3d-a02c-4aa9-ad4c-46d64095b242',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAoVONDKQNrUd6Rguwf6Vv0I5XCW+mB4dKPqVM5+6S5/u5
Rq7RdeIveIrT+b0yg8k/Wi5McpDSmhd7Vx8LeKxp0rTEu5Gg/df/FwYjREC2IEAn
5xioGBqoiA4FG36vkvOipzi0+glNNd1zHgy8RgQAGlm/A6GMif4zrMH6vQwdsQWj
80PU0s4tOkGYL6vMbF6TMW5d+ME9LbbgG5PpgIbsIPC4sqnlWONLjQ+/B/4sxil3
2YirGvnPYuPA1A2B1r/GLfRyYxe1Ch+yMsWKuEYMCcZgnjwGB6BaJh3ivTEHh4yN
D+KmmGyojjm/IE1bfV5bk9tj1zhEKtK+ywh1PMNKuzyOH8l+2yh+1hVJDsE5qt8H
sEYix1ySrjzkOdfhErhzPMUU2Eeqc3Up+ZscLQhD6+xia0/s57ds7rEFfEEWP3e7
FFtt99zCLHTjaRz8Nk4XKWUhykXJwFN9SyiKgVYJ8IM7nMxe4bugDbrKKYHRP8Pu
qktoFenLmwXV44tsw3xkL9Sg9+8WykrSVDUKdC5tlzeqo52wWzZsYPoWjtTU8b4M
AF8fnI12YPygZpuONQnSIu2DKsnGlVsupwDCohBDVX1F9OOPmwf2VbDpFhcC/nVs
/OR3ijbIlV/ghbY4Ysxz8q3EXlGJinb175dQoNq552sh0wQjKrFM8LzFdLuBFGbS
QQFigYiw6m1XYGjNYXZwiiiMViQCMEI8ng2CDoCLosASSugrCbt1vjdDm9Nw9Ml5
GvBdmZIFQYhFtJJkjCl0i2on
=eH/C
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6194df08-401b-4fd3-a07e-7441a9a3b54e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/6A1S2ko1JGBlwOWlMVbsWJ0cPFz4C0uX1FtSMlnK7YIH1
7tZXG/lnExZtDa/JxyAYG7nrjHRulTTst/ZqmN2OtQXfNuUY8s/fquXtEK3dAy1V
MLv6xHLjiCCIRaFIUDh8WDAR9p96zRrcl6ZxLaTJlJhrXYwNqBGiTbcs4PNZ4uRp
/Z1On/Cey69lVKdMy9uJDRoJ3eZKh0BGYcSOF3w22OWXePW8ijdvCORCH5jrejHY
izxTcyqaQ049GpuBUgR/NPGNqswzDNJzrgUk7W3mqecctMgX95FumdGiE0Mmh+eF
hFvBXOhJkrJdZUYvXh2LgJXXg7hYnyY7dGKIkbazQWmvE6rm41p+2F/YK/yOxh5R
Cmz7c0Pb3dr+XLKeTb9H9fZdt68ue+6XjAvBe8EfpbMtDuZpUvZWn6oMtvlT0kMG
4V/si1mcwOvUJJXIojlWatT49NPNExm2PxtnGHVNBDdF12yFkcdB5Pt94b1QJLmn
XaLh7XFbiKoIazqBRCg8ClCzUIUDyaUri7S52HGiGBCoh+kF0VLN33w/XLwpNw/l
yKCS48KqvqPYfb6y47XhTrrCfhPDFAy4U1B/cVXVQ3JjiLsvFu6DStP+SHNt4ZhA
GkVql9/7ovThY48TrJ2bixSa6lsWhf5brIurYzCMfH/BJOOjDnq61uvktKg5Pb/S
UgFR2tjWLOZ2sTDKi6cPz+ftiWOPAn7noQJ8YLJQRLfvDaxHnWw+GdxvsJ0vMbHN
0zsG1/g2cVCp5RPFW/gywYJZYfXp97Nljj2pbrHvWXwBgdg=
=wgpa
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '61fe578d-5812-498f-a316-bf9eaff59d25',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//YxSSA/uV904weBe1NSEei9ZOd/cLe+D4QBmmP4uKPswg
Jt53yP1Mv3WHccpaTTMWpkLeZcgg13txMk5Dki0qoEi+2AQkzt5jMbD6onty9xNy
WqYY8kKidy5MZPa/QfnglPzn8vhcL3wv1USE5+Xe6NwBrWV7MEmaz6R9oNLnboco
DjFpT7/BZ792jA2Y1828iOJ9dj1cp2cc63fc48ATFDjqAJ0PbDiJ2vFF0ixm7EqV
c0twDiHTK6EHKV7AaH7l8mMEjGmKs/yBM0hOginWRq4ggz0pgBKIlvUeHwSOoVyy
rUC4LFulpjZSaYwCIMKhuiQFOlkc1dIyFkMQHGTnYQ5l+KTtcQuFrKpCUQEkpdhP
IAnEZ830XUwpEKnFq3w63EOUoD8olbj2D2D5lrm6lHSeA3DT+t3sW738jBLzUmfP
NrBE0dNT9oyocZHB2aMKVg84DKfFzPpDVgJAWOha3QCCR2gT4F9GFtIwSXJUkeRP
si7elYub2OyuR9HM8otOQVnjRbZA1DWU14UVRC1L4Nvz49a7Us2qxsT7+VfYeFXb
BclHX07WERT5/fyvfceuFSmIY2qgtXZ8GNXKrbqP797zhwmSYsRjWUuN31vjSGAt
3LI4oyypWCyO6SVILlDpBdV5CenjYSgOh0rRTDY5rZ5AbE78eUr980SgnWPEIebS
PgEHsFdhTVdgnhWKE1ifEgQjLihEfOcXijUa06NVY860b5SoC93sTKQpEC5tew7t
vGcbOTebfUcIeavvg7rZ
=7G/5
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '62e59829-4cac-437d-a980-62a678c2f69b',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAgSZVljwiByhZXCxgqcaF1R+Fl9/0bgAJOaBIcJZuv7fZ
SfStNHaYKWXuoUB5FhhZW/gJLytuxmumbIWnw9QyjeGjBKQMSfWQ7Gj0MCvHenrm
PyUm9sIxXysUrLBHvhxPRSDIXAjc4HlxvuzREE7FEOy2ZE146Nx7ONESNbPoyq23
XNVOJ2Sq1AJ1uOOMQd4W2CG3f0IRJ6QQt5MVRLzcpWIIHep+H6/tvCsC9mO29gWX
9N+EBUAoqYSCZrtRejQz1Ff1nLNJfkQPzoqPdFOInX/Qbe1vVWw5r0kAuV16DlxH
grtDXkt7N04srX3wZIs0+PlTroIL7jYCn76cgmHi4dk5H5H6ebufa7QY5t4+PHWE
wn9UaId0nGdH1aTEJEmx7Jaaoc1rfld7qasD30Xfci8bTM9nvQ2EIsAvJw9UfGsq
3Fvpk3uTA3qT+bn6LVpmyz3B7VcrtuLJX2ccotMqVvXShrVLLQLGVo5uC90dRbiw
cGoILQb25Cxbrsvp3sXwLvJWnaK3rfEbypv1/mTfncz8HeQ9uZ8tFWJs8r2q4Ye1
WwmdkIyiN8I4p/f0K/9WjnPbdq9Zj0k+HuaI+/FdXiAr4vnygVOAVuFPk7Kf7lPi
Z0rSjLomvIictDA495kiaTyQMw5Q2ZAnj6qF0BQK7oDCA1av4i/FOsDfZpD7L8PS
QwHygFadzCZRv7vN1ZsoYVTZC14i1YvYZpVcO5BWRGBSwqZ+7q1fAxuTgm8q8u+g
piXkz6jqPRyDrirS710sZ6BNp+8=
=Ryxh
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '633cb285-0bb2-469a-a368-d2954363238f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//fJNM1kUHzpun4M+VbyTcDts78DMqomD1+1KY65vLomPr
1dG+4Fggp0Z8YpCGV1mF/Smtt/gE/N4XqYRxOy1ZZepYfSLAq4uypMRPqwqDYJMW
tJEDAR/6TTVh7f3gIuZW7XFSbiOc0f9cUN3I78HFhf4XK3hyDNC3V34nViAP8HUH
KdVN+f4s19txBOorJFAwW8UXJyCt30J9tm8pXlPimzmJMFqec++KD0oDkEP1MUz6
WPA4LtWUANk2VUzygnVfjfzLscEjBwbxKfqEPK6P/c8XBAYeVowYK4yP3s+TLpFj
EjYGSS0gvI1rJI+REbNE25iYEzD/IczX52sDrnixjgskarPXNR1fXTc/kqgmrB9p
7qaHDOubY0+EnMCWLcZazENC151yBMaoEqrQ4KcQwz9R87WPNeIRplUAoN8AcYwb
Jhl7jrVdAUlRBJ3Msd/REfcaUMYZy777rEopr5cV87K8wYVKvxDr2OxO3TueNJ15
9WOxSIltdEYmTk37bVD43kbthSoX7KwZXzSRaezb1PM3kcO3yQ4NbD9ypwq3jQLG
i6lhqXZn5pXmkSveMIeIIJtlk2CnLpMCi3S1rWPA5KTdNf8ws19NwkJ+AwO61FEO
RYkhft5PjL0VS0+GSFx62M9nH/AcbgOd6WkslYD4aNzGnhh32kdXqdAMvHIe3R/S
QgFIfoTRGELE684mTYdr4weuIleN4bPMiUrnHBOxxpm5ukvOaR1pauhv1n8nv5Pe
kOEYTsbPMEi4nckqWDORS4LCuA==
=D4Eu
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '65c0436e-d3a0-4a58-abba-7fff7301fc47',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/6AkPrmLNn/DoeiWAOnj+010ToeS6MFR778jR8ZsUhFU35
QK7rkGIVSL8hsyHNnJ9vsVzNVdKblmr4hQ+QCegnHYnoh7gwdE/wNGNK6th0eVVg
ESBPLi+rFOGPqh4Toby9bbU6s+CLxY9Qg/fYMzE47ZfwH/WyvlCYcK2NPDSq6Xmf
JK32epgfz6Hkxw97FKBEiW1ZjarqnyxU1+XlSuuAWn+H8GBe2pCGNd2YTz9rP6ii
2WnmtHvRejohqdKUaEPj1ywekPPeiJZRwd+j/uWf/8+WCrE0UEoc0ftv8g2s72BO
/2OB2GDLefWFgLtYkMNmTnFdm2IqWxtz0gIRChXFL5Fp61QIyPIx5J6DuPHxX/Ps
UlE5AoXqb+oCHf8LaYl99TMiHQoUfWs4SFApzDgbSTasOmhvVvhTv9KUmhGk78ZV
IewQl5p8387Tq3/lj9QdnSRSMH6b8Fj5S/anIzjAOFmmGdfABOToCu6uk9fdzXs3
pilyQD2pHnr3i4k+74Vl9YikLbYCg2nariTq7ldQXMHlWqpY1QXJPAO20EAJjzGs
AICyVY2lMk5ichotJAffXmoY8u/O9NRvLnWLJcFcVBLhZhXu5he0wC/5OsHJl0r2
XquBE4MVBonJuz9/JPYGFZIdkX7MVy834RHhV8jMf21TeL6om9Ne9TtYfpeGwrHS
SQGVdoR346ADlvnn5dD16wCyhlpCPgB0rfypOhGYUaJegTrUEFbkogMSZQdoq0bR
7Y859MKYKzyVN1zbz687P6YC7eAeYy9C5+Q=
=khwY
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '671c7213-57cd-4b58-a9a1-fdc3fa325062',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//VfSx82UQGjqwlqnBP3DSZRUQGWPmI4W6fJyl1LctY6gi
VdoA+sZ0F6pEM5ycqg4idwdqWUGRYSmrr0MbKqJ9ojIWLrQkspwf3pOFATd5D9+m
YBVYRKACk02NfXZ+7SuouoWIvuD0KirvhjtW2cbStJ1QP84ls3V0nnMEQv2nQah4
b4tMqzfunfWuk46iLOlq2xVEEZUDVSb9KMXt3VuwJBAtB13Iusoe++w3od+9tbas
QVdtyxWCvH3Ucfz6zUXHjh8SxQYsNBKcMk36oIP5Fkp1JKul4X7meQPNVF7kn3SO
5Ub+ltvOi7ox/qr6HZVzMLEN+sAzasOU9xQjsFR9n3Z7HDhc9UHBf8qpeQad1+I9
wwLg2Cfki1ClwhmAQvOEYGyBOsNLuEftVnGBFJfR9zjh8Srgo+3uS8rkATllDXnY
TvxVKkZF15e2GJliKQA/q+mRCPr5licoBkxZ4eJQPe35IdWQz4BytchB21ZRqOBQ
JSOXMRjR7dgK6bGTTXGt4XKvomKVz4cKwrZ5UZx45sarwvClWMlDkSEnIt2Ld4+i
ByAacXhBneHgVdUzSBwOHhHtdTC/6YsdwGXvdbpFLUi1fPZNsFAz0SEkaM3GM46U
DuHQJZVfSYODHoxng50DjgQTUVS4YNp5P2DeJoP4y21bz1XOwRweN2GslZvF0XLS
UgFVPDtnOyF4/3qZY4lgRpUfW+2nRu93Ki8iQ61C6Rw4iramvyBkjvS8vaJmlVTS
LlEWcdfYsAr7DaFotXYgEnoDCK97uxX1zLPUxppx36ZMtuM=
=joli
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '67808b2b-2d4c-42e6-a074-ea1935bb99b7',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//SiUsey2zWeODZ8NkRkT5TF3j/g4KNZP6ILfXnDcedQN3
iTOiUsBWt/tanTXSZaXtD8hjETp2BdgMDrOWInnIbgbwEUZZiQH+T+rgw7r1C+CI
FnpiKWHb8YkWNdOjM0iI5fxt6JEjX0Gdec4k/zDb7xJHP3PxU7OvZpnIsgs5twCy
iJGAXQPYsRJAPKw2sEMuQEsMNUmMCAlEt85RfiL6xPPifq0vHtc7Mu19PJ9E0RMW
DSQPWeFiL9cen9RT9hVLtKXyhgP6WG4/mOILvqXbaBSIREFg6xZIw0yQJ1q/rVag
ncmHlgZS/O0q0qr3zpwqsuTC6e7QT/OWiTFrj+a7DA1vpOPtub8Y0rj+ZIjfCego
HFUXybK0Lv+0PIimBKQELi+UIRw49NHVL4vrR3UzEiOfLTHLGl8yYxOo4WkyDNra
St3MiMSzzfXUk1p4wGF/q0ah0qIEQrLFa6tAo5jiC0FzKGJ7Um+fXcSJjrd22EgW
eGDmUcbKuG9ScLfqjiYTZk7ahRmh7KUMz+e7TJEEn8MI/dsI7vkWSWTP55oqS01/
wFKvuDX8u1Jlz7Ur4yg+49dzCPkYDd2ydGux0PU/nlDAnMMuHpXOabhhb12Wy8oN
dPk5rip00ozqY9msurwX/mI1uHgOkAlmfCHPasDkBwohlG3tmgtWavMe75m8luPS
QQG+VkcSQWiRpLSUXWOfEj9zDdPylW6aKWEQiQw6NX8L7KtcxlkoynPFS2picUJ3
i1u3mALp8LZk3K5IDaEcz7lY
=Jbj9
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '697c499c-2923-45bc-a4bf-b24a20671a64',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAoh6/NoYIDr/bNzutQn1aMhyIAsvnqDBIGheafHlK1ttG
ieExEwffH6MHUhr7fAA4tDKittnsAzaURYXdIkMstTdZ0m5nUFtkamt35zZD6Tac
i/cFzcQsUqVIJq1I2nmoXHSs4xxX/h1Xuau9HQ4H0LyjUJWXKZ5xhfyusi8MV2qf
QsXHt4guVNde2+cehRhNY+0GRtVNw2D5WyTppKHJCOLG6l2ZcjZmRWCTX8lVh0Ce
tiyiI5DmWITUJ/FlZqQKHBOLwsRnQiaXGftzRq4/6GZ/4KIox9JiwRWFWQ7R3rkh
n/w270XuxwtHhOb22GgCgBqFaxTtTxhQejLy4BFMBMeML6WnVKf4osVux/og2UQz
iIcToxTn02uiwNny0oidFP+SsvDrz6jKNtcRc0109uV1XPHX3e8KbN7kuaD6cf5W
jBtFdyjBXKmIyv+nP8ppsqDDH0HezD0oloaznZBlkwWAyNWtdPlZnM3opwQhRli1
JUqfcy0YjVpQhoPt7C/NKo+WU48Q/K/xAQ8yMQciCt9p3d/fPx1AAjlCVQ2WqKFh
HAcx9z9FXQwemauDnuqZFG1AYKtoP8cnkEvyu58qt2fCQX6yH2HsdW+hiuolX2Y+
FmloqfoBLJixAHoNafdvjuzKYCwDR474GEei1rmvvPI5bEBcntR0k2sfGN7ZsXnS
QwGDYFlWvwBX+dptOCGnyrUbuhHS3qdDLhcICamrvrqI/6HDkzogVc2inRpI9RwA
tF5RDhbaERKm3sCs9PLU+KjcWVQ=
=qVLT
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b35b42b-94a2-43cd-aca5-bdee7e889b1a',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAuciETRdppmrZiN7T3ibqMO1GT/NxA7eAEmvWcvYug200
JxC0lled+sOUdaJ1SfYxTpkO6/o8rzYkI0ZBnnjlu0/9lxL1rucfHaGG0bH+DqR8
reScXoA4oqA3+TICOAfg9HRwrf6W5pniHkZ2CfbWh9SvPPdY3HWez+HdiN8CcIXO
fLUTHutDNEA+Sk5frwDuUPIX8YUMIP2rJbjHWw6ZWaiDmXSv+72AHQvH/jUnNgrq
KafuWVimV5xgfCLisb3a3avyVoisH1iIBDeLDiO8MWluGJAobGPN17lhl0pExZ7T
48ES4SZNgYsAdKW3kQBzcEWIzJ4KgkEcrAOj4WIvhN8BhtL/D3FwyW9FOfqoxkCQ
QK03PyaR5GJXiRlWszSV1mnvo6QS7WEq6uuqaeoaX/2EREXVNEyA6DBG+hu2Ezxr
ZMNLS09VSoNCdJ2Go1+2lECWy4N4dT5GpSUKUMsAoe2AM+azNf+UlNnK0aN++Ul9
wzUZWfVKAeniFc0PCFWwkXxXLaY1DdrkCou9mjubKs7l2L0pOwVjVQLS48JNpwkC
yBan8lyfMDhPLe8UodoQMS3hrfwbwz9s3myN/QuZibjIC8VmxTXmetkSiK2GzIab
XMM9+cgzUnp3Ty7bV4jXiIGZBSeKHnAClVwwEY4F5IsuU7WUY0OHHcgAPjrpMqPS
QwFRts7Ol7XiNfsfQWBc2XHblyLz84AFIEZc5u+OitNMbezEHWutANET9HdeKa0N
e55KRRVWHi3edmgIjwVPQkBEjk4=
=iOvv
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6c705424-72d4-4648-a9e0-59ed3abab05a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAs14QWuTkyi9hlpM3cGjnRPrfVYDCoWfU40hcmiFlqETI
RdAWEJKPQoIMMhoFxpNfMdU/Ib1PwKsr+ojPlwnFekXu89xahsoPzYunXvIZCzcp
0lkJ9naURkRetmZkTA+f7N5I9WCbQPntsBkKRlyUpw2Pgses/nE1tJ4vsU3TgkEN
P1JzqP1hcoBBPbsD7sz3bzZErUmHdZdhvF+ra/s1FKRVPv2FQKPIdhxKidivAGLp
wWsaCEXin/wzzOpphZi5tUziu1H3vj153ORIhguCzykcxBUKtv8D9BOgxvTIGrva
WQ9b7qzhsY9Ih/iHkXeI0uAnXNgusWF0agJWrDwu7yvzmfpEBGUKzav6CIJo7zlK
CeqhJjKPTe9j2s6VIu6H7YmrsqKrnwtSMbjwEKTRTpC3gxbPEvyGjLB6RPSmE0xm
4Hlas+ZWmFAKiNXKpoXqK7nqFCVT8dtrkhRYOjdmkF/O2HQlSwSas+luVSr8pyK0
Ca6bQ+EA9lJP/WsWE4YlrY9xAEBPglffU8CeKcf0Niiye4YNHMu3AquwXDs78jeH
Fj1Fe+gAjQhx++Pt3Ymu16/6b7GhVzzKdAa4zCM+UhN4WRgU1HPl5eA27i98hYm/
zIqzNFsXRCeKOwynCrWhqvLRip3f4VsM8gyJHBrNkNM8fp4Eq/Vw4vYOzx4LfMHS
QwEyd2vMaYWrO5J2plyTvuo+/0oQugV05d+TvA4vpUb9MQ58/rdjNvcgp0aRFIah
yPyydYeEokuOApBvRpqkXb87GjQ=
=LKM9
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6cc9fc47-14da-4d47-a787-f72ffa969156',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9H3Zy1mL9EM/GdTSm7gfcsaOaML6gpDcgstELc+xTEeDf
a4c5xF6PFDrX9KCMtiFn4qiXbD4JA1vUuwGK3jdA2502+x/ko7jpNmRaoe+7Rrsa
pIGMMshi8gcZsReFEmYZVW/k6GumSQ7H9ALhv3+Jpjws/aAwk5fsCKE/wijpmtvp
UhH38UK7mMdqWIJ5AgvTuqhEtLpHWNClDP4LuyeLWliwC91qnuv0Kt9aUKg1RDHt
I62EoohCWYIii1BNQJ0c2wNJM39zgkbUXX0SDECxbWO5zp+zxxVI/UvgRfMl/vR/
GkZ3VXygAtrfCUaD4x5o9E3j/s9u/cliUY+5CbjD78Nrf3eLlkUUjGw6Tr+6bnVi
kwPHzIG6aP5JDeCVVs0UJsdNee/cO6R9BPO4j2i1LeqVDr290X9Yj9x7lunmJN9W
kcvyq3/13nsjzqbq5ANpR132cjD6GOthOcxBSENKt/QWcSHI2uol3R++b2Lcpy+Q
3Guh+r24gEj+vzoAVYzNtOjwh4j10pmAKhrHEofJ6ShEocc/2sGFe08qAQwcZjzT
xHk4Wou8c8J1g7C2lNU7aHV3kU//p4LhO4pR8r+z58NCcEmPaNIyDsryBvvPbbut
mYvoaMrgrAnIXEIsWK5b1MEM561anumk1ywaWDunxzejKnRlENSEfQoxnmNZbGTS
QAEh5brUJ73XZyeGLSqsDjtXDuyMVEIGQR1EOSNzvmzrjd0a4RDeSxcYG7zcXrOq
SRzR0r62FgNxneqcKwu9H0k=
=p6Z4
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6ccf0561-9b33-4320-a0fe-2078f14b4b26',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAhlnDOcBRiguars0nuQ/doNcs/XGyibLXqJmJvqu1dURW
4FAIrTDx7cOpcfTTvFmiSB2j+2m37CP3spFSsnfJqdTEjTvSgx9LdlpE6eNVad8j
bitgSoB3lM4k/1IVWK3M1uwjfoYufN60Hh6NEJTnK4K2xUfNTA5awH/NQIAE46Ev
uEWZ9qWCa4+z/laZeqfQFFxJYQZE/1IXd/KI1JOEeaneyUFDn1EtpFCsHiHiBZj6
k6gDTS2pVRQU7CBY5xlU8fxT8wH/74kG7J5rMU9MdQiybXQVAk/5wU3UrZlwHK5L
6Hl4c7gCibTI1WTmnuyuAPMKxzXLu8l0wd9Sr4L+A5FW+5ChkQqFMstN1p+djwFs
A+UQ9Rdc5ps+KCzI1bJLIdeSv1GiZNLnhrKasI1IjsTc8KV/wWLDqtW0Sk71rBqe
u2QnPYZWFDcdLvzKZCfjNnSU6gC6HpFtsCtgKs2+SfCwN9VrC3DPSc7VjnQ8E/sy
SIN8VJDTFJtO5TRhYmycd/x6j5H+up4kq8RMto7BWsGLa3zRQb4kXFoWWyhnQ6/2
NTVU6x8HVzHIYubGfBF57afklNdCEOH7qSbFek22dJN4iiOGZZcCkBa0nrtMUXyb
huaI1Vc33lAFcG1ZMKnpPd6cX+Z38Sfz8megEbBsOKfu1gHyZo5hDJrWvvXGDg/S
PwERL+0rpxPFN8+jUlHws09xh54RKNHOORy3riE/xgEDewwOxJJreaANny1oC41l
hHgsP9k+BUTQjtLFAJL1/w==
=vdTR
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6ef9f103-bcd2-4232-acd9-5acab170760b',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAxc7MPwryUzIZYBWVJ5SAaUCKnLwc1GH5TNf1zdVjjn/d
HBgdbSu18Ai+e7+tfM+dRna3RbslvznbbEPa99SGR0naRkHzl0WCYiC+OufVDHoK
MPzWr3q+rfp39m7hHM0OHNMifSdpv+dB4yUPXF5CM3FzvrNk6aF2FQYiQLGpB/Y8
Z4SEZAJBxykULg/d5e/DM4+hnbasVvFI+Or1666TkU6NTev4EZlsc1+O8QNZuDZG
fVv7TzhoXDEXMrDuMI86EPQ1M2KypIJgTrZZD8WQr0kaTZdq/4T/1rLfmiG20mBk
UWUFn6hqCWn8rQkoTnubOX+0i6A4ac4qyyhfj8nWCmrEtbgVA6vi1s8Cv+dOahYi
vY2SX4n3K0p7h2CsHpsa8JtqIKIDXt4MRSwnB3hQvKlx0y9rA9M0w89RXjtu0u/Q
TMrH1TK4fHed/xYl8lVSBPIVamzUZskYfobBcP1EBxIVAbi6e/PkLIYecWmeXrZ3
eTv0XQ70IPffOdA4MnviLFIe9wWK4B342QOWo/Kd+Cllv/KaYBYkW1BeQJ9DwT/j
TxJ7kreOOOGO3DK6jymX9b/ngxAUi5KJ0VznYfgUJZDPeAlV9gVJXOq+WMZxc096
3HagHBbz6aqMj/LxlSa8ZsLUMLyV9+XUkGs0AWy4G8ACKLB4u0E8Z9D5Pypu+GfS
QAEWHLyMPnPsX1XEpR8XYBWuNMCPOqWrH2AeajOeoXWm975zgP1wKErJQ5yL4ZjE
qQcMthcHUN6KPU9XyMzMwLo=
=Bp71
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6f110263-cb7e-4000-ad73-30ea8667375e',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAmCt2Z4UXfrze0sJiHzsS0nbckN54YIeoRudZEX5HWzZ8
oM/MH5slWXbpMds0vncXzZFSAc0JAtSekMsHSjJX/nPXE7X61tuVy6Y0rpVg7BZ9
9XprlT8MIzV1j/nh3c/5R4bx2J1KGKGVoWZloX4WAwcQjAes4HK8DO/KOscnlsVe
JaSTOmkvudT4xDOk8hiEKD8+DiZ452fPLhsJTANGpfOo6IWqEitfcJLIjEBLSG1e
yKLyEDBEZSn7/ZxngUhWvkbAHGcxqM5twM3+sTn8GK471QWnfo2Se52JMB5sI3RV
gUP+7ot9kliNIrWj2vZlC0qmglCSyaJBe92dH+mZsB2IEy1ahliFN5JL5drbD3+Z
BIF+BruSNSj2MO6CoVCVKzX2ty8YBMhKySRKBIdr/pjh0xqnt4bFbXBeMp3tQ2Un
5ycCKFArTgn5Yfp/q9Azih6Hkg8yWIu4Rf0AvTLO3KZ+sVgbSyLlrpD8j+ZTHR4F
t0RsIK4axrsYZtykllOITxCKS9vxbiWWe9PfBYLwIjq8K8oiwKzHGIPBPUJSne5w
TZIR07z+bAXKHsSW6+jMOU68nI7N7XdL0L690kDS0NvMP8Oo8fVUGNyyyGptXRZ6
qpRYIyEAxQljbNl4MR/uWZTFvatpyaO7EJ4NkObi4TpIaGapbOjgFqmWjKljPO7S
TQGbDW0TPEPbQ7NqXZgS+48PC0pZHhnQCiuDUKTAIwy5SbsDOs+aUOGpHW67iB0G
xFHiLFo9m2zyghmMnGJuTGYXFzMV0LiECKUC1ePk
=dMOz
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:52',
			'modified' => '2017-03-04 16:10:52',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '709a1199-0f05-4706-a8d3-d7c29eabdbfe',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAnz7IUFmrEtvpQ1Z3ugg+Ybz8gnsza/7LJZirApqUFPPK
kHxe/eENDFJjtxEjDDDVL2HJ0BTEfX4X5SAUXulu5iZLFlgoGsgsm5sRe2cFCekQ
pD5luIvWaPkCM59kcZ7xRgQ2kmXOlMej1/QloO9LrFtFfO2uhd2VDmMTc8XcvklK
X5g8aq5nNwaOVdPOGHw3yysorNPbL3++iVJ4koS8sADx9mewa5mhPydS/2TxuQA4
WDr+Dj259yjw0OYjcJo2LGInjaj8I+uC4YH7WXEtzMN8vV2NSo6dLrTKSNjO3SlT
mkOFITWCZgNhYJBfAb87v4x7X8lfnI5DYWracEMcPSqkYUAIRtqch4+rBOLZ7LHT
q1Fhjab5yvquTqx9V0KFc6gkFWYEcP0ner+/cpTQrvnQH3qTYYEvVJg4rnE5Foae
cB9E3Tm7dJcyhYDPXSt8BjRpC8qb28izFG/JS3OOFW+b0ScfsI4uAcqxdA8RE2eR
HvjPeo7+pGLQO1htLcLwL9AbsGFiEuTXGsPWiAjA9Fqt5/rFoNC0cn1AXBdZu35L
olFS2+8HTRMRpkR6LvCYLwfR0l9yo0HNetecBV3Ej3/yxeWILW5OtzhePvqVIRow
aXB7BdrgkB+YgHzwg2Z/olwtrlVw61odeDvHawHN8LRr46sEtAOGipdbXveXLPjS
PgE4F37awBCxNl3YPqTKG6+bq97BSIxa9FY+Dp+5raWCwiiIoZLEPn8X9z08fSEn
GAD3chVWxz/Nmoh18imt
=9tgP
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '710f1188-f13b-4307-a4de-aab6d5d83b71',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+Owhjmgiyxsj0r5JDk4q1vD+cCtfphuInKRdUk+UzaM9k
oczOBu/lcXj1sRyqQUod+CZul3Hv6tyIUflY6kIXnMjNLpeAXfbBV2mQmUVuBVND
LBIND9ghs23d2sCZxovPaOPto+ePf5i79iOF+/Y7c+WugImsViO46uh6F0XSb1ST
1mini4Y5563SnTOCtVRNuAIp1+EZGCSBYeIO5A4S+0MRJOWLubUkSFB438O0gO7X
gas9dPLPxbaZok7ETEI+ug+oJENJ776iSolNw1NLXTxR9zY1h2F9aoYe863z3lgM
rEiSrGiqAksby1bxL2bH/T3K86U39rQPnzBvawi5CxLDw7Gbc1GetcmEXVK+eLjc
snywrV7MZPjIOAtceWJl2JaPUGFmIx1SngqWoxNPtUFqvz8dI7iCqs88iwXEgd8A
D+SybtcL7bxIN0zenCTlcA+1wuRa6yH0biZI2H+P6jd3Go/fSZ9ek/A0xyEdxqah
9/fpDZkoOwf9GMRi+KLD/n0AoAmAHXlRV3/kS3y60Mn8OPLuh6Tb4PvjHshrHqEX
aan0WUJy0wLTdBYV3StV5iSds9o+di+MEHL5UXzK4/HlUhWis/MdpzcY9f7/BTBJ
PWpu4rYMA7nhfHbINIuBthC9X1E4HEi1+qZR/ILBndBE4maRso49R5PMD7Y4d0jS
QQHcPs/fa3avMvAYjgt4CAxUjEGD5YSyrCCkFqZAnXYH+eajDpRcLDAyL0nc99ws
QfQk52XQzNxiVdmM99FVjS/3
=lzch
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7141ec0b-eb51-4777-ab69-25e12e03d762',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA0WguBf841Hy+90AU2K5WUXkT/duN6EprAjqzTDNIcpEf
Nb9FKuA2rdDs/L0IGI3akCc0X0Vt3w/5oajpE4taATqWrwIT01H0m2RYuQl7R2pM
vZz//tizNigtbvZCnM7PjDJEg2wICLbVlU5yzAMg3BlCZF13MIEYI0bcP5MtCNgi
uTYBCYAPENEv3BsXeVwUJ3V7yp656WRQS8fq8fBc8fEBdtDwhvjKpNDw8ka1s26k
UhA9FkfCjtutVcaoAUubom6+qaI+F4a7PgC1uIwhAV/gNB3I5LVjaRBJB5DJV4hA
pxTObg20Gl8EmxiOlBGY+JVt2+2NPyiMKIFYQ+XCxzpNZ7WFbN8cyWjTNhYd3a1p
D9n8tU0t5LKva3NsSHD0dQ8xFcZahYbY0+uM9v9mlWSornhSvU38fbilkIyHU/Bi
GM51q5PQR/cUK5xdD68zQmWI2nnLG1xHutPWHbYVtJGWMFcgCdqVMqO3q7CRACa2
yAtS07NBz6vZAgB9gj8RhjRDfiHCa/Q/th6oS+Gwyl+sSDHmQX1Y+eDq7GLv7Lq3
Nn+wdeCG1xHGuQIKrGktU+w+Z+Y6qz2d3yBybDfWNIVnFENzcl2wD6WY4wu8PimY
MOw4vEog93TAQweL0UluNSEcdKHU19Gxin1++cCcZoytcJBBftDG8kVfEREscm3S
QAHmCO83bG6bALsT6k9VlJZFyfU6lqHjvQVivVyhhZ8iqw2K+ErvvnqgNiyRlWiN
S+qe+chZXZw2Jq4rpwUYV+w=
=lg+3
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '717668cd-e920-42d7-ac4b-fe026f821c49',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAk93mrDuD8LHTymNggxYoyny5oQNKw8RNH1a+ybbmGX16
N9l98jy3IwC/WmMVNyVVIIUR3+n5NZ2y1d4cecR084x1viMtwSa2GwxR4dmjYVYq
WhqVVePWkuXG9U0KjI2mLLYGuTkMKoiRr7/wQ64cDY9J7nUAexWZ0ha2bCVSjlFx
30U4eApu33PUcCf/1/6TW5cGwnqOsKt/Tw4ZKWWeP2PVLQ0rHMNhiAzfSZQTSIDT
vBioKrcLPl/9kq0xJaeW4EvY5lMZkttucWb18r6Up7e2pzTq5GKtkbknEEBlTru9
YSHu9DKBfxxAGlEZAkcyKX6i/ewdsxcgp0tFzjZU8znBhGxmhLhrfxWHomMdtewc
DxelK3zsgB2iRRM/+XFhZVqWAghjR1f/OS3TL5YIhNzSeiw1Iftur81SHf0pZf1m
t6Ei35NgQP86KKaY1IGayI42sFdaUm0QvdaD6hS5z+jZ3MwLtcpeYKlZ8fdKGb/V
/Wl35Ii+dYT9WZs2IIC60Ct9nqLLOxabZCkDb2CWpUlmnX6bwIqVF2Yn/g4Tjuu6
uLf4wXc0RxuohAiXcE9bvLJTQNV2h9+06hrgc1ryg6ajgSF+o2VMjUKFn5QjIfk+
b79ytqbL/RLp1f5j4FGSKRUtOlNihc5dhfBY5fno0KIZq2ikHCZcuuhvdDt0XGPS
QAH4l1Vpb/4R3yJDwbOqXgUmu9e4/UjEvRoFKtVc1E4hqkciRci4fLVouJrNxdQc
XGIc6m/Nr4zxrxSmUwY82kI=
=70d+
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '749a1cd3-c37b-4311-a26a-2e90507f96c4',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//QcICJtxi6QtHvlRVPgb/Z4kct5SoxC8+AB+0y640hOja
27wILF6pMFlFBsTZXcnJLdbROYDyYp/GdolN1L6o7vvGMBPCIqP88YVHGjVqBHXP
0OSIFYUyWhsUIWnG/T5+eRwPbcjQJSzwCZVcHJ45PwWdUZIrQEEZD4lf53qAF497
oITVSR/6jfU+7mKjexI5KR7lNflf1b9hItBBDFiXGsiE4XwDWib+tl6M3dQoHbNB
6v/oq3lrvqJM0xEZaQX0jdwz0SgzBDfihX0KNsZXWoqBWsR8sF2zLFaPE9IRBCi3
Cg14Sg6z7WN8aiq06Bd/c0Bf85OC+6NBpYAOEM6hYSql3lhcfLq5f5IgkzXeULEP
spjlRvHTuH1MKdJIHO6dH1+u4YmpUu+/tS7bg5jhwg2lDx0J3qAH7H2QyscMkoBh
32cYHYDLRDOlUSdYB3vJoIyAIJj31nUTVEPPjj3qPS/mSgpidUN35fnIuNwIXyCs
VDJirruiPJ/0KLlLL+5Bnc4kJ9d2/XSxgmzJXK9hxHdQrceiaGFbxgIcFhBMJgbg
4OSJFFEezP7p0s4hntWB5uIdwJ9tU2px2XvTvFt5+Hm6xSdymK6yV90fwJtn/qIS
UYJAuKe4HKWGfvsNtYocj0NpBVFIfssghS6Z/pD8Q80HWTpZn/rUFLQy+PWFpIHS
QQHn/bEHxD7xHbkd6QFIkZQXiByv3NmI3tNPTZ9XTkc4mv5p1t9086TQjKhqN9my
VzmNC1UciWMrk4OYA4b5YUbh
=vi4W
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '76fbaa0f-07f4-4c63-ad19-0e59b2e5ef7d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAjxQw99UuXYEOaz3970qk6JfO/1A/jUZtcc7uD0sSdR6y
dz8dLDJ/xVR/FK1RhcYNYDNUiOErC/huJu0eP6KMylSzwv3H1wAHTryOCPJ5QgBH
A985Sj15LKfHWtAAH00aTj71aDp5va40qwm6up/0n0e997pMzv+XlbCF5TNvubrA
NO4NZHQIUoeSewwpxzZODtPLG31Nf5VyneVd/9+kjYYMCH0uNXJjJEc/WuxSrh8C
Iyq6IJ5L3y7Z2pzg+EdZ3JH5jIe7PDJEVKLPPz2hroSxOukh6dmGIciK298tWAdp
L20tjfQNOsVmDmN/5jBiVmZWtWftuhht/VbQfgbEZeTMRgOkL8nSk1d0ZP+YM2TG
vHSCiX6WCqexeL8MIj1qcO7lJlquKRXT/ctG6huKFLeDr0Iin5NM2hnQYXq5R6Z5
7eE/S/Bgg1pZ70hDcGUP2uzTGMDIH2YPNdKvhPADmto8+DUw4uYApYtjS4lZEEze
elz27/xIwHbWcRSTL10fwIJ9SCZaDoUKUlbYbY/4UQENaLUyzwfcbOWXEZLvxGGZ
rybkOh9gQDWvKrwM5gi/ZKqaIt+SqGQQtSNXj7Gof2cxTk6Rrc6r2yh/t5fu1BwN
lmQBGlI4xnosgKTRkZoUVXWvH+JibRDT+vW4Oim+JU/3JqKQkZEtPnZp9RFHs53S
QwGgg3Mrq3ibb3G3pAf4riGFUIFXLd7d1ILO+BK3WQn55/cNc/0ZYU4VehY5OBDe
yzLsfPlw9rXkNOvTwXkxUp9iFBk=
=8CVG
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7a8bb276-c6bd-4578-ab42-841af6152869',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAjrY7/z9stTGXZixHkvBzFycLrP9NpotS+7geL7Tm9vSG
WHhpYpXqGzoZMdbiI34ixAcPGrbqL1qOSuTcp8f+R6hWZr2s2VqnQNj3y8w52JV+
zTjH7Sw3ZeRJD/co173K0W88j41vtnvsiDXU9JKochbz1ln0RA0zTdegeHaMQyrF
lggIdi0XWW1/yRXjolNYqoqNfFYXjh+dRB1MBqatARapf1ZuiXdIxiVhkiuyIgxT
zU7sF7kgJmrup8d3asrAOUp7YhalJK78A8kmYzuANflmFQh1JfsJNag0OMNkLztG
i3SF9d8xVA6HV930qeaxOprqLAKAWE4LAHGHkKtT0dJBAb54Z4+6BbpswDjn/K+V
gdK4rNe5ednSlQIkieEdm1/D6nV2iHDrA6bk+Eem0UJ5nOY+hT3eOhY5+uPDZAR5
U7Q=
=gUYS
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7ee01b86-7ef3-427a-ae6c-93e8b2257c27',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//QFMLqDqdcaUDvejk5McMKQ4Dg0gJKnuHA6g4cfSS19fz
qZN8jumfTqUuZtuBjZUP4/d+rvdY4jeLrguyLN3977jsrqntioJlBr31vP1m30ZE
8JQawDLVXTEmoLTEFwfuAnwbvMjPko8WKw77syfb+lFxI1MA+y8tZNm3I2P95c2M
ePZF+Mpwokfb2fk/ic1BXmZjjkE5cWhL+kKRx3+VtOAtd2S8+9+agUhGOo8qaTR+
sPwBhiZ7QNOg1G6KIMp7da9yLux7QHBN29nvSyfgxCx0s6N4Gx2qa4fKytYxO0gg
1m4C1GX+iTESTNYdpoYVIw7t5+eDe7Hd2hGn8tstMIyLEokC6RBuKLd65u7vAKb6
cUO3FFXJPBY3bqHTt+rwrVSjrdz/CPEzBdgMThOmKQyvmBSM/QiX4RgnqVfy2mZE
dE6Z2lFuOCYRF6k/76/doJjLCh9rsbbnYZ34p+bRIuAS/m7fVQjNKLemFOjPLtTe
G4AroDouc/gmZDzM0ovCt2mN8Ug88BvosBOTp5iQcbIa0U8/NXFNQpDYhiE5vKB0
c0hlBCrx6zDkbs8DkmmOHXToTBO68uFKUzU2z3WqI1rIeqyKfxQNlaM7ZcNCwYNt
+w4IXRbFQXBUGDlXuFnidvI+Fil1JfZ35XQnGiFsPs5CjcvGFcFaOyG8ZXBFGzXS
QgGKALwaEO9IL9OhJ+ORtZYML2F3dQuGANSvUO3630IGfeIK4j3AR1J5RY45zRnR
T8DlLQ07AX7HVm+j6f17S7bjzA==
=b9Fx
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '818e4559-f594-4087-a646-11b6b708009c',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAg/W2ggXbT+QfmE9SoJqqgS7V+0dzzKkLADdiL+cZOI94
z83IEb9UBMUqHfOvvZeX/0dvKgR0kik1OMmuADfYpTGigKALv7TZm3z4MG2GhwZS
YdFGaAMJGdWZTEUZRapJOKXLzbn8hFWy1e/D7wGi4SnBwwCV/yj0rFzmRjNUkVWb
ESDk8pRjbSEFEPGZV1ouxI5Vv4fL1MMFTHCpEC45t9rk/5YW72a691ALndV46dPA
tKAxX4A4eDid+Mnp8cw7TTy6VtyUhX8hzLAeWdsamUaLbg3bsoHqNTbVSmRO0Lhs
QTUUSSw/uky/z8EJFmGn0ma9qCS9+oyNfYgr/ES40NJBAfxP0VmKWmbUv7maPVhA
03myub/7TldiG/hEmq2qqmJhiRkbx1AEDsA9CPgaG9PjbzdnfeW4L1nmYTI5XZE4
Qz4=
=Haue
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '819f4f36-b5d7-46a6-a649-877ce4c743de',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+MFT2AWCcrWxRA7Q1Y/FWyrVZIGBX8jJFpTQwsSpIFKog
otF+Ad+JyZZERrnFpyNKWaz0gXBJHiBS+hHRUtsScezYpq/DI9K/sqwaJe1ujoEf
RijrAhOOix4Hd0p2AvyM3VWpu1WNd36WrPu77ZuSOxigpzaa0Z78gKYGFstqZiJX
FMcz0n9tuXRLyv9X7v+fmVh8Xa3s+r5NUsVOVUaZDwUYboTtGb3P/5ucLtlO6nz9
I8IUwklQFvgDY8yOFDI1/g8QetOMM68MftF09Z7rJJsBJkDotKVPXHkMYTSfw0t5
tekHopLn7lKL43JpZc7yCiqThCbFWUDw+RZtYZV84NJDAatXzNfZhlrJmb+rdPMc
BCTUfCIgmZqVJC3euyqd5Wa4dcQbKvJzN88u3vsK0XT/ZkVC4Ng/ZmMLBpumrs+5
IRNk0Q==
=CK5R
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8219a080-85c3-4322-aa6b-e14681ec7bc2',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/beOO12R5cQjs6ddc4zzkJ9elJgRCc/39d7xq9gLkeY3B
Q7yVb+e0Y/p+ZzgTumsP0ddz+zyU3fJJFyk8fdGACxdo3mucIsKQO/IA4xM5MmVl
5/0Y5dPZnd8cmMObnbE3k9L9ucoEmwbN6fpgpetje3iZvdk8LmwBMtii+HhIJjEO
SrQDgkzSZM3c6UVLu6736f6NgyUVxkaron2opMgYTbIGooYF8uS+fFTOM1S34Oye
ehDHKEa4dCalYRSr7MSgXL9S85/sQMGA2/dqlSDU6ttP8xhgrB0i5XYNg/8j0ac6
NshmyPowYhcTrdG6LohSMGnyAlz0qzOL802CHLDXJNJDAfwMMKv4QbCCf81de6DX
W8plFzVXdRoTVibcsX0tqIOWbl6D5D+8fmnSQ4IyX+pUXRgZCpw3cWP802AWK8vD
eE61CQ==
=II8Q
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '823d69ca-3618-4b2a-a3ca-b2bb034e7a27',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf9ExSjvO81rFOD83DG6R65umvN1WC0xbCE8gjC0iu0N7t0
+/Ny97styU7/VHClxxl/WX7bu7Y5PkLTSCUg2jmLu0bXXAcrIIcZE9VY3HQGjghs
68h0y5o84VTPriePE4zj2NwnmKrAThw1XhXV7Smk8kb0rEnbkiQ3znDeYCGxdf2b
fBT8iFN3Y5R453x9heXsqn5kBvXginDA5Cq/a/GIOxQ6m2vP9DyZV514y7rbNRhy
4oLA/FM7GJJzujhydLYfLMyMwWrRS8Mdq3O4xTX7/5XSPHyw8p7PtaA5BWmNmwN1
JwdvtSVjGhCpwZP+jqs7qM+8ckBbfBqXBZbq1zGkaNJBAdzzi6riKTmymD6fZS0P
F8CC7bZyNfeKL9lCrbiH9/3eDzlUpfjSIaMF21bhOrMsJDtnzpyTtYhWvvC3DXt0
Wn4=
=tyf2
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '82940f30-f555-4e7f-a65b-0e75ba79ac96',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+Lp0jYNU0/2V6sxh8XNoCRYMMffLrSWumWxkPuD/y2flH
ps92FQFOaAygga3XdvVDutfaG75tAli7ChTZ+qx9J4b6CMg8KgizCYhrqUVe6gLC
4uyy8B6QXRAGZDkfYudFbry5UwLmF7IBM4c1K6to9hGT2NgPss47zRMs4pkn+G/h
LLk13Yrc8hnKV6oE6EXQTEegg9fXIoAJbhRDx5+8u60XGLhgspIooyVhqqLIf8fg
GjWRWZ5m8iA6R7jszMNXlTXY02HlUKEsw1ns8vvUmFbp9iuFWYdwJZetzRuJROCp
yYSyPE/2WXQObYmICOTgxNP3R+7nTCPo47gds/QI3t8qrroptBR2iZNjJoJE1nBw
7TG/0pQMtCOyrMstg1iX6mRTvBzwTEWGiApahiDQfyh6ihGzTY0TJx/ZTFBewtID
B33oS6fIDVRNI4ICB/5fHXEFeZZhITIxj7uvY12wc8HzPIrPRauhmGzAg3ZCstDH
EAUsKTMV56kQHMC/py2mYWVUU+taQ+M6x2zO1sfrcqwyeuFzFlvhpKN6CIL8Cbj0
uuakLUBfnik+qL6x/hEwVTT+3A0MsUl7eNXzRkad1fiCDJ1OWFkp8Dp1Yu4V2/C9
h1uNYgP6UldlBjy25+yZotvYTc1KZUdFF62OEQYgUbYUzBDhFTQ8918EAhq2HlPS
QQHAMJqG30Ly/NfNsJ3AiQGm0Cna1EocChm5dUeDhU8nSchS0vjQKp02i1xCTCHx
KcsfzfTs0OOT0QTt4lZ45QMr
=nRuK
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8372a1e4-b49c-4f8d-ab2f-032c4545e60d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/7B50U5WTiCZjO5NLyz84WWRQpEO334dY3F7NfwsMmy/Ce
NnUifNMKF5T3ahhT5VwCyqcL6jYoQMxSvf0Dte1bldUvbDPvg/2new3Pj9UU7iOn
b4Bzu4ZMqjmX5eKX9KUsgMSHN5hiCpEHmWLyoM6NRxDPXMsSgpkCFQAhLPu+RF/E
s+qgWd/obv48YsZcL31ZG83G5RyBXu/YtZiLj6IgdQfVzZpV197/wCBIQySyGUA9
WV0Jgb/tVpGtBhSphK0Xmg44DGorTiXDgdQ8jg3Xu/D1lcdjmKGK4DRI9KTyggTa
S6IjsDV4m8E/4OCr3fF6khVoAy3LWqeVLARGg5+pSrR+B0JMLjXid4nWIT8Ke35P
O6iuMWBJ8XbopX8bXtHScumiZSQgGfGI7//wiyhjcfiHlfL3fHyndR71ef/qrCmV
pH+TwFkcZRe4fHx2YRy3HzFeSWRXImgr7wgMV1QkG8zI3xo/4JKm1GhQkUOvAecG
aCJWlVQo1/AufH1pmwUOpU+QMc5vaEaQh9pEgtdmiyLpkru9EnJpDmiWtZuFC7zQ
bp5gPfFTL2D4cpPtrJwj9LdUg/00YITxpXRVtO4KFKVowZiJL46mTU5GRsUJmGMJ
EmTwIpRYbj8utsGYPObgYPytjT8aXSxXMzv8vllWP4xWC+RbQOwIPMiA8jZ7ck7S
PgFPl1UGYB9sG46VjOMZUDmnq3W2SQyXEZsK+d2Bn321/iFy5GJxIrLAI+gyJGw2
/sb6JIrDHTBnhlRznfyT
=xi4C
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '83dafadb-6a29-46c2-ae73-d8efe59697ae',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAkHycg2Tw7S6rPLIfajVkxSi5pgVO4MsfFSulPTdJ8u4G
UQ5hdVsqRUaqEq84HOMvxquh4a2gn3ol5OSBthVqXU7300fvj5LHXrgAvEWmTZ9q
YkZF+AVh1d83dmnFvjxrLjBTUE2EhI7Bb8cwBI4RCr55qBiRfOK1c1/Bi0rDGvG0
qZGPURvDi03BVp2QMcoDg2hlruuvnAmWQ1ycARmpmZc7mm8b6WYAhFR6AKFc7AX/
uVyqopwrR0tFK8jP6I8WeiSFf2QSadiJrDiEF0h8nwsNf5RarTxbgU7efnmQMtB+
0S+pMltCGpPuls9jaj71hVv3rAEQ2CFG0lKyYnzZ6OrAJJG0Vz3FcR1EljZFEb8M
7nqiZGfin8lvIIULFTNgxzoKICnOF2m+3xktsjXRi4KFYeI0rKvMJcn3F0PoVqE6
BEkOARLDKMqnncfhfi2PR83q7BIki/oQirRcVrXL0JIHRL3N8NHVIzNCt2GATOYV
gAQfjDwOre+1UuayGOjkYd19//LYOycAfehD7mG0xKH33vn4JrXwygjmzpOyfYB3
TvsUO1qDMZHiqd9WRbISpKxy/tANsdBUpf//ZBW7VsRk2CY4XFPDmXUQM0gkXvXX
PVO+c0cYFJgTWMc2cU0R/jZqnQFNRAeYyFRSgzl9ToJW/mw52oSpGIoFSnA6j8LS
QwG0NdxhBS4Onb55+kFfq044c9sU3VGNQ1JmwGTGZXgDIdtvspkXko+No7Bjjjdh
ba7foVbtkDg/jrFaTteSc1h7WRk=
=YvzF
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '83ed07ff-1077-4ccd-ad84-eaef9350b65d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf9FmC7+EwDdUPMHkoiyzE/Ref6nJMy2UE+s69DbpBvizrh
+/URoMwMqz85iTbrq2oqlhQtSxkQNAacP5I3z5HYnc/Xdx6AeCAV+w51WkbpL0ui
seWPN5yGgmOhEFlv60vtTide+lRHRfhnJLvYoRUGLlx8EO/Fy9UX5DRhL3MiNf2D
/6mTRVyE9K3fkEiXftZYqAUixI+S3jwZN7kBs+OCJ8P5gUhDAcy66q98qs/imZJm
kMUZSW/3shaKPEiHOACQ+MTZrrtgtZKNZH4ffTbUtN3aNOGOiy+S96VvuZzTkhOs
tCqmoOMxE2XZR/Y9pgUYHz4rSn/etjbQi9idRl+yxtJDAR79XEkvE7mEJukDcjLR
3HvAeVeDKVFn+wWBV223dwqCg58vtZzUu8B2PCEGTEOHjHql4ZUF6bbRFPuOkxV1
uAgtYQ==
=v4kq
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '857cf78c-ac06-4c50-af9f-bcc0d9578d8f',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/Tqrhi0/Ii18iinopmCTSY9cV8spxmU2DoPcf7VPjsg8P
xL3kjiw3ATzBPJtOUJ9MSzSbH6j2GPQB5wWHY0UecuM59CCExSKz/Up3kq14KFXA
WSHby4WQxA8rGDxEXPNYfh+PrQhW/x1WFX7hfs6/gCh2mvyPYEdgCaE6Rn1PWQpP
itSOgGh/sXfgWxdG46RnHs1WYsX+Lq6O7U1/5Xmg0vOzX0nYMRzKtBE19C1XzeRz
j5RCOGWE9v5NgmuK7M+pVgBmT9c+nq6SyyRa3SYDbbGApZ2x2p5Y6+vjZFczgpEE
jtC4NJ7KqGxUgNybWKPggu8voajCzGHsrV9EfcjyZ9JDAV+c+GR587ZRgmj0JOw1
j1Qx2Vzap0ekafALI/setBO/Jdynf6oMkaefmBtGxyRh34R7JFroKwH+R+3xj1aI
rRNUlQ==
=MSqd
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '85e80020-6dd2-4f21-a3c3-dac67e591110',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+JoHB8Vv5TMNX7y4YQeR75zEBUUvVTWq4hypz+rsU6OK0
KAcpdOu1se64qTlIw8UkB48dxIE95tsrETLs1sloOCEGgMD+NsPVRSZvfLYeaqN2
zkEWo5IHice9brydzoLNvH85ik7WZwY1m7rvM0A62ByComRzEc8RH3DQj9JAY6rW
hjonu2BlWlDeQeZ3zY2hv7HhVNdpv3ZyN2iwL0Fo2WdaKWEdJnJaw/UJyn1HuXV6
2pp9SGVHourLL+eDdEhAyAxF6vG5TOpl+qWyc/PPd+bRtbRyxe7P8kyrZ5FY8o0d
vjkdhbvdkhx7URDQdqepelK5FW/7EWMChVohMQuGQUujdVWxcbbLbv3893Ij+lqa
iHkK/LxvAnZa7cWhm0Er6lxfeTmdZrYjr1DCQZI0uWgJgQRLTYTd9If53V7smk9s
qCu8lC0hpz4bEu/RzeAJbYIO/RmcGKrP8VKkt3ZlQCkXNiyuJxVN8TYt826NlGYW
hmWjXDq+kuPOF89uMO8eMlxa4C41I7XH7auRs/8uxUdGZow3NLooY4WdmpNTOkk6
NhTddeSM2fGtnWJCLdXZJBOwNVrPPNExQD8wirJX2aa0iCPJqE6/tvuJ97IG9Rjv
k3RcHo1J1yL2pT+fCzfvBrDPu5tslq1ZIxv+FrmNylwHaMD+c0hezDqv9VBKYerS
QwFYLMCuELBWvtqs2lPpeJVLtrR6MqaFGccjoU7IjDnmzdamFkPVg9OptG8jiszv
EuYdMaS8rwXjXXgyH6DgCduspA8=
=wmFt
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8a1310c8-821d-4ce1-a843-6ac2adecc1d7',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAxG6CvfnwJrVuJMS9lqZKNS1Wbe2M88/kGfMtYx7jF317
cohiZY6MJeZ28FkW7CCKRs8AMTlr45e7LrLBPvbAjxVfbag5RMiYK+/ZrGYKkwjS
L291ifybP1pxfeFbn1fHdox4w17cD67SkSQ4WEj26f5NZYGo6FflUss9rjqBn3RH
Zmmm4lRnSxUvCN8bLeFtl4EcwBAMnxRBH33USXvbXdDk8TxESa5Zc9swyAQzr2LF
ciNJ95DUaaGEv5IuIWSXHATPVHtPoylTulhxpTXD9uf0PPp3tvcRlrih2P2W9QFd
u7DPgH3XD8jlrJoBEip3p1axYx4xyEnHjl+VE22YPxc4Q6Afuu0dLG1jgTfK8NH6
wJjJxnzqApE/2g3bh+3qMS6jkeBayDxITzazqhyDpj89/P81x53ghxmmxec8kq0q
kzDI7Z45ErA1DMyuxkplnR7Fu3MHtDTxgL62eDDWZn3oyBkHXALKGjgRv+9dKJu0
D10A/F3C+UcvMz/aMatEa+DLD2NxYJGs7Lj/aRidHxQYUjSsTos8oRsSK74OkcFx
FIzWuEqZavco1zvMUrDWntK9mYyCjrJV8QC4wy7Zl9vcpm/1ONFZsaCd8WXhaSga
9hiAcwmUt5w+s3suxiw8zeIFuCTMDLQLHbVvNF/NpqSRlmcjZOtqhle/nP0SB4DS
QQGY6BNCfLLh88PsL9t6wVtgYTYdnU2Ctukksngyg3jcaZTBr6LhX7Fn6hyCRuRX
SYAa54myg58pmegvz2/Nl4e3
=qbM6
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b77da31-441c-47a3-a423-02a3ab581e56',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//TOs9DCLqnmOfi4vVcGg+bG6REp5Q/1IerLAIGTMcIlb0
I58qLa0OsjNv+xsUk7k3Ho4vgaLrGIb0nq6vfDF+4XvB+5WxT9UXL4AP5qDZwAtD
gch9Dl5W7s9pTzdblwRzJKtkKchzia3Hb7TnmKDPuf2mXk2R2xcoyErGwsL1aFKZ
09JBgRw55XFERKqIR+ux4fILhL4I5WrHiEbhMa6X+HXFbCHiII22ecw6i3QUyMHM
ReK6rdJcVL8/kFoyyAnnSNM0ZRo/E84g2b1ck0SBqMyFK4yDBOq1qbBb4KDeg1q+
BITm0+sJCYLPNCy8EFLyW4+9TNw9bv/tD8YVgH51wzLFBvUZZe6Z1dzrUW8FBsH1
FHjihZWOhbGCJ/eeBfdgRkcXYP+4vN3OEAts95+GICp/Sn7UTC67YmgefvSSySTx
vLr54BAq5Ss0RLCKvNDDKvZ9uxHeOr1pTcmxKkK3k6rApewZDmrpxN4uQkGJuzV+
3SQWsfmZkmmBYNt+fCM/1teqYaP8cBUGGuKQkFKCOowydCGF2V6MMRpp+ajt1KJ8
dGJgauSkCcQLrY/DypVogdWQmW2FAWGiAaeT0Lsc/nx3VRDpgHrzPYxe5AfGNjkt
FAsVjvfwDugY4Lqd9NboTL5c6Qh85h4jKXxAdSOOnk2okWPqoexOQprGNxrUgfzS
PwFBOxftGngibVjrz3Gjg0THEYfEzhDqOBsWBs0hlqlv9ZHUqYb+4hWRAioZL6sB
pda3TEYbiB4ex8N89UirZA==
=k7aU
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8d610565-3a30-495b-a67b-227b4282e0c8',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/b3cSNal2Cuer/exRSbvEAcEwnw+2daNtD/b8bNzmjH3t
j7xr7NlnyHFi4+3hVuszJY0vNg/9wiQqo4ncUgDksgFKKt5lR2eOwS2sDdj5cc/1
5Xa/cE4sew5VHD3agc8tYGO0G83bBJgi7nst1xBMbf0XEwS+mY/nP29umfQJWdfU
0/IYoimjophLimMbF+c64S5cyoqQYS/6UFN0I3vOhSd/yQo98SpsRF3jaamiXIO5
xOHC1Gq+TsC1KU3le/FYLfjCf4kEwlObxqlOS0Q6j7uORPCf8GNpgjNvuyseFvUy
cqge7TasA5O+TEXLtgXdPX3Pbzp+/5mvUf/ZCM20eNI+AdqCArrLS5ECynj/XWKR
l7jXX8Q3pyc8xjlsX1ddTRie8c5rs5t6BsT7ZHaDOJaib0SwbH6KL0emjwME1Ec=
=JQyF
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8ffeed55-ee8f-43a0-a8eb-14411e1a8bbf',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+Ip6fwEocYmfDOtmuMa6RkRAcPu6ZMAizR6k+SKX0L9js
qvuMoTHE1aNCtNZqd5Ng9aXXsSfqK0YiKlhkW+uxUEmh4ODKXn80ZUoUim153TNs
kAcCgUUCH1NEfcXmi3N1iV2TR57Jp9I55q0pod+cdNANKRVOnvdiD0fYYkbazx9T
pankxd4jpXyZPFSDfBwEtE+8PCFtHRE45iRuKNGo1DRHTLbLxBrpn7ehEx075448
BPMuq1Gpqpvzx6jLnaFzVnrSEmKtsHUDxEzbDpW+CEeCVqVsfB5VFGcV38FrpxnL
FboLTFGj+A3whELclBKwyIChO3mSrHZC8nHU1NPd6dDMUeF5af0rGA9PpB7YZKtc
8W/8gcH6eOzvfQ6GShxzWZglySps3ihg1qUJ4stAOXpuTQNZWFtjmZmHXUQwHy1z
kHTuQhoJbA2QgXuQurTUckyLpIzf9E1zeOPz3WGdxa/8KgIDFowpyS/dZrw/+62r
fo5aqjUsqngrb6iuWFJBhCaiFzQsqfVXNlpRI1H2xSRGiAomCr7ympZ7VWsvOriQ
PDgB2z0feSbxh1BdFpMNLqBlUuPbA1of0y64f4F4XtuqB2Zs0JzigJmDTWKRNk/l
jRBGLYh4zDNW5soy4fXk2fVe3szWm/BR9tC6RE3aT9VnRTJyCYjdVJ7ICcrfAm3S
QQF+gbv2irfI4IokNrLDDtpLOvnCO2pvQlDeOZHT4z7s/8KDN571B9D+6K+tePpk
xe4BCNT7L3f3S//e0+zNcab0
=zOs/
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '90b7537d-64fe-446c-a8c4-3db0bf51ec6d',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//VRba9b/VXuAu5S7Qf7KFJAzgRtzEAFSweP+DlnJvW/EQ
Y+osnKXVPXGWR+Ka5/ZOVH6+gVQnMjk+aYXw2kkSe3P+GkirMcmymCFYBatqs18h
i1HsMBRwL9bNC8q36DjcHn/3S/TPfaZ5ja9Mztv6ub2V0r6J990PzcCG0ViaeX/c
/4dX1wUpfU1f9+hoOosT0NuJqrFciYBJWW/+CVRE8Vv4k2TQ49sKW3FbD+kK6ynL
jvt28WoGfZTxQI4Bamu5/fGlhRxaFMNsUKcBdVbZLyZXoRB2jmhpKonrwzQDWR2l
t3iQYCFhP/vBeAOJM6SCPBeBlRELdIwouZcU0lgyvOAJSZry5GUrSSoqFeXjUQKX
tc7oDKrVPmhayhtWW9bVmMzr4SsXrqub8BGsSNM+rfFJZjidmnoeFzhFuaBuBcTK
VGSJDR6B5tYQDOQF/BMNLeAo8zAJ4OIOK5OmfxAVZS29XsbISIkHR+1mrCskxTvm
67QfsqgvZ7KCfi8K6+tOQISLmRjn1KN/cSobQJFAfmahEduphp0RVMGBRxYxYiYw
LtHGoZPw3U1SpQgyoOvDEDIAzDWrHyoVknfgRIlLZVGxbomOChfPfnHrgXWjCTmV
CZT0qW+aUJWnbdUPnV1rtGMlDGR8RgGhRIfpulRkr8uehvI7SrfN/xuaFcjRjD/S
QAFOTD7lZenZqqmzw5eBvsrcrahWAncHVD3KD+7bviI+BOYxypIaI35ew6zcG27x
87+WNAAAI6oyIelc1MVpARY=
=fAaL
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '90f74f61-aed8-4754-a971-45d6543e8c79',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//YYNSKc7tFCVDtwms+14zcKYsX3etLD5Tzh6lmgvOdLQt
eDAdrnPvYUyP9xCaU4SzjFEzSHFhQ2aNmipEnW5CEpikSHgCz0lT1CPfsT2VY4H1
XUuUJaSrfQ+g4GJigzO3MOGDHN5Ce99hzi5tp2pIPoSKr52es00q0AubTzmR9tJM
lnJY80PzFEcLtue31p7GQsWoHy09N+DuCM5NJVA20cETZIqJLHUyjTqCiHgxovUA
vvCyRUyNSvCae/9YNvvdsy6Zo8ExIBkg+FnmE39S3nIdgM59X96RZSFR1vcZ5vKi
4NYAkjR5HWfqLLtXCz1+ceRoXoc48mjMruUBgwMhHBPMdMHnH1yBtCUU/uHyq3re
o61NdH90y+izLzooZtHvncX2mQf1GkNQOpw2PjT5F5QiGHvdVGM4jI7SsFWu51bi
e+nCpKk22UntSBnVEKXwcyCkF70j8dNE2gMjxELGWVvGzZhE4Bee8h3KDh7fgeKw
L5EI6BxQihMWMcKhm7q2FWW88eKangBQHxrbZuzJFyf3XEvPlVGUqViW5+Qp5ZkE
3AcJD0EtbfiJjgwhnnwyGXBy88IMnRfti32fhy46mEUY5q6vhceLoWDfVjIta3+0
SaJ9A2mYozafHca4qxSsdIjYt5HRnDTT1EP8rkhPvdtpat4cW6ZFNGvYuuBSN3PS
QQFFmXwV1M21aAdW+KHXdJczvRUIsUOrKE8yw7t1u0qKB4EYTak8+LD2I8TeHZSi
qtrz/3qzLQ3aU8Sw9M79zDXY
=8QEw
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '918fcdee-babf-4547-a86a-9425bbf3f4fa',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+Jx2s2eawCCr+HiHiGEKga9xwiesXGLPBSUeCvfTbTpeS
fEirqMUwLy9R1AuzZl+Ys+dujTlDe8ZF/kMgtXC++elIwvLfCh/+X8po8M5KhSR1
PbZLA6cF8sB5bTJ9mjpuEuE5BWU2DZBHR6Qhfr2BSiDjfqCoXQ4URKy9jS/n7Lg3
jE3QO5maaYBnUIJq4cjsWd3imZPBUnvktV6efsFAxt8I9xxlokusuo0RxwpKDTLx
GX2fiRIIZ6BHa9jNFaHzBMA97U9nwDf8dJYp7Zkte6/grhsF3BBMLLZnWH11BqKq
NpkEchOGMDzoC2SjMXeYrWM9z3pKUT5XHumsxp7EKfNxzxr/SYDF1DG0tmXwC2oJ
wgAJ2ZPIhNW82jZk7bTDdNMloETAKCqjQ6iYfyYVaUjrgiBaxzu3sVZ2KadQemIo
BNm2e1zaufolSznm+6FehlFVHJA8DqIcbTEu4Hvy2DaN13xHWwUPVx/sqVYWayWS
+8GjPtwHk2jRnjnNaCSgQxFKx+E94pOg2IqDQBA/tMIfKOLpNenghs/dmexyELHr
qn68DnRf4Qea5pGqeQg7AwscjnM8Y/NVsW1guAvXIU0Vzlamy2U/6KmVlCphD/yF
W2cF+zWY0CXiaGL1rlG+Ut/HoKoyH03elft760zBtqzDGYO4fkQAZjE2iWvRPtfS
QwEZUZwzK0DkrX3yRy/AXu2xkhqn5lIUXzLUclZw6hAaHonmJFSbXyYJZrggKp92
E8hBmO09uzumQSOtyRLAX88Dy5A=
=lm9r
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9233a4b6-897f-4556-a6ef-60f309d1f3e3',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//b2CSd2gIXzzfweworDFlnV99dNKLVU1LjhfSaJnndj8t
pVtJcLqwUrjqF6sKeMVvQzgkJr9ulr3ZXfKoulZM9uOqsqKDIURQPddRW7YEWc6b
dzOl11Yy+mD50aeCOcS/dOeOwgwCnyaQRJ4Vs79L5Fq7YGcjQpt4zvV5RAIImU+z
OVXCzcSecq1ZNmZ4VHl5eeayMlBgOGeguLP8N1xH71YzDW7BbDKY9KB6O93U4skl
NFyWouIDnhY81Lxonyh3vhaRG4JIb+ABzcYErV+pEugRcQLTH3QxpY4jYqogBYVD
lV6Z55wAgtiOPdTYd/RqUxM8tZJ/6qRdJIt9FYWZL1plvdIucul8uwfK9RDtXjn8
nkfQorHSo7G9efpfpV1U4B093o71jaI8k+BMZ1eYOi+USz/CJ9aUgWV63kT/vPLH
Z4VqbGLnBVhgcFNj04dpQO2NEY+iHiOoIaFfpDf4kNqQC33V/eXfi0h5qE0tQ61N
Q+avPqFHMc1Vr4YXU8W23sXAxYtSG6xncrTLU0nhj7rTpvSFdqgzKPBxON0gx3R+
TFvIq7ubFrSqrmKnH1aLA+lc+wpc8KsyWN4KYdzWRmNSzoP8ZNVqK0/D8xsUvp4u
JU/WTYJ4R3M8NerKKkr9n4gu2B32ydSfikITrQzpka3PMELnaZlJDWGcV5AQ5WXS
QgHipsE+VTf3MfZN8tt4U82j0kI7WQ4wqpbWjBBVmiSem8jg8a0uP0FI55/KgOMm
zPOvJDwORG75IRzHP810zB9rOA==
=SGKz
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '954e8929-5508-4d5f-ad42-bceee0634d50',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//U3dRbMPrah+zUrDEUGawRH0G22GGLVHfui3pg61bIMNo
mmzoVXYy55Ryo9Nj+kZNCgiVHe0mB4k7zEfIVUKS0hgE3Z7kBYj1y04nDkVKDvCc
I0MTEU9VjoyZL5GfoiEpem85gyM6iCrbxIpBbaBpLmqCP3qdBSFkOtLwLhbqP/qq
I0cS35zPpIdRetHfm42U8b7cTJAJ7hal+umA0GS44DV0rPoRsDDlMCyG9CglRT5e
OA7n0NAKmkmDbF1nN/LYcIv4WJPyuNnLULGxVZQvQWq8F8dLj4/z//BcjLt5jIu2
HR8NfAccETsUHULFPlmD03t/X/S2bXDCgSJ8AVFWTPXIkn2WCZBzN8JPXYefC1Il
BHlrE62X6qdLSnt6CtGAnr0yAKlFHxoQ/1p148zpv7rmlcnklOxz4POvfmXMFOk8
UkLXPlQ/9Ec1dzufAbhCgR/mvfvTbLo9j54+twjYmTaDaGNxuxy/iyfEDZJpyxOl
cky2NIzIUELoQQW8b36zSyl+raT9goEYWjYEL0uNIWHLJu8gkwvoruFl/KvNmgET
hTrQbraQ5RU0t2E0sgJaegQbz7ipoVpQUgT9fV3PynaGacJBesC1Q3kIAFzNpfvK
tmfO1b5urbRMdx6dkoGBK50BOfQyx2+0FX/hbDIjZ9KCFwyqDAaBarwWc/WA3+DS
SQFkfzhmkNJ66BqGOGr/m8Lwi9OqZC7DeO9JSX8s0CpjL+fz4HIJP2TRAol8Q1NP
z7Qu8z2p2vNPv+Av0++/F2SOqWSGTX4kTEM=
=L9MO
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '95a8db6d-6938-4d49-a263-dafc332a1f94',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAuqZyi3rhADGx6VfsfsPnHnCGMQCvDH4FWnGba1K/SZPp
2IB5+uvzsD/q2dCf2j200EuKrr/UlhAtxHPPCKgqsGDcAtsqVHdD5PoKnD1bgAlx
71xFZ5+HE8vM03VGVJVSuha1b22JvOVua40Y42VzQVdvJkutiekOABnL/po6ET71
eP5UuiJhR6xJM5/SLuXfOoqRwkuvrbNFOXYzNi7CkZfv2TJA6/Q5QblW0oIgYiUj
tlCMKaVqk6q9WcT/dFPjIlskAWAUORID+yPftJD1L0VttxV9ajPN9tY+43v/6WtY
aBycVdMN5tDgLjj9oxEAg2wT3pu5WY8c6IKXsfX58HklM3xeK7rCuWcNorAcvyt0
ovH3Qcr/qhZ6pOItjI2zwBifBhT6cbWsVORKP+W/FV5k+VUXTceWIndVWNDSYJbO
7fNLloxOhRYvP0fj2b93B2qgCKdlFz8T7rnbgxN0Th2xPQSenoLrxQra7Wl3yDx5
HUeueqxNhWy5GPOPNkX/7KQR95e4YIR5XRVS5QyVq5l4Y3ti+E6/PKlla26o+I0v
6jyzGteib+/m/UTW9P0ukwOKMilsC916Y7+MPU1yw8i5q8GFlVF5VLlj+wHzlO2j
Up3dx3tWhusBPMNLeQb93BlEO1uEDwNcqUPfICotoNKnmsqDv3RG9Xa8Ge156U/S
QQESwis7a7bB8iSm+lqK1hMgFD//C91bC4CKFUthYSavd3czgJfCC7aoVHdo6E7z
rc/fIlJ7FtjlyZDtCBEyq0IE
=0lrJ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9649fc07-9ff5-4b9c-ae0b-b33e61fe5f53',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAts9pUL6x2zhevPjEtMnp7gTPOZyEc58EgK4/XiHdENfN
lPWi9zaoVvqeYx4yY0XVhiI+qfntooqwpdai2sYGOS7QnlRS5RKNs2I35D3RHUD/
1wyviPKjmv9jBs5dYG/W62Op5ztM97xuamCAB34g+gu7sFPyFUL6fdTbM1HwCZOW
g3EOkSwbSNLqXBBebH/jIbg3Qfkxm4/vfCx8xLocuaDhLDa6UNGtA6YN2BclD3K+
2ITksuWguFl6LZCev+VRNDD4ivzjpK/k245X5bTTjtlUo9tfgzndnjafuOY8vRcE
sVzSMixuT62hlhT4+4Z/GiiWmHYrMZbNRlLRZ2Ab5UFCzL3XiY6fdYOQVPbjchbn
YwxvxEaKXthTt08iNmJ0FfpDDwG3CPgz4CpZ2XAkOtwRQMdxDS+YSmpkk06hcuNG
EuBn9xu33XxD+im9VYKlgRMdCNGnye1yhw42ycR+igcguI8L2JpQPAoS81Txr+QZ
EJ36ciAHJxmv0A+w7oewlzIr934qJL6txuvQ6Vv1Woy2wy8fm75c87RP2U46fupk
no9A28sShqLWgil/k4bKOfSvZ2qshL1fXbb9mmV5MEbxXJbGQCvt/rIyiSSUuUmj
thUL8+PrMIn0yqD7seNrErP/8holosUBM2nnlMIQf6iqRMmkeqDZgHbnciVitLXS
PgHBvs5ksV4Rmgo31XDmaZ5RjyIXxIQ5r/TdfprhN9mjB4CVpP8Mkt0BNDWab+76
2juC7yRa2StMMjXdVkCN
=JhR7
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96ebe8e6-6dec-4496-a9eb-a3a4ff197340',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//T0bV09e+F7jGswtSMV2sUpEDBxTi/acgl1xlkFdGwjXj
shzmCQaTvVMYrMXbMdK6YnZMjcPgNMiIHHGcOfb3RUTLrLfGUz2uhQKDZK36+l5A
11/YsiIwzH+OXBnpiwkb1MQU9iTcUnCoNSDA6Ihfl/2ABSQeKYHF8/NX62wF4YSi
p8G7GgI7uo3R9O7Vhy0MWEAQq3oXOIRMq8c+4ocg6WpauHfc7xw/SmjhQZRtNuP3
tOBC9DDx3uCZ+J0Hg1dsw+kjqqE6oIKvY5mTzzmXDtl1pjgyxNFpXIpMHGKmF52l
V70Tm810uY3uyXRzrI9F+gZ5JIkLq5j0+UabTjCt63HvuAwjtHjE9BE5KRMUul96
bPSXnJqENpOp2PB40sY73s2xW0K3ccBxx0bwozLlk7FtDJNoMHNOs7h7h9hhTlGu
2w4KaUSBpsNKRAITDrftMFaQ8/5dZC1T81+x2sK2twgpbt6gZalWAvexLS4FnVuv
TttzyIVu0PCmAMqC0W2F0u7IzlTzQDE7Eftwc6FQiI4P4a7FpQe4CBNnKlUFX07m
M5JJJx/wR67o8JzaTCiOhfSJHwQHut0pTH7Ku2jC2srdKn98UlzdxH7812HnTes+
N9VX0UgklKN/7wPhjIQeB9Iu9jprUhOHzGogHfd/1kRSFq4Q3xeNwyWH3dSEgdrS
QQH/6A+GUizqVy577OP22RtgRls/FN0fjpSuttb9N9a2Fwiv+Kl2oT9nbGqczqXh
X4Woy+sb9ndgygFfkmGgLvHm
=Vcfg
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '977bca8e-e90a-4061-a1d3-1038b308c552',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8Dflg3jqr0O3i786XTpCTlKFIYHEkeGZxDSmyJKyBXyKV
eBDrRo3DWGg3gF0/DOfOyHa0MPhWCP+D25ZVpc+K1Ip4wyqm8RrSpSSI7WoGow7Z
PSwgpRnlDQpGYsoeXWCdmnlMuMpy2uPiN/bx9WoVtC5XAHBL41Rgz8ZAWbgBUAPK
tkEI0lbDTZ3f1NCevSCjI9I30GBN647FccLiBiWpbcJ3WtxhtiDxtj3C0RKH0KJZ
hlXQQRtuxEk/hW5njuwYffVeqSPaE8769HuaCSueGq3+v6Dn6oiOh/EB/fHuW1/7
brXqaVU40ux3puPpblC0HOpJHs+TXYmgzAwJcW6z6fkQ43YwkIGR1KPoUznYr9ni
+64evTsqI0FjdW58X//TN2HuphBWxnnv+sZVaIiGxvOoSprQNHzR7TJFMLOcd3hR
Fl/+0CCA/80e/YhVbKgcwEL9afCVj59J2r0wGbIEIaua5s8rEW15iEXIoVI1dzh2
N+Kur7vBRJC1cSuKlr8KHjpH8811hDNYWg+D+GcbCUPMO3m2umcncTr0J82wrg8F
2eQwWoqdAg7sAbrGpJCoHMnp0AsE+JBtFyPBHihm8M3ydQ2PTvJA3ZVaHOHNpkRp
DSDUwpY3EWirOaBYiHFeHFgc1E7swTsxlfRXkezqLMHBPTWyRYoMf330nLDHn4/S
QQFPhdy0AtBlxcQpFimF7tnTABXg8iUDgNTdVhiqW+44m1LdCN4XjYK5VUY1xX9/
zp3Hbn6JExEPwLVmPFFsECyW
=P4P/
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '97e5798e-7171-47be-a27b-4d27d4111002',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf+IrQnsapFJnrcLK+Y9legaSPfzlRa+/yR7JDeH3jchrI8
BJAeFtkax8Xi1XruZ1g9L6svcC9PJlaAMjueCwRPvkwfeLBK7I8lBbIVDhEkG70P
gAoBylCkDLBU662/SZYI/wJCkDUiQKpqDBSR2KVrGyNDlNDUpM/8WZKxaCsP3dl2
b0R1lNW8Sqd+JtEwQh4oENePuGRz9kLxi6YuRik/UUN54HNFbqG5Stzc41apkBHz
E8M3U8cFiblIvUpI/4/SfTSOGJbBa1Qnqmyz66/M/jSUbaTkGyRw6i5TQDqcxeLn
Mbn+D5GcLUaeVBzt7981hKekRzqs5rhc3GhxzuA289JAAcolSq9Yapx3XXmm3xxh
FKbveBDc1ZHHXY4UJJrrI13CwJ0uq1+SuFhqcT1S8692QbJFrJOEBuaIVAMqewl0
xw==
=MUiQ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9a76e9f7-c1dd-4c2a-ad0d-867eea4af3c4',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9EP21g5XN94jSwFcvYsVM7xKF06iC/20xlzol7AP+3wgy
yjpXgUYTguxzq69TVnhBw6lVkzF+29hvEBQ4o5nkeh2SQA1DqyI/fiblaIhNsSpG
kwqwCvQOpslgPYswK5KrUKT4r9p5TAfR9Au94u+HUYpt7AxA+nuxX+junENRLs3x
RuldQFLYW/cH52+K0+/ecsrmpAv9OGtXm4Y16NdiYWSx698Tx8AxMfKYUJYCox7r
B9Xq1eBqya+e5aVarKMtz2OudBlsE7hPLpw6S7wk5NFjwBaTu0DbAzMHg7km4bh3
p6+m5ghpe0O4e/eHymothnUDaJ3dP16KSy8zKkyM976HdoMF/kYn25W7kKFJX680
hK8WkLKF6dDQMeqSPp85hFicf/d6RfYDLK6YMwa7tDKE7uSt86ZAQtqI6DMfjnqk
Pfudk3M3gTglVqdrjyJ1sJLRStruGaVmiAeR3gbtM6Mpt4INvI70cjZGQjQaTvMD
mgMAuFZXnCAH0ExImLJUk7eaWchpHjokNnSPW9zwY32EvUuVfUfv2yWhF4hlZC8r
lTni3zIBxNkEM1wycAIeeNJa17scKI8qB9pCPCuL4NPf77+mYFLZaJNFVd5oLeG3
zGFQq5rmF6mVdU1qACjR/n3QC2SPgLm7J2cIlJJUXDrrqTuMJ6lVw90mFlExN6TS
QQGGe6U4qHsEc96OaddOZ9u8ijAPl2+Byok/F7v9Qq+7N8FPpRnKqOucwy7HcKIl
kuWgw2oI335APY75w8FTOjTJ
=Qcmd
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:52',
			'modified' => '2017-03-04 16:10:52',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9f3618cf-0632-43d5-a8b8-dee96904e037',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//cwEatouDId+Hhn2mD3iPa+dveRgxX+gG9IFMdjEt139s
Xz7htccTQa1SbpA2EAldO9X7ebHBZMdZNbLL4CRRJd+NLw03ekzF8GPa/pnoxYOc
kZf6qZGoKVHdm5X4M5gzBrMSoL9FnuSNYQ6BoJhV2wisn1oXYuicDZUc/IxSKXur
uCjs/VSSb1PJj+XGgeH52QHNT3DSbTHVIx5T+mesHpPAgYkx8o2NPpEeCfy9SaRo
o1mroZYsxVJCOlTivpoXU8trWfFhfp8fLUVbuIra64+3F9/jGZkrY8ajkvCE/yH/
IOjUMclcy9tupsObqDfkV1YV9TZC807lu8XEj1MFo4eipLtyDW+1IIWaEZCks0kl
awCxBnxcsCp4zPLHt4p42MTq9algmfJ/ztptHDNA+gN4B85jB/2wm/Ly5qXMc1nY
FBrwfWAPVKwpt5htBY2+T5Z/rSQy/XViPNAHHCV+06F2uNY4HxmuFAVjoEf6lr8Z
D6TiEJfww+xaJ4ekRAROSDdQUPX3dLPhh8m75pkCHcGmOzlOjVcYNtTH9ydCRVYe
PoZ5XRi9xb4Gsd/17J/BIIgFdTt6mL1u7K7UtNbp8eyohoteC+tsVIrealuQVIRr
SCE8JHFv0CXgX+kcDfyvXOcRq8Yqu4ZqNR9RPxdNET4GTqi/VQwjnhRrVBkJN/DS
SQEIlqqdsFpn28oNJpnSNNOGneT5YnVt/P+IBE4jrOnaHddC2gLCeVixM412I7t3
z22yTYZy7Lhq1CZyXl47oE/pQGzTp/rDEP8=
=qHEA
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9f757aec-81c7-430f-a908-55621d1d3a40',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAoug6M8ljJ5wqrVJ/oGZWbJIegoZLxqAExxgS+xPfGIWI
Y5NcYn3jYqcKo5IVNehbQH7WzrriqlbgHuvzcjStD0hfvbGS5wPvLALKiedPTlsW
yeY9xA7AgXx3V1DAzHZrmyLbLgdsRe6uCDseXAWgEwcixaBXqUCzpDTDdksiR5qi
JsQ4dQxzwHbrJkEyCChRrxYtDgkhJya7SJ6SDGpk0suj+4oYXK8XVy0UKb3yxPha
fWqQ7mitMSpgrKr2hZubYdQ++md4y7VtJzMYxzPhhy+bQ09b9quHhq/SbNNM8Gw4
B1ZHIeeSsQJDgWJvk4zr9kJzdOVGo2BybSTn4Wuzp71AnxYsFKqaM3zDSVZ2PrSg
VK0FiZdwhVoWbqkHmxOjf6MTInJ6YuMFFEHQGvtDyjxbp7oZmU6CtvMuui1OGJZl
drTCnSxraF6YK2ueZISPndLDNzfe9o5aw9Gl469DRGceIykfdgJDdcjTgiFExt2E
Ct7XqsaPFXpJOpRyKIxq9x2kw9Gy3i6RQQJGnMjMRE8nninb3sQLxijz+h4SnnBa
P2s5hsyQtJS9TTL1dnBYNiD9fgwAfoE8q4gub7f5g6DkSk03lpmkgaXorfDcyvov
4naNCZpkPtYv+AXH4TzxYmLFqt+RStZnkHBDrG6ktd+8+K8/9+KtOrKKoCx6t2LS
QQEq9DAQIll855cKwBjwWHKz19CN8MFjHYaqoCDyniNbY8USUYQDWeadP7yWdNon
1Cqh+k18PYSqC3BvVlFMv9Zu
=EhXA
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a0a459b3-b462-4feb-a6eb-11e1eea42f42',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9HDh4BY3bAiKr8ZLTXptAcIIoW/SjGEh13b6gHoyqupBo
HGDZHbgToypESGL2STwFPRW986atPJCqqxEeZvvD/uhw80H00Naw5bymdiLfEuq3
H3LPGu6y6s7rVtIptbiEkMf+cLIRHJV3mMB/ozfr0UKv+fjLHE8luTUO+2YrmMRX
vG9THjq1gANAiXSIPYHHm0vL857FWHJmESCRMUj2RJ72TkjTwUzeXIMYEOydzl+8
Wj/x+2xzUpIjs8cmJuJOy/OVpQNofnl1okhGtdP7xZHoiKp5hl6oKpU1JktQ6rgK
FXij1ks3Ct68dhZg3xokEE5fQpPL4xq/5r4v/xQnxjtpj0cZPI0Z87Fjfewf+vmt
qdvuWznVQjjjBAW35vwMw/FwU9UK0GNYsprDX7fRCXWPw0n1qCEdS7lA696VBvSB
S9+Bp10RuTPnrBfoJLZ/uftHeUSxRfxVn3sE408tDwZ6F8gwCN6j29vLcWQJBmNy
q+cnhXZ6U/KauqpsCz9J7UK1TU3JyLz+Hh39zaX8fw2yx4bCT0tPkYoWMnLrAbu7
vVIxJNKLJlPwBFlmsiE5zMNxnpTMerKaPACRcmec/CGNqs76vsN0kfXxMsxuqdmH
vCHX5j0di8Ze81gue+JLx/yD73HqMDoS5oYxwFTRpoZJOGyNx3CZ/XU6Vw+oZffS
QAHKSyvkzBAzPViK5/PjHe1dNd4uJNQ7Ta0hXOQlBl5oGNMtQhOQ/DwTdnldq1q6
q5NrflCuLd5NT4WCENJcre0=
=5yno
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a1d4a363-1573-4014-a0cc-156bdb75d288',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAuemdUCKIaZQQQRg+YC0qwGH0wOx7l8p7dBz22JvWALId
fqxsLJHo73rbS9Rb9Z7qAB/NPCumBbqlBsCB7jnIaLE7yIfUFZwTBYkAVeXcS1Ik
sWSWM4jeXNoPRwxcJPSUqNmNSwNyzoV6xArNaVd/Y5H9LjiB/16puxuZXYvBNX7v
qyb2hcEUf7bG+OFAM96leFUbz3G8ktpzKymXkOiT3L+2V6KDfO8Yjj6jUTbrqwSP
iBbvCnHleI7sdEtLVSkOhwx0g0IcdQUb3PufoJ3WsHVARi1L8fCTN1BwkWSrVP+G
jngg3Oha7GEcGuDRj/nE+r3+wLSP1XgiWSGGfYIDLbpsAP48ZXdxz6pE0mrFSF2N
vmjIiYnqdy/Z1RdkEaCOIf9wmwAvnkmoA5a6egPuiAtYlDblfr6Jp/IL/R9/Z3QP
AAtvqYuekH6mZQI/b6V2VwiIJ0hc4CtM5uzhqE0Q03/ZnI8tIM1zOnqIuaxwvLr0
ig/wGCcysxOqVVVS6aGNj+RG2ZolJe9pxDvV3qEMG7RyCO8sgDG7ujeEg9ESXR7X
CHOmeJNOWmHANO6HJC8qqdt/EGMh1bAebb/MKPBLw/QP0oIgxxg5V3dxc0irhq9Q
kg20GqLMpXCOaVtwVLHrNnrDPcDfe1zi0xUO02EKpmy0hD5wIPJ7jywX1WCjwEnS
TQHGvOoyPnf5TUF0mhYV3yQL4xKqXZygknS36VEP2dUM9f77sVs558HkMoxMhlsd
QjM77yiZ+sRQXBIftUQZOre87Yz4RPwwwDzdzOlH
=sOza
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:52',
			'modified' => '2017-03-04 16:10:52',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a202a929-fcc1-4098-abdb-f0efa9cab506',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAji4s1WDu+MRD/Fn0LkVBVyZnz2Qv/hW0h/vEk4ONr20T
NHxJOll2lqQ+1OWXpgtAd9vMdqwZocpWgTn6vRNFeS2tinSxrXVvjP0EerUSoqDc
6jdYUSevqMbcE0JNawsPP/yOfsgm9F/zZUVaQ8pOwz7TCe+pgycHEPuZc/vLlWot
8o+QB28XQg6Ch9zll4aLApxfuPPXCrQlK1bqKQw6h8B3vL8QyFyawi4UCFU33YuX
ML5SZxWm0WmrjCnyUIglidP/WGf3DAjJ8Z95DylMI42+s/CE3C5UsfF4/zfqG9lC
YHWiqQq0i1fRsG+UK2zAx0YulBgqDDrB+LSGtX2axpevevlT+MhYWlQ+OHnVxaaZ
FkfGxL6y0Ax3BSd9nIPGRepPBkyIfLVeCkpX/bNTRq5PmVLAdRfXo8XfCAqxTVG1
PdQs7d2xxk+EiNAb7J5/mtuv7koGyKtWU/ac5FP/XaY+rsK9GCq3lCQ0CRQFLm/M
yiur1MHbbhLie51E+Rlh11FDmXztipoiEAFgJTEKy6hvrocEYQE3TQ03cgOIY2Yg
Ez6QA3kLJ9gj1Rdswkv9K9tUjVmSk1ZvazFD6v8iUG/uJ7n7FoocE8wyBMaime7E
Zcd1BVuPjA/kM1EhoqRSlJF5VRE3nTDvivDJd1NNabhP4iEXts0a/aZ2Zd7ggoLS
QAFAuW5jkLFSJM5SdZX94eKYZr7q3YCOuTtCMY3St2CoYWEx439U09puY9DRRwRF
CE2ArBsDBhmlXqq7sDQwlM8=
=d+Rb
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a3566c46-f2e3-4ecd-a88d-32a40d1aa64d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAijhaVEOyFdY2Blm229WVc2hIUF/VDrp3bRJdNK6XdeaM
sYKcXDoilatUWnmeGmpOixpN9nr7j1pfjXtWjrIC1bobOrAbBKggzbqFF3NrNCoM
Mzw16GGOxJ6GYEJZbFdyQ6jIHngYYw5q9lb2PT1YiKqvZi97eAfLcKmyRyWaDPPf
lTFJ/pZlfwVYhqF36qjlx157HqM8NCNRTyhBOY0TIuNECd7Vx4W2u2dscZl2YiI0
fTUqSk1ZBKhmhlfbZyWUdH2W6r9WKyNcIoQGYuS/V2hLV4DUP619ikqSesBya+gK
yLUIybH4/0WxFFtP4+Dygd7bHJ528WXOsdBZd4IA1Dl/vTHni4/188UMqvgj6wh+
6SdD3uVLynyMXZ1fbahNRJnST7EXmdRf3581JIXpXNQvH4E1IrjJLPD8UgJZwPWS
l7rAW4EzG3rdrcgeBcRzxVJCH56K+GBtSIOtV/YB7DcqRjHffhWwJqux4JDwgUn6
eXUDwBj6E0i9TwpsJKY/HfQ5rxr5IJuY8mZ30ZzFoystOvO+D1iJGl3ZnLBaxDj9
yY068PKvcIIPI6CslqZcJcRRHd6OYDe3ihDNEolleylPHCszNb2+rSi3ZKQnCFZH
0UMusRFfBnmp5cJgACWI3ppwMuTHrZAM4FijMduKJSnwfQZe5jfstgxsbzDgOX/S
QwG4MMffhDCBO/uEr/gfSghtWGsQsqG6EZ5JyIVwl4pzEI5q2xb4PEW4vkapDeWv
nAn9qoBYwMkgmkq+fcRelPuYewo=
=FXuc
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a3c7e8f7-5308-4a96-ae50-7ae946252435',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Oeg6G+zkYLA1RcHP4d/O+25EDyQ2f0h7rP1y3PUMN+jJ
iuLfoBut2+9agNRq9n/SoVyx5ZcGw13nPlKE13z8Nn8lziQHcrRinK03iwAuKy92
Rm3iFVXlyKlBwRIKSKKneF3eW2BFnTZvAYfZlubMMtb6iT51fwpNwNqFScZwrTFA
+sdMcD9m33gFf/MJ6LL7U3e4SaVNLz07v00rrzYa+NhYjRGGwx/FzPuiivoJfPNi
/Ty2SL4aTSZj/XypsnCZf/4Io5aVuuEhWSjfMKZedLwdgFykIGPwUBtsPcjw3DBj
1QE61cVG96T8+jKnezzaJ/w1aFIGpH3DwHHaKaA37g3Fj/eNsGOzS/zgFGFmqlvC
oJOt3qzm9Plq7spf6GPJHhBnMBIalnT+1U8FLgb0TNc0RXf6zICtKfZPV+Nbk57H
zbilezAGiSgAcqBP5YJq8BKxq6obBrHt5aZi9sUazZgnorOk8/pO9ocyB1uFTTYD
HqKhcOci0gzcLiPOgjfxg20IxGj4yaS2zvGyi3s8voNC5Sy/UK9bpbMdcTyuG9/5
yMa2W3n0ismHjgOXYWlTPLbof4eBs2dEs5dkkYc8+OFNijgJ1AuzftdmXIJS8kw7
1Y/ESym+Wp08Htr93W8bEPn2LuQWVyU+TClm57KwBh+hg0t1LZqRfB6dB2tEJUvS
PgFckqY05PbWJRHIekZh52veVeQIH83Pl6fS3aVqn+yMjyx7kPPGSsaO5Hm4OtNL
tEoxyyXROkyPAk+P0iaL
=BDhs
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a58cafb9-0836-4be4-a2b5-f80cbf19a137',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//TrYw9ggIfsD7l9v4cCLEs+DHGClI+6KFQpiAcDD4uwLb
tdER3AicqPjzbGLzen9IYiHWyBnup07//7SoxZPnMRaYXHarm/ft7gMFg0hdL+Sr
hwPJyRdnCLGr94p+wyP2hZe4IKXAQCkITelCB+3KAlAKJ2ltypYny4/HY+prB23h
rsquZfstRpSbYMUVU9pPwRjknxL2Luw34J0eTMVZhlVsAWLaJLxL9+CDwlHB+Wnt
XyNI8e2G6bsn6y6te4CkIhmWXhIRdM81+ZPin2LbyMn2q+jbbuDaGxa4xmdn+jsq
CIrlsegp11WHNa7TpeklVA6j7i/LvyPOSCbIRxWiQv/MNeQRXYhTr/YsZ893Q7eG
pGKe7yrALaXjjaD7j7bMLa2on61/wk8qwQmVvzxfU0ycq8X19HjNAUSCuFQGwIYG
QAeGS6DJi/P3vUrJC/saAV2kG7mQ7KCNOT+6WAo3fnDdlZ5yptkmTJos1yyBYGyP
n8DQy59vpNZQUmjJoTvPEnETnSbRqdTyoWpT6/zWhRJje/Rso616VTWuaPwE5nFg
nTSnRmyewNF8TWZOp1wjDKDQFdvyaF+sEUm542ggZGFtXvDRhzQ9Z3uLixz2s+UB
hRhvzteJpitHxA3FY5cnhy3T001JCWrDNuRn2TpbTmmVHapRvQL4uzO/ea+W/5jS
SQHIPjBaSZWgmMqbQj8+t4V0i/Kxt+lJfws0rrOd++IC8xDu76E9Yp0VjhctZYqA
61oDq6jXcfkCHtsoHpM8GtppwDRa3nNI86U=
=Q5Qh
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a9d8c17f-e11d-4334-a653-dc03bc1ba26f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9HGTTuuIHThmyC1dppdZgA4l/QMw3rnoAAhI3N1RWCC2p
phZeD7HEvihCMWK0Pe80PDPNAVz3gMTkd0CDyMP8h2DdFRRVWRTYtbGGn22fjC3r
zqASV6k9twrkyl+OAx10f7s0C2vCvXw99eZUyL2ARbw1v79bht8HmHerht1kH5j7
A+XsVppllemYrwBCwPuWCsLQPlljbWveaXICSgCStfXkkgAP6+iy0P0HmZDpErv1
cAktKPWCAYi2bGUGdM9/H/QcQVdkjlY5cY659YKE08T1P3Z1BLjtDpj3uz+/Os4U
7N7XB4LJRatpDtXlSboV15w+OnZS2G7k8ZuHCQsAxdDq+ofVFzzYjKzHckt86FqG
eCPkTtJsFFoSkw4DeAka1PTnFZGuCfOkUEiVj5rbsgCo7JARzEE1+NXWMLvb7vmD
Uwt4A1x0mJSFFjQ+/g+P/RRLld2nhcLNOEvzVXsJ3gWr0J33qQ2o8MF5Eyyaqf4X
H8ueNCURT3L5ZuwY7HHA2tqpk6n03cQMD3Ae3QhBQPGQgL8hjhlkMtHdkxOqU2m4
6i/e5YOCCgFA+x5l/uWgkxbm1XBRCXwImrfKLDgPwFDyuys3m4uTTIPyC88fd8VE
sKhhiTaZXBfBqB7UOQEhFKg8lUOaeXfv+bMOaluMcqsn/RJpg/t2PURG2lK4wgvS
RQH0H0o1bEJf0INWzB/YZsN7QIoTm5VQ3PxAGIkE/EQf6HGix1l+pfSApD4n173S
52nGGb+LmeuhoobiL7nYTqgOrupjPw==
=+hLw
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'abd21aad-dfb3-477d-aa5f-70c3e13dee89',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/U0mPLT31FFjcVEdkSM0Kw2yC4aaPDwPwYpV8U9HSkKT9
w1poajCx9z57bWu+NgJomp7UupJk2WghNOLcWVblZuKTEGDP47rGX+BkryYfjgxN
rr8+Y7GGYh6m8A4azQzvX/6DO/BliNj7bKHkk5pwdLMD18DaRpckRZGLvGW0yIPe
fu80VqwzfJjKgVFN3/TA1hy+Cw7TMUA/CP8KYMjFk7JGbCu4OwVyyZYVQJi+45Xs
6hSn5vqa+B+IK7IRdCVoLzbv1VfZxiSN4IviUr7fvMuhXvlGen0Zdfu8AJh7RuWz
zayCJOuTFtPrqdDla3lYetDkJX6qlskXdSxITxXawtJDAW3ms0M0Pj3NU2Dn9BO8
IGoLwQLgyw9Cd/cux5T5rGZNw/LyHt7kuVgmpSFCKAWtj88ac+wmIofXVssYQeGq
74h4rQ==
=kEoe
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'abea88b7-4aab-4941-a804-45409603cc01',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//VNSen/RQr3sKKypSFwuTHY9WEiA2wrkYJmaOpNx4YYid
UpJxOLGYRifZq+C/zMw2E7MakUAygg5u09FkrQMaQoFi9vzYvp9nqEzozCpqaWJq
ukXSR8hxxgJrNRFBpg1sNg9MOA00lDGDZsOutkctufSWoLbYDLbp7aQoApmOnvxY
R/P038yaFhZ60IVp5Z9XvN8ZHQG1cMJ7RQ6eRd1sDkgiIePRVcwOt1Bw0/qkfCeu
8lxRFyZPAkhWyzEfSR8VZSMPbVWMGh7l68mcTP8/H2m7FGohh/fxFhpwFX2JZa3C
sLsENrFsMakiI3MJIt9LLSp4komylEFJ6XTNEEU7RB4m9dOa0Lc1XicwPtYYOk8T
iu51QwIs9QO1CcmvRYd52O0SeOly8cGAgH2ezz4iSrkUmG9CITZqGentWTGhmzRs
ZSLDIWOfpIcdi9hk1FEDtaglZnUzs6LWTMeSzy5EkbguyLWwh3UmtlvrowMy8/ur
UbeHUbQhWInsxksYjQbYYbZqlIT/gwbI0b+IWhkAGj4rOi+VZqe6/6Bq6LLoJu7q
ROLhz2NFDlHHxX6ZVsmi2BQ09n0yooeDdbUzneYi89YgKiIR7DZ6gD1glYvIa6H4
8X4tkxNYF/m0bpAbu0r8x/yP0xbc8UJA6xIIvsucuoieADfT/2FBusvi4vM0ZVnS
QwHs7hrCqrGawnBi92nU2e6O/IqZtudicW9wW5nQCyUpk5EohB/MGlgLSXRpQ6BD
40+bbbKdo85ZA6HtYnfTa5fXXFM=
=Lb7N
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ad87946d-f0b0-45fe-ac1d-fadbb86251e7',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/UAgRSE36WTeZvNQhJ2kRPyDkxbPUF9Jjkk/qYXN15CuV
sE8GiCOleFHq4BwkrC7G9sPSUKNUDqLcdpYfz8Q2rqWQ72lKODSDHF9f6PnruQ4U
TzUBkRPpE/uy4HuKEDys76/CYbN+wk7RCfQ5KLQgklIocE4qfArlNlj8JqggWq1T
u5h+RFEZrif0XCT2hRgr1xPCQ+7HHCckORLzp1aeWG7ehP7bq3KULo1O982qrJ1d
I+Pfm/57qEBxgh5BlRTZjdBTn6fjiQ23anxW+WjnCoS73mhS7V06EXNB+pRAVNK8
OsysX/OIhCHTNazOGNuKnFfnWUaLauOHFaus7UGtENJAAeMK6OJT4URVlK8aQ5t2
7Y1Z6KLmyh6ga6Bz+sRguLDsUzoL6Hz45C4Eb438rUvahY3FPqJXBIwofgZIDQ8M
dw==
=hAKC
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'afccdcf4-a849-45c9-ac9a-5fe728e50dec',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//cL08VElJKLBikmwPKtij6WhlqMewaEbxwAnS1yKkn2FM
y7mPJo6KR6cyucFawEloVyVdbI2M3yvN2fNKB/l27egL9PJQaIucSLXuWfMBCuUv
9MSOBSZ2Jtlh48L8dS6gVPbkVRcjuH4Tv5D19IRqDSeT7fEByMuYgLbha3pCwLlm
faoP28ec3fE9k6ExG7CRwAaIJ59HCrfw4P4nLo7Hj3NHLwAW1pcWbNRuRkwVnjti
t3IYyBzrnM0StTysvQHeQMU3n6Y0QrIDtyd/G/8c3RNGsVs0P74U+KAzakzJm7JO
kFITS3FGryVRSscEb6ATsXlQ4XPkqaf6NCT/5R/3r+l9XEGX9VJcsRDTn68vtzTD
9RlldA8+IecSa1Zsaw8Qaf+eq1tr53o83fU7X4lxifLuCRSz2JzAr7dwVMGUve+x
1N9R+Pv/yggR3lMpu0f8V17v/UiirdIwZ9HP1i7iNjahCG5BcosIs+RVKGH/vcsY
ahrwAwkd3iB/jcjITQxlLOpNAGrIpoKpG5m+d1L5GR+UFZ4PgU3gmq7vuWXMGpwg
LHb663HsvowT85iCGwnP6qNBBMuam/9id5GHhytjSNsWhlAvSUOOfTCKLOnHZ/nt
RLsQJflwQakdr8T+u8Ln8XiWyfJtCrQ5RCUQfqP9PxvP20u+UzyT3Sw3VwYFeGrS
QAFewAgSTtAECu6Wg0bUS9V776D2tX1FqS9ZBf3g+hEkZA3mmcJmQg6dZ9ngoCYK
N7IE3ZWm5TV8nzlOTuVP8G0=
=odcB
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b212db40-c627-4ad6-af8b-669a3755b807',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//bDgz7l9AJZ+ZRUNdx+hsZA0HolHIwgo6rbYDbzbVJtyM
ulRX11tVlGV8SrKpYcuG0KwD5BGgFEcRHgJGMSpwBWy0szyLc/AkE6jmlxf8nYJo
++qKrPM+enfJj9NWY1Ss7HAjxAVjmoo7+VsPY1gkL/SjOj+6bsJiIzYXXz8Q8T7O
D0Esa0tYeKB96+F82orrD8YU6SKYKindnnorNDiebKcr8tHKCk8ByQgNFHZOTzSC
Jb2UsaEBav1U5dIeNttau/xyE8M2tCtjXWgcr4+yzgC+9E/4EyuGQIedt9oXtFQn
aBtB8eeIWW2myeSZksXlGPv7l5wcfYAUF1E1XX2UeQKdeSEAb5ab3gXEg7bG+RvT
ctE/YTH+0s26+SAnrzmRwoFyTZrWK32E8foZK9neAC3l1lpUmEBvSwBvD0i61auT
ziaGmZxUk9l7vg+VfG1GOy4/KhZjxLd7BrPw/XT8UZETQgpgnFGV9SkiBEjVabyR
v5HQsKwQleLYGBSKX25NPi+2BLeG0IE6xucwp45KsfhrEPQtw4NAc/nNxMO0vAlD
+z801ll9XH5HnRKcSIvdev5GkfQ5yki52b+M4gmDh+sgxjV9b3oXInbOon9wsZS8
Ks3jPP2H47ltY0H8BG6zwBoiqJvNSeMLbn26ZzK739Elx9nabst4K240czXZvSLS
QwHEk/c8fALGiiVh33xXmF9Ni9JMixgW4bMn1X7CE8pZH8+lQDxvBFLwpJz3Zc1U
RxC4gavjNDAvySMCVAVYwB5OgY0=
=mjBz
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b2b18f19-a8f1-416a-ad1d-c2c222986a71',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+MwQcph+YCwc2fallMFUGNJEf/csZ0a6eIYIbjYlN8SE0
3f1eZ8rhHDJfD/5U0HfuAwy77aFDSQ3s7N8zhoM+N7XPPypDkaq3CJGwIKaJB2xB
5cufIUAau2nmHba2w7DREehCai64zlkbEp7glyZoZRu3oNjK/fsdfjNl9xKnYjhz
gzQ99M80wB8LtvXM9YKa9JmD5Q5RHXwy8EvaqZLkW8VKmrBk9XgEhdb2OwtLim96
HJ2I2j03dMZvBnZfVocKNrnNYoAt+uZXVtFWz5QoQzOg0dBi1jpNmEkVe5u/7qrg
9U157WU5P4A037huTVodreL9S7PkNM04utarYD02usRkNrj2Kz0VmIHeURcM3SVp
pPlyAuSw/mET73szPEl7nSXWv4bXwmOmBQocrrAhotQg+p7BB8KLLlMOQAppav5h
LNcYdIS+bO+UKehHzbBumxtGe6pGRs7BGEQFj0+KlWYh+LIJtNF7bOfzctW0C/N/
pzbIe/k1RUFzspl65jR9MM/bCebUO5Mzz7hM4pYa9kaqtX86296Nfp6ph5fdOOnX
lmsCZCsfqnb5b4W9SdOwzHnPklU5gnFinJnRe5OIe4EZLrBqbPtPXdvQrSMEbXK2
7s3c/4EuRgIIZ3gKc1XlUySJuVJwfa/9eZ5goGCa6MJb5QgEuIlBul7EDgfJgd/S
QQFxr50yk6R09pbm2AKsZZTMYJqFWc2/mtOfouK6pSCG9Ih2nU56z8CKnyZURPR7
CX47SlagtVhVxsLbBv68xhyG
=IKps
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b3239029-ca50-4ded-a109-ec3f0ac23b06',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9F0iw1ZLFkpyAZ8Btlpud6HffEIcnMIIs46S8t6Ilw/Kr
TjyDHe+zhSiVLH035/6HwOx9U5eGfPr5BYnEMTZEO0sANYrP3JOcDyUyoQXD9nK0
gJC72uDmXsAXQ1Dn7+SUlKIPCoGAy1Kz8QpIBCCgdgraglBfF04Hj/zBPwuEaeN3
/Od6+koTrQu7ZxGWnDMPZfIeAIK3cYXMoup0/5Tq+oeyFOqFqIJlndWmLUJsB7J2
zuBihnhob+zbw2Br1v3iWrdpvbeHph+r+U2Pnz0UHgldZEBugrwRXQlev88WCTiY
IWUQsbsg2+oynN7D+0Lrrr5eZ4aMfn0Pc/kjjngYToXvHDIpAPUAmDmQ3Dm0WhBp
FGtREFO7H17NK6/rmJQ6v6H0eD8ZWaJ0tgWd11d4D8BbGrnd/BEy6OiNfWP5a6Vt
TSdGZrXRvSNd5Zqa9t06NTkWieJ9duJvwaAaW2O5ym20dR5DySNY1jupCM7PN1MS
X1xCrOHFo8C4E+7oMk4PtTmyaVNrJEfAqLRclqaL0AX/LPPzsp8mReaisZRwpF46
8i98zozbNZ3yDbVjVScGojAONlGcmmfWkSMPBLk05KEXbjcAz3/nY9HwckbAwS+A
kwKJ+Xu/1axVieeS/KToqm3IVaCebi8iQ91jM+bpZuFVt6KCAU/yOPxXd/SaksLS
QAHMQ7aEAYoPnftzIoSltoKXC7mK3ra8E0i38uCNPn/8iSi8ZMNd07iey9hLVOqK
z7vRwT/ZPFtTaFxHvTVCP1M=
=OGNQ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b352e927-e4cd-4d83-a58a-79fa89cc9b2b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAyHfF6yg7Jfrn21n0W3tPSy3+tfhAaddQcxwT4tojEITJ
fIeF+YdW8IWVVYdUovMPAc18y44zagAD3v0flIyiPJNHzC1+m90Y9KnkbN0DPJhw
6yuwTC2BOJdpptSsS6dDCYhAnY0pOpKxvhMT2jMLH7Cs7U9+9r6DMl4g3PZ1F+2g
H3jYrktBNoILryPWDd8fKN03Tu3nWaZuzNZIf/npbYkV3go0vmH+74ZFLQTIZRjP
OrelkVSELs/yc6Q/cetKx8ibRjqAuvm5BfdkA+xCQ45DZvbf9KtEtdcp6oBdvAqX
I+w7XpRr22UQVVwSQBloKpgP6/9emqxMWm419SAcynHTQ6L90Hwr4Y8nK8MCuXDE
DSFHEVUvb2FtNmY2owfXa7fbq2mc0212XUwa6ETTPSZoiC9hizPHAHMkkOCasU7n
vGOW8bJfG72vsIbcDu0cBv5xZ7M8CQonqiq2OT8pvzmOfCS0J8yRKQiYI4AYoYym
T9V/sTkYv5EobXkSJ+HU2ZVnk91eAowG/VxvWpJJAA3nax2touVO4D6Hf5GlaciA
CaDRGorazUZT3WIbJEzqRF1aGwUd0OwPSQ7FqGo3x8OuPtrpba/uYTejnOBSPL8w
gLV1xSbuH3JHvGTW2iQTjDFpkGZeEK3Iab1ncSO4xSVQLVqANcliaj6QPoyPldfS
QQG/hu+ThWW5yjNI+2nBB2k93gjJujSXTn+QSPVj0M94Ut/oKO+D/fhG2jBbRu3G
PgzSdm6PxX7NZ/6Pe1S2c9br
=IjIt
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b4f6be3c-5dad-47b1-a353-34ba61ac2a11',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//eplWv6KLCAGLRJloTbbJR3lbpJGWi0qNVRY9ajcBLTCx
Yq0B+afKmIW+hwNoRZarQFBXKMASg9tJqH28Jqtmxkpv45jzGXuENtOW2VIeS3qz
IQYiiS3IiY5NjG2Y1somVlI827z4RIVbWHutUnq3R17+gvaZPb0RR7Y/JOtv97qJ
iJ8fVGf9To118xoqNe2ral9dzwzbeFQG/x8u4FDcmrDlorlAVxA1hUHgUMTpaceg
curYIWWyItQufmShVCYHUi1z+IpTD49QzLJcq6B8Vw9JpOki0obfTKSlrxtyDIjR
uEW5ZRaDETlFExLWsbA2YodzPbeJy9DAldDP67lwMIqm7FHhKaLRkgJq7qlK5kY0
lXBDXm3ocsAn5Lhga378+Fmz9DZ/5rHpddUDY49Vuh15iF62uztcIZpF8zdgXoAN
9DQmfkxTlOqYOnKzZukl3cCiyd89fnoUViVQPyG2BTHjxYCljhtGzjNIOheo8Oaq
m9J5hFb+d+o0jP1FzuaqcN8dyor9rNBwKPCQWclpalcMb8WuRPWbeLFohbhYczHt
hJAbJbViiZWFjxDBNoKwG7hX4rq2b8AsPShgnc5xSlPZsX7r2eTmJQ1b8jVhxBpD
K3jX4tjObW2hjfJ4T3M6sXJDaXFraeOEub9WELhH21YYZ+nTVFJuEpa4W4BSclrS
RAH7Xtgd9v6Z6iFtD7w9HfhdqKsrMJAkbWrmdxS0CcFgz3u/XNwct6Htq14yC51H
5bxMqw3KrSIb/h4FtKHrL/BYzwZD
=HxUF
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b9e25983-9b23-4717-ab5d-685cbfd00fea',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/f34KFUInmyx/sITG/4oZbEpv5mG0CGdr7dL/aiZRs7V9
SYrzskSaDAc6N8FNwz860DNvEK4EnqyEIoMEsFJyd04/7gyrmjTRzZPWzn9oZ3go
xv9bVzE/r15fgBz5mjk2odI7nLj5o3J1NmGlEjJcWbZEtCULh96RozLaNre80QUt
mfm1cMFnYGsQFYYaEfzfQYQnoAn8U5mZ9vP8lwuUKdFo7qVnf7rZDsA+3FGFOMAi
nVghvEluHPmamXqCvD0iGN7EdKLNa9tAYzc8GZjacs7xWWscJNT96xuAWn0CuP4w
sRg3dxNV0ypV7VpBB2o93aAMR+jTbo9g3rr0fCnQttJAAdiXjmffOIOkYiclchH2
HfPjLxVW7cRmCkDIVFGlQVM7X+TDZr7vwbCu+vKU1M/6x4AbMtMCFEajfxd7kawh
Mg==
=tCCR
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bba531a6-77d6-4f11-a0ad-3c44b53c46bc',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//SgoVOjUxRet8J7lNCBv39kkfsWFdCmGeOXHvkxynlGIC
l3q+pydBzfdM7vapkQqVHp8GMq2aTJXeQQ7qujQWIyvBomEnGB0Ae+0vAqugQMwv
oZLeXRVLQRd7at26d0QPd/MmtK0K02vUUgpGQFofG7yp10Mo1RI7B12rV4FUjDmp
wZuZliGShdGBOy/sLqkRTgaEfBe9NK+uc2TPmeqWAMO1KppqHs1rWHMbvKRomdoZ
UcA6TSXdH7u0phg+lGNsUWOIa37y8QqTH0g81gw8eC3SqHjBtuCjrSERTr/QXbhI
2/VpmPIX0RrgXmYjU+coFv75t9O4wtZMxUYDxueCdmCfWwOmS3vNdFCGIe3sq0ez
MJjgOz4t+y5SduWhhqoajX64DKeXFt4mxzkLZTab2xdS/3NtFwjlEPrYakfw9i2f
/elSMKDYU+XSBoGNDPfjOYjeNPmhqSe7AQ5t4w+oewnjoS2iOGY0kLML+USemz1i
75OTmkAfvaSqRULvjaBP9qaxOskmoJL7sOkcukt50YfZ+dtVuWs4LnGEXoKucKH3
+B1TdzMVi/evXrSXh+eFTW1equhJNAw39jvjUFG467sM7k6LyaqiVI9FdEFMe9Zq
7zAltF237PXOU4pMwq5+5q67IGw4ouwo/1e9fjhVx/pKCwbesmV2JAbSS31bYFXS
PwGh38aPEsbIi8YPeboW3m58UctqziUfA3BhOHAeqm+UzFV7gy4mcIoEx3RmvzaT
hnoZ4jiA+0jv+CtRNl4ZLQ==
=PxWJ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bc8e6d79-9d2c-43b5-a74e-f9466fcb13cf',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//ZlwX0ZmuKD8NaldR9j8Bif04IgeAJb1iu1l6ORQhp1+4
edP8xkW3tJjjmqjlj2E6pbWtX4On1VffvRBSRIck74YS1VyWm/b/JFYpyVnSlfZU
NGDY9KdjVB0ehA7t2NERoqFu2mI8GCtKPpydshyMMY6p5UfPii1ef3hdYFbZgmFU
OTDQYZZeWvDRNVEpIXBgw09JGrEOMSAdOiTojSSiLHQ2ocyOU1182nUvC9avV+Ii
Cs1B3zzrpuN//ty7rzmTCKy3ybgx7jBJw4wIF/iC6ACIAxL6IF5ABYOKQzqO9pGZ
1wQPB3xiS9ildW4X1AItOD0T8Ey4GMjKLYFIqxk+D5gyE42nWOW/08bPLfVECaKI
yq1k91wtUxZ6dFbQcS/qr6q/s7g6PX06vNprAmFasQ36sECTMd2Q/hc+kFhpyMtp
3BaNyKLZrvIxq2+j/OZlyyZ/2SGKk4qB0J3i+n+FXr23OP0earZkdkTthAk62O42
6U5R1zcCizGzOxox9492yY/SkJ0eMMkZaiLmBnWgCD4FzMg6pdfWtv5XcoAplPJd
XxuyGlXnAkMtDFIEBntNJ6E/EKxJd9+us0vIT8pUOQ1wah+nhOBJdqkSZdAnxfhj
5x3GuxbCf2wtjlyGgK4rrGpl598ri3GSNHZcFdl/eN9isLOUqKJQvCsjusMOTxHS
PwFWOSUDUAIW+hbNwBQAgXQVcUd55aV4iDtpfNqqe1YObzPsH/R7+M5D58ttHn8X
pGkDZNVNqUj7W7MN6+n0Iw==
=53qg
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'be843e6a-9eea-40bb-a045-a6ca84500e9d',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/YVKe13/vc15rltXQFXJJpI6MOTWhPrimFfrTbMV90Vvo
295KVWynWtZs2DjoaXFmklX3et57a3upMH1YlgCvWmIcDLX3nlRLUg+msLtpJiyI
FYLnddEXpbp7J1OPdOMy2WgwM8SScNNsRWeOoufAp9+zxUfF71GGMyiLbLdxE+cL
yqlv3A+Zk3uHGSxpL3REHeWzSN/L5BrtufEaR582FzjhC+bP2cZKaZcLky4WBtMf
kKy1TBbhgKNo+F8N2lqNZ0+YBQHQCIx0Wys/D9RsC3Ua/tkNHFP1ik0IKQg9ivan
1MGQCZgLkeofD4wGCV/DHcElJ4YJvHrdhoT0zsafedJEAeTz7F+ak9ogxbXbglyY
w8dvbr+k4oP8F9dJ8OfbT8PVK6unX0mEVRiy/vOZSCSdCtLmfe1Mg+7LHbWx/3lg
O1ZNSqA=
=W6Rx
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bfae9ba8-7699-4c66-a05f-73b9efec0e48',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAlHHVpuuR7dKi2fXJbAkliMs9ikAyO+CZtRh8vsbzdbcf
uorVebsGdDXRPPayoqHDKPZDqSM5kWSXnDy6591DcpZUVGvzpIXz1Za2O4Zu187u
rlWokTEPofLsAcCOWM3dyXUwJ4nCqw4TGgO0p3hKtKeWIBDqbMKlbxA+lyOVJ2KW
Xq+2xZW+5yI88ohGymC9I+uSn/TG98enDP0+CRCFRmHRr7s8M0jcM3MrN8zB7vJa
1uN05c+bD8AeqIf5+Re2FMwPKh8f/BfbPklLvEVSCi2MmhruRMvoyemZ6fUSe8PX
QYlIWwe4NIslZpdKCFGStSjOOPWxqCjlT5GeAG4ZlqkCaM4LFYxoNo+FpHL2iJvK
vBuKe7BE5vmr0dM4Io3ujMxqOpm5e1iwidhzhmbvKMCrMs8GPjwepDQAV0kNoOZn
4vf+gOKS5z++G6cUAyehtl3Fq7Yv7MxtmwYS88LtZt3hg8kBf9bmOPtRWGWb0ndn
J018VUfsUug+nJgf19mxbZSwlpWj4F0YRGMT73KDotAXMjp5RJX1+9+xfO4swKNQ
CRsiY+Z6xYFsCTy1EnVJBlZqHz+Rq5SGAAOgIeOMp3kkWmEcUzJN2yJctZZRLoWG
/C+cqd11JQGm7auq0HD9q0p9roDuOkmtAWdInuMGfKB2/YTP6rEPfFLkjJwNtL/S
PwGelIchCMnGuCGzLyCvJKcEOvKvoQtDugJi83/vjEU9WRR6aGpm/VUkZ1SuJ/Xk
yy+/iiG+ACj+Xbb6Xnb3ng==
=0Zv2
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c2bdf168-2f50-49a3-ad4d-3ba3e3c62f1b',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+Kswfgu1h0YXcgGXJTOokttl1eyN2G8Ciq3NYdD1tliny
v/ajEqSHPSZpSGqVLndiZUyKlJNtHOUwtBudYCuxIJXhCHOGk7VCC8li6eeYSSde
qcV/dGXKSbsrJiWxol7hxGHXTS2NiX2igTeZTCxYCN4e2Fe1tBCQPafcJoRcbO0l
P8ODwdDpX/n6kzaHOYpFFP9t+cmoDj4W9dOUxwmveX+a+2GdHQprAT0j+CaE5dWB
wZPhB9NBe3J41z4y19XeC2syqlWktUZinyqTsUocsoBANjEeZIxs5u/GZpnMUiD8
hPt+N2kTCHa7XGfoza8cHJM2Y6J9oX++XTYuYQWrg9JEAZ61XQbj7IhXR4xkD2O3
JGiDw3HYvKtvO2+OYXfqkNR0TGzUC7CexHEgrV7sgtxn+Df7WyzAwrKFPRCvEgYF
+ldlaNU=
=SkOw
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c3202cee-93d8-4184-a643-814bfc14008a',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+J8vw+AVLbD3w5nIxGsfay0VpgHYOcfo7EoHpBbHi5WuD
qavzYqmFEK7fsEo7fzriRnRNUGsjr+KIFfn/V2wft72X+/BHhG3IdQstsCJJhlKC
ytX4SnoIk8MuS1+45wy6OPsm/R+UxjTKK4fudICDPg6xA7Y3KvW4qnCna3QQ10/v
cFBe4wju1hpPcrX0owLsAavT7CLKDVTm4BEdFdAx6BnmbTImeYio0VIT8fcZq1cS
qfEQFqppSTbWUm7AA2y/3hxRAnxhruBxzh2ky5eKfNwVNry7ExVrkI+2LQqp05s7
5am1splZnbTuM7VPj8UsZjLR927XFlQyZYN7xagA2vAHAvL36yCw621fEIv4yVaq
Mq7HAR6cJJ692lsReRIwMATQUUnYZR2MpXvUydew56yDOhuUpir/jaGlTDO+4yGs
zgu3FooOrQjINZ2a0xiBc7omaYTrZL6w60xyzZDlZqOUza7Zao4VFgQDYY5EntEN
2SAX6W6yoPJtWS/qTYDQ9nxa3HCOs1lQ53LbWAFgJsstg9snBf4Kd6jKHjJ0Cg6/
1MWBck/+M3fMSfX7+qbQpFNtgPaSjV+TWwiB1h99/yRGg7z9xqGNHvMR4DnmwwoB
LGBnrPbStovEVAy/oAFZiJ9lwMSyd/p2fN6RCOPSbGpAndi1qzpEEyPaSAaeKBXS
TQFe8uJdrEGyZjpogVNOddKM2jF1sWw7GyW5p4FBVjjjaqo5QDtj2WzHtucphvJF
QKenoQtJe+T98c43+6CzPuVD5laE6nQkdS0oQxaQ
=v2j7
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:52',
			'modified' => '2017-03-04 16:10:52',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c3a11d8c-2eaa-4c00-a3fc-cd9c10d32004',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAizmgha5NAepw9EmpDW2OrM8nJsU8IZTNCVXBZBOEiS/h
rGHo9XkJJBTjx64h65CZyZGaU9TYu4eDTiIxQK/yP3ZcNvS1UIplbzP7V86SadSW
1YMGSYLNolTwmn4Le524noSoqtBQkoStx0Okboh5KWHaePiPlRJhhycW5TVCMbrT
jHVLmkGVu9gi9ksiv2dQVNfI+Wv4uLaRMtlfTe+/I08OCRrs6F+0pAVTJvyC75NK
ie9uFOlIoTolMIY/ExOHdhRM8GAMJEv9lZdG79duDVHsUlqF/v+xMfasUnGic9BF
9fup2IFJ2zJFhWl/xNL7DqInbQ/94NEY2lZNMaQlHq0RxVSdWEuqFmo5pFuc/sTZ
2kR4LO0HPxvADpXpvCi5w+OxXR8A0TI7GNZqxZcp6YB6mEROnr5DzbBSa6/P2qsc
kB9rkMYOLxtNTDsaELEOSBY6sSbqx2OU1StY1a+HJS4KwbV4I4/g6UJzgGw/Ovm3
QeMTsoIU4QShgendSR3z3D/uLFjRQMBLg8EtAhCxn/nHkneCnI1KIRN8dXC/0r7S
Z8gBuZmpjOLl7uEYO/snxUT6fsoNR/C9B2k7uQWyaCa4AshvswFseA5SkjM1LXIS
gdta1Ll4uztpeVlftcaO0VkVqdndP6NYbe+MobL5qBw9O3DVG1VhBHo1bs35JmrS
PwHNbJ/PsCwWtb7564l5+txSrKiBy8CGQpGdNgvw7mapEeIiNX8b3uvu26GG2RV7
vc9CBTz1IuOY2EocMufZkA==
=BwEm
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c44d5b57-db52-41cb-a893-b83ee6e3644b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//ayUYghafXs+wQwzihwX+MC7IaRyuXh6yxNbqAzSnd09F
m8qXjJ1pQ50IHwqjrYDVY8z62Et7ejfLvJGAJz+fJX4xNa69ynVjoeEqXkEaNiE8
Lf2v+I60u3FFeO/BYC2Oc9rMDH+bniQ0d0SGiw46cZyKm9i2OzftPwdNUBh2zaGV
DoVDsMNHZUOncpm1gcVfeRa/+DB0/uipSYASO9+n3AP/CgwG+/f/NMjDEy+5EXUY
PwKdOo6MbAqOQKDZZbHc0rZUf9eqD9spNYaXhMiECxva8SRrmKhWI/WDKrM7/Cgt
GFCkRJ5VeAK+taSCvf4UTOArZrpVzI3rU6JCJOd5o7QfKa929Y3GMOSOOG6HvfaL
GlG9UkVRDTmwIZNZD++2MmldEWKR5+SiGN4AR9gXpG3csj3lAQAuVyw60CnNZwXw
qJGxNC6OG/oXcrVkFxm5WKcQMN0v8s07xh9CfZf1TqW+fciSb2LlxNOC4w4tgZGe
HkciLNrzjTaCHgKuThkO8AnbbHgNDH+lEpAN73eOABNlOW1j4oPyvE8cXM2rAcDw
OfBQbUe4eKAh946jx/ehdMgDCAg2iKlnzk52+9dfzraFE9INSvs2jZtOXOLcNuqX
zaxJGOn/g9caucQ7+AnjwZvP7MMD1Ollj7rpUTOxF2yzfmVT3CfmkGveWnQHiHXS
UgHpcqv3cHtckekqdDbdmY1HIJJJxulMTgXYAISfavdS6uHcyixSo90ovZXswka+
SNr4fGcoh1gc/1EXwRPqCXnDgM0HIv8tOGHNeJqygvS3r2s=
=UUUF
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c52e98e7-39bb-4f03-a0e6-53585f6959f1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvN4NUDTwIMYSQmoZ4poLYE+9s/07QPxV1LnwJbFvrRZS
7s49zPdqReDvhYnENBUCjmxs/AthUY3NOThaNca0siI2YPLqgBvqSWCtUQ4ttTA7
vHmYO4x3Tmz3h//VYkzySOsUE4UFiZ/pYgwQ/4d7L+zPUO1YKNNPuiiEpJmIRLsc
22EMXX7Qt0j8Z73mvLysXMjqIRNLr/ZcrVTK4JWZLKqNEIl4Bgy2ug0xxlw1dlN3
NR53VEPo6/8LHOJ4ywNOWQJi0a5ax545jfKX1Fdg3O0K47EWWLywj+hDd86CbHIa
T3vddmpaj/L2lBCZbUkV/fK+hmaDgc8Vv9mfswtvh12nG/1/QG2nLIoE2lBPJ+ks
jyiDWK852d1k5nPKzzt42yIlapzwWD1KEcO9KpbvZZw4+wzfsVsyYrXqq/XKmgsz
kaWlR5T+3ry4mnxQObYdqPqLupFgNn4swbWT49yhPaQEW5bYLeDaib0M4oy4fGTz
qcuIRaUVEBGsGgaoUTAbxzKwmG8hcssR21zoWV1spQA6+XlOEtUuzN4uMMsLwhXt
oFpNPeO/IyRAaV8bLcky61Kt9ZEH0dgEIK7qPyh07oC23yeVmek3wxTJM7giaWCT
DzX1v47A61+KKJvlz9Xa/wq0T6u8ecEEhW9cev1HnfI83uECeUw4MgTSGyM9vZjS
PgHvtZEVnvmJ0N+bA2M9F7yMJ7woe4zTHWXk2uuyW/5yyJ9q74gEa3x56Rc2ZE7n
+CzSUesIHLfnyXybChGJ
=CCrz
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c57b4c53-fb12-49f2-a9c5-201bcd113467',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//YvwDGVj2jaJFT4wjuRb5/fVCHEwOQxp5wCN5aOJP/vHN
xdgGlnjz7AH/la8Bex6EqcHBeMF7Axr1ngK8hytpPtyi+ETDD8q04b+2Ztd1WUqf
S0wEgig0kpkj381ODieeJ61DEUE/lGkNNoIKtX/SXXupDM9bou5NxamJf6vSrSTf
mgJ+qAfb8jmgzY6o5aQjcKLXlrBzSUpehvIIdZafFWLcEACTtqDkovQSJxLucfwA
lQh9ypRD43/61RtOqSQ+NkoTV8SI8+xjIY4mcl8lxfVSw2pdTvzR9/m57vEg3Qp6
CSQoFyEVc1Ab054ZSpvupDmXLKG2xcdPoqB68afIE+Y5Jc9sm95V4ydCBZCK0O/F
hI9JsRRFBREHf9HwuR2o9cfovf9MQY1k6v0BLUYdiwC64qjM9XFXspgt5FeGbkPg
K+Z/EtkF3TvS7hOIHos/zuVdJ8dxWtYypffJfomCoLVT3Vy7kmI7NSAMvHPRz49A
Bw6Ksi2gJ6ZZpXmoOwI1HjOGFCY2lpULhp8C1vc4ZZZzAetIL1n78krcWxjYpBCJ
2+FFF+LdTlwSZFZkVv5WK+ieTkCOcYuXTHPdJ/n4Txv1ACq1acG6B7oZAYQK3/LJ
4y+cagss96QdeZnAsU4yYPEuyXUrmkIZoqVFi+2On1LcQgvaYktctqt4OJWgjELS
QQGTgsyJNGXf002miAZFiyJfcn/AoqiLswFiXl/REqlljLqXhp6Z0DN4zMfzQq1c
lq7GzXGqJoAnNxH5+dhwF/LP
=zLyM
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:52',
			'modified' => '2017-03-04 16:10:52',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c5b75bdd-18c4-47a5-aab5-f526bd9d3115',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAm6ilLhxrYCK0SN0XJcyujkdiB5o/gNIutaOBpCLyJZLy
0uKg9ZaXdEVaTqOQWYK2jSP4wOK/+U1ZkV9aCgsOFI38D7figS8I5h4Psxem8k1I
AMtPoRoVMP5lXqTJ+tfpTVUrrvFZ5UMhqgDInLXB9THWSQ7CB7QS7OoDZh4COODR
bI2HXrnCQlEUolyv1WG6vv9dQJ1HaCrp3fVmVRnvuT3dLku7BN9FbZCEJKmzEqfw
EBWBaPRSpDPpiVW6s+kqiI7FnuB6tsnHsNoEq1UAoGaCG6QcuTGZvXC+m5ToU7oV
zXQBJtC9rd2fhsiVqhRpnk23W4W2566lPt695feTyQGPRQRf5KMiTZp4jvAsGEoR
musSGvHgkLrYlTiCwckJzOFz6Z61TbGTmm8qg5k1k4tyoCxVGNeD0aUR+9xq/C3o
oh31BXLneIoXbyXBIoEGdzq3M9n4i0vGxOCr7qLHLHHS6o43/XS6mEgE9cO9ytLw
6OKyo6CmcQHBxcr5Zh+zIWUbQhMHCnrXRyO9fIPxIbWaH08ltxS29I8N6U0OoHV6
M9IAIl43bFgBOj/2Hjxybrj5G3IstoCrVyYt7CjCziCqHpZC+e2PZdbJSrNihTwM
bWoTXA7lBJPhpYWOyp/rLBrRWaxN1TcfQFDySKEz7BI3LDAS3MXHIf5OgCI+z0vS
QAEC8T2RjnqYf4kvg5THonMNr833bG1eSL/RctwUz8r4VXFj6dNTkwb6GyUXTUnT
txb38r/fj/o/bvI14IJLEdo=
=eGjI
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c71b79fe-c339-415b-ae74-3ff18980759f',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/Q6qMOjgB3I9eN1niEGMzepVJMgg7IGgJkpzBb3EsoA8l
K2s1mfUfr3HdbvWCgoornj/T2yjdiLI+6s/2MvmHstd9VofRkQItFC4vxUgjcOzo
fxOAp2MoVD0OM5XgyxVH43JalRO/YlGpJeuuAU3yidRMnb5stvuvr4Jj6t01bL/a
fDspaEUsioipUyR6jkGZYHP9JDAvMIdMCRTz8y4nP7gaJ0uzg0NkPKgoq8LN+XXY
D25sJ+q6ZUhPyookyT0k1L8+UYZI7byJ+JS05QDlmA5ZMU8tvctcFUccKN1dv4oF
7+0FnNbugx39+7leqXLT8ope9FN4s1HD2vhys/KtddI+AbvuXHuVejQSQvi3KhUN
eiS70WPOH3LrDXIzymVAAqyX/jljdoHcN2nrC0aH3heB2hl4UcqZMD2g9UxOFy0=
=ePwP
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c88c35bd-d70a-4dc7-a62c-96d118110434',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/RlMbpjj50FSXG94KrMa6tLbE0LBL0RYURKcFimZ4Qc6a
+6gxGjl5BTyeAQ8BF94CgRlhQR0zW/JA6xVK9P9XMePP/Xw9L1JUDue88vDm65ND
oHw9GDaFX7cBU9l0FtJ8bxxar/jKaYrEYJEHtAkM/Q8xvwYa0v79Ej2R6Ek6Ab8X
40nQNGFFsF2asZ2J2jKeMbv08IVGgWZ3JkzCnkdOoMhH2d/TQZtzE30FAo17Db45
9pKVwm+Mrl9ytSkD3RRa5YtLYa0M7QrYYa7KaN4DVREVziLz4Ht+Oq1jETxs8Ms3
HLOCQ1ffSB4DF0RhTIbhpf/Ntby/eEi6WxtEVnVDcdJBAbz6SMMb4ahypIRu9CV1
Nuj6i+6yTfYaJ3vJbYk0IGHu+ZrT+vRJzGCoLL/FYrr6piLruINeBJGbjU7ZYT2A
Isw=
=08Be
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c96a712f-bc71-4d33-ae74-982cde1fe27c',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/+Jxuu+S6R8AvbeM9eddkOkxmKlSjWRhi82dkOYabnrRVf
H7iqClohcjBvBE3mtNaP78vnQ1gbP8TfJxtttWM7dMc4TWr/58S/8sAZJeEhNW95
nFvwvLmAEfHVjNsvgXnq8jvp5/+Epz02a3eZLTHVF9Wc6G4mr5hlmxG9tOLjfphL
K5oefKoKKPRfjnqGJokgGXy3tMnCcjL2UIUcNEM1VfAlp6YLSl+M/ht1YuEGUdJb
/FnnnIBTNGRVNmowxUExnsA7bVYa2lL7l3KCjXnkkxZMW1alx37Bgh7wUbW3zUYO
Ld/c9bKSuIhoZLvIfkhLtSOGEsMmCsD/sY3cUC3gBQNKdu2NSfxOuY7b1/jnv8Pj
SgvtuqSke4zAghvwEcgPHeU7xY36mWLG+VdtV6Sxhcbkft/Rjy/EFKySfX14GOpT
3agYb+IwH30Ul/jEE2ayRQQoq1/7sEnps1NEnxai5SbXpbtk15II8wezMdJisUDc
pe5GjHVML58diBvJwvTcj7IWekpt9vgh+h4BQvMERe5YlERj5gpX0cbTzu0ZVZri
uWSAMM5mWaxwsLCftXOn0GfQKRMv1SfwAJ8uzUVSIqA2zCzqb2FcHyODflX9TNeb
p50HQ4jWqSPzS00la3PVZ5ilKhnV5Bi+pFfZd+hD4yXCYCJLOZADXNuENTei4+XS
QwHGIshnUMqFwu4pL8KIXe/iljJsSRq1JsZMc6W40ZIjOEe/f/MHOw/Cv1isbmnU
E2ucfq4zITKDvPoQJ7cVEskMpdI=
=ihIX
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ca4adeea-277b-4663-a523-83f9e94298cb',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAm/vJZcaEK21KF1TB1reQNSN15xlIwakfRH84MPEOJ+5d
laTz57awEhAzoJ2Y0MFlYspHRHeJAZq4tKig4p2Ktsj5YfdFCvcEJUYPh+48ewMB
y1bRVmUsUbIeaPguyCrYwRgFfYbBG045cnFS8CF8G9kjTl/FRdSk0rFfHLizQuMh
2zczTOOqrq/+qCEivy4Wzzi9AIilR+nUU/LoUEK0PYHAgh2y1iXgrGHnilRzCW+Q
0HQpNuqzvm1XQ4pIWjIMKz3X/9JLIw4SPvdIeK/W2lx3Pt3H4bu4XqniaJEpa+h5
K2ll7IkEJmbqaquCXHwpSmYOV50NnjGMlLfSxWyT76ZFRUUddv7PcOr36mjb5aOU
VZRbUQyICRT6KH2oxXpc71rtm4RgONuzwvheolzxxxfHqVoOsHhy16fk/wSbdUJi
C6ZDJFC3Ad0jQtLwixp93hXmySTHM+8LbxvL8Y3VteH3k/XUrRUBqH/L2CAe3NAr
bLivZUVFWVTTC4yKG30DtJSUgJA3UhkQ0l85pNoRk0bqqh+pJDJEJYJTHeqmcweI
Kig/9RsJdO2EcmiMS1kXMQpPTdlVzUZbWDpK+2HXo88y+RQgSp+omJRCrWD2POBe
vl9Kl892/3K+QHRnNJjMdm21lESSmc1fG7Agssgj1wJ27AiPSnDDkiq9auedEBzS
PwGj4aunJ/TTHJQ8TQcerZlSx+xmgRQPYPVcQJsCf+KykHQbr260QQ7e2vXQ0BHA
obfCgKHVjUgHBdiIu87v4A==
=b7Jv
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cc1f2a8f-574f-404a-a54a-70d511aab430',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/aKDHHQlmg48oKVlviXNXm0t7mpgoVI5Dn2a4nUJ9Xt6J
aR2ZmcAio6dE1qlgH0hPehxv1WhacIWOHx61dhl2g8INmQ0BQ4YrPigOhEVs/E1B
UrmkvLUjP+qfNnhu/miCTT0+cc0xnFknEoEa2Bm+MrAiR5qLqCQWKtOmVdCos+rB
foe3mu2aq0/heMYvZqAtjQJxDepvTcguVjaDCUbs9U2nVqAepaoX9Y0V60IXyZNS
8TkekKYD1F8j9BuWRI1BXqmnNIflAJ5/QAHOM1efl4HaCwdVQMrCOxyOLVbhdIUo
1Zl3PKqIvZVaRenHGIhCJR5HzCa9wrW2pxCBXx/ij9I/AQmuGNWuNHw9bT0LJmkh
yli/NS2jtSpUmQd3FIR+KLRucZbggidAbNz0qw8++On2PAl7WjDDm9N5n7RxbhS3
=bTgg
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cc56a890-88fa-42d1-aa10-b2a356aa75df',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAuQfXylDezTd6IXQUY4eMpTUYAfBEXNNlpf54Nc95A6RY
V624IZWUrdGMGX31jB1XGk/kwZuUO7W7ftlH6Ufcmfo7qPP6ki58Hk6AcenTnOYs
CfuGmiEJVcSVXGnB6a+fKK2BH36bXUmx2/s24yjnVho0DxENGv4WcZa1pfBvKu5R
MI17huYQH2UlMdPXblT5UiftoLal7BBt8f/Jj1BIS2DzJyl/GxZYhvTRitpz2lMi
PZRvc2z0N864lDaRXYsViuF/XYHMIWijYc4Cf6GdyM5NposlpDlNnU4hLm+StZ8e
OxToU4plqL2+n7sFJ1jhBIRrl6piiDpvkf6Q8xX7G0Sa4fdbPzl/O7gOu2ArQAAu
ZA/6Hfo+UAwnKrYRA/VTqfncTse/BZVc0qteAbJ9cpuNk+d3LV8/aBxHrBXTgR9K
I5NNONzSznKWtQuXys2GkRAAZBN0JGjEX6hHoaJ5W2aIZvH+GnCcj3/EzumIc/eC
XWG64WLqwAz6nruQg22Qvo1rsPy68qa8GPxYgEFhMoZck+MnIYqrQaAfTJKmVT+u
tcojRhE+kmdXW+6rfFk37FEEPxmKJQ3H3PwZmjrn2cyE7xarTiKyAznyVEjiqG3Y
ANXQ2gOrl1nNBLQnpzoa2529d3drNKuJEoe/bWea8HcP2iee5UV13KerCJEMDB/S
QgFRPw0WUwtVcufa5O9ZqPa5s0UEvayyyHDV4t5YexhXM4QBbhfPE3IYA7DvOSrj
3Qye0NqX0+3tx9K2eEJtn1kc8A==
=3W9m
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cd305dd4-7c46-4366-a230-12f37d1702b3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9HZjXFXeI/FntZNALp2mJPnxB2vMbrtXFG359VowV/wV8
Kpam44J7fhtE+79N+0t8Hog+ifICDJG+2yVAAG+qZVepNXCqx020BHFAEULNWqOW
uxsg9BhWcD0haTypOFzVDHnCyODJ91znxt25/JPCc9THyTU4ygJgBAKhVkZLhb7+
20TEfnhDbAfzUbgXKY53QvjVB79LIvG2QN00pThCeC5NDtG32U09dEkmcoUgL2vQ
O+Fzd08RZbTwpGdM+89eh6NGaQRACcgw00IMR1/hr10zEtTiU9ldXNCsHVUZuIGS
ucyuHvsx1ykxgGzOjvalUnrVbUkfrxJbgmnNGs7ivFYlbsyzi9esvIHbIEmrutIn
vVA5SqOiQl9f+vGghHjT4lxnh3T6fptsvFQrudKl81SXxLRmm1qjp59qc1YyXE9H
eLyfIO08rDxRPz8dbCHM1FWCfC/l9QgAYSwdc0OU4voguYBQsHKle8CTgbVUNSpl
6TT/p78U1xL1vybzx/BLZ8ByhrKNAoQeur1nyFz/IOjFqPOyuooGyFQMW/dgW7Hi
8d15hweut5ehfTjOpdT2VXs3As8ctOJd2jm+yid6nuurb0dYky8uK+2A7S32y5s+
vxumR9bRq/m4Dlcvx7dxvvcxmbrp1joXZQXSduFiR33DyU7iOk9RBN4h9dZ40afS
QQEhHudV/RQB6JvDX6Q0r1+BysHHX4FM5dlor1N7D11K9oEd1q07lqJYQ2Il+F+T
Rb2PEZmGYaqF4g/fXT1DGW3V
=IZ3J
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ce2c0800-6f50-467b-a92c-64e5490cb2c6',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAmXaeQ6ZZuptpltdSYZIgKFrge6t4n8Lxt922wKjDGjYP
ZsIao7NBDjVFejWccXze4HRp9cvWUMm1xVr9hSECxvK6cAn26MTB4nIkzxcizmvR
TT6KUSo9Fy4Ho/+udGbrRSL5GCvoTsLqU5RUE3fJ5PLi2g7YCSSzpn4oeAly3pjA
So29Xoaq+DmODBHz/GbTyz79jRhZCBn28D7G57SONx06sUWIbkrAmMfOeYT2Gqoc
jqA66YRGj+Y2dajPPyri38VTW3dGkhocUfytvvM5L06ed6Y8LEtI1f+qUcSmVIld
ClEKBBdwVJOx7E3wvwcAhmG7In9BMciMC71l7i8cCWhYF8fgNKlGaN+iSfN+EMK0
qYqn40NES41bGBC0JYv+RYfL5IJh3kLux+su+4Fghs64i4axYVayzq0Y34kIrNfR
c+FNUkDVQAejWLGCVpv0Tgop33AlwfncUPTBuFueVQ7OHpcTLNwtxHCpI6qREDqx
r1GMorOVTD851iU68QUrn3THUxPqnx1jQvcLDSPxfcT+bFd5IdzF/AlVADbZh3t8
vA6QXSyiYqvWqNHUMwKaW+PM6JcCMI9TRcOfMXWNBXFViyJ4tlVHstV4uh5ITVwP
5TTS2lzq4IFKFozkXypSO6rfLOTQd3ikWlpdbrQukBaecE9NFkoKXbwkTGOciQ/S
QAHvjDvc+Pl/DFZJrX+lnjBA5mkQFJsv/vEdriWcSWzLDydG3GkQJ5AsO2WHlI+l
K7QiHzbzI8lZKBQtt9aMCWs=
=P6Jk
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ce725729-8a2c-42d1-af8b-778e39785217',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAuy7wD8dZQzqzsK9i6dH0+tFjcN3mS5kMv3aH+Xk4x/uf
QoRjUEu2f8qmLTPHLrsTAWd6mSDuZDQvkKik4rxF3VVOrUk+UIw+2hAWjph2VXyn
0iO5ZOoApfiQM1u+FnKoivkvVNWnsDqs2tW4sRWDQy0cgIh/sAGwbK7l+G1IgELj
PX2wr55C6MrEt8rWzxQHqdvwycLT9Y/N7AoTwE98t1BxiymYJRTzLUSq8qeCT7gZ
bTUOnCAs4EgVfnJyMY3U2FtmbYMNrSOMQMWrBJstLc/DvIiByEXi5570OJxc+hhP
muZccOM6yTFeSLsouTESrsjkC0TDODqViWYMZwvmhiB4+2Ly7ioUtBGEM6/hcpYT
JMo+4THHVM4q0leygExPtGhBlsuzXGyzGxw0lrguCTfeDkNJuytXsVEIgFUQvOR2
1jR0b+/Wc3wVbhsoCM1UZ/zp12Y4B9RdrLtmeNa30Kb0EBOyG76phK6J+3Y0EXWe
+cdb7g52wo6iBymkXH0fblz9aCzBsmv+y46H9VCdeyVCzXZ11GczASTbhk2rgCV5
KFoOgZ2IGhQI5KjCreHG2ZjcxZYyytSLTQZJxhCFKX6h8KzUqd145Gr28Jib4AUx
Sr00s9aX8Il2Tm3clU42ypaOfqB9i9KtLDE2raJ9heqI2o5RS8AGMEkERGzJG3nS
QAG52Q8Y0lyF3cpQ7TK/SQ2RMAPQna70SixC3/LrsNIlXcLCCEpxPZr7zxxB6X7f
jLScqt9eop4eZLbPOzUdkFA=
=3N7T
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf1da90e-d2ef-4420-a25d-eb6879d9c5ed',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/7BcChCl1H8nsgqrwo5gKzogJxcaM6S/Rr4E/iOmRrK4Xo
NDgFUY9Usi60wmxla9RxU/uq/VxRwKXUdj15MuMIhI7mMku+Y+m7YCRDIbUxU7UW
UW6iaa6ihE/a+5aVxjDxjdZon44qFDaBREp/RaHLHhmJV/WTq6bHyIr9NZaKTuTR
B2QRJMo2BNhnCVnNA3Yd02fGIjVenVXhLgOadJnVSAGgo6WfUCMUKwippUo1pbIY
jaW3NstDuABhUnFsWaJ2d6vzvog5sG6xg+2ghIEjn3M8z9rygWSIVL5Zkp+a17Ur
yRu48SEdhpfcxMP0oV/gzMTPLECXitOkmYmqHaTSFELCf4FenPt++YJocj/mmPLK
6kCuA2/DH0cSyqHObn7VgrjQN+Xaef8kTg99PP6DSuHe73kEHMNp7hqD1bUy5tcN
aKBhb+6vN5qOBe1FNB/cQ2wdBa/NU0OOyeei2rz9QQC7CGtOepROth+FScM5M488
xW0td0SZDcUWQISBm33FJSun4rtNX7ZuqpuOE8fadwj/BRd67jG1Iui+Zn7ODibq
EXWd12f0godyywzHmLHXEO3HID5WJIgoJ8+gu0lwpOau7mvcCVcWUKSgM9PRNv4k
2dKa2jaXr1k6zZj9MLF0+55xVMpzDeHA7id77tVhz0enea90zPnyASWAYiOiYGLS
QQFzqjRseUN/rhUCj7wtnsl6IUm12izVfNElp08C+NoRHVeyV5MmQLYCIrENOqFp
vTB+WghTAI91uePHNYwNiMf3
=cAih
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf8ad09e-140c-4035-a267-c51f77ef70c3',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf8DA0fHF9gJQTXZtzxVBvBLTdUPf8DiXUCzjr2qEZlyHb0
my40xt0ogD68kcMYBvOe4j80+HBeJL3sDHa36dIS2r+DecpwZQFK+8dVI70SzaCv
QH27PJAjTvTP943aMK05vFlFrb6W2/Ml6VlBd36+7+YinCZw39py9Vd1s+GhDKct
a74VOJ8w9SYRd8OOEH0rYjt6ix6tV3HfLucT3Z4xrkAsIPrpVtG0YJGcpmpl/heC
YKEXl9X3eHM0XKZAzCr++Bl6+PvkDC51JE4kpyqOvs7bo+v95NNsUBkdShZHjzWA
LlGr+BPKKDi7dHDTiZCtXmexpvS6jP42MWRnw7f/A9JCAbqfq8B02SS++9P4m3nJ
9WT2loxhE2gl8AMYI32we8OovWvYiJBPb/gmWXz6epKtIxZq93B/s6fYlxSgYJCZ
P4LY
=+Wxf
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd6807e26-da95-428e-a735-17411168a85f',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAjbLfnh1JfloOrsrPNgx1dMpcoDu3kuDmV46K1b3IbZpx
IsGXpD4QJGMk2rbJH6zR2C25dBliyp9KFouwmkz0b+H60x0oxM61nQDFj2CI6wr6
CoDMRkENJ9UiPsjLjfEQxKlNDNki1y8hjvLj7BVkkvhS+lw6d7doZ6YT3OFRflTy
B5oNSLSR/SSfl78LSep/8ekxhwlPmPwzPLx1r1BIYNZ3IqSkjBwVZKQkc5DibZ3b
Tj0p4PQ+DIgocF6LwTv6eYJTQS0oRFnpwbMWp5hWStqhP+7GFKv0HW6teCwffsPU
bdl1vF9ZcJN1IgT40lIeIsqsjIjHuhRluoh5IWeX3r27ArfqsvLj6vOuWGKCTciO
6vWRPmeNQBONkT1T38fYDu2x6exQh5jsMRNnwE4Io9gb3avbr1Wm5jQctdkM5gw2
7fKxu45PGgIInrpF5vtFciz3FjWw+bcowVT/NvYUe/v7PAT2ey2QPw9zL5TiHgOF
D2a4FdZKqQu8NrVtSVYQ4EuYS/Od/aPz4DqsjAjRchYRENvnQG0GFwkuYw5YZssd
p9Qx2L2/Hhi+CGIxmtd5GJ4Hdboq76e17bdPLGGY/8Ms0weijeVYlRHEaQXk/W6N
TAbRrjIDZvG1+sWPhenZXAGoNMPO4libXWmijmcbh1SbLi3qj5zUHpO+gLekqA7S
QQFCTR2NqHfGE0Z0kvh71X3D42/Oa3KsZQWfTOPfJStYWrlxQvjzFLKNov1bUFrc
z7ShasyUavSYVSIpKxZ/T713
=QXxV
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd86b02f1-d116-4df8-ae2b-ac70566ffb46',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//emGLkjOAHZ9HpsrttNjNPTBebBTjbrHHNYI43rbT0qMk
JmiQ/SE++Mnzg+4pJnUqlBd4PYm0/caetFYFYQA3WRR6Cs+OxBjuB+EpLAmx5gg3
qN8WYfdwvpjLcF8pZG90ULKSM42ZnnWyQ+Xm6/B/yFJZFizSoyT+8FF44iwnaCw7
dJirO0w0owsZ3QUHnHF92tsa1RVJs85Iz1u4hcIM4+IiIhUF/fnxvLH/mrUvCq1i
yn1ionasyRiaGpmD8crWpjtQyBBP/Dd8PJVInIPWoq1imMQstIrSzvnkSnn3zz0q
RqCVdY9noraFrsuBGh4N/Fe8lMNhdlS3/p8fnZG51Z/SbARYz44N6yg5rax4k4Ut
aqT5CAxFyFd9A6Odp7KmbHb4jRtcDNK/IJm1fRS3/59wsFDSs3FX2Tlj6C6IsLJ+
+RB1nQBknoxXR9eGa1reXHLzq1ioX0nRgNkxJVAHl+YrkuSuFNhy4e3o0WFvlaiy
6JWB1akoclUZpEZYCMU/vCms2BvnFTm/BoGRE5JvepRPzSsvKtKMoNGypJOEKcAU
BrBIfQpBsflqap3QLnPpqLI384++SvBcFJXmfsVnX6Q7RoZdcdAbkxa95FCFpBeU
qbxNkm3ePRWE+FIL0A6UTdPJ25l5Yr3Z2eZMmtxmuIQMf3WTDpj6pkb7FRydLPDS
QQEs94SnTtGEHOtOyaIAF38dmmaoi3ux2iLMok/zLDan6309slJrpJ5Yphho9bVp
rL2rsxWz8wwMVMo0GFKs2KS4
=zNqV
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dde0facd-131d-4a0f-afec-ac73afc3f321',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+NLsthndpQKcEbvJ9+WUimrmWqojdAeJvG+HkUEtFVtp2
9xewSt8fA7QGPGYdpJEFxVAygMvG+pacp7x+2OfIr0RrkncEA8UHeEalIo9L67mw
BFCHlUyuwrRp3pfSdJEtPARHx/WmqSGdgO8XvcMB7wrZPEsCk0GFvgxx9YaTTVqe
q5xb1nH2jC1yOK3sdT53lc1TTG9rr5F4IhInBSwc+JlHgFfbVHKCx7XgcK8mJ17t
K/OhopKtso5ykQ566gUnPrwXUUGV8qmESrv18FRB6xlsabY2riau5TIJzG8JHLrs
Tkv5X0qMHXk2VQaRMs4jzoPAuwpkk+Px5j3G7YuRlW2IiAndly/1Rakkck2seouf
RPNGlnYPhxXWgsQpjyfatg2II9F4JKiXyK6YOZ4StLJZZjHJ0VgziIM1a+gVs9q4
BPVmVDewypiaJD0RRP6omgZ+WnQSHX9uE5J4WtgIfn0E9oLoS+jhTwQtfLoBYx+r
nwCSBr5xY3DIM5z11/LfmzMrWHRHexoV8cHOjHwxxnwjtJdVpOmlnfirlzxWs+Zs
mElHvekwfmlTCJHzUfcO48KxxbW4CeuvTs67Ozd0VLOnJrDB3AN/+eS6U2wH0xNk
+OPaq2iL/uNc6uto+8MWQYWYleC4p+5ZsiG0irPATE+Ihu0hXhRChXyppK/zet7S
QQHe+lg7kDYWMXKXOPr2G+N0P3T+7RYs3nzkShG2zWqvdnnbmYz/TOW7HfJnVTZr
GnXoikiB7kyyngKYJ7tVAGUZ
=J4cv
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'deb94fce-c3eb-4c77-a92b-fd9cdd12f716',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/8CYl0uBKUIxkYItrFWuTyBkA9+8PXat21c44OqbsUv4Wn
i2GKUGyq8aQDKKvX1mHbRRX7oK7A7o2ZiFGb7sp6+nbuWnjuWEvsbz3HdmEfV3vN
Mc0qb79yuKwIBGrS3Ns1DGW6Dn2AFIDoxz3zD/dCjZTYNH+53bjN6XqWji2UYwb7
vFGP9X+LT08p9odJTehudvc1enIkqc1HZBtTc9/XhvQFQK1FoNs9OfsSqq/B62xW
VMD/EUx7zhVIoE103Vj89e8uSt/vg/NlFSbYgT6GSRwDF07V8bMMDRb2EG8OzOHh
r1GKGy+WUdauF2m62WbHxTywiMmNQO7L+WwCK/dCO4Rdwb1S/m/fTLvtH01VCji3
m9/4A2fLJAPiqcUp760cp35bedFKX+/iUjA3AE25MY/LipGFD3DiqZ/kZPdAn/Hi
llk4TgAVgrgysy0Jbf+rd84HOO9lCCkvfaco1Vxm2L9sgEdhgv+8lOhtWk5VDMH5
Y52TAvje7Ru02rG2zQr7cD89cway/0Q1fs43Yu03XfibkQ6VY4iWix9ThV5NAiIz
M/XsCSVgGQvSPZUYEvQRI4p85CaQBX5E1xSSFMVGBfvlSALNKdXWWQHMj0tplrx9
IkRw+TzPuJK9ZZ1sY8SxgfU8lGON5437dsBtV+QqnK2elpLFjRf2P/9Mow7GOnzS
QQHZgUKmXON1ma8YD31gP9XAoHYFwLeyxSHHA2wL7FtH3+huOZgiGmJjxc7qQzY6
0Na8fCa4MZ6vwdg6EEOdhhZU
=io97
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:57',
			'modified' => '2017-03-04 16:10:57',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e1b62264-dcc4-41e0-a917-57f9c83dcb78',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+OaEIkWffmFuSSewV9zhvkf1BLQFq9mI4Fu4LjBCVAy03
fXxgr+LoeqClXruL+dJWL0ptZq4XAcDNgmo9HIGBjZga+XwOWtmVnjvBZAJJHpTr
NNsSnkdQoEBk7omgH8EwatUyFVH54i/1WYfYsEniA4o1MX/WXImpkiOdDpjuh5G1
39J0dZgAdaFGuCFrHIEujHbYudqazuxDIfv0twjG0CGmnhkLwJuif2VAh1JHKdJw
CFHW7UYwqmnq1UNbk9z5nFE7Ne9RgnuzLIUfZZdIGYtemgRpraKe5TEIBv0eo+Ld
7XA86OaumTMBfGz0XLa/yi+GH3ZRwsI6WLpkxnss7p2p5OiFSuQDaYnsUfj4MvQc
2luKDRt7uE1YTOucerLlxd1JhEduooIvE9mTq3wnIrkrGDOWXzDkusR4x3m/BKIu
d2xMMqu0j5CccgSUsWtufN9GkXBW3Z0YxK1Qb7Rs5z37dmEWU9M41/A2S8sxbfYs
K6lT8OXvycYOowi41AdJ9ZsVum3k2XzMYZN2CXx60mH3wkXeGGKoHoNxUo9jxmr2
HdlNbqG+O6WUrDlDGu3sTvv/OLV1anQGL/ue+VulAID6Rx39EJbhexkBcAlEAxs+
8gJ4T9tjeNatcUj23Mm5FwMKhot/1Uf3z/iSeXOGWTo65w3h2tiGB2N9iIRVF9fS
QAFWAxWlTGo2ZXZcYaSLJal3Pyruw/IJkYjivCGPdf1qlQyFpHXa7gojiXDA2VOn
sz6kdrNvqmDm2Vx4sWSjYQo=
=H5za
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e411777a-fe9f-42cd-a654-8fe2fa491a98',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+J45b9BTX1XHGpd/bsKkm/NBxcQE6Uv7KSXRlzG7fTSqT
LOyvNFZNHJI0S1rxRpTVCfJNX8QMBzc47RBkF38Llg3Te0JtUzcuZU756ZB9sEi9
MLM7UWhXt9amI/FvGTTjKh5P56Mk7IY+f12NuwVGQNbQARdaP6JgmVXI2cZ3KOk3
KHHdfbl9YajgzNBJeILhnEexASsY4+k9TvN+QEPcuqg0DmGRwPePIfvgFAeoWGt6
UMdNC356aTc/i2gDZEvlTI55OrBiO5omXsbXwnGKuMaw2LJKzGnxC6R9PJt6aQxK
w6AZHqaCANied4tKsipqc63oLRsRdxiyagpc2R+JYYqvIqLxqKAt9CdvejgMtAEz
MOR8mmTFR3aCUxNc7qqeTRH6guXJ7jQwjPtdClp4yC9aDRB3l3Tg0doN78VbUBUM
Ym8DMuUAKf9qGM9TIwpKlrWA8RaPcoKHh9DfELvqOftIFZBmr+VAFZVUmEa1M0n3
q/XMhSigOdPDhy8kzLHhOQqG8DUUsLh9qGWxqqMBM/oJQXQOLxV1tkxzdXRBY+Jz
A2NOLF/1EzfiVc0Tx2WuRa1dZF7jfPvI5Y/Gbfsl7USNk38K2yJOPtX3GJYBs09j
NX5vMcC0nTQFZRjevBcd9IQjuQjefJdW6KQkIda9eLgSUvjy74OXgvXSLqaQNQ/S
QAH3QQaJcSg+RnqlkxYwN4aYzVw/QEoWcvMCPp4CZdunAH8NAyxcpLTov81SZPXX
NexsytESVagQMtFaKLBeBVU=
=uecQ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e488b06f-d0cf-4c9e-ac49-1d96f2cd050f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//S5svuiN2WQeCD3qpMDnBynZIw6lXOrNGLJ5w7WacYIEw
VYVKd4SuaIEbcmd091xqsHCqoEyifW8DVYZV5Xo8DMM6imT84MUT/TyXKk5Ich7w
09jUEKGE7limbyui+8HZISjI623L3bC88HXe3RAtLeh4zWnh29jGLTWdrBzTtMu5
rAuylr26tBr+S2mv6ENK7Zpbegz7z1qNLiLxB196iPob/u+tkWobfka/M6vRtf8K
ldbMALnY+yDCAcmzBw6rMKj2CA5mZw0wX1Yzano+UYGk6DbV9/g3vepmvolbZKPc
WnhmH/oqvzH1zFQj86lMNl0kZRJ6Gk3hN+H1kBgdpM8FtQmSKJ3X0AThEZP2IL1u
sZ7Z+DGEQ8VSS3nl5vh1KayzWgkP4zwCLgq89dcYBSMSpQMzT8eHSpHc1xEy4XEM
k9rRCvWBVNtzHU+SJwEZH7U2e/f9ZNEUsiF3f985AaqRE5lSHp48Lv+2a86nKaTi
nHRx2gAUrT85lqz7lYx8W4ARw0FhRQrj2HYFQ7z2kYg56J8QxmHoqYC0ttEwmTWe
W70/XuVNGbNI+Lz3boAzZIrdV2UjGUPfV3djXAssRw9oF52Zv7R/DYDMTBDEuSZE
PD37DzlyPpNbvFqTuy/1ZncQo01F7kGkZuPCyXE7eYdlhp/L/4uy445sBuj1ilvS
QQG09IWp6ghcQVuDHYFBYYdfwa419i/F/95zIK6LRboFz+LMctQ01fGL67Up+GkD
p2SglAkuqJ2/Pjy2A72nxIlR
=er+3
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e56c8e9a-037e-4133-a37f-0c59d3d29e7b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAkKysl/AqVkR7kuoHhiaJWGTDLGgRnpB5zoNWgSa+qEpp
Ed7StADYAn8+PNl1SyY+I1yEgDpUT+otqseG2vc0e/lqvnPTIwzcBTNOyXthr7na
fQHf2fYYugaJ9cZUsTvso8K14w999azXFnYeIwb0aG3EldIBzB6ATWL45aHFl2h5
ONOGLYPWdp1R/HKadhCMGwF0afzIZkaPlNieyUtmPeYwFHTRCWQYhamS9JYm9o4G
oDp2Lt6hUcze6i25sYSjLpuIC2kH52xLpBcW24RyNWL8dYjS7w4yFjJbDbFM2AH2
hQvlwZMnY4zH2LatEXl27y0rJA8gkNwsyvk+2zYLJnb1AC7y+SccW9rzSnJ5zcU9
b3C0DNMOd1DGOSI/Ofku6bFhlcNmIgWxjE7JpWmkyKvzhMSAndXF5EyzHue0BmMM
PYiiJOpo3h2UinqfsfVoIRLfFS5AHuMHWbL/HaduGWgjUXOQ9GjY3HIWyU0vDfCP
cbd0yHoa12TgPXmzGQH4FOK4gWHhWchTNuD2haNNGVXVXedFQE2g3rImjfwKua+5
GZO0a4FMRtT0NrbJ6WSHKztzov7J8EyyIPdkHLPxcnGdAp10FfFqMZxjNTfXBr9o
HT/KrKa4xZEW5YAD/wsXkPLr+iYpco5YvB2qVs6iNk4yfp92HRfNcp87BDf1r5HS
PgGxkzvbNuP8LckvlmakBOkRyHbCVfkWbpVklS0SH7DSsDdsQY91N3PUOOYvKlSv
72T64BY/rDNwxyGDIYkS
=Qbax
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:59',
			'modified' => '2017-03-04 16:10:59',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e64ffd85-99d0-4eb5-a01d-2f6687070300',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAmkGXtNr2ucCeJppjWOdzDNrGQExs4pVefQEUCGQqd9Ta
CKZzywzdk+PZnOs37utmlnW40TGlE7ybxoRSqtl1l2E4O6WnfWYYE1nQVwXIFuvt
IByXUhyMikb5w6k/MTFB/+iu+c3+3UzS43xJfoUDW1Hyi8vFWFrIrHmfqK+/CWhc
U0EP/tXSwMTzRAPNGzXv3j1hmRTgMKjMjjk8ehkwrvS803icPUh6aP+akMNqziqG
o5jFgF3u0/PGIG/Hg9B0uQPWe9x5QPTICxBqzViAXsUsAD6qKowwTPOndWgTBBK3
Q6gVpPNH6d8blfwXtyz9aM0bRhD45EK5pqOfVQMCAufajL9rxYN+eCo6mH6yR6VK
yr6pVrrEs+mK5GW61rIp9YhQrlALMpCIvB7gVCDL/qCAG0CUkkIa4GZ0uTuoaD+5
viTO7jgFe/Qutm5rtkOQ0ZdqTTu2CLCHyj20FNmqKs98LZEYkcu1S2GbYFRTBd6Q
w7Wzh2FTFqixgAs3uLk11dHBbIDKalf6BSZ6l+mmVX9p0OoVJsfho/etlRLkyKOj
3Wtgx/e+vzCtabKH70EbjP1v+lJHjh9XHZZ9s9OeM+1KLPNmOwNPw4lCdhIj3U9Z
UVKT7nBw7h67YUB2uChS3yiXH+smTnUf7JPLp9ZKDWpWn7To+vw4ILWf0UC6pqjS
QQFLyfsOdVS1usOK4ysxLYGIYJSYyEVLML7HHKfugJ+2aaQp3mFxsuAe16jujSLF
JDGRE5oPfnQUoKJ5A8ltAy9X
=Xpag
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ecdf03e6-2f1f-478c-a8ef-ed1644b34530',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAjJZx7sYvFUKlzSym2+cz9iUYMoKoAyZsWuMgbiTSTt2N
ZmgJEHvrFQQaOguTxyrmikBvDg25/+/NhRzu44SqhkabyqvglCoIpUUhBJ81v3HF
kvPsq6wvj3rrYKJReBB0U+LWam34BiywVVGes5ZYpPtPk39LA2qljgd8sjjYGcnd
+62wc9XHeHoNKg+c2r5nCV4q1+LbifSAn/aX/aYjInVoxuvhjzMZE+iWyRTye1FX
nh3zJVBFeEsijyV0SNIHOkPJY1svwxEKGb95IwTDRBRH4m/6mODs+KK9YvNb6fgF
SokfgZVssTm/wX/ILSIdOTGmhCX3qfjsUXC3GfGESZaC/7LIguqSWZRwI+ks71Pf
x//f5yNcGHurRPzkM9OetarTxUbw4HseSDlMmJVLwiniBoclSAxnJRlWeRikuu2G
jWL0w1+P1b71ltP/vjCZrD2KjWTJqGxaSZt4/3wtKon9T4nBfgSufCbc65MP3xRy
37UeTctgXX+vX3una5d3kY7bp4Bqh5TPGm7rD4OhTzKTKwkf4YAkHUgThRfh0yni
wgf+fKgw/xVzPIBbxjw6ffx87YQS1B90VcNkCPwzszcn1TS8IP94E/paxANJymaO
Y6idy4vdWhlyTSO6J56FR9gpX6xvGbqvrENUk82RN4jJli0fuq6vZk+AFPEmZcrS
QQErrhtNreoVNjYmx1ghnJ+APhStTMqLk5eMmNCpNkgaA1Pm1/AwlDaTb4ayOhui
VODkFGzvZjyau/ZVj/AHg8hg
=CTtC
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f052e21c-1143-4d1f-a80e-dbe0f962de55',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//eUxJZMBpHQLzXIRoieyZAK6hzGuWv/j1AVUNr2JU2Etk
Jh8TNm0cygOTA0qSrZuW5nncMY7doXcZy/N8oB2UqlCaoZVZvZqzf6fQ0nkoxRj+
hojtAEguxqGsBSJkue3iQSeDrUXOf++F5MNWb2TbBkw63aZRXApOOx4GR6ImPeCF
x5N78f23x5onCAUtTMncNyK3bVmN8VvZGzH7OMaZYx3zJFe5DsbcQw8I9LbHSX/3
TGCFWuDZjl4MnNKUVI8HEXIHjPUNbjKxg2+MBAmkLmjtP5xCbXghZtBt49kkH+Ef
pgnHzSrBAut+HtwMW9RYsvcbhWz74Xkm196W+55A20LDDsw3BBwYLj6C8hnYs4N9
HrfVOdC+SaQASzVgqlnr95LfM5mFLBUNyiJtJHQnkn22rh4lI4os4nqI6y077KGg
8Kyu8vRsyWKbpO20wnxAzk4EKoeJOr9VgSpTiJpLo5QaKmuMoTY6RW4spRyC8nKd
hMdCU9i46DRfcAJrtSNKmpUlzGudx/+vs0yJTHH2aCHhtl/J5pGL0RYCut2rMUtj
CJAWmmo0HzPUTufNDMKIw9XgB3phPzWe+qOpkz6FtUupdY4N+5giEjRE2hqq0GJn
qdvs7Akc04/mEZYpE/fbN6P1kkJJaDJeTasGgmUHQighOZbxq2PvLz0ZmIHc5gLS
PwH/crqWWEdVs/8plSeJfkvUjV7K8bejOzwm+X7m7qW7CAGKMIjXu9JnEtT1aGOB
JDLGmPTfa5/ERmmEoLKF3w==
=O7+D
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f06c69b3-7986-4aa5-a458-553a4e628a23',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+JDVJZhkIfiNXnq1ii8BFPq4/l5YxXgu48yyyWOib83iv
+Uzg8OB4DcH6xmWEXzYVDwgVW3pKN8Cl0XRel/jiGsZEYnJN//RDyRQT7A3FM4pA
yxiT4B7mDKIY0WT8qVEKlhGF08zfvaNpDOQ91klbMINqT/gFdPriDT0K+BHrX4m0
SwvDbieg1OUuIjYO3VFbl00oFONjFnYxcy7Rc1xl8OK2ZW9Xxj2Eoz8bonMF27Ei
KkSjD6fd7d5mTs3cAVRsTmaCl0V+xDO33ksGUaQCDl7OPgLVYEH3C2IxRoj7Dx/c
RAHHWBWu++T9BRrNI92xXMLiiFjzO5Eht40M9okss3NANT1bqIuPoXg3j6pKkHW1
zYDVUmA1rRpWgPGz5JkbR1hKKNZFOIMDmsZYjfmnj+AgZR61ajgEatsMceariA6N
+wRTlm326djeGdXNPlwi+isjjkoZ4SGY2Iow/NdRnHrDqt8AQcRV4KBs8k6u94nj
i9ffqKNJgKxU5+W6rrHGnGALkV0I5fDZluOYj77hmeZ+EqAheSbjJK4+bK9YCMkr
B/XBZvKppeSvZ3csbJgjKWiVnNaCOM4WmEz+thf1NM9dd3PTd8NcSuNwbE+/hVLj
CDZ9CuFZ33yxL9K/dX5bu/2ocmHcmRSJoGsYIaA8LATZaFruudLMmwLiSG/ecTTS
RAGnnjyt+Cmb6ujnXIYgZ5Siu4OAlyQWfbxJhHw1h/URTC5CupJhb6ldsb/TcafB
pUrTMMesdOZ3kcdcd/2u0gt8G6pn
=odHV
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:55',
			'modified' => '2017-03-04 16:10:55',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f0921665-0420-48fe-a21e-09dca3d1d8e5',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//X1uPvRHdriQKUZZWmaMTBB4V8F68OSBZifSZLYAOfnWS
fDUuiaOYqhFONPluOTppxqfVpmPT+szmG14ujB5V4Cak66cQtVmmL85N/JDIgUEF
bvV6TpKS/YYs9szeIytWfmzP/LjONWXpRYq5Vu5sEf347FpuKnzBTPe42OZoUUlD
8jfAmhcWoOrD8OYgTS1cKIb1AolMQrWLa3oMtU2PV67IogEY6ZnV375iM6+dFALz
GH1IEFXjXARmcbpcWjCGNZo5ANgiUfji6wCnwzj4+PJgbfZu+ZN4iVu1o/SbUJD7
5YeOXdhRbX2VSFiKSn5+GJBYSW9c4BK/Dr8pD9A/JjVW/QtMZZxTVBwo03uOtHMh
VJhR/2IFS74LZibOHQqT+t+IHZMGm7klwI/Y1MtnVkAbwfuNW+VFepS4MMPcxy2o
PanBXw8HVi7JrAGKoY2zHGq45NgKj0GDE9aV4p++lmVC+xPqDzCyhf0nbnY3pQiT
hnqi10jCAA9kybMMDocvUmx7KMJDue1B3OBauQyBERsF1Oi3x8HwACnnVfs628Gp
fsFmi3V8O3XZox9MTyUpwl3aUgodKt+qK+DiPyuwGtVUrx3QVvfLWNFu5o7S73kQ
ujk02gAc19ZgYjcnaLyV9jvRjVdH0VP6cpv+If4BCldLcCYpp3Y/uIdMN4jmd5rS
QQGwG+ZsDnbxLbDajJ0oGRdH9UDfitIRVwbiEvnN485Jo+lCIEGmjEEQ9KMKHt96
a4Rz+gge874U9yqi0AGJCW/H
=k49u
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:56',
			'modified' => '2017-03-04 16:10:56',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f3e440f9-9e07-4c84-a1e0-062a2db19278',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+OVQ4J8P3vTNNjQxixfNjqLmDUGUOYFjNjzQtkgxq7P1F
5VhfJ4/uB1ojoPiHroKcFKSNMymA/Q1Egm6swH5KyOOkghMOUCIMjah0nx6JJjfW
j1SnO9tVZsitJrxNauZn74GaPWji6WDXbMVE/JI9klkgHEJItJvgRW7nbM+qRYWq
O12F2COTpTNVOBfPm2VkyVAFNAN/AK8MlfuFfbkSyiHUr1OYMgYpVMVUJesslInn
AM7jwBhRBwuAlBrlmUGoqwIQ08lNj/dQ/KN7BdYDRLIjzm2AUkvJzgyETGs/IWlD
6+elJT8r//KN5KqfPj+U8lUXH8lCJdGJBMllT8RVQ8xNP2kyY4elrW8S34D0o3Wo
DlWr8POS4VGuDT601pdOjlisLFqSock0hZm65hRyDyn3FjfhKtmP6cRZsxJkp3MK
nq14Z61+wlYPxXgjETmiighSPVVEwSo/TkFBpb048X79Pw0Ifd1eHoJXFwiPr9Yb
o0h/8AM+d3KjGh7o9VWloys7NcDnjmvYXOQ3P4pTAVCWZ0NqcIBHhOZOj/Y4HooC
9BDa/JHDzOaNJ377Ujqg/dmR7ih0Ey+l9NZMdztZ3Ms2lQgz1kmavTGuW31//Yem
8lH6lVAjebZsmWyKEtZYh/13MB5sH/nI4ZV01mrsEybzAIw4hhmDl+bGLXWUg8jS
QwHzhJAwUePZnfvdPBIjlr2RWH5GUOL1W70GkxGtiFXKSrbUa3hMbU9DmkfKs2Ow
BkYE7LVvJSoVOeDnECP6ZCUZ4TY=
=U+zN
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:54',
			'modified' => '2017-03-04 16:10:54',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f542c520-8175-407c-a9f9-e48e8247d5e4',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+L0YtCtGBrx7qqg9eO9MfA9pOS95Euirhh6OaoyUEXM1R
ibIS7dxYM3fR+cbXwSf/hUtTEwYqH5R1JgniSWIXztZ06uG/NollgT1rkoSG/JuC
QDwHhzkFHrp761pwr/XMe+gwwlxXSs6IrBzYw3Ofmc/yGESEnQXQc/L1GyncKA5Q
4k10YguOjCFzCLH2DUpNtzpEhfpvtoJzWKuPQoyzzVphLaH7otAHwzeP3vEfPbKp
EQ6+uH0KKoecwaPvyU4XTCdBkO+xfYV4NS5mXLH2dnn4LlLfIUWHK8TFBfr9bmlk
mlaqADU2eRioPNwlOCpVtTZAzZNQ4W41X1akG/TcWLhLjXs3PPRsTwfz/NH4RpJs
yPwIM5tMpLiFJE+HPfaZ2v9sImvU6gp+b82vaJQAgE2sLF7+5OwMlc6Xoqa8zV1C
LbMm4q/Hv08xgxGtrtioju3Wk9K/O4AA9qn3DqWcV20QUKBl4zwusRShTlqFYhbl
MC/cshKOjKZUjztBwkWxeqxS9+f+nFd+qlJ4DWWESP47JBGpxmR4Cpg2x+5HYzAt
yTLDvKR2h70lh3RbBNVd4RgS9BKB40Cu0RuJjLFgF1ERaLw4SFdjsk7VgdPgBroj
Y/gJd7i3XPyZmRh2xYmJ/LC+MTqwGgmBVL6wPPoaMFkWCRZ/6HF9CzGHWPNe2RHS
QQHi5i9GANhCnWn7CwjuOWd2PrJFTJJGgSjEG9qSH7NKFZtdUvQ2hjVkr3wWya6P
Miu3eAcgALbfHqg9/3RAAseM
=qGNe
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f55ffcee-ee62-4628-aaf1-e536692232e3',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/8D5Ay+HuexOL48uPJrI7z+QVlwErqDyFtTzd4WZ9VUN2Z
Psx7ZSVlO3mOUMzdHg6MbIwbenhuxhePA02ssuJp6aLOEleOhGRLMJmdBC2WMp2r
VzJdDeuL9SLuHt3Y1VZ09jvOGm0JfvPEKacrcGbaMhXG5Yz3QCIfHFgeHdpDB2wt
8fMrrYfzooLMaO2se2/xKwGXRZkuXP9T9ojO+YU1b8UC7vSpQO+q2JaIBoW/Y+5H
2WVJmoJi+VNN0X8d4OgvTTgN60cZPF3bhMpCN48NeYDA1DKVS7dzOZXsv5YW0jw3
MVLWAaoIebTDc6lDTiCQRgQpo9XsAuNgCtu7auS0Ukegug05iIRYBjqV3NUeBNsm
kGu+//rLXeyEmFdsag5yWVSNF1i6151+zaQJI0p42BW3xqYgpyE4Vd7+yPj5HTaY
NEKWFMpXHnP4oPtTuT03gPZDrPT8iLrj1QxU62W1Y5+RFvYxDCt5q9+X7wbn5ClR
b8HURqnYHeuQXiCLc5cJZPPTVmGvWqICquu7uKhU5UMoCPvSujmgNEFPSeTgNgFm
p1rhGx43LbZsfN/vd9OR91+08JeyBsEbUp90WY38INeeNDpKQpsGTXDLE51BpZqo
iBPZwp3oruOKgewIgRTG+tX6mlzooG537QHJH7Cqozpo3jN3K3LcPEq5CjP41EXS
QQGex2QPl4VNmDMeyvEKT4aylKqlfdnRqxjsjT3mw+jb3Um4BlT147uCyJkOxzCa
Xezc9L6FGnQd0oXCtXlPkR3s
=RkSG
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:53',
			'modified' => '2017-03-04 16:10:53',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f6a27286-2f70-41da-a028-7977802d9829',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+NVEUoHC7lSFpclOAnryTonTswMaO4Pon1p2XSioPo89K
5ICQGVX/O64XJLWQryF7cRf+HBgb23ccHVpW+BSIV2D1YLvfT8BXui5m4+ReyO8J
J9QL8gxuTnEzfq0P6Z3F9cIgRJeKpHuZ20GHAwlF5GUCuBngx0jzPkRF+RQyHtr3
EiKH0tmSMoLYePOaxpDHa1h5kAqwQgX1klGmrcd1N6/P2lwR//QDRbXocs+bqOeT
WdlI39j0VYxeiDl/E9t1OLEhV8bMhegI8IO/Ih5tvx2VWezPY48bDstlsfzRVagA
3cB9SctPo0rj0K4hmUl3DFUpCnB0T6LSC43JRu9CrpYpmrXeYSpcXlVYIa0kyZwD
aGALMcyFl+dlKegyrMMvbdQyMRBLbAkFhykj4wVsEiBCqRl1tqwy/24/ES0274QK
wsPCmNpr6ubDlLu3S8Lo2Wk8Cxf+btjEQ0chPnnHTY60tBBRUQQbH23K//QoEYui
PnWicEAD0G+fTHRPB8/DloUUyJiQMNr8lB5c89gJ9y2/rKKWuixmNbrtvkbTCLMp
2dN3AtrHz7GOJqPjkqEm6CqIYckiIz/grNh1D06uAqpXV7lp5NqgbleaKMUXlfus
RDnNR3giS06oU/0YGLn8FY5Lx+NzheeW0h87/5sVzASlOsEsQHA2O9ZSIuPlyhbS
QwEdM8VEKTfn2mY4XWRuB5eyN8OQraZJK+Dd3Nk5ugQCfU80qi8r56y/sHfpHtyq
xE7nYPAvzrJkaWfwmnCfAIAxrlc=
=OzVt
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 16:10:58',
			'modified' => '2017-03-04 16:10:58',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
