<?php
/**
 * SecretFixture
 *
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
			'id' => '02dd4001-7d6a-4a26-aafb-6f709ee699bf',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAqJ8yBxnS/HqO3Kuri8lqKbyyL+3sKIhwDvmMCYbjMvHb
GTjZGxgpZ2gr67tpyYnLgJGzQqALuh6bgL1t7y6ijkEFWZ6ogvVgGfiFq8go9Qtg
cid2CkSS2g6pbdpyAC98PL/RYBdP1SjW29kJ3TDIVom8DrMFBePA6UtOfhbS32YX
rD8UbIxptyrhoxlWl9t8zSnhW3GU/zkOYb99rBmxk5FmalaWnLjxNyEoBoEID7uz
yzAdASgnf4CvgcUpiD41BIxeYJNju6LqqPpdg7O/nfEL5YHFoTceChHOImG5lxx0
WI5oyzBiua5MpvyKVqBsO6ZsxEKluGfqvIt/4XhRY5L/6+LBMBOgDIooGamr4K+z
6JbsO51wLEkvnx2afqYcI+IOpwZ1um4zlQ68ICCZxOBW35P9s2fNK8hVxlcCAWP7
vTRTUqqrMq0fUwTz+xwvFuezAufN6xabelPhtMeYeZeSdB7ymJGOeY3IbWJ9bRx6
mVfSOWhKRsCBG/PB1dHWuMhiTkmaXYS2PgAZUQeNbz+5A1PQMWybfIEm80dAFONG
eU83rJAvUVqbVWwqncxux6sAJqNY39CncCNaiycZF7Sakvz8sLxoYfcZ0B/R7Ipr
KVbRywku3IJuw2kElREfEmbTPo8vi6igD2DF+mmft4wkf8vlg1i77XyNv+GnvO/S
QQHhibu6K0+I7d5Qmh/g3PJngmGZGeltAO0AngBTesLNl/z+GsWqZ/rJ14UnUDJ2
7Id+AvWU6sXAxXdsRj0JGfGx
=UBQr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0434e033-602b-4dd9-abd3-9929c2077803',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HE6mgjFdq3Mrm2V2q0KAwZmZHp/gPH9JQK4EIKA/KbK2
5WRSJPWbVQ4VxKmLM6Q8HPjyJ5JLpAVv8PK1GYubQbWLLpB4fEofhWmyV/ZxLJsp
42BFJbBcps/EK7e69/GNYOyZSoLTfMm/QBOUfn6n/Wj6EOnaB38Gp8MaP7hErMB5
DygO5V/K/OeEgQ8IP+MS/BbPFh6V0dJ1umrc2+St8VvyB+k2D1LsHzvEz1va3xXV
NBrT0MaCPbvsPFddCHz9SilbcvlisI/4KUsUzPvU9XaFhEkvW3/XetgleXUwEWI1
yIxlPwToXhze5ddCB7PVHtr01Jgr1XNbAp8xHzFsfl5JDnPBniA5o+sedh6Rper6
XjGctV6F6C6OLiAvRSQk7AQdsoqNDYdcvkoJ52v3hHxBBej3hrPjlT8vNMubNsNA
inelsnB/ZhKLp7ZfIXQI0v+B/cpENPn3Y73EcXkzMyiXTd1OuNlnxWHSPX7wPQfQ
IZqdOV2rwKhhf1xa1D5COgqZeqQXZ8/ZQYn4oAInbZjSeRqy4gMWHB402923PCxa
CQv6P/x/75b82ZMyDZFDqStPWzfujwO9//fisBSOdSk5FCJmcddBqLqWHSjRI1NT
kkONtKONixdYaNCtvPIkAHeRyJHc1ZT4BmvkGCbABVaucW/G/aniXezZmk22KzLS
QwEZTh2bHVauYoC/NypVDU59ZoGOjSyvfuW/N1aas5sgkbo/ieiOU1CkExa4+arG
4luFKsEaK1X8YHlx5lJKViVpTy0=
=GGn7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0600a7e5-306e-4125-af0f-d629952fb5e9',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+LQj9dBMfIbP11TOsqUiPADDql8XGVbL7Nm80GrN3lYiw
CwDVdYj/J5GO91yIqeOJ0wB0nhG48qw6OKfsoYz1bYvLuaiFdIz8VXN7o48kt4Ko
hAcLqZ1N/c0Gz0WgX5Z15doRqkG2kaIzs2zV+v9EN6tE66Qw+kt7QlJz6FgrXBJi
Z1dLbBEOWbO5D8S5CsUkRiTPLagoOXVirryeGGqbEz78uiuBGytWflhPcXpgXKam
qfjIhU6mtWwcuINxjWNnu1qaq6rIs2LqrQ0YwlEmDtIU6WTcYQ3/Gp2uiIiXGyJN
zUHJIOz6HwjsL9gF6ovFPuhxer7XBhG8yskwtIzgMdJDAcS/AAEMhhoH8vdvcNoI
7jAUBmDvE26HxAt8SLYJfik6lJoO+7BygU+G9UFrhvrAtKx+IswMwhOXd1tyffDg
xdCaHQ==
=yTm9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '06efd9ae-f3ff-4141-a8c5-4cf35d75f9ab',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//Rv1JQVuvvVgweMGJNYcIYYfLYYms5NIZWuAR/OCuKOPn
oQ2p0GTG8mB8I8v54Lq0qFt9Wxo7zmHCYmThsDDpCcvQPCXYefU7JwmgsmyouPfS
KVyFDYr2Jg4RESyaAm9KqRq4YSbKi0f0wEpuvlWcaHlK+fwvrF+UtDMqEmbrvuyT
ywgQeHtP8XEwM08Di1cx03Rry+qYCfvwbkCRUt/zcFCipsXEc4SwtAD4WLF0fCz1
LzN9IOQigptyb/k+XpwHqbhmDFUpUPJ0HUIp8AeUditypWz3h4MsRtr3FjZXUono
V5OrPIHLAWFmwx7whgj8hoonZZCDKkzYi0LCmVc3TY8Gyx289mJn4Jw3Bql3XX3r
Hpy2cAIIYM3PIDSmprNIkX4rHnSxvkveWHGfOKM1WyAZHuKjPfeaBelgaAnREPMQ
FaIDk6y1uPW4iTr6fw9FGwtd153+lLJbcR3lgpsNpYQshDdMtimSOJZasrlV9Ise
d6IeAypaAhK3lv/TunUQ8H0tlMkdHx4m4K8VROc7Zb0N74CqDk5uwRE+4TRiiYxv
uVCMFhDB5rfjOMBUisLv3Nwat3l12Z09h94kD+NDhymwlef0KvpbPIG0+l1w2Y6P
xrHeAIkNI1vI1Io3Jguaq1TTHcT3SvSMHA7VAn5XOyhE+as2Lg+/jmOs8zsVr3fS
QQFljmTorpNCj2iQt4HuYeGq9i8JX0GakLkY7xr8N6tYgek2v7nt1FOHn/za9RJb
9q/ZqZ1ZHoEkj0yhxsKjqBdR
=1wm2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '07c657c6-43b1-4ebd-a96f-09f39a90d31b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+MSNX9kBp43mhm/IZhK/viIeDfa02tGrivd1m8CvJGDCm
L2ue3BGin2QRZ/ygk7Sq6jfTl8HiIskQ3tMwuuk7qyqNzoQSAtbcPsd3Yu+8KhLD
XiYExXd0yySsQRc9sfDMbWs8+XMmk0Fu4AAWIlI5dXpMsRF5kC0EuxqTWxSTWlBK
ycLj54DQ3f0vxKmO4aLdYBmmxxo1qhb+SkrD3qVUJqresCJkHukkArhHdQylUHnp
TkCd0+xK++DLuxqHAnbyQ0g5BYreDlsHRJ7KLK6p+I0jqQ3hnurdqYku9IgcfHJ1
gV5ejxevW4s3G52C46d0ko+6zXqvfxWEgoFxMYiADqW5eMj7iBaVgDlce6eFktq3
KRUCZpM2uFwmgL91lD2PSf/Qt1pOM0GC1AORUfqY94DHIOGiIV8g0eSyrCP8Z84l
vUFsex+V8ue1k6ujys4Ie77kyt211OGWbzIMhtZmPwCTRfskOR0o5ilerFOLNpdT
G/bGV6nvz8k74Qruocpw63Yr9lvZFmF0SvtnRBFaCRNWOqUmvH8vqGBRvtxZreds
ZF99ydxsMlpBwXfHoTnsl37+zIp7WtzAIV98BkZTORHXdnQ1Mw1D71RA4UKhcnuA
NScXTXh18H4kikAMgOGvYyaiio+MVRh54Lu5BLp+WaQ8rp03D//5qxu7LFWUNf/S
QwHS06M6nfDw+usqQdTeqABmaOXIv2xquIaDn61XoHkrYJYr5yOsGEtGkTZcXPyo
DP0vfbhHw1k8GFXjjv2udxZ4nUA=
=RVns
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0d4898d7-18b8-4354-a96d-6f7e0313091b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+OEcRD4sXUnKDQwe0jRaZ/HPxm9r//Mjn6D2eHnqHIQK2
oYIp0LBUE1Gq5iYaoUwQfjG9OVR85krWTFfrRGo3J0Ai9+JjkDC/BZPeRa9oeoC8
IxLwwL4NxLHyHIVIvDDS+aOoQjWUv/n0OcM/D5x7zCXc41NGIfaHjGncue+vteUS
h6pgb3fVQE/w89DaRlFfV63EkpMCPDMliA1oH6WfJC/Me+L+SxLn5v2IcVcxsKpU
CzxJOfSBqS9XCZ1XZPr7lRA+CsesmM7h4ct52JAihNYzsAnGDTCCtbfrT5i7o7Y9
hV8uN8RvYcr+lnbTOP9vndX6C+ULb4PU30alOLSTAULqZs30CovSG8oXYHu+O5Th
H9cgAheS3TqJjMyzgsj4+Sox/Jl1EApBpH6tHLcjwkq+gBFOWnwMARga6pr3spvs
poOqHfjbBEQaPoETkMqUq+Np/AWy7BeiJlJ/nQkxZc2h3oZuQ7An5AOhFgm5YjHt
1dCHCCY24BnqXMIyFnsvwtMX4PRHvWMCEz1wgVUZQvEpu+oDqP8szPRpWCx6xnb4
OT6TzFrPqOsEnm3HQjmVQJlZRiKGjhWfmKH04ALYpvBJmQ+xsfjBijeK24z4t77F
EiDGIxGtvFoFrXgIsQoxRIxAFERGIVQNLq5tEcTBjP4RavADASrA+hXTK5iAO+nS
QQHeDGjjuqOBKx/ADeQXIvhc92JPg237ADw9cDtJaRyq3mSFB3xjGMrrDfSZNlyX
Z1HIVhVrpYq2ohfC57tgV0Kg
=Rh80
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0f95bd3d-ec02-4152-aad0-7e974b4a6004',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9HcGaSU98CPbNLA7898dbZIjHkuB1VvLu9iFUVv3ph+Jq
Yuwtkl37JWQRyUBvYbPgIoP69N5Z/lyLvwphZolZ8um1G0zmiMlxfIuzbNGi3yxx
uKwtZ/dBymsU6/AI0hwBQKb3xNGuojqxKfbXr2fHwsyLODNLVtjMmFLh7+K6Z02o
EXyPmBq+nfqUQ/z+dssHotARt8FRf4d1IVnczKq8RerNTEQ1DYxA0pkQ4UXKIjly
wZWu5PsRzcI6f/7BMXi6xz0C5nXS6Kc5VwNexXhqDBLS8CK2KV0UbWrfn9DEHaK6
oA3RB5Pfv2ABuj4Sb58+JYaYgxj/3FGGcXVZZ7EZYgQ26XV1V+p3VyJ2RCzwyuNI
lPfRCsO7A4+MQ8oof2XPpEqY6UfsZxADLiSSbBj7mzys5YG64gyUqbMEKS07ljeq
NBr8kwlpmhUwoTshlUzBTKmJqjzVfmBL8uLueHiaHyAMkOulimoAJxE39Rc/wKHT
R9je3YgZRc4m1rUjazIcPrY+JNkfMdklSk05pKoEyob9MwQw5/fttFuYnWTZvpqB
djel+VLQsI79Bg5z7XXT8nGD4CTHsQ6ZD4iNcemdWajz05x9jA+yBiBqlvI/sJXM
tmT774eVx5b3D1uJEIF+ajj6tSKisHF0ozd/uTjqbcu25fN8JM3nWceWdpNhk1PS
QAHHvyfvUCqFqg4c3icRluXvRe6ROEKS/xYjClk+xGEkS7sQIviIcQFl85QxGnlI
z1rTbX9phVym59zVfH72IqU=
=fzyd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15d13985-87d1-4ff3-aca6-3e855110c97a',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//cwQ59tyLSx3fVEMF1kvpadEypEYR8mtHRbLhEvAddqO6
zgEle8t5CmlpxQNP+T3xw47fM/g/2t7L0xcYJRIMa7K41jpZJznyZwRBxsITmtur
TqOohOsQKFNeSPKbEqccFGuGcPlpRiBBd0fc/R6yEfmHDp2A6tePr/GZD5qGujuI
oS+E7lgpHow5j1SnEBkjBmgthaf52Ci2QXgRTk7eClDoZOYCeaG9fZa3CkM49+2E
WAVKdIfOldMnsdWGTEiuHar8LEn17mRWsX1+Q12YJ3YWgnlkIWkb2ZMEh/OU0egX
01qah9wDf1kxVqmwvHtt3qcrxFjPraoq+wfn6QocEEohoD8mpQqrfnMMB3H/UwAF
u5gGveC310OWfER69qu2WpzRYtg7/2Me63AmHo5dZ0w0U4fDrc0NWO859HFpK+jv
1H5wWKfcCcqrl3giPSszNebYEBhZLsoc538rNlKZZ+TzUKmklo/br9VUMxHco0EI
kfX6Bjuexgro8RKTpGKmK1c8fdb2Dm2185xzMPtpAbPs72mJABOywqUQiLTc1Dr8
7FPhOj0meLGyMh8QiLo8DpxQjKvudiz1S/XupJTPM4iRWmzOAfGTwfzfHtb9gtkt
f/ISx6aRkGrRU59vJuYJRxsMc4EtniYHJfvMn53eBtIqO547y6AZ7UJWc7HYNN3S
PgGFJtDit4a3aYZUgNqjB6dpmAb40JTtQhDIMXdNAHwkoTXEBFZsNLs+z/sB08YT
FBtcnJZ11tIOSaURTfcM
=PtYA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1c0e7899-bd49-40c0-a39e-8068e60a9886',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+LGiqkO85Tsfvn6RRgTn+jItwb94wr/nhNWn+QhA5V0Ry
jadPllZpB8c47dGyPJPrZO0+sZjUKZCmBRm8HHGIKtRbfhC+5yj+tQ98PrE47ceG
LsOAn7MwDvCLDZx5Of72fkHOJzcxWihsQ+zTGM71Zu0LY7G1zzChYPQKqqjA+6+1
rn3Bg2KOp+h2quaXhMll6FexJYYpPDpczFJYngX4lo5CK/GOui3ydByx3liY0M7X
A2uiN/vS/Rxs4bB0xA/7PYNfbtTtmrCNuOPninPEo9fHLQYOCsTccck7l2hshXxO
As8yyR5WN1B8utfbNccSnR2Ytfu9Ljp+wPpZuBq5exxnS+bex6dCXPiCahjZc1eT
woBkIxx6agHLO54OHKRwPCAYwM/09RXrZR4UAxz1YwmcPX5BJ8vBdgnP5FGgzoo/
lRsahYf6S19xaFxArd1LbdcKCakJsma6I/WHY/X9d+2wBfSCyrUr0KRSShAMrVqd
7lQARvLQxKiLxpcRgZXxxpibkD6bhTRa662BY62ZNIoMNSVNUEd3faFo7TildiGE
Qb41dzsFbMIXwdH+UBgjCCEf57tan1BAd1yBTX2aYtyY6rKnpEGX+EFHQd43+7xZ
mMlIwhdWinXs60WvJNkOmPEDQQTz1hv0v2DGCiXXuKbI9qbvYyrRmIjrJZmGvNDS
QAGjBbbcjU0WGxjBWvVvZ+FrhcWfarOpNqveMjPBtwS/BfOpd/wX7ERb9AfDDUDg
dWbvcTIz3verfaojhEokM9g=
=vdC7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '227cf322-8815-490a-a1a0-3320bbcb5eea',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAgiHLKc9WKU82i7GZ2V+GlRUJeNYKftKHiCSbB/iustyB
rVry1knCFP1E+P/UGAHtpUbJ6xNKwRM8gjgFc27vJQMBfTP3sBY+M8hElmPrS5AU
gQUNjzKzGd5hesBXJ8bze7lUz7bu7cPmh/58FP6QYXsKpjEv5f94DrakSqi+ohMa
KKaydLWTxA1PSVUH9tpHxM7QxTlOYxUCNoIset7IwmqF+SoVRAWD3mWJZB8rQmCQ
dFWHDm9TVPKmhnSZexFJpQJdN1hN973LpSQylCeH4KtB8sSB7LEO0v6TUPfYWslw
/Glxt1a2k/IsJURIloDzXZhc0knpym8aXu2q/3mXhfJodGvkSCQQj/wwA5C25Q0z
WeA/e/OB0boBC7oCiv0pfq8/CTL5JkQl83j+bDV/IT0d2NX4GmqkMtX8l8YgpXAR
q0Fkx5EoiO4Lmrq3oBuLFFmVwMaJ9lgSS3HwLnn6AlBa59oyBx7DnQafeTGZF5Od
KdbsfK1g4FNXgCoO4fUIMJrG8f8eVXdXi0EbqFNOROFRKHXdT6xkxjbd+2vWTXhn
AJJi3wNknHekkB+4vGk/N/Z9bHoUBh2GRjsWLfNL0vjDORJELH3suE9YWwwNE+IH
YmAGdPkGWqESfWu8aA/nZfnuGiPwo1ZwLhDaI8CDOH4kjOTg4yZfzLPdyU+JiJ7S
QAEjueyDX8cU2TAQ3WhAzndMS4oYxPXpZWqAcK6pyJuvcy+5jb+QTevgBGmM+Mke
2YN0sbovYnjsrhydfkCbhTs=
=Y/Q4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2c54a4ff-844c-4f82-ab6e-917eaf180d24',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//SsyIv1vy5VJ23ab/SQPkULNawcJ2Nyx1Rsil1WzF2p2N
HpHxFUACG09SoJKIdMwC15QVIzOP13B0UnzVGwsY4+WTm5IFE39Bq7Ppvpe5lhZf
RGbUTqgF1Fns/614XQBX94W1L9FjuBIx39bl1fd7G3fThM2TDj7LfoHBllJpuNnj
Jlvaw83lJe3e3FLnXmpiVsiY1vYjOlohMxwAMECQMKwDvBkBlQS5szAZnH9b7dVw
AnHFVgHC5AGnSfDFP1Hu2YxfbPD099U/sa+vpMf91/sCbFkacGxq4fI4VkADQHm9
HVP/Hu/fA32U7wTTyGb+1quaL2yO8nmssgAxiHNTpZygKD86e1msiDD7DfRZe/XZ
QR6JMdD0Qeyg+ZiVIGY+24vw5k5b0RmeeqCBBjLhkGqUrqgwc82KgI4BV5+sU3PC
p1QhmHVoWCoJsxgSuHZ8ayFjHBMhxeotfrx0iq/O7qRtl5lepYF/G32H7oDUK+SD
DN5BMhbvX1fs1g2V8Kd86KyiEHFdGr+53xumpb0Y7+A8+m6fq3sWYg43TvUFpse1
QxyyYVBrxq/XgV1Y+H5hE8xE57g2vjp9p5MfcXUpZ0U5nKat1w34gVeQLjnYNzHX
PxYRjcmKp27czxuxlfC+XPlTAG0U+y3MzqX9T0IQGcLu2MznCXvsYi/Cun8BejTS
QwEboRaCEDz1iNtApM/NYgd+qLc7iiEmb3oE5R/QzGfDHyGNvec1CwgxNRBMESwh
itDJBbNe4MrfL9Ti/MsPlKHgNLg=
=EVmC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '32f96b44-5ccf-46f8-a3c5-8ce1874d560c',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/YXnth94vSjV8ZrDuPkV2sTNFHc8yM/sd4huP7wWhOa6B
EYszJrJi239CodhMGsO2Dcn+48IPwIJDfjQD3SDQ1/lVVfg9o8lLs4y2GeOBpTfp
01xf9K9nAHkMSSL3JprkyKjxW+Cyjr+6/sLOx4l/DSr0V6wZAth93QVdEHev9QFM
eFgfDw7Hq3faLkjo6T7ZaQ8QPOENiRkAbRi+ix0EH74kqK/Ut9jBi+TXePQJ0+dg
Cx1ee68axa+BFuOOPGMkYOaJphoNnT/pdolBSbkqIYZmp3lz/8OJiaKCQFztQRuD
gS3N+lR0ZhNu73h+iCqRousxXoGwz3n2C8kseAGUfdJEAUOCLHI1Og8nkxv3I/c9
Rm0zDxKqD2TBsjnXZnmNr+rnXvD9KqIO4Ztl1HhlrmfpIC1b+YVMZ1LdDZJSSkSS
wJk2ekY=
=9pe9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3c767072-1bef-4ed6-a2a4-0f91bb1c6adb',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//SPhdekGKg/JSLXDGy5JBdOR3Jfc00tRgZXj+3/uaQL0Y
8/CZML08yFb/73ldzx5RFNED1kpUbYfabt8oH89M/7XZLMdGw8SZRBc8cyzOVI+J
riAziFN4wwel0ARBsJznHtncwNcVaF+CWsrly0oonD2AWmmD5blNQ7m0nTTQe5zB
jEG9kF+TT3BcRZtHUo0+CzV48yqEVeRvkDFW4B8wPoULOMk9gr983w+5ETfH3EB5
5DBrwPj1awMPUXDNr69jbNcvX+WACNEJAxsU/lS/gySvxMSH+RrGo/3U+WXCKtvH
MHzyVBiLkmEyc1LX0j9ozaeev7ozxCkJOXjBZZFRHPRI2l3aDWvMQn/KSSbIaj+4
xw6Y0QRvYUvK+Jn20f7a5UkTmuxf33y1NyU4q3Ax5y5jBqrh3/SPb1szzpx5G8zH
IG5cBcL9dWgYVJIv6miULmXDxmcX/CLJBtieJoe/ONri6lTK3X6xs4N0z/Iy7LAE
ArPwQ1MqbksO90Ba2SH2Ra5+xgjG/vpzz8eIK5DKEgGifND6+Zsyt6JJIwqlnscg
NGCY/Db+PNhJaePj/rmyyrn8fT2xXUf1PqBAXl1gkzcmveUV4L/rK8pKXmH0yEvZ
FlNcQTESteF2IWe1y2PttLcLTKxY3pbWuhD0uDVgSS7sKKTru61rMMEEqft/OW/S
QQFff+1f7WkKcDolrnrr7TwfNPCDiTHJyditHrAt1O4U2JJ1nYWdZgnRa5R7NnKO
nm5ZYJyax1e3iiM2TVudopw4
=iqc6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3d9db58f-1abc-4a80-a0c5-5174ec9ce28b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAp9AlAzUX2W+sx28FDTlhG8r+JJx4omqdnM+Q73gRxp8x
DbN/U1rsitokbYvSsFzfcyD+LBGFU4FO/CZz40pd1MufuEhzK3d7vacBsMqzU/02
e67lxLA9P3J59SdNxMAFNwxxMy3QuJe72+w9XUKl1IsG8/fHbDvJPyuYiR4baNYz
yAaBfGtafZ+Mov5eDyepKPZf8dRDWlYWvJOEpybbJ5aGJUUk+NkKOgTSQuxM8AGS
NoprINMaGIbMjwkAtX4PK4EyYYvnhNFSsHX4jv3hjRMceuu75p+wJiQ6NAczUKSf
AriqpStR9bWz6e7GWxRlC+2JqvwciTyJFJNhSJA4fd4O5DVydadG1iMhPtZZIZMD
sqhsPhl9GCHecHacqPNSjCK13ZqvU+C7Hten5xtzxwiR/gTWXMW/8P/aDIv8tYAh
1M5MVXpOLTM09i/p0jk7efwKgR8N0oiai9w4bZnENETSUYOQ1duRgNiOiCBpoCDy
a50emdkP+DFo9e3Nepp8Ys8zLk75OdW+5CQVZYK/zw3KanyLtJsIw3WidwhE6Ji2
Sc7Cq7H6StuCWZnMxlxT6xU5bGZxY1+nE/j3w+U+2wCQ1MNuVgKc1AAeV5PIbFa4
JylTcjbqJcQ6WA574w3Ao6zOWSE3eRVDYvqO3in8VlPLYWvVgfHsol+9UhVmXoDS
QAEAqUNcbX3q8tOjbb2/qB2JxIN88h2hrf7n7uVSzw14bDQBjzB6GvMcqndwzr28
8Fu748HlvAs6EV0bJ6bL+gE=
=ncFl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3fefd3d2-e166-4268-abc4-b052b692a5e0',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAky3Azt+sUGTwzbjNC6rdUG0B1nybHMcUZvumHh7U5V1F
arrkLys6kzWJBMNhikN1ySqRMz1kOmgvLHMX9HjlDwgAddUevGv1i2KV1UXj5O1O
VrfZjL/5lmVyAFRJfwTZJ1uLO0Afr6Eufdd0230UG+rm+MucAPUGCnTuIOPkR6n4
nORowvfibCbg5WNAph8YebSjpNb+2ReH6DeBputH1uo2L9LNHxrZyAZVC8ubM320
S56JpC1Q2Ex3738sjXVZE3qRXV2RbmQtIweZYupsaZWEx9PfN4Ecs8YVLUks7Y0I
o0XXfzovdFFTJ6Ub3rb/q5pQcxy2A0HKohberzHQLDIZCFxH/KGPNvfyIkV3Esiz
ZG9YRckYsMRKUDXXNrqgsaTFPtj7TzRGJPn5YNPTV2g1NfBxj214REyNRFTKg/3N
obHTJER00Xb358tPFDlljvziWBPcx6N3uZ3L7bIaSFUH6o+xjp6qnrfSfN/fwNWj
8JVriWxLZuOWP63rQjJI54BiN70ynN+YnTZntDNvGSQzB3Awr5tWKElN7UfvcXND
8r7EFl69cdjztKYtKOrhYSPcJ8R5e2l3ODXnTPcVD5apLEWNK0Dkolc2YFG1C5+E
pU6GQukEc8NRmjcEv8KeMMvuA3xyEtISlrW53toji9Tc8cDmWfHq+LO0ZhE4kEbS
QgHz7PeDrn9Zz1Xh+4d7US+hND3qnhYvuzmKaT8SuPMDPBzao4KRdwsaNYmHQxBn
/ALzEHBh+pEoaZvkqC1btKsnJg==
=eL7c
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '401089f8-583d-495a-a009-d300e9da3c07',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//YysCMJlGSqUBP1V/vUrmZFuGsPjiIBvEGarr3mhBx9UK
TFOJ4Fd+SiEhHmY5SgT725gcfnSitmiLo1QdRf8OToPw5LOGKvg9+AD+gkTQt/uX
RsTxIY0Mq0hs1EeNBPmio2QSvytPAGKGY6ULVNVfeUvSYwU+IR6qZSSobDWv+KdI
mYi3eb0xxH7z/3eQuQdpUaT31WjHFcmb3baa52j2SoaTZcQsOdpd9cue3eH0niyW
ZKHDCUaG5pVLXXbvFMvEmXI5JyvtuyrOEFFc+o/zGxe56musRiPbtLkzezmzqSkB
ZiBZfXrRwvBxi8LNv4rIloS68gZeMtIqG/wbTsbjhognlWhM5ZSNX7JHT8u8D3UX
fo0zxtCjc6sFhu1VmBIFcQZsv/fkWrj8LL0lWOasYa4ajV3mvby3sXeLbH3q0Inq
o3DJjx8xw3Q1uinPTrm/17Vbs3UQ7HQX7LKT8caTOmb/8lALX2SLOxfFiOna4b+W
NZwQvE15z3EoJa1G+s+HSVgMbQGuRBNLe99hjTr9Yvof3BjcPR+4sEVg9e+UoCQh
QjpY/uFhzQqMQSVwnAbtRyRqJR9wzVfn/7e7c1RFsX0QSY1HOvXHHZsJsLJO97zW
c/ckkpNYmZPYwKK1Zce6g/Id2q63l5uevgPn4QZCA1eD3EwaFX7h/kq433KtL4XS
QQGz9/WHzoVMizu1hooz9Ses+vMC5GYH2YWXfGwXFsWez8F5Y8IqWioGm1/M6INy
OaLRjFf3HvTiOiOHLktPLPk2
=GlYI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '40f64691-fdc3-45d5-a289-c9c111bb024d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAt3HU2VpU5PG/WXqn5BVfZQp+fjLb03b9wTiPL2lPsn9j
SILaeQt1N9i5lT4fOHlfxHS0ZR+a2cGdlyfr0vCeKBNPEUSFjCpaY9jV38yAO4CM
iT2exMA3LZFINxv72CYR8LXhBtJ5uzu9+wn7EOHdx/5ZxhiJx/MmQH974W85wZRv
1+T25sPRPaEA8S9kQ/4EWrpVA5Hl8V5M2ur7fMmspVbz/msVxrFQFexN0qII6/bL
L6zr4LBpfRwmaVFbrpWcdeOiGg81gbj+yo5xsG5Z2lJF47xQm9GBGoea8Xbx1X6z
PE0/GUTVyShQUktEEW5NZi5ifYL8df8hJvAKfqEm6aaIoCz8gcj65aOv9TlWPPem
FVnIuIkwEfK3dSgBhLYjlTtI5/F+PEkFAjpj8XlxzIoF3ANqD5lrGg6suD1KY3EJ
p0FPq6XfKjufzN1QIND9aUHBbJ3rqlXVur00KRxhOnZUFhydfwtkBippAzl/CE9D
vvxBdbkEovWNvSE0mh5gw8cBqspjBjgCz16ZMjYjhbNDtEh1JJQuO/8EeC6cr31M
MB6nW2tNxb3cf9eQaasHuW6RzcypJhHSg6MPQ8rmaLhru0x5HDejFVH4Set2EQI2
Enq52DG+UHdls+eq6sxzVsxuSQiJyzija2e4lKP3+BP/d8DoTd6MU//BFHGwMAXS
QAE+5hhn0JFkjvjdds3ND4UZeOmOAayu8+R/xdThGt/SXioY1PMCrLVZZJJJdV0Y
Cb16ib3e7bIu7dGmqvQIs1k=
=INC8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4814906c-196f-4653-a358-244180bb7ed0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//W+y9hnqA7Z09FiUWBKUbIE19bz7LKZv5bNWMTlmTGx5v
7jYBqcuzUEV6A6BlAqpG0+1BK0QuAD2BKNiWTEdTaXy6wOw/tTPA8y1lG7NqgBcE
omcFmwvSsDqZkhN8TUM8PwEjPG3jDDkuuuFuRGLwdYPHFZuZ36RieUjzgAktrzT3
GcF6RLJDAn0AAljiiG80mxLkJOzbR7XbA5FJvd5HgblkJqPE4gAlrI5T8FLVjg4i
f9QMpqqmYbli+/z/KSspsusv7uw9BYUeHVhHoma2GAoM9egLEUSgrGPUYpsyALN5
4y2Edd+n8MCS5SZd+dlNVNVYbJKlK50VwoQkvCM1KhLU4UnyL7xKtQooZZAzfhfe
m5vzr/gJYDxCCwtnKUrOVO/8RTQ6K044PSUJwePKHlZptlKuztbMD6JFCMPVWgTN
Vz9sEASejei90Wh5DwuNWispitQQa9eLX0YFfvlGM5HGk6J2OxBh2xlnHtVUaZJn
UafIfOtv/g1GI7/Yc4JkGrFqX9OXfKrpTE+0jZ1oSpD6sjzLLHTLGTBMy9tdwMVz
Xfs+9qEwbbIUiTUfwrqF9ab/PefQf50SbOkbruW+0hvIsgVahdbfgxhRGZXp2cP9
SGt7PIErzzEKGVuKI06OkUHMvEqbRKbVHrouI7JIPukalM/zFTbMrqQ7Qtt8VfbS
QwHDPKQfQUapWiJgioH1tPFEk+2YuMZ8ASi9rn8lTCWCN5y1QpEU9H64oz23i3AQ
qHNmNlmeBg9JVfnHK+o6I09yuC8=
=+pPW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '49aa491b-cc5e-469b-adde-af499155e79e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAjPcn3yEZL1iuWGJGaIZExxgae9L7DI6ahvOvBiUhjG9Y
603JwroeV5dEnYxXUUdRFtfHbYGigKwp0ElU4icYGd4k8FO8bENCN9RMt7o/TXWX
U2SauCDhjCDlnlT6ZxP27p0VaYXZJzF9R1Tw2T5QgEIZmsR1lyUFdaj4AtJa7A7S
9CnwN6Bp3C5Ki0y2H9m8ePCgF7Zg/y8i/saX+lM29tU0146qMoM9Eui0CGlgCbfL
5yhuMVKMwYvJp7d2HvH0Kln/KPCBQdgyAKaNRW5IX5tz9wdBxyW2OlQZAQs9yS1h
l7C0ClcYTcwseNhLJUPd08bO9iFt01IcPBUXYmNxOESwWR7x0NtuUFy6D9k7501v
zhY/oNNAdS4pKJ0WPW99zTG/bTPqV3HW9lAC32ZLfftu5NjS4DfLi8B1NZiW2hmv
V51a+MVrRXSgR8TbuOW8nfNB601pX1RE/uwMyIiN3Vf6/UFmmUflKDhq/Ycuyjdv
dJxiSfl/hpHQg69SPIUVKrK+Y9tPMvOAwogVK+h4QZtoIZxw5MQz6f9/hbodJheI
T3HBt4fIvPh+FoeZIIHQiXq5pbChNwzeB9ejCtjLL1npwV5iaozWQxmEUb1ub/6c
eInrMgyzjtYid0lZw9HUpOEzvBIgns7xjxQXHbmi4vts9dlEj+V430D14PznlbbS
QQGneibCL9OsUtzJ9eDwWSNWgNy2Cz5CDzWbofHYTyuzCY4+FTeI7DbkLjdmBnj0
9e6DkmDc2aiEBtOOIEoJEarM
=epYW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4d1a35f4-3d6f-4c87-aa50-7f370c2a8535',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAtHImj0GuIy+pzrGpdoZccWLnFMSBcbmk9qk6yz7vgG5x
7Yn/pMSn+GEZUd/6zNvdq2aIuZgKjpHdA15PAAWnSSspHuNEOtHkw2Aa8q35+zv5
oU197Lh/34KMQIviDf0cTCXfzewfCi68JZ303j4mQVfZsF5Oh1Cw5AEWu3N2txOD
GAhcVs6E/VHWvqZ4JaFJo42DaHpTCV9Wq6l85wASN9oBIIqrhbGlAyk9WOfGuLgw
Yt1EyYT36EGfMJczTgHwwREOKhpHw7FpZPobPM+oS7G8f36QtrJqB2hSnZawzp36
B/i4AlsKSqnZ/9lopurV2o9LNFfcNw7G+ySqgMnCbsuF5CwPd80jSkaT8at5Pmgy
0esG2w5YhST+Iu8s+l+p8fxeRFsgvBjSIus2H+Z9sYJb6ZXdp4pP6mbdHp8TBdi2
HrSBXzRrKNgVwtZrJnmC04cRMIK4IA4usBh96SoSxnhy1ar8Rfc+GMsf0ovEm5Dj
/Nyku7K1M4Zey9zhIqhPsHFcsQeaoICrPVVnjjr/KyYWrPR6Z43ml2iqkcqPFz+D
q4qn8WIxd2k/x51xz0H0osUI2qxmWGRrNtZoY0tVaIZInLJB9XMrYLV+W/BWXhTW
l06QpKXDC5H7qYoQWUiHg+6wPt0BTQiQJfs4t8iGmt8WrU18Djwx0hxFyNNyZ9jS
QQEKovaZdG17rq0sQmucuywcMSnzv4RbVHoxVC1HlTPySJGKjTb+IxoeqInOsVk4
ghZIEJ9Fwai3g9m3kbdSYsvT
=XO+K
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4d1c8af0-8bd1-4f39-ad8e-991a19fdec96',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+PTyzd9ZRyL/Rm3H0U6zBmJ2CZfGFbzfCkKVhPVKoP47v
yWp+sU2e2YYS9N42e4FZsVnfYXtOPicDHLy8S9ae0dqPT3JctFjoOs0+Rry+5TnW
cliir+0TuQeSt70OJgg4dPZlXfUHHnSZAoe/6u6EI8YBJRpqX8msHQZZU8yMpU7W
y4KphWqsDpXSIfk5RSX531E7g40qXUL84LmDiYF5C4z8WhSpbulREHNV7aX4R83R
IaEV50QnmaLQkmyxpda3oNNUeTlL+tk6M9cjr4b4rjiZujFKD/XxyXMjrQ5tBOqJ
l6cjia21yqFsENOqa+mJ8W3bQQ38ly6/W1TIX3N57XcqYQk6Kd1fDaFb7+c5Q/zd
IbFx8yjPmvWIkeCGKJvFPVKnGf2clroMnza24k3JRSQBFQkbQC3opH8K6Ht6WX+T
nHkRpkf0HQgmlw7NF0copponvSM7O1ljn9Oqr0IMs6V0r8Rrsvm9slN0gcQa1+vj
+tcg2E4QGUNsrERrQvcxe27JCKpcyFf7lkWg0zUyD+aNg5lvKqXX4A+J/7xHvWnE
Z2FfZNL7wAkIDDhB3TsLfGHhu+A2Kual8xzXnWbH0tiLEkif/fOYKPHoOPw39T0U
mqNGLimqEDbTd9Om89IR8cjlYiiVd8bfO1fdIrbQtypu2Gu2OVmir6MjodhM3BDS
QQHgu+pyR8Ed75tYtLgB273ChHWfW7RuvJfZgBLK8xJ62U7Og89GI7yT5yDNSJEZ
5h94lVVulj0KhC+bIE03BRAp
=sv7w
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4ddf57a1-0f7b-486b-a802-9122a8c353ac',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAsJu1NHXoxmhOTB8fEnZ7ZXwkUFxy3/a+W5kyXUSOnHnm
s+dPqKBzS8JuFxfdnB4xqXR9O/UFICvxpR+A9D5o9WhVcTdltKTJE65WJByrSYbn
wqYc13jroe2iYR7zl4oGMhCEtzE1mInIAYtRNqiDALGmXt92/gRkzAbq0YK+Toyl
jZuQ6Y95Og7we7XSCrrigiEzQCLIaVIKYaW6gOYonVnM8EQSayALMD+7q5EplHQj
RkQj3Cubpmk9x5NR8dHkA//0uqTye6Kt+2bsXzOW57helUxicIUXdjuMqV5mno23
NKUdk351QgGc30qDqh/NYaEIx6IzDN0w6L6fH+V7+uI7ANjUoUEYkzs49HHW0hKr
pi3ClMpoTN7qDShX1NLFGA+5VxFPafDLWZ4ryn1SJF5lKFDPol8OLXjh2bF5gxpF
YHh6ztrYlFlRxU1eyORzDzeMfU3lFCHqFN+ZUSdnMcNhJzyob9Myejo2Ftht1sR3
FCzN2gtYPsMOlJ19GBPkbpMokthroIKle7mV8Zf4nWs4yIc5ls060Cx2nI6cOelx
eaS0KJju7z5tsOXyO/FZTt84dbfxPfusSZRRh5k+IF3d4cPCDPgVtrGp8by+n22K
hrcZc6uZYn3vFa3nHaB0WX8Yq2vpK6jbUkrWAqdjBc+ZYQjRcIHIm0JCcSifpH3S
QwEHjeXhAlwQh2bn7o06wu9F5X5cOkZFecOd1EdSCK3ThbPks76Tg1+EDWlMZ0r8
gbdohtCtcjFKhVQ4RjetO3xob9I=
=iiQT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5780ae26-cb9a-4020-abe3-93a222dd85c6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAhEMzMExRB9Tgs4lRo1nKQtP7qDfCKB3gEMJYP6DSn/Ip
aewOnvp4H96EuLfrQj05MoEB2Cnq1ZFUoMvi6AhA6FlD6IQipTj4ylXIEp/fjLUZ
aI57OVK/5cbMkRtTfWlxwYBpLjirHPikwPjV8nGZ/XIzY66ItHB5nN6LA/6gA0kF
bl9e9RAGBL9IeqY3HP50lJ4PDaMxywPgIjGprM2SkmTQFLMOxCA3mV6m8GIxvQz8
GhX5OZxMcT4oBxO+DGLHS4s4ge+FoJR9pYH9AQ8fakDXJ2ySUY2Ql5N0dQVdzn7D
xR8DKvZ0Q0aUam4CpGe7nzfzS4N/uHPG4SqUlNZl5IgH6YCh1Vgt8xP0dH/d/NYH
ibscyd/+39v9kAGKi51/7p8PK1V/mGgkdC6k4o1XXWr90YyeHwkAuQloET4ZCGiZ
f/bSi6T48SyTCDAMdY4WKOPS6NNrOM7MO8aOnMx9GgOp7WuWqbKae6cz5w1KNCED
Rl0ZmNaib2GdrinQwypwQF2f2JTfk8gOlEXJZrUnIfLEA260njW8lDLykj1Bsro4
7ELuzGesQgeKMA78Reb70eDM0IMqeARXt1jESYsmOuLsRw0HzQP1LF5QLmWR3AME
wSnOsjTJ1lgH+rBdTI3Hf2ecQXBfYKUJj58FSDfcWGTpRkoGkSuCdZLLPeiFzujS
QwHcbYjD+fQ/lDYmv184HXtZmvZ8KpDU2xSf3aJBpRIzRE3JQmrhhWWjinNBqdID
pQiOq6f30cD3ysxcmdmkVEHi1tc=
=Da/B
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5d000c7c-6edf-4c3a-a4cb-2dac2b0069d8',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+NFE7sId6C+mWkz1qDuXTQJZHblQpVbHfBJO4iLAq0oZe
S0GfhJbnQDf6Pt4SJmIo4bamUJ5mkfj5miQV9OZO6VhAASLlKo3+3Ih3NtMdruUr
c3lE/W8qrRLM/Q9Em1O6bH+5lIFkbyGRgPMZI8dEsqOC3N1Au1N+sAFmqdudwHy5
sbkC6kvm9tSFbUcoOdmn55kzciSa7Rp7zf2uDOYtL4J45HgF1mxqcl3NGd9Tg3tZ
BvR11xSpA0tQPfc4xEqA3B0QxxPsbJrFq5q3A34voFK9RDwnX0PpPmd8bAVHluAO
6u5xNixzG4PVU3OOg6aB4OZDNz1Bkr2kix4kmOOUMdJAARgv1om7USzZi0FfDpKq
i9Mlqg7Rj/CJvZbHNI5MtOISkC9M9OoYlJBZRMrwtHE8MgNnqzu6++7Jm0GQv+Jc
bg==
=fbYF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ddb6a8d-d975-44bb-a778-1f2e66ccafc2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//bdGsPZ0UskM2wECfOQ0qZzVIjcbbdCkaHSYKLQHCxBD2
Wgl63zekO/Z4XFT1oOds/PlCy0fpyLF6LNUAwG5xK4mkYjGgaHt2inyB+zKGVj5Y
vOoqVmzXbaSnbcZuBsuq2sCzHSbv4eTxQsGL/l72ooT1FS6W30lc29tzL99HVjjE
V8knHT2OT7P6ShJaRYmAp/rZ7vkAW0SpvG+8jOsaDu9weWeQntiB5/pwWII9NTS8
p0HL7gfZh1tv6PTdaTQpMrIhvyGqlOeuKAipwl6Tee9nyD05weJbkUe6POy/dyGt
dhlS5wRM/ptIZksu6+ZzCb+cHai7LzFD6DduDWT7BkX6Sdg9J30ipmm4g4c8Q58X
MQsrGundOQn7/pyAydeFi6wxu9VnErH/7aHcOKuLOnZSX3luszbLRH+GOzN0hewc
+RfIsoaOgwupaDYJ+sN51Gkrr4cNAul5yfdq16l7+/+yJtdy1HUAApfuIFj3ppng
t73i/Fd6faPXCppX73x9Q8nY/7drU1iqhKMtGPP1kR74Zph/7mqMwjtd6KQqRdoE
+M1HW9RVLKNAIt4jVCaY28tz/3EFVoA6deoQ5YtwUntxMq15XL1lA+SB+iUTxwWG
DrbKnBtISw0tB5VatuBHTgcQOaeaQ/To7MEIJ3BOWqx6S/LWJM6na33I5Cztt5bS
QQE1IBe/TX5gzrAEGdd9wQfPE4ORsEEKKLzrUkBfXcvEgvHUWq/v/vQwaRSkg74+
v0kMVQH6vx5OSvPdpcDqQBIn
=bE/B
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ff0409b-adf2-4dca-ad36-3aab9b3ad130',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAlurcIUDzG+dAmJedZXxbZEy7/kvax1/dcJmydyk1t6u8
sznbA74n/Jg/rPbpKimvjIPScJnAFX8MCKZ+3ozV/2cIkUQnVWv3ErqLkcXUBDEJ
T+9q41vLtHDagqTQtFrDSDgaoNnGRyqLeUuD5mkG3jpna2vBGJszYH/Grs6Zy2mo
J3Gsv+I41jIdFQXdv1D3v+wuzpnRtCdxzVPU6HeuDlO1bw5045jhPwfqTdTb70rj
KLDC2yv6G/onPKAWbR1L8N7UKTE1mLY9sQs/TeullcYTChD+wUS3FiJlaPRIV8jz
riVvaZFHaR+Q7xrGMJoE/blRnQzeU7zUfKniO+TlQdI+AaNGwHA+E3k1P084fRrZ
oq2alhV07LWbHaaX/JAOusMKt/4qhgnXz5kKBtll0IQArxkrCoCuA+Ql64guCjI=
=aQ5j
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6d9ebaf7-cdc7-4f71-a2b0-6e5b79147281',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAkKKxloy4URj5zBFzsXGmRcIB9AGMM4iHyY2DHOwmfw4r
Zindlox4c+YC5JRlLHwtT3MFTNxPY4V0wtFgaVa6h48n0oDcEux04nwQzpEO7OY4
QFhJI4AM1FSMGdFsLe5hYa9Jq/7PIpVID/Thzir4Et52VuRM+08FWYnMv9Clko2G
c3KUQUmTuosX5LWLjSZbhC8VmyFL9I9FgEqxkutfvjAzTq8nms20JhSoMika/C+s
vitXArzTWgY5Vw2+AI9jwx329gym505evkHrb44OXc3HG4S9peC3nlofYoDaToIl
p+kTGVAaySlICsG3FnaPF8fVlbc9pdLnhXMcHSHymdJDAfH7PUggtdTsgVaGIg/4
SeAQdk4PRto60PBlYriLewTx7+P1T8GqWeBgyT8y5Nv+mNv18GKqT+bm8hLThsub
KDX8lg==
=bcVL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '711cb849-e1db-4155-ac38-54288e41dea9',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+PnhA7XSsRJUO/j0joixYB+tDHUUPcDTlxQTEVJEB5xla
8Cf/YZwyvaGAd+j1NOiAZcmvyPsRei3AyBLqIDajNizg7AAQ4SZpJ7AEsNNYflZo
6rfjzhQsXvqWqGLZEfYfeLMwiFyfICS/W8Pbh7yghXwL/t1liIPpF3Zi0iVSxrY7
2iae9YuQNC1P+omiolU1vDlRyOfxAV+svk2dPnbGlgJtKszair/OCCZu21yTiWo/
npxWQLVl76a0EAqP0g1okGiHbgTEfkRytJH4Jkm74barsJdqNuOsMraReG1AMQXT
rR4oPTgViusYtluV8xRWntU0QKCfwokYw80D9J2pxZibvZ28aUrsYDnyDRTKSZV4
SuRm0zRsUbB8p1ssmtBeWwhjABJew8p+Gva9YBMfEBLVHKdb88uJ5bYLIJu7uuuf
Kv2tc2ADO9ovTnKHwOVaV9pL+40OCNZP0UG980CazkcJUwXv4nI9JnIkLTEdOQTC
K7f5K2HmZzoh0ukzqLG1vBkCjgnC7f2Jo/6Un8/6nJTyFx3VHcFaGA58Bcl6SVcr
YRbW06Rn2AXQHG88GgdwD2ulaSVTpatZNcCzjLhpOcs+/NM0T9a+STNW/CotE+OR
wZ3tZtfFfCXE3mfCFjfhL90IROH2Vzl4oIxwcZRWRdWvshfQr+ZrGpIpq+12c0PS
QQGT9l4tiRJKDPS/IThxgU1LTGl2UE1twZjKKum1af0yRfdrjZHbYlc1XwH1j3Kf
Br81T5QWQyKx6WWPwSb7REjs
=yQiS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '77a03382-1583-47d0-a0bb-265eec4a5457',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAjYn+2vIyg+O1XiK7Sm+oRbv5/PPRw6pFb3NmgkFj4T41
YQhmVuQLsvNtb4P9+MiTjAfgeeO59ffOigjSeOpnQ0WlEIDNn8ul07GiWMSUo+k0
A03mclqEBNuvsjqgmEc8umbsfxyz6L7uyoyjW2UsxzfOb5zlT1YD4ob1i6hHsdPA
xFS5FoBe82mlKOF8mCNRWYuGYvBlVLXLN1KcTFJG3rBG9eSRq5HRxPvg9ij71STo
f3IDkFOpDycTTwT8uQW70VQJtx/dA/89dVHwveocovYwh+YCZC+NSvEmx3WKErtl
QoQMixyXQjH41CSGeCucXTJ1CNnvMVT+j7m8VGeWywfxECf4cCFpK09Np4uUXWFq
bMGjbz1wI8XXIWs/rppLwblx07r2kfZjuPzUYDygyEl82VDdyKCt+TghTT6QyiID
ShMDKMwndTfJMZCo85l3xFOXw87zhoHJnx7m3XEKdc4I5P1OTetdEBaRM0diXya1
7gdNjKmRWxLrIbGEDNEynfejdDgnU56McQoLHsMyvIuVcmlvu/GsPBYp+sK4FyaL
aaAqRJ1MJ0/5yNSiHz+NXHGCy+um9QIfUVX/DXYlz59ZTDGxHZULwaLafqtreO59
HHz82TH8uA+jaZeQgvKs07Kf6H4EY9+r1mjjObtTIV59YsxNrH7rY2iAZUoIoR7S
QQHks4CAoVmR0fs8H+CvDr8LQXQXkWofZ8hyNwfgc7d5q7QJRjR9MoN2Qwi2b1S+
P1kUcJvEj0idztulEI0GD8Gl
=00k+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '88739471-5caf-4c41-a23d-624658a866c3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8CM0L6xPsGfS9+aTnMkwIRexgzge5I8Vufrq8xByUb7kO
zZeAWJnLrjHTr6lQvMEEUhSm7OG9s55iFeAPhNWK1/adcogYDxk5c7YL2e0gVm4b
l6u9e5qVt2IbiHOaMKQXnevxADN75C6jGc9qRvPd/BhpgOeI1SzQbtwnKi6SUmP6
RGiq+sjGNs1j56kRelBo6JpMxDWrrlZZ9fQ0LF7C5cj7NlmaymWvNln60iObQeFD
cVGDahai52jeN57f8SEDkGgZGMTG/CtDjPy1XEfjOqPVBmeTRCI3uDydt2H2RYlT
R0VuZyarT0Sq8Q6st9ZyWltDAsZBDKcpv1k/3fiAoC1yzYiwEd1JgCSzKvWX3272
Yn0Ne7iNDNQ1ZmCDQn5+G2EWJ9lCCGlRJUkLkJse8i/+FM200LGgWzs6XQe2dNvB
ZYniQ0J/5/mfiGzCCOVDCJDZaTcmyFFFgPbIs/OEca6TTHmvUE/ITVYAa47ZIyEo
7+tye9E2oT5d2KRk++WPgTLqbr/qTn9jyqPz3dp9T0rckpyQfLNQJ87EBqSgGUcY
zmapt4l6Z1f+7ZWgi4EJkvppVO/uIvcrAZB66P6xOmnpx50cQ2BWmwSMd4j3N0Gw
umASia8I16yR3+4VW+JQea9bNUvOmhDTD2PcLOdpx+raDtUsdHGENT8DSEDzxSTS
QwGjerTnPSpy2ke4gBaTGsXhDRKBU509jSjXuy2g0owmjhg8P3CeYEtm5b+KX5wF
PyS/mxn0I6k0SR0bfUgR5ti72Cc=
=VkIm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8ac1c98c-ae16-499c-ad77-ce99c7faecc3',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//chhsjRjU5XZsInSwQ8M9MdBh8ronQ7NoXnqfrwF2wjua
miX9lKj5EeKvu7EW8IutfCwAAXFoGIYW2k1TlhAvqulFFdbTezgO5THH5WO2vezT
wkJXjoFQvw8IXypUfj4OgIpg/WBi29M1g6nxC9GEvDF1QniC70H8J6DcMeT8n4nk
iEojBfS9d4zAt55Y+BycaQ6IYj3IRa4qdqzwhUnbxD6S43p4/6YD85//j2Y56HvE
eGJ0b3qqRoouDmxPbnDgT2GhsGYIv5Ght3Fv1/HorZvHRDEUpn1O+lwO3rMIBVOj
DpNCQ71QTkfby0ukGthO0RFlavcG527xkpTB4I+t1YQJgrBf0Zy3qoS/rm28xurk
7GXA0djqjsljn7mttX3KZMwYgErD3CZSy72dLzTmy5cy7EGmme2SyPYuL5Uk8LQ6
Nu2bGvMPrdmsrdvlkVtB1MAR8P5yxj0jq/aUBe/7teg+EhKr3XarHAGXUJuVwmnR
6SOe2Ewg8XQIqmWJkt0OyKuGhu5FvLVafHxS3x923TX0iFu/I34kIA3bGa99bK6u
niXu/DdqZUYTDjtq3HF+5Tl1WPrH8Q9Md5OMOL9qLnpOgodGjC6TZJYSd69HC7hL
kq4nLxqcw5/KnMVIZDdtMHQr+LYZ3WdRcyhgYvTTt1GlHqpXgtSrrLABGLRoYiDS
QwHzD48gIcbPI7xSbGFot++KW6uchJp3q+EUWYtihm+W1MPTX7e/FFCBqsaJn3LR
nsKRTUbEXghtOAy+sa/AbfQmdrs=
=IN99
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8d825a66-7ef1-484b-aa0c-c93bb4a1eab2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8DX1cvmi2LsWBoLE4R7p+Y/V0HcNqCVeRD/059+5T/oHN
9iKa+l+czuVUbJikQczopJtN9zTDD18VZuUWlcWKQh1JtMhhl0uZIltxzDbrm+LE
jg/4Y3RpdJNaQQb9fbtX3yRDHP4TF9uoJ3w1Id9x1mj4ABSu9LZGUMW2liqfoYsK
3D3vXUseN5jdm3CS3c2p9Ms0tG7iy8G48bihJnWA6wcWblSrVA6XmhTncjBNTPK4
RKCSMgUmJqzR07KzaR1kaQScYDZ0yj0Pzw4wlWXKH3QdYMDo+ykb/ACHzs9zce0U
A2taoUI4JYk7nATnccgM/OCeeZzyEiO1qp99O/f6R6WU/yJ3SliGxjJruFrMrc5P
jSNfQioXH7faj2ajV+OpSTJUdG72fHw340xluKCcOkBURWx3HsD9f9wb1hmB95gt
fpIz04WF7AR+o+zZbmnrFIQba+aowmfWpSLmlgnK62luUqoO3njrKk3JaR3hdmUy
qRKtWMrtRFICHJ90n2nOmcWrPR/toE2psmzKLEGGnZm9jeq8WD2v9ih8MdOJvT0T
4Ifvc8wJxga8zSNa8nCPU/fqg0pwo1erl7ZAT8mvPSVhZD7fevE6oViIF/7emtNd
jl8VApJt6W3lnc/0nBeKjc6OULaOpNZgTDpYyw5ZoLYcSd2Bj3LopOvuQh4O7njS
QQGG5vZlYrgFMwIFysx5+oDsAvkpjizAXLUWnH8Hbzlw762a42R+QKAU7QOtsmNu
8pn1KZBcOy9XnXI2i064H1vG
=bo1K
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8db69452-7793-4e19-ac5c-8ba9aea37885',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//SjiIrGZUeeCU+Ybbli23GJ4APCHNZ+kjbLQpAXaHbnBC
eCvmRlsbA2GVdd8a59JNPE4FPG8pDuopbe2lXrxS9Dx3mbg+9wKu/a89J+4Bt8Mh
Bb0pDkIBeO9lpSCXQtBVuQIayWoTNN+NBDlnoiXSDUo6+NQD9vR5cB/kCQQIw4iG
lQgs3USMppSgRCF1O0xn5OwBktWq8kLx4zJ9LNycmIfy6ubMYBrXMXWLbtvw8fYH
xWUSrmT2FFBQYIRDnymh8MZ4PY/ek0qBDVQIuhDOra2qsCIHWmA4UC1iE1AwTO2i
bZ/IYtWemsPsxKMIRw02SJXCdYRU/6fMQgEcduFs/vCYF43Is1r1AfCipdTffstW
upZQtxUOixxTuAi3+T9YE7E6qxfVwp5XfMpoiCkwaQimYSsCehO/FWWhdUmW9vVv
/ihu+Nkbadg8vb5TthVm6+DZxJXxnTHs/em6/YTL/kaLsSLpGX0H8V3godePcoRO
/6w1aBGx8h0E6jl1hQJT/W0WAI3AcG/flUbY379ML4vhw43bxVtAgHKCeJ/uMBCk
b2TzMYdwCPVdDkjVeFTc+C86doviCMnTMQfZ9NYjCqY8CKfpqChQxxAMiGhRC8/R
mP+dtxlApxoY87G/ouV9uTWiO3R/R+rJ8/HE4RoA7xg0tjN+X4pd3eYWqOhiMy7S
QgG4gqZhdBsO0vDVekXf5+X46976J8Ji/Bav4KvwDL4XDf5b6KdqtivqQkTZtJAl
uiK+BH5Q7fyimWXKdC45ZecetQ==
=Df1G
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '93f7606e-03d7-4b78-a939-88b7d9a8f5d4',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//V6oGANn6jmkOJw8T6fhaBpUpcS3LW33XZkqITjZLuJIJ
qU27C6i6ty6DNqJ94mZpWeoQIVN5j2AQ6zBdwHqwruOzO02x+HNlbEHx+1ZZFFaf
F+cQbmUSxCEk03k069Y4YWloway/4MXf7JDJslmgkzXVRtFMxF4/YajG5ninlj5J
RVpyxB1OHurnd4ySsGcwTPNECsLv9LOmS7ZW4Ic+x9+MN7/j/7yKVD3zPp2CR9JR
pfIitWil+pdTdWcrD0JLuhbnl3Oo0YFF9hcEaYGxGzcZMuOC4zFRGAlrbsIO4yT+
eROiKZFY21LaqT8wyEiYtoM6HXcu1UYaz6NchSTL7kW1N7rhh40NZeNeAyc6dtXy
ob2vuuAF81mw+61zRb0ZuCGGGANNGwW57NyUuIsWYh9gI0qT3+/vY4fB17WIr1oO
93TtBWnrJKFBH/TZbsKrSnB/Q4d1TakEApPQQi8Zk4zALicRN6Fu2MHw/hllgU0I
3QbRTGyXUwuDH22CW0ei7kmWckx2Cx4GBQvnKdfqQ3nKfiJz3/QPHye5eQokaZ5F
Bo36EkvA9SWX2nUFunfERTYpiRUU0CdyyF9tuziihPxlVe3G9PalH8HIKMeHJa+S
ZdWjX/H0INJRfAmAaddBPs9InFtR3q8HblN8Q3dvubgyhXddP1hWlxoiNaeFdIjS
QQEWc6TCmcekRvQSXXaL7wLHYztzlk2SXulea9IHINuri7g2ckKz/SF66kfWxQV5
kuBp3q78N7y76wO6oKzTapO7
=ks+y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9c367563-1586-43c9-a177-53ea5894506d',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAsZcZYzngzkWX2Zf3TN6KM8lTI1kHz2Bc5N69CcAvKeQ3
xsVMbb/YSXbtPxOe074x0GMIXnFQnA22IfOg1LjpdCKtDgTfglb64AmQobxyIUoy
noat7AbX1WqIH/habKMuHVj1W0UtJnWtLIQj9rXQDxrvWEUsZkLaFG1zr+dYJSRO
n58WFBZe/t8lnGlTQWiR5qmtggp9efiMzi0+Tdvw8Ucp8XKLe5/10sNQul/O0G5o
a+C6ujiaee+jb5lat5CqgQjcjeiU3sW4i52ENSm/I3OWrSU7GsK2W5LN/fRndBZL
VOyoWbB2V8ojW2AymscZlEoZ0FB5s17/mGjIsWDEeTEVEOIGvGKh0GkOJ9ZV64/d
eo+VavytARmnF2AY+HVbWixPwB2fq80cC64xYHKuhVH6GgOpOvme4C76DIxKMPrN
5q1ML1CWCIzzDZoGTtoxTcy8xyXnhumNugbsTEfDFm/Bxn1lDB4DtRBMWEgw6+2N
mz8xvc7EMrxZckAyZXLsBJidVarKtcHH+TZYOIIGXKrcZ/hnIae1KXDRRzWHeQHv
FFda5Lorxe3wmYX9pIRdBTA89WzlqgSSAjRfg9Ya+iYIysWCC0EAEQuUOP8UZEiO
WLI2JAeG3u6WRn/iWJMu8lDUIlB6snDDiALv8LM9cX0Il7KRyiNG7pe6UFidn/3S
RAHV06deqFK5xuHi2DqhsY0fz70D/T8HbAJDv6ByMfB+5dghbZDDjNr4BNylekja
z4oDcsASn78oVzhwOU/xc/Yed+wZ
=QmCB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a11372f7-7531-4fa7-a70e-6b0cbbcbafe4',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAnwhchaMYcn69MTRexubEcGQpZ4vAwdGu00pLVdm4ZvqU
d8k9ipTCpg1rxUj6Sh5vTWQhmrhBf5pZiX3GG8fMM0pXWqApWq3mIQbP8aorcyFe
PwzRN+vo+Ux6qUZSoc/MI2RQjYTUiEVpPEivwdqVC9+U7oEcIDhaSGUDkH6jA1H1
ffDAzB/kaIh3l42k7vQzdjWiRKaXmah96eWO93g/qZn8Fm3LKZnGIbvw46L/Pugr
yCZUtDt56Xed/zzFMw3EoO5fq4aqJ7ge1IJ+LJGUDkwgBlBQFCoDbf/fMVr0XfxV
T3UgywZYmfCgcuAQ8cnsORYx/MjSN+EX7GZWLdbnDr+GD2+uIchbXPooATIxFkRk
Bk5K7OLrl8HZlytO1W0bW/92NfQHGn5RBXipZIu+4tQ9pEV/vWEEvhr2KExrwW8m
d6/jd/a9rKluYZAcTFCYBpmUi8UQK1p/6asyEB7Tq4czWKSdj5PJD0olAvQBLGFc
vaAOT842O6Y2lfZF28n30ym3t+mHuO8jWo7fari7V9P8a9c5ZbiGeIkC71BYOvU3
iTBqxwBnXdPQNO2nGAmvDt/GobknDfe7d9UTk3wK3MRUOwdeInyav8PZRM8WJPOw
TVi+y8qKJPsnQq+Z8VNJ3j1IlcpZMpViqL84Ho4aUgInvFSxAe9a0TDKaamNShzS
QQFvuCYnSv5HEa2TfW1XE22Mm7DgsiBaXI0XlK17EF+xm2/wRFBYYfpAwZzmJjYC
Gopz92bfbyyiYxw/JrkCPaKl
=HFaC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a5cabc0d-69a9-4f21-ada0-02668af3c13d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAx9uKlNOPc1U55UZSs02BfPrZCziJ9i+60QbMd8oBJaLV
VtZi90tGBj7QCrFJkxG41PhPgTlwk91xNP4krYeHgIgCEEnvfAC8WH8s6ONsR8Mk
YlAtHemV3UgcaG0aEhIQpMjgQ/QEQWKRSOISQCk9WoLF/OVWzqX6inmu3RivU/IZ
2IH9KGpXmZqWDfqB5XzBoDW+8Yfn/TJqs2SIfogsPy0hwJ6cDtDl0x9Mn/w033LZ
8kwVVX0oKkqMQXL81lZwkxW4gT/ntPaDdgQYwwADFzMnvb3pUhkejnbF1k7FWZsV
FLy71Y3FhAwRP87sludx7d+ANI1qGRqOQS4dZUMvaNheAAUoYyl/mbF4Daz0Yovk
GXrSl3dRkvT+hrYZ+IrtPMQOTQakCkZ5W0+jgPB3DfUXuuN2CO2U+rW5PZVUJyud
VSInHPJodrMhcsl5qxLGVxPLsXeezFwSedDXceZNceiZErOJirg5Ja/82pn8XZ8a
GwIMzd6O76bzILE2ZyIocdLUwzt4vLCYCx5jfUIByJ/2AHEN52vG+6hyZeeHK+cF
Pi9aCVwdEberXdCmseyV9vw3Ixd6ineM/Na3h3/CIC8KBcjPPPxGduhWPeKOmSdF
Mpdf6wPfVGs3BZhFQDiWsms2I12y9+pTrFp6DTXv1BR9QS9ko6xRrrdwIpXRGWvS
QQFZj7q0E76BGYv3piQNOXGspEO+4TJX09OZYP7gG1qrOYOpzfRf7KUB7Jx1+Q51
emISrk9/6I4jUqiNkp8ouAV8
=OOH0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'afb8cd7c-a881-426b-aa74-92a4b44df993',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAt0hnGVl3sMG1yHIeQlfTOVpeN9iAPahh0nYe/dkadwpm
rRYtb+XqhGD0ck7g3D2kvatDgywy53x3Plu4GPhpXCjG30Tdeqym09rhLGy+OiOk
u+QiK+FsMkuawltt3HndHJNOzVA0FpU2Up4YYBhrUBUSOi4/Ap8KD4OnGHiNHTjV
Wy5ogR2OeR7mYx7SQT+0gR6q0UOYF2O07KHl4+pzGGZio3onM/pZfybVLz8FsAt2
cCisYDEpGbCE01jX0/RvmpJEMx3vSspCr/g9WGU6Ye0n1iL4JWTuF8NXO+K7kkff
sFPj7GU2R1+YZ6RwE5ZlQlqCd44TRHr/6jp1gE1kLoz9Bsq0YHa9US1/XFF+poCd
K9fyOXPmYkTPRpovJ44Axr6/VFfhKTvsxH8vG6YIm0on1NsNKRjvXcD56xaatc6D
az+cQp2w2f6+6IFPJ8idHI8Jj0CmFzcJESYBK0XNTl3TJuKbrPnkz+AY5UifR5Qd
aRU0fj85Nc8PwpYquXXttmRXTT5tk21wJlw5ZNf/WSeJMLJuNniKkshID9QYTWRo
QHCjGO+n2YI064jYrIVZXK0n1yjwT0JSD1D52bNs0Jf8ZCzS5YOLh24ytiwpMvMT
H96V3jOYtPiwvIf/Vu7IrCoiFV9T0Fvlb7w9CCrOoA33seC8Ss01VDC0byZr0g7S
QAGTXYs2NUVaGpi1+6mM8OiSi6ok4e6rgwyaW1DxdqXUgag4cCSITJAcpzLEmgko
Fr+/HkaTFmscIaSq1wbhgNU=
=8+bu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b17f4058-ed19-4380-ad6a-c083c5ef4ac2',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAqf6yVMzndIAGh7+6Ui+Oh7GOsRoYVCY1VsjTG5SwJRYb
Dl3zp9W98bl10Yn418+1mOty9jP0aZZOA1s5uW3FgClrrIYr0jysEwVIxv+H9iNj
bOOQllNb3E6t4082P4IHWOVNG+KazAltuaArqQDdQ+9eAepm2JdJ4auc0uWWAn81
f6RPKYecIVAamy74NfWYCijMliWlXl1DjzR+N1PhsnmK1mHX3vrd1ZynABUIJtEG
UNqZAdDH0aYTOEJXSBciALD0dXe4ckfDWXzCv5iFGS7Kkb8KUbdjyHteZmUfEoTm
5f8yzmxI+yobMbG7JQ/ZUwq0/shp86GmlOPPDOLWwwszLSfJjsYGKPqr1NCwHcBa
tRNWoaDcLUMNi1l1W5ez4znSE+T3fk9+fEKCEt185hmwlW/rHXwKg/46Q/ct30lE
yG/XI/Yj125cG7XIRqGB3B1Da0G4f+rteEcEN3fF7CHRpfR4kI5ABluDPqY42V/p
+TC0w2DsNTcSmWRZ/pRS6Ze71Ddq8w2vVeows7eNBSHpbolCp3khwILjih4Df/MQ
dUB789RO24atFf6QWdWC5ijl5Qf9c52ecf9DOGbWp+EkvMrSO+A1VkT8+iGalxMq
fOWIdYo8hAW8RtYsZx4jjjvHE+hgL6Lo/4/WCF65reTOePwKCLchmxaMLWx7mwDS
QQExE8OQkm0smDH4hfSLDVLTXwinh+3wiO6JXIWdYtRgBLPPmIWALu0jLbrMuNqp
1JQ1hcr7NAnVyzggsBFTySXL
=P93W
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b41587f5-2998-472f-a768-0ca8aee31b77',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//ZLPz7RUzd0iC4x1rd69NYsFsu3aVtx72uDeGuPKRfocE
MopkOT9HrF32bXZhp3cls1SV7TDXhFQTdqfG9KomaHvWBRCDKYpNwkwqLw43WqjN
xYeOtEwpm5fCaZJnjKAtmZ7wbo77PKR7dHoyOGfSG9qfKrYqR+H31RLpyBtUIvzR
fESIhWKLdoSTdtZV3plZS/vsMz0CVCoDN6HOqYYvtTRPn0Ls1KRgLJUPCMvDMcl8
h18sw1k+5imJp1+tEiHVqnE+33KCFOfW7iq9UnCEYx4kXNTWEhbEkKBTb0RprNNT
dC3l9w1ldm1S4hiLUsIhdNFmHEtrBtXWDKvBJvbHn9xoQiBjJYx7wCE6huQ+V0pb
+6wi9rwQtinFP9E/NKC1eDaVP/SCrevP865Q4O/MsyWjWQLvCdJXuTals5s29EsB
COjmSF6GqEU1pRMx6j7TqdPhUAvAdNqDxTJzk0USusQGUp1qMxyJSpx1Sh45Xt5r
xnYl/F6RX8nx+WaQ6PpDvwsvdIC+rNMvP1Bpf+U7ZwqKivqEa037V4L9iSAnSobh
0m2aCZ5oGu+w1V0D+9emUH9u1Ct7dOeLTUJN7uLa9XAvbhEbEOKtYEWln0tkrFTm
o2NAwlJ1TxmNlBsKJTP9fHD7sK1uK74ZdBPTjqtYXaOFlmS1C72nfmeP+9cLcjPS
QAHhPCYYA6LiByrZz9RuGPgK0BSheMB0Y6QWQo5jK4NVgSGJR1FFO1Jz2BJEULI2
8MD9ZUPfB8El9UM1PoT0XxQ=
=hCqu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bb103107-d023-4c0d-af1f-79f05ca6d968',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9EirYL8N9nI+GYTUjb7ZJazSkWwvc9NWp+H54XD9wRDxd
6j/RMpw8BSoklAlhaw0GvTQSIPfhooCyuQpRYhJ6rCCAPxEmqwt8vakj0KlHXij/
erJCatcG8jWjOFaPL0cAgByqPsiYyOdkh6k6ogu/XgPrhybBm2xhkevrZYkBgwNw
40vQGvtfc9PUXCRm1jYML6SY2E/uxoNwRzacw/dBwezkGr+VpqpetjPvMja0pqy+
PPrgeKCm675J/aBnDTwaiipSRcMsFZTzDZsC8mopl+rVc6oabRB6UBzvah77gKBj
t4i1p/0/KT5TeWWdFZng04O6cU9Ps6tU4m6H9jDrFxsG+gdr0ixI62QEnDxc73O6
m/PHymtNgxasRMHHE8ADxq86jePS+OaPry3Lgt5GxtZmB7e2VduY1DKhS5xJEIST
30c8IUaESkqgBHX3YFVPw7KGZi20RVUT+budNSl2nmBdDS+I9Fd4hl2bN81TBJ7o
+s/niAWjQXxm0hCmbKVvdNHcUsu1sHOeRfVfwMP0xzPIZazZy49GNjvIjPRyZ4o3
av4kPhVDT1WsiCGYML/0GZroN9VeQQx3CcVu4GSHGPhBxnuxMddtDNhKgV//sjmh
Hk+O34xQrzh32pOBTvqwyF1FOpJWNSicFVaROvD8iwJv7pU60UyT37pXe7fSSlTS
QQF/1TiHC+NoLzB59m5bLtbcS2230LE8qIDfdv/VzW/auEoLOAf+qsFFp+8iOOv+
nYrbKlej2dbHafRkVRLtLgqd
=BsVN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bc950bbd-802c-4532-a3c6-1c3f9ce5a8dd',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/XAME8oZSTb3DdzHqUhbN03nl+jSwbD0L5cMyMYrZc8l8
UjohqfiIQWZ0xSPwVXCpN/0EaW3zaKOxTU848HtkH/VtAG8P6qVlqiDf9KI+ABi0
vFI8+73HIRdef11D6O0HckCTYwn6/nKz1G/J6USs+amn8rjvVlzZpQb/jw5Ifk7C
TW2KZ9owPK+Ew0km51dGDkIll10woDybDLpLYhfT8GkQKwj05LZ7vw0twoBqiRMm
p0xdUGJj7I33mgdmijnkDiscPxrEZxAWdN4AZAtA87T19XPh0ICQKOCetOBz1BHN
7uxUk2VyvUiUvsOLJjI8jl2mI3vrB4uh5zKEGJBadtJCAciqnebmbgJzyDwiRM2S
muCvFLhCREpzqAlvSpPDxsXdISCPPWO0DtT0J/oPv/VLDelKjbhOHCF/HJhXp7zc
mo6B
=2jVj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c1d3de04-d3fb-4e72-a28d-4a0e9b45fe44',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9HbInCHSKhyClxfVpnC8oXcsXrLMD5x39FFNpGJebvEVi
sahpozYgv/LnsZ9odpEFl8w5bTPaGe1um5zKZu/PuS4OVU4D8gj7LawaX9yGeceJ
DPznPSHsxQLN9IOZ4Rg2CCuUdT25Zfj1KTOVMh0kLWiXQYyd/kPF22lGTPsGdade
2nkQ8nQteCB564v0UzCGLGwM0QBFeRhBzAsTDLIu1dHohzr+wWlgHP4wkG4KxR/y
do8RUR4F+il3qkgCqMgp4UXy2RM+kYCChyb2MMpCZoEMsAZaRrgU0tFXBZ1fdr7N
/IDduCvZT8xPKnX1ARZceh8oGhcs6KwL+di5lxifwJEaWXspuNhXhZUh8ZajDLlP
R1rGZKykRkErdLt33ttrDs6CyGR6mP7Z+o6+OILErkzpFZNvxP0saDFAoVzsBdy4
mQAvTrq179YunsvWogoBDcTE1ZUcScE5dhvYqjpsMsFn26wlDo7a9IDBz5iznVi3
+9r+tEWqty0bg7amPpojqytJ9hZKWADSsS1FUp/iz1xkKll7PQzRoAt6VgtADKdQ
2oPep4XitujmAjl/iGhBtFwaAUwXdj6Nyl/Cxf8D1Ne+9iF+qd5y/Ko2roECkN9T
mB0J9dMNMmLQQ8cVCtyn40tDUwIkHPyRHiO0gAjxXguk5vfE0OVCsTt/EUxlix7S
QwE+WXjnWA6xFPrDqrA1ETqWbZUNmaTquZkQ/I+sE6uIi5FAC3R3p6lBkYbIv3Zl
3VtUSL9x6HkhlXSqbFEc4tfJOMo=
=lHUV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c77642b2-e45a-4a9e-ac0b-aa747974eb41',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA55SfV9FiiTQLP5T3JzCfRHqygMsoHkjmKtxuHkGO0+Td
IeNCXGQuGDhPoo3em3B35wRc0GeQFMK9p+oJQblF1x5bW6uw2YOtT3avYCDk1w12
OgxS0l+V0IUwSlJ1m4T05lwAJIBZwWbnMtAfujDfOorXtoQsZxptoVL7i42jqxBj
Eduxr/TDHt8/m1btVp93cLWmW8O91OQ9PUd/dGkaR4cpFtzakQTzVTHP1yEEltoz
5OXmm7tKbi4E8/YtaCyu5ElriqWpj7MeMrfrtry7MPb9EhYgAaRMoWRDIhvYY6O3
7OT6jM3VBDtyTOFfX4oef7vHymJxRf0O3NOX7TwWMcQ71HM3iVCktXEkSoRwCsW0
fmDtnmRE/6DZZSd+s31xW9d/wsW+GqCSKHmWWQvHo7htLtORC6laVvDPkVPIgCP3
/+riBUjQ3K0YHlMKsGUXmpyzbjoRJK0M6ilafSLqDkaYAv41pTod5dvwY9a+Zdie
ACiXHnt5DYpCMUTBJ+Z3JF+QLMyViSeDNtAT5Kse4l5iPZTmdw4j4pQGaT83v797
WCTJCAi+v6WHvw22eO3wgzBFUE00gBbsvgpXYVIHy6HV3JvoCHZtFpmSY51b7YOI
dLfpr6AWAug9LtlQ1rNI7KkWdBoA0YzXfijEK6QJUkj/p3yvK/qAYvz4U/L3++XS
QwFhgm/odNWfzK/SdsD8MtoxYHXNqDFVOg/yohNRgJj86bz+hU6xEBAaFE+wC21M
zYPHArNKsd2XlloL2SFhOTMQF0k=
=Gm+t
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c7dadca3-741b-47ca-aa81-808cb1dd53fd',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAm+7pfq7lHbRHRw7TW+muKFKrt34JaWq2sQkquy4S69QD
OHNHbUnj8C9BXxJ33eKdHZcdzhm99kdz6VtfoN5SgJVfpPLS7O2OHgdkb8JVK5rq
AYKpVa2IIwqjnalptnWu2LkuvVawW8dv30isCyEgZSi8OO46ZSCqNsGCV9GT/Iul
zb9k2AK8Q4E75dpH5b0HXRBc3KEBLmF6Biwp8j3/qNiT2MucK3JW9lkG7nolcP5+
m+p+d1+iE1cB/b29qp47K9m+iS1B12uykZMxeJhQMzf1bpJUO/Ae8iQos0QNna77
ivuUCmF6OM18u9FluMOpKB5/GhF5+jDC/a+EmqibVvaMRlrCBT9i6LwXCXfEdPP/
EuLvrSimJU8iHFCcjfksBxhcenluQjy+Wvt77oF4BbF8WqHDHP+gv8ChlD5WG7Gq
e28xrRrletB4v9FnE9aYxFCFshu9hT8MGjabAoP4fsqPadGPRM8ffMsdvKHjb/oH
TNAKaCceEb89zfgEve1x/m3jbi7FCLx/EWwqSIJz1thuXMIhzGqV39RvksxYbkrd
d35/i/vtTwr1Vz6dfSkbuiMIKdfKGg0DTMH8qglsHKJIvflWrCvaOi2NVdzYq8lU
fnSGjazoT1eNHuqzqi03KQtHEgGcsHzNowSCDLmjWzpQrkZdoxfwsLyvnk0LQ83S
QAHyCfiafdzUYGII4DxVzzTZj6HNuSIu7EOvxEpBcpsW+J+iPpbn79r4atWPsZeS
qUpt5vlWA4vZxf7pCYYODVU=
=n6Wl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cc2c7315-152a-4e4d-a05e-6b585c91dcbd',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAiVUV6z3ffTW1nPRSmww2MKf8bCTNdRtsbBw3AE5oBDBl
5dVO0ZgIT+25WEt/Irf6UGTqQH3brNRO6Sycrv7OcplG+SZmwEUZ7wstKfeqeF4y
gUCwmBP7rNodrLWcCM2ycQuACesuUMo+4IB3bGXunDQGgtC7lOBZLdcG/lT9qVTO
X0Znd5tw+5KNE6Mqi/N7CnBr9O4I9DaMNJdu+/3JTwmK7FbQCVKORU18alCyVq7r
pnnypZ7oYgI0YEkooWR2Sw5QmRKG1ZCaAKargxySQ6IrjP7bFEBydVuhS+OAEGct
vaoG/7naDC5bGLYHt9qhhS40CQN1iBOXjAkb3iOQN2OjGu8ZcjTiWWOEHo4UryF8
U+tQoS9e3cm9UC9YbWdKdf+5xgP0S99LOrEZ3unituoujpM0M/GVAcK1lolJUrPq
mmzryywnwvvorheYFCXsKwhoN1tmzpwBJqA2bmRle/8zrUotWYY58iXiYo+CtooZ
A3e5CeyLJJYK0OPFYy2mxc7UmQ+bWxOZktiff2GTVUp6dcY3PdqohqSPq2eP12ZU
akhDYbfNuG/JkRicM6GYpzHSm8eeQ4zuDxH7uUzMhFCaWPM+gieDBjqByjuu0LLS
doMR/VuVIo7XveK7M9Xb7/D/ud19MC1RFfFv6gUwHcxOiMvJOdsvH5R/X+RU5aTS
QwGG/0oFf3RHlawGcKKV7AYPuyk59MZ5bxe7Kf1eOxSIaI9aDsWqqi3MPGrW1YXs
+vmNSGRJCMJhS/X53rQg2L/3Hew=
=911i
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ce387fb0-489e-437d-ae4c-0603325fc98b',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf9FPlh7LMQQAgkQGrLt/rpwwQBUU/GFXC6dD4T4J9GT/6R
SRqpL9vtwInpcdE8/0I2xFgs6D/JovbY9e1LErx/gXcP7224sHnhtnBTs5CS3fXQ
dVdOogU1uHcv3F+uT0uo/nPGkcGoDqwTtzcbdFO/Kv7/B0EmOsjmUieidL2peT34
L++hlcfwsLb0jj03sv6jWkjdghQeApAP7mgLmbaCmfjcApIlyyLqp5VF8b5gGi40
8u0fUkXJysbRF72FeVu9flKiguLymhes1EIj/Er8t4/Ok5tnJT9DNpfriE+5q9bN
pU8a+j7MOVaE3W191k113hJcNk4C2XenGzeEH2O+htJBASMcphMq7m3ytqIcWT+R
5mgBj6SgRgEYsKF8Zfp8qoMMkIl4eqCm5ADM8STFKp//P/tVa0zuWBR9VfgJdl8a
VoU=
=hzf7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cecb3c56-cb67-48a3-adf5-6f07dc86d35e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//SgH0fEx5dA7vb1vQnxxjYRFkaDA6SgrDJTcgccAHxWFM
ynqGGSX0G2sffUdiwHwOZAVhLP8NYgpuIlb4MCW/Ixd2P11rZTqtORKtYU4IBga4
pjLJj2PCb+HhvJmuiLeBCZd9dn54tDTlMtrfgT5r8/s7eLLPN6pJK19HMEdJB8Yk
mhDdQnSmAsMjHMxRDvChfy6NloShvAPy+fF8IhRDP53kCfmQ8+Bwyxu2kdsHO7Sk
RNglIOVPsnu0+Rq/7U1upYvWsxjWaEPufmkSv1sODhEHHVh1L0bXeZxAtJPHw0Er
4TY781bgGkE/ZCAG0pG+Tc3qFrOPEVsW2w+RFpmRSWL8cGh4yBHiCyFmCcFzicaP
LB9LgdNfwPZjpGH/wPeP98ybob4kyGsSL3oPtBirWGjaUCD10C+Arm1cI6kEx/FH
hxTaaym7ljK8tovT/ZbC9SKr0kPQ7ORrYJhdMG/4DjjKUn3AXA2RsEg+J4nhFR/7
2DYyBqJu70FJ42tRJi6Ey7BKBXflQg/VChw5kpaNDSv/fpgRUC/AgisuNY/VMSTq
a3104M59VqjmGcjeMsa23JKdn9aR9mh86U0ukuqm6X8epZa7Ct81VUFhyEyi3KkJ
uYbCKlPW7hCvSvSrXpCU3q9VumgMAhNZHbAfD6BgsfBJxyBNL02JqeaSIxhkvOfS
QQFpd7h90vNbdJxq7QSo09swRGa5a+n1hsyulbdK5gGe7/bAa3a2HYwKPO5iHXmx
kvZe2LrPA9TxXBM5djvKMhsA
=36RT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd917bde7-afcf-46fc-a2e3-8b3abe2b21d4',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf9H69JX9hjb8/wCbfSrClysPx5BIz8VrUH64B03kFMn682
dFMBEdFrwvMnjkMUgdnA+mPbIVzKIvngN6fuBrb5qtajprNytmCA1dhP/PZGyPLw
Fn/eamRraFaBBSVi6I4uYRGplAErKr11yeLeTkHxDC/zNngqiv2RPc8kaM/fWX4Q
iXgduq1ViZYuxUq1zimTCIQf00r9AvP9r82yTH0d3GLcNVJWshAi5NmmuJ+JtqLC
65vFIOZXm+KOQGCseJ7Q9EGqX60dmRgjJvonEPAXRZcqvqx2rNpjW6DRAXQ/g+Ja
mrrXRdyuDCSojZlUwOi2BmkS8/uul2rhF0YUpZkDgtJAAdYf/c5qMD5LcH8rqKAN
O+Ly9VJye9S7qWmUp/2qhjxJgUOn/NBT7yH6/6elobfVRvzC0WV5EmstAht7wIUt
rg==
=ev4I
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dcccf124-306e-423b-ae70-2b4b53f2ea5d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAnsRUA+T7o+uE+Xrn1wwvowj//3OzBa/SWR831yddusUt
ZmjkVDMlIvu6sMj23vY3ABQ65C14Bn+JQiThXzi/q6EZtclyICFMIn7OQS0XyOrz
kL5hei5VRpmH7+jvrt6uTK8D7dExUZKsYsmElyWA5IbCLA2CSA+jEEwlzdkaZhEq
1mYEGb/jfDEZeZtv+RaG1zuTEhjOp5DZgCcMtwJ5fPMJapwRu4vme9/Q6UauRbG4
fQeFuCxt21skb/Kki/Ds2Pdaj5N3oxgvucaCgpdsP8U1qxTL9bLgW6mfVK7vq825
GNwnDEVETt4pYrNO88VlgMOxI4ek+AVcEjCxJivB3DeVtx5BwXrQbJDAqLgGYvFu
XoiP8bjh8A7ggkYvyq44cTlYQL5GIJwFC9WoZ1gT3ekyQe5NfO+IcmcGTYB4eomX
0qgKvAtlI0adRkanJIGd3PTqWD2jjQmidTShVT4IT5CtuFvrPn/5YidLFl7S4VUR
CMEDSWnbqQvz+f77djFYlQd/fhYYWT7pQm9MY2NgwiuiEHOFAUAaKyGHiVu87w97
EXws1r2xbiUGdEgejeTsZTjofFSTStOwfoaIvbogOU/9Hi0pZiQNRL2OcL81uadg
y4Y5EWBnsGlm+kKIhPgIY6Vxd4++h4btTM++yHb+r3wVtUM8OEDyxZ29Ush++IfS
QgFOHSP7SOkIrZWLy6to/0+YQAez3bYz4PGa+pJUt6m59cUczV1O/dVotpKqiLwT
L197xL/S7bI4CZZtHm7QrG5i+w==
=1G9w
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e236d933-957b-454a-a595-7687c3158521',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//UhKkooCp+KgXBe3CgGeFEOpsJFkqw5lKGudnsuID6IgT
8roh9wQ+r+7D7dQC2MT2d8QshCKBn6uuqUUIusun6DPhFZpEIE5rr4UQo5RnYDwC
T3fxqkQKV7wDHYfgpvsmPObrVJjYamIdaeeJvcMiDOibNx6+chKBvZM9n/98gHO9
SHFPmxMnSflGqTCK50FvgUCwQCICTPL45ZWqKSniu8n2vQvseGAxnwyGtkBCuKYT
LezAndzhfIVhECLKd2LP3n28T3JmtEE2aaoLH8SDxWZutLvnSck/+1JY/Hk6EWAL
u12xFjizsT+pnHXSd54W1zK7YTWdU8fvQ9KFnmXt/qpiSFNhXHDN6Gi6eRWPOAbb
AXmyuuLJB5spNvIPP04cdzz9zCYwauChJUsQUvqhBfhCb/X/xCOLKcFgj/XAMEaY
iTT8Jkddj/xCl7Zoe0q8S6bT7FXC689xPrze94VZpSXI/Yisl686SpQGuvRYiNyY
TokcBMZtva88kss8LJgKCiQK1HlOVr1lO4koc/dsgWifYoKmGnhH99UyQudRTB83
sS0NSxgcpUWvUphAo81DHeJz4rqryFiZsJ4lxpNOkAnVhv4MqiNuHuk++2q42kRV
xx/sko86fevEYRteYpybU6Awi6BK9hli1mOzvIDRG/Cxz/+uMzJoGG4xpaikWKTS
QwH/ai22qRhhreez2WxS267pJNgjOHUYlitU14AC9n5hdv7OgX+Pp4zu6IULcyp8
LZzglRuzze9khy7HkjepDxw4Big=
=vaxa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e71ac7c8-6a14-4a78-ab07-8686c07cf50d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Xx02JWmrdT+YUr29olGYYqliqrouWwhtBEinBNTbNLda
Tu9HF1I2/eQbjEEXday/WaS1IhYzH7iipaGr5VUF3aMUfJji96XZOTzooIFzTjTU
o+HC3iKk1yCPbHslQuEYC+vB00XvubAVYMDYLYe+m5ogAhY4jp8ICRi0Gv+GLT1s
7TFEwhVsq62md/tZQSW8507KSGgVD1Pef1ZmWH4KNiYCgR6uwqC0JubthShzsTa8
efhwMHqYx3qLagBDzO41g4PqENvBl1j0f92nh+bhoVEOInKOwbziDDCjnLaktBkR
hj2L90nfAe/p/oSqQBpbxAtUqO7uGrylPDJISpZCXkwQB9elRJXhQ4AYcvITBUFz
2OILMFTfLQxj/nm8DsDJs4qyl2gv403CIiLTgQjuCPb1937Cbhe7XVmOQBLFPB29
vQnhR9Yg5LjvTdLnDJ3OpvJorDGq6+JCUCFWXIfTfOhrso/aPOwce5uNtNJhX6iq
jzTfv9OJShgOlrDDYFjmQ20ZktQ8Q3f0mXfTlftQVp3CSDyYjZlqvjYQKk8rpfBR
dmtotayicsiidrwoFM/UZPJ+rFx/MDafy9w6sPEfTyFV31ECba3e5erna+HzIlvt
AYcPZH7OfdXlXy5tyT1a9Qn4ocK4MVIMLb5POZyZuSqYXE3VilFtPVhZszviy7DS
QQEUdlDV6xBd5VLaa5J/JIVSRR+AttDmTFD/dsP7xWVH76Omn7QV3VvRXL55u7wd
bTK0h6jA3TW1gKMtgpVH5efa
=6tL+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e7fcfdca-f038-442f-a2c6-1e1e257a93a6',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/dvy/CZ4mUsNtnqGjsKfhD1oBsaUEW6hrh+fg74ug6GrK
dWEnCQRAV98fRyOqVJSIh9W+VI8wbu4L1mlzY0WeQ0Ua3NcYFGovZGrYQRBhOW8z
aVFvz/+hKQimwBo8yeVT9rbYKVNk8iKLhhqfoO8bY2JrIs/H80USI6bNjOnygvCW
1o3Q1pwnWPL4myGeuSRitqU4zGE54X+xW1MEDq7Bww5/lKffP+k3Rh1PHb6tF9eB
mrU9Q41g5HhL7Sv+eVSAa4blvGuZxEEup+CtbhJ5kjGbGwTbXZOT5TiKNF6TFgTE
uiaKzs0hG1egNV+m0UJF7lSI7Z1VNf2RD8dyNqA95NJDATSMyoCUhXVEhkEcv1tv
loKE7TFo9h7wHtcXtUnWU9Ngf0CAH8ufmh4c2Xgj3ySLIpZCxbR/kfhq7F2comtF
fHL9nw==
=wng2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e8d48cd3-4cf3-4e28-a9d1-930212c54253',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9Gv5BtRgE/Ex1jfUK2PxrJJVJh3ZT/TVwqJlmL8nLlUhl
g4meXJeQwaaTK/QUXZ7Bb4vkmQHaHH5uJ54lJxgxpucb8anFCfo7ot6bHt2cwZh1
96hFLycp2FXMoTuDKUEr45KRVZ0xLK+ktgBOMTLremUcTekTyjLHDNJPssj7slmL
P+08Exb2cFrPp+r0sPQdKBLeH8uZlxO2VXN8CGERxVBfPf8TKgO+PxImmwXk32eI
UET6ha/WtqCw5+xFC6dsJ+7ZszWGicIL+vFoWmFlkBA7lkETxllPR7Iwus3q3B0B
RjqBymI29d7cMXwjYFD8Q2G8R0cwj3eMSHep3pSa9UhkCMkHnnzzocQJveFl/62P
dz5Lw6fNWXTbr/0miSgyl2k9aR0RaCCe/CjAgjj0/orOnNpNSvCpgE9+eJWmfv9g
kdLNrj26HQM75K5/heZ++NdRP/0gQUbalTZZvXStdzhvchQ5mKqvEJX0RaUoHqjc
rA02pz5yoHc9dPU2m7e6V07QzJgESbe+GaP1Vw56EyrM3jDGTMRBXa5+C3Lj6JI/
mU+G6+T0sF9JzRliwFRGnPEpgD+j+Ss7rP2gvYVBDTvgelkw+mLyzD3UBRFGrBlS
Kh2z6FMsqWwQi3BHrGtp+z0/wNknEsmkM3BVx3EM5SzlKOMnaekX/KxP3XxxRS7S
QwH4K866NbEHvv37WH9MMr7/at+TckU2WCzEGIRnS+04PpNRgVM6ClTND4kPeC+1
OUo6Uu+a9ndFkbEqRSdSOEuIQB8=
=4eSi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ebab9d5b-3543-4aeb-ac34-74a5901483e4',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//QWIOpjq1ZjmjQIlhEd7WpvIJjEfcqkTM2D1OLRxqVJot
loMcvJEnf43m8FHbQ8DMY53AgF3Xc1hXEqG0fKxeIY7wTfeVkAgvRr4gtTj8RVPM
hu48vXYbU+sZfQqbvU5t7Rd+YdQ3x24eCwP+KTpcI9K2AIR1dBV2TP5wZsYmrlr4
lg6LZDMFAWiCLWz+YUim/OvZs5thW1c2gBX5cRj87zdaNXzGVzuSkKSt+xIbUe7u
zgSpz9KnMhFvyAh7WJW2F38mcftEarYvpX6oBMrV5M/x66WTcMaJINJE5ghnf6vJ
h/L/APPmCUcueQ7LB5dyspv7QHtTo6uf4Fz+zHqxFUwB00AEuNWDXxmk8Ml6aHw+
OI24gkAhRAV6uqP47vvH3892Qt9PllvpLWPmKy5jlFbUAMRGDDKUQVIt27RX7ACQ
9s40mXI19W0yyMGLO/LmLmqFllsHB05IC7n2OhZ8Fg/oFv9Rxg2jLrz8HzyC6apE
DYbVJIjy7V/+4c7jsRyn0q1HSLsnSbNkJsn4Dj0/nOM7+wsdAmGHBF5lNB9zhKqH
ER4TQQdOCD0IFKFt3yNcAfowMz54AJaGh28crcwvWLd7u2dCm0T+yhTI5j4X5DYE
lW8HZ+cNIQlylYd8mdjvlyhmKsj9UlTCNdFAAeushzNhkhciHcJpkkk2UBy1NsrS
QAEXTsF0EghcGmpHQRLz/WT52iSZaV2mwJ004N95M+lIXvaL2kFF9pCPnBflmNlk
fJoXBiVxco9H/wAQuT+R1R0=
=Doid
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'efc9fa47-b9f6-4a50-a4f9-318b424df9ff',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//dNKfkBd5JqexdqYrqdPx2aWU3lzey1JjlqR6hNcDkokz
ftAnc201sAxObPTnQXXuhHQrXm5itvy1WMY10OJX/Le4vb/3/GoHn0D7bEtiyOMo
KsidVojCF2VJyGW5bsFetKVVOT/NPA4rAAKST7JeDkNr5P6Q2JfywBfE7Hnke7g8
mdZBLpvalQUuvo7uDFXg4FQr4mI5AXz6o34DNBHiVKX4f2fA8JbW8uQoQGmygZZG
n5e5l3cWX5GsSKgLeASnK1nu4QVwELa8PfkQAg384rZrJ3zdILG3a/vX87p8je6/
kPTgF/JUMxqLnyL8S2jJMgYzWLWsbQcfyKAnRxCr7FeUH6QWwvO/rCqXxcfoUn4s
cN3kPJUgkJ0w2IAkFzEqk9tSAkLHFsuB/2/1hCCKU1gBhQatEGdv2Wk/zqbWZrTZ
mvP1arAu5vLrBDVrID50oANiqOQz6fBU033+y0BHOVveDw3CqArt3QwMzSePbjwU
aWkGDAJ23qrEJFuiD2jwJdHwUGTTOzXe+6ZEkX2IFM54WJEFgspSn3u42zYzDX/Z
e4ml4cB/XFLjXORiA9LVK/cy6WCaJFlrOChXfNhg5RqC2KdUOBNlq1VX3GeNmy/p
Kv80/2ESiLCbmxeAybxATpIMRHTj4nhT0aodiL9LO2QnI7UDR5DIE91aTgIBlffS
QQFFhGD2add5t3N7GX8jN8b2zzkr7lsIZgCQj8rw4Kc4YRgD6BueIo2fdvJOhkXR
pR7+VHrfN3cFmAeJRK4dfM9U
=UZ0b
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f212e243-0624-4516-a353-b56143e6bc00',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+PQWeE7Fb0aj7pwdve5qDGAWMupfTI9iJSsEvTQom/1en
x4vTJe3cqgmeWtAk86IpZvglh6TPo3BjgrjBSNqp0Xyb2TUXEiMVGGBtetQ3+MN6
IrMhdHWzAWpNQ1CACz9bawELh9kdDI27n1NWplJh+oyu6ue1lIYwaX7HGjphoTo9
iXbyk2DVaQWWORiWoQNYm+QJg+dalBdo2qmeBNW2oa10LHDYl38M1mVqkCqhQhMf
XZKH0Vi6UxeWOk+5BSIS8CPTyl4COXiS2ufg0R2KToEvn3+R88bv6Bq8qg7Bn7Pq
dGZSF+zN4PvldwkPfrrCqshDOtJ72K1PL+N/cbrGsI3Yt8+qtsiFlMIAefBM93Yd
eQZMWyC1CvPOPzi22uIZbyE0t5ftnNfhEQiuuQRVT0eWwNxvYigTH/AaByiTJ+tX
8c17cvqhmfsjNxPcS/VQJLq1MOfvDQPfAZ+y/R21PcZzTY3kgB+sUm+XHKnG5pOe
QUsSYL5vAHOt/uTfImlnDCJQaEQfjMOjBGm6AAIXvDh7Qvoo94SKbOk4wauOm5ND
MwzaPZbqztAoPeOWrT+c3HlJMcXC9+/pEtsOIWRvUlGZK4xIAQKhw0Z3zq4HZfRt
7vCwXwUo57y2neY2Mi6FqzGjOaEkKlC5E0U2D21b5m/VQQhljpME45zSU78BeevS
QgH02ZEyTJ/ba3kgPD4F3VTc4KW8VZFlloFrfoWfpJCHczUol82ggNPlgoxQzwkp
NdFiGyfJtgkqzEgLh4IOYmMN8Q==
=pacZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f22b8428-11f4-46ce-a4f9-96b4f17d05a0',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/eo/AHDM7s/jiuvkyCZ+y4XcU+1G20hW7B5FgH1r51Eme
dAzsDbcvyj0xRg1lp6SZrzrx2K7HhEIp2jUTGc33JOETe/OjMZFWhfXZc2UOvNFq
oxSlJ9PTeU/RC2mO5u4WihimGm4LgJhfnr2xGJSeD8uPF6pSWowR+ChJLRNujGW+
OKr5RjYY/wowTcxq5kFcxPsX9JQESYjHkM8IlXFpFl9lO5C+lxzoTYxYpECUVKOD
EqABb+3fIrulN8DfPY400BMMKVH8O151DciMFAz4FtXYl/XQAIJTRJz5iRLr4dJY
jsWoHsie9hSTnyP2lFgWwka78IobKzxQSfXjiAytg9JBAXD0QxX9jIye0Nqi4/4O
ahezNQk+x9V6WzBk7F5JZ27FN7AxWV9KpG1qEpPOwq83KaUh6TReZWkFkohq3i4O
GBA=
=oYEA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f243e820-e79d-45d9-a954-05ec82b37a88',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAArDAHJLpepnwrUZGb5gS5JIi/0aduI1c0VI0TzZs1sU8P
dtLawHkMe3LnSP/yiQBZdQRKNgsOSE/rTaz4JvVq/oBI76Jxt8ebEBYEEN5OqDSM
dJFWnEf7p9+QyMRQFeL9iNA7DJyOMK+jQG1p9cwaoN2PCTmb45hdXU5gMmXs5Hna
YX8cPUSZ53Qy4DvowoJ/SX8ZwzAtKjOdtLt7uGPTHc6ZTnemx1onZSqFNZCCbj8w
wnk4usYLkBBECeBIV6Z4f3oance3+GC/9QlCJuZ0y2h0VXMv9D95jnU5t8AJyO9I
BfiSJh6Exkv92FzuTg2osR/DAo1LJh63hVwByEytqlM9lteGjXs4bu3NBfX+2oK4
ket8iVcg+i+TxrEFdBPS1K+8qXLWlt6gzgs9HdoL2PBJ2l5lncLH8ciduYWrEsAT
NVNJJMKHeIRf06AaqWKuqPb3JCzwoTIi9I4hk0QS9vWt/SYiRWMYKcErHDVxuhrP
2IRXnhqc9Hqvt8MRlOriGRB8JLAURS4odbPzHPuiGZysCmSW/p3IUWcqtST189j1
VHJkxwAG+rBpCqqCT3pWuEWgia+YsKgUoD0SSC6lXhZCKEQU0QMZC5hNoq75E+K8
g2d1PzHbCuiMuJK55ZN7E68ZBEarDV4dmk2RhsoljHv9KaKqAR6+hAQlpsaAhZ3S
PgHJWmqnr0+Z/dv5gekUMd+e9wlZFXu/MGQXOIHhsDyZ6pByX2ynPf00bdUjAaOE
fYxut9sc/lhrJsxW75/J
=6uRt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f6e12747-6e01-42e9-a667-2b2f851e75b6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Sm7M89bXNLew6D66fySZh7o40XGKA7DFaseyUOIAbua2
QewuuiTcfVX6DdabkG6N2FyjDPCRgbdYAHbqLX3tNoQ0v0hrYYGge1mlUs3gnshy
T4Q1hw6PBNQTXR7NjUdLgDRlvX353YGbiajgr/rtYQ3nfCskQwyXh/uV43GxHsaI
ZxM6g+gD5DXG2YLa5M9v4arJrObQPstkLE5wlUL2P6WbiITslnRyMVp7aUyETfOF
RIR83GG09wJdepJU0wofsjE+OufiBcVpgrX5F2vE1eQJjF30Rtj7NdFDhNu9rDuS
VO3BYZ2yK+21pSfpe8a6BKPR/67uLJ+mdl0QIenDR8LLSvl82RJB2ymAeDhJZUC6
vqDTiGElefqoIy8cqIxyAjAFTXZVJmGAO9LO0lG5S2KpxPHuKJVEN5iOaDtcBSjQ
dMoJY2WjrMdvvJPVtQSeQsjNqQRCNB2VBD2xM45UjfSLeXG96XRNQs2euBMcauOl
L4LnY60/YYlA4nWZ9LF9KMKWN99Kl9uAHozKM3OskbNdNtAr3Co5XjCri6elKLQF
/lsoopQgdsuxIfKbB6v38tME9t7O3AI2aF8OemsIiDZ17QyVWd8DiHjsPsl0D7WI
LoCGxYgsbJ7wbtcxdWIMLKNAXQVBlniM1E3SFyTVkQ++59JA8kxmfCmrJizsOznS
PgECKiUlTJLASyxTopwa9RSnZOzt3apVe53Gw2XxWe9Ko4UIAX4fTTF4PjMoz6bL
y1nI/ikkhvfa5icD0gc1
=z5ET
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f86bb70d-c37d-47ce-a1f1-db60f5dad92f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Kx/B/ottlLO0l3xgBNgJleoLLcBgrdaCYeW1iQ7OGmvA
WE1fhK+mhqX8Xcde6lDkh4AOvgwzpP+cqO/RAGlKmfWCPX2l24eq3ec8MPfMkL0t
Uh2iRBrPDpWD3IO6Q5QFnl0h8O+3ezEoKynqqgEOSMzK/45cjCQvra/soRUN66Hx
GgZwxrbdvj95F5t3PnGErCKWZWvtVej92VH+l4w8Qp+kEyqVO/9uuFkkjmvDay/J
O3lvURu4XqZCXVMRvhpSbhA4Li34/Z6VYPDWFxz7SM++/6JuvL0aaA8ysNbef9W1
niohlG+CNPWdrmsm18wLOkdMWXl67iKtpCV5vooY6SNzN9jqDyPiypF1bHTFUOOi
PkPDqWQ5Pk1/Y8HB432+aiRR2kGB6TcMK2Ab36nHs/YNt7IAvTr0nSS9ga2PTdh+
6tnTEPOrH4SUqsG4/Z8NLqL/k+YJZgxSzQXghXqe3zhg3w7Zp0JXhLRVha6oPJG2
9Z+XteiyJ2Q3RF2YWTA26ZSgD+hoR34qFbJ2h1X0gw7jOOhrdH8TMEgnkI5yAcM9
bfmRcA0/WMS4dL9rsuWtN358ac5AHg7mpyW330matnF11Wc3AJcGAG3rQuNfOEHZ
bAHnY5ZnHZ+5AvemdlgaZXNgtnvC823V6hXhr4U+Ja/+t6O6VFUI7eiSbYqst0rS
QwF6c6+X0P+6fodlul7oPfoH5Fo9Kn7XZZcvMqOGRN/bqxsCry933auHUmAEwYOe
AEySXu2yM7/23Bcto8EWbN5iOro=
=H7PU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fb7495fe-32ac-44a8-af07-ce1ba5396e4a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA6Su6WKYEwosHJP3XZCtGpXgGgHepvwB1Og3Ztuu5am2m
6Se54QYKoAlPUXiAKSaMkL7ywPKi1WxMYneJ1YTHTFszsQVyk0MDJd6K0SoogDPE
okBtZI98QnB+qavw0ErH7ODMWrQXevLSSdJUgyQYu7FlcFcWTcV7YuJHfHpbsqJE
28Y2yMmn9cnkAsJ+ZSF2uEloGto15z3e54ly3o/pBtu6URhfI1hRMP6VKehrQJ1H
kKcHTyl2+m2b2CpZgCu3M3wLmTTltj4oHdIoBmD/ZzQAa6B+09u48WG+ovdjh9Ap
VcBBYuWqhYbWCYE1M4VnZr6zaQyKZg7UH6Y+HztKRJwk1DbVp4EkwrgI6e3Nd94E
oROdNYnDdR45DK1nJVWBb3qJsHuyAOoWz6w99sd/1zwhkhazicn921QYt3J0SClr
R8hF9txmm8fgyfb9Ka6ZNjg4PVVIxNgn0zTY3RuyHZ1IEJDZKDz+DsyPFPq/Tv7D
H3gjMiz86XJ4CH6Qd4ScJAEkyE4UAilORwEBqbztyORbAbCm73cJK2H974yY7CKI
21UgmnpWGF9h26/2R7suUqNmvhzGipORnNLS1XoZzBQUeFUP8+/QJRXqFmpH4P65
e3Cgr4PodIeeQzB3d+WjFFbAf2fEMlLpO/CIfuiOW++Ete/vmPd7poJ2P1+SlS/S
QQE5wPIOS5CfYaNwbScDZTEwyQTlfaykWCIW6afY4d5l6mbEyQAiBzbJw7uFtUo1
6wekmAxP7alccGlM/BiUojST
=x1o2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fc0d99ef-905a-421a-a454-8107ba035a41',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+Jr5l+134LRhczgbo1w0QolTLVzY1Qdh95wSfIpdKjtAU
h/nMbsnefOULLG13m3WyeaTfDthpMt4zfwxuCdE4TxJUNC5vsIVtOblyc33BofIO
UxVw58/uyoQpfTfM9+15MNlTfx8zXH+7a2V51w0QwKqmRJgl1NO61OnLP9WB4cOM
o7816f9v29ByDycYo/l+whbAa/jgmkxdyJOLt5diCbQMpgZiWuIfXuY0YonYM20K
tYZYGa28amvZvUT5MRnw+hkGf4WE5aTWxZQuub8N56VctDh+Wx6D7kTbQR8b0U40
9WDBWbEtlBGZBwO1yZc9ZqTGGF4wb74Sa2wIvaeoYtJBAQphTYNO2JMF88UDcATw
CstbapzshcKzd0UGh+ryGBVnqpKplMl8T/BoZs+/9oquV0+uQ3bjiPZUQGP3wo6R
SPw=
=q0XZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fcf3091f-df30-43cd-ae5c-37e9c1367170',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAk28PAKc7QOm6XDojvSsWeE8ftpaEr+UQbp8S4H6x82M8
4aDU42MSf9TAB4VY37aARn4BSmEcwjwkkGamLcbOUd2PZBeOiwo5VmkPEzjHMD5p
HZYFw1yEi5mirberFQzmcsu71mYFoEMLDG4bfl3tjPwnNoVwKlZrvPKEGBmiVHbT
qb/rmogseK2Pi5BHF/5Ie+eD5HtJZiZ8L4SYbe30F37md9chHWqxvW7IX3sNTE7w
s2pkErXadrKVn14nlZOI3/a1krFz88JT0+xbYHKNcL3K8PuQKsKeFBR6J4Pm5Vj6
ACdXPsRSX82BTYPXshXx7mUW0v+W0BgyYenmwJItFC3y9j1S/6D5GhzcaV+FIkHT
BsdObMmc5VLJtpu0EBkEnpWH7cxr5fXWdPq4N+u0M4mETy0hB4O9uofxHft63pef
infEIWrqlSl2ziJ4QL6kpbarsvHONpfMLJTERXwVmu0irV8mEt5O7qBEHbqS5Cv7
APmx7cAN4q3u1+mBFgFr/6tMfHbr6uszZZJjhlVd/EiExS0yYoktFZuIGI9f0IlH
hE4RvvRaBen0De0YkLxjLIWTUKHfHlw7RCCiVSO19ThJ7XcZbCdBEtgaQgkno4ga
yj7zntmSuGq1rml4SocIxiRKVmhjPlKGRMijLZyJDwm26yfGHEEJUICoroLCbknS
PgGhOObpHPIvk2ez8isGncpFR5pXhmHR721TCDo0+IuvIbW5BOl5mxlxYzm5bACd
KVE83WV4eaY8VrYlmy9N
=8r3h
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fcf47be2-ea05-47c1-a5fa-36ce5cdea229',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAsbB8M38YEFG3pjCzCyWH7WOl7xPygyDuurj0pOa4iEPR
0CLR605JB3XTdBCLV+sea0kRVcTWmvZHvZawbGCeeC4E6s/a5PO8jYmk7LHl1lLX
5cC2ThMIKzcLkMxEKt2mADtli5zCG+L6wXorI2WldcHTvjngtycwJOSEr/SX31Ij
Bdp7er5pXP0NmvVyM9wvTRm31U0CmFlKd/64kfJdpKoHI7XDhnXD4Wvkv7AYLd7A
gzSYYr0gjUjPf6P+EF01w454zs1GzCAeDFmkKYssrI4Azy3/i/FKKj3CvawOrBwD
wsoYPp2PImMLDyP/Jnb0gilK+XYpAGKuk3Xe0jw6ZNJBAVAbaDZEsbIvMWIhs+wj
zbm4racxrHEa74m9Q8dT1PxxtWDA8USWAmHcvQfxk2B6XrMfpgOD46eM/dYD5YGH
0lI=
=gRf1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
