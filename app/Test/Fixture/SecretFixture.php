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
			'id' => '00169c47-eb59-4c38-a83b-bf45061b4465',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+LHglz5aCL8o6PCfVIeFetAyPRuNyGYYw4FoubimMQl1k
Rm1Alp8xZvhaq92e6fZ7dRroB2o82kSeItmS6pj2mGc9IpMZitcl0vlIirJnsD67
GWYxokvfl2Oyee4b+yD4sHk4OoI+KiPaxLs4aWm1oAYZhgHz+QFvPlWrDRgMp56p
ALR37b2FTexTz9y6cm3uoH6fPm8M9rEA4PQGgudN7TOHdoWndUET6w6V9K70/FoZ
qWNvD8r39FPQQY78iWbY7dMdHrCw+8IvCWg4DYNFADa6+PmUO0eYWV/ALtb6Ye9n
nrvtyxyW19myDw4O9Qf7MXwtckFPLXc8JS2tnAx1KspFVDht8pvUK9y3ryG8GYp0
w0jip1sAsR7tOggkH1+4Ynua42WJOn2HIykO6nxjnqUXgLdAM6EId7ZZfLgs6p3y
P2LmAnjMxKxLRoj5tKNggX9uFH0N2wTHlamhbGTfV6Uzh2ZpSIp8rQoLw93QddPs
yCuTLUNm+whhZuFL0ZlrYj1QpJHCkHSLiqhNkKKWkrKrpzsU8kVWq/WDBfpyBSg2
SQVvOsO0pJXpWmyEWolQKDdQcsA26Y5Im35j/Q4iL3BerM06fc6VjZ+Ulx0K+F52
ZdD3D/hUGp7aQHWbBK+M6zQgNCXs7+aV2zPjsDlATIDPPMKrs9iYsv9DUU+LwkvS
QwHroJLvzt7nCaMrowAxNkZrx+avUKXGGcY0tAODKVlWJj1nMYxvGlyj+7c7o5EI
F00eAXgAI122ku+rxRuI1tIPY6Q=
=bIlt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0a62af96-f481-4661-ab1c-aa469a3c1929',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/6A4j2j47kUUy0DtlCsENngratlXNn+iMRcYmc6d1O8eiW
/2cdH1rqYNK4i7w115FWqgBiq9sBLn2mELEbLDvwHIL1ilPR5h/fPaxH18iQTvXN
VDD98DN8N0nF9LmYejZhvpZjxjyInscwS4i71XyDVpz2HQnkTljsTnBZP7ZueMa1
pDqkoClekJ+6qDemDY6RDHlSke6lu5nn8qPSIa01NxeYnwd1QygtzfejPUs5645G
ks2lYxvLo87E3gz2ad8tcR7kBTTWYlhy3yoUWYAd7wMlSCzAI5cgqbWJXEZrerLh
cKVqZmSMv2pEJ7sBzfvKmkFMq2MJWYvx34qTfs6EaAwoetyf+MHwndTFawHCMTY/
d8dTrv8Wx2yNX2CVkTO+nWil9cV7DhtKeAWCWvaH1oZIPuV8nPf/M76eXQdPxtJO
7Kq/AUe02Mu3E+r0DgxEAAeomtHL/ALiy4ytVYRijI+Xv+AvAcRcA6Ow2VjuFTGW
PGWKKdsODvn53OFZXOAFPoxJQxEU4Wm9sISb1bj4UUXya3oFLhnjX7TS2omRO65X
mWMas8b1822AQtFlPpBIb+6LLsFHnwCOo/XvqPlgA01rScGUX8jIe0LLxqljL2Cn
kGEMFBE4mG7FY2RMq7f/u46w3aZa2nnBqnN5qRPLwwnJmM9/zM7tLd2WdPJd2/jS
QQGB/teMQ72a2uRTUv3YW7qpVDCLNr14b5xSwzzEZ94gffePDn+eDHXzXKDR/aS9
JGIsUy0gBri6LCHnrvyEKUSC
=T9br
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0eb0e6b1-24d7-4dbb-ac6f-736e8a9ad9e9',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/ZhApVANzv6mVO4d9/xWQesgT3eochePieKMhie0Hi+ML
1VDSdgjWiZVu2FI6W6sb/N7VzXEJniAfNJ8Yk9gPUObPrbFkc3Vea13mWdCm8JSh
OnQF6HJsXBHtP6iqGPktEaUQu28NmYDMGAYaPD1JFdmfJ4LryjWT9R0HWg01x8hN
aXXi70ta9CQ6sQKux+FyVRx6KqlCrB+VMd30ApLfC6xGRnCwNscm+ZOJzJKMfwqE
UvbejQWa+8OYbDkueyqdDB8D6X8XQCth9ZQpgTzCHJ5GqHmAnB3bP/47QUvrR0t7
SPd/r6mD2cYzigtEkQqfiZeoCCby6QP2+2+8zmbgkdJBAVroRkYDamaFPPgCrO3t
LCJJPmPHhdqXb0tehOv5QR0uEdZRbBCALNc+hDUPR1gq50G5Pe1mTXd/vZBinX9j
u2I=
=nBRV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0ee8dbf3-4073-4889-af58-6007cc738869',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAjlxqtyxE7d4Ce9dC+/4ooxWWQ2GcCh07NbLZqNWDzjb7
jpNNhhHuhHzZkXSfvGTxNUe/yZOiWOnWPI+iuJGq+YHZW5Wq4c/3aDrf/XzjeXlo
efNHirlXLfikYSWvKmyeHK+Gpit7yGQ/4TAK+6x3VYzVojM8s/osNPTqYqZoWKtx
nT6TywDNXg/exRtaOWp5m/sJimKTx7fP8zVzR7GNWeD4OAdQtvYvFoBaUiKdru5N
JNU6rT6zVk5Y6aTw1OTHQAdYm9YHIU6r8Xe1n7F09Q3eDRCpThOPvCA+rs+o/QuG
YQ3U8+5gce1k0bEwBPnMpTBj8y+HH3pnsCIq9IgJtRqDFXcWV8nbD9UocrDdoqtK
KpLfdlYwPwN5tA3X0n0ZHqX/VkvO4FU+MDIHNXvDrWBCh5QJBDtZUaWBbuL5FA/P
Rf7MkbyifVe97gC60aiZ62qhTK5FXXwvhsVGczMDK29vQ5Lhb11UCd0VY/Ta29KG
vIe+wtT9Mqi+zBzKekGJMjO1pXuLl+asCvniU+w/8xQi7mVGmUKBTW2XKsKP0Dn/
2qeo4w4eO0MhCn/nB80ub/UmZXULSc4ibTAZ3Ik+5qXvIGhq67AGLiCUy3qJEG6x
4I0IxznIuOeKeCDkpIOvU0BX+rnBGcm10c9XLIwoSppELjMr+MNOOgrsVKR4IkDS
QAEKpDJYktxhWEgiacSfmhL67z0c2630+3fKXwvdSL3QZ2iWbBSmxaJM7Ci/Gqtr
Ko3ifcvVAmOVrvHGqV0eFK0=
=x67l
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0f99d2ca-5c74-4adb-ac17-96594acc1b4a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//dVtVaJqR8QgldlwldSZvOYRdScrSYZB78PoxLsIlHMUA
LdU+/QYPKBX5f+R4fiMbY8NwylB9WA3doCXc21zYxLdS6u9MLbQt/abgxKw72M9s
SlSQh2E1+DogMViOUU5J/6/1CKpryoShXll1s/0U557xUyjRP25g7dgfkmKtxROm
XVcoUTJns8WwfrSe9H9y7KJ5I1ERvkZh6m6CtrsS9o2mbgZKvNAxXDdB+M8pQbwi
hcvKcS0lPSBIRHODyQQTR5wfRwe/W0BQ6gHEG2d1J/+eMyocbtooQnNDBES9xFXB
wskOk5dZUW5Zgo1XqhYKDNPUizyM4XqVocUU7sCQYCxORg52PRtgAvieJLJNKqcr
9sovZuiAH7IuFr3kOlsyx7s2g0SoOWHGZQJcnLX/oRNkjBF5bxaDZassdby3p2dO
L3X2RVAqmGEqaq5v5FbHDQPMdWAppPqoodx+rV4tXUOIlGIFIK4iFNGqOSOKFnAw
uHuPBzt1fxdb7cMwAIrtwDw5S9yppbLhpABXr4RiJmrQrgMqNm5HrvX3GRe+55AZ
ENeb6rRnbyS5kKasf78OQoXNZhtPU48HDCIpg02YYrKKCNWtubUPI50/peoe4OWX
an8NIn49lTPfZxgcMepijNGbVxQ26nMgvSEigvCR9z2EdlATmGyXUjHCk49Bo7fS
QwF81+PEJruiTWkgAPrkXd2sPpi2D2iKRhV2XyyBCkSIEHI3GmaFyrJnGdNWh0Wj
IspuAKjD1oJpYEZtmxHRwsQ56i8=
=jCaT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15c84d84-9409-4ee3-aab0-3a8b23fe2b1c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9HNgy5Ug2UrVt3yyU/AvRd6impXl3vxeLXDZZHx3x8a1y
vn4XvRMtJpw97dzUrUAmoapvxhaysqWAjA7jS2EYYjw0IDsjZ91ltXEVUWPIfoMI
FSia90xCtCvMo+7sscVSE5I1vaY1F7fWtH2zvgqnLCOm3UWMqwBMkICz9a9aRTuJ
Q0EnL6t5arGHbPCCil12xXjUDweFzPl2fOxBzAGkr0JwMZSlFXe2JMkvaLziyhZc
E8aBq4xmImxD0ARnZXAgrhD2DW/tJKzWutf+eoLn5utn360sCm6I1tJEj8kzbMXV
xYnEch9b/3O49JTCHUnLvHKBwBC1iZeud61/Y/RjAZfYaGJzsOaCzxHnWxCspyeV
XXK+E3q3PBXCBue824Nl8zE/CsSqoLe7JFiPYYURm8nOHPWMlvNdOtaDD8sf0ale
9Tek3iY9F3zb/dKJ4K86bEDV3wkSoORScNqclu7cCCA2L2B3UwfgqPXB6Y0C2T1W
X+IfFQkDslCapY4S/+5PRSpjkwvKJmKUfY3ujatebmy0roZrIK379IKLe6yj5uba
dcX2npHoDkTrAGuK1FVo3DKYDP5HmEZdDc/Q7rM/s5VtOovSwrRyRQ9BEnKhT0oW
N1CXpLi17cagTSjC6KdRxJk3n5EQ5ekjk8Z0F/cho6ebbtwDCCwJ6ElFQ3ii0bfS
QQFXj+tZSEb8LWaGHYAS627qBo77gQ8oa9JF3QUeY/hAODs6o2o3xx4EuUTKGvjM
ga2stCLAObC/2tYOSzSBM/Ty
=8v1W
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '19a26ac0-4abb-4fbe-a8c1-2260d41ea056',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAsMBdZWxeogxPyH4fhsJGPFRAd7szhh0+9JS8QqCMIb5H
2jWV24q1ZdaDW652x2asZwG88S2cYUVVHfcTm4PmZ3rs5XPzAuVjn5yYKMJsqE/X
s5JCvFaqnIl1w/OrYyO9CTvh/GRrEKlrgEcb1DcCRYl/1Lp+fu4pdPUWugLcs52Q
3GdT/PYGejyJgDdbgQIUp77kUucToh45j6ngvv8T87bu2yINDGRDl07hpBLuqCiE
mPVMZRBZkWSd9dlqOdXIgEZRJzPuIBC3ErDWea/+H4WjCTkGmNYTKx0UalO/EVBA
TxI51AOQxZpd6TTbkOGfxc4RLnFJ6CrwR6JIjGBUldnCdkHpeuVJTh9Bs2NHlV4Q
VI09g+olIMkf/RB+VrLjlpgDwnvFu49iR7xTw0jFo6lRSZNdYTHud9TiUddE5H1k
dBnF/llLN91Jl2JRThSBSyWCvompHiYd0WKOGC7BMeIFCkpCQe0lt5fppeYQwMFA
v77KzTugJBNgpaNlpDwam2XSp/RHtmEDlbh09O6clBIA3xwoFFXS/p4Epfh5KlZj
Un8hNuLyylpgpnHikMojQbNtxknxsjxVosMTXmJ6MAvl/XAN1PKJOXCJNc/LcKlo
sDPwxOqPZvGOqNcKWKI0QUsqt0RuMvyJ4MoIOoF9PjcZIWr4fTQhXSkstt36H27S
QwG1d644xr+frZ1kVIoIAVD/O6260eE+MA0prsW5iSnV8rYSSAaa3bPh1ZYLQcN3
+e87DqZ2GGzTxCAmjfJce0lmGms=
=dFm4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1b8e3c00-b2fa-4528-a58f-ec7936b44d10',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//SL9RqHjwljINZENNeYBuH7jvLgeYPcaN4TiwJ90PDmPX
LVCl49r9ap/hY4F65232M08ndODjE53Q6LwFCOh/5W0isr44CPJuWK9M9UMr4SQo
FbVt28FnNdSYayXxSe853SVhWnFSuXh29+OM7szElys3MOrMU7WOq7VMHoA2flJB
SZsl++jW4WcleUfU9A372DNaxeY2On2nIkU+9hYFTGfkSFgkAV0Wy3reyOngtQrU
pun82pCwY3dLghE8/NGQqv2KD9balq4FZzh1ak0Ij87zAT8/IzEkEyuRR1y0G03R
yk6ZlKvNKBtXa3x77AMHmkFdpQi8+G6HOGxWXl4BMAvlbBuwKjVDCEGB7hM1Sa8y
RH/LQLZt5+HjSkloVrsTiyqWTDIUHQlpfIveKts3j9lUxsxeyduMgrI60+4KQouD
gwFxBWVr7epfPkJtCZWQ93XHiV7ZoS63LcdrcKzQsuT6ST/hGZOElJhJ3VmhWVfO
WBVW2tbjrGloLfDetNhAKPCQrGt0zHH2RSgNXu6VqEvn3GwEva44245K1LuLncYh
9jOQoiVl3HDSTyjO8XA0OlFj5QEn2jaUZWzMNbIt+Sy0vtdG+1E+L0me3uMmblhl
j5wZXN6PJ29GrbVjzHXiEa+Z8+hbF3pu8q0wDxMQ587KfJebfBW546j8g3Vg+KTS
QwF3+9wbsi5JZcJI9m1u6s0FHzJzkx7UET9QowduSxqz6m8/mB280i3JNEONkSsN
W/KzXhMMAI9C3Ca3Xze78/wpdZc=
=8UQl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1fe80f9d-1681-4328-aa0a-90d36459a061',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAg4vjZC3KgXMPlv9OdEFLkKUBAC2qEWd1yBMr37VTz4Vb
rfMEUWANVPqg1YV4L881NdHLxucO6xY7bHApDl/ghSJVejPdF8J/FxRj3uu2TJ4Q
YDrUR7xHc46Pp/lwBnXSp/S9tEIPC1WGuxhetadHbA8khRsmeQNwUse1SDyXl05e
pXqevbRmqgLVRx1LLYEbngmGNsARIRd0v7hHD/NvTUNEIVuW7eLiwzIgUpqjYg3G
ommgkzIiC3u5nM1eDJMJ4CJ/nQWImoO1p5lfpC+noK9YTqn483/2AbKVs5HFEL1p
IDJeTja/rfKsMdYf96gbFBq7DfIJfHcLVJlVKokdRVbq6gY9CsA5uloBGRhSb7Eo
IrLlrMNe8IKsxJBbJ5E1qRNjRZkEnPJjlqL16MbuyN8L1UmSMgI2dn+4nuGYn5ex
YiTCh2JU9EYuvkvDC6u5TPWEnLYZvM+iouw9HHJx9oBUo3sBJlj9avvouWIXFB6x
1DabMTg7Z32oguv+7p1GxjJIQUqNY7FLfBjI0UNwWFFRreQ9EC1BjCb/Bvrua19V
Vlx126K3ApTGL/qkhxjf7jqrwzQP+bW2Bi1ul38e3AcIj6n9+WyNj4sFm8OAqxrg
JA4Ge/4fJlUA4UkDrLV44RDIwcHC3wTCvdzlA8TvLR5THsW9P4LwrOhxbrBKjJDS
QQHBLlf9Ke7r2puV47I2OwMfVcB1Lcj0sjcA7mvMXSqLz05LluBIM7OB0+Vv03HG
f7z955WtbtWXTi3uLaGa1m2I
=svcN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '201e260b-1fed-47ff-af5e-2309f83d5e22',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+IIeHPvBBY3gtmuDIs9zpBcFcMe7jSUTvCv3/iGxfI9vb
VLYMwApEe/QXc6PZXVfPjRNaLgXsW70VRC286gCv7DtknXSIFwRxY+4BJmfJzW1n
AzHZKi+NoR3/Xj2QD/JAhSefgCpNX/E+X395ksBIJb1c5v1YNePDRZDPjTFv2t7V
eiSFzEmuFhjxsfqNeFwKGUpfg5W6GEuQK3bl5u2Kk6/875qzXH0/FdYYOI/USPsN
mL7emWTzlSyC5Of3Ngj9w5/zVv2z5/xllB/nSs1J2/q4TYi2Mys/uF7pgAmZtvxX
cT2u56MRDYqOLwrM12kAz6VCVQ4m8x9luF/JyrNJO4149sdBWpGI3CmfOCjzGubJ
+WI523BD6udx5tXWtF6epJraH4GJLEaiMBZsjomJfvN5lBgmpCb505NShNYtYzlX
DwG8BC0HZZ+Za1v8wiNmksBUYexpdhl/t5jVVnQoJ8ZDbIS2A+nakzCgUDAqXxFp
+8rZzWXe3/yK6cHTybnL5WE9JglvHBrc+YN1XEU7xKtYpUVlWlB716K9r6Tlh5DV
YMuAFbtzRRaf9auF1yskOX+UBQqtL4fCEH7vVaq3s3qs+GYzfUCO7dfl5Ye5cXPY
P38FBC8hJM5I4Gs04UftTU8rI/Yb7beD3N06S+okyqHCfQ1u3YU3vpkvajhk+2/S
QQFIGWke1ptv1zC13Ft7ZRqwOghESuTbu6IxUvJnMyfM2HP7ZTZWDA/g/xsHzmPo
WZeCgOf6a2BamN7r3+btGsrX
=Ajgg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '247825e5-d851-4563-abc6-eb96b0d2f321',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//e8GMDyra8/DFGdIWgIQcpcYXOj/F+0iIYg14DQf92n48
KUu1ngcB9P6x2sfeZpVC9ZM7DAfWA4VDj08bUuZS4SnwUJWQlTz1LlDu5vZ7HZBT
zfqZxC/QJu4BGZAtPGJtYGyAkwyuKa/i6X+evtZ6kOIzAZOV3rQed6yjzGDNzPEN
d5SV1BH3s2eM4c5CgWTNN9gy/8/M3k4Q3kudbWrbAmfaEzzvtG/gT3s8cU44vL8G
WVzOAnpfhrjjgXNGLCz4dv/7wvuCVi35TEcwtCIU2ZF3pCJ4ChnKMtZYhHRyL4TA
BBz+bJPlrbodkTd/+AU9APD1z2dQE6JLwDxJE4FpzQUapbEUv4rzjnD5P2FgaeN3
aV8WgbeJIKLK8jtO6TWr0hzI+NFxiyTOzZTz0ZPdxdc5iQj3QQsrKMUa4SBx7S2T
1NJrWHedF26ARQh8guNqNMpMMaVE0I8ZXxPVYVnUhoeZuX+4QXgMknPa5ygsolWF
HeQ83yqNN7mP6so6FSMDVqv/liS++yPQGoLn5QPsqyjiy3P4sD5qk2D6NCSEqZlJ
XLELNrTTR+HAoMZcFshlaXb6Z8O+TtllI9W7MjzRXGA9TLAfMBeU1j3QlSCH1FpV
9WxDlOv6OeX2tOtBCJsjE7EffZGGUa9NECjDAnIC6G/1gBDcNtvp9TAIjUEo2tXS
QgGiV5Ek7Enq9un3J+3cj3y+CD3EctbBnuO577k3dxxSOOHU6SZTAVZ61Q6/Pn4v
JhpzJol2E0FeIITqVITYxRNfGg==
=9snr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '298c42fe-5526-4720-acc7-f522e2f94b1d',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/cRdXQWcFiaQiNbHvz2IgaAX/Piija6dpPxfjXVUAddtY
X2TR6x0qwfJFqP+l0hIyZc8a1uNykcaM1PMe96QFxX5xqw1lLizvH0n+ovWqZse6
ZHSjTGsP9b9d5qUjGMVZ2wafKX+hbzC1l+9TrDBJCtBt5rNKZI8/C6CpmxXM/A5M
zrBp8sJGzxPz7M1Phsu3cs56A3QfgzHzYBKCJ71GyowNuHg5DJtr1V2jDqZRDhBE
5UKK5XCOudLhJVi5zDNJNbg/ymrJFG9ONo0t02YkFHlpS+Q5Y809IpU/VgHrR9lw
fJVd2DazvLu/vdhfGf6Bq/h5bkBGZ+3UZjyMIHJUvNJBAfZ0+mDgRgxzq7kyvAP+
SaSMRbq3LwY3Nt+hU66Wjnn7+vphMH0KREJrMRBmKBJ+bM5BHKsyG8yzjPUKzmqR
L+g=
=2x4N
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2c0fe450-af02-43e7-a341-b616aa476298',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAjmh4zJjWBacQEWjxKuX5sCucDTjz0vcFdHdY6nj6GCeP
pFpzgpdZzKO3f9y4/QjPcvmLpsY0rRJhgAjDSmOu7vbVkmxH0EQV+18NVhP6XEpF
6VqvIaPkMU5tlAE6rNrCxvlsr8hXaC5fRGp9dyOLw6jz2bF4J7lwpquSqUa277Uq
3dq6cHvT40yXFSyWObU/U3gY+KxoX7THshpVEhhWSgJbAy3MocKihjCM7tHcu0fV
+x/dz9Ghk5PSsTdD2c2wc8sHtnkPdOPFKePd97q23a01qEsFvwS/3WkY6u0qXTns
ylJuJkxTADvcuLgMyhywwN+SvEEQKmOYgygGZdoYej3zungRsZsRWfvHxlBVqicA
uprqGIZ0FxJ/B0KOhYgSm220/CIoBWoJRrVJQPELe/8Zim6J2VirE9qepb21nhx/
WsCcyu7RFPW2BM8LfV2GV/KpQWoMo+k3GTm1TJcJYIZe0KdhfdZx65BsoQe3LOZe
4GwSnMUpoOmo/xa/DoP/5EkhyJmKa/cDWAE8i+37FcZdOqXl17POv2vr7ZiDR/s6
aW9TRx77CpYn9CcWJX8WsGvgKRvSyeGiOnZBMP75SYuuoGkaGJD87cUwAi0vSPlv
u5fmnFTyGDVNmVnZp1uW3ZcG+OjvI8on1ILTn9fQ4VUmKfIS0n6WjoO5ApHriKHS
RAHvD5w8LrXmwhUsCHWdTu8ST3LChFFCRywd326UFKJioDpF2V9BtpOBX9bLyJYO
25I+GNCsZfSEBHyBXsJxGGdErXoT
=MFvB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2c89f987-538c-43bc-a9df-59e9db68049e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9FF9/I3sKdF3xdvV2/v2yuHLgiq+eaHb4Zv5n3ZHhhhPf
WilDlUJ+4luHWV/4DNu9A0fV2TKj4BMS5+ufnHCUN8MGbO8+X9fYTvnB1MCwFiDS
zYLwWw3Razwh7Phf3obpUy9rWCA0TgIeBMgbtsd6LagNlkPfLdenAOFNq5/0Wme6
/YD/Uc7XrxOt0FegYzc7W1019eocF7fXaaDjvgHgsxAN2bK8kxuAs588HGgEC7wG
UPoIeldTviv7UT2i8sGQtLHaqT6B8WFDOYEDcXImBTQGlaR7528M/IXHb+mefwg4
k+e4g16yuv25hooktqI92sVc5DAlOm+XcMAqgLAgsG8njevZj8hVcYNiapGDWazE
N+wJzYi/uTftwgUvH4sA+/afOJizUWI24cdGy+tLYtF3KYIGWMdbd5YBbL9yhXTy
597dvQ5gIKZct/ccxUQLHeVPX7aQ53+NYyHmQ9dVlkL6k7hzgkZp2T209cGjKyRV
27MkiS8xVDMf8PfMOaj5SzNoM/1FTdUotRLwMfK0y/ulbBKAhPQMxLeA4V98wcwz
3Lb5WJ3wU34V94IFJby1I+gWy30q+nkGMT/LzBWMaT0Y3Jdsu/Nmvlp6wa9LUz3z
yMtdW5QvtSXPuApLm5MH8wxaQWbSgS3Yks5LmDcpzO0sewOAxUqvnMMDOe4bVSHS
QQG3AHhVWYb7tXLvNpG7YwvObwAQ4B/15GWTc7kr/BwYlli7jGpxK5wxd5dAc+WD
1WyILmw/oxxqBWgVpMiZzcRC
=hho9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2cfc944c-04d3-4b2a-a143-730aed69b84b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAmQ1mL1SRWWH1OJvbWUYib4UMxRbroZM/hgGX14flrEZt
WDUP4N2Ovc0HwFiTppC7FR3cxYZOG9V3f9dWB7G0B2ZzRsdUVRN7QF1I1+GK3cTj
a+IIZdHOhpnn432wHbT9+l+BEFHzKe0FWjSWcvqj0WCwxKjwXSfYGVj7x7CTQXF+
Xnr+GdHtYLjTzpdr4Bni0wLAYBu6O8gCD211L/M8zsdHzXFKrvYR7JCxBIH5cZlS
AdFIxMMhaOayOvEqJIy08Dc843BJD4KDdy+uVrGton8XGUtg4MwvWArWG9YWfRu4
dKRF3ywBFjbdelec0cuZ3jfqBNyzRRccaAdKm7cw6DHOPw09AodObCmlF8W+zEpG
vFhLEUlteI6KoGvrfOLhQFDJa2SVe/lIHCwdtXHsEAKrbCJmSWUlpPYUVdZqjn/c
kR7gc0t1MrvJYUGd+vYXMH66CCo5iDpC7wuP7tWm7PDHnb+BwyZ6qWxr0jrpX5it
BfqbpDNh7XluIFEXzfPrD2AXB891jiwCGejOLPJXtJ6ecIy7BFUM9tBNRPovhElU
Xu8ACTEUMSMXa+yVPtBYjH+n1wVCRqbEEkLVIMfb/V/Q0+lV8ytYnBLi8zikhWhQ
1EqQtlWD8Uu1Q6DcyiQj1KxEotOGur1IeYt3uhS9rq8PUSPoMxCZPaBljvgyujjS
PgGyofSZUy4mD2BYjRJeA3kz8tlWgsAmMdQv9Rb9cSeASqWzm7axrPpYsQhg5qjw
Bouwv/yEWetskSZDhz6V
=1GW5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2edb878f-3c03-4e44-a852-35c9d4657437',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Qr+rMw9NCc76pycehIt4vLzC+7v1Msaxw+gER+XLgIBH
ostn7rolz+ikffbooE0tFM3Ghv+7Ipdj2fjO6VKvyZcQ69qAiwA74+2coufRD31D
cjOVmOb8512dqmvVSq6uctoJuZIDUzy/I/5ditD7lyZZz1gvjK4ipDSeMZU5SPcW
2bhRj1ImpCkFNFiDuqPVMBr3zjuK9/nz7ztuwvqH+XvbYgHVQutvcbseeJN8Ax29
Hus7Rk2a1QbLz5XMf3dAJIcPFW2J17A8xwY+CDa+JssHjvyVHYfelw18SNVHldF9
EZyId1TvXYHrPQwR5ucXKM4Bty3GfhyuDbM1tOzH6rl6SMOTNFys+kjbLdjJVFcN
BvkHNsPVXNuI1GYGbJVbs23UeUwzu3iEeHQKmtKYUpBrxkIR/GuGjIL7go6v5+0Y
HgT1NM5qmI9D3FtigiASXJdsxXyURs9KEAnT3OOBOhTYkK4eJrsYnHoDCubflOq+
FR0JhBEEn9bnXjnRXjwCYnJzXkj9HMZuvrANnpKRnCVj8467OPh/MFF7xcbF3Ma/
bmHjqkNavGmhuhAm9TRVI4rvCeFR/TEJt8yAFDuQzqhn2bNWdtV3zeCW5nCh3J5b
RHcEpGJ9oJ9uXjzbQhM3gHp4VXLI745+K5chS9FzPEKzGluRqlGjzihht9bEEAvS
QQGwgE5DrZNl0qRmnkQ6UaS56ioFr+M12xKOZeTDykKD1Gz7cYxgA8lvlFgQWqb/
79u9jnM9dpKPkrC/WnH+Goza
=7aHX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '37d2a2c9-237d-45f9-ad42-b65d03f209cb',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/Yj6VYX+e4m2dRqRWUKdCrq1TlOo3IQyfJxMYeJP2X5V9
QGjYcOHHewmfoEdbzoo5UeFd7etIrH0d1bp9dr0K8ttT0zPU2+EcIlyjqs5lvyJi
RkNHQlYR8O4HN2WX+SrS5T5coBZxhoKmKg9ehfYCvlBTffs4YFkm3rK0rfZXVB8i
oBvpm/haC+Zgzmz9Bh4PphdzMBIYXVOwuA910eOKDOe1ekaL8/5XXYgNHPbIdqg1
t3bvwmvnZ232Nc/ljtxFW7me9pP+N7A9tgLO7kwglt/8/s4z6ksU3iNngMbOAhQn
fnoS4A/KJbpaKeTeqWI5yUr4dAFlB5dYCpFovGFAwtJBAf1i4c+xS5MZHC8W5cxq
7HKHUDLYvYnliILpGQW0YCNt/CGVQqvkWaCwOs4E/4LHtnKjgunIftsdXVYre3No
x5I=
=YIFe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3d74ed7a-8215-4b58-ae0f-fdeb3bce1733',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtJ0ANv/yW8L60junNMLfwXzYDAjTV2GZNOp1Do2nHKO8
+pVcsZZ3ZhbxmAHio8VTdxHwWpNdNfLrx6W7KXJHCUY+hcdJ8u/2Fm3V/Y54Ox9h
bdJIYMPnST28iWOBZbLRGqKKC9aqj3FpOwdQLIz8wVyWLF6pJcr81R+uvTLiVk4n
fbj6RKQgwVpf0rFQ/1szZj5C2jRPgLbb5nzQsQvI3vkUMwoPWmBx/y/v2uLwVH82
/r9J4QmXJXcSNJ+z+mPZHLQgwyZ/sKmUHAj/5b5sxilRCvqF9xRvV/05FIMvk2YG
x5U2vDGx2/AG8S7WwOnycGK+PQGFYO1xPGCm91lKFR8yuqOzOBn/HOrm/LXGqru5
ua21FlfdxPWucqHwSALg78tfhBCKfVowWtV6HPkJz93f71xmGUpVDE1ykFbtmT4j
MAV7c8t/wi0V7uPsgIEwhw+u3p9CjKHCW60+rhxilCXaU3j9776RATI1q2xDkjjB
gMyO+68XQiRQiM3ryf4Evbo4zQMrir+5gRN2JwQK9KAWQuEakmEtaFc/NywCSzn/
vvWl7iJWsy1/NKfazkVILzJZhsr5Fj+GFP88Cl26aq5HmZHWZQHAh5FFJypUDgxd
RU9Gt0jYgYrkZbomcLyDuA3STMvLlWjVFSQEqJhkYaiU0pWlCK/lGWElGX0s2rfS
QgHWpmeuQTBrorr2oQAoRHu1hN4B1URZK4ljn7opEsPZ6aYdJDUenPEB+THaqDDp
dZyYNo+Rsujyj3RZOUC4lFOzJQ==
=L851
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ee0981c-8e24-4670-a5fc-e389252eda31',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+OVAXA1hnFnJJVM51v/17WwFa3/LF2Zu3kMZ0w++IMF9j
65bLyAab5IGzGogPhaHy1f/RkIiiaGlWVO/PJuO2MpnK5nGWMz7oUVQL4MlCefWF
knGoi32s4ZTLbA1rN0z3SO/ljBkICY+LFA1r38KJpQF6irks8wAhpmnU11nH0pU0
5pI1GoR/jlOMQNlx3Myr8/tP5aYAkusKj55tvSQWUOgSdR6rqMs5XtExNvKpjNnp
K8C1N2fKTlh9GmkbIjVr0C29I7COJ++lOcTaZkKdNke9DfE/ivBzU+66Fw8sbbUp
x+SfjRzwPbCGlVtETRvdS3P5ceNzYDp5BIeeACMxBpdoEdSEqxISHL83B7tA2n6b
H8uU+ETN/UPEHoJQwFnRDQYZiVp6okIMxziY5WGVHDdAqkJtNX+QCs256/TVypDo
jvGjuF7yxBOXdY+dQyTz+zOJy7Bhtz7N8pUYTXuj0gjlv4ok7TsTfdBwNWZpV4gu
jw0WESPrQrwbRDL9CjjMnbOnKMGNom23DDF2izntjV8KBIAZ/38LG+Fu39qj+tPp
mwUSnjhPQUj6WqmfGLZLeerUrFnhIDyMd+lq6d1ZYCOEHYi8+4U92wIdXfkHjmns
PgNIxVPKUcRnnEjodXLueRVGNIBeLbOrHFBjpJu1to4h6XMhW6qUvVc43W7UgJ3S
PgEfVBdI3va6QOju59M88oOC9fmNdUzTwz7UGZoldDoK+YL6/ZkREuGpN/y/Di8S
aY2/VLLDtKCGNF38QMBx
=LFcq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3f415a80-8038-4b46-a4bc-e813b0db680a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//YmA1ykZtdiu8uZ+hjamOOe014W5MIfPvxzd3Cp0Zf24G
zehv6ueM2cjO71uwuvNPMhTSPk9tLpwmbfWq7COT5zlIj/xphKfSNHihWF7bGDxw
fOzKxjUfE5VAKeDeH49Ujzcm8NTozNOSS1BGdHjt5K5L4B8dbJhAGS12sOxPS4EF
3eNParZ/QvyMzAXbdU97eaO7A6ER2O0y58oc3d/+MwZJ+Ep/lgQHR3Wi7mEm+SqG
yVvLC01KNWJfVP+foYAF4K0HR/yWnrUrF3eMMQf6ejuU9d5GEKrve2KjSSEt2wqV
IQIRONqbA3WBXEPudSv3eMeo8mEE1/2NO7dblnfpTncB+KhfVH0DJurJlUJiZgVF
GPlTURDbwjg1GYI2vXspCqMoMOxTlwQgJps9WDPZ9T4YNtCW2bmHAxJ2e0ZazOfQ
axuuJUtxtQSLlu952A6JOxEfMGZJJydPf1SkJBA/S2c5STT5T9mzt2x3GqqSKoO+
ieS1pKf1oJQ7rBs0gnVLhVhBuRvEM2HFe19UmW0htzp5zVmzxPcRXNyQajD2jr+J
AI7+riqI+lslTy7+an0Sj03dWKZq2J3ufO6t5FMaziyjkwNdqENic/E+tH8c4KSN
Z2e4XGsLIOcQdubBML7OO/FU8n60kC8vR9phsZ3acGqIZ6P25VyzM/6+fc2u1UHS
QwGj+Z7lGLAMRdYevxyJN57ZU+lShxfM4dVBXeGukqyqX/ciBeSDg1e2IoUsKy1k
Zv7jRl3AQojaqM99r2GdrZyzHE4=
=ndVM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '447cac07-e1c9-4463-ad1a-31dbd9fc5c2c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+NrHs/GdL3xOO3C0pp4cAYMmgSsCmCIWJsyydEvjXAVNu
Hr64BmPJOqI0XkZkaeERPAVQ366Cpfo1Tx3fo5mTYeS2Jkb2jxm+euBVnD+UprXp
j+U6FZBHCRhW84PzBXx4zuixYwWIsdW7E9VKrkfpglbjqh7l4UdRRhR6mT9XHCum
bZj2Qvy0vRilvVg62FfpJY9uVDQESIdU69nkWANzVU8gIKjSxOXZygTDuHiO+Ctl
azxrVz31fq853egI5yyj4eg1z/JvoYLjerQEVHpD5OYptflQwKlkozrC3BdHnSvh
1a48fLz6XCH6ZHixQd9zke56bgoR9V79i8Qe5mmq+V/S4Lq4OMDXgZyhZWFjGpaK
X055v8T1QSf/xf8S9XlE5NHKqaFl0sYPQ705zNn+/k4B0p4EPjylsYz9cUwZ2j7s
T6u4kgs0GAO1hmIYpO3mc8pILWpOweVtF1/fn1JPfkqqs3vb5m44y2XcIOc0dTaQ
ghXQZysezOTkdrTnNoWK9Kv0g8GldSL1PJrfXLunUW3RGFUhMA557pTWGt42PpFN
EwOV3TlD8zXKJrfzpAMPAm/FE7ZCU9dgAFhdcEozi1Jy3JFCGmtwIPK2bp/V8OER
tL7Qqq2rPBjB4r85+zXh7yd+QqQw0+sWsb28A7oqCGF7MeYaF4lBAKSt5EbYkHfS
QwH4SVhuD3ffq2vsWGcKqHFtMJaOYKmBVQVtJFX5sNSisrT2Ge++IeWFFRZbnhcQ
rOy4dgGC1b3KDK+jPrZ3yjBliI8=
=lzbS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4dcb255e-03f1-49e8-a5cf-cba882b9cbf0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAlBL7q4f5AaFADzWt0QmSaGxQEC/HM1IPV6B/UYqWPJee
9HsS4bP/Zs9mCNn60PCWl6VvZ9OZgaT9nMH1T49BlO6/paDCrsALKNrSmxze1l4v
Q+8siv+PBxTjkStzlxVraOqCrsZZFTZJ602gZZJ2VaApDl0Y+HI85X1RMXWkCPre
MYlHP2V75cIvQRxYiWfX1MfxawJc03qzEW8CQDSnnEz8b+TI2W8RFDtfr17gJbor
QRc5cpu7+3aCvWvMuWIufAiSjdteDPUpPe2oDV73s5TnKA0pU9WTxPzdPmdgxdWb
AgUNxEJ5AnJjxs/G8K3tFQX9im+ywbio9K6OMuhVFF1ioKPla5NyXRR0cGZS0A6K
8qg+p+POdytAGEVFFH9LIEnnfueq6c0bKBhrHhc0J7r20PdrXj3oWMXTwpJB+X7H
tebpPwNV4fmCBT/nQP9dZuFZxX/EDKT/7ZIBZOsnkWfdgMDdn6YFtFtWqXvuctT+
L2qZKsWURCluF/F+PdSg5qEwi+JqdPEvAqya4+4Y+jPPbBN9TulggF1dfRasfvAY
K0166S2J32xXecZKIvwoZf0eI9W12W2Sp4f3r/FCjOJ+A7a+WtyMjk8eMPcJjcZb
zLto9pZcYzULhwwd79uJiuqNdNMgeySuyjq/0uvNaGf2iTXvCaQ3CpRp5YEy0TrS
QAFOBXnlevjLdeYxZb4LfQFcuPMCetrcbzRiI8V0zvZx0y6aA9oe3/8lSF/wAU4T
a3k5nzS6ZEm11C/jSJjWQck=
=q9p/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5057ca16-a8b8-44be-a91d-410a2133a836',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//cfXyv3Zxue1O4akqSAtG/2iRqniZoDPuJogEW1HVqRZ7
0Aea3Zig0oKgVPLCuO1HgxIitLkYUQKBNr3Bnd6y4UCEj/9z2hESx4766kckvdV0
+mdzDnvkWCNhIlXKjCkSPOg75f7GFbFJ+zypmedP4FAHsWPWP4cHwYbyS8jgWSEQ
3vAj5JLd4FdnGETtlzXuRtdOn9ZkAKwOnQOdRETCNej5HrTx0C97abGd/qEgoEun
jN+ol8WcDXC+w+8tnpgsLdvdG0PnH6ChuHCqhzWdEawjHv9AkZVJzUYUeoyvxpHw
Hk8RZUVneQkpU7ml4qxZghcFdAcNrUn/U5VoUno6uJZGq4w+Fg0nSm83RbLHalng
3cgVwnNYAQld7R5VZLbTyv96UlD8siKHCh2iPpaz+hMlfkApUb6l2mdwskXJjP4S
DzrBKPvQ1HWX6IxYpk2Eaj3Nv6wcKnYC8MOQrZvhFVi2OSaFh2Kn9xegnmJqMUAC
/HC7/oEn45cF3GhYcevFqTpBc9t87kp6fOnM3yZVokrr1jzQD0rYit1AdyqA79AT
HApxms/FoCECQloRTemvxhEdTRJWE3iOcP8W19usRWFslVl2wSE8r7wEaDCUcXQc
ixnCSHhpYDH7DnRx86LIjWlmhpRf6tTAA8eW0ok6QGuw5a5T7VVcSey8xLbuYpHS
QQFQaDp4Dd9dEDVHzPS0+LLnG546ra57DaA+oSgZvLWhtLrrV52XzM/wbLppw34h
41RozLBdRbUckSI6ohFHaQm5
=VWqa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50843f9b-6f1d-4bd3-ab05-a5dd26697e9d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAi45sQ9h4BDFa/J2dFUQqWEV85KAZ38UvEruGJKwSl+YL
xp2cq/QKgGZmrcj3+fEm/vy5fsLRwvwda+Q1diHU3ESXbynB4KsaD5JSfUr09sy8
OH62oLwf2KlhyyXMYJj9uKBMuY9Cp5RUNkuz9aDGMKJ3QhZSjUt9h8FUHgzPXX1Y
gE++ah8GyBXjjzdLo8k21KsEnewHx343llsUj8a2DUnVeRtp70FOP216hLX73BAW
A9X3Zs5Fb9vVeRC0pfNs7MY1txdGLYofuIdvNDk1T1vV7uqZmNn3FGJTjO2+/ki7
DNai/Lfc64M2EUL1tDEQ3kJnumw7W1CN5qfxwQN/5pZzz4fQRGFGOW0KqmZbAXOb
J41ixqrX8Aann4lY6F/GVtQNNBqbT3kme0xeLogXcQmf/Mu6Ahac8HdYC7p9fzmw
kRFQ8aXoJn2Q9mu2i/mpa4rJstQsuAmT+cvDYXvZtb1Yzh24FaI+UuW5oPJwV97a
Fw7a6PgF95S53OVTYfv1q3ivroFTJIGWn/hajLT7jV7iTsK/XbS7Ypr7zjwWgo+X
Z1mRyV7LNx7J5VBNlZrl6poXg/vS1+EKg/GUOJtqUyFm8PtV31pIsYIJn+vhXcMO
iVI1DNurvNLXfiqF2d25CvzAL0exiXfRaj9HyZ8PzCt1rfHFvW5E2uDANjNn4KDS
QAEvnMS7tBSamf0+P3BQmrYajY2YaHaluIUjbhVoyvZSlLnDoM4H6rWhuuEkgW7V
Vtefa/Jmjj11M2Duz1lTu+U=
=h2dg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53497234-007a-4c9a-a4b5-690768a0fe73',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAwGt1VoTlsfI34FYy8akH0EoDIIAmtRXoLSh9KyYa0Y9m
0RuxP7/HuNsAf/RlnATbz6zuJnCUlGe4+nRoM1BDOe8CpXEiXuLNaLXMqkEyFrJ8
qk9u47gg4QVfx370wFxI1Rjju/5Lzirc2J/JK9WsNxizYqCtsH65Gcq69X0bB/ra
Ew5W/E+I/zQTzcRQKv4uOBvbUtvelUUxMBaARe1AU560oaa9Q3/YdmrBiiliTq6a
rRWCf/5DHwVurdnmGohaFbxtonCzCmzxmSr75Z7UFtmio3xe35/7Rrc5qsNuTfu2
8s2pgH4jUhxXZ2HAe5a9qlQe4uoBR5hjFcbLIz7tOw1YwRKRMXkEZAcUkNX3Zk9/
X3HIAHr0pL+PxgGINLcqVfviAsApHt8Wc8qy8Zz9GfwRKFSENoaWjJ6a92z0Y566
QULiSGPRzndTMn4j0GBCg/nxtLdNUyh/Jq2HuS0T2DjDQw3jQPGmdwREMJ0pMJgu
hwmv3ckUFhM5KmlXv1/7QmvyhTbyK96ZiO6X9536ACV6A6He77EEhmZE80NWdhSX
3n/1nM79NyVit3TZl8yNRSMu65BtUxu2TiRoLFOmaMgbB40Bx4JYzEyx71XmCCyr
J4RQL3JBl7NjS0QQ0fkT/XE5zRFaaWiv6iibhITKYcqAlRRp2FfIt2amI8e4yX/S
QQFccczFGvFBsFYXd8MlSWMRK2q67I3lm+Aa04jvoAruCKNmM54HWS6qBkjPU5O4
QXCE2yxGJN4IJqo8gX386aBO
=Ahz/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '539f0d1c-7200-4a98-a29a-17df1b2bb62a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+LnXTDHQ4aZK8/ADrS+LiZUKC/H44OBwLd2CDvTmmt1i+
7PYZU/vBag336H6ZfuTqPOuBlSdBkGkHgKypZ23VHJTw2e1bpj77dq/p/e+82LTl
ribFLZgOrFtDgae8ePv7onfRvAsOoYtoBjzeXUoo+6NGOk92B+dr5BA/RYnwfu9H
7HlNCXj+Ki2QEu+9EsPKgyVyLQrh4FMlbkZWxxfLRgcK6xf4m9rJHwI9g0InLiXN
gWuDTuIunm8aGUffB8zJfBO9jaUSJe0hOaMSecZczNXvkdy0UBiznJLiQvXDtHkz
3/e/XoRM6XlZq4k4AB3nBzaR57PQ0QbwcFJxvkc+ISQg8ffieLulWI0tktbRp+8H
bm+ZswEBUFiZftTfqPJ193WsnRsCHOYKMzQnqFQhLPaZDU5UyrDXUON5fG1vGuSH
Zjr85IHGcHwN+E/ezW8NPmu18a1FpWW/AXZVEGKDBfKv1favFmY0i37knXexIhob
//yzXLMDDTZWcgYC9Nq9phhAokW34ywIhMsoglstS6bAW7FCbPb6FZ/cfnUxfqQq
u58vh0wMcMjBP2mbJ7X5DCDQ86+XmJtbqkZ9oaXYuzbEX/qC6Vc0tkEOr9Aehji+
9xIXD/PzUtvd+TqpM+K1l1AegzIDqUO4wZrjoBlxg4ql0jzjtF4D6tY7TTKYBLDS
QAFk0W1LrTnoMOfavCA9VIVJh3boEKn+nOKmU/2CAAqYXZqCj//mEaOH9hnBIajz
mQKf/4O6RRzA2e1ubpPMqA0=
=1e90
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6342efc4-fe71-4f4a-aa17-770c5ca3eca6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwFd1zi3wPzAcOoG/qHosf5SP2Na328lILqt4hKJt5wNx
XfF8amYv1Ty5NTZSepRi+IwZZOxBi7xiN4n0TuS37geXkmEu6GVXN9RCb1WLN3gD
H+2bbZJ734lpJyuDI+hn7nwQ1PeN/KLyoBqHC+FxNNwuuCyZwu6KmTbvOXDrIqox
M9+Z+MQ9jdd32bbHcWAP+kB2jXWpDot3Qmip8JOiQ+sX1qKFALwWGk2jb87OggbO
LqoLCvcX2TrxW9Ow/ZRwIACV3b6aCIX/qOL8Uc+AGxuVHsx3He3QP//+KGULIOlm
kM/pU898v9BBXcn08ggPvkksdkrTmIzAYs3XOAjned0S/PYeYGBbmWYJnYzreb6n
IEbqLjHsstEUIx3EZtswFNiyJltWV/mJt+BJuJVNKet/R8MZ5fri4X7KxPjfjb5G
1EBXukeAOM5lpFwm18FW+RiMDiAbTGiOYqhjSsuVLansmuREXqJqamTyrsBeTTKs
iCwnk5GoRLKlBr2ggpLrEzd34GxpECliUADUDjUjpfYWDuh7sfzt2xSP+bAnNJRS
tueQ81O+tY9AZUnxm1PGW1UaejMIlSAZWhENuAti5Bu5TAcpgpjA3T0dyUnOByq9
momCHxskEyiHO7kijYgoUiw8S26ETiuA/jPrfLGja+alFOQtL13X/zq0p7P2rkTS
QQHfL3Ibt2sr4J/v3poo+hjD2Kd8FtVi1+JsR1itWf6dMAglaSX0VMJh2M8GQ3ME
Peh+0DuCK0/YmoprncfHsJG4
=1JoO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b8fb5af-92dd-4a66-a1f2-304a886c9b96',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9EsophCF0nXJ8KObhpZu0PXobzPxDvKoWAiaVHHYscquo
jUgM0ea5lLDUr5lsqrRJiDw1kdjZ7kJ5AnjiSctGlURfoP3iO5HGFKDiH+lvpcNJ
m7ZZvGSFt3/SP6qv5EITAxJwtYQLWBxMBszEZ9Wn5qYSUtyF+BBG8ysi33I4zj4c
Lmj6NUCXJk5uvQkaO8NlwPf17S/1IZSwTSo1POydaoiYe32OD+rjbtgXX6l/oWGe
vflZYzHYV2+sy9CqUly0+TbAt3J9zJwBRhxXMbU/YP2u++06v0Bi+BWCz+6/iki3
/CqhQyYbBlHP6Jk2D8AUVjw6NNrx3ENm8V6Pm/2fW6OItoyHOHyZ2r2jXb/u3Ey0
dqWbqJoYzy6+oP6JdVUzzxrzKWhe0CQt0fNFyzWv+FjmKwlsp+XPwdPh21DDP59T
Guus3Q3EKByM6WrV9X6rgjCPl9KxmY2egC/XhuizOBzdDV7sE1AC12NHUFEQWjL6
uacm6R8QSsQt0uLoB6YXxxkaTDmSzQGS4mqAirqoSFtTqj+iJ3pqBRd3JUpKsfVZ
PAvPPinJyi2Fr6bZd93LzmSLKsNgnxPy/G7mPGE7D1WsSNcQl9HJ1sRukNb3p3+J
AxKjuEzbHkXR4QRCNuXyJQMB8vxTW/CWgn1+GXqwBhpBiimMoBStuJmf0xB1WYbS
QwE7asJQ6ztnzTqLPk2ggkclMqqBMwaIkXcqGH0+wQrkv1MhUomI9ilyVGWHLc/e
NGlBh/SAFlexlaZOmoEGAIFcd5c=
=0oih
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '735dbece-3805-4167-a932-bab3ab699c98',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//fNZmQrvfXm5pWXgCJD1hsL5h3nYBzIQj6EYf7WyWtGc3
EzCuhPw4pwVKssdzaGzmDdG+XuEDTaKwk3h+Qbm1qB2I2tuiZEHkcMKu10RuEjTh
YHoqOqwLHibA/m6zZxpKNVw54bFUnp4J+t5njElIAC7SGG/PZPX0/nuay/r+r7so
1PllwmvQjVImPnfVzGMQ57z6RTetpMpmrBX3rNUWOttqEHRKeK+7h8qpZ74rgyZd
yqv4+2ZHGSkSLjSaFPx/WPAxOj+gjGPLHaHg0Oh8vdBJBhP9cjQyAwo3u0DiuI62
/k6eOUtb4jFBqB5NhO2GcHZRTXA36iKfXO90BOh3iylkcuvmf7/fJrx9Bjd98TZJ
z6dFrx1qIBCYmihGZDtl4IempQeU6RpcKeaZvUnybAe5dUlVvYNOblrUlBMCDFt7
nfdqms2GMMdlSFC0/rxre8wnBUAR9tJitwEtcQIBg9uUA7q32LtgUCaS+oXg5TmA
MWZZnjKH5F3NL8FlzKmiUTA0YcBR0eEUhstiP/C683KzcpO56ej+03iBs0LJx412
6+weRfin1JMrazGBsf30A+yCB3Xm68Pdxwh+QHZjjXWjB2XSnllxRCWZ3jF17IxS
B5nNx9VjmiFPlJzD6CgaFk2VA+fFYXQBS2c8Mhmh9OjtqBqLMvYSC/+u0pjJzi3S
QQHFDRfGriC0aGqXJdoVZFizmSY/UhzksGXJQmE/k6grMa/Y8P2e4mp5nLaT4bPo
T86Ek0DNt2B4nlaYeDGbZrxw
=KPRi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7d3b98df-c229-49d7-ad30-d4cfdfcb25e3',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAs3HAC2yYUZYBmP3LYbBWbI0Y5A6NUEO9S9IByPubZt3S
GlnwyqA5jJqnAg2OGCmwpGo/oiUTE2akxN5IkCeCSUdT4dGHw8dWqzYd5X5nmrQO
PqOd8Yn1s2AMd7UbGcfQ864zntLFWzTnFSxvBZWfMuORo1Ca4GKMC+4auRZ93F/D
gBJYf6PCD6jarBM0su76OJb3qkoGHv26sROM9kwkpjsRg7lZiOnCbfLRXgwbZg8c
i+NLAp7mihwW1DFpn3yHZR/7nAuSCZRSBpd5AS6D7T0Q7md+ExBjrK157i22uVKC
UAtU+NkQVYgt+pKAaiJMc2puH7mBjJ/yzMBliy5+gbWUW0HeNsd+khEKgaGGrNFd
HKfBRcX2Y53au129JOp2pswF2aLLPuKb+AtqL7s21IZmTcblhBvlbiK4rvBwDGDt
UMY16v23rOaJ0y/Xl3PznpGKyYtPxTcjpX06/96nToF31GwtG4Vr6cfsybz8I38J
CC9+lXnW7GsRlwkGf5k0O9cZTA4RTBA7NzB0UuYgu0hjrku8RK/5QGlrCdhdr9b0
EqD+EtO8r0e+n3XSz1sGN4/s0ScctRHWlKGj1CkNfIFi8pwpkKfn4IRM5KIqUvJY
yTmVa9G/nIvKWyKyiFiX6bEz0RTd2x+//7uivSJlhrWojJoZdgMjgzhsvLMapg/S
QwFzkO5mbccUpYHJKACFVgxXYxigC0Eq62HyVwiv2BTfL53l2gEPpstmJk9pox3t
G4c6mbitJ9pvOF5bYBNG8D8RyGA=
=U4+t
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7fbb07e8-e918-4a1a-abb6-b64016d016b3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAiEftbywBWU3rThb/oGnmbHFbA3B1yJiXGol6Hax5CGEV
VjdRDXSnVQGOvomGCYPztLNm7qxm30nvMMF6RdcnnQL2pFrrMt2n071ACvktfhLP
dwnhwB8LhY50S2hxl++lSOrjISLALIx+ocaM7npn1E9biIu1DYYpT1crmIB7/a0X
kfN62rfBDb5EwEgn2ntmPk8DMzysM9hX8El8SEvToZ0pajvhw225WPiBbJvYbYIR
2JVqF4OJ/n+LIEF5DKcoWoUnASj6yLG+QZns+synH/KTYfPYCClacXUo3iA4QW8e
jA/Jb7abQMX4RfejsSffNhXmPaiZRxeFYIH0j1x5mU4kR4Ccx/tGQ2qIGZi2LLYa
hgoCBeew/tWY3WqHTJQQmQlh98n1kwFLjrIi91mbLLodt0UzIuz2ed+I3LiCkqZY
4oLfa1IHiac+21EHJ5WeC2vaqGoUddzGX4mMvb3zLLohAjAR7rTXjo3APzs7PxFo
qrun4BWrLFo7f2JJrDXg0nHfNXqc/Qvx/bGR6Ue4QeD82f3VdIxERX5nicl5h/mk
z7jcZmS6dVOL9xUPZ3FR6oOnScJZxu2xoSMZ/YOmnXkfi6FoKf1TUl0KlQaTmex9
8RLH0TBV7JIKj4WpPCHRzJsIjuwlcr9VFRuz0hMWR+nDlHJ6LwEd3ogx2RT8mCzS
QwE32j0kLcV4r68OQY1w5KdxjQSc9QW/uN0nfNsV5sVbHO9vHp7Ct3BwVfcK4j4g
rdBM9UaW1izLb9EGTIkzys6Hk70=
=DTGj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '850bc477-e897-476a-aa70-9e256e520a86',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//bN87zrzgfEMbYBtrbj3KW4mgewA2S9cqRVBZvp/oVK90
aiw2VZ+DMz3m5sTKYfdStbx1mP7vXaloM0LBmkheyDCCUU7s7mX96JfyNuVqazNT
cutPDaq6ty4b43NJQk+4c22bEIbCaRLs0G3jIcqKGw8IpniyNO+h4g7Vadi9VgSU
xTQuYspdpV6f6A5SFqIEfyk4ko4Hh5i7hcMMfMPEkt4s/cijPSt84Zh6xHmtSA9L
RvmWxWizJAZ6KgyzLilgQautrpvrhZO6+YN9rdxlcH19p2YA5PK+FG9hGRxc/vWz
tLJii/yUdxc9h+pRPmzV/dKmoWOnCd9VLcESi3VDSOzRQ7reOphFjukusk7X2ZRs
A3DQAIn0+IUinOTgA5fKMh2FnxVMU3w+XJEIKg9wcp+rkL7Kuam9+pA+xlHxcMv5
NYDGlG+IMus0TEMO09BeIn2gkHwQ1NAFEEBtnyXksbb9rZMxM68gC6NzhCGtZGit
Q6ApyCv0UUrwDnyJklYTOM8Blbp3fKf/Tw8/5Ht6AK53QjCC8kCAZM7lQ8F97YZg
UvbINd8SwB2pwhfLHDN0ZSLcJ6glsyBmJYwfeEMBlkPBhW3amrjlSGw6N5NfAmLX
uoeoijyDFBqSEPx35svOx6y3aC87iAl1yRJ3qbxghyAkmoIXLeICcacdL2abG67S
QAG3qFvQtpvgS0sMHQOxeJr4smP7xELJQMir8q9YH+NIbrzzCHZRVpkuoGH4xlqw
Zpd0su9/jegKpXy/O+zFHYI=
=j249
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8658b4ad-ee82-4929-a08a-3747a5f08a52',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAs49vFcE/Gorh/mGUJA3k/QY/3qO3ZXvcGyd2/roA8Nof
9QVPWCjAL8rp6XuQcbeSMhkt5HENucpfHC05ksPXKX+Iqqkn1yBLXu2akQENCMDr
+Ew52FCubuaWuhxusDBjFSijJkXbzogS2O443S4NA9yde9ffFuonK7e8vfjyoH1I
moFy7phe8GgsYlIYpTDT883UrDbwUr/kAnHgmDhJGK/to8QZnH0OL+7qdBDcsats
nhHjXdXgw5EXO25cWn0CDjSn37lyu/GRLb69SgTN6LIlV5kdPWMdEilLcpPPayqL
CHmyrqyhb+b0sTD/hbLKeHmrf19TiL+lOYJaVF7j+8EO79jpk2X0hETsoMlFkGGc
1xOLrxehUEWUOGcOIrsveb3p9DSVFdMAEHfsEHwkb/nKbAzptWaF731MF4VDRBPU
O8Ha0+mxSqnVac9UiRiAVcBQCJ8VPP3EqG0ypmNArOZqGQwAEvRmiy3yJ/2jP+hh
wnS+5BExDrifIg1cw4MRf/B9ZXu/x+fLE9A3ZIGNklzzqjdq1es6MC45iiMYoQE9
eVr1NQ03z8kvezaDSqbWF/dQdcodi5EtOvB2OYZSY+UoQa+yDRyWTDqRcjJxvr0k
CdDYRaf9sRWTTsyhvxuJh8RU+khxBx2yNJY49S7HwDfGNqNfY72Yb+ddRHRZuOvS
QwFbzHUj7v7jlxBcPjLLOjxKN8n5anUQHSYZN0Z1xT39RirgZynwXKQICy3xyyKi
Zlulr1zlzQaQizQu3shX+PcYYBs=
=P597
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8752f17e-5690-467a-a5ce-433cd492ce4a',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/XsMZmZ+Dd7VhiH8Ifw55e3y6bAIzSYqS8IivDeJ/4PH9
yGtTBTpWo2vQs1Nk/nYBSWlIHydQ5kI3oxA9oIprqGDcd3xsefeUansrwQ7A34sP
2m0n/IsNIwwD+srZXydPMUin9jrX7k5q6tf7fbW+vmQkvIFw2f9t+xu9Oy7FXm2D
mx0xy0Z/iPt5NdhFAh/bQ9D4q/DSFnzfn3vLlMuAmLrSmVeAGDqMtNosITDvUfAm
RmaB+GEeBc1Dnq6I4l2oVD+n9qm1oCzVU28RcX5L1eGvLBVo70FKjXKFs0vjKScH
ll/NEuqNHoJKLFyWNEu2xAz3o85Vg3/bDEMY7tDG69JDAZZlXUmNO8FoIMOuH8FA
5GK/Ypb6obBGLfo5MqN14MRO7ZNOLbIVEyKftipMfHu+BOxIt9rdDKQtNLjOZmjn
yM6WLg==
=MOV0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '87a87318-fb83-4b02-a94f-9272cf184fec',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//dyY6+wLgSFtViALy++cAFO27rpCe8ssOVtttfh6XzZKB
KCq3lixTtlLzzm3P/aZy6RH6P+QVgCQLFYEgNQAo3I3RRqbDZzwfsG2fgwdYrrGA
pwdsLcOTn1tnvbSgaYrnpVrDU49u+GIHklz2W2TqSyR3va0HBFuNUK6VELkYvXPt
vWsaPEOCnKo7yRUyazM1Bo12RTmM71Ngwg72e4n/mgTOHW1qL+S3EnIE6TfiOZwK
QNG8NEpkOTiqu6GcBWIdmI47HROXFlSDFCieaYZgUiwt0nI0vaBxAwqdxziAZBPj
jACu2URqysM/3nXQpmOo8FNwwp8paUwA7FM1ixzEWC/3rOH2EnkspCB7EqqmMz1X
7di9UbXvrgGnu3zsYDmdFzDusZfvDICqoY+VlTFltKChVQLpBEcpGckDZz5c77ZT
OU7bVu5XFrMgTx2vQdmeyzXwOqxLrr0acYcD4oC/HKigKxr2W5XQAStQF3tsukSz
XI/fB+9tdGR/E+qWYLJ1tmnd2UlY2K2iAYdY88tlzfA+I96BfPSPhwGKfOaIphFO
tQZJM5BKO+vXiks9IOmuZeUQuWCXgIiJCdjDVyLf6eKMSAclxzv3ELJQshxREF5x
uQAYFXAiVOX8rBZBotzVlUg2cMne8ybHDHOiTwHhKd9w6rOgz8IlJyfY9roY5LbS
QgG0+fs5XZEJSOUFj7QLwL5w3S5Ff6vtnHlRYIQlXhBdAXBcoXSE1+Vmebijozou
eSZq823suCn/Ll+wmYYfGHaKbw==
=f0in
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8c6c3de3-0837-4a9b-ab8e-8f34f02cff24',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAr8CJIJ7LWueemQNTg2kIq1QIGlytUE73z7KOl6MCCaMF
v8zoWCd2z3fITu0fIW2ocKaxQzLd+TeiZwgZ5r1Ziw+PhPgVXL97fNZpym7vexoL
7goeVb3JZ5tBr7Bqvb6Rb8Kq9f2KJgraRYz5AXN1+iboahdMxskAcPxa8K8lFopT
dNIO9AkAAMlnF4Zg6vAm+FL+Lr3+U3i+ahEhZYHpK6HHFxMUS+McBYnGSbvOepBg
Z8Xw5dtt2b0ft+Spk785ZIPmoFg7NJRL6xtHz10ebbImsAe9CGwvoVSV166gJ3Ot
L23PmOwVcgwkUmagDJS+7YmRJVqAUrT5uupw736R8I3EPM2ZMAKH9Kxztj81EYgD
r1pIGnHC7MHKEHfkQxOfMMqAOWX5CC14AVT9D2fHLKcrwC+TC2xZ6rzPpJOEOm9w
xj7ANbUcHBXnXiqs67u7RnVWAPJbB/HBYAM9ZWhFIGFOYOQRVZv2inKb13mXWsbd
Z1puoQ+SMYLwWGdqzBs2aVV06YFHHm83Ba1IR5Igs+tNA561JOuSiaAgd4f3gO0r
MJu648Mdae5+84ey3xiito9nM2JZa3hOzTbcbqdn0QC64TSdCU71T/Hkwiw12Z4t
cxq8bwg2FOlRCwGfaHJsCmpHO5P+c0LvTG570Yl2BHoYg9eKNvFTLC+93Ph8MTPS
QQHDFcBPmijiDHXGlML5x6OiBciIDHe5YJEl34IBMFBDEz7ws3vj3NsVFcBtDhxV
kJTrSAfqPqN0k3EYzqOinaeu
=MEWy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9198e199-6c07-497c-a08f-91dff5c06161',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAuH2TFEBBRlif/9X5T6t2OIIEpMRa55ROL/Ng0YWY021T
aqzlyGKp0TS6TLcEOrWO9+wp0TlZJ/SfB8/eyGekYynrMYzuKeFn6UbEowD1UVMa
1iZyn/gtCuEa5/BlaZE5kXvEG6BFr+AmYJGoN2APvn73ixUI4XRFvtug+Kdj4CZx
Vd7CMTEbsHrUk12octt+uP7AqrBSWuiYS25svBxCo2xLhqApTT4H97sGwsw+7tWS
QTmBt5xGoY4GOtW5lyu1dwI1pdnoc6VJ8nTpIQY6BWBw1i9ns/4oByG0h5R79ZYO
1yZdhP2EFkTZ5sVqC5kRIK4YLpu12iOAz55EwYqsd7lgcFTwZlIfxijP3D0cUKFk
uu+2/iz1ytePhiEXKHwJwDZAxLrZspztMz80t3/i7plWkOILXrEISKMqWEBIbnSW
NtUrV46iOGPwOVYufQiQP+S2wlckwf8nYMgJhcYiMgeQGI38+0BHQmrby+f6Oq60
1q5ClzJhg8a0u96cqLlQiZU4382Zc1H6YTdvRFYQYig6yGXHKPudV4Iyfvamtk3E
kYgftRNOds9qbtxtEAdE0w60795f/hQOhy3J7lS0rFP2HLqcKfYAIt+JoiYafAru
OF88nU4n+5NxUG1l2pFN9hJJCiRp9l8MhoZGkUF1AWlpBtdSH5H3m/dbOeTn8vHS
QQEvOS8bBPffRFfAfvParCinMXr0SvufYEaHVI6VA1vEjn88onMwMDO8fF38rjPF
XGK4/Akzq9z3NpEp0vox2ypC
=Aj/p
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '920112c6-b2fe-488e-a7e4-1141f7f77f05',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAy1WiO4vlG+MueP1V5LtjC+/FIhK3wo8UU/mFX06f0N7F
JtZ54Be7um9rFLk7B2ZqdwIMianhPHjIYDIHIm6mYV9xOgfCTiAG+DlA+OEnWTIn
ZDsCa4Y1/DqV05L5M0cS+3NNw5WnKzzeVQYb3Mmdufd/0lJWLojQA8xKzEsudynx
9iuL8C19/H4wIG1Dwc7MoDpL1LjH680UFU7G52gdl/YJlFHe/69u0NDCpWlaeoiE
tWNS3Xgm9THfyly/e7QrbSmxBu6QQgDc2AkrPTPkB5FWTakyg5RtiVEv1xQvlZ5T
dW1wfj7oqG5I4IIqgYeKVSuIAEz25zXrPiW+duSBEt79WeBMkvWoRlufXyeMCFSX
34xMhcRqnF3SHbxpW6NFz80IG1cJazl4nluzX/j/H2XQC+x1hBXQkF/WvoPs83Bs
sJ9JJ1Fpxkw02lAf0X6gNyj88pwDqJ/24pwWFRNM3V6ybZSrX83L2Qb2jA8xkWIf
tyZ0stf+POhNkwuAdoHRlcGshEela/Kl6ZuzVyXli3eflyatOv8szebMspDv5LpF
nxULtdAPncUGI6y5js+86fUTMeUAP4B6qx/jZFz5ypTmMNnWI1r02Vh5p/1YNQJb
uPn+pz9lkGlBCDEojEMX9oc2+HfGsRVZF4v/a+DrNx0vuY7EOY0+uEVZQdHTUKLS
QgHLibRHmtKUhRMO6nu9CXwIcZ60Az6izpT/aPXml752wi0tCKKILuChzoJHUHct
47LpzlqwV9r6rg0CfMril3Jzxw==
=mSVT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '930fd31a-bf0c-4853-afff-68c305509a56',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/az5Npjpusf1OHD3/iw062mbkXYL4bJ1c79uOQ7qadcBw
sLgR38aDoqOuY5Lxb24s1v5+X7lV+QwSwR8D2C5BWsHsML+HfI9BxcKv6EDqRDzM
6Np/2pZR1nerDy/jxQIUxp5szyZlzPhnPs65o80T3woR2LwTW7aMyGeByjQRhSW9
F+ItFuhC0SUF1gE3r1ALtf/8l/zt4lRK7x9OCu/+mjOR7J9msaqyDPAJTOuoNeaQ
1ariYwIk5wSa5RlyV7XD42xp5K1pWidbTAkSN60Ja6lVQmPs4/2hJ/IbUBnsUsOw
7/3E8v1N3LCRg8eLB11o5yLramA4vk+KCvVEvDwcfdJAAfQq0SQFgQ2FfOyj4Epj
uDBhjFesNQYZ7h2qdms5ZtzAgwcdID0cgixY0Qv/Id2oS/+rqYoxhhV/v0p275TO
WQ==
=/cJW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9434245c-34c9-4c21-a2be-3d6e9aaaf282',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//QbH8vOH0WByPyDVaujY4tH9mGcY1eqqSwUccHji1I6Ab
pD+6ehREbdOgAZ86gsH9rzK5f4SUTukYd230SbXBCWroMI00Gb0GeBgiBnaXCymc
OShO/Zpnud/VbJGjrET/p7R7PDOC+p1nZyepwdVKv3rEW7DtjuhskKsONdbfIBhs
Vg0kBApR3Mze2U4RlNZKlvshhQxAk1GPM316nz12fZYYulrm9do1lR0/GSo8GTDM
iwOBQkWiAk3DioHC2AmkBi3PZ7BSWn0C59qNM63TIfEDpKyIugHEUy3yx3F+KSFL
DyoR1v72Fe9ELnRSc8/PDxhqVqSTxqfle29Cuf59Rmzy7mbnSfC+CXwe7WXlkrSa
pVJtd0C98qGUQl5zhAISftvkpeIt7jkuRAI9NNLabwINU21fBfc3eNASxvkD6kSv
InJco8UEyRgzk0xXF/n8HL0GKSAoCxbSFu+8ENR/cLprpcHbpJMEF4i97kDdFJs+
sQt640oEhkkFm3KTwU+A1UCb56us75dzIuPLMZE2PcmzWKxY0UYPsbnG6bW3NEoW
N8pd592Ax7tVKSiKPbPN27EUH+fwXGWqost2c8KTo0MZxH6nXw2/x+ozMtdg6xX7
Vyea3rN/WxoflNGraNigqn4hsYAIAIoO7Ak82EUFOT/Ogv1SWJWSNsQazPHSXDfS
QQENqWGxPsZXish4GVRC6ceedu2ERVK36Ii5f57CoDJCexShSyMaY7gmZCDzOlUS
h05CwI4xkz8YA53LIs67kE9B
=GqNj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '964f8f2e-274a-4545-a193-6c60c93218b4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAlX4t4uEBe01ZIkGVLP+GpBoB6d1e1sSX99T0bYefxlo2
zdtDgiT8xBLCzx3CQqnWNnhgTFnO6/ZGlVN3Xys/M0cd65S62RLABPm8YbXJbGdr
91wuni/vg2/dQeZE8RJYmwOBSDCoqGBI1LPrUj1JfW6xE4LHmSlWW21aaKIVOPGB
he7H3/6LmVE9RPVBt77Px57Kiwy3mnfn+fvHj7pC3MT/lL6tww0mfIDQzL9jI5Md
nIYxUd4Tqnl6iPcP+mykMwROluxxYuS6UWXB2GcJAXm3g6LlAZ/TMuxxDFaa8dVW
pkbV4v4UdOlBmQJlahvUsgQVhj6If7kF28vXTQM7ETKjh/qQHVenu8ZdlgdhcV5p
3ZP19k0r096J36mAGRkN9mSXJd35QFNm9Wxl0KppWIJOLRuoNM7gMlRjdrtvYd7f
CSugRfegdFxfABL083WOgLYtRncKtoTZiLSMFfVv7rWEAUi5JHbMT5HibAWKSM04
Z4yYJp1u2TfnSGAC6UpUIF0jJ+uGgiMCZ9X0wKueTLvY4aUdlHy/Vv35Ea5PqD/I
YbzHI/+2gOs0nSeHA1WS0G0w+APWAgTlLTRLwzrMFBQrCLDpJ9yV5I8xHk6S+6B4
gBgZupM8jEV2q/kAfYP7RzA4zBcH4JnGQboLEelgdpQPu9G4h07aFxgEvhzB4brS
PgGOVRw22KCKbDHyNswcqHKgGC0hTeRN5YRN5/d7VibvXdfhQzzppLvT7iOjxLr8
A0+5Xu7KX5dNuz+zeVbO
=khjG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '974e9980-b011-44af-a7f9-1450d0ac7f5f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+MmvExQN5ReyV8mbCi2atT38g+oshgVmfwp7m4v9AccNh
oJ8FOc3mFKfecdEGJ1MdfMc/alabTXpuM/Eyd8LKi7gnjOC2GCmL7s0z2jrvTpLw
iEs8nvbyO+g5t5bJVDB/m+h6EXdG5Ul5ltMniC+RgSYdmYOYR8CIXgfGjssBILuI
XO1cERUOzLIWeSZXv6dqpnqnIXEklcg7kELDcSdhp1y17SJK+AiL8NeCpZhTTU8O
FPFNlmVpuVBc/AMOi7KWLGoJHqKL/tfwB6H8mVt4m/Nkk5YdU/p1PbKlvJhuzRlt
TYg0+L0MGPi6F2xTASxq58GTHYz6erQrNNn51kpvQXnqxbj+r5XEOlURRhF6obYl
omFMek/zaqRKSF3TVWBWBb6QKwwCmM6nfZyEsM0LSiuWRbLV4IDTdtyTZlPkmrbL
yVDEiZB0BYuEE79GfRi3N+Y5QvUkvOBqVMc+ZNV9T6g65dVGrTKch156SenQkLzi
6AKy+WHdYp01Yk2x+1dHUDPKKms7WG+OgXbJjjs2LdYuyd7HiiooFnS30uj3IPph
261dFPwOAs8BxKS3zTC0FzZLwKI0RE50ZF0aUwwNQQ/0MaIn2JVSyRUP2YzyKRVl
2BZoUHYOi6KoVV/kaLPy5GtzSHvgZHgojYTgRPS0dCbDq+L14NNwVAOG7DzG88XS
QwESKywYVwsDab9YTTpThBMp/ftiCX+LHtHSjSig03T5UkdLMLVQck85xFZtpvTY
BRE/yPJr/PuSX1sd44ar5dSgqxw=
=ufhZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'afadeccd-f753-40a0-a1b0-4eb75cc468be',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//YxvMendfkIpPuyJ56FadKcGHBcWK6PP1BwmJMhWIJ9e0
4g6dpOB274PsYHrJyh7bM7n8Mta1ycFhPBmRwjzyKh00b1+JX1BY4UtnbzLxnFbv
mX3EMcN3pbDyvdErFrOk97Z2EaQyTwGYrUaS6qoFMb43EY8IgCGod9K3MmtP+OaS
zc6uu+++g2eVO6qvxAIIQAjHgFvHVQGsW7clAfTyltZdtX9dgI2RaVcmFOz1Lw5j
8KfdpZ16/6TZetpof36Pk+1kpZJ0ejJIh7CiAp541XaU5Qnmx/E2mYz8eDEZRNAy
UDI9ow4hb4FhOfmIZKX57jZvP2TWJiNsxDvZrwSBfVyVWzkRHBczo48ozDqPsAYA
Ov14SvU4oq6I2szDwJZFhxY/1hWSbMPIVSaEzyHXV1N3n3rL6pqzo779kQB3xrsF
kpUS7d7o7J/TSdurRLT8rAnfw0CCXqcs0B8DPCN4LlTn9EWgLcdq8mQwqbAOPACc
ypn7WN7YRo7GHIeYUjwvjpvtY/Yng8RmLhuPswbpoabcoXYdm7si1UkfiXokiNfl
a4o9xV8mNbv9E1g6dUzyq+4zPSvawEKVcRSXnvJ7r5/Bo77rKl1VV4IM1GMYIdj7
K5v2H8rGNPwr5/3TNIcnyeEmw/woOnte9kR9ZcZayxwcLn79mIClHtsYfmY4MfHS
PgEHYOIWFlLzrXzGWoOetRr3KWFbkTkRrcaFdMLjCleuAiFzMnlE5T24LrWs1SJi
tl+zc9Mpne6Vr4jPAvYT
=59gc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b0b921b5-272f-47d0-ab1c-5d954b3a41a4',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+NeCmyScqtEZ7BUMFyZyKaXoUkVFqBFLSmzUM5G8yTEmv
uBQuO+SeGQoPyfPpUnmQUxUJzIjFDQe7tRE/COPoOrw34RnKSWqCKD0CYZ0u7z4x
N8Mq+FiFZE+vN0FS8B63RO3x+WKXRUWp60R1EzCcrmmw37EAG64KGTfnwhmb5VlY
W6FotmDiq7w1L+JRPecUpATB0HPy/gpax+xHpPpwLMI/EgfZM1VLwmhhhpZMp4am
zxL/rS4h/PqeFLLwOJCl4GmvnOGt0NxAj9ZCRu1VUbVwcs+8MRHWv3fyt6SCM8lx
yH6XvvpcDQvi5XIAfTnk3hfM4zKrzjB02uykvL5lzSB77e750nszb+tPqb9SKLyg
+xpCfzq//eN0VXqlY5Gy95CC7IGi9WSZRAusugMTZGGcDvDvxKfw1ZQTyp5Uy7Gi
qG1jVGFab8ZrRGrCv8w3/R1IzOpUxR/3++Oydt7qjEC69YudeTfuYZgMKQD9Jr1s
TDAtms3Rf2/TsPSc93Jtsp43GoCyRY99gjE7f9z+eXoPjRfD26mWrOEpdDnbC8cv
s7bnFqwCgy2BZB17DoR4mZOauFeV+32fCd1VrxwcFT8fnbyv7siMW3CideKXIuwM
NjYsCKRnjGiFjUxn2tvRmP0movE6FHYDCK+8686nAjvyL0yYUC0lbKYz4FKe0YrS
QAEcxLFe0kHbw4h2Gla4Fp3QfQoNlXPJH1ygBaQK4P49uox7WlfNpWfYIDn2foU3
G/EFzhIUXv4vUD03auWGrDI=
=mxDH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b1ef5790-ff5d-4839-aac4-985ef729398a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//adehILXyTWz4AGiJHE+E/9SMIqgiQJQFXYQnTUGtY5oB
wyFYnX+JGRY31KdT9yiUpnl5QTjXPPz1pQMFp/RDQ/Z4lKsWup2XAEAHlv7KCPeF
D80QdpXY+EbpG0czue5pXOhelCReR117Mic8BFK/HRGOV4R9aOQBHQKRzUrb+VVF
TYDxSAVXIKNUVJIcz/PnGmadWKFwjpMt0vUP1JC7Sg+H1bRLZMEye9STzXCQz4cb
KlaAzqc05+93ysOQ/2QZYNNqPQlq56F+2dYGOQfshFnZ09kflxLqb11SngoK2A1F
sQeMfg8wowt0nFqc2gwON1ENutIOsMLJo3E2qq0/q4b9XbbF3cObIBwBMxlVW1RY
UIoLUcyMTRLK/WVE1tUsTRYG5nFNLwAb2EQTKDI7Ly/II5e5SmMJtoQQ3+rTq/Kv
jIGxn15CEYc1p7WxmXJyXC+xGH2c5eyJNgIOro6MsunlRAhkJk9GtfDAG9ht8spL
OccTugXDkoBFVPEw1k0km2OgrNyNnZupQd6USIYfpX0uJBWSJ6vRi5lnwjrDC8yE
/cQaFV+tCfHiEw0sKPfqvFy+IdWqExsOjQJUqVxygUk6KuwWw49eJL6w/bKWn01g
IcTTN/ybxH8vNqTEw2YaGItYSgX4SvW5kZv6Ca0CQl1qSyshQoYBQaxNSlG2TTXS
QQH6c9BlVXZkM+JvzCfVoRhZ3FVV6x2NdFdsivwVqHmlPINCMB7rDOi98diwmAMn
jxf7Wc+FK8gYyrNxtJsHmU2z
=s2iV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b5f7601c-4704-4ed3-a177-a3dc52aa7b4a',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+JQ8EKoe7ziRGYgDJ9Te56igoF0ozOvILV8wcTWwrYlmm
MHQjadUI7N7m20Z0mvVtNs+SlLVELLVrXw5nC1wi0OnwGAFw1j1992U+/TzEFjGT
iLxutOggr95sEYPF0heCDF+njxurkuNGIzBGJKUmvs457JynehEpHmR6ZU5eOzN8
0wxtcwOBxya+duNMVjLM34Y9Tl3FT+dvUBiEigHWX5ZoIKJpaQdmcOBvOqi/3F+i
w/AhtTZJoLeuClmFX1m/HNsYajDFSDucUbtX88JXhOIn5XLDqWndbRF724YX+suj
wVdXcrD2VOko1dBC5mHnAbrpzyRluspSiKOPRM2tBNJDAfznHakLoJEZe0J6DupN
aZyhQOv7UZlGh0OyRQFuZkho7M0wk6zx30suOaEinJrfiztSfPsu21JNZalERY19
nYiVYA==
=nErH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b8c3dfe4-1b7b-4ffa-a137-21b99fdc804d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA5KqCwup8TvtGjqo0/Iwa9fkKTOOgvJjwp7JOgGAXUAn6
bfc7l6Ig2s73sHXcVdl4Z7a53ltl60wKOufAt6IpSL2mbYWj7aXF7RU7LYeuvqur
XYDjdpYqbFwHJW0adAbT74FepEyPtUSEoKofrEwK7IC1gbCfXGoqcuBi1OICApPT
QoOsXEXtwAJGSN5OCnv+ieVOnRRps7CteCVHxNlaogNIzYFuvQL65XmDoL9mIpZt
Nd6RSu7FMO/F8w7lzb3pkmwnqhEx2pAQJTMgqs/ic7j+8D6jkRzw9Et/2OzsF8eF
w/1H2EaJBUqRCgQi1Y9I9HUy4USLEtnxFVf0thiSEq6Jw0C3N+n5LzBkvUCIgoYD
mg2O2KvEMYjKntXua81+1BU9PRJcYNHGH8bwb85p+Nf4cnqZPBwtSXyH6YzU9xNL
Gv8EyNmODU7sxt5EFKitU7EOj/IVc3m5PA3KsajIe479AvcZnUlQP4TsJbnEq8ur
WNfoLnFUy4RpZlKlQXkQCOheAzgy63CfGaq7eQXw/jUB+w+eRQuWMIGCwrjH1pRc
ZYNne9Raa67+8w258i8Z0PMeigaqHfLodBz12xeY2+bAE/vW3SCSwXINT4wQipBx
hvZUq6hpuX3+I7DbREuhBcBeAM2YrxheC28B9xigOeJAATH9Sbss/wqSn9c41WjS
QQGiHYYhzKIeomJDFMU/TwiD2E+tCJb9WMS4dc0REpXnPhsqhPqXw82YNNMkjBnk
x2zz7em/cWPJGKD1JdaX/kpY
=A2gG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b9785d3f-c6d3-401e-a4f2-5d02985f79fd',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAjoK2LRDDW6x5zMFRyqsBTvq3DN0SlBvrq8vJ5P7k4Zpd
zt3MaC5Kr8S1u2JKJwJo1pHhdsRXN0Sr0q8QD37zpJdvc8CST7TxLGUo+RZWtgBj
ogQoo4xnAVcDg0NTbifdxTUBPnTU7GwGmpQuQFMJtHi3kGUhlt6ujHEus1qi4vf8
PUjLJlICMksuHVrkBD8ZHIt3XqFlV4kQ6iGfucZYqaweV3Z+MkqWIRqvThQWeRAI
pWM2aYOWlA4ye6B+nQ4W/6ebp1xd7gj43WZHtRNyVCDtHWRQq0fGx74Mdnsim3sE
js7GtTcf97Q9/5IQBIpiAyUdsBZZNYkg0yEItT3FUNI+Aco17WuZvMJSHah9JOgK
Cw3ULNb4khpKNlT3ZG9laAX1nXt98VFzVe2cyey9DlVtrY+LMMt+3NxaxYDCRUQ=
=gTf9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c09a7d3b-87f0-4fab-aada-b3c6934d3224',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAlyefQKa0A1TRWwvWAQVd7R/rH+aXLQlKqDdXl52/9IWH
LWDi5B/2+ECLf9ido6UVdpYEkKGTv/8ANQoO+A4ROAgWp8RfbLqeqoCRCqqu3N5w
XdNk+qfO8MNYYFmkU64RjATg5OKem8v0okIauXvWluoNiw+JrAIGeA4Eet1k6vuZ
4BIeaDif2CNJoPZ69dKOnQWq+GN6RLa46BqKK4XdJSUW66XnNNd9WJ+mtRmrXIhJ
xVfnf1UMY7iG3AC6iy35/DoiTpgvQ+xGAFwCrzHEMgT31+doiJHRP+RhwV/Zkj4a
dlSPWOg3gqQhexalYl4ekrPmzoNAZOhbhbB50s48cRJZWFGrEw5zefduR0uNauOc
TuGpoYnEM0GT/HWZK8ymIfNh3nHwhXoPfQo7BMXKGULX+LzbhTdvlLHb51LNpPFH
BOpz11+6RMDlVQR38hCF1rVATFiVznwemqTY2FMrHPvLDJMj0b8+lAuPvpe9tfpI
cxhz+EZqk8rLFNvqnuiGjUCATY3XnQl+TO/0AoCs8FayL3JVl2G/FLQi47NVbe76
/TcQWg1j3ok6Y5CSxzU9YGZidDAJGapxVCsX4JSZZbF2WfOt+QVxh+d02evgm4e7
+OB3meCyx4CB8GNqGnsxjiY6XoI2bEYCeJya9FsySHO96dyXG/HLb3obKZ4LUhPS
QQGm11ILYow7SOGCMosGzmHohEQ96amxUqEtSQam35CXo6cvgb9d11GQ5g9eTlD9
SJWB7iQeS2jm4NALMyaUnvkx
=zYAZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c14624f7-56c9-421b-a04f-543ec4104c5b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Pxq9oCB+QyCADBeO0tI2z0p3nckH3B8YV00V9QhqkK6V
QSk9bXYE66y1hPDrhmTARSWX/9kvUqmrRBJI9thAfF/j3SsYoFtmBVq74AaOnbPr
OPIC1zjQGZlCU2PrvU7ntUfPeWHkLHDGC91pfwxuw5Q2SQKBNDJopkPD7y3dP8R9
46suI+TinOR8AV/TpaZUDHP/prw+c8shyWaeEvk3YHuxz8+H0jHwa+yBvwkMwBdP
KoYbktoW8sXCakOQMQjqT718QxgTk8RUHR+VVIaQ+WbU76QFJpvvLRtcqfxImvJv
cy+5rx1r/gMeb1lNjJHgB6sSaDm53erb8pfGb3JaYhiH6NuRkT1UpohAZlx9f1Q6
RLMG+x/Rcr+bA4REyh9s7KJ/dHDRovK0hjyMtS3Qyqq08esaQX2WbrRk/ZH2eay0
s1NRk+c8gI7CQVJDKTUZsCbVNTetUrbPutK3NzPdtiN6kQdU2lpshDPzy0tZi1ru
6IU6ksExQAx28/xDyF+mtJsyfpLhVPp2yGgVv/CGuj3ChI1Slv1KaEm7hDjjTHKN
9jrdIOQci3CLy2XO+XTDj+nrr2F31T2EgtXBBRHG1x1jV4Wk0myqMJ3nOQizC5CW
w2qzWjY5Uy4GMlsNTwlv3OyL7Z68eGjUOL2T+B99QjpqJYtSXJ7T865Y6g5na6rS
QAFWDjeEGXOQmemeMr3t2lMEXlfdApCUBJSppD8/xNRXGTgFoEzbwJ4K2SMI7Vll
NAzezHntZZOK4gQj9wfrN8M=
=i7Jf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb3614a5-6626-4ee8-ad90-b6dcab4f9cf5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+OcI4ms+thosyYr03rXLfTv2v9LLOKAULgVzv6cECfIZA
1PNpB+Zry0NqoVyHfVOJUm9Z5KQy19FewS+ATKzmHuwKNb8G0EraD1CYVVcEvD6c
j50deUlyw6rZm5zFfibwztpqjOWMZh21TWKgZ5bSbqb0BH3BYd/KBPPtPS1pu9zH
6oGSDkVePHuPh5IU9TI1mEiVK+ewWh3i59q0FZSgLPNVlQknHsAGppgb+sTKs9oN
42P8iUXv4mJAUSG/mvGBDUbS+aHh7R5OapmaYQ46vvW1q/dMaY/G4F7UIyNPrnqd
DU/0MHVH8Qzo/mC+gwISxetUBcJcpzKaXNlKfvN6aKjEMD9NZRT25bp2PVMPU/Df
Qt7coWN1wmXbLdy1V4tU1abVJ+jar7IpEvwOppcwV4q1Y3kvLIeS2r+ujvXbqMB9
jKSjD45YDuxa5fkK5m22X6pKD8RvGlt+M3s3XxWtnJnHuBEhaorYs/Psc9Ovi7GB
QRbqzbsDXyZ2V7WO3c3+Z0NTWzAo+YAbsl5EWLQk4siZvDcftSqOPJ9T8kGMdgKr
h5AQ9xPuuY+LSFCwFaUOrWbefnmXR8TjLy+W4tC48n7JwHtlWPwJMHjLOYEeH2Q5
idF5pxK1WYJ1W+NkygoviFbEi27XpDMlPgSYYv7CSueOXBPKgO+2zR4PCycA1pTS
QQHN0TZafn3mZAmu85/PsRXI1YPfZssniayVize2iGbNv/3FQ1Sqork3afANF6fM
gBJV0R5plddC4u2P1VAfIGDJ
=T9gC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cebbb89f-7bec-4add-a2dd-96dc9b6c87dc',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//cxqE15hl9CDE88Y46cPMfsm0o5xzsdOMle/dg4DVGg/2
tMHxfFoJJkwLUzJbyp9Q5zby5VZ/dEOcXYdfeFlG6UKXNu8vWkf8FkK1ualo7euT
XlcCINq/mGLvC9FKyANMnZ0vNPh84kg6tzIcB/HYVTVBfUnbRDahycOnxBiSUJ1O
NanX7PwSZ7VWJ9o+B2iTxhrpOxrwfqiZ8wYQVNo4q0f3hzLyV8ee3oJtZ2tcbn/n
AmN8WSy5JJAlbjhob8oNa/7fQ8M5Zhsl6sqrOU1QjHXR3Qewa0vfdtlC/438+82l
JPfm4Npc4QXm+DJD1Wk4if1Z/Ie20oBptoMXSfXlIarB+2BWfp8aE9sn7sg2/0Ja
Vf32XfYYjGS2Jr7Doyl+/3i+CEPoqlmdBsHUqZ4Su5GbpNfBqmDClb8v1E2csD1G
mJwu+2HUN/WPDMAYD7jxw8Lh+x7xUy9DvOXTJIKgmXS7qf8vkzxSETk8Jr1nGbOQ
9TNtCvEhiHKjWZcjEunv5ud1pLLuP1kTbWzPKbi7+61Vj8UaBwt9xVSz/A03fVaJ
ixqef44YLDAKcL8xdBSRJ7J8Enalkmz0KNJPw4IBRWWHgnTgncuxZB+zpBK5sv/f
wy02u0AHzCVBZ/p+j/608W6REV2jV6FWlM3KbswETduTFvQ+zm9l/PAU4/L70iTS
QQFxN+JLNkq29kbLYYRDJJTIv0kqcGinycgdU/mlJhShkk8vJMPEx6dEXw/XrHyl
uSo8rKnvz+Llmsfw9kXA5PHo
=q4/B
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd16a6c4c-3624-4458-ad1f-53941ca9d238',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//TJtRFDbEgSDqwaOXNGgehUWfGgXVVDmV1CcYOewamH7I
eFaeWMDKv+aQ1prX+tMmfN7Dq8c0d+wo7bVg3KYeijhjwVbVBlr9TN9lmU+DhCJo
HML66iSb03owDcdfKAAEY2QquKDKNF9f4F/8+uu/Jq/noDgbIlREsSYcdQ212IDC
FfJc++wtfpvmZmcR9xWWzFICu/4kiArxnCMhwOZ25C2yF+vxKkBPRVdjaEfbpLKS
d3ErjpIq0p+MkNsAitZ76T9qvBGIsinPVNiWwnDvWGmpXaI4uT8332EhbKG4NGXc
ywsRReL9/DTsRzKnLmkNc7bvwe4sd5jqaJV4y9sYG2uxv1zBZ/o2d5rQa9PrCWzj
faWUpSslIvPBUjN6RihKi/82XVRfRhfsCTjz/OIK0h67Ob5u8Q4p9u+u9QMp0cvb
BhSZlidK+ShorppJxkf6OdCgIUTIj/Uu3DSXihc5XRr+4uYBLB1C9xJ1BwDLOo/J
3aMiN9/aRDzZ95FupGw3XgR5Fn/sCv4gZEvgUqWlPL2ZVH29MLZ662AT8WtRrKy7
oCx0q9mghJQIVtWoXEQqG715isPZm2+idLJ5frXsqSpHGe7E/RneTSHoOSgYjCZ1
MIM+2H7EgWJrudO7yJdikU7/Gp73lf1i2i9eHQAOk3CJh8WwuGLYRnCcyMFe/xDS
QQExGpE8NSwxE9PonM4VCxgTUZ8TWEvZxB0Olsj6G6wLCZS2kYx8Pr87jPL2ymEy
HyLY+C2MEmFSfLjYDjef5iOp
=77WQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd764bed6-8cef-4e57-a198-2e2e6c1a0372',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HObJDklwI/jujEM4ri/OYQDaPuewEOL08ART0Wm+9RJ6
9dHhyWl3h3qcDFjgZLO99athmRQCor0Z4N8EOuycitGioEokXl0Xd5stgKD5JBx7
1ghDGQfPXr+JseTVCPF+bFSVkUhlZEYcNkNUj/FZFQHo5KMp9JqgXJHcHurcaSez
CBONCkDoU5tuUuS32bqpxVToIkFuqFOMMxnBQuRn9323MG4DeHKOcyBZBB3h3vhT
zQi6ymIQWlc4M4Kb+0EG5sOUC3f0kKpLQHak3r/kLwRGucp3DwVjbErvmvWLhdnC
x6vwM9pnfMRykBzlemgtjAruwM7+Z0iA+ZxhBnPG45gwxcLmZgLDgR83quufeyjv
HrYHZkLtdFPQlWqvvwvg2qWRhtS0/orHmZz68bCXb4FGNHmPGzhqPJPmpLVeUfpg
wgVnEVTD4vg0wSEOEE/gaYZEFolgV5vi3Nt9JaFWMthUskAZuCoZZINPf+b2Xn6f
T1q9T/YwqFvrocNPTUtEp4XnkUVCe3XnK7rYrPCeh+/B/j3biL/pXg8h/RhoRF1e
G84Lq+A2CA1pjKf3xNxcMdl+sYw/s/nboMxvd2csKeh8/Yq23JeA/aekc9x1Totr
H/wwHDqHepPe7dZk8WmLYyTJrGspk+EpsVVq8h0xLmMOndTnlYLMsCUH0L5vbVXS
QwEQ+4bfA2V6jH/x24ssWQaWWN4QJo+GfWhjZ2lMvdxAtjfARnEHVmHfOeWKuzVH
cgP6BPkWbZHLt4ryzZpBSP6IAMA=
=RBhn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd8a4d83f-6367-4c5f-a8f1-9a72411150f5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//d2vWg4Gcyn0H6hMYVhz7FEmazOAbIL2WT95+iM+W0eFd
7o6hm9Cn+Ao9W17UwvAKTSxEIdOuVAjmNTriuavbWISAdbhB17OSx2IUR1UXTggu
Ptipi+MRJ1Ktevqvohnuy1tjSHYffYIypf9sFU6QPKuEJJ2h1SEqBTaxhKkdk5t8
5KeNrUFG6rK1cjRwJMdOZWwS/6H3w2hThrDJnQlAZSr2hViq9vghzJ6bZFeN8E2K
WygrEB+IbryE9bp55gcpYsbxfP7H/b2H2UmfCTAY1w+TqUeZbdNsiHNB4Tn1yyeB
WiTCJEcR3S+9ihkNUWr9qzSyzi3Vpg3W+maJ2b5L3ZMaJcpBMj/xZKWKqFDD3/Lj
1rFh9zXOdey+Fc7f+BTbJzed5xtJ8FjxNbdGpv1UPEaFR/9muTErysA06+SiQla/
vWnMWx4lzm+JDPGn5c0AAX5FV3KGMBi4WA3BhlfSV9IuVSL79YigrX0dTnI4IMLo
iDWM8UXDcaUhi69Z4TED2i6s+XaUryRsaobvbzKOoAyaJ8NWaSeJFHpz8tDB/b97
dEANyUS3B/KQWXi4QJDzaIe0SF24mAGOPurkPHuKVYKlKl0U7uZ//+Gic8jkP6GT
QHmwXZMOtBnhzye7MYqoVO/SZ+X+d1j9/AfYf6u0WypZ2TRTxa/ygfB0rfxpc23S
QwFWtSyQNGvjkdOV4Jl1nMiFnHwDL4VFoAdbX3DYW4tms0uu7Cai9OJmzhWoIV00
9oc0Bwdjrd5LHkdGhL5lVFKPEIE=
=dYE6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd95a1001-ebbf-4e4e-a9ec-d8cc837543ab',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAqoLQA49LeuCpQastgDOwMQWthRKw2/TMZqcS/rqoMq9Q
p8UPAcq7fTkcgSZ/C9uUZUVCC2aPXGSGIM0LL7t15V82vw7RNKybE9KceaqytLyP
FlRGsDjSh9PMvy8EAK605Vyedx10dToM/lcyyspceYPkW44aYN7MBGbU5lG1Qfs1
A3+w75tnSFKdons4jNOsCB331YGwfYzsSc2sk7BE2tFfHmBZOm1ZH9wMIdOlVmS2
vQOdveTrr6XeDP7u/Hg5Xoj9K5k1C4PY81D/6p5kH+lLm4/mEjbWSw0gJSFZxzrn
Id2Juf7+dPzAO0geiT7NrBhmdXYQDXz3mFAM4eSx/dJDATbjoUlUhOP9ORYiNgR1
Q7A9eGu4lhsyTt2bNqSYsc95yefB1BmkPNmlU8erHxeGkrU46AQuzn15AuOzrtYY
6ebZYg==
=582b
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'def4fc56-3029-4484-ad64-159f84e5f56c',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+MofV+3TAyNkAm3GMmkwcHR6VevEH6t/lgGbljTOM8uua
YDklwovF2SN9TIPPUVpz+Sex4ynzJ+muZ/3gJ2N1WvpJwa6ZP/+z2IrhaBf+YxH4
6YQUhC5f/aAE8FpjmJk6sGimRXZPUQLLMB2ThCn6WY9Mw5jUXE4xR0momHIX5SD3
uz9QzlQU9S6yjZA7HJcgLpOGmCmODcT0dzknUxGFOaGbDk1ZCD3a/wQF4/rodA7s
RLgvyLshupaGRnIMhWPlMK84lUZJs/yRaqn1xBoQxRBz9oaw00IGbPTLOrXVYkdJ
JQJNgW+1aRaiHfg9he+RRRbBnfgApPu47jNtCSj7D9JBARNVHRbFsgI4kwIdDxNw
2RfGupop0+cgCI30g96IYlQ4/WqEWumxwckaUhgd+UYpTSRMCEYQH4arlf4D5HrW
gKg=
=2xT5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f0658f58-ea49-44c1-afc0-6cfdd35860df',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8DHUCaSHy7eNOmVnekhrhIOBR3qqKRwejTYy3S0fvfsmo
Y47fHk2WyCWssbs/LNfb516VU77mseNn7ehqOT5xXPxR6O4Pa3+ZP+vRfoAEcNnX
QjcspTUcrjJPV72BrKoy0l/sVfDiwuq6XYO6EftGimRQ7Gy34JdnLkpiahXMXHae
kRsmz5HWN4chpgqFE5FMC65XOTy2Il+tm3DeX6fSN2ANrMnA4wHNJ1gRQlibXk/D
THFNUyPx90AaCbsMvnkcFQ+x1z4xdYArDpnbdcIRgl2A/dSwHCwyClU0OLFAcHWT
Pzt3ahMVSEZerUndhOcF9BBM4op6NNdE8TFoHhuyl1IlHx4FHcTtM1doBUZkh3B1
lhAk2MIJu2L9wQYEjivqP8bBndxQ2k8q0M4L8z5RWIEzgLQcPC2C3cOj+6AQF6w5
oxdEt4x8hykvw5BYnxqdSy6KWuKhj7OHbDzY7u0MWZ7wgFTWkFgmLpny2I0+iyyW
9cTYvl59ngb54bMBcH+5x2UpVPz69e/9hwXoWTQWS8HFBwHGhV+HdPTod69iI1W2
h/ZjfAcLhrAI8d8mBmBPFPQOfo6RY3WzaUm3rJBcGopi67SzoJyDE73V4tCjkWQs
XMeXQzBbd9GA1L6A5QfmZ3wGfEOKwtIB6ySaT3/9fH03jbmSEz/wR4OOQDbfXK3S
QAEomU2FGL0w4aInJLZ0oZFjUgqCwqC7n585IOt49mmJHOac5raQCNl7DIOLSaUb
40Vzr5iPPvI/Y9vQ4G4e5Fc=
=w7Sp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f243d851-63d7-45fe-a57b-4d2149eabc7c',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+KvbearGQowZqlfRokXIg+RDYV02f1ZRzDBySvBfrSK3W
rCHHQZHgRBdYj3qOxmREQQ+IyiQTm7Ue5NXQj89aQbYqCKp24pU2ghTsAuF/fsDX
0PWW9JL4wduNYMZifKJBJJ8zDSLqsdJSQGwaN+hIpQj+mnMx1hf0EXMwOGvkN0jc
+qLlUjTHf9P7yj7gyX6eiEkiHdvPqbf/KDBzeXRihH9dQMMjuGIsH1fgjltT59R1
pw7Qmg2kbDdbKqc1uG5ytVPnu+2LDxMtPFCNeAe77oUla6MImjn1z9KapQop/3cj
9R3UTp9Bj3C/0kGBNJSIILjPPQ7prv1migjL6AZGfNJAAa3NRaVkoEm8ltulv8XI
gzPLCMz1IzX7hNEjEKMWcH08M1qeibBDXsQ2nnzyxkL8SRt11pAmxPyYeXEUGTbX
jA==
=V6rs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f4eebb9e-77a2-425c-af3f-11add83b48d1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8Dd95I5YGCqySLaCrCGtQAsP+Ch6S1kegbPEoUypfcUmS
Tqp9sBlnoUsT4VJ7RtpE5a76PobkkfUfj1Vh005QOggoaqae+K0z6kw90TNeZbNy
p4uVXxbqLIlysVF8NjJM7bGdMuhGLDiflNrPN6ZjBE/fDwy+i+3ixTkMg+tN+2UP
Psu2KPXPf7tBUhUxZx/ojuKqNk6ZhUKvrwEYemFdZ0umATMg3VnWdbycPMLnWCYS
u9Y2uDV4wWllGGvm/7gVlD6b366q9ubJkcIoh1LVTDJcyvjcNTOuKlyVI5Frd9II
s1EJry+Zfq/Qe54wQaavFzn/NwKScToN0YPnp53l7HjfAx0AYLWlTERjL+JcOs2E
b4E1+x344W2YrMXtgyXrYs8DoH/luF1tG2wwEtWjE+Fk4Q9A4uW9UN/Bz8SJj9nW
5gc/8XoVvyP1Puzm2B5jdzkb5rWVxQ6nngDY9t62Ehz7NPSx1kUJ4UT1XL4ewRDE
apzad2QKPqkZBYyPoKXmE7vE+lcRFeb7JV7kDu/VbKcktn8f8S+Rp+JYg8YpOal1
u/CpC+YXcv0eNCA+63aD1JERnLEAy1taHNkprkaLpiRMVcLcmPN2bU0dYfJL8AQn
m/00srP1mHq3mL6Nlxe8e2LIrb53Ao5NEY4x+3Bs5yXyZI6bABgiWnH33+Y50iTS
QwHhLkt7/MfqRnyzEg7lV/BgT/avGBgxUf8QBbc6MW4cKcLl8HPfOanvYeVMzTyy
uJi6urfnUujZpkTdAu4DRdDtexg=
=s5Pt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f6f25b4a-7640-49d6-a181-9b87d46ad5a1',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+KZZlKnXvRzGyb94iFtwZxtUOQo4bqo+SwXUFAykRLM6S
S5froDNENFBJureZjwUc0LdVhv0d6tpu+wTw0d4o0buMfak2+ZLC0cbITuMLtEnk
ydJT2KlmG1lcnYCNqYpPBl2nqXPmZlXBlA52uJsHojw/auNYsAFv3PHxTimwYd+8
7+ksrs/xZJDGHNXiXejVw+c+z4HKLmlEJdDq8C8pTNFO7pOkxERD42X0aGES/VLw
9LCHZil4Rdbt7BiNoE05mflejRHdZoJx9DzoRywgQkyIgd0Wn1rMbO740Icu7waO
nt7hDQAUUTdWN7Y5Qy9b1psibQOXbGkhqUJwX1oxoEMErqq5OY/nPm3NBh+FnA3r
EvFv/r8tQYpIa7duTzZym4EKDDD9N7K3RMBYSprQxRTRQzE4EC9kVb3J4rTo8NXB
LXpRd0sTUVJGmRXM7XQRQ90Vwns9rhBFXOoQ2a8AhTgNz3VKZkew/GUiz71XVhuV
P9Px9VHs8ohKTLr81L1bWQvBtyYFQQiDmbwNygRxQBvj6o5q32PrbwCBRs9Xm6Vr
1f6Xtz3e0lzcs/DAASVKLpIyrlbR6kncd8mGauBDV3EmZ6hP0iExgmG0GUecEnTU
gCxv394HFN7ql/iskrR+SlHQDKj9r4FuYxxqB9BYiLHXFe5jKaZjKmx2Nbr07Y7S
QQE19JkApe9lgzG82FLKwJG2hDGN+JMYfx93RI+YNAeqzr2nrXS1hpbBxon7buPW
3A5lrnkuovPrjN2Cm2LfU9Qf
=VDab
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f724e551-b527-48e2-adaf-7579c4dc90c4',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAiDUitYrvCQ/vvWZOGkijQIUDnQaBnOSkpH66sVqriZb5
wqhLsz/kHzs6Ggxg3eUnIN+WTnAYXdgpYBLrdnpPr7TzncPW03HXAWxsP6T6yIrp
/IQCYKHodsUxe+gLe/xrUkwte8YFUITW6+zxq1Y7UTHATS5qJKBRVZZIYQ2jKPGb
WuOtzmsz2Cj6pNWd+muMBkpY+9KCj5SNpjbRZeZ0YQArDErq3H79FNK+yJLjQ7c/
Ud89TPiRdPorxjl8FJtzojfmJ+h3biy2QT2HnjD9n9BxeZsB7aVnqgP7nkVl0WHr
Bv00QNAkWIrb0Gkfj0JLGzUUgLc9X+ilnwI4si+aQqemFRWn4kajcDoWvA3kqX4q
OhQGIYi8KDAgZICiRAU+JpE+a2o8e/WdzaVubBoK1dKGosiMNh0SDOM0+3/ERM2z
s5SZui4vAgWXe5SzU9ZOSO6nWuZs5po1BPZXsUf8j9oE6EFIsh/FTbEbEL7Fi0LL
KbzNEOyJfFHjrQe1XrBTZpg9bsMk8CMco47qYrNuaSg6DhRsV6HmljaWCMcLHi1B
z5ffAe4rjyxyHz9Bl0UY7+oU3j2+bOvwreXgVsD6dcBlCf7VGbZJWdZXLUxT6LCt
H6yNFJeWqaGNpNrjBwvUZrSMpeMH3kyCafe1G0L9dPJ8WneKm9mRqeec8n2nRn/S
QAE8M3RURkzG72oPzWrwjHu4Oha+ZxG50CapPExaXJPyOH6l09XszNaikQAx3oOl
ujEJTUTbsmUwYr80kMiHOBE=
=N/YK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fb26bfb5-4202-45e1-a78a-889a8e621bf1',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//XgUYM63/pMWS948pisEKfQlJCIrxVCHVEpz4DTHYGFNq
iW4DCp1cPulm4fEIkETUHaMwyok+OAWUcdqd334RBsnx8GCLwyYMOu0gSeiM/ibo
C60yeZK9j0sJYyvIo8VyFBxDqDh+j1MzkZU97Rjiny93QU3BQTJRzyACp8kH1sGJ
CPkyURmIkXgohcqY7kuaHSdfG54NQMgIBtus1bZi0N+BoxJSRIFOwS32vqlDdnap
paLMnC6m+FOfKA4KwMk+Kf1SgOSvmZRZo5RI6HXEgzjJ1k2GicwNwAUkfLViv0R/
Qte6DApKN37+a0CHlYYSsGqoVXIOC+Cd8LRmwjBLPk5byYcaHmh1mj4DG4BRiiWW
CYLvhAUszdAxa0uUz70uoof/UMHZSV8sMckawUeHLU7QluxRv9DVS1R3TukD2gPL
qtcS2u4GMj3QdrJeKJAuJRbU75IQWfjVR27eL8Y6Z4s+wLdV4QuYKb2ck6JQZ3L/
pWMvLeFUbVyql+lQELCXEVLsrJr4C7atl39h/3fTdtJvqwG5rhjg3Ek7AQRH4DXt
eIgT+JJ/gbPbArP2ulVbQv1+oRL/zPCLWBv+4+vNcHNvpyZ6T4nvR/gM9dWG3EpH
65ZGITWwnGHUXehOtzbqOqkmFq0NnZtYJ6GayB7H8iZ6yF3ktG/I5HxnCSRymZXS
QQEwdFLlK0H5vP3yQlAR7MY1x8eOlYu22QqTYVty73p6QdRyuNIXghB61suK5EHs
Ffd7TgP+4x6NhYqrXrcG3Nwu
=mP8S
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fb2fbb19-9971-4890-ab8f-8f3484c03f71',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/RSajAvF+s68JMpOuOejW0nnB1XdT/hg3gGJSGFOYROHe
k36hrEIsf+kgV3r2csn2S8uq4OgQ0x5S4yyN1pof4ktVtUW5ibOGiH1P8IgXWK7M
Nwz0EJiGLwxLg7rwtR5I1GDPHyWizsbiL55OPv+pvSmXmgOlOkZD4AOJseuHyMbq
CR6PGaR49vIxEHPFebTuOXFwJFpuCVhVaP5EmGgGiNQFw96D3FFm93jib8ei+gWV
xcHN4FyWuucvRVg8XxlA/T9vKm1k7oeFC6lu6YJopNgFpE9dvPb/ttxrfdExeca+
2a/Cr1aNg7jOWxZjv4f23Uhio6FgQKHIN3P8Oexxb9JCAV4l7HEVma0BIKZmfDg0
aP3W7oqfH4+1JWtHFY/8N1kfU72X8D+pyPpyXP2s+BCe4QWJVTR2S5Xc3GhMcDur
Dgw/
=a+RM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fca7cfe5-f749-41eb-a858-ed3514d7455b',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/akayZYL1QmYkvyRCCXfyaTkZkP4QqBTuYlubP9kNm0rP
4lenBiaCQVsokE10yxK9U/xPCDhsHom/TxFZbbYuwsxNpJPs8HfT0WLot/Ld0Vr2
J+s1ffTPVEE9RwsGxnS2WpjW6Z/nK7O0EPH+OO/zlDSfSdhchfRzp2tO1xLUeP0m
UcUPkLpilxTeNsEMovhj25OuTIhbAPaKeD0flBW35xYdb3OJKfJNSvG7SNBJu8e8
f6eSs4MFjB1aGwwoOMeR9K/Nr8ox+ufzrjyKetR0f3ih9NVyR0dKhPxKl2Cj0faR
CPkD5YKHNcJOO1C3MoB7QjCDC17y2mqWgEGAMO+tZdJEAShQ9H5la0qgJuslTQ9V
mOAmTG0SxeWf8P2cGLzc2Ld9ybZvyqahgZEWIVegFAMywoGeEol1JQ7KDHvB6BPP
cK3IBoo=
=W4dW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
