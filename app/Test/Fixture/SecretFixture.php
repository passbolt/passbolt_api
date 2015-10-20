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
			'id' => '0309c6b9-405e-4d21-a630-9485cc6e23cc',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//SEbnXHXgdHcJcmzI/qo7NsDvx0J4TZb+Y3tbUdLMTYqn
TPBkj0rnLvvth0M8O4h6V9rZl3Pb4RggyFkQgvC7UQpprRC27+cHnq2JTZlFYIzJ
akS/dTGPQJuZn7mKusyHpgGRTb/NfSibGXicA5r3qYskedIpfW1SvGE0N/rbxjyI
Gx/D6qEG1NPyhTFH9RlwvrSd4f6dxjMx3bUbBU4wSAwVPO5ZxYAkj4t6J07M2R7Q
GptAO2f0iwjkpi1r5U/u4dd6Tv2nOWA4sghZeMD1+fu1v4BMGNpb76NTrG0CZ17A
NCydZwlzd09d605wQ5qgooNT9ErQXNDKgL5yiPe3kGZhe2IFrCkgs5hj1QwBY+6q
5dbrVmmDUm033Xcp+MZef7Qgbq/B5F5/KkL5wIBXCnUvsFfJ1YARgDuDMs8BSdFt
21zlrw+bg4A0rYhRpTLcHfBTFflEXCpv/qJfLQ2eX+/mHBFLWUNdlhkmk0F7Hcmp
keypoHi70J4rwiMDKnas3xs/g71LnhgUImskcCzJ8TrLG48gD9wJbxB5q6vCUG9k
HnPdW8g7RN/4Lvh1YQmwvy67nmGCErVdlUnHTVE6sjqURJjHE/k4NLd+y6OfohPK
l4KW/EZ0ajC23R4oVFlI9ANQupYsYq3TKbfhpxQSIKazRYhx01uQh2jDXfil9t3S
QgFYJFDso/CnEqY7NVp31w/2fV2Eb7IPzgL4Ojmo+ZrrvYkkQg12QLwFa0bmE0Fb
nE9kJYVTG+LYJ6zM+/DAeuuJJw==
=ntDW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0412caeb-b2b8-4434-a9af-fe713a5ae27c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//dNTX/osnmggI5ZL7dgTjN66DgSOLxs3hdytNrMdaelzH
jHjfaDRJXXFcjZZUfBYAIoMkNiV81UEn6o8NhRzBBB+6qVBhgX2J01L6iIe0T6UB
WE1l8MBW5aKThwxSx2CedJ33ETmLTt2DEOt1zFDYvNd7N/HWUDrCKdR0Nkdh3sKb
1rTOqRnhQp6lGqBlxn5qbg+HUjm7IpEJfRKNwOY3wTJPV9649vkueNWQ2hoYF+0M
r0HCP/oUQltGxar4griWbWp6A0erC55COWdOUk10q+Ih4ALM6XPcCrOTNrE3Ew4C
a0t6EBl+r5L5wzjDqqbw5F/A6zTn1RIUAbipIH2EtoR9n3WT+TgBj1aHdo7NhZhJ
cup88U36EhcNm9NbIfgJNiKXziYaJR2YTWgJsZwfjvWw2N0DFNtUDkOq+Nsl5rTn
5DYRwtlL5gXhYvhrXwh+lII6VqJwXIuF5+MoxmTD8uO8GOrDig18mPNWKqsMzGSI
O8s24e1N2r/R1qS4OT1ya2a9kcy4isUG0K7gqF94TK0WTygpy0CzQdsT2Dbe1rF7
D5vh2bILfQeH1qgjuYlBogoOE4EVy8mD4oLQoqoJNeet0gFcL4BXYtKMyPoZGZOX
/HkhyzzI7OQR/mmVE+VU+/xL1neA6vMefXz6Il81ITM9Urdfuv3rkCt9TdmvZDzS
QQFLIiuJSxsrEKto6cu/Jm0JK7JrPyQoEpfxb7UrLoi4EoM6Jajvo4yac36cdKA/
bPB9V/k50LUt/DfdeuW0xpJC
=Fvgd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '06dd64b3-2b07-4eb6-a279-137e2747aca6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Rfn3ZZ7ix+LwwzsWUliSCnb0PvKkXLmg96NlB7mR7uFG
7PhbKxt1XOTPUDkZCXH6Fa5PlVU7JU04ANWJkF5xX1tX1iYKDeTJFb2IDhwd+NuS
kgIpt/FChpVR/TX/cfVUGT6VXzAd8366o388lB6r9VNNJAOE452MVLiP0jdXkbmF
I31Or0EcKrmSWArvY1o8yA7XN6/Tf/cZS80UiE3ie5sUdlT1TfZ4XpBJbAUlVagx
J7wuDnuxfv0RsljGwMUQnYh/nB7nXK3q2m2Ao2l3/Io6SIVpz0QPnQnEghWxbMxl
TWUviXy1oF8PfB7rNOvhRRiAXUWORE28RB/3kxnVQRLIkd4nmCQnoztX/M7JGrDx
4+ujTBSCZYmMz09HHGf6g901B44j+vUst1VsaxI91UbmRqujiQDvKWMSpLUi1DkN
EBpR+W4ZmxGmCB8DD3oa4AsvHYXuD19aFVV2DYrmwXxwlOFLF56dJils9vUHlnVb
DLqaFUE8Jw/0ESkIsHb5YXaSq8ex023CRN8d1jz7N8nJ4DyfOWTPfWV4KZtb7t7K
NfBYvhgNM60jmaIl/uIuE2IhrpbTlLxXfb8PEF8WSGC+TYjU223OKrTzwD9INm2k
9nshpKGutGtOxLapN9B2CN1cAp+rIb4QZCK0N58pZY7iDqI0tdcp1vNsxNtK6//S
QQGI9pr0jE+DgFVxn6UP9i5Q1/XFNHdn3jF6icnnmDrP8MEGJqhC5wYvbztx5XFE
2QINjL+Wy5A+IL421k2vtZlk
=oxMZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '08410953-3628-49df-a891-18f96a54ef0a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAn7/lxbEQrnpYRFtaRg7CN3Gx0ghD0heGmgDVL2Ou0T03
tZ4SF+U3BdE0FpgnExb/ktPT250mFSx0wpfQG6Xbj6dWoJyG/X59SihbhOqz6h1A
OHyr9nLNQHTq75bnI4PcdTuQik4xl+5FXn2V4ziqN/uz4sztDOip6xzFfnhIQKEt
NVP5L5aCGOL/jsGmXVY6WRqfXkeHtjohZBC1G1IIFN8sE2mbGP84UmOxdMYUN4ff
IGocFO1PvCfQmzCbsurqRp+atK0p7xwhNumSbUVD2/pqdnw9ZmmVddkRh2rF4GOR
a4IYh7vDMEdDi2uxd2Xh6uH4QwqvSK8MUn3wpx/utC5zTg60vsNrRbFcGMg/qbuQ
EoUcma7K8mpWtwddVLwMCfhhWbATE3SNmkW/yXyKXSxtljxQZSdOhWmTi5ZYk2bP
mBWjREDolxerjUe8Ziy+nA4cdaVJBFkH83wnDoVor8TEz4OBecsJFlNkMt5OUaPF
Hp0/lt1woNMt6Iq5sHnJPZ6kfHNQZgTeT+yJmuXsTHh2OIZQg4PTm5nNLKoIrHOs
fuD+SRhQNtn3Zphy+Ymg9HMicKZDWCfMCsDG3eh6TWndUg9s6kWC6hRxf6TJKQeO
fDyVLUxWSN7x+7pIJSAFazM7EBi2KM0w37kZ2iz01TNXqW42TpVfw8aAenyXpLTS
QwGK1rd72tNr9qmV2nf1f+WLj0EANLXbVY2zHvL5ErvW0kv3KSQ8P/273NAK41cU
kBCCnXXjPQC75E6znRI37JfLlvM=
=sZCL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '08e844a7-b8d8-4ce3-abab-f36dfb4c2c78',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9HStHpqtNBjVHIHTSWjlPVdSUrrxrktLSdQG1tvqgmEuG
affj0Ztkq2+F6SEoUiigBQ+Z8aAK9jVHhgT9akrcuAnqSaIdrWyeKx+Nn+X8h/wC
lpdQPFd2n5PjZcefadlhQlTJoxtXTYQ5BaMqWtY+3F/pB6zGeBum8NZZRwly0Scd
93fBnU7k8Ed5qNk8QiKj7dsNG7UyIo040VGJrlUcNZlnBS885p0HyCeG51A3HRRE
T5bGcS9/JGraoq1FKo41u32XwMvrwcGIG8eY4XidQltUsR3t3ZggbUKyxb9/NYHo
z02mrCVflg+nVy97zi6cuR6thA9cmJ+/oSMF89aTC5waz4A93N0HYnvFI43yeXj9
7tDWfvmq/dVpAmSNA/7wy3q74I7OHbGPtIxACUfUIP1RZjyBsjSrpJ/vBfgdvmjE
BPOb7vYBntSjVVa/8clXCir/UePmhcxjP+5Eesh5L8C89rWmfirEPpYDl4bL/0Lj
ycN1WfkQoqVm07zWzI0qAH+s02KlgIJLQG/H89Xd2ZttvA9/ot0fQ1JOKsnH6/0H
1alJVf6fznrPbPscD3oP2K9AyLXqX8n5ewuaDBSIFAOFjerpCyT1evqYJGnbJfLM
3vcPzmsMWgSTnlWiq2hCBfOoxp/GmOX84wbrUDOZodKChtQOOawWxzrIYfpByifS
QgFW43hZi4JivZi1urIrxpNGHWXWUCaBPhJ55Lun0jzChoprpQkQp9e0+XqNP0zk
bPMBjhMXe9BdKUimJOla+nRX7Q==
=c98A
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1110242c-e939-4aeb-abe3-070da7ca6525',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//ZusVGMovgXwP0an5U0fwQxqwPGUljdu/hE6vHsaP83XA
bFaOCLE+pzL5qu2LNkPZmUTqFKJ7wOf3laG539JTdv5kE0gci9iucrf2ompzfkpZ
3s7DfpIQapKY5JYC7Ef2Xy8UehGdWIMwVGWYdp4F/tUNrWLOmczVWRJE3uSBLcQB
g7sMDM92PkQTYzjRdGqW6/P/+2HzvyOBtB1Z6RhtLN8n959nrFIRvhqIaVjB4HpA
dLAGW5xAO794YkMxtO+J1NY5/7D7U2m+hUZ455E3Jk1+m4/PkuqaIWRSwkdHeKnr
RQt5rvOcsnS2bcZrJh0/w+npEQIHABfMOXn3PX1rlCuRf6Y4Jft35IgNq6Ph93DN
qN9dEO7NSvQul5IMPcjG2VPVwO72fb/JMoODXb/gpqf5NVe67CweD+rHEUjtTPUb
0s410ncqBXOQ/7Ao9ZIaTyXiyhr68/OKjJ+b6fQPTp9d1l9h4U0NqvCuWNrBHoHI
LzgFE+IVJ8DK0Y39vdd68Xa2Mwmwdtoc/tx3eCs3IGGUHX9KUti4i3wta/CJE9Jo
OyCgToPEBv6aajz/sDLuV/ADR2b/yBcm0TSK7m9qmWrHTHKy360b7m4FcsDgqxhm
jtDxo4V5yJQlVK1v9Dfs9stO+ZGK/yDL7rEWSHwJKHgI2E2rujKOtGrdR4mEeo3S
QwGaMWhYJEPfbkY0mVe6xWW2OE/XBffGt7FdIDh9Iq1TKPba4Ja3scEMS2MIE+Pm
okvV8Jk0z9hP8mSf7cnFDwL4X3U=
=az7a
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1550989e-8aae-43d6-a200-3e7974f601bd',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAwFJ/LWvJNSopJTiMBv8Pw16BfzcpQtJ14QDhmJGyAW1R
eu/1qMMrGdqzJPUQYfy5Ky6dIrxSZOJlATyGxs/EFmkMfTd8N1WkAK8wTpyy3vO5
4gN8T119Q0/pb7TTK4Zdvsqxx6z4lFDESn1PNOWtpsqXC6ivQmyWQIilReNV1BLt
rXiTkC4RtncCaX8IYNfGfOMcwpWgrPdBQw9q+BW5tAZBOYV1JaB+43ZvJCzw2GIp
rFy27Obt8jBxByePr8JCqGb6p44grJR3ewM1H7U4hs4vZF3JVGx7a7t5AE8ACY5g
W+/Po/cxX8c+fHFsEmf0s0BYVRdEf12oh62JY7geFtn7zpB+6xLpkyRWvvFDb/7n
Icy5kpRT360T54cS+NrG+NR7FJVSn33ILYncJVZ5nHpyOFvOqDQZFhXvMSyE9f2A
N1vgkRERRx3ywS81Xqpc4zsVT2/06htiszpEeYeCYyl1YpNVZ422iBU8+kD2k2Vj
ivFoz9KJuf5aLlJlo+4GQfc8Q+SboJMKncgf9SbzV1X0iJdGveareD+9qzojxNkc
Yw2NbCNBvE496kNZbmEOfs87fTR73uQdaxgtDI/ZNAwI7CTC1A875+YC5xWWU21o
4KjFLciXGMwBf1wh0Luw02Ocdr040u8AFrSbc1y6QTZbKkmWAuwdsDzaDH4rPEnS
QQHOVt7RHbKoDTkdGBUEY25TJoEPgjvsZxeS2AsZlmKLoTt/5ze2EvExRl5NK1/4
EGsmRs9srnd0LbWRlpBc4PST
=xYuc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2274b167-3542-46ad-a9cc-7b0279da4b2c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAisR8Twbb98alpodhaQzDYaUx4wYL6MxWkxT0D36h3Hbe
AzESHuErGtf18NXfpzC/JPwNPaUmn+1AZJvHBiKXfGNQcvJqjqXLcXCk9ps68VnU
lcVEykPGUgtQoZwPpyIo3G5Nbhd3z6kGLPKq228eXLQFsHp74/l0uLbyKqbMuyUg
9VPHxWGwD//xeM635R+1oCLnpAgcOEnCN5l0ovluuPittpzxQWjgoX3Kf8RJZB+M
kJjgFnFN+DDiO79uWl6w3U/btTT5YOj9Tu1T1i7RN5qaJPLPs1Fr9D7KwQxwkFoz
dJ8EcvCFEeOrhnz/8WPCQSpUhulqNU6BMv9063iQg77bjnj6b2DGCxabe6GFfktX
yl9u1k/2pqtUnSv2gsPemHeuN4l3bJlWbBEvfgjPsYwr6BsuzZ2PdfrknDDCb8ei
E0Iwj+Am/2XKh/2UfVLltlNRyMgptfRjWCYPyMm0N9b0dXmOls3DE4bWc32VDEbF
tZDeIyCmWf7Hu11h4Ao8YEtQ9QQkweGvuUto0cf2p4psemMwAb7dMHnF1Rgbvzin
/v3fxi6mkqKsBSo24aE00i3mbIf0xYkCAynaRgj1zMZgamyaQmtRRj5gsV0tDpwp
SchaGxYoB97ULrNTFxcozUwrIqv8jXXO4W+7j9PnrLcUDeW91AGGS3wUqJ/hpRbS
QQGXRFqmPHnABf80sgHcU3t9POuVsvgber+XZOCVV6CBpfCWlvqEXRfyVWMZ3oKm
Uz5ZKjLIqlmBecT/YiAgul+v
=L0iT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2739ccad-a114-45dd-a6db-3a9cd369e67d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+KKCKns1qAjhnRSfCwBhwys6xJ8x7O7GoUvj+V5NvMByh
WmogUBV32/R+09aIG/TtfZ264l2Q0dknMDlZRxRh7zLWbRETKwxCRDSb0oW221C6
kYYEPS3stYl7vtQOazCgMbSJUFMwVclxYbdK+k4atUg2Pr/AlBGwPp+Gl5t85nqY
raVGO/3lwPa/vSzA7DJnCFdIAopcASYzt4fgPwek79JHAjTpz/DpgP2Cq8s5EsCC
qg/NyHZ/WcxbPIHfwSgpxk6m7OLNibuVkJCbQmiAdi2oNuntNxXRfl694IdtnXr/
s3rlc9nj0AZKJKsbhjeGvg//2cW4JP3d9RSGKQFWLGe2U1F1Gyt3z30If0EL0/Sa
Uuukyjcm01XlqvrGVd25E6ZlkTuOZz6pBWy+aDGhAbHipKM0BbUx0ieWYs0OFcfq
xp+ISVkldqEGWPLi8sAjmQRDxB9hNWOItrAyFXV0hMph4nm9hRVQkzopPq7xc7DZ
dzSQxmhXTPbTesNkBqBU0lh8L1hy0Hxj0Bei/9aM9Ea+MtdXHgTZ4Vj+gvj/lVAB
czLB1e0izopl9n1ejIQyeMPL1sw5MppDBc84nskFeKTUCcPpDqD6lPgvKyMjE0T6
9U6Q64gwsiMSatSXmfSe6uPmp2BMQZWfEXMPCCEkaEcGJ6kBm0q83q85yQ4ZZYLS
QgEKEowC3e68juHb1PxdwphXy4jfJFYj6OJxXmOzsT1VrUNvbqnoH/N+TzkfRtP1
6XxbXlv+fS0swFTsNP00uuRSbA==
=jPNB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3110cd12-a598-46d7-a803-5bbf48f97c47',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8DBL8hCv/ihw3WZ4LTNyD3Ub4oHbq2rOPS4w7Z9GtM8xi
90Kscchn+K/zsap/G0Zb7We5/fabai+ajK3GhAGeI8frfu3WlJHDOiagWaWWX6si
YspDz/vdcN4vdUZxZxuGrYBYdtxlWk0x7RNhZSy84Uqhk62C1LC0qFb9JghQd9OD
B8uJTUx5rfyjM9otpOXSvP5LtoOz/PG/6hhy0IvzkLG8YLrY9oFf7lcQssXlfk2L
uq6fX+vsv9M9sjRvXf+wQCichMHWJCeExCmjcRuN4pjwLZnS9cVWPBltLOosvo5d
nLIWrNcF5FEwLZ4n90YUrr964BDrclNaoLqVs8YAUSrvBnLNiTVGcOfKF4VuT6Og
ZrwJpJuT49yIpYKAiEo72sGwTESiEXw4kaTxJe/jnuZYcXc2nDM6nrYFNktLYzKk
5Z5im2rsegD41l2gKn9RFfvUq+tVjUsP1rNi7nZH30YjDhRwbc6Gru25WI/ScgvX
F9iYR504YC4M58xpwVXiTW7A/nJGmdoj61XT1fgT2FTbe1K/SkfauUQ2gV00O/C5
6MWb7/W80ooJiKOoDhkcNMFd35Bq+TcC4aiCMYmOzzLFt3FxgWxmzXUPDT/OiOAY
YW2n+GUB6DYRBUiayIdhbuIJjon5+Ts1ytdur0cijXZaaQVEjQ5m1CjVgrK+ck/S
QwEKzB5Ok5MgtSzz42octtRgPIQcbhEEddTuKmCP8KiQy++rzbwfPUnQsQpZpELz
deSSqnqF9Zra5XLolO0li++lO6E=
=kc+g
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3388f0a5-c4cc-429e-afe3-f07ca73d37ea',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//XOHlCtlOukgJE7tBRTdb6Iv2yDg8fT2EbEGFs4Q9ld0K
kamOzosYu4sJ9/HrBDOcnmpb7y2xX9xFHajB+aCXy5asGQBX/3YEKDNWq1SR8IyQ
+iMl3p41uhwRxJyfaRNUcoQs0P37uxb2q4hXkem/b9eq/s8Ci68jIKaNiaiAonbG
uaYerEnLunJd1jIDV4Tv2Ll4sTMjui+OSpXRxZtfTM2HcRN48EvfFRdMqXhpMEB9
cBS7QRrpJ2kl3ICaEM47JG3S4OvzKBKFoWesaj5ZYY0RQOAqmToQeg++hpZqKu7N
sqBOY21Mksf6/s/xfYjbFkWYsXEueVk3PxxOIgNuey2tu75PouGWYnGJDY+jMMLu
kaMBvDr3RzxyaZ+ROebT4eDm1TElSVhlm5O2KeAJIxE8Duf3ztm1GjHgYwO6dum8
YCuLGK/nqmaDLf4Gc9TMFrnp4ykz5IR21SK5YLAGqb5ZsO0KouXKhK5ybC8NxwrD
BA5MZWhbX97suybF0H71yl+nPs7Kmd1rt2cbn1ge/0MiFAbK3BegSSPkC0RFFXy8
E3O9490Nl0pFIBcOWcGZdZN2Q7+isLIofEgTj9GlIirl62pjVjxfCiWwZNvN3MG0
oapfaSRRiD5BW3ygmw6R1Qf2Ha/AR+bewnIEpTGZjHVgytvM+MaCoU94+pcU5dLS
QAHTGpnm1XnhsgsBAmsIhwryuqYKkYcjXXdvQgyOQ36mmkWIFxW2Od+LDFx4+utE
gIfCfRZJ4pz+5LrV1xAklfw=
=zAkp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '346f0804-794a-4ffd-ac4f-3d61b1eeace5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//SppKv4llJdIFwrk2hHcPvq4NeyOlGbzKxK3UC/lcRb/O
PlRfHZmFmTjtQ3Oi7WNCeUMPrhE2zn9Pss8HLhukK6usHkd0zS1+2sT7OkTxdJN4
apXvnLF8M9xsfkRWdY7n3ogcZ68u5nVO9VqM5bM6EdsEbCgIjBrhVFpBVFpRd4hX
21zNh2N+Yz7vnNalTnYTZZ0mh9xd842qympV0QNsLAj+pqkL8jvB9VCX3MsSk7Dx
jdYd66tYFpYXORYGWDc2vvkTLHkKrSbHQPFOsCaQVIj61x+1PGYqd8EFyShJ/XMv
Osv8LC0e4RMuIzYY62me6O1kwhN8o4IinbgqdxuA0KyYSHgGb1yeVRp3wS7Qg/z2
kvqdRbcHIwg4U8yUDCjWZKXnPhXFIMp68KsfWSoBhQhoLD//xJ53pduJ10VSIdTt
iEVRJJZ/9/DLo35vRG20PpWoWc2Vn3HVqWuGbRkh4EgrrrjZ8/VXKxlHc8uWDrOe
SsTU+B5npaheyMH9hOlficUBqSXGcIjQOUeN8fRuPRPoYqkpDgbTyn19F/QmAWI4
L5xqeEjzJC3f2c1rLBSP2z9DwlL8HGJCeTJVodp+QC6jdGK5+Slu0t3ErBjpbkcF
Y+9uvaxPQnyCnFHFK7Fp9hvCA5LwqW60xLZor5lz+wxRlokE5lmhrD+dqCnyIQ3S
QwESMdvOd/2G0GOGQvN/DCrQPt23eahoa/I8GhwPdvvbk2Se5N0GPuCeROPCdU9Q
btc864TO4a23WDZK4iKt6Dt/F9A=
=IYCn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '35275493-7674-4e9f-a473-99966c3b6131',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAhc/QAknAjPT+O94FIrpdr6K5ff4Ba+t12TCECHnI7YzI
0AePqrE4QCPeNP0feI57lLo7aHqTjchygKPjnAB8aPjTLedPjxRaOM5n3ujMpoE2
JM8JM15+oVvnSSgOlKoHTCN1qEVOJ/snjgwM5iJdZJrPLnLrpQEesbTMBT60XicI
XoEAUUKTjs3Wy9NKq0mz0+YGK8UiZd9t+5rQ+nokd2FNgSf5TXVVYDReVeMUfsvX
vZVdB0AyVuhYsqj5GJGu2rxbCgX/bSxfjwbhnOwMktmqGb+0XsfwfFuiBialEjtt
kDhEi8t3gkKOGZetAYDzQcye2sO1il7PQwApgMTX1l4s83bva0FRAbuadH/46RtW
UvffK5U4O+8CiGIwvcGozKIOoJZ74E3/7pfAICeKzz5Yzi8ox4nzPQeQgHVSXWf1
Mg0j/hoziljavvOYLjm4slfV2kuq7yIPsqOszf5qSP5q+WKvxi2TnMxVaI4Wua39
wMl4V+pgz4ceg218Ak3aSa6Bc9eW8vn4e453xyZ8tBPC1OIXKDoJXsdJ3kA0cvzt
xuPvrIvVianFlI96giIruAzMKxpHCBAzIyTC7aS0TLxiBMTUBDhZAY8V0XFjJRir
K6sPDmpUrNj/PkMXgdciQih/EPZlynoM6lJzmPdE7RqGlmhLOVU8Ugy5n7K4O2DS
QwFj1I5S2EKmdZRwuF6PPmkXWCMe7sc6eodKmh/K32sp3hL/SNeDtNdGVn8/PFf3
v3n+6NCL5KxagX8d1Um7Fgp+NBI=
=aht+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3be2e54a-f322-4ea8-a9ba-ff6095d01f33',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAthYH5vCoPotOlQ0/xBJC+SO2cnD5YbxmCFwvQTtmoujl
/ZOImjyha05ckuz85PDKuWAthkEgsytatwMoKs2hG91/dBFU9+XEY6l0UOnwOWFH
NkxASqdKvX/+357STtgZwCRZm+mPLofHFkGy5C68Y8i7ldPcb1c7Uq5CeJkiHWpu
4Xkx6VzmUpz1IjO/zt7xu+71JGHZLaq5MSBCj0SQDMmhSVdYGjnMNfrNEVLMgjA1
Z33pFmahE1SEGvTn5JFQ3+FNSwPoWS/Al3Qph2ZbcJltQnf6XIqpw1MNsNz4Vbll
gBGcYNXvozGovS8sw32XBGRYOspPo83xtK3EqPvMujVGgKbYfcL2uNMetUtRABO5
zmJHp29gG/bFX/di9ICRJIh0YdKUFobBQ0u0CvgQnfYo6wSINnsX61HdidnB6WGp
bi9oUVK3dogfLR+CB/mmquqZlpCSnAzX7pk9wzK160t1ZwGd9bjj32d8c0MJVvId
SWtHn3e5Ai5zwc25RwNVyBNENejXN8QX5iSlzwdEcecCsx3M22iRyt9CUTgSzzoU
DBHb1vMaRKLnIPuQgEUtban8ZRkIdlPG/UgEoZ4TjZfYE+IoE/70M8T4b1Z5wm1Y
P/G5VnFWIUlfSSEZz4ewO6XUcxhMPwG8RbtIsNhU4n7s35LU9ueI8plYvn+XQrvS
QAEjjL2V/Q2kWu6fY60F7jhs6RM1JhaMRCgjc9m84qTwiMSWEfXjxiAvVqR2uW2f
pIiv9efVEDSgoAwSYNS4yU4=
=cV7i
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3e52ea99-6428-4d01-a727-0a313a110403',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8CSdDMgSTORZb4hMEcU8A7FkoMURAzeSGQwTsQ9izMJM6
R18HJAUTcAD/Zke8VrahsH2ZEYoH4UeIRaEQM0gB1fSy7Jr8JHEHqUy/4MRNRds+
bJwMrFRko13m/ldN1afT8nScaWTrOTVFK5/3MXuDAOhPqXR6xBb0C43mh5i8MDMS
2I09ttfBiwzaeg82B8N1/apXawuqZHNadCvbSpYASL74DWEcVvqZxegsEJOTU0Sr
QHnmZm5Ci/FG4qapBGEEZjmhDefSBHYOx79kGwi8dtpnIqZ8sxMnXj3NVBMkSyI0
6f3P2xpiZtHMPN6PVDlRSWGX7nuPjYC12JSp66l85NGkbYtvrOmgFxt+sLph6DvL
BMlzAklKaGpoJIyXnSJVZbLSSuomk/DxA2PHj2crUet0o8XPVfXTLB3FpDD1kOVY
rlNdMAXaWqWVf9D9lX+M31ZuSDFhrpHntk1l42bJzLZsQeLDKUjcs9MDk9MflV4n
BYmhCI7JTxFN5MDwoTBQ0D2z/J8jNXa59hdNNMHmAHV3GhOXn1wl2JjiB14e2SKV
cirxYSggOOPtI8FP6LMpz5hYyDf8NyAsamRnlnMHXu4SAySIK3DQI5GL1hmSNp5V
AaTs/eOiYgkywHMUDea45meTTW5oIg5XfLa4EPwCJkKbrdorjCJqQZX1gJVp2U7S
RAHu0hLFxqTAjnFRo0j16N84sR+xoeUp6knaLcnnBFCUTbesU6BZfQotT83IdYYV
+d2Cx/dEhaxKQdBw0bdQJh5g15tr
=qKzI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '40370bd7-0bcc-4e9a-aa90-2a29ceb82d91',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/5AQQPJTQ+MnBMOL0zkjTwG47djmGHcTs5YdZ1FF2FBN1L
TgsbPOjassIP48ZcTccR07XdFC6b4gq47yvJW1G/EgEuXttBd6YH53MUMvIAkDUk
MemPl2ViZrzqs93qHtpJ9eZrJkU5U3S0saRfVTKuQNPEdrhuXOBRUo1c9QJSd93h
PpGySueTjEtkNmd+O6S6mVR74WBbK2srl1vktbU7KnubmsSCitQruaP6aRehgBEn
q3w9YPzrCbxZ+icgQw48fHxIow2fpLaUKLN4y0Ncb7UNW3//cYqqJFGg3r2TIpxi
aUWvo3n3LWNdCKNnqHh+diJQyVCuZtoG97Iz9hScgT0D+3GwnONfC6q8BbnsKERU
9djbfvzU0JfMGu/mmwJb1pWlmjVlJFEuZPPJBmsAfOGdsfxDaU3qdCMJzXWZf73Z
VXswo1bsrajbJsNHfzrdV3reZSFQKyOVl+D3rHwVU/qWw9DP0T5Wv1XzAJfyOIm6
qEqfI9IzDv0/mZCilTqVMpPVs4VjA5RXiWeSaIUxlUHAMLT+IwD5HD+/DGYP7pBD
zlbKmAEJctr9RVjiZ+drzIwd6fuY7bvIGXzgDPZcoDJjnnq/kI73vPhKyuMD5Wdl
QVIxPGkxB2MQhtWEWe5ioHUvb6r3d2mp6okgq+HZinGCtu9pVPgNkjpV7uv1DVPS
QQFJF11UN4WdeWp2C0fM7KSghtEUDE34A7A3BB4EzPrW/S5mCQZ8SGjAd07ydjLI
+A7M5BBYgLVrhOSzAjGb6a9d
=9Vch
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4fb2da80-34ac-48f3-a713-524c2678c743',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//ZaxUJiN0n28WMBgVHVQFG+gjq54kSGhiscEO/8sjXXG+
KmhQ0w5yHKvkwCEzr/WxUly8u69xqn3SQfVbPY8ngs2Gc3zYyuIPXnH0i5adqML1
EBMPO7xzH8QW+2B3eF620y4rCSK3TfIFN/2SVGpYiTmmaT3c6prDlcxEj0dLhEBi
TnR6Bf7y6DR4vw/sP1Ub7VEdS9kVqEAyJvZaE8xoB3BBUskTwZj4GKJo1b4djndE
122wvnJV9M+m4mrJFOVANOoaXTn+zQL6a3KrRXo4vUJVQsJ4xSPf4PqpkiTXC2Oi
xDsTnRqJm6adW4h9sO5m47ysrjOKy9PAIedZW2GEuMyFXPOi1LH1rzVol5OJzDxi
dptFIIaNHPl+NWSQHrx4cV9PYj/QqTjPP3H2ZcuEG3LjPCDpqwUIo8gZO2NxzIYQ
1kBDI5kClNlwRUZenPvZs/OZROzS8lJ9OIzKTCCpVEbUQVYOe53Ae8ycv5Hd6lyA
GIxw94v9vGqqIFsEK4fEG0gd1gbKVYjRu9rvLYHFfLNOFVit+G7NxLcdit+OgXB2
E9DT5kPzeQRbNDnqc5yO7fblY7Xl8ccEWT/0N363ip+IPzuupOFCrn+HdGaao9Uk
Jpb3mFAVWOZAAn2xeEeUXRCiGQVZqbUHvWetixO1jv96AZwY3HkQu+9vYbT0MsvS
QQEzr+RJaROI3aiDLk9yD9NksD+bZo+Uq76u3kM+f/wMuWSU4Vj+XADxK0/VFy3W
X7hhOEtl5kHACMZ/AsM2blk9
=AvlD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '550a789b-1a76-47db-af43-ae3a3c46b238',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+LpTx70qrSDRGWfCjATq0O5BczDdonleV6S9Lx8GVNi15
Dde/gL3ZOCORRdbxcIiZInyEfdSv9RykJ1j9WulcDpIs8pzE2QwxIQIcJeWeoOU+
ozUaoTs2nGhQPJNAx/ovaZAXLtC2t2ZnBFwyaSHRD0xnJjr92D1jEdyHSGbGxm9l
BQh8FXLNUfS0OT3+5LeSk1vgHjurw76Gc6O14CMwFJlapmXpr9hzE+FtmU+lFXkT
58/oJFzULTmGm6/DGlVmqVwCEytoSk+6P1L1VzBKqyNCxR/U0bA21vGxy5S6I1MR
w5covucLBy5pzQXVXVaVT2Q5zkCkPKr9mxWgyPdj7KSNBKe2oPQR/g+qAlE+Wt1T
D5fKyBY1w/eMVcsM2+70/veGHQvljIgSeGulP26veoLCBSyz3/xIZ6tGWytbA4TS
+KccvgGw/qrKvhVTp+izbWgYBcriuuNCRd84rucp8JRSOrg9w1izWUj4ujJYOFYT
Xh3wqydqOGqC+gVx4B8zP7XWMcczoIj9xXCLHTZQZPa1LLUU2x3IUZqaKW3D0CJj
v8G5YlbUPXxeB7r4gV+6CTc19PVGFlkoOtzffcOxb76DrubZ2ykpkWhUfbj18d2w
11yqCQf2xx5tYlEYhinZ8X9eNt3uOp7281goF0RANqiuJrjOGPfpoXb4lXVGiRfS
QQFOh6CmgKOKMQeYjLjvhaCEpisy3ARfiqDLebBIsdgF2VAMwZFX3n7nk9OFlzOu
Fhp3va8OkzIBTmwxef5G8ghs
=u720
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '572322e1-8933-435b-a14f-7527f2e7513a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/7BOPcLIMt3DMVLwr/i0H6FlrQRoI7dsAR4aJerOEpVQUL
8UIqJsXIQAtRxtp0bS3smQ4Y9r6mEy7RT5I2JSDZuZdZ1WcayFPt9s3S4Yw/K83X
f9lMSJJO/DG0w4p+YXk0W8l5Jamc8j9s9bYzB3h1eMhjf2cPETehvmBP82cpsDyB
Pfe3eEMO3H0WaEjvt0ejuO8TnAOKqReH/4lOyrpcVc4g/HkAP4ezlZH81tH75TM1
Mc7hnkTe/p9Kn9NdC2xAjqcTgcr+A8htynHG2P8ISyz+RsK0rixnUhl4A+vcci5Y
sT4egxIopaBOy5dkn7IDEZPx2ESAmckkzZAPmjAsBkcmE0bX7wjTAKL9ruHpuwTm
cy6tfGZGHswAp1gj6nWLpbpIUt4cDh2FbcfWBLWp/2jnCcPxZt2TyZAJkIwPgMTi
k7gPQJ/cRgykubhQzk31Lm2lyNGdp6zrh7qArxNzGDNZXo2w7qm462UZ8JZasaEu
OPET9r+HoEGalnBWyAnsYSZann5z5idKQXM3P4z6M5+Td2Vd33a4RQ2HnzDOCSdY
NV40ug/jVqWy9N9ZXXrjFRthfzhjESh3lXfnRbFltkr8sC1RjhEyhY5P9ll2Qe+F
dcxCL97owJWigNldYci9bXP7oQKLse7IW638/WHcmXTOLI5XCUjjDpI+u3p6G8zS
PgHaTEMyvNcfYqCUETnNoGEUbWqh1z12dY+DwTj6qq2b+X3g2GgVzU/bj6JwvAAS
wzpMi6YhA0BpTk/ijk25
=SpWE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5c4a2cb3-6dc8-4fd6-ae50-3c46ad2e266b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9Eayn35rv9xLtylyhMGHe1fXuzh7/wJcjl57jhFtp2Uax
AjcxBAUsAJsJkIYQc5h5P4QAPxKTa3G24mnqxHepfMO44OoN+xQD3WcdH1uzP3X1
8OtLQd6TU3yFz952fpiDtd67WhVnwfiNdmrT6afqnjAMtyfo4Lck4st+bnzdBJWk
dwbgIRVoerx5MpJ4X2xYQ1Ohlr0SXhI2YUrw9kkuxcYVLGijwfPAB8PeCHdwKyIU
nnyGH2/xYH18yTGMLBVhV1PJRIY6D6uVjuw3csuu7TsysFvqWSJdvPFYUKFqzUQT
wWOaRXlKUUBiwtr1Et+SVbJlIjn9mXXzmlFM+WcrlQJkwU5dmD774v2fFO6q6k0D
IaymnVZ/6EFxVxR3kwGWqxpo73n4IV+CivKl+OF4HyU0/y6Laizj7MmGhzNPdiVt
g9PRYbe0H0lpjdT9J/uyZz7dKOC30poFTXRvxXn0dSOTDFfxU8FmkD0zUXDCABoS
9+YQeIMzDRCTxoaYWbDWJOkOiboiaXAVnsBVUr3pwNeQRI4HeWY12/eOhbY1vzKf
mwdIzXscuqd2pUeeYyviR3kSuU72iF8PTszuMCahECv3uZzTTjeXyxpOnrjppABQ
NGRr1YihufD2vQjKZbB+IKjqoGYKbs+v7c2q4J+3MrAlu1StaPQOqfjtoi9KqEbS
QwGC1VnpNBv6wEC27NDp9aevWsqk56gSp8d2/VAZef0uG2moEIg3Hu+D/j3LblwK
228d2WqrUgE1tDvNFj1ppej0Gjk=
=0/l9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5da71f81-f00c-4e23-ac43-1ddb78e84c91',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//YWgriNV0d0h/J1kGqc+Czfh3eb2+vLUkpRI41CsJTdZz
t6eDRilTBeAOktbsTCVNTWIse7R3De0FgH4G96z4h7y1NLf16LzLP+PVhMKu1uyW
ULpsaYz6oaA9sy4FPfZ3LdSiv6au4mWIV1v4jipolz+j0Cw9cmJrcvwklN8YJ9ek
hup1JzFN0rkKYWMoRfFihS2bTTgC2Y/sUMMbB7CIVtI90uVX3nC0T207xWceyVa3
bgrfZ+b/3u6DdbVNYvrp37PBYkvJcxyF71SLVs3DSCEunxyOLB8zMYdCfML85lJp
tfV5UNPzyIzZXRRTnypFWUSPiT8GFHYlQzGRABl9xkNJC55ZfG0CCRRu72lTF3dv
Az03hJDzuuU1jYXPUyhD0eysWNrlJRlXonP5nQK9C1CNK+g03AGBkWI+bpDv7JgW
n0yUP9L0PtQ+btPJIqX1k3naMPvd5ZNJt2GEkZcmYFFHx0jgKjmBB4drUF3Evt7m
1KuGqViT5CjUKig0LHQqnPnUYp4F+S4DYY+AMRxaqGWUooqZyfBhp4EJVdUhZKPT
NxG6XCSp26Bd9ohH8df0RE24Jz5z1+0lZF0DMVzEnj+l9qrPJT950akZTYO9jJI9
xAl/+TVN94FyrA9hEqtNap4VMZQQ9xGbHUcB5Zfwp4bHVcEDDZsLta0CWhMG1x7S
QwFVPCnHOe5hTA+B2XFG6ElbFmZ+oSwASFStBMl9EBw70BAeDHDcNm0AwNBE7Ta7
b80chK7TW9IU+fRO4/tTcGh3yQQ=
=H+Uw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5dfedcaf-d750-493b-a8d1-2cb81343a072',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAArpIoTMU0az853wmyl7O3fzsOp+GalicHKURBEPInX8sG
zr2mGBstpR9ImFunCovpd8CN6UCzGyDziRx21xN3A7+8RugjWnlMy52RgeHjLCrV
kSZOe8gz4HOeSBQQyvLyckXKTQlIWfpSrFfx4rCxRlV7dXGr8TYdW5dxx+viIGqd
DyKCTzha44Lz8b39MrHc1zhqCygrLfHcZnDNJKsZusLogWkVivNwExEjaP0OgI1I
bHKPSOTzfzsn9tW0iLnvFrpEeLzXA7Z2T5MWXIuTtUDJZhUFR304XHxkwI8hdyn/
7nhAXeAIDVDW1sDCH89a+g6gYTMzUK0AOP+iLiZGJJQRmlIHEBZqMRqNEmu4QIvc
lfgLzQM19EymB9NGF+RuADHx7d3cNN2HoE5+sVffq7CmE0fWjYIVl4de/kF1Gk5b
ipxdCHJ0mJdoJnFo77oi2O+mqLx0demZTqCTFjesqPfbR2B6H7oqU+DTKn5qP+gQ
8csHT+Z7RdClXPHioN9ffw7pHYbn7vFJNps/7rrBXKY8L05kNb5wfNKEpBrPDQUU
p3Yp8wU/n9VgjiBTGT80AYo5NTAnKVFWJKD3VWj12Jf3wJV0COQ4hRczcwvA4noz
IAXfD2NhzEEMMNwmaQvpGHM2J3iWsftk7kls/NKlA/NZZoah5GnOZ8wEubtaxS/S
QAGYs4SMH6lZb4cDAy6T3QENaUHlOUqoA6KFie5go5VdnUCfx67HAXjum8xU7ztw
01yui1TC+3cbdsjKrQ6OwKs=
=mNH1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '64ed8728-65e2-4c8f-af81-dd893b22c8a0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAArZ6j2XUAgJYKVg+XGb9iOD2+sEJ66ragHDHxZjKf4qKi
i/bPKC2UUbDyKiENecH7cCCf+sTLJfHbXLxV3RaOzfLtkMAvd7+NwtUlKpliwgNv
8JRX2ZCz3ESqFnKT3mz3x/nnOYPjYbHNBPjeaol4iXFNzeRBifJSCfH5LZKEQGrY
6T2KP9z608cbIypTrO+1hMDMIiycp7Ptqk1xqSv7N+JYHINgSzXFlxqABOOslTxL
1bGvbTtwrHeOZOFliGigAHk0Ym//O1wYT0zg4byhSoiICk4gDfO256r7Z5FQ7dQu
SJAjbEdx71HHmMHqp1XVfnPqCCUwYBZk7PaDjNKbVLXc/JBVv+SYw+ERt5lFnMef
7kivaDkg7xbvFBZHuvgoIVDA4IKi5/vSdMM6jlCcQkgG0AKBnWCWGLmlfb8eP7C6
9w8rcDmvfZt0V9L2Q/cbTJ22kHSzuH1r8LQk9nX2bQrRkII1LYXiOl7sme53DHsh
rnAdfIS/pyvu/k26qcvVADFsLtwY4pWmrNK9GKQygvEr04xWfIZAevXojwV8XRO9
B/kbVp48CQMY0BxxIrs5Lsmc8krTZs9v3oiOFk21o2ZRfDBabqZQ9W4crCvKdMjQ
qJU16NlY+1rdGT6+9t8ntcJGygs3+vSAwAwEz/yr+Ds5LUWne1Y/K3G+tmvsYN7S
QwHk0eJkvmPcyd5NK0GEZKohJFchEePeoFyzjKNS/pbwMQQUjJcqvRR4EvpQHEsb
fASZ4HrReZEP8BnL6PFmNelW76I=
=kHbq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '66732cf8-3027-4964-a92a-cc1ff3a1af7b',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//Qzg/gmgtn1x6KWOXIVPTfXHtJeoo9kL2US43JW2naMHH
wPaX7Q7HMx2hs7vjXN+zXydPnk0MT9Bro3+29fr5dHkPtZ+BnIifBhBb1CXwgzZi
XV++wEu4pPLnizLJDVgap4g1p9DDxMCOOQdBk9TwOgQHj9xI7fBQLD5dQATWqtQ2
AVAinsQfYnUwQ2B/GGaKu69UuwtmI6HEy880E4PJkU4iBJLj4LlahLVharsKKbP9
Y9YbGZaBwCIb2+xWyZZ6YpTkFY2FJvv1qint2IEit5/CrviRVgCc5lmhIXgL/I0Y
LB1ND6fQ79njeN4MBNMghrkdootBSEMXBEifCq49HH5qyedCZ4gPg9d9Fox8pEne
75QAVi6Pp3FRK/HvB/0u6CsDC91iQ3w/V/3g5la9kg6/9OyfHTNdxT7jUhYo8aW1
2nbGrgjA3D4NmGi1YEo9m+Ld90faMypP+tgDMeDbNhVPWb1W46tsD34f9GcY/otN
2z5KxhamPcoNcgOUchGOnZISUDdu7fXvlm3VK/hjYudgSeM6Yb+aGizzD4al6AWR
uKi6ngWUnIdZtnJ/+2G1GNo21AcSrVC2T2CXDV7jWJkVgJd4OxqemCWOTY2nr/LA
C200XAVNUVLjjpfcEoQvdsTrFEzZiavKjgD8d+xumzYMoGGxRQ32JVZ2T0aNs2HS
PgH+EJxpZsGCGeziGdATrbSOcvOOWlsbZ2NHx/0gFW8dao5742Kv7Kc0yddTQrjV
ejA4RmOir5JRGAvhIot4
=3feg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '677205c1-2699-4cdc-a101-fdee868372fe',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAu/k3hM3Zejl9Y9q2r7/Ra94V7gdXUi/D1SCOneslPle4
cukW1RAGtfOoT/FXdF35FkrM0+w/SUxQKuX47zz4UE/zLBDCROb+zoacd2DP7q6v
u0Q46sgT3p9O87qMRFecWRh0C41Z7ieKTnX7Jf+x2sbFZ+jH/zDzl8KEnD2bI2i0
Nu7LeOC8ihdwhxqVlM0Qkgt4yuaVKd+EYDysunHHTFL4RchbzLzKsvgxjAimmDNT
HThYc9+Ia3cvHPdJhPjBfawW9m1YiOzpDu8jGrz2tORl068HS2Cu9SSsEAHyRl8k
TlhCPEwbxkoqn8c2fBhh76RxUgbMI0ezdG8QsqLfMqMVDT3PG6c+v27sCRj05uHt
Nm+sxLBHrZ6Ae4YnR7arcd0PD34FV9dH2Itit8NDcVzyCgt4utYpk0FPa4NUsU7U
hawj8fOgZCBXawE8ySOiY0CVSmF9VcN8XKkYDXtLV/wQFVTuZlEVc3aoKGzXL2KR
SYa9xx5Z2yeD2X2D1uA/QlotHWmFWbJKsiZmes4gaTr48/nmMV4p259zuxspmTd9
9eY/NNKh+01Iw0JP7S/6UhtFHSEb7JQotNo/8XWuLLjL8NYNO8BRNqB8Tgy/JIUl
0Rx6E5xEvkJHh5JQow++Nm5OSE7IGxNKAuqUcuiKgLb8mh1UfOU+VVRSSariEOLS
RAHF/Xed9cgV9Il5GrPpD09uASgH67kHZlds2AHdx2vk1h9ZfeF/Zkz6xL7vOyU9
LGLuQymA58gfUZLjqn9kx0CCgXiU
=GGX/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '694be0cd-4f4e-46c6-ac15-5b6604d79a76',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+LHV8UC1tGqv7jIM2Ni++r9sukdxTmiuN5XEz/shIW0Mx
B+pdS1wSRD9j0stQb9mBPOXvmy6efzu7XS7jv5NyITRI6tiVd7eExj4RK4XrW9tS
4JL5Dbr0rnmwJUPMgeYkien1M+gb+ezHiB2E5+nbattukTjO+LEYYHII8YFdrgx9
UcJMIUDa7rUcMxuUFnhNMyE2iJZOX1ZyxKylLVrILeHwfV/tGEVCQ9PDGO1uCZkO
tkOC7BKEGsa3LolFla7bp+QMlvqqTCYwB+B9ule3kd1ut5hBTzTNpqLEEFD9c/lq
eINcNEAdrV7CUcsTd5oTlXRmsBbZwNYc0OZeAqlDaaGNQvzOuCf1/CX0k0PEdmXN
W0Nsk8Ke6v0/ymPLwjWcbKp7h0GDpjP93IxDDY94JIxTlyc+Q3c50lYxc51RZQZN
4bWpWXgbmvC3iRzcThqFbtFE7dKrFUzdRsZ+Vs4LXroVU4KFqQK0dZ54hEe7ckrA
m8XBBy78FeE5VJr9H7NDwpNBqcvMsFlV+OZKp3NRPI8gl8knd4o32L52vUOIFLXX
PgGtUY5SdeS1l2xYUlQjgpC7dPJ/ltKq7IvFsTnFvVn6Gg9qErGaO7zI1RNfds/y
jFp+t/BOa/BGzwipi6R6qJ0AnKsChhwafvlVsrKWXofOfrkSszn6wwh3xLMy32/S
QwGC9BEQDCyYEia23mqG7w3SiybppPQonqyXv6wAXNNuurEcxfw/1GgoRdG3paBQ
f/4lWi/0Nfqd8cTRdoFwAtbp6zE=
=Sw02
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6bac3696-9052-45f0-ab89-492264528dc5',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '408bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9HLmqS++XscrkNFkuT5UpEOrV2MOP7tTAggO3mNnKCK8b
aXrjOToMC5nzdsu24QT7NP5CqrUU6B1HNB0pcfI20zRJwU03IWwkYwUiV864rAH8
ohboB7QUc+bxjdcRm6Musg3QAe1jyDqRjAAUjE5n44skLtfjedJUxCUV42XMUaxk
Ge0LmHkG4s9ysJrfL9Qfcp11Do7xiZVk0Z68NLiRCWna38JuRtFwWwQuGQIkhqNS
IiFNN87UqvnixLO2dt9mPqjft9BfPSsY3XIYUespoCPnutMLtolomnwUfF16gGux
AJ+iEFQHBUZ5bQumhVAQ63swHoSpzMvkW9niWOb7qUHJwD3x3eWWRjTmEZTwEIxj
a3+/yIj4CZ5iimSFOPXr1fvdc4bowEpB8VlrwwRmdcteMkID2s8h06ztdOl9+3Ap
jdpcIGcAcwc+5iCmdIMYVz/F05YsxVZjDz8T6/n2JItJg3wrBMKfDMa2eWL6nlM2
jpFdoTD6pBgO+Yvky+ZdCg2LoWtdVLBTlvrXJGcPCi/8EVnMl/yQLPR7QYlLTQhX
wFQ0BownVnri54IbKLCVlh7JiRD7dclKjcsxiU36HqPSY1YxAZRZvNjbt3CnCK/4
QTWhf8vuuwyFo72LArHt++mLo5dWrLhbLWf96EoMCBLBVoROgih2TnAe8xRv67LS
QQE6HvOJyk6l8niGw+HzHRmsU5L8pAwWeaHtCk2vmfdR8vKP6rwyfzi7NWnuqShe
ZFCWGYGFCLybk/EQaiNbx3OE
=GzCu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6fca18d1-eacf-4914-ac5d-6e4d44dfcb1a',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+JmAnVuPj5Fa/deUUajH8HER6+6trQO6XNvg4RIlJQPZX
iEBZYeYLqd8tDbtbuqVCSBb4u+FldzJXXfd1EjfTHWoz5zTmTVAI2Q818KE60QwL
NIn6v1N3DPhECKqopPmACThWnZnlvkhvk7pf4x7UeamXFsC+oHxj9YdOqt0AEgo2
8DqLaGgPBLh8mqsy+hFVEQwWkFL+lUW/daw+F8rXa0jinOb7qypsKIqoqm6GV+oW
rwLqUDpG5r/3/erLv1LYK6QDH/7E2kmtcacReOZxyjSu4GToVd4QXNdn5FL29Q0l
gnaqBFjrEwW8N8zKVofUIkvts4ljjAqA4q9JI/O9M1lMdu0esX2r5Cy7kcSynztV
jRq4LoG2+2cZ7cNifrA+RG8bhDJGiY1JoemzTZ37YTv6U9oLNc//kBLspeD7ELPU
oAVJ4xMKKp67vw0YPVKL78o7Lszk3FZu6H3vI4IR+StyWm5cbAN3DabqpkbxlnjR
lJo8cn/oTkrArvruTQY0LU3tCXzMFu3e6GkSXXte8JrCqnH0/+hUrb58P6J/Awew
65uRfIVKd2wd/eWRJNWcxxdBV9YTRrnVADB7sINT664jphTZHsR5mbEUmP+EloGm
1Yvz0YGK0991jQvEDFQcBH1rCbTeRJddOKLZJOASmBJPnCQk7y30tuYvXnr84M/S
QwFkgnIdacrjYjTxfOhHNbPWSZevF5Ovceb7XFal7aKoe2GVMC6+5okadLyX/RfW
diwy9v2p8buj+FDCNNTw12vTkZQ=
=TdLp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '70d5cb83-8921-4826-a6ce-2aaed456dd56',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+PvdlSN6foI8LrHE3oRgj3RkgAqolP3S1mzlYMNpgRVz7
bzndELZP1Xsw9oA23FVYu6tOr/XRPBbJEXodRomPrI2Sxa1D9VuIUAsQfUcLuPty
M1Z9ZE+LGdTLJRYkbHL59QjccY8KO9vx7Pyk84nKB8fpdQwvZ1HPdaZjU53GS/eT
cGgRZJphhWtV0trw3Gs57iR8Ek9s4vW9PFIRkO1zwmZ/rwBf83skvGIPBpWb+22p
tphg01aXSl9ae7FOBlljC3RRnlY7rUZC9e7Q24dyltW5NC3Aov7P1Kb8emgjHf//
C15FUtGfkz/oGIIaaBLZZCRTSQMfKLZVOJH5WfgytyN5q61Njd3blg7ub6t7iJwa
S0GNavYVQ9s/211GHFTZvmI7iOzPJflQK7Ny3ZkvFK5iOPvKspy9vlGXEro9kWen
v4P+hC/09HhiveTiR7naxVQmQY2Y86x7lPNbxkoz0sMcsB4ZH0t9oo7iJL0dWJkt
a2y9jDc9lMIkVYtfjXcLledhdrO/KHoufM4UB7fcjiWE6nvURcNY+LQrNMbM9Wcj
UKtVv+T+bsazE+P5hv1y1dnIJefl8PlR8fC0hL5heHuiNOrZr67Q3K5DCB4uGz7w
PlH2EO/ciNCgrI45wpmY/o32Nhl/vVU7IB+t9bPc9aNhR1bbN7M3/fGcpsr9iLjS
QQENb6O5igrNT7R83CL1UJNp8ON0+5RkCeoHcYuIVo+GcRVluAJ3321NWz42Ik0q
yvhnKcLxI4C4W9f9dB8TvO0G
=WI9b
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '756d7c67-dc06-4103-ae75-9fcabdc35842',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//XVTek8dUt/o52N7eRtjMTeTmZgblvt7juGjTY2JjL7nk
JSbKFsonWRe5coywB3UCnkfp03CKdoLW/IIeVFBi9ovl8va7iF0jsUs0CnPHI4M1
vd6tnA1fundoGfUWZ2H3LL5An5VTM+N1t3/muVC1g7DJJ5WAg9E1s/9h8pbdTKNp
WJhj1NOnJ73xb6IDHh1m7cR9KZqcZyzUD2CqBwQzRFftDLWQ99mEbLpKOgv+ZsYx
3sy5Ihojg5lGonC/dxXevntnEJP2sF57MTAx49ACVqMlpYXerQqu2QLzSBYbifIF
QKA32LJjOvlMSyKz8Kv9epPZ8ns1KHCq7fQId9g4EVaP4fTe3fxiDAm8ikoQlxmb
NJje0VCJBmyq71vusNgJqZxHfzUAy53qLl5tpueNUiGaGdWBFTZLO6hhZ6A+qtcR
54421uLFgdYKMgD/6sEeMwJrrWm+jQcPGks6uD8zWtSxIibBgmPHjruCPFMWMPL4
jDBKZrP16ABJkIDXhiZ8jnrsArkJdG1kkZEKtJbR0BGqcFDNJ8gRC4HpeuzHUJ7m
qIuFTHvKZNa7pqhdclf8TEWksLuXfaPcowVxJmG6s7ExcVPjrUYOL0B/A6RZlIfG
SpayV6umVAHeLbeSmarHaiYwaliUKNvBbdJ1zaFu96anHr9En5HF6qJhRuOM0M3S
QAE3V3MP+weXDxBcoID+BGSdvDygLaM12s9t03hopTA6qfxbNY8wK5vJpbLo9K+D
c/5v8MYT0UhJSqlg2roeaIw=
=AgZ7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '822660f5-214a-439e-a92c-fcfd65a856a8',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAkxyt2roDM87eNdj86im5reyCumX5g0yA4352T6VU/TQ/
sY3ryR1CwBeAauZBWuK0Bfe2foy1HNxPXI0XHmiRsA7uGPeHD/ry6+uRRmz1YjIT
jdFSFY06c7sGBD3OHKCgrFwp4gqaLMjTxUZRMI/H0SuqKPOe+f9aEc1T92MU1N6g
3wIJrY9lEZZMZjmAeyENWpPF/9iaygS2c6YTanS0xWQATV8zmqNWq1YZcgFPLSuQ
jQ91V9YAUy+N7Q+buzstxWuGgxAzzIJC5TJl0wvKUQkuc9U3qHnlRtFx/Dm4j3Nu
xx93bo4psvrGeE87g6Q1zEE4ZHUgg3BVV8KWzgygseGm1ed5MDPSW470Px2DFAUC
VZscWRQqQd72ZwmSKwhpNazSTaekFi5UjNUdR60tdPJlc5x3mQB2DFSLdDsIiO5N
aC3zZI60EtqkvBaJgvwQ/OniVnJVGozKFudI55zRdCA31PIzUF2JSBsmfKC7Jfgo
arGrgova7UpAUfKUjCN/CG/TiZGbP2rLO0isZug9nuf+Stxmmaerwykt0t/s9Xj8
COuzfirgkCfsU2P0UUhI0oN4HrxA5Ib0QVF4UA530e9jRXDhhIN4r+nTNaGu411J
WCGC/mTuZJL0O5MErID7BlNORGfWdX2X9BdI4IXLyFVMbJRU64YNVPJXxTJKPdfS
QwHqHE+y/dShuljJOhih++DrXdn9x5VbK+ORO/L0CRI8mMxARsDCCf0MoaAwjiTF
aeYuZ5F8P1me9x3AT/iU8b4UFG4=
=O38z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '88dd3c31-a21c-4750-ae27-e8ecd516dbe6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAmrhiAtUPUMW8xTI4Rz34eXiABLOsKFjqLQzsQRxOxo3k
myslPpwhSE8Jl8stZpKYlhStsfbMRRIc4uUGLBRsVAcW4ti7CDMOJ2Z/vMy/ZEVT
F8dQRhzwryi0uGqjB1Qh/jqW61Lg1fL4WqbTgSV7YTmDJNvq1su4Lv8FnTyMNM1w
e3sILXy65YdsGcnD5HQhMZq3M11ZJ+N7VgXmBLhKps+nfWwADkdJVT/YyZy3NTb4
a18o927P9YA+ArQ97DqFq/NG0S+mrrDGGub0Ygutu1vH/AuNZXhm+2KNmWnPryO6
ZoHNTfH8YU15gAoknyLK5oZRjzi2i/Fuut+fpD2E7w9bR67FGOImUdI0Aua7LMjS
QeXtIJJEDx3QsYTN5T2GQ5c7HKwPHkf6i7NPsjApPr4IP6Ukxkb9zpeJleWfVURE
IqTSEuyGeb8RK6bIb4ZkMjIQ18LQMr4f1OG1+9cGrjpUNAZtEmo+VI2yHRVTGGxu
nVZbIuyViChupHtH3JUspyJjBzK+0PCvRd8nYJxU51oEMHe4xOpV9mT2f8ZkfWzn
anujgD5cAyzf8+wB2UUX/bCSPiCLcUYjBikZQMns1ji4vpXWpub0EYtUN878+0IW
k2P3xNJAZ3d7fB76EWBtnScPoP8IUdOfmh9KeiAnm5PN7uVxFy+kT0FRbvtcdG3S
QAEhsqbcmPKzmpbaiY/2ajdpkpZMwiopatNxCG+zHimUUx7oq9my1eAYczuSAZMn
zAti6jkYq4Yss7jNMR9fQtI=
=dnCO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '90d4092d-03b9-48ca-ac1d-d9e0cfb5b7c8',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//VB0zc06FQ8eTy4X35wpmLx5Tr+r140hF8HEaPHPox4X2
DB35OeK4sbFz7kYe+m3Du6OLjPQgQa8ZJ0vhovia5bUO9VhjPxAj7otTqeji26JC
BcVWEGlFEVGbg9eBnqHnomVNb8cE/tEuPzhsH9OeAynxiJ2nhsmAL6bkjCV1uJ0R
YYBk+fNKniS+/3c3s4aaCqm1eOXogNkmFPmvWbmu2hWwW6oeR8tQxWDxvc5EnfZZ
lEyporXfBFxssFISr/TVZUUiz/kC95xTNSUuOumE8l7KhdRIMv4l+7wTEwAoIA/Q
pZgFYLwhJ5kh7+ogXy8ozOq8lN78Obyw3luFZNB7lY6QUiATWzIsoxjcsyuh9b1b
q43t41NDJMHcm5RXHNcbcslk+eUxStXqOF+RxjDW+lEUn2013nCaL32Qu8AzUXZa
Rk3ROzYBggFXddXwfRVEN5gQc2vgoKbR97n4NSv2FWZqcOAXg8VDLn3Jdw6MgP60
5YofTvjZVcgTyY+VunBvzEfTofiRTkOiBHFyG0Rv/XRlC2H/ja6gtj8gM72z7WYW
nYAk5XXP1xlEoxebp1jmS6yV63FJNZUMMdUtYJ4X+gjd+X9b3L5WU1dloAGClUjW
QXWj5DqhC5DNkQLi52nvwfA2Vdk0kz0AG6dlC03I9Dq6g3upS3o7aiGWCBGh5eXS
QAEsQ59HFhpkcr25La1cTXlRyZfF9EefG33Mj11wiH18UYgnwAyc/o5FOfLct16n
q1lEOGhlbc/swk4mFktKAME=
=+3T3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '92de20cb-efb5-443d-ae44-4a9bc39dc128',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//UUTPMfK4VI3GKpb9lSjMCPjy0wcMtdNimvwYMnj+mbE4
EUPR2V1vkrcIQIRCesAeaitXLJxWjzQUIth/1Zd8DZQ4b1nhI2t3ina6QNgXie56
eRYXXprDhOwMghBZfPsH3BwpN2wUTUBgArPxxcavTRhbvOMKUGoJs6tJ5wiepLk2
67ADOp48TkAYBgY4Uf7G1jyNVTP2VR6g79gOELfA6RK3MiPD34tMDwEbLmkilfhE
vhHr3sH2KgrrhHfEkMbchZU/ituTRHRqHVC56ooKaXzPpKcPyBp+2neWAYP/g0Vj
3RRNydZcWJQsLuOcmewSIz00+lMrCVNSdZMX+e5X2vJwZxchsvyU96r+IEs3yBwd
LnSR6Bo9UDp0iXSG+TIIzDiCtVMecrawBiYTGK5bJybnELS18i2VnosR7Evbms+2
1BYwbmTF9KVJbg3KdUzte2aV7NapkAq354kh9mdRWcZkBQfmE3ynuxsG5hqwpsOs
Zcm3Jsbm2R+LpKKBNCsKcJPCpm6VBzl5cmW13HzVPNHl3crMThTj/vXdrq+WntCp
dMSrtOmXL3z63ADapXuAumWdmUQ3dO/VvWaBN0W01cOV4ivKt4zkZv5Q0vtQ7QqY
4slPdrkoDAowIemFB4UbLSGdWr3qx34P5DPQpFSafMF3o7it5rAgQp82qQUrT6nS
QQFD4oj00ONZbnfb77OdxAppbnm5yMgAUEnFaQn4YDKe/gSUyaw4J5CrbnvqXyAH
ULWmgKnvDHOcELkk9HGrrVPN
=8ZRn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '98c3a79d-f786-459f-a0d3-f30eaf76a8e1',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+PaJHj+r9oAOv7PmwczMGw8xTLRFGX5q1Lo/fXYcuDMRM
WY4boP2BA+4BeUo5WxlysO6kmTf3fPkY6AK+vnUIlAn9yxVrNHmDPySm7qG0t1zi
F+cb5wAOjNGQTlZa9a9GJFLdVTBKkYFHh83xuEbYRfJTYsx52iH5q++ebzSnoh+K
Tpv6ariWd+ydrYFvL+8wN0BRVN6ZwkW41jU0CFZw4Sy9S4jImoCe9/nKFprHL7Ps
dVnCWFA3nNlzCNCFRRuZAOZH+B4SWvTr2qRRfzROWkFIenYkg2Ye1kXhHKYz8CHs
X4YsOV3NMGN/2gvhuSp5YjBn3UAmODWA4FggfQQlYdZtq5qfDyu84WvTlTNUNXrD
llBrZBTxpDZpTBAEsh8weO2vADRZa/ou04V3heCgw9cVHRBG9Ukz2D/xb/h/4iEQ
FQYVvJGkt1wz3okUO4KBPpmTiBff41i7JMmkzQoQygcw5w+QW/RGTLqoi4UsItiB
pukm7RL31nCga6NyLbI3667rID97kvjCqkNYCE4jCbv+iZFLHDwwfaCzxw2BjLWk
vdPWXfeSQeAZY2PtcZpivJVRr/XIx+1PoxLnnEXoKN20SKwqbY0MnqVXZEVXgvgN
jFtqrgtLzASqwk9dlMd+DdgtRep5A8JtadXkBAvbCfCi4ZRKmoY0CqxbmznZxJTS
QwHdIjHxC1u151GrMfEvcdwWIXJGHmRbzL78eNk7NIpXJeISzbeHVYtZgZEYT/yb
3mRpmm4pNE1E5LkkKQkOSCOYYTc=
=CH7z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9b2c475a-6853-47b1-a7ae-2c44a3c113a4',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//UJgS+htKL+mPd41L0vDN0N+kBwhj8N27eODGnStkN41O
R0CKM/K7sAARjDAJHi4VGyFDkfH9JZuTtMy1dRFXqGXghoJ8o6WCeTeHMMS6x+JJ
SYhOQdCs2qZOMwVMZtWmcMG1rVtsqRcap4z+p7hnnGp1ZyQCKZGbLljTDa+5zJwP
o6ftBFfAdAp/zUA0MIdYhumAbgTvw1nmRdPZG8XRfpoZTxRV3yPKq/CVXfWRbXjp
XOpVITjjlcZ0pucr+5r6D8szBQ/gXDrKRdjUzgvrffQURzM831FbTxQdMghVlGVW
VGSMc28oZ/V3BtXsadiLbf0U0XPKOkloNN6hxnMrdmPQputbbqVF9OtypcAK41tN
gn9S2vpEbUQPmhbMjsJl2nM9EwfybzNfBd1zF6dw9DH6WNtls+Nf49ia8aczwxaT
EPqhmcZMBJqmLuyBnGsYrRb5STPfL6CtdLMbcaerLaMl66PPGxYXZ3QjZOUmchCJ
wVuAgAOkne4yerjQfCQbmVGIO2ozdyk7yY54CeVvGlAay5OE+YWtwDD/SGv9VRId
xo4aN+ZqQasbVg79yReXvPqsRXax/JvTYD1b2o6KIty0p5BujRnnrxJjJMCzUnaA
6bixkwkG4vFqdDpXcMl+8cSzYolRHoRywy+n7h/gYnk4rRM+WuyughpB8OInennS
QQEC+wVGCi6sJd5v+T0xmlg/HxUGpD9ZAUbULmtdSu+94ZJjO8pM67dRZ1LQhWZ4
k+ywMVqLRuLn1tPzZuEJX35w
=/ip0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a1a7138d-1655-4cc0-ab59-a953f17b4bf2',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+J2P8k85I4M0hkBTp98UQG+WDRn8OvGbGRGMjbw8anARQ
BU8/Ix/X7rd74INbpq8ElTO1E51XxHdN4XFThkRJZZCobc+G5Q9+em2mmY50HcrF
XKENi3WJHIXXkzoNb8VJ6I4EnINxJt1POOGmCZ8HiFQt/p5DCUS6PxOCixiWPg7m
Cc6ZxbTTytkz/4c/9LpTYhlM4z5axdCYxxkZd/ymxe54VDw6gm8eiEo/U+HE6au/
aU9JXbeKxkWx7ZepYKkLbLhduTbia1h7e3BzIFClQn4ipWpiyP5SmTkRPwhzOkSN
H09+Yw9zvdXr1Wg5z5teWBZQLBoGZAW5GQ6MMgkXCXUvvbMzsxCfx5F+1CV9U5pB
xaFN6CMOh4/eBkwkUjd7BGt4SMx5XDnHwoQcrg+nqWGzI4Nz9CsIFrD85XMAiGev
4V7C47GZ4hLMHD+j42uTAjVEQTJHH2NYyWJjEIrtaYz1YdBO5xDtc9Oh25/zvT3b
UGquC24EpJC8GxP3oK6e4IrabGt0MKoIzJWO52fwvg+urQwRahfWD3JtpmvhW3zn
SacfMSb1EPCNQWT+n5rFSFRABxHw0bBvqjjR0eApJy91CgcF4viyk2/QeJ23Yl7I
0Z/g22LiHcU2P+p7Bphnmnnxyo9dsfryVMIf2L0gFyg2QOTtSClhnAcAJPO+HH7S
QQHCiMbZRZrLecnAAFJ/oN8GRKaF4BnVz/VkQbTSCiehgEAV9+0WtrLFvSmHESb0
bZBnxeAz6jmavZBjZlAQZeWb
=pBKW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a3df4a7d-5205-4a26-ac49-31ae5dca7f64',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+N1Mn83VXtCU/okT1jYHkQAj71CbxuOtBWOxK+LWEK7xN
MBSZfPjZMwbVV9VSXsnw8CIoJX0JDl2/SI7xt8ZAVp1aCYxflFkNtEzrVYm2Vn+3
8iMJ1JEgO8kYzfHwaTb/bl9sxDxVv7YHpWTvWYo4jZy5QaA3DYG+mg4gWEAhYuiy
wnQ1Pi1ww0buSYi350WMXIA/hZRrDGv7NbtuRYX9Ye7QZw/8mvTuJLn0nSoH1zAq
5bcydaE4g6kaDFIB+8WS+5XGnHLey1sBgaL7u9kgaB5VhkXeNbJb1EVY1z6cCpbP
0U3eek4Emii8Rks7kpaV7PEJu7/fVQ8QJjhWvu3jm14AiydfK11Ev/Q9kHTK2Ki6
t/pJ6hGSynPn5/SSDdtIs+kGJt92GhL2ENRXNBxEaGugQ3NQRT0jx9HbkyTfNVLZ
JXb3Ucd66WRVoO9DEqx38XuA8QqYix69YmhnwJCI6E/YIdLRZSGo/JZIHk4SGdDJ
jXRmypr8SDJ/FSBHD9IJw3JNYNj5iHage1yC6jXdJ7CBmae2RWTTF6hZxXtiqtUR
o5LaXS1ux2QMIl8dbXo9wW1D+dpKR5xU1rengo+yMI6C7OFYwTYtNX8l9Nx3FmBm
soX11gszL6ysoIVKo6PtA2KrEDC4z031Kc0PTSxukyPHz6yFo7WkorWfx73YfVrS
PgG6Em3Ji80elbitRFEGiNehslRcYTayiKLPufUWQ9kS6TfeyCRPJz4yFhtvwoPm
6ftUeSyeM9OjfNPeJ3tO
=/yBV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ac9a8bd0-fa3c-43cd-a605-4912d81196e3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAuwBzD0HMZmT6NaZ4oR1CEMuS/vZPYbV/19mnOOjRZ+Yx
DX0sSHqVEClvLygzIyIp8hrXol7mGcThsIYbN/IDW5LXXZwgEAtyEhANrvj/mhDB
7Rt41PzC1PzvcK789uV/0ygYH+48DAp+RzOj6vzgoHAHmMd1gGLF8xztTLPcmIXF
5cBihQ9teqPaS18AI8xtw7BrmDoFAw+6OreFK2uI6MCFNAC7CPF2PWVzjSS5xeja
PrGoAM+uu0D05tZqebWb+6JdJYFp3jOLqP+uGVWdPjMn49y7bZECgRznRJ7TvHkl
h73FqmeuKM5++egD1kgjC/mgQpuoE3L8qKj1lpLHKARVdpSZ6FxE4cb1v2xMRuWu
i69dMjDwhLR8GsIXiX3/HvyBZjS3MEGR3513HtYVNYOmyodQQBT2/7+UTqqAOLQZ
O2vGWYftroJfW/A1gFb90uiq7cVWld9bK6OLz5zN/6uXz8iOzsQp+fZK1IpS1H9z
J/PXiHrZnhGL4ob75scLBKlv+l+6N2fHhq0uBdrLwVcn47MnRS3a043wKgHg2kVH
yfoCVfonq3oHJi6a++hmhwlzkx84sRa/l6Zda5aQMovuY2CZzmWD4WKeOJ3lXT2J
8ir5GsashDY0a0UCr1bupR30AT9EFMm3CdCzPqYsxvSoJvGow19Mo+Sa2kGd7trS
QwEQe2euTEo8WoQeIUr068LvWqnMK1JoCd7iuRo6mzUiOUDKWxWgqwJUQv4ezA2U
B7+KdaK/lR7ASkd7N7wO6cM+dw4=
=A1Gd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'acf6adaa-17ed-4b40-af8a-90c73fcb7880',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAwJ3XvPNcHWkub7b7CjcXDy/i1waaTeDKfcHUhFrb9fWa
XnIxuoOhrGd7CtdLOQru3es0rMTyAzfDGLig7NrBMaMx/3hSANDbeesOMLWmjTU+
A+Ttg/+5umpuI4tzVyaXM3Jqbd9wORrMugqZ3KpqKfnf3ADC0wy+TCg5lCvfgJBX
x235XhPwO3T9SF3rrerJia150Wa9c8WXYZbB9vefR04OutNbf7p2nU0choMexE3g
/2lLhKPQ/jNcZI5Act6f6QMsTMkpXZEFXTq8tyKkzzKKzHOZKyMy+wiMIuHqyOP7
D7R6sDWmatF8PZbMwSIaZ3y947VeHLZT4rA+RRN3fdyqpLB13r7pfVquHPiAKOAi
w8vpzWrNqQRnfiyJFPVdViLdQcr1bswGPtoBfTXhdycVGsakccP5uf41IJAMmkUV
apSGqnbbz62/Ed0ciWoiFSejTlQtl+k1UEbmv8npffkCPELmWxP3h5jCOlSO1Oxq
VkGyB749bDgNg01kwhTwFh3wSjJ7Mz1u/Y6dN7wFvZZ6zi1Yw+AyA5U9FPFuYk0Q
VZcVPEr/iWUVxw3grqwO3NyiZM8lVRBX+s/NyHLQ2YSAoAHIuLNAx7F+FCOF+aaR
nRyDRzLx1tjTFfsVeQ56makXLbtsnoiVbm+7VE0Q0ScueAaDNmIGELzOIuTJb0DS
QQFd3FxXwPAS0e3SROC8esOaEPgx7Uvq7VDfm5laEIsuFxy9XJ8EvmPPZX2l7SmA
QJjD8iJSG29wLAQeVt32/NC+
=4blL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aec9105b-b79b-4f41-ae7f-53fcd2504bf0',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+Kd6J5Iay4dQXx+TV6vY63sNSoVmSRbFZMrm9pOrIN0zQ
nKL/haZOxkut6sPOby/GqLw3ZGZUWf8Ba6QaWJuePWvNorn9Eq0r+Enpt4Ze7bPn
fXSuLUp1WO37eOeHfI3ZDD7VH/JDiwjNO1fYTgj2n5MM2LWxihWkgHeWs7sRw1LB
Ea11NIQNNHpPTj4ykuvD1t0aEyVTkndhO1PGk5Rh9kPBGE15txwBig8ePBh/YKmI
yq/ckng1SyW+RhGs4yaodAvhu8dKhA+87q/LSMKULHhNdieb7DnwuywWEPvEqY7i
ifShPX9gW8zKTwfYQmHucMB2VMXZvDfh2g2oJP+AZONhWzlNUURiye0U+v2df2Ji
8zED4QpQjgFD2/AAU6NaqPKnGwhuSRkn5BQsO9TAiHAfTMsfhBQImO7Ypb5Fpv4R
WmKYan9FgcmQrcWClY5YJTPQW8qqhV6QKiNqB2xQuG0Q/FxCYjIlKpgzYHbXHBjY
jQGHKEEb/2S3EsGcPjc13LbeBp73MjalALptewHQulLvtt9Ei1ydaxINxhOWWSjU
/ONizeR2KT5Kz8Ep4mYDua+ZbJ3hK0AVE4LVOFP8q7PHTGLToOcipdWxpu5X0MVl
ecrRHNAKPLStOHgYTPwgitZhtubAcmptQvJYpWEMdW75JXSYe063cMZAm8R/aJDS
RAGTrR/7wzx4B6M+ATIsH5uCYEJcnOYcmYvekQA2xg8YwN0GgNjeMvMkJxmPh3Hn
IPMBLEY4VqIH9hcxl7obobv+Zh1y
=GwH3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c59e65a7-9fa0-4b7c-a489-78b29c4f87d5',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAh75BF3XTkdBh3G8F9QbH8OkQJtZCan2tOYbrNK8xWVOC
2fwrBikQtN0H+D3lP1DfAvPOP2b7Xk+2a74d7U4n3nMjJcjFScRDh44N/Xmpy6/K
EMh3C3fslx11h/z0EN9wihYctlWSHcXzSN0Npo24ySMPdCC+fuM1Fcn/P/FH96R6
K+fGeBqMEwyrVdwSimcGO10brQVh8Z9URAMbRUGgv2GbOjCuUA3FVRQ9TSEaAWDs
fgpfGcttX0ggFLf5XCNCENWY3VNB72Hx4jwBcRFbqz26LCchzKbz6e9Du+0tzFol
xq5Knp9ujJpmDPT8Mi1COnwXIF0tywuXwg9hXAbCS/GpMiDSA+VgPBrti7s6H4BC
vMVDwNydAtTT88QiAEkMsplyMjSZk4FtL+7d59Otne87bKYU/RH3ZGblaN5ipVsN
Dut0hc9rZcd37SLD1P6YfpAM+ehXlsCRvpSf3tnKYkO36yblweIyqVCDLODMV0im
fOWtfZBHRy5dLtT1PbvT3jtCfHdWOwXnByIHC6WguUcmWOD9IEYemVu/fFTkB361
Y5JAgDB6NZXjFPoXKXQuiIytYauQIhkx0Ht4q4loHZee/gPHZBzrZ/wl1MTvDx2S
QeUsRHJa9Mlb5IgBEPGdAbap2Dn0m/if2hp6MwQNZ3Z/ER40cORBjCi5hfyR0PDS
QwEPFzdGf1Di56wG568CTHsGpuO5tAEXySwNj/on7lOhlFjetUgJCmWoUAF61Por
0SjT38RPSIBcrItEHX7x56qBzMQ=
=8kzy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c723bfcb-8442-4c9b-a84b-4663a96ead4e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XYznEwpo3jZBGYOvtu+EGtHc+b/CwAKY1KSDTMURBByY
ujgt7Ufj1to/258uFRv3bJscelTP2yS0UhDn5OdIUVbMPiQOrteTTQLt1GcMcj/6
5orhjhJmSFASPm9Vk320B+/crvV0Ma7/GVhl6UnA06D13QZbB+uapXzB54EhP7mr
umyq160bBsRmHYGo8Veeic2kMeAc9rK8uwmcGXFVZ4DyqksKbZmscItxtl6CC3O0
ZuNy2H6OQIPoW99huegqLcvP42rc2sD7mUfGsSYUrIYYcgZi2MDL/gGBm5+pn2RV
CQXE6bxbi44CwKxYmhjuU7AWg2X3bQHh/r1afu8/ew4rtNNfyeA1w/YLkQUZAwpK
Tb/4XACcLOfNkGYxYjfxTple7or08FTxBcwxt4C3eL2JH9t/8fhyAumAhCthPrga
QzWMstIoCHTldfQ3L1HAzlouf1OlkZgy8/K5wafM1uyNKgBTwrfv8FslncMB5afS
PvNxNe4A2ic/ecofxwBHhBE533oDLDayl2KZxbbbxAovxkUKmf5lef9f1gppfco0
KMul7dd/IEtov6k/HlmqOr+AStejOs8oj62YoVstQLtPkLZlkRb/ar255TUcGV4d
jlhCb28AnTM8FKj1yxPylYaM8Q0RMnS+fqnvmPZFw+Uy6Pt+wodxjUAKt8IgiP/S
QwG4fhN8TPw/jyUsT8GKRhRjJoc6EjEASv5FjGspbX9JTriR5HXV3s8kgZ0dS9ux
vCTEVKOzi5Y7qi1vjF5UlGryGTc=
=m3A6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cfd285de-2eb7-4100-aa13-d0adfea4b4ff',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9GHS/1j4xF4wpbm5excTlAsabrEsRS2TPP7XEu30+S18c
Z5IdPOoWsGgG2VSbECkIQffTpZFhWUblwi+Re9e6McqYXCJkJVMId/xh5P6dbE7Q
LfieQ2FUUrVWGFx5OVTL5ASdYh+8TxZpeTQOpd5Uel1Y4fiSNrS6gRCGGyrNDWSP
YkLs8GkTjs996rvmVHnEb4ut9SW3LNtf7D/gwgu99qCxS7QMYbFd30O/iRU+TNfa
3Nkoahzceuc/cqiTqoztd9tZJJymAJMGLmaa2NpvVf28eJr3MJ7yHOxJB816ruYd
wSjjoIh5847URWofYYWCWF1VVWTh5nhT0V2zdYZx4pOBDmLKXeSNM/1CagQIDo1d
tk3mlk4PExBUdaXHrbThqTTq9rJBk2ffZaIoAVRs7RdpUpGJSGeM/36EhmWT4Gd2
WxNayRB7vydoEA4SoqIX9Zic8mXnYhT3HDL2nkU1KF2w+/ai458P5FWembFuFLfB
xwbHv9IDKnaRLa4qNiL3gKH5E6QXpjAnMqiBEGV67zmKnPfZJBHNQJv61LmnkQhB
2tQ5+YsTZBJNvTXpgauYvWe5w5q/QpUbUMcEK3pMnG6GktbWX8t32QKMILhuDO4C
cUpm3tGP/W7VEB4anltq55Q6X/opog89WNsJCkERnF/d4/dt+OEVuC3q2LVrmHLS
RAEgsjspBSFqGgH2clukkf1micMKy37mFOUcsZS4F1iN1yTWPmgtqY0ai4CUN/zY
rMGVpGayqTJjXP2QJYlSrUU/SpKt
=wVtz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd2a62c97-a180-4a4a-a1b0-ff7037d312d5',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9FMqSEo3kniSrcPYSlkL07Ptl3pcC5lHJhvbsEYhSd5HC
Sddhg7KHBmi7ydLial8L75Z4hhjFuNbqXpy03wVeAc7i7E2zg8qMsIW/qMx6WbFb
xZkJo8EZGKv+wbupivdsXt+dDLzSHZzHhEumWvc+pxZMplHrdz9gaDLZfitEAU8l
1cbZ59mSi3J7VaylXHCqDjSOa0l9znFLXnKHYhJdH2BXFlWX0kcxuiGO293rS/Mk
Pk5wl8icKQB2zRUekcoXqMyu+BxWkedprWrWv8X2TRmrRxr4anP8oQ7RwOMtgqNJ
12cCjgHlpKvuQ0ocRx4rAmNlDj35LXtune2mishThEy5kvSIrFvVvx8LchPLrCm5
eg5S+u2jyUI8shjzf7KHsEPpon+Zu8vFkBQGhTLqVp+jjtcwxhkKedMU6HQ/gBmF
faUptqnf1tLvDoJQ+2c8SiyVxQdwXKsg7L6ba2loxTTEWudpKXtncHOQwICx8eSA
prn+YghtKtQtTfPkhaFXOpp0gA58iSdhcrlRZ3Ix1LWjk42WHNpnr2ZP0a1+dqNB
zr6wBeLdOF1a7QB/Gqm9E70Wi/8PeNMfrY/Hi9GG6HiF/9jgmsBC6W1AqSXwXfZC
ZcPlf/40a/ehabH9cAPQDxuPY/SHLlyBzUBgZvnCIER//3Sq0WhFpX5+Wv7BUmXS
QQGFc3YN2Z1sIVwIszDDccvfBVg0MeXiPImiFDd2GW/iMDnwLCpG/0bBnlDptd+/
c/B7DSUq+jV+0gYyvxXkgaxx
=fl8I
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd54f7556-3525-441d-a1bb-1973f8a38a5e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//QTbltSIJuCtElsHmz+0tXDbIvzjZ6LBTKYSqyg2/eCIm
SQfcf+qm4OcwpgFTZfjX+MSKaFFyu210hBkCJK+u75FC8ox0XGvzjFdN+k8epNM6
0T41BAxhotrfVD406VdJ44zI9TyzuR+L8gdwOTxyED4Geu+LV4SBc0Sl0Av5AA3O
zcXASBnu3L8uDhbP4yo2n6ZD9BD5S1UjJzxBdarQcSUHH8PYDOLBm4xjcvPR4OYp
91Wd2lSQex2pOmxVmuRVmE8CnkiGqJf0PMI+CBP2IntjqVtpxyKxlraTnkl/6DWi
+6rDQ0F8l2IFIFYgKna/aQTwGx1d1aS2HZ093neCkQjR82SL+Ulg9ITLvOifxcvd
gX44nFIc5iPcVyy2Zqpye30xTtevjXANJqu+m84vnUZRkqD6AVOuKaz1B8IBH/QK
e7QIBVwBoUP2neIAVSmyXjAzgPPojF1Qhg1QfEqA6H0swt6GY5qHvPMK+hY/q7Ul
ZvgCUAP6MhjFm0uVLNRkuOAbOYlUOBB04lBRCKPFXjUGnhvgZpeNNlVKQ7rClahK
JmiBJkpVi2QIHOmi3+8hYfu8hJK5Y3blaA85zYDoucbo7YLfXcenhQYDlp7sFjJO
mtmiN7rsjkufd4ygFNxdZi3H9TzHHo3rljeFY7q0igDKqjgqjTkz2zQt2E3OLfLS
QQENoQuaEo2sM4PvUjdjJJdSiQg8bihV0vXhzDrkmpCndQ9AdJEe4OaRbOqg6kP+
4dEsVL5YL7SSFtEp/EEgpnQA
=R+Uo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dc46b704-9ce5-46b2-abd5-feb0c488ece2',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAjNwnCMolVpFA0gBw2HfPPhFUkVLZzC0BYCDMsUUdPN3d
zZqJnrvh4KLmED5T+yB2cnR8G5Wi78u0omBxclYmBmQXsTstR9cgNTNJE/mM5H1+
yA4BTeuEHwI9rP5/674MFTrskJtN4VmYRqWvzNipLJCk7RwWoX57FPYyAKGHBjQv
2QdbnJZtOTvM1uMOP5haxlhjDG/mfORThCbVDHpUZEOZa6yXjyzgj0ZeMoolLKBU
t3n+pPXZnTlIN6VUFa2TchPLGDeml+PRssH/XEoYg+md+uxyQu9ib3T6OYGx2aUl
bn6DTMNJYXF161h5njS7AuhqW91kJ1x+Dknm1rp8FbFWRnCxydA9PFxKtbw5+Ho0
zLYEz9FNGG/zl70hQqC72hOyKit1YwTe4sofKUZjZeZDJKw8W9xRInQ5cpJh8TVu
i0nHs2nY0dD3HNFxYEGnvYV+Rqsp6Y8z/r0djRd243Bo74FvYBAT5jYTR4Mm2FSh
in1psHsfsBv5ZvYgUWqHkpxDlRUJYf5lZYCGjofrqVUfQ9tr1+rWb/6Ue61l3Ixh
yrhP91saVESJorxzB0GfgX/h/gY4m9Xd3KW5Saj7omJ+4aEd+sMVTqJikboEobT/
fEGrF2G4sFh56hdtbhsZHxUSDbUczLUH6fIKUD1UCfYDurPoLpSFY6DI897MCRnS
QAGe1MtrjqHgyup74BHKXmlFo5iWUWnOL2IosUwQDMiwYXMe2Z3JgEzUXIZHoSQj
a5Jaz7JFl+kV0pKFqxos4M8=
=TSPb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e0171e4f-4ad5-4669-a904-15a1a9ff1b17',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAtXd35yFZON4HmJxik5g3irGot4i8UwbOQgael6p3v2Ad
v+O5ab3tCieRRihCd67MHWczACm9ks7m/xqs/Aqfa704ohSmMc7gQ9q/f/NvS0mn
uO5DZD0ocDEIlzg2RHnG528eD0t8yBF4kqf8kClGECF04vIDTCQxlWvJZkPfSzK4
Hr4NIhzJrrbtky4kZcqW1IIeGFrZ0hwrbadP2PGILc+1Ush8VqO1Vbwy+Rchsw3Q
poMeO0N/dBoIlpZf/+wUJh1j+xiMgeD01Am2wVjjVyWAqJg8EZADV52wM6h9OKdA
9lB8vX6pxamrLAmaIeJDDgVfTFfF4jGiK2dYt+GHmVg72gSkFOZd0JMqyPLM2GBi
edFd/L+t/LOfzH6n4SfdTP5ZVbH+hCtkCFZk6CNZLBu+u8tyiRCt5mTtRSOUlDFx
DAxRroAhBL4j+cRSlmXyXMDvfpf3baCWC3F2Frrn/ZdNINH6t1Z3TxRbGU+d7MDB
yTaBhUkjwQ81urIzYTl6QgpjlM9o8InBx4oFUVEgfdXjhMWAekQ3LwKN3B1Sxx26
e2ImgvnO/CS/0GbjS+h/IetOByCV6JL5k8cTY8QN2vRZMpyRXa2PjYQM3JgLLN1h
6Q9FxNULpGq6dOIzjCp4WJ2YSyzbyZ6V7U3QMSa8emrtMhZyGJcCIGJIGv3BmI/S
PgFxYDz5CSTDZhs2HrCe09QBCWqKqVlzjiZ1x05XKPjgMDUTL9I0CJBoVlMZnbzF
12LmDRRXXcO/j4WyKd9t
=6Cjs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e0cf6993-4934-431f-a152-b31246a1a917',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9EsoO2jiDBvgvNsuerE0lgWMf+7JaxSl3ss+C7HqHV7TU
gl20Y7gDUW96nk3KXoF5+2SrsEQme3CjSxQRZJkd9dXuHBjHxrGsdFiDGEGNHyTp
CkDbuMKOfvTmH0S1NJ3xkVsi9TCKsQ801EPEWkxrt3Pkw94MuCzYsqUqmmnlFKvk
0tJw5ICiJzReZjuGb8U3EEA+F/FcPEl37OfKWBpY7qkh4uIU0MxcmC97wOKkwtCe
iRqRVf/Fp8SAuMAOP0sy/eGZ/bxBmzgc/IT4LUye+Iw1P3g8eMH3h6RZkQ3MBndb
bv/BoyT9SazTtydJ1jrEPeMbAXfSYsw5X4oi4c7Elnxla8uI+JJy9obAb2NiCMmJ
Z1zqC4U6ZanbplEdjSVUsRzg1LHl0Vo1sDChSNb58If+cXZwdk0klhwXMw5SnYXT
gy0pGnJGs/f2cUGCpXXNsSUdyFATrFcP+HHTEUaPAvFNJyNwMyJhd+rRFCoXMs7N
+0pj3Yg91dOwdO26tVij+yGfm2+PvwkxFCpareKrlHzRoq/IwhbwK/H/KTHidLdb
RWhbTdAuOmNUqYouNKLsghHRW1dZm7YHFuEvxgAZwlYoa4hHBtpih6JpiMvC4ELH
W9XF3XaOPpolV+9y1st+kxQ/8VLd/OjFXPBy88lBJAMO8PRnyBglMQ9GF9nmrH7S
QwGvjvtHrmkwtRKraCb06+VwSHl0R5KBh9XQubp7pPD29vchsXPSA7mK+IEVCUiD
8TpEyD009wN6zqrXfuYXg/mwmok=
=z1Sh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ec9fde1c-2eaa-4828-a898-a4c708919e26',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAgvsJPpl/8JFtCIXxMUbiGckRvwtWpG1HmsvfeYcWUc1V
DMAYcz1e5evdCeEJ1+i9bl3AXU6y2VSlq1+7wXRme9T44qrg83Y8Phrlu6C09DeW
HTc6g1YYSQduSwyPNe0tkAdiptjbHpJ8jcDMY1Bt2yrw2wlLsv63jv/tlbyyWtem
l1O0J2LLS1I2k1fwuyuJ48yo0ogtkAQYEaqlo4Qf1AoF8swsUad9FwBx2GTHejQ8
s0KsO7LHYqEyaRdcxIbqSYRtkVyYOnDOJ3OAboaVrbN9DEsggv6vWKlDew2sCPhn
4GC74pjZu33Z1hWFxK1B/Tkl/5hBKHx/AmLIiqoUR9si5DnbfH6ja6T8Sv4LPUhg
Pi+kl0ZSCPWeoKF5v0ibb20JpZMRhHMmULEFyquclSYJAVeo4TIqVUW6VZlcgwd9
c5BwZb3/mA+p+g0bJNLYhFloafMlSMB2s/WHdr0Y00IwkkenPBNf43O+8mZJ4gwM
/RFXDudsW7L+dZ8BU74QkVh6nUOihcus6LKfwR3XhKQ/H7hJdBf6rXUyi6E5O036
j/NXSgUy0w+3a9/wKqegylsiCCPFAd3YqbBGOmz2Gg1Kf6flg1PCDETS4NUnzF7e
+ghYeKVA49nm9dzJaqMBwpzoRgQWTOL2QlhvysgTQGOvSu1q718pErS39f705O/S
PgHFEP91NjO0rG6I1leTygtnvfvcyD9kINFSpxgTgw61wuF0cdO0zghfVn8oMDAa
WbCzyieD/6aO3WW2bg9p
=a3U9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f805146e-f803-4402-ab9e-64e3f3543231',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+PUPyPLMlKupUV4z7KsRxcvfUE0mM9qFx6WGq0ODAcXAg
PP9XZSslGBrYmRIMxauypvLjkuPZdDftGbULbV/x/zwZD2zCQ34kQHG9/UTQrNji
MYD1mzlMl2oKhTBPHJQFdOTNdOU4eXweX+l0sf77W+kcPKDH5vcuMCkmQHPxnho9
COZumxok/MA6tKq28zOTVLRxBiEJbEQnpjO8K1/9MGR5GD9RYaEUTYvoaQ/cnhez
yi8l+AYTAKfOCkaYbx5dxNltKk0ltAY2PEhvrjxYZgjzkOl5WVIV2ONoynnM10gx
JvRld8BdmrCyx2lV+pNOqT7XenngyvDoQRXnpPGbYtPVTFiXOJRbsNXHmj2brwGj
ecd/CmPkG73A0x9YCDmcA62zWShx678Gz4VFkjPz0BXYCnHLHlOpjFp/7PN6eZTK
QbVZqcITaahRWL6ew6VEnsNcO0McO7L/NVBoQG7ZUiPMgzEiA4PdPVszbe5rgXH1
QweiHxQf+WlEc+ns2wG+6MnmdeKOio4FDr2RdhBjhG+Bz8lapHk99k71vgK9EKXM
SqCcePH68rpHnhSRLwy8UxcU6IRskiOI7JQyfPElZoLi7fvzM2Lbobtt3bG6UypD
FpP9SCXZV1okVYJUE11tcsLXGv5OyZ2kONOOcPIdccF+Xt4ZzwVq6gsV8sZ+r77S
QwGOqmt4cq7tVgLlI20yeXRmdaP845jav2L+EENnZJqZh02x6Ok0gARqfvQXeP2A
B/iU7HuKzMn2dQG+GQYIT5rOTIU=
=xzpU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ffcdd380-47a2-4f93-a077-b8ca7e04a67d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9E1w6Ji9b/uLUu2b1qc63Cd1+BBahW/EAPstzkfy2kJxI
doQkirl0t+0Vod1lHG6QJmRQ9K6k85xJJwAxZmJ/QvKRc3H3vJNmFwqNqQeHNVHD
CbIFh3WLmbkBTqj5RkR2IDEIOTdbdkC7rPNguN2qE6VOykrKPMrknkKp7+GXRJ0A
GjCnjacY8XkU2icRFAl0vF+Pm8Mw9ZBJvk9I6vEEhi4iMulV0987nprA47xtp6J1
sQ/mqFI0JIJLBAFqPidSP/Nzt2GYA8WIv1/W+UTTZcKh+7R7Ue/iluFl1/5Ytaax
IYWBzuKQEjXELKYHAuPvXt9u+/+lNQOibBrTNHwPF1AlmwTedNHhobqJ3S3I+Pti
wUf0ecwuaPWFtQIZOP51vU5UUvYePhLgdS+oh3lKXn1ATdKvPR93kWl8J2p9xdSg
ntfxAFC7exzeFgfOcjY2qyNek317Jvi7PtqUlkRI+0CSXn3jQEoGjl5zYX4qrAUW
SqVrGooTX45Uu/jvWYzxHlVNW8ubq8frbrd1mMbQ50Fd5mZqr3pddkvUKzbZ0YHX
r13DECfp764kkNCn8eVq/1IE7hSMmibhjxo+bpbRKlq6gDmto0CvRtNtjXpJrRLE
bwrjI1i8lHbbDolS2diq7tLqT6dbkDtNNsgxcNYy/Icro47m9urH+EJDtCmYfqnS
QAFJbINVbADH93CI0hks6hrZ7VfOD+0xPqWSh15ysgyWCIA65l1IdMoAGXpYZqNW
ukIAAHLQtlD331MrrKfYn6Y=
=08xW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
