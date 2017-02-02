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
			'id' => '00f1b43e-0578-498e-a15e-92c0af698c81',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8DpOHh5kGH5keA0EO0kbGKw3RLE1XtaJnP+iylcRNjI5o
+6KwUgjKSEe0FrlqS2No/24SDxuMZnYjNiK456C2d7T8GDGAudPc+wzK7UVT2M8l
okzPOLRQk7SRt9CnjjjV4azwCkU9XMS/5HZMo6H8HXq537x9WF/O33OqiIOzJ3+a
GwMKwp3Lf6iwb1WkfNUEpSk2QVWWDxTC/WjE1jPsMgi3r5CDqSZ81YIjutSWl86Y
nG9AlksJJtLycfzNN+H4JSYJkyOIbJropXx58/d1qgWSz/UvbdmmQR4stUPAle6h
1cM97YSX5WfGb32A/XAyjeqHuylzSCniN2NkybDz1DthDJn96rGiSUB/b3MgSPon
pHS9vhUWx9uyRrhzh/Gc266bqrzHVpCYVKL1ZX/k+VHR+LsyGsCEd9RIlXvdl3CK
vco8QcQlIMgIBwmf0vZ1s1Uq7T5/q0uW0m8xXBc5Sxh6bg+uuTd9X3Lm7cp81SQw
779aYX/BeiplWIkmXceseVqoX9qFZ1zUlEBOKYOD9ZxfH4YAzhdksyQk3W5Dv1sP
S8DKapurR8X2kRkz+wCURQY+Q05Ntd63bHCbwa0dyXf+/lY/wulVAtU3vmmmP2gZ
BMDhxUE1OWjPdelBmU5cZN58gB+x17mCVp/0gSLg8DCF7xzi5k8f7Doep4MqUj3S
QAFhUDFTvzGYCjJqqA8bGZelA9wNjhtnTD6fz0YLfh0vrAGzSMKSY8sNNcP659DX
BzW/NrffY229HOPwfzwwFRc=
=b1JM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '048b90d7-5a49-44f0-a377-17d20a8d85fb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAkZwCZMT6CGfx9WeAwAG5BbNR58jTQBIpUV16eLjIz9DQ
bs2HM0m1BcMnthShRHT11b97IxoN/SZZn8Ez9VwbKvdztu/HIgiPKR2jDeWbOJIU
aeiXomZfv1DTXPLOZU3Buw1H+ATsrGqBOcVgpF1dSMPuSQV7PMy+f/yq1B4ua19c
pS6qVopMlSEx+uElrArQs5ipuz2E1gq+30MRbtqiW3MldGmHplsNZeANfCUBFWWf
F/vFur7Ylg6itKmXhyy2mvZqlnfWAkJzTKIKlLxIgR8TD2y+sM4/5KPHpNeua0uS
i3k1OSGP77wFxKI5pqeopnJwU8AdAyCoBeilJwVq/CnGCGBg1EoXp8I898uUaApy
1tubmD4xcSZ96acvfCoMKA3vuo4I5TCgnEbQGU7gD490soYeXFQ9uSMLjJBRMrSb
8xiPh8gJiUC/DQRlyh9YewA0iFXvp3sLa1jmr2q9SUnEzBKT/4n4ZQ1+m/bFQGcf
meYOlJXzKpTyOMSfSq8Gtx+LXnkWBqx9hdDxuGBh1byCQFRyxbnLReK4KH/M8HU8
EMSfbiP4MWDx27VmsTOaucGWnEB53p8LQHUMRFKnA8OEMURar877qcsogfTDzOky
D+fPvEyTySCkl+Sz3ox3HFeshIvz0QgaQRCwU1q5bBbQ4AiiRAEGS+JMEHOtVw/S
PgF/mrlUs0roUPztbdpDNsOONAcdlpX8F4qJde2EMzUkABqzWyKzfuLxRzuzkZ4r
Ue6n5lSTdbAMExK+BFMV
=ADQb
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0eb58de5-94ff-4eee-a581-b46851f39360',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//cX79PLdKAAQTwlmZtntPP+4oMAZaA1tMWJsK/gtUKAgL
3tF0D4a078S6NRWhqIugbHumor84EzEwHznI3RJzI3mMk4zkY+qqTQm6D7ynKg05
rs+CbltrNdlSXQZZNWyq2+5ha13iH19kbHJeIbMA4n7ANf6P/YZe2QNdcdQJ0QbK
75WrnNAWPBp4K44oWz3pO1LrRPvTPIvySIze3SvVLtRwn+XbZrQYGjA5+kR3ry/w
SEeMY0TZ/bxxbJPXpLXgF+F4hLLYLUE/Z4gYbznCPn/xuY0TBQN4RAK5OO7pnOtr
Lo5Ids0+SA9A/n9QsGXQQJyr3pgpzNClKuoYSyzUxen5hSTik/MmAtMuRZxSFo0+
1sI0JkC2XLuiR2IHCc83Xxid+UhlgtUTshKCJF52E9bGS4h/SIQ2NB7cP5hcmGR8
LdE6Q4cErd+qWS4Olb3yFt6z0jp7VO7h/WjjoubOqkVRjq1MrjV0s6wUupFNvAEg
8LwCtyq32oWHm67RkxZ8mCRnk/yAi9q9asS890NGAreq2U/H4IIHNYncw7H4fs8U
pStqZs2u6z552gVNrt0PNnlueJki7ic1pumEAqNYp/Z6wzYofof2h59zInTRei65
28eWL5Bid4ZUHY/1DTByCYGo1oBdzjFrUDGSAA4eCghd/qAAyyYB9cripuF2Nj3S
TQFEX9j9u9lWgmXmPI3oN1HDQylosQ7Tjsorunlc7U0tuk+Bo2L0qmKHiewOG3CD
9W8pwxd0kel6T3ihK5jU4lmCqEqu/7YRCKIjBQ5q
=EdXe
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '169ab03d-ada4-47c2-a340-20f80d9e5347',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//U5HMuydKBIjxVk9rwyF0nyi/IVQInFapMBWjDdifF1O4
Xnw/TznpxJMFKPSeoPl5jIdl9yXjj6rYqYobsR59o4Ou//NbtvAhm9P5WhAWLBzR
pSo1EX4GZcN8l7Xumg3bW2wKL8KTS++oj4W7+ZphvAKKr7GOFtby2CbRHy8849pK
xXnrvB1vtaNsJHNVUeOO2rNBIV0xcOW9953qKaLCY2GCNhME6BwPt7tx/JYzABLt
b5kWaImu9HBVPV3QjywBclpEEhD47WH21emkWscr5zZipjOHPoFLvLqCz3el4xZL
NeNmUOc5k+Cv7AT/RZkNu3KTaTk5QGK6DljShHjOuls72H1/56neH9yuglqjKFX8
uiXARUyf7KKpVfKcBY0vUZGPAnmBUswGaT9UKVUjEtkwqtZ0C5wbVJAvMwIygoT8
I9c4/HlgO0p+amMS9wj3l0mOIOztRDcVmGminrq7yr+ElDQSaaxfYFbC5EpktDnE
UCQc76EJmY69xtDlDngM2Jxb9w8MYT3HX5irouu00CMSvmwaJVMEQIyaSMWO1cJn
0wbj7yvV5clwlgBOXnF7Qiu/DeUbUFRbLm3o1Q2BG4NSWs4/UY1mGJdXahR9jSgC
F8pHe7VIt1A1sAlAakT+7EXhR9IxD6nCKICXQUn9ZW10Y9VcnV34DC7plrYpdwvS
QAHtkxyjpQF1BpmDzFIEIGLF/eCUKGfRJ9VNoUYJsbzU6o1lO+Azq/DOAU3BeFPh
JfMkraDGqP+gwmiDsiUJKas=
=7Lx0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '17666466-65cb-478a-ab9e-da5ea9439e5c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/6A4eKLTn980KN+b1/PLy3vTB/yauxmVdKxDFnk3Jn887D
L5eCGbE5M5uxux6VjaH5U3M8KJAvkHsp7FvIkji9zC4wr5fr5fAILwUiUMqnzJRh
nsPhVGLxF7jdlAaySi8wjWP8XC8XnBepjbmb7Se7JSCl6VtL9hphzfYX9f5SYWHd
j1Nf8LXvcWtFQJKXsKM7CdtWJU+NOAlczZtXdyM4xj2PRDFzKPUAtIrc7ckORUDn
qithQj/7OuuVk+K9ZkBoPGuumwzX/vvz9RmISQNT+apjCQcY+A+dQ/oYb3ABI5TX
EIXxWXnZp5pUzG35cw6EE4VTTI06xKV+ohVOkTtrKy4vdJRW7IyTffsZuEQVeAte
klN1iDbcZPnEuAMyglotAb/+vgmOGl/lMYkkFfPdhEYdrAfgjgasDXXH0nhyyTAr
F6d47mOrA37Ds0BfgPrdJb7gWrWoe/pZSt0cc+OkCI4v/sDeYTo4E1r0kVyXGaE7
cm2VeaXQsJbk7WWL8fxDqfo3AonveX1998lwjaRsPZTXv8oyDHuRW45lMZLPBK1a
PR4wYO3JUDTJRKCJWJEbyEGW6VAAeO0v4LSmAHXpPjuRJ9g00TYQIN7nrjafYatL
HbF0GprJ6VNtP1I5nJsAt4ynYoHvX4qgb92+gu7qr0AG+/H4d/bihmygR8uphLjS
QQFpJ3r5BheqblMV2SrgovKKOnbGSmClzabi9zJqJLmoN1B8ZEArN0V39tX4AULZ
TpzfphbccjRVFjOJNFFH+lnB
=qT11
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1ba9215c-2327-42bb-a688-5249f1ef1231',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//WDIVt+inDvs5PWIY3gg6B4nJLExko7LHtn8H5rt5moNZ
cE9RyYY6wEW2rlDIBPiiZmtFcRvUE9VF4fhKDoTbIPaEHgRXDkCz9+mkRd14bfN3
3vKwCRSpDTWfyXpX5DBC5mCwF+R7e1D8BI8n3bY4f9q6IIUCJT2iw2TXu0AfGvXc
y0oqksmyUjv7rYPkuf8OBl0ZkfGS4KQCP5/lB+cK1K62gS/ng458Hv/I88Dhh7n/
RLrYQB4oz2YIgRzCWZuX7rmoXJIowZyyTu5ZVyBaKsWDPKLGirjFhbX8lKqDEEUZ
C1U1P7hlhLdyiOXZtZgIcX5oYowYl3L8j4zHsKcDKgT6MAeHioK1FvU7gcQtuMZb
/CNnNbxe3i/bXL5mJ3pG1tkL7rHlxctbgOXY+gNA+97XMBgDum13mQRtIx5jMgKC
BhThSHn1bMia7RY6xDwLGROImnHEcz0e648LCALLTsyd8lyzNN0D3UuGflKnM7G/
2+l7CzGqyrmlJjzkWWPxniReL3X7uSrHafh/6KMQTOrb7YDBGKuQBUNO3e5KfYnK
7NfIVtdvRsYlDiqxQXOpQ0z82YGm744m6EG7yrOXeYDjz4uzf32RvZIhVmm9Qipu
bCe7B+89nMjbjN/nI68YH8UV/1/7cM/3RQ/k8PG/P43h+zlaCJV1Sf3tHRmT76XS
QwFApjMt44HPO4Ksvmx1ZN8Le6i9gTy4kaCsYdtRv5FhAFkiNcJ/K6gGs2KxWJWA
9upK1/8li2+6ssCbkXLyWZjb2yQ=
=OEId
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1dbe2db0-bb7a-4a78-aa6d-a1dc580c0fb3',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+MTW/GCFoZZQyLkTnUrHdDa5Xn9A9IEYd+cC5LYF2mWGM
ZsbsbBXvxWRVArtAwcFaLB9HBIP6mLF4wv3rdHRR3zFlRQxVm3DsO+wmvXkv7XvI
lQxc6yWlBvisYQo6HC3GTX59yrs0iPdC2NSN1dAJyupMn+sSA1QUObUimYoKoF1E
m0I8oFRwNBeVRjvxn2tWgFvGpfiqpA65moLFA6WSo9WfTLBMKnosDFm/iJOmiYjz
WZ1Z/h1Ak15o0bs+52Za67jVCjzWrK+6c9UVjvXi2n2ki17glVCYflSQ3FcZBYAB
maenvl8H0N9aTln53+PkrktAN3FKWhRvk4qN3p3pzG/Mk7+mx5of7/dDH9e5i3qk
jVaGJ4w0Et83E636T8wpCz45YFj267g36QfZ8u/Y3xuoGuaAnFPUNvbyfxvsdyeE
bZ0dADssHrauHDvmNRK5mT8YAVYwAvcm/9BC8f6iIpXg1BjKzNUcfxX9amkMFxRY
F11fd/wSElYoZu1viaM4yc8yvXqMIkV3OyVWGZzE/eQyYGyVe1GvISp4hcvoZey8
ku1KQ1OJrhVipAKd7cM/dgZguVR5FVhh1u2vPnPgcxK6BI6ZRU7VAOrCik58hJDn
XeFkS/7OkyqUl/QuVeyOexixVgzZjhfspxQNQHqjbp896m/YKXebVf5+5WiFV6HS
QwG787XgHl5RP5wAEEASOra7XNbjpzSTC6HuEwi2MTI9NQREH2V8lGXKeafC/nn7
CEtLvqJI/COJ5l1iezh9Q7MM0vw=
=S6jJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2013320e-8841-4537-a45e-0c3ae6e18893',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAnX/Z1Lc7kLq0JxUaXwle/0cchYwCHzZk0Fl73BEC7Erx
Y+x0HAZ0XxIwnKutU3vYcBXZMBVeyF2EaTFN8EnNfcw3CtcuBfDPANiARZ+u+97d
53hDUo1BFE6zAsXng6evkqixaByNgGjmW3wtN/5u5MkTCHXo5Lv1nhLLY/i6+2lV
bdVqHlxx/o9EZAASgKwtYRdV33u7NRry1tHvXGFJvkuWtGpOc86ZtYtRlO41c2dd
y38oC3PpW184CcggFrJB3HDrmRCkyy9erg1smAkP2cf2PZHy33QyYQttKdg5u9re
VcjAPl0N6m3Z/cLLd5AOmKCYeEX6TTj2niCyFEcKqvFGx20WSaKSCHes4Dy3gq+n
rtUkg9Bt2yGdZZOfxYLs45Z/l6uFPNDvU0xGu5P3HCyYX6fUW6TqLB8LZSdU9QHI
RLcDsIM6x71QXDDXX+20osfJ0pxEbCocylV8PsLS32nYvi4AMQcBfPsFB7EgZf4b
ptoIB+2IjL6nK1c344ITJMv3vhq95wm+H7JOYnh+/+fuOOo5VZK895DVhkYnPk9T
weGins5k4a3SsWG7xx4eUOKwYnIeeBpPg8KpIudtFE5TExLgIWLJ2t/P9wF+Toc3
zvBBtA/kPC9BRDRMHVtUeu7NV9J9nkIJWqHRarDfw7v8XcBLqwz0A584zclSyy3S
RQEEEj/w6Y3d7r35GqbO8e0Gsn53HsNFQ3uuFdwffKeuoJj/uO0wuQgTLiRcMSRs
rMiT+94P10z5UQwjhUIsb2RKJVOw6A==
=mzvR
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '203c9e81-85e7-47cf-aa82-ede4b942d210',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//QlmHql5mI7vHtI+ctng3WHT8acHRXxCLNYICyyBZk/kK
/34cEk4f3/PYbOhGXtbjnnwt+jh47B/SgJcwyosRfA3F358O0Q2tiqbp4WdP+Y71
IZenl5KVuntFlh6WUTAP08MZHEKZuASsretZj0f8IUzW+E8a/UrbHaP/81+Xca8u
ILNZqPf6qYS3TMxSjDmV3R69X2iBsdjpUQ/Nzt6gmiQ09UIfQzlgijz77EpU578K
yMGXqmg7LzgubiQ9dTpyY+oWj0KzSaEbw8CDXyEcQVcg3aRCL+mkqIsYzlfCyJJ7
xd6eXyiLK5txbiQ0NDMlS3Vt6mlXUDdZqAHylNiGsjjjth4YCNP7d2xY3+U3B46s
RLmC3X+jPmNZfxp6C+d7FZWU9g4KwMERydINwvbX5DLcady1Rbyu95sNnroyIR/a
OWC0PLVAGKDYfuVzmecx4QDfOON2FVUZUVcwhSu+95RwuB/d01F4LEpUiqqoAyDC
mm/rsNui0lnnp7ykAAeDFYlB0f7K8y6fTHeiCBI3dXY1FsUj5N7EaJxuxMl4UcMi
5bTTJEtL/hC5Q+qoSGYb62nEq7UNjdNSxXIOwMIw8RYbhQcQ7o16bE1PRxQ9XFqa
2As77VdChVGM4ZeWGRpAFQdP8AP2wqva76KIsILzctv0hdPT0alFII8Kmpy5tmDS
QQEF5KQlGrMh2hImwk71S3LFsZun82Pfc9Y7HE4RLBCfKw0VcDU6IMTpi9uoHLwa
FWIGNHqtCmtQrE2KdyqanisD
=gMc8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '20af726e-c875-4bc1-aac8-1e0001447ccf',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//TfWI0ntjKLX0PrGdfZpJv4BauPyMhKWQZqn4qe9e50N/
OyLCZEGRe7et4APQ3DpSxrsDRkXIzErOad8STGEDhEmNh8FfbxHw54DXcO5Ricd2
Ppymedk50Ty74dlP7UeBtu6/xieFXHKWxciC91tvmUgnH3jrk9cm3my4BC8hOTkg
dxFIWPZEQrY3TOFcQMPMXzUd3s/a98/a/+P6bkyensWGxpuP6EilOphhKmS8TBXk
iSBLzkxGUODZu/CvsOo50k3xD8gM05pNdmounZVRhcjI//VTXVfbM3h6jdbWzR0h
OkBRNkGqu9AZGA/qY6Tfqy/VIyaYIEC0Y5yXtYKYCLD/ZMiCC5Knlfm478FO4hPH
CmI3JYpKZtQFyjsVYPrhS2CtbaBgdTHCOVZe8Oc5xqsT9Z5QoQd57dg8zjjQ0aa3
p2jEyoDeJ5xWcE9Xjyv3ChurWGksvW1oB5HMf9QJo2ms2BecYfLgNIKCNr/28mwK
DboknqLosqrGtR++lhB6PfTYlItcHK+uEIvEmlpH6zX13Typp2rsa34SbwcOJDce
j/8NRYGUypo8gumNb1QJ37RCOBEIyEUP9HWAUAGUOL//qjDABuJucdXL2IAzvy89
Qzo7ozZj+56vqattbX1R+HUJ3gL/J89wecC6c7/PPmkXioiEORfXaFV74IP44qPS
QAEBZWzq3YXxL7MCEAOcArOESVjGG85kB3yerDogaGbOMNiZ4fgXDGhQzwejeKZY
QcD1/NZjw6Mdm+YyuIzLxdM=
=qKz/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '260627ac-cf46-4583-af12-e6849878ad6e',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/7BgV81+UtA88yDNhCyQ/NPOCd/+qBhjYyXDMxnjpKpVGl
FI2Fda2y13+jz7q9HTojmNjcGEhl7g7EL4XWaJCcgWzOd63NsYUqjPTx56mhQpc8
ojTS9sMMHYXTBBan1+4arQ5FQhQJlrIIH/g14bc4KyxP1qdmxaTdChJQZMii9L8y
pbyL+/E7BhtF41e+CYEm9hXWpM8nLSsR5YkANp7Do2tJlZbGaLBWvncXVDtyyfzh
rGRNdaEiK0aHrIH/Y5boSWPcXKpw3C210f5AHqVM+fkpgjmIndfbGxS5A+vU0RQJ
V0tczgd4c08aK1phHHY9BWY5VeL9P2AWbfUYU8l8l4AqmFgwXc6W7EIOKjS6obNj
GzvF+RZcHRTHwqLHVwXwElwQwGr9PE91UI+xLkbg30caBHu+OFKwY8HPkhiGzTJH
bfrpte661dqB9iDLC6NGnCgLCY/quRbh0a42gsZGBEdFzzk2VEmWMtaAN2aObyCv
oknnk6GMTadJ6QPmwpAizGg00RITPFVHUDrDhMhkQzO8t36Q7m6QbJXuqUWW2Y1P
P1lUFpEwi1DMN1MbBVI6U90DAFcMQD7LV0oyVQj4oqJ2gWjPSdIjV8+m+tJnp+VR
wp6gouAkG/AenboSPiSvDzuB55BJORnoMHRfGUjvCMhpEtdlBj/soBIm7xxn8b3S
QQH4v7DSbEYQz3/0yPWpDV+s/Go8HPCE68sPZ+A6qj4g9Q21zUcSX8IeM+Qx3X9V
WoaKnYEeCEBM+MiPIN2DbJC+
=vM9J
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '30ff9113-b2c2-4d1b-a785-a0259823e42f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAwMpOQUs05UTv3Ekgsu2riaa2cF6DPJU/fODqzFxL5yFQ
dYT+U8P5cBsOURKBXsS8i3S0nGP/mbOSCulpxtrBnhWWihSF+o5mFJkpxchOCVGt
ONkFLgnVbLtYldDw9u3UGpgb3l1eHsbpBs/HnLJq8cQdFlItmrTNBe8OoRasnpZT
kZJNF3yEytfTFuZ1uCtMjrZttERwwhJXdjAte3zzLLDacpgHbLKH7UTYsnSUBxKR
HIaAN25pmSvZ8fc6FXAtwNRY5KgaQgQgm4Z+UG8yfi3WkCMeb77dZfjyXpd+Ei+Z
h4FtsGyUG4WHFvc4U3GzMII6DyeRXJXcmbpNkNrUz+h+HlRbiXFWRF/3lrban4h1
bBGWeUFXhlly8hUyMUC6LJIRSnESupmzESrxAWZM3TiV9SY3DG0T6CTMflDs1qcw
/TLcvbi+C9XTDU06+6x8z0eni3GYtuMOUBsg8URq4KQEijzz+N4Uzdwo73zHpcvM
ujfYq/QwlOJPwA7/xSEFSl9/wFEI5EJNa1WekrQUg3TIqQOuv4P5eFaFo/9LoHxT
dPZvocxjVVr1rsB6qgMNQpsdQGg/OdOC5DCOOVW62jze0ySYuDmZXCUtvwnDE2q8
jbCrckpTB8tYVLdhP+0b8BZOFOSkACgzcvI7U9Qpq2evJKy24H0I7bjPqjVugZbS
QQFCeqmJfKUiVw+qe/M/8nnXKvnq0JjIXmg0h0ESYEs8GXS80JhI3wwAqaBfrIi1
TAuVe+YChV/6fjdpv+U64vqZ
=EJPI
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '311df05b-fa5b-4f49-a159-2712261a2dc4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAh5pjkb/ej1rN1YqcEuIMR9GMqOMQpymANJbP0ttWHmZj
hCTInpaQt045mHIungyTfHAUF2U5AhXv7C07lol5aXpXL+QlP3KRPynSGNDvJp1D
7vC8+ZP+D3KCct1XqVyGpzmQwSKfRPSS5welM3aMJYOA7kqtno1JtOQMhj6CLPPl
Jtp3QiS0DAU3BRyYZDbzxaW+B1yR0cl8tXftXc1zQDM9A4dP+ZKCIQGTU8E4ukGv
UpNURhLui9R1iyPR4oJi4kzhdlxqdnY+J+J5/T9g9X2pf9GDFszZOqmHeJx+z1Ld
62wS0GP2ssDg3RjcvLrocXR/8zwjVsgA8MumuzbLlH/DiU93ybBEhK+DMAPV/veB
7g/WCH7Yv8EHnuMV1GyQYtrL49+q3vgcj4VJZR5pt1mkURmENlMG58Lu5fk5BYy/
O3cYsKy7Zt1ATH/Mwi887tNFejqPK5KI/MB5X/BBGQBO1D6Eg8T1ZArUGNiHGFuo
/x5nDKTt83p4omYrsz3X/iZOtF9kFGJr1PWdn1hI98lsa7efY2kxywGwNToaW2hf
mR2rbjR6hwf6QxnBDaDRMLU9tXlQcjbAE+AQxjy2sWBdJ5o30TO7RbD81sSpt0D/
7iSGrpwoduEsGz7kfJdss8U01+vCZ9qM8CMamI+fhjt0yX2YT6++hpPeVLddpqLS
QAEES5zhOdeAgGWirbqpZq5bbwJRDlfwvSZFTh2vvVkC285lvKC8X0earHW7H1Bz
gGGW9BWOO26/v/fkw4B9WAE=
=Axqq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '31fecea6-064e-45f3-a7d6-4600a8600858',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//dNOoMh2WEl5My8e5k6903iuRl+mm74AmnpLI+xA37mAr
WY/oUvOWZcCykt1X9Ff04WNVyQsb54ebldA4HqSEg0JtNe2mvgPxIqUaIquDhr8n
NUeVrEDYGCsucaD2l+X5KgQpWGyyvPNbgywxCn7xxLcO+PwpsRKRed/Cu9fg2rfN
77x2xCNJZIeqiqoJHjNsGLwZ6aSGLWeUpZAyb6OZJDDrduLD+XSodYF8tvRmSpqY
K9Upook10VlxHpLMC+3+phcYIT/d3JZhQAPCeY6cLknBCvzOQupEvH1VszKeKhSS
u9NRA/QEECV6DtiaxYFDy/x4zbeksSr3GlpD41IT+YBqNS89yBOFTu6n5plpVVml
+0HbvnsshazTkLYf5urA8AWTMCc/+irCD1NBRr5tUTt9p4lM3gIe1EFvBe3MgLqd
YfSmYy0e9f0f45IrT4Ww1QH4bHPJugrF2HYFcKxBB6EnD/QdVp8gvvDY+9uE50ji
/zAYfadWjwiI9mnj1FI/0nd5TV4WtrNAo27U1pAHWPCDRMFuK5v0uB2MRFXJCK5x
U0FCH1Q6xEhXKOxXolYExiX4bhKZY72DE5IhsDivEvvJHX54ZGKtPL/M4Vf0+D80
mobUjyReim3cBhzl4cQQUhq8gkv8zinGDu552KuZztfmxKRqQEeEuItf12moTiTS
RAGl+S+c1bCAbRQWUWNaUPjBj2gvX8OJv6o5fAHRi4orBp9+joNKilECsHpb1XMG
Nt5ZW8P8LUlxMzSJcbIj5jyzQ0YZ
=ybIy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ad13a7c-3bc7-44e3-ad14-3f8dd6c415a6',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAlNZgxUqgLpmhUtu2s9+wHeSBey/4f8gjYqEOsSp1hI7X
AeQEWkxlHRNf1Xk2FDbe6wdlg0yr/jK3NPUs0bjSd3lIToVLjASV8JjXWg5S2/ZN
s4o9jOdoWWTo2Qs12+CmfCTJU517UWte9rpediOLlFwHnmnuV7M8Khuo1GjP66YU
Yu8Dbz1sxQVstRYTSaKdXjwktjpRyEs3YFG2NPk/vS3hCjzSWpaw3esS+81XQR6j
xf+t40UOH9rZ+mbMQA+x0JkzLOMG5LJxdcYsTKt2qX/hDTFfJfi9Cpj2LcP+kNUg
ZJPP5IhUxNSvokOwcX+Eb6LGIeGvjZxTp3lsdwZpChxl9N4OKmZtvb4XZVnNk1iq
sWbufgCbKbKgZFvh6HTYu+cVIAcyE21isaA2QzMt4lzJ9uOjYBm/uo6Wk5EaefaI
jYy4A+/YQUFDpuSWrsWkFBeoRGLfrFEEcbcwpn0LptuSnygLQx2FjQIWnPlDjsfz
qn4gvUDSWdNKGNFh5MTfhcaVoPD9SfzeR4WWFViAf5FZyp4qxGRIhAF7OvK7xRDZ
Sf/iiELkfgz6wbsU0nG7d6CZEwiVbRQRsOEx7kgP4u0ES5P60efYTbGu/1+W8QrH
PLmztpxLAdc13COdPm7MoWgBjFiJxbm/dNu4618EjhXHN1RcobcnrPG+AJsZKz3S
PwEQvZHPha+16DFayqHRaiNrRTqKlulullMg0PT27T7Mw4+E6vqZmobTJYi/q1Gh
pMpvgAn4eVkdCGTqc7M9+w==
=8SMB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ebcb232-cbcd-4ca0-abee-bc28295501f0',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAkLIqZGJb9dGHmHx12xvNhmr305eKb9nXAF79H79ZJCSR
kNa5CW1LfPGmqbPPAYDcVLx7csBYwY5DsVTxo4fnQOdqc7gtjJ4QMEvRFxKGvDfC
zS/mhvSnmmNOG/3uPvLuh+3Dz1cSKkJ12cUN+TS+noNBRJOeQVIyr9ixu3bt+ChR
67y9jbzckcSjDkJYgnwhZvsi9RZlfNyMbQBIf/o5zlg0I2NxLyEFYdvIXpD+Pphr
gg+XpoCx1iPyvYyNFn6T22ibcx/8izilYw9pfGaJ96zJDt0fuztJnCz/Ow8Y4OsC
4dPdiCC8edGfxIlX8n1tebo0lYJfd5PjU6iGGIO6d/sdMHG+O/Tn4Wa0S5Acp4SD
gnwC7d47L96//wjx4wQWllIonsWCUOiBAi2ZRJXpuxszf4imCpjljqyXrukCXMRi
KlaoEyDVJv1+WlFcuymjwgeNKTduAsZjkF51/uZUWe/NKtQ2YQGrcYz5Bu7xJ+eL
FY8OmvWjSAySUIBWGnChnaIdPrydU7g1c4IFlr0mtdNpKmZYjgmlHd6iKw/MzDUu
plK0MqX80bkluIqYh+YGjMP8S2d0plcoNvrZwVIUXfUU2ZZXefYMAX5sRBkc0X1i
E9F0nHwia02r95BCF8PY6JGz5cC/+LcOKKJff3osyb0Sd75NMOQ+3hkyKSEL5WbS
QwEqQVvW42XSIizDEuOr0auiyOwKpL78U1wJBh9eKuROdDGM2O6ZxbBUYO/fiJpP
mPSIcyTrNkHiZpKUw/50mIxiekE=
=5y8E
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '44357e1e-9c94-4cac-a7e4-8a28a94acc79',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//dusZfDFDCmBUVZVuU2BVFwOtxbW/0S9ucr+Zao7OiM4c
VtVYDQi9O5TSeDBrXV8f6er+NJKHPSgtU8sunXgD6tFML+yLblC4i0yernoCFDyX
7p/d4MSP43jG08nqKy2HW8aL6UwpS7kX9nLcCAeH8JyF8FCOFgw6/Mq+NoKLypBn
dgUAtp0I1vETjG2o/9pIcjcBYL7shGtO7qrsys1cyg/TSxAO2Ck4s+D3US0dN25n
A4Vg2jZ9jXodjfAM2QEAc7qSHMbdtzqiQCiQS+s6fU+olP5KMXH4PhGMJpDFDMGZ
/8cg153M52dSIro+fJ/116egvMMEo7tFfIs/WIWTrLRtp4PxI6sHPqVjJ7jiWoQ6
DRUVwwFBHYPC7xeAd8BTWfffimHMh2Y5DpPHv5VpqIzOaNjZ3o82IKSiGh/vsgpa
U4uDAV8vyicv8Jtc+NqP6gBTI1Meq98ojoHe2Ri5/ZFUHovKMtE11G3S5dB4sMuy
CKhHLGCAV9miIRurxNXZ3wxQ4sX5FO+cUJXwfmGl6jrq0mCpgIGsSnFSzYNrZ7Pu
OLjj6AfWYis+0kIQHTHn3qgRk/ZYVNNsjFWgI69yyeWeBOzfm5H1ON0uqgZz+Qpu
1A9x7QTLXSwMdV3uiN5uhpOmQ94yBXmu7KTqH68oC8B7ZbFo9aFl51B97iUbg93S
UgHJVlQnXWnO7GY2+E/ofrOW5BuscCERkOYq/jxwoMZdRpu8nKThtt4ZumDeE3GC
G2wjBa+9LYRbolEwm94ypuao/hAlVEoivBM9X8wVUCqvk+w=
=B8Rc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4541a84b-ad9d-4e14-a338-989658dfe137',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+P40OpQvbr2uckO5oDa+6WDUhH1gHwZIcu+3AqVSdnsuM
hjh7+r867To6LsIy5qipGkAPvo0V+L3lXMzMGynqR95Cmms5r619mp90nL7unzkr
KljJsaeq+p58P2LKLq9iB1/XWIEn6ql8yJ6ctdwWQx6xsAvynle8SwPcULbQO07t
toTBbNOUSkgrzI4uz8h12wvRp5NOz/gB4c0PejNv4z5LagWnRDqxseyTrUyuy1pp
8ML/S0adH3nl/utGiatIltu7UDklHYVeAZ/8FNgV6Yi6WyAGvuqjYm5sj+liRPCR
tdMJJADI7Q2JYXu3V5h96Dh0AbtKO1Lp9ga37+ukPQzxRHGPQyV+HYAqAW8+Qait
oo3yzChQrL+iyTXJsmR6BhaoSQQDNBC0UnJ/RzAWHUw5OO4qsh0ha4j2/gHPUPpX
afqFSvdnNbSoRYThnNqRvR9C/AuiDFfjuM1l6ngpmDRl+cS0m5EJxOkvaWA6DY0V
3PxyV2FgvBBL1W+xplLGkJoWnMYBGtlH1KyyI5Fuhe9zii8jmixSEHR3MUqVCuPF
CSDmRqs/d/s/QAxGONmGnK8GnKLo5SPThPgNVV2+b10rkq2iLiVKtUWFCFAgeUC/
VLK6PFcIP0PJAhl51/YexFZPHi+JwJGlcjzmmUWKQtlFMCcJiy9xvSGliOS1S1jS
QQF79p+mCZtz2GDTa9LezYVJJ1J5y81LKuIOW7aMcrWXuscy5a/ZzZ+7pOypxV1m
kwLdQXnzcHVlOu3mgzJF3puP
=pqj9
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4738323b-7859-40ef-a759-7d343e871088',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/8CVFIy0TMUEoLQaueSlJp18cRyTjDWVRwM/yIHjFDnmpU
PiICG5dcka79C9U94jcrhWsPfdw60G+co0dQrGR+VbR/4wNBalc6AjSbiQAOlYyL
KY5aQWtXJGpeZEu/f5tLxBK5uX4ookvMznfukdZY9l+XMoi5L7OAG1GlluuB+o+p
FRISwatwDl7Rso7TSUr3Ayldlzv99gV0jaidteLI4ilX4LUMOX4WNQd8IgV57C+9
qvLVikTSRz3xxVuTE9sVUyS+XjhKNIVNizSrV3+unXKp5lRyDBKH8DROa35i7CZx
c+VgudpdAlg67FtfGwJsRvS1WEOxptG9nRrYGErjEZXkx02WDO4lhFLJTN8stmCR
M+YCBWQ30jXXLb9BRTYwvjw3h5g808b4B9liGEiq+SNVuQ7UVwwvgz9C9/lsExaW
C9oFy0/Q9Vdh9aEcd4KV76tuXlEkyjQxRScpgGnDvjd1VF5xXN8dgZ99qw/ORUkE
AM38eOZY4icEANVE1knnBK5lX0bJVLt7Uakzb6X53LvTTntm9giBY72xh5tquO0A
sz6yN/d8/KHOXG0BWLTrtk35q76rHIs9R0OMkv8nm69pKxQpVaGehptg8TxM3AmN
snIsRTUNx5a8nhakqJwcg1/qY05Fu+d7FsZrmCy+n1f5hENeMsb6VfHQtwb4hqfS
QQGpBxXbnursd7RtMymv7MD72WHhgT0B8AKIqQxlOitfRtNypGwlTeu44o9F7K71
ZRn2okaLSrS3vKOTj2kFOIhS
=orfJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '49f4d027-5c03-4615-a3e3-c37430531136',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAwG1nlt2BsgBd6hcgp9Jc3ymeb8/NAb7nXJc2DAXcLvZt
c7TMAHeYPyOFAkJaturIp2kd7PNGU6Ksy9M6HLgvpP+NxqLbcB0154Q8HuaBtTDk
RIC0DCqJeByg80mWkaW6mS0lnqdNnc3V1mDaPhjwq51c8J08kev7XY60/60LGjeT
9HlEIXg+z6Pgzk6evexH2JgE8oOZOAPuwGzh9WlyYArupvG21TWOqN4WW727OyeJ
J8L5/R+jCpKdrwoE3dMRZo9v1wjVCtN6z5enXJdOrPu0lNfNH5vsjp++ZpjBlh31
CQ2I3/rMf3cSr55518zpaU+3i7g71HhaYSnI9Ki7klXYprVgjIjOiOwv5u3nnS/X
C30Zc+y/iHfY20Mi2deYDMXbq6DyWNzaCoWXC//kePAMCj4evXQqv0aLwgH6HfrP
5CNfVtxouCueouSNIXFfYeizB6M5GSKAs/9OLMRNn9TpSaBKJWCjpLxhlTKE1GWB
gQjL5joaeooAS9Wuj/FWewck0MVX6ZpSqbJHHhYgQU8trPWvwpMaoUT9MoPV6EO7
Lp6yv9iTJRrKTUVErFsfEY0wwXDX7BfJrG1TXtrd4EhZjyXfAp0sBTjAGpBqrQU8
QqiA4qw6wpfV50g+9UjPJDWwp3bhMKNxNNmzigHGMOXfypH6CP8aN08NjqLg9oDS
QQElASupN/efwnsYqBDlqEnmQQd0ljed5MDsLQHdCzHVXcpO44wGaRzYd09XR22X
ZY/NX4Dyr84Wft9yV3mBxbuF
=Ossh
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4b77b8c1-884f-49bb-aac2-c386c2e3aba9',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Vo/evGHZ7YJZDcnqtMMQyrvKKtumUHPkXeQDodqg3Jeh
lkMPFNK1O1dKwhZ3/PtFeGhy8vkxCZxwydfaoO0WWv8aiQhRuM3ia3h7rgfJ158s
bnp2kuAG7iJK2enmRcFOLi9eWPqe4ChZLIEx2pGcEHdL3ScnjXCzkTgih+GQLEnM
dSHa2cFTYqCCd/V2LBmp9vpkd2LvyvykQHmY1ddKg6k3qDk5CZ/yUARq8yJbQahE
B2jgRzooSR7idQ24i97PeWh4Vc8MSUNSSlKKTSxN/qinBuA0kZaUWL5HmvXLxCTn
l6jJISaXLg77Ry291+ISowV4mgvEyx5TMfNriab7Yv4lLKWgJFZ8UjzytjhAVmpC
79J81LKL8A1ryDvnLXl27Y8XKU7j8HH+a2p8UACXVJEdSeA2GvMO4NN5TPE6jgJo
xYKtWrh7Famw3k5GaZr59Tu5/18aBjnn+0Om6LgE+4AhkLNwgwlPeJhXNysRfRee
34nLlIFzn6zMDcqOid7Ht/XIgdfdFpSDnjwdS7wINREVsUImpczTM6i5v9JhC0ig
hhFatUWkMNcx0d65+VIooj/9zfI92aq/A8lE6Lo/op3JX+BLZZHAcGXfr7j+xk9Y
VRJqHaW+l9MmnBcFEvqyyik7ykDxAprIPtlfgpDur9U/UrByvlMdvWz7U5JHOYXS
QQFPiru+EL9K+81HfC7QMKhyGouy0P4nD80A64gItwGNUN3Jx3ZFxWtaybLJBAwd
lnERgm4QL7XigmxhCd3+DgW0
=8hs0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4f4e96fd-e526-4f7b-acfc-cb64373fcd86',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/8DwxHm7mxRJrbU4iTHYvEam5p/Vg867H/7bP8Uj8I76aq
JqsFtgp1OKx07Q1LQkTAtRX6h/wDY5uyG9Tk63JxQnDWChFBXueVJQKVqOnpkZCP
P9kzCKgv2JVZQEorULWFUCbLDcxoSkaLsU5mbbRfX4FXrzREECXjas6xCpiNW/4C
mzkcivji9pNUMSoK/2XCaeQT7K7Pql8M1KwFChft1KKHkDw1rkAdelCk/dkq0LwV
01mDLLTkM+/WRlocS52WUP5lnN8RjxCCD0nwg9wmWJGUYxZy7S7Iirb6Zz3SsCyz
0tSaZoCWmQdyUWzX8suEL5uTJnsODvUhXtRWItsPO7s1936iIKOpd8L5xBTgofuf
SASJgoM5QdaLEf6E/bdk50UB8SrKd4rHModHrC298gi9xGa8Bvq0Kx5jPWzY46RP
dWrFamGn49t/Ar088DuQmtbgAp5CI93CgTVOjW4XPg9WqpdVHa55whjQ8wlp1pdG
B/v3BiEX2xFWzczRDxaJix0UL7+QcBEpFtid2cu5H96peE9aTaC79b6vSOQwnZhG
t27exmFQgJhz/jqOcULQinMmBOOoWbs+VXTzPUTs8pqOP3BYMRx0s/vr4+YUf4jb
WK+lB5JDd2Wc8bn1BBtZjRQTNBISh1NJgDNbJumrSOzKpNcgLrD6sy0brnF6SdvS
PgHXxy9kHJEnwCU78kuxORF/mDE+13L4Lggsdd3cdQaD1X1VGJXAw29YKxvKwlxl
Ce9EnnqPxxyaOXAqrnWX
=AfPS
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '517dafb8-59ae-418c-a266-a4793c684a78',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+Ij6V2Cck7e9ZsiacG/tPkSzEN6e/q51fZC2L7rLoaCwb
KvJPwD763GYIG+suJclk/rA/FryMkk+KDEkRk3Z00NSMTlAA3dBP/nshPx891PnU
D++HLgSfTGkfmrmULiFUwWzxys6VKNPrXG/fUOiwCsuuz1gqqbnGGAgRNCfwQx7n
LdSNauu2lu97o/N+9FcTZvXSZAwD3kr5KTmXUXN/qU+2htkuLnmz+xboRaXU2Ihj
BSCpuWLChEOEREwBy/IU3HR7mdlUFOez11tbXHKlIhZMAFuvTAAmDPA3enGgei4h
3H7cRMYdcQ+949o2CEGSXoxAcHU7Gy0D1OYssDwCxLv12Fr8wXRg9jQo9I9Y94I2
x7Qh0UUEPyEQWbVunP48So55rVxYA37rcRpkptekKgE2pJ0pqwpoOkwJdKpWDeqF
4MA7PWoEGGoKt/luxzCbXlUim0S7EC2sf5AhTf3TtIG755JzkkOpyH4icIFKInB8
Z7ejGEBcAfDhngV2zy91V1uByq99yJR2kP13YPvL9Bxj4NJqDNvvCCRCJpWjv230
qqxsdFzulHGULeLXrxO+4HDZbODZe65YvZlwTCqm3NJQQLwiJERjSNpFWeazvWzc
unUiuIrcaz7sfxXdP/Ahp+a5VN35NsheOg6MOBN0OoxPcoqvsueruNYENB4rZ2LS
QwFmkLPga/z/XwJ4rTQK2m/SOySJGQrEseFPRns2e2AmDLJZ6WGvJRTXmtOJZNAY
aGWUCU/5xQ1/9EnWME1aFRN2x/0=
=Mgto
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '565a29b6-9687-4635-a298-fe41ad91a914',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8DCJTiAO5WsN7BeZ+zt1EOjeErEtFcPGgu3G2oMM8FPiH
h7RjJceFDyEV8Cz2KTul763+ti4PvAEpG/wS77cwbIeNfAQbeP8cVMIKv2Be2Rm3
WrLLpXc2+IeQXlJuxvWkLDs19g90LSUbpY/LW3AqmnjCMIDWdY06zICkH6HUrMK1
q5ewCN0dYKbqmXFl5hFPby3hekPZ8iatzKyN5fOG9z+LNodFoOyg+il0qdmE7mrQ
ByV206JtRblmepmbvKlfrS9NWzET3ZH3YbX5Z4B4PjDFDHdGeBDLk077pJuYp/bG
KjkiNF4mLbtcBiJwcdd+FtgfxeR2GPmfneqlSBfSEvRY/U59G7rH7SWwtf1klISI
UYiqdkRLvdYEAiwEB52YP8nLkFB/hM5wpbzrABVD3T03mkPK/OL5cTaunZxm/WFJ
oDLyedduYngyLImewNTH19yx13BvnyLTbAnB5hJMjllpgHl/7E+2wVPEmAD3hQVB
Ph3kmHRKI5xN5k3ah4FmEtpe3B0lvKHONsAfqtQublC5pMdWgi8py3jBggftFkmL
MbjIJpqLrbyRAOky+TZ4Ho1EJjeXnJUflWcqD0ueQDA0X+HVwQySrj3CaRgXzJsU
cY15n0kfTrLAg/WNCiqgawvsadqKVDgJ/9ivUqTwDxrBVidHNtw2SWKQqc0XGLHS
QwGPoziO3Dq/qrx3vdoaQAv7kmo1SQe2yP6NBtii1nBObrtqmTpIsaGv50HxLE2N
lG0cGIvMLFWHMCsi1771YuUlSW0=
=TG7J
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5aab212a-efd3-45f4-ab15-6970be30b1eb',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkjLFT0Xmq/BTjMt8OCQvFZpYn3tTiCb6kAqbUHZPH/uV
AphU0mdkRFvAdp2kUtftt5OQ1j5tuejaTv8DlxBoRozXoMFl7O+hqDJbBpcgK/+l
eFTZiBH80P7oeDNPrfU7ijKpa6f2mGM5EyeagzAysHjyc+mQR9PIK4bahrwDmlHx
TRnBwT3SP9RVRJlQX79P3JdyzQ2OpwG93XjveGBPTQ56Z4xslCqgoE5PERB6u2lG
8m6lsKCS/DGAzk6I40DhtsX5WpJZvUI7Y29VOrWJZqrpNFDrBtDQdtfB/NVYOvJz
/bRzpc6MawmnNLMtDmPnqVBi8Qu9/KGRcSHtt6LSi5HiCnq48ztO6r3V6DXDmD3K
N2lZDbh6s85oGC11w2hFYdWjzANK6radQCBCsJz0+xf8N+fTIyFMdeSgHFRlzfrC
mjkWa5vdaLbKfmV0lFiChuqEBdFAI/U5GDmVfhIznaPtfvhc2TIWEx5UdTw1LGjD
sySpMzPhzPanla6PSpN7JpBnVvgE77lF5CprXzrzTQAjV8z9fkN1A6pyGjY6iwT5
1vaxzdKKhhBU9ZhH5nff0GYsOVSoZkjj3M2nuOM43cLyQM7i23L9JzgT0qe9dtNB
cyS/Gv4lDkKdefS0MTgf+pvj2V2lv+bXCZZwcehvb0/8VWUsX4jgzNdXJZQN2aTS
QQF+xTyKMFl/+W4VxZIjW/OXfn33ZSC7WnMlqZWlsyzbTvYui2e5+DFXvoqbhFtN
WYApKzeFVdqcUTPnLI4HKuZo
=bWhq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5d84766a-f64a-48f4-a9be-1616d8bef8f2',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//VIVM4NicE1DbV5Hx/hfSQnwPoDwo1O7F+Yf312FedjtQ
C40YHkGkmoFN0vp1oeMNwo6hbHcUQhxCHAPNgq5sIvt3RwuCkjeb8vFwXkL4aRBb
7VAwcVsyfyOsz+0jV4rEguc9qcXwLBR8AJE97WZAGlSKxuPY4/Hq/eIlM+6CPdb+
KLzIj635aCbDxF92tPxvQfeYSBWK8Bo5a+omJc0EcvPUfHPtMJXV1HAYv0rJtIOY
N4oZmW/8C7TEJVkMiz8PbkCgr/YTLkkUEN/uk5IxWFjeUy2NIySUpFIEbPqMU3Eh
VzbNmdi/bJtA0WdOi+7U4S4pZTuKmRRGSY+HKLcm5XKCspXkGoqOjOjgMyFxQZuE
mekCtDYlcKtvqB7l0GaRYyr5kzGDN/8KwyDmJDM4swLJuGDf8z55DoZcNfoXB9iI
j63E8R9cG0zuWWlULeEvxT6pV+oNqNyp+TOev2/0UVz5g4EgVbIDcBucOOsTK6wp
j6hNb+x6ShyGwVjshtHR2too0KJTnIrV1LDqRqTtUcg+Xv9Dq5gVRsth4Ukel0E8
k71yu17I7NUJ+a+EXxB8k19/NS8Uc7W+qhAtCCbOyOLtCzIEwFZZz3ymwFjNkXui
vjfBppfkAg34259qcdeQ9/+wQmIwBELBj5dJD5RTk0930k5Kb/t4tGcipSFSSnnS
QQGrcyznhiG9oWC4EDiKl4sNz5NrnygXOPAjSg6qq/6D48/x53KvtQj0oUDTkMCn
wTi24r9k/QxOc1gUyTOteqIL
=0G5w
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5eec10d3-a9ec-44a5-a291-2b2be19ef9ff',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+ONPSHPWV3q+oO7hcomRJr/VviAWLlkDg1hRk1/5Qojfd
G3RbOXENTZf0rzJaqoCvl8lcNO1VycDTNGXUYOhWnQ4lomZByyCz/2pB7wri4GWV
jvdpQ71fF1KQ0h7N6tScXJfPjT7rggS0opLbfHgVtWwy6arRvpmtdyJd1c0Dl97U
65csuq7BSPS9GBfYVxbZ8xBN/KdQ4iI4AwB756/zu3yxkXY1+EIiX8ct1ocYrQMR
zXdjc0yyKU9ECC5jSl+FN7mcPgXo+fIsWIAhTi9dHmY//+KK0gAjURUphHTxAdda
sdrtmUI2nMD1jY32hMcces3PTFmPghZF3KkzjLld+zAYThsWSPYh2NSGsGMmoloc
FPLxjhuainiWfvVdpNfuUInWOSqs0yXS/FHUHlWMDTIZFULKIuavTPMFf37UkKab
K1xBT88MyA6vry1hOi/vBPnYnZ7lwKMLwJTDXqVLNglLEY2/Ua6Rlt78sh4yDM70
tf+cAQ3TO9HD1VyIDP0QFIFuF7OrxmBkbrhF8JiiAocLDe7Ho7sgAUFWinDamAWL
yX0aJ9F0CSvinUCYFEkyU5Rcczcja/AYpx8foo6CSp3zCfvnRMdEZHLvRlbqYTzA
/tYOueCZsrOtJNc3yFIWfqw9so169VLEyqE2D3m+3OPBfZe9pNxwIpku0YaXFEXS
TQEJZ9+gWT3Jf1bJxSQpW8RlCyY2nwbHmTB9EIWIZ+sars5wlkSPQ7vYvRa/4aeq
wVUwwFVPPW8fj1zea8YmxXuGkzWL3i4EYMv4Hkub
=2Ca+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6499424f-948a-4f76-ae1a-9d4253c6be53',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//RTsdnN6vQwOL+m5rlUVjDoGx64+4IlHQkPSH6MBSwbqX
1kznkTYL556uk1weJJ707gOTrYDI332wW8sV3B3x3SyO99MfiM9umaqpQhXo4Awo
39yeO8wyWKV/FJqtpQDcfm5gTFg6RTOJ0aCItffwFd2Vbu/ffu46IAASdPAyOJjG
oSvapAKCM4FITCmZT7rM0tdymx+oPuRvAeX4Xt3UJndIPn4ISRvstYLW3wzWABRU
N9BuNkK0lP9457JtsGVIIIRTUp6ZZLHalKMIqmj267D95C+8zadYfXArDK6eeAmv
R0CYKnPKbloOGdAtBiDi+1Qv3D9C+E9ZULMsA253uHnAX08FCaQznYqbLUOJ6BC1
w0cjpkr7wlf0kwS1lHOcbP4lV52Sz6XP6+hAtL7zusDsfURfUb1mF3eIxcXoBc5R
rRVIJWCPP86P/YKwmjHfQnTcPX2VmMR9+TXgUixBmCbBqXXRGMcafFjPfeVkGIaG
tITuRRMkWe9c2u53oXp8VtV5ytlJAELmqKwka0Baz0FKWTZuhiCxjy4F9YoRHwTU
Kr6ZFTg4ysjuJTaKVgFSwjRtmWgBJFNYlUvaW59is9etScKsueytt2xKyhbIVv5V
NSR4Ooyoa/T1+m0UB6Ras/d6ljK8F9tww6EBLMWz50P3s7kp/yoW2Mfk7pEQyvXS
QQHKdXxyoNODhL3OSc/MSFFauYGTgGYWFGRu1K1k8zEtB7pPMwmQ0LByDonX7Bl0
qEXDwg2XZLXsoYXTcJOVyHVh
=V0lR
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '685e9c88-2ec3-4d60-ac04-1a40c4d154f2',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+K6jhi9nvn2wsmd0qVlHeG8tCZWAhlvMf1YxBG5X63uZY
UVEYwEnmDTi+pMmIit1QhekancsJqK1xR7VoWYONI1nWQHvb7QOs9DvMMoJaWnuC
kr4zKDj0VtywaHLOELIxVqo2dX3Uqn1TyHrTgwVGj98Vx7Gx4zTJg3Z3FoRNyhXb
v0/wM5T7kP7dSfaR68Ud0FuXfjj1jA3kxuDiOKyWITM5bcity3ZZSyjN2uRaP48I
/9CNSCfQ4HBlIIMunSarjjIZKBe0/xZ2TyfkmXBb+Tp9AH2cM8jGFPUmzsJaoMn2
ifJH7CrG2KoODa8tK3QnUl9Bf99D0EhuQzhbTbKlr1wmRpPo94q0Rk2fseyRWoi8
MizX+k3CSh5CRQV72fKQY7sbusdAtf0q+Zei3EsBazSfuEuYelI+Yr0dWyktmPdj
JIEHbwNdetfkNIUyMTCcSRYuy1g1B8yomqLLolg62odCyEMA38WsB2GCWXafr5Tl
aYPZMMntOP1jS8GfRiW/0diUGFKeK40O4KxR1oXqS6/emCq1mUYKL2ZZoci2WhKQ
xa9ZpCbd9x0K4DOxwMXN5PAKoVvcE+hnA8tPLmlxiblIwvJ1rzVGRbHhkRCSD55b
UEM5GpTeVWxLaTzO4yKM173WTWVXiG1hFShqyRiIjLM793DhpnRyh5B8Auv0FGHS
QgHIm5EKABGMVf30vkB1D7SwjVw73BL9CBtGnKYHovrM3pCXe2gUQ5Q0cCNVe/dE
JaXFqUYD151EHqEIEDLyXZwikg==
=HwI6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6f3cc414-9436-4562-a5bc-a29fb513df0c',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8CuUE/QIzGb45NHjtu0TWjtJrr3svfHAiT+E2FtFkvTEH
nG505LQQnKwA+3YKrvyEhnQFHUv6rnU8Fr0k9wte87EKTj4W4M2r/1b2JFx/3nQe
dN0SAI518qlYqFx9x5/YWDgT2lP5VhnAfqzX/c4liclYa4wdITtnCTOuVrwfAOe/
OrP19Q/N99stayiDTEFUMqusM54OoH9iGtbGetJqymlhAQxqwLNts3pTihyTrOoR
+NOCqU2QFmBDb36W97zppFlI665y+IxLh3HStoSijsA0ovMobfV+5HOMrvKSMYa7
qD+5nGfko6uhdDPCQt4nodcfS77hp1hIZ8APM1lBWC3WkFzk5i2qSnwrlFzCHaZA
0oLFiuP+UVCbClr6si9qfJfj40K7H1x/c58dRfBDon+qNuijinAh5NpEzaCwpW9o
T84ryAw7vaXetvLxAMa58BSb8kLtsZYQWb5IY9TvmgvuKVHv0L7IgDjMh4HeXT1w
HMrWuLwZNAJ02CJRCerzJIUjykSO3yE52BCDvNWfw/UIRmLurST7EC0dItI5VqxH
/s61zq9rnEhwEM/HLmQCRQHxEQGjgpLyQb93xY6D4FlNm4wR4LjxVJA/7Wtq0s/S
7CGW1sw6noZ+7cHF0TVhefGfh4/Ycxdl/XpXbgDhgfbliFatX2iI94ERYaTxnaPS
QQGaHSQY57sIzJ6oBr1i/hz5VU/WdfZpBxxLkHa11XVY6eF7GN6/SwfngW00L7qP
mgZ0la5XGn8xVKa7EYBjqdG6
=XT7K
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '783e8e7f-10e9-4770-a440-2d6c98bf2d90',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAjIrF0vmB/ym57oxq2LPv5WQbuf4lgtRAVS6f0FDJ9uCe
TmPEJNx9bU8TNdQZTM0d2hApjItmMKm8h8UU0GyUlQrPksUAyaK0Z3n0Yjuzj613
2ZZZ2tJJmBv1YqDra4s7ABHqayadUB7xjwhFhflFx7iK/sUI+Mu8R/J3dub/4p3P
pR4ATI7DgKE8741CPoaYGv5ZU+6YLFRaMlSIRb5R8jlv2TqMdOrddlUzvaewLf5b
JyVH/a3nDE+WwyD5npGF6WhpufIgiTaqXFxvtEtNyBZD4FJhb0gFa/o2WPXHCGJZ
U3M/+iporMIg5iagLRJ4kluf+w5FDtWrpf/x8DX3JRVr8ZhAsLJ6G0d4xUVOxHbb
Z73zPWHn6/MG655Kg47asEQcOk4Bplh7kpIyjQTelVX0tNWjXpN0KBlrCGBkK3nh
MJx+jHSWyo2GgN6I2iLDx33rbbUDWPHwqj5XHiGjtt/K9j03w7wM7NFbr0EGYa82
TVW8I045LmWQOL5MKj2XKfy81tCOMBwIccT937WBE8q9wLdoG5i300E1iGqXLDu/
2lPv0Dgb5Bb277FrgVzKz6cQKNNS9r9zPbwjXOarMMEiFACeRZTF0rThg4HsG6KS
okYFK6EgQvIduyNkcoFBzLzhQMibQtnqXObzhQEkYinLE5i3lSakpcLl/6qWfiDS
QwGlJqM1w+mWLwQWq+/hKQJ1qGL2QcWWpfbD/yRGjMwaGQ59FVUNEY3bCepetON8
6DpQrXy5aFS/HKE+Wt3HatLMNVI=
=ptuM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7a08dada-2de6-43e4-a3f3-c3d1fba97867',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NMRp3qzWy2vo1MphqPbPGT/6M6JeNUSEhTxfv8z9o3Pk
+GM7boxQdKxSTgQqCfCgNSxIemrg1YCrBMf3KXZUdSMLJoGA4vZbnLcK2azwHyye
nUgs8Wlfs8R1lO1AeW+R4/LaMXqpCZ7OfWnYKWAD6qL04LEGkecgY9NXHSsQsg39
Q+XWouqLSXwmnN257sW9PPh7Z4upAtNhF8DBmbt2ZxctqgxOqVm3FXKLOIsueaYN
G17dMyAWxE1xJwmrhHJMJzluxsRS5ut6WYKv3VmvymXS1gwJ0Smfw2UDUbqyWZE/
9zMGPu33iPDYsTR8iTnM9SgARNxKVMYkWE5hZc8mXJd5MEBqVym/34rQj7H4KFRW
xxpy+HXRHNHYKmydPeR1v5Rvj3qP4/eLll8c4Giji1p8uE1wDQFTX+BJjy/8hS50
nVwr/JWCl81miDYfDJdyHYTCOWNTUKfLU3ZRVAMVn9mfKV4sPSF/4pif+YA5R+q8
FNP1WXvUiD2cGufzYLiQlms7nmfz63CMaZE6LoPvi4UqeWz3pFcy+/Y0+Vhtd18U
0/LKXQvGiikVMETg7Q45wODa97ESTv9RPlH1jewSohKKvP0n9OnKJhPCaBpUt5+k
3tkzNqZZlGyyYVX/PXCbJ3GWHSWjHFSYZVkx5q08KVI4sCVabRfLK9F/nGMpCfjS
QQFy7C1MjQ5oka8v1ZKyS8AixvM/23qqQ/xc8JYWtLXI33e+qdac6Ci8iHg0OuVC
05KcA/AkZV88ilWUXkDoYTQO
=IOdS
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7f71cca5-df55-427b-a8a8-004422f9c863',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAvXRmmH71QM0T/66bnU26HP2U8vs0l5aS4+Bew7UCwtG4
BJ8n2sa43PufVgbW2HLqqhuGDM/Frs5k/aVssKkwsREowosz5Gv/9KNaxVuwya8g
9o7LUcj4tWZ+goMFKB4yCkCNy2eSWUhySa0fomqhaCTadpYhkqOi9VXVV44aHrgM
NP5WT+sXXO/3odbwn3BLXOYXabHjINjB52eXKZp8m2O12HH447sUnqq8LgyuO9vn
FnNtWt0TjrzS5g/ubEeHECPzIqx418Ea0HtKQph5lsNgwYQQxZjSOjfp8DfCvZ7y
9QTXINV+4C3R75nWVgqSgCMwBcsEo3fgbOIFmoj5VzolgAAKfyvFzPoQ8aEppcpk
mR3+vk4fMZS9hU2X8TEFA0okPgaLMjDtIwZc4OBi9Q79Qsyn6iABLEb0uSiTaFqZ
seswBXkpCwKFKajpmygqXxSDXxGtRASTyWkKpQrPrZoQwDLmbB9iSNea+YfjUBw7
8a0tGoVQ6CTgIj2i7DjDiFHWJhmVHBUZh0tEjsjaonYSwv6RF0e70gKxD0FDHdGQ
Cmo1kNRWwBnQfmNO6GEl+j5pMRekoxBSfp6VZ58G6muW6X2Ykfs3xXix4L0wjqgo
LldtVIUZX3YPhUgptSvC6nZeevu+Lw7IB7ip2XoSq8DZ0cg8uTnzgetjtl6h4KjS
RAFUUE15WcJ562f20tE+rfSZVsLjPnHmDY67cqNm/ZetFawkTJOjsrxCPe1Y08ix
n3NCgqXkh4E15IPUXlXs6E4IUZdA
=gzsl
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '829f827c-356a-4b2b-a186-a044776c2ba1',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAi4tWuTuKNpdtsQgUHU/GHfWMhpOhZfFsCl7t9Ocqvurk
Nt4jhNdlIVHGk+g+Tfi25e/VnzqcVRKZW2z2gMMYwxK4RS4zLkyHja58PXkP/Pfe
znckM59mUBWna3VtLE5Rpyh/K/bS/ZJkudirwBYNArd4/YPZ7OnvJItUcU0vUuDS
ueMI9WQx/wOb8TPILKj58KTUVTBwEgbQcXCQM3xeGkDyMHPdmjoxd0uUEHX0D5RW
brNJDosaNd0hIBf6YdtSfs7c97Ct/ESzTU06rOOgIbK3/suLqaDjm2Q3Qf9Gc183
BZajZaXnnE52JYApsh2eRT4DbyBqYUJQKUzB7a1019eQZMrlv+WOsQAzW4vJ8o1W
T6XyLCFBP6LnkbvXB3dEgWLT5GeTpskbE/OZpSmoDRjT0H7D18dvDx/OXTa3EI9Q
DLha5aMu9OJqSrN4Cu2ZM008OTbUMu1trOR9Z96C0yx36qhkcrcoCSZGeyFSvM9e
4OGo5XFDp7V/BFOeJ2WT3OrTJLYX4+BXZoDV/mZlmU0VSl2KTpCF5DRWW4fYulBN
op2t+sCtV2mmk8MfmrB/le37DCg/yswjAkF0LD9JAiDgtk/QkWixhl0p7k71cH13
gQKOXY3vlAfa6+wGKRAw6rYrPx2RKgyO/lJ5u6GtgdYPuwAQF36n1TzrB9jAFoHS
QwGpWYliqE3Bx35ojg2YiEWp8obGUkZOwh0CwlGg6gE5rkIGLzwdoN139UqcZyCU
XVKcLlISeLHf2c65HuUtkQPfdf8=
=U6Z0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '82bdb33d-9f5b-448b-ac8e-f54aadf94fed',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//acnQnEarLiqUPwiThBtyvsLg0FTHuXujZ09gNZ0E6NdS
L2VCHYKhXi+gI2I2hbsLZcDQCBUebiWzFiy11dtT09+1kDwEGpUM8Qqwiz7kaZCY
tzBwK+ihBCm2iC2frpGxZPD3ryD7GVdsfcD87f3Xu2QU0cLwuIVM7lZ05VVhYHjM
yTP0LaJbvJ+b5mduHvkBJGo/GFX/RygMWhn/Hob4QHxqCvJmVF8BPuDPZaVLgHo0
/vymi0B9B49JYQwwkMIXNmy+9Fs5Bdyh6hu1pvfeA4obhkdZgdSqf6jbD4F9ofc2
dk5gQUJT5+kr85db692bx6XBMrMshgWO0eLVoR1Xk4hEa4Z1gD/meLxqvuaoqfhj
EbK2UQtArG4aFMcBjXnr324IcbqMSXrR/Rk4stN92CdwSJ9f28tEYu1GBOYGHrXq
0HdSOGCBDPtUFgPlkM19Dfz4MgY6a22XK1GhaCKPjXUBhdTU1tqxTlfDy05RsUEC
ZELdVw4r4cbAC4wgrHFd62pzQFR3p+cnQPNKE8d+7oMJm2Cy8r28Y7r255w0ZKuY
ZQiiiheOxgoamCr1g3pXjpZX963Drx2gMPl5dy5hR1GPsfe4LygjKDnanDckgadO
Q8GWws9juW2rpcPZdbuF9nZeN1rNuE8+b70brGY1jdYQvuYoX0vlx5WkoPKwo7jS
QQELRLfe0+DQMG+VlcmLwdovUc6R6kt4HKJ+PtuT8JTOXb8fiOEh32LbKVsol63B
NxiX0fTfAQhuGHegJ80Bmq7O
=ILvR
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '83f8fc11-4323-4b89-a496-bde320abe9a0',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9Hi6BUXXzPRJV5B46bNXcVrYpHXbnTrqZyMpuy1QM/3j6
tHnIYMSwvLuTp0HdBfLJAvAgRD/etWlIU+aAp/TkWBdVJUKsueAnwL47Jhziunzk
89FKUFqFKnJlE2j6YhCSoIVttxOwoQsafYwCiVl73KjNejLXbFa0GsphnK28FQjJ
H/32W0QyWTPzKSl1y9F7nRW6oczDMZSPyxBePUBXsMxZWkHQ4IbRtduhqt4L6xQW
xLItzI9qhYT11XsXGp4TU0bsSZjfPlP4lQ4yscUkYse58Mvi6hvEetWMnEAYIzes
ToQPtfZifR/nmSGZ/1wmJfch74BtFTPHPKwTQ/Kz9FcbnwyDACawypT/zfFwRG+6
UdZzNFeGJ0d7rMpfePHcJ2sBH9JQhu9GTNZz3tM892A1wWeXf9zQQ5acCWxUluwj
hurFiZe4QKb/18eh62OguoYaPA9LmmMg9Xyop3rxcdXGadpYXJYdOJX4UJIYNDRY
hvnBYZSKMUt+vOvx7uoSEqJZ0XIA7qihlOva8T2Quor4zJYNa9l3M5kFYSDNHIxO
k/e5RF6OvLXWooGpC1LqdfxfGuDaVpKr6yykqbs0noXroFleKH36oMG6iPMzZJWx
Newd3fqUjjxQUcbGZE91O6wDSl8Yvbvq8OzZOUu6me2KVtG5jLIeMSV9a1k9NQ/S
PgFy7SIufu0AGmAdy8QwTLCtKuReBpMzAW20bmMrx7Gy8blqJViw6pvSfk8/Wf7e
3us9gmRZvIh9IYvpdmeE
=dXRj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8448653d-ebe7-4cfa-a3f2-fd8f0c2a4cc3',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAsUqFctm8F1mIgkGwrl01beGzFzGo56J7/USovvTJqDBP
6ijy7rDfoqKPcdWEDeoTh8n2/8NxbuiXTQwWomWTjmBhoVSqOSe/Q1eqk36Ainvv
MrtQl2qJS11UMO7KmFahFcWU/dRQtKL0Eu/CHIiw+Gsr2qAf7QUj1Xn39XPK/jsj
ez5BZt2F2Y3nBYfPfTawDzfOpHUMJXWu1hZRjQox59eLdck/LqPpQaXYP1E4qhZY
X9moghmPrqR2gQfezkZcHaYa1Lg0J23Tq+R9HDckCEs9/ilgJiormIBi3hvd6Zo6
b9v94L5hfF1Wlnb9VYh6WkElgET6BxHWi9hfFfPuUM1oMR3EF5+sL4d32gjiE9Xs
phWr2QlzWCtgjcCbVgYRsADMdLNiaqVzDZg64aeptRShrDIwbb0IZlTpLCuWEAj8
+7vVVkHWUoQeTyqbXAu+jLu68rGJosEBQwNfDYgUEd8LVAkwwqnKU50zfM6jMvG2
MQP/gmNtUaYcQLk4QSReKbwD3ItF40I7XlqKS2+X7YKa8BXrRWFCs/byWvS6R9TU
MUUbPQK9b4bGTOkAKp6OcruHm2bra/gtZF4X9hfzRDs9iYfUlG4mX1uIBwKMmIoT
z4Lnsi54b6cdznNwawXW40PwSLB/l0m3TZclaPd+8I0Hq5stFrwL/lz5HRCDsonS
SQELXC7pHshdvUQOev+bKWY2Iiuq9pjSy+aLwEj2Z/KH7NL8hpyjbvQGYSUoTP5c
KSOMvvx7tQMxZwmkBnLEsx+Jp2sLh3M8u9U=
=3iQ/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '86479889-94c9-4bce-ab88-168331e0dead',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+MiYlFd38Ay1VN9+ISYEYuXHZUdC7pT/wM9pGerT8DQZ6
kRvLviwrA2swp6jL3ZUVdLkazbBuYKVB0Ovb5ezZFXUQxOgTHR7DgPRUfBFMvFSt
NohZRnJTAc2ApFE01HajxoMe7xTs0ogvaz7KCrS+x+WcmShXYZgHqtWvjVVuospp
xt9d/wXNnM2TE1F3ZqnL5PQ2MtFR2/OPksOS3U+9lyZsbGVjHX/+/7X2pStY9iN7
oFTcFiwfzbqB4EYojA1v9TI4HBEZJPWO7bZz8CXGayfR6/2i1QJqkEItnmt8aVLg
xiX4p/Q/Pcf4ca8/o6VVfDXpYl6ZR4nI15NG++yUF2X+kqmMUJYcDDtjfMwnnGIx
Kd/AjU9yV4DsbrX6DZPoC0bXf8GopXnt86pDtGc8LxL/TFhvxOD9oJZ6QBWbsKwB
MMesw6s1oPPC5EBh0amJ0faHZQNVYFf1f77J9itmtc77tpeNBczEMHhLlIVSr8iH
vxdTiJOxgA/f85wQrMsIP6U4WDLj5smjli8W0olZBYIajTph5BiYNovVR0OJzngS
8sV7jnQ4jalkJHVx4bujKu6RWeWeyiu8rhgqBlI1ZDNp0cJcgoirJyfzu+5A1o7x
6RWPwtTDfsTGWXFWYbogo6nXJvLcT7mRBHduoDIDNTQfNNcUxWPODN4JZRbhLZ7S
RQHSv0rrr6fVZpF/aOw9AgN7LMWXvLL+AcOu03vizh+R865Z0QItMzEsR9H6XjRG
NMrKl5vv5G+U2f+/1A2csq/8sMUooQ==
=F0Tw
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b8a73ee-ad56-4ccc-a106-1fe519138459',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAxEi8xYn18GbWUCOMfvS4rTEN9ka8z3BhRMpBaXWQ5ZH6
ZUI/EZRt8CxhTZqrRZVXcwZiqGXeQwF3E/XNnfKWgBVYOAlnAmK3ug7Ue3zlVRIe
udOiJS2uXZXrNmIDelk13AHPk5K7Q68sc9M7zxMlxUpYnwD2ANvLmeR1SY/3INzc
fwb7mHDQXGtPu2WGo//lo2Mzh4sAUafRNqUCo8W2nyf/TZQE4to+ScjSYnMm/INV
eqNnnnkrVSPNXT0livkg9ezzFLRGdQfZHdyzWdr84UN1Juj3oLIa/s+p6YeCg009
oGp6OHcwJI4OqsnAU6tf1xCNWNKj5pFhXyktBm/BP/dUg5SUrrbKNv8Bksq53e9U
qs/cBbCA+h8f28ztlOX2NU5BBw7QBUJ11UNZV3Kh57zL+S5vUdGxGbjESfy4VqG+
7zI9u0VZ8ZbK6g7UPgN6nkSvWJTjxHqNqLaFuaATuXegnPXzTaIUoe9VoZhka1Yc
ddRa2YFYokWbdmmWTQNlqSSHcz3Of95Pk9Ql41CO/Z0898101W5ESPEx0Fx9Q2Bo
VxuqBdF3uH4btWXVv8uOGjAVBpXusRhllxH8cNQy8Pm6LPC32XSvpUC3rV2Am9yV
3WsGt3pV3cRPCAgwSkCnAaw+MeGAIOExjKdzKbez179jFfw8KE9B/Yow7c/5O2LS
SQE5XNAiiDtqH+QoBY39j63C2lxdavUIWMGCi7sUd8P226d7kmeAWXdG9aSWb3Rt
CABpADFGVQFi1OHFLJPJGNfpNvaiDkIHaQI=
=A6GF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b8fa727-d5a8-40e7-a122-b1dafc1f88fc',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9F9So3w9Vl4C//1yEy1CdLn5Z+tBUKYJbQkpGxpfheZgn
b0d9IkQyDul3Rg4pdHtYe2Y9V11Kx6jczuNAUKw6x2s/YlCsI22Lo8R7xAgiBQTF
HOwvcRFTh1zMe/fZTbU2GFJguAjoQno6dI6ZBeztQRFPa6GyqTCmqDM6nz85mPfj
u1VdVzLqAGWjHyfEtiWNDbhfKsB2F5SP/IfNwZzUQDdXwJspVTYYCZWDBGznrP0u
lkohh9W9kgxmTazZEjNYwdsqTSxX3ZwuyvxOk+jMgBX0Z0/HCKeLclh59LVQYzE3
qOjn8b08gLe5NUxvthk7PATAnx4IUxyabcGFAZ1ZB2/4dvIWatslglKiyY+H5ltQ
t5u1PlMzqEtdsmMBu8yxcvHHCD1OC/1H2ljLMH1oSqzahK7iAWOiSRaAVwojY743
wYbCHaXWChfs6XeWi7rEkXxsgxUDxDdxdc0ZKR9wKxTNPuT8TuVVdrPMcNOCGoWR
+oqDB+F4+6is1VdWJu6qV4PEX1wH+yLQwjafv6Zt1nWj7lCo6I2ZtMcMJZXEA1XH
gU0kKgTmIffolJA3bShMSrdj5jXFoqWweNd2u9f/7Mg2pdCRrjX29TfEgscN9Tj5
I8ksSWYEm9Ajcc7nfwdJyCGhn1Y6quTKdcEi0f88VDynmkbtqOiY9EBtZz+0yZzS
QwFudawfm6n2LAXHRCgGhx9+/DzWIhLdhn2X9EA0fBVx3pDROjkmcdeTqbLI6Xkm
pHAbRy/eQ+j3lBISPPdM2Z90YrE=
=OPLD
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8f84eb65-d420-4431-a4a9-aebda4a20edd',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/6AuKiXjqc6s9J4uGZjNwvgdLFHLb3N1+nvUlmvcfwa2wB
Q56p6NyLlIjwq6uttMsKBygvO7zTHkItGRyrUuX4sgkCTKJP+ODiUUPYUMUtFLm4
N+IHWba/EWThfO5LGg1z4embxmJnlJtQbz/4Z/BdpNXRtBTzkvG2+r6VG5ZVyrKS
p5YHa3zYK6NeSrPK1DsgeRYm1r9XSwOSf9rfO1KD0nTc7aGyR6atd/dhToPRMTBI
0N75kzphCGxeYhKY094X7YE0iuoNdN7WoYPHErJMw34tzp83MHQenGCGzZCwwYOp
rnbycOAYQLeh6xhogcNMVSn3/UX70a79tTjGVGUVhDnX9Dr5AIWZglkSV364baLR
ie+WmMMF28mxfG2YDpA9Pwn0P++9trl04iaIIQZuU4iXw4z+ayHPXd4s/HumyCH0
md8NycQURoqyvZ7EEZo1um6Un8ygFSCaxOl8GJlL4/Y8wHEVaabKZkay0MJkQqBT
nvxD3u6VvgyE7J8kdajy2Z2DfeAztjBfnZEOjhHD3M8S/m9dFHv+UoSsuE7csoau
XYv4IloNJ7iyrRa4Vw9V6iyOwx+1WZDvRt9e+HiXjc11uvccgFeuYpflZcHNMnlk
fX1KPB5n2c4AxzTCxtBkdchbo5/k+V+6bAgsz13A9oJT6rKsN03odJjHuB5i2BnS
QwEF4FrXd5yogRwdkZEiNDn5zREcd9WzHeVTvJKzNniCILf7MSNlwSZh53kQCqZv
KzlUU0TgMp5dSrtlaFb5fIxLJQ0=
=P/X0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '91c96309-c758-4d76-a952-898a2a560619',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAkXrfv0EyC7KciiVHiS+JMFVDgZoKzzzRFoSi/cyW0K2s
LsPsybkpotvQQW/qzXsrfpZauOJnyt/Fx4FR+kWzFjvIB/z5frM3EYYpMK7fRkgq
KJRFEroJCb0dkcSnpClqJq7Ae/8gYZRGI9WruDGRfqQ6QWTAGzF4sG566oIqPMHm
YxZ9HfjscDegRCRAz7kG+i7Lu8tfWhnYW4jsgpVNQwxp5OqOzrNgJA+N01kBwpE7
Vm8OkORFMDjtfQPGvkNye6vX70BmTdZbq5k2cHmTMioIl+T68nGVt6xpoDWKvlSb
qzGjo5/MlZPjGLVmWqFuR5PBYnaI4q5XxjGmlucjCZ6ejqXt2wFZliASnnEAwc/r
IiiJDWvOvyhstbQwC/L56o/M5alLzJA4wfbLAI30ZRCSqiweKoms4/8kJ5ZJjpdt
rRYnssAHoMILciHFqk62rP2dw3Ho0Ssxbfmgd5Mi60k6KoXVEqOyuNXL+P24usoc
Cx2b9k8P1SQvec965D1Y90FwmYRumot62o9yiU/CRsJ3pP6tJfqBlhH8i5AgQmgU
WHg/okVWOI8+Y7dCsvK/P8Q1EduGzxbYUha4PGGGaSGp6Pgp9gtdZqzjO5NEH67F
+WvMX1ysdX5+ufTdVatuRBd/Cyaltuzdw2UNaulLA6hnSsVlSBq3TDU6n6oQ7K7S
QwGNhsP6NnAstHDrn6jnXiIIDeDiRfwJWcbYNgENMcDkuq1/efoNlFfeIcmLwAzw
iw8yHskjNuqUrTwFBxpeWqMC21o=
=onHH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '92cc91ae-1d6d-4c10-a0fb-4dc45a8c2171',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+IEdOTcoiUoS8GArpxCCANmtVX/Ho9pEwwNlCYpyF7feR
7/vrDp/s2POvb6/by6JyGd+9W2YxJCE0e/Yp/bCvwGV6O8jkPJ2TTFHrIWJ+co3F
LWUtTBkUdho3zAt6khJhOt66yhhtm9xiFRn3/JwjWag/Uj4W1tKe20Lx9Sa6+LJH
w5cJUs9CSgOAQO/a1Iex/TFuQimkJwegEWy8UMUTvQl4mi8Av4InS5yo0pIoCwW4
Qzov0Z+DqiXzKn2ERVWYdFpWqXAuJM0whZtOJ9yC7UiS7VL1QrW8DHsYukklKaQP
2cZd+RYY/vY59jnZc79//5qExdygsEuag3WUF+qOrsRSo6aqPhGSVxY0Oyo/0meo
MRkPz3WP5QwREUCpcTTYpCZKYfdlEAneE5z2zf09pYIKrgjHKbjkz+cAMNtOAo5k
oIAs+YDcDxVDnDHn+iR+WqaLnkDW+caBNGlJb7Hm2es2djSbdNcjmZavoat2ahBr
wJObCguzMNqlZMC8oagfDAtW+5VNn7QIi+mj2pZWYbc6CZ/ov3iUndVZexUzNif1
eQM8r6601P4aWAXnazt+GR2e397Q2QIeBz/x4XcEAr7djBN6X972iundJfQcS/Cb
eQHwlIGlXM0JD306mk0grI+CjTRQNf+7VLgSRsKPYIBuDl1MYDhg/TFAxw8T1BHS
PgEw2nLhKDNSOVwjBHxVrWdxDL5ucbL070YiadxvUFLWKSP4aqXwv1mb+FpRqMNl
Zr4yNC9TmUzlmvCvb74I
=2vQ6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9745277c-d01b-4212-abba-436564699518',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//WY2cR0gBLIZRjRdkRvKTpgU7ttkbJ863EyB/Ak3y3dox
rxRJtmvJCGbmit1DEjC/7MmweacW5aI+OUUX4FrAUp3A7yoU/OizRD6Z2Tvva01G
kaxUJD8AdzOG+gJL+b48GRpAZVBROYf2ETbsbtH/nME07re2hcSTah3QYoUosFLH
vO0N09MMktMasRuQSDbDOf1TsKz5VnvHS4KK6KbY5CL28lZhprxsY0q3KI4kU44f
tMundO5IsQ1v4hFo7mTASdRwTpzkS6/fz29oU5r8TlXTIoXiKClurc2/xH+8vUsw
VbJlc2xJ+MGCe/Lly1xBJkGIJNRpAS5LkYBK34/4+Ip4ZhJgd8MlLZ0F35rZofUH
AB2cjjIKx5P4v3dyPAMuDKi/Y/5LdcRVUzfmBcvw+FiHZ/jSoPfw1lkvcAhCWpSc
iZtttgBn4fmgBYDRgY2C6dg6V4npy/4U3UHK22Cv2XPaSvlXX3+W9h9UT2FhRbYS
eBu7acy9srNtjKFoMX+Yqx9bcqO+RDbnMrAlKHAMETWXTA19yf9A5J+29LGyJQDS
2wQyc80hdTZqio94gMvVOjpvzWugWIUz6cTZ1sr35jBu6eAnYLl8cQnLel29P7OW
RLkL6ZxbRYZkV82VwXy8LZmxUBqASJt1S9/PTibBEUCZFqlZzzVE9BjNKdOb2jLS
QQFtmdFxtRl9Pq9a79SbSjRsAgN8RUYm9bdWuyiTJEzcz6cCgayPvQX/6UTxvB6H
SDhWX/DX2DvOd13n/sOa30D3
=HK2T
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '990af157-6310-4a7b-a621-995d0a71a5ab',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//UjpOTTyANlinXJjOphnK9dtnw5T7hJOvewYCRZyqt37D
YT/HkKQDf15agWMhb1oASMeG1Z8DfujoKHrvwRaiwg8rYZ0D84AV2vHVloXyhoyt
FWqX6j2MRyFrTO+DUu4Ef0d+s6ovk09rQzjPm9xei/UPoFpLNZ0P/ZsCV1J+/qcs
g93A/P34y8oKI6ankEg7+ZQW81sv/wkAvsE9/wOZskB9A6DervFQS13fhCQY2z0D
Kh1XwiC/XRGzcokbt6kefJDNFuv4R2nU0mhrUjyj028Vw7cBkmxBvxurVfuYWh33
owYntFtOuwBM//lMDt2h/NKMV02+urr8/WpW3JG5NrlusvGKk4kYGBxVJ7ZgnnO5
Dq9aF1ntNbIxrwmIxzSxKp4TTeLnglY42Rh4NWe80zZ3hgS2Xuz0tvejjjJqjRCl
BdBBOvqTY8P8eAZuHxkB/rUSW6jeiG/MbVumyFoAw93ecWAR2ZRXsnICYF+8BqYX
kSmxKJro2gH+rLw/97LxP2F64vAZbDHUiG6yuJ282liUYUkiKiqMui0oH5do4FA1
A2SEBf1Dq6f6iCja/N6PD6Oi0FYN7DNEqmwrTQKOzWp4mcK0H/PIdg32R66eoEHv
9x0VPimyIaO+hGbHOCaRgUNdtRfdZJdnPPfUXJi6aq3N+2p/NCfv7NZ8lJH+2xHS
QwHBWAQiAaRP0Gqw2gC0sMNLH7IzUZd8AT4wDNUzhhelnpmPZsIA8D/S/gcin/Lm
bQCQfrhgCkMmsPGHYpjQExxkXVE=
=NQAU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9a1ac256-08a0-4e54-a7e7-e4856d2bc383',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+IisdQsxAjY9t4DfqVeZmDQqIYxqKZKDsKgSYP436mseV
3e7FDjR6Pj9qnWyQyC4dwH4wjAmApgO8aQyLOBEHJtmDpbOyVbcqEIBmajCiuPtY
U8aEGq3nHg3p/PTLwnsXjME+PYg8wYF5fJUm91MzYmlEWUcNaKWI1yCnrMgUPG3S
ZIHcnXHGGZNO1B20zKMQH16FF6mJfAlIvGBkYbqidTZfdKJLj0S0XBXVsRsTnlv4
ZrFDbnuaSWNWQQXjk8uVfr4E3nEqa6ql9lO86KilssKqWo+A+Bd/CJhamujGt9tC
y9VcKkqaOMe81AcI29CILhnbdRUB0NT+OB7oq0BamJYsFfyv+C5FjikMAzyZqeEq
LU246OUBl85H6KyXIs2bNEpyW9RyuadDgEP1b6NanAqCBAciyePduB6gnlfK9DkG
TtsF88bdkOIozdo6GhaF4wwDBIw4g7XdPNydzxwxxJoYUd5ytouEi4GFLLzHlc/f
g7m4XL0MhFYzKWfu0Svz7Tpb6oC4MaCyMldARh7nCEiVO6nNkRbU8NqqG4veAEGD
YPU0FW3rj1dUHZYbrvlMubBelvXIrjsFOVN/YcD08h2BhPQ5vxIUA8H+pcu0g50K
66sDnKSyhfC/JPCg7az3XvGIiEIuyJBbLDyVPzF4IkE53zsjAbPlZ3jqkUMJRY7S
QgEBaFnrPv9BhwOgzBKAhAsL6svpA9K/MHyiD3F52E5T5QQFnWzQ/q2jFUPkEkYl
nCBaglsomIcP9wy+5PIodOrl6Q==
=eO2P
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a2d62165-eee9-4768-ac39-7dad2c622f9e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//c28jtzitm+UjqTFnbl0/WybMnVPzMK1/kL6B4c0pSYzK
au8Tk2E1CqYpC3A6oucE2QMhegbFj53ANswMnC4Mjx8AXq9vuwoq9iJayg8RT34Z
RAWYPJO3+luArnUTs3CkLXEquo+c3jP3noLcwAqotHHroqNoNyqj0UKdgkVgZWGJ
xAPViBxcYoWXG7MybkipQWdLO2WJanexS3gabjJ5djybuvzCP3atiCbpaYPjArqs
J0Pnk3wDd9pdPHolNtdWQaVDy1XrQm4Le1W04FzoH6xCzQ4fhhofurLaDQmgDpSU
SsFVqzXDN7jrsw0Tb8uYuxAVz/UG/vw4IDOSDIhablmw+7pGVpee/aepJrJgLxO3
5gFzDaSRGAgUO5n5i1bWI0bx9pbYCh/biZSGsFG5yTIAG+ZbAuOcGDI+t/3XMOMP
o0Qmi3muhkMgZUZbs+KzX8ObT0IDVTiYbjLpMpamkD63C8371u310kPoCdMc5dnV
4o0YiZ9tOjY67uM4rnUTRmNB8UTb8erD9NAadFLfJ9JjE4hOyP+sTo+mtpsvWQji
LW9yMFglEgu1ga0SAEyuiY/Cr2xtiCagcrY0LxEUS9Xez5bqj06VwvfdnUV6dfp7
hhuRXZId688lhn7gIUZGWRHQx9xF7FfPMet5YxDOD4tgmBi/11oUf7WtaotoFlPS
QwEPIjCfasZmdME0mMnQ1Pk3KFeucDEFp4iJxhbb5FlxZ+ezWU2/lIg0m4MKmyh8
SzusH++TSaacgO3nOXpcBZzL1Cw=
=PjoP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'abe75b51-6579-4e96-a12e-3446f339abd8',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//fI1BItysjAstfZXLIYAKUxkqyvCUZx6LoLFSlRuJDf72
ifF4oKyVTFdlhvQ9Yl8rkynQnEYD+B04NU130IVVIFThk5ywyxWa/bB3q5BbYV+f
n/lxqDvO+RR37AepQk/KjzaGnR6zT0bi6IUbQhk0S2oR0UJ9q1PxvvuUmPkyA0Sj
cwwkm7w3sXL4cs3dJ3g+qBsvJ/uzvO0BI4escUDX7EkFrm6DByhrf2nQCyFuZzlM
bHlxXZAErus5ywkIQXEKQK8FonDVgouD4vnlqMPCK02EJeILvgBFOehej/VMwiHX
1lGD5kjQzEo8TNj8gR8D++v8y96aG6KlXrSU273OqQk75F19XsikWzDnpKhFsFNR
dk+6WLSJ+BBefZCR1CnIUxIO0hP/DIa+AMAFvqRQpPVwHCOPj0tn5Nk3A1yF9DwB
/WIxnbYE46ZuziGRg/E2AtCqEl1EFlC0bTiOjPXre/sZHc4xPb7K8FNPRcDe9CXk
yHKjcMsujekjxc8FTFgDhFwF6DaRPwiC/q+44Lk6c93jHj1mXW3IVwAEqZ4QryFK
sTaiOS1LNesIemYIYKqpiSTlTz8wgPqek0fh/ewYvm16uuPiDUzJXvFJ7IsLdRBP
+RjReGsoPDrPaYXxxPaLFj2h1g50oMStPXPYI1wIlTb7t5iD3a+JjmxZ1w0GQSjS
RAEemzDZkMGLKQSbjI4O/6y7NMJoZEX12A/gJ1atueT/MwCyVZz7a3nob9QQlUi3
HGDh7/SlK3m5dIcjqm66TTJ0Cz4O
=8Y24
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af815c14-aff5-4362-ae15-3180ce895d09',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvpE1tNPEz24MkxTJqNaenrI8K94ISFdIpxb0p3SRU8Oz
JmxlHT2kGPStlNLuGgUEl4f20wvN59Mmpxz68I48sE5rJ9DsQbqeU1IExmjU2uIS
TVmSeBbdFfncautZeRkMm3bYA1VRfcN2DGWEz4dd+FVt/N3BECF62olmL7cA4b6K
xXGauUGLVcF/6vaRhIANcDghITEIjVZTsfoS+zexmLoKjVyLU4nixoJKxT7MuR7U
F5mRTBF6WVcpXGt8uX0qlEkcsK4/8V63rrSi5TpoA+HPmc7GXSJ8BMuFis63+V5V
adYZWKfhXlUL1NkR7/j/Qs1IlZ+/Oc5LcTXQopVws4C5JalG2JF8myOH7xphOCwP
+YbJOZwDF1qgvt5fzE7Qgd4bXj/iiOSD2xGiNlyFKXdN/V1fnKCqx6bPZY9uc8fR
hmbTWWXBeV+iQXpqINnO7QBTYg/gbtzqEv00jeglPhWvbrl8+us8hWkf4L3XVnZh
vIaNM0z4v7eUo4bzXzJYSTLuvVzJj5kdgIZpwowS2QkaL3p1ZZSltuEEOVWo42jc
2uOKKYOgpcIVstUGhVTy/FQX2tFgTwP+X3oSqQ34K3vTOHAbpKg1HHWmZ9WCvcsS
ycTSTt9/3moAHVYM/mHPL8TDWsWMQvBPnq/h/mZYcrek7fVN0CasKDJAbkERuxXS
RQFXkZALlIO5/BwWTK1rn2F47iCjRf+XAkay4hFvgGFbqpnjgIE/mXvs2hDFLNTX
ci7br6hVXotOxjWaaPSA5JIq5VKAUA==
=TsEk
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b119b936-af4b-4a0d-a59d-4fd13b072c86',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//eZZlYNeBo7R9uf8qYvh+Hp/Ye+Xs/fXur5EAYvvKi4Hx
7Nqc+mM3fho2Df1YTtXJpz9DNzynRsvqriQCLgk4rC8ZKBy/dXgaVj9n8Kmrrz3d
yoxHf/xmmWxsy0axxx5qLcdHc5CfFWOp+t7EWa/nIv6U372ofdMoK4gbjaxiPe73
k29TWWI5Kfry8q4Tn6AuwTScclbh17mXqUFRwxcseLXrnyoB0VWZmqnfK6FFADVO
dPIvI4W4a0mzBitD5K3JoCPjeyW7ItLgFutZBQ3BN1j3NEtGX1xWg0RlTj6COYS/
LsstMgUzibv/Z65DPRCBTDiU18DoALAzVbZpyCbHdnNvSPl6KgqebtAifZZg3mEM
LSSYUTdn7QF+V66eaIX3WRKOe4Nx0MQ0RpgKWB9rAi+rD883PIdsn3BIk0w18z3E
HAPq4yCUpWYHwCV6NoLiE0SkpzVSZSOgLCRPjEva7lLCy4xGwCp7qbhcU+ONZwYX
sDrS2OW2Pn94bGqpSWPAi/ts9rj75Ih6VO/868e8i55O0cq7TINNQnglSHSfwexH
iuJOzclFGLDSFA53Rcj10ThqlmrW3Ux4oW+Nwjtt0bxre7PdLWFci9y95kM3hzEv
aC3PimYRuKkGIWyGO4OGVg3NPH45k8kmeRIEyGcd58pGdwS3n7PZxEdPtc+e6UPS
QQEHyx/BIcDj7Xw1tPp7WjjY30aNSSkotFvWnFI/Lusm2+t2c2iIhr6AUU38wqw9
LPyvjm0Pmv7cbzUxwB4twUiV
=4mUz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b13e3001-2264-47a6-a862-49d0b3182f8d',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAwKHgOwvGkTdTZkbQ5Uye/F7DVjEdoEl7ub9gJ2f0JhjJ
M4w81NaDUXwjQeqiu0mriVIZ+ypKbd9CFS5bdGykd7MonPAObl0fZS+jYZxJ9oI2
6Cpj89A0TKpoco+jfz181XH3ZtiUVImTv+CZ4qIhT2oNJlSKvBz78Tzvd1jl7Aj6
2mVkvOMxCMROj0BbX6tGoGOZ4qDGHX2LCszGqFoycJot76W1CaBYHFCI3aQASqsH
Ak6AjcWc7QYMb2bHC5UwaZNE2E8E4z9wPHxeWC03N+fc+G1wS0sISoYhnxsB4Q2+
RlBFHwn4RvQD/+bMMCHqN0c4v8wIb5JItG9z0/FuJXUUxvruFZ2pbdH0/Uru2ZK6
9V9lP6UwqHqQeOtF61wzOq4+I+yfh44eaWHs179PuYX6Mu5rIPrYU91P4eLilZrU
RlHvLFj02WnW6/Y9vxikFFj9XLCSlVYIVgUL2iitcan9iu0WidNputBRqTv/m56M
QiAEhDq2xyKGhazxalKGNix12xx+Thp+xdjeNeLQLSqL+EFxAc+GpKr9uZFbMrJ8
fwpOnqcWeynrX4CBAKL0Cx3gCNPFoX99ONbgzfiui/NhiTwjQSTlqwW/R3tKUdQT
ZSOdwGgZujBAOVLBZAwMjiXTYkIeSa3u7/bl5fczMn6oLqM0ZQfiv/5O8YS/utXS
QwHGRMQgSKWVjHUdbs+uJgP9KPwJAHoqngF0Q2G+uP8EOGIaI/jtgfgOfXA0MODs
tMCl9aq7MMaNDIXx1oJHEs2If8c=
=Z+M/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b8524c3f-f104-489e-ad4a-e0ea12700f97',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAo75jDALgEC3pKvmFDXZ/FbGmFuVlmFFKhW9p9C3sQQEq
U5gpKjFsoLN+afImDRj5ZZE4PWR/Wy29kc2KE0tpsi43eXcuYdkiSnGriMsO1gha
F/rIqH9MIgTvdeEp4rnZhdfI8+ZKENRKzZm7m0zwH7LJZnuKkoYWFjkvVcuoljiJ
34tdYt7JwcvNS1SCvrkgIU2wRz+pFMVypofWcYIYhALtxaU8LSTsT1I5RTffMdCz
uleN3+PS4SareYOPB2nTE2pgqftsRW+rAqInLyVgPRL6LSRT7R6FWy439jCwnh/u
CERAkq/oY/K6CbcQJZ4mcdAfSlapCrtT3Hmxu7RBqUC0uFm8G9XX/Wi/NDwp6F8P
tmIZGxJ2u4qD3ET8JupAEoFvKTAt2dCvW85w970PR1/F/CKoxxDUiprEeM4o3vCl
H6jQxM509gDjAoqdFpgNvWfX+5LRJAOFRszQnEBFi1ooHb19yi4Q33nZZ8qvYWV0
8ozuo9R+EFs5mUxMqfS8nsAv9wL8gwHhVaI2uQ5GkQAaVxNpJ+qbKMYQmVlzP+p5
9Q99v7BxsS8HpTL40WFrg4liGngmIyENOp8ZkfeUAdNA4eBpDMSb5O/r9TARTAFZ
n6xXXgS9eSOlY8Pb6QjZOnasND9qzALRSHKsa+o4hIGrlyjAKiIIb4rSda3G9KbS
QwEe2zYDrN6NjkKcMDK7JaVXjJl3AkCeiIZIhs9VAtwhIo8xpEKdKfLr9kPOYftH
E77rRkc0EPUMdAm0qt6achtHSmE=
=nKEK
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b877c11c-53fe-4bb2-a872-17d51590e4ae',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAnmh7J3rdYWOOLhUZBjXQye+nHuJZRyIbGfYwMbmLr1WF
ApDBT6hb/JLAUCJL2dB8iZwBU1EDU2HYf+38z2UvrFud8YyeQTeVkDfpnBS7NK3P
RUQN+DRjBNuW2dS1BOaqG/z0EyP7Lr3gW7okjjEIdhm1ygc6rTq0CE8Z3T3a8IZV
s1dzR1nGduaTfoRtUgKsWg4ZUeVDUzGNlGsEnlUPyaEqGdZ1wQUpa5mFigpLV40u
CbPCYstOxcf5R2F9jRiYQ37KBnlSqq2BCDxm+8T3cEqY9+WVqXrZPaV2WJJEe0fu
eFP8qqYhpfXW0gtXyi9zuMv//DT7uQQu2deMJfdpGzs0gIcvP+XNTd7AAlNLelxS
wvSmVM56j0ovRVC0obV+qs9I/7EUx4sH9ZCLheKuktwBm2G+oEO5yKPkVR71DjjP
BpkEGD2oJv3J3y/6h3t3rB/oyARQXcFuej7BOJT6z/JzZIofDcriZOAQKy8qPxdp
RN8rEpmV8G8WJKjlABdWo5SwJdm+VsQH7AJNQ9j0o9Y4Y7Idih1TuxfaZBzOMky7
SlOmLgrVRzbwtt3s0HcNHah615Ou/C+Fki7lDtZn9V/x6JPEPZjGDyQbt8m44cXo
nOOTpCGkKYVAwTTA4k5BcVM4pRCWn97qS8D59bdFyFPPChL7I6lLTEGzxo7gI9TS
TQG9fFZxMGLRKq0a6g7dxiHgXfYQpTnkzGTlwIyEFSRnZfUrY9DvDo1CsgfkZV51
HyuRxswjTTA5d8f1+Zuvpl+r6pS3wAJaYtPyTl1E
=JF4V
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b8d7d56f-08a8-43bf-a702-9a78d8cd7a97',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+Mic420nM+GA8LFfvuN95PvZy3c5vTcLJgaoUC4ullpxK
HGrXFyMyYvNL+BjC/lx9viuEI41Oz5VqyfQSy/Mlx7thTM7cPvI/D0R8OAaGZxQn
finMPnX3pTASwhPshlaSwWCPFCO1wrIjacUFJD2bezoe+wxeLtqw8H/R5jeyojsZ
WqCVbBekV2YVkPqBxm8tibSTQcfzjWmbt2tYXKEBHF3Lhpcc5/pU4RsMO10+g67N
ZazVCfKwIgwWURiqGz8ygBK6F8vYggyhOJdCsa49dZUxNQQJBKmg3qV98tTWCumY
TKdLf9SSbPJIY8mO/vANkllz2RZshp3zUkug4fMztpW5xTwVgPwxzd0mv7J7KrtR
t9BbGgy8YgSHc3W7ZwCWQkn1pfRKbjLWqRfJdMaX0EiCpCXrg0Ju2/87DKbTu4b4
m8BAdcpuWNaDcj6v70CCFqPdVbXpHvZU0+tzlLw4oWni9NIgRykD1zMDLU7H0IvW
fCoDtULAnW8D+icndYQGnKFHXbzqgEpV6riKNTcOxHLQn/hRq65NlzEA4IBuSs2z
x+0U5Ly3IaC3z/11bu/sMpBYO7KylY0K1RflV657BIHEfWZXU9HFcsqAU3yCdxCD
ZFwdnmc2tmU/iUq/c6f2N7T6EIi4UpiVAlpRlNmeugS/MOzR4X8ca8iSiv3QuyPS
QQF6KC3rMoARvsxhiSq4kSOhF+y6rrCJSjZfVZXK5CnLDe9SGiE+s4m0sIcGP0zi
U9X4NKQMOM9KNpPTLZ/mkonk
=ytIy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bc4bd1b7-11a5-4573-a98d-c590e3a25196',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//RmfMigYle7zFmgyMuLXPMxMh0KCPrs58IC6eBYCkQi0E
jVnVGuqJ3XrFp4BVCg+vbBG6z5trrnUeCdBEG7AuU7NWIkBINJaSncxLmeC/S8QT
d6VB9bl7JjXxg71diAEbfHz2fAYywW15rSWUuBYi/8E1Ty61teK9WPTF0M0vcvYY
+VFJ1C0TI9Zscp3dLCx9rrnsD7b6P0r0EzhAvBBtqxyX2qTe5KjRuHYIfMiFxHUU
1eSjPpt890uL2i3rOfXU7mSrSM7h/ynQ0ckyjTWt5cJmgqYILkRNnw8xz4zT6Tzw
J23WydwgnV768S91p53q6q9cwN6kol648+wTymAmev6um1UJpxsVigs0RBg1HdOE
0hgEdyi5ok0venLtc3IDWliE6yIFjHaTmH5kxpAf8w+EOR8FAqv1dBH/N1O4nsAi
uKfTmt9nGNY6p+SpwgrWb5K6BCMaFxvjSu1orlJGQSVYe3X82FqWvughZ192pfpQ
If9mFuQGN2JIqZ6o55cyoHkGp5749lJJ5p6AxXQZ3Mxk3yPQKAx61wQ2zs3EPqzh
fuR8gkfdyj1vLoiQaTf+sx6dthTVPiHRCRnirW4Fu15xLF+6Eqp69Yueyx51unC3
3+o5qXoN2VqYCpYCeq4vSTB1z+tpZIPIBfVpkrBaGFyZQR0+mXbsuvoFxz3AOurS
PgHkEe6d8KeslmND3A/9BAs/0/s9lVVsnQIpMfP9rR1JSG0H3oljYoDR0vvpvyra
yz4iWaIRsuDZnL7jn2dJ
=UbSO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c1f404c4-6076-40ea-a7a1-621943ca7e7d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//aA8z/XpPb/Uf7bHJAuEgLq5FA0vDUAfTLia5gLp4dVo0
J7ZIm6x0vaoKmbkNNWK6U6Dfzu5bmGeWVC0Z6Z0HlOsDd/G1uoNYSqfecuD/uLqv
JpJbNIUrVtGgufQsnr/0lFy4lEorxmLYgQRqlRebJIqptg0mOxSKs4LffNsFy+Qj
88pJb04tDizirH/UZLLprUvIDZjcuTgQuQ+Wjvka7uhMConWiAT++tdONIPS7Qnf
Du2ueFPQoypANqrm2xUR3gxdWUWOeIjZwWINwXll0r6Nc7TqhWA5p71pzJlmDHt7
Yvwhp639G5JS1uIQWwSaN3nhvBZltDzxM2219vOLyuZLHljgnkar/gOsyCDh7rao
ZY48nEx0WsPIepL7kcWm3Q1M+cdpmhq0Qsb0GYnU5dkN8TMwq5jEnOvFbz7vfVFy
AWZTBWL7zIOwLXEKe8YT/pl/0yui+DUsMeBUvdNP1wFfjLaOJ0KWX4xvpl7z5FB3
+e7CwDHRhNEv2f0wEGhSbaL9CVgGdwrzTSw8J37VCQe21P4IroVk7MqIvWBx9oIi
ch96QVxc+pevKovxQK/lcnJ3pnCus7VTVBazOX8uRvN6L9Lq26dqbIwyvryzNqIG
K9eeksA3RxZ5QUULjUiO418cutUPtY0ueKn3NKKaCYqQKlKbG+22XM7DoJ0S20HS
QwEf1k/dSv8Y5pLyh3kUP5TCR3PZXbAvtmORvR2wlgEDnhHMplUq13X9Fnb2nLEK
VXdKm+G1kSe/EYIgtxiYhc2YHd0=
=/WXb
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c3abeb17-84ed-424e-a195-7aa7a77c5a8a',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAs1oa8D/jusOYZHSsFRpQ2VhsF1uQ3+Uue00khZVYoggf
RIqjD9BaArQlUo3pmJfcJYdqh1uvuk5np5dLNMEv8BhFyk4NEhAPgWjZQBC6ESQe
ALmVojSE6cexAhWKGmCudJY6pVyM6DecvU+f5+lbCoQJaUGEKaJpMH9u5BrE0xaH
kPFFUDgTD24zydrTXepv76YCVtVKf7BeNVRUxYRRRjbvLiChpMWyjAGS2SWxWqdH
V/IqGxxCGwq1Le0rPSKxvI2WrdpQYD/YhzI0fyDtWPTRJEY1fauq46VpaVJZXp15
dp/ZRSylh5u05n2phmHaViY3KuFyeYOuo5hNFE3LCPzgsCsjp6L4M9a/5xPuZ5bK
yfeLLsWRYOVVieeVBdxmMUqvBgvphBpNxZRpZVCbDxGvd5C84aV4VTfL2BZkGp4s
Vvl+hoXmtX1SzMDESn8EFm7lqumO3WuPFYNceO7Xnv9tdOsIP+nTpAs4qXwobzXx
ZPXEHfAmqOYEsYJ736HCFyaoFJpTTLcYUkuZtRE/Rzk9zEBWQZ2ywXrnqIkYaFF4
SqyWzGATegg4zYeMYlZeIMEZqhywef4vwlqmUE59OJBohw2Xk2boALSg+YxjrTaW
NGT5J15aM0xBcm4836yfgj0zq3WkO+72WlYwFCUfEno3MAOMbuoo7H0t3K1OBEjS
QgEEchuaO9FyZHX2KWCuC1XD1GSo/jM6eZl+sn4ObKNUeKsS23F2seGor2aBa9Rd
pUEm0/uLYKh2xQisMU5nm/tvbg==
=sMSA
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c65c225e-940e-4fe0-ad29-fef4dd5e20a6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtDrPBn2dIIxHFIHYan3SUJxT5VgxnyaxtfEORPAXRvN+
+o7LalKJuERoZLkL5Yz8NfaeY6VhF6YuDTTBroUywNiFylV3Ajin0hXolbHOBx1W
0CiIKSK/sW4BLCBxCZ66hluw4qlvlKsFIcHT/GiebZnfmyNAlRxLUTK++ybRDS+Q
dLUAqESmy1lOaBtLP1jeORn48iavC64dcytTAlaC7Khpn3rI8tb3gS+CHzhaBv3E
4uOPxyfcptcctYGQ3pigoOcFmS7TIvAbOa8aYVQuqwj2glRuCgT1TthKuQnVbRJx
j558iziidWlUWB0KXwKKp5dwRpCv3aYRiKAuV8+rTvyl/1y3hZC4e9IazSMtP3RN
kxXn5BschTQT9PVVEI2LuRLGR6pCTJ7b4vJ9n9QJISdKjPuPcYSJ4HxRZ9z6uvb1
IFRMqSpvwojJ2+t1iFgiPpYG+H4aJ1zRmBr+X0rmI/nf5oqJ6L3KcIoQq/TUKzyM
fia05W2ZgavnKnL7I0tPMzsc2fG5Qvf7glLTRelQ46hXW1IpCEgJduqTVQASKL/P
6ktmdXAAzoTIJDV67/bxublplrWlsSafqW0pu3AaS+hXJtT+JPa2XNNqP8hqkWFs
Hnacd0ROD0a7AlyjRmoZfqANHaoT3k67HwCHg1FvG/cUHyc9SXMYakdZsrsTGRrS
UgHgJNHGOW2egdAWP1cjoP2dMvE3TVFj9E1JHSIZrW4h+VbbhBgJOqBcNWUxgIpE
N3xf/uZKIejFPOa1aH3HJuILsL23WRNAXI1Xg7pGjm6qSf8=
=sUQF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cad01dbf-f401-48cd-aa60-6731a4c31c4b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8D14AhF2HZU9CB/y+HUa+aevwKwOZM0SreX4yj1ba3Zu3
yDHDVxX7wYriBIOi0Gg3N20/EaLRWGUAvp3C17ZCmQT/jKR3N1sIvE7RRUi8uvKr
f4eHrGELdfd65QWtFTtwC8NrdROeKxN77OBz7tHJczjHjsjBfCu/r4jKJQ1YKk8f
ZUAiBSXQcsH2XvSsqDlgk6zLJojTPgXRiKKkhKUtGe6DG2t5whT68SfLM9BiG03p
7UU21bWHWGaJpdmdKQOO8ew4Aw6SNvzvJXeJRsJoenqdbSE/7fYrjyZfVk9s10o/
VDaLKz8tQED0/P437R6lb2xhDi8UXYYH4Q2qPOMkXzX37o/4Ech6cu6jnx9KtTBk
nxzCQdujVHClAvm6AIImSEySXOfglyPH59r+2bGVCNzQcTwwhEaE0Q4UZP99ilQ8
jcYkzJxOYjUqG1i5Bfetl4zASNfF7OHBK8kG535RgvCEi00gN5imRYmd9wllbttP
B+E1JfFAt0V273Mg9stT/iUELakG45gNM2GOLEzYX6oORAG21scu9AqnvaK6fTSg
f/WqHpN+g40EuSESk/c4L6TYwBOaGxuAV5HhZr4jF6vG1tJ0+akHDC11JDVBqc3Q
s1fTpY4wTDky8mrPG8pgIDnfregWNNPMPYylJmKJczcki1am18G1Npk6XiJGdsjS
UgGPpDiEhdLBt6A0YvZ34wTdZocJKC4iaDeWiCCVS4PC8D3hFSiiCFfzEn6wKHcr
Aa87pF7XXcNive1bfGKOKO/dM1aD1+ISJ1ooCMfxac3+eI0=
=Cubq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb713d68-c449-46a3-a563-abe103fa0beb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAloyuQ1QFuz4CoMlfm25aSGNSvmz0Tsh5Vb2UJjrJkupX
7UICHDiaP6FiUtRso1r2BXpR0D5ZcsO9HGhyr2Y2/aHioNp37lsO0LfErvixh1jN
HdUdW3abgBaJ056BGW2+IktTE5XhpXFBPW1XYjR6+vjDGZGp431oe/mKKhLbcO4H
a0cP42Rf1XQh2RmRQeCY25tj2InGcrfto8CR8YkUdO3Ya7aGyv2jRdoswS7EIwaC
5HJWzqrLtsUZitEeUA0kodfHkUNMjH1l9oIlL4qs63/4U6NfDFOmOaed/XOrR2Za
maAwqU4UboOEf1Om8lv9n++ekUozqWY6lTmXpFmGeVQZ/accLlj+n6zLPSBtji0X
eGTUrYnPq4n1hCn9JucOFvr28OfmAmvu7fm1WKq82clCbl0tBTyxt1PbmV7j/8Xj
ZX3SoCgDf+ey0UGFQHqerp50HovUteQhPJ9DUbSj5V4D7p+1YLB6mFT1s3tBAxSX
exJPCua4GCyIoFsXjI3KL2oTUn+Q+Zaf+guwH2eq9WbB+/1BkWd2LSQljYoRSiM2
pmtI6/j65Ror3sm9LaVnTYun3bAyWNPdpCfE7F35fiw5/QXaFuPQ+HKF8uhFq+B3
0Y76FIxfCVsyR8I2tmq11IKhvTA1XHkvCbJHa0+65M+KpHD9KE/567oLw9KI3TbS
QQHzzsenzcffkhszZMl/AcNHbct3zv1vb9Eji1+iJhqkPoQCiOr6AK8Zk8Ggz7eG
UlxURH7LF9vWELNSDtDFtYYa
=zInh
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cc184bbe-fd66-4014-a3a0-a56555610844',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//bLg18yDsstC9VEl3R3Y/aBy247BgScex3b4SOGWB+Dsn
/XVFesQC+YexYJCALhAdTySRN/hcndLFkUTf26J+g5rKYAGcMnMiSNNtQ3AWmhnC
IJfnBihUiANpyc4N+bK4hHxnWNx2cNJoqC1j8vxE3HEV0mhyM18Acjp7JqrGdmDP
lvW8ZuRrlAgY68a1Rz/xoRFUKTAWbcHBPUFi5bI/3PkFJ1VRagJVozSJdWjUhxie
uqVcs1s7trgWHJt+N9BtnYTrw2TVWGgFYf+RJoB2tM73+tsAXCqKRK5NnCynAatx
6vPLrTAmrqlDkGi4chtVaMfFWdIoXWShkpodqFPuBoJw8YW6LPN0iPPW8TnrWf7A
xQrkcUhQRE7nVHHxAUWYQg8yAnojeqfwN860MRen5DJvSxcBEsMLEbAN1NRoD0a2
fOQOtsw2SiNsha+7klYctShCA5Ul+7pMeU4djitgPaGJjmDP1rLE5CIVhSi0dUbE
Gh7XTn91jScmMHQVZARl4r9e4qbbqHFgCwkUqWg4AS7c4pFYHxK91lvfdzL4ads3
Tc8Fzi2ZAwDMJTiYhhQD85GdWl4FcrjbZ91BRET/Ut9pAzFcsWH9YE+zp5XP7q16
UiCr7So9ffL7URRyBjXzxvkhPrf1XXtZ8d9Qed+xucS2cMh3Ln21q6Wx9dcH8QnS
PgGiYoR1HMFCHY0Q3mkS2wFk9TRMPjlw6NBs+mLquYsvnqSm5uF3+F0S8czUDYSF
bHatR6r8t5oxKhno6ASD
=TueM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cdbb4a88-40ef-4776-af19-228ac70f1195',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/6AkmDWeV4IvUisEwgaPZhCS1xZoRX/rtuF33Uoqszb2RN
LH3RpLm9V1vme28PwGd6qSJ+bt9x67U1ffFtG4fSkPTNCyRTAmKQc3nc0OJss2U0
4G3xxoLsfB8vSo7y5C24ajWqQbZnPVIT5jtbsksPj5x37qRT3qIgHf/GF/uJ4hE8
lUtWrrzzOOL07JTPvLmgpqf/Z8V7T7truIyvwfLs4OwZJ51mIKFvC5ivSX/HarOH
xX8RlzCvXhwO+2yD4xc/WeN4ZAwgSCUUzo5Ewl1KtsMu0mTE5pz4aSZ2X/xvK97h
TxYyUp83uRktEvLm3WXL0jItPP+TSap6xxBsniVEtpdTBH8GtLnKqm5A9mxLMkOd
Co7HPLYcwYjSkG0tZJ4u3NLRzUrs3G+VIIqHYIdOqQ8ApGL50KD8N64Slyei3ht2
kuQ/LlbLqHR8zuCN3gm7z9ijsyBhkHncD4T3yYpH9w91xirqabgZKKGYIGhuSKmc
oCVegmVLb0A0dkpOTS4P0tj6XMCxlw0s6uF+98bbAP1yXyvDSox12zPjmI5gkriI
BfbAi92exariT46QprPsErYZhwrzor/3hwXyoV3DllQfyk4A1YOBJXW84jssIUtd
X1YZbcdJOZX09zZWy+cjGz1h7AcvW0TVSJa7h6JJmGS37mO+NmjPlrykbvHVdTbS
QwGqMeTPVS4SrwaGxKTYYmyq9+A/U7uMob7kytFztnqveqOfbPoGHToJSNG5l3CW
MMhKX7gLk7eF8bpVvm0QrVZtOn0=
=oaYl
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cfbfc54b-9821-4498-a5b9-d5365fb75868',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/8CnDOhnKcQ+iG5l/VOnMKr4ZVaY1rNkpD60tXf4MBAjGU
d/GOTP5VdFNX5043DeaqNUjMjYglP3CyIIEdnKmYXuFEzIa7inSASL/3tZbKN05/
94rV+PlG+QKj1E2oJ1fApFyHrsNgRx4xJf9BDCnXI+br7+b+Oj7/d1WhfRc6v/iP
Bjy5HyqMuY/IhR8GhRAZDSOXJmCpaciapugDHUPDFcgCBf/xJFr7AV3NFP2SBO06
8PgEBDw/Ww5UGKoPxKTTvSD/rzZWUnmxkk0KhIf/WfR8aK0OL7kq3938/3n8tbYR
n55zPqPabcLLv5U8xGjDvV4U0OzD/cr6JmxLt+JMBwI5IMbmxUkP6LjOTb9AzGdk
TuAA7f0BAuqj1PSvfCLhHLFhbzbS1wd6IrIDOAoqRgv+PxtHwkdDG+HiLvclVUBo
p3voexsORBnn/sf6SaU5TX+U1KTk72OmW9ME6DpFHeHntj1G4+3zNRT/onzI3uj3
rIV7UVdAZIfv10sgERLgEP/6VRe1ibyQOluTZ+M2YiCy6dlAkZ8f5fces8iBgPJ9
qbHAo7jyVKw0vCLZc2ebdiLgPYMGCKLd9AaUXj5+ha4m/i+85Z25MpmnHA3IVoT+
5V+myOU8EyibDSnDJPzOHQnlClLnCvoINFp3goWVVkiCzh8dfDVaq688BH4tThTS
PwHzK213tVb+o0+UNojzPhPCm2V+U4TrIHQBxvMdWZSSRGy+HzJ4xy6t/CIDg0/C
boGTvZF1JR9IORDtofvp+Q==
=GTPj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd0df34c1-bff6-4cad-acca-73c3fc181616',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+JCGR971yPM9AbK7mJ75D/lcqB3bmtuFwJ6d1L2CmeI4P
BTNe4JOGdqqISFbOH1AOMbD3/yvx3GrgitzIPmwruKahO4X8HQ3MV5SV2ss2c6M/
RRyU4ox7Bk0oDPwmlYBL4omdsuS1DlL2c0LPCw6dCXUMQQZmLcCs4uOaHKmfYK2v
LV44n8WRreJD5EmlV/ivU9ltFjo9pt0KV3u5rst+9lTXM9tHZosjOJikv7jOeLhS
ySkyz8LMJQtsHq9V6dLLeTCBFAJ8FJMJoRbBJAC8XVeKlYrDkcf3wACVIxSDVNIS
2V3BvgnHrR/mCIyZzwUBh4HQjbCIkJ+E0qKKFMffVTINb91rKl1vhHiPiLW8RXz0
RRXo4LnsC3kWw5mzz/Iuzni7J2003Tm72+C135QNS6QT9zYDYNtrDm4v6dCozivp
ETyRM2amfF6AEdJ8lMYwLYgIvp0UqrtTGGKr+Xc7X4HS0GoXmZAphbwf9ntenrmp
7Kgb6eN0YjP4yKLuyKHOHhZH6m6aLdA0rDM4J807R9IjapirEcpXdpIwUb5skdtM
gJCrv10ZE/HBfIiQH7D4g74KcjZsEWo0mnULA2L4T1Pr5WwA4skMNFnNfR+VWPs6
ervHXj3VyotICr/YWvfUwhrsVJkrS/BIkGR59jEEHOcC4M8fa0SMAyDyuLbCIL7S
SQFioSn2cWh1d+KT/GZ36uB9cW3IVyHVQoq/ZGMd0MTfjgZMm78KUGR1l+0NzUfY
EZviCOgCsFPM7/UaXOpimTmUbZtN297N2IM=
=UCod
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd24f8ec5-0fcc-4dbd-a3b3-9981312457c3',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/5AXyI1zBb2WjEquaq8t2ZEnsDypRKmJNTRiNi8CCJoY7J
l4v/5ZkzgB3+qEpUoELcRdycrHQibY5kskeYy0XXMhekoWICBI9x/Jvh2viB54yI
XFZkzcvWQtprBdlVzkT/vuRY8kqj+0x1GKwogu5FoKAMJgUk1/k9qiRRAkGwdeN6
ZOe1CaJXW8mXvVFt3TKogcvSmWpQU7claD0j2v+rUg46cGyK/9mM2fGI1x9h7vYz
dLGyH3t1mzW8ZP2k5r7v9K9XI5Z8jm0MJP/EJmF01zNmr2Jsi6qGUy3cn52BgtT6
zsb4/ew897hygRdnv/E5ua8hGUuUPQFwAohuLGfryvlvc0PB2+nbaGRwb9QXd5ZS
PAZr/32TovCyDGs58prJTTyzBsEC7wN5KDE9uwSDSUsUCrSC30SYv+wHBY8bS0vt
tsZO+QA/7PKxUg25ROkpK/Dm6rgpPehGD5UYnIG75HjhBRBRx8YzfkCuymCHcZ11
V9RDKns/eUu0XABtubT56a8zwFTyGAlNmNHuotRBcmFISQDFkF4wlajWF50bvaND
1X9H/KPNz18dvB4HU3K5FcBRZuDruSQEeN8/70aafaxy1f+Y1v7p3oyX2whxpFbT
bE15D1cEeyeGtPoB/o8wCs4BcZNtpWmr0N5NAJvPIXEPTmT/Jr3K8Zy9nvLIarHS
QQEf2WdH05cvvyJYMHxvqkxviiPnE3YFY2qUMW/U0YFBOYFYd3nkzBIXmcywt9il
oLDy2sYMx1DPFoPmy8ffqSko
=akpd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd638a3a6-a7a1-479c-a1fb-83d55704aef4',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Rnw4LuZfV5e+wv4ldue0m3uKWBKYsMF/N1FwWzLoomf1
1lj+dAJwB06aKTfmbiCADqqrr3Z5GlKHN+OXU0eb8OnKfTmyPIEm2uJzvfkY1bR/
QUoqRuO0XB5LBIURJdNF0WiA3p3S0QfRX9Nc6NfcvqucuvLsJQ2BEuuQaH1uonZg
iLY6pCMpOKsNUOC/D5swTzWlKkEwMDz29DPMjsIh7hmNOmxSCABsU+NQ7o/E0N1s
3tIbSRdlVo04pUF6IbsA6fFaK3NzDXZ3dj4CNWbaNinVVI4HIDK5pQGg8qtOGnMo
5/fg/xWPNvyruAODomORIuCfdcAkiT/qojYtR0KLTD4O+vdrYX1WPvvxyW/RX41X
uKAxCXiJEal9fRBNM7UxZ9FUHn/Ycc7ws2btbSMO1QISSrGdG85VWfQYQcYGPVsx
HnLYPlVPuT0K6uKXl865GfZGDTDfSYpvGFF4veYwHA9jtpfz0W2PkuUoW0M3I6Qy
5l7UALQrzRXASBHDB2CcqbBulbYHU+h42zls8NpcIKpWmEL9x5zRB8ITti1qDyF+
CdAbmCSkGwKlsndyu1g3Xz7NK3PGj9+OyV4aw6bAAHHuRIqvfSs3OmSyBH7gPOSH
+d/usCvqhu5K5yZ7Sy0gvoBRK+34vUj2a+/l3xDP7UYKT32TyPPG+LtWOVCzxFHS
QQFwPsokHIZsLisEf9peM/UAS5hAJHwOrpypAvlI5EGkwWpQqbsJjGvKrWx8PCiU
2zZ444vMZkRmWeJ6WiUrVTF9
=zGbi
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd86ac322-f82f-40d6-ae24-597e8ca59869',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+Ij9WKITpHzRGLROq2xDJ3I3oGvtFQTkLd6YonqVyqbvD
7HnrVVBX4e9nOH2e6Vzp2jxJOW5CaHAIranYEvklMpbnxnZnmvA/lqlshJARUdkM
KnLXIXhS39jLZl6+88iYfwf/nkZnI0G/bcHjz+UsW5dGgbPacgPCifYlwV/uJN36
E5RsDdfGRysPsDAZCPnc6koc42kdj0nM4j4/SXCb0TjVKL29zO3N5GXp0YiNxACg
8cHYWrqkWb6R3JSKdVysTzjTvRITZRT2wAYL+g1zB1Kl+NeLHl3ad4P1DQ0332Be
pi2O8Y55H4tXMsZrD5d9XJs0CYtJ8QFZtsFDEY5LbDUSSA6dj5wxtcZrluASU6y/
8mG5l0Io8+8rEYhShhBwJIqi8mzGmJExDh9WQ50BCj55r27K+zjHyzAohZIg3BFK
akQ7fCKiuyDYjJwH2kKsKi1E2uwLO0YXUdncXxef+68lI9+RPgcXTKbTdSqISyyd
B+EYiPprnToTtjv9g3hRktmgIG17tuPYZL4Ofk4fSjS/7RtgMnD5sdUFipSZYQ1/
7F+CVOyX3iBWN4EuzLJiqeUZwdDztl6uCiPtWZwweTeNWsw5wyawLmxN6zV7aAx0
UzbHicYWwADrGmkjwim/H/mnljrQt9K9GL27BUMsdrpFaN0D97c+bYl0psQl8bDS
TQE0qbhwBd3+enZW7Zr/5DX9sIjRItp5SB5ETAABL2ZN8iht5+G0MkAO9L4Nxns0
XCXVbiVvl8C4+VvkprAhpG75W7eKJCfm625+wcEg
=Tr46
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'da692a30-bf4b-4df2-a2a0-7d118c9fcf7e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+Mzue+IpMM5F3Pg55H5TqAhoimDaKYJ0EVynnh2/nPCh+
Y3fj18M2XQuPfIQ1WowFxXorusRTbWtsaXqagGVpECEjxV7kEys1C9GxMB2t1wZr
9lfNyNgti3XEko1uby/WR7aahor7P5p3ROiGvb3CFPf0LxmUY2c0a8tEa4h+k0p5
h9Us1z3Fap90dHse/ZwZL7NJw3q7b5i6aPxZEqhCHLMY3Oxe0aU/kssXbPDNew1X
7rVBP7HTYzncjug3ceMNm8N3+8pcN45Rg5IKFvvWVJ81vVqclLv6UNpurV3slPcK
8g5FcPUG5r3H1AV8vAfpgTG9Ur/fbA27oCQySNoUz6hh7htzPhG+qiQimZpJJG0e
Ry4VuUYaUsL/M/HEEUn4AuJgAtCGN4HmjO9eX/Qn+epwh7/Sdlprt7no0qHR2ANC
NrorFpQ4t8Ozd8KKX/PhJVziyjYkoRNAZPDQ5y5AEW3YotPtAktxFm8vTPyrFVbb
PvoOloDAUG2S+fR+TZ9Rc2QJfXQKeKrX9aFQ4yD7352B07NubhfMiWZLY5WlQLjR
cm0Z3QTdrMnzWB40AQu0qhkr8t0y2OOqgytBvTIS7Bx6cEMoCVYjOM21o8xw1uD1
iZwNqFliMi7ghFbHKuShsFUfeacLkUC2QhU9f7/JMHpNYc2O3Wx5OQafRYSUUeDS
PgFn3FmjnV0sfR5U/RoFQoC8RBLA++2X3LRzJ9nWtJ04QfNapo5kWCr5fTgGx9xf
F4ltFYhrdRH8HWH42an6
=ezVB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dad40f03-c710-4358-a1ac-c371d4f0282e',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAnIeTNGZlfY+8h9yUZTR5Cbzl67Wf7ftR4dKZDZWwlQew
dYWBTCuxdR4N+gz23GVKLUV1ZOgRG9P0zwuntilLPPsJSrQ+YIaMs2xTWCVTe+0a
sBMjMwvPfK1FQl0zTdfhtAsVlFWpnv/c8IQTPZwxpHOqPdYCuI+zXIZPIKfrdFda
+wwCO+guR1t3uUhw5i++kS0Zhq3DNy98cxAgtTDVSLFKFqUZqUJhW2g1Iv6esYJ0
wqsoASCwdLFVlEE658mNVVEesUcGQGT6ALCQ8FMxsE3+I74RrPadq95fo79ARbbX
fBhRmu4HvrIHXqR8firzc2pa9ofLDvjwLibMEir//e1KWsvrTS/NWRl46n0b0uyF
aKU/7MoR3c70VjOYIVHV0KBA7JxDy54cg4JTdqPVPuMzb24ysUuODFc0t3Hr2nQt
b6ee9swpCFaeO2TfyXmO3t4Ni1ceqb5wFluH3JXyyfCMom4ECeeHT/afDfXW1Not
W2mPWf262BAltNEhCuT98kqo8F+Jek0S6zmz+v38dubzMCUOyLCdm1x+kq3QSfjS
/BnCeNa8TXpb9SeiYii72Ms3U1FQDwv36DHb9Qgw62TsCheoywlrYvyzmfDRHt5x
+Ik8cfczRmQ6lXV+ePP5b4nn2p8GAKmDw4/EryIFx7fGnaVnCSenxsCOKLc3V6nS
UgHSVaa1fK2tx0kRG2LYOaE68NT6MQl3CV75bxIdqVktCHx6BXXEtqPH/ycWppUT
fJd1OCuCEsYDN7cKg7Qv8GUJt67mWFwtoq/SbV8BBD41qeg=
=zxgr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dbb8ce97-c67a-4532-a74d-3c5f10450ed2',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAmYBTrdoL8kv8BLuTTRgYkG5tjFZ4JD4TJBoOr1nuWNGe
21lTlFpH6ssrjM7Y3YexFA9H28kZgCo0rPGomvDhkibTYcBb7LkKTJ8nO/fgHNQf
8MPE09ydyjcOJ1e2oVOsawleqYDLwDx5N3F2sq/y5XGBRP4x/zMyTYC0eXqwNOMj
NsevL5gdYyi/1mrNoOeNLsAy5ezLQIJvlDHDsiXvT8TPb76C9LfJxhB8tHeQBLs7
4djzSR+O+zSZqAv+7jKylTwD44YnYXiPHFonN9KgSkXQClf2YIcChJOr+QnNhK6g
0YIZLPnrSHUYDQM+Hm3V4oB8hZIY2u46uTNSPZ2YbjzjLFNVHCojUGXLpza/hCax
yx+cPZ/PbPef8SnDb879+lDz1ZCqscQxH3lgqF5i3ls9VkuNFYdHYVW8Y4cOmAiW
u3/8QqnTjr4E8/oQZJyhdKNS1oomZkYK7Hpd0/d8uzu6u+ZPD4m1C2dz7MuwZ68K
9Lk7JgmtNX1CbCtDSUauKXAp9MX7KIzwRASuVO6dgpxojJap32vuKZGiN8tmJ/hr
IjQsZr86PWadwFtAlvSRxmh1H7WyZLleKbpCzBlMrrI6yoz4jVG7gAa+ziq1kLHe
PIneQg6lYtZky3p637zcwUBzEI0Ci4L/Ob7h4SwPrNLYNGG+mO36D1D6XXuAwPLS
QAGHg8rJjV2p36A67rX2KvMBiZL//HPSNE76P4oXuu9k7hZSb77hmncBg0psLA80
Ui+c5nmHCih0RCAsuqDHIq4=
=MTG2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dbc67224-9a1d-478a-ae2a-97ae9d654dde',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAArQN+sF7cO7hdXkbZuAQEiZ5iru5Q50Nrcg4RKrfEwgAf
w5rhpI5hIUutJDUQxdpXY7/4vZ4vAfx5lgVaOaBsP2S8qwu3DcksX8pj5aSxKczY
V0vfXfS9kHS+TDskORtKr7AsHedyqzYT/gP5e8h6GJ/qOEvF8O0w961/rht+gMWt
nkGrQ8Yo8Dp3O3EgsbtFzO/A95y+FfWojZ62ilBTTofWCwlFa+BkMaVj/VdmK4gD
8g8rmymCocJkxAEeuEC9piFJ2zoI60hFAZRzVoQcWrOvkRyY8u2rsWlSij2EYOxd
Z4MAjuzCpnSkivOdP6pMzRsDVnivC2aF0KBRetJdx/tiSQLXEgU/Js8fmyt2UPXL
5DqDBQvElGWDsYkSeSG4nH21rAxPjStzP6lN1SNxIjMFrPSkXOWLw0h+KHCkVVdP
24NxWcNcBHq21WYX8f/M+I6qDbLBGZtq4BUkzMkaJT7gzixRI8d7boRokOzUfveg
uCP7Q/rvUOJ+9OjexPzKBUP1DqFsD0N1cJFAByMmLj/JfcVTD8W2Lt3HKF3idaDU
1sgWQBZ2Yx/zpjmwLbD2VNi82Qw40k4vQ05RWZ1ljC+6nO9VLJw+C/Dyv0R9HxXw
Kk3+mkjtxbFKXhgOuuczu82A0pimgchrXwLi8Gzr/q9SoABaHeuxmEZOXkCc5MzS
QAFSThZrROu/Jk5TBCSHSh8i9KJHf5OminPT7tiJuk0jAxBuhsnfGo/tppbfDJd/
WGUhfnHb+YzBJuIN7X6RIWY=
=yxAP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e39233ca-8100-434f-abb6-4a7602d7b32b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Ku5UvN7J0ew1MAoa/g+s6Vo7AmNTYsKNh/egBnYoEO6d
z48HIFlmNSWuZTKfnTGZNdIxi07FtRwO/1yqz9bhw1CePFQIfwtqsBo+JsSHE4jD
BGIfLaycm6Qo+XbcvBoOzkGTPGI8fm2Hw+sYzjtWj9y6xunKVdd8f0CS3EwTY0bM
tgF2cnE8CstpFs25r4qAPZCAUuJYhW+TbFKiIy/uq4p/7Ib3IDxzJoB0H60fOkja
SYf5dUcEVVUjdOevimoLQSUZ3bBoAThpLBv7k6TXpAdiTJsW8NAC6FX9jpWK4WIH
aBr/uXvs8od95h6TEJM7mOm1mVKivrPfBTvvo8k4MIOObKaTT8OxZ95QqtX5T5uC
Sz8jQPhggVudfEaDg9HbKyi9wKWSIrMh1Xl0qExJD6hIjXX7K/ZUPfKzduNMUpuS
ihbY/BPa2hrQMC7npG9CB/HtzvtC744b8rZrgkbWtuNRTnX2cbaNku6ekT4En6ms
bEdfq3WgkcPdjroyQGlNr8F/bVp0yvSZM/cXnWH4UMRQlUdzMnRVYfsM8h24v0nh
15UpVFlQaYzTCDd3RUHfkgrOs09TkXMaoUkSjWuF1cE61ARCfUWc60qBgDTU/iP/
rrAX37JU2e5f6YkxUK1e3oZ76zXnxd1nm/UT33gC0QFoYcdspXKeEmc2caqjL/bS
RAGOK2pehD1rE6tGc1/fkAdceIRLXK7dRoJ9HcBKHcRR2mFCXCuLFmdp9tgjVETt
/WLInbWCR9/X2/Phn4qf0MJwK4mX
=0CmA
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e47b056a-71f9-4c20-af39-b1a467d939ba',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8CYPzqbP3lJGHl/AM/6g8P+A5ReHUgl3TT/yShh62Zc36
s8Z986xKp+Z1htCU9XRV9vGXRg9iG6Cd6VMiQCxAD9ojVbeHdFgJIEyjo/2pnLrC
6bqAq8PKTH6vd4ZVWz28v0wA2HlUvEv1Ke3g2otY4L2LUtpZcKoWRgcSr+Jk4xIi
q7Xijfp6LLrshVrzzGL1NJzZlKG0gFgP7f7dScEB25sIXzvmFb7aOxa9SjoAXu0m
PWbqBDzI+WScL23fFPf4T78aup9/aQBRMbcVK0oqYY5Iwrq89w9rxI+5BpD84H75
YoQ+oJSmNWlnB4CXt+O9CMIFYwfYdOt9pFzHAUaSiISH0otdTcAkydgHDSAUufYO
86zhfWPHVWZWO1VniI7fGWowTtCxfcMpa0Z8YgpxyfRkqd260iAqPa2jS+Uc0STL
VWS9yl41XLlhd7RBRPKhq9/yfSx14K/38RA5HnkG+Dw2WmmRA1hZLO8x5yoCi4kb
yti/76CWgMtgxqUyOEcbYEi0CV/Wb2pAfWL03U1TOUQ/DZgdz5221xVl389jKWMH
TObUloJ64yBH+PRIcp8G03oan3TFgLR73BI2dUKHzCdBR7N6o+GePO0hisPnFJ9p
b2bWp5q9gCcbyCKHIllUlt5f64lIW5m69VCtO8ZDhtqUoYNjUBVxtRKmtLKmtCvS
SQG5KOOUujqM9GmST+esE0hJJXpJkEpBPsx2YY7LkZ1yfT8W9FfN/Ll1u15YpFQ/
trpmFTKbbrxAbNYSD65Ig8L7t9q3Er9D9qM=
=9RO2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e8b178df-b0cd-4736-a3df-ccd753cd7531',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+JBpskBmc/Hdw+7ql95XStSe99JU2z62yJXLwdKN61zZa
jeMou2tPIDgQXjHIoN33kqhU2ZRauzeF/bIZDVv71BpNGmXyqrldfKXgDK/W6Ybo
ZR8q5znGOulMb7anhygBNOB7pq2QD0w8u4+LQbIp4pGhxoALER75lVF07i3YBgFk
qB2tlS76P5AvcfMV3gQdthci1p38PtSD0nWeH0QWWi3v2m9DSvOKlVyamzpO2m4e
E4UEOKft3PiUOrzQw+mVuwB55b04+wIi3/mH9hcyzf8f3XHtSNp8xDFRiJPAWf7J
PFhslA6qv+pCI3yXokb2D7mUSlcbhTg2OE5UWQCzR2nTlRMjdBRuyUwncb271En7
laurZ/dArfARr0yo8sxvYdNXMcUqkqWGJUmi1cewcm7LWQ9HS2+fzdDIOgKRJOIY
DJB6lEZcFvUZI7uvoR8gHRJMO1ZpCMDNcFZ2bxIJJbsA+cpXU78koUGuh4x9/9Yc
yCY5VQzWRe4xBn/uR7aXXg2Q8AEvFhWvBTwQVc7X/MHLkG2UL8WZg0lTkEV/ssOR
Ztf6BB51uuNvMBeNpSmo6KiaZWdze9MuuZAsw4rr92VKnq1HTs6CyAT5zQrMtQen
7oNx22KXoU4p9qDYIFekbab8aNdTIo8LscfLlyhHPPv76EUPLkCQSUH0bDfEAl/S
PgG275i58r5AZvEfRD453G+9aqsgh+zke5xiXp9aZPKEkBqjlZQ+oC/8xXP1bWZu
HRRofCmFjyjDsLQM5FNy
=6fYQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ed8d939d-383e-4d42-a372-9872569c777e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9EkjiQ1notb0SeW27Tz/CoUFjDufkux30wbTTpd//LktF
CIGt/LwLnasiwi2MfmLvlUKT3RTfxe+GvfNJLM/YcwNUczlJNcf8Dv0vPDrsDHDf
vvsFAY3ljt41fl8RfUstBQp3jEcOKXRkeTHHBstZhovRfnaDfB7vDwx51bCzi56h
O9Jh08CS12owuhb+MiRmJLnXiWbPgss4omBoczvBCHcSqsRg3eMQ2D9a9fUca4VP
kyHRjzGmY8ke1dYzjcvMPtMQrHN0kta4pAGO0zqSGNoW+FhHzkc6Xj4MsB3jl0a8
fFSAeBTpANvbtv9zQhzipeKqvFrtA4JjhI3JpQfpg6RA42FP/vIGX/CySV5PyJsn
t0Js9XqzSZOBF07s60ccrLx1LcV0Pv+zzdM0GkqFyUyHe5zdWE82jTA6fGl3MPUf
qUa7Zq8CDwJBlD0+1HM7PpBCg6RfQt2LhDLlvw5YUCRK9VCkwryU5WVXAvRcUd8M
k8HA9z4zUq4QtCFbXi3sCI96zvSgkSqTdfeRCDA4dMRXjzkiBqHQUtjidXZNekCS
gXog6LjxhBLCYPdSdFRM09yKHkI4DuxmNl2Gw7QteFXWJfLhoiMSt1GEEXajItH4
MMVuH6xU3YenQkrYOyhIKPOTfwndlGhk401oMaFArYAs5l8obMUtxEzjVagA+jLS
QAHm3XwDdjXtjhgO2OP3pnDwEn/3JWt2xozS7xi5idsxyt/mLj+l3EnFaY3z7Zv6
ga2rz2/2ciK3yNR9hrsYVo4=
=mNu/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f3a1979b-8912-4082-ad1f-7241c800523b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAgbl3LBfWkBOb8b5F6pM6m1immoBgJB3F5ZS1a+bN7CgH
/jJJ/QOi0FLJmR3MxmkN3vMWn92+zMr4qhSutOLR5lAzjRHH0RVBYPJL50zVn71M
IhX6HF1Jub5rAI6fqicQqmiESZDQxz4MVgNoDLnP6XGtGFRhD6EhOqJKCKr7Df7+
HGkVKPUNkY8t7c5GbEliLvzdlNUm521MFM3muCzL+3nQ3yCt1OZqhMG7k9i2mOQ5
KxsCmQGSr/z+GGbkL+GiNrMnczgWulNt0l3zRBeI8ITQsHErXGyU7Yz1NeIWqATD
0viZdmTpqnQiglrburV7xsISRCThd1btaSNmbNmgHxbl0tCcPn663n0RfTeuh8nL
/9npEHCBQ/pAQUowUaL/UAf5ixPhKd77zX85Y+acrDmssMJUMQbSlZKzs6NsfHWo
B06US88Df3pZkpIMI0+JJrtZFxEEaxkxZ9+kxWnc/EfGZI9BPM99JmEmeZGqJxXq
9pYJIhMbay6hRLlp6R0CyrmgKEw0FNv4gl1DFhkKh7H8wLwvIjv7PCPCAL5O0W+n
yqdiUYnZLaWMp14nSl7JYRnq/ykQk8PMxi8x859fxu4xwcE5ZyUYKvp82sx5/Ol/
Qkpzk+T8Rw0538vp2USJ3HMmb6iNI4kpA2Z1jTffzLDSh1jD9TLLZLKZLAKhjo7S
QAF/AbWKkrGFl6im3c2Ox67lDU0M+mIuBcCyE6/qHO5B9pYeXgx7bbC68b45hisx
IJJpmhptJIjCHzEAywMh1lk=
=jklp
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f4134465-8714-45b8-a0de-34da9c267dab',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAArPROjmnxP8CLURgkKgl2togYgJjXD/XqDtrBVKNNSjP4
fx/zNR3E9BEe3pa2KdH5e8oi+nQbG8wkkXxQ9huIOekF8mU6HUaCAivULks2z5Pi
8VaUQvDA37dKYG/lAKWO2AUSiAAABrLookk7qgxnlScCGVYom2/3wTQ/lX9XuSNV
7xvB1kZWmKWUtRMJMrYENEoJZeHqirL9XAeIgCLuyvNxe7Wg9CKPx3JEpA9pBhoW
jZ5WmkLc4+OFBaGbx5znrrMdVf5Rn43sfheVnJAc8yCm0dbzQ9GD1GyTgBDkMyae
8/708cFYbN6esOg/LWVSUHm2K8DDZQ1b7LKlal7BpfOlDGIKgXPhxVpTmhCdjERn
KvS+m8i09htcsxVbVBIqGPynadMEYYFZ4Mmir8gw4h3deirqq47KytU4d1nmbO8Q
vcBww03TxF62qIIQQYwMbRP1RDexZqJlzQknLor/Ztk3KOsvF1tyD0DhYeuMbK2W
q3e1l6dDMCf4FLqSi2O+NwHTOJLs9lT56/O42sCuuOALk2kgv28AKRTxPCABD/Bx
obEbFIbS91pemV1P44WuYpy897qOtdCsfbmgiEGTr/BooGBsqRWENxoub9AzrM7r
kRqUG/cDx/dASVop8NMpw2I9Gpw5Vy5fBRyLQk6xKAEmJE/Gy4lCdkVelGLqLx7S
RQE0LIxIIFniK4sUXRQkESNN/7U77w7tnLI2Nkpm6S+S4eG9ea8DSYXnwSyLUp5L
2Cq2LW7GRiVhMAoboTI/XhDUXEpLsQ==
=NeXv
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f809b15c-f175-4036-ab75-548d35829478',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//X6eaNgpKRUVYRra0O+teWuI7+UT9bl24cISgcn7InJEH
Df53LK1e0+kNtaMFSjLb7uZHJV2jLqz224ixSs5QzVZwWseNhsq5RgN6at1lZH0M
EIBkrUyeWbOGWCdapDh/njA/RmbuK/vGaOX7rj/aE5Tsckkf99m2DeJf4+5GwAmB
xzFdC/NwneQOuniyEElhCWABaZJEy1RzX01ISTNvVuf6RC4lId5nzX0XteIP6Y9K
FscamxcbPJfmCyhWLWQaHokCA4eNAb+erBUevcn3QyKL8Lh9Eq3mb32xQ0NxCOsk
MXeMFhgS61K5zdDzITBvmij66s6buXzLh0/scNR99urySuObKenfNeKCteN7quUx
J0NN1ydfuouCj9dyTCAmgkEvhnS5awfEfaLkcdKJszhVxsZX6CoEI5QKaaxGiojU
AbZHjJr90DXbEDWOZvCO8hgy+zc996h2ds7HFfxAt4PvRmGyw78ZlJdhjYQ5BV0p
Lre+HQT5Vjmjd3BXau+dp6t+Pu6LeKz4BGBqNCyUzha73YHkXNljNHXzRiZwwerw
S7ffx0sKBDZaGx93+yAgfQLef8BZ2OZTbrFXAKNSQoQc65IvH9lNlu6v1JqjgHt6
RgEYD1KoFyqDVvJvg8IfTJ29TEsEDX+D9FDCUbpajphnPEUrHHKC8mB5utdC58zS
PwEJ7+YjZ0OAHoLzSJvrS0X7bw2M+vT08t82fFH/6kH+v95jnrsQEglpvQciQMhY
iNQXsf000YsfGSQL5bO7aA==
=C+iF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f8d5c2d2-ad8b-4d91-ac49-edb29f36c550',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//RCaDVlOUzbIuJqGTazT2njl9dz0rNi3Pwsm5FVgtLgPf
lZkxFgi/IZR8utkd0vgUC7vbzqFcOHCPqkT4B8u6AuUZcyneULtz1hD9YvT6R7Te
JO/hZ+mCgIz3yfI6/dnxrZ/zkK6ceLIsaq3oj7glB7KTLrDkuI8PdmjF3IgjYlOC
S5BghpoNd2vU8qOb4JOBjVOFpob3tC/vTAEW3zaPUvP0r4xJEBxYAebTla2D+L0L
ZoS41/5iO6OXl9IYw/4kfIHgW78KpO+xngLPjt7Vjf0QgY9yR1DFiO6XSLeQU01M
EUwXiSZUNoKRkQriLjZDvJmGFJEXMs+CdjSsT/Q3k/izCC9tQZygSW/1UnAqaqBQ
8uPZwHCs+yXukhhSToC5WKRDXoLJm9gW84WeokrcJcTu10TVXG3+uQi88CQKWSiP
TGqVl3Afbck2ot3EtDN0sT2F9C8C2lfzoXH3iBxcqwb0ujSyoE9UgvkMcQpheJrO
Fk6oDJ9fx2h5hoyeoHgFthY9Gfn96jYVOI7cXd6VdMBkZMyiOxnGm0WmbqvYcn0O
Trm3t3m94B4P8bgCFeAp6UXzhkDUGz8EhaDLci9Zh7A7JM2P+y0+3QD0aKXTkWAt
yrwpy68iSrklJ7BQ1O4gkrOEjqO9fqW6b2kRQBj8vMs1GPpr3wrmCsUGL3Pg+3vS
PwEE2gxDfH3ooA92NHBSpHomYzruPMjYQGUUmJTjXfsK6yveLn4bQNoqVFTQhSu5
lFcEWrQHbDTJq49uKl/Bpg==
=iWe3
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fae3a8c9-b062-4938-af16-3569f1661ade',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAnHpSKmGn3XptEM/8UK8/a9WdJz4f8CcGo8E0Vb5jiNJO
Fof4jwBA8Zh4C0SYKdIpJDE4u4tXk/b+kLxp2c1z+T7c2jKWC4mATA6UD8qW2rxr
QcvmSpl3qejuGPc9e7tOfjngut1Vw6yHYop6XBbRlEn8jRkP3M/Mibxt+4SytbSI
s+XJO4fqcixALzK2J1LYxCje3Muo8k045OIMErw1dby0z9eEztzaPJpi++WbcO0A
OCrNGmNWV3fu/ztQiD/1CwDYsXBahWGyVaPiwD4rfNIPmLn1/JTi1oydyJeD06Bz
0wW/KUBD72fFTEEUXMvjiKpYrbT2pK4TDtTXKRTTTcX4AXUPFJjw9ysYIAyg8745
Np/Pjbu2YaRz/v5ZvfD+6d5O/w3+QXViuJmGgXc23nYXlmv08mgYRDCSOG4Q/8U+
SKPHflyaFXfVCaoxvosAtBxIv7mWWDcWRwaPgWo+wqgtVJ+dAjYeKCPajjaI3LXU
dXqdS5gXxAd/O5d99wrAFRsM4hbmxvupDn4lxJqFMk2fGORPn4wiZRhJtGYSdfH9
JigRB1Skrke60FR5NcxWV/yOoL7KUT/DCBnHW8ZuyfS00J11RKF17wrg7EU1lnNQ
JtGUhxbIVpszatuSpAWnUs3ZmlM9YKqvGGPnaq6jtMxNFTFREenr1XI49t6isWzS
QgGVaJwtc06obVcpRTLqT34Rqir648trg3mEuC5T/4Ul8T7KcmhX0PrMHhmY8uq+
5xaIL2GYE6z/yHkVUwuIBenb5A==
=IMvj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-02 08:59:46',
			'modified' => '2017-02-02 08:59:46',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
