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
			'id' => '00e95eb1-232f-4494-ae65-3166f8f697a8',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/b+/V4Xp5UwwdcibT93ILxz2D2I3gFF9KuCp6jtQkNKKB
nuxlnp5RST4yWooouVxa2oalyOovoaCt68z8HNNCNLCbqBrEWcSYfUH2w2TlNwvv
il+po6yCP55FkmaBYzxEZIvmDpnKxROHr2A5ZdaAG/R6F85g+MvuDpWBmARpfd7M
gGNgrAwt7LwUaxLY3d4WWVEQgm5Fgjm+Y28/iQ0YW1T6d7ZDV6u4EZIx2SM4XEir
36AlMd8NAyOkE64EyR7xudzKCnF254gsDBAmZzRPIVHLVMwGss2ifAg+82qaH0S0
dJ7H+xutjA5puexNX84UsujdhPV0+i9WsKUmBfFaK9JDAbhgAX3oJLDzmHtMqjfq
U1Q82ug+WWYfdAlvhsNFMnUkLoc2kMANBzeqkefm9qwoKgZl3QuuPeGvjcRRAx8e
+Hl6aw==
=t01U
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '081250f1-acce-457a-a0e5-cb9118ec0445',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAgrQ7Y7XHy1lx/F+TbtcerbzboKu6/7Yy0HzTpT1hNJb0
HBwSKbIVmb4Fs1WlTfxoSVwZ1WroToIhrAF9Me1TIbdczXRR1ZfTyQU6p9qrtSNu
LFFVPoz/UQ+WMS+eujeUIc1n7JQ+nnt00fjXfma0wpKApdU8RrJ82fBmP40LT/SB
gcOLVIBmkzIAAKUbpRD++7urq77pPW/bgPd7L0Q7/DANTOrPDPjZ3J03H1kYk2EU
sALbDdUroRSvpMPw+Jw8m5QBVYpMmcA/jZSrIz6J1k+7QeMaPodkXbhLXu/XYU6Y
quJjORsjxzzPHxeQXV5ItKOJLtsF2hC8o3y2cHdZ+bvy88aehwSFIEVingoscJJo
dt0vKq9NAiVKaarXQM+FUfgojOWVyJ/EuL4FLU51GDSjISxeZ2APPJLWjTMq0RHq
AIVsVbEUtV+6WH+GJDwZguS8BmD3jtFr2MQMlayPZqdbYjtdPABVYkfnNCt4+C/x
tZyk9EKj0XXiLcl7L3Qgv30i5dgRGuQbIgvnTLHcVBTkl1kGjt2hXD0aTLNWIIa3
QELINM6dRgjyc3vlECbQIhoHg7l9iqPNgf3+7sVqcfgdbTWGP/JGCLRCWHZhsEj2
CwjmjuUK7ifAsDqSt5RSW3RfXMKOWW1kwnxWWEsSIkM/tztAiIU7yHCOVkssUcfS
QQHWacGAGARX3ub2qPam0HxUfvwK7Sb2qUGkpbP7Vv0U3WLnV3+BTA0UqTHw3UIQ
QSZDG+C7RTVlkOZeYP4JY7Hj
=S+QR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '09b4f773-1e55-4079-a3bc-7f4fcf9055fa',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+OoMZiVGyEkmwrMvYihuUIv5NHVDO6mRVX6M73is9W7H2
Vxx8bq6vr2mOcFWcl5V20zqNk/7J8Bl8zq2MsAWef2Fdd6auIu5bDqj79MNAA82n
7mSH5JgmT0be4K3cVhiEbSGHe3i27iiUy6TfZ4mprA1pyXBAl3IKFwrnYs8qkub/
q3tavH7IdXGxayGJMfjTwtSyXAidA1VCsXTWW3QKI982rRxtbjqFPNlnas8GnmIC
mK1JNfKiksYzHT8kl7OLFvkXVZCrzxIaKwrm0qq1tMi8zz+XmCUa04K5IemUjfTY
ZXserqp3TclWUm6Z/WUi8PJYTv1YJybpHe8WlJSxZtJBAUAINKzR5k4ldNjPH0ig
dM1u7JZSms4Sv18bvJkXHfiNWrADf5O7G1PnRL1BCsLEq9wD0xVVIjmTWG5lKxxG
UUA=
=azim
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '11097fa9-eccb-4de3-aff7-6e56039cf85c',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/eogOCTd2soOoN2mUVofyq9e6IiuJ0Q9fdRQWcS9rjK7K
vJFEzwo3W8Hv1foWsXjd+0HrnjPqETqNmrI4kMI+E7dMhfCxyeAsdTc1GUXlGAXw
EM4tKcQEqEGka/jrzHcL8d46j19q7A2Nb4c2TcQ19rrHegE4jK+UmiiRjeAF13q6
NA21D2SvRru1WPHl9t1apxFbVsUa4Rdyltglz8hVG+R7AyUPfcrZC/HFA0uk8Loc
KzoefuYGRcY2ffzGYAmlJDdfE7nmdFtC6e+pVIz1KC0EAqUffLev3kwNhRFZmuz+
NJajg9plFGF91uCDGN44LCgOf8mJ1vfhg3PK/3raI9JBAXzFW1ARBYk4gn04OmMc
uBUoI961P0ScE0L/VNtZ4R669rLE/ZUgd+VpYa6OvV+pMNdQyN/e9wPJkxml4T0S
2LU=
=ZbPj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '14ff04d9-b491-414f-af0c-f1d0dd4b7149',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAoj2tRS0w5f/3Gr1TUyTFyuW/ZsqMbq7g0nHX1wMijwUj
ru8baFAJfxCL9J2MQRpoz3fZ/SwsJZgA+SVxczaYX019OTS2OyWJMriwBCd1bcKY
6eRrnxZPl7DaaI2PaBu9y0O49WaRJRMnCe4YOVNE7xWOmFRubDF6myfApdo/r8ww
lN3UnHWpoeC8AZ+R3mUt4uXanIiH5b4iY41M4TRav3o+dOKyc754Z3RNaX2jE8n0
10NylkfMgXhLXSRIJK84tY75htuP7lcKQsvjVL43NnYRyh1d72VqnmUugjje5LI/
FiQf2IeUaGXVS54IpDRXdny5xE9UhZRiYY/WdjpcLCTQKPowGQ8OQs6jcio4OnDP
uz7Gi6p82JxIw5zErwDnbbIXzap1tdSdqoWBLRd7yUEXqURcMVBZaQlNOLNFnooa
YF7cPz2aWyyP+69BYzKPm3+ddYhAu/yApKQGxGVfAJViD/EAY9BEnXlqfDc9uiHG
dt91DlorN2klS8RRnxloMsl3i9B8s2q2+YyocYpDVxFY4QbKcUmzwwBfaX7Af6IV
Uul8GRP5OencGJvldkMPtZNbr+U51BH+dlXgDhNjC9NSjm4lKqZ6zZr9k2uYIJjG
swUg+ydbDFSVb9CQwDTE9WrKxcdZXxlchEIZcoqQzPBB52LIPg1Ov3ZtCFB2uu/S
PQHqdGtxLJV4RTFX0EhvfGD1LBBFSKbK9Wr/gDeuB6CO9U/nBXYcnKnJvurynlhz
lacup7LAxCU9A/3izHw=
=JE1v
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1732f999-e2a7-471c-ab79-f371073d36a0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+IuuTbrbFKtVur6H1dqKyHxJ9cmjdVfnctuA/pdk4McG1
GVbz11vwAORs8zj63cvnuXNBu3EChl2BMrzX7fKp+J1gdjKkcquPY1lXdc5xQPdS
4PDwNZ8tiGA5Y0R8ZF2ooYnzOE7QKRI2a3TmBO6OWrZM1RTgKhyzxgTjbA3W1HyM
EV4OwzvQcC7k4Gw+6LUfBV0iJxMLdBHaeLcldQ8TCqUD30lXbQoDrlWyI3W4KilP
W8vP596droIehuzGNhMutwmNaEq/1BxO0Eo+Db9/wHZBXevMh83850UoU4kA8Q3Z
TunZomM+fGeGtJNd8M78X8RF9PpFrl4GHg31Fy8U0ZEJZh0XZ9qKt323Z8sK++2j
ZcmJ3nLFFPHXm8M206pvy4I1vy3yA4hhL1ACHE+CGhGfrDbRBDz3MlAy/PUUjzRi
fV8OazCL0xU8mWnExsWLNLU07KCQakLRudkB4V/OQWoTnVV+YicB8nUGjYtQkMJA
CBzTtkLLiPBLeOS3M6rNWOuBR+BZ1/FMEkvVwpjIH/Alfl/gtlW3yuVB/TCTQyLG
20Y4rRr40UA6HTGBKHaf1P13OmJDGxULUwoAYxRCBQduPWs/Nt/86q+TxtI/kD70
O/EEjb6cm41qDVWPb/F4QcyQmrGWJ7BqwbL56Jo8NoRqZEYH8yJfgvm5qR3YJGLS
QwF+v6X5wtdbnGiyhByrs0fAR+UCCz387iR60Hav3BCxom4kMjdH4GEyxiDox+x7
IFbyZQJMc9Y9ksK5ESOuX6lPL8c=
=J+Io
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2167c7eb-9f99-4be6-aaf2-4f2544567788',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//RoLWXoA8SyqPFI3WG2GyG/y18bVtSh3NerCrWXE2bCcH
QQB+X5WkIRnS1YlK9jWig+trzxaY6jc78sqs4XJiowzIuaa9O245nhf3ERcMuS7Q
WKDSF9TrNDHCRE0TPkfLG62TxwJ31Qb7BEStys6P6A13wfsrKQav+ATBYIDFakCV
WKrQ+/UOdMPIYoxyyA9LkYwCXlTVBTqNnNMzHOWltJOkIrRA6CRpvO9ydvahsxbU
NrYCKfz4Z+iCwiKqIZ4bGAaSp+pB+YB5EXqsEvyfjNz3oC6c3FEGskag85sVKl/J
yvFYmw8+sgsHBwq7eA5pNAwTyS7TtgapAL8AEMZq7TUQlRSFgJpexxUkktUMOWgN
fCioTO6mVUbUhn0AuuDsRGoVpjNB5blmDeGjsawTK4DZ8QIC6nrbiOHAKMY/JyXX
OSdAqRCDSw9s08E6WtqckPTCPnfcvr524JPvtUvkTYY9hy2XDH4hNM2RQ70hXow7
z07Ro0VPSk2c7iCIjL3NP2k0HTveA2BSZsRai4Pjc6Iyl1hwBgLEJSYudtYx30mh
5VWZi04BYRYoxE+/37UJgruo0/dySi1PcXI9BLA4LI6qpLh8qGAtGuwKWMuT14Zg
hn1oJWFI/sAW03peOnL6UqLdE8rJBIRIvLbOnDEHh7GFyluh5IeGrJTkdpY/OrTS
QwFf+61WFcQJhOQq+dV2GdgdXGIW8dvtBJaZLPTheTQ2EkmrYGy4FC+mLVuINALj
XT2LNuvZb6MziWZiuiSB9vAkMo0=
=EUiA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '21948832-3220-4cb4-a2d1-24ef0b73d0d2',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAse8wo/IQkLEpPfF5CyMvmc7JDGrvebj4bgoZV2XbUybu
BNkSTobhaI78/H5kIs/Oejw0GivejuTboPrJW3C7kmO/8PswqwGjzEI4Wjv7dBVT
+yAcmanVF5mRXU+Rrzi4Ur/mL7Zdbrrpp92K/HWcGEqkPwdDoZsci3C2d7SCWrGu
YW5p9cqzmyViqY0sKnB4UOJiPfP2x3CK1T02n7onCqLK0ATB3TLXym9F42X9lUfK
Rui+Qnw1e9nJ5ux0w+rrrr+iIKbgV9WVzrl1n63x121DjiO6lm5CS3NGCAFw+Ssx
cmcqEcI/CXQQRKq3OOizpTzA3R/l1DZiCgsJyzaIgfBoRjdd3g/VI1pct7putZfG
fdgd90DTa3YeWxptF8+wceFSEt21NzjvZF/Tl8QZ/oa0RuxHoZK/vHv1yW7aIyIc
/t5wYksbgXhQTBEegiCpE7Imzf08P3YdqqhtvxRkCSnHiq35LF+wkRMrClliuHId
5HFN2Ixv2MXjZva/bAa97YkrWmTlySZrXRVUdQfQLU22pw4aJcJaVP8au76FJWEI
5KbesgzjotBvQVphnsSOJ0+Uas2K7IR6vBqnMld/22IJg+2RluLVqTrtDhRxfket
go8Qw6BmCj5U4if+v1pQmkIgkEAS0uQj3xNCYuwPCvNXdTFm6lV/FDsIu97pJLLS
QwHByRy95Jxp99/Z+VMPXoAgDc9/K5D0/5m7pigiqjXfXWhq1HtwMzpKtiN1jHt0
bxlEGlOurwkdpgeFqQNNzmSshhM=
=PlCJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2acb3d96-4274-4339-a8f7-9695da59fae2',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//YlBrczom7hixNdiW8fueje0RcsBWMCFLI8i33124iRA+
PXtChbDhhhfXaBo8UeVDQjcvExIhxVcWIm4Or2NNfY1Xv+Ai2PgxQHpzKP8K54YD
eXwZsM2Be7WVEG2DNFNgGB+kLpv6qRa3tzigaGgvOca1rvqB+DvHDw/oH+v21pjz
5GeSUDFikVPM51MgCCH9DMbxJxoKFjm1bscKYR7CkaebpmZG7lO+xHPLu41iogf9
AodPuayz+HCRgFIBMEir+uCl1dbRJqiaRJ/87UE5OLhurVHKnUyrNvMSTreM7sVJ
uw6uQU7hhjV4KZqvJr+hlX7kSOcmwufAnXnXDMOxIbJ90Azw5HUxgIBR+BnO5MB3
WJDRCbsAEMNWpnD4kMNfBgis/8k2IT+bIXN3TXq39wFm0RJHa4ysz+XSRkis/pFZ
OT4SqTJmaSB8eyrsPSCI9HaDONlJ03ZugVhz09EC31WRyJyAGJ1kiRZK21z/0jx1
qKySxlA1Tz6o5Xs3ksy9ijBMxZQVX9tSVsd6QMdKkj5vMi5TVt0FHOU7cY+tCkuo
1bDGgrhSltQMI+rtQuuHzwz2v1aQJvbOs5SjJiiRO0VGcY4iPZoIOYOElu57cuai
JrvdvQm+Shabzm840sNweyTM9c99k2+YFfB4cHFF8EdN4c70IESWzxf/5q5DqW7S
QQGJvg+xQK4epE1tGd9KafZRJxeN6vByauhuUDnmxzRaqeXbtnzz9YEW7QB91wEN
lYVF43s4BCRw/99sUqwiCeDw
=m7IS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2df1025e-9070-417e-af03-0301cc8b01eb',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//eChLGKPqrdnBBDJuof1C46VEPTwRbHbZbwltdyeUCuXl
eeRrwvVsZBTav/5wEVIW4FnNjlk/pBj1DdF/xVGAdmu8tdg/R+0XWJqF92qk1JTK
QRYHpRczP+W4DVVQW6hJFLEdHHTLxE6qxR2xa7U5Sr5HBqNXcuAIIxeycJpunYtL
S847oBEjmTcEeA4kCPPaZORcpLSG32w93ccrXKcehH9I5WKX7cEesHW2DZYcy9mt
Mm862zqOmHmxrj8lIrpOuQU82+quajdHXRMQjhrR7fZ7ub3BPQFgJcUb+1UYC7LY
hgpH9Cj43kvpD+vsYuYmH3Ifnd3kPg5XWw1ikjgVoekXXZruy/LiFot8CdrpkaWt
BUi+tMW+LBNbwevwW7irlphLQudRKdO7eRakUq7l+XVxvFklQBjWccZpOQ9NBetj
N7oK7hl3n4gKBKaCtZ0UICpoBEkurwnHJwkdX/QV5UYuH8WXYohZyaPZR6LHa/w2
dKZJct5lM1Ohx9kKYHhJWpPuVvDHj1KgD2knQHGH57D4fftub+TvHAxJvoc7Pt1F
TYIXIKpeq9lNnd31ScZ95KNXhHjEALgAiNZ2G/NKnVHlJZtd7aeuWQ9dxDDFu9Yp
ukmxLlm96pdUb6TjUXBNkwrUPAoMeu/5rwhHrTvFTdwonhNh6cUR1u8DsT1Ay5HS
QQG0MGlfD1T6Krh/7FEkpGf2HsqCo/7IxS+BlHhMm2JIQSDoxyhrIg0lCb6hrW7E
d14H5xTo/w/vF0XdOtPWcZtX
=H6VE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3063893c-8f1f-4c7f-aabb-533588a9c4d0',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAjh+Yoyb6Np80d8ugCgu+QrJz+SA7XxmUFBXt2aeGRAiy
8hn7YENayDJjk96BaO5LQQvK3CtjO1vI9qPbDp6VcLJ0zte8/gNnUvVqtv7UcSyi
em7Dn3uL8gAUtilbJK7Nh0jSMY5oZGWAebxDtWKGixkTvFTQwnB2N0yVzxvje/xU
H5KSPZ39eC3GggYgYEHcv2IpcygMALI2zLbFfGEu4YEceDRDxfz1ZPYA7l1ge15/
NxWjrKJw1jnan4rJ6o0yApRP63uGEeeE+XtdCfagG/7EOfnacB2D7e8g37h6GyG4
GU0YEnmCx0v6pzW0ca42m7C5I7kKGXQ3I32Aua+elCXMPeiAGCiKP9dv1TzACWU3
VykRqhmDvvi9SUljim4ABOSVPp0p3eWnfKniOaSSyfs7sFFe0S5wYREdIRcP0vIC
yjKO5nZ6rEoO1S26mXkd/kG2DFYEN3/WUh1+R5iHtIIN3UfNPMoj6ImZdmhOb5f4
ptehDLuNIqPdIXyYm/Ehc8b7AZKbKo7fkEO0PmLTbaBeC2F6lrkZxZlBKfQhV/jm
Js32WkQaQdP6m+hhnqghAGUGOT9aGYbXUM+XLfsJuWxTxsloJj1DxpUMz5lsvkTX
ZVHAxsuogDzjVtSx3tcYXnH1BZOcSaqmOwcfweOnSnF2I/op/J44Khs9KNp/KEnS
QQGFX2QjWrVJ8ARlKD/Bcm79h2j7ZBlWxTMHrPquNX5O94Poz6Dv7HUdwqGbXFUA
JH+rR2FT4oPmZWJU+Dvnmtg4
=FNmA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3438fb3c-651a-494b-a755-5e4a81c000eb',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAuFZN/CUDzxwtKANJs5al/42PQvgdMChnTpkKR7odNzAH
B9sUSJARDdTGIs4TxASGZ5RSboks3a4Yt9wFlkZ8NKYbheV3nQnIIH/3APWd/at3
eCgj3qiZScFnnZDAF3L6AgYBAWZdrbd6B194oG3kI1tv12dkIMxKwiz4WuMsjwmo
RpDq4uHUouNMlZGR1ixFVgBM2nc/C+JhwfAyGQ/8wQIUEJk2BKzXTHLAXnM7klmP
TmFyRljUvPYMCz5ejR8y+QZ0wLvCXfUFHenCBOpzG5ZPezkdYdWzmEophoyDvcDm
cQEczaUZfTeNgF8aG3iBd4yC/HoyQPZ5KMpU16HUXWZXqWz3z8OJ8L8iTilqz8I7
GQ/aUjX3P5EezvonZXb8qAk01RSZcT5ImnGqOwuH0xAiEGEzGx6oW8DDLx4s2ytI
ivxnbebANCbNJq7GVpZMyRr6qaiufx+fyhDl1Ab22OaaIm2fFeB4/5MrhUv/I77o
cP5UxlCupK0RbETFwtV8NeRPN+y9BTKQqYa7NkSNNt1i1keMkYPg1OPLcHh1CB3q
tIcF7xoU8LG/nLJSlF5yHuQ+fpcpaGquUbKAUHwA7rP1RdUCKFu0AxxPSqBOy5ro
CTwyhm4GeOCao0WlaSS3tT7WxF5QJyZM5e0uuMYi+RYdBvAGzZw8CiNfFOXIvnbS
QQHRkPzH9pSdp+pLhYvI9HP1jHgEcm4sDPBpv4mt4ZTC1IV7pq6JKaIKz2VT13uh
iv0XGVhZ3+n9FkYvGgfqPGtD
=prpu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '35fa30ab-a085-40e8-a0eb-11263a15315b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAjO4aWhQG55mvy6saH6O2iH/YymbdZDvf29nUKN33Dhaj
JlDy3UJBqfKSAdfdtmjNBOgQyXe5utbi9OyUG1EIn56hraNkeqcf1C6V7q3rhZ40
mmSXqCPwgO3DesnT4mtqJQh3sVS3qPWtROKf/mtoHWw0zi1X7QuJCRdrHbEHQC4w
fr6OxE0LFVcPOtZwt/wUi59acPYegI64C9VHbMA06whzy0jlqllruVsPElnjrcEg
MBqaGHK7LCWe1wmAGwuQkCCWm4vE9WFZJyauRnvRJ9901pCANyZa/qhBXVqdZVGx
POG8AfJeSWE8ViD6zgDKChbjOVWhCxsdOBK2Xw/LTOX91qA4jtrQ4d7jLACVyJ63
eGBqcHRUpmLnHz2dhH6PhkrqKPVwW4R4ERyR43ocYolfQdV30h8hhECd6sgA+idu
Cyig2hJekJ97HxAY/P2LIbFLkP2T3VW0l33cJm4f5DBcCC9r1Iy3d7WOoY84K3gO
zGEocj9hHMS6oUFwWi5ivY5QIs6lucqCCBpC1lw/9xhJLYZRH4R+8fcXjBCpDPH0
FzSBEF1NALP4CySnaL7V1JNJGvb5EcQsF5CioZ6qvd/PAzYc36K6gDUGdHb0yMSl
1rcRSLAngdE0O54I+9EoIciwaL2KNcGAFTKawLWrXjKP8wqvwbR8am1Yk69Nh/zS
QwG+XAVWvxUXPbegLok3xmCo/QOXo1LjViip/W5eVDuPVVRsrEex+wOuljE2b78E
4eZyBP+RHSYRBDR5VktXWfi+CJw=
=Xt4v
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '36759b09-ad4a-4310-a528-194aba8f7ff7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/7BlQJfM2sLfM//rvbLTlLdUHQT1ZfMsiW3iZLp1FgdUVf
3cRH3yenFv3a1XKRncysInGZAVpW1myLZySw8DngyUbKmnldAFe3far4YxXD2de5
lKAtCuqzw4zI1k4VLORbKoY+qAqtbs5XvmT8WBDWt+A0xyzn65NQpInBXb2T4bdH
VRcaAu/gX0379mkxZbeqEteHmNNt4erx3GFc+nfSzERqZlkGPIJp1r+PQ0jrxYvY
thm1FrnQKChIHc7u0S82l84/vo9p792VilhNJtbqZbpahSk9HDECIG7y5z2Y/NlN
nh8ntnIAMRAgk9izNWBBGUJAPYCSwTgg3yp9t4gLHQ+7CVaQ848ScLLlC/IcuErv
XMUFWUM2fcP6G1GVsjcBkNhsLoJpkNznNP0BIuQw1/7OjK2/BSYJ1CIUt5v8kur8
ucs0dbEK80cen2U+KLsMgWgixy+ic10fVEqsCCkWU20cuXCd22gl9/S/OwMXHCxD
pE/KlU482fJ6rrMMpZ2cfvMRGWJh7tCppwsnUY0KRO7jVdS9cRxb48YScf8QIS8C
/VRNCAK9CQ0K9XA01rgZqGmx+M2X/N8QVjTBTAI8ZCiIsWY6PJdY/vaXWfyKhaUo
pxvOK9oiNVcIcWscRjbiA6fA8yN5/3LFZcPS5Woor290s1Cyr8a1/uWQBkSGF5DS
QAH1t5YmNcZ4KBODz2qYxn9cTPNoeIkJ1OtZ/LNdKlXiz17TnkB882XSg/3DTxGt
SIKDA4cpDSNSTkeHvo2/a6k=
=NQbR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '38d2255a-d6d9-4148-acb9-388a37a3765b',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//VrmZi3vzju5Et6z8xww25guSLI+GakWk2S8R2V+CsdWT
y0dez7vWsRWZWWuDF08X0U+xcC2yakeAuxBXaTLdzO0RXN7ZZcI1rUcjLHQJBaz2
4rtPvc3gw8w8TzjUImJu5xZD4AsSupm0gzXJwv7LhfB0hD7jTKyReMXSoniZOQCL
2UIFHwsBAAFwzusNnVyhFigXxyUhlhH35rpP+pVaAgdQl5J1UAsZkSZA2fZ/PH8E
BjW0q3+6WG8sgv01FQT70nSzU9U/9Xg+0w9/5mLZmTXgMsMwoBFSvH8GJG2jwSTn
ddhICBJA2M1QI9G4nJ8WAZcw9L6lJeJ3Ufl7bYjwSgoBtd03dQ+yt9vZxFBcwV4e
XpQ677nceKBQ6GA6Zh9LTDuPDgryax/njOvgOhLflNDAOb3HAX8VH40riP3cJF6H
69m7qHNJP0Aa9fa9U7SQmcyXb+NA6b0wkfZX7d0QjZEcuvbTSnsM68SY8PQmiWrz
6A7O5JTFQydmuhdt4Fy4DLD7MK/zblAbZfwMoy67bhhE1R8PJzv+Kpwrcx85pvcI
QRJiv132xMzzUSO9+18mjtWY9oA92VDiTpQOnAmahGpEHLnYX9S8xBtoG1UkXD8o
TWE+jPWnLeaSbgylg/5b1sHI6xzOlQRtKzkekAsn4cUVEOfqU8PlUlNWBp6oelvS
PQGPBje1VLJ8bnouefGPGhxLrgUnSHfeTV3rXAW7UCTHkwJk/zZteOT6+wt2BHUl
4frVtyceWJ1sN3zwCkA=
=kthG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3a0f5313-b27f-43cb-a71b-832e0d8ca419',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//e06e5E2q2h/7LmZg+ra4lk0Se1qWwRfYHPzBaDxMpJg1
wHyASaDCg3YlZDrcfNhuNxP4Na+Euwmf3JP8eA6we8cZRZ6yD/wNzSo1q9FcIaT7
8GpPE4ur0IPB1qpYzQNTrRK5OqMjN2xxoRhEe9yQhNjYeB8fQBHTtfUd5qxT0M1O
RNHf8KZZmTq5qHl+JnRg3vzD8F2GaP2XpOZPXCsREz+ydBogbSL86n8+pCUzP8Ad
R3H/UaIDO0L4MBi/UZERY3q5UONmb31jaC9OfcSnfsYSxciE/uimhcAe3sWrVfFk
+WIoHaeE7JC+xGCEEsRQqfDrt/NxUhRM3nAQPa65knvwuwjALBknli9Aj8/bA7KN
iROPVo2YeV+kVKdZ5go2dAGoBpe5QvhFqmVjE0fHPD3i3jQ+rujDqrW6ww9dlD4Z
GC5gu8eRkGkWluF4sZWe72519uLYjPI4XD0Vwbs3+NcQBqHJf0AxdT/x+RrSVnk2
cWVc826x0pT1OUY2Qyvw4r+ZeZqSSzNN3VGwZhY0ecF2w48rhRpkarZA7LiU2jX4
d08nL+n43VeEOrOte4DYXNyVrMevvKtdx8LqLM6xaEUuAwBGSHbEE6qjSxH126Il
bX/jp6moOR1L7I/TQ6s7vffJIIpQGG31lwfEkQUhrq0ZJZqX2Denct8o0o8GSVzS
QwH/RCa+W7F7n6M09vZJf87w2le/sg5LPJR8Og/3IcoDj3P5gvnC0t6sMUnia6ev
Fwq6olm2qPsqHV8dpTcdhHv1/m0=
=RArI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4038f395-a6d0-4ee6-a099-4f2156e526ab',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAkTGECr5ul2UBLAKj21TWW5BAiSFfSwBI5rou5QwcOddc
O99LyYBt4KO3wpRDE6dnAN2/E5NlV6WSE5VPES/2qQLyr+9iaX3zJEQMia3dBM+v
jNT0+ZAaPd31F7CJkh8+GlgUKdl7cvddFzpIVh7gYhe9m354Fn5dwxEGK+mnaii9
0yY4mGnb7dxjdXJvJQitg0x0ZMUZVRB5Qi4e2otjzRnMlmSQrwU5wW7/bOeoTQd/
4+orHadk9EPCWKqcibWSucHOq9r5GzX5hLh0YHhHsbF7E5yM1Obunv/99rdON9oO
cYqYpItFaGziv7YUZA70ggnHsyBgy/eWvYRB8MWrBb/sQHBwDYjjn06V+PVbwygc
Kf9cIvNf7JGRr5JGcdXuylpLq/NMN8UNnmxFGi3XLMnvy1HFf4DZ1ckF7DESNJKg
QXYaKCX21LglgCXGfjLd1zKywbEEVB27dttjNSSi+lgxpZFgspMDS5xii9dRDHKp
MFLtRVEg8S1xzKPyVa/pA/3PJ+p0GOMRLjy94dRTDuGP/PCOBcyo6WAnqCaba64n
XnuLCQH6YWCoabmT2tvMfuiwMnGVK0nC+ZBqnlSE8DMWnBwL9reCMRNWur//XGsX
nWMqvRLtoOqv9oBPAe8Tu4m5m6V5J18wHRKtkKll3GME9y+vrlCszti0s45ZcnPS
QwF7l+6G/6wOT3AvijsCPPmfd1abwBVjfphsmXDnKAYBx4iYdpNK8c1O2VJruT5N
ue6vj54iTxxFIPcAtLcN70VMB8s=
=+BQy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '40e45276-d150-4841-aff0-bc8b4700150e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Xs5cjgpb5TT6mrvhvPyQmVpyM4ogLPgIW0NlwFgcoY3E
/i7TIrsWF+9hOJPHXnYY6qC8EKt9rvhdvtSkoDT7kgabauTTYJ1tE+tNQ5/zDBUp
7YlyY2dGmEuy2svwITuA9z3z/+EKwKVbChuzo1Utvy0VrK8YulTJln+xt2Xwz/Q5
Kq7czP0l4kEBMFXic36ulT1IlFQ7eMC559YjyvaK3651/4dgMaBY9ADzgUUb6xbd
QTmaUJHfc4Io+HX6DZX6t12dEv4482EGONQWIk5LZ7pWpIbiozSe3XSNYDSuR09l
bZNtrxs4zS9EQDZiIBjafbDxz0PQLg0UtH7aGUNX5Vm9F8u5mJS+GaRI4FT6eQEr
LCiROqbkNkIR+8Is/nh7skqqKid6qV3UqA6vnts/0dmE2ag4q6wk+i9TuAi8TrkA
1ZgFtYxQs7jUI7G3FVCA8VdbFrPkfwl5INSxPkhLu81QkBVgUXmmT0j4Kf3UgoJ5
uJe1BbiyHAVFypBgqA7pZ7DjewnZZQAWY4xLmQRWs7GqV8BGVZMX6oGcYVnRaVFv
ipyBCcgB0Txq1Xcwn0ZL3fiRfaCuEuW46XkW8v8EqstSCz1DHmyuuV5JEEDUAqXC
mBak50V333i647jHXv8D6VS2mSY2Ux89e/crUrXP3wOxcuSKi211SlUcWBc+II3S
QgHclbs+G8mam/zTl3oYiNMVEFlgG9c3ZhpxApTtS5yJOZS+qe+rRZ5+IZq5Eudp
NRK6DcD1M9nbYm/nQA/kn6rDoQ==
=4hdF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '45282fcd-83b0-4adc-a085-e30d9bf56815',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//TASzvWnVI/wFi9tVsLVVE2BlfhTzuCBZLzH8ISFqN44Z
RrP4suWckqdxEtJQAPoi0vPF26Lb5UT3w00UKYYV6tvd3yZmmDUPuwujSIZ2cw21
togNSHIfOf/9740oy7v88jrmenTRVe343kgHP57BfuhBffJKfg5pGIVOHoI+yIbM
W83MfE8bB5EVBKvZ/NaU3z9C43Nw8hJCpXh7Hq8+nEcmA3YRTM6UJshrCz1WET3/
i5Kot13xnWBnuGFhIzxK9zPBWoKbArhw+6qsBwOzUtbclu+20/lo4mB0Ye+ikhVk
Esd8GZ34W2G0u88G/G1C3z2ZUmMENtY5YwsyWgmo6Kk7GjAr3oK1c6LIjPBFUEnd
6KwoIX/yFAgYBhbQYu/KtMU0SHsG7MmVRcWhPgKz0Q9q+pJ/i+3q1oEjPjDQaV3r
H3oxwsGKT2Oqa4gVkYhYnhGpKSJ2ONHu6yZn4P2UEPtcctcsfhJV7dicUBhGPLqv
Hh+1eRW4a2zm+p5eOP8Ci67WM9/TenYos4vkbwtlRcVKQ5Fsz0LTvhxZ5zAqyWhZ
jtL2ZKYmKcc9YY/J21DzZ61dW7A/WkknIqiXw2RXKi6OBYEQUSH2kjzw00G7laiH
ivR5k1UelLP7NDXGWLaJ0KNfRyV44T1UaaPf0uHccMEls9ucuIqKu56Kmk7KzWrS
QAGBxUFkZGJGfpr3liqP5oktLYhTtBHW4TuuOEMRPVwciQ0wdYUQ6yrSS7uzD9Hj
upgasxQMuoyThDEuEVr+t3w=
=GnOI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4f2770a1-4a58-45b3-aefe-4dc81cbf91dd',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+IpS7O5BFkaIESAXiIiqMCSeyYun6AoZvszAhmgBMB1ME
rRQqd2+JcyBPgzaHZfoFj+QSuv2lHRGw6lM09XNWB+PYltVixSPod1ZP8VviY7GI
/oTnDXZsJvmnXuyJehC0HHKiHprXp9RKlBAyJpOUkw0wnQ0je5Ol+bkzmzbTzt7Q
WYPZrZqessrScbajSzd5ELy8bLN47SirfzhIv9qlX/vG0SQ4UBMAEkUReeWukisA
lBihsHkmYwPMQ9xo7fun2T8m4sWgWXIZz9vx0c/hrLScHoTvC3wNenRKTpI5I/H7
Sb+Y2rvQyFfFWsM0POAEXO2krimGCft7y3J9TXeIiNJCAZEQfZmGQ+aFR4QuoAUC
MIrmQkrwE1L5ikbeoev26b7IWJCTNwP88tvLxwuHn9DYDjqa/ZcZloL4IPgBi47I
o1Eg
=xEVf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50c58ff1-b983-476b-a432-27c5e840da3d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAnCv/IBYZJ/Fb8Hk3ySQUiPcWxuo1AK2ddm6DFaTApahK
MoBb7cWd+e4DzpQjhlMHH8KhAOH4zedE4MjE5/XBeSC9+PtQcX7U2Rj0M+TrPVp2
fkOFvdjtbv1N/i018SGd6Rh1is30VN66Y27twnXCcAGBvlC2rap7I8ZU3LxLe+Eg
/T+fOxZgpFE3igX8SbCfhiXXtE7Cj0K4qPel6RSFE87jCOmU2o8vtK7s5N1HekDE
gBdCOVava2ryQEqfabHfuvBj+MzlNp69I89LGkRqecHXQrPhfavaXLKIBc8f7X3N
JtGJ2GBgK+0xBh70wi0kC6AdxGi/Xk4y6h+/wCzSVPN6ATWyyBgqVRZLUNv7Yjqi
GZmmHUXXxp4b9DBC1vxAiHxO/8U/QFQ0oJXtF2t1g5DIFgjWWgt1jnpmJii3C061
U+OVpyQM4Ia34yNqM9F8uEeKFhQKhQ93ywiQGz7RPg3Mm2YF9mFR23l7i/m/Syuf
wrKja8iM4Nfus59DxwfPO7Dh5gILQpkfeLycYPefUPNVtg38mSsOsMbJU9ouZeN9
9ofDBJz0mGnvHu082njRuUS/KpmMQhnx9dkBQTmGcArB3hxM244XkjhXCXW6lbzW
jIRSG8j0dXqAsZrzJMT0Hiu4Yqok0Q0m4nhKHEoO6ij+VF06pfMw+2+dYMBOW8rS
PQF+lXeA5Ur6ihvjOUUaAvVRuVDHDuvASpWvVjPoBsOKTDKv15EB2E0rpB96wfTa
XoAvIJIC9T8GMXaDSjo=
=c2W5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '573e87d1-7ca1-43de-a366-a2a0cbf9f5fc',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA7ILz96Dl8CAVrzk4JRo/BgJl5C63MFeKSSdIuI5lHMqf
TN90YfTHzpyjB60OcZ1o4DC2QvKve7cdm5jjEAsbDGN4xnPxfZ6lm8y4+udoLbeK
p4+mQaKdDQH+cBnX/EXGXkUC1C/cCTJSW8qSU+WWWr58budySz1JrRZ7Eb2QmrvE
u8qt+rb9dBbg96fYHz13W5advwdlSZ/y2WJNlyepUi4z6MTxrWm2rf6HKDOGCYAX
INYyaZEtiPl4kCDH+UOovh+dTq6rmFBUGA2ZJhRXDdFa32Mv1gwXJc+uhvl5Fl8A
G1VrFXYQuXmvBF892qNBH5jQCtGVRdC1FvX/LcYo8VgSlXuZoKjEvlPY+WIJPJHn
8QNjYb8CQPaLrdy3Wj1wAvCQ1IkUQHt+vkb/PDA8bmTCfV9HcS6h1zgy7y4ozOwf
Ot+s5TQFXcne50EW68A1t+fV1PWNomMMqrIgf+D0QZXbeuaICjGp50y5ZARjD6Fr
hTLu5mQee5x+dwPyqC4erVukGkr+Dgg/oG8ToCpNijAFJBmTU7ZQcplVGLpDK5fV
3rLDHEZ60vVh6RL9EKV/Hefc2CNLxAT5FoIMZ6s1ql6XhvSusWBWyUwa3I8rfBV4
qPCHynkAPobEyzLf4wHlryw6HWYfK/27HlqtIU5rnG3iWXk1eViBG7NjOuxVKUbS
QQF1nFXBi0x08/owJYLS95SPlEB9rdBpU1NjyC9T9KZ8lDwp+3IMVxquTLwlxsTs
8o8683BWvfNMHn4n0rh+0CuQ
=zaKS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5a661659-9056-41ea-a551-619d21a17ca4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+Nd2ynDUfIscgRGGBXIVMLpy4QNZcuCRja/HnkxxzF5kX
11+00VmCsg9a6anV99FpgmTqdl8StB7wxmrWEaEeS1/4Bc9989S+SMjC+amqL8zY
7oK0vooIrJSPfSLxsbJDmximQdZmPA2sB0PETT0WyxyJ7BuAyUV4yKALAai2bWRX
CMssn2GdU1nHRlKYAfm6lwCZGYf/G1vvs0BcK+DnAa2xadWd7CzjKG3LbzzMEXdC
KpQjnrqpe5bMi7PDV5jfn0a9MgPTAh9pnIZtno9uKGvbbOG8SUC1fP8FjmVuvA72
duXuM7otfWSsG2AMKuoQyu4FokltpU9J9A+9VnLZXOf71yzu+Bnoba2o9UC1Mt0D
45qC3KR2pUMyMKUoW+PEsPAzwZfh6tjkyldW6nPM1WDMHQtLK1ViyCUh2GpI9htI
zt/wVOWZBVBVDIDdlSY10fcng9sena/sCOV2FuIVrQ5o6fAxaRij7ftMWUGmt5O/
iUxb81B7nY0vGpZjBNuL04sPoXgproMskxHmufpH45jyLLSsfgtKtQUxbWTrvdqR
8qqjJ9lAJg7FEeT3kS3YVfFZv0rJP/GrqKZOsbSyQPfW2r2Hr1T7zXJaSYozAcG5
HP39eG91KdDofRxIACxr2wLw5GX+iiEgcLbrFQwnXDb98tSSPJLOUFQSCvEQrSXS
QwGe9CzFHh2X1nLsFsFe6iFaUI9ofEc78VfCtL6AvOqlileC2Iiy9DhTfgNEyEFA
xpATzWycAA/2q3T3zwCK9xjPfiA=
=4q5A
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5a66da78-866a-475e-a998-aef95010cc66',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/7BJVIQ6GfPCDiH6AzK0FPhhzTXXHPbd/dMFhDcBbwKV5N
FIw6jyBdM8IZLBO6tAttB6VUDFNQgrxm9gK7rEBIqiG5fp+HIcQqg6IHL7Ho5LAe
aUWphaG7em4DGJPRVTuKyqgR0rHQ3iIGbTtz+/6Xp0SJjk6mPOhCQ4+EMtTDra3K
+HQznK3gIkwI0/NDZvbzUcRL2MHsZqhQTsy2xdNAZCLBeukOZXByqmjt72JWWIQc
s2HtXwY5IYPERgU3k/0XedMqGmCiEG3aj2tzwz5u3Rv/FPX7Ms2AYA9nWdMpmgga
3BRQXg2W01RTpM6ybxWaty6CYIjpYcBPCDoEYPZzLqahKecDAHhmsJQNM7WB30DX
PJL0ppoVKRKXSVVRvNj5oAbZsapePl+B8cSGqnK+SG79MKy4hVEvVMMoYO1VaIAj
tk9FfaHKHSKsfWx78wiuj5MsdjfHqiIDZRJNPGLsCnkdm+WY7jTu5fvHcmcxUJr4
PQYUyAl+rAPzhwImAlLcgTMpyhDteCnCibtm9sMGBBB1C7lzR6w1fq7wOox7HyfI
FNMTczBG79oU88ySxqsXnMFKPqavB/xeW4rfNEBqJrPorI9CqGqbtY1pM1w/5nJy
1WgDXi/MH1zRaparaKBOPfGEXCMIBr1LMxu0lSCfZh7f4wYa+tA+z8T68B6mnsjS
RAFp2pVh5djt+DbragFXxFr1f/Iay9TRl5zgshXOuAj5uiVCKKek9+YVWG9jjQV7
zbk+AB2AvvsT3Nlk4jKQm6CtJjeQ
=RIkX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5a8cc01f-726d-483b-a117-328fbc70f72b',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/exGQDgoZQ6rfRjWBLX+8RPSfJ+ieR32Eh02X0HK33NkJ
tNd2F13CtjhEk5/+9zKYcosaquUUiLOJ9KaVhhSNaS/vSqLXT4u75fOKmsX3aB1m
nbNM93U2LXospM3bQ+8XEVksIEoWcbxmt8E1/mKxZ/INYZN6uzsUjLj0mjxl2dYO
GzI/PahTurMRaShPnhSbl3QNTIm0Nmo3qXXjkXTpfZqqiDOqYvtphPu7tPghsnFM
yGAlXmXWMeAKu5/3dlObApg3UPCQf6P6ZlN4U8+BFdgeLZYLOixp+1ugHSAWt80p
mHcwm7drn1SpraVBGYea5tPN8nK9FBEO/jMH0jEHbNJAATLaj1AU/rzlL7++Q+Cb
yy/exH+ZH/04XNTCxoNS/PnLPxREZyTAsTtvq/c9L7z5PTv4dSYP4+DL5nSV03jk
bQ==
=OBKW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '60327300-aaa4-4ce7-a333-4e0331796c6e',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//SPzeNa+XEK8TTlCiAxaW6fLNEiivph0jOcEc6i9PuDy5
8X6pOX+I21jFjdhy/SJHM9C/7V1X0OlDsboKQ+YanpKoUx4s1lUkgzcuvGvDQonZ
hS7kk4B4uyUVjXCHFANhzh0qAqDXa5pp5jNoyOQSd+aIuGqc/jedGpSwOFaSMWgi
yk3zKV1gRB7Yyq5m4u65Fbm2hdPiLM3ebDfDEUAOXMouIC7XIEnzXc9lqKU0yjdF
r9jc7ylI6RCRosyK819uzTSz4QrKS0ybZg0YzYD8rMDd++duplasl4Q5n61NSJhD
ldpPvafbgaYPkhPqklWc9s+PJDHRWzAOFKOy7L+nXkq9yXqx8zD0e0pvGPR1TrXN
finCHHxvPem7BOOE83zZuIO8NsWXNFA3ccJv079EYSLUNdgS5rbJC9UsKyHm+HcO
B1KUDuXdcX8GhsEhHhRkDf6HIuzCdwLuAIQYn+WhsrjyZZSbXTodtsaw4umegUkz
6OMWz1+B6VpqC+DByNlYcV7aWy3T6MYCR8Sij/8yv2MQ/iNN98qghIE2Hs6IXJme
D/cA7dENSPF2RKJImlvG9G36rGLHNSWAbcGk0UmpX1nZZiK4jQEYcf8nYf1+E0l3
wnydXS//VXaAug0ZEraSxIoFla6RdMIVqGzbGHMxPu9/IymE4hrK6s7aY3SfJqfS
QwFuUa1obfMXCIzrsLSp7QtEJXk8jfw49wj5LLMu7m3s0S9PanuwOwLZ15QWP6EO
XU/nqZT9DG0vvHDW5FYj59WeQXM=
=q/S8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '60c046e9-b773-4065-a3c6-dbd8d2249383',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+LoW4vmCRUF4m0s4CNEiMS3dmzvsY0wDZJXaYL3lFxciF
gugX4TSIAZr4iQzuumS/M5Ru5DkpKigAIyhcFw6NSx6FO8dxErQoRyA2CIPJvM6d
jqSZ/QWFc28OxDn/ElmmziemZRUKhx//USIIgX45gZmv3PRLj4TnYNv7NSMzl4KU
JnWyYAfQm06POmL6kiWHuH3y+6rB/ZHkEUMka1nLjfaH4FBEHmz3fysW0yvESh7E
hk7IfarJr853ie9RleLquapFmXu1ILPhFxyNYONS6D3T+QqR/xq0uE5fS15Q12gv
6WNcM0md4zbaGdMGK4JdTZOJ6pRCoiGCpNlZeYa18ehmrIK5s2770Irtc1WHnSfo
yGSyd9ooEHry5QFWr8O3xQso6d05TCcPHcSRlJrYciC0GM4lbAy02fI+enuLFKYT
mwMBLhFhNVAMixGmm30uyjh7He2MDQxRbr4usIVzDpBiv4fMTsfXYu/QpXz2R/Mj
JB02JDIPveOfqnsGWxz+QiUcGq58E6FcnyD5HmSqM2kB/rpK+DAVo4/pJ5SV6Qe/
uHPaR3sTyUr677Fat24phOuXqWbPozomdDSmIT2mt3xBcr+qfI66dUAWJxJiuYnD
r2Ny2lSS4NNSqqcdE1PBGU74Nxtr6N8B5oMg6g+XVpnoQUDCMiDfT9e4D8ZCrUfS
QAGc68QGUACq5Li2nQ4dmsT4J2qTF3qOky8zupsWkLLIYyBE11Ua+i6oOVLPKX79
siJ2lgQWwIE2gwHmSsc9r7w=
=FYay
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '65d42c2c-94f7-4bcb-a0f9-451d7aafe942',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAuhYRYxgmql79HRw0AHnu9NnLhKhKM/5ts/2pVG2HaWtv
Yh+sKXEkSocvNjJPQkjT47/FXdOCmepQbdoWK/9KZizk+RATxjJ+6XwCQc5T4T0M
rdUgbCm0uTM4gmwNkInpagCN/Jqp428+fmA/RZB1uMYlhQC1D0SoZP0Ddk4IA9gY
PvifyYQbpD5+xJgbav/6ObOP3M7wOUUBp9Jy2OIRwzHlvvr+SquKxahU2hwYkU8m
A6las9+dYa9lSXlqFZL68rH3BD7B6Davkcgnqz99uiYt9zcTFA/Mc/qIeMDi0e7X
FBDFIezy95rSsZ3OzTKMmCVmUatWCipCaPpfZVoFTo1Int9YLiblkPBz6LPMMKPo
Hqjtl+HLWR0fJCoHU3qFnbyzl5pCVTV3E3kKybYYQMIFawdUJht3Cxe6wkSKNxvf
qt6DwCuNTdRoO2BXysVKb4GoLtC+Es/uI5c518maWGyI8UMOUYyhj7qY1OZ2jC50
XOTUItWKKH7rM1jMUuReQTvBDPUy8nC01v7i7N0QvXHi19ZsJWRVVdmdrOw/ShyS
wI7SoVr6l83yxVSHG9XlGMELtg9/AYTFjgl3sxS2TNz+RtMTmukXSqLYE0hVUvo3
EUuNx97zMWF+j4rZ0qLkaH+bjVdGcmgPqWa52CHmw1QbsQPxf4DWkKQgC97uQDnS
QAETd9FMy3qPLHdUu6qs2PQtJ081nXRzLCCMbBdK+0rXKgFAXXhW8tAorLEY9cZf
WO43I8h2M6yEW+ae0kLX+Cw=
=eZ8d
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6a7c866d-ddd7-4ae2-aa09-b1de29a72777',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/R0UUfWE9QUdpNJ72ZcIHjZF0xbbfW4n1DO+IpGFLCltQ
0DpeskW2EaxYt0omAOkCdizZRfU+e7EBJwVyM+IaqCE6A20uyXqUiOie75qI1sNp
CfMBxzzcGH+UXg3gvU7qpvkhrH89B+bbubjPpDzwiLGezkmqSOOLcN4oLo757Ti4
kUsL2HVFvkHWoA8I/s4jPGYIFMrUTF914SUbdB9hsriHn+E1aPyayFdyB6F+h9wL
qVFVpUVk8BfZBzSt9dmnEkMw3TN3mc5EVmlZ/6C+lvV6lxYf2uB71wCO5CoF2fWq
ggKFnmO+pG8LZDaATJJT/4Hha1Mi9UarqJr9+QdrrNJEAUFtD7PBiwsoE0aXz+c5
LncigoR2xb9evTxdtHH0H1qzSzZyZy1rMBgWsFFm4m1WU8/RTnOQqHHXCrggthSF
kJ9aLeg=
=ix9k
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b143eca-dfc0-48d5-aa94-815997db7ba7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAq45R03DJC2sI9LLWztM1uQyVAvg35PYrSUNg6LsfZijs
xiT+xq/0BC8+hj5psa7fmij/ya2mYEbTqM3ieAvgDcVNGZVYS38SOx5sFNDdkG8Y
EEWzljBdYq3/K4PmlJJ1rF04QU3O3yNnqoLi/uUNZwDQfxkEezcQfe1E0TC/8mUo
prFWIXSbWXCeDKZzDDqd262dyZ6UjfCM8GgmShrtsZz9DZVBzx0QdpgyikgVczNz
DphpEnGWMr4dMLZ82kRVPTNbrHCH9Q4XwRSux/pqCA3v0oajW0NchQN1PpXyUsy0
Nrrsadv9TOicjzYsmWtwrJ2s5zl0auDVvg6uzhqVgrzmQgyuXZrPnvqEXC7VZIKM
3DrK80zbtjZUQI9y7TklDbAD8ajFHf63UORljJtHMyLsYhRnI+nwi1+RAvWAbh9e
J8HI4VUfNa7P5+/mYpA5acy8lkAMrcEoOnHYsmO6vEoBPaHUmm1oioSWfOXvwNLU
Mu2F4MD5/FSYWQl1sGumhOLP4hKT/+iQgA5WEGp2XlrzwvDVTLOJtdGrIV1Mf9wE
BszOWKwL1UhWJzLFcdeojMYG9sqwmjxYOUODjK9roR8vGV1rL6NILxh4AZwZT7A9
s7IfZuo3K76YBfHF1nnD/TD7UF8KRnWsiI58GwI5Jkf+alCGcFWeHsX8LtxMtM/S
QwGzcm4Sfkavq6i1y1hhU8I/AKlXEq6MtaOSonUJd2D57GrCjjgy5TviIyX6zyEP
s+w/dPjxTRpCQ16oYjMzm/CqVJs=
=mwka
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6caa19ae-7fa8-4203-a5a6-e39708420aa2',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9EkRIOak6N0r3725te/IlqcF0f1kahTEhRm4bgRGW1e/U
p6R2C1DE+r+Di6PXgS24NF/o20IUp/oPnJcJTC+Cv3Kpq7Z79e55tZp2hIDUen8G
5IZjnh3dPXvNERbfD2humkRH2e2DgE1Zml39axwXnbIa5H0BDvGGLOLY2azyUHvI
Ga1ywHYdUk72jK1Cuds6qbQWwK3JtedafRuyIpacIDykyP3hqPxdwxubHFI3lCrw
nf1WtoDK2XqufpyNYCtGkQjcJdSrpOV7uIG7+mCYr0zqBXS4AZBIAY7+5i1fxZ1e
b146/1NABVZYuUBqo1GW5UTifSEwcwvXS+X8az9vQUkBm76qO1fMOQZvy6RtwpQ2
5yQ0pP7DzGlWuPuI5IUti11OSoIMKG4v/G0w2kgrxr1bVxZI0jMkJ/QNXiV+A/kG
7EMEr/CnFAHe7SHtljkKkXQTP7dN/2eL6t6lFPBo1jMgllLsNFlexzR6drTDQpuB
5XZ5r+jBNJNIjDbrT3K75u2hWmkYngOBsML51EnB6Z6eMePD6qPfhT//Be/JCakW
ITIISFWbLNoVp0CwaFsYAspNYZ30jCTrZC5SaK06y7zCojWSjbU8Dyc43q95uqIH
kdvMZz4W5+qDUJPgDUZmuTeu0iHNQQuG1yvmwzXYJa1kwvoN6iSP2BRrTEbnFHjS
QQHEKfmWLFdM5PtxEw8JUWvskcy6n6GhPEL3H4ZZ8ZHgL+YdbjMeONl0lCVcMPPg
CrZl5lE8naYvezqjYv4C0kEt
=WN5L
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6f901475-58f0-423d-ab61-9e01ba08f82a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//aBwHZYUWsguG8vsfRIdKZDtABSLIfhWSLDHpv1ixtX34
WfBrrR5km5bhN4PpUr2ucoDU2W7r/d1O5zMMx7zOUMVswvOb/8Wn2LonsoCwQy5y
6cEqQK2naL+pU6j6jOR+e6halFQONu5jfZL0Xa2dfwnQNhTnaMI5KBjpc0yPeVCC
QIWykCmltWTlliRki5YIFvEDH/ySWQtQ+1m5/eQLHT1q6feNE0coJ3lzbYxqBwp2
gBU0ZBv1YOe3uxHJ0kEHTiH/eaY1AuAnToZBY3HF0Ke1O4MLDoSzulwRQFS+oD+D
EO9vHS8lSBtYM5rxqKbTbcflslqUWkgOk/51Co4VDjVQdvywhhRvWJC9zWuQ/2av
w00Ifq+nf0Y+nNDXEdQnmcXDYiIiU6M4vc75wSi6AH8qHCrdMAvzllww6AH+65iI
/8l0/X88EBrZwJhcXK4/eNq70aOjjxFdP7bD2tLVCWZ2Hv8tYbxzCsaU7wn9IY9J
8wxD4ydxWuC80Ucm5Vn84TtCxZtxYH2tbf75vN7q7V3tCm8i52d2oUmAvUJHGHMF
QuidFM3Ruj16NQex31Q6d3VdupufqbSaB8cs5WROhrIy60KY59VLE7U94tl9PoNu
Zt3pYkjM1FfxFLPEoPPs0Zfv7A9JGYePEwHk7DzqlvNqFWEbYsZdyRMqnnjBF0/S
QQF9rpb3rkMQtPXhau9JqkSN+uGawJrEuaMVEgxTW12t5DoKmkdEWXnNRlZyh9fJ
tp8X/631pqErd5X4cElJEpPL
=/IsH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '77f36c9f-d971-4c59-a5b9-37dd6fa7fa87',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAqHptu+sKG3XEPsLgwij6cb7gLfd+exG8DmQ0A3ixDgBQ
VZZ+o8a6MUyuc4LKCSZZP9FxjgAymMCOePexIipp+KRFh8vdb61aMXfbvdGO8ubk
Ka/ES3QUplYIaheajnCGCzOxUSYoqACVaxGuRSMlutImEaeJdaref1tuk8ibHU5m
XAw6FvAqcPB0w1WuKZnV2qWHUAmSh/iMNG2HAreTRZpsW1YgHqAiQWXxAmw7JPuu
3Xoa+a0bBPlRr9L3DFbcUCtJdNlsb7LDmXHqWEOm5RC2R6CK8Ohcx7o4jQVM3AYK
jAKFPBm8dYkQ79TXAau3RCL3F8d5M3JMuwjtpvd5bNJAAf9mlUu/ogE5K2SWP1eb
KV8HiXqRN3pvdwZ3plgI9YhHKBgtFuXIKmUI2P/JW8WNLJ0ohhhH5mt9Fu+h3SzW
YA==
=jOfg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '77fc8195-95a2-4efc-a4a7-824a4cc151db',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//SkMMzYJxlhms7I3FZVPyHkNpRRuZn5Gh3CQrSrhzBOob
hVuwp7jLxR1kV2H7CAHjDJu8O7fiyNWBwOzjBFAIHLofoiDGtb2yJ+5FSuzlN0pc
Kc9181jIq7LQfSR77eUPIx9tZ+tSiInT7KQ2ILXp3G8nfH0sPcPL40Dn69oy2MCN
meHhgK0ifRgAJ1hhSU4vyDYM3WGtCBQKqqjRDNSRfL8Ab34MKDR+oB87pPEm5m0V
YbGIidSmxpZjLekUBCob9BCyUfylth3zakowJWitDJg4lefl3IQRBwgEQT4ClBFa
+/9+605nYh1dt01qNtpOIGne8n2RZiBvIJtR4JgNc5X87GHknn7A5r2aOA82/rOv
tL1nGTxHRphyJ//SNcDrT4VihxLYEkIIR6NYSRAEv6F3ye4oDKbK2csURabl1os3
o/4EUc9tZ+fKDvn+5jo6fUQK/Aqd0bM10KYqajqVh9oUOxG/R+x2Y3ulYwL20Ugp
T21IcLRH94opKhaUb5UtxHeMJJwyDfzy+dm2JrYm7rpeMJ43PeS1w/pjjYOXGLAc
P0RvmMq9+VQDQtYjdAAMUB5YeGESZb4o/EdLclOVL607YeLI8zYhMcc2iCrnEM+z
iepUJsujQ5C+gSmbDgbnI/uXRTpVVKRpAua+8cqcuXi2ITcIjaFKP2kYUji9IJrS
QQHOlckJv+TQJxEIfhzrk6Oj3MIYvkWV8AfTMNCq2mueYt56mzLXfbHwxdj9/S4Q
uMHwhuAcBZL4xev5C5kW2GQV
=V8XS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '796d1bca-1864-4c1f-a386-1db8014ed8fc',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+O3NuCxFvJBsOQGwpCqUCBlHAqAZrNvp29mVgrECdCgd1
1VWFHl1I+T/3pvlVm0yB+T8KoogcAVXX+8/yQ/qAt0B05sHBGtWVIxNciyzbxbLz
pqkuzHFf1n8hEF11Q3z9ec7g2k64pWB2P22j2JkH8oHI5MDsbVV5jhcnBA+/RTla
y6Likgu3s+TxlW+Q005oL/yIfz/d6kSIVRIWBv+pdeu6wF+3SE3hEsdt9SNfCiAB
Ppn/zRl9aN9PIS/U6CKEBxnuIj4a/gb1J1Odb4sVaDIeK1jv5G3E4ijOwkIURfJX
IUhuVELoMHzAp9Vox8aKfnRJCdBVFMPU6QajfndCuLQpwuvRO7gZC2/hHCbFz8u3
d/lrVi+V0d/KDOTrsdubcSFFj2BB06Tmc7K/9N3Us/mFfJ81QhCMrY3b2J+vMRc5
o2X6bxYav4e5kwGzO7qQPuG4Y3G/iCWJdHVESEPBRq+exB4GI9E2OYO06rYIfgMj
8nvfErFsTNjzOWCHqacZJnrQ0q4jbr17SkPpY13gfOb4ur0Cp9rvf9Kpwlg+pIw6
VH/g5MDF+AJFGng6whFV87sFUv1ZC38d9QkS6SK3gNl+m/cQDJa7hNoFVVUck78q
JfeyhI748+yvkdMC4/JS2IAAr29EQMxJhILa/24BB6mleRocXfNfHVaH1yWXKcDS
QwGnCxREEvJE5jd7MZ9EnZhD+75gmJuQh9O6Icv8mv8xVFUqxCM3Q/1hCi9/2B/Q
R0fBSyOXndf8hihwoq4q8Ne0Orw=
=FMUM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7d77a896-b584-4a15-ade2-6e64202fecbb',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//ZGKf5FoyTcBqpng0Xx8iM7ANr5JD9qJ1Z1tXuyu612qR
O8E+osy3PXPs1ECKB6Z9pAIpcrXGIBnB4a1YdM5D5bgO3W8DwXzHSqHwdugutZE2
xj8K1+CeTgDHZpJM0PgSrP3wBtYoxeZnw/we6Jpz7mVpgi7e0dbsvlJY/mu4sCml
ppLgHqo7C744c+QqJL27L+umlcS+GfvYTKcwnaQ81AEl+UAIn34oIGc6ekU3sxBw
6zR8w6vbVpbvdvnnCs5ZmyvsiPxhvRY+uFiv3OFcYVmx3afrQC/2wifCtvdSZkMp
QcJjI62IrgjTy0y9q+dGJOikvptZkaTweYJNgXiscaKv0dhVxJJbM8P14mwGyUI8
zQ2Ag5s638S19AiRLdtIG0tR7p48z/MzZJ0XDGJoNSHKrS6Z6rzrwxwDqxgc8f0D
LBs9rCvUoMc120hVYwRWwhkUpSrkU19/CbMYQlg5J1IpqWhO2S6wxE4FeXTY2GbC
zCQlf7DCMNXZ1IMFpCuKWxEMFiU3hnc19kQ3a7t7xGk2NOHWyxIdZ2EihHh8zHp+
1RZoZESg/6mj6W0i1+T0JLr43cXIIcim+I4kFy4ieDQ42Mg5NuYhShhRstoY9jgX
8ntgyddvauHpp195ru7KK8gzi0kfFvMALXkygcERWJS4grCHiF2BHBrMciXfDpDS
QQHZm3eq5eg6oo+3wEkK1plNEA8NP7MnTEen9xry4Rt+wt+T3r5Q+fa1wV55uadO
WugokL7Iy9EVo1Rd/Zhu6Wfm
=HKp/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7dc6a627-422a-44ee-a8a0-2b9feb82d894',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+OY6U1QBSal9QKoO8lusqk8lPZwBOYT//S87ZZj4YJEqv
TG4R9NqL1UIYcRS3rEAlnqAamt1oOItUR+rzysQSn7eW7lofpWl3KWtzu7+iIqqs
Mue1Q1dKio+OknE32KITli/uELtFXDNJyp9QUjZ+2SnDYoW3B97fszNzegDfm0Ef
ZDuBS8Dctzhrxm3lJWWGeOiTXXgbSFLzyviuERozSjIBDE8O5Enyr7SjZJFNxCy8
xhMW2eaXcE3OMvI4XbzEHuN5+sSQzF7Iu7ifrRWUasaAxg1EvTa/O6guLOfM9y0L
kcsKNFhp/zVZHdkwO+qlZc3TzOva5e135zr4fzjGw45wdaZxb2/VjBpIEJf9vCeN
oXhv1cM+9K0AcB65GfUsiNFILlaCfbdld1KtHSxeWvpMsDsdPGY9W0A5ru3XnCR7
l7OFCdVMstNMWiH3DHMqKzmkr6mSboS5yOWa7ty4uMYBK0s0+ByyrIEvxBkG5j7K
1rv79ppOWrBYBvJEkQyDSSVuJ3VFAtvW6iieoPdu9xDTthn0nvf+mCqWcWewV2Ng
RIe6bVBXlUezfTPenTig13AVV00qaazu+bF+6bfqlc2RwKKqV8mv+t4YsKr9woWo
ZjFo/lwinmm4qLNVsr+mRq2cKDv0Li3UqsGUiOPpvCs/YZ2TO9lup3hRb5D+4WHS
QQHJm8gIQ7msnV6EW7TnyAwW6dU29mfjn/wORjBoqsTRMwa4MQ+kG/uujeB/67F2
plVyPBB4ocN+ubJtVNq5dmVe
=wgon
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '854d7fac-0e7a-49a6-a769-2c7542f244c9',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+LYS1JJ8jGgo3QRPpBrEdfzL2SuT9r3e0GfUo/Pcr+9Ob
2EODFa64n5av/RmVizihSiCmJjzkoE7u0dBkqvKtf3Kc6BClMLiVz1n2EPNZd+b9
uKxkYadmMwVvBN9RgECDaQD3MFypmW5bJnqAkOKdtS2EqeFdFFVt/8PcdOSE/fXl
+cVaytk/biP8wG1PJVQVybUV0ZyBR9grzgyhOx0OYIDem09poCyBKWn0uBum+bBT
RhjijaGvL15x5iE7n4Ob//+6MYHp8Zq82VGuGFIqN5dmvF2vxLL6kewWFGyNmjOH
wuLvm9yipDAeDmSTOz2BC071ywvj56gFDftw9M2A/Oj6kMdMlIB5IME8Lf1j0YVw
/eju/2EFZ72iTV+HmdaW2q9htpORHx0Q87Oeg5710BgcuWo166D4vstBpE1h6JOp
w93vXVnrGQKxpkwGsF/zw4JtbnkEQ6afSENZ+xBrYmP9V50OYFO+DI7wv6eov055
HJXFG1JWlxLy3EuNmVzF7+IE/KcgPkRENKfeR16T/uz0kfhFw//tDmXQasBk1des
B98IX5YgBmgRurOJA0Ndj/0I+jJtSjs6L6XnFrJ1g64tBbKe8KF7BEj9OR1tzJXP
GQIG+gDne3tXzaViFmXZKlXuBfiUKqLBqnETZbTE4Mahpbo79GYxCtjCbr6lTofS
QAFB9qVItU9/rUg8Ut6WLVXUtihWV5audIn6TXRwTEpt81D3VFldydkdoe1M63Cy
PbrGdwrhA3nerLsR5p7Oi1M=
=SC23
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8694c41f-934b-419a-abde-feb5e0cdca9e',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf8DL9Uxe7OxG2fkJVzqRYCuIZqsa8ts67pkD/SSrttrOzJ
w18W3uxj+NwUdidM/fbYT6QVTMViztHhOMP6c8s2KgasbSCjm3rE70CC4SC/O87c
Vekli+Laj33Uc3ZHj32pCmTwcKxfJxxkdgz3yFXMkfvwJJsSmx1NtdOrKR+wxSUY
C4tJuYCrsinSHqvZVyELFx708K/3MiCEsBj9Kona8+CD4d8edYA39tRoY4DACDbg
HL0R1A/Es5luiWrY9YvJeP3THmJPOicEtjtdsxtIXEeiuZxYIRHBYqhmGsGCusSD
iksfqWvelaKNgoNOgdV2X1+rw73MA8p5p3W+I4KIENI9AXkUiqX83CxqIqaU1xIj
1iJEKngeV3sLSzQ7CBnimacJaPE1wPdqz/m38PwNwvwKwWZpQDbd5hZWdghQWQ==
=mTjz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8a73e52e-5eb9-4b5a-acc0-c1bd2cca7bf2',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAnwsMyQfKjB3viQkid0I47cDJAFF7AOL8lWLbAyF9DJZl
hqaIihbYAmWoYv4+gbLsav1HFcM4hIUQ5y/By/P6IJkvenhxBDV41LQA3pWF5Vo9
wE78jnSjpgDiuMOiGKPlH/5sAFMS0HU+auoQd4SEUZwVqcs79pIqTrSPqdVR3QIW
fyWJvUCMjEOGZNq2VKC+PzCYoxIeBdgUYiUPiuAw4r0v9TO/3hIEYEVV3grWGr2+
GEb2WOlzg/RLRJxAUpjz/dVRTSzvUSrNZdQiH1kP76WBKTWBH12dRarck3Tj0bC1
XLA/c8VzS5AR8d1O9XsQpluzxe3loX/SQtYT51LPCNJDAaDNnb3BVA0fAHVHlKV9
ekpTXkXP3bgAel1n/NuHo952jrVwmup7kESibizu/jzBEB3flw8cYWkaXvGBMWRP
jNye7A==
=cL4n
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8df91394-8079-416d-a242-7f2360257dfb',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+ITOugrZBI2u/msMfwACGsJi/Jlc70hu/cGDrZV54WKUm
pJndZKyqttUqp/tbDlPjREBKuCnk/AoJDbOoSNZoRUM1kX3Dckq7mQPrizzpyUW3
TmTg8QwcRDGRLHamVw97IVa1anraSkVrNvO1yBkeW+qBYOvBnT0kKYjHbammNW6z
2nSy3zkamxBSaTISyFAHi4YQQakYQRGl+ZnlEVEv50A7EQYSQU6SYoKJt+NbS7Xj
fh/DoA0jE7pJmMU8T7SRNyvX/qgabOkuS8hT+0+8wDSTcfPKEeAcR2C4s0051Efw
0B/tj32dTRlRLWDR44jS5l/DhfO2uWbu6qKdWaLFetJBAb7FPIvOZZkgtKpnG5N5
3pZDItu13d2i80TZXuPL+2n8xa7XdqEQXmQR0hxVe4lRSixd0MvrKt2rq24I2y1Q
N6M=
=FMNv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8dfd0e17-7458-4c0a-ab91-c3dcb5f1f285',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf9GWas3ff8DI5cFbIyogaepXj+JIVNAOpWLhUCmqMhJnhx
djnyNM66L4ub5//wNUFCL4YUpF7vUv36SufKtwoiOE4vE5E1nRD92I5/esjzgDZj
az2TI4rPLNJO7djHG//nkCe6TIJCCONEzTmJDVwOFnrTK56Zz/27Yfd7X8xKGPEz
/R0oLmWpMQMsc4ROKFyY44yGPb8fCsTWK1m9jQ0PEhsvS9irGOKmui7xlP8ua26P
RNHQ9f32iot1U5kCu3Am3AlxW5DTLNjJGjMnEB7lm74HsSMVIscfMKohIs7wrU9v
MtTeHLACbSuxduct9EZy8feWxMv+Q5rlRODjwWXgZ9JBAZGA2cdMPV8M7EU9ANfr
KR4mXNkTRr/FV9zmEy8O9byQz9Mr9cBR7Nq6AevePs1pFa1Ekg9sqMuxuCD0YLlZ
nmk=
=GVXj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '91867887-926e-4ae6-a954-a83330e2f2d2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAwehcQdqKmRS5v+R5pnEzlrFDA7GychRRRjN2jAW9VEz0
DvE2s91BFHkWLhfciR5lreo7yEA+ZiBGsesZBJw0EeRiAig2cuQIOuEsWmMoYdZC
U2uB+0YUyvPwMPcxBLUvt0I7CDfYwCWLp24e0Lm0kHCOz+m680wMcbEbyWoe2TVM
NVXVdsu0V9sxRqUM7iTzdrRxLiBTa65b9zEks5xU+3PITmvPtY9GVQ94wzRkX+CI
fkmsfcUwIv4rRGDR7JWw4f2nMf/5EZt0rZwIo41scavfZQVWQFrXix14Z0+a3Kem
TSFXFxev9oV7QWsPJ6fXIwHEIiGU/80FBCZJpYYSGi//l1WR5w0mu1roZTiLn7tk
UJn+t3yTS7i5MClcRcNNsa6HWUAcVpDmT2NwJugUCcvh3NEmilXVpZCyGgYE33BR
5+MkUOZDkdIIrhyeJ6OcBBpeP/K9nAp6B7CwJJd4lzTu4bJrkFkp+5L9v/r4GBP4
BaGaDA0N1UccN9a9EY0X3wwCFJYCbUJIgB+6dbzwseiz7o6X7DXrUw4YOh6a1jOj
PN0QNSJGQLgVvO17hIgtHLHZy4OEtiaPTv7PjjOme9ceeaZU6LGWZCHVzAPZ0MaY
/vdl+LNRpv+ikhsOwgtACgkFZjH5DBq7PiXlWAR/IKVI4NPW8hoJO8m1AXDamWfS
QQEgfP9q6MDjJnWJ+3gQ27u8dKesIeEu2jvjKHw9qoSyfVCOqmPuOCnnPCW/qK5G
dG4dq40o+D36c8YqmCmqEHbI
=MgKR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '95e71810-c83a-4345-a1bc-45269179e633',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAkGfdKekIcVDX+b0wUJdbPuUrD/BhxfHDxYMH0z5Vrg+c
7JWt9YSvCOcMrlTHGfRJ0Z58VRoYxl9NpNTc4cABWdLdV+nmDhIk2dscuLwmlJjA
+e4zEjNnC3X6IFyHTy/HGOtxyHlT5nfVTQz7rETq+CP2GuYR1Aw+dKDAsMLwKhmq
kCfFA7sDqaW4qrG6KY0mAgIbQUlr8OslUeAloswMX33AkMl6bmzeLXBpCvsQwBkm
aoWxmAEQJHE/6hC2UTqUZr2Mj4BjBUuxoNKOxmBEOGZoR/N+C7nYL+wXh8Uc136K
EbbzNzk58Xoht35rqq3PL216DBGLxEsebCMTcswSQK59NDSPEvhaw3Y/r5HElKVa
utVsCSAO2F7MkOIZUPltr3OhYViyhX7VtKyb7utm5foW2jAACdfESMtdmdSX63/E
FZozeMemwNVN8hfRNj92kKuxPt4JeBA4CBYJQ3QXIA8t6T4fDjFpREnHFt93Qb1g
bu/zyQ+rwlHZbgtZ4qhXsXg1TqEnGdGg+yABPvQpZxuT1t+3eUDwzFPd29yUN9qV
NiYmnIMnXpscBwvKTDnjY0LhyleFgJDeNfY1FS6jGxzB23sMOpjEGMYnLfnMZqry
lV7YA23gQEPzBUxntNGHIiTRIjsJhOfeWIaKQdheJ7+iG15dOtrMCmHy+2VbP7jS
QQE2Hdh/zz3C/F5D6K4OwVN8kC2xG/bdGgRfbn90PaTEn8KW4nmxCdDuSaOvot1j
n/ZlxUOpyE5Cp6i60mTP1Wqz
=nfZr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a638dae3-e932-4391-a741-17e9905423ff',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAusHJGJ8MOoDK8qG70e+O8S09ktxRfYfe68DcTzQDu3No
/rmGXVc3qbVHXyF7Q7hYb3Ozdd6yiz9fCUoUm6wbZh2qS0+ePHAvvknS59+yOwph
l1Q25W4P+s+zVnpVhYGQCT1C2HqE1RxGQ/vLN1BKIeRy6nya4Aj3JILNsDSvupVy
12ysdmvpeJpqtGF34DcdNhfFEPFSluzuGO91pj2celsyVM/cYwi/HoxK9WUnbCjf
JW5kUs4cFvQ62HdognrTH54LqIOig/0VmDeQgaY5o4oSAvlBgTSnSjzwpMmvi6bG
+RBLRhdsW3nJABhVrOyRL/o0yKAHbfNeJlY9XH8+084XXMDiSoRCwClRwZ+wuUNq
49aynQ8+VnEt6sWH0zV9mwBZBYbFeqUpBtDUbsdSF0+r0CF8HbJeSEv3oMdve/Yi
6qxwRQO4RBBOsPbKfJZ4RR+ugJx+hiMJHapXOFANSV4sJ5bUSP7xkmaPUafcgP9g
4ThhXshazfVqtLjR2X11MyizoUcvAagWSgUlZWK7MPCzGL45pW/RPZb/EW9RkCZy
T6zrXsO4Nx4EpNL6fXFttCakT9uhGUhcTWNAt181MDi/x401k3y7VPAYaWs+cao8
X6DF4iRBEjNAvStAC22tcSMJQ6m+e3LF+5OTk53EqFOvZ/6g8Vf8KajqlTlnEQ3S
QQHGYd0nW+lFMAwnvqGW4GNaZxnoe6rFqfN0N3QOV0x4sMlhA2/ZNUwlDzdpc0fx
s2cJBLNbaUG9FSRyot+6BDsO
=6G6w
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a89a1f97-520a-4df0-ac74-671609804720',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//WKiVm/ctOfZuuBJzG3TxQwyAoz/vCZ0jdFnB7s2oC9t7
Yjo0Cha0gj0Bu4wHrJeNGeAg98pn8riQvLPajyDMEIgLUxTxEXburzSzV+6lVZ5s
3qp5aBX8GtZpL8KAjb6rB1WHt+GBhaz/HwtOuvI3P2XOnxZWlzjkoVWRDBHWJpra
9Qvu/9cXYYlrnnNC7x2opWWxQn2Jfp4mSRUGm4HuC7qz9pv4pB5t3sOfv5P10ma6
JbmXJDdAJ8qXet7Wp2+xJWpZ9sHKZjS8mFBd/w7fUVGdN/DUKeqgIT6Nie5M1X2z
nAAauSUvGPFz6tToEz8Fkm7VwLmzJ4n3ehBv2ZphAIluPw0S8fV/Xb5poHrU+R9y
OvnRq26in603NJUa3lihfTGcNX7U9SKp+aQqhyic5ebKkkv0ltJnFEBhZXkzjHy8
+toGH0aFS7io7531cf+7q7qLtvmn+JJo/hsPV4Q9/DcCvSmEGL0SzhmQ6+XPNW8I
tRKGz6qf60vWK59qhVnZ5TXkrImu2kD5tYdXqM/ESlmoIDmKKOoQwQ0UQCrrCcZ+
h2/FHR+f9DB8TdghjP84bTIntleKZRSS6PSXaf5wXf1IRPw6rOUfCfvPU8ikq0z9
HI5ydwdwOUlbq43KVFaJ802d06me5knrZxqLypGx1WLVWBui1Jc8JnfGMBierm/S
QAG8e86Um6pIZVDQz00EOlEmmCf3Go1ehMlfNS+ucat6XcbcbxZRi8fcW8sIKdO0
L0uXSO10vr1BAXUlynt7PfE=
=dA5y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b5d3094b-f0f9-4b07-a986-0d81e352c7ad',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAg19rYHebrzF7NRpYePgWKX30PyED+6lk4Pfe4i9tdFRp
KX3/xZVzVFwV3R2qt2XdTwiPIrmqV3dYSzhd8G1g5ZCi9pUHE9+L//w6LIZHRidC
IznvhhQCfIMGTMn+hLW1aCQADX1mNbYBfFUBejYi3CegLeRyezUsJEtue8UTV7yO
vj8rOhh9SPTv2hVB7QtHAQTXz3+devqLce6JRsi3o4l5OBuvfusN8FACUe5EzS4J
lX3DxwqoV5tdgJJzbJlOlG2jxFUSpMUjw6u0Z9z+jSECPw4wMjlSVrP6sK4KChk5
7cxTWY2f0nvrSwXmcC/RN78HmesB5trF14t7HrmEatrAQcB1heMsCPpzi580z99Q
sBL8gsYss1P04Uajtcfl4eoTuNkHHIHtiv0hr95zsEUFMPEQg18epNz2+VJbC4Vt
ml/mbR8aFD5JEl+xYKSSRDrla60K4fAnGQO/PQkQJBjT4zgm8aulDot8+5C3F5LJ
TQVq4EdOEunWnZh8yEl45V+dJnPscBXkFdDwX6VsTEoZnNJQckufXquG9bBH3vZ6
hhMEGkeFFw7UMOwvZo+ylIiG76SXSBk3X1dZhda+uhCJC6zhSOkfCANvBaCywMCS
HpCy6qEV3tNOsKUQplvT/5zNGpvUwUUzy87UjzUeg8giMaMb+OlIQSQXre4XKvvS
QwHYIriQLoE3uPWtfI8/5Vx0lII9oIu2u2FBRsHxHvXKpnJGCDCk8g73QK+QkkHB
w1UqbOkVsRql2sq7zW9CYNwq7oo=
=Ogjr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b99f8376-cb53-402d-a2c5-b180a8467f32',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAkUJX53GuP4BeL2lEOCGBwVtRdBsVqBBBOX0Pm2+ST7P3
DE4cdJHQzMlCnNDhoTbLN+UW+nOYPhxlN3bUseimr2RfwgEuR14zRWIKx8LIter3
etwU+XhEPVeyiQ6Jorgeu/NWl+d0pFWlTFDGHdI6CuGJJw7J+jfvs6intHblaT1m
rjl93qqxqYDizuRtWsOGU5UwBLbHgeJHQKJhh8noODzDanwwdG4ttkBfewhcWkhB
LqkF16aDUiFWbRWqorB6gss4jxD5y1FpXBzd1e3T9h26Fr6xRSVOxoEJQxuhSk8p
HFg+OCTk4Sc7o/PGMYOLf4GRiiA6oOmkkS5iPdVfYGrn5S2ZzAg0/JQ3kewebQu8
8nf7oPHg3GzjCBf4GUZVPD8ZuEFnCVsGM9nK2AqiyAR/mQhwialsjCqsdAljuNOj
fzuAkdb30FhEsgo/18QviVDTB7GuLzxiwvkRAjl5LS2K8ySxURtZPuY8NXXvuC6f
maqCdtGEJ28SzWeqOQUE7exR7ooRzhezS5XM94brANEkCTCfgcFC0z75qZF9bKuA
EdRE4vOoYHv9sSIIOYgSoeFgtX/HowSuwH1gYwU8FejZxLVMCA5hDuhgFldLRjd1
0Q2hBe60W/2s29O/RAgdtk+y/hVjZbJiSBkU6qtZhIqOphG8S1F+mB3tx/uVFw7S
QAGSuma167cunJiFI+vq5hhzErXITjreKYh8ICsuhg0heuxLeVs5dMswxYjY/4IE
ezGL4b9dZna/yBOQDNpkzbo=
=kDvk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bbd7ca44-fb5f-43b2-a65a-98ed6027edc9',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAjY8O1/YiD/31bXgFBmvPlfTuALr8PkAAlEXmtWAiDQ3C
ED85ZiAdaTNjA8XH4YiLGr/rqsdjKE1hoxvAZmC/eF/xJj5aFkW6ECWdSdOQcrhZ
kB9Nvx1hOer67onCsXuQ6Fkx9qFGdj/whHDltVFZLdjsbc/YC12miVefCnL++Lhi
G2sF4S6vBspsE20YRYh4Qe0IouzfgGarvR6pKE8VMVxT2VzG6U2QsG0UeQ5s9jYC
qKw0zMwIPFJZREowhvSRKHpinkHoX7yVB62F3VDAOx+cjWPJkmD4eo0e+57t13l4
PNIw3DwWeD8vjzSsJ4k9o4fiNUEyFi9dWpmpsC1nfYTcUDdTW41JUJBa1PbVc1MX
APte0C23AUKrQpKS7jgxzTmNWztQkz2/E4FUJOeZb2kujQ/XjYSVwjR7sBCri34U
aOXz3jzVCd8YcIKK2o5MuSX1WtuZmF8b5Lu4gcqGHSc4nqRkKVBTuALttQ6q+J/l
bRPAUlMJmVDP6UFuxlihgYqi6+tEaCnpeNhu8TW2M3vBNAQoJqF98ghBwDXb9Tci
k6Po5gTJi4kz8T1UOJfRyGbQ43aRIe8dNPfGAteVQSbw8AX2FMAeRxDdPLwUDIUp
JXFyu0M1uMH9YDrTvjMCoL+GkB2V/rrSuPmY1/VcRjjGLSpBpHMwLtfs0iwVauHS
QQFxTOi9DTOOLp6lUNtnutvX0i2MeI6jS60A/zeEu3+TIwPTAPsUmO074MqYT4Wb
LYfx95I08DNCK5lyHSWhMXzY
=igy7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bbed088c-73eb-4d59-adce-003a4d243017',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//atm81GkfNCTa01KQr014VDLzFPi3Q5nYPgvpDtKmva/c
ys6V3eQ67abawLqOW6ThmkFuYoGJhrhpu0D+Qx615NqgF0mCUxG+zLriu0fwJR8x
4FOlx/ohAGFCTKTemdobmbGrcM7zBK88v1Pt2k3JB6Rvo/ALbobA9OrJd2MV2jVf
ur+79ugwi+fzfYolZpBqytzatHCqqNsJOUbGA1QVSOb+eZdTnTr/vGQ591AfOxjN
aVvrMf7YEvUB697SwLfmawz4TE0TxG1fxM+UMCozp2FKZKhE2FJjycPi2SdDaijr
fwnDch+PD5g+NNKmxNp+63B6fHYsbcqZfgTgYn4nDWh6+PgCqfSdIaA9Vq2yyl5f
tBXg0Tj/GXxWc3KW54MhxFHhuBruIyNwAvRDrBb2bNrN7lPgGiGVLYsmPGSmbpcy
/x7YkkuyMM2DWn9aOSMVAnJ1551a8kvwVFem6wwZ2ioDPVgBNWprGNtcboRoaY74
H+8sIqpVyMsNWW0M+kxeI3Gn744NlYLildRNx8kyYa5LdHuccd14Jwid6nORu5Nr
7g8+RuCq+ZkYJ/iSm8jld+NWNW52hsJJOvVnD/uyzwR2vBuAflNlhY5Ju79nPhWI
KEzSRfA+bTDRk8BqCGZo7qUGFpDaWK3oi12j2rNWJArF059qWuyW5P4rGxOyYl7S
QQEMas4ZN8frq8H7YOgAotjKMo33+Cjz9nswW5tthqb+q1krM3JnYntmgFdrTKS/
6DLCz/Fq8CiSKcStR/fTSxEk
=4ObB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bfad5fb4-ca5b-4060-af34-efe95b6f8cd2',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAoyPS2j0Dfe5K+71Afpe7rKoUSv4xAOA+ACYa/5h2SV5N
CwUd08I/Dbu0jZ2bhWSFimrhDGwuYL7GaGlH6P1sNJh1S4QieGbiZq8bP5OiI5Tv
0zKQp0X9+g5D/1U/jp9knu7kd+M7dXMr7Ilsl/5E/n7V07zhUG72jwFea5qz1Lp7
Mj2upd5P7sXk81k2j2UOOVUjITf+Oxu5WVWmxdkPlQxIA4Zh5UMzehaNBQq9wFJG
WnpVbJ7K10gQZOPRMTnyrEryWTiHRm3s/6Iilymm8HylIgTI5wSHZvU9ROoTaI9n
d6NmZ4//IJfy/T6SMePvzzRxJ5QQI1f4VPeyG0SA4BPM9j2NLQqGoKEWcAYt5YqF
CSSV9Hy6GYJo4/feMeoXp2Id9+Upf95bbzeij7k80kEIfrnxuf34Bt/SIuhP2/e+
szDLvdeADqAxflXbKr0CdH+BcKf5bsyi2xZj0iSsHeo0sCJppW7jN5U2qF+3FfuY
g1CUJsnmORJUq90FzHZl7R0kzsBHIWoiqvcrGMQPwq5mrIDjnWOExgcnIOr+lgJH
YqPsoQvDRVpTmybUrv3NMj5EZ5+/7a6lGhx+D+wwdEArP6Nn4O9HvY45VVxFnEN+
pcGvnzovw9Ne1HBaSYNf5vrh5f0YHI/loKHJdMBcLSj1+R+yHV1P5r6WJMHDpjnS
PQF405Z5CnDzi8bw/lIzB2RfS6eGZQ/S3OKiH9FUHqgpvM+csnAZZYRJj2z+G4rD
e5trfVauwx6yqeI+aK8=
=KYmm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c4357580-351a-4b85-a313-46d8ebd5f579',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAiWAxm3DhUNDerRQauEOtdEGivBOuU+xPvABmoIcEW0o2
baSq0NiFvQsaW08MjnORc1WpmEZkA6bUJYDpEX0zsjRGCH26mF0iFkfX6Xy7TXRV
GvrUJa3njC6SDnnnOhhqMJbmrMiGcJFoHwdLiJ7GhfMkBDPc5PXLiZfHDzeLgCRA
Ld8Ru1KBTzaG/MWPLpJBz5MRqiMDkD1H74UyMyWtvNccSADsiTqqnjhkSZy8CZZc
UHE+Nqmgr2cGcpD6q1rmTHfXtjjue5Wg/Uh0WZgYfThOzsDu7/DSsF1WSf6vNT4+
rJMe6y6oF94kqNmtT5oHnylaa5p724ja+P6Twrvz7O7qqpZRrPuSofJbne0jh723
CzU71bSQFWTKwpJ7ZhCTGFNXwRJC5EH5XKqorNf+h9EatrbmGjJ8dsLPMtH307a5
H3TTlMv16yQRWSFQxUYbPu2xuaP60xe5W0mbv1ca4gFutScUTC9xFDxXFbkRQksc
RFmvS/DCbqsBHATFKN3cItndiDR1nSqYKZkbyGjbmrjjhDcykj3u2VDzXLlDoIip
u1I/lCLYCoDOXfD+Noc9qKLSVZlM67vhXpmLsqANZBkXk4hkR+/uvrEUYDA1jDUs
XlaKM6aU9e6qPbx6x1wf3M9wgcIibIebffUKM19KRQvTRp3EtvQTEW/0B8SUAcrS
QAGczgq74NaCwezOwbc60z38AWPosVLSEEOMNzBxTQ8Tf/T2sWYPsUtvIgrd3v9l
v4a8CufakjrIyOVYn9Jg9/4=
=7bGR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cd7970e4-4aaa-4804-af0a-f0a3fe353806',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+JOhPKK2tq+hnHyx5ul3L9Dy7w4vy25llco8Cg3pNI1dG
pqhinHR8YZh/Z3wvZocH3RIL6ylVYud4OAXO4xa4524WzWXxXmvKbVgRgwSi4TLp
gO3GZ9f1n4A0lJVfguyoJ+xsLBoDP0wX4ZO6ccZ/zrdk2Mvgb6fxprRXChO18/3I
Dwg01bVhhgSOLVpg+ONSHSOEPvoYLSFeRHGOy3V2/RbH1iVL+UVDOB4yhZ3xf9JM
rB9IXC5zYm+lhYNZABGv5Lf84yNWltS+k+aBKemaWPAYE9lURpgZH6x/O1zUmPxQ
TdBFhD8jT3tOdgzELQCHa80PK3YMPIa+Y5xz7HX0L9JDAdk9TdXUfiHqormPJms0
hlnSm5dSnwuusXicbPwxBpt1VH14c27Q3SSlmxfIqO2L0RxPXEWnFHsHmEonD6bz
rpHYLg==
=fJQW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cdf18c09-78e0-4304-abca-3d672a768119',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9F/Zno6RYXtAhtDMiL4hH7syI60RcO3pgD17t/trEzl86
s4cCC2LAahBWIzqJcYpXg4kJej/95zQjfVRInRE5ih+M3k9BFY77iTq28JarTygX
ndbB+YxP2SWq9O/0IDKhvaSU3kmYnpjQBpSBjaZrURRnVEPza03HTDqO5TrQTbuG
g9ChCHxP8n0EbRJwGcCaTcUK5trTkQLVYVXSlaXsdQ1U38Em1rUHV2OVUMuhvXb9
d4TiZcF+twbztf2N/2dB0YO+/2ViEnmV4EW5sipVGaz7bBJ/eRovNvFsrUhkCXMz
d1gTfwJYtA0Cgy4H5rfimP56UlTKSdh48jDimymAx8SWKRBmhSJHBDeaf71qjJHT
75kdbKmlWkXYmVwyBSkcy6JTccwDtq5WvU+ri7pcZZDOYUhVxxoaBe9v5s4T+c3f
Pibk7sDMqZj7N/MCEQ6BqJsO+cyomQt9UdxGavATm/uwRarKVp/uv9cosh2FNQll
CCRpZh6SNsBufZaSqGL+8Sx2O/zImYPXFQBSpCoJOtN56H4sihSXU2aHD5JHqCkN
bCTg7vcu0Q8lSjXEdoiSHkvfABXLr6bkaZ/JKPzwMlflAUAzSKRu54ybK7M16Sm8
pfNoKAZMXYPisKn4WyaT6meD6/IZoCWWtZ/wXXwZSGW+F8HMNaGX9esuioLAa0jS
QQGzf+bMoM0B9AWAfLICjwdMxMfjJZbXRiYSKTXaLpfr7UNSkHU4Lyf2rKkBSkM1
frVcoSGHEuFlN73uLdvxwVhL
=n7kz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd222b8dc-778b-4d25-a753-7b83fc3a7e03',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+K2PKKNpKdZqz2gYaSSZWD2e3LTcrm4mI+8WFbV4HC+Sk
ZvvzrA17f56GA2sZbOwV2DrGvzH4CTvp/Hqb/rkynsaroYG2v6WzouAFnE8rorV/
eZkji39gG7uV4aaR/alj0fDq3aq3nWxWJaMJQYMCw2naTXzoCO70J8bJ3hXudkJt
VnAreEKRDmSEea8N9u4xYoXpOMfbCAMm4/hHA5ZZKcRDjdHDAYf47WgO0L5Mov7b
imwsgPfSpU0RZ8Q0fiw15Co3t8In3/ZoTP74xqsciORaAP7Ds1rWAwmZTmOf3aQ7
tWEjI2Qo0CvWBxaVsB1UkSQgCrPhskhHxrN/Adx9EVX4FfA3DmV4ywMp0aUrRVQg
izeCRIGqnK5PtZqIiClHRjtpUmiRoaE34uX90ryTOdIODtSxEFzssivBQe5XgqUn
vlC8xukVniccE1QOeHqqY6FEJaGSZvyJ92eXELrzZ3x6E5mbWX67OwUSnLfamF9i
A2Gy1bI/yc6B3mDn6y2pOXVHMWNTLic2ZfwRAn8/4FPGdXqGKDG5Ok0f3CGrE1AS
k9/IuUHp2yAjo8vi9lchHqC5AWKiHrkbHluexfRFAEZwZb5FN2i5Yi4fvbm7TtTE
hUD2UWWZlzl2TZBADVR2X4CBSqpBmqZ+g2Arjxizd0PyTAZq2T8ie+jh1R/lA6/S
QgHtEWY0DhSZ15bedGPzb5KVutz2mT5g8mfPfO3Odqt8MJVA/vn52Tg9pL9R/Jtr
B4N0o2alIsuhD5jO1uwrLgluRQ==
=Sa5c
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd3f41b36-9d17-4094-a7f9-3e1f67feb4a5',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//eKeRcft9s+Ig209S8x329p7GUrNFLfBkSvcIe4lXP/Ef
NDZanYlCG4A343+XvOmY/c+7+IbobQaTqk36n1O1FJWu0vNqUGquOD8stqXpjXbr
O8DFcwRvviWglo2fEDYtgAx7IccSYD3Tn3Mr7v4NM7F1kPJ2VNILFHmG4aZADZob
/W1AFBOqqCTXhM6vVkvvPL6SsuP7Vgx3/4kNpY6/LMiU5j7ETvXw6GyDqlM+LGCN
M4ObkjKI6shRtA8EIPUGhfLChRBMl3nY8VAvyxD2ZveVuYcI1+a4McRUU4xc/mao
YfFO2zJ45RPehFUT284sL30zML2cjOboAMibk7hflwBxutbW4W6IrCs4vRKt8m/2
nMzYKDI13jTV1KIVEyrTVYiriglttPIrkFVJWwFgSKZsWuZ36wDRjZS4LiNAEAFa
WAbrHVXWoPaw519wOriBhjMHhxRzgNT1Nv7DQEjheR9i0BlqeSrpGnKzSEItrunp
qJTCY0nWX8m0oUVJuN4qefsVXKtv9/fOeo9+RfTs98btGKu1qT9HbaDjfDBKKHXS
JBz6Np4hzMUUWr7/Lf6nVVQA4aup8Si4/GaFGwb9bjIG351+LsRngD1blx1jBDL1
Vrws7BOYRsfbngWoFI4Y3a8EqmgAHB/lBapU8QpEdPp2yBN58PkxaoZMtIE4oc3S
QAGANvN+jLZ2Tw9Jdw7FQwh46JzUrzmxkR68JB9SoWVhKoj0kspn9GfEfGcH2/yv
/xkzS8X3BOA4iLTMNQp/KFs=
=azJH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e4fc2d57-f501-4240-ae8b-46cb212df40d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/7BNqQEE5SZ2QkarlhIL1MXKDSX6bmbMEDtZpR1WgFhSqk
gDXvJ2WRvFkjxg9+IiU8sPsAmlRzU8W5s1bpnyjO44Bb6WLjMg2Ybk63dqFTU0Bt
OboiWVPFy8cBYnP8oWAb4wVrSJwcp3Wuxfcv65TgoC1eHeGx5bV2Tt18FCDlm4dI
V/3AGpg2u9exUzUvgzFgyTQ8wWX1MTyXZ946MzFuABuzRuadAUhJKxmX0B3SjNJv
eAJf/bYIjmyd0DmGwstiG9VopLXHhCr7QyyxwROTdKAMfziVLo9P5QiDuu0xhhpq
Tnu4GYmfF0nn4u/xL2FlTbhnYJRIzNjUnIxPhIOag4kHnLQxJ4wqjuf1rz5/JkAX
fk2CizNmgRlH6GO+RWNbQ3LI7rsHe4IbwBu7nrQh2DTPuEBrmEFoq8SVrqezF0Wi
4YD3V/2DTeaq/Y6Fe1AwJ7kb588i/LL7BfmkoDeC/+DL6vHWmGlYXfHHChpbG9CX
WnDixlU0x2ZJzH3FenyLi+qjcID2a7j5vCm7mlHvwoKCqJ0sK4V/DCnjbzP76Ie+
FnZ+8AyH+L2Duq74xOFj9HcFRNrZuVjXr1YKmu3Ugj2H/7tQEVm3749causNHGJV
llGVtDozw8ryRORfDscYd1p2TY2bkLRslectkAVyXIO6JWh1jDBfeE4SZxwBg/fS
QQGPTB7X16K/I6TwuD/EVLA9SL2eGh7HO5VlvkeEIDo67oW+1lmUviFiRfKsGBbg
+oZkd0oaqNXBT/bwMYE/S5Qg
=x4Xs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e5fc136f-7684-4c7e-a48b-d5d9beccf79c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//c5NK61GVKpOtmp76EQPqLgI1uvT4eUHbRkuBQhdILGzi
fQRPdj2N3gJZQaJZ+b4SqksYc0OSXuH0a3GtsO3pCkZ67rqb+bXZIvvMbjhI0soe
mGoYkNApsxX1l6y4IUFSdgSNcTvV3ePgM3YBFhCTlCua1UKf97sDK0tWgByiQ0/x
tyhdD++cBBJFHQCq3W7G6gDHRwCVWTWVxdG4xc0uZTz/aptoRUQoE9WU9Y3UzZT7
m/NsemnHN9aBYGtP+ql1YgevZnk+8nGGqwVesQleWMoJuytt7VWqMHu2KYltkqKN
1UhP59iHYNZqrHiIIqOe/YdI/Iosl4YT8kBpak6dAIAqz93d6vCTh3CcQEdKks2s
/5jPTLOqsZD0rj5FlobCQnFYaOf8p97hhb5ZDw7mZ8yZKNyD17gWzmF/Ie4pqGWJ
hF11eNXiYGtQPSviZoCoHkQIgTkt38odHTn1ag8moSVzIwBoVNtes5XQeSk4vUPr
UPgL7ZgZusLMB2wLS161sYKUyYZWymE70vltsaOYgWj5kXHNP6WlpV/4q+urbeRG
/MgEBbE+vKoCQu0lkwGsgGGWzfFLO2lUeN1HQL5+/QwoNG+2nb/jT4Qh7Bt63SNy
obdYy17fmvum9NJi6lV2WKIZNi8TYvWmeTzyYSRQep9X36+4uESU6i3vPdwPpsPS
QwEdUxedt4dh3LGB4c6tT69H50TyDmIGEDkQUUnHJcw/l3LBUqb7iQuKNNGEcdN9
RweKaJwcScsAOm1QB/n7qWJ24ak=
=KhBY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ea4a59f5-7a25-4ff9-a68c-59e14593fad2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+OloxbRHP7nkk+K3/z76cUK0cCTUCUo0FU7MopLqfM6pM
bXoBIgruImW09qpdE9yCSDMKlEmilI3FfZP4DCl4UqBauJZbI9Vg7gI1kRFgtj9m
JUIQfq7p+r160EQgB2qu4LgGoIZQNobw456XyXN1unLoEZOM0bzpHzEmwajuPNX9
qphhGI10OblM2Bm2+GTBInvwEhvIgrxJlc8Hnv/ajNkefvdWAEujdPI6VMNwFZcE
SP6Hm/+V5FjU1iNsdFteOeCL0evS3MHgzYharorNOqc2/dY9g8d7ttIjIiYFqNVJ
1zUe+JzGFwUC7lnUiaBOUkb15djsq82ixK4e8fUyMAMpFwk7fNOWQ1w4EMXCmQZU
R1EP955zQLMyOEH8ZsWNKACQi/5qEcpeIysjX6vT0W0wbi3x8dggaIC7Yhr2WHxg
BQTasADsft9dxA/2PbwG9e4agIKxSckbBAlEZNDoXK7bdXnI0rKS8Z9+sgeV0Sbn
w9ku0FmPgG0la93aIj50Ik2hmUS/P12u986fJoiBiWovW4bZzwbxY4B6tsqftk6f
QpkICcsRRiWb7nreu/3LMONTJ/6Vd7/0Mz/YeuTFyOUYjxGNeaRS8zPxXH4tKWO8
XblGrJcwTXBin6gQPU829y1K8JJdGIia33HGhD5CtyTCYuE0fg36lmHEZ+GWQHnS
QwGGTa7GSrpX8oeUmUgHY7cC7ABXaNO2uKu3aG2OyRMXBHTPwhtjH4nDhyjfiwwQ
xI1CFa3wkCLHASjityuwuzHZm6w=
=7KKO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ea6ceee7-57f8-432c-a9d9-9667cf1b2d5d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9FKLZHY0OLodTn2TZ4urtuexfSyK18RpWSWRan8t2UkBP
mglyJienmZkgLRvkibj7BesuXzrm7OLbLDCsMeRG4esEFfXlZoVYxNTE+6u9rv1v
AE0MNG8CWz8+n85GWwzKb1EXvu3ARLy6sm03kmPBqFDdW0qtUj7lRVVRUxLeaa8a
ZZLExleyZqrJDfe2hJGqGfmtsSRr/LKcSbqP8cOPV7wvC/WYt7gY7lRcovKoWd4k
dPLl2PrnmDLdmUNNINDijOGid9Nv+OQzF/RgX1DlZ06DCJCDOWA38o9a7iDbN0HJ
dR2qMJCqzLhOuV2E0YCQDEIYwCPVtJlQCa8nyCNL7KUyXFq7rxt6E6ki361IqaRV
WtNCugzMFlkrKrPEionE+ffxTvQwIOcVLIkjgwmg+2CK0WAecJ95fWNLTiVTHL1Z
AVVEnzu69hFqnJgFP/y6SjZilsbeUMqx+MHz9JMcfsgLxm77bBCI8xNJUUjjvQz6
5rTVri47PKzguK1L6k1tFr1E133jyxAFQ5+uelCS1Y0u8yJoEZx+7TDhy1ZInFQ0
Qnz+sWWR3TqlLrpWq9RyEeaXbSo7pQ78oQdRVJMTAvxfUphTTY35nwrso5jhYamI
96Ry9rWkipRtFAmja5S7Z3hndsTKbqkw7WG392LJpz0vOWTBZTF6ReXsPzBqVcHS
QgF1/5mrmkx7TiA1lLyAB1PH3msMauvVov0IaldO3zUCmVnF5Kp22BFhtVuZLdvU
rgkDZWWF0tcMyOdin2N44R89SQ==
=Zti4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eb98500f-b092-4a2a-a773-8ca44a6a07ce',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAwGnSnvT17yYTFVUouy3digyQ6/rvotO6x+U/lMKNeynv
Wum+wWmn5qayLdyR1O4fGRnwVJSnBceHPB5+/P6eWXJPrXjLgG2Lgk5NFL0WMLtI
XB5rYposYTuDx1hsi9reEowCeHZPXlaZfYvqFjS6k7DeGH5ehf5070v2ztDFXMLL
By2tej88gAZb7PTxj/txTsOlv8VpIt1nthgaI0uKeXWveVssmSW3C9PVg/oPdvXm
FUbaoqtwuNgH0q+v7gpCBCbWICK40CUkHH3FR7cUuwlQ9LXaUsbt/ZQq/pXer7EJ
MCOv9fE/3eAgj/VC74JU2rhymWbNvYrVjoEHQagyd2DeUVuKDMvuSSUHPi7FQ2wW
TVp8KCdjWLq+DVuKB5PtysWDjw9H7mQsAqhs1PtRY73YEtX/P4mNzk9Wo1zPw/12
oxbij40dsrsycTuBHKUvjY0GZbS6r9mmWDFrMQVK0Xlrd3/DJQpii4YGeCULhJxo
K2EgLGtlIgBzN3wVtihiLwobNy5Xcmh5gZri+ppaZemsaBTXSKtU6U1n3Bz9MHNy
eatvKFFECxtft54MCqwcqvMqaJp33cN8FzU2CC35OddwvHczMH0JHMpvvAxdJLxY
xz6Wk2PhTCfC4oRRdbOR3DBEZ9Q0JMBCLJ2ozYr2I+VLO9huieDndDpIfZXtXXbS
QQG9oI7qPO62wR0O2nhhm7eR58cAbkS4Sij9WmYMER6S62vaBU70d34/ae3vxAVF
OvgG3bL+aLON3gDvxiALUOub
=NSL4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'efc0e94e-d68d-454f-abbd-b1ea2642da59',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//cH1TkC/bjpQUKXl/5KvWAgw5l/uRUsYO29v/zii4wZJP
gQuCqVyKDVM0YmG3DiutM8ZRv3zN/ZEUKjhmwO/8CRBAEoyUVKvuTq09tiCIotGM
Fe0AaQWwnUEZAITDt/l2SWvpBV+wWXJjl6e6FWhmLCcCY6EFRhyFl70No0Sf7P94
pcrlieY0+JbMjppVe8Bg+POfw1f3n2Xb0TABPDUxHnjn8SmYmfxc7mrOuZ0ea1Za
0swvOWgJlhusblDhhLc3Zl0nXCoI3FIPaohsdBKSBhO9gbeUGEYuO+bk8UVumdyd
W8UI61Qqv31TseHQbocgn8KhEmmq3y4iDi4zw+ykYIeKEYdUcNRttdGm583agd/7
eDZAYyNxVtQf/pA7LPArkSvBwU85hhZqbWTOhinHl1rD+UWFagWqkW4FS7M6c8t0
HP5lcYKnC82mBONGEAUACBcPdm0cZHG33sATsW3Q4hv24ZxuhdAINAtmkpqO9uaP
Lq16NUlDNZJ5AVD6xiHeMwIafz3LBoszR6A1mIRUjLVUIMhkNhmwnz8vtDAaLyX0
VQG+DXDZsTwCx1RJJAwOTsifL/5lzDDcZglcfWv3liqAMrTd6cMb5O58yDGRAP+N
hdGLjQgQJIDeMwA/+47bHU3+niEeamfcsO9wHC7Vs4lXZBPuIUylLZhEFhursrjS
QwHf6o8zyv0dB1Ro59Ylhnow6pD0HixZXAnytHMfztM/DOiFbXNwygdVzy684O2M
/gvfgDbxG+tinB98n+N+19gQfFQ=
=Mh8X
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f15eb8e9-0078-4c3b-ac91-32876437317d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+Ns9WqJyc7rPaB8YmfODoiL5SwfdrcUVX4DfNh7XxXTi6
9JUp6nX0pSiDTZ22eGt6APx2OK+zUXl5oBsW78l2XFrcjdS56Gi09TNntWje44oD
e795fiaIC/u4KZGfSkQAGJK5w7vJegq3D7ZcYBYTPwVuXTaUeUY60xeksJhye9f0
ZdW/WioWoTHBGu9pU88NnO9Ey8637HVbdGoY0FElcOQAKTZdhRmJ1gJxCaHGZkka
CiHvafpvd+zseikyCj/NcaFMFzPJvbqKixIK//QrDtH+I9xLTFdvQ5lsSTb48ulI
wnSVwiQ4eJakXSQyH6dHb4EyqNdnQcIvZwmYUczMUbO+CbM4xFu9mERhoNXWs4vq
wWg7dVdcsRjEGzlEGKzuTdcYp7+hGVrXcOlk9wa9dQEcPShM7fPFH7lFrdQw2j1W
yHZNWDw+BU/HnDpGMa2VJ8ku9yEkQZZ/IGgKy8LObHbIwguQwj7EDDWaMOvsjPJ7
LnFFbrPP/dBiQoWELt9ISr+YvY0Pce64XAPf+tM2z1EgfQ2naYGjy9gvVFtC/Glh
EsbDaM2r6KJSEYajLAcBN2ZDII/DOjtEvnPe9n8SlyRR8mFHc4352nwTUXkjoDMt
xhI8fwiOcWDWv1Jf3YghgKmKZwygEu+vuNXJqJs7OEzu/HbDw41NDt53adC/dpPS
QQEO/+PClBCg8D9uRcVUkz60MOMhIuAI92bPRXHjlBzFgSw5fmGz7yQZf1dSEGlU
YdL1TdLJ3y6QIWJHfccMowIF
=wupy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f5f8ebca-4058-4adb-abf2-6dafddff0ce7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAApXMuhSytIdADMWIvft28vCMX84WKv7WzewN/RD3SpvtT
rGlxTeT2po5UWDRNg4yqkiujyfC8TnTSjVN6R3gKp0oEBXZdea+N0t55e++9Iiys
80cc4CMKtpqtfcXAZTIK+8AOWDHRR9pESx1RFATisjOGrkhI2GLRk/8JMQGQIMeW
BNUBILcaOh7ghtQCj9uIZKZeHoSDLp0o+LmgrMq7OXFQFsBg+LK2H6XH6hj8jLyg
Du7HF67TCH5P/PeXTvUvYl3/skbKDgWbRPtDv4StDSy5RQKIjOu6Nu5hYEm7iz6r
BISuY3bvW38v9rGmqm3hejljiYmtBFww6/24m7ibNiVR4+aa8e16RDt7S+znBPqz
+Q4WopXf7q2c0pmVwzSNwZb0z9IFg9r96eaSvYlOxhct7HI+LQi9WdcCJ7F1ty7K
CMzbGkbg0d8GzqKHcWz9jj4kKZgdCJiTM9SAThKir1Wqib/8yrfA3XZyD/EMu0vb
dca2Ms2X7UYR0C+EDPp0rh33uhVPT+AxQ4kWgDyGJefzqYdB7IzsznvmcNym/T8W
Pvzj/DfxMq8yqOIcf6bNEaEe5kTMIoRk/Eo0oKMECqgpXdbeOGlUs1W+vGRxMllQ
i37ppRSxZZFDcTnj94mvbtuEgYX+YNqxyMns7bkWUyyytsHDxt5uCOq+4wcej6/S
QgEeEA0SIJ+3dupT/1Pq/ZAebZrEs3GtVLC8Ng6uWgyC6TdAhr8/88Q7//GTjnWU
YP7MQP1/fCDfsapoMXCP+SPInA==
=QFpB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ffbae615-829b-40d8-abda-4cb24c6d7277',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAm0uydued3lN9s4v8b9YX6lcX3RhxZ16OB0eshY6+kp3F
W4wWoC4fNBizyVMTFaCGqdNElVMmVh4slK+xN4lDLZ4R9AeXEg6hvmjCeJTcAwX/
fqonfkWDYJiEQsiEZ2WNMPUzOvkZyZ9P1BQKqvKcbxFgUMY0s5U55SFBk+5BuSGa
AbpxirKwrWI+N+pmlJtPLtCEx9S4yRrefcp0I2Gz1ry7mxsw5c3Il6JseEH5ILw8
K8CWGXd3v1nLVsk2/j+ISmYUDlEg0UmDTw9rqhocY6U2di69D/JnqB9OHNHL27kt
frmh1wxMnrjq7Hn/Inmj+kwPUt5mV+mPVV7EKHOLm0sOaHgsNWuWqxRp6Qu1AVrw
9Eyz3kxddURgHataBDfIWqVlAP4YBQfXMxa/bZ2mlOWvgK89BHgnp16VwzL84lXr
xr3NbJqYwIj42FGkCPibLLN1cSns8D6MSawIhBcqtU85MLBng9qnxfnVR9X3oump
Ut+XxM1n3b/ySeqsaBYC+k4vEunpVjV8E5DwSVAP7n3djR+hOi+sXFovTEUfnhl4
Sr86vyHIXXETTqz87g2vx+IO7AVKXBj1RP0L/Qulim3j50rDYEh7eOH5J0I7HYxu
ZU02AvKDLjqhXUSRhCLhObCsnwH85BCWkR7lQwEO24Y4N9SOiV+9H5L0iQjnERbS
QQGZF8Ly1OYfHMpuS3lIxX7fLoLyI4lDGqXcMDxkPeEZ2JNETJxw4NYYYIm9fAsc
M1Ygp3pbm1oq3379RArKXpe5
=/v62
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
