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
			'id' => '00a593bd-8f82-4c50-aa17-ed025d47d5fc',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+IY4G2tNWtcRB6n9/sZJp6PRDGDqHZhElxVMuX3Eh7Wrp
d1vyUD4geMBBHc00pLfZLNNmW2H/0kHNijqrXCDR59t/wz636svgW0dp5j8bvJqK
Gqch6TKq6auMBjgAkHATXTyeZ1V8KsyyknAtz5VbWOM67VhkDBkHqBhFByFhal8K
WKbfDFDniHnXQe6zCsB+Z22tvgkTevk+pFPGvLEeE9AVqLzZl9G4k4Huctr+rPxW
4ffAJinT6Gug6mN8jj16byo59IoTUcWVMUXT1shgNqcgIdVMM4gdk7JI54rst6fI
OAjto88FSTemy4yGTKPg4vTzcVQMXwZugbH/OfZnz9CvNR8sKK0gYSFRJNwP+7VR
YAVDgeMSwQPR97mQOndWEpw+PBdyWD/P4RoklskOiaSL5PWVQRNCQxwj7ycLPvW6
TNv3TAdtFGM2ssTDiZO241G3CA16/8ALLGcd27GlueUnCzwAgHHqbdiAYfiP2YW9
e+Ksi5/dzHcgGXedSqvq7ygh1hHQDA7WgocBGhvGNHdDyF1EvPruUkmuU9zpQgNH
O83Mc6rlQpqnWdOzSKwQIkLz6ujp+lwu2EFc0ejvdFGO4vQXKQH3Ooime7K5LMzh
/3xc7VTt+Ty8KCYYJ33VMDCyyFAN58WhsylPMgbYp3tbDdqAJp3BKLoOGVwVssbS
QQE2xCexyO85HANcjZdL7LbYX9rQ9l6OtiDv76lC0E1BiiwY1W2sGWeXeJlU6Npi
LefzNgeiGWPGEx6xRR6smiU7
=JAKN
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:42',
			'modified' => '2017-02-16 14:44:42',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0158855d-c46c-4028-afce-1323e5ac2afb',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAijQg6gTj3ROSiur1fzdywRKVvu1xgL+uBOeo37bfKVEb
5JQ0qaxWOIVpQkcVNjWY1sn1kKhDhgUaBut53Rjn+ZerH+FG3oT9htuPMnHx571v
4YL3I8FdKo6e4taTdS8cP9XG8tWnlVcpYRmFVR4E3zY1W9dvMjZalTv/yzXQl+1S
FyXFxsG+7PUaaVNDN4vhbozYfGQGJYz+p6qZLtSv6viTkh9+s6ruY/G0wHG7OOFH
T7cF4U+7oBz77zP9XTHmhI7Xy/Vcp7vnj7eC77CxeeKNynn9LjT96VS5xi4ePzEW
IuNqGapMvU/REnxH1cmmV+TwjjWt1VrfR3UBr4hp2WkT2ezJ6S/0CcWdOGdY7XHR
2P5De19Tjq6kiXufwVX387R4xL8Z4pX51qLqx3RhbWJ/8lsK0ikimdlIQQYhX4+q
Xi3yEhDvDasvTLTwtjAJEC21PqiAvXiEob25Jc+yVKKAFPKDpBMzMSbB3z9i/oz+
6K4+Lm2rzXAUhttLcNzQQcU+gWxoESQjgmcf3ZlWDennFCmn/tBsop3ozWEBlN7R
COuyoLTsQC+ALAJOTXuH1wdRNlito4muoD/UUcSZetnzJm5VFh7zFjho/nyVOz+g
7+9gzD5dmMWq8A0B19NSY+UPpoYxfc5qLMo6BhFX3BMoeQGoWGE+I4Z5T95AgSLS
RAHy78n1bdlYF32rMCviLyKye7IKFkFPE/S4t5Yail0mCF8FQ8dawtIOhHXE/wfA
49XImfk934THthnFtbI/sySjxt4+
=b/xB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '083b01cc-91b2-47f4-a961-4522c40a3e04',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+MpFfeUqp7xnNUh/KbQ4bOSwMOA/yVJ4TVgWp91IbNybv
9LyCvtUGTVkUeSttqL0pxcTTc0u2phb56levsvLSG2AwSVD/arZr4YmF47WbBPsP
S4dJRpc6ar1A9Z7G65czZsFMKraSU/hDnXU+5tRxydFvlhlh7n4EDvCmlPztGbmM
jnkYciHQre9B5NcYZC0oBBUU/ZOSXkS2NaNX+gZK5YKMXvqVGNfU0a5RWYjNIKMp
8pfd5jOAK5oFttt9V6WJgJxLNOl9wgVrKf8P1IGkJhvu62ngYa5fwUkgn3DJxQ+s
1ub/agrLTKZvQhVkX97VYen3N5NF1+Ix9SXqrkwpliT7CcELY1U0WBworBtjeA/y
5wMPxACwNmzJRiliJKWq2Ph2jbnq3EsEhW3T0aPs4WSRGpIq18QKoaajlarj1rg+
iaxK50f+vn2VfTInBl3sX1D3BE0qii+REIxclk2d0+QSnsOPOmZXbXU2VT0+OO0G
7WOgrX0anGiw7dNJLXN47wXWwJmo783Y6CMDkUFR9EGfu8orFYy9DiSRkS2W/TCP
uoUXtSN0knBNHS8UqQVc8pxV6xWw2pkqHiqL1wqx61uYwzBljzmXTrFYjLlcCyDO
lDiwjyxIBe/zw5lL0NKYB5l7AmRgqjsMjEBDWnvvxaDhHsg42oGaZZW3SBYkHG3S
QQFhbwSK2cEThnkonXlZpdZkiCMD6hBxZ/N4XhBZkQ45LOd9CgyDto+tZ6yUu/aH
sIs5EJBePNWyXIP1tTc362Md
=fzcS
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0e3621aa-fc58-4245-a5eb-94962aaf91ba',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/+JoLB5pWdTHDujlpdi/2dAvZTSbF5EAfcPztXY2Svdewe
vULO9kfN4w4zzG9hHSVozWfydzVqBRXhvEWbkjnZXKfDsD1luVQ60GaPtHTt71rk
/wyjfPPx0ElUok+3WTDk+qv7dQSsEy7C+McthnyBbNtGBLFa7yYmVzwW4M76MBNM
rC0ennk61z9yBAiFHFxb4PnbME6WPOTSkWOFZaZL17JWbyuGRbwDVwwMSr4aPIIN
oqZfsQR4Pw2V4p/colqmmKONvUD+WIICo24+rCDMt3l4K62w0zfRWxqmDCl7Ofl9
Y8hB3XltKhCQnB8RS7v8J/oCETRrQCNspah0ZmSfV5/DTh8OPy81WHKfnI3kin+O
8pVnGHjWO4QJexSHyuivILZrlhF3XNZeMhFahhPdaBWcDYwD0+SvWpWHghioRuSd
G0upUrdvddy6sK2wyeVuW3gT3ClyWuNdSxFQGSQJDqjugwMbxKHbuBkfVdCtLlbt
u8oCFVc5afqdttsnDU+L+p8u0Tb62nd8gLUs3RvRd0B9+Uu5e5T68IE7b4eMtZi2
16xDeZP30ldvtwSH7mjvhDXhC5izeMlJKTZeyQh1kjTQXLIXzUq+11o9AwcfXA9r
Et8JuoRBl69QOXZvN/Ua3PKoo/CFrNpB1GlH6lebMTAjC4vsYvUit/nYMBpBDFvS
QwFX8rt4jIuzed6D0F+2bfmQnPzwoHK9HJnxqFsmuyLqxEYIQtteV/Bdk7LIS/fI
cRb1hI3H88fEfNu2P0KD8+rHlPE=
=Xpre
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0ffe4bbd-abae-4cc5-a980-31b2c5b97d02',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAvGIX51dZE7lF1BXFfKEp3dZikwjK1HsYKW+ktD2CL1N6
Eopz5tSsQJAC8P/1qFDcMPGVYBv2a5Ef8EPEYFFKjEzhW609vBDJwGxzXfrPgFGt
ec0MdL67Nmnd0f0eMc64IDJuk45ET75DNw392igb0ltpp5PNCaijY3G7aO9pt6gU
gfldUm2fUY9pknQUk8ywHt+GIMgxjwL1s7CZm4M/X1FkRbhGwf/gjnDyJWv6BgIW
tf+dn8lAe2YEGl7xpzQ2N1amwSV0R1p6myP8WSwfP/3LeS9bDCpqANbo8wEvA52G
Xz2VGIVBkWzqJr/cAWCiYcOrURhK20cDBjPJMimPPIndxBUGpN0FhJoupZBKaK2r
n60eUVVVs056lLgk9AeD2BB2lJxK1EKYudKbqtf4QifHFHa91IA+fYEmUZxW+/+A
Zbq0tb0VPoy7rBUWGQL9UB5BRiBtY6FIwvJf3S+53hwbP5unf8cnTXOoNP9Zltdw
63lifletov7uiwAcW4GjkRdJxRiPLC0A7eU2Mt2udeudyGoXWjj4zHNCi2qT+4Ox
EaqZVOtBR2Kj+VoKWmIdrHGOy69K75GAhQhYLVe4Jgoq2XJOmchVr9XMsSb3Ngdk
qf2FxByHgWdcC+NuimwnMkPbrZtNzX/hUlVtE8qbnOCD/Cxfw8bbkMDHHjRZFtDS
SQG8Ivk20+thyQpvFEuJiVo3RDCdHZjPRQFvRE2D1/cKftNOq+G2IHfMIz+lQVm6
tFkCW2aPGJRVRigq+VaMFekoy+mVwp5UgOo=
=sljW
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '13154418-a59f-4f8b-a1f3-bc2beea5250c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAn3W/u8nZ1+MU5ZjLiObzW74F42QvwD95qiuAXJYfN7Bn
VE1++rgt4vHk7E+Srum294sFxUMeCSczOdgJIEu0lxPSDU6cEA12/BTy5SmNlpQj
6uXdkXk6rF3TekaYFbXoJeD4Hm78Grj0s8ewlpYLXKGfFCtTy9Gt2kby28Esdjw6
BryQDCV5L005mm5E7UaTxWo9rtSDDuvfZ9zJbxl6qMK1rN2e40/zyikRn1AW3vpp
7yhqFZHzf+3T8bzDGDRQlhW8j86XGvl3YAOhyvbtZpepyzm75yzXwetxlXg97u82
g3FGFU7se9d3DbNdqCGZgUsfjMyoQ0kko1qtggnHHJiFyxlsym64/I6PdDnHUguV
PHSZw5w8m7Ctqpuj7jcY2pi9TOKk7SxVChu+SSPvsHUiIwsawrG7/1IjYGxkvTxP
RcDYy5bFHBLlm3Z3Neb0Dw8vZV1EnyzMqPW9FfvPbVN7p85ixnTTfsWP1AXeSzS1
Q1/dLw1nUsQDJLBFIHOMIcskWd98OJ001PEL589fDtT1X+JsxVxTdf/ADqVcZeQz
h+pno+qsfID131DWYoFnBxh4hDNbpKmXmI3JrM5UuSx3oBXH8DX/a3pnPpetv/Y8
MhkTqh9owgGYYqyNfG9m5LWuLbnJTmDn307Wc3nL+rBGJa3lcCkQfkwrDsjeKCnS
QQH9qQI6+JP0H0VIKUh/hqstoxQozqgLGInp58WcRIGf84/1Gjnc4MnMwqMSIEfV
t8BN52SW7letDlQ3JtfpFdPY
=vg18
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1430f3ec-f693-44ed-afdf-35129efa63ce',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAuPZHG4hti7d/zNR4RnNCJcMzuetoSbmn7p0EebpgkziN
HZRSn+Oai41dBT2PiriWbVoWjZdPFenMyoHRfjvYsCrhKzRwSyAl8UYvEm3AIwWq
KwuebcjpZ8hZGhNh1yVnagoc8e4HynRmidjjxqcr1Me9GsztdtQtbEb/Lwom8HWO
I/udVTxM2BAM7Vb+LO08NtuaTxw8jghv39Inqk82hBhjiG+ma5167DwCrnAeUXjX
crDRsdN6X0A2jUeULFT4XeXLNCzBNq5Bp8gwB3KUILX6hveeXQlCIptovHLxs46T
FsZf0LfeNkk6vPgNsQSDRmR4213AIV0Q8bBmGaSFBzxTqLMuQMfItRV+yQEMz/8t
7iK9Amz4E8jZkjr2IQmbhy0EbB08vDCVwsoNOvDzN6EbvNERdEr+qdKVUo3CB+yy
4lYJuIm24bNS3DVDKyKxFnvDElnSvFs5gqyqJFWQJFSZU/ytu0nExQWYcM3IraxV
rykP5XeyPHTVzX/rBEuBacn7gtSqIwtWrBGjFYYLkUKi+KQdMk6kifPDeqREpsBS
dbHFEKJphQ2+or8EeEYW0Q+BZg7tx7AjtC7F3BNh6w015Occic3GpA2JmGN2QdZ+
oHzKG8ErXcfNmI9yVU9P6cMoFu03mEM3bC1PLnwqJBWHb7ITvIz5oBz1GuhCuQnS
QgGT+Zqx07BsWfDIgrH8K4yoruT1c2qApBV8d9sXjxjF7JiAdWFvIqOs2w/AL4Io
Mbl2liRtksO0L5rc6OsiTkItNA==
=S0a2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15a6abfa-3a48-42a4-a16a-3a0ae5aaeb2a',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//XOXR/c6H93dakSipptLLjbfjpdPLTPz9uAbwJ3heJvGC
175sZNoS396YZkDR0k5ZqyuXzFxHccJmHxu8ImpHM1BspRBIcv7SmNInVxNAGCXR
Ct+UVRroSu+QwgBP1vGvlf4ewujx9aNiCyz8J4/X024sX/s884AT1m/E7Wf8Vy17
zNbx7NKiAC2S8LD9x0g7F7rlFKtRKzg8cf+Yxpkir5Mn6Avc+HpWJN6dLmanMvhV
LW72Z9pEBw/EqZutD0+L5ti81KDYMsnffxTq6P5Bi1bFiG0r+Dl6eifeSCfLCyNb
uzUUUqf58hWnSAf1fmhbvaDs7yzUPt/L9crUHQBYDpDpH/zjoJ3lpdmsOSX5dBpe
AGObRYZEHmhv49l2PzKDwCf2L3yarpzoIDpB/Kj3JC6mxPGa1joXsbZKkkPFWnfH
4V0RslzUimZwpNE4hNBBOiM5t7qDlprpfW+/5//HqUtieA4R/ulmNMpTzp6AW6PG
zJ9SayYsZm8R9IxSJLRi+JXPmOUxhaG8pJ1Xuhtjo1eOJHtyzVCY3MV99UjndCpn
5Sw3/iDbrTizFKdhjjGUAJYcyRaKn0MNvQdzERK7UMZZYActm4i8AhFHqWZcIpY7
ila3pI9MusVaoFNi4DZf4gr9TRgirTrbcauoV+qg9G/ak/BiQNHRbTAxcjAw0z7S
PgECJCrzfakg97lKGEFU7c12KTqFT31naLbMr+ipI51RRcLkuSaU7WOaGYuKKwO9
JwYuqk1NbYaFuXNtvBHp
=b8YH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1768be0c-4b6f-491e-aabf-67a70fc77453',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//RwCb8TJjMM1Z4PzbHD8i+lNAvLsiR47kp4clsOGPxKUc
IjX0TMHIBp9PcvUY9jXmG7ealz2qx/ALMtQXAyfQewuazxvvXOSjJu0wWQwIDiZX
DOY6JwXQZZII9a3Uq9E8QnGAxV11B8QyvVoHsTPUd1Eoxq8MGCKa0v+nFEyef/hp
ZRAP/va+0nbnNeCage0I1s/fbgQ/ba0LJkUcq/ged85hpsMXx/LXjAjF16wERQ9Q
GuNxm7s4PI/q4Tyi33yrRhOq/bHq+FVrViA1nJekFzbTEWUpKcrYVjaGv0i6OOLW
Q0PKDc0adLRbyEbkpRJtueiOFhifjt5SdaJc56fkgYJa0P+4Qm7GkjHO+jOtotpc
EUnCM/dTmhODLeMGCjMKMfv4r7k5IRCBOEB5SIKixSeKBWYQwi314MVwBILooH+z
xwKt16EzLLAbPMTpNyGwiCiV9cJFGgLo9MngvB81qc1t9tvPK+sBYamAXJMB2URp
hU6ooLEhQoOeE94SiAzG3lRrdYJXZyBUyJqAOYggKCHfJj11ZXvRh7q1gxJejtHy
JQZ56z39pFckXZ6h90vyTUKmCcFfvAlWlclcQhppHXioRurhCtX9q8bHT2tGqVEd
cU8UIMVOyDAzCqRX2DwMt0rmKjoHB9iSqDkLO8g6HO2I6mMJk6UofM4A4bU0wbnS
QAFLw/NIyo59fldhZA+y73jQZn6rRZn0EdF3j6Mw+dEw3efNhASucOXYM0pjgGAF
2B250uW0RR7JggZ3re89FvM=
=bE4b
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1a0a7c13-9f3e-400d-a9e0-a27e82a7e7e0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//TCzu9eXlMa8n+JErpqEdqGuDc8sbbzCGexTOI0j+xFqN
cfJPiqCISPoEFzpP4djJQgqEh8JYtWonBQKEIKVeoU5IeLSXGpgIwhUrTINrfOXA
r6atLfKljKxbSo9lZJOLhkhqoMTdKsp1gq/M86/RBcW5yX+LasA8f9muG5Vgyc6n
LUnmIOO87r8YgYYpyyQ/mjHPrGY+V2KFUOMuaSMMShRlgp4XPRA9NsTgYfKXDH5b
7LOS0j+EoBHK8tfEkCfWOfGLNkvzVbvpS/nIfxCCgY8lFKxUd76wZo0WKjCqLlfa
MdWVZ9OvX6I2693d2uJBCvpnCMuMwJYrPGDxLBqq9ZF4VXUEy54HhvMOfiAejqDX
BW009Ib5r+vlY99N+SV2V4q0c6GOP9lZsCsK9esx+klhFoJC+y3BBBm1DQnHWbcN
q0C2tY9PwgbgSEnFRca5YPHKQ6kxIcXdI3O+N8fygG5G2Ur1dRdh85kejVEwlDDw
nYxy7hGJBU2xhqaRKdPf6CbKaB0gxPmFdxM2UtCTO3o8OGlUfVAj+vHHICw1bF8m
0F6WppMQ3Fwt5u824KSRtaA1sJj2B4YlNZ1eQ2wjQJDHsipa1waDrQRYM8t+4IFJ
f4F/EGfXL92ry0k67wq8Kej/KXwBGMcW252jxK0vZM1Q6HXSRqMsf5mzZJN92brS
RQF3t0uHCc4eD5ilE7zBj226i2dgamnOxjeHc5504LfQq1ons88JElDcwWCt+6su
lM90JFW73DOjc0z++8j59aj7JR468g==
=dFM2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1a3f7d83-3384-48b6-a375-025514a5f7ca',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XshF/P9mXPgE2yJn1CnmRYkPsDNn0IrJtlMvEnKoc2q/
aGkLo362BLbgkZq1nSoOWPsQG7KGZGGsbPhhUfprP+1YKdqDDpUbsagUbw5jUM9U
7eIIhF23DzbI4j4Jz4MRImHBbL1myjjVBq8kpsm6OvE7e9YcFCMDLZkMW5So2EaE
U4aP7NpwnwdtED0197dBxWrIfwyk6WkdNKQ7rJ+T7ggB1NHlcYe2cpF9AxxZ4Dpl
qh/Hac59r4y0FsSjGnXm9w9pyVr6pcLNEJqPZgorBrOjEgk9pkAN9RCf6QQRUkjS
CD66YK9t1NrDP5koDZbryLGewxyMFEhDoTl1FDzkm25fFGEHSpzZDkZbK9pWvjBX
h4qIfYhswM1226UDYILzwfdVU8EawnUZWqk3DSRFZ53iuWvIHZPBjO2QM2kUzjts
6UUgofK0K7yEwAWOyxRi+wMCU1fWbQ0TMBRRnueeRnx1oewjatNybLgODV2pcYsD
QS0WKjFYhO8i2Mwvc66tPjJI2knoVwAj7CPe77zHlpwtQL8HnBGmdS5EN+wLq55L
0+4jhwkrbBtMnxT8M4vXOCOJsEHe1Q/jVoNVUbCMaBUM/4s+ij5Vd4RDyp54lYdn
NFv+sAjIdFoR+SUlde6CWAMQSElZawzfB7th5++2gWWan3GpmksD0o+1EvdhdT7S
QwGg38D+gMwqRCFwrit+npuI/ZCpJmlSlxeXCrG0OxFwaEN3RPJSFtP+3IZYx9j1
jS/XlLhf2xarXn1SXlrlaVts1Ho=
=VVM1
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1dd0ee79-b2b7-4710-ad30-74cd0758c317',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAshHnQT5x9iGlvPAC8tl6W4iCmK/ZGjUKV9FjlCZW6lm8
ktqxIjqRfB9Rv5sbcWBqKLxkmXobWZpr/WKH2ceWw7TddM6dfE2Wl8TPQTLVMEkf
6LzfsAnyXcPmbRCS/WSjdk0KQdWn14FT/ms1h6Jw6aQC4D1NyWvUnxiGqzdc9gcD
4aFG7zO/gwh71IF2k4VKc5vFjbeGPoBB7Htk65SsmqBZZitLn834aiAa+Hs0p2br
NmiZWdyg8sQtXd775/1KYE8lBZPWlpn0EvrKopr5cA6XHNC4lxvuhMq8XfuOH0dW
uuReZxPji2nUYCoSt2iX6qGfMdlqZhC9MvdDeJo96ZNnPHt/TNXtYDRe0/KBB7wH
4juI+o1BYbLrCWtHnI+WnGTeovSyZ2NtPckHBUHXkTyLT1T45+6DUgKEBQCgXuAJ
Xj+JgLnYp8Ttq+i2afq4VeYhAzA1SRmmBCoQOnSsPvGYpD02uBEiCtOTpG94RHLl
aBTMsImCTTGY19kw2tnYlKvLGwmVARuS9+013XPRCh78xmx71VNSSM90kM/NQksQ
T8kdU47J2k55jxSA6xvV46E19NGV+/xiZPaM5a4BQ1N+LfdtmBDCSthe/ap/dslh
FnMspcYC4xsM//j2IgcoPJllCRdHfD1DYBi5rnT8ZLSMCdYXE30zgYPWEGPqxG/S
QQFe78vqzE+WcRQqCWEX+h69o4q9nADsmZdbYI4QX+2G9F9ROTDkn4HNFyjOfqUD
c83m1SU7H8UoYiWORTpOpJxR
=h5Be
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1def3dee-d776-4203-a6e9-198c11ce207f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//XBN1l7PgP8HkjfzrBTj48R0HD/A7CsmqAYVBekmsb8o3
ZJzAdfj7Q4yGeiUDzH8ts6FFUyGY6gKBFWIUU3ACkiXxJXijFwQZdXr2eGqvqfDC
cfwvmiMqZagRFGTdpFEMwJRMZXQph96X4HroEMxqG3jyUTZ+rqh1hMvuGh+Ighlj
vbH7F0PkzEVJT1DnhxZgXRau0Bi4PVNY5rWwAuOdvp1jVZ9VEQ5wetRRi/TA9PuS
J/wBBMPn5B1GeokSHSDS4P/AuJNzjoIVYXWvrA7iW/LVYU5TnIOq+0xCaj+N8UIi
eJ8jaVx7QsOXfUkmIke/yZVffsc9Mtba/r9UUiS2JxhvfEKT4fM/TQxcH0wR2GEv
N4fpIRP5sPyArigSg6zWZjA8La5o1IUo+Pn33oG4ddFadGpIKtOdUo16EBckgClC
B8jl76rp8xZaIdCr/RfrQUCYwZKqdPrfWRB45Y32d0lmYJc9N4hvyepdJyRDw1aC
DZfzN0CD++Rrz9E7tk10V9rgFM4NyKxCKn93lr018COaiYkDyS6G2zV1f3o/Tsnd
bWlnJzWtvtbf483O10ySlbR3P+FKz5utTRh9ymFCtX844dEd3KRd7uraEsnzdSca
AN6OFaQuT2yxQ37wVfp1+oKGMpoF5mb171lyegX5TTABF49vs0tUoYb50QuTQlnS
QQGMFVV7BQyxJzA38UuLUdgmL+1bscQzf7vB2zJjBR78p/nXn05pa5LI8ChB7g8z
ffdOJG2Pz7q9titYkOQbVO4H
=o8wr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1e05b803-fbd1-4258-a66e-dd898f269d16',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//ca05iRvzSepHIE+FoCRlr3zlYaZZcU/pTFBo2nwZzHiN
tVqFdIqk32slC5cRWJsMUPrsR7/YxxPmdHTyf4d2StO7IBjuukR8k1+5IxjPCIEm
FpQCXkd1z+UE8y7JOfpgBln4RdvLlx/QeDVjL6ayjipLvZFZhfCurntE7EItbjv5
5ooPiGVBiTlmBgVoQOhp/Zaw7j79pfy6F9LQ6F1giX2uJyAm5dVfXpFw/19q5G6X
D7OQN73/CGUX6u35fGSODQSewyP4pR4D/+2/Azikqfy0hgFy73f0bBPTYkYEnJOK
qfIvC+UnHINSYET9SrErCLLbybXecuOvxoaMGHvHeM1Hv3BPu+QjnyE7GVVgmuaI
1tEdPMBRTZ3H4iK4fLoRti0ZhSodeQTzDlbB8VWefVbQujXHJyX6EkZ0Q8RW+Oob
53uIfg74280GVk49UwhqHPKFlpi/DB76x5hyJTYF5tmEHcqJ3RlkSANrzIjnqR+0
mdBYEHhjr5yvnhNa+lK1UiQO0zk+WaySGT4lIaSBWILc5uuR0yCLRrF+rO2Z0msc
JhyTOxKM89J5RTcuM7Ie9yXwzqVfZ959trO2Ari4VoJ7DrEYLdpgFpNmbYx9oSNY
u+ExeqX7AKviXxxSnVU3w6HpHKWXYez/Zum+TBdHlfTHOvh/Y0gEYD18J4k0GonS
TQFnEV72PY/t6Gp7NHz9Mv/sxaUaDF0hKTLIRPxH+IL9XpkVVjV4FY2SvZLFBzWw
4uKEIYRjEuhSElTTV4M7Ood1AsCwPS3uY6fFBfXi
=KfUj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:42',
			'modified' => '2017-02-16 14:44:42',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '201f93fd-95d0-4cb0-ac12-5e74ba88ab81',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/+PiByZ9mEO6UsVFjKmOq3/QmNEoDGtC3n/1/11FeyeK2t
lI61B1rAGBYcL7BJyXsHf0mgDdH82yhMIu0rvfdjnKI4om6MzmCBv+f0X6E4o/Lu
XukdYIpOO2JWpwgOA3G/topOpi94Xnye/SsXHm+WThW0pWRA/cExdEyWkfJcUleF
/0/KQGrE/Y0J8GGOxd59I8/9ZuqXr0H31ibVmDg84vAbysAeY91XPyNxBmcs8ICf
tjnd4aM4ucv/RORWawCYIZGJyxJ6z0kxav0kdm9Q+3CnRtyaLUfdv3UvavEhVU8b
eAT/QldY4cQP5rwlTyIXcDW8pYzpZtsBRV1tYo4E4V+8mA9n59lHUci5Ue9165Dw
X7PnH8qGHNyk5SR5UoSKbJ/hU4TqgZXeOTs2v0aElYYJrfFEpSXcqYhSqFuaevFh
Y4LKwEzkulDLakhk8UFg4O1gO2Q1CR7iCRQghvBDTVOpQEc0ebwJzTMuYxFhpDcf
oDyhPOwzi29bCaqnP0AoSPySx0zG0alYRfUfm/JE948KfYdAHKZ+AxyNg4NW8Wc4
cxHmtS1N2y55KI5WFReyKvQIZOvAIep5o+9L9xPwQ4pM3wn98BoqnWWpEys4cc3g
Ko8GmC+xo2PylwQhI1CKsFAc1qLPExkYfOrYZs8mRqEDQ9l/t/SdockJNruwRtjS
QAEOO+sAwTs6uV3mOmrtz8Gfch9bXWNoqSA72sFzMfmcvywN7VsrH4lLIPQQZf5/
Rlchcb4l+3o9k5cECoPUxaA=
=rz2d
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '20a6fc1e-60f4-430f-af20-7f02850e3696',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+Onvql5qLPQ09mYOrKJrVKHHJG+2cZ/wHipmU0PC62guQ
oKj/VSkmvHQs6OIL992DnwFJqCjtrqZPa1kwcBl9iPiY5kQIReFXUmnSUoz7QCVr
3MmhO+7a1vt1/LfFOUolY9aU7S+lZiaT6n5NxDkYLOAUrrT7g6eudWVxXx/FUppe
L3Bg+etwaJOK40qlZ6cJV+0iZ1Cg2m6RPXPJkMkCK7BfoSJHoSncwGhAW2ICyuMN
tBlmqfeix4fh5lX36uX5K40D/35R2zObDA26qQiVQmLujy1/NrOtZEI2kfKeYvls
UIv4iUCtFIZlxQHYn89bdyM1EqFafOeoGZ7tUPoudExvfSqTUH31ifw8d2I8r3fC
Wo8yBsYY+F7Cw/ZbXa6uvEYqhL1KmaSSQ4ovywyTu45vvzwfNLdnV9nQjYGEgV4j
gCNWMO1aUEdd9Sl8sHMtEJF3ssYF+2hEqVmxy8Fg3gogPNWqRCjYBXXiKMEc7w1D
DOUp1tqAEH9FkwUBZctxscatrepiDDmAzPj9V9Gj6KtjUGXsJlgY7mSlH7Kx+AXr
YpcBnMhw3cZDKMtELdVFoE7J2VxziOb8tgX0rzAxvbo7+M90E0nVqxMCcTgwjsu4
EvAl/FJhFpYyUAfEMM6HYCTFGHI/SQvnEtZCZOGdh46aC47yTqV8XEt2tFIHlcXS
TQHW5evHUd1B7Ji6X8rX9MPLOMepUDWPh9w/JGO+DOalx7y7aKdzHefFZQBfEZXw
fkANjoNZGEbtdozof2H9VEkcuRJ5PU4vKXSqlfo1
=MS5Z
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:42',
			'modified' => '2017-02-16 14:44:42',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '21d35fdf-f132-493e-a638-9187ddb41a22',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+OCkvXmN8v304AFaKJvdJMdCTU2vOwvsdhSo9gHH6tqXn
S7l+RQ79vfqyGtvt0Un9/HLAskD2KStahWlz7JttV28TJ61ylE4gt/FP6DhUh5CJ
jNtwqS6//a2K4MqDaWHZprhOPFWXmZMEzHfuTKeKkB/8BNKgPk3G7fX5WYFwOskl
SGrO2oQdZPkuNHZGIE1KvVk7HdBQXOUtHS7IcerK/n7TgJW8QPTeaAt7VtUOx/8o
cVjZmsHt4qFylejTpe0R1iEgMhOAuJSv9z4bKZYmaa50IvS5KEtFz1WO9nX7hi9P
XJ/tHoLPF9WHh4wjBVhmTHdQT4VZJOzlwjn87PManoMJaY+xBe+gCxgBQ9zYVBfc
w22rbZC5qyhJkXYxXYAWtBSpVTJVevT1tZmVriGV7SEuGpP0IsdQSadBqiki8+k2
6MXOO8PwQ1t3w2FfeL9w2y8XpebYarL+X7yyzVVM+TAqZeb8oWSHvUgZ1r9hCr6G
mtTfNij0sLMkSRfxQI35yFtNevQ2xhz8yaPAm4IZsKrHWHVJSsagcInBVzyB1TWA
6VZib/G3PX+3HCQSDqGz1d0iCQVzQHUy35NibNPQZoTNgXYk06WsiAaCtzCAH0K7
FvH8RYOMWa/Sx8StyQpERmnWVDifk2yPYW2KHZkjv7u7+1vGbLgZGl0Skv9pLSDS
RQHcl+IuUHp2EDKi7BWePPPTxYO7QrHZjgxqNwE5pL0PHPRE9CZVukSyhmIaXl37
IbYYWtUnP/VnJYsbu0a2hQ2dh1FR2Q==
=5a3G
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '226e9da0-8ad4-4082-aeb0-1750c079f3cc',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+IafFDKcLQA0vqwoX5iU9dIooAqxa1wPVQgY+HOvUHZsb
k1HnGWnXMmYHWbPK2yFK5FMSJ0WekDjpYpWFQI/QTr5x02dff6WQUlngOoADu/aY
C+GEm8tW8XpwJICvB7VhmARk5jOnU2LLpmIZtTRnYMfC6CzYCN7WAb5osaIh4tDS
b7GH7lZZ3yICWghVpDEZXqIsqkSxRsjRNGwex97VsVaf3/50KYcrKTlkRP4HC9an
jP9RmXyP//FdXaehqVMRPmXojjEr5bwTVkQowHHEBNm9uoRfZhhxifiNw8TpZ6lH
8M2HszS6NFP650hg34P9CQz89wLELBrQwSKI35b7c1Ud9N8/jkzvSv8mVsIK9RrU
FD6o/9lELi79VFfGS4C0h9/9RHIQMGxAGaKRJVu5m8Vm5RlOM+ECi1tsydtR1XT7
yMZ2pxNWlMfB9lZ8zuChSt5uUUavMx7hYGjKVn98jstjq/XwpOE+bTVJfnW0RDRJ
wRuydPl7d92S+eCWtIUEbTc3xlCkXBZwlSVJJWlbXBl5T97a5iCC3dE5HVlplvpX
jnCxmJjweTjzXimBKER9NxGb6d9E9l2LEC9YcStWcdEAZRxpaeaEmHdYR+JyNL4H
JL7MhIavB2xN0TyQOf8Ueal4yTYb/oo3kmKb8KcVwQEFZclR9DBuIB+SZBp2N+jS
UgGMXMSOmrFkpUcOsyeYJfhO9qaOmRypd8yzSdjAa3Gu1tXn1EbOOhf3HtDngR1c
yrwQmq3eAnXh5rJN4GTYgc3DlO/70ue4jM+QnjRQJcjYtM0=
=5XgW
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '23b42259-f204-4bd7-a0a3-f0c21610adcd',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9GSI9FCYAswsYod87FRdnfAIhSY8Bx5NwEO43WqmjhLHq
cueflzwDz6VRwx1SwXLa2YfahObFd89BfoX+0v5TnJfCiz+HclqQl7gJAPxp+UHS
Z7jmtUnF6V0T+oehZ+cBYuvrVuv5MBU4VH0PWIzsvx0kJ/uqt8TfDzn2E4gskhWb
kikJN3l/6WhfMxCCJSPOpeGl+Vcvy2qH8crlB0M5ceN4PRHtLnpqGrZy6o6pMTMF
x60u14mIDSnQS26nq0j20IUBcCjvkTSkA+d3ZC7W52usCrwmkvIxR5atInEhEPT6
qqCIArvBCsdqqafilufkP6im+yuEBIaUzQcrV+is0YqmIW2YFoAlM0QsrnuA11he
ZyGp+HpRyAYfR1jnF4lAaYAk7grNRxV7wVfvMHqgYBMiw+nWv8WVA0ERd5dcf20z
16fIhlWkAa6oRgXSToiq6WndoeSHfXLok+nlgRGnM9MpnaQzkhODnvvrWHN8Fzv0
ggTw1S3/9m0w1160tAPsXg1psoFVFp+NcRcsBijgCTZCim4r6qRo5zolA2JmGaPS
tA4Ho6s23ZxykW+qmhN1GVoZVTWhsiQzSMmOrIKfIpAyUWt36UDiDpLabVUQ1lSF
M0czttb7MqkoCVDNECCpkX+tAx8fxp54q2i8VD+sH7yK8dE7a/wHK0W1YYdVFo3S
QQFwPb6VhGBzKB3m6rLnVXrrtxlIE3pzFMx9Z9Uz556UE+gl4+dkflrZgvJ10yxe
RO9TWE3y2B8vzpU6cVTm4EfQ
=gR0x
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2580af78-bc74-4b73-a64c-a224826482e9',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//TUurIaC4Z4HLu73OJjJigkXjcCgAeSaQLw+zMh4V8r6U
06UadvDJD1E3E3ZJDJQr0xdALUiEEjQLSSuVgs9/t8kjeVOPjiR6J2VKLp1uQHJs
q0p5Fo5pCxVH2/omq0zoY8umsAFj9HhUpdUQnXb/8Vee0s1fdeU1BoXVFg5tP6oa
KNQJAlE/0wQO2vzSt9odMe6ZqxV5zizPDCOoJxmsod3lkFbJg1TMjSWVJq18D88N
QHrG3j/MkJedFknCPhtPfibkyjdoYjetUZk/iqKL6MDSbD9QmjDhphq+1syWIcxO
LQvYmVxmrhtkhFuTgDMJotR2CJP4qKVU5RNo/JZNE7qRFJjHr46lpcw48g77m9t8
+c5Ifr1KUS0n+GUfSS7iip2PcCbBAUF2DPsBcPF6XJJBff8eJuO6Qg0NCKivp9jn
cpt5ihJ71UfVZm7j0oBxzAwVrqgy3D5Ud21GWZYJyNe+LuAeRI7aEPuSiM7+tJa7
AOYUhg94JVTZ2rbaKDrSusj1+03PExZgmQ/wObbMIzqgvMrWs0tcqgsXK0V7R+jh
36dmhIsZsjDzK8o8dr4ZXM/m8BiyayKeJCDOpDv2tHsD9Ml0u+5NeKBXHm373T5e
TIyUKVqYmUpHHBam/qGTdBXECU9R+uZdIEaiR6u7B4CqofnWRNh/PzFBExQBAl3S
QwEfu3NBQjyjGqwTJu0pCNuGtYIdHvDQd9j4Fh7oWO+R8MAVNITvVdAMMvNBdUT6
r8lF9okhlYRF58kPRZ7tG+Y3jTc=
=iWNA
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '25c18dd0-625a-42f9-a791-383460b1313f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//YkXNdjEDjI0DU2ABIPGe6AiptEMezrVsJY3hSiHaTj2Q
D4Ez8Mo2ltjOOHW73nGqAAc3tj9hqqHj++n03Z51vaLTmCM0SsSbK5SGY7bQjB7e
ZbggvVaQtlDNa39reyeSOFoKWZCo7j2z353SVZktYTKWUeRmiBroO31V7EH9xEgY
oXfzTUJSLQoIC1jGousNDJbx5eosXeE1gjylelqYPkNHRG34MOMX3q8qRU4eV8/1
ThPkQkqTeyP+71en8RH3Kr64uiYnXh6IUCxS53c73EPLS7tKrX8zw6Zn+XXin11q
cX5pyXNKSw/ZbPh1cOqmMbjBbsA3NG3JOgGXGMS2jK6jNRjjLzcpWG1n8AMeHUkM
Ci5B+llWbpn+w1lpActWRE6/kb18d4BFVv+e12ejiPqnNsKR9H5/LCMFuU525Diw
U8oC/P831Yo9luEFx3z1JVcv7p3g8vFJOWE0KYKx0eE+bwNOaYLy8uK4/G73TwYh
3Shp09G0RZbzlgLk6wF+cRDvphh6jnVxEAr2hFqLhhJls+QJuU8RdENczkA1hva1
YEjj06/Rj1ptKtdOLoL689o2A9/WcwyIIDwh1JaKk1Tae5AqvfmW0CZfb8taFFOM
jozzmhDhudlrJ7TYxC+4J+5QsZM9ocFIYffbR0z4v2fLqR3KFJaQPUki8QxD09bS
QAFaV6S4DWyL58xZH8pOaFNo9B2Ht6w7Yn8DHuvrZ4qnbHww5a8iu1h5i35eMb70
6jcZyhHLahHoyX9WFTqg0uc=
=CgD4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2b8366c5-0c24-4a76-a5e8-5eabbdec9adb',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/7BTjcyapuIvgT3ETyyzDRp9Ap+gxMxlx3jcBLmpRyhNQ6
HDceU3pgsdoE4aMOijmMMmAj4paG850el8h4xyA9W2S3sYq52d8DVgx2F2Tx2FLl
zymxxSIZU0zXYrkIt95F6iAI65mfTujzt8B5V6rLaFegHE/RA49Fl4MqcDpD0xG4
EOvMfggJndIRIPkcF9Mm+mOrCbLlNAkxOZyIZtXy9z0YHcudU/FAKhWfnXQsEuMu
0XEDNIhv89u9Av6/7QhmNycFwDMp80+IFLK2vWGxroy6XqQormTuf1gM1xTzbYOW
nTHtsvaJODaJkfz8VNI8pCXEx7T7I7EkYJldNIyyRlKt9g9br2gEYukxyVMLgTTO
Ck8ye5KzuUphc9++5UAW5/bA6+u+hWLxb9DkFHOyBjmQBtDr0x/tb1L0/jiCAvQL
9X4THxN46+XcEiAB5F5ntczqdJbyoiupfIVWxsbwue6k7J9w1HL2AZfLpxKGebCt
bOOa/EZ6PvUxQtsbE2meAVE1uOdFhxEvqrb31+2a5uWWAltDfQC7283HUGEJraNF
ANBPb4ti4PINl0NJiLPF/+a6ilSghnjVSJcCeDIXvvqzEIz8RVhXVoXC3qtYTSh1
GX8LOHIiesns8jU1O6trJNz0GM0isK2PCLuNxVPpfbPCsV+TTnDEbQM3dhfGExLS
QQGBddkvNn0UbH7JCE/OEr0SZw3SkqfpoL6IKQVC789KTOGGfDnTYXQKRd8VH1Tf
7fXp0ZSMfjh8YwW8js9zhcA6
=lzxP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2b8b1b5e-84a0-4fa6-ad53-3ea8c7f77bdf',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//crG8aHcVFBEjoKiV6/JFJOZSc8wTXW/wywFOMJAiyghy
j6nAI+OHo2hbNtTOal2GSvmuKN6pO1OM4cuet+iUH7hRwD9cHLl8BKvMyxdihtGg
ctUaFgnS8yZkTl8Cg+IfpyhnvIuZSRS7pr/sU44geqFwlO61dJVM5b8tcrQsW1v7
xJotQCXv+wfN4imZgC0OTsSc5cv8S3Lk+weo8fvoP6oHhPYZJROXLzFAfmIlJDdg
B1n5qJRWB4uvqtkQYXGV+HGIUyAOp2UMRBoSbUEowkovaNMF8MFUhf0r648ivOz/
IPZhyBKrJCaImC/2IxsuLe2vWgZV4otsgaeIU3LB0ip4V+NbwNkJOc4b4cTZQ5gN
/MaKv81a/SPJHGZWK/DHlQU/q7FRFlW2pM4lEBvbg/5ms/tMJGpXOkCsAH4z3Rh8
gtm4w38kicrDC4oHc7IuHlm9Zmi8No+8pgtpIzl+tBUEQePBLNlkgyJSzaNc3aqE
M6QQssMkdCjOZVGqtmJRLrt9G86wLT2EcQxX+/iWGrdjbxRdlEYFIqsL1NJX9KNB
6SVb8Fvl6oLOqngmo2e/MXK9pDheli8NTlsAca9M7grnCX577x+sovbf0sKRvJWC
LkOEVSb07byd+IweOQf2eyGJDgZLquaAmL0ZDoeD1F9beS3mzXcb0j6S2kKLiPPS
PgHMbeNTFCOLQp2ktogPCgBiifhsm5J6kwI4ExNYcSOu4H054qNK6KNDK0sRhQE5
OmZheHsH4fFcYp8GJO8b
=u/4X
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2b9f58aa-8292-4c96-a9c8-9f0de0327f53',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/6A0tiiifJw8oQnWRQF2f2RwGpAuOA8UuoHWZuhnEsg0Ls
bp+kANjVXty9ThDUIh0DHhmn936lY7NJZ05GkJweRryX4yVKwn7GYgQxDuwnWoaz
tLazRpUhI73aKxtjwhet4OnclLD2EllBkttaEfKAqsZlYZfddm0e8rVm26WOztWU
7wD9g6QgDOZx31oLG54/oW9Xf4zYF6XaeUglw/ZFqDcj38yg1r54rAfCfvN5j0K2
+mpeq15iVTHzojhWTs7NUfv1YLLVZYvQFunkfGmW/zHRJmISVvENsqe5rNMwj2JH
nqQB0aDNfJ1M1ga4APlHWT2iTSTFqfH3BY0BvbFO0GcJHBCjTETd/xWaC6sa6jBM
mC3UYUR3VLy2c/waZV5eFxaBhpPOW1kXOvTdYpkL5wkVRfOUP+g/bE3JnNIbTjg5
yKhm+aM8NpzyVsbuT7YWuud96UZ3xlMSoekVXU+GX1YHaUgQ/mEzHiei6hn5c02c
tdMOiszl4GHdl1naQdPC53KD47Yqohv5iiYCDf0fTUUwIWrVnDcERwsdIyRlgruW
Ca0JTCOzqPoF9mhGsU2B1NhRMM2QkpeUCMdwkYTV4rLFikP6t3H10KAg5GyPJyDu
2JuW7F3sqjJZfLAX3zq0KNKn20x5Xq/aAWf8Q81SuUhjj7mr1csull+8TK4BVjHS
QAHQZv4YSAUp7TNZs1kA9K5EJ663e8n5i79+egSNMR9uUhA2FEBhxCo1/d3dd/Z9
3Y5Uhtr6lwsOSDPFmkKyNWI=
=CPya
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2c28a7cd-83c9-47d1-a8e6-bd7bf2cba0c9',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//RS4NXyn7vymIfDAiudrU2PKa7HoC9CiCKlyl/6dDwtFe
NVDMgSX7RJnllVUoORGA/fVla3BEV7BZoS4bz7QAJsiHHLct5JtgcmDGx9Rut7v1
TN83MZsFRmIAaQlrJlN8S6U0K+qJCRXlEaaWiSyolaWxQe0BPy1D5Xr+Ly74ZqYv
7Xa/0PW3andUqllrCauTj0nPgyLylkF9truEWDmorr4p79orlDQm9qNxjOh1fNIQ
SaRg/dFR+lPnUeX+9MGcgyMw5KJz8mAoHOSmA4Q4nhIKu4hDNCEq8Niir1IZ8ns9
Xcx9Ykuc7cVhr4NYtiTRxkVw8tqBhYYjyl+xUtj8GPsZWK7AA7S0d+gmbC5KOCP3
t/up+n7gbUBTyZ4eSqq25rrcBXPYF6R7uY1S/gB6CPm/IzHEmlY1A3uozkjPjGER
Qy5NnJaNw9C+41yeiwuuzmR87iFz0xOmUH3wdcJaG/HBYHXMQTOdv6X9P3cJmykn
yNDvLl3ktrP59LC09Rb2VFIZ5ce9UO3ttS7w8TYX6q86Bhld6SuHCVCVNJXcjaWo
ERHG5YPYOUUodMPEMTWMvVHNXyqSLrakKBO7Hv2hB6zWlOpsLth/TkaQ3e0XLTHC
YmvjOiXOuVfffPAvXiZyMSeZ4XARu7PMJrvt5q9zzA8iiFNAjORMOZAttPcKTSbS
QwHCZ2279qc+2ogGX0QPPBUKHxdJkWD0fCoS9tDrLmVBUhVmimmo5i0xeWOJDI2H
Jzn5+trkpoAGR/KDqC+LDze3kWQ=
=GmIo
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2dc90e30-86b7-4cb8-a366-feac6cfa977d',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//YTT9D1tcJrwGLz7bpJjMcMSycTI+PFNjsqtYzuF3Waz6
J1VoN1g40QgbXFhreUnBmpEKL5++taUKoKOV/78g4HYlVNOlmhLJVqbaYK/IDEgz
xoZqffIThTRMHJdthEeJmORq48ujujHyMGllXDTsecO+fRC61hamZS/jXKa5jzrj
6gv+A7341J977nGBR48rHGvzvHRMqHfPfqzhzkgybxobsfB2VflBaV8qUf7eR75C
Vhs9+lgw3KTE+4V63dArdC20m8grV11FwkaR+Ib40ZC94VCGVQmDf7d8M+Cd9x8a
WVpj1lYTRX2gvYx/1JS/g7npWMBMKwkjBhnQzY+GKvXWUpT+zI65iN9ssoJsbR5M
os0NYd8Bc0qHnE5RzzP3oVsYEqLFmbJ7aQhtcLtD2fyG1pPWN+9T07idJzRVAr61
5rYQKl5wkeo7bsu7l7YgXaj6mtc5vXZMbhFBGSeZejBnFE74TkUPVfX4WH4uHOLm
5y2Rp92+7irHD199wBvUpacgacSW4wDE+qbXGuw5OJRGsYMlczz83BP3aVc+FsRy
Nr+d1poA4/PS294v02hgbjHFtg4aKoLLu7wRZSAtugdMJPLt81WwnfFqedRJymPB
wjcWKu7f4KKFQCiYGCDHZJv4lV2UqmQtmZ2Hb7smX9uBjUOScMebbP0+q1sakKHS
QwEJmaXGrBfYmB6GTgMAJxrg3PhjyshMK2qc4Mrp3fj/G4pFlSu317hKxouWsFUa
aqHO95RBxtwHgWNlKzW3nEUXRZE=
=pyP+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '32632d54-0c33-4f7a-aa08-afde8152044e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+OYKm6IQwtDHmqSZao4ItrNCVUc8uPa78VyZHetoKFoEx
vimGYxm/VUqxEKQIx96jYIYZDcweeuBwWwDj6uzmC/m9VZnYPfO4jHKOoWwypDte
KO/2lYpB+lPOIpIDCOHjwmuT6rzHw8Bp4TYMOpUpkRyUcPQIcX/CmDLDEL7BzbH5
uli2AwgYBUIQJbv/EmNgo3OUVxcXSFRXzWTv80spnIbDkujckDyURN1kWPGNaMfc
oT6k5L5Fj5zb2/9yCGyJM1+s1IdIc2Hnl+xGPOGmdfKHP2pGTFtWuqoXREYjcVrt
ceF6pX3y0fv1UtKl206r8qRBpQty3dccfpFWu9o5JkyEBq4u40GCkVaZbusKOLl2
1KaEWCq9UQgpvBILHvhpC4KM+1zk6gPsSaQ9kJ65eWVPFnwCWYAabNNOFYzaA3jW
Yc3oAI9N+4w7PbUc98sCwC2q5avrbEnsNyCDBewl31Hn1IbM1B8sLvbDm63Ojf/Y
zK0lns0CHeJaNxDPCkCtksfZ3Uspx+JZAH8Hsjfnincnedt1uJfF0t2Ek55Fdi8w
pXIYJ8gRXymj83W3FUnesZGDE0OV/+Dnx3zFfufWZunkQZbJMn2/aU7st4f3JsNB
EMMq9rKEvL+gEHO8ubQmm00wL81NsFMeUHgcSXJRbfpGmvw0Z4ii5yqqiLMWWPTS
QwHoG0y2HS3+wsWZr+HOzwAyQ+ja088x33wEh8Z7NvuQkOoVjPQpet4lB6gUwYZw
5B1u93IVG8mKw/vvQ4wFYcHdaiE=
=ViDM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '34079f6b-b195-4377-a1c0-c5920210d298',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//cjsKVw5INkDmziD8MFjW9c6U161M73gyGZARBBeCY2Yu
9dVr1coqCKZzpFWoEc+1VqpzdH+NubFxgl61VhZ+IdzzcBmtqmI5KfmeNUpWVf8C
8QQcZJu2aHlm05JzDOfVhoDDFTRmQ2eYyoNta9IWUdxWdDvCqaarn2J/rEBAjt7W
lVxGJBoGpKwQkXEbf5SHo4xng2KIH7PKiJre1BdXxRvG6b0QnBe19n4TqFQYB341
atI8hT1Fy1O2N+GApc4F9zYIruPY8A4W3nDVAswKSW5zbS/LOLNkHjxIefvuQZxD
zu5l6AUtYh5fNTDhLN1Oak+Ywi9kYJrs15prgbMGZM4YPC2hRLCQIVkzALh3u46E
7Hk0IHJATJuZtd0AAnzJeDH4RvrVjxQFuT4oDflpMZGhoIY9azFicvRl0RLkNpZ8
T9xv1mdNNasjzvRPuQlvGa3QZ1WYhgy4GzpoE4+e4JYEb/QENA5FqUmRBwKPUM3k
DrfwiY6iqBO/mptVZ8WxqcZpEZxcwnlxxgTpkz5YCIcnh3G1U42oIlECh1U50IO4
TcMB9HVuWI460lt932xspQ9SwHgZ26+aJr+fZvARb+jFJHTyAEkQHeu146NxnqwU
zCYPQrLNVguVHmH02nAvFa8AJXzIUJjtdMNbqyy/iYid81HP+0v+W/8UdEjDg53S
QQG2pS9v1azN2z8jW1az7ZU7dyoZb1cXcnqLnOW6Iy94pg4L36M1ZPWpoYxvvjcR
3UpAqxUzUFwx1GqiCvTHsB/G
=S0Av
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:42',
			'modified' => '2017-02-16 14:44:42',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '340bf7b5-1eb6-415e-aa15-d7967f1713ff',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//UlZIC3iBn/SXZXIsR7EtJY9DccIbwqvDO7rctZmDTGos
m2hQvbHe5vf6mSowbXG+8Y4a/H21Hrwbtxge+X3TjT9gx017YQIWZfGYXJWKq9c1
sDdSjfKItU5tQp6KWY1oXpzxwYApQIHrDb0ndxk+AgAPDOOHjvahaRWV5262zjmp
ybbqQarW6TmXcAztSVgm8NMsh6J4/SWbVIrH6BQxpE+gDxI4TzvGYryNFWoOoVbs
YdMnpRs1k8lT9NBcMccxr5LhZx556KI0/+PxfPLTr6loWk+rVfVen7Ut8pJvGpeR
h75y9ddvtOSeCZN88nE+QKld4pIwdBdKhCym6/a1p5uFOrJYg0sgNJSAgIEfL4+Z
0Dod+d7XaTH6BnwkruDBpVilf/YGOek+wXTVvLQJggdOQ1ISZoq2AqQ0Bp9YTizb
ts+O4NxyMZzwSIPuFt63z4hYsyDu1d28+RV2hM6EZlbVhm9CL1kpAFUi3ZEVkUkI
+TvWh5Ob6GOMN9r0V5jNMan5gFM46dYc6qNzexQCS+3C4r4FyoktJi2e8INPQHha
i7y2r13NNHbLzIUNzLweK8hevQIhxtY8l5QyDhk6aVpcjuSnJJXG4MgjXPQkAIMu
RMTzecC2CUfiyUXI7eU0zq5NkAJwzI++LM3OKYoFWEoryznQ7/puYZG+5JMoHgDS
PwGPK4Pbw7ToJxwadEMCx8xADBK2esDmEuCcITt2ntnm8smz+GeovKs4A6DhX2i3
+JrJonCFj8WhQX+lU3kppA==
=Ttgt
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '36fb54aa-8aa6-42de-a02a-024044d00091',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9F2pab1lJuVv3gmU6XD48y5/aGOK+gr7h6CjWBBCyzTCW
FFESiYN+s/45AwjABgW4G0U6mddvd6MNZhzrdP3FRg7ka0TnaWWUNqX6SFQq0alI
tA1kj7l3BJAPPgc29XS6O6b3zrecJctUFrCJbxtc9jQ+h0jy2+1VzsWx7FFhZvTT
/9zxjZbWr3jN6wdYyJDyLYiq69rveQ2MF8uUlOOH3VXuU3vdOxAaC+itUbQ+Ptz/
1khlQpXGPnSxihifaXjKthkNz0lGxsNU+HZGXPa2794n+t2cuyOz/gu8E2gu6Hc2
I7KwONdhd6n2CzyORn6DQd9B11+DNKepjERPEHuEl5LuRv0VgGafIEQSJHjsRIca
bYG8SWOZbLDldY/tkMZpiQuBtT+PMMvBpKtZxFjuypNOOV9IAfRo+rp1faB05M7c
C7NTz8qwJnoEdMr/i9NIZsyqdx7Bz1FVTPyLzJ+CqejaXegfe6OOv83uuL0U5v/5
OefNOkFNkkU0wN7WKJhfgkzOAGJM06DSWFSnpR7hr47P+tnfQULgE6qIbgP5jEkY
aTxSg/mMFZBU88iClMnYg/xKenSulMNOZUtZaAv0O2ciW0fSnAIPcOkblVIu1+gC
TTEV9Sbcw3kiqhMEfiAyUcisgLLaUdAuDDrhJPZu8MYuav6LDJjeOYHiFuWgxz/S
QwEKEuGVfK2m/0iJX0jNR13AYpaHQ4RtuxJ+xtEEluEGUbH730qP5RzWB+NetqwE
fF9wygAAuOcYWKvi+0wseToMkz8=
=c27l
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '37056eb2-6965-47b7-a137-9ff354f88ce8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+OsGR101pq7xyPoZUlEmDmB6WC8W53u+xUc1A6OH3fyJh
9VkQDajXwyk7Se7pghz/80R+DPrBXY6lnWr3FJW6k1f8bzwwsQr+GlxZWFKVpJYp
/PX2Sxo4Op0kph4nc+ZCedXPOL5ROERa+dOKcWIRdVKRVOmm1hKlt/FJsWPrhGWp
4gDqyyw+8a5xnawErqv+Wa0+08973YyiYMWJSvnxfuCsdHyTb6iVOm7TBvCQcRRe
z3cCw4pClagSZKcIAXIM3JNk4jTYPj0/iVclpCBe18oMsFYqkM+2fb3BTV1SWeFF
56hl4CAQEzMEcOG/OR5qyPfVhzUsOKZO7gEGuzkrcVZE5/ekSuFgM9ephx/WM7jd
TfsxWtWVhKQHuAm5aWOm/bGujo1c4ISZy5D0v9tLB3Uf7XDig/YPL89iOHrBHoMh
fID8bhgNp0ZucR4SJYA01dhhYkLcOo9iZCiP9M0OSxoEKw7cUMfQyISSEwRVVzAm
Qe4axKbJt9oNSpr6ibZuY6PcyIe/adk6JG2RoqnTBEX5UOYoF/ozmKBI92mM8Fg4
GTNcwUrsnA7x7B1Jm/cccx2OqiWYA6zslsJzY5Ob0tY9GZhlmvt7jycc+hSug0nH
2vnvdQGYicslqpWyHWlk8DNnKpoRWge+ufJ0HfO4/JWUZAqQtgVppUd1IecDImbS
RQHcQ593YsH6edtTYniS9bqNhdE4mUs/M77Xizo+Hlz2au5eHrDLkNnHg7FgNVss
u7coNDV8l+Epp9QUXmiceyOWfCWhGg==
=7CiC
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3730f1f9-40b6-4ccc-a972-57c3723cde5b',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAApC5ByQz5oxCinvu8Ryg7RJw79ZOrySS/boWrtEJPDKZk
UnClSN1k+mMZspZwf4mVRirGeDj07TlzygY2vW5X3i3lwDxCsiXvUgqM6i1RLJvU
aj1n9Rgj6utwcux1YJ6/utwVOtp5YELF4p31rro4mi19H4VJavJaU/zT2KN1A5rw
Qi2YdgSwqQ++NVSPraVXbmohdDvbpCVLX0dociv7uqldamCZu5mipym077g0HsKY
1GkJiB8oV9iAwYWUrpDSt7Iltbi1HInVMi9WWd1KdSWMxHCzPf8gGm+Trufm54vQ
zH1KGssr3oa9yor6ELJw+w3VatYVeX+rBP5kr3OfMn+fEVfpYjd9DGXYEb9y3UXX
X5VKzvs7SkXeGRhUKm6lv2MFTWmcWU8cHdiUdg6Ve0uMq668faNMEC0GHvWS0c+k
RBkVuW6IXVQkv44QrWxqZixXM4vIgPfPWMmjdNqdaeh0/bdx7IUQWSer71IKcDLU
xiKwWJB5DFN4rkFiqzmzR+EUcRkwbmnOzTlv9qpTrTQ4j8EE4px6BhLnZ2QZX/pe
huaarTD9rP8QFD3uJOo8mXLxOwY64cMcLfIc5rNI2nRULm2AwI/w5Gl4j+cEG29O
K2Zz4gE9XhJnJEkWylFj2Z13h3SEUpbtu17+b06e9m0Lk+Jz8gifRBlRaRWEILfS
TQFvPsSlWXXsbQJA6OeZUM/Rb7w3wBSZ3pwgxnulaP63TnVMYvtgFujEC82DkdVC
3rO5cGuafVXmhyXGrVFawa6s+XNGfR4sbb9j3z8O
=4ysb
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:42',
			'modified' => '2017-02-16 14:44:42',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '384f2423-0820-4cda-addb-383b9eccedb1',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//c2mqQaHdYsWDL4p00ChoVGMAvSwvlW4bX7rM3AJckzF8
AVb98a/Mbm/kSr73NDQWX9YiCgVy4LXmig02N3HFlkyR5JF5OtOBHsLXsZdo5YlJ
+dCcG6QxEi4TbKZds/g3H245kDRuuJYROmbmYXN3DAnKZtkWAlCWeQo+DSdators
lxN8HNK8SO7MnT6dTV3V0zyRYwllNUbzQkO3Imt0nKu/HDMwDoo8KEXIEzz1xKzt
RuYmk0DMPOZBikqliVkzZvJNj6HhITKInPEO8SAz57GLb5utiFYePEhI2TJ8cwv0
wwzJBL1xX+iu6DzAV/9aXW5uaAqx1g6GtgaDfIp/vJsECZRjEXjz16bV34r/4Kp6
eUrhi6rWvpLgETf4Yc2tXOdc38/SbMS86JwuBQRr8wg2oQyWwbj5LjlYX8Hv9nlW
xaUN70YmUWl6n2e0dvAGxirEPabnWWwL+kZLIeF5+M8FilMpLvtdEKgRefKzBxb3
p8R543WpkI8Mj8J4ZCL+tcyBoZG4jMH6gKf/Bvmv1JtaXLv6EqS1SvgSaMpTnkQh
HT/II+rDVdcwMfsPziJtE7UZklZP/ICySmarD0hjdxcyIJr7XNmkufilMWtBbTuO
20DqMVdPccT1aruOjN12H5dGkbW+30CwIhUs8nXc7uDKgsUDZsGVlv7y8DRqK2HS
PgFS5C0n7Uw8BkyGiZmYhNzYZuzMcelkW7I/ZlDeFt/TeKL+8Oe5slLopCOVJRuY
haHZRbFmm+FdBCqnIWRJ
=7IZK
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '38fa3c11-9e93-4492-ad0c-0bcf84e21e82',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/+MW8sJRKMP4SlxtNQTuY0mUE+COhzEEx+AacxGVAollzD
sSpc1KlJByNLPTys4E1uzR8wcrcpR9DhjX4jdXlWPEsIKIpxeHI5GL5zs2tcbnmn
QFSgfbLH3Rggobz2BCbllK9jRYW4GOjc0KBDDjmHvKIb+ZRyQR4FE0knxGyvPBtO
fj9iV5ZsVesHVbWlWeTzVzPKo7pfBe0ou//Kuae4cVcdJf1QGDBKXWKi0x2bPXbU
EI55whcFzwerDZDwLYuuuoxq8RQD1Se6Xs0/6lRDJMjCzo6HzUJH+qIoCbD9lpN9
9UlBbsDx0bOim5npmgXcXNkR6dGS2GslTHs/tbdRDVBND5BNuycHElXTUn3YleI2
MJC7vpnPY6hyXjGXE0hIXb7DOeTH6xuwnWM8BsZO99vYC12wBMjVLwjNN7NiY1V2
u/snKxytiNn64hkICQLbzbP07AWdVetbzgY8OaQQC7k28FZi3bw2Z4wNWldtQJ9h
kDqDFYfmwxJgl21PXxHVOkhWfpbELKcVksIfXJTXy7XqeTIQho7MaefWeYTbijub
7jNbhpngTdUdiX1erwEXnzsRz73K5tGqMQMSaX35WHiEbUO9H8LgUoIM7B189D72
0r5jsqYdMXvrQtRsGraNTLnYlLMSx8iw2CLLO07/QPc1+0vogXE7qx+VJpWdIA7S
QQFWqt+wVWcp7LOqkzoNHFkyACX3kKtpFLX2ubvKpvoIuLHtwh3okJ29/2cte9Nx
WFKumINMtgZUX+f6fjb5s0UN
=NxlL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3998908e-208f-4216-af37-7697085b4c6b',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/+NkgzVFaTrhAQeQzKvZtR0khPs6NBAa3xtFBLU7udsyhn
hTfHnDrQDN/zMxRTT442wnVO7/Rmrj/PPyGZXCKuRAMaC2m6bYhfZNfzpII2ABa7
8XbFMRwN75ZzK0LQ2F/SBF4eJxxlQkEwLipiLVc27cF7JiRHAboJsr0ERxpoNech
6qzYw4XD+jllUo1nhHMpiBIyYz7feeiDRsVPumbUQIO0dZCeIOSPQvKDpA2RK/xI
F4yZeSGjRj4mGJiXgM2yTHlv2qY+oEiVSUD/E9Jaoa0NIzINzsmdtVCXfGkcP01i
tzstnNSGElS/r3p0DKWyw3ocXacO6DM9aLSzlCWq02sEN5FqpgGcwfG6NHImulUs
Zgp0+MUI+DgxOqMtGUEVZ6/6y+AzL9tPe8cj9zZE8BD++saIgGTVZIvIqxHw06XJ
vzQyNbzC1NaLNc392EZJP11SEPSIKJHzhyui6WzJZwgUw/j2wfb6onxTY6MgnGUo
azIuf5Juz0jiPAIvK+R4L/HxlD/MB5MpV9bW8Gu2Ph45G5HEf80zuSe50USUyPVb
YXyExO6RGjWSsBfe9avYM78yqwIu9oLSfJ6D5lBmyBv5nCnZKWHt6+h8wogbhvv3
CRAJSB9HslZgPBHUPXpfcn9KU3KB30xX2d0Dy/vr5yCvj45NdbSgHgpB/QWZvkDS
QAG/J29Nzm7ly7hajRob6txTXOdHx0ilfR/nQeCpGDwQBtBO5d9xAbCcMLn82XpY
d3oExJg9LOvJ3sk8cA3dNpE=
=isC5
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3d46dacb-5b81-4599-a23c-d16edb6dd9dc',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//b89jU170n5y4lf9GuFayV2zG2fqnssxxzNmxJONoj2vy
wV73/QBrl1m8TYuHoq8V4DTJMsML+KsZGUO7DFs5hxFD6558I4fGYhydsy6j6QaX
MBAq9xSrMdX+YgjRs6Vg6BNmyaYt6nAPeFWZxBnVvW1d+NlYq5wNDkopljp+lujl
Qj7HUK+X7vmZ026nfOHv0wChOT6ZSClIpxNCiLuS/3oq7n09jwx/11WCE1bQ+RkI
h4yb7Xy4dbXXyMQskbfDoyuaPCzrDd5maVeVC9pDG54Epu1rKB4qv0pBg6vR2UaK
Y3V9N71fmrTJOsdL6fkF2nR1/Sf2m7sZS8mG4tg6NZlEIkaOMJjkkMr06td2PB8f
lBDirrSB098ALlbiUxSstL3FncGMfelGlaNeNUgopN9O4q8yqFuOdqyyaDEiBykf
VYQfA7CUvIg7caDE/URKfgtdZMxbQhzgRwUCu6edXcO3bX/iosyBLipXt1PfS+kb
4C9PCu5pjJcAdzRgkysP7brcvHbqNtblV4fmjbiFkhY+SmjABn100Urv+TYuP9ZF
7nJasBOGBl2Uo2Vv3TLbaWV5My1QNFgMcwfc7gKwT69HT5P/gWbmMhf84BpXe4w3
wnrEVzM7E1riaL9oMCuyWmGYaM7ksEjs8SBzKmI4WkGTxXvHesrw2jBO7afypV7S
QQEL9Xu9qer1Z1Fi0aFqAMkKfZq8BRFsnwJt78Jvbk8s8Jl9p+/UKh4Xq8s7Qji5
G+DSN7KdfnFouKz7fCf/2/bg
=HZUz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '43414350-2e07-4561-a2eb-2b696f8d6c6b',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Pp6NJgeLs+Cct+CPY36HZ0Wo7yyhytapli8BGq+ABUlj
Po76llNJs1qSmhlhu8qeEJPSAMgVvm+ygksxlI/TFVK1v/2X/bg0fQtKmFNAxB9V
GaaIaeGPV631O9LWoK7ZqBKTgqAeC+6jj7FMs10qd00LSn5UOprod+yes7fwYa+D
oEIt2E8Qk3tbqT/dKa4UmQ22QUYkewRW8dqM+3RnyrrxgustmwuxLPrk9NcUjabj
mDCmrCiWHesqsatDIYiWxHP2MGCHu4YHNU1nd7FNLxe4UqNmhTC3e8qHnM2qIIDX
Fgo139JRLqQ++Cz994keQs5N90q4llfNO4yKr/SSZI/k3ED2Ks/tKeBku53obSoC
EGgCuXqu8k2B7pJzR6n6MJ3uZzwaWLyLDky0I7tsjBoN8szi4hXhy9c/u3yJdtO4
JBV4jJlTYVH6RvTYWUQRdH7DPW4fGtrvJ9K/fLnIibI0ZiqkTmI7hLxKI6SlyYzk
h0Ksez9EKnkeKObOFxrsId95z7hVJP3MfCLheHSxBpS/XMjL/T7nCCQ1GSh4yuVt
PsfGRrOE/6n4pOGqtdGwhmb7mJ7Kzf50wPgm57zOH3RoRUApHunISBhTQb+CHBjU
3NHPFw++TqomvtXK/h7oeSiNexKct8bZ0sZVGIN3WMORwb2ajbLONts0ovQAAhXS
QQHEo/zuIv4FScjQ492s3SoD5k/shav3zy+XV2HWNYJQKktA+Ch+laEMmo54vh1L
gbRS1JltkDYnBN+6WmjYg4Zw
=QNiE
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4398b4a0-3763-4643-a023-b8e611d3a5d3',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAj4IAhWuMCNXEvkvIWOaKofj5RdXvRA8SJw/GvJV/Z2Mp
trlC+6Px8mdePhBBxWc4Ks1kbBWNgx/uAEU8tzLxiRwtmEvc4HHYTCX1krTZFmZn
mI2UmvCHJoxuvcvk+wvFEAS5hBIqlDCfNSu0FHTGgDxGDj99OzdOYTLrXnNRSnmE
PQYnvF03jZ2+D+w0hDnPOCNIpjJdSzXxvyrsDiZGKzGH7tLKgLZA1Rv1iBhwHJJ+
t6cgeyYrHHqrSP3lrQwEbEh3S2urDCOSn5ZsJa82k7GwhrhS2oQ6ldOAYMFUGVwu
rPdXfBOwdYcoJeUC9EZmaC1LUm6qui6S2/GVh1P18NxoNvyfb7kPCb6GCEadP1b4
Gww2zes4ip4BCmDA7jESBm3SgkzM1zydAY4jKbMI9xcTu09g3tdIIBBBnnURKHfZ
KSUdzAeUEyYNPfnlQbEzczuzKMDZJkQ94RD0us7uQsxGGwFztrCfH3JdEW1lGqh/
0dWV3BEtvUJBSp7xrOXeHEmYpJhFKREvbHcyDt2LG1gmsWIcogyIUifCPr7yRRaB
VuTdlXGV5mMW8akIoT4f8igXls0CgO1SCKt7M2Te3vYpkvBq7vvBNyiEFyKHuAiC
2DddYzil+Iyhc3Xs7EuvdAwtItCo2FUA63syE0PQ28xlbziJmEPqG/L+AWqcFGfS
QwGjTKr484nXgP10fAFpWioPuMHjIC/hfGdxf9R4gzDP+gohLX6d8wa3SOXi3AfI
UB6v1KBH6McHo3uj+z68pIqlKuw=
=5fzd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '440cd9f0-7b4f-4771-a91d-909aa50d3183',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAlgZjPULlIbvd3XYnAh7O1kuZg1pq0B20FVcymXNCx96e
mqVr9aKom49J6e5grN0C+RRabMLJZRaAVWHu79Kwt7ADZ3E2WNW9E6a0Ui+02tZ4
/ZQy3dp3aP6/Wl4vh5rnkH5wQVI9hQWYlAYQzcYrvYFxpUdAkjJoFe9+RZ+1IylR
9MUu7YoVS2NmKcST0ad+dqGdCeY7rbiOSmbH9mdbho5Nnfy0usCcOXbBA0MYqX3W
FS+4iVoJBOpJKp9UgIW9CQBG7WzBaRNKxCrIjoV9b3qNKSYNf2rUtBBRziQLCtt8
cPtTieSTd9mNIK+XD2ic2YLoxr7Ii1a6FNCFvMSglJmHSNPNMg3Uk7IIiylbJ8jw
Vti+uGDtJxV/xUkH0bTloxZJJediQ/1HAGM0vC1jDrz0vYEfjvOZpHS+9l4ia6N6
uQ4sLHMvG9qL+f154t8rLZ7aepYJjgjjYM8bmm7rWSNIU3UtzosdeVGNEOFgtNgo
nd48Gtk9UbPn/aixSt+n0mpx1ZJr4PC85P8Vk9YaMoDa/X0zz2ALvJyP9DjACHd9
M49hFASLCd60Hi6KIyAtXrXJHZCeq8Cx5SYpRNrvXOfG9xzanwgbR95QfdMUQQ8K
d+nW62fFpbiGuJBmN/3NlaDxUiNtZtfaRvKavAr1B5g3Mvts1SLaVSAIVZUZRN3S
QQEqlDQN2rcmb+8GpiPU0gTBZQIagFtddYRcFcobP4PwrqKEm/qCr+hWC3N1PLDV
SZbaA/VTp14qTeKpVsnPniEL
=KeyJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '46abc994-ba1c-4ac6-a7bf-4399f82cfea1',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAhegJCAJUG53dYFiVtibFghK8M3XdOEWhXbJSXTcWFlFR
2d5SwfVK76VO5xPUx7fktd8zajTfScNM54cStBFR5+Ns0D2x3nfqQBwdKDxmCMOn
0IhgRMHIEuThuqih+kl86LgYKFqjgEpe3ERki8DHV7m+jOjhb42qR9rMG0sdaXkE
w2gBJJnVBYVyXEnFcAyWQHwO+ZATJlW1Y+gXkQIxY3iIOwM9dGaNtcGmFqU0ODn3
UQKc6/9eMfRi9bW49i4Vi4gZdXhXrVutWMsYXAolIr+yDQniSFASoS3vNxExZpGq
SccIimPsnAeEz3gf3+cda1zZLBn7hFs7qMgw5qlHBD+GlcUXybAfgN3FlNDe/Ahd
bri10MetfFzHCW0KLZcpZ+zirGxl+moLfnmX+aE1IWrkCZA4wUN+V9VKFbZn69qm
2hlgJcP79mC5Rm9tKV+v+MCjVIfJcDpnq4pxU5fQfVWJUncstA2SMdVr2jOLY7C4
cxsKEwvr6u1ICaKJncavQvtpB5U3PcRTfBMtKNYhYTUseTy95/hXE0tlaqaKIsL1
XVN/cW8phN4dRV2rl+qRR1EwM+ouBT2MLnwXOimL9H6W7ysRp9EjUJIBNHoMlIDa
2EQkavTK9uJU1t6/tO1asUkWuA4ivxiitI0WdnJwfVzWOcQwgiHyC+l1NkWV8R3S
QQFZnOdpSnqh2icft/1hwUW9PJgbP1U0OFztaVGQbo47VubdjCWk0bDJCDTRUk+t
K5ErkZzSbs9A4TDNdW0vOq4Y
=3As0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:42',
			'modified' => '2017-02-16 14:44:42',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '46e13988-bade-467b-a8e8-e074b19cec8e',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//WUTI4zDFuWVd5YOAAY6IJutik+6Cy6Z9xyDvULrSAkBE
1IRTigteVRVizwhVwsntQ9UhTGeHonmeXIBnWHF8fIuAF1M/2OkdeYuHpMaYqsNc
hcQlIMYvk0EPdlMzbvMOopvayKlrWrxVdHyTRpzA34gFWHLaKWar0y+NW5QnOxV7
ecMoezYsfIN7sA0IZpoPRdTjCrXDik/1lSfwRriojVupGjTcHONGOihBzqhpAmIU
RKOu6Xp+rTVWyKZeuUkCNdVunlh/LTYP8VE31wlG10cP8C/GqbIihe0C3acoZAFn
qxk4XnKJMhInlSF3I4FiO8JZzWSvZk/v+J0VAANG8trEe+nhohNnqmyl1iy04j10
KMP3unImJBKekAtM2XiqNafr+auKuRc0pSimQWQZx7FP6odsUCbCh/U9vnx8Ndlh
iKJwec0BYAw6hhX8qz/X8Lv+5pt1WSTQnDliSHQ0vhbqdb/xsTbCs06PIPffw+ES
6mEjBb1J66JqB78L9pVac6ueLaLAkpI7gJnP6KjqKX7laC0kwMO79jXwGPuG/6tn
qHarqpJQO05aNNaAEbwkiTR6OHSCPHbM6RqKBBNdO9PQ+vy/qAa6pQchEI+sXGEV
yY1cAiWSIoj/vS3KHfju289en3ZHG6Cg/4ZdP9Tb9Zi/t8AaQoQ36PjYqWsNxt/S
QQH9PDMAqsioG0kNv/73Wr7vfX6mN7jb1MUPVyigADPzPc8izbx71ptg9K3Zbsdz
tmfW2hbILl4JDrXAEGr9wQIz
=wtHW
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '48bb5d88-ed81-4207-a310-2aa5e4a3da1f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+OsZO8yOqx9C4uJfvni3naBL/743JToVV2y1/Jo7oXG8P
PcR6Wh81Bhhu1KyE0LUKknwMD5Hp3X7UqG3dVQ3gk/Qk4NoSmh1AqFvxZ+QqLGu/
vjwuHVpligIBJ2WHNcL59+455gLgicXUReatHvxuQ1mqW1CtsnT/tupJWxPlAQnE
k7x/J6CX82PlEOc7pE9XxDbIuSQAK7ekjnLrI8H+gh7MGKnEbUoeVPCuvwAQ6xxO
eXKVYF/cxf7oFiHWGamiKPbz8+tUoIpmgUB7vrUMvHP8BdVf9biWFv4LA5QaFQZf
BkkfQOrE8g83RdfP5FuXdo3SSd+VBE5HkE8eQXqaYSE0zxf/N5t7QHSgwfEd7Z20
Wg09YTTjkRQ89sg+hCk6J5K/NVP0uesY1XXucu2q8vE5bZaurTCsUGUYdhTOnGei
lZGPzBPMZuKA2b8sY9tWIMft6hLiftto4JtzaTUXvtu7LMje6NoxfQtDC5i+MjM+
/JZLKSMMKZKuq6irUy1N1Xa/gB4STk7axatlWjzwUwhh09f41p02OZWdqH0f87Cf
c7aj1AV8UdtKejKInm0mu1FF903PKOcV5Pi6TNEEer4Q6GV36GXjZQBHWlIp6oo4
QrCVWL6RbhnGaxnq5aMTuzpidei7FCo8zWUGMIH8S5J4KG41NnJpJtIF8n5DzX3S
SQEDygdaiLUXEmiy9tDht9zIYb0lz8bGeOkheJFjXXJZDSnZ3aKz+Bxw3RQUtfLz
E3Nj4FleCP3PWevqSd3z1cKNXbP/Cm9/t5w=
=HdaF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '48f174c3-b72b-4097-a804-85f36504e04b',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//UmN1XUZlctPg+5m4ygNzF7NEWLR1U+0WvPuL0OAIAkD7
RljKag33nQufLwb5buS9MgA/st5Jglj984/GNZ8n9qXVNIH9nsDz5J4bp5Dv/6tt
Upt0Qv4F4/deMddmtinaAlEIrLldb5MWB15FOP8Qho20UDnBtCqal09tP/JQzgVN
S5TF/5LGk0Zi6wTcDdNPpwIjj6kz+nkrTVT/WnciirIEeL589jx4so1tzIMD4/Kp
LiwrzJIlwn94t91ufTekxt+qLkHPFpON/Sd4gVjBeaH3CLutr6GMuAej2TFJnFd4
aXJdrpyVOX5xbuvIrmfeXQ1b42xRjRhXTcfU1LOhtc2qI8fzTcOLvyIO9D9ykB2T
VBev0Hf7UnotuJqlUwJwFkyYXptKFeTdLAehOOx6wdAx3SG5HNcUxgyVugiHeIIa
R9j7G3m7M+H1kZWuNSg5IwdDnRt8Pwi04vcTwOoC2va1g3rJ0zTwPi1Nk+0rPtYE
v72bMaJcoEVXT8un4DebTGXy1W5R8deta8NuPTPFHcf8ZsZFQ2Spnkq2u0icTiq8
U4lHBXgIcgzV1NsZH2sp3jIVJKyn1lSEtJOJEGB2izGmHFZK4aU5uOTFuBCIDSIF
ulqW5xsgqS6K4AHI5G5qg2CPq5hVkO8wZ4Y8jid++rG1FK8+Th7mGO7YT55L5QrS
UgE4Ir7Kgx+2YFG2OLgZdp4QfW+WOEbXm+MEKqdi5uVI51wXsEmHj1grlATrRtnT
m7rnVS2YbvEty2H1KzoaTN3b9t7pYHRwVBGA01g0JpgDUYc=
=yDhe
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '49a5edd9-578d-45cb-a9b4-caf304a69348',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//ZjQ2lu10umpQHTTow/zA423atVDrKkaMBbcaiRpBcVT2
AMEubuCqxD6JJlqoqWO02juqlytreXgjhJ5BAj93i4n0uWiInfZQPPjtNCSSHiZk
bGKIfJwCmqquJu4e+t+uLhjQUu8DWeWn5BDxk9iVVQN8u70dvGGsuDw0Roh0hWFU
LbdctANZ3K+BQvH/T7HiHowHNdp4cI6YOJ8tri8O1BvMfuvAagwxu3ESXkwVs2BH
5CEz+jsGQbtTy6d7SpPP0dhQ+Mu8tBq0fTq7yFzGKk0Cm46DA6l2/Gb4gBaTKO1b
LwIhrgVsUGwzUPMWtlIoQYemhMqLtfvd4GJC6G5N4q6IuiAmJxMyLDQwLOK1l/bK
Cyz3V2vzK+aKpWoQxVMqzi89lokyg5kZQenXWeEeZmHn2QNDDiuQMbXpxHElMSlk
U+GWejg9llQF6Kk2c3Ft7b+JHNjCDP4p8Fu+Gj9PaOoQA5N+/pscBsmTv9IDpJL8
kHAt6UyLeHLh7Cf2sOdAXDfDcz6eJtO82UwmnF2HFiFq8P2ZTLleKVGcSySflJn+
scA/u5As8Hwj5aMKNNSgKODxZwHYC2gqlzmq3AqcWod2ExOfouK4Zp6dhQ9AbaNM
diRMqSqTqYoel1kZ0LokA0hZPoQrLPg23MQbaaDA+y45uUQQ/XUhHytMam0pRGXS
QAGQc5kp6hpaDBRdyESWEZpbORPIXpRUzUMjZxgQwv0HbAQHE1YFYq/8T+uxm09E
IaJ0x5f0/XLz4NAECs23TnA=
=5pPL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4b605680-7b5b-4f57-a88f-9063b87346b5',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/8C42y9cu+2nheji7DrwzOEUuH+x5z3Q77U/Eu6jqHKgL4
TR8kWJn61YDP6EchOt9LmZ26EvMIkntLy8BYcxEVq93SDStD6+1br7q6l0ueMeco
B5f6x6nnz8Yqhhh2GMWcLmZxInB4jwdgkL9bPZyeBSq7r6ffgHHWL4iDyWux9Y4w
vnOOgFeg0WFFdCQA/lNsHt8fsingg6XOl1Z5+P0edmZcC8JJrUTCY8PiXb6KWz7F
ZBAa/HLRBuX4F8cGJcvRF+EpZOhiAJw3iFnygDu2Itp7cokkZNq14lOJe3Q/t3gg
a56vbTvHJkPIGMFFPZhFs0HHQuC8hF5dZUXLeCuDMdz9Qq+BGgnq8nF1/pSRHxgD
alj3J6LQP0Qj5Ia7FCwG13PO8+fF/OWvt8XZKYO090SEJ67V03ELjT22phvXKEgz
4p9LfHCYm4mwT498WUirYPKrmbq7BAtwsdsaX+haCD4LnJ5FArJ/8M429AUnmP0K
QpY4tzdymnzDhGf8Rc410cttZ1+uKqz6fYeuIt/Ub/hKQHmFFD14dJLC6Ot+6Llr
cwNGDm0nd7yNnR/6pw1gohWohZhP4SFIAfGdSBvJG7BtP3L59drwnZO2vx1vW+pE
5kiTVOg8CfqIKl1khofzLX2joseD2MYVNTXN2ZoOQgQ2gb7typRWPnamwxRWL6PS
QQGmDXfoA5ntKZnuWJyW6hGknqRNoURXgP2AfwqUe4CROzrfjVZIV89D1/NvlEEX
E+PCkSpz/qzhxzkzljuSY6Mh
=6MvK
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4ba56c37-29fe-4830-a460-c3564a14ca65',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAgFk3jSqEUlh5VKqWEXIKRVb2P5F17rAD0x4K4KpAQXiY
mzrVoOoWfG6zQIi06hnoHf/ztVsbFGWKRhyVq8FphDk/kFvBtK8YRaQhqJUi7P2T
NE8WogdX/Ll1OCRW0hd+pwokUKh7v8zTheuDJdEN4UJoDZSCmsKJNSanESHJODOb
L9wytjC3tybIphhkT8AJHYFHpgps16c4gbXjBun5/bdC4wJGE+ipaxomah1q2GFM
K9sOl0mNhzyRkWeTAZDMxOrXOgpGYwPDsIyMQECvO00529D8RExVeVmN0pL0rgXR
C1JJaQCnNyNMDnxZgYPvMx+qjX+dI6mQydVL686PDpg2EfNkDmPnsbP6NRRVUQLM
yuvS78QzxSZGZw4KvKGKGK15iNDrnxaZ82Dd+NjIhlGf58r8fCFeTudC+Nw0OVIx
7Cm4KgNaGs21SNH1Dohf2v+j35I2bpHyqnJAnJQBZbg1oyqmKBOOxLr0RBgLMUAN
iubGW/iwMsD0qEb3//lPVeyrF637g12Zzw/U56Y3m+wPBOq+2bA6h2xjKY/18nkj
wpxO4gmuwdjK/O0M8GYg9Og/dguNB52OdtzkpYjOXk66y+MRrxF91nYexiRGIW9A
h2JpKQK44jsw/wibmNrQ9ieeLrEJkYSrqTYUmG9Ph1X4FM8XCDK9FRQrb1iv0aPS
QwHKGlzSNOUVZohMIbAS6WvRjHuMMjuszUzRIydM+vz4xCMOjg563ftE6eXH2gp3
SV6VOslYt/SpnyvrEkZqUapWz1c=
=oPuL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4bb59918-2c09-486c-acdd-2bd55dc3536b',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//aCHmoY+c/iYOM8C7gyX0Qbb8WPrWBXHWQK5wUCPSgtJb
lObsIKvDguSW8vITmV7r39D+X/hCnXdIlfQ/5GAI/M3Oz4y23DRtRKA8lqsU4C95
ksJEUDuJoK/SMlGOAH2U6Q/3MQxXOlk2pcXsMImT2Grhu36PpyF45wvxM5DSafZT
ueA6vP3v9RYUV294nA38FnKGHTt9bzA80QagYXni3wI5zxYcoVoHpgq2NIGLb6xN
AVvZCVFaH4u9efMg/etwOwNnGtkGxq8JW5lwOBhDg+iunKrNIl3hXwsKJ5hqX1gO
9SoHJZ2XpU8KQrpKqhZ179tAEjQhN1lhyoYOrFYyeEbK6fkjxOzi7hRDr4k4wuaC
dQwvEXNVcpCEJ+iLOAS02Pca8UYUBmI0qAUp22fdj/GeXHzVKO6qdEiolxil8D/s
wlmYVKz5Ir6x0TNnOHg+lOGQzl+W9CAm/Qw9tfuKO4SuUg/jXCR/4jxkpmmoUG16
HQzMsqPTjmsOGp4JaJzWm1FqdpubtBrTkXIsIiQ7IsTdzSporKfkWUmIkQhUgilb
HqQqnWuazwf3q22eca9y3PV5XxDLvRL9q6NRtiNH+at2fm5Z7OMlnnsM9rbquFO/
6driFAtRTwiLReYWVjmPsJo2dMOP9wUGJ7TqEwzI+tcxyKz0WEkO6tB1JhqI5IXS
QQGFqXKIx2SKZK9T8cFGK2UFzYczQEjVkEV1X2iTOF7A6W7FkDK8PurCm9j7IT+x
p0OO6+Ky/wZwuBYPIq5HJO6n
=5K74
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4ca3d590-f374-4687-a1b3-f6ec40fba77e',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAnLu4S3rut8stbpx+I/So38yGWNIn1jrjJU7xQ5xqdDuy
f4bwhdFyOrixPSE6ZvFmOdP5QXfMtiRYAcv6OsSR6BSdYA0WuTlD7lfohFfRvaRx
7IzYHNYWAyJOPu5ByrvNY/c3DlIXR15+ixW10MKDhSnstIbKaAgR8ZO5JEqa2++O
wUp5FSwKpfvuwnOXpap2adbSLWXZ9q8huYL2I1S69HM4mUdmozKv+MpJ1fXqMYYu
hKglFkyAYjhhD8q8AcTQ7X5DxXGB1C8pKDcSPsDR+wD7B1e1RLoACilIgIa2JbzK
k3C3T6yJu+gNc2LllbdYVKRQvBra3XYiiz5Hn1Xtlfh54ebwBPo601RnMf14+XZA
j6+Yl0vyrTb5wHqFo4G3LXhzWV7T/Y6x5tNGRE2r2KNzoHZFR1s5QD+wK0Pf6ppk
aoOLTu9QRi6hgaYguYPIvmaIjH3BOi4lWxqlfsnwNT8HjRqR3+NWaRXbasnXi+Ga
1j4kUyM9dO/A7tYJ95ESN+ySNLwQ+FSOApDErpIg5Lf21AuTEzGl9D2bmVmpp3h1
3ZOxGh/pxFx0FECan6cSpJJSdo6W26gc5jK5iuJuq0km5OgHT3RnbXO3mL0Oj9pV
BQ9E51tLSmhWObXQRcRr5NEL/hDdrJA8O11VIwzKdCv3ySwrC5V6BUPMrWfBP0XS
QQFHaMXhDmY14EEwhc2Ji5c70iDRt+SG8h0jCRGnRNm+H0N41KDUQxvKS3+ihstO
njuOYTAF2t7ao2ZTxVTTjiGF
=/zBo
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4d79989b-96f6-4b66-a378-8c10ab2b0245',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAjUjL3rQDbGyNdTESGycOAh+dxsH7lUmuy1DJ0+8HrX4I
s3JzcgoqYvZIl5d57mS+KW+0Lq6inMjIt76kHJZTTlpy2Tapc+Wwj9RptQ+mmul1
6Ki+a3SkXhT8Y0oh40cNZiOMNACpH9iWwBsPmTNxiQRKPuDakKZS2GV7xffH37Iz
16LzJ+s6sD8WuGeRUDCZP3SVUqSJCj1SiJHOO/Tg2VnOvz6jr/B5SMBaDZ0q4RXp
DjKqea4GELMPsDdDCJCoigsGEXqALXxpJYPakQN+p9wvHdJNit647SGTXuZf3HBr
pNC+IG2ygHLSw5wcrSPxw2PNoRmNfAVap4qN5u1W91brjEOWokd0YYMHYdx6b1c2
fHnAgjQu170qdomlYkVffTeIp7q422BE7OvhVQJo4jf4BEb0pAQ1Eu12aj4ReySe
o+y5duUYPhoyF/BY7Hna98jOVMPCX9grUAUZLVcfEODDyWvsrH480Tk2HQlXCjnR
31BIJVbMPfNRCuU/zRai4kiW2GGrHWwmFrobdOS8mFOcNjsAFlXfh0D4nzimDax9
dA5QYrzVlcr9uca2VFc9w/ef0zOdaV5ok2KncWiNfcyRpotI0/2On4ilimvq3Zzd
K5uioFSyUDlkLZtT50VK65FXibFHCZ+lfTZqRCy75eEwndsfH/2xkEYCRhg+vdbS
QQFzARPWtYxlmMAiXuMK3ji9ZhPVYDnjYVpvGKXCivhddODKHEkLddNAUUBN9gGM
duC85oD/kR5LtKs2xgQy/W3J
=mlKg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '51373731-a384-4f98-a036-6f94e59364fa',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAp7yomX6qEYEr80KTjbZ/MNC8DcomSigDoJ0ir5OTrTDh
rLSF2pjqnU1V/tHeu1J+TD8uKxWtQl/3X7oJgL4heTP0SMSMLIMiISM2xLutt7AU
o6KnJvjlCGEkpCluQJS59sWApnpPGpaf64SlMlroF7dL29m+qS8mtQAb1EcULWlb
YAlwbU+Sfyx5QN+PawJVLovEnQlJxxM0uYJi7TO+pjO1JoDA+CdNeIR9ia/gfoza
rD1qN1V+QDebhsw2qF7Kj59THBh2EvrazpShh3gJmdfd4+AGoyGGW8gLdXWMQrqe
vnxJe0x5NrGj0HcJc5B9lEpLPr/vRlG61F0d2AcHj9V9ANhEjQiQoCDWI7SMJziH
5xYj7jaj6HHYDUdIm8Pba4HHyG9Dbiph9t2MR3cvvWRHaQ+oy23Hq2tpVRosJ6ZW
cuz96yHgS1D4xLhplDlI8/H2kSTEUYY8S0Ql3apUvdNyHQRBPJQxfiq5hBIJXVOM
Kz69zLG6THHBpxcmthzVQdCyf1mHLpQ4DvYuDm/fpnt0ztsyY2arLr8UR9mZbncl
59u/OQFzMKkmEYnbk6krMvG4kyPLf208NYMF43EZjttaX1UTUfwKY2iJ44i34HBp
6odKcWzLzz8nnvxtV/xohqsVyNhHG3IglOYWQZZ2yb5HIFT/MyLBQ0wLjJgHeBbS
QQGwuktU3jRU+s4v0gvLPAEwA12tTv9hb5gHjKNOONeD7V/d4WlpGV0aQPhXadXa
AUnfedqkc/8oHI/GQBWKqBSz
=H0Kf
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:42',
			'modified' => '2017-02-16 14:44:42',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '558a2002-df86-47bc-a6a5-e9482e788e29',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//RI8C44tXxE3Cq+4eOnd4sbDnnCDT/zcaim4D6DwXKAAL
C21Ig7XHmjtJjNp67kWDExwFxNU8JB4cwYQATnItDwgJ0NREtM17W+xxTGpaW9KJ
iTeLqf7i5aRg6c/h+9kOgeoILaYXbzGAumjr9aqIzjtcSTHz8T3s9NeKurDpZBSg
6NISD1Ps4taHjd+jbvzEyrDliNoTiIB0jYztIWvbH/ALlYztaOMx1W4Y/ubIs9MP
sYDx/lyWJE9BNnmL1rr7K3Mb1EYRc5yBSah7fiBgZSZFu3ab84QVzY2n27Wu0AJc
8LttR+kzKpylYpcnLptHfT6jq9XeJaGjSWIr4kzWAet/akhiW3a3WHBv2N7h2VVe
y60l3IViHDo1iSvEAMeW/rrcEuyXbgkSsXntyDyKM+fb0nxtEGIMXUasrQprHFev
cqUSi7+J5TMiokA7ewFQ/539Vl/qwiV8rhxjb+D3MjjEieDSoHCmrs+/goNuNyns
z6RK5yVeGAliSvD0gf75hGfpq23wk9FDiCFSRO4AePRy8IS0oQmGcS4GpGMVuRTv
8iREJL1xhyWoNYi6mHOVYGGqr3yBamN/4HEy9+BzJEwQsxtLwoukU4QvyVWiAlOS
0on3S/NZJfW/r7h+faF7t6pWW3LD+vPeClT+iDuiCJQF7vTfKfWFrJd08u5PSZzS
QwGrDguUJZVSfPoED1CnmXoI1oDQ+kZBzc81NOrdeKLQd77KBSb8BR0uzFXV5Dd7
EnlPUGcXYC+vhBdEz7yeoOpyfrM=
=Wu2P
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5670e7de-7eb5-46a7-adf2-77fe4971e992',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/+KnNVBAN//ZiLKE5QMpRtn1PL0CfwKjPAFAwSyKUaws1f
uEDuvB3bBxnFk6djnPrWZm2UleV8QTqq4BvF8JpuWqRnrfol2U9wvlOHNemxMnmZ
5jUw8BdUIZMQbshbrmDrCX0tg/MipMu8NbLqb1XVdxJ+WmUMj2rkggAo4WbFSh9b
CmcMc6R4PNH18n1e+EoiDC0gXwcBG758jJ5GOyOqIWpWAz9WDi1Zd1tRm7Z0nhTt
IhpCYuzEzHeGUXrqisqWONu56npTk2ihB3oK9xk8WZpFdf+gE7WWo+PC9x2BtKka
zu2auTDAq7MBJo152r/g/bUWU2gGuW4OKzSzQIh9in9FE/lsHM2qYmbMdjVx/SQL
7Dcm0QR4tAHqk7zD5Uu49+ADThVQby+8HAFX3UxoGVf/+acmv1V3I5y9AX84YkI/
p97km9QblJuMQres+w5BXohxCw72fPe4RMLN9sv61TDfoC+OhAMYu2MM9kUXIsWb
h+eX6QvEnuDsbhyLGKbtIMIbYc7Mi0PqW6nCHb+jE5hg5WRuK+x5c0o1r+y55qsa
jZsNrRwUJE62C4Yf9peI+vy/hNf/m0OrEL55e/h2jyPA67fALQM+t8qyjBbuqpjT
BGAk78HsC5xrxbI9qwTH+NnaRf9H7pYL08N8qJG8npWCK8TytWrI8yjsvls1lobS
PgGUsaFiiyAyVTPouBZ5+eHghwV5pXQjvwVLKhi9m+qyKD1RRN3frvjTa4YtsJzq
SPyQnYBQW8BzTBGPpYVz
=hn2S
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '599b3b19-d17a-4655-a033-11416450cfb3',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/5ASp6O0/5XGdv90wCyoON4QtRCcBrP/gs8Tc4ftSv+D1G
YvdQcXJJbp3lNvT529nliKm5jGJD+VJAqyvYUb7cD7K9qwJVEluhTOIbG7gH8f8E
ISorCXcUkYMU5y/4Iv5ffCYC/MywO1aCROFrP8rAwmifnP/oHdozdIJdx8I73RUi
eVl//9lRdfjOF3UpCapktwy39G4WdYHUdxhB1gyKKeYmqRqIaWOjxmagOL3/DlHS
drvF4qYpF9RqYCimnMjfwj+gu//1no7HJDiVDYH9lK+Dw6k3GbTVk1IvSD49Iqr2
YfVya5UQqTOltKtWnrUCe+MgxgmE5ANa0D+qGF+HoMsXB4QkhEUKiu1p0U9d7If5
KJvnQ7ZCU88GIdH0DGu2iamiVLPviPp68DVOPAqQT7vXqK6zNdslRt8B12YtnxbC
EzbyL6xxD5t8Pv7K+D9YkfuXLiSDMcEFztMhSnlcWhi3vR/4KfF6sBPrmAVo8hQ2
O470yc7uuXgFCeeQ1OaKQrNL6RjxKfyTPxGoK6fcokpUsiG/u3HXGs5oUaisHpCa
/oL9ZtN2lhnHbtHX7FHiI41cxqCLlDuUIxgtac46BSTXfl5rSEjQUXUFIBCuy+6k
4F9MV83nY3/1QVpNb/TBhLM6Y5PhsFK4Ah8MxZsv7E6b+62VsQOVDhoeu0poQULS
QQEOc9vCeji9hJS6qpWPtMCpKO60Vizfy6zqIA+UtMbExdEZ3f0zLZ5rxiVD+RAp
CoyEXjredJmfZEyP/jcfHp71
=tiM4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5a228d39-f91c-43d9-a8d8-78f45c669027',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9EdhfHrJxX3Vcd+/g2IFsKx4cArKZL0XY1CAuA/zFF81a
DNhd3qXyTn1KLsqdVKGCRjUpdgHyEvMJaW/npQ7rIbXJony5nk+tA/9ai78gsN6T
0qMtqlwPyzBtd/UJLFn0ZHcWYhBfxS+WxXWymzYhZRYDhMaEUvlFzYC3LpMG3v+P
r+WDeY5E92/Uij3SNcRqOgA1nwzM+mTH9vvUNARWOsnos8fEAqGUrqxVv0VeTfGw
rtmCEq+BRhk24vkur8Y3/pfBpqNBFHp30CP3E2LEU6tA2nnG5sp08OlzKuQyaYHk
/1zyPax8wNVkQuGlpJaoovQkYU4ISe6kElC/T+JVKxBz9xyM/54RIRm9lLxuoDec
eyXoQMcSb7o3gHklBebL8aL56+8iD+PfYUPbBJbzBpo+ubt3KEA5YLvDPdVkw5fT
rVHgpMEJ+BrxKNv3YC5diGwZt2B9Li5eZayXJf9THdxpcwcIhvuwwecltq1TuXmk
9xMUuhtGNMMsDhShI0MoSwLMf5x3PgBC8mk3hwsjEUSO5fKMeUoYwLMI0PfkEF+k
7Tr4D+wSlJxC3TaO9rhB1gcQfjhy6GSK9q61JyoxYzoDUIAMMMtBSzp6SlBA06d/
YtNRsrNTQyXhe/SkAxFKnsqAoTX0rmwvdlw1CuIdnzZ0DAl182W7BDLChHPHwhDS
QAED00Vv9g/jxkXrub7gBu6z7l2K8FeTSX1Rv+ILaEc0ZaP6spedcXrhVDq67DpM
1Wq85GdCzA8uGnjLG3ejl1c=
=RyBe
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5da007f2-79ec-4a84-a1b2-d5152a8c6faf',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//axqP8lJya5LwCQCMhI367dIGvwrZc6BkTZHX9Tp9SxII
rW9/ifYU7sKeA/vrtC3wtak+WnDBZIZ3ES+coYfNttwyCDFX8A/BhOtcyA8oB61J
AoxbIM3O2VvtqnNwQANpJbJyr+jzNaaV6fZG3mTNvWOamWFX1UhUpORVZj7G7x+n
ONDNm00zQ/BmMal6ukFlKa2Vpgjh1+skFXu+onX661qxQexXoQj3oB8cdkeK61L9
afb96oy8DRZLaQqVzySef/dwdpEij2C8Fsb99TdY6iTmPQXYoJKvqxdUbxyhVkI8
BbY7cb9/zMKSeR9aZJiiMSIfJRBS/gbZWZwJCCQxEhOlrpLvkgWJbMVYkhkRdnI1
03QBibVzHbyK1bW+FtSlAItxeEbQB8i4uCbOM3irzQjq/bQZwpGgD2oNZh/wCJ6f
Kd8zrJn7IIgeRs45Xr6dCC0t1uFows8EbOO1nbTajZZw36OWbpHWTlDT7z2Fij+v
Oq4WHYS0+OEojnXLLZnVEg+Ii5Vc8bJUGisqIi72VH7RlHwFYRwodLUb5hOlRgRz
Z5MZ/jJAn83bnsDppDoC8pokUh7IoXEOZjInoKP/DTtVBMKBr6tn5RANDB4GCr4S
ZRQi2Zjo/sFg4p/+IL7ReX9PVTJ7wOccwdZuVqsRuCWFA8hY3xt+MXBiR5OQAlTS
PwHmniitlUbukFz86vf9FCeMV/0DupWZvP2KPY8RlhK2G/LjoCWWxu1p+chxCZtp
KTS7N4WOwXUX0FYZ1VWeVg==
=UT0C
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ec8ce6c-8bf8-42b5-a030-fcab94f119f8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//cvJT8DmwJXO5cDB7nFqJRTg2+iLBD4UiBYChlrE7gRtz
0YVEsbzUiYvOMvF1qaWM9xYbnPR5r9dD9SfRa6lL6GG/jO3Ao/tWCLLjPegyKWTq
tamPEzyXfkCsHb5fnyRy6YHwH3btfVJgNnK/vyreM9AojsMwYiiCWr06qddn9ycI
b+IrQzB5nLC1COGaBpR/O9Zg8yZddaEMWockZcj4adhJDAufFcUpF7c++u6AgDuM
EFRvfFUBqO3mMC5x6fkELfj2szt3KmligxP8ckRei99VRJa0WnBXznXwy7MpXKJU
iAk1mPyv+euqrrTuf46Nl9P17tr3KBdGQz6v/9ukZv+iZTOJ03kwQCqIAB/n8dpS
/vuq56NbvrRqGTs3gS3N7wpNkvJuZYOCOS21t4OkBTMMQxOrjokRx4R+8uccmqiR
G8HqV169DjbsM0n6B6o6mmSCvyKrDHPxf93U4VNswIZo0lXdHnaLd3il9zSpvtT8
w6V3pQoQ/ADRobxuOQgR/T/8R1QIZ7xrRbefmDECt3QG1n4jbB66l1xRfYDi0x3t
ueOu9U34QM+6X58HlKp+HSuy35xnClwbO353Jv568vAc1eHYVxmbR+5/l3UB3BVZ
6wOGEIroZRaRamURjjJHhO6QmhjrBPxb5LrHRJfuF/rbIyKk3BVdk0Zlw5QzSH7S
PgH/d7jJ+P+UzsaFhXh75bUO+yolGE25RHn2uh7yOd/9btzuUBzpG5nRP1t2q1Cj
L3zOoyCCeSDq3Zjy22FQ
=w17M
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '62623da9-d02b-4767-a478-d4f243c42d1f',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Qe+GgosJoXzsqZ7OEw+joW/3fwlP1mKEa4mpwNQcW9Pp
AJwPDsybtBbrhgk8QKchKwUoYnA5n9wKIxkJV+nClKsGHsHtFysgsxLyYdouiu+X
FqM+9Yt4iKRaM+IKN8PpMrnHYdofKdWqYjtAGN6dBpO/E64H1c02HbIPdJBjcHBp
mokVCCCnDW83Yzu88xU5eGoYqnu0dsiuSz/wvMmBqMOc47gO1FzlMHMRDn5yTYFz
zCgC7A1FqOJU54revmcQ2Gem+2obs0KL1+M4URAIiDiuM+Erwa3ke+879P9/I6CD
+CSzX3DT5d76/09ZzqNpF1VtI1Zs1KbFqjB6x19d6wgbiI/Kucj+xGlEhlk43wjC
9VEilcQ17WuwfhvVlUlyh804XBZOFqli8JDq/DIDm4wA1zYgHHr+UvjMB5e4aQLo
TZVF4PRsbLF/CWXvRwY/oftKb8LgWYVdtp1zH/ZqCrwS5cLoWwftO/g83xR3qgS4
e07vglYo8PXSSbCZTaSSh9z6dXCZ4jGBY7jgHQul+myDkwyK3F8GzKL2l2MlPWo4
X1LuFHU83rPw/kyYkvmUk4RU8inzcbjVzlFLP956IEPZfThTVMPSGCa4DSBAkZIV
u//P8nrLs5POQLlQ1bcpB2Tixvem4JvtmZd2zPJyDq/CKIz8y/78fGVS9ymfAebS
QwF+kxGpH7+MICzQZBMm4v1tx1xxWbC2Lf/w3qfQlg+He0veA/gLp7tsZeoajwMO
w2gohvORXjEBZRjC69+K1IvJicc=
=R6RW
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6cae9acc-ed35-41ff-a52d-c8b5e3629096',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkDiMXfAeAXWLg9AxdThCP3usUqfIFXLXS1cSCZWxFU1H
MP9sisvf6rcQlJzTFIu7QH0n5gwjmjBGVyp/Rr6L6gouJgq9m0U2ndtVJnSZMJ0T
8hc15+zbIfXSrwFg4ElV5WL57CLHkWJnPOvOObPHdsgEcu2+s+pSz9kZ8p+vWMTz
F5n+tY/JVJmQHGOJsnHLodOB5pRrEMHTFQ8v9O67+np6xPiivYWA4NKpwjRwmhcz
y9nUk1GypP0mdxpBiywQRdqz/qxflU7QrNIWOJfN07g8kVL2iwZbIZ7JdtP6jfcr
QHJyYVskKctufAQSzombPdUQu4a7YF+JxTqvDLk/dT0I9VkRaewie+LhJQFsmty2
MWL6VuuCwXimpwQt4NEzcs3ysUiOmij7aEi9eVUcRIgQ1QNSMn8x+r3bXctwwt1l
1EnfnqqgpBBofM0NVtnpFaS2pDos87+IJGA/FGttpwH9sKG6O4fflE6mPdYQl+bO
WTc+cQT+ukxoUr847w4U7GJ8Yq+QyIWczR3zHQmQVTM/fLgJFDYMyvbauPyVk1xh
Y8u2cVWZCuX50ysrd1OGorAabR3QjMPifwTlrDXhmaxqwahzxocjxZoOLJesIUPZ
chYGkfuLSqJp6sQ9DvYDREO6dvCRxrkBSfnUXJ3inAtiyzWKCfKELZ5hRVFQa73S
QQEjLaR82jiy2p1pnwJOlmb8f3aOXMKmXvkYXqL+pR4y2YJwsC2NTTlYBrcpPzrL
IbYMubH8sb2wrrN6I9ha3qyt
=ebcF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6d8fa5dd-8531-43b8-a745-0d5a8a58fd41',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//QmIJ+ADR4p8Yskg14XfDDk9p35WTGe8jfqHIXyUNhee3
DXhGwwZBw6257LyjPaQ/VwZKFjSkArkLA4V5KptKiiiIeyKODnvnlHYh7aZHCdtN
V8zPMs8kDcMdyCruO7Z9GNsKMFpcMx1AUNxM/VQyAP292M/N3XdYPCSJsSCMlsm3
1TBXq2ndLyHbeDLOs5PQnRO7gmGiZF7RvICFZ1JXposis5AgPPgkVUbuwtLo4ER7
lTkW2wot4yY9HCS1nthITWUAFtZkv3D15hC1Q4TCvutjFHCwuFRM8MqrcokYYWD9
2rLEszKZ2TRj5H31dCZK7Tmq5NQdimNeeReadoXiFRZPT5mRj79keUS5mE+aAGei
1qIQPEYTNeu8zhMsLu3I0iHJy36sokq9EfScBcpp7Hj87UHZdl8AlklLmoCgsB2v
8ESJeenhIx2+72tumhNeLmP5YhOIsJqfzsJ/+dHLTJxu/OrBkyK4u3bJ+fQLKgdJ
0G+Sp8b7EQHWXqUGmYCAmfkG/vzP4Ys7Yd9VU2A6zY+6cgzJUXf3rLiGRuwljhcO
2R4aX2eBI4uNiw7Ft0lOOehF+2lzdAlhUkyOssaf7R5qriK+YHV+W6h1IGuBatDR
Ygd4Z8EUb7QFtohJvKUW/DtegxExxQwXA0WboZvheXpQ1jVPzTkmVclGSnafYcnS
QQGd9zZFvM2Nvdx2qIK/lOmzc4JIeKrSV3zJ6Ap7/aPKYkTplo22X9JxvVuCxUCl
bRqTgfXgHQ0/y9ooACFKsUgn
=OjRb
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6fdf36b8-537a-4dee-a92a-e436e84d5b9d',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/9E7qyWnbIbrLrlpmyqZx8hcHjAS+Xhdzro9zcvlouPLXG
GsxwwqtkH8pDGvCeJlZ7ahoThrxz4yPhK57MVDLGJPiQhxkrMTxjA7bBia8kXNYP
mXEFCjJ+CfbXxNsjhlYUAARx7aPyDBM4qLJ8X4/cNw4VaEOW92yzhjhAhh0jDMbx
5Ufn/eGq+9kwf+zWn8rFacKo8hDCmuxUpVk+G4TmJgvA3zhwtNz55XPyzLqN5rbL
op64S0DndgrP+HmQOugEsKZcwjsP0IERyamS8Sfl02E+DpYWn3SvLS02fBvx9ks2
17plYS0cTUPrW9/ILN9u+euHk0RBxubDbhb5/QzqMQM5c8G4cx0FGsauJJPAKcvL
+1gcpcJ30+whSAodSf45C/DqqWRL18q4zN3AUkjK9Xobetbg9B7ajQX4l53yaM5T
vsf1/UIMIaoEjITm2cL2M8BObt9/yITEXiD/MFlL1ctYRzbNEWkerfvSS0UzbEND
m2ROVTlfjVxPhTR7miixCnl0Gxhol30viz6nPFSP2mySd8tEr02KS0cCMyarvgl1
AkmO8JjpsAxHAM48tNvOX4sKP7pqk9qERcjxE+vtLJ3H1u6hs2hsce9CE+LDKmkn
TzPBfRqo8DbYv+A0PAprTFAEIM264MU8i/w1I+eCEE16JMpJtukLN/sbH6zJFWvS
QQEUYCnLw2hPIc21JARywBYpOD7mxpzEvB8Zy/DP3V6vUfA9T0pAjfoQ8u5ncgFZ
hk4O37qKnaO7wWIuYTzwT6MB
=b57P
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '76ebe83d-c0d5-4cd8-a66e-48e6860dc111',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAj7AW0kIVap63Glcgxu2IImZ7Nc/vlNGVgnv3/AwPB5sc
2DjRLuG4UT+39poYYD6nUcUvqfn5BcUbzXWiM3XvcAg161oNWYkxDbI/S+zRbuLK
sH7FXLfGkU/0eLL/zIOk/gV6C9qUiZHf1//X0F89ntWo1n39MSniHq4dDI/z2gou
GHFKkKPyPJUNSZ05zfHOuhPvYSfsN0LNO6jO10chGnAf3aLVirdK56/mUxqn2AYx
WrftOYiAG+ORaQO4TQlScuxrHzt0sdMkw0RqhWTMsRgnAfzU8KYmEByG74Usl4so
V+QF3YYiH1+YGyOv1VVQJkNVeG/8TlUXm8C3xisAu16i0KXbufO8lZq9CiWiBTUs
Fetww1dM3xD+RrPxa2wARXHu5AidbxAQA2yUbM3Hiv+XEKbL5288gIBIVEcXuRv1
02LoqTQU27LevGpkAdSs+edUHPLqAzZVhOOdHCbvEqQtNuS7hc3v3ZYcxxhPkInB
HDTOLGDyRgIOaTmeW6Cm9T+fWaDydtyPGK8E5fTfTkv2YmEdswVQYVjDG8xbZnG0
uNe/hEwEXE3jrv4ARVGx6MseYdaPPuThGo1PSFwWOjOuOr/p8icoozAChmSRXOtV
qngWrPbfAoSQYqY/Nk0UJXqYb22eWMDRhJKA6mGvKFplfYDoFRbW3bRuhUE6Uu3S
RQH9Qt+UKOJmOPlC6YBEPqly+FjTwEnCDyQXY3/TZ4OlobklJXTuM+xAHi8yasBW
BLLCcBJUkk3xiChPqD+RXhlR2xG8gg==
=xNXh
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '79d95aa2-688a-4e57-af01-2f76ea2553db',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NWe3Csui+/h+kypqQykBWlBBnbdXEFkkWD18kWPEqE0q
9FdMtNw8+OotWWlORHaOpxfO07hEjyhcw+UIZMZNiQHBOHR9jik8TAv0iwVg7iQI
rDX53hoOI4LeUGxUFT6ltW/8n6MeS/GI3xYnB3+UG2PLRMYUo17n7eO/xFwA2C2q
CDam+wz3KVJDfDHYkllNSK2xdzDD2FfiibipKUC7WcPEpEWP0xEd4AZYXoWqIfEo
Kc3r6NhM0Ouy+GFbrdaEB/rNPuapZM2d8nQwEVbytJozJLvb5ag11oTnbjyP+pC7
tlXm1r1kvC0p9KRmwOuvk8X+vKqx+CNbl41U+XjdfPE0CqbruwIFkBUgmr/5WOWx
qJC+CcZrTMdvg0c0D+YvviO8IaRImwHF3cQdb9Awru7TaxVQ8cYiFi6ZZPPwtn9G
JraLnHvFSxKGznzqrx5uBE91uUQ9koat1veiCk7cCTzz4y2ZuzsgFuWGTQVV/cDJ
yUVdm3rXt0ya2NEBHPKzLgzKP1HgXAItGcC4O+qtKpkQltcqBev3beUEzDpUD/zf
1pTWV5xApX2ifEAN5ek5i6ueevnG1JyBU51hqSEZ8IECafzjZP32rzYyI4CwqTnm
wUhcu7TWyO+7m4eUzQ281rVltsNTZzmIGFLyx3ubOYMbpafhbSpGGWES2vLVvznS
QAF4uYEh9zCYx1+Q9UPclZMgGcG0OSTb2b+qgk7MQmYn0BB+uUVZ3fD9WStmFnw/
Hv9PxcS9+z8HeyibXs5yGzA=
=Hv2u
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '79fb25d1-701a-476d-a7f0-ebec85d28174',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//UL/kOvO4EzhSHD86D9PXLumQcQKmKxgB690Wj2uGCyqj
295vqSs5R6I/8Zz0sBFW/mIdAvosegwNJ1dURspSA4IHq9DspLXarGL7eSg2EWfU
FtllK+GWhL8SEWgOJ9pTE+G3dxfMdYHPVtKGT4hHsxEYNqaXLzPbII8tqkTUAzFw
dfU/Bx3jt5D8zARXwvOmb8tuN8Pb/ep8N168iXA8ZeAuVxYwablDb1tocIptmIm5
sCzRFLommZbOZ40M/BBa+Ogj4JO/pHlS9IN8cHSXT4kjL6MUZABk5wYeYn26jt/Z
hz/uzQsZZEVKdTkmeJ6JZZvTGbn3Gs2neGDRpN8U7Mp1fN9IPbSnfD84WtPeOmAS
lf0yKs7YRDJcJmVQ8iBiOVdHdCZAyVfG9IeaZLx8V0WKs8i0q0MlsT3vtHKwRNQg
I0bBFrxTlBQKJ9stTLipMU12zktMQY4LhslGcESVhYz58eqJxn4gQFlWH4UrspIY
V/CXcegzBTqW3XWINaAvI2GE4pbiHN7zkqncQzVUzLEvkkmRWbUweNul1KvdPra/
Gykq4JAMRBJpQne4CfOjjiAndqWWt2h7oDaU4zKC8tWA7GjiACSdXbf1Nx8mY1Sn
cDAME8E4jgrci1Pm4/twDFMMIMLFek//zai2eBgfNhORAzm5mnPhCgKa59i3Wv7S
RAFzq83Fntdi4wB9svPWIdoRemeW2QnqTvcCJ1CL8oG0JMA48sqg/ENPocdn0h5J
nh0KrjN9qI0XJ0eQwEqCy8tPyye7
=LMHl
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '805e68ac-cd65-4b40-aa4e-c8efcf308fc3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NMbzm4hK8fJqU98rRf0lJEtqf0euni3tVCyqv40rf/ZA
kLlw31/5qhljTwB7+Wtg5m8KfVLm8B2OjgqAkyJRrDRfjqyL5Rgh/MRWFuQ6R/47
yhvI3iUuHq0u51mOcHBoGI419pPdy8JBp36ReLQC68/ZHsC+XQLyAy1C0Bi0n72r
kSzf6nydc+jdBkeX6pt6Yvc86cYLEChh2p9q81Ur5a9zVYafUkShePTN2ThO1FAc
Td+96M9cNcusYI5H2v/1gVhCEnuc5LZiAbuEwt7brt+KJyUyN2JpdxLVb/m5vapc
nTLB2ld//wmqgBmUKAnhT7r9vrL8SLajvdB5S7IB0zke8ODAD+fqQemPjfET85yD
jtwSf9q2fz/v1EGDip/caA0dmiqS3/YP/8jrSlZu2XIjngw2BmX+UUgj/pnAxiXl
e6k8h1hvwU5vt0w7f3bUntpHlp0GuWfgKgoh1Xrh5o77bRoNvdK80hhwKrZZ/aBl
NW8G1/esaU16nBIqBcKQI+dKZfB8466oqoUCWR/fX4PqmltnyvBIwqQhhCw1FuyY
BtKGuSoKqmizVwE4JRe7pWU7boMcb7ZhxnuPqWIMUf8hynuzcDK47DAhdcZ83Xeb
GpbctU0pxzTEWMOxkwcUQEKYFMcA+XPJkdxYoo/5HIfFwmUPzxuvroSc4ITfYKPS
QwHnKx/W+aDRoF3sHZMfB81xzYoc8ElAtAlASJd8mPRQwXpSFqprBvnM2CZ6OOrk
o0Hg8GYXRIVOa9ek81H2yKHc1wc=
=U/nS
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '81ac7e87-bd3e-4221-a0f0-7b30941901f2',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//YqhLdgew7aiklaYxC+7VvdwDUWUsUbRp80dGMMdxkOkJ
QoPlqc0z37kjbpWMtOhjUHAz5zTLSOutFNdqqAbXcs/Hk+briuUWlRPd+/GQLQHj
0WdBEgdDW5xFU2BbAB0epibEegv0KE+aiz0F6SX9kJut530XTKGPsff7mu4mmBIY
wH8y6IbP/qVA5FLlSOymqrfN3upKrr5ha4sOZhb9d4fV8rgVlRqeTG30o8bq7pjE
ESDos/22mGQsZT0Lp/+B/3wK+B9BSVEYNCJ81+Fb4DOhAV0CU9RILk3AF8iUl/aT
d5/N4giL03mrmvtwdhCf2W0OQ8MeGLxkaPwvxFK8t44D6N8YMgbDP9trMGF6vGUm
dIpZJ8TwFEKiQAVv5JqSRddgS/s9MSqORSRhbUB3JtD8NIDwsbpvJuDhHCvihyNL
ni9C2VID79QIc7tK81QRpshhKJq0zGLEbvTq0VUnyKPG7ViwPFXGM0A6ORMujC/4
vGtLw/c/TR8WAlyK4Oss+X1aSy6kaWqa+oVvIzyGB4Ur2yGyciYxNgHuVTKvlfZF
fpQlph5w2K/izvaJUTCimqPicZ96Be2GhbuR3HSTep07m5+T7zANxUVOrcdK7xen
XU4z+OcT67EEVsnGDep2hhlXTVrhSLAlGsSoR3Spb/9btl55yfYPESnaUZekCs7S
PgH6pVx75KdvFfRxtSrnEz+x7XdEqsQ9uM8yXyECrotIbqwsatjehWNmHsjmj00f
XH0Sv+16wdgaeZwj6rxR
=lfVe
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '83fdd2e4-6848-4da6-aedc-d452af781fb4',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAnz0aiECiK1gh5iWyRZswDuNbPLzLBOJBCKgwF0R8hUUW
h8f6suqRTqpD1Ch6jEiXtKEMKY8M8m1KW1lwRhVyrLEOD/+SD9XImfe2ib4t3mJv
96Stozf7jCpTm/Y0I+PpF31SJlP9tZbic/8c/dW0aedQwcBs/IP9kNWjuqDS7LA4
wqzXb9Yw0lwUyi8M9xYeL/QRv5XXnG0vzoiOeo50cfmgCUhma2zbWtv7K0sekvlz
qpwvC+VLdnrjL0/zp1+3sAaWW8d4M0cGRSvwYLjNT+Dhnewlarbnzh+g6c9Gtg57
49fvf11gU8EqfZVtPCohaHpzPBv9iPAzAS4GUkQJyj2s3lhD6Rs8X4BxyxFKNzTN
TOLP6PL3eknpZ9n2eGJgEFQRM/CQwfCQubvNNAL359RRNtY92589pGT1E7JjWGpx
2+vE6THp+R2DRe0FpsTmv7YWvCajGVuzRCRe8M9rcJaEcJPAwL9pmOcaPyB1Y0IQ
iwp8LPKYy27+6zP0KfQfuD/iMgIR8r9rfjEE/za3yErKvoPxIXFQoHesDfDupKIX
GaFIR5poWzAnnfTkmDyg4qY/xkL+50d0I9QiI9xOP2VnAQqMev6vJzpHwZmn8mJu
uA7NT1h0vHnHo+Bc9Q9FekmodG5cJkVwkPKITuucqnVWov1L6+JoZkwKWTBrgvTS
QwEIufG1GGbCyBqI2YhlC89HFWaDtvJAIQZqQlgnaMmkSbe+qRMGhepz2OVq19Zo
dOGELo34pnlmWFIVkCj9vVXZBBg=
=q1a8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '88357b41-503f-48e1-a1cf-a9174ff0b01d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+JTmrl9zZNT3PuJwj7s81Sh5mzHEi5I0ON6c1noECGZie
g7uFBvz6go64vNCL67gtwIIA4ozcL9qJf7EjsRN0g+5aZzMys4WMC3JPSw21oAp+
FHjsKMwjXUwYsfDCPnbn6FHE40UnJ6Ld9XxRmv5FX/p4T8hv0gHb0nSgwxopFDjm
rIcYxBQ31nLfWOXKcu2HOaB9luIClHC3Rhb1ZrAo8uplonX277cHChXv9T/sq+9v
TohB8kkTs/Tlowwepmo1rvV7iQbdn2tya+w8n5p7Xv49EtMD5JMOaQr/TWW0wWp7
9vtSAIkJswQk+G+G+7NJ17/Nyr9mhlSRL6Ojnkexj3rjavQ/DGck81G2ft7qw7mr
ycsZZgD9/H3DR9Yv5gyPfQlrcQUoiWvbDPHVZcILWWtrTzYXolfCTFRmR/sP86ME
iuxBz7Fv37ikpTTiik+b8ho8K+H01FRAc7k8i3QX8Pcc94bysWpqjRz3+I75R3y+
/aTc9czjRczPTV60TZoGE6uZ5EOvKDKsZqsi7QhWmWu9623Ht+6auYjRHOGroyxJ
RPwd3/sr+QS2pxU6Rd1YcOJQgZlXFA5+krcCnlwfFoyBSwk9I8p2sox2r5bxRmWg
Y+V2GJxLlP9PWzgLBng52dhZofSb04DAdq71kfsBE9WmGFJg26T817EDNhMzQbfS
TQF+ChxmHBgzoYqnfdRi2IFn8sGWp7kDBAB69B/PTVitysAFKGXMhEXNsH59MSrf
/fLaICxrY7ltKjQ/FM5zmOnSAJXLKSLheV1Bw67U
=S/H9
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:42',
			'modified' => '2017-02-16 14:44:42',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '890ffaa2-d972-45c7-abf6-5b786b3d045e',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAs2BMpVwkSuBGslFpJSgLAHhgcSZ+Bgfn37ATKyk8XN0i
YPGXlqP28lSSyZtZuo8ElAxQmg++Ce5GUfd9lGXYieWh/7xiXI1SrGEM/265Pz+r
EMXKp4J+fsoFrZ19WKekZsVV24/qANtIAy0ty82UOunAoG3q3Y6htuA7q2Lu1Dpk
G95+w/Bj2+LE27/l+pPkpVPBQijhz7kuaqB/IteetVO3e7Q7FpVpOzaATIiTw8Jf
ENYU2hVjXY5TeXOMSATcsY3ur7fLrIirS+QsgE7PpA2ukgNm5uaZQUKIGtFBbZJi
ZG3YKGjJiyadXEg8dHUB/haS9FiRXJ+bE2zOo5edoKoNlyiQg58OoiEIz32KyOZ5
KfRb4x0X0BH6zgMk0lvckGn7MFpoXlzBD4e0T9ikHnvO3Y9flj+I8LyVh0WFHLnu
RuFk5O8a7BZ9QPvOHYr/twA5a0y/GaYP9gra3s/ftWjGrfJ+zn3GN0XdbcN4NPiW
Sl/wEhaBqoI7GvwtYaupZyXmdpmnznnl0IhPdOjjYX5aCcJ/EIwUU9qB6dMZ9gCY
VUyPMi7HZyoikq+hWBPJM+BMA3uQjTnE/UqcGJMPR+5MyozIRtB/xIHdqKO1cqkR
vH/slFmNGGPo9yw9tHIz0h7HpAjyWA7SBlmnn9wReGY4qrPEZxiQyeDGavNk95LS
QwEzCVk94E9i+yNqp6VttqH4CDKK+j1dg/PNTem10HHkwpZwjzWXM7OOmnNT1ulM
lV4zjKCWy3iANSHUgxr7MHqfioA=
=RHSx
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b7e5ead-b7d3-47b6-a638-6039cbed82da',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//RwuU+TWq8sTpG4VT2NxmunV2oTbJSp0IqHnof7Mn75vN
Lf4x/Hz/oSWP6MhRQQ5d/NQQFXPfW4UFEQtLyGgfGMvqdGT1IWjMfimQUQHK7vzl
GtH43EhYPXfv+GC57dxkp/s8RZ0V9IAmwjztO+7pPyywHVK1Qn04lHi8GLc6VO7f
h6WLytU7MMc8n2u2RDESy8t2wjCxM3KLts5ubPe3UnYaEA5Q0KNVc414bTmcg6J9
7q1R3kOSy7a1SvmjwnAy0kPBgxEGPtAMQOuWzDan+iYsmUzCbO+RKWEAyr/whtyu
HzgbO+4xJ2GAvDPI8HnBnBQsLEuYLzhG1M7cEg2ITR9SeSmaxs5fHpN44XSZaLAX
WaNAtLNxACdRyxVmaDSDdA+G2YIQqwDoasXyWJddu/MnlYXVWWqxQVK2+eqy62eo
ohLvbPWMiTg/tzWl/Xsh2mGyx9jDiuL0o2RJs60iNulvqTCEKUn5meo3n9XkGuyQ
j+mnnTCD8DhhtVH/rn9E5cmq5w2a58Z8WAmgf5AIjgA3z9ze4/3Hc1zV4X7c2Mzu
gzTlJubhRVQlThcQ/5NqpyUGUh+YR5jzHvlKpZKO+XGBOzv034siE9jSGlQ4rPzN
TCBo0/uEyzbMY/hKdwBon+lqasReagLjpc0jcBUZTVG1HhRHTKSTmWc76tN5SqzS
QwFBtEpF5hTCuMm2sWUDvoFpwtMcwj8bK2qzRz8hUllemZMxeMnUt7nlrR5eMgWC
awQJC3oNPOPSRpXbHnAMNoxwqNo=
=AQrO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8c889a5e-e482-4f58-a9de-86cabf8051e7',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+ODKvshj6fbHy3JhfTnMNEEsh1Y2Hi2dHQ2MajGpLjdS5
7qHhAZRUkHG6zi6ROQa+/KLsFSM+dZip9yTunZUjfFIOIgaQoAjreaZAzxX0Zvkv
fmjHhDZJISpTeiV4NnVsbjMf6wJZoF/Q26i8vZm6pAFDkx0ULfAn4CZmbznnQP26
99lNQZns+G5PbY8zisGpvRia7VMqnz31FxZQbq0TZUzaCPIl6KFvBUmPtR1VekPX
Vmhf2GmeRy7A5DoYEeLIGm8IV76SIXbuuQS3IUKmXTtGN+l/Eo0vf9/QQcGWkFc5
yb0qYQZq0HuATl0Bhx27CbeJihAbfoEQu+O9hAfZg05AziHGq5x6ruhADWN0BRto
NP83M0/9EHdAq9TGaXzVhGO5PtPtp3SZXPWmyJu8jb9+KKQ79VJ25gCF9WNRTP56
NnPyNOFTkRQVHoXzcRV7G3AcvXXK3huYOY1JYBCqpc8O5sXI22ft2CmMZCkmbq8D
fLcoTOAHnZJD/Jg3MQCPnp3uoXOkROP6TWbX+YOBFckfjm/Fdj9wTmBNnrMuY752
b5xvJLGLEErBOFKXW+jepkaA4eKBnejSk5X34twP86vAA6YVVktfAF/F1y6fCgiu
dl/tBfszx++O1whyORKDBZxfB+zPLSMiqCJqsl+R8iUtIQQx2EUluf99qzhdKO/S
RAHO1ppHOBCLDYSJdfDsmtPZLdsaIj1HhGRd2W8YSIMJ64B/ee/PRTTixZU4XwPx
yiwP0iGZswILXrXaQgRK8WZ62Uo2
=Eg9n
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8cc0c41c-753f-4643-a041-a373e35dd975',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+NX98kJefybaEzStCMIiL6jkxmvt3+NDACXfBE3kv1D/C
e+HPeN16waljk3braV0Ac8hrKC8idrJwiAthslKaL4mxEaiiCeaChLgRaO9wTcj/
CeueR+Mpoo5sgjln3U+iSUAUG13DJW81/PFl5YH4dJ9Mfpv+KlR/grzYugiXys02
qD0dtDQEK0DSGpaQ2brH6q6UiqTILTt12Sd9toJOO8ccC0fQ4YwsC/vzbpqG6469
tO4F0DybyO/kggseZjAs1Y05OYELAKp8KzsQI6duNpe/NmqsfdY3cwiRpYODviFq
WF7bnysTN5xaaCJlh3dms9T6xNKcqr7Nvu6dpAZmznhqOYIisbaWQrZJYwrfsBYs
Rg0rIvB0dkxm7XCw0atk3EximHwOXQXvEbS13OoOndYBsbq+nmdZGOsRRh5qr6Cl
zmIXF5yiOrPY2CYyxHNZtLf5FzZAV6UkOTDlHtZOBRFwQ69qAYkUnC1TceD9ywVb
wamkZ9+5d51+0wLyTFwsg9WjwUpKkkvxfj8bB9CIZofADdrAotPsa6aUbsSpDwiD
V+UMMAV59EhxXdPPmU6fL8z4tdgnkYgNjtQYFe0hV5qq8yFgZJ2W38vrkkFBjFkY
DwN9df7vtHh8jVlEKq9K6Yu+tfEVB9ZE9CNEQ7MGrZfxxrplEIKP5AfmExcB6CnS
QgExylkKTaUqZHtKsFKqdQQ70LKNKNZWmYztA0CoXHYnM6fYnPczEqAOmWu3/vNU
8lGaWNH7pTAYmVgqUvKfUCMZgg==
=cYkD
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8ed20d77-f1e4-427e-a461-7defabff80de',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAjETVM8MU8sTQGjdQ/DaTKnCIcVgbtp7dpf6IMP3gyskI
1SQZHPk3S+y2mVSSr7e3RphEg2AuLDF8IN79NIqhZkq+jFVvZbtw+ItFvfXxQEtV
9aU0lTPZwPQMeN0FUKqnSrtisQcHxdF5ew4376ywK9q3qlWrA/B6ZG5kpEaKsD1z
Z15yoNEfdn0xOIjoU2/CLiG7jeYLOW4UKS6LbAPSqIfs1ATjk4jUICeRdh83qXRD
EBXoTXIMb3yrqfGKxjEzFqUhNz6oV3HdyiX95YI/bVyndFFV3mEMOi5ajUUQPRv6
7DjRw+tDOxA+Ue4hHBOnkadsYHr6OVDk2rNpRNqU4iJHs2v8KcAMpPoY3fXznYB9
So8cVN6R/+rO53/OYfOhUWaoUqwaey9jH4euKdNxzeqpYgJyfJK1Qm+7JCwKjL42
09mWVqY794gsGa3NY/0axoPNCMeux0T9PkJrshnEDPYeg0yXbMGbkvUb5VPMtSxU
ECFHqU3Q5njGxa2JNEu7V9KPgoG7JMORGz/98bjv1TTiICoUfxERMhnykNg6GQIY
L32PcdzaEQjwOZHftSsyI/pp60vSJdKiIp/ndm9ICWouvkC+I7pplB7Q8lOnHPiN
++Im7C7HnuJqKewBdm8c2ipSgpH1z+yVwWE6wb5Tf+2G/2MRe+Vxz/bBiKC1aSXS
SQGNr1NCyu2RlX3EXrHTs6xGkMZ9lrTz++A9ekbox4m2ktjPAcaDhhuIlh+9onr5
wGx1S+QzIFbDl04S09V5fcN3CV9DpVaCWmw=
=nLnd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '950ef36d-83eb-4523-a9ea-242d3ee70031',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//VwZG1T+A4iM4PoemIEq5VGC/A7yye92jw1JGfLGUE2B2
0z7pVn7CnGARxKNkgAerTyQYHPVYloN1LrQhsSEIzA1SH0cssJYiHAL1IUCtQQtF
6nOXR5DkQZpPgXaELJl1otb5F3hmfDGJ+8+/2Hyk34HV8wGrtrZMGavsO8R1aSWw
Lcgz8mU90PjMD8+TPR3bS1PXp6zAiZKtvOSiMSTugPuRfk+BEbs/H1AHzFNeBUUB
c7+pzcH+QGEqF+bSLuXM6poUiLVQtIKV+GGFlUDGXX3kUAtcVx8Evi5YLxUDvLhI
vmQOsRbgJBxHjb1+zkty2123LMDN1u4AIudSNnsxLRfLMKfgFXD6SvsYzq0ZXura
0PRQyIY0MS6mAcooz2zg08g+BTtYWgYmlNwh+rvHuK4GnXWvoV4B9TMYRYrsZrT2
pRZEm2i3V+qmr7R7I1yTqUXvHPxUKbLTHJPSzktOUyUYp87l4IUX25YJvXGpD+Em
hPmhValPZV0CIvCPjtzAzGe28BuVzsyv5oiMeQe5VHoWgr/rcBacOzoeMPYl1DY/
4KZOfvJ+uTDGjkB6ZSDmnKPOmnBbRSpG5pDwR8Mrcv/5olgGv9w6wxhuwwR2Xb3F
8snYJYZmw4Ls38l7MLGoCmoD7JC3bAeOHmA8E7sVYfT4Ta5tJ6bal4wtWaou0jXS
QwHZ0homWQ0NEyNOCUb1xyC2x38Xrhh6zYLHOfoZG1HT5jX3XsuQaFsBpK3YwmC/
23Y5nODsGxDUc99879hNfBkLSo8=
=ClOT
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9658ef63-c084-45c8-a9a0-7d8ef9d6ec9f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//ZxqhzkoZWYFQhC8sE0/rR8+vpgfq8EW4rEvm6ecllpHt
YnLoHtIcmQDgw4KaqgUDUq8hvdjBB+GQlwjnUYCtWt0z3gtU8+xIfeslmX6GX1/6
0ZSshGWTUkQSY1M84Wure65kirck3IBSFbzr0BlaqH718WZgOj8dCn8yX3pvObws
gy8eXqTlppuySVqZc1KUNca/vN/pzBw5Kne2TX3z+ZCR2kGTRVwWqF7GVKPzAodj
89A4+53vTsJlO6ZYl648tiHVinlAcSWEXDSzESxJOEY3D7ZU/J2BvqQM01gQofyT
4EgI4wQkEHGnPaT4XWuiUOn82ThcqI6PiJkZidYxmzB64unluBhE0dTJMoKm5Hiz
w/b/o8hKW0XGo4f44GJATgBZlZ8mWbklXALwleeK5AvrrWFxWEkNctjq4xZGRF9m
L3M37WL/vvkEE7Ky3fJigFLwP0E+yjrDyWzJkATXphkEwUV6Miln22aP/qg5K+Z7
UNTND5D7C9lXNVxqJYHNVYUC6EKgxBy+7hwd42K64SeRBhSxhgOb0SGCHpHrY78Q
b3Z9kKvgN4GCw2+/s98bpIvzoHIzuMQ1hGZqe8ki3eyclFaFvqG/et5TblNJUibm
k+dUPRFrn9YXWldGY071uMbJjTkL+WVpGxCo1JcNHmkGnYshV+JZHlsq1SOfFO7S
QAFmtGCDiYL2aVdFrO/HoMs8Iv9abaBYoEbD7v2J2JEnpwDMxtBXNDI1p5DKJteC
Aes4lLWQCaoLTlDDAENOSlQ=
=nhW5
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96b55271-8d0c-4f0d-aa58-837686b3dce0',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//a+djXnL+w8vx2NFhvSoNVjrBjCSl2jDmjOv8f1/HefQm
hx8sHierXlcmxKZ4sXx3OF4medfeK2FZfphUpyfMXYd+ZmxAq9hLhMd79sVGkm1+
FRAZTYWcW4UUpLblvS+FaLx/a9ZtvUG7ASELY+dAEAQSIPynIXluREx+2ACsmEHd
lP52rZM0ydV7YqldzAJ34RDABZODvRa0S0uKTjRBbqTedo0DN1RmLxah6dQaF5rI
MSaWkKrlyLPc4ZARlYRO+Vud3fbjpjO01mDvFFfHuvrWOU+uEAEpJBUHTUjd7xR9
1AAIqeReJGgC3EeXpU+skLKbJGI7Wfvqbtr1lWntfmv6dGucaLSgOB9ro65Oeo2L
DUWKHVE+HYYc64IBWO9/kb2t4AjFquhdooC0yAnvv/2LjfPWg3ctSOGgOsHB/2Jt
U5Jq2c/Ec2+Z6sWm6Kff3F8eGduJe05ggJAiCR66p5/Ml6wxPOA6fnlNCRlxX+N4
M4Jw2w0ywiOM5iVTCmViGj6K7xkvZy1KpT3jouoCKrDgQOr0XaC+XwQUdESToPfj
udcU9IlQmoy/yEfrXzxuvMTUE0USgKD/YbyX9x5TGCJHbIXouSuU0B6MXKUp5+zD
IQaDUw6vZusKGEdWOiAmP6NgePjv8UyCoJxHQi2lkYobShQLDyQXgec/86WIS8DS
TQFyiIIZToBDlN5QgvTcV1grcMvWYkLoJz9+2ICw2KCQTKe6Yfz3DsIrijELWQYw
l4ykA14dOhZ44zWFKGDyOndB4Ie5ZwswlxdBjbR2
=xrrS
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:42',
			'modified' => '2017-02-16 14:44:42',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '972fd576-e61a-479b-a0b6-72aaf889463d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAlmQAbRMjjFUTPLRicot54kWFPr8aCLuioUKXgVwQqPLd
Udp+9Xa5kFE71YcbU8wEdUfPklwx0A9BCmuag7XLoPbYsAhW+Lx/kP7UcDd/MumP
90luteyO8Zr2slt+SbQ5jgsk+M/RLGmOdeX/X10H0HM+QMDZkyVmbaIn/PiKp+cH
58bQQLG404HHWjEP4QmaEqPLNLtx5cUlpJ7xcxfh1xO7v4ECxYHNQoGuDEsKrqZe
BarMB1LFdFrF3+29eLajzwtSDCdKhdOBGw74Hpq/Qiczq4PHOOZkDoz7IAa+4RD6
dmF1XupFgBGTUp2IeNJ9M3bLcU4HAVCK4qpHrxxHGEy03DuUmYFNLkUZvpy1w9fH
Vi90AqoSF1AsOn6ZtadVN6nxigaKPGjoolquydfco8FzAb4pSbnPTm4q0b4OHjAq
hQqYbs4bTfCUmBqb4Z2zXikO17MzKkfT6oI5gdUDX9VpuzbiZ0tG82D1t0l5d+tL
WMn6e9IOpwNlhCQICtf3wwuZDK61qjZBhHplrDNl15lOlrnojhDkwzKuN9tZ0/S/
ViCNJK6AzK0RAfDtshfy9QWUPuhUU8HlNB/UYH0dFJP14QV6Yu7b4ASWioydboob
PbcUiGrVJ1IQkvlVU7Bur7RIeZ0HfCj9L1rFmi8CI9YfW8WKGcKJVaZb6O47mVLS
QQHxvv5UmnDubf2PAwsZnQzQYymf1pSxFjFPD4L/krjT4mGEIqOKP8xY6H7FsY8m
OSXtmEd/5aaqg6/CzhgaqdLb
=Pk1K
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '97d864be-2a5e-4db7-ac65-ddb6ca6327d9',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//YqTS11B4GIFB3PKcsOtyAQVc3zZxrbAL+EYv9cGfBnL9
iMwNzZQpKoVVYFBDo5DsVNbmYVtz1/qSPYh178JQm8R4ngDlnY+mQT4m99joXb/a
WMtVqPE94ESoNAVDIE6IeB8L+e2CtayJQQ2dqWfhOTv70K1lRfMLK1esm22VX/Ku
9P+j0x6qw6JeXWpDfMnDhUXCGJbGVT5+Ke3I3KUYjIJR77q/iD1NnTYRD5jBGx4R
bqQ42oas2RnjlReSKtqIsp6tyjh0oR5zN0hpPQdhnCdMGCkUv1pvMYq/DtNFZbrr
0IS/VtOjD8jjrfXUem4kmOgRpsNXl+i0z4RfE6v8F5wCaL/x+NSznk1371pUXGCn
PPs2TO/z/RbYdhXtxC8m/QRW+HyMAOP6r9A1rXQnpngIMhZp6wtJ8z97GEU2icH1
idhQysT+ViXMGOn/gOMrX36y9DPGr+0UMG7qzznzFV8IwDTsv0xsxIQAwDT7K/Od
xOsdaC1BgVLzOdDKNtGP2vTinXGlMpMbPvKC8w6xRkchMsoheXdsBrFLuvQJzgLz
euxMr/0v0JY4UcNY8G7VbLzvCQzlL/SZlK7Ru1XO8JjQ5xSCe8XsmHaBj0IpLvVi
Fs+sWxV36bXrHquHFRR5u1uI7S3khSdzQyOMa3OCs7lebCCq/T3YoVSsPKld5f/S
SQHJQpw2eOM6+4Tp5SLSHJBOYBZ3Eu606BIOnLbJaDTn9rlNo8tqXud5hMdM4k5t
HjQGf0eeWSleubL7qjlYL5VT4F2ZxWxIoHc=
=Wnbt
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9842f551-ba15-4e13-aefa-fbff675aa58b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAhalNSN+45e+YoeHqssA+J1q3cm/fwJSqADjin1k57Wqs
ESMsFp+GqsRBx8ncRsndAN6GHgPuWZTN87L0/IIw5N77YS3Yl8YLm5pGb7ZqJc8J
nuQ/JwTbEGd2oY83FGmF1lSN8XrjoVqAko1TOZyPCzcnhSYDGcsveSAv5+Ns9pzi
MWLuMr2bgj7YQ2geBWZuNgOGWiS1oeCxJugvy51PO1X91oP/EyEToyh5XIcAtJnl
vZXDXyCpS/pCPKvmhK+X3xOmZtcXTyuenZtILYoVcYEUUk/1kVTBLMi66TyRUANl
ncRi9pzkXpwK2tV+J2VvOiSD8BhFhMEXPg5+5urGguFuQWSUdsSqNgfyUJqYrQ3d
TgczV4zz8gIsnJHPxUXRMTAIPYm0fhZvhvFRpLvD6+bJPH/OvdwSSoel6lMiWaHW
FV0Q2fmiZ4PzU7zJgjt0BxBL+ry4beK7APICMj7rH4XSPbvAS/FcdMUGwPWeAXe6
d0hwhSt8hAgl8FXvtIEsr/awUbuO16GgWsvrlO+k4k3arP5/0gPw4bfWFZt1GPmS
zRpKSRgC/2bfdvwSu9KzePrH+cwh3S1BLFuCWlkD1SPEpV8ezmWy/0DWf8UKQQGf
e8Z6gGi+mRA3f4tCY9RxuUc/s07Y8lLUgTwDBQJiNgUETnCWVjY3cEKyi6tbnmXS
PgGsdsPQFTuqcZC3gg2gt+FupdLifN4c+InVFpJ4iMZcwffzAx8Mbod6z9tfgc1+
/sE/hMMG4VF0e0sX2tmB
=MPnI
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9ee8cda9-6839-401f-a93b-fb6f39bbb070',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+L7pUZ7mxsicEJyJv576iruVoHx+V099/goI/NzJHSnvo
rrJKm43GCzVgcptciRGlZTXlvPtoSXJOufBn3DsKi0lgpLibjR0ZMRjeEcmkBS/d
MfqqthcA/bzCRQuDj+7c2P24IA5tj7kwTnvfe/b0eD4vM0qA5mMIdw3cWGkpOzKs
sbiJGNeROUHNzY8atbk2ro1/wppBLPrBb7QeIJMFjV5mn8Hm17IzfsCkuKeCmFGu
uEVLeupuN88P/wm6VQQXAI+ryY2iGh66ZC6idD/GJ+eTO55JEfh3LtV9KplFF7t4
9rXrlPyQFYFM7SfhsBeorb/NCA8789GbgvXwZ2ExjVO1ygnVMaQRUokP4HC0I/TT
COCqFhF4DsjZ8s5J+87bUAd9HBJhUJsm1wVfDaYmyr/XktzFvaCxu7zJUUumkxMC
yLqQqbBxcwUnjxun3qXM08/KGvbOCMS07v9RF2aTbAIxrREFJOTeeG4TVP+P+v+g
475CuAb8fRpB9JA2Ew4yx+ldunn7zUeWnr0qfrB+FbGgWOnEN1Vjg2QtAo4/QWay
fvxt83DCL9m1nl15qbFNN/xrXrDc9f0rANn7N2bPV+h3d8G3i48K9hziCUSLyidS
2qDq1SV/xOr/kPsO8RSdZrXWvWzVh9WO+tOGoZJwa6tLeZya/Jimd5xR3L+eU+vS
QQG/yF8eCuu3KHVyrkW7W5+E7uJiA+5vLA/X8EMdvAFlywsgNMJVSdQGUmDUwp33
Q4LrZvvMZg49Q7y+cLYIGSGt
=7GAO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:42',
			'modified' => '2017-02-16 14:44:42',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9fab68bd-0513-48fc-af92-7b8e1c70f765',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAm/czEqyEC4ZaO7r4TpVkqBYrKcNsh5wtpnmMAkt3PpiI
QtM4suAJ+FM5Gy8EHvWVlJapoRv96/cK9LjXoOxv7/bcb7zdXBBRvLrXKwxFJFba
mFgd83pr8xXIYoLB86ewsnkGqPnZdR+fdJKfaJxenkIXxeVuTuZfs/AEGPxAUUHM
+sAWL5yypgRWFJYWq+JLvW6AmcJPIsmqTcWSjB8BWVCQQpiHEqwCtScIPfw7dKGU
Npix1gxqdacQ1Gybzx9Mw2QFBJF7xsyiTwJ/PAiVdlRZRZ8FBUPQM2Y0Er4XM9Jz
2SyqlBzuc7+OyUc4g77CevygHGN0yAGa6vx8FC4cSQgwLltiJbXKPtkBxl/JNj4y
yr2uhE+1zaHzI0U3rmt/ZFXyBqiONeCImcLTMRppG/MqZkGtINXAfiCxJxoYSWlR
LcOlVgAgr7Bze+sHZI52FdSmNLUc1lwsH4vvcmDbRn+UJTENvZnV8R9lvGuMk/cX
IFrkuQebqB0xAQpt7vMiZYq3iUe6xi5e69RerpP3YMUvloBFueB1N28bQ7oPlKcV
lCb1ho8DQNiL5erCBC31f3LfM/Il5rEZezrmsGz2zL2u0NadLzvDsGZG/O1JyFZm
pgZ453Q2NN7QZsil5jrJ8g1CS/NgPjHrhLGOR/J51kzbVRC5MtcpJ2yC0NP4D4nS
PwFnFRB92EBNovsI5bHlzqOMVq+oJ0Z7CsC7oRgaZS8KIULEd3/whEejuICJEh0J
1cSXBQXw/nfK7tbsDnipjA==
=9lpo
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a0aa4667-a3e8-4a2b-aad6-acead1e2f943',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+LaWNGuRJTrapSPspy9E2rocauWAsdwzm4GiibDVWxvkX
zKcmA/+8JfCkWhlrz1QbV86MrXvULxTi4vbUDsDmxoyfmmHrFL737RpXIRQjYx/a
mXCoLGQKVKpdoGVTnTPQo6olncsGTNKienfC7/LsxEJoXhhq1TO2hPCGAYepg4Di
MYLXXtFbrH/8fbeSS9UzksdeROyaVpQEbmbMh7Ja3Nw4xNeSpxsgjezgXScquZJs
hLgORR+kLgs9ZYeCKVfiBFtXlkXkbwTbEDwH4AUQXpwXEnQxtZ1+hQnNfCXfiQnb
sDYfOFklMkTYMZSAIjfyjBdTAEJZDbxnCxpuvs+IoEQMwK2+OZkiMykht7LnIl+L
9/R1t1e4Vf1FZfjAq5gk/wmF1y6MKq5lcn8HeZsILk6e9Df5UhEd3uSbshTfX/R6
M6qctkx/1MOeNHNStJOUIZDxtsXtG0noD+ziakBNqcKt04sNrjStx1BbqTm2GhU+
yZbNltWxK1wgjdm8a7BMW70KAeFQVII1sNpBOFG+lrgoBgwWK98XYVnvy2Uplj05
iep6VrFDNMOQNVorU+M81uBwnQp3P5gvNfkP8a12mmIfdgVNAwGT0/wt2i3Gad38
pIUQvCQ3jgxyKnkSowFn2+cLybWW+W8eUwJY81fae8jI+Pmo6+GqyRLOdOp+i17S
QAGGeg4FVEiKrRWaza7Yy56Dv9mkHnBUgWNvE7Rl8P/CQohwswTKd/QWuDZR0iQM
Y0ar31EsnXpX5kjYYxl5Vsg=
=lkX1
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a3d3f743-4005-45c2-ad31-7d25efb229d7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//d5V6XS34KzPn6lXHI/mvdSNQEIudtKB54uRY2/hfZ69i
tyiqHbmeSsUNKKw0EnwNYr/h4gH5tbKtvAGKxawKGpq8QkgLL5P2iXK7eIIlQZwa
V5jQsibz2y/eODup/eprHg7rWvJbK54wDXiU6jX0yvTpWdz3qUrPoTNpEIvCTE/k
24x+IcKmzAW7KZaT+xGG9zvLSv1CSUlaiy527TU3iRBOOPNACkMnj+9NatLSio97
DDNfpr2uHH7MikZZKdiMxB50XSpYOKeFkyogyal6trRVOLkzv4XpUUl2yf/COf8l
QZh2X0Mtybe/bHBSnvPIrK+c9n38Qj5x3tHXbfn/qDmlqPSU8p+LswF3rqLWjV8N
7T5U9q6LOPFW6aL9yHWbWxmNAQi25ihCODMcuLuSVXo5tnXiavcyRZCeD+ECh8tp
Cg0r+nKSJ+ThxBsApZivHl4//KpCzeXEB7DY/7DK3GPQhbYCSLL8ReW/AjCquwmx
us+Ao7HcYB+Ax4VgUd+p/G5dAkQJZQ8IWOWIl2H/LIx3Pd2U+l0gYSYhqJ+ofJZ5
RFMd7BdwaihqQytmTM1dkCGJO6VlRD025fHcnz3VGQK6b7trPRfcrkDbpEGTaZ8o
7PcOxdegxO785INCS4hSCIJW4HhrRkNgEu6Gk9jqAJWy/DYJf4WykJMFqZwNpM3S
PwFptwl58C8J2k47qpsyiExQ+SgVzF9MtWMQ3F+1/5uEL1ezXobxL7csZJBnB4QT
GE/bi/43wk0uaXeaNXDXPw==
=tmyU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a4466fd3-a253-436f-a9f0-9bc1a83b7c05',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+KUnJlxSGq3AqsXqt5L7mGvGfon1mQeMZPpvwCXMlKDvm
DsyQQstGKb3WsIylnYlBgNVfTVGxLMNLAXEUw4y0e8ACMTruGpZ1TqJGyGEfuKx9
VufkEwsGo2vP9uBAWpOCKkvCPsGk+oU2OeNyt0e15TyP4X7E0JcLL5s2OAPW2f5F
owL8aVuPF+wKmFOpJSxi40vpGBMZaMWLP+0r+t+iYMl6zka6DMpRghNsTbnaxWyc
yLb6lA3arVHuF+yxHHi0Lfbt0zEoIGQYplQrHygb8FbIctNij5RXxr8v5fz2JBZB
M2jUJKt9BF7S3JPFns6s+CojNogGItPVC9qyiN9uP0XEG5xynDYMyeOfY+tIrBj8
28urplUL95+GHE+UshK8HCn8M8G/nOTpd8qS6oK+ikVIn13NQb8ti5ebkMX3Yvm/
jE/Kdpw9RY4AR/lOcJJp0stfligpldi/uv43+ayRDTLEiAH264hYwBMj9r4J749T
RDgz1T1hbhmhIHQ9DtladmrM0akjOI3OHo8EXdqtSDdIwtFM5uy0Jsobu2sgYxYH
D4wgwTlvKjJ8GfRDoD7f4c2PrxLZUK+VjBE2t2P2atb/F/v289lZqcYvUOPf1H+j
ECXcec3/ETCVHsAUai9VHZUxO5+j8JeRN/Bfv6dhQ9BXFEk/fO90zEg5gHzCn5/S
QwGtv6lmcCger71Klcwts7H2fLqEk7higBwtT8wpk8kCEitsy/4PoH35RKZC9QUJ
uardPPc7QPUx6Jisu0k2OBqrz/E=
=Ysca
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a5d136e4-367f-444c-afbe-da79d5367be3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//a5BefjiaFqAC7VQS4fjdboDl+BgEwpfousUCwHTAGuCp
6EHPLYe8oEe7squ6Fd02zLtwb1jGmXZQSCiGHMlZvXKsnMkhue29rrvWIby+m1+c
YA17mGcjk5A6HMcyidRWjVOEmFmpIDYJOGrRzmGeJ1vgYQkqaXmz9xlPoT2GB4Pv
LWIhv9RWlx/ris0VS1lWjc2LE3E+C2eQEO3lHVY61rGWXUy1I6++dLgArYdsnPUM
1me4UwiqA9OnIbqecH29m7Hn0Yzb6MDHaLnqUkWwRdqE0Uofaeo+5U71jySd8DHK
kLAFUt1Mqb6MK5iJ8QUE4mk0JrdCVcriez/UyGMnPH/MiZZ04FuJgZJT/SPlfCE5
Uftlp2zlLJjMfFk8UoNqdyYGa5WIFPNhI25iyU1gxQe5vdOKBPCEKY30t9+Vc9QA
hvGqEzAl3xGWkg6mb6BsHO8rGc35/ZLfqouXyEREQl39teL7eoSTFnyhad4HD09B
Wu7r11R4NvpBP9Vg6sdl4EkiPhdHtxjyKwniMCwy/Z3DsqBO9DnefJQsgEiFzP3R
nQWERn5VEVzWn4m+agWccjX0jvN8Xy0DLjSXs3UUSXURWGqPOlnCMhaI72pWD/0T
sIgsVRfd9dBodRiDVQ33dCcDEdGjDERRYCk88grqAZhBxJ1EOi3DoKKjbzJ9lxPS
QgFYfq+SQCn908/x3W96oTvfVzijSDDxJp2yRYSZfAaMFrXHpjO8ELoQthQbnrRU
xfXzCd1TBOCEt57Ij2VyRxtUTw==
=VQ51
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aa015a55-ec81-4ee8-a0bd-5c74a1e2d904',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/+P0NZsvIMT2pRAG+GzV2HKVeVNu09givLyRDtMqFdPjsu
N/ADX4CTfT42yatQAyC0+DwsVX1f54g0JdC1wKm2flEZ7pT56e1p463zKhHPrvva
5hPzyDexujgECivSgXVc0IugiKrhxF5B0Vi1L+U5n4eiGdUqEU7qMFMAOYNSMTeb
m/Cs37AIveNdwQ6lO76enYM3DZQhuEcFPWM4SiC9lScj3UxHp4uGtANNYPB7MOaa
od2tteMH3REPfSnPjIMTzxs3N53kWdd7c7t3QDxNIVy0NUaSa7wxyQjwxMKvRn31
gbk7e92FgMrwFFjh6eWTg3gu+VRcZGMJyjsu4SLCim5K8WZl9qnFiClwJqkFn2hk
bLUUyvwRI4LakWBIS6JYDgbRF/jKIzBBeX5Dw2PYClNwSab3AMIUCEiVClqAJCoc
hHOYnKYmbb1NoSE6Faxso3R42QROyIJhKdGL+GHrcrYiKwXj5px1Zcryj4tTEt43
0DOMNqJkJALBg4EdTxDI+gvPct8j0tBidN9TmtTw78aKxq6VMJ7TbesVs5t2ZxCa
bfwNboYlWKWoqrC5VG++rFit0rgGX4BLRGIOLQ7Fyf0rCJf+SJlmpD/YTeQrtbLZ
h9iKcTqRMwmu6gX6PBWWOwWCZj8bnJamykacYvgFGakQhxcMFkgBiDOSrGEWxlHS
RQHHFXCXXsQMPmHB1D4cUwUKPosl0Prhmy4SBSg9ocb9XURGAMwtMug3wW48hp/0
/XB2JzbcUNz6vCzZ/jTi0rm27mt7OQ==
=srSl
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'adcc1d83-0fa6-4292-aed3-41ebd4113d68',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//S+mijhxe7NCiKq3IDz+AHqWx9TrxutwF8jO80tbRETlI
DrxEtDBvMsVNzcHp8e9D4hCHFNfIDWkspn49oZ3D2RA1x7L5bTw7JOdV8BMpJb/S
RSW/2UFwlZVyrJNN0/7pq5CXC891ZnhiolTV6J0bbXbz166eFee1PZvwXjeeU0t+
z1M+EUaQ9911KCfQVnHUhAV9hZ8X964NuqDTicobKFaYRGXkAHjFQ+gVGkVMFoIj
5CW6Zq/kKBWzWN3Ih/R8jd6CHkMj7B72c1D5AQQrLK5W3BPh3UdyxpovkIMc1RPq
ff9Dxf11df+TsHAmdLAw2uG7x7ID5ybVc5s1G2VIuVM+HbpuzC4XvtT6MCTQNw45
YHm509BctVS2j52YCub3XDKoGh5+QogsyH54XA829R6OTiuZjcRCTmL3QdRFf24n
bFk85OyGUC2VwYzw+c24gZyr2MXipnROa+eLKjox9vRMfc4M0TT477F7jzj0IjxY
aXIS3U1R6w7sl5eekwNkqT21CN1apXLuIdDo23xtblPnkzAkTdvQFwWp8QukIHWW
W5aD9PNbAeN34wvWZoE5tjZUzukG4l8A5HzYdINrXc8OTBPffJP+AZKzQileoObl
ebvyzyf6SSLbDqG7LLHqF1BKtIHQCXrS7BN27uy4SoErQF0/gA+ZMKwyt8EVaY7S
QAFblhY9rtSlt0HUeCWgjjONSaKp1FWoK9YkGMV6axYZiqRxhpbVP8KPoh1c5kbx
GwRaZvl+KJ5oQa+bLIRu92E=
=qd99
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'adec700a-f735-4ac2-a12e-33eb2a2182c4',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//SIHF4lYgLGCf4Z2d7wa/85pGgMXleU+jtHr8fZiiW0Lr
FaG994YjgKeHWKOazRL+ydRTLzKHfbrMGWyOQl6xzu20w3UsQ6wYW118dwWz6rL1
bL1qDeafSQelKTiklwz4xPSGY4JWvW+3PV/MKdchas0dURhaM8XTZiLXhnvjqiKV
oQU3A7TVKoLwgyf/giktFBFnIbHnf25DciwUv7+SPMSZUkKs6dHC+GA1TynIt6Ix
UIlwub2NKKRy5MVuCLadIM5upirzlN+1EW6mc5TpBbC6Blzvt3gakadfLJSqSuuR
hE/S4xHyWU030VEwWxNru9ful1lcYZ12/NyQrHir9SfVTz1hiDf0ciYe47vDknKc
xdNUBzvSr5DTRqjY7cKjTuJ7rVxtrZPYFZc3UVOpjfnTkZe9q1niDZgUBHuBEBes
FvxAzy1O/TjcrWKkgzjoZ5SGyDxT6w0WWp2ZdICgsw2PTuxq0FE+3hE5oXTKXUEo
7Bf+jdl5SdE03uS6kdzk99y54ycgV3EDrLb12aJpbRqsnzEvRikKQCDKxCg/BfbQ
H6D/TTMiCdJoYbl+YEjoMWbTqs2tz7Ja325RTdAGwYCourTWApB7DICCseSXzRMO
ffreF9U3TJy8Q2zTIhRRfwUPl9Iot6qGFEZJSkOv+1g93iT6D7W2m41AvZL7EG/S
UgGCmAKP6CidVXy0hnzWCBiVzJJjODZeg8PJ6nfdIcF22/+UNdANrxLMM1s8Irnk
CD7XpKu9nABdn/qmhQtF2bXj4dgXRTmTx3Mq6WTcvcStSjQ=
=+GuH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ae013aa0-1451-463f-a031-aca92e50be78',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAue8YYRbvmkPEgFRaB/3mdRFkL1moQYVAfEbgVl6vXVPM
ClnQERopJc0e1BF8QxK35BKKWrwaQcFso5iJUJNxzZR3tccm24GFhG9WdwtonTTy
izxSkJjAaUvt0BBepqNarS7OMZ0pJlnbRhy9+hrDNp52Kmty3N5pg0DdR/lNvZNM
9jub2IiZ9p0rt/DPt+X+YfuTGbpAzU0rlCGUdIi01wobv8I0YYd8E17zFGPpo34c
/fKsKHM9kSFvUn0XAPkk7GS4ct7QZGfLSh2VPfNqfcOEcQyPImgA4rYHtEkrxTXU
Pkgo9gH6fjlw+mZBdJUei8vYK12zKT0HgQGqsjTT5k/jc3/lBNwMlNGBUyEB782D
iSoqHqLj6bgFckuNlMXp7D9dwJKCp4PLpqdmvJzHO9aXcXxniHXGIaOaWPzvsz9k
mmtLDXrXVExP+MVULGrskln89Mx/u7orAUS1JsGXRi09m2AGUyyPMcRfc+Nz3hON
rcMHzVAczwEsn+iBnJpw08cRLXFMMkQL1N3XiAkrcoQcwYRk6vN+/x7F0ZQDmb05
48qr3bffOZl4OVgVMEfg7l7AB/8Fdb56+fwt4diWErXP/85nAOFdisKv9PEnYFCr
+wzdngTGdBCAhE5JWNIpiSoAPC4cjzCIiiD22hq8RT5BajzOXc/7M4SJzvhOBijS
QgHcNQ8+oBWeOVchxubdF6Di5lvXYjEHiy122ONLkjK82FIJImYdgbxtgsjC24iy
b9hlSfFtwIfQgFtE7sZO/xc5AA==
=rlDx
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ae33e7c8-1ad9-4687-a731-a0b118a47587',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/8C5u1EgLDO02RU5N+WkMPx20exTVUMxJz5E2vGPmpHOKz
QQRgwsGJNGml/b2vh4eKe0T5iraASTiX6wpn3ZHEFftp5GtOUvgeZ4SrD0uhbDod
1VQVu0gBtc4phiH+r1W6GaBietnIQUhCDEyDRB8FQAP8AzLiZ3ajMJJC2PXkQ7B4
T8jFqFpgNf0WaIlr+4NRAPQMcwY6CXKTX+fK+QA1NVsHEysAnJk6BQBWzZCnwrfg
eU4RbKEpPwYxyr1v49h7Jvqz7AUPRYg/UgxM1Y6dDaJiGQCMatk4GOdXCriMM7Nd
dIYvj6CUPTtwO4UzF6jDthGYJbNPbIXcCISh9KzA2CWZuAJt2Vb4jt/x33rUVhbQ
2DOUgndEaNKF/vlfU4bRewl3TxH077KPTYEtHKGXLQxYj6up/nwiWDW6llnXi4k8
QvyUMF2CnErhwVb5MLfgjd7ErNOZjgZ2YocYnzvrACswz0hyIiGVc08dOl/8KkDv
VN4ciRIfaaxo9mmO9wwzzTICF8g1xzjcA1HSpaDiUjse5K1fLnuWaWr6VEE0lLF2
eDsltbHMLN4Fzevula6cCoubBILx7mcVuv3MmgP7sCOfcMm3csg9zlNKAOs1VWha
dcv1NZTA3jKEEOuPVRz/ioCu3EYQgOJsDTwAad1BzXmDy0n31kLlsr1bwAbWpj7S
PgEgWTeJl22tBM2noAlRWdfdfV4CfmyE9ylV9pEfsIRmMLbu/m+kYXVvW87IVb5M
IfKDK6rj80rlfuFt4+c7
=k9/4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ae377c2a-7e88-4e0e-af22-c7d9d2255989',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//ee+YVWqQfl9OKX4w3PbRbGlOLOqOgU+cvsqgsVkJfAad
Ddcanm1lDEy7KK7Ec7LeybyJN3CZopY59fI6oyGrnBgubG6wCvsjn7iBep1S7h9N
jkhyDXFC8NtYzykly0qneOlFAbssudlKZiY7DIMn/qjFruC+2QWnw7IseBI0G6fP
rW5AYFNcluBXo6yzp65hSU8K+GlDcuGGfTLZg0QpBm9J3SfddkkHPy4bCtREJKs+
NcrQrj7InyY+dMkrpT2IDHF2/RUkx4EmBcofyJ406N2DwMClxHud+7xY11Oai3o7
rfZ6ilSivo8S8wBxgo7Uhe2h27Vv55hjNqr9/sTAQE+XjxFj8/qrdIHsjXi2IWXI
9J90a05qrfl2S/FI0XYXaWiUt089WWytAMPU7skgV40POsKz4cO+0JUf+jpmu/zV
HawGVASXkYGz21KYD4BQGrJ3TB0iEcC5bF8fxKyg3KZP+P5645Wfr+3UPz2W/+DE
qZFfjm9kHmbN7YHyGbd3mfKsNE5vcsgyQoRshEIKDXX2y6CoJfpDyFtrQChRrkql
x7qr9CBTE2OaMqZnnh6gMWIeKnlpk3c8l5ioIThHk2aCXmUAP/zUZm8nWGmjAv1g
2yh6bkGtI19rtz3DAvBE8wuWQgc485kYzK13FJ3mrpO19Ybaysb3ES5BCAosYLHS
QwH2QqFAcfebd/xyCBR8iEL61MmMi2kh7WCt1e5P5ffvoJaUGJKpEl0zHexSiwux
cagv8pveyYNmn8f9LhtrvFiJgaE=
=2qrq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af24b7bc-6ada-4501-ac74-eae0eed40db5',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8ChOyzhJXh5tCzOwMDekachZLCf8EBb4i7kXpbrhAFeqf
xTYKCyEAnWOcbTlOSJqwE0LWh1wRb7nTZU+iZnfjZp+4XU+8U4/DPOlzAIw39riU
f97t1jgl0IHtLJhbjB93bOoL9bT4iRoyNmIbBxIA+f6Kec9vJ5JQcvwfiwnk2SWd
iLNdCBQJ1AEEy1lcoqIV3Zy/Mvs3k1wg7ig8x/REyS8f1afkD7MJFEc4DMDgWGzk
ZVW7PGYn+qaF9ildBtF6TeSXeNEOPv2FDRCD4WU0g9va3eV4x7iMayL7CvCL6tjt
bnL/JYdjKP/zaj/7Uz0CYFGSazmMln3PZL3VhgKgbJ0Kxu6ZQ0gmM+OU6B7qFAPg
X9twzyf42+F8AtWyO/jOYIqjOTDDUyGp7qF9qzZqljau/zpaGUNZhu3ulSpJ163t
g0PnpM6H8wT66aPgVEhQCe9YMuMWlMZ+9EC4DdptpY4OL2zbvHCod6LHlHAMowPl
YGHJXk6Yw9QWuanf3rJRjTXKqrDydIK24FywjaLXh61Dx0x5t9AKIw6W6qTj20JQ
bAsYJeXtsaH3FiIQRAZeSy5O2l5iVFgorVGuWh5IhcZ5gcxVbvlS8QiiNBtxwW2a
2GzqpFfRpyIHL0qUfMqH23lnLfiVD8Pot/lhzoG6pnsmM5BQ1Ob3H0gYUxXhrADS
QQEK5i4RB7gUngORPcwoM2Shzbqydq0vK4cXYatvKY10q4IK70XWkEwebXB/m6mW
nfQHQ4zogb09f0c3rpGQjG/Z
=RdNy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b20fec2c-49cf-421c-af8a-829480618799',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAApohWH/N8zImS9rbW0h+z9hFlU3b+qANt8nUwOaP4EUjJ
A1T6J11wjFfci8zOIZO8ugsApcAd1ogXjadbgdktUY0vnXzlSzEkByg680Pvk23p
wm/L4WuUe9IHK+ntmNaeCGjzx+zkpltTH6YdsdjyFdro7jNRzHew6I2L1ohiYEW9
OIvtblWCyug353DSkot6XBMjoXVzoO3U+CjarmO3roT8heCuwgNW1HqMlSZkoBAT
TLNqG1WbL7E5Xqg5RxZSSfPx9v/cyi9+S7dxaD7Sy6/EaZ2SEqzSS0IooLvvNZyp
/gcUF63E0nVHrme17KjJ1wG559K6Gs1b2uiNXM5vF5MwvfMDRZ91JhW/JYBdSobo
3rOTAWQRUGxBxRV4asHHPD/mXJqcSI/U4VidAnVwonLhKbEWnQ8IIdCUzCNWqnDZ
U8tU10/e4/HYGoyfdUWN9qbzvoiXxQNj98x0TF5zTVOsGP4uUpf3VzuEpBclIFCT
0uOtAjIZTwihZQfpKOsdonw4O9uTzLSph4a6klGd6/awbd02ZZO3gJxgCEYqZ0Tp
d/bybdidu51GRAKZ3luT8CpcY3cp4En2/SCbF9XYT2VK961tny1ObJgG3nWs7+b3
LdvtNBQZRnAYPA+jkqbVluLdgIx3WWzTAGhLiB83QCqeBgDYtY8AcYKlm7XhNTnS
TQHOiQjkMLkhIse1eEt9bJibVqTNadYXzCTTcmocRS9lE5mwc17NPNHtUHP//Jl5
+GiDLNWgZa8d77a+vsFiJzCc4i+iqoGno44+XLFL
=Uz92
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:42',
			'modified' => '2017-02-16 14:44:42',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b238bffb-26cc-4ba3-ae61-2208e6fe13a8',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Otq/w3foRjWXuFVuAl9AwyY6rgvpww8LuMC1nzuaUV4Y
E/RhMb6L622SOWwo2x3cMCapasOT5zSDlCyNpplCi597GBLk3Cy7C7lh3sY1JvXl
oeY8OPA4zH4Mi3eWdn0HSND/V6Kj3gMbNpQsVTWNLywO8KkM4vQbkVWxc5gj0MOV
AUa6+/zf7aNvZsAwBYq9Qs1QqVzddO/F0pi2h3yA1WAOyc6kmbni8L1WbXPeUZa7
8Qz8YXGDQlgn+SiinZSlvVXCwSZZBpz+8YV7hQ9Pd5uFjzWXtUbdVJhTHAjf781j
JDkp4XactHSFdYt5S8/tpLZXz5qphwitcEeDo3IOYHkG1kIbzUcW0eY3JbrsfS5Q
YrL8O+apFdZgNJsSsDj86ak3sVG057F6Cb2/5vhWarWoxczw5Zp7fStfsTc0xnOa
bcplVqZEqcGhl6oW4Ah5Wcg6nWE5hVTAMnbPuPWPsJFYrjblxrZ9f8NJH8jHjeiK
RgDZtCVVhEqYobrLvdwUe5Yz9Oahgj4hgIHl2uUt38W7b7tck8TOh+NhjCOR1V3F
GUUAc4+IVZoU26YKmmlwS2y5FHFKw/oERI5m4HKhdUcRiGKCgZA27vp5t/bfYBoe
Yv8FE8Bc604qiJW6Iu2ZvAa1istPi1iHAEmqua8kh4O4S3fiuReyEmnERy0OvEHS
QwFpINI7NsDP9966Xwj+iBcjPykqL3AanvPU4U4gCbiCPNtaar+PFYshfD0mjAqA
kC4XZAOr9Tx0zuw0I5Je+TGtN7A=
=nR5f
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b40f5b29-5366-4eba-a0bf-9084d0ae5fc6',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAmOuD1zWMtPV49hK6RDsYygaWV+JDvgFHq4Hzni+THGvN
TCTg0RAf8dQu2YhjpgvWxg6bGZBCN4KwLD5zmf/db7M7Vj0OWyAKWslJI2ZsnxLj
C9FzSycqyqrBr/HXtJUh/r1zrWfaHM5S7DSUx9LbcqNCISUgKPfgezRTqb0rxMeY
oSpjGf90tdW8YYfNNdEg+LZxuZFcVu5HF0bKDQLO9QQL1b2AZS6bmHSx5pLIKxOY
xFN11PKATqJpfUeS34auUD5xN6mQg2KkWThdurCWKbYkY34n0ufT6PmV3ZH8tr8m
3StucmgP0oYzEnz2erPbYM2Ngak0xxMT3ZZ0AiCEo8wyzKPkCXcgY6S8924IYgIY
0HxDMiCd1iTFY0CrijuAM+a0A4twTs16Tms2TMeVwiQ0cmf4Fui4dT/Bafwjqc78
zNEn7av6rhjC7anguuK6igwC7ToRAqAUVN5ZjJ12xWCyIu48lb7vW4XPJLmTDdii
j/gyLg+FGDltkpNgGECfX8uBPDP0xc/y9WBOUitKMn1wF2SBxUG79ygPc4BseM+p
VUurgPsuBIPF4tWJro+CDcIRvTVuMZBV3/nD7mcCuuHYXVTx7aDNLX7VUeG4jFHL
OEOqoFFdG7+vwL0BQV5/n5hovnc3pyRSHfV3lkGFHSfppTQt8Y2kAy9S2I8lxybS
QQEPy2iDvZM6Q+FMO3dlgkmGrFhAoOyDkUCT5TuysG8/B5HfEYWoDIAb2OLMZBXI
mAhLg1pqGLS7u64INWSHtv+Q
=Vj2e
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b48c36f2-bc48-401a-abb8-36deba480650',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Ssk9+w4MiTyagBoQkNYsZ3v4b3auKooYs/ewSkBsOG6K
XtXeEt0cvDaLeGm6uRMpPezFw2ETZRavV7OOXRw7gZtQ39uCfEzGxXZ0DOn5/39F
mphzljwAMylCSd8b2dOpBJ4p9IHEL9AmsumMB+9lzCSW6ezgoDEpt3x2BwWSyiWJ
x4RIUNqu8LlwCe1QYdrffk+ILVIJJjhXijDhYhkaW07zSsZOY/hRSkwcc2A4oYlh
N839D9hxXA5VOEnA72+n+5LcMQIefdZcQP5Y3MM6Zh8e2Yp24WmJMtVxaeA+7HTC
oO1oqh97QzzDjAWUQv5WMjN2DE8YOt1zShp3hm4MYLHRkTtBdPTbRwKpJ3Ey63LC
K1Faec46vPCI6JlW2jQP4jOVI7DlPQPaEou0S+KDQBVU3GRCtDlhnZ0YR2cU0BbC
Q6KASeR2oPWPfebhKQ5LYAwQghChFBjtv5vyRTNmanz9nkI0Cckv1kkjXe9OZEdw
ebevizFAvAqc2ZrF4rBtl3fjHayd5v/YPj+t5VbdhIhYpsIYl2rtB4puUD5OfH57
/cS+HDEiziLKsPP9HF/E/YEbvifv2ga7728kgmP3t8szUKi2SskxKEVsTZHrBGjc
N2XsEQo4MPg3x8OKdvCBtRkbTf0JnX4FvvilPGKLVRB+CM0iteUkXdfTq8iuV1jS
QQHp9Vtfk7tQkxpzMAYDSDWBs+v7Mtl9jIl7tL8wePFeKUCfPws6BBWm7DKgZNMy
aV3dA0rHCe9gDIhoa40e7fFt
=kXp3
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b6192ef4-f322-4295-a61c-1d208d0d60c5',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//W+4+koA7u2gixfClWuprjHi6xzDJIu01ANh1tSoYdoor
HXSMxTcLVSOfmkY6gvjd2b0TFeaOuH6SXWz/Qt+JDmFlS/o3CUPT6gXWknbwCg0Q
jHtLBEaKGuqdK6U5Vf1wwJ0TI6INPftnms+0k4rUflsW9oNxLOy0XCwfBnhqOxit
j4Cf49dMXtshhJMHZ/LCB7ImHHKl8MwLD8d9UWy3mVWvQwlad+pNQM5OFyZAjOKJ
2DeAPAIo1X83PUqcI9d+48YgvIEolyQanoalNyYPi2qzNMf0xAvUpcx+C0Nc87kh
q80u7aFN1MsmRlobM8etfWfF689kd6YOCHegp6kYz8qsDRBFyrYlVakLF2aDIh+j
CPbVgNYxqLXm6Cr3JLJhMkpKOr7wrDfaQr3BJ0BFOE7DaLgRqa6yozKi61gP/wzu
t+JpL1+L1GWvd+53sJuyyP8lGcRzVKrpi0MjxFRZWPBJ3dL1o175RUdx1elZa3v0
QWLyDo8ca/L5G0Zq2j4z5/BGXLDfnfNowItah/mJSd0c41kp53ARBM4PCGyX6oWg
RKv7NBuBjzTPEoGgM0+oFkhgbwhV4tLG9ZTvTAN2CSg+M74MPmjroFR3pTkkogBv
biaH9jcRgfWm8D6wlhpzr9yKOT13PZXwA6nTZLo9+Wq6wUKFPqNufhkapYsHponS
PgF24zuiQMNRmnJzXvHIqrFE4aCRcd53NW4/qjvG16s/W4CDLrEj4U0EbeWkbYP0
iAFWHbDrIYRlc5w6HKzN
=wau/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b756c5d1-3ddc-41b2-a31a-ddec2ea38c0b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAivKsLGcBFdjMdPfMhBjDhy5zX9lFImoUeFTT+91QloQA
a7Nwnd6zUbs8lOm6DGAeywGCpiZwxY3qClBGTSfT73w7LjgTRqbqUOSVJ4Em4UyB
1ilyKquSGqCzOg4KoDZLIGdiQCe/1k8qbdPCdp1dnWfr0FVz/rb9bBvL2JQHt/vg
deIGL4gBQxR7bCLK1a8bVmGnmW+At6W2ivOsZSaaEwt0BzlEDLwH/Bq5eg5x/LTb
mThyIfTedDd5TjTmOOYVqZY5NNpXZ7cRQu1lQbzT9J4C3mEcibkth1Vahslv86Eo
08Y3SKM7N1ECFN1MUVT2YLpJWLC3K1ge8aAfl2ob3asFReTOv3neCgJ+Hp3pHKc2
M4ZNskmSWuExRCbNiQPvrIJK269PagCXBuBSdGWcPNbgAyAf9sC8Kpr3qWd1504H
nFlUFErIiVYS+Jiz/fR3zCIaPodocTO+6NmC6//g+eKYoXFVX0Sy8UM6jEMEKhK/
Lk9pS6TUR8fJX2DNlxAZflmF9a5V6L6V6dBCKKX9f90isKY+4yleeKfTEQWLTXDW
VPEGupqnBLJK21I+7T5pcEVKiwT5de6d2dOgekVpILb4L2CAen+nnnMYiMO7/LEu
uULJ6m+86opxd9DLuL4+WsMg/cBPWjGJacdJVfsyELhT2HGrgEw6tbWewv2xKAvS
PgG6DAJDPf7OgvbNle5ObQcFkJIIoRa6v+iuuzCSJBmxZO7gq0x0tX3MZX+TfAb3
0jlOgTsbzIFfvXpa12jo
=xKST
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bbd88ac9-018a-4feb-af24-a6542cd1e192',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAtG/yjaU39oiCWweovk727NBhKi7ZqFh31skFN3bfh/4+
5tXyuJ2UHwZnLLoaPXZK9t/quey/W8dMkr5WLEf7ov3Vj6pilx8CFnvO+33wUUJz
HJdLvPe00ScddGbHJ9RNGPHQ1vfgjD6e5yWJDOjBna2ew75WYXqvXqGd+yL1OeNF
MHeGPCjl52eHP0UKUBHLUN4NeXENPecTmpLd3WvxbCsZ9pxA4lY7WZe17yy+BqHQ
eJouP1MF1qAbfX2Bwu+fa1BUIIObfyi6MUnI1pabFjjYNiz9rqPY1AgeAfmPBxVm
WHpAdH2kMGo+uq190dkN+uItdmNL/0r+9C6oHBeLCQvAfKv0e1+TgiBwtP0DOVH/
66Lb5aXeWK1zi9mqvchV8dSr/C+nWgzsS89JeqO/BYPa+GeVNEPla5GPVj1VKYkC
ZDexQplMJaQV1X4/+uW4kx782pxrouaFxx1fIC9LQl8AxMz4sBjvwnXoz/y1066k
lPalwuXf1Ho/OTI8Y2nN58p+EZt6tV5TiXEQWUidQV6zxY/AYAloIjc8LvFCH5ou
qevB/1EADa8suUaABPKJwOPWE3ibM6QiowZFPvIAET+8wS8+tgXDfb0dk9d+envc
caIkBBtqBIDmJXsci2PAmEH7Fg4KIu5HKJ69VD1gZ9UvLYl0sHIu8iDFumKaaXrS
RAH997PG4iw6FCpJ0zFD+/DutYO+xxt5RYL8tt8niN0JDAimROaAqA5D/mUXVEjj
MSdiCyJAfVEHYWZFs6crTs7bIhkP
=QPb9
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bde0f419-9e53-46dd-a0b9-cfbbcf9e03fb',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9Hs2/1gpdsgJhgENMmymBu96QLAw34X1LXc1qtEGK3cac
Xi0usXYeZO8Vct+77L8eK4ewCBueAM72XcZiR2k3v2AggCN9vH5FeXGV1/zgskEd
znA7KgBk1Fuqv8SYLjMQVhJi/gR3P60gmx3ninOFjbstkbsCZsL84H7d25Oc2m82
Hgil0FNlDKoG+gjun67xj8w0+XLI7ujA35G7zh6jBEU214J2+oi/8p+TnapZ+138
gVCGsowgIleRxQ14bHbGlElGW4wiDy0NhzMFoZSzvu8O1dNVV4xRa5Vl2jmUfWEw
yatRhQA94DvBPE2mVrmCBu7MHndlNWKKR1NBdtSqZDihL8QItgL0+3S2NE3MySsL
/oVvSpGSQY8l+tW2wGGEbHHvDiuc3t1mAGZl9SrmN+54DfzwJp8Z9tNEf5jXfsLu
CWByd3Dr9AKiSBb6/yIgVAg+AnWfovqoD6VUER1lj7qeL2ZSWQhkHFzsNb3PBn8o
z+CCVdDjG8jRYvnEHBT4wxMakAs6Tx/SzlDGkJQ8Vm8mFQuaqCR/UxDu69e2T43S
/Eutf5z8L7AM0CdzqPpeFnzzt0gBMeEWXWUo16QUMpcoU9VthR5oCspEpUQ1+Z54
BH3qWqXBTRl1RVAcpN1lAnN8KrF8pQQAhXXV2HmRER6BJ09I4NRZ7b8cxOVZyoTS
QwHL2Qe9EDF/5skrsgTIXQxluFXovcfko7WFlI8+SiWjeSViGhWLtEMjFSc/BD03
kO8DhZTPLL7p6AHZR9UEnA8nsEo=
=XlPb
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'be18831d-5845-49db-a365-8586a5ab2790',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAoyfNIWQfRQs4AtfBu9f+v1Gqik4hf74BRh1VukZf7XEQ
QW5dc36uvaXpaqTgDwFFENop55Rj0krYwLRBox2icyz/FsSMQfe7Dx4pBMStGmPq
dnSl3+sUkl05LbxLeHmIKyW6asBsrOMqqkR3LvlY5L4RTfCGUcXpBEEDN8700x0e
JtgW1W/rwKA5J7k8Z3h5hfRnpafJWJvqK0Usqr4XH4jtgWAijq919V568dPxPYL8
J6tDDGuvtOxQAwuRZKqVobNA49vLLrDoTaNc+ry3EPQaMDxyhJgURYYu0u4lz4BJ
u5rens+XDbB+e3keP3dzvvc/WWdvqrwlJaaMn3RqjZiAZmON46jqK9Kbg5SmGfEZ
rtNnwiB77raSjLPOCVjBKFtGTODCpHa/EFaJFXeBitAyF1bzob2yNgVdj8UdTJ/9
pY35+7xOXGaK/Wr+dJhO7/hUeX1M2sw6PuDwbirWCAV/W660sv0RZoGKEEvVCnzK
2GXQna7k/yOS8ow15tvdk9SNrN7iQnfCKBN08X+za843DjF3mcfSCljBFOzRuwGo
5o6hGb3Bu/4oGVCY+rBlmJ4jp6SUyfnqJmcHeGVyaTrsW9YLcggvzjrFC1C9wZ1g
5K5rVESBGqKtpfSVApzBmKCa9O+ZR+qRxI7O+FHTcVoTU3uuPCbuXAxbBGZkgiTS
QwEDeH5wsBczbWPzAVvKUNFa6UXXa9DLx6RdFoQyWWERuKtn/suTXUc0XC7ACRuo
ZzFVMejbnEAoAa/UHGFSLB773dc=
=7fzy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bf711553-01ad-453d-a023-fe69c61c2ed3',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAApmvCGXHYQpI6DyHlBaQGFvAkHhbEQ2+7ufNCiQcFEWtK
rwH5XYUA7i+YtJz2lSP1LdYXNg2VlS0cIdCdrTKKkdUYfVU2ke+bMnyOgFtH2LpJ
3+1NCfmYlH8sFGj8Vp/SBL4lSaI0LvGwnUwvfJPAuDeEGwmByhoy5FDfbHTcLP+L
CJn2GZefcojAnbE6cdFUyofrFQqCMmtu5JcC0J2C+b5s3dzrg7hzMWvnetzgjt3/
KbOLl6rMDn8N9+P1APDIMzK1wDvOQvRxs4q8kiDqX+yuwpcJurJTwltsK0xiudhp
mqfiuYjEB7/oFtJ05GHLAB/y+sx0A6ouexfytTFb/puYUr0c/o/B3LKzhNX0D9re
xVT0gwykuzmhac1Pf8scmf4t42NpNcl3ltTdSfCCkZ/Qg9p+OmZy7sQiZBmNjOF+
v2S73qinc+EDBaYtxwla5imaaqBe0jL3RzXFshXpog6Z4l5v1wlzvJjhb7Mg+7Pm
cNMahl+/FQ9+1imDVJTnbTa3N53KMNFBge6dFb2ODFTf/LduePgYxvvL4bqxc/7m
tg5pwQmi50A6ZVjIhnl1XsfMiFOmCyjLVN3edHxGAc78H4Mcvmo1HFPpzrHdULTV
sWskYhUy9MquuCpyKqhrDi2zyQ9MmySqST7oW/LeDhM34vWvlYpZwOSkZ686l7/S
QQHZVRx+QQAD1ZCvT1KI5oJWx5fm9U64v5k/o6jjG0tOMwT8iS+E3Kw2KIhlDjsN
RBjs+XuTYlGdoSSwUStwx8a8
=Qvnu
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c0e63b3d-9c9c-4515-a3a2-801ebea9c3cd',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8CQ9awHhG2sqZzRzN6Q21mRAe9zfzvUnb7MukQwWQ2Jq8
cbeKwEnhyXI1c+UNnEgLwbMHRUBDzG6y3NZxO+X2jGiwYaSMnODXDiyni7EaIA9m
MEImY7K0PyjX70OsApwy0yXdcrHhBjH3C3usYQqeez0wNN+hVGrGbp4BRmgiFi/K
B0TIWTQixSCppW3meTPXIWvDipizxHjH6z+JuIAxfCu35t7Cp2yZrPOgbbfanVnG
49/ZVu1K8WX6kLtinHuBm6U/wo+9h04Xv2ws+qn9tupX3sJf+IvNAr1sT0pvGQOY
eMsQdMXHEJe2EgcpSMDkO9Se5zoYoAfWRbNNB89AYAiiTHZzdsbR5AZiLgsRihwL
Aw8472ZTEEoRivx1AqusiMFG4cpa63eg5GzpMyXxZIpUfUqWTXHpcN+fLCCyAh0z
17nZfzkzGbTcYxMxZA3ZwWTfk3agkn7wVUlN3hFLZxgIMs43yX29SlKB89FNBL7p
ifF1qjkzEmqYUwfc0smXMZCBuTSQmsAFHuMHAn5MozI7eQTZ9AwRn76fle5W5Mxj
YvZHZDln66udveAo4mc0zDneXBO49DcL0GsxM2SQZyKLLBbLSrVtHUjo1WQ178sG
L3QgipRLVyQDYlHFjJYkxLAYWfH9+1PJ5tGMhxDv/j/DmRRI6QqC394vDOCUkKfS
QwHcZ/q8M3U3IJnZQ3ht6jYtYVeiwJnskdjhyMqUOJy26fmCW1TeAU3Y9ATeBYfW
CkLa4XQlCdYv4iWWqXGuCfF3xwY=
=ddCe
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c12ef77d-b37c-4b15-ae83-771b4ad02642',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAl6MkddrXH3JzKyFUQJ782uNGPz53SVJsfoXZJzcgZ2pX
l8mcoWlGwDTrZt8eogqEfpBvqQvNCBUbCH/c7+gvajYWbaMajXnE2+ZtXTx8CJtt
+9YScTzyILnveLllp+Qi+wtN/HfyTNu3KI6zFiE4jJB2xy+cjZEquEH9C+qwACQd
K+eN//wfzR9IjWPsgJ/MXwp4XMbUN+Z6sQgSxyGYVws3DGWAGKY1qVMlEQXiyXe1
LAnaT8rPepsXDOXorO1JMa9c4DXDflJ4PG66btI1sarM8KI+lQTG7RJqG0b1QGye
idI9oC/ZjaVArj00jX8mjxTwJ08mglx1IBBb7no1GPvGzIk3qsavLFy/l3J7n0ws
qC8V2sz+d/+iGWrhBCF67q7zk1lsIEi+j6i+0vjRCzWX54UDjTqA6zkeShO/H6w+
gr6d3mwSozo3ZTiZ5PnHrJNoVr5N8uXHKN4MMMRa/hUmRYkBqOp3kF4DrgbjUGUT
nD2o2KLw8sGDCIys90UWwgYpFmwD88SB7317Aacnd9ybVETMnQ0YJIK4diV/IlWT
+0Dr98ulbylrXMkHn2ePIoOFG0vfFhwD5QbHbu9X3nROD9b151mz8TUSR2kA4DUs
zYEAMortxicMTUNqhPm1AMoB3bkyICnRAwY/bTmXaf7Uup0iqFBanGYtGIehzdTS
SQGJ4WTGdgd8SH2+fAwj+52F4XBD8Iy661aJbUerrwfmJigW82l5NabcID0QkWJ8
cuG6ZmcSDMuGAAS2NTw+6VWfrdSOUXjXdcE=
=50al
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c26d30e7-eddc-44b0-ab53-adee3a24dc56',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//d2RtYYckEz6+zEGjKt/9pfB9N4HktnbL2+udSPCiHRBI
ckJ8ANEHCrqisQXBMhL6p+v2MW464W3a+SFC2m6MZzm1JifyDVwOYpKwcXFHz6Y/
uXnNMPKl7VyZU+OAXVL+0SWtXgSUY190VmstL3Pw8iKzwLwNK5or/S0gi1aItfFg
yFhFqcOq+O2c3eJszYN6lotYf7ijsC2FYeOEHVi+5356zODCZ3bkqMalZnTj+9UP
wrxk6jHm8Pz3fpNCPfT9fVtepP6DTo+zCeHwgk0uYnSWCExlf3gDlvFrO3I3/f+z
QlxbdAXwNCaTtqCr2uZXHbpyugtj5CHExxQ1ZsWay15BwNTjIT4qX9plzpv9mdGm
NlZrpEw4m2VLKmOLhHCgFw8WKk2djH6fm8VLoGg63UT804EN9OU7+rk4AdqQlxk5
y0SCrehhF4bzdosXXwURoPqHZyjDwvO0W3dqOhdNXgvVkicnt3RaKWkM+b6NiOj2
mjtK1QUROtY3iAtHgR3mvvK8dLfRcn+xKZqNFTbPXrQEsaXdGOf8xlHgiaDCF3nl
6R1MhiyMq7v8+v2VR/Vg00PEwfTHpZw0Lna2UmWRsX6p+eGP7GUd9EiHEGLrnA7e
8SoTgdnNSHy/lhgNLBwKWOaFYU16aDRoCUlwc0A5wmCQpV7CxPmE74mITc/P3nTS
QAGMbaM7KqBeipCfWthKzH/+RdNYEqE0hgNemb4WWeUhsA899WfF08WuqQz427y6
1WZXNnWWIC6CWZyxPS2o89k=
=XwaU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c3dba6bc-4591-4868-a8a1-4a98373c3e4b',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//bE1ciuOkT+tcSSmwHtj0bhE7NZCOzFtEChR1MtiN0oXl
IgMbKC34LD4ytXe/7X9HnJbE+AlKVH7zCRwy0sLYufedlbtoGYEe0qJYCdBgHyw1
tLM7coBaV0jDSY7JycTKoZ5m+37yR9I+VBBk3FEpzxRDHAZQAFnZuVQR61tP3Tmm
oohk4Vaa5dLhpAqDBYcGR9UaVn5UP4xDbB/SCCqTE+J69NmlXvvkVmNwgOKV0O4B
voEjouWMzxgB5ZHaMwfCKLjOPM6FXaXdbYTA2RRLsANE3igytJ4sastaOfq/H+a0
uao2Syvw3ApRcytqkpAzQm9KGm5oaWH+aBd712M3VCbgQrV5GdRtFbeSG6q3xI8o
ZM+Ws0lyygSSEUtXaXEl9pPNzpn8sV8kCSZPTQ+iCVEhGWUM8YDKQAtX6d14kDdQ
7+dj2U/h0cUdOyPb0RLZR16/GK189PdhSeqRrG3birvqzVcs0vv6nk0RDvVFVmX8
7pIJGf/rd5qHe5ACZXQiJ3XQqxEpC0kjkXCs1zwwd3/znchOtfhlpiLxORk3gNGH
32rkB2dsrLVES2RDCfCBEVG7DmPMDp82yJdb/lHxD19Pb9Ud4c+uo3YRpkVF5ZgP
jnoJrtRJGUqdaRNx/5ng4yZpiRCOIioWqUcSI1s4c4Mx3vEKiyYoCEB3Nb2LgVHS
QwGHXUbO522Z4FmG4Hpen+9biIIAZ5aY104Hs3vWmD5b7jUAPDeQzW/yL75wIxTr
I4UoQ29VMKR7HwnVUmZm7lqTEqQ=
=Qkuc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c5008c7a-d848-4904-a4af-22ea34b272cd',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/8DN4+V+pJ+gszg5Q+v2+rrUb4icuedMr1WkxAKpCyTuxS
CknULQAncfwCOWdUhDRyfYCLHFsd9gTYHzDrlEusyY8MWdk2aCX8Hfngz/gpnA+C
KXt5kTNJSj0nd6xWVIWzHu7YhBOXQ2krY9Rrim9EhJSsG9IpEB2O2DiZZYV7Wz3D
h4KuyWZCytBw6N2s/LNxSVFBDbtksh5EjGg/YrP81dfZM5pbVySxqyfYiihQIIoT
IafXan/w9t/bZuLcll12dfoDuZVlBM0LxnYCTvldGJdlVPdQJbDq3JsJaOGa4JYj
1lSRCO1C7vJn/zdf6XFfsdhn9qo5SizW6RO1N6hMEPboGmXM0dnpzw4t/2a6rado
oXz5CnwxEN3p1u/Qw+U6BVXKIsCgupsEAvwxfScRSKiHbHlLdSxoOp4Nz6wkEy51
IsNHiXzh0ZiT8agiRJ+mOa00thKp5AJ01pXcqNVqmLng9u1kWG8he63Y//OXxCIj
eEAC99gR8ETnhJ558j7ql0jk7EG1s45VfVcunsNYeUraWtC9eBQITlxWWSZgO3/c
ModHulxIt/Q57f7UiFG4R66bcrIul5ZHJQeo0eW1ZYAXxuXOVyVtALemMDUS6Ebx
HlKM3wkskL/kn7EneNhwAHixdzdIKjJSmb0HxLSi2WL/H8A4eGDFPDvOC+XDwuvS
QgFa4I2XTpNy5W1tbderbpFqZ1sar5aYTzUVlC9ze78wVT4Q+HnnbrHWkF81fpes
rLF2RDkTODf54CvssIWjqu2BqA==
=wvsm
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c6950224-3dee-4981-a2e8-04a3334f0d17',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/8C2yL5TM7ihftdES6XOEjpBD/6xwlErRZsUToza0bN/DW
K2EzBQnCtXQmtXui2QJ1lQFqcB6WcML3H7DA/I6TQbmGqLuFV6vVi3CVy2Y2w7Cd
btIXdHnYjnkHz5cWSAbiD+M4XB+rwZsJDyXJihANBXtuCcDcC0vRG5OpbJdtrgSg
ZXevnafUbEKQ0Yj03o/B+V9XyJ78IOj8ZsRtMqgInkWaABKExHmDd7ywpoRm87dr
hIzEvOQ0snqHPQE7c3ntpccYwuKhzxPb+rvBxMWJeHiDDVYQKpt3zLCnpslMdeEA
XMK7rkdINVeeGnBc9Y3t1Ay0DzL9hd+Prun48PWeL+f+yaSBuQP7ldcm9KvOzIeA
CiPpWpojw5Xb+RRUIyZpzIoPNsmwHgHaMgWFuMgo/nTHA1HbwmObiJ1zCQqbuCUh
SyUeHjBr8SzfEB+Q93qVGSdPH5vSF07eRZVXCwRhDQ70epUaiev0h3/wg8FgI7gg
qC7J/NBCFiTf3knDzhlTo4p8aFMiHpge2FZtIhiGnG7ZG4nnsoEaZWApSmFuViVi
bgrkskwykEC3VRi5twpWZPRfJ1WhQ5V3xWN6aiZzqq3FvpBYg4hKbmq/O2msCnPL
5PF32VZM76iWU6xUq5NbpiTUjIw/V8JP4adZ/Oh/QQVkIkeN0xP/VTU5/msDCofS
QQGqRJCA2jXLcB60bJbhZGqU/bzAYbrSKGtV/pIhiuRreb11LOF8Z44xCm0FBHjk
aCx6eltGAHaqU03Trhda9ww7
=nrKi
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c6afcf54-7ff9-4710-a924-e3ecda4e811d',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAl3kCS6g0V5fITfRCHlyFCvThXhbYCPYwwWm9rMg3CWKm
RgDHa0KOGnWHtII5OU3ZX8ZAtZIltc/QZyZX7BgPNTSLl4exFNJx6Bcv3iklfYK8
MbxyOJJzfDjhoFZ93EZvr9NEKHC0VL9hc5WBH1tGWLdJgzCd1pJZxaJKjXpgzRxG
Ga8D5LOhWRqDIWxZr1056Esp0t1FulqKXLbNWo7DlG9TiMfPj51faR4m5DHvrd/b
6ms0HvGBmMwFZg2tVfhCJ7qNl41r4GweOAcGRYEYOIQjdGlJrBlnnA4RLcid0KTl
k8rOWbCkHUJR/mgUrmSL+ONmCw+s+PGyNAM0s469vwQ0W5GXlIB27dfTLEqsha/3
TPmLf4GmQ8ojbCmUcgBU/Z2CM8TDeU3rfa2+z540qYXCPUhaB8Yc7IWuA+h9gzZG
kqxQhDcZlBv/lGarmMfeezhvoCuH25DdjZYIMJQ1y/mF6m0SEa33qVodWkuEfoav
da9AYI8hhW42iIPE/J2DfHjYSZAZhM4sTQW8E3UWdI9nR9EqpuNnaZQeGiQ1NWB7
+w2vYLS4JSK7qsGVrHUJwDvWj+UTSQNymrvbRN9rHmeHP3Ss67xTHFXfN5zC/pu8
hOSizzWN8EoCIs7tWwikXIXgyf8jHOqKwmq9yU+nSkcoHvFOtcpnmqRAbcgCYNjS
QwE0OJFrdbuI6OaTbhC+uhLRQ5jUaJZszK2LsKPdc8jzLWDUPSTBVVK85liVW8ng
C1J5ty8Zg8H9CBA/KLFOi+dNJxk=
=2zXW
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb3b556d-6f2f-4623-aa67-ff14b7ab5dcb',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+MxycduXWGHp+lMr/9o0/H7roFYh7RCyJv8tqUBSqRvrp
Cm5t2gvz2aQwORvGBv0dfFQ3Jt+t4Pwk2hov2jeU1XmfzgFDfIOhTMRjvI7923xd
l5izMblO3fPgTCEz/Q2qdLO7Z/FuDCE+/VoGqIvGPV7LRfC6bC4dWbE3zdVi3zLU
RT+QEBdSAeVqKygr1SZ8+VnbdtaISO1FT3NOeQB9+S83ijXsFy+c78LWigGhMrON
5Mstfromo4r5naLz2oSKX8N+6TwHzAuS8sR0D1LeQT+0YgSepbxf+vwu4wXvT74u
tKakUyko7k/BM0cDt182+K7wZuoOka5iKV8yi9r3gQYeJ6ba6k0IYOpZmwRWr98x
Thc96Q1fuXsa23mOPvbUG1ffkJ7eD+3Kx8cbf4a92QipV8avNG1ywP1jYjxuh6V6
L9EKnzzU+BH29VYm5NjtFbh1QxL1bN7pOTBXReX0TiPLSIt1lIyPUjv4waJty7ky
hvpw4DNaPIojGTyJ3iQVtv1c7Z6Hmt0NuiAHcMaNTeDU7vqDUTDqoDm/dwARUpyT
MIHy62WObXAYPtT59IR1b/Q5iyGkO4Q0l7RjEdUD1h6kjGWl9Z+e5cUVEE/dixaT
6u9MZ++gRZ+hgqUVzHKMpHbtuDYmZ48WOzxiwqG1bzPx0cVEVJKqWEB1NWFS+wvS
PgFSR7nA/B7KXpJhGk2G/AzCJ9F2EnPALI/qqcoJjyMOyRBvTWJViu7v6vKLCFbU
nRrpq1Shr+6DmXDMrEb2
=AD7w
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cca038dd-b354-40b2-a8e3-b33a08ebc2f9',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/7B1KWaqCJWhXizhgwEl+bdcsZCs5JwM71XwzR/0wqymw4
Cgx77SLSlEK+ekp4Nj6AopY/PrEiLYRfc27fmpwlo9JXg7AsWvmIYjrHN1OOmWE9
9rrk5wZQ7JA0k/0sDXJ7XExcT2JIoELzNeOjhcPelM++0qjACEMsyWi+bPjq4ucv
pMcBBJRx0T8io28GQKG8QkISMUn8+UwABIJSD97thdadS9nvCBSYrrL3mole0DXv
5fQHDFCM2QBu7Z8Q/fNxtMHZ2RlKtr8k/MUfWMUYoBJ/qCeTa/dArs+o3Ddjqxho
lUqtSHN4MGjbZKB6fMn9nl0z4/DVkkWdsdqg9YHrExFDfdmTFis0ltuLx9eyCuGf
85pTUEGHfh/GxDUv3zRZfyFK017fp75A5Jao8AqN8sIZLdkSjJyUXU55Qc07lUCQ
EelAJIfNADnDiStx4+khIu5Abu4zOWPMEdRbio6Liu/6+gF8OFXMFAbwl4+BkDY1
MOVciemoUNhfTHaGJ9mI8S9wogTEMkxwpQymIuHQKJWxZ2k//YhbmmfhVfv6fQ7m
SndjbAk0AyshJY7QzTc+fsHOlIw/Diz80jKsUdy4fjq559Zqu21FmhNL0rfmiFCx
IUqn6+cfCYW/XLfRuay5gv9V9vBmu1SiECP5T2+C1r8+nIGZp58STtQFZnv1GKHS
UgEzH+JNYtDvE1BgvyIwqFbfBv4kvBbJiKLkcJ23wmfTQPxtw+NkW8j6wGLf91SL
23JKanuGJw9RllyvFtkZbVQ/VC2QjGrplqe0rz7+QFUF1A8=
=7ETy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd229dfdb-9f36-4ec1-ae3d-8c4639f3b6cb',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//V61pDkWuKKhVR9esEt3pJFbbjb4Y1kE5wm9j7/Gvi1v3
7Z7IC0JK7hVNF2OInnwtIEfK6I7EhAvxpLOoMQmbHFE+xIofyNYI2q1NGsVraJGo
qZVulAgSkpUOnZOz4wlJZ32lEbZPSTz2CvKzPFofPzOr+blC26qjKAkIpuewPAMw
NcSBYkJfpG0w1yv7OYlIwqb+z01wiMImRUpFq+MekDN0Vj/5LxIebL+hy2tdR1KF
uwSyc+z9IIsviecE1ijwkyCOhYnl+Y/263gw0EnzKYUGiBvT9lXIAZWaoNwGjGTq
tcqjZECUd0rzRD8LxRD5+uHGcsiUenTpg6qrO3rAcP09fCuS996TL7hiVttkg0A8
N4LzLfyqdRqONzGlJMfTv4BdJHyUqmRCyZW217XuXLeHrWbLObCgm5PF6OQxAIFZ
ZlMWubkdSJREDEcOSqwzqkorYFg6QlWjv1aUEGbLKiFhPIyOgknVl5rdj6W7AIzR
/amNScnQKPlgbyLOn+9Q8wWy7IcBP/PhlQ4z8SKaAJd6tIbygwRBiPO5XLkmk3T+
EU/zsgwXZRoXrwNAzSNeC8Yp52zuzdzgcIQLXtH53tFTSJ5iDjmOXSfIcId+M4fr
Xxqgnue5MRrehiYq+2W3dIABJt9s7/WY+69W8A/zPyzO9WAIqZIa27+SovcqQT/S
QgGaoA9C1py9UCYJ6v9tTbaxwSDLpNSpIjzlypGmuwaHIjJ7NBM4hxvqWDmmOKFF
wx+VNo8I0wpUpaxVjh2tPfyhoQ==
=iCnF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd4379add-9171-4563-ae7d-71fc1b622cbf',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/8Drjx7hU9AqtxRdgPicvIXugTe8ulz7G5Fofg4eL5aK4Q
QRCDZrEW94ExCQ2cbBHNO0Scvwiu5zk0JYuGX+Rw4WobPaRG3g1d0H0Qh4+iZrYU
bfWSQsBMptJPqNjjyXNMw3bAim1s+rk92zcbEhhR45G3xYwo4TPBXt6gF010jyVU
KRy/HO92gcvNKM53QPWuFd8Hvca1NipuNqhh4NYx5yV56w3mPkk0I3bEnJZ+QsgD
HvsFzMa4cTXzYzp4z+mJg6y7F+EE6OlmhAupKCKSNK8VNH0fIJx9JYuliisaKvw5
8SDKIFKyruEoCVEbBwNoFLRCnnEblR4Cz9lcvSmQ3pCLSTzxg6Zw4NqeNx9y4MOD
gr2rlZEoKy2/YJYjUAErYmQ2XukCOZJQxAYVxlbIu9tRbDklqOst8hyniW8gOxQI
tQXoIRwhdCf8A/v/8YsZrAc1ooQxqcr/vPzPnEAVxk0YKo0Ct+TkV+GK3yvyM4N1
UmfCFFV0NSs4IAiwAguu1vq9pJVHFSjX3iFLJeXJt958SgLHQ/vWPCpaxosFMS73
6+SnkKTtR+IvHtJaaxYKKuM0Wn7Fk1eex+FDtvmvx2pr/PG7eC+UUH4PRKImnB3Q
iB7xUOqhAWRevXFpoeHV3YcI7zVMJIVpg2h3tSAJ0MD36RTNX75H69Q1SrC8IenS
QgHnKhYQF9id3PA4N5R9ikBJyYTJedZuFqlvaFueWfqoXTqzVSyUaBdqy/EDMiuh
2o7w6QpNTzczA+s1yJTupVxT6w==
=Tnj7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd85688e1-a2e7-4b48-a34e-11d3882b793c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAvcM5OGeUp0uWpnmGxFFroGjYpGjP1z5ov0LZvBbJJC84
pWUzL9LnVgQUmOmntYaJZR4Bf9tzsV6mLzUa9FPawFIsJh4KHRUHUw8RzH1Y2PN9
1tL/M/pN4X1lP7AtQakdsXcmQmA3IiXd7A8I8PlHBFAqpkmN0jKJfxIenf2qy8Gg
2Wzt79rlDIHtasiqAE3mlabKzj8FmWCwDnAKGwiyBFLpLCj2SVeZ4y3plpOU7i9+
gmKCjJOX14G0CgauCeGMsYvA/GTTLNPsIuZRZ79CaTygUDbBSqUK9tHjsH1KAA5U
/5TNOMvxFa9kfoew/Q4lpfyvGSPgj6bbwfuiNpCk0JKPcWekq0YTsv5S1gza+6QD
iOtmTdDfzkcshRA8nEQ9vo5FE7uQZMebuykoY5vF0Knv/pswuqnV+G4gJ8CdMlwf
sHSL209MNv3RExjKTc0LXjaeSDREN4VkRBbSdXgdEu1v4cEuExUMHbDGsnfayLSg
RD3LpntoGTiNeWN+JG/47XcQd1dgdeHz+XwwfNAdFLz2BtxSl9Ci5rYaEfvLqfFw
lFMEPUy4Rcz09K5s5USQLlMxr3CvMhnM3pAINAMIP3zXI3HKnGvLJTvL0NgbfRdn
FcKDr68s41hQjS2cGr1C9xR5s5KzmwQpvfPySHibNeW6eIoH+YSrMPBdYVpxYWvS
QQE+eQKDstheZKxntP3br+mbPMsge1h8v8DdREwd9NgYdz8ocu1/cNZ5Absk4uWK
NCVsGeNcI613ZmRgkoaPLRjI
=BzGE
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd8eece5c-81a8-4f41-a283-a061e00f3c54',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Uo2I5yKpAn6tRU02Ay5Vg+J9GoYQmD7KkMvJpQjSjxrF
9ySWrRg7OcuCtxaZHbZw3c/NtRMOcRmlQ9aAWcQ+NmVAe/gvRobyQC0IOdDxGLoJ
0WT54JhxNyPuPYNpq6QUH0wfUgIUszQVbsLwPuATdcZtPEecxAlf6h7Q5CX7JZJ5
So4nXgyH+E9pEE0UnoI/PLV7PMvPZf3V6EuGWeOdOrsQQam1f/PClR+Fab3Tpj/M
0pA3eyPoJTqtGMZ1dYn155ZaF+6EJLoH+sSTKnnNAsoFjjkaW/KhWA2G87ym0JrW
Le4tNvA6JTQCBRmq9L40XYiG1aLQBRiTVtCZiigifRbKXc1zcd26zlo7EBCiNkPf
/cEajWoV6t5bkpewJSENwaJJ1uBERI/wXJYCXKH8S5nrJEBI8WkAWdN8sG4udHCJ
AHPFk544GXXNYKOE0g5QzxMIT1aNVZ/XA5ywZgSG0KkBjSn9k02huGxqxyAg74Ik
rrwLZaxyR3Tl6a4Q8WZiTg1B/1QUFwJLLdjeC3biTAAejc7KhDvR3YB+1s5Ss8QU
hbAxEEfteNrANRrVXLdyH8w937tmlLZEeiIPRkYG9KWnpATvQ2S7WYvNntRFm/IQ
PuTsCqWKnRu1A9JMx1gBzk9to4A7hFm2Vdp/jlNtaL+9ZOvTKuSnqqli1WSGLGPS
QAEYt8XP8OcRCfLAgZEtPkFDravBOsCqiPhocBgM+ykwj0fP1CM/JU22+vTDmN5k
XMQ8Ew60+rjZ0yLF4H/W0C4=
=oE+q
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd94dc73c-4926-44c1-a8e0-cb123d7f764e',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//USlfD1C7Y5wglfCRXW1Lw0ha4Y6pxb5N/+9UsMxiXnwg
5fTVF0drKRJ6rrFejGoBPVnl/S5cGyEsy6MKtp2U3lalKPg4QnuRlOdR5pqXD9eR
xvNjPc7LRL99QD8aK/mPMfxHSz32OkKrtNeVuJCwWSGb9Fu+/GeF6lUlhLBW7HNt
W7rpJgAwK5GzLEgeHKKtyf96ivGoAZAK8AIcLZQiE00h+h8DArSn+OO44ocMOwir
Ng31RDogmgDLBAF/UmhrRLkVBztNCZYXRZqeHIF0to+/i+kOSCn7fGfBGugZZkeY
UDnx2ZsqMVCWIZYyUGTMXergGMgeaP5qFiF1q+YegmVsCxQF4u03AnJ1gq+f2XGa
jc3Fv3j/0siWdrMnIl6i+1lvX1i1LnWn6ViIabsu9RKDUgIXUmKMH2cOJHsbpcEK
M07dlRvKjCv9KiP3VBFhYKQWffrqDSDahbqScYte5mw03isty1WLfpqRa61smCY3
xmOPK6vOVcUpV8qz/xh67jVWY6uTcGOw8nbT+Higc8OiV4I9WcZa959oxkz1ja/+
VroqWSZSAYpwI7RbNSOxk/7QmkNCJaUid3LxaKAbZB5bLA5tZbvC2PRVOQi2KwPz
d4Cswlay8TLAf11/5M0UjC0pcfZxyrnzOvPX2uo/kFxNm38AjcXLlwMGrIGuBmrS
RAEAyjsMmEvsc8T4psYujyq2TFVxh6yolaqvnxPgdxCqQxn0cw8gDudCspB+hMen
JRTsLPe+hcURKipTJDBgycmfO6WE
=Qc4F
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'da25e694-7bd9-4e5f-ad0a-d0b96a6c5a2f',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9FseKCndsyW9gptoFK6kBACErn8Hx/yU77zUaQyLZc9oy
8sV9Akoh7dDtkzsSzcLCWPfCY9MMo+8jm7fr3tPhAA2s8PSWtzwdMNx2mQggo7er
+TUkAPkOL5bHxB9nW8mrd6BqzwjhNxR8XuwzPjqnb+k6i+8tzKFQ0JsvpKdlmcmS
LP4vBAE6mOMadXAv8AE9N3GJzNt2k3iMTGefKMddJ20c3KhdA0RFBl7Y+QZap6Cv
rqHajBlkWijdZGEFZNNe+lMljJanQBxxR9q7SMcKZnngtBi0vHR4bhl0sMOVOKdM
Nvjnwpc0tQ9WxvlSINTvb4KhTKDzSQnOhajgV4qb9zZqeJvqo4Tj8tsIYj1uSWTG
fJJZbp2ziEH0ZaxJy4nazAwGb0H6iv8HhW0MygAaRTP8FNW6ztdla9ZbmKe0CDZH
p/sTNKm6s5DokIWqeS7AiMVuRwYk6NfwmjxTpbbr03yP61xek1UEUR0ZbxlBS8Pz
SD4HiTo33Nj9bXNcf1l+xGEjzo5nIKgAUZQMm1qx4JAbD90gnoHeDju93aV7g1uG
DsqXKgjP2myBrfIWpYGkCoDCyyOoF3L7HfEVA8lcG/XcAlbkNVxy+gFjAd2/HyRV
B47/t04tVgS4SbkhtA6sN6nlxOhToltQF1BlrMEMTv10cx4SNzjYFrbE55XpK1HS
UgEFk7wQhs+RCNpS5yROcA2+HJThF0qV7GGWRDXouwAgfjkkDEmcO8IxMk9JOmuS
g7yMrA6L13ov9Ch0AI3A18aDPgAygr4jqkQxf8cDBk2yjt4=
=BRQD
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dbf2287a-6af3-40ce-af7d-fed874ee0ea0',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/5AS5wnQF4LERS16QqL4L/QNTnd7p653RHZ7C54rp53QEe
Zf6lTuy9h7b3VVSA5TZm4TlesTf54OvNK9f6FM7rrBvvOQ9ZcKx8NjO1i1yp4U/K
OuW8YpbWJF/0g/4vu7KfmPVlXqyNWLspmklbSjuIrsQxDF4lYvPU1dAf3o4F0Foq
nRJBx2yIxtVldNotZWV+H4ND1b+HfU2LxsjrWMy3HTzuF+qxy6rSnNdNBjFebW9W
G6YUGsDLbUtMHpbA9K/3KWvRvqF2zHmLwKLX/vUqxLBbsG3ZBgunS1dV8MIGhHi+
VRzgpqIq/Yo+3ZN4sbFgnj+DNO9lKDkscfRHrRVQyhQpYGaeOA1VWgDwNha72Zxv
/sdiT/9gHP8W0NL7KNgIoWCYquoKPP3N2Acm2uu9xwRvrHq860IjCJ40AEgPqPO3
XJjgeO8feWMTST2YUL1+t/8iPwma1p3KJNfXXqg9shG5xj1SJCzVPt1UqGTvx7p5
5qXWjdnb9PqymVbf0y3U9sHtsF5l/LSQK9qydXZHkTsdkQ7ZSE87wMald5cpefMd
tlljURR46zDcwZXg//zCh5kQgKkvVS/zipb6s9hOllK/S8xnibIfXZcLw0+qKPfD
SiWrk4MlOSt1dofj+/nlvrpu0d8p6DZtDWDWkq7xb3Vy9jrOUQlTY0s9jad95snS
QwHiO1M/Jzu5jPX8HCc5IbRUgFzchFwwoMktvadgmPw38DBBv3SjKU0awWHPqYoe
OofSSxMLXd3aZt2Omw6aLRLkY8c=
=k/82
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dde66cb5-7f3a-4d6f-a1d1-15e62ccacdb5',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//dIsF0LipeYIjDgHGGrrnVBU1lGPhZmqz259XDPVR1QsU
/gfj2nBjwh8Vo4XlYmtrriEvH2yKyFkhQYHjbvorS7qoZKK4Gr8nD+oiTmRCBM2n
Hx5FRTXLlkAgoeBTFHbqq0Sc457Vzz889HQQitHyMMdAoWu0Tl14hb381uFm8FPE
wGBakIKJ1IYCmDtBemtpB81PbleWU+sHerR2+3YtDSnbmb9pYERrPBcOsGShvsv5
2x2gDhTwZHa8Sj4BqI4ahrcVfdu4T9olWcJiSYW6io0Hf8jFbtZpbuUsqiUXsygs
2NtO9Rh75HHwv5mxuUPUHdSB9X0fwPlIbuQKbGaQfzwxKCmH7EIBGhp3Kmtpmhpm
OD2nKoqX/P9SktE4O2CiRtO0rQlVziTugoQiShZSdD2gtnRG2tC3DwhB67NM7wMY
oHCVdaH6bgkAlLrfZ3Lb8VnDAF2mgoJXUMLkPgOKVTionOnmqr1X/YqRIf+2ZeKK
roZ2D8L8xD9lvMOH0yr3U0cG7Kw+4Q8RvOofWa8Ne/3SVjzFWTsjtOxt0G0frXjJ
hXNEdDeR8GDpRDI4PkZq7H13S1m/UERDxZOoaRd++h9RZZ7OIW53N+UN4L/YbxSk
CCun/OuYQCglKShd+Zx5aFL1u6nJtp27qpsWXa4lk+Bh2iBg0kNWzxFY13jj3xvS
PgEgpaRF+/zHu6afsIPRZIgbVo2MwJZLUNbQ/h8CJusETI8EepRcFkRJYNKlZ+5M
RSPR12VBvmp7HGDMuTj1
=JBLA
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'df0c2030-dd67-45ca-ae3a-6c08979cfcf8',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//ZC+JPLtR1rFr9DoeARwbdFCi7DrhQ01IAjmEuO99k7b9
LfwEclr6SxN7vx04l4DcRDyZWulIglINzc+q7qV0MAfSJKOtgELu6oPQf/rxKmQe
URSYRPQUmnq3OEOw/KURZrdZkEDDYvoTDrq8b7Wshumr1L8RxRjxzEm3p/kLlEFt
AKukBE9/r0zqiKf3gKI2lUihn2AGh7KCCrNTTFO7QC6b0dHQ0/0iUkUdOua6Xn1k
GtyFL+HQL0hhzo7+9QuZ34Dla4/dR5VGI3ne5WdAHKcrmUtixE/G8yHlrMBRAxLw
lUIezerClXUKgpU2lhg4fFZI8HoUxw4tT6gzmg3ENUaleLBVnSy923P0cgsjAl5t
5ypcpWB1xSAAlK79OPEnDC5Hfk4ZdoqR4LXRcP8EsG3GlpR3MsSxlm8TtYsFhalh
bZrvhJDIHoE5GphqfSDHgXLv1zRsMmaG4min/K88lgQV2EVVZonPxhPu0j5BlhZ8
ahCVi/2sj5WONPtRHjBZf2FrNQymgzamSDM9isnzm5ivB4Nruw81MepeGopvEoty
SS7tPOOT3Vu+Gcnn1a/peXAg38g8SIFZC2jTGTu85lVuZ+tYj5t4Ot+mzxkLn+iU
j+k9B5xvUZIibOb6NTJjhGiesq/SSRQmoe3SyQmEl8UDvvUej4k0kwES9tIN4JrS
RAFQ/hYLzfMV/9kXRC0ewWkcYwNxVmvbYtzzaAQSvSQ/3aAdaABdYIhXI7fed+xV
1Go7zoyyKiiNFw4lHyoJwKPuC5Nd
=3so+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e1d4f5df-c1e8-4577-a614-bfb3ce48eb6d',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAsXNHSnz1I4Zl76oLQ2wCidEFABAFUVF2uPd7D8RZlGR+
H3++lGj26tA9JjCMpcoQLeQOysWihhzo2D4VqLQU1jlnPw6C1eV631fZSqeKCZWg
awbILHaky7PEHi1Tp6GyNPCMuj94xX1qEJHrCKAC2Ecsr31jMv18e7Llzp2mPanL
nUtcM8Gj7wWb+e+UccdcfGQW3trSAaCCOWZg5mrUbyw6rGcCFhYs7RHHE2H/4hGQ
xvkJFeLyZC0QzQZ0xy+XpT47W4o6oqMGXQDXGvcUmIJqDfyX4gmRngwfGcN0i5W2
7rbFGp6/U2rihE7/PQ+hiurMjEfmyQJZzt3qw8pqMXJrtwZLrXC2jFjRs9PwkitP
TUN7P4Q/yEpY4sebyRJpf09gJ6sF+0kSp3FfP+G3qjx6ZKkQSLicN6ssQ69EyTxk
+th45vcDDcu6AYC+7BUNzgBIfK0/l/SCQy44JARrTTqNbMAjNgF9KqvpgI83sQvd
NMgW3krBTvZauKX0TFUFY2Ft91wje0xIA1pn8bRwQ9rYEK+1hAtJQIoyGb7UE5iR
iaq7NsFfqkxEsqQnvnR9wiomVGD+SMmkeD5XJYRi8e1fg3b+UIqFlc4JdCyY7Ktc
P2l7f4UyBQ+pFuZI8xjVaQH59fI+t00sSBVOvElpKRiVGTWzXZqc/z5f7Euk863S
QQEL5gmdcOqIHpyiCUbm2CPYc3UMqDAOhAVzytn/8hZ5Uxab8QxHPn/9p3JckVnZ
r29HJYzoFEtfkzHTMbRUj16w
=JfZU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e66efbcb-5e85-4262-a2cc-49f3a386377d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//cxhVGMX9xVcGHD0UYcsih1xHHFX6iZ0cOiR/4hA+jnkA
rbRbngfvreOEucTwkd+XRRB9TcdzZj0dPTTUqhDGFX1k4dByAchAm5OhfOOVqtl3
XIXXMLLEQ04udRfNYZsDKES3kxRrTf5fgYr749HfCSCYl3h8jIuDmx0yXUOR8gkc
CqILu5nYPVlgzFjuh2Adx/7J8tcRSNr1/P0LsXaCGSZ3tkrO7EScnARuUQ7r9nqo
lYAChfIWNBlxVj3kHJlOAVpyNfvzS03Wg4Rklv5rd6AJHFaDU3h8Pcq2ZHA69F+m
565oiuzVAiamwRTMxfQijM8tdWJJoqRZqTIJr4nHG8XcYLlHuRJsLn/FzTGALp5e
BZRlQRmzJAhu+EK2TJ9X8m+cv1aQY3o4VDCj6V9FxB7KGs+3YfkT86KgL2wBM85a
hdhfcXI8vKiNZpY/47FlSXSthnHCiFORw4iGCFENZUd0u8chCyllDRx7BvxaXVuS
BrPTO0Ozhk33rrGX8PoQU10Oh8Zd5sWU1foX46929e6qE/bpDShpvW4XlCKkvvhy
CCCL87HXqlXAdXPwv1769aSQvAuNHvXwe5P1jqP8SsOPAjDvtyu4RSl1QB5fLxJ9
M6JPBXOZrzUrSuFbbHbU6cy2r3BkW0XSew3YLrOWnHlNWdLiPnitriAA2W+Pv5XS
RQGx4KZEZgzzYVX4KtMtdtqXS6Fxal7L2NuQ8jlpsUsokLVjTQW9nBQa0GGZhq6m
uOk8YH6t+besb0ctFvJ/20rgkoNmiA==
=vAmZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e724b71c-d0f4-4049-a03b-9bc829f58d1b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+KnQ18t8QWBchNEOnrYD+zp3um57to8BAU2kOs4qMXT+o
B8GDzyjViNPQC58YQHBVR6XCGhwoIcpNJ1pC1zw1xbrGdjvDzsbn+zPmIia5BbvY
Kuv9j+oRVDyO25kdEKLTIUxQa+rZXw7FgzRfMG+TKi9cgtf49QHVGZgGig80fy+h
K+/0DL1f7x0UWUFUH+KfDB5zYGOIt76h4gySivUm9W/GEzl0k9ZX5VqugwgHs+LI
ttzj/i5RWtnXkQDiLkZU0phv3TBdf8awGL+Ghl76I9FR9cBapr0MYNAF5FwV7lzG
inIb7muxAfCNsjbAMmTv+XECJ8qqRs/hbQMn9WfKUQh5Z5RjbJJUeDOPA5icWW2v
JLkISTCcaEOz80ZA8IZLhjGwDqoJQfAFK5zwZ5ZFN8gWVY5gthPdxeLARNKOwuk8
w4SNQIuYDQbuTffL3A5LH8aKvW6GVUYdX+rO5M9xUBAjuyAcONUHSmQHxxbQar2V
Qv9dTeRPnQY8S/5h1h/76IukbK1PSKm7o/r3zYZo45tFMpl9apiXmbFAhxaxwj/r
CpSlHTZZTEuxyGI6pX9GSKRtq621pVgh7QEOyg6KBrkWMiT0STK2VjL4WYlfw5kO
RkCnh/zgHFyAD7fr5KsVNX0o5eMQ+u/IUXRCgRhqT+Zo7p2WCSKNp+zHFqo4OM/S
UgFWo125D5inXpq2/IIseSLv7r++xFB09XnqW7Al7vfQf+mKSVSNesPjB06a8emD
wfLDEaMi651+ZPt11Tns16HD+YGxt3ieDsklDqMTZ+svckM=
=VL/E
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e85584c2-f51a-4da4-abe3-05cbc91281fe',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9FR9sRhSeBJ6aW4qkwZhuHL6ADmuV29TscBpEVxk7frXn
ouIkfDQZffViy/E2qlimG+xo0sUDWBdQw/8Dm3Flt2pV6EKY1sqYX+DaOJc/jzKQ
7yM7YuFzOjwi+MLSGkwKgzxmp/Wcbfy2ZrVWTdBZX0sMf7NjfWNGAwluH2euSqvA
p9WR6gk2knhZS9w1tVi2pTEqoqDtPoMiLZFL3Gr6Dk0o4Ezbr+slwadYMno/9QbB
vHFk0BIIBRdpdsIq2qIy6q934mc7cOzKM/rl/UYwpavZT6VG7qcRJGqPBbkRWpIP
Kpt9/2mknpd9witWnzqEq5vX3GyrpIlJ9EgnlhMxSdcqapW3rh0XawxywaNddn5S
5LotCYqfw4wleWSppawoJ7Vujb3xFgDjuCYyaEbZJntJ999tmTXUZcH18/ZPnZ8p
mxcNoregap8+0SZi3Z5keomi/mOqOaEyDmv3XAIJ5eWAi82g430FjvP3lSI09Ru0
KXcRVIha9XYUchLFPvthocqf+UDgVbntlPaTP82y0uTrgCB7algDUsT+XPGQotwI
IOUrJxNvr89wr5uEOPbdEjbJNKLvY8/8Gsh5hQHkztudRlsde+bF1YwLewAvdQW0
YCagpb7ZaF6C0+20wq2boWIA/vkWWUlYO1euvxug2gL+j0j3LHo4Bc7Y86+iq2LS
PgGpxzF7PpB05VdqdK32MRketu9XjG5T/hs2BIOs2/vIPqxe1+VWR1fzWavgi9ZI
uGjfsw8cQHrp1PVc53k1
=0FRU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ed907007-8503-46d0-aced-fbcf30796171',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//aZiC8TJ1SMtudjvLFFt0VaVzTT32wFwCniF0CGCgHvEX
fUZWPQvU1w9LHA0f1poPTIcFRT++XxZvNCVFmQQCbQHP5aHyNLrgU2sVntnXEvXG
vIrM2vRoHhVG97bZ5kYpkqVWW3c68ok8NiVJ4+5OYKniyDFERRiYOMwOpV2diNwP
fYy1rHUzVBdSZcETPemqal1OROLbvIPZNTCKSsnYsz9EqKzkYzeV2SWGU/dw+tkV
pC/B6EFp7cZzT+UtRccw7iOu9tRc3Nu60WjgouB2nB0DuKpxPcMt1ULNw/J/cJ2u
zqPWU6NzH5FPEDALo1WIp3soZ8UjPFKUs77sOiVxJuotOzbsfhX5+MaNJ/sB8btS
Xdi9YPndN2rG0hYWx1GMRaYrlcOTusJlBJfdN1U6u9GcJsCkcht/vGk7leCe0XdO
CQY75A3NQyQXcnff6ZtUnX6+iookzPMAvzeKqPB7+j+PbTpU8I9PiTV3kNx8A23m
sfPO7VvOnP7+GjfMbsRGfLcYGmwXvmRjAz1ciI2q4lBdau/n9vR6xvAbwlD9n5YZ
t4Z9+hur8qpmbISzS1RSp8gSPNe27irXkotluaMettp4i6g2KDfHjt6RT054XhNM
RHAuVSl8UOVIi0y0UgEUzigO2Lrv3LofugUDUkVBtnVMle0BwxAiHjTy4XZ0auDS
UgGzvqaLDneWQrAOkR7SSK3QWCJAasyfdbaZBHJt3w0D1H+4Y/17jOcTi1aGpIsx
ldvCrsgac8tXekfhTzS70iNrvSqgAgn3NkuInKpamK+A0fg=
=j+A3
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'edbc7bef-f7f1-4eeb-aae5-4ed95222b8e8',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//XmTHjY0bE+3eSoxK+LYVwhGqkcaXnDUYhQ/FUWXquaY3
NByv/G1Sii128vOSVB6RSNWpQM/URqmdfQp5LEvrWE2nnvyARIAqE0ZA8heahP1e
uYIXbfrMVfAxbKPMKD36EyC7hqEA1QNiafvBXBIgfqSGgnx30maYI8SvuoDfIJLn
ALhlYTl692dI0IE3Rrwekbfwo+yPdWUAJZRvoo2zurEwvbVmtm1wZejjXgubXlAo
FSD7f+k0+43GTdOn9JkfZO8SjCttNfB6wNl3Vey3s3Zn5gHTEuzoKvdwceS/iitR
0kJ7mNwHasEjFtjJ8+Ox/oZ0ltkZXTm57/Nx3eZ6IZ69XPuFEAdKSZ/Z+3/EY6uR
cg667Qjqq2rv/axTljjRR6gONK/5Us8SUwmOHKv8yv/G9RMndqfCvkIpgmql5MjO
Ze3iccocg7c6xFU5VXHjtV6hqXJpG5CqsH7OVwA7tQPBEU7oAOSqiI6fll/AqI1y
OjNYW8wOZdxtD8M4cY60QJMzeoN+uvrPTClPYBJFhVy0IUt1sOC6dktS4RleyonS
l0Gs206794GSBUB39OodiE8pzbHgCbTZg5I+JcIcChQyoqdoRrXGWStZRF7k+zD7
96xvkqXgEcQiDGdgkF8xp2yXm9xtL/eDWQ7b0VXzLdUsp9DozTTOjNqz68q3fkvS
RQFgy3MHbVw7KkHW5eRek4VAvBTMWqfamCZXUtOmXPYmtzlgR2VjACbeuWxLTuEF
QrJfKudAmZU4VXuF6+J6HiBMBcSz0g==
=XJl2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ee24888e-9612-4f5c-a575-62221083aea4',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAArH9KnWDPJnMmpTkZtpRpxttOPyQhjfx+51d6y6SfnPlD
JlVA3kbUWOX7AZC7x+bi6oScGNKhPCEtsUmddqNhizKpuwwFLVXtXw014lDlGH2Y
j39jywkmG4ixiiGJUVpZ2KnFZF+D3cFApXZzHOFzvlSGOlwFjwLfgIHxNmR56ogo
XJXTAT9UVSwVfA4UXeygexNPPyrKWTellyIM/C4/ymyrTB7JwjRPbEhTlD2tGyzq
vmzM0vWco4Ffhvy8zKOwiU7ZGCkJ4QdjDdWO5vJS4fW3P0bmqIGmUm9IZ+pCRne1
NKfGtbwuEDj7DsZVohxqkrHaHE/UkGR16B9qar6VaVombRSYQTreEUkbFQ/ByK5v
3Lf6kmjE4sHOLR7HxpSaJS4e5Ad5iFR/SCGolUI4KKtdYnQrv2oQe2kDNXbhO66h
OC+Kdsux3tVDeMrcTcrYC0sW/EB6QNyfQHkazjxGno3Drdopf+HufKXvn7pPHF4s
udubjbkD/238BswmSzr8E6puhuZsP/NFeIrfj+G+iO6spi5I2vKkh5MqAI998FBJ
fYwae67TtxjV95iE+3Z3APnwL7Je6qoDCu6H6nPuAqqtH1O2fbIoEah+KOJ9iF77
F5Ie5Cnb8AxR0BwwrcPISIyuF+ZQOM4FMk+V00AXo7dYvzOSm896wABldj9E3zjS
QwFP1UDfSKZkQck044MXZ8cKfGLCx5/Amsy3VGpxUIB9JPZZ/QKkk0nLgncuavD4
TvfgN6IQMypFZVg7659TB1bTQI4=
=cHOj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ef51e739-1b54-4a92-a404-458748553e4f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//W9VuFscP4qYF6OnyjCgBTswpSFGM1+iETgxKSB/d8Bg6
N9iQ1AaZIHnlOq4MfRTOP3fNRcfZcvgk7YS8aor9jm4DDbDcBiEXiLwr/nucFYtv
yUsMWPFHBQbZW2aJIUsJCCaIlzu69jmrbIToJ0mPlGVM+oz1x/1OFhImZjw2YlF7
Em3zyZixBuMzO4kDD30g2CKqAKytxJsRuz1DLgUS9rtCKOk78/0OHurFoErcxhuq
oURIL1lYHaBxoE3Ko6rStITCJlu4JvAxHYEwusNxbaNN2jK2MIvtHqGBykV1DjCy
qWBv3sbS7kRWGcsLt/D894FCK9KEIi2acgcm2qIYmmUcJ9o+k/Rs/Nhx0uYZizU2
2zT9xlTq41cKVHkCt/WPF0mmY6NFUQGnW6MhYwyjV1n8Ysa+b6L4EFZkpPXMvjCF
YKvUrRpw0XMx980Eqe8Q2lcQROVX87SXfUfJ53IpjNBxyhQf1/3Xj8Poa/LY7022
MIKEvgbRrugygRbgxv7rFiVI8/hil2eiJDcnaBccugQLb61MBBiMYSQoikTsKPGV
D1JIxVh/w/1jspczeQyETtcejUmmQkEb3EobouJqcO7OKkyeGD1CRzEz5cl4b4Zf
Y/1vMu/mmL5IGxNhz0nM+MZpHpj+WdyDJ6mr9IiUsAZuH8G+w56OPJkH3+mVXsfS
QQEi0XNgWoQcUOZ2gA2zuuhNzMNUoDBEcNmvcshGKefHV4nPIPsSwDGhaMmjPNx7
v4zpQjtzcDkAiwaGF1jxVZDH
=ObCJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f30a0c79-784b-4746-a43f-4d26727a4845',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9E7amvxT9YrpKUnvehJKSKlmc0R5ebNtUDsTSukfLk3kJ
v1aR1uAPzeBxWKen+Pq3kgcpcJS7khTABfrE/svAIyolpssq4WQki7l7Y0GZYnG2
SxXRz7JHfqQgUq7H44jvRAjFIEYTS+6WxWIpCbczmJ6S7IQMBi69bTmQdY9nw5cD
1X37Mixpwj4CehiM4HJ/INDRAQmC8d9yCOEfTPIptO92L1YW8E+IFpsQxb2pHlyT
pueV98YxXVwyq5ies68VMIGQOSODt87BMmoW34osf6IWscBmyQmCSa4t9FN0HPdz
VHP5i76yzRgbijswKaJyQ5ahqqQCljAAw3mrukCsn8/n4iHZuXLAh8qPTsrAXDXf
oJI+WADK0UV/IwiV2nK5lqTHTK3ari27GLtqAyZjHwJNB4XaNYDRzXmDQiv+H0GV
pYYu5OJW3MSRoi4zDyx/XPwG3vm5MxCvDoMph8hOc71O5iLU+mDl1VxmpQ4dAX75
WkanaZytOikmJdZXr/d/iV5RLEsDKOL/lqNiMsjhc5rCs+fPlAVODuGWaH220p8s
SXx7ELTnrx6D/UoTyvCZUdEZ664RVbj3/5J11L3VahdSnKEknLEIRHTY4quoeETO
XCm1iXWJdZ0fxnfMu06l1Jk6IHB6PyXAgEQRR3J6Ooz/np/SDFO+iRA4XoLtlTzS
RAGaw+6RpiXGgvHdjzIgWgrAGGLWfoORFs2gdDvnEz72+kZVQQP0dlMhDWohpPty
pErB1P1wYNVROSXI68jJb7HnmUxB
=ch2N
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f372be16-631c-4915-a495-9ecf67812e82',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//ek86YhzrazllkixfnfoDm4nxz3Ylsvh2NthDnU/YmUT0
BpnPa6q5iTfgLVB17DrRty6G/RxFZInhRZdmIA5+KWE6xOED8C/u5ys0TFVSiiu5
3Sa2VDWnNO3RPD6jl2e8j3ncjzTW3iPmXrHIVeXv7uALjoNzaLLMAIYnH61Z03U2
/AHQ1amI78q0BqpxRZL1lO6EGOGBngeF51LWP3GQWfyV1r5Ity2kuBd8uzLpcEiu
/l5isVaySgScUBJxkuJb5+jIDWhiUArwmD1DqGRgW7E9KYH7eZbM1UYqm8ZqpC1V
+PK/qxbiKVtWjPxlEzHgU5EQG1vBLr0TmSXVDqy6+RtwuYFJJ9DKjFzHni6t85FF
vaFpJB1Fq/tuTjXbJMtea6S1wjdtpQJvoRe05l8F2F8m/fJBG3lJZ1JX0U3hjnEk
odP6Y7Axz4jCUD+JP30xoOIe8LYkrF8oRJm10OoqlBpby7BZnqzuEZD7OahlLjle
1pHGgy5M5Q0T05zZHFgRSKGgVzPFa2/VY3gprM5e8rFddF/sIXv4OsKzXGPUh/W0
GqnU7f/WHJWeaNjnUafkdmGk3yIkkCKrlPkxDZOG1r+SdO9mrcQNjYhB0xRIkd7E
T5CcjGGU/igbQ28MxZ4/wLc8xy9QqXH7u34/k7g/1hvLx0Tm9QG0WrfZ3ofoiM7S
QAFuN3j1BkH+WjYWv+wlIuMU3bkw6elb7IH3+J3a+k0lcaRSlFDcduWIGKhB1Qm0
Q6yw4a+C6tU3Wpx5KpiWYa4=
=neAH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f5459c49-8bf2-49f3-a0ea-2aaf86120836',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8CBeeYmtZfDmkJ9RWdwsZZh6yWAU0CxdxYb9Z0c/JbB8D
1jdA8hCGiSahhAJ7uA//CSvfTSlorxje/N1gdeweCoVbDv7DaaDkOy8M6mr+S9vz
BPx4eC+1MQ3Sckn0Lq7tUUl04kloOTJakyy02/9lfGWH/wsB9uMvsiU6bRFY+ST6
5gVahFkwg16uV8IJKijxWVrq/WKIhJ8jHoTMJPOz8SSR8n8SjmbQiD8gZ+cPezZk
k8Vs81m3pqOoQOkMPfsbDlwmSozuVh5elLC2ib+v+sFWJFJh6JD7DeRK+R99rxn8
0tOS2zAj9qIbThjWKEnMpDq0m5QIwu4PcMbVDxtMnfjbqnCIVpvwp493KRbFu3Yj
5bxwvMlM1J8G9FuMFLgsNVtCHBWC0c11jN9c3z3xSihm+qYlbA34OwuIs/AuyxdU
mKmnKAKgF5E0eEJmxBjTrNm9g4qi1CEu242plvl6yL5LtCZ29o1S1dGWvMUmFNaY
5qoaUddSp1WJiel3Iq6R+ezD55XW7V1MZ6cBIjo7fbfwBcSuZopWAHkzuY9zVajM
PK6q8qI0fva4hZsUJQIN7u2flkCcMQqYspD8aQzATm2PRaLJ53mzjUZlkJDO7Njk
enQpJI6MkogjYWkDV9jZ6cPd5YQjNew7fzR1pGaYvM2H2V6UGRGaqL3ouLUs92nS
PwFrvOLqxKWR1SDwv02eJwviuZL/smeQI/T8/i9Ea4mUnlOPouS0AnT78wxiWJZY
9ffP7JIz9CyOHlHxVlx8mQ==
=nRnY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f781dce4-325d-4e02-abb1-db98cfdb803d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Km5Zf83TCIva1eB0fYjNwfqlsMRABp69SM8kN6R8g4Z+
wV4oSAlALXMuk2af71e6j03L7CiSweDEIbXOxwYCkfPMVi3TwzqpJ63B7o2bng5H
Ip7vUh/qvjAJuazLxPX0hoXglJk+2QdALo8PDRZJVClzYTlPRYHLeMdHYWXKZH7A
ExzurvZSe3uzDi9Uer3LweDWVEmmMxX53xHUw/G5KwIIa4wUWki3FU5aEn9EKEz8
GBIS9iTvbgg79uLN+Yx2o+lxVHT6YEBu9FoaBA6Aa5Vq5D+XpVrDH2PDZj2cQVCu
FmAIFTMxvsfro/t8wP1CQRj1YGdqjDMd4GXEpZV+s9y9st7inovOHZpvAXx9j4BN
aFnJWf48+89B7PtpDZzm6SRI8f3nzaJqo7Zh5/vpYEITGC53yCbfbuHSuj2d2wC3
bXL2gsbhZRI7vJCQfLh7SOJdUalYdeoqRASbgRsobrh3W+fsyPBHHd1A6oFJAkHr
crRMq5oE5bvf5m2YP4+yiyeFHY5t8wqbNGUqX7Lp6bH51Rw7tN8DFviOnTa0yaPG
vxCyUJrUcW00KqVePMjq/pw1fm4qQUIDL0Yjf654R3PQapUYeazeXKVE7cb1wP1x
Sqzb+a7pjFfwl0jDKQEZnvM1lQ4OVkn+JWNDLKXuZJHi434qu22jpYb/BBPLb/bS
SQGyyjcoJnYL84fVKGdGrF0b+KBRGtE1Le7au1Asbvq2oin3a8pWloSojOa4GkzG
/1/eD1+agS/7BJaQafupxEDWZX6z/0PbSIg=
=X5Mh
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:43',
			'modified' => '2017-02-16 14:44:43',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fbb125ad-3b09-4033-a8dc-05b6b681ca76',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+IrxLGjcDhvV5tYht9TlzVFjFcMbHjkOkfteRQfgCWA2M
Pqgi7zjXAghX/tP3Sb33QD2iJ4bRmWcKvz4h85sF0VIXzNTCEK4JlegSs4zziBLI
zB8UIv+lI6wF56GHdsWVmZM5wIFiuum1NmJyWW4HUtXa8kZmuTmtPDGHKNsWDB6j
hHpspL31QIW/XXcYCZoWI6NMWGYuosrCWL2/6ruYt2Li3VfDCywRQGm4npHUPowW
VrrdWWvDCdM8FvXg9EkJln4vQ8bU6WWUClNHH0O7zKpwpsypgnIra/qNIzHlf2AQ
wCziBK/GeKOIma/W4YepTRfc8k88d43EL3I+BJQsTtCsFN1QnfwGR7GVgHcNAxge
LUHbDBl/clCrZ/6Gay/r3Yb6og/dNcAS4jfv8upJmProXD8KKVydzRXxDndSbOD6
uYa578rHqeGZkbLwITbIzLZ7G2UPu0QbaCCtKLog4ASu8+sLM3ZPTxmasidP4D+7
xuyUOojuhqxQu2roW7/88ejfsSOar9Qmucy0Fu4tGTsKKZhXilV5yeafJDlkkff0
ffQ44u6navbU8ebBx+V3ObQJTtKH2kwfVYsAk0spZNnbaNY1Z3gK7JElXmXmK2uE
b3EGof6Rxdm9HsvIe2OBjh9NK03uspqrGs3nFZxWKKqER9gC5h+f2GvWV6lf66zS
QQGNkvSWI5tGbk3h+fxhm/Wc9Hx3lLCSgZ70gl4xXD4tLqpqNIg0vu4dGEBTOSCa
4VrKtYHv0/VBbW1zPOC/WENr
=q90Q
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:44',
			'modified' => '2017-02-16 14:44:44',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe7fc57a-f4f5-4f10-a105-f5f9caa9e07f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAgYlI9jE6DbDpGNnrzCXWnP5Wz64ayql/lz6PfDibIf1D
W03coep3HOpHobu7IbtBCsPCUoE9r/3f52O4KCtkOmM+4W08iJzXQ/gFNV4JD462
aAC3K7UpQNdzMuu/6gkBGS2R99+WLoIwUybga3eR+esKoPialQJA842yF9YpdpR6
gW8mCDv4CX4//bsF1f3d8s0FHJAFj8uxcK4c1tOYJEhBNH11Wjufp09gw5WYyhlR
d0C/3imo2LD8fLFGaL7tAgyOPueQsSyWESnO4Y4GT8qy8wtF/uXRUsAy3CvnYwbr
4xLtoAXBM2QKtZUzY9Q6Mw3t/eDO6d72zGFwKKGDmX0UQ78cB2BGS9YMAMPWSp9W
jFHrWEL6yTwdBsEUBJ2wGWnV2ChE43K+YOjpldqzoXruSO/9JmAPXoeEVTiYRZhW
6Ds59iXo1YbUAcUV7ngWsYnVHC4PYVDHuxgOukzhuVG4neJlrJUiGvTDqN3I9H06
jPjOGtyNqp2VmfnYnM6uKKJ95G6fexcPEZSn3CxmNNiyuEBNeIcMEszGJiypzdkq
oxleHGXM4EkBS4V/HTtYczNoxKndoKTRnyE1cQZhTdPdkziMdLOrgeMsAPJHNZ5W
d/Tm8fdihqvCIOSO9T0yKbxGhRkT4SIL2fCoeQHAr0yv592s0s8/9EXxZrcYEgfS
PwHLItFV4diw3VxSqhcTLJTKzE8GJ/K5+DrCJ12KN0DpS8U0LVTZ/pabQt5h7XFj
XMf76FPVntkQETXWL5qYhA==
=ygXi
-----END PGP MESSAGE-----
',
			'created' => '2017-02-16 14:44:45',
			'modified' => '2017-02-16 14:44:45',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
