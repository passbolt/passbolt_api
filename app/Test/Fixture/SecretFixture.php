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
			'id' => '00338cc1-a9aa-4034-af1b-fadaccacc8df',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAArBEnODsPsLOPOHIY1zL69r2tJEkMLlMckOit0HC976+9
aIdZht5KJ/TyaXpzx3jDYAq1CXKL0BoNISwA7EyxJw57WBNL8VRFDJ4OGihHOaid
WKRuStZ+NQDvnXSjGeIpjChnX+MXiQBomzv9QDIlbyeXxrAgQgCoDu+rYCX12JmF
NB6Huc13jiVtWgYt4R9KSBqvaWzUcBXF4FJA+tZiREFr894DoeKu63epEkr/inpM
847dIgpQDkfb+wvnSNakDET3h0+Bg5KiocmrgZ0m8Jmc30LCD4sNxA8hY7f+hQBy
fLjYHIKVAs7qrd5Gbdw6+tHbDtX8OfeCuk/98n3iDmiNhx/UN+2vb7fbrjDCcRqG
gHJUU4aLkRHwgq9QcmTQqLZCOQTb7BfRjCb4w9UsYDMUcGYb5eNLq4+rhTkTIyWM
2IRuedgRfyFn7OrpQH8vMrcx38NOtady2pnwjupRA3OOWnrQ5BaSF+xwC1IWDcSV
DMHyLNZN6S/erRibZKoULIcjQgTHos7BNloWpk67l6WU8BPyjYR9ZG0MR3UUKMzz
K3rq2anpLmtkL8XWuFjs4i3OvFGc9YvwJBUB1QvBNmMWSBWE9ua5jNbEvvSB21yw
eVc/XY/vVS1L+7gZPwLb+nmadwMI0OB7rXuqE35oW/0FoSvpFQ8OcQjagRDC7cDS
QAHbSmzNrF8dVgWGVFz9uhzAUhUj3H93lsued47eaTXOUnmrAczKHzLYpAn2inHS
9a0NATMC6EeXJN4Pt4/Yjuw=
=Mu/d
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0dd522af-15a0-42b9-a170-0cac07a2b63e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//cEuR1OBBS1Lt3eNV9aaJHPIQlEqDnjB2HtAsxH54ymJO
zjefv16jen+FQHTzuHgZTf5CuP1jVZcBy8qJcWGrixuV3PHWJv36ZZ2jEqQHwkcR
eBArQyTBdhqWSMT9HcZoVkMh9Cg+pFgdu02ghby0APJw5k/6g+yM/F3OL2kUiGob
AGd9uUYwK4GNEU0ZRPTQgG3YIS2/0iQhrMqaeBCCZccYvg6RAebzsBawp12nNO+m
LiVBhW4fBwFQgGdNEXhP0H3ZIP2JV7aHhMk0JsxApn8ZCVIvkJsQIvFc1wsl8wZa
efCxBC6eb26Cie4J6LsMT+xB7IlQDCl6J66kdk3SEgwXHGFiH4D+8TNCYuRC/ZgZ
97RH1W9hvSd12eX7of2UhufAQK5jULuttZja9rAgD7gkWJaA9xKAhn5LWxd7bZok
5W39kX2oDr4VyVg2mMEe0Lqdx8wkPrWL+zsedga5tIRrF/XwuzriJpLEkGj0ud+I
1gGBxJ4NvaqbfN67YmfWtpIE9DF4jCvppjWXT3clq55d9AJvZ1c5hSlcD6r9ymOG
AJaf/yTU98HYTxZeEnncC/mNaD3XCbTbI49CPti0TYtL/qJrOwNxcj1LAxNWhKh+
PDLikuPsgTx1MtslSY3lwyp4NpJaIsZKshnUMAU9+8fppkF/AOfPQxyvXDxZCFfS
QQHMqSAFUS1cpBHVsoU4VMcjxzfxRF2IU+MbSUfByOrHBSrXuBmb2cr0oXyyya7i
akEwsb+EzVT4nFlGw+yAiLZA
=5Euf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '20433c32-d665-4b8a-a6f8-3c480f4899eb',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+OR/jGmN6qMBLO6TbCXAHevC86Z5+YiSZFzfPXoZ4dkDD
JlQvomaV+0zM2Huv51gFxBtCCuOu5ADiniImBqqlEG1fRbWwlqsqyixnduu+T1M+
M19un7LD5O+tVOIdHL6zXfV7VJwwn1Z9S+e73rGX3cUt+1LBh11Ec9ALHYx+BXw4
j5IrmePg9IHs8Z5KR8u91z/Uo8oNPbJqR7HbdDNKC0BpBkGiVcCwcZfHqLxoh19a
3PDokw1Zkz8PylTryXns3XNQj+lPaQaol0h7F07fZip3PLZKmncMBQH3C9Eol2wX
o/ftCK1h6cCs9oa9Tc05alSaQ5YSOpTFQOSKT9UpITj4EEvpuGeE1TWkRBSPhWCX
tQFtHU9NRWkWeoJifFboajFwO4Z4MTxesO2/uVBB0JqIrrdQNZVU3+55n2prZdEP
r3PSe+igHo+QHNOwVyuklABd4bIzM9WNgxreI1Azcs87b/J6Nfs8R/qhXdSZ10A6
idrkq2C1xLK8cqNU/F6UCCfMU3cUEbmc/WQAmStiG0Ao0WWqkZRniLz7A5Z8Vei2
6Xu/QyewFu5s6L2wwFrmVT99eXKjA5zPMFR84gdctgyamhTtitcV+x0TZ38rS0v3
A/3tJ9EcU5eLdsciof/JN9NdpLMBlkiKcG2oLOQVFGqDFWNn96/2h03I1cu3cUbS
QgEtv2lIEpwZBW4WMMr+OF5IOJrn6feJkmG9fUkQdDb3WJ8GuJZoVmEjIfTPDtSr
pc+m+Yp5OwHB3kGNwVufzVTJ5w==
=i6tS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '24058271-ef86-484b-aa17-8bf809c7cfdb',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//bSjCy0Us1cCJyOq8zgIX2itx0XHTRpgPrr8iYewlQrsP
SM0+EiRZvZwo48+T0CHm2KxjCTyeQbdMaAJlhzCWp3iDCNwW5x9zvE8xxbBKrfII
5GoZ0zSKplgW2oh6jU2qB+vvUhwtBdEhxMNHV1SFVqNr4Wwi3fmlDN7Ch9BiZ1Vd
TcOknZ70TStVFqF3OLXh1301nQOdRJ6jzfpLNqMpcLRnVFsWRMXAGla4APBjZPrq
68yY3JHGMsHHoWX1dqBc6gwW5ZlV0TYBE8nD4T9FiM7PKH4AQlnHds7VAOFVezcf
hYL61bN2KW2tXJqIu3aAmQTd1wYTCk6GtAos8NJkt4/dNMrBO6THmg4qkhWe9V3j
KUX+XsmLym8qxh4glvC/EIGwdnWDhBLOrledoMZjpQU4Vu0uIhm2EeERvzDadJ2w
e7oaC3l2BrL2zVwF5RJ13beJlgZs0sAgcuRqEifGGwoorUm78cdND+lQb6qaz5eJ
EVNssSLw60FrtDKgDtBu3dPKf97DwOIX2frp7pWRtBn1PQ7QVkkipah+KI8P1ARX
TF3r6gtLCqv5Uv31Rc/UV987cCeF0KDPi+1VSQemuNd6HncyU4QqUUsFdJTkNHoB
j9/OgSqHPf0N+N6hc42mUsbk3bncP8QU1G5A42XwUnvQEhPrCNPhXtq9Qh1kTJTS
PgHgKWntNxU5DEcZqoCGdijdHPlTsktJ3EIExs3kJ4Dcto93kaAUjWQtjmlgUoy9
kv5ZsFOPqWO8KE6ASlIW
=/AV5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '25203721-d326-4ef5-ad7c-6b8991871f8a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//ZZ99aOFtAPEx76P98zCd7/EBF88vCefN3jaPgj/TIDzs
FHOMGF8OmMzWApYWyBWnyXQOYENVFDZun1y5VoXOyBAxvOOtSvnI6KxSy4pIg/lC
DWz6AbPCLthmUZQ98nXg6OUw5sHHtlcJ/lM6rPQcLa9j48DX+pCfvAj0F26MTDfd
vB6lTxMUcTGrvxSejVp/1RuZVecq3pQ9SSvxqJ4hOpeRHDm1LdCqNnJ1MKUUcNUJ
yrtk3ARH05rmGK/B6cf6zOfyZC7TEkMgTHQFtkJvAJ0UTCDF6l7mtQYJpwc3Y+Mg
tepQN4JdFDrZcwgpHD/x0PmbofUqN9UweQQezreNvuAfAFrlWaJBwC28xGAgZ2Xd
nM2XShNmPiGhgNjFpTD1mztbDotPSITqKo5zuOJ+y8iQLYgf5Tu9ahshD1w6/kef
sLrLXlYgpBtdmz0M49p7AHuRHrG7JbKVH+5AdegGsjVvJbbi9EaCF0tOg8zedQts
hxYPfrqn/cjICQ5+SEl4dW00kUJ7qCaH1lgIgzxRcjHwRejez1JmncVvj041snmh
z7vLtZFnXmjGay03m/47BWDqPJDA45oyjs3ARCsTJY9wSxurBwmXlUVIbfToL+8x
NeumUAm7FDqJlNDOGU6I1IwTBQp64c9Ktq2ZhrZeRC5bZwfeTSzonDdflUZjK3PS
QwH9fjXOJLJWvvSqN/rqyFYDquQ5OjPW8QSbO877eEKFfiYdlEOX+Gjo3I4inBCp
XtECS7k7gdV4jpx9TYyPxusOHwI=
=OLS4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2a21b118-a987-404e-ad17-97b426fef00a',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9FpH6C5ZT4/m8vH6T/OZaDSVPsrkf2UE2p7b/tTNB6dmf
Go5Cg1iVP+jm0+wvqdh50xeDK+YAq/RWyXXrA1TuPAeDTpPKXMYCRCYOzjNnKJK8
XNRVxm+Lu3abvRJOGByPrEgsf748d7BC/i957/UTEPpvc8lY1ncI+CLhkxTuhv0V
fA+9FeIAbjDvdH4KPN5vhM6PavhvQw6ryUETXVzLpeSJCCeSYe6ix4u8im2IOdYE
Ha+GnY2XRr+EsafjuQjmnTttL86bTG2oLdrfiz8E3Lz8aaXxMwX4HxzovRvS+pV0
TA1ne8IobKJ5eVfS5thqwqK2jwOJCb68d/tyMOR3rqSN6YL1hFVsaX9yqyBhYvYo
3RSZ5uECmu9iWcHTiW87iRLJqHKmLFHbwsqEqht/z/X7ucWJnnqyTz5gRPop+mVd
wWHywbJCkWLF620EvczwhVsZCmXv5SFSlMROZ3v9BubEQF6551C5xbjd5kunhbW4
CKtfii/tYzMhtBXp2CcjAa/UMq5cb6+guz9dbFez4fPwSe/WcK187kRyZn2dwA4B
UObuy/jyoMvCPWdKSsXTmMv47EPrfQjRENGCvkOd1aHMO02/wXUwBkDr5Q625Tpx
DWW29jXmMQReBhLTIlDkaKxDu59NbV3FvaJTL3H+eywuVxTdKwmQLqj3FDgGD9PS
QQGe5Mw8fs73QLyUCPl1PTMlOjtVBcaDhPbRp95h1r0GrScYCTeTGq+7qKLPeyMD
NZGtTIF7H0tKUuKEeufFDP1v
=t/vO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2d5d7840-3c8c-4362-a364-9ff81e781ec3',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgApa5zhMDIKzZT60hifkSH2HztEucPicKLi1XdLyp6JzQw
FwXAxbPHPGtndIreg+3X9a/FloFLM2WoDmhEbzS1xXphA7GDUq+rJAbzQX7Z7kip
DPZ0HljEGvF59PJXkAFrqOouirXw0148V+3svOibbyqCt1bgOJx6S58NADvPeMU1
aft7A1o3VJq96xovtWt4jJQiw0Mk0kbmWJxaNsz5t//pF/bEOMdUfFO5J5VPtPV0
XvSMvC3hnzlbhfJ9BxsTA7ytI9H849bWuhTyMrUmI5IHQKqoBonvHnM9uUgjaL4/
LUWegQsUM7GGF5aTglLAGbUUPpEOZ1lmEcjdJgf+cNJBAeWIe4LMJQzYMenrETEE
qfr1wP3Ox5UsMHYNXf6Iwvtyf1YOrc59W8nLU4+XeyoYWzO3AMnx33EVJfBzSyPY
VlA=
=wZxS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2d978b77-b4c1-45fd-aa3e-4d0c6dbf328a',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9Fgt9YyWaAWhjkvQlaKD2Hrq46dWUFdEA6wET4/RVibJg
HiTyoMu2jJIR18N/r0Zf6vEcB9Qft3jqz+4l/XbPgDaXGZHmA25kh9ZqWErXc/hV
FamWJ3sbN4Crm1e+FrUnXiYK1qweQIFuPYcc/vGHmYss4KcaVWlOjY8AjK9J+CMU
29uh4uRb0DrnSOs20/a+nZ2pvz2q7JvyuHS+xckdBKEd+WdSEgqaY2sO8D4Ebznz
rdqmyq2Qc3Ic55gA9asxU6mGFDTEqyVFBqB4918JveJ2MM2/5UEllQNAtg3Bmi7S
/vv0qpZcJEJg3DE8DBtsD2k6DIXMNQP51dEuI2CogHH/tIwUs9HLH5oUnka/prsZ
XbXSLTk1UDRuSXes8VjzZQDJs7RPXJHOn7r/PBcquBATMjIaivx9tklm0kPIfGf4
h613ex3HSC15yTBnaRPpLWmf4t7ocRofmpfLrXva4tphVlCseX+jhxOpjaFK0r0P
XNXXI5ErKx14I9kvy0yobrGqYUFtAJIPtxWIXxdBZ0UtsroEzmN9a56R+AtkWr+L
EHDEwRBv3yaaaH762MnEgdJUGuqD9ZTkBPFOyvFcjwwZcm9heyJRNfzNHhBQPbtb
Ki6mF/CvaAwYxbRJX2uSaN+GPREOfnqZi17+4JfYDxD0JYU3ClpG9CHMPFI7lhDS
QQGn+gOPHB97pJNOSfQe9uv8s6neIn3P01scoMlYEvg2dNzIp+2HJhKuVJkHr++5
Fh+gi1cEWdW1aqlpRBHXUd2V
=eWYZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2f8b6709-ba96-4f4f-a4ad-f44fb8717be4',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9EJBZJHdVJymQSo2CVtTQh2/E1FIt43ma8PBYb0J2WEa8
gNn90j91+1jZNaPZ8SOlfNe4/oFYlVsQmqd5Cevcp70ZQncI2xJfzOVHP3za7dwi
1nZJ/emcEgBsmJwl+KAQCFwPCkcKNxpp/X161UetOgLJM63M885CE8YiiOzfeae6
oMCxpWb82MRqYi447oSS/OCGuK+X/i4aXKrNb55cBdmxsfEHJqld/cV+o5zNMPSk
BCUUerW2qS1f4YsxQHmo5N71QFELNzFJ3LbyOOXHynb00J4Py20MA5a1FkhqHyVm
ZI0ctxSje3xJcjsU5C/3LBfe2dJbl0U5r/XJyvNyskg7mYVsPj7EWfAJBlrjHs/f
a8DZMpCngkZ/H3BW1IWwsMy1+Nc5zAPUgbiqVfQem3ayxEwCeiqvyhiuwY1bD8/N
AfsEbAfApYJfkN/OrYfXpCd/HNGQ0FmnVTI5mkSUyC+a6mHvC20lQ1mZzhvDKifl
JzgW7a76E+a/sJf5Yo3wafOkjmjTvr2TZUg8U3D9AG3HA3VgHwODKgsxMDDLye+x
7KX+EBSZ1O0RDSZFt/fWmZyXSua2DoekgBPEpsaz+EjvKoPc1EEm5+fsKFWwB9R1
4dN8vXzNnY1JGVWykocgaIGU9oN6MNLnff3Q8y791x/iFFZmmjFLNGAGkX8uB0rS
QAGdPvDqeQGYp62IVNstSo2e6AJIIUERs0oas9b3H0WM8DEfbDi7w1CxhixJ5OzW
o2Sd1oWkFUXKpN2aUpMIQnY=
=6oGJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '35046c32-49fa-423c-ac09-eb673e45acea',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//fGKkUERUrTWZ7kR1h/EbHBhE4GpOXx1Mv6tv/rkHjvSI
USVfBaIH117ezpNiSH/1kSsJcfLF89veoX+2cHyVDPmg2d7ykcN2o9blDe/784XP
feg/jICtwgBDkBpo3VhVf99vXL3N6MV2p4udGSk7o87FcV3G/CwgNVefLLtVNoYi
XP6Xa+/mr6TnUEQSzmqy9QhXgnEdhjJFWCaO0jznYCVp0kBfs1InJuqK8vX+L/At
Rftzf1c/ucZAg/R8+g93ylmY1vMtsucgOsmVA60dVZVZgSevH7VIRmnbYm6GGlrP
v4ev1ZiNnA9l06Iz3L+wpqbEGOfqCENVoWBd/E/lR05pRq8+Htd+pAjq0YkMlBNK
6HcRS5g2EbK3fOi230Ki+Xjfd1XP5aOH+jLqu2oA/3SjUE7SI0Ew6W3S4sNsDjru
qC2bSGSPePKpkoGvO7ym8JQEC5r+Tao6YE5r0HFEtdSLyQ/4XDChvSFbYmF6rXym
300AwTAAQZsESbaMx1pdSZXWQZT1MBySftsEH3lEMUDxIGC6OxBKqqiU+nHXXwfr
zO94oW432D2aS41FBbcE5ubcy68UQxv8z3odfXbl7sqmBG9wxICQE5GYQ+XZh2Uu
KnYB4+fFHJCN2wu0DHWY9H3pIAXmfnRoN/hQTIQxxIGJp6FNfB5V0VcMGg0nlObS
QwG72wR/R42/zMhSQAGm5Gp0k16D4tXunssJ0DzG8gzEZOyTMCshWZiJuviH7Mov
9bqxskzMPvpNl6YAoZj1W+KlNpU=
=/Pea
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '35bc6648-3f9f-432d-ab1b-40f8efbedc1e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Qfhi9iA2KUY+sdd1m2SC4Jivp7zo9XCwCEhLmntu+33n
PfOdKAG7CYjqjQFBYvxh9e7fiTiHqF/YQmDQFrfMJW+wjLbWzJ14rzOTqWt9z3co
KKMJlbVWehQNTJdWfU6DYDTjXd9x+JjSnTiXc+z5lYyztmTDTw6ByB1FHQhLtYn3
0tl4YtD9MIjKs2ReWnAwHrhCGhY6K1y5HT1YyTwP6LfSqWvquW75FfQpix8MRbks
n0e3/cUS4OnHpdnYTI434uHR9HmB7psYVMDZ6fH9Dp3lgKwFYFM81zVlKn3oRgfM
lpBmm+QzNKikBnFbWMHSQbAtg9+nubl78CFVg03lGA+2Bv3eQc2HOIXw0df2bcSN
Rtr6GlgQaOVSLf/NR2FsKViypXbhzUaNtfxkgaZcTdICS0zBRUlzhhWIQkfnxsdm
DETq8nHVIkMHKtAeO1sGXdqlTBR+fjjM+uAvcXGNuBz7f/Ftn0w1/m6BnWKElZ0d
MAJGIJEVhjw/z1Cu6MShpm3cOVhzd5o4A2i17eWQwYSWGLhX+yf4LTCeKzLMskEC
dcTIoQVBCyFQrQT3k4WGuEV8lCKS+ZB0bH0l0kmDrosORng6P3ELUtexLO3rSTFc
AWR+tcSNkbGI5xN7HGS87kBKc6uIi8hsGoxreXnuVXndNgmuO0ubWhlm/4oD/6bS
QAFCJjKrMRiZY8MrFk11aar0tUe3W9JJ4pmocNMClSVwP1UAwSCMo9BmMoOT0hVg
M+T3/2wQvavc4qlYjK3ZhxU=
=8SjA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3a2cff70-7d2e-4dae-ae4e-0674b8c26a6d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8Cdo1uNx0V+1/d42zg8LmBz3FBZxWsdiAZCEQfZ4OsrhJ
XqcDxoibBuJvY/46SqCSMc5ydc5K5C5qRym299n4fPsLkj8kkb1FFql5ulwvhozT
i40QvLCBREMGhlHV0hB+iTguobv+NShtuw9BH8WO2AYF6Cs7kVvem8KQQR1Y2EuB
nPeFp2bh2V7l1wokgKzGncRCVlDkIRz0l5M8t7C/eUNfBp0pA/YwhlhvGRz6ycZj
k+qNf9Qxoek2qRNF69jvzHgELP2g3sO/5uaUqWxaWJVTAlGRIWEum94Cc6M6Mn0t
D1vfDU9WgGM91r9TTLF90owH9qlV9Kszm9ilnBa+Av6mDnafiZaKA7xUmQNGDVDp
M8VsOf1fcewv9QQ3s5EH4aSsYTHzM27I0p7ax0wWDnDMUUOIjViXrBNoz5JpU04z
I1NTDSWlEe7uaOivs65lLthkdqDMAINu+DwpyvC4W0oRTcVMaW4ipIH3+nfHJH/d
CCR+gZbJCbAECpREvs6IkkeUy+E2a3Pr/maTuknp6us5WQS1KOhpJMzqCy9gcdw1
uE8tmo46b+pSKWVeFyq9EfM6L72JG0Hg14Ak0ltz3zFLmKamaJKDBNJ1kj0NtvJH
6eCdoBSwhKnrFuFOLNDb+/CIOiJN6qL3dudb8Xbf9QqH2HW2vnj1xG6gyvgHDlPS
QwFfWLJfblZAYDqHNGYFf876hdMhMXIMeA2zjZGs77PREwKUbGbUP0JRBmYkTfc4
cxHeldf+Am9uOmIncRXRsxiX5iY=
=4frX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3b09d5a2-83fd-44a5-ae03-97a1aa963b51',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA2EHoEtHkDRBFDby3QmYGJi8HsIWNKByKpHTtKU/un64A
INrPGuWlkmXU+IrUJwBmKd+9WQAUiYF0xSvBHoUCiwBV3KD41TkwV0MJoo/O5sgE
IPEl4Td/Au4YrA8aP68aXuz7z8UN8Ba7rhoXHnqgC2YAzX5pntwEI/4cyqMyPLKj
e/YDdz66MwaDYP7AU8m+0mbR+Fh591ASSkZwgy0ueg7ms/cfCf65hpGFCmTnFZ1z
qGh20V/S+OzPjtXOMAPTc77T7w5JhBkVg/DYVxhFmWpeoRviBllsL8x/At3NMExF
tNgFTWQInrPy0sSdhyQ6Bg348wCqNjP+0LrDFzV3ygOxEkuYC0DIA8m98emYVGb1
vnmdbdlR6sncPsOyIp6VH2kkrJtExVGqSXuDvTS8NJKVVgdUn4Ak6brL+9CndsBE
1yLQwl+O8rX0bvMZD1mBlHGi9iCKJSYlOLH44Sl+JsYV/RdUoAZKAE/XTd3WQrP4
8mcfWvQ/pWEjxviRixHSqPFLPmdrZJFXvBeBaHZVGS5tPGP54mGI+8ADZct5h+38
NCwfrE2wxvHBftIBHc4cXYHnk8VupN5D3sM1IkC5bRNSeTZHwZq/6FjvYcCRn0NY
wN2lartV3vWRapkgdrVN+cTGvoeIvBJaS4msIwU+Qhv1Rl8MabNiYJXKHj7QlrnS
QQFY3/hgYLLA5fgVhoCbfaoxMcWZIO4XPiDl27Xoj9PMKWN83RoBhwgMwKzw0iBu
BEa7PRYDKnaXjzLcJBRyMHC8
=c3Mm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '41a21ffb-ab41-4f45-a36b-45fd7ba277fc',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//aX7cBHDa1z+wUxgLTj5GNaDIR19Vq5TXG8Ut8WVGgICo
wG/J+nkYDnpvXLZKwvapfnr+jmxSPxDSecOXXAH5PRomfDbftVUZ7kyXe6AuZOtj
RcXaZ7umUoFbNFLUy3lxXfWOowNyzbC0VY4rdJD66ZWuXSPhtttnkpLnJ0Ygqqsg
P0j/099/SY6E3CjMBAg0tARDqaVU8woxmCAqpBY/V2zVi67KK3b4c9Cv3ytQzNMg
N/XPUAqGM0dM7kNYaiIun4/n/X43FVlo+E81/0wXj5CdlsJEVMXDFo/0fDHMBD0n
vN6jlPbSwHmPjYayYpGpG5/qYQrNN97v6TazDXgCO7MFE0Jx89KShTOYyN/D3ZWW
LLphH9q13+Lpr2WACJ2CIh2yo2tVdQJiKIzdBUS+D7AoQ5NCJghasiILwiY/Xmea
hGDyvsOqTjJ1ktGBh6n2/kB3JkAVO6/ZAtF78wS8/QMMLVP34xHkk6RYbnBMWyiI
5tnXXT1Ha1Ce06da0mX+QIN1U+D38B9eHE4sW+oISwHhGWmKYaMUijRREXFfVUft
4jWIP3uj2t0n5eC9Z9djXrv8M423A3Y1okzvh+RLgxdIqAFhVPYPvye2+t6qfnDb
YOsTEfrgk+zC7EIXgtkrHy8EpD9t74MMvo0K5OVNup+F5qtPGs6VJlj09IGoYkfS
QAGaPNq61YyC6ApqdETkXMWF5HvnRJo+MC2/kRAkibDuZJoe43q//3ODg2Z93MFx
eMA3NpM9JtX4DrRB4wgmerw=
=tA2v
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '423c90ff-019d-4809-a4f5-41e9113f8126',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//WL/MU3FStPvo97jyi86EKoueY3nXWYmnDbMFsKnZnrKO
YyNEDChMFZ02SBz4PQEQFjFBVAjDkeQMkjhjtMVX19Sn2Dl578cXz5qOlxx4bpld
TSMJzcTah5WLVPzB4auvTX8RdEpxF0E4TgYUSz8w2n/9Nk05TOxmO60dHGuajwnZ
6TOAnG/ULuJYZ6MKtKErcK+5+jf6SdEtJAar3Xye/x4apIq9dzvzUqFukPtuCt3t
YVBy1mwAJEHb+ORyeNGMDdHyuYJBahZAxXEUZz0pn3bX67UvH/MdY0ALf1xjHQxl
nYr2LSHU5s6ga81OsqhP5qnyDjokFLjGHNDTLvePt5HxCerspAm/j+PkmqPUzvWy
dRTJfUSw3ufdMRsEofXwURiwrHnY93OtngknPXjOSPuvWeC3KUMfBikFmMDNRm+D
mBWUh3pkVqV/Y2yFDAb9RFz3M4IraTfzbYXQMvkRs6hUVi1hFckLpBECiLS3dsoQ
6bcRm6+V+j0CxCp66n3B1xv+9L060BdS767qen+2hjbU86+ak4KguiUft00jK/xE
SALrqNVZsii4LSrWWwhFyK5O8RrNcCx69KLpVY00LagVczzmWI+Roe9U9F2gW8nM
8Y3BRzn4wAs3lJ83GPrqkRa1gXTXq4q6EvcCBAPvs+vG3+xkmDZ4sV56S80LP6/S
QQHXCXlc+qhOE1GpAESfDngcJvDB2a85dF/775jrjsUd6SCjxpj0X0kq+hArJHor
P6aIy0HzVUXKybjSVITdBBWJ
=IMAZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '455cd90e-b994-4d26-a6d3-7aa6f2e2c6c1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAxUIsQICG2pM/ncpKGu+fmjGjeAv9SS/neJoeqglFC2QW
uqoN9rymmC3flJRDNMwvZZI36z8ilPXma06qCrsSxfNnMWsWyV1MyYf4b2bNDfxN
5W90TMi1vbe8rKfhBIOxFATnCmW0LhQ/GJH7nkotaYkb4XqRVZgen7KQIm5pn9u1
+IH3jReOzma/Ogc2KJaFNI4iF7BKMOtEI1GQbsXAwq9oqWnmoo0O6mImNUtcX9e9
Opv2N67/HUDBshWGVdCujWcnQY+Iyrm0p+dnFHSSWg3dOyBE7u0l/9fEAUpMMzTJ
6b2VcuxH+nVuSlFI5K5/2EjT/R6y/f4IRGeLUl5mLXyVGEH5ytEzEys8wP/4/L1g
ntAh7yQ+LejmyrWXQTilDTl0cI4Se4fimMMABckuKOgkymIqoeW+0E95P2p413rr
J1vUIdchRxinhTe23YMJBYf7Quvev4/q3mB26ufR6MWIu2VSnTmhpPMu17fkMAuI
8z8Yhxmm4sR0pc23DXu1LMVkKXKgRUwdPk0UoNTORnLSwWaSJlHGXv0eqOwV0gOj
ltueToiRs0vahVJCS/46qJ1oBW2PM5y0P2InOa+ZNcOc1HUKMEaETgoNfq3DZOhL
3tUFDT/4RJmqb3Yemo2elo8Hm+42jTU96cSceLV52gvPk78IzKwVP3/7cxma577S
QAH2F3jo2G0b4uAGIvvHUL+HWhhw9Q2FfrfdIsYpEkbuTAIttsNoB1mXqLkf6FQf
8flOTX/jQhR4cndilz+h8Ws=
=ebCP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4b49cf89-9acb-4d38-a5a5-e634f3253ea6',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//eAjiP9Dl2myOzh6mEMtVDgebWC78aejosZc2/d5x/G4O
B/HnZvjoS0VlPeCDgUinArdQnEw82egjd1gYeI3MzuH+r6Hwd1OyjezGwkY9wXMS
1IYjQ4SoRjvXfC0++elZjVO1nbnpnUw/sOWoUmCZj05fKcoSbYH3iuGxdEBsiAQG
LR9QkBThlU9Dko2DJx61jhHyQXRy1b7LkvHWgFrFgN0WoYr68DNMtR8ZJZ8bU//h
5jls7qiHafGzl/9Dr2LoLCahK9Br40aeIyMbKeab93MI7gO/NyCV5Rb0kUN9a8rO
ekwQmSdpYnZ3MONavwDpOUleVgtYDmeeoQOaT9+1D9uGKrWwv2TXvrPHWKWEieG2
EEe8wMG6NrFNN6uXe/OqSwCZKQw73kpRZo2UPfzPGwT2JjCd2XqpE2ZuavQqS0+Y
FxC2PXnG0mRByCQYMb07BXb52UyQk5rEcnneB0MEfs+PU9BTXMs9x7/9QU4pScHk
kO5PIsMwOhfkd9XWnIVIale+IuHK0GJt5+6qObx1t5Cq5xbsoV2MebAmCuCIFtew
XQuHyS4ScFKeNSuvjbtTih922QndITRzlIReIaBGFZ7yzcsviO/3rUWblnmxy4qV
KlqPTL9M7BLYRrhHLkgRhqSRf4aPbZh+tEv0hVDlZ44KjzfSnqElHK/kCdlCMr7S
QAGfTXqHjoxRXlCeZVo8MlIMY4vSUhmmLVcIZyscpWrYy5WwrO5oDwSz5CsIgG13
y9/KglzN/Z6jYVzNfTjDmEw=
=ntuo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4c45fac7-032e-4ba7-a78a-066699a648fc',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAyk/nevciB7wRYF7uanF+WeAbnERp9SgF9KQRDPlnygwM
rc+bI+BoBnfryAWP/IriCyNzWrbSvtkwAPe7n0hm4lHE5lego7wqqgMi3G+fYUJ7
+CHlmxZ/330iZkbwPScLxdxoxoKJeAuvNxPFFWewxDhSK0acl7b1LRWEA/uwM/7t
sIa5/ZRjYmmraORs9ClzjyxWFi+6bJPfaH4N9zdMe3LJ0oI8HL2wU4ya+J55CVFk
9+c+/kwqJnxe3t+laHFHoyhZyKP0QujfRBLYgJOSyjg0ZCK+rMXJgg28VDQmLUWA
e6yx6iYWGR3lqn/r3/rpcleC/rbc8S6OQ6BIOCuY9tGdOnSGxOXdIk/ubsCVnwpN
106ld3YhdYNzWGWLieqwoNw+lr0rR4vSN+WnRH00RiVEim63iTA4MKW9sIMm88oP
VnRrBwxUpN/F/uISWtPY8sELr3ZhnYCFeHOGaafeRdV7uc7cQOV4H58igZsD6Jvj
OgDttfk9LMxWdXcvGnxpdHoGLcapi5RkgiXTrVRmQDHLbvvloOUkbAXMxQR9SxLV
sjC9iSmSqd4mU62xJot2u8quAC8AXY7WUisQVQIq70Hs5eFpdaHqxj0AWEaqWBzR
4WPnmPakLLgK75w7XJdYE83G6sMNMv1AgtSDv1tymsn5RETh/mZwu8ACjQcD+iTS
QAHVAXS0LImESkG2l7Ob0iUXLgWL0XCKQdU1/TVjS/kgIhKP91F3oB+hCsX1dWuz
Hm9un+Rg3kwGKFpsvrmqa4k=
=j0bq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '54056cf5-2365-4432-ac97-f119ce64843d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAgfvmQ2l7tPQJw6urEb0DqGqpzUdT/JD4emxk3VDAOrDU
IXTLXvFlQ18h9KNAdmlIzmJLqnGSI3SGANBL8rORcwP4nHu2Lb2RaGrjGeZOS+ec
Y3+xpMNDgE6mG2HXqsysWBxheKqRODJxyfWMKQPkuiUn5TWATXX9VtHgVvDjGBTr
68krLaDrIXAWs0ApqWKYnhbKhkM3U9LJm0/WY59q/RKCujVGeQog2QJjHYvs5DiG
2c4Tp0yGvyM3JwB0wiIitV4Des9uR0SF5Tcu5a/Ka8OWlq46hzKsva0aDRGoVGec
qrFWYCcEifskb/cUrvskW2lmgoaRjlpy4rZ24qVypNJBASj6odmHvsdvk9HMF9C6
phyRPewqG+IdIzmPEEBszAzDSm87U2pD2G0taZhtZGvY9L3nbpJ+LqSoKvTG1eA+
vdI=
=3uig
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '605315a9-6585-40e1-a276-2056ccd93768',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA5xFHUYLJ6YXEiQu0i7kYCWlQCIG1LLAtOf/zxBPHUijl
3Ybclkw109YPvs/WUYu1fEJr7lYQEsyhUYW9bZSFAV5wQY81NFnfAfi077nB2Mke
ZIPtVmWsnWlSKoKLnJe3TpiHHI88jy5m2tUIpTyA5NXEwfA/ViZcqeqmSqUc6hQf
+gg6H/wrmzD6c8zCTh2ya+M32lUuWaYAkR26JSn9Ad3G48sM/KWxNBs7oFdI7cQB
45ds10A2KTMab9Gh3K0y29V3E3x9cnZUBL+3D+dhQjb3pOXahMZatyHXSCutzGdr
TuesSK/2ZQImcfsAdYnOpqF4AAf7pzUxDmQGLxZy5ZUyWX6Gp89pFx3JxWdkEDQG
msZn8vvKvbFacxjAHRrtIkpglEcPwUATZsgEKc6b2NVpOmn7eRQIP3K5yT34DoLX
eE9jX8U6mv3FczCawU1Jj7sPexD0RLiteiZ10p19c/qXsFWRtQWYOLWXSXZQ+MpY
GrUu4vT81stJLw48mAtuJiWfcCDHb0eW2nAPC5omYBCWOxpxlnW6zaNx+UGaszW+
n9ZqbTVIDZSQXyEMbngt2fVC32MBWRRx0E9YoWv8oeiZ7FWdcxTkMS9Acxye7YBX
HzI49SQUWJ3w1trHDtpVJ8mLvNPNP3nfjGQoqjY5rfH27cfIBo+PU+2TKAUFpYDS
QQHAXai4jNh62nll6n80Rz72+aSW18I29gQaXdXpSPVgtoEl0DKbdm4RQHq/Ui5a
bDknHzxUabbJpRq60X7TD569
=aBD+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '699d388e-ab64-4211-a115-ba54fbf14ab1',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAoCbsbPTELG/A39CDQ/4K5D986rZAzaDLY0+T9VWCCysP
HihAlljCo7ozGi209DEP0vrMy+KyKBlYK3l/hRxVH8BO4OgPxLlGBfVGOMrzcMBF
Z++0OZu29LY6vYMkEMrUGYnPqO6Okm3R8QyVyvjhqXslEeyB0O2AhMOi/n3CQ5f0
BtxEbVEao0o1tm03ltU9zEcTBsIj8l+52jMEqtK7ju4L4Q/hGBE18XaVUHZCEhfM
vLl7lG6LtnrFUcojt5eOT3+5jZbuJ6lRAHEvQlo7Zwdrl45GXGzetnJLlfv/Kwdo
aPyo1t3f9tvVf5TMzTP50m8yguntB6OSk79q9BB1aYLdBG/WQOC4zFlwixC6UJoC
glDkbor26U3TkOSPCBzEZUDD4xcxyjPkixiLCgCFqx0W+2DNiT/I5xQvZl/mXsYK
I2jfRXOVdStVSQRJzrszTpgwrhV8GK7h794JuG8lv3v+pVdtf4XUMFhdyA1BFLps
qSkOnGPbQ5SJsjHoKDowhA2wxMciHDrqLc074SWFjezMvohy/DQGgVGwSAfRZ6M1
Vci9Uw/TSBNHBj9SXauIiJxQVCX2dWOBqNx643Khj+8xVxmtoMmFqMsqFhXaV9CQ
cePDh+eWXM/0vWZnV8N6fo14tv25Tb5q9eObimZMn5ejqKp+vcl4VIYfamaCdujS
QQELPCH65On6QrGDouIlQPcmkIKokLs9tiOO6it9XQl2QIsRhj91LXOYaK+N4oLQ
yd7mrKmiAAHxD43jGNN/a/7j
=JYGW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '75ea73b9-33a9-4673-a5e5-41ff3eb2adc0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8CX5UXwDbgHpR3CclvpurznWUeaiWpjQvLbAji6iOu8LA
tInZ9ZsFaKIv8npa8JWsWXJOmpqpP1kCH/8eDg1iwqhvKQw2nyXPahDZqYWkwdFg
4Ox5xRFGqWLyrmbF+DD/r+m3L4KJp50PHSpeijXZweQffjCEoyKbuaMAunD2hiid
TLPJ+qTdim1wx7yT/4vAJNw/EjKvMoVxhgOhkuoINbVjlYSdEGYlweWA8ZtrCQhP
vSaTcIUg3oSfBRWH6oZqijPq8OWLmgJlXFggxF3zwwu3ESh7ibJNX+fLzV7xkR4e
uKhsQxI64ctVCjwL7gk9Ol8p8LnUBGMAfJe/45RgqnDIcqazi8FSCFPriTLYE8+c
L+mDQgmtigRLQmSplaYNwPUvmflNBlEWp9ElWL3QKmlWHmIEnSzti52xhU6yeGMm
TqeuFEtvF9+RdKd4IyAkJdp/S9mHJEXHXTvct36RAiQNJzyzBaPkkbKRD0JCyF+Y
Eo+1qhq5WXFKeCEhT1RN6yKX7LzyFCJ2rGsL+DuQi5L2gkubus0d3MBxIuDH0w6q
UWsqS3HYMnFnqPfMROjj0ppxQiLMHWMkqsAaM2FwPWJVmTDMd3pJ3ojNDWw2iwXQ
M5ikCPNYVPifUSeW5McNcKUKIAm3zagdnzfvl9SEFNGv3xkTS+kuLnOAGbM/gEXS
PgEbtxgFfv79EnOYwpIeFCmk5mHmYdKzr8/smLhIF+9ZSu8yrhXP+FhtqsEqABHU
vFaf7PDQByW75w/dZ9uU
=Zr+4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7fea080e-fff1-4dba-adf0-716ef1830534',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf8CffBITI6pmPtkR7+wR6JFAGXmVHxIOn5l6AjWXZGX5u8
6KU5Q+SrwsfpNL6KovUZLpl5M3dBu+W7dEtWD+ZWT87ABgglYES6vD9TZpTj95l3
PAyqDTO8hZjx0Ssk0q7VNfFzrDAoMG91v26APb73v35PQrtJRt0rmU+7WP5aBCub
GuSTNLjCH901NbsmBAuVUa/nZx+EfUPNoYznqsXjGUEVs5vx7PDWvsZdIgZgMjZT
iA7HSpB+CWUwYN/yc/aWXn47PcDsqrELRjgRTUWvbiCTka9ZBon8VuXx2//zWdu3
YM+MwKwBUaEbIb8j4yHJtnQTCIpJu9B1Nr2JHZKZvNJDAXgsX9kzec/evSnp2OeO
F6LrZXj3FVLU3qvS1pZ7dsD+E97ByIS5nSoFJNkIEjE/07Yt5ZryO7pmK4348mej
qI603Q==
=mfo+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '831f7945-dcbc-470d-a9c9-7f208c8958b9',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/bUOsh0Cqh+sUeHnMfWxCS/MU7OrTBXn4HjKtbklJirO3
9u0WIwnFRXPXyBDL8eg0whsn7/EggweJQVCk5HrBkwWlZi82m5Zpi/+7yYYvpNTa
8A4/PcRwqq29lbXXY43PzvNLgIIGHbwXsBbtQF9nn92WpG6cnGKomZPez6g5+Buz
j33eP9Al5QhNp8PWP8+EjsoJ4Yi0/Dces8pTB8P1UVx++uf9+hfAbzl/DLqMqLki
EHvcQu9IHJh9yiNiYgotk2xRPR28PKPR3JCtD+cZjLZ/5GtqluHTCG0DjqjRFW7W
4Ttj4oLLS+THuJKAffaWsUSoMqDDGiBs8BX6LWkcGNI+AdaGy4nqkd7HrqHcqWhr
pMpzpaEV+O5yfABICzBnDBcUnoH4nfS0KYqsGFKjHvxivDH+P3gOsEnVfvOs5yI=
=6Dd0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8a92626d-0dea-419d-a116-c7fee9176d55',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAqm32Zeq3MYYJjdcevTvokDe0SbiDFz38MjgRCOhLtufA
y/AB6ki3XZUDNnR2kmQHe0WP36MWbL1zWBoCksKlzSnVu/lL2mtOCfsJJJn4cxWJ
KabgN2t1mZYjVUZtXOLypyM78RsUR+RokEY/hTJUMsgFLEJfwP8nAutiz8Hzjyvn
pAxS3kpK2EO8JxxR6Ex4pQV2DXaV7PvcgKAwxTLu9Fs3/wQQ3nHcgcq312Z7CO5t
tDu0pI1zzP/UAoFOmMgOSat6ns6n+88+5ftU9UG3qjLdkZJ0hu2NbJl4bdKNwf+9
ltwoSbN4jVMmhzyKBIsbd3nh/UjnrTes4TL6QyozwKxsYrZjlqSmIOHFsf21BAF3
BAU0N2UUxDlS1IYhUk1oG5Yc0cSRKQNnIXZRjItnZcsjP5nbsZ66f6K0vld5rrp/
QJopv9OD4852qjZR9dx5yZD0JvVBnDnPq5BwpYAaMM4SBQFB8Bs1mALoNsokl0Wt
yK/n2tfDbflr8uH9J78mG8rQe3cRctkYCOfSEEf+c0sSm+98dBcSPjBZxMeZYulN
OyB0vx6WkrG6Shj8bIuktj71+knM8txAhXnseGtWkWaUE61npJ4OkNOl1BiyvI0p
RtFAXAjxDH7iF2b+TLnHmDIHjtDxMvkQWO0MCIPyfSlvyFb4F1L82KY4+ZUFMUjS
QwESs8opU1jaYX+m39wcFcT4UO4VZk5FBqDIHYTt/mfMeYRmmUPFP96FD6XyLU1w
yT1nEXNjLZLAHQee99KrBOAhPgQ=
=V7LC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8c428591-680a-4a7b-ab44-32d4e05825c9',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+MqziGmoc7kZqwxO9b6W+90hUORwudotahu9PwkU/wNKv
jvxmoWOEQvnf+C09jckNkDpg26BLdGOCZsZWLpWpHeGOwqVq2YD1Enmv9AXBGkYD
ZNUZICw6/JrsrYjC/upaAXmTjljIqzXOqGCQoa5PlcOxaJ94+s0SLUsFOTaNdzr0
CPM+/4hzPdXexcpQLBidnYVkc4S3qF/ZCPvsoA4xU2T9W0Rcn2Y08z2UDTQlEXms
SMCIzHE1e10uLcxEn9lcG/uOJj+bYW0tgJ8ih+Id424lOGJATcgG5+UQlZAqeRWl
REYtnrcDvlnkwQEjJxEvmePoEzixMX8hYMo7Wt7j3piPO8MLv9Mu9PVq5pBh78gX
SeFuKc2W705oiya49sGEysHKVJDFsJI161agAP6Ff/8QwiuLcgM8bsf0trciRw0w
3U4rPgSbP2+Tdm8KQFUm3a8nJaNAYLPeb86QoM11iy6C3H2iFow0nM3NmU1Yr9lq
TiZcP6TJ6zEyjsdkWDf5vv4wKRRenGqmfZ1U98HZ7A4Yow2gOHhSfNrMi/lNo0Pi
U2nAeWB4N0ulEoJDEMcGoA2ThZUhrQUZ9/ShNylPRHwp6RDNAw9XVqqD6DELXiLv
gEwsu8HenQLKZIkeCYDabMEeXogOIznvuRk6bKMFL7q3/Mk91OBN5rSmaPpZuMbS
QQGafCns8qRerMU3wOLwvEU3SYRCEJGjNbAsWHTEo7cEeErPMNgQvasiXhOqdVRL
GaC44ZiA8jVft5MS/SBH2OVn
=+TwX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8cc5eeca-ad1f-4f48-a8fb-d5a5483837d8',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAhJXH1oJ+p4DyBHRvy9bSfo42nRqtRR2/k4XObfmILtje
0qDxNIKLaroOnF/AzI44V5hkqvHq6AEJg5BSqOliCLlJh+sHDFQlge8OiwvGseDw
YY9go3AaQvlUtxq74z5hVv3TPA3jPa/8rccpqr3gxiwkJuB0FOiALwMdG2ZEwaX/
K0aap0k/j87ZT6uKfmdBOYySI/9tpyNu9Lmj+Eam7GZbt2ikYQCYXPQfdTkQIfsy
LQHjRabTlUw2Eyq+gPDCv3vhxNDq76GTG+kI+5S5YZALD9jRWqy1SyH/eMpPtz/8
zqmniEFKstzJnQ5eFSCSrbVZGd4lgzTaV+q1NRU1LUJW99Ca/u/XJtRug1nTIYBV
qKLS78UbM0dwvW6OGbN/cTtpNVd+GC4AVXxX/xXFrZkgDSfIOfVtk+mASH8A2OaD
GqoN3x++juft0ahZB97fPfTHSLKGeTFKkqaESgVNifZqT03SVuo8y9FrjP6PaKyu
soDPiK7u/g/x+F/YWGOKhIzmeyaCIGH4OPmhfJbTN/i87UKFEj1FNNe4hIDJ7rgL
kVjuovsg/wO7PhTUk/bZCA8DZ8eeDXy0ttXUY1NaLOAOVPCQRb5CJz60YVHWDHP2
9HU7kdasR02FBYAV9jdoPZFFTLh7wdQZHrE89fq/hyOmV6k9zhAKvbx9PV7unUjS
QgFassw2vD0Jbjdi83Rmfuyv7PFXU+KDJYEt7y+H8koIIClhIb7KYhq+7+z3zL23
g8Rfah7cNghP84CI3Q6YjPOc+Q==
=+yEx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8e2e2151-9e0b-49d6-a9f9-b74c3fb9fedd',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//TQflhHi7ecZ35yqzomAuGVxKu/WsW83dNiOUmv5hQsw8
realPvpGj4jdoRfKM8L4kdhmKt+7ptXZxLDQVBeftS8JOitnNHyo3Ph5VZ3pk7h5
chEpD0+9iQcS2QvN+5PvlcIm5AnFV+80p4vScFfcFHSp7f5/TK8AfnUAnFdfaIY2
MiIsllfNM5VoSHzB9QJBSiXz0W13PCu6nOXFGO7SKQlv08+Es2FRAqQt6FOSC+oK
qlDky/i+gt/1GhPuT5cfNTYt+DXfYQse8lI90X4Vy19kgZsOXJ/Gv2dJ4njVFLyp
2eTEIKv+8gH+WP+FIceXOlSTDGUkZPREq9M8lpPvUgcNBnxs4Mj7kizNjnDyKnd/
Gc1nHDCUMUIbaKb6qDYhH0+ER5tlhAgLfJ7fLJtg5Xn+IithDKMgovJuAvxUV+9q
TKs9bJg4VXvjiB+HhNyuVCPXKQwWcHnoqSOm9Zr+Ce6G2JYb0gra+yEpNtgRmIVp
/M8HyD9+0m7UUBomSnU1bEXJMNV9TPAej2D5CGiv/WzGwhBl7e8joHeB2JTLvY3c
iZJ4GKQx4YVeSAm9dBMme6OicteYZ8l1I3PUUsgPQeEcw3TceoQXZKiznC4Tsg3F
IxXPBEUisUhefS5F/UjL/bpasTLAEjzbsS8fMS2wgCPWFqVNekiDBWI/7hxZYxnS
QQGfCUBsJPwZMEi8xs+vJVGBIvfYwsPi25rbvsfRULdahipj4a77V+7C/YLv6n2E
wo2qdfNHf2adV0XRMamby5gJ
=R84I
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9b735b1d-0d11-405e-aad4-ec6a4d301043',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAjmimTiGBAio9f06z8U3/Y4vHq/MF0dk96MaSPSN7we5v
gj+FiT8iufO17Mq5Tik5Wb8CQknsuLVnSd5mfnSJI6MdS++eewXlVGzRvt+0hpbm
QN4U+gDAPZOfdXtqshTvbybuck3rbYMMsiB72Lyk3eLjUorPmw/DYvfLhEpvji9o
zC8iy+O1E+l3IX0lCn9CMmIlNjNTJRKrYRPosSCBJ8BrcMWIlkHp53hmKdwEwXSy
3C5BiOcrOfGXhJYFqX35rBE74yYFiSpjZJP9/BM8s2vboMX5m6k+zEGJyLsE2cCU
X1eJ5TQrt7eacAmR/ybOc5EgQGabfir0WGe/V98DfatjJuGgk6N7H75bt5c7N93n
JACVrA9wZEx1lEU5TH0AorW/qRHy2HH5AZaN6d4qq1o7hE+g43ERI4eERIBtfzHS
YJpHFfFQMj/4jE4jBUS2m0s/kSxhgaNosGTIlxg6Jz+ZGUrfEqX9RIzk0P7LBElg
RyRGnha3jK2K5HouPpTJ0qRGKq7A4lLDGKP/aK95A67JRUWnnfdD0Uto4tgkMoZO
S38bC71R1Hbgd14AuYoOPA+FvKAS0/tWnY5yxlyirNl97oHHXUbbopEN3zANT6lJ
s/QljB2PzoSuNS+stwUPseu76GEiz3W/HBsI7NQNrTz+pSFpBWWGwNo6O/I1hrjS
QwFsn0sZP3/HUCRWplszzWHiDOM1UA/QCEUqvHFZVVcMxfk5zq7eeBLWZ6KlByK5
JkmLzIwRwMJwm8fd6M5dUVdxvfM=
=LFix
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9ec5757c-636c-4098-ad8b-ff2c0bfac0c6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//YIsOC6UWh/8jUTJEScWI3d9yv8K1ZrHRU77bLzDH/CJA
jNB1Lu6lp+iDrkM8BpV7fBqtDfZ+qqY1ERKXmrp0LDdftGzDznooaEXn/LYyJl0w
w3UglluMEJ3c/SoIleXfSsVpuVUHNH25YvA4JhjOMvlhsNLoNbAxqtRiaha0CgaL
PchD3UoyiAuM2xvwf4FiQqiSkkYAlMKOefW82Ov7gVcplZQDAdX/QroKExjq2B9k
Ju39V4ZKA6+UwDXV80OAgTAvfPIyd+DDO+En+ad4/1aLHoyYFG/G78v7KUwdG8ig
+7RFpkliIBEXo5Us+/0unaL2V/Bcbz8C/jJCon35Vh2TZj9W72/AlIrv0HwRwhN8
xy34Fvy9bpsAF7HwgpT6ixsLwSetJZU6ESY3W9Bgu+mKIR4BE32a1gDV8CUJarfD
par9fnmg70vJq9ce+0A9fCUmQEV0hW3gZcSoJrGi7xO0vt9n7g5SyRF76YPIUu4G
VdTVSVHdpcti0Dz1k3OCz1ilDkrWq6qLL57AQK26iUY8N/m7d3OhreylpeOgKlrl
/epFPbdp81Chh7gMtVNXzRLpD6KYZuh2QlixhzRrs3gu/D4Fk+1OjjjRS6bIW23v
EzbfYikDdI0Tj6Eir7j+OrJH3lKSV+ekTZ5UrVg+pfEfo/3RJM6KUCXAtu55PAjS
QwEmhV6PvNKxun16ZMVmncBCNFjzyIl77Qyhe5EcSqgKBcCcvyEdTZ9DFGAHatSC
CB3bD7Rn8S3hktdjlyr4aqh1ILI=
=3rsY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a3de8801-2b7e-40b2-aea2-90d71ee16adc',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9G5VvWT4XVi5yVREW4nVw0vGsU9VyUgf3ODCSQXHhpH4h
BIkemOhrnW33iiYfd8M2Ci2cM4x+epLX1RCDcHM2yRtWVjNm+kEX+DIkdgvrxVZN
NlAxXi1+bVLRv2vBp82gLgCmwnbpiaxjjGzCKNkpOcMJMf73gGOpWt9D6nlvihDR
ibwW50piOeg8geweUoG84/JV7el2wEDu6Y+ZnYiWtHht34nRgzwJo9b9hbeqdNr/
pv7LukYrcMtqy7O4hFO5O+RKc1WoT3b3YZIjht3Z/apgILo/U8NKPLfX108a6H4S
prufynaDODa4pXO7nWHXDgEPlA6OQJ+pgF8bQlJPuiwPFGw0g//MiArmDuPPtRlm
/hroFCl8MrJ1GK1wsQByWiOgogkmloJdxEMQp2pf7xs/eZAGjzZuXoYh3RS56L+3
zH7ewPKAhoIt90FOF/c3/HOPqPaV13k4mFur2jHBu4sjZQv7hQdYtoqAEJeMuIX7
RgO5QVKdN0aNoIXHtBBa5gifrbdxHRQDKP+ieNdjEKqK9fzqAVTtrKwAE5KnRL6T
/42iSjZ/ArX6VHsgAFu6jfpJfnMLo5goBXqTqJTV1YrrbdwFuDN6WEeywg+tmxCZ
SOyo2lGBWQzkdYJJsVFdyFy7R+Y5c1M8Y8M3cOMTRS57T3yoEhKJmMfb0NeI8R7S
QQHY5nZAp+vY8hW+uV039sSZUD0JVwXM4S3rcagIr7/FQIPN8vbXflV4ZLJ2ia+7
UYE/XDsrYgaD+NwwiXC6oyia
=j244
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a73b091b-1800-49e7-aff9-38a7139e97ba',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsI5DOgsHP7jFgzIZKHeVvFSBcek0Vjhh1Z232zx4kart
bhTd+8V3iKt39C/J3JJNchTQBztybpiFJk6oIDwyZIE1FJ+FRlhVPBD7a5yHrbBR
sh5jgTfbgpjfSuuFmQ6OkoFWqlsHDeVO3OaxOWRdiPUN/K64rON00E+p3vbD4GVK
6leWVxF4XYbwXYClmyCfdoNOENZxpXryipp4blNyutH1mOdm9nT9eT0UUYCHbLMF
06eebqCwssGhzohEHt2tTK2pFfhltpyKYdtEEwFd0wKuaBShhV0wtz8YPPiCB3Ht
Tfao9ZhgKM0SE+Y+xCJFSaK++AnURX0KARPkWdVYeRXkPmCf30WOhr8O5vgSc59n
Q+OM7fTBQcdE/bAU8mObRiPocGLp7WMTswQVyerUiepGCrkkqOXEIkqOJwgFEMgy
AI0y+uxjaZ4qFdMMZ7GBDfCIE/jCGI0hIVMiLOmpqF8XMdt9uCQp0bcMgiGzy9al
VDH4VVdZUQH5Ys8GsoXDx7s2BVagFwZt11xgxMVECx0Ni29Cf7Uq7xEkxGfPfaUC
HIUAB+JPzO1rTZSsBMACf8AcBtfeL4Zz2KlzmpuK05rm1hi+AWHTPctXqViEPrZA
WJV2KVaPb+PPY9mAA7HCJCUAElz27zv24+Ndy2oGp9sOyKrzI/R1jkyxv93A4JTS
QQGHny67jXrkbv3lCd/nmA3PfZroe3b6Fbwbsot5BAv1iLlSoS5DccKcYvGq4qo0
I7XpmZ1VYZFFsgPfFSbVuJD1
=Sszx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a8c69595-9821-4894-a26e-c8240068fac6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//c/fLUjSTNWalmIWuoVy37tDt/PgyhpaZqRSBheDUVFpD
ct0R6WnYLWO3JtJ975Y2ULt3zbGqt2AW6xX2Xey+vKawg4NOgfsC8jPnvYWoI7Cj
pqLaOM0xHRBahAoQOPmGDUUsZTEmjE+5H+zbUBnYZo5YGc5ER+8tD0JDtqJka8+h
IMe+SoYkxjbyhM1emmN2lRXD7gOVNeWUOPnQF7LrUQD2hZDOzhiwAX0UZL12NF+C
TrpbHAXWMoCc7+qu79rnfaoSqdeKv4IvTljVmCs0KdgOVm2mGEeh8yR9HvdfCGfO
OfedzmV3PLSuX+CD6NdP6mofUI8YfnqzEkpe15KHTygXUSQxwbMGp4VAjz36af99
4sQz+5qmWrNYSZ0SUg2lMx5iZXjAu5d4/GAE0WuVXquBuPkJ1vJy2Txu7RKGzaQc
owRTnmGoBfjw0qAFNDnPGiEwUsTtQqZ3WSrbELhJ7zprLrKNkgNPWtIlO/GVjYM+
d1uFChvem720/Q2+3vlvchNdhhCgLI/NyNu6OzObWQKQNMsl/D5BZ/nCV6QbCzAr
AUJvJmGTJWfkgVdryeS1zz/QmAdv24nr3Y34XBqRSdm7P3md+VHbuyf4XmdNlpYn
UeOdYTxwjprsi6xBs8GUfRrAa3vaSSKEQ67iJb+7eWyOzXAdoOMw+L2DWrNrWCfS
QAFCrtv1mYYNxTYCGYhQIS9AIPH+6TBkiy8hfW86GwGSyYx6MDbNChyF9UG3FoiZ
sirS8SdUbzLe7wasEdISctI=
=e1h2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ab2ef9fb-cb2c-436b-aeb0-2247371e11ad',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAtVDbyFCkL1CSIEJSgkABKNxxtYl9hYB59H+4zyjlkDxU
erhOyzhZwSDn27kS7LnpjMHCd6yFj2ZFIy/javZ8W4tIjXA/Yhp/L7n+SX1X9nKg
GadOsLi2vjM6aIakoYjwH6wQ1l0Yo+xOJu2wlzUNZKj3CxDUTogIsKTTKM5jY0RS
K+hshjxIZBuAenvr9pVhbEfq1i7+APfMHrvQDSAyeMQKdGyFSXVFkm/ADlvL/IhE
oaBrtalHF3sT1ecuhK3g93SmL5GlSJKyvDQmXiATf20YQtm8tRlhDTLRpLSRWsui
ZfZdUT4znbL50hPW0u3DTdrvt65xs0GdoX5Lxg/HefGmZEPz4jGkONnutNDkg/fP
oyINTYoXew8JJ17W32EgcyuzipfkBWvyPhigLFK37/lZC4UXpSQO8wTGsJvQ/dn/
kTEt1PydaVmHrlVv//qjBPw9zU43UJp+GyCaeGmq43exPGkxs/OlJFyoVmiowmfx
UGNm5Ts3G/1Ob3AJZ8ALjwm77LPxwYLAwpcpHGSk8qVfUWi4Y53rU5zWq6NK8FlO
8kWJO1T5ugshUutK48gb4eI1hCxaQXBCFpo1h+6BBJska29Kq7fhyk6IYi/Uk1GG
IKOhwcodVtJGiJfuiylSgZ0RLpdDTXNZMmAbIGwWz25utnzHYgopsacRyzl/6T/S
QQGKSLvzQaH6XE4M0ZfZKLcYL2bgGR7DruflgTBUYoCfDr6axN7FK3ajbqM10pkT
xuB9I2wDJhtSQM5eZbtCRX/S
=yoM/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'acfd4537-208b-455e-abf3-3cdf18052ec9',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+IyH4i6OqxJu7ZU3nXIxXreH3x+lgjllHwNAor66iQcTA
BO/JugNSvxb0QJK56ykrQSjnOqqm9vaetuttLnszU52MXMgbQ4meP3VQbL3JI7Zb
uEyjgZnQV3v3np+qxwBoKP4Nn+q4yEM74XOsX0JJg1XUnCiMsR+haWjEaZHjAp2+
dtIT4eOXEfCa0NQeC2u3mxEy5tJbWGoLv854BrLBjHXOcNFl3T4P/rOHzTBcYyQr
b5aX3WKPS7U++QVuhV/+5c3LIa+spPF9g5AOXe/na+1JvNoUaY07ouQ0TP7sbRc+
OtDVYrtZlp6REtdSkl8D82kJnFLNvZv1jo6xuybNVHNLQFvYUKC6bQr2XuYnweGJ
zCiY8YS6mO4ur161JjqGCh0jR1eHtjedPQXParXM6NyxPrcJJq6pVMfdkDU93WqX
0dIvO7dKW8LQK2hc9lWIIIefzcCJDLVyqZyvEnvo7VXu0+djD3aO4GTly/D1DFR2
Ngmp1aj3nTBieWR63Wl4BF68tELRl3wPxdN2WOMWN1iOvDri6RNrF4VceXgoes1A
1U9hd8XwIN7NhVYmP6rgSQzSVE9M74hRb4UYlp3QNaJpvnStNqZoFDUOhvxkEnyN
R0/Ve+um7ZmUbjYCuAUq5gNh0v/4hUxaHDAfFews4r4poNa1eUvBp6AyflhX2m/S
QQGj0GgpSrYsSPaDGXXhmmT+VnX6wUL4ZpMRzgqV5cROcgSAancV1MxJ9OumqYyC
xm6Uk2lFVeKU45rEgHthCSxa
=I7Re
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ba1540ac-0634-49d5-a1d0-c9490dd2bea7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAArtTxBZ0FGIMC426VZ2MQf/a/imaV4KmrO4u+m/Ane8hf
Z8buUsNhYyguwD4Ga/U3htKqv9x+dPsE6XvkU3BPOR+bEI9uH1m7tetVvaalzo3E
O4hYsTMNSKkUuT/gW+luWRQsBQykSBZo3PTKrvwdrmWUG1WoiM/YTAyiRyopWv56
Elv40CBr83SSLnzZLlXlD2DFCCk8u3oc4EdEeQdMSKeZNRhsF3w/cZzV411jeQS7
Hx68z/uGKjuW8/Nh4K+0a3iXO9qx99K4p8T+Lt+B3QmxoviW8Z/Zlz6duPkfoA6J
SDQSDq83eAHqUbJCn0cbZw2hjro6s4166/mbVIAb8JMpP8xOOCkyQoOun7CuGp38
u3UFv6Axkiv7fuFYfVaDnIqq3ogFDVKW/4XkOyX3q1Ff6+0SIsYLoU22uKWiQ0iV
oOzrMZrQWY77MPVai6OK9qYSez28suKP5P0lWIHL4Lk1NcMN5MlZqSHAMDumXQYE
bjgiI0gAFH2B1hz/fzwzoyyd6U+KxRGUX1u4iNpFl2Id8HwJXRzqUA0fIw+tWYHY
HEJcaUt7pbz4tQEzfBSbFhQBj13Fydl2GWtcijsf7/BkzA1qsHiy3tKBLOGWFpC7
Ac5HBnTgfOsgvQIPTw8ohEc9S2SP17nh0i6T61oIFJe5V0mA4oEYLgGSpJWgp9TS
QQEN8tzKQ1QAKM5uHrYRnbtaZnqQH/7T3FKuUNguoDc4OBfi2fgeZN266gPf5Tcr
LKLj15yUKeijaaF/13ZqH35T
=Q3bk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bc554cf5-9741-4415-ac49-b09a87fa01fb',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAl0iDF5gqMvVB6nV1BVf72a2jTzUc05Nut47xZAbidlZR
1Y2bM8cd4XJjLhXTmER6aZjAR9qC+9iVCM5JJZmnkhO5VpsRVMsQMeo2Zz1UTTC0
mqkj4oJrPVsWk/if3wx5SGhsdWqBSJr7xjVhqsIf0vCCo17xPf8AST6k/0bKCn4x
GTpwBltXcP6BQqaiZv4UQfOGzQ9xKHU5u6YlKUB4N8eDJqbb4hziuLNTup70hjfT
Av+cEgPprwq4QDb6ktD/2BWOOce03Te6ExQTpdIUH7P388RBs+NoXMIQqx5SC7tL
nPic1LlcKy6njIpXa/WrcKYX8U+OoyXCZjUzKpv3IqTREmtJjWY7Z+jRcZrNd2fL
ZTGetyQxwK+OguIl0+io82jzDChBRBTdb0RT7Hxr88Ez4drXV+y0Nec20NzQj1xB
mDlXyYG7J4dDuhlRlkj9mwetI4XDGQa3YMP5cvrKqI5NYamRAo7fECYNvzvzgmBA
HlGoi2VhY2e1z3K096M9sNve13HqeCltTGEAAFg5PE0Psc1xYsxS9bDpFUTmA5ox
QlbcFaBD/Ve7gj7c70iHf++OSE4cAbecGcX+cyrJ8F1K/FO/LWtrcTrUR2/4M1Q6
2/gVhh4unrZ9+1fp5uqnTlXTOl6WLtmRTQnPQtLaOtEAH2+Wit5jPEVDNRyI5ZrS
QQH2hFPPqJE6gWL7fBBDWMxWpqsTS430e8cq1dETTDob4tphoHYh+1Ncb46LwIVW
NCbP9mKbDv3kJ7ChMI4vPWPO
=nptn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c9ab982b-0c20-4f7c-a17a-fbf09b4f3e3c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAArLvNQXfdolSz+gTFOYO2zfi9/ypwfmgsPcTJ8Pd445Qd
g2Kju27zF766GCvf9JJeKidjGydB9vYY0QGRrV//Zwqj0T29WgJjI4vGTWbLgey6
luJWKpMadnx+oN6pi+25GUl5YjaCj9vSbE58+WJqgC+sesvB2CxjT6LRpN6c2SFs
jehwtOyg2vF8kByGmjyri763jDJIdDBP7ITEXY6PE9CxsBD8xAoturOV85AyJURq
Fb5cojAa51PUbEMedR2Y9GYc3tKs3CYlt959IyiVR70pk9Vz+VpiJ13+wwpC3doJ
WyEXK62HvgZEL6w7CGF8jC1mY/llRgCfnxX8dvroZTK1dlqfxKT862umkO5z4KZp
BnsxCMvAJZCfperVfkrPUZyzJegEqPRAms8d5UgMETm83HXcMBU31Xxa1myzrHmY
8wG6BNwEFkxopTBWwwxNQiV/PIVbbaDXJXtYggcVC4kjn+FzJQtJZ49Ii3r4ASJt
tbVqYDdwDcTIh0havvZDsaQ8kmr4d+IYVv4ybuw/ib/sJ3AMHBpPq6bw/NS/S19q
6jGiB4MeUzTpuGNFXHA7Q/NGgPEO1oaPqoEcSsiHhFffuyxCE4gcFxQol9g9kvWm
SeFdId7erxgM4ixQg6x6BDlRuONjhU4yaZM2hyP5qzwM0KruGYXPJsdsbxsX2wTS
QQEXYWFdrzdaaq0MmGLjX9PhEoYIzSF40n4gY7MAxu2fQZDJW5S8FsgBSISSGDG+
mJ1/p7CKAAZLokeFeTjBrPLg
=uIeK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cfef660f-c462-41a5-a4c0-6b2235f480c1',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAhMsZVkejbldDKnF5QAjIdmV/ocoGai+tOm8QqgKe1U3B
A0D23xSZBDnqPUzmUnea7SYbfWwCXEkwZpRt48f0IrWv3f9FsZcL55G5IvY9+7hw
2ejVFvcJab6w3r9pMmye8HhotIIFqTXEFaq5s4U7oyfMa1dDmDfpf2jGAwDkcKaw
n5FBkXPNILpm/fMZ0yadAVmhkLNPLVQQkHy+Gl11TDc8pwo9i830qSWa0fIxqt3h
Jqja/JBl5DJHKwvwys0xrP/aN08FDRMKc3uNjJMDduB0GpbhluiUzFpi1WgUb+Nx
Z5+FDjq+GUm/RtG8Z7GvpDzt1dVIII5Q7suG08I5oXgUQrYrsgjqDz08ei48T2hr
qeWUII8M0eqKx8x5tEctTb3Aiog85JpHbkjb2r3olGw6wcnCxYCT+dESf6cEq3c5
CYscMJkdoGYQCQrOKUfv4RXlFqCKkTgA0IpdNnE0YV1ncnAZyez4CCv8XNfsLRu9
t+ecxa6KTBoSBqIdZ/T03J4I620JYhjvRI0dV88QdlZmUK29f/1t5utOyMH49zXU
Dw47vAR6Xm2IGNZlmmdiSQm/CEL4FCY8VHAlFGGMQ5Eb3OCZC5MP0aA8l1iMOiWH
kwWLkdv0LGRwjyvAZmLp1lhKP/BPQ85M1sLmiwCQEh/KeCu0ZeliB2Wbc4qL5IjS
QQEZvRnBn9oT3P5LToo5nLaGi62i6v0Z1X0B91gb5uDavLrVZ3xm1tkgzWAyZzLn
MMNLeVsI4r/t214yOrZC0wtk
=UslN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd04ed543-fa4f-4cc6-a5b8-cfdb248fcf92',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+NHvI2B3kR8nXIE0UaN8Mk9C2wEAuvML2hudCI60KUZgE
wCUsf80DevYZQf6Zl9iTt5wfH3+IM31OGnx/maMl38IztabMg0hWHm7zUlPeO0h8
AI9pcKYRDsRfO5x2xp1ZPV99RNcMUjUfa1+4CRktorxdEViSquqMoG67n4npW1Nk
7sraG+6SlEAAFrj/glHd6mOPFY171wXxtSA3l1dZIWlaFFKLNpAxg4kJcBGuCcHs
dt9Bu6qmdfqqm4s66Bt9PUlfeV34kAm8x7v5O09P3YSz5GnAYsiocr885eAnrqPf
9xA2enO0lscPKdT1lSWktgAYNIC2dAlAO4gBO8bFXtLPSUeeCU7zrbftJEpeKOvG
87nWNLlvgFVUWhB2cGIaTJncwrcvmLgTdgV1T+bf6lpejpERWJ8YVmHNYBEMNFop
2EY5bBWhgmJdjQ0R/Y7WjJERgpycav/G+cmDOqI3JbSw1l+g2JrMGQU0iqfVOhmR
HRQUpBT4hckIGiGxO57Sh4IGwkqTzRsOLj9/cZegzi4UXWb6ybb7yGTwIS6hluZy
tvc1GuQNgybRXGwSGTl4dkXSswhqivkSptzdNJKmzxBm5IeI0gjLp03SOpEar7sB
02nQK1aJVXCiHV5PC+hgXzI1ULbVYLInCEnRqfjE5/ioUfaw9VURFoLYHxdklqbS
QQEe2sUzEcvF1gqgPBHOOGcRyX1Z960//8IZeLWFyB40h/eHBR/pRc2TmOeZSXdS
Ygh5wI+bmlvCKo1zh58cIp01
=3h9o
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd32c8cf3-2d9b-4462-a318-deeefcc63f80',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAgrrYvMnongC5ORFiWqCeoaryxHuMb/ggy/mPy00xWqH4
tL+gj5YMrMdIlyjaz04A2l3W72lxzrCmuljTBwCHF56oWPe8ohEyHjx5fRmgKEHw
943huEysQwkQcacko3uriyX61v93iJwFrIoyqaDsIBnDzUcaFvnLEp4IeFQSBp1x
bgug7vBkB1yNGq3kEmzFxFysqa+T5u8O8TlDUADvAWVN9ZebzBdEPR2yD4HIz38X
1tr8+8cbtvvIBW9a0XKDjww+0bU1QNey5lFqxE6paWRUF4DfoDF+9AK26375iLAe
0QudQbztlGTu77LnzZy+1Ouz+aBlY68UmNCEOoA2H2k5741TnKvMMmJVcUZ7W04O
EO+fqUuh3bxoHtur2ou/Hnq3pJsdwRwRNMXmzdc+3aYnANukPkrEvOtxhnvCJZFH
W3fpZhkc6pJZSOmTMlrs9n7Qn4cFLHTIZBmFmZIvtp/M9/A0kmL5Uux+fEsYRiPf
REwCLG4S2O04kmv557eJ/kT+R0unBMGebiMYeDOkFSaboMOQKe+1xpwANnQmepX8
MYJ605Zxbf0nruxBEQ1XfH1+wZ//lRKTG5Tyw1Bqze6WqytMuq62KojVUTN9lLiE
ZAbT/w0i7M2+crPOsa3ELWpzgK3N81PYaEJwsekXibJLPLGWjt30ViwI/MictsrS
QwHxarV8LU4oRo1E1R/Iabx5iOx3TEsk0/aI5BEZ4tuIj6Zhu+l2Dz8F1LPMo8kV
6uxzF54WuwMF4AZMX9wbFZTTmkY=
=uLpi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd898922f-dc00-44ae-a72a-c9aa0451d520',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//RTwbPBQwN9E/jQzVyew6I/0USb2P/q4Q0vpE2g3zYa23
l1FiJ2xhsdgbdLMq75yzpThdBcad+JBSHwjTyv6D0Xt4zRTHohURgxHNwEaCtq8r
fBRmku5/rF99cslpf3d1JkY20HyQ+L7VkqGFy4iKCVBczbYJPk0ZAVCuxdMh2/tf
M090Z42jFarOff4cnSVgICqNfcGQaQT+6xU+x4vgp4Rt9l2hQ4mXZGKVr9bBmUvq
Hgqr1IQJPXpfrNhO0EC3zOpYLzjBEgcHWh7jxD0wd6V+ID8U5RtDibMP+FeEAxjB
6qE6nKpL5rE1QDnCtyaNaX8DJ4iof2S3vHevBbemNLff3iOMMUN0Z8ecRiNKFAzK
7uKpM812Z0W91IwnqwhfHtgZm4uGGoHiJ8dAIZkGqCCHmzVhljivzwOTazPlg9kH
YBOo8lzeD7BCpKrRBjJEONr/L6XuSdOWZIcHpEW9g6SHHny9laCCzFXR6vvVLi/o
zbZDmNqTOvNpiyxshaMeVaHoBUH10ph4+hOW1NvY9quFD+BJSxfPt/fIrUlcPFI0
0zdPRTvUOHMrSM4ahn6QnNaKBqMCdktK4giOtcpW7FU209pM3bBDVYdLRuWHGiaT
80aOjMxFhLf2OYyEH/qKgHt8bP+Nb9elcn9ZqC1HjiLGzbAnbhbLeQSxb/QHpBbS
RAHtfkRazcEbPyHxNoptgTi6VC2t5doFt27RHn1q3g0EEEPzDA6SLugukxYxSw0O
kS1lTq6k2OxqBxQciJSf0ZDXS1xo
=7mT6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e1fea6c8-6997-41b6-aa78-a0086661fb01',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//YYSXp2wrPa5MfB227ED5srSkY4FJDuP5ivbNYXxjUMJF
G1WzaTRVITbEcUu3Txe5+q/benC/6pJ+9l+cR1hFDIvpSdQFh3NYDS4fiMGgL9xW
OjnPur0Hwf6pOkG9hx/kp+s06CPOCGEo6WgaYmZ0U30BXZA5Xm+ZFjgrW/zTCfpp
f/LojmP4TsvVCC/+R2VjIEHcVi8rYwD/II1SMNd/JLPzSD2YmVRNOLC68A2P5+B/
NHQgVLdPwWvhrhluNeDnZa/LOW4OgwK69ham81PndiLVlg9rLP1x5LYRI8cs/b3A
DKYzrUhQAzxpY5aFVdX+9ZUF+Pm5aRGpkjVI0690Nxr+xKgktQBiBa4lyEAF3SOl
t7lHUtr8c54nMtrBup3qy3o7SpNdtDFEJQXaKt4JsVax5RNkjbKPCCsFplDSduEY
uKItAnHA37iaiCWBgXygjtvBHoDFZclVCWEAsy6SBFsauX9VE4YX7HyYx2GYOxRW
AHL8VTWSLumY0VqwDhMSNN/qDtDg89MI+O8y2oa1B34QN5r0rGyRTuSXEhIcrg+E
UPXSEGQWtVQbu62fUlgBgYFH1WucQ1CYRlB+VPhk+duZNXYPPdozU1v90OSp9ttG
CBqS7sl2E8gecdpS/50ROYd8413/mQ3Kl/EkDyfCMbCkqgwANiW8nDbNPC2bPSvS
QwHoJKWBI1Ib8x49x+z80IuUjd9oK0lEE/AT22MKOzjMuXQqet5QnelSUvxy+hUg
I+GmYKzoq9Mp3GmkkURy76cQ1ek=
=TO+B
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e2bbdce3-11b5-42a9-a658-f733e67273fe',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAxuQ+0uiHk7YFfR/o1hxLKD2yA6CyvuCQUPdlFn1hY9wc
Z2D3bJeA28lx8vfGL9OSg2knBJxUNgN3Mb6tDW6b2FNlLCdnQL+kfB1+OKugeX91
RcW7ezk6NO/874f6LHrgCQLbTuQ9/h2ZZunJSKr0Nc1s4c7a0qmozCChP+/nTbeH
roR+P6RaHu1Bv6dHOJbGXypW6OsA7V59dYkQG6vdF43su4VsslHXd8VxEUP61xDZ
O+YedemqjOmQvZRbJpD9EvpsafWkZuhQQsinaAJtxhyiYGdT0BeVn19klMS76GJX
SuwH6q6orriBN6FPzc7wonI9jae/NfsuYJtLE5bGqxW7HCgSye65mlEyVfzphMxj
NtOlphcerQEnmzp049tCPGl9MgNlkGtBd0Yy5tknbii1gNIXmc69MoR5FUCGdKv3
fmtJa7JG9TX0DWJBr73d4eQeEMFZOaAiV4zI/nZC3HtJI/ZsLcS51hPuG8fErv8p
x3lSe0ZgKMV6BVZUSVQTaklY37Vwzp8/dpIg1fEwT+8PG5xNKiZf2GxqVHr9gJxw
ZbA68f50NcCR9UeY+FqP1eBuiAQXhYsYViURBO/Cn9EY8iSEkT4Pfuucq12BkqBm
2kxiLZmF7Be3HL6wEVLEUkH8lGkqqAMH2oEXm+IMpWBZPtzsyjrdQLhIMa19icDS
QwHWd/BGI/3SSojcZbWv1brnmeqgBvfhpXwrC8IaSOrvlM7F4e971g3s2EBHexrK
ClS9091Uq8sB5khBMYp8A4DczC0=
=ByTn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e4a674dc-0b1a-4200-aaa9-1025433a0d83',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+JUskV8RG+sWu362+DTLdSUXWzD1pwqxBd6gbYd9/UyPH
LOmvYKSAUdcWLTDqCTeyi6EZIYTa8cumfgn+9oteVVX88ZhQ49ZiVSXSZLIIdWaW
7MzocO5kq6iBg7eZllb0ktfcbpaqOGaOKqjs+HxeQ5K1k2PdaVD3rusNptoITZi3
oOVwYhmDwEpPB9GQ2DqejKictXhcYFbGtjkht5oxd+pe+9amF9FgA6nFczqPwHxB
Zo3I6z6rCawQC50kzeVUFVnvGVhWVAUfoX0Rl20ix3UyvkAdB76B3G+l2hr9jLAA
rppOoBak2yqUaIqjz3+PY4pngLhJQDp3E/Xly2MdKNqlJUzt1Z8aOTQcc26nZ/Zi
74362Qg5oE32P5iBf/rMUq/kNO3F0kNp7OciqZ6dRhfRxXxWowCuO23fSYQWuxC9
rSVdCHkxyRCa2bHLA+Ks9/nLPJBQZSobDtufUeDKXzFNsABAamh/k1hdEkS/c1j0
tvfhJuqSXJNdqa2/zx0+M3gsgg4oUQhoAvcSnv4hzrCv2jB7MR5pfces2nLSDipP
71MembIypO2LT5h2XfSxkyIPbp/PgSv6X8bYoKLl8cT3w/5aLAtwK/DdBO3OJzJT
S7kPO+9gEdYv5cnZmJg3/RpIk3HnM3a6KznBL6UgJwGgwyuTixgPy3nX1wRJ5ojS
QQH0KMB1G7mlpk2Qq7G901O3Hn9yXFF+QuHwiqYqJgFFjot4MAF4jb1uTX02vGbg
nz3UEOTyiVsDg5L+B6uUz34k
=SyKf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ebe1e312-a3e4-4427-a79d-db8f27cafb31',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9FtvzSbshQ1fVWlub55gXSAu/0fHhUL8rUUkSA8fwcA9T
YVagaLofglf/49wVq+IRhYPnEsM8sFUwp96Wom4yy7p+TCNqWc1d8aFFFY5+pOGW
gcSQ2vqXd6/J22iKFR9RlSD5xGUg+gdAOS5TatKwP1JM36pXSBZylWl+KMW/UaTP
oJzD2MQYUsZLkDlEHrWNWkDvj4kUbiGPR4j2NI4nO69iwWGOjbSbCs3GixdVLrgU
aIbvCm/QDTwKGM2DZ0e55ihUfdCID93399MMFeiXqJchzjB+Y7oeIfjXzONsvLQA
hbdxYywBqAIFstxZ+mQFK+EeX+HKqxch2Fkr6/RsbToT17Us6dcQwBF2b+C4W70C
MahT/GC2LhDtqYLx+FrSQ1VA73231o1wYYuaQkQMjXuq75B1Xg0QyQcRNVHbY4CM
zgj1xV4fe0wTRxWLIXXxxEzXuCk38/BIu83yH8bTNtkLF49dGGZA5bBGjt6IsupL
7gjFVjcmt2QfKTxOlZ9+lWf7HAvF2DN47OPN/TcAeNcsRhKjmH2S2hC3s4EGNk0l
fhwPXipiF9PpNUKM0tS8AiDpwpSzU+KC9/WUkkBq7X+sbrblb05utF7XQkWvQxhp
qMw54cVizxvWKfyrxSsZ7EGyJixgcWcNj11aBFHjpq+TR6Y+c9N9d3v7ugiSHn3S
QAEcdV34EjF/ZxmeuD/V/P3V4YtaKrijdrczRD7Vnzi52tQuRclY/vijl+4qSmSE
p/n9TEvx728qoQF/DgYeMS0=
=PR24
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ec1281a1-5ca7-478d-aca5-f5f9dd0194c0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9EWnVtmJCUISaZ2v0gPE6SRUEdgxRsLqFf5xoHZshB2gw
pgbFJNcWqtUq0CgHxc5qbA+mFNAehvWal/8nvfeB99dmLTgI6E33gCq8CqJOLaO3
QZENMCLmjyPhwf5Wt1ZdGwR07FLvM5/If+2v0FkSXah8c6HMruwaIW/tJ9a5AzzT
6rkYjHCNJquhwBMrZo2nZ6OTUjp72u+QAxvkL4uyH38xWCM3a7UrJ9ygRVfn8+k1
BYzn47jLXKaMf3rxtOB/RpnPszdt/qn6Px8VI0mJlxRabwWXVYU4daqp0CBM2/aE
oa3DbQ2nhFl1snQ21C30GEI1i1yyg5DuXKw87Swt6wjTtr+qcOSSpFBUDdpZchnE
uXX2RKE9L99tf4wwEKfTqO05QFqCGWHmpEo8oRKNL1LyUS/MnREIvBgqu1owH1de
pHrTqTD+JdT4AckDgdJpTf55YFM0ZOpnUM5lSYcgMIlp61OMsde15BT3Q4xi/4NV
DPA7TJncJ7wYFHGseDmbohpw7LRAdTD2eJuSghoofiH3if+ShwiP/bLQ47PGcP0i
5rrKkKpQ4G3dXQ1ss3EqHJyuktWV7tp+dttbXfoZ59+6Bll2Fv77gyG9TeAaw8Hd
EtFlNocxVPSaCkKBB7Dfwg7T4sIIXlIlANg2QpjX66too+B7+a3nrAEuokmO2SvS
QgEZXgTxoKyCLqqpS1ZlBicX45uTdqmNHe+Qiv0eLGXknNf1OTI4drYrWrQXziaI
5zebxQEabB/XApsKVW+qI4E2Lw==
=68Pj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f06c1cb9-0db0-4d7e-a208-69d2f9c710e3',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Kutut7nVPivj8OivpgKKTTEfXE1qw63AwFisGAi9ZL55
hvhBibMqHrVxG1ZLN/svmee9MwIKS5/AbtPxNSGRHZDa1+gC0VQcb0/7qbAfPTF8
sZ1wRn38p/W+Zn6A7FQgFNazGJy+Scf15uEysMnroyB030znS7jNPfNUfhO+KKip
5VdWv9UtqzRTL7MTA725Q0CLpWSThAq8YTit0b0ykc8wiVeddL873QSnuEwtYQ0x
n1FvSakdH6CHA0v1kfCSN6QyjvCWPLsyHWSB99XiwgDtbAocNKuAh0Y+1SkD2RQr
8HQIx1htrhIdYYlpGvq/3WjYokJOhgg3U4TxXlgK71CSRWpUJ+PQloWOE6QA7c3R
krGZehfnjKUruHpGV6wBzuxDiHmkGdp7YTohxnZ80IYfzMGHDoGHHr+2TA8rDvFv
ZPPPrVoI3lZNGFjAKbKKUHzHYYqiybJEtt2iuXSHvJPxnru3eH3SfUanTGwZxSfc
7IUGOsSz//huTqNt4KIA429bVbGz0J06RMTuwezZZw8aMpWvBNqlbFpjunNcM6vO
bwT/EatgqjvwfByRiOHpvgCNh+eUErJYQNwsrrXTQ1TcTxNOJNVTC6Yp8TDPzw7b
M1EICPYrHmNkiBA09kPFWc/kpvcpENNsXrmZXnsUDtz3WU0ml0Z337It8SquYNPS
QwFqup3B5K0Rf2a3SwcuaUAWe/E+rVY/Tzv90R8gKpubhSTbhbLmKCHiQRT5h24k
MqTE15CAU+QBsH2l3HMcP9kfw+E=
=MBFJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f2853152-b280-4709-a475-1703e5a17650',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAg0aUnoQUC2KQTLPg2l8DyZ233P5B1UJlUItAf9nJ4G3l
e6KLPEycR3ByNqz+JRUKwPXwSg9tgFrVaZKi4qOlcVky5OHhdWCshoxHraF0+hXz
mwpDdZliQjLv7RL+jeAU9ivF4n/SCYGTTL+cvJBWjxLFfPAhm8s1IPudP9paVHe8
2N3JIfyNu4Vknw6fkLUBEiaTmLUiR+TxjPVg7z+8TEpSoIinREIJ/scyY6O+izZc
81WhvsWX3tlHjJSNBGqCirlyTW8RL8XCEgsf8N7pMyG7edX+xQBCz96KoQ/3nN5M
PmCSxT13B97AUsSTF3ex1R8gWTZvnYdF319I/zxIuROROedvPuve2u/0bEqDBRbX
hdkA8GoEc/IclbvdL54+NxdjE41LiIwZkY4KJCzhMwmi+bHC+HkLKAtnksLuCiLB
HT7LvG1cYu5MkaTu4CH3B9+BsIldmA9rCurgGF4cAe5iTvHye1IaF4dGuF+NyoTP
CSyk1ubPe8vETq5OeyUa7tcyTp+62naxSIycq1yJOvIt69WWC+Par4QOiu+3ZEuc
vHs3ZMyEFCMtKEiNfY+MZZaZ+23+zvy8Zg4DxVAwhcnyJbhIPSzeb6a134B7VL5/
L8cy//lvtKJ1+OgESbDvfaqEUDkyKGGKqXRLuG6tEob+3jJJcXwTM4KR0QDAebfS
PgEjAixcof8WzAy/rmY4YKf8uuk2qAGDeML4Y123i1CG0Idr1cTRsIC1L12Os9QB
vqU9UYSx6c9EKhp95BAI
=Ua+Y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f5eb6a12-2e1f-4c58-a7c3-3aff77279cae',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//RtgH6jP5n6q5SwlCcJ95d/yQk8VuhS2VGcyOnhrozN+g
HaYtWM06MX8glvCI79D+/AdkTjKrNypzNNSsR4XKvAKJbN1wm7FcL3Hfh1pq1pVp
ueZXsewKtjQqs1e9nVJ826DreDcXweaYC9GVaCeXTerCA6nHRWWVJzOjRaLqSyW8
L+FAsqLUYMxZtN58C1K0VjsERIgQcVBlZ7Lt39KLrXKVl2jLjmS2DV0rK/LPbQeD
MjZwsiTApjMSfXWYIxG3vFYVX4y6Wh1zoNYPyQuvIgkNJONoErteCVRsbCAW5fi7
EgUcFGeq3kFvYpRL0tb0+EzENtz1WkjleisfARBHO16WiXWNO1Adv8UIk7J2PwNF
54IEWVRvDDQ5DNAuxoEVe06UzDp/nG5oi4DX3d4PZw+MTztatEB63JAMrN9V1A8l
Ho2OATWr2jZ8GaKPYJu489WDJ4JtAYiulkTKURw4JrEh5q9wx4k74Zp9egBVDlVu
Eh+Xe9gBziuHuJFGHbm53n08taC1LpeYhlAsjwea/NgXIJDH1Oaa2Lbtf1AANC/a
X9KRAVEQYZ52t+Oyd4MsfWFUxZXXwbtQc105TL04RtKFofQ0Gr62D0LanaqOtxCk
v2VJw9AXbCqh4IFGUTkkYcHlPRF2YMPpjhhIbxYxobHk3nlyjOp/vbFr4I1c5/HS
QgFdvVyLFbPN3PcLcYZTiJxQPbKuHA3T8bFDT9p0MO6401RLifJ1sp9dIi+1uK10
gLjnrNnyndqOpeYx8uQLpwG5Cg==
=xGoV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fbfa2ca2-a495-4462-acf3-fdc021963d34',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9ES1eUWWsTCkJuhU7AP5mTAU8FPdQi4QbQglU1l5LBxjH
PhlwmNoWPH1kIhLD98bhfIp6ve7suxnuIRbVRfRPVXXw3KXPia4i49KK3N+fov3P
EQqen5BPAnEKKXp/Z+vsD56D7dDOsYHiv4TbDppV3o+JiaDg39WXO5H2HWT+BQP1
csMCl04ExOKu5f7UpLoRXazh6yfKGaocUZX+gB6wE+hsxAmXTSZ+/0E1B1SHRBW8
Zo/tdjAIQcjMEHO89PC5LFsqeZgw+Bm8uH9+u3XhxAG0VPONS7Ky0zRSnBoEvvFu
Y3RkcZZZCf6mLkKNV7R5ARgA1fQ50f2xwCbb1MW0tS2FHnLrMApff4P6bFGVIaIa
MEQN+GFdur1fk8j8wRxTNp8i4cJXSt0U+UKhnz2bmuzNyRV91Rz0akcBC6DCQUu+
JwEJYuUHhsz6A60O1BMwbL8MuK1jqE36z5UmbEa6qQme9DT9EVuKNAqfdMaGb4JN
rO6zhXGqNqFeApQCppYUo2E5022M00OEHexNJCmuGMzorwhQOstDJxvfJ3H4Dx6r
tGP1jB5kAv1ymEd09H2yWYxmadR3b0J/KMvuQU35L2Kf9XcmtWUgM9YAEHGyWvK4
bxm/XBsD/JXFRL+KMX6b+USSzAR++4dezPppw7Pas5Ty3w+8uf1QD743bnalz4TS
QwFIlFH7f1e5pmmepcQ+uFuB7cnfnOWGxzrUTTyREoGOsiD0FQkEz+VYUcYO0p8e
2oUPYIQxT0XQaNhJSMfSsnYjArk=
=N7u+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe2d8f8f-5694-4b56-afdf-d2ea062a4a6f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9ECYqNbFEN/k1P5CAI06sM/qtbf4Y66Q1+Yw5h6oAIAMH
vk9OV85EzT2SBdJbYMxmBLNdH1KBklvbnbtWSTCepva9+pNY3unftbttrOQrsMOc
NdZLZCc2E+LyehelTl1rENhjSLESDdGNbaUitgT13Y6gL8pV8qhWtc5/wOdF6d77
AtzK7vrZhVcs9DtVy46BH9RFz+7fbK1yU4x+xe3osaLjaP2P6hPm3MlxOKfZJBag
BjYe8/ivjeqfuIGNXDzno8TAsjgWKjFvIRM/gESOTXrWeVEGVdOWEs2jmk5DC2hs
AlBgYFBSZcQ2A0/W02gq1y7xNhWQPEb62MDqX4Bly//iTMkcSJlAg1OpSq79J1sd
I0vdsSEG629sRsp41Iij51UsdhmQCJwfwBLWqyLc30LynnAmvgKUSq/3iSFY21cp
zmzh7JQ7UBiKxfxxhaVRRJsFLHvT+wex1RXHx6mA43l9Y7zdL+dT7WJ0AKohPfBr
H3A0l1ADoG1D/HxHzETlfsDur9dcVhe7DhnAyckw4p3nrfvpcXV7a3f2lYRrBeEz
k/EgCfHrPNfqtDIkhzXFTrNgRz9/4LJR3O840HAkEU3QO1cYy53LNPpWT2A+O6wc
z7ieWYgVjgeQswpFaQR77cu8qbEddXCZE8djICDrwBJxmK72DZdPikMA9x1KtqzS
QwHmjl75YQUNOFlAN6X7ejcC+1B+uov2tM5yQ78QTK0gyFec3lF860ztYn8CVEBB
25fkyhcCNJgvzlVvgabe00p/PLc=
=iNkN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe938ca8-76cd-4263-aa90-ad7d9c6d4b33',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/8CvFGzOEMYJunjWurSAs/YuIEAQ6G25W5lD3RDJRIRbZk
pe9fdedys3viKVKL5LyAFSF9gXgEaK8d2Jl6QdcEwZ2ed3NbAzCBVRVivECSiX3c
RyQedQe3qPvTDpRFk5eFvjuywe1lAnn2yFsI0npkLNP3FO9ACRNhBTik9SWoiTFL
ZBFmwp3HuTJSFSpRgH2RpfQxSI4/Z3jp98772+bz6RbCwszMwrOGw/AyhJsel3WR
QmUKiBqCbvXRac9YswRBs1BO5vdqq4ZXXY2oUPNb8VZ2HOiyffGRZiWohGrp0RSK
l8AbCG0YAsOPMdjPSlxnDzHW/I9G4e3y7yYi6MgZagyjrWTFo2ySQjHAKoNhYoLT
y5EEt5ebEUBWO2ORcmFVhHa4PpLEJITVYp0p9Tdaoarbi+O13i+BLddT8TLzJIji
QK+cdCoRwiPFg0nL7QnaDcayTM6FXqq2/5u8iZ1B/DD6Lf2DGo69LIIKgOxjj+ac
fXaVpKPHefZInq0YjkVTJRu5Nn1L5hJkTBmss5Ru12BUs36sB8fIU0nsvIMInVxh
zEshD+202EHTWwcxTHgRvbZEhdpmwxR4nz5IpBLe9WLYHCYXH/2Jst4RXH74jl8L
5l9MMvZh+F9pusL6w7bUuVNQeP9qMwS0/RuKPvAn4H8tApo57ROFcW2AkFsP4c7S
QwE/D/Abvzn7F4v5d/b7ziAP7vlZpDTdSbujWLWtn0G88bMb63JCYG+pesEeJY+m
/c5vlByXFS0yZdBYLrz06JkYtTo=
=fOSR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
