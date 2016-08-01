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
			'id' => '03bdb47e-42c6-4696-a794-429474b91d58',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/8Df+EqB0SgTaD0tLwJNWSNa5tK4uLWZtJq9o/LXS+mpJ8
3x37yC3JIcG7k0mcqxg/OqSdRUwCtsJ+sSimXNqqHIdgFS8zbv5VTv+K0h3tqOsd
UGF4zHUUa7Z4C/zOdWLqVAdmu8h9y2HDkGqTexwrCWPDSKRVvi1B4+VUD2v7cluF
J86SkB1fmJJ4xIBnz0Mcxqi+9T3kMGK4kkFjk3xbeL6EH0iXPb7qlz2Qb6bBsiZw
KtNsDf0t2HAkXqseQSw6MgUmDCt5Qrg30TIjzx7WQ42Z7U+zJ0+ypbe6frYPqvv+
UjHEggWK/z+fGa/Xt69fkAKC+oZ160SGvocyv6Cbmj6DeTRIXbN1D9NpUJpVASSh
4ZSVeFfC5QC9ZdgOwMA1tmu9CSMrt/cGkFk30BU3UHGeDETeiEo/CJj1V2pYr28K
ohuD1huZWtQ2NWVCdTgavjUnxB4592gUiJV78k+iWe2Wirlzez+3v9Itb9XQeCFq
5oAZqGWMFsQVVeIBtYFyUBKuZv6hOxjuT7Jj5vPxFrzG/6ky4vsqs43UvXB2oBhM
AlFbDqSUkPf0sXXmNVV+RRLXKx1sIrLXavieGrqQW5KB3CLB+ekm/YDjokWhwwxy
PxIQqzARd29UyfjFX5D/1oh+I6vZ8UPTvKyhr/R4dQABeKdI17uEGiYZjGtBkifS
QQG1xZcjCyORJLgUO0TnMvU0uFn6GZ/K5LDPTO9OL/dgFJlKL8ukDzScSuIQl1Tm
FCO+swA8Td3Xf8rbqvWSFs4r
=dMHg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '072d85bf-9654-4bae-aa15-759340d3a31b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//UBmfxo+uyjI41CnmFFya7hQMoaUuHp5YXpsvBcB5/1dY
gU27i43KQCTEcc3q1j1HtIGSRNAt7iNJA6DhAUAvK9n6oJsaPoYt7OjH4b8Ye3qW
qOtxRldDjtHDwnSqoxStanFqqbwBtWfIRZH7EGBRq0U7/pLSuo0mdVbn9EXEQkfW
B97slWSP6mVMLl+uq4+3F3bFsdLA2uoqscOBhELMVw48OvqHvsZH7P7qyIlKeiJz
qn+1+/hts6+Zloe/OAJeR4XbpfDi7anTnbMvPQR5pmaw28a60b5tE+2kq+kbbOVV
1jdCAmbZad3lFpV8MiAasd6lt27jcyS1KNXrpwyiIgrk1j7O9WynIe6LM9dBqrpj
Dm3Ni5D8u0CVPA62iQfOl8OTFTTvcFRqNRboyZvT8SoYsvI1zlXkH0KBDvqinBM+
fqXnGmjyNBC4NFPg7hIpHJX7F57B/jtyXj/uvdwpWMjgILkXdTIcnTAHDmu+Q6Ca
CPIC/bx2TkATw4cJx13K0mto/9LR3/KFck5v01ZQmbE+NAzwZTmgDON+fD0TS4Bf
FWBUplvP88N/4W1rjGKzyNRsZNHnzTAvLFo2j4X7tfJ8cGrw0oKJPaF1xWtWabhe
gW7YGgtcpwniORlU4nW9TnL0UYY17/KiV5oH3bolfFF2Cpiw4VawEJ6HiWRwyP3S
QAGAxVOxjZlqevlL5TeZC357xDx44u6z22hyddvFM/XPR29zJITLkfbQlmo1WlBa
LP9uapziwrsFEktIOBM2bew=
=yD6T
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '07620f27-1424-4ea4-a06b-ec08e391a6a8',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigAQ//XkAldeMlpFcP1dNOVcrVxpkvcTaQp7Ig/pqHbGsqzgGo
6womiiycQ6+9XTOZM54jNc6kZKL4KarXlVZkBR8N7T0I/84xWLLGcqACdCmR9arB
kLnYsXOkqzi2oZ3JQUvwz+b9toNe6Xn9mwzoenK3vULqer5nENOX6aayhJ6pcKeT
b/Ryxio741jj0jiss/Q8CXzVPjhUzPmXUJMVSKybrN8sbI17NEUH2bBYPV18qSB6
N21/8+tWcB+o/1xdpDg8zDLWOMzOC3cnXCs9Bb07o9UK5DmPL0ZGo9m81vfpD9H6
Wo9/qu0RgE09/uSieqrJgaO3OpNZH11R0Xwq7iY3AohMhVmUWg+uyT5YbWWNEBUi
JM12xu03Noccc9qS+JdcteMoIyTRUqkL/43ZrlBwfyFkT6DP5qTHnFwCEk2lwgdO
L/OMVTgzAHBGAFUZZeoZkjA/mAu7igV/KaFbvp2FPG0Vn4+v4hwPvUTZgXcfx6bb
EwaBvi4ENJRtKsdRzupffIxsqQhlmtVEqO0l4oDI+Fad71sFHyd9V5hmgnnABVUn
LTRK9CXpR9Sad9OIphLMdOLlkEZEjBma1CT8gUF510NU/NDs8uKoN2u6XywXBqr7
6f81qK4N+V45ySQDUbn5xwxkdxiZVSYw/oksQ12dUhThnkMUbv33B0/q7bkh6IvS
QwGXWog+v1OF0jKvBlseGV7Vevamx8rkr2zcP6hRgjRzsOaNnnGYbSOr8OS8s76c
5A6EJMHo5dAErUzsYGyT2oraGOU=
=kuM/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '0aab8110-0d14-4521-a9a7-467a03e78cc3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigARAAiVsgbyvaKXFkLE0gJEEpANUQq3airfcd8INsVWgqF45s
7t5KPj5EcsBEMpC3qKwxeCLPQI2uLxIZa3hSh3gWj7M+YiiqXByxdxE5KE/6O7fw
pMcdRazeOL/l637wVmmU5XeLWuAZjDJzkqwBXlWfbEjeN/nTrGCgDdIdUh6Brd8F
KkZIO+Mbk69mkp64g88V54xNm0cvL8qEf8pOBv5MtSXJ5o7v5lWpqSZyIUwlsC50
3FaLahgCFXs2EMi/GijqBCLAECJ4paXFH9EUzUJG6zabqCfPVku6NaeyVAVsKl6p
xhdC6CD/apWIiGjz9+E/h0wkcg7SUvYMPpzi0h4ZI9st3tVmeMVdBcls7n/wrf/E
r7uQ0WJd3uA9XmAoH0UDtGmefcTb+0MqXC6D0B5VW2NlmuO+ppu+MUry7L2veS8+
1L6i/c/iy/dUof1CvBYpDVA4cP2coqZ702x2PiG5XuY2QdzHWH/YjCv2qbcjdCA3
HyH3keF8EJMs/r25AtXoIqTiqm9pUk/Hte+Q6RQQPlSRdmDiOMWBnrjme7B32OLV
gv0KBXuVaHtuwHVwoftY7gYga6oBzV9l1oN+9SZEadjvvr4y6rkKzxyBBcq8J5WM
vIc7Lhp8xoFdwxD5CLJ1EWQO8SMwQbZlTQqNZB1JqjUcKG5mjXNEB3I4wm46iL3S
QQFQi+z3I1Irt3cKrKIPDuSFRP+ObVXDdUALM7EpqFbgTxwKfqfWWE18bI5r+gkn
UHMaV4NdOa0XsnsmuEv2EPJH
=FsHw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '0e0d8c21-d436-446c-af56-0f86b0eff5a0',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//SeqV+BexLlDQPxkA3TcZu8RWhCFw2H59NIp/8qtTdmsK
04h0mMFU7TGIrDBiuNXHHEpTzAwVHdNk6XmSkn27jRVWqbDyyQAJUKYVv+tJz6RD
q+MX2MFatfvlKUnHKEzhdE1sCPyDAeFEUe+BF/R6le58dUehWJ7L84me03g0VdSk
zb0f4AexpT+XX78jqJtNb+gmZd+I6NeOL8ecku/XkJpXmppkf3dCfY44yug5lkOg
mQyypxJ7e3mZ6pIJkTItTr/lW5nQ+ZaPQnM8U1D40DkR+W9Ml2AWsgmz5UdLTRD3
hxS83j5ORHg/jzUaQPGiZekaCmdinrzYifTSzfoDHgGZja46CDZcRQ225KtM4zeS
DmRy5AY89o/FLg799I/+FMZlppl0r0s70txE+0v0MAezLHKqhYsF63JtnKVjnmK1
0WqGuQQhvzZxinHQGdq+de7QMckbyzDDfBJxduiB4NtyEZq/AUMK9/eWLHGPQC5j
/PlgalzkgXNQu00Pw+4FoYP0cOl+kFwX+T2ScaXn3y+zC4Ui/uCbQ40xU6A9Jer1
3sYFtL4OBTJT/pHgOv+oPAPJDZxcPhWOG3irRbkMTd3CTE58J1djzRrO72D8xEcc
mgS9yg59QHWszdSFl3iiR2DeWEG0WrmlwDYYtvvr98dVwodlw331Ab1kTSqOaV7S
QQGL1xPaaw0kiQiIda0ywKFslI1fHXCdG/kT2WoW/DZCZLH8cWA8uQ4WppAFUkLL
crhx880KOGpfJ2M4tytgNp+d
=JdPB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '15b2a946-d9ac-409a-a147-df2c993bd576',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//ZpWBdJwBYFfrI6+JYWwffApyuKidaTLQMoJAbd9trxhy
EXl7jE3uEtOEkHT44Y/h5tOK1VtcVYqfHMVOcCOZ7RWHbR+K6ALYG0Ggw1kNKA3d
CtIvdMgaCmprncsCcbU8hRVPCNOh+/1BSZus7hdm7FGK+NaWAI+c3O5s6ne1h8m2
N3E8DhTs7/Ieh4bMYz4J6U6zEKHc8CSUZ0zN2z+yMXk00N0R4HzBrg/cP2InDBVa
bPF6xoTt1TclldDpqz672GRLyRP3UslqLOTIxpK/xXp9QdBrak41EY/7zYKaHpFg
UAEs640U3qQLFFEE8iYBXbTBJSt+azjZLV+B35RhSnWBRWxy9sShmK4kUz6yr6xJ
1mhZg3Qkxk0L8F0BYLKiSyWijJPxwvaJ4HHuorqYQWm8ufkibW6paWCWjXDUrbpV
DpeldWgxabykxl08waeBEJsE88spZKC0NjH9MPY1OzyCp2YlY1sFVe/r3gMDD+L2
Bf/owzKHUqfe2jzfP3IZCBihJGBYTvMjRLjv8l8j7zA7u/b4sD0L96NvprHN8nmR
j96hry+wmZD8sjVzH7cxOYyOGT+J00qpYtIx5fYO8UyXcPg5mZ4ueJVR/ZLYX6wp
2zw459vyvYJx8ZIdCQrHgpat0HqPpa43Tl+VHa+UqUm/tO8pJlipEx7MmPpcXODS
QQE8AGMbEopjva+zjBrrEs8zVkofNjmyY9LcJjnJqleu7cYY+QgispCp/IWwmhga
N+HYTo6XYxZMnT3rVy+VENZD
=bWWd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '17be2f0a-02f9-4927-a93a-67eaba7a5ed6',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4e/DeCIHsAzARAAkByQBjWuScQx0jRxi28kqtWEPQC8h/Tmti3Cd7Y1mxib
X8b66Vy2wPl9izjauNkhcuxU3YdEA9K6SNlFC60uOK0cyH43OwdtWDmWfX5ocjCx
ZzBkhpaO7BDk75ZErp4vsFBjRNGihvtwto/ww/u4doO2TYTLHr+FlUOkO5paeu7j
1WFEUt7eAiwT8KNKhSW+N0FGU2CvIkcrzltHta3ugS1L1QnEJznUMBcW1b4KaR6L
dnx9fTSIHYMqhnfkE9q45ddX6SaOS81vDznQSYvsTXjy4lxUtnydzIathWDwaYWK
rL/UUqgnG+3biyirXbnUStiiv2gU81zM9MSXuCDgTOrrEKH6GgqMK1OR9Yw0r9Ts
S8yP08sqDY1aJ07GdFnWV4RbRLl47X5kq52z7gW+vBVxj/u8zaQAK9ViZjrb2S25
6AYgZTSaiGcGDZlk7faeS7YJbfuQ6umX6B8/pdWxNOjrOkv7aUM8A8wD3qL/pA6l
XkgkxVfjTVUsmhv5qkbF5T9DRWG7U/bHBcYopjqAMRky1+sp54JE11BaXk4bF0d4
YLmfsp3Gqm+j+QIPp7SYJZ5BW73ImUU+S76t6bAoOCwERkPQPqoId3oKZbpi3iWF
wlBqI67woYquvu3R8D1K5hIWAEhG8hxQ0Iv6ga572+r4tpVoYed5l3Es3XW1GPrS
QgHljkwroJx/lyVu7S7pxHJ6RRd1UOgv6bf7y9wCu+rldPfevHzEkvYahq9D5SwY
8S+8unc3QoPFuy+P9Srg98Og3Q==
=djfX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => '1a34a09e-2201-43e3-a203-d024bc02bb2b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgARAAhZQ/1bjKjFbb8BkWXWKV/ab6XMQgw2z5UKZa45pO1mPw
9DH6j1KmLFeLSGFE+2E4EBdivDLfy0HAadDr33EW76scZ2pU0KwoVcL+8NLPjG1e
OK4kpQrw0+zBN54XYzaO4dDoyhZHlSK26m069WKo0QpI7ihM7daYE16oFPIaHe9c
yHMNHWov6FSQvUNbb/VdW2XyPi/dLe4kH8JR84cdYwwWe5JyHUN5XhFGl3ua+dLa
tTz948MWYVSupPSwiTyfOuLOvIsST8YLiPvje67WGRqdmo15/IsGCSpBDNdkUMTX
PFfeVI8hF+ziAi/UbiRUvzLBJICHyMZmusRqeZpV4YMvyEdaA7TvtRgRU/9bL/FW
nwgVQLVS8sLIjxAXDFViK5D/w7eWxCGnzDPzm9SAq6PXgEDnO0cgeLaAuI9xwK/0
2g3veBKX26sMXhdfMCcEXJJO23gV+QypyVtZsGxEjVbOqw4LoVsALfqpsUTX9RbF
IdvPOqENYirsdwoQevxaIoyD18h7lCF8BA5eiFSypUN8mGiUbNU9zLPxQjTAl9ZU
8rBkDYkm2bvhQh5NxOXEutKe3qR1xbWAikaTTLWB5Qop/hhrrFVGVGdC6TIUgAfL
ZiJV5PKx8MSYhAf2wuf6JcAIIgCGiHtIMhVwVdSz2p5jG3dMyH9MuF9JMH5NCQPS
QwGKybF7NDvuFGcjkpzxtMpMQz1h7SrSY6ySu+z19Y0rGfZwHiLZ0Jx9qTtiezdR
0xnYUgrOulm8IkCuigxyB6yHvNI=
=FF6i
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '23057d1b-93dd-4084-a251-4158b6ac9f66',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//Z91g8PqXuVPpbdGNci9yx+TQHcl9bX2+QAnSLapxpVOM
OhYkKHQEq1HQAAcwyNyWtyj9v4LFVi4gWT1ZxxDOX1R+Bh7kfnDCo2GmJaVscoEk
VkJe+u9Vmot6J0uY567fxlHVczjefFL0ONBcdZmixSHu/9pg9PUrVoJO2fI+1MFR
AjrpzgVvPhohG+a3XwUOOOerjdJFuXKRG4huRzX8TX7/tDo3V3SSOQidEj1j5ICz
0nEonJqp087hJMdsLo/9qA7WhmIBP37iNopzEtvQsUCloy6ED6qgtox38tyfc8mk
unUrn5IGHZCXcbdKNvu6ndYzbXQwVdG6y92+T1yR7X3uuaDiobooScgdd2Rgn2kD
QEu7SoctjD+cuoYCey8bDGLtkUvI3WACObC1sklBZfKE7qaTbTvySiI/OCeiW6n4
ZEYVUgQLU2gfAz2cUjHxyxcVKP1c3VdNZLq97J2keXEqWIRgcT4wI+HmDRDnWJeX
JlJAfj85KhR0pvWtfj22nDGRUPrcxLBIgOH/BWnVeJeJ5OWHqr2PP0V8m/j8XQkV
SeRwTUJ5jLQSvfSU1AHFbFk7TuOQgC5LdXC8o2h91b6T+c36z3ODV6/HBVqOiAH0
9UEbgfdnhjsFbabWoQ2+hUjHvSxfuQwZF+jUBMaFkLR4ghvCFb8Ju1eO3vRJFnTS
QAGI6BIMakkPek30CzrZa6Y16RuUu7ySGRonagKa1qJ6pyMqKbG1kaV1gbreeb35
kGXYBmXUYcmo8ip7eyxmD6c=
=svoT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '248d26aa-0d1b-4134-acce-fd3751660e95',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/+O1bnZj9VQi1be0ujy+gBN+M6hIR/yE1rIa2zFyaWyiua
bvzMVy1b8iNpjE1zeXfOaaWxB3Qnm5GO7zc3pUqtNZ/GMuQPzIIG8xIMvpkSMMIZ
vM8KLkxIhm407qr+yUWgpXPJRqd4drI5DvAG5sOUBmFJg++4I1Umf0WEGarKv8FI
k7IemQs0Uc6EsGgRA/NPrj31rRNzlUQzRzDGqxSK/EvpjRHQ8/efQbGoRqHVmQ9N
gPTM5eN6rAQ8r8oTcsDPLBir4JZ+JlhzHb0MWkRi4RlZ3EU/X37YS6eLjY6ZI8k5
/IASngXiDtJ7yHw4Qi8nKkOlS0njY+mEL4z2BwPSF51tus2L6KpAl+Vxa6ONpbAn
DJkmXVyEYIc3U6FEXHz5yEd0mPyYB78W10dvDl2CheuwZ5uF6gtocATstHuFx92Z
57FbrCFpQUrnJVlnHmDXFNxTxmZDIU8ihLwoYKqv1ZyK3dWpC50YdzfioHzVZ9xe
3ICzBNu0Z8mNGQm/JL9AzBZsP3BYHHbm9rskjj0rqd2Oli13VD50yeb3xwcRgQby
H/TKrlMrLaq9RkCntI1k9mK6Qctf97yoL1DGOzR1rHHHfyUhkz2AfVtFjuGX4/C2
fizuiR6KdW/64xGvvGxHZJRZabJwM5pnFUdtE5Z6Qv84LVb1B2t27AtvomWiD2bS
QQF7N0U1c2SbV4WhdHr+AH+jQF1yWs6k9+o/CRgvbzaUDrHPbQpFR1XXHMAC2Qr+
QiVBUQ2Pm1/1oP7rhO3n4rB2
=7K+Z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '315ff3f9-c492-467f-a35e-8f296f58d48b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/5Ad8NmHUv58yCtUL2YWCwRa3aCtDEhQ/cxR83Oqp3NGoV
99na1oTaalPDsMRBI07OD/yMXNz6d3lx13et7dKQmk6JHJChu7NoTKi0WGhzcTy5
x+cQZBGa1GPQhuq3k4YJybdNmi4/ZjX5yYR/XPrwxng1zqpNgtKB7rvvpkEJ96xy
tW/RIgGSFgJF7V2ZI5oy50HeCI7gcXq0GmJNdMjMkZpg3M0r9JYweUuzhRIH2iVP
I68yHzbR/QZVZPqTEZ67IYcweDZwmsfvwbe6YiWOwDRO/vkxceFI4EqKpYBOdr3V
OgynbX2YsFWPt8xNYvvoxbI9soljfet7avdErOxvK/klP1Ii93a5dtSKoKVy9iG/
JmoFKkOOqNBD5dphgfnbT/18M3tsPmaYlcAehw6YP7o6rHZOFw6y4VvUpQXj7+Vi
X0asi9D0cO5FKDjppq6JM1eO9Ff/XBIyx+N23tI4Zha5Fbtl/vOXy/XN73zmVceU
kBqV8fRUwgLAXSqxSh9XXPvO9ZrHcYG5fKfDS4FJ/CqQokvxvoktzGnnRjdKANxX
4poALWuWVnbK8dOmd8XyD3cihjLrUfCBC1LGPYlVjlKwbbz4xNupGvDUNemmcwoc
KgJEELgsu6SqmtyMO26hpml00TRJ4ZdS1MPc+nDOfTYB4OWU+kah1owAFDWqMQ7S
QwFqBXI5Cs6PaHfvM+dzePA0ChzjwwZuDdA6ZekkCa0+4GZ6IwNP+UBb/y0D4onT
AYFi9KN6RtfZuKpF0DWt6RIEI/I=
=/CMz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '35b0833b-d4f1-4c2d-a0c0-7502099fa81c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAw0P12ReHhxtAQ//T/M092HI7y21J3Pue4iUH3xwu/HsBGgy+YG7OVcMDnut
0nEVVxeEbvb++2OYhfBy8DcWykKAPdamp6f5at1ewk7Gexqgy/f+BXpvtVHIipE4
Ef2X4fZ4CQzjA+lMe+11MdbkYy1xprb/9bpT9+3W8FMJku8C1DqM3Sy28t/KdZvM
YKD8sRGB5eRvAnwGKQ2XGS8XoVhbYJcmU+F5pWG5wu7zoTTn7OrAvC8jodaXKEho
+gdz3+o1T8hmiK7L6r1C/H3qyeKwuQhzFfuLwE9BEc9vpSasE4ZjcJnPEJuvop4n
p8kWl/bznLTOPW4OdiBORXqEBLEnUyM3S79HKP8p82xTBEZVkqmB4eBQz+wT8+2p
TcVE00FPxV/iEhAW682DJBn8RIKzA+DARYYyu0nAJtDULTf79eMBPUZmdt3Wo5Qr
N0qFYnmau7/TE6CkDWKLxmuBdOlClFerMiXs8tO+ArmJQkW3qrmDifGU8HcWq4B3
uZ2e+Ama2EW06+xZF7zP0x+tFOaQDQCv9oDBi5imkhAap9cbE2PFMmGOvOANI0C8
o4zd6aPfAtXZC+59/tVa9MdP9Gbd+lM/XjrOfBMkmH1yHX5LRopfR7km2muR2tTz
vw2FP2Eug1CqDr1AJCxbjwi8Z06hnaMH9yWToM9L0sWZBxdT9G1/GiWzANa1BUfS
QAFK8mpbaxvrS8Z9zmn3CFT0MDMRmJCw3TGIzSltLviwz+Hk5g5Pi0VwJR/9UMix
X8QZsILPcqCN3O1Hh68ZtuQ=
=R7r4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
		),
		array(
			'id' => '38d299c5-4691-4cbb-a858-60a97f711c12',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ/+Lpy1S9G7kZLI7bua1kSfifndbxsqA7Rf/e+hDpLFhv9q
7ZFBotyv3k6YgOMDHYiggei1Rs90//8WjoHFyQMw7GulMVtnFMjdhB2T4bNcA3kW
JcGGeXqIbjBwQrB9E9CaosA5sx7InXMmXzR2z6qxsLUkZAGLR6S97vKGHHqq2Ux6
lBeQdU9T0uu1YK5BoG/YVufH03w3NGG9bTjGfSvYMe0y7uW+gAq/+CQHDimtbNqt
Cymug++B7G0AIBo5U2RSVzECnoKlb4kva+g8i1KMTQmmoIKRgrh8FFZa7pAJTIya
f1jqDfWZs6sQmxLPE4bthgb55Eecq5SSLhKe7dbJm20WqY7tkdm0w2CizyqPlp4z
zivdfVhMqvFlKy/kuwc1s9ALloq1WUof2eXdXfZ/7yzmlggU/H1f1oDhRq09TXCw
y0n2i9RClUTLJaRDOKESWHs+vxtnlN8vOson9LF7fodT1CN7iK/c9zodv8P9x3EJ
LVFBSWhI3e7O2tsh+WVb7q65zpCJ4YMEYnwr3R/B8AGoZzlLDXIwBF/QrM/27S2h
Kvr5dZsV5fy1SoEwAa2Mb3OWdNjrANO+jkid9g6IxhlSybnvkrf7ItMzN2+9OPUQ
HkVVphmm2mcg0DmjEAPYzhge1IoQk0L22LM+JMT+MHMXNnG2KRrZ6M2cQ3FEmA7S
QQEGGj+nGIENDLgh4mWiWGd5+UwZ85jz03SDvGsDiEDAbN643zzMkkYl0aGx2/mf
WlqvfpQeaVRuJuA1dOPJ20uM
=AafA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => '3e2141f2-0d5c-49af-a91e-8288a5fd3a70',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//XZaNe+gyWZhIVZhnbygeIgp1zY/CLCnWihvJGPK19kAX
Zp3Qk7BJZ+DxmsgDWy00jlXVQUzgcWiTq8ncsvCnjlLOJJyCz+kjrdH0xvFdXIXZ
5v5Lwh/vFgJOh8J31e21uvbakKxSMQZj6sW8x+NoFN/Mrb/8d7VF8dNFgWhxDJ50
MAunHPNkLxObeN5Fo598xsAu5kgDlshKTCo1MnNFW43aiDOHNoQVK+lw5Q6yYHoD
rVkf1le/moKHshwUokVnXK4TnBzQiWzi/6p1/oi1xkV6bn4Xr8uY8IDceddUjcGf
1ga6bAYOCWN8BrKGIj97IXRLh2m0TQ12YH3NXqUwlAwQWek+uYOK2QGuWNbP5HWv
7bLtqKjcAmLYWrE5B/1RqdrUYa1aOPsIhhdqDa+AU1OtrnJ31WcOeGx4SBIe0mP2
VQDz8Uf834NqutrMzSCq1QHVu7aO8PUFwPt6sMf7xo4iwZU6i8eodLajEDPsi6qA
97nr2tEuGCKBph8Jm6OlH5PHeKbzGjrV1/hbUur6sF93I+/AH1eEdHOK2nMy53C5
AL0+SJZ/57KyPlw9xlaRWu1Jg537pAwnimrdXdHWHRQi1IVjRCDJWB2IxOqYiM6J
S4630Nj1HCrt0jzWU6CVDnrPD8IEbPauJVKBTyq5+uJMFxpzoUGEc8Ue7SZ5rUrS
QwFb74zBlyh+Jyj6jREDWcsMUkQVZMQv+muVQiaY4y3IzF4LlN+9850fJ6s1V10T
4DpxxHI615r9H6V6FAg7gA8RzqA=
=QTC2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '4aa00250-4bd8-4178-a220-72362585f295',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAw0P12ReHhxtAQ//UAYrg25Yl7ddg25ZXa25vZHR2ba5kJ24DE4ysjLnM7qM
Us3pX+7CkoYL5RgYvq1WNXoKcBHC1vGrJLlqiSgJsbzbRCwQ1q7AoO6COZZ9ZGcS
ncPCz4ZIugqfvH3a473K3A0VyaHrE5o5e+9X5z+UGHBgWiiERuX/5z24XYaBx8D9
mnTTyji2QF1HWMi9zTzDzQySjMvQrXAFW4bKl5/+tJrQBUWCFiXFB8g+mHV8tqFp
xtf3k+oNSCHyEtj7lduh1lwhZDELpytfGQN51IXna0/zFTWv8u7hX6vM6NCG6hNh
920dIP2AMLBxNw6XaEt1qqi/C3AQfFzJiWcWz1gdd4H0cdngdoQFP/3mBwZhbCr0
UyBqQL3cCucvqko+Y/qiIzMNqiU0qh00NwSE9f9v0Byi2snGAu2GXYWkdThQd0JB
J0FKpXmlcpX7K/FviHnJkL5ssDPVQoG0pPVBJVTXgtMRnGkz/uHq5001wpWxwltd
BfWe8xMEcn98gkH4vItyalT+wrhWkbVWSypqOKYz5W2ZEHm/1USoRi0UQltIV8cE
tk/GdIq8k51DUGzAi1DjR61bQ9aFbh+oBIlN9cCZxzjAg0cuvdvvwjtrYrCahpRq
hW65GVFEDNofm+6Xg0R2Z7RLSKy34IcJSMRSo0RMatuWgY0kFdLM4xKp1yT2Lp/S
QAGAPziJl+uH7N4Wj/Z8OoBmRqv96wA19cGveR45K8tXaTAsDWngTwnGiHwnWeCB
AbZY2XfLHpxGg7F5bCwh+7I=
=nZuH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
		),
		array(
			'id' => '4c04bab7-75d0-4dc1-ae28-a821605c7b2f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ/+Iy9vYzYQBKvPDc9iyxEYSIG7CkQ7JszTTvO/TElFX2gK
f0Fo4JVOyCyBS4G73cxeS+zPl2/l5Sn2uyq4JFaemfMnuIycQPGfLRkiLL8NdRQX
1WJstwbuEWHxZPREOFhN7Z3e6GpyrwqYbkqi6OyFdnnH7BHH2a4detyBC9pNx3Tc
VxV4kE2NaYzeHxo1d2CCKSQNN46mqwBbxkN7S6VYCpfu8kxTDVmi0b8RxAa+8dfO
bZMfVUytWQfXCGQ6v8LZyEf7JRKlaYcb762lX7ZahWJuIXHRnLcMp039gltJOldb
lzTOtohXvSgJKgXvUz6BumBsBATX22LTsHwhacV9eiV51cBxLDZgusmN/fA2uTd1
0w79wQ9se4Mwk7awNtRA8qEiaW6rxBWXRacVVytdt0u6/2sypwIMTU8V6tZHf12t
xNgqFxeAs0xVWxFIzEEck0PxYauhQ5Wn2RwYRQwD48X5/t64MGolwOwjMT8uk8f4
bV+/1TxJiww7OzOmpuskj1Q9toG9hiFQATOr4J2+S4ZWGw15GNsZW9+kwG/RyHCe
anRZovsVkHu4p3iUclRF7KD5V86w/el/5QXvYGrG098EJrW/UJlU1KacEiNdHBDr
jMyKYyAeyo5jDPoEhFMCZ6BQRO8tX9auT0lEBDqq1S2reqx3gfmnc6hWaXt8XCnS
QQHDyKMLRvyFhY5PvCdlumUOZ+m88HQZ2eysWU4Cd8yfMFsY38UHDhfYJcETmxy8
09MxUTwQN6ysYpq3vnIR+hnB
=p6lo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '4c3ff8f8-e91b-481e-af78-b263b9815bd5',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ//VJ4GhhWfP/KvgdvzgPO9Xjdz7urGXIWSq3UHY3Q52FZn
9R7nSjtOY9AhdTCggV/pEYM9jn/R89A5ohlULWKrr6xJwu+Hz6+ZmLDBgTqKws5Y
HsX1/leKH2nXST9gCm+GSCfOJ1vyUcna4Jb2kK9DgaTfLZ5oCCdoonEQQUsJ5Ktf
G2RHnSz/Rg2YDmKIU/G0XiAR1Aw4A76rJhXYp4AfFDfCoxOTYMFM8wWCnVnjV2pP
d3QW8kx8XxYbMNgBE3RHKitqA3ZiUal2x0TISPNsxdjRt0KVg1pt1NQOL1kr1Xkf
vJmPIqmQwZr1wuXu/ZUCEz+uIP6MId+6ruA/222YDqB06YltMNTDDHQ3Un5jpymL
UR6J6kW+y+Y5Zp6L2K/fj0Fy8kiDeORhJcTQncfMb841Ac/UVnQGce1qPtlfdYAL
6TpP6BKfKAZ7dvp/CM6q4eryuEzn5mZ/v2fX19vX8VKJhkRSfbe9fSFhWz9SpSv2
tf0yioHUtLFBuvAyJmIkCDLNEaN2ZZbnisrvLDU2fmAQ7qLPmNBu9hP2PNq6LWbO
9SftuYkW3KpF7KfLtwoPH+yhVjNnxrIn3laVS85C6KW+8mOro0oyt5CSPe6CTja4
ETQK7zoYIAQuy44aKU+UX6zYodgs48ICuJWc84nTXiyvOMifp0kjhvkzyjUFZg7S
QQF0N31HJVVoJRdzbJd4wzuPpYnZTR9toKt6oE+OUSNQVWmqPN2UPuJTrHoUDbma
ARQP2jlH3tbFK/yH5pViZ1RV
=JXQK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => '50e8a51c-98ae-4246-a835-e70249d7be4d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigARAAnp3zkWMMRW3tzS82HltZQG4sOreERYbvvFO60hH13AMT
S8hGve1N/NU5YJxZ2GJFypQlphUBIRbas80xV2553yDIWqe5IWCyqWPHA+LO49pO
8OPEt+ZZwqsV3WWWe4DsJCGMk/bJzC41dfucN39hMiPDZJ5CTonSqGq/VqWAdexP
HEpeI3DihHnZIhcZoZP74LoRiSR16Pygnz1T2+IhOmvF7EekUPl891zClHHgo0DT
4DXQoqstA5QsUD4EkUEMOHvuDHRU2Lhvc0uj45Y8pkHbFo92rDpwmYMZktTxTQ6n
FDuxo26DDVnTQLXmZvYq+cFWEczvLW9wTbVe+ZahUOHfqEMaEvHVIAd+lvnJIQVV
uIQ2Oj4W5FcFvgsL9b1lGwmbA8zAqEhAXae/egYJd5xvjXQcDuWXpy9/8PnQaD/Y
hfs3u8LGSfbwHUH17D1VcQW3hmjxzHB/SkOybt5SqO29eIsZoLRcPtA/3PBbUo65
ui63pEzrrMqGFLTzNhTRpWNOPAMlljwhgx4M62jmaBUTLrUbzJlr3H8zZ6NoLIvK
LmuoNeuE7uBhvMJj++87Q1WhRSRiStEy8EziErqPP/ll7VOJqq9xmmvykTpIDj1a
QcvZpcAJ5UDfYkDpbqGH8wba/TsDnXbuFBl5feJZ/ZKcmgQ9VLy9mvSWXCSbARPS
QwG59vP0BGdEEtvQfZHeeOJiBSLxvPO3dlfi8F8nPqZtIc2mPYjfCmpX4bty57cc
1Aa7Pd6zoKRDPHahwSIJnhEHOks=
=1QnJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '52569f83-a955-4670-a49a-a9ee5293bce9',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigARAAyRMe7GOMDS4JQscWbJcjvoEyeV/EBA4PKuY7/BYPS1mC
awffETlenqwUMx4NLt81nctdAJAmqcblGQlt04v6p0tL0V8Ppctm2CgYGkiLw1I+
/Ef5c071f58MvXzljMxPbPy1wW0SdOIBUA6/YfCcrTJ8E7SGPd/uBngQ1+2td2Ta
D9guDi3GkISdtvbXkn72eUE1t3pwJ3cOIoZQ67fwLn4sVmdUgOI8XwgYbzPL5zj7
TBeNJLkVqxvdM73DmrK+/RsdKZ86h5v49jPRZCzSyyaR+6Q8UdSX18Yf3mMKqmr3
B1GSS06cqnlK0grQ+YELv2POrW+lKc9HmrzVUYo4ZAPF2KcrJJsqrKBCWhELdbLA
5OYSnjR0HBIwYHmpNtzIiZzFqijcAzFxane7ks2DYNZdS27bgabhNz/D2cmSdRYi
chfLCIuHkpmLMfe1zKcHQQRsZA39JfVRqBnSFbb0fVrrUjBzdjweR3QYnB6NPvj6
8EMMPaRpFl00CvpwfQ23v7RsvuAXpyCFr7BMquXJuGE++ttCIIPxyI/qagb6zL7k
vtrFycnOHt4HwWZN14HWA7rh5acseEJCeTehsaCL0T+rYIM5S8NWHgYTjfGPZcYN
4yW6E2I1qa7g2oYEUyZTAP9a76A0P2/wL1VaSuyU151sRcsE5YEtUx6LYO0kTNHS
QQEb7/s06FiS5Iv8/BEiS/KIzvilxwqFQSuz1RlAH4Lz7NT7zmlgkkef5wRC3CPR
GTgiMZ2/TCGEtIEz2m6UPtHm
=yNEY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '594523fa-129c-4a3c-a6fa-0c6ec2b04eb7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/+JAuezhSYDiNjow6tmfWmliytrGh2YgM42jvIhTlbZ1hk
sExvZk2bA1RMDmwpDZxy68y4b5NeQAcmifQunyXRKTeN9fYHu572PdNdH+2W8EwJ
s/elRnKwDCo+0nPD1G67Bb0TUwMJmrs7JbQAfFGYppgOHCquoGZL1xXosHBJNr0q
yEzezpPGzP7RO6rdfQ+yJPnqItPaLnVw7rkvAS1GLk+jVIyJQpbp6dl+VkHSnnX2
liDAuutdXmLM85Hz5g0MA0jHuwXEKWQ1B2Rx/fgKj+E2ZE7OWJaPdJZusvAv+CMu
GgkYVb7okTxnNLnv52j9EQJ9qGmhUlmY5WpqX8BNCjJpqOAwlRse0T1Hb2etPVT5
ZrVmP/zBfRYYEXE4+PpGwo05c0q/5e/8K3f58wVulHhKHnndv/qcMP79vNDP7k0w
qmH/Fvgzhv1/0f2LEfs/YUDFO2DxU7aby6UOrZtQTTdqn9gX884Y4oNwgDfAmTyM
4zWNgo9YLf4Es4OQaprHsnI2IKpEZlEV2fPnP7ChbRx/Ut3dtLxwcIpll1jWfcjH
xxMOK3s5pmT7j66AiMhydVOIZbbIhKKcO76DERL90WnJOTARcjqcud3DPj3jjVBv
WYvB7xEig2APGt3jWqu1Rg2g1mzCtVEhldZLTpIG9ez0R094/tLxrBgCJ+y+jQjS
QAEPgTFCI/0ocogFvAWk6SRrnCs1uYj00VheP5yrWF7XWsWtVoP9gbTM0JVhkLq1
VW81G9i8GY0BIzv6hcoGtZY=
=xiFL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '5e7850bc-7867-48c8-a491-5e61bf3539eb',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQILAzlz3zlJcBT3AQ/44AtzsSpIQW8UxmksZULQI28f8U/U/aMrGvt3q1F4NW/s
D+lwu5bb5NkTm/0kFMi3aQ3ejfNTSja6jt6Zml7GChRLhQaop3hDbPNtg/4LmDvc
i1AIaUY7FlIvLayaCukdoJffsHctYjqf9eJ9DgEqWDxONRpvVPKb8Mk9E69UVLln
WxiPgZgPmfPNu0IYXF0Ep0jsaji9+azbo6rwHH+DycVjoc6DNinN43Oiw63au9LH
kVo155RHbrjWqIEZq034h9d3dkdicC+hFrLkffbvkDcYubKwOcLCqXEu4ITRw94v
qrTMyT3NgeHADRtrKW5oi/mT3Bhd63Pz+pcs4m7d0//QWHiM682jy9xen3Rp++cN
/IWNABWGTwCYvU7tA7HpDRT2mTKtx5TFCiuYw952qk0/jgeZP/e7eruB6OC5zugE
RU5IxPUsO8kxJs1NpEqCshmDpygwk50RbwJ4Fuegs+kWUV8g3jOf6yZSGKvznsEQ
BR+NpMMnIO5PAHc2NRsr94zuoNo2yqOS9FKqHGpykVwRKXuQpVacJ8Dt7kbeWW+S
913bkTkCc9LjyODZNOyYObugly3NDQ0m7JPkLa0lWV+jrepkR4ayHGFjA8UNgBQV
oLgR8rjeEhJXUFkXFjCINuVsLA4iMJ5G5IIk1EXGvu3xJQdp04ELQjrxLAUC1dJE
ActpmYIpkmeBL6yujI1XlLdWw2wNdrUmsxgsmujdCufDgwyK6IW3Agp+2ByCXvv6
Q1Ez/SnzqAKGNQG0O5E2dg5ipgU=
=EdiR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e'
		),
		array(
			'id' => '73538f17-c52a-4553-a59c-310b1edcb33c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/8C/fE2pJ0MeFzY/xUQJgOHlh1eH0WQ/hGtBUijwn+WWe3
JEESjHgP1SS+5OgZO2iTa5W6qDt6BF2asurp8bSPDIk6pKy6yDjGhoBQ9tNHg3rP
8f+miOGmnl0n5FHz8TyjnvArv4clFHJOZ00Ql4Df+NZIlGONOoTwTrwXlzs39dOt
eLxsMzD5v+YZFKj9VfNgfwEfDsMzHi/FhoOVgBC8aWkwgvyiG23zn8lAhzFv5MK9
uA8NCkO6lUcbEdHwdMlI5hQ6lZ0c3PYOXgVP/x2nK5uDeGn6YqfyfqUVgzxjbJoK
stYV4A2+1O09K0bcKURK2C6bzs2ZzfcK++pfiTLDbtjNiU+XN+mVQtc04dTETukw
47FRo+5vwbcpSo6G3brr4xtwocd8O9TPu7iHmLIv1jFFr6YyDBxDTYfZZGjPyNtK
703qaIKbTyH2zPHkl0OD+qJ1b4WoKN+tL026X0DisWw6T7Ni7X6Eqb0duCctmCg5
zu5Crh4qzSy0IxL4I4N7aXPm04nfNeoCW3D96OikqrUS9Vho0FuGC/Zg6Ixjmdvg
xgSDBNH4nv1xtr0O+TTBtqLfx338RyFLtCbJFrWWwsA96vILhzfJRSh7Ko65UwQ9
sgtiMGX0vk0J4NfgECaKNsp0OO+r0engeeTs/6+bVoEJh5xgRZWavMACVPa3xCLS
PgFmdx1ACvoWjoaBUHrzJtuLbWMOGB8fdO0SkJ2ZJHL5OU3dB0Gz+Ny+TYeBtFXg
CNMmA4UuuBTiifsHBZP4
=AsZ2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '7907aa27-09a4-438f-aade-2dc4dc0f1354',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/+IW6jqLTy9IDCFx7lA3r9QYePIFJzMoP8SPYhUEVsZFtK
Fn8HrMJE/oXF+dPkWSTOkiKL5l/nmLFLdIMMRi/zXClitN/WIB+geG53Ld7QQCmP
hWHrBICBrRGefZLsv0QzfFfaNJgtLk2FJa4n1rEKG2ouBdvhHlkjxS7MmztMCDTe
b0PykZ4Iu4nUrgEcJrJ33YrO95RUkl8x5rnAKa0koEiq7gUitROjiV0G6yq5Bbfy
UbugRTZzOZHI1ySt95dGIveSg/CmT/9jtXIKv+5BRg7AKr0W9tqnQxq+EeZn6l6c
L4EDTrsUKKOu/Vovp+qTYD6i+OZ57qvxopH8LHYUUldmRmPFz/7astPcFjAlG6k5
j52CeaUREnaWqJtUIz39UOn6e8+0Oc/jdhkDnNfrKU3Y4sZ5GZJOubn5BVCbxom2
/aceSv+f0NKj62d3NtZh6tFB32EsgVK6LUqtPYsUO32mioSVlSFKVXNnNwO8/WkF
1hGTTFHMZohVTQpmPn7UgXpCa06f6w5TzaTeuCZQ8PrrhARbxpeuhSGbstZAlknd
rzw4Ze7GFgOSO37RI0+EFICueKUStAAh/oIQZ/8EWO5lifrEr4uTjZg7Ur//n3ye
nSbdYuy/Pw8nb+V8sJ4H1vcyjf/0evMkKn4Di7fBG5JCS95u0btN69unx/lEdjLS
QQH8mk6Jw3JCZ31HuScr9GcH4nICuLkISstBfH9Bp3IGODnTDQtAf7ZBdols3g+A
SXoldS42xS8MJ/d0HgU9vcRX
=9VES
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '799362da-7624-4c6f-a811-de60d50a16b8',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQEMA3nDbC/XKAhSAQf/XLP87WNXyiOM+GjjDiLlD5Gwa4EEinEyG65UUAyXzS6B
7OTaBnbd0SQ2JV1iXOB6680i58MNLO5TeDhj/g+NgdkBMoned2xbIcXyMAD9Ccr7
ek5znIJyUZOfRsp4G0EC6D65+y4zqmsmclzgYko9uR59lG1C1tsCMCeWmjrwbhvY
2xXvm+9r2n/q19agSTbrLKzmUhtMZ/9ztQPn90x4s2VBTKNp6QKRi6/BUUpqDYRD
7muRGDj70r86g4JgxvnYCQfhZeVUAkSKQLQ59ApBlCO4H92mn4XROQhIGobMAieE
JjiAens7G33HNou8AN1D+bb8gdNQoLXD0NgaSct8ptJDAf41SjWCmmLgpMwUabNf
nQysArpssw3xV2eniDZ32O821lLGZVozn156ZbVlHUg4XlC1wY+c4dtBase6Fj75
wq0tZw==
=+rC3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '7a966961-abae-4600-a26f-792f8bc26230',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQEMA3nDbC/XKAhSAQf/eGIBBvs9LtZNfOq/X/RZs7UEhDD934vRNr8XgjchKAoH
kqxAgcuPtWtlYap4ezckMfiT3oHixLYuX8Mb4Bsfn9JM2JGqHMicKTifTbTIH+yT
9FZuCP4sOEW0KwdwTY8ltICPVDbn6+A5rf9FvySbVWMHQbCQYNEsRY7OZAdAIcAp
tjljDAcUgKRDsW19xmMXx5Wpo3uubhD9qgSpExXOxkikY1DSkj2EMHsI8Stf/L/S
wyDdvin6iFMNdcb/+ggQOoehby8jzkukLe44xCgjNlynVpuO9/eVkifsdYulasQS
ZuREb0OR7zqR4E1ufqUQYAqZwE1mQ8K0lxXsKrO0RdJBAVtHBHhl9G6+fpvqpejO
XN2hgEib7Cl0BO7oMx/ojTOPT3Os2Y08InQs7eT/ZHn6fCe7xsGcDn/ntBFfEUns
AmI=
=mg9a
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '86c06cd3-9014-49da-a001-d3877ff31ab7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/8DW7ptLQ4lxtoMN3R7pcxaDwII44TXn/+ogW6b2Xa/EXU
AinxSIqjtkPHR8gKnf7a9h9l+aNf1WIsgJKr7EuYW3NRm5NITx8tic4KrEU5JEUC
13tyVevam7jaKFUGGB5kQqNn+ZTzWO2aitVaQTcCQ6nnKM6MzYUg1LjDDbgxvN/2
iR/5H8p3d+pwqSirtxtYatpqyzX04dDVJw/+mGm5NoDuNQDbkOs2MtLg6fwyUQmZ
IZE41++JwTaBu68jZl0Ci1ttoxJ6rToK1IUM19MIwemzksYcZ74M6tA21NFEeAY2
5xsegxwGv6itfQZUhDzOoTFsZ8N5joTujZqIt25uFnTE5x7iviXZTEoKIBMI2ju4
TUD7NAWiTgeeP1ACkTfEy8RBjKgbZIUd4FNPeGfmHmMCQAG+RAriXkpdK8LGIDDs
/pTjNmHrFxgAM0OT7dk0Esao0vANil2E+q0dX4UC8bGY+iGeBIBY0jz9KtbecLcI
DFewAV707d2FtJ4zSfUdsBGYQk9/1pfsA0bKy2IeZPYckTuA/xBSZ+X/acAkaJXe
wTQIUhxnf2e3i8+0c0fbCQZfkuzeGsoGaZ7901shvyr5hvcN33KUVkhMGoLer4G7
7oKNmnREtHpF/qdxzhIxiZsc6r0Qmllv6Dqs66FANOZGHDx/uaLf+aoVUTTOUPzS
QwGY+62WTilBzoAPWj6jWyHPxfSwd2XYRft1Z+L6Kp/4FYSmcuyKtNvQegMdG1Cp
fWsaQo5sbfHdBAmDAGK6AVzLjcc=
=B36F
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '87b30003-45f3-4218-a618-89b1efaac578',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4e/DeCIHsAzAQ//Wyng24xDoQu6OOB1BmQl9VS7D6ptOre9+ONyb0Ll5qTB
Gk0GOLOwLasYSf9HeogMWwSCvBZwuF/3sHkODq7dc7P9dXuFujXPcVBXzCYehOHl
hN24Mr/ZTPhqKMUvt07cN87Mk3PtEjHgw++KaHWARVDM9Qc00qvKZUmP5EFYfU7J
JdEoT2KX3mkhBIdFHmHnO0ORDz/WfPcj9/NW4vnxzNaRCqe29234ZCqbl7/Dw++k
I7PwG+72Eg6dI87Jf0Je6i/AAqeiVxjTMtTgRKTKO6fcrWfOBt3Xygwe0aygzsqN
LzdZQ+jwZRwvlxu0p0hvOiv/8MB8qoGkBcRqOvoUsWy7SApZx1UJjvHgYrQWuzQP
e34Awaj/baQwWS2Ph+PHubEQ/cDcGW9vP/J8zKQP8KxqXxnqnvz/Ign//NeJWnxL
W4LO1wbLyCtfX4YkvPhx/9bdhqU7yCtFOwJDrAKVdmkUmF12A5NLNs3vXIyeQSxv
XY68KPhnBfB43WZJGY9B6bfH2054eYGCZYH4rdEuTuj5bpVQIso42Ulp5vHhfZF+
zD+D3ifE5zj1BuOOM8Ik7QeVH4Lv3juoGKJK5co7CAO8meQfD1xbqsbl2s+NiUOe
nbsv90osrEXQggisDEWfB2NhJgI7WkouZX+d1IeAFboZDmGhJieczMykfYlbo+7S
QwGjamIK3+ah0KvNifzF1NAFgy4JNcislK5oLwFfgPvoM9u4i5zIOz5QTgvkmJCu
6MT5wGbPunIUwRhCIICspj+NGf0=
=+tFF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => '8ab5a62a-0465-4603-a641-22f353fcf359',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigARAAxkudtyxni3i2Qc2ZMVjs6Y5gPI1+gSinUC2IuylnZtOH
ucNVfIDJPUDT2lpzNqTD1G2DUiKjQ/znK8JSIfO5tRDwJhBjDHBY00GI/KEmqPY+
E30+PyVZ4MWFufQqKZFZyxXs1eDo7+YGEVqRg7G2ptyxafEz5iD6r0ExFl/3v0D2
Llcr3Fj4tUgrORcyHq9ltEVaswetPpEdjk6/SQ3wButeVlX2W4O2JZmouUTPP1uw
zNX+qqJMmKHfU3H/7uoiHfU8HtUxkxZvGz3rnAIWDKSTQ7pyRwCiFhKIgfloRiXo
cvHzAWDT1kNSBeMuAyha8YDa7Ee7cWuF88UG3fwv7CzXJYgkmmnfvVxi3X2wJDGI
FTo7uPuMWsnblIynKTlMhscJHTQrv5qQPYggII9YHcP53LYhxVYMajGmDgDYpPXd
G8xqcQuwXuU1U+O9+ojplg5arUnJtZUZGm3v8cf9Jr4T8Pya8/t+ASSVFraRdLBw
e+35ejf89MkSYGwteExGdfxGWo1Esh1Ei8Pppsp5uTtBql1aRK7PSFGcac5KwHvc
a0hQWLrZDLOvOveiYy2qtT9EWFMmbYMFkM8a7XMLL4YFsguY4TWR9Bpl+SF3n4QO
77vVJvIwBhyoAAoSPSQwTYTjjw/08nGqjOg7BB0S9BKWw5NpRdBbQl8PJsYPPlLS
QgHPHGXTwuP6uSxyc2DHwKkvOqEvvE/a2hFgBwnCNCtKRrESp8WBRE17IGH7K3Uy
SggQaHNqORQ1y96Zl1YAagq+mQ==
=3MhO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '8c830b13-4456-4951-a172-da35fc9f30bf',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQEMA3nDbC/XKAhSAQf8CflGzweeAOCRwfP7Z+X6vfEP8ec9OmkZgqTNU8F3Ecw/
Nt1MsPcSo3CDJpA1Ti/MUZ4EjysdHyyMinoeKEcO1hr9j1NLIoZfZX8VpK4Brh0j
BWhGg/OoAbLA5EFb/YGHS7cfK9GcnsZXXcJmz7G3CPQUo1KLcBK0LT2ftK23pj8L
OdkFPkj0lRM2ZSrmNhBaTpMRLZ3zOAvSznB4aBcqI+wEc/Tu6A18efJS6rwDnPId
Cf6zomOqCRv5I+OIpm0TTNcJkb6K7exg+3tClq9lrbdH6Mt156HaJmE7xCTN+BBB
yK5yqoTu3CvFDyP8GKPk8YU6NJaVV9RvwZbAIHIGLtI+Acl6maf5ysyB0JWsAMhR
m1ugXxrqIJr4tucygF8+W7QrPLr69IddPwCUARklfJZ/evBKv37wzQr2/VxNihE=
=GLea
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '9e8e142d-a87f-4516-ab18-5ba9c4df599d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ/9FuwyWcY/5NmzKatyDUxJuRjEzI8jf9jc1fjDOiQ/7ij2
VJDso3RYQ2KyVgGk5lqBK+r3aAMHNkewLqbL1B7jp4HD3XyAOEwS08FCrFwMZeYB
n8egV/DfLbME91jJA44Pdo0P+vSRu4AtTxs7QQ3waPbGae/vek/d8/garupa3ZTX
NwQeaqOU9EwPhccXY8RQU0rCUSX7YwcQ96zIKz/X4/NJ1HX5D3e3gBgbi/DHOw7q
NPtucyDbG000ZTejnHUF/clie6FdxtLdierAvSJlgXg2Bx6yp4nPHWIrFEndaYj0
J3PG3bdie1k62O/Pl3el8chZN+Pa6DrAk0Wkmef0qXX9sJi/JnPcOjcTSxiMg5wl
SnP8GBsEsyuAiqDxtj3vMqdiBpT9Ja97oEXH0ZXpeh9BtKIvjuQW55TUH4v0kVtt
UGyrkiclhfuqIb04/72aS5j2xvTl4XkQJ6qlDMv8EK+8CAd7jmswLmOJMxUKFfd6
z9xON46QqOPlpiBwKYlBvmHRl9zWXrR2gfRGky0Afq/zoVm8TM0ISFAPcR1eqDbF
SE3shPXaW39XPjXqPJryLd96/OSlISFKdh83OtwdJ2DP8Uezo8JhoUyrzvuuMppc
NMoEOtyAw1/OOTojnuOj/i08+3XE4hygoRT/fzdnIxK9ifYX4HUuc9PvAKjNSaLS
QwG3Q9ZoYlFaWuZHXitadhZve3mrSDky46+wC7Yg+yWEapd+WK3zl23P6LIXeAp1
C4hdpxsa//qvXV8WsvkKyLMRXz8=
=kGPR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => 'a824f117-3416-46f5-adb9-fe90c047dca6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/+Is3ZfOK9JE3WSchCsb2EHoE3zluAA5+bHwrYJ1rvqI0Q
4y3d1QsyN32AQuowm17/CoEOZm5WyHo3/Lnr6LxasLQCXMs125dNhuf92sV/m2mE
ZSWGpYRgVKSXFTY97hMTLgugq6Ls7oYolKdbIVkeZSxtZEpsYq6m2pIZX5f/8OXm
ayAdN7qgdqyLN5bEViMrcTj0J34kTboazZLE0nH3/qNGR2yu8zN0hyzCw5G8hvxj
IMS8DKEVppNn176YoHF2Ws1NoF0Hp/6nyUVZvd//KigVyIvCHHZHW6toLWkHNODW
7/GKkgs77tN1ZRADTKwmSfqRdNz3GDUVWWu4JNZolhWMJDp5W+vJ6SCox+iUfucY
3bkX10DoQ01Mw/W3nOQtKVtQqrbGjDZiutUC95sLBwp23fyZVQwnlgSTrCWlGpNi
PLcZxEiBT+94aFdzqZhqsY3jh5aigudtYKjTPMImn2RU9Y4GOK77KRUnl2lKJCmP
FCwb4PtfXwy1txtmmBMHOGrTZ108KdBCXwFu5AqQXsKDR4SulZXoZRV+8kUdwT/H
HGwrWMAd2BGjyuYB+gWV/VR28lDTJtCmDWQAXw7ncJMj3byxy4Mx67yEC8a8CTZK
K8L9kYNFIClptZyLOH1Ovel31qMn7ESF7AcztvQ/dAOrYKqevIPayl/Qh2a8xlXS
QgHMOTkOqbIQ6PuQXKsS2zmQKKt4duxoAYHE/4zruaJhwAmNwwKGOeXH+sz2/Bxm
TqLVwkx4BO9bKRV1YNurIdAFMA==
=jU3l
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'afd67e8d-00b1-49a0-a80d-10e825b25967',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//c7kH4ov4/qxZfZ50aRJdFHK762f5mflPIqVDIElnsJvk
Y/3jrEuERqfog4bp2vAGVRWeF2l4EkT3M9glz7k7WX0Jdu9rDx4KixX4J9EOEq6B
Ke+WwtAR3jX0DbnYKqHsE2e79aDeCR446K6HmH8UesGPJ4qnSCMw1jSXcdVWwwpk
Z8wvAblT6cwPtGN5Hmvqqv+8uvWi+Ksh7q+ttnHn73uYOhaj3DZyQG/sc0f1KYUb
el58GL+78X/HJsS0KbqBZkttwSw5D878snY8w85z4X/cq52wnEQbqthz/JnjmTlU
MsSonA/zyRLf6c3K8FBRMmaP99zS/7cKdkUpgUIP8Xhhl359DmQwFbda4TtYMas7
J3GnP4GyeobjU6sLdEH7aip4GH/piSnbOYh3Ii3TD5vaK75hhEReUo6sb8MmGK49
k0WPW9zag0koG34+aUfzGgGixUdAR5mB4UUnHpZ4Fvsp2WSEAmwZn+fvLvExZyCi
gYhKuZcvwuwsOHqODUkR961zYkRKmr6cuNwzm2q8bV0kdflRvSep7wqlsJaEHs0V
x+l63OLff4mNFpHYXYXca3g2fekP8XyOzQx5e5po4rpj+LOZd40jYpsERqlHZsET
UltaAdFNSTi/9j6G0/7oTjYLm3lupjEarNwckcxqZjfoQQwnl+9EbIszjHQnC0zS
QwGZd/KvAJOc6Z826dEAUnpjolTvwZ/n99i3SjF4PErOalXMCxCfKmVT5ouHqx7A
FHdakbIG+g4uAdhusacKO9a7nls=
=nDzn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'b4de8d9f-181b-4bb7-a2f1-d2f2daa92b76',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ/7BUta+GJRoUcG/y/dDJN3OXAzncRG1Ipzk/TCcDGinuGP
1XrASwGapWcWXvEXgINHhobHB551XOaOw9SKbcKbd9Mfp9bSOKEdnpEvSm7e++qe
rO4PeMpMeGNf/ueVwSBARWeIFUNiIz+D7ksL0Jw8jXHSn9jVnw6DRaxMMMUKHSg2
gNQgnEprPtQslKX7bqTrf3Y4PibwNJf3+9xE4RJm6LVVPAy7eFgxnd/fylyRp5km
/8bCtzr/LzmWZp7oaqafItFfZMuHBTbnZsrUXedL11cW7kjzqGSb5zAGLt+T2PEh
yjcaqMpkf/WkTpS96YJERNL8ckEAJZgW/G5vOPcR2cexnfd0kfIL9O3p7Y80JeQb
uGpBbNNXE5alLIzndYTqCqgfRqmhgJwIxfcBq1Wz94pqZmYeVlMmz50ICrmCkiN5
nMIi4kGnaVNtce9320JS7wl1Pw0W2PBnKYRBiAp5l7eclKD44T2vEiQ1mtgLQEyH
WtINoC+DinTaUk8i3KDRGY05dOurEKOUso1wu0LmLFSt2/XGn/fl4UwNY2khlCak
yzZXBVlHDZkLjrJdd5gRkU22ULM8nYhBxZYqJ5rFq/1DemotCL9XusVOux0511Yy
7HKe3ihgiphN5ciIyuPyaDda17Xy58ptM80nJLsVAFx3qBgIZVntyELNQ1bOdtTS
QwEoEXsFkfOVhabnVEtNYEEbTbJ80bP7eAkxDoUIztxxdUlfKep63EKhSaMRDFr3
TXWG5VK0RDibkjxIZFYz8T55qbY=
=owmv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'b7713911-5670-461e-aa81-8952a709ce1f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigAQ//USpLyO5JjGPkRfKJh2Clk4aAAaA3OIu4VJrxdtWyaOdv
vjViDyYAoXFRnz5MjRN6zu3UqnOCJOGsmdTn6TRUa0gueVzGVHoa3nNTf3snd9rI
mbZ7pHImYO12Bm75jPgCmPWg3sE15rujUGrRiHu+IrdFxq1yv+FKCdQc+4UGI2c/
SK4wABG7DP1Y7EgJ5r7Z2DRAFuvpMuaVG+5jCZHr3NelnNhVFL1OEzczzu9uo+BP
7oAdh1nWcs+KOpTwqBdrgnU/xtkyAJbasOZfqiVNZv10qq47Hn6CnDJFWS8kbneY
WbUjVmlwaLkMFOv1LYnXEZ33Azw2sGJzKD7/Ixn9XKqA9c4AgCWqA9XSl49WKnAr
BwDVY5ymB+P4O9zboBOgtTkQG9IPAqfdV0AgBKvGqwFyvbPzWtiCnxsHF8u78WeK
IiDHkmhg57jyBKIhfbwEkPILvib1GW2hu6BciLRFvMuvmyiimjAKYHtDgaCeZ2Q0
WjDk2JBrHE8YTeWv0iSr0+9piqdnlxZDw/uvEN6gc4bsEec+CWiBMJqPpy5zaFWk
HkuZSBV5OBE4LbQKmJjXz9XVAeQAUBCz3ieEI7Hwz8exRK9u/tWbWgwpIHquc9ov
PYs97jHBUnKncwxhquoYjZre8ke5JohvSpff+UH30fI7NOq6KRDbpEE+CnMafwXS
QAEARFGzVXnpbhbvnyzY3pBzxsfRw8emVpjMFenpiffgeeSah/5qlzPeMYSaH9QP
8XXWHbP99ufWOg/Ccud7tqI=
=+PYp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'b9fd6f36-fc73-4c80-ab46-0bd31818c53a',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA+p38wQEIh7oAQ/9E9i7t69FtMAE5+DxWsFt3t6/3jlhbNSXKjbLokGaYAqX
uv+dUaEQOQW95D+j2jNsFKLgqOVffJDWytzBEUnk6WQCcEsQSzBzUZF55sFtQ0yF
KxIMxGn6himM98C00lpH7sDiWgYOIsguK1NTecYmGsgM1BVlcgIWrAxZ8MgOeZOV
JpwrhXw2Ra0oO4EBQfWdyxEgeigs/pS/3nDf9gInZL3z1RDh+2gBw2tE4Akry5CU
3YIp6ZhcWleEI5fO3+Wv/RkETyy8b8fPpHwjR2Mi4lbHrHLevQ1VpCSwLXAAwgFG
6YFVkcNgXinPzywtakvC4d6B8zP0SyY2MYG65/OvKkbZoKtHXgVfA2ItMIMD3uB9
t2f7XVprQ9q1uTp8NL00qdlxKsf7eZBtSYryKVnffcxha3AgTnbBsUZnjIW75Q03
GhzlqTxS4FLFFjo6UyYnkLz25TgUKdZOatSHvce/xk3h/LEy4sjZIpDv+hrwK9BC
X2/YZdKwNA+zuXbQkoI9THc+G0jJoTFl+YD1ndzWhFSIkLUO9RKZ68VTItR551ns
MxmmsPACsd77OnAyqJYq6cM1Zkvjof+OVHZAW7g0lFVxpItkSJmuZr8uf4xN11nO
gYdAukTGEszmuEWB6XH/ts3t/oepHNFvthKjsLSjYuq+50bx6ZTtd7BQX5IMhxXS
QQELdmF6a3+70z30eS8o/y/wZW3dRhEj2JA5hYIUqruLhAruOqLYR0QmAdn0DQQk
Dz2U6eC+crT5ipwKEcAuaKbR
=zAcm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
		array(
			'id' => 'c1d7f922-7b6e-4656-a884-cd7d8efe59b2',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAxkA6B9Z4y2kAQ/8DtZCH9iyfMT1c28rRclX/iQzVwYTktuihZAvX2XVpSoE
1eyWQ+2IfV3CMwwP1cm6lgXNiPGw1vda/C11hzswB2fkgO1nzhW9VJuBiYcZREb4
OLqr+x5Jcp5hQrHyVk2u3zBtumMCcDz2jfqJRqk5oUDox5uYubKav4pq5Cit34ce
A9KPpldr8LjxsajkGYXv6lTAUfHPuQ0OoS2WfzjXCbLXFSf3+YyrubufMEl4UYG1
PnncEWNJe8sFYu3szFFi3qYE3826JoZe7X+WsJWHNupkS1si3vf620y7VQywfhep
g6zj3YcrEEa82KaXwVHQjkCZ52OVx1RrjyiEss027wh/ZbJ+ziRLN3FBdBbQTawC
vMB2c1gRZSJ+hqhmW52xLac6nTzJyjYLHZI7UmQRzQqJchsfeYoGHBkKKGWWwTSN
HGJBsDhd3O5qkwviSqbTc0xT6FEpyNh71GTw9XPXeYG/dyutgo3qWauEQoE1RLJG
KdN6qKPidwJbhGGLfLdSO27zy3xma5YtkrAeShamfd1+JegrGO/V9MOxqhhk6yy1
wq8lzjRqAunKRMa7gGGfSZttRR+9gRjiD0X3oOk0syH6/cGbYix/eNqxjjpj5ZXi
Awjwc02FauEfQY6Ok9OwztrASqKbrbZeu4wbpvgRwF4NALjJRH0jEqrHxUkVWG7S
QAGJ6vo4ecXqnC2EXsDN6bGWGgw8I9XdCK8yqxucWmifl/xOJvtr6A8+9+bgBoIt
oqu10RC7sLkpW9qRkFVVFwc=
=FjtS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05'
		),
		array(
			'id' => 'c3f57103-8c51-4a7d-a24c-b147eb0df933',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgARAAh/n7FTvp9pqavi94sLL0Gqc16y0BcjsD67rGyGwzzJoT
NScr/CYmhsmkVpCGe4qdMrXhUy47i+DOYvSskCmWAabw/FG/UqhIT1dnmOWkRrwN
/GpUtZLzpTKLv0QThy5+3yQxDeQyNiBr+LZZj7Xsd6j1QtZd7u/Ud8v0FzWW4D3y
E3O6MyXndYbA/5SpLeMcDny1hrSdTE2k2csk22GYnpe3d3lW1oeSN9p1gwEOEIOc
DM5BQL8UsNJyKCIGcqZBHgnkLm8Cl/UV/dt9UDYd5v6psEGMkQUK8C4MmmtyTUha
Z+XfhbC2NO1UbeVGPdF93zYVop7TvApJ/tJJd/jenudMevXUyUoGzjINw0i0apTB
XfQ2FBhWLBAAczTI/6zVBDd4jvWaAYjnM0BAgYQlTtDan/M65XQQ2b9/NTozJpRr
42mPajUIy+v8EbD2rYmdhGR1awQgI5xkrUKrSPlibLiRqy9lsed+XyJDPrrQOJ1B
ENP5OysSXxUMxJ7FjIGMdZTPobRSje5Zdb9DpKYSgqRAtNpsCEDUoMMfrBJTc2UA
m6A0HvR4pUqVe8ycZ3/LR+iWZsH6GEQWY7M5jfMWCX/x6qrBO80Bt0P2/g2u2NG7
y7n9KquS8adAo++0x/HOv0wUTr4bZ+R+Ri7lJT1Su71ALWuQE/3g79tHFJ18F3LS
QwFKWXMJ4tD+JDisdKxIuwEt6FpMe5b+RbzyVyehldBwl9VDQgHfIGj5f/EH/bet
amU3hk/g+NKYeWfWEF7CHcXwPmE=
=AGEn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'c6603503-385c-41c7-ab17-88d89fd83b2c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigARAApH7T496PFmzknyPTEyQnG2C+Fc/hFXsk3jBVw2LzAmUt
8uSnupissyauIw/0VKI+/dpGUr8KT+go5VRYrpOW0OfCNE4SYlC16N5EfnuIZ/ZK
OB0gLEyWsKnukchqXw0hA6hxZn0CJfbnKjwvRznxckqrGFLU0ZxsVL9EX93p/lxy
PyT0485y0gHMpBNEwKEHofB4gGypQvSAhvNEFab8NuQkSpOaqt0gtLsuGr5JC2l/
x8hpgJ2UJw3vdYmT7r1yEfYnG6Edffqev5Xii3UmJ27OZnFKhw4p1T3ElgmRXEuj
PXYKeCRicDux3zebG56Zo+5ilzSZ7ZpX9k3fKL9DucIdl5xzDSG9UorHKMF/A8kN
XP+wWYe1WgY//ZWA5olnnLUFqGREm0D/sMJXlFjKfuDVQbEG0//pyNhR06JMF4x6
4ooG8LZ+nkyUGsPH9PnKPlHClTi52t8aGwp3PrVdsxK8cIRR3SugGzxkBfi39Re1
PYKh3zezjeea89cHOJYJBn7ogg0dfntS6URFdDBW9GMnUwQ6Gq0A3ithSusikmiX
LHFErbUJk5UUo4IGUT4CYc1tnwrycPPfjTF1zgfNjEKH3u1omvAKo2Y3QAxH7Soh
3z2kB8x0HFROc10KMFQbqxFkZUQhNb+Z562rtr3z40kcX+Tp6nhfgp1+cJXYGi/S
QwHno6a8Ixf3XmFrfPE8OKpZmwiZhxl5odd/CC3j3l0FJbm+gAreJwWuVwkH+AhW
C4hBg5E/zG1qhGj3nEBkNOYmHQ8=
=Vrz6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'c80c553a-3cf5-448b-ae07-4cb0b1151244',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/+OA9ygnTOO7/V3FhULQ3HnyF3p8NrHcsMCrVg5/ucbNdn
uQ+C/OIuPu/m8XAtySE3OOkx9RPg1Z4+iNMa9yN9g4rZLAxrKi+aHTj7tqq37wah
AatIZHREmdCYGXd9ukz6Un/MPtU7+PkPbaoaLs6BzalW/GUwzoF5wRM9l+pqdCOL
TfJLcXqyiXOcHJpbw5TqPBjX1uDpecUGbbv+hfuqKaxxPUIiUyE0aNmt9Rzo2yoE
9DzpVDtSMyL4RTZUsfjH9g07VXV0oUyqEVeFC/HiovT+5pSE8msnhRm+l/1elpXz
qpChsNQEeEOkIzEyplWiNbkNfmMhXa798LegGI3rMzkR9uFvYybrJSuNVjgcaosN
Gbmz/pgVx2CwDmKzxuCJvwFwA7/BswtH1f0wqyLSzsaayz50MiL7NXiT/AbcZcBX
Mjmo/n+6drlumfx3sEdHBSjPaLrMEZTgNIMeYTYjNWSc+PW8CCIqpe6jpArccSxL
doeQHXvySVK5M7uWeHsIZ7riAu3FgLOmRLMah7uTsV6g0U09LzdaFxWk5mqyhQDW
S8LKrOwQTn2makG6eFsfoMIQ/0Er0bUqLp7kjssyppS3gxZSOe8nZzWkuZ8V4tj3
kFPUppkrCKVXzr4wZ7nFoyn4boE1SvbfldOW/N3lpENgawnIworlG+WObLCwdCHS
QQEcsOq7L8EgdswF0z2ukA6rdKLSDMyl2B6TvJZ++O272z0BWoKUkCzbUNnk0eqj
m9n7D69RmToX1xxQUGbh8td/
=0tYS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'ca8200b7-c445-47b8-a51f-4fba6c8d4244',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQEMA3nDbC/XKAhSAQf+PT9MLJruN7sq0ofeEunF3ymjvrxQv8w1T/B3pn958MIs
xECKk4Dz/BjuJ/y6M0OV8FsmTqkQlJ/+JD54IQZzGvAC/xws8WuMDSKrL4svOnNA
AfOyLrwAeij9GKTW/Fn7QCB2OhtUOQ9EP0x/w++K1eTfbE3/QGf9Ied2Fw+9f10R
ndKz3U8raKy+C1965JXvckpd1ueebphcCLEXNWXJ6rHz1u7LsMmT34AiOpzxXnrw
N7j44GWWlrDhTZnsWGexrUoNOOCF0qVcBqbYtiC73e+DLrg4iAYP+dbLV9U5R+jM
MdSHd7nCHp5FONk2u/sQmDgMJ3nn9Yn7tleGp24KiNJBAXXjqeeiHd2IOu7yDRte
U9ijJIq1ZMb2zzl1IidE3Au9Qk11ZNZE80qBm3gNinB+Fi82l5hjB4oUJ7hjP3Hi
z48=
=ARJc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => 'd7996a51-5ae6-4acf-acd7-49d4a502af6f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//Qa7d9jG7P5kzQXdNaktX4rRVgZWgoNRGwRcGAi7iTg43
8cEmnX/A3pJihJK8caCQWSuDjoFkV8YQu3eM6Y1KyOtIISXtaQNwKjVHTkntCmfF
lSPxoxXQyefAaqt7KYPEYxx3W9AKyUzqLyJxR0lIqddEc8z+T/Dt+Ss81mS+fJu2
IBq50E+4hhUjXAOjG3qSN9E7Aygxj/Lm2nTt+zCyG4ejJKdRFOHzC64quLsVQaM2
3zXlqJYy1kpXojt0Bqr4gezg0pCWqG4XZhWfTDgExjmMJTBgYhguf7XtnS+jVxOm
rq2I3PUB3YMnjJjb0gt+sR/L9g91RH75ZUMiY8TbigTe4viE+yuGrFTKN2FDQ7Oy
Cp3QrAwppifylbCyzm21SGr4Q/aUPcF1RgJgdR4wzq4Es9TV5VA0fhZ7vevqr+bq
8st22HlgWKMO3A3aTRXbpY0pSdLtkskQCpc8g1m0MXsrfMaV+Wfj5cVHwSSqLqXK
UtGByKUOcpswFqvHSZawThlf12AFqY3JlbrxlYk1Q5Il5ZoXlNiKBl864rtG1kCe
6sZK8mcqdBOQ7mzDoZ3/QtbzdhHDCfiHnU53iejerdBS6dbBZTwfHw54skshZSM9
4TPK+BEsK0e4XhRFAanNGvjr7AQnzFRrl/xf/sTOCKhyWp8vWw+dckf7c5xXIibS
QAEUQrrcQL5jfJKmzqHkdcjkFDQcJLZ0xLNlcr2kIv6moWYZuh8g+wU8akF6RgOz
9/D5dgjXo33rsOGOYTpqemM=
=X8to
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'db51d0d1-1210-4d41-a995-963fbfdab571',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/8CeTEJAo+QshiNuimSanhZ2LVg+Wz08PqnxxqeGLl4124
qVZfut7tihLRjqp1bGyrsxIylor/gimP+yA+vEZSxqqTCwrJ6Dcx7aj6ex2KI2XZ
KTJacPl+sd6a35Fw/Yx1PXOS7JsUbpvIMEtlfxG1fHY74nkYqMB69/TiCbdIWLZ6
Z9rht6nS8uvtqenxL4BMyhzpda017HlKJpiVP78xK0IPw/3NnChWjW157UckzLKg
Ri52L5xa/p3PxbXHXNqFIt+7nhI3ZKi5cil6PBn/E5s7mkWBl2hjnOsm6bDbroY+
l+7AQ+HFtz8+NV3MJZw7zDAIN+AzTbeURIRNZ0sqOvN0G6lNZKjW/JwkUP0sLfk0
niF89S3m7Bwod4xyrpFI7si6H7bo4Z3N8JyfEVICoNTvion0Rt0dYZ6HA8XiN2B3
mXZFM2awrc/1wglbak7aMF1po5tERhhZOnU/YF+CDcuuqD20fTRKqszCP2N2FceU
sCMZva5gycMkECnsHnQAyy66UiIsFBUFF8OgG3R8KCJpykd727gJHk9fIwDE+Rt3
Dv88FIMXrd2+IqZDl+qDR1JgnPqkuSikGIYcBT72ewTcw4AG/e4KdsOMvNEqqzIu
up+SD8+VXu0ESxXkFKDhWojEJC264OucoAobPLlOgpltfLaZYW0Dvn3sZb3vLKbS
QQFoBnKcJjWgoW09uChatoWLyLff5dsf5qhvQhaoip/FKXs4gvzhT0pDxt5bgIE7
4ECjNsJkNzJUMn5fh0MfpQYI
=e8rF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'e0a5da88-054c-4086-a03f-8c4c29dc5b17',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA+p38wQEIh7oAQ/7BjpPjzG6agCliGYyjhRRIjUKZjbDkyyCf4EEHWuFPmz8
d3vXsnpayyiEIQnyL/XU3xfwvokRVpnxyI0mdyVHEX5jP3wY2OJvLWrMbcnMgR5N
K8eOrZN88hzQyRgSVuODecmOZTC7J0zF5pyzaeqQLA/qfrf2JKmkkvwE7D9RccUU
cWelIBdnycgT6mDv+PHBgf+YA8mZaJcIqJT2XIbZD6G80m+WaSnmSYHINhEQTwWN
tkym1mI86IP4BdTnkaCEU/OTIVuq/7cGFacewDkN+fdr51KjKZzdZ6ZJFLdWL3vy
dT+0ue2/XsfuCzsuC/iQwTtHjrQQbachx6PJz4uw9bfUcYrAZhtDE5ONnukG8AZk
VKvNxzRD5ABxUHakP43WoBM6jr9Ap4DHULcn9tjNwq7i8Kafy4WngtjF0FD23HLk
rR5NgUXdMLnCpaDruNaMf0TgS080YmYC9cDjXiFtg+NMYAikqHadi/5R5LNOPWj6
qsi2yIDJc/2Yz/P9blqGrX3+Sg3ZYn5FEGeT+lX6C3fExuhhpWIUQz/dmKRqa7iC
oHZKRPyZxJKbPiDnhpI0fcXJWxB8yKmu/eeJllJRU+hI076yKZChabA971/CQ23I
1r39m4OU5IGHIXeb5QaXBIQjmBznCdFReGsd3cSi+hrukEyVRFYg0054y5bo3a3S
PgGIgjiAIgMh1VgS6/pBxF2CiA9kxpn9+FpltnetZpQXmc8csNpsQxAIPGUkci1+
kGnYBBaXtKS1gGRMSzxy
=P9C+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
		array(
			'id' => 'e436d564-3551-47fb-a9cd-6b256a5ccc25',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgARAAqa6ljABtFK08XWfLXyJ3gR2sHwLTN5viuoqKYKnn/Jqd
NzYnDY0VAcnuE0arcVBMn2ki/VSXd6eCj/pyVSkokKhfQE1mTjgaE8X+/hi5hb1Y
jhIdrmt0Kz8hOQJiGnALb7bky0yol1BKWRruucRqC5hZ2SrGZ3Zk843lagTTuFMy
/kvUTRFqqVwIKgxpZMlbONd3/YVqjSYh+UfT7UcCPSiCFljOewZwnLVHbxfPYC8i
JwFe2i/HGbYRdJ5vCL4zaE54ShMOnM43hPEfh0AxkjPAjMVeOP/u91XsG5NUJPoP
0/XLVWhwrmHbO4yrCYgy36nEjm4qlUAS133jBthupF5LkUv+/6sUqzieXoafaTPO
vylHVgwqIKAVV9xhBJcrYt/Us+/V6bVGvan8IAn2GeEd5IKahN4IpPJsEOByTjLh
1c8puNH7Ig3n5PswCjV5PMB4R+FGN3TZnGW9bblFVVV2e+8jI/L2K2B5O9LpSH2S
nTZbYD0nKd3nn9StVOV6KQ/q94Qf/QsCxQvB8LwOuHzWpCUvUgziXJIsM/FI3a1Q
McQZz3TXoxcpEjCrWwC+0PW2Ns+681BzbwkghnF+uiYTbIS6SyApY8UHh+7mUASr
fvqlxyNwt/1131fuXTyfCHxPrNpm4GUH9QcJ7lZ06XjZYZfA8dmYWBqAJkAOeB3S
PgHV1aChn21yNMrQJhLoZ3ZjrUWkqQi96yT4/HDkjfGyulaiQzr2Ctap0jeNw9Dh
37oA3EJlGtdOZ8++2wl8
=qZPm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'e4c2dcb0-fe3c-4261-adb1-d497643b9d30',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigARAAjMdOV54j86roKmHZjaiRiBYKYJl5nwwXjBwLp0JRrMmi
agOutaEhzDvVEx3HGsAoGNS9gyNrLohbHovDcTeMygrz17NnSNOht33cUQj9qX47
HN5Y8wnHBv2/5Ap9F6o49USoDTdqRpjLb1EglS4dAxtunC+3BdVppaB5GuPOWceP
ND1VBUQMskvmWh6SNkfIBo+eahfEXShAl6/n08VvTHYI7UDAjLS3mu4a1/6RP24/
AA+80Y3dnmAl8R5JF//hQiNjQT9e73bIJi+pw6O/5GQL36mv3K+LKd04aITwPIPB
Tf7EHP2Y+Lrap5PcwX6WdTgD7MQ18aeLPANVWJeBb5t8Ch5RP0XOVOlXxtz9Ecpr
Zfp18QM1VOX2GOuM4s5HZD5h66U57a3nUPamIQuJa9QVILjycgxPVQNOnZuRAFcH
zvazolDsT3OVpjT6QAfppdCpTGogESob9UHBLFqDjV1CUJtCdth2ddvhXZ+4ZJW2
A6a0iL65mqw7d/cTG//TNy7+LR8Q6ZiJ+srmgwdVJJaDnLr36E/Tk+3xGfTHQ35p
yOV0t3ZHzborr20wqhlBJPicONKkYDRs/nXrui5qH/2376lGprvEop5Y2/8OIIlB
8ayx5YbBJas72A6+SJlpG+RVcT22oTCvCCR33VXHC63I7jRSQrkGRV1M2o5ZD9rS
QQHORAsVjBCdm1E8kF0qPKCj6nCYTqPGTEDr0qhCjoIqO6WXngfMM+LJ5PD2NYDX
6KG0hL1PG7MMa5ewgCkvLZco
=9N8s
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'ec26fb33-377a-4a0d-a3ca-06d365ab3a91',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigARAAmJd4Dg3wBN01I3Ou0BRyyQzUckIwempk05DsVpUsm64Y
/5TpgfOe4ou81yY2p/dgukHwPFQrwxMLrPTRkvEYiO8mtqNJ9BWjUq9Kz6jbAOLz
o5/oY1NBLhY1L+5QuhkpUXzG5e8Nxfe6XG48KF9kfeIAy3s7U3URVBbgTYydUwRw
Sat/LfDE1goGqSqb8uBc6E/CJV/Z5oA1Mu8DuzQCmB9WRY8z26G8AQ9dm5J+MrLU
A80E9/qdyQhlROFzOLZvT0/p3BpDgqD23VZm1FI9sXZjIBdWW9Zd9Ko0bxc0UBMT
fnJrkOFWV8JPiKpfapy8+rfCpwtzaNnCDA415atiuIsZCLNz8fAprNKQrRgBYpaG
BElkI5tEfeRyHNMLRyKWhxn7m/PJFkV4eHCFuyGziypaGHSNu938O1vRq3A0MjDE
tl3ku2KjX4xNyHYJC96+5h8IQ90/OlBdDclQwKGs3DDQZ/QABdIXdRSS0pMlFyw+
f+onxmMLjLUgtWUfDkmZvje1IIPir+Lhs5zHrbkEr9wA8A+0FRREnu6+W5lbxnL1
MWMSvEOXR7u2JI1BG7v+c10N+m8Ht/JItbmrbD6er2d7pJuU8gSM7PmXSP5ulXgq
MCgRmsAp82NNY9o61fKyAuMRLfa85DHnmRfqMmi3B05UYuf2rXUTG8XSkyldemvS
QAE4l7vFcsd4l6XYUrqRML+4T+LFVZP07+p+4bcD+yHtIqlP6xX6DxRWpNKaMvca
sSYE6tBSpfltQmMP44gYkSM=
=2wMq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'ed168669-ac60-4c8d-ade1-57cdb0982fcb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4NlM/alsYWgARAAwu7T1HlXFGl3FPXADlKu4f41FijW8MK0ov9zesWJDk8R
A84xnv2Fm0v/pju7yLSQJuG0bGUmwRALpa9eXfH44vay5U8ief75wJTZOYSwaOA/
Q4LIHATd7Qa+vcF1x8J75W2+gXr9S9pDp7kCwHMElOy6DnfDW/IZ4iGAMI1jUyLq
yA9TuASRUFBYCBTKlzASSIdmW0hHNN/CGnGHp2by6hcLXwW6awlnUo9Ku1sczuB1
GmhoUHA/UMa4/ZfHId+oLaUHZNfBPaDWcDb3fWg3W2t59OO+BgiVeApAtzdYYlyG
b1GHTI222a15GWX7MPD9RJRJqu0X29xD2Zhy4abZbFGLD8zvFnPofjjj30O0GHxa
9Fz1q/5R139CqZ16O/FULBFpM/8xkVwP83Zhy3JERiN2v2D+vniS2XMRFaG6MUE+
sFCRrwqwNnT8MRC+BdJunb1OHxQW75orEZuPed7IgxrfIsgXS/xISKDrZgpWpJIF
+P31iBmT6dWaxsg6GYm/sieV1cYvmCBYnEpPX+zFBgIOXYR1+uQvoGHHz+t3BN9M
B/+DW6Tt6LYpbaCldpLxqj49rzllCkRHrSbt+8S8tJhyBuLVV1b/PYSsSsgohiPl
pj0i6xygDXS4mOZucAlWmmdAJbTaxh6V1oOPJpD5ApJdmzALsDOFhuY7aZZn0Y/S
QQGXLr1ElQxsZxH6/e5EzhqKaVDE5YmNndLVWOcgSfpBraFom08jbCf2Yi2EAHBU
fg0zikn5Vwi+Vzlp0FBFpcwb
=rFB4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'f14feda6-746f-4a7a-aac1-aa37922de83b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA1P90Qk1JHA+ARAArXPmat/QeDs2BXJch/HRRSFYMnY8f5EgdS9vRU3srWG/
7EhHsTFwN4LbgHeeaCOSAZYpM6c3PhRaD7pnuckHEjBL8+eWCy11uuB+1MkyV/h/
QCFOtSKaVCEb17CcqKq+unhxKYigi0E3iv26MtAhKs4MwHcRwtqU1Zk0NWKLM5PZ
xBOYEKFRTIoSSOQalOTcU+5WK+nceI+mmGZ1ZBk41y/MpNa/0QG2rP1OxIWNrB4/
07TAlHFk6K5SWCQTLyqVJGP/IQZtgMKAlKmNNAb4wq2mUvCOSVzRkz+ifjLSyOSJ
NQWTQUyxefNiFZpJYOde4dgkZAgsbmwGvBJgQKuZpEK5+EZWEkRDaO8oc8ayqUgQ
JJNZnCp0B33a8rKO/0AFIY5+q2n7BhL9ppz5N1WmQ0fRLPIpyCWkffGr76HNjsqB
gs0E2/XCBUaIf8OCa3OCpDTNZS1jUphMJauLNJ7S2oJQegtdr7Zwlm74x3R/E/WS
w97L5CUbcC4E2851qycULljJpp3NwSm5B7M8HxSXKC8eWNCkUqthG1hqalEvZhgS
MyLsW5HuN7ba6x7uJnkGwPqNEyqF1B0p50Jp/LeOsZInBV7x9fsoRZ9eJEcJ8NI5
760Cvhn0B38ryFbdvRUtVC3vvYQD3/PqJMRpLT8K8zOk9KMhRM+bqKmOJ95kknzS
QgHBAzWsXLite8a7VokbqQkEyQDpIoIE3ddFdvCjSG+e77Z3jFnWXtyw4LCLEll1
awOt+UJufqZ+3SY248Y1JEWwMw==
=Tvm8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'f4456580-2672-4769-aa1e-2fb60e679eb4',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA9nJydJ7HCYGARAAuiPSx2Zeedh9GvXUyBxxN4KJFgKF0mOULs2/vM2HmL03
uQE/MGHvStvpOkDAEXr/FfCJVSLpAtbvyFC93o/sVttODlQTs1x6/HrGmf4e3Bxa
yfizwN2nFceodqRLKbJxc1hmHoCfn6RVttNpPingXHgLxVqMKQ8o1EoiI7aiVY5b
XoPZ1F/ojvhE89OCLoUOYeEQ2PRpCNlRDLUKkg67frEmxuhznCHziRjMkW3DvxuZ
wGPAp+U2PuC/wbnX8kEw6pjlJX0+/e/lVpntZeTeXXE6DoD/C9rt8SMvYNobjzDO
OpyKIr/nKXV20SqLtZAmshs1/NgzL9FeuO/+eFgPN4xwGn49hFuK6oDGoa2gkZMm
w6+l22Vt800Kkg3yBU7oLCeznuOnKR7IBgv/ehbp/VxTtQGLP9gmg/zYEW0cV54A
JC4JAowWkY9hY75Q7SSimLAqBSgAlQs77uAfcCn1mwTqYBFlslyMTy6oqIRJoJ6g
adFXVqRbavHdSNV/mJdhISLfrbJCchN+HAluU938UqBzNaBYFxVL/Lf9G9ukZrId
dNWsIvCJStqHQgws7Z0mec+xaGf4yDqnsrBN9FFQbIuwwERKB2vkVXBUM472Oe76
f6454apD56uDwFaS38LHGL3HydNPBh83pbK+3m4fxGwXlgPtW08iw5XVSA+ktl7S
QQG0JIfDTSmrKkeG/nLllwBKiJ/fWEyyV5e+ga6wHszamA6sF4BLvj8+qxilMmqM
/GfideMtoAOb90iPyKg6/I0M
=+G8x
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03'
		),
		array(
			'id' => 'f81ba515-0473-473b-acfd-a4c5e1c85a66',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA+p38wQEIh7oARAA1mpG9tbEHazJg8Fj9P7sqsv/iDN5dwTFYxT/noKbKgQD
6InSCSKYjjT/O8OvLIoifC7pAgbW7w18IDtUbX6MGxh2EszDHYe9AiLWt/T01Aay
DkTL3rmuFKcEmJIl6XYh2oWHfShNXwUjHZeB5WrdwtqENxVAnP2A8Y90ENznDjJN
ICde0rq1XyCyYzIVehhsPA9T4NDDyswAbCz4HPlWG5FT/zuEyxlxFknSnGt5EYhf
zZ5/bnqWP56lEeFN95ddmKX79rKxYt6d4DbRLwTnerxHUzYgi0FqyXVQAhKhMV7i
6JKfxVBMx4fBRvU1hd+DB2+WCR55E/DBTLCwu8z/GE6EGnL68xRYKswsrDl8yGdb
xiyQR56VhP4Qseaews4zfEdYym0HRbsvTX4+po5jj65uJ8wR4X8zvI9jtwDOXhrd
SSbq+sBJ5rUtm0LlQUtVjX9Ze6ZtFH2na6kM3JquuMR10/PaArvOGK0aIFqCKdar
KCA4gB0XEkq3pEDILNjg8Sx74GNiyfd0az8WdTJas5fYkDOPhZlSiZSXPaHpxvHV
d6uvSDYOj916zNfPKcg7UbgOeZAOoIn84+kVVI8kpj1E/07AVkN9+zahzdUcMKzl
pzIiKJTu0o+/Pw8XzkDUhLU/VoZdDolpA8xlsXQ8+WeDg2Dik6Ge+W9+ZqtxGqrS
QQHPFRjgebujJOYOgsLccs0cS1egzooaPmFQfQGAksH8FiaBrohXZ009a6HEtVl+
h+kaSbPbQ9+nd4SIssSBpdcp
=eh54
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
		array(
			'id' => 'f8aa5441-b9c6-402d-a34e-621bf27b1209',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA1P90Qk1JHA+ARAAiJVIu71HVRR5nDiKSTtfFlHVRjl1VHI1kKQJJs+PqidE
4oq+le5lK3nlPmvVlbiTiqvoKSJolm7vWGrzx0MKwpqS3dNFP5w/bbOLS2tuIrnz
18+UUJZuT0RFRTxggjhDziftGknhgkzUqgzpE9w0esBYzbzHonTRjcQkzjCsg976
mDR1WF41nUXKR9ik4OwyoILjIu1SsEZt/Xs9KfylJHmfyXnX6L4+7tT+o7g+fhyt
Xg6oKjT8VXY9BC9F/qLPUPUsZYOKgw33gus37h8LSx1RYwfBCjzN1neaD5sxv0cc
p9hbNopMQz61dg13N4ijo19apQQZT2mvBe6yEANczlUH2kDCpoL1z/CzczRo7aTb
gyWjpRl1s9Q2EjqjhcwJ+w1vKYlccFhzKjsQK39/G0xjCe3zgCdm0vaqyzPaGHNv
knpZMnpJ2TjFwBiR1BU/fgDfJ5hINp2sO1W8RW0q+L7M6sTpM742mOKGQxvcMNuA
lN0P9BUJMmYzPnnvL1Zpu37dHuhlt928nhU20365ECftImtZzuCVrjx8AA5LAMVS
aFfdcpte6AQsPFJjGVGgjdZl6XldHaYBRHMjyMu27FsOpWd8QLzpTKdOYQk4+Fjp
gyvIuFipxLLoKbb7Xczf13f9yml6H4vB0Gj1Fj5HN7QAbRKd1YXij8x6XdDE7XPS
QQEU6bcwWjK6YrWzyCazn2F63IrTcQ+S8xzZD7dqjwSmwGESuQUc0ofOYnPCV3YS
IvxeAGX3H/yKKr5dkfTZz5Ja
=ZtPH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'fa4d9868-b65d-4cc4-ac90-d269f45b3c77',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMA4e/DeCIHsAzAQ//XbN3YXXn0uVxpaIwRHuSFe3VbDbyF/eS1tyK/BH/y4GD
z0t0PAi9JUfaNyJGdOhzVrZrGHa6BUh58SApFNkuFZ4/QcNGTXl548YAXNaddANw
Lfrd4cueP88/fEMrKMycoe6x/NfUWt1xd50mqbe9GJx9sJkyd8rDke6Snpxa9rGZ
Wpf86sXaTgLExZE9xquPQNjYPQkO6/65pcOkiAeFArCLmzvgRJtkNsjjj5yl/GMv
3DHA+Ypt3bgpSEGWv5NVK+Qa6VAr2a6PVqG5WPkYoMjLGXehZ11ImUjWEn1+D7Mb
ly4ATK4zHHqVg9p45k/DRadaw36INIkIlmTW7xf6t9pmlOlXKHg4o0/uxSTcNe0d
7wmvxH7+0WiyN5DC/A+7s2l+m1NmH2SF9eeBH1t17C6rPmTN/so51lKywV1oZEQv
yTVaI0iOZxT6Q5s4qtmJvhmfjRCbFyCbNaTapVWPi7LtOvl2EdbALJbSLCjJtUmB
cXFT4c1WgSzaS/hPPvyQM2md316vK6WFmctdl1LUIvyg/5xHnjjeBqk4tegl5fzd
OZoZZtpAttK1K6a2u4UVZT80sK2YCk4UKHSWDwg9jtfmy+VaVlCdUsd23xZjcwFS
goXLbjYA4xLEpnnjyVb2NCM4YtFDSdFVbKq26PQoQo68oK0/w+trZdfWPyxrPOPS
QQFD/hltiJGaY/Hioaxe+pEDMnK0yn8TPr56xuMPw65tT+Uj+eXzUpk/LLgp8zzt
Ld2Ii8L1c/8/IQZkRe9g2knw
=+feE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => 'fc930776-133a-4804-a8f0-97f3d2c16dde',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2.0.22 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/+LFp9zeKfVMWbFg+5XJ6k8fT9cOukkVi73ehoz+OR9gGd
AXln6OnX06nN9VNVOIIXcTLVY2Iy9WHBYcsWYb6J4t4gFkGTZz2u98o7QDheiDfl
GIVgMtW+4Ds6J4yVax3b7vNZXeoGrhYoFFajMuqAZyYc69lmr4JwUG0h9W4weKcu
nJCKWZeaQlPNkZdDjqIPUVxEKrnwiGkf2EC++F2nV6vMfndiWGTlEHhGyPxhmT9/
4cqAGfgsU1eeEJXD3R/xg0JZjUVci8KTEyuct1ipKxgcTkboc14knKcn2J5jfMAB
GeOXCFnuN0bjuwOZ2zO6IkZlwiXO78002adMijhk3Cg6BnqsDHJ0Dftv4+vsXYhr
5fEmYbdkd34RCEvLLXk+nynrLuwGiOz3kTqMZglfbBfCLh1UPcWEu1hvgU3m4XTA
y9YqlpjvNMxfPcOBO5QSImooyxbEt769H3PaOHXrLx1hLf0Vr7zo21sBk2ON8Zw2
8EJENPbR0OLJaFzVa4M2Kkx2rLKdtbdAHKFuFrWz69j4zJsqA45mtlYG52EMj9xX
9i/kMmPfuTtAt6CNGV/VNZ6m3HG5lfva3ocRS8og5e/XVdBtYi9LaCKQ+w/Mls9T
jWK876accR/WgSwe5pwVVD2v3fJ6z8sIwNAkFwuAc2TdvYyTZ2jVBroD6Jg2tC3S
QwHhoBfdGvlKNZai3XxL9uHmP4IIHSe5MY2netSkZnVlXBorVAiiZWXvY/4BYNAX
DbI4DBBgwajzMctP8xiWaJZiFtQ=
=fbqk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
	);

}
