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
			'id' => '033d56d2-1cc4-42ae-a4db-6e2245a2e59e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+KaSJjCe5WsfiBLmz0zhkZFZdgCiZlJWq/pKXLRKP8ZyT
fngsi2W4bT8lJ0z+SqonKo/QebH+TeFYTxqr24x8terCRa8Rg7wxif9DsSKX7Szr
/gQpBuex0T2bEu0BxdmJQ/CkJof9Q0wlsnf4N+cspST0awHvoci/kehRstLefDt2
IYe8mrl9iqqSXveNaT3F5pvRs2RaPBSAJl22QvJjWkylEfHDJIUUFfk7FFG6+CZ6
+1qyB8Yoyte9KxEJpGGr8BxFWTVRzH/kWtJeWNM86r42XF5qy1AA+0CM+FN7KdJV
SpgXkpDMpmC9l/xJvPlp5+XybO5Ek5TmoQnTqYZAa1fXTrP90NCCQEBdzyi8gaip
hVX5eVHCPkBi/429yagavIVqEvC4PAYtW6QYh5QzQLrO6xM0MYiAC/o0YHMR6Ra7
Ev/JvjcnwGAJAvAZj4MDHrExkuPqhvh/DA7o7sKjd5imy9dRtx49FAgqpRtLTVVH
qTsoTtmqtD0jxoR8bwuSM7DJt6izfhtTbGStPcrdBJSZ1IkgTyGUPcPdEk91BLza
O6YEsDar3L+fJ1yl+5ISgPyUiaahtWmIx3OEvpT4kuOSvSiarUmPaZ8m/CO9uPOX
RtL4t5UEHnlxE43pg19hLoadtPA+z0+H62DetpfjGjmHC5tC4b7z+KVNut6zO3nS
QQHmZ2cL80u6610XqyTfZZ0lUrz/2sm8U99Tj3eOZ2EpUbmYOK6eP0hg1O0MmrmP
U0oid52B13f4R9dAN+7wt0S+
=KNsA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1b76af2c-39e4-4297-a5e3-d6b51dd40a77',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+LJ0aDRy+kqogt4Qr2M8P6UU6t0UaqW+nWwCsyvHSCHaD
6WqEUg/0Vev5XkcZ0se0Kf5FtkAHXXjVtF6hZFKOn+tkllOvzIQhgQPKWPcDSxZD
aJ9qXLtMO04hy5Lpe+K2shgnT+8cRA1jtIMLa0JEJ3Mt/18ic7ss7x/1kZfG70fe
007V2N8WSDUVbMc84YxYfeHi0+5BaI0Vf39ynKkeJy69Xa5xStIc9MA/VOn0xYe0
EGESrsTctXLlHtac+LR7p2LbasOOrEMgfyg6ofzakwnzjaLccW1q2keIo98X5zHt
ZVFmmj8Mpd1uOy80eKLqMR2lJCYqv1OfS6GswhmkctJAAVjrjFqPckLXUc6t8ER6
/joJumjKFoaQ2rWCnPu+IUU1SRUL3DQva3i5M/DSU5W4CM51UzooQfUywHzFtmvU
Kg==
=kFkz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1d9e5e85-229c-4006-a5c7-4641ae5f9382',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//XyjN/GZVVrT8MXOliZXiMnxfxH5Berfguz6Eei8kXu4U
IMFoOBwUV2FtX81wViGb+q6tdgrziWF3HdV2snubCp/4UMCuqkFL0xySVYxwIDDa
sXohWUIk/vpRERMfV+ceKHQEoMUPpi2zm0IbcfzhSX4D//jIVfbV1dREFBjaONx8
WUkjDb6WCfQ6Zb571SzFGyyZJTS5lcV6a2dOY0APuKnxrAGTFpolD0OhH7EaabLi
J2B/bs4UIVhrhf37w3BjiD2M5DnZDlaWO+8gO+T6ghUFXfYlWTYJYIot5de5EUbe
g7ruqhjcBswJm2KHNp1Yrld26er3IBG5T/zpAeIPcjiFd1DPS3m252utBfMKiiWF
Jm6fX56ayAWaBWs8SoQ32zW+wVbM06cTV2sY/6aZI3kHGyWTrttZv7j5erOIXJ68
Ym8OzuFSJO2ims6KMEoYRmBpNrYlNGv0s9XjVcKTOJaRghianWQxf+d3991vMAri
zZBAhQi6iLv/iSAk1MJ90aNTeJ4ldgwXHob6uXi6jtWft91my957YCjj/E81FQVo
t03/3gRhdP/VADU6+cgRwzDt/Qln7UIH4LLB3kb8ZIxe0L+TKH4Hb0x61O+VBO3C
UJlYz95uhydRYl/KRDJoEA/6QB5oxNcYf8lhyNvoafLVTtd2Xigf3JeaBI4WYGPS
QgELWy9b6qEQ1KArDv4rF1SQ7ZXZVXnaTjGkKkDLH4wD4d3PeeC/SA3eU2fulbjC
ah/i34YmNbVPYnuU8UjC2poYow==
=UvGQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '21bf1a59-fc36-4652-a49c-5cf5b323a46d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//b9ViLTfPbc0MvBaYhUknwqiqQblp7jTA3gThaj1KeJsQ
kPL7S8aKVCiuGXq5XoA3LjCrOtzxLEyhiquZW+cVGaQjI13XYhmEFTj92X4GXwla
2YAlDuKrGdYotdiDLZD6S3as+V449/LlSAkTFRglqTi9tOGVFrH316yq5jVek6eH
+LTwQPUhdC6WmExXZh4N+Ssy8mpkOr1QJFWX+J99A0niKxVdD247FsQam3kozuSm
BS4FyF8WmvH1f3vonOxiv0pah0eJbWOss0d2i0BmXr6WRgMC0NXfF2i++dYhR1xf
x7+j2aDRFVlp2VYG0rwk9XW687ROBUtE/xEKgOOL5XKbIllD/6QKg3x/LVNsyeKA
GK964u2uCKWbuMPOw0WMPCiSA7YIyVzJDJuD5kLJa3lxubXZo95GIRNsJUc3pkwc
cJF2u6/x4c9h2Lg0Onx0ndQGUD4S5aQl+ukqSEUEb/X7I2A9yiuWUiDYoOeM+HcH
qm//eYqmdwj5cTYpmdhKD0k9gtDm62jy5fUu4lZp7uECMjRacSKRgYNmIbOmMV1K
NtHpNMxRa068K0fLFF6oOncMzdEkIHsvKfHQPth7EkWJlg+Cn7aVV2wGu9v0JJRt
iQWo2zKeS1nfE18AzGlbnsPTA1AvnTWe4fhB0TRncatu7x8n68nbEnDcBUh5rXzS
QQGDlNpk52Y8j1ul8ZRRIY3RE/pv8y8cBXHfG+5MD2KCRn2dde2JhOwDTohKsWyH
bCf4i0CLkuZsm8IvOsbLJZgZ
=APlc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2287959a-be1c-4133-a62e-2c47bee14f90',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HRgxorqw8SiIoNTQF+6bzxW+NT4CN0s5XJyWMHch0Y1K
nD1FuExJEDN7iIBFhZbV1hITnwhRETMIQjlPhlYYji67CS9pX+y6Ces++1zryNsH
oIVd+YPzyUwMSfEch31keMh7fCyndBmNnX8dWEKxrcCO3d3tf7/z1V4h+/lsS3SJ
6LsvyZqS9xezL23ZcVQwLouNRWuDj/BDn9POO8L839dfWVpkJmN54m3oFoQNXjhQ
dlK18LaDU5lgsQarZkFUEPX2j0lQfXwUREd4TY9SmMjYG/0tV8fQvQnCM7fvB9ij
2pivsOOlaVRal4K/ig2mUp5DrsGN9VtIPgEJjSMSZjuqWql0hetQukyeMLG+po/S
Iw2MFm1n2to1O3CfaCTgEomeeLMpJAsS6V7ui/Vw60LgcMHku/0IIFcyxamgxF/c
xCaqg3/wLE6O9BYvQD/kbAxnh7GtQoB1p+lzAry2Xs7y/7D6y4SfInDc0YzgOHmx
53wFXg68V7PVHl8RFK2dN2pyYgCXzJFIYElBrXwiPBeJvIh8cd4/PnanGYzcdAhy
MpOKrW1AQtMl42MhTuxiBLeEeau2Y1n23I14vc4NIo11gvUOwnQRJiZIjH1ukaOM
P2E+ELQOA9uhjxtpJfCdWb/hyAmjlhB8OS2nyM86jF1wliAAXlnAQOUAcARLp7rS
QQEiBqHpd7fD9Cqjtmg2P8hfeQb+ZqqcdY7yEMa+2BVG9VZJYkGo4jKg6w/1BDgS
10x0tdW3iwcPXKyg9MEc009i
=oSoK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '27188739-5466-4a8c-a93c-5869f235e538',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAppb1pZXc35lAGvzClKNWJ4PzKAwtT/BK/LEZENoKD3f4
yUc/XrL6FJokNl/RglhcA1xT4O9JI9OsVVoqpBMBezxkH8/Z2KLKcsQffDyo1MgF
1p64oX0RM0ulgMrlQtZsbjlIwB3lra98p+jRc2IN2byGhBOIjsdmR29d4jDdAY0w
ZTdwJybVJXUqr7uRWbXn0BBfArnh5fAPAvFKbWI1tWIG/WQmVmGEQLxftzQtvEOA
rhAdnowTtbo2wz7vekEeelNkdbaIFB864zomILqlpsvcawIchThE59vslyUxvNbL
NCPEEtEE8QYykXJGW651Grx7azwJY68fJYXGEjv7udJBASpmdxBT4qN2BtwsCygc
ENddJ8xj+Bt9RI659Oscp7CODFfD1zf8ITCbQO0DRg3KEBYQXQnZFXOejtcjlbM0
jlY=
=ucsi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2c3e0ec6-d60b-4e89-a863-fd801ab69ad8',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+PGtA4IwNw5AeP3U1vn8j3HtMOOvDsFZBoaSA6HV2Qwda
st31ib7tnISSW0nlFjPHdEldOvGTp06aW6Y8xYiQ4iB5prCv7oj16MweBTvYn3Ta
i5bpLLF51DuoOZhb039Ck1yghj8TiIJBZ1+uhjLxhbaK+8rgh3pfEIK5OkgfsnS8
D6PxUAArFEvbCi4hr9vRBR+NkqMcXng97Ey9Jq5DFIOBO5DzXE/306taYMkR2R+a
QayjdvHAA9knWWt3j5s71XlwduljG2NsO1Y9AmYZSvTuznNwyEQDAYAzHlXhq11z
NU3iE9uO661C85leWwCozk87Wn1ogZEt23q5/ONSeU9Kp08JUE/8epPe7puDbY8z
bnUZgpTcbGOgoeyRGtneCANUKnjeP9MQ58jtHXaLwI3+BfHYZhvz/mvqIivfPqkG
V7nowQicly95phicQRW1ilr6wQX1ardPtfsm4ROm6t6aelAMPkczPF0pSG+z2PlE
uyb6C7yMiA9nrIQfeZuHl96OyeREvn0RYIePtc+cN8fMW4J5pcO0SkfAh7qyA/Lo
DmGetTdJrcGAF9mu4wJniGf/aLeh0u2iIhaSMAiMP/XrDp3RKnglzox3n6O6e3bx
hB/dA+mVs0k2Eln3lhSZRshNgU2nLATmCuBs5orxmFiwtXr16CcRBBmu6yi5GWXS
QQGxf8YDgPxkWbA6fwlOPQf5KubP1xFcCsxqg5rRI4RO3DGllQpdrhQsJod8sqrM
BJQk75VK5ngrrnznY5RpUtKK
=H10t
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3008e879-c942-4f5a-a802-311a3e2bfb84',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//SU9WsQfSRu7xtu1evA8u/eD7jLTs0dQ95shPawvT9+a0
GRaN87JKRVPMOtxNHuncd8IJ26Prkzk0UXyBm0c/d54xTrHnUVjsFFaY3kZ+rFoq
EgNkkrE79Ao4GRpVHKy7+DK8EDmBJCeMMpo/7nbJ41IzbRcbNGrJoikfUei73byS
58YpJ7mSZf3UKhM1C6o7rmCpF6k3SM6VmqFyMAEfSIM+bPA+ENQ+rGejrhqHVcem
pizz8kvfmgqNzsqjrZA4jEoHEih/BIhbk34r8dgW0bvU6dwJ3I5ojqESFjLVB63k
MSEbDpvIRyNIeGmNKeW94MLRgFb1Ina74oEg2bohtx9zaXunMDnZv0B72mBD3qv4
ppULgFsv2g0ggyk5p/t0TvqIRURuItIaqRyTeXyOZGxnX7W5NGU1yfIiiv3KGFyG
Ogj/7ZqJJ/5RT1KhNX4oYA78h+OvHqJRxB/ZZ0NoC6hECoRdRXOb5mW+LCzAapGe
OnEtaL6vP5JDH5qY4ZGPCxA3trhmJSqNQpXwiFo17Lw/5lw2Dt8BDOgfudprr9Nd
nR1FHaTlPghRAN4YmQ2F6FyTpaOAH6bjhfbB1WbYFeifE2dI8remixRt0/4iaRIB
ASAisd8D7anBNZSqlkZlE5LaCOKI8SQ1jjcMnGRkPoe2mW0LI0Y/Uqlun2edPmLS
QQE3VQSw21yAjSUiTUFEAYi7jVl/KN68dhxDAtLfbuL5M2Hi757fGhEsAL5rzBgp
UgTsIvyAaE79qclwfwJ9U+mr
=/dDr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '32e5f833-e42d-4cdb-a0d2-e94e2b8b5c99',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//QC3BGYf3euaEXYwLFkBHQ1sn1/CCaoEVmrpWijmB06Mq
gLs68Tn6If/wdwuxIc4Aqn7Pu/aghlI3cq6br7RVhRC873K+8mfz3miecm/Z34UM
kLs+yChuQT0bpTtwYHya/kU31Yk20TDUrMTfn3rNHbj9qtVtnVW/O/BqQkYL49nG
Y3saPC1ZDJ88SE5vIN0nUbnRBpFf16stZwGXzxL4QRhD2LInveIJZC7fFj6sw8yB
/bNPnoh6dB7CEHDbf9L0G/ooIIJsq+N66bQjfqqD++TkhxUTbdk9AzRZbI+Vb26X
a66Cu6EEMJc4v2LMkOirfdouN/Yr8T1XjzC64Nbjt6TSpybFf9MizqpAl2SG2BqZ
Zut+DkrC2X69eQgKoKEnX+Y3Np3Vep6p10SD51H2bXbYShCITnFdkmX1VC6th39h
X8JIn2slPqX598IqTiyu4+v07Bv06BYU2F4yDUaarkrEzO70Y4OakhWPnbDsmxfq
Mxxt+cNh+zV1trYBynMMmhha3UHuKQvGGgTwzGY2VmPNHXCGVGpEYxIp0DCo/J+N
5bLmwAayLVD1qt6RVu0A8DMH6u0qDLoD1+6gFrRNRfFLrWi7ouVN9S8O8VYMFi67
l2GkECpJ41E+8J9lW0+5bRsaCZrCYMgapHc4qQWUS4XIxMTFl2RhBqGlbYV8u23S
QQFM7fafLTiiQ9cw8lnwBWE95f5WiP3yWCFNheZjxTcaNatCBPxCtHdOEOgiMwHV
EhNMZYPWWjkh4mntmdfcbMey
=hl0z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3831bdd6-5f67-4001-abf2-1d61d2eb15db',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAuvP0R1mKzislHgxtsnUh++TcJD+rxSTV4yLR+oD40jfv
BtgLQGO2919lEjdc6Dp0HmsjGxnrmiIGbbojn+XhSv/GnTTUXgiz/eYHiY15JAXN
Xy7+cvNhcwiMoV63SqtNvvABuQXfy7dx2Cc/WK6yKsQ5UXkCWCWZQLkh7kipYUyV
XTrwYEB8y3sF/UUSydAfEWcEh0AjL/KVjSMHS5K3VjAhrbp8NCOdq8/SHt2MYzCC
OM8r2bo+surSSlt4mrnLPoEfZMmlf2GLisVq9BC8XIllECdBsQJTJCR0pnPemXRa
/lhABdB7+IxUQIBVOjXvQp1dag5WDWkUf67WxJ+Np5jdmiA9Y0Q0M0+FnFBHLOsy
uQG1AtyyiewIKcmgoRYlhxW2Qo8MMMVpKzj1Q4t7TxUiQjOs+CvYct4sSvjbfAqP
FoTmgF38nZmq1WMVxwTHCvGIK/gJuNM/57cY0gZASmke+OCyybGsNfOQ+oVyBYrY
tG40hhUfLH70qKDldkeULxMtS8DQBN9xV3BaNjxnVMbvH35pn7xYuQhRvgElip14
2b0HKWepCE3Vkzd25IQjFNul9HoGNWzsZ+HOtmp1WObHFTO/Ls+Ni3NF3bgxyo90
CaGQ3FuJnFYq6877gHob4Py/6xEHI4tXnGJ59Gkgr6/GtZzrImP/GowYGyhvagfS
QAFCq3vWrQimeHwEeiBL/i5tq+zbASs98uFlvjocSb40KYVz9RLm/ssX9iJ2ADnK
8F4AqbjrZ8vZ/ME+araRfe0=
=vFIR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '39f30b53-b0f2-416c-a4cf-681decf017c1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAmeNQMkMBOBvloy5/eesZgqEga+XnO96xOOci6Wf/gflZ
l2GCd05xDsNQcXS6nFiCJ9xyRr4dHzL4/WthJxLgKbtagqHRY6FTHXuEIPAxEDPu
anIylvJiuN0XWl12pQwp+JbqpdLaP8z0lpMYj2215EZTwMnrn2o1xub0LRnV4SLa
BMO7WF/xbJl9VHYerdp5h9JJiTQcIwWg/8lVfm3nMcdsW4n6ahRoNmMVx9sh8a58
5aFxoB6J55Ex6vfj3CnCkukJdzz2cHlRi4FMw8d1oBVQsZG0a3nZzHSr/j27rXcr
RydJz3z2fuGIwNxE6d4T/mdeupD7FYhbKhciAPzOrzyW9AZdJP9u3wTRZlB8bRK7
D+AnryEQs3H1o9e1VLVrVNkrFl/bQ2IGvENdZchpNo/sG3VPniJi19K7f8iLD8cZ
xLSh7lAxPwFWjQMumZG477j9ImF/MdB2mgGC8arz0TktA0f/QDk7WZruSnvznLO7
g/ZCKb61gQWtjWDQ3lVSWaG5XOv6AslqLU9sP6SEHrX0WgCMTvqPHNGWoY6Vq3sX
e5qs5rQb1F5mnJmGaIlceH4Sl+iseeF96eStj1/+pS6RhpJ5mUqz1EWsncpJbAvW
YPjwmPQA5iShVJexZjWZXMdWH3vL+wRd6KHJzEgT0DA7mN6Q5o5++jRX7e/CYoLS
QQFS1zlyA+lPDZYEhrlGNo2664LOwAUlZK+uvENP77JgjTfNzVEaqOUQPhHWl52m
F1amFEsAqueSisIeb3qm3Fn0
=CwbG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3aa33112-ee8a-4430-a2af-333f7dbd9bcf',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAlbTHp4TAWmZkkrIxUO/ZAe5tVjY8qdMh3ZzuUvj32SYm
gTPXtQVi0O5/9rMS64mF3Vr7fsDZSHjmpLhAMyDnYVL/R0KBJfn4T8zipkpuoGA9
U14urBizocsHHgoo8RgFb3PBq94vdCZ1vLGpyI2NsT9TplGglCjCn/6CVYSEjLpX
RPFlBqh3A6nEssAKtL4zzYTktpDgsqD2/uVI7RhU2t2ytcp+5M4FnSNMhfTsBk/L
0/6c/XoUUkKY1LM4OB5Jv6ekiw5C720cNMNRWLDfW44eM23PqI11/zOVWJFtPSdW
3M+CJMGCMOQQakBqW2aKMzxHGqbWiIGOYdgHR76Bss9Og6tKNeFB7F9ILBhVcDlq
QFYLfTo4j5N3IVaeepHUFKR7LHJ3h2O97Izsb7R1md65P7XgpVv/ciID0iz7ve3m
CNStpp/aBHS2+c53EfbcRtrCOcLmMqnAujrKu6UL6YZzOZ0aqAmAIAaCDKeRQm0P
GUQT7i+67EmWUd7cjPtZOp8LgXKFju3bWy/QRCSLAsI2RKBA5j/xQU64yODv5EJG
cI7HQtTpdXOmZPBKDkF5b0u6WaOGy2+jzcO4j9mG1Hq+trmMjXCvW6Vocg3fQwt2
nqfmONFHI7DnxKK3467K0SrsH19ncUOcD0FaW3JuitUpKDtsv4zhb+dMJ59XZq7S
QQESnyR1TrYqW27HrZnfDQpoufckl0HSYsjp1vmhiohknBPxjsZ/+/j0hjHdSKEN
ubQpd256gXmoGV18tOCUf7e4
=z5ta
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ec0ae9b-c631-4247-a68a-d7aba5f09717',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//ZomVP6erlKHTe53QlwMKbt0fYD6ggwbm1TTd6Oqdrihr
r9pYyJLeO3TsYSoBtr3yYPFVZfBjiW5LGcNPgNKWbFeLCdAUDUyn+nCxuC9bmYai
8VTntSfeNrCk7+lx1GK66KoqC2sa5P1o4/Z2FqCrPI7uLnMb+OprDxUhb/YYfWHu
WWSG4NClHrh1wPOo8TzHfKp4alK3SGFfc6YvlFvXMFKN8Pk4O/ffDqP79i1XSOFc
eJlmXYqSVGB00uZzmGkrdi128ApaFeNpOqNgXebjchl2idtA0hUjMKUx8PXfBKEn
W7jO63OVzNrNK4zAckbdFhO6Xmzrmb2hE7KR/FPlcfoqGueu28swBSZtb6Z5iV0B
cVlxN2i6drcTFnC6PXhThWTIH84n5C3mTcyNibHKe1XGh7z3UdKa2D1Cp+u3yme2
FpOapeLTYZbIxe7U2NNK6j6y/3sFDXn4qW/4o2uJn+Z8lot1YNncZ6KglkjRQb/L
bjRp6NgeBN+F+DgffxImw2jNJjPLn76AZvK0+KKabFOoWmc3c9sEbrSLNJBqKfNx
5OuDSq06JX6vHwzc/o4qAQDYh9VFWlf2vk381XEDDdBKzshrIwpbSVhWXPqSGSDC
HUL1NcJXsm5nliaqBX3sULJ3uPDnMcOt9nmH2lTZ3Zd626v4ReoaUJznWa2pw9HS
QwGR0RJGJZqLX+My/XlozU5aHAMXgz95QKuUdX5zNmxxPiC/ISIjHWxduqU4n3gv
ZBqYgAKtJRHGcPuAGvqbzZ5acrs=
=8WLs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '415b65c9-a9aa-4236-a8e6-5dcd89fe3526',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAwiC8ZVNjVm+Sf07jyIUcZzUApm+/HSOxrjR02yLlTOeX
x74+t3Zfl9r/E+LtBpdB9SGYmz+2d3HJGC8jQqIN5fMJSY06Akp/IW+cd4CRaYcv
KMYRkWwuDroyWpqyYlJO4PyK1R2JypEPQSlJjMCvYQGuErTdS5LZIwk9Fjkan1f5
Io9lQNsD9IrHMmRZhN3LMPA1yLgkSeyZavvyLM6Qw+Fum0rIxzssvWwn6hE8Z0nj
cbRnBKm7yqhTJtpWiW6pu+E6pUmqrgmG2wJWv8h7+c1lwd7iQkTwfS9IkAXVI5GU
+rmh2u1MTUSO5W/t1RZ3lwos15zfCQJXjmLxm9bUTwuqUsmrMwRoVSsUxywobWFZ
NMvI8WFqrjyib1IOiDHpFmfbKsS2+di3u7ZzqjmtaWQIExDCkfoKma/4gkY2Asfs
R+oi8NPUZ3fLtnv/A8Eqtt/k4+RklXHH+V5FfTpRKBQ4zpXqICQDyhOOyIxtA+Sj
S+p73tgCL+mBR80Er1Pk50jjRH6ULF/044JHOylsGEkCYG9+xkKfukGud93R056W
5S2++CwrAsKmAOXAOGkUvjv7P2gji+F5/fUtqZcPcXqfq8rk7ApAM1qq6vwwUQzg
7rgl42NV+UowmNd+xFL1HlEmw4A+Jmj4EEg6zwnOE4+DuyqFdNDGVBiYpvOJSCHS
QQHNuf+OLvw+NfeX48zB5YKXZ6YOF0+mlDATUh2BQLMbMouyImBaIfUO1X2tFMBv
ZzHxdzADjfe0+BGkdN2tUBN6
=cRqT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4360137d-c363-438e-a3f9-3d6e283a969b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAlpdBruSYkrcWWQK9Y/mJSq2ECgihHcS3SprpkPJNGt3S
XABOF07eJtK1S0j0wfK3M+hB1aOyWFOyFZgeHkMg9fuojXySZlNEw2bByjDXo9MJ
kl+WD0RBArqpg9vV+ZaDrEi+2UPrY5f4t09az3v23sG479mdrK4VBWLLVWvDS1+2
ePhEqdzAL8Y7beF3zyphbIen1AKFMx8fwShAwja9mfD/hvDUOWldtn2BMTyaJNFs
ocJy0+ZYF9aMI7Ct5J2zDlOwwsp3Y1PMFpVNUNNZdE/3Ue/RJHADRGyz/nozG2/B
F+mFPKNQP7syw07X4Ydb9YT737TlRbAlSs9j2fi9XUIzHgR7eV3A9f62gIPlpnc9
3udVFag8TZVkcyO+2DTJXxKc9UDen42DT0Ws8GOW5jTAj5D8m0PKak5bgzgI7rPA
kJTg7q2rhveMK8UuVuYWdoo6pvl1ca0k6yNT6Oy6XsXmwD6fi57zHkBBejxutGHj
XxWKqHy06tbAVH+9/fZn3OwU9d3wIQILA8tCty7dFAmSOvW0xbEzQ7xUUlj1wKCA
+72XNSNiXcu2NvvtKWYa35UXnbe2siUKRgU4sunFC2HJEIM6UOPo+jlFZxdBW6+I
snBi4YQyxnhj9RPbJj7WkvxJd4XOSjT5ENepKJ7JbX1GBZp0PyKHbOQbeYs8YwLS
QQGyHFP9sfVHGoJE/PFvGn8oAdMpn1BhV3RXdYfvkMJRcGUPPMTat3gBqe6ZUs5Z
0qnPbli+VZu+INaoHDqDvche
=oneu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '467c3a6c-c34a-4499-a97b-c85f8e9c308d',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAuFkVrAzca2rnwrd+UECYLVQdt/252b+vEU/egvmzhcP1
bieKsqx6sNpqgWE6Ajbcr6ecPO2ThrL0sjNxMUmYbldePc4sKoM0rJ98mcSA2eoj
Yu7hToHsbJCN5f8BV6vXjnLPkCqFRQSvV+8W/s1DqAzwMcKQxJRU+JK++qMjhTSc
SP/W1PCP1Ykd9rHk4cto8TWfOTxIxWBWrf/SoP4GqJnw3a9TBOBUSWmLnzDcDo8n
O7P1X35QVs31kQXM50aB5x8h8NAdgACOLOx/br8TVVCa17l0ZmN8J3qvlB3DHcbN
pEkEukjoFBdcYBOx6l2rE1hjCWoRkJK2btTJnqhHzvWsjueavgw73OwvG9hyiSi2
prFe/4GQPwWaZPY7dmqlFqqIelqvud7ncUS4THkRyzo4nAaW7qJvhgjaS9EztNaM
ySuBo2EAbPkbY6+CsNkOnR5Xjs7vIJQnaOlDX4X24FeLlaSYaCiAOOJHczqqG45a
cbCzfM2/GACP4MTHuE6KiXUs8GfiFGRQ6v/4xYTxAJy3ViOKKswyv2aYblPgmXvW
pcgfG0HtAT7zJR9D2L5me0o6TzeMLt/C82KEIr7qkjmpym8ae6cQGzZYO+nY1r5M
hqq+6fsW2tZFZMvZXkkV6GCPODeE2MlloVTSeKTdWgks0FFcv/Vo1wdAXn8ZYZjS
QwFSTM+wlz9fIj55aduP4vgIfzi8FYMoX/PHdunTKs6pQ9RkZho9PCORNiL5gXZO
DocUlJdTrAPegzSnoxeE3D20zHk=
=KVts
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4b65e45d-f5d4-4b84-a878-36e313d1a5a1',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAh8qk7snEMOiVWXl14wbxASXHMSEEfnpI7942ZI3iQpXa
CFVa0dpNEtyYcW7haQypGRCaVFzK/+LjkLxuDubOccwBVgHol/hkHMH2XITxXubi
3cwF3cmhgfRvej+1A80Apo1RXxocn7nGp7wnl+DJqu5Ge53jdVDMDx4AvOFUaF/O
mxRjwRuLUPV82L3Njd71xV+ERKVNZmgytvLDaCfNiSuK2WwkN77R+elIEYJjOeDP
gHk0msclI85alQcFQMTn51NAn39AWBBs9V2YF1bHNwXQOMZ3rhqyBwpoY0z6YB5K
IP+F17rrErFgq2nrxO8kFkKVYcd4JLbAjBi4NmwLnZWcFtc++3kgRbV2P2hHCcEF
zSpSKmmloQOtwz42J7eyhh/ArVOWYGzSvsMqJIUFqY40pWtGZ/ZlcHwjh6HhSNjp
MNruH8lbunPOqfzCZWb7Ts4SP7pMbrwIvxrClyWn5/JgnZLZfVol0LF8cf6RcUHK
uTlCJs1PqRRYMxPDDjYhcsJvcRdMgdKYjxZRmNLIV3YtDCXP3cHsKX2sdqL6v9AZ
PxRLI2PQQ+LForCgJdAKLeqBUAUCPPD0pgiMEeQK+MDFfajew4yPJt2fKLF9EXyF
AJOW33W3RuJUjFe0EwrV7IJVVz1SVzApaoQ3uxRUrLND6ug7NP3KpNoRXP1PxbPS
QQFGZTzSgeTntBg5vqj41nj7PIS11v0lxq1OL+0sEU2ULOd+bW+WEzYw/Yc1nPS2
jnvNcAx7mcnAk2zEmWQ2mn+E
=bKfZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4c152da4-2c62-4be2-a43f-73b3e6c573ba',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+OEw4GxQiwZny6BAMxLYm4WBSqfLbLu79xXHo2DTZimUn
KLf9OG/hJgd3+G+Jl2kmfFIqEI655f65qFu+JWj6cJoZq//gZdiPlKVSErEyy+RN
0MpWs0QcaQTJBACrPpbUO7uzdvjt8ENHGE0kB780HSUQasJJ/tFHO0Duln8aYZw0
dNehG4lUVEk1u6gk5BzZjHD6pzCcor4YfUX5qeElwfOwgGEZM8hWtmF4BhUH2FeI
OlMA4YM78PL+J2jKKbaXzRyDIG5HbO5hCuvtvsdv+lf3B9JeTAqwn2pPBqJyW2Cj
ndHFKASco6shfLbVHax1mWzZfbLMkNyqoQi+pubwndJBAU/tOB/UP9fYnOkkCEme
zikP3MrvLz8YKwnAo4An/LvGU21NSXAKDYQYxJWy12k9zGdTU/ERiaHJq3tdv2Eh
Hg0=
=qUJD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4c8c5be4-ab97-4ecb-a4bb-46909820ffd7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+PzNYO+G/rjFPStR/YKOhvz0wLmovRijMaOoPKUm4uudr
zMBvY65bOW2uezR0j21Rj7t+OmSV+Vh1qsETKCBbO0gfM4Q8UulcrCQI3TGFxhMR
HzBzPGvQXyUMegmMG+rcenJ+0pSBFhHD5GBIuzx8EyRSmfokEqpRj43ErelWaXtd
h8jq5xE1Q3vvtcHONn8FoLu3QtVbvLeiHxpUfuLZuuVWJRnT0gdzOuARZs4l/0oy
UkT24119cAg/E14nk50WNEWN86PfC37RtOUczZZ+eKlQ2EfYPHE81SnqaBU0kqtp
uIlA7kG+ncCN1xY+XeOwJbQEKH8XmAU4opnvyDPIK39NGyTiOqSWSNnN19KyBVWY
9dx+trV8OwN+RVFQFKa/fzi52r8HLNWKshbd/7ydylW3T0DmjQSlVgQiNH00pXmf
+B/3OnmYHw6pXvzKl03f1QV89y5mWEgq6hBTZRqPQwwSmpJZvevmB9cv0OIdrUsU
CFmG1ap+5KgXu2wBoDZer+aWLP/8ojdaMK7UkydSUoV7I2u6Cwv9nk+U2qXTk5p+
PBeWnpN0AQq7Plw1e7fLrFfGziU7KEhm3dtWyJiudh5z9ov/xTd/q4QQccBHh53S
TO8W7aR83ZIjQmhAJ15z0DWG1Sp9R9GopDd94StQVlYQ5BiLBGmlEOY5fcf468rS
QAGio+et5CmIcyIxHnJQBcGe4Uf4jphoK/b69zxMyKEWD+Cw91b7DlsFGBW2KV5x
RnpoR03lQTHiaWWrYVyUEVg=
=bgk2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4c984dcf-495a-4768-af09-10a28e6c1286',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf8Dtq3l6vRVN5nS3q54oAJAJYWXQxz551FjilGs14l6gwf
Imo2/CY+VsCU28SPVz8GFe+gKmw4YBr8PaiK0PUnRQO75naI3bgL01W/A1AKxtf+
jrVOhd7OvjjGNxq0eDuT+hB8wbRazygPmYMCtmuOZbX5UgtKcmNbsQVLjNl8VqXZ
N3GhFFN2bJE5KzB7inwHacoBqo9cgozVE9RAfmyX1tTEBMwvABFXQeW4pxN7IHZb
rgIwfv00Hgut0WGhxFxVfqm1xLyQk/OIPyHAyVEJPa18RXOwMnUg9WzTH28Fid0l
R2xB+zQWGTsWSm72xOxc7S6rAhEn8TAgMbrybAIzP9JBAbAdes9xD1LPdKPRIqm1
MLBf8Ou5YuEeJFxmzRp/OnpfW6haSX2QeI/zeuhq9RBTKPen24jr9Q+ReSOSwLxB
pm4=
=o1sH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4cf3a903-ac90-4a80-aeca-d9f982e021ff',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//YsoKenE4+85IGMGkoDWfTQkvKjxNOqg1Q7ClsMQXHbwF
04lt9cDRcFgsjK4l1NKwYJWYDYFc5RJt6sw9uYFLkggvbv2F55pXihR78mXC61j9
xovSaDEdd7gaJ/33A0R8T8yMT9RMEldHCfvvN4+5OOUct7qOmzm742qctEAHrbmm
3o8d/4LKBhd6RAP1DSn2vG+CEqctENXu8/bw604TCT/asKbTA1XhqYNnXztNR0qJ
hCkt7aFwNu2+CLDO9cwr/ZGNzxbYKzYoayBfduwJ7xkaULUC5kHuXlLiN7oLhNGL
WWvOW/+2iDdD0IbI8E7zL8m0Br9ON3jn3vhgZHGeAWmiDE1EjWJp+xnbzhYwb+6l
mMxJ9JOKy+T8oFR1ATI43bVSZzWHWwG3ZMQ+mxv3Dym9rfqjfcM72M2X+G+9VBl4
P5izBU0u28RrHm06mUEHFQ2lPSYCmXueUeyf1phRtn/Xz3L74+eaWJQVeMzMldFB
3BHiNIgRfp82GKqxaexnAZ8/8oIeLw3WCyFjcRxu/AB/F1V7F6pNA82Q7tayj5mw
7hdauB3Z9u3Kgg92WW1PnEWiv48a5TPP0S8h/Mxviug9VWYFtsWCLBLnA2kFT48q
l/PqbfzmsxhpsRiiY8UFYsWnY2dhs36tW/Q1HD3yvHI4ClzHrQEk0I8ueB9iO0TS
QQGw3WLeyZGWbMViEsibisxCZoRSOzkXQjDia1XriaRGcjVUneE23DTTvn4N9TR7
bLaOr4rRteD6qEgHW1FvRQFK
=g3mD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4e87cc32-3096-42ab-ad5e-3026d7d076b1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9GB3CzahurheJPdF/dLV2x7tMb+1H702Km8yZlQhUPI6u
NF4Y/HDEXQfRph/ec1keFd9MCCgGe0jKtjJGQxOEi7hzMRa+qJrFVNZ07GytI2NG
YsJ/1D+MgdqN0CPzwZiKfgXuabKrpV64nJYjGI+wdAlvntHobVurQ6bYIAXN1BFO
hii0BRY9EhPIACeoga6d1B1RCr7t0YumTMQmSd8WhXczCqqPsOnhjaQx6Jvd5aNN
fQ0X0E6FoQQIIn2CVjiqs9ry4aplVMuFBAge71u3bNY3YPxfYfQJjiyFGme5nouq
KyN1obmCDVdR7pfd6qtHc9EN+/wr2TS5/+Mfa06+KpUFibTyCn7/GAl4EEw6JJSl
rxn3Nxc1T2G8+yGBXXycySPlv6cXtV22K2s/WmYBOkZEgjHkjFkYTFemnPSxwR+p
Dxq32g4m5jDcjjLQWzUGJNnFTA3ftVV8+3RyWqtmSajsTmyKo+rrLNdk2gzHz0Ge
RNKDd5H/u9Hx5ngReXqyDZ+9rTwt7ACupA2ifBAdrzgNYLrmWN809+gpIiOJlLCf
bLIY/i0D74znOHFjhAgN8Q/YUEgy2Dcnmp+rpQ0MbGZ8H+3Gd2bt/nS3bgUI1/ol
wni/7Jj2oOzek36v7c783VADarJ8QmklDtte0TWJNJftXjYy82N9twy1T7ZdHJjS
QAHqIn50e0Qqw7owMtG+wF1eY4+MC4IXmVyz6IyU25hSoBG4ICTcnrdqmkfy3Hsv
erSa5MXiDgy1enK5y6QjaQY=
=EuAz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '504fb063-6ad6-4496-a49d-1538fcddcb58',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAqSbwtXyXUGI4bkEqDOcmoCvXyBYZt2v7LhdQbyTB9VT7
AvVMyZxtCwMMOwQ9yjfbfKtDhJxfGJDDzj/zX+JLZb1dxd5kIUfPM5hXBuz2F8TP
ORW1jLg/54FwtRBo1i/pmsG4aJiJM9By890g5N0ExdQviDdRTBS5pDqYvNd8fgDw
LtmOkFZJ0f+Ilfo7sIVjK6MNAnuZq8Q2c7Vgl9ESvFAZONiCrSBTi5fg13omqX4E
5RMoNvyt9mYdyWLcZIOkaTlLFzmi51wHz+K6RiXtITNEnEU014flMOO5mRPFRy3h
FwoAIU+gPixNwwOWaTZAusceI91gEExhk4mODMA0zZfOw0UeiBx+SeoXPN/JUekz
1T+xQCTxWs6POByjfJiBN+I8tnnfSyaeB66oPhxaSUtlNkkhnK1BCd8iTIDXdKAe
VOTJH+v4XqdvzUXS14CCr4KqTN8MF7pv2djTuTCJFJhtwdfKmXzDwveDbis5b41y
FLGJetE4/TEWFEDns+I2B206iPUpjS4dCDgyvXJchxNoUB7RCZ86WX6qpH9kLnI6
FBN+Ac5m37GwaqYvw7HetzmzDiUSaGowYiALRZ6O1RonNQ+HakThP73o/iwo0+1n
A7L8s4bo0u1E5pXavYXEy7mrE3hIgwI0TyA8HZ3y2lsj+Y6fx5znayBoDojS7oLS
QwHFshhqsGWpbvf6fA68l+AFVDjVhcoo8FjlKx98qrH52FhkSg5+vHMhu917ry6o
1TN8wlOQ2eTI6OTUu0RZ1cuhCz8=
=qMuK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '526646b0-c7e2-47f7-a5b0-1cad02c5ab6a',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+NRJGl+yUGyl2U4Ab5yozoz9AVsweTr0FYLqAa6RoXZtK
DckgTlhGLUzHkEheIb12An6QxgA4mPXPkO04tckfRD8dWr2ppkdEuOmEuAwWULoN
aT011Mm8fuOLXwcjtY2HN59Xin+BeRwH/9b8279xQz3QtvD5cGUGBpuDu5247Bk+
clBMkHy+v5NuWGPt5st6/r2ueRJyFPVikf9relPAdXNKn8Yg3U66gNReLuB2egMI
1BLc8JjEMlbnwWp1NVLeh3jkWQvIiTybAumKeGUHexY3fRNvU8KOaY+T6bPRarHD
o4S/daF6rRUcwCcFQMHlgPqRQHOmkiFuy8jNU/9o2GFJ8sZPhYpfZMM1wgbyMwEG
jlvBYzE0+xBsq7Vf1WV1gcn2hnOWiwz9ktNoDTgBsnk3CSoLvnSLbTsY/+xxui81
p1r8Nw7cuHkBTRn1Mz4fGSVqufP7CR+BjTwQMDEir0NHI8LMmsjK5EH/c4S/rupD
LHsPQh1N6pPi43CItirGX77Jx4NvrprGeFvpYI0egKP5oDXSAA0h8hG3WuRbLDwc
Po8/fDwleX0Sdrsd2aJ1IHe1ObKsC88JQihpx69NWh0OSZP7sOAYinXYuTabLHzY
1Q+yEPwJ1sEMAjtP9MuTG04Fbd3vLcjiVem3KYtuPG2xSpMvawKEoayS6xz8Hm7S
PQGR1xO5LWUQAZOlVxu4vHGWOrgOIECjLtaHCeJ6dYoURgISNXa8vfMVXDkarrwW
gAZqwoNkkxs0fm/PDTU=
=Y53J
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '57ca2a7a-f190-4937-a122-8d328eb4726b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+Kj+lleRmXcspON6bEpKyaIew3A11mPaza8t0IO/H8RJr
dTfgDsx4Ep2dGaDpihDqSrvcOZrjQx+dHvWXqOjXvlV9/vnI+vJBJs/iea5xioy0
Jrm7vDmZI+1yv0V1qk1fy+/cV5rmTrx6kDJU8+8cGEQWpRCx+Yu8Li2lBTuunsgL
ozB4Fjh049/8FjgF4XM3hrF3AZYD9THziEtOh/8IoJbt50q2epmQs+qIUV9JxDxC
W3C+04HNqohhmvZregT349PAijifcUbTguu3SQG4wca3Sn91dZUAL5yh4dLCCMjZ
RCjyYfuV0We2xGLn6CAz7rgSBJMIl4n34EyGK97u7G3+2dfPJRyn+jLA3aeDt7+O
nzR103h7Pp3eiooh467/khFuWistfJH6aN+AgJvfF4kfQ1uaQdUM09+U1xrk9Yp/
LPFupRI+tUwpgRz2LkUbMzF8ysRqXXQXOJNm/NfOrsDs5QFgByS+ZOy6S/B8px8g
Gdy4PZBjGqVu2nyp4ERf3ycOITHVCJFHULoliyynWX/G1LDADZNXwR5zwtVkVHzV
i9uasGmp/Qdnsm/skIXfYcGhCgyJkxFyMP62of6W0OmN7w59v7FWahBqiILH6Nzz
svuPmcRApMCPp7woFNNXr87TEjU0jrFru3/GDmXFMuE4dH5Uan3FWD2Agbhdp1TS
QQGAVXbmTn0+qBWIouOw6ctidjpkEyExJqGif1pV4CgpYCZb7RqRU1xBkuxCqcA8
3e8ewiRsMn7Eh2iBCDJlZMT9
=zB7x
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '582420b7-328e-4ea2-a00d-091708a692d1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//YIu+9PI+VUtUSEbhJHlRUklpNHZPjjnzwKz0V5iGCcW9
NchOYOk8cYvMku4qr1xFrYrGGTOu4Znuh/r0RfaqroAa7E4gOFnsX/CXsCy0vTCg
/uhVnci+D8pbDOL/8L2dF1pkjIq0E6lM1LqJTMynTtzNM7Xl2TP1lU1m648IRUdc
HD9MTrjXa52FKUlLzZVos3M+4xeEOQ6e3vf/qPcODCbtW5F7EZ2v/u+kV8dRgUez
vuJSydQCJFkMqEkkfZCKrgeu+dRPEI5MrQ065fbPESEj6yhuAX6AVp9l0zYE50wn
ZKdOhm3/cdMwvU/pg/UnhYhdn5qThD2Zm7ZO3hXuM73Xsv2REe2YFFkYhlC7NBnV
DKcHsS5Nz8WlQdGpTp8eFYQytBevT0ARMdyFQeIvsLy1pcdZ31Cjs75Zmf4120OK
n5CxJk89nJWa5X4dzJnV5AOlxW2A040GtozAPYdFZgPsezmDrnIsUJhQSAMe5A+a
4/cEThZmHf5lk7LlT5kJQ4O5SJKBTyRs/+XLoMoK4Wjd3S2gxUtDaFIDsRvL0Q3y
jK/qQaU4uYCI+N2DYt5dc+whxJOVRqxds9TDV5vL6oNb671Mm7XqgNn1RBiI2dRm
gy7igmpbnBoHtcEDUqJ0HcQ/lGIU085PZ6S5r9H90xFL9U6qX/212dpEksCU2wPS
QQGpW4a26mbCCExd9XGsSqfBUMzRvuTJ6Y5Iydt9RbcIs10imlmSTtUm2wTfLRov
GkLhRnrwsSazyU79lC9x4xlY
=YPRg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ce4e305-48b4-4d6f-a86e-47e17c723978',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAraP6CqX6rknGreVk4StZxuSZiYeKuXG8CW1EBFMjNtrK
wCpMd6RewcvuLqFCrnT4SUztZOY1WqD21ZDQT4QPIh1It0XNUuio6/cVEgT5uPsW
v59Nls1vbPlL8jKsnMhjRZWel1CTz8Rcb2i5aTyNCjZ3VhQhR8eBzocRKMGEn5TH
cGW2Q1+I8YKpcZkH5qTZUczVt8N0GmX9H9NElO3Oq8HvTwH6JqYmZhX+nWNZjVbh
vXv4ntqT57wftdFpFtQ8Tf42sqg4SMikl4n0V1Xfadva0ID1juqaLXR/hCxNub6k
UEQ4RMviP/AsX+3ARjBacLca9kMhom/gTSJV5hEzcbpSXN+iTffMer27uH2EFPfz
3J2qS8nsxc+8ee5N9xCCGC9D3oxQlDl/jqxSaske935JuFUWzQf1xYvKt8VjIJp2
1c6FLiQyU/rLz8Zcv97lJY5Q2GD6kFdURkLua+P7clXQ3QVbjCvgaTTDiDor/eit
qp+++C9YFnHpFi1frrQKXqk1jCq8tYOo63P9dKeNsbc1f9axc4WJpwCVDlV5ph3F
tcWBQPBQL4GzFc2ilPQqZRPmGfEXcZlozfZV9lZYzBpz8/nl1zhORf+ODzMyh5/x
y2AL8LLyyrNPE/sFSRkQx3DXKd3awuMP/bcoZ8n8B+TxDOqdoKFSDGfWHcm2AV7S
QQEYElOcAmrZ5IiMyIsyvy05f3m+fSpN1Dj6QOBY7PEldwXy6Yu2WhHXQXEhSN0I
/ItULt7OakM5a7VE6/sTdyWM
=TfqP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5eb46d00-04b7-4448-a3c3-df27e1b2728b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NDKtDftd2j4dc8156vXwkLIe2n3/W4XfNzVbCR69STUT
1wp5mfvj56Frcu1QbQWHjPGklry+TymsVUg9+L41n0gaLrrAS5hTMI5cv7WbkBQ+
GHcy6MyNkcMpxph4WSaXfiTuTVHkKrvRXfVUG/kyEaSQPqanZyhjmqV2Mge4CxIx
zzq76jJYPE9mxmfCTh1EEGUkQzsmyXhI++iP2haR4RGqPZdt8yclj4PEeDHL+iyI
ApDj5HuYmKwwvyDYh0MuqJQZDliI1i1NrqtZehPBQPg+v0YZd993GM9YCalzuTMR
STPGWdV7DAzLzK4l1Rq1meRt/BX9cX9WRHLlSJCFgwckZIzHUFb2FVxV24sRJtQe
ODgh38I3+sUJnayzi8qHv4/qZITigh4sbOQzuqv1pO0/I2NQiI1ywn1hT9TNoizH
enWx8OmVejrOKf7dmqUs3RAgvwtzzNZsB+hMleBdJTav6/cbcQm/7NquK6npE0wa
s1wSkW0rMKIKoAvltrpodcsaFl/9WIIU/Ei6uNF2WF7reR8nZGuK5h+mbP0OrFZk
mzebaAycX3Ci48Y1waiZTjhFtCKNbU6xUwJE8GsiVGvxyce2hzA32kQs0YaOvsw4
0VJGKz2aKxqL1HKfHz/CRdpCWyPHvjZlW9QI3bb//r4nmsGWsFfc3z56eKZepIbS
QAHkb/IaBLTtoISs9LnTySJEB6ldqOzdq0rU0tlc+4iiZpsm15XeZFaBNCdlC7DY
OAVw/sfBG5urtaF8Gi8LZWU=
=o5Lv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5eec691e-288e-4dc7-ae97-4c241ef5a0c2',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAimhHa660yoEC2DdWJWXuyGNmE+3Nbuvg78iFxbLKRz2c
24Jc07YTYfLRtNINmoUGz3sg9xMbWkFrUMtzTbkEG2s2+u4YTh230pOVe2Aq+MNJ
owTmSnTIh1o44aRgl2+NZkab4Yqk6vgxDx3qWBq36TuxJ96EBPKrNv0XldOEnagh
OeKZs3PKGbVpCGTyCPyYr/fQLS+XR/XUejipB+OiT4nGOvCrSRh8ihRZo7WhYOE4
/BrXRvayMEKFZ/77x+8H5j0SGJGitETbbMLIBCF53x6XY10aVoa+bJifN59G2gMB
crCOliPZKlXBLwpYEZp3cPnJpgRadm7ztl+7UiffzMFMs9dV5mWyEyYbPNIP4yBa
jV/aA4VErsMg7cAwJKM3w7J6ZSWcp6ejxj4DLLqphdUV9BaGGa66nWk+gyNZk1P4
Fzl5MQs+/yTFc2tkvaIZe11IhAb0E038+fGPmhl46YGRt6Lh27fvkU601vDUM6rZ
4+picy+YgesBUKfM9xGUp33lhhM9VDDOopMGLUDnwn6aKjinEk42e+71iLUqvlSo
XZkaimjhw8eaM087rTR0UgjuTLiO8Ncj+1cn+IHedXfHg+gN6XWs0Ne5pdD3sSV0
3rDHlXNDRc+WqBs4Y7xbcktnploX5gZNqA4yENriOfXatjfdjolhk4JzljLzLSjS
QQEL0DQ5AlSk8RwMgkrSeY3IsAuJoT8EA65VwOWi6ygP1YTRqL9scDtInWRkKnNt
OhetF+Pnly3VN18SonQcFELc
=9Q0v
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '622ce678-cfd7-4810-afa6-75fd46f08393',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA0YblAsJtDULCeKlErhIQWgz/qzFaQk9uDBtZLpQtP7px
Hu95n/BjBubPZ8NyOqxBrgoFRre6m8pijhheXgOKmhTcILL65V26zUBSD0miV+Jg
NxP4oN7iWPbl84bTzf/5fQv8owGH1XkinDD/q6I/TM20/tk59D2rMQhXiZ08V1/Q
VM90J7SO8B2wzuDeSs7xO3YV4ZMCz97RhJ5xeYA9cRZn9dJtKoZvaca10xrYvbcX
1gpgKR2LPTrZdclvvzXrDTTMzTb+g4dCdTB1OcDoVJYLVWCA8/EQ3ut95Y2aPXfe
1QZqmonMM61pUNoWBhCB5LCUv2KiLk68AWAmtO8PEYy32YoKjumiazp1FuGqZkn8
YwkJhsNi1SEsTT5ctpTDAncCTPB+F1jG3uSJ6ReWMISYyz3g7unM9h+H8eHjZ9Ka
6npRerNt4dLw1ASeKiTp6QeJAsbBO9dgkevIRt7rJhIbn/qqFcg38ZuLnyUoynrd
UdwGu7WSa7+vBvSxUPccwgjjPaDjG+MzYAV7wZ53BnAezA7kPEev5QOdRLh3Hr/k
gMiNHpxeeyDg1RbcqEA/xX//0768HZhQGQHVacVcxQWJlXGSMvi96wVTsRGkaC72
quMYuS+IX/dQapiayvCt0uI3bkDFkiG6wVobuG/gQ85tfxc9eUwAfQWkDtDmnYPS
QQH2lt4XBaEH1PjcFh9h0BM9jnikKKOc2lLh04ZMURYqtbkZBYT+/0nNYjOzngge
NNuJkDH4b9oH2qHtp6csM4QB
=w9Fr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '63772ac2-dd32-48d6-affd-582a335a14ab',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAiKC7qaOhspsvZxELyVTG5p/0QehjLhrOOoQ9GHeihQLC
0p2FG7JZc2NdCJ/I1I46YMoRYAX1D0K0UXiP8L1TaYnrYyonaC/gacdr6Hoygo9a
SgiRkrR9Ce6FQGZEzhc93OSTRdZAIdELKFfLv1rxXEghIveDP2ixlNdxYCAStwsg
mPTPMD3p/DJXQbE+En9bQXZXRrV18KNfv08uxDPztoiMTX23Wv0qqeDTaQ0K+qxt
rMs74hiuPZ8MXmH98do2a42B/52PGBZllao1gcFaApX4mQQ1bePLeBzmrAZbM1/c
r3GPVewVDQ7czayLeG5kwrAuCkP5cnxixtEj/8SWyNJEAVveg+GUf5yh2KY4gKU+
EWb0XaPOxb7S6GYGZtJydAjTRCM6VvLXXsMy4E+aSJ7e7T/wpGLnnfkxZRCJ4heX
8AhOtgo=
=Mx89
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6d8be013-f078-4910-ac3f-357c38a36046',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9GM6smE6FUfiFugbpTcNJLwQbAhmUsi8RAWu4WXAGpIif
Ut06JKmFsrBbjCfpi9j40p7ru0vqTf2A4Wwjz7YNReW8Ze5kmW6lOWx/67GDoNhg
UjU/GedEvHFG6M24QLdec4h/OIjTGgbjxMgCC/32KZroApkb/sTP3IcYGZMQ9bdm
eWKbirf7DIZEkR4bMcoK6jNfUWJiZh40xMigdvdVKJGdFYYpR35o4uNYOKGTiLOu
C55HRNaexBCGPu2+rolCWmrCDVf2b1MdCMr08p0mdACzny4Z+gBzlfzLXCsDedKz
IPe2kjJHS5GwtHHj0whsfXguroodEGvbrqvthVJBugmMzz4/62QFfnbkXBLIY4pD
ytPVlqG68d09M1F/tWc7apL9Szs8SdslpBE8UaBo6e3kMKlikq/dZ1C+epLzQb3R
AJfV5eNiPfsmtm4WbwoJ2ZmNRZNRCcAG98mBrRzvTTj+3XoVfvSNewfQc3fPZZl8
XuHbn4ftJim8JlTor5A5xKmEVT32pa6Ru0EvYRpIShVbT0yuV9x7w3h+Q7maorpo
UnDclWyrUEgjwCDpGlExfR4mhuaZjZiik4DvA9AZQVu0Ld+j6gnBXcZixIthTQQT
sB9MriOcBlmS+a2phr1UEsHaRNRpLrkBkZx0QQ30QAyOT5REnuLI1Rs3cRKPt/jS
QwEbl98dJDojSz2pq+rzu1eS+CZe7Ok6zax1n7ksgPuSp10ZTYLL3K+0leu4/EXM
84Ql+HF/TrL1i/LAPVgRkZDA6Xs=
=jbEh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '76f71958-09fd-4e91-a06c-f6ffd0dac47b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAu6Sg5OxjzU5bR1VJs/VUhZjJeYldo/Y4qGYwQN80ihZ7
3F3HQ+fuJEWkh/LRI9+bF2SB6AbkN0NG93KSNP5lfn5kM1qhZhO/5HgeBy6XXWeB
O3Pg5M+hjCGcM5A/iO4w0b6S5Y5LXC1yV/RMwl/+S8Y7nbTOlUx4O2yxKXai104p
8LRlpFiwab9Nm1eIhBCX1a9p9gRTdxtf36BUPRQIcGFf32+hOztJC9hwh8ZrR81p
s9pr0sPgTf6U9x/ZTLP0e40HgRFJTTmeURFf25CNS+H2kG5mxJN/tOPHe27VavjF
1zbe/JumxemOvWwLw9uvj3DCPcD/yxr5TM3C5jY0wJK/Yx073GjzBCmKa7bU/lNV
wNHMs96PHuZ+a5TuYjKHxoukLvlXKeHIBRnn/Xro3B+NkQAVyyOuKIF5Ob9wuZ+f
SKvIOyQZu8toxRma/imywN6wIyuAfE/nntKaznyMEGJDBNhGIXYb+ALdspabJ9Wu
2cKaJzobdB0HrQo7bh+pWV3Mshv/qfH7TvvBuG+jOm/EFCq5rJ4vH2q7OhpqTnVo
6gAsnhXWgxHkFGj3tdpcp+QV39Pzy4DFs99rs3FxdpGionRLSm+uyFlbhZiJHjDH
sDOgt1dQr/TA7PcRSNygkNWLXLMT3Na05p3dF+EdVy9LTGcog94cWqvj9j98lvLS
QwG0d5cAl5odsAM1bPDExbMU2NMBdvuX6kYyq+TP266v0uInkuRJIbUXFMSSBaeI
GcW2PGAqkvDUlR10kE/SlG8siho=
=CShf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '773a1eb7-bc47-4133-a4e6-784051dd6659',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//dUE8Yjj5BDT7TLEbBhWS8w8GOs7pv9Q7hhgJduHZg0tw
VeJ2sqogQlb1mNDneUwfc8wwunh52WQCOAR5yqPSCFTyoC0MmOczwAIgjNaM4q0y
TsqUozeCUNL9r59nyVKDkDGVcAK8Fz8UeSq3VclO7Lc37Ztm6aII2BcfFMF/qsDe
z6cHnoh00347Afg1HrSt5Tw504I7JM9kBiQgDooTBhCW0vHJC39EgR/RDuDI5zV5
T41NLFIfldpLQzFvEAoOTEt5Ijsvo3Oxc+usgIp9cxkaair+Yi5wKZqvsY4f1Tqj
0TcYUDcbsXvlLbh4rbqILa2XegV5KoHOAJapALnQ5soTX1ELK2kbh323zviN+Muq
M/YgHccomM5t33eBYFpKT0RN/NiAt7vGh/1Om95DUPTkvR/MvqrZFFkvM15gOF4U
vnkEJvX7H/mAVlIXbgAGgd+F3bH5zpBC6BVADUQweHSjwM0mHaqznRTQPGWyxRK/
zevT61PKo9SeCn32qsVyGoZoFosKPwA7erQfHYTiKC4dsiSQLZYd97kbd5lJYzQ7
G2BTuKuUXAPklUp/bAsV+5La1wjOAfQElAbgUOqm0+NGmRriSsUFwR73YeUNXyT2
eAVTNp0Kws3pRmR8guuS38e7USqShtb5ka/HHBQUb8sODACul5EqlGgLDIyBKAHS
RAG56aryG0slcdVBRp2x9F9tyOfA5xFItbjK/2gNYnApxpf+6tpvai9S0brEsYvy
oG+b+JveEeVnWqyb3u1gw5yTPDYp
=1/tt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '78abe02c-ce2a-4533-a09b-a2319998c181',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAoDH6NazRiRQsVlInepytNe1gWBIhTvlMtzuMpm33A7am
B9VOzBvD0lVc8uGg7dzGXJplbS+pf4bUmAXkwC3TCanjS+FiFjZ3ntNqh/644jIR
RUParkPdZiqQGqDZmLODvNAoTA1aLm9HvQmfeEXdR/ycJIfSJqLt+FJLUzperC1x
bl1ZwYRT7xAUuyViQbqUrKe4jJKsvvVvp+CcyvCZYpLkI2wtVGS0Pb6tBg8m/7BD
BmLKTR4w60ZH9r5yh5c+etsLokRjUNYe8sDVDd+6BsPujX1OJKa70tPkHCIqMPLH
Olt6NxEzZZfQAz9hkn5v7WXQUXyxfvg61HxdxCUDYdJDAaXwwnlanWhhjwrBjb36
eGxUYrgLh19Z68LYFckketspwKeUMfFuVyhmcdJz4shHd5YStUrMlOiKoXv9XYap
MYqnow==
=VgpM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '87ce331a-91e3-43fe-a924-a13af10a07c0',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+KEjAmqKO0+IXvVGdsGBsHzjYQR5qxfTZxCnfr0aUwqcz
N/u7jb502BCpt8yvuPjZN/ua5BEnJ0FpD4C9rQDgKp14U3M6fBnDuUyMiBJKTtsL
j8g8r8PqGem9SdBLtnhqbvoYYFIbthDudDzvz2R4WzH2QK+DVAtAdG8rn6cOKSab
/N5RjPKoOlrIvuAH9Iyt3l8ykGgeh1hMGj9Gvkj/8b34ppTeIkmWkuaOG6rx8f1S
7/ALJpJw4pIyTlnOeAMzTcRt8YYvXOMhZfyh9KVcmHqL7lqKr8b29cdp4wjmO9jg
lhwcF4woDwX6zFBxh+m2uRBnx0Z1gSSttnUPpKjeQgQSP56K4EvK3Hk1VYIpphnE
/xxLHrvZJ9kvtQJ41eSNmIg3YiSzcxAHz+OL2HzPI0vnNplhvWMy5eWzdsGDXghu
t/sNj+NO4xsiPN4IW4M1wx2E2lL5YOb2FpKJrSwTxFbq3wOeiOPbKbQkZd3xld+5
N5kAvkZgdQD3oScy2U2f/Pw5mUcUmKZvsFIxVKmKI0APqtE2VyhHKWCjgsj8d5y2
AJeTspmtrzlWTNrcQpZI8TTvIjrlM/nJxKJYXEOJcIejhIXwg4DSW8a8bxp+Zu1d
goPdluPDyWaN9TO98eRav99o3w0yDmTcaCO7BIEQvq2IxZbCYPnNDbRhFCmXvy/S
QQFpyG8iq9gIsckquGurPpA91KztCcqqlEa3t9cmagsdherVruaYDnDLP312uWfk
d4hs1oyUj+EQbATlnqef3u9K
=uN3p
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '87d92dc3-c657-4810-a71e-cf4c60582cf4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9GdzqHCl+Bi7MMpv3+TYwu8NNeSw8+0MJbymlCX7fiyPo
CZNnr8V4ycXvBtsK+LyZYB1L6P53MqvQHoEQFp+MUSOLKZhO21WPtxSLvC1OagMt
d5R/4uDLBjEn5/UAvot5fiUdKvpibwJobeLDkHJLzgLwPEV9wFvxwTPXdRU/UFVJ
+Baq9oq8JFjKBgdGs1KWnfpG4JZtrJYw6TBN8N4qacWBor7FCD2x8ft+raEqyANI
2XCYDEKuw3mS7BzVJWhLE6xySzZrEJythW+szKdr0XZJz+yX8O+5MdloCLLRqw7m
HEM58Q97Z72H+IL7jln4DL1nQ00yumSlpY0uCRobt6OP0qq0ikG+gc0aLcPgywoH
V9K1/6OYl0cQevGkOJDr9db1EnDklfIWNl+UELYCWHdFYhIHDvERjJPBNuN09BLm
q0D11bl3nwS5jT38KkNkchAOPq5B9EwG2KdaDdbkmrJkcuEqn4pq0G9vSr0lpO9Q
/TbW5sacKhle25QTDmn7GueyLaFfHtiGc/43iqC5ZcfD1Qy4yz4jz05aKRKk3FZ0
/pUORWVA8VSq4wboTAq7Wm0le4IGkinGMZlKxO3qFUxvAnPZ4Ens9EI4s3y7OK2Q
g0VmOy/aXrrejaAplZG/nuDBeDSZaxQ6FGOzKhpadmgnFcqCCTIYSQ04knu69trS
PQFINjD2N4M6SgpamCC6pHY0Bzsds+ruGD3mNhLcAPhciYE0UIg3jEOppLh/FPqp
2n42AbtL8h369PQr5n8=
=U3wv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8bc70e5e-510f-47ba-a4f3-842423cfccda',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//RB2pZSt0ZSq6bLhlL5Ano9TJ8TVtiKOlApvlROkJoTOx
Sa4QkKmu7FFrmAQ/toMjvhKGVyISgTSUMLSKyeGGEizVNoXWLKpr44/thfHUnpvo
gEiVO/Jzyp2qiskHxpbIp2syppsKIU57LGz/FrcHsF4FYy5M37mIqt7ayu27TutY
0wOxT4zR6WiD3jAGTDIcQ0rpXdax7Ol+dhtmMiuS8JBopp/THM/cROq+CSu8SwUh
12tt0hm8G1i0rdioXTHa93/IAsIFQq3QZC3ceRzPyg193LwNzMLYOr9sczqr/AQu
gbIplPqCKcVSgDvKvfAxUIP/vFpgKBAvjfLKMZr7+jSen4x1og1BqHfT2++4v7x+
MBmtdkdINZR7GP7S5lkbGo86DHJYjJn7e1M8yvxQJkm9fhGsooJs/NLtTUJ1wF1R
rrz/dnevhgz/Ceox936i+a8FlbDRV+GuGJAJXwikXQZiZZanSUfJ15LQOOhkKqA9
LIqlae6Z4poU1FuN7WgEtmSyUiM0nfaIT/Ia42LHcTTbOS+rqgrRgjoDP8NjX/q1
9o7W4vMIJC/4MlOPcmt10dhaW7YJ//2qp47ElrNjuUJXspnqbAn3jAAcYuyuthcx
nIWvNYwp/1DMfAtGyedyzTRKF7SrFy8/G4sYUL0S8NBSrZ9Z3cjGQnwuAP5Lzv3S
QwFDdqWKRQhj8F/408gXuCV/o8WFylsEd9Z8qTJhhvuDsNA631FsI3kooeu0Ytp9
eHVJfjY8+3Db5dW+d3+jMWX4DZw=
=U9aJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96fc493e-caa7-48b8-a268-1a967b5b8a91',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/SsDFkFNz4Vw/1mybbWQguUwPA0P/EG03mWpEe0EmsUE3
aVdq5co8xL4D/Y2vbXB7tc6v+1IBvnjfR0Cw4nI9SPzBt5HNeqxMrfpd+YozrFAu
MbBqDFbT8KMIZtLAFMrCURpKB85XNTwK9ozs7R3Bd4cdb6lITEidnSZuugAzuy/c
RtKe3MOoqhDz4se276NK2/Mt7VJP5w6x9QxNfEaa+DxI8a9qkmB6zB4afch9wtHy
wIofD1TfrIbDeccKF4Ob75SiKM6jGx4vti/xnFUgVTS3L2GSdruobMbn/4Lx1a5m
LNWQqkc7jpjjMP7vANkDCybemh2+LmJ5B62LwIv6YNJDAbGz8VwTStlvycuzP8wH
3pXMEnP1RfbzkolzHPXydtNMN8BgGioJoGmHVMAc7541UR4gwLhD9H0WNc0xC2YL
AVwXPw==
=REQL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '97ca124c-554f-43f0-a0c8-9bb8c63c07a4',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+OHUOpo/tLSATy/nSpxgFadHprZjcG0eYmg1I7RiEb1ae
AM7zXojbPQOiBOdTajV/c0VlKpIVADehs7OIrpd4byYZ+KkZEUZOflKwAxVZ3Iwo
QULPnX/Ca0QwQsS3kp7DtTrNjjKYUYiuEBOjM0YC+QldbdSnr680+ukzXDWVZFcU
d91feGTYO/eBp7JXDPleZZKwa/obpjytmpwYihZz+tKDL97ZnCtI40ki6EfRc2Uu
2Ftq9yY518NMKYydwXtbfaeHYGRzZykGyLQOsGIe7cTazXIzIQ4SNLBxt6hjjMQW
i3/fNBr9Tt++kqpuuu6HsBYmyAGd8/J5PqKiHtf83mmK3OFXQOGzbCQEA8ksgYXc
edm8aAn+eqj2uIK32GDZjHCdInPqpBCYKr+Wf/CJi7W112rP3Octd5TcHNfWlCP1
CNlFu1KTyDINk+L5kTHG11VWeAyAT7n8lB3kPKnbVFsKuiRDNix7iED2wTKrkO/9
lgus1Pv7Jrh102nfRxaLAkR9C2I0sn7ZHjYtNxaDyjPdfFKQd2nb4lhSCG7+u9nM
nIwcyp0g0kah1oVhP/lxl1CwQJM2lUfgiS+R4HzZDTXonxfQEZBb1oY5V5rj6ZRL
rTVwdqA85TExvuwM58euzVu2MqgTzxRpGRRoeA9fuB7fHxFyQW3kNJoDRT2aTXzS
QAFerKxMMqwtWX5E5c/oK3qrDlfrtNjRMamjRp2OkPgcIZJWRFz/6ZH/HOKz/nt6
v9KgLAQ/yYjJs9Z6wvwFyyg=
=KWP1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a433aa91-d5ed-43b8-a47b-0ae63e550c64',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8Chd2/n3vv8xzoty+tRcQkHBlbq7c7woBjAwW3kBZZu6s
D7mIGFo7yl08pFgKJ199ov7KfsHNiV0GVBwD9OyOEKbaUEabxWj4zyyJpBupt7F+
XU7a1Lz6adMv1p9ZbSd81Dse1ZJxg1W0ylyaUGR7sbCfsZkmbr+c+SjnTL+wcmKy
fLgB+7HWlXcey6BVqFrJSMlnS9sx6SEbp+w3a2gDgBo4fOqluiw1AccqoT2alO65
yIlfZY9N8lHPq9gtpgTGd1u60uJyj58DH+opt6ea+DOinskpnwvrXn70hQwssZhD
2SPruJCxLPg0sh3NPd+SZEejIejCK1qhdBQP6BA/yqRExLXOODtfW8j+YZN4g2bx
BWJhvrvOUk1HOfT42AT0ZVMVnA4DHiD3cAAbnePDpLLZW3AERtkFxeF+MadZorY6
9se+/0y/cwgWmGw8u9zPUFYhhoEcsq3iZD3rpTGd2QLJO/rin3l74VMn8eOzOyYY
BmVmG/xU6pIQG5RWKWYJZb0xRpDcmJo1FZmiHKL2Rf2OJH81RK7WSdN1mgONcMgW
qkdaNpQFjX++ujzXELwYRem+q2MZxmuKWorkTodmnDe2O1JANhVIK4jpOjEb5gbQ
caJoJIpeAPCRp7GEb7BwXR5QCgtjRFfsmb5TurY15bmsaH4ZhppA+phdMBQVkIrS
QwEY0cnNbRXsbvdxn77McMM7cLsV5mF2UjHfKO9A+a0KO0ONaWiESD20GV+BDhaO
EqO916qBkC+Oun4Ji0OlYtbT++Y=
=lpPq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a4767a2c-f799-4f87-a6f0-89f406860961',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAoYs1DiuqtgN5aH5pg9e7TtEPhp/hDxP5NsplyI/TIJES
fIJGst4X+d5+Oe50OtLvYCy6ZhPSGSkg6SNLxdGNQMS8aKI3LAf5NpMlvmEUoHZ1
iBX79sTtos8CdOW6+WDI+NzZDy6T4e8AH2QVZ7kaUqdvxqxM8a5NPrDFMOZzVlWQ
2QgBqgoUuO38GlQrZjrSosAFSq5P56z2H+VE2IxNRzSMT1OdrFsS5Q7zVt+4heRY
FdnOvkrDk7G6oi4dpU7ue6sQg7RQei+wxb87HUal+dvN3HvghqjJG/b+GeKEvUuE
tZNe6HDqk8l61WEToGsX32KbSXQUcDU388eIqgcySoBmb7rzpYZhYpyE8bCAa4x5
we6sVtN52ruc+ZbzM0kEYwKhYHa8sc6+BbdMn99MRBZVq+zOmpFR2IJH3guxvBEe
8T73W9oMa6Yn/VXJFHxZiYznN38ABkXuesOoLfc324nLJb2nI3TDuAc+R/y45CEP
7myC8uD5+9lAxxV4g9D98ELwDeG45uH1qqdscUkHWJAY4WuhcTzIlcKI2SV8grH1
Uaw4eoK7c08GMegzE5fA1vjoxuo6j9EJN3GeeUCshapBVR22rFEy8FHI+cyvbu0+
qp4j9fTzdzeadYWeMtnovsZseTzlWLF5waFXjoUw1kYn5AD02HpTJ2oo0MXish3S
QAE6l89xuX6k+zIgnzJLoOILAr1CxyECGiVsdrB74+H3SDiGl/72TgC+5x8Ou1We
iVB0PqGoVMk3e5zAfE3Cigk=
=J/+Q
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a6cd8bd4-ec0e-47f7-a04a-d827220230f5',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAkE9AhEe0CXoZfXvWjlU0iv519mSIZH/CKmgDgp3Bf9op
F26tAE9Dq9XeHgIZzqAj8VAcmIs6ZB59qlm/FqdQif5N7Czth+P9xHRaEMqS13RU
ZVp0rIRgBSE81fIgp77oqbSG5A6iRMAEzl/Ww3wcvELjl3Uuf1bLOLxR2N2gKAJL
VDQVsc7SpwRnWqbmWwq93UHUscsJtOTD6sCh/PBoRr+WPZOIE7xvHShWcjx/ajFn
GYzIhS8HroE+1UaDDMk/zGfk1oMKqFGeDMCZuzplHX8NrYsUvheP1+OcmgNESAhh
/oDzzHFebYH56eHBmP+0/vbhND0Z1V4zPwFyMSKuka6xIS1l+4brR7lOkCvW90ia
GU1C5n/ADpnfMhi771YsJB0FD+HYfao3L+Hx/bUjX+Z7PPPNN6XF7d8dxqhyrzPj
ZzK5cskTMdMGNh2BOjcg1aMxavU2f5uOdW8SIESfvtPD6UVwBhlUp5MDlHkgFctl
ECz7QnaII2h85QULX79vKkws7KzzCBNzATChQUN2KBCz/377/ybF0JjnSBQYfmv7
TB1JtNWQNMH6IRYd4k6tkMNloqwS6MKAhwmwIVrTGmQOC5dQB8Gz+38kE/0liBrP
vie+ydKytYML7XgiRQ66sfLUoQJVT7Un9d8TJex+VQR9W78eigBbbV9oZVb9SK3S
QQEpDvEx2gvYptQ1EBWr5R7+9uq5G349rJkOD8Dih5iBIrY8Zqa4XS6SesbX0TcH
nNH4ud090ylz04xrUBbC+viz
=iigo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'abbc2920-e8dd-4bbf-a37c-cb52fade7e32',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAw2M51mGsfBPNVkNOgiScuWzT8ldtQhenYYTUPwEzXDAg
v3YcjWtWVx4BCBCD/lSi7Ohks6PHO+dmDHVVlFRhsz4GE0g9mt4LgpTsgszJjSWj
10By7ese02mJN/dIAd7izZ3ZXCsgAn8Zoy1zS0HcQ+Wnn5gZGW9lmp/zmcKzXV2Y
r6101l/1DIJvJUjlbvpInZSBaLEKjfEmeJkzyjuGduCoE96YlXvgJ9kxdzOjP/Gy
/GjaSVz7lYXAdGFOOUAiapcuOSKD7uSrNQCOOA3c0o11h/GhtaTBx2Vgor/yWk9R
R0D9lPTGnpt0wPmJS/fe4HsfR0bJQk+G6iyQsEuNa6/ZOlLWZxu9CLR7vE+WVhmd
0ArgnKwwmB3S1pE9DoXcWozfAO0sjCePXEhlNhzdoyGPEn9QLMY6UIUfeb3LG7Y+
7OJrTvSSuYDocvsFo0VlV5nes5h58Vju8IeMp6mfyK/fXqfa/nBq2E3Xji494ZtO
d788zZg3Ey/cOoR33PYnemcGsRkLjBQNX8W4nL2D0blwtq633w8EMtMLXr915c/1
TSyLY6NncRFwylacrdu/R+0IrAgl6/gyVnBezrZuvG/F+vZjYFFjyVs4fmQ/FEx2
x4nFAcGpCrVLSpbq1snd3ltRqWkuMVRBV2f2YRwrYLTyOhzEzqD7A8cST5TucGXS
QwE2C+D59SxNpqqWsd52fGJaoLSUzSUd8sVHA1gSFxP5UHSerNwmOfmfB0kgOc7I
obxxNihuiXgjoD7AwUswjbsP3xk=
=JBdU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'abf3a31d-750a-40ff-a96a-fc65fd3a8a2b',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAjM6pWUSE1oDLqXpWBAdM/sBw7zRy4dpqgINcl6mPaci2
+lpBK0UVi+B3mlL7soUzfUJVrU5lYV2pkIPf7tlPRFTkSfVdomJOuoIk6EE05l8F
LhOZouYGxwJj76yhULky5o0JgHsvA0dkRT/0NLB+rYyP/FzJnXQtIb3F6TgdPalJ
LqnJGXKXtVRrT9GJOeIQe6ZjfR0bDQNQoKM/pXxNoe/zVCOY6p6dCl1kKHoYRLpG
X6Tyh3tMdMKtcgH73NE4KygSqfDL5kqQmSFs3BRRwe344Q87QjVTlIvZGBClyXHa
dlFwN0BFLeLqUU0huUiXDudYnfZlGnewwjhcncqfS4PaDvdur1/t0ijXqQWT9eNp
CUrvA+Qc3w2UhVnNyE58q5cNyk0cXKH0k6GO7yvoO9AJaf7O+O5PbnXL3tDPisdW
atebv/1/Jgos42Kr+EislEdU+FBVT9lXB70tGM9sYH0M/a08fh3hI5/Nr69AQmly
1TLaQosTeB623yP1Aoo3A0LDroNdnLpTDfOKaXKg/vhbWjy8R2fQEWYRE3tM8ET8
IPDSxbVds92QI06nDYmljhfnS/ma/nytMorxiT1glBMu2vjpPIfWJynFsG4HDDmZ
cf9itr9H7IKQpJuOMeM1gUu0KLEyeOakAMwPTpsll0wmS1C1RDr17I5eKT3LVUXS
QgGwonH0EOM/CnE523sZTcAeYsDAkI20vMe1chLhiw7IL6pn2lljirjPn9cCPeZb
cvV+lfTXbjrBzSa8StTBWz3tyw==
=BvRJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af0d5463-ca33-45a0-a235-828d33b9fac3',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQELAwvNmZMMcWZiAQf4/uQHQ+B1q/kmAx5U4uOyIUSsTXrXNEbYBx0N3o/8E4dU
XGxYDZs8n3RBADUjslcmi4a83VywBNfbIODJESh/XT7I5oCuvziozriClkz4JNhP
d8aRz+rbcFicN3azje9pSzQjMbJ1XT2IUBPLhaWt3zEj9e78GNQialNMZ4hOI6Q4
bjaioHDrfM75l2QApKTvn3F31vYIN7TKWUYkJoEBHB4ssOMIjetFX2CSpOe+Ilaj
kDUs+N0imz2mhycRAA0LKEY0HHmNeqaFE4sg1HLP9S99YruFA7RHXP4L0S7CHRHs
fqUuX1pCGRC5k/hHGNRYcFwN1KwTvGzqLwPlLsg30kIBn089CdYBRPiQ00hVYJPQ
dIGZao4LcCrH5ry6l7Y1MJoZq7fFK8A85eqM9SnCuSjoWlwtpY01FCjWmhi+dzfN
2bg=
=TXAj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af109bc4-7cce-4685-afd8-73fcb2efc7dc',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAtW4lBiLAA0vaT0yaEyy72Jrh+yuWwhsob7aVZYsfl4i7
ZdHyOappDm/c7+mE24QTUMJwvauLrkSsNgNoFTuLP5uJ08FOtS5E045H4hCGkZkS
vmuMcsMbF/fZypfWAhIUO8IOgtqogdchgWoNSHtjv3A88zacwQuI3UdCKJFEWRzx
XrlSscanaVxskiEIZpay7Gyyute4fRwyq15qwihKrm6OP4MkJwmgs66LhO8jkSmx
OKaDqle/MfakuB/jtPH3URZRn0bTY3ONT3xy2rvw2xGLxqOgRFmf5Rocuw8YX82L
uOfV1LG6E2X/p2uX6u2zCiKGm7iV1Z0V2BAKd/InY8M06Y8br7uuxL440Ei4zSm2
/g0FCRAA1NSqjlx2K/Ow3uS/Dvf62K+J7D/b86HaslYAbD48V87+OBNq6w37+X+W
irhF4Iim/IRCbS2Hd4Qx6O4nqrqOnYDT8LWbWImpvgMm2g3ZmIJgQI1nWTjvXk0Y
CAwdytPnzv6htBgxbZptlYIScKsqta122wd5b3ZjOcqLQlAGly/9+VuemQ7iqD4c
arHbZ2xjgjE7ZF071wYp2CFW35OWI2QgN55JgDfu7kse9DZJ72+Pzd1ySSd4nCZG
DvzMcx/f9UG9W+m7OaEusr8tC2eSFADLPPUboDw2bGP9zBF2Asqpc0EQrg5bSGHS
PQESJlqKnpKHI+Df4wCbmn/AurfnhTl803SymFZy1Cn7Nw3FI9mGDwKc3XNaWAVE
AGL2bMOd04NpDwa7KPY=
=TwxK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b62eed0c-c76f-493a-af79-35070b5b38d6',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf9FpMgCVcU2RySVaOUv0X5yIYotKA6FNAeyKJKXCGbEAgy
tILEL182791Op9/kgcFXpLYrAh6fwDKyhtwqn2h1DRZKvxRyo5urpReVHaGanuDv
Js89NkbpIlSb2/OR2625LhPFAFftBmcrwKjwLKlebVLEgzXyE3xqOj0y09ZVe+HT
ryxFl8lN1pt5B57E9w6spRyXfYBosmNpEXRWrOEd422VQuim/ssoFobOaDW8ZswK
MaX46ZnZhgNt3RWfphxQPNXa26SjCVooV2lYpKKHHW+gvNfiE9zywXwy+uWnNZ0L
9AAfsMOXGW+yGI6RS3rksQkKfZyVWpGFMyH/LyrhONJBAfrLeiAxpHFyAUH5zXpe
8MZOVFp4QMrPZ7bmgtdTAkKtBrMoE/J2iZvabBJKxkop4rxQh7gtsNc4STD97Fyy
nW4=
=lH5a
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b7ec97bb-fafc-43ed-a2ab-5c3447d982f4',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/7Bnmatqobl174OYPFNqxdMluKAtFu4ESKXulFCG/ZyqI+
3U0OKnRVGFRmrBw27wG268Xn5Ic4ZVfUtbC6dxJVtTsJM5sk6SCxOBi6HGCCFZw9
sNQsi4VJGbYBrcMO+U2AHY8tIJmNCoOEgBKT1jfIi390uIkP0zE18/hFQ0D3+7rw
CB+74SsevAT3XEyj/pReF7l/g6GMaF+Kf7J/tIFKqK8NN2lc6krJx9OpKO2N/b8c
vUnxbTMfyhEHDKyCRBbG/NTYqXvUEWH3hSkgreeI9rKsZgDXvY48WfHBu9uvKQIB
sZz+qsTvlqV9+SngD5tBYEkPB3YqtsRiEbmoOp3URnPM5h4iSepULAUiP2sJEGBp
1DdtD0D3eIoGKrJKSSUTzmepIRG4kv4I4H6KnusK0pT4zjbc0mZwM3AbvsOpjKIx
RupLjDsQvZYkWNNpRcnaFADVbx1XsIbN8Vi7nQJIpyVOj1uWpePYcffVfHmeGZmp
GcaVEzE4h9yBS9BtthtWgtT2Nfwy97OkcWuUNWGdHDmXoek8QCZGVsSggpy8oEal
YbBBGMtF7aVgLj0b/NVs62QlMNKx1mJoa+YHBYQXSeyTDKa+Xm1UjxOPTwXxugk6
LEwgADWOTeFNqg2cILr5XHalAwaiFtfN349o95U6kISZ+1+8bhBxL+XrsGdzsaHS
QwHkIuMhXvLn+VgEOVmjMKyWcO5EiZLqN5x3IcfCCki8yMVRt1dvlPjYR7XxN9xP
vitWg01kMfHLXHJqbp1zmXxfy1M=
=5ZRd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b832d2b6-44e3-4294-ac4a-cd0cb76448d0',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAuwjl1eKGvoAS3mdMgoNTcoDseCjGpZD5jp6FBDv/+tCU
klNHC8iNlUIO9SVwGmiCjeNP49qWWNdjelrDxUDgnz77EOWP4Yn/kr8VBaKbZIEI
kUEDRD8FPBomV9nF2m8/AY2MZA5NrVWqxj23KnRTu8eMBvdQq/3JhFR5gadF8xle
a9MoqxO1ffz6SJjpfKmPqTEfdRSJIDfp3hHvkwpg00aNSPUPym+XaFHRe3thCjZF
7ErvWKOvxDV8vLlrJ8j0WtA9ER/nCjGG/BGmuM4GIUaQ1B1eWg6rcoA68+CFobY+
fJ0fNSm5/QLNIyBK3tRJ9P6TEtaZLoFEhE8vR364wYdid/ZB8u7Tk7aNWWGc0PWg
yCM7WRaymbQg843l0zB/apWKJNk0iB5vZSt4LmOCJSysOQl7xWyKrz6S4/qCempd
KamK6DdjYFShTlX33ht+c/lQjIG4DU26FK3qM2c4BuIL1Dz3a9TVAKTw3AKdr8oV
7XyatkWJVW4SKcV/BdmEV/mGTty36D/hWU+Y1Vh3WZyaTbkCTTDrpe31AfrkWgGL
5s4T/r3/kfV5we+JXwqDDSiUyPg5UwVWeV/2s4E/yJDvcywPV+Jbg/oSpERMercB
YrmnYtxB/kbfnFW+H7PMeBqYXewLfNucArw6wBV2BgkXDb8nZ6gWN7F2eOaTqD7S
QwHczmTbaoFii6Vbo4iKC7XSinIOxLGmg+gLQFkfejEmt2HTBU1CGEIu2CZMW7VT
lBJUWWfL3AsmFr3SMaKAjKQnnUg=
=+i+J
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b84825fa-67f3-4357-a03b-6313823447d3',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAvfjOp815xI6EaY9bO+bzK5HCHfcZEHdcnC+qsFXNCPDh
JG0USFo84TaD5YWMgi8N7zAt7sm/u/s32xOdgj1Sn04m92QXtENcg6PbXTwoPOU/
xwTUvn4d18zOm5ntSWoMBpksLvNVz+OxsQEwnKpsT1URRYkIbJKE/zR5v8Re6tWv
/G4VHU5GstzwFwdJSq7PVWVyDdnYDWdq7Yy1ChKCIq+k33EOiYH3C9/AZWwgnYo+
ehDdaGwQdI3CW/aZTz6OWefXnWu4oLWv0edmGgXApeIz+qBCpDQE7iP5USZGr6I0
Ez/c0QcryHR/PFNrejP0r7forFbrSFPOHuSdg1lErK5EpGSPjMciJRwZeb1AbUji
c1p1+wjAn1FcMBSntQadNW040MzFsOE4QERXk+blnfP5gVxkG+FFfZWTaKIEbBnW
LIctcsYopQ2w5MdTaoLybE/6RftSLibTTbGl8Pff7HGi5l/7aN7x28wDxucL/mqN
gihdIRSdPJVzxNVLF7skfbgP6SfrAqUwtuoZDnR437NzzB+3EYfX/wz7J+HgFuZf
PsuLEFDf3wisLJ8voWZ7C9kQgzRE3I0csUFpW+sRSwuVEdj8IZNoC+qHgk4gEJQK
cz06wbg47AFEu1SJrhcOrXhPOE2HA7iBk++b/aMQGyyjdxfxc0yn4vJF+MSNi7/S
QAE8VtIR7AI5cW450lttd12pyWNG2WfsxrcGXBYMVqtzPtS8bvM5eb5lkgVu81Wy
JOVjoezGpbMUpjgt8wHT80w=
=YNuC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bcfe0f35-498a-4eb8-a9a0-6b10d2f40dd0',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8CQPUFdU4FCCTKKe4yEm+bJ7W9OZ/zcBkT970uUUPeKCv
wSVlsfrt5r80+rTYSHxGWaRhZL20yxWVvdnDwBuhTwLjnq2yBuERi7d1T4xDcrlU
6etPfOXJrENPE5ZCBvtZM6j263ZZBQMY0o5HVmI6yAp5nzPASzF2x76RMpcI5LO6
W6wvaMbXF6w2XD6HzfX3pyPUhxFZJklC7R44rxXGk5vWrhfi8fIIrqiXbQ2QYfn4
7ZT2bLw6EuPHrZXUqyEbCSJTro5nJ0DCk3wxxZHdIYhg4CmtFrWkHi7ud3wdMvSz
uul3xc26p3+4hIBZmp6X4k1diXL6JvtIST1UhLyMdVCaijG9yEwqcSj54+FOA+Dd
h/u7FyG5dtxRpjqBnBBZRBgVaGEKOtfKx1aAtGOd6YLOF0Gg02ZDDJTGCZ1Fe20T
thhy6st7tTVDuBuSdMpnl67x4lZR/7p1F8Jl97+yM4+7AZD6Qh4iNUo/1peERbAO
TnPGphNjPR92s4jeA6IflI3ciKNzq9W7EJgWXwciqYAaoe7yAlrBxQGNdoEJRoyF
HXjaKDxbXTZWfhwkN8+JziEJpPcX2B94F1cmJn2JBQafEuF79+HjPbtSYEo2r5FV
Bdfi/L1bE6p9qx8VG5YQX11ROrz2PVoHdF7XtR8qlTo6kegde8gQTEP6X8zJjZfS
QwGCe0+x77+F5iofrKyIiwu9CfxfowwWQmLVMgt0kBf8ylxSd5Ya7SQ/Fwzu9fI/
jn5ynODP39I8R5wprmio/Uzq1Gw=
=r91+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c3b29961-abc6-4885-a45d-4a0109fc5c7d',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf9HlZNzy1ny1Q0Ay1+xQMi076in3dQZJKzyQUXopxK3a0C
niCy6/929+Fj2EgVEMcVGCNbutUhpzXwQ4JK78GQnQG160kbKHYTQy6YIEv0xcc+
+RhHi84j+LgcJ4C9na+EuyYqPtpUufRJkJOCY35YMDRJuGw4v1YD5Bo+S/0ndd1d
0l8ZmWTnVEg4nnU/2RPaGVLgxHA7HIWxyEvc6v8L7BwXU+6uByycjRqcdnGn19OQ
7UQYjUfe0JvQiwvJxdzjEH2gQGcw/qXcGVndPUqiV2qRws8J53ZibwAtm4Adczkm
a4Pjo3bAtjbjIQiiysNvQbWosdvXLOhkK7bfMr5H8tJDAR4UVyxSsiytE+Jcw6qg
36fSSqX/txpkOJWwNQBv4duGG4EQU5yZE4pggrPru6CSXroGZL2n+/lZgHwcwrlH
4OvPww==
=4hhW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cd61518c-7ad5-4b29-a89b-a7adfd4a811d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//YIB1iTsGtaIhX7DL+l/KXuzdMBOi6EgTRPL2IipkXJTF
O2ov0ANnxfJ3d19CIIPNngNB4zeDt2vZBezpJy3yHD6vytHMDhSzqE8gsaxZE/Hd
YqTMX5TA0wQA4fa1BMCiYepVAjF0zGhIXHfruVvRk0PnPbMqHD4Eqn0PabwMw2gD
kn+UC8ENsHeVszZHRxmNvahma10P0B0/ZHJrdjA5bc4aYRLrJfFl2bdYsENnxorn
sLXd52Bm51tEnz7GJ7kvyrMOhxH5ziVyun2dTwzYLZHVZGBR2EvfH4O59rf2rqx5
MapCpcSjmYVoVXzt4ka4G10H6++gDWw6CpfGSkfESk6Av52ZHnj8UedEmEh36AJY
xy07hx5vdRMzYoPHJNlV/o+1bSRyeVnltxazcz9Xz9XSsO9v1KJ/YtqP1t8EVAAJ
s8DgyI8uxybT5ht1f4phUchvlv9YO5g7LEcPxY92imWtTFPZgs3rbYvUMAAeDeD9
27tPMh+qszX/NT7ZwRZsFcrnOYZFNstctVn0ApEvVFlu3doRc1IA1pDVUFIrUSk2
+KZa3T9+IkLCQtpBAKjXzQxcq9hqRubQtm0G1hivUEVCKcHTY4BZ1Fo3HuxgOKO+
6Nl2dA+pF3fB8Idqz1lyCNm3DQvnesXKq+DiZX8wX7DkHyMYZ3J/dVWqWjVfCJfS
QAFQwS746QDAk1zRX+zDoyAof0svLtNSr9/PLDig1J83lBv5cCGaCoYw6Hv0Y/g3
EvojO8kOr0OhKKq8vy4URbA=
=un1y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ceffdc50-25ce-4ce3-a60d-6ba219cac305',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9G5TZJw0H0tIJxJh1/hinbcCsDlL98vADKjnGvOAw/tl1
C7DNXelraQIEVzQCP6OG2jZ2SdfIovuhHRK4w4qV4UEju+THcReY7cArH7HBKV1W
BVnFmjNGSWSXQVwK4cqABwNAdwJmlGarL9EEg2kUsjC3FNu7544D1ep0qHTcqvWF
Ssq939MQuILJXSmHgsWOmdRyaZ/tauLja/mvflVHFc3FHzT1br7UteHA+DtDpCUM
KPMXJVKa7lnisTvCivSg12+eJ1Z2giFUTYfeRxqAZWMo0ukiCKlsSsbH5wRVzmez
P6lmccQHYkNqpnB4jDKgntCwtf4qsxYQ3hi0KE17SNviW+AyFxKlRXtzwQCPlhB3
4QCFICxN1yMwtY96WEJ8AJYa87ZmY3ahzcIxf0+BSJ4DugXsFb8WJZRnk47+IGlA
nH8NvtdL3Uy6xzHZW2rX7QWOmNxj9dQeZfYNWQLqRYcgbK+cVlhA7sSBrYSSjGGd
58c/BGcA+Ij0Wg//WWvIZqxLuS+9UjLiqT87FZAgPKn9QQnkVBY0FkG7169dfGAS
AAu7Zh1HzVY2HDyoRpSOlOE6ZtB/1jts1s32LWNiIORSA+aamA2/xcMIVfQKRt9k
eg1CfldZFYyKsyfiLCcpKD3ShFfDx2uuqO1k0jX2N1EQtbdM61nDKYDfSotzpLTS
QgHb71Qo6hYo7xo5LcstHmpcOZHWK46+xPBYzQMQZ9MQcwuJmDn9eIFFqrtjcI7y
WRWDc5+4dM3FS2iQ6NFU+9OjPA==
=BbiE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf61721e-f2e0-4d41-a777-ce4e55d55a3f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Pm8K33XPH5ZE39sm7BC3xR6BQHp24XBiIN6LCaBi5oQX
N9a8mggMOAl1PR+DK7AsBmN/ZBZrbgNXfQt2V6qcKrlp/IdtMQVkoWbw013PYNgt
Im6MVr6add1r+6PhzNYnQI8BFGnnDeOpftrqtmURTfcIkft0bNuA/xajBiITyHMC
r5lUagPsCOtOrY3peB5tVMuH73yp/+secY+tcNLWEfKkqBYf5Rlw7CnzhUNerAO7
G++Yxfriv+lU+vUtYFOzMoGwOfrxCWUUOqBHMKqtEJyv9caGpeKn37kyq6WP3gqj
OmngXHMkzflEGTFQKwG+16qUBF14NXPmNsOT8Kiqojn4Dekpk0JiFAWPCPa4DBoM
So4TKOWIVz8lE6R3i2aSY9DmIO7ALFan7Ft6CHxR4vgClf2/UOV505rjrS+purKI
Ntj+F+g4/40pK1uY28Uu3/LuGRAxefw4zjv6zI17XSjDZyVxjESLMpQwT7n0rlt9
brJf90EdHVVRigbfyQEkUSxCtHbZnnoifEMPYURdOylRJH4r9gd76gp7AlJZONLV
sz7wJ9dxM9D0DuL81qasiwTCWwZC4cA6jBIOiILMU+3d/ILwZ57rkdiR+b8fwRHK
yN4CkliNh5hR9XTnegXpfIVUgofDtrniJA2u7hV2Uix4+tom13qE0grFvETIddLS
QwG1QK7p6YzIrh96BXJPPOYgVaRHI5p8fBpw1LWQ+9Hfq+UPE+JfptzfOH4fHlLR
ThgnYFQdDDyssXBAgeI68hoaXoY=
=5M5z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd122faa1-3577-4cc8-ae5b-645a50f7cbc5',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/5AcDWVoZp+IkEQ3clkI4iX9qqHFoNOLZPZ80RVgl9zFbY
LINlJXQMnOod+dyPoDSOz8PpgQtpXWESglDU0jTxY2dzL/tT/nylr/LFuMRM2+mK
lj3W9dgaK2xIaLPYfBl0BCjOj53g0GqN3+Ftzgu66HtO2RpZnX9GLbJieXxUCp3F
L/TDJzhmpc7PfpiV3mpXFcZJOF7JWGaQd3m81Hk4nKaL9YEBVQFRQEz5exQFESwK
7XlfDpfCv7pcwtowZS4Udr1NyzorCsKFZSpiXLaOcCPSi3c5snjQX0zikmN4MvkC
MNwSn50lvAW3D4/0dGWcpu/dK6JI6U7dkcufMU0pnPfyFbgQQVUx0xaItwGkA7Fc
vdmtMgomJzQTby33pbhc3vft98SAtSBYzRZf8i1otjt+PvAvqmdzOjX2hLHL0Xzh
fpJ1ZWAuAdbpS3mQ9BDouSsLZwWCBxXyWPPc1VKUwycm4GLN8m2i5NUrJ/xNULyE
T3+Z/nVsyTs3Petognz6CR4LURZ1+3O0yURGll3gPcNVfRFhZWXQUAXjbmt1bnDq
KYHKWV85CvxdVewLeR1Tmh2vztg6//ZstiNQfZeDovECYHenTH8yc+pFlsFdzBbc
jQc72HJiKXxjWD7s0suFVXBv/xLyUpN9suFRlDh2BM+KIFqpaU/Qtk26fV+ODbrS
QAF7yhZaCilj5C49fbZgnPasQBK6aCnBnDxZti2kcbgCE0NaCEdgwZEu07sPa0zV
+YbAa0YEyQEDaIKShqMSnxA=
=fLMX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd26a6363-d913-444b-a639-58ad9dfef78d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAhgh3zw6nrGrd82MQRpVreAhn4A3kbYRhfpH6AhABCFJz
F/HYibmB41RRuVoGP+UzrJzhCWnu5/+x2zqnfe6M5PgeJ389dwwrxmR21B8RYTvO
oOjsogpsYL9EJSxZVqG2oWADI4oSGH8K4QIPCbw+JQv9ESDuzQMFUmRWzX2BaLzw
iQKjY9/s4ylCeFsdJEdm5Az2eZV9oPM/+1N4/rZXpGcJLSfWQBkQy/cl0J2Ff2p9
nHJL5pKX0gdEbM3s16VwpVFky48Lb21gFogMopIBfWfYiEK1gYq0GdFR4ehDK2ZV
sHO1dT6plepnkr6YayABDxvEvMXcK1FoLkBoKtzCf9ntscBKy3ZyhwOdXZvhRy8z
kZoeMHrNQLVzlHXSsNQYveQCpyVa50jbUQNGIkXAgEQT4uNibNRxrbNfqq87gjmA
vnJfc2FLTNDq2e2LQi6I52ymVx3A9L3tlhOMQwNnHQPRt9WWj+544qBvPm00h37A
FQpzbk3iNd3LwbgoxjVXJ03BNmlwfI7VXoQgBRD9wcTPPyIql6Tx6vDQ/fHtyQHM
Frt4Hy8nhWVsTqqlGdm0UiXgyZUZUuxKLqHVgXNpPzWhEFyyBZQVSPyb5VNKEGgE
42RXPtCl3/5NzWieqDZvxsZ104acy+5QqxTG9/v9vaHmnftWTsgflhRdBYQy5sHS
QQH6Fu+toot6crXOro9iAaAXyeLxpMODiIvYHO8nA/eHLVflmYiDEKrf/eKd+hWg
Ui+JrKHd48Qrvu4lOwFvCwmb
=B3NC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd64b9a32-9f23-4cca-af73-2cd611745070',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/YEqu/N124u7zW2lLwsUlu0Zo6eqIJYgg9bhOwcR+abpq
Ru9AE7Bhlw/RU3e3+bQld8G3ggbFGkpVdA+ZN2d+SMSXKt/LwVUMDTLaBkVqfssM
f+MY0uPe0RzEZUv7mJ/0nc7WMIXQpOE8Oe5dP0ufChSTuK6x7IinGc8ZGUklAmx6
smkz8fiIGeGBKfmG28iFnNkLy8Y9sMSmpzHLgkRJDBGniuhjvvVnbwae4s9q683S
QeoFwPPbAES2Ynemzj7RpRoNrqnQtg0NizJlpYA9l/t6QnDWgtvi0rFJdSNnOAk4
lo0IgODcMYdlyLcESQD6CiqGOIsuhJKrTryiW9FgVNJAAWJBOBamO+eYX/VCIHTV
k0pcB2T05Q3GgRlrz1GMga9zUErSYeZ9lOoJM1I0o5jfGk8cymnrtUzvZyvPTCTY
fg==
=uSkE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd851c586-fd76-4959-a29c-fecef9804320',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf9HSZBY7jo5nbAiz28pJ7PYzjYOmM0/XJLOHwjNRDoGlsX
foWS7nPZK3FrtqPrKGH1oh/YN9pZuLafEA9C0KLo7NxnlPjo7JoAJDuWScvbkbZG
0yeS2sPEHiY7H2z4sxMU+3+dXL3ghtfdw2fvB7aEjJCvBQIG55ztVdiExTVeGOt3
AQ1ncD73gxiLum8YdQpGQI1LKW50XN+q/aMLWs/XejwakVD/0IDtwLZHWpZVFbzK
eQrqKTN6p1N/7srMvpqkjJobZqMMgt7LPlcWhs7Hp8uaJbbV4iF+sESkowKNM6fS
DtwC48vWv8zduYXgpIGutMRj/RkPscxZY55fuCp6MtI9AeHFvCknV5nTWStks+1W
WzUb2PrnO86vGaMPGsHWIVlR2VbzljX5+uhfW3QLgzGPSQZ17UDBOIcSlxNU8A==
=nuzw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd8e86573-072a-4d38-aa22-c419859c2d5a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAo8zE+HYAZi0HikkmSNeOLwduh8o+0kwXWFaqox5rdNW4
r5fWlVtHGL2aV7wFXaQbTRhzmyCeJU7VzeqLcqGBnWS45LPbJ3MNg9emMMpZotOH
KMd/q4A+Ba521lzP9uoYV2kPU0lUkxRfKxRFhZXLVPTJvZsc0DheWvxCxRF06LoQ
DXC4fdUl/MNFRWqAT4SwODWb3g4QVHSLKdFrw4QhtVN1lg9vh9cWxXUAPefda1KC
aQ2knk98pYr5u3dL9KbqpZtSbMdTHH4QO4Ag2vR4n7PwKAnVlbXc4VkOyYfMdVg+
yy5ya2UASZYR+zSE5H1KKvd3AYYMWBBe58TjmAzNRCLPc2FlTeAU+Cs6egQ3HpBK
C9c15PtTyMG9MlUOWfhfVESBZ//Lsrt0mpHK/Ec2/axXKzAmUifQL47bT9svenwm
RIwhF9uIqGSlNvfRaJ/8fPjUFwGO/ZlCV16PGlrTxWDIfie5QYi+XKYvjca5Hk9f
SL7yLwo79l/LTxjS2fwlNUKlkwzjE6R44mk0j/MFx2VF9M2iNKwxlvg7mSmMcG0i
J+sQ9a/ks9PVQxMSCR+3qHZSrCzZm1cJftjL8KvEUIMS0wnASWoluyfxQL4vqvsb
9cfchDIGff5rAjcvt0QdiNZ29onPnolVfxUsu22A6XVe3pyDWKqUIrBn8u3w6JvS
QQFBqOcSCfDNbwfzyehb3YV8RT7n7gZccYroHhs5SRYYOpawHoRG+4H4xSahhZwn
bhT3yBVDdFVZsYtyZRIhN8WY
=F/AK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dbcf4c14-c179-408a-ae31-73f7724f17e5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Ts/ZRba+ulsoVmIYqgVluX6tIbinvP5Y4pZZbzGbCast
Q/O7GXKk6H7sB1Y2J9ynn2v3n7Bw6xZ3gS/2pzrMCe2tekG32/Vu8RWNp47h/nRN
lH/uupKpcecpQ7M0pLA549SwErmk7QIrZmrCAK5ClkGT0XcUVo861xDKDOY0rlm0
DGw32QYL6JtT58ZFrBdh4I9C2lrWbm0UpXROODL2qIaS6nH58DYRt2Wj+0zGVrXo
6P5aJrV2VOyq2mAuEhxQSOI9OzYuwqr9m5UwQkaHoZimHvw9o+KBGeYSEgBJivH2
AaQ8Ru6/AKC7F8dbSSHfdOQAjhCGVqqDnu3aOfqwrEV0nZN6H9R85e/M4XIIgsM/
XAujAv69DLa5L48u+cxAIwUquuX0mqaBrsJuHv95J+DMIcpfKSPsnKPs6UuOSwlj
LisxrWHE5Fv2pdzJ/bFq138gND1FI+XnwDy+dByBGoZfc1hlo87f7qzJVing5k6v
Tbdi7b17iBoL/gpbjAMMyYQ7IsdqR8WWuT4SZ53Q8VeYTAQoXHbMlkmBA6zoHDql
85GZn8lVbnzUNBcS8Idyv44pGH8ErTPj54lhDuzLRVGyU6Kn0weoITsVObqGFD5n
M+3Cfow4yHfxjpvx2Knev/wwuw++hZpR+oyCnaHaTBBuacj7dtoxN8xbzH3ABBrS
QgEVDlwhiZQ9l9qfav8V9VtMpPUpBp4W/47jb+Ybi9a13b1Sk4IQEIOFr/GiHj72
GTulphY2vVFvCqiFgFB2DCjmrQ==
=S12F
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dd00babb-f368-4530-a00e-fd9b6559018f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAtJRGyrRwIYob0BxFyViITGoybEnvkyalvFCCDj3Dcrk0
XYoTCEICe7ohdm/bpgiVHlNKNkp0OUGguw18NAW38PDhFWP51eCKdwkZSJUt68DB
O6CbUOUzxk0BFQuZFjdmh600/F3sqkUhxs16M/s+BQXiwHxSpyAN7+7qmkx33E41
z61CmpmcuFWxLp3FYcNOzp1iINRRyMOnGaGg+KMpuFPXz7ONwqNVI7QN+deuXjBZ
jpTCQHmjw62PxuzfuEmLPt86Scoe+wiF6NRn7rVi5EJPJZn2hFgELpVEVX7YFNG5
J5C8/tSoYVDmfVxulZE/r7jMgPfyOs6AX8npP4suUzEXaXEGeyvvDGc1A6BAQg11
FWTTeOUpiNZHVTHutTsS9UlR4Y5cmcwiuBsF2wLmT+teY+ofqde9ipHSZR0hDIkZ
7pLuISFBQ22D4InR4L4aUk8mQ+F9AgBuCY1jxIy9YSqR9zZdkWAXammZU2zMbnQf
OQRtSWLGJiGeZ7BC5By87XCOU4MYWf9UF/k6LOcvL/icwB01yXPiTTG3iEIxVL2E
vjpUmJ2AokKTs/nf7K35goGL6zDS5tTsIugFfgogMetlhRpZe57P1Mdd0/6aF+r5
w7C6q/rD/fzBOsZp0fqP0JRL5ymqDLCB2fdRiB7go1DMFXY7dRNr5z19jQYj/1bS
QwFPxupSkJyRAPRPLiuZ1xSI6pJV+3FjVaEaGoNxDyFc0JgQJukmQDNLpXPWhInW
p09LVdm+1LDuxq9YBr1+SbS1M3E=
=ck4q
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ec1bd0ba-d8a0-4562-aecd-c8c3b0edc324',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9G5v+dmnhbOSmdYQmLM7b9ZMQoNENxBsiHYJhzB3Np6Ea
idemtb4dq0qR7jEFr3Lk1/bC4b0bEgKNNM98D04wfGVyNES2Guf2KuSpYMDPFqJC
Eq6woRqgQXl9zpMCzna9Fhk987oie31Sq8bEmKS6MbFuwyD+xdrYV96MvOoa9wvo
pemY2622gMvZgeanLesxVP98nMw58qTmyy1/iMvT8bamyAQAwY8rkbXoFK40b9Xo
Z6Is91o8QAriS/9YdFJdkqa/hpRoKnr/V22SJ6FGUxVI606cg6qrUFYXiN/idMgQ
ZFI3Yepxy9QWsHmfgbF2O0+FKCK1dxrvaMZCw0oaS+VVSEgZkcDQzYPB+rCNnawr
AFNO+SSnPO/59E3GOIAApdN3LJNeaa+OYF3YQ9ZJ9pF1oDwwvxZ7xmB8P3rv828c
ecKQD6MfArHd7GvxNtoWNzAJ5zoeGeFSZO+9Y3BsT2UWXKanFnjBy895suajaStv
gzwTJ7bw9khhu2cMtxFAWPTff8ELT19EaJDndwWGP5hjl+0Xjpa1d6qrIofJdSFj
QpzM3vidpNpsFyyQ9AOsHdh7w+2Qdrwk2LHKToyF5Adn5GcFZNJ34YYwbXDRwnYm
0n7Je8dZwhCUA2XejUZ5p+oeUMRXtwu42uJxGXcApJSFj/oTrJo2q7z0t0jXyeLS
QwGJeoE+1Ck0KEnscQA9Up76lIiSK5fYX98Xd7Pm7vvXnY1thpNVlbVaA1IFg7d4
E/DRHwrL+X2AM0f46GCrsxWToIE=
=oaPa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'efb7eb7a-752f-4532-a04f-640a30d7bd76',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//VRvfi5x7J7jg/aRSv4rBp/o8kCMoRRdj2Bfnu3sUKr2e
ZNF3LkNHEMvmrKCQjAvoTO8xMlFQxGuS2C3UbYb9BzMsJmYXRWj6tY9DcyR7c2Xz
ehCiI7YEy8jfjSFcQ2qX54x5ERE4M+7wgPwbCbvtcMrlgZz7NSauKNuGk87facQF
18n2R0RbtFUXK8izA4WiHlwGS8MRk47eKxnkSUZPv+WkCIMtvySOBV1cJbP01aVE
qL+b3goF/ggktBQdmo0zI3hwXI1DsRJiUqNjj5+H+9+c1ba08UUMeuFVbKNYJs1X
YFiDpEDXiBqEzmZhYN/vDwfxKzN2wbxvW/k2VXbu38Ihbw1GPAP9DYA4ng2CANxV
fw6Qc2z5u1mzzl8UD69dxWxVYKZoTx6+z9Megiipg4cFKMy+bO3KugQMctiaibQC
FpQpzvGOjTtL5fyEZ8w2hR333xcGn+yhwFLDPZmhrqlvzxhdQQ8Pr/1FtiGB+vAu
iau9wn4Q35wHGMAC7aFzeVMgFdKWDAd4NSyuL2Ej9mKAQf4tTxxJK8PHeHIgI37n
3dSrvNN+cOu7rg5TB4CJ0PM+s8VVBSz7RlncpwN8BiXlZ8HY1pm3YozuaNi+rKUg
H+1Upv63afhADfXQtA5wWS3kAy/LUA8jnU/bddszXT6kUZojf0U3HQSHYGlLC7bS
PQGwy4AjLswhj4j6HT47xBWOHq7sP0vKN2sJymyJhZwiwhvWFuAfhUDeQWFjrSAg
hQ4MfuZg9g8EnHKPrmU=
=H19a
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
