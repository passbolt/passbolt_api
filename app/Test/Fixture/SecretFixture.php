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
			'id' => '01102cdc-c044-4baa-a654-68563dfec268',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//XA8jClTHa+aUqMW2eZk4KoYCnYNmNWM2V8WXnrNGsJbk
60lkK6i2pnIBibtcWfbF4itA2cZU9fQtheUGMH19YySZZl9Np1Y7r/9mJb5xn7Vu
TonFQ9yUYuNFc7dOX5BHTis2FwFsjqzUJnnlk5iPEJcczszawadZPdaQo2knkEZc
bMvXoSkWpVS0yVPKSCuYXnwj2X47dHqskoXOCuJDb9O3vJcKSmT/Ln6sjOdOepr/
lsHF0pS/4L7fkBd9sMq1E8wsajztyLVWY8GseBGRdtRlsSlA4xhi2uvJOyTNXIZ5
TkrFKJ3XDnLKGOYQKOJClYXcO6Pq4Icrirao048wOJw5Pvn6UeoheP57ZT3dewUw
+ukaZ8GU0eHRjC3Mxip9nvWlkKE+VfY8mCEuxfFmrieYMmH4r+Xf8ZQVMqGJYtKa
a6L1HtO7z8k131nWuxujMXx6nDOOKLVjIL7cVAnCgSjXie2ZDs8qqs93YqI481hv
LZqX2koOAXd3zU1PhyLZJB4smAsMt7xQ14pH5Qb4uSwkaywnFKUDm0xS5rURSw3V
pzEQ2ziS7qvZJXLunaK/aH7IxpZTqoPRzzKSKhdjVa/Rl5ESRTMl/oK/4Ekhy6Oa
DxsCanQHW+3rVktu9KF9YZknPdqhUV2XqsOm6tsYIc9/9/QsLo8lOWuRllAGnQrS
QQEOt1gTThM97FKhtrADtZnLflxaOuUVMSBTZTUUj3Q6ZwreJigoJwwwerPK7pdy
1DZGBh3T1DbN3+ExYForANUA
=Wg2W
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '07602179-9dae-4894-a8ed-6324e7d9d6db',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+OaqorzSnXm54xkY3H5nQj8cpOcD6vrwbMM7NKi0PyrDy
bp90PhEguofPTEzmyH1eFC7AooM7I5wUVGLoeIVKm/49SyBFMje1sb2cEJ8zQ4dQ
osJoGDrsN4zjjA0U2GDc11QAh+t1sc3PyaY12ZNX+tT8XPT17ZeOdS8mp+EixR5k
FQA4pDLVd78e7oVJvWEUpnV5WU1qAt6fAAWJpZFTe7hLdb0G5tbaX3S6upJyXcQ/
s+73AhIfMGv6PWri6sayjEtHEKGCszrzuItxAUO04Ihsw6OtuRAVYwQr7266NJy7
s/Q0NxjWSH7HK78eKDZTdeMYNHXnVzx34uoASkhpHPkc2pc7w/lGTP3nIg6agdAq
+MburVSBNnRJ3yafXH+nYDGD6iJIWTBvD/JidBjC1+oBMeZtjVyPT88aiEuO/GQj
Kc/WA79mLVm/2PNWfjZLr+Y7S9KfAMSJ1IFRiw/6k1jFH06g5VQz2UFbLiVMCZny
FJNsBhWoPcXztRcsDWcDdjn2qlLmOn5M6OO7HFKEvycvsINqePdaqXfhk1eu++Zp
b/cyI1bYllIuJBsyx/+HAE3O7WPzfqi5y8kFBSeQObSVq4JgGNMCOcrBp+G5zM/e
T9AUuJqCV9tydRHHqKKaVbxKyTPZT6HIk2e0j+wAS3K9AMD/37/6ipdIuHrwDZnS
QAGTFoUxSB44TLDrLAFklVlqnl6qnwTeQHlWb0TtgAVTIUYvQqkmjzQikQcBya8a
XEZf8cTohHGT9/lQpeg9OqU=
=gdIK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '09b3338c-61fc-4ea2-aaf7-ce5102b5e819',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsK0HPETIC9lCjwrgks5Dm7WKsd8v/osluLylTOkMX3jV
rfUyU4jRP31vUICQ3Wr3U727zTtXjsfRCMxbatgj9Cg73KhvDrHxBdIN7u1oa2wV
o3FcDkK8FKPPP5N4+cWdxsTZu7tE4xt9lM56Vi/ZpaP86YwBAkSrUyQhuyWpoOr+
CESSAYs0RWUhQ+I191YiNUMSFun2cD4jVJdkkJHNvNbfuGGZ/2MIsFp45XHPYs/r
uZXJmE9JIfkchtMDYMMfpCbfD4Fv4L9mYrWJJiqoyDHj/gVAi4zuI8OEZ60HNYsI
gsilw0qWMwxbObVb9qVyWRIk2kn1hjsKbC/8DrMbl9oN7AOU58eFV9v7u5bmYWHQ
PKyxiWWb4+r8NqWmpsuD8GlIuc371cuxB4T9KuCw+JHAmtlBRyqRZguBXSgtGnUt
kPf2LfaPpGnF7xZAovg8NW5eCwN8i7KrXj5L7RqL8/G17Bligt7Gvn/gclTgmhjJ
Jn7MxzckxnXxTNLqKN4Z/Z9M4gkJghoT0dfrnlZfq9Lv12R/DzVmlMI5C+OlwDMU
NQYiZ38YqKma0UNDJ/vnM+x5yV4r20k+sMrBPyrC97yXKYuatVyearXD17UMBMwG
D0lMrtFz45RxSbSSYF0gTZCUebS1wIYrfr5EmPLcC3uT2N5Nhu+SRTJIqGMPsvnS
QwHpGJ122S1FJEoHj+Vl95cqJ6/dvYSnY7rSCAl0XDv6r6ni76JBcrjhWRvuTIed
8G7OSOOYLCUirGo3UwCUATtymbU=
=As/R
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0bbed281-c672-4c3f-aa0b-6674b6c2eff7',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/ZMXuALisEsJQIiWJ+frpMDEJiaSz0WA385jrtFGO4BgM
vP0V7N005zDdCSK+tw5i+a4agSsUNy5nd/IOPXlKUyvwRPNrea50GqyGW//Wca8K
kcMd9qrccyVDLm3SiyQ+c4YDOtA+uIELMQrlh+hOzda7Z8mDYHvrtiiYWZoFbYjH
r/CteXskKVgFW5RdqhQr63T0+RqhUtriZORkXKjx1odjMw1299HQUAruPXhYbwuB
aSRM83rOvusJ5vHM+d+B+eKKgWl00hT/QgB2KCo2uQN6thQlKpSdaOgcKN1fmQ1/
lRpk442wdDTJA3c7+TeYlP+p5ul1JF53y35EoiJbAtJBAVx6h5juQqiGteJWyEQv
w3d/NH/2bMZMKBFjHDDKvBBPi/IS73mccdgzILKOs5PfnOtPPuMwn7AF3OZ6QPR7
H2g=
=KSWF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0c54aacf-ff8b-4373-ab96-69c4723cea71',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAuEthFTg3q0cQAiZWlB5GNowuFAtYGuY1Vd0VogRhM9+f
/00Lc0+lD9187MNWmccQJTLi6V86atCst7m1aq2mDEJqqxC1A8/9BTLiQbqyr/Ur
/UmsrYuYuOBtUV+GvkmMuOZvsFqzWYwlDDhJ16wb7TFjx4BhXExeEVsp1cEYV/sD
WBXUJ3aoEAfkDFzHXrqqafc2L6/PLnZqUW9swtdJcKsUZT09aeUecE+VL8xdO94S
dpFC47hRDhCZlfYjPqlVWnT0pOf2/nOrnVVmkyZ5xSD/f97GN9gOOjH4enwbX7t/
upqqG17Kp5TYJQeBKCn/2dYWZqbKzGUD+GP271RHvNJBAQcMiEQsiERaBNRAr7z2
l7Fi2csFl8qVLEBwAMLyNh8kma3ZPiNV26doDAZHnP0FBlLOxb2HZWtZjiebzJwz
Wbk=
=kDMB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0e1a67eb-7d2f-4780-a1b6-d5afd1c5cff6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//R/bTwwdfsNcnly/aKGZu7oxY8lAQQy9aqRhDzCDC26rE
zeDOBPVbgKBqs9j/T/OYd5pjcVeXRXG3BPNktApTxuPt9llTxww/yVpr5jsEgptf
TALhLmKdfzIGJJKtPdI7kV7rorK3VNoRjbkvcWM3P0++PR/1gvXUjocu1aqewwxO
MQtCJsTHUfmiZKcUQ91s3964UkCy6Was5HP90heQqq7BXO80lh6vMz/xrt0aqiw8
1RcWBqm3z+athpe8M3+7u3ADQMr/XxhIyPzh1PHoIrm2fumuzRt8gAzPwPmaYsgW
hNhyK2T3pp3rrAGxfeAumyRAb60Zyny/8iZJ8E4xZnLBttVqG5fntWOoqBH2FoGE
NqtgT8dBoLMsp0oxO3XkYerAX03uDBDGy5BZn0mSj6y/4cJlxQBKi126tYVHEbk8
BhlmndeP5nwoB0ZRxSveGP3YRVNC5IEY/M94OZyLNtwcdRB2JZwafIIj4sJQkYiB
hOlKgeY825BztnxMzUVByxXrDnHDZj1qs9xZE+G1LAEowD0+kwBKmfhXLCSciowl
ic8mGWVQg+uksK1Jc8GLb+HSPomWhZRTvag3iR0GjohqYZwxY8kgg2IpD91TUaez
TuC/FkIykx/FpC3UG69eR/Uo0m18qLek+aqqRHxHVQxd7O2+eGkjQYRnC+96GoXS
QgHC9XFUzg3b4bybJKQPUkhLM8Ma6MP8BIvOWFuOmeo9UgZM71O0yDg7NCrAGI4s
ycd0KRPuLcvS96NQl2vgLkBzFg==
=HLbt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0fe7f7d4-23f3-4e13-adb8-c7a9e1bd71a5',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA5VSF5gRr/OTKp/+HeOWKBGdRLjc9IE+eU8KoJhDUEJYW
BbEeoJROKzz+EG6XwEqeKVOCuqUO/8LUOtMqcrnVXb+qUDmjbY3oGdP9OuIjJ2Xg
TePY6lKyu/iPO/6ABeOpAuKtQY80EhgSrzK010dqqZh0Dbvs7pyUXwS8J+AKwy0V
rViB/TYIFE+4/aQZEKT4DRn8JCfP5RmgwcsNN9yHllEu0TgBJxxCOlKk/dgG+MTP
jQNy5fMlDeo0GrPNpLPybcYYCZh+VfD3/eG0jX1y14WQMk939mndLuwxwlyb7iEd
zKvyqr7Zb2ue4Os4367YTF2Am/kWUQ18kSs4elcz+F2JvNAVnQJDRZwQN7rX0RCS
MnD9YfC6RxFHbAs8bkeKlv8xuxM68TgplFyt6Sl62DmFG76DyA3ui7SRgJTYNXGQ
P0hLORQ+lxL6NoSwaXbTIAssMOWmXmcnoBY53w6nAX5HCBwxVYrndLOnSizCneSp
lsG8RZRccEMpN/k0Dfvk6WSWRBC3U5HA1BqnMduWseLgwGwSXlUV82vqhA28J63I
3tn/j3Lwx0I5LsBjZS3xiTl2QNjnPHOXW6j1PPIu6NBa4YQ+889MTRelYpWTH9ep
wJxlBH8VaZcrqxj0E1XVcFXlxmbsW+PNkkCoDu4cSYEB5+82AVGxelWR5DLofirS
PgEEdCY84UAiA22+qtDzRt7yEyBNfbH+129zRZ/dxO4l1P/psu0+VFpNP8qLr+g9
oW1ZFrmxXEBuUDRK+rxK
=aj/8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '13606dbb-f59f-48d6-a4a2-a54cab89c8f3',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAgmsEAeht6BefvObkpsM9FWe/4RqMTP2hTy3JxDIShLnS
aBfoWwoAeYEJNqaUxdo/UU0tcBRUf3ra3b/aEXiS+AY/AxPqu71R1UyWnPXUCiw2
Oc0cbRO0sXn3nnEpsR92zvM6zvvxBDCDFapJCFHazgnsbEbpukarWxRiQU+fdTXy
OnW2MYo5iSX62lSGJ0NIVBvzyYGL0nyewUK0j/wplI1kPnqUsMVmxAUEXqayi3/Y
rX/331hjw9+rEkw1+5RMVjpOJUKtfe7ORnPlXBrVfBSbiB0t8tlaX/yJg6+kv2aU
OTeCu/ulIkMoPB3RgWYl3isKMspbuGwj7zjhX2Xvxd8iewEKMt3+l3z6u0PrcsvA
lpWnEWwxtn3qZbyJMDz945L/0K4dHRo8Nm8PQ7SgKp3T70PoAfG+BXAiA6BYGj+8
yQNdDyovNIy98uVirYelfS5KtWYNFAlx2QVOGGlVh+FNRfU7vQz0YaSdALnELfYP
D+GDgW88TSVOLx64Fcqseen5IbMC0heSig5Caen/FSLy/MFgwIJ5MV434qfCyvgw
AQf8wvlU9XdC/fl3yUU0fSZ3K25ZRz2OnBo3lQ4l1XQixCu2madwkKFJa0cSdXIg
3REe8/9HsD6GfdOBJj815Ii8q4t5P78AN/5Xcayu58m5l6ekYGixeYWQdKpXGdfS
QQFvFPtsaM7su/JvN4+WnoyooCMekckLHW5RN/D/0Q+7cmL7z2y+XlCFpCId/Ork
dvWg4qzKGdYwLw0RzjdCeW9o
=G/yK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1513d769-7291-40de-a337-d35abc461227',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAk/PPzQuurPLCG9XTb5I+sXPgGtC8o4ie/yDxXPN8bxcy
YgpVXoAyFPAH7LdqIxtzIhpzfLRcGOuCdh2gT3GcPbksQvJVrSIOrXcBmcjfIqc4
2CWU3FcTxZCVQexpyyQsCbrWE6jSc2f9BfvxDIf/+CkwpwosddcfVHlB6tzMkTtb
B3pcNlnSenHGnj7oqRr4AIDXZ17qH595SfaajwaTEMtGyFRSZZxOZzVvXQQDdlAA
CdkgSDuNTe1s/hHKUO+WKTu7qqgvaZSdRJqAgViPl3hci0/sW6HCJd6+wr9rN01b
JEpLqV3cI1YrN6iRcIPBdS9J1ZygC8ySJD2gcxLSu5fZau6fFpBCobWF0/bu4gNl
YnZxfW+Dd2kakqRgzb4msby0eTVBr7NnIHz92lq/vVtRfkGRpqQ9FoJloYEIY6mk
6te7JxCtqMS6NDvT4PhUgP0nThku4XgKdO5Gv+XDJN7Xqz2Q8E8d7yb9aWTDga27
QLT00kaN/ErS8Vty+aM95IWTTmp17tExgAtoqjDUCYaegaQa0mJZgXo9r+SrlIi6
sK/ZK8ViAjdvnVVYkKPBk0wnH2EN22PUIlvaI4fI6pWBcyGkbQ1rO+oXBAA9doxZ
1LtAYEE3DBkg4ZDQ3wrQFzK9UFU9S4QDICU5QS7HYUMS6Be5QThDtFh1bzZuSCPS
QQGa8CfC1rwA7s/bxkCf+WOBHd1vaCwkat+fIvkJHND4Y8+BES4NUaBgTPtnu0Ay
XeVdJanCjKGC/SkfgqI5FirT
=U8SH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '166dc36f-c228-48f7-ad02-eecdefc0cdb8',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtVz4Fyt5fhYD+Yt9suhRB5BrIBt6d0N7H7JJ+AIXaskF
JPKsOdReFb6c+DlSXdTUdjTlA3JFQj2x62HPImTFOqsLiWFXMjVY0TjFmFJBbS4v
WjPgciK0vc0Jgzm0vIfPF/PWwu6MMNyEz0ZWjWhnm5hffLKMTUYq40IEjh9z6Grm
JVm427xqMIhwKt04RDjfAjT6hQhXsMGb/tiAEni/x5Lso9+/e8IgxOV7eYYwofJq
vT3hTKjRVsTGlAx4fGY8dVuSfmJnQiYCyxdtIS1U6p1BLYFYVlwtNRs+8//ZY6tg
lVML63vPogIhqxzEiWA8UJdKORQTwZiyzP5xvUshUbu1TDsNOCaKipywWqK4y/iB
CH2jVBFndSEsSykgpb+AzRMLWs0aaVNaxP+Nl9zjBfgXs3EyQWLX7RwC7OyZlU8L
SEuH0KBXUH2uc4b0xmfP96dDuvVl//PswV1xm5o5bnsFUs0b1MIQYuT/RU4gU1AQ
FLw5SSulQDbN+/s5Tfb5cyz9wYlbpFd3ar6bD1yFmNCJXNcFJ8keX5ygHesrHXim
vGFwHClWqvplDamgcZ9LbTJuLHG/FFK7QUZs46h79mOALoJmdTJCse1fb7m2ouqS
+cm2IRKkVTYgcCbne5Jy9toRMB2EdhQgmsLpV5a9Gqc2zJ9s/gQslV3OpuMDQsnS
QwHsqaIIa7GwHPiV7vRXqJsHTjeHcfw2unzm5zlNT9iBvwwM0HrSGKkJQUMntKqP
a32YlX544f0DW3y3Yage1L3SIN8=
=cd1U
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1c03098f-6b25-4923-a90b-f9085d60ad40',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+OKD29Y6y9BgJrTSlhKONfpHxCSvh5QM8MBRVgHQP6zup
XrGZ9ntAdxVOMtlliEZJzn/XYON1yobM6gTDqfReVORh8yzFpfisuH6eXEighfO8
i+PBac412ZhRFPSpHo1++gTH1JzCitnYMMoJjZIo+UCvUySOmckHFKVrwFg2c//j
+Gel3b7BfaMTHPa5P3EhtmHvC9mTAiBN2EnZo1PaFsI0aebHOdKr3NExlXEFrwqZ
ywF202uFqtjrUAMWIanXzb6S8jIn1eFSUhO49m9FpToof74cgEpuUibTrMGNbsFU
JNwja/JYmfMmSBkcKrZZKujzLc6LZWi24pqtrVAi4nrugOaIIFvW7ztQ4lzOkZs7
DI/f6x9NCRwp3LsuTeZvIiPBVFOi947bfZfJLzO90olrKPfb0tnAdQ26TG9sgy4k
y1w2td1NTlyQgOpLRVkaJY8ousqf/2+czOJWYLKgQ2Lt4tGPMTYeyDoz6JtGnoFe
DmZ2kwKhFv9KJTW9iUEJnhrQrTD5wNY56AP43KjvLy6pv9X1QP9LFdhoKJi+gyvC
hb4uGOQYaZK7jfvx9w+C1JTQMw3qPbBduMtuCfoW5Xxr/sQBm35lb3oE5+/C5Clo
2EB5u+ju674fToLIksyruO61ImVoIqyAWf4+78F4FJnDAHqGKQ0xkpPzChpMChnS
QQGq/lpfpn7YwZ6HZx+YgZakqSgUGmek30m9iJTi65o4YbOERAnYaWXJZnyUK+Ev
yjWplmFgbfXeKtem4UbDbBif
=oVbz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1d72a9a6-b315-4636-a8cb-2258796a0c8b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//WV5cyutgRtktPZonAAoamQOMc5Dw3oVW0KyoIJ98BB32
ihKa/wsgsgNP2tDRn2+U8sWHX6bfba9PzILZ7QBNA9zWwVEQnP3v8rQ9xjpujoKl
bPUL6JD+tXEdfpnf7IDzXr6Dabw7CmM1asWnsId+5qN5DehK7gb4yy4Szcvxvr8K
eAMvElFIKfiqEZvOKykQbLkQV8qH4WXrmKHTmHXvBe1rDUbmuuuo9wKvM7BYQ8Yp
JM1V4Cyy2OVYJbryHyBtoOYcH8tx8TcZFd+42/nGlXPqlbjDMsbFfVwM8JhosoK0
uETivOH97hRUKkKNiZnmusi9etsmtBE4p54q0QkdyC4QAseRN05p2hZmFqAjnijE
DCOsT8Lelw5ZUijBt4WTU6m+H4YpgPdxLDT6ldX+jCJ3ESYjUwMb7A3Fe6+jDPrt
ra6Pgt2KTn7QhqsNaAJbaLAjPExfMhqGcqNmK5ULxB43gg+wZzjEpIUEbqz+RFqj
ME7lZoLZ7BXoimGr1ab6Nmz3+QTCbWkTbvj0aGWLcZDYCRwxgWujtoIn3qOq3N+m
Miju74KsUQU7Y70b1qvRUNFsXWIzmUd+l1+YUU3ILZSNHq1mq0kpf32667LlW1II
RmAM4eVW9PyUCZxmuZlciQmHM8zR44tKN5VSl7BL8wpO5GgEYDVo9bEbuq4tL1rS
QQExpMwkgZvae6DkVX3hFDehE+uZY28dGiIFTYI25pMw8ASAkbwJqA9gNpDBVB0W
SBQAfVRCdPfhaf2WwHl97tkU
=N1nF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2375531c-56b3-428c-a022-a3bf56160e8b',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAtevITKq1wCfC8Gfz83vDVGNLQVOJYVBiVj6HRmuzFmyl
pgwxkdNyH5Pz9EuB8TVHTe/TAWkMnJdjGPE+kFFAV5o9+Chx2wFOadFDxfdu6Set
hcvKNSaYClyC7HDlLwihKqMOHpnUvhRZu7yOLDuUy1+/Lo3HkcY1TMJxHGTwps27
VFVHq9LmZZ+J75xEHw/T7Ob3ICpB/dHf2mzF3EryO7cLmwKkwj52v3mnxVnTaVtq
eWHOk2IdM1TKQmVjChO5kPj1naxX1OK+cruCWzq7jufzfOshl8ZnIAtrwIeAjc0M
fHAccQK3xohfu1d89rQbxFA+FOuRjsceUagsHGw6BdJAAa2Wk4R0XsZ9wDVUwaiK
PA8NlEja1T+J+85kOsTj5+JpRRwhSCLXAjCCslSOpjngJfff2dVB6UEcskj+zZTp
Cg==
=+nY7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '25cb5da0-bc05-4454-a3ed-34e676a766a1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HH3Lrl1P+Au4A9OOC2CvmzgT6gAwRffjFLjYVs1qkwM/
hUYMSJdxkEy+hVMlImCuE1jHxXqGweawEdB/XmU3Lo9dfulBbJwrUYzIT6of8Jxq
uOCS5it+B6dv1MHc+LEUomGGfF+dVHtLNNMIaWU8Om88O6jRri9Wurm8XF5K4aYz
u0o0QB6aLm29V5v64XBv5SlU7E6BU6Imw1V7s2nCo/JqA2d/RbfetkLfCmllXuPY
Abven7kBooGss0EvbBRRe6kzBwTs36V7KDX0ilTVyLmZxe0bMEJf7YweTMgTOa9t
J3hnynVjhz8QmZ2U58DpNbN2311NiSvJFYjm5REcZ0w7Cb9l9mhrT85W4fb3FcqV
5f7WpwX7VMJRea+vhGCK5EkpmbAb9P5p/ERLxvLOSZODGt9E2a9cNWj1mt/Pv0LL
7sdJauYmIQ2JK8C/1RLnLM1FV7VWtpHOSzKwFVayPYXhk53pJ5fT4cRi5vUS/oFx
Nu39x7PIBnBKuRfW4eSvEV6qAXiNW3kcPeB5Hs01QQMQaLPOCPPafc0L+k8GKS+v
yoMReLlLvckHgy8cDLNFQtdhL4ronFLB3TYQzSYiI5UgZJguniBjeUaT7DdTQfOl
SCd7BL3T/2vn54XpiGorKfn6XuCi0IFQ/bNtf6o790SyIwTRLQ31XTNflaJGSm3S
QQEvOy+ue+m5ydHwiNC4nD1CxdI8DQ9MD1u7PZIRKU4sw2h+lwU8znQ8Z8IxpWK2
g8AHmHcS1omLPLnJPsoLriY/
=U2NP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2975742c-33c5-4754-af27-94977d6702a3',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+J7zqXr77WcNFU+bbSDRaaO3wc4jYWw6amL6nBopcf/I8
x+UkICx4tNmku55FQm11r6HYYNqrbQ9bqh52THRmKDpWgi3bET+JYYe0FYN/hPdx
kRsRf36yfdFWQ3Rd1AYFx6/U8cCWPPVjJMPix1HgB/haAAJAKwFFWpaMsL/nEvu4
3ll8jn0B/xsrl0t5c1iHhGqyW30gAghPIQ0s8rShIbfn+dJ0BIqPMH7kSFpBwes/
ruzK06lIwTH1qfrAckwLdzvN4PD1OTVUuiGhVxfPjOQr5lWWQqO+gclqECM5r3L2
j7i5LvwLDsMD3gA1oXbvF/f0OgrW+nf/bA5VXs+UtdJEAeeICfhP/xw7r+0Okld/
wDDzwmBnuc7KtHJ10ZyAxImLRs9oR0/cwQ+p9e6kd+rW8mierpGy8uT+cbqRGg4X
vTJy8nc=
=wdhV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2dee72d8-af5f-46c0-a980-fd78ce9f12b6',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/ajAFRD4Aj+KG2NX0Hr6tuoVCTtTiODiFWWElxqPABd7S
GoQGO0bs7plbkQP+rslNK0iMwnSpqGWjUHPkHCwqJN/aYTKE0Ke2fxUX37E/X5Lg
mcsaaARZgHBZjG/V/fzdIptdJyMh4Ac1YfLDX9zCicf1jVdMqb3tBMWj3mmdMX0f
AEbqm/ekkNQPKEaQM6Z7WY/HSZOS8G4/b75y0oJcClPD+hJG5yYzG6O6Jf6VIcgS
4/jwXuwXPVKt6ZYFtbip7U4Q7xYb6kVZQ+tIG5HlzHHYY4Ug/RcgiVnVHhJLuqxF
xtPBowtc8lnQQkAgHB0RjgMIpYjbvgzuTw2vhj6oONJBATnzxdQSAFVgb0tgTxB+
kdB9ETme3GpZM1QaK0Ok00SvLPcWh01cR1laEIZNb8TaJw1RtY2GmNojR9heqijm
T4U=
=fxLm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '335eb593-40e1-4683-a8f2-a8f506958f41',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAArdYT6JaJy8fr0WbX3H8r0hVpMR6/wqBBZCOJVVOjPjG2
nZppfHE20n+cn2oscjFwiOnc5enMppihgWY1NPw9dTNe83+j/9EIFSn23Jw9FQlx
zhLqLdPXzTwhKZcAQ/lFL8CxR3+pqbVXrDSDr3AcC0l/p3KT4yfGYELlEZvhQmZi
ZZOhrSnNhDSYubs+acfiyk1BvKCGIlt61xnSG7sqS1L/pdFwk8RXEx1yms4IxX/N
a1xjgJkC6Cpz1flQEueB3mSB5cMXV9q554zQXOunQWsrgjfIGZmqhTPozY6iT89y
PDCCHFQOv57maIaPauMatEOcmn1s8g9EVRllkPPSEcsneObWUu1TjFs7xexnYNnV
ILI0tRKppLu2dxsGewBPqeBSD/iB1le3QxfOqVNMJIbe6oLkG77g9gaYfOd25mkB
8ytkkp3g753NJhivdw/MIlEoAdD7Fq7ia+P9F/vLpFW8HgtEyikkcrVhPWsuIxqM
V3KuUDWlWQXgngh+jadhlRE4mxNrClJlrbZxuR+qcwnxT424F8gyePoqyo3Ujuxd
WV1CNepTLG0iZjUyEtbxWrCpzmfZ3lQFnBIWKqKc6QuD7nm91XZI0gT/vsAORrm9
BaleoOWr9mxfaShVl8CgI48VCV9l+ojHeYFg46f2DGhw795bQ6tu7LEaCg+mk7TS
PgHIr76naK8PwDUNB5uJraZWbC5Byn0ennFwSTB9wl/JiU28+nhlkVVH8CtSSm75
x/7UyGTgYqAZnfo0ozuQ
=YYf/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '33f2aa9f-3996-4e5b-a3d0-1abcfc4cf081',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Pa1i/+8BKItdGUdR5aanTZxcnQAZjN+ZLkoDGeombdPx
eFbtSozDD99HJTkzD37Td2lcmSzu/Q8RlZ5HNmgWQPBbHrveSRf0ZV2ebJOAz/1o
g7rJXudkXzKBvo1oqbc0Jza3QMltEqsa8lCQ2HPlWUXl/OPMNlVwsubIfFMXz6RZ
5QzjJeXI6RlJFJkeRzHRXj1tzf3Gd4ntPulrLONvtOCxdsx66re4f6K3vK0S6LID
wSuHR3jNzQon3FKf7pvA366MXQQJARlS14cSaJSFUt/OCm4vfwuwU952Q06QnxaK
kQEEX9GM+foRcxznD+NNcSZFHbXgt0hCH4xrDA6ghLDio6+Wz6fLusDlFnHPUzDQ
HQpjOEp1z5b6f4PSrLfvGFRIqJF0S6gGoB5rR53WY/wLnZWry6eJf7Y/kK2m3fCi
kJT8Gw/qT+zpXIZ13L0ugsTAj1Acf5G/vwlYKqFm+dE3UvsK4R6P9dUmOoqlwldt
BLhPEoR6AI5T131Fuxq1rfFFIIwOJqccomjYnTc4eqbeM5wYOHWbqNPnoP43L923
RlGo8BWZAxJ5sYrtm8YBTdxew/QFaSoEbNxTadJaTnbKNkSwsjnbaSzhhtAyrjgO
gZU6TrPR7ugzOJngo2Gu0rMFQa8d6Ju4KSx/l5N2dDIoUa7FjcDM7WbIRLqZ8WPS
QwFCDeIZti49f1cXp4reKWOmHYC7nY0UM2NRDGQwRM85juL519qr8E6UCNM8kZec
f8LQhjer8VGyeygqgX4Ee23J7N4=
=r7fH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3af3e306-a64f-4d59-a0d6-ec3d63f9980d',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/Txs1suHPVQ0OkykzwRr+FWld5vfx0mp6y1v/oXL6c+Lu
LAOoOkAR3tcXiWZL3N5w40JTUBOBULXF+3ocHzZfO3ljrkzNlGzVZGJGXcyFCD0K
6l/7cVmlrPmCMw89E2zQrqRXwFATLvr5xdNgCEb5m8ZI7hJZEyHEF37Q3qYtoN2+
6yq8kCj50qCcwfY+z27nbv6uB58O1rRA83Ysi03DAavp4OvS96Oa5cEZSHQZnM5b
/EPEk/Kh1AlWvr8Lj0ueGr+mu4iwSVQAI02otASxzuBuBG4iq2/bNZhqugcCMHvU
fBAhp0xeC5dy0iMIlSOAJA5L/xOzetSANJPr5Wn5p9JBAT3opOJ7MAbNTpc6jm1F
n0pDehZ1f91IIf8ScWl9QgAKtkl6zfxkSzLkUenlxGZZmHLKjbTx6k/kOQ7pNhbn
TTM=
=rsTq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ea49700-e685-49fc-ab8f-fee0a4c7c824',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+Pdmij2TjL6CrPTU84xtHkIg/m5W8W/enhnB1j5WfRkAP
/jOXvXQML7Q8muYnDSGsLHaxw42mC+KdrxuWHD5MZGB1Y4BIcLgeo/paYbqBglZL
8+QDDkBjo2DHmrXco674pfO2/HA481eRZx8qNiAIFu1rGQZ/AJVi/918gBATldGH
urLEM8IinaiHB1SM/gmp32dDfMTYyY15JCER41TJpxznxRa/fTFvuhOle0pEsaG4
XykTEb4J/mUF8oDdCon8cXoo77bfl5LUWzjHqUyENhVHwnd6D8ahtKMsAWCbzQ8J
ryyc5Ng+FC1K1MdvAWPTFKCt+++gjo7QU8JsEosdjNJAAdZ+z6nuQasS+XnsiGWk
pVbVDcQTWc4WQCJc+95CDymnGDkKB1Z2IHThkd3MnvBcO3fpdl8BeMh1y2IHnid6
iA==
=wZY0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ed8e432-1fb4-4276-acce-67c562826870',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvuPt9rCAyXP981uM+d6vnEHL67GZcFlrKWylRiYx3PoL
OXEhfVokTGTc0a0w33KVoq3oNoy8icl95pKDeXRDso63vyQCV9ViWjNUJHg0nOGd
WthQBLJpGL0Oxm7FrF2xJewd9As/BlpW2nERkaUEoPfUFBJeFLTkSxzNtXVNJF9B
FZpGJ6fzYWkyCo6JbQ+NGRYwimybqKCkmRYVXLUOBX1JaYwKlyHZteGNQyM5QG5Y
4rB023cOet3mtlQ2aNtFhAlnDXoJOTxqSM1NJb88bJ9gcChdPPyU8kFrEU2XaKlL
Q1lGV/kDczFfPk9ITRWHat/v75qHsZRquXPx0RLRJyZR/QHWiE3H956noFzTMj7v
/LZn87GS/cK8GIDcm1h/3LyREKLSU6F67+Z8bMDzj2OiYbPyXMQ41GvFD1vwb2By
CRoYJjXZKFDfY9xHw79Xusmv044b/KZMRG6cTO+C7wcWqU0sW/f1dHEw0fyipYAx
UWt4jRuUyK3oEH+1979M76vSm+HE71aP1ECX3W93eaQYPumtXQimPmXDS7HtMj3I
VTPf4IC51wYLSUoOIi7ibqwrKEIAw1RLM+ycLVQuAAw1O3XeZV7maW5Cjmyqh0+l
CppJ2QETOYSev0105iSGxiJTqmcofowblAZm72SccLzA+FuMqSMdE4ZwS236ZgTS
QwHy/CiRv4u/Hjfu5Q3Zl8lzUCeECSHGWjaUcRRe5oATOlWID2Hkbj6VoYcrI12k
z9HxmRNbAmTUko6UIyci0VxVbcI=
=Zi3w
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '42108e86-5c58-4e96-ae44-9a1010303188',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//TGWc8jv2rjBreCFcrb+g0AVDDEiRgHJuTmX6vNgFKF9E
FD0BIoib5kIKd95qYL389J1wlmRulhFS6Rtms+epMsKL0FuaX4ktir7SpA7v5RMp
CwzquhaKnikmkwduEmnxJMZgYXuB2woz5stSUJkQlgwFQnOhk1X5lhynKA7keVt9
sl4jghHEMn3EePKeZ06g3/Zl2q75tBJYqljwsAj+RUdPYmUvzXM0YziRnZYGApa6
r0rLEHKLsXZIyhShl4NAhw7ZTDaLxXlTotNGFp0fBhKogfFR/ndjiPI7WWWc5uMy
f+dWjkSg3gLu0M3F8d/X1p9fnFbTIcFrUDBKxVGB93Tv7VVTMrD63iTVEDELD0hQ
j08Yv1PsfQRdWJw32R15/mT/WmYJu+q83K1YCMgBNz7pwF5YNJIlbR1kXyHhaEtw
vllMG77QLptiw2lwCbo42WPGYJwuRJUED67r6YaxS71ooS1GwakPpfoMJu4QIEbs
aLB7w3pFgfPBdN/82ZcKcqJ5Gn65aI3W2VDyWM2dBEZJAJq7bN/VaHnItp4hwbP3
wK0BGcxV4YDD+EDTlJVgbEBFWUnwPkl75kVkVU5n+yEF0LwVOx7Xu+bZ8ouVeQ95
Nt3tBO7CPSuYngxym2DCFlDy3FMGBPzs5wipXeQsqB2avSEgERxOH4I9X8Qli8nS
QAG4w9RJCTS0Rnd3OnXJiIbnWFxdrbH7hcCbs0UA5IcocayPRJkQFmxqEGXKAS2n
N+pLAeoQve8bkwrMl9HzQJ8=
=cn3T
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '44cfc796-51f0-48cf-a7e5-ea2fe36e6978',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//Z9by21Wf3dha/TKzgP1+qRtiJN/EU8hArFoqIrckVDt/
HF2tygSvuzFsfoOsaCYEqB2G+RqidT2QpQha5EjI1vcjFG2kB++uJ7Q7386G4AHE
iisrIN30dntmXGbKoKe3ScmaWo3sQW42nBNMujbIbm5DeubSaSuPLz+BSFWdji5/
/MQ/01IJ9Y1B3a17QZolS7VBI4O1y27gHvWWAOVTyVOFSBMLUKav45sCmgYwZxee
wSl8rYRCV6bgiWWd3NU2MYTcAeGGJXB3E+rv524yuqunUfJYJISb7nGB7XAK4cz0
pvdJYv5PpLZ/8H6ZzPBNtdw6M9D7tQTLntN2iRlyL0oJk6IPhUsnDfdBWH8U4cap
T256foFNk4z4AVyD6upS+Jq+h2prCW1wrz1VeMAAkwjmFy3sYafJeBCtRVJ/Jddw
EkkB9/zTpGxnZu6N0QYPXSft3mdjWzlHQgRsc3Dh9/zBmVcj1VFXQrVD7O3uzVdy
nTDWPBLF4yJrQO7rmmglXSD9E+dn1Ra7ZaOcSO1m5TUxybsLneKu+vm3k65OMZQ/
mT6mAwPB460qvtTabo4Gu4PYwQQtFi2EoygYIbTz76TpO5E4QQO3wunBL32Dxa/f
IwusDAy2VChHGOQ9lC3mD+qx9ve/sAPkEG31hEnBZWhyTAYn9htBCze1ZLs1wmDS
PgF0hUGAFt+N5117oY/TKb/gPw1XXvEhTtzn4xfjAWuPwWNpOViqKOhYvq058W18
BeWfwa3ElKXsa9/ckOEi
=bIeq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4c8d7dde-3f61-4e04-aa3a-9e85479f2d6d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+KqpqNodAr2LY4oEvfhdLAn6aInLmysKV/Uub+t94CD62
oVNviPRR1uNWH/AP+5pf/yc6PTPeD7+AgXcrMt8LPgwGN6NTLnYDTzMkKxeTrPlS
GVdxXgp89v9mltr12YsvtcLtIbKlEU4DR6dhhhEpIzC2RQnjaziBjsjMdukPs9Bp
zJtX7uue+HrRsLLL9gpud9k/8Kj2qtnYfFZyrTteYUltHkF0eGEuAc6KDMvGZGdX
IhavSas5vOKCCkYqaBvRH6II91iDfBEsK+yGmDTjXVQeHosyK61zVv2upl8M4ctv
MFdKiEwgjSqPJFxT2NADdOwmzN18/ivJOuq8r6iVM8+DTTUIvuC0z1otJ030EW6I
SUY9VkbxRZjADsxVr0F9j1eOI4Yh+wMgP+W1XOyiiuyF9b4JOCO3HQfKAky0pSLl
T0HAFffCFL56meiXkEoSn65zyHR5ZdADz7xjZY8CnASSGCA5yP8KYSnmSJo2mcrS
AVzeMlrNKKF5wgRDd1iXfiZ9cZyM1kXCW13sWf/+XbSBH2Jh4WgU9+px1lO0twCN
6gsJdiKOPrY189BCHQII9Yc/mog+VnmrY8yPAKuD2EXko5xRIth82BjwN055hPJk
NmsrkkaTIFURGj9Js3//IrrI6JWpzVsbzIMdlJlfYRQoF/NsjKRxD8L5OfzrVoHS
QwECyAB99CqFVPJZBqB7AqrwGWepYfWn3De62npR3DThrcJD2pP1bEHvY+28ric+
X1oM59CsXYudcCjkMJKkZycFeWQ=
=YRWO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4e7b9f02-9cae-4e11-aed3-3ccc1e187890',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//WRm7iMu7e0eELpnxdwuhItMGskiqPpBMcpqsBKRkXzyY
n7kBgaJdGPMNTHyaXPbaoNb1ASG25uXexbGjPbHWm5T4aJzw3Z09YgG0sfsWqLpX
sMFfwk9vE5TY7N8s0jvCiz+zML9ub4UjI1UoZH1RTbN0YzU0J3He0nuqvsvaxrwg
SWV3yXiPJGI4mI+LxjcOkxRAlXfJm6TLVeviYmF8DW7eqiV0I0I4Gn+Dh3X/7BeI
/SYhcOVi4UwOnC8ufQkICVRnzTqcWgotuamC5zCm0FKIaDVHEDtbIp7mzCK06p76
4eJaRZXdWvUbRqy8Qc0H/Tzue+s1wDdEoF1PH0bXkY1d0g6p7U5QyMgyY7RaAyUj
c/VveUuIBWH4Z/xnZ0RPC/ODY2GVUfvkMLZwAOGZ4f1V3WDTpYlHq19y5HgM68lY
+WDDfovXHASNHRFExD/9kYIyushR7oqQTV/CykK/+BcNZwR/1VicrPl0AJxLHtWr
EWPu7kjyo/rqY5LmbP3QPCgKl+D2PYkt08p/1fsV8fCcZQciVKPgg2RX8Dohpenm
KLTRzwWz9ujLygiJB4g1FQHSWSs0fKmyHoN6Idwpu/rAhZv6Qiy/Dan2xgn56xFB
jWkKdtwXdaoS+NSVloyGQ41mZpgnq0ODo7ZioSqVRQeZAkiySThj48za8iYwDBPS
QQG57offHjtd/Wc5S/tdGgQ3tFgwnfFoh+vVSLvtoQttxVIhDElDf4Bnk+3Tx0S0
3ncdC593OIcXReH2fCOa4foq
=hopi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '52c3eacc-88ae-4043-a71f-21a3d04e2aa6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAxxGdCW7TakddL1abiL9JHz9O0PKMqUc1DpA2ZsZGdry1
vLgTQYwQoi26EJwXZsVLBwNT+q/jUdW78mrkNr9+bIANwoRvMlVqbdne7XtDAJG9
xWHSOxLteGIgDYsJ/CWKCioerAJ5I9Ib692DUMOqvT1BPe1NJHKaYcKkJTUcw3nL
AscwI1D6DGQfpivGjwnLylkrCPHbSrJLn+9QQBHOPQmbxGaPxKxFtd39p+0a838B
vrdUVrtCD0OotAH5C09IKio2fVfYNivMBv0RM/7CNiJUW64aGI+eB5NPZTNUvLRU
OPwANTIOix88D6/5koPPGtLdm5kafthruq+h34ovUQBk63x/7ObOKBedclOmjDQm
OakecUSn3j3fXSn5UGCsDt6/v1jcHU63H4mRxLNWBP+GqVP9+E24rwOi5LymWwEG
NZDv637ZuXXMKBD8jF6B48KB2BMnl19/R13g6FLui60YRifva39vrvfuq0NSVIot
hMN0WtZvX+eIMeeqztT4U5pKI3iVczkLn2WvTTcK7f+JIm90JNZB2/7ZrtrGa4r3
gt28b2k4qAWYkr3oyv6R+VbxCStpqtGQp0xIeUOjI6xhgmY6+tFzcGsx3lAH6mgW
tgS9Nc73XvYOQJZjQrOWypbQFv7kjmrCaB21VadH0Vvk+hNZpG9vj1FGmZ++iG3S
QwFZ3OUZgGeb78+XLICFoUkmjNAfN6VeClTc/m3kR/evQubxkAtw8buv/Wi5BjcG
08e7tcPD/FW5sFOQ4K4F6PDsnRY=
=Mgtd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '542503ee-572f-47b3-aacb-1f49ef8a97af',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAqpnxIxlC8XyauGG7AR+tIzQhElWXFSpy5heXHuJkG/Ta
pXWqCBC9KecXKygfD8WvyqDvF0E9b4LFfnQrv+lPnCTkPEqtbBnoxjtlVNQy7QIA
JJk46Mee5xHF8XqfvICeZ2CttC/dqnhUmdAxN50gI8IS6XzYT+bKcRAwG0bdQVXs
S3nnNRElmyn9E6vSMhb+Hkko5tD/iZ3PXDkxv6GDzxPa7SfyLPhuxodERq/vYY7t
bM1nUtRbXJpuGmZdRjNmIRoQ3lTos93fuXeX+Lb2FsxAkL8QFpowULmhn47XSNj3
YeUFejntdfQK0MEKEKStMONZ9bnYiE1D53A7qqBdLKcAeLod3q9m3EvFgN9rP4w2
ZObmzWRM/INxagt3MaHXwFrIXpCg5ntBTa5Do37z+td2oK6odIxzzigH6CUtuG+1
FU8HRlN84/iPD3MLMbtJI983MJ9Mctk04enA3PcW88ms+bN/4SFxxce+ENkguhOw
WpRZPdJq4ohRp7FuDYDRhg8Oxxo3GXT0YcQlJfYz2mSxSx8CB1qSzoQQaXWij3l1
9MrM37sh0vD6rkfwneneu/7qFwMXRLmmB+4CmmJE+Knd/AAiH8tIwx6odZu2w6QU
hjZvEcUh29QnUD4KFEOYjUHukX//ldkFGjZsffYX0U6tYvTFt5W8NaattcZZ7x3S
QQFb6loM+sIVFRXSpMCdumpQ2e6a6E6g5YxYNt6QvqZA0+xvY4WUkLP9n4hyhncq
pXn6nF8KHA5ds+s9nppF3BkS
=AyKC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '573a9b84-5e59-4646-a87f-398ff4426673',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAkqxuDJkulR/kTFI2X4UNHQuu2fO3t4zYn7CjIMV6BcgM
wPeUE1Sj0oM1wLoHXFRz3Pwrottj/UINQvyibwUElrwbFmMkOXrUhycQflkojA0N
gdWZ1IHlR1lw/Ln8YsB4XadbLNFWdoLZbWTnWhZEgBQ6ztjBSeVCaU+XrnoTT8S5
Pqj8BMtW98KvfeuYkWtjooQ4GVMm6taHmnXh509p+qWnBZ0xmcAUQwbTL0LWXIM5
wSZNAm9fzA48D4Sa6c6H3zDJY6NdUmqgO2XcDCJNtEFXX3H8YvmeEwvhG8pHBcXK
cG1JAm58LZUgryE78GecsDh6IeX+Q0QtQhiDe4h1ntQQNw1dOXaiQGdghb8ldW9W
o4j2Jn55V1f5Ib6jZz/1I9qh6EXunhdvVoekMrcMMbwEKc2ljMPPPoZPwVVH+vBb
QGdfjuB7ZJTvnDlWTkjv1AFX3kIx9A1O5iyKIFZlvtNcB+ZHbI8yO9sX89yfJPSB
I9ThzymSSyFcUglWcKM/wrOBJQHsKMtiOcEb8FV5fUcTN8EJa5XUgUZtkJY9mPAo
/GkF/Ni33IeDjQSQ1GBCpKtGVY+yB5vRl3DUiU6pwZSygK1jwhKACPlgZgfGNFWj
3UrftmN6WBPcUrQHiSpl8xeK+7IxtA3NL+jFVzwW1z6XXshY3VOMMWHzPF4Zrg3S
QgFjaSXWyViRsYDcUrvhfDVypGCj2qDwrHUiH5Zk1XJo9J6/KF4creZ9j04CxYtB
uhYb0fwpP71rKFXMyIb7/KIcvA==
=jPVg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5b4cbdae-8464-47f0-ac2c-6bd11a339b80',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/S/7hCsrUtfJ74S67kiWTPGwAE1QIaqv8/CdSC8tiDIBA
0e4blAhqZlPsZLCr2P3ALNt0ULiec+nUu1ITpIMjN8x6kgvwcAyrEuFVxQo/+lNC
fvJvO9tIpRw27eCiPQyukSuYHYRzCXKl4dxoQ7molAen8tqvahQyovNTTbOUAiV/
fSkfw0NALOiBmAYoaaBCsmZvEdCDTzw+k7EJV4siaAqnLnJ4k7z9oTOaFTgxiLuL
r0a9n4XfB9AsGE8Dc3RCAyNjFeKRmI+atdY20BrrtXHf95qbH91e73ayKOfypdC0
hzkZY9aeEOAEMzxxY37x3edroJAJc2fhDf9ZPWgxMdJDAQjo4r1HJ3mpDSFuEvpC
9iY1DQwvSc/BxbIQIk9/6QuRgB20duJtxu42ba4BIdUPxSDqVeU3COfcGNGW3J2R
CnZxmA==
=V8kG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5bd3584d-5238-4ab4-a28f-871304db2f8b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAlRZxmfkm1pBR+WwykO7hFGlO6d3z0Y0O8QO6KHjGNFwt
XmHHNxDJ7OAv72P3JDUdNiT3YK7mqTvhmyTWq9qNsDTW50JN3I92efd+/rC3/kLy
qeGmJAJkVhvq9lfAAokjQ4/DVCzajFmtsaJYx4KTbYRGP5bk5y3oTLaLIFau5bEr
UdcC0Fmgu0g0V5orzLFikrQmTE2fz21RbAMQsv+tfLrNFwUf/obj70sZlgNEt7F+
PDJS20V0kltMewakKFLpjDCEu8luYwJrxwL/9j0hjs5PKdrkAHnVarX469HSuhey
fvN0C7t9Cl6KNCf/fuNd60/mQqv4aMQghlcJ2Ni4cxW5WI5jyxoXgQnkax97HDcZ
+b3fGQ8kNA+TnFMe06guNAk/qj47om2dKPSAO3h/mxXxvuMG6/akF2SqXp4WBBNc
L/2CTJqMmrPjMefZBIv3yk6iVltXqxFdcwNMwQyCJYTHr1xY3oM4zw8dRxmyrgku
uCzasn+4HlzWdP5LuxuMbjWK1RAXA0zTsjRjROWREkQi1ZDMoD7y8NyAdzkTnL0g
E38fbzGaVvehDw245veKaxY/yYJbwf0AY2WVTfxVv24Ymc67H8f1Fr+S3rErgKLg
9EbficxCXmlIZW3PKQ3od2ylpY3kbdmYZBAq/P2Jh0q8yMh7VA2+7xT4C5dxA5TS
QwHibTJKnYYiM5cctON+r1TkYbfcsCqAQ+rltfe6yRSvJSgiqqAS4r/WdEHOrqhR
rQ9LGZ8JnshsOdFWsfxTcaLSiyg=
=xUBW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '61d8248a-8379-4856-a4ad-6f82644e9305',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//TnVaoxxajvuaPxbRvo9Hl8YTL0/D0cb3q1WNYRW+ZeyQ
fr5sanUt+YULjY1YuKPtypRshN3c1LMhnZX8CJnzUqg2tTHUAFXkOHmfeKV8YMsi
IparCNuHMl2Ukz5mNrRFc02q+6jhEVotPx8/f9Dc2PrFFGSYFYk5MMSYqybtIyaB
9zPVE4EmSslp0/B7qnBysgPdislZ4TPSKp9pbyf9UGwu5IHdqUDCJJzvh4rG8wMn
eQjOQBt0VpwRJ2RV5ToPtXafwKjlvPijwFMM2iqKtT7JqGqs7XGcYYSO0LF5ojA6
erL6jDypABT4czRLBkdhnfn5J7ktKl3Ma0d+Mak1zIhtt9iWvZv3BgTWsEhqCbU3
KJRnPEigbSocfl3bLQua7tfolgz96i6LVzMEqYTOuXYdwamBKIMmucZGWQpCa/8N
37041l9qW+dGJAQBCJGOVHXK7QCu1rGU1PQ51uJkd4x7AdyOTOIsHERghfraH8fE
qS4xWEXBsx1coqpATiV4Pl0S3XAMGehdVOgXbRpRj5lToy22ZoRflH5rezwHHLaC
pZwxWJedCjjdjOMF9U40KWd8Mj/zu4H/iGu67g651vZEPMll80W9JyFsBKbo1wtB
cIgtKY8sIrFvQlLPUen87BXzBIfZLDv4yt6ek5ygRQFUEZWW03GtvUpviKYebSLS
QQF41Xn4LrTq0eMATzkzE8wNvOdJ1E868G751mkgyLSCZsDJuj1JUPLsGP+M1TNZ
+N6uhxhQAkS5Rca20iyPoFtb
=I0fK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '64dcae04-d043-4ab6-a273-0ecb81d86572',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9FhB3pgniO1DDdw9A4sWS+6yUa9XlXIXoynpqoypQiAg5
M8pnQPWmf4OJmnoCt8uigEwh56k2mSPa7CzYxtk9D69Vfc9PjtTFqD+lnftzZ/x3
Jftr1HBmsrKTnMZ/JHcDsTyIrbKnmhZ0YGhgbyfwChcRrAL6DDR9S2tfDwgAXrgV
oaryFQtL03UPDXkt2QbVeoixjapmqe0oJYIS6S/VwNTtx8mqIDxyEPVpepEdAiJj
b9UXpolLZEqXhskqMV2XnjBgSpsEzV4l+yK5hbyqXHTP8GkWXs7ePbqNG8QPf5Kj
1PRuJQL1AsmiiMI2t65P4+cLDKxLLuWDXSv7BJxDya4FUyMrzF6etcyu9+YgkT+x
+0rBGpDnvSrJNqJ/bF2RqfMIuyAEmm5BRCndeHTEnSJzqEI5Be7xnyfQ8QksE2XI
0/SC0TyfJ45q5RytT2yqh07aoaHwwtT4Tl0PO+QR59x8hsn9rvzaAdZYq9s3ko19
eQqymtx1HLSVsTkp8I0GnKuiFai2dgk94Iak3oXM/v41nB+uHOlVDsq1WWvG2nI5
1YljDuDaLPy1BxrwxKJjFwOqYuq7jv8iIYGK7oxnALiBW1F35Nc87J2uU55ZFYbE
H2wxTbkoEbJoOVquxtWHbAXORhiS+4lnqcb1vM0BptjDQ59bKIbBc2iJHw1YBifS
QwFdLfVnpZ8vB1RUxvO0QmMUuXnikUlogGSBLfjx/raWrjxGZ08Vra9hsCdfLcoV
QiiOAu+gFMjURaC0m6eMZ9lVJQc=
=lHPP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '676cb1b7-75a2-41c8-a4b2-ac1c2397f8cb',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf7BKe5PM78F2hIaoS/KfDvseM1LEv/pu+YmUoeyXr5hlyZ
jfKTaHyqA6/+1XYTCgPkZDmWMxy9+OlHl+U+6UyV4VWwo92fznPIy4VS1O9+LfJB
biN/nezDBsLwPOc2R/Ok7UySur+f59Ssw3LgnelZZ7di+UwU5hfENHICbNked9hp
3mMtY6FIM5KKWKEypyzeJvQ0zni9pY9Nl6TJIgYn5e+TdMQeaUZ2+gjIHT6eyGgc
NzsqgVjbDrzLUQo6EbAB+gc9jVUwzJzBMjVvz8HDmORpSI40xxpsaFhG0PFognM3
sfV5VPs1dz7P5W284i0hF1sax0kFpaKZeo9jQ/HQS9JDAXkRfRYACPvOeoJ7axRl
U7w/2o4iFel6KOplGnZ3lixDn4j3+0Pofo/iMWJewiYcml2/srwem5GBWchOhJoR
rYhM/w==
=99vv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '697c6d73-cf90-4817-a3a1-35f1a6804e79',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAjbQJGt99yY718uXeeq96oYN4lIXl2MGRGiKpQMM0U5wE
voLuC2jpAZr3uMQVaD+dM0uAHnv98e+vk8Z5W55lC7Vm1gUi09SRgJzhl4LXmihu
yOdoVPuuDpxeG/G7IHP75qOiHYgOwgQRzb7RzHLUA0uGmzALAV17cHNg15JtkD22
aes0F+q7i3dKretHSzvqNrPZed2WXjIfPCp8jfl4KS+PKze5FMKUh8/MQlvBkoj5
qrCOD14UCMZP56r3ZaQpjazKWLB38hiKg5AC/dF6CeqzAxmg/Z0TbF4F6LnCAMNB
1/KjCWWOgEyn1NHnVU1glLRkL9Lh+5ZXwanooK8pEA1ZXJFF0ygXa3BRE3+f2xy1
YS9GB8dpZC0yiFrGQfnSi06h1arXY6rQcVw5x9yGICT4V/bRB1CFlTiV7V1+omRT
q9C5/iu9LMPcz7vuW4i1aQUnXzgMCw01yiZCkNnv0SwZEZGElPLJlXG6HAUwN+Xh
6JavzAjhdS16Ci0EXD2jcphxoIp8w/qCZf+twGfpIDJjyAxOMjEC57HPRm5M40Ie
qbkyPePCm1SOECsiSPGU3VoyLL+j6Xo+qkB9SzHHI+q6+5J+iyOjTV24A4rkLvnv
YqZyRSCvPkNfePS8082E6gBBzCcJTYUp581cfFI+wCEVfZx8zl5U8tri3diq+0LS
QAHecjRtXAMP+ZHrGSHwGM+z3MPY1PmB4PUavloBoS/5pZ3X3oqMlfK0V5WZaz6P
hTrqHkVQ/QD8kPFDknQ08Ho=
=3U3A
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b761c5c-cbac-490f-a266-1efab0e81f5f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAuarVi+3XqnQv/5lnhwVOIdZ2rmZv1298QyWlpeJu9CeU
0O//cRtn6AQmsRmxrx2SLiYNLMXk8GHLMn9XbJO32UOJQttloEiQQYLRDfaZgTji
gp5t9ppvwOyB73rh5VVMHWhSIe6gAJfh2flWVB/rV71lpvTnORUVYSgWke/t8iTJ
LW4MSLeG0ZZGpVnIRGfI04Xh8GmW/jJCu7DbvZ2T3jyDPQDmENumaGarl8WjRJv7
wFKlp/uu+rkhwZQSFdldDPHcFb+LxJBvxTbIiSmaMdV5woIh5okHm9dAtbjG5Yj/
F8bwW4UCpLqWcb4+UC36huN+ca0TZJLkqXDaeW3arz+xa5iA5c3pX+RrXEVXV93W
ae47hs3cTWc+9ibCMq98KgMblluWtiS40pS9uTnhbYK3m7upDForGqinTUlk+zs0
b75dnPtnJFXLLkk2YD0gsorVjF41PBPl64KsSAqxLwKWoYI10rVC+YoXvyHNPdfi
0MdBR9O8PFVI7Jcb0X29swC71OwcyshyuhSEjN70k+EUAgtcisclZhDjEmu98qfl
hpWCRRIjj61FCpDRaPGzHVf0EJmn4CyIpok5uQL9+DK98qguj/HFQFS84W47Hcjz
rfUOK6mWmWYsue4OC9IxkBTi6oWqIPGdwHB3x7v6/AgmaXhVD2Dl2vkOCoMeXrXS
QQF7Dg+ur363O9s5Qw2PJOkgeflSSu+p8pqWf2A2n/KmNowLcrvZmImA1wpbp1s2
Q0qRtD40XOS0TTae4Ofq5x4j
=7Q0L
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6f4a34ef-0041-44f2-aca1-b383a46ad68b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9EkrPVeKQI2yvWYBQiCen3dNVj7fEpQobRawExoJQcMN6
tkKB8+X1vf1Mj6LvzUa3Io66mSycI8JE3aXE2EyohYT3mjiSH0NeffPC54hPStzY
fZAQQ3krz805+SJVCUe/yZCsM3NWicWrMRXprlzz46cS71WKwFt6SwM84duxHcDL
ID+OJ11ohO/3zp+ol/fIjj9Ge3jLQRdrNG/vMm6aDd3/LhsDkdg0y7oFFgBub0nr
kbEZzKhQRRpGiJZnLspel3Li5M6zCIR4P/gjyvvFLL+r0ccjnYagU9fYAns9ix4d
pLscQIpCk1xNvUruO0qSnAJ5dsT818gDg8tWympauO2ZGuRIlbqjAS8NdfQSR4XC
ZvzFy/nacnhEi6ICDxJ2jJGAJE/Pu1yKcOehA75QRHn4IQaOJ85ykIeB6/PAo9ze
Ziz95x57U0hxypTcUSOSz4n2SPfdFxm1MQVtzJaSp8QjROyf+c+Ah2U+CNC5iFTD
ryJdnVFQi9RnFaXpUkjexD04tMguGCMbHxvxUobeQ4UOw8UDnoLGiq3Nzl7vz2Ki
iBwrX/rpsB7WZ95tQ7WsJwDeIVFRnouW0X1AZBEq6LsCr0cVL0BZAZFBAcCHC7Z4
jYBBVhGFj77ckSzh0iYAM9ppJth7zKG4SCJlORrJVLmBBeOj4T+7V57lKZgbPHXS
QQFaZHZq0Rl8qZgTwp7J1Lkh1QXCAxaonXlvBj//jjo29QqitLnOWBLm+dc2ijNJ
I4mVr5dm0Ay68wHPSFBQ9uE0
=hm0Q
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '73e22c2c-1ebe-4830-aac6-69b3514b8cab',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAqUMVeLxFRevYqyzlr9rwFK73SKRDY8ucbUZULwPKw07O
6fR7jn5jFQiBZ0qQ7J2IjWihe2SRjDWqm29uUNI+H7Jo5zVqQOkNwzpTRBcilN9n
mOYN5rlsPnaAFKowk0r8ud9FRLsoaUvWBtvMQsOCv/g8I9Li1KJsZS2molfwM5b9
lyqDrjcMECspXtCq/7CCx+E4PrbzaCwnINfmNq1y39sy3d6QIAMUvyN+B1la2l7O
BAFoT6FtB5hWRIsvNhBYFv6cD7DtN3VBQhhpAfhzDjdjN26YYsfvHArQTAttwFKq
/u96EAdF9uLffCBtECfbRicypo/lZgV+rBgZDq5K7lv8u7x5g1kGnzb1bdFjg0Rd
rFuWMU693vNZhJ2X4KgXwedwOB1oHj65PN+en+P2OFvdzIU6TCnM9ewvN7hljytd
DDdf8aAQUh7IQ17InezloTDY8sid8nw+OKVNPoLsdhojx49atigSL3/mw8/kGMtc
LjNzAkdom7TiVZ6W+kK9Rk9YiiC/u3JYB6NcEMLobPCjfTvFmU+vQgn/HcYpDyIT
BdBRTEOG3UPIbXApJVgOivzGJI19LTIdQZLyiOqTnj7PXGTDTEJEWwXLhvrqBIuz
l93PjBh5LeVdpiy+aVeTWcuSWFzKztSgspEGAE1GTRiWFJ/X+mw2SLyUEPqD5k3S
QwEGEwxiWiI/bwMaVnrpDDtlu4b0UxmxvssAe2nAIWwaEcK4DP+JuBwnYOjUqnll
oBNNITHhNnhJ3LQERNV1n0/Ub44=
=FX6N
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7f7a7f38-0104-4f6f-ad5d-e0e0df77ea4c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+P66wlayKfpW5ENeLySWRpI5mveAOOBI6LiwNoaESX2RQ
kjIIDuniGfreCBfPPwZTOloWFZHMHoxhoqpbcGYvitvmlJGtZ9CyaqkLiXlbY4p3
QCj28F2lojUpGPE6Qn8dYKk3lGnDo9QQPgucjFMQjx29j3RCbr+3Mi4aB4ADioCA
I17HyhpyjTd2Kj+QkFAAfhdkDzmjzCW8Q6C+CrlGpEAWEle11uCX5YiNTEZbm7EB
7IC1Y8FnYo/9fT5DdEIN43e0Csv6F+eLttXUfqvU0w1oyBN8kS4nAFIgbnCXhVNY
10MlqUeDLdZKRzJOEea24IOPGnrJa8avvn1yzdRD3g+mEqBKRsVNXO5Xg9QZS6Qn
w+dgA3+Aa5Lv1Rsh2bmFmTsfCjJmPLp9MDXVnmzfvMlAySi6ATn31rDeHAgwr0GX
JRHGTsxDGBiljW3LfYdJAfB3vVTU/ma/EKnLLfIBE+4MBd3i2+hWalT05KH19B6p
GeD5A2JvQQ3qcW/AA4ANN8pbz1jLZJmuPBjorlbRZ1nU8lNi/xrzPNXPhVoyM7Tv
PKbe04EEgexpUa7h4pw0pKRkzxfRAnseba8tzr/uQwL9I1r+5BDRviRuBcaoN+r6
RyjQa/wGAj8P8ui5zv1qC7kA7axKQE/fLW3hKCaiOsPscKIn6R2xE7YrtuqA99TS
QQHZe4+U68+TMV1IJKeYoHM+oVw0i08LkpgcF1lNu/mb4CKlavLqTdEIMZwQ7AyB
RJpOTY6+0sacZvcfaa0FrGCD
=p8hA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7fef317f-8f7c-4aa4-a845-df2df032a8a9',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//YF2SKbyk1kcMWzNiQNVmdVXzzWyrmKJfsAfu8QzBz0T2
nTOxbHOhNYE66qNmveVLOU+Bnqiran19cUXRIo2UQCul1u+iN3d2rM1tQesAG487
1VPblhruceYvcfPE3CkUHT92LIkYQYtNgTKtjLdwvmzLK6bQMn8fp1EVhAehBpBp
atq+x1TqbWCuehmMbCL5gESpJfp5dfEKqaP1lTX1/FSa/9Q92Uch98Aa/c4H6wDr
3WcKupTBma4XAuwGsOLK4KreLGpV64VPdoBHYZS+4h74FhLwhmEOez5mqbmSuaON
LSBeta7g1CB3wYuoQ06RpHbOoAUNIoD1PaCLgZSyV2bFk/XaAwFihko5MSGM9suk
hGgH0dNQKB7NRVBv3yU6cKxe3BKDhKIOT44nXk1eVcswvcuYQuJa4DpZQQt7fbg9
naaZalPrIH0f3oR6qoGNuC3ClfLK75pfDfnmo7w5t/+6FD4SRLRNrcgYTGT2lBbO
ewgIsfD6dqj1yhd0RME+78TvljFnTuQODz/Ow69gTX+hehuHKfbpI+wf4PPzCPdU
oLKN5DWUhMrIIup/ZUnL/e1XeWhxBWDekAWNshgPHs8zdmSnc/t3KPCrVC2Lhp2o
M7F4AzFOexsJN6hzPJ2EEx8niUqgWGbvtnu2M9fp6VpGT5EMiSuuWDwjGNPmwi3S
QgHmfqzRFdC1ISg50uHd3K8ckAMpYaKqix4YTuFGRmZaoEeQCmJrIZOVjJ5gEIYo
rQ9uFJvlAWatGVR4zr5f+Ip2Vw==
=PLua
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '86a5e39e-670a-4788-a3da-a1ffb96dce1d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//d72qMYV3Uw/ECWv6n4hy3R+EyJ6tdrgbHvrSc2ofIF/3
Xn+/Qp73UsVv2FVBlte6g4kQiovwYDwhShuc1EDmyVJ1IiSHbbNtgawX0p1qoQVK
63fP95Iqc+UymNE5fMpcPwPl7KP39+fFUifu9ViUq7nLcCEUowgSv1b6XMxJ/aF9
8g3upZvsPqWFuZOymgStI4iAOsf3O4d6aOKLgxqRfXvgOqSzWp/Znob7ZO0gt+yE
kbT5eQEzTM7bxtHvR1qKbbMR0Yr9B6l+YmYhB5+snI94S/rj6HPnf3UB4XmMfBeJ
Fgb1gIPcPhCxD9yRA5hYm595qswoiri1NfkEpLJBVlekCmdtY68DQ2ThFUWTb1B5
rqN+V+tyHx4khn1lu2/m1VYwMMIPLpXslounHgthxz1E6xwHjSyUsWq0mSkCBJvx
vrdsXckSkTR7Mx0VG33mcJoRBJ0k4Zfp5gFAKV1anIADeyc/ff434pAvAwq008Le
1k6kxTTn7OdS52A1P/vmJb3sE6KKrrdotpUk5ey+5C0nI/Jr478mVqR1SH6uMuj1
Kjd1W71+dIN6WIQBVtlg53NZGh8HAI+OWyH69DAeIWpLxK445Z5+mtSy1VeiOJC4
ZXXnI6mvYc9KWe/s6aG7t/4S5DYXcw+0iZr2R4ovWyRPXBBDRNL87YLkHFpbko7S
QwE8nRh0fx/Dtly7FtSWYjVftu9O/kG3BZow1NoUd6H98q0Y6k3QJlnZJq2Ohatj
Zze8bCQR5AnQP2eYJDIMNRMzvwQ=
=9pIl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '905848cf-febd-4aa9-ae8f-14193246f516',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAxnTqmc+q6CUTEJT0c05ORG4hv+kwqb1mBJQQpWPwTHyZ
mPGklInFMZoqHAzvP2wcqMS13mvWlN+CdMUK39k3hTu2KtEbNfvaUTWVj8I9E7Eb
NeOSeHTqdc6YhrzNTGsPsk1ZZMvJRBye5ZYXoalmrekzn2n1V1wzpkx/BYMOoPgi
B7YwUSAcJCCe8jCC3zmeozbcql2JcZ+U1/+wDE/PY8HXFKJvjZvKBFoSFsPGoJ6S
RdE3Zj2iK+SbgTTph7/EyYNrzmFgnUmLWnu6PwVvl06l5WAf+wyJzbQA6370GqEC
5Hu4UUckINhv6cNhJ9/dMI+LjvwilDHjXB7XkjTGxMAiQTpD3ayryg5C9p3LIJyR
RRv06CyNYDSRtAjJoJFXIKKAVYDDt0Q351IFL7jDSRa15RnjTKHT9tkZ+2bI1VO8
80u3eFERSCT3QmmyAE5YlPaaWdKneL+VjT+Ec1B1IJ4IHUH/hKgn1qTQQufv6rNE
6BMy9n9vYZH/P3kceBx1iCDIdeRptd6UeEPKzGPNvRH06asdNsnkBtltEaag8xhi
/3p8kM3tLDtHx1feqqlrOHVDMY7a9YuLJYZ3DjG5CSno1eq+hIvv0nj3wElD6cKn
hX8RGlkKeTfLaTqPHqvWmzlRR/soDJN5v9+hy1fJdVyeua2hnJuLIBSjlGn9XhTS
QQFYvIz2ys66+fEwBzd2W+1iVTILocddtj69rPgZ1hksEWoW6HI/xvaYAtyJiX81
jmCiMg83bDO7N6dJY3KWp4YU
=Tutf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '957896a1-7b5f-4562-a69d-9a4b09ec6561',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//ZW46ojoaPvlDGlemkUnVaEvBF8n8qxJ0TzryGOorlBAb
cJv8nyan5FKs2yIshgY54wR9wShGz1m3NI39S0MDGDIRM6hQC0BwsLgiEi90JJie
BpNz5JuQZitY+jCJ5UcOgkWPZWRY1CwPh2889ejM/sOyTRDA4bnOx3sOsxfmzKtl
/53SdNkQojZkDphQoawiK9dBm3/rD2hp+rrgelOybFOxb+q+M9vHz8hcUZ6anD/s
K921RRS4cMBTdCt5wEnDIwFCtT8keQEVDleO+Kg/MV4+T8Mm14Sewo5ZJa8YUvG+
Gm8PnA736lIHB0CuqGHnK0ASjOudZbZ4kIpi2PsIgusRJxOLjzKWuolbyJ63wZba
waHzIVZn9PcMqozZFJ2jmAKkKSeGvZJ6/zlxyMA9LsIpUyds4dmDAaTjkd+ogbPN
Cq+n/IDMMo/9WgWsHTNXMKhdkONPGMcylYcj2PcFeBRunG9H1u52YCPOK8aIU4Jy
tAEyg1wMh6BQYQ2HDzta8sVbI2382QaasRtgE7QqRpJ1OWypcTkarYBksditWcaf
TUD6/uFvlOat1+wiqid3tN43aef+eUFMnwWy2C3vqqznkdjaItdFFOilEA98s3e/
xfdS5tOQaJlge+zrCD8UdrA5q5RbYrLIJts/p4lG0CCoNwCp9SRlG9nkGrjqvrfS
QQH7FI6GEmU5U+SX4b8OcCS5ZNRGnPzBpGA0t6edQqZ0+ELcd4kfX+BawlClWT9v
UiBW5ip3BI95QONSZridlCul
=1/i8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a02a6ba3-2c3c-49a3-a0f7-b933e45f979f',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//dlLQYxM1UxDOcqjZWSGhGunRubbEtbq4BpflVvGxboPT
JANHuWrvAK4xfDoOuncJGR0lUIg+vSniHg8KKJxjFB+HvcXvw287viL3V5NWQgv1
e4OzntY9c9E7X4xZytaODR1nyWdIW3twDI2Uihl39YVrF7zoORDA7SZiN4lcsmOv
aD8OoBrhXo/JUD4+3BI5INavz82FLyHEYS7qFWlPsjj/hUgjbURCoklPF76Yrjlb
fI/ayKt9thKVX6AqOT3fskamYVi7asqXexqoFah2cg1iJX/2cjobOXblHMjHaYKT
ommVtgqiUH5kpBkDA/iS0/MoDjmWjTdUCSShisEoFoxHAKDPOCGg2J82fT5zMe1e
Gnb8omd3FEZYjD/XiVSgp+fyWpn2jSI4mXoC+tffI5op9Tdg3/8QAf7VhUwwJKxS
g9wBKRfMQfEnDkjP2cO16RTU2aSoth8H2kQz+Z2isp4pLa6i++YDsHa+6/aRv1Nd
y4oXUwDM41jyNl6hvcHHypyBQvmmZuA9j9PQ43Cbo/mxPCG6U5MCSc7lLJC6EX5B
xlbokaXnj47FkVuC3A+46l+uWple2TR8fovuWLLAKVqi1aU5sWGR/XOrPSlVSigc
PbhenzeEI1sJf0mEJ5p/rYR7FldDRtlQXIugDongvE9NvNoFpErKAWQNFts42iLS
PgG/YVv8tgyZTx9aSM7xOTb+VuHFahG262rh+bA6HEEN1OIEOaCOWsUJQskrLHx3
T+kKsPUVl0zkc8iKSI7H
=VwKe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a323e00d-2921-4b2c-a4ee-07bd6da258ee',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Z2+aHaXXDEzs53fE4GXoFvM4Nt16Fb1aWt+KgTc359Oa
sv3T1Lk02gCsft9Ja9+4kcf+O43apYmWlqM8dRnhUppJVq11fNmO5T3XVv4LsUfU
VC/MSoIRYIofBKFHxOw9C03O/gJ5TjBtQY8LE0JAlxBajNLDZQ9jpsDjhtYMvygL
NpmXbzplZnVnbcYLom+g+6q7zsBAOiYkP/xLm1WCqOw8KlKyE+Z5FjY5dGctdJWO
DNSoDGs0nbvVzR9tTaTDxDL6vJRlGl7tak8TZFXa5xETpJdAJZIlILIBKoLulzoH
1UjAGwd36M1J5aLbJK2aaaCqPl+RnA8ITYn8ixbyWwH++i3ylg27ASzV3AlLF62G
kvJWrYPpT1EIf3E4Eh4gv6QCN3Qpz5sUQ92f+AyTwqKcaSLBMKekxJtPwH9OUjsk
bQTpTrsbjQNe0bbRXHpPS34WuDvrvgv3/HWAgXh2zVkaiGjJSCrvwok9WM/oL5BY
lVu7R0cE0bnMmAJ7My66LC91+A0CVFfgKdSujMdfUuVbd9VoA6UMLxvgL7dp8UEm
hDg0/pX4mcSZUEN3x3N3NjBdZgCl1GEkRFqYeWCAIPgUbTrloJ78+eVE++EZMnEh
Olf7CncgWCtiay4Bf0bPVSJch5HIGCy4tOnhEp29pbgic1QUt1Ah6ZR3dB8R0hXS
QQEKZX4/juY4seLKJxOJKXpEfMTyaBL0o7z5Jy02AyPBRkrf9tDZIlI8EgmSJp/v
rBTLto/EepkY6ytlPNQYo+Ji
=sHO2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a419a89a-bb63-4c4a-abc3-e3a54cc165c3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtlQOTpfqspl1SJXOtZOd9WfhUT0ImfbgvPTASd7tQTWQ
/MvRJQ+/lWSf0kUtz+CqrvqE7trdxD6eVbZGmG3+eQn2DjDELQOE09fqxwcRdGQ0
YrmlIEoWsJnxUy/zFCr0Tc3opE0bESr1pRkkQyEQueBJtn5JieZa4BgWjZLUaMD6
21P6OqyPYLB3z4GWmorSFGVQnFTWBowiDNKmrfeHt51rNZoRScLki3aXF83wuoOg
P8TUiDUcYnKc1QvyG8OfAX0bzVVihM9/I7EX45qkByMXi5VnMwCgfLg7gElRopZN
sx22VeKVZl+QCW5dYMKJ5k0SsYzhheRPUzuU/Jjolf2fYZaE+uwCpWRv7y5d+pEZ
Rq67wIQ3InBDYFgikaDn5kW1nU6rlX2YWohcFZcDlgmkZtgxUDsodll6+3N1l+My
QzS9OyUZ3YKgmUKCtDExOkhC4BYn9d6cM0vTsCkS5RRkuaW4zKiN5zc1Y9KaKE7A
VdiHmHofX9LLRjttczPlgqY7BFzWeQXA8UtDwu7svKqOhn/nAnkrhSs0o2nD2SDP
Z2HY1QmiOqwzWH/IcxhaZjZQmbxFrilETlgKvYPUILiEGILxji2lm959UsNGPVVC
P1ppzVoXqN1DxZw+k3RfDKXUXULIgsKCp3f7n/UX10XjcBA6h3t2/GdxZq3o4eDS
QAHrBxEP9fexnAThfu9kHexyFLpqhzsnOJOCtBokBq5szC475IS2XizFMsltp9Rr
+s+LxcBkd9AN2QeQJ+Pqft8=
=8rl8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a64b2dfa-9152-4e06-a48b-1b89e95cd3c1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAlTRpytQROAEm7lKACBid/qAeKlzK83uPcvkgTZNAd73u
pPXFwNv+ewnXpXyhhvw2cmIDHZZ7bnHYYTLdCLYzdOk4haux7BIs273/qK/MQz8P
5zS/KvdSUsNfWbOPJ7R0wS6wdYHiynVLcQj4Xj1wNbdc0IHUOKpMhiuY7GeTpBOt
fhkACZP8mSOHGt+v65jw20Hg21cNs/uIBCR0FnRRokelgGrGPc1XYs5wtJQuNqfd
IRcUlPN6VUpTwQfX1qpoCnGiKjrbiV63ClLe2ywLIhxD0gVVBA0JfxCIQqKZ6A+G
RDmSYrlQw/U22JAqsHc2jcZOjk6wUoxn6jyNdfPizomxze/4dxIIC+7g+rcaCewT
jIQq771o95sSMCL3ymTVT0SHRbQ92Yaa3CLJWB/RchSOxIBH+JYyzk668OLQrv5Z
ZVZE3hpwG2DLYUAqIDTYSuzVpXh6WUKsERkd5v+n4vizD1+uR0SreShlxpKN+B7F
/9iJuH7MMz5rYxqnVC5MKIfMu367+KHnZlTJ7rNtFL8Tv+fl0AFAf0ZWCS3V0Xqr
uNZL18pS7llhPQ8ACRewH3XIZS2pp3C1ohspXt9AabaGBlAytavq2JIb+uYxW+cp
sJ97y6tZ42Jl6f33gDI/6fTmR9r1Woux0mRgDq6Yc7vQzjRuHpEVFH3iPDdBQkzS
QwEmkp87fhx81ZzznA62Yqis1wzHGvXMoUmsZxC7lX7FlLS3p8tc1DyBqYwYM8DF
9O0dnH0GWwjH3C7Hdst+asXif5s=
=f0cM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ab673145-f21d-430d-ad37-af029497269c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//Thc4f68Zi641rmHU8yZSgh0fmKcJoLR5+bkR491uSYzI
7oLTGJzDX61YO1OaFmmF6hPoma01l7TO9sWyOKxaGRVWAsufs8Q04RhB++UeAUTT
sZXiJeAq7TMKn0Vb0OVFktNHPnDISqrXplyB46D+oHnt1wC5u8pM8YNmFwXv7PW2
JoaJhYnf8946UydZAH2QOdw9bsOFHbNi5LWCSBRxqRvsOqKMCGUaZYA1Y6KNfAVu
jDvcZ+80XF/DlZX0KTat0X6vheZlQ0uK4bUSUkLhzlrfc8yT5tx5A1sKsO+z4tZL
ntnKx2fDZbp9LofDiqfVuxWTsZx65yLFIe78TamxsAApIbOvP9Wrzb82fHoFpLSL
i+tDdxjJ9Zzcfm7JRoxqToULv0Jquha3uZqGcegOYHi65xvxvzyMRIRTxNMPUyIX
e/Lr6RhMPkhU/9djxSZ8a1UDbZKpgMEHYjgTPgVJ0GRF2FY9vL8KuY/Y8xdgdNwr
oNUzEA05xs6UyXqHHCctcnPvNy/hdYqELXHI+DwC3/WkGyF5hCJ7LljNRGgc7T9T
kTfJG0wnBPidTxmKjS3PlHhkN0N6waxxKJs4ZmYbDcsNHIhAd3lQ8BNJNFpu4KOe
CKw3zI2Om9FA/ynLi66B0UVAYc5OYxFemlGARMlSIZRSMtb1kwsZG0HFFXewOIrS
QAH9p+k5cSPrRt1an+9Nnzhn53+1ekcoqmkq1OaDxQo0JsfxkszwQuganD3Wsg7U
qzZ3n1idZKSZI0bI/+aT4+o=
=N/+2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b8604186-1fc9-48e6-a755-6df4a557989a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9FBi6wehCa9TrbA376EUPyGn0g9d5e6R2ssHSwZLSW0rF
0emQ1gO9mdrYRChZsdEvUf9+YOW7LAXgPr+iEmOCG4keHuCJL9PjfMNoDWphsonw
H82522+PxVd5F68TCmdhuvpPYc4eAcz7q5MvPUYrCVM+/RTIZz0jIJ81mId2uzxg
06Ve4yxAGR5MaiB8qC5F/h8LgrUXumx6cJwyV8ERCx+xLnpAUkNJz1i3Qmzx+QnM
hlTh5yZxtCL/O5IVjhVi+Q2u6j3ZUEAVoK6Uft8EJjplxeDbgDT2AtdXZdWOITkC
NyN3Cayh82SA9PXSX/p3zQij9kGvYGBGQ1/VthkCe9SoURlK0kyQaeanKmysFoWH
ji5c3YRamdiSgwz2ndqNqAaP60iq/OimXhuPMmuLyiu8RZ1aTLF2Bi2l5itbTRxA
3QIvLiDtJKbd80BkUcd3weiLPhClJ0G8CnC4WauosI+NqXietLTIAHClQ3yZeBS4
c/U46lbNeW4rP4ugIFuqWRxpCGre+yIviA9x47aLd7sz16XRwUrxdMwXGcEGrNUu
ySMEwvQ/jKrFkkB9fXGSNL6hpkwHKagjQwt59jnBlnzi0KzmyhQOtt7bVfzVs9li
ACu/2GRHtJZ21shDK+GFm0FPcjpv3F26tl4UnUV4RH3ThAga9qkEi007+fIRT/XS
QAGJPqg3o9+MT2ycPJEvSGjPwUGe6AaZXqPWEZW7yrN50g/flppKJpGgT9nqKjLf
ZmYuoWJmDN2bYGahi5cMWk0=
=21Q1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bfc46cf5-4606-471d-a4ce-c2253cf76116',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/asC0nOxpZq/nDb/yiIzWuCAt09UuodXWyP5P7nyBHrmf
cg0sox9zhiym5xWJ5UU0MXQWQ0/JuJjZe8G9+D546YLdvnTwcb1eY4ZfxALdaz86
tmALCXN47EDK7RRi64gyZ9PSzA5WI3dj/ZoWPy53TVKXPheoh9nVFNnBfd31/Gon
Lgdkl9lGEUp7WXTQ+dwzIRaH/k2ufENga3Ex6VJcfA2WDxSEXIz8CS9LMxc7dDeK
+dwOMLQQclV4kGtEjtoPj9zworuQbN8vrIiM0rNshk2qpQyQ+ivJkAWTVymChAFb
TYYF92bDI619jkFLw1PMqVUlQ9kxulfz5Gj11JYyMtJCAVLvx3TEzC1uc4vcZRjM
1Tq+MSwpfI9gZYgZGTu6fkGxU6bVnlH+y5NostGDzJHptYOmOSsWfQ3WLXqUkTQm
Cqdf
=9UOB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c6c1d289-c9c7-494c-ae79-6e1d8b31380d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8CzWVicnVpSTnKgf8Cl6GaIvoUdGPqk7ucesZOh7QceBJ
3jcOH6VPwgrFmuTXkkANT/OIbQolfmOPZmgYjemIa+VvBOklP7OjpV0ToEJYo8h3
Zy3ALfsb9hPfeUwnioA5Khw3C9XgJbZoqZ9Zv37TpZpdbD/o80s3f1DQsZgdS9aF
Gl/RoK7oela0p1FpIlj9fDiHLPGRIozWjjKabKe5VEuA1v+93oCm9g5re9S+RF0O
IjtLQ/BNIk3wxqBQfyWnclDCHyuKofOj1Hvl6LnkyKl5OyJlH26sAccSODr1AYMZ
cFgSMQL+ySM0/KBkxOqxGwZY9Nwh7FwOK8TO5zQqyrwOkLNVBa8U+toLGzVx4ApV
gTpIG2JNTv8MsMeebLsYYCpn1GISSn7WpYotFLqaUOyRhYtHPlyZSDjq+T/pKz0p
lZvdKUaZ1ysoHw/YYc6p6lJvSuMc5u+kbbhy423lonRjnn8Kn+0UO6jQDuAlhel4
Po6hj2r6cjr/ArnDtZVOvCtxA7/BIFr+nFkJU5jdr2jXsLNLs0+r96nxTTKJYU2d
NpY0po67BB904YMnZXwfhMetX0CmujGC/Azo60XaLPgxWqvYlGYAxNSacS2yEodf
PTF8Swz8htLnNW822FVN685wzKJm9g4DdVRsq1c4x+Ncn0wC4JZ/TaJXqxnyEgzS
QQGWXi2k8Znuv+xtf+PyhycS7YCgzj4DtH3fP55CAXn9Nxj6FW6FRFxfvn5Kre/c
pPzwx0/ZCC1hlGu3D/ea1no7
=8QZ6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c76f2bb1-ced4-4505-affc-dcff551e02e2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8CCliNkjN5MuHbGMj4IzndmzMRJ1BW35cxY+QlSf3pDfw
hoglzqeA+sI7P1/5JNI2RU9YomQfa0ZAGQ8q67MMQSm6lKvAYMDzq6O6WzadvrE9
aZaS4VzinPWTsuZ1F4poZHz9EETyPwoLOAuZN61Z1rppX/Rspkwu8IcmcAzUVRlC
WdwVqkNMUUQunMs77Sv+w3/GLvgpGtAwBp7TrPfmQtQz4qRHxkXXGbQ4yJt3XgmR
TbSr6TFw0nonNaTwnHJ2CXONtzjMu74QKsfTX7Bp9Q2zhC+aAFJA7dBKcPYK3ZDh
326MRnl9czs29gcJKuaTSM4soi8xjnuF0tjGSEt/9UIrSn54A+O8HptSRnG1z8Sc
tox89xoNVqyB+CFKgUQEJygOBXSckh5fTf6QW5viiJeJ20O17LoM2gcWE/3rRQ3V
OtzbA4jqIDSRFff/YKv6xSwi34Ojgy8SsjmcS1Kar7b+eN6+5UsWiv2R+1FRSe7L
73DYZbIGJxStgperazv547X0vnulgKJQLydMfz7ilOPkcl+vnDxnYIWPaXAKCJnk
jal5rdyuHnxYdxHg4ferm8ZVontx6EASksWCXjYpkjSp3ObvOyo8c5jrMnvNBWHD
6Rb8IcrZTFIGoX3zULE0Qtj+tRS1Efyy0VnBBB0RsZ3x6nS6GegWgIcykPaz76TS
QwEMSZPibKxWVWpadIJaB4IC6HvZs0QiYoRSQdWTUTx9OLy56xZWaCE6oSUyOD7T
NmlXGNyFH98zOpGPh4FJsA2XpJs=
=XX55
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf53fae8-fb8a-4916-afb3-6f1dd9df6de1',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+IySdjMue4OCk/BszvtBv23ZRH/Ind5KR0OQjSF7zf4Ci
PSOugnWaHTIhIaalzxWghB8VzuM4uIc9tNPpsH3ALhoskzR76lOKxUuavDHN90yr
beBLU9DPyFKPafymxSMrMGlklvyvf6D0VZ/r1iJ+8oe3x5noowp9z1N3zAfvZGgG
cnHTgtWbNCFykTTrY8LB+k2LCkU8kedHwwc/rbltXnfBab+OoNksR8+d7vdNXM2Q
2uuaL1qC2yLGZXl6QxbtzNdfWnJk79FHAWBK8ygD9f9BgIPX3qxTqocAnDZAJ7LI
WB0gMt1J4IymSCCZuXXPMdr4vKBT8bbHSXFiy5EgnWiJVjOUEm39JCkK0Qf6mc/p
ddcR0NGVJgx8pEBM4aRSyHzkGTS1jxiyHWizsMu8MWk8eg3o3oxCilzgacJQgUWu
HS5KcSdMZyc6VSydkndd4Je98YId4MIL9oo19TAN0RzTxsZ9edgntqRUv54+6y53
g4Lhjsld5d+a7s0xFuPHO8CCToD5jDIEYxYnNPzQdrTakmQb09u5bxhLDwI2II8A
XvcOTy/6xYz/vh90JNOH55m+et2N+Y9YlXCtBdTMP7JVnH+NjAnCO8RBC1Yy7O/u
qt8UYotLSJLiyjj0aP7/ZytTg5R2wWz9mfGoau/l9u20/r61FdD+Vis3yFzGbPPS
QAGnPyO/xXSAm+vmsfEtm8vOvNrQZalvNED9c42MdK+xIh8oTy6FbI1Ffkqt5anX
5kzjRnhufJVS0YdFglBq3cA=
=2YZc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd0146edf-0a5e-45df-adb1-9dfcad1f7c76',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//V1qbCC9lm+ziD8+zN0J0QCBZk/8NrV7lR/38XYrYrLaC
o5g9PRVfsr5fd/Um4A1H1Ww7cyWaBNWown4iQ06XXgfrI0Hbvfe8sxQoBI/N0GDZ
f6VJtVA4ka/zU5nbixMwj/tDIiN3mtKNsmZJ+nI2QUkyetkjrmcKmiYyA7JjwFzL
2ct9FMVxDpEUpsy3So0BWdf+ucfZgHmZDJCO+rIOCKR7tY5DUCByKnrzP5hOklrM
kHoNPeF9LjuqHmwcFmlu1i4OTs575JRfFAZSt6pXXvYI2IetSNiZF5h60RIZc2eN
at8/tBcR/5uQtgk/1car0/OsOjodDNnoG49+VLk7TFHk1OPVPSs+ce1cMMumYrMH
5mZvgekl4YGJeyEDhXclhNNFsdElUL3uMIbrrBnw7R91A0ES/Cowje+Ive+JE+Fe
RPaF6y9cwwfqx6OZTtK9ZdWNXJLGkxd4cIHzxbicxMG0nhkK9zRAI8nJGSuoFYxW
8PB2NSv1Sd+F8F7ZvyVrfz7Y26lKWrQ8sj3NZv88GG2EzFWU9n0XWxeq7eeCuAMn
nNZAbMS0qoFPVZr7UMYWWAoP9ltvRCKeGanEuvdpYQCg0JKm7z88ARhROXq1pXDy
uh+tipkONFYHCG1Xqs1dDP8y1M2hwtlCoGHFXp+znayF8CuUYqetsyfkDMU+zMTS
RAHw1Zx2Ivmx8SM+ogZMsc7h7Nhx/yyjIQoQ9JHZGRH9RDqyXIXaFGsleO051Dnh
E01qJgiI8/ZJCe6sydhB0QHQwZZ7
=/1v6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd2ed5839-744d-490f-a7db-631a9603a0aa',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAlki11Q/DGFvctB0PcedRnDpg1UkA7NFAicDqYzPhfgSZ
tivTwPu2TBKywP6UcDftEug6HQVBa+sbcu1Otl3bKwp1iaFHb3L+9zj9zd3J5xJM
V3iiySXc1u/AtprGkUHIovGHEL8XlGTmd8wgSe4RSs0msDdOAUO9lYS/pFu4OwHa
XrUtmXEqsLs2ls3of00XfyArHDyxRWlDnZYUHdy42GLv77uM0YORIR0eHu6KSOb9
ZQdZqB2sFA6U94cQDyaFCkF21iM/oibplfUeOkfgDKCGVb/zKVyHaQ8bBwWCmA/O
4/WtRksLx8FrOjvTBKGc4dmw9GQXPi0SVLsc8YbXlwxC2uLylU+t8wS+rpVRv8cJ
TAjvOOyEtJppZi+bu7nT5QJbC98atIghjdNTG/W1nn7D0iAwHtxMINXgOrUZqjYW
9Vy+kQtgmHBUVlEwUx7M8iCMObTPR7eQGqeaKMBw4ObYKvs8ucrgky/DSXQF6rhv
b5UamVsPXCLZVHSx6TsOZTJV63AvfR5z6lTBWDzp6ORqqjeGgp94Rl40YIGQ81Jd
oKMFpQJtm419bWk7LhXtSwB7pDEGXfN7aZ2rh4zVYhFlEtb5srUX6MYeIKqWgS4z
5EN2gn5nOKzOFCbGx+8LaLoGZn3rVMRJGeF8rCICsoRYJM6Niwlp8jAKjlkMr4bS
QQGPndH98NsqPYomaaWMPL9x1qDDhrJBg0OiXMcH8RnUzNFldfSMuyGM6ZXXu41e
pzQlBmPs3Z4GXr5WrLAfBZyG
=POW9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd41e4f6a-d8fa-42e4-adf1-8e9a30572fd1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+I5/O6rMO0rRq6PFtIZRdli1ZxV2AmGj3Mv876qZMunIh
iyMzSoKyEAwq9beSOtu3PEFcqmeWTxAZqINp8lY/yYDdBbMs/wSwqaFBNJm3BBdw
UpWnKIiemp9Ah9jFeP/mJChuWmT2KCV5BlkFwFeXllIM3P8Y3J/7qjLpGdItbYbv
IKbqtuPy8aBTBHvzl+JBbI0Y9dMnB0RzIDx7UHXdtIFPgxNEVV8vwgV0TZmekK4a
zpMqLA7AOPXQeqnGW9Z8cBJmarl3BjaJXAylh9JlrtwVp4kzwd0SC0gX0sLtkETi
BGtdl9nKs5Xly+be4K+6yI7LhKozQbuyY9B3xxBbvHDRm8QsCgylnz2zdiC8xZvP
tCD4t+ywMdzvWeE/8YArOPkjw/P1QrQhVYUHCk3sDknFaf6/g4CluvBTUEN5HB91
6AcSXIYFAUYp6F6zTdxbnugu9doNINdQyc2hgQ2Tyz3DAdvxz8kNTqZRxl3Cgvnl
OtkEGbAk+inH+psr9PRn0eiVGFm7O7UoeOUkrnLk265cYItSCrH8X0sKSm6mBt/3
PAHtq3HLdIpXp5Rx207kDQOFJm/klsjPOvFolYfUD25MnLYx0n3UWFLzKvyDkEIU
IS5OCb8x0KsNzalrLtOL/gk1huEKCLqcGw0bcPR9b2KZZeqX1TPp8ms1SOTmPSLS
QgFRJ8hXExnbK+eG1m1zphJbXdToBkEnY1NEdAWv1aj0KOv+sN5QICnguRJ0fO+a
c9f6fkzd1h7zUWfivmj34E+R7w==
=TxeR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd58b7d3e-595b-4995-a8b5-9edba2392e5f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/7BEQ5SiXzQqaNSKx1Jmy7AD3lGg5cbfdYyqq8t7pa0HA2
4QB246o03hn0MGbe18w5Qra9anex8K8OPe85P2grbIcxfX8v1UWXRHsRdLb8+6KT
YPosAL50RRpV50Yp3XXD4W/3xo4S9OirKH+ud1ze6Q//+wuWo+e7192wLT+4wld+
PFsm92dLN57YlH566U9RgS6l2ht9RzzjRJjl18wJ+hAAeofTBWYt9/R69v0lvicE
mXv/1J1lyFUwub4Ujb4zc1eNFoc4bvE5OxBRCrjP60Fg91p3F1A3TS/c65j+VAn6
KGfwQqDRYTq2bFHZWc6/zkLdCYEbzhaR+H9APfwjJ5LG/CRYedqNYkShb1mK1g/u
Q4PQm4SJpW1neAAieGt5qUxFymL1ZwGvXQoI5Bed37oTjLXcxNB99vOtOeVrGkiy
Mc2rUrjcEzshGdwhg1UHCVkGkuQqZBRDRS9ccxXLSnRSvEkIDZZttBZIQ9Id8Z7K
62TlCGFeYSbXUrV7Z6VLzygnUmCZp3aS69eMAyt+UZl1ZJeurtnugW+IcBktvZPI
NpIpMbwCTY1De7BR5VgODcDoUYalNB1lg01csmdkz8OHx+jxewLj16cZIc2daGG5
nkRJocjrY2LiztK6sWa6nI/0sKRacErFzWry52hjLLdiOo7Job4XDiRvX3J9aqjS
QQF0HUcG5w5CywwpzTiUk3AFN4eJcKcrpmDRuZrPJ4Nf+OREQn87SzLGySeyVTmj
79KKYgFIf7Qv7rxm43Ufv8ui
=+wJe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e6210911-7621-4802-a4a9-723825838a5b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8CeqsacGK1dGUeTW9iN6Zma+NP3GgCoqH6J/znuk1fH7r
MnAk6piW6QZzqKhyAkdcOKwstWvEsXITqgHAqsgjsQM71arVv35mOWyUZGt0aETf
cOu5lkhfW6EKKZmuU2QL9AB7SNZgKWqMowdhhlM6w76U3bIM0hHrsh0fwrxBXO5+
e67pzKKWpP/hgNJNWgkOgtBHKf7zIXOP+tWCPdXBG9nDR7qIFLHHUQ+Sv6VleoCq
dbB9iHTjp+QndvS6UoCVfNs0w3hvj600iO11WP9VxL+IAdTn1hqcloEVudq9qBKs
0NSas0wXFlU3twsj0cg7hhRGxG3z6f5NjVYG/4qXszVFxBZC4KFm11FmUxFIB0e/
2ghHjmzoihmu62cFq83ewLPlsBKg7kDynAud6gz46yFpE1Tsy8Bzc9Q/7Ppdvc54
9axZKNycCnmnxJvn9a5CdIno0pM2FvZZWMVsC7haDRvjUNgyis2MJLyYoWsPmiI+
sFFqx4QvjbqwODGWN3qEDUAlx412JOIO9UwRYPc+0MUHS96aetevclj47LZMj8bh
P+Q5US37ONjei9icPbeHENCqZUdtQH7z6yohYLrkhkqjKc7ircE7YYK2QJPJLl8B
q3RA8rN8VxQI/i2xz5Qs62DqGFPAj6m9XUZZ7GkTqdN4FEsBJlPLpbY0LXZBjhLS
QAE+sxujtZSkmMS8F5x0ampjWC7SHcyAOu02qUn8ylt94eNr73S9GUgyQ3+Bt+ra
LhaS2nGA1IV27f20k2N7/W4=
=G9Xn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e941225d-dd6a-407b-a76d-376b1cf0c28f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8DGLqwC9cZNVa5kSzx41EiCMsZCcJ97M/LsjJItULXJiO
/k2aiyj+BQqMnAOu4hL4d/iPZoRcIQJ7PxrYA+dGZb+nIdin0M+ayE5aQmCCRefG
L1d3SYuXDv4ms9hU6u/3oSW31tp8sr4zSGcjtJuE26FthDFTTKNtAJHwq4UjXqMg
j8rHvWGW7QQbrxksl9qr3U8teXFrtNjPDK9R1F1/+IjJA+2Kv8dnBCtUIla55p/q
1IEVV5DWyQjZsBBH3utmadlJTTzMGulH08j18vaG4T9eHK4D6KRvb5gbc1BzMIWA
uU6utm72/kLOzFGG2lf9qd/ZRYz3B4d9acSB0UcyMsVLSxCiRSjsCo/zvEduTQdQ
W471oz6pIph6fVaG+AvfSYWI0c+35d4SZTiVKQhjK16qMgTBHd+2n/PkuHQaikSn
O6MmWsP0kPhAWfWd4o31l+tzUPp/FxbddNaVXA0uqCrrd423LU86KocOM3/4YGs2
bfbl1O/4K649QIxSfWq16u/wXM90QyEr/53f6lYArJ/C+8QO4/UvmJrGAnV5JrDq
y+58rrbIFEA6EO1VO0TCsbeiQlGyZmT/PtYXe39uq2wI9VIlsIFexcWBx35aOKHV
V43n8oIHG4jACbv87Ti4QzmA0BF2OwP2R2laPDCyZaBLvwxIalye4KXNsiTyYbnS
QAHJyvLSZzZ2Sv2ahUMtaq3/ksy7v4U2A3BYKDt16aBbo0CI6aeL5B3Y9Zo/6Hbj
zppZ6or6r9ln0yqQg51BS60=
=A0Cq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ef0e0847-cdbc-40cd-a780-ad09c74202c8',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+ICtV2ylrskseVQQ8oje6xGc+ErnU0mo111qIO8fh/HOK
BOGh4FFuzAD03/8y906ntinA8conCa7aTAB5YC7HiqeyEGWO5WemB7Bg+2+jcaKv
d/FxAr8DChxBVa5S+I4BeeV45zyAG+VykCj9EUUVcxc2yyDc3Bok/kgXxVzsWE7S
oe9NO6d1Dn6xYLe5FpMNHL2glKTIZG2xsgHDTGW59FDfsd0u28fO7GgOtc8A3CuY
zcJ47Lp2BjcouNezG2NrZudEGvISiVGu++dj2+NGMIJNpGp0AIerivvgcvoCnekA
fu6GGFkWUaGi/4fJEvDi5AERDD7t9zMhSODy9yYfBcBeKaqfliWdYk8v07OB6Mol
1x+UQQYZciOhfcNAnEdcroeKUWSn0T91uZ98teSRUCLEEYMZOMY4oJjfUBHEeTTE
kuhxd0speCfrR36Qzgr27T6Hnq6i561hCIkLQn/kMvYj6CnWYgb9uUA4Qe5G6VmM
7wspL2jVapE94Eo/DyUP2FCb21lgSj93GK0dUiCwT0IPsYa6E8vhLZQhpQZpgdLo
TWdPBO/kAs2z0Zs8MniJU+8FRAo7gHZDHu/gjaZ6ey93GAC/3nrsgkHO/MOFP5Nq
m1q+7i7IVqh5AU60hfT/4M28uc0qFia2+bCiWidQhkvqvt/YgznJKMBY77UguN3S
QwFsMu/m/yHTZTThMXUrYPRLuVe07h/HJPYACTcUaJLxJ7kD49Hs0ccULwKd2MOX
1Sk1WoVSCldeie/JeISSVTOjIyQ=
=Q9Me
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ef5d2f39-c74f-463a-ac00-f2584372465d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAyHUX/npIGBrlfxeCZ2t5tCRgJ+Zf2iRU2z1TxJKiczZg
7evpIg593qZVw8Rd+N/j6MGKXqyr5QkwhxE4opcgI2XFZm6XZIpTZIExx0jGQmC7
JuMQmoTFXsU5aCMhTl93TwmL4kb/0Y/6h81ftIDN8kzh5q8TO5nxvA+22jzsKgl6
0lXjzaB9N10BFj7taYHQ3fVpL7XOLqmJYyKIGReJXXjglgN9k3TgZwu8lsezHYj1
fhVLndSxOKDfVoezKPqLwqSY+pmC7WtYBSe5piQlHsLehg7o5DQoOCQR1t5Ogt4z
OMrnep/NU9P7xG1m1Er9egmY0AgmHwDK78EQw0h6aAF9o9LQT3zopIBCoLdC/rSl
Dubg7blwBW1kQPYSvV+2fLOqqhN8+0MKNcVCmdjSEPeY8RjiqivEbV7I1397YGg4
XOgzp0HyGXPMAP2D03ESux0pm+Ehg4dEg9JUDHfYpzIoV5kqywUbndKi+QJeroiN
UrbE5YPaPo6iXlgcvw7/xxa52Hul9x2c5nQWV7EQYUk8+swwOXu0xipwPMgI3Qmd
m6v0D41StEbxL9rY8MyHrzjY8w2NVs5OAbYAibTDt5OK4si4mk0fsPQTuQ1Naogs
0ZChae69JEDSjsMwcAMhQwg7yyQpRjRuGKtJ0BBTvzRJ415JbheHDG2N9fYDxdHS
QQEtq0LZDqOLqGuBk+sDWUqsYXQCiYX57rzj/DsjsYNZFkX1ahuaAeNeTHrkAqoh
2WK6KgwdK7vga7izCmliQaLt
=rHV/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f29ae2a9-1885-408e-ae11-aac31036e57a',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+Oq4V8SHZt04I0lzC4OCBLp763DBsvWwktJTLZHsxmPVg
ibL9SX4Q8ZUDuk/3GjvBBzwfEmvvwhQl1dahPDvaFZjnYzr7HXCx7ARrkOQ8sks1
1IUUWcfcK2krBmmmQ618NkM366P3y6/x/oO1aS9YlW5i+f12gfPoIA9c+ZhtHpPW
TIGCEVesBgDw+WRXuT4IRsLArep+jy9/5SFN7NT/fysZHkcuzKexT7vI/YWfm4H5
Q6j2OtgBNOi5D48J7Bx1FWqkkKWSiFa0aZQ9Cuz98oC2TmCBZvKZZXqHRQnEI+iK
iSp9KC+GrA0gr0w9HHveGUvSnaEpECJuBbZDhWryDNJDAYzKCLxyU2oO7ZtkgKT7
jujuX+HhbupvjfHxQunYAi6S7tLIj10ciV345lffOhyQjGl1iH9CvNWQZfSG2XAJ
ArHKoA==
=oA8L
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f2ccd215-cfd2-4eae-a077-d7cd554c78f7',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAnRSIM6kRcbyVdHRiW7lURD2RVWCBdcxbjNFQVg057XdP
R8tQjecsRL5WPieMkW+Cwu6l2YdD8m9q6X2yLICOlg70pctaKTqhPK6ClUaRJuy4
FEhJV7Ve1KxZVNgceYs8mfqsMr5RihrHTpXVV6EN8JeD1+dKhsd/6/4wW+8ggpqs
lsI96jU0jCnTAxmdbzBmecruXmPToakSwltvgDHUPvUHUvBIvhcqdOLcc31vKqgE
xIP3pzKcsI4PiV6ygQVNLGLRoreERvDdYe4GtqU7SlRB80fkeUnOoe7UI7yZ5zSf
8q6bas8jURrSXqMiLcRtSeoUnZlrW8kEpDV2PSQpu8RFIAbIoZfAcIV1Jzw8g9E/
ujRB8AcNjbzk48X80MuUoIgYVtw0lVIx3znSB/aRCC36uLmmWqdUyk5NRjVD8E4f
ljISaYfp7ZbpJQhfMsYIW031IxBuM6iQp9RBKP3Smxn2RC5cbNPkAqYFEFvNRo4k
/AlRXVLTfiXO96SgDMRzqXPEGpd8YJLrrQdAgIkQzgBFuNkAlwr543EhkrEssEbE
eZ30Vo0vuVd+XyION2BKQ6NV51uRWmTt7ptpZ8FqjGJBvxWJqZaIPJAIV0FSqYAF
ebhFRQzK4IJnldXcyHi8ytOsFSYYAHIlhihvM3oOHFcHWjyKShhlkgbcnEzpNPrS
QQErUSzdRm+aR0WDPTYWKGzA2hajLYNdn6/udHCEr7nz6BUnX7KKw8DJn/QvYJsr
zhXePA/jPKc+wcQjcx9bJFKc
=V/Wu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f61afdb0-2fad-4e22-a4d7-361e10bcd1e5',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAlwan+9uRm93TdXROFXT4SdtaixcEAwJ6+8qw6iKyi5Zo
Ls7qELtgZUUekiolG3K+7S3pShlnc9RzBESID2UU7vxiXDqzxta7iC4hnvisZI1k
GEmYO/vUqsPMZLy1/MkLLl38TmgnOHv1SbgAgQuhW0C2ANypXrxgAV28sxF8hCCl
2v13CgNnnaBS58UFSJmsSLWBbZuu/l65DCdBtM3firL3EYIn+vzJqp34z/JO3eAE
qYPZ2v6LHwv1f0SglS6Naj5Ixpo/aW+dQ2Ysai/yEjMBhdaLh7GXcel2DeaTyqaQ
A/tKRM+9nC6K8ITRytdm/yZxc8pJUG+F5Ap6OUr8TjPjpoKFm/U74mTY+6QinzZ/
QGxNWjxLltTlS/sF76JjAxweNwaGRSMUP5l9CBIlac+pf/IvV0iALkTXqhpLDIp1
fQipuL2YTU41oo1x8hyZg2lKp/0lPKVIPp+zK4xqh4WT+Ggr8ZnMvU4T7mOz6dzC
+sr6yl13ZuxkMjIDg4N0TX3WjgHqgcpvEtghKPFvPPHK32NkwCSvPdQk13lPSzUh
XgGE4BOjU7i8vgxNPPa0gDdapf0uzFQDoKN7FomhqjzGgyUfSLW/rkUTyuHkt57s
rouzRqXG57gxuhsXJf/vMiRhQF5oCHxlxYpX9xtcIqu16TofwicxYt0bBtvyxb7S
QwHNMDIVel4AsGDjsuqGrmeb/N4ErcD7Cb7fzqL+Uo1zNtefJWodaFkZO/EzM7NB
GFd7zi23tm3x65a7lPmh/JoqvZk=
=f5zr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f964f923-d977-4fd7-a936-2b800e55fdf2',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/dqWYBCtoxDmMKCQmerfKbQOMrF8XKPrlZIi4m+P1jKDX
M5OSy6gaUVXTZ9sDM2JKew0tcQai++WVpHxeXDaOPCB2WRPKsWVQzZgXMaVUptAE
za2OOXWtgxL0oOSd826AFAtIlcVviBahQUdB74imNLwJIF8jU/knh+hHOEWq6XK0
5Ws4EFX3EjUVuCXysU50BcKK+yM57TOD0ni6UJzHxtSuMbfhiULuUEYb2KU6mV3m
8FryXzHLQHiBCNcecj2USAZ1NSFNhS8/6tfAU25suIhP/1c3iMS2g9wcgmbfrhO2
9UPXb/kUVKCvNZ4H2fF35T4RFmIudtHeL8HpVC7MkdI+Aftf8pgQzwy/fcP/hVqY
oEXjE6TpGnMwm9KbM5eeIkMw2dcG8s2h4bGVTIxj5GeULGfKvUUuO3qUY7o10uY=
=IA0T
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd80d2c7-f791-4f5e-a354-56d36ed3801c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8DA3YTacmHwzjSKrEAouJHdvjUuCpEeInVYULBGNU9BJG
jE1p5dmC6tlNE5SswDv9yZppfKHVMJtgwQL/HcPp4s4f64ZBz6eGezWcZFcypZJ3
xh4fO26uDxMYle05GmakB/StmX//yUuzkv8q72YGkMsohX1nbSNMcHm/aTmPXZ68
wyxtvwFQR92yBcVLzi59xVUGCkXvtMgVvhgfWwlRSdUZYcQHKspkUQVadVRmGA2c
lGFcNaZ5bi8BOZMWKYCSzjVLYkt/Cgiv7aZ3+ZmqPNOAU8ky2cYzI3rdlIrFSzVE
dsl0IT8nlypsVByRxDRJLPhroZvpw61yPBGQe8c5Td8Pa5f/v9kz5hua1FLh4yrv
uLyu7XOG45wpn60I7/NVL78XlzuJEwR24nqD2EVU/So7K9fAJw6XaA8ZEdJY0jJB
ntcgOlFCqvt9roYKg4CCGkc341SRzTUKQ5HC1lrLJQTGY6soX/AhR0EmDDK33RM5
6CPxUkcIfae71tS7CIn85/nIrn/pf5U6452ibHWnG7d8WDMC7eDBxhW4UXdGr0sS
iqWEgAlZ7KhnU0l5iTV4JkqECWcrQ0hditchWK+0uT+ii4NsONPJ4t06GjQm9XVQ
zHW8dZL5+RZ/jv90bxdSDrXxT0dGQB51iu1NRIP12/G3UPDuDPVqwi9O7QgSY/jS
QQH8OU+0pGimmhY07e52rXQ4zMfBlnsk9EUYdBI8lyot0lQlku73yAxGMmo9kzuO
347zCmdZ3OlN6bjbt0CMnOjd
=Yh3O
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
