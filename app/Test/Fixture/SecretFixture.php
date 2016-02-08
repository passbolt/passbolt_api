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
			'id' => '0575ce5e-da72-4ac4-af81-6b97b41b607a',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAi1lv8aiv+rjhN4xH+K+9JxGilRb5PpqGNC/uulQ/rzxh
K+klpTbhDIvX2F8Cymi2I+7+AwlCLlJM7F3bW/3jPIGu1Z6LZZQoXETXeaqkEXeU
5i0HSsY330klFHJ0wFCOZdugL2dx5eDdjXUBfshHASyJ3wbGU6+sDE3LKtf/PFsq
xSpq/W4n2mghQc58/eNVhBeHpsOAzPiWLfxYMD4amxmJXhQY0cTTmUc7EPPDqkNv
C2L3+QdHDdceYf/k0jgZzVZbx8utXvXWRjmlzFOEaKDk+ENivUpiufJK+WYs2fc8
A+9n/UjRxnK5qc8nKNJnoFnioCLu/dZWkDH+wm5/08AHf1186N8odfYRywS+YcAF
eeiUZbS6Drd91qZyFLUL6MICDWAPvsc0CofEtTfQC4/GeMcceImByBPuHFc5cu+B
7pJLvlHZsFT8/+4oAvwXQ8JBZIyCCMBNE/lKgaLz5C4DAQG4fvUuuwcpYTI/RERw
YXQwtKos0C4iIob2Yx0tkoX4nk68iAXXb/0Y34yzPIew0avrafM+KvwvawnsoX2b
8/MGgPDqZMBwLXDwSXm+4MqjiKKRt2C3nm3XhMDvCKZvM6K6Ob1hLRBC2WBOEeac
Dw56p0ZxtaS9k48St2FjxlP/yVkJqGcN0qKfJJH1/XK/m9geRXP0/6xTuGprhcfS
QQGiHT/S/JtAitk2TRDPuBiyZzGJK0g+1mr6mPsrU3aeuIS4O/lXHxINHPn4Kbde
wb34ded9PjmS/dGJEpieJL4H
=Qmhd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0d141598-a8d9-4b2a-a688-4b0453bcf298',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAlCPoCyhg741Yf++oUIdhnGO0j9+6GWgf5QhkoDa9r+Bw
zSA0jrPApqkCtPwT6z2gyHKS6bvF9WW2vBdOi1UPPleMqFlWKUvC/sM+sA3xxfby
Bw5boARzyrvtRcZ0fanIw37hmgGy2RWKR6f5WQCJ0qKXirrJReuhfWrvnfn/giyG
WAecokUqR21WfbftoWOzHd7wN6eYTSZcwQTPsqvejxSttQUdS/49DAPDp9ELoWTD
wASOYtBKCov28e4TYxwSCsLMcGHsuf0k/k5O/NTopjpdd0RwuRuyT38i+vT8hmtT
Xthg7ycH6/mzR+wQT3gZkRexd7yAMypTKR2vgWf+fWN1i0u3oY/BTqEIQgAhqhUa
UKS9NaOoUMwirLMLavVAujNxUzyJRDsV1qg663/Z15p2X+jxHO1pipcn04evmTz7
5YAR8ZJ3XzpIWgknnkgDli3CfF8Fccrc31M88WQ1V8zZicIrWYK0fwTZ83Q5h3yW
jTSZhM8+bGqmCtPsDmRWAnWIgkXGhyuw1eSnrnTG4lc3PnVLnGnCtJK7BtY8iUOQ
s97ft75ipWFJg11qgAZz40BEjhWowhJTupUXM5/1yS593Nr0HoaGxkNoqFFzFNXS
t+AjSRZd2pqCbFmGe+oiZU5gJeLuWRTnQvifDZcVfc0TGcmmjK3qVWmfqHRNBkTS
QwEvvt7NutndsBzzqvuhTVu99ADas2kp96bkBk0t6AxLYn/wXHdteBIYkr3nhnDo
UOmIolt+Lkzf3rH+nyyl72yriuY=
=CBoe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0d44e980-944f-493e-a66b-e45402d0fb93',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//VlxK6n+3Ic7XWuB3HYdJef7R+QC7T6cksx3XiuH31mod
F+yl5Phlq35myVNDRzT+hp+HrEGx9Z8WkNJQcoBTNQddCqkyLc/c8PK3B+S5jh3J
Yhjmh3RlPTu3R2pLQHar6lTamQW7QowXtekw7PLv6hC65sgZJmYdV0A/PTlR48g9
71Fp7+aNZgojAiT1wt/nQc3VeuAeabhpCSURU5ehpcI5Ep4BOO8b+zYklCMS1niJ
DFcLj6BmZBN3YzkOB5DuHDcyaMDQoYnNn8BEKoBYSn6PLuDhVz9fdmhDOC4evM3g
tmn3vpjv3ks4e/wTCLLsKNfl0pI8MF9xMlapPa3+p5+re8JEeHzAaWGBZx5Kf9gu
xoEbvFMr1ysdi/WFaBbFnP1GXpstTmk46M2eCb9ZXxJr/jxn7tk2rOKwfrFoUQIq
uTieSTdWtUF2fQETf44gBoJ+AXFUx2G7qwF+sm+2Krr1DMSZCIe87HEWelLMAY5S
O+gZesYufm39qIYU3LMiwQyqk4RWJyLnBvtGNBzmtWJq/McfUghtBej8WjGN1ViH
3Fvv8bxQpEf44NEBPMh/umSdqFmUbemzB/MvBQxAgP383IhfkDSxIMzSZZA7rT64
DRN6gkBourP24hwJieemZxjIM8ccXQG2G8QtBCRTmDZYVUjvBcxT9WXcwtzyqHzS
PgHUF6ccVL0f6nrzw8w41Ihs06bjf3Eg1R2C7WLS+HnfVkXeom3fV7J2Om1WSZjP
eQEFjYExOavoaZoKPPmt
=O+4l
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0d786716-9b8b-4af5-a937-c065445ac9a3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NRcOgxYAXr9DV7t7ZO5XASK1Nkt2uSZ8y/Gr81P1A4ar
GkRJppz1aQiwf6M6LNG/Jc91ltYh0AVfkiA6g0oYfXbBVDboMRCMWbQgohPn8ZHZ
lX4M8u4nqyDnUSu/Fh+D62dKKzaQ1Ck89MVpsQJA+dhOAjbPkTeES7iAMJMPD18t
IdotzZ9vhaiYXmeNwCsV3p0TcrwFDYvqj5qfgC7wT8fR+/1Txcu08qG/2oUtQoW4
2OxNLwdYhyPAQ0sgS1q5dvTVCM9g9gwRBwIBN+Z/jMV096Udf7soLKeeLL5RbTnK
LNaBzWwrFfnbzHlj+hiq97eYV6Iri6gZVL2knhBTbfilqMMC20ht6qWpaebk6Hi6
UYVl2qkuNDsPTwVQvYbN0jhE55Zr1J0sz3JahwX21IOYXddKnbMgepJDHgbpgV2y
bZEqFTkuEClXfP6te9HZ2tJRyQYMuWN5in8OF8RLdMKHJZuJ4FSU2xHVumjw0Rwv
abPhN6ovwDjZnGZC8aJdGW02SkLZa62Ce6X4JAeCmoEt+XwmgG6ZRylE5NJwFR3k
WUe/TjlnaKD5d4imfO4sg7jzY2rGRoZx4bt8nJf0fjEUiqbV25hhMUsYSqkAJJBv
NAEBRGsnEyCO8NWuiJfAjvxoNvTd/hUygbq/E40slp7voQFfeOSVA3KJkoZ0IwvS
QQH1OJVePoLjxzkUZFHKVlw5Q9FtFke1Jz61bxgVpxCxLOhRCdZlbqmRyVXSXbMn
lfcD9M2jq6D4/7ww3CH9WAov
=yMr7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1270e304-dea7-4f67-ab46-e56b651443de',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA6nGRgvLP80luuqFXAW8hFj3zahdmaeXu11GCC4IX7IsW
2lcYOrG2V9dYfFqVn0rb4eOExkQVlKWrMtdihL69JGFV8N99uQgdhJFEek5iZxM3
be7uX8I692B/bnuLHbfMf0tdB+CnR1+BhOduqVgY8Gw+MJjczDQ/hUBtPWju/KyS
c0TPNEdvz7h9vI6Xcfp1ILbVbTGSZzZKgbwux66pX6TgXGXeOiQkZsTyS/S7JNB/
EadDQYMxOPdvbpXPLgAmZFdFS6IlmZtqr6oLjyYHetuXVf/JKiDkY8ar5Ivavsiz
gRxDMU17XucpG1nt1JppO96kpbOgkZ64QCchs8msYdCrpQZBHFm1eWhK+v7xFqIw
DVxvpXB+Ker2Jp/11CXi+sMrVtXS8oq0T8DuzlSSH4WF+AtfgPeHchhyWt9K1OVz
Jzjhn5aOdsIE0ZVyxQK8o8AFWWw96QUkbHJ55DEoaMz4uXT5oqUj/8CGQVqynAxe
nQ2Q80mK0sBz3l1V741XTCGXP6x1jMQt9o0hgoS0g+g3pKK9F3nW0/eOboYVHZ7D
LzRrNbR+Kl3RDlYeozy1NQTUY3zcZeT6hGz1jkPAkUyzDIHLOP2HSrDoIvTa0Z6x
Nk0m6SEy4uG6Vf5D2K54t39LQiPt5sWKKlhMvxC+/1P1xskqcUxbw0mCFDQY0BnS
QQEdOZP6VAn/SM8eyXhjem7PmP07Z6K+BYZ6CfhAYULH58tF04vLvOpQY/lLlXIw
Rjd+vwK4eIDB/DM/UVHMPl0J
=F1Yr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '172d4eb6-2f8c-44c1-a441-800c2d12b5fa',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAtB5Lt2eTgkXj6hpPJn9Ab1Iz9l8oZP1XKJbZvdUw/wH3
5Wb8FiVZnwQqNe1EO8EfsIBWbKbCXIMXGborDsH73Wf+wqK5eEVUHA+6WvMTGtfg
Tnd+b/s677wNViEC+dx+CgXz3R5r6L+XNoJStF4xi/Ay9nAFrH6CkqO2gjDKR/m2
f6VAYOqq9z1DeLOdm9e8FdofdJMcImO5r8AxcsaruMf2jyDEkVPjSZg2BQYdiLGX
Q0PCgBnQsELNXp9V5RZGW9s8d2bWJARwaudJ9oLF/CbG6GY4VgR0cPRigoksqe1K
gb6q9p/V2koTLk+ZidgJoCmTvd6+cwDzqIZ98alAAoDeDDzUCzx5E2+g8yGWy8wK
QYCPcybmCcBz49qqgxIzWoEgOEC0sykNxqVNELjD8mSakyAFa8QstWM8S1pZNj3q
X/YxQoeWckVCf+1z9W59UeXJSD0rFvSwh9sGb8+z2GssS/D/lLAkgn78HRTyR6zy
xGT5zh7fTxLLSGBvOudInlA8jvr6ZjfjkesVBMOQWmlvTupsKHarN6Nc61rUltq7
F0U55ssghaIhQXUCvvqonxqSU/c9qTbYMFnn12g9skAiFmKdQ7/3nN9Oxqch2HBn
7lssrmsfVjqFuG9u4TspQmgn9AHchOrIw3dQG7ajDBPvelnkeCUNUrJCr3/lwjLS
QAEnYM8KUNH7GG/0y7x4OywhyUv2dCeBqeA4Ra6Rc15TLbNB+kSyyPTV+c0TzmF1
hKAlEt9UbIrPEd1i4kE1LT4=
=JzrG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '19a52fbd-0ea6-4060-a94c-0f12efb7617e',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+PSB7bFSJ7HE7dK7d98ll7zWnlFNENYpgZ3cQcqInB84J
qbM9bO4abEaAyhy0E6VJsRLZbKfYKZOR7+4TBgfgLLtSirLI6q9FC4+NzvxSdeKx
Zr6JPv0r9197Ggz4K0RcJznV50zrO+gfv6Dx+Z6C9PDeiJ/qQcg5UYKlebxPn0Ii
vOUgRycGO4AAL0ogSDIzZaJ53SU6whLQDMCGKry4MMMFGYiFjf0/hexLpzuTCtaH
gqo6D7fYtHi36nHOazmDDAotwR+vBqkErIqT+C7XQbTB88+VSSwYdgqMIQy85GOF
QU82IeXlezBqO4go0OH79SxUfAzqwH5cM7hqygiLq6cb+4z5UaLOzk7WDDqfquDU
AIsZ8NOG+KBnSGHKb8ZWO2JCV8ZnquAd8szl6c7TRgp7Bkxq9NWbP+8P4nrF+ILz
Iy07efjJ+6QvZsmeRuDRL3pTna4r+AgC1DFn3/9MGt0mPQjaHaI2CWCg+zn0pxlP
kMDObw097BOg7Hhlbr8qBdnREF8ksMc/p4tOKVhDTBnnnnnK7bmKW04snqHFXKFq
lz4ajXzYWcW1sWdmNSfdZ1cqF9vN9HB+x5PnFjIM/toBdqWoIaPmLZsk7E9oveEk
IVydMSLAoL6vZIc6n1fDMl5kfvkz798ouL9/zY3lNLfpnAUChD3KPej9sjq0T2TS
QgEcZMCQjCd/KRSFxzcSuDGS0nYAwJiKOIgPrpAY4/SbjVGJjp1t/sMcORkeXZYJ
Dw6BF3yjha27gnKgHSePf4Ujjw==
=Z1ag
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1ab49d5f-dcd4-481d-a5d3-07bac83cffe2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/6A6k0MxOT+9wxeYNBhY5I+iQBxcsZBigwULxgZsxX9hhP
lgo1RXtbkWvKzRHJedoEYzFSry35J9L1eBgMwaVDeyZTQkIZHdzRLo43WVmnlqqd
to4n6W7pX3TiRoXjCocxay5pYR5NrYd/E8nXxrpHxahnpmwpC6aGiNae8d7okK2t
kpgkuauHioiEkt13xDmnBgPlAFoVtO7f4kIZpzLG6Zgj4KseBeTu0+AwvdKvpk7d
t2y1EVme97fBhMYYdwcyS6nUFJm3k+GUYjt6jI6Wdmrh4TkyF9Mh5VJdGvmceLk8
BZvYkB/HQSGrMOhFm+0wPvTsY4H3/NXcLlMyHUN6+YTUvk1A7mAmRv6+zLOnRbC3
IMszfV/lMG32Q2oYSF5wDgja9aFuWs//LeoxUnZrMV4WnNb3cWqGkA1kdgA8Qyn8
if9DqH8Ice6bXVampXYFqQ9ZNjextOHLvUd+ZZzG8ebxHtaCYWVrb+dsEpuNrb5U
FSFQ+d2SIYMcGkZTjFTZwJW/W3RdtJBYK46qsNkbQwwVFHR9q4BSeLkOR4ovEKOz
qR/eq5OcprtLZXHpmgfYAxAX8YcM7fqCDJiyL96aNEuFg2C78bv8HNVc3GEGIPPe
Da4KOGW4uL5dgaSrQfFS8IJiYAXVlXfo4j/8iOQH7gfJiJ9XedeQYqZnPaUXu5LS
QgELd9zuaOhFa1s9ZXeOEBaL7bJ8ubolw1gTKRG+qszsvDfKIObpRUS0HwoL9GYU
+BoaqxZ2oodXElWg1OmQc696dw==
=Ienj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1ac46003-ff1a-45dd-a6c7-e92f4ca9f623',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9G0VVQ/EqEGM8V36k1R+gcvqBIkFQj0/PWRervRg0EVyS
iXO50YmiG6EvP3ZhkGl1iSBtrmLTVLQkZWf5RF/cFubcNBo7I3DlT9qzGOEtuM/d
iOTx66D1+/BiZoLQ2Tag2+XyzRrz8FwhGdfEmFSlTX+Gk6A8UsKiYMSUPzqDUwL/
f8J4cfFDIuLZ6/aVvjyL0kZOcn0YeoXfCtouA+osBpx8aG4v9mdEFiLdtZYaziLx
zj51iTBzSwG3Twp+Q2GevwPtLF23hykCceazyP8XpwOtUQ+/Q+HgjcG1eAFIdaZB
6ii5kCsXWz4b/DBm9CwvlbKM1ma88w432cAX6P5XRzjHDmguGgHeDh61uaD44RKx
aApOdBiHrQDvjGAhoP8B7/PwvFBgbzJZYAXXg+ixWn3uyaHs2TTMMQxKPvX6UVTr
TA3B4edZgKfbirLNO7Zrt8xk2dxuYBmhyUFqhAu0JtBz/HLuBWtgVgSkw+RIeMWQ
bbSlDJjvHkRid/Ni2/WbqSi2ewheNnz7p3FCIqV+4BRJaOrLCZdG/VOYFcmbnere
1S9yNkHe0FUoVOxlxN8uElBlF+DDq0S8CftNyQupooMRszxf8YL//lJT8/W9ylMu
F9QSUZgtT4EOM52CmbslRW0zDGVYkb8ADQpkQawpCoV1MA3TlGHALs+TIqVAhn/S
RAG5JIrqwZtDkBZ/2gATDCwRHqkTOMD5gcXoAmUPvGO1u+B4MtY8p2x74sBUvprk
eGOw6lrCGBNIXMkZyjDHdxjZudjE
=rU+o
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1bfde1b9-72ad-477a-a93e-fa92b7f08884',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAvAS58Ef/TZwQ3vO4Xtq/u1OOnuF//j/hsvnwOl4ZCaz3
rTZ3gqs0uCMR3dGwfHjW/5nrNdIBKMWPYxsZwgEmoqiHfjEhwsSiNJqlUso/kAA3
qejXaDNyQIY6ByBXaLuFS0BSybRZQh4tgXURXUNh7Qnkz20hm6AUfpogeMXhCPMy
0K4CnRTq0/NIgWzB6OZPtnOfSAEsP/5O9LfP2tJPyQKUqbUbOIVQ/bBDCC2Zd7T0
wVjS8IzbliCYxk4y6gipa3NOq5LtkQ+qUC+OXuG5Cd6NxeM0bbQE0aKZKVwddt7B
URNdk3o0Q07LtMxloDg5oaycmg238hgkTi4+/aG9akdhiQso5Ac59l8pe1+XbOnP
0GZ66HU5UQqz+yXXfIMZ/ePXgyxdP/78K/8elpjQ4FurQLPBdc8LfnbC/SGupi7m
A0nkOndV1rKRLcvA2+gurd1JlkRdnuNGOhkDjlE/2nDG3sc2t86Q+Zc+/EXXrP1b
jkbne5l94wjiz356bP2G9qYKK0gNJYptZ6+tXKM8fLtPeEb8X5XgqNeYJe1I0za6
e+nUvgJPm4W8iV4rCVMF2E81yJ3g+jrxYQ2Pv+R1FiUnaFxVxukbMFnNi94lYDoa
2MuhXJZ8Fl0BSFqZsf2PxvwoIdKB10e1fD6wVkbI3i69b8gjPXDN8ForoyKJFiLS
QwFV4vAL8FQCOz4LpThai5fiZK2euLgqxzEwbXP0Lnvl9voTgH7koYGfviRgmHEp
MEYLQn6NNMxeQ21rqoKXbJXH0wk=
=gY94
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1e953735-aa92-4b08-a615-ac54a2f9fb79',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+LZYyjuqoyMuzfUxoHxjI+1ujrzIST3H/oF5gjTotw3zE
B1T8h28U3yLX0My1jOmY+bvfu1b4JShHdR/Vte2zFrroJ2wXZHl9bMFgFxuYCvbY
HLp7FuF8AZ48bgBmKDOyES25TaPlv2hwuNqhP31fpYu96Fh4c3G8GH04bEEHjYM0
nWqiblCGBcBq+jfD/+WZFVFJ0qabmc0VIDmqekSqxPw+RrY0uWFJs/W5qWkRmXa2
G5nEQGwZaucZW/Yqc8lFeXrnbTpPgIpSu5viBkacQZOmcLjYhOKDOE0XFnR5r1vC
rxvs/SFIUm/ZMVlOlbPIllz3B0tXuJc5XNzkXNvIjuIR0DjmDnt39vQoZKyqo9uY
X2x5ieU1ZmTnKrVYbEehsSishre4uEJOVZpatPCB3W3XORL0c/EC/z32hMF9vdxE
aLVkFtMb6I7J+ZRcHb/0fV0gkwKGec62iJ60FZcFcWnbuVsi8Fb3JByXQ3cfbnbo
g5RVllccElKTUXF8QrOHr3irNvLKFf5rwUD/8R+o7zEi2n1ty0TKyA/7WOnIiIEN
N+bD08x/FIiXRIvGRLCo9n7kLk0/8a3gc0F0swo1TXdllXOOuTn8hhvo9UeM+b9d
fxAl2Qx2/gwxjPx0Fyk3fboO0blZOC8THqU0zU0JSGu92whPwoVsv+A3VinNW7HS
QwFlHnQ+7MOOwbJwkQqKW/E5m0n6HRzr2tm7tCeCsGVVT3+/ca3wH5vNRv5XzO1t
FuP578yYg2nBXMMB+uCFYZbanf4=
=y4Jl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '200183f0-62de-4126-a1fa-ef2e82ebdd78',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAr2hncGPjaM2EwkEH3/LLpg5z5D9Zto9Q5LKD3o/X51ok
X1weImFhlSHmxrO5AGA/KGJaGzWQ2//vT1ndQ1dY1lB8wviDEqe7jJxT+yfjAtG1
M53R0IpJ4QQGBq4I9yEdeT2bdgcftmfb2cNclBS5CBMuGy26XnGAXLm1J7A4fdt5
6Vy3cwpxguOORQgDfA0911uvXuhILY2ZahOKHRg35b1BizeVwUmJYqxovZ92fOkF
Hhbs8O8I5e+5QqnBSmlRHmcggInrWw/zSafUTCtUcvsyd1O37hHxyHBZ4t6OMpf2
3QvM8/h47JMfwI9abHguwaxAZLAylEzdr7GQapS8nnBF2NgY2ANxny214QKUJK51
/TWbH6k37XoEPBdkz1hjW5yb4HhOU8al1eHNQg7Aq2SUX/L6FfXl0yIaG2n47JCB
pGiOpDN7/ZVOKBYBKvagBKuPvCUcbCiyfwQW4ucicXobEVKYOqq9mvEIl20yoMsn
SjHTMo053IbOet0ZUrnCxjO8w8H5CRf+bmRSmx3KxPV1EjUbnav9jd/LNhpnq/Ru
zcmlVnExqKve/ueF+fy2wfeId1Z0/uBKoobtCiG7FLX1FVXlP2saBwkXGFac0c6o
uqh76WkmCJ1SV3RVC1SIJJS5IMZC1u5uy4dZou50AtpbQvPxBoDHpTnxsGfGgYXS
PgEPrYGmgheVicY9THQR+iX9tSJy5txe1Ee3dAy4iOzPBK1DWUivLbCFanIAzZFv
V6PE6tKwpQicvtoUwmth
=O+Ml
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '23b5d95c-268f-4729-a6a6-bde5ebb260e1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//cXO+DAvaa3kKQDBM/hrNoSfYTDDrLGDybYjzrJp7007T
lyPB9nC4S4ahE2s1uTozDwmqnI64/D7+07sUD5qhS0H/5rT2C+2xsUQwhWfh0EtF
2hW9D8oiMzSKESNG94XGJgwKusTBllkv8MvcOdFd+7Et7q3TveRPF54gMhZSAoE4
tdrYsZniYQfGFmeyNZzXrRCvPsFRoGgU8bgO0kCR4p11X4cIsM0X+e49+1j66Ymr
DJDmgMqtly56dpWbGxFiMXxjnmprKeZgR9/QCLbx85fQeabFZMzgrQnGotT6a1wE
Tys0fsMc4dVCTBRrdoiDTWH+GkkToR3LXWCMJnidscx4gmiuGHCW30pTqo6LF0/G
KtcMQyNJQfRXWlhwpuApGAZDPsjBvzkiXTQZ3dZfX/01KJ8NL7mELZexjJM7FnCT
v426cBF2wku+aY+TnnExu+juuYo0gFmcp501iOr/nfEqJVBLzfErNRAD3guB0p2J
OutvEFt8jQTzsbtjwd8BGS9mhG5L3KUKq2/LzJCD8WAI6Io8AHqWphTKGLm8NSDU
9PBeccORWIyQbi7fi84/gslMht4JRjYzTzVHIWhGCvqFPP9rNIIWidaGhkR42NQJ
3O183DwVa1mYewgxWBp6xhHGOw0tjwFm9K+8mRCX2K/arS4N0BHlk49W61RomHrS
QwGxuvRFGyw5EHdN3VVMtIuJADnGwbxHDf6SBLCqF4ME68hN4kUm23LyVTSOJJ6s
Q7qSFCGAp0w3/r2GCRIJjzXcxL0=
=Rd4d
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2d4098d0-4bc9-40e3-a778-815c50c5c0c4',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//U7IaHF5ojHvpQADoSl1v2Rw3hHfZ4SKGgyE0P7yzpXZE
U078ce+gMVu6wjoZo7UmCLQNPyDRXPc9n+o16OCFPBTTN8MrgL7jMdgIxZ1iLTKa
TQO2CpX9vdralmHCHFkqvMfvMM3TcqDFQrCPbfu3x2d+xNBv+0OsQafAa3rT1+R4
gIE4jIDsNvQe0QhYzVBjIHQqf2NF6gktXoXY3H/iGs9+qtIW32tSNHSjXhbLbbxu
TdFRi0709lKw4Dkfp19/f7uK878zHc3L9dnNECujCfw1++aKyWLTHealmPJqfm58
UAOkIzFtARsc0jJbXc6TjyzFGYshMm0qwPnTKsbvNKZxNswaXkAl5xjlWNi05jvC
bSm6RLtXIeONb7bpZjY6nDQDFRwQIGEb4qwZf1mU7M4GiDaWKC685rWAimDthCT/
QicUb6sFBVUicQXyWx6tPZYcHOHEJxvcPE2f/epNXDH0go8niloCgdWssqamzlUT
MXDNolHAR4bi4/LncpAqpN8g753dakMbNq4xcC5wGKHos0o6Xoucj2kp9beeTDK9
yIdzqDRVWFK6rki0v861lInqIkH6C+FAXfT8Utzg2grl2XuzcQI7UVQATodwDYO5
zzKKGS5+CtjvENxintmd5QA1t5jsHe1HJy4oNr11D8Nn2d+KEVPQMFJ296DbGSDS
QQEnn/poEUBirbtVpCYfx9kDWzv27pZGxIF7TLYJd7h7hmSBZhS0O9+pWI3UJh2W
NtbsoT+1//Zb1J8ZluhbD2gd
=mf3o
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2e5b07b6-eae9-488a-a6c3-5e02678285a9',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//QDGA5pCw1cDJOelsWJW/Ee6ZiYqFQUxBUXhwoxKsiRI5
1Tn/MSPRkV3agUsc7QpOZbKLly69oth6HLBdZUbjHJPT3m1iyoMz3hrnp/D7/Gn1
HVuSYQ7d0pP4/DWOtG7Sv5A/DXfXM31NV8dXBUpn87wwRVU6mPFDo9ke4CJuOPBY
mUYbOz/7H2Z7qReJME7xcUXIwkL/wZebSCofomAXW1WCRh8t64wIO2IpfuTYVUvZ
uBshCQykPZZfHgmCZzuBvXqS0f50v4r2Nm5+HCGW2gwU3OwsNqEfpmo9lRpCMXac
g4Q8hy36MBhDCGXTHT6b1m/OuPtayzlTX8Zoti8cexGpaTqwxZ4VArUQLViCIxOr
TtQ8JEFujlJZW+FJj2JtNZ432cxsecejDCcc3DPhRfJNOtyo1mr/dVJ7mUlqMdPi
iOjbt16meEBNKaoFvqLI8YnJ1RniiojRhCkV5Vo001sxhAMsebrT6q9YftWwRe5J
ZUQVPxg4CZAZeZgdzxE0xjzggeJNDBJa07DP2ZUTN/zDpUqMvrE72d+KK7DXi96J
/HI+yWrv70SFNFmOPSAYmWKHmHWJx+kMl7EA3BMR6aO6D3QdSrnkr0gkcCeTAzQF
SMQDiKRQvldIBSqK9C//b+6SXkuPNtsRHT6hQU87tKQ4Bo47Y4lS6YNI+mIjM2/S
QAFYFVbwWp3r6INNrX+VppIReTzMsOZzxYzFLFICbtdOZM5uGW5YgLPk9ovAO/6/
71ueoudoe6r7/bCR/oBXfMc=
=VNIk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3d64bfe7-a962-4a65-a516-5e452245abae',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+J3NExKNFOUGb8Q6SrRpSv1IsC+AjW2Obwzv0pAC4+iLw
2sfDNw19jW7EX56F3rVtLXF96x4+ZTvTHY8RPnyI4CHGW7Lm7WsiMQ7PEyMnWZAl
tCCNvWws0rQln0hhPaG0y9GKZDC8HQpeVysrvRmz059OJkeK4DZKneCxjqSnCRPt
ETauUEtx+BHEp0Vk1UOc4lfcsakP7F0ANftyRMqSRdDlk+O6sx1c/73pXGziUXhk
MM7wdCaWEpOk6e1kjYO1aO6TDZpRfxIPcx6uNl+IX9o9Htz672IUJp2l26txaSHq
6uTbjzSiSmy9I5qfmHQr+agOowiT55HZI92gn2yKWOhNM+pnlvpUdDW6wszg8Ci+
GrG9L7wScXzAI3JhanK5SUMZLNaqdDhGo/EVuP2mulFScbByVf2hO/y5ESR9/icI
nXn/l4WVczsQSeLVOWZE5gFU/xZRobSNxsjaPQ+UZRMvabu8DSkiygccOHUlV6dX
hciSvOOM+TBXJZMbQIhQB+kM/Htqzxk8lYDxrqwS8qnlOiES3dYM2qMDGYByasup
cAvIziYABfcF8xSZ9XCERz9pkJzwrS/r7gZLnxRbbr4HoU1wKIj9Shy4vhOH+juy
UcuDDP4lqSLH+HPTxPF/t5v0bulZvAlmmvyeedYhJE7a+B22atv7otgCKFMfwdfS
QAEt42YQ5w6H5s8lraNG5AYQi8yshoRXokTZkiVklMeU/PgUgKGRgWhrScu0z7c2
mVdZ/vpVZJabHrD9iyUwTgM=
=KYJh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '43444cec-2119-46ae-a439-a4b064691177',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/6Ay/Y54VEhYveOxAF8cORv2EV6bcrdQyfbvQUhKx6bEm5
0BPlPVVi+D2wNo5h4APY7VRhz3z/QwFj1DWpyoyblfDmEef7ixGjlJkDvKqnNBCY
1OEXBx2/DukgfR+iMOVxKOlqUzXgZLm5kBWubM2wasSnZ6e6jHp+d2rGA64kJ8Vj
PUua4NQKULbSqBrmTdlkw9/vclhkfAmlcZZj3o3mY3UUnipTj+RXcI5ZLEuhk+lz
BnG+ZejicNp+9KjmY++qa0jyUU8coecR2ucr/pYjVL3tiPL4ZWKdr7hVUc/qOKTP
dYfa3J7c++5YzWezC0gkoXj1LNzf+dvIDCBDWnrtGCXup9hBMC4uGs1mM3RCVnoq
+jd4Q5DXa6p7RTmj9f1j3CN8OdBA+O9E/+DofGwXEnCr6QODUaX+gDpl+Q33R9Mr
u/OC0nX8BSDuz4nP6hsK9TULpU+tr1KioeN92aVYyUSFR/PNW3HrK5tq3J36p+sI
RlM978BhRxfc+7eGvfYIaHz7kMZrviWLOmH6Ma6eYJLXJckdoCF639HZ4F2Eqkb6
i0qVpVTTiskn4dvaUCK7JvW8bJkmHDb5WE37WrU8G0D5pWiKgnlErAZcpvLbWhmh
bgFgL1KOUau3sTBjv6Hm2DNNUogzOtNTwCytF7qbk/Ey+nHywmBxD30WffEMXAzS
QQE7azRzqS9ms/sxnzKbyZuJs0QJZrfubhjSa7j6T1X1T3Y+YPUygjalUQecFTVG
bWFtT9btodhFkpWfosG2wuV0
=2NKu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '44f75f3f-60fa-4bd0-ad1f-385dbc36d345',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+KiDf6Gd/Q2s+pIMppViX7F1SvUXgqe7cUiHDQLqQ1I2t
1HumvgnfFsKqKcmbjtlijfcJUzA3JcbbswjZnmedEl+6QGlG2JXTpffWdZBvdWgo
YIouo7LbUV5cqACMypdhKfDWN8pzOoHOQAwPvRoWytebIHhIwxsH/bJEHHoK0G+U
eli81pcOn2ipKkZEDjA3hEIqHb0AdKiM8ti8Zh4gytnxrhZM0858L3rlAGk4dQw1
nJJ6BJkyVcF5Tpveop3d2UPFtTfnrIiQGp/iPNB0E26HDIM9wkKoB2XrLp6mMskj
6aOv2O3HCkaeBQ5jyPL+7X1cFjG9JlX+wkZzRGx5upJ85B37KFVVLeWgpgRPu8Qq
TE154BF5DjYWAZ5Tfq/96QLwp8AK2ZP6H5mSwWClekO2Z+qCaKcGG6YYPyMz21a+
N+2SiGT6M90tbJhjMRge048w2tbdB38rfFDvJP2J2ITqe4OyRNsr7MgDsLSKcTLN
mdcBbFJy7R6SfvKLss/paZFyLAcKJfLLW1FYyuFK4RQNBfnOgwGTW8qFtXvg/C8V
SG0ECq2eWUonBxWu4/FBpquRuBwevorVf1ij49vBl9rCTZqBSoAtGu+B8lWhNRKU
4Jh977joCZMFv3aLKqT0jwVJ1JxvTysVOtF51I6GbjKkVUit4Ly0IUngi6dJGKXS
QQG1Afjk8/0Ao5PWEmZAJ2EXJgZG0RN18lPgZGqbx3B1CyEoSqXWG7jPWaxP/Vh+
Cxeo3kel+Nn8VnvmrLrFE6/v
=5qfF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4a727f8c-37cf-44ba-aab6-9045ac3a3b7a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//ZCRBt8MrdgGsj2sLuA0sxCGuM0YOKVr/W9dPjEue+X+L
vXxrdngGOu2X2FwqVooLueJF6yzYbxrgBcr3Yvg3q9pVyXMQxFpgLQVouHRKGo+H
xsjtsOYvdh6zCikOB++Aq1LLCKh+q0UYhuHKLDkErizzO1K/1CV4FQAI+aiTZtBk
WaA6yYrpbfG/yxAvV2IUIofdqVtJMjtoRDn9nWz8roaJJkuO5txkrNjCqR8BGKqb
FjeJOZA7z2bzCBj/9n8cA1EwCINvPgLjJnzWdLEfWczjwzyGp7XL9jBKStjQGyn0
e7+WaJhgGaISj73Wvvh0zLYH3pTLC3SwbMfqHrncJfg7i58nLvOcsuJJiTfQbQxN
Mr2D4x655c6bs+euiRVLkkJpEty6u/ur3wYjBIZxy7CyqT4Nk7yErpQfUmkRQmz6
QBqCC90leUJvhwApUFoBbaQLcM9HI55kN3kiquuyLIcVEEDAJaSRGSygYPrv8rNl
EiRl13I28vyYtXPnPJvyTVDV6oTeJOGyUkmYpEkJZByHfDesf+hJ642fI9+QMrRO
qz9XPLiPYe12DWepqx15DSvRiVZoOS8FwMrcIAk2k5cQUOxKNpEa5HwY1D2BWqZr
vMDwCnG1c9ATjEUB2k3cIGWdcWP66nn+R4IC/2ZdaLqxY2ZjS49Jvcw/g7hrml3S
QwGVCv1IxyGrOnHy8eY54pZlSSLKkSDy1bfI1wwOrNQGtzvhBijVUJuySjCuZCG5
GN31ebT+o35R+0PpDO2eSylcKk4=
=Efah
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '517122b7-7e8d-463e-ac4b-b2ce0cf8ed36',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9GZpcjZlUmvvEPhep4tFSvS2FnbWz9WtWfSqII8gwjN3R
AOftNOzX5DDnQxDZTEXW5SJ+jfwy4uMldUnvPsXs6/TTUqzV9mdENpUi/4Y/Al/J
4ERZbKJkcvC2qw7nw8FZe+BGq/njDsmVmZ323hwsMwWoA3iJ0yQA3QpCT2wqTLku
67JDDfsafT4JiShtW7BD9FppnXytG+35FQQwKpprJi53f4/Flf2TkhzJdQWwM9tK
1ytHhhsPc/+Vf+XKEeTe+iCJnUIvD5pdK/X39Bk2yDhl5ktGbeCvAUX7zghoT+m7
Vhnt8vicl1wnu837TQGPpd91XT9jHL89VznASEdYPQRg+xir1Rljh3MtyppchyT5
YEMijzdAurRmHS8XXT0HAOgps5VopLBaxmMAuXfSt6yFGndb/dvD/5xOHT0tcn/e
I8GMbZQoeoW1QlW3OQ1blszoqaH4uUYtKHwYx5Bd2jSSyN3slr1O1j4vx7FL7/ws
/MXHQ2gwuT7O11WJcJv2VxR2GZbZdFDmsCJ8Q8zPvXGFo3JimePFgnHEhJ/TFbtD
8a68h7Igjt9gchtPkygBKOoq8xRN5xoN6WMAvdi2+YK7v4y06nHEbf7sleZMM+/8
nQr4ql/8Z13DHikt7LnLPhsY/5vDaeQ6PaypFUT461i4BWkhhhUXRk2mDuTHDz/S
QQF0XWfEOXIvck4yc8QRSUTdZbaUY3iBFidIU431tuC6H9iXBK2TP7R86tba3GkL
SdHpHNGusftk+KCHmM9kz19J
=aiiA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5507a789-129f-474e-aa6c-20914492d3d6',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/bmMnQ93W4OoEdT4Yo0ngLX2u98s0u+ZAwI8IvEYhzh+4
lmTW8lztmvsvIM/5gMvAHj4uof/Wh+kiaUJZgZ9+byseYE9epH6yY5NPHAt3rlT4
SAeoYGBopYK2IrY+SZ33ZrEwqv0Q9D3xb/2uRAtLinEOMs1yKzH4VwGjxQDXHDkg
bW/vYhFATLaX4RimPhxCUhCl/eRPU6opxFYu0WPYdJcHteHkiKFuaqe9GqUBijjV
/uMaJEHMMgD4kWfZ8padaMcCn5LAayUVdKPJQped3lj3ReilzYeCMEpZFl26QCvj
xGP0wd6/VeOmT6tOwpBjNae4TGc5luUOA1u7CpIGINI+AS4PkkqGg8l+rFS28Wa2
aWJM5WZ+X/eGsppQ/lT/vD0wYLQ6atTFvv5gWniEYtiY0Jp25JiC4Sbt8nrKrWE=
=fJZ/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5756321a-a611-45bb-aee7-0ca5b383bbc7',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAnjc8Rxn6uMa5WjIaE/BsSupD0lIpRr0iixjf49tAIheF
Nlt5KLCgpMibkfVwkOMG6ovY+gzfKnXFo2rRfA1PvW7h0K/KjWmQRTyX/No53i6Q
/a6C5dVr2UHHjF7T+xRoCKX6S23ihi+m2CwXYnTRsA4D1ded/Nb9afB6fq4VMpPC
V3Tz2S6muxsM03UWehM+NZHJ+j1nMvHrqKOFPimprF+ZsfmVccDEq54mIgxwGRRh
vgDU6VMV9lH5ldgLI8sqNnzAvx/57G2Cxl/Cw9ARIXoz5FOYE+p26+0YULJN3SWG
kIeYrl2cqbRwKVLmJKwhEwj+R08/9cOxivZ8+weBQ2kdxg4jLF32iX/Fiu0c+R/H
KqR+zCQ6uGjQMiy6zmDftSQQBtOFLfqP+cA3K42Sqyc9pPEcR13B/FebFaMTWwSs
Xo5VPx/0poU19VacJUPYCp7im916UbNMNZDJRtDTAvLcUecIp+M91qXRDbwEmyj1
mbK5lWv24FHkSo9CWEwafpkbijH1FXOk8uhWK1BmjPgI0t+BF2Q8rqsF5VoBCxUe
DuztVKGHsioI5IgVN95k8bxjFtbGR41CXbcbeVtyNyk51p3bvKKcrw4okJceIrf8
KdyOqjrpuZL1xlNRKSh6xn6pRH0DhbkWIo+thkwZaipwGhT6oiSuv7EqvLiJF/zS
QwHB0C41hu+zODzPb5rdenjPt0R/T2opF+qwD6y0NVuS2eMmL0Y4iZN9x6pYCrDU
HdOo2M8ZT691RpTITP9Hr6a/fCs=
=nOKU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5b09e05b-0c6b-48cc-a2e6-664d6b7c0c35',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//fRtzg/Z7WmBVed0oCZJANrPaMurz4NuluZOA+3+hkSv2
uhaACgn4qlPtZrvauO4GTrfb2/K+elvGH3UGH3e0xbLzqUGCf5HPtUTrHwhUn8D/
hOZWQ6NK+35bvw5pjP1DYlcPwWGnBXTMTzVQ9R64SHHcIDo3CcDZQmQXJ0B3Lf6E
N9D16zLU6tBELnbJ+VNZUlqOCiI0dKk5xHpUiM01BIahndR+uUi/eRv+hC5wiw22
cMsVkELGzdm83x6NzN+TldX+NA0tKkRrl/roVmaimLTrtgMNAn5U4/DJeApz+mt2
ugAT8k1QOsbeuponwG4CqVgaW3KpAb+IqIjBlFEQELP5b4w7VJAMD7y4OAFd0DRG
alHZRTVkC5SS7D49oeqnshiboNt82Z5KXqK2PzV1G+zGtku/ouGwoUM3con2jR//
VxpXIW28ObxyNQVgvAzDbj2BrnlsZ+GbbxWIbo9r6VW8PQGGNWbgYsbM/mq8a+oF
ehNRtAwZTncCXSF3HmQn4Xc6rtc81u8WidxrPKwLHMjHE72CyA3EsnuLqKGbpdae
piH5MY/3tjvRbtYQ0VdUOxE57Rlt2AMkJkJPJrPNEQ7//EohCPq7WK/IJ7OWy+Y+
9BoiemLqQIRpAhk/XgKlbbEFXTykmmPTChHdgX/N6QnOucx/c/s5ke9QTTgqYRbS
PgFphZ5OJv57cSa8r9KXB6RaGgaL7hRoHwCTbYY8Y2n1ogCc/Lqx/PBoSWNrhu9/
yLTD15QauXgKyscT4VQq
=YJtA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5c610444-74c2-4d4d-a24a-1e3c8f43b43d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAApw9mnTArExn4c/rY7wm536KkvAe9mqQvR5TaS8ckJg+S
Vwgo0ZHDEZO2hr6e/dbHjTU80T4n3+FWOf2qwJDHsC/Af6M328dmmhvn2DSN9FEZ
UeRPUX7JYXFJk80WmNpqdslX8XK1XZkmMxs3/kabP9WElUmyqLbs+w8i/hq3ldBQ
1DE80Pa7ZvC+FIbWPyN7ZavzSpzQWUYtJsAc9Evif5RvndDadVdH9xxySN0CbuG+
EU3ed5AkZ1hVDebNtOuYVSmAoHtX8/b25QA4vMLIHciMuUezZs0FmtGuDJ6Cuo5A
dneXfQDqf2yo16xe7rTq3caqzD0NRbh7aKcbRUhz3JMaIs3HUvQ+0vK7YS5FMGLi
Fn86o8pY2g7rX3DWtBTnlGYMkt/70it8BfGS7IcsMWNtnb9SdGfskWT8Aw3Kbpea
LTMLjUmVUeg1TtJsbqQ4ZgEDPc8jW2KAYzAgaPaZ2ETnEyet4x7D2U6yMM77sGL2
WKFpmZe/CHhg0IlxsgUx0gpa874YRSq3QDXNSCXN69kVPWPz5dGmkhQm5iVihbZL
zG3+wGXwoJBm4YtsPgF8dLssY0WMzncALbJsb3+ihihttU1Kgc5ogejPxw1+QdMR
2vbwvDPGpRgRB+t6Irv4Jm4BhRlAaLLy/00jgtNTRokQJySdUXMnWMrnuUuMF/fS
QgHj0WE+PsPlTu1Bl8mPRi02pUBpS1fgEmQnAtqwPv/Bsa/gbO1mvwWZbt/fds1T
PjzGPsZAptruU+YSsIWd7p3OxQ==
=MUbX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '624e473d-2740-4678-ab31-16ae9e271580',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAkPDKOMSwcF99BoBPULbqXnzt2XmWF5XfSE0bbPbxsNjW
QGZxQ7ySEtbDta9/ExlBevWmznTqGzEpdWwN5AY0//7NZXyklMWAf4T79DBD+qea
pzO7/AcgBg1XXIdHTHD4dVmFa7RGnHhofXtimhNV8RQdJQcnRGzugnF7Qbpi63gU
1CT/E30hv8Ecq+9fp/fgWjWj1k/Y8jwpIs2YK9aa8LszvIer5awJFUWgfRSvIfSc
Vw9WuFO8H71UYTA9svnurnDdfurk+x5f4NDNJrLoTdmiQfzD1r8UL78hJBoSL/4K
STsKCV6+5Juv5CDZG93uu/rHa3SHNct0nxXuItfvLtJAAcuBVpT1yec6xPg9ne8a
ZwJWWHSGWwKfcdG08oZwOOvyRzFrGrHzTbeeEPyisKVqwV77vdtWnMr9fgXKxmOU
PA==
=MI2V
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '646db08a-2e5d-4ace-a47e-ff8d5c42d6f4',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAqbbKOxrEkqJ/q2fXZqne4JWA+ruEnqHjOHWFZsirZmWw
StHHQFtP1P3tm73GsxjV7uQwTR4z0AYbKmNlWBAqUniJ/VMUjn0WlND3Pv5gQj9F
B9t60kzI4zdeFfxxvxrXyxDfOuqyZlW9ffZZZiWy3/dKRl+7HWqlWvYZn3yWZ40e
dneNKfLdX8b1pBUCnLXd7y7PgrXIg+5MybqgpwYt4O6oISgpnrXhF/7p5HNfmO/r
DrhAN8Ebp/xXKpGySH5lwey5UVUpW4ywKwJz70sYgTspFnJpnT02rnPIxu53a7UQ
Z2pAfUEJyR1028+oXbPUzlCkWQteQ6ZYZc4Oyv2GYtJCAW0u1myneKh7N5cCNZB8
nvpxIwHjW+kpysG+vYV4Egibl7ZJcEQZhVOK96JiXV8zvCP1Po7AD6xcbEM9WAhx
AI7v
=pqhe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '67abd483-1f69-4858-aab5-aa6361fb8a4e',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+Ljn8KwsFjlMn896Y5W4NaF9PRJ2Dv4FlcpVG/i5qf+Bw
SSiRvEfic7Nlo0WsXqC6zkJChx/MZQmWSbkQZ/di47fU3kMckTtTxI5aFnuKlJIR
UAISdjmdIgeoeZ7Dm41ruuh9614H9+NbzStYJlYipaSjTUh56VV7qh6DvVxrhA5C
2G6StRZWktIIj6E/X5yvje426qAnmHMvhEpwkujex9GwNmBQUTq6A/RYLgvBRIMv
vgp0lf8tmunLKJsPqOarbxxUNwgXIRVaIIe+KQjvHn4aYBV2weCNUtpF3jWGBLpq
9jxcNtI251yVrcpf2uUOKcvkTzsNnBa/CXKptd4Z55rp8zyBYqDGX2lEcsc9+Dqj
zxOTgtc+Y9mUv6Dr4F2Ui3tpgDHBVnM00BL8vwd6PG+Fi6zdBOHLcC93fIFZQGoV
AQ9NlogfY3yUC5Eg38Vx8RvO3yWPKcjZiAX+ejVu/4lTGkOtoEqqZ3W7tCEvISiG
NTTZWM6xf+MXrMxjvmwp5fkozV3f8xKiZU65yQnOn8/VHMD2mzC+lWCuusaqNKXT
lBFnQ2+IS3sva1ZVvVAFot6KFQdRi6eBzkj6cAguhXi2waiFsyYLdvIAB9Nq+Rpj
YqTHD5sIly4AwcXDFgVgHaHD2cu8opd9phijKj0h/cH7UFruOR+C3nWMbMtDmg3S
QwGn+gPSxxw2Oll5UiniHVVPBBJ05WNrNw+vvpu9NtgziNQmHfoboa/xc6yjD0VP
y7uSNOEEl/T1Fm6d92LOfu3ajK0=
=9S38
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '78a7c098-41e6-4373-af9e-f04970d6dd6f',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAjkzDuOwhz8WDOf4Or84rRqY/dbl5Ewm2y63ISV0L00BE
DbXP3AuVHgRxHhY67lAB1Jzk35ijeQjII+vBFyc8+JO4gV8J/hSMhoCUNaIrGpzl
1fTT3r79CX7au6UNzACikWfKRLW6f+Ur8UVZpOHsMRSLi2zsUaCW+6+WDCorLiwf
LjTAh2Ez53/ACh/RO0PYDhSm6aR/5tXVhrt9H2dCPHHLl1PYbFYerksR+ybKk9nM
evdXOSv+KI8OQietkgsW0lVXaRx1+PvJIQry8UefVWvyC1mlHAwY/OU8bO3zYXII
drTXmAN+GVPB4h6psdO4LY8LGTSuOxYDpWgW0vg8GdJAAdeEFmf8DdcmBANMwGX1
t0AZLvBQGqZvEy3HA076uoHcV8GWoyx/aVRz+8enHm9seyT8gPOJcDOzpcydyCy0
mA==
=AW6p
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7be9fc03-fd2a-4696-aeed-0a9b27ef4b9e',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9HVOQIYl1/S0xVNJ6mB0iKqMG2U2xEFyX4WJ0mbS4z/6J
uHEJBT6n+y0bjMCyOGFpwmK1pJXclu1Jm5SiLUvJ9FvS5Ats4V73CiUjQGMqP5Wx
V9Kcz31IB0W1RYrEZCLK1yPTyZT0UyqzMGzCNLgED14cqNh2jz71/geQgkuDHuTj
vbjuQuLq7/+yEoDMLvAZZhuM4jC2qrmH+4H2mfvxYPQiquZqptvY5Qs0v+8lqHWr
FbRGyY7pTJBs5dp6HhFR1bW5TLdozGTz+u2QF404QUmfKWsduIy9IVTJwnWOtPhI
hYreeOLJJc83Cjt0IqXyW41DN/rWTHUyfzksBSu0Cx0QXJY1aFTtgwIE3hDkGn07
LoQ5qijLeFo3GXFXBsiRCQ/Iy7E+pHqVe2/lu2Z/1d8F/zZWQ209D02SWNQPe5tF
lI9VawbImrYy16+/tgxRgp4Zp8kSyktrBImNKipi5XaRoh2PT8XGILEcSbakzPHO
48jrHJAGEDpHrJIBII8+veupCzqBQMHOjO++71DzPjPLFePsj9VHCtNb9twGhlcj
DDvBaiNmLAj0cowfgodM7ymy5M317BVCk70uEgEJG4gHFahrIPBJC5iiJbYGwB1k
PTWYee+Fhyfy7BpTo5yXIWbwKqb+4Ke5fjVQTgKA4cZ6zX4ehVgYS7x4fVV23BXS
QwHYWlDihoyuh4VQx2A+XW/ymkEUrTNtsJCtDPl/rglmAx9YQpRpn3XKldW6VlEK
qIu6q4uhXqqO2OaXEXzHEYSMSnA=
=kBkk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '859263f4-aa7c-4839-a15a-be06a202e597',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9ERXkXloMeOVePUiCFqc5ehzzlWc29UypCt0+Tt1moY6M
T7bdQLLntQW4p2LWMgCFDqb2FoFzmzQ2GSiPomxyfNHG4RDCZ73vS+Ypgi24REVF
MUl7mz0WaM+E2C0Y2EAVLa8NUShicRnaCAYOtw6qL4pMR6GcK0fofi+vdIWgj9Kx
Y4/O+1FTV1foFJTFCwthLNq+UXhhoy9fZ7a0VSro6AzuXZwr/gfdBqk0qGCar5pa
8xETuBurx6ZjxWvaEImeIfigaeyrpTjv9PBJRS2Nqo/XELr6ev5J/aS7aLuq97sI
TPSC69CGxIIQUxw9yOhEdhKAF80c9upKr/jY714rzi1YAVAfoA9JYXXwIZk5oagu
uxOF2HmrtSfVaiHxLMezoxFIm5tKswHAYaEr00BwB6AtR/MRPYDjDMvvdxDxbrZZ
WJ/AII57vI/uUc1JsWH2U60b11rwipfvrJW9vygnTbKxGH1KpMxVGzyGdi2SjNAQ
DzQlr7js7ReqmB9uh0uVGwGHCRq40K0UWZbwkliYq8Tp6kInek1tnfMfdqUSggwx
dxZyOgmsVXa6qivAxER6uGVa1RwV7Jug+g1c3Vjef8GDBw2L4g8UQlBI1JRwe6bg
1ec4J+vXLyGAdLE05V6WRyAtMdg+IkzAGB8eEIqowi++9Vc+M4Vurr4ei1emCpTS
QQG1+1wIhJEa/BO98WTqkwiVihad2N1wu2r1vofskLyjtKk2XzuDaiMdvUUjohya
/EORxFbo+ExYIupyC2jZn8jx
=Pnxu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '86bd939b-f643-4cf2-a831-0bdc3b464b6d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//XZB5Hw8JeFhBSMyAHQ7iSAVtmgdGvjy97K4kskXvwPSO
gclfLMGTD55GQ0AbncuNtAxTA5i5TMlwbHCxPLMfJN5QFvm6Q8n926VjFpE5p89u
Egg++Y9QJhPWBCIjhqp+eAq5YbK1jXibYK/rcHdcWupPM0+zWqSR8J6mVqJV7DHB
OAZRB6YdbJDPkSljNhPwuXsYx1MVDayD1qbBeMWqQ3H3HXwrZmFvJOcLb9D4RxUt
4aNA/cHlsoeoc9rfZtCc0PUcGGa2h1HzT31tGiR0Jj6uXVL5WUL4zhoZzZtZ5Bjg
mgFMf8H6+hBDpIdC6FVaDX91UUe2SiraCKybiLalO5arnYydjiiGzqWuytTnMJsr
Cv9YTWjz8KtZfosRN3okYByXWXLgpEy9S5zXpiZdtqgrwunh0mu5NZOBEcjVTz6n
GvemRbkNwof3QhQB8U0rgXrH5xO9nSOmUlJY6qBlGw62fpC3+h438TLjxEzl2uaC
2NdRSXflk9VRrWjoI7LXvN0ufjGaQiVqtg/47b3sZrYyqvl3IKpqNfWM+/Cvlq2D
c1PgxyXZnUQoI5XZk9314/RBlm1upmj+MBGd5uvRsTtnwMwqD0n771gUn9we227S
mtv+6vZs+UkbR1h5cTUl9C4OXnkv2r3jBJkhSTmjKZNJ8P3mjTXY2Z65N2pDK2PS
QQEbXcqGAFXc5HfGqcYaSpf/ZyXK97mriipD6j/SQyUx2El+cMmIDvSTsy7QnA1e
wd9rYyYvCJM79i2y87WUGsJ6
=Lx6y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '89a1c186-36e9-458d-ac48-1778702c1162',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAArPtusFwv5cM02AZHSWrQEVg22FdeaGrI6p0/YUVRJU/l
XPwe89rG2u5wSDNHkBkt19k1DW/aaBI2l4u0fn/JKuAG2eBjGKH7S5NRy+X3dpas
HyLe1lso7U+qU67tqjLLApHUpiFQCvGSLVP0CwIhu8yhvcQtNFjjkJp8XrKIuZva
rkVNrKPz853CQKpNOE0Nr+46Z+GfRvkncM6lqnfz1KXC885RFGUuP5YGXBEw6MUL
u8uzXx7oIYLJSNtl3AU1ksUkEddfbvVfWOqSi6dC40HFNnsZJyGfAAoF6Pfo1+e6
zw9UqtZO66FPeogexoHnokzVgV2KBVIv6NgpSXjns8vowppCh+nsmr/jp+h1nJHk
s46kN7amePy+wKcIpfowCJMggOMcsU37r9HXoQw41CrF3kQCDJzfyHVp9MYPn2Sf
NoItz6X1+K2CePsP15zLcykwpzefGfHt3sIGoyhHXkPLViM/dMjntzMlNDi063ls
zF2EId3i1rYu7enC0I9ZwxasdPMy6vXiL3FU4dv6FwsIYoq9f2FUUSwrBBzd/Shx
SkrjVUW00HVxx4mSSxOc0tjmsap2EhESJEIUNfxHplOpYp6aEv+QyGmvEKe/r4sO
6GeNpQpdBUCQQKWbL6LlAnzejNoIoXtRGzwARrZusUeAW641ghm3gm+E98UcNNPS
PgENDvm1QCQi3DqUWw6IXsKizVQ9mrw86gWFDX+a9KxOGJYQfUeUSpQI3DR9HOhn
x/bi37AsgxhUMqJe7THU
=UeKO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '92b92544-d5eb-4ac7-a246-802554435415',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//RGhi/Px1nJz9GNbxhcSZS8LIiXEcfdkS/LBz9msDFHQF
N6C0Yrp4YSZC7DQ4fHJXYLKsSx3Pd44KMdS98mlwo2NL9FNhHaAmQ+THK8wbFUhm
1ej8s8ksX0T3xX3Ul6bJCG3u8ux3lvgUjKvLnQ1mb2XJkg0oyFXHRyv0kiaz4W1q
PEsCGt7kyJiYMlsjFzLOjE1FVuHaPU5hFrd0kDE1P5fjuX9PhCXm+MtU/467ntop
a2gkoE1bk/o9gIesz9Vwd+joE7QUuitm3kzInJnt4z0V8H6ykv8NPfeAqYPdAekM
0i1Hkj02lDud5e6coNGkr7RFXHZXIo2bum7D+p79+ssGAlzMyAqSFznQfBkaOByd
T1nxq67UDz1cIY3ZFxDu1nT8LBE3jjnNamfmhxgnf8QLCNSXU/tv9faU2l+RhEaq
XZsv+9f9sz8dpWKFS7bWSbT4jnCe+SM0qugdSJkqhfg8v2mlkDEhz8LUkdTQSYxg
6J0yf9CnGozPPdl7Swf2Oy75r2c98s1TuaOD2ZW3B6YESaQS+PVbLDbZB1YuXePO
LCtC/L0cV3mjV3yB3jrlBUnL5yPC+PhZHDqGcYG8HAu8DDdOL4K55mYKZNV00dze
9ARyVdGrZQa2Wg+NQoQGQ81IRW7938XMZNgC1nF6j9YQ1jCIy8ddDdxLaMgv3s7S
QQEdN9Wxb2u0P+Q3slmiWhjXFv6oQNcufxo020jA79gzI851TM4/Zk2OKvZBY5ZZ
/ZZO3yiwjILom2h04CJIA0To
=1J4v
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '944e1363-8f80-4621-a816-8a1d50b9a744',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+O7slbGLnAUKJgpoAHMYqHoEqTMWTWUBSQjFbGg/zlXr4
d80nQsYFyLNVKOSATJqE29WMLI+fUIE+plaWUJO4eaQwSLscaNKYo611HApIosOn
3vcUKjdGWp3F4/CxvtpVMZ1SFC1ApohdtJTW1JP+SmSm7wo0LToCfyr8KlzRDKc1
OD1TOEfrWFBAkWnAoZsqrx4ITQAbElLhQawzfXKPFrnOIabr07ybDVjf5HHtaN5s
UGeNgCOAyU65ii/BPhHseamPeLKIpl00w26nzfPiITvfmUng47cDdLz7DAf8k8wl
CeV9hhvviHzUxVJe3rlA1D6QUb7Z54vi5yxOxAg6ztJBAW+3emHUU3oF9XNbndh6
YT1x7fIA/EDCZiAwdiaAa31v1vdjsSy7FNfAXDf8ZRKAszIei1aR1t/Hjzcvej6Q
QgE=
=jtT1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '984ef049-7017-48f8-a011-f16824a71a7a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9FWKYyHuJ2S2jgHRrxs1xvgSQ393g2sGUhZpIQkflb/07
W1jzYiMlbZbe0/1cOLCQE7kns7p8pxjhjv7D9Uy6C9byzzv6enLvWd4mEOEeDB/p
LSmZFVouIQxBdOEyecIZHyl0XV5sIKw/vRGvPp4M7P4YaFxlLH84BnW8/BZvEmud
CQEX3ApoQTC6OFZtntvC6XBwu3SeYIOWVDqAByXFawGi6jgf4ffQ4GzuEE5fiwey
/e3udbp+z/wEjSCp3SFTgPtQl8rpgi28p5w8vImO9c90MmbYmWiecwcgcfgrtg8+
u5AXU+pznOkWZwtZXkd+MCJUCzLLhCtX6rzw8lK4hfmcmIwPHBXxm3NhXEJ0xsIF
5enaatLTvYCaFaQEFX7x1LheHPvAqKl69s3bKEESKlvIiNnD7K1lOgAXa6t/T+DW
BU3f4SNzH4HOOTFuFMMFmHiLNAflZjgQE+AG4qi65abkwFV/itmNrS4xWME1aTdZ
yesxUglAMYM7pFCGKlybYxURkolrQIQWfQGwq2CvqPPHr2HimyJ3p6UbnfhFRFws
Y//GyXvAfII1nb7inlWjOOiCEhoH76QOwwDKpLeNhHyAmWXkaZng8AWXzt2v2SyD
KWhwF1D2GtZUT5IVa0Tyi1mBCO4Z4AtLyaj0NklErFnpjfNQAd7arb/jQBA7FGHS
QwGI4utRre8l2ZDx3Ew7z8Uxj/P3FxWPHKDbgO42y7dw5MjLfrCXw+aD0bXgUFeL
0FqgfeRgUlKrwuxhxH5savYu2KE=
=djNS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '98e83d81-f6bd-49ef-aa9e-ac9f9bae75d4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8DXePnzDD9r1dSHghV5YtyTQDKxNCPPGuNIAMcxfIHoCs
JI92WaxK/Gzul3jqFvoXBZJ8mh2JxnLnkrtRL/MkvPgOqvm5uNAzZyY3tQwaC0oB
PEU4lO7k6e+vrP5ivXSYNi3K5mnheBCHRN4jexK4nqkt+njSu8F5olN7jxYR7aSK
8rYs3PYfemaw2gVM7NwUGdoWN8x1uSRhl2Nsvt4guI3nLUl+TWEn5mdRoFwt/1Os
NAUI3S5Ifsi35xLFtqrWLfbVaN+cZFIlSPjBqPpSLXSXogvNvEpR7RnQu9bQt8lU
rlIYjdG3snHS1hT7JWDx8pbdw6vRPS+o+TQrRgtFzDQpvQTWrZs0E786vDC8MRqu
juVuBFNWURzstyaoBCX5SVA7xEjLcA+kBLsCjeij1M5iW3opmImIm8udXHiygN0b
YRzxa7kwBp8LpOPTGRXJe+tuuycFTWY+/bR4Rh/aNUMGTGuqweJIZfXc2U5o68l3
qXCUfdxrqbew/aiNt7YyiQgwnlkP9WOqGFSkhI6j4bj+U9vnwfeJdVh1xl7iU9O4
90oqvbXztA/XldOaRHsyFm/1QYO6454laIgcpLtuPXVeUcdJ/oPZEgwq40QJB7Gz
+OebYK2N3ODUtJM73ZMUUNPijNglZ7lj0dxlHfs0SQBRvPYWiIRGDI//xf9tgwPS
QQExz0h1XMUQVSvjVtIZN5d4AcrTBC8xwX02B2zb2AHH+6MX2UjH+CTEHL096daV
RXVEYe1o++23h4OgB/zemcmD
=v1UW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9c9de156-5ef4-477e-a326-1b85568a7926',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8DaK6F/kLLAgpe8ZHVI1iPf1fue02pKKdne/pB3Yw7MkU
1rr3o6KUPf8WgVtBDHPSoF2vbokzwy/8EvkJyF3ISqTWBzH6V4D+JO1kkDtPQ9PB
ybsna9xLVCEKrJ8vvQGjeUaZmY7NOr64UIWhGhlXe0vh37o8PIxzQutzakSKpHqb
hdt6NjWn4foMyi8Oy2rA1I0O5LRiwZsclgweqYn/wFEIR801Irk2uNMfirR1olyI
B7t2EPLLcNARcALib4S/jgf3wJonMnCWsye4srWcBp27TrI0TIMfYwLXlPbr0Pbt
GqW3+Rdgwf4kZfYWhJ0qM1cAXOb3czZgeiBiyIXTx0xl8Z/MJn7xSrDxJ6VMdDLe
vl22cVEF5R/uzUiPEbNjsg0n2CLq/LwdIRFTa/GBFnpIPD6R0oMA7c6aNv8HxrEP
ZOkKHjfeYWXb5NeemimZL8/cSFdfZFN5sd5q2KTlY0gKMRWtdq+pmiI1YINY2gum
hnS4Kn7BWBtjqs5Km8uFk+emjSxB+P/bEWmD6hZXRUg9YwydIeHwQsJE1dXS+9+K
J1v2QVKgtTwXoqzyOofmKS+Azk19P00P/Ia6VozwUPvUbP+lGkfpCQeo44Ihr+4v
Rr38wYzOh2+ZprQaZTM5r/DPO+tM5kLgLsxoWyNAqWX0paqTM6qIau0fvpNjGi3S
QAEEmlDeCPxmVC1Om7iHpfrfc6EKh8i5HkRMleG85ml+wO81MGpHPj0yKYHHiagb
Zy1GdAJIiPIzCA7sxuw+omo=
=McAg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9e33a40e-9afb-4aa1-aaec-6b004d2d47e1',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAlvSdbKsBA7uu2th3ky+MAAUyU1YI4cPunjJJ365MYa5o
2EURa5vEfjZ2p32pt/mj1q5vzcdj1nN4n0uYtX9oatmuJms0sbRl6WWDxA05A7lt
opllTpFyutTz1GEnajoOo7VxiENcQcOLDCXgXO7Ej6eA7EnUMPWLG4eFiZvrhNll
Ppctj+a8hY1ASqPPoAWlRYtrlakPczxP46E6kSclus0NzwpiEddvY3gjuRJs3FzT
/KaL/tqA7EA/3kCMIC0S0rbAmzc+Ld/hJYLFvQbyUy+WEKYCcyuIlsPZUHV8Mqhf
ath5bz2psuBSDu+p5WzEqzySNO40fSz3iBsCeSiZO9JDARpn87SZQQ01Fzgcllkt
Wntrz+3lScH5HnHdQJU3dqVd3hLweyMuPlKA7N1Z2Mi9d2A9KzFW/0N1ZL0u6sHD
SzqR+g==
=qI9q
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a57ce783-27e7-4fc1-a440-edbde14df45b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+LzGF2rgjuTez4ni+bmzk1R5wD3fZuRvBnpdQNvnl0RTi
7uLnfqNuNp7zcUHytHrhdr5615t2tGbZ95CM5WxTjg7cYpHn6zfIcvmmcmbATLWg
WDu15ynE7EG+sVGGlgR+3BNxT5ZfpgehGtlz1CB94y1KmlPVS05+k0AegczsK1DG
vj0VBLynKaF4cE4POOYYuVzJlyswgB4qwNV4CskPBjvdPbzHDYgbQCSvB61rHn3w
LNJfN9aXBF6TZO4dewtQIT2URLCsFuwndWFcGeaIgIk4l9PtEmGRizQo2yHluVKN
lQJVceyd4QqB6rWIkFX0JxGk+V4boWAj12K3gFkBqny1zbg06uJmxwyyK0HXgbfE
zvexgGXWw9YmQAR1f2CEg5bZYImIDAKm1f9J+gMaVVg/qPR4aoypk2c7uNPRY5hM
V26H5Pgsrrgj6Jqp1iTwmlLSfywYBEhYT6p7qiHlxuQzg6GmbhKd2g72JeGnT/Rv
gOIEEaGl4wGp+feMv8H4hW1qf8LKdMP0jONNQtCNUWkPRi3t7BI06w9ZlhIoGa6w
E5ATegDFrYvMDuAIN0lJxxp1oSVH5a2/E6LmPcJzJMZPKn/2jWaVDDdP104Fh3/+
5sgS65gi1U59Au1uKay6nUnhVhdaxxCqcdirDnho606SBES94MlpgDyFXhoxpJ3S
QgGe13kfBNZDSXb53yCv/JXBX4OYGKqRFfSygygec/wqZb+ktK6eurhgeV7v9eCN
Oal2qp/Sgcpk9bsufTLG57jb/Q==
=EEkC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a701a6bd-4f10-4e8c-a2dc-bd58e3cb9efe',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Z7KS5BL5rj0wTIKw4LlSgg8XWQfxxOWYzzVb+6mZusvq
Q1Kse6jVWcjLvL1gZB8AtbYMLd7Eec5w1u/OJKFzBk6RHb89/X+lOu9OxZhb/9jg
gU1IpAZO0f8HcsWTqyS9cLdGOjAx8L9pmsfQz1MnCnKXn6VsATpdenFf0paCb0qC
cmXpm2q65QQnlDkIvtJOkoLhWwHqQIf2iEUXEGohVhrvCxQbUZ2Uf/0DV2Bom/D7
fHQAVALZWgFjJdH4GAtcFB91c2r+/ePeYdVU1JSjMmty7fddjJFzrKhaq7V/Z7nG
lowB6GIGPMwSQ4+Vx7bwdeQWeUMMcw0yVXBc+f5lbIV28Ff3bJB/W/Mf2gA3huqf
FnDYG1vhMhVKX5Z2A2szcbD/6jzgHpJSmCVkVxeL26qfgpgtT78QwGYFvJdBP0Jm
o+d0yQQvNt62gXfYok1EOeXhIrQdZR5zoLSJARyVXEpioS83o8k0NlaGfwWcEvqw
Tj0+u1VlZkwMKJ+P7wvVKGqlYBt+n4rEAgeGdw0W/C8EEOUXhzvxEUwndwmTA4GH
ASgEsaOcYEyQ+tuH+BCIRD/8BKLQxN9Gx63PkTRwwtokVpR3pUsGQRc6JIYr++Fb
0NAL/1H5ZcnSgkNF/HMq3IOYtXcdzeJVaUTye8cRs2ChugzQIa2elLD+zFsxwe7S
QwE9mkoqE5Q3SUC5wNqbyB3JUkSNn9wzL8kkiScwab2/tjlXEK+VH81YEnEtB6ix
Rn8aGZ9w9u8rVprbr0zwSrWIxkM=
=WSiW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'abb51271-4635-4e94-a6c1-1473732ec5e0',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAlTKb60OO0/bYZVurXIFoS5fnhvzoXx1q6KsRbaMEa+Jw
8L88WXSBh1BVf4TLpFNSoRv+l+HETQaFNohf7/sz6feKwzs7kLlZkg5I4XB3WyE7
3wpyIYh7TgylTdKg00BmBMOxnEMU3B5w6kwOzX0ytai3zYtw2cYajLA6D6mgyeXH
EEKNb46wqrxmtvjZsHzb3PH/Ok0ZdItUYulWHxnBKXDAxRJF1ORv7kQcV7DvPpf+
7QBwC7L8mgFyC2j3djdm7hTrM8J6Ur3Llx7UmerTrvhDSnhJaxI+FmFAVCfkrkjr
qONQj6jR8WfIk82gDS83njnvz3Gxnm8tc5+pFLsH8xPdksgxYGhuvC5my+6Wmx2C
mF4dvprnMqQIgs0YIuyzDIie0DrT/EIu3nuk4ofuBOUP5o9J1oZ+iLRcLw6QncNC
x7u0V4twR0FEPZnmRFDJuV7iS2mQacVJmIXx21vr/oEt974iyxjxiFc1lYjREpPM
FRSSWt0yw5VDKOEDphp8/FWSpsPGi1B1/xEAskaMjhq4OzsyZGkj6ePjRs28B+9V
KWiHItBz8my9ndeqFmwIsRly7bD7eZauswzUMNetQnmf9hchJGGqoDFr+0xGCjOY
22aCqRffRFgH9jxAJqpftVqrjCr0bAEusaHPsn2I0q5nSHcacvTgL0+ThKwTOw/S
QQGe2H2v4j2X0QDk8cP5uFjbbbZGHom3LXITZtWEcJNRmj8jTNdrl+EUXpigkJod
4h8Cke8YBQiO2Do5/W7mx7Ck
=gWdH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ad2cd3f7-03eb-4ba7-a03d-3fdd6d8387b5',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//f025JaiqFw5mjcayrAEvtKlvOe2rAFV0ZkvSDEXPu7M7
6/NjUHRydl2d0HkO8aQv9HRMszPNOscuAFsXvU0tv5a/B9z/zUrZ2qRmAHM7pMGp
bQ4PLEXN0B8hO21nSV4Oubfw2rN+EKVUOSFiZjhbWKEBFADMJLOppvHuhWEcaR1y
o539Zd3eK+gc4c4UbtBk68Rw7BK3Nh6OvOS71J+DsrBfADCn0rfMjAvWLQVU5TBu
oPAD0Grw6+Lg1GJpgcwB38Cz27RUZCi+X2RQm2bS8cPirLOsCHf46wjABNI7iE8s
mFzt3OqM5ZEeS4qeajQlPNpSSKXkh9c07XXLQ8p4WnubgJRAfBLcZNLXdkzYIVrj
7aSOtJlcpPnaKooQK1gKHcfWF12OtmcruZJtmr+mEXkDMKgw4HsBzT1jOKF/A81l
sBgi4aiGgO18iJBJFRL4+P/1KnnDbkgydL0l0VtyzQhQ+8LGTDz7R789xtzd+XS8
K6celfjMTp/u5XTrzZew0MO1BcLzg1OhgTz75c83Ozkt+wfnrqBaDJXdzVw26Izl
5CSZYEMJqboGF2o9eR3CsPokJBmFtOfISEepIFcS1JurJEyoDQcZUOteonUWmTAo
WbG4ARN5VJyEzMKMDnGVf1Mi/gmOJNzSSBY0jvjCsQMyRHcbV2NnD2SbdQXiAivS
QwFZV29Nv65vs+qEFEBqPIwkW6BwBmyZbSAIWCzzEMFoKpRCX2snxJnTYfpcrTKA
jWt4Se6MhPNZQMrfl6dO6eSb4aM=
=k4Ry
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af7f45e5-d67c-4a30-a3b2-760a9af03a0f',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//YGg922kF89WWz26ADKqz7efryCwraO+uFbLT6h+H6IZO
ShDOHDd5vJoQmwMyvsByiphQW5iLr9STRLxENmnLcIFeFDLhFbMb+K+PDn2mqbX3
9Vy8YFJWDLEzD4I2rbgu5b2E5mlTaax0/g/1waGBpDnp7q+/ZFTaXtKGSF/y1sH1
vCA6dFfNbPsS2OpI5MxAg8ZDDGQ5CZYK0xhi86ErlCattdxGfRuzp2rYB7NI6qIv
UP5HMSRDIJI4wTv1sIot/U53pS5KWbwhYxx86zG2h9Mpms0O+UXwwCeWGN0gEQ2b
tG4kyc6msSf/7rofnoYhhtbl2zEYbpVMgHaoz+Pyng1yr0IRQpEo5uJo2RZyNQP0
NIYgJMSVo5h7i7SEgXd+1FOrJHXdcMAGGW7oOFHXhL0nVxyzKPSyjPzdUrHBoW1S
aoJK7bOEbTfle0XklnTqDVKzgdHpmngAf04awRRyMB3Y5q0T/eK2dKUjZk1YoFFR
h+S189dtbTb7bjLo3cF3vbI84oRM27jeQVEi9AEiwtFjGCxDQ/GPqniyGXLogr1Y
QoZti2EcgDKCShKS1FEquG+7+OGcR5hu5karwfh5ELENMcK3nn+4VkthxfyGFQGG
U5KSBXWRyGBTtlWsDXyJS4UqNgHkPYCA2/VFRf0czMedPlnCaB210m9hjqJJ7lLS
QAH0qrNctyAUUGoJH6+JJCmMMpSQRwKLY0PU9wTwoRLBpqJhB42qv5RAvEIsXFTR
G025545/Zc+pUc79GQl6tzc=
=3sSh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b244846b-a3d0-4c17-a002-222fa84460d5',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+LDNDwQIMIPKeMDdhx0SfRz6gKUzmt04OgpbpAEgsToUy
4pr8zyN1jxhRJeqsanRhlZGY3+GCOfLaF8lRcI5F6nFL1iQwr59ljfC5APbVn+t0
IyXM2qd7jlKE67sA5TJ4Z1+E2U84V2IVzL8yvEYoSJ0yW53ZoIwm3gZLmJOEgCiq
yEuZndWDObr5oOe2E3x/2VdAykMb5joeMaXWhVEpoSwSvRXaqIZxllI2LSQTuCkq
S+3dk5RxdULwMWURHtFlYH0vCqJOC3ZAsMoy6QvNv5kwX1pndk5uRH0Gl2sWed9N
r9NzsSiOrQkQdnS2++wjbWMHAc6hdUEOdSmhAA9n2h+Rz3vjmhwPx6qFtgrgUj5a
l32olvWDLgfXPWZp1S5zDh8wuUrVcncfmqhTsxtQVt8nwR+TJKUxmIMZVk4tk3ku
8QQEmcbh/SseNtm0aDF2nAbozZNi4PZGpQMpZ9R1FD2sC2thNZ1vwbJCMt+d1SDs
pMo6vjuiEbCvXmmlEy5iPB4ebR3GXDEFEdxOf0QwnISImLN9Lb68jiaBs6ge4n94
aYaVR2aFIptI1fPGOMdTIXSwdj375BCe65NGUKpscbDpwoe/h9PzKEYpgbuVEMX3
TB1ShWBTp4DPwBFRSOj4mHR2nAB7UCCeIZM4ZYtP3kEyvScURV/+UWBe21fJPQ3S
QQE0dOSOFRiagp/SLEvxulpubKWs/lpdgQcxTACFFsy3OTys+2ByxNpmbSbQsB8W
eQS5k5x8Hd7IgmyXexGVjBsB
=EHyF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b4046f48-10d5-4406-a6fd-0c0fbda7c854',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9Fhf/HDUHPnpeDVefVSp92a3+yGoZnnTXcQKJurGL+1XD
QLD2wmHAyntDMcxYx41Ii17RBKnupbUcxFss40Vw+7PJ/891PBuGpjPoEmdEayss
SLM1+NOki0QMYTIYhrkbHQfBquHYDTOAWVuIC4KnEFLpUuuU17VLXuh7IZWCGAcv
J75wyFK6T6E37FOWa6rONWcxkI089K/4MdOWKhW58uQdG7fy7pWqs3v+IicTMBSk
52HMzfe7WB+cVqPOCVIPHkro+WOZvhu1v2azv3iu3F3W0Hlc5jEHjqweGhLeZ4V7
vQsfRH4o+TZ2kiPsHlhXD36jtGl2P2D31CSGNNwazpdy59Y9nGEJ+YpVH+RLSjPW
n0tWODphI5opferdZKU7sWT8enBK/QbLN2X1PPG3swMXjOiPIYmCxlpJpZfEmsvv
Z8pk38MuWapVTn0N+ilzvNTfbWJw82UDQ8ejw77l8Vdi7v/UCV1Q5pSlxhjxsypD
21QY6A8fMJJuB07ssAU6xoJe+D1WFSSKmTRipqrKsl5Dh4jmJhGF7h5dOwh3RNKA
LJycIQrXFTEDzUN64885v5Rjn2xHBjwFNXZJ7/yfXyo8DkI+xm6ecctXzYcBs+Dy
v2zoomn6co8SWOsyPh3ZpTbDQav8xXdHjXflewGkB+FpMy8+7zXPq7YhnHzPuyjS
QQHLDxayHoR39H6+pl6jGUen82l42FNyEDaXPhqRFxsHYxLyzhxEiZwOgEZbJ1Ta
CBIin/XiXcNjQN8iQig5K1PY
=hyHB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b7dfbb14-ae6f-464c-a863-51736db02219',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAiDzkDJndDu9CdPCa6yR9b/o0IEsRu9YzWV1fsxLsmHPD
7AClfYbEAHig/Te0wdB00xGdx1kRmNA2FkCR71+5EhljLa/uhSqdgr75jkCkMEWH
rMCUqFP3OCUKQBxCJATh0BhgFQjZbxov9yS1Yt7JAY2ZSnpfe/+/mD5ovJQjb85S
R/tDm7FIdVRqwuqA/olx++eRKOpenlucFpAmgunRBqUpM9p+fQkJmF0cSUzSN2qv
cs0/anuKhV7aoMYMwQhd+IOCRayr81ySPHDNh7Rmb9B+GX8w8njQTOQEMiiZxjM3
yrD198B6/4sPzviAorHGmiQLFRMyns6j6VGoX3CDXw1vUALKpw6RjH0D+pxKASyz
VbNSxXVWriwi5rsLZSKrfh7inwJRJrvjjRKpZizyigJF1nPAqw10cMIMauS7F2is
F2N8GC+U9da0uAYZmKNWHV3tHcwTW5MkTOO8RlAIjaOSP8tfZ7H5oISXF7bmeEUn
dfd2ju2tM1z2QYVcqvuGEVf5bwZMmY0/svXWhzsOfBuG3ZmfHmOUiFHIYyKKpGxq
+yhFGjRZHhc+KGOaVhBVmNe7BwvUrAE1okRyWXiZkuO7ydEpeUFcYEIZftHKHMRD
OpNPN8sSgjuOePtHEyy1t8xZdkJuWisdpwj3NaY0aAr6YYNBPQtZQuE8ASV4YhXS
QQF77/PfVoVAGXuylMcpyNdm1Xg+Ultc9JZE6Yy33CCsKhcsEypCLT5Ij9YVnkLG
8ZyUSiloHLPZAwxIuPCYqBJY
=kO0C
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'be3a8408-929a-443e-a641-3b99282781a7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Tt1wy3JTi4CL0mXt/c4v2mhQQvsKIvfgAwGHHuDMrwBy
XmppY7fHRw0hqIrEa+HA7F66hm3n523cmTBWpD+W4oAOtygKtMPHkhh+mdvy2oZN
QaM7Ph/q05ikHmTUQ55+gTG88jTN3DkO0vb4p/A1pXVySGpZKoIv7WYSAnoBoaNl
eqo50m1DIkGpzA8ROJHtlqUHIvIgCHr+nrvg+3SKl+sPT8X7wh7J8tRRpgS8T4HW
u1oPbAIVNIeVwKAsxMNf2i1rIR3dAL/Jk+PgP1IEvpYd2K6dlziQ/58VBdY+N1cc
ByO3UbKo5wRjcqwXJ5y7okFmqpZXaxVSCD1VwKbICCzMsuTUs9IBJO2IC2UhmyxO
8x2UDOoyTAdPXILL/cGYLPVtdu0Qmo1PeVRxfeTN0xfJBsIDgwOKxaZNNXNrblM4
SrLGSGitcIMxyuicI1xfq+3t5wSzvpi3d9jmMmeXazb+ZvtlQzkuf5GGuKRXBaqg
JDGXXyrd4tLrYdptLx949GHYvpfAkuydgez3dxDueUW/CZWdYUg9w5iAsvOhscQH
EzMyosCraElSnnbHOTSMbg93NH89W/shLIQysI8NCauRzMeUyjn0jHWN6MsS9jx6
3GVvr/KZUeqlDiDdZ3SXWdmjFWYlH82M6XOAsAI0s6aQyNG1xD99Vkd7D02E5lLS
QAE3bBWdJuBGbTX19D/lnrpmxyaESkO83gWb+mtte91OjCR9EqaAxsB9hL+ZtB0G
p8kyZMLloFYmVaM7z+2QBA8=
=vEIn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'be64814d-2c72-4d81-af30-53774bf4cf4e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+LS++ASGOEicwN/CaCVOHcbt5fhsbL8P6sGbkrxGSS0D4
3KdsLeGb6NzaAAdvTPO9SGO3rnUhk7kTbWzGICI/rOpKKwMZBD7uuSQ/AGrUtgcW
OgYcx0V5skff55BhhQIucXDgqdTnsvzTnlUXsd2UXObrqBvbXbOeul7khP7txJQ+
gAY2AIYcpSQYJqrtBjp52Qmm6wQGSmeM9RwCIhRag96JUbsQkVWgMscJl/yCkvNo
XvqPzPTOQCJ5XkLR+13sbamH9iD1Msi/QuZOctYQGRTtY2d4REzDNmdOzQAIY8+F
t0WMKdINWB+MHXWAe4/FQJ+0MkTxJtPkCq5oleMl3lJlDaIhS7Luf+DRyZ+8Bu8n
br4JiDz+DEj+QrA+sEIKbzYvPKhfYKGBqdBYZhZ/PoNflNJEenNwrZrxRfaY/8C/
FjV2anXu3NkzfzkEagQOk1XjcNZOfF/kiMLebDIYT09s30mv8oeKs0kzBJgOfk6n
kxxycbQVvO7Z7pJNcDyiU89x0pZ7NiTZNWZ+hMOvNveKUc3r6G9t0pLV9d7bzF3f
oflQGD5Z2MbrblDs63GAFfNVhh3rTr2J3dfdmDtfpzYNts+HZM0h1dlCz7O5dSHs
Mjvo0YWwM0qvdYWPu9K0Oo63BVbN4fewtjul0TQwJ8eFR/Bio9yr8Qx+PWLHdiHS
QQH2dowlgX+WHURMgzRonePhVF8OFfpgCGZvqfHoU9usEsm/d5hcmSZOTceFmWlj
4PshHKjKHMPNCGbnBJI5osPO
=GQJo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'be93489c-ac4a-4a2e-ad92-1e41112a31e8',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9EIs5vn9RprAvjTw8gi9GW2XfVjCobbK+GI1Jdvas5nmj
yKKKv/G5tXIPu5pQoUrLFfn7RKn/RNgdaWTqTf4IKMMNxxcsIquNKwbRVAWOvzn5
jgVpHF6HFeHhOOprEk8cvXi60hwK7JveVYT62Kggf/dPIvr6FMAiKvN+LW5OXZzq
FLsE8HzfZNKTf2n0bkHo19PbW5IEPhsUqz4Z/AMic6OLMLiw4U9m4D1m/QNVv3RR
L9dw2wBBI2VDTU/jt+8dBvO35r93SKAJs8hTuSV9Y/paZ88FmgYCzv+hxzZecFap
mMSNB0YE80Y1EMDMUkgAp6oc5ipNTnyRkvwAolqgjhKvLA5HQjkYAwngP9mHa631
IRwA874ETlGB+7aGwNg3SwQmYPaxmQ90y9gVaEOhUy62kJ7sxv8AE62cwoemE0Pu
ZkmX5YvV765vZSxMaNa7oCsEJBK0jozSHg3WzQRDXbqZFbzPbg5DuRVgmBpYFiO8
kkyrVtoumc2sBAcXp149Rsiqv1+UKX02KfS8UmBODNzVHAxjNeImApSlozzz51+E
Tpg7mKQPNazalqPT2SgPt031f5UlSQp8XZZv9SCGvOtBKmXzdIfEdk42wwuo83ai
7gK/Mq9Czgnt9bL5jN87+O5jZ2lm6E5TP0JupQRdcFykGIxfWZ705He5hWlKTjrS
QAFP0rk5jMAi4wlb0xmeTmQXzSi3AxzpRcMraTOR8WkjFpKSlWHPSj8HR5N/HrNA
E3XeesL08A7xXh5nvdiu2wY=
=fZg/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c7aca155-e8d3-418b-a202-85a058c61a9a',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+LU3wdwOS/D+7dxlRyz0kXW1afKk/TsgcOwu6Yo/FTnBN
1RUcAT/thqsmOmP4x8x4GaMQ7Br0R3imDxpwqk4alHHabexUj/+4caftaEDsJvO9
QraC8LxosJKoHywZ+y1B7xAHD/vWJYNzffQ65ETkJFc4rWlGmg91J8H5zazFVvQ7
pLjTGgaP4oMTay3t8mpU3FfxfTWiCypaLC9UCMR8Tcx/BYc0z+P2QqkN06sa2wfJ
RQ+YuaoZS7HsgIJgSKQ9vUBEufi5hVXtQnFHawU0uXo8IgN4yfUvSK8F39iW04u9
CN3aTj/Xa1MCyXXaaaybBw8EpDWMdFOUHkRb90edVtJBARrbZMXngdbScpApM6m3
7vqtGCkMmrYe/ot4GebXi3VqOcoFymWB1IedNuAgJhSyf+x+l/ozep6tNrKgWUOD
2Fk=
=EiHz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf9a4a6b-e69b-4c91-a309-a3fbb08394d7',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAi0rEnfYMs2/WqTA79YkOeFTjRfyF9FKl8Kv7uyQTyYV+
RMyFTTlk9wz2higRvlZo6oXlyhtmcpQyhcy2/nuAbD7MzRmzmlNzfkj7MAFf/D2i
kTG5cdVyH56Ga7Y+kJT+cMMboK0u6bFJFBtVGY/7VN97TDo1A9+fv1y5p1nPza4j
47rvYHNXCWURR5ZDDxmOVfhBeVLwXJol4rkXb4nK8eikw5gvEn7HLxXRgy7DtZrT
7f0UMwxL+1Y1dLsHgoXYyuCMKzZP5alg79UknmGy/km7Zq4Q+r/xW6cJH69GfbZa
cajxa9PilAH4w4+2W5FRH6glOg2+D/zo/GgA/XqJw9JDAevbo1+VuUaW2YtzwIpr
jxG+Fwt0Og4Je2+H7B+qFckmerXcUtabhUZhrqOXUGNdNOZSnHeIyVr/JOTTMGfL
Kp7C7g==
=XGa8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd0c80053-72e9-4b3d-a705-b667dca8dcd6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//UUXVPk04uU98duKoyldalVIN+DtjaPodRTSsCq03e+Wa
lLP1lwL6he7hA6EaFSs2vYdsQijCrunI7+DeRoT1vCn1gdCbdm4Zxngv7B++vMH6
6U03AKNQx3oRQZtzO4vWbN/rJqc+ca16IF79afNlX5Uus3MGzKDPPxpZZ4+gYH8i
Pxn+u39rf4XQGV/jgTVgzw3i+Ozcbh+NpkxbQ7t8J6MyMydXsblB3hHes6skOEPh
q8P9av6zSNIVa41qMQkYoUblZBglqvTkvWwMgG9Wuy1Hpc2+5g4UlEjmoAHZ9XwI
GGAY3dH77yIdryD8B5rVfE1asMjBrw17Hj92ec1LARm2GCv5VX6T8eVWfJRRGV6I
2v0aNcIRGOSG8S066mY/WKf5YiYBNEjf5pF5oJ+xWuQpPixydoyXch1+siw6sEC+
oF667OFTNFKb66/3Fl1VHXpKKmV1n/WaOpAO8Wntpa5HARgsjG1ylnf4RMUcVVF1
B5tTc5CWUZbnERlx/eyT0M96eUQ+PG06Df2dMFbDu8SImyCFM97YgR/OAhwZ8c2T
b8ohLLw6qC+aNfUJe5mFH2OB0h1nQmhNnmT18c6FI/WjJ9TGlRoJQaLyihm9UBWQ
ZUjBQgRrCCJ/R3SB8u3wIVZRsFYw1KV/WagSMpGbTI4CVSAVzUJb5xgwDGRsa/7S
QwGZVUk7Tr5tilUTHHtago6ThDAQDc4UebAQbCPXzpyl4qA6qs0+vQME4wJ2xZgM
3akNnVuJYgISMj6gKLy5R1Wmqoc=
=fNDB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd59a2bd6-3ac1-4538-a067-686e30f1db1e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAkowgib9qUUcvSeRyfK8JpALcIAeYaEp57OBpOc+QVwQx
8pPjEW4FdrJHKDPCINJqkmUJGved8OQSas6RfB7yQD1e1uytel3T7zFyi19yj12G
eWHofQou/kym260ZFeih3DTDdnkJ/jEVkN70lIgjFicKD+1E8wMU4b5WkjRV72yr
MKwPszTNoqYoQg3Mfl4r0FIborl6u3sHF4zbaED30NoJdXwRj2qPgRiGnHvtTm1q
1BBy+IazYXh1n2xwjQ+v/A1/x40RtbjilwT0qMPv+HEZkhFMOYQQ1o9tQmjRXovr
yeU50jYJejYgBYoYvxg9735BSJjC6zisVU6Y+GyYC7J+WxpCbAgma46ipMHkfYO4
dZWI9KLUvJQlxZPv2V5PkyrZzWyKOePScN+hSVOZHIBlT+uT2ximbz+zzg8FR5vA
14XdwFlcZJHg8OWtuzjWwHGKbGRpjCgVwXyRFLKB0SqM/Ws4jGvCdLeZbDG/M8xz
69HzKzMVTnnVfV0d/g2RgSQt6oGsWiXEQTbmXX68AfM98WNVxG1zOHukVIG4pkhn
KgqalajoxiUPdP9rz3/vqPRPpnd4Y9SVOOVIfkb4KLhBGUw+7zre/GnXwuopp/+E
l+5of59XhEdVsgtUt2xjRfQaUTcu9KHCD26fLHBdQ7E6lAO2anJvRc1hLsXwb4zS
QAHULHmbyvSMrkoFAydBo+BztoHvuyuipCS5CjFt94UakTJ3r9hAkMdHWfSDV3mJ
aKMTTn7F1NnARyyLRuAzTm4=
=6yRl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'db22f0eb-938d-4be0-a606-c13fff11c209',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAqp/R/Gzqf4q/zrUtLyMLFiE9s1sE4l1ncWxHJaNAYWsP
8W9qjKextYBAsg6kd7bgGfZlKAcDgrIJ0CPLFEFlZeU4Z+GLipoe/4JHglVrfA6/
JzLIkbJq7pokSFxvNPCJEJv374wGBH8Fejx0ZKGszqpbGkSO2GXNIOdVOoQgBu4R
wtNxlA8fY8mVKPaIWCWGBUIqgEQ0BTHgeuGkGH6eZufFkppjbLvy42cSRWj9OwAY
lfgGG7w+GOOaOMB6iOBRi8/pNTgaXHcII4GAhkp3TTdXVMn26RMGtQ17ihCYF5b9
TZxjJagQMDwmB6tIVUQS+yOwZ2dfu813mwQRBUzJE+hHqxZ2dwzn0XR7f+OIDNB9
Xw9SHIW93BPx+tnqmXckDvLppJlqUJX+a7Ynu4PPfx7WEgrK5838gH+QLL+tLRd1
wxRcIRz/xmLN6jXN/S4DxPyPYvOOk4JkJLPcnlYLrZP4Q8bARB4296m47DMOaN1D
7nAXOH0e2JGB0gPePk8wovMCa+uuXkbixGVEgzwMcP0SNQl8EUzCb6c3nsh1QfWw
FEeI2bPDuoV9EKS6h4q94C+wU98QRincY/gtCT+nhSU0YUfFAXkOne01s5RSrYlx
GIDnwXsc7EJxk/9l3ssubmHcnGh3jPtgGnZHf33m/BiWWeBZAG1tJsJRiKmnOcHS
QQG+5nDx1V6WZ7ozLNRUWG/QBDQeGMfbLrKQq5NFnxRUaEOn/kDQ78JYSQK5ifk3
p753BPVCNrVzTeVoeVlar1To
=FEof
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dbc0a0d0-161d-4d32-af61-480d577158ed',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAxDg6sjNC1AxqdnhH65LSBexqN5pW6s9ioyNo+mxPgOM4
qn4Xu5hInxH4OChqQgro+7ACXsl1/metN+PWIoNB4W513RP430LyyqoT9qF23/AL
Hbt+AuwQiUD2+9HshxWdz2c6XQsnh/XwNJyKGXrkxDwqQJHnKGjZtHBBrJmw1U9M
JayZDCsMiivkYNKc+LIh615HvCrQ4H1LgqAVzziSQ/cGSiiYPXNWZk9JvezVExAZ
G447BYZyZptMrJpdOJwWin4Nrb3SO3Xzt7JhKuC9Y5wRvZJWq9Lp3UkdtNFHyofx
7ssBgNezsNYwqvpPhjmSSNxNEuqI2cwQePFiZRU7CdMaae5VayvHFW073ymZ7kKl
Xv+qjXIenV9zSnN/hmOs71rxfcoJ8lpPkfTC4HSBOhvm6yO566AIgYZlDb5gBZ9V
0bxKteSYeYH2bHxwj1zWvTR9UEMUSMbwCJHgXPtFZifWtxuI7pGkP8KR9/rmAL9N
rVrIuIcQBKSE9PI8o5CHBw3kVDxFwxnTsDjrriwxhzcEKIqzf1Pd0pLZgx1g/Zgs
Jx5p2mPvc7G/WaB6tt0HZNIAOa2lIkPkNNKFNMlnwU33WDBmNZ5PvsQTXQ4HY868
J3rSv6T6B7/JCSZbVTCByVY/cAY/BRIyXM8pBIbVIL/dlmzBx6IJ0npBfnGIFITS
QQHzBTSmkY7/DGnIc95Z0PwHr3xHJaTNIJhbjzMlUxpY5zTIa2wNcCuxawdpc/Pk
PM+9lwKf/Na/7i1mOC+SjuFm
=8lll
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e1765e4a-3299-41bf-ae88-7459c0e853d3',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAz7gOj5x/2XOtl8h0PaaPDkRKv8IV/Y4E27ChFrMw/wRC
nUZYRZMajCsB5bayMTm5TYfXqopOiQxnYCeDKU19y0wSSy3EUCH7bq9JbMKdZaVQ
uAVKBIOiOPKqFHr9TDV68DKdHElxjAHOme1LYfB9qznjppVNYAbcfbdH766jYhHv
8xnaP1CNy9FQ16Sypv9cqVSTvQkKTPWUh7dD8b33KAOzOgNJr3d+yeYa0PqiL1Pi
+elmQBWmc2sDIO2PUhhtP3PRK23fQr7HLUr1ceBMaujiQEKpcrme78mH3fch9hKn
8TFtlJuioRUyIC8BFymbiGsHaHOwBrGAVXynon+4W5HyWu44tbfFXG+h1T5zfcwp
Ik4fdz1d+L5Aio5qmLQ16LKCGv5Od43BlemqhHEMiKEsjyIt35hQUW4ksdqatWnX
a39aV466Rnudf8Bdi0KGlZyYbWRPQadVwLpL+Aw7mUY0D8ZsGP95Y4xEgj5StLmw
FEyH/SnaoPwYuVeZlvYlD4JaZlCva2UeRa53Dl2FT3BSor79vDSZYqSVYzG+9IIL
8a4qrNzuSJM39q55ggtczPATJsocR+210c0OXjDX2dcBSIrBKjyS3DupyI4KmveS
zdAi5R7adbh3Y068ARdsOdfT2zfSBw5F48YS43jxXWT9fZh77reZUe5dLRqcdvvS
QQFUqEW+qP94OCik9MCc7nGcPu1m1wPnq0u3uB9hW98sos4v2Qi4hki5dNfGqTeM
/GXvB1QqkeAvhRvOa4Nby/s5
=uhMv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e7f15c30-da50-47c3-a4b4-c0a3101a0b48',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf8CDfAnjrgfsXMvMpgPmFmslz79Bq3DpnEOkCVCr1Y8lNh
28o+m1mws8ZWxVWs2D3ACZVLL1Tg6VkSGi721QyJpcOGLB4gD2vo67ArKMSQWsZL
y+nrx4Ue7W/Y+KMTLp3YkWLWRJ0G9SjrBq2siaDq/6ieM/zChqCzFPpeyJtXlc1c
HSnXLH/YMoN328UiCl2vm35WnfEwJGuB3cfW1z9TUqQDw7r2X9nnu4Ydb7oZHLau
3rqhLNfQF2XhCETqnP3iQMfccmq6i+YdfSA4UhaX30Yjf0oGm6agzCBSfnWlcCjS
OMjmA2mpUyO3MYCrkCfFFp2ipo8wkPUbLWLld6SU99JEAXEbRiuUDW3TiHF7l/Vy
9XmUTI2Bdg3Bc6ZXKErpvexYikpiYOsDP8Gd/iN/BNRhwy7ZKwKA6S0rvakVlGKy
WwKrLL8=
=19Vf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e94c2a39-e9b7-4da0-a51c-372d3c4279b0',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+KXIurttdH4bGqfESFlTjJIJZllySTaVViKBexoZ6Zd1A
6kehFdhlVXYuIrpOGliKmd82fewyxWpe2cRQQ2HXONLq/6CXudfmRqV1OLDcyUBR
H6/pfJM0CrVfWscLXVHhQdIMSFZn2yXj42B2QAB41zyMdCwTLQ0CRU4cS33NbGtw
aKGdd8giAAEB5v4DpbEym/o4BCmJIXUZfZ23ILTw3m0RCsfk9VE3fqKDuZrlWbbB
c0BefTNtPaKav/GLG+RuKO7wlKN1pYS6ewVMdW/atAr/AHl6oXFWe2mihC42phzN
sjxu8zE5MYLsdILco10QBR1X/KMlmhjQGgPBTyJZ2d0G+KfQrQ4qPjV1woYtQzH1
kdn1BszBwvbBjvYmi+i6vxjv2ihj2DLDqAteHctPKb2c8NHqiyHiyU7s27ESlN44
C7hCWdBYHpldpjwCLYgyjUVXx0x9m6wWXWIzVRzovVQwahZXs05Z9D8la9BMgSO6
HISRB9KEt0Fi384/uQHBK5KI1DolmINmghDYMFAiPJ3zTl+OUkmCTo2SwmJVHeEX
l3+VFcysbhb7VJAJpHhQxM3Auj3NZhohGYxXVuFTjsSxzcxIob2Xigd0Qjfxwdr/
MSgwAKOfaBAT/UcuOGso65JrBfGb4RMICyE2/BpjN+DzuDRXcZMFio4SM4NxwEvS
QwG0dGSnKnsRWEvEnIeeP8bo6nvEVAsokoVFOXm7HAsPVz5ou+05CY1bZpDB4xX5
D880nLgbl0gbrbjcuaBHBANpYu0=
=evY0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e990dad2-58eb-45d9-a642-26755bc6c106',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//WVgt5ImvMX+BRpu/6tj32rnRtBvObfOVrT1JDpJN19um
1MGCx79vSSwf+FOn3qKZYgGmI/Lhinj6tADejlPmdxjLtHAj5G4wktR8NeHXkOdM
r3Ec78c/Mhk+oIMX1nJpTXNuU2iGVj4ENR1FqhRCGdi1KqRuGYcFcdBVQItYs0L3
JJh2ia99gnP7NYCw2xEsHpuMSWUoC5kbraDw/BaTtWzMVGEpUXl+KsZhZphyRhNH
/jA8qCkggc8b5UmD1GI7V7DKVYrzBg+o+VCo7YsO4kKgKtiGhSILvhZXSS6CafjS
XZytxkbO2CW0yJYBa6iAfJhhSWcIxhWpFdBdamGH8NW2IQVm53FmVN0vWt4+vFFb
ozjIDK+dSta0k+6PapnOKKKL0daLkw8XOHmS1tIkUROBaEAqS3+9ejBWlS6e5Pn8
+wXBH8M1VFPvREqtasbKrxJbMm2+HocgUZmqlSiG6OQC3gKc34zRl2GogJJowJif
eULdFsR5WUXLBoLJWmDlBbUQLYbLD0vvfkIu/4MaPBK2rmRCGYQaCjn/NY/iUCEA
MDKGPNj6gxrCFjtsmhC9HlAWkUQLG4FPKvHTwWneJBXN8dKrHFWO0yBKuyNOARFT
+rUOWHYzVuwWbzdj+kAOIys+AJvKEOQFLayIU5c9V7xG6HVfNNGXkmiBxNbqgCvS
QQFJ29VwPPMgZhdgg8Y8Ejfxrr2l/W+935vS/cCkFlvP3At18/ClDnSkmhh/cpyW
7cemfF53GZyAUTDNm3gn3lso
=iZUh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e9d78ba0-2bbe-4a56-a8ad-df504fb2d6bd',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//ZiIE6+T0YNxVjdoh1CCSlHhwdrJJVZDDkHRvLW7ubaTy
FBwqXGFRL+Xdxk9zbij3VXW6h/wBjLLANa06/1RIRg+buoFcOvMa6tqFruLZnTRl
OjhHVfa17z254rJ13Q45ghxpYjJhWQSgZFMWSLbm4tfRo2ulhuEWMn8GHozvO6Sa
Er3PNzi7/3KhMQ7LFVxDH/l9POxNc9/4gy5CKfzFmxBa/8dQm+czz/K+izW9CnKM
qjFcA3EjDUzD9hL4SDACFw/9p3oEnb80toE9QEWcaSesy28eOFlZh8WvVHBg4UMD
TZXV02/nVorfEhK/dP9JMfSfvH3vXHMM9JkILAZlLxJFWrJKQ90WHWsEf1qZcLfo
dQQWMkWIFwm6t96jmntoOp8wyDZURZeUKrfA7ozPLuW3yHmlaOGAeKHPhRm5Hcnm
XSFH1xn7kEJOEg8fH7+v90RVSubSvAx/Ud0wLm1nL6VDGT/BFuIb2Ty6dAiJ2Wa/
51KQymmxNatcjzt1STWLXLi0Hhbu++LVBx3/ffmCxEdVhl4SKxKmoZ5EzFZVYfLJ
Z7fu290jZyUjXsY1HfIjTQry+L/V+vMFbxjrz2BZqsTNjjnDw1gtYFJzpMqMbKed
8GNrjU1U5Zh7yBPzhEkd41E2gIHdPdFMbkDzuPz4cYHhnPyLAhxD5eoBZHJPE6jS
QAE5ixpPecv2PSqxYcPvk7iiAe5lSZE8Ank/C0/o+ZRYciwVshXnTVAEAp7X0hCK
hztBLM3Xq1wAbh+iOSeF6Gg=
=s5Nm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'edf83966-e634-4fba-acff-e0b594db97a3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//aHvUPxmrb8fqDX9AHfjl475Vx79LPYr3Kigqaw+tsiEk
PS8NVIXjbJaiE8DFLDIzuT9TlVh79NyOmwCK/QxYnSv462Z9WdKJC+hmwAx+GmMH
orXn/2/76IRy7fmz3aBRHFGJtilaj55pYiBIEQ9oPS9Lcr3DFDEK1GEGo8chVdOi
J8Q1P13RS0FL/fIEKIJCyy4WPXKSP/aXkooEuDcRizHJ5yAmoWsBDV/dBy6sTIDm
y08lro39W8hFf0PkA2U4ykD/C/q9jNWA3qm5cH9kngnfc44ylT+Hc+30wQI/y63r
yiKxVQaIb1CfzXqMY+VwYuNzF4yDy6GazpYj3PKgU2pPGVL+McNxNkVkuvgqFwNH
EqqGB/cxEH9xZ6CUlqbID9mMjJcB5yyjPWEm2Xfn7LsSqa24Yai590HwABhhgfMG
g2uod+D+m+EFIRFpEGNQX8d078Md7KNnuYpkRsxTA8yiJTwY97rsPQ4/YrRU/U4T
FGRHe/F902rnz42YN63qbzPPRTwFh9jxdWZJHM4BKnU7NShjkx5bhx1N8v94VAV7
eKtvXxPbLbiyXsGFsKYsWKJlazXUUo77pilP9VboIN5SgaLKP1MuQW0TPIZ371tm
DDkIcv8ePAdHRxSDxqKL4jzKmDBpl/11VaxJMoT1AqhOln4orcgLLuodvpibuOHS
QwGbf/JUkhIQ44Wmft2FO3eaiFXIBUt4QDIE9um2WtCGMnRIBgbcEC32H2d8x7YC
5n3Jw79hLzG7dz2t2+FkpHIWkUY=
=BdUk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f2db792e-6da4-4203-aa60-c929f0aca645',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/XzZJBQYnUdP9olw6vxJZ7LPIIUBPPa21v+wkzWa50PjI
M153GYSbufl7LSCXJy6ORYnSVTPJZlkRyEju4rk4fZ41a7lQxB9cJX6T+AgkGURt
dOCu+UcFfSHFYv5dHEKKbAzLY6voE7CC36mnR5tzDYf6awkwMjoxN6/mrbBJDAev
FySz+EI2aTI83rcin+gGqZJfhkq/vuHri+TqyFEUBwPKf+s5bgsx8SHULUCpk0us
dSER9+8+BQh2ktmaG6eQ/u/8fo3PF6qN9vyhogY2Xs+HB/DWs+/p0gIpPEqhzYMj
4PQr3y10vwns33ta5bs422jG5Iaep+1u5YVo66uyK9JBAS8bS7LX/Lve8eeYyOMS
jTZImdFQCujdAU8vHuhyTyZ5BqJtBvaniBnT4+GsfutskTl/rP++CwM90CMg14/w
UWg=
=IoHh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f3d54dcf-d575-449d-a59b-61f61d21ed3c',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf8CzT6saZ9KOKyxo+SmeHrYxLxrjNq2ziAzLgswz9M8/Ka
HLv7qq6JwU9Yr7EuE/hTFlHwR7/YfYvs+pbkn32jUYx8nBvAwIOKs4+w5asAV1uY
PIr90cvtTeHKYHg3qAkI5dBbJ+AzzyiFDE64D1z/IkruxRZKC7TXmWTPSB8Rs/Ug
qRbyd/ptvJ0LjEpVAn5dnFU/XiEyHVo4BjbbU9VT8+4CgTWfx+CcykxZF5S1siSX
aw4UVSFgEsLgqr7FH3fWho+9vGaG8JA9MfPXXATNojHGYfYudJXxw0/CmZcbNwBX
hPLvRlEi4EXSFH8lPe4NcOBUZjyi6jBUENyPiDpfINJDASzlXi9mEACi4dOAHb6j
4O8Uxn2N7pybbsuuj+odQ6l1JGWpfyJyHx+GGaPLpC4Mtvmi8todOi2QhxNaKD/V
SdTp7g==
=8ES4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f89bc9bc-0ff0-40a9-afce-b2a3e251c83d',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+KNpy7JfoJjqtnZworQcWOjrAGm7IUp5XFsXoshCwLQTE
OmviEgqLxwWYffXtq6wwwee5FhW8e06AYQ7+viDeOTGMlEUJM5P7haI1iQCl84X0
J/AvIdTSZdXGrdfpxjf4ETUz7qZVY/slDISGrJoNVEXzNbV6RpX/DCDX94vdtm8s
ocAQXpPDo9Ol2DAXBDWznyBfcd7SijVOhoNWuLeoTL9SImAHtuz40VpdXpv6wa8j
/QRsrU0cwRbOY8iKzbY9RohwS4VMaU37UObh7G84jnkn5QrKPxa4ddif4VUJMf+h
lU4YZtDNHykQ1kOme6QGhVpann9UBSdHN/LJrMpQatJBAYzzFkJLoyy+eh8tFBLY
j2ZGSKeIqCzlbbgdp6AgncPbCmdRH55c1OnXT9ntJEq229fnBUh/7e7yIURm/pFJ
DDQ=
=U9cN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fa893096-3a3d-4b90-aa69-235feb76fe29',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//R39mnbNLnAu82fr282ZwwxpJJ7PRnnZEWOnCagKT71ua
pYrrb/YO3b642gNGmeDVGZwNbAxtgS13BaBPoTfptGhB3ehh3bpMdb1RiQ8B3QSg
FDBUgHola/9Olx1m1fliTx1qWWFkGtq9G4KpGbI1J2f5KzDsQehJFp9187oCaZDf
sGiYz2E7c3Tjz+ahR1LJTMRzenq0Z/SN/lXJXe35HckcTjFOKZ911TSRCAEQuqZZ
IUl1ZmGyhWAK0xWK2N66t4OzBWm1xLRdi4U9SRUJj2zGNrn28s5z2t8fT+ZAoKAg
sU3BHCnUPsagbd1C9YgFREA9wHJsv90Rw0o4tZ2tW2wcdD6BjBbehyWm3Zko7x6n
Wbrulw3as98AyrGc+ztvWVFg80A/A9DlXTx2qb/2fc+xGtnRwBM5Cv141bJL3Eag
mOd2Xvvgvh9UuYgCu1ryPiC3exi2SyWamC9Vy0z2xU5RMxD/rcc+/j5cNOdnct7m
rusKcZjAGtPS3bbA25FRSclaPpg7eIp5b9NUEQ++OUaZ0djJw6qJyy7FkyXBWgA1
wq3QI0bdNkmErctk8FxYcdipXITyfMI2A8E0LrgUPxo7HdymI3+f8ldw/caYIbDE
xxRT9079DohfMJ9mvzDpW6m5+QB0VNCbcZXEnAjbTGykmuiKk/d8M4ONEGR7z1LS
QQFDiKsZK7ltSxRPLRw5uaPLFJ0NNRj43Pi5U0pAwfGg0ePK7KSTbfyHfl+eT6wl
9mSn+cdpxlmj5Ti8HbdKGYe7
=f12x
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
