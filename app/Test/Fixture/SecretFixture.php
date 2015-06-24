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
			'id' => '558ab371-7aec-4e94-b25f-2146dbeb2d5e',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '408bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf8CtaN5tsta7B5jVll/Yo3KZFPLAA80rPGZmU3OQvFhAJK
uFkO7z46huLN8QaXA319T9QzKyGpjgIQqVo6zsdugaaK/7hERu/2uOdV0phgiNub
XvL+g+9jjc42EM6HRSc0yg/YzuFyhrAkx7Atg9K5Df4EyHrLIjehICuz5DcOJlhM
BwDMMrNEoDAvEgflPUwxlri8/sh8qmpyoLWgsVqow03VJQxkYtrcKn2wex6V7yT4
6APRNKqeXwy/gvX8E7tTmdSCxMi1eNO7wdWJoMwV/ImQ+M0WBD40SOcPshpnB7qk
ZA9pg+6xXsfQvJq6UgwigA79b/QPxbksZo2n4TuTKdJFAQvJv/o7GZKwyNSROHO5
ZYp/h7SDOOD90JCi9KmLO1uPwY0P7iFcsq6yt9HyEN2sGu6ecj0Rgsi+lRFCGk4q
lk7NWrPr
=Pxfj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-090c-4f37-8038-2146dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA9QpT7WOXdtjAQf6AnGZ6ubvGhtq62Kk+QSpOTNQwAnE8Xi8ig5qjhc/ff0w
rp38R00QGkErw+JkfeXW0y23xO1Aj3ZyBBPO9ujp6a7kJq/7gqzz+8MhBwUWjR6/
LVxOYCgXCKIahlulgkJoYWqKs5dMHyP7QRL5JtFjl0jpZq/hSIVyQzYJ3vPOx8Fw
epkaQ6S0qdcnAdkycbndYXq2L8vPektmJ9JXnOLQVM9qx631nLDGCSX3KyH2vTKZ
EkrGU9jOHWm5K9JbwKl3NANQjGces6ntOcCTTINZhYYCuyZMl2HORsUOByImSieV
CgX3Cl8Leoss0AHQS+e2SnTDg0oZDn19PY1gY9OHFdJIAfAIPYdhDqexRRmXeFCC
wVMFZGOn9jqtdvSsWtRVygr6RRaQXsRBWYSJxuqwcGeskuOsW8Sxog68aymCJlib
jEFgFK85b0r9
=eaQR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-1a40-4f34-b4ce-2146dbeb2d5e',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAgbsxgnPSumaSnHSJZEveEeWYiaPYlVmuticEwbdNHyBp
J/EHCBzV0lHnOvEw/QFKL29kjIs2jGvFh0R+/Sce3h6UTm0CP8b+krAxjr36A/hE
CwFGh6U3qAgSeHPTBDwdUlpXtbw80SwuJGAfpEorL+GVXB5Hi8wxLwWvYIxs36S2
AMUnvJuLphPwSho/w5nsihnrIQTHL3z9kgl7Gopi8CCShj2Dh/7s7DLdlJjLBHnT
LoOMqOy4eetpnClXPGfjoY31DPWcox3RQ/7bc5oaMJQg0HiFjhbil2rummUD3HcF
iaNZOkSnSb57MDB/vJ672ig9f4LuNftaueKLAaA1+NJBAah0LvlekXuSzXXjfIJ1
upgsd3OPm9ft+rxr05Yg/fUicCAN5BpFQTkhy0S3XumrgqYDK7mTTWB4R/nbd6MT
4g0=
=4G5u
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-22b4-4e34-92c8-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf/R1FKHsmB+6mqlyDU+k9s/4JsSEBXdfTwVCzMsb9znT2R
pL5olnqF1S0zqMEvLDRyk2VBsVws3+hFAtfUGC7HuP/h55aMe1HWOWSjbQSzQS/u
lQEJLp+rWmTTMXMtzwghXHnPctSCvWSFAGIusm/LOws4Au3pRz/Wkg+NdVf1G1LG
Dim5CsJTXSmPsj/6DmBG0Ouuz7Vbjux1gdys0TF7M/TJP+7l/m9tVyDnNrvNe8BY
PB/nP5quMZH5gih4a65P1/T9gozVbcjERdw4e8vRJBBUoDTM1t8EGotLfrR6aD/v
e1jYW7iHbuaFUpPkr8L7uOAja+9dFzUIjqRrktAGodJEAcJpI0pMpaMg3ZC7xJ4I
VBTh0iGngUMtDzvDcAHmzoASHV7uaSpPqIll9ynkER7c13RD2g/EcBKGwbc4FSTn
3I19hx0=
=gREI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-34b8-4d2f-8552-2146dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA9QpT7WOXdtjAQf/UseLYLIhT6eyHDgtL8d4IKAMtDXzLL7O/2Trn6+EDkBh
+cWRkmkpB8224QvKgCxC3ysPkX8P/jFpHOc+m2w/lBuL5fcUoSKdrQ+XivnS2z7O
BL2w7G5PvKxABwUYRZVVIIQVj9p4RC8vPOyboBnvdQ97C/Udkq5i8K1opehiaHUg
jgwOmauiySmP+M7+ACtMFnL2jGGbQYeEU0uZUbuXAJhdHTOahzxRQ+YOMvWW6CLm
1p0qgAJfw0Hqx6L2ysmN0GkDJHfZ1F5Aydx82b9jhaXRcSenqpuhemtKK1piPrTw
TudY1dQjoeJfrA1geF7aXfju4O7eGHSNvYaR2kF8VdJHAXSsnSZaCN83OZyKm4oj
qnJRxFhBH+XDvEQfpcQ4s/bUu3sMcdPecccM5Z4wU4UmdfGqFS0KTOGON15eg7+y
MPxScBsGPoY=
=PxAb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-3e84-4606-98d4-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQgAuOnIG3Cb/CEPWP2/giFTIOkgnwBo+nwg5DwIZBQEnalt
4iRzPQbThmSuHDKELqglHugnRTNvrvO5ZsDGmtvp+JLoGxBxbCn83AkvxInnYJDR
diAOgEmkfo3M6s5ESXll/npa4UQv8YnKHIPF+Bxel/d6+KPqB5JZ/tw2U0KK+Ix4
h6HzaH+gPdJa1s8+E2F3ByT97WiGkLjyiEq6LW7HnKcB9uMxTkd6F1MukvpePjyj
6t/nBfB9IBS4AMGgiOzGjdCPSFXgty6lCy/c99QcK8Ts6eGaMoJoJY3fkYilpWcs
CNR9gAi1zcDpMhM49dB2sD78gmJTdc+af1sCnIh3KNJEASoSgVCa4qKXEUNXsE9S
b17Biouh5aLPIyNTAK5x8WDnwq4pRSpbNhti+JQVdqgfYzlzDDsYb3bv8c71gfcr
ypSa0Qw=
=meno
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-43e8-484a-b9f9-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf/SKr+/bVJ/f5yMtZc7ZljzNlwMdiVlr0hneofYRvAlKkq
jLvKExhlzpY/cgNN9OecEcZp8/ERDp+rfpZGPfgA6KqXk2qKM01iIsBhl1LEkMHp
tFZhkv48S3coBHzz9PXB8SgP+CUoPo+rpJTlWZ5vf1IHyrLyVjlpAgxw3JAvv6ik
mo0S9pDLQ827aDk6cBLWAZUDn9EEzCMXydNUHnDH0k5G68xsUFNg3RKzjMfTduaG
ktOaIXPGmFJx/JoVH/gyUiEJ3DggxDwcdzdg92WTxue9VMjk5vNaQf/fAIeM1wfw
QA8nc8Lhud1c9X3b6Mavmg2ytkK7x3ZejBof6RP18dJFARENqi+FGT/1xK1PkH50
xrVU0nidZx786BAYUDwVRP1n2AG3juuu8vMkpiI+Ly8WzdaFt+mmGnXpsxGn44XW
CsaGNXng
=mGL1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-5034-4cc4-b89a-2146dbeb2d5e',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA+H7eZEmilqCAQf9H5tN5DVVV8PY8Em7Byq5BZemT7tAVn20kstxxLrmP8sV
IAYDyT9xwBaT3cUmBpSFzlKac9smvtQDHiT/zfTh0dkWmlmm+50SxaQhG2moFCJ+
o88Lu+SPYLCWatW/njsfO6vDcqFnZFkiz1MinqWY4oHt4ioUp6qOwqviiHppnosn
8r4blOoKo0gEkP9bmy7Mk+ra2GeRIxd2zUTAXnJjL/sI+yzaq7kO0MRlVZCvISeA
ctUCJwlgO/oSfhahsncdNjWAUppUxzk+Piq4UCMK//nJGP6AD7qvmqLuzM9YF/gJ
JXRZmUlIizTb/m8OpkYeOVe1p50lRDWIzuRERioNPNJHAfKNVQTCuSI6+mpCiYEy
ld+GVIo/Zy/Hj9g/LTJBUK/uZT9Xb25J/0R62jkpl5k86hlvEGF+0wmiOuXSng4J
zYPBwytvvzQ=
=STbf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-54dc-45dc-af8b-2146dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAp33Wyl5rcPMu2GInU0yQ2iLjQ1yciQYXDV+Alhbdn1Ju
eEHRBrnZyXoO00u0LxkfY7Pcpp0AO0bEy13uOFDHiCwjWN/A65MVWeRycsd8NiNP
HyjVYM/r4w6Djv/3PTgV7QphH/ZFSrtsdorGzeCMnwv7XgyUbPq+jiBiecclKA/L
B0795TTXb9O5UZhtoWZmTNr3p4Y09571qwVkgf/4qgEfDB1kSUTy8FdwbxPkPUyj
NTkcfKVlapDpqGdZBTNVOypYXn4chqcuNyyE1J46iIECmFbjzeKsadq9r/8rJLqG
c0NlGik1x0vRQKtXrn5Ij4Ucg4L/An6FqtD8CohQetJEAWxGBRr0cQRczYIVhJlY
+VBIvuBz/sTsdo7lO0+r7+tzePs8FtxkFKkZLljqJfIjQyZAAbHxLo/51lA3NwuB
4wWC+bI=
=WTiQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-5d24-478c-a0ee-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf/ausdxXXcYd7O2tcMbEJMl1pFGXMGdCjG/aS+x0LWckN/
+VniDIobM8bdZ3+9+Q3EE9WhCm2lKf5Y71Z4wGD+2dr8WFgNjaflD8rHWpWmR+z1
BItlYDFveuh3BnzoDCLktlQzotApXsO7AIX0NSUTgbw8hYTy+BWN48ORXW0Pm9zL
+ikZCrIhBmSWzuG6IeX+9ftrpBXW1fNCiNveopRnIkj4biD4pPCwXtzW6kmLlAwy
BvaFK9b3sD4ybLgbZ5NGDWmI8doQCexUoT/bBK5NKUBlWlNJ+xqDvRxuL0F93iVr
t2EdGUVkWfSgjVKN7eQF9Ju/neuAfB8aSrOh8V1NIdJHAQA6Xxt9Ae9aB/FbsRP7
w3O2TBSNYvP6fz2YcxYD+Uu62XEFRiVN6SU1mRg9+lr2GDPrT6hCoBBFJKskoo6Y
OCSktAum+no=
=Hor8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-6434-4624-adb0-2146dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3k+UPTRIoz2AQf/cH2wUMK71FUM3t/EvsQAQYPzBjl+YVNG+qJtCqqYhXeD
ZApnkLTliO9Oz4S5m6bSpnwReqnQCNrZpYumMMBiG7YSs9stfHF6rMvWpTGMolXZ
KgyIYribVq2LIJyIMGCqO2YXNj5BKSpYFCqcIXaUbWPCQeWDS8XR+3emJh8yQT16
WaLBJOBHMu62UA4JlWlcpLSIpB5l8rUd55qe4HrbcSdM1B2crp/s6BYRaxtsbb5Y
HjxT8kNXjU6cA3w6TzcRkNGDJZitNU6Wi0A38jXkrtNivrDP+mQJfvHnmWhU1ckO
+ssVUzsGDXrDiaHEGwicv+gvof+Ecn6JtD73jMf5stJBAe1vOtjTsF/8EEXcl0vt
SyqHFdVPv5YVkP4E9g3iPHJ8nmsiNS88DxC8PlqTSfbUsPkWNai2sJ6O8KoW3lJB
1ng=
=cYaE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-6544-45fd-a88f-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQgAtPuYFUZhioQQW3p7W8hP28Z9RCGRngQWxjoskWlXH2G9
cOgx0vZu56m0sx19GLpyPoJXzuJiql0J1T2zz1YBg3FnmaH3EeA7x2U1Xp8105Gu
5L5SuFVA5SBYlMCPuFVMem5WHtwFh/YQpJ2P5z+HFj0WjI8vMhUzJ0gZ8fOusT8n
aHCbw+aOVM199nI1DVjMqxTzvfgJ9j4RA7ceoktZ0IdVpgvE6Ac5sHtxcpSebzSB
71aFXQWtnA8e16uLJBEAfQPFzQWnDGrv69v6HOIeD01Sx46rNoMbHeyak7w0+cV1
LL2iLbxc2w7icxr3yHtBRCAiLS9Hnh7zACRZ+qfh99JEAbmOLiwcQJRWLmEe0Cdw
gij98tYRYoBTapGQH625SknWhInjBYqwABZOApoW+/IgJ0VudoLq8FwhIwb4iZZ/
XfxEPkg=
=qOK5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-67e4-40af-9520-2146dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA9QpT7WOXdtjAQf/VC5eIHDh4YuMflS2dNkjae28O6W1ZcWogKh0QsDLASkI
MMgGKzq7KYfbnkqgr4R8C7CZPrxm4TYpv0JQXyWIRxt+wmbqgi7Sp217lYiWz/Os
EfDeRL0D0NwXDLy1Fnsj0o/Y6rOy/TTkIXx5YT64zXRbviRuEkcZnhzp80BxGnEa
RKH1E1CZxJvppmWEGeOut9UVYwZZ+34+97RrkC+NMITH8f3HtcmDRBJc/WDs66pe
+MwbijkwYRahIe0yoo1Fs6dRS9nnx0wlI9PWGB0zFU4EEbS1T68/Y4Hk5alMY97s
zQUWsi29nJCDv00+f1VmkgOrN9FdFU/rhIRDRyTr/tJBAb7x9mANQMK1l3iCfLwn
lrC9Rk9lEau/odC2FqOf3UGkcT78LEGeb/o/tYxNVnxyC3ODiC8m1kHezSczkrSY
fGA=
=TxM5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-6b78-4f6d-b3e9-2146dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQf+MM/k1IVgvrfGVkIHJJwLMN1vzM6l5PfNT6hgBkejNtpX
Yis13FX6W3w+oSgHsI8ujHgmx+EQfTeiUwtEJGsBHkrAs+UB/19albvYE1TEVTC+
6PLqcYq5mqAuUqrJ/g5UFTm5h5cCXC4uim8mORMwlhdNyQQzyqf8ZHltXIGZgvf2
QOuhqvnb4dJPS9a8HzDlLR/leTsdSFThwYweWU79QNpNeFCcXVPuAEsRiZA/pgvp
jEWvU7OMHsirvZOgNmt2V4pIAoTCvKm+KWiSk8g4i31tcpylG4LCRXTXtn6NE4PT
aRxbonCx8reWkwRIMaWBOLECbCoTKA+oJlUnNGXhddJGAYIGhLxhM+dHwlG33XGY
9eecqkYxScs1SVpDSy3woav1rpE8WlvEHqg59wsoHTSg5iw2cbz1ZatUSpP1jTZd
cmZqp2o/3Q==
=cCzd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-6d20-41e2-bb8a-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf/cTg5E8XCvpZgdgQ1q5dTITJF1Kw36rTCkugHAaEKSwF8
l9T+4x5sOsV3VR8CHCIw9P8OQJBr82IVCyoW/L9eG70wRljeKVPf5iwqvToCPiTW
Pcj3824UdpYmxO9/AFQzDFK0D1smz+bfovynJr+eXb556/Qf23l/Jpleb6PU5GO1
cPj6Ph0knsDZRXNwfpuUT6/oINLT7ADKchAj5fGD8LnyA1z3dlfucq4v7BVUu271
yXk/G0HY7aYK3bVEQzdAD1+sSk+6204RMw2WmtNQbMe2vYjPrRxT4wgyTNKZR8G3
8zr7lfZicHZ0bFSpyMv1v1I51GKtTwjZ2DMYnJthttJBAagY3M7Fb4rNapUmxqIQ
L/k3VWNjV3VjqHs2J0tJwSGc6r4UGk6G78UOWkbIB60LM+g0smqcjbduzAH1Xyc0
xOk=
=hoDL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-7d9c-443a-86b9-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf8DX55tEMujS893ZF2Fa4ZEy7wobQM4eq5UCXswMy6UvDt
zqXaL7e+x5yuWBU5sCAvA0jox4mUoENui+tLuS/qR0fQL8L55QS04e2ZaPQZf+y0
GF62LTeUCL1PlbNII/fK/BI05ppFft4ZP6JqV0cGhq+6fPNh3hY9LqQrrRgN//r3
9PInRxiWPaYCaLBlUdpQr0qTQaCTvYjG0qbTFAMYCVSyf5HTzsQYMadlM1DBSW9d
9on6pIJTqrv3upyHZENe+OEurWkk4MZJIGoBr0V7NSRiK4262JcrQW8Rc9MsbHJy
f/LUauqIeNmXAnmvVIbAMtPhqo+RvLxul77HIbU7w9JGAXv+wWTok9DfvnwagQin
Mzkbv869Sqa38W68x0/LTqyZ7KQnEoCroJgIwfj1nC4dl7IlqYYWvED4rscLwyAj
p1bOqnjLrg==
=F/qr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-8748-4529-b6bb-2146dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQf/e9RELnLYMnmV9s9gRMz9xnxZ+ovmmCOrWLrK0XDYPU9y
ZTDbDjxaG19sANmC2aRiEZFT4y8cVmLMAEA7tU7ztQoKIT7lNPdogmjUINW/uEvL
yUsEVU8L4NkDcsVNiiYx2xohmASWvLVlkpOJq8iHbNouTpRw4j8n8oJO3QnlrCxG
DMQ4GfHdCv71YuWMds2VwlmDjo2wSoOxsyDJSiMKAvSKPdr2uZ4CZtC7elhheGgk
Uhdzxf27/J89YWD0Td8H+hIZ4QuLCUXfxIprTft7tbQo/IyRaVNdcf8jaILyZhwT
EbbwWlh6iEWSApwMJr7l48gb3K9vt+2rGT7ynF3zV9JEASRSmG0TJt+J5FNgFABF
Muuxi7kO9ImgeVEQLRuFEmEH+ttBrI9JW1OtXnUnzpa+VcbDqLOI93BY4ctOXnVX
JcoAGP0=
=jhJl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-8f7c-4a26-bf19-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf/UGAEdYEvXzFrJ19kVlypdmrZLo6PppmpJARqNY0p6wEp
eExPKFoQtcEVHmV0xa2XDlLhkt5Cwplajjvb222yj66bshUCQEA8F0kwVAhXsUp1
wzKpbNhemfitts063vBdz+LwcbUqbxBHL66bHqkhYUG/D4XrG1lTvfKfUOSYA9Y7
MAJq+6S5ixAuCqjscNFQPKvkbZoCiZY7doN0S26/4wNuBapmGyfEJ8MOJLryONGf
K0ng2css+DpY0ls0NYE9aY1SkNZmcUxB92itLo3Ho1nQ3lDcWnEomrSiqAYu7zF5
T2WPC2ifX5McEYvBd+4hPexhUkm5gW7/bjjdXKg7ptJIAe0bJPT2KOiAUJH8Le73
D0CMX/YXhropTBoJFAKHEjYk6BxGNVH1ni4bNkBKfR9kTmxzvhyxuyZt+rHrUB92
886KimBR/DSl
=75M8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-9d50-41d6-b0d4-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf9E0IhKwowN73C31+FyP/kiR1GrFOt3ccHGIq/MpUK9ty2
yTQ3ao5Pm4zWLb1y1jLGMVpd86DK1XPJ0BoDGD+l+lzfUmjHHlku2HLj9UDKoNRl
mmF4QYLX6niB9JBlPLDvtkHpao1hsoV71zxKyGFxZ/2xGmXA/RrQj/YKOuZJo6Oj
x1xAdieGm/ECkYxKMgGsVfHqdzs/q8vazmqLiAQxACZd/0+x8rN61c7NpJJabne4
GaiNlptJ2b2cvju0xBCwiI5WclXAIuIi0EyKWlEl+hDn6Z1oLiAzA4lUTCDiqu/n
Z5QIE/70COedKQBfkMt30fpGRKKih5eMuwDgoNuzxdJFAVOX/QApADxod2+2GUTV
lOrNxT7DLRBnTNDfq3FNOS7O8Lbs3hteyf57HOxedZZh4U6cpt3fq5j5DKa5TYFC
znT7y3Ql
=sKtM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-a988-4dd2-a2a1-2146dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAtUX1g/iUlB/2rxbd0nIlTURhc0JYXloVv03xW4d5j1T4
RY7p759dzcXuJUlkXsdFs+9LFjTeTjaU8DAn5dmZUot5mArXO/NooVMAvj8EtKgi
Y/Jm6Z47svX93M5F46ZDRhXGJVj5hTa6EFyKDcUy44gEpUtIRF/75vm18orOmkdI
CJtvtDSrozA1dHVeoaISfO/BFWFI+qM+wphYcmZ73+VkpANQau+qmIQ9hIWO9vWf
oCXfTB09TT3r+61ri9S5KBDZm7Yqu97dG6QNRI9xMpBWGjZBRRdZ8ipJjKY/18DP
ICc4nxEyOLush1AdBHaE6zdQsRoTRNe71khK3zb4/9JFAQhjXUbVM7EmqugPNZC2
iDl0SNkPhTbKE/eVzvy8xKQAZGQ5rslzz/miH8BXEKywfN0F41lTyLIuQw8LNqFg
EP+eO39+
=j7hO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-aa88-4ae6-bf12-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQgAsgXiR0B/rmIIDLDrB+9QMw7tTYdFTj4Srn0iQVj+GtyO
FU0fAcqe1JX74dt/M+Iruuzg9SkZO6cSLqxqdcGWr7slXaOoO9R8an2MWxsQzEod
pVmzuUk3XFW+rCcd9KYwD6fOqBXhpv6coG0XfAboof+5mfuhsndLB3OLgUo/7FGr
6D8DXFQtfuato2klfyQXSUlzEMk6prUHX6G6N4ui1hjaTaozn58xaEIoBjUBLvOc
XKbpmk1xPb1dYz3Jcp+PtiYa8NJoLpykZxFge4M2ZAhiZ19JyJXimqj//hWMugbA
szOOtTi8qe3/w2FTYLk+MnlT8U4YWKgEBUQZ0/HGQdJFAej0oOuw8Ey+JjQUP1xH
Q3GWnbW3zi3n3WAM6GjEIsIXSlJRLV64iu/samRwyMZX+ke37C7N1ZEMC9761RO+
hIIjcZNb
=WDUI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-b784-4a33-b78e-2146dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/e0caJcE98yMvaktxO0x9jPnZ5V6nddjCAF/e6wtrQyj2
TqzIet3A9GK//PwsWgbOWUcho8FB/te1rkjmiEZFHXcdCrblpI/E0SoKLy0vV/xI
DP/JjEJbqDuMJ4B7/ZEQGIKiYnI9r0o9i1lDjhvIWtFrthP5bG4kmWT3Qdtu1ABI
S+Bj1VZaje2Zo8fnyKGz6/WZpPkM7OVu7o9cNgtIgpKnJYyZb6iKd6MskbabIH4D
0+qkWiHJ59F3tGmEw5zWg7njR3rb8YqvzuzIPowuRxGjy063Ku/j9sPxzS3yq7bB
ZtNR4Pfi7iGsUIIfRVU2FoO+WgsFjH8q19inDx/ZKtJEAcTltKVzMRllxf7Wr10h
+0/uTungZbxTtWxcHX7mS1UMDaQrBfQBzqdDD+KFUhJ9GavabuJS1nu1ke/DBzrV
r9pZW0w=
=j5ZF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-bcac-4496-b148-2146dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3k+UPTRIoz2AQf/SbkyMfw2OQCkaR2rnnSaoAOVg3bHrWWritZooW2I/Hl5
0SMlKXaHSq26ZYyoFzYNdWgoZlAofZulR43jTt3EEI0TpvL2f7E8t/hzr9pXzrge
LosSysD2Ej2DFX1jU7cx7tEnsLEoVNympCBuOdQaG1vvX+deABU/IO6BXsQiEjgj
zZ+owE75Q4bqQ5MxpljwPoqcueTpszzv+vdL9c9P1sLq39tyXtuL3ODeMxHek5VL
vujWGtkx276hwCF6L7vUIPBMhkuantF07ksEAEnSU+MI00MgK3qTbJbWzaswTtZv
cNLXzLM3jAZOtYfRuoqMamdYF2oghdJpiKNXuxfgQNJHAQXkYHiomC9NgMJFqm6U
gcOI1+qzrdOoOaCZ1Fgk8artzwlR8P6IZXCubb86uDkINl51cKRs6W54lBntXWPj
g1ouKyZMXaQ=
=HvET
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-c9f0-496c-8a35-2146dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQf/bX4dsy6zoIVBXPEMy6u4BwlTUoadZpZWCiUP/A1pC6D8
O1a96QSx6M121xskjRm+PVZK+Pvot9zVVdRIC91ZErLk/L70jx/6ooektrrdxdT+
Wmmnqjyrf04tg7IYLwv+Lf1NDRsAHv7pnnWCFI4RscjEVpyC6iZ4QUJ34F0U8/4E
9aYLRi+KTC6xA72YRS2jW+p61QwKfzK8YyVMKpITH9WWeVn/nfmixDAamXCvyWyh
JgLpS2OzXGhZwOhaGGYXWlO3mmfPSAS5WY3PHunvJxQCqe10iQaZxZ7gORXFHK4+
Zemot+b+ne+7pWSSmJHu+iAXp6YEH7eDp0CVuIn3l9JFASaK0iDVNpnzxPKqkKm7
qJ21REdWYkIR626WLOHDUmbK9bBY6RYdbCGyJnZhr9DiYwiC3hwtU6TwTP3e+ziV
B13owaJx
=F9cH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-d234-4863-8f9d-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf+JzzhAkhOv5UfqdJAC7oDNLf0MnTOudLdEHr+fWbfP0oT
57xgIhYkuhqGTvm56xtoq5T2ZiQvU4qEbtJIhWdS0eTWFmP0sMIu+Pea/aQPvIx5
au0empy1tFLDRiq4SZzB1uD3PKKzFNuxEVicShccSg3r6aSb2/54L3qwAAAYgrB/
2JqZ6eNQP3Q4TZFi7H8n603M7qyPjVioT1kRm+u2m1ZIiw5mLBD9Nk6+CnZvgPTf
tykpQwUGS3TTXyg9bRNkZfh6lhaejSup3OeBAGhaChkiePGdh9ZmlI1jNz/JIz1R
zTRn88U9kdF+OycN6wfiePptZ0hHVF1BxRRJ18VkfNJGARaaYlXVpFcnJfKaTWCB
xaPuPdVSbfKcsiuAVVDOSE4KlH5vouSHgogfz3yWpmRfZYxREuo/LKlkVsqEp6+V
qdQoTj4S0w==
=2O81
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-d818-491b-a919-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf+Man/Za7huJm6XBpaZDRFyVvz4U6eY7uJFF49m+ZYgrcP
09AmkgwJqZO0KgPudV9HG/16ZpQS18fXaGHJnWqkUvZFuo3En0rg1lw9fufGHFgz
eSjddKy8Lca8GM63cW6KdGim7TELbV+7pr2fsjSfKSQAPOj5B3M2gz4BekRRqnFR
0IrNsl2WUHM47ICC5kw3a9Ng2qRbzYrD70GLOdyLGAu5PKilubUAy6SjQuHlFtcg
5deGMIcBWWnInL0L1U45v6gIUBeedVdqWS0qHIMSa6pWLrWBE5df6UDwSSK7Kt6B
lGuNMe3WX4rWStxlE+ovhPutTvCCbshORp+oebkhddJBAe4aEI2KDaRv4gIvF7QE
awlsTZr5Y5tyuefef5esRuwVoAJbg+OE0cxxuc8F8oMBiEoIPuWcK+9XYyFZeoHM
VOk=
=MM/m
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-da1c-40fb-9ada-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf/RFM3N6pe4ntUQgu4d/G1Aa6Fo8hkv0Buw9b3Cj/0R1vV
XtvfFzSlto/HtG8PRHyFPArF6e9iwJucFOqUuRB5EHWYScMdodPBP0w8SeyAK534
qlTvrpPFXoLcaxBDV9oP+q0uV7ISoDd+4aMSENCNQoPs2sQ7DyhelpRx5VbfCf5L
whJgalMTNn2Fuvlmrod+UeKdy+BvGkep4kLWhIuaWwhCVnWpwSCQGO09EGGuGOv8
MgYrM+8/AaqvEUqOGl95hXfQRQ5JgAdtXLyYpcXQBXV8D5PGp5jSM/wqQHEXV+aE
cP/pcM/g8bCp0gDEahB30E5CSydtc69dTes8Od1aM9JHASUtX+vy1D+sPszCl22k
g39520duDrU09+41FLJ+GGncyY0lAS6SHx7wbTGgO0xjvVtuDPESJmbv3TlTqSQR
/JFcR8/7qVo=
=hXDb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-dd40-4413-b9d7-2146dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQf/UPidySGoHcSxLUk/LsM+dHUb8VW8Os1uzFaGp/QtVWwF
CYHOR2Y8miukMlPubrtyBSfWIZawGVEscp31IV2M7P5VPick3Ms2nNfTAagfZdGQ
nCIk6TWQ2Y0Fla8y5Gu3OoKS1PFIFKwwsrW2ITJsW0eeR2o4eOAs5Bzun1OM4Qpt
muB3xE+lHqieFLqGfLJ2si0nSawLMXwaNJ+j9FOHfG09uw5kPzm1vlPyI5nUIIJH
hT2zLU+XanvNV0RaFcxrWOUFu6E/BOANIZDSv/ey8xjg7yTjSJcMRPnfaXOAL8/J
S33mSz7JrPnf5zCeTnxgQEmTf0uHFDwmcLskOiW96tJEAbIUII3cst0Pdgx7NLgZ
qxO6xcy8N4wlq++OK139LHACeuV2Mx1CtycKrIx0G5lFlvptFJJzRTEAFGawoHTq
e6qpT84=
=jSgL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-f040-4f14-b7d0-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf+N6McFmHH8AQXc/UNPZKfqpSkyUpoBhXmlGpC1HekqDM+
4Ur6hEIde2tL+g7n8zMNN2MjBHsaoUmuF2Kai6JuhuRDNq8JsCNhXSGT0iOoUvAX
woxJOLtE7jJ3/ydYnRtROO9E8nedkr15y9ODFNJt4B11KpdX67aVomMWc5gnASJu
ZfNUaSajb5oQBVL+wIokvujazIm2EBEQpJ3ddMl5Ii3T7j15KtpnL+PwsZf+wle0
QQJ69tP9lBBlJupMhGZjrRj+Qtp4n+QOAlwO7xMjLlr7j8B3Bi3/vBhb8Vy7KgS8
QXRA8yY5XL8MssPY+Xq7Tug6cqioRUs8EaFTYKg55dJIAeFi6G+d7r6r2GVypnvv
f4sFSAddJeqTOWS7Stu7kknXW09KyRd6jYKSsRbtNEV6xrBdBQbhc5RJsGbbBmAq
ZV7zOz2mOLuo
=rsD0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-f1bc-4f62-8945-2146dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3k+UPTRIoz2AQf+JM5zlztcEYsBo8z0BIUNdcE5M3Y1zn2dePm/PB7fF1hy
CyRYIHmqrBpFxwpenXMSX9YY/09GoMHfG/VLFrHD9fcQU0HoefZYcX0uVMIOxP5N
eiNN0JjCDGOVo49+4DWHPVExudvfUFY6xdyZskUov1V4Fu89i4ed9X+b7JFSGdep
CEXq+KGnxAZCEybfZfZLUizwURfy3puWaa9PFRn7uvvL3rYnW2lAsF/x5FqGFxnr
ErgYPtw/3wCNGuESzFECNohrwF8FEvciOcF6nHJnwuR8NiqqlGgXCUWSbzaQLl4j
7/hlJztNhO7lLDmGddq21BkI7ijh5pnn5su0u2pv+dJIAX2aR8oN3MFZtUxoTrsh
P/PGeu5LJoRpJ42mcVn7nxt7ThgoGq9I7wU3WepbC9oBE+XEU8C2CwA079s4CRwN
p+GOtQ1vot7c
=g+g0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-f270-4a7f-8005-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf9ERx0TP7pZPh07fYIP0iYzgle8ZVTnMyXWGNhHbxGXulr
WaXLlNi/2p89+/RnDO1tNl1R5BVj6hq6l1t/UgGwWFPsMddvLfFx+Iw+iIRc/LhQ
vbZOj3ursbknZM9MU8neQPQ2fz1yJ9onY5CHf5Q98GXYLkppSxmtF/RPbqEXqw+w
X9zK5klRfswMUSz0k/NVFaeueuKvFzJDq1PrOdnOvb/MyO4dwfw6ZiSruG/fz0GN
x3foAA2uzGNGyCPbMs8SznnLjHsYA0VMRdao55qlMjmrZcku+2BLw23TlDhR+bOk
+fZw7Vh/Xf9q/LDIDD/uuknHWUXQ8/qZRAagNYMSPdJFATZ5BTEJ3cBxWsZEZPRL
AlDO6ICwEbZJuO7KO6ZY4yKbjeLPQYJmjZRxp6twAKjH4YSm+GPLsEsVrNw2beqK
ZfZTeeAl
=VoyQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab372-fe64-498a-bce5-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf/VlriVdcMcjxhhliSmnrQksHG++qZbzC+wovMUxR3nd9u
8uC2k0WKjGz2imWikq+85CPRAhiDaaBqRWUZf9yI9rLN834aIga3I+d7ZPld+UKl
NxAhrdglW7fJwoVqGG3DGJGQV9YJHRcyGqCrZcIafSCl8Yq/YAwkhVg6ocGaBjKW
+sy2w7pUto3CCIfJzWEZCFWMul0tHsUfTySsursSuQqNoZNMLjM7Xg0qvXNmT7nz
m5UAnVX5Iz6p4B/jad4EprEKasCZjjnh8jvNeMeyWz26410Fm90Kp5AY4/wcSDAq
tkrRjcQQ4zLNMQO/ddmaAz/JoTjrI2uULJbykFVt0dJEAWlfnFN0kVWuWtFpEEMK
0LJvfWD4HnlEKRc0AvVnRNCFBI3pXX0CqqKJOW40E4EZs30G6sx+3R1rVuWktyTg
9FVVsBI=
=bjfS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-0694-40a4-97dd-2146dbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAyFwuC5Agdg+AQf/UoNt0cARzl8ZOW91EbeGNSeMa0Hcqsm0IGWvyXo4MGAZ
lbmpvuAg7FmJRl9ybAtLQTdMJLwIPl51+lq8MzqrnuqWeDJz9lIra1H02jjP9KI+
TdM+5N1dbG5ycqInkutm6KufEvGJy8Jor4lSivzh/NopeJZzu2SZerfCjY9v9nK1
fbP8rDJj1FPdVtcv9JjIe3bAbBNIpiXjAL9OYCQ8p8adJqyY/JUw/Ugh9dTSJ2Mt
b6KARdHxQl/kWuJH5OujaqTEbhZhcIziKtOIEmFgMtpqfig2DMxmnvYM8R99XBor
hrFYI6K9sYsrxBmSUzKMmYxBXsU1s9nxRHEWjnbladJHAXhu8nL0Vgu50ffuWc7c
ROpcIUfgl/QlukAPDsaAYmjxXARka1sN3MDWCmQK6Ro7U1W1Q/H4xgSYv5EORxx8
Z/QfRsca01E=
=mebJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-2c34-402d-99a1-2146dbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAyFwuC5Agdg+AQf/VdRqcGbEwMQhoZIGurAi1wg8Epzqg+Jpcb97k4khWgQf
+SaJRsPqPLv3WoepgSz68Zs4XGFwwHxOVuGDLuwpAlxr8HAkdg3ovW/iKlzXAE3Y
NzehtzDkFzzRXB5NquDjuOFpGCN1OeyY85Y72tV0rKeJHM83rSTpoQEtn+BpAaSC
dN11jJ+7R9p+HQa5VBNvE76Oie7W6IEMu6l/eFIhWwliK9WLhObo/tcrvmMWnYv8
GS0I3bDUdzKq/btOZW5x7Upd2p3NTQbxT99PEi4AJBH+SmHkBt19orZ/99n6nNB3
u0uztQwS9JaNKyMoFTqXNI0Jbx02otb++81Mh51epNJHAd+JKg8/lye6XCRclL13
eCA44eXZ0c+kBM8Yu7E9VFv43LQjAeLvb9rfz149o6nJiF+agBpFPz9E1El0TyEs
HcfcWaQKNNU=
=CNsD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-3e2c-4588-8f96-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQgAhVZT2Ob0pSX6i9rZpWjvIxGh6dKNfumEdhP5s6Spyty/
g25N/DFcbHgz8mFOXwi1RTYCxH4IBYolaPSvh2buuBKSfB02UZUi6jLJ0JasoQCY
tpETd2Npd8bVYh15ameZ4xO91Gx/1fNwrYWDmaUb5JNjGFE6yjQAlBe0/sIej9O/
1UyAZCuZYg1iJFy31gMeValcUygHryCrlONQRT9WqVDVLGT9M+Y8USbgfXmLae2b
KvLC86bhTyHNW6sFqttBtFGjWYDCVD0OHmeyumRzH5I5NWNVDfDyYaAFjZ7F1MwQ
hvVy258LmHU+p0/WZKq7B03w/9z/k/hSM4m60hm6cNJHAR/EclUNDXM1HGWw488u
skngERbVfhCseNuVPQaILX6M/C6BEpjCKlct0rmXimr/vVwijUkD8/SxCwfpf/Qy
Kt4RKuOXHEA=
=JU5p
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-4480-486e-8000-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf/U+engsDwyTYkMN8EpoQ90RaNWU3PFc9E8xM7T+pcrbRj
t5xS5diuOTrdO5K9b0CVtqErhrkTCGVc42V+PrnMDufFaZ7y4nsW2oOKkukrZMwP
wnsauStmeUTpAbYYRBNeDkvEmR16yBas0gGFVQOcYI0NH26LwYMia/vTocWR87aU
vakCp0D/f8tPzwKdZefwi6zEiZ4u2u8DaN0kN1QB+p4w2pp0dKXjwO9EttCEWIMm
WCNW+QsEE9XhMf3TSAU2QReqv86l5S2srbMUQEghlF6+Y568LX4MV+iSP0Gr0SlW
nFhJ0HpkQM/hC1AUIqyZdcePloAlzYhaL07UCiNUJNJHAX2fnd6vrA50YOt3HC2W
wK0Gi1uv62v8AX/wllVEfhObtacKDOHkuupsXUYF2E/YoH2ViXLUD1gMLPClAT5O
orkoJKUnaV4=
=M4HC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-4af8-4696-afb5-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQgAqFEiPOXY/2BCw1GRLfnPU3SGQ5xghoL2/+SxkR7Zwyek
sY/dIca9SvSw2JyUeb9pmn35M88xk0aFuOZb07GSm/tP6//LPMd+LEyoUDTEwsvL
goi/zs6gybE98hHr9BamTMCWw9juAppG0f3dNytSa6gBLrGb4NTjTJ3g4MbT2wZy
HuirZjFSqm1LrjWeayQLTm9xRgpwTGVgTkpy7XnZ76IR9RQr6rMIzeSWkha7C4gr
//nNFUcC5plj5X6nZPgclm0KTVj61iAW0zhsZDdV0NBg9qgCGEY0CEUV6Ko5YG3+
2neOlhDcwm70AzYjjFfPyF3f83zXn3mg+NnchOANUdJFAagIGI8bKhYjnmWT+rFc
awsqvusB+BACD2NSscxQCFJsxE3jJXQHzs1qyn5WoNhr+MMjyhqobYz86adD1ntt
4Pq6FIqM
=HHxE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-5810-49f0-98aa-2146dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAs3749s909V9BFomStQnMfkFSjZ50r0GwcJ2MPmQH77bC
JcphRO9aJvaVfQ9BWjk4ytDKP4FvwG0c5PFQmtc+j8Dc8bdALqhBUcMnToI7+/jP
H4WgTYTbpjqFDvaQdaScSVhB2BoXGzEQx5RaepfOOMYqBR33eWMqNc0W/b7GyCgx
ceEFL//bE55kr4uFTNZ0I+lr1yDjgztqqd/h5vxGd0nuuGo2E88Hz8lHSsWzcSoQ
u7smwLTNIQOFWmHIeYTXkD8dKnfXvFfOrU6C0dtitYvvKt59Gp+tVW1GqxXaUlLr
PqW0N/84lK1bLNYDru57vhS4JDv5LvmCuR6pWl+3xNJFAf56kuSfB/bnsPiNXyh5
3JpgOGYp6RjSnchq0a8hnumMrw5lO/chjZnSUivMuJvR3RtCnOKZveC53sRBCszQ
5500hH8L
=iVvO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-60a8-41c7-a527-2146dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQf9GrR3nNPkxeok2SikNcA94BqstN1xzwmXxibFO+SjrRD0
jRiPF7k2nauOeYh/GFXDwuLPsytI2tTae0h/NIP/uvm4RUmoHCGvlmxe6ER4T8h9
KG/LUQyT84kwtW6wywLHBJgZ9VbP/Z67X2/WPxJkhCjbHi9pGBkoLKXveRxajFaF
jQ/+LGxUPzt7J9bmppIrVG+6CHE7IutS2dglu+a1upmQa+C0QzQfNCCgPghvpy4G
OwRnXZVzlYnYXls0UddaxneNp1Mfd++OPPo/GT+dymq9Dog2uDn3FaiKbkE/WV03
WjJhokVaPlujLKYZIQqLUJL+7obXBl1MOy7VRQey5tJHAaUf/JU0vaoLWXhRyC9/
vMNOaAlZPyTRvedWl50KL6KsEGh4dexR8iFbPRi1eUNtGXHurr7nrXRKsyttjlhB
u9TSUMTiOTo=
=QqBd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-6644-4af9-b5e7-2146dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQf/YWgBM75q2T8eu0FeWaEdXw4wcQZiWG6ui5KtGGoVg/fU
9fNT3lnV0G8FzZyEQud9SDRbo8WZrU5xBMIcl+JE3jtuzDH6EakBcecuYQWROoKu
MESL3Uy073dtQYdhI0BGrqN4dbWQjqVd5saPeuaD/lIuk8jC+4tI1n+pWVQE9YwW
eoUhCj95OMSOKKGZ8xqzd8u8PAac/tGB8rMw3YeOz5cMKenuSYvQRG5J104dgPhY
/kQxJiuVIchyLhQk6T8FOYya4EJ33YJfKAxsbeVRHQSAu3FPa8tL6+DTL3La+jR8
G6Puja90exe/3v68aAuotYMcRI6oP1n+x3gSBfUYSNJFAUmZoxl7Kx6s8TAfzo5R
HrP1b/7rqlMGrKgq7NmHrF1PZvPgcWRQLMKq8LWHJFxSzKnT61mFVgexi7CdXqW/
Z7AM3bEu
=Faz3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-813c-4366-b134-2146dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQf8Ccl3wUWGjXkCZi0CfNFqhql1kBpoxKT3C4sUTQ3sALU5
gxNh6uqlxnDXZQp16DjAp6LeHCuRbpUzENhS6s20QHNaz9O+n/kHm6iWjyz+7DZv
rE/yufpBnkJTVXr8mTF/EWuTtPdAP0V+K4ubU2PS40gTlTBTRBI5TqLAdRDXGuHh
BLkBoMjYbfd+oLy05yH47yRi53u/ow0c17TAQWbrIFdUWvwBAsp0Fj6xUVjDld6G
OymRPvNlMvm5Jl4DCzG7Ovfy9GUahTS83begi3NjFoaP23CP1bdyTKwMm3WcBDCS
7DoIuoIJkCdE2+2oTQz4F08P04xiGYASO2Z5cALgANJHAe1zr4NEN1rRzNmuS/hq
ZrCqb/Kx27DA1+HpsetBxH3BC00wJuUxg5dWFy8YXicSsuu+l4O1K6JwYpDasG+z
yukdRnI1CmM=
=zK/F
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-8fcc-4d28-bf9e-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf/eBlgOcXBKFMRAClD3GUrfpLJryLb9L1CY1g+aqUHNDw1
tuNB1T1AEY3hGoNeKoTgfG3kziFC9v9pMvNIbE9QChNUDI+nCEFHCUXWhQU5DFk3
mOkyLGbv57lMNTlQWowRiyCK3EZOhVExvWME86dfGYfRJsSIhK5LshNLUB1p09jW
TZB2cKMJ7ARxAq89rGst/2hPX4XCjEY+OLbxBA8sX7Jw+SwbDS3+7gwYxEbf5RkN
rwZxx2wphJH0yXN8Xk0tfDcuLKLwkFCtDBBpxsaX+242qU6eSNq2I1Rm2NYifKQ0
hwPiGWs4gebPQxpPJsDJwNAMc6PfvSzFnC70xqIM29JHAVrwFAfyCz+8pbwjeSvu
DT17F4Wymb0WxI/vZChee2Cak/Nev3815fRcYuzof9LCpYvGLfEpC3qnOgV65kxT
ezAA2ndGZBo=
=vWt1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-b4b4-4ef3-ae0d-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf+IGuNpVLOSciyv6iDQf8z8AMxR+QbiNaifPFxAmhHelI5
fH48lZIIFdzCIHa4V/UXBuDrt8/Vo5fpR2UGV2KLL347qfeenddORsHKQFZW15Sk
BvhHFbU8PyGM4aK2tJJcQnTAgx9ltx8yhKgGU3Yr9liGebnJTIrmyV0r8CUVDROt
SNwFS2eViXSS9b8FUomg9R3QMtmjVHvH4mJh+lePoqmE5+zNh0eoWxrAq2anpzhv
ywC6eYMj9MxxmX5yYds/qXBC+uJPU3WHCX7e3i0sbT43e4h4VgJbJm1ZjxdAY7ks
zQ+9j+ynlDlmrjyO1ZE2BcjQhSo6hO6q4wtWwlYzedJHAakHNtv3e+bMGgUUdB1L
MTt9meYk+gjLpsMIp1y2WHSTO4wrL3Ua4aTSuNGMVmkxxNvrYkHftTtg6EC3CUqi
N4nLq71uiU8=
=Opvl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-c3dc-46e3-830a-2146dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQgAnrOjhARpDXFa2GSHfWFM9KSCVcrcPJ+WP197IDoBmuTa
ijhwgj4RHVefTcn8nDMru6YK3ahKU2FBN6X1c8uaNK13OXJIfei3X+jAaI9K4laW
XevLMeb06WrBhyTSgRY0v0aC+g/iddlrqZBx9O8ib2IhsRWPa8O9ltxgpIOlWwSZ
U+jdXnDnh5ffuhn1J0hFBI0HQeYSCLT82XC/3QHytKeM9jnUTKOHmbv5/RLMIc+x
sLqZQYZtXgdxlVoikjMojymLvUD+yfA+AyyBrADi8vtf/+9Uqjjp4A/uSjjm/Obc
VRZ12jzK0wZM/9o4asGq2ZZERsqRRJ2NIyyk0IsdadJHARL0X6nftiUnDCSfeK9H
eCDAEUzw90frK5Ryrskg5X3w12JjUKwRNXl0EOIM1imexx0nP+u+miQgyPX+PHsy
5yI4gkqo5Rg=
=IRdX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-c52c-4e94-9433-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf/V4tne++KtZ3W7gH6sUerR8DTfSynNwkO39laQzojcAMq
oPMkBXjyGD5vb61YhUnEBmvtdWsFljxCaijUM0/BoYqNrwyWfTlHg0rMupB3TZ4w
ZxFAT3UdoGmkDRSx22g7DUhZm3e+Hjbr8ilFvm1rlBML/cpGmablyvdoe2vXe6Qz
Dvkfb1NEHRwNgrk/iLHC+zCtR4Mm8rMFmxiHdqZ2OHMeQE1e+5gAOeQo2URyQjNT
ybhQFkS83GtX6bAVCBqX0WazlHkOm6kl7oa8ebAxowK6oOh2Cdb280pre2+4le2D
rJu0AmX1G+bpHl0kF+QGvlSCeux+dM7i9i0qaWdtGNJHAaryEaCm8b+UC+yCZ7NM
LxEfYpPwYLCm507Q8EU9+/pD33yvgZXtCxJjeAu+Hfbt3jt1wkt15NHts0dNAeAz
Cj5jZfKoyZk=
=LXxL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-cbc4-4e5f-af54-2146dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+JFszB4tgL13uWZ7Oj+MBMISTm1mHJnRSD/83xrv0LuHf
XCL7qRKQbfMahzALioRU4cWKRhhZw4gY1D9jmzc/biDpbcGwxXdxDwEptZtVQV59
63iSkLD3MZR5OIajbjIRfCnMg2g1rxY1t5vjT8XTBr8GDquSZbqbEPwRm+/vzs68
t3cHOBeSI9Wnt/pR/snNt46Pk8qaN7SeSnjv0OBB8SI/FarMtfF6v6X/V6/LgcM1
OtP02ZGcqn0g1sDjNAgsojhewOitA4qAe/aZ25j6TsvX+BDU0Q5J6AMb7VtUAOj2
D0tcXdji2J48MmqL1yXyPgB+1AQAA3cEMylKJxPn1dJHAROLaz9pT+bDCGv+wQac
WSGy/wEqx8qBTNXEW1HqyHPqRG21kepgICqvJSUOkuvTmzyWKfdwq+IQwFeprchz
Vb4l9uBNosk=
=30I1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-d090-4fd0-880e-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf+IFb7uxn7aYxQKDP5bXaB5lXM675wZsAKrn9o/0L9YsPm
1e1/o51JBhvkgD9R1AfmRUmohvcXTcrciDaT7boHsGAxqeMAcz5VnCy6hyzafzy3
pGG8x1/3yJegwOSxvJo3jy2LENDnevhGZdQjEkmmP8IxikzfE1kltpaxR590+4zx
q+7AfuDRz+0579VEkkTq5JVPvIC3CpfeSjaPbdFZnMA+N+kyROoD3UnuOtRVI8oM
pPXZuvWwv8OiEHrU+JzuNzGa8TqOh3s/k5un0lm329jcM5VmiTsKf98KotFswjt3
nt9CX28M7+w5TUNOZCiq7dnCjXXk7o1AImbU5GwsbNJFAex69/AAx53/9IVUx1iM
q2HYFI6S0qX9hecwgV2wvyt9NuIlgIu9KJ4dQSbDmObFv5hyWvAhHxq9YDI4Qsbo
DLNUCITw
=ovS2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-eb1c-48cc-8b82-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf/TiHps+PoYN91C5TV8b1L01RZgrhIMFbc4I3csldpYKLU
ocpp399YiNjNwegrBANIINrfoSQ+dRmYmIMPDDrbsCgh1C1LsC6kBe9MQVeircoD
c+SBSYrOJdnwXxa12DjSRMoRfhZU4RqM3JuzzBD/qp5JM51y0lK8rsXcJpMlLbG9
jUkViLz5f45lwaamSZfxbLPZD9DlKHCYwdrX3AjUeKi82BLMku2/EUKT/rkGl1n3
SP4EbWCiV4H2lYmCJFNKRIDpHvLbqzINYejkO9FyGB1bWICnqASiyFnK30RuAcL/
97lHZw8/SXg/opKi+CVOM1gpRYXrm3AbsjcCR1ZTeNJFAVwyexorY+ETf9uqqWVf
2do92jSKMOpOEEVoz2k5NJfwxD2CGxjpEludMUUjRCZ2UpenAHPCo8NAnoNCvvgc
cp53ulYA
=B7c9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-f204-4d67-b26c-2146dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQf/eI5s1FL3qwvkOQb7WT1i5vWFFtt58ggaSI4lzw4pzRm1
OegtOSTmVyV/y0/giT6ts18wp1QTgXKSz0RbxQuoWXGTgqe/GK7fkeQVtEqlFq4z
VIye/yZCcPvaofG3Z5zrdFRHiV9yvSPh0v4KBHsdXrXvuwMbPcisc1Su683USIBn
B9JIDIjrr3tTLFaqVx0zSi80d3sILpvZTy8iV7ck/a0cktNmdffrV40vRckaZZwa
0amF8BOqu8iMAcJES3Q/W82gum9ZgBUZaRTk0XXPlio6hFe4VRc0oPY59vjG1uwy
EGss1oP+HnDndWavmmH3R4Ftbo0mqcNKuog/n/F8JNJFAd4B6X61kEIMHkS7cHUb
EnEbz7Vbl6CrTMz5cUINQOvAmklIIlnxyy/myWaYMepaU/LVNS6J5n91QR7kyxch
UgSjSw8g
=z6ZX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-f450-4bb5-9b09-2146dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf/fRinCQwZSzHb8K7bivOUWbke7ZZJsVk+u+fgpT5QwnlZ
+tmyxSIP79qh4wjSO+VQZcPfq5oc/Gi7OFwa3w0vAqOwlwakhIv/Nlvww6PMNkKg
+c9bfXUnvtDuwJpr76CFDr0flEKJyR/8yvldCyKZsqW9WHbih/ppUns6ZrbDZej1
/uR6vfrnFvupNrJEs3eN3dkiCQE/gsCmVwZLzxK3Zg9U7ECqADenhlpkcgQ0ypfr
VRguzf5EYsk+RVrmTDo2G5bfdjEH43fZfv8ZWefdxYqoTweO5PEZbwo/PJu3uhgg
5jXGxqL7pc+DhOJI+uHgT9qwJYhLqoXT9voPwFPI2NJFAW+iNet0PFP9+p6U/Fuz
qSRdV32Z4FasrS8fBx7uPinj7S4Sv40KuGhcUJ8MiwUfXeRdogcz6FtOQPF2setC
dlk04aJI
=xUFM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-f968-48e1-a16e-2146dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+NLbMpTZB4tXQUXz9KGR8Ec2M3HXH1XDYVEZbtZsqROUN
O941fTC+I0yHP52640zICtmvlx5KT1jsY0Au3NcId7bMc31JSNJnSkDcQR6Vx1Jq
0SE0imsTHYYkUNIE0YIphE9Q4wrnSQOGDZ7vg6oUfR6Cc/3gQdERTUv7BQzfgiDZ
nvEicD5UwGKEu2Vx9opF3Xoe4gVB2wNnylvMa7QTurc4hXwmuFfOMdByc1NuXHSF
+tCTKjv6jhXJcLZQUgVU3L2ih+XgRUvMtdC3tRe1qiyabu2LUyMIIyXFmhcaSuFZ
6jPu03+HgozCxbvFpn6p6vJQE+qA6nruTHkot2CRGNJFARLUxzSN7jHJ6pMwAyFi
WszjQbv3u2bSm/enNpihKEEmMvL+p+Gphp+CfTpR+yC5ky5naXTfF8hS9q4H1gGu
DtQrEvaa
=bBJ1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '558ab373-fda0-4966-8621-2146dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf+PA+p5dj0tam1Q9gbAM8G+0QeO9EAYOu6qGl47y9VXHml
v6PWGlEhlp/A9in6BnzFXrJyr3bojGeqm6zuiTrpRDZOogNUW56FNL2LNi62HlpY
wwxxvTYRokJC6LiyU9IUfoZus22qCgfDo19KC+YEegA4zmabSe8d98YOi+UvR5ru
MeHxebvS4DcXE7hTsZeAA3n6b7didklNPrk2IVOBNg99vE2w/Y3XiJdKNx9ouiTx
dFQxn1DAsLSXCrgFcb/AjXmBU/QRIUjGT1nrAiJrsNGgYq4pFx1VhLy3GUUhMuoB
dt8zrL5sUT6kMy4PdCFLsp0b2gQKwfu94j2r3B7+x9JHAb3rkBwzVBq2HS6+XM6i
7RSZugEDCy3zfuCVZP7oEyliG3VxWd4680YehxKCR5S7I5Iqv4p7nsrglNRRruK/
zjSJeiEdWRs=
=20JC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}
