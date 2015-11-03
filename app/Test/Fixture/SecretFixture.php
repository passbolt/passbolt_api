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
			'id' => '013653c6-f63f-4e03-a829-9dd71c891d82',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ/9HMt/q/d/Iqnk3IDmkPetw5H2A30asCeb/X1MXx/h25Tg
H/JaWCwbPr8lG4/rhFCS2dU14XKc+yEtge7jbeJX3elure2Tah4SWhEtK58RQbTv
uNjYRMRFdVEmCZFlk3i8KdASzYwGOWS6hIkkLbJIw6LBZija93VB6utCYrT4+DtM
gGpzYYkPRnMXB7QwZAXQEDNzjxJ9hyLMarx9rsq1l7SRbJWxXv9XkHELDyirqpuU
VV57QhU1aaqr/mIIPdBq2JTXLmnVGsTFKK5cNhjWxoGd1bmkokN9GlIXKds2Wb8W
p7a7+mKo2cyxmBmEj1IKqX1SzW7GgzTdLrJalH6dGxeFfPbH7s+amSvuMwDfK6Ms
4egBkXvFITJ19geKf0INRncSpp+9lE8rEMsFtUwfVGoA+x685R5H4STKpiusbZfH
gYrvnNSbeNXilVhVE/Ftkl+LTVNKRehyRUJwVoSOcaGqoFb0+y7FxC1Ug7sDJ5Ut
TC5JNHHgO0HEMPv4s37vCmOvd6qNc8OwMaYv5Uhvy6oEVy/JxyJaticDiyGWQKW4
FK7F2UxiBUkbU616QYi3cAqowMIRlUj+yXqrgrRXvzO/HQbP8B78Z79QOJHw0RcT
jYFDEZ2E32ImDgtlFKzSiefT/22Ewct8UeDfyezo4JBSuPz2huyxIJbe9/s93X3S
RwFX0l19np6HP/KlR3TlhW+3Zbkl5BLgnNmE/rfUxPjXhP6Ix7U7F1p4tz9hHVof
VDSErKUwV/L4faZSqO8XMjUN/zqtOxgx
=dhSr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '043460a5-e366-459a-a22d-a8063874df34',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ//cDqyiXqxS1Us15w7w4c57bWh4wN+QDX6THBsd4E2IhU/
j0GrCS3P/qG19/W4clcjbdEGi7vENAmSZuezezPRGcpcssRXcKNVloOo/H7stxMx
F66ILIR6CaxME8PIQytYuSVp/BuKBHH3bacvJaSYzNq9C8FbUPN/ZIb20H4hXGAD
C99PFaFehrJN6qedoCV/yeg/dahaztCoy72VgfaGgWvI/3YKVOOnhHhIBAHiXgbG
GQt3WNesc1ySFHijShggdpBbZwKHDfwbuhhdnx8EaRQss4Wz1nSfKtdJR9b79PvM
lw0sWcXya8Zl6qNsqTUTOrjqqSJjt85WVnHpdm6bGD+ff4B0hr3H15E2nGv5ig7l
RQpP5VlC8ysn4BfDfxHWq77lmymLuBWw9tiNJlMcwrR+coosAyQIQckpM+aWltKs
H2Ni6s0BeV6jmRMXcFbl7LttNgwKlcG+pXw+VtNGkdr6U8focGocpPp37Y903OSR
72RBiXzuohQqR6uKhvm10dKDTuvH8zbwnvfkts+4NClbUnjMXB/gp89DYTXQwK6C
mhy+9wTQLTQqbGpxmVPwMJSm/9GPg1EttM03/YLihqcCiEncEhYp3EufkSvZ97EG
kju2Zq6TajgmpE4Sjfvsp7/zbEr3pOJAaVRa8ENpCVWvIKdAkFnMoF+GvMz0eSHS
RwEVOdOxaQDpKd/OzKXGZzxo47471jKE1EmGYB1y7ZsVpC4voh+mc7saWS5qCu5V
VWOSCKUffo/VUTX99Cgqj8A4Jr/lVNzx
=TImH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '06654b92-dd3e-4b8e-a94e-f10a007d4cd2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ//c41xf/IWkI5utjoPQv7ANROrV9wQvKwP9Ph+ug9hDeYe
wlit6JiJqCoPd8xc9fvyaH57KK3aKKYjtgYNhuBM8FKo+YAU+EBn/N6P81OxczM/
eFO6ZIBe9V7TOr33VTquZhm5Fg9lK04DVkYPJ5CjaFFM2tfFuCc9s2+ew2Fr1MrJ
RpJ+nnWMej6L0lgE8ZeN1ldbQqZhNMjlPJUDpWe+4PnIh7WoqY7VDqNehiGN/Xmu
bwnIHQlu5o2VdN3Z/8zoEbntxAn8+VWm/LrD25AZNIzupUuDzCQW0OG0HV+Bo1RY
/wxR+/PAwgLPJBvBr/eSFrjke3SaxEEF68kcrr96c3q1qr1tt1Ud2JVMPFlumnfN
qUyrwCZQ3qiUxkk/QDiJ5Ozg5c1fFu6mQuAleVswEYHeGNLzjNnDBqfiN56tO7j1
SwmsRvzz5IQodRVeL4ePmSUadSbDXSPk6S04nvE+fqwwuwOU4fL4f8Cu+zdutCcY
y+izJ1PKSx0Az/HxyowlPURO9fLLLliP7kKntv/wNT/5wHeN04+RFV/Ymt5/rqPD
eW7pc6FWSTSY4bklAm+iClaBHRC+TrAzgXw2uOgQ1pau0t3A4QKh+0KK2Kt3YQ1o
LHUXR7XXfvAdLMWsxyzjnxS7pql5RHHs/ObhOMtrkcENfdVKOGmoPoHz6w3Z0GPS
RgFquAbR7gy/ELmLDOVTXOjP649FfPbycTpAdCoQo+/btZzrPLbchQoZV8XFNNIR
Z29Wab7M+GFupmBBUfzdKo4hiM82FZg=
=boE7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0d6e5b24-c5a3-4f90-a87e-7ba04afaa0eb',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//eDHlbIn7rUzJ+uP1JMz8WKVXD995ZZpiVjqa3SLMIMcu
ZnEg/3aS2qAfuEIzTab2Jr1IQsuOnUmGRqDGq8yEtcgeRKdImKQpTxqE/IYjUAeO
OSSmJ2ytr4TWRO15abCXhHECuzdo7mBkXty5L7bcUky3Yxoj8am1JlTIhAmimK80
ebkNxnoSwBrmgjoaneCPW+BTqOhqB9udXpDmLBYyQETIeoiophiuJVIMAbAAFd3o
4dDaB3yoU0X60wdgnSaa9rEACEh/3UUQUYCQqUIX/2Ve5oIo0AR3PKMs62+zmdry
FyqZZyoi0v9SXET7JiLZM14POcLnbm8iCu8i7w38qftyim12ywZ6FBlN3JvJMOrj
vT8Ja236c2keo0KEY5s6eKU0Etz0qLHM4XV54gTSmpF/+7BAzpr3GLZultV3jDCi
Rswpy2gn0BLMduiyPJxIWXwL4xC1cxEfI5vPrJA99NHQw4pFnNuN6VLUC7sLQE3V
WOfti3Fw3Gqwm07CTYc+S+7USdc/dJWFYF7difBJwM3Gt/n01pvZNkq6n4wv40Uv
eEp3SLdh6eJ/mN3kksatrpTPAZt4mmGx8bDl8VJTpDpC01EPVy2vjxGnzGvHf6/W
ja1i/Rt75QiEYQiFoYR/cNy8J2vE5VlNZcidJscKQlZXuCKOmTnPNk/UxFRxNZTS
RQEDZm7925mxsRSAEBy9Ypzpd+WnuZ8omeW3wi2AkhVGCNbuiErD7s342byNggoG
l0d7bHrqC6gVgIQqTfFxJfgZoZMPcQ==
=LHHn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0f3053a9-1b46-4825-ace5-6bef6d770900',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//QKl10o1gmVxMUbQwH28eIIkxIxmr14Mp5xnJp/Dh4SBe
kKVF+fwGn8udYmLfc37LoDAXnOQckJO0KZBAjdc7mjihjBjIzOhuBb0VjMXms+/5
s9pSpDv8yqOohQElfY9iQUywzhQW1eFAhqwIMTJSEKZ8uQ6AiuGAORo+fsLpIJ8D
YFZ2F/ugTN2QpcCTnOtAy8QdZNXJH8bEjNYCgzKiNTc6xvhuKDc+4J7Wxu9MPYA3
fKZIoEBw8ckFqiAed1zmGqyZm3Id7QtEU9yvfPihoNdqFYB1+kXrxcYj0YlljLYv
WwUwtoIpZi+qKuqI9ngOL181fna9ePc7QtBtehJwTjhY/k+LcaaJpXAppqnuG7w4
shYDlU/hznAURpQvsih4EVprTDeYjA1jhXVLLGQGob2YtEkSRzFKIfquQyp87V0a
fFXo+Uny9oUyhfMWOmzzylt9D9VdFphbQUWtDcLXtlX+4SGw0v2d4gLVNfgBYS/d
iX8pu7F3P+2yDloliWcfADmrvBqQQ0cKcW3eYQuMIXFb5b83hpu1rhoHAGgdVYvZ
Rks4g+TWVff+U9x0KiQSVFiGKPgV5fJnJgvK9cWFZGKDq/2lI/+t0szrxLmRQvA6
oGp+kbDXrQDs6Me8PS8kAv6ZgNirq4nBzEd7MQS+XDTQL2AVzhIoMe4Z2IV6inDS
RQFUOuuHvaewgqvkdFU2RQUNMcfYZq4cbqfkL8u20DlcLgWpVhZpSXzayT/Oe78y
6ikZoJZbdW8oGpiEnDs+CUWxXTZ6Bw==
=Xx29
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1066c44a-ad28-4769-a193-b86e3abd6e9a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/8DbWI+TQGtyKN0B8SIkGkW0Y2IcDZNPv8ZE7pZuyeLeZ8
fays+AioQOssAOX9Sa3TLaqdusG+dwJOjwN9Oh+jq6IEanCW8SF5vp6SRDWTf3uZ
vLe9IlXTSaTAGOxzTJovqA/DExJj+G8yht4J6orKsF6+yeGCt9c3G09CbRvXseiz
0rpK/zIv5zhXSxToRDLshvpwpQr2sNYV5rmR60u+n5XN/Ew1O2A4UpaW+4hMEr/b
HInRizp8qk76/klIS8Z0UwYQ3f0GByJwPHmTRUYES0apzA1blg2zxfXVN6davVtd
nyWpyz/siC7g7VLfUAWSEgM1xinYQR8cB2ignPTqobuF6uyGuvNnGLWDr/4UJ4Ts
AiKc50JvQ7baLFcZWG1f2QzVLo/tou7Ev3qp/oYYDX6FQEDC2dY5JcLmstwR4NlA
WyS3wg8w+TSa0Jse1GmKNugh3IahFqfV5eL0yEAx5+RafFApqTVafyC5EmL3hAAb
9tlj/JXegAPyGllqcox8TaWZUj2baojWiNndunHORaI5J4LB/auhSBXClcjy5+13
q0kLnUzNqCxxpAnxK+/c+hNvbKRsw/+VgHt7FuJeXWmdpbVeqAHjNeEQ0Stg6ncK
LDFv7ZrXLe42tngAvKgUU78XTKpAUnMO0L+/hoy1Pq12RrdNjXF6ve56KSyHJRHS
RwEF55FMNRulhyFXHL6x7f0aK3NgPyygPOYn+v/vN7uz24vYtk7TRZIvu96unQoo
ntFwTljC8X7JkNda6HKUlhuRcgsc4QrR
=pzvU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1b497137-ac0b-4c15-af17-459679aa65c2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigARAA00MXyFqG0kZr+9KPOzLJo3C4eJTHv+EywbBwac4NKDZ8
ZQNBK0O9r0tclAwXccNHA7+aI93BXWOtQi7JtzkZiSUpmgRTFeU1jZdvW6mmMMxs
ep/H9zrVfw7UfQ9CDxhrVxEZAcp2XUQNYp1o05JZguHJJLlXGrtxXmWgtins7QMp
lesZvattbpVtYBv/irCmzfb0MGmB/MhHPT/h2sKAwiDtHVGjmbFEvjnXx6SC3CUZ
G/IfHgtwbzRzJ7fm2o2s5cbPpJHcJVAa6PM9ZImLzsukxS1NYHT/afEEFgbYrCFO
4hrnzQ3KO1xgIk6TJuuQzEPaFSddEZzoLM1GCkNsNr3lzIRhkAJqRQf8y7c/zR4V
2nRxtwIUQektvliljA+DpqeEcUbFWMWXERPZWuO4OLCZLmTtisy8khEw7PTB2m5Q
dFtjn0KiAtY24sU5L+IqseHysjVOiwAoelYswcgM1MERcr8dncWJn8Ja0nxhnT1y
kVx7oBUsszt0rRhMGo6f34WIDaxV4I4YSs4M8LPPU3FtTzmalj8fZB04wCJFTFl7
Np2ieNqHlKzi7A6M9JkUXekjRXByWCjf4B9bZGixrHY9wbE0w6FLGnZMoZFoef1B
15Qgvb+B5WF6dEZpU0Gff8rLExr2poI3iECjq9cBI6HW+B3Fa8BW+uDUVjrbzNrS
RwFv+/GFV47CHeQDeM+cwNk7rrZzrNuxOAUVwupPudCjZJybLUDPQITusEtmeHzM
C1byb09ZDBm3x2aumalSbYNwxlfAS7MY
=nEGp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1c63782e-3d45-467f-a347-cde8149fe1eb',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAxkA6B9Z4y2kAQ//QlHo0xcT65NM0J4t1WMBuZ5UCG+uiWCDnSM9iNi2WTwE
32RRS95huYB5Z2jAeezZmRIw81196IKLcpHhytXIBJa08hxXPdk+4fc8HeY/a2P/
96eHJNrArRuzz6y6Tw4dEZlYecGbPOei2tAEt/7vf1Vl1KPwgapzMW/MQKfCvq2f
3q/XYB4mQ+QYC+MWbjd/2ld+WxKLgs7coP1M8mB7laoAg9l410Nm+4YS6y6uTghy
QDu4mFiqlxHMm+/XzlPHeaamsb+EXzkZy1kQXoyJtKYyh0XlwLXGN88es1uTcF9Q
1Z5HDmyoJ4R+NemZKlk6vv+Stvdq7T/YLJjjawaWqhfH8XgYXeUfT1LXkotU/Z3U
LuPco3hMZoShzf/nltJlCL9jD8iFlQUKBwSQKWO6qc69NUlHufjFPS2Swq2r8Aoc
KbPOLltj6hbCzkyGfr0VxbKuWiRY40ZwRZa/iGIEACeyRFUf5FZ7SCAe1AqJiJ5z
FYS8GUnFTS10ZVcUrR7MgxXPqvg+tzk+Yi/NkixoDtPZO8BC6PEcDFlp3earx9Vu
9XVhsCvkKzzPdXUMVj0PkwjuFK5acEn/ZpxVe1dC3JYzmVfzklZcihHzaXaHsojc
06Dzw26a962ZhteFzx3QYVTxT46/QpKr66tWZRTM92Nz+FuPAAGrVEEDU6O2TNHS
RwFNfGvU7YThs+g2Bd28k20MaWu1EUNj7oYMFfubf4bwfss9XqHUk7x/PvDAdC/T
xyiye3DRn5AmG1pD+FDZKL6k3ncsOI4v
=7gFA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1dc250b9-2123-4a73-a56b-1aacdc1a6941',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4e/DeCIHsAzARAAqm/7e73P8jKHdUm4Egal6HBfIBjENCCOeKZtQgwSc2Sm
scT8VK8zVjLnUf0BbfGdSnOeehs3Ao/XuDMRGvYV90gIPuqOzurWAvaxSqqhvqCB
7e7xUdIT0Y3aKIrgrIuMnMPfO1vuGfML5YRNpUYRBErnZeY4gdWS/AAvvHmbO9tk
5SuUMNKp0PdzUOaikKMtlf71LUOqWAQc+2AxmLsNunR4gY8fiyFeirokQN521/kb
OZx171LxDwmVIJf1JDqQfIhwEp3abqWx2frVfNe1wshvIDO2GMTkBLUFgpdospZJ
VBlEWvj8jM4nr4VTp8LTCwmJhY17BrmFiSN4vAU7h7cHvQzJOxwPDKF8qg0Ae6+J
wFQhxQg6IHPfiA1QGJbiTF6IV0h7L2x2c6GWdR0Ca4xvzS4DZGN16QZoCMAQ9A5Y
doxoFkihojOY7FXf3Nz/QchQdPyLcMAXCkVmTlZyCyFQiawcEBnLwol0b7aJwUkF
6TI0qTVastKqJoTz8lBmZsmQh77KxFmk3tb1lyVh8lBKvqQ4InsIOZMMj6VYIJrc
XT2GuFqC05MGAbrxae7WsE8Jbtw2MABSOkPyuwYzRBoQFGIXbIyldSQXqkt55yOF
LEk1hnInHzBqq3P+Fdv1T1k+eqJiC0R5OJjJYdZw7MhZ9AmQsxqbRqFUrOd7Zb7S
RAEw4w5wx6pmMzx75DnVlIAs4LWwJ1BHGm37/kvnKk5oljmje/EVrfxM0h0o/4ur
1ZXBZ67l5voboj4XQ8e3NZZA0RbX
=4MA0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '24d772d0-3312-4360-ac84-8c3092401f7d',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA9nJydJ7HCYGARAAoCy3wM3eOxvzXIwi8XTKft9s4k8G+KU4okV6a0x0WLlO
wdMvTKsgK5Fiq5cAAgDNwAIYQ03u1T5O9BO5ClsVs8XMpwK4kt1wl9fgDxsyGWz7
Obo7fUdutLRdhJ5mBthMDhagbo2g4n81OcMrAQ4eRH3ZnM3mNCgFEDiiEmQSUP1A
+S3FhXZFsoShOXhBVOffwFnVNul/orpB8UWGhGP9D2+//c45Mkq8muxli+UenBvV
ZjS/OeqcQnhxLoan1vHa5xfE/7sfLy/dLv88IgT+CGKloccSN0UDAzbgFuCMN5Ts
qqSEfo0q474C5n3QpJrwa848So9vTzbTFjAOhHK/NhiNYEcuP57aAla3AJiA8im5
NWeJdXAGJxrfTehYeLUM9aG4xUcvlGGPGgV1SSoXIAJ6r1JWApQfBll7DWypY+RQ
FlmLTs05j2E5DvhsIYd1y/FAbLcenfKI/ZmQs7/FAG4qgnkjj6VM3iH+pgxdL/U7
/9CytAqYOVMVBLI14Z2xpLtCspxl3xgZ9TcnN6x8+7J4rwQuE+RF4bYym212h9G6
QlRxvI3yZ+yI/iIvti3Y3nrAMXE+IPH9XQujNmwV7RhBLeNDX8u3QgH6xb1MANOD
jQ2pmrXE6QuNsEZSlfLjfjHdatPaplNi7pmaceuoHmSFAeyGHuJ1wisY5V+QcLjS
QgHzF7JA7RL0lxU2g2l+z6I2QHf0ggjBxXmX8K9U1Z5mT7AyFmL0Bh4Cah6Wnm58
CioceIC2obIxiVWQeWqtV2yyvA==
=yGqC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '25ce989b-51fa-4ba2-a01a-0ce644536499',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ/+KTYqXEjXhzRgBEY0AQwaQNT4y0r3gVEPGso9g0Gsi9I3
fqPt/c+n7ahCXMQRO6ko/YjCA2CtPM/qmzXb6kZr1rWbEv6mP06fyETTo+4BGO9m
t7jJDHZ45OYkTk6tN/Audm4jLS197y6e1seUkCoA6e0CWq3Ld5k5ZIoIypMMewur
k0ZS4LBArouv4LrUJ1v6zpHMYadDYmhM7M1nBFE4ByStU9li/bkekRbtwX9qq4M+
UYmvrXfqmwTGOu7n/0a/t1u2+uocRIngXyiL+56KlmlBkMr/9fpw2RIH9LLTGZ+o
7UUVHzr/Gu7TMo5WZ0I6IONaQU6TrkPrvBafkNpePZfL69sB/WmF9WVnIoFPE5JQ
9lldnh6yyXtvAEygYPhORxIgkJjLI4pAh45sH0kHiVUSpsUqFIkAPMAh0lPd+9f7
mpMf+FTiukYE85y+cvJp0aTB5EBUk2PhyL28ML6w3QTtkKHf7dYKwhBRZU/EzSaD
zGPM2P5P1E+f2Vxb2NFm4sUdCn/ZVRagT14RNgAX5RkT8PMhhg1q7g2rXgYUAL8K
IKpYU2FeiqSDME0hSbTO4C1QELzau3hl8cvrWKY5HdEWzxTDLpMkJ+NlZDlQMu3/
EtMfDWJfnZ1R3J42I27vm3VqDZ1+13OaoA3h2MnpJNUz4NpE8jD8gsyJ90OTRBHS
RQHft9Bb4DWcjdUI6uo9O30fY1hxFxZpuwRr2SF+ZLNrRa9/EdNB90fcA+Vn5Toy
J2wfnWEe5RH9u1s1LysQcwOkNAHXwg==
=b15N
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2936357a-5318-4827-a815-9ae902d884e2',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+ARAAtQl37/ACLSZHNHSaBrGhBKiLGYoqiaaSojprCv/a4i7m
kXgQj7b4QldCTcTuYH4qNBK554wSn0f3o/vvYGdar6O6BrJ1SOhG3m+d0p201eaP
Axx32JB81TzmL/vBBkZ0sXTcHcwLDILeV8z+clj5FiqvE5T1XiviXzTzRNzmHLDB
6ZQjLC1nSGtHDrXunVdWkoYnD2Rty3YJApCPhri0O9MQ8g1AhSMxKIEZTbGsbnz9
08gcbGNyS3uWHM7HeJHKQtZwrUfFMMSYvWbb8MamOxRc87eTHBNaRnWn7K8L9dfi
KEfE2P9CSed+KUo3nWpcg28k0J4Ms62pKEvmgKJwYYyd99XXHzq4A2pduckkGXBF
n0cXz5pKA3kEcOrhu5suf08ZTxBq01pcGlfZg1kABEuwLXoJel97/x239n7uxNrA
EGb0lQqsqHCUvR7a1AxDItCLLOFGMRR0jnpwQVY6MovAE4+ywqw1pj5hJ6dyAKbl
kjM1GIhA793KJbFmDLqzCbOGQzt5dFIwGQL9gM5v6faYZ91d8CK6gTS4yvyvDKHs
qIZPRGqKmVfhhmbfa8sQAF9Mb2URfulZPAXsLPtSTUZXvs+78W+i43oZm4VyCLj7
07xvJEjB+dI1ZHauhJG2+BwieyEeZ1ITbrfU0u1qYx+erQWWK+oI9aImgG2tzJnS
RAE7C9yWUXZDsnoT3J2pPEn6H9jn1Owigrb9p9cnEtSwiA6giv/VWCQgh6Ghmzm0
D5Tv598wAr4yRCsn6z5pw5XDwUBG
=XYXQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2b54b875-9fdf-43b8-a454-3391f0e771c9',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgARAAnVXQEghrrLTfjUvEDs6omzVFTSFUN9kVGpdVro3L8Pt3
tMG+vP9zo4dW4AhpJXqxVZmnp45GkRZMhIc+FgXVWEmrefjTcy7rxBFExCkWi05U
MIGf2Ftz27yddHtxcfTR92XDm0eCsuPOTOe/bw2UIA3iREIywDxuJ5QllC53uRDf
kENe7rlcJSQ/sw7B4sXPG8GuefBy7C2tTn9liGfoTGp05omOmIBMPQkWD8x8WWL7
9MLE4OqJzAeMxc/HqfycyAw/OcGzvMKuU0Mq1NSssVyFarX+S918uNDEO3qc5xky
WpL7kUk7IXIiFLJertJ1YlP6zfYmMNu08gj7hUjbcjAGlW+9n0WWOviuvvzrlkcA
LClYWn9kgRoArmP4sZJfqoumzeC9QLlHI30umXu19TtcLsX2Z0j4HAkM4cEDAsrN
ypJxf7XwaGALxAMEoaFYeP1t1FfpsOdue/v+DOzuMmtf4z+Nubrf13CBVr44pXQj
miNZkcrkIuesGgPrRt6iEEbQ6oDF0eoCYknIQqGjjbGwsLHetPYZ6H+AHFz26vLs
r+VSSlNo48/7JY5B0aAp2qKfuWFLmGlFZ75JOHtJVfh2XyuxpjbG0IqAibDqzXfo
nrM6pRmVXl12A8wTbR+ayifmCBsc1u7rSgWZ6J6LfSJfXO6E8REo2Fdgf/+aF9PS
RwF1MMzwVvnfucCADYkNGgyAodi+0yOp+cFAMS6pZLmef9a7/UD8Vdh2fBQevCRW
fVwgs5OkIVozIdiglSDpsG6KUWIXesBP
=+Oca
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '314a40fb-8949-4825-a8f4-0decd5da0031',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA+p38wQEIh7oAQ/+JKO1uu1+n4EI3/dogX9ge7+LIdCK6hFxec7UO0nWe87+
zo9/lBlmR1A9OAmgRq3lnry+UHcf8vsGIc5b4e6AqTApRQXZ4bYy2HaDWS619ZHQ
c2vAtiG88srhDlU8nbEYjCEP/pIv0W4qncNriZlzCE4DLn33I6CNXgebmAp2LwOa
zajXd+9aaY1y9I/bnBSUMqI0ahlmVkHTT541rPgFif7+aFrf7u0L8WoGjss15f7e
EFi22Ldn+wuDkKuHwrMOKVrKKRrjxxGlhpI6BLR02G6G5RlVVdJzGyGOSYqTCKJd
dP/4zOSNcoVmA/QRbFho/F4v5r1eSH1phZvXgLzCHoPVt1Pv046MH89y9BTbpclk
GD2SfvxXhezFxLJBoelPBPX7/0vVsKSU5BPUwFFvP0Gx48U4ZsQ1glXz4BBzdaD1
nfPRaW37H3SY2M+SXd5jVMnVQQwK9fRUpKtgdvJaY7uxgt3LK80lVdG1lShHuPn0
Lmk0pWEqfoCT7RUZk25lBNBsRX3gelwZKJS3jUmsWXsnP9gIGg/BSCjNvOHlHOVL
d6SgVfNqltTfbdHz6XXaf3GjskItEnaGutHXrCmYmPkTmP914VbX31VZeo79Fwkq
3igQzzFeFpExF0bcJNqm9EzdclqAXAp0+DBV42Y+UYZpxdNgjt2rtOEEyFXGhpvS
RwF3Escdzu0ZKI1x6as5qiSzOZqAxC8Qj4VN56kTErGC+4FlAfgIQwZOsl0Adj7t
fGnXc/xGpziqz/LAGiGh/ysxlgsN1Aef
=bOXj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '35991025-1e6f-4bcb-a6b7-c4c7a6c51007',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigARAAnf3u0otBS2P79dBpGo4Fi8R+54OIkEnL8vM+XIRIW71Q
/eUXmnZu+5mXhckihO5EZ2q7Nqh+esxZI2iOSB8kL9bb1WhFQDyECmEeFmTotYxV
B0+qfoJt1V2+D1KiaqDIw/ycGSijRqjIFHronD6UmTLWkoDeaiQ0KI3LYxmHmp5z
64zZ62fGpjD77qasuRqCuW5+Z6H1I8ri+clqXhkJ56q5kH8DGxc8AvPSrvJX5xOF
VHnGQdr1O8MiAzjyWZRLVTAqMEYw+coBSVI7bmBw1JTl9gShBP45kkwI3Xdk/qr0
naseThuxc1kNbDeXyfDx0+5v9NWb0sVusbZtnkRh9v0U/6DStbOc03h4KqPlSo5G
AAfyVUSuCvk/9yk3klkbJqZGo85yRirEBUbwZrjFvx7YcG6ZdeGqcl8/C1ENAOnV
q+REISLsPABysoFUHR/qYIW/74w6USiCZIysRJYO3u4gEW9kM1AC81Gc3BOU6cwT
bvUtyYd5VZFacZIpDaSHQqUaBWlne4ZEbp/fY3TZBXSPs2kWOL/xrYzWsBr1fs2x
mS2OwvtRfxpvaYT6c9jKnE4HLXYj872M46TAn1HTVBPmKRLqCu5Z9vMd8lQLxAT3
Wy6SOMc06wQXFNcBO47U011diCkOtRTKSo8IJ9QAWJ4UFhavMlvIA1Ei+1hWALHS
RAGCplTZOXH6iuBG1GTzgRnc3r5HqSgOZEoP4Oo4N47GthuDLgQfLkPszxnjyrIK
e8VwRsqgLz2pg2o3tIWfls5Q/YM1
=dtDn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '35c0cdc5-876b-47ed-a818-ccfd28d8b71e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA+p38wQEIh7oAQ/+MDRG8FozUt1WwHhupdr1qpR4mcrbbFjMDaPNrXykET6m
s11KoO3xwLqHlxcczQPuzDLHzJdQX3prTnK3VHFZ4Yd0hP31docz+Tv0dc4uClq/
8fCLIFE8ketGiELRTh9zQX8b7bIYxLwnBsSBjPdH4XjHhmVe2Kd2t49NZ6RVtRY0
A/tK2FGSUtWuFlg/JHrwfstZiHBbf0OZ6343on/4BG8Rsq1VNlOdQWngcp3EsUgN
JtDGpafVnZeMipkGrjTILg34DaDvbGBNcJ1hhk4b9ZhaNDHvRYIewMJAzPm1SWo2
4O0AHLVjLVs+KJMIPVJhDGsqRv3AyfkkAr5eA3U0wfP0WY17vp1B9wUmf/UNpv1O
UJB81jByF8ZVvrLG36jh9yjG2+3ZUaNZ7l6UfvEftKJRGMpHHJxV02WYBssclquk
SL4eLUb/8AW7jibwrXvuHuXPw7PUSF/tn4aeM4DFs22rj9fJBkO2k176jRmku08Q
+ou/VQiWRiUq4GuWbhHwAXVARhsjd8JHHbr0bUK0uZ2jO2v92VRhSg7K9u4xMdVH
9fCA/DvYTaTde9KZSWXQUOYhPgtvXBxgdEJLSuHxNJbUV8/Ch1mX4XnBrQO/aSJz
9/ug5U/r7njx1YwFZBcn2yGOc57R+7FV2Fgm+oCtkJqeU7C1GqWtpG7DAn6erW3S
SAHHOzy4lkeZMB5PiGFS/TYoc7m9s1yPdtPgDHleBs4DtXXVpDA62qTpUAGTv7lM
zFIwiHamSgP7RBWH6xOHQCS4DArdPKDt8Q==
=JZVy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '370a7d30-9cb0-4d71-accd-0382d3d4a793',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/9GDSJrRCiRhmtQ4EKgaAQZ70MHSVzpDcXfHt9GSmOTqxZ
luYj8YDxxUNIWAg3Fm42Z635uU9GauXNj6P2PgeY/QEpgDUiaTFWWhkdBtx1z0b+
Trn6AYd0Z0yevPx0E7FyH7G1aVVyRopkFdT/shhKYi6lBn/ijXNAY3LN08YZH3VP
3uPStK9aj0Iliwdvknl5vsoRgkYOqMQkwHxDZFQhrQNFQ2JY65OGlizoA1wldXHQ
4nq55MsUaveJWaKbtFYQTx0dC6IIaCuD5g9QJm8+Rh00SKY6zNAEz+DFxhpnYotO
w4+tTx+1Qh6QCI+3Zmpr3NnYAZD5YSqnluYGR2ZnCySTopvb0NBoGmHQ+/kzpJ16
npLpBGRVVqDUPV6Uu8otW0zqNxn/pD8RRfE3QrtwkiMECi/LfQGmhYAw9bTL8Mmo
mgctyqP3NmtktJmcBmovTEzhGJRsOa0nT/IjPaoinmwk741qqECg5wr9z0TCODqg
ZgirjLKwHl3Du+dz9wC/tdz3uySc8BOP4h1TwJq3WtzxI/06yPwqPilCN8Medubz
/ZsHNr8bEZWld7EkFmOfC4NL+lj+4s11JF+6Um+UpgU10bmLOf35YIIdXT9gF+xc
wqqzjJy3RrVNhvjuNGRJc80BoR61aHH6XvsIRjDhj+0dbFCo4Hqyz47KvllwqDLS
SAHnxwfFVbpdeCnxxc+gmqOW4OOPVF0QLk4EL/Sqeqei4bWMghBFgv+mtGERInm7
NgdtDs6XvQ5HPTdO+sjM3ZyKmVpPqiqJ+Q==
=EhuV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ac98582-5c8d-422d-ad91-5644fd24746e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgARAAttgN2zKm2+dq0pqkJM0qMMFUYMEDjCjDzQuDOj7EA2Qq
8rj3khhQ5RifTza5frMC9MGMFvfTnlLBr3dpMCmAo3H6+lctgIhO45LC5AHNN0yQ
3wlvIuEx+ueTXCupB0avxI/AcNH5i136mOLT4+XPBU1+xyOTV5ezKnqWuGTuJqV0
2hj/YtCZNnBBNCn32utkuWRCGG1Pn7pgabgtxu1Ek4iTvSu2H8eG6TYQk3ScUUaI
9HXvBpePSC2+Me5Z9FfqjbAmv1KsouK6E2+gjS1AgztlvtrpBe0r/3dn2tk9HtO/
u4M6PqX5Fr9kGi3T8rbqAi4/F9EOVsUohWbGoTeC86jwYZ/sdb+7vTAzXE6XLWQ1
6fVB6GVVMAVUr5SHIHeES8GBtAMT4nqtYTZGcypJ3gr3L+VAnQvIaDBQSBH+7e+v
mbnm03dV00b3RSw/hXKPVWyZGGeqqeUizsLBqHpWWQZSS2/gOJZimrtFqmc9ZhBs
tXNI0amlQP+YlIKgryttz8LAlKx48aKo4kMn7UIdN5z2U44q0yXjWPP2DNwwp+to
MtpblUJ9jqNTg8syig74GU7nm/nbXaRRy4tG2rHU2v0g+F4J5BgP6OEBvwvaiX0F
xrHmDzQpN1dBshVN8H3nWHVSp57ohEHp5Yn/aRWct718vxAowFt+cTrfIc2b8XnS
RQH0DmKZvbWlv6L1yBxY9hUyP3MHFrWXlsIVDCswsXhCWcJ1GiP3gTzaQ9YIYEHU
Gdh1o53bHFuN7RZ0d9ejkwCSAwPQqg==
=6V4i
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4c2e72ef-260a-4000-a068-6eb33e17f37e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ//Yih/w6p2zjnZf+8SZu9S3yNyPHAYMeEryxjk3/FHPIC3
Dhu6NWW0i+ko56DUe4hDzwnAPmwx3YNJm58/0G4l0/Rt73PLlB+LzejuLas2E8zJ
hmBigKu4vHtFf16l02ZEh24WsHpAEk1D2NtbmeyzwoCbLkwGh5MLUWG39ThrB1uD
UFK1z8B+8Qs6eccx2ay7Z0QBUC4zZ97wP7OuJPt1TV3dABIenlGA0vyGszEPQBEd
EwH8V+gdi/GrVPEpic9OLleKszyt9eLFwhf6lyOv9gDlScoh3u2pIeyv8lAj/ApE
YypG/TbKF8l9G0xYdvXMQTUWDTDLsQV72z74MN+79BFEzBnhWugzIbSH7TvSHvn6
p+m7cNJ5/UptGz11Tdz5zXPW+L0ZvG1O+vDB4SkDfilAU7/C8u+1ch+db5IivaxM
9pR0YPRMxqtRcqu+NVoF3A69yD+jIVL34FSfJQR2wV81qFzbK8+VieZAlere+M8Q
mWxcTsgCIk1CXguIVY9dtqL6b+PA2EH88O4t/cHC6fkex2ZMyQLWTE9lXg9naaSy
j4Bd9DvL7ZI7M3MfcI3TJnOWJUdd2eRdYEf0xkL3Jr/WlUnfv0pRfa5idLWbhRDB
HjNLA2rKQBpFc9MFL8rm67jDmhZ3z4l7kF1lqW63QcXYd0pMvajGpjJS9T25hNPS
RQFiLzV7ngdBbkRClD/y4GNw9KQAK0bPmyCaDkuCzUezX0l7ak2Kzww4dfEIk7EB
lObDTDTLLAGjwHgC9PWj4+0NaOvYsw==
=8gjG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50ec1070-bcd3-4639-ae44-5f89b1b73c2e',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUARAAogv4Exg4+O9vTbPFBkrYd0bmQfFGrsXuFRGUu9pUmnvL
2B0qmXDqnb+cZxBG3QguTmox3VXKa279BtW2LdVqVf63ExqqG7rkMYJivUy1Lw/z
p9K3RIO2DACEIrWnP7Aj6GeBHJOaJqjSG50dvHzCROVZztiIIHonQUVq0Z5ichuV
jqaTaIy6AU/uUsT152w3goOBRcEMfVbVaRsknVf3RkWk/VGmUnerCF1NAMbpvloQ
KfwfIxHBccwcK+dtl2kctqBsuyG/V2d1PI4tR8rIbld7M2DO+yoCn2Ru2oHmypL+
fEo2C4/XIzP7phulgvTJW4CVgmU9NxfYObB4z+vq4U3DOYp9GkCXIqAnX1Gvw8o9
NJK0w1OgEg7UBJJiKMNO/DkrE5b8Wfby/Fi/IxVFPvIvC4yqfj1zDIIB5oVvn9l2
zUygbXLOYE+C6EzXylzZOO9LeXygcofWaMa7WYg8C/NjQePw3Oy2RHLjQiFyLYH+
Sjn4Pfvcx7yrKyOz4q1tnezMKp0EBgEIFotUxxK/pb6Zb9kHYpdha+yjEo2aFngm
UPwPlAz0VhRz7UXYBNSIsvi0uGO1YwQXnDIwxGNBvOUdA48/Vwc7PNyqnJVWwdvx
WW5kM5Me2KDxcHUH8kJk8p6lmApH/Rcveut3eZ08r2JBXL91lLhUBYgAB1Bx5k/S
QgE7DrJQeu+YxoCM3cZ7Jr66JcmIxN+hGsItUQlWHO3UKxkVtoQNXTuxkK5qtjJG
Hx3LyaYz3YvkWxoj0yAxp3qGug==
=U+r3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5d763dd1-e7f1-4258-a853-5575a89a3fdf',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//R3aBVdPjh3baue8k+GPNUCRpE90nAkEkw0AcOSkZPTEN
QHsYdI2IgBqJfyYSlknyHJB9ibY/f8LTC7vwTfXcnHJT49N21YDrGp5ANgO2NqEJ
GyAoV+frtZ3Y+6bTs/5LX36v0TsGNiJltNmCgpX2Zl27oVpPy90foLa8vK44HYbA
d/Kyu39oOXBfGx4LQlce17SIruMeW5xrEbhLUfCrzLBK5WMyXc5DmZzRVRPuPF9U
daUm4rFF9C2lW2VpXWfYaMvGJe+itSEuOH1jlFAB99zTi7qs5ZaxBKv2bja+7GG+
bvCNSYNhvLrt9EBs8bUux9zAQXAdrtRdTZpxk289zQQ/+sCDGFpa9gDjIYu+a1GR
6V2vxjQVjjixHdjVKxSwldOYXMRtsT5UeP17rPCYPmOAayHLon7IfmktnR/e37mg
l0nLlHEFackq41kKDilZmrLarC2v/dFU7PUgAkrHciELsG8+Cqg/z/hyTw01iefx
dFUv/sII4qyqVnLsW+qJdoBFFPmAUuwOxWvYsSLZ8OffWyTxjhG75YoBXQbSEh47
ceMQIKBKrgWJ6c2bQ3lWa7K9AAd7cH4B1WUFSRU6+YwMyRoKXJOiG/vpasNpWAha
Oy1jAmbUZXIeMSOFQ8Faza06IeATJ3DdVaaW/jm41eRCNDPeXPsIsBkA/0zHXu7S
RAFyqLJrKchMs9jr3XxYeKb4YBomlUR4maeXAJ4O6qKVZQMIOz8dAhqNOwdxDZNV
GBvWBZYuRCUaw8m7SZIYaCkJttaI
=GUo+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '60a25038-9d34-4e3f-a750-c9f5ecf72766',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigARAA0Q6jqoWqPChBKjnswSd9hRolyFCGgbz/OXy+GgUv7+KD
2JKUODVPHvE1uIj4bwzKVVZ0J8zMi/rrYi5C4FVN6+BB4zvvQADFoHt3bhvbBy2Q
fn7cp1mNknQ1hd+jlAuXfqqLgG6KGap8bL62FiAuvYOES4bbDONXeE0oc0qFuKtU
qxvlwYONGYIZwuWfCoj7TqYMQFbNZPQOmdEOHIaedtyRRPB9YUf/ck/dB8EhB7vG
YxD40BiYwc2F0tO/LKg7iisYIHC8gpNvc3hBJfmJmjJwd2ks88lx4HAEDWCl341R
L4lokgXIBvRwjYPI2xpdzFsh5NrwMixDqTp7LrkXYYfaUlNs+96Pvl6w5+d/+pBZ
K8tIKda83bYR2GTtdEHnoeKrMV8w+Z93fldn3B1eARm1uaiWpTGt4nQv1ZSrNXQ2
nlqqRTHt9Q2tBgR6JWJWJ/VLd3A4M6hpbbjAfBCp7wlLa32zcR75WbfJQGukBtzc
ySmYv9BKS9h/E+8IpWD8xHwx4Hap5REDQdQFnVZe61vxeaFv80CLYNK+lMhTUyT1
vxJuvy1cRFalK5graX+wr08hCP0Q9tmHdadF/9Cgs2U8iIxHYveLqXq61Qzd70o5
RarMHCdpgswRB7nyZ0qCO27vyTBpgwLHv5Im+/mxOE6wRTcV+SaMmjnpapUTp/LS
RwEvOsv8WxWBsc8cLjU/q2PeAP15uoG4heShZ1Vil0kWpQUpvCcXhM63duCm4JYV
N8ILT/Juc0qcs9Jlhdo/6BH71pNMW0mV
=Te54
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b2c6da8-4bf0-438c-a1f5-f683f736bf0c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/9Fj+6A3BPyp0UEny/JfGWNtDDnXhwQdKPBR2a/s8CcHev
Du/NjpwYjfXVgRDoLb+ZnAvadXPbQlkvsup9VsWHRxpn1p6B3jzHfgOrJ5drN4XI
jzMy377Q1r8Yz8znSYPj56W7FIKzj4QDxebdQ/BBqQ6TAzZNLRUepeu/x+M2vrNe
9DM7vbL9RgyHOKx/XjaAK0ad8DhI8G9IIVoVhbWQi69hFzyvFA0v4P233q+iEAx0
580duEg5LJYiLaCl2r6dgtEXb4PAZEyO9kTybM5I6xCTuOEwslTmphyY/Pk6PcJr
AY11SKIinW1nYTSDlD8+NkhXij44CdMn61LJo9WMU5qnjxGP/HwbbSOxGgUGVWV/
85KOHkIbxVVxE6tzRouFuEidKzMTSBUzyEhq4jI4amNhr/0utb5RSP21dKfmYCSV
TzVh2RTFRQeoyNWgeH784YoPzmlXLb6TlH7NSmEDv0fMvevQog8q8rz/x5JG1Y6A
9Cn59HfHKaWMgBzwGRoSMeoFAbxKjoy9ayJqFQ2rieA7W8S79IwxtbrFSpku5lC7
+lx6EGa2Wenq6FA7Qq4NSk1aYOHYvK9cdHFvfJOJpSsJak4NoYn5CaF8cb3sARPo
ryaS+VpA8jNIXfy5EvDfRnaTkUjH8zvPfLTIesrTRvqMAG+Q2ExOLAP+Bom/tSzS
RQHHzsCAN7wNzJ5XjLVLBTvB/9OZKKGEQIXuWsGTWdKqXynGWeLAEsZb0pUOtiKf
2r0UahSrhzE2kbrhWI2hT5vBTudCvQ==
=chw0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6fc79813-5703-48fb-abd8-dee3a54b2990',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4e/DeCIHsAzAQ/9HMEoQCHpSIHraDxL2GxaWmtXTNm6brrBaCiem6ZWOd1M
sS5Bcak1NZSRp7PJdcOr1q5oXpIpROZAxwENIKVwMC0T+95+SSePlBvGbb+Fj1YZ
/cA8ssrAiyGXg/UhSIMrky4SbxoU5yzQcPuOLwSRXwo4NN8A8YybwQDDV9+Psyh7
pzaDxivoaob5VMPBBV59vhKmxiUcCpG+5ROTqHY/X+y9trtSwV+b1f1klUr+UQs0
1UiEHOAFUiz+tSgvrnFectxy7oedvXtQojfQxtua+HbgElHL6VqZiIMu8JLfvyJz
dU7v62qjC5N4Aoq+25hcHrU/quEHGiQmW3I/4izJUdoUDMP4pqDkoY1j4SkGX77d
PdA0KgtogP91lEgR3Zqc3/ithikzTsnv36rL6coE4aTU4x5t+txjpzUorjjyHkaO
k0miZ4VlAuOwXwcmyJmv3BYM2h9B7z/9zqCXGWQs/Nv2KpSb7OL80RoeowY325Mj
dnZ/57zcd2ZDqsVTiUuQYm81ZOTKNPVyMJvXgBrCyKU6qDf5AZAOFsz58OWQWKHx
wUpEI426fO0t6v5jyxVtekQBKlIlz6PV+ri124VXvC+0RjVSjglMNxN0CtXjs8S4
asAzCGtJa0Znxm7guWR2CtHckTnTvT0W7gySOSkQ9qGhOdnM/miqPQWwWwNgLAjS
RAGyE6+p/5wfpp3ameZ8wLBpXnwfGLiweld+O87fvAmlI72x8/urAgJ2m1jWlMOK
U1uJH6FThvm8cDFD9JDmLM2VWgeF
=HIEm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '73cd30b4-c769-4489-ab64-c4cf03da88c7',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigARAAzJQxOuZh9LHLo3FCnSm0Wn5DQks2+p0Ng+qGmRRjm0dx
IW0Anh4d9CzkbmRzK3SYosD6HC+t+cQjPaPbGN4RcPXeZhmBcdZXaoconR4m51y+
jTrAXn8+miLskHB8EJUPS2IsOtCQl47NE8aGQnDmcCcAAS9JG9hkgCMBIuocsA+P
rk0+KUOQwYqeOze+EVI1c+JevwlHsWA7g2p45KQuoszPTSHKrKtRp3+yFaUv3ms8
lP65FFRhnSlb23bnatIkxnct/3JDa0k2nOzQ6PMNO/VyKWVIvKfXbjCVNR5Qv1C4
KFCxoHI581Jyx9GOrH99iDNPebK3c6o5INExypuWh37eLaBqgluLHSvFfWd/+zsQ
v+23JMQFqlpRd8+95hitw/bZqnfqdpix0fSUipjSqNG9rLIMMG55i8mkC7MT9i1M
uO+KwU7rwvxZ13JHXu69CJc/WSrnxI2bIN1hUgd5LtUgiCXHja6gTFvbXJcCtvSI
/8CP8a6bFD9u81/Nszjca/Os0VC5DMMPYwJ/AIk3xcyDXnu51WlLdlvKrPzC+hsH
xXx3vHxufPSV5AxUCkBsi4/dwdOhrhM9WEbWFBb9ZNj101lgGcW19q4eAAEPgo8W
OzBjzYxaMlAX4ORGLtoJFIGmasvNI8/+yfSFeX9f5WDfHnGEiPk0HJpdVzy2NJPS
QgGlGenTJNZKCvJfOllbR3wiHupsRC/OTe92+egd45xgMwQOjxvg261ZsOilaQ2I
LtP58qweItxT6EFko83BVm7KTw==
=RJuB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '74bef2f5-9749-420c-aacf-cd9c1e6ecd5f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//czXJ8qrbL787MwGt8KXJ1yewi3nCnKjmdf5RanoK5Bri
x5R4ANaJZ0cRC/cyZkl2zo0RPLiP7I1TOQAxCkZ4QKnO02bNpow3O2deYmBjNDTk
yfBHfeq4ye8T551hbUrzMYrcqnfqp85vqlhL1s61szk+tMu76joNffT8DAsyMy04
d/cSOTz5mF3mPkwQZ8LnaaNOHT1ZTn4lMVwFnPl2ex0EaG7CfKswrGEzu2jcBk9P
jKdBXPMOIEUIhRp7ytsLKBmhOXKDSVvawUIFNOc+LdIyoQ1AhRG2OhDgI1OB713d
6x0oO80vMk6aUtRbhLzWqjuim+JX6KjQrGY1M7U0jui2aE6r88HH5zZ4GSiPnCfk
jK39sbFwWxYBJXzyRANCnT73T/+VS+rtiZoySprsU8ypPI3Js/LTPpt7XiB0g7sF
hxD5EwHMME9FufDsZQM0z65Xi1c73smKNHJnBOxOsDJ6KwnFoxNVLKDMxvL4WPaG
0NNVQsRnAnJ1arqXn6SNy0paAgblUfUVTg0B+heaes89YI4zXDPPDXc5uVPEu2Ji
FRNJY8oIyonbWtkD0YcSbNnSWjvLtTPsKX3fshe79MlLXjKvIfFumGLJW4u63u/b
IkFhAqhZ68oVcjETiM8iiRTdg2NU7mtSEGceXbjASfgJ4dEoFajkCgWU1qXouBvS
RAHOov0g0YND5Eb5x46uHhjwRjXUxtKAas+b4PkHFsYQEuE0wCW/GNLNHV4RK++a
8S74fQmEPqHOjM29rTffvR5kDgez
=JaAx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '74f262cb-e41a-4286-a65d-789f1073149a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAw0P12ReHhxtAQ//YxH8E/gcg50AqmHly7pcZg9aiKiROMAWga7+u8oyQHi+
k0RGpA66/rPM6c20H485GmiSmgeC/ljnvl0HU7qzfqTvgaonOo0tnhJwZ596h0FI
nwYCgEAigkp16qjvCmbf2JosbXGlD4LSbIZ3RdMtAgFfU4c0rQu2W6NzJ4JB1tZ4
WeXzhdTkTvDIXMJP6T1+t5AzBtGl1/hcT7qgpopYW3wxFZx/9vT5awW2ebdOrTtw
C/dNshQ9f4wqMch/jybPkPHPYTZXA64sDkvopSzmiBw2tmOr2SKkaclpH0bh2vyq
WtW+ODJqeqDDuP1YWVAA+JGUVH1mNOzANonZ09xQUVyOoXt2bfacECF9SJuIFoU/
ZARN2tNg5zQ9CZos+/tEWVqDZyveESLBSo1uh0ikXqmfs4BPYpYEDKvUHONigGxA
Mr47Gj7zAo0Xi0uFYWK6X7ekX8/TKrKzMCD5TJkCE3x5MF2O3vcTxQWfwkZJMynO
abxiujrMWfUCcDekYHPn/47BbHKkqn7ezvXFJAngjwRwTvoFwtEtZ9Qij2Ma57aJ
V0iUJnG7XCGB5T/is8T6CGLJSr6Oo5AMkAmhU8TRYZEwJR7L/fcVbTJSUBiQCuDw
mgxM59BRt4Uu/0JdsTiIvaiOiI0R5e1os6tY6gETM8Qkof1BbDuzsC8/mVJc5oPS
RwEW2rz3s6vLlH/0HeTtg2KKA1xjicQgrrXY8QEg8YEB1iO2ePdyMeOyTmlRII9R
8iXhSgYqypXBHnHEB1WWXe7y8ggpegaG
=oBhV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '81fe7a7e-a1e8-49b8-afa8-76a059db31f5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//c77Eb1DAQYWefFUKqt/Vz7MZerKYBaXEsBsKtAd0vRM2
MW4gzrnODl2pxeejyGwjrahfvUVQCfdjW5ECDKjzAWDZMWeinABrfLG9ecew2YKS
/iWm2PP77mX5l1w6Xhu9EAeqGcqGqecmJzlgeCxThsae2J2pl8SA3ldoQPdmtucM
mkI+MXPdQhxgdmSgDJ1xDUREW0eupepUmM33VQTJI9rNaGUps5iqI6ZmqFv1e/5S
gAKNYrRlJUcBaL9gzPEIWVFkkMQKp1ELPK4rxYejwg1IFIvBpEQljKlz+J2RIaog
KhArzbZUec0G78CzQ+fphMFOlPXUsvoVq56dPE8B4mGv5AZKQpdg6axvOaKvXonn
QwiIKpjuZdJMb/edPE98qeiCG+/yBt8NOz2bkPMTR0TPVE+F4H1hcTnoir52IZsJ
L1P3jvrW2sbI55qzH5Tp0UpCOO/Xi2cE1gy2IHHNj2pso/2/b17HxJHNnKaVW3/y
1Ra2Ydc4gDNyjuj+thUqJOax1zK1I4OhOEJqR0veNEGYnUnCnF1j7QKGnqXv60H/
BXyDSWL5vlZxV6jZ/6aOLD58F3NNZ/O1gm/F0Z75vgnE1ktk1OAtXnt9pemnwRtl
ZDHpXQEUMzMn8R3O7J057xgCdJQuGbPPDVWqMmouJ99tdPtBRmSJ6zzAdu5Y5FjS
RwFV40XZlI1hFk3xBxR7WPmApHVnRkEEQ1l/+LhavCLA+Z1OzuUE7TOhRsnhqqI6
bIt6s+sPBzEYR7mxmXbB2SDooYQGoBjN
=1tyC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '86454cbb-3a07-4de0-ac04-575fba8a7bb1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//XG+9wAnGP1xBKNacpWBWtiunZgnk47ETu1BVM6CVi7DR
8LtgQupRtS5DGMCnhakYXX/awqKzATGlJOrsIea9od+OIWU+lecZkui+j8al1TK9
kJZVCiXhQ5/U79fWAi5xQwgobkSI77JvtDgXMYG+LP8lJPaJ5y01DlvRRoDJFU7j
dhh3tnn3BGh5yTuO+0V7+u1XtVcUSdyuO4Ix1FLg5VXjpRlRGTvuVrwB5INQ7mPH
XmVQJOj1IuZdQYToTZqoSLBPq2gHLMmmIc5hgkqga+5jxUKudNpjgrOuwJxOGFRE
bqHJ28KKPlhMG82v9POpaBudH1Mxcste9DPgk/OFEeJqMwuGdiWFwOyUS+SQt/Gi
X1slnrPt405k9LuxlBI80KSDuYfP3Dwmy7s27xcn6ByArS3m+FeDWlDRRaUgXpdU
HUi87G2pFLhNdOGu7FPZBCu/f239OF6BTwMAlAsrkG9K6lDHzTfprOxM+QY+XE2Y
tItqpzQS7oFvEn9ieNhFRR3AC0brXv9qyN1TwxtqWSzO0A+dDp3bILuqig5KHqdd
y3fQ49p57FdMcDT0PikpuGgH+him6DDDrKCJjEutwdUcxDUC5MpZUVoMs3SDWYd9
M5ltgypGUxcUbolFVMe4NdH1G8KOUlCkpsjlq4gejVfIQ6IGVHHuI0A5GXGuAubS
QgGRwrAdf19gSGY9MkQc+gMNG8+975srpaQjh3dqW9YWerawa8v2vc2kziu2+vO+
euSF5NoHE4y/K0BBfV3uxHwk8A==
=dDcS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '87c4337c-0e82-46cf-a0da-5e5430f5f7bb',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ//W24YEeX8R6R4EAJdYeRaIAzeQql7jYRbKCOGwugVQ5BY
7PW3lHKiUEgW0zafYgDJ5cuV9lvGjEtMxiq7d0+Tt7z1A8947nwC3ROyz4w8EreA
lKLaDEWzGPXfa1KW0ebwJXrlN5Cm39mMk5Ok4I3RaLfnaDwpLwWe5gI4LtHyeRTj
PqNg9OAndr4ckIbxP23QuNz0u9NeuQAQDCpUYBbQ0bjrpFTdk6hB4F6F/FL+rR7R
C2o6/72RFQk0B0UpX3w/dnPpDxkFd1kbTKdMhi3B64eBa0yO99yNzSJyuv2KR1wF
Ktf+hp80enN1SGRpzGSKNFSw0lFcXwnQnOZQOZrVNxy7U/+DhS7T/HQdCC3zX73f
RiixnZWVkT0Z1jRrAFFlE1oYU97sOyIRfWhAEWr/N0V5ksm1p6gnFavDC7RmU6zm
8e8A58eXMmitD1v5aY0xpuRo6jzfLxj1dO3EtITtbMP2ur3jxvNcTK2ELA/W9jpo
hFYop2H0Kwd4A9+cuwhtW2A8eAeG7TITmf8k0AiyVNT71qMZAvY/BRYDUqeodWp7
ebJ98PB3zrE15Iuhm1dSEtPBiRf0WgZNWlqKWQOZrxDxJJfG/puza4fWw1NnB9Sh
0x3PcAfGHOwQXj9o2fFWYDmRqv28ecp6G8F1A8JgbdyLCFPEdvwXa2n/TaQfK0DS
SAHL7btei90DLZhVCmRyNwqQiOe5g9noTuOTXmwiVMGrrvBw4a+S3M+lmInfP2m7
IOZ9v5LDWo9UwW8pXN2Rz1PUydFPN5j1jA==
=FG1P
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8ceff6bb-31d8-4f8e-ab3b-73125c987969',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ/+KaaGTZVcBOixKmeIFISqL1hWbFViKhcVbzxp7He5VF6N
6GHXtMadv5BKnzixXtsjxNDgZNg8jEV0Emqv3IqFy24HVkiu7BkwNYxsRxakOybS
roo2v4mlFS0ihw8/CYqYEBIryAg201eA4UB2MRXQhawfGyx6TQyMwlf07BUWSv5W
OaGxV/PIxfAg37giSwtj4osfM5UZIYmT1zHqkudyiiXwhnIMK6aKsojN3VE7lOF6
KrJc5GZqOqSNNysWjwr2lb56sjfsq77iwP3Aqd8001/+bzZRI70UyK/2rtz4hLqo
UNmFBBaWaZlmtLi6Kq09HtQmxXcEn01WWjx+Td01iFXHKmnisGnS217aYWdt//dz
9JgmMBrjho5UzJXBvLlQt6nwoEWRhQHpu0+V3At3U2PJ7TPk2/Dib1HTWWbCwkrw
lFRFtmzwOxz/bXV+Avlmj/li4aq7QDhPMzP5lsZN9T4oDQZKXHNn2qRYIJQdo7iH
VM9XnMAMPe6HktgBVcSP0nfy7AB06KpKOfLCOhyIIgmbpiegExR5ZV+syam35SlR
3fg4E4iIeFIPVgNLao1bZCdybH765Z4qd0q+Bel9fl7LpvMPqI66/vlv/47U3QYr
JTuWGl+jTQb9qo84SRAh6mD2vBkfgHpvLkdXAFnhWQWT1kAUnLa82HFUeFsiHCDS
RQGjl7Ob4G9WMjy37LbG+fSLw/sgnoXU2ZIdu+APFIRO2O2RCiXiJ7J8e854TDVF
/atOWdaQfGfJ+XYSOv6MMsw/GJF8yA==
=uxIS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9385b9d5-deea-4929-a148-ec598bc5d06b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/9H0zuOdN3ZBjRkWTWc/H2g5LYhLlq4LrYXb1jv6i+aoLE
gpIdnDVmuH2mTIBOMesEvF5cVT/czHWF5h1tpg9pl/VOvx1oLIcpS/V9LJbTbKRL
QqFwB/Jp1whv6L18TjAkiv793c008zLbjwzQOkd7r7VRRGfbKJEPYJ6RevGoKiZ+
0fNwcWkI4I42T99g9HpPnCpIAraxG4au2PeoNjwVXm+6cWk5lov6xpHN9xmyXVpv
NVqgMsl0ds+KVprDFM939riK/Uwhz1CUekQLtlBkcdsJ2O9QYo0mggSz/QFjXCkf
pUNrJdceD3BCwK+vWfPB4+5/BO9EZA4jAZqgo/Bq8JqswgT5DQv3oC85G7RXWs0g
nJGFFmCNPhcN2wZmvpJtAFCzqJtmAYphLpinMVW5rd8sSqA6hUdMVOhu6hHiVGKo
zNg3/X5raEL8D9vKLUWcdCrP0wwW3VEEEEr1T67rWI8CAo2aP8/5+meUjjJpoDcK
WX5Wtwa8eq2jdc1njNvxq/qDlUMSwZzRyIUxWG8fgVYXpdG7nBrMvZg3FkAGbxJK
Egj2y3fEBj4nqFqzCgFbOsUEUT51V9tV8RAzbQNwaIUPwu4g8ZbA9gmYlD3V5JYu
/LYJyeHA75oX8yNEPqyFSSXui9djWC0Ouc+pbm02sRNPwF5jkUeHTQPzeV4sSGvS
RwEqQgXSGGgkk3n4FczMxeSYyyXHm8KMz6Tn5dlFsiYSw47RmRwfVlGTwHDJIUBJ
Y5D8UVFyMJhV36gflxES57WorFUopLrK
=xc/j
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9659898d-7d07-425d-a517-2accee0b542c',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA+p38wQEIh7oARAAuD01SvduX/VTbAr0zanjpL374zr0Hhbwtymd5rswEAZn
aB/OMM7OoY/s/nmuUv1b5ShVqLVEMj1pNMfaBpAcFXykmRZcPWFliImmaW/SEbBg
j/GnLQzZ4Ek2fCudMH8P6jIvkxiMETBzcI6r3ZKfiyHDTSEIERnhfy7QB97bbMKI
txngIPBl0TkNWN0gYg2fhbqgBeDZgvrKbLy0Iec9TRbWQoXguEE3yVWgp8/VGzMK
1sOPkxvoaRze2GiUYBK26+FwzGxXBuJabbRu1y/qUlGk27XYqzM7yPp3MVTdy5qA
wZZsQlIXmlY5OeMbR5mr+SDHniTCtPkBSOPkVG9fkeaVBOjyRl/bDw9ntqf1B9fN
lVthcBaM1A4QUjjQZ/iBsidDYpnLv8k3Cw2DHYJnEDlEYlv+nYPZLiBeHExjVFwc
SVJrMUehO3TCDa6YeGDgO/5wq2D4BUeH093C5EfziRYgEBxpyxO4rqYtA0FgYaVy
hJuyAK33fhkI686lJIWJd6F+7cqFsYaEvLF0ol+HMq8zyRIhjBQ43FXTK6mmTu4g
jS9sUuGRl0GFvU7mOsy722YiLRVHxpmUjjEZrmVmvZsV4/PO74ySPEMlt8EgM0Mi
PTyNKihRUpllBBby7FofbjX+hoM5XmDFX9VVyswlKcMfXbsDJYVQFAaB6HSolMbS
QgEBep+C/CQ/Mp+97P7ZpBvNZlmCIC8y4f5QH5515deWRbZW9WnLs27kbANK+KsC
4UnfOzWqFVpNkXlHMbrAKjAtUw==
=jx5J
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9abd0a38-2420-417b-abc3-7f0f75e51dc8',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4e/DeCIHsAzARAAyIac1qQyDRS8ZUC1CnJ1QYiDv9L7mc1sGB2dUdYPSGft
9nLyVj6CIcTXngtZjsJ3aF15xzP1n2wC8GQPpq81RNHqVr8K1UYtFqyAapyY4YF7
ProHexr2ba4/aLnWHjLjAJ2fh3nle6JRsL0qdjOeigfCYIdbzkx5PKFKDxls94hm
ioH3n/YnD5hDqZeV5UY3ih7jjbvBPp8xgUzsz55FjL4yUbv4wfdyuAbvRXkQQWWp
BOZrPZ2y2eEuL+m/0LUmCJeO7T9bUZ8P6qUh6LBSl28x6Fxpt6jV6fxOScA+SrLz
FPxgspc2Y1DedVEUjrA/jtfIcyunWVSRigN9+ml4PsUHEJHiv0Pfez7vvS1qhiCU
epUEq5MrgSquNQqjy2DdreADmitDXkms1oUeoGTMqdafNNewlA2zYw6YOvNVmMVa
C249M9oWDTVCf1RixBOR1YfsPBb7xMlMLRCNhzfXGflkm5h912gtmXSwiIs9pNbj
2oxN9ODDi2f24rdwlsDKyQShHlNeub6XNzHnx1aywqGJEG0S96y0U3c2e51x9IOY
K4FQmjiNCDhuSDFy1n4olDn/1my+dxCOUwbStAp6ep7xSquuJoWqEDzTnozXumNq
8P434tlZRbVFY6Fb3j0qplv2BmPqbu0tc3SBlzSUgtFPQeETgCyzat5F1bpFivDS
RQEsPwgWRfgwIp5hfJAeo6cpsCJhjOAqRPyfwe9f2cF/5kdlJpT//dSVf2qzZVsL
XsaRqrMor0/lSh30RGyMJOfTjQBVcA==
=B0cc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a3b28ea2-0772-4032-ae67-19ebc55f56f8',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQILA4NlM/alsYWgAQ/3dliP0iD9jrX9FOiVowfxL/lVO1w3+xnotIE0PntwaNBx
Qdv0tgSw4wyvFxN23Ge5jTowiGCY2xvk/kuHknPgEuZaF/Uw7VZRcpUbYyCu5Rfi
G8i6HahIaeGZ9K1XV0kE7onwKLF2CVe6PxiXQw0hd0g8DWqCRlvXlaOAPJoo5sTZ
L2+X1SUK4nirA8EdSI1OgGzzcx93Fd5zhxYQKvSZcN6Cdk3VVHBnwGHm2NzYWGsJ
ZwYM6ACCROS3N/nCKIV+xvikPUBaRq2whX6QGQKPeRjqEH/dQQQx1nFvJWmOiAEJ
XrwOWRYFF4EaCXJA2bhIjIPU/XCUnovVB7NmjVc22PMhftgCopfIR7qg8Gy0wMPY
xMrkOC7EYlSojLkhYiyAyd3X0x2Ji+8HBp0t2NdKo90TOma7KWzvo2RNidM1iBfh
Vwvc34wfeGU9eNj2pF2AWJtRNOJTv2HH8OdhuN6YoF6RTF5hW5HkgIZ1Feg/04oG
CVHLmD7zZKFCXa0w7A70bWxdAr51M92jjGVN1eBSsGNmA/eCvYGT/PU+PmqVDbLq
dd26zC/8Bltw0NpPa2xoj9k1qX2aBpKEV7l8qt/i4zWvs5Qutbwwk1jtDMCHRXe9
3UJ1ZM80Z92Xcu9eqfEbn7Vkto+L/i3TBLNHRjsmvxBfNBGzE057Bz29azcbstJH
AXlY+jSQouDk/QA4IMssPjNGTW0fwtXo3Q0QNUBp76UCPgE1DWSMkUFmNJcViPw+
dVmjyhhOByizpW3by5xmMcEiVgccmSw=
=fcL5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a621c725-431f-459b-af77-3cbf7b2cfe77',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//WQzJrTlPcK/DEMHgnTvIWJw9cfY5UvhkEQTm1OP7AiKh
wrIDH9FwM7HEodyXMxZuahy5Jsv4o3KDLRGASvJwgkI44u+XsYFI7y8i4/ZcemaF
R/r7mocPhu4bYNjdWEO986BLrYE0TuNXWZzBT96BZsgPPyjlOo/ToRLHfpP6hFWz
cYkZb3hRthLMvWHJ9BM1UejrsjY/NwWfl44CbTNhujHxnsV8lUn82+vCgCL+cLH0
M32Itmog85Dg1CJr6McAA6iLVYymKxB3GjiEYGJeqcrzNR6X4VTgO0xcU5+vKi/O
VXgw5wmgTn2OgNZ4/ytYVyr9yL7mYrFed9htyNWBMp6mnPaOZ1hNbo4nRI/Ry9Nf
a2ELEFiS8OiiTj+vH2zbOsoc2MOdUIbHol5T1VSvd4yDpXMtarg8KVK+zmofLLw5
2Mh+j+o22RzpYX8xch90LmtgF5L9I8n+IdAKlpCum9P/6HhNRlypzqDfcSKXU8uB
6gUgnM2AAI75u2/l29WU2SeAQPLit1mjTA+p5dQt39L5vqNcCzWCOMcDqFF/2Kdc
2jJxGNo7u4t3sgPyZ9/fbuymzn3Pbtq6F92RRSd7LWYMu80e1CrUKJ6noLgFmhLJ
Xzw91On1oIa78S1VfTI9sw2VQSSmauiVnT7kGUrN4wcWX7YgifaMykSoERLXGNzS
RAFBsyidQ9Sai0VspK1ipaNeYiHOdyKf0Arq9biIpSlLo2xCAI1bFYoMIVyNVyQ0
Ll9pqI1nX59BLbeuyaSTf8kYm/yE
=lxPa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a664e640-50a0-46c2-ad48-bfdb4c6ebf9a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgARAAqTjrDSpktUeG0laCUMm68iJQ3AcwNCms9KZ/d5BCxZvm
Kr9xiWRAkvMyP4rRs+G+Mh1c+jQtGYhqqea0rTGZSb8Rh19EOFh5lwgGULFPHf+l
1hsDQi8phK/Mi04VlaOkTCOFn//5Mi1BdfCBWpKo8GbyQmdBdN/UK8E84ehsQYq4
qjCCBdGuOTMJxO4a8PAMxtzy9RmqTSjhpczgZLvsYE5vdI9RgB53gjtWmfLhP31y
yDoCdfanPv9h1Bm5Qk0ZwutNKk6qi+cp0rnDTIiOPQSNZ8sN8aaAXOAadTQHfM6M
ZTURk0w9iuO6ye5WlDGMM+mngIbE/a2Rx22jYp8xw4cBXCh3CoMwVbU8wcXJP4mf
masBRiRyS8d4IW8ocLOo9EXTlzmkbajG5W5VS6G+P1KCCm7bkMr3hqCgdzKQ3hRv
E5Kn/X/2B/YIHZlmx8wylcJ6YnnVzdD4kH4tofGW+Zlp66xGUCA+nr4J8G57lbr4
BwkW1IXkd2zCcHT1Lm/OZfGlsORvBoCuYiZ8GyyjJfW5SN17CaBh5Nna3rviyyWr
Qlkm6HCEoxe7v8f/mXV7tjX9rlj7/w6vNOoKg00lP3r7zU4+iGiObKs0eZAylbQo
XMGCO3iaEqL/Jyt0Z1EYZED9KLxPbBLqxKKARcJOs1X1Kapd8nauK4gGCtrSjKjS
RwEIYn9hojewYccDEpXgk2RKpQJkK8X6KxdAmUfvpG4nTbpujtuSjmSgWIkP/cD1
prn630kbcEXKxkJM1YfoIFZXDEiNdf8A
=brbE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'adb0fa56-43fc-466d-a35c-71db4171f87f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ//T441BZQtAhdwarCAbGbNFl2NSQUt3nDuXgBkmXKKt67/
TMJJfjqqUfBKfOD+b6fLL69f74xQmDr/Gu1KKn8oDmiOV2f6Icsb8QykxUYAqkZR
Tm1HL0ogHSI6ERW0I8zDK0aqKg6R8IobB0dTB1HWV7gU+q7OvIahL4S4B2y8UOoD
/bNDRv13znosdVuELRG2qvXO8mBZ1kqygcsplTtE4KSJoGQe2SsDWvOdgOwkRNs0
GS66x7GJvcpZuwHHCmy2jX3/ucdH9o7wY9h0NEJD+wafij8yHJcWUpkme+QRI2Yf
daoIVVFYqlBlFTFT/YW6cbA0IDrb07ejUJXkgBaE6q+MLHZNbYOWERrZotmWbixj
2jqtQPi21e2K4canzZc8+H6tmfjXjS1W8D9F0RfS61wlIfWGv9GjU3BfKu+kBmfy
1bR9MOpge6HIf4D3uWNnCVQMs6d7otITEawEBuUuceHWxISZsYbPqVez1LWhCMYU
O5RqeYTzEE1PEVMv3F39qRWU8mpcaRLfORaySNDIXDY22gUguKo74kH737ZSDzFv
vCDRgv1l4L7iKM7aq/QiZT4tjuLsCUqpMz0I3vRisBo5Kw29pizb/8E0rR0HkktM
Xcw+RiE/tgxmNSkz9z1+XNfpcnvl8gMoFLRHnOFhZz92fJ6XQeV/6a/Cf1N3HSzS
RQHdd1OjXhEEXB2U97H6Ceo7gWyMM2XWErjLOvMaQVrO7RBnR2t97NFZ15HqXqkv
MvC8tPOkfvzWM7chxKhd+Cpv/y8G0A==
=el4p
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b01d89b0-0d02-4e90-adf9-9495e73be9d8',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/+KjB5ct274QwDyb0gEpwhJ959rsVGS1xAbEhGHqwW5suK
52DpqiM5kcd4W/HlJtfC/NIw7TMzfr9b5H3cgJ6ICqu0BAMVfV5Jp9nya4OoGVSM
2IXhakdMREypc2Wnm+/0Z9zJ0ZN/lhJ4jHVZF9ZSdxL3EUlT09bEvWbRZkaMaiQY
fMQvRmynDh9VfxbfLughlXZynqhhQ9JQqP8hdndMBOE05eaMTRV1IetDWlWUWARP
AP/C8DqnehrqSJ/uHSc5PUsFvzuTJNd6m4OTV2SmZMuuLrqdi5hOhey73l3HoEnz
UqbtS9EChNm3Yvc2KQKUbAtE5cRrdWTF4NyEQqi6E4Wy0ejvimyBKx2eB99WuoLe
spMQY9YHtZBYU4y9KLvKbi6eUjX9db+SYMR58Aa2PZNTEc/cgBmpZaTrDbFxUvju
bXzY9CcAsWsNWhcezzohXhWQdo/m2wHDOn/j1TNVWoWkKazIiq/PP/pZt/W/+8lc
6slTDUT9+LSZDKzBOJrYsh6Kn3c8Y6XdIwgqaNSucQNGQDDR4x+a6kRt7ApYVR6T
P7L709ObRZEMV1oxfwlGYKJNwNUKKH+ha2y5Hln1+epoq3m/D2v408kBh4XNv0xi
U3K9rdDHrN+ZKf8qpjqduFBWlbCvYC4CyToKAXUVtRS5XRH7kcas0bvHBIAHj6rS
SAFQAcZfNTT6lXbkb8T2L0WnRJxzuDeWh3O2z7Zp0s02Uj8hMiYETYFyLJ0/4ql3
8rTaeCkF7qE2TuWgetOkk02DpVNRTSY5MA==
=UPYC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b8e6cfb4-dd4b-4374-a68a-5e2a8f5d43c3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/9FMLy3mu/Z7Qi8/EnQyxq31Sjr+E97pkA8wX7Ltjnc5gH
30O4a8Sc1AaEoa60/57Y6JJ+e2bpdOszKvqEudQnJqGY1s7hIIdj3/0m1NTRQg8e
LagDzNMIybopw/OVl3/f7lpzRgwS3AoHmTR3cumVAaJ7gAPbaM5hrpOnIJvImfnX
PfY2e+n6MvGL+oXoulVQbs0ZoC9YlSRbdBFBpH7TUego61JTcNF5NU301Z7w6VzB
9qKcbvbC0zZFDGgpzriuk6ffo4is5CtudIoYsytmflf8UD1GZF6sWbIQ1+h05kBv
Vil/Ng2GE5KyN78Hash70X9LhT4p4iwg7LUhEEYu0py8eLwMDuF5AqIVX0PGYvTd
x3QPinEbOXD3rrfIsaID9U9paceoF89+GwfFHSWU4+UbPmeMQtXsQjRp8HkIt1X/
LtzlusUPZttHlsgEU6w20KZxSXHSwvIV2rK08XwupWz59NX4nRxdVIhnep3mkZ0J
vCSwA9v3/hP9+Byu6DO0pMEY3+0a9vQXtIjmM0Y+Nu3aGTQZez0PWi44Qi4Qja2o
hAnGNcEOLExgOvO58voV/oSmxNpjNOtOQpQMw9wdyQB/RWLDa2teOFGGJm5pJxRS
NCUIVHGJLftJoUsLtcaKtzvhKtcdOOTdZukTmtvaUUnG5tYaAscjIxo5bceUGTbS
RAEKZ0ttmj0FmVzIZ3QLdwQYKnIBhS1Q/Rrzql5X61cnu4u3t4bLPO90fjqrhs94
DL4suXPj1jvDBelxi6cAZv/io4DT
=tNkh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bae3b456-e315-461d-a353-27bb5ef1fddc',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '408bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAzlz3zlJcBT3AQ//fzDgKspThkZcmtw5Ga7KAaW3H1rbeK0dkkJzyYWIiQe9
M8gPwiT1glp3B+zfvE71ZPR/JfxnIKp1qANWyR/2uVNFL2m5itV7i1q/KMir8i2Z
4+gwb9J40vezDDgX+k9OAJojO+fTTfKEL9i9L9+WwhHVVRVzqHYJ42bdE8DByx2A
0xBPmGXRs8JUaGa9y/eiuH7GlTsk9PwT/dkg2Bc8mCmYCRpmBZKUb7lu12aU9VTE
Za6YcopHIzpN6/0EEOdN2Po+2O1pi2Q9JcO9L5v1TmSqjqK4wWc39i/QkBOn7MeT
dI+rzp8YEIwRpp9CtMfQ9jldtwxn94AdplPglZwOHS5B3NrojXDC07NYalFv5SZK
GG6KVpDaGp+Pr/35LUJHXlwsbvJ/lQ5WR7CkE2kQ6CHountzNLYGRUNsgJzDnUXD
7BYKZBDHxonSye4PFHLsHDNcOShD0aQxxrQ58CsLjlwk+Be4v/1GmEknVhtH5pQA
F/FL+qCDvS0IJe7RwrH0lNXKUZhnRx9EQirPmAImX5BmgBsC3ceZ5Um45OyQ3zwD
WSkm2ksRAjjALTyiDkZLkjVtXqiXccF3DTfsqP80MY0nSoI704vYYYy5CR/qUJ5K
yqOeTyZoSN89Nx9XUxQDVkksxeevEHsQoiRwSpCJfzAVBLHJfcf/Z7SDge5RTlvS
RQGPfsgK8SKtgVzmKyL0N1wMJO120mqTPJtnZt4a5pXXBSy7g5ayTIuuzHoajdHT
vMc67poYekS3kBlwqsq359R5NCzszw==
=2eeL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c70cd647-8078-4aec-ae69-37e52a8d7cb4',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ/8DGZYUMBwSEqPgorb6Eehx/ds90Ed0PLl4vTf3gA2JHnS
NXxQC0doHrqMkgOlwyHCYKfzSuvZjvhTYgS6R3Pms1yzsATayjlaNN6lwapNEtjH
beP2ZUV/g6C2aC0Dw6PJER2Q1cW+oGQ9ahuZk38OLlxeOKKHwOftEoHT9hRXTECo
OhbjmRJCMF7IIZUgrHyA2FkWnGwwvWD9pVkb6B+fqOMVA+GKZqIgbcAst5gsOMMk
4fCPMWBBtgEd8QiI1psTsenNrX3v8TgnoizDxZblnyzUvcyzArUx72Tw8vHneQWh
Smv2PrFmROrsQRZE7l6Y7YB6HHRCeSCxqx136KD3E4AJc7FBTeyZrrYfLDdKGGda
wZJqXtquolmKhNx+yexj17rpNvqEawUf/LLcVNTLQUyN7ziRFjWG/0UGcpxNoir+
NL3DtyOibdm0voc2zKjLahstWYGi7T8YR+kYBGlrq4XdDn65/v7SAKPGj7izgArq
sVsA5su2hY+cJjQhpskBcYpWr1LVoUAj6ZuXaJac4AALMG/CI9n+De+0f35uQI0l
z7iDxMNPD+RBZGnNXHUPlZj2CAhz/u+iRNVCbEYxdzyatwe4EQGd8CFSJF6y8SBw
iagXIiAFL84ZUxvgH8o+9zZs4Tsws14YxfoH3yeDqAo6xwE0Lesqp5UtGDPnajPS
RgGmh13sQKV5gjEP45BnQutiF3L7NMZ+N/t4+gIVmBtz3b3hsB6DVyps+junVvyJ
2BC4JGEETko+PiBF/aBfj8+pxBan5nk=
=1l58
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd17a4cd9-3d28-4876-af35-c17883b9a5a3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgARAArAvklNfpIsIhp/3nixSQJ1WLOi/mpJpKdUMM77qhZr+h
RJomfRqxL0sZH5awBiUDhaXVUlM+L2VWHpfemfB5zJSR1+4V3yf3JbN4lQ+Fye6u
rGN22wWG6oUaj+LjujKcaAFO46sCvheD1DjVp5FWy9JSgDA6kJ+PsasyC+wQWXDB
aa7tCpnPslLrEnv9WTkx3zTsNJgd/faOHrn14BMhMvd4UQ6HeSlcnFPBtQNMexg4
rJQ/3+gJ7LDj0qMGmZOxfJRGYU+qtojW0yvWKbVx8r09fBa+2muAXX6lmv6DzeNw
AP7/tg2SH3q2NE5c5fYXUxE/MvNf52xfe/9uiJl4ZMRpfNfCdEFGoL0QhmldTVtK
gbhyrD6k7Usiyb+M7p8/IMz9OcvCSd4SNmDPTwKWCo4Fw02D+l7BXEiNXA1H8O1C
XPnvlTmF5qHwFXmjPHZ/iLQtRLobIoIn/BHdhDFs9M28qVOPJwI1mYEKDHU3Y6NU
Iew2n6Ynnoq7WTW0emPp3uZ3D+KkyhL/3iiK9VYSrOzOAzmn+iaVL9e4o4rNIiMH
uSTWWh5kx1Wk8CR0fVhsk9GxC313XM9h6n6vNsAHYtjqCjo2kr7oOm3EU51eYk91
240Dc6rMHRQPapWxmVsgQaSY+tunQV2+w7Hj4TahtWcrNcwG5CBs+YLpIMlbG+3S
RgHyoQhMrwY6EDDrKfNM/SfAU7qZEf8i67JSU3gsjFKNhKDowccAcHhW6llZpjKF
55CYVzUAZNk3IbFcoEASkGW+7y3MmUU=
=K5/4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd6b99d6d-984c-4000-a5fd-0628163e63df',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAw0P12ReHhxtAQ/+KKegZYPGkddGe1KfINt5GcQYhJfJeEhmqAnEMNoHiv6d
ur34X7kqFH4UCNNEK8FA4oPP0yYwixDCzCtZ8KdvFwP6DtK5RBay5bfOn2sqnC9o
Js2KZZSl3ws3x1JyXSh8bmq11pIyvNz/DgRmlkiSjH9sNnxIcXi1jSBbbZQkKspt
s+39lxSMnGIs22jO2T9GTGZvlJFVnRbeipfNNIEWxO02YaVLKvDyZyJbhlgf6oF3
jjEHT4h1NktQkZ74UFzL7qea67ao8meDyd/qDQ+7zEi1kusKYvugx+86O2Poibhn
BXbOHai5ML+9wln9bMNADl5uRTRM6kgJGdDSV1fVJyOsVwbaaHvpgq/xmqnQ4NrL
SHtnYFi1B7hfvkJv1i7qH4nCrenMnJvEhpw4gR+YJKzolfUSFla96DS8dPGdVijx
0OSpHhEScTMzz9u20KMKkhpKWmwUFBXv3HuvEksbyIZQlk31OU1nhTZcKbGD/zd7
yWHbNS/hXJxNnujLKD/+DOBUda5WGZea9EQQ/bGhFwMsN3k0SQzmzGgAyATFTsEJ
QUC3tE8Iey46T6YbaRZMMrtmjG9KkENdI4k5Oi5GqXUY6vSVyC+u5xi6gDQKVi/g
oRLR9XIzdthF+7LptvzO3nLIDPAxvGo0qzNO+LAgAZRENWDc/Ra7ynS9t7S4G6TS
RwHFSX0DK2BnY0Jlw3RzV23U9ZXIe35a5XHhAJaV1jz7h4K4kDZ8h6obhb/D5Rst
xxtcwcnuKJcvx0KDqY5SNAPoumUokHyF
=a6Fu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dbdbe086-1b33-4b6e-a7fa-3bbd283195a2',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ/+KbklhaPEFyd9ncp7a2o8yuODoRLYUJeBpnpcWx/Juq0t
7Pz6u2d59chI19R/hBcJXRUbotE+lFg4mLJFH6N03/RLN4ScXpzDE37SDrfT4mbf
PGnfSBw8XUBQ3I8EKe+bhJsEludgyub2vaKvti2pe9N1BsE1z+bDg6fk8RUefgI4
f3rVmucxQkHOcli49NIqf7z9QiR4w/yqIjjRinFs7sC9+ki6cbJuPWYa0oob+0HR
o5dO/n2tz0iEkOyuUsPDcRsumljhgYc833ZjWuEpIFUaRDfgPblZn2EAn2f10TYY
pFhlXt6c3J/ML3ThwLDhMTzed8zi0lggUoWmJbPB84crOrOBWKWytnQQEpBrp0kC
FHspcyzaILnsZeOJQrDbw5TrKM6BLb5cu1e5NEWsA9s4P59Hr+PWs8+9ufUcS7bO
Xy9JI0+oZlgQCwFzme0ts+0yr5k6S0Z8mWB7irG/1sLoqkHG8bNqE9m7dBYu2HRB
fNKxIbr7dQ5+/oXMwFqwAgPgzc8Momgl5leTI+lD2XCNySV2KDAOVnaW5BUYZVZa
AAuUKyx+o2+ApEXxglriFe4nPKtuKqH3cURtsRotIsyg1grL4+gFK0Ru1cVAmeU0
Q8cTjbpLdbGdVl0hu25avOvqx2EmhOJgYDbK+/AkPSXLO4KoP+vQdRGcIQX7THnS
RwG26EzSCYhND6zcOLbqrLoO8OQryK7H3mtsrLiKrppTiZczm73yUyoho1kbUgzi
oEjA4YPFS1jmoSpLQdJsAKbuAOKMHVls
=y5Nl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dbf773a7-ac7d-41ba-a0f9-df4825d3961f',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUARAAiRJs3tlE2PU9ZSukrU+cdoyUk5tPoBG8M/qCLAWZAQb0
S7HhNv9kli+kSRCdOB4Mu6J79nJ+QvQdPUqwS9SaCUbXC4qPiRbf8dnWhLh11i9Y
XfgcM3Hw3ChOamE9fpwc4V/+Y3ZWikVr7MTa0yzMMsk/3yAHYye83FJEs/gmRR1A
GUcXxZ/5KrPFhpCRhj5XadFQbjNq6cOj8QOJeqbjUm/aY8z1m6e8yTtdGIEhx7si
H6HEWNplcV5R2lBYi7N7/JVbNY/PSPNh2x8IOK/mDxPhAoRelDukMu6G00aZtI0O
cib1/uxbaIezOm9kcD7NtM5L5XoeXiBEfI2ja5eZ8kD5s5xKog0UzsABj+Xs0Q7r
eyw4l481YEo4rlFXGsyzb+Fam7fn42UyiFOUaNYXIrgy2yxGbjsebcmZy2VBveGu
2BfMWW3YIQhhu7HSGoJk6qP3BWhogUhFe8SQebsLX60xY+SWw09fKWsbjEXiu+05
41UUlme5L2RykG1K1vJjFbjd89QE6MPDGcuCYdTpKOekBXJTOlFRKNlzfwXoU0oE
3Kheik8f7xPuJOKEB3lcLc/HesvSf8MyubVxgBk9VE+mMlKkcdXYn40t2b+JtIPc
gNfwcKZOZH6zW2pXAwVLc5RGTJS+5FrwI0oHuFBsmxDwNmvTCU751DFnf0PwPifS
RwGGpTNTdqEgluEEbmCDecWnMuMpznTmTGzBWgU5dBaUuiYiArVu1KHvuCnQOl6o
73ChxYl/1A1gqPutOqgT7uzzOKhCi+Ub
=x0Of
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dc97730d-cf15-4527-a03b-6c5db094a66b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigARAAoSdazQiOaGf4NYXoODvS2rmsdvQU3pR8518QJj4Sm0gg
kjA20FaESdWO/jKynTEMG9FyXneift/5VmDnsAgbFXkG7bqlJRU3i0VE7J0qPO5N
pRRLLg4n0jx+xcv+sO5Cc1/k3cBjXXhw+jWi7/TvImKHDfTN0Qt3NUu301tMzLTH
GZRDYqxDrRVqnUqqQv9Ncd5A6q/09s1bRnpBlI7bDJ2oxrthuac+PUf9wFiPempW
jc3oriUi4W55ItcFjQ2kziWbKXwgbNhuAR6g1Pc1xBgjGyjNWY/VyWTzti0iqznr
/tvg0OeHWO4Dt9vBkPTBhbL11MN117nCiisd3E32GXo+Uie0nCRgedN7m0Y6aMv3
/1P3kS0ttSVsrgNc+fPULq/g495/0PDUlfiwgZ0kbjO5qyZq+HyVSZz7mlkF/1wC
gfR5ty1vlaGYo9cxkEeBDTRQeNyzC7xX6lPGXJIj36pOYvf45oUKRSEMqxjj50tx
VHQTxrukexYF6uvxVAaBUx10iRTHwjqIxITA7uXlHSHuxCI2tvVLLNOCJnIjLAIK
lrHGirSHuM3mIpe387snfx18xmxlwsqNVZ+idAHT7eJlw1M6m7gUhyPS8ZWTkatX
KFQ78VnJtpYHMqToRCqajcUYOYGgW27dZUOSF3snk+ZTpXvqJa7+UyLW+7+FLUDS
RQHTlKZaImC8P9JagKlTLPug0OoEV+MFG9IQbnjUHDnV4lkF7wrzRbjeWXd46lJ1
qv7AVkETFq/7koVbA+6vKtHPf+55sw==
=0hNK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e83bd4b3-c79e-4aca-af85-c51c3dd81c86',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ//WeYF665ArtAJyrI4r2F63XCNznVy/Z5e6XXeFXiJzawK
BI5rK9/H7EZdkHL7otgStYXuK0D5B5DdHgvSygHpVlyrOrv/ZNaH/vaSdN3VE9ee
h/cbhnm/396Gts7A2KlvctuXfOQMjr2+okHiDv+59Y+Tpi+ME/esorFI+Ith6fgZ
7fG8XmvWP2cvV/TJs1ZCt115PZ46lMGwkl+5foJEE0v0whjYXeQgndWNVtmotVUw
cTLyz1FCeoCnOic+qcwCuVyiq65MaCKIrWsGAIpfUE2V6m/OLk582QskPLqoA4HS
an9kNSkgrn/MU9gld6A6NMfHTEJtfx8kDfnIEQMU9amYVIllFeSrQcM30oxIm2xR
JHzziyyjwCp/qspSTg5ikIKibKbg47kIBsUvavBYzKVPKvuTlSwKcCseKhuGvjKc
Hu0lYRg6bFONs6ZV4zNeFmkp6kulFwUXyvHUS3CcKZxfmNzVR3NiEtthVSPqbdDH
ilO9xTD2ijWEibB/y0JYzyZQbJccwxYcKNGjScVrdFt2alh+tT8V9pt/ewAk5UFv
jBW8ikDyBfZgm6SpNu0spFoUln8JUzLyGPvKrXeRz4LG1IL8zZ736LXKmC2Q8WxY
BppPUGYZIvrzqNfBmzW0ANsS7G+qCCJkyTPL2+G2sxIJZ6yoOwGBsuku8xA04N7S
RwFNQf0SbCSfkwO4oq66fTxLvC/OzNK1s0SD+s/HHFn8DdLaJSasNKNS/Mi+GIbi
b9Dv1dOhlfYDKUAfTNxeCxYi1sMw5W3o
=6GXH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ebe96643-1f6b-43fe-a1ce-02d78b4d4ca6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/+JSvVNgO0L6eWIVRxhxL++r69UBJE5csG4+QzXzlUMugP
sthC6ByuJVKZwsew4t94J6u6t7/VWuzTa2Tc9kpYf6CoTTME8SJwFo9oyEE5oQyU
93yVlJo/TJ9CnCIZnk3vbdmnXvhKl5J0eHspaRhoz2izTRxlotrrxLtE0rmYxMlv
27UhETkapGp+xSekye+lrB9zkcrgLeVuR/p1765TwkoOVtn+IG3a77RarLV+Js/7
Z8duB6z2P8Egq6rcdcE36CUxxqvh+6HUX1E8amd+jebNX1apsFgiAmQUSybiuDom
N722IB/R9XQs5Z/WOJBwerbffir0o3t6W220g/k8SBYl0mfKLYH/QKgX70Gi89dE
7vv7jigGmDCVayVgu4qDUS8HfHWKZct0qXTItL3AkrZLnCYs6y4TjKb2CHWwFogd
weax1WClUAXAIf58dgTRx5uYkoDiYZLUF0Onc4jzFH+RRuuY3u2pxYLNVRuqV9tU
DsV8CxbJ6YfEwtZ7EyW2CQT8i7NKRJQ7LlyxBSvnsfEn5G4ZLBzp6ukotxP0ZlYh
vjrCgooEMq1syf4YjWKjItGsM6Bo0TRs23zsuXBXNFnVhCupSBAgl4iDcZceaIRP
fPbKhJblKdKcpM/3yRIFW99+2Gv+dgUdG7i6BBYyS65LGOI5pGgqVkk7i1KbxGbS
RQGP6u8gTYGUgSnBzMemJ4ZEJCzZs0YCuJ0P+ap23Xruabxj2+FCNf6OOZdWdoCg
6lt1i9fx5eGLbmTgELrjgTA7actduA==
=P2Hz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f964666d-7374-4dfa-a971-66b4c9bafe1c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/8D8GUaW+xaGKpaLbFhhaC6inFoGo+0B/vYejrVGZKRWeW
Z9R5xAwuHiFzfJ4jeRDNS6vqXdBxoLnt1gjUxfPNvwELKlhQi58r1NkT8SAAK7Z9
dLnGTBAppc9jvFFLROSNoajidVg5Rys+07Esv+xf/IHiFiq+UiZHrwqIc+MiuT7J
rPjclRfMk/r01fwLU2wBSYm4dZow/MuBAA31oybmCxkU4E56792beBLEGhsAdC++
Lx8vtJ8K8G0QRj6YpQichaTbrs4OLus2kp0/jXPN74RO/FcQfIk5cXyFHUfCU0Me
dWZEGhoLhFI+Z6dUnUvO9l498+CJZRf0itvF/4hrS3zLQXVY3an8tYybBFNPGXHe
gBiFLsi8Wgv8LuK1lTe3RxsqEr7/FcqLWYihH3iLlVeHVcisMrV0aB2aBynHN4sr
DUQlRNhp0q8GvhZcsFn2+zoHfQiAjANhVqhSwHYjHydISygn7LcQr9+igJdDzhe8
WaHisoZu9MUzhmPN0aQTLY6wUtKJbNZleU70f5JSSg1gf5rw9S1wheRTTCDcLxiQ
4w0Up26xNLXl4sQNsGsT67quq77UyYrnR2xrfzmzWmE6SmmlZsBsOs3HVk98vLi+
XOgrJG1dKVQtYPDOK0M7ukhfMuRwn+eqaYWAGHrTEgOvTjkdz8XzjcmZ2AJou9/S
RQG9HTtPBFVsB+p1EMmd8SEYJ6IkDKeNUMq965RLVdRYI+kgf3QOQl67uVlxPXWu
yACoq7AvhDlXBWdGYj7W9jWiE3W28w==
=u9Ix
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd8e648e-400a-4757-af7c-25798ef0cf47',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/+KJ9mLDlALACROZJJsghPYOJPavhXLVgH4sZm/H9ZLtGB
3OzbJ+6jE3nk872vqimxbI8djN+9RjO7pTHjZb0D9ZnTv0ChmSUBiKbcw6n7Tdsc
5M8nBn+m75cpDxMQtZalthJWRnKTNHruyI1/0DugDpnk3Xl8tmEWvnc+vhmGXAN/
Gs0xon4g8EB36ErH3WH1lCrQhrEtFSSx/tOxV2KnhQjZViVlpAV09J+oeLcVZTMl
yumUH3VsYwwXpgfOZBEKWObuFhf3N79EMzruH3u77jJowCs04wC8rgwanDNmNBFO
leWiLFtC0nhqdMp5HSYIcbYUDbBrT6JTkUwkX6zHJzU4Uyint+i2QITfyjMkotDt
OKKZTDiSWGPlHQfnvla3NlwWtDoN6BU7DQDxpV3UldpaUMoBdFvAE73OOaY0DbVF
oYiqEviexKMSs1Ablhr6oFMrCVaAgjr44mqj2LZewCWzT39JxF8wQj0e8rbS/Ffz
CT94Y6z7KZosyHvpH5mku82Zmkp7eU9ZrIjW+w/wWPfamT+bj7qhrsJXqFuQJeG6
Z4c3UnNB7TZCtVkfMQU1Pt8TiWRxJ6njx6yPBNNN14eMV5dFB9HluKQmko8StxR6
lbGArm2qINQEDqG4RPrPk0Ja9pSUB4mfzBq9icFoR0WakpnrMkddgjjgJuTY3h7S
RQHRYsthOaTk/dxk+Vlq85ovRq1saRfWuyRVkVUxk0mALdqvfB2IwrTrjls1TX/R
Mx4q3kKnaEJRolgW8HP3LFUjgBlt0g==
=r1yz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fdf19fff-4abd-4c62-a665-0d58fe6536f5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/9EgheJXS07Cq5WA/exax6iwTjEURPilPcwD6aSpA+va+H
rkOCuvVon8dlK3QU240J87yRPOqNd9amNSyJxNERyNOHljcwfvFapOXPMWiAzuNS
xK02mzeOj2Mm0+awjlOVlnFAeOmHvIe0z27mrWswwfrOdR144uC+T6LJtimo2Vlo
WfPlP2qF9WgcXQXmhunPPWawu1mVcKav1Fu0zWp07214Nq7IkNr7Dgl738eetSbZ
gn9W5qHfGj0qBEcz7i5z6DEVRH5Mn70ZAuCBnmwQjtmNEDUcCVRe9FpATpiGqyi+
3q+vvnBIi0i8gsKEz2Fk5vVcnXwRQyzOVuZxdluBAmNpRMYzSLjtPUcfpLxqfM2/
M5Y28PpiOFtvdLJBnXuLcpHBzbdmHNazpqunsdRGDicg2iHde+rqJFFH8bmsYQfT
oVoHJ2tGeE7zAOZnYYMFsrHN1EoT90DOJH9Kop1C7cgm5fVarKzWi6qVaiAZFtlu
0VmK3xfCdW/8BxwU/2npbuFwVVfs6uxKxnU2/Q0Pgy7n/Kev6sJszL9ztZm3b0Tz
C2/Bk2zD/cDH/r7mOar2EqT8NXl3xatOxx+C2zJT8zsWxVYSmEwP9TbeWw/nVOhM
+n631IEC5KHZFdZ+3caJCtlqE9ZyyHxqMcqyZEHSaVK7od/8mmA5UyvVG+GUjXHS
RQF1eTdwCUNK+9/CCAXlsmcqmDvyv1oQ1SrCiokbu3NWYQ2dyJ0CY7x9dPa3OLcH
6Dh+O+3d1ZsjnS8dzMPf/xKVP3ySSQ==
=go5j
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
