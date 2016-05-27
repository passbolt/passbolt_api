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
			'id' => '088133dd-02cc-44d2-a9b0-6d2f7bdd36d9',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAuxKK18QcWzmcHu0c1YjqdNVAyT+rteC5D6NAbF/a/+r0
aEsCm0nnW+5ouRKzZMm7g07AoTSoRVvibg3l3DJBHChzOEzoMiHDGqp29QOrTWp7
Z5h4iEAB6kmEAJ2yYYHn5Ixl3FQXs77MHCzrVgHzD8ZipwhnmPtVHhrW4lY1JxVz
CSO9c45uppY0rJuBYJi1tUAVGuOrt3dDZRtJshKjahvLdahM4ft/pjHF1PllHSuA
npSuA30BMWtr1hc4D6zSN7Bmr/Mz6VKdxjeyFU6R6lfHOMuZ1ZcJLefrQlhLrK2S
+vJTE04sJpweU3OCXWWgAcJCAmKmUSNry341oDkzhdZseIRcQyWhHMANnZPjhExC
37gvmTpGPF0B06M3hnd1WeePXaYL2fsLFT60DupSbBCkFsf+D/n1Iolz44wx0Jx6
PIPsLm0OhdGoHzAQ6i1X7AV9hYSysR5Sg8ez3yF5UWwi97S4eOvzOLNVvHybIeQh
0QiTApqaH3XjV2TTH3ljmZeCoVcW/eSBVMidFJS2i5KRdDmILAbshfK5Zs8bUxid
PQ+sAkfjgVFxIyErRH5XVwJ0rI8IylepEPaBu6/329VPbyFffKNsc06QpyCCNnfN
nBj0kQ+hNw3u4JbWqQBZBWTA40muU0RtZjWNF2hBWHAdMfIH1ayMmPuBzKZcWxHS
QwHKKOfNTibbm7xf8aId7JFYRg3KRxiTJrrW8KpUlBGckB0cDWmeNrPI4Vx7Kf3D
2XhcdyWe1E8nQp5FvGcJvWgYYn0=
=/IAC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1645bd2e-8a0c-4544-accf-1e9c1acbfeb2',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAnko6WxDqylpr8IZCCNilG6Cwt20Ls2IpXfhgghqVh3vA
aZBOtvbCpmogOVKPwkMK2nfWiKpiFzCofpFSvFvXrhrsqEujv7NSiHY69XHPHRDS
LXyrJybd39rpiH3L52TFNy7SyhYsfvCxR9TNez9k4z4m2dx8y4i7cIk0dk9NhkU/
raZNi6IVx5J2I+8qcOAX4MzXD7vJglVlBN8xQgEobAIRvHr5cleQx61bcntx1jjl
NMfPOECx8ZW+mN+EhenGb5dqfbMvtoUvf5ozznMUY957lg5w5J95F0YaCEirOX1Y
Gxjv7t5gk7Mf1e+7vyXBkBlFkQB04sXBCQUqHOIHByCKVOLUYtncAmn4ADXjPSCH
QiYhP1IPqbc0UUQ2hbwfipEmyB3x52dHRmJwQ0pipnXuo5G+ErHDFM/Oo7XtOWNX
+kf/PE/DKHtVXWGGENxprzPbT2PtrdpqTHO6J4LLIBGteCy2StDiYYvsJYhtnXOc
dKLYUkECJV4dlF84zQIRdpQWzNP1nrh6bywDVndFusN3wziNpDFrp42LHjXtEsup
LORCvmrLP4BaEE+HdEKMQM8di0H90CMjkrNImB1zR5EUdzahL86m81uzNYlBcPkR
OfhoRhhPrfHU4QU6izNY8gfnN2zh+mmTPfH5GBOioDif3/qOYJTN6zXSM4ea8hDS
QQF3ZIkDgUlB5/Qo01qUmlzZ/Gn1Ox+sahoWB/lNKbq1V/4eps9dFrNkS2Q5NJfe
uLhMBW1rLklYGeIT2D2EI4gR
=viAH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1b8fb8b5-7dc4-46fc-a0c6-c58bd8db8a07',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+O6TyvZ2yUboWENW8NYIs9VwqUSHqY7qF15RwzVUdOUn9
T6bRYcYxdr3SwsCp4dvUVfNdOO9z5t+dooMaXRjUrrbqWNexAUE/7X6FWoHpYB5e
rwVIzH5smGdUpR0092Js2ZzK769L2K26gJ3P3D4g2CwhxHK6vZdXH7a0T0R4WKc5
lStEemUiXAdIaNU9hxc6afidDzt8zSUBQeB0CIQdGr1nhZLIcZGMKmCUU/wuEDBE
5t8NoZl2k74zlPU6Pr81CRTtlQJ2UugrRenmFuBPjaFeyTKq2KpjVCbldhlu4s6D
ZzlpCNAld7eSsqMJFyh3Qk0whzIJ0evElj1Gf9bq5KBCqq4MHQhHdu/p+sHINwN2
xxJK4RAZMDdqw83laOXSg5zsPcvfmax89865KP17N88sAzZdq/EdFHy0V2xDf7Mx
euPY7F1IdWxc0XuVlUzO0pYnR6KdZPTii9ouOPWiI9ypE172ibWdSfWIOSc/fSfc
tH4dFMpC3KUjEe0E/7MfsZaXSj04wt7GcDJyGUOUfLyDCl4YyEG3Q9xc45YUP8kM
eee8w3onm2tLUSG8uLcNKiGHovMcoSJnPoIOe/Wi3meV+nm/UdPc3KaleMXU1Qug
VGX85HRk4PSHf71Ul8HFyz4yxev/IPDkN5Y56sYh76VuWPPJtUJcIv8fKzqBsfHS
QwG9AodG5VqNPDCHddbDHwKB2s6ziQUfmR6YLjxSJRFiI1gVrcqfM+kPUE03kURs
ZrhG6D4jTSRb7HwophMk9LH0260=
=2kzh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '23aad361-6540-43ed-aec8-f2cae3bb4102',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAmQhs5yTbD4Yh8X4SAB2Xyq3jBhYpusrOC5oFbBZcyjSV
VyPwVmDmctzUcfnYgwRYiCfzsLDeXXSb+qkLoct9RWQ/AY7FWpVWXtQbYURz/B6h
SNFBXpCahiVmz3gZxO7fsGXVnCw9FtcJQJM2Ps872+jiLlagl/nH5OMq/KBgqOh1
an440f5T+0XOX7pAE1LMSh2JyTKOUhScyykGzulrT5HMsmQkLv56yL/Zadcd/NdX
4JicOg4M95zEfQFSLp6MArlNFYE/B+6xj/iPDAkjEbFb4tANGoAOQjoYxjAbbF0b
2sRstXCT2RMObtzJuB17RAPLCqFIt0pIavJVHuizzNJDAaAeGhO5qMfsZP1NuGGw
ch0gQ6MnPUOQRwkmLi0qOfgEeTPfhw8GSKHZMTYj7iMyAvt8sOUZp6n2c9CnUH0u
2l4d5A==
=IMTE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '29dc84a7-f244-4fab-a08b-97478e45c2e4',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9Eqiz3xa/tlApBv/3XwQ5U7CLkiTCz+hMDmNCZboVyqTT
UCwUuWFtRSBU09DFl0R9v7ymbDbCoL6s6Y04yam469659dWXWZpnMIQia0IgReai
YgUfT1XzlbQEypTvciBaLCzHn6vmayvX013Y0BpgQ7ruKqUNpZ3Hacsw8FBCn5fD
aLjNNDQyCKi5jUsMvK9tvb79jP3OGkJhLcQJlzWV2QsoIdBb+WY+Oiyr7GRA7hZa
el83su08rLRhC2NqGylAMF2H2cGjH508m/+9YdMV8+KADn3FZcXUBrC2kMzWzdVa
5VVVu20QbdHqmL2ZN3ss17UG3xncLPASVa6t53eFexcRAROgH6fqx3R/o8bSvlbj
0RWFFmugbHFPvxRyF3igqlzrqnl8sTJFW8jT0hzmzdmpBMPIiZJzC9MI9gDkCJjD
Rok20d8afrPz4ikcyTa7rnnI7VZrOnTk9GKrZLTJQuS7IE/AG8t0uuMoMWu3JsqP
IO4Xo8CCfVVpm4vCLCKEvI5PH163BsxpSlSnh1GxR8rdTnOAjcuwf+8eTom5jsY+
b0yBywrOwC4DHSra2s42cOSTqMkyatyDdqDi118sO92Lc0slWUZlwqduyl/drPhX
jramL5G0C+KxkWeQ2Udv9dzbXVPL+xWjprEoXwk0N3OUBFE1eMZo96xnX9pkoNzS
QAE+cLhr6uwAG8KtDDZLWuJxX4aP2fZuoyPvz3ieHcvMgEvRNBi8XC7zlv0JWhLJ
+ZHU04thAUz3dRia1rfA4Bw=
=lQFH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3039992d-d73d-4dd5-ab24-289a44aa6c13',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//aDzoKv/Ofg1B/Yfp2zo5peVUo7D6AoMdO9Evddy/5Sjf
tQ6x7nAHHn7Ia7CuDiBX8N2paD4feFKHjKMN0jVHcwC4QpHaAvPnCP3ujZbelHO8
v7WwaP6lUE5KbGyWXPQI8o6xsQ4h4GMca/3IgHDqfspA8etNnMGE7swdzU/zvXLc
LMCnsjLOW9H63lzXjNUORQ3jpy4sHOhHgmGxYNJsrhPn3fQFr7d4+LvqnYGS3CCL
5t7eRfbAwP7lB9BFaXx2hGqArSnLu+3Nf6tS47VAcaErbc2FBqoh6OsXS2eEMMAW
rQyreJLpZ2/EQD2PTif6vpni+tQCeZPSu0w2yjNxK9N6EmibYXGWsQGcQkmw2KUA
sBWVZTqbHBC85Njq1rB9CKLgrehvuuV54YEZJS6GtDs/i34wHshW69cpypYeGau2
AVTTidVx1RDXr3M6q+Yn9GH7HVCkC0KJnpvKifEa+kjfS5LVsatpz7IvX/UyJOfU
z7wEzwGFro1G4ea/IU6gCjiwDFAGxxkFVxil9ivjjp47SwtXBuSu4LJGRyk2P7gI
es6uN4eQayAyBSHcZfel4MeE9FpPPZV6ZQ9V69I5w5F2reM1tZlbzNSegsKb8hbd
e7FMYhZhkBI/bFnGAhyyeg6Gb20pyQLQpqwrpXlNiUExBZl4iuDqM51IqVw8T0fS
QAGCsGaKTt8AgY1tGF4zd1gtSLq8x82nQlf2q+JN5H2sBJe8jjKKl52+VM/aqNSl
xkeB9ANBwqbCo+ky4U7GK/8=
=lJPA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '363462cb-8299-4c4b-ac32-be50513d2d71',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//c+8zjwGXfj7TXGdYQRc2dzwrv7yvWYqiVnDcJXo+mgk4
P/7TV1l3ySFlWaSj+MnLmMsK/CV1eWMW55y2PGfkUGSXpYn35nIC14jSImtrgauD
+Eyc3i/oScCQIi1vGIsEAKDq0YIQRImU4ZSBuqD/tgpnpQES+t7uE7LFpj3pi114
IDO4l9Ai0um4UJgzACa9iGR976hMCz0JiqojHPHb85txPiLv3R5vItzZ0cddnlyX
cUKT9EP/dLIB//tY+rtXwWzWjx75XcfZGPzsb3doLreZUHLudn2FyN8m/fLMwSh8
nulVkaMTUwupIqgqketn6UCpU3yNbcHzSIq3NYeSMl994oAmnmNIOZTtRzav2gln
gDaBnoCCwVcZpulyqIvuc983vvphj4HV2jazKnpCgidArM93uHUIbNQCXz3hQ+bP
fb31Mv8owu43qeLohytljMMb2cnJ128aO4m3Y1hYIsERvY7kLZVnqqJimgmtfDKo
oH+L1NH/ITqSedjzKl4PSaHusXR1IwzvslrI3A1MaGLL6js7KFYIZBdpqUbRHpA4
bBNkbxlNrOdg5kNoq4XhopXXityiV1WJJYZoRCqQt6+Figgum7jEq6PXTC0kLcur
KGP1EHf0QC5yCHB2F8/fnrHGsHDU7gDur5BgaFx82eGlaXxT0kSXSDoKQlzgIc3S
QQG7sSnfU4frQXRzsO13ndyDj7D3rwW80ZQVPZ1IrqQxHqzN7EdulHJN3sl4H8m7
R55oRTH4KwKrUcUYnHxgQabJ
=ZAvd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '38bf1bfd-af53-4ae9-a764-ad385afb55f9',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//VDb0FE1YC9VbWiCpt359HZoj+Ju+392dPCYy5nTJcgD1
2DzINnkpwIYatC3a+CFw8urwMHpbW4Qx7vzJIr+giCe1Q17d3k5tP9jj6u1NdKsv
bphFOpIzB55CE6va1jbZ5rncUu5QVZcMUMXU89vhVKclUKDhDNIRb9EhLc1FyQrf
lVG2mnSb3CEptg1hk9mYOHgV/KCI4XWbMEtIqlzjWZAT8X5OGYwxSxPW3ULguUrY
XdoLWvfI8uF6dMWySRKA8x2GKgxiuhNhj0/Eqw/hcfU6k+dUh0MJSzrRJK9Bs3lx
Qy/s2gVm/U5EVO+ZylwPhuc2Zu6iRnumK0MdCDcNuZCvwkyRZUe904J/vQkqFQns
UUJXkurJpenlIQWfgSWSvn+D7Feg0zmnHfNb2/JBBNxj/OLUqKW+d784vP0hZN6u
7vDhqknKy3ZwHDM3VsuTVUhIM/2kRDu0T0TCYXjRof1wV5FqE2GiyQsEEjW9ST6S
Bfz0f+UEyE6zDJxqJt9TEV4KW2G5Y2LII9zPhh9JQNP+o0S/HIXXra16SwwG88g1
9epAeCSEpn0nJb1ZiIUATSVeBEwNZtdmn/ckIyJlx14olO9iTNPsJeW/S1fOFyQE
v5ggSpPJKnqYeEEsylTbL2O6jxcddhWYAMCzpKuHj5WGVG2Hu8exgsXdJDWy71HS
QAHBg8xd5GV7CJ8v0OEczN3gR1thP8/W8C7nLeiAByMW85pbfsFaUF/t1RsSKMvJ
06oYqmcOU5Xpl50jrJVSLN0=
=Fdqn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3b1c297c-8245-4826-a0b8-486c547d7942',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8CAsici6WjiVMvOBw61vWQc2MueVGvUb8Zup0qRZhbbyK
RCrseY4B9/YsLHuOl+Zx+M8bnd3Jmu2aNl0aCPR3gsElzgsN2OF7ThVq3vrgTivf
3x1o1HFRu2jvEo6BXkASfpCZ9Yh5jaTXhAh7pp+GyQkJaTDBbXe3PLv0IXDewwfF
hu+HRNPFi3ySAh38xMqLQoTiZrs3ZuNzUVshhM5znTpwDmM7iN/Np/J7WQ0Ankbd
sTbV/K9cWdFrblrSDEzEgN2/rmuefhKEhnZKCy3z4pfmPeKXTl4rPQ8x036A7sHG
wjLMNuPYJ0hpLRsdSqe1ZAqYXM2NktsHtvvIq+veek8z2Xe5A7o+u781P6DMxq4z
FtVEq3yqvaIm0BSAtfHWJm1D8IZrPPtoaAMnUglJb5YOstl5aTZ2eNto6tzIdIac
fGEhdwq3b3QyhwdumETaHxYek8/s2Aqn8VKZ+r3UdmHx2OTOnLFrPpj0Ymqp+UuK
S8ooiqy8Tyo47Qnhr06kLnufS8K0l+FN71USjVPLiVzivh4r6a+8V2lYdL0YwNpa
/n3/C/ti4saFzYiOPPGKe7kdTdrStJex4/Lvd56VVEBaMTpXDnjF2l//kCP9Cw9W
Or/6z/1cwLdQ4kCkrUEE+bQAmmFwqy6XzMgMzuu1sMsS2ql29BN7h60MrFDI3djS
QwFC5BrW1OJn7G2NeoMQUBClJLLJ6/ShrgPTgnWDuChpe9297rqkvvciMdJ/HEIQ
DbPNGZ+okBe2scWWTLT8daRfSYM=
=8UYr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3e86b19d-1c11-4b91-a5fb-639d8f782148',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+NikczqqyVPvJle6kiSj6Qdta3FLEpkw8nrT6WX1QbUiX
YdAMQvQm1nsxJ4p+G+4m04OKzZbLAHV9f6Cxf5ODEF8MKvtfa9zyBwYrVheZRywH
F0TE4jBNXtQ0f9YrrwQAWm1SzL9HXsLcCMu+1Q/TjsLlC/N+9D1keTMQ7nLSMuWz
5yeSsDLRVAVO3U3tHKhACT84IXMYq6HgZKwtM+4lFk4/wH3AFslEMEYvH+LJcJvq
KwWkzanQRWXsNfspHaJW1/eTS4e8BOo5WF8tsQbOql5JZGpYCI5VTgIFNBZ4PUaS
yAIpMvqI3/3qc4+MTnEiahVZRBvCWfMEGoIISDy9SeVV3zu2mVWFIvjwy2DyllZy
NEuqZUFehkXH6gw5Q5ELxVEZEtvsquN9uN7hIiILm5LmA2rnB47qfTczcf0PXqzs
OWjIA/VcTU0LCdHGIuroKnVm/ff6ytHSccui3hU/RusiofS2Dhr/7b9HKLb4xJSO
Y91rP40abxCz5Bn4PqA6NlXHJ7mqssTSvy3bydtoNXJPGxTR77rfP9JMiyRDbMQE
1dADqJWo/EaHqY0pwHUf0uLeNj8T3Zp7SciTt2+8+JRDF7wj+DnrHPhOifdavzYi
1Ys3aUndOxWW0U7zMfCSYCJpXaVsMZonAtVOnjh9Py3D2/NwAw1uDL65RDhdJY7S
QwF2Yc9nlxjRUsLYA0pHKKekPgFaBXb8Qjzcn5VRHiQo60C7xyxtoTZH8yomnNIb
7AsN3WdE3KjWRlixzOMyNUeCe2E=
=QGA2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3eb773fe-a644-489a-ae09-bc27a1ee99fa',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//XlzWBoSX60enUkgfm/9hmuU2vLwtlI82mvTE8Q1DEow3
CtjT6lLnj1Mm4+P5C8Xb8nQJz+fyLeSXu6lEG66xejw7H3PBqkvd7a1O5SbJinSH
9Y0oEA5lx+itfGC3PunSW6/sFIOSmBNF6T4qep/Sw/pfozlvny9u/FTpIMwI0Zmt
0mpGXz91Uu+3mvM4GojEZje8ReP5GJ2Y9AoMjtAPYI3ve6qRYS80xoBJs+qHU87d
wkCT2Tbs3dWo47/MVzcpdDiouxtY74lVIaU3QRnmP0/gnH4aj2fnERgK+//1CWtF
1kcDwitKu8U5S4d/xEVKPQ/lHLiyLQGSkMKJ5l8nXeRwimqEooVZ9mmZOWhiugUf
p3bG1HKF72lplFAQAdDtaa+c/qqsEiCG5CRrf3af3HmzzJmkUz7aM0/P1NyYewBa
RZOKAh7ziG01urxVeczhIn83mXLoVEvKaYPEMq0U1QielACkb3hZrSdTEVmMcTyW
M8C4W74UdaJCcqkkdFXx5w10YZuAxagqrKRbuvrAdN7AFjgLw44mNYRJKQ0y59GZ
WEp/6zZB/1rhimLlsZNhRJY7t0o2CcNJXbSeg6IoD0lcuS3C0l2kJFS7bRZmi65/
jyyOwUqCtwCw+2w+MWpuPPVrUegb2J0KpP+oPxOXt1dQrYpSCt9PY0VIZOnIKVbS
QQElSY051/OqKUx3M9hRNKY/Leo5Db9OVK7oDgjyTrByk7o6uAXYYS+gQw2qZ6tF
yMSnGc/cnHCsU1SLebbEbY0C
=IKqz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3f5a50a7-07f4-4f71-a5e1-3e94c7e3a4bd',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAusBnva1brpAyv2in9crANULvb/iNJzylrzeoXqv1jhkU
RVhVFzzqMyrpDIbVHAJl6CgC3I+icAuklJAb2a7LN9zOtpRpbKxs3uBXoSlvKpMo
JfWayy0WmPqY2sQqNMSRVezlE+xL9L9psY58wDVa3UWkRUkZIfyxtj9+pfYGdAl6
EWvY5a9NQ8ghcFLsMd5FdMBse15Mm8rZOL3/04HEkRlX2CPkLQRqzZOUZZh+2gCt
YCLXJR+82PfPz6xrwzsvJlS7+lziCpyaIVWmXojS6OxtrCdDBjLEJfgn5YyooK5A
rsffs/Gvt//ZGZaPKcwiIhCg5bNRGalAXAhotZaJO83m/GF3fJElqX2Qz6gadAOU
HOhvAg44xRAbeTvOQnz4B4UgaePUvfDgTo+glHuGqCKY6c9YRfNmc6g41hYd6FXe
6fOJZByrunKZ0IJdquhH4VoGW+IYbnQSHZzbozFIb405+c5RAJFEKIyaFohzqRZy
9bQPF3mgZ7BLSTg0ExY1fQfWpNUgG6O+xDJ0o+TVsohDii8j/e0PNB66tQ6+HvOR
9Wt+NK2Ns2IfsciIDq/0Va4sGpREXtccZvahEpC31YL84jwwEwQst0oUNzwrMBNx
gesAKDqlb2RQHCc4hVjdaKDCJBI+u0cC56/zKg/erAqvwadfeSW0rkUJKzQ3Jl7S
QAHoRDXYnffyL4MlNz/fPi0X4E9KyUww1khwRgpjSXQPrkw1YGfWCgP/+BimeR8u
AasYEWp25W/LhWxGkPYgtkk=
=5rOX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '44dfbff2-5ee9-4dad-abb6-22e18d3614c4',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAmYuq8zRF1glK0V8rvEtr5NdIj8UE4IR8dhZhpfPuSKPy
IDZquI7imtPZicyX3+iICokGXVobQ6La2tmRzv31KPcvdF+sBaha7WMDI7hoz2ZI
m3PHksSzpzWLbcgthuLpJdGEDPaaraQQzzaM7rxWrFf6GwjMXE14+CmcIRyHE0Fr
027/6D99ASiHqqlDDM9BoDYG+6xR2qdZ0B+tzl6jTvNEU4omKbxhIB8OqgbuBZMw
x51IrBMg0lXS51gHL/v+SGUtf9OxYlinAs2hYCgkvbviI1kBxZlFrn9AX5qko+Xi
LHNM6uIqVmYboslc7spxcAa+lA7IWkfT2r7U4Kogxq/McJ1BbVlF7lN3MfBZDAXq
2KFN+TDV632ZhiemH8lZ0MwYjypcecE7S7d+hWGcuZL2dPbIIOvGRB4DW3Z4CO+V
S1AfIhtLxrN4dBtgNNPge5fM5OEiDm4Hnw1Q17v8KRYK3+aNznUkGLloiubaw35y
pMTcB9/WEp+871snb8OB//q/YIRe5JxuHS3//A+AlYiy8Jk00xQYPX8li0L/wSB2
elTE3FBVeh5utQoA2EDSfpuJT+rwECCVxoyFWZV398seqtpQhIS56gsQCZkFFBY3
NoBcz6ZWDvdOV8edUhOAYS2RK5E0Wac02UPqd1XtdzpiWvjsBOh+IFQldC/u413S
QQFgUs52JetUFeHCmp1P6u3ECqlAyuNJNidtgGr0/9d7+KVHWxnOpM8/nKxuTbcL
6dvBwSoq9hGIQdY3vRvAw5af
=B64l
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '45668535-bfe4-4678-ae4d-f0d85a81a423',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//WicEQNMkTj5eAkkLLtBu19JirNoXcGYxNSJuPPGSpII3
4QG7MBIJ9K6L6XlG69iJ5qbq1h9B3yzEEhK4NKjAf0Yk2Nbdww6V4wyEOho7Z0c9
ivGnLf4g2I/Dbdr6v9GIl3ttcvdCW8yex4/8Hnr8zz8w5eVJC8Xmp4gXKpnHcz9n
9+e4Fskhjiywjcpxkuy6SS3nhOUk2aMTycQVOXgX/BswFfGgCVZ4vFAaSuB7RpaN
+WhNyNPo9CgDaiIqF0sg0GuYFhmhmkO/ltBsOrB1vxaOkt0kBk5Av1Mm7k24gfvL
hNarKXNZT1DtT+0PU/+eg3CFDvYdLpxKNSOcXwjfnHmmFTcf1eeqlvKUtIh5aDyc
Yk3BSvORcklLXPCtQnWg4WMmhQFhwkwe7YSZzJhEGE7kTMk3Ajt65ogUQ6fSdHl6
qLFsJUWUsHutw9M9uJVZJ7r4phUnn9oP5p6bYwscaoY4COPlb0XA30YMBn2CnyWA
2hlUUYzano6uCfeWy4el0yaQGdnSRCYDI778dvDMduxP9mp1t51CrLEeQ6IiwRjN
NI6P9XDxlV1rhJZ3LymiUXZ+3AVhvxBYfku68JqncQtsSFOV1WOiimkKtaDTMZRQ
TipkoSy2hOYfE7SFLoJ3KD8KOZysK/vJ2M8QSR16AJa0ylTxAcfnnAkc3tvqVZPS
QAHRBxC7J8Tsx7TRozFhDtdBztrlAzyAfmCuIJuf7yztGENvxQ04o7bCoPqpBwiU
79kH0yoXgMDMm19SDfKsmj8=
=gaha
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '46063573-8d05-4318-a247-009a38f42e94',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsqDnXjAPM9QXUxon8xlHtIctXh4mNq7EhD6fiKwBK0z8
lrKaZ8EswSo2RNId+FpnnxPWa1f+cU7fp/9rKiOASItrrTado0zVVrIo0l9wcRr3
r+CU3O0yNB6rVb0KYp2qFA0rVzXCzX5EUpfVbhz2VhJxxWb85cTWNOE+W6IDxG+b
/cWtreP7/+HcckblA3oQ+/jlWQ31j1t8HfnMlPS36dqM2/jnvJBem1nRZMTYUkDr
2mzL0ZphbKb62M1U8JxQJawCgu8hxgtArJF5rJSIWcKGmDqcJGGFSNqnhSZ7zro0
AaJRckZYIqufE3x3EChkQBfve6F2oTQWmN8rfUxXiAurVQQeHXi0tIaC/q91M+48
pRQ6c688YeH2lpV+1IZJhVSWujLVhAEG/vkMSnq0hQ+V2YseRHZPHUmXKoo9Z2tl
eIakPYks0D16t6DnzAxRaxyp5JIksRAnqBXUm0FAp7I3z+1qnYTUUXqQt7WXRFGs
jxEXCrFDC2RWxONZ2nozobM6X6jUV/ayJPA00LEgScInYAIKClnQBwByXKUsGS8c
nfiNtGGH6cF/NU4CPUiwFrX8J97UN0YFU1EXhxDxZs2MUgQ/E+mV0Axs7idf27KF
RCuTzddrwaZaFkZ5cIwXS9U6C2o3ovbaVZdBK678mfh3uFxgFbPIlckBKSoV9unS
QwGJK08nkS0YuYFyWIp1Lc7TRoqQYoJ0J0SKwFnsZh6U07vIiM8L7nj0biJcpt27
7nPFjz/3LC2h2l3ECaCCj3E0v/0=
=H+Jx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4997952e-5866-48c6-a35c-0ec026709085',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+IgzKv/FCBOKkAi8yE0V7DS16eSlN/AU4i/y1xGiyUeir
ZmxteIEtOKGNK9cCkU+HhmC0R+8VueAKPv0Bxuxcv8W6VWFQxU5VwRduPtm3yJ9l
qULrOmjujz+XIDdJvxda9ThN15xRzDUn55ktGtTzVIy5ph7Q0uJfiTZzSc60Wiab
nzASlKfe5jM/94/E6mIwM53wyoUaBfXDfrYj+9ulgB2iU4E2GAQTlTR589y6pDTX
ApiinDY1mOcI2S6EriIEFFObzfEC8w+6iyO0vLdg8zpLWSq4Sj9HYCCsjfsy5Xo+
dYHj76zCRiKlM178Uv7rb7IvF93XnRrduCCctAfejocOzBuyU2/ahegKG4y8hxES
0BhjeIJgaN3cj4P4hKUyhabmx3xlP2hu723XU4FLO3IJ+Zs9bkoxFMb5by/2HCXI
hh3nI+2z/bzQyuWOKzvb8EQEpigrrKfV8pF087xAiZfAelKctD35/DBoUbmH6hQn
CUNY9hS+tHU77U7cbgBOnTWpl8z8o/HlG6T/+1geoh4typoHBv0i/f4wNzFYl9oS
gDg7gmmsGkUjBg0DZ1k5AKZw0HDOmIR1q1Jn6cL29WbtWJkBWWG4EcU16pk4iolz
uu2FD9LKR1PGbQ+qalQQtLgdeCDAgDwkhaLDvpB4TbgX3MUKt1j5a02oV57PopPS
QAHsL7darx5XqugnT4bCXcxU/XhcjjrZA2BjNIBSoj3j4w3wkFkIR1rSwhBpy8uX
OvUUuP6Vk8JGO7gD7B14F7Q=
=2Te8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4ae7a9dd-abf5-4baa-a322-f89d5e328ff9',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//ckgFVvgmy+7calv0tbM/r5QiVZZkVaS/QOJxzTNe6d22
3Th3K/kvZZ+nrZEbA3SSGLoqxa3MY3qEnF724SJHHMvzlruyNmi8stHN5GnsNCgz
0NJApg9MWdnkHCE+CKhgpRHMbvkB6xblFFvw3Y0f8yYLBVspNXrR7BIM/3Mrd2Z5
UoPCI9RPEyDI2ywBHzradbRHYTe8A9w8CSubg1EhG+K6PCq5hw7f0fBcDYW99zuN
SfsYxwxuOUlqHkgzko+FVReIuuqG04IX+I2YUC0ThZCKYhvMstUu5SUzEh3mng94
BYf5gsbyfba0rcKmPq/Porfyolw3fEvfS4+7WTyWnUO53SKlvwvSgM+Z6CpZ1Zwp
7n0f8reXIUZO10C4isxEgHR/23Z4qyaeurkmv0De05LW81O2vOeLYvvAER5OMdTW
Fgic8lrEg+4oVxRZujYFLuy5AHgQWwuHMn6AXzgYGbifqH/nZsuGfCiJh/iOY9WW
upOnGlQAwxbY/JVio65KbC4i01Sa8658gtyQO1XnZ384DHdWNlUbXdeSo8sFf2tt
3XlXeNZcBhR+Uob/q5WqTPu19dNt8H9LqBM+wO2UlSb/07wDlwE1Wk3Fm9s1Vvr/
6hU+dzY051YntkpAuBPzTQwKl5E2JriAS72uNeNuIx25bq+vfIfGBVssVhTQ/jDS
QQEjPvbUgom23XqDP62GUnTx1eefR5nh3rhXzu1SxsueI4Fy8s6gU4usGzYj2EKp
dejuQRPs/BDYsVnvV2nmqIAQ
=4e4o
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '56f214e4-6761-498a-a6c8-732499430b5c',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf8D4kbiPNUFqiiW8861mRgiIzrrX8fOYPkifHUripHxacN
u6LjS6+AcuCTrVGAkP+g/oCrqvmu8/mS21242b+I8nsn/TXPGuYCgXHtDNw5x/QR
tf2HCdcA/fJpJEefsHz8QVZ4U08P5s+KTKDdYWuRbUKMqEaU14Ipqhk6mNSi2AAB
WQIZydMEXPR0lQ8jCdiv8jnUEAXkYgq10IdVeBKgl28FDntQ6PbIS3Pg3yhVb468
o+OwwIkBTylRi8JlH6VtSa8q/4lxOwSiX45lD0nAQMfbT+Cia0hATu6H7RkTeAkD
4KHiObQBAFCELDRumZ5fJuZ2UrejDVqnkuwvSNGsDdI9Ae935deYeX1XDWDvGDjO
wKOfQ/jdsb2rlnfDKlcx2KB+ljkGGu/L5SiTZ9s4wZIKqlY09Jp49hGcsoGCZA==
=QECc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '60dd2dd2-d370-4fe7-a928-4f182aa03c35',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9Gh1XbkOlP1E/mCT2lgtT3rKXrRVE0TkrEpzTWCGEqOm3
6bsTBSFCEeaf20hzNLO7s6D+h0pL79h7uSxZmci1re5gakyvviGYZy2AIs9yCz/s
T8jB+YgDFDNHdgTqf7HYsxK2cidEj1Jj0uCCJOQACcq+pkfYKPloF9YkPw5Zeq4n
zu/RZNMwsfRvN+vkLEd9+uUfxRD1GIZvlAJZYhQOURkaJJlrwE7IJjTBgZNzOom5
xMIEIgkXNatfX8BfJf+S4bvCZGdjYAPucLZFrkb16PcIx8jxF1W0mEDhtV9NQefY
TGxJwteTYNu66/pcsozf+AOIqrRJ0C3xjYdrHqrG5oRBUvuTAeo0XFIrIM1g16gA
oJiQP5z9yxoQ2LBGEQo9ZCuch0YyzrBFKJVMc4eDW6URO4lflFW8vJSg0jr+zYhh
ShlhegomdQVdpRZHjojn1O+wiCLqWaGzuZwRzFHKM+vFW7oRhE/RkipYPgvbKs5y
epb80MsZ5ucH9Mn3uBfW6wSwthNPoStCipfIZDU6vG4NsaJ2VNQ8eP1YSY7R3Y0J
+qCY4Cn+Z/ZEK8PKlBmMJHqybdPNwwmDL8WELGzSp/DI8bPNERb8yK5LB1/0mICj
guH+7rdxAkVVWz7bFhkb6qQxbMn+0u1rjt1YTCQrR/ZG4A3BNMbiK0LQoO5SvSXS
RAHgFujAwMbidDpNhXM+HegI9ZxQfPMdbIvUsB9ayQj7PCjnuvaAkq5Qy+2HryaB
dGDRPJFjsWZPDs2zJ12ayDv9f6tU
=Mob4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '660595cc-296d-43a5-a7d8-f4680401c85c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAiORazfmMTvY6yq3swjl7XYUkOuNquLopryLq8neBmJbC
lB4nQAKrtZeueEuwQNXBn232gvocfkswwkdPndqQ8NDeegj4pewP4mQbZJ/5hnVx
+5FOacfIQ8Z+hjjApYlzD52HyvZtGNWLfiybMOL8s116bMOUQIqc53DxBZzIzNGE
k6jWSizqoCkZ3v3ecbfF/C2MGkLJKbQxxcmKPr+kWhhzQmUWJEkFWn1JtG7WRfjZ
ZoHz5tiBJSGlVgW6qbulv+NlS7dG+IOuJFpmg1J0PB9t7T+1jZhTjunlji5L6Hs5
8O+S/aiV3K+FE7vm3KsAGym1lw3lAhGXQ3XS4wNMuW+vUw4ZwMhBCXYklznFOl3L
6EDsyRWqG/US7M57o3arTeKky3Tf5vGnrQG/+28NoO+6vl2t9kcvx9mjEKb5g9xB
O2cIi+iqOBObismYK52FBTweMnuNSX0mVpd3fD/8qFoYaaI8Tgr6m9RK8IEoxMht
2CFYLvYwdHlJd9ry0F9GZHk08buLOtLiIUpd3DRhrlfQLrF9jKBzzalyhIKK5CYp
YC+YUPFu2YLYWchw66i+rVZ0Cct2ct12zEkigF663HzJA0JiKeIby8XRcOTYq6La
j5isuqBiyL5tSGalt7vvupaSLsRUBbPo2MrCKxN783HmWEamtgw0bU/rF08KDQLS
QwFgcBd21+5cxeJB6YoR1q8FXgEYM01UgVQZJUs51sDIrE2pW4CHdQN18Gf5/a1R
DiHNK7YHzi7S+tufhik3pJ3YBHI=
=7xDL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6c11eabe-40a5-46b5-a159-56d8fc1d970d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+LgITPJOe67f95cghOMU0vSQBOpGApGd2Nxgkmr4Z0nmg
SrR89RPn0Pc6g7gX/XRMQRhg/BLD3TwJnxb6IXSoZCx4paB7i6nipv6QSzr2Zb9Y
3CV2NtRcFJhxuKUnoavjMmmTyT8RMVkSGXfG4GeeDG3ERQU12/+NtyzFOf9dU73j
zLTq/mio3xmqoAGows+FjSg3Zve4ARSC8w5pnglzitLbNCHhQAe3tFEe7Dbaa+bN
zIBsNpGlXNnFMAJwkqpJHbMNeL4eyzzvHWWY3ZAYujCVRx8//+yq9+cm1rDL7AmG
mceY2NETl7Xr0l1cThcBO/ZlGFgCYKfoiBtzrrPyfdJBAXM3A7nXB7/n+HoAXFcZ
amV+b/JSW8oOX2JgkQqUwgx7WiUXknnJIdSpwpQHAnFwevOUeNBhYeqD7SmogYvo
IdI=
=xddE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6de66ed7-56e4-4249-a286-2efb475ce666',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAwxoj4QmM2uRDBza2KC+EGNRPxmmMb0Ay5UHLRJo06ICz
hFvwk5NAKaRJnAfMw+5e4e7k84dKT3OPLbYP2LLuKlEb7f6xclLr6gZ6/HUehy3d
btqtql/zDrmxU5gFZXo1HEOhPb++p8wMStWt905iCifMtbKjPmnMwzMrJBtH8jNf
xP3XFBNQQgsQgK3dQBFVgK14v/mKZMg0lXN5uawk/+sE5TMkd0cGPsXbnBVFX+3L
xWPnI+TWtVPh9p8APHkIUaWpAjlO1cevI9rDBenGG/KMSzht+U5fsIQBpv5OYnBk
ZEbQPiNS/x6tSLxoGFDURk6yC4JxX2MXIBKhtdDk/QO6cF6zkVxJUvNVJLyxLHy4
UMaftX1k2IjIBCjMhKo5Xp4L8Mwywd//Rfh2ayNpDCUi53Ythd2gUzBpu3PLM73k
I/WNT4UDVYJHPVO0lBm+WSlh2Ql388+LBKHoZ++AJXhu4EC4qMeytbYOjupy33nk
jI0uyKeWx4NiHViL5CPOyUd/3ATNWsgOtBAvtqAwMWj/nKUTYSAdhp3B5vtZvdmH
DVmp9i1K0Io8Ot+jGtEaJWNstH3EyZBjvvq30hanx0StSiYpvrldkfJAZxpSZ1ii
JzKJrp1KJzYMIXcnBPQxHQXoHFXLJaDSscFQvcE70ordQMrcpqnA+zUgKRamSFfS
QAGgHy7+ZXfjC2kHCaNJss17BXtggPkMhPw228vTYqdriy/Ydp7aHdL1Pi+Qaniz
hfcJlyagg0848jgImgfvkQ0=
=SYKl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6f273d9a-59b2-4e5a-a249-a91dc05ab33d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAmGal/qv07FsNCKqtn/XdZ0OYq7dL9dR3/j8MG055zdeQ
seeg0nGwzpo2nlWhWNjU/pLsxMigjgxxh2inbxblnjyUbMRgEc3ltmhJ2vM9y+K6
l6ziWrptxah5P4V94jC1+hAvwyxyRsDFFRnCIo9TF9VE67cCZH8NQsi32dELYsHw
BVh47QCWVlcYVwKQqDw/rxmGcTKgqV2HhoReUr54np2INAkrQhChDZB81EmnvBFk
Chmb/0CxRhixwQxiMMlZh5tPYorKCGFKxps+LMpH30haYRKbd67powe7+ITvZ6tg
9J6nQTs64beOO7pGG25TccW0KYp5jHEEdTHBKbb6Rdsr0FJbpRU2LnuOcOhjAm8z
gCemvvyOafGH5thxBq4WHwEEchPXJ9ZJG0RL7CcE4oV4N3I7g8O2YUM/j2588o1I
yBJFzAC8eCbMD1lC5APFt9fQA/+MxVQpTrf7t54kektIk075pt2G2mIyZSlqPri8
CS8Lh7D81jTS/8vfmlI+X1tvUTZlND9MMX/SoFjsOkrQ12CfIHSYYN1Qs62XBYxh
Z//gkqoIuWXeVAfvYu8PuijQZ70xv9ol1HBGsKAKgstimmqI9yUy0/CLGU7cCu2R
B9/r+kylhaZvKxfJgsuEooqRmbDOiMZHu6yKVxwaKoUqGLT7fs7D0tx2gZELVefS
QQESWeIDca2JuBd0nMI1OQJNZbOmkDMB4kxObxHkGBgzjMM0CvW+rvv1yLfppX6y
S6uFql3HbEhac+LvSXOKZTqI
=MLUy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '70efec99-431d-42b1-a9e4-acd92c215a16',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//bvs8w6JbtyjRgNfHi8qk9BFEW4rcb0WDXfW4vVDBF2gM
wveft/svJv+dN4ImI2G9Ls3SABvON5C8N0mO0teMjrjXZJP6LHH6T2shP9vA4eKK
xh0LXebuNG1m53ljbuCt2YiHAWIHR6GkTI73Px2A8hAKoYzpAbd4EFg4TvUkrtGV
GOa4mrRIGqeTZPDu2jUBtgOXpIUj0eCAKHb/dzSbhvzQPpsEHfpGiEfbFrORo5u/
yAb/EXDDnCIZYn0nWKXgcLE99mZ3da4xsmP2GZQIZgjkx7xUL3Bgx/L1v8xNPaOa
iA0bW6IG5NOrqlx4lmrkPNjzRVkG0U/+ZocdkBAlDMU9gDrqU2Yz/gzw2gMasrNr
GanGJxLWDvTvsRtUPdU0oMYh2FdsFxv+btx/s7YnQUOvE69AeNseBIdjNvq3vL8J
8stgZ2hz82ve04ofR0gmlNT6COFAL8WPNaeg6YN18pUnZo60mUYt8qOmh3kuqQix
fQ/7OhlUllzEKAYgZ3xpudwvj1MFdDXsspr2mK3B/gQ0dIOQrRAuh3zv+C7RQzG0
cPz7TtqjNa7/fOGSidPIlNYrVfAuh+008iBwMLnzhyiQ+ZBqbiXJ19Pw8Be4WS0M
gH/zqHaFEKc2ugr4Yih605B/ut3H3PEXdSmN5bEYUWZV0Ci94oOZEoHPMwdu/nrS
QQGZKRBRp25YDrliahVQO3ncZTiE2anK1/RZKakjzQYwCqED4sp/0+M2yS2pNxSm
b2QHUg9WCA2ZWFdc0qhh7b5w
=39iw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '72495518-364b-4b4f-ae99-cb965c7b9d46',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HlI0dJmG2RgQnOkgUzN2dtXXmL9ayVbFkN+KuQmnHuww
XZmMlvNCzYmo0PMhqK/vKTs5L11zT4xW0Mkvu8muutzWgQAC9HZoqy+VWNChbCpz
MQ7zq/aH4T5eqP+lu5fOPcbSkSL890qx0Bhw2X5/2ZMECAnzz8pLq6lMdnolbHEJ
OGWThCUPWvWJy+s9W6LRJ1SBy5WzTNitYsyZodCQdpogVy0RCf2oMFf1/HFF4FIO
/6BzX+IdTJhTdE5l19QO3XmmWcF4ugwnOuvfJn7E2TAhfBtJfr67khE69C2oyI5+
tVGkFYuzxxlwiV26bxWuTY9nnEBZHUo1tSbxIB3GH2uGsrCsazmx7+uUuqjo/yAz
j5i4kgy+T6eKjtW9pq686rwQVsJgqwiF43TvUGa0JiyWOTMFZkYJuv1w+ON6S5b8
24162gveFCjIyJNSl2Ym6aF7ZEsSvBGOdBqcafdBsojxTcc8llJKksu89/rYrlQw
bCJDPcCa9DjwuVPLulrgJXsMNZpkxPmbijvV+D+cJS0YxlFx0bwdFevHpWSOPe8F
jGWGqCG9FsBWjRGm+KG37x69W3YNibt3JH96IBFoqvnvnicB0mIXHMns/dvi4qA2
On14V6LJeXj1zX+oeakuQ+q+u6dXqUqCu8chT8SiEGpAUPz9kFy/Bldi/loxle3S
QwEleZM5EoU4dhY8qjwYJHMKayOaYhjVg0JBWSZDtFJQ2hmM85rMYtRJt3PAOY6M
nr8hMp5ALFx5v0gm5TIuRCAgi3A=
=bNEa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '75ac5cf5-8614-411b-ab56-fe88e2b69fac',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAgHn991W6WhNyKgQLoWePrBzD6MXSVhMWdtkc9AhLlRbv
VTR0ZKKgrzadT2j+0uxWVAecWctvtVA+QJO3Z9WdY4kibp69vWW4ekUaUM7R38UD
XLDoYMDfLGGI7b2yRJPD3Gij0/yn0QAgjduBPCpDpUBfaJwdcCKRZcaI88pd4qSZ
Waf0btusq2aToDXE87xD1yMFeCzO2rPs9w5qmfJcoBxXVLDPH+3urgZ5KOvUt9Lf
cBV91Gf7W5mA80aPf0x7GBlJk1KUJZAnsK2IB2V2sImDcV1PqZWTfyGbLvEhtusu
Ny6xzj7uhrp+t+LeMhVqLvd7upTdRgK0cEcSUvrwDCE8E5SzOdaSthw8xMQtNUic
/e1PHmfOpOr0oL+mRIOyuBqaiE5x9/E16XGHs6Rp7pYunAvRMG8pDqI5ees8wMJN
vFJG5y3CQgfOU/79tex8Y8BFEURCaQOuEWry+6GWKsawSUck1uqhoqBG5hK2XT/I
WVnI+3fLCCSjFVRKaxzg759UH8NE2AgvIcqXUualFWp/4ZZCP52ZJxiOCAcXRlNZ
jMSW7/q5QmfeJAR/YJzYK9eUFRVmsLBHExU0Hfl7QE0IRiJemTLL7EGIY6EOODr8
Spcpu8jW3lSK5YjMqoiQlr51xuPwNPJornf9HYTnYJ3cTUT6cKPC3b+tPnHGsQ/S
QQHExoDlIzD1UsDbJx/D/oKBvXWeXZkUYDtiPVucNaIFzzTS4PLlS2UjEbqaNKex
lPOvYFc9w27Bk7nL/5gOlzJE
=z3Lg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7841da53-9223-4fb0-a00b-8867ef0acd40',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9GrIDSvYAvLErTEtdulskdPJxTOame1QkboAuufLHQeX4
y5PUxIbtK3NsoFKAs+YeyHrUtBgHONk9SDsz1nJB/GJZPEbzb+K1GN0ms4PEotiH
vCqBS2tCincCScV+52lCtzF3rlTOL3HRgTFYLRkIomTod+W6tivgTVY5YzORabvS
6BHhBSlRcaZWdUxOU5B5Jk8z8qH1NZzLTL/vx2pkRc1GBAZdFae0rgDqpl2Oeuki
rlPjns70+QhZNnurhuZjAljvXzP78UGDk07RHEVnI/eoW5dm1ZVuUw9ma1shLo5x
cHyM5jrIgQvVOJ1is6rgw2LRl5UvJMjJeDp6mY5mVUd4sqzXIhF4MvzVSBxpBWNV
sidOeiZBE02/vQSdAv3FW0O7ld6kBZ6Aiyxnh+O76/0P4M8Ssk+XuRwOX6I01tfs
3hl2zi0Lq5u6yfGBNQ4+T4JvQje5SFNstBcc2r+M1OVHh2CsjBAWd2fy5S3/agTP
sYhwcTabRJhGs547o2vbOPDmfdYXcXBWRIjOO3OFY1P7esatsCmDKrk++f66RUMD
ZbnNiMIBS92PEVVSHnHH8j+XklKkD4duq3e/F2aq/YsI7IPCMuahJtgiOhNTzHvq
6x9aNFuPmDkGMlrmGXi+Wv/E1zp5a6g3T1iB+Y9l6fJfDgOC6wm4cs8aEJcqto/S
QAHSch/3KR/2LvUiDwf6+7mzGBxS0mHR+vxz0WpKnLyXXNDbcfQM8mQshYxuAmNA
Tuytiiy66Sl8tUwVIKDmC6Y=
=Gr2k
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8ae717fd-cde8-4c6e-a24c-cd6302b9775b',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+JcabwL7sf+deQxVsQZcbUGjj/4Ay8hUKJLpN2T44ToP8
k4I+/ISOkCQQvxo3PDeMhPdRTfRQhijKESPVSDEXGvoKebgmgphJBOJABEIBpUiv
wmH8s+ahTtoVeoZDgFvGvf41JxyKEbAc6OyUCoI3eQ0JIgxSGYWviZxDNb0hXPUY
SaVptQ3MKhCPChCIWfzNghDenEfrhfUK/eN51TNO0fHyl92oEo6HrNu/OsEOiVYS
1pZSd+gD/BnyDviDTG11EcayyRN7yVzXjADJzE/s74DVnu/fiIelDnNTtMCKaOMa
U00PMf32drbnrYsnnXxlp4Z89BsEGdWgRE1ffJtD/JKi9BDW+IHd3NJJkfQ6nDqM
DWzSBsQEwYuhymemL2h/NdUaUTDc3r5pvUnb++v7fbqfnO9w6stRRLCHJpxDg8nv
vBf/wthRNDC/HPGRmfc6CFWmwRhZZJpgftniP0srOur8EpKPOxHwKgGNHikwLCzt
EsOwflF34KgmaDPniT7+i47KfCAg6ZVKtpo1x5esanQ14vakeU2JOMIiTUSbhtea
DGMi9txrdlJrj4yJ/LcaL8uhoCOJIarsgDmyIEs8n94vxiCzS3dP668sHzEpBXwA
NN4p4iT6t2jRCtmJTjQ+agSgGDo+on3TZspOAh11TpeaLE0BUBtm9mFNhzQM9mHS
PQHskOlVk3QbOF7zERa0nHd0CBGJXxfMsm+r2oD36twbv59dTRIszGr0n/NtC7ei
mVYUb1xkYPMsBYbHCb0=
=j/wH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b63632c-1d2e-4318-a5f9-27543cfa0de2',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9G6C0Ctedn/uQWqPjZsdatnIhNg2B0Ikac8zIsFlKJBpF
mCcO75VvW7RIoAEroxu+4CZYUT7sz/8k5tPe1OE2PnC1527qbKVBJlRc1mIVuLuE
KaQq+kxCf/0I8oPQsPlqZ70PpZo2vY/lb7emJV+VGBK1i09Fo3lXDsSRCc76UZIr
Bj1gRL9RUNPQP7Et/g+CaRUa4jA6CjgvLY2XwshzLJ/vhxT6kzcEWY2ogAP+h6Jh
d5aX1rViJV1tTfr8QNCcaiC/+r0lJKJ7MybsY/bwbGWOlg4l8oTfpYdWZ2SJzKF1
SIGNEGGVvRoQmXwYQUlcdvzDL/X6c3/s78utQuElrMM9TjErgC2VdCbM7WjZsTnd
sF8FGdmsLKSmnE0J/Aj+2KPuO/sIcxzu8sLDXnDG70uRwWFUQqvcoyOLEqGDnTtM
Ua8t//I/9lOdRyj3jkdGiqyEsp0jgprJAEkCXw/uTNvUML3h+4sQQBYhGpJO0OcF
ZFBkazLfIHTvBYRDRi4u1UUx/RKic6rv1wmzXwXP/0OQJIm8/DbIQHpERyg7zSr5
0IpPUn2P6leWlnGhgnH426IUZCW3g8L7bW/WLdg2eJSDv++11pJT65eHu0kElDxT
OfQQfnlW2YWQqtSrxblS5fP69Ej+FttNNpx6GUx+cvOllkjuS3869EHWWHFa9w/S
QQEF975qr9r3I2tTydSgbTlWKFSmQ39Tk8SpNC93GOGhpweUSZhgwxn4GAAFdodP
oY5dm3PqBpxfkCriKXn/nqsJ
=bK9V
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8de4fced-7036-4147-a19d-8dc73d84e672',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//T9FuABW5bJpDjDNXKhGZMa3cdyHptjeM76fyb9XFivIU
x54LoBaPRnQvOj5g2h6WrK3yBEzJPI4lgmnrL2YntcIR4vsiIrPAX959zPlcRiQ4
TrAJSWFRRurgK2jQ0BCrK6RePHL+VAxsQYLD6kjek54p6xnB8R3dxKnjjkxjHGJV
mBGV5G1PFDF003B+oYkFlcGDyXlUsWndgaRSYbHr8jpoDEVFkssVL8AUkAxCqSPE
J3a1XSe2O/egE65KH955iJq/h+BhTq6EGkXIVt5/pijjROX+033FddouIeviSnuY
UO76a1l8OoQPRsL6e+LmcbN2yElW/dD8vE1umJqOPigp8VZ7J1ETZzCVEqxqup2A
8dIYHmUAUhez4ncp7DP42h1JHKC1l2md7liEEX6Jqfa/aJDFyRFFinIWdlo01kqm
P9U4A5YDzN3wavCOOJvHK48mHAeHXVbMkc+szYOi2PDL3r19esu60Ug8cipIqXWK
iMIj0zokE5XzbS8S3y791xS20sWBoORL5hmZrLcJ43jzBjZ3uNQwxRxgEMZw3fp2
jmINDw5kpAm17JakMYNncZvo1VNhwb2HUJyVGZ8+6410mqlfy8VLCa4wzZi8kb61
8jm9fkjtOasg82cQGkhazoQAouDYyKY9+iRvSfJaXJPzVPiPNJNRCpWStREpMfDS
QgHSTBDWcLsMKELxwqhtd336SuQdyNf4TZ7jLzYg9alRvEK50ZM2Pe9WIs9acpm8
Ki6YT7su0YJr/4xNzFsSNFe9qQ==
=JhWP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8e6aa97a-e2d5-4775-a210-73a4f0688cbc',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+LApEFobSsVeNHHNp5BPIX2o1r5zsM/lTQhroM4woS2Sx
7GVzXraJ8rDn1zuRcmL1cDFkGxM+QEFr3NpYg02UE4KU2nr3tb1GKncnb7lzXeqe
5ih/x9eTconOIJWt6K1pp4cHjr8grWvVWMjyQkt0RCJpD0TWxbhXgDXjpki4vdHK
dB1Gl4NTlFNjMWctAS5cQ1KPnvdQoYRccV8nmjUdFmcE2OzKU3IKyWnJgckSgIPg
52XFwvkazmZM0o0T169ChCU108m8NUVZ6VB62Ve1FCi0xVjBFSWLXoNy9Kv3sWzs
/rT/sj9rNY6kwSfw2/N3C7V7CPXXUQv1MKuKCE41/B71DH98psWHQijddN2QERz5
gf+1xwH3FpZQukf16xUKufaOb3O8bQzy7PMrxa5sTNWXj9nWeKKr+UNKCt3+82He
xL6jUUhBUS+wx5+oH3UicBLUnWx02tRVlQ2OntRFXRFpFSOwJx3lHntsyOEN5Ckt
vAvSC2MaSGcrStPvxYRogt7D3GQc9Xcnv3H2d61Z+kN1dK149GilkHn/dn8sL45L
af13sukvKUsAMYoJaXXDJSpfeDmH40YPtnP8kQmxcSoroyEf2fGlYgI6eKJupzPn
xUq2WjDAjkFbREvYJ3FD84+lQJgskZQ3TqNqu/3IZwv+3LCgRher4qJyKcYNhJ7S
QgEVtjfVToiHx/HQZZ3/7IUOdeGyj0MZrmXISrYnUBHen+kDu2rekxQ2lYaOQPVY
IocL1hAbQenluQ7KhrfVlzz89A==
=C7dq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '909bcd1b-9b5a-454b-ac52-bfa1824c0c5d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//RGIEpWlwvCDHZCpd77UKd0on/5eTyP6FpqH0WR4Ot78M
pADrs9/tkOrWw5cDBwOZVyNWFftdzw084wJ0zJ8AAFgaYP3ErVATbptlYk7Ttd3k
fNBlIIWTiYSmAZ5JbIjCqbOdgY2pHmu1rGz7j0CiDnTwNeTCPLSEvivYHx8b9UEK
7fTV8DHiUOUlKWeJYkVNUIimOmn1ndfzsm0DfGs4abzmGQ5UoJu3B85D5j8/d7It
uz8m3TPX+6SdgBInUFHl2HbdVWmOmYq0Ii5GhkNIzdgqjAMO4gHZb/BV8Yp1YHPO
w+9hY6BkfjmzCfRGkfj4agDTTHmQrkrQaXmpuHaUqCI7CXc4464O4G8cStUp7NM1
p1Od/bL7SJbCdtSzMoGlTLknL5ylATg3WykIFz63sEKuCy6SHn+3N2AgroKkC91/
yiQEw6ZWUUhw+BWXd0C1Y9SUc6IVJKnUsRmen8c7iwjkFbXTB7nKmRHn1gbBZ3Zs
eaIZxA6OBb1UwHUfqd630juNFBDEOq+tbu0zGCKh7kup/nHFD7zIte+1JSXXr4kn
VyHuepaTmZm8jWqLKAami50dOcsN5Db0V7dx4uj489y4ICS9IF7GIWl9wVvmGdQp
E1wAP3YsEfArioaQFbYqfZKpy1x5C1U2BU87gP+ABCJZc1x8pKqgeZ8ADiUh9kbS
QwHIIU2vBzTaL5TkcRXb35mn6uYLcGsd7/RXVhsBwy3SsvCrKdqTmepCza7ufmFN
UfmxZal2wqc0qOpKspx4AfczdqY=
=0gzw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '922be07a-5894-4f1f-a8a0-d30fa0e5bbf1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkQib+3Lp85AyHHDDKXcEPYZ/d5+3oe0MRbyW4uXsz5+H
kBxA25phh/yo6kOCT/9WCP3hWQlnp+56g12ZUR565sKVgwy3cxa/J78MSA7EgYaC
jztXNi2vh9QUrJ/9vatUZ43T6Sk6gnGO7uoxbzmso9Qy0qrRtq8tQGq+DIUN7omv
gdeznPn+YthkesoRT2xrWrhywBam09OB+AV1Qtk4NSH46BzwLE4T3KUWUtPL4Olg
WY/FJj8ag+Tk8Z6mBXK2ATUMNcsxiJUAAM2B35QxvqkWSbJzeGxoPLAHCH1thuqv
zGJ9osGFoaV4h+uCh4LazmIcwl/4n5wFTDL4fM/TMYuJ/j2Epfsikhd81uDqFErC
I8ag148MGEzaBG5W7YpAhZWHn8ZNPb5mix3qNDMeXl380SOTcFNLd+5PdWjDyw/s
KCKq3LiI0iCxQ4Lnf2toMXnO6YormWFTQmUCJ8WIzYH0JWyqU3PKb02g4GAdZ53M
ALVLdDMq2/W+qp4kDHegr299crmnVIiFEYJkC1bbPAaKlgLkQln+bOrtjF/H0T9m
BszXbl4d5iRToLxAPTqioxA/Sf7qlwqa0gGT/QulbHsa9YMgjr+dTWxQut++Beoq
rxRY6TC1NxS5Sf/oE02zuIbtKW2l0yPwyVUjDQHRaW7uEEO416AmYTGy1/Qa05LS
QgHA+WcyFIcb/iHP2EBLBtWkIwE9Y1ywspVD6M4KBi1IxADhuRIOwTgGIJdwOo60
gFrmenMdOe07hFBDSP/BvNwdjQ==
=IWBc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '928d1cb8-a954-4d0a-a5d5-9814e9fe87be',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//XAlYuQDIxVk0No7FZ2VwMrnQFEv1LfA+wF9HhFRX2uZg
T+urzE8MIFQIZUXvhcrznVC4nkPTXSn45gv5x1/IJ5J/b8Q/hdxhDXQKNfC+zzb3
0saRRFaPLJj/YB75eXAeRLOW0j2a4t7skNsQg37IIEBn8s73hrR6fD8ZHRwmE5dH
3sbmUa7tXGrPZzQ34rgUP/eIT81dUe8Swa+0BJuuQlqRNAvvinkD/vOb7/LB9sxX
L3Xth2cS4mN3N5Y+e/sUqtt+u36l+BnpbQxkT/sprOen55e38agT0GLbC1iUePM5
/FoSgwdGDz64IBtaP21wAhPyK4qgE/ZZx1QWRQh5wkHd92AF5QdclyDkd7q6sjCb
k+GzHq5NCvb008O3vo5MiBVNKaV17DAta/pCbqrA88K/pNOmbXnvxqQtStmn6Pn8
WKZJW4xLhOci6k1lUxibx1eKfGhglv2F53NQUWbw52IEqt3wZQOI1bjObKP0z7nG
pB4MV8rMGkEEpANux+LmlxO2bplk9ILgFVEzKoYR+i1LBUjPVpHBynUnml9YBkIc
xl7MuIYYzAfYIGDPWblRjTp/u8ChwX4BwxsdlQG6A4hZxMyBmNB/qPls1JA1rq9D
LeCcwugaFE6RYvCwaf8p4GlpiTFdmOSi8HE0uQuj7SdnhXAVleHD5QkXnpPPkNfS
QQGIPkdF9sSh7fJZaocNYeckIP22TFtso/QVkM84Er/moJ4tfONU3ZI73QOnT2dH
kG3jtrPok3iFtHyzAzJwk3eV
=+K9F
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '999c14d0-5b15-49af-aedb-786660284366',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//b80yzmSUqncS7HVCvN5Gufx0X505YVo+kBU2pLfi29kb
QXtZ2rozUzpkl5cgBZ3jfoDEfHePdiLCVU9vy6tU6tRZHpX+RBNOatjpUUuzl1ki
JW+36m9yCAukpy8KCe5WclknTQ8EXCzFVY3cxPY0EVvlxEWQRATi2Cl92Ir9QAQc
SA0gIUCXYtFpLIOJIKul2b2RFJOgQAK6Zlvi8Bd9y/X6vydr2m4Nu7FBMsPWzfS0
IthG+hAasz3w6S1MZCxFBCDZjNEldJCw7A0kBj1cVYKUHpxdrrjwT5mpSMEcKdib
QnKPQKpkVeT/pSMv1Ix2WpF+nvHNX1FiTlxdpYydL+L4kg1voFV0gG0O5tvWYdpg
TZdVd6kEmcNWSSa6CPc/heiNvyZC40UAOmCJrtVzPvtlskliv+NFdehEY4HTfs7s
e526ufw+rh534/1Fp7zebTh5rOWi38oXmmcyXW4undYcXtU2JRLdOo1O895octKs
Aep/Eo5HAroeckHviHedlm8W99k9g3bdIMHfxjpEB9eDNgIUiauz1Ww+lyZcYuJs
M/l+r08dIElyycn8v1oilQLddrYiezPuRMF4o22a93+aoEjMq2/VvnGp0o4EThlD
BhHli/5AA3MRG7mTjg11TV9+k837cAOFsQs+ancvLkvhzB1pn56pIKD+kEX+YD/S
QwEb9rMXehQ2FL8iKvweU8FVjLpVeYcx3+Uzb8ScNe/nQt2MlsnRc9kTyM8p/27K
XnbMNX5x/L8SZ7f787EwDDP7ctY=
=MG4L
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ac74a1cf-cf4a-43de-a961-fd44caa8415e',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/dYFGPOoAvOKk0nnMAiUwThMbypSH5byAkcZFCOWN7c1F
FdCYkS6e+FQWo3rONojSwQX3W+WYNO+KQZLuwUuDuOEZSSqOrlWv2vqQMGd7z7QC
+eYIIy9SVBAs4Y0xO7xh6HZtUOfrMPh6qrN3nyUDzSxwYyH2cto7rdrgwlbSmZ//
+3hDPAIKQzn9g9B4PV/chBlgmsnEsKEwnndybDRP9IXx6GdZ/NpgPzugR6XxZZGK
z1+OYrO8IGqSI4WZ2JZ2axgF/GpxvtsQsUEyatZmBS8zffJc99kaO8RjOFQkAcpz
Ez5XOYbQcov8RHAZN2nk3gHvohk4afVhEukV1uSZ+tJBARYdovyv2cIvVu4ujwww
ugn1G3uYcAFjIMIEI7A2umxhKmmPXOe6HFOEz6A2+mP+/0GE4wPi1TliE2bN58zD
cD8=
=t8dw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b44cbdaa-06a2-48f1-afc1-636a7885e594',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//aTqZ4qMLpOQDmLI8xhIjOKXiqONUKH242hKsp4TGNCFA
dYSlWL4itNmhE9d8Gdped/DbRWQtWpe46cp5isv779yidZtnt73o4NwZgBUB7//7
b6SQqZqU+TQWfKAEJq4yaEzoLLpy7wV6OMDlBNTIggig4ZG+As1Gbz03nT2j6lie
Z28SnKgLhLZ7r8uqpv8ih8NHX+y2/rZOVRofsrcVXDrE1sJWpxIoh6DAekxilyZx
zmmXfXlqdtiKOGH8gh4d8+g66/V6BbzbtSiU7pwKqEMlqh+qCey0gdsqHJagxs3h
jcCvbSaQMLvUVy2ayBIgG0irzvuEENFs+J++FN+WmQUgGC0G+O6Q/36X7Qx41k4W
oVamt+vsp1OEAP0OLqxSBYo0XHp9DrcOfgjKAqY8zwZpVNLWpMd19Nj7BehyOZsZ
o6Lzkr1fF3EV+cv4aTPWXEl/vpWWuavgTYlZwZwm9aViZMBNfZ1831+kZhvnUcxg
GvSOc3m/edQDDGecBInhvvEFJGMSAzD0oeSPZZ2M6IwnozfevAPimlLVGtNNajxm
LhFVb5//X6tKFqyNxxwZ04W6kqrJsm9YHb0i4XBnc2FcH3kY4r0qhEplj/kyYUWn
isPsDsvYorp8vn3xIa+pn6mZCmOxnjSUtd8Yl1GFOQkSpaB4aCGJyf6l5Pv0yzrS
QQH0VMlH4d3LlO01Bk45exOx+mml6IbfngFzi8vXw1zFLO+jAEeomkgnH48LtsDg
LjCVxV56Uizle+47Ts9TrUSD
=E3Xu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b56a6eea-39f0-4da4-a7d7-425acdc20e64',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAq0llLTA540dUnCsyYfHVqlxOi4NVZr31gkyBeJFbBUrJ
6+F8JpJZmv3EHMNDLpJVNMGRnCfLQ6wydpcYD0kD7TPzwvA9Bq3J6LYn+oni690o
3GiRx1Fkxn02LIfO8/pFTYtyXl09x8Qrksaw9yVbdEMcUvrJNss2TxDUFXGAM/hb
h8uaOpLJ+KT95S86m8Nu7r/uVx+9rDtg2c1g4eXgnkUGaFKvpUAlK+Rq+3i6LUEa
PGxhW9y+y17Tgs1GXsv8YcHT5/Kv1OWAFaXlHLiulmVpoFQzZZPIy3fS8VIIhU9m
1VGIa+OlpOAHytk6ISIEAk6FYeqij1HIaaX2Iw1zy0IWY1kixkvPHejmzcI4hGXh
4fkIZoThxySX1Gw3u1bQlfZWCys2u8dwUJaDr2lFkT4jov7/iqPS8hN92NelXY4I
ZATeTwjXwgIff8zXlQC3Px+pCpcy2aYIcOKsGEUI8+T0z9BCmi8PD85YO25zkO+0
YdhYPeDrp8KTrTksEHcVepmSdzTgsBeVviX7p/ho1KyjEYUKBnX5geg/doZIBbx8
xnwXZB4eM+rnU5b5sFTPGuIA9yNZnBOAhj4uFZ8R+++O4EymY+oz5kMdtVBgREIT
CQMZstV+FOPgkvnPklKkZjYd8UQyFYjGWuBFJI7Ji3QdP50otCnC/DPSV4EPkMvS
PQGbaaVlJCzJcVww8E3ViYYKBTZa1rqj83YjHaeIa6jwfEwfY9bbggMO2O9r3xp8
5R1mt4+1qiHNkRvQKf8=
=Twlk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b6e49c1f-cc50-45d9-a5c0-194e678bae5d',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//br39GLODQgpNjPcPOX9BlWiSqHLu58dvyVh4dkr6FEXA
GQZVaLYVncyOdyV419UlaRVJHQJpSbsGSqiGvnQiJojlwA71zZ7LsVKb5ICd+wSH
q7DOoX6ZSMLOjcXJQV/FAc2SCgZl+9avV8IQ6/KS9ZOiSXZmLN1+vss9sT2ToeiZ
neE/ZWaUJOf83XtthxnjExpIYcJcjevZ7xvb+tEyZhR4QxQkDD86aNOtlxhQNA/+
NE4Q5xgWJ/sdVjk6rgqW3pKNhs1QlPcmfKEjvFOda60N7nmBabrq7CHhYhaFGOXn
WS9VjOgAA18iB1r4Z9Xzx/xfmhajTLRVOzA3BtbccKOJNm++xT9d4IOp/LnpKRR7
rfIs2E1FZYNZqTMRHkBx1LEfCbMqxqQIpbgNfAumuxwBhp/RA/9apUQGcmoQVeqy
YyeRElLlnJy2dT6MM0QPOdIXpyExeYeOcRpdXvC/1cSgJzpnUCOEoXWBjQivu3bU
G7MMg3QEEybpdOQrpqrUzcLjM3JR0VI4Jq4nmjnQIcFij8vwGFevnHjP1QnYxMrQ
5nDdN9wOqKaxES/iDR8MpZBBTjFsX7oUpm7sSOq97jc4OSjLVP40ap9F7VBE4SXL
Af703SF9fBK7SRE6b1uhjfvVWskK6THOICn8jnLHF+pLY75pTPxeeVzJsIEIwaHS
QQE8fIn8KkJS4Pb5o429p/h+VyAkrHAsg0AS6dONf931CVnukafdzRdllj4L5SVT
kdecPA65xNHfyEyhDGKiQxVY
=cRfA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'baab2414-dcc7-41f3-a7e3-ea8ca3547cb2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9FqfWJuI0X8KJNGbbv3eS3tAHijUolBzC6FhZdCblfQj5
5b1wFTU+46l58oUTMRQVAR2Ezw4BEEZIHfP/wBeGLtwMwEq73PmsPwS0Of4IpAe3
jtKVRll4zPzqCiplrBxIkOcQU7wwxA71j1hNUV9EPyM5TMlZXSs6El7+kv67/KXW
3k9sf3IFSojTyqcDVmuHH7CPNJxvbgyySDd8x2+iBDH0UVRb21ntDAma2ww9lB0G
eULBVJ+bxZmpDFW4KtmBEVr5rRRAs/Q473MDQy3LX43g1meqcKsHEWYo3y9M+NDJ
LTxy9hWViJHmeSzfYW0Bb+LgESXaZgNOrTizkdGh6caz4VWUSZNeSCGhBMEHav2u
j/fh6p0TzxnBiwRoU9vOck1eiJ2I3HZzronEVAbDlKnpxw7XqC49G7k03M6YNooQ
0V4JMr02goFkFd7SXDTqsdcRvwkCFbKmju9pgbKhKtQGesMK3fwpJ2BmIOPMLK6S
J9S6gWkanai4PitNoZG6ZiURrVIVvvekTCe5FIUiu+sgmNB6JehJnsfzmGH4pvkk
+3+B8mYTfYKoX64CntZv7Q61Qattc0PZ8n9+NmuntGIL9ATNjiSdvDTfZdcym6lf
xQ44DDvMEAadxIsZj5f8Tyb6ea0F5mqmmcrc75PNEVsAWZMfTrgnwZGz6Cl+Nq3S
PQH1OCh8bzHMwW9ZmTUeyk1RbCnJbasXdaa710BYt2619LwShxsaeG/hMxeou23L
KhiN94tjJ3VMYRLmPOw=
=vFht
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bba89c9a-1cd3-4506-a050-7375a86a4db8',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+JkP6KofmmqJ8A+qVXNbVtTVo/WCXyiJVcVlGjOsxufDb
8GilBbQVQXteSBPWBT+Nlk10rfDAOo3iaNHuWnGHXAWUtcCOVIQBsG39DougD+T0
UwedBLK0hrdiZiYxvf3pCS3RiutieUPFNOpfTc6DhXJpiZXiG7WUZaZWrDZpBhHt
ZOdqpVzwlta+0e1o1gMpOz2NSADuxpBBdUwzbGpQqzk0w8yALkOhFV7jyu5JNzE+
Pb/WCytEVkJpQiBzRJlTyVq+oHu0auhPKdDyil+ea/wOql6dGKuOEnn3gVA6upCu
/O+qJJ7yG1za7tbtXOLvx1nKCscXWA8R+wVP00ps8/qYkdmo80YoUNPUICKULnjZ
D4PhIefUZkNT+ca2E1VP4Jyd/hdRoHeOfV75UfyYAP/m+bUmCF/Y0wLHW5qmQLL+
/gConIO/+dvWej7ngYcIKxnKsV7AjTUK2ud2UrBE5wpcW8mkEIBgKGFqHc5VuADS
VUcTfLo8+CdAkLWhIwGnjSRMT7rxDHOYzntfz/Rec/SkeuBXuy9UDrZoARmOXWDg
nFqN0GKFZGTwQ2Bn/Hn3c1XMxjBABMiGNoJio2FB0LJ6C6vabdwJ7T1tzboaxHeU
eeQgaAn0c1MwvhMYLLWSx59qwqbPfbpADeWF28goWf36E7kxjxZk+99csSkhVBzS
QQFUuMRbdTAR0PBReQ6i1WgNLQ1sPp4/u16OOz2g/XMMll/TINqjCadMe57ZKTzy
dDQMDqQ92g71Bf3frNMczu7s
=6lnl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bd966841-ca07-4f94-a8bb-6f2c43aa7e0b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Whl0WYaLX7UUVIDOzYYYnHMnfvNVx0ichjyT9IqWiO0m
Mc4ZddhL15NkIw7TZEvAkK1huuV0GjWu+TQyxyFs/gyN8FeurM65jhFWm41mSyxA
U5FJUecUo6gNPCR1NfCpE01mfMd0AT5oZiQ9QYmZ4Dsuirn2Z0imCs8Ttg1vtYcX
lgUXS5aiCpbtn0tREtYi+Ad+eyfk0h94IJAZbvJtcdWlP/vK1zJwKSvkZK5Lht1v
w2s90fzq+RrWa0Asmsbtk5dwcen0QIMxb+qVcyffzy1/6PejhWiwYzjuW1iveBBs
W8+4dufuqstR7uEAypVgo3asTijVglkgGzxPuEXKjKpFygS4DlIUAgeAYYc10fLT
00BH1xhNy3TSQzZEwTK/uQnEuih+apMe1uYPV+Ntre4B75wAUgj6ESJm8ej90aNV
P0yrhd9BB93MnTyJap4fBGMsLX0CVfBUXx4jl8rH1DVIMmCPqCkF9k3VFdUrfUkW
qND0d/agqI5l142QaVrYgtYu2ugOkvZWDcM9RIeJZztfvgt2XJ56ptG3x6UtsDMb
QfwlE80Wqv2jbWWtJs+kDU75QZcAedpPoE/dZzfvPo57SBqPDb29lCLnYqD4R15v
yOH5+/y8fx/ZDlrtrMrNq69jpdQSgOn2MBGCOez4QbquvM/7Kz520BeYxNwP8NLS
QwENKVxvy2FiGP6uZNtBjtAz9jcDlw1iqES0+SXwgH0MaqdvP4ezsWYLVzMICenF
y6nfFoOawxaJ03ejzlDa50Oh15U=
=VVcp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c23ae26f-8017-4c0a-a8a0-0c4380cead1a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA7fL+qSAa4+wAChOi5czWCWdER5edW4tJS9JeKmCVRdbn
p7i4G+qP9mKUFBvOUHnBtmLa27a7+AtUgF2r/JiRkgPrjPu80Pg7LFl0OwM12k3f
MUbxa3KF50AWQZ7onFj5Z123zeDSWP7kRzJQIIkL7DY1UeMiNbM+a0weMaSi3qO/
oYiuOqh6vP59LiAyIvuBFcVT6Dm0Wt8U0fcNJU8CVZ6LTLwy78yFB09AISlOp78i
fHyaga4rgnLzRDnuj/GjDTcptqhKf7iW4bKbXK8aXFOkI1ghMTYQuQBJRV76KyNC
IoCZfQiGoS1eHa+U4ifj5Sv17OMOEJ49kumnGBPo9r5U+5yFdYL7UwOncEWCRLyy
zj8EUaewVTOj6w9d92YEyHgKzETgvYIfabHoKpSqcvr6vQ1uGX5eiI04z+h9S45V
Pi5FWCUnmkx+kIlAjZQOOvWX9YgicMkeVvLWzqCSvPa4PwpJ3/5epNrTY17FOc38
jYC93ZUeAUtYhvoL0STZqCAiZfoYN/zPOQPPXkYR8HhLyTD38NxnFw8BUzl9c+Oo
IrEZq4AjkIGCLnL77SfLO18Biz7geOxklwNDJq+FziK/+5otfkzf5JSjIZo9YTfm
FBA6A04OEdSTPSrKHk+qarScamle/pmIujb8n0gpSzz6giiCY1LEbBLA9QPxhW3S
QQEEBzzwNkyGelOdaSYka8CLo8VZDNYuZAxegRU5gObGVOMt9Mt5xVKA8rRGhgV0
zJ2eDqqLX+ZBv5IEhIj/m+kR
=5Eev
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd94c2bc5-b71a-4af8-afa0-7613178797f7',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//emE0az5CtheuJwQTcE0u9kE9u1iXM7MqoqqDu8nH2lvZ
S0zoCFUavOZjKSVmD/1Jau4/FCKw8Xzv8vhAJadGCxcKwo+NEueEfi36umhc9AQ6
DmnrqXiX5/iS7L8bgC0trtmNbg1tORwfZhLT/nrjjrib1kPsnIOl7ejMrHk9Z8fC
UzIlk0jhfPXcWhY/MfTIU3REncWRIYCtR9+3+vjAOsADy1O0BHVRIQFLnYDr3UdK
ItcrH+4el024yvvTc4IR3FQtaQYM8wzlnPy1siFzzDBjmVt9m0lVy44MeZGz722k
oZv+XcuVvnz9yOn+kDjYPeKtbHulMTh6Lv4sK2nUBTyQIHFoDxN+QrVZxu7ip5Cr
scOqgHuEO6oRTbi/kd7yeE0AC/eeJpjFQq44gv6i6/M/VTjLAZVCkybUSbtcbm9Z
1Pp0EmKTjkjJETdm/JmK0GjiptiTcDeWV1sA2SkOYfJd+hG6ltmRhwi2CBSG9tHP
pXyonVdXP5DQ4y9Pihtm2z7lWb843G2bEKUXQiybFvOzjhkwfVEyPL0lZ/Yf6g8J
5UMr3OH4zjONOgRXJme/jNJgeKeHcJbeeoUlzvZBWA2ZqgUvswLX4m92Uo7vOlRy
wAyDyvtrduA2BYYxf/qhiQXQuKtxTBCFjmXg+fyg8r+PbBwSNrvamLuBIax8zrbS
QQELhTA8wOmhn/9HPRscL5E9ybcZemWRCaDsnGxirPIubzK/Fcc0WiAWfhWPgGZJ
N5xIHEHxzuEZoqJFdHd9fRis
=61hR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dd76f6b7-f3af-4163-a36c-8a8e6ce9d50e',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAlu+yI7v43yr3pHKVwrM6W7FzQL0HoYWR5bYByW2GNYks
bUMjbv8k2AfGthJJmld27rYUFqa4dJYqZtGABZkbT+pCixzBedie0nnbuXBPtEKc
eJ7rjR+05LCm0ODsu3ecDzsQSnxP4otZf3z/bzVRrvs8eVbsAZFVCOpgv9d5CXpW
csDca8s7Gh1dhtiu9ixhYYZpSZqcFqO130brs7VeW5Jypte6OdCQvSrW7Ss87pA5
K8VmFNvxsBdQCuX2ZTYVb2QchLkVkWM3FnZgDncUs5PB5TJh2Wg4+2zTawqSMUNG
QDHDGpEiBw2HCpnzKYpyOlkb0aveDjycP1C1D+PQoyeFH01ww8i8XvhdrmwZGK4I
CGUxNaOJVF+Ngowpd0lZZbbtp15VWo9WkdAGn/GAuf0h3wRyIJ1KQlsY5glYN/gn
jNwT2+QLUhqLqi87MuRgysHVdOZ9X50AbnPzJ915HryH0YoCf6qsK+JlFlVoEBVE
a+flDb1xHS9lOdiG4L0b3/pS2LFwDZHe4PnXZqyJYa86g873c76kQZoLcxw/rk0Y
DD9mE+BGGaI5fuX0qZomSnPlN/lkHnNA/PROJ1L1YfbpIeXbC+yFtYBjF81kUJ3b
Mvleple4fHUjDrOtXlC8oL1autPeIhdoLalQQGBwfsRJ2gccGqqIs784xzfoJijS
QAHSu0rJYFbiUaKYY+GL/hF2YIY+/JiZtejifdryREhE0XItRQID7aOYZQFwBGuj
OIDBrcJDpzqBLNRLkVNRwH4=
=l5PG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e732d71d-b9be-4be1-ad3f-fd60218b7103',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAthEghhoxXu4Ox9sLLK2MMLOy6++6OHnwOYQzdYp2NG4x
afmhAwePnveTpHssAEYt7ZaltxIpUpLBdEEQZFtQbdaZPBHOsA8R6eGGkpLSj7Y9
nW6u4wXeX9TB482zL3pOjGHStzpo6Tgc5TJBSOifYnxlAsm5/LnBvaJaxOLsNcIl
tMk1k08cLCNt24jZ9wcyVHAywkoSZG5OpEtbkc+ZFsc8LL20MBN7FdCS6MjH74bh
gEmW1O7h+p+BgBaIBxXQibeQxnvriaYrSwO4Cd8s1u3y1SUFozkJS/pyoPp7iesE
5Gu07/AwjPXbZlS6yW6zVtW1LgY/vkIXCs4OIvDtMtAEFmbCD/Hn/1lsfhe79/WF
nQ47X+B+6yuwQK8booGtMFOqqV18LTfk0p6gEIcui8ab9ludKE1Rh6NAAaJ+mVaw
f8ianz5RAz9+JkAwupkbsHaEifeZa7HgThxc3ti4olKmjq2knq8agt54+j1oehFB
Es4y10Q6gXfzSpYPnOgJ7IYm4XGnK9HRnp9itbnSaDVkISL3fYlatzow1ybkMZ+4
fcK1gevTwn6j4WGtMmgIeU5QKBVc9z6U48kvazl6xlbNZRiHfC9bGeR8Amf6UR9E
Rlsks25RjH+/fRDxNXAr7blkQdM8Sutlx9Lx6MN69s0rbNSYgI95yEPLGxn/j/vS
QQELg5b6bF31WsjYw2bVXiz/UCYKSUIVSWxQ6s7rsT5FlAf9FPIuRo3LEMiIOwCP
SXxugF/QUZoH8Wibg8meZ3xm
=0XTr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e9098a59-0a3d-427c-a866-d4487ae699a1',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/6AllcFxDdMR9Lu5FIGE7pk48guT03TYGyNgS4UtI34jpL
g/qL3IN0M4oWSFfxJnOfScw5X1Jl7bt27v7Gxxje2qpqDCdJQb/z+ujvCiRXOUmD
5wNW0nE3F00wIymUf+snmTobT9AQ4zWGNyhCGahOUw7YM+4LAB7yq9mMG6v1qLID
8zNdDH+TuifsE03jsZwYqH2E04y9eeLKBfvV04ADZzfaknFe7fWqOJNHIodkCu7G
QRIkfzYEqgxfk1zV7Q+ecOXVmhF69TaGE5mLkJASE4/3xBQYfUy9423pEKmTPoPc
SWFlcV6ka2Yu+rWX2rCph/XmuPrMr6FqgmzqctuGCd09V5iprKz2wxFskGzV4UGH
40sEOVSaSK072i4p5x7BRFjpLYs7z/Bpws2Oha1chvV+fkP8kGj110ux/oEEBPNJ
NsTLiN91lgraczwP52Wmjcak58LKmwP89/YJ5XU0w+iuSv6VzMiDyaGq/nxJ4XLd
ry1mGnDgiewyGlyZ0N6vDjLlKetRPZlQxRODF0tE7H+KfJh+HWturqDXjXjuujIS
JUmfZMzLFOO9EGKkzsLC+vEVvKHBc4cQyMUl77A99autg2Dv8BUhD+/mTJ1rGhY0
Nrv3Yqe5G5mzGrPVnyLvfuhQF+ObtzGpuMpEIXJzwSxJBbtA8ycY85o47Xszy1fS
QgF9tow6kKPgv45jxDyshv/Srd+08L9JDvRN78GNn1BSoAjTnF/UeVXxw09TMNCH
2bHM+MjDbHe9sx7q02IrpxUe5w==
=waou
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f30d08f6-2b1c-4807-a612-c1b118fd726e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAhoXPgw6/xVfdPozgF1lm+dQYIlPh1z/fC6l2pc6tFrSG
TxK2+D4RIueC0LURNjxCRtyGAdxu60WmyAT8ZiGid7pu2v8RDHoAap6TN6T4cBiv
6uSWUyjQuQxPjlQ2M7D/JXzcLNFFxjL0lurr7aXF2znLqSIUfgSHSLkGU2yYZWKg
aGMVOGpJNfWtn7uf++7tNCwK9muta6pYuxjuRanDlAzZlRK8zqirkaM5A6v9vdfU
//X5jxLc8R1X5MWLVbLK4H4q0pE8Fr+KxvKNXh0iDPuRwCRiycr1kI1lvhvM1utu
oUiCEBUV11TOlUqPVrBJxwwjMn3EB8gqJ1Bbmw/s9JtjQEe9bDesy3bkLMLuYpaF
nmKRrMmhEoE0T/V3xVo0hM6jknLBW9sf6hwEFrpw74Bx+piZ0BLtaf7+j6c69dcI
S7WJSqqt4jwV7k2l04fUucgBjvO8OGZX6dF/lrxF/KJApsqqaoAlNXrRgHZ0zeui
pUl9xeDT0wp42YoeuvXiiIGEr+PpaBh7i9vkV4o1thOPIZsRcQVG/OCSRhw+6nL0
nBfcVSmr+V+2ZyykHcLLEvs9acDM8sqdGjN0/iBXmu8VUJY8ym73kT3BDB5C+Soz
VzzN/FXuZhFPrX77NvYJ54Pr86x/6SdQrHYd6J9cHnOkNGVfFporvmaFfD+0bfzS
QwEvfzL5L4Y9uKTkP3qwUbDIkYkqFoGB5r2M7Cr+1qnKGXcU2rRX5s47NEitDCLZ
U13+1sLxxuikdaG8R2Ov7F6muIk=
=wCzn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f3ec3e6b-e37f-4c1a-afd2-4b37b7069e85',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAqUmfLGlWWYcVyyIksHJN462iHXI0hEJh5WAGCw7HWrZ1
sQOftWe6HfkSwPou7SAIUqEfYCcge6IAXjJqWhErr/sxFhiRgPlUJY9lyNEzeJOx
9YvuIXiIw++adtJVUW/Dkx/N6S3JN6e3+IFAi/tQqQeL7FjQDme2cU8gwhKPN5cB
+DQtIQFeTSSqxDkbw9qcpxbcLMj/9D7VnWtBSz66hvMX8VKgAqwwztrolYewI7ZH
WPu9NyGBdOR5X1EfjH6CqnNeRr67CAK71Hs1fvocIt7h+q1Gk5MtiUdBBJ1EBLtv
DnLAF4hkUL89CgDI/u4GGR4Nr/AAOqzIMiJL11gff1Tc3NjK54zLhqLsTiCD5brr
k+34hGXkWUO/a1TBlEfsNiymfExbq2cAE2kypW+SrKm5Wys5zTrJhJekRXT9mXNp
JowAui7E3KYIlk3Q1ddgB+2nCe5eU4Ly2lBOpXfMq8G++o4rRI1HZGglCAot0d0w
/Zc3jp/UCLQI4MeDiZar/sGDby1Jpdb7Fmw3451cQy4abS2btWdfTSqK+lM4Er9M
GYXnL8dc0Lz/ktyl39aO9JOWEPykISPy4gOXVPwAKoJdAlqc7Ymol7ZVSHCOxniD
pDHnWdeOjv5OvyjogLlGe9LR+CdG1idOtbwnaotKKaf90ArmlJSMvFFSZy8pby7S
QwGxCjcacibFBMk5euWdlsE/sBfIxhA0n7CAWez4dVFweocpagtr+jbr1Yykqz1m
meoZarz4TVfdztDGTvXH043gjSU=
=uB4T
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f76f8dfe-8835-4a35-a5fc-030761093377',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsgmXGdZOyG0AnfqPDIfG2Nd1LfjSCiMt1VgtRLDrc7aC
3OYthFgbR2eXOUrujuG24o5PL8Vxma4yrMdTO+400ns6jWkkBOsHLP1IQ4MSt1J4
2XNUpsK9rdtSgNc+xC8fFKqiAwUnPOE6ALamtDuplvge5N5Jc9cA3JvXjWz4apz5
k+JyMXzOgunHS2zJd9+5RqUSc2ILusBEWD+SOuYhlX/D7d6joavPClrf2Y7NkM0U
IRhWqFsrNTzqwp+TdOVDVgt8dfeipjhxgudo8h40VirElwKM0HQ25UYxxbS+vii3
StlNywytCZtmnoFGnAdcGERViL32CYjMPDgvY1krI9Mw5S4n2C+6SRmj1jkr8CCA
k8w9h8Ye4AKE9v0yoZNaH6SWM7/pl6WF3xoeG1uKKtXl8DEGtABB3/NmxRt/xzqt
//xfzwXcviGhkrJa8Fr23cYC5r2yZL+bHgkyGagf9jYc4TnaVkoUukIBavDAwv/2
RVhGukxp4REJ1C6AGKDBeqrDyWBuXzpUdbUyxbfhuCbXOelJoyU9kSIOQ5SyVEOt
MZlKmMCUB/+VwSqO3LXbYn+zL07OpR5V9aXSD9kW7qy00udf7+08/fGR3Ubu4VB5
Og+yQSIE/AYJVwRsEzPBWqHFlT3ZW8fgQ15eICw+05ihI3nRDHNN1odsF/f9ogLS
QwGjTuQvxBGqIM2jHC8RSOzMeXYCsSHviNRzoz5nuVg8gKXYQrXaUtkYbQYKWDVH
MfomX2Vj+oTDD6b1sCYqfQAs8x0=
=FT3/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f8c5d098-a245-432a-ab0b-fedd9421971c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAl50xEOFyt2OxBIMBN92AqyW7N40rNFF+sWfyZQMMHQ7k
y/o5krbIHmSrL5w1J9lZthPwOz/5KRfmcxeUZczlPSpT7tQZ3x2CqoIEtX8Z0W6T
3imgT3eZa1n4Dciap2FiRAXUY5Jx9DMneN5yDL6+vj5otuS9ysySr0KeUtoWSiau
bGg2481jGZOEhyYRCDtMJYj05MqE1CWRVkr0Aq6nEyJuFxjFHM6eAPItl+KWDRap
Xwi6zJsUevYAlNhGRBdr8InpHT0Ugq7azh1BBVTD3yN4DI8RtuomYleEYdpwOtQf
5CYVXFiO3pTPda6g0bpYGccCCecBGL5XLM3piKG842dKxu5Rxsb9aSaw/hNdz9V/
WjRfvHqJ4nj5X7woh32pQHl0ikUL7F0+DpZi76joCE55sNewPCv+s1Ae0/M4ZXkw
pLKwhFw5Bimnn5R2/2V7WV4DZtUfB4gXIiXbdwC1YYj6uv7o+CZ431Mw6MzWTyu0
BUfgcR0tdgmJR/q5qaI0Jns64NFoUT8+QzPdvoNOKOUvfCXT33dqDGhBKASSrMF4
0WR0PiXhLa3eLyy6Y+16eMh9DMVCbPgRMPXofZnIqAHMlhbQpy6G6ZehQUaqZfvO
IMvbqtDeWBWfQFw4xOIejFm6xEgJDdLqa3u9Zmsud5VKTd0Q1BaUPtP+hAdqYEfS
QQG/OWYMuHAHSdz+SvxsBCyEbgMT90ErmX/Zb+ZuYmo4iSuCDnK/PuSGrkBRwgXM
aiNY+R5n4U1d1U6aU2f/Vwx3
=Nwa2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'feafdcca-0d9b-4c12-ab47-856151354414',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9GLE6/BN8eNPfWH4WakQpFvuPHBSVbdWc/mMg4HYFJaB1
bJFvim1vqGN7MFJKi6z2/oH/5/NseturtqF+oUwnRx4kspjNfEXQAwhXbTOdTYN0
mbkcGuhsS8ZCeQ5CuLocEBR5/clLYEu6HjRY3VFZ/CvcYzoOtOuKkfyid6B33SSK
E9Su++JJPunLMdklyweW6jp5Y8rBhhS0C3LS6SR7YQGAuCbkRgI90aohtzetM/2z
amdkC9x1OPWnBsmg9HZ56siKqM7ufQ4WthQthasNIUet116RCrTSPnxBzMTFMikl
5qb8zxfyDLoab/kiU/bjUzlUvNet7sGPdnUSRbrQxfi14PHpBmPf/GHkUkqAXEzU
QWabzIW9kefB+GvauGqG9yowo0cqQ49pL7IbB/9leYSvcXkCwhxka1MzPbQPj9fD
30PUf1Uf79iinMSVp++7hQtXQ6zHCBMNylyWpOY9M0qv3cNSbCir0GrJMAlWujAn
fnw8vlUimrcXjwrA0DG96dxVvFXw/iJDVr6GGhgQKaN+7QJSzVYaeToLQc0KlgeN
+nMLItA6UuEsAsUvRiIRX2tapRdT+iLXFyHoVBhc3ZXhfpOoFFfH9RsFQWOe/JDZ
42VJBlSHfN3bqcRLRKedeb2hA9XubaCDSWLUrPcYH5S033cWasomrZEDrlHbMujS
QQECk9SRfMm3norjfyvYeIABZSZMLgdnSpvpFca9nbaW1p71hDP4c0kd4+KdVzyx
tV2kBubXF6fr2JbQ5aw1VAgX
=Y2pC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ff9087d0-633f-4d96-a2a0-17c4ae2047c0',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//R1mNpE5xT10/aV+Vo3mFJjCib8Lieo3xgAhJ95aiaC60
iwEB4HTV4wwCbQhmLtrR6Gb/TpOVS7a+mPgEPTGOqy6m+arwu6kYqYhL5xoSYi3I
eHvGjUA4ovchJvVvFX+LCcfMQnkLF4s7ClGDYxransvmhCTSHgUQ519s4KeaP6eI
u90PFhY5EYPt5LhSPFPRgGucWArjsWDalQsbI1XlC+26RXJl6Qh9XVOGre3LcAlx
/Wg/3LaapycbXbNyYDjtXnxUogA3s1Cbb3AMQlwit/Q2GApvjy40Q3M0/kpsXdEd
ZFInIvEaTuUljgWt08x4nIM22RaKw97lb0wcl41H9s93arweTThg8424SN2kgOIM
AOjGeS9dGTOE7SG5Mgg/sWlnvaC1/RJjRAzcdNkEd5KlxHWnCqMdMd/zi/vse2bz
VrdJT7jswtrltmQB5u4x6Y1gYa5VH/fZwZUwCjsv1H6hAsRVuxwTFoGOh8MuVppL
tB9sdro0fBzYWPrDr9CZqqN9UFbPglBLFGgj+uRXaKbMjUBAVyC8AcyT4M9aGJtB
d4YWGEh9LyZqAtYDOMdpW+gtyvN8RnNhXegbK9TvwBroSOabZ+wHP5AS8F+rT8tQ
sLKi8EcuYc4ScH/xQrBf1QRkyGwQLi3s36Klzuso4f1pXlx1FBip7bns2QhcjxPS
QQHI04vCQbGU7Bixu1/PdPQBW4W3PPDcNsFGmKVaVMFsyWC2EB62F2D/HXH4qm83
ysYvPO8HkHuKSwCE8sEk7zeG
=hsHe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
