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
			'id' => '020be7af-1baf-4f60-a863-dc26fbdbd23f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Scfc+2buZFh5VXHofifymbDq28cioBDfwWzGtc6EKEk2
vnjv8aY/JRcZx3VGZtSMdyCgNAolKW1R5KFzZkeci/wOlO3uKeUs4U2vBcXLWmMs
TFmA/OfW2dHeqyvcQdSsUmbKPjI1QZyOxKyKN0VAShlm2x8CuTinmTUVMyYuYKDN
xSlmf2PNb3hph+8yKfUtHmhVcvQsU7BwWSh8iSPH55WUcfev92gqPKw1+mFsfivM
bm6CZ0Zt0LC+HXTs/zTYhPkaLK4gKUGpMqzvdeIrUg4N6qrPsuZmHzRO8+F2IqmF
jJ2XQfFRHcy/NO09xfCxj+Olb3H0gBnHv5y6e9/f1CMW70/i2J6pUzTxJdk5DsMP
orrNA1CmJHNPXPbhR4QuAbApk8pyhP3H9EolT5PIfpBIbxe5v7Mvoi1HCepJQg+L
c5GgyphlxPz9RblTFPKIKUxHttp/xuV0cKtdxPCI9Sw+6KVdxjgsRDKE88rli2ts
4AcNOW2PwxcXA2TPjNYLm3iaowUuZUASlL42XR+6vrl+rEPIa8lMZctVm6axAJDc
PxtURI6i9SSBJkl6zrbHZY+mDA78ZI9AqIQgdT5btH9bKJr7irJ2Mb+8UIL4THgI
4I8GS5shfe3c3xxWeFS/PaST+cqsa+OYxFntERiy6jQr4ER2gIIRKjX45Mnc4dXS
QwE1DAuYvlDgJwPHHK/b+Ctz1O+zvU0fUNNGsefd26Jwu4wEByzOX/TeA8Wk9eth
rLBkv0frGNRj1QpP2a6H1Phw7KI=
=KJ8O
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '022ba058-f108-4b44-adab-0fafc98be069',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvnDN2vWUh4R5+bGm2Ocyev7Mvfl4rP345jfZmdmVO7UR
kk+V1y2v3bif+brgTT6esVoo/KUROh2pkDoKnhmgFy5hrQjT9FOVYBFnfjuGRECv
kqhNV2o0v6yMC0rXn3hihCoYZj5lY2rggpYXCSvctQk+de0qMUKkRPQN9bDHs7Q1
D64e3QIF6I7fQA+WtXmU/+6OR8mHR+smSceaXuPNKUWKVMLwjJNFOxE3R+pbanFi
2KFtq/lda0LNV+90+JtF9y4j/8o/N+Q9MVMCMwZYVOzibKa7aSGgZnvbNEuWjRGs
1xzjDoS2b4qNwGTY+GRBQJwf+Ec9EE++agjPAJE9hvA2qpkFwVDfJoFUuWM2aVxI
8h0aBXmBvWYH/dOfTMn9xM/E+ISlhTVvGkBEtT2JPhod/1IABmOg176lHzLUgXQD
d/DR1c5ac0pI4meOJGnW7MvrFke+nbcy95aofnPzH6L3OYk/rzsAkmXSJBPn+m/i
vdNKJDTayH5ADE+RBQu2LaMf4bUN/ji9TSTMUrultQeSKLCJ71jDYyoMc3tnhLY7
nh5FM3k0qFY5mSHJ3qZe//UryI9x3TKkzI5qRO8YZ6tQqXUvsxK4M7vyE9fHPlS5
YfCsEn7fuI6vqXRSoN0X30I63XlMq3IkHlJDaIcjPkkAeVqTh4BGXdw3mFa5EyfS
QwGg5fYqWSY1sB2MF+pqQE57ZicPA2mnL4SZYsBp6FJp+Eheeub7QUGq+y82177C
4qV5LxgGDMdYiobxxoPDH+BqZmk=
=S6Z9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '04b09579-de5d-4f70-ac85-a5b8f5ae0e2e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+OzZs6UL/H1OF35dWON4N8HjE7Oqr4Rzv6IyKgb9NO9Dn
wR7YaPAcxHPwksf8YIeyfxH/yeN22te/H2QNnZnPen+wR7E25GHhcE3iRPqRZXCo
CFJQx4VJvQtV2kK6lgqax/bPzcqc3tKPm3nLhbXfiAdhZvHeDR/SaYxFIxLlDh+j
W2CHOD+Ympdo+o1+/rnd/QFI3dBG0Kh9n98u178EGxntIZ6yUUkMHzayix9zu2GK
hitP8slC07v22DsHnhpF+K+GXiR+mMhTYFXoI+IrkyMIQDtVxPdWLaxDJ8Dv5DCu
ndGyy9Vkel6Zpxz5PTrNZEo4j0dw9zET0GRm+DpXK8h+ADMXUQNMWIw9bWx2zWLL
9tyBtUFTOSVrOwUkzudef7m4Xdpvp3tczxIU1c6DMtc8eD6hfsdp8G12VSiflh+f
4cVHogAAC8UY4rADn68A+5yNmNo9jWS7aR1kniZYz0INPXUNX2SmX1/4W0rII1lE
VQ3wUd3A6/+uQwqj2PZbjrIneDhPYR88cVL2PO4xr0z46XUomlAmfZ7usgMol+bL
Lu+IC4laAzOK2MqGp+GoUVnMS/roxTbgzgK7tFJpwwWsAQ37AMzGF7BQIa0b+RX+
qHG3Gw5SEPWvZz0iA+43tcpYUDwePuwyZ6guQl/IVg5U5qawPapaFix1IxlM7prS
QgFmeF7mOJcoaIj3TQ91NPfJwfDBC0jbU/Ziu41FRnj8GrjKi255Mlj1IjFwwMG9
MUhz5y4jhlQQEgcG1gBwkKzS4A==
=mNuq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '10526640-1747-49e2-ad38-0a4def2213b3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8CP5tDWbX1nb8juixEOAIxPHpveo5TJKSCxpWN+D3tE5F
eik2sBBd61dgj0WzcXAnMyWuwsFczAQJZQ4PNJ8TreoP1yi7coCdcOSjLV+ZRt50
vl9LadYEsCM52ZxMT7oE22l41l0gBLbHAjfBYvAVHnZcf/K9sOdYWKpGuxhkHK4K
YlYiUmNRYsvgbs8/Fp+gzbW6RGG0fAZTUACmUCKa6f0osXZhFGs7jKVFXzgcVs7D
qgmv3zaibCuQ/ONp55tfqiQeb4kkTW61g85ydnDDbC8Ig4kByndVvZTtVRBShNzC
7sf0O89inJOXOfcg1I9kPrz3U6CGQjPGttyszWWJeRwdDsD/RhTcQMm2wu+9prE5
ybbDYrdW4NG8kG4DgKDBV8jxjyup+v82GXRKG4iFRDHUPqU4AtGSunYPgMkqGHAr
5XQOFVENhbwHwyBMtKgZ5Vp6ezDpw6jdxQmgQAeRyBBobR0pMB1VKHlUIPkmWJ2I
OiEm98XD+YxIozpfwKvABMxQE9oNHgaXcw5h9BKhE8SNYC+rz5e35Kafj8L9nIwo
/hdFHsqHW6G6kjELev6WMQ0mCeZPkB9lv3+UVi0Jqsijl7ehgPFXyjWYDHGJRKki
CHDgHkzbRQSK9tuwl+8/PEtd+GwyQjUWfbQpFsfwiYE0cJU382Q4DMh6HpP9+ILS
QAEcxKRADK7h9i9mV6RAQXsFWEgz65RmUuHwcWUQKHfIaPLA7TQhhmQOJ0JcRCzL
SUQBMvjiU7DclRhm94byDEA=
=7DGk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1e8e100e-2f18-419c-ab7e-da9bca645e85',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAApgKewvAkQd5oPo0Avyu+8v/+NgdbYMrXNb5gZUDjVmN5
Ju4iVGreFMuMmw1ttmgUCyNMP4KI/map2zeoWZVJCwBLodi3NbyzOan4yQUtMAt0
eVXRi0ClWhUOXupYMIPV5FSzU4cXn6+ls6W8/V+SKsC2ATasOG9X+fd9zpUmvXbh
PXTxtjfp21TFwFa5dkLajxWYtiXDQUw+pOnAKBUj2N2OERmZvKppn/RG3ylLMkno
ZeVMJgaApi3+Wdl7dhvP5pBBQJRhF3wwZfpihNdUMhHQIcm3tUv0qp3lwt3gRM3t
9oECXHIpciCDve1rZGL5mfs0TVKu1YWOJJSh2V36cC6PY/eaNyviLg0GY1+JD21f
Ee/BHggqPg+UQw3cFUz+0OPnaR+Sq3oypyG3BywKyvhS0B5xch3GdkhUjKCNOjpV
sXPrValRDVolw53Y6KUolLXpWQ4TYq1kTbpPnLIAoWQz2SuOgbnhlOzL0nGtlRrK
rPRtbR4Bw2MCZQ1O16quWCHsBGHLixWS2TzshgjbqzDLUhPDyxsq4FkLsqq+bIVL
9GhUhHL1lgRl5GUy3K/O84yPjyx5pwIMtO3B5x8+VB/4ij35MGJpwn0deSeWXwQX
lv/dXBEELfVNcsN9UUMMAiNf+GBz+NUyL7zmDdj+FytXSJhds9/0dQ65D5aynLnS
QQGS16bSfF/Kkr8P3pdrQhNe5VQTDQHL/td3AjQicGRO8DWI3EluMLH9v3Ea6ob/
RRljTe1L/3pNW0mmvjRIDLgL
=0gPc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '22497b09-1ba9-4e4d-a9dc-ab09f17927c2',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAmDrDn0GPvCzDZ+vrR3D5JCJ9rkkusk39HBAyI9fRUNr9
vFS6rWCm7cyD2nb+IG1FykzHLZORKUqc3jRTprK6UYm1rKXKBvV7m/vTkDjnjl9N
dql6tE7jZYaNVFMLw5tvPKwEOVgAlmE/A2YIp5hEcK2X0bs9Xkzb9mbDi2SX1KW+
1PUqraP8+mgzgOXbmxlxRD696O7nixJj3tnetjx2FB7un+iJCA5SxCFlK6Z70fNP
SA1LJI7GiBhZUpKAp1NU9fPWK+ikYLxKGTNKUbrXVOzSCh7pEEkMzGA/qwVPe+CH
efCI4+IeCs4HsHHTfUwLT+ahTaREjPNXnNbL7eC2ZhS5YGYWvRcuKzbIrHJsffo3
rqHEjzzT9scnsx695ZrDCdZsRdRvUycNbRxp9IjzAmPHDEBlsK1X43GQ9bLgG9Kb
/RgolO3Kmqdu8vR3CFWCp6Io7E6hx35ahq2R0KwK2IV+kPRiGBAk/Au0DXwv4Oq5
OtJkrpB7JbzmdgkJk/vRq8pwnXDbKfgIdxJoo7iOwNtMon2isG2ZlNn/Nhaf0snT
AJ2BxbJH9uSTjx2lDdTl6kg0ngWSIZbv5n8nxVwYH5SW8cm5+MjE1agqCky+GqUW
TxtTC8BwuEem/2NKGtoowwJvxT+Twq8Q85iNjcNRRoA8/9Y2stf9W+16j5YrtmvS
QQEpQX1dzxpHF2Rlt5A3wIZvKu/7oL6gqvy79Wge4XSvzWARfWPXe6/V06/4mpJz
LmgD5LwHS9EgtzKWZSKuBQgP
=ygCM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2302fac5-8ed5-411f-a266-2c58f2bc8ad8',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAn2+odqqB83xRQMbQHkWuN6F/iks8Ae3NaYgT+Q99CCZO
333wFTieKjawNCkqfQllhY/9l5bwIG2sMLiNjl+JBOiVA4H77xXllrMpceACAG4s
36SKyAweTbjJ8/9Ar4ilgdHLq/ZjwwuebfgJeSUVZIbtI+LeuJOUaXKmA4wIMfce
1tnQEKLQZa6O1EeYhZMgwi+U+0mvk/wboJP1JexivIWxzk9BskMMEglcmOhLIxf0
aHrmeIqHGM9bAF9Erzs698uNRdGLJJorQ6VukAZNGBfEqHpbYYR3U4N/PPFOyps9
E1zNbIkYc0/HF7lKOjmmDi/MvPfyX2Sz+K5ZfYNFxtJBAaM1/KTjJHnN4IeGDXQy
nsJJqks0VmGObkDGfkYMO/RPYg5uHQpfR+DcMJvN7ewW6N5dVThpcMt3cJmDHsam
MAI=
=bx/R
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '23850a4f-0d9e-4acf-a1e5-4c21df4307d8',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//c0gLtNkjoWYGrlSXxwiH29QHHIjvqWuEX3RbMsn3lB3+
Q/mH+W5QzrRRea1iwlrCtTwqcnjCTywadSsEJb+TxOaGEexFL1sWqMlSe/8QKn+t
dfiHg68HYYJ2ZE2ZmApuc7/nQJsGA4aQZSWOsk84VeCVM7yXj5df/N5DJOFRAbtx
elC5fbtzis0lHQI0PAPDHVaLjji4oJAjsN/kCc79K67dSYMUgqWquSJRClJPxnlY
L3ySY1br/MORCiB0N2L5zg3UDjYhz/pWPgalctG/YZSQjHn0okjrsJcCN5fv4E1T
TUlUcAtCRW63/FoEBuzXbm56jFEKDmOIuCDxq+8q5OXO8YV9GnsdxvINmwrQ6BUy
qo+LbmmMKt3MgyUEpD/fbP3gPKrpcZxmtXy+Stt4dEktBLrp/lHso8BuWeM48XyP
4a8hQPPqrVwDW2V2c/39I7KlHszJ+KcNJFWBzxHq96kqYqlra6sqdJizWXkfnm+i
V9jYjgraQ1CvxPW+5ToRd+Y1Zz4A8VtZCGLYICVXieXZIJxkoKS/EfrThHCIzcaf
qMEnFWt2JZdpmbkFDCbS8ZWkqM3Xn99ytdzXG2vmue3T738enmVR1KbLD0WLiqgC
YnZgOVLzkuoB8KMwSsZMRlo7o+JinzQ8sKrTa3YTIg8FDlvkf0kIWgRMGpqsi9nS
PgFgxjIvzI0ucBwZ22ZlmAAnYgu2JhDXhrFTCVErtsxpyjxMRDLk0LhQYHNEGUso
1yTIPyF6L7wSgXXVhQdr
=DHp3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2389948b-92dc-4e42-a158-2b2d650f3792',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+I/7dstxmAdXaXH0cv3+dhor5ZImFctAcveyYaLmO4z3S
cEUzc0uLnNqxPrBhUbIS7n5RfmV/PgdLSeqwlLK/CWFcC5XnP4h3Xfuq/dlGSUa9
06Jv6ZJGKXGyh/Wa7rkTq+k9752uq9hKqYbsFheKcEE2ZuAH23BG0rI9qzpMj7Tv
mb/6pF4CYovEfTi3Pga71N2O0DczmmrYR5i0cE9j06Dtiv1dijMt/pDYfhEx71/H
kXDB8AKcjMFsHt1Hi8ql2SQeWuAtxDkjzNTDx/ILryESZ3gJ/kIIFoHALWCy3kyi
R/Xn3q0Y7ISvdi7yybfDxd3ulKrkF9TyuJ8rcNjjIP8qjwFo2Avj+BW2DAlHyOpG
HEEJVP5xWF9djshUoNmaDPXCUXOhZCTshCqC1sATzLManBXiYcU+zqoxjzqnhpb2
a8rc7SPorPJVrTyjomaMm1558JeoXcvnEHgwU762c8mSmeCtnMSk2y9DSACt1alH
h04oiULlHwKEPHhEh23MQ3LOFbeLlBANdy/t30VHdxSkgjMiz2jrVP92B8g+v34O
igDNFvQ58mu03O9y0hYIQPHBguyxA4qeuTpMPs3K4oBWBdIQYP9/dXbmJj0jDJSH
IKHh8tHZtHcXIe6YieofPe6PEfRHrmGHsGg1WcjLovYQ44zkdoczw0ESBwt8KcPS
QwF4xXeDQD1kEeq/wJhaqpNNAxDkVsC2wXAm1OG64pqgl6rvdiETwKvNhiy7+QaG
o+Xs3ekb4U57nJmLcTdbgOp/+/0=
=XIzd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3258ac70-3ebf-44f2-a777-53542a70f597',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//e91nXq5EsV/toNUsPm8WJ6w6+kTSw/rL48SLX/VeHQjk
e9iHTLZjFVRkBfF1a0GrOXQmCqGDF8vY83XTVUBtQsxnP8W8pvr2qH2bwASU+RWt
+WkEpSiGNBChwEBFr8COBz76Lm9ot1+0gWJHpGk3uqFzbBLsZcGlhN5q8ggju5vI
fafkgJm6KyMhksVjI+yGyewG18jzxORLQYfvPJpbwRpD+/wR2f5tX1DXRsn5qgKS
v0fyuvFNiKG0ituOaE1TGUjZuWGZV4NZ28Pabo3upUzW+0XvRChUgDA8w796HDQX
6zTIh4mgM8LWc9qqlFbP/gnsOaPGVXFymKp1H3PuumMQtUbaiu/5YojOx96m9uoC
SjhHRzY36Y5TuCjwpM2owB0JqKYWf3qReZ8dED3AN8MUeTnXD+XcY62c2MvPI4jo
pWZm0LQJUPtuhidumiB4dJZdtjeDbuZ9quRtb4Tz18738fgBg4PgR9oFkOOwFSmy
T+k6nV4795zPmjtz3u+0Nn225q1jPa7USvxC3/c6DXO5t/dF1yyToiM1BtMUWmrX
+Bzu1NO4YBnyVpMqwsFsv+CNMj4YqxGA7zmV1EYqfvBNdZeMxaRYtdZHm7j+HCya
FBRFwZP9j+4ILEq0Yu/WPfCXLCrhLJPtomn0rCPeo72tdV9gLKhBVv79krPDUFfS
QQFVkhmmLCDbdaB7SKpIGwcsvmbODv9nQX7X+5X6Q6Q5OzMrsLCFv/bhDh8pZQgP
vBCn/o0wAMXm8tV6qWpiesCk
=KcP3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '377f1006-4203-4746-ad12-9b7740002b3a',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAp+xs1IBu3FKz1tEHrarsf4/PqYhIOTo/tInLYvrnybeR
W07eVPp+cd9xHCy9yk8KJxw7osV/mdTkwoV9w/H4GJi3N3B1lyv/SGxQrGyIo+xG
y15Ib65mb9CSeaRN3DHUWgIWsRjuCVOO3cOGq7cWzjjBdf8kxfxU5u3tsIOoc/on
JpkTmU9Cz3RjMopyR1wSN+z80d9gRWZYfV1SSG70tltZsUOiOoDcnHVawwwMlJS0
/UBgiOh2n0bdqae5wBOdXwphdexY2+aBjbQ9jb/N/Krs0tC23c6fN+opgHdI29Ft
YwS7VTAHc+UnfX4FDsOjTPJmx4SSUmaVBwbyUPYMUdJBAXdi8WQhwK7Occ0xuwSa
DAivqfh/p5f0XdX+VOc9oixH1O+gD02ACGLYaNrdGI+KAVp61IsnjjSaJ7cJtFvg
u1A=
=qQhf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3d09ca07-3713-45f6-ae27-abd3f56b5fac',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+O8l5/gauYPrciX4dKKAjUkRDjE2XdDJ6S3xatvyH//fS
lWdohyW0sKKpAhBp/cnL/vlhnGggH5LX1Qy7qiGtJvWh5iQo+jii/EdLtmRrnVgs
E40jdmHWZcN+1gMBCi55zlhFr37PTDZnHwLxKoPuhwWRod2c8JIlBUHEkKyk/3t+
xd7N0DciJZuo8snlVknuqRSFhDLuLdEdXyJiQg2lCKSsqOGn6ZbQwmXVbS95xcxx
uM1skWwm6ZSDw+ktAqKxgsBCoKOnmPzIgLeN43ywOIVijKS2C9aoq/Z9dlWlOexi
7c6LdBUwofNMkxF5r/MjGsgNLYqTuknzToebijXVzsI4+5OVLdfm26VB7Ni4iJN+
oAFrv3HU43/sa3qkNVRuRZPUDwGDWYz4vDkU4oliLzv7am9NBvnHYjdyFD6GH16z
ecR7zKVwGwfm1qtKsHYcX51RChuHAM/xIZwUe45K2DkyR7/iM0VFr3g/4jQy5xx/
xsrXHoJZ9QYsTBT8x004ZkwxmfthTeLbuv2qWF35GRqd3Od2CloqFEtwZapeBjqD
KftJs7WxtUJkqpLmNCqzeck3MdbLUyycfbTPgvxQCqIjoUo0Dtd7pFNxaiqsIOli
6jnhxA50A9R5olz6JnfdNx76JqTQ/w95DYCMTpU/jY+54TY7UcTiOFgO4ap9y0fS
QwGW91xrXwebAqjWMkHRiBWg3cITTzLNIInS4N8WXhpSpGlI+tRRSN/XBwmSz9ii
yYwGSSzmiWA2/pPfie7td0cXzIk=
=obWK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '420b0cbf-7c88-4238-ae4c-ba50c2bcf35c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//YUK2SLEr9QCkO0dL24TpoOYFpIID5MCNKxKrlcWU09bQ
1wRXx945XC+DfuTUnWE2RhqG21ZiludRYoepsyOP04QULrMJd42Q5lNCCCPfhHoQ
LXcnLtLQMp4JWQM9lGEhxi4hekJRm24Nk5lPHBb4u2R5P3aINkfGT0y1bntuCYFA
9iXZWbFpStToQC1sIqy35cw19ImcA140Y41ge49prPCj67iao54JuqRvf1hf/Lbo
kIMBjKehUHiy6Vqk+DGebxdqGUim87CCDWf5ME8Ckx+NWjuqpAvqZ1PzpefriwEV
Sn49QZK2DrWRgb6rJveQJvSLEFBdh86og3HmoNHJl4/NRybwmg6ZlV8zbnGMsXW8
33XfQP2DyMZ0PovxwnndiwaQYWnlpAU8r/vcEK4oIWIa3rG12i8ws0toUmdIMLIG
EVKBBwjrua2jIub0vrx24D3ldbTx4JXhOcae9xGzPpHkJMhmJJOOjzzUfCEUzC+A
tY0HIYGNda/x85fGnKnCLodRZLygKJRBmOTwvuP0KaTuUV9lBgOLQVENgd3gSxqZ
PHou9BIu97GbuQHITWZVFqMnMXbDIrjrchKGm5y+sDJZYuryMiCghtNxumnN6bEO
4x2CnyiZpjXbifbk6the5KBoFa2XoGzZmK3+F30Q2uKU+ND6RLpROk2T9dl6bdTS
PgFrCJrk/uQLTd/vMx95WtFktYvxiaZZAknLufiVkjnz7OtjBvmRvxu5jw3LrzsZ
8mRvnT/ZggddlJL8fPYg
=Iz1j
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4325843f-5b80-445e-ab60-ce598af3e325',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf9GQbqDFLDfXJwcY3mrwB34Ovv74QRZqn7gvw38wQSEFVw
IN9zXyOHAXvvNdkrjekqZu/bjeALaYKzZThNF/ZrEAGBY6AGqsPg6sYjn+J/JzQl
KClqcbmPHrUcWVSRmT/eivZb3jsCW7AWY9anl65e/8heaocS9Nlex2NQe/U0aLAP
P7RHwzPe7iIgczTL4La1XSiVUSOLHDo7GU1D7QdWyxZsYvh3LoO74eYcWUhO5TBH
rKMRepYr+aemFtC7TrjqrpsMxoyfFNHA00owkixtVBC6wd9I6pcgogmMN6zGjofz
W8Wkcf3bzhEd87TxYGwyiTmyeE7lzgpUQY+JAG0N2tJBAT6AyeyjX+hgImijnjIX
Si+Tjmqscjn5mndEpnwLznD5OMuk2OznYa2kuUUtLey4/aUSidlj1ljFAEguYvfO
rLc=
=RApG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '440234da-8505-422c-a55e-1b0602746ac7',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsCpOFjAh8YlOqAcwbIvZuYuU2hKs8qW+iz1D5EjNohbD
k0VfRVh7TeRAlO3GZqXbtIIGCHkzAmYtm3SukTniX6LsXTVI+O7d3Fv3ahhT4HYf
w8Z1eA7U9rNAWAs3qpvBD5uElfTnDkrG/eWqcSdu0XS7ZTwS5jGrRdUvEzjfi86K
2WAFixn7Uk2WlGy9o/uA9zCShFkaYtpn0U/XY06SSB6DWnwvLWehyMOdtf6tv2q7
1s3PP8VIgLsEkNc5ulWz/OS8H+jNwZ1OvNFQ49NZoHndEg3tAIQJNONjN9sI/c25
oJ10RCC/0YtnJfO3gRrddCfTo1wGmwqY92KBvbZR3VVJLzGQ6+i/qd9byiq+zBbv
5Bl+KEuWm929cmvWyUNHAkMRC329IVGczZ5wliLRvLpCMOej79riJqg3KxN8noyC
dsBuFfj8/UUYaoebjJLQogk3wqkjL6O0dsORTzOuTEGFtIw1JO6n15IKRUZUAqDy
z/dox3swD3F77EaALwlaXZEcE2ALuLr5XT0ckBynpRHLAG2s6jwmooZniRqfJgfU
frkaa+/nuiZliyYymd1H/FNnz53Rckxm9UiigICGCl9erOhVpJ/G5YyA5Kiz6g8o
kZ5c5dhcf71BueFUYNv8CbAUw9HtAz7vSLofU9QBU5ZQ1Fmoo86dgqfLepi8mYbS
QwHEoVlYOuMLnWxps2SF6Rj72fUsrSO1/yfbMXsQvaZVOTq1riYyKPrCBgVhjiVI
tDrWyQYzpJ1ZYhnW1joLAEbEZgQ=
=H4UZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '460381be-37c8-42f6-a3e1-f58830b3147f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Q05WZXvF92N6fglr0EyhQYLzuiMUxMx/1KxVbGAlkDNg
h3Da9KaHQhz2f4gE3LNLjnBnNAUBOxg715dKVWH0MSQ4z0P2CGhsugTj/bmXYry6
Jtty5v1UsmgcUnvPKd4gMme9CON66x1oIl2Fo8iwxl8pCVxTHF/FkP4uP5pHRmsH
wacJTlOQuNhxyjm/t7cVFS3ejzR+p/FczYlsj2doEplKH2Imy49dFpA/ylYSzn7e
eXNQNdG1xlAJ0BBrdexmaGHQxeqCAsG8AG/IyzUjOVxxEbpx2Ht7XdJEx7ijTnOl
nlumE+xe3IHWRGhL5iDawGCN4UkQE6tE9Mh6RUqEhlWVlFFQIiuQ3mfRpWRRr5Kn
LTwEUjjcOI9f+squsV0n8fD2NHkKijT+9nRu41oqYAZJxJE/IPXmKEvQanOosTME
/ViOP+NZNQsF6EGjY9BU0zeVotbJ4jJuJPjTByNa3AV0GbSEIQS8evnCLKH9W0K2
fY4VcGvkNnjaNBeoJ6+21WSKDQzKwUxvQP2av37H4Ies1w2Tkkd89QH13xA4CuxP
b6sz+O53qUvt96IpWpmkaj9tuVdGckctmceERWwdZ1/V01iT/wLWTYfxVT4wso21
Jfb2JGvoVmTqdf2c8uGNDacPlY4t/HjV5o/DQG39uOGW8BAkASQAgPJFYSGNFKbS
QwE2jV5afFHz1RCtagR1mC2yeK/TR/h4f9/98Tszn0eKUvb3GDhJig96UNQVyXX7
/j2EOe7U5E10M3WxyekxQ286DWY=
=7TqO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4d70fa6b-7c64-419b-ac24-ebabb1a4b753',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//X9Njlcu1UHwZMViZR3/KeLuwNbLsy8XoeHIx7IzT6BAH
8TQjGaI8rDQJE7fEeKsQE0W+szHVydlomm6Le91IHpLDs3OgwJ/meWVRqnd3LaGX
OM7tZlYkletxWyimw0WaChdTOgYYxpxoAcR7vbfhyoVj7/mG2Ibblq1x8+lXVxyS
JgRJqFkFXKA4LbGLz8QfgQOr/hrF+TRtPWtxrucOCV3FBun8HZbll2+/yM5/2enW
02vhnsct8jnkcpDZhKMurIWgaNW5vGHw23viD4coeBkkEyr9VqxlwQIS0wflMmva
bzYF41EKB37/4f4u7VuSOEqRJSCETgxrtoRuoyfjZnE7uwLg4nhQSW32iMnKunCe
86S/O/G84gtOwAi3weRf9S3mgtH4+ikBZQuHCTq+/lTlHOCy3M3lGPRYyGeN3KJM
5kQ1lzwFN2HUVO/IKhNte0bgBTr1JvWoq8jlpeR3hgt5c3cxsQtaiMizilFVO2E4
nwlDeSBI6NRmY4JDfxEFIHlc5JnKrrR8Dhzebkz3lRK4BXD9ZaBxoeMFR1yC3J2G
LHkKmOBZsEd3hCOQeA0ofHBbcvDj330EgHZBgw01sDar6gh+VHj3PgZPsLT6ey8a
Uc+o8qjkn7HFEdoT6Jax52z2zF+uTaBxj8vxgTlEjneTD041nqkNiXF0KSQvX7rS
QQEnHiQi8yiSUqfSYURVG9SUoq084hXpgzHdqgK7TafCyk9zWbm1ONKNSdm+0M6y
yR4qDTCJ1rIuFsRY3kBNoqg9
=kkUw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4e61acef-59dc-46f4-a586-faf0af5a2528',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAkzO/GbPNN5WzhkAg/Uf8qBBryUSWHg0ce5L0pkXAyjUa
jd9212iv6DwdTFjOGOnaWYzLZf9YHzqRG6tnubd3tirUZG6+gu1rAY90RKDwQdvh
mumWYG8fzuv2+eid+d2crjtUaXIOfuXMGZEvprw1TP1cZM0aGGLj4Y9sOdVfioFs
KsFq+zU4npAOWACnmG0pa8VdQlJfVkR81macFbrMRoUlL7cMapUuiIn5OMuLKiBT
0/EJ7UCUW3+sZVi0snohlp1vfvdiGX1NseD9yf4CTI2n7licMKKFMa1sHsa4r1yD
YMMGYZR/47d1a+9VcRAEbLQiT0GpkwTmfTs9QUEQPTtgvAus2IEKjIUOkxAgRKey
FhbYWi05PSOh+yOMRuUnx1btbRsNfw8ZQ3DZX3fI9Sh0uRumZCPtAfP0d42oTljV
QVhBsB1ua9FvNQeS0iJHXo5c30CnkrTk7kaPIpafqS/2BtSjPgzB0x/G5aj5SBEG
FKYK5wmdt4aUTGw8lS5zp/PC863M5kAmU66QqtxUrStHMzzA1Z0TNh53O2TRGnim
U6NtSb+f4jlXd8ZKIPDmfRWdZUHhkhqDTigVqJb0Zu1lVpi5w/tiHZFXBvJu7Rjm
3gaXxj9WjU4bhvWHyYuQXAJN4Rk5q4Fbsgk4Ine7BivAhr/HN1M3Yar2/HPm7Q3S
QQE4oGM7MlWG/ixlSXBWlZDU3I+75Ao0TPQMVL3dy/0g8wOVomXnPGtO1DRv+KI1
Y93qgEMtVxwwM+YNCjVoc6EU
=Pu5Y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4fa21714-1048-4060-ae5e-8f4f44b86638',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAArBS1IvsWLmgflQZMLsWgf/fDXVEUO7bMKxbI0e52BJME
eryd2YzbP4UN9ICHVMtn2C/X+NfuwIwHRXbj/HdUN+busiCn51tjpmBCJRTCQu1u
65rid/L3eO94KA4sH9YiT/EoHlPpTuFtwvwJHTYqJHdziqEQIekRSMGjYpoTuBqA
XGwigW0WaOLnqxiVBnjO37J4AvtxkiezCLbMLEj5kuiZlVk5XNXKZwdlLc+cB7HX
c+GFsNZqWRhbe4G7DIH7LjQGOmTDr8l09t3e0JkpBM1mfGrPb2kGpbIJ2Lz2SCU6
+eBcHFuDmavObBfoWFIoRfK45y/kbt2Lx9a/B0JV2mkFog3/1efuMDcm0HO+oNeU
4H7VtS03hMWkKGrC5tuEROYTCwxl2DsR0Hoa74X3aDk8XweJvpUCBeqZXifQtkwd
mdbCwl6nH/AHU6JMAnf5os9/aM7PYnRsCHBdLcluM1QDje6TuOmOrkJnkGM8uOu5
1JIetHyHoTjQmbRs1Aid1lnZiPE/L6jMhrKV3M6cvmCWfCU5hMUxmFNdfv64Fa5Q
eBiPqMQoBcym1bkIUpoK53cDOK6pTsZWWUtbSrPwA9qAb5F2X02v47JnYAaWYdf6
7nV5WrILDK3/Z6eMzVdcORZSxyu37rKoOn8W192vYh/H8G8sN19xq6e74Chzr+HS
QAF9v5ChwNpNqKJowDCD0IkNjXIkYNtZuGOqRySmJ/031rEDR8wReqKAF/rrO93G
ZU4/Rmip6Qunx6bBkFcPc+E=
=5fDO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '516aab59-12b4-4edf-a9e3-b5df6326275e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Qkyo7zeIyO50xn2WfF0/idgfGkuyA7ayL1VpVdKOBOrw
ZOldPOe36gRcMSzBbNv+MDGkt4qhKrlNO3Z9cnSqcNPTTeNIgmgGaP55X3tKsOYE
8byepr31hEiHjzp1kuhHrzZqOxpGMEQxqHfQnY2j8Vtak1GHNsO6r7+Ns21u4YPa
iAtjZ9oF0sIEGIoG87+O/4sKwnFiNyIEfFCTU5nReUQXAjK9bN4ipysmi+weQBkB
0L4MYiTQxNJqaC7tbkxKBzlJ1Jhto+ZnOgmIWf+CxC0qchZjtpBsFK5WfOrcJ47G
l4Zi0Mia8ikBno8yZxj9Z+66AV2CnfyMu0K6ZmeLoLgZ/y/tgdVqT0yXIwXZbbpK
6w6WfEEQoJF5HFt6K34idlyoUNqI/0obfKUfL5z6QRI3ExYSUSSVANFXLJ1b88d5
v0Ks/AfY8iAJTiGElsDIIAI4IKRyRcxCjstoyeLF382iSj3PtnZm2JMKVKeI9GKf
l27XgSsfaMo5zbRDAHeTHHCgCjyQIqJgC01ECobke+Pq0aiJggnmTa0hlJSxAfcQ
3VjYMtjSbMBYzzTCPxrrp5vs0wa5FgpPM/h65gdeTLwbKoUhGcb03FYi2OOwzLpp
LcrbpNMhOg7TLVVqumi+r/Xlbz8bjE5I+lDcsUZk1wLY8OFjhJwNGMPrQtiyWjfS
QQFEHAOwBE1u82qz/gD49ZwspsDywrgjvc7sXkJTnKvl16ilmbD3EFas5j/nF6Rp
52VN8VqSalVIoPpRPQPC+8BI
=cOEv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '541bb78a-42bf-4a05-a515-dec45ec89b00',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+I2NPyEonD0NoFWsSdhNJmZGU5SEy3Z1gWD/3pjyqrI8d
UM0mxE0CKkICemw4CckaMd8x1MHUC6FLKnAIATQm6K4/vDmkRanJ9FG9QM0aigbO
/JOrD2YQn3uP5QXsBgTsRjI7iDQ6ApnTqJhF0sL6g/jHr1x5fACS7GFA4MdJ4/KP
S5A8FdpC6F3Dqqgb3YFujYS/R/XWmSq2GuPCXpLuw85cX6okgdyippWHy5/oddCR
vK6iOCIECeFEbEC/Oc3XmTbj70EoL51wkK2PPn53mta9dQcCjtVQF2xECSLMzl2Q
nEpDc00NGhpTwDhUxS9OO2OtUCWnWkaJ6iMpoBBVNNJDAXcdh2f7uE5grOeAB2Hh
BdwN3NMi1oha3McFFUOdcjabWv0lBvV3VpidHam+isJW0F5pQGoRVyvI81alRJ/w
jEqZlA==
=rNiR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '56a6d31b-4dc2-4982-a852-569e5825d506',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//ci5JiYvcVPkfl9wuARBn5TwAuizXcoBJ1UlhgSumu2i0
tdqKC9F+3fMlTGPsI2P4Xxv1g+bOoVyOvJDLP2esHe95RHj2yV1HiuVjyxiIcN9S
VYygEgSlJktDE6yzQmwurf7p9P2J6YGLBweKRhtUfyaujX/z9zQ3G1qtFchY2mSp
0xRGewidO8rwP8JltwlLLtYKtazbE7ICHTWYzjLSxDkNoC19ErRdhnUauAuysFQX
sFeLTwzz+7bZt4jMEz4d9oLuhMupP61j20zjXMetTe2vvmZl0QCU7FUwF+1GSMfy
83dMio1X6Ffvc/pTxXYXFCbMhKA4rHa9yiH9dGytqu5GTK7wrBWtVo+vQRENljmp
4q2mg51NwcOAf2+IUpTcgkeXXu+Cu38aLyRuzVCfXxX9DRNx540IsevVB2dQszLF
22f3Je6cjUNk0ckyzGkRTyzXzlMYrj1wLsYrGH0jQNZ7jq6uzQxRlnUGtug22+oX
qRlsT/za5YiMntu1FXvtwgk5yUNeEG7TOEcXeqthiJpA5tnK4heEO3dbb9wfRaM1
THXGXogB9coNruSP4GjWz4P822fTVBvaeE4IcQN5p7MFtiOT5vojfUo1Jy9OItdz
LWbSTNBw8aJ3EXlcV1lEn0dBTYxKvLRaEKUvEtBaewTehmtp69KccbMBc90QPFDS
QAGwHzHwhlcehVRdMKkqXZbi0sSh87DOeI1rT2brBRAQyrJMKosKNGWoQH8rFCFc
wSH7iajQqcL1VdfwItTWDHs=
=o+di
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '58d8655d-eed7-4b51-aba5-dbd5126c37e2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8CwiaxWrPtyld6Dn7LWZvydgBELVUuN4FVEPDSUBlDruO
Z8XWNp9xQa3unlG8uQyG8QGbIMET0DJ49HVY+ImA1U8yN2z7Z4wiNWSYC40caiKn
XlrPtpD7IX3NXSshcXnGpNUlZh0hI4AwbR8vT5CCIhQnfDNk2dCXNckb4WV6/syX
KeVw3p1BqhBeg6tSOOlODW6PdjKndhEi37MF9HHxYEdihEm1/rG4ICwX0v7WcO4/
hVLq8XJAY4NssG14nUhJKu32kLymwzzX/SB7ci3x0APUtrX0Oz3aox8Tw0iEh8V6
O5fAGQFVAeW27F+RZkwu6MtdDsvEDdkUK4PZZTXHJNa53HiOR7Ou2+xxC4X+HJoq
jUNSQnxeI08GN1pGY6HkAhChJ7OVohPIcaBq/V14KydBkzdHL/jHZwmfG3FleeqY
rmpEYFz9IRuI5K+NkPz522hQBzpsdFFJunlRNiePbePqdAZPXh4VDjBbsCKxppWd
92B8ZpzMBC4vY0unLHLTLD1FQeKV4/ylOQvEPHP23UMsv9fmTlD0sBFPTghLY+8s
+PSkZc9yBoKxN2F9yJPpUr9r20ZLMoydiEy1DCqK30leecufjrUjJ4piuVZ84825
wB8ZedsTLSK3/nKPpbDFdBcOmfFZXPO4LwtA9YKovQEj2xel6DGqhH2p0jTXiJ3S
QwH72OAWFLfQGYsT8H08aRmhRnRPslrhklQ0usjQVMPFmyBuaLMEUYhuWGjRL5HR
QyKoZTkM+VX2auZSPn45DO7Iv+c=
=Deb7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '59c70f6b-b96f-4d00-abea-4f7ca1a5b184',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAjagmqpZRwym52XZHj3NxwbldT+t56qUOvkVGhhzgtOri
X7lVHyIPt5aJDQyrMqPVR+ThDV7ED4ksw3ksSZPmanG5lqpK7zZhnvyb5LvldE+U
fqpdcBE3rUxCM143Ku8NETVjfskulVNsCnqdKoVITYunTpLNz8KRkm975EMyDyw3
lLgNFzFRyFLA9uSwAzvoVd11lJ3uu3Qc6G1eygwirM+slMAn7XhdB/IXw8cxIZ26
MyjEJ34IgeMyfPlSUWAKQTcXcqEp3TpOBaWuij9gq07xrLkOCraNdGLd/vJkgZIg
mXVmV6d42PhLGDYmiJvz27scM/I67JhjLMGh5wkkYTUPrHXICtXu4jSQRw1+TzGk
GrDAFQzzVUKCvavmVv8Q2baNPYMEwZmI5mUEwTCDuhAs+gYCyRgddKVSBNDyXS/m
hJAAfwRivIvpGS0qW0mPR5uX30qwQWRTVpldRf/v7tQCzv75n6GcHArTYjm9rVuT
K3DdWO5a+3KSVIGTChTR3hs005bnKtwEps2jbwmF/ITf9z/vqr2BtbMhZJ9qJRuv
o7Z0U4+dlkcs7ElX1ew6IOf7m0JHSGhfmuWYqSKRU4APWDpwvZ/08D3ZXjLCmil4
Cwc2mdURFBsHqAYah8VgP06U8o8oQ8t0uIs54W9BNH7rz34Fl0QT2xHxCogH0OrS
QQGx+twg5ZMhXOPJwEBpyL+Yyvaxy2Ibqlppk90en4F0CYVziWUxWCVBYqFQL8GJ
ldgtR+OYvy+WwDMAV2KqvmIo
=7uQ5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ad2a49f-7fdf-462c-acee-6aec0cb878d6',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAArh1ugURR6AvQJTyOu85/yY7zTgmlra5Y7maIn1rIfy32
gcAbnzxEJhfOqLmNCDgLinlZeHn1eLg8SEWmot2D8lAVuI+FML6LQyL5LvF22pRE
lahVI84N25faTlTdoikAicou7XCOx5WLdN1eeS9v/qg+xt0IqBeWu8ahbe+3SS6g
lv3gOPsXDjT0HR8RrY3QRBncvQZPFrBLNOsowSNDWXOeHp3BCwUa9z4eaE5RBCo+
bIhZ4ocLbL9gTIhlFPJth3PAWSosyN2cZvWW+jYe1lWymURw5KqGmun/5HcNnHih
hH77YAULt+k5u+1h6dDawRkKUJ58cpPFzagS43x3dtAi+sBGrfnOPW3ocVONg+kW
YpigsN0G+ByEBHd6KZ2B37fjQWrtI5WRfoG3omVlj+gWpGEeby76xBVwSy0WXle/
wy38xMinJxmlwlqIxihvwQhQURuiHiJwRVQcFGfufLsp49Y8G8mtMfrz+uHUY4St
2ZOKeP0QodZRYDUUYou2pPpKVdGwx7PaKJPfuBMwdjUbS3P37Nox521vKkDZhaE9
AM9zn1kZiGYIvtmDLo0gFHSlRpLytMDdVTIOchQsQT/Q5jddVfMHjN5XZORYvxbr
2BnD1kssAmcS2TZ9I79StNLcbptHNU3/pfufQgYRHHa9B6K8vgWwRTLE0LAmjbrS
RAEDX6U22pSnveU9fKlse0QbU4LGeXlBOwAvQEiW3SRDSR9RivJ7AAEfx4igPOml
PxO1z+m8J5rMjY0HtaadrvE5BT8c
=oFTk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5f7788be-0392-4084-aae9-e7fc43e783ff',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAlZ7jm7yHJY+oPtSsPMhdbjkzCaj84CdDOkhMUW8QvQvv
6BTsHCC87nHxG4TvUf/MTrq8ft1BXGax9G0+bWsqOxjnMGCVhr2GOoK1feZ7Wnf7
3Y0HEbRCIwykR9ql2n0RkDfVudCzDlqT7SNTYgzgWA1gjPXjwPHl9MV1N8BR5HXw
RqGjufGYFqsRssqnSMRV8OrFddiSNd0eEVWs4E/Ue7/cb530936jZchhWTInqpps
wm7D9XBMaqqxFj/BJlhNQI+UVuG0PcI+4Z80JBybVuzLfCRSCNH+wXituh1u3HJ4
gt8doURbBemelwS1x2zaciybTP3JuNlqEXHk+GkCh4R63AdWI6Gm1JXkVjgactT7
Rcm5hwbQE9wfKCQUwtyedYQFkMCMZusFh5qwab/NikKOfleEeg+XA7CG/hRqyDRo
KErwBHp16CJF5LVkPFYqvQPgEkaLsKbWYjoKersfOJO7D/uzO9ZsYfHi6aGxZHnj
Def1ZzgjubTFK8z0yAodCMvGdrICRWnS88mlG9tJbLZ0INcTWBTkT0Mgq2BMq5jc
zHvQVT7LxEFNG0uTP1bZyiC3rpDMYqDB36PSFrsAH9nMLXetITCVhRUQAl2TTlkC
YBRrP2cxz4mhOhNzO3lTwKnJkFFhj21DlUGmzZmYPNJNSH0Z3zTn+VRRr0DUs1zS
QQFRuZekKHrxVVaZB902g8fHrTepkiN/y+jaRjQZ3ev4Xz0sxtcVymTsY/t+b9Yt
k+ACH4xIfaqAR5mfgTX7jLDg
=Cqe6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '64ad0689-7cff-4a27-a409-cfe5068bf083',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/7BVB87ttCqJPRbIdaI5ih8PRps1cQzAsEqnfwyNo0tqea
JNd9ZZAoQKEBiPmCHAcbQpKTFclSTxVlN1aBOSSJGpO9JEffjL0x73WMOYtI6iiB
NGEOlEA1nMIIaLBC9kCru2DIg30pmIdls5JvoD1Ggc0A70cdeWTff3b3CMPTa54s
m8T9aEnq+p/zRO2JsiX7FxEq0zunLxy5LfFTHCaD8llP6PyuSHQLXs0SQiArnvE0
ya4BzxrCkj8esU0iE8CHTPXqRRIyzIqE0vqAndOCajmB9ZggmynhWTTGbMw1OZQZ
1P76HTnReIHSjvfuqC5cUC/xD2lKooBAS8SorRs70Sv1vAu62QceYNEs4v9exInE
dtEyB3gt4uJilpjzvqx+ib/v91sWZ9azc7+pw92gBvSZrok1at8XaRle0rqr37FQ
ndZC0UGalYw0+ty3GdS+nn+ABDEMMMv8mbSrn31mIQE7vUK46Vi6q+CGyC5iW1Oi
JqKDkKBJ9WepOxxRDphHcFv39al0zDW7yho8wvq92ctvIr+0NUwUs26nZirRbla4
V5tH0gkJSuXBzmXjr9uacnUb6ddkCQDf8Jvh3feLU6M0p4LzbtRGU+RH69EZmXoj
saB5LeERj9DrPPEZOxnm2Qe5/Nwrkux8rV8w6UzqFZl2AdorVNvWE+jygUAENGbS
QQFEwoR8VEoXG9cRza3lf65vSyIq+0KLqOCTejSZzCNrtIfRcQmdsNtIN6YicewJ
vSGst3fJ4/Ro0iB/mfVbGnOu
=8VZG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '66ed4f91-adbd-484a-a94d-fd9e12a3a53f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/6Akq028o+xCDZizS4ZKlfrJe+ubwk+KY30/HEfoWHxztj
L+DQ66JheL+9tiRmBpOjg0FY8MJcvwVCSKf2sfDN1zUUyL17Q5CS5X4WoB7sX9CY
5X4ZhzrzO0CfJirVeh4ztdztZ4FHLKk7GRPiRZ42brgHvOHEb+JMHagk1awr9gOF
FpkmxWpSB6CSXd7K4JSR/fp98RFT76dKaqd8ghGuB0ts9whljRpWqsMNEE4QSMJM
zzTbc5OHdtIuvUMCSWs61rYVfPKqUWP5AxnlZc6/1+u7yyJPiC5pDnlge2srIi5S
b89PzbyVpX32Hy4IlH55fJgXzht7Pt4S0dHb/Spo5Gk88Nnu3UVC5dqHlxffrSzC
SLq+BgFe30wEytjD5mdRSByA1RWv6qFutCbVuq0EzUjH2NIJaJDANKHo28fUo7KD
brBsjJQk1r7xIVmJwLWbvfAiwmO4sGYRjlfLPUc8Fkm1N6JM5GjJ7sjrjg3+ynaT
hO8tWT8RXTdMeVZKfbENqK2nlLzVNbn0U3DswC9QiTk24hLJtlxdQn9DDv8///En
wJwfWqSk/haxnWR2DoV5GgdYDt+qNSeowr+En9ligVKZmnEQWvzYlZw6yOeGlJU6
Qqp1i+LXl1w1MtJm/zvGrYpsPQJvIm/sU4sUK+59KIA359BQvO8iKzyF6yDUd6HS
QAHe4kzjj7znIuelrxasVqokGjftVJpolveVESqkCa1gkL7gMC8Ljd33r9WukLkK
hMwqYNt3J+6CsEKonT1sUQ4=
=b7J/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '75b51ac4-32e9-4ebd-aad6-692bc64a624d',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/RQUeWgr2cqxOPIlGI+ZfX6MtL0Ad/+lKi9Diwkmagzyl
r6zCDa5+2yL0YTo5NrF5D+g5kO+AuvA5Hyq/kLZCfv8AUB5nJtbPTBj/OL0Vs0Rb
Z91zEq21BtjQHCtJU2tqPj3Y/tNHWV8bwHeG8/cX7j7a/ac6U7Y1fUjc7xj9Ayzz
oxrcsp9KI43FKmeGBRtBYSXIvHxOn4rpOWIZLBxYofGQBZMNggL3y6AA1NaoB/X0
equt+o0y24+Rq5T7QUdUzTQDUr59Eex4dNJKkn0TQYAxjwKysU1esTwKfCzNPrQ1
gC896jl5IFxjM0M6BsXoCaKmJC1fPInRBKhNaMwlfdJDAUvdW5B7Vtw3i9S9VvAr
CkWtlIF6A9npn/nF4ruCHk7Sjrqh+hWVXakNJDA4RAMkrYDs4Xr4EDLI2UwkFaG0
1ZjjXA==
=U8s9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7bb55e95-9c66-4395-a33e-166e9c152449',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAqEJ0Feg45MkP4dfrV0d3s0f1ovifbiYfw74DQP6EtFyO
otknl3hdmRvlxZGPnmyErXdWHHwfluwowtM4PvJ2QZY6cUf8sp4WlGFaJvRtcS3p
43NpDBccSE5pXNOBXEugGpssLmacV0lXbNQG/QJsHjSaGn7szcVY9PxBaNLztUkY
3T8MGBo6dbm4VmY/wNkdYC4UkYV31+rWILVVggaVobQAClcGvqqihw2mjEdSoN8h
PGqoMNh1fCslLMOROKVW6cGq9j69JTrLb1GM5KW5x1OPLAOBVWnm2ZYUu/TTEaXh
qzLMUF7b66ecosvoYdkrnh8MLgBS0igRdw3wH0+mKdJBAVPRLiLGdOl4F7g2+kny
poJXI8edJJ9z3wnRaLJ5DyT7UKN7B3KJr8G9nly1jb2l8g0OZIv8F7FykSWLp5Pl
nG8=
=Auv6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7d6675db-dd01-4fc4-a914-8028b1b06101',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkbS117LUEIuYss7ZfuNSptVfGxFxIs2Zq7FGd3hI5mWq
HxifXo1gs8/HcLJj7qStq6usrveeW7R3C+WI0yRl0WR+UVinxMRZPo+7BkPDlY/K
27SYM+16JyBNUd0QjRS7/wYEO9CqZ1ofH6XGCctB9+fLWCwjs3/RgyF+S6yaF/Re
A+f9kk+Hz9g60MgfLDHqug4ZFE6vgL1Vjbu79bkhuEkDM6gxB95EfZN7Fn4Y8qvN
qvF2XdGw1oHICabOnuon57Daxm1xbwNnpVODzKoQvUye0MYnDsiiJ35Lu1I/rGia
Ad57aqEjOyWG1RSLZOO/n38PA/0xefAYPnQO6lSoBQ8D5tyzvPMiGhUVVm3hAL/0
bM3FUdyX4DgxCXhF2RVDth7ugcEqhvOJioAHSSeQLkmgCM0aJYSG2ae7isqi0rep
0xc2jb6g3BWEJ0GB0hJ17EKWazKTktcKD4Qv85gmewx+BNhJdUXKYq0phso4UPo2
DxZBm8IC+ZD4XABnLhNrLwocFqW7DtByHB4Jhv0MinwslXA0KwhP88dslUr5yIxz
hUS8cWG2KdmXafktHFA+/XqP3R3MEa/UMjCuPpqKfwrgtIANQBUAlxuTPU+Pl0wV
QCouCsb3JmJ+t1QQUJnfavuep11HjpZXO+c0/z6as0wrOZZHOsv2o9+CN10cZPTS
QQE35d5MjLWMwQZuRxe4MrCpGZUbZZ5Y5zelJA6jzgQjV4r/IIzLn78v3lphCcaD
k7MQwjszeZaolGYbb7p7FrNx
=ii+m
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7d961a0e-6a6f-472f-a65e-510be7920af9',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//T0DDhw+t04Jpl8y78t400iuhvkbVC178fVTHBOGLZv3e
U2aHE3SkpEvh2zScMhmcHi0cVUPWFIqYaFkbl3uvIymX1Jr2L4ISUdSiZNpNwe9T
RHKmLGy68cdJK1rebRZ9twKs+1snCPdo8i3QYKbGfLhrAUqZqgN1WVgkeiVBJV7m
4wiD4NmgzIUZUTVK00GzJGoJZZOpv4+CAb7UCJ82BlzVNX69p5IHSu8XsvPLZItB
zDsML9z+ZdgnL229bqnDq2D9ayOrVSq2PwaAcaWkpfk1BuwDHDIKKldUCngEcaRq
D6kKTkpYG39ntmq0c2GoQW0AOt97+n2Pj9l6CIogBGh992G043Q1nm6UgOjvM8j9
+oJy5CCj0XXbsYrRhPPz0gQLQ/bjVImpE0DHNA3If2h/NYPFeGcWLlZ71gZ23nlY
//vHGLmxk+qnUiOkiZVESnNQyxDwufK1DHmiIR5HPE1n/xhN7q3bIpJMTw5SN4gE
asBni1Mrd0+rVgi3pAlSPJkYs47ymch3JHb4NB1giX0gs7kjReh42/UgIlkToHfI
Pg9+VCCZ8g5AxKIZsLzLLc31EWpVMIhg3IoGmi2t0Pa1aSWpjFxU3fkWW6Bjtq6Y
TStItRJO07BaY+xVUoJANlJ8NNoscaX+Dz31pzoCGtPWk+UQfz4DnwIU2e6/JrXS
QwEB53+XC4yObDnyGwF1oQtYtFkUzih9cC4X++pX+cGLhWRIwWfaKUHO+lrJS9OK
QBV8C5QHR60wtkQsgMLRwCWZCak=
=Z6CI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '834ff8f3-d530-4d79-ac6c-8857cdfa81ea',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAvPOn02aHyuEpco6Jh8HqxUlOF0UMPHZWKa5uaZJ80tMV
Rxv/v6GBCqDDN3TxeM14FtkaT2vEJv13tcxdiWzC6u6zKcwDRk2vvjkw+J7M+pWw
nwazRdY9igMKo3vodgg+MBXF9l1IKtILK0NyEkIlbkZSbjhDvp9p3wEFaO8JOdHO
aiMHJVdEW+Sw+VEsYKjjAmbwqLj3bzJDZdhIRg7MiqvODPeUkyT10ah/ccvnrl2j
64w0/PEUTMg4YlXdY17ulf5xcbJg7jnrbRKJfPtH5A74UdzPRah6fOEucEpJ2U0M
7kKdNsIy2TXq3mvAmfNSXB6I8cYpdWF1SI8dNWEVKBPKafxbKOLgOj+y9DXItUMB
1D5OLk+/o7gw+SW5DMEbmRuIAywkQUbZ/Ut8pQQpWq+xZ6udcWCKe8pZSKDAIs/u
tW/cpmX1R3kT6LPGbE3RCzwAru4asIkIOgOKWann/s5VC6bI6LKl1KIs9+41M/ma
R7Hwuh1KtpJnYdYOr5ClfZP6xr7DECOr0GiJ/56HN/nErfg9UbWAZv6TqmzuUZxV
oXKAr77Ya4XDZJrPmjQHU2Tn9cGqQLWkstqhOgOKmQLHjPcf95A8jkmtBZfjAfbZ
7vDO+e/2ugDlqHYT7JXvQANGe6IVV3gU4vw1pj5x5RaB+kCwlygkOZjh7sZFNTTS
QQHlJUSnwRUZDOVG9rrUio5yzyrqiK1bQ4ZrXmqh7ie0Fl6usKNVY8vjZKuzVm31
c1PVs5l7iOxzrnP+Fx0ubnnQ
=CMhI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '860ebd5f-d022-4905-aa4a-036aaf0cbce1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//al4W9+qsBwtWLgC++wm6/zcy3ggDS6HNXg7jcBgR2yYr
vP4ZDDh6wl3osc3UlIiPd7L+cCoYb+1MFMeB58e4RoDjhH/8ZBX2v0eK7BGLt7vn
vaPYuzNHE4hLoxeQlmtH2rY5ayPZ/PDyBhzGnkEWNnI3VX22T1e1fUTVtAJPTOSu
Lau783dy2M0S589eG9X7qQ52sf4U9/HuLeCdjXNff8HGjMEFZ7vD3cvmIdrfa/ns
EQ03ba9en+wPBTteqwIjkCQapMViNZ7LBE3S9oVP3iL2+2AbrKjg6CvuT5ViTboZ
9dpnRN4Tk94M+ZxHvRhVaLXssuHK+OvZp5466P2C8bYKAs+DrpbeTm7B6K3mtYPd
IBX+DlS7vzjuLiYF3b8nDXVtkvTX+Wom+0e1VoFsfI2K4ZNQ+JZm4A2sECtP+k16
rG4lQBBKxiodvoKit9IrYxh5Wz/13ubrD7BnBPqXOzr8oi0y8ySVGbZ9Xx5VDhvr
IB5cKHyGzr/nNgeNTZ0xbep+3KHMshD3KFYWrCeNUROu9jgIKBr3y4UJLnv9PXsv
MBD5f9TSyalgMUCwEvXjmXqrY/pntuqQgo72b0uDzfoZH2Nmx+5++My2k9owkhGW
5cMRgtsuklq7QqZpOgDi4nseh35MW9xa45Kc/HdzlHSzqaAJuiqyz3yADPYx7aHS
QAHk4Im4g1Bk3qz2x34s7FJAjem/5Yb8brZ195sYtaP+Pg0ejhP8p3IGWKsVWfHT
ZtcfRng23SdQrL0De2dM1zw=
=VGCv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8c4ab172-c249-4d42-a8c3-01abe5ec7edd',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+IqIKQ7WS8N6+8rkVffImjQm21Ac0JMZKNN5HopEflaO5
mzayIYfelifmiK+TF3TQLLOT5KDr/CXuMYl2D+TmOakHmT2z3qBZw/bpycG4V7Wx
kjixnNIDJ7Af9LNDbjSb4PUZqYYYpS4nHR17yuE0dQm0RagpJ2IumBm3Ie18AjFj
ML2G7eonWc1Mje40NaEoKD1AzuYI0Q/X1R7MlLLfK0GMF/VPqj7LFjKG9fRjrDgD
eaeForbvJTrDBQRixjz+tmDlFWCwE3V7Bb+i0N3JfhqPZk1cC5d/4Wb5xlLJskEl
EM3yOTkQT0MtMm2ZZmBCXjC/4jHbMTMkHHTOPkw6Sr1hXYvP9czDif2pA90T8KNj
fG2/OoD/ip/Xy3Uh35A7d7dPYpItRQ7jYUuyUBPjh5WYPWaisWNZ6mN7QNmcsI9S
fJFvXg7bNEFdZLVfTd33WZ+p5JSoWXs/C+mK9YkKw8o21fbvn6enfl1VC5HdH9Rs
sMsY6yUN657RbygnyBwh/ySaf+11Y1JT1JXAJV6cUtlhbDXAr3+AD9kN7vKD2FaK
ox4r9e55u1rI55BG1HnHoPUlgPBd32uPFnN+av4BcuXblXNQ64Df1xA6DwbMdVoL
YwxtglOOYPOwPLQqJBfKf2q0r89WZc8X+3niJdIs//97T7Q9beGjkwsm7+UmG63S
QwHQpqoaNee11y9R1frxRzpSznK9v3V54CXbBUNnIQxcxdce4IHV3lYmW+S3U7uq
a5YFm4YpGyE6it667AeiCHewtSU=
=07m8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '97fd14b5-e0ab-4e2d-a557-5f84d4de6763',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//SiqQdtd2KoMBjLZBv7BY8HBwyyFKWdsBVtb7ED+fsdxT
3xiyNE6D2C/GQ1QViPcquqp0YnDE1WsoYwqvt7FfBqZYpnr7SdHcLioDWhyEuJaP
/YZgsw1nLRJxS233LaN6VqSDYGuM/3tUATLT6hMlTbcnrdQ/k/CkKb6jN25WXnLa
4zcSRPZcLdfFQ5belvON/yS7qzhhMS447MA/GObWQb9HRGAmLGHV0CaR/+IpT8sp
sLUdXSaq+Tf5rdy6Z04Msf7vfgodmCWi3g/RDiwhD+ya39HwWznRDQlFMjKCMigu
gmW9x+tXK3ljStR46OTBqI8sL9w05sf7Vca8oBP195bZJPou1Rr+ILC2aNVb6hJr
ExmfDLAZDoTaOa4PfIlyHuhhub57XZ00dCQ+tGBh5KBs7WxBP5OiIWjsNVwMTgTo
p2C/AfeV6vAbw5hkbmEf70pebEbDRAIQI//eJOr8acma2H6M6WuI7icIuBH+78lu
/aNoff6mJxWfluMnnInHs/r1Z4GyS01Akr5hcMtKuUzSa3bqPpK1W9dMNAU+SP22
o6k905Xk0q1fP2klc5om/DsREDNTwpyIvoFS2b98oGijOLwSBqpb0jiYHwzBJzrO
d5DImXCHqXdXsDSw9Ng2Cz6vQCC6sr6GIK/TpjCvh23LhkWXudY6p8UvT6zj/7PS
PgEgIwAAMXTI382SUpzRv+w9AHe/yDa427EASY/UJUGkqh1DubZuq6SuBuBCmpag
Xwfe+DToHUQXcWXuE5Qn
=9U6M
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '99c66057-7471-432e-ac70-186244671c0b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAlSuGDUquAMagIlLJNUc/OgqK1N26kFr+oJ6bpvKiX78o
viEbhZwP1KlQw07WVlQMObx6Dd7iPH9xfg6aTcFKDuqIaalhcsZ28SvAUfq10Fjq
d+NYZghX0isN3e63/QZGmS7p3zy5sSy3TuBgoDxJQV+9GXregI9yKRpdjrWL09Z0
OOALWSUyeAkRVYjvB6HV+G73v30qb1s8bGQe5PodjlniXglAGL8hjbFFeZkei32I
jwzD8CpbTzyEui2xmWqyzhnU/EW6L4XzyhBeQr7lAHBeZ+QlaH7LUu2GPFbvZRxt
Hb/gGP2n0U6V2JZsLPGhqpFtu6l4CC/sVgEfLpA3HrsPzR2pVfRZB5C3todBQjZ6
OycI9+bJMoNs9QjTZwiDsE2iQJ5RUENbfHho7g8OnWBZqg7KE9YpWB0EGPNLcUDz
doBZi+TE5htFLGLjdlTQds9No0ajtosvaBrIOL2fsK2SgVmuC96LJd/ocSdDh02o
+wsVWLChz0xJSCr907jbGwG82kEXf2WYW7mKW0xE/CAwZcu6ZHn2zfK2x3NJsO2j
CpWAtvcYxs+zSmNDMDMKRAfoOd5xAZNG9aS6mBetF69ZUZVrxOMOYvtEAF1rRlSc
C1nTxJfGE2Gj83BaMm2+HmhtxVkesMKjwAyslW2sXRrmL/raoLywHXxmEFKGQD3S
QgETjqL61Yg7vRsRbTeUz00E9e6z0MHgMNUDewql3gyJQjxdFCcT55/bPznCe5Ch
z49cyokswDSaR6i6BrzO1enGYw==
=PoWH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a0683b73-24f8-493c-a03b-029c2b9cf437',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//fCnI6SsOyM2BUiT3V/liju1y6vfu0deh4o1kDtC/zAzP
zgHz2JQBZq5SdJS9rme22wayQPmOcmwIWEdBu1gmvkCBnOKc/5/lC3RyD7/uXXeS
CzIZQ5b6yu2a/bQNAt739wlK7FIrElF6mwTc6nGVd1ZM231GES/BPr5v1o9exS2g
EB+qY8f9bEhyCJvAklFQlWeR1Ygo7pL56bf762QiwZAUovHn0aWqZd/Zbl6YAi4g
B6i9+bk+oKbu1e6QOEDWTQva39+iWVZUNnEkzif60eqCEAHuOu2KItE+fK5nYDsH
c8HPVtqugeTSKb6haL5RzT5fAbe6+hsSYY8WIm6pIwYMpMwc+LCOy1IQABaDidjl
HrmazMw1OT0Bii9wLy6gRNTFytK7rqAeUoRHKzuuararRpT7iN+lNUXCHLMSWtSZ
GwCeZnNSW2YthGpPlG6gvZwZ/br8od65YbARcKvbXVkLqeeBGgoM3kJ89QoCltQZ
p+mmWuPKB0AoZkBiPzgCv+V+tzJrBPjthaO7nigf5jlm7Kn/vJP/bcypAbJvqTG8
1eE7kPP97FyotGk6VUkd8vhOH0/ZOub2RTP8EO/6ox0HPO/Sds4C06YDnzYglNj2
YlWLhNDit/GSwtTPisVPBxHzC0HB0XJbYhri/lggJFz+r/xsiCEZZYZdDPsmdUHS
QwFRGtYJXLBG2qOPqOjjBVDXBZNrPqbgfobZ0Iqi031XMVLA9+kuvYFqFdI3ySYq
LB8W4VUOkEt8qYVsFtz/fC5T6YU=
=SxuN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a5713bd0-f08b-4bda-a37a-367754e3c328',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NfKi/h4kNEkIBwlSWDWIkGCYPgrfT4/hY4BXV93tVE14
x5xRU54coRxNc/6MAAtBcHxJzBnzwbJVxFiimMYd2hvoWHByUp1QKOBAMLz6EQSq
4n8VuKdQ8a+AzzsjVThACG3OfxUfKTkymDExhyfMeymwHXJs4YT3fnOPfQnP8phh
yoQchAqUBvoK/97o0/6QDbA3uyrgSzgOJ+tiZNn3qTBjzc2fV2sgRRSLEHb9AxFl
BB4XA7pEdtTtQASVVv43zMtIQE4iV3GT7JEeiMHoa5egyO9qRlGAEJBOMjo8U8Pq
kJQA4PO7TJJFLkJAuwY/UArAZTnnLm24wQr7R3S4gmDNIXearyio0Ujfw5T97r/w
ApKlEljGmpkbjPP+cClJ1l+EbAvHR+KA2snZDZt4yPIZkYsSDf/kukmTtCv4A8EH
bU4MgqxcXCygK8zdW9RJih4uqEyYzHVGFdCNV+nxX+dLREJy3zZT1FHWbxgt07eN
tanX4O1JRDVCLhM4qZ8HE2E/ogqEA4TmplucwrAsXkdRp3138ValPQBml/5/lsuq
i0+2goXT9tAwu2QddYe+WLtvKx3t3/JLpQGeSt9TzdeNqVB1AKlo7YLuvwvdqxou
74HN0pwdl7/UdIqtu/8Oq626y/lUoL6rBylU1J7OOjOW5OkTsaLJmkjW/1WjX/TS
QQGqTC559IdINtmOvsv2KDNk3PTtfzTG8w0Lif+6puf4KR9wO47sah/Gw8ALzOQz
IK8cfZe3uzRdHGmoAPBRa1yo
=nB0g
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'abeeb90b-71af-492c-aae8-c670a33d4f77',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//YQv6xphJZIlb8o0pXuU+U/llFB9CGgb/+3M/fdGK95HC
kI65d25vF+7z4KzzjHIFitK8cPtRSClEqq33XMfUlUCUKndFzEhMqJd5nA5X6DvR
KqZh97luW3VYxvA6d7jSpXvxa0c06MAPQJhm5hYEbkcwWuf5HR+9LwAVCCWpCYVh
xWDHIORiRFF6E9groBUE5lg4wncUQWLrVpABrAuceGmW+dcrVp2iWrYeLj4vc48D
GoYi9/FJCz03SotmTwNT3oKFtctHgQ5m9+e33YHbrLoyJ76f+zo3685MfWjaq1s6
rnifIsyF+LT+XHFk6qnQ/GqBbUD2U4eDLuwE+E2yZFK7N6ao0HxwgBxGq4Ff6poq
VErAeKQj+i+8SEo8kcZSSPDX4ngUxRBsNFSbcZl90GfCSDTpGlj1i/WOHwqRLpko
NkO7ha9oYc/J+/U8Kn35RMSBN0/6wZ9gw690PuAE0rr2bG4oEMrWvTXKfDSXExUs
vHyuSGQy8iiLRyZIArv1TdAcF0DEBp2fPIVq0nIiLAiWJBT4ZsqzJZUnOILHXdIJ
+i/R6YYDgh5yui7I2l7ZheGTjGziYLgfg3WF8KBAW/gll1nggQVFMoCZZEMZ5zMj
0gK+v5W+Vy+YainYB2gZ/4sRTjDVP0MAesVmRfqkvajlMbUxf64SQo5d7tHH8h3S
PgFlB8EY/8d30RZQ6Kr1MWqFiwzUNWTvM1sYgGNk/zpQDyT1/Dzj5Ea/HhQ7U0pB
PH1EOonGVTklzJAKIadC
=8PwZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b75a931f-6fe5-4381-a31f-68515d522dcb',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+LHmLbyprBXWfoxQYouJKxEknqf2EyH6E6xSAcYqu/lJy
XNhuvrMpdreiibsFLCVUUbgLWVh4QCt21kXT0vB3vQEJx7890+bygGXZ2j+OqAtb
tPQtSiTZRgzQtGFuHVVwymvWMSBZW2IPfUQ0D3MCj+8o7kfb9EUNttVCPRr0Fh0D
heyznSR67mYhE35dACJm1zpbzF+JEGi+YTY9OQk396WqNo9QjQ2KfTY6gzXC5ugy
6Te+iM41d06dVAW2g5pZ8gF1i5VBfHIGPg/8T/QgE1DIbTuO0+sC4LF+49GWCSnu
l+xch2DOmqUjYwPef+JlrTF41DrvXQZEEgu4I5cO/Dot7K//QqeXbTOibIoy3vMz
aIjU0vUTw9U75S6P+p+BWPgI0Db1eoMaHLRh1+L28vaFYr6OUkgQoYAGppC8UZuQ
7gWjVBogQ00k/WV/KTjLE23CKxrAiHb30/8UVtFE1Nq7gTG50/lCRmHw730cQbgz
jM1u/1i7kj74fixCrBZcP/QB1exWvSV96bjp9Ax1H3ag4xvMF+HfIgjtY2mGqH7g
LkG4Ej6e0gGr44gSvkFI+KSAlPHc8LeIqC3exodlX4o5bHf/RSPiwBsw6N4Ik5Di
mRfpuuVWlrvLY0Tzz7yBaK8JLj6KHCOJdNYof2uWkwvufznbM2AWmy4zq50Ax3HS
QQGL1roBmYr/6lGr/kwh+pPts7ajjxcGWPwOxhAtvn3yYsByMuqvCI18HYrHZnPe
2YIYUHxZ1WhIFsJ/ZLH9vekU
=LEtd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b7c47f64-2b26-49cb-a9b4-aefc8ac97ebe',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//ZXNRy4vwqIfWeC4HoC+h/kEwgs0CtPXGaSz6ZUMryoiW
uIH+IRMERZl5+uk+SKE3ch+B6xOWtW15Io1O1hTKlJ49w36ZV68SeA7Jd4Q/HLrk
fklUadVz2TrCAA+kdYgFHfRB5ANXtdk+oF+S8PeVdO8FmgEY7yVsYFE4a4Bof76J
2CTXtyeyoJ4hfMqJhcyMGaLly9hN0j8f4k7YBUI9ELrZ1YUXpqbRjgnr2oC1NLl2
zk0BSwynjIpS1f6A+JtY9lNMwQ+dGNn1jNCT6r0xv+ZEDrKeck6CuJ+fhwlh/R4n
OrUWdE74KL2nwTAh/23pLRgi9kxJjTLML3bum09beluaBGvq5KJNodX+y9CvgvH2
y7/DIk6MeR0gH8Q+25s9T7l0HYgoXSukuIFNin3YCcbI0J07/lJc75zNCzslEnZN
06HrAcm6A39PtjgNRV1Rmi22482oS9/FRhyYsC+N7x4+2NHEH5boPb1tGphaNU7h
ySHjdK15xxUf+JMZjQrXv+/eLWppvf7zTZX+8I6y4gzuaLHpeY3KjPANwDqwtXj7
iRIJRfAGvS2EGlpC75kWJ7f/sCHD+HWQ+4HbSuNCVAN06X1jTsV5CjllwNJ4TqcA
WeOQ2/krJVdckw/Rc8nRMCuPgjNhM3I2R+HqfL8fRp1oe1Y5Uq9jJhZlLyODIPfS
QQEyQSy6Vf29W0JetzzX1j4APPXEvRREllvmSg59faQo9w1pQ1h52id4WaHa/v86
sAWydZLqttq8jRkj3MqIyxUN
=XIe1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c31067b1-705e-4f24-aeaf-6e7b472aeb4b',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+No0iinJ0ssaqXhNkmP75AdUbTL6d9oCkiph8atNa0/sY
lV/RA8USOQy9OoD/5ytr3utDLOI52Q9tXCkRVOHG6WmPfbT0pziBxOfXru5YsEBg
M5vuMo0NX4lToJBeBqPo68BzX8jC93S1eoGT5SlurmLCTJHzd3czvH+VnNNykCgu
efU09V9+xwpxCO5S6DmYgfnthk2Jy8r+rMAJ7CNmeK1QMteGsDSjchgZ9L3ZLXdc
pVi5YkjoLARjsnmkKSxYV5m4rFhCr1mDDhY1TVmRYwBj4vHeVsqrD/7U/Dzu1eFD
KwOJ9V4osNk8fv7XQ6Jv3iATgqsGqMHkDUgFnpbcddI+AYt1WnQ+Y4WgyYSCsr7/
iy8GAwiUI3qT4iG9LLJEe17wTEgUSGn3cXhIlTDLFkQFsgL+5kxrw1Nq9h1YHWU=
=bXp1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c35c50c2-684c-447e-a1d3-33105e9dbee8',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAlzh8gzXID7kriyUSJqU1Etx1asnGeyNXBn0rIRaHA6gJ
Y6AfsJuENTKpnsTPS+yFZZ2pq3pyINwmbqZLic5B/NbBMiHPDAd90PqNftJVDR8K
O0bSIt3N+McrJqp+PCtu2qWdgdusHgR6Oao3Sb+526xOO8Dfir8anvUE110Sri2Y
remMlvR/rOeMxxmlgEb+o/bdtdhFC09MjZ7hvCA3oVU1cHh2aNWkp+4thJXy5Aft
p3hGoOplwcvhZiILssD8LYIgN6XJmiwE6Q4anEsZwDrGrIfzXDOmk+gIE0JycdDI
Jm72aA8h8FahMvGNSng2mqAx80Ty6YTEEDZeG3QxCYA2RJC83k3yYLrKYqJpPFwV
OUgqTnk8G43TqaIRQjqzUvZZX8zGG6P2bW0mWM0SWYdTIKnmPeTu19Iw13f0BqQF
dVJZRFUL06x+CV+BKv93hK+ffLhYwqW5fNx7tclANPrjW1cnoR5/O4iCz7mQ3qTC
ospLJZd/Wz/2t7Jp4/TA3CrTwOjWyQAv/XXUVHS+wPaJdOHUXmLuu1iN4X8IyqwU
pa8NLUyyIrXPfOXdotLOd/imrgACqcfhkKD13H/Fb0FcWT0OQ0iTGHpObG8SEPfF
HZvNfUctJ2H0ih6HvbVYJyz10QJzoLgxEowGhzN78f7mbCU+b+6d5y32pwwBv9TS
QQF3d4nR19yOlRZxbxwZpQzqvrZxIOxRjZckGPiwjQoU/9Y1Q7TWBtADUOoMIE2a
L96pI/T1H+FrI9QXd5e0s5E7
=YY2R
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c4d52059-f1e1-4c3f-ab26-720714356ca6',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//chn+zwKe8H2EXHmcUWVm+6h7Af9RIpc6HN8zxJ2zyVrE
IrCVOed27caFAvZ4IXpCLBuJLjtZg8eqVY8wKQdImE8gKO0h5g2vSTlLnJrC7GjC
5CfikOrkiTR7fmfi2lrdxqiLMpr+GfikEKTVBsX9mab4+wbiSKhTxNUfHKOT8WVZ
DXYFj75QHN2m3zI5ES8Nas20DoCs/tM73zE1zAtu92jXmpdKk7fJZ63GmHq6qdH0
yX3o29/6vXCeFECVm2E/2znmut0ov6iTa8+8x05//PCVMhwEdzB0KoZjpLQSislP
ihuVYHqrCocpqjWorVnwlPWSdwE7VHDeBuyvPBZ2LXCSZilKnrL/EBIrqybVJ/Ho
YSyHOp3vPfXZ1plpqlU0tMav196zvmUpDWY1oEpVXi2odg0MBQfjh3SN0Hxu6R71
CEzVs8D703d1YoskGK6N+bAUNzzvCxRAVXGE5L3hFDhKnmvM2IwV0kMTY26DsSkS
sGMHEHPO7za4xUnIaGmD8s/enRFsddHYIvRN4JH9CptPRto+rSSMDs1bAw9ZkkiG
M/yuf7DJ4WwA1+qD5yt4HBQeFt5ByzcI0JFXSsI725sR+ZR3UFcgjwaTakOAgJIc
Wo7seYsJwxKgh3+YHkeJVpg9/f3LYqAwfNN/7i5B2QOq44MmBpJAgUC9TJY3WELS
QgEX/ir0AmcOt7HI7yRMQ/yDUih3MfC+OA29LB78bWYWj4DTIl+2fqAzApVpByN8
kVUUh1AFcbyDKmylsGxQAaJshA==
=puMT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c5314765-4989-430b-ad22-379e3b25143c',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//azIK281K/upP6P0YJlYmFPJfM/iZUl8P4xOvZaxT9IXY
c6O0IpMW2cxSHKutTimg5tXdZSgXZII+EhVMdcLpnuXUrFrr4qnawhp9C54zBvnm
3SeSziiqIG+BL7ZBAJ1rLwmceyOd3t5BrTuPI4BK7OW5nSs+Vl7oOLW0tDuRxdYN
whdw+CrGyK3DnF0HK1TPr+aBFG+dHpJx9xEvhdwgGgcGKBQh55gyONuw6ALobnYr
+JgB+DFVlqniMkceVyHqsI5rVHoOL5nrc8YTMT8MRC8dqFPYpZQP4kCHV8aj8HtW
6WK3leeUGg2PeNKfd6FuXyUHhmVhs9gJLyrr8tc79c2eTg53cHqaGVfoouVhqhAB
C4otVvaxKXfpeLhS6BEwnTmCi4SrTK6Ups007NrOm0qYgWwMngUg33vDD0zNA1ul
xG/Tb1ftDIeFOje0m2zEcIzqv/Zi3mMuD/ovaB3/ycGsfRguyQkSIW+f74OPLf/l
Du822hixPTx1de+SHODSEbV/Ab4tT0pd0yzO/653IYuzqH8PTG7d/RVZTTZmDQoT
7BVPG86LgLs9dVNyZFD8DWPSOO2wEJoXYMUPYoupYl3QVvQY8yYtHc4OcBvFRqcw
fNvywSkzGEp3kgDBSR624s4Pot5wSdq7UM405JrrhlDpQHY2vJh6ggR3TimozXbS
QwHJN3EKePFCh6Vix2YBD4672TciDmJ4EuxJs0Wyt16V/RizjNejyUlZQ6Z/XzGj
lMDh8xxrT9bzvFoQJSNflX8iNls=
=SmlD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c5dc8c0b-e448-449b-a372-ba4b58c869f8',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+MzR625wxHmcitXMfik+EtTNmMj5sDccIGNbwsFRBli2b
5sIYQCcN3TroZhbIyOp4zFSv4TBjio2KTrLqNm62R/6Hcbutoawp+1B7f4g632gI
3Cl/GqK2iu3KfcdqMT49fM7RtyOeL7ruB4fJTuFXZlo3wKCerhc7j14eebpCg7fh
N3KvDDjDtmlxxzwEg5yXnFLO6LjXGlSrtLd8SvZNQXknHzNT8ZzyvSDoPPO4OB2U
OKR6Bw4+b2x68g0E/I/HGjHwpGDb0HAsOGTrbdzxT3VLfdxXbtX76dF6KrPLA5nr
Il+Eg+cqcaePGJ7g1/QHgGjNzGHBJzXCdz/Oqx/MGnvQaJCCHsIdFn2H1Qo+Yf6g
jKZWTWu7q93mGwLRdAZbKLdlyC5dXjyeGsdb2Ou51TlHwXAIs8rhKqXVGFcVcpGz
eGtA1gosDOOXZBG+/Pw5nbmSz0ciNGQgYimaMb9ZlmK/xudUgN/EL8MHWfCuwPwj
rf/qj7lFH9JSaw+FsHthnX5Rjr1UufImoRVvwRERxZLSq5sbdLlZHm82ft1rdh0s
P3utV5/4/zBJRBr2tEKuy2Oh3zWslBd5gh9Gq4gffswNSYda4OjkHK8zNpWDh3xo
z7KvkQyILchbHAvGuzV6LhBm1TiW2vLFAT6wZ62KryOAqLqrxKLC4uVuM7ieQo7S
QAEPMinoipAD28P3T8JPAYk1tLaTc4i4XbFFmEJvza4caUMeEVfiiLrhXwozQpKo
RJAx4uEOtITn2rbJYk8zthI=
=gPQr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c7626f01-0cde-430b-ac1e-f8711d19c32c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Rg3D81pG+T/KpDEvBBvOPOS/bzIlmpyzr5PGZPnY+apE
DZujH85OtFGpRiH6YrqpC4iR7psc00GgtMZBH3TRj3BBzM33TaaPpy5JOIq9bMLm
FCrY0CsfiL01pScmBQYo16+1TsHS3MpPuFl43cma66VZLyPG4O9F37k18DtHJDey
CzLmkt689yogEAJ3xsZDoh32Llx92XAznJi7nmrp1pvQ1rJpqrvXZE7pkcTJ8a3Y
loMBszTuLpp0XMj9e7M7dsvYmJjRj7q9Jebgzo/+7pHa0yoFLG69mc97b1pT9SmU
iilhKKFHpjRVcHeV52MPBBzSJXBCcbmFwfKYk26cr5b2vvZFRGdq7i+4n5zhFLLi
PCh+ES0LU4tYUwVHvBJzQGxBsPFQ6TDDQO1pP9ZcMLxfwFzPwZLSbjGgTHHE3wjb
LYVzqET8/W7y7KPyq73RdTj/pzr6GGklwXZ+caXW0Yp1trYKcxIHPjmhenRGLrtf
qZUhYwiUtLcw+nsOteblEXNv2VXUqvODdycg7bccJtee+YqAt9IlPzcaGnJJIfpL
jA7XvDczJjfeJGD3ret3gfdJFjSvq5w+cZCvZangQc/BeHL4oAae3k4ZcI89ACPQ
bnty4d7pT3ub9aDwV0QvllAlWM4V8hBRA/iCfv/LUo+o1lvC52HSNxdFSsN7nujS
QAEUF/vr7BOSG+3IX6sbL2v31k1UDGtnoT8a793OIM0ujs7JKod3AxJOdbEnFr/0
Sm5uu1/kNVl/M5/Bkyas7f4=
=swTU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c84ab7ea-c195-4263-a1f5-1d8e3f14dc10',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAkEtU+4g8rca6Xd+Ygj18uo2YPQjHhhkg8L06w9N7VhJK
xXEy5OWkLdb5/8sNxqo+4g8GB1Di96GjJIGE7fvcuylKE8y3/pW/w/X8YW+GsdRg
MaGKjcZafZF7QnQ/5fo/mkB3HsEVg01dVAGmqXIRR/rD/5lBiMgqUIrzcGve/R7j
01xPzoWSXsraY3nyJXCoRYs4FP1a4aYZlct3u55/Gs+qy9XhhhNB6IRgcuT5nayF
hrpyr8xs1uA26K0MK0NtLTkbh3BoDqM3StJ/y1Y7KWGO/ITNGKbCyEd1WDYuYwy7
SJgF8Fi29ic8HeUCnjWfRmqtIohObVxY2ZQ/597Ro+zFRDiGlrwyOIKNoaTvN0vr
v/X8Shvuxm6Rxdhe/zoC7oBOnWvJYIU4VSkgl4ueNS08Oz8INqVmMQ74RgLalq77
ctWa12Mckp0gOUb5x0tkQ+J5/kSY2gBIp6MOCRmEZZanEcn/cS2/3BDV2jewCnwu
ndsAD5gf1j83krlCSuQLfGaEAOJLdZrpRcpuz55v1KT3mBlG55/o8ffKY8ReTb+E
4ew0XhLFHSK3ULnxfpkXdqgzM6KWo5IXUrT7QbAFjlO51JDjsfmsNTQIC4vRcbj4
BOeUqE9YcWjSreYcfRihb8M7TJ8bhfjO/nX0bplb61GQZHkEYrQeZ5tMvQTuz3/S
QQGD9uReRqr7Awhc1W04eQ7wyrU1zYXVqWWhHDVdENe64oNA8BXN8gdIN0Nmj5Lr
VExrT8SQKTlw0PMTkcCc/kCh
=TXv9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ca7216cd-ab89-4093-af28-e18060d8d15d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//S6ylPScRA7qvuRxpAlR2DqvtpipROwBl1H8ml1aM+DT1
491jK7qKyNFxQenhgbjQWlfH+9py2FggssU++PdcU+1+TROkBGGHqCuSv8synuLG
EHd5LKOBOHdFA4zEsWpA5k8PEyMrOIe8hgDVUZ3t3WlCcL3S3Lu/wuqPan1BhBzH
xkvEKe6wtubVsrkDSsaUcl+W/gg9zLbbnJqIgoVyx+SrMn7ov66hOZktAzu4mw5l
rta/7dQV9dr/FL4aNDcz6MWHkWhngZwwJ++9EwkowNylknLGo4Yb+VTg9C96gQAZ
WI0B9O0U0luAwX93xThJCcWqYmshJFrMDnsog9dcUwgRYRq9HIJuX18ZkUvtsU+n
f021NxwvGbiXpXl6Xy+qD4bZ91uFfrA543/GIO23HlqiNqDpV9CGTpYBB1oPxUL2
iCVXrsRAbIGV73nRyOJIcFVe3UwLZeWf323LBOxSIELFZg5knPYhu8r3U6THS1gS
BOOvYe72ems46s1C9yfzyAve7DHrST261MUu+TpXTARtXolQcyYy8GxK+qQSEtlG
z0RcElljCXxZbfVJdUv9Ky9HB42VPLT+MSCP33ZdTmQ99EAHcWzKGbWzyOsN6EkC
k32ajYD09PW+Df2KEzvUMuHS1gv4eVXRnBwmyTtZlC0bT4vokJupt9GhIKdYhxHS
QgGxzTI69aeJxJOm+Pqlb/GT69EDtyWbA1ujBWfFdCkOwBvtK+QOGGHKUES/r3JP
5inpZg0Gpr+uHAlsPL0tci2TfA==
=JMf6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ccedb48a-8db1-4da5-abbe-f2fc3bb5659b',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/Wrw7AWFxRI1d6E0ih+YwxElhww5fSzgPuYGouyGc60ce
azg2oxSlvIb9WxklsbAyo2cF3LLp0b7j98mm8886niLhN1/UK+vUIuSP33AXvyAu
9qexwOf+GoeZ1ypsdOZoM0TsFq4i1xxz/EouwVZmiG2hQPi2DW7pr5yCqOEWCYPl
8WfWLwdn+BMo26Dk2ldFwhn6BxzodzOaZVGZWDinbrqByD08AH/srK98flXDjiKp
HkGRn2JFR+uO3LfkHRn0xe2s7hn0lBQh3tPG/T2T67Fp7R0jAQ/01Bb1xR/jDWwY
w4ityBgi+Gyoa4K3Bt0adb7BWP84dTSfF9GJLPDt7tJCATUbhtcc0iGZmDM/HQdY
5iOAf3iXZLY6grfgdA6/q2E3fv28e1X1D6WeMIKdXDCjQmFPT5bXaPX7AwfoBC/F
vaY0
=pnLG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf80f500-2450-45fe-abe3-3edaf3aed8fb',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/6Au4I4/CLbZYgtwY2daaHYM/fKN5p6fP/AqutWvmGAXVw
fxVRJSYhHYMkHPf8Lvgz6fQSD9BLIXuFW8uq8wNLX33MEgTU4y/KLaofrWOMBjZq
O67gZZueU/NiQ48KZCXA6VNTPPiZ9seNX4SugVEswGHnPMpHOGlj9PDqvh+tF2yR
7dIHoYQ5+KGES7aWvtN1WkYpz8//FbdUs+4bzmuC3hJJyZZfbxU0PfKZXJTPUVXB
N9ZEwJ/P0VReljN6W/ifw+vgm65U3xpAXdrUbJYDYWUjGmrYndWmfsYYpouGAXkY
xIqk8DuJnK2oWpsu+3/onvdl39Ihcd5qSnPQ1M1IEFg0GnzWnRVnUoUjdVBmd1zB
+rYT69fF9ew+TFgHrxl9KESEjygL+Bj+p1YSHurLDxCyUQB2wjemAUvI+M5shSZE
6vqgmDY4M9U1HLkjNY9nDzc+veXRBFUqdpbYam+Bg5F0LsZFtaVJ0NVglu7UEU3e
ziSS5S9dRsaTmuDG77MbWl95nCu2wZj6spA3KUr1zhvLPzt20pXLt/Iwvn33H9T7
HwBr+wVo/6fFzWAvob2Kv8qkrha+9zeEU86o2S13xuPtjk4VYJQ8ZLfV7HCRVO28
CWqtUVoO8LMo/thHPFaeidkKfSNLAClwFAgmzYeNRsZk4oE14Ofts7MFCZAbUCHS
QQGFS3cmE0vB4PeKkti+PpbfdJFU72B6nx7AQ9ronKuWgY2UF2elXBQCtPi/v3DC
M9Lm1ZSExIzIdXTFghieNxBd
=xS+I
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd2740f2e-01d9-408c-ad06-dca988f8e36a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAgIpe96IKhxtqmwZo2OlzHmVUtwXL8YU8k2lNevS+vOAB
aQycsqQeJBJTgFK0SE3J91GsDTs107W9aJCsvnvpbc3jaQyISEw/04QWN0bzRCBC
AmAVfB06MR6AqTMdKPI2kbbopGMwqVHgD862htRBigPZKiWX7jdm3QvbVRHRbqGb
ZwV6B4L7hFPNMvNxCVkImVZYMly6t94AAtW5DQ6bMrx+37Ds8MAt7RCWZdzYS3N/
ed4+Lv+tho913tI+ISnkqRRyg0SvVaZ67HwFvtsgO45m5NG8DrKxR6IN9Lw2mqtH
cAaLaEnxGHITErRzCgfFK3Ed/efq14kAKrAFxUB4btPhEINk/vJaAkiu8qiWaXrr
d7mIK8ClJFiF8vkwHHsdRk6YZ6jTSecLjeIrBDv4eRXIjMXgY9SlyavnkbSKbpkq
XCcOjDxVuzZje2ubAE1rWBcIY3MpLNO+hiLxLXaTSBifXTC0WIaEGPp2nan4C1hQ
pFtBvgElXuSYuifP9IWezClh2A4iAOU8AbplgPTHoj4y0J9zVUoK47p1Ra3gHFbg
zo0wToJBRMiPYrvf0dK1pW0Vpfi5MFb/sEhbxQLsE4VYCOyLc0fHQ/Io6CCMTOfE
WO1u5+tDGfZ74ZZ3dFyJv7tHozAvgJcum3d/60EY+TC8Xbwq9V3pRdpUrsyDuITS
QQFKXIzSZACnuD4QycNEW4+yyVWQmVUZw8hH866UnTUsZoalcnBzyrYqXOEapiEJ
clJeTYNGqo4vXkLQjt/jJ9ao
=9/+i
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd843ba0f-3808-4b17-a265-7dc4403e06a6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsvW60APIQ6tk1E8ILfvwipr/g5KHTj2LgFUAqub/H5bb
faGp7cVYY156yn55HQVoeVYE6XFIKGibts0gIxXlbme5MgWLFC9TFdfwCHDXedNE
vEaR1Yeub6kBGUn30HBMcuEFTUwZrtZzqhjTamY7toB4EUI1J+pjWeQybXAWNd51
q93juxRkvEGeCa36jx3cPA2xv7XBC9MnyxFGXTdzDMKR+TUQvweAGGpoXBDe+f/w
DU7FB6KGBdS0Uu4bTjpnk0qxknx9HZteLPFEBdztmFNwqtcEYmBDEkG6ixGxUKJt
H1tl+ue3dT8qh0LNvxG+sLD0RHLXcrpiXfdQ/NsQjREmKnoeFprKX8DCGTz6jjfG
81LJv91t4HhvJXF7nZseUimzX5aSu8fPKkL8YwXofJE2FErteK73N8KQpFH+JaU7
J+3Ja8f46IrSJeW7ZtFtS7M/PKrCtTHNtSn+Fpu2xcD9ccfEtlitWH8xewDZPeW0
/QTPS9A/qnVA8X77jRj/LQPTk4T+WMb3fXwODuMbiVnnbf8WibNm3Qfpd6ReZjmN
tRgi0Pgvt4Z/a04tuWlNN106RmYvRpaM+SArnD2Kz82IChoVFQtIHhpCEWSuN96D
74MwovtjX+ausDkRGrTbU6iH+H2aeTLARHp6ulPqUoPZgbY3T8ns8YLg/vF8VMbS
QAFAVzPGC0w7yb5RUD/RBtkQBZw2r7j4YvG2zxmrJJYuMmNTQvYNs+JO3IsZ86hj
haoYvAZF8OrMnlHiiUJLKD0=
=UOAw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd879d122-4493-4972-af5c-0325b5914bdb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9EA64k/+1LeMd92u2KgIaDhB9bdybjXAj5BNnUZOWjdB7
Nu3vXXAG/vXXy9hajlmFSD80fYFAiF3EIVRJRo7Wq9mL6/HTmofyRj+XW9+jeJXZ
T4M88vevgmGZYMc+P6Slx1tIlfjaN3E/N+6hl/2kHEYdXeuJfoiykObSAw+X0ki2
pbWU2W4ETB+IdiReJ2vBD97N0CYJ9plyTiGCo8Se9zPDWDRhrFEaNNizYykdfR/Z
nZ3OkIclGlboItpGuP5demjkYi7AmPMFXByFAwcwwR44hQxsbh+jugOCVOTwhN1g
EpMprt9wF/LTv4fwK0MpXaxP/fEQsg9jWNiamytgQkFCVRqjvHDNvRZF9+8PobOk
Y7C5fjenq8yBJeGbrpEfql37R5ezaqjtlkfgOmZ1jN9UK8tYb24PgMi4Xvi1gh4J
T+PpyT0cD9KAJf37xEFNbOXFCzGoHc+HCthOuT4oRKYfkDoZSiiEM89EUbMe7gv9
xbTXHi8M222oKcSj/2U0L8AOwg0c3p6S67LAL1X37sbTuzDoumYP88oo+6/yQiiB
SJHhlKsVeZc8NiwxNX0dYpvDcGgdLq/Ono0Ol1ag/TunjgmlXVrJIGPP5KBm6ST0
AgnLNQXpnWE098/IqSWLPQb7mhOIZ2IqpaZlBNqWngT4EPFbWegF9TqbUDROEPTS
QwFOYy8kXL+SZNFDO5FcEe3nV3olWciyYllvgkauoFx+xp4S/F9v4pLlagsWjShd
2lUlT8p3NKICHXM66gQ/U42YVeA=
=h2aZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dec62354-e777-4513-a1e7-7696d0ff220d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAs9AJSM/oqLOXxxkv2VpovAL5+U9JG4H0ADkHG9E90RuZ
ovTuo3dWzDgzNNq/jh8H/j3C7DJR2SVZdYJIl8lkqPh86xWzK0mN5PU6KzY2C1sc
guZ0/AoaL6BboHIeufZXnd+Bc61IzeUIxpg4rmW0+Mc+iZgecNfDs24l6FShhCGe
js+vb57X4rsiFd5WBJ4O1DPkEyWsfnvcSyZLHHW6gdH+yZg3fWCK5J1r8/V4bgSr
SbUQ2i5dWl5SqYrS1JpdzTRqCLQL/XcObozYnPBUbt5P0DY2Q9GybIA06MncIkhv
dNROvvkKA3r6BWWwfMHaPSsd26RE+ETdIBc0RNld+jq/bTvmuOcZKaLJwLuLxOdw
EU8z5y1J7+5lZ2cwikEvUVR1D1uyisuH0BFvh1vqU9p3rxtv9vJ9vZMtbB8kGYQj
uGwkQjSwnUV84aF3MFeKFdjtZsdsw8634JuPcieABX9DlYL/QOxp/mhW8PzYpUY1
nXqxrgArqNEnU2Y3oUyN766YlZS9ScDdWsAZAYEmdR/EplOZGgyImDWjKuHBTACq
zfYw2H+SMFGGgD3YFP4N4l+YNpi2D3WU50+wd/0Hz+pXnLUe6gfp3lv0KIJ5vRs0
S0iAa4Zmdf6fbZc71Kd3MAHpqgwikm5FwkonhOxBOy/13ln5H8O/I6eDvjInH9XS
QwGxq86AgnmkKzf/ZgJj74Ldk9lK64Fqds92HxuXZNXwphcMo3t4h4AU/WPkQDiB
7k2ER1XaIyKSBG4WbVZL3RMnblo=
=xi3Z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dfb03f21-a3dc-43f8-a419-11f62601323e',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAuNwI02gY+M+vcKZfLmCLt20vnLHVgS7PIHCMbmXQFl9C
pdCl+OUnZQnMH2GMZecuax1A4CA/KmVN9YbVAI6cSbVydAKfUJqh9EQlTvf610i9
JelltZu/931BgFBO1bjA6OqnQHcVtNfGiOvSORumYpUb6WwspshB45VcbYxAjGcq
3cD4ye+sQ/PSJ2MWmQuQ13BEOHpGUViVg2vnEyHbvKL6S1KaVTdA81aWBVfPkO3y
at4Z0Pid5uvgXtR2iyLsS3zxuWYf6mKcVbb6E+xCwM+c9igyfr7dptxjGpVJAt9w
r/3mdajGV2eKGJoNqrdgAoqN1XY0wvpi8rGNs7LIntJEAQ+07AL3/PkkGK1cwSrl
fYTOcSvXi+YN7B2Ms+zZsUrDy/X3w3mBpmdCQXjpie+PH3qME+5doZWs1fMxr3Ae
Y/6kksQ=
=mjGJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e1190261-6b80-4c4c-a774-54719c7a4931',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Tg5ZuEHazvSLOXSMoKYzmun5pUQPJ8PWaiIyct9OkD17
OJfgRlbXBOnkV+31kxTA74DVDsdcouG9Q0CP1VuMXVtPkE3Bosjo0AG6TJhMkJ8D
ilSbGDTfFSoaY3lWQ0LB3ffj2VWSZYi3vqfc2e/x63xgJ821TAU8W7mtuw/mRnE6
W/1JCLIyOSydoQNvGFomzwUmIPv9HmXilB7hnxNi/cVxQAtKGc2vW9NatPr7znEI
UsprJprEDGGx28eM/rEiS0psfnLQv2tSaIZl+6P8N4gXQJ5RZ0egQksCu7NoVHnB
eX0Y+BoTQng8s0KCqcX/UNQuQ4yQ3PsCOIyfQ6tX9edKV0EvyJaRXtF6+Bgv1+rB
xqBPOsOxEsEiPHnFSJYXBwCKhBMTWoT42LDnPAoaoiH/EowaMTW+JDCrcp3u3DXf
FqZKZI+atlqJDz8E1LzLisP6twbu7Hqg53zcnhNhQP2bls3JkYcvjFd5qrzU+KTa
B+cBNrBT1TFyKJNtSheuquNgVCg3FmA9HrtOKl1OrFy8Q3spVaC4n45z6FvErcRv
ZlPzO0ZsP0UxVm9qi7Mr0iOKtCWaAqwJ+7iWP4UHLEVvaqvSOhvi+qSzouo4/bmZ
KwOE+q46xKsptVns6M8Jmpm5nv/WgnAiLDTO2e2zKL3om3scHhmF0I7cjajjQQfS
QwHZicw5zcCrRmHCGFO9XnLr1/d30VlfdaiSKCkB+Rgy1SEliPUulx33K6vb4k6C
OJDydXB7JASr8/uW4c6Yooru5Tg=
=jhUl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e518ce1d-59f6-4d03-a0f5-a6abf61e5d81',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9GlCpaEZDtlp9XjOjmAa95IfZKJk/pPNYcbR5fBBzs4li
6AHY/1lyF3dMBHsIJFZ0TKHdwHLw7kqK4ITqgsI6cFfuZwxg9yrEv/N2Reb8/wBA
F0tZ0PBCGkKdFQbQWsS1tn/EPKZ3mH6+uQN+fNGCpRb1yfHj/FJxy7rAmoWHFwb8
OOHa+Yz6bVYFbLOrC18SE7t9v7XwWhP/YbuCEO7w2INFI/maXWqqmUt9myH8R126
BufkjekI26Y2RMRJiYhQP4MccExBZRa/Ws7R2qIMBYg7nc7C4+GLe6LCQtWOOB1A
n/0oI7bE8rLwtJdB3eWCtbzqz1YJsdSDj3SZQEw6P6yreWmp8hCkfWkqH0i8jHp3
f19Cvhiuqa1LI6y3UCP++o0Dllsho+PgrCFnyyR6kR/S3nEoO3EIk5TQMi1Z0Mbc
n3x6wYftSB9N9lvnKfvVxijTu0viPiVm9w9Rd+cRDRH1LXzOds6UEjM4IpDKQ6SZ
+pIBSYmWX3AuOhZag05WQ9WWcH/09lA6nYhucAnbP6TtgP6ZtTyMpqwERFhENG+a
hreELDDfOS2c1EsB+jJKbstTEQVxGm6p1QJTYc9k+vnlIcQLjIG6BCdNxMpQJl7g
eKUB9mO/lwtM3iGKqlluzua2JTlwHEez8JeEiyfQ5CR5pBUd1ACXKsxfzIE46MnS
QAHwWi5yzFIt+bju6oa/J4WWs4plwdXyOkLIe+MdYWdaz41VmwRCBJQBOa+9C9ox
aE5GIITwrutQ9CbmrJZsO4s=
=BV/n
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e61750d4-97e8-40eb-a78a-a80e1c8c1cdb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+KgdFyMn0PX+rWqBiGgITRarZWKa4mq4jg3opoEL3uYll
Is9/hYxW462/w6UY415F/mxbdiLMfK8hnSv65G+TOn1oTNTfE9MnDj5egToyxhBL
tTQ2VF2iogVEyx4sDnHfs0sz8M0xNGORuw2N43QREIRiTKCke6ZwxfKN1c5pG5V7
29y+sYOJUsGjiSu1G/5F5Wgz1H/BFu8ryFQXkT3hWULJRi6xpduevA3XzBX+w07a
kgbHEbCG95bcgrZUOahH26LqJU1AbrT9Z1g5oFHdJPmD9fb7k4GzPCt/1mECYUmY
fGCVvEEk/tXQWOWWwayVElyc1klFaWcly5FVkKdisN+hJRSdhaH377cM5WYrcS5J
WRAM5GHmNbcdOqIfokNcZ6GWkHZEhnOzEeZHvOv+oQY6/EzyWfJhKx+tmhLElbhu
gbjH+ZWIIT0aGhmhJV7iQI7hk54p56Umj9d5Ka1CDu1l3LOT8e1JXcDpJs3NFP0Y
r8DuigySmOjKGEzqeGZPP58Xl1zSXDaXkqrSakifQ6e0/7sOF16Y2d1dAzp4s1bS
CTbLJSRFpKdNFx9aY7WOnOns/AzcO6tA5SnscIUgRS728Jb62l45f1cX9Yb0KC9M
NlE/aPZUbk6ZvEgkUKDgldFQdNkDOz5GjHu3YgCXHg5iUIUm5scl9uo3ylEUiozS
QQFr9QSejxLw5CxyBBlkS0yIykvghVXWqCHRK4P4u+ZpChIkNzqZeG9v01V+//CJ
2k/u5WICrbFV96xLriGAvi10
=sWDa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e81b70ad-5f7c-4b7f-aec2-587b1cfac892',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAtSrkovIhUhY/6DC2qltSrET/FmIIfC7LIUDqoCg2FJ27
O4NVrInGvHvHBUbuxcKGZVV+L9rHtlGYSsMY4kE8HXP8jrcZvblkcNDV90kqmZnQ
7McUN7H3j6afEdXgJwxxpvVar7aaS2+2YjjDa9cN+AJxcI3d9VKVF5i///jjdu4C
ZFut12AzxFYS7VWLLJLcd6JFOQGpsCEh6+FYxE3m8rAigzZPYpQBX3KgXBFJkyz0
015GOL5jqqBucjUh+2P4cbuRnwh2z9UeU0F2gB2ZtLocTuTCg3irOnGWtWRp49Z8
dEGD6u5uCpoClT2Upc6JjfFpaVltEIjrPXfHjLPqs9JAAVc/l9XwB4epcJYa3o82
X8yTNVEZJMxxMYTKr3AAxkN6pTQF3tPMnRo7pP1iN8D5xGFNWhIiT8qwjIHOuSG3
dg==
=hbEJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e9df84ab-bd58-43f0-a112-cb4ea4f7886e',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/SsCDgWNdNJ7t6JV/g7Ve6ioKI7dTzg5QOCow+yMQVJJN
NJ/9h6J8fFNxIq7KJhiVqoL8jL9rEzxWH6HwXCoHBqMWEiVAUSfw3+1y9QP0uS3i
V5TFvkyzS4xK1n1oUxOCqV9mGC0Meb9MSeiyiemzjGHXJRLaO5sobcty8UpQgxbn
go70mVbm03e2mTfXvLO8M2WRfwO3h26eJWyrcJjMZsjq+v7nN49ppFpIlN9DNJUX
d56LkPZvWUovShROeOIOuJZSL0xXp2WSrIUQ+CjwhWZ9M0o71E/RbaWBklfZ82V6
SjPs+GE3I/bYiFZUCCpdot6oeeoXEKCVuK3M5F1tVdJAASTIRO7U0I0Tejnl41+a
VFhcC31rYEreS10utfliXDn038+0EAteCNa2GUm1dlGPIXryJeh1OElKVzp4GANV
+g==
=5azz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ed286c49-a237-4518-a861-1e6c91639a3d',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/X+pWt/O4DQAIb08isd2snf/rsB38bqQHwsAuIa+D/PXG
QR+TzbqrlpTrsalIn+FxFJYSERRqEr+4Aw8C6JJlXMmiSeRVp7BD5yZX7oi7PLni
MpwT3zYsGnzrJaZSyDwXZw+HQKs6V2EjNnEIDqCdeDxdwmgmdLBZOsPbZTyajq2v
SR12Nem9LKHfp/Kxo+p07jTMZZi+JqdvfD84qi0XGZsAc/jlOjsErfmR0tf79PKC
akphns98qzq76MVUou2nOQRCLL4LsJvN1g/4MGe6eZVx5cXR4ievjFDXfnFQCaay
UQM3J27YjJz/8ZuJT110LsTFVv1RzSy0fNcijjPcm9JDATMvtGCZQJpwdZpvy+TI
e/TScVnOqqGEc9P6QoLPruUmJOOpyA+Ih/bH1S1dNwsKWsTIrQ8fov3nWjWdLXxB
V42wAA==
=daYH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'edc9d7e4-01c5-47ab-a851-21e5d12a0776',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//QavT7YsI9ihQEOmbO04L+Y6OLLCKumpSa/ZsVfTxDFgQ
V2v+KKbRz4W0pKPSi5jssEPlcBRtgZs35fVY5WPZjQvf0SjGq7+RgGDwZ/4HcYoo
V/VMSSK+lnOd21hVR63jKOMxfLNG7bONlxCEtw73wvKi415twU90uqaAavo2exTh
xuqn2lAk7emuQgjjmgXMJlRfs1H/2JwjJ9tBo2b481ZJdhzzn8CXq+NK/aZ2/yoL
TghpnH7GkqHtcPjwc0pR1Z/+qyPPp6zq69OQCxhFa/WYTadZju8L8rhtmZy9aVte
EYrYrl7ccdVXfczd5cRLE3jXFRKoHzTn73RdG72jawgyRdNly4vcC7SZhxbfO+M9
5CcU7KlEOTVbC9T23ntXlZar7WokkFuyUgFvrtw1t0wIe2hJV3aecHo1oN91MR+O
RlOHL8gO4i+atJSNtDmIfWL1Anfj1KHrSUUsU813igpqC4O2SfEhhyZeOIjU2vtY
JC5OG4pODISbf40QKE+3abzJfwIshVhRH1+jCQqvpIUGp55/MkZb8j+5vsYUvRzz
SDwTbXBCvNIYS6KDleijsvA5tzzJ/NBwjk0p/3GjJnPLzih049a7CTFYERjxnOaN
2cA4kZu62eh5/X0Cp8NWLsnF627VFKP0Kg7fmy4Jgcb9edaRPA1vt8j3kN467/XS
QQGW2RnUf+YXRJv29t8aYS348aRGkXPfG3PGUku1bbcle9FidVPhHT03au1Hm87X
HXogsnESVbXip9g0dRP54/1A
=hw9o
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fc661cca-1475-43ea-a0c6-447918924338',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAtQqSUK2YRrueAMDzKi4D5zP+nqtjSQzYPcwt0AGX+c1L
z3dlFbzX/hVKrXcDYG6//GdAdV/YdQiT6ftcTzBYJi3FzRooTZ/mu+movT2AxyYy
cWQKSmfaYCoUxOwHTgGQHCU4u/Ue7UMAlkL9hSCXjxCGc+6N36sUO/fp2xXuigoi
QbiU9inV2S3x3Hr59TOXK2AHIxQNXhC+hQGVnW5+RWvWrJ2PYYiV/p1mH0XGpZqk
Uay+NGN7fR2IILzxwuHk4kKHtSNEdwz04Uo6aKfmtjhmfP9R+buiESGBJIMGbp0z
fapyoSImWmH5yoalbsK8+Hpuz6l+RADx2JAcLuYuvFB9DEe1GJOwdhNcn6Eh/pwc
OzbPAhxSTkU8MnUjDwhn1OB+nszVDyHamTT6lQ+YZbxpBe6hxLgc7kqpUkWlet7M
rTdJnfMiQh/EwpYygSZ1l7Nral01GfEIrg8WnADoUJgpoLoC+/You1eY2FvtdJIm
QjaNGTVwDK5o2QySOoN/FCCJGorpkXOVUBwgp8Cm0acdFrCoY/jnSj25aBbXHkzS
XKyKwXqbH536fQ3ZbDn3T23XC7+axkIYvaFEHJ3ZwFVN7ybqTKcKZ4E2EwZuxKRw
Yb7HZ1M9f6l8pp5bQy1o0FBo6PNEt9IP2hQVsFH+J2bzrYFDM6CkCrqK8UQC7AnS
QQFt3eJDB1ehYQ4CvVcIcP36kL+rhnNwV/gt/kgLki1Djt5qcu8CekdWRVMvqjo9
KU3iu/LeuXYKY0+FUoa5XCg1
=xZvL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
