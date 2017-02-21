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
			'id' => '03883ffc-79dc-4fc2-a101-7d764e3f0502',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAmdUPXFZPjMWjwje5cWTmwVVR0Xo/0dLEa3d0FXFo4Qaj
bZFmbQd42c1UXMFs8dWhgBbFp501PFHlFgx4dR+opcFpnYMvR1Zr64UZzfFt3hZP
QKj+HvKOZctYhfmL3wU3QGpU3M11VI2kPacIn6ao7yEJTCKpRgwg8mon8jo3IpEz
A3kcvsVtEt94bJgcbUPe/hgHCXKFwUxvnNP55T0204zOZUhvvvpR+e89qCzapvo5
vuNUeuUH5Gzckwo9MVlSN0hmCyWhPFtuVqaiPMh5hF+KrDcIfIYkbuQjvmtgS6R8
5bEqhbysNo2ah4QOSY7iRWeqpEefWKdDn9/1DUkQmxY1IUjuC9WSX+PJbMwFWdWH
z8CEd0xJ0Fd6VNtyOEoREyn67KwdihZYm68565dN07B8uZ8GenDwM84EeqoGSlBn
2mW6OrGZhCn18aXGlQ52uR1MgSbYoTZyKfj6B/tGl3FON8kTF43WuCciPoroTRHG
jMK3Nwae9Z0wJAwQ2kVQ9pswO8kiBRB/Ue8Ue2D9IbBu2xVXjcpRbu7sfm68bx4Y
pSXmB8hlRP7fKRwDyXa4i4yUqXymt/9V6IhKYPEzEi4IGlrGyvj847C9JBuNc/7s
ExRi/Gm5Y3221/E8JIpaIVzLqd13Xb+2uMY+CfEsm8kjqcfbH/2Uorjdapxq7bHS
QQHQid5EdK7npWD8Srai29G4ELH4yJLi30EMWBLEqjeUMPsZvIrLocOtEDeVZE+C
rYM4oPdmUErWkv5oVrrerlJ6
=pDYO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '06a368b0-271f-459c-a5c1-1e5c1fcb18cb',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/6A34dkFhxGHtk4NnTYxfKSBOdQvWxihDPyGUz/SfPv6UD
dc5n3EjekzWrOlqN5EB8tE95QZFKNZVZkZKb+5/vC2EKmu7jwIfcmUrmleSv3Zx6
pP0Qtxf9PrX4lfI1Nmx4Nhet+NYGwanVtBhh+xgDRdarwwy2kepf8GrdzxNk+fl1
XSzDLH+tj1pGCD9QUwm0AG4NhKnf2gJ0R2IHlmGu2h85Un52vNFJJQebIzYfBFbk
of0fXGQHQ1MJzzFOO1zRYJkDZrQq5NG/jwK3dDcCFyG/1DtpgqvIaLqlE9cbJCE9
MN9Hz4i4vvXYGs1HHGrtIyB+vfr1wz6DbpKoNqP6CuYflSPWUWwgu+uOWWmD6a0Y
UIrAv5iC9Z7TJokHFJEIohAoY8MlQFSJRljrm88XChZMXHEDciEkoankXeNWyXLT
GgpqnQmSListWuFrZCcojwmNK3iTXb4QuaYQCGbVAjr+6+TuRqdkS8fg57av/G+Q
+wSsDb7EXI6idU3E4U/M9didquY2Co/mI3M40rY2xTm8Q9ap+BB2xvhUhFl0jY/x
J4fqJ7Tl9af10QkVTfi3pxy2GlA6kFeQza8Vd56IhJaY2TYsgLnmpx0bNpTu2myR
0lCuHkxebZFLQnOF2JDn3TCfBsg45qr2oMHcCRE2dj5v7fK1eCLsnzY79eHFLvTS
QgHNSA/0bBDRMvlXd6dD4YWEjepeHL6oIpsUQG9mWlqFSnTTM085H/QexTcSWrz+
1+dIlm0ds3jG/uA27kWKCSevHg==
=848E
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0b91abfa-afe2-4733-aa71-0254a70d3a22',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAtdc+PC7fr8ZB4bDJdfVv2f3wjHi0GAvlMv14z8CM2qKr
EcgkdxJ+uxed2pycNc4OZpNfjHZTLzqM7QIg+iz4plYedsSoug4I/cqaZTj5reM1
aOmQ1YzJFi3n+7LvvvmB4+rsKLGeoZ2yI+b2KnalCHq3p2HboeC6Kb0SiDps36b/
LLx4KEBVRmm1InkLP+8pVY8x3IyStHkIIKNnJmn/WV3na8fF9WRbVYksrRC1Jrd6
9tHDEV67COczXV7OooJJESKq3yySwAt7VQSpxcZO9j0mQPMos00u5JjxZoCeqFDG
Ilv3fcrzkjQkvihfCdf/PClwQp73UWPAx0UgaG8UcBMUxg5YTaOWj75KL6dCghQw
J546CbecWN+t6WgY+9qIXqodnFGJufMhJOrqMwaUIYprsUv8zrRVX//0v6aDa45t
f9SAM8fF7Ge+fbopEPQTyrMc3fhraNGTK6xsXb9kLgVYUHUVvIZC2oqpTKGHa9Vf
55LpVy1/PVkV5uz99EfdhejfM0AMHyeO/6+PUvaB/LZYDBrdUuUkkRg35nLGtVrG
6yK85mABrm7qg/0/ffVTK7feoMtDCodPu3QP1qykGgSvbgahyA80rqYUyKUGkVNg
cqIEVd0OLMO1Akjbvajtj6N0ey2Lnph8zWMYMJ/+dCNTdCOSKNg4WtrGqJPnd2jS
RQHVVyuUjZKy1VuxCg2RYCJrL1O6sC9auLmYJolYi5hNAdy6qiDyqV1Zv1ePCmb3
gQtEBWEx3XXkyy9bmGnxl2EresUF1g==
=l+Uv
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0c292514-ec12-491b-a05e-8ec065b27f94',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//ZB8oDQ57h12rfNY2XGAhW3kguLo5d0w2UqKWN8PtjHpB
Lilg2DFwbaj6xxqOG/w5MsWenT3GzVtj90Kma/DyGav8PTyIgtPjHVIQZU11N8jj
oEpH7elrNsatHAEIboYSXJrN/yvybrQ1q4xs1qylSb5iJEoE9MypSg0iAokBOb/V
ZouA2QEvThWyfDB4h5EtowbbwMdgsPjV2aKQOuwHAZS0k98E9G+HC4iQ/q4eT7VQ
LTlOY+CFdMOz50OhFJjXzrx4MH4PErSr8KBmsxcvHzg1J59f0t+64zpc3YU02d0z
lAOyyBV17bbO2YRaT2ly8D0wBLJhOftFdIT1rARa81aDAfxeDmD5N1TZnvN7yHjq
GYcpj5nW2BOnRQRkW1nkddD8dezwVJOthDyrfLecPqG4aXYolyYg5TpheVI3TqDL
V9B+Y7o7vrF5lEPK6+7hYo86gNv4zr3oT+WtLMrTvNh7Xq4CnGPiHhfg679WKsPX
0qHyWtY6ssOT0VQyVn4AUFcv6bGmoyzxsox14hlsG15ibw699GU+GA6QSZb1Jl6X
XkHa9bAv5ra3fMqYD7SaabpuViOb/VNP7qMX4DQ6fjR34awwqlS+o5N15KWY1Fyc
FQIKkEBP9LWwzLCeJoQq3/uQjp94gk/X1CkzbdKqOv54eVEUdRGdXRQZNG4KTv3S
QwFFpQdTtZ0SNzVyyygCW80oNXuxSI9RjgOVzje5litWWk20dfeWx2bVli8T9CLT
DoFccq0HvXfS9ujbt7LgAUAAoFs=
=wctg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0e123c61-22b1-4a13-a8c2-ac61193fb236',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9FIlvwClCdVpPhBclXCd10Gj8hVCyin5duMvX4ETSxniO
ps+yG4TAGG55hq9cnUSw3E1H/9XfQHdQUJ8R/LjnKJElXm1KWy3TzapUGgYO0SFp
r8DJzFIQWdNdAQK1n9d/zIwr4XCZsxiy4mgLNM6sV4qmm+egY2mh0/yoYQyDJBz/
0ZydU9EZVdhjkLVyM6jwx0CfaBLVHjesHKndl/KsZ77UgrLmnfMlxkcm9fLJIoPE
f1sTY0LvWXRh/WeuPY0f9JzlZNLN2+fA1E/McfsnoGFhiRAzhpIuaVKel4bBCCzM
m6E2ugIWelK/AN+Wpj2K92yVHe7zqlYKD4QhyRQOyYYN+Ud2BvPofCuAxQXEzXTi
pQRn59/77rsTUyUS7W0xst3V7ZHeYjj2oI04A/t9i+OC8CWOWagN+8qv4Xy9jLtK
9aTfysWcaeBz+yk3b+4Wu/4dQ53LqabGXMAIy8+2aw0Mt82qRsHHaOWVyWBDuiRh
ZEetEBb9AuM+Zfkm0pTl/SW8DHc2tI2AdBCPe/bc4fcmuPivJtJhbPfbohL2YODP
ZCsp46C+k54XwyRCJzGJ9GO7gngGYYW1ZxH/vmsyKeBDV1BU5MTSEp2DZzcAI4Xn
OEirmn/eZL5LvTk9VvkjTEhe99E7SB80qS+FPm7m7OkWG1v1ciHLkjc0lmaGSY7S
QQF7NP2v6hJTJ54Lm3C5l6ityuZrUYfcfgMfFfXORoBkTCE5hcWTlt5fn2ZQjmbN
y6amHaEAV5TmBWBIYybr6/Ev
=sl2t
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0e3eecc9-f5f6-4a6a-afb0-4b77cbbbfdf4',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+N/Xb7I1zuo2WwlZk0iQa6kfq/0jb/RvsxSx8B9KI/cQ1
n1SQWXBlDJJ2GPRzuNZQPRLTjQV3JxTlP46l/ZSeWcYt/2pTpgL6Cg153HAYPJzw
fhI9oOlz2blvtBzIWj3Cv85Ece9DG2zkhG0q3FIHq82Atusc4OSjcJu4I9puTUln
nT0pJTl160KQn3raX1H8zAiUGXRBGlY5JZJ+4mM+EVUcN+qY2vXGCKMJN8GYvBkU
4qx2H+4W75IBEseyK+/C+vnsROB0SjJJWyylbM9xEJzM4dFf8DQCreErOtF84+/W
rwfNcD/NPB1wUC4YpcOZ1PLxVh4cro9IvJfqPdOBMb4o6tFHcc6bfALlvYQRwk3X
ofYHxTLy9Rgy7ZZenvoZCDq9ZUNUY9bkto81XSUOqdfZeK/JibJbxFNink0qzJIN
8uT8dL4wcplXTrD1Z8z3slobbrSAK5IOcOXAQXi7mTVUoB4r6iUjEpKdszC8J6KO
makUx5K9glOw4HwusA5mcfK/GsDQQU+8KqA3NNz2btYFnuhsl96m0qJGvT8FMlgH
hZLWza02RUwdpzY3bZjnn7FP2Iv4RTfxr7p/Pa1qmFPBpoTK7Ek/ayFE1T9VwD5H
5OUx+/aNJNyMxThXGFivQFegj1CFYmctu7JYM5bcvp3FUNVbG6Uv4vhr3AX1LwnS
QQEjBXeCzQSjbP9ETvxMmfW+jp2udlmeoMGqq6Wvtve4h7TpUZ5qAZm5d/kxU+BN
SXKtOEhsJtZ6+l70EDl6RmeE
=B/U7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '108a5a1b-7609-479b-ad15-09e1efc6804a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA5/heoMvCpu9JaB6vvfA94Rn6aqRAFVPJyEzEBSPrx59P
NJ8CKzPf/CPdQNqueAKQbM5nODqLNVPmptuRTZzIkqzLfJ2uqSIaVxvLNjre9vtQ
uXg7gMqPlvpmAEwpPmpa7rbRfRAkMqr7xZv2v6/aFdva168CFFTlNXc3279Jn58u
dCijibmrN8wFe1yjJ2+bZSU2H1QOK/r3xeFLZOGbAea1Q4I8m+7esHicDbYF+3Eg
u27kJDPyEGvxrSBv/Wm8VX6SUFw4UG966Sv5u8MQXJBXdHJVSEpocyMqPXPi0AYf
L5ODJWQ/DCNDEqm42FxBM98SyWp8J3bCTNRAfs6sI2z7v8NbY6r6WoluNNBDYyIx
fS1CvvgvuV/1zf8m2/D9n7dL1+0K0BOvd1xIvwcH0lo0vwrb6rOcFNWJEINfiY/G
1R4aapkXw59mc6rKPxsxAXCRHvZ8rtKRrwQY6bZlBShfG4lGfe/Hj7YQ5zMZKAlm
UT7BbOybCGTvQc6CJWlsQzI8SPK63LE67ToQHt19NOOlMx4RdC28+4wIywWmIpxf
UTIlh6RJ+gDY50Nf4PgyyaFlYMay1mjQO3jaKJKCb0uDosBWctpX0/XPCXUv4xrV
wsdLYHUJ5u40XIlsy4AGinM90irxXw6m4fQVT5nWsKqkKcGZ0kcDeDeKvIgtc3DS
UgGiuDkqLz42LUZT7garl7sQCR4TbfrZzES5x3Q2k5QgwDJWNt2mgMnbLFUOTAed
euviuX7BmNd3h9GgIEyfogz41btkBFpJgOPNrFxHDfj9PDc=
=swQT
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1097834b-6483-4a91-a657-6489898926ab',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAhpISGSxas82YsZpavpuQUW6NTht5tl56cVPf5shhXNeq
8jPwVtYnJj04VjIcPwkVLiuyKFWOnpjrVvv9Vz0n29mLjJUfrrFqwjo+cmfcj3+S
KuZxVbV8EetWVcX3QVa/ih7xatNhW6DnlYIcVNayduN7a6EsB+t5OJmxCvuMgWa0
eQI9BFxCVPjBlCYAIvMHZMTbPWc8x1UEF3MtMemUPdZFvAfisE9MxluOB4yUWiXx
8ifk2pkQ02VpUL8BkUEA2nnDpQpAWLgozv5VScO26bkH5iSt0Zm2oNnLKMrrI5LP
f5ihaLic1e5QU7+2c2HW/UA6Lp9lc57F7l4LktC9bNJDAcxID9ttULBpKs6bSmRV
1g03/lUD5w1ObZ+GhvJ7bBZ8O4CpQDjCt9WQdpayEo5A9bBohP1RyQgAJVxvnPCS
+Kz0jQ==
=XFR+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '10a655c3-7a2c-41e1-a99f-56a5185fab4c',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//d/2gZXq1Mi18WHEsABDq1/8KbTXdo16q98BECInhPaN6
87QytbcREcKt+31YymaIisnfoi30D9h8ZrtYh0ht4R3iLZEPYsAsrBSNooEQs4bz
3CvIk/f5Fj4cA0uSghPxM6lHWSnrZSKoUgUPJBSOHWzgbrlZ8VCO+vRpePimhdG9
1UOnwxuFlBJu1s5+wOym10SP7Fh/pcGvRdyRLN/YFsqG/zOl8DLop//jGFa/gukO
uibctjsDXsfkF5B079eCPHFFapXq5rFW0/KSrgfI5BkhDuSZbrtM2fax1uHwir5G
J0rjdwoc55ndB/tMAtkqZpCAzJTadRFq/IpMGbk4rgBaYv9wMUnZfkq5ZNbgPako
GYKSZxhcwfu1TZrXLrPFWaFbb4Ya30mvEdDHTUY4aJnJVKFwhhGJZ6ihZW7PzFIS
E63dJ+cnx5Dby7VQyT9zCqS6Ngly//J2pf4PGe97+HHutaHKmkR4JYrON6/45mth
mCYZd6I7hfqleyJxQRSa8na+e3igjBjnt8MCrcsZFxd7iV5Nl8qh6v4MgE7wTDgO
tmS5LGVzlTmKlC6tXEHbGnb5yUnrEAZ25Pfmf2atjhokQ5TaL7cpKJXkIuUytc5v
kTT8C2W5NaqMmMhLcNEQhTytHb9jrazK9BNSLDQuoF9nQJpIUuEYk4BUo50ihJDS
QwE8mPgyIqvRTbKlidV1/nkrfvzcCcP8eOFGNLy01hZEYC221/GONcmVAmsM/Q4d
Mha3zSxZxmbeKJ4VD+Ge0P9JXMo=
=2Qr3
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '13183f65-2208-41df-a991-0b448857a360',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAmLkZQQGCR4Zay0CaPdyiYSHcS9b+DPV3ESlqqW9fhFWt
N6LdahFryoQJCxb9gqhydZM2iDp/60Amkl2mhUc6+y5lwTlRKo/jH/TRUqudfIwx
zmHIlqK6c9M2Pdsm04PHmQ/psW9vL1qko37+OLc7Y05Qft8Vlp1KD0KS0pZqD4NH
b+lQ5u3c8+3v9EM7cMGoNMzSZxt8GJ+cNfXGDNh+V7C5tWs4G3IYkRGlh73t2Lqx
j2iWQ/Lgr5ewWJ45svNnms5RjNtkBHQ+BH3LgufGprQbA8ZbhwD/t5eSejAtvuCV
VB25UDfQEEFZ7oj1hAkFaI9VocOoGHUrFalv61VffQEqbdlgoUvkrLFGB4120NEV
btvy6CA+TZyMa81a8V8fRmFLOxD3AIm5WrZkmB2gcxSWWBxdvr+gxcPK7RG7jVIg
aw4Bu6mVFXykTs/XUfc89TASfVtx0M9n1Y17vldWninCuGpiruJyHoPGADA24bD8
CUj+zaLkaqNSV1zBGIOim7LSVWVlRcuyNspJ4YZuMBeZjla2RlcxwbYQRluHztt3
7BYdFD59dywPxFRjK7lygJmozT6218u1qk1ebDMMrWdVLELt6UiETI4kp+Lb62qg
b25gdyIaFi0TcOoQvMLlg7SkRFPG/E3KY7d44/TlV7ENqxpDnogOPx+UyflVZ73S
QwEolseuu9TuvmXndhRofA1parpA/7bATDW4xgbyr3ps0AUCRU4jdipK+f8X8is+
t1ml6CqfbntDCFgmU/QBqQEekBw=
=NuaB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15d9b621-042b-4609-a7c3-86afafa2b759',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//WSxaH7nvJie6SSfI0VHbQ6E5ZcoaZKqUENj9jJzexyOX
5Bvmjh89W3DinHN2Bd+q5so036Jcix78Ehfc7wAgiNSAkmoxawR7rCJdgR96afDS
Xj+CELk253YqCf7m89inXrHDP30lUR8iInf7e4V0wUKbmJuBe2Qe/gHKwIRFxTTt
7MwlNH5aIJcFSwGhRXyE4fnSghdnBfVrIIINShvoBfbPw54lM6sYTkldjzvmaBLf
cO6ZdBtsxaYoWFznP9kBufmqM0o0n91Vhmp4gQSP3PSnYAnGAndwyCdWDwpRs11a
uV3OrJXbH9zzOokXR/B7bymBexwnlGIlGkpBEdXHGL4CX56oOpciYPXO04Io4/Px
42Omeq2uQge4vzn6PkZJQ8EqZf1qkzVNEAgU+gnro2irl7oJyyi3NUMBCSTmeQ+C
VB/8cdG13ppIYY4QPkXwHvkIX3RjZyDWZWeZGAcHyuyn+hhNBLw3C8CmpskHJc7N
pNA6dpjigEKYpKn8ZaPBXRrc3JCtHjVQFCpwu3wagqxlEGJJFHrF/UiLqgWtqOUm
7OlL1E6y3FyhEn3/srnpXfj5lK9DnaRAgViOk6UbQ0Xv6RlYKM0sXcSqBmZ2B2YE
v9z/jtKoO4VSGgjMhJvMKh6CBWLS4U2kDbrKvqCfDEvVroqC+v7l5TrSdKn8LUvS
UgFWzjdjioSbo3E1fxCL08yYOYITKaMSxWWOh1y/aKyd0ysz/ZgbZEDNecraMqfX
SE52zv5du89JXS8RQL6Dw2fsdKxYT8WMPcW7R8Fts/icu4c=
=xcUW
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15fe218d-5182-4fc5-ab2d-f7dad23cd052',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//YOROkdiq8J+PWWEp6J2WWJzBIV9PgVuXsrIefEPYFRnj
K+5FCYFyUxciozsHLm734GpI5kEjP2KeTCWa7lxcmFBMO9nZtvmqMgsaExan5KJu
fyLN95MV9n/+JwYcStkxx7JlRD1kkJd3zbMlq0RvW8jMZ4AT808AWaMmp146iI0T
4HnNcNbGNMkSo4cDkXtft0nY+MimKIQ+HzrbHCc0tyPnAf0srbj3/1hSxTwLXePy
/7yOiLTWOzMvKLUKaEr37ZyAklcDX98ri3ylvZvz0i3AkG/iamfKnCm9plDHzMPB
fm7PKlos98KRAciPWRGPL+17C0J5cI34tuAsvbwNAVoNRnvh+kqJlBEAdEcrfivH
/tqdwx+RRASGoUVkwLw6qDJy6ZZOUIp3jszfd9pGOclJLtmX2EXVtTxC6eKQ13EO
w5ifwtx3oAH4JetJG0nMBuRh2j6Z/bTlzoiu/QbcfGh6iU02i8duJgDH8bezOrqS
fwQWxGWKzcs7OupkjJDyA8EKIa2voatE+Pd6hYp7mo7DG3V470IEGFn5/2tsuth8
XWXrVCsEX8bRffulfzJRxY4TPmbZCW3DqdGt+c9B3NJR+ZmyCvoJwrTUQ9prjwCj
jTMoKiNeh6rs8oPLppZSF7O1NsovWyok0iiQZURFpd+WCcSXGh09klTniINq0k7S
PgEEM2RVjrHQ1h7qESKA3KZkSV/mnFagoZzo/Hj3jGWya0Ry0VED1w7+jQELzzkE
Pm5IfUcGVpY4Kl4Xw+yi
=ZRdk
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '160d010a-9639-4332-ab9d-1616998e0fe5',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAjkDctCS0ShY7fSMM4RUJ3bISuxHNEIbVG6MlH6CBmjRy
ty8RUwxJhGsG8E6IaogFUSH60tL58aiL76JcHaP7GECr/M3xXbrOFRgTqxhSSB5y
JZYB3+rIi6bx1jFmc5HkWwaAfDtnydeG493V8znRRAIA4n5Wr06bjhnVx32ba64c
SDNowAyRbL/ynWMUQvtW7twvjQ2sMd8YEaCtRRMVGns0Y/XKiUu4zwr2hlUo0uMn
l/Y+9/YcVD7TEkiYS0x+CrnO3kYlMuKC6DlFyIRDTEguXSSeqwZrbXM52NCZicMc
30aQAnEwZPYkkNkllD0kMIY7Uuk2/P4BPIg3dypsUdJBAbdR9Pz0zoacP7j/kz21
D3gbs/+kvYOL/xvs/kh4I+chgeC6HXR7eFMK9vNaW4vKfS298UPxPM5xIBjmQOXB
500=
=MxqH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1809e17d-b264-4a1b-a87c-097cdea3570c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//YaAhyMfcwSMvFhZMoD+Ps8mdHEWo/ZzTRB5PnzpmoC3d
bvBHPMc0dcGfLJyLgviHEwOn2SbBtYG+eyBy5pUNIAjSaTAS02+85z3uNlN/iRWQ
anIv+zNhRzuZWMjVkNyXhkhLigFWqkvlz2odMWte2uYwbK2sFL6WMJjr7c7MMLNW
zNlsF+oIqowsWUA8uTtRiz2iaLe3Oc1M9VQk31ytnpjhFUKjPloS6rC9/PISoonv
cXPSZE36NnnRJ6aNgX4YMjZWZmFga/kdCLSJ9zI43Eq3OV2keSJ9GtyD44MCTT65
wq4uvE6oa0ZhhV8hZidBv7i0SnWOSJljcZ9/IJknUMl2BsCWQ65x8yfV7osobGhU
06xuKqXTGasrD5q2KHSScTH1QLB0F0bcHyY3gROvhQSgCDZoK3+K/gzDWb4RQoo7
q5n17+dVVftGtyYfWneNupmPaVOKhaWHRABxUpj4fT7ze7RbNFG158sg+yEaQiMa
VqwDyQE/M5Vq7HfVHOSMKZ1m26LYtfHe9b+YZkgaUszZEPYSNqmTha6xasUQ06uO
Tcq2bX4n+GieC7w7cY42fH+m4kiL5c3Fc/aX+4iZagoglw0PB3Z1+vnpII6bEL/b
X2GV4kEOOsg3R1ZZomokqUJcl9LPLRWR7lbckieH3NFtnqC4BdWXwa3cyCv2ijTS
UgGReSS2Y/26TZD10q7D2s4Iv5Xazez6LYnX6Z4/VJdLVZw/eCSHOZYKYOabK8vE
Vylo7fMHHHi5RsKAUtFqMRlCGizwquMD35e2MAbLR11BaiA=
=ytdT
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1993c559-3cb1-42b7-a011-e8e7880258de',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAm+gARHAuHro1eK5a2ULle+tODKTVZwj8Q32/4ZWQtbCJ
sDC5TZON29SN9fjTlid3Io7wZRbwVwap4GMIzyseBmksJEerCR0o1I3egtDiFC03
xWzfK0lY2JjqOcnrNFH/jvsLYjsXpOQGjOwppysy0LnM+78HnbVooxyum+4/IAXw
ZKIQE9kaQ5jyDCao5+fll2nTeYNRotTmgkfpXMBa/VbB/ca0Hk2vTes3N3kYehaP
OrIp/SnMskfxyaypPviiiIKcDqsTMySqL+5z+4cLTR5S5mKlHN8tzNatIkzQDmUy
es4eb7siDlGmH8FIqs13yhel6oyQSh4fBJhAMSIv4WvPgBBytZLLG0R2y7g4lAIT
AmepA9v4HgjFKY7QSzuJFKgFUXqksr7axqxnX8DKR+aZcZUIHg7emd5XCr8zG5yQ
rjEIGgvKuj9mXfkmfOagKfO8OM47UyEX7syRKxVP7JXO0BE+vMMKsRiwLK48Ruyl
u/D0CAZbO2P0rJYA2yfhEHt+j0dC3RFqbov5zujqyuTLmqB2ZhrFjjV3o4kKZUK9
kpRQjxskwZkCd3sYJLZdbwkTyfjG2FGUl320lLEPNrV6LnN4soZChLFtAilFZDz2
4bqw6obJPCVE4y7E2Xb2735Rdx5iOqlDVqQ6j83U/C/CH7V2osy+DoCu35uCXB/S
UgENeK91jZVqav9rY2wpGiVyHxAZAlwSDTi7Pj+gfnlWnCRqHp9ek+mdR8VvgC8T
GO34AU1Du8HUKB+O6bISyU2TAvkHAkVJaT33E30sEPrnj5I=
=BKgq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1a076fc8-cd21-426b-a8b8-376d1e6707b0',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9Ge5iU0AHBuDIGa+kZWvdkFFBFUSJLyi8T9WNLwN8LW+w
5Trc08NQQ0dzeMXsEapclrRn7ONZUP2h0NI4yQxr2CfJtLK9mvIW3yDkoIm32TI1
l61ClGweWDyCE6+v13BnvYYnz/4PWVQFGtrlNt9lnZ9Tk+IceGRYJSEDgyvyTpIE
vFR1BEKLOXp5GaHWqJe2EoNxEEJjFejiTTKiintt2EWlQNRx3LzaoKexbzt9SBsx
Vs4Zz9OMKZlL+BXGXBesG39HQ8N8mLNKvxMNAfhIeeOmLDZLPbrtwWIsOKi3x+h9
L6DJCqREG5DYifeBiby+2tzv2tridlwqle4MmFMI43Gt+ueAjtO7bCL9JOyO7M3/
g3pk4w1QRHJ1IEh7fgPh5UyT0H6+tXg2fWDP1JfbL+ovMGWAjXOMaVNy9Hn3PXb2
BoJYCX/kxJSzib8alkfiu9Gl4wAaEn4FceojFrh3ZldlFV5aQPtgvTPe2xvg1NFg
BTtQkLPoEf9weRzuvzzJVJva/v2MVpFA+n6MF//ch3xerc3AOPW/FxWV735ezExS
TyiK1v26Rz2mcApA6ppzb3B+ySIpr6y6IvsRbHLdwwwgESHsXjvhaFiLYeyNWL2O
+PKaLjwFWjl0dg8URO5ZKcxOreEBN/GBVlHmfm39DhC/ryeb8vlnxJfnXY2/2bnS
QQECm1ZC1s2yrqcFvXyDSjR/eKL2VHrJmmZduLjq/G7Wa/gfPojPt+JBYiRU8HKQ
y7RgyQocx8PwYUvAon/qlhSw
=uMOT
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1a668199-4ccf-4957-a41b-0854aa609cc0',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8CSloCCjKsxFCbD//qEOLkq6Dq8iwhFcA5GIj6ilkcHql
1qPqpd/6AXswcpBKqvNe8cJU3NcTDLx01hLu1UDFCwCzenIkIBZXHUhsN/npnutw
wDV4+rsJHPPFUWRdJTyLp17CL/dK/HFhDRUIqFl7wkg2LgM1g21wUL8ctY3mh+OH
elZtCsFcWB1jtckpDvk7DxIDXdpTUMb6/p9CX/zH6PHfMP24GczJGAS8760l+EOl
QNZyFhU7OLlB/ry4tuQzz0sMDquglebH+ST0VpurtcJ+tPRvaxrIMdTbCYYkDgX2
+RIBaB4fhratTwM6sapIyTlPTqicgDMXMKTh9wP/Xm4MzC56KO6DiFg77nSLPc6V
+7LBPcLUg3ZlmgLHSYgxQtkjg4iDvix5MhzKkwpuN4dk47jFuiOg484/X64399j+
jV0PG9ZIvpgQbbJSsnfFbRIaRMbM/PLho+CykOL+e/GMLhulCSr51VZ4UHIElRqe
dySB1AkhlFHW7IlOqlB0A4q3LuwvEgKc+NO8ik+sRLDs0pVf5DCmQTlM1YNKgw7O
0eNBLEdxP1uDFtrwJb/sJeGa0vcAttX+d1pcAzWBwIc8rpz70zybd11xSobreycP
DAyG53rUO+GpjeHG6u5BfUQvkiPsNiwl1uTtlXd4FyZ6BZ5emGWQ4kgCI5C6sKHS
RAHCPHrfjqJJgp2tus+B0Kgf+REc/pOVmzKiwv5OEBUE2CjQS6f8DqPeNCFVwzAZ
AfqggDvWiqEblz6UrZxdlbtU2KAk
=6huq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1c0a2eac-f5ec-4828-a374-bb09e603047c',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQILAxkA6B9Z4y2kAQ/4o+fryAOz43IU4ZPXzC1trHWJ4FoYKJLXaBwZctD/HNnj
nt/w2XNk8Bv9b3zvlxMHCizlmuctSmGt+DeF7En3h0bdu3Q06Mb8uWSW4qEzbcKX
SvJqMe7Ryqvfy0GyuTlchMYvy0xKeXyiCbmiFT6XtkwQiBJ5A2H9brnoWGfoi7XY
sIfR7Gz8P1H4ElWwnp6XAjYWApJdiKENlc4RdJzrwBEUjgolJRrn2MR+CsAijkuR
rR/HsHg+SmWg5ozLPjrZsJ/UuUqGOpW6mjevl8TSKQy47BOyiYEl9a+tVpqafNBn
xqqVpEWcHd2PnDDx7VhYma3/TsUObCDJQapI1nOUoW3hVc68zbdSxesW2bZ2Kz5p
PW+AEKpwuLJwPmQ6a0cnbfbufeY+1sD1tunAFXZ0zJhqr+tLpKGqn/2Hq6CaV5YY
hkNXbZSkQyYlkN2lEIB7ApTb61NXoPY/KG00cG0OV76TTHChIm+RKuUbptGvCc3s
wstRJ9eYO52qelKZaju7YrsBQWvAz7QlLucX1jsB0e1YjYqtsduoiZQC/eA5vdEI
YiBAqKxYn18udCqIVck/YZyJ5UpH417KA352WtIkvGMXcALO+AJqtwqsPjqe61wV
ZnNKAkfOfdum0stIltz0PxXW4KaU6uvHSk8RS0unhpwnsdrOV61nvqpi17f/ztJD
AQpowFmyA52q84R1yniRixStJKKMVF199EFX+sX27RaTq4IqUhsxvO3Ed2ooCOQy
cREJiGSeb03l31gZbNq8/pCc7Q==
=8VMo
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1dd65787-4596-4cea-ad75-2466ff9e280a',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/cZt+pLca621CpQldwTLdkRm6vhVbp9dqnUy0Wp1KNeqd
ztv4DPG314g7Q/QPPfuY6Pqzxa/zQB6fW4DcqTd+TX4Tnl+jZbpkw8EhxzBo/bv0
nzBsAr+sbRG0JQxWZ6s8KhM0CinHfYdtqiHOse7xn9YUpy/fbts4ZFEDCCcS18sk
hm0mPybBSpbAfOlcNrXoWGeSkL4dtovcLZqCp+GIa9O+DUQOJrxR8jGMxaCf1C5K
BFoYZn4RmAvNEaJhCCheZTRC1Pz7NjsKC29GIhFSYGGxjVr69PGmK6C99yM3Fm2Z
+ZEPc4NTFA4xKcq2eDJTDJrxjVI3aV01UobXLGaBb9JBAavxzUKh1MD6fjEGVsOt
UI2wxsWgo2B65nZBuBJDiflNzZvSbhYyPyc4aOYAZ1vly+7ZP/U25z9IA0Bgy6G3
stk=
=It64
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '209830eb-ba37-4807-a467-1bdd9d20f310',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAoJmTgG+043ZWAOW+fD4+eUaVBA9n6elkvHKcxYIVbRN0
JRMBPzTtXLEHLFzPcq3S5YS45neTQIEDfJt0srLjCPGTKU54IwrR7oJPd6N+Amhv
v07rGZ7j9MrwUj0DqTnmybGM4MvKlgfKCl0ePW7cRZhDbnPY8tuMZqu5+ETbkizQ
WMqzmDFzO7wy0piYJ8uqxeKZcftXeAxi+V8vlwWLBZKMQp9o4fFcESwjDLmWLz3s
G02/rpM4jJcunOY45f+CFAuxXPmcYZ9oSDZ9Ivg5psjrHUuc6uaHlv4D84yAHyjz
vKiq1aDD3hgeWccwnL3WP6pdQYcLV2HyisIpiDY4YEf80rsF8ZOwO2jjZIlpXGjL
DPt9IqmftkiXtIZ21olloQ/mZh++xQvODu+TVgJKeqZgOHYpihLcV3VYliZoQNnC
3Yf2IVG/HvZt3h6JgkrpvurUSaLaWfD45qxN7q2uQLtfPTQWDsYIaiU5mpRcBJMu
apMsOVhbPOBbo6xnSAyQdPiu9J1SslkxLHO3LpsehSQTydAcOYS8EV+ZW0qlnWOS
QHOJhD04fCe8PgnWJyGfa4pD+yLwDkXUKtueRlzi3sT7Z7R1TiTRT6LXtKoMGBs4
sDD6m86LlFXZkssAIs2JN7J45wY3FOcL0gzjlFkaaoqeqSUhA0kSS6ajhkl8nJ/S
QQEaQFuEXSn6qV5ejTO6hwltqrw/kPJiC8Td1unPUerdDp8bFAMAeEaAtQU03GgA
piMuc4iXK4WrUJyUOYaFMVqR
=Vz0x
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2270fafa-fa85-47f4-a0e8-a92cb4d3c8ad',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//TPZwI3fWa5DyHsxy3+pXMhwzEGcBL/ZMTENfgyPLHm2e
Moh6jlsZsSTvZUKKkgr7L1bF7FgFaumH61K7xE1tSmqSPVLkGQ64vbOf7DKNzqdr
SR4ANwtwvMhoylQB23h1bnrfE9Akdqlv+5KYPS25r8J2pGRomCCm/4fkbtTZn7qb
IWwEZxLypWJONcKbkSyrftvlRjobMOZ4+Exi+tRdV4sESrZPEIBql6X1xd2V6zll
c7elTRtGF+g3Us57+aSJBmuDmF48EcNkiOQ+pQ7dmlBbTFU3lky5CMr+TIPBaiUu
lodp1j3NwKs0cwFHvoZZ8QBc0BlxEQdHBaQeXdAtJBbvpwR/dL/IYgNaNFCpzTYa
vX0E6njdluGsCRu86kVDTXjVryXGu21e8w+aXH/CCqlCO3ForK5vDjuX5YPPQ5BC
C3wHjymLTnFNx5DuDo2d14YXt5sRomYTL2Wxh7JWNsEQ9gEuzjKccOAFln14s7Wv
ZNxJB5ZGv/u2SHMlhsbDEDpdUCgJBnNwBAagk+iB5nxhxWurD1W+KQ1JfvMk25Vm
VIEF6QxgHrj1wUxNyMqCWuPkvD5813ol6ZKmBHLRjpL0uQ0Db6+GTkvhtwZiglfP
B3VOTg5FokNVivROhBeqPBaIL2KaLs1A4sPN+gaHacWmEwkL0axGO7C4KjdiMaDS
QwFQ29ErIkSI/Y7N4SJc+o657PPQSGoxNdyNl3KzBz+irnBn4ZGk1Wxk/M9lTcyQ
C5iHP3cji3hirByz5Z9T0djiKd8=
=LAfL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '237d1adf-c004-4840-a7a7-9b75667847db',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/T3LqUiHEqWkN0qQrmymyY2T1PxrDfu/yXJID+HZ5KzgX
f1wF+C8xm4bMUOt9j8Rs0Xl52tgFJqtoXqwqIaIEvRI4lGX9j/4a9wf7FPQcCein
dcWn901TA8DjS6NUCkoh0JlvP1/9rCSWzLtU0Q+296MHmwob42KQzB31rKrRl8wP
+NeWf+yvUlmWcVJukg5R7X8MLIse9GgHphEucYcXK36Ig4rZ4d/3tBRs/iY5d+oK
ubk4o7VpaR8KoQbJg8snntErjFPg3PBEU+kwAQ44ziY8zJYv3udgutFEG1kZ+5Qj
qS1qRuCq/ygNyQW0GYuQDP9zOYfDCWMpqYFmCuvt+NJDAflVzf4W3t+/UACoO6BE
S5Dni+DQm1YP3qRhw7o+yDTrT3JDkxuTxUvcZJmqryzfh046Aho84br8vY+gd8CR
Aoj/uw==
=5pQ/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '27f8eb7c-c437-4eb0-aeae-5f1c3058c2b0',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAr7PFB9Yb4foe3e8g8ouhFZPlRFI/Bkvr5IDI4rJzrsLz
FMyjrpjFXLkPNvYc7epj7fMKX20bkd/zgXuE40U85XghgeSMNbWx55cSclmryQNo
r7iOaX5pVUxGqv9LwcSWQmabNUONMVwFpD4TbSydJ1+U+4WPlkKFST/q5lp8F+CD
hNIG5gNuCgkw0dgoNnRmhMU9Hu5jzze6qrK7Yjj0shkH4QJV8wYoDnfkzaohnK2O
dI0cspEmSobHcvoO3D3cW/gUzmteZbbdA+mDCkzvyU1KU1PVM+4mnn/1GinSHLTW
aUPGEpEQyH9M47NF+leeOpHoAt0IpMcjh5RFCCYTttU6sWMUdHOwx/7tx9qBI5i9
E84wH0oJAluNLuzyIZpx7zBbuWy0+hLbOW4HpE4Z1FeXAbpaBPc+RrTpUGrSAwVz
KV4qEHSeZwKcZoJCPyQq9gX2JBVKtN2vAcNk21GN2vJhs3Pn4b9XbvjSRmU8pUrz
czbEWi3MzkEbJ0Ft0Ruo48TXUcuuNSmQqFBw381//DmGcR3J3kO4NiXNKSMAAaw6
2dsaoNcQNbiKW06U6n2zlEwg37Jmq1PBXX6+tAg0rvwfkVXeEsmHjkur8cV2pnNS
97BvNxPb0jgJW/oaxigdiaHXS2x2sMJN+BLM74bfbS8v9ZUxrhSJ1ctr1HvvX/XS
PwFaaMRgsTsHv1GWVtV3RibPLSOt44UAK86/CxM82X5BjtKExS5GssuZd6VkFajT
77JTkD2aP6jKXlXrl+oqLA==
=ha6r
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '282303a6-5a7f-4709-a602-25531130dbed',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9H4IuRl0aG2jcPIbxQf2bZqIr9picVrdY4lCF/vBM4rLu
jSYKiXVjhOWw9LPybktOnM2F1ETJ/MLEB4Hi7wD9G4YSSC9X00mv0CkSSElZ8y+9
iON6EL8toeY5MT+TTioNQghIDTKgmucZirJq2xqqmPefRnRIzF9tnr1zltJJz1bW
1+leGJfjX/YUU//Gc4vNg7WiGa+oaix1xDoLiEIow30uZSNyt9DAcfHoTXnBQFNF
z8DIi7zxG6KpCbmrpfoUmdYhBJNMfjd7pkJtO+n9xY4KeDM0f64+Pa1LkdROp/Nq
DtoY1F4VxnRJWIl2WuGluF5rUcXOA11q5KGAM7F4f4ZgpedIXi2nZRGhi9Ugs5Du
IQ1W8ghrlgdFQRePDksMJjXIapqoXu019xKAWQszLGtP9q4yLALqXA6KeId2zlH9
x9RET/ptBa2+CxipqIkuS4JilQ5BJe4tQYF8vePdPCY6K/nU8pk0D8iQyGXVXzpx
MX902/CCRAgXxIhJNVQcXcwbHz0r63d/cgJXLaNzd0hDGSU/HRuXE5bRNzbH8uED
1GMyXVBlwZkfoSzUAIWCI8w4PGgg0hcbUOCEn3F8ulA4RY9EfyQw/qEMX5cYoOuh
kj9Sdo9wH7G6Oi7fBBO6VM6O+4M97G+3AOorfNFdrx7B7QtChcPporkfKsKPjrXS
QAG05r5qwaTvGAkk1iyj377nH/dpo4x9V5pSkFUkYO07pM6okGOB5GOxbfghpP9R
fk912dpqs3vgfQ3SvUkEP8E=
=KeLZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2bee12ae-3a15-4916-a937-347fd321b4e0',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/Xlh8Nw9vx0KMoxGqrPVmpXa/m5ILg8cYck5IsnM4fANV
Y8fpqhfCK6IgF6Yq4qyojwoAg3mMTBjyq3tK9INqWpGAL2gt8D7Fp9gQuYRt8/n6
JGozKHQpHoaEE1fJ9yywJ9IhaH7hYfBkIgZ55YLMb4lPbAL/UXht+pAGd+fW5Ggo
wz5rA6lNpdDYemarkOCywvhGLHb37YIW1EWwKXSVW76XEJDyOlsuy9RPMJ6QzZU1
0+t5jo3vkoRiGjNMbdDsDUql7pYmxcpYXXi0nmzbtNCgEVRBBeBUxT55FoypSomm
aCmKz7uRL9zr3xiu5NzzpQDyRrQ5hg//kxQUzMTVidI/AUwwOb7bsD7mlfDBUx6d
6c6rahoaISGx0D/MY2ZpC9kv51e1EPU+wEjpqsm8FxfwQcgYXIqmnv94SljyEPeI
=aAY8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2ed0e09b-1c9c-4910-aac0-ed688a8ac61a',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//c1r/JqZGDLl9fHHxdwpDY+/fi4kiU52y4S6o23tn/8Vd
9WWyGjpBWZLXZ6FQBFDQFaQsMFrIwBjw6yoV17du+LZxX9yqyqs9YKrPDdsGjYzh
sw4kgpgNhxPR69OcfQYMaeg4UaNcQJ9c74z7T9g/72v8FLxoUOYqyEBYfTvP9p1F
ecXxIUzbcXq7HHXxBJQ5l57orfu4iW8oyBXYMtP+y+MtVZK9T0eCJ1NsuyfJW1BT
iiuMmYNpASPzNjwCIiNpv7WC9JUGszYh78Aq+rkl2AXVXrorcK5OGF1K+uPimd3v
AHthFOgAX+m7hZac85lZAelUcEi2IY+3UfOR2qhG6C/c/k8AbN1oNhDkfTFTwI4C
YuwfSg9MDfZrhlBe0YfJzO/eXvQS02efORO55Gus2W0zO6SREB0DaHEVmezWmelh
7ahl7nmFGJ0PhGEGAWb4AzlRzZRbOvpm43qNAiC5uDrs1WZWKgaSAHf+tTcOZOeh
6TpPq6b2BSKDMtJeRRgmkuFOMfDEnpBxkyjw6YNJZvQy5hWcMzfnILUPWyAMjNNo
g/U4vHJkhrPLzrAx1hUIgm8DRf9waxDOV0ND3aFHjavmfgZsnYrA/aUmTLcsck3g
YHfEUl2+QDTxs0uqkcR/0KbBCkpde6BtezPueBZWp6nGsrgJniOpPmhQt9cUsY3S
QQFFdDP/rhvVQ0vNrHI0yPyu4MdiqiGNS2jrX+9Zoi+ptc5Aledgg41xXuGdM2/H
o/8Kwf1Xb8XGDOlhY19xwb/H
=f/0Z
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '31196f70-1820-4739-ab4b-1c866dfd42c5',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/8ChIjd0XDo8186EbBj+AsNDbSjSAbZ+8D7OqS7pUnLax6
XM5ulCe/J45qeSrI2c7H7zvtIZhpH1ZpRi1nL8P9dJtFDlbgfGMXfI4WTXARBTIm
WLHxmmvfpx/KKJ9OUF3wWWLJmTs3/P3RahcgrfL2yvOL+MmCk6FXgaNR/EkyNIfE
wJir5vAVX7TZQ+0bplm6bL3Bx/tyXJ1wb5gZhZXJPTMZcLVPTW8sa2q67HhFQVHe
vn1j7Q6jE6s2ylSLR1hanUIqpFYCBBobfSRYzRffs5dPrdIbzEVYdSlm14YcTThv
bpuieUaKSyhGEoMG5TfO0a5wGoKlNLoI6OoND+hCLNoLfC7OdrNX0RVwYul0jZTd
0P0Mis3LeJJ6nZFlxdxAQa+VSy0cFgyFP2tEyi6ijY1b08nqSxpOlxVb6u8JRUHa
6k52tCdboiJTNfpcrsmOgKGWLjBT1ItjPPTPE665fJ5iwBhNYfI6C9vLi789YxY3
pWMDmRlerDk/5rBczWkikX2FNEmqPukW2DMsS42yIn8EdFQs6i0L3n3yD5b98PrY
m5L/EIgthWcaMDvvawHlEus6MUUu62OMl4oH/Mtz99mFhKnsOkfPaWpjZQYjqX3s
t4oFxsCxK/0K9MIZl+g9ULAVZ77ujTv3VMZnp8T+njGE5nyo/6MX8YCwzZ9BQs3S
QQGqsIlH4+4oaeuhfd16I4yaJh9JEOhdCs+EIvvtqp5Sj0cPHaQygIAqzg4JLkh2
m218vDJ8lJBnd+CWs5eld7NB
=XOWp
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3309f995-f6f5-44e3-a50c-64785f55953a',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+IeGfa0Ik4+4VTg9MHBIFNBwsG4hoAkqKrrPW7drSxL7n
yfTD68iecS6XpUcFjWEgc0UU7ADD6hw1yDaQjX+aRPe8G/hICFlEoTitDSyYP4n/
eF6Vx0aobpQo6hVkvh7FfZe6vBRUBMenxp7tHgnB7ou97qDwQanBxs7aMMBVdDNm
iQxNx94xLC6qmFC+YH5+0r5UiJj8dE4eCaWohh9Ou2oI7+3U4WauE7IKQ9K3sC7b
8xepcjT5Pa00q3JBlGZ0QxE7ochJt/OdYpoFE4sAoYNFZ1BC21Q2ceQb/UJEW1KG
2ATs7JVACDsjjlRyRnBL3nD/E2cS9C5JbvrWNt6znM1k5e5hogkKlvv8fTsUAAy3
bcYy2aVJ8rz0ws8fhbf25UnDWBwADYX1v1HvUHfeLHl855YPF1p0jePVywzh6c4m
tUy3ON5DNfSPeajijlSng5SlvSwPhONnNrwCVoTw873Gr4f/3cn82FBUAFhwlxZ7
YPTJS1rafQBfA+CynBpFm8xgQkLJ16CvhTwrbkY+3P591itQZP8syJw7XnUtgMRl
1YwJw7HnUoT3yiYvdIoI1tkV1hIer7gS8whYxByjS+xft16CeOWO1kWCyxo8rdyF
MVhsJ7TP5RkSHBWNCXyveLDkKH8Ieu8jJQhPs2uwj80qagRPHAe2aBrDvgA6cATS
SQHp5wshK7f0reqNR1HMBS6XjUVcMWUew6CzniAepCn1Ytl/mSRpsE43ibDkdoTR
p3DE1YJXEAhHnXgTPJsHitscRuF1dF34R0Y=
=eHTD
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '33a57053-3da4-4afa-a007-b67785a1541f',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAn4Uhbr+3HXCL82Otvm7E545074s47+QZWUoNu8H8YCdW
KOcS+EgC2V9bEAhJdjWo/1PXkuEP8A5W2E9MYR3HUF3uqpG+TJ/GtBtDLPmnxmar
k5AEWZGPfFqx8k2tagkZsRZNUwQYdksHvK2ooDDfcMB6cCPGewAqbKOyKdgXPHk3
EHZJPO5brVygSpn5F7RwY8l2UTAx2bR8PuweqjMwqEQHIuit400CAlxbBLEuKTDx
O6X43ui3votyOgtmSwpE26W4VnXYl74RHUwR9pbd6tlpakhkMhFeOCq6wrXC0/Xe
XNJvRuvf9WL1RKeJSgHis8tQ9x7UysQJwuN3+N07CjdbXhKwtaBJ9Dtllpr+zZf6
y0IRn/KoCCOZHshRVbYSeB0OjVvoPzEC4eiO+ZXUwDJ4b0kND3IuFhq26DTklYDy
hjTTpg/hmlj98ToDuL5iDzaAObq+IxJjo05fCS16JeYMU6J/X0c+ygfEftpiTAve
hGVZEtZdrpPoNekr8cMLaFUgjIhxug9K6OicJyRBz/pEoxgSTZcGnOiM8v20sKZh
y/fbURBX+FGlERXApN6LaXybFKVf0U11BjO8YWQ6vUvK9LosIYQXWe9V0hXpl4Ye
YK3i8Z7iGgDv+poUu5KMRLTX8NINL94P+Vh921fG8n4VuC0PcudYG5rl/Ne48PHS
QAF43yTOegpikIw5VhCKsQlKjDFRPjeO3M67XmBuP4x9oO9ehXsMDNY80epex9QD
bEpXoL6LTW8avdGia7mLwQ0=
=XSir
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '33c30179-0c9e-486d-a920-c6f39370584a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAuhqgl2Zq5tro8ETGvGJZOzb8XwV1LJBh6U22kHCp7hQB
0xglAdJU1ChaRuODOS0R8BjSyi9RDGTfHHVF4YCUwJ7N5k9Q9exosOIC95RAuBZ9
hpX8Vmp0JZsjH6vMXDIltLFHHpG6APwoQ/bNdK8HVOdt+qVJODCBRtlN6am8qLWI
LHWdw7bWlGd5eHEe4XWlgk9xf4MUW60bV6o4iGE33cUUtCxLXTQYxvIbxLUhTTn/
t3tnEWVWzhyVb2H2JRMK98DzgMT27wHImgFRX7fvlXvjPOEyraLOhAE8hnnUggQe
IxxJSs/eiURcMl9hrUkGj18YOh4xyiAQ8S7wD62F9ALDpiGFffWn3ICMRFfqYsOo
renNbB2VldiLlhnjASXOVrlMpcFMN+p+wpqDcSgRnW/aWZ2rfxP70k+14sIE0n/1
V2uaU3p0sy6IsC13fjwsabrtnNsgtfn95bOk55+1jqGAgCTWVkyea6b+mBNj0rvn
BKUsleH66ekDruy7kqOMX2BcjyF2PwZlQx/m2fAhXrdjb3dhM/JfLgrNmBO4jom1
woVcsSOFAdcgFtF/y1UtAVBFfZAXc5xHfPgMrTBy7WP3fSJUya5UVsqQsHUMZl91
r68K/nUC9+PSZnIfa+0iW0uHOmN/B9hICaGUJFys4vedDEjz6gsOfQGAUOPqIPbS
QQGJd7pCbFQwrUQFTJOMSCmCM0dUhZWo4kSyNzs6Nq+MK3cT/h6QG33Ii/3vGmVQ
wTkf2PJu4F9pGWp98qTKQlWn
=FJ6g
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '387493b7-4125-4a4b-ac17-f02e969e0e3e',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9FpXTJOr0lw2duS9iCcvn/x7SaB03h2nGz5QA8z8Rkj7W
QTXUfLrObrvTb7Ah/ClpmRxMI5TX8B0IyXpIVD7nYFqzhQBihvypFrOl9jbAIjPf
M3+5eDIb+6LFOtAPckiO/6d2e1HpotfkjIGM15eQRPsTTimS5mjrwWO+OuklG7yL
rUK3GP3EcYC2b1+Z2ubadZrh/ZA0GuqOI0i5MWffKMQGH2MifEJTW6BTc+hWMXb8
dIuhEva9scTe4k6yDezeQ6ZOB17bjJGOY5O7iRJuj8rMxB/YzLd8NWVM0NaGcJ62
lEXkrhRMKIpRzCe4gYSkR0w4p5KnCNcMf8OGYnW/j02s8bcVUPC98k4gH7cAZsse
T008Fg5hgRk89wTTJCEjkZE/NY/BsIati71CHjjnNHPZiEJflZzbs3VebCS8MLcV
8MiLNXJbTOdZjzEZMMHXtWJpVmxXPoViq4leRykgZ8aTrBPuRcwMRn3ieBt9RtWO
0Z1eGIAjs1C5lEA7ejm/Sf+sJsGo36velLJr0svfQTZ4cDxDMTNRbzzgcUgZ36Pv
2RIi4FYi9UTAwE2CMgg+8Bd+gGT9QLMXEQiGXvuntSy3yLJggwVrvya+Rj7hU3XJ
w6PxBSTLuTp3/BbR1XyptTD/dAMGtR70GAiIkaTLPAk3VGTswh6CKmJbLzNTkDTS
RAHdcgQSESL+UnUgPQ0rDmgSDIh2bM6Q5quHTpZ+txcHYxI/3m4rlinGPO1t4hKt
JX8sqaJnRWklhP31L/tYvz3KLeQN
=qMHo
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '38d2a6cf-8d4b-421b-a829-f504489456a2',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+O1uZ6mYD1WoqCIPpipxg5+HTzwaiPwD/EyrInYocVIDn
w8sWch4DGYcdwWfhF2tchxZ8CIUEq5QetZB88BeFTvR2tXsmSlIcbqUXtIQILZa9
7bvJrZxMSIbKWsv40XZrsGE5y5BIjmbsfzfx6kN3s2P/Dcki4Dr7olqUP8+hk61E
ATtiEfVFRUS9YqYO8zxeuk55lHT+X3Dh90PtfY5almariR+QqC+xXBc8OSoGUQgS
lLhcLqNFoErcie6RRQdw5xEq8UgEB9BnbI3ViWiAAUPPr8xV52Y5EnG+pAt39H5i
O9xCVsHUjxMGP0ghPn1RZ2lVF58aiR1hHObMzTLqCny+gcjEc3bERcFv46Jovi65
nlIAGOMaFIQsj82r4X+wz9JNgW8VhlimNI5lvqDXcIwi79eRw6LboWEYKcLo4Z5g
L+OanCT7jHVCtKt2knyW+gER2C6a045kKIoNZW+bZAbT9TTHBzn15y32xqs3Crsz
PJdBqGWP00P+WrD2/2l9M8e+QsoJGoVycTIGoUhOUlBCPTKhiK8YgLmhBZS99w3W
zqJfeZWq1kV2y4Uq8Dgv4Z8H4gyLdeRQxeQe67/Ex993oZCFOVG046V/yFU/R1ty
OWYJwAqbdq2XU95rXv3h/fw8dZLO1x6qBQP30FZ4D7xmqjlkGLXPLwkvbDXy9g7S
QwH67rmQmYpMEOmFxUw/Zjfxkm/rYH2iTP7GQswiXtYqro7XOo2T/fT+yisvX4Nh
65rlySP1kmhnLFayRlvylDUwmfc=
=p6CV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '391cebdd-91a2-4da1-ae24-cf94d205bf96',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//c8RJlmGqrJ6YKWYKY18rDLfxvDNTBLt3mtHkh6fv51Hk
MMmCce90woXNKMsk/he7nfsnfNCoUWzLNCgKuyTbJwcjW/X6NP+OQDWR94n/ND1m
PxpG5FEPrj6ekXNvbRyzG8SjzbZNwesAQYyL47uU0bjsRslyQ9FAgO4hfK9EyRUV
N4fTU/bTnywE+FGAwgGKSCisyDEY3GiKYxw3dURyiktftk25LHM12sfKIAIuooI3
DbbrCJq6FOSIITveFdAc8zuBKTceQ+Crx3gr6awrMqDHNIkjPIUAvaqayIToDsLR
Vru2qa19ISP/rYM1uhpdgO1vjFf0SPMEazCJXHBJOycB5xLAXaTRYjEmF6GLbs1y
XL+IqXglD00dRhzrdoaTDTT1nnGVf25jR/HxnqX64XhLtZiPzhPJx/JWAe6x9y0x
XSieLCZINVFGxtTtOwAmwgrUyvosbpIKJbn/hLM3xqkAar37NsfYHJ2yFNh8D4Uv
KZl2S2AWjw32YAUg2iUyu1M+BkRQixEAestqvveIH+rahZaP66T1mpJvauwyRayc
ADfo0bTMHjTNoRl1ILDglb7GuLWohkJPlCrrOLHGiijpULYuBwYI1r3kug7MnCuR
jJkQ9kIyAfUAwlo326xGrQNGN4Ogmjln8SSnzwUrmKHCpdL0WiRk4g6dzjSyh27S
QAEE01HcnwCwByHA8uSi70i06T6l0espqYjR9JU4RGhR2YfNl4myPDvgiSHLIiF0
E9J3nf4MXkoqCfEV1GXlH1E=
=8lQe
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '39ed08c1-d3b0-42dc-a5cc-616b2117e2e4',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//byNgJ9wXDrdaHgTgVQvDTZ1wBXNDkGQdWYNEsktzl7HF
sSzz2CuvErUp/Mv2kjlM/N8KjOV77yhJDoJcPzYlr6hsMhBK/7lsxPEdC+2Inm15
LIp+xSJh65PWuLE3wenV1PAkI1ECS4JxpPXJnA5HvKrxQJUQZ/lSjouypckRGDaD
PU3EoQiFqY/bpf3xpmllevCeJi93fOTel9NMI2GysRWY/OA7/X8CkGeYUm+Tqtsp
1nnnYSOGpFbsEoYJGcyKNJz81Fc7hBkGU8BYvv2l+fI91wt2AlZVYuNHEKGKBldh
oLpHImCTHkK7K9XrIEX/n6WdtZjoqw41e7Kf82n99n04PBS5KOcAUt0V2D4Qsc+C
VL86cEwlg4qTe1fII1r/1Q2i9/qs/rf0AmLoW4zMfhREKurQB7swvXha+4KAOP8e
gen8P0xvSU9dLdNJVvOyORCGRlan3ZjAvGAQETN5u0MRJI14aTAIkjL+TJpZ+9U/
/UG/7nWZAIVqAyGKxGooNElS+AmaEBrSHoZoQVRYD6Hp4YcD9Ag/zQNB7jqE9cAT
OBUI3Lcay2MFgdmS3rPa5o62favI/x4UrAnJglp5IFThCo7O3H7uAmSy/VB2ZJ7g
21kDF/1UjPv+i5YhEimQ7XY0P+ykVWqcoLH3P6JwxIVJ/W7oBUJXj9DX8cAY2vjS
PgFQ4gGQojO21m3y97yLA6YLLHzRb3WnfeaR8SNvFO6ditmJf8w+Lj/sIApAM9VA
mq+6zWduf4GsPWge4abc
=lXkd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3a79b3a2-c09a-419c-a03d-fe44babe46ec',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//fEFXR76ygMWgtWqdqiLVVSI6TLiEyv0RjKOtcJiWD5TG
/ck1qSAxhpZvLzGNC2Vt5bZwty3XuQYfHoTBqXVQKJMhNSlftr4xj2548Cgfw7iy
GYeHKik6LqCNunCy0uwF2tqdklXGU3cDmGP3MGCZdON6VHQxctH1xJbNyzcP0vAn
duGfv0pAp7WsMuff//7HnYC6ogRKKRfsJDEgOA78zAKr+NqhW8KPJG0HCtBdcUn5
ze14U3Go2JslDu8QTqk74us8/Ap95rqikxPerar5AZMvEEkhv8dZhNhPAgT4nAX9
k2J5hVZBnOI6CfJhSBoBJezhDMXMaD7K4STFeYVG4Rasit8ihMt4G6GO7CmifFPY
Ht0hASMOKjD8v1YDa2Ij2ibnHZiBcADBNffhykfb43VcesMaMjdk0dx5lqTtXsAf
VaE8DD0tvok9XqtRGwl8v9N9YJALvAPM5eIsq7qTyMPwIaI87CNP9i+O7Rh/7tNx
lqkZ/rDNQujPmys1QEPFkrMOlPZO0qvzvmDpLo8o/V3jEZhhNFu1MeN2EeRFvc7V
CrT+qpJtbzEpRIelfC8zs4iGY+q422W7vhbuK+/s7xlToOnOkcrtSOgKXDdhFhdg
nudgj9GNnkXp4dlJUBe9AdFxHgEfgE71oKkp5MvPqzyspxT4ps2WwW4t1YlXqczS
PwFwBxP7SzQQg0iRZPO4yCDDrK9PooMKnVGjYiJ6/MRLPQaUAw/e8Y7nvXkG1MTa
BY5UBQciZnN+izzwlALtdg==
=ewdM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3d10df3c-06f4-445b-aa6e-8de81d2c4b3f',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+INyuMbg6KxxkBsjKf0v3SnRi78MMFWME8Ow0gQ5DRugR
153iYgbL9fGuDrro3S/UlMqKnZjVAN/R5hvw1LThuI2aUK3nidGMqbntLIxGGyo2
vMrcKl9rDjUVkT1Nl+lhx8S3ljIbOw2MMRffhh9Te5SdQEsD0Lge8Acy0qwBdnK5
UQiUONj8ckgi9M4dg/jfn17N+5abG/LPbAmv7aArpXkxY0knADxDX4xJj0L+053x
tWPuY+azfMYTQaVwLzmfe1QA3K/1DM0ito8hcLoF3Kb4nUrA1rjWyoxR3nUl5qw5
bgk6cVaU2ROEVQj4OZFhBZy7ML3eIs3gW23rsx2hBNAFcAuwHFRBNUbnvgPyipwT
W38MYGA0VDe5bmj6IavLyt5tAwh4NUzCJ/kZuH+IR8Jup6eFMU0IT6I3iwyX5L7x
Jx5MrAbZ6mXp1OGd898sO16pPRBaIKexVReuzf+tqKlax4MagbPxSNVT6IILfBF5
R/pT1Add+PPXoNouR6fdbO5t5Kp3B6kXApTk158sPNQqL/Y6FDPlcYBbEN6NA/hF
pRcMhUCVqJ0IUZaW24edx9FR/hvMW1Nxh+QxQCVtxfPF2Tc3P9SBIayz7TL4xNBS
mX3Ck1o/1uqp0vF8jAVA8lghye6LYe8lHfTEFxORRbXP3L0LQovCPzeoSy3E3KfS
QQGOoxAUZWfA23RQ1iZr5FBNuVD+rdCd6V2PgJHuArVCU59LHKZuh6j2Verh1GIq
tpumV5hFdDHwc3GQ7sUN63Jg
=T9zR
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3f5184aa-9199-4a9e-a233-f47ef14f861d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+ITvM7YTJ7ea+TLUw3qCMQpIN4SlXIiKnbgAW/XASMtG6
UbbX+KIPffJTVNIYaswXPlGtOrJov/imM6KPrbj7G9jbjNb7LA/5cSvIx2V+XsTC
U/A38nLcWrGq8p/j9+MKwiQJnKGOG3fZ73GYE2eHR6axleaSgX/YH01GlzNC8xXI
qp/IVzqxrYGDmZIyA8dl3UCFowuWIbKNbcTpfmYMF+7s1kdugSJvHHVRgZJUdcFx
mgvSQ+iUiXXBbxatHVkDi4fICS9dv9VjduE4AR0HhppSL3/iLBDN3Du0JAvqW6OE
0UOuNSjR33g2DYEKVBD+GBofGZdVqcTEb0xGOFZfhzgRJNz65lPWI/P2PMQgwbzx
AGP6gDJeE61WUQOJnnT3Dwo+vuZh7QlGEZt8telM7rtsiGU28BV6tcB7ObZx1B7h
wTXUWqJUx6iuIiqWWMcL43QSVE/1yZ2+18ts5bsrSGa6UN5xq+++8wZRTpQ/hFP+
jHGDhmryatrLwxQcG9W38XJJKJam3qbGqWhvtoCg4coWF8d1ukRUYqmZicI+xJRD
/r1V+/d1UeVW5km++1Lc8xOySmevlpfakNWyTOX/s55UxU/oxxSAikfPxJY11Vfz
jgWLNnVdk68oAX4oT/eQqpjPGdAEukxoQx0bM3Y7klMztftvywB286KC3eXjcU3S
QQH8N2s0cOZFTGa39qHf4S6xTkxlHwZmL5sfP6u8zTeZoS3NX9OqZxn00FHICFET
iGjjkiMNJc+S+TCCTOkFE7mn
=6Nj9
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3f97adf2-a976-4939-a231-8639dc956025',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+KwCZ2nH3E7+rfucL8Mhj0M9YQUAdL+MYYQN62N4ph1Xr
/V9PJwDqBvl9+o/v4GtiGqP+5yMKnqT4dnDbp8IGqvflLOxdhkDjOpaA2NZOm6Jf
bd+mDPNOIZ99Tqdw/rI3wqUa8rFmi9CGY1aBJ3+uVPZBLGm+0NPLe5VU7IGtyVQo
x72/bCJzjOvsWQNb+MWwmoJJyi1gFtsKv76oeM0vVYxOzn0tvKemYaa4g9IwJpdD
s5P+ioBSrHW1VhJehOM8O7CqNemkqOYDuqJkbl6ylYISx8BYAkuX+cF3hG8pkJOr
XIUIUYPeN49oAWgGq/f73Yv71Mj2vp8heeuK0VgmQ1Lb6G+2gxoKzarAzzvoEwNO
jS69Auf3y3biWcmLsZ84Agx+gce/ekxcLhLifYCGALpPTZD1qhSWC59FFY0JYd+Y
WnwnYo/pPbL0rDgCFQcUgdk4HJGHLmrDBM+Z2+CZcAKpYWsODNe4LcU4R42Xa/Q7
/JSiKrErVuSm9i9Knsii90pNzKWGztUULwyeq7ke/hC1x3FaKv1PFRk3WM7uXcE1
lnohNDaA4t9M555uUfvMLDJzVIcr58JYQmMtesi+l+GWUHmiiN7+KvoNJm8yetGb
nsxVwLpbcVCuzF9CKxGnwY6ctG3dJf55hkbrSObug1IFn2zU7uf6cwlzvH+ApF7S
SQEv1R/xFov+ZvSjIPMMEaHUJ8IpwBBHtrywrHRwGeai39/FpzyO6KGm9DdXLU9G
0y5tg73x4abEIQeP43qJ8R5GshCcILaEYg4=
=vhHs
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '40337e60-f6ff-4b29-a788-00b74ad35cbe',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//Yqc/kDuMcT4zVGJc3YDlwAgYC0izJqKmVDDnYn9S4vgs
SuohkD55tOh8WSoXIQzEQNx+Pfr35QHxHwm4YfiJk7hK3rDDCf0+//t96D0yvD+w
3+e3TF7kgu03RhMuuBLN12AzGYaZhBdDY/4W8N6Ybe7rR1y+iWvqfEIr1eiFS0mD
PT+Y2JkFisBNXEO2Q7viI2lPySRkJl+GN3iSyJ3dVRBHsw3Bt+P631rRukwwFNmq
GDgoASniZilStF1H6eNofQ3yF04KWRmfe2IrDPQWxf8w2Q8Efgi3uabOCNzMwQl9
ylVom3rrpxeYrQuAN6wI0LWeDk77VOxr+wqSU2qgNg3/peQrCJYaKWDQOvTnyyFU
MZ25WPh01NTPs5KNuyFGUJUlUsEvMkYn3i6KjwyoUUh3+9/W4pIFV32EzShkgynX
ZN5i3W04TitAmsP+Y8oFTk8X/3ak61qrRJilNxJlh9pMe/kbIEd10YWToXN3kuRG
ngt/uGiCfr5hfDfT3CmnyVRUDgp1WZZt63TRwa2oOpU7wC/sizPR6ky/l20MPhQh
Bav4N8TGtCX3/zLB69AnbLAVH8ev87X3vFijoQADKX6kng4sqfwPDzhi7h/E0HTI
qoQQIMHVacDtZ2teDN/x0s1fcWJpvCsRnx4H60P2/Qq97m3z/OaN8jFt5R+6qAvS
QgE3Q/NF8jvl5xBWEDRMH2Suy+B+YgLdB2yZiYvbGpdIcr4lj3Rn4yTeQ2xsF3I5
bdUi6r7WPqgR7Xa2lVcED5E49g==
=tTsl
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '40c0c383-b195-4153-afb4-540c006dbeb0',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAjSybZV5+D9vEtZKIF1pFc3ANxTTM/ScZVu+W2tzZaocu
32eG64GueEw2ChSjRhluUBq+QDPigz+J3epxgRpTYoXcUaDvyLHDfVNY2TPDMMZM
LoKHL6Tu6QwqK4QzbmGzo9KnzRVCWxuV6Prxzwv4K2OZ5Zq7eRV7XAegIWgwH0te
c0QsPsHCn3ZpPU4jljDuCKOSd75jo2gzobQrTZqJf6cFpvFkZiBt8BESRYWIdQFU
rjG+3JnKp8ggMk6j9hV8kW/5Cpo7JWLZs5KehKE7OQoVLqOop+hD6IP4jQk5hqG0
R1Yj9GMLi883boapdDWfAp249tmIyKiN/Obk5dNZx6WhDk1T7sCt0/ueh0cVZpl0
n6lRAtbX1kCIYYYYRpHWoO+nqX1NEU0yzoE6NZmAxtra2D1ExgovCejVHGiBjJy6
RkZ9j3vmyhlnmbmvsbb3LK5DFM7oKCai92leGjlKBfk6gX4IuK9w+qHg39s/Wqau
2cvN/xRozFpD6AAfywBRQbx/V37ZUMye4IV61tKiDZo75D0V1NvtODX5OeugBoFe
nLW5hZY/h+OMxbVFAxmotygc8pfkBxtHBOzzU+IBjpbb7vi9uwpEVsfbrvZE9prj
GvDH9k3PqpVcOJPlWhRw67x2WU4mm9LndgO+0eHrHUgUoqZFPlVGVVEFjbc3oOvS
QQHYWsE0UU3eNQJHXLgcDGCImHnozkauBsb2l1ICzP7qEOd/QIR8U2gdTSH3K+B5
6LLq/lxzkZgKGP51Mgis/kSt
=ed0M
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '42121a29-39a5-45f5-a594-fbe78438010f',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAu7Yr+XP79bXIppPX7STOANj+5fsJDZgyLTeDgS0UTaUZ
pA3fIKSHZ+CGQuYO4GMCzAqdj/SHha8v0L9lzv/pFjrI/umG8bzT1qi0w8+RoclU
pwAmhvxfFmTUu+M/Ff4OxKl6bwTp52oAon2S1FqJ0tuQot1k9mD2Iu/xR2tfuQQ0
fWemUkum8SY8GZC7LXOkF+O/pn75u7Kmr9p9ZLKGWtKIGPDoYKAc0iNf1TEYinhh
cnDY54je2ClGB2oSN2xXvdSG9AxNgXeD6VF8pgOybErTgJ6SnCZXels3LE3XlOT9
9zjd7E20pZ3tlFUvuZvCd601PgtDmtzEEGDuEOGYLp7SseB8Zo3sHPZFCgA/vWxp
dmqRVERYN+TqUap6oG4QiaALKCEgz3mgsIVPnZkI0ScbSUlBW6ExuE6YuT7OiJDs
OmPRy5JimdwUSDbigbb3tfSHqDr4368X2aZL4JiMPJ+tU8+cX0GHuAhRTuGai3NY
H/Ucm0plBxLTidjMClvspmeV/oSLXmPbwXZYH94AXBDffvoK2DSndKNhyicXstKR
DRgdMQi5g+gkJqSLFannif0+YPrzlAEtR+Z2g1JbsEtxdDCz57Ok2HmN4MXbcrbl
Kv1jaUZEpUoaRgWeUEm47M7PXTouYyf+jlS2gVkJ/0V0ccn3dcmP/ChRWbnHxarS
QAFU+93WkdCyeUZxQZjNwTCIHhV0a/0xOkz2tRUD+khbFU1UVd381rirbPxhPbWJ
ha/fJHiwu/BHwCWJO/ftv4U=
=KECM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '42af570f-1c3c-4271-a29e-406f273b4cdb',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RuSX6Ppu+5nlfopQhU+Dj2P2GdlToDlnyOSQkHwHi/Zw
ppHLOnbv5yavVaqZwD4pHum3l/E8prE2su/ID5nBuNj4k/tpV3LUYUQMAJCPA0xo
TJRUXiOBuBrJS2TNR4rHY61p0St4DLGJaPuzwN+AXL+HmZ7p8/7Wt6GaLgbyxwdg
RMuAHmbQoQhoiZ6vHrsJS0ySoh6fAKNSsqbJm0It3e4lU2Swtq4PNNjqMiQP5f0p
60HJPqrgrE8optWjLgWazV0ShwjLUumm2fZkRXJdNflZhnbcAEWjhSgxMNBIAw1c
264oqkiiOeJZxE1Whrz2n3yOHxrFqs47CA9OU/xmtNTBG0Q/74zDSG6K8zUVtLjb
z5f1Z0ncdwYZKwxU5G2snEQIQcQgLuGdAJ/hYUHr9SGYzHvDCYpdraVyF1w1S860
s6X176DGNtEyoIaLTJOByKThEY9hwlId0ajh6KuC6lmFAa2Udag1R6gAHIIqnQ0i
G4cEkNyey9fy5Y1BW/zOKKYWT11GoQuUtJMMDVhWvImfJvuNZX/Kw5nwmi2StwuY
ZA/J1FiwVrFM2DnN8+MxQbLoqMuY6Wi50myDppSA8QmibzIhRtmBlj/oyyzEhlh6
fgU+zPVN6SJvVTu1qEvWb4BpozyQPP0QmYHsUGKLSaTqc9pSHQnWy4uXBD21ftbS
QwFYGitiT1QARHo9LDUy7cV18QTsn1WjsBCPJ8noqTtgm6rZHC3NWYLxwwlSLjgy
h76aIiIYSlngfaKF26K0v97ldG0=
=QkKi
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '43216824-ada9-4c0e-a4c0-6948523111be',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//WG5LHrA0jm+OBUsUdMHMc8ducB0C/KaBpPTcMAFTQLNT
NJEaREKOm+WLMEt5B9zyytz8Z6QjVwrn9k6DAdtidOwlLz3IT6+PFEPtLkuCErau
/fAzT2wFdNXcPDZRB/5yJM5A+G06hgooavxyrO0MVFVE1N71TU//NIE8abRP3aLj
FrIXk7wtwTzU3VrQJIH7tp3t3RNZ9rs2q0hjujUNCmeCPZ3bdxvvZEgqZtFJ7A2t
BpUGFs4Ha3PgjPTe2OU8q67YL6OaXO6NetK1R9EhkIkBVK0difvfqA6df3nFEi7p
AaBBLHccs8YPz2uedzjyt4pPFPLsZlwaQ8wD0NHixdp0l16xWjCuvhgvYxM1Sw6M
flTmmBciodzjc5PN1JM+q1c309dOHQ93bDMqUFYxQoCVx9VrxNdkch9q+do72Lnw
CcZ/u24DBN5X7PcksiQGPPCvYy2y9nr2rNEWSf7U7FHD4Le7s8hlJhcr0aHwS6ud
edkOT9BZIxEe7c1M70wHEkDj8WbLBrPhnx35xpMS3T8OUxqe1AGdWOhntFo5LrIT
QkQ6mf1BlXT3O8fSmbV0lsqou5lm9XwsFfmq49g37v9GxadEHAs8N9mAgvX75JL1
K1zz0dJg8ODLJSloyZPj44QECQQtO/x6EgzG/PSrarp6ADXzYbR4g4BbamLV6njS
SQE3IBaBh26StNhzBBXJ6r+BSaUIppM5r9bFJDV4TKyTcAkDoia2HybwJWsxiJBp
ETMktYPJeZS6lR38rBq8YeFEGPnF+2/vPjo=
=C9JT
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4465e243-ba02-4601-ac2e-3dc3b124a56f',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/5AeKBTWZ5hUVcALk4lC9y18MqZNHPzfhO2f1JpjmwxmX7
0+1qHLTjvXe/1Y5kxy9y805jVIjblVYLR/xzUmtgmkG4W9gVLVsEBBNgq6levaKi
6Zt8Gy6WpRXpKxCsu4h1G/nn5bXqeXjAuvsr2KlkVf2GX4OazySXEOTHrAZy7a1F
In22d4kLzK+F+ZR8Z3mW/cuh3680y2xhFtvXtVLGdlpuy5uJdJ1POG+XvVK8pNlU
foQ26ONwK4+AsgWMw/yJQg0b4tnTjNhJ/uR3ap9nixtffsj+4OQq3VOw9fajVeaS
T01PA6fju1bc43mcHJqZHwirZhOCDjpLcVlKLHpjyinVGr0ITJb083z/DXvWciVb
HnCtQgzLEDGTu3akSGPMaKARItiFGMpVjT77ho481X+VQEIGYp17IBbsJ1pqXnvv
QlKZy6eKhXUGLKZvDBGABzRhdZo6NizHdZXA5iIegXg7Bac1Mqb9jwjHPw9DZe+9
gQbvjvUlj8CSwzdKqVtJpYQMpI4fbZoGhaV5SK/E76bsGODOvNzW9AA2FldpW8F+
VDalXAwMVbswlMazaPFP7Fu3Ta3/zu6QjwDcCjvUXl9gUsq9f0/W5bAkF6YK2Trt
ZgfJI233jnuWah5HL7JiNGduM9yyb0/+AUIMUnt+6xpIhdSQw2NZmCtG9MUWsLjS
TQE9eE35xZZ7AjnQIqwX3gFIHUyUljPPiiWxrLmrp3mF7Am2IWbO29jOrDagHT8Y
kZhgXjFXo+fNc9/VJiL3kBhv0pT50VgxJPgSTk7K
=QxKt
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '447205fc-8b47-47d6-aa0f-02b74a120e50',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//dnoOSpr+Z9ODCJff+GTGyWEfr0LzxCNjC5EBghsL+0rf
yWd0AzLsVr4wu6iJQRImf5NFNHYO5RnCSzXBpGBInN2r8BFNLn6BV7kVwPhDsJUd
nx1OciIMUB+dOnwTWE+bV615iLCj867fN4ZrKY3iGGezpmi5lcn2rba7gyzYvN/p
xoruEoRSaP8fgddKVZ3CLWMKeiAOfNQtjBNu+6LNwbYYkIUX3CwyWgi7DKt9DxkV
6+qYVjgYLOGz1v06unTWsi/dnKwpyf6crYM//uEt25YXZhnNwKMiBlCB0Br0qQg7
mMntnM0uSMxc4H6jAg+DjDsR9pU6RmN+xrS4KP0gRk6rGdr3NVVK6y8Qr6HV3pvm
9Mznnclk8aM7TnIdg80j9Y1oLP1UwFWEDkc5XlyFOu8325DDYVBxqt4AZBO7ZZT1
dDj7BVdySoKos64ozjM820kGHe6RdtjI/dZmHUOuUQI+oUZ+7+QmgG2BpUqwo8j7
L8ANDbSdYfGJZpOwbpo17kFPC+9pmShkK5Ns+JW+zyLAm/PYjWjpSCQl6MfZUFTZ
ZWxLIXBM27PHlEQ6AZwSlyYcx2P4A4Rvwb0krO/qSSm3S4vdZIhfBKxlYRDtJcRo
/ewf6DG11zQPCQVLkE5JEA1ToQRo1YIh72K56udNlxA0l3RUHJtsx8TXINR/qtHS
PgHOxZodx7KuFxPRFBzkGAvdyYEHEXEo1C/UzmWTMonOZPCjkEyRxy8G4TgRw2zC
L4IDC3M1Wy4bDdqHe2ct
=5vRP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '458639dc-ed62-4e9d-a8e8-6866d0ed1bb8',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//QgMffkMvAp7Q9SX1nvixDUr6GXI8wWwGJ52NPwWl05X9
hHtCkZh0GO6d2YMRWQf/Xhq8PrHmIdgSSd9gzv1zxCvYPTUbEXYSVQbOQg2LQExI
hQ1YcTWbQbngFeFm/zWR7/Hjze0Uvg3cUCIywNwFdFzpKk0qONmPGu/scX1U4u0w
sV7+548xiKexVkw+YCf3lAxlmOpKrwq/efmlQuzGRwhMxqAuNvKLvauDAmWZoxX5
wZHbJzIBQqfcbifWFjq9QJoVDU436CpfZ/9pfM6bhOLM0fgXPRXpZPiOGf7pypkP
IYlnxsk1Ta9JRFCjl1iUxc7fO9VjyL8kOehHmk6FB8UEgE5uoBYGqwgwfIsQ15HB
wRMsGzFRhhNKzsTtAcSar8TEr888rUYuNuDHgtJno3+78u/UEkvh2kDH/zB+vBs1
cCSpiOqMqzHasepgU04WdVlI1bEb0dL7Beebgy9I6yC2QkYVFjBzcEcw1Mm3XzoX
5Yz6oRHzV9p0QAFaqEsq0gxN3HOXC4yAhkIKyBCtixrChJsTKAuo8wRItylflury
hgRiE5uYaG3nuTO5iMEOMGZHZT1rh7LnV9pOddcbmbwjgNLOKVeTCslHLbxsKfzT
GcEtJzV6EQAM9FZAE4Fv9miZsZOCGcSPXzPg8AtTN8oGQLs1tJKPJX83Ht3tgtrS
PgHIWar5edLlvI1ggRRWgmD8JRc+LMwyAuMrpHOd/G+gN2KiDrJk4KCmoxUKcu1l
ZS20VKkM7RiiDnbGP5bR
=7iTU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4757523f-0441-4be6-a9af-f06c1a7dda21',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAl1dtycpfYNlTtLI6yFjI3VqJ/BgdtSLY8MuAakxodoI4
XZBYrv0gFbU9RjzC5J64tb5si0Uvn6BC+UO/fANMBOcls4fXRZ6W72/5LRDwpIY7
nUw1eKCzDid2ZDpBXVLnS8xE7cJt5hc5oAwXAeHbj5ux3UVg/yxtn9Inwe3iJE55
wP5knZJWnFQ8qJZ6eMGOrGZxGqxXxIQwguL32nMD4uBPxN4odkX9cJnde++vlQ3n
V2uNe7ZvTwS2iyevi/KexcOoPBw6avEeth1RcDLPB5SsTyp2JxpxsIlmijd5V4Ax
zZs3VAgW+d9ozSN+wLbcwW1s5EE4GAxAm+YhxqorRykOQLaYjTojwdcaw3mG1qHB
Ncegdq4IrPK3c3MDwLAg+cu8Orp5MZpzL4iSzh/H5yQ1DtXB39K+zjrh2InHE0/H
LLoTbrS+Q7U+6Mt3fe+0duRX0yaoGBIqVzS1/2CNa4OQhe/0YmpZ3DmnIn1pxjSM
cxIyhofXouLtmpECAOec1xCLnSawtzbhHfZpFlvnp+pcRiw8ZuuFThQxuz4X+iE1
RiNZ392iRfOeHexyxQXSuimV3JYf93euy/iNDRumo/xJrqwcp+3/WOqNIxnprdtO
TR7+MUs4wgpAhJJA3BiUKkEMiAwWeeyHpv3IsP/VtIcL/r5NMGE+uVaYpXiMwrbS
QwGE0uvsAOYl6FevlulmlnVccwYw/nnqTkM7HoCocvfliQkd6ZNBOSYv/k/VeQBa
gyCNP1VLLP5005xiI9+CkAa9EQA=
=I4OF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4bab2be0-3ef7-433a-a88d-b7403c6ed0fe',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+OBmYZ9tYmPzFjvWZ1lpLr5txfCgoR9KhP4m0N+7oHY7L
MNjq1KgFe7KSYpDreEuab0SvkoyWAtTgm6+l0hJ8/+SYPx6314E36nsoSYs4N2ek
BhhrxcIYt9XOMxzDv76Gc7ZbJ2xaEDVdh3tQtDNF8VdGZrPzpMebtnfz5E1gX12z
Std7PELve3WDiKyc1HVe2McQp8erjsPJF9h3e5rFQuEh5mYUQcgGWS95LtqAIhjH
c93edthVUqkU2nlOaVo4w90l/UIYZR6Ky9HcYHBKEErC5XHGG3H1FReiWiJW8RNT
R+p4OHoxXr44HN4ZMqJocEWhpOT2rhXWHzmGJlwc8CSBuwStNl5qNr+BTduUPnpt
9jBPSmS6BR16C/P9j9EyBAVJwaAD/LcgnJHJneaWg+tOSuTR/ZOugcNIW/+hyJAv
wxFcLt03zMT5MteR586/nld71eyQ1kinSJeKPKKjMyEvllqfv5So3d9mDUSsuEfw
13ND3Si+4U+dXldvaKL1SLfhX9V5Hg37YP0T5Wjg0rPFqEdCbhgVSNsYY9+aB+XW
PzDKC9IQUdJMqS7bpdiQHPCh0V4oASNRjDtiv0MLAyXc+hDzDTgmt8r0L4g6+rR7
Ie/UkrCIngPp47vX/JZ/4iL+TjO34cQhscCHwNg13qXVoz/WvVZsvEzjQW9LeNrS
QQFKeBqcAQvzfRsmVpXJQCo2ObUtlFbrO5pqvOkAiLCsTFQqzi53PAHZ3fWhLuOI
dHBGjDiIHDX4g5Jv8ergFOpl
=xTDW
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4bd63aa8-8cba-4aaa-a2d6-81b3f4727c69',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+JWvvgyFb3rPWfaXTaYmxw3Rw5loWLCycW16cW9ui+PEv
+i8yr8g1uqigudUO0BNLq0qSFu7KiWfj4+vWZ/58X0yTFPV7NkE7OD8NPJG363MB
LQ0N5Ka5H2m9NEXhK3YukQM2dQm82zONFGK5lx6hQdH9DHqOZrp62NohvpSNvMvw
k49R0vNlZvxFm9CTiEkIjQA+Bku3fbAyh8tXcPdraZAvhIdp0PZGVGdbEFHQl0fd
l1WXFJORsU2VNUAvYbu68Eas502t50QIyYhlwHmiRdVtun7J4tWPSiXgWaB5jTKp
njH7KFVhcSCgLn5a/qfeH1L+dBAy1QKGT2hoFFVGzBbHq5qkl3QLUUHxTObAA0Ov
cRueq9G12qVYl1JaMbgDHNZ8bugiyCzBaO1kMFRfcYfj+CywZfsvrZM3+s1m2RMK
uJ/jkYn09zeEoXS36lLJRatXQsx6bI8KA4yTmsAGAnFNs5tLlyVo66AEjKvVQYYI
HDLYdjCbg9b+br+WpAGDz8SUfm6Y9u8542wUkFlFcFrQbLixENQwvpxCVAah7SE/
/UOmrFVdhTETeee05+NCCe4KppANkHGU5YAihaRhLoGjDLH3wo89UJ05SWFDgfzY
HnfqqG4fVeR+ipKi4lTdNb5sFX5QdFt6SvxuN98L2oLKzS20NhQx7yG8vFEYZkHS
QQG/dk3NSrnf9y7GwDDNehuNMIVMBP/ncbCEBjiw35sQaFbiBETCBj59zMLlRi//
v3amqszdmGh7VnzveBZdxfGH
=PQUi
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4bf6ff20-6fab-4007-a778-0209a8ab0c91',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAr68RNuLPqyLj8AXJ/zpB0SnGW3gqxxtTTCC5iNmP9uHd
hS2wPZAvwTtTceUjWFVMxy90yTmucP1Nsa1vTy3ajtsg2A6rF71O9/kceqrm8Uyq
KlEYIHAL5ahuDRwiZirCppZ+TKh3++Ri/3zPYE9qrbi0pk5oCuheggF0ovAbcGHn
cwHoxw/A+5d1A+nG6rQcj/eujDIZWxxDkQ7QajqgxI9JCEZpEOrGEbjsD4GrHqcO
znV5TjVpkg0ABbAtljXELZO8O1YKxNnlwbF73FytF8RtK/re3wJylaVC4PqZ2ReJ
H8/mrH/ubw5iPpAdXmvdDwbVIYsiM20CkTbLbF5XAAHtk2IXNsPB/qWQ5mQEL/UA
pyyQrIe/EOTwWQxh3hGhl8WzUwGnTYeymr1MRll7RgpnFh0Q6HMSVxVOHGhLJmzg
sxoovCOjMmusSnaM5IcbuWtc7PQMuQmdXOUkbySBtYu1YNdIouOb4rKUTTF7kO6e
3mwE3QPss4k1hO3O1StiAycd+YiFbz3rEpFFbUX3KZeiuOePqo/azlGJzyADFyp0
8f9cXyPCzCHtOH12+AssvJUa9+1IZrT3L7GW7VVUbSbgmpKfVHuEFHM3DivvUgzb
ml5loWVV1NgwkOKOXtgSf380L8UlHyJ4U1q1EYWLi7iX2tBteENpbwOJ5vF4oZPS
QQGXN32UON3NJ9mGdouZLkd9kvk3UPCs7xL/RPzSGt5WGkoC6KwEkK+idkhW+FfI
Q3bwhDKBHYWDOeMsR/19L4s6
=1c6O
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4d1ce28f-a99a-4dde-a5e1-93275ed97ec1',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAjD68dfkfnQ7IMK+SJEM03QIvMnkfEDpjJcFPxUPx91WP
GUJd/fHvWcREOqtOHGs/K/0Ibwy+R824RfaMDsKedMs/MurofK5ue0jfo+YjDfhF
25PHOcpwa+HRQ9aTRgnPnpJKCtzhbyv0f6Cg9R6Ghgg78QmctxsNMUON5DYK6DYn
pbWP24SlmYjlnkXK1/l3S95l5VVT+XFhjcqHGGUkyDYyTvHjBPlK+Jex07AkFKhE
QmzkakaxP4tSSYJqrusag+Os5uhFdelZI6ZQOGDbRu3QbZGnf8cB2jOrANuhWkH0
s4+WL+bR84Q+2poePV9Y37wx7Dkffo/oFckmnH7GPnFdjJU+scVqZIpG8Jrm5gD2
QG9J4bXrtUnoIM9TyHGZdKhDUq1/m1Q/bYZ1gJMuZwxadJC9w9PWqP1nCr4I0fSr
mWOm9DsdqMazZqBuHczp8M5GEEB3g0qOeiuwTLlDNLehtkRtsorZSSdT59+L1B+N
0zteMsPlRaF6meQzFWFRBGE2qMl7019vrSO+7ugMof5xkXkEJFTtoH/nuIRE3w+o
E2qEoK5XuLGwa1Wiw78MYBmTgiTd6+M/+hUoCb4b9WNX9fSMhwOoTJrE5uFsS5Ux
XCCMXqxBet639NMYUblDPYWyZxO5SbvwQJT8oW774w8ib925Sr6wErnPRCwMbxjS
QQEOY7Fp0lc4I7WytEK21ov17WYrykvdO2xHlAuavZKepVAk7NqWo+dFH1gOlTBm
g7KlOOmwEPQMyAjRGUc3zp8F
=Xp1F
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4e26b04e-a6d1-4584-aa59-4769f5f13245',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9Fp9EkrnZG4IuxOC7DVpioSDAZOmDMEHe4rAVZIdsre38
hmcyOM0Rs8DOGq9eG30g5aY9HdJblRYnnkrIOoa+tjvZwCe1uoSH9elmKpvzH+ST
iKM1NJgLvpqLvt1IfzlRtTX83Mlzfkdf4VsxVUk3adt3C9PtHPv3nj9Qjzs8mBJW
Zb+9fNA13pRTjYzTFQb/b7NSEVK0Tk8aIc+RLud8+zeyZdzzjkrW24Z9Iypkid7o
mqNQoSagdRXzFH97llbSmN/gINuVgf5Am4xrj5fw5HWo32SCmBYiHODVEOtCTQdE
Z02KDMrb+FbHDog04++BVvikUBOZFfi0tCrIiOKF7foZGjbOnmPZWa3gmkoBtar0
OVmejwvCjZNH5Z47iL7mAra4phy5vHpums1x0s4j4O4uiIKwDUQz8X0HoL0aYLJm
3II8ZDPnVZNQP5fEThvgqEnPYS3GjkoDz/WJ9z4sclpHT/Nh4OpzlFestVqX+rIv
wB8r2Sxupuq9XGB9AisHJIoMJwISulPdOiVaKbcKacCQgy6CvYf2duo2xkGgOn1X
GgvSR46O41x8PMnlO8/bOnxNJ5ja3ii55LVPN8l51w0SCli3M56A8s30dPGZfnQE
KlU3Azw+/MiOEW03JYxiwGiekZlERyA+osEiGP7uwvgXOVjGX99qFbi5JdywBWHS
QQFdBvWbfzQ4Py4S401ae2w3ahTz/nrMrtaTPFBYeu0OBXy2IWV0J6mdmVfSuV6f
wswe7p1GMZ9I8kljpq7g6kOi
=RkTz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4e8f6a67-2e05-46d1-af4b-35241daed264',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAqIcdBLP5GcstmlfacSiRu+BmfEI8GRIsVy0RmDvLM2Ua
vPLj01hyxZmE6aRt6gmIxLWrlCHn3To0+g/anraLJx0NaWloG+1GDCJa1/2btcb9
o/k7fwh+arx8ipL0eFdAgcORPDjsfensuYrYBEuUtja8jJqK8C0s23vwKXF5y+1G
m8N927S+zmxa82NacGzbAcnKC+/nB6A4XsRQoYCyB47EuBy0RxgoP/Pw5gomQuhC
6Vp6Hp40XnWn/duvRmBbPqwedP0dOxNbFUhNyVMlPfdrtaBMM5jAnzZK0nnnAn17
DcG7uXRVJUterCdsz+H9jIWIx96vTRiNX0TlPhKo29JJAQz7Yi2ZuKM5YpUpeOJD
pupG4rASw2oDgTXGoTxw1moyUI+yZ12N1qy9DGZRQ3U9ZMqCGTBGjtDs8wADUhKu
B2kebFyra0zRdQ==
=wvX+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4f24961f-21d1-4a37-a375-4754d32941bd',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAg/aUL+01/fx+3CJSeHBxpmqnwW/xYR42zSFFyLF2oq+q
0g1vaiA4ah2umuBvC2mo/dd4gY+l4b2V65wfVytAUojSFWykqgnsrUF4XccDuBs9
63PKaD0dKHf/+XPaxTIv95+XKEAe+5LbUbWL14FYY/N1qtakb95nNvURqku+XFfG
0BffQ2C8fQ2qJ6Euku9KkHwcEyi0Em3JaXH9FChv5LUKN3p02tNgAoy7bYzfNQPg
Pq9HLNQRMSPtaTj/0MOJbCfaz0vGIL7a9NHVVeI/UN4Lz4Bbu63YIni+MdcT7SAU
91sxxSO8UYm7jB/vBdcKS5/GrHboaaUWnze8q/GM8ocPB6EuDwXQxxh6PrBCZGNw
INH9y/ecPxOg/NRu7Dz6cYVgt/nfwgEgU5ep4aAPwYAkJtGWcDK3I1gJp5wSt3nA
mS3YmPTfNAuFrhFbwMIyTpI1koUH0MyU2FYAaCXUHFLoaqIWXoF/Jq41tWjfESXc
yo1DAfSo/bYgk3dqnwtNFTrJoN7za1bsTMuFRH0zbI0F5tTafSMcJPF23ilbhFpt
Ojy2kjRYK5XQ/c6DoFNplierjq1Pq6WEVgVJD6c1fh7mtoaTqRGgQnkWiVngvlYC
piF8jL8xNCzdJBUG9qhs7oJ/kGueA23ujlbIPiVVuc+ziUXtlVV+MsyGabZjmffS
QwFqmYxW4Z6vi72RSBkElax0i/fowIFlq2kMJyv6IZoYL9Pnx5dc19Z+D6tcBp5E
kslnSoyhX89dSc67BGpGdmS8Bsw=
=biXu
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '519c997c-8cfb-4534-a2a1-1aa4f612f4e8',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAkRLBZzuX2UtQoUg7Ufa1g6GL09ceCR5tuDG8mFUoYdW+
0NyxP4FsIleal/rXPCVeGhVcTDr9U4VIZOWpGECClgNzZGlXlmr/g7RHVIpl2Kmm
cO0tYgGVtMcMFCSArE/Ww1ghCNlKkLWU7gimbxHQoMbbUQQc2quoBg1UJ1Xitu3z
fW7gLYJKDkRK0PjKho4txMwXzEYmnKpb7djtseq/pnsb5xxQjxS0LmpqySt6Bi81
UUkLVS9QyrCL7Ambg/oS3jmuokJB8QV8mQ4TyJKl8wJEHY9i0xpBtW4mpsLQp/c9
bQkza2winLR8FKoawkcNnUbq7SY+if5iVfkAg67Ou33D75CBli9IkHvE/RP0heh2
EdFnB3qifc9l5URVbPf4FrzEhimMkB/oJJRIS2VPA3DLTGrekLCeKVG11IMfjdL0
BR6hWZ5k4VUbIXna6dJybzFXN8WXOhWiZBxUs0WFIYe4/6/I9fsc6uHf13blJS6f
QRw84MTpYHMoOBS85xeEzmK7c0OcUVbkp5BI2uqImJu9NNcFt/UJz15r2bKjjnil
H0Lv2HJ9yBC0LeS2g/IM8PijjQ5dvQ9xYFBzUOUEsXT4GjnBWMwKst7MlfkOZkjW
EeoK4yfk08w3NqHTW2HjM2KyarMruku6Y0s2l+mQAD027LQ9n2I4LXouep9rqObS
PgHvVFnqpdjrJXawiXDWW8tlYPvoGZ2+ap83s8J1dpoCifDwNXw+VkTQeZy8oRiu
BzlYgsxtQ1qVMn+f0NVi
=zOZL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5467e605-4c50-4a6a-ab5e-8aa4b0e709ac',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//ZLlIq2TtZ6aK4zinjSvViOYXU2Wn5CUy1HvFG1z7yUjE
YKkepZfSw/B2+tH3xXdQln2PVPZYKhPYlb9XPZ0n4y79OT4sT5QeWnfsARakpeNh
+h449yCegl1WuI96c8Q2mMz3qiRP0OyfoTkKL4C0wPLFTS1FVXC04UQsFr08abnO
3DlK32xhYCixp8JWZlH201NiiwTWhn3Vbp1iVrjcBackw8XifBAHL9VHpk5UjiOz
cJegp32dK30mWHHk2Uy+LxRDZBIhZI3g1rAxtcT5YDiEw8ImLsMfqFtpbgrrCya6
wb/k/8Vof6kWwbysQn7/BtPHBguiS4s5oAm7qnQxy0h7gorYbuqxgyfEETSckLvV
Z3fpJUy2gGzUeHswFBKUDvUtebnC3dn8Nb/MX/IT47XKWZHhdci8ztAxJSTpMLyU
4hFsbakakWBD3JhupbQB7QxIcP1244CXthqRJwuK24Iv6Es5GSGSke462lwm3GrS
gRHhzuRmnStM6iIwsbHHxvqwRf1GIn5U/lp/QluhLUsZ4/OFno+vHFlSxIexMzwH
6kQvSColyE7NiENqWEdhuyCWcx8udSVSMw6nqF1tm+0HcApKyuhkKySL+LGu7GLk
dadghC0I520I+xcBbT8JQUqYuAqNMP+T2kYgE9AB+zSV7FELLpvntIJ6DnTnfUDS
RAGXsnVdybsAsUkdnCfOjpCA7ZDBwM0OEEtqGW8BndXRPO92EvmAySpK5Dirt8rx
sT8D2D9xczYLBE7CO3J9/pfqzGqI
=bQLn
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '55822098-911e-4aba-a9bd-65db640bd82b',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/VBPRy4wG7q4VW9tl4GRlRI/kEtvAol/KTfg1Z4oUplrO
yFtu1B87WUBi/G8ldVeDW3nn1IEU/b2MA4+3fcEoylhnDc6uxU5PEFhF60bryAQl
bj8n+yDUm0WbYS/g+P4aVyCFJ9dlil3QrZmeexfy22OfmgllGEAqkq6X2Jm6yv3s
wPlJxay5XEujqavdpqiCNMDWrml5LPjsWgqcsNPCb5GCqYeIg3GrfM4M+72O63hW
r6ed0DFdqL6R2DB5i+AX79xzYLhxhVcA11Jq3Xh89LJjUBfK+yGGBipHi+xZfr+W
yVco1cR7GdiyZsaQu9yZnNqFj53zvmNgMoZNuJ1ez9JDARGw3qmSCpNnjwYk7kx5
GoJ7AXhS2sG3uubS2sw7kYmgexOJAdzXxQs05qv6e0PGjiCA6BCWJcqS2lfcQfww
lJ45oQ==
=fGf5
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '567a9025-da1e-432a-a38d-fd79a5c9dca3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAsrXL9GeIAZ9TvayS0tX/bteiDFS2WwuodU/bhM6BYBec
oUUS2tRwu3/eErwecs4PHDUFTjsYtIDzFHMlo92XmCJUjws5tPOjhWbULoV0GIaR
KFVeU0ctKXfmA6RWQ4WfVnPu1APkbL15yikKnwSch9CipegGpbJGMBExGpJ7oPJe
SzoIiJUwO6FSajAW9B5vKiApqNxX9E2GjV8po75jahDIYDnLyc+MSlSmvSfjuGck
ompD/nPSyYNxO4fWRMVeZoVHbQQ9AdITrtz6yJcTm2v4X8fP5FXyT52RS4EZuZDw
7H17+EU83j0uSCHf6OemiQ7upMuSyM2v1OtG7yFGsO6B1/wYaXsI6My/F6Gng8lX
K8pyDyhfS57zxF/q1YFKGxyPbjQBCkmA3bCEdMGX9QRZVTJ/qybFiDfjvSGUoAlV
jFLva2l4EhbrS0Q95DGLtFYor9IlB7hPMAZmZbUa0QeW18M2amuKnEz3bFfjoUaQ
e5egFinl2mFsH02o8ipqoPu/zvuNpRUJCdaSMAlL5lapiqptSld7QvzfNHVTBXbm
Px0wqc7We8XtZjUQxkTFWbruL9X9N9azLmKwSBTCboRffau/CNTmQ0z6+7WTzviS
+TCPQU5bTydeps5agdUuVFpqqz6i1C8rr+s4jYVPKyqTnr04DrM3MKiyApZRDCHS
QQFmfsohqQP2Hgyf98ImNOmCPITGl14Yt7tVBn8SdtsEI2igpW/k/KMuf+72TqSC
Mfh/BIYcS84CLYv33whHMu4n
=pxnd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '59ad091c-f4f4-429d-a12f-71f6eaa53860',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAy1Zc4i7dgaWOaiGO2dITa5nLvrzegqWfJcTWkKh5IyyR
3DRnp99bh53Q09HjWywpU5ZXlpVMbl1b0UlHVotwqMItCJj03KjRXCLGUdLydrqz
rtoUQnBtpFmarUmBDU7Y9yiXhKBnFy0HVYn/Bd3aveU4vq5od6kGNf4PHa32/kM0
GHAijxjVH4BS46IU6cmIYbPCZmBPasFWjE5YJLGejBp4ep5Af4vh+tD9VkvMtCUz
5VwELwf5Vx+jFxY6SzT8LXZuCEwgLXBY+qX1yD2Mau3l3mZjmZNSU9ofVLPaL3HT
9ghEFZiRcvC1UizsX5mnK8lT3y2sXNHX6Gce2IRFRjPLtfPwHxFRUtLaTcYGPa+H
uoxEsCuR5K78T5cqYRqg9lE7irHxZ5ngxojg2E5jOYIrrNJzDoXmZxDi0Plk2o5t
ajyMxzIBdWn+DccdBwfXYUlvMlNO5ZvvMYyPArLg+YagB/2xERDnzcbkHHRS17zx
Pu9Zofti3cRGBwlu6irSylPjI0V0lc3KIb0DsrlhxNOOTAdA3BJQ7Uxs2AFWuklw
JfaaEkqtBuVuhGbRZZoFUwiwSAeiPnifpGQYOqtJBHus2hDtpWEshB3GeXiCJiwc
H+r4QPsWRS7COsWkoBnJqjhIO7/4MpnP4YY97NFFe+I9LPCycMM0JvnzBuO9YdTS
TQFZhP1gdZVou/AwECp32nj1Ao6YUFFNAZh0A5H8XzWyxJpaSuHXxXhiLZrSnZny
PlTfPsV93nHzruNGQyhecx7Z6XD9BUvv+4/PTsrK
=acf9
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5b873c47-b563-41df-a6cb-26902af34e2b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//UYuP6EoU/RN/dpSz1CatUj6PPu8WCa6n11dZA72H7LI3
INtjJFq/TPA2iVlRbrxnc0yJf0Q4ZshitYVAJWT7Dr1IeU8sg7lMMW78tRRfiRsA
WYaEq6Z1IeYWj/UJb9VN48f7tzOz09lXg/QsxR5tkksmTzWrLDXjzYhVYK2zJrNy
6mxSgUIUnVtQ7eDKqSgVf6zs2ils+qxBPnrSRxX3QA8uvNv71bc0JcPLcyeQdOWl
3NPx0XA5nYx5WX+tnww6OvqfBxbOV9GKVzcbQCqFMn5Fyk4Gvr7UMmOt9N6QIkcJ
76KnWway3ueLs+1nHmHR3Arg0fGa889QPvZewhntpg0qzpv6s9ziREzeS4ONJlBK
XkzvkwnLBlf40NfqSAsnwDiye66wGzSTArIR7yozKompFne8c85f4P5W8knzsU6y
IVRIneBf7yZAmF70EiBAzV5NBe+eqdRGGy6aKaE1A9ArSYJ29d0tf8w9YWpunMt/
+OH3+FCJoknCbiFa4tHxYtMUJmg+eylH3vGoOgN4olEnoZ3s5SyrlC5PXXvF+wqO
ltrb+tMdEsmQg1WlcsjqG9HuFJhjwieZRlSvzb83FG3ZsRDaB7w8dG/7hJeEDLda
y57tQidRXnG3lfHp40QhdO0ekgCJpJSltdQCXeFRWAm6Jo9IRQ9ZP3PsU21tSULS
QQH+rXyaJmAxzcLK+jpN2EuaDYaSO6Od+6e5MF3A7idRREQhpPoHPp3LyDQc7Pzh
wNPvOmnvjk+9z0yhFEOpWAeL
=Cr9a
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5b95e4e6-2025-4cad-abd8-cba96a0f37d7',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/epLa6zB2wWUuVaDTeZZxdMjL+izVujgrvUiBW6h2zVUs
px1NEQ6envQk+6nIEsHuCZiOpyeiyiJQK/vriRMcJW/n6oTu+KtZT5z6xBKh5bgb
qq5ju55R8GRJL0WqyFC3tljhdCm9uXmo6HYlZFrkUVsfeqkOuh6L3/rxutJssvNL
0OfbVqyODaQfuGnDptC2x4AVN4AY20J9MrrBlaKOT9M5graXnUXJipy1a+U5DUHV
6Q4SgathiBVjj85r3yH5iQnmDwpoMWcYBwWwqksWjjsagaxZmdWsBok1ROJrdo+p
2FhoJtw6gnN6DfKrbipp5q5Ochnb0m/CvbkdG0Q+CdJBAZhuTz+lWuDTNgwQ1yy+
+XX8addT5cLylh2zwhFjcnnqQbve8GdgOzxGX9Y5WTxW6583EsyIjLCl+AT5HBS1
EKM=
=i1dq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5bc5f659-c7ff-4652-aa9b-1c01c71ac149',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//RqD9Kkmu84ErYeqlW48UD8kl3OdYF60fN7YPzHlYyhPb
jIoWO9ttSJhjktWqZRli/ZR3XGauorLLWVGboesD7E+CMDPB/uyE/7EJLv6+soyq
kYuIQUkm8T6VU92S5+D6cr/pRcBw6u1qdWApB7HzewjL1t+f/koNKTB+h5UWutNw
TtVtLx5HB+p7yU5OBYHs6seHNF08mvTJx1H2MYQhU+SandMxDLa+D3lipm5Zun4S
AUfHhgFHRLRhuw3rXW9kyLPAzJOpndBGlUWYM7OICLU7jTWMPRzlze29wr7z9oTn
sbDVHrE0VJAxy1ZjpeFtqiMTzZetSyi/K0GKm4EeJ2VdDFNQl468X1qoNGZ2BzO5
U2MSS4mlGFPyfa1+qaEUe+BSLx+Q4bcCXhw4NjnkRwZx5+BI3IPvwuyfHy5j04gg
24Hql3L4/glZi1CrWH32FBSEyCSW1Qm2LY9WxT5A8QLB88HpZsDKCMLU0vbSSJ20
v82D5zwylDwDYHp/tOgrhDKDDUr2QZOYuYW1/3jUg2w3/bfVO14mbQc89kdKZpJ1
v2+jZsBcbrcJZRoEBQB6ZXMmJeG3Dhtx/d0G9lo8VW7rwW/IFdw0ykRivfeH+BEM
I74WS+BJAdKHx46UjJGxgWJX/FvHb+fF3/xwstyJ68H3fcxxyHLpDBuOFOE2AHvS
RAHGHc510es64iZakwMacSoj2Ve9zP2dTXfewDEdnC9w41u6zD0ReKdwr6Y99jTa
h1kFH/YkKl8EpR5BxCa38TBJ5cDJ
=KbqC
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5c4dbde2-677a-43a7-a16b-a59f469eb379',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/+LrpDXOi7+xdBP/y+e8ONZPxCZK6l93zIekxE7VLE9NCf
kXEJ32hW4myMqwK4L07C19tJoU7SDn2rd+T5D5tMSnJGRFpnLU9jkaBLQaf6yufy
yB+KOW9b3z7SP7GfitYEEPrb3vGMli2tuSFOCbkMFcrTY7q3+w/ezr3zPt6c15sO
hdkUjjPRccQd05Rz39n1BrpHy2Z++ATWfrLw4RLLa25O+wUnsjyn3AB5rx/T13br
CIPCNq2cUwA875vllHloio71ovH60tMMy55ChSLmNC/rY0n5QW4zCAKDa8lO87wD
3s1pTMHgjl0t6Kwl9Lr9+gPziVEaHYjJhS5kP2+4Ecbf5kQu1WJEzKFysxMeLmED
y2GA1ohTZqgSpXQrv6580ZvYgJ8lugKIvs/X+AcFmcvwh+QrxWX/uXtOSHzOdMoH
VqjCzwao1OAvD+FKQEHb/hAdmEBkjXhABAIriYrIuYOymvZY0Ul4qihoEb+V5al9
gWA1MYK8eZ/4ZdRk8Hwav4x8nWvQL0AHwHxgz19v/kL1Kfr82wRnmkwXSY6pIs+B
e7m/D8aUJVa8U26dTzmaXpmnwMYJYdFJgkC28eH60xZ9qcN5R0avZ+rCO87j1nsQ
q/e9Wd95AE7TxlXio29/rozdWzTgmBfR+BW5sr0hGMiiqYynKbdOv28A+5aqLzfS
QgGW4YRwMusQwwvpsWVu40I70JiIlnGS6ITG2QhN9F5FVJJairOgs8qDydh3dNfP
08FnARUje0U7yCwXa7PvEOtArg==
=Svmd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5cd4922c-2393-489d-a198-6b41a8fbb074',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAiUdwnsXxxZ6H70POkudl+MM1RjQLEzbXbM1h5XjB4NOl
h2BtMxwh0nQ1qJgMpYSJApyUQWH9ORabScZlooRNKUNB7WBShwYbByiTDm3QpOQw
qMxehMDRhg1ScW+mxICg8LcdafzV6xYHB7YSPdLffpwgCyGk1+8aheoBGGaHV2gK
Au9H4/wGCy6bmwi1huVliw6O00zlYB5TZCJAeI4YuDANllb60a7uNpmLGBBjbwqh
eiBtPEK3T8HM5YEihrnYgybTXiUf4bsJKmKiMUl6Do8byWpS3eDNp+UQc2geCxwF
v4m9q0/sl9clKn0B/znph4wziCYWKbwXakpfElKTtlVAaKSJM46dWcy+1WrnKJHN
7Sx97OL+JzOtqhOKzgGfsoCnEYpeoZunGHxJAMlfNxbosOywGe3NVoSA4wigWJUJ
Jod7URt1Vc3g2mSXsSY2exQB1s/Zvtq4b5xSKTG6R082eyrwxLJxrBQ61OLMAYxI
O1L3bQn4vGUtW1U4BIJGC9x7djgTzM92J0sbdQt7pripEtPPzP0DmzyRf8+qyUgz
4mj/5hHuejb5NzhOiusTW3qb8oqhbnzG9lHz1+cJPw8NYoRy9lDIBPgRTG4axuEw
cMeGmZADE1Gc1WIZ6xCXId6zL23qqveYIanntwPHL37m5x1VsQt121qMz8NtQOzS
QQHgqhlvuonRpOEvmh0ZKxHKQmn7PEanHpAmG8dCNWfIolWn6dAVDLiD2VPT/xQ0
EH77zOqplBuT9tgp/3inhbJu
=ExuB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '60bcb100-2389-4340-ab14-be3a27a0d2b1',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAmOBp3O5Rv4sJ3NclEAfkxaLbvHz0MI03pzLfL76YtTIr
zhVsIRuvkJpGY31dxVeaoMySNvU5Eouui3g6Rd6gfpC2mj3/OC0I3a7p6gtbkhJW
cVM+h0vrULrmXj31KdopCU9pkVW4b2PBnrEDbaLXUTdxN5A2L9b/ujvtAzQH9HMD
T31DcZnsKc903WLp8UgiICrFaSkkA59c44JaSdnlqIZZRMi5yOckSyM+VvYU/vgy
VQCB/c5uFZIw7Xjcy8OL/sQArr3tOpzaNjwLVea2JKXBbdrPA8c0YKYyV/8WVXam
zYDP1qs44zO19Pyn2j9LjlvdbkPXQRINwZI0TGOkUau0o3V2RMfissD4luZRGY7/
b+0p+oGeoGxeoe7yLonPdRFhVNAIS6KGeamrsR1A4q46+yAqjhdAgtTBq7pbeCNw
0nyRpLol/8l1d2LOm0UqlgAJt7j1o5VhR+wsEBFql4YKjyrkVrK+rJqePCwH/Odv
RwYV/tGGqPtQlvwCPfaDMTwRoKzAl0Wdc0YeX4LSGjUgZ3MMHAA0kgS0pDSj5R3l
Cc0SS5qylP/TqTJp//cNuKFLh9Rex68M6xaXULl+jXWWEhX6+luNyDr9H++Bir+d
ivygHpzLGWe5I7nVRihV1OIkNb1dUfj+oHuXv/rz3lE30JQFhvaB2jLJG7CL9zjS
QQFsn7hacUxkzaK+VNPu9vDVPwT7ecQHHOlPy+K/E/U52GCPYsABTEjzvsiBUcY9
+WqRvi2SscOWYotTgfrawAUb
=YTm/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '625fb92b-41b7-4cb2-ab98-39fbc7e98b6e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9GczctbKR5jG3qyeDrCNwAvs/hIPHwueexFaP+aYRVasA
x2Gl4VkUUPKHsLQQjdoOOoYWuaXFdRFpw9Zx13vDuOc2NJZmJI+wij/lKo5ihatF
NdutOO1kMY+2pAo3e1n5JFyM6ZcDnZjBhyTgTIwQQdom8f9YSzpOLpA7Gqo/Ixws
K8F2N215CptubnHXofM96scZJ7GuViVa77Fpz/n4831BEyNVbIv/KOzAnf/8Zr6c
zZxdbeSy4Gv21BI6GZstA/EWTnenY355QUPSpA0ZLXT17976BTHcp7xu+bz4pYjx
rGf/IpvOs2lXioI74Pd2uoommnlyW1wwfmZKrZ9/7KbWdyqa4w4KczTKMjxvFsUO
Q67kygv6J5uj1kih+8punb3YVJUqLAvH9ueJRTqbcttguhpDOdwjOCYqF/jd+nAY
7BEXnCUue8rwHTtHgzTmhiwdc1YjcZYorBQbbogVtwjHRpOKw6abow/AJSv9J85M
COGpqifU7bLmAlGs3uRvJM9wbjeHTQDiHS2MI29UbSLzYRJL9fezIf3NROcbXuFk
7mBY3WAy9XDPFhCoVt/fS+QI4i1bitwnad4ey1SHmch5cgcPHT554SipJxlLEQAA
xKZRHu0X8rABMxoVDv+N6jGhx/jvzo1/UDY9y1EtKnPUXJyCbUbUEGrM3GcI6jnS
QwEh/1lZavUuqoNpwLvInm/UNqN4N4kIoxG36FN8Tt+jULo1vYuqf8uTaDkY1Y68
72RfjJy2CNqjyVtV+s/vKyjYcqg=
=hQ4H
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '627c2e56-2456-4b48-ac0b-dbd798fdf604',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/8C7NwXVenQWl56e2/8YM20MltAcUppPG2AeY3WH9ajx7Q
s1NFuGUQT/phIYwLYGKuPkJn2YKpbcxSa3eN5vQV2bYhdIi59qabS1FZ0p3TeWl6
nhA0/ckLUMigSLs42heo/Ja0JD98g2/tCfvLHcG9llO8V4Bnkqz6GCtRRfjpNbms
/cH9wDRzrFtnFp/NXcNnawa3Qr3n5wcs0QDM08daQ7gUXO44xbEMKyD/ZZWKZzC/
shyg7js6FpIiDDhrxmoKtpJRubdNoJmGWxuKgKJ8NWMcbbeevgLEAKcWjTwh9XDh
RGSHX2BEFURcbdE0vWAzvnBSWCuu8Ku3VCkueuG7d6XKpdLZdpPg6ie3hKCDLzCq
t0i72qGwsaWwnmB/o65sgOyjaAr04a5FGu1JTlJ5VxvyoEhatSgOnGrYeg4XojU+
daKrJ7lZprGt8P9it2ofnzpNL8Z8all9hViEVZNtKEcVMOCR8SWAWFpsTyCfXe/b
W8ySxXPd//6/Z84PCfWYGIF4ME2Owr2rcQfqOO5pUqYUyqA9Bew101rhWlIiON00
1mNbSzXt6YGE80do5JEgbWLqBIQN9zV0kBck4ErE9L0Xc6AACKaQIYzG0xA9dD3n
C4hywq1WCvQJy1BaE5TvHL3bl00x0rJw7NYKYbTpy2st5RZa23Hi/+tQ3aym/hXS
PgHAutVcvNhS5fmNzbQwWv5Bypdmm3+ijs+2I1qHuZw2UBSAbILZ1ydqvAWBDkt7
ieRwwsUziA19luUDSxog
=8uVy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '630f2e54-0831-4ab5-a56e-06d7f72fc6f7',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//REyBV3I6JYFDEcLIx/SKduNtxiOc/O6u0Ka9LzlbO8Jq
lTyTsPGDFf7VSxPVSWcKrVS4EPhp1E5vLUXS9LZ8MELHrKiqIMTeQNsHnGFgZnJ4
51WI2Cp9qzgkQkchm/oS6pOpaIx87PsFErFWtCfE8ZbdaIpIZHBJBrx6AIhAAcWs
nSSeOYjRWAqsO17dKThhe63esL/Ba5U2TKhSXyyNP80Z3abn/3Kun5X3Jc+fiWfd
rbwH+HuEkhN3SoQdKitFXohZtrYoBY4+DR/7JzvALuEUPWHrYItOm9w/B2ekhYma
9nIBkr6UGEogTId6B1gfNLV3vL9LnyaqN3K+WayUzoZXSGXfdt8mD77sY7V3faBO
GDSffOMwzr2O0ieR4QgUWOb8Fr8tTLmj64v4GQau5HrgN92I3/7SHRE3o5axK5rH
Csx0dB/UlMStTRY+M5mmRcdM2YK1f06/fdKHJ972bhdUzUeFHQSDUUIN/ycvpPmz
8UA59i52xiEG7FnybZj8cpK2qKgiwQEvO+S9khIDtMHdj4uap7VbloLbYmj5SXcS
Xw0uRdD4N/AEmBdHDoht2jYH9s7QaluO27VFTeL3lTEiLn6nUqD50wRHxFTwN3Tj
gnzkMuBilPCMrhK8k9eBDWOWjwc3j5sXodgglNF9r6wn9dY3pf/3jUVmEc3rWJDS
RQHxYmXsJw4SvBDGp0S+JPmFfjrkP9RWlJ4Rhf5fRXDPCvc+e7PugkW+ohMuroRS
51I3n3hIM3jsDaCEODL5i9f9IRslpw==
=ZQh4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6548bc2a-d60c-4dbb-ab37-92d6e7aa3554',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9GcnXGglX+N/nzoGyH9BYGQGtd4J655CLssxhF7Zm/540
3Gup2TQKsmRXLMpP1tSqbEotHq5/yi/SKLGMQ0+uF1EZKiyrzAWZPznRxZ27lkKN
yTWpgXSJL5fSWlIPZJS7UAddvkccusmrUGiBZpwsxQtmKn9HEEtY39NDO2FIQW0v
Aab6cBnxM3LYEIidYG2FaCZz1Fi8p1m4QPD68Ie3Wi0Y959CDciHSERTlbrAZP4y
l3X6/FTkwYlWKk0vTlM4iDT8G6Vsjv3kzeMTVx+rXHs1+5RyDhkpFyqm/rSR6VSi
ZJllx6WBq+qKkswRen3YluAjQG56BGkuKyoxqQpELJ2BdfVo3SaSPsT9hmkTMRMN
XLPuzyVNjUG0Ue9EaKB9PZzq/ZZF+rtv8LSf2VHwCRPjmRnTyoq+N90k1Sl9rZeT
x51i7ggBcMsNns04e4SWbJpND4xEsbhNbhP1I6p8Q7Lzn9ziZ1QfycFLysfyZYx2
I/8T+VAmvbCnLns5wq8aSmmcj3JYXIxYO8cLk0VRnumz7v20wqa2DOhzC56Kbbni
vuvVBuUDhPgF8QaXtiScbQVaZd+SYld1klnq9hlsh7IcHoVa60swGGrjIBlPLO0q
LS95UMqHp0vZUt+39KIyue4G9c8M0FTRwJZfgdIycGSaxUTkj37ZvpdfMLtNFiDS
QAGRGY6dGN+pex+3QhhzuTSItHt0MHf+UDQZ/vevPkhnlhmq2gWE7vzD27WUtjgd
TLGcR2ZxVdlSwa8tMMn8VXY=
=LN7y
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '656087e3-052d-4544-a6b8-1963134a42e2',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAlflr6NBAI0IpKYBZ0LmgPDmA5aH++0bdtmpaF+DoagXP
5NfrlzCW3NIaz3xokF419JZOQCDT3RoKBgMm7DoTNOA7w9ihXrQ+5roJWYpslVWy
UoR3Yk6XgnX9LrPNvpVpLoKwZguzyp4zxtVB6gEc6vTZ0sKLAPB4EF3RwZRSfGcp
br+ECq6PZHypqyzA+0N/MTXeeitcRycObsZl4nHQ4HcQbN/3AAQSMNFkq1nZBurI
1D1Rkgs9GDDpdtWM3vhEYA8QxUBdWnAFtsEEylVTsL2JsV0ulrqPzie/DIBhyE+C
TM+Ejtd08B6LbX4yAF8ChgRLpR+kFVJPa2luWuCvYVfOSarlEKCttrUikz7i3sr0
vdck30l2HPj5rw5yt0Pl48sEfz2o6V9H8a2TkiD9D3/u8kTbW1rdtTc99RY0r3IS
E8fqjwdG2crNiGoBPVX5CU6MXygDiVC28jhFzWvDZXur1TMSDgtXwO2PyUBRrlTf
Qfj6DVAltbOPxK5GffkPGruM727oNpmWGOo48LLMRt8+NIEiTq4A3ZatcdLQlzXd
CYYfZZXrpY14063YCjW4T1qkwFzB5AgJQLAAtzlx0ZxQzRSQIhskISRTasLgUqAt
VVSvoGpfo0G0YTgvdxnTSmhBW7RMESA3q2kuIeP4fKbhAt9xc7YJruZzPu1qvCrS
QwEoY5fcwfMCQWjklQhyY5Fd+f/3P1IKMO6da7Mu38RFBFduUSqAnF2Pa27qFUgb
zJnsUycDriuw2pTqi8nwmFG6ml4=
=WFp0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '67000734-8fa0-4b0d-a1a3-09cb3a518717',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//TV+17G7K7246ZNhernHldDSRMzvDTcm78BvgHqxgty3q
nZ4M2sCPwZlVMIc+mJ696exY8/as7lnYA0mGrgSHht++IdhCsQfKa9UzYhrzblOr
dqmlUTwm99UENkmFLWVVQRhSghu4VBMx7GZjXZAflZdhJkso5UotbZmikt2Tg03x
gC9iLaJVXnLNpKgqkbi491gjrlp+dfndUvKI6XBadqkU1KswCKM2x4bbyXI8dZRr
7Dqu7/qbMc1wwvU8l5iMcZwIqKclXq2cvPA3kad6jcJATZ+fGPZUC7uRR6/Acquw
H1ijycUmvqIMQpKFYVW640z3F3T+tnblFZbB6a845WnQ/9GjFc/D2cyOmfhjjYpC
W3Un4sI946kWQnolXh/waCI6S46wlbvgSxclTawtbwTVUzu+G8THTnwfvw/35RBu
KQmQSNnSkN23Ky8LYD2XoVGHIuluGZ6O5tdphCtGXcMqD8tZ1xZHv86mqwqTIkqL
fQv4EzNww78No6wkb3yiidXf2bgufto4i0+mL5bi7EB+f/bnyGY9DaTlzT6ocSES
z6jn3hz65YXdUzRmdVX4YODt8IuFfw6TiQFQO6kiQfKDy/5BDjDwYQ7ojc7XyOju
hohkKOAa4ri9kw5UTEZAU8w538j0FoqWU9ut/g9Zo1tiC4traNeUEswIivFbcczS
QQH2ZCmMrKIXgiT1meyAwYbgBLLWkPBf2J1KtgHArWo9856TndjaIQFzL91mXc2p
t/mEDlY38mQjPaIC7x26duLi
=Tkfq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6760ed00-cd25-489c-a230-8d5a661a5361',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+IAVTk+sSfuRS2iTF4ztRMf5EFPpRmNnZczSTT8urTvyP
eb9fHDWBfkfW9SkmXaij6U3HI1FAQzlpt/ogk5lefP17nwIBWnfGpXHL7bORWva7
G9t5ccqec7H/HpsTqF4u062o8W7ApplkQY8WJxBAjcKv/qQ+E6ibpv9e0sECUMkc
DV/wjn6K+SobG3gLR5swuZWnWQwxSdZE850P8YeaFQH3JuGCYSOA43T+QojvjB4W
ccfztBthPXPsnvwJ2VhvKFS7hhhBIDhEFBFmPfPBPy2BXLrmYkZsMBxHCZLC/Pfb
adfPtlA2sHEYoKh8ddkZooZtZaP9gPNF29h7f4FdHppUtemYJLyZ5040ObFdenSs
ovvQPJY5z6AEIF17D8ITlCp4ohMnCJ4OXYLKVO22HE/kbpvxonqYrecjj0suzAit
4byaMC20KQle9G1fGSoRE+JSrgC0pgeB7yoJo1gXckZrRhex7XXpazlIUcvqlTcp
R+d4XesjaFB/E1BKZ7H6duK8R11ThpM0Q8KbLUbMyPIB6bsxbVkAWxnLhlT9GEAi
9zPj+l5dFti/J1jvcJvMr+eAgxerlrjCIBdxkBYdlabZmhUnCL0xAS+zJS8vSi1y
zV8fBJ4kgZiemQjevWsiyZHjJmTwWpAhFFhIx0hj3WlxDw1pNd4Pmci4SI08hbXS
SQEzpwMAuXd5tMFwh9RmE5TmKQ80ytDQ1VGrcQ7qECwC6QNfC8UqkFPhX2a+G1yg
WpBUmxbcTkmD2g6DGswghVsaSdrBMHwS20E=
=UkO4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '67a3c829-6d97-440e-aff3-9baba92610bc',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAs7yZeVCuXMf2l5daVZFtmwpEYCjGK24fHvMEZQiNQ6gj
LfvONMK5IANzYhbKAgl3t2fI2WoLUhaGGRN3+0zHkWilhhtz6Mem9Fjf2SBbJxvn
6rX6BLACS6e61PkPgfOtMSukAZuTOsSkjUalPFmJIdc0ew9Z7wScCF76aea+fh+C
QGi+NarNtgEmyXYf0BKzfxzymTATvYh83dh1z4wyeJ1fSUVLOVVyjm8H1fMK9N1G
XZ9626qWXpOnvWp6DcRQZCTDx1zL/uirrUnTCPWTrKIVXcGs+UAP+ciwwYd7zuo8
USBbiulJK4J2hOg2OjyLYvm/g+UDvRxUuZL1bhrIHUC0KBTl0gjNpEIemQ7bioD9
MzaCCwgNRqCDlMLWNd5cHq2IGxGEHF4T4TqsqtMPMSjyM4BbPFvF5t+hKNUsJ9YD
w6iG/i7V0GoID0W85rZr0dvoVWaMl5OnFzXINgU1tJWXazGnT32BsWSbv6k1TDOL
lyJirxBdFZ5NssmvHIidyVhosFZwIAkyPEIDHQ08fWgz+VO3jHlor2UcIrJRfakP
CPgHfGnZGh8npgibO7h44LyPXyXad2rYvYCEQP37fhgnN0sgI0kZpuLcLxTGI5XM
vJYve03NYFkHutYxu6kinY+5RaoKUEOgBdaxO/NylodFTCnil1qTANGTTXLeWOnS
TQGEHuaBLtsoGMn7Y9UxLSRdKTFnMl7fpViBzq2Kb+WYgjAehBlcMIczr0Ham69g
OSFlH4hjnBx+t5K2y5tV0QPFEzfg9sEzX/ruY07m
=OUZW
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '682102a2-a3aa-48b1-a56c-973ebe437602',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//UmCzWoWX9NgFVNvA5tfVa8uN/pTL8xh+AnyBlkodjVBJ
qyDyex6ixCOwKcXgJfygb3KrrsdJ37BYjn9lrXHBGNgrn/DsZiZTg/vKA1V2bfAS
OjXGgfykGkpwe30j0PJG3XqkB0aj01woAz7zftbKwhknBFG581dOzzYHUfvVSBaB
7BD+C27w/6iRjI6wYzLONmf3Cx/j5aqJ5pM0yJlAO7FXlHJf8w//00EdSvs/kyE9
ki1E3mbEEsSbzi/39JzUQ3ks0/cYvp9IMKdexfKZeE1vmAnV0XS2me9vVZS+7QK0
MB8c2Dt70LHxPOVQ1v/0vvuLEChlYrvniehwWw0VKW/uykl7Pr65h0qrOPZQlH+n
MkBbbSR+HMeu1v+o+kWPesXT0OspyhAOgrhb15aetP2NOpx4tn7vBwJGLKUm7iTB
5+nsK+pU0u6sx5tcIh06fjWHw/G5SDeCJFPbYK2V3r+fU05puyTKrX2P79uiOyWL
QWI5o7P3+DhLt2mbeLV7iegfZ8TZaTpfLNSIE4R6QkdyqVydJgZZr5QwTPwbObAc
ws/1mMKYNl7QMwGekGHWexG52n/XFeFpDAr0jsTXR4ap0DNmhTZlkDzDgT1HcO1d
bLtZqoRQO12/pEll6CYFiNNoYPTQZb64e7kA09J8LRVtY69uB6b/6FinIGm9p7rS
RQH0g5/JqvJsyq3P14ERzMCwsRUO4PrEOZrFN6deAgYq606H6DlS7pQBKuiUutkY
BK83fLuXBKLXMoKSeCg+6Y1/jLeuNw==
=YeM9
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '685f98ec-6c06-474f-ab89-9c768b9e587b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//do+qoLdfNXJtaRDy23msUcaavfDmQ9Yp68j6ax748Mg8
P8v84z9P+eDye8QXu6qc7GxA9L3AiGAwM3J18v0AH+9R9IwNtqeeMjweQ7I8PJeT
Pa1CfSBTtuPj9tWmGtZdV9oElxXUig9Djb1MIci5D2q/jxo7x5HbuMb1nUlwzFyn
+DZVCBjmO5F4rx2QKdCyv4vJqDv7ezR/0GL/IsNhIBcVeBlhCKUnsOqjuQLaf54e
vukGlryokf/BdGD89nQiIBK0CVIv+9gddfEkkNFVNxCEJs69zr5+dV5oLkp2d4iG
iknfE5no3YsRn05fuJpGinCUxTXS9umF29tUtE0F2qthbf5y4XxSt3q5orwd1n9S
bXZFMEH27aPUcAm/olfHjDbIEnrv0c5g7CD/YLVlfeVzxyQvt15g1nz8q0l+TFkr
muh6zMtQWcyhK1srbOqWmVZWkWXAXKrLqzttyE0TJB06V8NHn1DWGnQ0S4lL8A0m
XoNujQlG5oG3uf8lrYEBpMvN5mzjoKrpwOScYnFmicwghrx9twUjB7v2fiVmmqUn
UOvQPJFL7qhyZLpdhrr+itS59w54DRV6d0YzaEO9Kqk5Gk+q91be17oQCyKlbzdF
oOHEygbmWjhfiz0VQsQ20yTZAi87DQrbWlDgCmexhuUu1Gp73n4A/sg/9URqc3LS
RAFLyO7GjIpwtlCInbhxIwzv1/wlOi5I2Yrf6ADF7UpbNw/zc4o4Lw3YdYuSs/0I
bUF1aNPwtRLhNKK8ckG0Nwx7RNn5
=jR0B
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6c9deed7-9903-4aa6-aa02-fc27a356550c',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//T+X1eUivrNg+1TAAEAZTtKVpR3ZYkfSZ565nfxozR/ek
uh9/Fyyu0oQEyR2tflgwgjaSryyS3te/m6CZylgXWXz8KhhUI2T2nX7X9Xgww5Nk
Oxu75UUmYtinMXy4NJxWZ/bqSMUMw1wdd7of/Tg0AUg8JQw8DxrYv26gKvMXbONv
8nCOBcM8237bUN7vqp3TSiGE+PTIjKtQXnYV9/9sI48BRebrGHfBmBr/tRj5rBY3
up5/30Xk4BM+ymRNSlhLP1P/9x6VZ7/7qGw5RELp+KQ9paW4NUOfKdJs03uR4CfG
uETmdmmlCbDAAhqyxGWrYWhm3QhJ3oFrjNEmmPZjxe8A+W6sRPfspLcKx8OJ9qM5
30zHKZKTko3gY7+F4Gxgr6YVt74xmjEyHKYVFEwhtiYFoXEWNmfvb7wpns5Cxrzm
nDOXEm8/hfg4/QYXr7rRW8xGE2sDUdBQY7uQCHGPv7qki6jxi/9TulxIL7rida0P
TNlCSOdyttA6PBjtKhZOBb6aA3slmb+VNbVmjVBBP6th6qTX16b0DOW1Wl9u7hVi
SBpkJX3AIGYO//iH1Xo9Tzx4Xow6EOjhZquM2RVn7a5SobPXZ0+lkPNIYecMvm9W
PeCW6xisJjFArvGxsjIhOAm0DzvKfjBKmsjGjPZri1/flJxP6+WCYvwY+bDl2LjS
QQF4iPBM1VOY/eOT9IiOs5v8Zr1pw1KVa1fXcVAKt5vluTZAAApGY+k+XEmThepY
O2+AV3+IapO2fanUKlbn3mlK
=qCWd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '70b76a33-6dc8-46ee-aaf6-9389353a847d',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf9Eou2eTvAeG/NINoV8sJ+cCLwyD8xhw9bkdwpTz+jgnhM
TE7JUD030CfgYfHI85OzFpmnQUV9rdKqCij7MtzTBgeOo/qHYf+tMH+BI1JZt9Dy
Dbp+cmWbBvA9EC+u15sEC8WYwuI7e2/ipAUP/zosgZXxSw47w+gH7/EjedMz+mlf
yZNQ0LjCqpf2U7bvn7cGWNxbtSHBciI4/pEZCAiw/2XagscyhmIevQuWrRWW3CI/
xtTo9631A/IqGor/6RrmWxjLQoN3ABjZ1/khps81mxGbnKntdNrRF1kPs+MiXU7m
3QYKJCTH5fRJe+4g0W2HbVQIqoY18A16V2lGFkqcqtJNAceWqwyEjGaZCj1CShBA
KIgUXJbQuDPnSZhr9+q5dxtCj0Qx62Ji0KzAykW3pUwWD/4tT9fcLWh9rHt4+MCb
gEt3SfQAowr56CM+fXw=
=bqyQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '72671aa7-8839-4dd6-ac58-38aac4820cd8',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/ZHJ2cwgzS78d/wbOQrJc3se5T2dc9uml6ssUA1hwukys
fIujHMXu6gYFFxOIKhxg8JH2R9DZBJgyOrot076wrhyQJSZhf8UDAXALlsJm3kPT
W0nByClh28cjmcqS1Vsc9QzPIo4Cvs9XiHNRYtAoQgnhz6KBDYNgmX80hdCh7uw8
37is0HT/Lsl9fl88Ijz9smzY1VFWYXG8hncvsZ56Lrf+ZWAEpyX8taMTq76Qn7sf
rHHA1bi8FuESI6hsXCanE6w+35ncmeo9f8NEsO90Dp6+EWzNf9PWn1udw1eKMKAr
+XzHOHDnbhTV6ftnlorGPCfueaFGjjR/zOdUn0huKdI/AYrhwW8elLvd0F/qtvM5
5EaYL6fVRMjT376A4NOLOuXc098G5Oi3cday3ntN5sRYuEA+JiUOvU+8PSm6Bevt
=2psb
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '744674cb-9229-41ea-a45e-72c28d7ce3cd',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAqKYnfkdTp4ncMzgNoP5DLzedFSuN/Pn2o9rxcqGX9ZYx
jxBFx9ocfuNubcYdwFZ3Y/2RFJl5z51lCjkdbM3d4d/HOr18PlsYoldPm0nMjvRB
Bhl5pR8OVHl56oPUnxY9a/eOhEQXYkv0mZOrrTNhI+5DbiIjS3q8VX1tV8LdAGBi
mOxDPTYSSmkavbIBstVTT9LLsboeVwBGCXmZSvgZyO26j797+XetRzQOrgwZtGMB
7NJC5XnBcc59spq/g5DamK47yz+416d10a4tBju2CJj5yt+TcgGNFqgLiPRGnj0x
poImM7dBcWiG6kMFExU3tCZ+88x3AJnKjnKzZ1tRvTprZR9TIHVsa8BCUPOUvJJm
z+DljhP2poSZOIi+O6CA68iDN+FxVmYphbjCwxSfT7BKBMD8kV6xhhHz5zMWFG9B
CaEWotBYnUbCu0dls7EAG8/lb1zziGCf5i+hd/JMDVyxyLlJpCksoUGwmywCz1UZ
uO8Xu766h2HGonRSWI+rsDkbHitC0IOJh73PmrOgAO96Txb9ofyP6KWaaB7R/s4w
/FWyMft3lrE/T2SxPVmPOA+bG7EEr8jpcd4UxJziGhX7WEKKifdmeV6qOMLyPCRI
tPD/LfNqJDWXmuEUjdEagNgSjVOt4DDtgbDpj03Uv+6zj/Jue0ilY0z83mPbg6DS
QwFfkC8JGtalyl0di3I6wFYdzEM7Ap3Xsgb8yjikrnE61F5GvWIeVPJIkNreqcYq
GbkaeF/BuYly9W9V5e8GghYDxng=
=Uq2i
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '76b2c4a4-9620-438f-abd4-b5d701d74a99',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/YHqc48t1JBccsLgHY4hWJYlgJsJ75OywpMm4ePV0Z92D
6c8J1XGgY6o41Lpup5LfsF+W5su26XYWtAcEp2Ouxo45a7pw0i6mLKiQC6sCbZpe
KiwGYZBlKszhTgUc5Mk7K8HF6MvgefHPeLk6EJQ5a+qoByDDXD0VgAVzjroBfRuw
D0DeEO/d6M0BiMJavudV0ATQsA/9nrr0AhdMuDhESGW8BrE+eIyMDMkdNbQHiDUV
j2NBcR7HyyPNCWToZnoLl85oXd2Xy3MGefT98K4WBKWpWv2Ug1OpIKuxs8rxWzZZ
/dMU1dLuaFY1tFP9Mniy85QXO/QicIssdu+Y9PSv89JSAdDSzAUMSjgfHZ3ZMG45
Jket1tB3ilP4o6ShA+nWdiV+lEllU67EQxB/B3PK1oLlQYvSdmwWpfFGBqD/zDAi
ccX9baTT7iLsZ16xENPCbBSSwQ==
=4yov
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '76d33549-4f07-4efe-ab10-a3752c4fd7e8',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//TWzxL5MY4jpE5O5LWjIoTn5TfgVay9szE+F3DzKCr6eP
xFfhVaXeN25rexOPvn8LRjYPZI+0rxYs47BPZDAgMIKbtnvTsIQMlBhiH81bSnyu
tv06GBecQkQU/0pTpbVwsTOttGaMUj4xaf4EDDPftd+2SjeUuNLZ4Avp/EvxcosP
NJlxBGQW5u40MO3yNq+ajmUjwkdr7Gms+11ZMYEsoZqOIoOUX0EIHDUIgNAV8+3v
/qrN65JUiq9gucZcsNb4HBRwywuyOVhRDfLNum96qHUTwxQS7rwVz9nSxA/5TQAv
HGk2VQWZswlkprfViMXzHmuvw/fTZr9sV6kqTAgBzIQ/lci3MKqNWQim/qRy8JeO
T3pd5ZW6UnGr2bHWt2q3FldIWqazdG8xyPuggJW9joSsiEzEyltCDjDKQF1diVXx
izQEo6fS1d54eqU2DAGJ0HzS6NOOaR6h17xEoJl/gl2ikNEwwjbGnFRsaNB9URR0
vMftYHE8SaH4dwOei/bHv/5q5YPjktZfvxl0HAgPLQjLY3E1nbtXzTQThZlQcXgA
jqXj1eAZZNuoXKHstFVXSUdcheDvJdEEFgnnYzMkbpCg8fWnlhlaERprmVd/uPAq
eXuOU4U/YWvsygoumnhhPG1l5HsUxqLDQz04wajiWIdvePzzRZ/YWBvZEMSPHZDS
QAG027JU/nWxo/pmK8uTmXGX6OORQhAiVVbRy5+w7TxF+j3FaOYsuQkZtQHVobjR
41zYeZuxu3dhep6QBQRfX9o=
=lGYe
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7717a8d5-f5ea-44e2-ace7-e3f807b2c02e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8D6jqGGTXs3o6hToszZVxuNmLqxxbD0bsV/7fIc86Wlsw
sOz7tBhC9THFfKU+IrOlMYORVYIdJia/06YNpn+C94eevXhx3luhIYYweV0siJ4b
tx9u8yJ8CnwrlIRsrbGcjZENEV/buVEr81FpzViZNwMBsjmu4WJR+Obl36YB+Jz9
A7R+bFH51OBZVqZqm66RmY1bHtZLB3fjI4AwaxWuPTCNAoimVuaXJO1K3eaz8VFD
px3/H13bFsY+R3fe9IbwNbPlZTlW6jDAbfKzEuBbGEFtChas/WD8uTdKUMZhzj9l
Uc8ZFkfGCqOeEwjy19IcxpDAGpDNiNL1jf0a7UnaXyVfNRB0jofFMuUo82+SkmO9
ZFncnGQY/34DOZ+O/pVFTPRHnP9m9rLBtDgQH795IwFcWigvWYAbja2kQuwRZk12
FSvBq9FgZAzVMZgs+6kSnw21iVzFjA+/P/RrsJLSgLOZNx4tl6bkIM9iBjkcXtmH
pcFN0UQIboQLRJflFurHAUOJX5kbyeRr4frlQNA2NJRCt9LA4S9bZzTY/vmnbMO9
UarKAdYwuiuxNFqghwCfyt9EauJDBdidpvEHyVF3LwUm46vJhDloqCfkUfRJUXG1
4XaO5LgS0EOfDYR8Okgy1B5Y9YRr6Ghwin2hLNpNqr35Xpl5lv7da0wCuqeAkTLS
QwGW22q0x5iweIgyeNMOOLgOM/DiF0gOs6tsajs++9KJc8VpyblZ0OFNRz27iYkt
CSIrcIU0ByJVzjRWmOpa4Vm2i5c=
=An2Z
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '77a43fb6-24f1-4e17-ad58-3f90fe9215f5',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+KfTZmfvAOL3sMcN5QPu17JTfAA60nnQjLSGwtOGFwGEF
VzflOOxvizsdzFD2BaCW15UZps0AjkFMrj/H7JUuj0FPWNxoBn7aSFE1VWvjYYZJ
/Pw6ZXp6dF7FES1Xzsz6FzIRj2/gZtSCMGhZVGmk4jtBulUkSlxoNKvyO8ERTr3Y
S9EwAZgqOmGuwPzMIWpxCTKoaG8gKi5xO2JiSyh/pRSAKEP7ZlNBYg82f54XeJ8x
GnIJPwAlzgl0QtZ7nIN/5s5tv8nxd3TuO/3hsn6kf+dA4pdLUyQBrzcPRz5wl7mK
LLuwQobs9a4MGJD45HzYrwvmS+bJKdnZt1mzT4H9hYYE+OJTqseIphNN78fybifZ
tkhZ7jQLvvPWFJAl5fBFLEOSonDS//5tf1rsFWlLMU/Ran2iiJu8u6m93GLAsm0n
6Towr1hmOmwE+fjapA5xg5wcGCodj+Q27aT6NFuk6N55HgSWh+oFWMOq3PK+lJZe
bHY47wMMBZwxQHIYcgC1n5g7vQD6e4r04BtbyRsqCkU5nm1Nn+n/ABZhhv2PB9vC
DcEnmc3Q2xO4CICdFtqJCIK79yP4+Ix1Y9nrA0GRu1ujq/nFRiy116xMiuC2MIAF
gtlXtMAK/E8d0Z8uOFbOyUnASrQ77qwHghIj2leieNU9iTiiQMi96I5Rvp9Mq1TS
RQFU7S18eGwT9fNIEkGOIFnCmBtnHHm+Cm6QCB3F69ROvkIoopo4WmM+UUeBfIy7
MwiKAunQDqL/TTDuB/124PdjI064Yw==
=7DP8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '77fd789a-a953-4228-a1a9-b7713a9aee60',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/cU8fdw9GFXsM+e+TJOiBvNljsbJtPVmpqa+zM8jvD230
81QqKKx4HxC5aUFPoP2e6YowX/HWgGSI+3uQsgEDj1/l5XGPTuN+NdTsUNjXnW0H
FfoCczfLPqheod0zO96LHb2ymn8dquwxAdk6t1iES3+c/yTprB7wyN6F5f67T0xA
8wCjgEb7F+xyg5WxvrZ4P7qc2wNrKfmRtr66R3B6i3EWgjFjT0S9KzpaTZsG9fBh
pNqSNobXjjnL2qiJ/eJPuNcrvqQluAEV6j02IoUBYJm3ykLHzFKOkpS+d40psEt4
AIbkoZqNcKY9yMOa5F4JSlLsiON1pTPv5xypDLD8gNJBAQMEykQuLHjZ8ky0vI7g
6g7o3CbtNQ+z+zB/1UE5k6Y8SCYaDT+BkEbin/AmXWbb6fmAWpXmEbgG5fIUM+dh
Tcg=
=NKi6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '78cf7639-f27d-47b1-a127-fabe06ec0eb5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+PbMCgfWfR59PqgNJMh/etuuS8fVxDJzTevtJVIqD1kQY
IZodEcJQqZGjPyaXbwwL2HlbeFngjv6TrVUng9aatE+KxVsy43jmofPRqbNV3Zko
1ZJGmlQvegLMxl5k2SkD0rrcVtftTtieVdlBdRuz4TKt+9C76In8TdJ4uWz6+xUq
KptNniosVtw2QcGYIReoMi1rZEwgKTyP203FPySDhwBKL2B9EufOx6vbdGCHUW8b
OYRetThJtsS5hYib6uHFd7dH8fgweLEc2PEDG2qsQYr4RFIruuPVGSDf8WdN+zCg
fvzErCGQCMRL3J7rNkyBj5Oi141QSYKA2pLe9VRr5L7jkF+/0BONo/N8TGsPqGYg
LeEEdepj3OvXDttIpN9o1bXCkREAndQoDb9JEyj8/CDywLQY+CPhm9fyTE07O1N5
INeZZ9OzfIe3c8+XsN+J4WwPYrnWRKWbi7IMULZzWSThuwoAPbB/jHMnalbnTTmr
7ldLq0UIjKYblNrTwULz63KIG0TQKYmQINHnQBcVCrNvKzmB4MUmYtCb4adhtuXL
DPMp3I5TpQQoG8ef5aAxmVkRX4uFQ1ubc+qPK5/N2tVm1siqQsGCujI8DvmQBVKS
56YIE2RCe5z6kEVS/6AYIA7pKh+5lTqG3+R65BnqhqVtCdUcri1eC9MHh+GNN2XS
QQFxs80g4FPdm7HXwY2Yylj1UiTYj8A5gr7M6oZaWP16KTfi/doSWn15RBh//lbo
NYRkaKj0SebHbsUJltoXpXLI
=t3sy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '792c5d0f-16cc-4854-ab86-59dac8dc78b2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAryCKIxU5qLKiF22M5kDwzpge9cgFIYu9Y7MrfV7b3wS7
56/wBO+TtOw1XJ22LGu6dIuFIIpv99QdsmZg3vJ4xIUXrs9khupyOEcVIKvb3vkA
4IHFXkL2NsGcqbcJinsg81rfctpE/cDsiqwlyHAFDfxlnbyTeP8ab8RgKFHPJKbb
UzydWd91S4Ih4Lz9JhVzECi5oMS5gYvbr4MJoXwCEzPWhBVseHaRxPH7nQe2CWOM
f7Six2zqFUdIczQF9RBrKOYm6+GQzNlbhKNfg5yzOhIfuYwgeTj1Asd76fy2b5Jt
hcEvq8z6dnXsGUN7vRvZFVpQMwUyFdJcr89RUNsX50X14QfJeuto21PXNo/p5Hru
e+giQrmTce7qkD/3B5vR4MaeANap7wu6wRCCwZ3BC0M45SpzL+vs9g64nAwvW3+5
91YnuoqDNQOVBghmY/QQSI+ghOvLEteKDKgs/E8f1M9qn85ZexZDZFSax3AuejV7
L+KedLL/20l6RRjkuVcDJym4Mn9FAGYOrhN5EGFL+WwC/Di0dlpX+gILzhL46AaI
3IQyXJiZpA+isf4UG9eQFyI2KiQvUEU4HDQPiYqw5Ou5A5G3m6YIF5GTOE19lXMR
pxEBnQj86n+8r8mSVFhp1f7q+VxraXX0zCUlKhRW7kU+Jq2shDQ1/f7oh0BT4vrS
QQGgli6EFqxMTO0ApjRwQDkV+ptEOCd43VThz1SPJWQPW3qhMwr9yzmNrijsOO5o
NFIcco989+jJFJRHnoFH7RY4
=8A8I
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '79dae664-2840-41b8-a31a-d230b6ef2671',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+O7EtPeYYDVs9Cx0I52+gIWbbL30/zWXy8IkmCBd/U2py
y4khL5wRm4rRyx9Dc1mtV4zXrPErrPAV3MggyJupuBOEekgetjwaehzKJemxyUv0
W9eZaAdL5XnHOZAKKorqexGrw5vhaZ3H6fzSftYt8ci+qPNDVjIZ9nEIhox168PL
yRQyrDR/Hym2HyCHP5b3h4k9F5bSa1Mk7XDoWvNDBIAZwpLJJ5NmvhFyeGqqD2Hh
Eo66/GaOHZM6nLNrLd8UkIbTYdSf/dPEo1DaAMe+WtKtaD5cd3I5zePJthlynTgt
BSkEJzEyBtdllRnkldlte7tjdIuiRt5CByBE539E3SaavtCXwp/Dl1gy91SDmw3Y
gN8sjqcCV7ZqePdphMmY5KFH8OPHWjM6h8Ukbb4lCM+Vs3A1231bigfgeIyT4Ddd
Dh9OSQgdKiX9uRdlpRMCcJWiPSk0yXLjSqU6cGgkdj2NKpPLMenmCR77mcxdlTGR
CrKv7OIyrM01WtYb1VYZ1bEuvgDV/oWtsXLr0o/1jLKl/L2ZOG77X0mUqKNCA0IK
XnhDr3th7YB5nPSo17WV5k5gp7rnIAjuRnMlIwdo3pIGPlb7MrLUkHYxVvoSmgT3
49zWdQjQaQ2oM3fkYnndIteR/ecZLC4IMmz8jgTEvIiEZUsjpwn9Ew2vhJog4anS
QgEcRAvTi+DZrOw20VIDSP1KafAKPT/Nr1dLqLWuGs1qReMk3kkIdJiW9MKrMzLF
XpSDySKTLoG+4mZUa+zQqH10nw==
=PwKh
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7a170103-87e4-44f1-ad87-e27736097fb3',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAyxZf6d9/kL8WIZ4nzmf9kS+7hgiQDXjRdEQ3bkpRGxUc
1div3GPhWVKyWthDumNVV+weKizdistA0Imd3DnotvpbaHnpVwebptsVx2EhacCl
tsFLu+NBc9joaNQhcrpn66tXPw88vfPcLR4BBe4X6wd1/UotS++ixproVDa/5fue
xL4gFhLiOSScc9ZiIZLYUKJue4VeF1kT+8hTg04clHnCbqd8+YboU0aasKM6oA5c
5mRubYWuLV8sPruuYgYRz6/P7Au/JpA7ZigSWPhw+TsSzjlcsUuoAYJS9kDKBqQr
cIvjXKxekctBlZ4ZDJV/qz/OHd2fy7aGrPEBO4d7ctJEAZbeF9omRVCA+z0orH4v
N5refwmgY0pedDARvskeUtxqOl2L+rA+dalwuWUYPSLw/ihhKLYaDmBDFFihZ9QG
b83nFBM=
=k7ns
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7b1953e4-0473-4451-af27-0c37935ba93b',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/Q5lUdBqcy9FmYnTyGcGP7peRG6Pkg6OjMn6LSPJTVcNE
yj5eBY1dsDBBvvGRXyb/8l8q1lhyMTBvf99cB6u0mmJPVRqPgbWFRJNJtCQp9I+F
CHXQzERwT3hrX1JWHvqxwjeXw/umuCAWWzI3pYf8thaXM1llHb7ft2GceYN2TXui
0EUZQf5AZySEYi91cJsY3ONJRYnDxpt0OFSsfrM5ihc75bJ6Qm1EQUKLpS8JhB3r
0+K7RNlRlSyFVHalGEuybKfGg8X6SecvjwJ2+86kgRUujnCtDsAHjlVhvntnYgOi
lJFHzTd3xCzmzLeaG131DIFBM1rUpHCEIVCjmozATNJFAX147QeLXN7JYsGFA6MS
DyVV2KBfbqR6unOqkxdzLA+QW3BROi6zRx9AOT/F05Voguz9tex0sJY/S1LjZZsk
TbT16gXo
=iceX
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7b4abc65-bde5-4b13-a8c3-342531f66daf',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//fkqDe+3flFs75w4GdxTZ0sR/5aOaufLR+O2cJTefKYHF
1EQ56p5v9f0xft1dzmK+0ISsmfuhhH4FLNiWaMZsLTXFRZ9Y3wXuni2F0d6jfxVT
4u9DdMutLE6VmZbjJYtWIXCHziid8Jm9BJc6FuRpjXMxzXnJ4mznPlJXoeNGkjlu
fghDEMreUKqWi6OhE1tXFvF2n7XQlCzOn8RGgVHtjmCZPBtJxlb3FDeE+RqBNKdP
lWG7msRITWbd2QHDHL1d3l/dePydhRkszLdMoSiHEP0M8RCE8DZ88fZTzzICfXr9
ttShfrMqbQUqDOj3RIYTYfXmhtPtnphSOmHCSFcGXdVFIgb4zxBtZ5vrFE0H7kWJ
hmEtFMG8ml6SFEJXlG1zbEvJyUc6m8uhI1RAv8xFak+MvpQRtUPTnxQ5QazDP9xK
ljyp8U4FvEizT/Q2Uw1Dd39jXwJXjPHcNGA8BTxQ4t//XHEqYQ6njhNtaNjHR8EL
U7+QDs+UlPPHbmLYrNNl/1fgDjdpj5UrmQ6LfMfbolUz1w4RZySSn9wWRHrEeYUx
3wutDoO4JSclpH3Vw/OEVqOP5oeCeYgJCfJ2t1++/sKedoOoFAxSMNZ96O5k8NRd
ZiJtOI4Hh8iyS6/AXeCqww0lT54YINodYynhldkLVOZEBLCUpDyEqkc36oXqC8vS
SQFYKzNjvEAlOGcdNmRl3CnTmkRPCOOrx5Y3sMut1+pvFtFdwfayoBz9bwoF1r7W
mdKbE1Md65iR1Oqw7wof2PlkhhIUDtY1LsE=
=Ioa8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7b9f70ac-c59b-4f2d-a1bd-4e54bb80d30e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//X3MugyP9tOblD5zubZMwvTiz+Wgz7wtH/BAhcSE/O4XZ
9WPZnXEa2+eaAKvi3YC4xG5F7psKK1oKDgqr8GatkA9jDjdNdJx85uq1T/3xcD+m
0+18Nvry25TI/VVIo1UQRLvd2+OzgBWZx7SOspjExA77XkCYc4JZpEqAX4N0ED9g
O7EL+v9Vnbp1iszBCn+dhOLR1mQFYL3HaZkXoTIBnhUrtthN10NryuZlHNEVNxUf
s2mb4SYHgZRaxeaxX5ZM70WIiOApV9Vqby/1uy+Z1CGzu5tIMAoUYc9GgCs8TM2R
TECQqUvSY5UfKPEMtRyfeVzCxjcHkJ/mVPirrdCLVtVmazBGcqSjuiaQ9BqcM7fy
9oe+RH33KtFTcVqrCEY7k+Ew9qZy4v/DlKuKz/+TnUApEsWx285LpfZ59KYOvg1s
HOP5viMw5Rr3YpPPpLLfb0nAOe6rkcYw2h/IeylhIHiHZh2F0zz43/LYFAWwWrGY
jCZOtlqlRB66pVJdVjuaaWji3FYP2II/h6gFFwxN1Igny2R54hmEUy1uq7MUTHvc
uzi3QvkTCdj/noZqLieneMJ5UTyVBrT4A1aHVM1kK/HRrexY0Roh0rr5rSYq91qz
cJ0SB7hxkPND6Iv2uZ21DI8pvek397WZnIDN80f/dmePTNTlhPCzVDaCmFUPbfvS
QgENmIqvBzfhX++IIUpvSNOWNIoTtVCt7MXIfpzZ9reeS58P0S3mwc9xdw1FfUoQ
2oTjb85xj3lNB8Vkjh/ZNlYhRw==
=gqrg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7c8a11ee-91fa-417d-ad09-a8a53a63096e',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//XW1FIfvviRDpVfKgPNqH0btiGmai50lRcSYFW8a7u2J4
p/RNX9Lr/dy9kFXu/ao/cSD9n/8KYSBh/Qp4nx/15Ol8c2IF9PyhaTFkxEcD6X1/
siFohZwQo0V+WW8CFn511HdNwQM9xG6KIUzFhORDCmllO5+JIqMiDKXzN+95thmK
rRGU4rgiux01pBMGLVwGLGHxqwDmQneVmnu7mGZkuvkKh7CA74OT9XQRunMo30AI
NWnfhtumqVatOqlomDuGUjBUfUZw53mgNH+v/50LvS7QfSDghZJJ5zb84Y2wHsHy
wLrT+aZFSD+thE7Bf/qg9PjZgJd10ec5zG0K1qdL7tNcy1IkmeovZCJPlwat+/g3
v40/tFv92b4X1qZ24kYzffdoJp8/Qay4BfghC4Z/ce74XTxdpsDs/v8FAWgaR7n/
oHReER2pTeAS7gFGcRDP6yIk+0T9JQkwXMipy3rxSdPYi7sMKFpJ0wkr/EcmuSrA
VlPskmFRv9Y0OjEz4nu62mRW+RbTgiIk1EO9N4CvPx2QN/sJOWLehpW2gDKaGodT
HHl59bnlOW7EfETkvDKh/zuWDC2sLnuEeD265SA/jPMWW1FAS3Ozyl1+i6egjfXc
aoJDdFxmYjLUAzjIlCtawaVVdBoVSLzlZN1rjHLcy3DoXJNNNxeqslw7wy+YZKPS
QwEnYTtR/ogm2GMijBS/L11tN8WqWfAYXhQAdq0IlBNplJOTCxxSuVNG7aUumqhx
h1nS8h8ArBTYT3T0z59NGadOJwo=
=eNct
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7ee21ae0-c63b-4046-a96e-f9cc77b469bb',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAoLtiR6OlvcIrB2ePErS/iovc+IMyTDMwi+zEqHpgyFt2
lmWwEfS6e8NuQ5J1uQUOGXX1W9+i0f8bMV17xnSsngZ8UwMJZ5kmV8iRXHkiAYO8
YiRb6H1cynr3I2hgn4EwDCPX1aHXZevBe6T+lGPRgXGgb3XUiHzXC2z5YoIPiNmE
HWCRRCmFurVgL8izLw7EEB0JMyrOMMW93aSPkkUvgvS1waRM2m2lk8D9664KLQ19
q7d2b9PtW7hklQ+zLWFEAepImQSvcZZ3NexbmZuni64aRa0YUqOha5MZ6a3W87KL
zos+74ptrellbsQMehSQrgxh9BH7LinrtMlxo5aoA5mk82JRIYGv8kv1Ck9n8OVN
fjqSI/8uhebl2zqpUVLU2O7D5SoaK0aSUNzjAldm69IeC59ZqItqIG33G33a8+mw
2Pg+TfiNeLFZmxCppzhlPhXDcvBV3VKeeTDOh3I9xsjWn7SXYKIHKiIxHVX2LLnW
zvrVQCY3r1QsQtQO+I+sOOnRTeIoZ/140EgEI2Rf/TdA4fG9hEZPqXIdp7XphJpw
CJIsgvIn6g91SxgcnkmKyASEfF8D266NeCi+/GQivl2LktRNX7d/VwvbC0Ds2LOJ
qLuwlI0ua+z4fdgY/topE5B0cNp3n70jllnctgyW5xmN7Hp2VBHQzgCHcttLSD3S
TQFOUJF5LLl1/3FCyHSFlEa2W2WiZbiQkChv0bzF3vUu/ARGXNCxldgW8H4Lc8pu
x5AtgneXiucfflHjdDk+wOwXdk7f1ZFvJOKYVcfp
=MzKi
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7ef4c646-9cbf-4be8-ab96-0a80f48d7737',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/+Lcv0uKadA/pCxDg6TdN8NCsaLdpBMQUeu/OKFLLWqMY+
luLq2yACSU3ScWF+ibykvTX2GUV7xFN9zLE6fVznyRZ8Pv8oCEiOiEG6vwJhHzu6
ApX1jPfNXXWLD0f/dzGAKmKZ+EOdCVW7zW0LrmSV5+wgXSyhzkZRxu++N8NID0+J
t8kGtVtdCVgU7N3dW6+/JSc30nhc+ecXfEmzJ918HRajDnxwZd/iJFgZxDGY16J/
9mbCyeHB58Ln1vS4ElFR3Dim1hYWVZGRzKFm6nu73+91jaJHbMljgQ8GApaRW9/S
m10GMcCZw1+pLJAfCqHopzLQsdBf0N23R8U+0VgLJG7T73SNeGP8qtKr5Y7N/p7t
igfmxvS3dTzpQnV/Z+4jW9RXecDJS7Mq5NsX25xf7xfXFmSXJFpDT+jPqLWom4He
IZu2MEb3YqjeQ5gJnBM+rRQvz7LqX3D7Ash9nRy21A0MqgUu+2RA2TZ/rRmUs73E
WSfPB6VMR/gFNno45jDEsn3SCLLfNN4kO1W9Seo3sm/5aTFMHiBy6PF8SvzjnYSg
q0R4JDmRu2X1wlRe2MgkfpEgGsxDc0cwsogdJuI0XS3vchxL7FgUE16JNyaWcPDG
8eEpBpIrgIN7TxSiCDxB0uJP0qJKOf65l/d46fpwL35Vv1Va3m8FpWeiCTwlXVPS
SQFv2br5t40DNYSdPN4EUe0eVSNVapJptyfGMZ9iugBKSPqY3xKxv74n9FvFqLxq
O1t6fnVlvX0qI0T5X+shvkfj8/ubrQKvFJU=
=P8Ik
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7f974bc3-9a30-4b68-aa84-b3be5b1e3d03',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9GisJ7UE4nYmfj1BONXZ5gRF0eRmWaWPWQER79Y8AFt0B
d6IhWrVqRY5rcP0SreyFe5lqb9txLZ5vsIMFfNP9u2adfFC3gpMkrRewnz1yLmIq
B77SZJsnp6lNl3brmbnsQ/0ryrL7/l3Tdo2LrIQo10KbU5oWcSDDjTqYVHreOgxi
/m9jPJ0DdiuEn9OOioE90ai6np+PgNmnfoXtotikHBrFrIdBGX9GSn+/Nznjzllk
O9egW37h7S4KsG/jnlYqZmELxmqm2va6imhubnUcgdMeP1cYzDlqQRm6WI9vYrTG
nEy9AWIU1Mb260VTB2jFDeyHLqhvwwFQR9ugxYg0439LgsgauxHxXBBgKtkQ4rTx
neG5qw0J4FG1CJN72dRJmxdknxcdkmoERTV6Na6X9LJqrvtdfdxq4sIw/r/zyBOV
5zXLKiRyBwQaqsIDnuffZInI9dexI8iy3wyDpsPWyZ7iBop/5WUADjNbXkFbOtEM
JDwSve/sQNXSNUZb09Se+bEOCZAmNFkFUrce6UlOdZVk/Hf/Pgo1GQ7eDfazJNCQ
aXe02bi0F3qgxuD5U/NOKbPYNYYTRPX1O1L8sWM5JFU4mzzcl9+ICOvQM7XZyT7h
ZRnPZT3sHkY+tLxDbEUx3D4ZIsHkwdSxz4TTCBKW/WQOUNdcrtWUSEcxhRwXkKrS
PwF0dJTvXoswM81pxgJvtFnNY+5P5SqELwiDOvxNMJY3PZdChX9R2IqBCHZEzKVC
/49aU1PX0gfME2SYG8I6tw==
=3PZV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '806f55f0-cf4e-40f0-ab96-052ff503b43b',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAty3K6xEcf6XUs/1oUkQCGqZS9As0BkfduchwvhcEo6lh
HajugNMNvYSm1GeoFoGDOjuIG/yAma5PMWrFYRxb4ByUQUb1rkqsL3fjazXbTU4i
l7RlCth0mEkQYrDe3AJ+ysbmUqYI2yXWTrolO0E7qmUERqWrsGIlFhLfCi/x9lSh
6RIyiXMa/etWGrZKz7ra/dDA8sSlfrhbykJvYwzh5dvLXUgREgpPedZYNoMcwgRL
vRvlHlicnTWbbHHi0UJW/jjIROSseavNPwOC0bQyYk/KpShssGYLAKmSN3PmC+C4
OIvOGDQRL5jaXpEYCfRFTfW6B1wVc0jBSR0V6M9RrGoQAdBiqO9uXaSEDmZMx06E
jv2rXcjsrt8FLLy6d/Y5HcO+JL32c0tNDOw5lzlhqPlsXs3X/GdLYlBDmZhb01/r
oVkATxUTNEh4tjMcKApabP8vuqE35NQin0iylq9mA4f6dDWidNSZKzJfwfT4UOFL
H1V7JdPrOIuJiGK/ic9TP2Pix+w1Hu2QyOStNO70xEuE2JNxo5MWT6G5CX6ICMvd
lfgoYMfo+1WIvrl32MtL3lInWHjrXgzXmgnUsMkbr8j6T/zU1WcjeoAsprg49izB
ot7XGxL2bN69voNydJcMhc/5RZvYmcEr0kB5zauhP1+4kDg6R4sH/MSRD2XsNZzS
QwGt2bGamd89Xn+pyhpda7iGWnTR9pMnZCuI9QujtwbS9U0IETuiZU5rPJdBXDZN
5X4QgiSGWv/8qx9PIugTcihACjU=
=0Lii
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '80eda56a-a5db-4ab8-af90-8242494af8dc',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAsANCqSq9t3eOHkyiaAAto8At0kA5inNiRkSnoxhKcwab
/LCv2YqK1EJK9FdaMQubgNAV+ubSc81xs5ws2RYsQPhylak510qSiGnm2NDJ1mQr
BtGMz+Xf+TZpWD6+jRTCz5D+lWAVdE4ZMWGOLODIb9Fkzhy4OnSH+0K2XOF8aqCr
N8NmAeT2lE//MunXE2OPW9Yq/tpH7OTFQDl0tUxCQz5y+hfZ1KVw6wga5dvnaYBt
W0a4Iz4+RwBnsOTRS16wovaUeyTlFS+ltfKg16FV0eGmVB9Voz1Vs+lu9lo1+Ok0
FVVPf1sjF+44vyueiEPcbtNQs/bf46YtA6Mt4wOaGFeetAH2dAD1IJ25eWHqKsqZ
0+t4XZLUGzmz75Qcl+P3bXS5UHvt1IGvQ1bVYtqK5cr9bjVWBYUp5FZX0bKf+qUC
ocPA7/h83H2/31GCKid0KriImiqZL/73911ASFDLpVJSNzwOEI2whEUDCBg5y+oP
wyZig4Zmyj7Rd3gv8clPOUAnaL0yjL5YObdz9dbGtFSYKG/ddyoCQ2G3PMLsixQH
r5GCDWGO7PlNCW6Li37fgqxeJCnsgnEKyxlT9vELtZPMwsOSanKanFW3k1R+WsZ2
TKNmD+/8HBTOc+e8yASZ1X5gTOpJuzfEfIP6sFP5N5N2uZdCTyg3XEk/INL6O2DS
PgEGlYRCmnHC3XZKRjtkmkTUJE9PHDqJdcgYvg3+SP/sDhVAF7dull9duIzsIKPH
oo9JIvZMGyEMEDUDGG7q
=1qTP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '836342f9-05f9-4bc4-a47b-1d37acfdb28b',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9GaIG03dDbVu0yRgG25Qk9yU+IwfksFfhKqtDjlho8Qwx
d3y8OCnllXnf9In5OJC2oUPYN8Dp/Y9Bj7uENR7gKdWuIYPNWJNrlnDM+JubYXb+
FnZgaXVf5Rg6jhElm8VmZjN+8ItXmiRRy1Fw+90qH70yxrsbsP71YU+M9hbrXT/z
2xML7aTToRQeXYM77aFT3HWhFBnIPhskaDWeZbY/yf/8Emuslv7iy3sxzNHm6G+c
C1ZGOMlSkUE+bQtS2NTFuSYruwlTwZBBXQeQ4nKFrYSNeF9CKpvtoQNfqstaRXLo
hfJMw36jrZ9uZKN5kWfjvTIL2UUphXfpGaNUXN/JNV5qj5iQkHjhkTyxka8IbwnS
JN6afSxjwftLW9QpfzgUIv1hCu4VYX7iUTwNGNGrytQK9FOOqE33prYI8gtmG/BA
0p3OeWE17fXJv6R2rzL91HwtjzCr4gOX5QMAhfgR2RG0/amjGv08OgP7DuZlSnmD
dahv5E5LLKdvv/wccDrjObgfqs9bJqMO1CNhguPWtlypzeDbXz1D4W/SdudnRjeS
PnKbBPsuV115Atz+HCZgGdLtfqd7ks7QN7W1zv8tU2CohUbS0+qajYlQMH3aIJAS
BNSIMTo8mvTe5zVqQPQuFG9a29uJzmINiMB/g2YwnmP5sf28Y9DYruvygwIqqB3S
UgEQIs/e9JYUsv7gxFm3ZHLWfKV1OT0D/501R8xcpX/NbBD1e+8XgN8gGMU4KuaT
W3BcXV+s5NQYaS4jjp1isRQk2aZ2L45HxgDedgispXo377g=
=SSlZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8626af10-957e-4bca-a612-5517e2851542',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAsW14F5U26gS7/YVSPbJ0svVSId/p2LlKqUJ/ttG0tDve
+CNGUqVg/fmq5NirIhNO8vX62uUIYwl3RfVFTCnQVspzdYcRzyedFIk+glDcGRFM
SDSdHP2ZiQhjAn/Z0V4TQQgVllfK2soghAgBezC2gEB1dqYl+VQyhpCzUa7twkZt
UGYPdLxLDCc5ZzV3GWxrNvtAMBUtq71JT9Sb6qb/WybsJCSrqaaCIPKRRscbpKbx
txrfL9Bpjb/9A1Kv9678JuWVxQTzmJ9j41zKqtIgSocWhKbwDBSwFZXmkZM9pXJu
gK4xGJS8hnAaopud7spovObuuyOJ/k+n/rDABAP95fvQ4+R8c39CjaRz9aiWD8yL
svtsqQxcL1EDSdoHhTFY7A0YIwHG5nZZLRKnIvCxyX5qrL4DOfZjdSs74Gw4B++T
lGbAfmeLh2NYZwpP/nDUuiNIa5MYzKp/wPGvsL58fWCashfKWHC1+WeZ2JQ7wQVv
8RVb9PXdaw8MTdxKCITVf29nmrc1HwMVdTaS2KtCI9cUJK5FQPWriG2SIpRCSQLM
l6i/seCqA8xb5VDwWY7AtsiUOdb20Iu1/rT8tPlJP6aqteUc5N+gCB+j291xcWi5
yqXcZhHUrpy6o+7qY54hMvHxIMDKOItP5BrSIrnEvBXUqB6G9FtGmksx+RFXZePS
QwF8ORE3zQZtRYxr1QTNGElJlYKFtSZ/WGb1dyxglhVUP2R6pxpwMimQSQPcBe5t
xE5FZkupGT4b26B7owIW6Qhr6rA=
=O/FS
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '86a96885-b356-42c0-a256-da79efb31eea',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//SzzBU1e+J7U/02F7u67RZehJiNEo75t2jbsT4xsiI3aG
MXr3nczxKBB5AcSWervtNMbQp62Nii9EzdUnZN/DAml+RA70P7TgRXiQjuTNwsyS
KEN+Yn3scrJKKLSnw6We6TqCFhPyMFx5EcieawUD+iBLtutV/DS1IwiU2oHb7bCg
Ksdfjlg0ao4lWWNf0qP9+GbK6QjBj2wZN+vaLlrQkhTdocr9mbhoaEufSJ7HlxKF
mIxZNWMyPmKGptdav/XVQhcBCU1r5ZLObrO7SZnojhuAXryQT0spwhCH89LIkI+Z
Xx4CrcOOewCI3kWFTcR6/F3RhG6rbjuDQ6PYX2SKuzjt93PDJ8jzL5UQG0zi424s
MDPovdZgfdNwHdpQQ7rYIvS1pwz2IsK+Lkb7OxQgqVxzvWBo5ZBl9jNnZv4v41R2
VnGJQD/eS9AAn1d4nCfhzZnku+epHaL3PEDetnMOnRkYhFGnkKG+Y3B3F0iOjJU3
e+5WnDx5m0mw749v1ftj4sHXoFgLUZGSAYv286qUMT2s2BW4sc4vdCS87WFst05B
TbQFiDHV3nXejYm/BKfrr+Yl8cUGm/weYIq8JXWyYREiFjo5tiErXVe+Sn2xcsmR
cRb1jAkNAWAqDM351IovkDt6rQhJVyERAA78rCT/HO2245wI8hsR38O2UqdB4bTS
TQHoayA8QHVUfqRUzvpcMLwzO5CjL/ZhShSCumdFoRBY3sMUPXZuhkErols+k21E
Csz7Xcm970UoZHdlW0D7HJEFOXtSkfTAT3VDIThM
=3z4+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '86c3d793-3bcb-40d1-a744-3a682ee446a0',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//XAEjO2i03tC29YFbVwthzKT6pa8JQJN1kaA6WsptN1jJ
omlqmnd37nYxeVL3rKDQK6RgbmDISGh3l5khw+ceNGFecbzooN2+h76+0p8QP8BO
CkJkpGhSqtOWrPpViUG2R8DdKCfFvNR0i7o6TzkPbct8vyt8W3qU2Ed3oMg8rPMr
BSmWZ/PZiO0Ii9/qRkXy8vEn0bnsCnmTExupV1bkytd1WHZP8f2Ee0rkTfJDPu3f
NRCCm1qE840/w6RA8LqY1NGduB8UjWO7u+MPEiZ8aW3h1jvCiBsUs0dQnPv70Lp5
XUbJ7LTJcoAK4FNww1V3ksp8o0e/WaXj3SHafhJHIxPIyJ10AnhfoSwpKfNCMT7e
q5wbJhoYr8kMoBosVPKhcykSmelZKWUkEBsIIv5JKkj7ftsBn1LgOts/Ko7Q0qlI
v9XDVGPsaOammnYVeR7sv9xrImfJ1Ovnln9rCESNM68S3cQ0bwOxr4W8/yxQaY30
DawI+/ABD45RQ2c64722/OS14OnoVDUQ+E/gF/rzHpaRMoGzvnkTD0aJq7aS2HRR
4OZpbFDnUePnaluyuahpVx/KOb9fVxUeJ0QMQPdVBrrFLAtG02Z4lVmIvJcH+VUm
NAfrKkjeP6mBcaQG3BMwQooZzaLqWRjU3deU0D4SutT/DQRL8ar1BfjjI6uq+8XS
UgFbtOwe5FBSUJtJpjDqPmHg0YICqTwkPC4X9pABu3R32UGfo6CiTLCCozM14UFA
qhxCH6HEKXQkhxGXKbs1qHA0JvUggMa6EmJ+q3ujT1QkItw=
=s11B
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '86ec5ae5-8afd-44bd-a40b-4ec28dcf863a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//c3wNH9FLovj70rgtkiQXRAADDrcdYr6b0fRp1WRrHxby
GRSwA0J2CiKbwND731mfs3ZecH/WUD00cDRx1Lpg9hDh1F763xk7BwMbhMm/5vdT
OQzsoLdAn7gHYuDp8jJxvOQK2zbtPBiQ/VVSGn+NV0ubC5ttUWOihnvoqWY1rQgd
vAynofrd/efP8tVmNQAGgp59QjdJYv9WWBgMOPCmS3UvHyOAizRRXNhl2w5KqlbK
NnV5GxGQmOFcZtPkUsDWp0RYQ/KGFXJeVXWuOiETaZHoTJEOsSaHYv+YA0Qln6YE
GyNoz9gW5T2lRKSJg4C0dA7na0tcij7xbhVPSvGiFcfq0PD+IN9tFhkB6s+tv8CW
fN+LXM7yG/ksrxqFgvHVk0oonuoh9sGpyXDUfW4ZvPeubkKklbW4RIh9ixjtLdl5
w4uffcr2PyV5kVScNDL9ZRHOUUiHzxnaDY0LHPALk0vO5yyBleAXFIaifSIj2ee6
Ixdtj9JhMO00lx3TiyTHtWszLqv0xeHZWQc0HnKXyl/+7oHxCvlkR2LaJUTtbqBJ
RNdv5kcHDsaY8/LPfsw/080Cajv+W41KQ6CajJXBfevtjp2vy+EK9qrP1uQS/K1w
IFFJsMLSpMqv9taDvZyxDXBFljVr08KyjRWA+lc2KblE4SU1O+OoKjPptZwWSvfS
QAFN/y091dnEIgvUJSjUp47504Re20pYp61B6L7nx7PCZyp+OBMQEcC5hnD+wBIr
H73Oc8I6uPU5DWS/zoD7488=
=MXWF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8774f27d-63df-4c20-a291-592346a98c51',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAh+2laLiVf1hg8JSMnGqI4b2NRPVLiGtIhNyJuyR41EJv
Xm5KvhGV6OycscSTrt3udhtJd41ANjatIQskaingHIE4ukUBvZ5HUO8qizo5w6Ry
OWBaY8ikAKo8sd2hCqOgEFmt0v1EjWL19AEvVzzRspW9OueTs1BD8A+LGXKezKXY
TPBmGVX23QR/hUNrsww4OjvnPUxMrLoQR6XL4cMkuEDMPDM7ywFhfA34g6CXtY2N
kVx6L9TutPZn4ONoX3hPDSymtzlb/SVYpc31Du2X1h8dFiPUvFb7/UYUubcPiC9q
eHS9Q73fEG69g5mUReizxo6J4LSxw09kcpl3qYW0lj4S1qHeXhUfRI94MvYS9eSn
djRmdJqm9qwa6hMiqjjo04QxvGitJ9I75RpB6qvkGsjGz14odJ1+Udoh2+Rd4rG2
ZQtIjNTDD2AyxdbskXTDko8ChAc5TMNjl+dk+6ETClEgCwrLuJ4xybi/H24npiBQ
EtjLEatP1+eHtK+eijkCsol5i9/fAeHH1sq4/mzMWOlrEJtCqlaHtA+VMBLb5jFP
fZQruFCnxN/oC2MnI5Y8FngbC4Jj40QPXI/Etly7B2OeHt8ybeak9x4FDaDYuZBw
6tjs/VDA7rxAbe4h/dOMFJeX0Rj+6j2Uhrglr1dv+bVRcIX7kj5/wHx14XiaWTPS
QwEsf0Y8zmheeUkt+be9ozvAktiCFYai7SN9aziO1JsY3W8ffb7lS6XlhaXgusP5
+Zc3vl6/+XLRnO6UZgjdY8d82h0=
=sbpx
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '88e44219-b943-45fa-a267-aa3e9e7ef3c3',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAkSqBy1IZ7kHV8jb1deul6dXXUBRx0nLHIpJcKqCkTzEV
HbBPcb0TSQBs6LVsIsQ2yyE6Zg059b9TunduhfLQfaQLdmqv1q3MsKXCSvQmzwCk
ZLDTR4IFLX4PYQhEEh0374JljVJFDZoS7UK/tBiyvVorOydKmccIV9UEeaAUA8W7
SI5baWKNA5EWYNuQUBcSh53vo5ziahvjShfM2oeOPOUZFjfzMAqBMtnNEbQNzIEG
xJ8CAvBAcSPnhVej53FXljyRtqO53+QDsyUTpf7QpVNAtNYToJXM1VKew/jnzrrC
CQh2LsfZGr2FnwZvA42fuxJnN0iW2jmp7xkGHlAfVD1LJIu0RdWDQcahfoLMTh/k
7ye5z9UBM26Pe6hnxH3ZIk4YJRJslhIYBJrDMNUmK92DFBY0+PEo/QHaOITU07PF
fRtJzV+nq+gFWHiP3/fIHSieyPC5Z9jx5JpKJK80WgWMrhnk5CEjgL22URWaymwR
N0hCIIt+3oYkTy7Tv43A0vSmcjT53odBRWG1hLDsb1dZ8mjBVEHK6JGem0Nde78s
RUsGGEULkkejJTzyJKQN7uT0JgWg8jG6lGbGizUCh3kA4mQC5mZQoGk15f7hSImK
YfK6q7kn7ox4/KF/0UV8M8xkdjqB6R+Trzyi4NQfMAWvs0+fknGQhvXTPpwvU3jS
QQE5EGvwaGNMA2a8IAUYq+EyNaFPiFH9TmmVp1GammJR+F5ucxxgJlvtfQ2S2ZaO
oRl+7DFs17Ed85OX2ObffTF6
=mGJ1
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '893443f8-389b-403a-ad37-f1d1206499a8',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAsB7gh5jntUwWe99i6hcsqrSL61qHckWqyHr+TeZEYOWO
u4npDfYbTP1vPN4/6h8FQD4yQo7eTW4AJzeemy0ud+cVTW6d+vyFxQqH77sJcF+O
iMNm6zAjgMhVI1uO9bDd8dNrz4Tf1Ur3jQSfMUFk4tE1QivvolYqMaaNKCVHFCQo
RxVhtTNWEoldi104Futau299H9HH0BW9C2ZKWQJoKuHV6SGheVz16u4D32DtEbU+
w5xAlyTtt/GzEh/xdFYhw1Ib6gfPjABsd8ViYbBe3eyQWRk0k1/QShBkAG6iGM1F
Cwvd0vCjxU8Zq8PRWT31jq7+CMY+cWDE9CN8yIbiaMEyvsGUZtcWVK35fnh75oW1
vhNTsUc8RZesmxjfDD7x9s0d/yORS7j/dkw7AxeZSUio9C+Fi86Wn4/8Nii8vGvP
McZK9Cq7RqO206kiJionNb7Sf1pqPTg3y17IlsmzLhG3wMFRr58t9ytJPHnDcLvC
ATlrGVOpkqdi+HXSOD4zhsxxciEMF5lxpZJb5Y4LNhAZA/tvYs5Q0bepI0oc5+4L
jtmciaPm71yyCjjDN4f2ZqsHedHmt/kVKiTtkMbHF89qeV7iurZlDbl7WH7L3L8L
lIBWsmNKFhj2UPNMz346+AsxER4bD2mgPUwPzUXBSX9yPwXgOGIPZkAu0wjgm0LS
RQE7Kamjjly3mbSlZEZEzN9vqGotJfqKMJt/K2CMUbgHPNS0mq5Hg/sBc9j3Hp8M
6ADpespKQ4O2zqljTMK+Is6XWJVxrQ==
=Ctqd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8951a79f-d490-416b-a045-fcd3583ededa',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/7BZlQp4k41ZdqXxAWkGW3fNW/k120uRFFRS0AbgoLQqWd
JKfNtDVh8lSLP79pTC4LoR0E9sgReFQ4sax8we4MiOprlbPL8I+rDlpg4FZWUGJ/
1NLaYIJHT0SOzfwf+/XWTEY/RSiwHVUVFCjp77JbKMicgzxGU+1PcVAK/Ys52Y8r
wIrFj725Zw7QGqnqY3OnvjMUk5jI9eTlBIUO52871xfdmxFN/Xjxz/EBDwjE+W9V
K1FUWlLr+vqv6dnvZCp7QhjSnFQCNSDb9azARcxYy5tUcSd24Cequ1cLVYWxKoFl
HxWp3Cr8Ly2G37KZFkcYfIyD8nxcfF+Emh5I3VfyUoo0b9twWq54dHRvrcGM4lig
tbDxYistNqykUPTPVwL/OliDDKiJDm25ImTmFk0HAqTUzKc0Lta1g8j4rdXFu9tn
zNDM8F21DEPKfoAIykGh6mjXgLkJJjkN19RIewKMiHvM+HmGOu9kozOlJ3P4+Mgi
bNcVWynXaRWDKoVuFMbqbfjuBqsgYSMx6NG6kr3e3p6+WpTDaZY95kQMSBuGVs70
5GYcsAmkoKtYSsTcktloIHCb5FRMsztOFYmV1m/HMLcmC1UfdjGnn5jcB2dnp+EI
aSM+ui7Un8cnaIXUBGPtPKnIAuUxBDXWaCSAQ3AAIqSQL7Iyci5fwWR5N0yHNK7S
RAEV9jq4U+LxbUeBkfHlN43bWYt9Qxa43/V8RyVIPxoEUK3OP7SmCJZqSt7iOomk
UwUM8wk2IznvbrENkUga7VK879nX
=0XmS
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '897a043e-3808-4e98-af17-73a1490cb636',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAn1ZlKNEGUrcD8WaxMIQkqagXs0Fu96YjAyCuFMu0UHSq
pJL8s4H++1o63iK2TWf4QESUBPYTRCFGKzDhFOgZfaiA5A0vxrilS143ZNOyB+7P
qE3FpMEuCv7113NyBK5yQvrRw4Jvd6jbWufYMNZkla26p9bHgwD3hak1L2cyl9TU
0YaEMeBuvX6mmjE3HlLp0ouznbCIzI7XfBsN/CO5YLIetoyvBmyNutpAc4Ka4our
kbLEeOAGZ0BYoBY0swMwfzG5Pm/uUncl06G4uwkg9wvwGgYW0qRa3XruiIdSVl+0
m5Mbbnh3Gzm2w67bubq2XNR0vaFclpQhWX5MLiG8Fd0WqZKD3iCXpHuDGuhkp0oI
Wae2B3v0scbHD/zDMelSkMhrgD1Fe7fVzvIIyaUceFem7BNVXjAK6WuwYsaaXpDo
G+tAZGDP6nKFAvu970R7H0lexho6JEdr3CC+2O8vh6oChxlb9cs24il2TNgs7xJu
c79IsVJmYkO0y2FKAiMN/B7hs5po7V1IFDndgB21iiLAlWCWUTkLWgfeeBsBUgV+
/hPtZRMvz6tb+WguU2Oxi3UFyONvPgraYrkl813wUgIj2C6NjR97V5YkovipgUd7
pJz9UTYyfD4BCl3Vc/sCNW6jByETdAs9Y/zxo8drj3yaHQn4jUBd28zHZ5Dm/ZTS
QAFEi4tZw+N259yUK7gLf+hxTEfWZOSU+xCJQD91wjfYnSet1zVtLlGE7Sn6GbWy
jY2dJzn68pJEe8/C7VFVqf4=
=o5jg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8c853bb6-45f4-4833-aa14-d7450c1d5c14',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//WtWRoZBKD9fGGy74aSrvZSencV9c9PbSOIAKp3vUgVLx
ERE6o29l9ryumI+FJ/cFkwbz99UnDLLTBRx8p9Fm0s4/1U3a7wrH3Z25PyPl6v71
94OE7XX6FMql2F+pUHgeBA7jjPUcJl4skjzy+u8iQPsLqrXvQSdyLkNOVOusJwVy
jcdHijhp0H64pnF11o5VGMGRQQFbGqINg6yH+XQmAPM7R63BIGgAOhj2BfhYGoSS
BSFgb3R3H9wQSBPEeOFkhnd+RdigGaIlzI5BsO+ZjA+4CPSeT9YS27639Kir9MfT
rFmRqSjShNXSXz8z49FZJ6NeIotNbrQw0uU1NNh/7M3bHFc3OWyU7H9fi2UHl19F
eIJgY7cm1zUJp5eZlA0xgps4osEpavlC9BI+xh9aMKwbM9/Xq7iUyPiiswwTs/dl
LIgEAtH2hMGuiBOLKT+8ruXlmJHOc5Vn0Zd/vZh7Z44mWPCVe3zgWfbu2hR0IYjc
MD3rlIrRZM/2t0l7W5wNzH7OCyN4+LFuKAanioIkVn3rQJmvTrJ5/+DgdGYndajr
CalWoIa8Yml03kHifJ1UZbA79j/XDcMkeQnFzUuwngsMd60Lg/63cN28dFe4n3g3
y5gyT9n27x4iJc7IVXE3wFroL5teIiYpy64ylpcX5WILlggbvk64jkhN3ZHEk37S
PwF+SbE2amrx01O3pypZxdzjP6hEKBzbA6qm+SqZ05P3nQ6GpzHniM6g5zmnAxDI
ZltYjBblfxIvsWW0tL1iJg==
=BLtP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8ee505cb-5915-4a7a-a0b9-11bcceebb7a7',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+K6RlwkC3pg0oa56mPWts6yr1FUgAmjoi5Yz7wf5g+o8v
okqqyfk48+sPn7ORPmf686ziCcu+/V4MXo6WkpRnFycr0YJ5YB0EiI57W6OkqH4Q
5lQX//v/QV4TL5NR5IvGaCCAcKx55xOit7c5jdxf/6SLW2HTBALoz07/e2mfI9ev
/3Db6SOhGpLAflbExqnBb93uyl01PRyJ+MiURjFmGqUyptSDyhP5jwSVKHeNSU23
nCN+294q5Fpcxqr79NPZ0YMW9Nk0qy3tuunluicj9p86yTG9gePk+UddeSgidL38
0UXF8u6SeDwr52Bq1noT2h+rd1xSkrpJ63q0FxQi054gAey8ikSfBB2IkQq95VEa
2ylVaz2UUti8BLNP0dvB5OznVQFJTwaxcawRLWOT8abXL1thwRJySpiN9set8X9s
TZ/iDrSHNJ0rv1mJVaALCHqICzXBmR9VzK+rAV3AvueBIHfgS098UUgwJckbmO3e
RCOE1yP4ABmlVjxLxl26LpGCbGDcUfxupzs2OnXwpsKXia1NKC54T0IMTfiAOPb3
S+3jw7b9bxBCyeIPWqIyboWWG1Tn4yHAEAq7xfe7NTcmvml0WoXuh4gViyBr6ql5
Puy2yfB6+nMgVdf4sgRRNH6L2OyN2DjfHiVHWpaBm1P1PCh2NgAZqm0+4RHqw1PS
QQHpQ2mHtE3tZcarmqzOuHJ3nmi7qMBPS3EUkKMbW61uCh3Fr2JnPuNrC6PUEFlO
5+SIHi24g2yll553O09DXcGL
=qfhi
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8f760472-f8f3-4ab2-a469-830f89a220f1',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAkTcXtS/qQKCWZkrG9wBjieGMZNV+6FZX81yKQa52ii8I
17MNgzb0ex7yV57qKXKzAoXtTJVZoCjantf4uA5AcfmI7u8g7KWRnHhWg2N78Br6
pWqETCZijXmDSNvNuS0lOUBswwqeaySUeCGWWMuBbeDIeGmUIr6UJfi/p8b/Fy5S
oqeGrbcfHd8kn2De/ezPa5mugkUwHeMa5g99uT/3r4s7TRM8+2hkDbopuzV1+sn1
VgBvD7wsgWUk2b/RNGnxrDdE3MalNnfCIeRQ9M8fbfphwbiPHtMGA69HtjWURDpY
gOmFylko9dVrqCIZDRZATUVZFnLq4sy1AffQmMvxfdJCAX/+IiQYsfD2LF0THo6P
XdT7op8d4KrNAD+2ix9FH5xiXqrDCuSzhCVYqMAukHk+D+dWBn6X4nIe7j594KWl
PDBx
=uw2t
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '90b7f81b-ce75-49b3-ad27-eb3cc31244c2',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//eTQsmhm6UJq2FiylpWwrfu0qCdwwAdtjo1QD8ji/Qpk1
5q02cDchkmQQjvd4OS96qzPTHhPtpGCVzeLR/ahyLWnHwn2KjT8xGPZwxdQp5efG
CqMpOM3B7Fpik9KwIjWuuzpn6wozN3YWKDAaPYEjvr1IeDSGm3/9hDti8PZxZ3pm
KMhu6zIjZOaIyUaQ7gBqIhMJ/Ihl6qmbQeaZOzU5BseEDoCqcprtXDn8AcovEg0E
XCIyYb1HK3QEpX+jCxKOk2IE1WrMvAnv6tuCbMs1ubcqr4FTE4j/Y4p3WypiUlM8
IsRHY/6sJgrSSdXr6sT7bClVpEI45nllUa5IjGbLdEXqYf2uwmWNzdpTTvqa3Hpv
0mrFkeJr11zNnMQN4ljlY8zHnS4jEp3TwPZdjhawM71G++ORNsmXgDh7rolFh7JK
itfTA9KS4l6DjGNXe/rsuBbHjdaJMNyX3iimIoBnOgkaR1shI27Ko49DsURyFBw1
k0AbAs3GRHLuP+kpVYXmvPeJjAuM0NDZkxaBC+mrr4Hb4BhCSUbazzigDVwioQ8D
qaWTpMdzCXcq4+tPH0QTwIndDI4VBJ2ImBnlTR/3Ys2isMkNz0IELJOqUQ7+m5+b
Oph+megpQMP+kAsNVU0gdNT00Hblsb6VoiekQF7SBDNj+/dopot0b1rPgGOapq7S
QwHojXcEnbhchEksEzVCqZj0ljuUGg06eGA+9DEYxeP6DE/jDotf1pOlNrn/70Le
lSCW4k90aUItF7G0TwiMC09QZAg=
=ltg1
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '911d0ffa-ef0e-4a36-a473-8a9ea4021f19',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf9FObUDQEPDfTSXMG4wb9xTNempRh+4IVx+G/c5RfJfLRy
xTY/qkbO8HzfUlsaUp5sogoy3eBC6zFQKrotllRKJ3loko5FbLK4bH0ME5ikNbyt
VdbC7oWjyEGpoTLqQ7Sk8mEVsWOigaUi9OqnElnD7UDneQL6L6qSTELlS7XxNJa0
0hVB4DRcChtjhke8PDh37Aoodx6lA/dMT7XMwmOI+zlPCWuXDxAqDiOnn7hddALQ
WlI9Oezd5bxTT2kUQtqmmvLViD3MrgyH4fEUJrVxU99OpIpZvcFrkG439m8I44+X
ssj3USV+5JkdswvDPh6Gl0GRnidC8U9jBVr4Fv3hI9I+AXSEEgPfgfMWYgqHM2y7
LOff1DAHxz+oY0m2PcSWV0j19z1uVuUhBp6cIk10K0I4/FOFRaJkKYK/9sxiKoo=
=HNOl
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '92923f83-d985-440c-a14a-16d10faedc8d',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAyUq2wbhWbDOm8MjssF9MLz33+bjJztEIYxxareCEQB71
YkbNKGKZjBLCak0n1b93icwUbgfshFqs/Th2PZNKuhfCXziXDGLcU/mGh84W9tOm
m2o0EZz/vjhyHVUBTnKxx4jFQkNXa1GgsnfICVq5Qx5i3HPxZuyHw7fzY3s/LkQf
l+PyFByanimNhP5Fj/TWh0L2BmblzNkiirZPsimKljVrqk4+GPOEBb7J8jfyMryS
ZBYidsTQ6oHt2B5dYTf+a9VLvVe4wpOhR6JhlsInPnPPD6l+1fLVtA6P9loyIUvI
AzFVicxOE+Wj+FwQqkesfBYJTYK3rjsJK13TqJZIFhW8R/QzOJYGxFbu+HEEuCt4
U+9F5tudkjbGEpmbJgF5b1db4gczkbMMBKWWMlXv8rWpV1cxzgiWI6LD3sa3P+ib
tGvCowy4/e9+xSOZ1RViDhq3h4NIQNZXhFzb94orFL/bueHiHtpsZ+fPcsAgD3vm
AEYAQRb/v5MwseSQywhg/dYUVac6bEvyc1ur2Dadua1OTAKHldZkWt1vTBLfP/4R
FiqBTt8nW5kLgp/1bHouj2oeukIxX3Wj363n2UWJLP6RIycytrDdvZJa/ffV2hG7
LA5CqTbH039KV8JK+1BM9HiqFgqsHCtLOzQ0BKSF4i4CCUwWKJmfaM9z+lLGJg3S
QAHE6NIVJbnfjD7syuGOm3JzMlU4nEWSaYA3tTKkHhRe860YLGwWOad5jUz8tvod
LvAqOoPIBlrdrjypKAtG+QQ=
=M46N
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '954462b0-499f-4344-a9b1-3484ce53ae52',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf8CcXNvLJ+LJcojoQNKigZGmqnPojpHJD0QRo9r5zw0EeJ
d7HONkLW6a4eQhy5mQaL6W6MHJpz7CZPymeTu8xx7otHBVUU1IC6yvKy9Ul++Sq9
OXieDQYjD8FRiZcutxaRE1O9kjfiIICY6nda974kEmTjegLdGLxNHTeWJiZnY14i
czY+NHOJUD9uSUdlwTMJ1czqg6daDF3azOppf39JunZHWZJmBtfC3ZuS75GX1bME
99ebdV16YwxwsRp3kfqh1+yvD5p8cVLHBhCMmK48QnAJXZtP7mIc0cZ/o/IW7K1q
YVZDNcwiFIbbCWRGXBh2bXwyKSOXZqJ+ypBmiMRmttJAAbiclUcysLuhKAmwnUok
cWLOnBUFt34VFQUC4sTtvbSpOaI4Gh7QjqwKCnML/8Rq+XhMq5qANNaVKVgwzEMs
Ug==
=cjns
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96710424-3590-4dd0-a78b-c33b44cb796f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9ENK+KCB3aTzce8Al5dJdp3aBgfethfQmaIK6XlCIgB7+
O0oVfnGr1dSrbftO0We1DoGH8VR50cvblxgTfe+ZFZRf2nFgUA1NwZ6s/EsJC/1L
UVpfnsWtKMwH0uIsECTnzPTII6CzKt4ny8oGXL8iO5wnSDUHL1FaM5BGu926iFOl
p0QYVehYvMI/o6gOQAdyw8DFZ4hgpnMlUylsZ3coJkte0xQfmGDby010O58Gx92T
TRdyLG5THUEzqCyBO6tpQyqSFFTho25RmTJ89zmMXhKnS8N1YsxEs/HMvT2LQLhE
j3EJM3BG522Pg4kN0wvWzGruMoW6fANUx/SIrecbVt1PgOzRGNjFekFAVn31Zv49
7Lcxx1C8pvZmsEQblt5LAz95I1/kZrrC68VDaFQBkxNIjbZXPngldZwOowq44Rc0
h55k2+ZYLtSiCum6zlkgq9Y820wmUzd43L2M71TuOPhqCCXgnvB5vQWkA2XdstUo
clwM5MU+6KTquga4kzCsQ/ks6x5nARY1t/HwaUxZFp9IBcg9TWJvDVzs3dXrol1o
Blsz3sPnhbRQvVQDaGDY/JOphEtYasaW1FJQDsVsQlJImGBrGvXzQ/ISzZuPxGY3
a03/zKF90ijr7tgD3W3LgH6L+0AOqS+yqq95LEP1//jK+e5XYDk9K4ECPD7Wx5fS
SQGYs4mXdTGJhmrSlaJXL4dMoBiJ4zNOQ42uL5v7NMJ5c6kSZJnKwYyXUjgeqcoF
GcXPcTSJBkxuCGk7mIQtMpk3IGqz8bmnQIg=
=5Ejp
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9746d784-84f7-41ff-a6bd-1cc80a4d7fa0',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/bXmIn0VjaCppHCqZAj62Ka48Nizuxs6jL7oc6KNhodVZ
jAiTJeOYmRX799iIHiXk5nXXjMbtEf51IecUZ+F33FTslMp7juHP73pij+gwIXUG
Rrwm7itwMOxHSRJdO+Mti1u+7svaHXVgZOMZLhH7D9sqwJOgEGf2CD5qhojX32C/
lI50DQnh9hQa08UJfp2pbB+7l8vK/ZyByRrZcTl3p7RcAaatej4edPa3wzqBbHuf
XccmjsbpWdX66JYqfkE0VPXMvzijtY1DJMnEAdrF7mwF9awDHul0ynB5GTkkHGpL
kElR+FrPFLxCqxO9n/hMUeRpBcgi/xc6emd9yffAf9JDASqDryGCktv937eNjmeK
QaDvmD480cSkh6ZWbiexnjTQPopDhKvmx2MsaBqig8k9g1B+QZbQY98j7kveRCoS
veAU9Q==
=JSvo
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '989ad39e-a3d7-4b60-a369-6bbe8b81a0d1',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//fmrVpxvKbBteEJUJ3tOZuHtE5Rfb63evQc7doiIVG3Mc
GyW0jwZJjcW8V9nzKwsovcIwJ2LqWIBk3zfgaftIPUHog7sIUip9hajzx8T7QGJg
gLE2lix32xK7Q0D6LHeLauvDZkesMeen65UpyHT45Q/z3CqG4DMyXKYMf0ncBE6x
AGEogGcz619eBXyFYTeukP9KKRzQkC7sy8rg1e7HJOGd9kV5oC3c/UvH7ZaLSntZ
6ZKl1/jDfbZCvj3SJZ7DTWDLoG+8gNuu/cA62QENksVY8+FXFMaL25oWO4hOFb1s
uSYC4NXHCuWpVAKCs4gaGxGEpSFr7CQtfFvsf9tNqX3V7L8HiaIGLd603b2txPO8
e4iXWoPYwXcWEveSVwQT4uqJK4aT/QzXNY5Sq8ZW8F1zJrAlAZZfHxWYCnzaKIDu
P+jRy/Z4GdxPHUgRgtJmCxSV4NWef130KadtvKhLtVBV3F6LvPIwzB88+0N0x4D6
h6BrsLzjdBysBWqLfQ3UBn+GsZiYNXg7orsBfCpubzA5vAN1XqvQv8z11/CCE2EG
VbyCfDOeAVtN4uMvp25fI/hy+mV1mSezlAnmw66Bsm0yzInyA2NL9rQz81BmX7zK
19iOZIrrCxvILVk6gWbpFDTKyDh0AzZGBqdatNAaEqYwHwEkZnNzn7OsrGFsauTS
TQFFaCEF5uVhYdQ5vPfvEwq3bYfqIgzphO7he4wiBBT7e8JPYc1gTEfrYxNA5FI9
/wIgFUVNQpmMlbmFwbrIqvx86gIzEdFhCJqWdXMn
=ZbDj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '98dbb36e-6dbb-412f-acc8-8e7a4b78382c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//WXKhZ9fUu6wtxwtY5wcXVnT6iEG+j8ybMnfcCAY82zwM
2ebrGE1i9gKHLJgOKiL2eAeRZdG73zZ3+XynGzXEXNN35UWqqV5rmUq3SEZpdYoB
v4vZN2N8AvRv5oCUtCNU8ydC784zoFVvT3ugX4kKUSesF7ijWxckTwkFNvDi5Sf1
Oso72YuFWL9R4uIOHl3uRbcIBwFf6pgrMHX8I5hndSiROcf3arhAlwfGBQykV1+t
zcxwViDAVpTwh9vDsvfyPMcdWTdCypS8ZiGnM546Yv+nB5wbSd4pkOeYsuxae/Wf
8C05e6a43RP31tgaeBinbFjIdC3rlPsuZq4VjGfij+2ep50PmwPmSsSMXI48CC+i
SqoLVLY7xMUy5l+DFBJLYo9Hib8S8pJDKJogu2vr3Loa+JUKliOM3Yi4JGLWH1Je
732GNkDat9suC/zLb8SzFqg+x0+kwH3uskq9wvoI5ixQ1AEzXLPrY3bFQ+MiZVQ+
R9o4PSn1EiuyxzBCqi4PZJESUtewLcHAkuJyTNSLWbbQ6E4X4acwzdDtZeusv8n3
mzwh5JudxuD0m7buYUcb58Kw5gwWs8y/10PGlttWFiZjUTjYw5YmSmI//xPIFylE
n/U3ImZQ5R1tmvGhsSaczPUEq879XhiWxIxeysFhLUeecKtF197+tB227wTI5x3S
QwFq+1vcoKuQ4naovUoAEqn2dT0jiSEZoAynRc3YRbYjQQe4U5DcwQ7xwqz2ER4D
KdQe1hNR1rpvkX3wxNykD287hgE=
=cq93
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '99f2bb73-967f-4719-ab30-cd80ce1b4983',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//a619HE4yOMPj+pvOR2BgYyX0lZymYuh/wH+ep6epw3PJ
I7Ga2lwLlQu5zuhVPjcQCPbJDntwd5SK9c1YBP8ksOPw+0CQmlGwc1/95H7uoEjj
Vq8Pb3k+2dqN1HGvU2mqRkA5HKlWwILriYr5yGjTE/loJF0HEZ6rrPQwRsZyf3Ec
bjjx6kUEheiqEk3O+MswdqIoEnwMV4iDT8WBI9tHxmACFf8jWRhmTP0rkeK4BVNF
8vANO01J0isc5/+58iDFiZTGFKtr3g1omgvclIRIjcwmcQDftgc84Vu+IqKdpfoL
NqXHbDSf/8g1KoQ3JEb2siW8Af98aXQ1M84X9mmWGnIJl3c1fH6aTWzFGjFc40kY
Y+J2b7n7OfD3cyLsg9fYkpd8vMRa18mKNcX67rCuW4A7V8gVIjNqslKVj1XLbP1A
e6Bw7yxHSXVovOp6oZaOXDMdKR1P6Yy/cBMWjCbRf86ZfOIe3rROlICvTGbN3m7G
fqQKQ6tIb1UCc1rczyTiDjYGwNrovOTVMsJpwR39wqNUCLKMdCzk3h1LOe4JhA0j
kb1mARIA+ZHuUx3Npr1QbfZXGUan5EowB/2IatmpOx8yy/MV9XRL4VgAUE6U0zWv
iRSFL72imC7Zp2NIS7kxp4ERQgsuCtCXSVAAkyzfpJqHmlyqfuTISyMtJ2V7VwDS
TQHN3RiN96uPzuBaloS7mUZpFG8NRcEIvd8R0/TfdbvIutoSyjug7yD9gkZ8Ze60
Lz5bmvpvm05DLzwsamFp2INbxXClqWkxclwyimuX
=4Acu
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9b25825e-9b3e-4b03-aa52-b50111a8bb73',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+J1QivpxE3CUzzq1iALlMxHowbl+vxfJwyuGk1547WpiS
fZ5RGOARUzvpTVQ4NLJHDBBTON/GXWdMcNkjwi01FE0fK2/sgrEDq7i2gYPBde2V
SSi/Nuy90e8kSQuC3cGVeigDOlZf72FBT/zEznj8bavQdkq5k+sOrVUd3QL/5uu7
aeQ8uA+UFJ5Bk733ydv9yo/aKmjJ1FOoGU0QzsIhAohC93tiVMs8eey5N+F3BQm0
aHuSC7BG9tuIcE/82fP4q8p2x65GUTpsQ+0r3b8L3VmF4pFs0XDuMFK88hlsPjms
oMRwkhpDiUkYBdoUmbso9ZcrukbdyE9jegnvQCd93wpPbzsbCWga7u7MVdyy2BbJ
PetNnufTGwwzo8kWysjRQWJbnL2trLXvUaQh2R+Sf1AshqaFb/I33RdnL+5C009A
V5NwJm5gtYZV5qWL3IJrGhre+JEgvPdpEeUGd9ayvhUCF0mnkG2ppU99bAUf8kLA
nTgv1LAVsER8lIoaZEz27OKx00Sr8ew5NwWEvHaSlafGjQ3UJzcZUuoPFJHw1mg1
k82hUstLYsrtwYkDn2k6yTvVVf0w5NmSmNO9OxDsk8XzO70wcvKWo0WwDAoXNuv4
gUEmQ8Y8mXXS9Uu57wonidbiOUqFtEvUl8rX3UPFsdAEXB/8G9hRq5PmLmXdFUzS
QwFij/F9Ww3QAv/2s5c4ULQRJfnn+/rzxy2IPRYkiNY1ht7FA+qc+uOanM+FKQzq
qwkMapqhSEJmkiLT1Zh4bUMYmU4=
=+BbD
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a239347a-cb19-45ef-aed0-d51b7f0cf03d',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAoRBuZBbZ7stE1wN1rGVXhlEGR1O+bGJpMXzXwWANnB7P
alizuEBli+62hd+vAX+ewx726eEM/xijlzMCCX6DfodaGSL4Y30DrwWtLQPjPErt
bOizxosZ6fnxm/T/Bi0K4Mk2a01BwYHqKEYAhrpZuJHJ+dTVsbECCgWoe8TucipH
xAkaUVqeI7IwgDBPYSFrK6AI3aKxsJHSfi50z33kRDLpwW842J6kDAzaPvwmwxfp
tk8yX39ia5KUjpo3Q6Fwofqc2AWuYmMJ5npNq/73fSBZ53OhgWK6uBoSINt0PcVo
jbh8eqBsWkF8N/QOiD7Rcnwpr64vbdcVzDQfVgAVq9JBAUlg+5hToB6oDI5CqtjV
w5AY5gnMqDFkwdlZ3YQOGmPXCRmmD7CqQrhnw72FQ9VsmUn6OiRgL8/kEDHK+a6O
YcE=
=Rz7F
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a2d4a7c7-4fb4-4e9d-afee-2fef2a21afc3',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//ccFFS52FiEHNyWGe/m36rpgojAVRGe16qr4hi6NZ6voN
mV0BJzSoyKuWUKqp+/0gIZseNyAMKsZqB5USKyho1xpg2MJkfSBusEDonV+igDXR
KNdDChlaXHtSWJJ9AT4WSljWkVpRNWGVnfNRkg5hX08mlPd8oWwKF+IYvBRBFM7+
4waVAKc7j6LKoaIA4d6tcVkaq+QZ1qSuvCZf43VkCzsTRqf2pN1QlErvKr7XyqaE
VETP4EQYQox86+l6tMIiJmjgInUtyPZmibaG0yepONpvQwY9JfyFsYNrE8z/K4SG
wbpPshYm99ARZHZRXk3HGBpp8pzRusLBnMeQYM4c6OG8eimTS/psPMeTC/SGID1r
xBECjA+EMJJBKhc5wfj/nOFiinvUgP/caHUIe7Wml7Yk2qGhBgoGsRYnh1Xdxb1O
+EYJQ9kJvNhx5BAb+dduPbpKDkVVBBKAm4a5ej0/FAwKI8YAm9PlRtGatXPlShu0
/9bHKPF33qlfwsySNQgW28NHWzCA+I82QncQBHK6ImS6MMsdNd2JN0u36uKUxCsH
QxgYMS2FCKiUXaPKpdGDj8dDf0sD6QYhfbmvFmp3ed3TYBVcOs/z9lhEZ1/kHkXW
VvoscUg1m4kHsfwSh9pmc0r80m+RLmZyyDvTYLlsGSwOILXpznaLZU2C1+T6qJrS
QAGSuTCD3XDUgNJG6G6f5YDNzdyRXRlFmtX5JzofRIhssQAOBxZA3Mgz8prTET1a
YvGg8Wy5lQVnjKLGoP1aT40=
=xBQc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a3c420b4-fd48-4a15-ad67-b849da5deb5a',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/ZF5aJpbE06YDit8IxGSDTmFwp3jDkOWAldcopr0zO1e1
dsuHciZptQAScxk/YkRbeXzd7ss8usr/u6PsTrJT1VIroA6WpXZ+qhig27tfvRLj
XHjpcESHuGw9n8fS5BD51d3+pb6Qca51IZJ9XlNurPO+I93caOFfgD+pvJek9QGn
BmJwfF2toEAV3/oi9yCWic4rVQ14N+hLEP6JWzYgGWmyS/dBLomG/kJxSxIVO/9d
Jih0Mg6pdFjs9J24pzsgQGk9TCxWLeQmcV3MD4LA2eRN6bDCJbTzmVHuzWiPdKR4
41JpSl894zR4oj45TV0FNbdGnVc8DJYw9Cvx60AI7dJBAal34b7Ds39DhvJ1+UuH
6FS4ibeao7M9WlBQ8FCUF0RD4aCdeOGxXfuXeVR5Qgbhg/PW+PGlaIgQsN/RLOcM
HQA=
=6jeB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a3eb6e48-a45c-4e30-a43d-3d8aad782436',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAp5xNuLiVFmtv2RPTaBcy+E16FXBWvVbHFKzqssG1qc8W
eG107wzZuvcPj/CwxUoq25bl1dGCKyvFPnEmxqXtjQAgjEuAv+JnunpgUTZZfy7h
py+MFWZzJrLz+o8XriGoE2PtUQqtyXjeBAPbpqoCDFdcEd3MiRL7X8uL7BJ9NVUE
QTFSFFTk9T7MNw84V7dCwSmB69levGdDYFPcsziS/58snEZw0SWLIUoW5P4EzBgi
xjLD3sxh090JJ8GuH63mqUYvt4kB8vbFmGhY3THRuLLO/yuj4I1m1ZCsU4dZa3rU
hdVG3QldNrsXa9Ab+JpqccEgbEu5DhJpwhty048nO4LGGXHbHJ87s6uQUbblvvmE
i/oR4UY1bPeMIBfFU3ZT9xVGur5tJQ7/QJ6hqyWi5PVl2Pk/UxSTlU4xQd4rWRl4
C+0f+lG8WsRbis7lDY8jTx+flcvlJ2MwflBR5ZmHJL4vtLVHfjWbWpv76hvRFP8F
RhL+dW45fQBdb5wqlAO4SMhiMHGNR/ohIcIStZkyFFQFodf+lJ+0p9FOLg1f4lRt
JsDoKizyRJnCRtv88xgBaQSQm1mq1aWHQWGGsEs/hxOwj8Ech90+XFpPHRPUlVCR
DcK/Q4G+UgBmMCyxjsk//QzuCYk4WICaGD371rcxjGtjmpds+mkZoDqLzHtcYMXS
QwGhK7Ct+kQF0BAIWBp6lR5iXGcNtXW26b262lKSZAY6EhLe6Bc7jDqWl4oNG4ZP
L/3ej0k2mDqizIiQjoIAQs/3y9g=
=rkfQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a5403ade-d134-4c09-aaa6-82eec34cfa3e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAg5jTi5mQHicDbBKQTnnVukrsBcLOGrFuwe9saBezV0Ll
ZRBiFt8jv/nT/XK7r+QVbG0h45amLw7Xu3AeUKmfUpxka9PU9r936OIbojHpkEA5
Y97atsp8bQw0PXLCBm7vF0B5+1i3vKUs7ii902QSU5z6nqjX3dGX//JSRdNe+dez
I2O+t2xucAFpTxxZGRGYs5MJHwDD8fFfN5kb5/RMgk5I4sEFAl3zQEaiWz5vb+0r
d3h/r+qgQ8x7nYdi/wdF5myBuZXP7urKQ/aywvDkgVZtEo8Z0iw1gseg8Ei9YKKZ
uUduOjHTMgNr0TIIXkWhwyaN/96os5gdrMt/rM2hRvNqH4uHhBeG5o8Am3XvC4TZ
qDpEICe7k1jXvKJ2fnpJp45FoxlpB4UOQ1cks1IaeNQQ5KccWj2EE1J29Ad6NM5H
ysgX4DXmW3DL/V7jUSSalta0UJNtrO56E91o6bCia5rKvXHOrUgjD3kOVnFAHswc
WURgYrlEHh3C1EzJj6hwsL/6o7HWckaUwImHvksxDa+RtSmIwvt55jZR6ZQ4xrOM
gWN39cvHTqrl1oe84Cb8z1uA3dW0TJG+xNlEis2jVp6L+YptVnKfbplF27VQ9B9d
Y6/FxNnXjqn787fr3ifDfNpy68bfU7jFSRFtHaOLVPn7FehK0cYC/cssP3YzAsLS
PgFoxFDwY5DW+Y9z8z8fuuejIj3GjdXu40MGd7ETqaaGTpWHXPc/z1Ze7QxjHJEa
1LBD/LxtClDK5/6R5EO6
=Y/40
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a6334dab-5103-4ac7-aede-d060ff06d931',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkje4/i25BH22gxD+K/HidlUjJBoXx8m8ksahlnd3aUM6
cBXGYk8oV//TCtM6xkqAerMo1H4pSJkwD1R0wZ0RRZX37uofb1qkqZBXRGzKuG1h
3Cf7il7HIdSMS+34t9aHS/t6KxxwL9Txp/9lflSMtek3pA7+6eYsJNoIixcy6gfH
fT7gze1c8E6F1UFF/JjPBgxUlFld/nXr6UOSpDUQLp648929n3ndoBabnfrzLsjN
ZQK2DNe7V/w0hz6XLcdykWQk+rOuGVGFR9nt2VFC1UlhOGA186pyvNElggvSJDMJ
dAaGF5wC2fxceuxSC5XkPdgjBiWnomn+mrwbS29UIzkkq3yD0Nsodg15NVBKkgD3
/PWBLoSfnYN9SIP3oKyHHlHxMBYUpqnf+h+sU2LKAkIuf6EmlkS/Pfss87wOFiDW
vB+qjI6IicbnLG58LO5S8XhInM2G5W+9iu4hBSu5aY5HwXsPFxOeALxFdIZCThDf
49PfaYYPnxEz/dCBVPTmPqBvQIYf4r+XpXE2KseuAjhNOg0e/5/VeiLshrtPxXNh
o0VDwFe4+vimuda80N8ZZ02Qmrw+usyZFgTGY9hiTsUdwKHHQJa+fYHyfm3y2rT3
tfgxXCtwRtn2joqMsZ77p6FsI/KyDoJ3ScalwAUnwSPQJ/dStPY0Om6WRg+rXgHS
QQHdBonRti5KCoY7DHPGGb/ib+gJehTyavAPDdgiMexpC1gRUATUZTveA5gUc2yf
sgYxFYwBKl0lgSxGLiNK5mwV
=QPPU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a9dd7ffd-2a03-44f2-ac09-f31b28cff346',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf+OPlWCHtsFZTHIdO5RlS786txOHekitzIKSSPLBmHdjIN
H4ZBIPyCy5lOix0ZT2I4kA01wlvK+WJ1w8dLdZxOcE0R3g/ELT3TL5eh2dyiLm01
pIQchHXkOzwPBG+5HnUwEUyC/D286tWq3lsHQsij9YlxFi5K/r8JrHADvBVUDyu/
ZTD/BSMh2Yyf84O65ENjwYh205nyV0Pqxi6dIh9L6qQBAPxb6Fd78ShFAE7IygMG
QMcHujvM+n4Z+RLKIBhBI34XWcgC6OUeTWgoz+7Le02yS8aq+dVtrWfPme1hJlV/
DJlJXPkc1Oq0JXQBJEf4oDntPZWQV3rac4J0fplMANJDAcG+n1Wh/8WA6pw1sdfC
nk+sxvQQKZ9cq2Tk62abdLC983ZTXBSRPyVR5TkKpsd9WNpbVuVw8H3oakzPk6+J
qEYIrQ==
=rphI
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aa3e9a82-4495-4e4e-a2c5-25a136ca1660',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8DZoHLsxZWq6Zfujp/cvJBKArN8PmdyGHs3DS/JZ5NUdV
xkQBiyR1hMEINL3hfDJ5lOkfvheVS0Hdr8BKvsBsUmm1wKKmI/I63tUfcCOlgrdk
ksz6uSt1GkOCTvgeFQZWmnhw4IoivRk34dPsg3CLOh4plht8QhYLP3AWFtHD36jp
O4pN7fp2zzse5kdZ06SRVS/CohpR6u4gbk857o2Os255nLz9kN4s434Ppg67pBIe
JbLb2wSU+LHh8HBZH7GzgsltPZ2QCG6jCM+gFkDzWxOQQBQC39Tt/VV+ea4UP7DG
HAHcMOZxrXAbHzUTgyYPCh80LdOYe69WRMiiUSiK6HDbodUo7trOIb6bDZqTfe5O
dwzglKYXxA9/EctW6DE2bApI7kSts/S1bP9A2fQ6L7MikFcW5CMDe3fdJj8OlvdE
ICtrB0050zeY5Z1eAt8q6juRRnVeQ+0hzt31KfHcQOLEzNj8SEecZJwvzL7h1lE3
mN6zKkfQlY4ah1+4wZAhbMknpDMziyaqfn6GSBUxxUSAeg/jVftqi6wrwmFv8DGc
I7F1z0IW4WHMQN+DPl5teaHFHrWWiuDuveP+V+DV876ZTKN0wZvLxAVIQJV+gnBa
Rtdk4cgC4OCPNok6hVd5J6AVb+RI3lSMCu6RCpk3Q9Eh/y443LxvEvn+n+XiGRrS
QwGAnPPbgiKq8NXzE9KMeOMTAai2zXSQjDHqeQOonEYvGIzdozERfrWfmCoKXPDO
t5ozputnsKScI1+sARusLmKXtpA=
=mg28
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ab7ced15-651e-4a28-a9d5-8f6adf766f3e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//XSDdu1/rzU5YZppewgb7w5aMQhnWXe1WmUCFHREQruNw
ArjGQcHY/hnV7r23XJuVk+jTh1xg3P4PVt3Q4SOHQAboUrbBgVcW4ZZjjbfhs0kK
xOKlp/qcptrRkUEI47U685HWxAS20+sMPVZglbXjKHP/i85HUg5L75FKbOBYsWaj
aY2UF75b8Zp/xyTkZLh5lgQJmKYiH8sepES7WCpUzSA/5waH6T3Zem3/POQh+7RW
v+eODCUpwAJzLhBHEGCR3HudOiyvfSaOXZWE8/yZqRNVypZpFs8YtSj4Z1BoKrld
NfzaWOU83CjWQoiNyb1gXjdzpiuOQG42ofv87vHHbRdlsbD1feNWe9qwRMJVp1f5
i6GahGCdVI9lwk+nNimBCAVNdlU79uvBBGs1Mkle07WGzQFOaISHrBtc+kqIcedh
4jLPR2X0ru79W9CozIyvJ1P8n6+PZRrpasH4vDuNU+sU+KUKeNFNxlfLsmOJFtKz
ElNm8IStUmabO1bbrQ7OkuUWYxyUfSkYsuwzeUIE3UlwOSQibzH+0c02SxzgtUGU
hBRWInqPe8Nxm2ljhQJvk0wHirO75hD9t88FI4yv+nIRHAU7XH1Yb2KQ4ixvul4Q
w9Pmr4AuM1XndOsCQhaacK/ZY1p3bYs9ml1ItJjNxIhdyGLElu2kJK6XGTksY7PS
QQHmk10EZ5ctkqJe2hWb1f6VilhM50QCzWYIw499BqWz++ieDX3jAZzq/ZJKoWep
fwxn87Z7x33ofb038dVOnAx4
=0pf4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ae363a39-f5e2-4aee-a5c3-696c281be6bd',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9GTakl9ufywv3B5s32oBsc/jCHKRnH1BuWLyDCg4iC4Zi
TXcXy6Xzh6iU6+nojY2WlCwbglnIqSZFer6EWEC7UvpF/RNGleGhdzK/CTUXIXOT
lkn8La8T/JlZxWYav2NwfhRMAenwRc5mYTdpu81KYVarIV7QTavtTgWD4aq8KEAp
EYicH19ATIfQYM0tzUpQwXAjQenhUjgzaoMikktl4YeYxrHmRtgNfkGEf6xKSZFE
sHjfNhejS+gXtpXElM/x68WX1g4Nz23FL9HjZVXpZGlyKOYZMkhYRIw9S8S8YW8l
FZXO5Er8aXMXMI+SiItZulRn+U2VvmbRoinrnUeLYMSekJ11kbyEjg3LpKjGEdHi
h6v2LHJkTsHuUYSpdJkDn9cYBXg4pSrjtIkSO/viZizEcE/SLF0TC4gY0bYoeWQz
PtEBEZcvBucMykMVRq0lATzgrzwJ6E8axl9ISx2hJwjq9mcRKtkVcAvilyfVDzIF
g8LAtdHZJkBaLNCGZL8Bz01dMp+BSEkS/Kx/XTuifELCGepl/gWDTZxjFRshAI/0
tiGGfBMRGhU6qeUimywo6qWHezH5K7gv2k6OTf34mrUTiUvZkfyrVoLk80X2Y5lF
FUY0Z0+odE1JsvEyWfivfnq2mCSXfvnm8SxZwA1eqUzhaF51gEu8Now/UwZpWp3S
PwGaoxC8QAXVkoTqrjN5bf63wouik/1uhRpnZit4KtGTe+M5EE4LjZE+lLKKWDRj
3ORE2oW1P/XsHxD4AG4Udw==
=TUYn
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af1978ae-0042-4457-a4a5-f337fa68ff58',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAozpOCq1EQNFo2nFi8Wl10kftgn6707RkMeI3cfdBTMcA
tJJ/goyi/P7KohLzt2PYnWD6gQGJZAoAKjZIC6xednFYPkNAyFNMvU3y9c7bakRo
OEsK2l72VSRruWSrt3yuUhu8TEmRuKKP8Cx4ogdk9R8A8PqQ6uo5cmTUHoQkRuxu
JEPSDq+6BbEGOBrRxMoRDwwEORsj+VD7zYPTfOb6sW8d06zKkROG7tyAFd9fGCc1
tIndst3j0PRsvi03UAPL6NSKzfTFQ5T/S+uza7L9RFpTJA1D/ICP+C0l87mQdJdh
esKudMGV0993XTa0Sxzo4roGwFFLW7u2R30y59xH8ccfI1VboP57aFxNlanZzjIB
Ld9CLalT676ln9SabA1uYwwircaTUGLqBX+X4hryWIfM1ezkKj/IVJ3WVnqwb6/E
YyBJTqvsmqQuxGJ/ugv3M1xJpGGE3zOKp2FFn2wfyivrz7zkzwzRBvUKggCPY6C/
O81ztooFBNpDs0Ch6VD4/UvG/caXcXhqXxBRDoLX7+iPeQotlyT5bDsNgajZX1Yo
kdZOqRlkst4swgbrHUSfJ2QDCE1SyQPMy7aGa6DWvtjWNO/xv6OumdtCMXOTWsyr
uBfw5ocLT8d+zGmtdEqidhCoyun/kPBQq5/4hYgB4mSomyH8xeUk0ZNPBjhUOvzS
RAE3aWBrsTS/7T05QDe7i1oBcm+b1VkfU1NwbUzbyShltAahPnptf68MGSHLCqG9
gHWkWqo9s+q4t2um5kNrpfNhNuHw
=GyaL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b107e6e8-dd7b-4751-a7b7-6678cc939542',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAuxzk48HbcuhF0sUJXEjcSUM9oUfIJsSsJwyQmCuA4AW4
l/0pg1XZubqRexyK3w3Q27Xizjp0HbZZYuDJLEMMvQmt68tmpEwY34bvccgTzoCe
sAGuhjwAGAogv8iFf8Qf+g58BWH7wb8pd3UQKxLkq5CSCy0qhLYSrydt6kP8Uv6g
mzf7ojz3EktHcKMvg6698b1+kMrqS2ybylzgzhqWeqv/rMjnO9ZWT5y+q3W9jqXy
LYqCQPzz2ZCwGmtMG0Eqs56ZFfvbkKDk3eAWfMUGaR/cFMJkuU09rA4BlR2+c1yk
s827H5T6hq+pXWLkWTgWCm2/vB685lzQHBwVwR4m+Eq5+UdQrEz9ZtTi+kjMwonY
XLNEx4oR/L80lFY149dytVCltltWueGCAP3APq2o05VGPFXzzZtKssB02QSh1dqK
XtviDkTYT1pWjGCMS7WDRhSf0KI+D5RVdgmJVHzBIKmNC/zIGKtzdHIeULYY72uX
QvcCV4O5qC5SsjPR875xNbQdELyYRX9ATM9GTiOBaP/+xWljcxbZHhZwvN0rYb3c
eQSXxHVh3IooFVY0BsvfMRhJr/E+hCBJ5jPY4uSU9iDrrt8o7eI98rxbAjRctYqe
PpGu4ZTlmq3payYwFBVU5ETp9fe5bok8DaBAOpxiwSk9AV13VA04S2EGqckJpYjS
QwEJ0D1dm9j/Hl2W01c2qiMuypd7p4VajR1/VLGMQ+CW+RdDPIpg1FuNK8Tr1yDM
DikAF/htQbS4D4Os9SeQ7K7SF50=
=rpb2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b1d35e05-36c7-410d-af49-81482a27cd24',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/cHQThzTTM9LM5yq8Z1rpcd0vlepsfXBVHxhZXQk5Kk1f
7KuFDMMiZl5ICl5HyT2fRV5YjErFPn9YpnjMYIYyx4ViisQfavEwWCicoeNXqDPk
w9OrmLmKhOFQbArn80RwsqgJ6/V/KI/StFBOjGolElSK593NuAPbZZhLQmgl7OyQ
/5jtksu38sCWt2tasyFfOJlNr0qBx1Sxe04gD4AGgnmxeL1p7HD8cxxZ+ySo77uX
Pe2enHtZOgrx+SUKvzTdQ/yh4AdbgFpasGZjvyH1tF5J30Rriq0VmK6Xa075GCTI
kJ/YzOtfZzzS1gZu9kxnkl6bFDxd8Kaw5J/37ygx3NJAAf+SX9I6jXALXmvaNinJ
QqFHCoM8CF8ciuwMXX8zfAKHY94/q3DeGEiDx4TNCLQCmJ8oNFwlLtAztuGK1fgG
2g==
=lFTp
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b31a38ba-b55f-44d7-a5c3-cd70e38ebab3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA1523hWSELY4g2O4mmZiknAb5Yj1Awc6Mq4BAYLwqtWbZ
PxR1fq3gt7670mrt5pcf/OXy9jkahbzd9MxJJopHGymid74gbYJ1UkqWQaUy1Gdl
cyIgoeI7xp9U43uMnMEgbdFSKsZSmYrX87qNPYII6aTNKA3lEbLIWEhXV46y4p0N
Rl8HOciOqgL6Y8/gBBWyLtZZTyc0hCEpWaoFYq8N+CDUQETgBflY/v+JfsSxYhzS
Y5PVqdyyRimq2SecUtMQQ1hoM7Pk/VxQU5+YJGgm5li4xSSZQda3TFDsW+4bbo0S
Tiuj3JDycn0zE1w0UUR/ZHXkc9GdmD0N5AQw3ProgYLMkDo1rB5RFlLIcef0mQbr
diVIh9HR7tckvy58yiCTbisyfH8FkTWtrmoFTNh5ikU2SztbswsNp0ePZCq4W6Gf
X8oJ3O4D+cR1bHbjCZxKfClDF0+nroOFwOBgMweqhKTyxqFGBVq4laruoXnmfoon
1yS7FsJwJTlohRh6YP5TB7QyOia9++hNH4WAIJ7TbSvJ3Xg4VsMkWFWdMZQnI24O
4O0UxRGVHdU44K1Q+jDB1rbbIOMZ5PHycNxntTzGgvdvpxNfpGoo2oio/1J5pgUH
P/v7ZIc+3Yr2LC4zWg1UBaTY4wmvysC24N0punNNaX88qM6UFqzuOQYO+QRhu9nS
QwFmfhbfeohP+IT2ONQ7uyigf94xLw+IXjwOj4zfjDTEDS8NSDKW/j7SqqVDR78P
euZRjplKayRwOqHKYxIp9gdDtt0=
=MiBJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b471ce15-1115-4746-a7b3-65cada6c869e',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf9EutSYwF/os0sxp8WSZlVojouKjweDlNuvGNBFT3LukMX
e40siMPX3MFfvGXuEi1b3MxuVcOv5Wu+4ijC36W6i7tusehYGVKTmOw9Zw92F+Ef
qnkOLVWb2ItQLIkNLWemOx0YIzOg3YoAYzVwj5h1I2e+VMNhDW59PspFgl8A1/LU
jaZoKc/0n2g5ujR3EtJdrdlrf+BIthjCxSWZnpCrFG2Mb2OhSYGc/t2a6Sc5yOCA
s3FDW3NedRhwFGycrvqyP5R0iCnOBSaF+kvvSPB67nUvAH+vEtMHcnUQzKe4roNd
Fy/Ted7DCugZgZTWVjQhXHbp1FW4LEJ1fDFfdv+zD9JSATWWRv0Cikio5bR3yLyp
ZfnliqD9ebY53qM3cewChI+KOyXFsYj8NJqaIxBZzRkZEqGGtMxyKA9GZyR9Dvf0
Fr1/Zs2m2JAp8zYrAp4vRBwZDw==
=k5O/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b4edf77d-b4d0-4223-aee9-ae2502d31335',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//QB34wfrRsiwnAiGk0yiITxmCdN1iy7MkGg/eOXWkrMK2
VbMKBdfRolxOsZurHeOQv1XM/O3Ue7A2PEnbKZd0l5sSxAIWpdBnpYfL+BeYh+Ef
SHY4nlBu/D0Xfs/53CI0tS5cx7gCQeRyg2tpeAuWyJX33QiVvuoVKcj80zqRxeTj
JIDaowkPr5Db2QDHaMPsd/dkApQGwj2TLRWpRFd1W4IelpL5HftjXMXJnzVUlCv1
ZDPugzFlRsg/CJs66osIbThFqR2n8Pf+7NB2GCs0X6sT/2QqYw8496yuzG6YgIbE
uRekFxnwtsxVupWvMV7JBGthHsS6Ml2Jm0F+kkW6mmTAQe15d/Cj3K3jsL8iBo0+
2OAJxxVo7Fsdoij0cPEqSpW6UnqrsPWk+8Ek34MeQhF3Db1QmgN9ZarEpvo2/cl3
JzTbqpAq1ee9/Veft3S8RbAaHfombMosvDRtt+iw4FRRciP1MGPo+q6frafayBmq
yi72bsNrlE0k8VIIwHn5zFWvxzozMeqkl9MzprSGyebIAfeJd9KwyBqfMgmcbciz
pyEfaaWkzSi1UwyCvnXsVQJ1o61SYuwEPF3T64NcQ+YZ/+W8vbahzceUsXWQ0yob
jMD2V8QRNSUB5TU2ZyTf5DZ2ilPmUKqkUGvSyB+0ECiKbnI4/fmYV82AGBzHR6/S
QQF/0YE2GRagdSRlwxiDJrh2sgYylb2Ud72PhAMDikDj9/9dRsxmp+j7nNkxO3Wk
mKZknr01XrNSPXwQqCTNc4yH
=Wniy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b54c8fbd-cf73-408b-a85d-478ff618d230',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/Y3MdAC/QT0DcY8Al4RMhqVWiSvEfzoLqlzxQBeGrbKil
QnSg49GehV3DXFGb5uRHFjxEJc0xsWkqcsYN0yQACpNSaRjYmhfYHE3cA7BTMw/5
fKqeFaQoDc4jGzwwnpshWKHZxxwGiaAwY/Fd3W9nDQcAOnwB36pj0bEDxJj8+Lxl
xK4v9SKm4Pj3oM8Y70iH4d221+Wyl1QbjiG6MX9UvYOcZnYBcZr+XQLbJsYMltI1
D9Gi57Hb9Jy5e6KLgERIxaCLh6isFhp7FK/3vR5vW1eNutmOjlYb8aCLhhtMWr+e
q3IdIC65IF6gOsrnpWyeGH++tlwTAL25hXks4ctHLNJAAdCqB60EiHwiKW3Y0My5
r5fHQ+hkgD+FL7NcgzodolfVqoIyMnuPh+YNs/5ogHCG0nv6QuaJ6Bum4BbOxYo8
ig==
=JJzF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b626901a-de38-4f43-a538-e3f940d92ec6',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAxNYaQVEm3U1c00TVMMJRdLqjCGTkFJ98ueRTpDSqSkvk
NrARKoefYo7nSVz7SUxE3urmQ88lfMpeusC/wqCAZcUQNM+SqqCkZk0oTmtsJIey
CXzMjXyxNLmjXpgPIEKwBFD8N66IWcXeU7IhbseYpVvhFisez9IkddOqixNaAYBp
BXsFelh3fwBDvNLROGYaXbxl6rAg9XK6gLxNozTv4/9bFvFxGVwzWeDCQgLLAN2Y
CoxTwy4ahueCdnGwyHkhICas2w9gMEbSamlGiTimNFmnbFp2dtUes0D3RJUbk517
Uga/TM/G4cIDRaHcVpDy1Jgj+pOP7EpgdZLKNq/S7IgiF4cG5CDf6yf5/74FIH0C
28ptv/AsCx4RU3EidfTGy2zAlDZKgiv29VvsUuImtPlyybR+o9Vxy8VZzvDYmV24
IFir5z+PKB47VcpVAxu2vNRR3hg5e1vgQaqpXazvJ5DIsvX5CyZTEa8mIp8yyRDp
dY//VZcLXavdoPyKJDSc6zAhp6i7BGw2t3E/PVhWMmSqHGJHbLZaO0YkKd4C476w
W/ERzKKjgcnImL1qTOb5nUulbGlznk44K7PoqXjyrrAidsVUHph0EXRqYDvavC28
oe3lh627v1PhZVFuClUi5p8IX9z33S+Q9Aaw67qf3NI9EyqdDDG7tqP3SSSGurXS
PwEM+WEzck4VnByEJY9WKTnttyQ8GWgLXlwEWUVjPzGAIanFCRxYdslure05G7IP
hqHyJKADzkpENC2V2QcVtA==
=VhI2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b8ac8398-ae36-4a50-a353-5357aae9b454',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8D3h2KeWbD93BHW2gkc4jIS49LXZ9kQVbhF8+51HKqTLN
SV/uNb4BEl/aRRtjszYb+qGlBukhYTPbZChAKOk27sxZWcTVfXQVnGp3S/+o/UPD
TXpV7wRJl5r90Kpp98Wb/MHRO7vuRMdTYo4xPj3rOEjRXeXfDWZIbD++Y5c5JIuT
5aMwhpzR000oFGHrGpRAd8CKBL63nBpQfXcxAUwhfqEX4ydFWZNFLJusf10dVtIa
alyeQ9HutRaZcp1WP5KhaInrpbOCJm8CWvFoYJBNk0GC7shK8iPCtDTShgV4KJ/f
fOlKRhJjgEJPAtZyzhtVeW05t/WDC2euGD3q6aaNbLC5qjoU/P/wXMWR8y9rhmC4
pnDt7ByR41ruChrdSHkTPCODOFxdzpwxZpCc1ZuEizZziDB/II5Iet/DYRRo8p/I
N4vIKYk33rENdCReirOiPxiNqqu7TwTzepfz/4qjKGlnMy191hZDKvtFD3VQ8jYa
RMdhSWy/pvPEDmf9i5h/2aQ9LDs9Mrk61s6Gvzy345fP7GO8sMsOmkCoHtBRaBtL
CciSsHfReaQTAUiwA28mhw0qT5E6wguU0pYBq2VX7h0QbMyZW8qPOHsgSW60N6qF
tV5/P5/V5WqMNYwGAbfaedg63ylnfICnsdkV3U5viO66qhsxWDR75sxu7eKGdYbS
QQHYtcj6J9AnltTRBsJnjS1oQR3SukuLAqaEiys8cMTXpSaljnKDJH9IJDxu+wR2
7+87DGED3JGCND76x7JEgDYa
=q1Xs
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b9f95e10-ffa4-40fa-ac6e-2d687d5c7b00',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Vky9juRlcEGNr9Rn32OD1lorsnVHfwCSsCZfh6tApvfZ
POtDDj+jQWK6UhyxcOwjCABboaxvL/sq6Jb/0zmHkahdh1VC6XG4Te8FXnN+8WzP
/K+Z6Ik2eNCBf515ZnwAaWzX0A6I2ytHWHrI2QxBxWkyawA9fcY7Mk6vtyDX9tu1
e+ccs5DDFV1aERUWOBz+q/Wa8v6VdTr0wQAkAOj2wrxeP1/uVWswEpfF/LR4UATD
uYGNduajdTwBEEQyCymlu+iB1DeoZZAk/m/zDBq11FHeHZp2lEUNaJchy9qd/D5P
M5nNjVUnkOpyH6xDtSQ96cRXFzN6OlGZbYVOGmxVgpZbF/AFqIvHIbzfU9vN1YOE
WBsRlJ2mBiVP4Me5xdFmm1at08Lncm6us3axFOZJYduEEfsiAZRlHQsL6Jufw41b
gqrYc/4pB2yaRbXj1V1X8jtANC/UPakUBj2a+R/wIzjzoDZRiImU6vhHsykm9SPA
CLuMCaL47C7iPjpWTLxONvVYVUVlFQYFI4ksDcoFCoRyL8Vhbd9WRLdBrsHiBl7s
FGTCEcmkCsniWX8ijFNghxwEuavOqqo1wXJr0EOi/HIKQBEP4dFCzRUL052RbUaA
1i2NmsQN0G5oFJX0XgaVXKuZljZagPX13xmxFpZPX18d9tT18M5FCHBbrWXedMLS
PwFp3BiOlTaepWxbGAwxPkUR3LI3M4atO4Xx3fyXgxSLXbAe5yTBq8sauBF83X+K
8XokODiXrrShYJCyAgd6WQ==
=i7B7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ba240eaa-6c8a-4af7-ab1a-cde1ba9bc8f0',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//eV5i/LuIrgAJzUFX19C3/bwyqHCPhf/pAkxFYRRMyo+5
uUAKC0zcwMNpaJa8bXmFSAeS0X9KjSuQs8Hc4YJ1IF+n8B1bNiGy9F9TDZ1OmZzP
WtGrJVR3T23Jh4N0/DvAH+jwSW4X/ZOJnj1J2XFQiepEbyLT3kXR6BkUmIEGvH53
6jEml7U2OsJoBl5OKZXh8z4JLj542ikP468pN14pPMl/yUpjFCGfeGtau7nEu99y
aYzfUm3LbB6TVdutKUaD3QNN+C2x2+hN8Cen/UGiuE7lwNDmZs8D4csgOUEkE9/E
zC6TVqUxCCMtaSZzU4ErAlL48UdnA75/id79WhZWR3rGRoa5GKNnQfnAhbeM2c7N
4C6EvEdRssrUuo5VUINM6MBazQOfd8xfBix0A6ud51P4hpKjMCPV+AwxEDwRCxu5
AIwgMYaBDnDXmwj2rtRYf4w/pBTEPqz1koVS7bXLmJz7Zlc3srrCYaDD2vW2GE52
8Vt9pWQ0S+ixSxo2n8yp0n5nNz+mLtaPlROuUyIRiJzMQ8xqkHgrLlKXYjRJQxe/
PVwoANUfrQ+CJxg8YdGzlA5+w00q/lt1P43NZrPPGpAjS1szEBa8ga7VP7NFtqa+
vu/46mtm8eOnu0CMvIYfGHUz9XztQPlIPKCA5v17ZACeKMQ4ih7HKAD0XIc+mHDS
QAE5lT4WRyv96aApHRl5MhSMhgfV/ujgoKkjakYCW//zLjTeD05mdxFaEXRkiL4k
49MnFLAfWX7tugVlpBoF3Us=
=yO0S
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bbff0df2-6479-4197-a89f-ac45fb927e22',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAjsMetX2cvSJrsZmpLK7Ny/NkAU9iOcVBT9xJVNkz/x70
O8nU/UkNq/3KxE/1j6ESuoiRdpmpXj/Dw6XtEWeSSbtGmNR5QQMmrKB4VeAcsTTK
rfGZtzE8N49t5qLej/snWWU6dkN9bmHf/fJdRVOZe4GgvYl/UJ1Wy/UsMKOVz00W
uXertEZuUC4Ct9xn4yQYndsMFBoRQgpAIPq+ra4X7fVOmvdO+c6/Jx8aYhtjYdTJ
4Sr9Y+U3CLoeJaUu0LcN0qojFkPrMbWs4vj3s161deLFfNQTymp6uIhvFposw/iU
OcoXiQinoE9sBWC7ZQWfeX31byMF2auncaUbIKvsfdJBAZML4XAOWZFQDZ3SmCGr
fy8WMsxDkxpm0XwFHLC84JB3LlNGA9HYqReiG2fxAydt8V7V+PZHh0/UGmDoP6Hh
ULU=
=2a9z
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bc54b86f-5da2-45b3-a1ca-2a5ebbebe123',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/7B9nFBAR8oj5qGNJZwHG60R32fgdKhREHg6+qYgQxT7hi
1e0n1rNJ2BKSVt/fHX7MCaVdG31U9soSBliDFV4yZ4bx9Jn+aV7AesgS154qiN6X
3zzMWG8hxcWAjawKAE2iCzT702w8BQuX2VQ9zf6jIyiPfa/si+zMwT0TErOS42Dc
T7in8HOz25l6Goe5uwp6KNUAzzkf5F4Kkq3aZnSaA0hRSHK7GbTUmtLYwnR6+at3
otFv0VmNZfR2lDN732/6+Av/SDH9+wPw9tbr1KNOv1+YywwHikW5Bz46FX7O5dLn
M+ZRwAWi79At+4rRhoCBf6wdcIHNBvZ+Jvh2LMxykzrNCWMOS5OJY7n8V7npkS89
q8wYHU+2QpskWybrKeHlCQZFT7rUCVp84GAt9g5ZF8svcXg0Pp0boiEiyP0kl5XV
GV/IAe9x1wVZt98O4XkrGDTklPic/LxxllIfKqbq8bAXRUcLGFMH1jkNApJFHHhl
J47TFlcM8lUKthSbMBCdIO5cH8xRTNAKR+IP5ewuTcwz4OSFQQm8XuoyzA5BxorS
XH3mJRFHRakeCu57Hna/v/LF2RyWSAWoW+wiJCU1/bQE9MKVq3H18YEOidXl3ezp
ZHkU49fLdzUrGwSXgFxo4OlPdAmUnuSPzL4JwxrlVPkokTPv9wPaJwPTEhluuA7S
SQFHEzVNQCGumx5WPvljHxItYD/6Lf0F/hUheripW/A7nkSOTvKxMdkB5msItJj+
a+vBH9OmOg3KLI+jy96+Asng45UXlJ8k/lM=
=MYfy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bd19095a-15f8-4a75-aa22-831eefc58bf1',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+NzprOHQou/wO67PFur7TinrlID65y3BcZEV638fiZ/Ue
wjnm3kHZwHFKfWo5/gvmDJaiRdQ1GN+sgmxuZr74WYblxl2HB2Dt98aVUfb5UQCa
b6x6FBhoO6ddsVXeDm1Fwp+lDIKBv2xCgG33djuLWRM2pPItmYYnEyUKxIHpKt1f
FznsldpyCoVlKNb1bV6zKeVh/QSdEEPMCPA2BwwEe/7NkXKTIbrgF0L+BbQ9kNhV
ubSuTYR0JeSQeqk/V+ZV5fP15A7vltT0/xOIH9etYDvfPHSS/OXdAXN4thgjeipz
SJOFRuwy88GSkaBbgjr2sOCo7IHO+gCPwo0uIRj5h7BfTZiZC8C4kEpQsJRlYDM7
fMWT6gWnOk0K5z7Ibtn4Xena6mNulma8j35+Kqdsgpg0jOQIziIVtwtRK4PA1nQj
BsNyEqmd7U1wCOxbUbn4ALNw6oYpKgjLCt954PpOpqElvnwrluiaGuJpRFoIlNbv
LVI/dDXWgPNKeSbbAYSjhzq3IJ5EdUw1DdX+o1XzvDmUJhn2aFTCOl4UGR+QBUbR
eitARQXhroLO83UEZR254qKnXwhXd1lBK3tyQ9BFU4of8yOE1XTZl2HH4v62iojn
QZAkZvJl9GsVOy788Syp5tMSH7g8VOuSAn9gHE5+vNOoa0TzRsntgVtOrE1Yce3S
QQEbHA7YlD4BUvwSlUeeaOOt+bI5XpM5yppoxOhW0VZzs1Rp0cTWBd0Y8o4Ogrrl
Utc5hal0N3dFwwd4XW96jvfS
=t9nI
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'beaeb2df-02c8-4cc1-ac76-35f76294c0e1',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+JCi1csPtw6KAwTUZNOy7001yU3Z7lHZx25U5Lg/R7L9Q
gG0OKvz2EZ3/oYkPE0OF0BFavsNIRmbbRfmaQRA8rI2G16k1SL7ldVTAOLqBGdY9
k7sExpRWgCoyFF9p5sJ1BqhnmEl5WYDyg+MmIHhSqmM+fo+5ioXlzllRS4rAqFjU
95s2lXgXP5/DLf5Lv+x7Gjs8qXtSL01P5QT/WphDoj5D3YmfUJMKUpgl6rRS7lwD
ZLIDTDa1f6/OLv3pnkJQ8kNxRdJ6Y+ZPPJupagz4Fway15RRblLwWGRRuMfSX6Wl
3ctJgp+naUDlsg+KulKGjpS4UJms0SbY1vSAjOTMWcx0sk2PzkzMAT+jXvNCIzzT
N4LGJY2Y9mbN7+rGmnbpQacVdnvicW+zBHqhpCigvvn5681e2JBhLWmhdlxQmQeD
tJCJ6SbH/O4snhA9u5X+7U2/urcoZ5l7WeQJUv+3eqy5+IssbLz0RFVdOGrDL1Ih
4AaFhDZjDggFdxoIiZqc/VjrhYBUJGocKQ9JGd97m+EFOMpOAqXxptcT+gmfpRdV
RMgZ1vGJrnK/5INoLmLCR3H8ozVppda/GUxG/JV121uQOp3dXWhGInlvFh+1MprV
Tpd1L2uGU5ekUJOZ8nskCXceoi7+xgpZicThKBRZbacAT15A1rtxM+VKB6c0OGLS
QQEpLDzfkQIFe0RLLVpIo9XfywAAFKRlJPQxE5NP+A2dd1T5sTMs8TCp/jVI8GFy
v89+3fQKpq9jxoANLck3898t
=rkJI
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c2437c03-1ebb-43e3-aedd-654a9c193bec',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//YDLTICq10OaX5Tbh4s04EEkPdmw72CXnS7B8Deu//wJX
5qz3SfL9cr+0pGzJU1MJ1V78ZpbeyCix+6gQFjxU5HypLWqztaoSgWE+BD89s0OB
NxxXq6tJvF6eM4I+vCL/osycvC9ahA0dfmgwjCZjQ83rRRj6qedDyPejgdhxC8ZR
SF1hbI4meWWo5iuXgCEAZjwf6NEa4bConCC1ymB5WTvJSf8NMzJS2yPTGOqGWZbL
KlM5p2GMywwmFPrVZoM49/w1swMe8NYry9Wmt8xRd2ONBFlwxLfgqsQsOMUVxlso
O4YbFGnI6wWiU4VEK6BNDBsOxBQqcfdY1HWunSsO374MLMvhy8NQSs5aHZW25dxp
S8czDua0kheZdXv/ihUYWAb+Sox4s43EaYpgYlBcR7tiJAD/IQ3AS8eYuPoPk5g7
AeXvV2aUsQLw5y6to+2xgim+CVkHzWes7JeUXVZvISisEiwvlWJv1xhUpnqygiM3
+yMRjrXUXEr5avYj3rPRAzJPhlshgV4QE38o/VvEaWlRb7Xma+OwLSblJtZUFgVS
o9ZRoSC2YVmso3v044CylnPaeo1em6+N/zaVujj39VoiaC00sV/iYH58/VV6I78h
3AnUZsZ+HRGNT5E2S2xsp19mixWfY8Cv4LWUO6nDg3h2B1oXHBuZdzr5EPdI5NXS
QwHTLhtGmOw5zRk2tfLDIC379Dhof5rZQTwZcUaSuFbIGiOFY+Uo8u4fT9wAeZtD
xLUgIcE+ROsMUkOEhjYfj76SQq4=
=34x9
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c398fc08-6380-4fa4-aa0f-157e70fc072c',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAug0ngmjxQccMqRHuNrq3f2hQmKx1ykE8PMSd9AvsjDjO
0VVi+c6PrsZR8olxkL9n9n3OqnHOFH42zCKUYF6MqHr8cWjskHci1QCW83eTYwQK
K92b0vhSkI5MCCEmQdVApTTiEfgta7Ulk6I4Pj0kk2xy/79dt1oXbiBPTrMWoIPN
oKdkd7czRtODV3+UAlCYN1SWsymUsLi7LVUAXwC9OTg1d7LZOy/zsJmGIE5XyJXM
xbFC27bRL2TiFFcGTJIMvJndiz6GlQDnphBKygHLOjh+G/TPAbW95jWYVI3Eh8aA
IlcNATcjsAEPuF9wBLo7KzQ6/vtzR5m9DgBlAZKjNARwdDCTGwxfNuOjdX4k39cy
1SP69/ISpCn5eOSa1F+3E2KbHOSCaKsgWIWTtvEc3YG4EZynXDIeDnao7uGIUVg8
4nop3x9w9V3CdXLslrbkqYqwzVLpPjPbOsWtEoQ3VA3/YjRnd9zO4jBCMf98JgX5
8Vm4Y96AyCoo7gGkjCHyfjp1m8soEwSrk91PbxDgWQEP/bukF1TgIDkrFfd4mHcW
78IluMrhokFlXeBUWxglNaTgXWYJ8lTx5yjpcDpE5A9cI4ms34iRSzCpExOgKLsP
oj9fvnWDQLmW1mCVxmCxa5TIunvjPdKP5xySLQ7P7sdhYYTReO33nRb/o7W8d/TS
QQGHECeQMkVOXNRMG1C9LsTXOZjP+5Yz7xiG/01XZNjUYwiWFfMwIKBGrXHxqaxn
zoAJ0L644dBT1huUigmBoLFF
=I5Ok
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c4da1303-eeb3-464b-a88b-45c923fba1a1',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAraU9W2mIsKkIa08CHwmJzM+uxrToYYHNb+uwdOjRkfKB
DuRyDLucgv/GTtK8FpNF8HnBT8W4vni6ASFQSfaeywPqMw/MjuNWpBT2vRGdddbr
rZFIKdVdRzd2iuQnA0UNjsCKMzeZj6HM/26qqYTh14LVM1OlNPshuyKShpxPRQt7
rNgIdffuwO00O/R7lT8J8TJ//DWyssRulolJsERHWr1/Ld92M4Kbsh1a9DunIRhX
mUx1riQBE9gXTKMVD8zH55+Ap49kb+tqVsMAoQd8YyvmA8WhWcFHYEdSE4QXdXgI
bXU/sShP5HTw2Qr6hEU8wi4ft6Gim8nwiPWfU1rRPfYWyIXaleaYv/XvtT8OitaZ
32R1Z1jepz/caTZxYdXWdMNmu6BtZ4dlWZoOXvU6iBk1lZiT3NvaMtZB8q9cw/5B
RM90Q6ZuPgTfEvjuM08iAzLOr1kOKVA4voEXHxv/TZaWytgMcOt0KZu1VG2AfHbu
CleAbjv2/v/hLz3BGbJOTMBtWHM/99cs+J37q0JvWs6KWHVOaug2Z1DxqgVjyYoO
9nDyOmjh4N8UM7dTKQr5UFYHE+rcknL9YX9xIcBixz2o4ZYlnHkSHQv32RzWoKWB
rcPLjutTXoh7Be9BVOctRnlmtf/M9TfFGV9y28moy2Jv/gGYNuahPLQYC1Kf/EnS
QwH+5DCmSaFRB8gbflus1u5FhTCWjTXGnGqPKN1H6Jvc+0qEfYnxPX8mBi1fjH/v
nlSznWoVY2+93ECs5xZrHChyyj4=
=kIUB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c4f1d806-63db-423f-a563-dac05c9858a7',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//e+c7HBtHoF2Q5duUhSigXVdxWNuTJexLbZ9KV/mxq8JY
F5cGRW8WBINV3Ccdyj1vIpsk2x5QDr3C591Nxc2Yg9lJ6YXu1pxZRpjQnIEdFsHF
nJ5SHoV+QrgeDUln+xn5yob6JPnyxQmxboGIZ4nCwnGGCx3Xl4doEtbsQpPTIKBz
PC9dwxVaNqxpHJqdQuZSXaUYSwUY+/jKkj/zTAVj9nwDAwC6w87H/B24VHSA2iPY
0ySAsopVIksAeyQDQj/PBhxnd70iPFEaFhAcc3v9HObwyYg6p0pXNkrElMilmoJP
Xnw3L2SgpD0z0LuR/eFz7XOXSTsmKNV2Cdajo9yH9itVrWtmyYlzfTxwbTCFM5we
/qTTY5nR+rdPgydVwGz/u2wEkzV2I1U2DsUdJGBozpTj/knhbJSWwANVL3s9H5I7
mhhntQ1fUDgtbnFj/0+UtAx5mVVv4PQCOhF2LYxPzXwK+IeRYyRU5OkZU/HzzF/+
DXrOgrHveB8uBWBza//WgDsqhmjJDw/yeq9ikHHS8KTH7SQcv0ku/xeQstvoCKi2
jWeuGI7iLLT4tsEDJKtzzXthq6OurO54O5Ah8Zq/cE/GND4L5KNwEspG95pqjn9w
8P5oEcMtxmPEgPLIFvVtMZPi3t0T0fpaNeGHTzmHQG68E3kOIEd37qKDqfBgL8zS
QwGFGOfjhO981ayLrr4l+k1bSk7BHFopFzND9CfzsC1qqP3LAY1bqL87kD/Go0hZ
3GFfm33cPGmcZ/nqmIKPB3dclcw=
=BR/e
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c50fa5aa-9d1c-4d9b-a7da-c08f39ca4f78',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/YzkG6+yjMzU9gXX5clN+GLB5r9+iKqNaT57AGK+zJSyY
QrwzCE7+s+6X3nC+5EbHcg7B5F8N0DUpXpbGpX0x98Mb0hLnUARnRuiZV+5nwekW
HkOC3Gz0UV1qW6k9aTUunbYOoYApCYuj6Od1yjknox15FEHEQTQ3cDOGhEIRnV3G
1NTDhRhqter1hCHlRiaNUpiIkCyFyqDnzOT7i0yoJre4JFwMrRCgKhbNA6750k39
EkZ1bDqWNXqYFISg+gIkl5oLH/xUPIOEXEe4lYtLt0VaEQ0RHkyXyNh+GfSEdYHD
KS4RcHQ/RGI6gwcAIAxR18FmYE88H1B0CH2/R6pINdJNAcoLqLdc/lGOH3m43BZX
g+jD0P7S4e7SLtXAXx5cfqqk+k3cGQGxL9R0YmRLGu8t+sQw8vDERyzwmhHRXnsd
qzjA/rvS7aXQRAWKYyk=
=Y4mB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c5bd9c1e-404c-4c77-a9a8-0a009fa9095a',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/8DknjN1HW1e0Wq0MFRxEd2qQasKPhf8wwEByIoxTzBkee
7nESxt2K3iypPChQnvVQrp/L3f+Ue5XPRrV8HeUx5l+UNgvzA/de0xhhe0gd8ZAt
qbvMwalmHaHKyYzHhbCDztG/c+GQJbgQj2atLYzXB3VePRCXZMlp2DP0CuxOBg/c
0QpFiKvbNG04vZkKBAajAxwnaCh0amlEn/3F5bcTCBAkx1xF3wW4M1M/muoEAFJi
54qa8ICMqALky7qMmAfOeJo+rLL98V4guzjEQgIOO5qJA64aUzuJv8KaTDXMD5mJ
wmosQyXsS/mHbxUKjsdxiLNYKrmO4yKGD9Zg07i3qiwOSDxJ8bm5MkFO/xq2N4Y6
mx0OOFHM5yyiCRKalPn34eTd34Xyr5SSrTPeVPET9kpnv6nzdOvr74q3lerKe9oV
/gwsvbbvQ/UQcPki3iNILc5m7jcaFyEPIoHrzbKDySCi+K24ewEErD0oJjbV2TTu
lRgtIRqVigQCEq/cs/matjEtF49eyI4YXjzufRLanUiBgk2j/GPSCa021HppB9mi
f7n8Zmvxp8ykbf2c9CnsDBaLjFIjDhPjRHYlSaLR2Z5cPlfgUURefMgyyflT9Cgf
k/KXtfjaxV7NOm3l/1v84nObvnagWtGupbEIHT1moYQ7Yv3zS4UUWOt2ynb2N93S
QwFtyN8+5SssNfl+4+PLaz/9TJUP203UOxhkT7xfqB9XC/eJTpzMVvQ5ffhboDAM
oIXRk9DdigKDUlFjUZ76S+ATiqw=
=3WTV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c6792598-1c72-4548-a229-aa54627d295e',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//X1By4uBP7kFCwGBpCCuBBK0vDLL8d5r6x/QhfEJZ57Dq
dmo/fNS9PDJp+C7XLBhxtMBnDFaTM+UXdvnPPUr3oonzwKi9WR7kg0M9tXgMACBK
fCgYb2gkstLnUPHmSOPgmWJ6EnZG9HN5jdfi+OvseFc2EZO8Y2l2hEn7ralcvbXx
6X+JlZsZt9WDXIcZC/A1bTZvtpiq4KcoVA7P7Kv+I7eSgOcAu8dAyC+ZS8bxIQ4q
smjb7YlmCRtl/Gqk9Kg+T6/SJ0wFiZjG8HL+9ixgyMSwO7i9NuJHkaBEm0cc9mzd
0X5eFozpmNzHFljTCEvZLUOkMXi8xpSZLqu+wa+vm2BEhSwBun0qRUHIolUENoc4
mpBYMldRu4zvGxbvWSpsAkuyWry/YUjOHFU/IDxxaL+RlksrSUXfqdh+fgl7yusq
/ccNLhLgXaJE/EcVy/azx79GtEnAkPSXWhwXfgdHKNVPeaUim/78eNnEBtEAuMHa
n/sLiI9pAl8Y6tx6Xr0l5jQCM6QrcGaqpQ+jmc4s7V3i4dHnY3ILbRkzeR+v6p69
fFS7GPj3CDQm77QHlmfhZWS+LV1+Wz7WP0AlyT2FqcVHFglL7sqR/7beZBrqHtt0
TLGxM5nlkECKI2/lkxeH3e8RbIFesMMcycJN7Q3jZQVHERvslA9NrEo8F2o7g7fS
QwEz/GuYdr//iau1IambuHCpAbDxQ9BEEmNYO1S3sMwUIv6ZjYmAebRy5tCW8fxw
GC+74igO/huAipW5tOSdE80/o+Q=
=PwDy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c6a051fb-176a-478f-a17b-b55b270005a5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+PiAHJKbLOF1CnVKh1kC3ixMM2YIMc3c0BCd8w4H70tRc
udcFQynEtNq8C0CjRY3LyYAljzwJFqGup0CvIZ6hu2oMgqxwMPy+VjKRZm7VCj+A
A21B2sPJhxnTESa51HfgUVXx2SsTRt92I89skzexN0JLXpxbL1HpgLCNZxbQbUhX
sSVCU165T7bn57x2Vg28SNXGzhglUUDRx7O3PhpLfjxxJocjx5xafYabytOFUcR7
HuZngBG+vMR77vL77hJ3z8W4uQ153pTNmYJ5v3BakXF3Yi/KqLGvnWK44M8L2xTS
eoISsEH1TrRtqMZX176mJXSW1DdGB8gpFlfUewtRk0a9qdnaVNv7xUkTmxvMMQCf
YfnqfP7nz4DJlgndERF7tMKQGkxdmrkTgQduAicqfeSbbGmIEgGHaiAbmwzHf89D
wepMDXmhDSW3fCBQUuI1atj9i5i/y7Yt0FxBVgsn5+p0tyByzAMe9uAswkYQRRXC
tLiELFqJFOK93vULosiZgTIKsJQsXc66TqjP3hDmR2cmPzW7yWIXWkk7CDTQTBRX
1h/Td9CUzNYSMQtkLRsot79VoVaSeTWPxiA6t2gRREN4wB6g9CiyCOnpbXfsJeTC
1ic1NCehGqBu0spMLhXCeCxrxH/JuI18WKM9ajuYndBA6Xn0U2JZr1YHsk8NTfTS
PgHAJWxjYwPp6b/PZz3oOFB5SWRW6fzpZ/rs12ZYRKs9C+DV2shrx+jUk9wVJD/S
hNknoItJZDr9a/mlhXk1
=HuWZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c6e75286-de09-4aa1-a3fd-dc863343885f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//RGKoo5uVBUjk9Me0O7LDjotPMAR2tnKu/WxSc5p2w9WW
sa4GcRBIeequB8nOVwxmLa/LFk+t+LIiRrDsShhhScgOO0BtvSTCmi3gJ6RdeOYT
gwswDKT1+5cqUk8W3jPcKaT69eZ7ixFQXBIOt69LXBQlvhoIrMt11jJ8TQhrVc90
Ljfoil5KJjz+T9jmKzNqHC+GyGuNaNVqoAFRd4yRaWs+XD19GZXJo+yJgJnVQcLr
3CqvOtcGWDHFctwAtoWgYi/FvnUrrGQyUxjGeLCWJeZ5ShlDpXQQIYi5xzrj7uEB
NSl9Rsc0JzxCAsXNdPJMus3L01mNs1g5s6a0ha11gz3ofY9ksmtf/3JWG/ymqLLp
h3y+nv07TLV+DY0ifvSXnmxMt2KzK6R31t/RZKEUR5lC8Y/O8ROvXg5SVqt+97M8
VpGZ/HI+xiL9ZsSBlvensHlSqYA4tYsdunsTLKLW9CzIJzcSB5KpgiLzrL/96OPu
5mrTP6r/RlGMIhyEuIme99rgI/UnqJYBpj9W4M1x6wAohWT13gLjmYT4ZaYYH++A
aSMskpKIwoneKmvnBwCoBPJaIG7aUC9TKluY+pLHlUnFWQ1UDCeXsxoUiVSg34yL
JKeLJV1OdwHm3LSd6UdHFN4zYGe38Ls1tpyRM4Z5rbhstIAKMZMPkK+EBi8sbMbS
QQEe0rfCCXb7YawK/g+QfUtvBe0S7mRtIVc1UwiP15+obTfS/SCBycipFvRluW1f
14KiZzkpysYSA8fEzyXtL33U
=s3BV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c731717d-839d-4aa8-a2b0-4f8b30f4897f',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9H5DVPqTTSEDHnxJPi0YBg6BWUNmGUHFQvoYCeqFfA0pn
d+wxNzih1iZtqQz3R6cZ7o8lLI8Y0HLhkkSzxEr8CihACpAJFJDJHmqGHm+gKXyz
e/HwXfRZVKXzzThVdDxys2KD3WZCB8HN5/9eznVEm+wOEa7Yvb9k+BJm+26Georo
fZt+a15+THHgHv8BlH/RfkxJsFmwKqvw7Lbf1iSKrpw7fyl6F6G6HkHWzwNlsLiP
QoGgwaICs8a7yUWFNs3W3GnOKl2TlfJKnhMBnthxGxxvX/cPZFYmHXqNz1W7hZ7M
N6XXTnkwzBHyv/Ij6SvhG+XbPoz5yFGKYLfGG3f2VERK8u/IGxb1hagU/Tzim9cm
33h0KPoY6LOzJ0X4Bb4gCwCDhPjMbEtYrk146hMC19DO3Bfnv6Iuvut+V8/3GoAQ
diLfv0WqRDDt/u77mo8QgrnNKg6qsVLPns99JbuoBMGZFZT2YgRGLjPsYdZTnA9m
cdqEW/lq9lOie5Nen6SkyTNSu/9juBB58gI5Bxl14Jg43C7mpACsR/7nF6Temxib
iroS+s0NiGVRke4yRr6I9+H6+34lXRY4qCGLOG5QwMgU9tOyPMoYxED8FjnE90Ve
nd6N86qA4LFqhBukF7IHWPE61xRkLJtIMKwcO4sT9aBG+5b/jFNpvPBNAZd+yoTS
QgGBkoJP5oBEMPjPDqcYgGiZf2ntWey4DzsXjltbX/2DJ5kFOVpuRJ9+JMPUqD1m
EVDvNTuInx0q0OrTEXSpWOOY8A==
=ciu6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c85afb28-7d9b-48ca-aa49-1f56ccd824f4',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//YCycA+m/6eTPROWyRZECkd3ENVYJV2Kx+PeSKKtp/UPV
SvzXpTD6mMl0CPD1NFGj+Z9kyRLD7t/F0j7hwBA88wqAcJgKsOSOlPjzz96/6aVp
4RUPqAdnCz8f4G9Ncxw3TRQakyFQ1MXJSP5WZLwWesiXNixyK9LWhCNGldycuajs
Xy8Eb405Fnbpc3y93jSWFLnDvIHMvu40RDEt58Wubb5JiVvxRzHwYEzPRe62YiKk
Lmxx1H1tQxj4vORtYNpufgOTZVQs8W82FjO3/2JIjGigw8tTeeoVKhIrMb4AoV7v
o8/VDjcdyTIZg18ixxeP5BdnE23/14DtUbBR6H0iLr4JG5uFcWPFjZRR0Bm2pfaU
Qbiwa1EsmmW4UwFdwFCzWsAiLzUJ9eX3I9kmN+ivbMr8ONPh4p2r0nBVahSAOt0G
QZ3d40EZaMWfUuudwuH9dZrGmsLvHEUWXl3allEc/0my+u+UAh6grTwqVeqEPvh9
lGQ0PZxk0kGMEXnT5wENad3ycQ7VkNIALxi9r8qSGLkJTci/6mQETGNxifc0eOXV
euu66aZR483oUvXVIlTtSr/w3E6aH1D2dPO+c30wIeQhcH5F6CoJtq4kfGQZVszk
FVtgF33fjltQOnEBJNqQUzdn2FeFMNVEPIKFbS/FJ73Ps5gJiH1zn2g2BxTgErXS
QwFczmOVE5CnYKGT7GFZ+4kHQCnFbJ5SqfIhbW5tj6eyLZb1QI25xHUJZ/lyj/Cn
DGQXZfhDrKfJfTqXfCbEp7ffmQI=
=sZhz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c8665231-21a7-4df7-a054-b5273035fba1',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9E96MmYAscfKaHqDk4NIzxVO/xh6Py40iXAz6xjbdRmlU
YZKrjysZSY+jGC+rWZrt+b/XTuS74rSga85fpR8/JznFaki06U3fc5VW2TfdfMUZ
h5zanFaLR//BQgxo/6CTaG/TK0HHcz1Ic/O42ARVBCXT/yXhNmQauabg6h08BRQ4
hPFn0uis0gkxKH1lU3bidDplbUVN+I9cwZkx5OrObb00/ItffdRC/fkGbR9j2lEN
gWMG0ZNCHo+7/QIWV4iu1fKFbYpGa7qLMaQ2QMgrddZWJT259ixR7WOveWI4IQ3V
BJQX6EQaG+LYR1njmgfTALXs8fzh578wY9z43wUipme9LNzd00pCSHsnMld+OFNh
3c1klcAhWTKCn/Tft5SzrZPfnidv4qYjdG9FdAce7znX2YZNDmvt2PYaRc4Vkk/g
d641g5FaeJZ1W7EHgKeA3Hpd1Ly0suO/PyAyg4GMGNjqiPSFa29Qw40qjRya1q6m
wSIZITSU3FCRI0ajjc75eMJCszoJNnflelozR/GAdeKBPXKJAl7ICSCaPrxhi4CZ
dgyImsuaObncsH63olSl4eDhROSee1nLIyCFitlaJtYoqonOfpdD7Q735s8fId0q
By58TDGxKxWSwx7oiPc9Ud89sGBNWYRilaACa/SYGyoqXICbjE7BkI/YUFLjQEbS
RAHqfiTHdD+NRW+Uip/dLbMB9QTLI4aYH1mA1VQKwFWdHDgRhnp9LfVvG36q4XND
HgCdoXTT4OmqR+B39wE6E2Q/dvcZ
=F8p6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c8b7d380-f884-451b-aaed-ed70b6813b6b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAgkTMq1UdbA2N0bdODJxt/yN3ZgP+2k+Z9/eLxPppulNm
izcIcDfb/OM6zo5r+fdR86C26cZamekgw6LV8MFt+QuWeopKpAhXmka5aGc01vkN
jTpkzhVBCuJ1R3WBX/32vz+SuZFd4+fgWoKwd0fp/QVnGbhDkr4YNtN8fCcRAP1V
GlfTxdOjF+G63ZdHp0d/HBav0nJVOO+oyFNwy/tYAyCF7eA7o5LRdb38uNca5GnN
xusN34BcYZZyWhLYAZBBYhTK8pC5PCdH0Gqovskk6ZP6H05zCyxJ2Th60DQ/LEXN
IxvYVhml5gZ9xBVtK0SIBpugDWL0EeMX7isE8vZunGArtUprW3/wqHwONqO6Zi+G
2paC6Kp8qd5vhb5qIcv+orqW51I1Cu8MS8NFwNUCEJLNfDAiIpiTV75Igsv7QOmK
xg1/OZVgN6O3fkcMZF374Kn+INOmufuu+5XMjxq6r22cvBgqlFPMMQXoqJ55oHAg
RMJ9CanUeXogf7Oo2AH1WYQj5BZbyG0iazDojyt2kT5X3swp/gSCN0zXb5oWi3WI
vfh/7aUyHTtGR9gHBd0ZCBXQsfwLvP/LjGqz5Ea8TjsmtTZWPvFvZgq5HiSrsSfM
cj0QGoGA36Vx3TZzQJZDIvmaM3CIvZuQSx8qNZwtPrpYMa7MOep5XR1zt5suPB/S
QQGE6UfJQQFTZXoyPc5yRaPLrBe8foxNaoFlGkn+hFHVi5PX9tETYVqUgd3KMUYC
DnMo3MPx2w9Z4HEyknYa+Tui
=DsAK
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ca38f482-69ab-44d0-a102-1c2c39c36ea4',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//VQs1mjT9Bugw+QKbWHhiGh/DbAMubhos3G1wZGBp3CWy
viiKM0FGiXeyFkqQjDgc2TZ7j70M5iVRKx6iYNxfSycJyIPLExBWbWAmpymxsCrs
2I1W0uIy6MNp04nQoX6fXVuVH/FqVPyigRMf9lRSR7/MmGaoCbLf9ZPAqfKc+Y/z
+2ICTfHbxqjron1q1X42YjAdis9io+OKa2XwhC3GvVTHIxJN+n3BKQg5gCTzUSy7
k28GJkCV2ycwsRuymYZrt6ivSWsUCFWM14etTyUYKh9EVe3hK3v0AZKHJWWxvYtH
5v9tTL1zTOw/DGnFNFKXJvbBuERPhpFB3Go/qhKPj5Kk51d6viGtLFuCdjgsDP05
EP2nG7Vw2WFNKCUeWSlB/EaMYpz08W8+dALh4vHhxrPL9ZaChQX8OwwKZU3m0HeS
XQcxYiS6E2VfgPpV5dAZF0rXSKw2cXtZqWPrLFCc9iBXCbd6HAl+EEl+SSp46mCA
XAtDTL5pKg2SIqSHZVCuzPJ8QAbbcAAYG0xn5xGaAMsL/g8SY/5wcdln2POGxam2
fNATbxNRhSaOBIXEfrLOJlPWO949u4j4P1vwZ3Bwm0LAIykCkxlF2snQF7kA/V04
SYwjkgs/Qaq/gYKms+TbgQXyPD3579hcjDxTNqTTTqnA+kym8NESyxflCIKOSlPS
QQFuw5nszjrCqYdBVYdsREwcxyquMufw6AfOiFzWmzi2baUm5uP88gWOjanVCvJA
Qoxz3OoxlmolzaByk42nle4e
=dtNO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cafbcb60-4e9b-4d11-adee-98a6907b2084',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+NlubzcNgnd4Zu5bRHV6/UnoRJ7o9+Bq4RZBjsIsNfYMy
f98WIJeXowg3K/RoyuUqtW3qeGMmO9o0E1LqLDeWYB0LpzpwFv/MECkam8m//bHC
RaZw1yq3OT2cE5hg8bRf4Y+YsMXdJbRH+I0gVJeXpIQtSjaCP3MyWex6zltVVif2
riS2XxjPTZnIIu1ij1yAfLu/ftTYiWOHlLl8sowH3GG9WDI+jRd7Qn++0h0vJsbh
vp2bhCju0B7Vm6qFDtep+A5jifyK2oy4v6y3HvyzNFAihN2m23N5HrkbDF8xA+NK
hKDhme56NNudKBMpiAY7mC2sQPtuAE+N4rRlipKxQhF1YO/uOwGMKWK67Y/Uq6Ht
FFxm7lWr/CkeUHoYrtMi6G3OzeDR8X9aXpyLDlr4xSXqbHzqKhsuT92M7yfcqItm
WPjKIidnXvRl5/y6nkxmpXhecABRRVGGjJ8D27C3snGMNCZ26yxJubwvewfo5rBm
fYPKKqZOanjtn7OFrqFmI0VF9HyhxSDUzeHQEgzgM0QsfiCTIPlzQ4MgxOdEXKIc
6ZeqZCfuIeiv5tsUg2VvsjtHvMWMsowOXTn+Hfem8V7G6LryS2BgIyLVIomJi9Q6
BgNuPnsK7kP8c6O6T1Vxga8VQNvCEhzWlL7ilrL2eDC14se2f8NELRkWyalvjuzS
TQFFEMHidN8B7y4YCiTWRvS1gxsOipoqlfu/DLJsBNclRCtgeUWHMf2u64uCdNPB
7zGDIfMmydo5ldkeshMXAfiZD/DxeOtSwucm5wBK
=q/l3
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb9d50c1-82ae-4462-a327-b7d4d9f435aa',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAsOOuJxO3CrEA/MAQB9p0NlWURZVhFJXugkSqpKmyqXae
/Zai1DFJWJt/kEc7WP5VL3H7Dn+835gztqMWBC6T5tgti/mWlxuL+EyeY/i+FK4w
U/J38v1aJU/bUNV+DVOeBECSS5gziWLIVh01DFp1V8m9bGQr5hNYD5KhkxMUL4UR
wydilLQ77+WERChilaec8zawNyzQ9C727pN2qjGxnknpJHNB247MTYFoXfODITOx
k3lebJCMLGuJtmvMbYXU6VN/c5sshXOMoaCd7x4yhYIdHtgYXvixZjVWHF1HFjb/
XqHuEwSimzUrI6i+ru7bfOTrI86ALHG7scNSBWL8EQkowmonwNYPWMK0hvrDzZdg
36IhHZygbo7mGO5uH4D5PSqONpXnyHixQ1uTt5dtaeJUv3kteO9Wbr8zmGX5o3AM
x15R2CFHg+pJlBibI1JeuOxvhzv27CbLTQqE76kzdPzJ4nLAqmzs25ZzV3yaFgMq
404Jf7qIEGV1ovW6OURmSx39c+BITVp2iPr7mNxTTgW8dyRS3TFIAwM407KJPsxy
AWUYIFK0fTJD6e5cvV2jzL9LaJQOi9yS4OWLjnimsVQnaHeGgFz1SdrQoJbCHR+n
4qUpaWIhD9uyIa7lG/lh8180FRhdrQGF8MCKmRYMKHOnVrbvASvvzy7lgHERozPS
QQHaCcMVVWxg8GAgu7XLFL2xu+lCyTbuSFyGGvJ6xlrSQz7r2a6tPQ2skQ3r9TaY
HvuphQsARJ7YKXiUo9xyK4tH
=my4x
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cd5c44ee-12a4-48fd-a03e-b54caf95d0c7',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/fzhya0A6WASd4Fboc98G54nGFpY4JggW9aRySkV7xsdk
8RIfegSSh5zJDcLkRciW46q+knnYdAjLauIkUhzoCB3zKm70/TnEIJL6UkBea95x
vyi3pzJ0CDEP0DAMRTxw9dl2AN6znO/Vn8e1que3lC+v9DpsHaUSMgXEQA68+Ovf
uRhkldrgRyBqHzMiC1dXEEV6/ICMqRBBNCsdKV6g3Q1UpGCdz0PTmVTsDQ6NJH+4
ozncyEFCgNuyjHYDhT9tgM6eJFuWCYsthCF3SDwSKSnReZEG0JrygGqnGvfatqjk
KSiXKfiLZfNAk8bJB1QK3KXOnr61QVc7gr0Tzr08tNJDAVu90N6V+vZHtjWuK21x
0tTvByt/aeI/zTh7rGNjfE07Ulk1dbIBaByd+BZrRCdq6rYvQbu3HFEGivTlTByq
4NjtvA==
=fD33
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cd6e0430-dfdc-46bc-ac4d-e0ca78e5b927',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgA0l4vuJB04BnrMy5Gz8guCutepqh1t4OqM4I12CSNg8F6
m361QJY4QvT0XpKpBBp7qHxgx4IGszZmktv4tH1Cb/1S21Wg9IMEMww3py2g/Jvu
SMgpfuWhrl/kgt1Hpfj6mkRwsnFenxj/ZFlSPUr7Zeefwxxa5gUkFhKae1bx9xh9
0F6PrvkTNnGuqEDAofFUNxFaZjIm28YTXxYxBx9fcb9t6wPpQ6MzMP48oXx+pYOJ
KoqQbq90vkql+tSXFkN5vQ0xEpSydtfxOtIk+LB6OM/E4+mk8QCgmO7Vvl6SMIlK
ms1APjKg/9UCZe/roy4KTB9ptC4yVauok8kNTzaSIdJAAdCck06FzvtTRbq8x23f
khITzWlBK3927ua1tzaFdiMsPxJnDnOXhIhPvPAFSuxXz/y54tGC8hK4LzZs3YVD
KQ==
=YGdR
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf7d042d-c8d8-4644-a9e7-4a7b470203ca',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//RGueSp1jMBOsCh21bLsx42+k6vLqc6rEdRW2MbI3dTSz
6ofEiw5DAepi1wFnXVSMejYsiqufBqYZxs9/x0+Iri3L6khdazWe7EMCtyGdBvwo
yv3EnTemyBm42j0VK28euZdC7zzW5KH+0x2ohVmDWFGFHhW0mEqnmvm6AbgfxV5Q
7gxKLEXVz4V1hrAOE0F36kXt6sPNUY5f+ERvEMD5XoE86JzF/ZQeYCyfiuyD/Nvq
k3LCulQvKlGycxMFQH0w09nHJnnz9968T0iB5fe59ht+x/X7fHYrqOThtsBO+cSK
6SlHgb6067R2QAPXtQEn0qOy7hI7Z6ewBHSvTf7qamc2qM0LcKrDUDvIO1rAoTIO
IUxd7H+NYX8hmDYLYactsO/ymdAYSV2djiIUA6f+tfoOySzlHBhuE6KkJzQCiE31
b6izgOoWOZUCEQJsBcGkkE3QsgTrwJuikQCvThQqc9CnDF5RuuT7PCIiZ+dw3xy8
wJNjFz68B9ekpGO5R0tBvqFMGHQLPlLkkZHk++fwuVYdWAxox2ZJ/zBoSAwYxWw6
GzK2GFobsVm4qvKrZII2RL9blviuQlzR8OsCNQvH7sqfBS9FEfx4p8s2bhlUJ+Cy
XbdPkvlfJdEvPhcpkhX94j2OXyCW2Rxe+teiYmR382jFIk2zHjmv1i5WAcQLBXfS
QwGU7KyzKHwT6sf/UslrloQkWp1N2ZvlvjXvhoxMe232J8a4fppxtqDzhhjYOiRN
p2JqXruBoekGlyjIjgNhpVXpzo4=
=RYg9
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd0056cc8-e9d0-458d-adbc-849f8d7b4d83',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+IvJ0PnTIArkt+ZQjcJ9ykzMbJAoAL0ix5mP8GXI4vzJ0
p3WpZncI+CgjlxTVZnvV/jX9/bRH8mfDLshuhMhcrR7q8vkGFe9+hC4dmIolQ5re
qXQ2mFnN8i2NV62YQtb2ZJEr5rdjtjj2sgigxLwAPlbrXPrbNbVLP+R6lzWAgUIL
Pz9IPrNo8t8mfQkayqPsoXB23lT1lMlJhYPwhEXtBOwWwt1581Yzz9GBFezlP70T
toggjThMM/72Lk97HvVdIlhGlmr3UVRlgiWXk9aF5Yk31Lkfp3bsKfxXwN7z5Zfq
iDwLlOiUG4MX2t6mgTJ9YRO91mdnoWXNcsBtvvMK2Ij3bcrcMSV7JYj/Gn8j7p+Z
rcuLNw+a+Z6RBXOgQmennINNva5Xno+2+ViYKTSkwPmp0OvqfrfueHqroYlAx/wL
sbNmEVAOh+Cfez8P3GY8mlMH9u3K6w8i++OmwCsRR3ews6/o2uWlbXIxrIv3m3h0
Muw+I3laFRi01hOK8Mc9/b8jJHwTA3tjOwOTDFyRzqF+b7is7ji4Wlx9z6YY2R9X
Ox1HsJ6N8wa5o6Z6AxloT7t/lb07z+yThOMC/+6iakZ+xBUSNvRjKxP3vvcGcCeA
uJv2FxoM7gnwHp7UJ4REnBKMlFEDmfgXQlBuKVmVbaZKmE+8YZztg/26QAAyMQ3S
QAH1e1D1D6tcopjZF2pB3tMdtnjnXNnkQap8rlaOtlDw+Bp+7gsXUbZXpMsR06oa
iUGtysTpuBw7CX7EzDQIaGk=
=GKiK
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd00ab0f5-94e7-4687-a8c0-ba9ef127231a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9E1kul/tRi8MWeJ67/KWPgTRPYlfhdfuZKm13iAkwaHYY
jxjTH7loemw6xLE/6+j6a2gGm37w8WjMWIzr+YeTAE/twSWE+68wdN3UXfnTLT7r
pvyt50e59KaEcjCkcGkWPuxS8AbTTl3uJH8FJozOO8zgTw1zSMNH79mGcUwzLPRW
f73c1qV5owLKwxzxve+5HGx0w5/DbZ8DmBjkSyIMzRtVbUr7XzX0umLPZctoCMVB
CoxI/LG2EnpBpGlZ4UK4VGUBIPrdepFFV52dn3puFr9BAZk/jINsPh5jHmNBK5ej
2dDnbm42M8OpsPfqmLjCghbJ9NAUnpAP+2p559oBEg6da/+laOLduT78gY37ofxj
LiG1mV3dVWSoPIgaaVeImF1LVuwuDOGQOG1JSu5tGwbRh302SbXoRsH343khbxFJ
4RywJSxfd1unstFJ1Wy1BlfTNvoUjZRqM/vjywetUtXFWLUpG3fjT//qiEbdXK4I
ArC0wr5edpT0eY2FWvdUonLD5khuvAP6FkD7TF7As4Q4lJupXqt2+cw1NLgYvrCj
/piwWGIggkwKrUjykNTEfa2/bN1hJDD0GjmRb/M+eexU1fxqC6aoKUEmRl7DYWuT
MAn31KJJo9LNsTjEfZaayxxPG4B8EtEXCIl1VyibWxn5zO6ZplPofPeE+HJ6qn/S
SQH9DQenVgbusKkkUrA5YimPfOEruoJwWrBM5O0pKOrtxCQBFS81OOzivE6XSofV
KJmjAIXh/RXEbxVEJclPEhcMxH+m3MrT82E=
=Skf/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd0ad86cf-bf18-4cc3-a4d0-08463054f3be',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAga1xeeynD0+mdwNR8q3h87qCfcSHjcmE58TGWTj/Te7/
dPKTbQ4F4sZD/VZeUAPJUryG0CzX9N9ZwCOuFCZ8cHauw2Hv8nbA7YpL1lTU9iSr
tPUbPI4zVaY18KCpPTI28FqXicdzgoR9pfey+rAE0SMUYWviPmidu42s+0zgrpS4
8uw+cs/KcHXQcF+QmJTnaeoOUy+bWZT4a6WD/FCekc+ilN7zXA2aNH+VXIxR9cui
yN7KlYXGFPGkY0BQiQer+H8J0u9Q76g/jjrjSkGw+VKcXtLsUnJe5/PNvAhR9gnw
gd9sQmQwmytkQoUQeUDTWKndvzEWMi8kgzGqeLBSoHuXAE8O9+FTqY1RjnQIOzAk
42tNLNBnSodEJ0wpYTTDom0nyIAfonvxunXpJ56S6iNtqfBsjf/FSFieGB63dOPG
irMaT/VW3tXLa3Y6HmtQwfuYsmF5DJbJyQ+najnT1APrePTJ99OioLUb+eekGNBm
XdbPapCloDe46FnT3nkB+X2jJe/+xzuGT3anIoqC9sNwZk34+Qnrr24JZ9YjqDT7
if5JhrTtO04u7v2Hg46sCzprT4W83Kp+0KFsNDdIFlBIgamngoKkjsizFFyA0h78
yBhuBnMO1o2D+qw/5qAff9neDc4Mt+r586wEWudrfEzj7g72QLRQ0g2AwMG7DqTS
QAHYcclkn8v8xsEuncA2z27ugZef0XSyhR2xH8yiZlesFyzjT1qANumBr0mNc/uq
uhpkvm3WWrrGLlYSO+QdYls=
=xKB4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd22e7e2f-45aa-449d-a3c1-d82c5eedde12',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//cp12vssvO8TrgLKcWbciVj3AMNcxQetfePNTBJbYfZJi
okOJd6fBWcqlKOLNBaB672WzpQxZ9PkwJ30+6x/JpOYcfscdvp7JpC2NJ6jDtR7w
5CRWCUHsDj+ufux1hcm09gehruDk32K7pL9jTvdyVGhrXiTkpS5QuZvw2wVL3TLR
CN2npoijsUCT3NzbXs7Ajvu9HWVd2cyTTQumfmUnrwixlYdu1ig/wET8Lyf/lFGu
CewQkZcL3qmws+wQ/vP4gTniqvLl4TwfNRbvTTI5JiLNDnji0sdHsWtLVCbBor0M
pdl3kP6pfQwjmwL/SZSpQOi0eD1966Uq9RdxqJpCm1f4SqpLhARcHTpp/QxGg9mT
2mcP178G+PfgHqiYorw8TG5ETRId1FsKwfXxytD91ij0MuaEx9vsdkcgpg2p/IRf
6vK5TCK3lBapP0eiFE1W7J3TZYJ1pycxHOCmtjZujMYG90aDrh7UO6/Pxag4quTo
3tEQ+u1gliXLvs+kUXWKNBWmgL30aXVy068MN9YM7MkFA01WEBJi3jAbcMij2ACW
yzOn6i2YB3hwR3uLO3ZExCzYRK/Ajp9xuL8TwmBlfxvKbpMVMaLA0aIffcMvZFyd
Tdubb/JKcpN0mwOmZlLHZR/f/QXEElIbNK8iklOtEorRFLQCS1WCpxr1IL7qB2XS
QAEBvnqj6vnG0j7grf2OYmQ05s+kLKBU8L227K4bVesjmPeGu/e6cOqF0NQt0kNl
Jd09M7dz8f33hHCWWOhN5wY=
=ieFp
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd277cb27-9b58-4a1f-abb0-e1b150af915d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+I86nfW9LJXftrNRMGhOILQLOQRFgBIsW6u5mYG3cTZxJ
s4fo4ZkAC3XLIg7CWWd+LHN9O12pLwXwuhHcjiNTrbeYHNI8oRsfxazE7v2XgJYs
/VR/UXSkkMFjKdSo9osw2h7fulYwxOORddgCO+0JLymtmQrsiysdIahPQOXviux4
PRVjPNj7l9Z6QHmjJriDsAwDwAr+j/vrKIcWmYwjIpU2BmxzM16ym/v6a93ilcH4
ExrxYJwYmYyzkhhqX/Jz0kdpjEkwUVMK+kSAxpwH4MvI9VsQweAoZuQeGokFgwZ3
meW8brpHxf0av+PuLgqX0FPsSSfhnO2Odkv4nPYtoUWrP/qEBJ9viIUgRODZGhWt
ik2Eop/GJpKkWuT6cGTdcjtSkPOkxv7z56mJ8KvDYXux+ftdE8JaD6ZseCbvYF3M
HKUUn7Kh2rFNBlWtdR4sKfZr+M2ZrYmVSLrGq7L0nwu7RkDNJVrh84z0APU3Bln/
7gangRTRyFB/iD3aEC2AEJ/9awrgb5FRlR+h1vygAAhchfqEB6UuQE8E5oLrZA27
tcFfXHF9kGr7ba9/OnBa9Jke3DFiNp7FjMuOrYmUQq4GxtfoB0IId8BAC+WumQ+Q
mU45tqPuAgLa4MpBxuKmAtRPshBq8pSxSc7ZNPzuTlDV/F8YI/mWqmeduype/UrS
QwHdG9b5UR6PCmZvP6mziBlFsR7JES0StQSE5XoyQ2RA0XoHAGdtE0getPoif7a3
BK+TNv7c3MfVK4xzOTOYk+rc0og=
=FiFz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd288ddc8-57b9-4399-aebe-e9aa126a9ac4',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8DO845TN+y9rZuM/0Y9tjXmQjJaIl9vvzcOIbt3/FR7PQ
p0Nac6iZdvVnJs80d6U0C5mgpuBew4YY/eI3Ib0k8sCed/JsWJYu6Xasi6WZD17j
rxBfzGN+35muf7z9JW5eOo0Jd4Svap3is6gpAJXtmnCiavnCPxLZHTZL1AlNEQSY
SgkvNf3pgnS3ukNkHImdzU//ZQ+fXpHaX5husKliY9NCPkDw7FNcb5/1c6urusHD
oOD/YrQWIgoc1vV+l3Akwp0X3HB+JMBoolgcCyzex7kJXtcWo/pcC7ooeCvo+meQ
i7rljhlNK3EWGbCdIJsXuys4KXZyA1BCoZiCoGGUME4moNT0BFbYtpYXKkk8dqMO
kgKaz9gjEgbjaWZl2VfUKNvK7J5Btnh/tzk8rx78gkON+Step+SMOl5kzz6iBFCP
EEG0u6vYfRBkIsqJYg1Oy8S0Vo8k4WZxkP4ZJbqtejRGpilh9+HlZb3GKJWILr0q
9XpyQ6jTuaeKWH/jUOmQmDYx68n7RxlAd7cq9AnUQDy4tCfhtN6N22HqeNeXdSnr
BuAPoMm0DUQ2LdnODTcGj3MV6oFuLjxzOgeR9WFl8s3BSEcljhJ09mLFrLPqdPex
vA8MRc4yREtETpb1HnAf08yeBLhjd3APLUAtXGmOUJJmJrpssZoQSEpfDrW1SxPS
QwHI5WT6rh3fpEnnL2bNWUCwzi5Fao6hJaWNa1gbLs5orBqmjpu8H8BoV5uFNMGo
lQmkpK5mmGPMlAfg9KgbIh/vkPU=
=lZK1
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd3960722-f7f3-4ca0-afb7-dd25e08821d6',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//TFxzC9JYx7cSSR2Reqagypes24+3EUl3ld4CFsFkX2un
Ld5ptfVUS4Xu7n2WcceQcM88S4zGZ2zbhR/dgJvFLArEmyDDVw736f98OomWN+bl
6hGSCjMA29VEM/zAbgKe5ujNpvfo1B4GLFI9WTriO8i8tBOy/frOQ46sB9h9gMi6
GSLADvhdHg+GF0Ct65TLwgIZr+kus3UUs3+AO6jTuwOdHWF8/yiZaMhFjJd/eN/p
NvRpoAmmqfI20ZR6vSU/q/rukSRL7lJlyHcwHP+7hHkyB9VfTRcp2MBOsslXi83/
XEc3JxL2U1Xdb6i+FWplhimwiX2hvuMniek3JwH98sJgEXaURTFCvy7idO884B+8
WjDVXcT06+WvS5TBnTHR1gvOhjRrkd2X/j/ZgIKmm4OCEDCxsz2gPByyBpdADN0q
IEeJhYRTxEls3cd0IIlG+Va/ObvuNFDeXMtwuvMkf5deYY2w+m3CK74Jj2wJeSUf
WPetGFk1up/oM09WHaYLuTrvz5UNjEKeztiWAmjcfMj3EUqhDLGXi0rFEp524JgG
Qn6nwMx19DXsj7m47sl4AciuedU4/3O3jSnfCxOmN/TRqbTKuKCBn9V1/ylXIaE3
Z2zFDXexZh+vGImlnmf42VTz6SWj9U24INsLx0VrhdNbfrAW/B0gTw76hLQPNlLS
RQE749nTnSZzB90Ruehrjr2qWWbRqmypOYahEEIBbMXaCT3hjpiX5ruS2Tbf/2FV
omwuMT7X526hRilgdTvFusn4OJmMHQ==
=00vf
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd5ec3d9a-6b26-4e63-ab7f-5a3c11c2bade',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/+M3GyD83fgBb64tgHgd5vNSFK0D5nywr6umggtKgnodYU
mARnsPwktY9D8a22wF9qdFzS8zSiatJ8EUZNv9HULKwc648ld0k4KrVBYnnnUUn4
lmbewWR/t2x4iamB0UkBwMHL0rZemF83/2Romj0tj6FCTrFymlTp8WJN2/DD8cD5
8tPPMS8TmIV0VObSN6mT9U4GJ92BIKwvZcPyCk343HFDLhiDmEYwFWS7RBnNKoxH
C/ZUmOxz6DNtZF9ktp3VFb6JHk69ZgKKJvY0PLwzaC2Xhd48MQpjgSMvbbS2iqZf
oOALLPbxnowOg7L+AcrbC7Go0Ix5Oig2kI2uqfV2rgfgK2ijYXsD9sBDbjR/C8CW
quVOuatD5K0DQCBZGDlbtc4qJHilNtHliPUY5BtesU2E/qVr6fths0bQgjvAij+i
BHR219oitCaK2yqkBcQyVnlVHidn515JdXKzBAmH8cdp8jJaNx7gl3o1Vi/+9G7c
dH4LdHL9ulaW0huy75hIlBk93hZZV5YE96C7yUi4gN9OY4ipoQBHA9aClTWjZVSU
uSltVNcKjhz1Bijr9hAq13XaR+D44LmyTT0bjwpNikf3q75ShvPTwRO03XlTPNzS
BKZhqJ3tu6OQB17QnmfO2kGZcqmTCsyyZ22cEWR/FoYuNiQ6av5xhlsgMkwLy1bS
QwEoopScIVL4uTLpZPMbOuQhxU1Vw8h58m0uBqjnHxU7meBTpRyGA0w6yB4Ojwlc
swgRHq3QMkHEgkcxyliXeBJUKfw=
=oqfH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd6a3c931-fd1f-4965-a64d-64c1f50e6d3d',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//ZGySxd801NrEoqXpJzs9ayVGwwT4hmUtmIpOKSaNpvjo
YCjBp+T2qDM5Azk7nyuj0cza+gMJHV2oEdJnjtXE4VZ4B3BNadwiq/Y9v516Kivg
0vbrezs7vZcn+K8VEfajd4rMuDPfT2tneistqWu/NdSLdgN6f0XSFJHe3nppsnRU
nG9dgKXtg7pk2QZHGY8YHduhse6dGMT9cl73FwQeOKjp2eO3IOZU2ep4iih9HmF2
rc3RgY7z3hIaIkW4L5HJppQehudU4caZwLU5PeNP0wJwrcZh/2iLD9kFk+s+PHdM
To12lE7w3NWJjFwmaWi/y+57YKo+b+dCt7wp8D3y074eFlADo5qcoONZ8cz1BBCf
nI9EqCIH6AnkXmU0zhpaqiF0PgiHcrDeGL2hjOlVoQEUXZizYUL+gjgM8YvkWv2m
g82ct8ihpqEJP/uK1bc12ZN2yd84kiby7d0mrgia0BoaHnQTdeL704a8UK7YfqnV
8krb6Xc9sMp1w2XMIx/fe9UsM92vctCVkXQU0RaJ4K3PuoJNfIm7egEkzAfxpB4I
ptmjDxKOZLjTea73QaKZBXETbrAigTruEx/JvBlZyMHr37nbDa68/d4ptd/KzgCd
1MMJO8+r+tC+Q5XEnoRKx47S/CtzrhETSGi0gkFnoGDn+VSmAj6lRK+QAfaozV7S
QgFpNK1eEDXePKnljBdt0IOK/TvRHZy6Wd0Uq+c7NWSK8wBDoG2TjBhSSp/aBXXs
9a3cZNoojlKmVf5HkZQUeF6pdQ==
=PaFG
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd8b5a091-f34d-4098-a44b-4e807d745190',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAyepg6gXqE9yCwUVzKp7mk6OxRmMuvqDyKDO5kiwxBUAg
kq64BQxLE9Dd0eoun74gDZyxSrPUVR7LFahbwEcwrscYGv9Wwh+L9mKEaCmep7Rl
vzZsYR9oDgZrKW6z3Ed7HM7kZvAN09DQaQLH6LsXffKABYf3/Bo68IkvCr0lvrNJ
CAhsUKSVmHdvUR37TUu6K0p/mVM1esxydzbbTNqyOdcYczitjM7KngyrrwvsuZf4
qV8IZ+4oawz7ZZIhqjh0dkJnwkaRglLIKwlUROXLhpakvr02Tcqie2+Z0AMsBa0x
jFnk0adQzIlYKJqADwJBwxsdC190/nzPhadrrVFqGzSSvu/dP3J7/+U+z/X8dA7c
ugWvSBYI3v0sRZ3/fyX2lIUgcw5rVt5+zuUxP7TTTTyTb73JCR+rG2VA094QBwxD
wUWXrwjy7ogxWmr8wn2prjv9i+lZh5FcaBh9/MVaMN/AWwS88CVKKM7lchfhIyq1
a33fiNS/gDe5R1E2lc6Yru4W+Cnzv4oMs6G1yr9RuaaG5gl2ESySvijxL2w3gyoA
HWu0fmtEYmjV01Ks2Fqa5WtDMHFizwCQ45k5VeYhausjNRv8v41NoNxLc/dNcAwK
FEsIxJ18JaF6m3qOdBbKWmunxi1sITYWL8zNQFIx56pOrd8Xx/BGH6TC+Iw67n3S
SQHAodo9PmG+GnCcaDJ+VSr4Gx6A8Pd6BBpYSKuwhd9/r4O/l10zDFBUcWOiDwls
uuKkjjVGwwG5L8l85OFROk5eHJQBtuopTF0=
=k2vs
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'da15e552-a405-47fc-ae68-bc9ee9ae5713',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAg9ZKGdq5saWsk/p+I2KEZpT8X5oGZ+6fn8+gy0hCE8vH
u8+J8scDNYh3FXEheoKdDqTUjjLf2Y6HuRglsROjrKLQegOD092Wh+bANC2Au114
ICkOgYRDJDqLo5+EgiGh+K3mLob6+X8MDmmOi6Vk+OjE9aXAm0BdCeYybJqdzo1q
0D/Clnr/tVZR+aF0sO295tpCLuwwtuGhFSvauuq8a8GAj+vdTxt/P3Pk16RtDFw5
maiVcXe6pI2IsihFDIqrX9vDCrN58RjAyXfYoMTksdmWtOYabDRKT0xiaiiFDr+Q
wJaJPrua5oNE3lNqlFt5WLarilPdtAg58R7ARYP50+6Kw9b78n5giQdiuzRNQx7G
KkuWDrjvunEXaCw1ssFeWd9OuTdyXzRjM6YvTStTLDF7IaTftP/0jtfC5+Rx1KfC
nGbttDQ8A8/fMr1Oe9R843THVRuU16uaLhYdFJxcAoR2RW6Bx3O3+ZgLQdOqsaqJ
sBN2AtV9MZx4aLIq4aGQlGbTlFuTMckNGNbHHLM28bIRzq7/6Vl3W5s3Nb1RFK59
L27TaAy7MWaX5yxI6vhJzhQRX5RjRzJm55+83LCxDJ/L4bka/HKH+4QFqnAMvrC/
p+ipqnHhaYdMFZuj0sqlkFOXBLqsg4ye1Zj9TJ8hdPFtERKVTSJx+JQMe9odVFnS
QwGEdmlbktJMIHXZWW75zLZOUEX2sP57Xtrd+ybPT9H5bFbELpTVGu2UCtGUkX2J
VD7PaQIQzSMvL5vePK/aLCWfI+4=
=8ttj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dc603c63-17ae-40a5-a1cc-9cc91ab055fb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAqCvsIslQM7edLXHekgDwonOuoS/zBbDMyrhuu8u5KM6z
bNi5YgNl2ozav5x0w5IOJZpFItvPqUOZTkkCAMYw9F0KrtuTuXViIhCIT86U5EQu
tgNbPxdunB9HCzRT7e9h6zXziHec4Zh7zfHXawMYLzUJZt/MSaNfQaj+3akBgGkk
wIXNg4lYnZz15CcjTym6ZdfNAcWbBEZ2WEQQcp+6NS9cTdAXiijU/20EAMMrLFZ/
ODYXqS1jCKpcYvwZ1ZS4LwP17enhU62HfLMaa8vLegmHLu+vV5IO0QS9YnPGccXs
E+v8c/0EH16i1XqAFzdrtl9GydbqcR7PBbA5S2Gx9MyGetTjya2n0BLNaFtUU00g
WDRFgYDo7myGgQYtQeVStm+Ldtxr4s7zRlpzMioAKNJQVcloCH/PGMg81cVArl/0
iHUVcLJofvLFUN2/vixcJMlZGf0LgzNkFZa4oMn0fP8+jhZBPoYorCP5fuCRfIOf
EtlTynOZpJl5heAoCJRcMBgSSsBp51OzRki3USVZDl78/RCcyHP9F/0d8+upYXqZ
mGg23ESZdRPRTfqQ8YDG+QCpun3Sjhfr+YsHUxHHUt+iDWWElQFzfxRxJaayAbEK
Um3tDEpZtTeNccNcaO/cPpE9GTR/47FpXfHKzoHJpXcw/LGxNEzNWF0ari3El1XS
QgFHEOS01wMeaKKSeXzmrjg2d29ucSGU7Z4CaPIPyluCgLo91klYJS8cXysE3x4k
PN6VSyMWsDofbO++BYcfpWcfxw==
=A+gc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dd328a13-5a16-4874-abd3-a82488f32e37',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf8DPE1eocM1wq8twV1t+2ci01A8JrrG1JYBOEn2Ptsg2Pu
gs65TUmheinkP5WfSXx3HYgBItKsIeT3jZsRkRffNwqYPYTMxWJu2weQlMyK/DuQ
tQuovao46cCgAQzLlnJLdUUc7MBCZqEOuTUu7gD1bVG+d5e1AQ57BWjR+f0lLThG
6ymzXSZSpp4PlxA0f/+I18faixw6BbCkSLXdtx8+cubE6WAGUpFyZ4Zz4vfOIqGd
YUcHsRjzOGMO7j9NQe7Glw7n3PL4HNQMKd/TDB9jyN8hm1gciaDyTAFJfxM6m6Y0
5anqz7GaY6ru51EtPi70a6QwvzTZyheng4eO0ygoOdJCAXQYPon/vfJm+Dhb4UC/
gTRCeksdCgm1D3xIzykFLFZ7r1dKu2d6IKXZt1pPYhQbEWz9JGtbMOKloLP7PF3v
estv
=qXfJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'de268471-c674-4f43-ab9f-628771733f1f',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//aKzbUtM7IxHB4oKGUEcSYdCYEafe3VVIT1io6ND0qWHd
j2Yb8Qz8uuJRYFinqxsTPH8wupaHChM+1I9ABFtMh9LNJWLbh/l70IxkA7t9Wg1g
Xebb/6uIy+J0aWwnHOJZ3IMxWoYmu7CmQ0JROjTM1bDTDkyiILK2eiOc/ynrtN/u
UT4OMl0w2hnBaGL2B/cFZYe+Mh7YcH/AK7h2rmUf6AZZjdz91xweTQihUQNIVk2O
INTyE0AZHrOKTyJ2bu3ZSIJ3JeA2bEWITIpqhVZlPbivmxTWKs775Bc1nSXGezKj
800Hl2fRaiHB/fCbpY50/fp2/7whkfuZWkv6spPHqzErCBU5SOEU8peZVzOym7Af
oVHiTeE8bR74Q2CYL+0b3ifdW/nNxNJvyMChkNKX8DlAH1EKHZobLiKWKR90JT0T
HLuUyQXjmX6oORA+S9JifLhm3KN3y9LDuftj2iUyJJjqVqb8Osa5ilQqUbJWvnJV
9JWilG/Nj2dVcqkhWwIHggR2zjoRt4IvL1vzGD6kCNLbkfN2VTwgQg/tNGnCa0O2
1LphAsWo7RtXL4Iw17nmfl+ebgjzVKRrwVKLDE61wIEQiOidPzlR+SO4a2R54/OW
AcbnaD0CAbpTzsEpLwYRTtgDcavdt3cJVEST+rAJjXpfE0wJC+aK33n/H+Hkf2DS
UgGGQMA9lDbFxJ+X0k1S3wygq43zAto58fsaevtsVKcAK/Veae7AiV3X1ViB4Bdu
72WhDNBMflBHBoix06XbSeU/SUO6O9S4YgQzCi0/8IoK3Wo=
=86Kj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e0c09c6d-3e1e-452d-ae27-73fd5c80ee52',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//T3L9e/zV18qmNdCXO08MUoI5AtpjSFXewpQI/aoFfz2K
m5bm3b+/c7J1WLSy1esK25+O0/lwvUdR0DwaxK9J8zVzb5LvmSE4CASLxZW5N21H
N7yy0op/sgTFzzsoX3Y2/4sPr0wq2UnlwnKfululBHCQQaFgkc6O4CQOpFx/D+wK
jjQvZzxstwxrPOkDiELkhSzdxnng7VcFHMClCocmQ0X/bH32YLSb2VjX68SblZ/G
hAw/LQTXgC3gRYN62mLXjPhFrWG69IbItc142yVg+/mN54ls+2BsY6xBelFNapq8
Jxg6sVET705zOJT1I+T4hXjY46F5JYj3l8MKhiFMtyTxGrI6b4Cm9HP2zve7nR1e
Hmf99L+EanoOoooXrX0g9INqOFGQfHHvxTDOoBgk3piA2VXeHE7oDX3WSv4SAlFa
um/+h+imr35/9iw/RASfbbseCw5CNGAm7g1Ww30JGOExM0o9PjMMbLEb2gceH8Qy
k3ZL5dh1czpiLTBClV+B1B0pIHcE/+wYUrMQ7ALDE3jEJqL0tQTuhvzzDxKX4AFR
9ueXJ7WqaG2piVAynDpfj52evYV+4wQItDuZ+1Oihv7dwXbg/Iol0om8cea/LjyF
BbsTZGH4mr6rwrFmvHHxt5IPPUN5HHTu6UTZCHYQxSldrAKvO6k0qFDEN64vRZ/S
QAHLtjNhU9MS1WYCUpvkMYWwN5/u6CeHjiMr15HLelmK210R6AzcfW1LxLUbAN8M
4xsgt5TTcaBDseeswQV39aQ=
=4F7V
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e4f22f3d-e217-4987-a2e5-e88ba3dc6be6',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/dX3bRqKgNOfv7ZM5U1YFtcsYbK98k0ELtyqpD7mCX9Pn
zITCP8ljW9pNkBkNw/UVhdn3UmgA/s97zqOfAZuge0/im/m4dsKBXaFKUVr4SbxA
Mh05dwFQFuGL6dIxE1XN9k4P177sf81lZmDGrPup3x+Qzfael9+2+DOyPMhGXRBp
/lzBSfbZ1YzVCJlETwtUPEAL1AD9YdjXCbZ7/qcUa/pc77nZGExbZptqpryVIuKk
kNnFY2sfneBazbYx/lHBgpLlVg6D6Z+E/ME9Gry48E+63qlGWOLZfrp+LhNZSaW/
zjd5eQ44Xv0WfeXHKgpYIHtBk+Vsbzc+VybPE7JLS9JDAc55+TzOT30uqcj1HYsR
7QYvDMaL0P/5PX1WH5wd2jQIjadNc3+FVG/2FDOKTc3F6RlpsoU5p8jkOdin95zl
K8Lp1A==
=ca+r
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e854af8e-4793-430e-af2b-b260abf2ef72',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+Kg9i/RBLJc1e34cWfahFas58ubjukGNYLN8gOLbyNSJV
I9g8b9cwPliQtW6yJO/mgaAvH0QdBwiDt4aBlQJgqQPOov2eCZ13ObPZjFlw7H59
RkWMmV34BaNH0rPPiD6Ao9lSeXzdwzl76tgD2eNGDzZ27XJG9/PY1Y06AKGXGKHp
uryu+f7uD4uNH3BJxvfu4ISIctpUg6QAFSzG4ctaPLy4vYpi85e340Ip2AlbUofc
VRrKLTMLOTCz1vl/uh2RpyxNHgyB1GuXeZTugiEb7jq0mVXffXGRPDJguC6HAaJ5
5DjNkrL9vabgdBCiaZRP0f1X6Oj+VHdadV+2qPky1xGAEtMrxHc+L8mO+ieLPHh4
CYy8YmjYtd+FU25/wsInXRUm/9CtaHDxRIlGfYIVpyPrP5Bk111PHRxTJxTyTPky
Pmc94INFGKrvyDVD5ikfoGDnJOHCzcwQ4G/TddnRO2C+Mhg7Ki9A9LT2sLCwn3fq
O+MUnf6kAgR16Lu1E9LmAtUOI3r4me5tk5DW4lDcUvu9sR5BsllFRlbZgiqsTG/u
rYUkpfIeEugKs1ZTZjrqjmCu0JsM9IMGPjyVyaUJHU8uKe8+3IHmhW2AmBvkyMF2
GOv+RErRZnWAcADIvEr0SJYQjpavfoPICVvK4B8HoUTHB88lyfX0O9m38FhsXtXS
PgEnHwYvjNp5ZW3jh8UIWddmR1TfvJ4+O3eft4l0Eg+0R3WUKifUisXTnPCbIej7
s8FjGpqu4VxQKSKZOiB5
=kE3n
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e9101cbc-ca35-47dd-adfb-32b4cc3f474a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+KFGj1daFcd0c4UolCVoknn1gFz2bhEo7IaAA8tGk1k1s
xFI76Fz+JB1RNco2xji1ZJTHqO1e2MayOvAj8vcTBAzCSkreDFk3up1A5jISpk+K
9fml49b7DtrdtvBTrLUir1Qw8dFGvZ5UGqQp2xMXKzswMkl2NRJRg8G0YaNBEBCb
RrHAw7jsh6RgsZfd+rMhuFoToZghLrlW4YOYGweVaIHpbmew4ltsBvOj1NuguAX4
hSzBVSNvJF+8j6pWJzOOipJHm2VKuBj7LGEjBkC7JvCsmEDZ2zm6+y+SjfwjzJcq
qUM8FgmUpeRnaOA9+SX5p5DGRn6s5/Boqe/7f1yY/b4pWxUZ6pYii9HliG23fIZK
oZHaaz0R+cV5slI8/ResYru6N7/1a7TaUz0rAzEYN6DpIvnwn0ltiy0B3cG2Qjag
8E3KOkVDff6g7qxdQFz1xpgndQ9EWoloVV32/Slzb/exb4NoAO6jtm+J3vw9S2Be
gDlX5j9O4IEyusCGMfHjjDSq1hmYkwOxjlETvlNFsI9iJGEIRpfO9hqRMLmLPtZG
JE8/c7mvZEpt4krmosOIKFJlc6p064+bMU6BhFDn0tuFj4yxOcqbI+IWdvAX0lPq
Fg1TdkXqMqJkChusSRXhYa+CcAZ8Ua5pEXE8J0lLmyos+Ezvv1VSmJ1uDPumIQ7S
RAGAGzLlhJ9BqKRit67sqe6q59UdGH99g6LIZxbTneeurnGWkkRGbMp1hdyMt6XR
3kCo5p1fkJmlcDfPaRi7ea1nHOJ9
=dWTV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e91eae6a-2591-411b-a52e-a65887d80ebe',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAvR7KRcxpj7qqObreJl92WsmsCRvc0RPAK+IUmXwGg+D/
jNbIgg7mzQ/+7dnENEiaHJRQ7Gpd8GisOuhethGpTLogtlcLeUiluC5CyW70zvQg
Mj4hixR9Z2+zCjf9IZj6I2KGMQVEaua/AKx9SafmYddNEhS0QuPnDxDzRsYGelbh
KcI9ASe143ZXiL5pER0bcMPnsC2e64UndGAo+oIRdlwIBKrd/aNc7bi4qypzQOAg
mjNoKDGF9gniv7lZ2thQsOp5gsisx6aVhXwszhZ/xDrdDmedBpvMCTNsQ55v+oE4
6RzOlfxY+dujVfS/STIlBsDXXp1X8gj2qKlOkGQhMcLFljL2YLRCNCMkW+T/vXJR
vGl9sFjAWqExyDrpTMzbEkvbtnXyw6OnORxK2zLFegemEMhkERx3evIoq3AYt+KE
9iY6sKveDfuw7kfIidX0fOcNcY3c629hwjv1/S+vU1obA/imo2m4E20a/puCfMCN
9mkx4htmvaVgrO5NAnfoQ1eTN7xVKh4AnVC9pbJb4mHWSTdtYCe0+I82TV6v48W2
YHQa3T0/oqlQ2eNadNDGmHWEA6JlSpRTK1lgf/sipyIxGDNIrISlz899N4DRlITR
/RxAMsit5b/BovFchQW7/RZZ8jnacCVWkD0Mu2+eTXOf8u3UM7LDC85W32/fgHHS
QwHpcgTtoEa9PFRZB4dP0Mc41QJCKN2sLV3N/B8f8lnTXPd2UML6+hZwTyOu1qeF
WoHciWrAZIhCbPhRb8wlbFjFLGc=
=7T0s
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eaef838f-56a3-4531-aec6-1830070ad8e8',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAArdCjAO90seduEha65lymznT8bJYPNi8ncgl7c4VqxFzW
45tExAqMXZd0i2s5oYjTFOT53GcQj8RjWU/eocCxbZtDRjcN9C1kKXvqyB0+QV5N
xXaQnfzcE5AJ76eiQZrhOBKshhqk+zwFDzd7tdTzZsN3CZeKLFBXWhuyfw1aApXa
gGI6Bv82aVXnCCVZlvVKeaTVRVqmdc0zU8KLpNs/OqiZ54g7w03d35p4abzRHW7N
9Q3k+iPt+B1vvLdkF2RrSV72+pKQssxeJIP+/KAWLZvZASLYuZGEDojsoa5j9ssg
UBqXhQeEx0nMyHftcH15jp8uYHpLrNSjyWcCYt2yCQzLWsUzp58IKaVqDqf6DUjV
UMDKbXKShSuYgmuzi10AFiLiOQthS+seSKXhme8T/tIhKEYpIAruK8+1bOhZmH/+
uGLnvnt/T3YSTiKqCXS5d0DBalh5oXrTYv4qTdRxBYzOi32MWEE6FwU0cEXLxJMU
G7bccXnMoHz86gTqW0+29v9HiuBF6tRKpL5dEBwJ3ThueitBIt9Mo7VRoYmZKhdH
nYO47zN2C0VQHPHJ7f1jIfTOu3N2sB+ipo9pI/KaO7YsYfyOmvIAsPCbOrq3eYI1
kGIupFXqDVitH/muqYwADpSxWuZ073F7dWEhAAFbJNbMo+Qme++M6qWfYed8qSjS
PwHRyFGpofM27mfTGozaIPI90BUdMM3s5zBM8a9V/nwDJTmJYWlZrkZlFS7MG1yd
kLUrSN7E89nCal473p/g5w==
=b+4s
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ebe59f08-7c24-4686-a3e7-7f97af45a044',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAibrBcsRvVJYmdiJy1tW8tijN53a7eUB4lTg5J7WGlS3E
QgV3X6QvZ6Sm0I4D4J+BHbn0i0dJ3fVM988PNsyw5TH/5dCzZQWPeRw4pvnQlUm2
Z3LNf1Zaul4pfgBIeB3RLk9zcSgdZ4chlRU53cb4L7vuJzRXNRIgu7+MSXx+aj9X
otMFqSy3QNKPWoXvlDxIrcTJjLa1gbH7bErsc421EnDZAHKAWRS2HfPVCZZwA28F
yHlwhWza6L0DUiKIEkP4yH9l2PJcSDgcPXk1a58nlOB78aSD3KIuOx5IfiKrxv79
F0GUYMmzoEkgYdSebaIK55PRjs9JbuQ+C+bDf+IPxJcWFKiRvf3hxqGC8axNXZSM
ePEpy5x5wdUtGjSRSf7WN01yrSkt1NH6O1rbmjgEgiR5/njq444WIw2dnHCHaq1V
NrO0Nqdx5kmr0X6AHfXiFnrbQF6eZZSYCWsZYB6v6PJuniBdfdS81vkhJmgkDBmy
HDOYJIySp9pcqaALvQo5nNmR48cCkM9KoQ7ccm+kaiL9Uo2QX93Dyfb0WCTYXBq4
7KZdWkQ9D4fTpRCqHoQuOqKsdMyC8xR/xJHWIntZH/Dzty/ur4kCPg833h1qj/Dz
iI40coYkfPsExpTWpwW6HZS+m0eiFUu1HmkMciNm4q3Bb1USHROG+h9ziT0DEUvS
QAEuYBYQFu7b/Bcw53yZyWO9HiY7zrvYHFOyETY20jh6qQoqsxhnc96w4NwjBGkA
GjpNzn9yh2TqaCvK9RWaGsQ=
=2NKk
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ee5c88f1-1434-4156-a1c9-6afa0a5fbe9e',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/WMpeRqbt9MbOgxf4UCljyvwC7D5TNxvMukizwn0lXzQh
yTgNCUu2BIs9LoALdVh8AFfPptMQET7IA0ra+WBR+IsbqJ8+F9XCAMWpevDzrX9u
64wIUyoWDzOOyvb8AWifItgNG0fF/TO04EFA+24E4un0WHxOkLM9yamTGsejpQeg
etC07/+dwUrwYDnHwzoxLHQBnpvvfSDWM+gLDbRtNax1jIjrNwcCtNbO8YmD7nax
9q3hg6VOBdI4R4TQJlj/HI3x5HHDIT0ETx3yMR+BMhaO9DyDnY6Q7esm0u+iwiw6
VRyAH8ay9enJ0KY6jEN9i+HAs8kiWUz82MoAESdNHNJJAaXfW8vWnJ8YTbtqJpzl
3fK1d6VUx7dWX7EgdQUUPMD+LegjnF6WS0dVAs2z4Ui93cba2SmrhKL5u2ZQXb0P
95bbBpqBR81p3A==
=axM6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eeb213c2-b909-481e-a8f3-42190f7bc183',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/6A5a9kOZNWu6yEsiwkc2nh/grGWzHluDdQ+5lO1JNxVdQ
UayQoXHPXwKVV5lrU5s6ZJf9dLrR1JsVr3st5jXvCNF9cMPjUtYXT1x4/FJyD0oP
3Z+t2chePyKRARDWsSBt9w/i84f76cHsyMnvElO44nprxS/8nbsmlY/LZoC8iS1h
jjV++esORcR02glrtY+rWAyrsFcZ7ruQaKSe+CnsOktK978b6WV4OyZpU5FpXhan
JHu8itEemSyH8khtVAnWj7Qmz4PYWOyjmL1WydCq3i2Fu61HYVqShwT1tsIpvHpz
9e2kqtZPC6SKlN0Q5lzTf5ZdDcHQ4Qs3j4eM2FDvDzPJI+1rCENPgRxd/wN4G0Vm
qCXRv/PoiWrCcaWzCtdbu8lS6mllPnLXDnrCa7K81WCvSC/V8FlsOmPXaspyiPE5
9Mh9VqsoY9GYPUrqK37ovE8ftKLLjvhDbkL4MIaER+zRAs7PrizaCdZVFMsvPOZp
8VxXFIjZjSm9UpTneTkb+kY0IrBEpWpbTW1d98XiZVuIZ0tpbAjKrOarkMoBqatB
GsyTXvO6O/WwzLs3TVxPmkwH/U2aB+HJBtGOERSFSTQqmZ9YlefLtB3BkES7x2OR
IUBL4+obAn8KZUHECcOVd6EEGTj9OBHglDs9JH00YjP6Hb6GWyijFTtrwdzrnTrS
UgGXFUHWTGbCtAxsHhl0+pvMts+yytv/gKF5ncRqYjGp+979gwMuP23FzZD8gKr/
6qV+du6L8u+AlRZv6PpLV2Kdi74FLeU+AHSB6UH8DMwIqEg=
=B9fg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ef002176-f133-4fa6-a5d9-de58c0757f83',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAApX1gQU+yeJcVZ8TKGZ9N6/V+1kTnWmfB1MQoCjo39V8g
NyQLD3wnMEX+Q2/vJ3TTMwBBRicGm6JVfRQL4pRs9kFeAE4ldEubM/J2vePgdcoy
aRQ7OdBNXQ40BJuEjInoZu9Sum2YyyMqSmzUAXk5xmTrUaDi+PgLfOIf8+idd31g
+GRKEObWUuOiqrQu6Z5peewDJ3h5Esa71E1QOucfTxG3AQSwbI/FXbTaKwD3aUDC
KGAqn8ep5MY+hsdUEI0wxGn0KRvzqKhwSwuXv6vfOaCBeP8lgU3FU37fqXNqZjgc
b3km1UBLSUCAf9KOfiAunH6QRtgsA6Js+XC11PyOeUhvI3JJLBA5tvCGP6Lznckb
ZRVCcYYa+c+RsuBmgctJIZYZgZhdrhBfH7CivLj77XXJhtAXZrMZBIJ9yMunGjqw
TJY68jR88sOf1RIyFM8YDZBQC2ZacbMOpQJUkwjOCTukPaCaRs/hpbK6TcEC4YSQ
cZni2VN5TMd7ccW1G+Bwb/b6Ft0zwJw28PJEaWn8Z/0yp46exvbvbD+ycJqFpgWf
1wCNKllI0hlUL4gEGZ8OuVMzS3r3VlV1qiczflAI3TYONZILJwKU+HmHlD5FW/DE
E37aAdnAQYjB+R1Z1YtG/LQ0f05Ki/ORvPnILJXZCGy+mf8oCn3etqkwjJ01bczS
QAH3WUD9MNLtuoZZkXYDFAyk6Er58B2x4eZQdiCezinJLW1UwRo4HOHdeDJqP71E
MH4ShuPZpIiSRktwd5q+owo=
=ykqZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'efdf377f-a75a-46b7-a5b5-44d43eac4809',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAoep1HHusNkOhoICdArZVL1CdMtUjLnDnVGDS46NDEH2V
DGkA5ejM02MHSEcLR2cgblvH1K5Ox2aFJhlncFyTo0LWLzcSxvDTATsjQ648AqFt
3GtI95yjp7xVJS6kg0if4FbG3MGSdnu9YYyv9h8ll7KiiKz5yp3e1VaUjBmKuS53
f3BcziMg+x0us8A4wdcJPS+j6uElnfnLcZEbdBYZ8bTU48E7kN8n16uQohGXxKVC
5Q0Vipt6gB8ETNxB1S0FGmofai90mijmPKOJZrpele5Cy2nTGcM4vzQjxHtzgyr2
JPlNysTKK76ZuZlqa+VpvDS1aFq1vD/X28Qa454/3J0cB9tA/1nwD82iWXaKPhWg
uTAjklq2JSEgpb34uAn4bwTmiNNIdE8j/DNXXYYDiWvymF/nAULgUEwUwCxIRZP9
VCHqw8iwTvxNACrk4u8sedeuYjHXxIiPEaPav3CFSDU9lND/BdEiNtuxbNpDgDph
qQWu79Wa4o0qRHhGDud8pWvcMonq6sdOaAdLqTWilqpvxhcYe7S8HCkCA/AU/Rnk
i0Q8mdfLjdtRUoDYDSr58y0m7HBxO+i9S0JtE2ayf99ImJHRoyQBWqCe641qx2Tf
qZlCikYx60Jl749mxaKB6YhbwZMd1bVVk3W0Qzd81a+R0C8Gja0SxwWZ/LmrK1XS
PwGqijRNb94IjBpfYWWN/rcA+oIAFGxFPuPA8F1H2DQNaYuyynR7qFY4pKOZ/TrP
ftjVEm6xySIXY5Mx5Zth/g==
=klu2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f02c9de1-bae3-4f6c-ac31-4b03d83ca564',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAsydAkkb4VGSC7eN3AqL2Na3rj9pQIPk3zuCRK8APgZb8
f8PAAzDdOBkYGet9y/23GM4A3vlTawnMNOoFFRnE5xQx1JI+6LBGO0koAJNMNa2e
MDeNzmcfnkyLNulhZJs1ELvVJ3xpSiboOGyiwE9tMAV+lfUf9IE7frLZF+UWh7af
i3oxyQg4JRrlWjPgphAdYlislDGg4Rnz4la1VbBETgprXEMPtR3Hr2ZEN9zeoLWy
l80mh8HzlhRHX2cDSUI8lrnoAXPPeyLDGwq2KINjVeASnz+LewWuwyzmy9BtL8lv
n4K1N/yZPr+BrFsUBDLFcdIWsQUg/u8+/fcFNiQ3xAKmX2aNty9yQXXVlLcMCw/o
3rPnMGgr25jCNd+O5iYhbIEBWL9wlHMjSxqZHd0a7hkFKKRaDv7nOW6nSyDWiH8a
upvD/SYQFrHRkltHETpKFZiQtRyykpFuWZ1SPgYRDVgzU6izvjn6Xp9DovOCNGEQ
AsIh6RJXXG/YbDuCPzQVMGX3sDjzF9qQT15ldFre0wOyovvj7TXbKgNbOcKTEutP
RGpjPzam6yZiFLgL25OMh+b18yAJdi5Ntx0yu63aimWoLTRbVpAaNDmkbOehyQA8
sesTgdNeILxTui4HIsNWoh4qNeL6hHk8xNNtkTilH9CeNp/vqv5DL+73MDCVPYnS
QQHbQq7zG584fNKZfzD4WHFpl/427ABKT3/1Kg9Gu84Eh4G2lm+DiY8nIs7mISr/
hK08/6CC3zoi5ayK9TIkuEyl
=AF9M
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f0e421b4-dc93-4924-aa13-eccb95e640bc',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/+Ly5INtB8nx5P3W58yPAlekSxFtIMOW4zxrkKJHbgAdmw
bFXOTSVJ3rOwNYJEm3Q9g2Sq6LGrEToZJOBAToDkx2h6bh9euA7eYT4mQXp93F68
UnVwxEzwO08LHVikp49JyLtMvTRfpIL/TjAgiA4JYi9gX/8c3pz3jIeMOQgklgRL
YYIy0DxiqgTuC0o2Wk87rSCIOpT+CnscjREtUTlsaEUte0oIJyPhmg1UGWeHXTVx
F/zdbOElj+tkQbweeYrT17kaWwXCtFWvGsVOQ8M3Cq/cKQyu6WD72dAfztgupuCh
ueKISPUJ0oy30JcDhLm5PMVpOqbq/7gRqSeIKHC2QkuHGi0my6YddIYnpVLgkz8W
mF45U7EPGFpU/uTA3pdIS1hT/8DdXDYySW3+IDuqQ9tUZ7uBFb8fj0bwB55WOoR9
MFEkUK9l/6L1a73e5NY1+MH6bgABDsaS1+SgMgOIEgLfiAs+jML05/BdlHD4i0Wg
7BNn4Tiuk9h/Ssemoz85BpFcstzhKhP3Nc3JKINjt4U5NxDGSG6IV+m7VMYuJTBi
dKCGBd0m1I4Ef85BY0uvPfJ38gF4+pgZP+T9v6bpklmhygZfstZKsv6GXhLgVFmN
z2WbQn9sxroVCnk0/d+hkWLaI0gJhXPEaM+g+bgWqpQdfwgezVKKeemzAv1knfLS
QQGI0U/ZWvnR4/e0M+98QlPDbHrUiVHAVh18UYbOXcGqI6JOVxhbRtfQu20Bj/HT
ESOdgx2oBr59n8/zQ6w4a/s7
=r57z
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f1058d92-f8f3-48ce-afdc-991b967be664',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgA0PXolOm1YP5WUJHai5g/ubSFSwSqerL3yC+4GDxOgN0X
AXYTbepEdk0QXo8lTalE/8zTRJRTj/xeqEt40hurbDB0rgykUR94JklFMm0vyt0z
LOIa6kSqEfcOomP2qH4+nr19oBa0LsusNgE2yh2+04aqnP/nJUnZpz6yhrnLK36R
7XILzKEbB1Sdc7prE5nRHtKNQcfayop08sdpyzgPM/8gi/abUp9SnKg31m3ENJFA
BB8/YDNV8RQDbtdHrD9VIuIr+ymTi4iuFyuypVE/TlyebdMTtHlFJkTFhQe3wIzq
D1NZ4wedbhoxdd7R67mHqxEqL+BqB0o2JExEmlgGntJBAQyL5E3batM9cpU8IcLI
bmbE1HgO1X+ydMqhrvBkiCSNaMmmOc9w3DYRF/UgZaf/y/UtibD8h1wVstqTPBhZ
4wE=
=XySH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f14c8e1a-1b54-48ff-a730-049cb4504946',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf9Gsmff1swrjO01FpWFSjsphsRMrw8mcuyeFINzDIlJ2A/
q19gFKJwgNYK1y+w7ej9nERyPkWhe4yKQGTi8KJ1SvvyxKzMHOqxRhYZlm4O8NM4
ru9Dcek7j0DVEMh8QQve+M/MTqdZ2JW/8U514LX62FFQWUYAk5Qh7IzPZnCOcryK
hFWyII4nC9UyUAno61ibRMx70BcRx/LuNS41bfGkEZep7DOivZiZZUHHsCPLPqJN
H78XEo7+KeXMT9KPV4kVivCDyqhzWD4x26Y33uutNCdyPegvWO/y3PP3rIbvuvFO
1CLqXFmg3ujLQ0UPUVRDuVwI/tUm9H4Gbs7REY0hVtJEAWOP9Q2jLMzKTksfoflD
eKvc9lSc2vnNfFd7XJ5spVqUXUheAdVYm4ELPKfvOh91pS86qFbFFJ6Z7QXtiDGg
tY/BMF8=
=alUN
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f1a4a1ce-e6a3-4125-a417-6b95b73800aa',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/UmXoIXgwv9/0yAiHJ5vDat3i+/f45X4/D+xrEQr/R3eo
4DOgwmhCapxUg4YsMQ+PHpw2CsF5srApdLU4TRO0/OnCedFrQVSlCKOnTpi7L6Rg
oCh9OUsV/XHDX0Nc9JuDvGjdKey5Yc4ZKyk/TweZ62ycSxO0LJTcws8SFpXWcJl/
6Fc+3i7Fd7WuoRSZjHEviTr9c2/VPdq0AxL5XUFqFh4dWL5BNXsKiFiHioe48+Ni
SWl5eGHjxBWd8ulWORkSmMbEkweKTebuSx6HzfhWUSk1dKLL12o1VM0+uFrAxuvr
RF7R49XiLsztRRnf0syZCWByElRb8IZP7Eu8BEnQTdJDAZjrLlty0bRD0AZHBYJw
zQgmBrDH6NODYAaSDoQIf/hj6dYN+HYwa0XBlwpHz8HLU9Rzr3FTh/mKDRBT9SF2
6pzCgg==
=T+u8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f1ab55ca-db5b-477a-a501-f5e92710693c',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAy2WjGMqksnu5HhbJvNL0s3nxodpvieeRAqFIqCn2fFi4
vViq79GcrJHuJgk3W5ZoJvSV+LmtT+h0FMMYTsp83BqmgRk/Wvg8a3cjGOMcPbcS
kSagh7QASuCTLxJjcH/A1JoMrsxp7yQDDg5QrpCsh4OZ/kmMYW6pUFgfC4UBx4lg
+4IAdq9/8BA2Dqoqn4a7dKiKBhgFZoyR+RSywOiLCkqeQky2oWtbQNhrtrlRltwJ
HQvwVmmV5gTbNZB0cQKOecn+7/YdnxQpnpIYaNDxvxeqaqe+zQhmCkHG2aEtYbPr
+uqpj+UQ4roQ6BWMgjZNiC1e4FTEq60ENricn+SXkDZcxioSEd3DDWKGUdi1/ME5
S4sZ0cfvGaIKYHMKOfp50kQrU9Cce0PLynvqLQb+OwCKRCdH3LAze8BDv9HJ3X4d
orWIUW3yM6eDqSygNXs2zTQ38N0g2GZG2R8myRq1XSn8gE82z7LgKupwwVITDspv
goGUKckxWEOX8p00mv4zM6FcLG5uL4FqwfDfMiQ222ruIlGVROHlUmo9KS3vmfaa
75bpwpa4oE2kmxLCodlrk4y+n+sXnxtYxD1wG0XtzWbVEDvTUpg9IdrQPnz1fnew
3GBICQQXPdoa1/b11tGVoGjSHaIwuGLEB9CRegEBcGESlJCgQk26Oi1uHx78hojS
QwHHHU3T+/C0twRsLmrLrZtvt+IpEkqpBd4w2ALV6MQak/ZmEUqtNNc3rzxP3tlW
oY8W5KDGw6jhPQWx+ee6BPr+8yM=
=HOg6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f239eccc-32e6-4090-a32e-bba318329a2c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//dtWmOfGkyXfXepBNSgQo6TwmEEbxz4aoeI/HpN/AR7pf
wsVZ4TBi+jwt2UN2UxhkSANyr6qwWPY5GIbmhy3/SPLh5OAF5rcbgM9B22mEVdHb
CAJoMqtczEcff3Ncm50jQmN7xm7/Fa87zdYZgaaaStnwTjkZKCD/iApgyGJbrO9O
VJ/T1/Nr+xL/5mgmdhXGdy/xg9Mk4wgYtxAq1ZiQoJCQFZHRJQznms7ddbpXOCzo
m5saOjV+wx2PVjA0UeVsnGUU3sssoGObMk2xoM7Cw00aIhE69qKek7fl5o0ZdpLg
UCyJGoaTt1gejgGGWXLpFPDe6OeBHtyUi4H93H+BYYaoCSrIXyKaQ5/GI0MFWbDn
TAtw4nPTBCCrzL5wUgQHCVskGtnl5S+Gw0+NVtcOT8zcm5LHmm8HGP3ZJ7swNdF+
sP6oepDedYpJC6gY7amIXZrnGn6vBuh9cVox/OqBt/7ezauMvBdGSSTW1HcG3TBf
dvCABCulT2WSufrF/SZYV2Zz4gPRKvFpCU3yDECwwbV/oHtfCD5kAX6NXLGMg1m9
njcOJ+K4njM6Cn3cDmFUgjEif3RAHM8dWzXl6OaxNG4k7v9KBC1Yn9jQmBIqAH/w
/w5zoKME5b2vawrSD32Viwj610Y3C3FSjWpm2xeee9UirLkZcz9FYsYEeOBkoWLS
UgHAbEyRELMwyDOzWPt/x6ZrYIQNUo/FfzGnzkTQk6AmAuVPPP5+cr19QSvvXD3S
F7MS6naqzxN/55ly1LIrB0XT08eVyvPUk7lL3Z3RT6Sl3yE=
=OrUy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f2aaee51-e6db-4430-a4ba-c3748da5e53d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//RWpmJke1FTfR2b/MrW0A4peoubp1v0Hb/R+IonrstRyM
eN6TvH39vkpWoJpsUCQks5V4MDJOhJL9haKeeY2R0I+BfxIwIu67VN6RWoN+ZDf5
5cgCPhaA2bDrGG3ZvhcIYTwfn7uvAD5KP93KcYomKlqT9UFHIu3vqcyvWAY6o45K
x9THBf49cgz+TZ43UA7Biep6RwEiq92jBvx3a6MCOS+djeAdEYL+OE11mx0pfuY9
i+qWVBeI0g/qBLyPKlFPnpPfTJ3mtny26507lvuaRq+Ds95WmiTa6IEeDg8Hxax4
5wP8c7qQClGjN3PgRrmjOEBYFemwvJsipnR7nEnCzq9u+u4uu2Y6ji/6DXHHg5UA
FX6eBxcjp8AwLBCLCCRot2W3f1H85tzvdjk6pAeYuk66Qwk7DlXyplhPMA0ShmCM
0j97LFKlNatKRWNatz+ttp52k3Y7FiQH7nz+/4n6ovlkIFNpEXsQHr+dW8oFt2Mz
XivHpIKgvfIjkjz03TMh2u31qT79aWEXKJEoBiYdjjEXhQWoTvgB1EF8t9PX8N/+
Eaixtne61mnT+4KPaX3hhqa73yo8VZbS+eF71tAWX5e4r9pwJ57h8R8AkYiwBDlQ
HwopUIQ7NnnDj6W1m/Wtk1uKwjnoQjQZVA0HLjQf8yUlOUUe2Ac7pZ/x+CMIpB/S
RAF5QYIwS4M4FWd+mXxxT3azspMlMbIJ4T2Dv+36auggmpWXgYqZRnp+f6F/VD5I
8moN7UvGGlMmKBkU03uMec7hZuj5
=K0ty
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f2e5aea4-e958-453b-af9b-bc28556696b4',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/7BsooMrGSS0c9uGzKsxN5Bp+oLXCy29TJCVohU0JnntKi
Vv4+11zVY/TsLunTh6v04nYPbJnUAKr3UbbPxoxqN8xpEFFPcfWrpoJGA1Ojdeuy
ajGQyMxwZaLmslbZJSrkyMKr5Mw7psAuN3djRFDrvTJgBGdNWfT62moK7X4Id66j
AoT2sRBGOqqjxrk8MlHk3I+xXZ/jsDTejnklf1cg1lDjlzkTj7t20Fm77QftZUCd
aaMHSaSf6Rr9x7re965YY+t7bGUTKyFOpOqLbzOIIxGDySZy2cL8VVBqBJ5xstNW
9x4jRk+QD5R+bQ2mu8uNnSC3eBpum9nsLGcjmIKQL4aJWhFqIkrd1fFUF0hG6XkO
sE4ZzsKRwRLfVfovPYgTdzzVgEZHL6FhjNbWRkjsVt9oSDe1+Yb11Ictb9Sgey4q
IDnVdMAurD24zDY7mYAuFvNI/BqjzHBEq+dHII57CCe9aA7U95PkRcwoYU4KENYX
YAwcdJThHfbbzlw/RjEwrTt/CaLNKZ0J0pnjkGoPa2oFyMhAXkanztTJjaPF4qGW
J9ReZm5fFgRDplDIBbIoASlrTDPD9kmhLhpERiNshmZBLmoJA3oe8rngWtPoWllw
DiLMAVXYtcSOvuU59OhOtB3NbSxQD0jpzM3jmEQDCs6R8UZYj0kviLouwndXr87S
QAFMtlJOdlSuOF9aEeG1J2EdwFQcBuPzFFEup2iBwusCgFteWxW47aQa20xLxi2x
IZS5tO9kR9jF5jQd+WuUjmQ=
=9+fQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f42fcbe0-0edc-45c7-a93e-8396d45c08d9',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+N/rUOlIrEtARKABwQYTZ6Es5K5r4OncfhyDPgNo6R43o
rLVwbSpt4mgB4COsuxGg7w1i8gBJ+/shzTx/1LOsUQYcyQ8d+rTpF80VMLY0RIzF
cDC3Q5GH5qvzUfLkWbRSpY0k5AaaOlSvAeacIHGE3Ye617005PbfJmDWWrIa3VXK
lPnhionPhfHprEYsi4aa8iw+oJdliM6NGzrF0XR7D/SPLswsT49Vt47nwMs3tjtO
20E7geTWvryKYRZKWIfN7Rlv3kqAirlvSOkLcF1vo0kIotCR7tBoQ5a1Qc5u9F0B
sQ9ZW80nsq+vw3CAum0vBv4cOw61+nAZOYlg9kPs/S/bVQbfj5BIziKBPqLObBak
JHVYEt56rgt5mQEevuzm2L5PkCvhFrt7IBxHKUc+vg6bOTHA96DOWvGojyMOTVti
dW08oNl3gV022DNXeR1Jvla6T1YEguRBvRFrTj0CDMdyMeaOL1U3mRR2sezG+I7b
9Yb5Ug4ulq2TTX3vrYiN8mlwfmrnWvni1YMOncbkHWMGK7Nes3L8RYH9zFqiJrBI
7bvLyx/VBDdklPc4Qhcgq6cykp47JR1uH0YUu6y3VugzLSzAzDKvptv8WNLuvKMx
XbWC7j6l3t9TgLHtFKp5mXNdAkW4U5h2RGvlSZK28DuqzhzDTT9cfec4dFVx6PDS
PgG5yQeMN6qyHnaH1lWTpbhCYprFV59uRi1BxZ8bnhok5YcQIrcI2Jct38dkGubO
VSSJxshja3mCLeVgclNE
=UrsE
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f44c7071-33ec-447c-a2b5-68b7dd10001e',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAjS6HxY6Bu3X6NYxnVyXksJodMJ9/Xe37rhc0qfEY911+
JFp8JZg7vYRJ9atO0SCBqx/YvUybubGZtW+qJ7fyN6zKH+rT4ywPaoLKR713S/vQ
YYeEt/AuA7jiRGM8aXjXxV0oT60G7kw54Ev5cwAbZt+q9pyplKdL0N7D26nDhlbl
WZHX7CJjSZDYkxqgOY7wvdvWWZMBZEeg7lWdxx85PxCT9y7Vd3gVILsw2wTvSRAO
xtWeSg1dFL0f4L9lTxAge0b2Ax2AtC+XXoXzK7gz/sOKKTDSs5/llPkGIt1NLNRI
/De7sit2HrDVmbR4x013VZmpbEDHRGMkZgoXpJXBH543ojm1ZG5S/huj1pwh9RPS
+99hRgGRxVccNeeoFnG4g7/BKeaOBRgQbQ2gA0Y2oEXV3aQYmeWE3H5cbnjzM6Qn
V8+dgR4NtIuaw3rwNnDUZI7wbEZN075epZuU3Kn9Wu2P1GRdczPtsmHlGwVg4EWE
15cLVsmwxhrdiJGjIHxhJrJzz2Ku3iVP51y56OaBZBkbHAddFzIaV7PugsFkCDlw
hqrPc0vhI1Rr7qqmq4Xfm1gm4eBvbVHl+6UZh6lGcrM+hYvlaL1zA4uWS4Od+JOE
oBv0a4EMIx9pe5DFhC44IrPucBFhKf4ZnwaZiKUDN9KB8gLS10v+y8W6gUxahpzS
QQHxqkVKp44nTEhBlE//Rl2yh9WIeqzNhDjDAH+VxCg8I6rfMz75pkfrJa+m2y6N
6vjFsZgaMQpYywfjOvNQUrdf
=wo+l
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f55bf74e-ab4e-4f67-a9f4-b87105c8d155',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//SDH4ywjj4w+U0//4vshpL7lPGsUufiV1a2ceqaMse7KR
W7lM8+Fd666fncAk/RUKvMMYQox7iaiWuifXW4LYiplWCvAPF311Txy48BbwXXLi
Bg+x5/nGt5/I6KKAEneACHnYlm8/0SG5nj8umy96fEYXD8kXbYS4cwmMnhGxab54
OCYm4EttjW41iy8Tp+JkX+Ep3F2uzBxZ1uNoanxN/qyBYS46u++G5snJ3HzUy7oL
KQAEY2UuuqfQZAuZwYPoZjmprc77972BUmRsgM1oPAmhsZw9uTelG39f5R5Ql96o
XI0F+vOHVmHZq2bSAQUt8Ftf0z/gI85tD2wnb8Yp/wWhTIhrONIa7jfbhJGS/mF5
dTpM/rDke16B2abkdh8wbZZnwVLaga3o+jHZU6/5r6GJr9c4UAzNPRBxbJiTTpvf
/cq26L1GhKEKAGrrq+HzP030tJlgeKdjcifDKH+7UjlzAHkZzK6d4U7hklPxzD3B
R6zjAA7/s2/sz3j7VgjKpLOzVVfWB2cbYh2JRjbAor52sXBjpyKh2DAN+xq/cli/
BF4lSLaMiVjVZElKrf2VH4dvhjP1o2Ir/jd5lhvVu57u03+ol5xyJ2pVnWOeAwc+
Ih9VoyQ2KE6DJVYfDB5HIUHI8sEKuzOoFsyYa+wEyZqrdW/HaKG9LQaKOJbMPsjS
TQF3A21D7bpLvHwQmaqotwnRPpEQwqiwFNDSqkPGRj6D+o1c6WdTmZ/jOHtzFeO6
Q536SFbkJQibbMjFLBhKiy4GCfY58PpihFhH3Cui
=vek3
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f5a6a20c-393c-4b14-ad40-8806d2358219',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9FibdkLZsPvEzywKdNqIUmkDne4ZuyPiPEq2hMKhpSRJb
uHcpZCUvyCv/Tc1+azGPC27puomQNvYhuRHwJ3vCb30+3xqcHRy5nMQM47GkYWtj
+rMdhTaWWgYVRS6r2qxcLsergJZlrVpPexuR7PQhDVCUzsPjT+eigXPS6g0G8jZ6
0qHboU3Q/Lic3kspbausyDZclZXcchEwfMI4ahy5RZuSr/X/d/NGsb02iNgC+G9P
2TEqfjUzQXbdZ/Jn0Sy3RTBDUJhvHuqi2Wlq4KFZIIlvIFA53TZWWUWaUx7FjAJK
6AJ0OdGg3h/Y3EPFEvq3bkaIlSZNOgAvW2clcU75VSDycoY8xhwhajQ5CdgcLwn/
pbtiVFU7hwDhcMzs9jP2JpXqY++vMjU2PgwhHa4Oh6QBkRMnsJ9EnsVabV55RKMr
qUP4yai7p4J2ufjrRWEym76PJdrLalV52IJnk9CaEIfh77UblrlLjBXyjv5dWXju
/+dc+aDYQr/xo4aFo7ID3D7S5bi+BZrX5Z2laqaXat8r1nGJTKnsC7fi+ynNK8KY
flpPfb+kdr3kkxSNce4saYtJawRZ1+ob0ROziYGq3kBB7hrLS5oT9zt6kB4U3IWB
0DlLQfHZ6CnuYbtaOpV08vhg/RxaxBubhd0xtAZGNMiPITxoPobgQvPYsvtQ1azS
QgFSd+1/lDQPWSF+P2HHLKshjktuZmix4zSe244sbdHY1wowVinPNP4BA8Sxmesf
Wrqm+kmZUl4Fbdvcy4Ip0lmKiw==
=Awo4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f5d09307-59bf-4b98-a2ca-39b14ddf5e9e',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9GFUr36BEwAm8ZNmqqN2xOAN/R595Uhyi/N3iM6QNbglg
SeYFyK4LeGETneQEqwdDxzdpEasi815JgKcBitxFQsRT7MyuOi25Ip9F+N0u0//5
RAKrLLx0LW0Q4/fpEUbKKgg8SE/Bk9Ejg5IJ6t1gYU14yzcvkAv76pUr64pVWLAS
kRafo761FkZbdxazFvWlzI4C3+5wKeUuC309tyh3kZnhxmIMIdIcdFSXy0Q3brh0
DAnn5JBD88snTj5Y0g6JpKYbE4li1CSyCGrZv00QXpoJUWoAFFPQ16z7GprO3Co1
9tZjPfWCFiMsB/F/KmK3tRCRAEQtaS3TnysNw8e7+oHjxf5r33xhOc4GbWjBQhvN
smsgqjwInRkBzodawEMhNAQ6Yc+v3tirxk4qduBmnr5f2hRDCBvsUpC1lpLtPb3q
FWJfzjE7cz1FALCqvxA09bVVAOMVP3+wCSeR0TC755I0vbuB7iHtNIVJbg6lrGZo
6sUTlohcrqoO5SCEpqB/IFYLlKzP85/JDDaCwmluC0HCGrRrocnN5ISHiqVr25FT
VtIDWiSW4QTyqEOkBCOoZ+CMsk4qcttezcx+JooGzWMDJLViBn40VmXyt1k9No/k
sQTOlfwRnJBwcspQMTeOidYUDbL/TsCEW2agQdUe+GBr3VWowv8cF8iajxs6zWnS
QQHAcZJubG6oxo/jEB95zD3OQgsZQSyWKVg+hbyc9D2ZPnhUAV6QwmL4tWU4yrxo
HjEfRDNEZ8q3gZI3JxRhPNcQ
=7brJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f6e1f026-e237-4865-ac3e-133da85942c5',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//U7E5jouzaPWQNPEEavFp7p7RCZqF4a+uEFEzah4getcO
7CoGQLm6jJfrjU/uuGMXVUtC1QRUUoTlhTepbRikAl6/Kk3uSX2TFGP2CMYN+4/E
JT/BuaLPc+LcCoLsnByS27zId9EiEsP3rd9wLrD/fc08rbvD4k2PAJCRB05GPgkd
CtQ58wsnZ2tCNH+DIWqxNl7opbJ0sPCzvB2aS2nXj7ZazpewCFbJtOu+TPH5lm1g
fn+1wge1gt/y7vbD2jSn0+QmgYrVx6eIDtL2WJmZLMmFeIrIPCQ1MWRJl04SH60K
x41iHc0BIxkZq5WQuxwpZZ5HtjE3MgM1Wu/eAFKnI7HeAYkPG7sR/CGodFBI2gkA
7Zpj9AH+JD+PpQ1IdC3Kqt6n2hsLcZK71qq07co9EJ8nU/bpi/dtr7O03VbZsF+z
KXNGfFrDy53rQ0DqFxi96s+PzE9r5fRjpPvx1jZGUr4iYMJdznEg8qd7mN3s53+d
TD9J4h9mJR5OjVld4t0O6t9wqB8xbfIjmpV0TTkW4X8KWLBMxQuYFJhjkBQn7oSc
UuRO/9m2sMmR+bAmATWOND9rUlM8Imo3Vzb/GMQ9msa7U8x8fDgchRrfvUvXTZIP
1tsgdmfnMf6k7HBJSsFfstp8NwgYxfiHlekJ6MSCrufjbZ7K83+FNLSQ2YJA/vvS
QQFMEpsPLz3Psmfr+UXvxbvESGKZYRrUiRdXflkbYtQjk90eQq4wEL124XDrr46X
aBr+LaGa3vRCVYN3og8VdIt3
=HymD
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f7a2e7be-2bae-4eaa-a207-85e31947a5be',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAnth81H/7PXqPOyOpKA1SsIRcI0Et+sDChYI7pOcBBjic
0+xlf1qozHJ/Ku0S6OijdcDyaWV/JeOQCHUGNSUqzLo+OCJFuHTq7FVnwDBtAi4r
kKGbo4PY0LiWEO79MoGLVaKT9LUSjjIn2lbVeGkMwOS1d0WT/lTtXNAf+Rv0mBaL
jKqwWoFuYkGTGcITyx2GMuZZoGUUtKOxI/2PilaZnyPoDG3tQy13HyH0vWf5kEvw
EmJ/Yz1V/AXqqsA+mMjue86ILcmPfz61Gd/Uu0Aq72EXoTPw8w0VaLxrtpICBhc9
1hAVJAmSTqvTe0slH+dKP/AsF/5zJlUWOYVCPxUxzDLyXP0gwfNm4ZS1ONom4KNX
08wAucYCZZfMjOO2slqiUywN5Yk8kd6Ru3Wzp4njrDKO8XOOA04+a0zEAK04Ii+O
cZUppap8pHPDiZ8XZN4y0dpfGPauN6gCnifXpbwhdltDr6ze6timqW6vDZdCpcE/
ccH/3iiXlcnwzJ4g3z+SvnvLESr6kjatbvKN4FgueSq3MemvYxjVIGTHZxsyQs+h
4D8B+Rob5XkxphV5tLatCaHZulhUJLl1l/LjBBg4weU4G7wU3fte0uOXU07chJQZ
JZsS/vjSBJ2K1CX02Jpc8EG84PTffV7/RjFfFaFaK7BSoeYobZsbIZpHcdgLSy7S
QQGTlwHUvKrfu6JriUcncf9+EJDiHC4OU+FzJ0UREjMN7ufSIkVNkm8acLehRPBi
f/sG0x2wJ1qjgTr3i1yexSx6
=VMIi
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f822cdf3-5ad7-4386-ae4a-f4b5b05ce8fe',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/7BISemik0CJjaKoekrLQCyNHPmvEkkSQs4U0gx2i50e4R
+QXYUIuT0CG7snY9UiOpCBRNJqs+4JpP0+gVCjz0rqTP4tvRrLAGI+I07pyUjZmm
j61pnTavZjY0VoEnplRemzyGpBNiniLqPG5QC/FPxgPiOTtuc7209pgseR6IsOAm
4trt+8uKeUbq9y9pRmE9x3ijrwESR9ZS9G6ZnIt4hUekbnNH4mbHengPfDXQ8ZPa
InLT9OGC6t67l9YkyKCDXbcVnoFMS98t98e8sXhcxteMiGl5xUitsLGCVi8XlplO
DMYPsISeMDPBKzFrKJvzeQ9XhuACmIw5PN7aF4wx4H6M8HJKB3rmqHzyX4gjjoEG
t91x+KsZRPmjMvzCpvBAkWqjxfqBfoDv8WI/uMi85zTvSll4tqvB9hm/yg5g5ErA
8qBPvDjB4dvMmemqR7gfHrQNJTE/LTMnZ1+/SMo6ru5LwO+V5b0nPYG6/60PKegG
LC6bTqo4N8RTWQPg8cal55bGMyEgTmh8tTH2KMmsoU848dfbQ0YGWiwu+WuRa2iw
oa9kcE+TlujC0EtEEuVkISkGAzPSHqeARL+t+cqLanbZvFGDIyhI1021yfqeA3n4
k674P3/o5z0Lid0pmpYd5tl07b/0nhKziZNzjtXK/8MUvsZjGYEupmyjvXeQox3S
QQHwKB1FwPdj+KQDpqVvz9MmgMQves8/tbdQVPPTYmGHlLNiTZ3Y5RJXQ9ObifEl
reF1iqR+PJ+m3pP+YTKRI+XP
=VdhD
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f8a785c5-b487-44eb-aff8-2685c2db9b40',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/UhB6LMcAf3nuxt0nVW5VOcUuwPUB/cjvaXrVoKr+TPz0
fYa9lrSCDD3mLYwVvCakdLpa7IZsCKmXQ7vnetV+jmx66Rs+aTfgWlWTX9kS3KlK
DHc1O9HNSTSeHWv0QB3gcyfXPYsqb1Kt7UL+3tBf3T70JMFgQ2ElbK3lBs5q+7km
Q/ldL/6VT+E60CWTwRwqrki17U5KePLUp8K6Y6EZToCMKyljXsXAdWvxfHvCVT0k
gZlUJuXPUYuaYfMTeaypzQgYlKK8C9/sRzbiUjwKz8san+NJ3D8P8/FekKOYJ0Uu
chEIOggPFwelkdoEjkn7MtVIx89oQTZ00GJY+lPk+NI+AUlrDhVe2USxZYx7P9Tb
Del3WcuNfFi08gOUW2XznpjaShaDdSfbGAnkfonfeKc4vI7zWeDrZcYzLpyTUVE=
=vBus
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f8c4aced-010c-4742-a7aa-86a208bcbb11',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9EtTIjAcYq2NSUsrUwXnI5AFCK/9tri5mtX+Pn3vT/W9Q
XffLRgCnDXsBrij/w3UgZZp2n06aI/h9ziM8uDy+cv0Bj/EldFZFlgaiv0kfOcDT
ROEAdArGfW/fYDJ2CszhtA1X37fuCAjCcV4p+ZMYuV/n3JSvOc9HoiP3SakxBDFa
ksfU/lSyPfW4dZmvQMltbFY0YIXyZee/cOWORLDpmVhCB4HtupnVXGXsjYDT45Oe
6e1ri7SPdIynGquZNcuXQcA+hsUkiObeF8fQ3mzdo9e97F36h+1O8KO/2Zrqaffq
P514w3L1MaQpEvziSp1KFesfWoS9m3AgVzz9PKh69kJrUVdWd3NBTUL3OCqWfM+T
3KdZJ1JTYpIAcznCcsRk8PQwhzDzek/MW0O/Xxc1laOV30MzSuCdvXr+jAyFjW95
ql0oFrwkESzhdzZ7rW0vKwEPcMB18iSxCIY++F6pbkMyPYkb1vws1nTF22pI6mE8
Rbqh6hRYvH/u/omUmjixC2unbaGHYx+9I3lZJ7RxjAqzFBNQCZQwu5QgAGSbUjI7
o3X0Ra4UjHfAy7OdWWc+fEU8irtLrcJpPLEVPGDE/klLS4cyHnHxWMqK+0BY0Kx3
rWIUDwYFBg3QWc5QHaLEO/hrTDuTbejR2zzybLmkxoSgdkDZzfTS2p0VYSXBuzvS
PwFf+MEs3M/GX7Xn6ubzguZxxN87Ojc/kuAKL/54vIgYHT5R0RR6x36r6CLpR4AX
CGgbrza3wU3tsfjInhkyrw==
=Sgu8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f95f10d8-54df-4751-a89e-995e2d7da626',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9FACIxwlOL2enjE10q66uLk+GGeIfmgRxJ0jAiId7zATQ
GM29w6oBIDSjfdPwxmjj+UnDOnKOZ9Omjon/8aJoBymJ1/sueHaoj0Yuu0kwnxRh
/kajHUEegK7MjdSTSv/gU20o9QZ4/F3CVRMS6Z52UG/2EqTDoQz/KuGc6JhSPYUI
joprwPzSuFautP+XEbHD4aohFsjVpB4DbqLNSjU+iec31azWTXTq7htFxXPxykup
FrVUOUaPFOqcnNHnd0Py4CIW9DijvIY8FiBICzsnlFYOli+Ri6IrSS7CFcTa39sf
Aw88JXOK1VMxNYLP6gQRFcQzTlSicMRqfOvsL2cl79HpH/+RY/2yskZCGOeKCV/o
27xL/H2QWEfJKe7cdj62yoyO+D0yLRSU/mjNGvHtVgOhZ1nFHBSsx5i5Xv89TnVI
n++pv/CYpwlWF7qPXos0OkW7UUFMN1kAA4tUkocFV/NgzBBzyc++IeNxBn7THkVk
6g/2vvJ6X/W7CjuIOzLJrVwFjXSnumzrs5eCcxKTs+0oqW3CN5BOfWHnL9WG92+o
w5h6M1kkpUR0rdFvSekNnXkuvnK7j13FxURqQdPneOcgBnNFVAW/d5eKk9Ip4Lex
5FkhzMu1HgQ1bCZCQfOQ4VJtcLsae/3sgBYEMp420tYbIjdo8bNhbggsbbHFxtvS
PgH/JI4TfgNn1W0p885AWOdU8X2KBRUgiuaI07pzQR2B+43x6bksziw5sb7axH1k
krW0fJ0ZLXzcYxZ86uGa
=2lUc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f9eab055-1d1c-45de-a67d-7e0a5fbd114c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAjND3YmsyEReGa2Nx0nvn68x3GAYkFl49GTDZGQ/f6HZw
SsC1voh/i88p4UmBcGIzLVt6THAOu3zxuaZEMnYutYMlPR1N1uwnLGyWft0mSArw
ZAWmz6T/kZ2C2gYqJ+a4rKwsO93HCOwr+UiBJyqnL3kuepXra2Q6aRRRNPQZH5if
YimQNRg3UMXj57/OmUQfYfYLxnzF4pEW3JVPFnkcsOhMxrFW4Z9LUZo8ysrfW2Mu
h4YBjDoewpwmC+kA67LDxhlqmJS+DaVPHvukeiDf6EJDJG71TtalMq/h40O9GYol
qMShI+Gx/KUnIIxEBRTP7OqkDbe3s+9nf+Via/T9svTiAG//82gcK5WqyPwnTPD2
8Ma0Xe882g3KZgX5SYOwi/wgzVFnLGbCBx0GMeovoaBZsBF3gSEN9XXNXwMWDJRS
Pklo1TMUMUlu/kDh+XikeciK7t9TxqTahGLDFARPdZvuAq6jtBBdE1fz0Fcvj6+E
mdtUSkFSfDjKS93RUt8CeIePthaMMVqGzLWkdN0LTL/gkTH+CQBhNjSpXynbPCPD
jlkENU5TZDctcCs/DRiPmJ4SBRJubsdHR6Ka5jZ9URHv3Ya/uT6/6t45pW9VvyFt
pkC3Xl0qJGj9yxDSNrjhUYwp2pM9bnTuGtlCtrB6fVqxAkXVXmuSfuCI4mSM1I3S
QwHZAW0vyVs4GYbqHx0gg+DSnL1mHbLbeNT/fDNf8itVxdjf5WNzzqwd/Kr8BpES
SqJmkCzZBquN+fd6RmCfBkWrrAE=
=a1j/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fca763c9-3201-4e91-aa4f-38b845926017',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//dqZs/90AI+bibPe8D7xrseOH4c4FojpYzC+TEOioFrP4
q7XVC/pfqPKv97ssm57TwGzWl4IavyqnyMb75NxncL3xEqg/EXABxNv/4VL5tldr
S4DMMy++NMq6tms61A7bj2ctc+EGyfalb2H6FVzQyZSq3Jl2BsEFAdDi/re/sHZW
bH4ba8kvKsXXY6et3GfcQ4lATN+uJ2ZcZ3GDvNFzxNj0SmFb8XRpC0gbfXpr04d6
Ag88K4Yny0rh+CoQMjZaXAp+197w6F8X30EwM1QJ8C3NfVxTH7eWPtenJpCb2uR3
xVJu2jH3yXtVZwtsc5AJ3+IsvumFjObUVaDM9gPLlkiNfD93o/koKhTAHPXn7kAs
6eR3qg2/11d0LrqwruB/eMvErGeLHQe3QgdiLs2Ukn8pnCNhjx4tmm4LVaH2dGoa
Uds/BsU4s//hOsqAXHOCtmEQktR/h0oVwLWvDcCJdoLtSs3zX8vRIjj8LIRQX58u
CMkvD+KHFMY4pjXgt5kFim13G8gG1fjQRrC9I2buXjTYktaygxsLOyL/CxCagcZ2
9+0BhHTOmwHQrqhukRvVW9upJaDkiDFkc1xVUV49n0dbvvq/wQKRkfkREWzP5Qbc
utUc3bsg/nYs+uyDHFV8EBlff0RIewOYvOPrJxnUlScbTt+N0c4kKguN9ZCM12HS
QAEeK9Nn0T4YoDCkR/XofiKuFkFOZaZoV78p7BJDbgbWLSg20CQOBvpQjRfN2jox
tZDLL/DbfDLhh+qXrkm2vmE=
=LK4T
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fcbee544-f9c0-419c-a0f6-f9d2bf78fc69',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//dJFl+FUyoBMBOmeNeFdWh0+H+7g/WLYV/2IeyW1hHq0s
J8XuEUdvqprAkriXakQjp+UXAtw61nk6F3vLPALDeXyUNZF/NZXnNN1d0xwdDttr
dBHBki/ov70nABmoA2SMJB4qIly6vPYXzoZBmZ6OruA/xWKTFCIgYWIoRXBYxrwP
II+qqCbR5O4n5DF9bJZBZLtZkYYY6IjsE5s0Eox3bAzdHUDw5xXRKB5WJpirm2ZB
LgAOmPRllZmxkAPqdBYDyEjJyuYWuLc1Xsy34RvnxSIuZImj2qVeJVf1Zve/U3Se
Z5n5hc6HEZTdcjiOr/AFnTx26UpA0AXAyMk27mhXAYSSiklnOwkQweqq13LXOm2V
GXjokc/Y0A1EsjYWofEw6jqwfdvK6VlwTRoo5lKT7qpobPU01h/GjkboM0Ut+Dd2
qmfQJk3D9qf4PsZEk2rwxYRxSLXbdE8vdqDZAmZ3Jz9RBcmkkBrkLH77W1MfD58v
us62rL1zqUMzYthKi6HD+/WQiOc6GE4s7J7cBYPNvbuZSJ8amJ/gVjWbrG0rovBl
t+dU/QNXHPPa1eK0DymLaYBV/dhG2/EJ8ehDF055OoppDxUV5PYsKMGlGq/y/C8d
IliR+waYRoH8XqwY79KqSc3dkfkLC/I5flb/S0Mx6jqJLm1zQW0gjcI/8UrqrejS
QQHhzWf4s82s/zHW2uV+Q9DPj29tv+NAzFhWABgDIYPnE3luNnzRc2sfi8g9iPqw
hjPjPFiwmtzfjt9vyAplEIR/
=7I3d
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fdc434b5-aa81-456b-a1d8-f4a58f3f2317',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//aIAFagjDBW7+rDB7OKCQezRlzNYUbh8Hu50+u0J8vrWS
altvdE21lDTAnP1N93Uz/XPnn7VgBkIJxj2oHjQcZ+OU9nPMUBMfOt1hFeHJzNas
9qEi4CxAQaORZctkHVktkhUk3479qKPbfPYineh7A+RnO9nh7a9oYziUb2mts2yG
jdLeQ1PpGs99wwPr7KowZWQsP7w88WZMkOHvYZfzgZ5WfkaM5fT6EPac5t9vSomu
BMjlkRKS8Gko8q+wzyBpzH9DT9BRwdAKiXn/MgSNCF8RWSDm2yqesd97tZKv1tKP
Tv3as5tZAO7JSQih7LQt8LsLOBt5JkL+z1GoGTetSNVGlrKJAKpPT4bLbl2gO+Sd
bMnRltpoJJVgI6efFxKEFMYeDGAnsnNUPktuE1eCiWJFJ8aF1FoRQgOf5VKnNChr
N7hoSqAPgKXCvdlsoCoQNfLrvKV76S7PgNjRy/Wi5KvM1Z8grq8qEcrGgiUN1giv
BhlVCI2dSRX0Kpfvbtx00yXmPRaqyj/Fx0gOQY30Y7sNOzqZW//cQ77mZof6o08y
OQD8KhTxGdb7tNu2YgOm7ZvYDFOfdzPsHhqWoLSRWhxKiROHb2BS+X0cUIBpVWQm
KVjoTtHD3BiUSl7k/4b8eOlQDS3EdDcfYwZcsdxyfjkI9H+XybAUPjpvYqfe3QTS
QAHsp44C+2Gp1A6cGdtiuD0s4KjmI3oS2VMhJPixeDldseKWKT3nBl2DKmIsqfCx
hHDPqc6o00StJhreb1b0Js8=
=28HL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe6d76a1-2359-4a63-aa84-6baaf6cb787b',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAn4BVYxt4x60bQAk92c2S1FR4QTKd6Ay0eD8gJmn6fDXM
5Jdr24fRJnqCRsO4DUoRQbZ7qwmYhGxFzntTWOxnwILk3Vq7vu1yOTkP/QHkBLPc
eWBXH0f6QDMqaSIo1nGOB8c3WJp//bEAsXUcp1QhYr8FE1CUugOpwchjOxuZt1Vu
88v1wE/XyF2lheW3SV3tLk1RFxnxawKF5aSELQAsMAgiDHIkmwcj1dolQ2If7S57
UQRZXwysH/4ejYTprKbX94JMjitz+VcB09ddRBQ7q6UARTxW9tPnAwS8PjVswUy3
oMlCabT2/xlNXxjyTQ54ZIie40DqEX6sXQ2VLDhs6NJBAQvrrMDIR90cnYNJ+T7V
DoUpnvIpS7k+I6fBOjQqKfnFOqDfnbS+L/RLgQY/GNHL/EnYKoM/h2ZW4cDA8ud9
24E=
=YZBI
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:50',
			'modified' => '2017-02-21 16:13:50',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fefd0d80-bc77-48ac-a303-3a320262627a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAkww1dD3LdmSG2Bi+/0UkPThyv7onAC/EtIVT+MUFoCwl
ETDtNnnnsZ2nqzO1ouWQDIucQnlsgSx/8lRVvQYbkPhv8TmqZkujs2XzLP9ZPubK
ISCv5JRLJOfPnj8w+zpimdyK3E4OZKpA0otXv9yZCycJQ/golIhQJkB/I/5rh+vt
+NytCjd7xO1s2oWjut8Xi1YefSfne4M6I5QImPyF9VDDc6oBjrcA+dcwzRICZrGe
J3bOxQDyshaq/wIQURsahiDF68zSDEELoLB5Zzqff/gSlA49IQ3Sd8TYqCYolyHk
p/v0cwT4xfhkiD+kUfayQbmhI+kYrrCUpSAijSoiTo6S6p494+RpAxcc5cp6N3lh
+QJ4IhEt3IznaMHBQY3DOM6Kf2RAkQxOB1at8erqAU5DuHVIbbVhQ+HKv8gM1/8B
lqXOwYB3UmSvP/WBTd1SFIKfZEix9TA25JO+OI7FqpcjTk97hlGudFrNMV961prJ
iqfdvAFEVB9umAMn7vZIB9ro4S/09iD+efEM+1GTo4sJd2NjbucmUsCy/doJSdh9
R6nLTHR6cBvKf6ulVnHxWbpIvdPmP3dh/1LUj7ZyvJNFTlDlrRadd4p6A0JcJxbN
v7d/2kW1Pnk7G+97bRPRzOxvDNg7X8gcKdrLLzTLgASKRt6W9WTw4TggMvMV0EbS
QQEwGSCEqShUFEY16m1t6FXI3Zgc4oVMz2Qq0kXYwx0M8VoD+LAd98QoB9JUg6Z2
C3lFHktG+mvXzi7jg9ZCFm+r
=jLca
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ff2013b6-3f69-4c90-a422-79780f3c83dd',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//fPC2+lYWerveVtGpFVUKMuwQ6ncS2eYaG6SAdrmzRBEp
Hqq0aS8vQWBHOHXqtH88Buk51wHvfYzcTBZnSxYgnbZrQGPofX0t6bIc5Jw3vN06
3AI/u4HoXpQGTjk9Srh3ND/ZpZDfgoS0CVsmoato+xCuHdZDQbgF/AjwD+DRpexF
j36XNw2LXID67tm3jJ61sHjuqHIROuBfMVt+crg8ZEi9t2/vw6K3q2TCy7Hb2qV0
3jLEByCHhO2hVs3eMpMFDWTh5PGzTU/XLhoRz+EhLWaFoez69ivrE290m6PIsEYt
lPwJJ5JYs2wxS3BLMcQpyUH1e/NntnMMvdAQtx0dwyNxB7WKWLMigZGmQxzT9pga
sMvD2kv+93PH9jsyca9Y/h24H38N0NYi8XW0Eg2uLmXc4rH6lRm7iUVmSlZAsVAO
0l5TQrRkUxSD4AvqsCWE5lMyrSy+tj1nYRLPb6pxSeyMo+L0fo2rlmH7CwNlOrWJ
MSMdGAH8DdGa2JY0QUJIMsYzZlH3qvVoxSDoMq0EgQxv8/zzaqatdZIci5PU/ue7
KOOad5zFz7R6YT8l9m2tRsQopi29uYiHqQ0zpIDDRsP3OhXaEyg9uj9+1X2EvzDy
F/OXHFLIMJTZM1eSKlB2rTnRcO2eyTvqTrjNnjL0a/qfVjbDfKuPdLl05yz0nPzS
UgGFVIqiYl86+5DK9qfbnIbGGNODAVjcKKt/hZtN5ujXhuJpL3vtbYODFHHVgAIM
vxn7YYdGP9Bi/ny1PU2cflzgIGkKtvV4/IYv09HEW9+ZVqc=
=e3ia
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 16:13:49',
			'modified' => '2017-02-21 16:13:49',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
