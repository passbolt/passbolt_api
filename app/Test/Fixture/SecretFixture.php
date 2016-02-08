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
			'id' => '019090a5-760d-4464-ac0b-193c8ad4cd02',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgApDPxqvjKW/yGRsOtL0uPyXrJDrdTbRIKETVA0Oxv765w
NlxKLCa5fwdEkBioa0E/8WcNyCa4CQu1UQlSifXIM97/MISQT8DlG2TujEQyTPw5
El2AcsZsb7UEOolV98STIgmRGru6KT474jATHJe3KqCTdOfozDgZGsx3di/4MlyZ
pnjxahS7LOzaBPmD+jLM2mPLXog8T90zuUh1p7B+J8aooH+RHwtjHPz7gkHTbt8C
bTVs6gMZbwHOos2sKfsZO424m+x83TCXTmClnIMweeE5aCcPkhTWxvaGc77lbgf+
sVO5jeyrcZPhoIckKo2uX412tMWtyUu47+CrhOCZ1tJAAfRAxTlajYUFY2DDnqIZ
O+Hgl7bNU8sVkW97dPp2t/c3GmbN1EiKBWwHPJZbQHyVh/oKkNNV8n8JtYSfn/Ti
/w==
=ZCE1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '058f03d1-8e6e-4eea-a413-f1901bad87a6',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//WyHHm+XeMRbYHwaDrvfWkElDWNp3R+XTKVjzoJvR8Ure
Lc2b4SPbRnswesfw1vD9+lAkTXbvYAlUNbjNMQALRtUon5G+2pRnK3F3UswaqG1G
giLWtpCE6+CsUy7UubyuUnuPDTgvklx+t5tANrvgWnoxQ13y3nH6cm7Uf5ezHa0U
4+xH1dTWfZN3GqfqMCDy3rklBhAMBrGxhSUqVHNQ2K/iCz0fG3XyD8ECbNEwPTFo
U2rwoIiiq74NqWNVJZo9KBeV+Y5vvmGfnF4D4p09EXWSC2DUGbaGy+n8sB5hH4ef
Q8w1ffoNS0fKxWN35zb/9Mu/tDGJ0jXDYElrKr4pivuUnh9aj10iSy8+ZLW4a9Ry
3yrMMjbEtbIx1tA+8p0BqTpEmFWF7fukwFszBq6+V2WEYJh+5iVx97wBOI/wiCg2
PtnEgbVySwBBiQvo7ntU192RN9COeKS3K1nWUC0jiHRvPALThOrBkM4VNOF8R+Kv
6gb5yQYHxTpB6n4m21M6ACv1KBEXP7dSGsW9J5txhU25sTvPusSqdYSJ+2uVgIo6
pHvVw1qB82MLxVXulLqS00cjEaCBQwWdM8DZdb1/ned4NFecP2V2iQQabF4U7aGQ
Mmcn0qJcipLtF4GwtPMOP8c7xFbgWoXuFMyNBRdor+BG5el+vKwLXZYdk+QlEwbS
QQGKYVo/jaNHdH3idiz9JrjmNuoi4zlgzCWNSnXJUMx2atiYuf9QKk9AwQHpGp39
RRYnr0T26jgBQ2JU+FEbhfHM
=crtz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0827d21e-6fdc-4504-a855-306571391cb3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAupeJ5V+avIi0/7Td5LuELvf0KeCHxSAM7Grz1S2Jrymn
0CWcvdQWR28D5E/Yr5G/CNXd/aFLXhLLD2T11dbL0bZSNbDykIANk73bM/0VS0zU
BYTUekW84Ek0FFIyotI1X5esTlHDtZmeSRB2WPeR6EC18zXbMcvVzOZZCcGDhniu
CatorKspQQRDC/rPgZLokGu1pPNAf0QD6uMb+QE2VQk+iKpWv05WjFMc8+IaQIKE
j0q7okxOMkI1v3wbMYcFSw89TcaksuH1EdFlnpI6+VwYNdgfr9dTClZN0sq08pSY
OfKbyB4EJcTKwAGmdbOTm6v3rQcsIv6PixnedlypLiYHzYc3rDU/Na4n+NpnYCJm
nY5nWskq11rE3hJMnDo5FV8vjssPr/6nXeF3kq/xQc72zMB4b7DwPuchh7C8Blou
ZKsAslW0hd/b1nqBc8Vg95s4D7PoPYZTPeNEtV7kifvkpdpyYK9FVQGy+MYXNIoY
hVLTqqeXWPxuXFkspJVdDpTiF/23eYuiTVnvLOGdKq5dE3kS8z8gD92C/wFYfv6g
yU2ynbm7JH8QyX/zfciNBC1JIxAt+F2Xw3k5lGcH7kXGQ5qy3b2W+LOOOYF3l6np
BsZCaiO+Hdsxmt2tZCLGu6XLf4Bf/q/QhKcgSPi8mdmcZxOvWLEeT/6s0ccCw5XS
QQF4thBSFhqUowgdVnNU5RwyvfGzsZm9fyj9uFtmM4wwGoEGxVSlNJ6EZbDGhFXJ
kFLLtyxD6uwrsK4uZgw/Crfx
=nW11
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0ac6418e-a40f-478a-a5bf-adfdaffd9e16',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAncr9tPMtP1Knq+mLai3svWbBZAesdu7pTG8wmeWRNbWM
Pi6tllalM8pNIqaKTddvD9GYL/q0QM2yrG9LRYSWbzu0UrVxgdovLBtFhOeVkka6
6UNWyF74Jh+vWEZxuR0Tgdz8kM+bzK9+mnZSvHhJUZwG4TD+6Z3rY/E4jeClwyao
dEP3Nsihxbq3D0pEny/kINcwAVNOI09OojeAkpUpP3gXovtV0menrxp5eLU0qaz4
g5m8sGraXXmGtnADZLiS7e4UcVrmE4dw0Bvr3g6OXfQ/uNKdV0kZYmlXLRSA5WdK
ajQEhCriFR8TktJpzX3Rz1OWwwpiJs+80AkJxhWa1vvkWJkdvU1OcZh3OU/LmqG+
DXUEyxboAh35jLutpz6WE5MD9WUkP813ZH5dh64D5uIsHx3QCMyp2tkPHwyqlDCH
RDO+TAFAIrfFBJxIiLy7N3ZU6V8BHrTvtGwLd5pNlhxhQXhsjhNZiMm2mVQ1Pk99
17EaQr+8QGXb9Cp8pv0Mh1lwIcBTVWmYhdwQ0ihBckXo4Yr/PotfRzcteM9YaiLH
fxW9/tM2RxTXd8iVjP0pRwQsw7ZTL9so8/T+v2mIsB0KbP4pCgqKvhMWpr60xY1l
eWS+sk37GXY3oZJw4hwyyWqgyYKD2P2G42YynUeXnjwo931OMEltHTWCxA5/lpvS
QAHRAVBxRj4P6YIeiuj69YZSQ4k9FBUMDwB89ywVeHkhpn5hsy4FRmaXkdxzhXA3
7NKQlhK/36CpyESbAXRotH0=
=7AH3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0ee4689d-680c-42b8-a1e3-52c6450778f5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvyp+oXLBhZB6lhAAVC0NmEq2tc1UDdw6EjpmQgJQG52B
L9im7E5U2iUnKHUb7R0Z/siuJ0dx9Fb9p/kYwrgyQO4ZO4sAaBx/XdiUgDNv2vZh
jmD6hNpleOp4O2DppusR8WZZINr5TkmuLn2SWk5nY5G2wtKi22IMgd5la2ittoCR
tbCl5GiFN7EQeZTHs3TlQ2NrI3qBfFW1HLu0hZMgQPweBo2riBL2lAC5I2UCm84u
8Qn/4DFW1a87gIiUd2p12rglgy4rJHu6tkj0PvnN3rwMLdX3Q5rf++ffugsmoDNj
2iMvJ6rECXltV+Pfw9V4S6cRrugAFFr3DbSyfDgVGpY3yTvUllYEdc/0YEBXgIt3
DxWe1/E2nMwRJ7y6pP8t3EOSU66ehpEp2dAeVjIN3OoVdGePs2xxDdykm7fxU7Hf
QMISzXjvZkx8mQwF/xBrextpPavybwHu2IkyUe2AaNUt1VotElHRNr+Nw+w72qiZ
fU17cqY7gEsXXojIcyRoOQzORxCqr4Gxc4VG7yDQPNN+BgxQmzlCUtbdyJ7yngep
nN2Vc08YyVzwGKIIKliN9f6XYHu1mKtihiDAO7aUAufzGgNxY7A8cXAnBWZ/LYQF
GSBmmRrQKjyHnBg/eQC7EC5VpQTrLsXJsl+Y/zLQ8rP9PvT3AsPE/JYExFoLHr7S
QgEhcK9gcj9EcM+pIsmoxnYEvt3d/YGymXZEa8/x9Xce0eLTFjBWrAFO5tpAR9TA
szp0FZFVTn1bGILuPaJ3GRGmIQ==
=KbWa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0f231348-aa85-4280-a80d-92fa6133d43b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XWUzanrAb7Ncc6ZF/vx9lcwXdQjpyJ4QNtJdbxRSo+uK
TmVxdt49J4nOCCfde9/wDvB7KzQiYRi8iYbjsBpcFzUOjV0HMHWEAjmPXDwBPJDV
ir3DRZEjg/SG3voe6vsrZuDCfoGUs+odhPoQfMGhZASelg/O3TwHwTATxqX9ydfy
GG4N2QREg0dT64tAjsj1oqEDyW7JQLUgk2sShzrxnVLL5PYx+iDXiVyxBqXcDkq2
6Q158DLjdXFLPkyEpwzAUamKVjDda9wAxdwcfK0uHsS0wy4TMHKBcS5L5/9K/dLc
ZcXQHULHbbMKq91htrJcckRyFMRM1sJX5wt4TMgsMcWV8ML1jm86ldi+LmP9Hwdm
2l/zKPMIHmjSMS0v5DnH+Q7KXg3eSQK3UsmE3y4gtLzqrd+swJMQ4gR+Tmn1B+DL
d5awuvzsy6iv6tTFfBpptDUqlhL+ad5sP2jfcS/RL5Bcfd+TZf23t1xDbQRq3Fo6
e6z3utZwuO/oPh/aNgW2pEbZKYmgu0qETB7iXPs9MUn2GquKqXiZI84nvg19kAg8
W3MS3zlAjNYOW0m9KN6S8VhCu/ngDOFG/wyLt17gKAxCrlD8yH3axXh62aUurXix
n6Rz/dcHVzN8lXqnlLHbsfvHsgPX04GBpa0CGvQDqdmbrD4/489QETJbLGQCl97S
QwFfMVqmjjIG8q43T4WgrD93tRaRuv4E5phv63CrL144RoiKmj2ItyzAtakjWNyt
B1yY9iT/lLLZ/oHPtMWsOsP4oDw=
=c68e
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '18c723f9-e132-4e23-a48a-c361896879a3',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+JdqAaxszefL2Z3+45fDmsbZ66/N1lJTa7B22MHE8739M
YmTqrFteG70HI8S7bGjRYM/2cPdACdaOdmwk6F7GVuMMRw8YLUYBgUmtSdpMcEKz
b+8KfBlle2T4O67hj0iCIctuS+lYupSTvYo/KVDKvvf3cioCNdR/YRuTH+GZYl57
vj1FVGwy3++8gHdtNxyv2cZ08VzN1TiOZWHgbI2XqBiG1Gy9CLCeDyhFSmH4XWeu
4rr4vk8RLpevnlMwsj7xy/M1hd1wlwTl2cGj/iNIzrv6OTxY4qg2Z/jIpBS5c21a
dGw+dF5iZGIbEWDLUyQ3Munh4T/Ac0VWAwlfCdU/KdJBAZXAnZPoPpvkTk1D4A56
uDXweZDICyZjmppDDhIx4h4dbyfupbY/q1tWCmdR2aaX8f3ac85OiN/M/76Qm+dW
gM8=
=svE0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '25601f38-ab4a-4087-a6e7-c71cd32d4070',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAg9ltuN+9dFtDqXTfHYs/thx2ERXGX0eD5/ri766xedkR
6g3YISjMOl6ItFa827sLkeB2S6G5fvUr9aaz+DzTnzDCAx3VOqXzYKyS+cUwTvUu
gAyWLftm8U2gtoMj8/dk1ONNwSVvitrnjj2gCuFMzKBewhwaVJkqniwWEZc4jfn3
kbYB0i5hA4ianK4tP4rHw+Lfj+qLz1oIGSZMAMjV9RpUBvFX1aCGXcuAkq8CEsOG
7cPILLO78hbzNNvER3F0RI4VA8kVz9ltSWfKOVU9jwFrhOLT8077A7hWW0B/srqM
0y2EAG3UleoBVzICrX8RELZugUoxSHtg0EB3Hf6S82UbrOELbiCa4D7MICdU9N37
8nJotQdjzGaYtr6DhEQKDPEbpgP1tUERIK3Q9wcWMBIzjfKf0Jt9jng+Iauv8DbU
MFwzVKkne+8gb8hkWxr3QrAeSbDD43sCTE1mvNzPAhCGYwT75bFG4J6yzruvD0Tr
psctc1dYXb/NrxNH6IaGiGZrJyPHB6l4wiTQbi/FQdMMIouOCfcz0/c7Ap1dM2vY
A0KtcUMWq4Qi4b299fqxtYAnNOmg3aSt2TrZpRoKz22K3lOvcjUePpNczQ7TET4T
yj+F4N9Dk8h1qBuRNZj5jBbpLUrxeZCtkJxuwT2XhBXtbMLFI12t6tyqeOyaNwDS
QAHGpNnhI8dpnJ8VhuO4NsX6vbtYK2CUTxVrSOfTlH52ZmygRaA/Zkc+9vOylRxQ
GZFa2uNS9yqHIhb/uGnSOfg=
=pyzw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '276b53c2-4ed3-40ea-aa29-376e796fced7',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8Cfzk6993Oura5DZHPztwvnhV0PevRRVkqBPIoMsooO/y
jzHucBOBMv7GPP6ypopM4ZIYZZOCOnLol9uJAvv/yuytkSFVunhlip1P8LMw5cwq
hrzVupijApd5Xummwf92qt+OEAuxTKorajX7nDU4bu0ZAacnrmJ79REUY/2Ub3ke
GFaHN/TQuKIIn2b8eeXNudtXgXaBcOl1LbPuug2v2yTKYuN9VmBBfvuRblOugZiP
/pu90vcCcUgPtPDGgCNQ3mElfSF5hZ+LjjhlK/GFQf1MP2fhJ0ZOfTP0e8+Ranh6
UMciXS1/gBlxDm3YA6ptVqaOZS7lk9MQ0xWZpvqUqjk8Cq9qj2gJvHAvYoens6uT
na3wzxHelG0kOo7wrb6B5xhmBA0aZ4HbGqAZADnRfa/JjKtiCD7ClVqbLZlvubMZ
WJ/M318G9E4CaS6AqWMoHoQZOfmTiFUFXmQ1EZyKwDQo1g/jd0JUJVLCzvgBDPO3
JNv9wfb2YJd9MErZypyzMJLj/aSHuH4RyjySqxdVIbXHdTEV/E957L2vJXf2ligC
jfii9XZrX+1ELb4RNYLwco0/0AE3zkqVfrZwXHPiso8MquHDbgeMvF/LlI8ijVPG
8jn/AO31V6WCwx0eR8J9RfrAZ1gbndUCt26+DH86iGRFMt0CHadTe61jmqw1NaTS
QQHXac2m3e+1Aa2e25szLD3IO1NSd2VW6JW0Ag5HhcKPJeFI5jGcyTsFlRbTa6FZ
+jXjdFFREol0NU5x9YylrA8j
=CCF8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2a5d799a-a293-4492-afee-db0f2c596d5e',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAng2a45Ytgx0+yZT2K9yvfBIXmJ+EoBx2cF/D/Debeb0C
e52rXQwj8yI6gamK6WAqPc5A3xm0PknH5jrYu+rNhZkAZ47344LYWUdCPqJ0PQn0
JrwUlBf1yys3k1VeIHvPt6iUajFwLQNsk6Pgjzr/Moy54Tkm13drpJ5Si+cOqKL0
qvEnJ7AjVX/lMRxqvZ+1ncSnMjCGBwsAZi/Pufq7nOh7lpkyrM9Auif2BEA9YJZ+
xQNOESUilVXChR5YnsqkPSuoSutTI0P31wxQjy1CgD4Ea/mzNSbqzHrRbPcde6RH
ca77wz3Fb/93Ddkbf5BYvE9H0j9wYmsm06BRrHTqarKpVyEXP0D6HH1Rp3jMHJQ1
3nIFKBheIRGuRpkKh8/FUVwn+zof43DS1pNXimFtuamX77P08RieWKdC4IUCwa6G
VPhbwOu5bwgOmIxUjr/VyXOFQvXBitJKkTKjAnqTY9u7qWOMi5FtDbzhimePj0CD
2EbtSXnUB7xX7T4EH3fe8J7n/k36tgQQE+Ojb/qkAV9cZSPpf/ZmL2cacYlIH8Tn
UaUaElmPF4ThrrSltoIp4NR2Ti5751E9re7jIX8BdDdwxqCN5uSHQddbE0Ddk3xe
Iv13KVO/Gc6EwO/c3+sojuqevtjISQnRxtWK2BD6ZyOC77Y4ySNrimhLqdHulwfS
QAGoRBol7/Ktzbf/PBLVTx9gcaDue/RPHkrAy96VF1OPPgAH9zrjIH/OnFtKV62a
dV5jTky+2AKXSbUY/zDVTeo=
=kufd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2c91b721-1a7f-48b4-a8e6-9dc5618642b0',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAtiI2N7z7FD5mBHPFhlJTkMZ8z7iq4d9Kxp0O4MN4RSLI
Les0haWtaQkNGdnm06bwzaGK48UC8IkNOYcT5EC/CPuo6esnucmqV5Z8iTSdADk9
VytTcTYayZeJe5HkJqU65phjrLPeHPaxN7iVTpSogxBIiBn2MyA4oHnQe57VXAGn
M5PHWFPcyR2rvla6z0cpqQKqV/07Vgw1LOytMxU1rRI3k5PXiyF7EV7ZBZHNBsRC
rLmPL4583TPOR7nIuFXSxnAHnZnyRHL9Vylq3oYEvydFccRUGJZ3UatQbtX2aK43
qakPQrPs18WbLxC3nHomgur3UNE97mH4kFWl3dBuQtJEAX28kT37H4V94AdPxgHD
2D0V9Jj2US45emkeAJAPFQvLRBWepH7GiS/DGEXe9xQALOuv6RwObzGXDhKmQDLF
JL9E37o=
=fCvV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2f6d1151-a896-4a65-a94e-01ff8200df03',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAArIkHXtlZMS+MEwBATEQN+7i/3//bXh7EjRKufYJOOZpW
yJsTW+1cXnTR8lwYA5oh94lxDuYetdsdNHfejKRM6sbMPFE/70F7iDI5NaOKwmTM
dpbOkxDzTvhDPq7h4wzi1itzxyPDMIVZoI5OnSilWjiiS8RIx++k57Hwy7Kf5np9
wUXGmhYBXQLz9uieFCIChmile3VHYGwiq4UiFtaUXaqpfN7LlRiRgSec0mHHn842
qVeWqRxbFKZ3NXhfD4IlHr2Yg/4eE1qmUMLxCjaL0gFgVfSjhItVcKTFaIg4hACr
jNU53rQ3PdIgd9Fgvrr9ZNfIgq+5IyiwDLSpxwGD32MirApQw09Ko2aQRKWowMYS
9Nq3N/f1Ja2RBZ6xFvjpObRi2GOEf7Qat5ll0M0w8+AEpAckD4mW0RDEiH0LH7Bi
lDRMJ66Z3qqC/yjhwVTE8Q5eOzuDRFoa8QSLxVkDSZo/nfiO0DQ+8b6NQPq2bfIA
HciyzGkPdvEnzQKbzijHJt3I50DjsiabJ8I8XUzK+3uxmwVwncR2FPujBFEk58Gn
4tJHJTJJnisz9MN5J1T6AQucGtPLnfiY7Gk1vTbqJUnLroiOQwUeyNncc0TFVKIw
ozJxdETBhVfPotCkrT71VbEeJsLeWBV8wuAEIrDxRrBCasOrX+maaFtElj7IjNXS
QwFkOkxaZOOSemMAOufReo6k2jVwpbUXqRVVGLVrmJ6yJLW2iZDKazdSAjFrRRhj
kIqWYzT0TYc2IpfXRauPdJ6+Jc8=
=Bi7Z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3193a662-aa9e-415c-abe0-d4ca7026863e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA0kz4PneobTzutQb8rgImfXY9f5EuY/3vLvoCgVY9D42A
SsyBc2qqmmMMUrnUIu8NFWPfJJ10CqGbhugwGs9V2yzB4P4oZECI4kZhMz3rMqmZ
1X2sfWotSNc1uKNpDI/REMZktuRbItpuEaEZ39oBLmoo3GvuyS1F8+CsSgvpsyaa
q9AA5w3DRqVfC4siA5zlT34pJPIP7fz1SFIj8GILTSzMCh4Yx1kTo6fO2YtEAE6o
CpvdVTEID9bGlgrDbcuhct11DIVHAZqxTX7Fg38LHFh6eCXonFO9mZaJ8rbzvXVo
xnnc7ZNAFLnI0T646iOtxDKsL4zp7ca54MCSEmL9x9EnPyzmAyMhTxl2A1o3j6Ho
VTXeN1/Tiqc5zLA50nfQHpo6FExyzaZS0b3qGvVsHxijTSoo+GZ9R93qYpLsBjSv
baUEyn2jvHZl/m6/umVX6jfcpwonucIcnKvIvE2Xtzbfk+XUZhiFiDMsn0IFnabj
mwhwJPlI6/58w3lQDBxW4loR1rZyfToaNsH9jZg8RZvf400QJ60nm+SMvljHZfjL
2Lp4ZXt1PSR4IBNIbXGzjtVKhG6eT6gIUy/WB3Ri3jd4JCZ/MXeDtSR+aMkPoW4Q
SlAtiFdqTnX0dmDASof6SOE0XFXGeoqb5ftbNT51jsnpR6tJuF1F198aF6U7I1PS
QgEbJ3U62eMq56x1fZj6wM1lQWSb0PHnTu6w2YUSjRNnTfPa7cQHJm6oqYtRAm2r
Bxz6kCwqxK1IA61LTTMXvNybaQ==
=tNOm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '36e42b14-299c-46b5-a45c-f55957955076',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//YWrZtiRjPwwDLP4R5KKVZwLvLoG04BiWTkEFNuEu1HKl
UeX626VbhijsFiCTZgNjs0ZpEVePQ2e12py12kt4G90IX856L0KiE5ZMBavYRvbQ
tI0Cjgt5o2rO5bA7h6jqXXupX3EDOQFD44i9OHSQKrS7zU3RJmHXfHufCWgfX6pD
Hxjsu6fPuo3gVLng8gNdxOLhSNWh1rcWw+821ZPuA/reQ8s0MGzoc33xoaJtRe+N
3JLUhTNFGOnBlTxyvSdrLod3qemQ9bxHl5y1VyiFirGGBsQUWtZsSEmrJhhyXidL
9G+5Fm5JHe86yIDOO9UuWLVIdgDEkWZ5RQ12fyZLN7DeykJr5GXhbAFxYRCgsG8F
jCuu5kOhIxeapzJydED7HDPCOHnUSPktzEK49cYz/nLKXcSvFX6VPOa33I1iAKIN
EVlTrQZ9ciNo5F2UOCltui5Fe60gocinI+o6CEO/CKzVkKaFb6PNGrdduQkbs/ty
Qah3COfPZF/cIomrJIL1xDicpSwFtoa0r+j778quMQKmjG13d9aaCo23XqDplaS7
Ouocn78AtHTmjwacpevs8soRFQMwQ7D0ch2TqoQKxmB0h8xPeoANLIvdp+WOYq1v
kxeAtczMulqFkKxU8wxh/dXnMds5T8u9ALQw9FA4GDrEb4kSnLhoSXCGoWfUFizS
QAEA7RkPu3GqgqUiNvYuJ7DKi1Sd7/l+IEgbreddfq3teat3+Sr9FCnicsoM2edY
S2pzNNavm0z4OPl4Gsoy0GE=
=qTCd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '37706762-0762-45f2-aebb-f93803f7ded6',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+MGbXJUW/SeyMuFCOrtF2enw2TZ80bnSSmaDl82JwmBj1
kowPT49a1ntLNpPqu05XX9lPeEBT5NhFL5QY9d0h8EoKyAX9rh/KAOI45f8ItcMy
Bz3O0bCJXkfTXo7n5evhdsTZBtlQEMYRT+ct+WuNYnVtooNqUX6Yx0b55nu+y8GP
Vjh3f+GrijnJxpvvb2WyHsMDpLcgHbmh3FLnUIXVKR9GjpReBGUbyzivPSMmDCZ7
9dnxsim27ySYNrRqlN77iUiK1xKh80TECWMR4gO9f6TS5lB/6Eq4Uc8EfYVpgj74
0SfDOET6Mk4yzXUZtXRJTmXu5X+swQ/s5NWOhijF7FDs99b7gfXeVJ5AhXQ29vyr
+LSpWu3MCuDzmUitR9VrHAv/mcM3QyiDPoEJzNeFaxC9uolvLkyNEZ1Mrzdhp2uJ
/PKZCbe697gz+m+Rq0M+PzS8aZ+S8LCO3bOYTVLi0MX32OldBnAjOtT1VM0jByrO
INipt42Qculoixnw7PexwqS5uX1wyoOl51+McxeF8ejm5Pp19yCQLEQplTKduCs7
zi2Tsd2vX0njRLXyDsy5Sqvz3rkdWBn5a8kBjE2B6ZUHTsb80n9Y33SIgLSmCzBe
/32BORQpanicR3rnEz7f/Iy/NiikOyggtiPmCfHEpRBryPh+0AlRPYd17GXsaMDS
QQFgjnSng3/2frkBjNKF7xQmle8D8Rc97lZa0ufVp0jBio9PbXNf3B7CvqRldZQv
xISW6WRGrBspZwDJiYtCGAef
=ENoZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '42353d67-9ac2-46cb-a280-76001469433b',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//Tw6DlxZPAiGKCaKzjiyinnkvZsnjWgrGEgQ7wJ43dGRW
E9f4aAu9vFCZtj74GHKTE26ZmsKwZTJqgUfISfghCCUAmhiDot0yOWmHLx3bYkvp
Ua0ny+4eU6qDhu6oczqImbecp+cW7Zt5POB2cHW2yO/eTLInlZxIa54d7FOyUBSb
qjbGJ1AfXWzuoG7UN+MSewQNbC42erq+wiHQzgurq85iJM3UXWUaMDzoBm1H5GzF
8SH5v0wyxonh7IFuySZnJPGgSRVJRU4AlyjHLqjs1iHBE7leyysl8dTJdUMvomne
3DZWRJtVl21crl3Tuqycxf3DIRsgJ63JoUzqqSyotaIAaKpw3fXVzIGNsT8k2bjs
GRZf0bymO2vH5hYTCgPZAha4FfdPz/Z2+pPy/dCKV+JaeNZ/AoX1zhKw9ecVFa7K
MMDSQb6hwuizTBHF14BhX3YvSrfA47F1PdckfgCjbKy+X34RL2oBgl9Ggn/n02qC
wlhhI6CTuTUlc9eIZTgqWCeDOOqvUX5p4NyPDELhgtlHjijgaRZ+4IJ4GFM9yb8/
Oq2/kY59UIEIV8ict8hQvxq7kaZ8gje6pgc0sYrx1GFtz2a+ZBYlInzO5bz5fbNs
eYFtb7S7J+V1m9F/qTS+4Nh9HSIkY38KyR55JyDONxu1xBwLsuRhU7thmjtImQPS
RAGu7kDbZ1G66v5fVF4Rp6ET0avXx9mE/cntAs/eqKFk846wWmqmRCGNWNw8rA/K
Y7XXpdgNB02K2IKOSEKJ3Sk3lpxq
=pXaF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '450141f7-5c8d-4d34-ac5b-77e1a35c321b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA74ZRW2CuRO6hYzDLtJQPlJahnE5t/bZQPsKHxLkkkdt/
VGyBcasxlkVc51B3bOVwGcrKbInqQgY+RYOMsnSYc3bbyD11NOXqXoYEc/3a8i9M
KjNsXFdq66jCOTjPoEK/HAwpyaAxaPrf2p7gPq1zdKgppMHcAnWqoy7dmNOBWV8A
tKmk6Ss0Hba2BAtPpqX3JZiY4JQVnL5v/mnyrbwmXxik02nqqnRZCfwfAOT/3vld
8jhBmWtpk7Kt893m6+B/GZk3LGVzcVY/k7wG2lYkwupmWSKW5Vent6i7w67nDJZV
KI2Jx+J64gby051obLVGu9PQExUj2SuJP6KPDs1UfGs1Q/NBkqXtlPtRV1qcm5FB
NoeyT/mrWyE4cXOsfTDpxqTx22VPXl4qSynNxg63i6zV7exvDS6ZLh/x3G4YSpRC
+bltZuFtdle3GJTOUO0xbQc9ZjJ0j2rQ3JsPdJDkpOIBn2Q2pi4LlwZV5ksUnwFi
Q4fGB2jMiWgm6pt7gbVRP+Z6SrevTIGL3gwGO+tJqsX3gNdiF6zX0wCg0bqcbqHs
naQ1p2tXVF6ateZpuJBsjh9y66ajN5oNVKfZ+Y7ySSlkgUCQtPbOFBMve9MuFMnv
FxpoW5oW/KVO3CLsGUbPxGyvBlTlQSOQiuaGlqBVnC13z0CzZ0fI91SX8VxLgJbS
QQGB2n7cWVLZpqXaRQEfZtzKxznGTx4lO9YPE/8mYFobGPwp9WBghl4nXSH4wDU1
BITM++RLrTHewPqinlmk8o4w
=4Yo8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4a61a1b8-2d3b-48e5-a5b6-409475b4aab7',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+LONJKAN8VxvDyoOH6K0qLgZtiCAo8LSz9h5omWLNcKyt
TKam8qq8LeWCZOWwq3cQj7NYKQAvmz2tOOlz8YDJvN1F86kaLLKK+KKEcujyJZwz
ECdPjoOLRmM5wSXFQ0+O+o7j2KdS4mRe2gDw/DapkjJl0OSx7He0nnX4wlm99r/u
XD+AQREZ3kbBFFVZbB/anpcTSn71QOPclS9757fNw44JWumHaaSglu3PLVhCvLza
mHdTI6gVYEO2n6JDZWBuq0FrbiiO0QNdnEXejKvr8p7o4Y0Baf629R9qEa1Vm4Ho
V7uGE4KMYAnSYrt0CfkvdIk+zE1o28qfmni1IBpHyXLCZsyKgESzZ93H1a2ExXm8
wBFspIDPoYo1hSqqQ0SVaaIT2Uj/oZCvMi0edhc9oxZ3nUt5ig2z12l2rYbhtA65
HaKl22q+3wdsYZg//e66CbIKyA5VHEo/U0RQAefdBqTZyhsAnw5THz2uxdCzCKcc
dY05E0q5E2WFyXNNyb/rP7G+AXvr65uNQWTEGZWVcbtB/BrojeOFkpR1UAMS3W3s
8IdcswaEYW3UMaajYHQDOEGAIBCqhp7WkS0E3N7jzKx/QqS+htk4xa6+p6bEp7q7
mCYe19hi7nXiYo4gg7Aj/HhVWmNj9incFzeniKUkO7BsJxOXcCmDDLaC9oPJ6VfS
QQFBJQGrACwgf23dqmO2ahWwVaRN1cFFmxs05/0rw+4cIJ75VLPyF/0o8mJhMJsN
nh/SghxzqakIANO/Q/xVoay6
=NErk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4bbe0158-fa53-4494-a21a-7dcc8d292858',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAiDorLS5PMFwQclII5Vvg6y2Ypt4jpt5XPRQPSHFzINlL
ajlkZAoIE0AMGAPThDgoRKB2wMIdAGHWuCV91aaqi2leaTxyq+ENTxKsN39dwFU6
Y31HF8OCSKhgba/H2DWQBebbdXi3TtQBf/oaj3H6F1rqsDb64b2OUSavhehDKTXP
mQiV9rQ/gkZprshh6GlwdmTfBJtRnzRdxhiXG/pZCgIWI0QPRzI+oZXqtPMyjITP
x3PuH5Tjvl6GOPzeiur1SzJFzFoaN6mlKhuzsva8i6FHQDm3FyOebx59g1X1u+vB
M7GUn5B+uopaMwsy38ZQn62BInaNbZoMYo3llauS1K5KF4I+zjFHKy3LKoqJzGiW
yMpJF4QOLY1clSBOFSmq+s7nGrwqRdo3KBGSiwEE6yR1fmim83w6P2uudeKpCIRx
bmgUcR80ZRjMc5EqJAPv+0ZvCSw4cFy5o9ylIpCIWMJfaVZ1w7I8K2FpMSE/fKB+
0njt/dGEBciZy58yPS71gg/y6cP8ifWUlgcmfYiqNqxCD6Kyjmiw5pf34ZFBQhp+
gSxay8azJaOE2U6j3G6Ryf3h8tdvBwyDVCc3/cTUV04jxy6XBtT935IEgAGJ3wJ9
KF2VtUS0EBSpCtJaJI1hkMsoSFvacAX68PC/+Sjq/3cy/9XsRq7TVDMAfeuQglbS
QQFPVDReAybHczzyGdGY6+sXPmpPdODtOtG1T2cQpUEVNdFAg5yNU0qzzk/m+3+b
s7m/20V6hOuizNOVEMWODlxw
=mtjJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4c602bbf-96de-4f5a-a2a9-40a893c6a486',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAqu3z3n9sVjUmRhyeOsrn3PxVBlUBRWJdM1gJUsRndmFb
pOqcRd+zRBaIBbx4WpiR6sd/hkaDt+xf1Lm8otvHkNQmY3Me5o81P0YwULa00jAC
zErirBdCs7zN/zZh6E3f4ysRgry5bOnpftY2R/Xwc0gOYhSqHkouoWrxPZxAXa/6
bt4hDLXLtSvhBPDbVZg83mZS6FI2DFgqbrbRwfvmaWBYDTCz/BXQ1XTQs7wEFizr
TM4CeMi4l9lYNgB3SFLF1GD9RKuKb/kKo45+wPLg3d9c+QLKP1zDZM7xkqWVa+7L
QDj3x6ZXQ8UZe80AtrjiXEzIINyYtz+SZH4lT7SwkdJBAfif2l8Lhmcap6cYs9kg
vdF103gsKnA0/YoEtteWNnveOhFlrgucDcsowiW+huhs0a4MfdEaT6gPdF/j5u1o
fhI=
=8/gf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '54c4df83-f204-4d40-aff9-43ad3db73f98',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA4xHckPWkLV9u1vi7qbJxP6UIPwdnCcTt5AQCPEOWWhoo
x8PBdj6ED+rdu5fk2X43CtomPlMx5Dtwq46pWWNtiYNp07tbfA+vDxsm6ohbrk+X
pgBvtdbn7C2T0B/us06lAL0re9P+uFb7KfHFh24qQV7+DW8eCE3vF2dhx185fJsH
yopqsryKJ5kNBhtDflCgiCFRUbLdNTyYLRoMYsI1sXPfSjdwUiA7WsXg0vhXEnLz
Na9GTVQVCTVXjYUM0RQX0N8QsHwmW8Mm9EXwOgxVuyl3/m05/5yeUCLDJcht+z+z
GjoZoEPFn3X8TLofNUvTXk3fi5F2msQNYwd+71RGJBym5Qh8N5ATqVyL91Yvfz/5
C4KF0DkBnyaqd5T+g4iv5bLo11S3iryJIHN8VUWanx3ju+rJOwk37mEVEgFqmTqf
QfRZ7AVDiuBc6PSJCtYcbz7RDJ99LChcSMcp9k+wZWwSHekKH/jOxjk3CHGzwnUI
xG/3FfT8VciYSipw7naBlYda96IXNngGnimDSPegpw29Zx0GSimH0r4znH0J8xaZ
o+HlduA6LxB+uuNsfEciWHniaM7iI9TjbyZBNCrBlTklVLM+v7bUXv2FYeKM+3lZ
5vTolKGY69JtrkQiNHPZIopcdZ9caDQRwOEAYTtYtIYlXeHgjKp49KfmbG2n9EfS
QwEENkCBD4ydmK409Hqhxphf+t+5cvLt+BQnAZFo1ZDM9fPs91k8W2dulJVdV8X3
9c1UjyFx+7l3/OM0kM6Unf1AYi0=
=Oviv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '55a9158c-4f8a-4e9f-adeb-56248ccf6302',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8DI068MmaLsbCnXkFKax6TSfJ9BN/sTQOTNC7b1IoRMCR
k/rUEg8FIT/jD9Dk99tIfntjAxEgEMAbeSH3ZjfleQFIyXkUvsI+TH8NrThIivN3
owlCLhugywBCxHskux/bYbrIeoKeQiLDSnIxSXSJZrVTRxSuWNy95TUh4MdbeCIf
j2OowLUXvnWQ/IzIFZaaEbJRmNZoih99aq2Psv9UXjfP0Emh+DlCNR2Kz4TGEREv
9qNWk9WppEFJTRtjIbxpSI2NBtCD+3ZrhfTiy7dJPBqpABBTptKHiLKBEWoG5mYU
9AAM/HL4xkn6Saq19ruczjNTNnnmgT+GVKej8QkErNnLXJA4IIwkFRweQcSTzEtR
qK3y2CwMH3cRrVFtnwjNgZrWWnHwa5wS/tU97C7KoB+zagoMiSWIq3ECHneEuT/w
EWSXmEojtLUtjHdcp14QNcMhGyrYCXL/Vl7Un2liyXTSZPpNp3egNAZPPkdJKGvm
W7kj0K9Uvj6oDqaFoPH4EjMMOJf+973hq/hGm8Lmk6bxytPepfMZ6K6ZsO2/QVUL
qgymscLRs8myiogra094zFgDFjmEZtLBrUcCypPBEBW7Uq3Q5g1tJY3wIdum4wO6
tFVmUANiMbFE7BzJYCSldjl25NQqwxZIZsu4y0hvbiXftxIWCpa94LveHEhD2YvS
QQGPNZZ0/JGGXwWyqrM74sDIQqcM70HRPrFZgDL8d9X+ENz6GOlh2wCknxuU8v8U
hPATpT9RJUOpvtY+Bjy01MsY
=3/2V
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '579c41a7-7d0e-41b9-a1ed-7c6c268f34cf',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/eIcilnZae/+b0CclA2Nnj7F8zO/iwdrf8+8vSzzdxiIq
jtz/+eXcCsqCO/nq1vRvPZS21sVDWeeKf87S3wXN70dsg206JvbSfQyIqPdKo3n+
EVL+sKy5d8RGS+DL8uc+rtrirJNMJ05FpR/IEIzM7h7Yu9g3sp//hKyEtDpgSe6E
jYC7X0mtDX1pdYvRjuadXsyq7Pj26+wIlD8jj2+Cwv0IqFXF15xa+fwNl2MdviqY
kPHfwOMwEpYKuu200xeM1ubGAzonQNnkmWY88SNDBdu9X1Ox5rPoHELVhTz2M133
n5Wb0UCNMtGPnFhIZFdjbAJoMLhGpf8vF4PMEGAyrdJDAYV1VLhTt7RIbxmWIyKd
OoaF+VSfRjo+v2WUhC2b0kF8Brp26GwuwUr8zLC0szOjxbUbqVJXaVUpbQeL8inv
uuq/VQ==
=ChxS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ea21947-3204-43ca-a13e-00f351162600',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAjbI0Rd9l4MxZ9qdKVzP5/9ZgnOiqFxR9agF9Dq40N5gL
aXK5Z74JjI0sk3lie47bGzrsQ+g4IgG3HI1J6CJj/UO6iQNVADP6AdkVFEM5+U1O
3qp8nKUtaWUWCyGNH98Vr3mXr+b22Q73B+ImGUC8OD7kqLqrsk32i55qqenWvXnx
V2bw1uuxRQqLIPAnrpUSOs2D/LAt6jKKLimYrFEzfoap0iS1UfEYArjQpW8a9LcL
NP9LejNBQjJvikY/s5oenIDQ2YJ5rD0O+CR+zjRWq2Rry8glnAKUjVCUysAkGLxr
3fG1BmV7pVERYCcqx9b1jnIqR47/gv1VFYJW3Cru0YbF09ozCDOpMbzA3rzDDFwr
0LEG8EzUDGTIe1PxEoR2bKmPOQq4vz0+4yhLRp7+l4U2NNzjJ9huSENQPYfFmSFk
2KgAKByCUE0JU0zKwu2lkKuI5tqAHEKEtBOM6+MrwJ4A0QHWS/aOi5mQGGsvTjP/
ZMbNVqBFSWQC7MG5/AlvaiJkkrvxbRNUDojZPjjdgQ76OGg4DhluCrz5c8CxPWNo
fIOQ3sCfg6Wf+RYmrBwsX3yIeDJS5qReNs0s/Ytl6RqjIfCE30RxNYvrHN/vHTrX
zywEeCthRlu/cbBufu8koGb7fgj2JarM+oqofzodrZ8h7SRtxuJV6RiAcwRtpZfS
QwHgGVftBitL+V7mhPRD06I9Uwg9PlCGvrCdtvqB8aJBIdaBF+W12jtORyiiRBHS
oYx8CYi08x1+7xY557giCGkrf4c=
=n1SF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '623448e4-9016-4300-acd9-69d08a7b1482',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAjAmmhSoG9bbBBTcCKFaqdFPjagIxKlCDCE/7yUlD32cI
LFa/WqaCdt1T7byMFpcZEXiplZFWEc5GsTHy/biZfRB12ttRU83l8InFUs7z2iLj
rzDD936bnYVUUntd33PIyd52JVhLx1Du7s9KbrgBhraG3LTwLASPlNyomuBv551M
KfYlo76FSFdRWEZrQqupQxtxwAoAySM3Mc1ksrfEisKF0nEdfc292Yxuod/D5cd8
KQZXvKHUplD7bOKh7hePi/gLsxpXIzqDBEslStpUQ9xMl/UKEBSDh9WLxlG0WNaL
EyVxNiyJRCjNHjWjFZhYIqUVpGxzZgQ1iqVgNeg+82E0Y4wRP1FSMc8EIHNTdmTx
VQC1Tcg0LyrOrf087n5eM3qpdD6D6nPfPEV1J6tYoiHNyJzVad084uMCdf6+CmoT
DLaHz+kml9mzSXzo2yQjodKM34aOlrU+pi1b/wOhrMB96DrvFQRGe4Hx21l2hDZi
CIIlnXhBXUx9uusp4NnJXiWAf/WUfjDpuhKg/8nGujfAcMLF3hvibBTDqpFbdbOP
0L8UrmM4xSzhPqRvuDBbE/lyZZeV2yBAykavFY/V8IzwvPmd5yQWuhJYl04lQ3B6
h34UNXa+Xt171wMmWupjYrM2g8id7kJaLtOihk/Yxvn8WadKGVaFYJ3sqoy5QTXS
QwHUfQJ6e3MpZHIlnUwMDELQBmI1PVevlLcF5Yr0pJ7IAde3SsqjoEXBJ07FzVI3
e5/5jXA42w+fAUD+HdoclsNn/nc=
=l4IX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '657ee19c-75ab-4699-a309-e1410955008a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+Lnyii4UrsORPQqHmpPd5o23LFicMpyMTTPO5/T84KnpU
lNi+TcXHbV8VjLacQnyZ8I00UYW8cpTNsZjoMz0pTOpeOSsYcHG6xU4cDiu8ni7x
/yI1Kv5dVZHYQbbSXYgFKYLy4ygS26yZekUmgdYQR3cM5G4953cH+EHHjZC3W4sd
9DIqvodVDdNw2XOETI2bSLFKdDVxLs43ytKAkY3lxLs/E0MlKyEsUBdEdKdHw0TL
wY5f1OLeuOcaj1ENF30Qgat2U8HgETdlyq/vhskVHBNeRssgDL2hZlts0X59W0XN
p6xc0JF+T6Dvox6/QvBrdfbcRNDGwFNimSCmfhnLysWEkELTBcuRUhRHZx7r9hhy
tihUQf6FhpFIXtIXRBbbuNiBO5g89TY6LJGNiH/YOMUpeVdbksqqAVBt7V26t1tw
fM/XEvrtLhtZ6AbSyHcyIn24QfpYW4DMy3WQrpGyEbDuyp/KfLZdidmyD3G3PXo7
VIYwPeVtnb3+W77hC8H+3SRmDwcy/tRRiPA/AsOBK6CyOo6rGbx/hUsChrBz2Ujr
u5ULtwVTdXtklrJolGnAG9Xg+PWDDggtAXt0ZYZzu+Wzjd3OtVH+GNExc0JsWPVd
ki9mSX6gdBgNp6TIQk17O0ivkRSuRPTQyWdbpif0c2Hmn2abAIaMFGKfcxoUUsrS
QAEb6x4yTA5m/ZyoS1yZg+T3GcxRJSQO5/AyfDaIe4h0xujPAlP8537c9S9depXW
G3dIs8cbSE632s8PPj6tx8w=
=6F+B
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6be15ae9-2969-4d7e-acdc-fa6feb5fdcd6',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//auzXHK/Yx1bMDxLT/Dmn0/3MFXCqlrSb4KB82XMl3jWq
GZyKjFsjGFJ+HZY1qKf5jCXMj2MJBrhR+ZZQWYOHS0hCGjd1Hux2lnZtWhKNrChJ
g1+jd/HMaUA+E3ggquW2xhqiFYsMTTqw+RqKAF35hfMYyUcnQeurs8xjsLoNUOin
XhXFS6MUZP/oDV2USslp/Y4XOQE3bN/rX5KsYCg2PM8h5qcDURENwIv6yDrb4u5J
mAfXzShclfaR5d9jqbCNMWL7oEyZVBUThv6DsX9GVKwLHTgdxMxPbLURfUwyTPuy
FUTZUVLX0QXwDkO6lhk1opXncA+IxFvoreIfUVN11YTllxR2uhgVabvX6RcEke+6
H7c2WjgoyePe1EGd5N8H33BItrxLC80UrIphBfgjQqWXa0nw9IWYNCiTq0BSZrmR
9RO3tgzRitWUHEAV1JD3LEu74PQ37VIs8eaz+GJRBHeayxe8SYWf2m26f7E0IrHn
hhVrPaSYkHemCN+Cjp811psD7KCQ1W5wZ21VsZh6ap1y4UlPz9xJaCo+V+8sLf69
T8RmZFjfX2oAKwTXQrBN6Dkk25+mFPJEmbEt6d4pS9wAUvW3hTs4De/nYQZnr6Wt
RALVPQNKNrqf61Z3PfuxjgajlCho1tID5sFbuJXtFkkmmAPiiWp6EKXl52Cgi/jS
QQHwrM9bKQQp8MUD7cBgULxYGm3nYegEa2v1USx7QQVYqCTS9Y3CpHNh/RuJ+aLa
aZ5jrtOgMU7nr9u5+d7Gz5cr
=BJpY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6df0be1d-e727-4799-ab7b-5902aa094c09',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/ee1LLAF/wVSm/lJlH07tevvEi9ULKyHGSiFr6TxwBK+W
tMk01SYwkFd1TiZ4X/i3K8ZbJBeziAEsaBvqKyTyHlFp4sN9EG+0LEsMYVVe9inA
Oo1ElH3s6vOdeNMZ5SCF8gxVJ4Xii9mR8jZ1Sjca4aWebmJJFkUX7ltbQ1eboepE
2YGUFwxRAXyyFxZMEKeRJVbEaoWoDtnwIwfdwvyyTg1Gx+22QypukuV+VAmwkuhP
EI+I3dPHi9PJIR4jliucqilBMA7mxRYnZO9AGBOY73wsUsr5YAQZIIbiqXOS2/tP
/VVAdycW1B03eEwD14bc9EmCtWdxoBbYSihWuW05dtI9AeV91ntQdFc3f9qytkCM
535tYbuw4Wc+ylHudf2DL24DNBEodjweaGikeW2jZLgtHQctpqVdw91FQQDM1g==
=WXeH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6f35faef-6251-49ca-a9d9-6f0c3a10f227',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/ZToahBJR2bjqeKoqlUkCaEmXg68dw00De3WNNCnUz+Vs
gd0eTj9y2CMpYF6LDGjNXbdIyvkkDwADRwKWvr/cy2iNyL7z6LbrBVaEYxUGYCZP
OIU498SphVAgCvJksac+nPeZ6cOQuYw4kr5llEu93RiTW8PqeFVfvtXKRy8UbGK5
gAkPS0zyq6ovF4gwkYBoO3TI8oYgfnd/unJMzMHJRtDEke6Wg2hxKo4fPq9ra9Q4
Gw6aeRZ2BidSsqkYbPdTcw6ma0XsJrxd+A/CnAV3Z8cBhhGEIz0TMmQAc4wG5Sgz
TovFzBB/kuc+A7DBXkMnftPYpGQNyf6eQydjLNcB99JBAfW+AwIvgzYFPYLA/H+A
VgKKUFp+V0SX1AHTWNHFNE+vJaiviOmrFll5M8I9fW9nx02xKLKkQi4U8CUudqBF
84Y=
=CnUg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6ffd12c9-0290-4d2c-a66c-e632854e18d8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAgChzSfDMeUh3D8XnVGFMmnVxug6Uid7uEkVTlSvOBqTI
ZpDdSjw4N8c3CeRbwKiXVaoGsGOogCUevt3nwc6F9CiodzjLjVxdYUx+quip7sdZ
AMg1/9PKcErFV2dqm0Ed3TU5LpkYZMxmTjPg2jcieg6P1JEgF919SwpZzU1TxV6N
Mn4h38IL4WOLxpXx6Acij3P1BIHRank4Ypz/Bi5zSRORK5hWEOuP01nFOx2Gtm9D
9sGCVaMsgTKDHq5HNC+Kt3Af6pGgaXXY1JaUnfyCTY9exLQLqxV5/qvtqpUi97mE
GA/U2v3mYkFNqeIEZMMFV4PysvPayjOLZ0AvfkdrvM0yXuySkETqJGSgLJiaLQQa
CytpIuMtuQauUSF7ixehhy+bKy9HqxoB8zTVhoDykFsIXA4NbZGrZLQllt/VHm7F
sze8ui2vHXSx7RFxijRUDu3QvEpHIcrqdM0ISps3RYZWa9KeCaJZ7B+CdFIMGqPW
/f5wT1nTsw37qZ461fNbXO6mSBw2c3PoCsrzW2tdMs1mh8OdjrFGc4cWBeuDMFSI
me3XBgzoD394UbJeDg0L3JRsSRWIJiIwik0v29M33pDgoDJr5VCsHXTg6OlttTdz
wUF4gAExKYKfRUOSHxtPf1MLV/Lelwqi3t3vWGcexYUimBSYXmq5ImE8bn4G46XS
QwHwz9PlBZmVxlG8jLep6aIDYE6upCUkb9fvQ5oCBrm7yveXYIIEoXDEoJMu4Ag1
AGCdjUgH4aaEDnZDx6ep771eXKo=
=/8Hs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7348de08-2814-47c1-a5e4-0a8b1d22ffd1',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9HgzzU1CAeEyWYibFIZ/H/vLAICBZ8vuyMXff2B8ZlW3N
jSOAJ+SljZ5mGWgHOvkSskN2VRbuULb+Q4xETqU+/OKeNBEf7vdS/L7arzc+CIuK
3UTibTurvhZTXnm+lEC4pegN7/rGf86N9cBR5LX+U5xoeJ/z1RXBpbnJ+rAE8ieN
S57bbBdSyvlYCpshG7hiL7y/lb/ojeEs7uIwrmNFtNpyLBQBAOixkyg/B7KClgrX
t/fTZpu1Y4a2ZR3QwaBheCs343dVwVNPcOWjDuhg+hxiYldvWmNRNy31c2oWH/vs
RUvVT4210ZBIaDR2Bj9rfOsiqkPWLr/hOxrxbbkNzt3H8f4oKpayKvTrtxWrZ+DF
oTrdr+63LnXWLnumWvOVLZRXLu29yZW6RWZzD2zGeHwK28yIbIJZkasZJHmTcczX
IFbclXR+U+PNEtDdFPXeBOrFHwgD/hBJvrxx+/IciOqTEswi3bhTc2DzAMzT60Ul
fhFJCZ/WdXRg+eURg+LaSFtjt7id5dBlRDbyI9nlwNAZswn6q/C2GWA129P8t7AR
QBJ7mFrrjy1HXHI5G5QHXSP2N/6h4aRaSMtj5HnIyUaudWyouKyfjv9pHeVdtqtW
D/hWU5THcFnxhb0P+n7hgHYJGKih6te4Lvzztw2JT9jPL+J9sJlQjydF3Z34LmHS
QAGhCyUl32QXI6RNO20XANPx7REe+Sr16p77b1oIoq1f4OofY0auRUAQ0M7EcCtQ
mrNd/V3tc0D+KIve9+oRiNk=
=gAM2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '77d1631b-dfbb-4a60-ad18-95fb893f0a5a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAsJTX0BqHROSh7X/sJQnEfoaHXOyrkBblj6w7T+PUPJRa
ATG+uBCzvcowOhCyODjbbo3WzhQUtrcx52/kleOT27mgE6+GJB+8zzJEMzP79/Z2
OMUA/E6yIqjEhH7jnZ8Itl7KvRNsYGiTZg2VvFO5PxuOHTxHraCpd/UVV1L76tCr
F/1OM7CBUaFtEBZao8aEWeSubRH0oXaiXjbaLX+/W/y23KxytjS9X85rl7JtZ0iF
o0oq0b7mkj8wzuXYGVWzK7lilGGZtGSozvDzNFCPi9zIpcy9l9SX9RbKVqs70Avz
4RsoUOkPKKxzoshnNsmyzCQGDzKjHDELYgkSSz6NbBD2EyD2XthcNdv/9naU7eKw
WsQOfiOSdd5hBd59ex8uY2JbkWQbUEzs4ZcjaOxKL86Wfui6vy4FfHpljRje8QX6
LrBd2dY/pf2sl3u+5vTSfHfyk5u8SqHNBImZdVa5eWxCTrHPQbWVef+PekimPO2t
/ZHMJbBEPK+BplBb+NX/hc7771Ew9VAap8Yhyc4cdWzBpcGHn1dzasR1b32NQyH+
O9pqTirduJA7RkrBXaZuwsi2nYq2uxNkGNBS4r5XSFS2Kn/gkeFyvLKAXYoSCCzQ
uSgB+O3wADgJ0PzLvoJFrUQZ1nXbUJHWPYSv+e7JuREHBZNGA2uJFTd5kUyXSlPS
QwH0AFz7fVTVG4DVwkDpoAcKJJFfodZgb4N3xRkP4ep78dfUEy7AfpkGdSS+SqGR
1AdYFzlfZ5yw9kF6tFMDSOmzCu8=
=+Vw4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7ab7e7a2-2979-448f-ac30-e09caba021ee',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/7B0KMlrdMii/F1Mni89FASbCqeAlUeRX19CJYb2gE/l/n
Yvg/+KvprWpDVgdqU9hPsauuA1wJblzPd2esi54K6/JhECAaudEZMXmdxuLDZQDY
gXU5/1e4kpa7T7AlK2TrxgOgUv+wqw1hXFTeGKYui2JdLe+Ip1mD6LBAwbJ+m/vm
uuLavBMcqcLA+s38mXHYKb76Gl9VkfxZlf4pYZwNGK/wOsI74jAhvp5GDLad1mYD
1zyLSaIPEqZtg3YLZlHGVy3uBTvmVOVPINzebh0FZLdPpcEeOZRqoBRnZFTBl3Aj
Thl6eJvrYKCBLDKuWFN2oqT2reIhT5hN71Nk3O1mPSSgOnJEi4ETuWJibV/YtRwP
0sNtRBe9VRbBbNEcGlaZY7OGSi2o4R6Hi6zvpceVJ41zQRWix9LVY04PvzRz50wj
isTiqWJHjq1tjE70ptFz0uKG6Mhy/9pUY+pHq+jNGzQt7DxFlAMP4zzdkLOFeOxq
/8mpwyi+7kHRPxBQsI8rjiDHjAOoQW2IFaz5ap2TIAGJfFBxDfMOLHJrkC2xmWMk
eXRkWsp5K++LYqSFsdGHr0Sa7Jvu49AbQpKs0IA1JAhqGi7KjjwomWBOoNEt/dkY
8XOPpJNcm56KhcFL/iHbt/QHY+g+GMd+/0rKRKm7cKmbZ81EG3oK/RQ22mfd+UnS
QgG0ZzAY4CYdP6YOv5FeDm7FgEhkX9dDBozdqb3zzqEi04CBHVMfgZBcrtiBljfP
MdWTTkGN8x3DFb4N+j+L82TedQ==
=haGN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7de22366-786c-4994-ab4f-20fa8b0d58f3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//R1wunkFBTagiC0ccdXdq5Q/JuQWOH7+l7RPn5ZF8CMFv
AF10SZ5lZRIsVSBSvsFmom3D49FiKccdkYdHhLdrvit8h7iwyUSKDwcL07hzsGHU
UvtTaHJklN06kyE98L8y53VTtlRaPxOljM3TfnQRE7GwRHuZQvxnXc3+Ut4yi2WU
9BCOVkxn9IngdWFPCFRub1TtBDUAlLN0jWY8xveihxK2xK8CfphwaYPaPkq5L7kX
k+ZqfKuFOjx56/7iuB2+mPChPibDsCPcZxmaOfewF7cVR9+1WhRoT3/6UBy81GYJ
QrTRcvgYT3B3inKfoDVfNNHyNcUHiWRRPHIDr+c3xfElOqKGOfxqNBgaCJWgyTZq
sRcNPQ93k0liKPmj0XbCTSfgovOLVla1P0gG4M2+lD4Ql4QsN/AaaVQJym4u9nQo
CZTTlGCkIMNUhDx8UiIZWgt70p87msWdNc1MIdk8yh93g304ulhOsG9LtFZuk8kV
mN0G7QvU5NkYcXzyXCBAzNOIDWoxdcvJ9g7tVh+Yb7xDjuq+FgaFCZqZAIFuhW4W
QpfW6iIXf2uFDsM/yds987Jgd/eSgs8oU59IbHulxOVa9kheBla9nlJlJXGn3eza
2ftTYm1zbGRdwsnlYGLHJgZY0j0K8JvOulTd+UbZrSCXOVQOkjgmiCEwKU0D3IzS
QwGl3rsVGU9QUoHMYWddF2/2O9VRdzOgAWcfS7woI9uVNjOfb5ZnCKUguCJJJhUx
Xl3hM3opWevIgFOhI0BcYbeL7yg=
=I5qe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8a4f709f-a7ef-4409-ad36-02798b8c06f5',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8CXK3leSj1Lhe55CdJ0zBrGShtyNX+1B+rM1e2C7AE2bO
B+/MyeKRwea7J6lbUBxGnYSmO2lXV1yiXHuOswwahQtfvdbY5Sjyc0m8+urWRSVw
rcbWa/HVoc16NtAhiRzLzRuh3lM0Y4754sFpsPV1i0QUbOlBZKpljGkKWj8yLoDF
Jyo3mxXRQ40BhfjmqoR60TY7M6RiXdZdhiXymVsrP4wJGghkp3bsgmH43n37MWaW
u2lb81CW2aUfwHLG77ticFPEp5QXnyyxPp4apw8gpEUHGDibhsxyr6q9rERGBF/z
mk7Mbwv8/SZPLb/FPAxyx9njgGlbcOX2oybQb2MVNFm36NAXcz4L1VA6sEFTNlhI
0g2vnJSm8UvJn0eolvlIEHKwKghjUlrRmlksCSH/czeHKS99fAEgNCxVQGbUM5xv
xF4u0li8g48cbueZzFNvE7gQUUAlZyckzbJbBDnpBtEfAw+le8H+rul9Oj6OjQrC
ZJrprV95fJqdresFP4UJDUY2BNFB2zWWgYIXWb14rYZzKRt4PgFLFZnucfaKjjjw
KHuL+TnOCk7sKH0P7GWIeublckkwGOXIh2dReC/64Sb3mZbhNo07KFZXkYSXMXrr
Uu6+zEMWSucCfd4+CStWdkqTPC8yRkViaYY5i34mD6t3JtSa47BbCMvPqsZ1MkTS
QwEpO6lYx2G9kQBZKyrWWiv1BHjDBgB/a5dPpXPfhJG3VzovKYu8cCZtWJX6tqPz
WHidqidzBjnLcSsnuuiXHGNmpCE=
=sD3u
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96acf47c-857e-4eca-a867-b1d4b58e217a',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9HxJh3yn8RjuPFUr1VRtp+PdeK4RpwYTkUUXB9OHXny8T
eI8miywAwekuhwwOmYgYWxZTYa5cAjc8XLPbZm/rAe4WvN41DmEfpku8XBe9R9eS
KIjvKpOKX9kewHAoW4VUacmYnemp/gqhpDq2u1OnY0PXkQAKG2B4zB0UjHrE4zbG
Jz9MFJeEVBmvcQ3DSW4n7U6jPXi8Lm/k/eUUfQK8/k7RhRwS9PexeZqtZzAk4vhK
TWqkL8AwX2Zt3OMyjYTbj9cXj6B1ZemlqmD/A8YxpojjBB1QOoxZvEG09OCZK3vr
1TVyyZ75tnRMoMHnsy4MP8G7t3eYyPVAqMuKU4vSlbPZGY0k0Ojpho18w6ioB66S
MPrXhoZRtxe3zVV0CsPrc4jTFGnHKNb3STJnfB1bKwlFvDo23OHgqGJrFqeOqxbR
Dhv+A8MJeEjsAmG+HGLIzgVLW8hlxF3s46KNNBlK4Bv+2pjds54lrl5wGmIMxIG3
N2KatvXOplJhqtC5w9X6vxIxCyTnN/hcLrFy+MXjYgLDH/qZUb6u7/32pZRGlHPL
Q3vn8SYW2JTRW/kydYetJEezwOlOzM49+ZWMlW0PapClMoRufE4nQlZDfozLd75k
PLlHAAqN91rDdWyyWT9cMZ+vE1SY3mc+t46XELWoirS3LjIFmSD+x77zPEBW5jnS
QQGE+1QHXtC2QhVJDREcnnxVb4XnWk/4c/j9SlM6TnHIRZIwNvM+a7UUS+ReeTQa
rPTmFHpbBigH+bmdZi+FETjP
=pK5X
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '98c1e95b-c43a-49e4-acfb-787b62b79faa',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+IWvs0M0BWWJTt5G+A3YCctIQIOrKRZ5+GB3BucYD+MX3
rsjrBX/ttlQv4IZfITFl7XepIfN4giwunN1Qn8UrzDnQp6PA1JliMWBI1siRBO1Q
JOHfB6KCp0WGCE/AwpOYWHDRIYumw+j7WhUWQJPF3lb3a8hw2ti0+5st7ZbbBtHL
ehsyZwPLuASyj2QrYYNgvBS2S+m0fGmcFNf21HZlHEuHWZB1G6WVNGPnZjtPFIEb
XxeQETFEJY8ydqCsuj6ivCR9c7Emiq/1oisG0nk1mTs9s7Q9K32hMMA3KcBGPtLs
HNPR074fBgTPqHsdmbNauKWqGHN0vm9HJ9pOwa/btHbj9IfWBM0IDl04bAuUlzJ4
JhwneOG4NgLtjPZIPMHopmMYaehFN86LKQam2euEXq25CvZ88EwaHK2DidSwBlbq
ah2PZcuQadYCLI86XPpIjDjHDAyUGH+Kz4sDjk9VEHrio8HEw5AVonv8sdzEV/dA
BMw1Ik37vaa4uSrwC9MrvJkG38Tui5gyz00l8SoNTSFa5wtNtFlQUNCd0RYlV9L1
uVa6+UZSBJusH3IasDPO27IeTUEYO6M9sghvXlW/56votCKB6P03TJGqEYeAdeod
hbrto34jmHvMXiAPyMAwqtQpjwBF+V0vtw1whNj3YOlzM+N9mN7+XoYpN3kRZ4nS
QQHuDwJ0cgSXghqMsjraTwRtjHy3ljP8XoDuieKjaniIyZNrhHq6qasaVnuZjaGY
g2yDzqBX0YnZc6ZR8rUEiFvm
=YdQ1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '992511bd-5249-45d1-a181-169877a263ce',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/7BUnM/SXZmkD4Pb/OucDb5dYSSH5rBOP2rHpcpRo6yi+U
eL8/m3D1UEBVHQDrT72/Jn5CAlvvzK+sxr5s/IQP2+Mgq2KDmVJ4ENjLJV2xq33x
5petT/jScr7PswiJGk4JwucmDfjJSHqvC950RCJESlXlpDkrJzFlmKtLlpRt94rm
LbJkHE9y7Fwt96/RZOCrPVWJntWVPW+OL/2lOVgCpi6yMw8URnbIU6vaJkGPt8Lr
iN9OR3DPXWDqG0hC3qc1GUc125S4aF5GisNik7bRwLTjifFpsL9Mp7owIz784KWP
bk3SooQrVdaZ0EClUqSbyVe2MOeFWN8M2zYHC2VRRu8F2g/isF/IHy1EOii1+iYw
tTE00oIHIHekwzMusNZgkXd4Ptg85yEFMMUttUg6iXB4eaas98rjGzqziwmSqQCl
Gd35umo6vQ12ySOowb4mAn4fXPsrjo2+ljVzOsdvInFsifi/0y3kHUVUishmqcQR
sw52NZm4FQGVM3mVyReBNQcaOKu4LfjVcIEyKyEl+RD9pGX1/eFung0Mqlg97dhY
gUM84i6iqDtmsxUj6B+Q5hgqqsXiaC8N+XqmDI+xDgG8JFxUouexuxHLUCaZqoCq
4LbYNhX/brES26Fibq55TgZncZytaX4LnnywmYQyEBorOGaFVBFIGpK2dzPtdubS
QgE6yNwsc7G9WlvrWaas1IyY3/5m9c24i/h7GYsdS2zhXGiLFiCQbuVW8H0rh/Jr
oHv/XbOZHx13wqqIwcYndMQLRA==
=vOPm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a06c8888-1dbc-413f-a2a0-134d2f7c7baf',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//eMXJ1U+gKsXOXvqiy8x8UXgUDdvVLC30GL7+V2h6NLtm
qQlgCfOt3r4+OCoxWDaZNMWDgE91v20QtXeAHQ26fg383LJF6jBvir7fc/diCYHR
GXhWDSWyL4OI+tv3ues1ejjFMrmOokJhAyS+8h6GM5Z+4yZCi+VBunzbwXfE9NZM
ieLww4CXBzPJ3Q+lBRuPmLgroVqOujxXEE1HmHR1LJYYCwVibPc983nwgNezbbH0
7DhPXY0SYfq0BqvuklkFjM6kIKFOdrP1Jyed5KS63M2MZ9CQRyqJNu6l3PQrFYHl
L9OXp+JlbwJbZs9jSRVCKJMeqJeqcirvULKJNorwqnFfRwdysSH08tgs9l+n/DEc
Wx+3wEYEp3i4sSGGg2JSgACIhVl7wZ6pEhq5d6PdvN9GB6tQ7lBenGZ20SiGXcfO
eYNR0NL9xV6801B9x1k7BGY5xzI4dS6yHMaOjX7lcwlZ1rWjNv43tpLQNEOuigr3
LIFnKL6oB0lphUGtYkx/N3+cy3MUVWvCWi4twfhAqOdK1romJM6jo00N1NGrwFmV
bREOMyG6xDAITQZERZ7lvQroPLVp4XwfdWWxiJULyL3EIKAT3ooI66WZAe1e2RFw
OlPlExublGtjIMYRcJrNjhyqObyX6d/CovOtcOy475GAM8RNeq/rGqWeGS9fSGHS
QQE45TatMIDKIRBHajFlmIvJ2a5XakUM8DimAeEvTdcR0N1W9pHNbCh56Zu6nVJi
FCExgXrns+Hjf9XJTIsMOD87
=/Jqb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a9788ee6-3c95-4b91-a14d-60d3033171ae',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAtQAozPtYRuG7xqm1V09iu8bqnmzkabuJduFuC7YHuQiS
kw/lCl/WrLboPr7m3467AbenkzjX5m0M7PGyHnfFtKtuFBMYdg8JBa8vkcPlaJNA
8Aix+GV8AukVttiqoiLhEW2JkztT4hlJSHetOIiprFeUItCui+gG6+vDDdWqCsKz
++vf9wpNASwpOG5XxYRiI5PnltFeHCKfBzlF08fgrzaolfDtpDKeaKkHiXEQ1ELc
+TQAl6hmy/soRAaxv0FmZmRbqsv32sOS8RA9eNv9tCXCV2KFYMj9tCoZmIkDaVa8
9gfoFQ2m86mMqwKoQZv2/NRzpff96YmGxuR1aS9GTEAjZ1QRkmbnZkoSgtXUt1B/
4C6EH/h8l4pKSWOw+aotLGL3RbZegX2+wJU6ASu4Wq1y78/v40UCxBpfs1yaev1r
0YM2HAqVAIdveSfRvxtyCeMaXUqnj+5gYmxnsQv/7euFoz/13kkiINAEs7yjXC9d
ClgVk8WBlA7i3Zbv+YqWcLb+0zjCGJw6LPgmz2mUDZrWfk6gXM24S6sYL9zHL4lF
hGjs7+stQ/Hb8cHKFUwy/rhoiMIPG4Css1+dR7zxg1mgK8c1PPJTfCtIwvmJwOzW
oEKNyUU7QngFczwawx0leNdM7HpyVieQGR0qcprq8w+5CX7/I229Iu5DdDMy74DS
QAFEvSsFjG5xoTNT01CbwnfdHE+O31VJj+Y0lqjSowml0ZY6BdVhWxkfIjp2hLOv
h8YUZp7EUMyIM+8MieMlWa4=
=8oeN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'acb7b764-2e0f-4dae-aec1-636a3c5c0515',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/b9knSQU/ISvaT+bVceO9TlQ+FnajPViWrJw+oD++ADVV
Sif1ZqIr2fqtinWiPtFBmArZKMBwAQ7FKnnqrqHkkeyK6vnrH/qGBwLuq5oPHVXI
/6k49t/DNPPh0cdUe0mNCaOvAeVZ9rYsFvqhjbB2tcP/XKbStOL4JuXZdWFkGbzc
3XhaNinzYgzTy54gBPjcddgROoijsxR3Ken5Gnj4mG5nhqGY21sdz3w96TGMW3bS
RgFgAuFjCpseQkxm7FGk1dZPa3+j32XvkMGkGGJPa1oIqRrjzzRnoaZhqw4SfVOY
xKMiqGXOHKbINeDWm4vuFwUpuYwJJbX6eCPwh+SH+tJAAT6OqAf1RcIJkFrXbi8L
u2xmlDPc9Gy8afG6yFpQeC9BPHsltSc+gmU0FFzVNNcwbvYz9eqyNQTE9o328MBv
vg==
=jxG7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b01b14ed-0dd4-4ede-a407-ced56ea932b0',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//S3q08Mo8q4Eu6EO11hh8ynWlJZL2E3BDdXDXW2jcYb2O
Ij2zZSSNn2bmHDN2H5AKGaQ9xIWYNaM+8UodBwlBLgIgCRY+o4FXA4mfNZ4x2eo9
e+jXZ9cChqvu5PhJp3muU4zAK2CTVaQO6jbXrhHm8zNQS22jbZAEWEPni5L6l0Zj
pMeSBtgB5ylXZMpGOalccBbkzaiFxAgzt55skxDq2IjMPLiQwkKSySw4LQEwHtde
8eGqzkcCwedzf+KE9/P2qSCQ+2oHbtbistY+5r1tqy2pZgGQ/jmxQeOJ6BVw55x0
iYb/AFU8vDuYuLLBHxVPGW3/yHtFwqQrrA0ZdiYNA+cB17RshS41S751UQkuUo6U
gzwBe/oDsWhVgFJnkftNcbHyeiEYSXa82sLYn8mXXheZ9NVD+0c5aOA7kRF2/Xh3
saFPuVKt4z6tHw2WxNxXd4/xs+wE5m1Pv2MFnl8mv1pRi69i+2MWW8pwzO17FX+L
dmrHHPMfCoRSbkPFcstWhH8kGbQGq9uEv40kpdmlajmQTDT0YrZCri0VAw+Il+pD
A5KgGzA6Gyz89tTgwN34arrwHgdQnxb8hZBHf/UTdgEwEAAF2SXGMlsAN4dLcc3y
SaqHgpl+yyyHttosC8sLsnvv6WVIJJDHbR5+W7+Yee5jCcL47tQGVSNATCgFcNDS
QAEzcwLh8yUk0HuZGPYr8wPm1qorLEBDovxVHFNCoCYPlsXQgeLCEuVZGONEnupH
+p7TfD0pYSvSJZe3y5w1p9I=
=/a3P
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b904a919-b683-4f0c-a53a-1e9f5f56527b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAucwiM+Bq/EB5xVgBftl/4OGI6YhnhZEO15+N8ot7PCai
sY/l7yYyY6GaswKK/k62jUt2nYjxXwNDoX6dPpWINQ3tDxXZqkGQuNgi6fURvU+X
r5JcBMOjqDbrZGOJuRTj6gNZ3+7T33QWbeDx3FYPmZ5dfFZcvtO3eGBeLzZKV7Rp
TDPY2mTmUCNcGkAZ03zo/6QOxl4igV26baaSP59ny+d+ekmr4VT/j/+M4yNwW0Fq
UPhm6qCTbTUCCJgkxvDqbzW5y9Vy1n5Q1GE6lfxh0L0eMkwi5l4T4tkT7VVi8KS3
VbZAqZURFbg3/pSDDRkCpidtGsh/MN9NT6i8x57VdIMmNbpCC6C5vQ9sPp99cBNW
QCwCDwJyu2vJwlzYCwc/Io+prSqTCaxCQzIlnksdM/zU6Ysc+odCmuGqK2MXQATj
879qZrxQciRUPaYD4DB32d73HIN2KJXdNCFQlmCO0JyMznarLiaPLIq414CwFXfO
YoFlvKmSa+kx5tDPX4CHwMdfVsaXQNSO3sGMJBlBvYcdl/C+TMjy21LA0Av0EXMb
3Gs+tVJiKM/YH45L+NzzKDKEgDwMyIEE61BpsF5AYgcFHRjM70tr1xxCeGxGYe4w
VhGc//5WxgUFmMBIYpaArnW/vXwsXesW99TvZs6GeuTXtrOR5fn0v4Eu2FEec9HS
PQHKKtHDCABHdm13g4DZAttP6WeoTjycFOBhYpJgBK5iSAnI2ois8xwCn/glkIp3
Wevpo22udegt2Kc05rg=
=aIjx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bb0322ef-5960-492c-afb9-5604d232aabd',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//fEXrW1N5LrMzSyQBAlAv6K5UOwojxM99Tr4HAmN+B5DX
dm5eddk4SwEcouaLahJJAZekKXbnl6RtIqBZoHjkPlG+2Dh1oLVSih0QF4rVfRc3
kepG6hP+CbKzfymxiAuljOkICzOmXMNf5/hntVgbXt4RLT8zFQsTkT0hEiQSxG7T
Iz5SHNqtRpwKOBR8kGuexmtowRJUzEcfSqNLqQVvXKd8VrrXqkfrB9f89mGVrDiQ
BtDDjhl5Spp6h/8vlS7GB3L8oq85FZMS0yUy4W6xWCIynCLb0Cg4Q9ygLLXSiYir
QI9E3F+u1W/EeIXcyAhT7zuvyJi4VBRhSCCUJTN4mGOjlYoZVcKISc7HqfZgXWOT
ZRoAkvJPhZB/U3JNOnSh2UFCcignuUOHUUmoAVjoEsnPTM7vCY9cua0hWe8Or0J9
s4UNt5Cce1pxSATpdLlao4KmdFYnZZDunkIBL2psFFtOwAvSO4WvZS9lL8J/ve4e
iO7JiHtlG0hU1Zs9AA43oFYoZbR4umm5dUPrE2U/0yPjsW85H2xZbtmXdCfx5sif
9a6M7SkZFUCcyL/0nsf815RWRa3TsuJ9quQVvQ3KLl4YqB7hTymNIduNMVxdlNsV
M8j52WB5amVhp0GfLNG4BYz45sXoScOrWyw2HMp6lNiEc3eJNUxi74bqb6N77ALS
QwHBdi9XvbfLptdARKUjUYI5gdC7f+ZpPrexi+v8mQhO7aqhTZbH3yMmEJRSf8ya
syqkUWKQLvEn19vmJ8904Y7wzMc=
=yUTB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bc505111-a8c9-4f73-ac50-10eeb4ad873f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+P7ve3djk4fAuEXCD1GHKJGfQkDtJpRVPs2cukUnmuP0s
a0U3kKhbh7SS/Vc0eix7tfSWlEIxOFYyepq82B6c+fuLjOAGy8VkPkjjlsjqvpQH
S2V+61F1XvBZd/rAf6bLEcJKKAuzQ1j0inRP/CFQWIjZbbhs7FOtNoRUB9mPqmje
pzhmRdk4/7AsXDMgA2Ik4ZPYHi+Se3gglwKY1upzLNRiCVAdGfkDHTX4G/292hUu
NsEWIvMeE6teFs92cW+Ih9n1zmwAh3KBJ1F5brKJYsD1PNvkCLyXIxjlUF0xswmh
hi0/wHuRzf2GmomAHUveGDNh70HEweVhoUSHbi3KpVlV5cwAnYZHNIOYNnkN55Px
IYp00hH3xiPHgTx88TTxd77Ts0XncUuGxjIZqoY7ktFMXL3FF6dH7us42W2hk1lL
YfaaOUjNWrzISJMGDFV7jLXVBYpLgn9Z00C//qhDD277FeHt/wTda9jQjvIPFPvQ
lpjiLo6/gavEt+lbh1wcYVcndYB1FEXB+AvT+45tG5zn8YBchDpOV1Sr1kB61VFM
PvGr2yBOcimcgHoPsUdldAUG+tArax/uxxFpqx8c3ODAxD/LPBJJfTqicGze4g7X
AuxafgWAM0odNRpFZktlNtS3eH7dp4ia8PlTgR/XPKUiLDNDbpZkN5jyIlTTgCzS
QQEw5sYkfLUzEC020AFSBdwMcDicEQrMRbWimudJxGsLXlAsiQ9GARiePxS+1wrK
P+NZeug0QNkUo0573m+DzrOE
=Ec04
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c357f46c-2271-47c0-ad9e-4ca8138c6301',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//XTZagQ/C8gVkeCtN0U3HwGsgcSv6PTkWWSyyeOByVK6+
Fgw1iM7sshj/XNWvJ+hUmln5VSfVoaA14qiC0HbO/QdtcYuAClMf3SK4Q6M9AEoJ
ftD0Nyx3XNJMJJxM2bsabSUjKrmGtKebHB+WhSnGtzaWbzHe1qo4JZdCaz9MLswv
GYaGOWPY99eRWmjXQCrkgENfRmdw9KTRSOE/FK2HPfxdeJvDGTmhFH/uCxCSe7Ab
mL04gHgmXl4PGzj/S2W40ktTb6kgcVP2FaaOa6M4E3yVjLAOoca7rl2W7hlz3S//
A6Z6CLYAUBRK3L7XpqBeorjjukrjm2AYiP+7mqdWJvjqaOO5H+FNk77BtyU9EUPD
H2LCJtJzvgtxOWCP+S9WFJUn/FY/GZvQUhKwYcadnpGepRPUFfKXsjM6a0Zy4QGV
oI/mjyf/rFpQma2fZFeNIdkke/ObMcNKK+CtQxSvj1h3voQrc9GOMBpbUtg7Tq/g
pEs0wlbZnYZa5fpclu5bb30+0MJxj2LfJgb+hz1QLkMgOx7GPqcgPZOBosGGzKS8
4ouSf1CDo+D1GEZ05xXOv44DZ39B5a8QHbFX49uhK+NXugY4Dg7TAqs+6pexQExN
RWNKW/yMNOHXqq1qq6EB8dQBWzkXqH2fmnMRSy4xllvg9Z/3W2tgoNDCIm+VkQ7S
PQHe83QbF3JN67u/vtqUQpCz49i7tO/6VJUseUekKnSS+hm/AUq944OCnQ6rb/Wn
O/Z/44yCWCGoGjltMfk=
=/ZUU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c473d565-a1b9-416e-ab17-b1d574011911',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/7BvWat2+L47aPyb/6/LlRET6Awdv/p/KJ+qs8SjCh1XyF
as8/Zfda5nTYGXGBX1cvYXZ/wq3oO6nKNNdLgpDd8NrIT/UcGTypRRWspA8MjH9B
Ao5mUEXPi4UjF9hWKoPXftQEvcTyCrArvdmPrTcOe/aN5/kSBr2QoWeWbTIFnRzR
UG/8CIXijebVXYqGX+TJwmyhfsCYMWz88vP/tPEP7jhQyLxEKfC8WUP/d1viaQms
mjBNeORBvLLphM/bw61tHNebT8fOvhzpzBAM/A5XMIS3NG73XPnJ2ABxCVP75agI
2tKivDmXln5f4VbAgKcV09aQfmuuJyn0iKF/k1SRSL1ty0ERRDazkmTDELf91tJg
q+SWoJVbtXScLkeaOJLQkQgKIdQdo6qbHsQYQOSm66Jvc+o8K4AtN8FPAwjicjb1
nfIwtbuBQimNIJVKd7tLaC0EGZs0boRkSHSObXMeI3EX3lReMXHSIa28aeArvicl
zoqUCBuo/Aq2xfEJfeud0G+dEQoMC+GMMCxgJ/ek9xZFW3euZjCSNGDxFKXH7POl
WDoqh/VpmG5q8ra5m6o60Htg1GDNVak+dtvEOKyBnArCeVSN21BlwirmlbrYG4bg
Tkp8W+U+urpo34zyThdyc/FTIOmwiAxIUni53vgE2bwLLfOhZTaisOZTSeg4vgzS
QQFWeo6joP5Qh05QeyTKBkrdWnj/Rv51YqLo5Rt9wjnjwSuUYW6I5R3SxLQMFWiF
h9NMM4MmStO8XwpYHPmGu4Zc
=kC2M
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c6c8695f-f13a-492f-a23a-d0a89a567ff7',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAoIKQCRZNmsZGDEaO3MYXxZo3Io5vHX8Q81IoCMxEx/A9
EU/3uNHYuI6s5zZrv67kUhlKTyG0HCiWkqUumfvIcOP1ORgKsW2NuwiNHEdVTVb6
F4OL7cbm8asP7aWPMCz457uZPIiY58E6bc/p6w8va88dCNsEsWPKSrVApXIQcluz
TYAmEDY7z1VJEfc7H8nqdZdn53U9IsSKQoU1ZrnqW4BP1OYNV2PSZwY1cK0f1Q8B
HaMuYdokG6o6fSpyX1shugvWT2WWcztsKVV2Du84dbxxU5BXPLdzjbmErWQCe/Jm
ytXTM0WZb9iW6SMt6J17DR0bra9KEGxBLbpD6FU5ldJDAXvEgExp1wCovFVbNqGy
vR97gAx+xX6mYLS9G8dy+l1ZJBj35KSDSylgjiL611+xiUenZ5HEdvHJ3FnWzqJd
kUEdrw==
=jPoj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c824d011-d252-4895-a8f8-9915e7611955',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAhct461e80PM2xXVkWg9nICmiw4xzQDJaq+TZcfaF5Z1A
2nCReunMZdDnhh0oEcA5A9P6J/mJGPXoN/l1CMA6s549XhN/HkwNFFzRXpnrrr/S
0sO+VKUREduq5nK3Le91BlTFlvUY86H5HknGc1tAEOP2UeX7V5dRXV9CO9FlVhoW
US/JDChK1R3ZPp30Mom2BQCtR9rX1pL0ZMDB5wD6rBVz/VawViI1BwIEijPq0sXG
2Z+84e1RiumQmupFDOk17Yt4sxjyHqmRJqCZ6pxtmyez1tGPlJlWRy0SwQkwFjbx
rYA2JAOg6zRamUPH2vjb/du/jJxfdzMB7His4NCt99JDAbQ6Qij24ZIZDt7ij2Pg
Fowv8weUTR2Q5aDHVOqXbbI15G/Y8o/ZqKYjOtLM1CuPrvNw7epyd+dfKP24FB+1
c1Lb/Q==
=aI7n
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c828d694-f58d-440c-a9c0-54024efae314',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8CIlwyIph2rtKV712EbuEASm2hBeXlEwoRBz7nir7kjqC
NSyo6GKFM3z4UINFZiYdWh7WdI7NpyMVRK83Zj8G5UlsQH0Ic04MEq/RXLR8BQkn
Y9p9mbqVrbXSqJebnK43b6W9t2X3rWMBv0Zf6YUQJto7BM9I5rTqpPRoc9B/fVK0
U8sQudWFu8FBRvyRfKILU9qpifhJZh+/3KYebff1N+eqsoEjFw3bVO8ujCb0ZUx1
uNaI1G0Y2qnJb4T5G20erFJOmRgMve5uSz0Q/EaE5k47cFIEIg5gackyCaSGUFnF
ndRVLRlti2LxZFtyu1MNWMFPbEaX0sy3ITMIMS7ON9836+x/L0GQWi+K1gJsSj1m
zg74kBgv16ZYlUM0zFmOjJ2tZ9oK8Rgm3DZXVsimyvGcwKfCGoL14+1WW7rUmanz
drv2zenoR/bPnkSupbOp47/b8n0xz5r3fCQbhJp8vJzPtILK9BvqWJeoOLAYsfH4
jl2pf63mDjoANq7Qb8xmFxyzbrTIUx2NGleA6ebSm12ka8idgrd4tYRrdkDKZvph
kg5iHsV4aPT6nVw7gMCu96uhXHUvKApm1phknLOejDfqLAoPscIp7Otpi3brDqCF
xykZ8ngqV6DnSGtvMx96b/CnRNRWynqpqesgOlBouSgqzD8nj1HJG/1lUvMH3jrS
QwHeRyY1GFZP40Lntddo9xF7UbrpjE+tKJ8fHxjy/oYwseklyuIKz6ztHDe4N3gI
b6/rtibbKh1dsQcE/t+l0+Pge+s=
=fBWA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c8f45614-4f0c-42cb-acce-588c634659e7',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//WKMac7c7XqqZawgKylnTPQPQaSPRRecx5YXnAF7HEvVt
cUp64QBbxgZnwY4sYv4XlfttoCUIyCdMTt2K8iPuB1Lxpxb+k/zecWO7SunlV+aF
T4H8DGRSwZZuH6ynnGKOQP3s5Kl/cBH/htfZ6GRuHp1lB6K1RbLZRGS1k+F8evYz
4Y8hQwGJV8SaLzxvwwTPGRLePI+jqGo3XQqgyQ/WZ2kkfSi27nPRWuAA5W1HcE/+
bTnSnDvhq6qZMxJ6tzfXkpQo238NXjOFRtvp49S3mo9duBS/dDCfAemgxfd3Mg24
lWNClJKICe0pN2R2xIeAlHeNSuV1Y/Cx6CE7XbtFsKCMeqKH5DlOfqElOJw/LyI/
nKHWN/0BZYUq/T9Xo33Go3t4SjC3n9BJ9JkzD3Gbtd3HdrbQKQcIReeNwjLIt7Iz
u7AFs+Rgn3IO838R9E+o7dU70as1Jsmly0FzL2Jigw1+kerEUyaDqS/t2gMIR14o
McnQqrRrCgKqT8uPou14PAUthwSz6GKepjanDuq9CgrDFqbXOUrLUtDPb6NPChXZ
1WZWPZBFKGR6TC1CNZRhPHWob9jPT8Mc9ANrvaMGWAP84KlPfC2d2NSFDT18Az2X
MPqykAVBKbY7eYppFP4ZJXgIL/igaFJi3WUb3pnzixCLRSrV/gPIYvc5s5SdwkrS
QwFIkhA1zLn2JGtXz23dGT44VAyccx8Yjgi7JRgAlLeWOgWur3YWZYSb7wBNePw9
r4mS/Mx2mpHj9lLGgw9vULlvXGI=
=Gg6T
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cc98065f-4e41-48c9-a5b4-bf6f5db1599e',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAkdsLJ7gmb2JfZ5jMDxvgyr4ERJ+LrKySVfUlZkUN4Y51
cmvuqJxJu4yGHkcZot3pBioE2KLim/I8r4SqLcDOg00BDrqMbHsiOPZm7dkO4ksL
4MDvjmQnDdJDj1tw/GsPqMr2zDR87ioQp1J2EmfYC5VPEGUYo3l0GA+wwVIY9YyR
8wF4rs4oWTny2R3mrl97+Fm1Ey+O4ipVgZnDKq9B1esLbpcz0JH1caqoBNI8DLzC
KZsOw71AymZ/NWbEzGXHe1HuE1VB+VwbU0xszqSPQC8z5NofY0GlR1ztLY9Ac3V/
0W9NbY1J9cTHhO/GuE/fCMgp64fJ1BAUGbpgcF5bSdJBAZMDeGtu6hrOeAo0IUhU
oDkRRL6xm5upa3gRiy7FELo8CsskDQ81I5SFlqV/svSJlLcPVnirmzsJKMLclQYo
4O8=
=upR5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cefd4cf7-47e4-493a-ab10-4622271f1903',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8C4se8LLFuCVv6YG+pFCl3kd93miK+zGUdfloegE2/N+P
kJ3L0hn3fwGIye8ssg4U+hVrUUrYwRf4vUw0ms1vRVCAnkT5HR8XgB0+57VsSEf3
Np9xtSWPDRuCbQ/anWRnP8kZXYphC4w3tGh0OQrDGsvjb8LwN2/8ZQnApRpK5LyI
QDh/jNojqI70cppZMwAnA7CkSGsxrTQJkjhgb77YKJFmRhTAHi16vWP+8CHeZ7Tu
N3kqmuJTH1ckKvjkhaf8FJrTOHZi8YZnECxqAhH4Hk+6WJqj/B4ycTS3Zw/D+WVK
fLXOks8ll1ExRYbBk5HsNhUQKhvodKyVo1y6hvIhizCbHJQVJOZMGv0YgUKXzePI
fWQgqLEOA9RJq7c3KuEGyS37BKNzxd19AjneFkVjMhIwC2UxdTl8kqmJn6Od+MYz
tpVkXfjx+rm9swlS3XtBc2546pz+v3Pm310Aylq6RU0zVczM4xnUhUAt7X9+Cvmt
oAGJUj4+q9JcLFSni/kSrelWsrbNa8Xe2CZtg1ZhuM5srPgr8b3vogqRwtB23Fw4
m2RG0SnDEinvx1kv9gZ+HW+36DZfck+SLnq6iUuTraBIJQTGYQpy6wpvDh3ciwK7
Xk0esOEom3R/ICoEi1aG1xXgZUqFY1Nvnwt3p9zPLUVy4n6bAxRZJVvtaOmCWoLS
QwGPtRmHxgOYhJKegvV9y/uTWFoWN2gVUnLlopWirhqzDYahrjqK7quAdBhbJGJT
hs1yfBSHppC4OaZdRRZvc9ZlU9k=
=0vGo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd28747d3-5ed0-4df5-af40-9b6e82a8edbc',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+MOPIdLePOVZ8DeMzDf+Hp7xdKi6MbDHDmXwVwwzJwQ2Y
smDTCiY23X5nus8Z1AKpxQggrob0Ur+O6ZmcWzRqgoLIR/G5b/Gmh+23bWKyoh+n
1J7J4anMo/mZWi9l9WmOIZZ1/gYcCAg98MnOI0KIryn5tM2jjfBfuWnbXJF+ITGQ
lnnDEYBB0iIeuEQzqGbyMddkH4PNtd+82ZhjjjXWQ6JY5tXp8Piq5JnOFLLhYGyA
aoVeKQm6gYH5zeJN/EpWLWH7K27dxJV6OaYSptGDGj4w2BRRs/bNfjMzTHElFoVA
FNWlESAF9WML+XMedhMyzXA6d50cRhvKO1aAtPg/Gm8IOSJsc1JQenSmjLfVmsQT
mzwzqdBhcPCq5CXGvVaaNmljn6Y0/3egPYiUZz77pNj0kDgB8oLT+umK8/ofx4lj
e9rgaEOFnPsE2MLR0Zip6o6odzpkvDpDSZnosTIWygGLFW4v0JqitQp1UlS9OjqT
L0DVLjK230M/68iKtCNFOdWpOBF6DSB0dDeTwlOFjKjA4mCbkaJPsUgARWbhMwuy
1Wp4S8qk/MZFc8AxRjQsqN2QXctzdqNrbvoX9RbkfNkwVUyokOs/i0qzKYEQZf8M
krGSPqpLmRbhR8BuOlPWRDx9BvvvTCcBtkjP4E1G1dGtoLrYRWvhXkOOOu/S4gfS
QAFSD9JrM9g3BV5RstQFKT27E/KQrm2VjEQd6ab/cbqOov8aEHQ6eQTulRVqNwHz
KNuajbXA2CmbCRknPr9tT54=
=GqpN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd28cdada-1145-468b-a43c-334316054792',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+MQSgHfz4LrAYIeyfAscCfRS2lqLYLsUvmArIHtxKfE0b
yGpsiBIL2skBNvhZKwa8FyyrfnnB61gnwLX6NL1c7r/9ZQPi9SyBEzC+WKamr5Hy
JVXQWa+D6Ec8yzNtuDh9jWHFMqubRMQmX74HxNsM2Num6EiURM8vHtTDfKD9TK3A
Z4ZTvKDgdbRJj8Xz0voEl9F13CoYUdnv5rRts8IQP9rw0Z5JPnQLyN06/UEV2Ocd
op8sUJUt6msKhZGs/klRA7gJK3bIECOZVYr8rIVFHdoNnJjo+PWKIWGL9WpsvKKt
1QusLc6lP1XT7NH/zm6/588H94Spgwk+6/5IlxeJHRUyvGo8YhrLsOlnp4oJ6EiE
clH/jrlyUUNCcKx0BIcyaL24hGDwxJt0JcdSo5rPqIOAKeBVMUhrTgzBuZvdP7MB
Sfrrf9fJ0qC4Kio3j+qTMOfu1aXy88GwA2eSTdIXSd26Wjyaja82bFG5LoR/10n+
abVFgAIPJXzN2VLdYBz1c2U83hH+/lmT9idObl3u2bQYOQeRWZMF7BL5QWEA07by
Hx8X7US76loyeZcp1YwxjNAH0PGxIqkpVLsi+06ZKQ34F5Q0GmnwETF/cXBl4O5A
0Mj+YTRNh5v8sxDcO3u7OcXPetdKSSVmeOcnwZw+mK6d383XFKdzn92tnVOjrB3S
QwGwaHOSu0lOwC47/WU1nTtfY/z/gdEg9wPJq6grEmz8dsiCplbUA8GH+iGddfZC
fKm6B6ZbrOhwS7ZrBM6DqdMtj7Q=
=3xOZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd7ee8900-cb81-463f-ab93-25d77e05b119',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+K9U8B8T8pgfhJlipLQRLoZMQyU2TOSpWoCM978rs+5HX
sWtaa7Hf01yeOh7R/jBktCAVoBAdcFctrbB6xGJpjSk5uGNDBreIPmUqp9ChJo8r
nRcK1Lgh0VidG5CQ560SLffzt0V1foXcxq+kohxFp/8nKi947wffEOCImw2CQW60
s2aY91NkZWBVaddaEgVkVwUM5GFkPSQkP7rg8EvmRYajYhJHXlG8TP1hDEgE4WmA
aSZA1PNKtlNVO2E7rIJ+Vq3iASOyf7il2X/OOtlxk4RyPHKbYv1i0/k0DV742m92
q6DTR7A6EcOuup6n2LC/JRgRw/YoEFAADOygSlrqKhnM0dDq+aPpp+tvaP/8S84o
RXRWRYVn3MYITvYRc1NKpi0PyyitD4a1B6iR9Q77pr2MwZ7760ViSpUVinmZne+K
p+7hnFQR6a3D45xDDYg9nLF1J/XBFm4vu/BpT6kOtnnkTyhJE9hALZaN4P/LsODM
MfdQDaLFQXWNmUAw9nUGIR2imHLcMzQYe2Khwf+jFZJRKm/4dbLWnlgHflelZuso
sCYCymB9LTvrtLvmvnzlrPU6RHNRud9atoS24w1JC6zyI1riBi4SH3b7yue4ow0P
AGodQlveMfrcTnGmxOyM3UikR1HSgF1VtGUdaDZZl/uywRT3s+cSP4l80Kyfw67S
QQGDsBh0zUXZeXgR3DhMINBKEuKRGT0n17/lpdrADVaBxfYdpHFr2WZrQlGn8Fxr
uLQdfiL4CAY0p/L0hNxR5F00
=0I0f
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e0e0ba8d-1aae-4461-a895-0ee61d07607c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+IaqiT6vipdlkPZgobNI5GDDU2Rrsw9vxpx8RXoua1mgT
+Xg61fwh0X/4FxXabTbxUOcx1Kz0yxpcMYB+HLSLrg0CuK7289aPR8o2RrB45FQp
Ps9meigpwUqzJLKqxo50gA7t/IEDA7UIdlDIme1FU+3P4x7Ind+Iv02K3VOQk538
dVeKDNXI3gKQXbWmT9YXXb3kdtiXZHZNQyO4L1MywhhrOQ/ButqG2ff4xdVUVSxZ
4atYj3JmdjwKeDDOrJUhHp8iSKp9qoKNH/fuw2DYoy0zHuk5aFHxYm9WsY2f1+ow
UvxiV5LgbBo10bfnS+PuX86salverFPa0c2W0TON3tAcR9fpKIsU+RBUO2Tx91y8
T/CqWan4X6RujqwwwhPobHS0ukl7tQPMN20j75myODSAvBH0pYYAp+qu4k3A/NnQ
6hLXRwqV6xm4LUtuZ34LMDf3mwnr4ytBc7HCXf6q8ciRCyi7XPWmKsW/ZChRGTvg
iThTINM9UKAtaqVG3f1EJLK/L9twjzbm8LNbqGuTDC/2tqHZ800uGp6tdplngW2b
lJELP3k0MJMEN5brpK8KU5D7IFLStYJnav3I75ToT2LQUUWeZgMhxPJeyFmquc2J
zNrMIpEWijfdsEKJZLJNtloxX9ZSXJ7M0PoMevpj5m9urq8vPJ5yyEIKPq40UO7S
QQH6Ec/Pc4zN3Q0X8rHGPwMmUilfbHP1iT4c8MN0MzCBR+EEXxLyi8gKO9CUYNY6
2d7gsYqLx6TnXY9AGISwHSvk
=s+jy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e181d92c-9a10-4c42-ada4-0164a261398e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9H3tSqcuPyeZJHjxhchLuUxS2+C4WbJNd8qIyVOvft6hx
rbcwjRgIih6IxPNbEpP2oGx7joUE3ruqJg/lwOfr1fa6AZc1FRNLl9LwmVDQDN5d
azbCgf6o1sUfHyNRxx/A6yD9Elzabx6fE1OQP2VdtnQtauLIwRubcjtw1fsz19/S
iQXg4mFUDh5wPpLEccLBrzCmsWwdLSNnYAFjJ/d2fBD3Qk0yuwntdnFSzrEuFMmP
blAIfIPyN9BHeqCI+Zzk5j2Z+ZSoqfu3yZHPO95SJGY+K7uLbcUIEZgS4s73YrVS
3NqzV4KezKrAbXzgjqsktKBQ20+UIlYARs6uxHIMzMwJ+Jlk7uICGJS1McVTNvw5
E5HZIUtNZMf/rDaprRhwVX1VeitZKQCXh5TY6gk/FrS37EXAEGtl5H7/2jdQbGyO
P3+OoFcHegadazB3mnSHK3+j3n9mqQf8poLG+VrR8fhNFGrDQuAGDh4kXzNTEuYl
LbCWUMAhcv6WJbbUXzGogioGZABxVng6t6NNeNB/MhbCt20oU0PgbyYWuTRQXGkR
F+v2njKweA3MD80IBPduSrQJH0yeAGGNeMkAFQZHA0koCp//grOsmwHrY3Wqex6D
Y2uwcuY8ljhoikPY0x01Bg1MSaqK5H3Gl6ZvIJ+7iAFrZ8P9hEJBg1WAIBGalcrS
QQE2Z43hK6kXHbc6m/UO7Ekn4GfDY+Z2byBJqwlIMlgsL3YXDQtyJ+Yqh2Ep/MTH
wpohyTMK/++JCiaAuYJs+CRs
=2s08
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e5b0436a-6a34-4ab6-a322-927a0ac6e247',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAtCIc7wKqiLKEHpbkpcgO7r258NALNqOYkcDZy34RUN2d
2P1nlIrbjSdOAawtUGBo6J0hBsF8wLniK5seGX8o/GosNHr6HfyNmuKzz76AghWi
9EfoBvZk0s9uzzlEpWeUQxSxHcZPd5hYbpqoLErhg/fgiLsc6YEuv7IdF5n+oNVF
1gyWfgWUsahO+4c0pjbfByudEn4H1emLLbjLhoX95pqLFwhNZasfewI9ZNqhXE2a
k0X+mF7KUTxW/X5E1OKjNq2zrvSeARFaDRwa5C5ouzBGvJ+AfAMzK6BE3tS23PZi
zGzihKTJJGg44qh12RqebTv3luDBDirhlo+pPdYZd7jbaIyUaKtEMffNtVjBHnef
RbGhqenJfzcbvAGxA27sFT+OTsYUit6ulq30EwxvIjAUb7bcwzpc4twoYuKQf3ES
R4R6aN9TGU1RUxA3O+zWh+vQRsuLXyW7M84YoiaglxmgUZKklqltPrBxofncavm7
GdfASFrcoaMrY+BLw9lvrKDj5pRDX8kPnD8XFsv/1oDDFjGEKNrPcsPCFwHg0938
Z0FId2eZyB2aBdsWiWcP0fib0hEPOY3TG74nG8MetMeHL7GHs6ERqUo2zoRI7+6p
36b8IjoiRgjl01KbqcHj8brI4uRy7PzV1oO9qZCKQaUp2IydpiYVn8Cc1vxJj9XS
QQHF6X4JiZkIEPSjSUfjiSQ/KXzWCCOyzlH2yuK8MeEGDAURIkTjsk0rVYZiPNkT
zWyO+o32Vvg3dMAQVegjjYur
=H0kZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e7be5c09-4ed5-47f6-a600-7f4d3bc33ecb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAiJGg/RQ0AmU2H/w15nTVr/G1er2/3eHJPxX8d8e/1EKV
b0yJjWSkEMjxKjW41fY4dAs2AS38dhvetqldZZizw9xSZtVH4RSN55fqHAGKaovP
t4nhrdL23mZLTMxNW+FrHLJzVUuEGABfnOoHxQFsmxLxBibVnCFP0vuTfwxtiUbU
c7dsUE88IRIZNS4+9qN+o3nQIlZT06RpF4drb60RNYWDoesTFz7um7OsW1TgTlKz
Lnj2P4yBYjkYPQd3vArlhgFNdjEa3V3ayMEHm++oXPtl/orv6jZEGs2SmlXPYh4v
6C/ac3dt+zRW/QMUvVK0kez34bq1/L40dwThiA3IAaTX4PL0H/uyoaIv5JjNDcSn
Oj+xf0on9PyUCX9IWQSRORKKfLOoEWxzrWayvbvqYEl5LIBtKZUKta0ceJoZrelG
X66BowR7EbRqvWAKcO4IRtj+SCECtwLShEYX68ScX3MVdtPBxYfL5aBhW65IqV9n
nmTCiYKexePn6A9NjUaT5BqXjAovpwmRcQX9vxYtzSnk8eso7Z5zzHdmvXQ4Lpkp
2uZ2349P+Q4RJSfoM7fyyMzEsNX7nqUFnS1Tsh+iR7iAbO4bVL3Kgt1/rTVYFdiJ
fxWTTUPw5R3Yc6CpcQ+m1K87LPSz0u0JJ0DlAc4x7vysxB/Irw2h55Qwn3AjKHPS
QQGlmTNqwnHbO/8pAth2fds214ix6jqY8RgdP2OCIWMUwOQ1iRl4pJWpgk3kbh8c
+EIe39SMbhhanm5gJxzICaGE
=Y9Lx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eef3d262-edb1-4f53-a03a-4de0ca0b96f4',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAq07LJIopp7KNFxQSl3bLzrhTiVkiJcMQ0ND4Fcj8uqUY
2qCLmtX6F3b9hMMDak1M2/tnc90CF3wlf9WybKLeR3LxdT6lIx/A3Tptse6XLYyE
YH9WHalQINKD5NvOtgP9QcJzRb5oJx5Mc+YHAv/kfKPOefk1pP+cc8x0nYP3WwP+
/rVIsXzXZiJOX8THgT7rK6Jz1BCufr7JRwylB8uFWHaTtWlkTR9LZPotUiowDDvR
DYEz/2Zj9lr3AZii5Qb159es0DTG/Z/EfBdmV+jks2zgeie4tZLYcQx/9teMy66T
SfzsJjrIS0pHC3RZ/4u/ZAKEO/FCRBhJQELJuLDkatJCASp3w+rVSlpjtvRcPDHp
xPDR23XuVlb17Sl3ruF7MYzxy1MQ7TQPbLwY2tZnbkeZuGOxBE9tlHOmgtXPWslB
nBt8
=opdl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f28b04a6-65ac-499b-acaa-66ae40c3bf27',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9GEDSKrjCMSO0QA1STMxGNkxVWg1lWzGdifzvJLGYLkQN
naPduzAWPOEk/emX+TC3iQo5NyCyIEdm7TXIuJmO4wnxYcmmqQ6YKd+VevHbFsDy
C1VQ4m3TJn/oOksfuQnsMPuxMxCgv9SsRdvOXuSs37it04A0Zn1ijL3p1YYfiah5
v74v/Xnotiznp8WMRSCRd2gbpGjM5lZ5KvP6MUOwUHAG61SZIE6SFyV0tSC32MTd
HL/Clhh6P2angcyX/6nYYJttzieQsBVbz46t8mcV8ymnMfngd6CmtI/knmwSEEp6
joLvYqXrJaOOWlHYSV9JckAoikwo45sBrdeKIualnDLOeLivnjgCVB3mPdR98dFP
rcR2sSZZWfswnk4AY4gBqhrliYS9kxavsxz0kQWUGtDs/V3JaA3czYRIbjBt4FR0
xtl2vk8sDI+P4ubIBapnNfKx2I+CoOPCiw0n8ymMyd20OdnXDvjB7w7kHtgFcNBC
mYl4y0/O6Hl7zH2QQhlfBodTuCiTLsANTRNntQxVZbjRoi7WSeqfque0mQ+okOtM
t+CLoT97Bvul+tqOZLvBEcbKGPAOhANJFnaKsWjAIzaXdlo54eGU2Edk2UMq3rZn
JMFrwIS3bJ4ia1RP0/BHtmq5HpPK1NGh0lYZFbauREBplkVhHU8m3yJ4ftiIhFDS
PQFYPNzJiK/q2Clz3Ju6rD3zcwc1RWagZ/NK+1wjekWxrTA0P7+nKMWwV1dINYEC
i22d3is7H5YcJEwu/b0=
=SeMV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f349d087-c7ff-48c8-afef-00b5a2bed8f6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAt3CeqjgNo1dxwHhbPy30rdYLl+Avizk8kjXsQj3ocW35
cSXQOfwzhDkiCo8K+deix3V/q6FisOZawBY96EN+tmxoNV6/R8z0/Yy1I1Tc21FK
JF6Bsff9a7GrUznrZgaQ2MuZiIpSziY7PKGRf3lptFSv/TDTbf7lt9oPktv40jCa
8Dli1EJDGD+DjWIFrsT1lpS1V8RJWbVFtbt4dIsIqUgORC37oIHZFajvopEzeNHf
LLUmXFKIMhPdGMzyMO/NkF8xGUTN+VtiVxseLSY234JTvIPNc45ssmTZt6/6ftwh
FwiTrmzXj06L92JBVmSXZf8gPwzCFWRFt3c+AH4qnLOvNPlLi3CC6cwP+Q6iz9Lq
F2foRUafpXUuI580yfnaoTSxK/ujc3OG0PhHV4XSqBOgqe658G5+ll1qb5HvHwie
QCACS993XF5oWbo7RszW8WpSHXGavW8UKQbcRmjwoC/xhL8WDr2GnismFiMt9Ocv
Ru/qQKGAN6oVn0N7ldYSWpXiH5Jn2PscMSdUBSJFFfITlQNllkZWN7WhlACgGQ+V
VLNQhOadWrs08xh9NmuQSpmOvjOHJ63AV29jJkpm4OdSJ3e8kAypMIkdjiuOX4uz
x2+BNP7aeDJMieMT+L0683ScFAE9o2WORnl9cZhTLeUTPMvPmU0vqydOZkZ8FzrS
QQGMXYwgCz+Qfo7P3hZJtR8uyAMOHwj+egt8deAoVHu6VJEL3kuNJc74oRnldOo8
06RDAdbmEBVYgu1aKjSz4Can
=IyXT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f9e86f27-a6ee-4dd0-a673-2fdb885ec58e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAqjE14nju0MmLmGRWSDUw06/m8p569x5ZiTLfpp9UKNwM
nRImP5iURfd52lbNTtnYqtkXbeg9OXKYD4CR+cNVJnlB8bywvzjZkQh48RD0HZM7
qaTM4JeKymTBogdnQ/vjj1B5TLj20NItuPhHqwAAGsaj5eOupWhoji86yNzTI7Qq
xzOv/Cfu4yyMeb3ursXZpuDGd0kGbqfeHBE5lZ3q8TsAQhRk8w4xurnWL3KS7GWm
7d1QDLvaJqEgG8lc95qcoceTOLuIw452/OA87NMlAI9LLn8Znxf26KRVlpFbBs2x
v29vzSrbkCXlSkvGwD1tEt8m3MoAexUNK5wzAaVa6g/i2U3KJljy47f0JG9dIly8
Tsp3dFB/ePqsuOZjXWA0OsPTwOvVC9QheUhzrANi5R3w5iUfTevtNG68LTL9GcC0
PXZiNfh77ifTfWX+saKT9qzbZBx1xm0Yp4Hck8SoeI5y9h04u+GNm25Y3Dtee65x
3YGq0TiMBC0LvSc2l7/tOOEEIXyeIS3fm7bdl0P8tcMeNstY9t7nQY7k+dNBUR/W
VzqmyIzkdutjCh7Lf8FMBPKuJ02vrZ+R2fPDJv3tu38h2S5mfkVEJSqzIN/lHRCn
nqwZHPapeGhd1cSnASmtdNOQf9dYpu4FNon7tZcr8XrNvffYnpRMidsrwvSQDInS
PQEh66fZhoKnOtq3yE8BU3KA8FteaiH7agstkrGPhNNDBle/Z7jv/loxoRQNiVQQ
SlYZWxRrFoy1lnNsK3w=
=T9Et
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fbb8f9ce-f67b-47c6-a897-b523505d2e67',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAj3qqY9V0zCCibd4ub5V3QK/QtGN+KjjjBNwVnLscAYbx
tamcaLfYP1paJdEP5T+2iIVJTZhG2Ro3vFQaA9kGScPzFLnOOCJmdjq3yVQ0jfQw
vjfhKRM0nuwOuvd5mhE6vnNXalhNu/3CnSjr8/f3ew4FmPZF1jlTE0HW71zImVlh
iFOOok/hdN6tUTwL8sBPavYiloGZKuhRbzMUTfNMlzPU0mFIpLvL1uGLSubQxrjy
K+3kpz3MC3ooPuHc8HZI1MP5D+H4km2/KkKEx+AvOmJpkCb/V2W7NaWF74N9c56/
62AZHk9ViFS8SV1fX6nJ4O+CnCzWTqSn0+xl3jlyl6WkVOKS8qseQzVi9Y6152nG
d5gIaMPYiqsNLCsQxh+ZMesBKX46uQwEBXKdYCfdYvjhp0bNGLnfL1J9rDG7QCN3
/lwcxvI/KQpbqkWT+ut33cBzR1Wc1ldh/+RmRLlM/m9tT8NY7hrbf/6cOBL04DdL
UWGPQpsgx4XMtqgdo+m/hjqmXuRLTE19KH6m43mzsqeMVoBHUQosyBwedko0aEIR
I47HCvzIXn7VMGJM1Ptbp6w8KzLIBE+zhG4oWY5tWL2vC0utBs4z0iGDkGknlSeh
bUXiHvxZR48UT6yWYzFnaAf55a0aLOi8fN8jhbWR2TNpHRbaxrXM+/yB8MUTGwvS
QQGv/Yxu1EPYddxRy2a+X1Jp7Rsb/2ANxuqaYeIXbpTG7meRknv2fGrEj93328+V
wYefSCxyV7cFdNeSG0Jq7g/j
=lGYB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
