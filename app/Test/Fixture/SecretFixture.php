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
			'id' => '07561635-a898-4f67-ac9c-9e0467fe9585',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAqg3oG1eMkvRhd3bZ8ka1aCMzS0ZP+OqzUVJUkFlnVNIe
tERsATkMvjJWlf6ZALxVo/i8mctWoVqpoYw4l3H7oLGCK4BAC7wc85Ptya61hosR
KwUqu9VlGjaPRvasmRu8mauALlXM4oCKoFW3SF+ubnlv0z9u06CmM5AwNHzkkZAY
4ruiyjCzHswujDgFQ0E98Xrn+BwHfismWPgXBYEfQVZhiJpit33pO8amGh+Ys7zF
M61Gm1DKnRo7stttzbX2DauGOJ8ZhXCwemsIaeduGJ+xw12pYJtZ2tEY6Cl5PKnF
H2fPCcG2ROnsF3HJw8edn3VkSwz68BBchamktWfEaudIsC6Svqg8n+4PbrSBpAIY
pdnR+Vl4j5S5Kb2hdzjBrQvGU8moH2/mQld8+rCxe4843I4LjHe+S5bz05kOAbVX
K9OexOiUzc85DXHA32Me7+F2KBMqyp/Tj7mRsFbFPiaXEaMAf674+Bce0ld0IwVp
V0Z3a7jAtCSaj9N+JLDTxNYZGrK7egEmwgx3By5C7v/d5nXf4m/WB4wPkCcJgj0U
L8vALNwDP/ZLl0rML7sPPH0yj6IbENofkFmB7rcqAuE1g5y1WmJd9++Eog1dwfW8
VSnMxsxXP8dBF1EMK+mIDeUxKOOjJHRfT4ACfXpshW+Xshm4ZEKRsTHq2UJMaOzS
QAHA7jjkZNq3caUaTYz/x5zdgFUxVSnBz5IO0tR3g44bacEhWjTaLFrbQ3/SNqbk
L50VJhjNE8FjnHvsiGKu+1g=
=gObo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0ab556e6-4672-45c9-aed5-75c0a8b0a212',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAq6MpdCQgrVTv8zprS7hj6m3w6N7YSybn2XA7OaVwgBFT
b8kFFclqM21qu+DkyspKDXAnpc6tY1VdMNmkRE6VaTjQzKscFtdwQZyINQb0mOC1
JZhQpBxzUiMp56VBVi/ihbhByGPlN7Yl6fhzu7vxmGlGNeSWRJxzzutrjX/tP3uU
r7UYK2CG5v1awS8/kABcoQcz3w3thVydlcFS5XI6vnB2IK9tybFf2SbP7wBcvGZy
oGqepisJMaN7CaObmnoYaZHHOhmNpDYK2sTtjizAfg/M9d+L7iJ/dH+qb5ETBBeh
a4bJrMYQ+F1Y+4uwvdrksMpEDZqrXxbMvptGPAxudqyJnzlathho+SyM7+Bxo5gY
gnWARk8spq/dIyNBR67iSNQv5ULoM0iHgykjOXtLjULhZSJZr5yWJyoZ0Aj40o/Y
o1/RaUJ2fWZD4cdMj/a3AwPx9LWflMyFbYIRn7CBR3UypbHZOhTT0Nmw98+Zb2tG
6Fodj282omjbX1neV42vBmCXD3ihU8u82GS0QG85fzuAbZtzfNO32eblUQtC7l0S
Heh/tmFKzvJ0nZro+5q8rSOg+LlbjzJnfPzxXm/+I+epQrQUO66JCTS8943d+53A
gEtweWlvM7pegOQANCmyQPRF0yMzCNhq+Ly4ujO8EFA+SBZtffdVUCu6KkS1DxvS
QQEKD/xTnkEeRA64oPcQVwgGlgQQSHmKA2yMn0cirUeVpBK9FoHXhaHrB2gRkw97
MeHNn7gYsmKAlCZrxxPZDPPH
=NHqS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0c1ad75f-e606-4751-a6e7-4848e4a23334',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/7BElE7hmTEQGdnsYH1giDEnk+LjJENPgK/QeCJpfDbB4x
aaldS9Q5HkyseK3luE1foP37EsuqibQSNa3N8bJbW77F35Uep8M3YSIbT1JCb7NK
gWgx+OyS/H17FOOmI4fiP0eNt59eZ5rYKpQn7prnp2rQ4e7zIEYhgwKeJ/sm32Pu
D3ZcbZec42PLNPBZ/gIJUgwztORiFyhmFxipX1r6WHgnUYtW2JixIwKKdqyiRrfK
PY8tYh9A9UAWefqBqGSFBATSkHfvJ8cGI+qYmAKUmDaimbjK2IdEQMbTujgAb5XM
LUcPNNi3iz+ROMxs8Dl4F5j35I7lfMyCuv2ReUROb5GTObs4oGvM1RdGyToZ/q3m
06b6W/obzUpshl/5rVO096EUptCIVc77mGrfcn5Y+pp8rhGfBkEqOtzb0b2vC9WG
TyoAq1g+5xvjzoI2sLw2n8X9Y4bP6wDUojGeyeUmrj5F0kUXFhj8gJUMxolUbAb6
ZLlNNU1rlARgpEYumtusLS9vURucnZEv5ilNaxJZ20LK+pXN15rgg2M59a0tSw08
30j72UWBThINHB1v3miLWu+/JodeWMo97zI0LwqrmTyWSqk+Z3cn6brZmbYQDsnM
Ltax1CI2twqeqG3+NGI1gziFtQz+I26bTXGeCqKaEfaagxXahhJOxkL6N/VvJ0rS
QAE3ReBaHwvjiIsh1uZUDJXrjvtQ3j4dMo9EolfT2yLCxU7J0lNxuI068IifyfgF
45hL380O1vtu9bCuB3X+SHM=
=4P6S
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0ca0ba91-8739-4ba3-a41c-477d9589e91d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Ys3ebO9ya07BOEAG5WnzK5urT+10lvDSV/5kRf2Nui6b
+Rzne/DwiT3uKuxPyUzst8RRFkr7WsxcM8+2lNtJh2c9CXpCz7xkl3yh6/PQyaRP
42DAyW8jDHg9BDg1rhB9FTaZ/AAtKgrvsI8UbdI/9vry7hMTdxFEibTw1a4S/VVD
gw9wJjKjjPcDmep87YYp1IlN+jwpaFVIc4Swzsm+9wG4QXhZfRM5fS/IepwmNqtj
Od195bVUB/Gfy0MEPkAP72Ur/FpNgmWUh+QauOlopvbFiakP3juFjP/cSeVr9Iam
RkvjNE0KdyoJkYLtmvFPP/CVV77ptMX9OZ8DAglDflnA8s7BoiAuk5rgb4nRuUKc
pw+H2UKmNbkNPqwLisywfh+t8Zj0bXGIkDwTqOAGnuEVEoAompu1Nz7w3Cz8cutB
+EkuQd1ruDWxYtY6eLkiO8TN5ZRL819SkVtIIpyonmRlOxUzAJjHIRqWWU3JleIU
8uUMYvhHDS1onqpZH6K8gT1dF/YKmXivovHwPxX41bNWCyPCYpdUoa/tqKIOHheV
cpIuWjpQy17D9XlTgZeaqFN7r0G+PO2INxGlFkF9tEtKjea/Z1TQT8AI6+k3i/Y+
XUlflmW0Mx6KbiP2Ls0Cvr8p5JCvi8gQyNC+SFeag/mWPm5oRFPVJiLBKsQUSEzS
QgEqRplfTrTa2w4INuyBe1IbufPaazCYVHZqu/VdDWaYClYT+CT/hjjpUbov0uWY
kWM8eFIG1XkUQaYLNwGfeeXzqg==
=K1gG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '112db14f-a0bd-4b31-aeea-0e2342e2988d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9G/oxkqY45PfBqRx4Z5hDa90d8gIDCJSl7uR73P1upM45
DukXRRlxqL934cfYrNu59Z5Eyn02Vn0N+l8VYX69r25hvdD0O7mE2cW+9PRFH16b
i8fUdcdUsV5VOvXo/dyDz3AqAjZp5l+ot+ywxq22Wg+I904ssIUF3cMtsC+rbs/D
1iOK5PFIecdIid3KZWb9UphFAiAvkVFoWSpftJ4yy7uSKAOqM1c03A4zd0XcsFhZ
lk6Y9GliRSO+q4Q9cruPtO2WdsV3Gaix5l9TY+VuSdnK+7VwaYkVCzFdT7wO4VRk
ZQQXD8lrNVkHHNsdQPH+CycfH02OT7yYaJz7/Op/aJSX3qP7MoJAfIZiOL5BGyBL
I3Fk5Ax23iKJc9qVIf/Lzh7ysub3xBLZrDFJH3miHpCPF5wvvbqQw85nzPvdsUFg
lajK1Acyd+BMm8OBhQ5tqoJ+OC7GouTymknYI8/bp73/vXdywHrKV0s+lsOJ+xhY
PVojJ9jytAIO6FgIcmsg3o3mWGR+asYsGsEWHqXmrxz7v/kWpG1o0Q6nsznt3+81
PZ8giQsxM70GF4WTBLS+gTPXihcVZCZEK8uQ2UXwyQ6jaQGJMIIL6VWsr+MwdA9o
KbGbk6+iQlvdnbghEgDIi4fXazTBQgI6cHEW0Zmi/Nl3scx4RhnyAW5nrBrA+uzS
QQGsB0nZtXdC2l4WAuwf8KsG/8TSPtRVMgOEuLjhPzVrvdbHnEeOjIL6SRg6yV4x
RKA/Jxy5cFrpn3t6hNBjTVQP
=CTQR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1241d666-a4d0-4608-a1d8-ec53ebcabd3a',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAr3tCIt9LQplea/jmrQq5/1cLqO1sccMITqImXE26tn1z
qbiTA5EGDdVLguZA65okTI1bjhucdn2MHYJZh4u9jBFHme2QReBkXOBKH6dIcxMy
YzXZED/dNPKDwM0pvhM4315YaPUu7ygVLzoFmun2mKuDdELv6xcDStIO9lqA3aEr
Q08ZJX/9Ptkhj8mgGrh0i42uM60Tr6zf/QQW88TOkjRN4qJiMq1b+8A+wMXX5g6g
weMCoOaHUwpL3UXEJa8q/aMkgA67csQLnsyb36QZ27AWdsayM5HxS/Pu4xBXrMD+
ZcS3nB4RgXP+0UFYupA7arwbUnzPoeaqyT1E+9eBSNJDAY06FhAMddJNo40sqBpG
pZmyrSfh4rflm1oMUkva2bZvwPf8LhMyxsRhceRtOvTn5y8Ta3eaDjadoe7vKJJN
xQLmhA==
=p/ud
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '18a47cdb-b403-4645-a736-0d349ed9ddef',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAq91H1CrqsU3sgajyr1ofOZTDw2l4RttteMTeK52AUkjx
DKaWr5IChb9CiFE1BQ0sR1oQjAXdI4FKikN4mvEwOV+B8fduGc4BSJA8fJaVRUIt
fDCJaU7DZ95EE+9kUkCBHp2rM6uOd0TyHvy8WVOHzxY6wYa4+bjn3/6AB58DiAv4
3zANhu0LSqSSmkBIiMeLM66hIx15n03qY//+9GDBR8zvJjTTQhkvuelKyalZ23nn
mGUT2bThiuIglDNKUuX59RTeg6IMkiCT9o0xtn5AGWUvpFI9DQHXlJkC7LGkHpjU
wKxWOqfLyq8bHBEL9wWT9gfMbTNo5Z/Z5lGhmz/5jXhhKc2qeP6OUh4xmegaZvlL
hdcLVRqdCJKJXm28MQ+N02UEbIpq+CN9QJFhxViPUdw2+EyYpW+XjKaL8LSMIE/N
P7bNrWK7hizYKm3mUEwKBF6I34GtccalPU/PjfVkrOOzUgJwZYtw7U/6xtJqXnRM
adEjZ1gegCyCBSBPgABGTivg8qR2Bq0AJ2Cbk6LWffuE03H8Npepx4RGH1FJUryL
st3eDwUDbpbfx3TVJwK5zDvH2SW0Z1v/jFA+3s0mhhG3TnlDYlU4GMgiDqFM4xYJ
ww51H+niFMQ4aqkw0Fz1h6oj98HQtipQjVJWLaG15SEl69fh4Gy1VbkiuavhfSjS
QwGlg+K0UcY04ItESL9739TgKzHvC9x2cBZmYKyepTpgw8jugnJv01Fle23W6yI7
pythY2ManHYBeYtaEUhlwtQSndA=
=hGx4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '20632d65-59d4-49f0-a426-edaf5faf750e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAmVk7yt9X+QIF2t+houbOd4bkF34peRKBJlaLBgDK1/Dl
Zp9z4MtbkIew2/BZ3a0bIOdTV5d1mS80ce2ypbB5xPgZXTkd5wITUFj9vP7h0fMW
6GmIn0M2PKXuy3ZKyFwa3DR+OmCdObyymjtyBem3vYXtszDEQWg1Uuv4J0GLqIQT
/SsEP7VgvS1iMh3XsoiKyFXZ/AeXs918yDI5nNDuaLNFPvSHnHAvfSk0XJEs4Ojp
jcaS3ppaT67IiLmeta7Gu6HDoI89FVZ9ZiBxKFIG3jiwbqJLrQxucteE+zCeCmRE
eokD84vfJ34BWswL0ZeJ2i3PrJ7Qchd0IrqZFRK4Euak6eg1ddpT2+oL5RDxfb9s
ZBiII+KY8NJYw+rBGamPftm/NwPzc5cURjGxjcf6INJvNIfJ3jW5wyqOzltaYjp9
hyRxbVEvw/wOh+gOmpCmCiXxzC4Q0Tgv7a2qY5pLy+4fj7+oOZR6n5jb5nhDdt1t
/teKvnuPQ4OtJmv5HFYbrUpgr+lAv28m+nwAHJCBjPUIuGvtLFu+fExVj5XASJnP
g8PMZ5yrK3nUsyOgNgx0fmREHHr/3KaZ6G9Z3O41MLrVSsVtLpG50MQu3IvJ31VE
r3Js1zQHeqYF35A84h8cSs6C0+AGVQDXBWbR+xLGt9MpQ/6L+Gewsd45Uzn/E6bS
QQEjesnHg3U9Qywu4xHcOznkkZsE7PQN/BecPajg1Tkm/Idvg26Tjc0qZN/BChFF
Jdr/UseaL9ASQhiKDpcta3s1
=au3Y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '21f30432-ce5b-48ff-a7b5-67a4365035a1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAp8wxvqi3b170+LZFK7m1H/AYGvtzgT28QcDjbkKa7Gts
/nw38d+YMiI8Y3Wd6RIYcudjSncTZRiSBxVMym5JYss5zm1h7HDH4kLMStEMUgxo
QpO3nPiGfTIcKE4tfZAdv+LlDyyrsCXH5Qf9I/D4V08QP8FvZYRm/eV1wEzQZiOm
dC+eX4J2ZBxEqN13tE3xBWSQLqol5ciZhM1xmO96boIHMK1ZBmCQJwh3gteomhPk
9cS3AYN0SKMCDckjDdJGpifGgc+9R4Ld0AHCa1P0NuPXrWWeK4CRkUjoQreAAnf3
oWyWhjvBBXe7v/KTD6buKvjt64sEE2QoSyQBRjEl3rbCMs38Tjjs/AmAkbNeLU8b
g1OYw7GPil3ugZcJccRl0yussrc83/ZmV15WNEDzb8gbQiY1FSu63DRGsGGYn6TH
kf/K0ZjXdu+0x/kgRf4R1HjV8bq5T8N6TuIhNxPMETRwX4MLsSZKbq3Lg/Tip7sQ
Hq7VRsPiWdzdR/fvAeLMLhP6GVygOtFFQ03nNPOBrr/b87xYiXu8eILpHu5ClxSP
m+kquswpBtpXx6Yw7JFtF3I6yp/1yta/7VygsEn8NcLLuoAbT5sTtfY0U09ZN0wL
svATxMjpU3Mug8YZrtpKU/dEhKNkKY72HPKKFspLfCL54wR+NqkV9UV2GvZPWvDS
QAG2eRkCGwRqc54OJD1auXybHqjeyWnBkIN3EYWTuRDwLicDWA+QO13UMYW771YM
globHSuW6I0RFOCABmkTpw8=
=8MNJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '25cd9060-5023-43f5-ab40-e9cdfeb2044c',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAk63s4z5z/34rxPU9C4rM1UYJx5fxgih2xfj3G634jlId
7Pn7URMPo2T6HFHCK7cyl1Ky8//HCVznhjgdr8uJ3FI45vMDwc2F4/kHy7346g5l
+iQxhiMUQQ1hEQCxzvMDFD2qhIDujXY9AKa+80zfpttzMDmdtoajLM/SoRis8JFH
ZBERL9CXTmO5Nf8sBXvwj9x0AKsMRxar0O/uuuiO4yxB2AN4wfXQCObSQbUQq/g6
qGlxav/UAdLgpri3HHCop6pr0ZEL6hwIjkptD5YlfvhZKuwbj/aodu1b0s+ycFWv
4+Lgx6u1ShUNj2zOUBWl6VGn29lirbzanZjMo9Q4nXcuBlB8/30oKgdSPXK7THaK
eo7ewHdzTImurOdmBwgsJSxqGm6xnizrDqdfzH1RowaFIPGz5YgUMgOU3KSUxe04
8vrnhV8du0vxWE/4LIMqqxgHrUT9hVHkP8Uj/oPsQD8Xb8l6j2c8X0ERRz7FpxD4
ZfRkQG9uxQZQDWLqp4N2QLFRIY11rUF1V6Glf4xqD3QOsBfzg5jXcr+VtvEueC3c
VQ8GbJwVNVCVdZrQyy4/IDwbZFXAVFeZhybOPmETrJqE34tIhTJ04Hbbdtcg8Rrr
CoKqsk5wyb7T6ueX/fsvscH2n6swb6s67gdp9GnwM4VYuA8abklKV+Fg4kozOfDS
PgFWwR3UwOgch9ArvaB9Wip/qzyibDohuB7iDjRuXGul0N53E3QQiKfIfBKnxYR6
q6l3uqBr7BUwB8P+GaHd
=pVX/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '274cddf9-d20f-44ca-a4be-725fff5908b8',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HYdk5EGbOi+Bd+BkeJsQreKkBcJHftAX9Qo1RMbC9Ziv
TjlLx9I7yARYYWAoCL8iqn3b0ACumYE2iUqvl8IUGrvBhYcMzEHALTT1A5O5biAM
FgaTuj1m+ksXqzH150DknDGUPbw+61A+GM6EaAOB4lho+oBoVnqdIZ03NGhxhs4G
L+Pvyb6h7k1vDYDH0UbDw9Qtz5hDljKam+WgWO8kebeSAH7yQAaWvh+niRZVVhRE
Gpg3D/VNbJ4maUdAOmShMsPObp6sSjNTbIp57pBpdtZOc6IfBro242aZnT/Iqwf3
2qk8GDaqPn0aCDYDo4JOxoaUgRVbWnULYGf6qm/r0Leb8Ogz3rOqs0oDhX+PYzhf
GN0tsR1hi9ebP0R+to/WG4js5sNeQCOqzcKrWf9IAGsk2SsnpvDsvJH20xlJ/iSY
j2Wl0fdgSlr8IGDyCvitPBFObw5caG5bwWWIlqFxTXRc3Psbf+yOT630k/oxOI04
m+yiRWz3gwqCI2KRnOUjfT6BiExvt6WLN9cblLtidkehGSLIS4IElGgqq7HqWCmJ
3RHw4qsBcZIx8O1SFatfJkju3pUA7laNPPfyv778bP60CJmiLwZhRiObdWc2RQ9x
o5dtyJ+u3AtApf1FsetBh7R86rnDNCviJMggdrbluVw3zjwyPIMhh5MztWscrWbS
QwFYv5p+LtRYkfhg0tMzIb9xcGLNQScss8NpLegNACIKYFezCcGd6RIYJsASrAkW
lnGj4ekWHTMGTx5CDHmpHO8Dk5g=
=WQ87
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3a56ce52-a829-4255-a60c-57fad1d7365d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/7BTQ1eLzwQLo28baFoUmDNRxKyoQ+MlByvrsULj0tMlaI
heI4loWjLDvACULKRB9Eg3N5Ic6XLlYrzIglb3eEPt4Ngqgm6t1km9dzkOiyz835
3cr0Obx4nLH2ps+fZZgHP/g8BbDrQcWcYl/L3FtrjEySMAbajkQ3o3v/4xeT49yz
lxxT95QQBBJn3VI3jQtMAesTKUPUAe688gfxW08po9BT3TNNxodmSjgGRVHzgFVr
zwkYyMkAjVpH+3B/8C7PgpBOWX7b8PKmFjNYuCsIBgC1viqt8H1QLVkD2Lsm0rMR
uLbbsJRn4fjwr0KDHQmdqD2C/6Ze7KqcrXCldyhqcS+voPj81X7lNgsaxLM9sevR
63+XuryXOpf5TL1z82Vl5wQd12es5TMz3rvt5b0HGb/ZzoSCYfF9+45TR1lPBxlr
jFqWboFkpJLT/FN2O+sCWJGce0uDHm6LBXfp2Ncxx7kmeoxXyQQohJH97M/DgGNX
HcYsTiWzvsGoesH7dYnPgFVAaVwbPynQjeVaGFgJWwxwKdjc1eU7s1oKvcMqLJ/h
hpNXWuA6zIRbgNiEomVcAyo1g4gD9clsyvun1l5buV07dgtP+IZemwpkV9/8jMoT
8+HOc5OuTfiMoaYXV06Fn87Dxz4TJxaZlJlv5+DX9BpkeC5aeHCC4j9yRiVbFvLS
QQFNSO6v2qaWLZAAk+1HJ90JyfU6rKyD/FzkqCvbdcM/uLSvwsIciqXmhnowU1n0
RVO/lTtZlDSS8NstV2IQQruh
=0a6v
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3cd4b473-f01f-4b4f-a562-302618ef302f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+M6FQZyKHYJTX18IkwtolTiPwhAt0bgPNthu6FdjPKamn
Jujsfqhs90rhyk2znqWxyF0gQK0rOINVjz1E6KxSQJYVGwPIIvfxy6qoF0WDs14l
tHeveQnQh3mpwzdpDjYxXEpSa0aDsi1X6YUvHUBZ8zQK0zuM5CAbXtqA+TQVti5z
GZEB2qq1YwwCMhEw3PNxna2Ofpedj/mUw8gaWsdHhXh1dPt75/FtWhijpqOIBPaA
0TJ7gkxaQYdO/yfiMsfWEgA+ZyqdUb30393Gp2aV71c7G8f5WkPKLwbQl0guBGzu
/HYpz3C/2DzEUyKFTNurz8zU9Niur+QF5aK3g/i4okK3jX6U1Mm3E/W07SbGKT3c
Czhr0iEJCU9wZ2KeEjEFlnpFjh29wmLNsJAFsRJVQQycacNpKK/LpKliNaM7Qjsl
wf5QgLkTfZJ2QTuHPC/7/XokoE/HTEVWax/uufTl1+YI7KuIMxqVj3eeBNdleZqp
fW/vOQHM6IKef2nZ8SyKRoQ4ejpZwmkFwt/VKTAmrKzT0CN0BOxHM8mk1zUIf8F5
bmI3AQ9MK/oNS7L/e4Sk5tMhpRR0015ZstjuuTor7Am7I31ndt1pc2PoDIhewtUM
K70SCwdIxqevGlcU0pvuPPQd1dPyPXaX5lDz3VDsH6tg6+t7aTzrXtEgibZsJtTS
QwEl/v9e3zKMw2kAkGWu1REfx1bAyRua23ZJOoiFlacpeoxILPF8cRU41zEFkRku
Ly+OLqvlQIbr71CE0Uut+k0FZvA=
=84VZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3dd7f080-c3c5-4e96-a995-ef6d1475c5c3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA4fmsTGjBKiOWrxIffdK1p/i184yofnr4ycsZR187x87n
RKLzPtj8MM4WXmp2UY4Te/I/2v+UfkzOt1kVoYgAe5yFYcwJ5BFOWLVdCKgj2owS
YaubqlIy7VxmrkIfkJ53jyPmuQIxWu9UsYbkhktqUfMeTjrACN8q490dxKeOfFQC
zTu+Cb06TZ7J9kqQojOT06G7txGiqW6tppr0do6AcTy/VId81ZRSkhM2KLdA/4N2
wOht7SS2PO7BE3y3Ie93EEx62ukCoVOlHsan+DCNfuq8F7i3zFSQQ45LNd+7BGr0
rZ3lDFTcULVXlftIWTKXllJB2xf48E7se78ciVIuD9uZcvX9Zrf5IPkes0IHSHY4
7rxSEDVstafKUqwnGk1BYbk0qellZpU0IgyFdMh3+km5Sl+38W1W8ygk8zywCU0C
R7i0/v7NvKEChdxHuDfHwbbW20dAZR+bzSLe22WJl0+AnzOmeNeM7iZHgmpB7tdr
nWPNjsIfziCXx723Xq2PCA1hM0svG8e/GOKsG9jNd//bhLptMBA/OKCcGJeX+Hg8
NwypTBrJIt1ZSGa3Qq8ETLMs1xh5VZ1P5xub4mlEv2HTV0tCnD1BhrvNYbajUjCl
WDrKnz9DRCUaRhYlzV4S7wvm8p/Ssx3ULx5FJH7VqAIYw4gUUWfMx0Lw/AK5YmrS
QwEeEfEe6Db3oPOO5jJsQ744h66rqjphYQ3EuV2M45+DkQzhYp1n0Ar0U/8q9htv
2O+OCN+rkNuQ5KCVJ0R0DBBacHA=
=QRNK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ea03417-5631-4b20-a7f0-1a67d31b34af',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//YKspD6T+grdaZ5rfKJ7YCRHqCAvnSJcj4dHP0YfhKJJe
RAsujybiGB455CrclhXrjApUCXj/dhn0mPkYlHUC5o1wIAskemUnVXP7MAOVgs18
ovPfPxQWiVIvKYLmALTptGfJzpwXOGcwQeoK8mU/xQKViKPcDssh1FgbG2CLTS1f
E00PC3akoHnFGmQtibyWGp6e+pqnb6kwUpnN3bkr5P6qvbu/7id1P8zdgMf+wZwJ
hVreXsdUJNtVMW5RNzbxuMe5DczVDoG5Dt37whlARTs6Rncvy+jnizUQxVK/YxLu
z4FpDOMRd5ldHOe4AbU301l7FR16/JCuRfcbv0CCoDhoouSf6P71VUclHllFlFd4
nu98VP5f0fg3sAPPvgIic72D2iYRR4stessVPpV7WqbVQWaLSHMka7z/C57l+Id0
TCw+jQ8pxTBp1YL+vgqU8eU8UHQxatke//EGPwjZTPQWsPY+AL28xuZ4WR69Hshm
91j1wt1FalrgTKbFa0HbSClcDyoMAdHM54yD/XywCu7z8/Y/L/bJb0NJMfcaDvUw
yPh6ZkCBMXCGAMPywTsWSpEJbgUBCLQXw83HGW2+8NijyM2NNlXL+jM4woN3GhUk
28IJwCmTBHVMAx8EFDq1qCbGO5WB5DNj88L1IsiWjbDK4zSoJRUZjnck7cwgo+DS
QQFj2dCej6xmWkifyOUnKLUjTYjXSRLz/gc3NCHrCIijeMwsJAtX2A/reHIDf9xm
8igPSMUkOW48gAX/N3JulJWn
=MXFT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3f73df43-ad25-43eb-a7d0-468fa16d969a',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9F3ufA2oJ6yvMMMrZjf4m5x2+PpyZrtgSapNWv1KrKlvD
cMQ2yrZEPl7AAz3ARCiqo05vsfLeHCbeNNGq7FLR6se2dxTMuXWnX/NGxPEUm6mM
j2f5tC/KPRIR0879nwVdfSNzu1tDECAw8hT8UzqTGepCyI3JPCEqk4BGZBR4Npwt
6GLX/EtiSdWm9+SdxqjWB3Li+ahVxxzROm6+WE7w60SUFB3lHw3OUdKdMG6nqXy2
BJ58WsgrGVq5Hq+fYfjUdwVjW7hIlZsU0LRkWaNNiW2sc3zZqkF4o3zIZYsyltuQ
uQeR5ZQCI1LciBLi4+m3aejP2DD8i71eoRbJE8XQja+6qRrZ5Zg+WnhQB5NpI3rJ
CCO3Jj/x+reCGjxwWKXoZtWJYNAY/LBN79EQti+QVh1VLKxBymAyD13wxiWPstmG
UFK9B4jEdLZZ9xo/hv4YqKGEJLrZGAgDzVIqK1ak6XzW1yhXFxrajc2/CcquzoNl
CV3anzXcbnVaEGbcO2Y8PTM2qArFf3/MYN9INf8U1rVYEhyOczSh9lzkIocNjKRm
fGIr7EUDU0Ix8ie5fBmDhm2IBrXKYVbpcdSzamdCFAg8qhpDq6LarPe/HU1N9oPw
ifROitbFo4S2yuHn9udwfHEEw/OkllA+fB8QsI9Fl8zdfgwswXAAaFgWdQUsjeTS
QAHjTIp0RFYi31hkweNG23kFvcPyD5y1NFVOWRcoHtcVUSI1C1aNXCMjNbeNuZPZ
Lnrlp/kIFmS1+DKEQL9dwL0=
=Qu81
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '400c1f63-3267-4fdd-a2fa-8462e7c43df8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAor68CpxQitpRm0QbTbuMEBe6eG9ZOjQ9dUFrSLsC6xoZ
3oDzKBmyrmE/JY6ey2JPh+GOwtNIVazfMlDLc2KdVzC0fPlxgRfL435BiFOWe7/U
nVhvHu+isng3Y81t6viVxvzq6V9nPWfufWG9ts53k8JLaXz0ElO1q8y8/sjYJH6I
DCUfmWQqpgHxPpPxlRLvX4/uErpPPDO1D3hIKyPMA1IRI8gw2nT0yS9alpcwEXSj
bZNHAQEeqQ1xnQGY3bX7NizgsVQJazq5D251mQzEJw71L7f/TbHNNADCGyfOqrFK
dX85cHSgquEMXcmrL4S+0FRpp7aozGCo2+1i0bYh3ZAkO5FetQVLUiDhD2vc6L93
MCl39lq+53/gp3FThYaVKimqf4Wq8dT/AlR9zJDnbRArS+trvm0k2LmUKH/moal5
2Foj+I/3nRo/5X+D/PZxWkJdsl60Avv8cAZCL4+0vtAAb9i6hdgaxkRalZ72DEdk
WX8uGLh4vVRx07EW7sBYuLexxVHQ0UbEAQxaJZp0LDEwFOj25inDYMlIUp4kegsc
flu9bS4OEtoOcU18ZuljVERqqeC08a1ZbgjPrl8IEMuv65VFlAiYp2fdXf4qfo84
kLju/z36kVh1Fauxm0GATNN5rY2wNQ5QQ/oj5YOpJkPgJI/3UTomOcp1ooZ6Jh3S
QwE1n9FQ/7E/PLhzwwUuMDamgbQ3mZosSEngplnS6Fvv5tB2Rr8NyV9KY08vQENF
4OlR3IEr9zccEbQpUGDdkGU/whU=
=XGfq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4ab8b742-9377-46f6-a335-ca59fdae2851',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+JM/QXbjhT1rD+IbweqidXQe7z6Oe4zk4CwX89QjYAq1m
wbJZ05fEWCMsF4pnHm4GUDJsKJqFyznGtzYd4xsg5bPPosDBi8BHMBCfCJ3uiFWU
cFhCvhtQpByvfWyKBdqfFFNQikfEFTB3aSnXcTGKhVwT1CItwYTEr1hK57oaYx06
8crfzqU+VdXMB7j7dbUtTktOJhvxjPsSu5BHVB30qT5l+U20Ro1rfFRsetMQQxYz
dEpI+zWImXjpuGEvn5ozo/7rhl9fOO5MUNg0SiOew/jTxhfFihJYDYlvJ+aKazp9
BCzQo0JX/al4NlOR+JwNe1ECQCmNaKC0W45F8MVDp5dL+3GSjRkZhXsaPHcBoJq8
2XXFjuMM7l2IjGOJzkFpFHsi455IisBsNxr5yOLXc4xPqLQ/jH5qdqu5rHdiOzua
HSQa4oqApdqv4t37kJHmCKIonICBSeImU74Zt0oqKHaG/ZcqwDLzkYLfBlRwrHQj
dnXWsP2IBj8Jhjic+sP+3HjGiuecFkn6xixoMtmPkI/U+Pa2cRcaf6y0oB0crsCu
GuahVNbXHLsyUoq11qDIqysQH5vWM6UPo9X7xxQ0a4Cxp8KqU0JGxTQ4VjPUeRWq
vmz7OaQJ4+rphXzINiyb1hH6C7MI+tmYOex7JcUVV4kQAUPpvMRo1y4Vu4XNOITS
QgHf5Ve4DIgSNXshlcOPs0etJwBZI7BVsaDRtISRgtqyyfDH7HHfdG44t72onr9m
bh7Fp1q+z9x5Cp/MEjToSZThsA==
=7A9Y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4cb39ba4-edf2-4ec6-ad6b-5321ff017f60',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAsfxKj+7TZL4lNYZ6dSrC9EqcXDRnMiYKh1YmirvOE1Jl
G9HMrBtsdItghSqLB4cb0l/qAQZoUNU59RAt0OoKPeHiLwQwNM5ETabAO6q++xJS
TnwCe1JRJ+IoFD7xlfgK2ISaX9nGAW3JBL5K/bxSCMAfyUvA/UPZygo3+YIstjZt
hZyeMsxNxMncwpzd11tdd5pOIFjNf0T4hDi7uPMEFIY/IKLK3aJ+Fvt2E8Tm+Sz8
quecnmqdA8wjpYQxF7oAtxLfeYTQGAWuiaUUIQoyVi5b8MbMoYTTo8lW8+Mv5EG2
zSiqrtmrKgUJoi4BgrAZxhb1vdkwZfl/VnVT3imFcNI+AbRJW8kEu3778MzlE4PK
pUwMmrQNyP9FZmnioA2kGmCLqVXh2uoWRWEF1eAj7Dv7n/WgejF+aYoiYskCZCw=
=ioaD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '55bb8cdc-6fab-42bf-a482-78cd01f1e662',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//XvYA0srAJA81eEzSOwIM/5/vTz5dwdY7d3PihDPYY7f7
w0una6MhIoHmJlUTCROApQ7+LJ8UOBikKmJeJvh6ieVna8UMZqF2ns/v/nwoF4dT
leUMttOJJTckt1pVDEaFfwqyQ6hnDNbhHhWJz6SisjcCACxBLCOtZy9xAo9mX9KE
3Ev7NDoz3DvrVLusCdYEQm2sbg/j6WQc12pgddVn7oZ3wSneWJ828+w28EKc8U0l
LSPy5/FRAvE2JXwqxzjD8pNBUHjf5UtRSGRF182waUFoMRI5iCY7OSP197wSNV3x
yA9f87FkkuYdAUwIl+LRzMNr6OEB2HHLGubOit7nkgAkdiQHdjseZenaDPK2i0nb
scURvvn1YSPslT+6Q3tKoWf8K+upm6zjA0IJjRxmeM2qkn5RC5mNECeVsNS+6KOW
HDb9rupMp6BYspItURorHmfAwi/ichd/sJ04T9gkKAzneX72bMpeVU6pcDXvB8c1
ZbLO1F6kWSHmMi0q5S7BssPOPw2aWHkMnxu7lE2owP3MG8GJaiJYFuMha6Etlg2J
Vh5GOhR/y7RlRqsDMmyyZM1pZtBuGS8wXq/iyyrciTaslvfcIMedJ/YDx2kPl5AV
LRII5cBQkQSLpNuYBp7kjUmRryxNuTJfWL2ONs3l0110mEETzDMNJQU+72AHA4bS
QQFz7FciOLyys63am3r4xRwlf1+VwL7hMBA1h7ko2j4ByP+d1+WaI8dJyW07/FWP
Nvtz3EXqbOXn3L/wily9Njlx
=UAA+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '564d2b74-df82-4a75-a889-c496db1cc2be',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Q8+RNxgVAhtDmus6HvTax25RNw37HaiWiPMPtiTq3lMq
NqLC58Hiwzgk2EGMaBK/YIAE4Wf2CWN8UECrTDHQHbTDRRw1UNRDyQGqLWhT81Ys
NgwCkz4q/tZlG+m83kC9Ja2OHgbKnpRpRGBZpAF3jwsjsXwVRokAF4LOwIQF3gX6
Swg7cGmBUmp4Q0CivuO/QgBo+lxwmOVS1C9Fz2ZGEsbv4H0DlGJAo9wgFbeES4ML
0/tKvROZx1gqhBOl7eHOoOWMo60ultq2a+E77Ve80EFG38KDPJFuSQCgg3+oYyih
iq2C4LITmzQIqGfr0Z5xq/I1/i6P3qmY+D4vdx2aa0gLq1xEbIdA4tx0qtA1KQe6
nXIS2CEen3nNBLdcA5tsoIfJ8aQ2WhLjAwgDjEbtN7YzUBTuy01MyJxwiAH0VaRg
rIRJgSEG90A3ARc8kpz4uj2bjF5UpNY2uEShhcUDDAxP3YfspA0fZVPHHcCZZJeF
Bz5GicccthhVQFS7JhK9FLBjQd3PtsOcUjQgmXeL8CeI2TIqWd4JDS/OPTm8Al2w
RJdrJ2dpSWOB4VGoAhFZ9aXXEQbG+rVry1hAxUm26iWMUTKLslhVo2GXbiZvQ5XD
Qw7k8pGwu2Kc2FSsreFi55pOK2y9I5NLMXSiQuyIENpMqhNjNEKe12RMPys4a+fS
QwG8kbSbyhw00ngpCKiZIMipHObThNpx82Z9ZOn/SBpoEMXDh7kCgvp8CodRuKVe
ZxkNehYWcBnJGWUuO2ZUO4wUBts=
=Ewkz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '621f0c56-995b-4a19-ae94-0abc8d63b71e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//dVv85oLSJd7TlThgQ5q9PzuuVQH5gfgPMmVkDNKbOjKz
bzCAWC7A/waZSOfAI6mhKPud+yroYJPS+GuyBCLsERkBpDj8uI4eTq3LfkskqOU5
qajiIL0COGYzTsyddrRzXWoDYcy1oaqGp9idzbys8RBSS7AzPN+fyBEsSSdXTwpN
mDJiEZTvPYh2lWo1dWvYIXZWJl402KueUFylHgeCPK4MEgHZv9Irb1ZAm68awq9C
I+nJWtcoiQKh3xqcKjB0SVHvYrNtN7ExqX1lJmWRM36qYwkL47SFvhe/YrVd3bPP
HrKa3i/mr5VNjifsq/rtrZ2XcTJB8EIy0QsG+NUqXu7vJ1utqLIHtkU1KILNoW8l
0V3FB5uiRVKmfYlE4P2iBlVAHTc3hLQ6voZeJE5nijogf6MTCImUccS4OIRljxBn
itePCtrGS6IVOqf9Igu385km2Y2xKxqZnrPjGyxbUGVpEwtbXsK0pE8JEfglCrMQ
kHNtw12skFF7dEXvF2f9mUTCLoxnSX41iEnSgO8V9/vxdIpJLw9fjceNudJGdNYi
0BmAmSsVQmqr/J3HXYB+myHemLO0hCLijzF4ZNlvj9cPpY7hbnIS3lMi+p8IlCsG
zvqKjQ4wKsTz8NZIfU+nNuCffsLe+oQohOoKzEgQswWRdIAd+63xgzaWOoGvCF3S
QQF+IILAjkJHmKIdL0W4/L2kkkKp0O3QvZ0meiluo8oYhvZNXi5s4UzzD5eUibpr
bfa/vOKrGqseavuv/sk3D5Zj
=t6rA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '65e9f48d-24f7-4380-a73e-099286007559',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//dOv2Ba3AUnjLhozI0LFQjpRHxeCyue6u3p/NTamT5Dbe
w3RpG7V7Ibr+vmL8P640JssUWGt14pgef6EsPWj6bnM6h6wLVaOBmHsCILuYZRo3
PKrj+qe5iJ5PhT3iZgDMNw1w7I5XsKzYphIoaPw6BTV6FN8hH+euc9B2Tak5prOi
YvPvGgMZeec2/mNNOpViDvBrwmZYImhsDz90cHcq5jRt12gUFUUuQuz49u4GiUrd
jHMDN1Mz1T52hY28QvJhSa8L08NZyg8j/E2ILY1eALy8+TLjsSvYr1kwDQrF7m2z
hSY+FG83oIA6K+fdc4OqC913DAtqVcWO6qLcnF/m8xqd75IM/pgz9K1ngb5px+AV
3bGt6dDj09Xr9qtG5br2vEeYpEBYtlKAZm+hk5wdNyn2iFA1omiZbEXfNm7Oumd5
RucLwshtRHnrqnd5QIX736IEtK9hRbx866hKPNaBcpn2i2sApAHFjH7lZ2Tg4Viz
UZZN3JuPDDjacsnv8i9SNTnHwBbQ35JvM3M6aIRQF0cRFJ5tfXFKJSWMwNDY+liF
ga7HY/yjyZ5j8TpPfJDJWYaSgWwBJe5+fv2u1h8IOVdUn8kVWJECW5iPqSlqRHIH
NZjq9SOai4dcJfv9POWVOsazrRBXzxN/8fuSpcjECjFKqf7ExfME29cyXWL+zn3S
QAFosq5o+hAwan9WaykQ2cpy9XzXYQnyssWq6sB35odOXZIunrIc7xoanL1KsH5X
sIBXt7FLp2W5FsjsBybhX9k=
=b585
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '674a40eb-2c8a-42e5-a27d-8ce717d1eceb',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf8CMNNMUyZxldsuUwdm+S3fAllnclR4Z4hksTewYPucK+p
KPjzOWCPOUVTkPMi2+7G410+ktRttefRgI/ADM160tAvtUcomH2KwoMrBwFQsiIW
THy0bDf9WFNdirzfEA5Brqq5QUNaV5D0PKEMLJlWMVcp6PrmesPcUmZk/DAZ/6fN
M27jt442XTaui47keCX/zgs+aj4n9sFjEk/H22ki6aZuHqs0tYn+KTy5rpjLyQ6d
FZgBLQpRhgsd5xFfJEYpWxYy0DECd8NcTw6Rcth5sK3f7eZLWKVmw9CTNNiZzCyi
paDgY+IyvlnFsf6rAmcoVeBRSmsfX3d1AFyojkrAqNJBAVbDbNLtAGCFtJBXrN51
FNDfVbB/ziW/yhSbjU534aQ47Pr6qJXowjnWzj6VTtfcILnmPDedIVj30OVFDNYl
WOw=
=I//g
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '774af642-dafa-4af8-a26d-890eeea60fa7',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/e/2P9voMYiTKOhJHSstynpNhfClpXpKIZZ7WV9hCDCmf
78/tk7UD07sQ51pjwSCyt6Q052CxeSx6FKC5QKTQpiPS1rEnyrSO5xOy2ypvpVfv
tkwnT60vehlowZjNuXPDIZjI9JG/R8DgYotGvyKl268u6YnsuRwsitYugT3PK3Rh
6MNo8cVwzg4ZOFGdyPYdT0bzZoAki/rNUDlACg8nn/m4zEgrgeiM4XtRD6R5k1pR
pZj6ydMpmIqq+xwFpYmJ39fnHIfkI0j58fmLjp+hMzyD91sIUi5/p7Nid1L2pKo+
8UAfj7bLe2JO/dPR8tF97PqGyp7CHpPt4s1ev8vo+9JBAc3gTLy5J20iYBKk4ZWd
23Cz5/Gx5xEtZTvxajY8u4Z5URfq8pW+0PuRHtcrNtu22SSu0EM8VX6FN2dL2f6f
e3Q=
=4lCj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '81f25bae-2edf-49c7-ad2b-de0cc5558354',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf9GfXXnhHwc0hST68nsGsg0ZK4B4pbOXnTm8rxP93dMbO0
j04HIIegZzDKIbjXyYObn93VUPFdD6xRTSAU907ca/Yj5EK+bZ+4TvAiBdWvf2Oz
hzCxcGNIRk0e9TWCVxzht9uVjUtqhM3e7Zd/2amm7yp50aBs0ZRK94iOXcdNBZWU
g7mN4rlKCqXjc9+mkkwvutXCLhz3huW3KNZgJNRwD02RIVcDAUXgTbwPPQ3x97yF
CmU7gl14RbX/EH6C5WtJA5VxtdGJGqSvS/ypexQ1MS/oZyVLiTKRFgsoEPQkPy7S
veSRBSy3LGp6lfX7b11H0qalXhJluiHiUz988moZHNJAAfbVHOT0bWElCTfrHs8u
0iuLWo6GU50ag3zdoBJOeGxmjP6xAEL64LcR4Kq4Kll4OjVQTHCTkIYD9FuaUptW
lQ==
=stJc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '824ea1c0-f0cb-454e-af15-9f3570303be7',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+L7V6Vz4vOMIC+4ZDkKoQwg3HCUFmYtob5RrVWA3yHCdR
dDJWnJnXWwO+Zky09LfwE6Jdjvbsq5zUgjDJGWuiEAe0bJoN8FsH0tCp/BGdBDdT
9wrtNKiZhMEOZZHpuNkyZoCi3VzVbilMb9F9qfCBHoW9FFjagylelleZOpXFaLNS
en8L6m5htC9+FuRa3dttveEuYtooyAVKlm3RHC80D2XldrUp7O9wmduyZ+thQ6om
Jn3voGZE20zDHOzaOvoV4YtuFNSi+f4L0U5bwr5J6JZvFMD0BKc2C3MeN0qJyGQ/
knUTjiB0CKILc3uK3VBqyW/u+CHag/3wk2+GI+xqL9JDAQZ/gmyAgzUm9xbEhlA8
zJ/uWUCUBr11tsiUFz7acpG9f2Uf5BuFZbLBxxsuam+ObCBmnxoXfeJuiIG5bhv2
GNKbPg==
=QZEf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '84f282ec-1066-432a-a14b-188378b6b543',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//U7svadt5zJIZ+rtw3HW4U1fXKvbiW2GJ8EF8HSpUN0gx
3AGoA/Vfi9gGIVQ0rprw2rENM4GiNhAgT4nJeaHnFnHCI+UHoGYi6Ffx9RPGG0DI
v52eQhdQCjwh9udpknqwP/Q3iaiEczXS46WpGHegHSnJwA3xlFKn9A4zspxwS+yU
vKmGdfNIYXA5jzlpy3UydsK3yizaEa6sPZJsnX12RGdRmguHzeyJ38lKh3TfI2yd
DZxpZZmWibh4QrmD8C10jatr7Qp3AsQNk+8ozlpoClS2u7a2/3+Fabykbrtk8v8j
d4/E4VUcGY262NeClWNx1FZsNxN5z6aTfY85b9pjCaiCG5BXS24TrSA+tMAOiTTx
BwVyVlpwpWnuPucl+x0RA+K0IAV6lw9uqx49v2R+gHtBqtAUG/2uOWve1wyulaa1
rwraJ/SaihhGXZiDA9EIavw1a4QE6Q+FDwrFxCo8YaPxDxLxiC+DHgoo/Dya7LEA
J6nrjpCUJGUjfyiRXZkLRmphlwyjASKBCio2zhrNqt1vF0+Fk/OfUtoJfrWwsD/U
aD+c3xtQ80KFhfQbLJmS577+sK7nwBNfGeOJNTA6C9B3g4lasNy7psoioQCiPhML
BFtzaat0bmCnLSr62UqkDGviRh9shS30mE+tcOWTXf/ETmY+8UFZceBtmHxLw2DS
QQEc7RV3LFYc7l4R8XDs/KU/uxq4DOXYB8GX7024/2axZ1usj50inHoJlYloBqhP
uo7/bI52PT8jiYu0AkTHG7/Z
=emsq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '85810174-a4d8-43d4-a5fe-1653d82b510e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RAWyIGzVbLiXVD7BArw4JDKW7oCCCAU7FeG1LX/EDcKq
d6NYOK48VFMsDanwPqyMOjoGIMOmNHu60t2YCa8mqSkL6+EDwHkZMX7zBgZpILMC
T3A5Mhd20qV+faXx35IIDy1gDbZOFiVrKiQ+jVOwBVfr7RnVERPEp9I6j+yTRxgT
3Y9Pco8LSsM5YdH5jBrMYIM7K4aTNzdr26Y6XIBAGzm9RLbiIcABTIPttaZMFdNm
MkXC3ZJGuFe2lbnmWhbsmgr6EJ9SR9gpvXsn66Z7lZ7eoXpUbSTfhxREw/f5y0LP
6WTtK8StVFN3j57Zd6WGYP3ciICrNppyT4G/kQWXzwUqddLrw3uzJqdTT6uqOUpa
vlohrib8BZZ/2n0n85JBI1LB31mDX4UfRCfw0NWsdFguBkYwLLs/29wL68tKvPuJ
dwSxDZ9UkyvX2Xm8dFhxpIJ2Jagxj2ZvADUqE5ekCqx7H2R8dWP40Vzp4WIVtY/X
FqTc5HHAydELKnkeaHuG8YIJ1f/JWhY22ktXnhFak9fMXWt+ikV4zsZlEyeZ52ta
n+pb96YHKJrUrGSbDLodTDAtg/gDLw4hiNTAErwKAhgSspesL1V/SDbwX0+YGRCS
iFCJyH4P8kO2C4plfFLoJXfZhUiWa1SHld64aDGo/wy8NLUuooGEBjFfYQElL9PS
QQHBBwREtC5hVpp4Tr0CZZALnSo7rc0jAZd7ZdBOomu+ov8ZtBpmgOAXCXbKWUVr
ZSOmvHANMbMA/YjT/t/bXPpA
=BykJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '864e7a7e-d13b-4728-a594-77c2b544af69',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Z3RhC3e+pQ4BCuqHw/X6CkO9ws6gVDvxyfd6VbpGgi+K
zR/TLTL9ENYjCEdpIKnAVgYVuHlcIiKVDgy2Fn45bk4uuBWWltiT+/ORSiZ4ohzT
BEnLCrUpnSiWs4v90xfZXZzOjfk+TSCbUG6oDacqidvFHlzTML/uWfiKfUohMQUM
t7VZtfYPI0pwJpu6HIMJQSyFowwd7RjAIfQbpkL1LEJHcPXr7TdmZZ6PGMvn25iq
g9j6nBN7lQaRPuiTWelxAxcW587gOQVIuliT8Miwp9Bf6Gl0Z/X9OZsfm8Jt4mn5
4kICbtRzyLFqYElTfX1YIAqZ5dntvJhbM1U/x4PpXdyGPlyKXWAtHvgjclH6oLNF
WpcmO77wTi7G1XPDwLJC44dJaAPtU13n155kLjtchtxs/UJQL1uqjBw5sQnocO+S
q9KEQZZZz+Lmr6kA7BcqMpkjLaR0RU0UP+jNeGSZptu56x4y46CzJr50LT5bpVHq
s45bj8bAiLBWebfGWIHs5Y1HdmqCM/0mzwq3Ne8LAm5q4lpbKLE91pAda+zDVbHv
5A8H/IYp72YJJnioWcefWmC8vRYmz49Z0QSqB6ECRvywQlm0X/oFiZr0KCEqSEM7
xtO2Uxs7988APHSo1ZmnuZ6/MEMRGVGSlcFvnfmgrvI2PeCZK7QraAVkHgYr/jbS
QQHuV7OOdxsixwIFf1bidzp7N7OkUWl23BwKlA03EdNQ5tTNCyPFSJbH0ZCMf3aU
09URtJlgyIifZ2uD4Pu+VpwG
=ff3T
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '88696e35-801a-4d63-ac0f-5b1436cb5343',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//V4Zl+cKou/zBRNUeceqeEq22XFY0dNyX14FZP+cy9Wdl
QUG+Tyr87u/htXG/Bp32MlYvWwJYb5qBbZEsx6OMJBabL80bfrdCE1HMe57BI8qT
Uot5dpmTAjtIpcYZR6OfCM6a/P/oCJkPimmqsBSeEuaV9m47jVR+BwHxAbsy9E5G
yk9ZN7JSXrVRw5tzXdask/Mns3g5JT1NbYIRLUCIcNBwZ/8MezZ0HgCpsM0IvXnP
7jXmk9mnqQGe9LSgJNezvIhovlWqDeKCIJ4wTssi731g24z0VMUCVI8a8qgvbv4n
avAv35UlCVwfQqGzOcupE30XiB0foq8aKG14+lIBe5WSLEs+RGdcztnopbLhJF6k
m41qu66tIUpNm7V380rJm8pV4lJQMmEV6WN2TjlzB8lw56kp2w1l4xHAj5lv5WIV
WqKtvbcQg8ZLBWf0LPeTS4RpPN1pJixRzKiAiudT+JJl4gSWsvP1zk/J90AdoFaQ
hmvwh/K/q48qztWAyWEDQ+CpFU8ele7i4tyWIbm80YZKrM1GGGp7iUv/K/WW4Aj6
zwyRwi4DufrLr6T21p3odHdIVOkDGEz3JE/Vp/wW/gVQ66Yq3DhVnx3kdx1pStgU
egCVuqqJBHCS9fuQetOHFhlz58luzPd0coOmSClOE1weI42uD+vbdlFvCs9QihnS
PgEmkGa6H9QHcgEQXo1/gLbAYuQUpQooA/9RXzrGNrS8d6v/cvJFHwKkJQq2gFZK
Y2m33YgNC9LxZep0LlsH
=ceMd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8ecebf97-9566-4da6-a7d3-d4e0fca4a242',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAmYA9a2GPLe+tSXU4GOWJ+BHSFi8FgWnMLY1gUklqKsKc
SEdgepzB0eaE3T7U6d6yJF9ez7lNnDEVpk5rU9cB70JYytLbp+UigyR6KbHXLwbu
yt953nsmkoxgeAKgJS7BHqoJavVwc6yHeI6HXcketluDeem/w5dgoADEwHqPEs9e
tPHwuQYlg4703BYlHt/cbI2a4uQlFbL78AQNSlD2yPwzEIXn43DK8QnptjqPdX87
yMsYa6lDpBv8cue3uKh4uIQrY7RQqr8JOstatrVCVnwBBL2kmj0X3TmhKMpWfGod
7NdFquNs8LpdXuWjd5bPKQOBB2ednWVQ+WWbfhkUNuXrR6R+dkp9GGL47gXpZ5fU
A2o3pLrcir76Yw5vChITv6ofs7jOwFcg5wRKciBmVJqS8NJZsVUshhLzcYWqtLQG
2/u+6phAZ4i5MQTeu/j7ekS6Jdh38QCcTkrsze3hh6Dw0bEDuccFIAonvnA7Ql/R
CdgFpqwCX6zDrGUchxbFQI6OQnKJ98Q7+9FCcmbJGMJLwPpnW/702rQgYaYMMTRe
YWJpAP+ICsRgAyYXa5FOXPQfDpBMHnO6nSCUhLhS6YpOVG+AptEzHFgtkInzKANX
UcdwFvlGiG+QL7HPK9HuxmuoJckXSVw4m2bGhFP+7JrMZvbrm+MNfl+Qog0/x8TS
QQHya3f/f1dw/r0p+ecBFebmi7TMe8NyNzYfY9jemAt66KssgI1+6wWgtcVoFzeK
yEu0r3qQDZCVQlBDT45IbcZT
=k+IV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8f8b22cd-6b7b-43ba-a485-fc98dd5c8aa6',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAoRfbYaF6jZWUhFp3qrIbPKVMLU9nec3AvhEoN12U6Hba
4dthTz+VzomQjVXNY4QcpYBuSn2L3PnqiCTYMDs7Pv94iW0e+zbER6hY+Z4qICAt
DbvZdNqiCpY+qUODkgbpaxC85kLjrzCfG7MIAu5aXEHR0qJRhpiJM2MwFuKA8rn2
LH6I0psKsnn9ar3+ESKO/0uXU0mfyQuM8tkHO13ydzh0xJECB4GNuV+Sx+jy/XFg
ckCgEGW+eA5VlT88f9L6Gv79IOBQSx8iSc+SEgikY9qYqAomYeM+uz+ANJpoKRfP
CnwiJwl3WbQSVQCndKyl4/hrC13XT90zwqs8uxvh1dJBAQMXvsN5vsl9gn+qgrIk
/Jd43mVjr917EOIvUo6GUjybBpUUAJoVdfKnTBagIuNjHX5CotaYPQbgKQZsdjzf
JWY=
=42VW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9c7561a4-1098-4283-a906-0f33f486170d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAm2UEKn6XcFDTMIrCi/HjwBq/qeOWPKy8qHT+rXqZXDvs
uQL2ITaF3ybUhPZAFzsEBa4e5sxKcdObSCnXeJVQwWnXLj4in0s6KUjm9RjeGoZw
/57pifaociCXdbHOggBbXFOljwkYTUd7mnswiNK7plIkUY+CMgSIJ2//6FmZOZxZ
5v4q2EbR9KvCIfB4Es9trtZ4IC5V3oDnkAulS2D9wdac19hgWJ8Vd5Gv/7cAk+Fn
sSEZmdpGDhXauX4FpktdgAZrCIAo001kaeXYYXBJ/QuLmycfUmxQu12A8tiPNGvN
MQNUj++RTgGtKiv8qGV6gWmzqNowiakyv5TJFo3o2co/10mpn4t/IbbkwFdEfxza
bVXAslaBSUEcd2y2SlS04kZCF79n2BFLZsqoHVG6MM285vQOmLFCRqRVC6aZAIk1
hdQEIgbMqL4N2u0SHyfBECBeyjFb7M8zHm6G0IYZ244rIiEUCXBkGxY1s4pdBYGT
w4METpLi1NeKB2LXH8wF8oTewPyZ5NqsQRnHDSWyPClMidwnIEwvMggzbIi8wsaz
BIzk8OU+jdFKTXhzu82Urcq5yZmeIqO7gTGISwMp3lKR/sIgHGW7USABnphkGnNp
6314LIv4Ilr+rIxvc/hMhEA6eWY+xJMvHGlx747BWyAbkkya9ylWmElQrX5iPXvS
QgHc/YN3EsTE6H/Kk3BAk+LtwfVcVAcRFBIb5ezoB4nRf9IAGnfM32tvKx5G7lBT
wmZFlKReUAoJF9ELNbVh5/eLWQ==
=aO4N
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9d4cf547-88ab-4b43-a276-7b8427e21001',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAoFlPI4qPtzj0fJSInmQiW113UvX7jQD0kyZAa7i32QXS
yQ507pBET0cPQNSQ4OcMxdQ8s3u32LCrqV3J/SvjPNiaOENpN+Yw57dQQ5tAfmwA
vXdYP3EDxGAoq/6ABRRoPJ6lpdg0loKsQArNfPyZDqpVvpazvKKK8i5e6CeYjHBA
n4i4FpRoEHRe6V5MeHbovZi4OlKPg2LrughhaGNw0gDg0HbsPBvlZ9lM8YtYRw5g
QfnK89mowQOa2vzht8asaqX3U+6L6gsjeDv0dKYzX1C/pi6KFondxA/G83Fmzs+e
xZ6QABYjvmGVS0q5VWo/3PoHX1On8qIjgGnxfNHNYvn+17o22UZ01zk/yzjZMSQV
ujSS0lyn7v8PvyTticxwmzVlpbWPLgIDCBQET2EiM17+dT69q+8vOT3dPDWC5C4+
YMLmEVQ2Wmbv4XgLSMJpYJurkVzliqHwu77xSzMGxBgTMpIYSgEsu3GPhQejV8lm
SPNKtTsR+K3g8fUKacbwmI4kBEPW17MMdDS8CXOmqhyxgX6ZVepwBS4if/kJD5BM
rx1ttsF4Iei8VZZsKhiEPR11Sb3sy9nC/DypiB3gg1VBR9a4LYcOlgsyrzdOQfSm
T5H5Ra4DS4QrnyS5SofoGeBEuHdOajL35ipKmzj0kxePX87veYPOQdtupCH7+iXS
QAGtp6Vz2pDfJLz1a7tA39bulh5gTtFfLEqDYwNEnhiqJ6bxjC3yQ6JPDE29HukV
3SeUH4LIQDFzdFeqEjaAaNo=
=8vUC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a1789a6d-8f04-4a72-afe6-b14c5a77e03a',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+JTQM6S7/ILp4m5QDLAZzVB5HbMmGqHYQXpEkrFkgyDWg
rdu59L2UL4AYXPvB/0FjgIRUeb1NL5/zPNIlXKhwybslkIYEN/pgs7SPSNVn3+qH
ZaFBpD5WH4lJf9orVNsuhjCN2Qdnm7Vb4rtXEeS+HJLKgdeIh1SIUMBFqCsfAjBb
yinT05qQlZxFZzl4Q/04lj8SgFJ0uc5bWrOWiuFLWOQc65CYbAUiC49KFkKoOQLC
qnG/J9zRiQzrpgs/H4ukULEoccN8cQix38BHPY6FFRoX1M2jI2H1fkwohP3DxcHd
/+BGktgza0feTyIxHaw4wbYjj29Eov0LwO6fVg1wbdJBAYhaHLcbabYbGScuzK/v
fP5MkmW6dHZJZH7vHlfTtkbIpmYMPEQj3cR8Tyz2USSHiJmPt4O9l0txFIRUr5YS
Z48=
=/N3S
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a470c894-6e65-4fb4-a5bd-f64d0a5a9c02',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9FPAXWRWQP2DxD+GPwsqbOyVjm0RUnsWGMCL5BG+AmukE
2dGcpJWNgRaodpnrJxMZmkeRcLTRjIPSl4WH9OLXd+SmPnH/zlyzH1976DlVzkq8
rCB6XTtOy46beZb5J/ztjhh11wSz1ABIUv9Q9f9Fk20rsLn1+IY0MhC+D70xesOE
/qaUuvS5WR5e3zblerEBjvn36/XfQxk7MLa2WfmzxfWiKNEkWkETxOZH63DB90tL
HfMJcgTppxhoirMfMYIe7cW7LBbXalETotxBoMWbfkpyGz5pvy/9gXOgSgGPGH3m
NWB9v3qMwbI/QOV8Mvylav3tu6b/xPgMMwmb8vMdIBiU1t9lt0haYU4V2arXZM6q
PW4H5xoiNbdESMBQWwzEawatBUUAWtHKc+WTN7IdcO9srCTq7SDvNTI+smPTaZVY
uPDhdmMdfC94aJojb2doB+BXAOHaL63vRA9BLLAgCDaaH6pOvQXbhDUTMmpFzXw2
Q1tv5KCqqAwixebM1S6lPwa3ohqMBSm2rNITHTN/JjUkXavTT66twgDEPlx7RVfH
cG3jbN8d0w4BKbkfyPLN8vcPN/WD7HKOa6ByWXTtjIX+LJl87YEVFpvsn/YwTnf9
m9wZkMw0WwQkdvefGx97+DserVc7axRJjn3FUg5Y1EMTRn2gv8AMX07dIKR//S3S
QAGUhKtQB3UFqiX5zkJWDpfTMGcVZsb/5URZKOini1sMWBG2gKEHgNhJ+I2H4/oA
5d6hPFpvKLkmNJYtvC7AunA=
=7wtL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a5a4c21d-cad4-486e-a249-8cf75fb790ab',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//d1BrQOu6+M3Z1Ugx4xWQe92fOD/DYv3u58Qwko4hsWVq
EubzXL3/MVUHSMPZ7pPIWYgu4s5k2uqe6NN/y8rvuJN38GmerPqOcXv9mOB/C2BB
xmGXmOXFQv5SHHdWP3OWlGE+YE56R+4kTv+0LeIZYh7jpcSe4O2OCOhX37d/Lu8a
jbOUUvv9/Nzja1tGV4geBtAZDh4BsTHaiYW1Cfd5X4q6In/h7SHwbGpX5U85VTj0
Dub0eFKV0LwYus6xrn9fXC/UavlcS4arr7/56B7Upzal2ZHY9XHXZjesRtKW7diy
//XyLoXqUXUCCUzlWp7TpJCPni0fiq8OoygW0fJDTXHnLtDQQTLpPvSWERFipTYo
vClppfMfeQSjiMwcR3WWvYGaQsX4NTEvE91WqIv7W07gXjmomhs6mO5t21c/lZHw
MmnJgjCvkx39QVsRFxvRhl+MYN//0mwao2c2LCOUfqxjKVtqGZyxY5gbmXeZPiOy
qg/+gX1Sxn/ttwkzA9Su+O9VXrt5Wb7FihCeSrmZ6kWEmVD1lRAPsgBhdrI1jgoh
yZJdwSi6sEW4O5wYOVLxGhx4WYe7gNK5yFwlnFy5OSeTDd6/7X+WpCOPmM7D97SX
Ut/Nw+JAl+QGfS8vLusNRYHKIG1x7+/Ncl1Tq91aRNGOMg4y4e+U0a+1Qq36CujS
QwH7dXeHAa1pAMFBPKU8UaZRgIrfrz6qWVw4UQZIx/WisLjThIHGKeep1USncd8y
Zpp3/HZPbfbnj3hZAD4fRounPW4=
=wcZe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a5e0be09-bd17-4420-ad05-a4b9d0b34a70',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/RhdFCSzCu1Ua5+sB+WxJXFqGtEb3j0zOCni57GGq24kF
wVqE/xStMH7atUrLn6FkhVQjshf6U1qxI79agk9K9sB4OGHGGDUVnZHWxBcaROpJ
V376/zo6C1HgYeTJQ5R9HKNldLV+zUiuX0kgQVFTEOKxGnkuKRgCU5EHaQwkc66I
zAugSJNvv06DJl18VpkVCUplNqdZsSgGkiZwlxtrUiPjNnTR6eE630d4ubxHEROV
YSXr9y62eTMxV6F19eK2CvajiMEdtPe1iGPOJHVdzK3Lz+lwCACVZ9NRr2EzmKi9
d7wRLPDxA5gvyaBhLSj8oW0jwuAmW2u80OdHnONfNNJDASZruL/bKr9rgp/+ZWiq
RoijTZkcDjKpS1fbTCjyF4TnI2sSduTjqMjN0tuLgZxdYXSReCwIRtyWQjkZAbxZ
f2Wrug==
=UlqF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a5fee2ec-16e1-49b1-acb1-4f31ed326dbd',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/7BI+R9yK4p+G0Fx3DMHxIQeuhIHdcL41+M2xa9rV08n0y
iZPFgMBi6YUL3aGAjGMGtpAUphYBvTlnF+GL5NCfIR2LEy5f8Xljv62Eg8J23BoH
tkU1sW1VXvh1fSAFYkyEm+S2k1bN2kLqvYNBtX/r5g+B90ZqVgeXC/raie+zvy91
vz7cV9LXfODDr6rzXqTDF5MFEN4zXi6wmgr26Yg7jENmaw1vW5JT1IzYzInPEXI1
UmXcfbk+KrLHffHy9xp5vqdd6Geqy8GE3db6Tv2R4zV94oqNfexSG6vEL8m/ynFb
DzfH65Tze2DQM6LgQ/FULTJsFGbTUU7eBGzF2E5l8cFt9/btMsw5G1QXnbWyJ6qr
0fFJIMDJ7Tzb2N1EzHdCfeoIbHg94/akqtB85t5KT7eLMTq3GlaAKRL3wbAl/Jpy
8bM7FMHGILV/6jry0s7dQwufaQQwZlJzv6H85cCK/XaHG3zaZYJofT7SVU+A+qrU
BsqYwIZ+fSCcehTJ9rTFVeK/x/0D74ZbhTbT5lxBCH9PsKhYy5nyeoPxOlkYw7bm
zRUUQi5jkgo0JbzJAKGidpM4+B3jS0EXTmUGL/g+x+nrhmKjojB3gave7vGtykVU
MUg194sIp9W+tEJYiOuquwhe53zXisH+C1sE9ojBtdg6WlRPuq86wFml/CJieE/S
QQFueIBmULLGj2omgrTzK0sY76eLA7fBGEGj5da+9Wv0S0Rj8nEqFH1f7Bmekb7O
VwVTXNmcmx8pBjWGeQ91bccg
=2nhN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a7376565-5705-4915-a6e2-1b0a7aa3a790',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+KwOfDQJVnrp6z2bczwCPPf8dywRYtDY8zOOCaIspZAiQ
PQeFcsZ/rD6XNgOmf1RXtrJShSxUZ0wTCwknT6bFsV2z+zqtj1woDr5HteBjzNYi
tWc9391oQaLtLp9Ei2p4+iG+mbMcVbFBgCYZCl6Z05qu04JWqQWiG59NMn5i1mnO
diHWev/4aX1BINmlUQo10rmQ8/kIJdEckbBkMu8owsdjwVqYvasmv5L/hAmY8MuT
/vBFQayAyH6MVtb5QJxVxhDKJcXoCkck+g1tF+pTvHb0uMqWAPW4eH/OZxmn2TBF
sqBevTW16O1TWy18KHCeqJB2OCvnZGvQ9OavbnW9/V1W+SgO5AupCXtXdS2cU6OM
0mcI/t4astYVfa3S5qXFwb0xlj2hj7B7U7nHoZQmj5a6aRkyOeGBgiVIl1WnTCtv
npAHd0WZO9ePvlUFMeiCEWMgbHVS6r4GS/qAaHpbtaH5RisjhuaS4KzWFeHvA0ah
MgVXboXTn7TDy/ghww5OB9N7m/XHRrmmgagqQ5VbV3TAMHLHfUa8eG3XluX7NnGX
I3tkeQBuPSMRsGNi4TTR9F9G54cAHEl9AeJByklCUPztd3bmllGytXXRJzrPvl0a
Cn43bs+0hWkuA6hxQ5m6+2Qp16F37nqI+xy3Egpyg1yxNxun964dQCQV9tltJtbS
QQEuqyag1fp5Z+KZ9mQATDKiE+LKqtxU8vV0bSfDSLOWirBQis4669NOA0zjrDOb
WqfMmMoTrh/o66th9lPcKng8
=nvNC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a7e9b038-1c91-433c-a2ad-5b59c1f96c8f',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf7BMMAv8xqQlaNbOvw6aLTN7lYINPHAyZlhq51+iKK6e3B
rZ501FYVXnqIJeoP0pC5im5QLM0mGp3sOxIopl6SznhMLpE98G9egc4hMKnOCud5
929dTebuLkW/3wW9JkyC/8hLTekxDAR419bHs5dIRfL+siblaElqHZbjoq5vIaWE
W+Abjfe54mxL1kM2qHtXcfS1Yy9/T8Lw4RTThdXGsbO/UGz/QlE7NVFVP0D/F4Kh
c4nP0UvNsNYsK2dhEkvPjaDJptEWOoOo5RyVfct1iby+lOtsf4pSICYH2rZzqcRD
w9O9sWWq+NclD7mdS2yMKKdUlZUlH8OLEk8ss1jsOdJEAWUY6zRe799ns6iJBIKC
HxNFb3eydFFPbmlbfWbQWDxGu+p24GslriObBRWUrkrhsD57nFYQGF3Oh+Re8YSI
fjq+F4E=
=Gb6A
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ad353c2c-41fd-4e87-a5c1-ce9e870eb01e',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/+LS8X6Xjv47l424/Qta4P/DZJMLVZGIM+o5CBDqmnkTne
0mFH+cAnn0qecKurwsEvwga2iVXUhy3gT9Mve3Zbaoek0fB4Sc8nmepKEzH1o+jy
tcubQ3zwBJqZo6A8dijIbuAvi+zpD7O365W4UPvoi4O2hjlNUePUVBzgdTJ88+PT
M0RUu2UOBKX57XMxzZkixovlQqx2dfB77MLm19f7lnPHDWPLkm4zyEeYce1oe663
Pm16fmVSIdRvT9K7kiloYvwbJ5jDg/IJAg6YvgWSzINUrkIiNKhzkYQyMOCJQ7Pc
+d3LLMDBd4CUaYl7WHkKq+uI9g/QDgvPvXZMQwv1bYlxoxA2yAAu28baXl8CyTVC
iZJE+UdhMPmWHWlwsBXL2XwgiATtYEDcmAQLzl0yH1RGpnZ/wdYXUeCYDYW47SLT
8//nIkPpEj53RhnXZJpNOsdKQbluvvk1drYteL6Tf/ZWg+w/McQq+pgqLWAjVO5b
MJYiLomICc1Xstoo9VBLgiaC03VPT7GvHlbwerbXAqF1w4j/MgQvh8ghYAWVtIcG
BcceAslXrPvlBkqeSZ0e+qhgpLgkTMgOhKKXJa+jc6t9zgyNWl76uUnq69q8lJYL
WWBhy+MoFNe0EzExTr8goL8ltvCDb7wQT60fffxGHgewx1jM//eBganTn05TESfS
RAEwsOfkO3L5Br4R88Wovnhm9Y5L1B8xkVez1phtOUP0dIHQ3m91X/9tcM1e4Dg8
OEVQVcBKgn8Swfs3z5jXzczumsxV
=OQ0X
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af7d1c43-690b-4d26-a9eb-449571ff3375',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//SB0SLD79dj+dZj/CHy9IGBrFrmj6AnU+z8oNFvn582tz
gWmOLOofcq79hIZfO7G0ltYoUGlqcbF9Ir0V0r5Keex/wywLtTTenVDbYaY/jqfV
fZdyQC3NvOuPSjKMuJW93UPRb19AakZb+rrYFFd7yaJX9lixRFZfNtUO3Hqr9rRh
wGP5zM6UCSpHChA26+BUn9Rx6jcGqxlSMIWkAkuJOb7m60/yrdnCJyBcaA9FKAk0
t7XU8jcZYw9IuKfvqjmPdFs5pNQR04Y6kIjQhVbEQdxLtgQSH6MlYHiPrTP5vkiw
qy1tbK4NPsC1JjeQZ18350YIRk7s3HfSPLzClGXCBfLrCTfuKaNRdZMjiY65Fxob
DGR1SC5DtP7NQua3k+j0TTna3yxz2BTX5EGYW0p4LGasvebty1byBrxR5EErn+xd
nP6mfPYsMnbTwjIXnrMsX89XWYLB4+1qCu9bKYvlj4fglE/4WsG0j6SDhO654rY3
eiZ6yVrsFYhAIkKDn8qad9id/ITSk/TJuo5+oaKCNN2RVYrdcWlbj1mekr5Nkt6Z
ZOuXT6NwiLjKBko4F72t6JMlNvGOaAuRK789YSXRn9lYkL1MsV9rWQINx/khOQu0
K59qsF+3z4b+Bjgr5GF21PjmgHbb/f01JcVADx0Cla+H0ZV36O37M0bKLcdBbVXS
QQEHAWQhmaCGt7+rsd4SifNMkTAasS2Mvv9bnGa/QMI7dcMuRVuWLaRtDAeVsRVH
jJEuYeb4vOUA2yF7KJCUp/+8
=1ZS0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b5cbec4c-48b6-439b-ac0a-1d32d8271337',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//RROWfvkTtMrNdlYmYPoUfhPmpS/GbGpTSowlxycEInxu
Qr/L/HGlxOpAUodPR7rn+WNUuYHRyzrDstqjWnFoqAPUUpKKSahtLGiR3l61zOAl
1NhHWHUurcUPkDr79LkuEb8C62tToICcoWrGejFlRZNe9FcnLZcJ61X4QSMDDB7q
/vvLRlhP/8k7mBWpHPoH/HT4PWxM2R2rG+dMNZFtZJZ/XgEIqjjGa8vt5td5EltJ
ViV4efqfZx9cA9inCRwdaImhmHesaBed/VIZlkLxo9KMAJhoK/ShQZ39KhAgsDGf
2fE3n4XMjUsfc5ipvbCU67AcuzTQlies1e2ERmlerZktUTiDBym7HV/Ah0gU1krR
SEs+WuAd/KxVXUv1oSD2Oh/JmL0frA28yryT20mw4wYjFEM9ZbiCVEQiMC/oI++b
KUASXn9Bl1YVd1z/eNI8d6kdb/AyeOjM/TT4fAu2VXOnDvhssrJYGddjQvex+cSx
RrjrZTuOXuMvrEX84TYeFzRloSrKHwwBvHqhAdz+FldMqGNTzs76T7TCKpLo/Coq
sW4f87Nb8RDEKixOT1Ho4mbqBOpDEVJbZuYQlkohPgwvEXE3VSod60cP/KZ+GvB+
VrdL5Iyu/lz1KlC/GtFivW6J2WIpqvgpbn6H4VM8tt9E4bm0uga9eLZYWY2R5rHS
QAEEZn9KT/nXOe2YrMQUDBp/56NBr+vRFJ5n9bMuum2SR9T+IlEmHs0avN5jEIpg
KG23+ggDugNXPsiRs4nnbMs=
=+oOS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b7cd7852-6e3e-4d29-a727-d51fa5b4fc26',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAiSdKubvUllqB6p6lDVYTekVeH4rspgqzQ7+XlZnWLzRc
sg2tEyHclXMR0Epc0DoIwIYPc8/ggZE3ScFJ7/peP7sPvAmDOhYQQOpa4Nvd4yYe
6opL/bfQtDflDHsfzdBS/i4zIgRhCZPn8ZXUwnE/RHJjEe7XeGfdN550vgRqGqs1
eHh75JTP+vbM+wObOv5tkTcDsSwDHEdJl3wdLRpQN0rybNf8tlH7gC2CfVvay+gW
9C/P1iouuijeOJxB9DLyPKicRSlFAoqapmyNfEvjNe+TMlVFew3Ur2YuiPFmDb74
gy5qe7Pyl4moWlQ7qjf1cLJgSrszmF72XU0n9L/lX9JCAVJGY1lXHv78+1CzKd8U
4y9D3MQmwiortPXWOS2mrEBje1PFRnzq5Lu8mYyGSpvrKijKixn6HR3ryDteG+E5
wtV4
=zASl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b7d87da4-83f1-4cf7-a0ad-ccea227a2867',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/7B7ehONPy5IvU9i9U9ZAaWPC+UCH8DtvXepPwSU7GRJUe
krkGy6pCipRu2coNtpg4MqRnpiBWRxo4wl9DGVShHEICqCjWqh9408puNwP7Rodh
QmxL34iPrSoN9ymZFGHsF+4oaLWqBMsPNb306l29WDt4kTtNr8wuiMu67ILtIrV9
SXnhtuOugdQ+WT0M0KxhvisAOSORiYRZCBpOea02RYmoJ43srrnJsstcXTIgEfBN
uZZiXq2+mR9m49GvwkQ72k2pYCrv3LMczm+RTlRTRDPr+3gKa24pgFXctUVDuy/1
Hk76aZPl/CtME/hHHrqt6RKl6WUcflVGgYH/zyzKFZxjdaajm81FXYQ5oLWJ58DR
q9KiM6GEywIMKufGSuPvVR2upE2t7EPHeYppBZjPyONlAmj9FS+mAUW+SA0Nds2V
Agin6AArOF7oWJ7sg2xHWH0zUCeb2X3GdzK5l3YIJ9S7tJb04w03O/Ivv1I3JI1m
K2AE2epsoLEvkz5hLW4Il2nK7Svl7A91HZGwsRkv9SdxxznXng6Mbjnvro6mEW6r
0A2czioheuuGLpzNz6vMZGHyMRM55/KYN8+rpvM8CcMvBXEEhwowfh6RNL51cxKd
196cVc4H7hO/DABdWgxraNj8WAPrG7J5FLR2lynhaVf1xv/Kg561rXyMU1ipINnS
QQG/hSl05Pbg5Srw/C8xmauOpSrEqX/487pVFz2sH0c6no0XddPXfgxQ3a8KW3ef
PEiEYSwwd7Kut24692CT4Uxf
=mWZE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c0edf34b-93dd-442c-aa03-ee8af19b4020',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8DZxobFUMjwt9MDi63OWtX4UHvIx6Mbeg8A+by3ITDuF5
Q310CZeoEwUxCngmZ7rFej++/kO/osZrgOiJh+geUfOldTKEIXotgKqebLpgnYPr
ZlnkG99K7HLKUMxxgzztrufvy/+NQsjuPCGZO3MqSFn2Upm9uO66pRLQ1ULWuoDH
WDmH7SNmQ41lJ0Frl99qlLZpM+mYXtVHq7fEao4SgSrTl4n1iHQ9B16xqfK4A55m
yMOVlsG3fT6COF+y39cIg3Nx5bD3tawzzHD0XRP7ZFJB8BIKYbl/1BAbGnNHtcfv
Lm28Urs+wtIEdnQsAV7ZKNbuHox3yyZBv1SAftovVUYtuq7ENfi//2fMM9cY4bgf
5WdVXEuPShR2iWwDoc6BXmsZ3bgdp8Iu4lECiPL1/IuuvL7dFHr8KhGyZhHk85AH
oxNuJZC1TgYivmju5cHOElAFEtozVTK4MqKH1Rcf0RuX1vGDa2WIDwRBr+fJ2Ov7
5k0YpduYFfN0IUsoxlxe913wJ2pU66gtNZORF867wezl431CiEL0GjgwwcS4Dx0l
yP6ddABk0rcStvW3JlSEBq9mRNOsR/UaOplgWOoewYlzoEywCEKISRUn8FMSQUSq
7xjeF0i0jBdYbsWu4EOBwfFl2bdi21kQP8/KKDQ7XrLmGChIhFD78jlZfCUn0IXS
QwHrBhIan0vYofm/sPba6PjUAFaAGLcFk3Mh16GMna7bZsPysvlMAM6Dt+gwZO7c
PhpK5wl6QRRuuqEuX08Rh+lPoLo=
=tZLq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c4e88475-00e0-4c35-a419-85959727e59d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+I3SIRHQabBV7iImWuR0SF7H3MQM2lKHfjAxO1jQWwzZh
iz6nic/sF4jO14Uy4EOROnES9DknubDh00N3Y+ifINUX0pozCvU738VZV2q+YoTM
SzVYQyUNepWliukN15xxS7O3D7lu4SbQnGE9FQHix66P+uVOTfi6D2SUMp1+Z9Km
y1oa6WdfGZP40JxqWK7VrlV4fSl+1+iW9F1E7TrMUaNn+QuuLZO/ONGGgJVSayoN
a45O1luHjUttqkdgNr07lNJK5JziaN/2OMA7M7lcGJVTtt1+Fv+oRsGX6D06a4sd
W0BSZLj1KYdQdEh1jDKSiVh5YcYtRTR5MdR1MoooskuwGt3eGDi3OV+eVNXWsa42
V2MxRsTQADSIT5v4uKKp1jdXkshnfILDNGuGA3iJMHMOD9PnlcKF+xYpKNqE6Vlt
2K8pjyh1Nh9ofqWM4e8RT9iLLFtNuzU0Vmg6Hqa1mXrkp2+kWEF0t9WGYdlyqD7e
8Lo5sY1JuTwvG+anDBJ5vJ9qHWKU9rrw/l7pFcdvy0pbgxt6Y1iIm3yJZ5hrbjBz
XnWffSE8MMH7U71kFhBOnQ/fqNpV1eiPN1tpdMegGNKckbIlvH792grBOPLrzpYO
1MNSuyZ1YZpEpUS7hU9vzOLgnVjNatVEzTXRhIxIooirk9UkAKQM0xngpgRnJ/zS
QQFFwRQk0C6Va+vaBdFacKiZtaIjLuSkj1Kk5Kt/RMmCu+xzBGPeA1n+EpmiGWT5
YVNc+b2VAt5E4BY+qWsjKCI4
=DDHK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c842f23d-ac53-4ff7-a01f-5043c28a9b4e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAxwYZNUofDBjjG85dyYwHh7zSVXzB4OOSybDn1+D5p/zt
+iR1G5t12wdvDyfF4WJa5FYF3mdplGExDpX/dgLKcvJSTdBrrKj6tMzSQqbXME0j
VdB0CoQ9n4UUeta7z16d+VdgMBBEYVR4xITn8vhqqNmPE+aUABcX3/N5CcjDaFv5
J1MH1TeCHwOUTuPardYueShEQGeNWnd3TdI6Hz7U9PT8Ywr/2gQVGNJ9SAHZjxaC
zOHn/HyaHkOUvvJkZLpXVSr+uhyiwIP+ZsfawCucPC3fxhT4M1UTiR3/xKf5cfh7
8z/ort1NrA1TDqUEF8oGSFW8sdrleOsVqtq+YgrkfeJ3uZb5ksippFYPo9ejkAhv
5260ORc5csN2NzZqOUgsQv5mDFQL00Kciwa0JtdE9y1Obxp9CvnsCQBMRMBBfDLn
tEFjGZbJXFkRAo+P5Y7V35TpeibYGMOB0z4XXgp+ow5VKkpw2WxFutDOrcngZP3y
xrjgQUn77nVnqjpRwz4VB6Pca0xYlCgF2Qhdh4Ed+9aknAfXMM731gTCV/JrqpD/
rdkl+JKqW82M8A3e51veFcitASi77FZxdUKKWZLDkdoK5049Ukd8pnTXrUk2dMx7
bjmTjCec7lzweez0ROu+gPAVUg7i15XEyE2Ow/tjBIUhBPUQVvY3lvzK/qL+TV/S
QQF8fPz/7Ztn7RoMqI5F/0GHGNRUjBg9Qr1F+JjrvAJ9Jv7TcRgrssLtA2cGwc+K
7155ZPSAQiJtulgRscMD9pEX
=l7Xt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c8f568eb-872c-4183-a51d-5acc691c3cfd',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//f6avMhj7G3j6Q/wl54acQXjZl1Z5dT+U4oL5qxA3An3/
Brfo0vbcG6J+5k6A4z1lRo0rRSwUoGdlwa0pNBcYKErqqlBE8Qoi93jX+9+3t9yl
JqW+Zp7GM8puckTB4gJrAg3v1HHHMGnokr7C+bOvyUtwOqQGXHG2pj6+ewqez68O
6eWz8BCm1IBPpeQG05465Eeg8Rwubo+GnBYv0Kku3oPyG30Xxnb0bKZRZf0gJgB+
oE+BJHkQUgOtTp+PDyeEjk02snu3QL3G5rEPpDUwJs+2ssh40s181cZ2kv3kdXa9
Lp7YKvRmlZ3DwNN4BQET9zPuyvNdNUS7boOh+EVDmLHeBNMQ0kiA9TJeTXEzdVKg
txtMQSRova1GgqZ66BDQQHFHZkHYiX1+AVlHa85iqCAFGknryDqzOF6C1aWs9jXr
9FwJo/IftL4kRSROAEuYsnsaCGlonvWTJ3Cqoqv//nqxhqtXMchv4Ggg2lcMw6Bd
JQC53MXO1EtJ2JdvzvNNGVk7GDVSo7pXX4yhm/1M5ElzNX/a80xhYgdnpQgVCPPA
wzYN/VeQS7Xeg7jHwbHQXIFF0oeX+AoH0vH5Eg1K5hg9RVOHVBZqxP+URPhP2lxe
n/me/rA6jGPCgDYGRuwmJm1Q9pudsZMRi9EbEBhXdCL1/YmNq/a5neQ9PzqeWCXS
QwGx3JBiYEz36cbB2tTH0MWor4dOTZax8hGDectFeGWMDabUEOE3kC7aq0Kveu9S
I8/GLZ5I5WyiXLQitwtRmYSa7AU=
=ydvP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ca4e98d9-cca0-4ad2-ae25-8823ca491c57',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RGalIw047hj1wzeRSuyKdEp8gKl11v0hfLopO9swydyP
3cc9qP+1qGX3artixcGapbPHVuwxRhVWNXJaXbkR7s1FBVnH8zoMSB/OomKe0L6s
NToNfSIJ2Wv0Qe+KkMhV3YoEfcLP5UfxGfXWPc3ZgXM++JGPcI4U9L6rmSpAAuui
HJcuNe0Iz2FVkary/qTewbX9OXSGHNJX6l527bkFvy3+9BqLxWKHIJu+1G3129mb
UGrbP7a3a0aSoe1sFftrBlrvwWg48T2kFocybDaTStvC2m7WfBTfS58dY5cWKtEl
9+lX38HIOsLTcJvaiXHtWuRMiCBMrwWWi93Rb1LPiRdGZ54LwTPqRaz4OAEiypF5
gR1c0DtIE41CMrt2Gc2W0zKIAesVbIezcyUiliNrjZeb2QbMsX7to3wjB3wsDY5L
ee3XbNK+wLA86upmZO1gbe3qT2pdQLayg+rFKmbPDsMAoefLU10jwxLa2txLMN6r
SQtXZ20pNUoDA19EJ5GqabHPA3XDJKCxaRxgy2Yq8S3SFvl0Uv2R4qXdJUOsHaoD
13dyi3iqvCsTNKbJfhDxSjHblswX9Kvxg7WcKuKh55uIWCjOMtGkMzyJCOpTeKc/
2xjFFG3jKI/M5mJtq3kid+0KTaSJjwr9MJf4zMnXr1YW92ooJg3542BzN2MD+OfS
PgE/Iv+xNNP7ByejM4mvgPkGrUJSblaRq3Wpo66sbvWGS3GUqQ6IF7/o0OGg4RIW
xZJXeGfq0/bs3vVd9WZY
=ILQb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb6412c5-8979-4f44-a1db-ae17d55382ef',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//ZeQaH85JZ6tpqqHeJIKo8nlsPM6joTQswvu27ttRq4Fb
nEHRtdXigioeyzdXceuxQ7tzpID9Mhos42eOvdMqWKlMJd7du4m4c7njoPtM3hm7
MiPN5wO+nyGbKiD79ekr4IfrgXPfzkHqAN5kytHOSRpy3/B/WsapOZpQsbwvFdiU
OkhPFzBRLl9Om9aPRmDGpnjGDjFPHPFs3gMyKROoT2G88YOHcOsl0DWebuUjYO3V
T4Jv1OPIaP7/jR0Bz56HhAEy3GNrA3oMraSGyRorQJkmw30bFMCPInYaMoBRDJsM
cDYQj47+W21I0ESx/oPKkgPxf0pt39wXkSTGl4iopF/1qcLUhzIEVhw7GywOpkJ4
5Hi68xWGJAOLpsyOalxTZa4/67Nc4V4OLm2KiM/OmiDxzDi5kD2gdLpwUC79oOcM
mzEMuETd24t1jDnDVpAcZJ/dGfhY6kAj2AmSJNCbU6Mi7miC/VkNB29UARQu6q6w
PElHEzGsVxEDYklL0L5n9m43UPkqtQ9r39MglH+zJJMyIHPGNkFHZyf7phbVIcFd
7+k9G+ezMAyj/RVfzW8nmGMpxWIkV+lVBeLgyVQjLf3Ma3m7pFXLAMMhXIF4B02f
hzGufVFAnWunSWUewA40/+Eqf+985XVqKwJ0wbAb1Sb9BiLtiU67uS0qu5ec1bzS
QwFvWhw6tAc+5rNlMJunyz4joMzmBavfE2GxCICigOSIg9zLIcVxI0DUDaSuEYSe
IYUbxY2plEKwIhnksNjbLewMuWA=
=Hqe6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cff8b883-6771-4f36-ae38-e111de083e79',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAljGZ9BvTRKi8US300lWKaYDeV38HxVM0/Kj8m9kmxmX/
0Vsppc+582M2QRjl4nxjDTKB2mM/GcypweSiui9vEhf63IObM1VnTLnM7ioGLVUg
K6rYSbHdIaIvQgxuVRj2vCEmsxA0eIsyB9baun3u217LfyeY4HPvQDyz6CC36msr
lp8mE30O3hTCds7YFVCoKNh86FXMWpvwqhyK8OQcU5h76gLC65FWAB3Z/Wyr7f/j
bbq13t60L8MHb9G2HNCdPQncV3AmfPjxviVUoQHRpzDY2wuIULsMw2RAtccXTrjp
c6T2fQL4YLBGerwY3yDxF4fmG9G0dgn+fDHC5kHoTvOqFiqUt8bK4R8sm1iRyahg
20nvJt4Nz8j3axf/sUJxWkGvXUmo+tyOjc5HZYwrhk9W3yhwmGF+cXDXtbWDf8eF
P1i33sJOIjkatulgqJP2hUI9Z2imXF6T/PSm+6ZmBNP7n/5J0HkbDfsC/SvJkNUg
xuUvh65umOlkVaX9GrjUAvfGxSfPyxh2As6/clTPlIvXMIAgwS7PPNQJ3JRfNk1Z
RfP1E1jI3gGpjvFhxwwVaSfPNDTp89AAf+cjrw12xtdjRE0530/dksFFuE4NLsda
jGQmifUXEpQJjb4uQCxhfOVokZYg4Pts61T9hE4ndNO/fN2xzeMkXv/RO7gr9vTS
QwE9J4w7qA7idcjCSKxS2NlQ04PHVJcToEa22OHqfNO+bOGfiIAAXomOUGXHBcnD
2t4dHQqBRHArFHd+0JO3HA40y+g=
=WBjB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd1c31b1c-90a9-4ab9-af94-943bf7a8e8a8',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8CNYvDrJ9i1rnSmDMsiCm08O4KeGGWEn/Q0GR0pDBQKzS
VJMs/kMCrbtvcIGgLjcZbd9f6/baR3EXN78y2FNIS6iqWy3o5WYbjZmTCpPzSQHX
i3TeCNwJ9ilAZLOT0Ddc9L5OR9Urbw3jmjWYvTA0lBcvOcyjYlfCNDgWaH/GCR6y
g9ZILtkvSOg3uFdStDc2Wg4q8K7t4JeK0L9qWg9QYqdACpOCGhltSWMHiRGQmYXn
WXmK0/CBCC3eb9VTtTY1K+O/UiQtFirjjdRf5Kb+D8+pGd7JLuEO7OB5y7T/rCGn
Pl6dw0vWyB3PtPNoFi3+18hFpTGmbDAXdWCCiIqaIBd7M3x0eIK/duII1S5zImos
IDzOxwHFLgNs7sZGFJaEOQMU+Spz08Q+28F0hisKMV7KdhPttc4TXvpGvWWofxbF
YlQzH9EP/wbKBo3f07uAlH00NE4CIMKX1RMjYh8pfaHnVRwavX1Xj8vupAa9DEPZ
C+0xc3Pkdd50BaVP/VjKjZRMgXonQx2Eox6ThhdeHXvhpPz5Zd12FjzweNToZknh
ObyoEsE6F0faO85p3H6Jjm6i7WUP6FHCGnU8Z3hnC4MRdH2Slf17EBcY7z0Qkpzm
/fKGGomUyU7iRtyO16lEmhded24STo5ATQbhNqh9X765tQ32epZ0NjUWFNthH3DS
QQGUh69WZRhPjIk74vLRjuE5XhWS7xD9e4Q35NWFe56jkE0fGFhUwGIOh9uKnNSM
KShU/FnTgh/49ErNceYM1T/m
=2ajV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'daea4e2f-0189-4078-a4c4-4cd1b065ca7e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAyyU0VMHoIwxXItKKmVmzw/O4UpktzZe/FLAR7+uN1Plb
4ZwhplRogrFc9p0C25LF/EupD2ApPqioMo1ZzsLsufK5mg9Yn8qCNms1AooFhneW
tC5obmIuuaW8pvW4TNBsJ54Q1rzIo1kIiyzuZTdR8GeZx4zbvs+Z+yAmaoRoExGN
7bO4rqQsK2QS3Q5XsfcCmUZQ9jXfMCqDbIB0hBiFCzEthmdoC454nRhiMhoYgVHM
Y0bmEiHSoOKy5ZqrzVZrZeLvnWhou4wVShbtASDZ4j9ibvriq9b4TFdOnioNHNml
kRItnRfPFT/2tv3oUWM+t+6sT7NojVeb0O+EP9mAARaR1Z0fZhBu0HJ0OiHNLPPM
nqySZfWcfKwVuUwn370sCDRHrqtx9GYpkld2a6EBLi/o7qUwsflMDelKIDmf+37B
AK59T+M1o5VBiGOg64PKLlsE1ab4YIfgYzL3VqQK0lMgCVLcm1TRm75QkYDf7BW6
uXo3roJPY5BCQ+pAFJqQ6nKufW6gXXZ4M85fKBh8yDqc8OaWyN5vG6argLbj4MrI
XR9g5BaG8QfxABqaTaM5Xr3XMWfORJJbEdVdX+ElvYDbNX2A7A3z7OLVY8XQJYCh
d8cylEVh7mvLvoaifVHtDGNe09pAQTTdkP5BlPULfBH9K9smGd7MZOOgGkcZ+3jS
QQFzNQOh3TKAubRFBq92NUTiOwgPg0d2Hjy0MkJHG3S5tsjkDCPVBN5xWZ8bgO7N
/PlBvvUsyMQrheeQG4evcFec
=U7FT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e099c119-c0cb-4023-ae8c-ec4dd20363dc',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+OUpmKfCTlJNDds3NEHEhqfxbLMxWeSmPlOA/D0Dg0DAO
bqOnKEdBsG9MMsIMgw9v8B70C0+XUNMpKCwTG0jjv4ogkkleU15ZrmxiUSLkgf6H
RZ38UPIvnEZ7Nh17RhaNnFPBltEvz5tm/SUprKwjc1PfO4gn6SkhixbpoQEVlYyt
OXuchivBxPIegNLysn407G7hQWzEbwPx6HvBGXldHXXL+m5DCkK6j8j5eczGk3E6
Ry745vroanzLZw9+BxMXrq90nrOAPwQaI7W/ptkgsFaWPJFv3jnzSX477UNG6HT6
Q7tDp4ZKz9Dw+q8tiOcwKRDy1SDFdxzAzGYAVHAiQFzZPlpSBYhG/rw2KgtIuTiU
Q5k86V/uwQlgsTdaxXmolqJ2PDQ/MJ+hKaxWrRUfc7rPA9gb6wB3Wx4SKL18PlKN
rTdOnCzRKeOEOlMHynGyytkM2phgVH+cfLKLu8/wp1LxLphYhdXO0KX4DzxgLSxn
2VB1xnZia87SHgb818V2uMoNhlZv1VrtXgF6LYnDbLEK31bLknDtanaNMjlzO8Y/
/BVjZ6G8a+ecd50+LyHYPC9YfLLzX+p6LPpfvA3EazmIHdLf1KsaXGjN/LVzr/HP
xy8lQzxECO7R766ZbIfdmnlK1ZDQ9HQbQ8b4eLo79c3h4XOUVtDkT5jqzkTdbK7S
QQGfw3ujeD87HYs7nD0P3W7krCQnIExLs9rW5o5voLqrwdsbEPelJ1HaTOV+VOvC
6Yi4tvoSsZ4h11+3bTw0hKFx
=FDPA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e1c3e850-2fa4-4154-aadb-e5271058b896',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Kc4gC6xlSkhzH2vNk8xQYoBTQY2SlfeeWX2FKYXxFcqq
NpOtEoHMF2CJw9PnZzSuq2bvEWEztQch4FnOyXHF6m2h1Cof6WV/KGN10o4Jd2Wy
2FzdNsCsUoSrBebuEACAjKymj9RLjRATCvVh0rpmRBfiGJkFUgjnhFr/5oCzagil
um5mevcuNufK6npLbt6LRcnHuG6LmKjOmtZIPmqbH7nJZil0MdNpFrvIYcQvIilY
jhYlktphJUity8zmOW9uJzjmY91y1id7irnpDjUXn6JNKR57Rm8Zk8/NvRF8BYJV
q7h8pR0JudvaM/uFpAw5VjbdAD5Bz+cSSUAE2/DDBYtaS+QJ9zweteoFTZ/smY/1
EzYcfDvjG1JzDK1e0qeyuIsiAb/dXjM2/0v+CpfA+UOr/3XjS2NziVv84PaROBXm
vFC1qreShIEEcojAE5oV77k7fI2zU9qQlL1brsBrnvNP+Ycy84qTf9MHfUzCpG7h
es3KNJqtFf0WPql7MadDHQodmMeGmmfTz7JgCbu7oXrkLntP7J0HuIIrGyWFApkk
pL8Ih0/ku9xBt8j4iiCYSg0/3bU40MbZwSE9ESqwNixHkA9p8TZC7tIJQv8Qo+yX
9SfUvTFX/+UyjNSX6XjCpR4zDcINeTxBUgmdEJ1rzqxFIcEdjcmCtMr7ht9wF5TS
QgH6ZBPwB+wQZyTujBDtaeLaCUChS3jAMn3ndlFTOB8RzMxRZXwCvQFYcoiJG7fI
3CGx+52zywW1Hu5jmp2303Co4Q==
=R9w0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e359783e-c106-4558-a127-dbcf76d2887a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwLAiX9lrfpJQx6tyuiQb1kd1d+ZH7A3IGltuGbhQcGa8
2bJsSJ2jfk1CZYzlVcugU9G4ISlkxwxRnWjG+wZRxSAgEKXhHKEl+mkKnz45NSf8
6iURoxkpHsnWsGtE7+FyhBl7j/OwqGWUVWsie5LE5q6kFILiNYAa2v9Cj8NYrIip
dYX0/orCHRjmB4BT+PgJDdVX6iTaf4mrt8FxVkS1HZBGON0wynreiZ2Sq3dvEZhC
UXwReRS60GIEsu0E2QC0eHER88P+KNvFk/3Z2rbSiB4D5zqccee/gFIMK16m25Kq
RqCkO3lXi8dbrnNZh63+ZfCcTmP4BWTc2Ph7PTkMivIzUww+MQUZLmVThRyoTaLZ
ErJteuWEM78szFJU9pxGKiU0vfeC8gD9pXSEolnKW0oMUffyXBRJtKls48gIwSl0
jSagmNz0h11rgn1EL99I/juNahy32WhxsabgEi9Ck2z6q8TqfEqBVtyi2EJZ6wYe
xZ5UnRgj1eFivPunGt9L3yIZhqdv/A6DrN9KnYQJkR/ZDYhgofb2jXTGO0MW5AJg
11ZW2UjmpeIQfYXSPwh4FL1+zYaLgE0APvnGFxt60qck8PM8htOsud3AcS9Z61hE
k+CIfudKaKwCQnsRX3I+KwGv09xodZczdAu4Wf0V1SBQiS3Iw80effEhS+AsE0LS
QQHG01pUt+g97yluH1KT/9NScvB4LL1bzKnJJbBt2K8yo+va7mGE0J6BdI+3aw7U
0Cl+DjPG5wQdDf9TBS1wy64F
=7P9f
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e824c58f-0a60-4453-a2c3-99d5bbb2f259',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAqZqd9nMIEfxdL5KPa+JTgs6+VElA7czxmxzi0LW3Xr7f
AcZtD6wKG00pD+sgdaYqeNMPPrv7Ne+y2IUMx2/hg5O59FLqHbOYwEBXiQtHSCBD
rz9O9bfROCDrPPWweUZ9M7d0qM+7/jwrUKdoceSH40JQGln0n5N3lYznX+HgygKU
fJwG05sibnlKjJhFIk7lngogah/MHdC7gbKTUItu55RqPC9TPUD/EmvSnh+LTP/U
YvWHu+wh7XVacKNCGicJi9i+MeL6eBfhAiifXQ1GGS9rNDyhmLeTv8yP1H2E5kxU
kVUW1goo9oT+67QlGTY5pUPeeUZIbBIV8lC4onY0AVD4BNud52sfyA2p9YRkNqnF
ruGlSox0Yx6lNHZrLnaRcJTES0TvyKWQnWNibzc6SKJKUnKijgzIa5+YwaNFWlE/
5ncPcAm8c5top3vxAr3WEJuIN2O1mkqPZDW5br33lUHbYFgsgRRJQ7vU0LfpFHar
7GzqF7P6qVG+XnAZjSB7E+4JWTiiCXiSlwgVYFh11Bd4C+jjmXW8UgQ9zYeBFVVo
XW3ETdo1Oox5luEthIiaETB2hED6uFDBeNyO5uQVF9mdqL+XcWSpqW6IapQ+GkxV
NHAY6kVRtQi5kRDa4Qgg/eVIiiEsnoXYI4f3c444wbWjAD/z3bhrDDc+9Dmf/ULS
QwFr31l8y2s0fOgsuBRu8mGDx+PXjrRhbRxHljC5S50jTINbeBSALFrVKxsnxHe1
eBobhZlyZu9UxOGo9/1Kv4QdC7E=
=NkdR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ec6fe738-3516-4c00-ae3e-105c7fb5fc40',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//cQrX8dvjzhAnZQrTepwYP+GEGGVPduBKmwebCj8ZvEuL
3uKa4pqekrpzngKK5/QBQnwwK2VKMgD20DZ+lYi+EhQzL+OiKWeDTmbYcz1AhyU+
kdBu4M2eXZJuUIfs/sdres6MpcJ9l61PBjiEwWt+QkTnz0O7KKLx26wvoTmTd+XT
U1GVK65b7vQR3gzO94oYeX1g3JmDtMYI73eIVtifkxjvHfMGczjuQ2lWvzJCUbQG
SURyFAy5gtZ7MhiSwzddhyfB/80C95lc9pk10ju+wZ5+Qg9HT70I3+M7Eok+8JyW
8Mla/bGSBp2hz5dUbdr+ydPKHqvNVs4Yxv3xdCeMzVj9w70/mBSyRSbCMjoZ7Tg2
95g2MYLJKtEgvqb3kyd/GQExBddLNdm6v+Hyt4ieiQpG5+1yXcYdtyAPKF8u4kDz
2DA3CTt2r9egXWeasnZ9sBQinRpdpnlJXG62ucnJ3upfntGnnubU8NMXO283x3mm
/FLPNrnWTpjtYIgzj0PzmakZk/b0lyWee5D4boTU97w2hsaKBKf6RcnvsSki+var
rRWHdP2083b2psa0rBHFNzC05YUY0b0IpcrWaQJZZuASdTSwpVvFI3kIu9C7WPEv
AlwWiOgOr7v4MlTHWYTA9xeJNfvcMM82dUp7gkt0ptqnVvQ1CWwI+7RW3QCWB/jS
QwEC8iof4ynKUsi/DgBqVssKzwVntbwvxNKQyzUz6pogmMiunVb3vbNBv/d5NzA8
uWz5nbF7tvT79eWCSl4EkjbdNZA=
=gCQ6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f00b4259-6d47-4fdd-abca-4ce96e577b41',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//e2YuN2RuagU9q5iCftjz2kZpiEVQlgqr93J2LvzrNsbe
Hc08bCAVvc6qgodhwcs0IrkE6QWJgZifC/5f+NKFc1LvLuPXx8gXuI1YiT3w7X6i
X/vynUZzJKCZEmAvbRtz2Y+HQuUmflp80/uiLixIr9D7GbIL4F9Eh+I0CmQwBV6p
GzdJvygxy5ow3J/ansRKm0OxCsIV4Ruox6DMb6ZB5bIqZp1ZGiyP4e/arBG12Adp
l2P9pCyAIybUYKAx+nmH6OS0jnTr23k5FDjVWxRf7gyU0ssxfw4+56fKckZvrhae
DjMUyII8PsXbJfp2R2qZvmY5iKLPwm3oAcgZ27YXgvl5RO4jHzfvEQPcmeqE7S5V
rzawaqKDsMVMS/H0aaiJiUfT/qdBLAfmO38JfW1TY3cbKlH4s8WFfnapy1jNOSdr
NMzDgVIKyLxtXG/nhX6PxdWolbCrIzsJmto4FL8dbjHSj2HsTl+5TH73xWs0Xy5G
+BRTtZqU5hmGnxgL86Kqwt1HzwiN2k0e18O0UDW4HWRlKvH9Zkbhf1Ju6QDOOhVp
gF2o4TRNGUCziKKquh7EdykxPaYj4KjYOjh0M6gOYwL/IqvLDKJ75wR3p3GW3sJ8
O/nvgzAFz2jmRlztaM+C1sxugyt9A1YB+8USvZk2x18QZ0v0E7FErgLQ7mkVzfHS
PgFdaqJuPRGd8kOpEyzJippBMTYX7Fa+G3Z0cJIc5Wr/TINXSsrdUgY4YnajtLqW
K7cSgy5B9QmMLAu+t2Si
=VoGl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f4664658-d649-4206-ac10-9832c983d8c5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//coeNo/7aqEtVw8KhEWkkBpDMAlJNSmhj+zJ4Ie7bUqYa
yymOpfv4R6g3IkzFiHLhAYRoS0s2oQunW5SYFuDYU9PeC4ol7T7jzlFEXAdvN6WL
QS/BfjAc9JEAFnDnJic+JbrrsgvHjvP3Ac0qTFO176ZjA8kE8a55j2s6Rs4ZY/M/
jNAOPtOA6irTuyhKMeC+Qx4z/FZ3Cwb7FKYujCgI5ECEjcKfuFvKaQOAeAWfjIhg
ASmvopJIt0V4wIW85N7pcWPc0RzsQ+O3y59Tbh8xx3Gx5gFq7JoLdUBhHB+M/JJr
wnssX8r0JiSI8rqNLgd5gZOvph6YXuAm39SBvh19kleq42mcbvwunw/GwHffUTwP
7udtbREb9ijJSzS3BQK0pEX3e11pfo9Pm3ueMfKMyfpfvscOZn7i5X7jwCSO+WEH
xUPcFGlcHg3c71Wi9fvzA0Wk+MrxGNQuXLpAPihNRyAoI4Gt4lqN/XpAbVgS5gVb
MzCkkdpnt0UG9Hzu+LvqBvTjlpecgnKNnsShMgnVfr5QUR0In9bc25WFNFW9Ljnc
Df7utB5g+ORRWFenrmdiV0meBMDTyEh0G1irzf5k/U3ExzyTtiLYJU7JaoV+btd0
8YIjvxp+rNoQPJrysgzTCzLXw1NfpRL9ujoIL7tj887E6iJ0kJ2hIQXAEGnXu5/S
QwFoVexsmi/q3K12jPzpTv8Dz/0IoYWXLbQM7q512G4Xv7gSr6sav9NCM8vnNbCS
nh8xOWWrhXUmi68thMI0KttZvuw=
=R5Kd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f719f191-0b12-47ba-ab74-a5ce2bcf548b',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAnHC8+t6+u8ohG0tq1wqf9xN40spiX1uMRTD+8COWneHC
smRUudFQaugfUwFmsdUFuAiBbUGHTZKGA5T8ez4deZanu/Edzx+vAPHUVexpgR59
6Kd/uHRF2WIogaLIpeyicG6x8RIgE86AuTG7XfyKXfpqAKAKd1mndgEJ8XjxsScv
kUeOdSUBA+wA7JY7hFQ6lQ4KVV86+VILGDYvk6ahDcLv+Gtw9OJSRGWbPlIO8ErR
7YA5Ym1YF425BHPnxtDzNCaI9rBLVGM4Jgx5AgHbSUe89/OTEOunCwR0lnDslvlN
AmTIcew+1ZwayHf6p7P64Gxhh9m/wYU7Viak0a8EKtJAAfkmkwbhNzshCj30Wlj3
NjVBwgrylyvBpTgKLGeHbB6uHJCqgF7LW4NShOHgtvDgeqfqdDafgbeydU88/z9m
6Q==
=cQhY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fc0516d2-9ffd-400e-a218-74cdaac1daf7',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Qxc/cmeVCUEcmFBQD3BcLs0bk9uLMcLHMQh9NtV80SBw
BMTC2C5gAONYw+2TGnvt0yezf94KUij1sxQig2iem7W+3jvDfcPTB00bxBl1/+8c
CBDIDoeGUjXHdg2opttVb/+693Qk1U1JgGXzaQjKgt5NJKg0GZIZop77tn5Nr5uK
RhBFEB4a+mGN/y16xLtMo0M8ea+Cc/IRgLUA7+hi+AiP9cZmBwQlpX5Q3ao6Pc6f
teNegnJnl1Vl1Hc//voVBghZGmqmfUF1t0JC9fuNkLn4F3nInRX2uIeaH0ZX5ucH
TALBnPLf8Xtr2+ewas5klKtBdjNnUOmFYBLODjEdKBzdD4kekeeV/Dj9tWFiC+8U
8/mrbvDjc/Tb0oDKh8GRvZpczQDMOGfy8RFoM2higj5lig8Zf5Nxco/VVQ19OFQE
Apd1HqrhFZt6ZfVyitNgsuiq4bGb487+YmyGtNAEsmJAPf+OYx4q6brCUxfPnVx0
vzgisvEPd3dQmZoPcowm2WSEx96Zb35nf1eBH23CjcGngE6iXUn7Qe224bGbgOaG
eP/CRpVI3CnG0tgNjunP+oTz7gCAsKpNUyImmnq7nQO0XDaWqiNHPdV/llf8kFz7
oWfmjLEliu3gkbVoksDabEFzvsLCbEZYDq5/kr4TYS58SS6l3yt30B1hPaZgBJ7S
QAGCKzvJN8EorGWlWptTEUjC6tUbnO+dqjsdtB2GH3xIFCSK1d4tFe6O9BwfBoqn
37mpmXubq75tIL87LAGpAUk=
=9Bvb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
