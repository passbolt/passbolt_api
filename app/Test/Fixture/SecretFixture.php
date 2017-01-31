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
			'id' => '016a0d53-f2ec-4541-a1e2-dad5e8432f15',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//VIriNqMida8yh0Tys/zQM27/Cx+wC4npWzut5NTQevd8
iel8WnGAaxi3cuSYoq8mVk3vzQmPNOOPK4/dsJPDCiWo2wGKCmsfdG8fZcoii163
H1l8HIOgdwv/sNclh9vYOYnGXzJfmeSUBJ4NkLO2OE9hHxKNYWIC0YZPQfQl7wnf
hY/k251RcIqZHYjJYY0aLTCOzG6QTftA4KPYwh4ThnfG6U6aZGrj6l696LXjE+WI
H9UGXqCbC+qONbRyS51aoLE+4eHNw2ekAAAV2f8JWAcvSY/qjJcF4BW7JzyFlNmy
iuwRb8zYFFtUyJQpET8V82+XMZBQk9LF9Nf93QnjVc1rEk+pqd8Z3IERTLlaw3fC
KWBbmI/NTPsg9Dq5g1oI0elw18htwPiToXyPkjWYwqN+c6KwojIINM1LGJ3WeuSI
UTOaxA6AOfj5vJ7CcIPicYLswuZ+SnVW0NlhyOjpCqbynB1e2d3L7uglDZ/MQJgs
kbKapvB5N8lAsg+zBS8k/4XCZ1JXKi9EUnKDvc+n2hTK4V0D7r0AzfXhn6h5+2eu
OSHRNoqrzSQi/pXs4dd4CWO6aE0L8ICVWUR9bxWu8+g+BERhKS4FWXeuFbjVgqm7
5BvRmWK0c02NWkxB28FM3BFRpyWNveibSG5HI0qHU4c/xFi/guhsnNA1GHSNq5DS
QQErXa+VmL4nwQ8Uop7qrIyZiNhDzWoL1qsQ2hdA2nnT847EVIo8+FHNJnTfEBlB
umn0Ih8liBCsnbqBFTncMEkS
=zQPU
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '04d19541-27fa-4509-ab7a-a3488d9a2ed4',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/9GQJHyIh33FhOIeDas6QNI77OtDrmtyLemHdtL/rSrAxp
7l6IqpaUKJCTl8okPoNGyGNRL/8EZwvL0OkOHaqgKW47N139hWB3Mh1FTjs1QvRM
klT2judu/SsbdaBmNQv+LCVMxiK2u5fM2KOEkGEP1mf/0ymd/WkoLc2jcT3ZNO8j
zGDCZG+QfbtOqq7JeY/G48ZsPLfJyVPD9Eu6Rwd6DvZnUjOF13xbZvWAziPb95pP
UpijzHu2HSF+9TGtCHmeDGNhShvb5FF2ddVu0Ap+zAH4NsebVQ5a5xHcplsp9Nlc
cTZyThN52Xgn0S4CZjo5BN+a84jhSZFkKQlPGtDy7vEjhwpfqJ32sieVhoAZI1BP
i7d8yNAwGoNMsFMuskGkVYCY9/F5z0P/sq4eKjOkxl757+nwmxfZJThbDgGdCopj
kfyrcIfFbjGCO5GMmUFPr/u7cR0CYazr/vbx3rCHdX9CBcVky6Esa1jkeEnxgyJ4
hejFF7Cu8O1qbRj6ClD7FBiAUJ63a0KfzOnK97au5n2HuvinRZ1oeFWyuKxEyhMj
c2Ymeu782sp9focDNX2LXXwLwvj7aqe5pyHuUfGm8fo4xBGwfRhQx/ms85c/wicO
l9A95LtnPwER1Xi2elCi1nIyP3BKMW7FJ+8b+dDqTvtPXnL4RJXUhm2wHRV8eifS
SQFmziB3NNo74kDg8Dgjw12k9vdffQd1UZdzat1intea1C8kOUgvtxrpcXPBsIFT
HXHyt9DFc4um0XFsPP28HrrOpdVnuXw6oqQ=
=zQBj
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '04db3dcb-3b52-43c1-abce-ca065d9f9f2c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAuzODEAGTu6QfHILWxeHzAiPKnpvSpRGqkNaij/vGyMuD
JBIUNcRs8oN6hgk7Cu5+6t+pf9IdTpTOqjM57r5HSDrvbV8MEsDGtsfpp58zWqyI
TW9RSZFqVGfP4rZbSkGkeV6iPpQsyPexg5VFLU4pw+Cfkwof+sfU5aXvkso9jx2N
1d3AgxXllENRCy5Q/Z1efJ0UeeSqbIb8pjdu//TYf5VEVeC+Wa4L694PWsLJPE1w
l3Kas84B7lko3Nij+5IGYq9rFKN0zGOE6Cy8PY2dxoRfk0EpxIApj1YeBQWp/lJW
c48ZBlnhjuhpL9fhNF0o1HiQVdQEwh5gXqULnI/kJLUilOFZvwMiHB65AnlMxNAj
mEFke2CnSNjUsGPTkHp8xL5YWqrlJgyHht5dHVe3JJsqIKpUbTwl2vPMFOJTqCOX
qaSLUUsF6pK6bJL00jeAklzHxnQ6jwUFtVhNMSracaGbsiLbWG3NL8QQaxMmTNsf
gXCj9SuCfECsNTSSNDKpVZDsx+20DGQATpMqWYBJvGKD1Bb35zzjC0QSknRFALLL
CXPestGQpzh3o4Rl+UjMwpw2zp0rabtm4MJ8kKJMLPQzDkmiG3QvkNVGGN0rQugG
/x+m+jLcirjCrfy5yq7eQILazTxeXY93suhvIOelj2x6P+9zTnuBbuqUmyIZeDjS
QQElhHWVma8n3lqIQb4gaR+Tbjq83HLe3e6PVDfSEU/2t0htDfuB6+5mrSZ5ICRI
p44KYa7SXbhD5uUVOralTFPR
=II5O
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '0950349a-b2de-4041-a12e-ffd436918a9c',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/7BvPCX0P3zqeohx+zavuvhMrR3cvdv6f8PmrcyLqPuEMN
2RQTR/SkpsQi+XfC1YKPLnvBg6/AiFGV3BmdAnAqJh/b4bxE+oYadXp98dMSDnvN
gvqp1mD0u3+5dIBjJtDX5HtHGRvKDZg2zVCPd2xjD7ySkCT196I3ueSdmeovQiJf
JSta7DG78ht5SbYu5/cmHvO77wfijle2pAvD8hVHBv3eWpV8/lPx/WiBfMRGbYrV
1TfXHwXn7C6NT7MBkMNzhRyobW5febgkaVS81yASMnW+4dui3HzS5lU0aw+EW537
iruU2kK8EVA1vHfrxMm6l+DRHUJYOdCN7Pfsi9ARTsS3BDvGZb2z5eo7Cda2P05r
oLQ4DPpjBpoLoaSnRLcNsdWjf7Fz/SDQpx8IDu7cSg3TBiNe0ms23zXaKY9dPX4C
f9S3YuRWCaoXvKG/FTn6PZyUSj6IDv33g/643oxjpbhy1CllTrz0dK8595RkXmVq
uPOobZ6Wr/YB2cxv59I5+lB54ZcjNiBcQjB5ZWTbNnSSy65ARpgf3zJdS4OSwCz8
05CWfm+sUITFtnqBqw5/bpnCrRixSebBwOiuQ7/RtNn4N7x8OtVpLzB9YM+g6Sb2
lt1KDbFMZ61c8ndfwNLIc2F63O1i/DRk2J7S83l0QlW5d30G9XErFACbcscTG6TS
QQHE/U3Nr2XJz6jeREjxMRLCv8qrss1qDoWcpRlH1NFth/fmAc6tMw7ggF68ns7h
WCMIGbmZ5BzoKgQSaJyYOQDj
=7jJA
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '09f103a5-bb52-468f-afb2-ffdaddcb71d7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Z5YP6bQSmcYPJpqKxJ3bO0J1EVMHrW0zcW1lTZgl0LhP
/O/is7W2LbblUPiNdbokiyBbVhGugXrDY5AJDGIAlHJl/U4qSN2gRp8CiC8zsTIp
AMPO1FT+sZCCdIA3IxGhdQr6PC+wDtuPbZc3W7igOmcFb8TTuDJZDT2SUrwsaQbK
VHG0X71e0f0ugcXqNajRB+07PuErttgoi6Ojzh900QgLTkgelOVkVvXxe4vIzrmn
Whvc35trUs7DmOIvU/QiKdNv4OE3HCeleN+umBtqIcg7kY/stx4dYa81+utp7RWp
WqE+Jephl+VnSMmOZBTbEmB9vzLNx+QFk8374Y2Sq4Zu95A8sYNEJ0gq/nF5VGie
D5lMA6LK3JfCLrm3K2dwjBo11RwBV8majPCOBvu4Fw5F8Xqw/dcFRSuS7AFsuFTE
1wQHhZBkm5EXn1luEQZtD00eFdNlaiBoAKFwmB2R3V9BojuPUyXte61fl7DNAH0v
JkTMPE6gcKBxJqRMfbbKmtYEO7VJ9OnvEjPdSpJfwwz3fDBfstNpa2P4XE542H5K
QdESR/hMC/zMAUF5krGNThceyk8B/tyoP7PozT7dy15jh/urjC4S9YC+xf3rvA9B
x/5fa37YCYavjMucgEI9D1CpW8x07xBCbN5w0vyU0eM69kiaIrPpo0JUK/Mfb+PS
UgFioqBy0QcuQbCjVG3SsmImAzw4S0Mnq0N4WkW1IYcVgBEVQQaO+DdNg+mq7dBE
FB473sKL+JaIXLYkCvT46pIUQeJbUvoAwAtl7bFypMKQfLo=
=/t2o
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '0c0da3c0-ca72-41a9-a46d-bb9830dd7068',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+LmiS0goa6uTqauaNj66jDeiIEN/GVVFz9Fe1/7W5rQnG
WG26zrmSHGn3v99bzxSvALio4IH/pt/LdaWpxWdd3vmqYk66hJcRfWhWOFrHYLQB
Nb8q8307WC+f6JbVtKmTsUAgti1jMHifiZYBJ7M6VkmWHLKnzRYbndR+wuU0QzSS
wzQcLYKOtSOegum+FhVi2Cw07HFzQCJsYboiGWztaT/4CK0WepjbKHv3GkYji//3
nx1HyqqzrJ8KA/nn8nIO6JJeJRhyUavzGiKImY4QWGhJtgQoc4+yG0Hl1x+pldGK
0TGxFhXtLhfNlxm5Kzc8z+FZILtkOlVFeKVLeDHrfOQrSP0COqhMTAdJFTUdR47t
3kwuZ/UFIMCAA8KiuePaDewxLrqPZKiOVhyhgOhVU6SDu1ywdSSCEIzk3PV5Wrlh
a3WbAiEHp86UDX9P7pS/vaJv2qKsRIE/XVe3IwCCuyKUqzV2idOil5S1Is33yGhP
8yPSDPN4FJCFXtBWJi+iuKLjF8Tkak8eC8LLCBOI/v0ioheXPxnPH76vUxPut27Z
n7nB0pOHtcPpq6EF/r+aWSWU/lJePSdg43VUTScwxpj03wyWq+FbVuBti8n4mAr/
BHJKKyRXwOi/2jm7xDXpZKlHesrEcRHk4VFwkFPr+jdt6qe2Jf4FurrphDa92MHS
QAHjTfa9RtBoxqG6ZMNWOQlz7PYHHi6za/xZ9wf1yOQqLQ8chJpR544/D1KWPD11
BFM08oFvEs2W5u7NQEBt6cQ=
=S5A5
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '0d90a6a1-018d-427f-abdd-13845e522ad9',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAjL8aa2jfm0c78z7TB5OL9f9mk1pmlyG5OIIz1m6D4ZXa
rN9Dkktgw55hZWVopmohbIrpImaLktmfQmq7A9rl+oK0A1BVpe9XNgA3qLJZ1p8U
S1iY2BtIefJ1MgkNZiKvk+5ghiLP9LsHn1GUJ3cW2wauFA1A3i7jGKbDoZJxAXUp
wmFzhTqooUtCI1+1YxlhzJ8QaO/NtMJmX7QY/qsUtGSFq2D7Ob585/vIU+Xs0Bby
XKdCK3INp1N+XYHLNHq4OVrIzuiab0RMLZAi0viQBuNwXcDBEB80KwOI4FqA9Ade
Kay+C12JLZR4jb2nd6uw1Htz/BBh/IVPZ6Kt9q4/OoHGkrlzBaXlVeQQtSBBsTDw
u0AnC0dXxiC05G4lG3bYHwpk3RXpUXmXfmVG0wNrEBGEQa/cfrQpQkzK08AK+lcA
O7PrkejjuXCCV++NoQtT0UXcHIEArNlBJzNSZg5PLLPVcLL23qnC1QtOusT9l0OM
yNGWbeglZUDaN115fUHuoqUrF1qTkgGlMWH8aV0tmEiWbD3Pgtge/fGHLBqFfwyd
mfYGZbCBQrPubsN7SbbenE/uu1gww5OI7EwomG7//dhgKfka4QvH4z0XJY0MJdbD
ASZgdYnSlSOk0kDdSmSEKV/o7Yuai9nsWzMksFyWmb0ToGEtCPBbDAeBQdBBMvzS
TQG0Glx9F2bQbrY6VW4lG61Tp8GAL6275AnBaNesjzK/KCDy8d/1gYZPBz9Bjo1O
ge47QdtTsyFJnKFHyEsvH5YKdZFK0ku/CqvPT8Yr
=QTE5
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '0e0ee307-65e3-4e69-a784-9a194cb7a1e4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAo41ERrcwhJ2sb0meXeFA/KFt0ovEk30Gz0DbxlPzFuH8
hg4fTfGleVeNHa42XKpV5E+vhSlHGP5A6ouoAxZKdzX5R/g2XsShhtZnrg4YBd+P
gkB5kM7usyATUiqLagqnxT8u4N9gBmJJUUF3fY9R31w6YBQSmdxAbR4LmBopIPxq
GRg8TC0rtjkNJo9W56AUOuT9g5oEJmFlb8tyOivDmoImypWWFAieZFRW7xd0ub5S
+xcSg2RixXukVwg8uC8dPl1Tg0BQucz1z1JEgESuZVwGKJUeAC5POaykpQxRuLiO
KH+jGQs7Smefr/4gyMLn9TnLjDjeSgCqE41+PT6dAgvnBowforzXMg7Kz6TyfS9q
DL+J60UJ2EItT4Wu5JcGfONjx0Kfjp+jt7A8xdA0Up16KK/p4JKC+xhBKLAv7fNU
55WzVTFyfpuL/VHn2pNviylIqfJjyIsf3IsuaSljG6ee/23P0QFzAghAqpoefCeF
/8235SGG0J7Imbd2wHzd44yM0VOL4OJCnlXe91Z9KqZq1a7H1yLMGqHEW/VUxO3u
/kU+t/msjP+igROauvPm35BjeTFai0RMc0yvlfXpDmXMjgeB6nMYOzTIdydaj0Uu
ICb9MgoAtgRddsyf7/LDkhcLTbZHgxWuuGfTdjJyR+vNuElc4mnTDuhnAI8O8zTS
QAEy/x88ni2Cg+eqVYekliX7sykK6VdkZxKQgrN+BgvYifSZSqRWzV427TrtmjOx
6eBLX21EoTf9LARu7f0Yqrc=
=sqce
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '0ee0a677-bfa1-4ceb-a911-19a4493ac997',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAgfrWzt7C7DmTmJ9kBJipKQYQw0xJ3JcbgukHnmfwSEaD
SQXHeCLKm5O29gNzKnHX9lZ9DEwP/uEsM/lTdYxNNEAWg06vG68IFCZpPpr64W8g
B14puwvq0Q5U9hCI/+9CLqEB6oOTDPId6ZxxP0gdlsrlPsxOfDB+JSalcQa73OKs
DmsiBQaYnzzOfT6sQ9LRhV2G1QFPpgoVhsPpIYktX6V+ugZP39RovopkHIm6jh0A
cDZDvBQijJ4UIsK7KH1Myy50oG1cEmk4AveJxI4v0Y505s/VzWmJXAWPpkHJ5b2W
x78H1FxpPVJfUJc9/p7OHsgUyjvosyg/u3VbohTfXtrKKJEWfw1c2f+KZBNb97OY
guNmKD+czgU3PNuuaxF6Y3U1VfvRRJq9iG5aQgkVIaeOrIA/0QLqN/WPicLQf2Kv
JWn4+g9kq2B/UpqGbbxU5sJjlVcdQKa+fFunLNFi9VaZ94Xx3HcJIbquPDpM/NPo
LNiVCyQ3Uvyw+sqAqZiwXd/A8lwzIU8cixyfI987dV9FV6WSWjJdfCqqgY1EwG++
esZBskcZ+ABPDRjN3fcYfRIMKABAt4G5vqCsWvB5FXHQuu5YBNc5klt/DWlq/i9M
I7glnS4p11sR5S67MxZX7YelBX/zeB4rEeS0vK3FRYRCgW2730JFcGUNrZOzGcbS
QQFUkHBfVGNe8vPvxL/5fvHXoMKWPmL7n+CCgSElPOSeuwpIqsFqPQmVvwUjz3yH
7FgGoQRrq9+QQtVFK2oW7sjM
=JSVo
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '144ae7d7-13c6-47c2-ae72-a21fbf2d0a92',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+M3slSYuAsthZtROoTVhRF4x2c0I+22CzO1CEYfj2SOM1
QGOlEpmDnxYNh0XJHBHAoVTe2ZJ6f9peiTL4QdyqV3WkxvdE/IN3X6hifnNTQVAb
/PGJtynbIvBq03W776dGvSFmmRqCcHw0KR5KA2R0pZRNQk8py03rwsoxbTAeA2c9
4KbuRPnPor6vtoFZUG3i3odT+tWnvfF5INynM1q7ylOnnBENezDtKRYp1rolag/v
L0daAfIJgu/eJ0sXhe6Mnq093JQDmlxTxqm/95732mi3F7StSCqg/3/KfZeT3qrf
Zi4VC020LmaBQCEiyYkg9d8hJbq/LTXb1punL+JhpYFDb7NPm7O2k6f1GKKj+DE6
XuccaStQSNwuqIBkFh79xQrVs+mWuPZceBElgTE16SyQBSMriSsnfHL2rxPdWpAz
abnHktoj08meVnGay2p6psDftSqYo0K+vyxAYH2QxkhyIzKcg5e7hyxl3x6tNiH5
bvH1jtAcl9gbFx1+PiFm9giMiazDjIvwk+VNxr06vB2lPrn11OsEZqlrnZ9i/nsm
arBP5aH4Jkwp52MtKQiNcdea7zH9gaKlWMzpz5l5dvYOw2YsgQwnLW8EWRB9Sjjk
7zdKmDac9ur80azie17eN+4Heh4r6AokcAMNls56qSN7AUNaV7zx81RQB8ny+ybS
SQHHis54SiqmEQA4WqFSTfB5wumoGhCyUWcAshWySVEmXJTeCkEMommPE1kAYtA0
MRxrldtzJl6XOa13hexCrakaBS0uXKacqJk=
=CFZl
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '17a0bf0f-8954-4917-aa40-0fa39636cf41',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+L2oQcWzIGvRB5tvMx82EHo9ebQZV5a+P8KuOqip23uQ8
fHVnvoSyWgqGKx33sJCFwB+6Vck9IjkgVP6+ghmbviXApP54VyaAS4Dtu1VBdO0l
HZ2lWYGdJihS2kGTcfAw6uft/gZ6+ka8NXwJUNd+D7GqjePUSDKusG5mzMuT/Sfy
qRR9pDyIxZLGPGlreuNCIdp5xO3J76jKbHXcsKNeHIozIT8sg7QF/zE/p1DrqK9f
PFbttbdKCInTCuKaZIdETdlJNz/QGBtTYMIikBhVGoaBFRK2wswzNyKkgsQU3azI
33PjH83A1y0DTiiLKDeLP7UNnE4XAdm70Y4gO+EIKp3L+B6Ocsx49NkIvaHOFHw0
YFZ/kFpvhrrJ6tLFLW+j/xrR2uEifjnHrLh5Fm43Kn0EvOK5oqH5cjtanNYbIDcg
3NM6QsXajCc1H3L4aQwTytBSGE1JVucUxWRmEzESf3cf0arODN432O/ErrP6CgJJ
OBfMcOoGq0NmFrPRy8wy+smc2Vk8ZdGi0fT1gns/QyhEdB8l084wObOixj24yL3t
9p7EAq2VhbmZNRkgaHwR+zEalxDf8N9VApRUiVXBpo9bV4VSXhDjOXwcXjsFswRG
wGuqLgC4uFtCCaLqZ3xiCHa7/uLDRawXX5GaYdTJiKC8lMf7bNezLbbECEMjbBnS
QQHABhHhN0UlWjcjrEVAhXAk9ueVC1pClBeFI/f0RR6WAuq1E1AxVEmZ1aIXNAm2
0Ye1qAaPKKF6kT5a0UtMYS8v
=AHXq
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '1b54809c-7b34-42b5-a4ad-108ae044a395',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Qn3vOg7hjgWI1ejs4m9i2wC/rBq/vbgAh3XHX3uBiY7n
Xey2aKsNgY400mhqr/Tkg3MZW/7cf86yBt+e2T/s+3pMw7GOIjFwGE4XwUAuhZAl
KOGCo3pOw8PDaeUWayh8xM6owAz1uVEgu+i/xhGmnZJZz5U8i2dJZDE/eGSkOpfT
23bAyaNeYMj/0AVOx+I/eNNcT0rbwhzuUeCnZY2+9IebsPNifrGggzUwbhfHtU0G
mqb4vJUqHgc7UHYVUDYmI6iWvzAhYgVOeRLu701oVjESy7MPgZ3IddYWaZoN4pKK
Q392IqaXFwV0eZ5PvS3owJnABCysa8beDujTS2wBxIfXP2wn3gJrdTAa2/mtdE6j
pJt6BP/nRQABbtXCYVjTfPsM+wAYHeqmjRaEhg/z0XisKT5Cc4YLql4O5QPuORsO
202921wqaUuTH201pWFip0ZJI7WBNb2IBb//YARiXqByNtNc1vuYdjte3+Hy03NR
982kAkgRmq3Mfr/9zQMb1xXjqL2EugjcmUfV6J80sEdi2c1mjUgR7pUBcfBHm0uW
qp+X/pkpt2lOtxWRDhW53r2SMgA2zAg9986wmGWLXydFKx8vYF/aQ0bK4Wd4ZKZj
4uIBwgXws8xuY/0S9ukuhuZDSOqM6cDvmiILgUBdlZKMhZjRLVO9gVuJNBmHS8DS
QwEcFeReZMuZuvLuVVF96cVMIWAtE16x0NgnvNJmkG6a7dr8qlsgqcox/R2Z0t3V
c+zCF01jZwdHj5UBKU01xM4bllM=
=UZMq
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '1c7c1fe9-6982-4949-a277-c1220156f017',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Tv7y+UwoUIIBHZV8JMt5Fx/QYy62GcoRkz9lPIpmbZzN
N6xqR2KuvQw/icVH12snoqAEouYg/FUn6e+MWWhmm+njuOr6iuTpmvr4jauA3ydQ
5dGh1GBVJbimbuJd4FUtjguP6POqSEtFHM+BGcOmWNXZp2DSt6WJ9n6PtY2X5TZ9
sBtC0T2PyjkRSo0qGt/nw9bAs8h1OsGWCAjhOmyVfVP7zAtwe1WUAr/BC46gVMJu
O/WFa6iWSAyqDWZjQBfn2OdWEn0qG4kLgpOB4813LnsbZNkoFM63G8yE6+QWLsiY
sHeWcNrtytfCoWix5J5wOhV5E8X6iiKMhkptMl38qRGCwTU3IdAwPO1SbkTNEVHS
CHYQ2rp2O1J9gYnAWYsrLTsnl3mSai74FodEv9ox4sk/+S/NKtm/KbcWdtR4OJOp
bO3+MY5gOcOTcsf1vM78GtxhVrI7WUaro5klbDvi+NTTzjGCkJoPpBDj6IjjOjcz
TvHvL2dfuwUf8cGVcTNCbhEy/bqb7kJjYWK69stTap1S4wFMeocFMPIBsixTpgpg
6fx1RQaX7YGFOq8m4JbeekDPII3ZdnmWgSfwv4x1C3Lyyb3wWcl/xUtX4Gkc2/K3
IM9nKQzNPAqWRZJPn9gRO0Z+k2kghxMzDYoqjkg7s1hVE5GHoiBlg05eTx8vu0XS
SQHrdMIc/a9jcW0qZ6TM6TV3dgBQRjLmY0XqrFgfNLCJ31kwiPDOTMOeHJQsUuvH
G0mb5w+aznc/BfUKv0jbJvyjTdaeO8BGp0o=
=P4+J
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '1da93878-6043-4251-a43c-9418eedf4411',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAjzojShsLNNVrhHyiO4vlS+4XQH/AuO2LbYAJXxxHQhvE
axue2Z9W8Qi43I4uXCO8l09RZC4guqqPFOxbIK5KnKIxI26N1x4/M9UBmsE2/Pzp
ZZqOuvAUGJ0PBDICPzJGzRSF9YNeBWM5mT1sHwvQwt7jLOfRDNU4a+ETquQVAfK2
gWzHKxdDESMCdGsfZhTLESLKSbnf+ERlwrlT19v+kex620vXNYvwcrGKCBY/rgvA
FjweUHznYFWOY9vCcgxIJsOr6zWW3zdzk7GYUB9Nqo3gG2HglW1tvcP3KeDl+7l+
HoxVvfZaZ403ure6q+kUwdAQ3wyZyOvYPsay7Z1Fs+XGOm5j0yIp2kljxHB5qnV7
57VUl84PfPEBSPkM2HrVQLJV5Na8XJzWEjGwF7/EDwTy9rMpN6iP7/umGYbnjhEk
avqevLgW5vTUMqfwpLGTe0+NiB8fBpBFNSByHGSxBuJnixZ5s5drxvLKPJavFNtD
3VDxq4rkDJJBr9gM0vM2LBJWis8gQf1AADcdYb7ZHzGhHsVToPCa7zrRpz7Cb61+
PiySz5ovm+a5byhIelJVYc2B8hEswn4pvR+kwtOT99seXgtJExkBFvZq5IcDKFVY
tqHq8y1daEcB1qjjz3T681hxUNF385XqdBdPMK/KtYSndkl3NnY0wXnuaaEIqN7S
QwGD0SA4xHbbU6ZXWLE/4y2LuDKyNFO7U5dm8Ohp4puFnkK1xOhqJXorl9TZz9Ij
UtW1OdDqc4GJ3rhHwCqmPIpM85s=
=a1D9
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '1e0577d2-e14e-4b75-ac3b-29f9fcb307d0',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9HLfVJFL1ATKm8JwVBAZlD64bFnbU3oxiozBVz0199vWO
pZyvlng5HQL40cHhhcetx0hlam2D9h02NXtaKy8/03UrjtMG5OcU/s398ebHpxqu
IhVF3Yzbt2PxNeQKVH4WC959y/+TiP5s8zaljuOUXbMZw3fZs3dSg28dCFvXFm15
cRTc7ER6zlnOxJmZauuDc2XmFDlAxyVIVnEdYUU+8UBfr85m8P5NQLT1T6BYyroE
S9QTOTGJgTbvXx6w8AnHcTkRvbAmMZ4CSX0RXdnSK34ZD6USMIraIpFJedOojRP9
rlXks9rOK+sFQ6IF9t8Brhyw5hHnqDuiq3mmBQUWUUHfPLhdN63b+Cl7Nrz+fFyd
cTge10VFQh5MGanQGHdiQV7etX9aGrVJE4xsntMUaG8W0U1mX554EvZXQbz8CO6x
wsVnsFDiwvKFORAmptT7AmDwBBlzylkhujz6+9WVvKcToCtgGkDDgqNDmQnxrEES
1ERBfmrErYAQ783RwSfNDtRhbfMH6Z/SXOXP8bieHIGZ9rKksRoDjyPBdIFadgo5
/tszrA84IHJA9Rnqgc8D9PonTATIX3yjvQZVQRavKKOAdu3r3oIC6NAmVNNqAVAd
zyCCVS++8JOy8za+lUmmoDmUmOSER+T6iVWMxnNW+MFD3BzNvIBtvQRRpBjvijzS
QwGMNAmDyX1qS4hipk/lIFBsLbeau+65qgtsf7HvEpeIzuSG7mChoXAS7wTZlGrT
FvFKOrPewCybarC8E0cX3/5Bkjk=
=OMoY
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '1fbafe14-f531-42fd-ae66-68a00937e117',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//Q9CYuq93YkYrxGxk2cXs++4C6lM2cKmF1t1FgGCwmdN/
VoxFuIoCqS0hQwJorXbF7K+bkQPF2+2NXBzPedSFOd6N0r65pj28PCqf5BeBnjZJ
o6rEqjOFesWuKuKpZKIm2a39RJBVJnRl8aF3WLLI/S/IXf4tGgITGuVLD2DSq+qq
jLW+HwbPXRUYMT9WGtUUlFfcXAxHTx2bWGR0KP9dZAlSDEtBfU708LP1OTKJmjlJ
0PJKkt+5g/V+aqwH0WXQYAZfVtmbd0SHwgfjdwI5e/AzI1FMR7oszr/BA6uGkXJ3
+MTJJqEnd8aqGJTwSClbBLOCs6aNyNCBpO4jWsNih/YlkRn5vTUc7LdYLXbL8SfZ
ZtAGzN+S1l9tIw1NZ9k9UalnLw6nl9w7U2xE6rzhdX77PdyyRFiAD+31yBCDNA8W
FZWHb2pLegbXDBrGnTkN9Iesda9xGhF0LfUb5eVGFBq9IrvJdAe9KPHyS4LHy10u
FOWuKur4Zns5IEY+kgfgfk41CrNDoRgTwgEA2GjNnzpJVw5YfskXE00+3mEr8cvY
Ok8uQztVtXrTWNWvdwGypiHS52aOIFeCOmFsIX+sg/gxccH/yKf9+/LNJDk/2Tmn
s0QHgs6Vj1fVcBq50riktDg41KwefkWDs9xIqeD1/Rcgz01H4ykiN7tzkf19WN7S
PgFLZCx0YBklXyiHbLPlyYqaL4JV3fQk2oIUjgYUlmgamcqLCoqh5NaYYZFELVcS
ZFc610G9C8Zqv3kL4u9t
=xqoC
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '20c11285-cc3b-49e1-abf1-7f482546929f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//RttUSrmi7LYWyD9SNi16MIuJjYM+12u0cA2dQCAbFBNp
w+BhHGOSyGgrNQpmoOWKzW9htgpMNssaYnAfMEvihdXmbhgLWCs1q44k9MWp7n+q
gPWEvkaDRaZ4pVBP/MYb4zs5gNu7FAWnGJTxU4B02fk6xgrwdjKkxIpHxNBBF266
ihfWGFzpV43/YzSGlBTw2Cxj3Vucl9TtKDn20dmlm3rrqXt42coe/cSqgrKo54t6
MUNdxXNcNnIn71lczKBLcp0Y3XhpVGsslu2782dhzkDEiy84ITTHHuTZQUIt6pe1
/1vOJ0EJ8I7VaxB2wwPgqT2xc58mCHCIiYlWq4EuRxWBUdc0n0T7J5HhNdcTvdRJ
F1t8KKwLQCKGmkENdvpqlcFIO1sjLfdDlEKFkwm+8r8WIBhnJKWUHvP793b0y5PT
fsgpI8qCvimzY/cMjP0gDpUDjj83vz5dvx5X9MfL4zM8AyE7KZcAdIB6VpZfRU5k
scF9kZxNrkDILB6OND7918GeFYZUrsgqHSZiOJKn1Q7R7/FAH1S/IICL8REfe1LS
GrVKd3j072IbzhRaIQ04CjObQF4BcqTSuCO42VgVa7Ly2zPj4FoFfGSmd+7mw474
Pl/4xxv2fmPj+zxkN//14935gZtyYw0RbiC9nXq3Tsf4BUVlhPg1HSirq12GXwTS
RAH6eph4irOylEHvsf/kHN83KSXYX5VyL53Thk/19MkSKwT+0aD78Skasyq02mOC
4lttiFR7g/Jh7he8gPrNQ3Rk6SMe
=Y9op
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '2146bb54-f33a-4750-adc4-124e24f1f92e',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8De3VMLmPmocJGW9RGpSaG/Pq8gPtx1hMk74F65tX+aPb
AkpVEakQDYulDzHa4ruIB0Abv73aL2gdsMKRWcLastVKTJQ0dic+n3pnEsD6IoFm
jXBw526w4TPpMbVYhgcO1JZuywCwwbrqCCeblxdN1+y1CTG/hELdpJ6wMaM6j5Co
mFvl4bpXY2pE6p2S00cmMoJNpgAjO854ZfxqiSYJeIa8vQcAHlOvk9VG02moNaxL
K4eDkpAPs+9bBilZPkPvO+bJYWWX4huFZS7JGGjLpt8GtfPklRP8CVR6AgJEOJM+
StUUaF35F21qHiezcGV2jd4cjH3HMWmor1LiwndOo3+KlsQTuzwwRMsitXqZhUoH
E8kS7Y2019Qr4hM+kLQXi6QUW7ya/rzw5NgOjP8N9VUFCl6kIJ3HrTZjg45uG/Vi
xXSGlT1+QrMBdv6clKFWeoLI6lvrWZi9y41Lt7V7oCOFQwBRMI14zeutG4tpvWEx
mDY4dDvYdpQ1p5T2EyuRf5brWnKOZ30Os1Yb06dHggCzZ32B5Fc94Njz0X7Zh2nS
LVNwjtPan78ipllpex02/UQZ54GS7rrpT52waQrXbfU8RSdxN8tPP3breGkXx0lB
JcKycNW+KCte8rBm+plEgiOpBxAGnawpx2SkQNdAG2Wg2LnD5mRQRSZRfCrFnInS
QQHwiCIVfqpMN4Ezzch+SMhcVhw99qbdUD/2z5VAIUoe21s2NqKOzQ2Zby0TK7fe
qRNQOgelfNXy7D5t0mme0fvv
=4u7k
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '219c620d-fe54-49e5-aefd-9630f39e4f36',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Vq4D3u789is6bCN6uAxpdH1M1b8OrAMvVGR+iZjRsRpJ
+JCn9Yk0MDz2x8KXUwBC2ZSRVL+71XZN8XZmfBPKRmIAZVMDdMZ3kbycP1aeC9zK
rzBy4RVcmyha61cWI23GnJkaJPSRZb8izUO0XdORtkmht6SZIiktSK6fLssWoQdI
ZedOvrvrRg9u9fspQMnb5XFcfEgHo4J+NvPAi2JVmAc+C7hlH/niBItahc+v4N5Y
TowWnZBbQhFDw/rR6jyPBnpLV+uKVkgAvlQ00KRZHfKFvYrEiTzzd98o0CbZYTGZ
6Cb3/WTaMVsXHM2Stku1kOfDNi7+bobap8ne6oXVZIwlkCFWOl1Vb6f1gpIABswM
EWYUfgnJIZQ4LbGGeo0YVasdeU1bjvDKWIOjtbo9TqRyogJ7w2ZpJ3wDqO3Gs+sh
hWidnyHg5+aMTMnT9YZAJniJqv5HVlIuD0npbZ9A4kKrtNYS4SmSrkZ4uRQEiJjZ
3wB1I6hw5uUHh+/+sMCzWEi42rbSX1CFt3JbCl/195CYmYnRm7dX5S0AlRb1MOTi
48zMJ8PQlZ/8oAZLMDzLl6TMskfLLlo9FZXn7s+vs8z7Mwe8C+YHLORKZUB+Kd0v
0O94obofl04bnHDpRGT50gjdluLm8bWobN3xn3wbZPVM0xG74kOOhgZHLI1StBHS
RAFIKJvQwH01CZMneNc70b96Ou2axm4n2dfTrqh5v3h/lWWLpFZsaMdk6ErjM6rO
WSih6t1JV8tNWP2bdECU8+8zThaz
=beug
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '24714019-f21a-4c88-ad82-1431ba669982',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Le0IUh3JJAyQt/huBXuIiCB+ckm2of8j98/AbP8vLx8x
lLRc3Uw7S45e3qTnPESSScw+UWMF30IsO4jaelQihU6nAKxLR1RB2x0qACaOUSHB
UMcTjLow3IQQg3dc7abhUPhnuV0Tf72emy/1uINhY3bDUxlLe+wac/wVRK/3EgTA
18AqC0pHn0Z5b7PDZdtxidBgMGrBVduuAmoR5gAfyUVjp/5nSHsSuFeYogIKztuG
ZIXEbmLU4XFxMOHQoF6/SYQMUwn3EG7e4BYdmk8oszmZOZNwWtjb21JkQlvKK3Ur
Iymq4Zx5uB4JsZsetBWJdCfqzy2tL9JqmGvJ6rQNytfdbUAgQJcEwqgmr/py54Hn
KcqWurahd/libCTh4awQz2nvVSmDxlxHaTnVOLdBDkd3GvrYXOp/A43oAkC7wxkx
jwaAn+Vczt4RUO8tCYJAbgGXxhuB7BxmH7CMarPca009g4FSSFIg5+uWDv7yAu5b
xBLnoV7Xz81cx6MzCfhjJ39iSNO23pH5MD6puKO3qEEdDHdQLDQFq7GDXNaTQRfN
LlZ4AJ3jTwuaUeb+yXfYrx9UBKcuZoF86IgxuReuYaLV/Tp7wI1ic2DsLD6ROBsM
Snb7rxVO6/P5slXu9feUY8xps8en1BOQn8fJVEpp4JQ0LwYO4wuupErx3KQbm7XS
QQGPTShpYLK40eWBrjDt5k74PeWTtKFC3SsUeYi28U2AmNH//6MVEhSHrL+JMh3/
KztQfuD4l9V0qTWKWcxqYQrp
=eguC
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '261a18ed-9297-4059-a7dc-d704cfdb7eac',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAvOXUO861to7fI+zs777RUMNealD1jkuroRrPoDqonRC0
D4is9gdzCSN9woN6UJH+/cIdlNLfLnQawrDRvIbFh8R3mXyyzI4x3QdCKqPSVKU0
agM0GCxnf7m15VXtc2HHFL1IYu/lB9qnBzxHNSjEDJshrtkPGRpoAJ4dFtCiZnWG
/Kouj6O3qKw6m1200Fnz5MNLEQvz2BiW7Qr154GhRmbLhACUSuI18YoFqcK6ViSs
Q6Srti0G6F1Hup+ksUDOvlrEzOmXMP7xZX57QqBTLgcP5clP0djonJjYIsPKHRvf
aVwz/oXE5plo/SbC8BGKM0zr/nlUz0z1DtqAzgVkt+Z/rnmsYFxFTgDsTAlvkrV8
2X2K5OLob47Vh5UdBexax8HHMXqx2dxr2vBZgOxYQMTcU0J1KRD44Sisf21JIOkB
PAOU/Lwr8uWzuujyZC/tZs/WY09iltws3w2vfHhOipOIyrhaKlHfg4LQ5JLX5fBE
nfoPo3QJbW49maR2PP1LLajo28WcC22jBb1BeKoX9QtGiQ8UytH1MmR1jv1SyEdF
KHLlLcoG1tlp8bcPR+SXetXx4HCDxn4dPAEGMnR3v0zZeAGeUtJ/pv8KqwnUCbAY
D+QQp4ftTiNqvn0FQkfAF3lmmFeap0VRLrzfUfM0SO7liIA6RVs5N4OYjIRexWfS
QwHRfdcGqYabQMHxJBv/F8rcCg5le/ru0Rjy+gtlXmJMTvJvcrvTj1Jfa6HTGoSF
iFAlSnFTtmz917di1L7ppWuFDYY=
=EeID
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '2969a424-d507-4681-a8bd-2c3d2d31d070',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/+PYEdmuaP26JdzpodTdi9AU16L0a+liDAyuC1VCdglNiV
G1Y+cuch23RbsjS6d8GVLcAVQ2JVhWRC2nFe9Z6/4m/OynG+7b2M966HncxNurS1
f8ZfYl3axrpL714uL8kW1xG8wJAR7KiocqZEaGUesZyAbsswl+P6zWcHPu4aB8N0
yLJ+PRaV/o9Mh04Ghcwxe0qyxucNiJY8EmPErSNo4hPtvUKlRDSOqeHBLFkbTcIK
mEa5gQcOqIX4c2VeHQr5HWtqr5K2STvSIRaon96YQY7bMb5QOnxDtBjPuIF9lM/R
MlNF2Ls2FGSWfl9Iha6M2TPmz9tVilcyOgxPJZh5SSuPn6lpIhYNu0yl1i7NMHNh
GEUDySMCZUorCjsal6v/2/hzMpkL1OCZNFchTq+5/24YbHcxB7hDixwP7miu341Y
NTzWSal5GiNMKSR0KaA8NR20F9hnqlu/lKbdjirHgEkdHF1KVzoYZHdmKit7VTC1
jdVGXmb2x7DUKhEwy715g53KEtQMnihh3k52+IkCmbWYOW2HxZDB5bhd7gYmGhga
5RgkXRg5bW0Ag2+wSEhwbx+bplonKrUAg+K84nPSjxVhlDtXq1nL2vCSNKYGIpyn
RjQvyVjP2YMrbLonkgUoGbD4/HzL4fhC1P13Iz4DJEz4X3dsH9gFGr8k01YxX/HS
PgE6vClJ6/Tuv9geXYhwAHNneMLRSCrTXCz6i/I25+Y3UI5Y8Afd8c6R5FBR6/N/
4RM7d7mQQcMtZHH3T9a6
=hhI1
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '298eec87-ef35-4b4d-a8d8-8f0b96e0b61f',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//SXUznMDaeNKCsGBhNYoV2GnDHGpMxUulhY0i4VCT8LDi
7S2QRCaCI5SbP1j3ewpPqerIf+JqnSi5gEOKxjPN+e6ZQx96REJijY0jaClTrE+Y
+CC8aKVFnCI+WqbLy6POfm8dE7tQKFpV6ZAZxAqnF0xEluoZ8rug/MPThG3+84E/
0P3uqY9IlKlJG2H+phZRX3neDheom1plGTAWZ11nTH4vYheNeYjha4Nn5/ZSyf1C
uTuhkanYByqbOVJ+YRZi169f4GItN+/gNwqqzc+n42TTYeW3mkMGdgkKJul4Ctxr
NqzWCDasVAdrU/WCTF/qY+BNV7a+FsGOeRN3Fc9eY/YKXgRzNJUrrGTv9oBG37qj
2bsY4mCHDJmjTrCJal1a/YlnIRvSSo/QmDPVyo1PvsKqzVqEZq0p4nc4euT1P9YM
LYswbZruPMHLoWSN+qkc090R4KaElaAalWkU5gJRNnWsAFfAbDS64I0J0j01WXFz
9phGfUVPP4u6bi3EZdpMgM4QYkQOFUq1fUYjaLyDOAPz4aBN6T2d/XYi+W4tK1Ne
3uW1u1VoIKDdmzCsCwAwCMbAhCpo/dVS4XWsf9hSEf6WQNwaWpbnSl8oBrDBvKAO
dRKJUaROVi41YxdvOE6lOJsMBXzMSrGRmkPY9zMvp64gK1JdVfOfU3mBTRoBkljS
PgHNXJoy9Mp1qlGhZXpxPu5Oc7g0TIZHKOMgi77kaYwwbOFg+fCBO9M3iMGFAnnK
wg+mR1nozqTHWGNU/5xM
=Aetd
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '29aa51ca-f061-44c8-a1cb-bf736dbf0805',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9HL+Dv8qObFlh8SO8Hzz5imMnE2scBSA/aD/eXs0hJSbJ
YqQ+vlv1YghYJEV193w3sWB/wH/55rDvvMDpVARXmjWZmonk899vKUbV35YOhVd/
Nl2W3uJ4rs/XtDXM7nBR29smJVS/TK3HcPm5rc1aKAlp23RZENaBPrXD2j9YNZeJ
hpkr++oBMY6oFut8aOgK+00K0xRaoGAG8pj+XPGVvf3kftaeWQnxGWZlxWa/IiYu
mock+R191NO6WnS3HnzXxR83zsQLfvNzc/bpUqBVcdb5T3fnLRh+91CkTFF7xiMM
IcfTGTJStFni8m66CJ54GLf7ZrsDFjpsAsROtes4WYrnpx8svc2VE3NTVMK7YrCI
fL11nLDjZDqkSF2koI8vrV0ep5ZFW84+yFc4arC1bA5285DTrklHRptmnxECSqbu
FnxfiA6vBLdRqKzWxEOqRGb8PYzN9XYe5TGiH1heqysjQltpz/kb66rglXRgN5rt
Uy23VyNU3CC+itEGnr1bH4qMqPOcnSX6HU38XltSaQegNhgjfJYlz6ZjN9ZlUm1e
E43fGnent4FDx5M0Xkm1StWDXdbFTdnYlpap+TQHGMVF/kDk1twwJ1+lhFwJKjLr
dJiX8Wv4VWtuzkJfC9bxPTAZXt+otgzfKc3cKxWOIx+y/UMoetv8oiWWDy8A1rTS
TQE9+WRcn6/STYSP2OZfeBrBVI3tu2MZKRtfaDRipWfM6jodv+zRR/O/4VNNKZtN
+L7OcO5UAjv74U6I23C8Sdqs7OQkglsbtiEZMnLr
=QAUq
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '2bfd27cb-a6da-4922-aee9-5e8ecae385b3',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAiuA74pzpeZ+QcBq85iyZqHGn/ryo7xFtq3Gvk12tNKd8
PlGOryMbhtyEfGXN04iRLtQdsY6TB+2yRnqvdoa6gQ0yStKm1A5Rr92B+MntBv3j
PaylWq7GM0PpkelPZ73knW+06Wx/fHJb7hl4oA2IML78FkwaMF6CXqcp4tm3tkmJ
qIDGkfIrSiix6XCKWImW46T+ng33sQbvuEI5l0jdAZebZm/uK9V48s1z0iB8dwGq
gR1PuIwip0cg+mo4MceG3vy4PAm9FtkLg56Qk+Qrsgil3NvuL1GtFEfEVOpixNAh
LhIlIrQi3Eyk4zFAqYHZoq+O/kv4GGrfHfglc6BjD0rpX/0+IMN/s5WEvzN7XDBV
SpfDkT1vimitEgP4pa9GLY7aeVIuHr2ELuXwMKV0Txt8/MLmZ5/eQ+Wfd4uefjYu
yAUdugV0DYIJLU65lDFvB/s3FHk/3iRRnw2eP7GQOb7BO3M7vMih2bXnRXx2usY7
1WF/JCeZsthoXREi/zuEPnlRMty0Mco95YuiB23edQGG1RvriGW9YHJI0pcqEw6m
KKAixn24avG2KBXrAg55EUZS9A1sN0WUaqYk+v2LE9gvrORHFJ7w/wWHFMiU0qnr
2LkD1MKhDHv6BlySh3AEbBoW7l5dqevIeh1fc6Fy/11yfp7uQpK5pibXg0zqSbrS
QQGWYZvTflEaZTliOLmICibLWGyAPCiOpy9FgQXgkm1rI4jOPPKEyw9nyhBhQZ9i
kNHl2nzL9UvFnW4ah0xIaT3N
=4Ndz
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '2c2cc511-be92-4988-a556-e65206973c18',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Wb3PZVr19NoFZ4RrUjWp/I1NpFHC8b0ovs/ZBjEUNJr0
TlmTkiIxtCiMFhlkR0xIMDXMzIDPOsA8iIdMFDyCpjXapQFFDFCQ/QJBPHYslYVD
d3X7wKQrk/yfvL9nx1oj0AkoT4IQvV+jupxSF/A7NH6qe4lOjfhAntZ/e2691/JZ
tVX/t+7DQKnGF0mE2Uz6OgivdX1AyqtYe/wKsjzlotm0+lASyb9grq1WDNJ1ZK43
34PEqb24gT/BSASsUPiY+/G+z3bK7mrGi160Onnpu1R33EQGukRbAZEhufQ44Dfn
dCy/nYpMOJBzMUnpACQH/husj+PWGSMwhY9g+AZH6AEr9lV6R32EyttjqpuaOvSa
1pPjy/l9SFzoqxd04GwBwmx/ySX8Qv7qqo7c9deRC/3AfCxCfgh29E3t1yyMwKyV
Se/yWFDLbTy4JdZC+OrSkkO59QN61rcLhTfdMHeR/ldh3cbciSc8r3iRwoUIPQZ0
/4XosnIMJ/2DimwjXGM49Vdfw49ZMeWPwB+XlQZ96+/6xB5OzDOIu+r0GweyEc5D
dQFEVnF8u1Kulmbk+nG9/KyeVfc1iX+d4gB+KGVDcKS9gaeJU3WPsCjsJ3ZT/mw2
9dYbxQO54LERq4zwHRf72oXnGlEvvdlYTbh6EqDliuodRewg6PbpG+0gXNJZNBXS
QwFIqulE/GG+9CsYBHQdiPCJnJc6GU5gAJ4GbjBtoLVwdfMe7YI4MqL4q8V/Gfk5
XRU7r9wDSurLKYSVmlRug8LJsTY=
=Ymc4
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '2d070882-d8b6-4ed9-aaed-e55ac06cd6fa',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAxBcCurtNY16tvn1sigXrZTOEEcnP77coYNbLyUbMHTnR
pkMtAJ3D8MPp73dRk0Pxtse/IysKiILKOoXiCmabyL7Zthe6ckZQiysWECgzaKOZ
kzi/bD2l1gJ1M3S23mPD9bD4xp22XUW+wwZ5Hi0bWfHw0wWxV3u8gzb87XAufaXc
f/BWtX+Mk5KFxqPYZCQbsFzK6SZmwW/IcVLz2FGcHv8OhG4Fv35Yfv7+C0nKU9FN
FrQgxN/lJqK+DN2g/qlGYjkdNHMK/XE+LsgbFF+XVHA4kQ6XnVze/q8sIus66r5r
c1Ece25unKjBQyKPtLlz0aBrcl0OyceQ7lfFTMj9Oz1TcsnVQEG9VHIhFV/1pxPb
8LU5KSrGIr3kwXrNOkS/MvbBkQeJgoBIRmcE/gIxuahexR6DHBwcZ1mBtXvvtvHa
uBF4TDTPI5jz6o4kIgPux2LBOylxmsfedIPK3ckIhjbITH149OB99pBNzUFfdEPx
L/ZvSq4ETcaBM6ppaT52ZoRMd4Scx+3LkEcQ7hYw4Cbf4Pe84dCTwKSJmAoE5alx
NJ13o5/W2X4PMC7uCvRHVvW51vag4apGHwIthQK47vL2IMPCrBpbXNG8btQQh8fi
c+bFXcjh5eTD1a94Xh5a6th7NEYU+shTz0nSvrgW7FhI5ymlWoWAu4/1OYe2gEzS
PwFhoGC2cTifIdmp0jfiDTNbMX+ZIk6vU4EHNwu9BxgHdNsVe3hqEHP+Ybo0vUb0
3b8Lp811PKUQvxejgC/22g==
=IOYs
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '2f886bf8-7d0a-483d-a1d7-18c8a8b890f7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAuLUerqQKvnR+R64xpVmGXUh5yiM9GzL/+ZcgRAMBNyK5
KqsbG/eOfsmbcbYoHP//gRI09+MNl6eUJwB+VNS2TvcEpyk7kcsfCVM43zIJt1fY
ZO2SlBWqsiwaS1kapex8F6JlxdVEDUD0rHru2jnEfpQWZIePc9CpGeiB6PQ+GblA
40Vkf5ruUtSRumuisjGoNCBCVcIVHm9kNKwX3f2iW3oFPoLcqqnw95byIM/N4mep
ndxcfduKqlLk0quUf8e2sHnmEX3vVGYy6U1agDb/a1ZoW1MNsCjNuNIcPzGAZIl4
xNXj+OTxXcUS8/jB1KO+M9Zipi/ZVF0QWzQs5v7pkRPPf2a7kFwY5B+y8vKq0Q8B
GCzJa+W0bvcpLDn23NVLepDyfeQ4B+CBpDws6km4Way/lfKVI+2OkReipnSoVlhe
GF+DHWSdC5kO8fQdllxACc/I1CTuOzdxlT+9BR/mf43mWDFY0mDMJowWgQzvbuF7
QMng2AHvyEGgaijicuwnObUPcc2slJyT5tXJZktm52O/S3NhhgqhcV2puqbEQSVR
aCyXfA8lHosWUIX8njJcAPX83v7zuY8gbOWVJkQtzNgcCxuHEpFLwEYMnfR7L9/S
SL+z8GkX649N8Lw8aEnqqOWjuPcBubTVYrmlSPqqfqmBBtYIcWb6SjUe/vPI6VvS
QAF6e3xIBibQ6s03yAGlO0+Nbglw+dTwPSH8aeG3gaIbY8IFH7ElKLWavpGveTrX
Ih90ubOQwePsjo5/Z6NSQII=
=RwN0
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '31cff2d3-bbbb-4294-ad73-6b0a7cad6cb6',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//RCnq1/Ijv+qDGQgzk8s0Ud11TXTwbw6xPj8lGJOECk7X
4w52mEJAJjBVjmc2ANs3jVdx7wg0oyWIbX3sXu1a8tJQ6V8HRT3vySrqi2ULE2TN
suUvQ9Mmt5kNW2CO4lKuloUrbcCmVaTMU8cmA8zKPooBFEOdg4U4TR288rohbd53
CXILBii2Ua3/0gf8XLUcna35LCmbDzuHMw3n+5eRs+m0iM5dODv0zrXmorTlYN0w
UE3z2GmIpF2lvdLmmyySg1obm+IwLwnkFkDfL90ddnA865vKutqxrDB9aW9QLAB5
Ji8+0cOlS3rjPwT3n1PgIlAhLZGZN3olNuGfAhr4Ffqu+wQ7objCse9eVsp0lK6t
MuWOwSyCMXrrg92bZviY39X9o3Ud8A6etAA2elGOA18TfIv8SorNI69Et3t9v02C
57rsH22XC0BcCOv9NhaQx6lM3HvGonhxcOIDLHuH9u9wDwlTz7PkHkUdf+obE3WG
tOSsfGCf1DuFJ6BHkeTQSpMarI3nc/Fs3eSKAJhqRks/ltI+pvaGn3DOEaX8WcOi
9+XJDt/U9BYStDG4fIIJNfE7UKVa4fCb6xOpREbHWhxvGr7d/zLPX/yJSFZqJPlH
mHU/L3T7B9KJyjKoapX6S+VK2ujuUiKyFINqeR0lgzhslUlhuEnorjsF1a817TTS
QgFbinnz+rWRT/xS9SLe1AUn9/uteN2MGdxalPXbtqIOy4HcHui4BiIeBWgDcV/s
NZvKDdzc0GIZNXGJloVNYMg8ew==
=LxNX
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '34145fe5-12b2-412b-abfd-6354b1cc6ec3',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+Pzp+0r0pZi8DqIkPsR7IKFqT5JauMg9ml76+JrquVQrp
p+vNWSXLc/s2xngNT8D5Hq98a++1ci5VtG4/vadjHVdTo6stdoD2LDrqjIq/BfLY
yPkqGRaTJJg89Ko+aKmOHQsPx7uAJFmXyAGcN6WkF4SHJJ7bhmi1B/Bdp1OwX2fE
5595Dy4UpIeyg1S76fU5KtIeWpH0GSxYC+gNyZwItDrW0yygiy5ITWewivYk3G7b
JJc5ioip1axinlTdl8B+z5L1qKnbr1/olceU8ue5e/+tXCOn+THVuJeLBqMEBpZL
VYx6WoJ7Int+B42LJjwMXtrXGymsnnwfn+eOKrXNJQ4NIxtQ/zgFe2sVvQA/SEVs
Oisjkj+ArJuT19czeGJ+jUoyrecoCk/VvI2NzE/zWdpdp/VkNpX/KfbSK1gAfsju
ULJkVljEEANuok3ctF+g9D2jUoKNqzXlHvsyhAkLwFgmgetheFmsj9Q9Tlv4V8dy
pUSCXhUH1WZyetdTGeQDKiTI2iGXOX9owHnkbm25en/AzXc5Ni+p8BitQhAWtKSl
ILHeTicuUwMOfO42HekPH2ngqSCY5kpHQY6+nBLnoEVmJ3I9oFDT6TX2u2lTC+0y
OkYaXOPxLH08REkXxp4aIghure3sKc3fEW5tnicmDKekC448+jWr6O6GIkQ0nVXS
QwEMgDludqDKItNAjmlgOOfoXdff8yFl4QtI4y3RpiJ1iUbpZEFDonK5UcxbaHwp
hvBVy1LXaeWK5c1RfYAs3vI++nU=
=h0yM
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '39800887-ae40-4e73-a7c1-6fb8af4c1590',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/8D0K41Bt4r+44MsV67fbRjjzrHSjyefautLXcb3rqR3jd
yPaTa8v6Kxh/gA2J3mfMj8Q6y0XOYzX21RYL9mRF5xdP0ItL9EvIggNfE30lLFSC
xF5A5G8hgvfleVL/EZV7FMJskgmJ5iuBN+ZlifFudPTRhuxdY1iBA6pfHLxpBDVx
GxIy9oIb+tg+Lqh8nHGYcWR0M6bMLH72eNyGn8eTFhVpeLpRjqj0xpL57ib36cB3
9kildC1XgHCLAKAHDfQee+8tYvBcPQA6IpTALO1FGGFldbsTzGil3m3B9vB2daZz
d/kDY2LoKbMgTKzFW0kNDCy3O7qhu3FvLa/3Hhdx+/8ptlS13wxt7PCXLHHqayio
nZsYaGp7LoPAWiHbYJ3eY1TxVdrp1ywo4C7g1y1W84x6eYsqxZwMLBXNbMXgdUOu
kJEFT7XKfYkZz1zu6AS3oY9lmNCytgQqfy8fTycwSmhUmZZtFKc2Ilr9LfM4eDWC
NGaaS15O3w960PGMke087Yy04S5dqfQ8ZKq63fOLmWF4sN0dkXNdiUDpzBMqNzWv
OI+ygiu7laJK1Pz6sOMoU8KGSLtBOAd41ko083nI2uMXGZgxe/jwKKWX3iKr08OZ
61DZTjs6vu3nlduqD4e9KqF3iOSWBAqxFlqN+7fHN1Aw86xOJM+Uw6GSPioOyv/S
RAFa0q0yB61mVNGSPW6Rb3r2813wJq5OLyHedQ+NA8nrtjN2VkMxNgBUjF+CN3Z+
cp5gIQKcxhYZcpKHJ9HEvoLY2Txr
=7ad9
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '3d517aff-4367-4754-a2f5-574a6b6d3402',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAiwEGN7J+occJp49lE3ybrQzxw9zZFZNYBjKtR/2c1xGz
tgKyBAttk4OiexA544ypNWTUsPaRPjVs7LJ3JIPxDa9cxtZkbO6F0I+Q4mbnBPgU
ZBZCC4vbqASjRF2cRh++hhSWscWKlUbT+/4/tWUR4PMto0d0CxlvpTV6NsBsF8Ce
9KOR0juX4ErWFQTAx3LpXCDDWun2ZTRuvDcPxcrNwh370FwrniJmqJR4BBphUKSe
P9UuUX7NIMmsGHOsU03llFN7lgJUojf0ZZ1ehMjlcKkmTxzQQAHc8XbxGsYuanZ2
Eg3WyEbVXJKlvTEHH5A0plrcBXjUFCKmdpQp+esd0On5po+s+gZRRfaYHdkgAnZi
IUrbp0k+r188Nl1Qs7+kfZjPAMr0mfph0O4sgNlEIb6Mj5ddv87YHiREqnlvpKFF
14rrm/jhgCu84alNwvvFf+HUgcgmQSvotQ4B0lFobgPgQx7Kk9/uif+XuWnjI7wG
VhOMU0Y4dROYVYJ/kup2dcixZpF9MPXeydL50EbzXrkjTEg6goZ+WFOIv0/yYzF0
yePoQizhd7puB+AWqhZHvyzaIH2pd3yWdVXGcxre3pUg3JiowfkpuQZ/pxoH+u7V
59n3Nhs11zLlK3EwPAVDj8HbcMzLP1yQn5FLeLnQJCToActzVSbr0Oz7I5P0wP3S
QQG1JoVpKlnDOlkrBXVs4Qhf0Id8H4ekjUuIg3caMLDk9pOjKxUDqtR343mDRUqZ
NbkzENKHt/oniwhiUezTzmLI
=gUTj
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '42b904f1-55e8-4d78-a9f4-d02362a6a5a5',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAhwjC/WZKgudxt5zoJWzwhpp6es8k8vXDpvF7FDELltfE
5kG0pxPdvUlWyy0hMX95kglK06+S226/FqZeqvtckEGVoAdd8cRgfgfH/rRpMrwd
9BlAdPKAQjbG8pVStM2NDVn0N7+zWLhyMrjwrX0HQmRbRZJN77enc7/M+DjeIkoY
ak1nRznNZjUr0Gt/t4k4Wd4GxpJz/NwkLUqBZLQtMe+UVY8/Osnd5sZAUi+hFCyu
8oxhStsrb3g8YccUNMfc8UGDWAnsa3+zlVJ7p64dm6l/XHkbGpLK4awGbnjxMsJO
9pgFXg+QrxGLFDEBXHHUMHDg4DAoeCIFtyasOhK1smhn9VH316bNLTLE4MQIiZsz
v9+E1rxgMb9aBjONTzUP3lxI/fS0QYe4c65rAmelF27GYdP+7zcAk+EAnO4UW+/r
JoGJe8iIyjuMuIWqDGEHBadzRWEkWjF7spgQ8jRfE108NNzPgqw043SCOvRbBbH7
EMrfh+MTskE2191iiL3l3aVIqu7+Dg93rEFHyh/YpvLDvARhKsak7cMuF0QFRwIR
LPF37nLNp1jvzc/lYL3fUUmlw51MlM/+98LQdKhmvVT7lghUxM8ibBoO7SjanN4U
F75VZnThxz7dCIIXGdfka2tV2xQbvR2lZwkWcmv8KTnJL9/snEpYuHkawLMSUCnS
TQG60E8eXWeWOpRnYC7JCLUsoxQ7W4zCBQvmjas33DKdFxThyc4d5NgWNbsLrlxr
lmktnxkDHf3T6QbqnyYx+3AubQUIt/QpMj1C5tKV
=dIFA
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '430b8a7b-bcc1-4c9a-a0ed-f9a847a0778f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+PoeB4gkbExxp6hEjpQUNccXLGy49aodTotvBqZAMUg7i
VignSPuUvu1JRqrt4baAxsDzjPaAGCIYpy0NtJeUBFyk6MGLXIAoON7OTpCkGcak
ec/ipc2bKfqoESvG0Sihc6VCLJGOTDP0MxnaNWG6aFssfBaKLKJFK+bgXkpGaNrF
BzcW2ib3I0JcQ2cDatfhU+lcQNRL501aC6dpVniakwC4gxfUQzBcFt064oNNLTqv
kJteENA1srPQwZ5A1ylfuhKjBcmqdUjWEgbMCn6lZMLOOXdSLRAWGLGimoHBGKob
T/olVdiv4LlWrUq+M8YtJcFGvcYk8Wx8nk9LXLBQRqSiKa8jidGK8zpKYLYJU7UA
l8iVZqS9GsUtGEyhsArLXi+zUcbdZiuJWO27Ve/kpwBr9U46MIFv9iwk5WYjkLBH
WwWQHF3ixF0D5Wlzo+rP3qiEKvU9zzFSRqYjyud4+b0/ZoiQAWM8qrQ/4595S51H
NZnhQPl/N445zFKrVnR+c0edXlRUFAfWR0VJKAXIhqBd45NENYZK+6CawisLw+Wd
T9PCeZiox359wOe3O8s9dpfdMDg7xz/SFawFsuQ05V1Qo+m1Sd3XK4t/yQC4Xmhp
r2QS3oe9tHQ/96qPQqVBHHecIOEPIGz1DmoFwO2SNfwwKyK7e6WwtjVg06eLzJfS
QwEplurLxagLlHQT955+mVsIHVmxacyjvlMm5aT4hxIb2dlsUfEyq+//f+ccHmE/
RfBIDEUhgr93vxuiFnfpQex7oDo=
=t+Ay
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '44d58f39-193e-4839-a3d2-22e60a1f9671',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvp+wZ4ZkMpDOhhh+lOurmvwhVHfYmzZiQ/gdWzlkLgWl
PktnP+mEU3Dl4tyfWxvYBg7yxDgnc7Rjz9uK5bj3qOrgD76djrWQ3VQzvXH49u2z
JSrlohZEH6vLVg4T1QgyvFUv4/7rXcma9RDWbgLNZF5dktTqMhLpnWDCgCvEHwv/
rKZ8pxHhajf8cOj1LkIuhjLYfQmfIptn27897A2u15FPrKuy4YTaQHbv9dFEQ7yJ
qfOvLeNPhWsmY526tCwGcAFSFA0Sk5G3ueuEW5bkoZ5tNDIqTZBxQkDbb1QypQAB
/HAxhfbymOcgvRwas2XldXpCpD1sJ2VX6DJtmEtRph8bRqJufDbrGK+bxAv4CxAC
h6WAc+RQg5+tZiOiBDsULzYD6eIS4efjrJc65zWImYo4ii8zS8FOwJ+dT4YXbyd3
uEUUZV7yvcreV5R7rpd85hnSskEBvKYjQuH6f46q7maZy4ObiZblIezgYptu0+FP
bCXnXUshRiV6evD3qv7qyB8aVoOZAPhaFIjBADcvKkTgg9rKm8FiQjvscQMYGvAf
zD6stRdpTE2KuX0UIE8L5nh61Wa8wDYu/OPmoc74M1wkmb604RupbNJNJpqVPEBQ
3caJUIpbtxZI5f/Ouc4RTlVT3YEXYZSvje01pwwJVDeF5EBBkjdI7Y7W4nS9hB7S
QwE4QbwbumQqJgFIK2ULyKJu/0zlA6KfSRgvxNWYHRxZA5axZGXPM5bVzhrwbiTv
wlKLWO/TUo1ofY15WGJYQWu4lkE=
=mrxN
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '4558b723-6fe9-4501-a874-5dc168beabab',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//bGjI1BD6im4sknLRZqt0qxb5mBHrklxs8EzLZwtA4TWN
syqqtn6pFHGZdIO7DrquxFroQWKDnBSwyv7mrCv5AUsey8Ql6cTpMJbazElSSPHs
XYnnqwnQ8PxAQkYR+i6wAbtKnAXGpj70XNOyrPrcVo6NXv+kB+TNuIdJPopdbGib
SIq9yNMJimHipS9UyQM1gn5lai0xK1YT9zMKDGp96q771jl0Q0sVbO9r67T48pzC
TrrESDF9X9lF/ORpOtRSDUIczpi/InM0P1hn+OzFKEFsLK/3Yisds8hAEYj4pxRD
Oe1+yjngxQWKbHsLIGu4rO2gkqiMW5tGUrUdAl1qn4UxOtIukCIhDrbMH4Nd4fSU
O+pTzO/rMxW4/MJ6fSLcM8Zkjeyqg9lG+eAhL0H93uME4qfv5/U//n/XVqEnoMJ6
QKQjU+wXOjfkwbtqe2vjDFrqhdXHMtbuCHsa0X3F/BDPPLLRZhvSixGQTSTMUlby
fHbI31MJuk78obCtkgv/TqMBFN0JQaXAVqdQFhRQFsgjP9NnJrhFUhmsoRKKTml6
Yk0hJw7tNt4FyBMWgg75UERT+RWqir0LToryfY3HVGbAeEPeDd32YnNEEqUoW7sk
x5hodLnul8aCJcJOj+smu8ShC1sYWkFZdcCAx1TMjXij8JvGIN7SHbJpwdUw83bS
QQGMfTQ/QqYfwyz6kDmAysmHowAsI6UVNb4khrbwZoKyOPeHrh1YUFYDjK+Otw12
0JrBehN3B8SPdrSpdLsE8S20
=9GhW
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '474db142-a4b9-4d7d-a92e-5f6f5bb04e50',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//c78xx1hBHG0qmXI7Cz0uZ+wB8oeaejltNPZ8BpSyQrfq
p0LOiEVKFMNU8DbK3F1TlfkucUNd05YUMghWCR0/lMGZwNlLL/XDn534CIXZNEaf
9UT8zBoVBvYlkcxDUZ6tcYXEDTmJeMagk3no2QVNlXKAI7ydgJI32ghWHdFMFq6d
d0QJBKejmvOCqGxSLYvdl0SFERS+cZtNXzYDaGuJZU0GZOEKV8/QKPlc2Uh6Pcwf
fyIIlKfZTrQCLJxTfEh9Q/DFi3G1E2W2Ru3dDoYyYx7yTK3DR5Ety2RvpuZUVpOp
izyOvCV3sK1ocs0MJRNRyZBYMLpiGt5f0LVSkbK7KKriith7H8GSoBtVLyQ03c3V
3AZ4ob3WJnpKRdWJBV9asLplSBx+u4n/6XwhIJp1RctdXwA06HDV86WTZ/ScgJ6i
2paZcOIE728uCPvr6oJwP/c/FJX7XgHjsI87G9pygFOd6EX3sNQBj5IyXqtVscq7
KJPifNFmGzgWMPHWn3v0k3SySOp1qIFwLsHPPYVRBA9b3uqctTG1XNNJqDLMyh7v
bhzG6zZBBUu+mnWe+/l1G/HvaQvkX0+TcPF1Px0GiNCWHUqwdC0J9MbWbQ8yyFvF
jAITLcfKDz5B8n0+N/O4WzRceNR88ukvrvpcTNtZ3PH9xoJCk0qF6aKWS84UgIHS
QwGu6LRO5ljlgz1rJswpZ0qe4ReNoYyHTiNQAHWROZtHJhKou66870sdy2JKb4pr
eqyaDX4JvJsIzViYVeSnPKOYhuw=
=i20D
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '47585890-6d9d-4a9d-a56f-070297fb14a5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAslFnch+89XBoHyDSvlnjucpq+WaJacl47sKa2RKv5NpU
lGm5BgZqssPpF9/q3nxIekALfNK502QOl3JXOOYpJQRAx+mbRQbU5AYVd61CWCmk
uYWreiKBLHdiBp8x3JflKfXYT3iFnr5lMZtWZuY4smfnoQB34ZYaFPxRMQLhFieu
a3jKwDjeaniSv0GxR5AuhYT4MRpWjQcm30swIoiPpPMQqz7LOrz+n88Pv5AL5sVF
SEbhL8f6iVUsQnsdeOvom0o5uPt+m6ic7kiA4bGOhg8I7/HzBhYVE9r237JaFX5l
nl/HDLLL8GmvS6PMz/uOuWapw2Qk4ENWpS/fjK6l8Ec9P7M7tpNlHE7kEfhALDBM
uz4T+N6BSSZyK/ycryrxw1tzmNynrMBBBWOCgk0+4dEFIhTODm7oJqyxZl7UmM3s
AyMe4/g5etgoxY10+yX9YDzcLx2XBe/GPnHBD9Nq14tZittumoTvFDKfqGBuhfMM
bOUpzp5U9RtdAzx/7oe666v/Hn2Til1+Jza4s/v+P7n3Pm3xo9WcfcdLNmuPpXDY
jWfSQ3PGmLyEbQiph1svCA/Jlog4kkU1etbj63fBQ7l5gVWnhBvID/DMQ/cpp++R
/LStVDAf5HOqod8WlOJMwiPijzmxgQIaP9w3/SO++eS/nR09JqSp7ARYnfOavo/S
RAGtMRwM/SOfTlpTD6Z28vLDl5OrgF4e+09GDHoyfoWEyAMKI/4Z7w8hfXTYBB4Q
XGv0FZJPKQ76PIK5OQmHZfL9ogE4
=pUXU
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '484d896f-9506-488c-abb2-9e3b394d0a8f',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9GPZ9Y59vNtN31EkObDD32AWT+cQ3UbTvRbF8ks8t1MCT
70ehcrHTqvcsLdKZH7jRWzlFPwhhMlm0nOcSgLdwPnr3PWcM++1A31Y5tJ6d+6cg
M9uB5du+lUdHARJ0NRrEChVwg0VT0qI+5fbM6sFe72P5gyOvZjDMrRXTEMWpp9rF
KDRa6aDgEChw0aNwA3r3ivZMsfTVWwgCyCa6zxIfJN633Ij4LGv+vEAkurZzfl4U
X010Zjpf1dKJPErejBwIGGuMvuZPoGH3dlohigy4BH+sqjtCJoSlGVtFPKqeq+rM
p0yVC6MQrURCpqxOvfmYhXVdcrw9+5PnGvFlNOTNYqGeke45Ynjd+nWg8s2cUf7n
BNjJ8QSmKzad/9C0YcsS4fAY/gw2XGWfYx0Rszc6rEn9mm/a0eo/XyiwGs/TllNx
z+N9vckk0cWFlBgwPnf1JS7mAzUKGTUkS1ERN49nZISB0wuhUy5xz+Iv1XESSrAd
GOF8K5fOhuqB5VtX8YfiCDSSqADKw9qkhSkzkoTH0dbaY8mGA8PtZNQO+ZNd6gnJ
P/GCI1L/wAmvpXCxURRjB73qtkvvrAN4FJfbJCdyhuocCEJbq8wNf0ix73t51Fcx
Hj+nD1vdO6g+C9TU2vru9Ng9MzTIm9arBwEYCCUt7Ucb2fdmx5nmLXM6NdomWCnS
QwHjw75PbCA3Ekb4y8K2TWEDv8ErxF3bY64HZ0DEekiha8I/3Xxf8/bTZe1Tyb8M
6ULuWFf4R7uvDEOUu5rooFf3sxw=
=UKpT
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '48cbec5a-9be2-4043-abd9-420f58a02667',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//Wvr/AioLKsaLuNqS4yPe0/enu8Heuw8fAUegFCMWTiRH
6WR+cPI2pzMWoQZJuxR9gQzFlsKmQnZsNMrD1+HMgq9uxnvWbaay8X/vG8D1Pfyq
DxOfPzum6vK5y/iVhf0jNWLhxhV0fpepj7z/thvQ8e6DTC4ZOE8fXZPgLNksZbUt
r7BzAVJg8IkA1U1vjvol4G8ZXFf0fISXhZJvIAmRWb9Mu65Tw1aiUn2DjArHAAuw
Muyh5zVJbiQI8sdyN4nsd5V92vJrMb4mJC6ath8c3gdvI+9pvfN4hb62gGpdTM7X
2Lg1gWub1wqh7LCxwaor3W/kobOwUaKYoON3TVr9ECPDDofWZS9dS1MztsUoq3Qo
w+O2qxKLD075ObShR8879X6gP/yv6pyAEVD7ObyGPg5eSE9L8RJWcC/FItb0ltzu
TZaO/Xf01ycWIbw+JdRFd/ZoSwv+MC7Hl1wC+vbNRZMynEjssTb88G6h0a1Pyaqc
SBiomeYPzm7vDPosAMZBdkRL7pfkUPeV9umLsRNrUI7pQ0a0m41gabdwpJ4vCYt6
GBCk+AXsDD1Mu72WeUwPUvdTXVn4NNAxEqNyxfhqnk+IdfTdUwFizQFS59C5H9WF
tZcM3rMx8MQHaBh/JiVJmhMpoGL5p5CyICLSwox548Brm17tV50NVbdlgGawYoTS
QgFP3FIU0QzRV7J00UQ98HnSTDhbJqU4dM2GBgBf44/FDrakgeZpMlgisZaTeq8d
DS7/v59jDcBNm/R+bgx6ra2sCw==
=iMGC
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '4aff92af-ccbb-424a-aeb6-7661f192b687',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Nf1pk0ToxRoB+uLkU4M8gGjguf/9yjEB9T0WPz9GQgLN
pMgH434CWPCXgZVqevXTvjbuY2zoNBj1CLBmIVk8NlPvIWpLaHRUAU2kJ/6+jzWz
bDs1JNMBC4ESMMDstkOcAIQdKi4a/ue+1B0mvvIzrIcmeVUdIHTc9e7lckcl9GmK
BKdjA8hJcK3d+8z8ynSlo4PCyFprNMJDEUwE+7vRNrGPrctPQ7XbcrD7wnRoF7Ba
1BVE/9DElWzL/CHJXZ9tFXfLXGMZhPIyszsDQTe0tU2ejhc/Mv4QaN6fCCo+27vi
gpFNTqYvcLK0LxUjOlYJUS1LN8EJ4B/r0Jko9hr5KuhKd6X+cFThMZ6pqgMve+0P
XbFZtBdhutVhaTuqvvNlB8/mYOnK2B4hwvmvGIjE1yMtuiMNbkaF+Rl2X+s3SlfG
WvR3FA4M/6LS1p45b5a8lGiiVC+RuJ7epfGM00bIx1upyRz+3FfhA2r59mzNSEAg
/91yKTtv+sK4X2OxLXVgY/QY3Or0QRR8Y35Bn+klv0ztHITQTiE0EzQuGAMv6Q0o
reYufEgjAWCRCFg9sl4eq9g4jN7idNnzeDgU7gCgQiOnNbBwEDUyOOrDTyu9PAd4
7G/Em8NZHO3Am6KPz/o4qKxZ9TRNG85fKkwviH2wdxLzKLDEH+ZajLGBb8QdlrzS
RAGjW0rnfQ1UosrNoGjxHdKsnh3bBJ91AKTEQz0IpIIds6LTf1bP6gLYCRePwVFD
NVKHW56+Mi48qc6x9aZFmbEFXJ69
=M9Eu
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '57f96e2c-5e31-42d6-af84-bc541f603c8c',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9ES9ebe4mB6TWjabgCNQrwJKnPA1RMg211wIf7aXVQVuV
IKgCL/qOcC3XRXhbOlzgFZiBWipRPPjqaOfwVUgKfpdtWKWOYNaEOX81sKAmcc3y
N5Kcexmg5/RDSK+xOR+9yJtKPwPmqYRdL8XRmGLPDHx3zByMgTu5y9rZSHHh8lDJ
Y6ceE2FP9JspyHVk05lNxQYpg/PwwTTRgHkc+PmjUAZ4hUrjCQgRUEpRRh78VYpU
GoPrdChIKXkZplNujrGnJGARNJAsMvt4h38PmEpXyOMvbr3bYlsnIbdPOPIbTmmj
ohRveF2sGcZaPKpgdgRTzj9OyF6Tsa1qr0tpCjEnEZnHXIw2aumI1pwXc1I3ye+d
0QlUJyMV0JOj3vTtpVLyZF10TfXk/ckUW6K5igj9bjzWjz6moJFK3iOee3YOr6Hr
EX0bRgea6IDgRcvP74N7rbiu3oowsHMNfmfjSBgjhlEsRZIrULLODv7wdSOB5iQn
2wE4qoLzhTSvKTy7yfIyE/z1EdfkTcbtncLKuPAm/01KJt3pGjTzrNd9XdIcwLdP
ny9XVXLl/uaI1MwCszNrQOv7nls1IxPn4I+6OzAenghLMP036KrnlXiSKFA/zoLx
QQooB9/wzARw8qhFKGOufYmb4bPAgqsJpaloL+zIl0fVrK+0lvHYkk0SyQhJgR7S
QQGPIV9JOvDTuQaLcwYmTP4Q57QIs647QmKVmg9nV34yfaIjDq/vNmKE3ul9lQWL
vnXzQ3/Dl/7pqwV4hospa721
=Ny1q
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '5887bba0-8b22-4f83-a78b-8ef567a1fdd8',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAozk2NwJe/rGEHgqNiNpdMODSrFIda1uRswmm6qiVcvCL
bILkEbeSiUB5IoYfy83ioC8Are1zq7wVNJHj5qBKmysFumqUNPokVEPqnVMq8tEN
G6rDT9px4gOxXqjCt/f32LyTor3ERlLtRt4rS1Tl3Mokk5DMmMLdRgf6GcpI7LWq
ijEIqrQESf6em4TnBnQRtbmDTfya9DhHIDzDI+KE3QDRQw6b1Ar089z+zL8u0x80
JRdDZWG2lJIvHNgljTiDuw9ntPxV+2Nh6eqA1Ac//BncQOlIKdfYv18pt8q9RBdD
+6M8ZVUB7AD7Y8czAeXm0VBoe6WVf/xNtKyVupjJbYfInb59szuvuOxhEPDqRbYM
kbIopglflB2bVoAyXbPRExNH8t87/QALF3bPjworg1ta6qbUHF2/4Ng7+yJK6dIp
WIHA21npz49REReNFK1kEn8ozwI+j9XWNmgDBPV6G9tkR2EIn8QTUfLuTWnbfHDe
FhQd1RkKWOaPwoRYeFJBIUKGjDT9YDFCKf7y2m7wgNCKjmoIi5nsEesKPIUeTVi1
en5Vm2mivkIJ3BtdppWsMmYiZxInRDEzux3wbsS6gEJqOZmDrav2DFjzwT9KYB3e
mIrO65wXpFR25d7ATdSUn9UYv/9Dvsi5wnzfznumvXAo+gVhK5yxG5umMevdNJbS
QQE/ggrKutlgS9gbdXnjsNZHUbkuQeVAgxq9OnUOjo5e+DcX5YIulrBQW9iD3HUK
ikcq3lsbZnczrsYen2QGI4P3
=lBjM
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '5a05f029-3705-4f12-a1bc-f25f31b04539',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Qu4QEfW6aB7PPQt0q/ZUZ34ux6s4MkE3XGUpUGEi3Vz8
QaTMQbCZQ86DezaMj5BFmdRyvfZoVJJR8iYfetrUkKWASVDXZwz+DQe2a/XBqufV
Z7ZNmBXncu1w1VtXgYqdUYRCL8B960q8tzeN5xPAwEwbUFUrfhrMyfS6lV6iCYFk
LryStWuceESLLOZ8Oqzv/Hk0Czf4ugqhl1K/pttrCgMC17ePJHUgL3UZHBCW5/8Y
JLeDYwNNjU4DOyPvJZ22UEDYKa2WhL773hL/jk7zzGZRDQbMn7unYEq7+ibKTNdf
u14HsEptGO9RFDMFWStc56atKhu0X8Caj50MWbtwNXH2D/PELVYe7aP6z9OAWJ0O
pfJQcCjTVyUDVWuI9sK22ILwEjd1w72HcTq9yxHtkzsh9YfYeD2hTHa/biskzVrS
RYBeq0MqZsNFRku7KXDe7sHhZrkOzhiSH0cMuMaeDFTxPEBewwvViSz2QAyLqEG/
8RVC4kmBKTfkRbx8gKioGfqmWPEMSg7Ea6kHxVDSuyfg+CyqdtYBrElCc8HUfJG4
bqc/ozdwbKvoU/pFEUWfHxOCOfaZe+LZJZTrkfWt95tzE+cUtQ1rbuDnMzyYDu9m
na9VEKi7IrPtx1hW3QidFjEt5gv3JdBWH+Mgo03d5UtBfLSjJEkpG/tszdp+8rLS
QwFJxC3YL9t3E8Ocbivh2XWvWB3MBik9VAtuy7jHbczKIlIF63K08RQfAbmG+R7V
DlpuuS0zjFO9+t/MjqIGZ8bZmbs=
=5S89
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '5cbfe401-b49d-467b-add6-f6ffe3d01846',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAmMgAbYP77xpneuWaQicZOnkCHWWA/U0FSi6/rIUL1ojd
gMSB2f1TvC2inzYzoFakE43y0zcq8KXeyn4CVlfsEcKfM6QjAoNiYf/XLIz3hKfl
t16n2lidkAxCjuLKia7BDMMYp5RixN4arJBmJ0A7i7+1urN1IK5eHy1sLYyHGDBL
cbrzx+tNXXn1eTq6KigNNJAe+5wX3SkJ8TxdEJ+6x+ByJt0wkNObJdAwuezp4MAQ
tdSdT9sTE8rDNe5DdC1GZ9rODVEEj6PQ5Mk+MeMEwUaQFY89f4gLZImC6ZjElxFf
MPKfOjN2QCs4kOeOWeF2xFstZKX66GQhER0w6PVs3QzAex16DG4E/v/ettfPn/ZH
yWEoHxeyZHv/8DmdGM+Gr8/pS5MqERlSNvBSO52LehO4kSPDG7kB4rmqBCyv0pDL
CMJlzX1scvbMjRiqCNdC/kWq7H4lsLyZ1JID1dmoBOdBBsI8nDbrwGoTgU5zAAlC
D98UiBVZG6Wrjl7XRaG418v7iXOjgfTPcvwpVe/kq0q7ZqWqPN8u0X99U9Vp5I2Y
4erFs3RjQQKZrTAFnuUzRyzy9mYtzNcpLWFSxj6m+3ru5BypGIXPUpPUKRthuTit
h9l91+Gmqj/EnZ698nruvnlr6gpfLnguOgpaNhZIlnwqo/A/rmjP0TPlpsSI9m7S
QgEDVyPvMoeDpEEJxHfADZ9Ta5Hlyt2yMqwMa9w+ZtksIhVjDQ9dMoRUi08FyKqk
byo6sh+Gl4HisESRm2sjhblO6Q==
=TjYj
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '5fe1e4c4-55d0-4978-a0f8-98aa345b9313',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAkk05HDJgtIBXduj5nEQs8GCcZyM1YHSjoNMmnW0ZYCJd
ugwkj1iUgm4f70hZDythsicquBi61fthERUFuLtbBemG1QXp77+lKMtFPLnsB/Zf
O0ZnmeBnPAX+9EPNyK+Do2K/Yb0N0Kzrn0rsbX9IAmdz6hP5rkbIK2iAoKH4+wBA
MGVf2d7mr8/cbilc0ZjtmEDtXklszzwjn+YhC+gu5MI4vm8ggXymxQOeQahtxNfl
Q1yOtsBU9oRXIoW51CWlSTDGjaDddMnaBm5v/TBwmQ/t3sa+jK0Uthk4u8mH71QS
AIm3f4eFxIuCslJH5eeylTnWvT9vOkL5K3NSomBvhQiEMHrFr6Fp6j32RkSQJy57
zItOfRY4RiItMqOmKprukdS2BMeIkjche9p6flmpMKlYEQULatRoTDqI6fBoenk9
xQvt6ZLapx/MvJbwJCQVZ9GQUjBFKn5XTgGA4PelgWoYmDCPOZuMNc5V3+Ubo4PO
9DbV4Iz/AFK1bQ9UaERRbg+/LRfMkDRxTzowccLB5/X8OnastwkxnuVeySNxkzRe
zWXHyixw4HB7Qv/pu50UmFitO8GVvaB6fVX65r/NsmIAYxZBn8TsF3tR10Muddbw
c46wP0Qdc8laoUz4UNd/S9AyX2oQVJlroEoEp6+xGjlIZpRq4G4U7sRSESQuMp3S
QQGvo6LYYeEf9eB6oM2OaJIf1caeByk0KEqNgDfPf4A+Aqby9S47JxOX6QodvOEd
z0MF5nWYI5Khkt2QtCJVuV2G
=u9uO
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '671cb7ca-8aa0-45bc-a685-4c99a186bc49',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Ip0JzTZt5twWf9wKOV16XT1ki8AAB3Kw94kXkm8LgG/E
O8OZsWBLLfjZm1xdhou3sqSDpFlCcxX+CF3kqCzxkTnvo+oL5VmQ9Fzh8ktqx34K
7ZTRec/b9Wrt9SAhaY8WbJs4KC4T9/KQruenk2F7C007I6m6Yjb/ChKWAvyn3TvC
Jd76crPy034WQ9IDjtD8dYCTVQbJao8mipCG5EBgwxwhmenznJ25C3lhL6kKr33j
FylXK270KuL0qvRzpyBlKmY0Bv4LY+9okuwCCQXENzREvbOm1frCKW4KzEM7T69E
/tq0CJvgJz540tE2i6g3eSv9ILm5KHleWLGE7ITCgE0eJeRMG6F3hzDxp7A0v09F
EjqE5P8e7nb5q+7RLF3B2fpu8qbTxt6vHrn4DteY7ARVMhFW6XjnYHEDAZJhvhz1
Zvn/3KqaIsEu+3T4mWP1Vl6JzkmMd2e7DHWS7LiYAPh5XGIQ70T3eNVayiZ1mI/o
ZgSeL3egaIFOPCLgXXr5LbubTHkZ+MSn7QuKzLeJ8jcqdzPVLcL485+fOQFiX7Wn
vVo8bV2shZ1HucYvl7in14a55uPp04Acd0CUO+VXPgE8YaZt7UbOf+9N05SSaRPl
HoYdKY+y2/5CCSyrKDJGXvQNULqUpx0+rGhiUxSVbkFeLsJMNXIKEJKSHzN65gnS
QQGvYkv+DjoHD46qKJB3OJkPICFYMFHAfDfARq5SkI2AM7SBJyrEjLqzekBNfM6r
zgWFLVm5DoyGhuTqb33Hacfs
=a+is
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '687d95de-e7af-48fc-afba-7eeaa5cc8afe',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAArL1v9X23roOTJdyOg/jZ/U9K3ojBPV028M8wpBYJU5wX
P6OiGYajmLxxdC/DL5DeVVFM6Bh98J9pXmtPk/BlDzyXFVodmlUiJbYexeRG7ftC
YjnB2aBuLTkf2EFfyhveZwBYQdZxJ8M9rtWomKVm+7fSNeLUkGr9ZQaw3BKIb+g5
X7Yuy85qXGSEVNL3cw4Q4XP0E1i52TNnPe5qJMfBsNqqbS+zg8FYF6alWFaRfwLF
Kn3J+Tiznq6lE4SJs5ixIVCJXSU5Rb5s+1VmcIKiY9Fmab7pRdwF8+2s1Y1SoKp1
oA4ebkU9npceC7NSHBSfSUYiQXCsvxMqEHAzXzu8yR2XgI6FkVsDctFsQXnxFdJW
Ql97t2LhiNraexu2rRFQ7eiaS6hJR2Y07AE5L4q4iBJg5EVsoR0mX+5WidOzT+fc
L4WXtSyrt+NzbiDex9IEemOXToTUYj2W8GOvI5vMoggVmB35rKVjen4GSo67rolJ
/a1qNlWMgh9YNw0uLY+EvWcfMCOqf9sKV/xoBSXhMjWLjZkxxtd8m/Ar7r/DBQsu
kpx8/iQY/MFL9eylnD9nHmbOgO37GB+/AjbOIVeyyGtn1C6TBIlTGa2LZ3wz/8eh
oJleuFXDGrjhrzMLxFSbFQhtvW6Asmb+BmDPSp80pYsTfr4Ufq/S5Qdc6oPJzC/S
PgEX3oprlMreProbsfY/TWfCBB3zrbxQ0t9P7QqNWXTUhJru+bvIbpFeMG9aFx0x
430cV+CBIxn2co/oe2TZ
=oW4x
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '6a0820db-ee77-45d0-aff9-37fb63673d98',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAoiFXjXZpflrSBMNgqrmo3BNe06AnDpNbgwDSqKv1EkrJ
xXyG4FYZvJRxpEHoysKxnG3jJFvV+y4V76r5eIxxlxfvnvsP5+sxGqhdqx0B37jS
cxj617bbEuydcYX70AMUsiJJbhvFvn8hNPPqMF0j1khSzX/8HAM+ks9ldQ/XXBBB
VeyiAgscdeHkzFI9W2qGOtvgW3QTlxQlGQbkoZzp3OyM241RL2YD6s8PxOxOcxvQ
zGz485QlNVIflme6N2Hbm87EDaBgh+f0WqzD0+IEO+UOVy6JGGB9Fl1QlMma+Elf
AtxmRu1p1KfFDQ+LD8oRidl9oGVRVo26lTlHPvjQuIL5xwZ7K1T7jEj99X6s+CzU
710nLeB/QtJI/vRATgDv131HjnxUCzSa4/4f2roYlh+wdlffBktBVksA0PceEr8K
4aBtztBtJ7Pt0RBRPa9mQtVsXCTYmHyYFKbjJqcGsJKHQJ/AO9l7rgGe6ndFlP82
jsl4wvy2+8ht3jGWtCfh7x06+XD7EBrwAOkQOSfxNfdoAqqqsY1cja06t1HlQuW3
KrjjQpIhFPUJj1byOUuiZAnzxc3Jt6t3Dqrb8F0Sg1m+VBz/scUW5l5QRrM/y8Hv
XXC4JOQONzh/kQjGFYtRJOYn/pUcYwANcvxS732BoeNkwnXc8v80HHDyBj5YLDvS
QAHRIL2xs3c/rjvwKIEkB34G0q+5N/mSXUr9l8zNu18IzUSJN6zB23kIZ7r1uh9+
yTLGHvpw2F7B2UcB7epCbfA=
=XF28
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '6aaf92fd-6bd3-4120-a244-3bad947795fb',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//YXORrY6/8P6BbKooptqrMX1OP4f13VHonnBjJudHF/PI
gLmojY7Z+mQqGjKLuDH2IzGt+KlYZG6smM+6DdvVNGFGpvesCfjieipPeqz2NtgM
bz36iTm0L+i69UPXb0Q2rmfuWGiNOerLiiQ/hWA6HQDNjhxNN6YJdYYUUbbklzK9
JGMbScnRmKJvvVm5r5d9UakXKcgXCxvcOZAS0kIA3hEWMqXCJjZf0zLdTRJdUjAQ
9SxgPlOmB2BW6YF9Rh8LyAEx7d4J6Hq/SmY0NsQhY7L8H5cBpu82lTlFwSCtYLqd
uVeTRAf5IfgyzwKcEwFb91LGpreNm9eLOByINiAXvA6rTpAguE48Yrru8CXIH3Ha
3R2j8GbcZqnJ3hXyg8/DTtfKhvm0GhvM5m7bBEcizVWq/vNCBlI4VCra3I5CzFDr
PWFsl5OMft266R+rCO0k8w8f+Dm1/JSmlMzc8esSBfVJv0RpnmI4YwMMwhuucUZS
BDezgY7Yz0342K13bYP/PrhFDoMk1xR8xFOmbNYpY1eXPLxErp/fxo/I4zbjLY5W
0+kJ9Tqcdeh0H9Si3/dzzZCdt810hU4P2RFajLJxaCPbpExcqrlmHaPtncgcuJed
igmR+KW41/vNEgruE86Mfbg96roUCc8+Fp3cV0/Pl0jEyqnGgvHsOEwlNupqYV3S
PgFFOPtSBW6gpzywCZQMc7s+HFonxiWeaDYEDmr8RHuj4SYk7m9PuaAdzzqKe5Q3
gE+8Wx7X+fbXj3OCVNgX
=duSM
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '6b0f3d89-03a6-4c93-aacf-a7a03cb97026',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//V34E6/VinCnNoAix0hNpWqyAqyD38VgztcOwjqaFbZJc
ybMk6h8dE1Jgvj5v6u6SQnobRSvB6E5w/RTd8/F5EKzqOg3jnoQahxJXetjNY3tB
eCy4aLOWuBpH7pxHwsdzGgC12Z4/RWiYQeIzQGvux5s1YLcZCINcvTYuC1g6UnBD
JTIqGcjrv5KfSaTsLlAkz9GIZA5gebG5/7wgiqp/R82djgtktcmsqhkWIsYLOuW1
gMqiLdCLYQ0UbAy7ulgv0EG6Xs8y+pbBjkx3YniMAmdCpKfn4V/w4mr03v1frIez
oQ2+px1rnJRAHWNzsrG8juWATWOWzDv/8NbNw+u1MjaUFmajWGfixV2wMwm3XavD
An8MQ1BKk8iSh5qwfm4kDVLmiF5igsRYQMShpyawstAg/F0RYydkjGd+xQ+7uFXB
qUi02B5kM2Or1k/C7LgaXeKCHq5zFZjMH3FL9vagivpJFnPNLNrIQlPO9Nexrgyu
iAPw9KBu+mBthL9SWJ0Tg+GwHeJo+CkMi/B1yzyPk8VEsRD64GJ9heAGBWEEzm+8
y9zWc160XBuom5dgbrLjfkVMJGSqF5Ejwcsdin/PysP6B+Aw6YTXhdsrn4+Awv4q
BbbWZJXHaaicZu2u89jO2oE/wIeeKGVMfG21bKhEkMFofY3ZgL3iV1estNEGAY3S
UgG2dFML6syEvgCH0I13HV8B/4wtvik7F7aTW4QnsA+E599kTzhX0YiuPR0ZzJFd
SocDFS7vWgOEfak7yx2jBEe1b43A4A4kAu/LdSwoExyZKzE=
=nyCa
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '6b44b9bc-5b8e-48b1-a3a1-954499e27caa',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/7BG+ouI9IJRoD6enzLJcv8X5Tjl+H0cBy64Jon6MsBt0T
3/EWpWMoSxqQfQSqg6sAx1PoZshPNlv5sVMbWhVwnQpaYCIYnDAZh6U2dSeGy5t0
WYTpvFndvdSdx8zChdilD9JkZPvufTzAwc0OZotdph6d6Q7a79+nQ50w8+Bbi1dU
iwr5m2dZJZkKLumUFuQ1mn7yaqerRoxHpRMct+RUgjaBYIXj6RM6rxIvYgCmVXk7
jRu9vcYkUZxjMHE1iYricdJBPmqXYbxOw2t8+hwWrsn/8HNO5zVSXMs1rxUFzewM
c8Z3y4oY6eOyKfeT2JsaI56ikds35yoRQNrYRWv99cvY23ypnKudVH9zHnO+Ee01
3Ki8jyQwCCxWlzmLFG48wxHVYvU5UBbxw7T6rYNTxNTjLqeUTEVjzqv62c+EdKyc
26vcgoernzdbtx4LXQxN+9qMA3mceQSzVRAlyi1d1ajqaQCA02N0Rzak5CBEHJHN
kEPGHVBE+azevRu6NNUut0g96x1OF//0K2EL66Fzzr3/q3xXoWoV4B4JgGiJKk99
y39XcpJfEu3KOhPuHLTjyaaGxID5Z3Pwcb2gp6TCP3bTUKHWExsI/wCkGYNxXVSD
keywEX5DHiDXHZwhheugykCBjFvvGMDHehr90ZnhX+yAKIAWa5FdLv5evVn4kWLS
RQFkSXmkQLfx46jTlHSwWjCmxu124dfuka0UdArpzScdn8LB+2WtzW9Ox73s8vZo
6q2KNHJUtSmCK/nj4qIoqS8UMgrdDw==
=aGEu
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '6c199b1e-e224-4b15-a97c-843abadc20ac',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+MPHlc3rgrnE2rnygE9+jMLE4JLEr2MIrbjASQgji5k5m
ASBorCz7bmXIouwS1A4XIPYoG37EV72Piy8V4cM/nrNKt8n78z27MMO38UCGuGRW
eDrzSpBJog5LXfx1IGdHW2+AsbKmcIyf2hIKXMIWT1KC8KCnvtCK/rTIwBxSa0HM
I4uy5MQN531th5dqEcAbMDQFVaEQiTniA5ewmqjHepL+qb/w/sAe1QAxa2FAUv4m
dVc/74vAwuwCmu4pAZg1H4/i9K2ADZDVPeu8+EwGQlPoJEY55OC+38FciDDFDLxe
W82zzgzhp4hlLaVnq7f7aFW7o0NSX+y/JVYgrePrA30dLsg0M96JDGU+fapxUwKT
kMEg2TZ8vsBks087b25yGFwsB+8Ckp6OKEEIwgf+/5+UScyvQR4JBKsJgUKGAGgJ
gRUbDECbw5tl7a8PvTH4bTdXrfsErxGcrjk4iZ9tzrDWIjYrrhCUPvot/DT+SzD5
RKIyHgNEx1JwfrqpMAwGCOy4uInwV7XttvyTTZhIEvyaRSLG5xuUXHPPq0GIxBec
ly/TL20qX4MCx7im8inBfHl1iuF/5U6+hLyeUG5wK8PLinBEP14LZmf/WJIIuzwm
RMPKbwL9WlAKqqhCIEcQ0ORkICgRIrz687q7zEB//7D3PFgluRHwK3EwlGEEi0PS
SQEp3svZhhFtTYvyg36gh1mvj3YpREVVtpZNZeNMNSEI0AJRvqtVvDIjQYjYGsGl
qiVqQDj+VR0wArBBQ6R1sv4G+PQ1GIeogUc=
=A9Ui
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '6c1f6706-eb36-432a-a2ff-f04da6fe568c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//Z17lTpxVR7SxE+LJ1xP48budboB1aQhTy5LaiIqh7VTT
o1vlvNnYkOsb+YTL4Coe3Ug4XDbZ/JE5SlSn7PZYHlL3eWuCnFOmN7YvyYJFVOcK
NKCY4EpLhQ1jnOkYuVhzMNTyX+aMx5PArp38R046XXZTetQahfKd81S1Spz5S7ua
okmjHkH02q2euw2UaQiPqpZqmOEwLLmE6w1JCWtVSVTRawFmSwSQxzKNzfH8jBO0
XapgbWkgIBbVafaKmkYMYuRasp/TMnm5AL7o58vJxtluCW6TITjEh06RXZ0PnjMO
cGQMpg/FbpTsGUwDGr9mnd4mfS5yZq81dZHJXkZA0qK/x5q3s9dohOZNmfqWNNmh
JltDqaROKPNIPvHTPCl9xncpxwIqpE/XUujXUhnq0So7xDLKr6rErZBUv2+ekWwT
I8nC4o2I1bWOwOBxQXnKeMRfOoP0gCph7nbBy7iti5BM5En1yP/ZZEwH6WyEIoHs
XIWb8qlKysTr/gLO1sbvU806K/oqwJ++Msd2i6EbRBLjMqsoXjozmmxvNlKiuzT5
VeqdWlRSqrR+hR8NhATSHrdGu2EHbL8xhDgF1GN2wQcT5Zcc2immq7i0uXlW/ZdP
IYNgAIe4by4d09XqJHJ/GaKIS/BcC2Izpe34pH3GeBtTzHGHvaLTZcMSFx6a7WrS
QQFALLfEdJ+eTPjruGNGojluD/kFPwncXho2YGqRKdQNFBqsf7r6Dg4aNKraQBgJ
vVWgHh+E4Dwi+Wr43hM1D0Te
=fC3l
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '6d067e69-9fb3-4749-a003-97082f5664aa',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//VzcAvz3V1A39ahVXmUwHga8C7wsdUpzJqVdHRVUNWF3M
SN8S3NexG7Z1/BrDKGvuEdRL0efeR1nRuy3B15O28UWKvkj7dPO2VBWfv5ciRmP0
Ry1TgLa50hFjlJ+RizdAVqz30nm+J9twp9hQfeSskS2hxn+qDj1zchkZzeQvteBa
aYymrLXWCS/GigKcQEUum1bXUKZdcGckNoD+GOZtYwZE0uKG4bMY4cLg4FLSGlQJ
c6TAN7EDiJbVpJoyxgA5zF8pBXbJozDG7VKIE7FpJmFxw/QGQRgA7Im1wmQMcx6D
fEsVLeJrc3HM5udcW4QY23leUqZ+4oeHhnsETBas3O1PMYocMhlOjb4b60RlXjpH
9GG9ZEV8VnRu3Pq74MHqd4QzW7pyhCw7BIDIg18CdeQljF9ZoLlvMoGGmIEoNBFI
qyceqv90ooE2xGiDKwALmkpz65L8sdeQ3zzG6YRuvhExwf1s3SNeYF5JK677qD4q
y3By8quimVzKOO/b4b/CIQISFwjBID2SD5nbLFQgfVsjDLYi8ygSvVnc+YlRgRGF
mrScEiE2NXaehPvdl90jQ/DbExAkgVK9Kc89xnUqfac5Lv12EJYcjSd3yk39Y9fr
+3ylyV9H3dNUQ7tRGqGeB7pqOKdyXABimLWRfwzP//+nUG+2fR2Ut+Re0fDMtLDS
QAGd7a11PLjSJeXf2ZdACz9j4L211TXsTbsPByb86Oq4iXhNjOC8fTWT8KV40MHv
q946FDSfyHPqQa11Xy4cWRU=
=69og
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '6dd4e999-1e84-4497-a9d6-4f58f78b2c5c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//bsg5A78pMK+kfiXk25C61piuGBSjF02gRDHL8tIIHZ0/
lBtX7YmNrZHMSOFKY5Q/penFcG+v9KA0zRNCqlgZ2FgtAIo6kA6dQMfugue/6Urf
NTtyDQF3rMWwqpQMvaRlcI8uu7QF6SbWf5Gd4aTCxmTN56yx/bQPi+cnjo/Djhpf
VvjzUtQ7W4LD4a9QzrNbHzIamzH/xLCoG87+4Lc/OQYgjzpFN7Hl+X/wodDltBIv
kA2il7h+CsC43coh1PdIDBADI61JarQC1exFsdF/8EOzmNij3Jh6ixEHToWkVWVg
NAQZ/F1aZv4C37xkUMg0sAAFUOgSSivwdbX9iyBtEPvl498Tsq2ARvFimEiLlDS9
y4PgFI8fWQM678iLbcy48Vurogr1O9mLrJnE5FSZgIV/3sm9FUxxpjTMWXiKbPgB
/xkMCSkQM4tWMuxF8Ezo5KLUTPcxq5lCwmg6VmHEkfXPMZoeldpVYrY8v2wTtbij
yFLy2ab7gG/GG5ai2oTXRh3Lq5AkRCZNeie+XQ8JrxYhUNhmm2CTxHnnuVC/jWom
293sszlpekHdZBR8bVy27YwSIAxQ9geZu9tVPwa3uuO++NZMIohxBZiAanhHmq0q
R2z1sx5L/TmDhGOPyAPz9LKdSIzCmVqR+oT8/bAg7oBTVmqshyccgavOWfiw9+PS
QwHEimzIobaBIa2a2amJDseQyrGKcqEmWfDUt81mysMsWU04LMuD3kkR2JltEnJj
Y1FjDZKIUBeovjWchmr5nmF97mI=
=oPSt
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '749e8e60-f3c0-468c-a887-6f24a538ebc3',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//X5/rx+30r0TRwybq4XUdrHPOx8ihcm4nbFNhRxl9hZ+M
G2b9IZhITwesqmxFRg9kBMYJI71Wxs+AqFsTkN2YQzF8VcxiM0alo7cPEziwmPSE
Qa6hty6XfnDd6wwtm5Zx1lRpfNrIVIp0ha2+eq8T8fBI7Ye9hqE+SSupIcfbQUTc
ZnZ643b5Cw/aKOhZNDDgY/JAJcSHdI5bPWirsfZ0pMvXmYpIUivbJiaESlG4U20U
9xAktlBc7ZHP5tkRClPK1uvVHTuMK1WmjVJ44SQgTj7SEsERCq70LzgxIvvDzUga
9XStGEsNKrsdMq2pcaZUOUCBDwj8kJhT4zijaLiHy3EGd9W+B69j4xH1R+nTRr/m
k6la8sjCkktFauDlMQbPryoKAA3ZsA+WbRPPJyDN2pORvDDtE/Zzan26fz2ewxJW
JJd6KKkZ3AhvonMFXPof57Mqgyz3jGJfZajK/eeVQ8JJuyG2JTIgJuNI7B4XSS+N
W51pi+9gnFZ/QWpix+nR+8MV3LCM/0YzokNgffiCf2IzrULxJFLqICvJKIyDPKXt
oNIDmJFwEqQqfxGNAFNZS+CUC5is7ziy1dcTz/CVitV8QnxmQc28Qws3O5kGbftN
WyjLM8owJUj3vAn+CY1i+uHgNN8AP2Sg1QEeWX3hUio5gJmXNku+m4IH+Gn4L3LS
UgHx8PtlXNyQ+cHw5PpYsR7QgDCEG8MaWn50ZaCQCecs72fKhNLps7oMxNr8m5zz
7azEsjBikMWpWwZHpDfZ1c7EWFTCKrJW5ecYDlLjAW9HmvA=
=gMhR
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '759fefa2-2cbf-49ea-ac84-b14419b6d549',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+PRMzq8QUqXKnukmeTGiZkXbz8MuIKTbSO2FhWbNiU1Bg
KO+piYunELdLc9q64H3YKcWom3ETrfUdgmttes/eKIBoaeNEwPbHmzELPQw7zEZV
QsknuzaxIHUSlLgLQZVAHhrgRYPItrD+61C+zPrm/v1XMvMmeXyRFmijCVTn6CxN
/lL8sLbx+yJ6wNTwLeCEKw8Gs8FS8WlrsahnIgtPOSq5R47dKAAoHFUZo2WN8el+
yls02U37o2A1QXDy4VfZCE4KDBWWaiZ5IZZYHOUBnuwnBXlCH+T4lHlf5EmWRlUw
C6kyDHIeHkY6wCLbLoksggUWBPrh8gsDbpBKSZXw1yBQvz2BbrNywevIoU6Y8qN9
RE5/LjXxLc+jdLccVZv93ozqEkTCR3kxbDjCOSzFNTty64/5TJaOyTSkPRa41AS8
ID/QyPHI5qQO1v5B2SzLe/L2+/vC0YF9Vxbbjnbjh55I+CcMqDmhjAy/KOyEncJm
vFM4t//hviLfrEZHonoSPdvZr/0ONiTuSWWnzYYA9bq1wH4DNEgRQclZkk3nXUPv
8ujnWo0xysdRUqTcFeta7zbi0UqyGNYNMeU9qBOINni47Gersw9XY7D45IMQrnVf
/ojzOdNzsmq2BvW0bnpPxXTey7KHXGyP4UwlEnK/hnSXXbmuEpIuMqzSVX4n8sHS
QwED+4gWFKBpQslfUCwBTyPlPkOHqlY+RBCak5L3IbhPIVRv5TZAV3aCwAMhxoct
qKysVTpwbzWXKs0MbEBWZg2EWCA=
=zkyG
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '76ce71f1-08e9-44b8-a3a4-8ec75d4061ff',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9GRociPYr0G6O8fz6FQ2RGRvH4gGNSOzNgnUza/Qr/YVz
X6miWWI7cvyqnqU57y4Kjdbaun0TKzPpzAkbzzuYmj0IPVzQVMZ9mNNiK6mqZR2j
RUX6wtQOZEHTKNzDAit+BGWvXITfYQQO1GNKwRIwaXVBCMK5QGxmgM0M7yMZCmcJ
aCIm4hs5pXsz+6v2sHMBHAcAZ8L8X5JCSvtaXpd8pJ4JDtCEAE0QxbWcsEeFO7Kd
dU1WYsaAaFZcqurriV/gW0QDprSdnOVhbL78BrN7AwVDULL/qnIQpSmY//3B7DSw
BQZJHe1HpWzSwW7pdr9ajI6rLZespQl44r5OW/XqEddM3h1ovSCOrpsUovQ0oae4
OHX2o7GhWt3ItgNURyORwTI5+mIsI5QA2kED+u0D2WrzQsy5dDxUbngvLRvnA4W5
WcKJ07Ws02wZ1JT2zBfgC5h+onowqKrRkt0lBpt+O2kOl/XgVW0RlPBhMWoKsI+F
4KOE8zIlzDcMIreEbjwG6e8wkPyfl1PBdWWlkaLuPltBKwBls3qZ9S566FY0sPwL
mR994B0C8JPOOGcxx//C+2obUnVa99f99SZOxviZWltRt2EFw8My3LSDkblAFL7+
gfAQi9aX+9Xrzhicdq4IbNjZ1l/dLXdteRvxXIZMiC65aYyUEvafE3U+GJN+QgXS
QQEDcrlnyQazGjgXu5JdJbFbP6Gpi3GbuSPRINgNFEKqi5CxZ3M+p27n1vwI72VE
ed8HBZPbBCzx7gnSWDa/y8ia
=BOpP
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '77b3fa1d-88f9-4d67-aa62-1261104516a3',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//QH0OzceXKF3P3yVkswJ+KyqQOCPbr+7ewhtMqGHrLxPi
gBbHxxVO9iCYRHpDl6P230PZx9UYQuEv/D7PdPGcecsNHN2Ife6brSIomdvkbw2U
Zf9ncPD3n7Ia0BMSRZgsSWPMWPpk6KAgMrwwLH+6m1ao1Xy/PcASWmdCyNlp4pQt
S/5o7D0QGMCWFMolHqRu2od6kkMISan68i8mNigIW9oYyR/vuy+51gXIZHY3xGDn
ocjolBKuTV3N7Y1tmJORK4nvGhz+KaU0OkMwrSQgEINBxGsASQdDle91aprl5Wji
mLjDWtjqN17Xz41NrHstKUJCvTlp4CmFF+eOHVPGWMv0gbmMKEz08QDZ2RXcHKt/
9aVrsAAFl5Thvi6ChH8Cssz6lGzyXT1yMePNCmaLRwKlL6gOSoDZiYYyvAwd+A0g
D2pLWsIV0lmIChcTuQ4pBiym7Eg1vuTnOXwTpkcbFoV/+ohzPPxSW1eSewLj/CHQ
eETbuSn4EbXmlYtT+0FwGJuMDvwtc5hd8pgFaYWojOVEdqBNq3LLuZP5vE+L9nHx
sFY+sbjaPNpdIU/It722AZcOGkAV/lBQkrj2WfLJSbOlGHWuhWVbyVZQietZvjVK
UrtbI5JZoxs0qLdnLivLOsV2HClZZZqMUYleHPSVo0C4+X3eJmeXFTZ6YqDmWBTS
QgEM97FSlP0hZoHtrNIEcOH+LcGwrCopyGPgOzIrSPhSs8T8aH3jcL19C9lK7lRk
gWIeBBntBdhBUM9R7By/twYQhw==
=JS8A
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '79a86772-07ef-462f-a34f-36688340783f',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+JQm4118MT8lXCFMhbIxYYvE/jYP2JSObarN0FpWY3Ige
eNH+lkFxjlFN8BNglX1KoqPU7ZztEJ12l5fVI6Mt7tvnFeRaTdabIBVB26GmqIS4
xbEQerg0vDkyzUNL7EMdDhAowL0AR6mGqlUeUVjujpR125ktx7JAQ0t5H5tT0kpS
IdPbj1KXwclrfSJv7Nz8YoEe/W5WmXD1558fgvaS/J2QXFyBR3Zu4t/ttsPr6cX/
vvr5/apaej+hzSTHs5OHrD3fNcXlZlC4Sqtequ56aJDGestFHqkG1n7TpTFwGqYA
leyy67UgmWuzPFKLup7bxtpwIDNS5LZMv4wY0nPm9l2Jh/N6OGI6XgQDRzswWxX/
AP+d1q+zSKqfIoRSzpjeR4XMfEWzvJiMVHbXhW6orJHNWf+SVPVWeuKgzQhOymOR
kEdCRprIlxXjz4AJKbg4dYlotwOhvnNE3sqe/5SH4pJ+UVOkWA//wlq1CHHG3jo7
9DRl3uHPKKQHa47whH9mUA1QzcwVqlurlP0pv+zNUmRHqSp3zfKvnBrRDGfluumO
F5hX/HFGDKTrz0Xw44A5y4AStplcNA9Ow3Myp5q4wdaBGHmISVgF+ZAOUXFaPRzh
hbTy1Yw07m7eGz3Se9G8RPQT0YEh/NUIDQpOACz41qJHec5zolF8EPJ0jkSuRBvS
RQEfvxKXyDSC8gsDVkfyMI1Yg888GQODUMLyourYLOSbLchn2ot5LM0gA6U1Wtf0
FSRfY6j4S7aiO8tHtj2LIKFTN5ov+g==
=xl09
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '79af8d95-8d84-40a1-a859-3817bfd86aed',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/6AyQ7plBpoz1jYEhAib2QZv4ExWmX305DlVRQDSq9Vw4f
jjFZ9JQCXwjx5ajvtCBeABr67IN8/qG75WCGh31EtzCQtSXnpnjB56Y0gKjjGRZd
ce6GTajawBe6sVHhjZ/rL2nRG5tIS73Zk0+zvHHZg3h5Huh75shJ7r+ZZX4wg0dv
zGb9csmesW8xt4jgXMse2jZlRrD7dAD9e7FTyIgC4JXfPmme54gvAMyGW1MO9uLY
rHsRQD7zExBhQDy6giFx0NIe5wU8G7e6hgHPc6+0hP/ke66c0YLq2yRge2aloxlE
/FT23G0FFqcwLxyYyVAn4/xeIvDyWVfSTZVCdwxg0nSaE7eZqsI4ftPvmFbCYHMW
R1vRaTeNLRtn3v5bb6mbeWudhpwmCdtW2bPh9qZpsIX+iBTpVZzfsk45e/bvyhmO
vAYyVi3Qchx4g/QmyB1t0AU/uxDfvZAIPZ8YR1D9NSsANw180fyQWIom0N9tLBMZ
1MMrtfzcqwH4cgiJ8QhgCG6ID7/PkS4iBpPFyBZQ7GY1lvp2TjtUg1GVkNZIQEO8
5cDwRuDmD3SB943r3CapUROBMWevTYrBkLTA5OGczmb7ICOb+N21S4VLL5pQpqFi
sRGN7XosXQpuHIkIjMJDi5ce8Jak5pYJ6xBzOA4jcc00qohbj7xHV2Dt5EUSD2LS
QwFzb+64ZuV2T7kEUb29LY8Df0od9dExp8DWW2zOPjPpgYCF/aSF8mKx450hoz2D
DdBLcCUSFEehhARldhXVy7T5AMM=
=XIIv
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '79dbf92f-5655-4471-af1d-d5b80623e695',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//d2qqp67zdRToWar/2TFzdec3w95+SGrd7DNHJiPLnDbY
pfDK0BCxSmxJCYBqBk5AedqhhUUJTPxxgOu4sCBJJS+S4L/32W1xFYACkDwc3d6v
Llnh2dbj1UNf16zBg0GFxaBnW61k0azrEiusv3OrZ+FJVcU64yeI5ZpxS+BT+tfm
6Id5x1hjv7iv+ja+nE0eGwFtgrUyOr+9vjG6CyDZNbngLsyN1ETImZxa8Dvy6WPZ
enlrPoraBNCWmBONGNDUYOMFpCR9TlGPFAbLYthYED61iY6LZpmG9TaIW9Fo/49d
VK9eXsj+M8BpD3ntQLQP6JwtSBdH0kAAUiaVThJUOSNP1ZOauMpYj6xC1Aeud+8V
tzbNnvqA08QQoju5z+d3O3c0+pbppxuIidYcOk5A59CsrrH6lrxLAYjTRZXaqiSZ
2JWXLeKgw0trRAHteoG9zk/TgP9hMz26uBV2PusodRADLBSgpdvfNCVzhCaxVX8g
DjZy9QBUxLB2I7LW6CpIYsOII1HrSsyYOkfL2JYPMJUl42f8mKlnVgGMiCQ3iLIJ
MEJyZ5sgUa2xVzNGXxLJD37OnmS3QvrnhLGE0omZGAPNMYKwCGDlLYXrlWMMe5Du
Qyq2EDsf4pSEjemAr1Zc02bQhlQU4GNQsnTFAIBqOsb0PT5jqB67hDRSA1u1edDS
QQFRA8ff4id41olUHRxJ4FAEKCFdhCsTbK5mNgZiG+JLJQOpTOM/uOEU+veS5AsO
6OiykQ+kCCOKuC2z+9uQ2Nc3
=YVzT
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '79f6a755-6f14-4ced-a665-290c341a60c2',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAoepHmyL01+yOiKb3Te5UzlK4bxN8UfDWiBPbFQOwGrf+
ZIE77PcPtzSAUGDB287QmFi8UvCcppzdGJ1HtLQkMdnv0MU2iBPKpV/iQdIuciH0
t0XlLrl8lDo5zEl7j0Ws647VgSL2N1fQHjwlxBdHuKflifGIrmWSP5VR0Xwvyx3V
LqWATVUvtqH8vDX9SRcZMtqdNQNku8BoCXu2qmAwv24R21mg9ohc+qMCPh6cBUcL
EPfrlJS1h5hXVAhXqp+tPNwtWSE3XUxEovZXi1iwflc7oOqZAKMey4rlb/JQaoLP
+4fNAtP14LKUP7DJ4xo0RxYEiGTUYLgPBiEN0dNSnwRbgI8e8bBjVr5MbYWYkvTZ
JYsemMunPVGXzOQrt+zsxlT2DHF45QBdVCGsbRe8ZnG/gfQ/xmcfwgeCvw1hM6xT
KWNnbv9Qs/7fSyxURkdieLdV3JDje34jDCkgbPZbuGyARpAgB/rktUMbQnEuQEk3
FVIVvQJMlyeHObEDl61pYEwUk4TnaHu4ncW/she9XUkfN01hyV+nJOzrv4u0vA/3
cAx/10jHXEen8WDOK2L5mxcmtoUDWU+2UNsr7H6SaV3YpZqya84jO4rlj9Ra+mmu
YxnNSqAmBWvwNSZKRtMyMOgyfKMid495D9RC8IWKNga9Tzg7JkacT23W7ZHXQE3S
QwHHMVvm0VzwWacpZ/RtcCngqBgMtjWz8YjB6oQa1uh7ORFarWJsOTxY2DTfO1eP
FVoBQv2hD43D+8WJM1MYeC1q6EQ=
=ApIx
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '7acc8f47-6258-4e33-a909-3bda1adb2111',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//T8Lcj3iN65iu6C8L/7acXh9ObQ8VRhhNNOoB+midSIpw
gyS94q+yAFHAXZDf8BnYWqaSikD2jKvK9GYlSMLg7uNQGInBOLtYk5EkpHF0NpV7
t3aMFDNDDjvlE0fOi7hk4vDL34h8H2BvVza5u5aP5A2ygCpO67vMLZghlF1H2B3U
lfMLX//xJ/a0a9lxg+ONpsK0QTtpS1HvL1gVBL/sTqpkHHdQU+6DtHqnMXerOn90
Phox/2gnkDgW9FlQZ+XTT9ftJqo9zOjCzTULB/1GBxNqRLhh/uFw2CwPVnVu61F/
Idk+KnzW8jK8uqnMTARDOPzLgk13XEEdedWQRMcNTP7LjOJcRpcNObviJdI7YBvs
2SWyHdA6LKBA57djskelEPUypzdM14Op+ZpIXrvoDBuDup8Aiekc2F4o74X/8pGS
MKM7lKBtfYQLHtSOPy7JQctjuN0w7pGvLxNphuaijbIYRRPlZ/HDf0vqJyc4zbpk
MiBh0MRV2WjodjLm55dtaDhsA70tUv+O9oQx4qiEoYGmhl8u6jbIKX5spHAtjDb+
tUsdOGkbRMX+N/xfwfs+cd5sE/VrRk7BpH1Z/JHgUnd7hBQ+ERP4yJyUIm7tuu1O
fwiMPvuD+SjN7VILr7XttICkIVpFrCqvfckNMhQbJDmIx/AbEdcUPjR+SO0XtUHS
TQE6t44HZfgeesKQj1InzG8gLsluR6iXsWEvsRuu2DbrEpwrMmmhpRLgalfQH9fl
VpiEv8XMUFpLdNoom3Or+03VQER5QANeHflPxxZ/
=Mso8
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '7ca145eb-2045-4ad0-a82a-4c0b78368299',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAhAJAOyN0QBysRSNogItP47k6gQrSifXxofhkywWQDd2t
C3AdO2nX3mr4KXwTpHrnkJ6Kjx9e6lqNtQP0wBaUrfX2qVI/gBlUlFo3RYAbDg3c
TXIBYCVFLD2HjR2BjphAggJ7ZB+WQblzxH9w86FZ7HWGTI/acwlx5W/upPHGP3vH
OxTgZDdQeTrZl+9schRv8FKfVrazf/COntF/V93rhL7JmQCnBFkZn6ooqyf2c1OR
Dp7T6zwVt+LieCGtys+ZH093qRaVC4GiDJwazfgUWjvPLi/ThWvXHcPzq5FyKq4m
VZtW+JVS00Zg/qom6zN2KOhkaa0vEOEC4thPCt7P9nu0oLyl4a54fTdfL2kanDFO
HC6x9IRsU4MFC/DeoWOBadThdAt8ycxO3R3unqPnH3aYEMWUZ+w0Ao5fsGMUL6W+
WHaV0hnH4NUBNmiA9DZCYJb/dXIVX644+1c6U3bFbY1/fOGyPMa+dztzDWyaSxse
RVYauU7U02OBD0CC9U/3wd89tYHyOAqnN9yvsvNFRnH7Al9zmCrqBL+FIkG6goIg
YSTH3lrvgklcyAC61cE5vAEWy2u0StttA1JMmLekJKol27NOPGIA4fyBi9dqTKLN
yb/9rG42rm9B5Ugxxwe6TlekfmnxCNqz/rYVtOUIe7q/SeK71dpiryHCMBb7TkbS
QQGvwyU2O0Hr1Uw8blngSqpNuVu6NnnvcRGiy9NAzYiCjFhDQ57HeRN/voAwo53C
zwgBYk5CUDNWkGwh+09K5+KL
=4K4c
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '7d2d9693-0b24-409b-ab05-5999111ebc12',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9E/7s25iIHQkaXkNzdOEpoq1T70EFVRbpwJqHPW3JRW1K
H/Rldp/kXX6dUwuwrgtK7/zRpsEm+4Vs/O5KeXQs6hnQx7bbker2IZHwdar9oHkP
HYg64jGvguat1ODq96eLd5A7HaREbtuwVJkd/3BN2EGR6sEc/KeketASA2bfv8h7
8fI330OO6GRNrIRUkSSntUPH2Ym3O51fJaJI7ywrD7Myj2smyon+xri1vTT2clBe
FkQyyK9aQ2n241SIkiT7GTKmhRAxaPR1I+owH6K78ZQAr58h5CkfDhDGEhf77t27
ShCtmYpyxEvAwcpTsasUJtDLg3orSY69s0+NfmiCauJfB4H6Z7gfeEk0qO5Fkmtw
9tLUstu4TAwDao08GZ2X05kuBrYv3/juzlAnobNGvS5Q36FPCqfgj4hjVSu8CS2w
0JWhfMHUHp357W6edJ1AVycg+OpphbKIYJNgmyR34Ccer0hCtCabypQPVt7WJ0yF
kqJXmeRo30vQ+CBvs8rvEldQgViXDaelRDmS9kHngPdYOwvohPKgPhl/M9BZmaYo
0o0oLdmGFBFBp4IXtA8ToYbTdX4HEMMd0iGqaxoGpV4jplw2x4DkezSI2+lcL2EO
AgB1/j06vLb4ymfwtaS3PdCB2+GHaftIWq/WJ8QFh6Fh0cEIQuLIy8xdEWy4FUzS
QAEnKg8KOmcJQOSs5ZU9XxBRXGuMjrmfUvsWyj6ZSCZr6IXL/0wtoGMGfs/AGmzg
ckuCa4m4qJBonElv/Hu3p94=
=GCHL
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '7e2d334d-4ae7-4ab3-aecf-e2dee797f70c',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9Fe3yL2n+AxDjyk1dE7Ka71nmzs7VAEpTo4QMccXRno0o
xX/iJ/kfGryNLVDbUHlQMs95F7fgW52aGDL1uRX6IWm/VgGW+s9sF7g2PiRbz3gm
nnoGWvNC4Wr/e/eRQSN+YcvKk4P1FEYqSjEvV67F0JyOwUP2/MzXeYeb/K+xbyGx
1Ddp4RgAxx5oCGGJUK3YjRdq3x0PRrRd36dkZpJSDFuiGZ9Mdyu6lCxaET6VNywt
lz3TtZUKQ7QbmIK7/YTtidBgCsf0BfXRHtJF19d3u69fNKmdRjXiQ995CSLFoF/d
28JWw8crjQ/T2B4631T30lfIAhJANo0Vh/fxD4PWbakyUk1E0d9QKSfK510syWLk
kc6TFc023qKYwlLGzzaA797Df5dmng76XUCEZdNtwwK6GFxCeXLeJkQw70O9mpS+
FcptL8hWc9CXShkQ/hwwv4StpUgbhbkMwfdetxzDcz1YEQ0GzFAppWEvchANiph5
sXUeLLhBF+5LYj1NEVnGl1IU7wqh0Dj8C3Nxlpoes5nMNnBM/2P07GADAKgSMNE4
GEHlvjy8Ug673z9hJjQJy6RP/EVIfMcKqVQXNjP/PoHJ7BnPEVFmQ4aNuYYV7Q/Q
VSms7g8oABCx/K7RcAN91z5WkJGHoRmGF54v3tAZrADIf0ODAMYRSk4NB3Bb423S
QAErCvSlG1JaEdmHtxymSo1GavOZQBqiHOBllZgQWzpcdj3kf6o2aZCMGhAfWQAl
93qQzglgDArey6ErHan/Bbs=
=iwzB
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '7e3cdae4-b10e-4f2e-a337-1802cc410234',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAArzx035edwTu1hRFsRdUhe4qVnpLUAa/INrX7BenPQmXa
GfUF+iD1s/33R1/GcGXdCKSKendXLGPTcaGHFOR8dF8++vHQGiSUsQI6Wnlin7is
cz5pvEyPUHqZ2fgxfWcyhZZ7eIW/+UqOqxP6/QADOIaAh5OGgLtWrju/uHACHQhe
hz8VaN3HlGY7jQS6Z6QsHeAUHMUqO9ZEU/jOuY49RVJKvZYRGHAtPG1zdzUwbTy5
vBJYoTrECL+MpOJwZwPGl3s2qzJ4/muqvPA21I/ilCL6YYWYn86v3DtW3XpUIFSA
tLBSJIkNHZS+NVTeDReW00hAmu33CM5vtV8sTOEBEVR8iy7rQFpainsQcFIMfSk4
+Kxo/3om1utxXN732/8oR6JKVEY5juKvdYXUo3IswiyxrKqqd83VIAmLd9c0Z4yO
N5a4v5kZRxakyE8OtF3FA0mBCkR0c0xuht5seybJSCjUPhjrwfOwnZu2MILDm8+M
Cjy8IZ+I8nM/1WbaC5x9BvUa+cBG8JJr1VpzJ/TuFt2kBOIU3a3hSd2MpQPyvyej
+O65dQYnHCxMEcVi1Ir5iFzTEkUphWrAoLR0Mj3RmLOnmYkHR9xzerYlBvDFHGDF
mShu+dEj4kgomv8dqbVK9vwhg4uNguxDQBKxxyEMX36d0/QLlXP+qU0Ok+jqdinS
PgH6H/V4tlSVzdpx/VN7h2+uJ2rEXjwV3B7kiDDJUMeabkr4mnYx+X3Ja5bHSn5U
TI762JspudcxFzklpJtZ
=RF0Z
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '7ea439a3-8dd1-482b-a255-d15b710db0a2',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAjG0kh89HNopwEfJfu0NusyiW+e5koocSk4QJAGVU3iaI
V1CEopJKgDNUAFvRhMaZob5VjiBG1On1TSuh3r+j8hPBSUFIqJ77NR60DHto2IJH
aY3XkP+gGE53hWM8kBRADABbiHHjcd/R8Ov9P4b5Qy131Vwa1VaCMGbSNZPUCJlQ
LlrANz8ssfdc7/6XU4fT4J4Vb2rGNaRXjCrSs74ymGt1iWp2Ke7VABONko+1H0bl
Xq+tvQKVOkbdW+LUMQtg+9nX2hHM6QmL7MIdvQjRuACfc/o2ZbSWE/ljqJ7rBhB/
3SC270YFEY4a5uhdnzm88Zjk2l7J7lnz4N91Ap3w+tmlHK01x+0uFZ9VQqEnSN4+
ua6g3EROax7fK3HqR+XgD3FQJ9qsbea8I11tHKfyXE6XGUPkxHHxvMJ8dDAn0dgZ
MYpWfkj71M6w4LUJ9d7v2RUl+fzjmqsw5/iAKiQMS50O6AmP9Q2OgK45qNM+QNR4
3QyWNTIe/6KEv5PARwvH8gwWJNn1anIOLeTLN1fF0/wNXQzuBF3sQZauExCa6wf2
bD20hd0/uIyXLREK098H3NurEHY1ckE0dj5NtQUtDtlFqnJJ9ZsD8vLQC+scbgqP
5kP2fwr9as29lWfeFQpxAErpoHr9ib+w+QbP3hcRR1Zr4mTK6xnccn2vEOYG/vPS
QQHBESu9sFIw9cN55lXTra2yoPkCRU/gfa5aTmTt5fVgsDnosUs2PlxmImvzclOq
YsQ+yx4wJhdrVEiTnFdnS5Um
=2ZoQ
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '8222274e-db8f-4962-a219-ebde8830307d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9FeldygT9wSh3YvVO2gpRtzAxse85ILrce37wYjvMN/pq
FJCSN4nSGRPADbaA99xK9ZSo9EHz5LUFcy1SAErFSuHii+UyIaCXVKGKL2EaJTyB
IITQDOvVWhfiq3p19VToEgcxtO5Z2NTMKuONygwK3j9iukx2ZYy4nD1E1zxWIWYr
EZjqG7QIACvG7qgHhLbhjKpzSKbnIWh9AqEy69qQ3u79qOMyaD3ad9JY1++ufeHE
qnIkHaVRWzBMMRoquSdMtTYau73UTxJW49ytildYYR5zN7MPzV+1LKaLJXdNjKR+
I6f8TtK/qdms5l5mUWAnM0ge6JBxiohi2EhtaebTwGjBdJ+B0T4bFgsThJLkOO/U
s9HowohYUa74leXeNJCcIk619oHERmeiHo4RmjtmUhibsVvOXyfmTY35rPy2Vpmt
eWNPZLg6W39stN285BJI2pgrVTQw7YdEYzhOCIo72X+ZFYPbDExCVRNOqBdbNJwX
SQInJt6w6/Vw3g5R4Q8xdykIY5p1hI9PuKOOJzQs3sBpsIAF+mD2c3wNLsASp9PZ
0kbEWCJlHTxzAUpHnTTGcrW21b6Uo0OEiPrTHnRVV6CHrO2UlMnHqC8Bv5BCweOP
3cd6PJ4xrI+RdOgMUPoBe4ytswjK7CtF3ke8OA+bo06NY8Q1Ysu19jdIZZcdcf7S
QQF+3SUKkYQBQqA79HSllW4jMd6IExdBGXV56Ep4BMaDiEfJMrfHctNksFCAv9QU
fHFHoiCh2e1pgIB/GHWm2rSC
=XeWX
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '8271c0a5-11a5-423b-aa66-eb5c323ab4f3',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//dCmLLOnYY574zrRFULYFaNdOp6HoMEcbGchibo+PnaOy
veLd28pkloWSh76C/EfHCxMao4Tat91LFM3o6eEAkCdUvEHKo//0OosamypO9Br+
st40GiGpuTKI3X3nOFr8IpFsW0OTjrI9uXEM99PU8BX22Qtr7gL+2n/Pa1uxL5E2
EKUPONC1bbduf9F22YvxH9djQ9PbNH8ZY1SmhvzOeldVITG01lqPJQ2XvXSjBYZs
VmAvaDClKiWLWL60wlO3IBjqh6WA0d81K2gjFYhnrHb1Pwy+2r1DZr275eASc8f1
wfzUut+3Fc01wKjDTzo2auvlZBrZYoIZrb6jepLhbpMbq26333hPTRZaWyXXtVBz
hoWfTEU1llhunnRz3IVXqnKp2vpVBHH8I9dZLrwln9VzBeIbWYHweZepZrJ6Vmx1
TXlMQ8yDMEYJmZS7HVEfWbz/lXtMe5XNipcW2q7pVferj/1CzrNOumCI0TSmyBrl
dbjhH6HcjvzAkyouloNSL9c2+4mx38kXCD1t3q0cf1YkFi7TjsOe37gDquvq8+1I
53VWkCJwXyI/oKlkFRFsyUnzi+0wpkOpCdcrlHlW8quA1OcY3hovXjunktZd3p2L
A1bu5jE6sWXpYCjKLqrr/rshP8lG9dZR+/H15tu4XgtxTmLCfeTYJV7AVEirjvjS
PwFcJ2hAV9NH/HwM19AwULqURiV8tKmP6avxZq4OHl8kYO692SIhP2rbabl4o/zG
aAuyMczpcfp7UyWDZ945AA==
=zmoE
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '829468ae-073d-4f0b-ac79-9d4c633160e1',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//UtrPDON6+byCthcdZOyo8ruNDiDNKrYjY287co5smgdJ
FBHj/aWC04LmbusoocbM6a+RVAFh/qdpnKw6p4xQ9BfXAYUARjwHHWRq0/+IMTgL
eUum9FlcJUlYUK3Rf2yNjEBOXt7KXOt9WguobukvKJA2oiYG8Ya++josJIGYMDh+
okAtmKdSljRLgHWVyjxkmBycdNdzKlIxSoYMZJp4JJMWJLph0GwJ/+/sCM3zE2fX
9vmbzd75zABlioxULblw79OticSceES9gHbC7jO9daOABw3KSLxEOClUptY7By72
hcF6DBcBMOaJ+UU4wBYZXnkd0D2uvsW7qTZ6cb37QA88oufy3IqiC5AMvv9bhpuG
LcUSkk5fuGIx4071fjhuW4ZtLckbWGpNUOFWBYwmITB2yKoCyanKVQEhLNoKdXuY
zvlTs50KZebMQepsQhRTJh4+8gJOyOSQh2d2uZ0jIa0HpjlK5O+uq8LTliiKtODh
1PbGvl2QoKLgGj8SRGQorltmokqSaWLBj/5eUlJxmNxSJATQmnSVINOSYpQSPO1n
egbCmJBwKQpyMidbzydaxMrMZ2ydQxreVQ4uek+328m0s+w84ZGxoy07YaSMmthf
Tikt7MU/ftsm5Iif8Mrnj6qUGQXtbxX8gXmGWWGRnqkjnQWTssQNkjZpsgNt19bS
QwEWm7WnSgcMi+TmyGYQYP0ES2lAitVcRmnv42Em192YMBp0w8n9ho8k3rOO0u6E
qKbN+4n74d1ccHiEpVWVjEAv/w4=
=1fpt
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '83abf6d2-e7e9-4f13-aa42-023410784b8e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RCME0MmpV4Ol/UJuFMIh6OP9/WJe/8WcqlBLO1XaqxMD
yJXpqtbgMOov6gnePhKjdo8kCrNYGfU1FElZZw79rWEG6V+VobgkZaHc8nhgmhfP
phYyUSoWVq17qvwUWfwjw1MeZUrnoxVpmR6u6UFfs6k72NV4ldOb5wFCFGD2aNSd
f98sv8KDaSFnES3/GNJm5JBp8CCivyxBGuGIEAQqSUU9qvu+eTCJZ3ATEMGO8eOW
nsouwI3yUt6lly0tD7iIARdlrA7KyhOLAjL57jDcaswl0W5BgcCuPpq/Qk+1FWE4
KRCJhtqP2D0GqXC662l4dKbXJni6AIkSNTQhP6yvy8rHjuzeMQSOA1PZyzeaWsoa
Fooz45/DL46Eh/akWasjEiCk3qnfG+duAq+NyZ7ojmYRNUjDIMwkTo/IYKoERTFJ
YzRy8D0+TiGc68Q39kffofiqn19gic1ff2DCcIRF9wbS3nUBcAxn3zRTYlJh7XtJ
hVFikxVSYh7gDzMykF59rzTnH6eQMUVYh5QAyQYRpiibXSK+pGpDqdevZ+SxWAqD
XqqXPDz3/rLEcw048zI8zfZILiBEdO8kqcG+KCfYMRNLvZ//aj0ztbrbSWJJumml
aLqSoLg2ZtrFgKwnD7ODmcawZnykPdnkHu/dSrjfPSiJ+m8NfrL9S41NrfoqgdHS
UgFI+3ewjCvFr8ML2Vv3rWLkCSCbdr2c1YjY6jAYPfGpo7QCWoiywXxdpsW12ov+
Y28u7EXJ+YFGO3SWdljeI1B4KRZF9c4aAaatu4IMokC9XWg=
=YMXG
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '84968e41-9d99-4cd8-a84a-3006897ba6a5',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//cApevaTodFEPlPWc8R+3J+7SoyjSc/w/ughJIzi0wpRZ
jjGtv2SjBZMBGebKf+qpr5v/BgF4bCE2xt9MDK18nlMTUj3f9hQLOeKtkVIwUrgX
5HPep2oHouUzT3mh6kSAMfhY0qx+YK2VXkUd4cOzEWkSdsWhxDs5zoJqwrQ5Wkbg
eT7K7bJTQz2kvvs/3eUdYMzdsj6RRpfNKH6Cd1hi2i5YyERQHRNScRM5ygM80wrp
ft1KzKVkvzIQLbmy5SxIpEM3mbxn0pEYyk+Hxilwd9VME/cam4TZA+8MVxiiOG0H
Ajga5V7RlDu+6xW6OzPRhGBQg2W28vUIs9d2flXzcDQIBkjsEkZ2UMCYRztg0hRJ
JNs+YxOuQzDuwB4fCscvaLxi0EZSdyzGH6oGWEOswI32dQ21dV1L/ANdNOR2gA3f
ao2pPMkKxNjKy0sRxlkAqiIAxI+nEOs5MQggRKOTq28Gom9A5OV6hWG1PDVGj+gw
WY85Tbw/BKNRLuwFUNkEtGz/RrUcxEIgA/6JnJlFVrvCIw9F7vXTWrkjYET3Hb1k
wC6EdTuOR/aNCJ7cGLAbAoy2aOsxCWDxYxXOcprxuxYIG1/FvdSNc+RXqLvwEtbZ
xBjqZA2sL4LU3g3YCqHrd++DyLlAvQMcpD2hr5SCuR29At3aQayjLoYAyTfBqV3S
QQHm7BYCL7Cu+ntKxbhtVaOUM6Ea808Yzr29VSuCt3mrZ/TiC8qJN9ISG6l5AGBk
qm4kusXxT22QphZNlwa+ahgq
=z6pW
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '86457bd5-dbb7-4ea6-a9e2-80d99233db1f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/6A4dIZP488bRfypc5c0Pt9esvF6OIaYQfmEi4UKzmMESR
kBKsbGRNx3RTUSfZtv9ypzIlyXZXlRv4+xjMQpFHwWfxdzLgsSA21ZckOuq+4fZr
n/bb073U2pd5U9sFkexVDfMc3/2vdZ96JxXRwdphJrh5a4QtTmiLbX1DoXyYqKWK
Xy0FlvOSluFUhBS87w45A+DHBv/gELqk5kXGDtNb3926CbLm/ZiHBhtP/xUeQa0n
eO7MxN7ZXEPJu2PVwvZXzRFJ5nA1J+9232RxJMOn2wpNpi8i+5G6iD77Vw+MdiyX
NgmYzPjp/2ADpSc+g+MonrY39LciRcMEJgQltAPRJef3eozoDflBkaBL59958e/Y
sE8jlWn35VJ+TLbhUlPMSrpUG//T7iyfqbwS8R8NdJ3dWjumVwQsFnt1YlJsKQSp
NXlzMOCWlarjyPJOcfGh3/hX/JPUjxVD8Eplfdmu91POufgs5PX/BKIpGusUTiQj
PDjVFufZaYxRAAmygfQpp2LT6Z5Q+cRAz1SqdlSJBYe7lOe5W+am21eAIXvtKMhd
Ic3rr+Y/kBasRjEqugPBBbcRKbo54iX1ZyzIMWbfnWkSncrlXGS0wuqd1xhz9voB
Gody3GaIR5d6NOTh/7dy94sYMMMNdLovRah4Dzq1euabkUEo8q4lpwXOVH3CbebS
RQGJgaGIThy/lyVEKdyRulSZDkKThogaJ3jiWIQlY0mGEcO7RhfnarfU7tLtq0c5
OFcHlE9L9xSKPP0J51k5JNXmB7cGig==
=pUjW
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '880a28cd-f1e2-4fbc-a9e0-4c562857fc3e',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//eaS0+20fiyNyrgOv796pY8h0inkJKY4QqkWnR5Wpp2m5
gJGxr1SWw/OgZ5vUrYHekF/8Lx5teiKtme9vSJ3EQQTr0wzNO/0yso8U/ZcmgAHH
mgvBRnmCeMq13WjbqQrmWii7a3bW9TxMHTrxQK/3HQw2/xyR3WwSj1L9xBDXGOFb
bOngY3YTwSIMCivH4H5AYE1SftzlXlVgwGhNOKZkE4xZaPKc/c8lUb8Dl8RZOY8X
XnX/lw4oASBiomGPDkm08knxBfgrG7Fd9Z3vlojn7wYsDfteUAj/ryxZ9PqQGL7S
r/QwvbELTkoqnriFG2WHcZluh1GZKHHC3yQVV+mUXeL9JgLfyije4xRj7azjGHEA
HOJs0NVH7/6nVbAnFEglvqMTSM0jrhw/GFePMQEj2L0gRnWDYmvgL2flugYojCdE
79LW9/Yx5LTiQQTTSf4xdfZM2PeLFg4SyeYyiTC795ktFR2B5hb9HflBF4pCdO8n
tL8fk7vfCAVAnjhZJROUxCWN0gldShjqae/2U+m73pPm/HW2GQ/TY50Jf77S/0Oc
fzy7yUO5/jD6WXeclMzOgs2Z7tvSJDCB2Dpb+v+6PM85952ho35RhCktL+m5d+id
nbToD5Izkoaypae6BMVsXBbE09CIGu/DsmQ6NG8VzCXCe3AaNO3plzil9UDQORLS
UgHg4YrG99RN1bqjstxqwoHB/L1x1T9buDtWvtv3ZkmyXii/BgH00DWxROYeTH/d
HOSoSYZO1P8VhISBI+9LxaMP7Z1KyXNC22DUmCEN8YXi4pg=
=E4DI
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '88158ff5-108d-48c3-a922-01c891ecae8c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAwMHo7c6XMwvCNVznutOr1AkViQDGpIxx4Al1xb6z0HLf
o1hk5luCt8DLLYjL7LGMNkFlQWhddUQy5vuScGsWZrqCj53QHS+gZtF6QDCd7d0G
QxPkB+c4jbLpoWl2Lqm4eOyg0IJkf+49ypNSFr49gZdAsCs4KcDFPryghcQg+my8
++oPg4cdYt86Pl7H1MylBng0GztcxAnc89TqXDHOC397/iShKsL8SfQrmhjUZsTJ
ll0ztq8T/mttaqvNqov/IbtwVtm5/SAXuzx7bCmecXo7VyJZFfcKontvdZ2RkhPG
MauE+027gH5oxR3GfMOi87DLSMQczyzbKtDpRcgFuQQMwJkVxebueI0OLSP/7KzN
KE1uZomNv+E+Nyz1mHgdBFq1h80PceCb/85/uk/2kh8Z8P/4mzKMuzMUlfDb8NTE
cEwJU5Ciz+/QRwIvk+wZVSg0hKBBPQHhWEIXIPCbrI1L1ZnYeCPhi3HTJukBAawo
bmMoeNfxsD99RKUw+4nAUqq1NWFbcVrviNTFmz+mh6/12AxPdfUIq7bZsw4k9KgX
bUg+/7uGCrdwQq1sv2zr/dhFvB4IgMyv34SLk8FHGQYiFQe1keBnaQEAL28QpJMr
Y2Q8jdBSpMuC5sC+5KW8AI3eRhk+KIJ3/CKh9ABOzEnRtZ0OVbnjxAOIz1rr/gvS
PgG6F5S1uzlI1u070QllygLqaDKxEh5ipxinS8QfeH/AHyc/HDkzsPQMECoOgXJF
KwANrPUahyVYs/1mOCM9
=HuiF
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '8c467a3d-fb51-49c0-aaba-11ebe0b2c57a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAkGcFXR50d1Cm/uZZvXFz1IhbiIWonTQn5iBUiAIpeEjT
xpYB1CLLEFQeoljwsjXOWnPoW6yQ55W+ZhcOzq/OUVKHaFG8yxL3ZI+/u0ERZA9K
oLJyZTAKwXUqsqhf4tb+InumOjZsY8xHYSwQ8ZRxjUhDaQcT+co5KrA/V78HXQM5
CiqV0aufLwW+F65RFChSQi6yA4kM5lxnZKOzSq5BqaVzgrpEzeoiDPDRdEYTQyMD
ZcHnf/Dz92anBOR0VqfQ1w7TL4nVvvU5pFyeL9BkRv5ASbHfI2bZHJTvo550Ags0
LUP3FWd8v/d3oYjgI6BicLUea4anHxx1f22p9FCy8dgWgaxOQtVwBIPlU8NnG04n
gf5PV3kKFmAXf+Yfy38KKYO0Q12M+TkGuNpSOeI+xQ8h0iwuTmz/VIs/hIbfWB+g
SjJsjCkq6u19FTeC34bu5j81c3XxBMr1OgnItALkUHSfa0/GmK7owmw/DFrA0wIQ
xAW0mz40vnBkuKAFFI451Uxy+J7mrUogAwJf4iaE+5ud+O9VNkcAU3ByHLcprrJ8
WAqaCITHiTkIYMHoypDnNtbQrvnwWcdJuQR8IQdpOftqVEMt0XnmreROT1ViEPXQ
64qflrnYzI2udPfn+joICQw5Y+oz9DiKmXHW6y6beSUXv7+yfzhoKpNsG012Z33S
QQHR2XzASfDXwh7oO0fcKS6EHThTYQgZUb+Q62A/+mnNIBGq4HyyksFyUmey+bS/
fR+kM767JwXpwae3P9qYWXSq
=kyTM
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '916b6091-e121-4711-a2c5-d7a6a34921ac',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9G0Qi9PSe16BiXMmUQDqnevFoLaspwszqxVPCHt8WAq8m
9dPW9gXSzX6DNb2B3cbt00olAo0A5Ywu2FbiuIDlO+33yoVIM8+gIepnawzPu49l
7OJDQsfXm+UytdSnIwRI5++nW7ll/mh+JHN1ivEvNd4IEPfIeQjd57shY4alAGsh
i7WxAtcL3jM2PnTPzkAM/BLYmv9kq1Exmc7Mgp/X7V8iN8D3+B8r36u23KaBWHXL
uFAkIFcnLK7SfHpa1JcHo96+IvJYWIHQPD3KDMhVu8o6M/H1S5EY+ZTJZtKITRTe
5Gu+aYgJBLWkCI40JM9stBVQxpKj1et87x1Q38iNx3fyMAWfufbAdKa/68M54UiI
AjZd03J72Wm4AS87oDUSwsVgSDYZ+6VQZpq9wsnLUCjBDtv/pQvs5pNzCykKFS45
ALpXmxfph6c/gprjQA5uVSTonFrY/ltI6+jkELJTLu54lAuqLiFcEm/I9HfSCw9h
MBH2o5yqD8ZIaAqQ65xf4f2xKe1RzzNVl+y9icB5ZOsPOTi2DayUqr47Qn6YK1jW
uuuW6XPkfHJewP6kVb5V5zP/PtP1IJAH3y+SKUgwlEsN/QeEvGsxQvY28DztSefd
1QKxHoZHm1GzxtN95A8i1M72uRXcVEE6rGza9ZljU+bdtYq3bGiU8eoxcf7EwzbS
QQGoQ+aT5yY8B/27faCqy2n4P+fF7oUzFXbs0APjv9ULvArZP87NTA6o3QRLexxe
hm0L9HOnBHhe0x0ERlrRSlLy
=qvgQ
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '931c2b02-14f3-4b9a-ae95-92fac3d87c6d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//XdqI87OTnTgxuwWfGMLMqokW6b9Bw2pQu/NPbmnPEhuA
w0/m0U2KM6zE1LYIfkW36Rvk5yWb5soFSFB0KcZoFMqNZ2ws22p7vEgO7dM7qai4
kVaT0o4rXhr+dXvsCV2E8XX/ed0KsLqCiLz9VWaG9BxxxEN3mi93mAt3lv///p9x
LJKW7wvDT49uPDUk9fj9SQkWNVvBLmPVHB+EQhP3349qmThiK4kHb3WpRFCOnv+W
ef0DY8W0f1fHcRgUHKVpgPplyuctnwexqNji9psdcu47cJSDOjyuGT9gVKHm8wRd
+rC7BRvr42/YXBn0X7CrwLBdhgr1HDSVsF1pduzr4mB5kadAPCYBFKUW6YHWWwWV
01TcBtmsAh6ZJHJCn/5FN+Cy2Ahaj1CSnhKBjXCc/imEyRPk76Nvpp/sOc5QnU9p
yAKuSiKUMmSC0tSnriOxEjKojRzHysZwehUFFHlIuJUba3gfn9BVB+H25ys2iBxL
79wcSvGYr5wm2B9BsWMLJWN7hSSRa7MY3LdQcKEJeZuQHld9FsjOYdlc8Co9pTtJ
M5Jar036CqF/DlUdSg56CtE+D+9j+uDlOlDpo3HytyBmlA1vH5OTREqObm2fR2NX
mFDczupekpZXrXKCMHm4iGPUTQJ+Z1S2aTX/S3h+OLpewnepAqMpxGskkikhnRjS
QAGjN5eykjvinp1dUsSuWy5VQ2vEZII2LXLjQdSHNxLbi40EMKXiHUaB6292lSne
7lIaE0nFwxhYq1ZE8d1zuXI=
=xfVI
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '94255ee9-8d2e-4747-ae43-b256ec3784dc',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//SibHC27uK1B8XhPSNN1/vv0y7JCYm39kQsSi7/LEKT6M
E6k5QYg7XziQYasCaSof4S1XC+f5lHhJVBXgtQ9gnqHEAPhUn/AhZ3H0cUrkmx3/
gOmlGgFPYBtanA8fHWXsEnf5l9nRbaMQcEk5ClX2sZ22axPS+UWDX7TxIeo+CReW
hEIzOUYWEZZuDCC5MMB3ch9P/vWUPqPY51EiF7c7ej1uQys8mt/wbHwyCPK0Ah2J
W81PF6UNJ2z2hnkcwTjX5+twSQRs3DXdWAi2vtwG1wNInzTCbaac3dbWeOxKIr4y
BFvtCrx9xW7A7N9RONS6GI/Mx3MQtYyrRMfn2fIYJsbGRIUHGpYJqis/mYg/m464
RDyqO2Db+0bvtfJnJPtA+tnnWeelexQ+1NU0hajvcXHWelXJrQFuUEW8v7l0Tor+
XH1My0X4gHZ96zadv7Vx7nkd44KjrfNdJbOoaORrR1YeUNtxhGAjRHfA0Y5jnyB2
VrVAmVMGGmZs8kMgEK+oubiFm55tvwAVOVSxnqaKUbvbMdJcfeJcskOdjZZyYqmo
/ugmD8Ye6BiSOxlzupd9PEAiZQVzMkYtuPWgEGkll4hep2bMkTHUDW48csz+BveK
G5B1hU7CZkoEW/f5E4n07E9fUa/xv7BKBM9iXDHb7od3QN64jEYcr/uEHevk4nnS
PwGxYis4+v9Xoady1401GYL5OIptHSA2xYgwHva5pJa/7FCrb/bn3eVpe/FhsUNM
BJ9TZurv3/lRu1dv6ELqxg==
=QsaK
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '9b0dc94a-37e9-422f-a32f-b1c41be668a0',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAjYumRuyVwXBTzp/ZX4VMKSDeOivPv1krWeakcXqCRANI
0r8ivudF9fqsFf5wAWySRnlaoAgk8lhCgE88Cxx9mPX9OUW6zCTAJWG9YCOkzL8J
v5mQm1ljbXY+R4KRlOCjqtkTMLfnn+Pjtqenr6PlXcFl4wSMOI9+3N3V+3/emVUt
m6XKtfLleOX0T8t4PnIMffThGfLdh6kMxtzFdMkDCq4djqBlL1DiqOnASfMqJ8aL
6sFyFttoIh0RFoWoUIo9YtfWrZChruRolIaV23Yb4tw5gq3fB/tMH6nQsRQMas3s
Nqgm5Sz9uRq5wRNMef333Qr245pLhNCE7aMy9tqFAwIyiKW6OuiuOadrOGISXZHc
V38U5icu0PWjEVMxYLwUeLHI6AKYcEvE2vTL83w7lEMBq7Gmwzoj+RjVqgGRdZLe
l3KTJZSxHCXfnLLzQqYSwB52VPfsAfloRmFcaiixwkDmSU7YYg1vybTSmTYpLyJ4
g0o6vSJEbkqDABxW5Wrt5xVvhRzrkD4MRgg6NTAoeDhg6qksmhHYapJF6LGmHyvh
GicQ9UWRrhEypAtoHAsgf8cOOyeSBWcNXkq6/xx6+m99NyfRTRMHVwEcRfPN6RAL
/BTAVx2PpUpLtSIzErs0xTr160gorQBokfNxWPSr0UJfkMy+GgP87GqhyUIpnSjS
QwH90x+FsObGLI/ZV/cbxjrSKQ2EBvOo5F5QqB7uvQn4Do6sxPtb6/MRc19bwZn1
A8BoSY1JM3x+v7GzViGLz18grJ4=
=xBGF
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '9d32548f-4350-40db-a92e-ab371244a738',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAiFJKXa9Igm5QWkhJiVqft30DdEvBgVQ0Mp8dxGHQR2K0
v3hgw+sxWfwze0g66I+jeO+GkhqCKEqZzTK7FnrWVn1p8mFanl/vC+AlWnZVGG+w
1FgCTtPBog9JauBdT7YODpsMV3hw9zdtX2wwuRJIDMhLt5HpFtkjVLPnkhqAG9sR
Q4Izg8Dw3RmKID4MmWi4zRh/zLmogtXTkJCRBkme/+Z4qB4QTy2xGHk0VvBgOW04
a6Ig+68fdgJmfwQGe4qaKTVVs4xgCuKPslsYoh39gJLZK7yzfQP28azrPStus+IX
UT8wo0FfhrrQ1iNzi4Y36DUyQpeizCXNmJiXXlz3nWsi74Z++ZnDl/2nVbV1Dy1n
TjAQLO9FDddXp7Ux9jcAf4XT58K4h2RV1Qs9KLMoC00PKLJBS8IbcxBznbXB3tz2
U+XmopF7Bfx0ZnSkckkYcEoGgQIUCXUPJbyPbuKHwnIVFsiUxFLz49tuxlpLybic
vGXZOsgi9zXtrfzJrFG0rttEEcXEu5HWz/UcoiqfD5YO/65gyK6QsrmXqjw0Zcew
kO56sXvKk+INYQ3IMedWdOnXBbWVPvTSJKII/GQ4En7Ru1jjtkFov1z0QKLqSYDQ
qXWRWWNSc4J1zDSZfsOR7UzoRph3uY2W8r+VFeNVfnKieVWOLUS1uq6S9MlL+mTS
QwGa2beJj4rRLatyfzwtsUlDQw2XyJnLbVEyT3B9Be/j/vJz8u0cBleXAFjYoMO4
TStXgQv32HzDWZPKJ4FdYnlkNLY=
=AR4W
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => '9f15f87d-3337-4858-af4e-df8d69714923',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/8D57yF6w4tgxakWWfRb2xuV95pgsTeiG0ETFreMKblHSQ
NVSbTSAbIr05FNXG23lT5CEHZIHDMHmfMHKidIJmzgUbUx7AFRUuV9pU+oySPMxd
hC/o5P7/cHKBohIU29fAyfD5qji/m4o38yrvdhfH1Q3wWVEP0mHxdrXwOjfZzFIJ
oCtcw9IU2hh/fZiNZBxfNeyPq21K+8gl8YJNS/y/W/odzJtA9jmLTq2QvMh8KmuJ
MuxZra9Y5IACDEzKW1QgGcWNr1xCsRuHGqGW+LPI4cxX1wM3G79N+dGzjpYEOAQQ
ai7ac3nmV7zzELmGMGraz/msBOELR5gdiwVK8ePobNcToeJKgpafg8s+9+2dSdW1
OhGy6LhpJRaC4RdoKQsMPl9hfMWpP0tCoAj02LW+pi/VL8YIUboOmnz+pR9qQElu
o06FZhaSLu8v/arOLWAzBbW0Lb0Rz9MgVsV6yY1UDGr1WyCSP/h+Se0LyQo+lySQ
XXIr+O2I9kEDWOPCssZHyJDvFV6kQuL6r8DzWr7lTD9wYfqJeh+oCR4i/6B+EO5g
keN9xpCFp/x41MkiLUGUkCIaJNGj+7IPlsyQBG0auc0wL69uilTaB0kV2EOXvvaE
At1OOLJFpcBSqVmYuOY/SWt5vLRZgwGXSXhsoZcX47AgTwMh+RUIGTenIsUkpV/S
UgG9URUmkArnI7GhXBA9SAVgIOCM1B9fyXALQTN5s+rPoioBlnSo5hJXgV2X4VYJ
fGAH8NUAIEgifoHTdL0oRlktefuQv21Dvmv4C48zlpLutlw=
=a4Rd
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'a3f1723b-4693-40f8-aff6-c5e253fe23a7',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//TcCmUwPdM0qaOJA4HG4DVfaXCKgmXzd5Ju+ctk5/uHli
iZazRKTTGWb8I6f2l+Cz+OtGAWHYHWnjceUnXXXPfC6SSIbUgVH8OpPvxlQLDepv
mZDUSsQ0ONIJXY2CzQ8nNhTfFD9elsOQGzexLstNZDDPclkpGLF5JXOnz2NfZRNR
x1/GneEUovhEjLhnt2J26Buk5AT+1z9q9ioIL2cLL8JDrbV2XLT8PKDCt7brvyGn
BPY4iSiqcEwIQ1Xnrh4bnZDZ/GDJ80dMysGLwb2J5gFx4eCf0UtmCDGqgXc+IDT2
ynvBvTK8+opUC/Tk0RsOViKDr3I4+d9zONtwEXDu/YdadH4NZICyy2eQ/CCXumw5
WckcGQSRVq2dojy4oRfbpJmewJSgMm6kVprMEzvDREbj4rpeGKrIS4kL0YzIVCY9
Yh79Eu1Mbz1Cc83R8X8Pze0pwV14LJfcxTgASaJt4S8TbQ+n2BjRDKdsss47YldH
KW5JTWxrI87h8fDXAzC6pu9CcuWG+Oqo5g09aIdw5CcR1x3F+wbqrB90YL0nmtF/
4r9ihfkesTrgfIgM7CfM5Kb0htNsIZuBD6bOBL58Unm8j42pflMarvY+Ze6LBWBh
DPQ6KmXuxkeqLNKZL5tk/UEma/UFOE1s5DXfM8h5C53hcSx3liyvnvyD9X9pB9/S
PwHJASDy3O33FmCOggu521fLgrDKZLl5/RXMJQU9qALkAaD8cht16ilL8Bef9qlB
e31rYlNwoUq8G9ONhOS3/w==
=rKDG
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'a41bbf4f-c837-4118-a8e2-4391f46eaa5a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//X21xgc4vWn1CrK32wIh/6yB9gqhw8zOIvmOpY/DVPWQt
ZzsStchfcUZfLtuG0jg5GcMaaKhj952t1jvcmv/rvg18VrX7tOeW6bng+txNPWfi
xzS4zFYiiQr4AOdhQtLEPEHHQ2kbXT1jxcc12mi0XZVzZ7THUIbSsTlLNja4Fesd
jrZ2IXLc94WV3PCCoEzpJt36VnUuGfPRRzD/nxI63npVlk1RL+Jw+/r8Ska6nRmQ
U9tk1s+8bWNwl6LrQPN2feduQfRLtHbxn9gdTzl9EbgXJpyqre2haIQ8gSJUHUDl
yfeeWX3zvXLfh5R//A8ig0F/9JXW17dE/Ba+OSkUAbeBRaLHErK1JRr2vcav5NOE
AoSe4s9l0+5QWN2Ko3gWHsfog3sfl2kolnOGOIUXbFubhoXV4zviHur3eucKo4pL
euB+MPYvz0BfzwfxJVPb3nhYf0X8um2a4ttFlAhip0tne6bVS9u7ghbkKTjoC2x4
Odqn9fy344Cye+0cMRfhqkAJ4KxFiR+0pw1QPM9nRQxqR0WEdIBCh/UwxqRF5Rnn
XO5juldyImyjapSWN6VwMlDeMsEowYMXqHKwjkRwYI8xfiCnSu9/ahJxUIZzflj/
uVCQg0A6kgLJnIOuIWmCbRkovbnnBq6wZWkkc37U6usALioCcAXmaRvJXYKP99jS
RQEH7HMhXqj8Z8EdYIvFVLDowdfQjV1UVdBftrXQlfmQHvGEOrc2xcVkjrtm6gW6
XNgKD8NXIR9laIbdZOnwLeiJPemMLg==
=YgHz
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'a66dbf07-d4da-4e48-ab87-69d337697ccf',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9FERdlz6teVDi0ynQ5+Gn7oLafe/05dvlKx4UBO84TK/t
e+wIuks2L2t4VKqU4LYTKQ1IfnqNSLAGhfOaSeO4YOa1k1L1lEKMyBUf+97ydWtS
Y119qzD4ejCFc8Uf2yiIuP+OmxMaZEmVgTAef+1uaF14gdhYmyNiwt+BYJV1ItaA
rGc/wZ2ztqXSzZXz0INoMABiCZ8Hc/B/CQuwmo97jb1xph/KQzI+Mw+qwSe3MW8R
2Lj8tOah9lzIHWOO1EPDsBZGHEZWZHH26ZmvkF+CnkzUo7msHIxJs/sL8/b+FJhA
n6fiMDeEn08Yd+AxDmyIYXhtAjZHSTTNksRcTkPIWBl7PVEVWESxqEu8Hk5cjUg4
la7ihjMWawhZEq4JahJnzXXLnnevG7nRNov3pF4KzJKDo+BRuHcBCdcAIcWnA0nB
PLQ+i1kmcLvXuJo6m/qA3D3Qqhb7nnfmGB5JxMZO/Rvd28C8HD3J8bI6X27o0P1K
paFkbjTlxJLBYSN5RhPPwp/7RVQZrY3oAXDGdamqP9P7zc9jlYLMqIMSBFzpupt1
1bpYQvojQg0VvvrI5zp130d8e4TTbYxTtSd2gzGsXEHizAlCpYpAjWTgiGl+3qzz
86kQmnqJ2UGf0UaqNyzLjWQlpFY8U5kQ/vvWlzK+f72AFN4M/6e2xENji/tAjpnS
QQE44qV9Ll6yuWjAa4t1QqQFttqRdDIa/uq7DiYwGzbkXILZH/McB96PYD3flNTU
Qkl0vjHKWMqy59qH/ROyDTWN
=DgAB
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'a9e6e77a-b034-4ca3-ab53-fa89df98c96c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAkA17HIG4IWn7zbFVu8vmp2qio6EQv5ws53ECYqD+PHuG
aqG4ApiVvGKj7A5Zrbl4nT+luDeFjPXwYzEbrr6NtNslMPxlWpAQLxDzkxDxco2A
4N7K9Iq5e7LnHZZk3PjCI2ssxER3v9nTlenI+qzOx7udkuctQXBnGTF8edHTzM18
nBYtT+gMp2jqiMje9eVk/PXL91oDF44KQeZ/zagClnZiXPHCQDWufb7mVReot9g9
MXFkcJHkfyOQ/lwmCUelLGNu7JronbXYcrys/0lMg2RjPpBgZPTQnSKMnjSAPX/R
AeCK/lhJYJN8rMNEkWC4npVHYXAelE2LTCB7HHaL38x4P4KFPmWtC8spp+PK8ogh
9+DMB8bLL30D0xQVoAf1D4b9KtUWzp9uqsu9ZK8vQRIHro2uKBySJ28pu1W8p4xv
CwS5omBqiCGWvFaOvmUmqYAhQPH3KWfJrixqb//OtvlpideXSc2zNEpNmAZ8lqV2
y77NbXW1/jijlK0vZXComRJfSXiOxfPI/Uqkd8aA2+Il1Bu2UcpwuqUDnktgJY9h
JjT5yslYqu89BYV603qfcpTADFynZ8FGZsmiyPGmTiD94NbekXDLaNZ1d0mUoQZZ
pJZEJusGEsJXFDs/p51kQK8NV7FCyUVzj3R1aF3901NLZMJuDeBDJoVOqhxt0X3S
PgEGHpfbGlrGtH2D2Knqc8HwOdwcQYM370FWp5hRFApMfX2LxxnT1wlSjxqZsYKS
bwhurdnFaLymgK6ia+SW
=Tt/y
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'ab1ef423-b061-48b5-ad5c-66018767c208',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAkCArmWnfDABSpVJPiR/7/CVpyNUL4WXPp5ZRJpQpLDHh
C0fkAVbHIRTcWxxsZbNrxQaML+Wqo60HPNg/aVn6rHCtUnZpc2x0c6zpZVbkHg0x
PQE/hMSYMJ58KnH0LvoW4bRBaBUuInxjDwGXTnNADWjke70A0dQ8c4FPNurSmqVQ
03bTI+n/eu7PQ5aJLCSf+KZJFrR2Wqr1EiUn/nyQ41i8dyqDzY5+BlsjxTnRsoRU
A0RuWLWM6NsOWAPKkql4ubRYMCakZy/dMc2zhq8xiu5N2b3JT20dtDQKxgNveNZJ
DpIP+ofp23vxwEKSOKXseOGujhl2VLM3BrsELLMfPSya/ffoAF+5ggcDc16lyu1H
MvMxuBI53KxUWRa3xY8EJMIFvaNSP5//wPmPB/WF8ZObKm48OcvPIkq1sPPBszah
aMaLTFq9p63yR6RXJSmGFroitdVSnMQj0ndhZqf8k5NEmAP1XyUy8RxNBCp2cyv5
KKrvlBYI+od7O6L+EinVwmTUbkPvRRe0FbkM5mHARTTord8dfMoIjPIUOtBndcKa
FC3aLcGwtonIstMg1UUhcqeq4gZGqvbkXt2B0sRydeRFIvAeCsgzVuco9XIJFe9a
zU8+D52JCk0hPrGBAmlx3i4JUTFbcPygX9bp2K4NZOG6Y4yAKgKsTKAxMyNaRKTS
QQG2pMdfNI7uqox5UnErJBOIIE8nvJs0C49zMPS5Vmo7JfsSASG+8uzOXYtfSk8Q
9wISVZ11zXcLhNOycHD6ix7a
=Z4Tz
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'ab81405a-5091-4d2d-ae10-e8a507070db0',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAkCgmGD2I9b0oQrNkJfK8ZbNOAbiME8ACEvLDGavsdCEc
gxdu8oqA7PhQzY0JLZGj0+r9P/yCfag9/pm8OMAsvS1JqlCUCryk7duAcoMvoR5o
rVL1ITVI2BrwkUpjWwbluNdQOHSeE34w98OQSjVzIjrI6hciHm8Pr1kKKgwY5ule
N0TzCsGGTVZtnmlohwMo+sjkp1ayvx+NM4+VEyVhIq2yx0YskSr3l2TExr5BvcCH
u0FyJIJqfDRfc/yAnPzafXO63KiFRhwRIDfwIHvnyFS1obSdCMkNF5CX+Vz6eMnA
qmJ3W8N4bpW2W5dweRq/xe742BXfa+i6OH2zjvD9S5KBjAh+RPERkkjr6/Xor/hu
jLbi4QLrTFpju5SbldIdFG97cq+u3EG2yjiu9GHgXDvvb96tcpvt/ChrwG818WW4
T8twY/g49xx6IQojPUxKd6mnc7uR9EmQFs+ke7VmiXZa0Vp+HjwrUf5kJHNRxQxu
5fesApjtzPK5Xvt4dzsbxKEvEv7qtL+i7LUfvTvIeL7r8CKCDZ5omzcQQQIjGuVc
q0SLQEygu1WOuRZozbIDyiD9AxPm+2iHlHWRhm/MXJLFUl5nP/wxLkmV1Rl7RGZZ
a1uiDPfhhHe0O1ZSe+cX0aJxp1kktlPUiQzwm4wpICUzKMfrLNDjrGz4XL3znHLS
SQE3RQCMTe6BjJFfwrxdJM2DrzGIr6MNEY2QVWz6b2B0ZDiebRU5BhsH7myreSEz
DMX/UQa/zJkuNj3Wv7VH12NJQRaHinXzPv0=
=L0SH
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'ac199477-8ed9-449e-af65-dc9c754c6dc5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//TgIkq0Tg78qZcCItXRpFkXcjQhtAmxR546Q08+FBzdiY
e5SH94E7mSaSAjGUh86C207H7pSA1ABZ40JCR2bCeAbke6KNDA3fdYIAMkRzApp+
2/Eah7e5baJLdAqL6Pz3MEuNFOuMDb2qoX8rsH4VlvK25YGFAhA89LsTft+HvA73
9AI8J8J+NVkIPpzWuqsLK4FAjwbmadQZPXhcjm/4Yd0XWv1/kimjtxzkw73QnLy5
swLvawqjcxOANRet6pgKWMQGto+SMRxIOYw/inzE90DvpixOD2lUQRRPlSmSqoqJ
ZGEEdScyiSP3JB3TuDenL66fiYdokJKJENmWvoMv0nUvDUPci28XHcA2437/AwgW
jRxLzwRGxddO6qG0lcpk+fsTlY5ijf3UGUTsob/8ekbx8u9I0bxhqkgFjCSMFmJD
jRXOXK1GGu3GBcV8hL4aMz//LH5JA4+R0rYG2fxnqJ+KY6wzJIdsyYx13IfQbtxV
pbj7xtG4xzn4gcmucFBXPORP5DAqFbuuGTWdymcuGtljkOHSzwRZgP2RGf5nUXvN
JrQyfiNJJvJVcm5yK1RIkUNZQmA7+086QKRGaywU2pU0k17ivwfRHBqYmGHtWdqu
eUu/s94eLwPEWV1h/BMTss+Xs/DkqOpLptqWPsG/a+36q9T0/ZQEtFRavT/+XxLS
PgHUPAQiiM0KEUIQdgyc5VMs9OAQow0yca7fN4ak/Mf3//SLfUXhsdt9bFSJcJrh
UalocthpLlfAC6EwJKRL
=Tjs3
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'adf44916-1f7e-4cd5-ae1f-70adb3ea0e0c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//Sepl7i9WGLNW7+lLuzpIN6164sd1bJSzZ5b5ZPCMfqe6
QEP+GSnee2VpS9DS/LD3WjK2y9JK6bDWPRUb1U1HnmhwgXxBirgIkjqJegFkQSyG
S3+m/v3/H5sGppDVuO35tF6lMWJs8b/NSlVYQZQ+eEDeK67OggCmMZ9ea6Z+BeuP
jcbMnM/dThvJM/ERb1OYiWwou/MAMkFF5mGfGQcbUoQcm3b5PtiSHiWi8hHQNdzF
N8k6fiKjNbo4NPKHABwwzqUhH5wK4ed3DwS45yBzjUG5bWPl1+fxHShDUJHn4t9r
u122pB3Oe2TlmWnTUx2EISl9i5X1vh6/EbdxG1/4lpaG49lls4EiOBTyjtHeVY5a
n/hY5HYUjzOszMMf1BzvH3sU7+DqckWMY9e4YZHJ23idD+dkh7ZWnX9b74YIZ3Nf
neSx/T1sQ7Juj91jPD/4PYvUqvrwoLIEpj5PJDWhleyXXG3nenbuCDfEPvjvS9jJ
+oLbqx1ZgQ3iVHy1BMNv3N37IyqVbo8ScdIqoNA6iytZazV43oo17Il6ch62NjYP
qyW26QaEs73HJe5z8FX0sAo5/R9F4MC0KTpalMlk47O4JimD0KBnKLsAGlbdEBhg
LrbrW2ywSne/I4jMoaSxHS9pl89C583bjwn4xTpZce0j1mUes1W8Anj0HO5z/xHS
QQFRbYqGWUyBLfnO5FntMayRLLjq5fKAx9YroXWX7Jbarwu9orSMJW1zTTpMo3kk
IPjyf0KhiqzxIj7gQhUAbBta
=lRAm
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'af0b57b8-6785-4056-a386-aa745bb73a77',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XUJnZkQg7+k7klOc0vv3OzTsI0J9193OAr+g3wM8Ic3z
egGYvlvFTD7YcjbxUx21Tj29KshVigJaFjkW1xUQCeVIdPOq9j1UrkJY/bcEp7bi
9XVwvuHbspA9cGaZv6efFPP0BdDi6+Vsk2XdWX0TxRK2iR3hUckbKblpJhG3/6qW
BpV9WGahiBaCS+ZAKzRd4mEb+HUdSRoMuFdrov0BCKWXAQuEeZYUtBKchcDdl6SQ
8v8m29N7ARx9D4MGoU7UNtpL3P8KdGIJ+whKlylDC5lwlvTA++0NONs7zx2EG6Rb
1ocqFMyARuPQnkiUW7TxQi6HO5ogWU8+kDA74zHOwGjnP6bcE1xG4+YiBg0gL/uf
uL5KVme+9XnU6TJkT+UAI1FTQGJN+vkd8Y3H1rvFp96kLLFhC5CUiEC2Et9FjzqU
WKS8Lf7/obHCZTjKtpKeBoU/LUHE1g/mXHEH8+HdcBo6ugbTGYLTbJ5TRRMVImBY
2FPvw9oBpzxgn92vQlDiz392gmrYl8v+dU+wSsZNaTpi+WPqeq4NuMst9PEsKkuT
Colo9LG84qJjHmL5+Q1jfLhjA4vgr8MQBubdEfbP7aePBVQUOREBIsR35FNEEaDk
cH5ZudjIdgStoQ1qiMO9nc07jUVoq5wTI4PgKliZIJfxnzylSmEe9k+1YPwwHjDS
QgHlvAOO+HTL7uhgfCapqZrF49vdzj7YAc1FCVpxypHJtw7SvvVHitKt5l3wh6sc
CRg1IT8bMkf+AUFDW/3jncXxSg==
=Uhhv
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'af5d81a9-5a0b-41e4-a6f2-8d27ab17d8cc',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+LHUHCqpjj241c+VfMJqG8ydNlywGx7gkkxaAIXJFnUrv
BB7uJynexic0SdVRU4S0S05GtaidItN6CyCfxgpNzZL1qzhCYv+ky7ChadR3Cgdt
A+sILwVYqLtB35CSz/ZaFUxGNgZiYnT4bjf4DqKqZmsHJPtlUzgvCP/jctINg+ii
PBf0HL8aHzCnnRqRbndteByjU2kRIXQHhfcyPk/wma7nDlmiOQaetgXE9dOOu5PT
HTmnWaz82S/1+RkuTvrpbVlWbazX33eoFb1KGvjXOvGLfa13vZvNi49RJDWDbKNk
PwkUFY9FxyJQUEm3r7ARu6mIyi9zzv5dcNe5OiagAAFzF2HYzC2pINwGw/ZapUsk
z2Swhxtg5otK/WZMHbfq3k0y2FY/yGordHvI/CZHxSck08QnIzNhQNvB9z1UaekB
LTlQ3VLH8ptcQwAn4GaEOo9AOROQA1IXJLDgKy5m72yw6kF++dcYuJzpjuaLWgRw
nC0mZYN0jOl3ahgWx6I4ArWpGlCgNLTQOrsNpzn2B4+qLpWa/7O822nWVPVMNMTI
fVQgiW9/7hBl/1kmTrSlonZYbaV6RJV5dnv2tYDc8dfV2Xfc+Tyo63fxQ1zBP0Vy
+67RVWfB4QjuKstx5ZbxMo7jCa35t9B7rOimoTyy+3Ld3OpkzfMX5rFrSJoFo0TS
QwFUBTIuDg72RRRvl/FQa0tiRzIW82VfGlIEb0K6GEq643cP+Wo8OVKtWN1Pt4cZ
vXxsVVIQRv0AZ5wIP+do+Zn6ojQ=
=9F/p
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'b0889503-8792-416f-a060-0c40a2d5fdb9',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+MdyncOQxNnhS+IVbU9V3RaXeEfIbElIMvcDADCWiilLp
rW0MHgxAvve+KSyWfOIri3HxsHnHvfmL6//eZ5nSwYMAHAtfREOCAm3YWAVS1KfY
qggiO+ua7gg7TqYz9WfeekSsrZzcWKDYvdPN6jV7ovQHEvQX093h+kKR1a3j05h3
ML2opA+SQy7YkQWDJexkkIoHypn2Oamw73ycL2NuhsbzlQ3DNi83sRW3PaHe8Erf
yZSy1P5DLGnX/Ky2hzS+Vuu910OhGxuC7yhT9DEyd/Fre3AwCoI9fKk53S0NavdH
nIH2D66SrRHQO4f24i6pjsuypP/+1z0zLVSzVec0B2qAO+BEyhZeKQzAufHPts1T
Q9P+wzAIFN4m4lOBZv81QL8x+4lCAT5vgfTUD5MZFR0rJDdU4k7MBOsV4ppc2+VR
huym1OMRohXG3ZvlM1OfJ5k1cEKtc4SjBu582+EE9leHYAg4NM7MVg06kf/VNOf5
sSxekWF6+56+PL0b2gxCHBLnCCr9dxTXkhuxX5EUNLdnu2VDT1ngHHe35j/lcu2N
T1Z+K4F0jXzNq84csTyOClAvK/ncOXY6mZhPkDRhhuZqYrKC+xw4OJ4JwuBWOPlT
hwITCXrBBqXuVTEXUF91xad0JXMZuX9/9ZBTWr7EBtKCRANMDqozfO5jk8QNhrHS
UgGhXi3bx1gnzROvwLP3VwxUcNxyEir1GCMeX99AdrXZXP2KU5A/E7tfEUgOn7kd
tRIWrvELFS3C3djhG2x1D7an9fYYqluJAPK20igl6iC92rA=
=Zoh7
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'b0a2ef20-52f4-4ba8-a0b7-7a6e39c21e65',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAhyc8UvMZip2tyRNa73n0Grykcy1f52X+MY2kOyWQmXI1
/oZQUgH3BU5Sqp5UpcqFr6x9HbLIst3OWF3J2NHMvNMQT61RT0fbvSPDWlb9kmy9
0oaTN69DL+nKtm4SuIeBwwT0s2ojEn5eNMBpnoaOMUGKLHfLU9K6o5BAFREELXt8
ETZkGa/RgDJ3luElbI0rvdRAqY3pq7LSBsDO1VHYKw/PVZJleSAacg8gFJr24wYg
1ui3G+LOeR/eV5MnnNqJVMlb9kwn4peyYHB+SqsWpuC0Wx6/d89PY/iZYcRJv63F
3sXz1GR48xBUgqdKU3KuYg7vJrPpTeSLGnquS/T+xNwRncmb6dVKt5njHobSfzDy
cis7/CUQ8RD4KytmrNDnBVubw84pcS8yICUUkZiDtuBsnU+EX3rjb872VIJNzAKU
z7dmhjZI13bmo43Uv1zX0e0RQy/WnLYmRFJm0/Vafu3c5yS2b+MSBuu8hwsCtW6+
Aj9P9xuQOYskclTMi/9NWb4CwWrtgrGnYLUxtpiIt/EjD868psXBnuSon0GOOAzX
yhA0UFRtebhbjelHTscif4s3SA7EiBPffVd63sbRofMXBKACySK71OBzT18k+LLM
Ihqy87WKC2M6Kw/i+NXrLOZaT2j0k67W4t3oNRHlWZ7FkXfdV26wDIR+e1iUmnnS
QwEKqQIHnH9pnN7i0HR65HWEzfOGk+T3a2Ol1fKqPSAaPGeJuXFEGw34RFDHp4hX
Z/NdSZGxRUI673lZ9pS8qp9wpp4=
=Wuv4
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'b576bdb1-4d67-44ae-a445-4e9f688c18b7',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//VN+HrA7DpNFjC6OyIeT4FXQUwokMgHwyLYD6gfIBuNmx
wU2Cdo5cjQdkokHorDoBa8G4yPYaQ4le4I8TW1nSlOYqcSQDtelO1P3klGIhxbbK
sZMm4+/4VDP5+B8aiwxUUZF+6/0qryXbIl6FCTLzd2nzmI5Gd56P5dYKZour4kBj
crsZQNhPfWLsay7rN2KXx2nBVN1XLgwnuWuBtFHBi/lIK6UFWip7moSJstjshKNj
shE2KRM0rHnR+o/g7df3hLEFiI78Zc92KT82TiYOhmpug0Pa/0HTDyG7SfKvQL+r
1//oBfz32A/mxKZAL+khI0TqzeUGLYdaTmuvLztmLiGCWVQGSqYYRFRIyL1gs2dS
AJKmYyxbzMadAgX/qMS3QYfZr4+oYd7PRZJHJi6KUw5RGGxQov6XyCs4/0Fl+yfQ
KuQNy/GIn+6zLdmzeymkTwJHmjAXKfMTe+2JES4ybNk1nOi7dzrMVSoHL08nfgQt
nieb9Lj19XqrKVtjoX3On3ml95CDDkJTIS9nz3Jn5/Ti1QFQHg5+j7pqFiVqULoB
bG7eXeZRClhGn4sWr4pvw+Fnay/Dphw5j+brnxKnI7882Ynq/Sy1QvGjAFACbfj0
6nYlCnmmk4660DUyF1caVqVSWadoD4GNJ41jaDwxJrS0e4g7MHf3n+AaDWVaUlnS
TQEM9qZixvMjSm0sulNdtpDDiyBGuMdOhGT2L001qFjaBY+PtzeTppOeCeCa/1zF
zvEvvmqbBnIaZaw4oAigT9JjMXrqEBAuRNAn6QXB
=cvJt
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'b736257f-d6ea-49f8-aabe-ac8e5cf30dae',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAhIhBX+yLJ9d3G0p73mMdOGE3EhmVWzVCGBpNdao81DW9
aP2EWTnQEilYfHGgKTDZppKmZHZshZkZGwfgaUMzmwGDjZJK9mnKdTxI3zE5sQIh
Dc5U6fFG8SrgjW7uhbYASyiXA1jpZ6kqiZDx6wFgtCkrFcdUzEAA7MU6UemFFBB4
NxXqd6QBOJo7tRja8GVyhhoFCvIVrB7nQaJb2skLBU0SzhR0tMu17uHCUf9agLNE
nZJ7NQkfEKc1BlurXUjRyFnwF5JO89R6xqxqJUb4+rjFbDKkLTkUnZzT2MgWyD9w
Jv8Muqm1IbrS4Gx1MVwwJtUJIBgztCm9tZoE3Pxekdh+xMqPjRCVy4G8LTNxE7lS
1csAf98IR1fvg2Y0gm7xpj6NuPhH5/Mo5wHXtEZCMHo4Gxyyx0YtDVPQ2EIhE0D+
avHuPOJKfVMeNof/Z8S5AU+FaLtGlYahfMNKQiwptH5YqW2dd5zWS33e5x2GXYr1
tz/se6kbNFgBdDIxq+KWvYEKRBeLHrh8SFfcp5w1HeAnDuwP6+uZOzFLziseqmqu
FoI85zGfJVZl66SeT05oJBSZVIuluI9Ob2VH9C+O+UXRiop22C/Ny0Y+5xuePeBO
zUYOSEiU/6jCgZwvXdA1xCjoR74RZ+yZQd9Z8kMNjdaVObmurIpo6izPE/Q6P0vS
QgEdhFMBy6vcYAEjF9JOj7xB1eBtE4oL75kq2YIzHQrnsCc/2eM+vQk4OCQubi4R
zu2ErhSBJ8tBVbZ/T+xw8SRxYQ==
=HTtJ
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'b889505b-e405-4f14-a050-c7eb2ac54e2a',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//X+l2FesEhwuAt+8tdqmsw9W7tB5ajsjqWCL3hj3q39Iv
c7uE37YPGh10AqATJYw6QzRzju77LlFKXjcg6vY8NXGJAi+6cNaHThu7B9AdwiLk
pKvbuk8prj00eUbNN0gUgY7PnYkPTMLLwSPvWp1+kE+YXs6+OWNryltA66wN/Hjf
VwYZX73YlA7949Q3y0sZWFS0tuVIP2icpn8XaWcTq9GqAdBRCw16FAVrNJQLT70h
GgTzEKclEv3JWcTJSmZ9B9dMkcysNFH7v21d6dO7zLSTM18D9WOOAq7mobUahPVC
HHE2JRPDkOCPxzTSBPXiyb3yDZw+4GPH69NDQ5aExx+cjuOOEZPl6jLzJ3SORRLt
k18vS/o/wo0/FEJsD0wgCo9mC+B8xy/RQHoS283A9bbDd+YlCrv5sbpqmeWH9vxM
Rh5WXh5NxT6+k9iEAKaC8/ydxH5A43rCHSp2tS/P4NyTGxbNwUFKTm/4u+uOQEvy
G09Y97dzWRyXZWJ/MZVhAlg6UqmLsl7yY2T1Xuz/q1b3J1oIyq/J2qFy6XbegLU1
0rbdeDMper00cISZvZZNKm/nrir22v1BPOF0gwrV9oGers7e9hvC9/+YuwiD3iYv
4HvyG+C/jnxJTg6vx5tX/n6Gw5DjjmYA5yo/tXLS2disl3RnKX672XOQHKEzhKHS
PwHo3bR9/Azv2iTq60Pnu4w138Yobhbca/r4gf3noLKJBb0gbtsn3MixeeN/XM2Q
G2uCN27y936cavmEPg4O+A==
=ZQQl
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'b8949613-a127-4353-aa6b-8333c7237281',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//SvvX171/6/3OAawF70NnL3Dn3XY4gve2r+CcxmouQct4
2AQPt6e5oP2opiIybirMYn2OwHBBy5V23JrGcBjZcZypXFXDOFf4QiQGyvjvSSMk
aLhhCtEJYe2ODDi1YUlEmdZ1uUNlW8pcgpywAMjd050SAW3B7RbLy44iZrOswhep
m7aFd0wyqJFk2UTm5hnviFaawcre09YkgdCNSKlJqj9rddTSWuBfvrO5X40Qw5eW
H/QBqsBvDl2CG+Mvx3gQWjhnocB/xoz6Pr32ySPxQuvdvVcS1PrFGOdxPgsYAwrh
4WtFz8G9H1XZft5vVZx95PUbK0EuO4S/XCl242pS571SVOGIUyJZWzYFlBle0qG9
lskH2qsyxMozQBtH0BwU4nYH8VHZuxj44mKyPDSvxmvWRYQ+hvT7FR7wJaqMesMf
xVlCpBzYRwprMYZhdPkR5SECgVmiOrZkQDJeaHOI5aDmYZ/4rNAgkp1RcT+2UwpX
Ibs9eoxftjfLuK3USAAS0VF/DHj13/L78pCZb8jBPBpLCFpKJUaKtGkey9K+0xsa
mUSGBVf2WBILfHXR/yC9sEcClvY98YAX8biDuYvc2lKAANNCoC2Vrfek+7Mzz2k3
jjp1Q6gH63yi+WRcsWNSYQo/AXznJ/IEEwhirRgOxwUyKcKb7Qt6KrcLgOk6y6zS
QwGWc+Tstf1DfA9Y85vhftp5lsuxptdOclt2eoX54sqO1ukPfUG1mVffs7f7ZpJn
GMKxcnghGXvKucDMTRiulGV5H8w=
=Cw2E
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'b9e6c09c-41ee-41f0-a02f-96f5e6149bd0',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAiDkhrVMd/hqGbFN5hvi7P7WpF+RtWsx5k5KfTTbsbpjT
TS4I3ETM2c/TfjgyGY7sbZ2411OygBxg77n8qBbyHytf8utPQ3avVWDC2lej9a61
RfM4EJOPAByibs0644hWrv6jpzKC1Em4sgva8lG8DrI9+aJEGGHVGYVghbdCUAlg
NBO0e4tg5UN3ZE7EC5kYPRW7gVZLewFoCG39yFpoTBZstV3xtTvIGGsU0qdOeAlr
WFBUaY58QGbC0fwxegwuiEC/WHOi9EROHj+4+wthVP7w00mUwmzft4I4W7tF9BPX
pujrn/CQSAryWuW7Sx56cS1s0iEwBSUO+oiqEifb1xMXarD6NGZYyU60cik+gJ0n
jlZNJJFUlchRNjQU2Rj90JN8ic16hhBYrstpVqoKZj87N2SMT42as/oOShhBI5qh
GdKqDP03z28aaolxRNCfVPMtYZzXv4YWPhXm5pOZ/OZNM0Rb5v8/HTGRCoZG54ma
ngFih2yU5jBj7sozCWd+CzdOEy2NWX6PXGGOUQuxptSvhGe0ThBPgMd3KroQuv+W
niKob3tFYXYjfDNPC3JwszTOIHPMOJ4c8+sS/nFBO1akK0Uybs3IczhKQVwSmswX
OARTuE+fo8len6R6bl/tBdcR0L3whCzQVzkhMxO3xY90a8yMnx4y1Fo6MW/ug2vS
QAG2YNQdTyiZVi8MUHHZUtRfoWyxXrCi2VbwHM0Lh6XIuot/U5qzczjgp3acp2HA
PWUN4pB0FcCP8PRNAUMrqSE=
=QpXW
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'bd7b821b-483c-4e05-a074-6270f04ae1cd',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+NiaNRDKPb0hVQ9EmaoLme6x/BfA5N8QbigodC/kITIM5
CjBtd6QhAb1ZwtjsOexb5xQZROsdi7c8tUyxV+5IO/DOSI/DOLoMcaEH8ed+aC3p
2OEu/Vk/9fVkNP9BwfQkoTwZ553tBPRinL4HLsYlhME3qmfv4VxWUyB1/aCU7Jel
2R/ka9VlCgIabCNP4heeBzRkyX4yxkuFrbfP54TpaaIDDKY/NAIZsObwiTInKGXW
3JC52ZOtW7GrzHj3DA2QvkofKaFoEYTBm5UA92ptI7JQZ2mPfvxW3AGh4WVhYUCy
OC8yWSPdNv45fryHP0R/NTA9WEzmrmQb7Gd2eENfGzH3baIFrX9u7tT7UwjEp0LW
9s9Q/SJ0WRzMyVSpZMx9tnqlLM+j2jCv0viGvVqCyQHoKNhZ+z7pt5FA9C78pw3w
RfmCGHTG/b+84+bnVl5cYk7CQsnFfFR0iYv2PnAosVy8vlDUw6SPX34CviaWWzHa
L3698ZPMuEuk7kbT+qEDTOf4JoKLDt6Y5fxd2oOfol/QVEHCNoxguDIyRLpVWN+r
YVhfeKZU300bYu17s7bg4fzHa8cQrUryrH/e7yIlFQi4I/BCWQnscU1XkM08dam0
2pE7Yr0f8Efmw0HGjuZkM2gk7G4YUfjUZnbB36tUipKRZKg9UWdkiQirNiPmHsHS
QwF0hXZW7yLNqMCOHBI8BdOedJgKpD+To4lIomFCARz/Rvr8RpS1x4vHJ35mQ9jv
wEkLRHzI5zl7oU/2r+jZRICA0No=
=snJQ
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'c15b3bcc-0f18-4da0-a520-b0e8ebf21949',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//b2YoaPA0OIuljvGX/elwuiDL/mjWHPWFQtHcmuy7hcZi
2xMeZCSdYB3ST9thX+/1/SRJ3hVgyXptiPfaT0FpVvi6wvhxTt5pNTIRGycmTx7i
yglH35UsZLyX8DLrhDkLay430zegVwKv47qd25K4ZWMCS++k5Zn+WYyczUrbl2Pl
ba6qA6QxbxZHLFaayo3sRLsfryH+AEAai45CLnkN2uXQeuVeGNln2FBDYRZVeMdN
A41QJSeK8bp8sCqkHO+a/uMq5RnGK/EN9TzC76bPyhurca27pPZtXE1jkzH0Ug4d
JpVXmg/3ffpssbdDnUqD7FfqgHNJ5n6cIaqfvm7+P356YnjvsdYqImCMmDTtks1P
u4fp/58FbrnWDTL2Pnso+8p8KxUQdqCrb/NzdhKYfiigHO30+PxmGfDemP5ukMTH
0vJmtLhMEboQ/d0a1jxpTJw5VasrJlJE4ouwTt6uWbBLB+m8TnkSORqgItJQiB5z
/ioUrOlvwC4fL6bTsJe6XES+mX20+X6WWbdUlLyz377wUNlx49wfZ/lzb9OslXJI
sPZOhl6IT9nc9RVFVqtiFd0P3eVK3bSMZRoD7ogCzeD0u+777vywHvuA0B3RuYyU
SLt0YQ0wGViqolYqlLdffSkJVBDSUWhsGGR9DJ+Sj2I7VcvdflBVDmYjnGL3kinS
QgHZEQv/P+ha8kFnNE1uFBQ0QDRo/r4j4IidJOZdm7br3E/dGnM+5yn6ui6IznUa
2H6FGQujTzBhNOpqVMhIq3lq9g==
=I5tt
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'c4154151-e455-4f69-ac77-d4be40db282d',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAjQZ3tlAGJXzGVU3JBBd8sGLbn6vd4uiYPpB8remv3FwE
giShD7f4cEcRDsvipo2zCdsFVKJC8vjbTz2bi9ppwuUtmUf56yWXHfkxQn76YLaJ
rHhoLGcyNwGtCVu8wsKRu8/akFLpDkRI/1s6Etyb3UkhF+S0dgkyqtnMMNJYPoL9
ZC/gJy4PeCYC+vI/BuLZFKQodN9MrM6/fZ9agrPwNUILCWELsN11dXLFX3nt3M8H
pdlMoF7i4RIdc6keB5edGUOUilY7+C9oRN0esEW1NCGXbEsemG37mumnRMEjAMDr
4p6d4tLN0QBLeNsuH7TurtrcWrQsLHj2w2IfeWpBMtzzIZfXaCqGJq+bZ8qhQfdA
XlshoZcuKURJXjuzAezuBmcFlCJ6cQfHCLqddV4QMlDzN6QcVgiR91W92zGIeY9s
/wGR+QnPt0uYWkSDvD5JubthbTnVayRrx5zBIcvIHm9NYIPdomQByLdFCODYUASv
ZBYWEK5gB6ZGL5shUQSrfBQaiH+KNCEyX7tZEda3PhsxbJMAgapfj7Di6NJnE7yf
91iA9G8NPn8U0ssP+IjfPUFJSkd4uKRGUt0Y731brnX3SCZAVvBa5k/Nhoe+KVsj
xPzf+4Ypyu5OocR2Mu0wv0M6diCtPYFxIxVJfL5kOXaoPwI15qS2gMnaVkP2gpXS
PgEaMhNhB/0hn4N4zegSqitC5jfn6i8LLAkBDKkp9fRM/5O3T/rU0eohrRMQ4AUh
lF8oh/08l68W5pWZk+Gf
=Mgkq
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'c9129850-a77d-4891-a6b6-b9dd0d97f030',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/8DAdZ3+NH11BhFAvk2x/cTStbYHG88vrfvnZ9oGbNjfuq
ijBiFX+9kfQV2k32c5Lf9fVG/R5yOEyuXWT4VC9qd6nh5sijwvfvoVUTxA6Mc0Fo
enpyPHjC2gE7VDMw+Njja/2eWec+uiXLXHJck3cJwY+99rfx3llqtE922IQeKso4
wZLsf0xrpB4bJc9AK99MM0m/52ZsiCj3XiZGBiQIH/q2uRWBIHr9MojDyaqaF4eV
S0vU9QmV6NaDS9BVA/F1cregiZfLWJaQWmmOAGNnfOVoblUpaWK/ha3NmCvETTgY
lmky+6OqKCeu8/hL3YgZV35NIs6QPhZPZzxa7+W9cdGhlq+OlYgTiXjW/5EV9WNh
X+QwIbN3JAP449JPy2VKkllY9DKUSS4+9gnYZzHYm2CvMgs5p00mMzV2TgYayk7H
RqiAE44cRhy407jeLXEtmflwMNDv2dZgtm+28zMK+Saa0txKGKnr1gyAUSrvusN/
B8YJ7xZFwrMFaF6EvuD3qyDLLvyKv1DTGDP89rIFw29MWGFKL+wpw/cVymDl+jmt
7rrWHm8LkyNndOQFeJl/RSm73PHCwJNRWMhyZZpOccUKVudFaR4yhErpjzu3mMDt
7RNIaszZ8g6FwX86tAShPE3lM7gSFs/DQbyz6rPBQRo5a6Q065UKwSTNrqJ79cbS
QwGT3K1oJAiKQp2xabR5lTQ5q8ke0nZUwH0kCfKQpG41AiIm4H5jGisRyLgPdV8i
nTrljdLjBYgTjemHF6FIEEvu+/w=
=5Ypq
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'c99a9206-d4f9-459f-a23b-90fd56624196',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+M2RF9+FGZGEmY3o+3aKTzvZtPZAzqIXhtPPg6DKAsFdr
1yKs0k9mJdHh5tgbtQS7z/kQVCwHvs+cSXcXVpJuiiREl/PxJcgMqqOZEEtSk37J
hvoHCrBSqBBIGuqryPsVTx0gzOrVOtLIRxZGR6hlOhEXbAOct/YWqL8dWi5Y/eoX
nsLYQZueGySu3rjNL70jDGAlTJAZkGH30wJNia0d3RbACCVbqhiiOAwPs2+y3ImG
E2K7qZQTzFkuXQyR4JgwiywIuLZ9+0Ks5pLWanhXhmmmXp9V4jx+ME8sbEUAdJCG
Ru4qbnheRjng09uJOsZKD8D8xME3v8y2aq8oGsu0jTgZIOKKtIODqsKWmZjkxaTZ
R5lS+YaSQo6jcUMiub6J4PQTd87cqZGN9mupG18iYMIvFVYfiNG35/MN38Rc92iz
2H3s1czX0P4M1fGM5+L2dpfSS3fgxJvTWyD9ToMzHGMOE5KLA1rqMo14vqYgOn3b
5XvIFkjxhXD/2/fHxNjrPoEG5/E7ygTMcnQ/uj9gV1CbyC+iaZcN2Yv+MT+g87nO
WCzgxKWBu02Att3R75CGIfeYAaAXBRWj8Aj6fRYFKbZ3FkzAv9LD9kNruUHXVJh5
YZL1l0gyTdrDXhV8m7vRYXHZKakQEUT8zimKFVQpRcsqkBQQlobrjG1KlBzFll/S
TQGbtNHR+r1UxgV0BFv/ZBDaBuia1912wH2uQBaCYln6G7AvoZmYN+TgV/owUUhf
np2zhM2VitjfvtV+93GuZKsS1uu7ZsA/qQlEn5K/
=2+2u
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'c9cbf463-866c-4319-af72-832cadc42c3f',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAh9SDI0AddCOQsiNMu8NV1FNPSQW33/Io9fmiTMgpvVta
BGyCmFkWwLCC7MrDN1DW+kH2CDVOSJNkGMbUBNgJznyOlxmA5H6SqC0ZL1hPKnCA
oF5/uOzjjpbKo592XMO/FK4GoZ5uEKir7uzf1Hlmhmv8j+Q06DWZuz4cE1CVavRd
geT+IMyUDqnXpwVCulqPjP9e3WhNSPQxr6IpSHgkPZc5z69dWb+9twBC7M0kgNGz
bBE3LoAZ2/HSGAlOWwDbVwNvfvdYr1TCDH+TKGJHv9kSfcHgeZAt4mifIVAS7jCP
DBc4YJjd+jmyUZAwWnZ9HMQRkL31CfsFM71c5MbXhVw1jKGhExRB52MKc+up6qya
DH40vrbSEhTCNw5Fgp4wWaHVHjAOMD8uyq9WxKlV8QHeVj0eESV+oFgBSbJw6dZR
aE5hXLeUMnudITmmgjOdZ5N9rxN5rDZ6QsH2UuNzsbfbC/bdwa5Sjk0xfrfEHbRv
sFCsEHXbI6VOv4bzUDw4R1IMSMreh+2ZOhHN9PtBenju3NdT36Iz0a5kcUY5Llks
DorHozxXieNMxdyciRIZqmiUb7zA6t4q1pt7ylhE/AA1dszom3gqSfsmhhweoYDg
0L5lGAl9OBNmahLfPe3vGF3s3Ra+BAq+X3ucrgeSj9JGPZ0btcLq+LX4b/pj7zDS
QAHODeybqS1LyHO8v3ve23a/TkJE2SoiibDzAqpR/BXDpp/kzc2nJyHI4GOQjxRL
hiz4fomqgiZz3ZKtOAHiVTI=
=y1p9
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'cc10abd2-6fe2-49ee-a1b8-32fb4253aa1d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//fOxMF12CAhhHgfgs02scMZ7qBWTLGTsPqeshpo5JSWhd
7a7xkeU4CrF5FsWOTiap7WG9JnrO7hy4e2rLM5/rz1uZpeAK0yckJNZHePUv0ZHg
ve9fYnHbIPba3FadHfdfVBN94qLpz3FXpnpS1E03Ag2J4m8ypq79amU44gR2cH/D
jwJW+IX9Sqddm200JmecON7yAvEH2UvQk5J6mJBuGOd4+/KdMTJ552kR8WmYnNEV
VqcOAPPVlD51+Q8DsBJ4FWbnlRRDsH2GmSNVvaOnQgK5VFdFC7CWCFqo9xMrIKNM
GbGn1ztXav7jthV2mz4d+0EQMctA07MU0XcLxNp/MchfGtPehN1VRChJKE6aDsW8
L3vcbXTgfcscKNbtwdmhOg+R7nQbFy+QYGsLPlacs/DTmHwn6n3tp+JRkWYGq/63
0MLNod9lS/EqvVORYE8Ovy6i7N0WxsSaBjxFh6k94Zns7Jbux7qEfabu41p5zgR4
U6FO0TfxQzVxkxcjRIBVWZqw5MfNBKZ1BpD4dN3VN2qEPqo4RL3u2wzMpPCGGlco
v/Vbt9LUASN+XP1abL/x+DAX1MprVSU/VsWU2vk+FFBWknjNQhnxndvkbLzsa+uc
kLi+W06ot+M1AdDz4vNJX8t/hUQVHDjfgsieQfHH5RFHzV9LBrxo2zBvDo8FibXS
QwHO3/43uAEbW2dOPYL49U+UHlPx405aw4PrChFrf5aK/8ISe5Jqg9f1wokgdLK9
4TUgMpkeut44AmbJvmA40V9gues=
=19nN
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'cc11a59a-e6f1-47a9-a161-4a5cc36807c5',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RhGhp7Oyb0K8qjIYLY6VOML4L85qDL9bCx7j14vYpfZN
70rSq2p3bjE3/bWZmvYVQTPuuI8dqkb5bplLFQgXi3583XoEZ5kCvxOp9DjaZiUH
GCKahJeR6EkYNtakbrS+6ssI9CowKYq3PlCO7MfyxTpiq3nfRtUtKPQU2h6Nt48L
rg3mAYNXeZOLLauylrp9hOdIJvj42oJZDLeSO+gPDWX73e7mJ2lIy+gcaCV47PQE
xaDF3FbeVd+Q5cDbrKiUa6vghocpBUGYBwfN0Hh/lD7GRQgSrutH4vuJTEoOk0bl
gYNRfT2K7RqblVOGpZ3iWZaztrpQMhpMdXdR6uj5KdRIEqlvqGSTVQdZrE9eRVa2
HeasOdrZyhsF2E7bydpWEIcFgiE8oBWd0AM/lIBiaxzZ/XyWGS2Bo1aujn6Bb8l+
/kb9MFjfldgh/UFlCjcpMFyPorsD22rq2tysnCHqvngwY2kdUNBfsKWtoVdOeToC
+jqiwWnCU894CCBcohunt5fsTlsMKjxyfh4X5gLsOKARqg+0TYIDPWNWZyvUcd9M
G033UHIr2TBeDwVY9c3eHgh26BfnbtCnq4T0BL9lauytN4cid+fhlh5cJABXs2gb
SLfrBmX7bHZyjbkEBUBbuqIigMph0xi4S5bH7ROIXAuQgm92mX38NfOyivWhePXS
QAFpVZmvue3MxiWpoGU1s34IY6kVOM2Ijy2MhPX9UlyvTEObvwUsNw0BrB64UDj7
dv0SOFaUNsuoyfDV7KRSXqA=
=lHzV
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'ce44ed4e-b734-43f5-a99f-c1a33a6cee4f',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAmNkGWjZwDY4tnInQvBK/PhSZeqgPk8vpCtp/z25mpnKe
9poq2B3Yk+H83YjXicv3N8hSHeEM2nQFja5b3kO325w5JYM4kD15b8eickGHeGAb
IrxEzQxhI8Gl/nN0Hi5y8ZidjZRHIYJCyi2f319ui2iaEy3Eg1NCdE7e6KXJ+qq4
yrX0/I+qLn/NPnXx6Klh7buHqXaK3DiVeZeHTVmPFRrmyo2KQCCFggt9pIGJ1vrp
h6W3VWHA95Cs0xM/R4UXahXleDWslMouLuZvNlCbTpG2dR4yGChCyGtuvPThEPP5
yg+oHcCVHrlA1krxAFyy1hPez4NRnEm6l0GgcV5/nVgE3JEdRwMYoubXonw6klKe
ITGBtKU7fNEsMxnp6f7sjrjBjSkYiMs6MR0OR2ebGv68ElobyzsHz/U/nijSBMR6
gt8Z/eXm2veKiEnmxSJzqAD1wExJc2CpLy2Vglbl8UgC5sKpj3C7jtYB+2VIbbCo
jF5nCqQ/wYjMzIJmTtF1/WcC26aDueGHBs57++GdzqXvDIvjK2EVfj+SEOueo/UD
u4hrMd/lC33vMQTAtu7NCutLk96VqKRz25u6bdMYHmAUbb1XGhecxXMRgXNqdqg1
DUCBakE+RV9bdYc+TUwth+TO5SDFdgRaCmGoHXs/Xq2WsZImohoQ6cTQTZQ/lGXS
QQG7tlGQh6JohxZ6qaB4z8j0enSmt7FC5c7sXbYZSPoBTpr/pueFT4KZB/MK5QTL
jfEjevjSqaQq3VtIPJLRmmk/
=NGQD
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'cf8d0d60-d430-4bb3-aad6-5f6e4eaf9585',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//SWXuucvlp3WczIOPIbQICcDBuZOfUcybeP2qjm7amXu/
zyJmyxmktp5zWPcjCazFvPHmRFtC4UboFsKOt9zacCOBD9JNL4Dg31KJW0UN76TI
djeCYWMfvUE5RLe5uGANiJUtSPnlPbStVxatKQUENklNTpaSEQJs524cPpsjK7a2
FhpKYSICwg45hlSOE2z8LlFhbT3IAKJR42nTyYrUyzwAPym9k6HyvxsCctZHJg9h
YG4GzWGBXUepQlQGWzJrEhhfM6T+vO+Rn0f1PeFg332eTY3xh7M8cdbFHFMO69BS
JWbn8Z1/KSflMqmlQhDHpAiFNjGk5QvUJNyzpHlRJsUdjjmCEeaPsaek3o+ZcyTh
IOEplyf5gZKiVN1FIyCmSU40MBK/ptWcfJMXCWKJlAGLIK0cyF+6+ujsqd+0pT59
BZHPaQuknL8yAdb7OqzUa/pmn9s5SDqx93BJZi1/5fvDv/u9XTA19m9sC0wUXQG/
NNw6+1MU7eL14grclbt9A/ppQuD/iJpS7vjR2mvIL1iDbFFTW+L3mXLWYdTBwstX
B0avuhIBNCxYTZRYmiCxiepOIDXfsU8vUIQXYaq8U/5eaBIqukR1AhajGXikXqHc
5yK5w08VQXlJOiZod6esDu6A/sDxjAaCIXEdugbMQqZQQlmYEVpcP32oQfLjywDS
QwHCQ1JCxjSUcGS+ojQScK6RmudmnDosptVmYO4jWGfomRV71VfrU/qubLsEDzsX
ufvNHpKqMHNd/BR9peQj1tMKpQs=
=oZ8d
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'd1b2ab02-958c-4c78-aacd-8a15ac2b37d8',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+LwhAEPGsRwEck9LVPCTejFhuKYtjBeLzk7qekUra0/YM
Njxrx6ALyhD+ADEHNWPTi/4RR3cLJXdPEQjtzR4L0/HGdBujx+XEhF3H0fxcE29y
sLZtLRCCC6EjR9Op7luULkoeoKNHtbPEqqsT9y8gpKA6nrU4Nmxk5GeTgfmoDdtP
9fGYOsb8nbNqzB9pMQGvcGtax0F/j2gxm++5ySvyq//nY2qfzcgWz+ksz76JJH6c
SwM+Dokj529GH8Ho4z7TZtK4lgSAX7cFP2yMNQUPxvXE6RpJQKKXyb9k52urzq0U
XYVnxP8FnzNtXGmnYRqTd6GjV2dRWNBYRJBPO/tH/uy6tsJ0jBCoPlPIEBSQTtuN
H2I0YocaSvnizZ9ZnynyVbzRMWV7ZWpUHcyIHC5X/J/4moFzPzSHBwmR2I86G72o
5q8mq7eCRGCKDy7b5u4nACoDWJU/Vc/BmhnoyoZRfYAmbs0n2xwTgZN8oZlRgdPu
dBatyHTqejyc3FuAMX+uFm19PM508S9UgCBa3/kFLDxeQ8xoLUy8w0ZqY5W7PVfP
VsBW1nYkvgvHPzloZuCteuAqQd9hbWgHYaYdpLz76BfmjC3C+mXwrQQBj0HAm5h7
IACSjicKncfGXoEtYOtNk0mp2AxEAMAGejkn/0E2XCpMjXzsMeYE/UJQR9l/qpXS
QQELYKfPbCIOPVYWZfFfs/pbwa1vQ82BlxtkSqJOg/SAXYcLJC/yZ6+Sn4MkVFiC
QcCQtIRjcl9wmHZarxbDTSyG
=9Sjd
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'd25448ab-a69b-4b2a-a124-78f3d1006801',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAArCAOlTGrowGgF5ba0J6Io9/LeKgnBmmzZdNfaK/3txIX
XATyZy8OFbDbMf7kddUh2VAmOtbe4O3OrsYts4Ndlx4ukYL8u/4WEctWJWRgNr/d
y+pqHQfJDHLb63ZzeReaZJd3xoqlDu5t376UiM4S5VFylj338rsJvgnhQArwfQGw
3dwHwt0MKmBvSguLnTTIYYZDG3uGVpNJp+QPumWOlpsSCPF5BYAZR26ECgqZrZ/m
606dUspLBwSjXNsTOwPiSp+9kJCNDbFbT52TZS/EGv7XDU6vuMSNWqLq2FsxuW7B
AAadmUrAx5fCzL+AYRJEf9CxtZKPpuMdTBrOyV1VWDRanoBURoFBo9Ji2kbh/FVN
WCVHAk9Iv0o2kBJAgFJpQLVecg1c2rKridosEwPQuKr+0Th/iQbQ1KIAT4fMKOTP
hN5Mvkjb6Zxpw4pz65n/mhILxeRpQJ5F1Hx6BtQa7dhbeQjUi4KoUr8woopwu47Q
fZjg3vczyFLhUD/ke84mzQ2SLNhFWLNiEQfxaxOLTfv0DPPQFte794569BKhitKA
TnJGnQtDCIPuQkvJgek3EpKDZ80QSFvjkEzs6oUn8544GYR3fc9rU16rr8huWMJv
82ObbJqeV4aRYRHyMCkN5t8Cyl46tZUKd3eG3ohD0MIKIfE66nh9ZFmokIvR9KXS
QAGRfqt+GvWPFlU0dsKFEahait1z4WZNyjQtAVvKK8vZLyoj7bXIlZ6QrP38kjom
PyG739GkDweZMs/YcxzXDv4=
=G8Q2
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'd5cdda03-9af1-4e57-a6f7-9af1e879ac5f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAjujOshwbKMQQUIPboueDY1xYnLwhG2jkSyiSoooX/M0i
1/b4NSP5DxZrXy2Wd9pgOrcElYsMkWxqSmf+Ss013Q+hfAf+8zU6XzjFQrYExGpa
hLN30EjzYEPEDqcieGeDknlVakm3pyon68T//35aoLODI25V3SjPTzDgJJ5UzMpR
K30v54ZSW3xlpTDIthAhDwPx7iE0Org2t8XjjMUf7vsRpjwXwSeFvfD94kW4uxb0
ekq5y/Ua5ZLfe8BS7Y5IhByAGV/HHjnKOvnfEIzhMKhTHufUocpLfqqGBaVXF/vg
uN/2jTTBui1I5o8sniipI9yONe4Oq5yHFYLbtpaRJt3CgKjCWC9aNIU0A1FzlRIj
8oM0FU62mk2EpI0UN7P9WXbn0Yxt+j5Dc8+nU+pJO+P+4J/B0GhTCOi8T8lMPvqm
TAKedujdv3UV79u79nWmR6UfMKRG3S35htTSPN0OUbjR4Iccij/ceYM5R1nbbhP6
p+70K5+p0f8fmXoMkkXUOMfD96GhuqmUGCEWcBemo5FNefkpUyoxnmg5F0ZR5eeB
k8CDseG2sNoyKu4+HIgSaD+THcV5GyCwnJ9h1AZ8mHMQ/wgFUdgBrcFUVm6wSLlB
c5WLfQK8EVTze+1fSx5Oat7SjyXv5ueMCsZkZUuaWc5RTAB/wYRQBRRz5ruyhF7S
QAGHDN6TgEZbslpYFTo0ocxjkCNVN/gDoCLywk4wAkWQKAMl7FqrR4q6ih8fnD8g
ikGrFszVZh57tv8k2Y+sqn0=
=HNPR
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'd9e6f959-9b09-4bd0-a2f6-77382f9e9d1c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAArnbmy778D9p67sMpa17UvS7KRpupYcs0LrCk2Sdtw/5p
0EcLtIQp+kWm2EnHdzNfA7Xo+s+wdVaBIyh91XpyjF4DdZiCf3U+R9m03Bo2ECet
OPHC1EYhmHpcO2ao7kgNb8XDxKICZx0+5SfpcPj2dscrTuSsf48XVvrHFG8xIUbs
5S3kRYadDfnpj7nVrsshSMRSYXaNgKjllUYbQdLdH4nPw94qydYJxhE1BVtDg1d/
KnlbKt3Qt1Quq45a1GDTRg8Gz3RuaxrIs17UdjOP8fdKR5EIZ++C5Yz1bJypIjCt
e/1zZ/oZ/Wn63fakshU/DScKzN6V2OGZz2lVAhtOhv2iJ6MFubFhYxaEYn1nCj+w
EyuFU1coBXfznaA6tno+FVzrBHjEqWE24C/dPe3uVU8DgwubE3CoL9yBUsTe6mc0
DtHhQi+saDdQOO3KjvCMjO8DPFzNKJgm4KesxbfBm98up3E2N0jCdAHN/8da/zKr
MgK2GTP7mgBKwcMHQ0V21k3gUpMZZVQriEWitcqFnVSITeYyMlID8y2RFA90pqsL
U6x9mCUjOLx4spRcxzsAomxW1TQdGHad6OL0hFhyFszIgbZXdMzyzvi8U+/SvZN8
4oTv/yDDb8sZ0npIxiylfPQIVRWy01x+7bVXJSisXXiafeXOr0tBJgjKEWaxkl3S
PwE+0XGOOSHHIjqjTGd/VvYZ0s1GPx4eUVw+4iltIY8S76a4LfCTnIjpfXkPIklA
eYUJNCWO/KEJfS18ycF6dQ==
=+d4Z
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'da5c8c34-f91f-487b-a4fe-7862a9af5487',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//VPMFRoCgMMZxcDFhTFT0fqFFLOqE/ApB1cMnja2i0r7c
1BkmN2MYb9j7zRl8PeryCJjDYzFWdAEvxfJ/EpbWm1JStoFLEeyOIRE6kNw3YbRv
02To2QnFJeayPK+acQVTA0w+Y3Wc2MTml8xKYwrAQV4ZkKjzIZnHIDvDEmkEn5oJ
iZUGGur3JMvxf2Qt2zIXoHgRDqIXLxNcNrFmP43j+LOxf7iWwhv636E4u6mpxZsp
corp3yvZa9jgxTAfpdbPAtUVYPqCYmHCpSgiE7r+Dqbe31lv19w4qKy3D+lIwRq1
bnnQ0nwYP3VQ4TRVXilbFNIZH06veAwjQndMOxpU64HMKpfhiAezCucLNIxnVCwv
snn9/EOBungpZ2kAucfhSqwx6tifEJ1QIREiCHsdEVwomEUvx9CdUCsWeaZpXpvL
DpouhgLiiIz0wciIpy7iYSxAj47d+xXmbh1MLETIxzzNBdlLzQpTIvC0f3ceqowe
aPVwgaem6X4jGhA59TT0FLmLYPe27P+kHbzRqvfYQ9ilKuR8ofvTNAFl3tfPa5N0
xSZO0pUfkMO1a8/BUoTXqAM6E/SOyRpRT1OpWMsJGn0wGIQLJ2YBFqsya8It1S2e
OiuXrGwfnjV4fTM2/L01l5AjimgOOQaZh91qr/lFFSylXlAhYSEXTQL7ZXuEW4jS
RAHokesUD/Zio4kDm1fMtHpZJ1LZBkcAUGI7cL7joM/Dv8aoMeq3dwim11YoTQcy
mUtcFAJJgcmasggdA8MDz7SSFRu0
=EKAh
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'db564f44-dda6-4229-acdc-f11bc961ca20',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAi4+0h0aVRnYTmfdcpIQ98FJ34xWU0KOVVeWqTQpmUZDX
kuFEuAF4RvpmkHNbvKerxmEGXfFgl8UY36DxhU6b7CfJGN4cRhOpRROwcPGadhSk
oFlULafs8NeuMYWiV1DW5QIxdCXdu23AjgMBPS9mZCtUeHWg/Y9fbV3fQMZWF47Z
8QM4jr+MnkDgkaFO9eKL2VuaEtDzmv4j1LdaH0VRJAkIlV1nf/Xgxlmy29JZWk/e
cmY9i7NH82nsxmW3CIdX60Lz3YxOB7KEJB0VcA8astyMUn03ugVql78JSIa0YVIP
Uv+FlvFfD/e/ewXlG2vhDozOvoE2wwGr8NK4HrXHVbFWApNvD9Qp9EkDdcYlg+0M
W18zk6xKCCbB30eZcpu28UV6NdM/Ll4tenaXI5x88MLQw2k81elpGkIs9hZKaBFC
0Pu7IopTP/rck9K9ehsm7fRqQ1MVJLFaylEAaopP4lRIdsDzijY8HhoLPjYaUCKo
iLtIHXQlh8FpQnaPryCQZuHntfd+yMlo8gy18mInCMEZn53AgcmQCRMuXmtq09ST
vuuKkU3SyoWNK/qsyE/5bvJjC2lwIyTedZHZ+SUzumVohNiILonaj7N43GcMBbF4
iyzm2wEFp4R5TDTxSbXtQwXpZETxhadGKy2GK6/9xPGBZ01K4Yvh2RhWoVZsKEnS
QQFxtTNVwYWLz5hNr0i/AFjwL0UhopQnuN5nmXDAFgKxbtG1hiyfdeZh1sS6CbiE
nEPl1ijZ0lOuEpjk7LgI3WRF
=Lhyd
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'de2b3658-38dd-407e-ae84-c1c3ce395e5d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAorsA6EMaU/kOVblb+XGrA1HyQmgx1Nvv+n6M9cwm7Q/i
wRkVBJ4kfBAI7pTtQHhxi8gvMZD+EE7rDJ7xHuMeD1/54JCh3UthGy2Q8unhXwFA
TbEn1Awjj6TWJp5svI90jAEHbqQ/AIQVq2nvkz0QVlWzVbrnGTgfI57bD8ADmV4r
taOYPt3syVJdRI1rztn/2L5DpwJUqRApOByjkEa2p8qONCKn8ivevFdTuUWgUueY
c7D0Usy/NwFq8F3nxlk6PJg1I23eFhZ5Wh6k0gOZg0Xjl0ikxM4+5Gsp9jGCAgpq
tbk7Z4CDsqs1uoNqS2JyQBkdyCnnKpYOGcncYZGd4s5CTYiggD6JVETzA4E8k1eI
/UXYDCoiF4IeLCEo1hJi6RdeKjZUBObDNAhK8lFmzJ8vEHmPnGr9SyxpF9SEWXj9
R7aL97BR+ae/WAWx566Gg9Ic0PFABWDNxPfCB8LoucJaV1WEeKjzx6CYUh1+3vow
hSLh7gjXsrPJKUaXx/GHOu01v9JCUS1WXHvPTpM/42Inyuvd2hVJIgxfipvBWRbI
IDuWhjp/b3+lI4bYzigGF0qst8nWjIQKU0Uj4Rd70ClC3z968bBq9csIC4lPejPp
xtcqbG7MrPrxHrAMFpKgeCfbU9SGkaPR739fBt7YKkAGKwkBZKGcVeDh2KSEhxnS
PgEHFpk4gihzLVbR+1SKdhe+ygSjUmZcIhS8LB4G1YHgyX/8mRolRRB9on3V8yA7
6TkvfZp2Cl2cbZp+qBSo
=lf5H
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'df4fed85-e399-4d39-a00b-3e097fd5199b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+IBa66mWyQ6fwqoUb4C/lU16tEaencGMsCTF7ZI1mJXtL
beeQdhUrOw+BE7ilt5zkAGQVzoIGMzoT8i+DfW/e0jXZYiQl9Vgt1kFIAMHm2Pg/
38Cwf9v9a1x8DyG9uOrKz98VSbUwDJin33MtMpT1HubY1NHcMbYEc+K0ziipxht+
NsnoU5T+Q+/VvV4OY+b9cr/i83u+OZhD7RrvAidjEpb5lZ06QxpQOdDlmZAtaoOF
kA29+wh5GwUXMX0v6V4OP1JF4Lo9vT4xUnE4zZUk4AngWRXTQAlXUgTdKsDyOgAu
YsXXc5D96kZeuEsnvSwEacGQo2TrVs0PHXI5sZhFDw868WkWhzmX9UT4Eg1b8C22
0pBv9vZRVCxlCHdqdvN8eB+4L9V4asAQzgFxA8d8ZZFsE+QJj4PPjodd//WfK3v4
R+IxHzYbH7DBY+z364fnHPm8OU2ZtxH/4TUzBRyrfHiklz1vIz1WkKqgEIgcwZ9O
oN+gIwdPSIGuOi8Dbzt1PnJrn46x3K3fMzUrPCFEEenUgsz7/8qmNYrvHdcVZTiI
HIMFX3rZsCXvE6e1gdztmOUqtBvyo3vLXnfhp1tU3ZiSLBTdukdpiprdql3yzetP
o5R+FahvDMSP7qm/QSmG0c90Pg9CzVRVdTBZPi83gPxfDQoIvlp557hkp/Jmm4PS
PgEQrjt0sMMkH4bYulJE6mIjrYm8mE5Rtw4t7ljDON0SqRq90ppcA4qyE/AJVfso
wWbtca6a3C3BpDrYlSr5
=VKjk
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'e46439d1-ccca-4bcc-a919-78a6d05e15fc',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+OqUdipcQPVku6oxomi0WZhbib/gzRiljAzpPyYz9J3uS
+DJ1Efjm69bh7AcKaV/D6mOgKbQdtJOLDwvhc+3x80CIRcLKTV7RlQMi1en74d3I
+wMpEZiV1voFGmhQktU7KQIaPHOi26NBNQeBUiKun8Z+cQZNH898UvjFRHhKMYis
KnTu53Pe3JEmWu6F+n6rtETW0m2vL0Kn/dUDazdCxKyZyCeRqdXQ1hDAQoa9nI2y
UdukHKplaCUq4pHBGTukQH1x1oIvxmFJOZp0XZwNF404uGAKRj+vYMOhpsyqCSRw
v8JBkQ50V2sXzGoBHc4w4AZv0zr/lbLhoQcDmcUesvyqiqnYNMiItYVsyo0k/Veh
U2NglLpQI4LdEIA+bspVDdRpnuUM1vz4/P+MNXbF4h2+VJgSlDJvH6LmnEh1SaDV
7RHarOpKtsjZfl5Ipmi7Ng+SX1l2yWRGPZS0Gs6pna4L7C4U5OAz0oTbc+/qmreK
/tB87E93Jo3ihVovokRXW1F4zmY5QoYYNCAm2xn42rnaLooFA5TU9rB4hq02+XYW
zZHgMTEW93TgGnpJ3kkBxs19ZoDRB6M28p72Wspii/WeNUO2YnWmQHkPC16NFJ/n
O+FWYehbHF4ycqaFCwGX+pBFtB9DekpOhXZyiIvuzjdV35OUYcVh+yZTKUq9o1fS
QQHJMeteY1nJE7Imal7OEzv9hOBVj3IxJnRXXSMFkdt2LZhlQyjQUZe89JaezVnj
A6yXayp4U4a1meDV9HauZ4HX
=V5AT
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'e66416db-af4a-4b73-a9fc-71fbb082f6d8',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+OUz7l4ofwGsb4qQSp4Y6jAmahMdFxHbM8mFiS1xbkjZ0
qEV7Zgktghfrqtpr0dkmC2A+nCwbWPpeXUQYBqJDO54c7RkjyVfpNjxm+8/qoMtT
FL6WPm+D/g+GbxIhfguJfVvR7lX/uQFdO4kL9yNmI11HvjNrH5rvUpNVYoWwWgR6
/cfyXeobkeGXlmU1hO7bSpPg+D5ac5hlVPky8X3hpNAd6JVzhSUXQ5As7Rcs84Aj
wi9IC12BbF0CV839Aj/2z0YXgQGWO7/PaXOrqZWP6G4QzMq7bzIABpP9LvJGOKzJ
dWs4uxB0+UteaK3sxagE43WNVl1WtJFmGLa5QYJ4S68ydpg9y9j8j7haxQU/VV5o
iuQFHC8DBkxNCH+/XV+m+OC7rqu94I2LLW9YBuRYCzYpis3DByyG+Q7Rr59SVO97
I8Ng1vdVaCqsx3R5SmFOSZYht6FUex6Gx3PVAJBpTa4pKcC7rTU3TmIM0/eO7sTV
dOw1HUZBXcCukTQiIzpzxk2iiXmkj5SQksoir0FhOz8xzotVBW/SEUA8WShGDulL
t7AQ8SUiFPKls72X3uk6BXvTFoU0loaNW8NFuUqNLkI5utqtnI/j5CcHkCYPBWSp
pSEJWFcEBvpEXI0doLna+s4sbMX6NUXiAXKQUmDl3LNMII1erCj7GUFdnK4Qj47S
QQGSaqBgolhZXhzGmuCXtBIpYfjztdgjECIEJdkNF9vvwgtRw/t9sXFLnQz9mQAS
hScuEgjKXfbOH77OXBjbmnYt
=jdNV
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'e6dd906a-37d6-4183-ab25-ce467e38bb86',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAoovE4CwmtwB26m7aV/t1N097FrCGPUE8Gw66Db7mDhbf
awew47zgwZ6Acg/9vsNI+hBVoCFXzRCGxSmmGnReGWX1ekr8Ax94tBCsijbI79s+
A++KuJbFP2eseKbSvGkLJ+RJ2RADbDiMfo4x3W0hLWIAWxPeddyvtCPjhKSZCPiO
/H84lUuKwA7EpJQF7YsrDkueBkCRYEvnPGvaCChM3d9IkJUu7whlAQak5Nru/8GP
A4TREPjmXjYjsQpqnUOpTS8l5NEH0kWwk4BLGI65qFcVzIuHE5YurH9cnQWsBhQl
FwtiYqys/x/u/l1PYWn2qkExiKChYVI/nNXH94RKZGwSj2YTiCzjpexHhM8vIWbD
L22ST5HShCanVAbnhc59At9DLw5Mt0btZGL66522Nf6Co+P5dukax/QNmU9OzPub
47QC0dzAPR47MmNGadm0KFHluewrfLi7tbqzN3lpzeKx+ioVvxND97XeYK1T3j2Q
llQLzSPmcLotcs7bAB5MOry2BPL2a0w6wFTSbbkWUWER2IdcU3wH2tXlAw+C/HsT
aZBdZ3oLaOqhQz0wSswOQwmDk8L6ujW+bVpvMwzBloojUOlavNs6dMp7hrtWGFmU
uM53UgYiuOueza+3Jk5BL6ZlIQQzmg4AMwNty9DgsIacoUoPlejVpliAve/QgOvS
RQEFUk7OZUkqBEDEugPtfZoHgVjHeMJSzUSsVzQ9+AYDcRV7dy6G3J38P7YN964a
i/X9iccZZugiZZVl/JqqRP2OqIPgug==
=qFGP
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'e79263ed-1f58-4831-a6ce-047754a8b284',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAArCLjS5WUDp75a2sXZo5tqUqV6Jy2zfccxigx8hjHt3ef
PkSq8vqpyH0t390PXlgTJxxRtoUjgBwHEZQT9qTXvF1VmlgcHxHWFk18WrPXvQcV
pKobeUwQaLbz1RUcfYbGUMeECj827/banMXsjvTaervTXHJKXePtKw7SWgHksKha
65Nw6KGUo5nTTn6vzu1NS8xNx+YEgzox7LhflkbDZbZ4STu7V8tK7IfdgXknLqhu
0+Kl7zFOl19rlVQfNlj79HOVVReaq4nGePdB5CUQ3F6ND7ZsFpUkw/DoKVXrBYe5
ZBKiqsDfXmRJtd5ZrGtUUpeFmi/8Jlh2ZdCUn8o3vlShgbu9YajijN7vqrpY/zhX
tCpZUFasZIxwsyfWGOjHC/RmqgiAbE7UxAJP5kg0kMKulaR8JzTviwkMNqme5Vhu
3320c6z9NU7tTdquYk7hnQKDB2bw2DOGc5CtmBulf5p1Z0wqY1X8CrbFBd5afbnu
iOvjBIa6gRyVzCdcClbhzg6BXSpuHlgBZ/aC2G+WveYtOnDd5gemLBR0oeLISVL8
1VttciswUqq/8GFHFIeXb0PkrXlXDw0BCI9enY0/Vr+bCWgcqxRZ+vrXTM8clnx/
jORKBrF8i8YHf9cCWwQxsRfS2jw/3eCF7nFuP07cumqZGDjRpuZvG5lSmjuRsefS
PgGh6wKnv/uMe8Oq6GZaGcAYuXCED+m/C1jPrsQJLCUVieciSkxtL2dlyAJzD+7C
OGuXj7av061Twk6k1nqa
=UoVh
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'e9e27b05-ed05-4c6b-a70e-fd856c5b7f80',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+IXx/XBCJM864jEpCXSaPiS5te5BSH88dqhF5SZ4sq1gk
aZ7yRI44P8Fso3uZc0eaVIc8aA1KxFAUPHgR3nN+aMM+Lkyf3O+lK4nCTFM3ATJz
D71nbcaRl5EW2HEgpEIDRkvK7do6RqsGsywpiZYT1fpi5xXvjoPrfQP5l77fjQN8
DlW/7FGa7dOYPoXLxV1Xt7ntiO4JAL1Tmc8p0OcJhw6k/3pvEfiLF9Le8/3cUWOB
VBvQqIrhoteyeK1ac6kWBmL/DBaeF3lYBFJTtv2pt+VigKE/0ljAgWOfv3dx7gji
7fGP0HuKXdxzlzvLcHPkZAIx7kg78XK77FOJw1gXLHx6GIIjXRu3rjKgoma/lZC9
NzFDsVDjmkY4uofkc+RDY4Kan09Cf2lEuaUWoA52tsx9AEzHcFOmaBnjtt7aiKm9
oKs8bANAP/dN22QYC55GGQo4i+gNa2vBcelGMfOgGxoQ9SPXtI4gUPhcBTDrOP9s
ZKhUQ55NzL1e/O0MVYT5Gps4YGo9aOCaStTzD8SxEKEJr5AgHfDwYd3QGWjgAAYk
7hZsz30zCe5iMp+Oggo2TiYtV9KbP3+50feCYEBvEbhWlkM+LB9xlqvMi8KcrX8x
tIKRgLtPsUC4BljbDHpJuQU/pZlBkKW++PFBlUJ1augBpQX/HLtKtBRyiNAKE+DS
QQHItOEjiG+lRRPVKnA94FMtCGUw+9+uB3F/GHZ6YzCg6miVIKWsNL/fNolDR5A6
qTBFio7obX+u9qfGML/7aIRN
=KE+4
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'ea6eb1ca-e377-4ee8-ae34-71814ac95c08',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//fbp37JqqAYaXaFTZfSU9rYxYYWEtpJYtpc8MqLnRBGx4
XHCn3nqCW6RxxV8CCm4MzQZn7IdhSgR41VEO3rd2dMV8+cTvGxSJwgqUFQUar3oJ
egvrQ65GpP0fkHK0E/HabciPozPOW99OtlCn/RdH7dHibM0ozHmZQ1/80xeoC6ze
jSNd8A1AvK74RqfBqV2RgeaHwChVnxQuVdEjSL2uSsdS4UbY/f45XAQrGJ2qlIun
IIe8Jj1qzYOtdBrX5v0lStRXO6pkkoVLEq0WJHSElh6+fyRgcz/dlS8hzBRqS8nL
Lk0JCCUf2js3gpcnzqxcwVJ2FZUz1gBhCf17yq+iYJdmHpb6JizcqbO058uOlRzk
gFzQW0dOVMiU0f8mYJRafH4iLEMOB44SE18r2QqO9nLgFJ7o6KMkDxNZAcFVAx9+
vnPqle057MpDOhJqYvV8GaZwXDEZWsAjSd4j5b6yjP78zEjyegoZorUVJ8EnPtJs
eqXC4DFzpvti3U0Mlc/MyiYVBppneeRZyJdd9IW6n+pmK3jD1/8wnVhKK/qzd4Qs
1sVYYp+yD6ajAT+78cWTVyjL0ARMd3Oc/WcP7ES8C4V5Lu15yDGo5W3PYpv5LYoo
i/nwNOtgjzRsWMD+QJTwAmHKZECpJeMyG3Tw0UhUXzcGwjs287kncaPS/p7T8vvS
QAFAyrl4RO2rhyYb5C2ZOkudFYpz/hANJI8+q5Xwo0CNypD8pXDZ1FJnAUf9xjbS
BgnC7sOISV5Tm64mud43MUc=
=z5bz
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'eb0db729-f5bc-4db3-a0e3-ae5135f650e9',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//buKiv2tFHrZadhiYZqgpTk4C2MFAEkGP1l75qI4GiEnK
/inGJ5At1ng37piSf6WPwr+myJksjFmQP/T2rY4KR5ZfS1uIFDESqUJzEp+gOKCL
0jciSR1sZEqdZuTkFpDveXnCfY+WUHvEQ3V1QlwDvUm4xotunyXAlWhLh+R2Eqle
lTlRZeAJSak2KpguxJkx76OPRJjeMOamTiz1FIpeJpqhXVZzAsSRrEoyJWoWXRIF
UPaOTSutNNDu6ftGj38qHDVS+pUDYcv9eEi0UUM1VDl3uUTomepPcP3CZwlOq5DN
rL6CqDXBXEUHHg46M6R/jzllmrKYn69ZB0mZq4LPYOAfUgt1CTRcTWzTuZ+E0spb
tzWwhRibLwagINAuutyTk8AIGFcAeqQ2fVqnAQ0cDuZe2H5xwCJcuCGDThIvzGf+
9HDGN+IP1kATY92OKIlGS+Oc/BzZoX6b9EExZGWcsPA/pGvE4iCIEZ+j6HSJ5rwA
ce5lfLXMAWnnVd7cMJ83sHCmQxEOkCMxbQWHe9/hOL1kpISrEZjOgjIJamXC6eXw
ZgcAlh1l5GRlkXamp8/HORxzh773lfyZAvAmdVZLiA50NaecP8entpTVKKn8TJji
Tx6JJhurxNKLFPl56BOb5x6IY+K8chlxzJuyYUIc5NZvhPjrD0CLFB6XWO44q73S
RQHYaXedh/S++hD758hpQMf5iEGmSXsmMLi6ETETYp2yn/5do8HWEqAvTuM6jb1G
BZCiTxnhscQ1Aadt2zvbfSNiqtffjg==
=VU6E
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'ecade61e-257c-4479-a6b8-3a2f908b14b2',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/+OURaEg4bex2AvJpWVxPlC5eBjylCV5yqZFhwbfa6zLlv
qDIfifumijVoSWnYeY0J6eTh9xXQfGfQIoFLEZrOB3jSGnTwtA6WvYg41/TgUeg8
PYEOOqUQHd3+vp81HbMBvzLcb4fq139CzN5PG5z12omRh1c9ZGs28KOWI1svzyvd
C2jjZd2ekFkgFRky66wd5FVIqG0sM5DPpE0ehH+scym9uEqipZ37qBKAl3lrGBcx
1sID0S+3d4lU0dMYC5f+KCjj5wdDMuaoIMp1dYcWgd7H/qC9V6O90IF+CaRXSTtH
e3HjSVpw+kwlRGCWBjXccJWauFy7DA0krX37wo/PpQzNI4385YsRTnpIqkNZceFr
PinfzkF3vVAisnpGs9oodq/2ubVJjDEHGgYQIR6HtvTUOYn9wp09ZFTddvH++FkY
tLjCXEoIdLMqUBs2Sj87kjTJiXNmmmzXsII4OBSvxNjw/6t/LyQvkQ7sULpX1Z0E
sqLs3YX7qoA8g1o0UypmnGxT29McYQlFr4VNYga0ezoQH5Ja0DSji1AncCZjTkeF
fc31x74NcuyHvRmiN2VaiFeeQs1oVRSddPi3drYEDP6WTC2yvtPHoXRx/zP7P6GO
ZI/m7/G8EUOyli03T9kiaX0fE87IdJr3vxkKOYvWJ77HyQ7ZJSxhijX2jYEOignS
RQEmiwf0lP7tmy+vyx6zl+O9ex9kjymnLHRPGhJrzTGQkv9LuSI2Ao781uw0xhtN
17dfLTqXFNy3DqjDDB3H9+Gfsb1QCw==
=xD/n
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'f1027b9d-8d8c-426a-a524-fc1a4d65d5d6',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAoKFJNikxwh3EcWcI4dJGCL+SOwml6Y9+2JVFGZMBUJyB
SDSjuhhdjEC4WX7Sosfqon0+yBGSfV7MGnWLKH+AjJaYLnQrsCbRgGE3MBn1eDwy
9LuSCuSUH6CbCJAtdQnA291j49erjipuW2RBTihpOmqq8CHS0NQqT3aKVO36iiT8
lWefLg0cFaBNxvytggfnkXhzqKWFlb2QJqCKXswzH8uUabzYQsZP7tMtlbbryPCm
6ENh8goW2KF0IzEesOgsoRTh90gRkMNbMTp2uPhex+QYhb+BUzY+P0o3r5boqoZ8
vy9KLmPNZ4xNCgkplEHK3mh5ysvI+Pn1NgVOVDQikDfFazwj64HFXhdrjyRlCgNr
TI87nAsOJyiS59cc92DoVpu/QFcdzFXU0HUjQW5sIEuswQEpdjgtdb8XMfprSPPB
FUHqIfARAWDf/w12nfnD/zKvKkhIv1hSOaE+GFCXv2sZ/4rjKU2WdG4AqIoawRW4
41a+JqYs/dxTujrQD9/ogJ+lT9mTu76BrF6UE+iJos2IcPMDrX7tBBdpiRqPxGak
NXf9L3pdRt/+TNLHpkzZ12UVxYZOa2RdiZEpM/a6HmVm1gOybP1k92sBuQvodXlt
uUaHTpHo7kLWtXq8+ywS4ijNM5/N9WZ3tzyomw49MKD8QbyHfcnwrKHl6L0GpWLS
RAFB9J+zhhOGn9J3uvVGxGDNkMZM++x+XwR/M7OpP/zM3cU1z7ZpCwP+mLFOQdTk
CD+638SYXTJ++DKzHCobCdUJ1MPa
=o1VY
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'f34ec4bc-0772-443f-a3df-11a182588534',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAksadH8bEpntGdveDI/CA51YJ/OhUMq/nxQdg690Nthln
Mqnu1nRT6Fz4M9Gyw+uVjlAcW320qbCkm5GGLdM7MF5tYfatEyQQI1s4K/pdyjat
lHYTMGFCzcrwEIBHBD01FHAT6znIPvx5iJK813RGm6F7oCCpPa/MU9MBeV9mSmXg
qTwa0byqTDNN1jVFY7f+i7rUAKXp5yir5Y5kFQsDH6RKLvF1Ais3+NgFtblZUm79
Ym8h4iMvlDRWTB3xXN8Gt3o2AsvRcKLkpxa91IrsOuzluQlHoDiIKMgZGexG6iky
2ObM73z5idut1zMzZ/Fto+ZXCYw5DITycFbYxMOFv95ucHTBOwIizGCOibH+K7Mc
uUn6kwcxFn2SN0DG5DSLto7Ui0sqhGInphezifrOVAAs7X2zjIOeetO970WRVsOa
spKnH+RPX4+mA9q81Y3GpOrTxk77qq4m+PShmmI14SlCpRg8a3nPlfmGs3bru78J
rjkZdFIJBoWloHGCGPaE0fCW0K4gQclU9jjrpMdt0YZ1uatxWUKvpyc7DPNHAQ9o
Z7K2Ga1oPuD5fUx2kWAky58MkabzZzPRL8elhr6ELz9Vja3VEE7x+Hfr90CxIJnr
wT4s0kTekVOIkJOWZSn/IbsVcMpqDed9mcBtdmpbTa/+X40+K1YNGYZ09HxwQAvS
QwEoMGTLme8RHgW9yZHNbzln4h0VZOf/6BiNozCxPJszChLbJqIuZNCF1TfjCpDv
Qx3LnAwBetS8p1Ja/HOtaHX1Ug4=
=tYd3
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'f36dc7bc-a15c-4694-aa1d-fc304fa29eef',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//QAEIcAak2+c4UKJ0W6MUd5H7hedtohI6p/6k32vl1yQM
l9zxlm5LKzj4CdvH3WApnsYCbET2iQU5/7Fde0qXUcCiMIyUkFbAT1vxws4VGMhh
e2ChNHFO8B6WcFTllyEpTLRq6/qqPvSclWflQol76c2YOWhjArZyZ3mFylW/u5zE
sg9CM1Q3mpB7TsYIaD/6VCFIi3foaI6A/NDoas4OM+VxjsjlBs4ZpqvtmQ4oa0tY
UvrZ3g+xIXH52+gqXv1V6F1T1P3nAD+AnpTKZ+jmiksabsVp14wFoC4c+gJDCt8s
ExpE7RdTuTKzHabFJIPXY4FjXv7W1Y94eW4kr9TdlpPMtL8fwsCq3lOKUs0Zy2Uo
lgA+R+4ZU3FcKDUt4peYIBKaiKA6qxODspwWcz6v0fl4FhCXPYYw1P/J8w+lYg2c
WEjbNXoBsk/Z6E9vi4GYobkfWVeQFg7YhNyu9kZHZZ4Kx0UtSIFDmSDEPtkzKQqL
SvTe2cmCqBo9hYFKbyIgfQdxe6eIwa+RKmYwtvo4iS6d0Uy844MPf2onO8t6yBBi
6vS7JF5F1GcQeuxTG/EAXZF8/WbZ1+3z37RcxQq3N19EvjSO7KXnAkQfmCIlb7Kj
DYaAc54AIOR8U3I0iPqPyKqYxdubCjmYByA6SuCYIKN71qeYtuB5pbJy27gl20LS
SQG6ak+c2G+ozq4oaXBm52Wk+QScyW6KboatWIxxIYo6pEVBv0hQxMmBGESW26kt
mc83S/4yiTxXm1A986ptGZ8QXfeHOxVoUNk=
=XLQe
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'fada06ca-910b-4a55-aa22-6ceb29268bc4',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//cMgycO2P/E8cNaoLd9UnJkIDkbpGTrOQO8KsZcK3UpAe
R9J5+ZpvvezTsEc3K7JGU0FOmi7kOL3tE4IKuWx3Bw7NY7QvDqDtaOvsiFfwcDoH
05wXMuQrgOwcaIlz7ibkDdKDyjsn4zmcj2idUD8z6JjDhl6cjpaxEE85LdTiUm4Q
qssEtgYiGuYOMc7b1qZ1cmVW5rIT5+RwbZlm8mOI7Zr9uEByikopXRmPhknpOjK1
IS2mKXxC7Aonen2ThxxfY9z31WsZUy+uXQjnI6TvXX6QisEHY5V+NX0PX9WlCrcW
XJIX91RAGdWpvZMeHtZcD7usDfGCyKP1tIp4qQ2JYtqi6I0niopsBIXbME9uEfmd
TJ4sSeeuHsitq0glpdxk8vijvOnl4+3TNTgZFZG3FPzPNBdUky7AKvljgF8U6OeT
DPN5kii6etcDyQ6f9SdXJFPCqHgb85Pm/rBTjp7tjCQbNBNgKqopPrtkH/N68dzk
FJcDAsg/LeT0zk1VtVYn0eqcNGjKUFZ2jkH+bgfhQQrIriwP7QmdSCbGgWTyIa9R
4d30N85hpIK+2YrwLXfusLLUN+51380bMO0bAD3dVhwGSU1prEz6qNNT4HCeVpWR
x+w9x1grdEfCYtz76etf1vwW2S4TQQXGlK9I0oTvyq3HfezmoDdkvbpnb2rozlnS
QQHGvqGVDBBxwUhX6lREzp4DZsivuS6gAN2WKAHZEG792xh+pIZ33A0UcQKovCzv
p/WYUbvoP9SyBB8gj8oEDzUM
=odw0
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'fe41326a-2c62-4cd7-ab12-5a0a3738046f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//USYFaf6Yv7zk4f3GKcwqUpUpc16VhLIqX8dWzQBgJ2Kc
Kjr1jjoFNgP5YYojyOGYa6ojMepf4q9dCFAAeSzDuVspkNoFB8zcyTjkb+Eux0g6
ZR1iXAuhdKvUiIJUvwnVpI/WZeKHVvHMVgyQOKQSXsVy8Nwd2L3AH/u2t7/wfJwV
JYJmmjdFfQ1i83g3NZqjgK3l+HgSu9vWrbG3Kdv7Bb/xcUHbjJb6FrYbloEnWnTt
agN6mwWlnWpHxqC+Mb1EtxdjR5LkJAHks7Tcw1JMhhhLU1ZyjlscCWmFcFy0C6Vb
octJ2vFMOzOCnXWkASOz8rfktRBzvxcLeyc4jHQ4OgZF81lOEQIzuSeTPV9mN7Bx
gEeCsIL+8B7Jh8GLS/DVf1LdatdNTLKMy4pqVksWJWIXu0Jrp6PQfWdMx5JfJNfa
DoVxZJPuWqrE5Lfd98ellMk1N92EPDiTNYLdsuEwmyL4pktJfesIjNOnP0XsfQ92
S1F0+BU4zZP8BYRQYUCNtVTwCIwT3JANT/l3pPuHazbDaj4Cc2BV0OcZz83eY5Ma
2feJYNKjSLNU3qvJcw9GIJN9AGh5xpqrF4aoSpH1EjFr0OWPPjJxs5GBJsc0C155
PAsIFGdhx8QeWOCSCKL3KsRK0dNnPgs/BRLd/SqRyL9ITmoP1IPUcrj28Zuhg/LS
QQHI7IB6ZEV0PPAxElFTX3NT9KgqJZMTZ7dDr8eEVmZhU/zVAstVbuMGQMid8IZD
UcV/FeZ8hkwYm1U3uoHmHOQT
=hycA
-----END PGP MESSAGE-----
',
			'created' => '2017-01-31 13:27:29',
			'modified' => '2017-01-31 13:27:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
	);

}
