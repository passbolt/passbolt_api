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
			'id' => '05d36bb5-78b0-4712-a9e9-8b5d369daf49',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAzg/JjmXsFhMJsmt5CmoLJbhKl0mpJIA+oskqNwX2tX70
zHAWWvBnDBvacYf9U41vHTybiw/k0wqrOz2BTh0iH4yGF0o/4TFkDOdS4XW8bBkh
unRsETbqwT5CiQtCkogKCRXhXkVtlYRv3mslSNfUrBexIiujYFu6dz/CB3eK+vTa
8MllPxMGdOB8KM+NzWsCz+ZmtlfPHb+OHyIKed0prwspRLMXtChagJsiW2pGAFQZ
HyxNERTEOuns2lfxC4yZz1wXPWKT4b0UOqAe684rb1W3kKUFA5T4IMYTu8j0kR16
2z1lScAEeS9k4kLR0tNGIi6tCE1CJNA8EGZpxnMI9lolVMy+RXW+xRZsVX/XIePj
nxpAO4S7M1EHNwKHjiSghWDHwsiAowr26ygQ4jfmDWCRzJpwx4U5XZXRoHfPgxAj
b1qTvIUpwHbdPyQwoEivnuPhn9q9kNOEQs0GO21UT5qC3czqtZ9H+7i5Y/wVPa1O
D5XcK9RCeTRx+eeT/JOZLB7SV5qv1A+FwUA3Osli8pTsBDjLAfDg5SnzwlEqJak5
5T4sB2GhE3ot/SLkEl2PEFwif7r9upA/bGnZbjEd36u07ulaUVFRgEgk19WHswnl
tXaJyqCuoqQ3IJvidQTiWhJ1hx2ZPWkPspUhLLW8c6wBqBui6a2nLupA3D+BR1bS
QgFzE2OhsLj4M6YkyjsZkYyXuYq/k6qQwicqyodTq/8G+WrIDPAHF2b/zjoSLmXZ
o1HGlnFQmaCm8cFmsZJ7C0bL+w==
=VvI2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '07b58f98-fef2-4765-a011-46a218a09bde',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAk71QJXIPRFijuC1HblBxUPXOvq62Tsuml/DLMHjpgoiZ
DJEJvftXj7V/9ZxMbTlABAf9e+Vnzhvmgh6IToONuwh03uqMEFtEoaJQbdn7ocyA
bOjBr+IiZMWbTGxZ9RlHGBwREJzAqDdySsgbvYM7858jY+5qhMvRYpf1K8TN2sfn
ll2Jw4I9hSQhMEcwTBLmAsa38huRRx2LiAALBMeaoJCFoMH+cwXbkmyrS/sklkcL
ObMk2vE6cSd7ZBNNjukoz041WP3HbJkE8+HNJRJjfioHkq99/Bvc+RBEkH0Wcx9Z
Hmukvg3lB1Xjc74w6vy8bNkQxcjlmgqnQki45wWKAcn946SzoFqKl4W9O4tlLyq5
+TsnfYJlwIiZ8NQU05MfFmxsIleoThjve9tA9/BAn+aXeuF0Eynjil2adJ46D2of
dvHmnqlttkBxNoc6G947uykI1Fq1n2ixyp0Vp5RU8CcfGAklMgcWhoHIzxJUOcyI
urEhLETfX9viwbxVvBwF6YFrMZL5jrd85uG6tmfDbTWyCN06YkTqhZeEgS8wePYM
SioDb8o6uOK0MG4We/V668p1ZsWow4hvCw6m/+MhavfJoC7icRxeEvcD4AMQ+Fyv
iV39ZZjtEslTqx4P0pkVPb5EhmoCawG5W78IFsURf0yC9lKCVLPBGcSmexYLUsTS
QwFTAEx/VZcd2scUyR8H2P4l1WixT1Rc+2CAb51wQ6Jmxzsk2pmP2iQphGCjAs4j
qk1qhl6YljhQzcSogf36jCiy8Mw=
=Jhe/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '07c63f83-e136-48ec-a639-96e9bf89dc1b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/7BNLW5AxhjcXIRu01b6uSyMzl+2aSZkdqURqjRkQ5EWOv
acetkpCkzSSeyY5D7zV0ZaUP0F4kCn2tEiAWR4gd3Qoy5SHG1AKm5VeRPqAWY75p
z171W8GBt/U/4UxzHUWBONITefVRardc7nRfNN8X7aigPfIHlQuyiQ7j29+kc+sn
KbN2vHqSKuq4mub7vY0UXOaqSGWCEg4+w3G7+/4Qx1rVPoEHFdZWjgzW2o1LghmX
42sOyFOxo4RQc/zPcN3FMTaPMS3Uvj5eit90rfl7VetadKsZW0I1NAm6U3VHyiew
WGFreK9tjqzcV79Y7x3YqKMf7W075P17Oo3jxdxYYwxYKJ48Xckt8oHQDKusUhap
2aDOsdBTDjRYA1dTRppL/q42paBJvasT59cA4CIdBv/SAXrzDFHydEcyN1gBnJmm
wSR+TARrdIVICvKFzlo28oAofllac51KaA1aycSKrSLdL/ia36eIif8y/mmNkw+b
WAR3lTHMYNMPeTFHrdF7ms63uBU4yKgQMmQv4+JOyUw+4JHzIN8B5QXcmIb/RkpD
eXFF9k5KVEwWebiewzYPuM4yY4TTHkl0DGMCtB9gV5Z9/SEbBo13BTuFAmCy1Cok
nu0dj91ciwjMdunwOs8Z/VnDdBRPy6Ikqw5+XoQcScmVBn+0n1Z08VNNvkAP70XS
QwEYRobbh2RGOcp3G8IjCzLphFOLLG+D6s0EOgfAx2JSP2XaHawBe5q305DEtaIS
IPBAXXSBMfkDOFDqtN6C9HQ6AAY=
=xdyA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0b2a8fcf-cbba-44ff-a7e6-4c6af80b22d5',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9GjIGV24YQeNiYmgLRTdZflAdIzRK4QNylnSLlz9Bxd6G
oN4iqnIvANtOFZs1TMRCHdjNq5M57gO3ZdQCDkEfOq1lH5lQj0TNt5RsRPSbOJE9
NKHfsAoCm5sB+9wLeK0itC6HKq6IWta0OHvZEp0xmr88XRqZLCfok3x7vxxZ2t75
bZtJXuhq1HAjSYaNjI46FDSBzTuh57U99jnKr5tQzPOGjHe+aSXHF8HbIHbWaTGT
dQtCT0Sehs3C2RBbE8bNTOGnBSk/FH/IabZdODcaJ9Q6fnGUK7gldrYKVOhH6z0U
sk3RyUHiwTv+Ncm38ppeVQU8/XVGqEb8+88RkfpzTzXi2OCoWEviT6B98kcZ6xOk
0MvIMLifL+TkRcFPT27vW5yiR1rTrVwgPPi5b7jU65uQcMXwkerCgyC8I5tA3byv
osAJMhsDuKTeNfbVyS1elfU0aOcfJcxKEZJH1nhPIEzZYNiAgaXOu+rDmEn+2cdd
xhU/OF7e0iqcMqvBnEKRTmcxJ5eHZ0rgBNR3k1YKvscwoKQu1EAwYjCNec9gfedt
BrYABuHKJ/Bd0msttGrFHM/D9y1ylyyKz5Sss/EY9cSjIrsuPeloy7lUP+penPGN
Xm3g96HckWtYjsqaOx2i35KzJk2chgcgXUEFmO8d1m2cqKXlOB05c0gjc0wm3JXS
QQEAf2WGxru0S3flkcvKRoRuAaYkczQ25XEi4ytyCKYfKyBH4eZxtOX5GjVeMU8L
EdyiEuLCKlXVQCsoyoxajWdM
=CG2w
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0b96b642-7970-4351-a20f-a8cbe4914797',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//X0DbbrucblvppaoO/LYuFptBFsMZVrHQgKvcWB9+NN8E
cIWg/3PhrpGw7KLodHA99KU9rXXyyBWeCO4vcmcSTeYHiycGu3ohGj4FhrHudurg
/m8H4AumkFIR+EodUYloqaadJlHhK75f/EIXRS9/HEO2MloiTZ8OPEUiplIfD9xA
z0P9MQ1v0x7JtFOYiHvIGtv9tXcRm7jNgXw3NV9bLDINv8q8kkOKrh8ZhLywHtRe
rerq01MNCiTzTZXdOpSYzYZsTngw1E0fhfA49Ja3g+A42kbM7QSRZ3DyHTIqRt6r
RFFJb9g+bxwavEj35MjkX6VOvpJ0Z//fqJvlRxUoGsee8/2Fbuza5iZQdPhWfRJE
1caIQ0rzaMUiz/pq0NRSWn+I0eDnDx4Y3loUAwDyc+XJrRUO/9ntjDIuYF7sHsSm
2GAcP/9qWSOaM08ac7QJpuLg75XVXYQFEdSSiaJx3NewClXUxHdPW9QVQlce8RP6
BI80D7ci64/yq5YlLTEGlnanPeiFuppJzzr08PIKPsUm+q/cS/5vMtROHonCBmCX
JY6mlZ7ZYHA0KRvCKM/twYy+qYPaBzmY1PF14Q+UsXiT2T3xHocWNuoyiis3Kwo6
Rs1t/rhonRr0Yhmzz9pW/R6TGIgzKxcEE5Hj43/XplCjIoPC1fOmXe9ejXfoogjS
QQFoEuTlIC+TDwThJ+HVhD387ZAMXQJBb7pvsKrz8NwPASKdcqKxZsOWceMc6SHw
iXFEm07Qs78TpwULfQNFLgV0
=07/l
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0ee1c723-9c37-4439-aede-8beb1982aa8d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAlEpgwA2VJFRJP81FTeaWN5ppcLagDnavgApxeLkUXC9O
9bZV8s+qGaYB3Hgabx6xnLqFA792Y4f8lea37HgWvQvnquLQ3up+X9e68GBEAGu3
wbrwzJbTSTJ3rpUU8woC3lcR13LTHyBiATbp5pRx1Gt0/Io5D5EIQlZ3Z16F1vqW
acS6fZgJUvl+RMwUrzlot98jfNV3t7DGgPOA7iZMwtO5ofb0xwwZsIAYZURP0iUs
WTljhv4suHygd5ItsZYQcgInNNjBotzjSip0xIV4bKeUNOEeNdPBMlu9mMeFJyY1
UuZjZ8Rdy0gclRw5TLN1jqe2eCL5OmE19HakUXEHXJg0eA0g+vj4tjwzn5Vl8Xys
1W7Y+POdMBCY3i1Abvankm+vOPTLaFS9/MyobyWW9SXTeju+t/tJ+4z4f6lcb2Rq
l/grqToWpcGFgccBsZAwsH1kHSXm3qW89Gx9C93IgZRyWVfI4GQ2XSbWoA78gZHI
FDUZFr+yL6Szf3yRr+VeBHGd6FGcFgi7r6bD6ncDGuvwgTMaTXXqXsTwqvGZd1jl
z3n1AJR69p2RGiyAUoxo3cbQK1T3f2wVZUlbtqsm+Be5sNcfyRmGOuAi/BPx372L
GwmIcyEnoWX1yF7Y+1D+F0VEZzI3tYA6YrRouZZdkEaSymeWzJ4XpHlKJhFKdtXS
QwE92SG3cDPbOFUMxEEPwwS+LDHetSYOM6VcYwmmwIQB6/3eTz3oPpj0FuHIcZTT
NgN3LG91OqDaez8v4Q4DX991Dxk=
=jWqf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1061a2ab-3b14-4ec0-a431-c5a8f6805c73',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8DgwLnb91KxXs45rvVCbZQ1xDuGLwBtY8eb7G1oOjcgur
F3f35RNnqHvhQs4F07u6eltkpAvrMAsQBQGh9/OhuBISba/oIDZsrMAb8waq/VZD
wNzlkNexSklI2jvcJZ81R2HqyCVgw6HnOyUIi++P90Dh3aCG3Zo0Roqr8p0cPsoF
6gyM48XsnfWSjXdFiXNeBGiN5Ks8QnKAhv1v+VLNgKvc7MlVrjoIdzSEuFCho5+N
IrEsxktCAWVGjvxQz0BUQndFcAtv612voJULBvyNlhSftWxmgzmHimr+zc5vcF7m
jzlu2/62NsjpQfGRqFLfCdkKr+mZhhlVlA9M9Q+HDlZ5kO8f+74WJehPhCz9ARh6
3ER1Kfjc7pS1tdMuKuaPzke4MhXYBYCvtlp5GC1l7ueAt04BgYCsGE7GOadqQjMf
bsmTgZ7RiIgT3yA73GLEu4CZsaTahlLw30HCo38zwjy+Hlb/64GB6tyB32006VMf
rxlCOINIgYGGcg2DD6xxbJmIEiURBFODv+a9Rt2TI3DjQJUm7Z0NSyfafmJCV3xt
ihe6xSXIfN3vd/6gg+GTdCaNi1kdSvzvR9A0uvOEzyxmFMltncSwkE71b3/V5Tb7
4en/gis353I8NsPA17K5ey8k1/6qtN2jGghfCoG4fnNq92Gjroaz6t/wt2gFwBvS
QgF6iENA+g1I67xceRa7PGrHa22fZqLw2/ohDTcGMm2/apmkFqZEKBOxUh8iNupP
hsuti3q2j0jHdX76c/DPM/V4Aw==
=ZyRX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '16c98f42-f61b-4b32-ae93-ccb2ad226fd0',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RLYPz+pIVy3WmiB0aEVlKmYBZLHchi5STq64riYlQI8g
OtPCKJRLdmbsF4Agt+8lS/Ccp6rbi6ZYygEetUZXpOpjDt8nRBDLtXn9S5AVAiP/
ft+tSYKhxQ1N82Ia+RU5K1tDaKV6UJnuV9ET2iUFWcoYPU+4q9Mua/3WQMMaN5AD
6Vd6rwMQyKnO0TQJeFL2AjIP70uWPKp9zgqCcAYmpnzMZVIMfE0IPrRenjNKjbzb
Q7A+ICA/oIt2YENcldMW+LOanErLs9uzp+IC3+/9Pg9d4POFvU387m3Az2cNikvg
gJ5YxbKGPNMEJaMF9J3Hu0vdulzAaMmX9DsIEf/AyVpRT43X1P3OYL4HzvHtIMDO
uEeCRDErRcWfwt2k2tFSU0vwza41heNVvMWu7H00BzLoR6crysjZ9LBoLBNTcWph
BGYe8iHCQgd6MFsgNZOIUPg1JZGs5PKwJxA8Jn1tgxsYsyKIlXrsHhIH05jBVrXL
CIoD8gqXTPIfN84t+Lm43mbmyTRi+3kppSRnRsfS/Z2imrT6EkwQXSknBlFhYI9k
qliz/LXCtepjFvWcRWbMI6nBJwE1ecZN9PDWRnez+qyy8t0GBrRMsMoJGBIUjohv
bauLu0Spwy4QL9QmKFmowTQrS3Ac8v5kudbkY8Ch8J0S6ML4tnjfjxMFxbKgxP/S
QQEWO6Qg8fH0E45LTrr7C29bbVM4UhvX99vPSXeINNzx0016m+i4dydX9vagArXQ
x1HPStfbkTtNvLDUYTXa5oiO
=Kgo+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '184cef27-195a-457d-a094-5da6c56439fe',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//cAXA9ZXBhH3BqmrhvSa4pR6RKaPYzyp5JFZfDZygv6TL
AR32jKIBt6g6Gn4pddvvoBh1rV+rycId7JVvVs1pKXL8dC0JfjjESbBgnvSgfLGn
pjcSww+6VhrBcHVbO+A/RYeHQ4OBXWIC5Mcprpsk3593g7rfuy/+4KCwN3FfWOUU
SWWUTP8+NPFKt1konlhERpPolDtiCGCaw/V/bxIiM4ylWmdUtpvsbJjwUhvMBPdW
Ttf2Z13ZFCSiJ3elfA9OIffTe84OKLyQPFtp1avhiw7RVPS9mWlXEmQxcRaX8NHp
Jj0dBXh7gdhKnOA3uOxPyYkHO2pndTNDFPPBPY0rrg0459aBbFXQR42hGmffmC+6
Ocwk2V8H8XpE619jdR7569RkCkmIZl3FpNsixUIgm7O7mj42sXM5W7gcQRUUs0Rl
YabADmYYSw6Mb6fWqIN00QpriwA0xRvKT0GFY640sm95cuyWsSxMHK2fpK68OrD6
0LD+uuA8W+yaw63H9TC9tJHP1drqquc0O2S2sdvkW86fSz+PfKtZhtH8Nj2Xuogp
avqfAIZP8C+tblWAgnf4qdoBvIp/eHxxhjhOxh+mK+DxK+TdDaJmXOswpWLvle1Z
V4cFZINoUsiHIxgBGe0d17wRLwIJruMz0wfawRF/ths7EnAp07cs86IKnDqLDs/S
QwHwPhtSf+7PgP+fKn49HwIa0fPJQP9TL6FnW/tTObX11uwcoC72we7mr8ujlz1M
4sS1NmAUtSjk63Sf1eQDrh4rtgA=
=IwrD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1d4f0264-ffda-4417-a885-cf3e6a7d76e5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//eEGbqv1U1oWZk+PRQlTuKb2qDLZsF+45bbeJ9AseGqvT
20Q4MHpP0NLN+jubhrqSXcTOFV5bhBuHZ1q8QnZcTaghXe+SCy8Gt0MckEdchyYA
6j9JRjNGi5hcrE7uO9DPVj5lFy2FMvGJMVZGpOrDBOBm/fenQd/nRsuj/RmsDQAd
3x5DeRExIw+D8H8gJBfPYJRe8j2xA8k/tnNdGbUBHH7NNuruvN8ajYI1nJ5rjpNX
qteAhECpiah6cZ8gTEMGBR38C028+b0Tz8h0+zyOANWQ0ZP+qbQU2SSv3pnu4dNo
PiB65ZnX/t8MraP/nnadLToznCvt+bIbSa3XYW8U0rgbCZspaAXJxr0XZGGP58uU
G0xL+o+Tgp+ICbZqYBsuejSacYCE0JnCwXWK5mKbe/ot+ycAWqILnw/ucYEoPGTN
TkU1CY58jek/EdQI0/T6BmL7ktAeOlH7c5azGTgN4wJKwRFLJcW1Zp70ja5TfxE7
wA1lfF4/8rrnUyKiAXuLWIdczA50OiNFjC940kp7cUaXq5V0Gv0pFgJluPYOiWNk
1p9TK4x/zYInOoR8UTuYqgLEvXDRVYLmPtM/QhFvXJLHmbmbpiYCIz5LyNma+hSi
4EpOgiURep1KVaywmRrFKqI4vUD5aZmQWvQinJNreAbukEB1I3sU3UBMcd46cnHS
QQFeEx8OrfGsbSP4UvyJ4kV6mhC0GakrfRgOPGZrS3anlnxVYG/NwvCNoCtLOiOv
YPrStK/Em0HnuilG6eDUqeSc
=E307
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1d5c87dc-c30a-4ba3-acf3-0ba17c33ebaf',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XSTry/0PdG5qs6Zjh0L0lCutos5tfs3zzjfjvDqETM1h
ZfKmvz+HnQazJbjMkF/nbSzbORy10uTx/+jKBBsJnY1i8cn/ZkRQOqfAHqOd6H0A
orkef0jGHXw/LULedD+E4newW+SWitCVrzBwlLaAYUgsP/dlzQON396A7uZlEw7y
DcZmxFnfdaX4eRJVvdKUK5fwYEd+ofl1y3LlPs1AoTeS7WJ25Tml2RGVfpbuP8yJ
HpjbFMJ8e5hUfKdFAfZhRRK8CXKVsS/PcZkiT1ljQhqUpwfZ7s5x6cX/nM7gZ6Mp
goSaEmaQdlPpwJzcfdnlUn3PINQPsO0Kaw9Q9icBTtJ2pAnllXfXdYN2JBrNAgqo
+Dq2JJOba+2qvvhgEldFXwmc0d7TXT+nxDPKwILLkyxka9HbHx3Cp5p/kWCnYHYk
KStFvxZGBdAfusao8mJbelR8zYZMK1nzR4Zs5P07GoccgzJr9pZkW3+zzFts9kPc
3eebh0rWe9AHhr8hwGvcNrNvPJj09IQ0ysti82OcLjey7NU/02F8DMfnNbPX9F+O
pKtn/yc8/CxRQ+7pLwpGwEasfofVWBaFos+tmgJcNooUEnIYh58V4VqQpvo/MeAb
FTAL27qgxhPCAxE/FHOAzXu7R+pTk0Sn79e1aKfDSgTyeS0BBrqSBEVKS9Pg91XS
QwFRnMaNirISpgGLZlEPHe7ljRr0laMCSSzFZmt/tnQNeWevFHIEx/bvCJQN71ES
t8uV5dCrDKaf6Z4nqB+2LO0nack=
=B/os
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '21d3b541-7014-4724-a266-b0bbe7c03a34',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/+LKiiNpypJ7Gntd4BGztfsbc+5ArOWLUjU2CSjntbBbMf
7AsPZAJA5Mwr9UzqUU875XnJTth/z/HcEtyMBYxAyD0OU2L7Luek3suR+QCC7o7u
KbtJ/SOKW0kzpfV6CrIt1n7P0JxJyXgGeEHwlNMKgdOrAIWfs3kQKjDxfLb11Amy
LUzgcc/KK/gVhqNK66H/UIiLYMUVweMl/PLm/IJ1oZd0fkB1XIbVczxYJWN9hKbB
GdEsGiGConaXlO2B3lVubSFEWW4eB2FRKj5mnmj8BcdX+15HN3jBjtsUUmQntVUc
WmHPEPQfvDYseyasFcjq6Sywv/gvsFxVbkwOWzeVIHIxnci09ChQJ/sQsaZw9dMD
YwZO+p3jGDCguzWoT7EgRgp9gjrjWpJsyREjuptjnNft3ABi+qMuIn5tuwRSDAhl
DWWoGkZpRJyFglr7SE0AEupwqWwEUfH7OCRXux0BlCBZ8bYHl8sUpI8nOxqm17cw
1wE+XsSw6kR5szGo6MYLxiVwfuCdRhQfVHS4X78aesH/As/QEp1dvMuxYLSAnhC/
J3zmTO1+FynIMFRIMtNrWeGEyPb5Lr9v/E/QDX/I/jK1zv4qpa4SBduiDFqA1Rpv
UVFdmhS7yVlf7EgawV5U5wJ0VGYODGjcxna5kTWXKT16LM5f/4xaLMZXfq7miL7S
RAGE2ywrvL83t7o/G9Ytm7xnrkgnWtjo2Z2ABO5ItmTJsTJ+R/KUQALkYsn3b6wx
zrer3JNS1bSWN5q+EMXHx+PtiUar
=32b4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '26bdf665-8cab-4776-aadb-d699aff044e3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+IYK5TvOZKerNwY+X3ruZyZHON8ubj/oIPZwhehi1K0PM
RyWBgghlYwcMDHrsIEqYsi+iYAurW06256WX47rmLECpjt+Veac09txamCZ11BRV
fOknuU0ctPxLafZhc+YO0+jeCf/KXfF6XCZfx90WVSR4r3+/XZrpxecg+h+QJPiA
6vLBX4s0yoJGEN9yqCsra3Wr55udnYsP8ywqdiFtGcsmpaO6KT8neGZbS0ZpeFle
8oxKVIWfRa206uTFXzbzlAlnYP1IC2lfGmPFLsMj1QXLh1D8lGF3bi+8fzY2FGyr
sz8Sg1ChM6oS5NGq+2ga4oMmYq1RuHYldUiVo/EPi2EwC6Nud2CaI8Oqv7LDu5FJ
qSYKRTTdDY12HLu23nGuS8C+i3OhZpwde4ZNuY8tRtV5QeQNyL50N3no1ES8cioO
BWnW3NdMbAsxSNgS4SIuwccqpUTU0fe55lnVztUvIbdMav9YlBV519C9Szh5OXBM
Nj0bH00+sr3FkLsVCmcIw8zGcQAe7WtYnax5zZHbUOeuVnTT9ndn+Imdbwb2Ov92
/2jC02Lwho8K08WiGL1E/gA+R6iEChbOjt1/jL+ojN2baTRj2xxcb4BvhUvRY37R
gzWJB1nB2X/eTrmYYOTMezViJuiUy9acs9bvwh5MDrwUEJ/AzcWgDf4MHa7w+tzS
QwHeQcvjP7UqEK4EcGAdpqbHUT2Xl5gMDE6vCnoiAH109IpDokTBdslgWr3NGK8X
91oV2O7JOw1aTxqo5q5qK3W5OHs=
=9vvQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '26fa5ab8-5f93-4429-aa1e-668a850c79c8',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//f91MAuWH5GYWVFdAyKgrpOoHMf6QGx2nxc8irwJ8VdLv
LPFvUO35w1XkSBjbDFJOj6n+lr7x6LK6yujaWatvYR4MVhZaqJSlhRlEmspqRLBL
yiAafpS85Z9YIEkbcOVwm1eUwAdA9iPwzrtExwppqDw8kRPHFMgw0CPy/BoWF0kk
hjetriyap+DQTiR2CNBYwMuDWhxhJBCu7chms/x7ZNbWREaJWkHvEuyTsboZZ0vq
vDB9i5dYwy8DbtHI8tlP2KGRzPFIgiVUfOMZ0FEiVG2bNFI9p+F4gR/w/KlgKGp/
QvsmP9lqZamir3aJ70gjSHGW8hzs1p9XzkW/HxU4AFjYoYo5yxnFK+PYa7HTJbYc
OKRUNwr8LDvWOP7Oqf/O34Wf8PKysc/9U8hNgLlnvoqA+8qj9UN1TQQwoUZntAi7
oo/G8cWRCHsO0A7YWAsiPwwAP5uS6deVZzM2zxWYMIJDwErMHk+ji62Ip2ONjBih
H/0rtFM5TDXP1no77BQFB9miCz2whtqFjkbkzbHJcbBLOHolrMnjYRBds+LzsARE
KX4kEHorPpmhtLCp4qwU04DdBAX1VyNJfAodD2dLLyO49cQW3/DnhMOrokQMpvn0
ZuSejjqoB1mHIcNjHudVzQuws1GRIQBzkw6V6iQG7i+9PeX4Bsl/O9qchL3seqDS
PQHv70dcdiUrOGGoVuKB0maaICetuWerhVrEXzi7dyq+iyaeyl+hWTKWUgbDSQGj
IS2h8HF4eTxmuf8X0gs=
=2jhS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2a038051-3b62-4025-a0f0-1b5581522881',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAv/1w/DPvoLvHypER6/3Mk8lrW7bnb23kA6aF90Tyo62/
37z4cEOv2x1VROqnXvKfKR0S6z9gQSkoA4fBv07uqXiZeiuU/6cJ/HT1htdQ86B3
h+hKdScuo+DYE2al694gwUDfgUKLe8NRiohe0BpPgSKFLFbtXCtecKRJhAtUMp5L
HpJaStf9hE2jtC92PBgqzre925sT1Fike8PSH0uYLhAyzOoGC54wkmwZkWsgPVWA
TKif7j7SDf3T4Xw4x5GGs7lwoXsDsAzrB8kVYelZJnjuEDH5omJA4EXgu8cDKuKM
WdC1PVonf/twVrtVb12ZeU4/mNF1TyVJg9Kk5SaHoWlMQtx+AQTaMsKNxWJ8GRme
r0NYlUhKJfEK2dYE9OjIFLIoGNU03JZdRVd45wsKD4ciGEKFB2bNt/f6TmdcP9Kz
IMmNkOiMHF+8yClO3nytKC451er+qI883DjOIlrdyU/4y7ATJtesXbDLONcZ26Ul
qDQuWNtr7BdaUBATyQc9w+McBIIfzmXf4lOdewG/zW8bqfqZFmup0PQqTSv9AfCj
6MTQck6B2QSJGwQsLBNgV/Gd28O8/Q+TsLuQ0DI1tqrmzBAOaDRcjqWdkc+8kZpQ
edwhjQ1jDHY9IfrAnHKkbDb+Xuwk8wX09NCjoIsb/AFr6NrT30bZXYQ/qzMJ7ZvS
PQEqjjKsRbhtaSnJ2yt2JpMS0CLMw2lPpr7UzNuQxi9SOkkg87Wym+Vf4ptoOj/G
OdwdTI5mVNzyfT3WW2U=
=+Ylk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2ab0a73d-17bb-45a9-ada2-f8153f46d0cf',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsciqX/O7UIyLiHCe9oo/chYAojOsZ9xaEANWpgJVy7Kp
c4A9Ed3oMDsIb4HnFHWbLYN0rHTivqX+JYCm6G/xwk/aCm2prUfxQ06Z0pZrdf21
Gva4gWNwsTmJSB0dhnoDObwsxvg9yQTaIvCWlKaNzeS0bAZlOYgOMFDGjQ7c6T90
bRzJZ7HM/bDEYNZHhgCEOP9iKv8TikzZFUNbXD6EwTiT2JOcWny1M7hqAWQf2zGh
wsWzUCi5M4WphTsTXMmN9gIX95FCV+R5maiyfv36jsnd8tYIz8bIMzz9VxqeqY/l
vZDy4l77S9I/lpBconXpPGiDq1RniL4qNgpIfP+RBgwdsYT3s02Uq085vKr+Agcy
j9csZMxNMQXiYbXtQDNbtB+djy2ayjH1TjRQFjwez7mleWBp9m1FMR9aQLXl1510
J75KiCq0U8MPCpZgiYbNcXwzWBr3FthY5HIRg66VN+MW9LX1jPT+f5xN/akY5zef
tZz1iptOX31XAg8pBHpYtlPa0P4ThRcfSesl3QmAUv7UHHqjnOXHyoee5ylY9UW9
KTrKiuD6nFNt6PURjiTtS3b3gty/PFGwS6IaxkfIQ4QC+Izgty+pSudir2tVG0hl
RjmFTT/o14Y3Z0fFKEExaz+hbZYLWFTjGQHMYYzHx44ZdsULUqP5X+7ENt35oUXS
QQGx8ZOjfi7F/q2XD3P3QXxGyH4V77p7HF8QDWVpMfKeZEz5IxWPJkBPQ1HvHDNG
OUoKByOQtL7fhjNuhFx9Rfup
=RX0K
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2d969942-d81b-4ab0-a07f-425bbeaae297',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//TIkg3xtBaXB3cfBBumpR/matZ7vEBYGmmu9VSPXqT7jK
pJFDNQK1YqwMDUyStO9BL/XZ4oV6maM3ISSGkXZYKWzRJZ4iW6t3I7CmXMDMoBYp
d2U+tt2Zcwo4LTIopTBPNVyV0ViVsqr+KCDzz5OqI622BOTREaQac47rDMGKtB+e
zzPZnzIK/hmxTnNRpEAsi56CB2c6PJFgMfPrAMQtJfReEQ4MnqxRksutsJ+1EMk8
fGku9WAiBTyIozVyNA/SaRVIRCIxzCmWlzT5Uhk1l0w8pvSouh1DlBn/oW6eFZaJ
6kxbSUTP9NQU6HoKIt8mkYu3Tghkr+W4dfF3w0+O8KHvaUl9uaDib8OOrVPl7Dei
RH7TsmpDuKm9VGTwkvuNBJi93I71DeAEDgN/0vCsSdhSh+urkO9TLibjJMdDYvEf
o9ukWoLVnD0U1C2ICOqbsrhBKe/bvyafVILVR6rRpDSxtZWP+h9Uybw76jDj1OaN
R6dZdkY6qkwfECct/UlvmSGqk2AZ52wfvM+Lkce6IB1ZjHn8E629ruJFF4B91GQA
+0ptqvH0STKpVIHNMq5Cp9SnbYVF1dZ9IHHr1fVFExjdr7RuQDRX/fnriBJAoGx+
/DDm06RV7r6EBsqjswrNrTwuxx8gGZvGsOf8ZwRcLnaFkpHu9SKd9o9OzpBVVRDS
QgFUeLfLUv/bgnQj5m3Q6pO+3jYrzolm4cl/+SwrUmRPfIlOembFn0nlelw86klz
lGGrquL6BgtKgB2CkLr75NKXUg==
=eGZR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2e4fb70d-e87e-46ec-aa28-f5a039c9780e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAxNw3A7oeW1rWbx2AzVd2chiAiuO7kCWOmOOFqRXqeaxU
+DoJIytH6RTSbCD4b2BY77eoDuq2sknhhWstfOVjOGYGBQYzSIpG3UKUuqq7B4ar
k3uZ48SSSG4E+JL4dVSzcmEwufqj1c8XQe/pG/+L4FfqtgfXX+vz+L7Sc5dWpvUt
5xonBHbNihIa5rik4r3Vx5MAsfjbs786YFQPw2StWgXeNpUvUmdawc2OPCs/hPkp
ddW4bguzFizxk7Pp9gv8EU+b29GfomGtRNRU9BTP+5P4c8CJlVYYNhsQE4oqT7/P
YDl+9+579zRldUfs5VBMslIDvPCkECT0PiMfI25bfN4C+tNn3TQZgeXbh2TsEZ8j
LaSaHPPJ5GFhlohMFOsEezRg5T32776HeAbmqL0+oFMuuULeF7F0bhkrGtJhRtXk
OcrGCOIu4R6V890ADfNxUNIq+hcz/zFPtaa28hoH3cNkZYpIcjd6BJkg1mbjgrBp
cVy4gX/F2pOi9U3C7FitUt0+ZglcWaCF023uuXm//fkwLj4UfJ2lVgval9X9cJth
+aCHGiPKNHwfJyMnCk+e77Ov1JI7BhyndnLH9YizQtVLX8gvRFcpjWsI8pvxS3P6
RuCusygavNBEHhAWe5hnvUHrBnJW9yTF7mcGfjFt2Db2nY4mK50VcsaKQZOCubHS
QQH3T6jZCDdk/Toegh+684aApx3uPyAmryA2JMGerwTQI1fV2ybs8ZMX3v5ZW59k
alOIgpjkHzpHrUw6G/34IKDp
=3pxO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2e9303d9-e709-4a5b-a6d6-3bbdac6511db',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+Pf+ATh5ETvmKre0qFg+Bn+YdiSo9lguqW1rxwUh3Vd4d
/kSE3eRSo5Aa8ILZ2VR8XkXYYdzmbq+UVYcp9ZooBNkEggQq+EqNazw/GCWVFjhs
+JCkZZpS8fUAmijmrnxDgpdPWe0GqHhV2w59PlZODxoVEHnoGPHeKp9ZTE71QSco
rRs8eIn+Crs4IP2aPF12EkTVQCmToE8VjcS8BoRQhyGdriLRNoTAxMOJ0Qm7KYPj
QuVnFan8YUGWfUTrLLO+Nn3K2xadtMjyRmLqqzGCMN1+CZcsbnffq/uBC58ngB7Y
vMIxmfn1h2UGG4OFWzuD4d78DhmXB9pdfzfrhJiU/ByAdjz66rsFjHqPD2ESpOwD
E7fRyXOcbjdlCCmvpaZrkelkEOjkiqKA3QASmBTcaNUHy5LWAoCDlibuiV6et2Py
7YdBTYVEPj1QSVxkk6bhqEKAoruLp38CBIvntGPCVTAg5MkuVyZr7a/J9DKr+e5w
PFbKWRc6B+f0QRTklmGL8KGYzou+gWHpRgVzRlVyBusGBDmMAJHbYZWB5GoCV/D7
heXr7h8AqnZ/XIuM5P02LRLQ1JhkoB6nJwe/l7ylPff6EyEAtdi8xPoO8U1q6GNt
5h7tpuDZssU2cwhldaANQr9LCpTisOhkzS3kfb6c/pX8ne1nG4NC7K3uSg5vk/jS
QAGs5KLzxbmcTEvvzkepM5Nc0dZQcM7xq5ax5eXMQoWOWYswFhtn28KaeMJ3RsK3
Hr+NBea3V5/sjkMDDboTwjg=
=ZntL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '41bdd7f9-b970-42c8-a63b-c722822ba8a3',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/Y5UFuXvkn6MHavIJNFdcb3VIrXRS7gcDhuf9jd0z0Lyd
P2wrldiQrgxT0OPfKVrTzs+WPq6lH07nhRXPhFFZSVPkkfjAJDCMNmOkBKx7nklU
hoa4U0vreuSwXPq9vIu9S86hmc3vtHywLJnhUuLcSKeNAVf2GyctGfZAaVfw6AlR
yPLsszbe0Mx4TKwD/3DCJMdffyAu4r9mm2H2UH4dhR5DwBhTLeUp5+akBm5WbJjd
LfFClVExZt9nZFd6cnSFFLh4nGoyENH0RL0O7pV9qpdYTe4Sz2T0ObqJxCajCDzD
h9aY79c9qOMwVjCc0DUTPn+bPg2x2oqARDRJwddnztJCAcEQJwP7HJiSJF8xyKWH
SVLsPbPYlGwmDwQ2rv7XmNAAxHWr7nKxDgLDJlg8qLrwLQuwtcmD7Xesw5aETqwV
aH5w
=gNcf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '42a10da5-f1c5-4de3-af12-5e335eda23e9',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgArIF5M8dW9ISeQv81/45AVXL8LVLYAQ2NlMHRNE+Kna6F
KWBW9nEoCPAvLlott5MFeG1IRrs1cU00yA8YpjLZC/S4uFishx8ftMDSraiimmbs
na4Fq+s9ByWQr8X7n6rQkG5+Gg47iaNbr2zgKRQMxZ7f+FTRfX68zZDRi5+eKfqX
CUEhjMLt56DnPNbsGZKCME7Sqx/yZZXM9futS99yev1RF9OQSTRy3UTSD+tMzVjd
wYnGbJvPMivyRMxk9pEq3+OXkEn//WGzS9NE8L1OehtAqFHP9bGbDWsFeHT9fj3u
0Wlq2TScrS11silkETBmyusfx+1JloZPRT67VQ0vV9JDAfJxOgmTX5UfFkrSMBIr
6HALJji86ag2D26WJrYO7kgYSu9635/w2J5A9OEnuN2cQBUBNyd0GkI1nCyQxIpw
1XYIBA==
=V5fl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4358e861-24f0-4b7a-a337-d8aed3ed3896',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+OcV2iXaRmZEjnSPCAgTtLov8d75W9YDjCCC1Sl9DiNUO
hN9Ywy8oGtTDZTW3MYdwnlEZgBz4A5U/nOnZiQoxAlZ5WkcyfSg/Kd4vMMya34Kb
Jw1h87IMumkEx+r0VMG0lOhQ/BxHuirbTIxiM+9J4Gx6YEyEl8uajD0lQnunYInI
x68bpblY6FG1Fftm4EFtWjjk+gO15Ce59Py6aC4Cko513IVIVIz+i9Hdlqlozb9G
MjYuiNCxeCja2UGsRIn8zbpH73UWeaR5TgHbxgmdaki0g9vYAD6CssM2icHJdrAh
fjAvt5JQCl/bSV8W+vGygYhgR6nlfTyOwp7PXVQwPNI9Ac0wNEQDYZsN3Enz24NK
LV9mkbZxiDtuRNbJhffyZXiYxrwYipnoDWQnAwirLUfhUxEot8Iz+NU34tXzbw==
=B+0R
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '470f1e5d-4cb7-4215-a2cb-50d925e5adab',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAqVRtN9+s7UFbQSU4+0v2DEJ1h5XogWAt0gBaHSEIzSlM
abo2jJThuEtt4p02cy4ga/4eMwgm8KeaGg/G/yCBjvH9E9Us5n3fcJJZBB9a8QG5
4aGVFj+m6EFxBkgqSNpxWOrSnSigprbOzPy8v/f67ZOiqYtOm/wl6eq+ojD+Psc5
f/bz4iaWhGTpWwTNuXPjqf1jMshBGR05iBMY6JHbMExYRCM2XWdEVUQzf6I66iOj
39bkwXTGhzaSUzCA/fEVNNWcVGrsvAWbBnla1FaifbwEm0pxiPqgpWYNxV21bgo9
t7Dim1P9nQHHokplaixiMYUsmTwfOnS+YiLWf1/QnNJBAW7hfP3WtUcFZ4ib7xjX
HKHIVNm82Xiin2A348pbc4ONqQbYPV0N4hCyck/e8wA63BHrjvuo99lthIzhEQT2
9gk=
=v0Xt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '48be7ce6-e36b-4c2e-ac67-a53a6da5f017',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAznhwqevb0g2xf5+qbUrBp+VgEnxjtirOfxjF9p6OhNxb
whB5UzJkAN3vWFfTB5At3tT3oXC+FcTNLjWda2VqLJihfiQAVFiILDHxWRDwqhVl
2xqFTZfSv234s8l5SEVEzkU1p40IGrulpH6rFhjR2eBfK+xdZgRpj2XsDOoI01xp
gRwszSTFh9eWIqggAJMvRxrijw+Kdl7ml21hTZzzfOLOw2yvyHvgPGTYZaQ+xVO5
0O+SDwC+KYCareZmQWvaRcGAjMs0sM3MLyo47grulUiqvLU9+schVc24u9oFA8+x
hn8KT9UoJVCDSrqmyslqiKfC9HmH1BqnD/+0NKVeur2GeE88c5N+brHztmvY4pol
SAY7zLXST0jqRUM1gj+3+yD+z4lB/3LJzNIlTdpchEFvypGr4am5aLiBRqY7wU7m
OFzCEnRNXXrJMlR5duB4e67Q+UGD09DTx8abP3lvvLBXMt1HyLtnthGhkDQOCMwq
1ayGz7EEI2WifyWBUzOtjeF0TfAl2ykn76XKkEZq+YrQTCBzMAuhIK50Ec1q2cOP
rmMBzgyvqt3plLOPEKUrY1qQysqS8NH/Xwhe/nwjIxyFtkFzONVhd9vsGc9DM4LD
2BhcACO7m/4HsnDEbWVi5QTuXaLgZ3yggH6dusi6KntaSy8A6YgDA0rslg/nqYbS
QAEdt7b5mYo5bl4G5gSeQfOMATq3JLZ/EH+CD0CIAPoS2C1yDKhKnE9jTEAVMQAp
6HSk3nPMlqnDzTQk7VknTsg=
=ziks
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4bff1199-0a3d-4073-ab66-27ac3b19d1ba',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//dzzF243C4e06FDEKRS1INViUtRBuGw4kxXgCrIN58GXI
6U1xpYU6PiBhVBHiZ6oZCcfujX/nSJbMA+/uYnTcHniZQNKoW78PnjjDtOzuBLze
ZpKkUFEvQoq3jD4i9lqwDHhqRRzhLHxRAzQKRrh+RV+XH+SyCDX9zZt34qPNQmwF
lVBkk8SY1kvVBvEMyOE2dTGSuCfAOfE0PRI+ip86X2/BsMYe/6yV/FOoVHLGEDZ1
CxBS9qW0a2V6WCS+YujyL33juQCSCYe8SdeoSd6WW4sM59RWeIqwx9mNC0wbn4Ky
dGBjAtZ2tw2tnSKah5frQnu2TTANrHCIDxxEHR2Ltadi4ZA/TDRhZfjnvzgJHxwr
t+vw6V9fEZ1jZPwnHukbSBr++ntnI0jf9BUN5IB1UF6Gv/nbeA8D73z6GfXk//WX
4wPjDkHv0++7WmTijCSR7ZT0DV169HPGYmelFIJo9q/Tjoq50m/S5bIOEDkmQGzg
pYIK1iEFaeKQ9jOrXY3mbEm+YNnacZkKaFWYdM3VPTzuUh0EzaITQzht8hTp2d22
P2E9Xw3352Rjil082/nr0VTiIKO1RZlEfI8I2MjARJYJcl3uC6hwr+VY2t6e/O6N
owIRUavYiGQEBBN8mmtakQytZNu7LP+7WqCgJHWgVZ8yUCj9wCH6Nw8vDCnY/cHS
QAGm/F2DOq/I3U7G48Cxkxa68ZeyPJWQ0ImSblZN2A2VAWYz+yEu0ZG1B1MdZEvY
aPAZduZWn2iHdafFM6glr/0=
=4QyE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5235caa5-6fb6-4adf-a92d-74bd5e994b65',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9EBLalzh6t9ai1w+NPpXmsVsBE9UGcJkwlMTMq60xgNj2
qqBEzIQPzWpoIYj++OPkfP8NhSVbD6fi/u3L2CwPXXx4O5FtrH+8f6WX3jKDM3S6
aerdZJWgWxhT8St+A8zjZ5rvABM3to9PfsUU2s6HMHaWAzV7W2bvORIf9SWEPF3V
omV/9BrUqQ37yh/itJ0E+Ik2AF4tZHShntg66xoRpqJG6n5BXRzlhlVT7aaiDbet
842WJCqHqMy7Wa66HB9HeB/czlrDTA6zGztr3p45qDmoAc5cw47WCNdT4d1NAjFH
o22tH5rgRGLlVjaOkn5ylzzNf1JE4bfF8pd4neucVuIisrpWwqHa+KF5Z2wm4pqU
JetLIt1WlK+72fzFm1mZvjCxFJQ0RXsdhMEZfMnJqXuKJSXNT2rlA5pMecWtLVms
44tinrQuj2vvPAg8RwTtlyMub41BiJUs7b6YHa5DxQpweU7es021VpgZriIJVpn4
5SMJ2Qw4nsHTH3eUHlsqhFMgLsFazRwUxYEtBMk0rcH396LNdUqQVlD1bxd5ovNE
zBzVbtSmsyU5/D8ReGpocww52Pd9iNGNLhqAAq8DGu9wv4brifmM9lFhOvQljTTd
NI+odpZc1iW/p4Vr0i2kMTshx5LZ13Kybxe/kJKPVCrfFKBS/rofjyUJKbcKOQLS
QQFRNK8g/0ElAF+TCTePkpT9VGvPeEYU/4HRPgoETopb7zAltTR188Zha+11zOgB
bnFOpb9inXUi5drctwkGx+Hq
=Jwp4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '58f216d6-4425-4fab-a1ac-9a6c1c1c9e0f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//WaVZt76rOv0H6hH3X9T6rSNoEh8swPeJcD3QOcQk32tg
TgkHAUghNQAvEUvHsa8ajRmLIUejCHa2O2AHYfKdOhCZfygVt1T6sSWnt5+pYG8d
X6UGbluv0aeAb2+4JnzTfw46iuKU4Gt2zF/kwOfgcWKffdELeYSm+9J6RmdM7hO+
H4PJo6oRlxQCiNhEW1SLnJ/WoeZ80llBPg4ZYOoocJ+hTQLKTvxCjd+P0mvTnv0b
Dj7JXNAsJqUdw8mRRNQNL1KsqFKeSlUNVMqMR4Yqzjc7HStKi5UdOWBWo0M4VUuc
RLohfAzsg054oz8wuZt0/0hFLnA3ZzW52QZ9WPTcYghWbk0A+8/Bd76CDIWrDY9/
6SvKQGaUX6VM85yiBn4kLCrXsaVMB6Ir2DhLfaq5+2nPXWT5rze4mvvqVVu/VVNS
fd/KM2OJyeerftuTJfkgGtfvBQw9lpVBEQyyT24C0aOI8q4lDEJQ6310pIq8md+n
+HeH0zaeM/4mmxCBsQ4I3vb5xf34qJrxtjC5xMBWBft60xxsAKRTy6BkcLaddBNc
2Kij1oGbsJbl8eb1LSHQMwMxelcwTW1ADCZE4OOcAHxIlRRn0IIWnw4Iz3tpqIoD
GxUwVbzJ1cjo4QruVpuTnPgVFHPcKFj59Sat31zmfjXrQuqQJk5z7OB1f8Jo143S
QAHUcSm0+ULitEpXe0zdaOjjfF0q2KB1wJipB4IsEtQ86VWBj9PtdY0L7TH/MKWP
jgUmpIEf5UC3Vb7PGlXzEEk=
=36ZC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5d386df0-6700-47db-ae56-a846fbf07e89',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Wo05OeqHvCKG704O8O2zCbLyvhyHD8S2cxpseyktjBwv
cH6EMnzX5qG5XCkcZlYPNVNEQldiDT8Vzf6hbwu+ALIhkf00S64Beb78dCxFg4nw
TQwN5Oq8UUbBLe8h0tJDRE3ehqauvDmzJ8nSHbu7rV9m/7t9Hrc3In/pcSIGz/Rn
LiFM0W3llaF0UEsYzMgR0hygykVh9B/w/0nzqeCjWAI8XydTuSnkr5WInGo2Me/F
SSElbugubxd6HEPFDNpXVAvywiEwU40JpRqH+vQfm6Sgzk39Y11yZtlc2h52VJ4V
PfuP1PdGo5AdgJ5hBmxfta1ad0McTG9+3+/g7GQijqAtS5BVwHF1ztB02LHmk6MX
XjUvk3m3LBREDk3Ob9QXDTMjOK29zKgCvTbvQRMhTj2HdNb/XIhqgeq/k7pAVn0W
IdH4mFFshi0wTX7lE6f15oYslPXMataZdZfxpYOiZMXoC23nVPGZ1bXxYHRkyd45
xS4c9U0gd2aAB0UdR8wWjKnhukyeIQy9u8sCFgHPI0tzz+80tqSxJbxFP92katdU
HdzhU7FQmSUbPTqlFVWYkRPtOt3X7n2Iy1mz3HOna9L9h5/CFI42pJTVe7turNqV
/VGN8LdZbu7JeITCv4q9lrPwDgjxLlYQZPP3V13xXGp2zrYohI0liy9lWmkvPPjS
QAHWYOj2MVT70Y86jB/m1P1/K8arvSOnnOf59Tt5au9x/lUJ/CLteIFPX1VtUCHq
i8iGVpXL4jPy9Wl6sz4V+lY=
=VlxN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '65bce744-9415-4b7f-a090-c53833fb8947',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9GeKj6biPs3kuqryR0dF6ITY5k8ziRZ/NE29luf/3TU3r
V0L2kEyUP8M5/T5szCLKNqxyKbZLanaPYM3xryXhjh1R9W1Hmo5cgziFHMyGh2J7
ULyueL2D9ZZPNUOpiK8BQ0LA9iqRJymnOsjGeP3GolizCnq6GjrmoL2YA/MqoZjr
cvlaCY3lxzE8NuAp2T6mwjW5qPPedgz9xLX4C/RdE+X2WDzROPpJ1aQZPKgA8+lp
dz+NwWWoilZPXEvgJ/vbMoaC+TAJMKmAhCG4Yjq0EyWTFko7uJGF/D8F28TKZ/ve
oYVnQIxEisl40/aNIbxYFM14oZHWjhBjk2PGAfb/LEFPvjSVgtWilz3Nc8kj8P2P
VhxDVAhU6Pfn7romIBrd3p8c5jxDa0CVCPwFqBZtTXq2cvqJiWw0keRt17EAeRE6
EN0JhDar4Yd6V+uaibyuhU57PFxgwVUN+k9yYNztVgDC+oo48C8w8dq+qYIDsp4X
hrF2D7qfTVgpmTy5hTttmFjuHpSvs8KcGTSIsPNME0T6NvztwXhQmoLevmDxzfIs
NODzU3y1YQC5FzRuNuBjzf9HYiEo86Kq0CoEmFCAGWgEhYtV+4kwi+MGye2U6XEG
69zLsMEssSeCPRmsF233rH3S1CLIRpzp4yXckLB63Bojm53NLxokg6veiTQizlLS
PQHYJn0gkuEGXEXSR6BMUMyo9D6Xy6jWhZjEGuKOnr/W32pP4AfotQLefiebr7tL
7H5oUNlWsi+ltmgZp74=
=gQk8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6bdca29a-723c-4116-a191-73215da04b02',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/SGRqXtynSs43GCTKHGn0C+nOtJa3FSXWQPFKIkl1+6Fz
7Hd860uvSEZNAFPfxA0xGKcn7tbA2azEAFXc7JGH5TMwToFFh6pZ9oEejbQL0bRO
0fgSbbcE7q/BP56i5HD6JxXQ4tICYQtNWHmKTHq449h4ml+tqWGv/rwHWRRU8z7w
uwEiXFD3jatvlrNJXfmEeOMgx4M6LEDVHInIEndASWviJiGr+lLvWkWsh3Nf2KAG
BPVxE5m3doBeiNOx4caSX8NnFHvSu6Z0hkTVUxgomk+NR4GsiioOG8n37NsZ/uMF
mBxUoy1ROqJPR+d61uFAh01x5SqhETi9CgCJXyvzy9JDATuWTqETQJsDu5QPcQ2r
6AJJ1gC/6Fuen1NkOq4FKkbnpkeKWtZ2sZTSD+KGzdbRfnulL6CVMFL6Rt5JC6yj
kSHicQ==
=8Qft
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '73d7e5b9-d3a1-468b-a166-38141e14c19c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkglKy6X7IOTpS2/ziUD/lrw8bzKQUY9el7TwOpU1Duw/
1K+VK0cqWeDzjc0HgJiv+l1uGdNThtgcITWAU+736u+8Iu94DgMqOK4MEWjr/rr+
xW0xeJyUfOFA1Zcm+MySuO35nnDlQKanARjF0td508KLYphPC5m0VPvw/K4FUVjL
G0E7xxYonEA7nN9pmCo+gv6ycq2ZmOlpf60nDeQgQmcJBGDEqkv6i/EU0X7nTEu6
sgFLDLk68I2X9InEW/zUxRbr0yv5GtSYavXxKeEO2mgmT0n3MXJD0wgNZhYplcMt
bHgjGGXo9R4ZfjnaB7mgH11YFuut81/5p9Hje1c4Uqi6tQw97emdQQ7fi39jN8Ul
2Q9z2uD/yr08jgCgzpKMZcNtL3jZquAjlZREmTNeihMnogZBpm0OYnRlHSNoeXzu
plAYnhPHZp93Unec+g4H3jsQLZUoL0D6lQCrtpzyn8dZdBJA6kpdjEMrhK+bKvCL
iuwQnn30Zm/5ph59pGxckWHdd/fSw401WUZjrCROU5PgRWdqzQlFrks3KXnLe3lo
K1wZuZ0N8s/ZkzbtwdocMoYGFHYp2OQg9LkM/YuWWpqYNDx/IglTXLsrtpWPuvhr
A9fyZ8AGGZsZO9jWzLok9d0Ew0QAOHacPQLfwoRJlEcMtIdndctEHzN/Xu7tkSTS
QgHDIL68y/r9gwZ5KU2d8bFbzwUI3qL0N7sYYx/OIkRrhYiw2AliGqaOfkVMQp6u
X4oXGr+eNM6/uRnlBcIYVlluNA==
=IN6S
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '74d83c8a-6353-47bc-a45d-0f7969551b90',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAqlbEEx7gfOcWhOGUHZuiEPprY8sd71VY+jLv8VHSgAl6
g5W7yMPs6362fykWHH1+dzPxyPIdgBAbk6bpmo3uSVv29bv+45s93zwlmI/x5MwO
8Y2wp2SuNOBHnJEmZbNsXvCe1rso50poSgnHRIzGYMossOyxgOLv64iG4UJkfpiO
RUymb3D/cVyj6gtuRHtxZu3iut7m+DjD7FtJkf3BUOr7rvke42zJi93/ZOtO9b57
vJH8fx9aSRwTmhGz/4M62c9I3QJzP0CgQE2xRb7ebSApjPvaoSE5oPzpbsbqTwUu
8HfUMW5sFQlDjG6DidwRDkB0y66itaap5+G7M5exQdJAAUH3x3J1eUJZhkd4MKal
Q49/7ss0ZiASK0jOAovjM2+bUs04xpkBwcZZgnd0sn3556BAMUuttnvRfVXmucx5
Eg==
=SvGs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '77344d67-4fc0-4a98-ab8f-34e9c6992244',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+KK567QdWVI1wSgrTT4Sh1WSuC3fL7Ddw4+YegDSHDfRS
HSxDITO8A6i4/3WxFj7/Le74knZyJXB60dbig+HtC4dhJY+i//5YGLBTlOWhdQ0A
VGwqkxS8R8xKPizMI5wfnzCSxZDwJwlzg0K7wqOUrgIBsaK1PGO9dbUjIVkM9KwP
gZNcDrgpLPC0C+V2mxsDcItd0S+fmJy27RzqqV1biv/EX6uByVCP5PdMrvsewTww
474c/wtnHI0eMiaXEkNu7eJiK4mvQL1SPemv/4aPYyCiRnthsuuWlk9lGd+LGuZN
XTZvcEOzubsB+xQh/U4zr8AgbJVoeXDB2mDQjlPIQaV6CD1NLBU7I3GdWnVKW0Nt
L43uS+sr3NczxoCCgILKuWfFRbQ3JGhUQqW6my8S9lPVh3FnQXcc3qXsStZdqR82
9AIdcMraCyqdKKaYnq0Mol9PYG4ZOA0N9JzDgHtMbn51r+nf2Gl67+KhSmS76Jx1
1WfUP8x/QnoRlXeukjBDvmM0lPJPYKqLmIxVQUWD8ymBiKxwh74Zdrlqz1HXcw0E
YBJbP9sPbNCaHsfTudp3zCuALvDFsjbjoML4DMQkQy1UjDOAzZLeak76W+yiC13s
db7fUI3AXJMi8bW+JY/OFMEFNy55mp99SylzVGJYt9knr3wCQyMNm6Ka4GJTMPPS
QQGgNgq6d+Z1pBq7qD0bnKPIMBBCxQ9N9Iqxj+/GHU605iaO8cfbuvXZsRbWX2qF
q1edN1ghx+G3NfMJaAE7qTfx
=25q7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '784ed0e8-31bb-4334-a890-2eeffaa8efcc',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Pnlq4sX5OpxWoCkblwftw0urRSf2YlErz9fGBoDV3xyD
gW32n2PnHTBq+OsCrDygDqKV71LeCepD/kMVKm/5Qv2+s0IhP+y32e3z6niRNn2E
mS7DeXk3eD1UxXNUUws1Qyf27mEtXEZa6XjPDt3Baa3OMEfRkkyKhbIVVRXTJzmu
QICxdY+YKrZRBWpWk2aQEUPxyZo/N3CjhYI/BPaIqlNkYvzJbo0wOdiyHz6W0U+n
QVF0YxDQu9i3ARKy9Fv8uXUbQ4l3iD3B73Yqd+aCOcBZJleJ1oYHqt8K233C1f02
/Tgy8eQL9b5kuUai2HKrDa989VJEuGnxFIbWTUwMIJu/nzL/0Czs/OqHahPEdE4K
OgJ+/XEbcmXvOGn4Jivywy2BHO7N8o4CDjZtcyEJlsFK8kLt8f7kdkHrSKFz5Kjs
eA6urfYWrfaiHRRkEbYFVo4H56IbVpt+PL167Sgvba7XrIAxRhgz/nN3EXBs/Sl/
05wqQt79NBSSlm1O7rCFaWdSrvcOxeCTnyMPU9dm83vU2SiCT6UnR1kMGz6XndKO
pGRBLCcMY1tL+7x3p7YawI+xKT0RM0Uf0oiM+dBVtsIebYedcE9BVgkiRPbB8NW5
GGl5Z+2qN+w6yzLoeCu7aIK6be5JIWfBdLsfw8b7paSSejLxWA9bZeY7bvxF5ubS
QwFMfPTzYsnVZw27bJYk9zKTER8/ECsFwlms509w0mCQ9hVf9tqGSNxBxSIzM5Mh
VZjFjHIJoHkoU40gnDEVqirj8Bo=
=YBI7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7fa6cadf-1ff4-4c2b-a33e-21762e285b73',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf8DuMUWeA+vsUC2edVYUCyWCp2PUzVRG7ftXhz7/QLujp9
MrU1r4tyK8+B1BtvE/SOJR4hUMYtr0//g9h1nPSBObbNo8d+voIy4Xh674hz8LhX
L/YJ5vlWltX66uBAUmzVch7X+fJhr/s/UIeESU8jHRdyv8eVYq+l0BGT1p73d35g
FYl50IoI9kcsfoWxVRm1IBQDlQPqJVpcvdE1VIOO/MV9lvRTWiMmzgA3T7xbBow7
INdkJjlSMPgx9Eu+GaVoX9izSuJpSWMXeHYvpfvRNKnIzi/Qqh+Rb1bNwxHQMKJy
0zaRCgc3IA/lAV0GCjtDGXyI/QtHa/CtNd1TNLt1Y9JBAWUZOpRGVLcU9kcyU+dn
QZzUYtxRq20CZpPdW4FWVkYVNUQ4aWgu2vi79C8BKgVyqwERSwFYdNwUNK5CcT5E
t4g=
=bsuX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '82259631-893d-49b9-a3bc-c15f71766ec7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//T1b2NNn8izy8uSUze76KuviMKZPOYsiLCAMR56MbksNS
ArYBy6GnypJslyK+Kn5cJXZKAS/swG2a2jClsZezgwtLcKF9OHJy1oJK8gIVM2Dk
droJLw/OyrPNZxLoYyue9/3/ceTtNJETPdEy7MGJomE5Uh+ODGYgXegx2NOFDNWi
OCWuJIJJaqga6fA7AQooboszTR3CS7pHQtKFHVSZdo4Vf82/hu0adZn9BMkYdOLw
/FFqf0jROXIs9ufEid7An/ArR6+KrJe6KCk5RTSDU+KgHHcGJrf2D/cybcuV5DHd
FSimjP7+usLDrt+DzVCCTKIb6qj2e3YQXH5ykmEHqFEoPNYZ1kLhzP+ldrtP17Ek
+T0+o26PxWqHgCXKbGQxmAvyKLIc7KEiHTDWhXwGHlqHFElkT60VSgp8lvuOOSqB
N0S8AKfXL+zAZ7VgJc/2AC7lkD5idIMa0FIfL/Yn0wJJVTvZWUS9bDTBxKF/q7It
4edy7zMUICR9KX4eJ5VkUbh1bs2qp8F+TPyI9T58LNnqctePYKPQR6qmkyOag0oP
pjmgN4S5OqDEFoRVAxoe+dfqIYxPx/0fQm6zESbdZujwKn54rwDLk01ckd4XvnDb
DG/3X3f2qXJ83Gj9XdyprZfzhDI9phEgoaPGYGiMvvlKPIGE2KSpW1sB+4Y0muvS
QwFs9OauLp5dYb86LGMq5Xi0STPCkyWZbWhZraNjAcOESaUUtROc+pwZJMBpbYce
TIpKJWwDZpYWB2PDkxQeErCljbE=
=C0hS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '84aacd01-43fb-4060-a14a-b8a3c80a4244',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+ID39GVJxqVdYcc9EUy+B8jf1Yf8YQDbhgUEVHaNqt3Je
PfqlMsSQpiWdoyeX5AiXmcrTjTAKDICfBJILBwdMVkzMpFhnkL09kzmqJD7MWVfT
rlZ56WsEsUSRxlRjSomGEj8NsP73XTTQtCjUXwq3/4UOFKlriEwp0Onw9ujQOsGB
Lfb6+ZmThPcWd3e2Q/3LIBY2cVwcCoRMmkgK3iivob+/eCfVoeOmfET/OM7PFhIe
Ylu5M87TQoQbGWBENtCxfXTnrrxsu+rt7kBwCVgMIthi/hj/ulQZyCbZnjXNw9oH
xhgwCneJh8UMah2qE+M3U3xzHpfSJOAfV+7sBMckKVOkhdfofuLvA5h5K5efsUA1
5DqfUN8Mh+e0nzCGBUjLa88qtq0YHxplxWBUyYaMMp8jCxSKZvqz9esDChxoviSY
pKNvHqN68TPaBxEm1Dfqh1U5PxFml+nMnFuIlm5XKXUD46T9hRRD9Ri2Vl+RE4rA
coWkR0zjM1FiPxMZRePUl01+VpdbeF7Xl8aTgg0j/5BMNQSQturmqgy7OF4z+nOc
zROlhDXrPaBeEr0OgcB2aYO09JbzaI147dziaIIjKidRD6rHun/E+LlJpI3VxRmD
fZq2UT4x3k30Iov55yUzPeVrmCJeL6yaDBsTtF48QSWqG+Cy1/gFkHBG2dXYUhPS
QwFJS2ok4qe69MnlC3Cm2cMM0aIHlQYLdXk+rfYtktBo7t6vhVqekyx/CltVDut/
/jJdCHVBcIHwMAml//qaYfvUHy8=
=MbkN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '855bdb70-7e4d-44bf-aeac-0f8b458e0c13',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Ii5Ndzo58nFrzD/qBYnt7hO5AzXl9JVRnQ8U/+003r25
CEKtTH7r/wgLPyKJxCvl6lM7ooFFJmXoQxr2ZDsY/tJHdFKtZYNIPews+JHQVWyM
Lop6/WZzzUS+q9NfvzDOdivKMNjsLRmnlAmtbqVAXBGx6Sf8xKv+pjzq64S9pwZg
Id1NtzTSRKSiyVkrXU05E/uY4V53BBaoeZ6TE5WZ0toBqYyZ/ZkmDB20/ZDS5fK7
wepJhxmxHrgN1Kz/Y1aV3CbZHKHnZqORu2b6i48k+UXB/ExIjFZKxkZP9CW6RN5P
0NDQa//GtOSTmJ0Ywo7gX9gjjK+x8SzhJ6dNhacdpuRpwjDhHdk6CuR0BBAkbRTQ
PhKfNrslOinU0BUm7wjYXfuEF/6Bpa5v5I74BGp6DbsqQFQY3wbYQs22xoPubZNd
xrBUQ6rUxaxPfUtllMum5HUIQ58wKUWtyh6N4g8wNObMNzYT2m0do59Feiz9DiPb
m7JQYfk3suPD13afQ3w+FWSviuv856Bg+fXFfsobo3xgK9Y+vBuqNv3W2ROGlouT
xxNXRRnZc4g7I6niUqBD5/WbYNw1Jb4G+WsY/koDgqFhHRTVcIhHTmmT3X/jqaIS
LB0t563ahVGOpiwBQrpxaE3pWdA86sSOSGJ1IZeP3yUS1jj0bQVIQItOS4fOItfS
QQEOmhNDnGVLoqbp02cgAAXOUauS7xnCDVcMJm+A0NEd9jM9xdPNuqyJBXkjmiHk
uyRUwmEvbbgKih7Nbv/cNeFM
=Daug
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8ba93d9c-afd8-4d2f-a072-048a16e9f784',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//eK7+HIi472SX6D0BHd2qratzrwZS6KHhdsiEvfONC5vU
I/vBfAZ7sXH0TaqHsgAhtlSPr0kkEvrsixh984s+km13iyO2uM6NkwjucoBCyj7U
MpIDnoVFPYQ8lyDi0B9sNMhlR9P2JmBHHNrBGCaF7QPMn4P6JqjQ+Kb6iB3V8iyg
bDBJsp3kf+TbGZaC3SRRKE3GduYmiRHL5QWlzWowXb0Vv+yK37/goI3xp8rMGec3
El8honhIbwWulgoY6f8ChZR2CAQSgU25W50xeey7U45VrisKdjudnpvywm1I1/OV
Toua0sBDVv4NnN4QjCgDuzjAkJCISftcNmx6s2nSitFd57P6Z0jgh/aYQZ4F7BUA
xCT0Imf53qHknFpb0wCNeqz32ZHWxvHgUEICf4bky/IVOh5HH5PnnHtQDRWV1EiD
OTUL0wbEqhf3kizVvcM/bVJAuor67uhQ2+6KZ5MjuWXhaaptW8amP0kkgvhm9Cgn
P2KuegdvfHbcfkm9O/DFMWXIIKEwNr4Pd1ybW4k/8D17WrIakdD8DGMCFc/afG6w
dSDwX2mdiR3rVHvzNcVo85FUR5/AeEGbg/O142063iSc3PWCbugp9WzWEQSVCj/L
OA0Gj4vS93bQkaQZVJ30kAgIACaVdPgaSyuFTrsPvVyZYxCn0jbT51J01BPLnvfS
QQHKZHDWIkxBtVCISegWVIFzg0CwBTrqI9TGJXp9vOET7p3RcTR3XY4bmbRhn3dP
cPXGvqgg2pLcJ2kNi7GQwuz3
=6HzW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8ccac59f-599d-4a12-a951-61977236cb76',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//dMExkfmeO7KlaXFgXnikwDN7/tKEPLDOSlXMDDS8uUvy
5wwmbfQLsLY+dNQKIkjRr2QJyZ87f+RUv34HBFmsyuifTUJHdrMqn+nNXkCtWkwH
YU/yGGvwVReDQHqksYUEDjuqaWH7WmmwZ8Pcuwlkk0Q+jVZKFi/ZExilDecCNIfL
cHSmNNjCWKBf2IQxIaW0hzxGqeQ8BZDPD8oWOlYcwqKpXvCZuEZ4zRwf94MzXZZD
adVhND4dCRqSIb14U5/XqHtYdRNQD+eVeoFoLTU9VTLDv7sCjlqx5eE1VjA3qG/b
5QQq9tLh+OBVnqM7fEPvP+X8gCd5NL45bcxuYifa1wN1dYkfrocBPJOVAGiVaWjn
I43vr8jKHdBfc18F804ZFcwuClf7jF+fc6rlLTHVbotkFBtUAbV2vnbGTbFlooWu
/JkUYwY2GR5pLS8ugj9i61s3hEn8bMS8b8vITKrawukA4P4lsalLIOjRzGlTX/+n
gpBiMTrFG1mfzeP8YRlJQ6KTeRgQQBn9B0IuwqdG/oYIhcPu8C7rYv6Vv+1dWbrO
YRkkq3MAx7nIqeg8kKoeW3kDtJoa9489dzm8iIhFmBXsJzZySUd4qMOlKudu0lob
IMZKitYzWFlfaZskK5Ql3u+Twdkr/yzOHc96LNd4KvqmujF2gLqxymeqw+41kCDS
PQFG2+HbSKw2Gr5EoI444sHI0I8ljROGPu9LrbYX7V1UwV6yzzN5FZEIdmSZqg2T
UAL7SYXzA+/J+LjahaQ=
=ogge
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '937ef63e-f3b1-4396-a4bf-2412031ec243',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+Lc+nGQzIiiCwCRlaLHMjLkiNsZGP/AI8Rvzn2FEQvYxi
nEOsaQ8lXNTDYoUQkoaNA0QmIB8Hd7xb0jJcHuO6vJrCjNnD3nHiAHBOyCkGy6oB
aaWMK8v+C+sqRjfmQyCke3rhp27d+9+cP8C0SFfLTUvRpkTzMY2bLvtkC0nBwkCu
hKyj5B/3r/DmK+PTRXLVWUZQqAqg0erz23mkq7mB/v/cTXkwgMM2eOA1LrDpVgcx
IaRq0lqMGUmz/j3IapFDJxWluv0FqR6wrGdv2vJYreyPl5wtTopVpopdNQOFE0iK
iHGBblKkzmsX2n1sTN2T27OqKJiP8hvbEyWN88pZu4hMpTUpSYohjS3lwQpPSFpc
z9xQ/B/8YZvgUtaMqTQItZQwR5DEET7l/mXlCzp30YXCzc9n32YrrSNVmkXXx0DA
wnSfgk5appm9k0DfmKUdFvf4Pk6fNfI59TMYklD3JUgUXeUf0S6tNBAAEm8+p5ue
cS71MPeduxiyWkk5a9TMDqvMHMsCxUgjXYbriJX7ahX4Fgp9mHJI6pfcK2jxOXm9
HG2DlbJTBxTDBe3ty0PWs2foBvpUiT5B1I6FBhUnlT3+GlQ71rN3Rl9o4xovaXkI
A5JqNyRk67xq97KeGn7ic3aJx7VQN4hNlAvJD+fL62HEQW19uHUeM2ayS/ovwUXS
QwGQNZrfz7Vg9yeqBRFtBeP9aD6lww85QRCLPn52ND5lsktnALp4gkJrwQFU28s5
SbU/9vCMUZPamieoK5aI90MuNUg=
=3nJs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '962d1832-ce8e-4070-affb-f1389a618115',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAioJ1Gc4qxpVIUCRuqNrlCHSaRBebg88v0nTAIklzK1Xr
IAddwUgXrTqOQ+qTdefMxuKJA9kZ5hBbkX90MLMmof6fNsF+Isp36YQrFPmnxwNq
MLmFdsEa9CeYBxq1dPjDeKlRM8XfG4/E/HtGGmprpUBTSEhmfPGP/IucXNGomylX
/IJxlz8p1cWy3qY2ILPsuarfPnqDG4hOL/qUGtKG3Va8fvjm0VD97k3eDEED60Du
LXpOn9MNCr2ls02Y/ss4ytR0Muffvw2wnAD1aWRhGiWXSRCW1TGAGsxcH8zs6/h7
f9eMKKkp/Jj+7kmkw5n87sGyhXhPSZOjmWjtatOoTdJBASEgzxRao5QHawbgtho0
xqGVb5R9AjeOhGNfQb7wKEP0RqpP1uv9nZzikxmTi3ZPdbf03XSPxMnP5OOCiWv8
mHM=
=lA55
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a42dc99e-9e40-450f-a939-538e7457eab7',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQILA1P90Qk1JHA+AQ/4zP1J/zzg3JtgJ8e9Sa1mxy6/P1opiO2KpUPiIH9IUxBn
J17V/t+pHbrmOQABysd1fEide1xyOfRH92+j3IHqNIOXdMS5ZPGNR2kFwNd5S+VA
P9r/mKczST5tKzsKTC8miWa0DrRyeOSwxJNMogYG1hJfN1pTB+I1us5tBCKwQQIj
mS14mxhTd+xZ6Ts3+YEw+kyByzuTdp7H78SVxYXKQmMDj7GfJHOarJDoG1AESFUF
NA8+GSsdlUGEixuc1FSDhk9NHjW4QlXbgnKF5gqK7KC3Np94dJr/vf3EvYhPJoYW
yF3KRFSVz5Znz29vbF4lLxkw7GxDgs/5Npe6yRL7E3L1uNZUXVukrKmTiNVPD16K
V1BKRBl/et8lA1RoqJ3/f0dxyGpxkpDRtGUBaTuYW34+vn1+dcTswjTUB6hE7ODq
U9GoHKoBRZHrKeApU1gmeawrVYy2v2biqrxyX+W/a2rN+DtT9aE2RDchZ8hOB3VW
DbGTok8K8X1iKT1VgWlin4GMIWXkzO+btWZFasJDtDQEErdqqb99pVpLGnOWAQFl
/MNrSXgQZWKtdqHqoJDp5Gu6KSW9ZVu/ORRE58NS4kqAkAF+YXIfglQtolkKu/J5
gcBDFrqdcW8KW4sjYCOhbZxfL5V2i4W2LO8KVviz/ScPUDpf+QJ7eL9tFlgicdJA
AUgsrQHfMaPiNLLnDV2F6rO+8QOS4gLfwdImPxe2Fv/w90R6VLiSD8kzyHtSZi9Z
vnmADuAbn34x/f1tBnlgCw==
=bXeQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a6d923b6-58f4-4e20-a113-2d83d46ec153',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAgcMQX0v7ninjHJi/tcruvgijAA4kjxXwOUQqp/VBiykn
DTbCRDPtheQdSe3vfyzailq9ij7Df7WPzjh/+Lel/NOcr0D2wHcjlO3IvVYAPjJn
obiS6JGoryTyr9keXN+FrcLyBrJvq6QlHf0jZNdDzes4tzBM9ppy06Zpf3Ad7abn
z4JbpI772xArUGY6xjS/+Ztjtv2u981eFduZiukPLhREpdkzaL0errIbSDriZsdu
DWzsUVjU0xHVWmPLLE7hUB1+EMItLm6gkkmyX9sSbsRtKYu6gl2D0IGABfi9xDo7
0hxN0bfFxwK3FiG9bQ9e3/uAEH44hvymUZ7U6/BTAB1mYY3hmgG9dJ9uRIfo2qJN
EOVfL+LgXlsi7e6bhSSOrbvS/TCjyrgiUYRu8ID+TGJk0qXIFni6G6uXW8ZJn7YX
o84ij4B62d1k4qA5qGNqFF5ema0q/hSqGjwPxbBSe5YckG8b8HXWLipMAQwktAHc
C4EIjQ873iHWDT97Ndden4lo8q4dEv5HhECU5Pxer+Y7lRf5bR7B1ObqC0/Fu1ZS
emh8dJLpJfc5R9r1G+yLIdixS/+LD8cTj0UDjKlr/3bRypJX1RyD76PNaJ9afS8I
Kb+vu1kK6yAWHdm6Wmj++KujTwJT48S7ivCphJdIi2lG18C53jYOr5hFhUuSwgPS
QAEFSNtDYc4OVT8GCEuAiQYD8TpqmqWzIMcvJjnjpezPBZEiwqShjF/vPyIEeVbU
u/181AGQ4ehqTsBjYS8aFhs=
=GaIn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a8bd4d75-55b8-459d-a62b-3546428ef02a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAw12pbJYfvqH6m1ORUdUTyb8CpKmHrNYLFFYjbj1gMHts
lR9civhYraxI1Q24/cd770xPEId4ST3Li7aHKVhTcbxaIFe3xlqURsY0s4IrDM5R
cxWEZQcFPZq1pNJEoqaqKZXiDIbyBrLzspGJ00DGu1dc4aR+K+C2oMnoSTU2uuPD
YVFJYa2ZcDrhei0RBTSD4a/LrKrWFdDRJqshHOYJbFnRgCGQGE5r4Y7WDx5n0sv7
p4eGZTqkpU5Z8Co338tJxbfqAbrckgwd0P/oYNjg5O+rhiJbmSy9rASvYlawos1F
YZDhZ8QPVqzN2VWroS4hizWOKrECaH8XIie+7snpIG3lHhyi2dVmuo8ffRjjCNYq
dkaWYHY7oba+T9+h1utB8xuxxKxwdRvA5yizJlAnQSxXiAf3eFHYiZQ5A5/CjqGI
Lz6ZsrR2OtyKS+WNDV5yFJKcHyXdkiFKOvqnCCQHd50r2PZDZViuLJFblUxCzViX
NjR1bP5NJeu8x2Yo+X7ssdW+4JnGc/VCceE20+TEyMOCpktXd3p9ng1b6si0lFmk
5Ks1KIAO/DBoNALsy6mfFNnCTE4Dio0CgdtSK6xb5X5V4qpEG/q57HKv+bmOD93w
ae1bNdNrxE7MuJqhf7cQsK8fi+zcdywvxQ2MypESwUg91co28uRxAAAPZpGckZnS
QQH3efcEVHcXL/8C/snHVzNfGcYYWUce4BsxGVlzqyYhh9hT7Qg1V2AnRPJsXyn9
b+hpq35ARX7DZB/5Vp1rA7yH
=pftg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'adf8331f-441c-4261-ac1a-66d694e8653f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAgnav8wA9iACugNRsIcm2PCL5j+45oO/ojTgb7q9qb1z5
P6a9Af51JQXzFi2LZre1aSR56pOHGsSY5LP8BTR0+6y04W04w0JMIKrUMiEguDZc
aPlLoV09wcOcccPsK74GVRTZfukhi4Qbr5Shto7JmtTGzprHi45aJUU98IsHpxbO
LkcbuUBe9PcRqS6Gl5dg2SYoIFhf/WaFhYtlXukgCECHE3MXlCQxoTfDLbcqM2Th
I9PQyDeD/m1ySnxglffZo2gQKPSIS8YWGr39JgidePzAbwt1vkEJLRksb/TbYg+Q
8sn+l02d6qIPZDSC58R4i9j9f8VzsPXS2DZ8wUG8MTETGUBmw4aRVF+kvN94fnr+
XUTMOF+DCjhpSzlxWSvoj9FHL7QEK3OMdEoH4fsxPs0luZAaArfLopz42nyodiMK
ZYX215pZdNho651s2DTerYQE8V4uZfb4Tn4LkdIGJgq6RcR1h4Hjruqa0EcWHUNA
cxypxo3UdWg0CBIfZGsg1gMqigZX+AJEPpYuTKmtMeovnB3RH9cpv9afIvN1u/xy
CTFxrsxFeDN9pfbe0Uro9peX14pNyreT+APrObhpoYQ9lbBdkJptz1EoOJlxCmD0
zAvIDtIli9mlFDFLLHo2WzIQHH+LXPscjX2Cj5ZdJEcR3oebB02wlkukNEZP0bHS
QwEmHR/0S+du1AEjhHKEkqvjjxuJ4vYL5Ze1svMYn4eWkCa8Oyaw0/5IB8bDcwTG
ek+QR7v5bFXHy+cTztKwxDPV5YI=
=B9Fm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bca5fecc-0794-4ba5-a76c-dd8927b3c00d',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/8DOb8pAWjlSmz/rDNtbw/Wh9QiY3LAx4LpNg1N4omqQaU
Wh2x8JnEImv5oX8IHUZoIeC7TdQP6uvYQONuE/4JlbYO/Uh6cF68ycagJhIXSId9
qGgrHf+rg7yg1BxlPr3ZbOeyBf2NfiXS3hjWPZ2EMEBLbasMF06JLy8DXGzzSgcU
F3xqajfHwI/vYF0ygC25wFKuKKP+X4SD5z7u8X3IiW741uhX/4aGUgTS9k2P7wwt
pzMITmLLp94oIaWIcqlatbvpOqu4/m+L2KwOYM0gktSgnaHbQNia+ceTRbKSU/RL
hsQAQcnN+OBfsgbJ/EtONCyjVZIpWRXChwrAKXkv/i3//I1FIcUP/S8bYzB7L5fT
ITS9KVcb9nFxLiOPJzbPGW29TTEUqKdBUgLWtj/akQ9+d24bRG/2HcKQmWwHgaO0
YrCc8vvA9WgRtwI3Ymwv7daVzL7rzEKuA8jBJLj069AKBSXm1IpqbRe+tLLM6X1X
EVpMHF+Rg7/qOAr+ygdL0qevRDIL7nJwsQ7OFf+Cta2Zl9AKAlnpjbCsOwDazk/n
nv8x8ndH2PJbgpJDB08wKRRadsEhsIkGX9TfND4sSVeNslP/eHVzj3Fx7HtbarAy
wbAk1ATV506oNAxfHP+jxVQ+6TOTm8ZlOUxWsxM8ZOl2zFORNxIOr3qoYcwpzHXS
QQEYYQHH7mz8DGU6B2GcL5v56qf2AmFWhK2jLJe+OrOXMW5qFSimHnkd91HpALKR
jLsqKYg0NwzpuX1+T1uiM0Ct
=3zne
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bd9d71a0-e67c-4adc-aebb-4ad4a5a81f93',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//RDHDpYNvTxEILWUqnKoeWRBCLo0kdJv9UugBUaN3FzOp
mXpRKPUyyiWpk1deBTMA9t+yJJG9SpCoY027LBsbxinHw54qzDAW2RbNeyCtUt6/
MVv/Ih4+NYztgUIe5JbOE5Q0GDjdkwZigVt4gRzbIKmBOGTvEvlic48WU156E4We
gFDlFLnuAcIfUsb63pTJ/uFj7TX5s/me98KFL0ETTWtCb1F1LquwE+GfbTffL7Mc
VFeWCVMFJD+Jj1hWsir7rmaS91v2Ka0/hXxPdIyANYL1VvWC9R/9NTXegd87w54H
r4iOZbGf0l3FhgCvKDiwjAgT0k177GKeWsP1ODxtTPhLDItLxD+grQMj8GRlxt6A
yGDtCn5D7dtNLnnpmtxLGHXJDVOH32XmFdFOsEpBeRFyHNGNWAb7PXAd45cln96f
PheAy1dqrJ10GY14XJkBg9yWC/ZhVVKgrfnL6EbiKEopq568sh8c2iz0gTjTlzBl
dEdzhVKGddxYOW4kc+2nNtQ8DnCRdoFqvRYCzOfQb+LJzvAycWngO/G/xMg+4g5J
Q/l4svdT5yswjbvPBIy2aZaIf68S9jecMfaYZEeTCvqdvIvXLOOG54VsQgr53Z2A
mSQeAffZDXLkTWSAmIjaXvZXR851Fs0Y55gRWMZosQY77LWv5yaRzvNiQ7nTGkTS
QwFWICmHwseEF6E5Azp67tIrDEqdWgh6g0Kil6Q2oltHiwRjiDujCo6y9fbrWU7P
hulSCyAp+s2+k2QpQAkGjcqFHrI=
=0yF9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c14b80b3-758a-4300-a6d1-c9b6c661b271',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAzM2xPRkuPgVcKFqewwmLPC4lkDl7Jo9/LtlevyHN+0zY
lG70+TurHyP+pxgECyJeKSNS88SSL/hJ8njYKO/iHQxmnDD3BT10QksUjSKIFa0q
hRQrov/10QnP7B6oZ3hrwxnJpd+Kdm2SpVDQte7g3ixyaTjvz5qZm/k9Vgp+plhW
pt5+BuUheY2OD5L7mrf33izSm5fqo42KHxHgshyNxlrsgDxY0lgqW23AwcSCn2Ih
vDuwUvTS0hPrbeOuQcalIGT8JeAeyyNiCYX4490+hTifrNTdzk7mSmjuh6uUFkE2
68gALtr45EhIWrl8rtAP/2yo/vSaKQbaS8gf4zhKMamMPKIZedBOLx1E+CHkLmON
Fbs3beiCB+SCM+owKGX3CEMYEStGGREXHEgyKNJj3EwUPQB9liiBflU37n+LocZ3
WkzX81A/NMlAGp2mcmPL+A+Zw+T9s5nWbmEm9x5DKNiycR/hV14KlwX5+PHA6Xn3
M4+f/FfcWguhPB8/UipC4j3XcRqFSlkXaTLN9wCNXfaDubqOWXWSMILTzTatiI7M
4j+/D+tdJcQxHLJoEWwMt232cGXD1sq72/JLIZVrV2oLPt8Twyl19+IFHfHfg5it
rIv07tIb4Sc4lqMys8OoGL19rfrVlQRlU06IdXkbfU9ERRapd4545Q75ZAD2G+nS
QQF3RUd4plnSK7uCn16upYik3sKzXle6SAWlLqQJihbwdqHL/vJog1U8Fe9Y3HjO
3OdF+L9Q366yvcbRL1KwWs+R
=syW6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c49e0cb2-3d1d-4b27-ab24-ebf15178fe74',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+K7MOEHRh07m5ECU6fe+ipL8JHrKG6pFQfLt1NWP67pEl
t5YKJLlEFvaNN2a1PuIplgHd8O6unufZbBq1JWQBh3hDCZOmzEZbYA2ABdxvWBFc
mhVu2YO5BcJ0om4osJOJAFJ/NzFfK0g8rKvmMJMkh2ha9oY22ZK1BWlTlDlMaDIY
heFLO4nTxkhIhdT+cuEb2rD9Q3lrusY7G/MnXOmLeJ4xJqlzN988JVEBvq3Lmkgg
YOc2hm93EwqCScXyIfnr45JzMA5eyIlKPN85TrdLD0N3W/1XjGLZEnyMol7drrU0
F/W/vP9vnNBqoBU+PgYYRno6odRa4tRBP4bPIG8X4dJAAVic9k1bmz9jYg/4hYMQ
B7sgbuDPxS4H7TXFszLIF4nhflIRDFpQU0+txIJmWY8NQFUXqQ9juS866ryqj5lo
9Q==
=2V9M
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c8009037-8e0f-47d0-a8f0-f6064ddb3b91',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/7B6D+2J6+39pGlTArut0wK3KbgsjftaEx72sWBLeAW398
Ov/r/WBAPKm8AJRh+0hjdyFbmyjA7orOMnEiZrX6HB9iYjG9OsrUBqdtmpLC7o6M
7R/4EpvbNHhHqviKD9JyWeXUoU9kYr+w3aTgjvFAvxlCEmfa7W4Dl+T4Vam4d9kv
hci/60rhbhUAVFurIGqCbJCJgmGvC5Usrt5UUSbDzgsASgWTh2e9LJB9IBYZC/FD
JTr8Gn5CVivOKkKMNRIvNHFI9gEwoAjVfsy0jBSFhnU6ODZgH/oC4GHKJ4XwEgwD
ZUMfu8h1GbCpQpBeXkrpvYuU44z3gaUxlbgq7MWgBLFJAEWfd99j6aV9DkfEpcsM
BRXDCxNRpPWLmS2fBEKYw/2ZPbZIsDFpxOnyEC4zy8laz4aApSyQYtvdXZ+QXBzO
QNFg/W4M7fd9GIEGAuXGqxdJ3DzsdfXwXfk/g9mejEpKrEqTY0AxTDJxYZG0BFfn
3uYPxbOF2vV89akb2FWJH+057IdV0oe3cHIu/ykCpMosLFswyZSWTO+PcNerlq5B
q7nQkSNY34gtwFhyJeWoznF10XPe9HqmbdnERN87WvGz42TpLdrORKYvENXJ2I44
WbXbQflhAkwgfQ1NLrSCGDJx0Rw0Hq/tEttrNlZBbXFA3Zu0PMT1NJDIH8Z1D8LS
QwGtWs3KVM8r1RSDLKCNBI4lCQ7+/qGb8md57nucA2K8zmYaUuh8jHKmQe+Gr7QD
wD0ktxpEp2TRpPj5MgtSdzI/g38=
=m+DS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c9fe1256-5382-4706-a4ea-67ff9373f8d8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkjAfPUos4iytNx5gfkPEnbFe721DVCEaeAM62ohW5JVf
fNgatwOaiOKtRVOeZAyZzLbi6/Iz1a/A01zXZyArF+/Rd3raL4aU2BEs4LWPRMON
HiMS53aKW/1kf9KRE9q11hwO30JwZfoUqF79wB5nFui4QCcEW482eGwGDluCmfAB
6Tw4lwjB9k1ttUQ0UoWozMghfy2KxTBtRaV4gZurw07Z14+qLrTTyNtgzWbJIPAf
/PZzELOywauqTBw+2AJCTaVfom20aGp6c1FNQSY3Ot5d6B6tuow0z62d6mnQphvc
blOmNxvMRWQk1tywLvMCxAhkEnqK0FappSXv4p3tGg1LLISikBphfrKPa+7l6bNQ
quFUOlUlTjxreP+4CTmfxkU8mUXAHO67l82nLiiVGbhSEXeKGkLB+22yBFUIkl4m
RFNjqszXcymDX9qAos1YpbtTusXZvRHXCs9Pby7+h+mVeupGxTUmwJRceLt9S+Sl
FRJXZ9X5XNhdQSJRiIg6Tnfq3eo15ErBuFfZJdMuxroM8MufUS7GnMLe8Z2Ynosv
eBvAb/vHXISalXg9nMdR3T+bpU8ZCQ/t4jMWYbSehrrdU/LqWjY+HwNcRw//p7I1
vd/IizvkXmAPJbt5TF2+oaOLlmpKdNs3goj4oBqDtSdLjZJ80Z3HmxLjJQWUacDS
QQHHUdlq8OCNfNQ3x8OavYhUOCpsYYmcHxhx/l2TX8h24An1z9DmRqa3OUhrT7VV
F0Tvt+ulXsx8Abi15ScPeXKZ
=Hi+i
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf60ba63-a51a-43ea-adaf-c274b7118e92',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/7BPGNnP645dSWVUuGL06Xy8hkRdY5b8Ia253jlQFOdNxN
iEH0AGBoTPt7JmEZl80DGQ3gGAIfwB5Vs5SQ9nCfDRMJmBoFkKqZRHVecu5gjNKN
gFut5Cga0OoPbhRzL8GcFoEsQRxp6RmF0OT7gg9rlf12V1pfSYM5JSoPU0L2QO1N
CYH3tBOI3CgLowoVX/xWy9GFPyxlFKWiPJVQNt5ysqW/SMJxw7ZYiGFqC+z2SorU
I+8RwilkSRxnHXcx4Q+VnQK3MPjsm/ZszDmY/yZ8urNWO89qENG4gjNUUfs786sR
UxJ6lapEioWtv5VDxATdvu0KcKN0VSY0zq52xHbpcsrnLbOaL9oYag7s9IirzuQH
+gyHDaLPg2iE+QwLehMg29TcunpQYNUZX20QmVRTFF1kGv747IPKfRbK2aID6Qam
W9WFnVdcsV70qxkpbQOjwS7NLMWZ4IRf/VALCprl8v8I8oIX5SVC8PNRYtskQ7FG
d9WacRoEff/g7BMiwMSufiXIdS/FKBYbWg3NrcaKK+ToOUZx7hfQMfx2tvCcigbI
KBwwZyzi8xL5AL/dM45HsCFoFIgo2AeTRA5Zo8RBoobuo8Gnnc7POpeXteoW2aEd
NM7xETpKA06yYxQRZi/EpuDCjVKfFPfEGcO0oUmAsfgbKJ+Z8oECOTeORbroEZzS
QQEHtVFVhpGldMjky+qSI3hcboSHAbf88Vuq7yNySlsuo8Ziat28+U6oo5VDiJ89
hbDUB4tZdoK9OzADqALxLQx+
=WKmy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cfd0f463-b684-4a4a-a2ad-83b7e92f431b',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//bimv2nQPZTlNkjJ4LqbTJUz9Rg54y9KgYK8zQC352b7Q
mVC6Mt2UgUJxBCnrYA2Faxf/d9qC+mwvXV2Me8n08vLFcwGBJcJvxuftPY92FBIS
eWHN7b9qgGIlYtxUmmmXyolZZnylYfOa/isM0rIHl+YDSQlzVZkYPjImPmvBPqc2
cnk+g45oF+/XHwL02dz+0+iPEnzh8JVtB3r/PFiygMUbHQ8+YiS6/QlZI5jvURPg
eRV5joeC4cvOJ42gDPkjNpwXD+i39831rZMDtlDSPqPld0WENVvTU4L0cQHndxbu
1ikME+HTQYKiAb6hENQbbG4NSqpxYwD0qrcEcpxVIj+vLvM/9YwofIMDS2RIpM1x
kIbg2+QnlsFXGHVOboHQ2241WKCE+HguaoEMkVvKLjobCT6CaArR1pGWt3cg7rMe
b6UGtt1PLr7mVUnKX9PMA1S3MC5vCK77ryTsrEydwEExAuB4yFEOTiy50XJVWnSI
T0rwVp0vsVXSQRNna1lcNjw7w9RFcLfi5vpN+s2HEuhfljc6NgEkS0a0CiCjeL+P
mssVPffSOY2BL/pQs7qjGGjDgK5pPYuk4LTtfeWFGeQ6ij+M/I51Tm4vapRCI+E4
ysycmGwWQ9uM99gx2qdWkQDWi1LQ2HThIs4VM1/OjeIaDBY8Sy91N4BTE+nRrQbS
QQFnvMVxIiMPtasSh0iStfl1kSFnRpiZotZsRAJm5RzjEPq5zDFE9yhtyFtKzzpz
5iUp0ImNahgsf7tzDfPSgUYp
=m+eI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd4db46ae-2a29-4e63-a4e5-ef9a007fdcb8',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+PdqGfFIW4bOkPydKCCkvP1qsCN7TPr56YP+9M5E9xY94
24iW9LIJbCtzPtsib7HRAd1mu5QS9okwQf9EgqgESs245tmM+7yCdL0a9ynu7qin
AxoiGQ7tWxC2z6Mvaoq/Nwdv141rTnnd5dEX6htm5hHpIrzL7fN3YTfOhgp+PGyw
ve0RasKe2VFh9JNL+NDDHLw77cHEtBLGGIGzKbStpT8uDTyXH5SJjrLMAjFmnAQX
bSIpgENurPnLUnY8fJiLFFBpr6R5zwqeiHFB8x8HGsXG74+hk3W1fo/wCDtiXVHA
QAO3SCMGxjMXdw2se0maefxXx0KYwlaykLb2S11TJmBZloeDyoG2M//BAd9I1GgM
ZTWhtPrlB/ISq3yjZZTfGJmx7zdZRLtKc8JHR6Tz/9GF1D/M3/mHt0xU0QUW3oO4
aMV78Rc7nswYTp252AyYwb4dI/8H8qyizKXXHkMJPA5D8wlmhWvXl9uO4bN+vF5x
7Cj9DzxTnsie5CRMGUZ7Vq1Q7Da4vkHDjulksJzlXug6m8P9le8f0o2r3oEkpEA8
hoWni6cjBGm4xsf2S7vDfyj4lXMuRPzVjttbwXqlp5Nh/kLvFUdaz+jBahmipCRH
x91PHf6cfPAG2HJWrao3z14mKiN04YAXwtcX77036WXympYYId6cnNvrLmCvWyTS
QQGt1xnxEZG29stwGxg7yvbY647SnsQplJ4z04nTtiFtGchaUWNV8IfpDXTgN4YM
ChFc7V120GsT3IOo8CzqTDrR
=YvdU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd842aefe-8728-4a1e-a1cb-ffdce2f7845e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+LUVyfY1opbVWTxY3dENlG2b/qXM0WE2898EiAwQeSQRf
nOBf2TIodrNvGX8X4PhtZ+l9ohbYiIlcuoiPIkJJ6zof5LpvxV8OzVo+b9/JOwnc
+aWPGDGWazBu7iMZQJ7Hyp8+ThlhPzf2q+sYNQFVFihzLxZo+57r1Eo41Gn5WiBD
YQXiarX0I9omjrIu4WASK1mYIuO6lZlJQayozCnP45iUuN5BRjiFzrth+a9bbS9v
Vgjouyz6GXvKbIWq9oDLIT3WDhgcWZ1FiYyluIM06rtZncRRcL6Z1LyRg4K1sJWT
lCcpx5hxwovseYiKj51jpo0gDmJ2gOl4FdUClCrXSvwFy86buOvXsJ5X2ECK15ka
RWRDLrQ/aO59BmT1wUkz0U2IbX2xjTL2l31n61T7W2EYTFIKXFNxkSxkvxzDZ8hx
/+f8tSiwOHDsGCyIu3lIw/OE0J3de4Qx7eALIjynhduSmV+8/D0rymemOpQ+jmrw
/3JJpm+8l+u+Jzs0w4PEGszwDXwYe5kIRhjPA9k/PlmciL9UFj2oUE299OhhutME
nBixjdH07Z07XT4DS2q7LB/TrhkIzloNeyi6wHf/g1lf+WpO72gGEpWtEDY9/Hl+
JMgqC5MBpCbLGxXyU8w2o+5tmW4kyOLhAqw9TFLJAxcnatYowQzDHnQe7z2OuWTS
QAEXUqrERC9ATE8VJx6J/gMQyuSz/8teobP1kW9y1lbamDbo8ceGAMu4aysewjEB
pfjVerjSy5/im7EqaUF1U0k=
=3DOW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'deb439dd-7b94-4c08-a2d8-fb93e2f74219',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9EeE0FXx5BzYOfbgQCjLtmeQNwUnUqJW2jSXOleUFE0DJ
sLzbFHQXEmCOWLbTNKbumkoa9CxbYIJOk171FxkB1AL8y5zIWtI0ETQfTT1iXn6G
1CfpuDQvZnv1NMYIjIWP3LGSIv9sjZnfoFIaf/elie2i73qbxkHC5lrf890Hw6NC
HU3qI4tttTjQu+B/iBvt25sly6Pxlx9f4/69cmHdjsnn2iHECpg02VrO/vz6DBtS
WZ7LUF+1aJt/xjeb7OGBILVfuKMeNNk1c0NuFJheZGxEZc9JNdsksq9DR3az5+Nu
gP9q4+CkI6KrlqRmY4ujulEs+qg5jYv3UaL+GCd4PrPEo5lJNTcATBoY3p7Dg64v
0jkS7IvgiL3GvcpQfndpLwkrtonpkYVl/qnRXoH5Xe8jdnhVVzw76I0Wk6iA9k1P
FEbEbY00rB9+WAuBn+chBYudR1xxKXxiBNkSB0Xjhjr4iYaJC1US3t4l5DIfJlDY
1WVh1eQmAvmDzlUkl0HUnk3/poFhKdBYnWEJy1fJdqlP7GGtghj8Lpq0Y/0zlf50
nFfDHKgB7Z68y6Ct+le9IBWqLXU+IsgTLaOVZ0HrhJccjbIPe9FaWcv36Gl1voV5
NR/xAzH+bO/NNJLJUuDhoOY1YcjRUyXebDWgCCoSg1qB3SDcQTnSuC0EZJoSLgjS
QAG8EegynlaxYhJAXwCBUb9jjISNlDF7ylyzDYKoFpy1JjjKTag5MncjmNtAInRq
pl0YGzBydEtS2Xcwvz3Un0M=
=yuVC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e8f45c56-1b4c-4b80-a741-8cc635fa516e',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/ZRKUXsbvXBkDWL7BJX4+u02Zz7nIr3+vVegc9Gh72swH
nOijtd7NU93SgAzsn1sUxCEEQQIjAaPKYyvooIpA890CNPRJIN6wepqVyPgkeCuJ
fxVXvwGrsXv37xXKbYebMmcVPBRnXDhQtW+CNK4i9I11I/OTl3x311wvkGyXXjsR
Hw2rH9MIRGaMqswIFV9b6y9AweWc4e2wTJpz1SPoc+ybnMK5OYtQD3oKE0+TRDJ5
CYL9YN7T34osnHslZMAIuvWhLje7uyO6dZpqWNKG7vA+z9g1+Ad8Ku6GcJPZMQ1U
L9GQ13rnS83Imz0RCTlvcuGAuOBViva4d8fDkf2XStJBAYYlHT9btpXhGsCiBlbq
k39yyoy7gJIA8zO+UqquP6louCIYSEeudYSKu3w64Y2Dn3k3qJb3uM9AdVQ1MnQg
Mt4=
=nzGx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e9ae2aca-5f06-48b8-ac4b-c498d5fe1b22',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAjRulYR6iDp6WgwQDiRHSowVKzk2zrmx9o/JZNgZc2Fd0
kRNeneIEIdfWJdPRO0eemufPFxxGiup9XWatGpk9/uO2j+d53VeL8uzUU0vQkuPG
3u7/5OFCL2k3n5z+3XFC4KPcBCuy2UpoinkBGAXYkvjXPb2hZGVHfcEmaJZFlyFh
7DPenezjyDDXv5K4mFIhBR+d9hmdapjegtF+4tW11Ci2yCXqNpxJQbS+J9XXk9/u
KMCrdi9jO6lbGllqpjZXKCfbfDB29k6g3AUgGICQHPjVA+LtWWtsUyU07ZCICm7e
AJ3opS0hv4E2hSNlfQQgPMtN3+P+Bt6iywcF9jlkctJDAT/VI6KfLwEyCZ13WltP
aK14OAB7asoHTC79jE6qzWBmY/T39F8hHP8Qw/nF2V9vSeMx1yw/KEPefbkMv+eh
xBwtIw==
=zNy8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eaf87fed-5d78-48e2-aec6-df548c44ab1c',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAylOiv4VAhbAtbd/TObXyRAkxJPuYAe7tis/Pjb3S7cfL
e0CCwCqWtaOeuPVfgT8DMf8CcELjCYGwKDxqoddvGz7ypnf5RTy7rwg4XJ5iYZ+x
VDonn6USvV4JOc0oBnnmGdp15Yuiv+lilSGbdgv8DuaeCvIZD8Ra8oK60vREutBe
uV6CogUN1PcpVFsmaWwZlPWfgKXerVI5k5mPzYIYbTxHdK4j6lvqUKCEaixghbLE
kdLKf8skjzlQipo86/6eiWwDhS0opV/NlziXzgVev616ACmfGZuoY88s39wdHFaG
nk0HBFIPe2F0/oX4s3AYUNseDaazHSPLAZb7DJ5QHIqMzxa14TTacaZsV++W8GOz
huRPLt/CMGEUcVr11SQzgQWU/dndVhXAolCgMEvhPzDEEtGPa4QI4lg6x6YJjKPS
NbWzORxb4nsUGWTks6pnboNPobNrJBRALQCWkSk4xlF5vAc7p6V2B3vssl0g7enE
t+HviG60ecKwm+Pg7SfJOfxsQVW7MOCK0v5yCMv60Iq90Yy2DOvnXPB+sebqRDRn
0JesJmQXTrpEOtjxNNnrfZnq0JwHVujqBMVUQGYHTEPaV75stQdpBs2gk/mkYFci
q5VlNR273cgxkzzMwifoHjlSuDcQUEZMGzDB1dqDe4k5socjJPWlgoS0f4ia1eXS
QQGjgupKShbJ3utwdGLlC13x+tOAzVPkb7xm5/eZzpiAb/FSz5BkzVZYkmknflsb
DJRIGe7JVj74b2pZSmafn1ur
=QynD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ec36423f-9f44-4489-a779-b3e4e1e3dddf',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAihOSIAwvlLEMO1ezgncUADjBZAD4MHHkwVeTKfZuG8PT
TRUiCodCWCGT4BSKLxW2IOXMaTGYSgM3gZhQW3NWluiLoqjLfr9aVHSehjk95jq8
MS4XNbrECLuJaHvipn+1ongly141C3HGcDwlV1Zqq7DNTv5jYx5R6zyCNQq2BWzB
mY82IIEzT1mf40dp2TnBm4T7cn38Xy3zZkPyg9PM3KzDLYhIjnTEWGCNTlr8UD62
gY6Icg4lY+iS3+CtKPb1WMTpc/+gOw+11XXdp0Lpsyu4aDhM3sSSAb75Tpi7JKyp
ySRAV9S/56zaCwyNKdMOEP+Hlg5dR2gj2f9mzVmcIK1FghmYMLjZaOkXytfAYnle
Z4GzTTMcrQR7/GHU8mFvxOpx9KedDR+GQ8+1mAckLBZIgAReroA5S9GaY6OT9VrT
kP4P3sA0d8PatzNqJm8PRE0iA8yl4jkMYxEOwFrt5ox7Af0SIxnDcmUNknwvD3ZD
y/o0uEJKDw5P7i37AmTcAM/o1ueUYTLph2WxD8yJ/668YhDjYwCUIzLmBUFVfSMr
OCsj0cZJHr2GIchqJfwkojRdusdR8Ef7Wncyb5wx0NY1RUlpDZGyuZ5AA8cEg/hc
Z5rYxX7Um7abqG2vRLUrwiSz0P0rtsdy+1yhLGFXn80hLVybqjbaxgb7CgDK2v/S
QQHb1wrZTb/xdiWbDAp9F2fCh06aer7rWBgA/66hAhdRJ6IPBu6BHIo92/UuMN7N
fX25rBeRk6t4KHRrkb97tTNK
=ukgD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ec94dd18-f3ea-4a66-a774-c878ddb37a38',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAsmOXa1pIlhvZCX1Fedx5UFefHNEcO60mjRdc/JKx6HPY
JtcB3kdmpiuVPb3jQ4IwQeGDkp1lrp6wfbWJ7h8ldwSHrr91CzhM1ovhrp0eYVom
j4DjVLyovYuED4A+9gLkcOsEe/siCRqnHjyNYStt/c9WUmdztzaBo/iAH+Mx7lwU
ib3Eu+2Kt+pcWviAOb/6lcpIbc5u28Rd5WVuN1fpCg0l0dzE5Yb0/zmzBvu//HjW
g6rYzndPQV71cLfzLCytj22fWgM3pT3Dz3MgXN47ETZii9N30hVU3J+N+H/i/BVo
5SEFMNStvXPXMHHkJzCpU1nzMlKNfegjs3iVfsGksv0GjzlZLcRVsHn/5YNfT4PL
oosgWhb9PdH5TyjGcAEgZIngnbfLWJA70FxKwN5vUsBJHxIBY6t9kDJU8EWlfomo
oWS/Hq2M4SSpF+mJ54cjls84u4l4/KbHApA4DURMyqDNFF5tPE1+8EW7KbfkyFDi
OhUHfMBAFhrDOlOO3Dm1syhPkrzZhef80n3Gjoc7Pq3N7YeFxVixwcDiuavzgCmL
wSf2Axz1XaypPYhC/CcSma+roKJEkwauXM1gu1w+t0FR0cBmPvyQLdgQVIHOBQ0g
ciOnTtocbN+MDFw8Ltvj7u9oVi8otOB0AvoYHY9C7FzaLRm9/TsFS1ui7lBMG0/S
QQFgqi48Du8GZR6wltRHbf/bqAVF5733t4XAj1EeCsfgcyderAjSZ6m/B9lYaDHm
8+BalTRMwOyQYY8rOK+mQad5
=uOle
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'edc65846-9f7c-4804-a48b-7fbc2f93de8f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA1481np1JfCu1sG3UDZ+VdsyEUKr8JmPjPJvijIsSEXV6
Df+9bi9h90mpyBOx+lL8ipksgIZnteJBZXiMRCZGl3sNEnU7uas6D8GNLx6AYqwr
JeBh6AR23Bt1sza8BK9j/yUfhc2lBLpPT3gIYpLWsMesJ6TnBRRFznqHr3ws08f+
OW+Vko7n+ZOPGT5/c/EH2XPTzGCThBqGnkVBh/vBtR66GVcqYoCF3ZKfc1UlnZi7
ROue5g1KWvZBZHCw+twPsTu48hC1rTqD9brVDHgKTOPzp+9LU2vee8b/DezUotJ9
JVB2Lw2QrpeWuv0sWA4ZDim/mrI4MFN95vVjbaR2Uv4J1BAW919Y2iLs24txtuXy
UakJeQweow+oshlOxjAL683jgB94Mna5qAMB1WTGb6+vVBUIQfIBTHqrxGP79bzq
YDZwaXqMTc4vOGBfCVuNAeYwJRq+BVjntwBSWSqDwxk5My9xHY3gOU8xfJGF4qhV
t63SqoKvgc6+i/Zcu7r9LbKZAQlxkyXxGTNg4RWLqnrJ2WgMD3X5olA8zK4MMrkT
dpmSfmXiJ5dxrIYUrpj+1wonb0HQVJaJ30SSAfgqozwOGcFnlfhnw2y6Z6Rv2ufh
kI7BQ6OW1gsf54YWOfGbD0Bfe8ctT0dwS0xoKW0BO5xCPdC5215JRRIKduKlZirS
QwHaFL1ENvOJ8Q1wSPZAcyh5rt5Ay3S7C2DJfc3EnVVNyQFUIPFGBNwxyeuz8NlG
lmnknJKEWbObmli8Mq0y5UFScVY=
=XCHi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ee50f58a-838e-42f4-aabc-b896712deef4',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+KrvHBuZhV/rqJGmYnbK5p5syWWROL26sSPWXdP/HI1aT
vu2TMxvgYwRRVWkQ8kpSWQ4eB2bAEq9Xf/12j5J1sOTIO6TsqI6LLE5DirPdFyD8
peN8vyPaGWkp55+lVx472gJKpLjSGZ7kDSUDVPM3GeVmNXHYrU9a/qq/PNAxuaiz
CkQ6bP0oME//pdim7m2JIy3A+i99TdT0urizBH3yzxHPsHu1rxsx3VrDHESgPBz2
mlflWPs0a+80CYwOgAUyqV8EuaNb7pyOIQ+eo9S0SPhhO+8/JL5WvIaaTkevJZci
E3/UhTmAhXFZ541uvuZYA7MTt64fy1yKRyk5MUkJtdJEAWP8/gxEuoRCs0WwuCnB
+80csqPOetv0bP/go5SsqqsJCzA1VCMBYkDA/hkf40MTUZKM436QFuyyxclAYIfp
Tb4OWec=
=1Oi8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f42a5be0-f0e3-4cbe-af18-105369688557',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAy5LkxQqEkGVOU5l7RGzaCFuD0mPxRK6o+guCynq1SeAE
S37l93z7YqIV5RzcEWNbIelv/QYY0J8NIPuH/DvTK7kygR5CtFxzdUExIFUveI6D
vnOHdm9xA7JtHhyhEuS9dmv+NZThcCYG0qLkYSp5Cxzni5B5B1CFOJdBhMTo61sv
wbFhmJycfLTdfM+ACTj89hSHRe/VIX8oWNRtCSUdp90eYPK4tT99DlTBjuaEEzEl
puBaV1skrmcedAKDLEKVLDQKOPCjZrmuoq//4LqGPOV+nUJ4JYLJxFvrechJzIDX
EW+ItotNULFWgYCGJ1y6DYhK8XiZBBOk9sll7rVf1aA8xcKAhjF2M6sKzP9XHIWi
iLkv71S1PRYdANQGMmOkky0bO8/6HapB3IWbQUEbRzkihjaKljpKcfzVGIlMFa6R
o/oTtE9CwJXWEm9Cz5nhq0V9VyNPZsT1QGubIAXI51XUZ3lB/grjhN8GDABWCDA2
fTbiB2j+atdMB/xRwI1EAMaTOTUnj7/avtpGeQ3DFrgBANyRKcwOg/dE3qLDfe2n
cIcZp7/F7yAKIQaVihJtQFiYj3Nw5BCvMh+/Vyn6Sj/79YnFDQT8TNJ4SeHg9DhB
MC5UIvY9rSHSAnx7mpLMYYk4NBrqKjiJggVwB3rvWhHDIPU39atqMG1vWoidJSvS
QQF4alOui8yU9lvSt8pAeOhTHVw1Zji6nixExCvX68jnft72CXYkvYnrzYgQX0um
6Xlk3oJBvXVNsmNiU101sy4F
=N243
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
