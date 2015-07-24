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
			'id' => '55966b46-04e8-4397-8aa0-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//QuM8uUZxoite4t6vZXOnbYklpuOBsaK4O6STrdXoPV3q
spui/4R9r8sgACwEWX1UpqUxppaN2vUQCPaF+4V1DahxJZKB9DbJvfpkRusm7Oaj
JcBdSyHlPmcyuivYP1ybGtKsydpd1c0nu6J4VV5qapYiBWmiDtViQ9pfmWnYJ+nd
axmNd0/l/OX+gplXRNDElKrRSzoZIHyY3KHnNOMb0WKb1Dq3LPNOVqlYhpkHpBLh
nj7ciM5M24Ur6qFFK9rGTn6LYN4FDUBQkwWI73348VSnki+GMq3Yb+GqETqHeLR9
8k2NS+20xyFIxyoeMFIknlVMlTOLztvMCGWaHEb8Lxt/xQmDhtasGTNmaAg648DZ
ESR0+p6eVTsWlr/gLTisZsIOj075/Ro2DEGF4vsbltlP/LbokJ11fjqDID6qL8hN
msRr+KZQT4DNMcg2Bhi6UBHubnJwAi4kNEORGex8mThUWWsotYXFzS+FqxdW29+f
LGuzg9xiuwoQTdZ7XQNnhXGo0ecl5AuXpszPCK72rNvvBRpo34tNveHeNuVB0L1Q
rfzSOHJK6+KMMiwhvbRnfRF4QCnPb4aakRNlsuRhG6nsalDqYdIHwROYhD9T6XQN
if+L5aGF6EoBYs0Z6xFGCv2aKyWYoeM6AKwfvCpJDJCFIBP474DV9ikJZCsG6tLS
QQGIIbERliB3koAxPfFMqR78MnH2/ENUfjDoZWS0RrZMzp8pyNPYRrbxeVSjbFN9
ubSzzNI/jH/bXbDsn/PNgZue
=Ojgz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-0de4-46b0-ad0a-19b0dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA+p38wQEIh7oAQ/+PXx2t8taE939X95umiluAyCoPkT4pR6y3fPZ9CtnyjPV
QdDcSbk+vfIVRrLZSkwezvtblB0jnEAPAcxK5srMTOuM3qT2NgKTy7RXkD8hebQh
qPRqTDXDsJrz4EvYXuo6TBLFNdZzeskAxLIwN9dvWgEd9Oo6S5uBUUYDztmpXrHP
SLMyocZjFLFSBeL9s7TVo4EzFMt2z53AzO7EffulIbWew2b7nYzU/R9k2eKWjSGS
8n1D1FWSwlNxrADoC0klxsthokpCO4Vt9m8omcPmDAyIWrppQczG68wRVC/xVNaH
9RRWDnIjRYxjaaVwCxerGyxp2ibkgbSRXCMM/eSqgw2+NFWHx+6iTB1WANSL6ZrA
kjHZ4rS9+D4y0R7u7QvTUWyw8taYUz42bYK5qqMYsf5+chlqXHBO1Miwn5uWhYsL
t6I1wILYD0L4l2aFHwyIgwmF+Lo8yRI74VImb7oXu1Sn8Lh2zl1W6oQr+Hvm67D0
M+X0r5mCo+/zHK363hfGKyJqQfi5nObeil+0oeTjgLGAxuEXIXC3Z3uOUudDtoee
ki/eoSR0RObU7gCZ98zLa1KE9eDENq+R7uWhFHkfgJulOiM33VDcZ5RaMQMeJF2U
0AB8uZPQtc6BAwFW4PP2cqYhzRzOUc7jn/yf8DqTGk780bpGUGb6hcVCmv/AaNLS
QQHiYTguhulSsl7CrxZtXeucVLUBhemnb7wZ0lgtOLVOkC8HnJGVjuxUXDOjmMwF
/WZALGJkohB2lySIsdNuc9Mx
=kIVr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-2170-434f-9d10-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAqNukyDv27ynRM+Fn0mesXq1ZYccPosESq+KLdsDflZS5
DWECpODFpSSf6/P+tAhTP+9ab6JQL0YbEdbmHht78CoixTgcTqO/VLoEoKqluU2O
4BZ15Nf/H4KUWbE8GbgiDIgax+3YwcaZ9pQy8gqQ1KndAf1yR1kOiNsbBe682IOV
2oxgDaunKR1+rq2YhnmZFzRDwKwX0U/b1W0X+mg4eBd6J7uGTQfg7H3W8SgLwFo2
WCDnLa3nVAWHRWFqqUwJXuThH0YeHy8MzIaUU6N0nNhLUy84rAVETteLWkySLN8t
2Hmou1RhfkE4IzkPcmy7g4hRvYs8famz8Jwrhci4utJBAVCH+fOMGhmMT/IaKLHH
vpzDsNcfNJyX+4kQdP+QA8BnUqKIFUjk4Ipz1ge+K7FV1d8EQSlmCRypu2+dmn1b
xR4=
=0eh0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-258c-44ad-b9f3-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//VkNsrDtURwnKMsoeEabMqFfVO4fgbt36O9cPJLGdBa3y
nA+4AH0rvSC1O1PGq+yuKpgVMnsK5ZbS1D3W1VRqQo09e4G0wRQNA/3rc+qR0SjJ
t5iBlgT4dxZ45BsBud4PISqpViPNs7Ojtfaeu9SkzzvrUhsVvcZOlfrDp/gYb2cw
SlfyTr/KyQUYMtjYQ1D/jLYaawR/HRnKMwdfaeDcMqFshV4keReRvDKoiJHh/x1f
rzoZehsvbcYA05xTgM/bnFFRTfH+fteW/OEq49ri0hJaflymgZ/H5dgcqq0O/vg6
jLDrV+8bvuFmJS8AcPqLpBMnFGQGom82NHXf5wsYfuYlzweeVE2c0d/i/2128reJ
OZT1YObStPELpBLJGnD2sbzEKdSx077yMAzOrDuiK9RqeVSZQn+8nAAkXmQTe77X
MaEJi43oVqsAzZlmtnPMsD0Iu+SqLWSGgAtoqhKWTywR0/lEH+WUD9sQCc9NNlg1
3efyr+zTuBR323t9KEB0QJIPUHIyBi1ohRfqiHWpG9NPBpMd6cbfj7DMbI5540uX
wBlS78h9RXJzOXpMLZubqqQAkKz3fUwXz8F280SFJxDNUhfX/yWVRuV8z/8327OB
aFhZtWXFgBM9DvD2G1RV7fHQjEHMiqNDSEdwSq+p07CX+Zkt2gnuQgzhY01wxZXS
RAFtgfXRsml/0E2Xq5YbGLBhJpU6jhzWIeKJPlIEd2KbTk3i0mpSIUY4h/YMyADY
RXXiFJ9dwm59Kj0EwOtLaC2Z4qMy
=twIl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-5418-4314-b5a5-19b0dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ/8DcrMoodhd9DtjJY8Av0C7FQXrWDlaDzuRfO39k2yGlkH
zyP6GzFAVfNefIQK/cAUCzTtXJmW4HAUUKCXn4M3BgGnNfDm5mB3M3nXeA8LIB+a
ZY5t4g74aZGxbE7BBWimfY2FXxZ+TRumMbyvdFuEn0D/GhGCnjewgFDzmW1v7pd2
uYY37ltcWx/+snFzQP2VNIP7bL68vrAO2AvnFGgZfq2AupMRuim64TG5piU4y4bd
Fdx4rUQx69D3cy59/SXpKos1SKcIDPoKfcnqASwDkDMQY85jAL4wTNu4G3OrSMdR
phlUTBhpNnKQWfpLrsQl0ArzGyHS5fk/Y1TUE8MNCXuub08fOnmyupwErR5HPnZK
4dguvQnTOjMDw0JXzY36XnhWBOp/mSH3wgauW8vF9nqLFemMcKghv0ArgOGjG8tu
IL/GwK4f3PRNv4S/3HOiQV60/8K/AkCMUAsu97B5Tu4Y4Kp/ieXL4bibt4t6/cnQ
BiChnY7SMjgV4SYKBBBn+MdlLKuaBYCtP/JlUlZZioKE6XtcO3nZJmP2slQd2XM7
W67jgMpt6EM5dOvNi1GP4OGg4FESSweEFWMSSP8ghO2ip/DdAUgCNxb/FHRvpYWZ
RlLNMx6EdRq0Pf2xBAT0ONBmlJ8hOGszMwOvJxgLqggB0esPFPivjb40zBy/SDvS
SAGtVqPj9obOrPJ+JF4UTjKQLGy/3rFqcijAu1oVMj4+bfrKCDF/O0P0v4PfntbI
o+LhhhrboBH5glY8xGpz+Wbq0M240/wNlQ==
=D9T5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-5894-431b-89f5-19b0dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ//cgBjSufrik9xUz6ntR+XCCGV0JOn56HjpBvuQLPbZzxy
ju34wfiovyflJ9SV0JUl+lGUFR38EPJ5y84ObcpdkmGhzHE+PeXAiZc4ISJsipBT
Gu6Rt1T2GLglYsLoOl0Hs2LWnv4k5aJGg0XUIE7bJ6K4uluDGXfBQeORC/8JFFxZ
3GZH4VTaVT3BaxfqjiFiUGqy6njHj32rdIIGXLb6hdkXP0Xdj6MCBwfbRW1am79k
FPaoe6fHjVeEyKxhQUTzNf25a45y91/+/LKia7aDz8A8rd1Z5YnnCcBb2ZGZCa0B
10rfVoN4d2v4w3RwtPuKBgJTvaOZFN9cmoF1CFo7QiDcEI8t+mSDjzHavb2bvhr6
HPWt7tXpbBfmZznEjXg1qui0UXUl1gQ4c733I8Erbv13tKWvm0ocFvs5tlfLMAw8
K4f92NwNnkpYGpiaxzXpXGQFbV4FIQnpSgbnFP7DH82TSvlQxiMOOBSv8zfrLriJ
QMbnjQmnRC5etPuaGo8RGV4tCNwP+Q6NH3L5joIMBzbUZ/vasord2X4EAbJYcM2N
xeeSeQjHq+4K3z0kx1cdYd0EayuD3taaerKCZp2vPMrMwnzNmCHe8C4HWF5YEZry
D2X0n3LhCOSP+8tULv9X72o2W1+W2jXAHDeHPn1k/Eok2dLwB0yXS7EwzyiH83TS
RwFTmC712GuIS+rHYDwg/f3ugMbvd+L22yorSLvEXSE8VF7zjAtu6h/23GfwhlF/
uHHsYBMCRvL3PabLUv/QIl25cNI4IaT5
=YRIB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-69e4-405c-8518-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/8D15N2VomEF6AvbXsnF0vfnOFzIPqiIMy9pTebh54j2nU
rlBxFaPF0OM6DkohHhC7vrucUh8rMLp9cNxrI+FVY+1uZWMZXY8xwT1XkdQAlt2g
IcxX654MZ5HMZWIrwkFJxWjck03DGGNpEhtCdmJh16GTNo/gFoCRtrUNCRWFWVXW
DtNWwZCvmCqCUoQpysZw7tQghkwEGdqhoF1y4XGHz/JrhCvEMwqLHuE7nxOeF9Il
nWs2NaysDdqqlZDA5OG4MlgaJSpvlI9RsbBU8J0PTopE0atZJ8UN/ADShUTXhcZ4
4YngmmAUHqHzzytHrhbGopJvSyZPV5j/I7dW/68eq8zy9UbPXLTOV1JIJgt7Kwje
jVqpxL5WLbVM+jlgipeUB4xBWFVyO5+aMeZ1iBZXKMXjQ5MvmTWeTsuHs3S0hGAg
dTNDpVPJmbltQuhQaDMC41++Q2cIp25vU+xaTOiFxFtpcDtKOAGCOqzuh1W1TolH
fODLZJN/vbe8JfheIZXkpBonWAjb+JWMbRS+9vNWw0/lW1cmCJmT7WETWXz6LeDx
wFEHFLWHeFAotSUNavCpNPcg5LlmhaNQvZOeGsQKRRTe3DKFaiTlRuD+hBnHz8HU
uAQ6SJxBHZm4PWOSszg1VT9F48nvO7HG3e52pAi01eueUIxqCrKfKvMXYluuOMnS
SAGrRrFhPojOFaO3LRLlCITuRub70QE+rKb9G0SqC+f2ZBrL4B+MzDbY05nPeFNC
H89PBR2oZ4nl2oXBXTBwN5LDSsNngOpodg==
=ZGDh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-6ee0-4c25-b249-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+LFyG3m7RCzsc6R2qikH9F51kWprQ9Ru/S7erisDfGaPj
/aD5Wr/nATeYc8pxh2eRdrJ+IMUUCAi4G0L5iGPGgOlZy8iPQ1YznZj7BAHYtAMM
BDE20cHGNB9tklJ5+ZSbtPUSa4yTwiQshBKMLGdnF7tmC0XaoaLw/LZlHV/Bmc1o
hb59Ew0+rzTRD9PxqTzqjG9SSHCNqK3pKaV8V8vVakFV0FHEMZVlYr83y2PyGW8i
b54VlityRjrqCDrBWwfT7DBSXY5OuZDKhkeOxmKxMiyNwlj6cSMLxBVgkp6XkMDr
pGEbUcSdqohBvHbKtsuNXYNXVJAWQDd02cYPQJUr1NJFAbStcSuZFTI3n1wfV30r
318FZ5TMlsztzvegTJ8JFtNYMFd6cD6sUvutx+vKojPMpBcAAdjlq8Jl5kv5ZGXf
onO3g7xN
=xGuI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-7b58-4075-9f64-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//dfwwZlXTIWhin1Vc0nJbIRuW/rnLCUF+hv8OXmBRGlrD
Blpd06aEx72e6jYzk7IWmpmkgAfeazfddK5KeKQqbsIJcPGIwHHXvfWr5yicwQwz
avF8ig6vjTnbobilHr6Y4DApZDFEu5xq4tezZHGeoUOFM01vXUes2Uws8R+a7YRR
19mgv06JVF7veIblPQEOxXg0TmvZ1oTpxXQWe8jCBLhm5ZwKsLxkeTwIuLvDdG+6
8qeJnpY8O+9hVNySeHBZpja7uA7LFZ3IIlJglEFEfPQgvRX2KCF5X8hNYo/4f5IO
vgku+Zm/JelCKLX3mf1IABX8vnnDGp/Nen36iVxtN7p7eCqlcdekUXcGOZ4ExZso
8186wRxYa5U43Bqf6HC7S/srb3UoPPbdu1pZDSPcFRYizpMiJ8McrSY51wzkpw2Z
2tP2pKmn1oUtFajhQWIINYWmB+7E5nKujCHyLsSWz7Pd67NDvgnj9XzuhGph9U8y
cn/eoILUyb0DwaOYRqS9xN1DkKZm6pqN+rmLlCnmnBwmQu4jbrKvzGHKvArE5Wlk
zX0yB3XFKnGRE/Imfgy2D0oze5nqaGuzcGkf9qtQR9X58zgCBlhs5zKHeICU73H+
66deaE0Xwv2I+zNUc7J54TaE2VqvtPUyq+apWKEkunJ/OGDipZUyo2sQGn7z1lnS
RwGViGGqatvPWBRKjAumgOdXUCwbAcqrJ+gOhhlfszNzt1LV121iNYysIJxOphCi
aXHqUZTtG0++pqMghDp2QFj62Iwvvmsh
=Vchy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-80b8-41c4-a207-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/Qba2m+AeIgo/PdyGEYO72C+XvS3yalkzRXQqM94S7IBx
sJIHhlEmZB/lFrB275WNHETSlLZ7n2AtXxxhoC4t8qrJg9VJ/duOJDvVF5FHqeiz
vuBckSJl6ZsYd8OSpGNtIURWt2bGMZeP8u6mdzYcOammVC3sHgti/8f9a88NEfNH
pKC97tulCAafjS8ppE8Abrj2xmJHgniliYQSYumkRB32yVN4N30CC6i1e07aSabV
me9KYgwMa3T5/LOaADKvqrmVU/O8v/fcqi3DABh5vd0/PtuZDLPfTd/6tyrznz+Q
Ui3Vscdl6KWXZDFGdID0powji6BJdcFjQ7Wgjtn0jNJHAeEoY0BbhRUE2+a/uF2U
HPxtvOxNJKxG8XF4+vuBU5HmqG1YklvmQNe6YszCJ1nKy11QP03faVTYm5HtGCEC
3+9dgOLk/eI=
=Gx2L
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-86b0-4a58-a712-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/Uf4GTIQ0Y6QlF3YV0WwWX3VOVlsKTDzC5Q2+ENhc9w4c
tllNOhFff2nHVq/kkZRGX/IoiDhHyCYKFJlKrDirlh9LXPLqa3IhSj6oXmieTYmc
AsESjtKArN+qfE5UtmwQtHRLb4OZ/Wy+Wsm0gQsv+Lt8s1ijDoXUCHK8BSjgNwSw
5jVdFC4zTMIg33eaeKChSKH1YT5f7OpgPa+xqPeAB6I5IqfuvAULLCW2BEl7a00J
lD9LLmvfcontqRjdkiALKnlwg/VEHHr9aQJ4zEZXhz3nUzoUnL98OpdBhb6BhEmj
H1Ybx5hDJd0GIcXWBlZKbXqqDOAtG0nmQMvhqBkkr9JGAd63ZnPYspw1JjXkcQHA
F39UqKzY3SvSm+WU8vgoaTcJpOytsvLhiwfeFzkzYE8IFFXQLAh27/cVgec9hDo8
PfgESpW9dQ==
=OvnD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-8e80-4836-935e-19b0dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf+OFNQHvh9jXHSWqr9WZwtcJAIL3LOSKRF0+iZAFZS39Hx
4gnAvfgMNvfcHsGxkS9GHQeBuHqPaYX4q5WWbsBGAXvwjOALbP3eviVLewnPBWVM
6+hb0hKJTax0cKj/wQ9W0XjtfmrRhG9xhGHy+o9k26ZXStaiipIH2O47YNRI/tRk
W0UM6UZBULoDaH2R5fKAqUBLspUvNew03c0Q4I70QnxZf7S6tGwefrRQDeWe0tyd
XBOlh1J/AXAQ7rSuMOk7qy6MfnS9yZeCbD8rKQjx+sl60Lr/kI7BIRcFwZUgwez7
gSJd2cIrE0f01SPhpCm6j+hXSOVcJ14Py+OfDRF/+9JGATT7HiaaLwFzWdrlPXFF
TUEQHcRF4jFqb5eHtoVOf166do4zdIieSe02S9CsPx4Jb6NMzyaxy4lG+dhf1wyg
8vkzV3uPDg==
=Dphc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-9d74-4882-a6c0-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/+Mi6fWd7smaZctlBkjV4XRAD1iQD0WnneyYMonrjRi8tp
SJGcJa+eD0h0EQSc2U0pa7DRNtJlmCuxAeuk4WC7VHF5aaHhL0wBKcL83W0I4Cq9
vsKC3mKHxY/DT8aOlgqc6vJgSbzFcnJ7w+9ISfSjbw9ozc0LF8f24gf00UP9kOKF
Dr9M4+oWsqiPH8ubXdHmS7lqfjnJoJu8KelawLQzXeAu/szvJh5qhP5LYJWlW9Xi
4byT+Dc+hOVlSU2lSXptNjBvid5OcFPzlTaxkKaFRvmiwNngHR+fmO3bVVQQqrZL
V33rKljZXkFzS9eyMSqPK23hw4kxW+LHEhW2J3ZvM83TcO1TWaeQObfA+RZvw4rN
CmJiDCZcA5YSGO8PiaG2pk1NZAr+FivqtjPOOfoTi/KSs0S/rlLe+tTDJeD0B+oi
FttQc+gNBMG76Zup5Zk8jCjB4AiIrQZyAw0bXy5t2ConmKysPki/Ux3FOJnyZaQK
wVOtDr3T/eC/45Op2lDMewaT7by72E0Xf8Y2eIF3IXWGRLpKXFKQsWStTxmPS6V/
36V193eb+sm3me68iTgUlFEdlreoGxpPxaYeyRppFZP6ONS6bpmGZNYd0GO6kDJY
YCAdx/ePRkV9huOWY3QUL27PpqwYd9QhuJJX02kJLEG+8AEBKj3q5FwhsoJP5rLS
RQFrSmQl+Sg1CXgDBws5mcfYSG+j1aFfqyiZde1+S+wKELyXjT2c5V1lUQxBy0eZ
ZyArKPa93uAn1pT5nXcCUqj4Xo0YnA==
=UoJL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-b874-4286-9c62-19b0dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA+p38wQEIh7oAQ//YZqz+GFg25MFDU/qd9pV9ycbvIXlBir9uetOf/+xqcNm
rbBXJm25QOvDn1DGmH9W2k9aWMcWNoEwpALcsF7FvwG64xXU3hmqwiYjQ0gUOUNm
yhaN7WT2YdPUdqvxVyn6T5W8NVOj1LFgL6LLovCjQSBm2wdCxewG/midmyLosL5t
xR/3KcMHMpXqFS/VvME/X7hqNQ1bwy8Fon1H2sED7f04soUf1C08HA2NlEu4J0tu
5a28/iHs/zuMW4OB9GN/UhtrWYmDgImhf26EREVL2jem810lF/bmfuKxSC43e2TG
nQ7MJC7CiBi2q2g33cCONaHvmA3JLbPOPT8qHItQfLVpDACz9nPA6cs7I9vbBx2K
KiVkxIqMUkyKXNUT0lB9657S8GustrJN0KyfMOveai+iDnEP4pBwR0WMYP4CrXyJ
QITFir/JJ0FwZDFFnPUq5d20JpfL9PDQIOAJ6hOev/KJhYacE/Bk3ntebur8utFx
NtO7xyqQxXJTvHPruQ4glE3F0IOIEgHKI1hv3PG3ITcZbmy6TgJI7Jd6Tb40WCMZ
kaQ/m2inSIVTJCRW4p3p9slhhRDhpnKCGNICfrAgWXNXlniq+pq5r3XEI+Qbklo1
Oj8P+i3UJ2OClC9hEZUaiLALt5UCN11z9NjI6Yq0lqEf2oGfbFVUTsgDMYhLhT3S
RwGS+YNGNOwfF+R2viftLE4+0aF0BlYtVJNqgbcXRpLUTNcOBiV60Hc/GIYLgZq3
q1UmuRUvoSJB49Sf4XcINkmcyXhpWC9j
=I9w6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-ce90-4f09-88b3-19b0dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA+p38wQEIh7oAQ//ZQ2w2bNCYaOK1QQHhshwAleXJkw0drQfkCWnEusBAjrH
pL532K++dqMgCYhWiP06/BYZnJYK2j4nbJvVsKNxCxm6FBC8Ke0/I6pYJmPu12jI
ly5KMzlbBwMbI/+V9CDqrMo1uVi4ahkqQ9YiK096ZCB8MCIFpOv+8l1ErdKYDX2p
z3vh0BDygFlbE+/U5DACel/xF+NDi7nLeCZm3nQRPjnWeBe0W72iwEYshuHC0SLg
j5hOe90G4jVHwYH6/BV7pSh+mIySh7/gFR3DtVvF7+dcDY4M7a4FgtIz880e8O05
TM7sM1sGh8/iuSQdSITv1mEzIqaB62NqfY9tHsxrP56QXX2yD9iqRTqPYZc/+P7G
6BAHibDqS/IJOXhn9/hPW0v5mdy4jW3c/FDW4Un38Lzw5JCJB8L2xYadLA338yOA
zowEaV5Ufl020RkZIAloXyaqT7vGFHya7LFGJTBghFiv4bMr15MocbpvnMsxcegl
v/SQiGKMhtmtwuuWbPR5IXm37Wwe7QChdCygUwqfQSWmJ/E4pyfjLyayBo5VRB/v
rz2WNSrYqrm6qtTKzcdIVp7Dwu+yt0eKZJs3LgKizd1ODf2QthTSGoHxo2CdgnX3
riPl6omlw8fNolh7DIba0JKRLyveku6nTJqBlDJDUYYjqizunaZVx+JbP4gJPFLS
SAGYqLiARROR707quu8fjM4ZSBHLnxrjJ53p7H7l661uOfLKJVEwgWsitzTsUAqd
OkJZt92SkT2OAXwfVJenXBAlXp2ll6LY+g==
=1D7A
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-d334-4e34-a000-19b0dbeb2d5e',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '408bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAzlz3zlJcBT3AQ/+IJp6dobWRdcRon2GvwjEj5x3IAgrzjmFNf2eH7th7XzN
Tvvv4Yx2vGek5whwEQufVM8xGnXRv2TxYIWKnLtRZpHCvCCa5cDU9unOuc7MHQyi
awOzSplERpOsUMDZXFdqmceEg/+DCvFvCTqSv+pP1Q7zGNtBE4VpDh4VbCbOX0AT
Qoy+nKRFBdTsA9K3mbfZWsxk/0e/eiTBI+1TSuAKyZ+rMYMDjJZ9KcEUjMjDmxYu
/7HF1UFIXBSAihCnC5EoKje6EKN7zSXmDAe9RyD56Y7VtpLViL9f0Vc54YhWX2ec
BlHI0Ty5GahPv1MQuCVRoRZa7bzBa4Yg9TKD0zqdD0ewgfQlgCrxi7EfKAmoMVT2
uQoMVdGsotShUPVq/7HBNzZs4U4kOJoCw8q6paBnx9lw08Yo3FACaFkWvVZ7tsCH
q5xO2OXQcGqEbRx0YrPUCLaCbT5Nly5AuvNT/+Mp/PCbumGlbLLmTiHJesRvUMAC
Jvch06Z+8gna17xVoJj133qqPYpHHHfgXBx+lgd2PRsVsVl1rREvTJik/f9zObue
dYD+/ddnydWINSJ1gVQcpXvs26MnOWv9r/2OBtBDO8cped4ku9i+aMUZ+xO/jfmh
ebXLiPXN5prl/XSrgRW9ESi850mC9fqN3yXYTrluHGm4wwmHcu/T7owIqCMkDiPS
RQEoirylTP5BRc85cZqPdkAr3q5H3fROCBR/sTS/QjUCMv9+TbaHwX22+H95SRO3
W4JjWDiND4QtMMft4C8uc0dpkXPtNA==
=9uL1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-d494-495e-9413-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+JF+6/+NrhAe8e/Cfo2MukbmmmGi4TrlAcn/yJIDAEq98
Vx7AtfNCqNgtSGRmPwfZqbJrKgihbP+y9MOCTHWgVtFsXgeXExUD0elLBxgARnnP
gsAvPNsbw9Ta/YkOD/zmowBY54k0P/4Tdhx/xqaxqmPJ5ejcYUpteiMYlzTsfggj
XVTEe4kl9vF2FGIeMHlCTSz+AUBAPUURfD3VDbcZD819/Sgl2pJxSjZR9UMv4ufY
GhBJguhrp/HiO+H7Ub6T+YwKsCP6t4uNswjUNKDf81zqv8MEtuGW9hMrVlPZj9EG
t9/HS7bcY5YRc5ic5M8uMA9I9WTrJ5zbCltGHIB379JIAYvXDCIey9IQTZW6gz/L
7BnkwJ9A8q3dXzhNBfwDV9IG0jftzgir8aAuAqUDelGU0MVGzvNecDQf6zdK8IAq
+0nCZw09fyhE
=H8Dm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-d508-4a5f-bc55-19b0dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4e/DeCIHsAzAQ//RMYqQAFnNRb6fUN1rSI1OiZnJCeZDf0QnpvFtBjoXIfb
IfkFOORQtjfywoLMH0IoVW6XCfn785FFHvaHBf0B4it2ycqNhyDEB1DIvcIeYazz
rxJPoiICOYw8ep4TUFWcwf5OeuqhYU0m/aklY2s2n7Hoyg6O36HIzxEggIAUB1Q9
A88OkqxGnvWfxK79RGjdgk3Bx2GI7LGJbTzldg3I9DmwoalpEVz8PE4xL2GePz35
7lHY/j2g7z2YUaeZSY6JSXyURmqATf61Yhpn4Tk3qp8DQAl+8npYcBqfH95SxaQ6
7ukocLVtIIqspJ6WIwTQfBezOJz/W3DhcLOFkeVjCDpYvsdsAG0gfPZaWDrIElQM
pjn7fYQtwt8wM8iBe+D/vsDbVGk3t9/b0uxKKTA4M4JDbqLmgKW0NzoNk48wLN5n
IOtzvwLMCnPc9ujAU2JoADEQJg5XQ+1vz29F3KyRZfld7iFb49WywyeGCy8l/hji
Iniaa/4sevScIAyJokk24Gf4IA+sHRY+KWwvQuTEZ4VXgOjlj+iDA7cXnYKLoDjT
Y3nZrZ7jIM0wsSAKWIJFbeFRgNNgpXA//xzxgmxrJ5dMOLQ6IhwydOFAF7tyc+cv
jpRGqpZ5ID/pCi9WPFNqBTh4GmEEVIv/HeTzdMF7Z4tfKA43CfbH4NrsA+0RfmnS
RAFIkpr7NMHIIhkiXB2FaLg7+vxEVoe2uZGb9RfjgJo8TN27LkAM/0tT+e7FMDgD
kQRz8AVSBFJ9KgkdTm/mJ27YRmcV
=qsOZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-dc78-4cbe-bf75-19b0dbeb2d5e',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA9nJydJ7HCYGARAAoceH9vuLsRlQwSnDAU++NW5niaQ6F7Y2c10td6+3A47n
lXccJDMqOTITCCH/mFc0AyxLRrFeRxXaG0y+YFGJUh2qhp8Mtn38/IKp3A1Y71Ux
Z8DaAmoS48R1PP/FkMXOlCs9LCKhNFLucMLUxSMDNVPlpKSyoNGeH3YDUif5kc6g
STCf/Xr1pUfiBUZ9AjJhuuZdVO1v0Sm9wIgFXSqS8TdeUCKKVQSYBIaUdPE6azfW
j7cdE0f5ITGvcdDSvdbzY1ae8NOB0k2UrYcUHRb7PMt0Esh9iuV2zezEXMi2bmbU
+LU3OhUp5pIeBoxjchKCx3TGthc3bLnKu4acJPoen1WQNmoWdikqqJgvP0UOLSqS
GmH7Vf9+HSkIjKmaATTgJK8SRvEVLdDuajB8Qovpwm1sla9Y4tEwWZgBj/2JywmU
ZJA4c0Hny5+RF9+REhhllqSIMlR4AF+fgp0jafsH7Wb6WDkeXu4945eU2WIMfmSP
6cSl3cVAELH6NbE+0M+XVY7BiJEiiWn6LCUIKvXaPXOIh9Ius1WG3oEPDDgnL4F0
3EWelsuq9Lc29xLo64FidePWAcPIlSDTGf1zyb4KVf7HrEpCWZqiTD2l5WuFBM8O
z7vI4qXLZOSG9mzhMIv7EkHWQWtxfuT5az3B8g2yP+ZRVWYcliLUG8vSSZ1LQI7S
QQFzTBoxfdNvvIBC/g8JmSgHDr36YXEcFqbCzCaW0kEbZy8k9yep3CodFc4qtJRW
5MjI/WgSRQMzTw8fjuGdxYzF
=e0J9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-ea0c-49e8-90d7-19b0dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ//fO1X17xtWl8fbg0Lysjos4JmZfwMzwbv1x73/S8Nkj7x
1uH4L3Q8X5LJ7iIX45ON+bhUVkNtdVX7NroMOM4Ifu77xLxu1o6hA8O/8UULFwxE
DLgdQ8GYCC+kyEoxGJWP1RTCL7CqFU6S/co1Ied20scdSID1LrJ4H4hgF4QmGkYb
FTt/j91RGnxgO1orO3nPPMgUuGPhwLtbFIJ7WBtOlb7jg8tG5IfcX8RndLOXtwky
Tzd6Yug6LX0Fftb0Er+cfuSenOQFhNrW/7EK+KXXUbt1Di5sgsce0qXbuQGR/CuY
b3IFuGDZ4iVw+niOc+k9tdlGcxhP6fRLuLbwsNOkfthzwkh3r8Yp3pZjfVzU1FUv
LckvBdSkb0KFKrUW+5pR1zjGf0dy2hu1HUpPypyuz6puMmDPFsP3heSI+Jwpony4
I5CpuW6aeeW6R4i3Df959dY2N5PCwYMxau/6hYnuAdKo0i6e8tqONmn5g6LMyxOg
nvnbBjNhSz37j1FiXPt3hbYqb5GvcKMgByedIlwShlY4KesY7b/0S1dt91MyatuP
ZCYn0vTCI3z2QpsANgHx64E+sdGShFhQtPkZB4lRIWSLTPQYdTJso1uRfMvr19KZ
7fae2gkuBYuEXEXym1QaAx9ZDnNf0ly9g25+NQUJGNsgMepZFLZHF7N24+XoXMbS
QQHfAfQerSFxldvsuj9Gz0u24LWw8wbcksm30NNRQVE2V46gOmA49K7T/e1reRyr
dNbHOD0zJaXhDB7Ia7phzPqs
=QLyw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-ef04-4098-b6c3-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/cevU7UPNC+l3bDn1HdgeA5R07YyMtP4QceSgpEm2rKXZ
k2CPaALGmaCJ1d2Z9b2EM7upPcKiZOcDA+3iPo7E686ZfEFkO2f/aKg5Nio1AhGt
mNjeebgEiUyLB1ZBu58HGTB+AybrAL9tSiBK2nkW82FiuP7Pk1EaEXie/fdQb0wy
5NTpcVWwoAYK9otIwIowKkEgXkGZ4URKPGCIsPtu2RgX0JjK37gIovt5hEOL6w2P
ypkQ1lMoLoGUNO69l1lO0giomfIPKFAwyGdLkE80HxK4E497cBZk3cvdwMWWSIBc
VHdLh3sL9dCguZnwEVbuDsTdf0pkk39dJV/gaQGf7dJEAW8gC3kHLuO+tA/N/Q8O
ioF00fGB+6it2Di7MqH8+hHDObFGGIpJmkWUkBjep7pzSik7v+P2Cm5shiQ61Ir3
jdrQeyE=
=tyll
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b46-fcd4-4f76-af34-19b0dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf+I/pJ3GM0SP9g7q3hDHdIUR1Fs7LkwY2THi+sv4bE5vbS
82TkCn/vduEm7fXZzXek2ZUKG5Qug40kY90uiaK5B1omktY3KqZDTOi1BV6fnJW9
di5uMr8WtSnpzQrGQTZw4k8k0QPJ5hMx8HfqMfWhHjON4az6VBRnzUFrYPhR2Cyl
Zs/PSD8Sj+3MnoDLhSjelXyGO8/vko6mB7JZdhVXc8HI5VEoo2tqz3SoNJM8hVlM
BQx2iJZKlc8UEqg2suP1czUPw+X6mQWD/aNa8EeULqAV9JMsehh3Q1wYeljZTJED
7SQ0iz7s2tL0EENz5kJem8yEKEvAJo3Pz9v9wGfCudJEAVz14cIGZaNehfPKaGSM
317Ua1Z0smVmsXXhm06Gnu8nvgR/XrPcVJbgsHXlm+gonayziXg5OxIjtqGoA4UI
Xyx7LZU=
=3a4q
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-091c-4ac0-a606-19b0dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf+LuGoC9Wru1C9ennWHQOaz9yNwMgvqaZYGC6DJjEXZZF2
QVw8yi9rTuvcigNR0NjPd2oerGc7lwpQf3MzQgrCHXzmiPNRHJmmlqwXkZPydS9k
Rw/W1YhThU8jPazAqF+0462QQ0m5SJWQ5r+QOfKPbYXaOMOv5k/nNqgrBjLviApQ
nQfrKGhwM3Dm++P51MHALFTOP6Ne7f6BubbayB2BeEy7u5h77K1uFsmuWG3VHwGH
2+Bc89P8YJR+jaSUI4aErRVkn09rWmCEsBPjf3JFkKRaLYVov/hge8sg+PkRReSC
f2Fb7YyqNcd53iRSddIZUBsmD4g5GdsV6URlSh0Z+tJFAXpSOHWql7zyTNccK03L
+WVp+HJnO2OJz7erHMt/mgXMxMmVGoBF6C0daChCx/i9PFVVmTvIHt2h1u2WbPbc
1B0+Yaag
=JN8J
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-1364-4aa3-926b-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/R3J+uQ6w18gTYECrkJS70uuKEBmrXVge5mSF9aEP/E7S
+FEJoLD81yPnFUUGsov5veNZVtpBSavKa5Itb+JDVrLSuq5gDZOwT/zJZHTGpHVe
d5f5E3BWMuZsk6AS0/ioCHmVRgbsEn+tQOOt8eMLyNvX5k8HAQmoMdsKQSxsTbFd
k5VeW/Z8XZU3ZUV1/6F4d9G74UM7rnjfVJ248anvCYJoSakujd44Cf03yarcgD0O
QM5yktMpEcQlOsTZcxXUkSYo6Z0wBumALlgdHOBki8/1YAPMpDA2NlqVVlq5CAnX
zRaD21bfsyWgRXglYEbpZgOXXVa5qYGYp5Pz2qgGY9JHAW724e0IRVNx0lUPeEmV
923OMFFLejVkcewKOe24YshkJG467xaThErfKm9BKqpOC6lb4UU9LrS0Ks1pUWwg
Gm2PZt54Vzg=
=19mg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-18b4-4030-9754-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9G3XeCWULQy4lQMrGyfD4k79SsemJ76st0AHwPCodnxbF
RnAFwvEIYYfI9RuJmXnaCRVjkI0WCMX6NTOC7u96LIbkbehtra4sRU2Lo9AzEa+x
SPLhQ890yQTO9zDjYw9KSt3kbYF0BTnkgbGIPKeMlH754BwCcU984q/pchS9iADb
j7zXkK9OF9UcwSGqr6r/4GpoysAs9SoRmkTh4Ynb6dJyOuQ9Px3+dkz2ViQvHnrK
xLr7+8XmH814mC/WMmzX8XmZWGd1C3Axm9kcuO4rmndYGLvGV9ZkcP90jL1rxw3X
a2QBXsjLVljjLG5MyGeD2sGaYhSrLQZAnkLPPDP/2dJEAWsPJgpZcIr0JFK5XE11
Q/QZLjLnp40mWEIkPWPySo5Ta4114OnhEugWVlouKaW/2tSoklwvP7Gx+optjEjS
ytTTRbc=
=nYDt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-42f0-4526-a7cb-19b0dbeb2d5e',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAxkA6B9Z4y2kAQ/+OmUVP81XO8GuOMHUqnVJWn0QAQnx7ZzcoHD+z9IL7k3y
ZzPo+HWvv4lHMD5/orRziaehATHmH8QdBJ/oOZHOREdlAp0v+Lt1txbttU53M69i
uKq2iKEKEAtkziamtsEoMSFVgIyiWpnSydVW6gyMZtjIRpamPerWy2Q1CCXvc2lZ
OH2meqEZA0IbOooyvAvsho+7UBss9UQy5Dpge7YiOlBKCuVPg+4PbtaWh6cqGOkK
qTDLgeO5y/3yyJNad2gda0l3IUFiNmnLOuiKrluAMpBXhWfYXR0LdScmm/iY7HME
9NEU9SK5T1cuV0CEPGxoyYG03zqs7D2MQdzBmRQDinFhutIRTdpepo2mINR/0VMC
b4idWEXlqoEIvWQjVI2oobDUkPpgC+Q8nffUpnvREH7GYLRbJW8/WDIUCX/Py3EN
eWs+eJJgsWAo+ziFAkPl+Wnz6iW+ELRRu3Tii7cL6KxT7ciuE4L86fWJp6xe8bHE
LM69+OqUrLCblobK/G7OIq8xRi5oN+sWmWiRvqK+O2ZVW4E/f8eWQe2rxPKcOB6p
QEEeG0p+ZfxJEoRH9uzYVi/S1+rDb2w0jHhKFbapKUyYHIWlWpEOz6pf03r9B2FE
3fbGS2sEl15bjdycR5ucR+LmEBh+2gZiSNg1ENQ3tdRMr7ST9Q743uKDcDba3rDS
RwGgoDAyHrIkLsLt00yUy7VNPG7qiobXQfdFbriTk9COomKPNS/Exsykmqs1WQf5
HXdOZynsPVnWyIn+mCaV4VEN692+k8NU
=9vag
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-473c-4b00-8d4c-19b0dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf/as8lejngk26mmYZ0iEvNL8CeyR0jP8kMS77yN4wfncUb
kx0APVljIZP/ka7z1q7g6MoZDN7x+h2vosbSgJszwBpaOyD0z47P7njgh3MRU+d4
rpqXR0r1V7cIqhXC6Iu2h2irRTci+ch33eotfIU2JXoarlyNA+kuNlNAOy8PrMAY
/iS8WJD4EkJGe9Z0EPpDu/K7Jw5WG9TBRGpFl+iZKYkp+4s5sOgQ8KPUolxSRFqK
0nbzfhOO0nc+KlviOXBYQme4uZ+U+iWkwdKxIZEYo+qL5+/MberU0GqTcNBptagS
21REF1UsksJnulNmQpz2TqYuk1+7BlCl9okhppWWO9JHAdEyUsYoZydIpwwt6o7W
zBwxDBvqLHSXjAPpBzkMrgLXkaHg9Ybmv8I9TVQMewxybk4t8pLeQ3FSl3NxBONC
YK1UafCf3nQ=
=xmoU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-4750-4b3c-a7f6-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//SMTgNvUBSTkqNt07qf8NHsvAKELx6I1Kdi4bGKzVHg8+
C+WWUE7onUd61UI//FAQaCi+lnVisAbFdAU1vrkUe4QMyJrQ5JyK3Pt05DHf5YSW
b1FtSr5Sfh50jBOBhgPo+tgMSjH7S5yYPXEsdDctKUuhX564v1SweERklaS9Wxdj
4F096u451/9T80ffKvctSQQ2z/1DFi6ce19DPoLk53zzjU3hQNWUP2nBYKjJiRlj
7/b9F7ppbBmJNRLHlKjnL6mUgq/rxg5z+KN0kXPkDycZg4ea8Edm8Dos2II8Nnqr
flXrtruS1hSET52W7QxzywVbYQcxBXs5FsFmtx+EtJ0PGjDFM0fxYrkzJQtD06o6
FQ1ADl/7ffrpPzRtq29m1UZfnWvoQUkzmZqsn4z+T7lCgQTTglp7hiTyNonq8wIW
imwKb+RU/WbSE5wT/ECSq2KBv9E7ia7bZrIQY3+i+UXGNbwVqlccX+YlkVBsemn1
iTymatk6MQWkcUSFXt4JTIUVZKy59gqjwHxKAt5bAZ9sfaznceRbHRFYciTLR4gw
Zoal4/WXpMbDF1SNE40yNf2bfc5ZdAJ14zQ66SvSS9DmWU1QuCh3uh/4uKHhSInU
x+qKrXTYhgpfWMUEz/f4HDZB+mE7wvRLA6SiDcOlIyEXcf4hYkuKX6UQ1efJoRLS
RwHuaHXPYbgRFaUvDHGqO3uIk8I51WyrDbSKeoCVl/pXLhURmQly7GcKx//efTZV
tjPpZhehjTnunIoc+8vnl2PZuDffiG12
=9w5W
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-4768-499b-8429-19b0dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4e/DeCIHsAzARAAsDEmSWX2wSHj0dv9bXR9MYXfVcd0i5evPI8ueX5SHGCM
XnHAi+GoIvZGfng/59v/qyoP3mVurgJPPPPGpITFjR5xPRTEEVDiUrP2xIMrzShG
ZtKUhTTZZ6sczmTbwg7b2qNTUQReQe7C7s5wSGZFhqVDrLJMXvZKTrhHrOX9kxi5
U1CwpeM/pWz/ERLaGn18cF7KCC2eO03T+N1DkT2cpu28UZRmzLG53mHXbGIE9Vub
5EbWANqIpkf2vVMQEgKWh+9RJyWK9Xl/gxa6mC4Ii4fjwIIjCjBGjpVyFfKQOBfW
C52gMqylh/8QaixjgVCc3GFHPPx5jF8q48fBP0pw7zuSJxkwE8J/FoCyUymLegT5
fSkptdaJD00cSEDu9xDY8LUzGw7Nlgftvwt7OrmCeSOJpbkd/vRus+HpYjYxUImJ
8akHnAPv2G7a32AL+oa0g5JM3bTpTT7oVXZCEZlMdnLoThAawY1cX79sqRIL2vyu
1KQq+9TQ9/3fWtpeVviPLgdn8MUxIXFPJqUHjAuBLHQEjQzFSWd3Hcxsfz7J5KEN
uw0f21+/5oTUjXgWeJ4CW00fCHfoLxm7ym6wDhHteBcstlk1aRoBWnI0jT3Y8Oj8
5mp3ah7cMECUJcR7dmziUiP/ibj3hkItAgQW65ndybi7h9n6BVBQG1LnZxVIc1DS
RQEVbm6fPTuAqM2KxyekCGkke3HGO1X2BEW7aCw2kX87zEw8vkFEp1YICwt0Y2dN
vVOi9tgPVHlUZTWFe2Z+0Aa9JB/syg==
=aoAf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-5454-4711-a5b6-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/+OrVZj7N/DCDl7Vf/gQUlyprnAI2iwW2hgg3r/h40iXy4
f6PK7byNdp5HAXM/0y+7zlHwvYW0+0OHek4UA4BlnEWcLMTVtuX06eoQqiunntgI
Ze/y7FseZNgIzcBNlkYrJV41cMZHhxucHphuDJHBNenpD9Xd4rwbUYVgjnnrDkB6
wm+YXTjkJwin4z6SnaDukNabJjQQL7mJOx/BV0Qu3zonuujInuRX5JYb/BvWckY0
cnM9tIkHPW+ltESgCgR8/AMtjDZXlX3za3sbauh3p4fD6eBXBuVl7ECgv0xYTlkZ
SqePDaai94lJcPGil9dhIUFPyJEsHM/vYJGZDaVTQOM3CCcWLFFgRehBhfrBqjat
IUrhT1jSw1b/6mcwy5MoKQxlPm2vDh3sHW1CNUuJkjxXsI9RkTDMnnAnrY8DnJaI
FoNv7y/iX3arLr4k9tyS+CxG9nqZZVHPqBZ++tH6Q6DPCyMHcOrEoUUO9gk8ypGd
yMdRTHeEPy+QK+XKjaq7NIiE5KezY6cfrvSIYvy66YyWlvo2Ug5XxaCX4fEaJJzq
Rqgni2ShE8IiL7jBhsLvsUqpDylpsOywpOrRQzjtyIanWEYMGTxEAHbMgn6xIqu0
5DmV1PxVOgoJrWLT8CLGZO3pYPkOJhCPDKlCibynct7q+MBAiopJx3zx8Fy3a8nS
RgGbR4wBU0cNrCRzkPQaqg0R+i5pgjnr2zdvXLDBn+WKXC5OtIOYBpJ5RYIdxa3z
tIH0n7xDObUmQMjGkBwGXfy06/82eMc=
=Q+IB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-5680-45cf-b83f-19b0dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4e/DeCIHsAzAQ/9GA+cOMOACAdV8QsK9pi+7bl6TYsMczOUFVKiX51rIYte
r9fxvyWcOPGNrMRs8C78r4C8dry7gJly1P2+q1VLN316k6vDgHN2xY1VzCJdf8fp
p831UDOpZSkyzRJ8bU0c9/8laLKilXJGC3N8+kFXPAzd9upzY4FFC0hMPFlvuGcj
Y9J+iPs+C31BymdscaTJFNGWcEq5cgPoFuuCn7a1dtWT9BWzY4wsETJDhR1Hm2s5
NPVvK1rA1M3OcimT5VSnussqV/I5QY01lUqAiJdCcGnLlQJsfPoN7oL4iZNdbdZy
ZQBm7qaY7wcj5w2+SjvEEXV4KghK0hLmFFgQzx5JaqDVoN+drN3z+2nMkYxDJMh6
O/s/wigs5g8UQJGy2dmK8cS3K4Wrg5o9nuyUEGVxsVqWzIhsKwM0RBKPyCyXgYh9
IgNHMmTq+GaHT+Y3ldkj4wXjWwcXBFfFTQV7iW46vbDkWEdvBw15N0z+pMOv2N/Q
a7SA6LNO01QDO+OUCf3e4MoHoEM9f4b9SwUGIElJ/Fg3I3EdvRycQ1jUsVhwRvp8
tl8tlPAch+gKt2hZS4Ksqr/67ntP36ZWKs/mi9eaYHPkfMuSc9iEBGy+peb8DLCl
OTALwprtGvweQ4SVl8rfXQOCSDK9dDrsBP2DNXRmUZf8cmV/1HQwrCAWZPK0sr7S
RAGM6USLnnZG3nBJkxOqNlzlXBbI4FI/5EtOO1oJXW+k/QUCezYAuOPNb/R7qtNk
fhhRMX0t9Y1R9NYn+NhxHcz/SzKe
=NZ2R
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-5f88-4f35-a469-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//aGzmDEyzrIFeubiNeA0rCuY5R6Cn6ZbUM0GtLWPPv8/N
aTC6UXAurezjt0m+DWGVZR6sSMM8g380LLgC0Rd/TJRCa24CZVg0FEAByoBqpj9V
3Isf64zL7UKbXri6/kXqYYBdA6krw14H39LUhqS0Nyg3W/MYAL8aZ3s4rrxYSz/2
t/LKYRUZ0zJqn43JtWJe1jsjX/y5qJiu0UMiuujcSbFqkbIYRnbohATFtezfmVpg
OthCh/bPjGxNnXfb5pRR6eWQU6PYf/Le8rJ0ySZpjKlFdYxmuJYOF0XFUAf7cJYa
VqqOYyNzWI149eC/dfRSA6QvgAvkfJZPWer11OofTm1sN86bIPqIQK/lSvl9MLuE
UEVPHFCzT0bHVea/3bprj+nY9zTnPW3g/cjCyEis9QyTGZWMcaO5ihc+LKY++mfv
sUQ8T9yipkhzR0Vnhjs67eqfrHckoKIUot6kKzLRN+3X9HIkTARxqVcwRLozibtA
tv4xSkO5Tqt5ef6rNwujNi26VTATBw79d4EQ+KkVmA/sx3E8fCBbnCxvesv2mfQ/
YuV1gIwQMCkfO6Bl/Bw9ChrTsUHPKzbDdnLPF51j0nzp4d4EoUu9Nrxw8pT414H/
34z2uIO7U7I8C6NVSK6jn1QmrUwYDNgkwwFluGgmohuPoLkqDnGogUgcZUCKEK7S
RAFPOX80M506AGO650X+2H1khiMMjGVfe1PSPNuUXaWoYBvYmjPEwb421X6PGayR
nNkoQvxzkf3DtEzEbdLeIB162+ce
=4rb3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-6a24-43fe-aa46-19b0dbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/WRK3YesR8eAFHiem20gCVw8+h1uVRaKJtYfGzr00Mb3D
cAVhthRvvH8mbcyIBKpi5HTXr7zdTQVezb6R1CFArzY4M9DSMdQxaUZlPDQN1w8R
IFXJ2uTDodLUP4xNbxNx7qeYNmiyCojvkHKU2mtkygXBSFrj6w92o7cwP5vzhBbv
Kjv1MfrK0RxVeVGgvjxzkrfETBMQecoCK/gBGHrOGR8PvMr/rTiv6MoPDfQvm+BR
0fzbuv8OtJF7+wYJzn6y+NsgPwdZH480hgyOpDWANJOedogBOGogBwO0P39dMs4r
f8BgS8eWkO9pblrURcaL06sgOMOpXUDOnm5nJby4O9JHASr83z+55FNocLXCyFGg
dqcrP3+d/BNDMNbJ6hXN7vSGxR420LPDg8+CL8A4tvKka2c3JlV06BWufIbjByaU
C1BDXfZvBmw=
=iU3Q
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-76c4-4a43-a9ab-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/9FDwzg2HsW3iwGy2CUcQBJIqkdAY9m9eIL7l0EI+JKyq0
b6opMu7WH212Bsut9X0rDapeWClGNEO1WYVvQgvpI4s3NNRqLg7a+9je3WqrwQ+0
XF+21TaX6jZSeTtEyhSDOsD/3b8ZvblQiteF8jzkys8jCT8gfh1HsI38YZtwQTQu
PEKT/dI+WBYcNufeEYqN8kUc71fTNZz3zOvtYN2Y1+RpUkKFSY9w/p/KK85pffJ2
jhOIcQ90InqbHcO0AQY1x3fJKGK54QTPPdj9X//rwKIOjrlZczUd3tDNi2PCMW9A
vgY4YFuqODadLmZNJOYoDZVl49z3YBQis2nKsSUcL6QvjKnBTZxb2BK7ZScByou8
CCQBaEFQMxDtaFlQft7TEpbjnphm+X/aeR9ALAwlJuO1aH19W0LKfpwgDeim+g0r
yiCiXhbI9EFRz7j0o49xexW/5sBMxyfXKwyjlrmqTK0zcRGpZgPX7ujnZo+1qEAp
xBS4IOpOd+PkGlhkML20HZEFPCPG9F/MqQdzrsi1XRis4olTFGopnmGWUPBr8x3E
n/9v1CTG8R2yqJV05jBKoJtEt902zkLzLZVtmYGvv4qUJy1EzX1ZzVeMMrmmYoAj
FUf2xlcGezEoxRF9pn7un2+DP3VqiRmbm3H5QjRdqhe74LHlHn96jn7OjhMvS9HS
RwFC82tQ+upE92F70Xxw8IDVErmf8Pq55VtHTyFbc+hvEIdnv3fRAREHrivwkTi5
syJ1NQYA5GNWeOY7GO9gzYu6Nswf1H8I
=j8q5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-7d88-4547-85d3-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQILA4NlM/alsYWgAQ/3YKKt+dy77DPzBMIDaT1A1A8eENWI27kZrDEM6w23TASe
d8lzvj8Pcjc4sh/Y6pNG5l+fGmTHTOAbouOhvEhDteAf+fZ9oirjuwFfSHZHZO5f
qvXvUFta3VVhkCNettcbpYhObOs25zNakMwYtwgTHU6W5wI1jh/GM4wm12bXwiBP
WeNGsIK1hdDCBoxyNokyS9bNK2LIjMZfeSbXJ+yipFM6UrsRbJVst5ALauAio93V
PMbQKNt8MJAKfgnBfkQU7t0e3kGffHtmDWKt3j/yq2As76cBNw3IGf3kMpxVYajC
YqFFbyDb8Wf0hQo4hRUrqSSpWjjatiZ22j2y9rgBDrhCianEb5apoKdB7Me1FO4u
Ob9/G00/5swi4lEEnOwvajeuYPGZSX7HmMZkysbz+4UPVtDaDSLjVZYGjQNRkSeC
U3yekqmvxQAaIJ0kZREydRhkonC1Gqu4x2J2axuw1dNr0KydOO5mda3lK539MnLr
1bC5Cekul3AxlihjtkroY1wKlBQHyL29Febvp3+y2XT39rFgui4ZbFjrq3/LgrAY
38pFHmDyK9288k8/gWqT8RlTMvpDdmRWAICEeqa0wdLdlbSRhppcQvfN+xT6Lvp8
SFgsom27pbXBMDEd2/Wk6YSItCZ/VEnnC5BJoeiITZSUtnp+67icXf0ufFU1vdJF
AcMEVbUQKHrb+QMNW5AGqHranXNgeHk/97sm/MVjSy4bcts5u0xXLXrKMRz+afcT
wIGmQ1TdDYP1YMH5fnA8+8RmHQDK
=W5sR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-8868-4336-8d95-19b0dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUARAAgJwYZDcIv5hOZPlZK92dmNDaHDwHtRkHXEoA4qo7GQdb
Uq1gimS2OrZdXoZsKglcIBu7hRvfYLc4sgBdceWFHxNvzw8OYXdUPYAhIIyvzewr
y21Ogbc0Bd2of2ztjWvp6V6Xk/iKkNAAgDUYKRSvsqqf5qlYtr+mPYCVAGqlSALh
BpXJOLYW6AzhC/IKn4f2gmgbWSzT2Vs2A4sT6Evoci35DyK/p4h+OGGIis7aX7F0
S6DbTlB8kTvLsuocABs5mtmUHodPETvy+pntmIKfDDWUA5ViwwdUUnAqcT5MlR/P
10VH3Z65wyb6XMwp382bVvB4qZWbCUTRFIDc2cPBjS7W7tLKiMIni4B6CUw7kqqC
X1eHThn37gtiRG+1wxeNcmufBpjXl+l1xYdfkDQ8l1iE32yJpqfez9847yhHNXxI
M09RGKLujC8NdFlTGXHaKYzNWMMqCMXh7+Q/t9W0IhZoud01g1C+GtHL3icMWmkJ
FiQueaFZIFfnVddnXsN/hPK7sb/Sp36dzpBQpM1NpCUvH/Sep5z6tY7fgqYhmyjU
9j1sydBjr199S/L1h0LvrejUedoGSOZp73dkne8p1WUAHz7yPo6pivgCJl+vTnhv
qQ1u+6Ya501w10cXrwx2JkWiQaFog5i0IzTs6IkeH59C70KepgW9GuPj3eQcaevS
RQHdeewKO4gq4z4tY4vGKzvmNmFOl3W/bXh0iO3JRlS4Wt/5ancGoFfyJJbQxod3
k3ucJzoyhXGtcXNjZF+rTyzbUHx6bg==
=7LCc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-897c-4c4d-9156-19b0dbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/dktDl6+DOJZPyCBGNLg3ZQjb6z+LSedrPcIswDVHF59Q
2KItU90EnnUjLMxdguy58LUAN6kJNuo6eR/kG/k0ytRVzaeWTmDo3y/7pQgpLJ9V
GQKg/twmuNwDPR1bLzA85P6+parssX84Bj6nJao8IRKmtfGm3vuRLZD2naKhVlcp
p5sNwrNbOZh+1eWBviT8XWkMz3BtZwNxx73/AGRfex+QtsNcJhcWnbABCLUcU1eU
DMRUKRMEVGPrBGgOwHROiNp46hlBzXBxXjcU68ZUVDxbd6st4ogFCc7VYuYAzx7u
3PeVPnGd9RTiOqswQJ0x6lv6nhX1Ev0E0YIQzn5MjtJHAY1wMyeiW6TjCcUH4C1g
HykLQfNFUiuMPdNZ9XtNHVxdlNdHzrsevFfnMki1hn8yyNZi9ZyebNzD3GDd8ijp
Hxx33ewCFRo=
=rfvb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-8f3c-420e-87e2-19b0dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf/StlKh7WH+nBc8J4xuUlXloP1VQFd58bKpWHgSoKyRhb1
uNKw8tKl9lkU5RHisK60LXICv4EIryuM1X0w7eYiyUTGrd1ZboUycqqdlJl+02JX
EZlTDND8jg4rZPGLng8+IagXKJwgaD60jQL72Kz7DqWRjs59voleAWfa8htRzCYc
giAUITg2czPNkfxUwisaY9lzPN58xM+ERQ7HXfeZlwnBUch1JH/Mte0tcWAW3xCo
OE8nd6oz3u2z/toU3ESyXv6m2t+3x7H+kaWc+JBPgSYm/1qqDhc4hRNfgb9S6LKQ
766Dpgj9bSkc6wP1T0iFY7k1I7FdPC0bt313cBGgktJEAS+Vrev5KemuGlHMBvpF
BV3CxBhDTj5IqPRB6A+oZu1kF/etbE93oS2w+qI3QQ7cRJiFI4186Kr0uQRL1lod
HS3LYvk=
=NX/n
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-cc14-4264-b75e-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+NcxPmi7yYkEqw42n/h+CIwNaXF877kWWOnwoCP1342xD
GQFh7uz4JwDeMBPpnKGsCNmWbluxopK4xwkFR+dOq0spyErKm/WIuap90C0gjYhx
20YwxfbtvzYf6M4Wln2Jp2UkT2HCnxgrWaL9gxKjS8vrHbQHm7UaH2OCPUOLVtO9
2t/c6utUQm4UtF60kBKNagxZEHAiiiWyvCDmADF5rIht1MT9ZIx59GrtiSKyTTC+
Dd8AFJE7JhlR2OyEILRekzh1osRAXo5S5iS8STizT/q2EJq+dO9kaIsmB5uhjvwk
zONvTuKLcOd2oF+Ci//c/WH4XTIz6Jpb3WPTvctLJtJFARSiN1QYZ5xY9UHzJi1f
EfGcj3mL5Ta7S0Y9vuDFyt8MyQUy45k6Mm5u2uapkwvXSV75vcMRMZeWsh0rBaPJ
JXkTts2r
=Fcvq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-cd60-454c-9836-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/a5RT5F+ReOFmJgTPL4hUanrGt8IyEzJp6HVtSOadnFha
KGkvLtTHtfjHSh9FhAS9kom+3wTNcL/+ESqK5NhMv0FuDUquh/2s4i7vlsPx3hUf
2kwkOqOjMQS19aPaVbuNwYr6Ti2RzCd44YI+vTBO7M/c1FXEu/MnYI6o8QEgBQ/g
HjB0goctcby00SJn9yU1bHf9qjGYyTKOL+cNMZmvk4yVa+VUuXSWS9jFvSYgj3iP
Ai0YGaF9DVvSKwgHA53jLbi8mfNYmucVQZM9gzQHCtxcoZAZvXPjWwrzKrIjIa7+
XjvK1zCTe+ZAQGR2ml3Po+idIU3fpwETpmPwBNn/6dJHAQWJTbiwDWzEmCiN38Ny
iGKwB5iaQwPhv28NzUEsh4LlGn9/m1kuM1mVz+gXYCVGKjFFy+iASY1k9ukScZ5A
k/YRuWiV8rA=
=Up4s
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-d23c-4ad3-acc4-19b0dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQgAozFnGMcyiaj9NTrVttpTaL8xPCTGtxv2CC1cbR7fglfU
uuuuq4SHQbxyfFWQWXXUqpf6oUkEgV+OTwBcoRQWepjKCAJ0H0PVOP0dttlppxQh
v1HpudO+DTHIoSEKEDCifnv4ydh/HRYXLpy1W3MVVyCSlvFE4hCjsPqg2nhX/WQD
4a0Gxl9vJPT/uNfA+tDoylpaCCEYXJzP7h9keaIm4InVWExvnTbFrFQb5A6IqLAt
4p3wAySTwI0CKvDlXOgKsvyIRn0rJO2X+io00o+KeUx58qoLrAwRpH7/SG4vb9Si
D8Q4VcT7WwG9mhBG6mfOhsEI4BMtT5FflCNi1chCqtJFAeTC66L5V/BT3BTo4QWc
e7f6JnpLl33B0D0aX7uprsxJaxoAADIYgh6HYYR5G12zrwYOfmHgmUGhkCpjlxaB
p4kkb8TH
=ok9K
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-d964-43c6-9a3f-19b0dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf/eldL7GJQqyN9KNuJyphjaD8GIhxWWlzbO085AibPEVnR
mb15yOrmY0iIc6jLFFC1RIxFvbJH3laR0DYgdPsQQnMywkXPTDbqbxf79r3TEjpX
XF6iRAt/fKMLXeRXPqvZNAUzXk14DguHZcgQHO8h6bZ+eORiRWmjFlpl05GGlfMB
ocA/6OttSKwWDclWfAl7z5wOCB2TQ9Wap7qgkmOZ9CMMiyz0OY0wVym9rDd13+9s
OQMxGJEQt5cVTINPSxYUOUYbpgFfP1EgYFjubUlr0DdwwjjPH0AoCrH4PNJ8PoZ0
UbcScs6iWc1CxOV5xKBJHcwf4fhN9lYnHsT0GHsSIdJHAVBFm+O78Bwy6SMgNwx1
RFxMs21g3Q4fuI3w2Hd8L93M8+S/g2jXLW93vaQTreJwhvnzLsS+ixVrK7deEOAl
ovuIzbGBG5A=
=23bZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-e484-49ef-92c1-19b0dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf+NT+Ezbw6IBoUOHssJGtNCCsDEGsyHCnjjvwbaUSpCJaH
C19YEFRXEAVjBOib+pp6hem5f/PI2nN5Er1cGUt1lUI2rGgY9zF0hulEzSKD7qMH
tr939Ea8pFKfZdPWl57Bo9m0+5lBhC13186urMWKO5KGVEWfBvIjFR+hfmIPkNe3
MNu/VO7NakIkGxETsC4MnpXi41JaldOmB8gfwxW3qcZWPw71EtuaWu/uuVlEfJNi
RmWiU34y3lrmGFyRC5aioKPKVG5ldjFrdS/XX64AaYE/yGwEVKw7qgFTdL2LJD9Y
z/jmNkMOsBcGLhg1GZeotKCQJa5xYTv9NowBEeNZuNJHAV8kHp92OO3+khNAs79w
FhBBcsOedoFXNJ/h641YxPS17nH8Glvnjo/q5LeciNJ1PdFiSRFUSnI5ut/Bc0SX
zRarYX0AFXw=
=R1QE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-f8e8-4da2-a41e-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/bjf4P36u54a/I2qPhJaBG+QHfaAaP9tQAberhhHTkxxq
sBTZn8/loAoTC+Vnc1HSxshYVbFKsmPRtJ9KbfuI+uEBbChX4Qe4lxMSncb0GawM
RPoMDyQHSUB9996XEvq4L8SlhZCF+XcHVzOEiHRrqFhUaBC2tgxXvdY9iC3UKXLv
K05EPyqEF9oSXQ9G9SysJLEUXk7ZKzEWMn+irBz+4bBFYj+awmnn7dLUzCTdPz+g
qb3aFaCsmxVlo2Oawn+XpXTAUCAeHCdsuEHRBnS6bhHagsT+IlQU0JsJFn06cekt
6JIzgwW1bczW2eLPLkLhCzy8pbKfIV66etjdtN0E9dJHAe+4n5e9AvZ+FJ2Pr/PQ
McVpiklJZIx6r3fF3VjqvRp24xDRRbVArluBcej8bTaGq8OV3wVOaJ8/+SyjgdEM
2/Ao6CUeotk=
=NGYI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-f964-41c6-82d0-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HObjYFTGfYLwMcoHUoJMqRUeP6wTeKeQKKshKSVoHjUi
lSG3KZPdtVnOnLxjrlJplIGonTWjzVzPOSUDzkEdmHNM9jTPf+7bxYxm+XOgNSX7
gPjuHOoEamBhDuF3ZFnmIjALmVQgOSof9kUdTPu8OG2IPq9vbscEncbVCwarwzta
ClehMC7arKqUBArKF9ewzV7oaUjLWgNyXxP7tqk6uFV3QDzIDVgMRkC5t9mBoNLF
K7E6RwJFZEmGxTP2YVwDSKzt5zQESZXrJxdJiR6LJ6q4+Zllihp6Prw1zBRdRnNZ
7w4jD1KAD3ghnPrJD0fibJp3uRKqZlVu7FNubxGvjdJFAcLNobhSl49JPlMda80h
0RsdFqxWs8k+273D6wsLgyrYSFDlQFsBdZEhkRFrtImZLQp8mUiotDesxwIlwB/6
D2CW6v8H
=weYJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b47-fae0-4b3f-8953-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgARAAuC06bz2OzLjdTArm17qc6pTFRJkD6L9OTWEncnH+C9tk
RKJPt9Ovof2OCCIwJqTXoicLTOiZbY4NZcAF/OgDBeohGfiCyHTJMWzZVTBiDNtR
UJ4AMhihY0Mm1oojzGg7jDlcP+nJoXq+qR2IRDozoh2NGb7dMxF6IdanRmbYA1zn
WBJMMiFHcdKokXmmGLDwZm1d7jZxyqCYpmZIBPa4mxUh5ZO0zVYo6UXW2SevpAzn
3Ms3P580YiXqbb/LqBq/3PxBcxagiRTp8qYCcIaWxszLQVYJGEvY4sG//7+bsOD6
aMOJ4REpnDqM/z6PH1D2lSBv0q5+RCzRweJ1RCAe8YRK+hBWLOk2cB4YaHCWiA46
AVlmsk6eLNDeukLdNuoyyWdISX21bfTOUfz8Gs5HXKGjixW/62h0xm1BUSL8TUSa
cU4fkX7qczUTVZTRGqyL4KzgC0M3OT9MwlxFkUpnNAT0FVzwPwCaXtCnfQvV0++w
gk2SMFixHZ+ZooDVEzFF4cSqGC4Yw4wpCZPToQGx1Fyy8t4IyQTKYCgandJyr2l2
21m8Jm4WIAzF677bM/AfJn12tVvBzHpWqFzSI6CEAVMKvLhrcwx/CzOhCi6rkgFr
6AitzW2bFTcwX3tprhDjoca6XEt4I7G0liaghQj5VlmVS/uYYUySwCP9dXqK0lbS
RQEyw2WjYsRYPOsZNdJcRkYJKEgt4lKf+pRog2cFvjwaNFDhSwEGvcei+hUwxFE/
9JPs6cmV7z5kwQTILK9rxhkqSjNOwQ==
=8nD+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b48-0720-46af-85de-19b0dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/W89yJ1O1JL+sAz0Tg86i+Z65J/bjYzTS5u5PdkXDq+ib
Y76XtJyxsJALI17zHCo7zBKFMBw7cE07r0Ua4eOnp1hDdxqrgou3ZgUSE23fcTfN
Dyk3qbZoDga0vZdDoDNvS+MK1ogl/LeNQIkQZnsTkvqou+C06XGb8LjtWONrEfXt
L6RLhzGbm4RUhLhqJeH/4C/g5YLEa+2YNisygmj3xS0V48ifUHj1iXN7umNxBjZP
M7QiJARwPJNfcxmL3RaPbn4eOlubRz/HXkXbAoZbQOCPuAgpnuvZuWwWIEI30nQx
fdYrpgeVjp33PbxL2iVgvPKbqApO5IjHdwFC27YZKNJFAem25cBAp9JGV2cuQGdl
X5OqmRaZYPRhX41f5m2jInPAt/lyr7uY0RxKO3cKZ8OxjbDQ43W4JqNTEMb5nPbO
1/fRBGLR
=wI+u
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b48-2f2c-440d-8bb0-19b0dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ/+LUr6o/SAjuEGjbaTI0f4+UVr8oYQpVAWHykt3R1Zh530
L6DWGxNY3ELn+Q1aax9hoepi8vX7556snMBsaBJeGbJrVx0jTNHOKEcjhpTFce2G
pTCrV/aPm9fIfAUjMfaWUu7kloDUNAxqc/Cra5yAURLw42Ge6+tyyNAAlUiNBTWf
V++bJFYteUV22pWg6MMHNx/C6OkZ99BhfEMKzd58xD0Q+HsS6/LnW6iFKxMJuzh4
J+RapXsHkiNpGJJ6AWbEBQVtT28X2/bZk/6eIgMQA2rNdLdphW4dsakc1SVeakaA
GDV49ST68uOZuiChHqOheXqUoCjtrY7sxDnfuFPuyh3GRfTZeq1uGpqhrApy2Una
yqpdaRC9d486xk4ixQ2upJdNEs04GMn8CZM/GxqkbUbUBtrijP1wFw6kmGjciUQr
J3xxnpIWoaf11l5H5Y1wfSuQtFxeyrU1FxUyDz++erUPpTTXROjvR2/kzG+Gvn72
qLvWgBm19qxxASfKPEVAd+JXNXLgOF5yHLAdn57IOLXQ7IIED9bNL8yFZb4UNx/n
fl5HUxu957ML4Jl2TTcSsnkWEpu8HsiBZYeyYBA7uY1rOha+D5HVCujJKAGEdu+p
TSFJUku6VU+ssAJgb9JQf2+HDlAXh+fdAFjN/RBPr7x3C3pCgW2d4wBByHK7JhjS
RQEnPNqJFbIDG13rWXFnxy2RQF//KAUGyFt+O+bZagBhsu68OAvt+RGtTg1Y8Zte
dDXAbT/Kin+zjGw6BMVU5CpbbmX46A==
=IIhu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b48-4ce4-4f23-b40a-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/+IzBDNuyGcAG3KJPQaD2ez9H4Zb5HWd9v3fiwyxfQL7qA
2PnMarrmOrPw6u391zVxW1BIZw+tP62uX3Ab5CTGWGcl4SeD830PYebuLTPKmXdi
ugrQ01/+omA8A1PFuXtMweNyR+YB0QtIB48DCqQg+b3o0HPhBWFcRQJubZZx3Dv/
h0a9vpZeczHacyhPmvGEfyITCZeeCGNSFQJSuSyQO8bTCryx1LRGkIdpcQE+dcJH
yRHRpLsnJY/ihsozYCsiu3vD7qVgaEqptkNjt9mcaUDNIpdKX/BczxB5jFLza/S1
0D5P97MMTSYmWikcUDNcxPgDz2weCWpvYMeof+QgRgRjiK/qKbMJIXSQm3q9AOyx
ExQVUy/mmoYP0pT9HpZ9+Jeo9bmpRNFALdkLOmS+hw81A+As1sK7H7U5BQjgwWfc
MqQs1AWKzDxs10HMluOSdr0E1g0SAhPvQE+r+nmThVZSDgb5MqVbmkjlMAQTKjvA
TFAIeUpVEvF/foYXl2FRiReobVrZvVGTM5Y9kQw9fddg6THu5hUHtJtKpo330vEf
5kev6v2HYWbWTpKo+Wt3jk9cPFm+vZvK0j4U/PyDbSHjMKrqKOnRTV70egUq4CRE
ICDFjBJuWr/wWE2XlC63BMi2/hS3oAo43qU3qYm+jg+fq81QQJiD/CdvAea2XA3S
RwHcvu3oQd1IFCoUjoMlNn2DPK07NG717nD/9CturAcCD5WOIAEs96RMfKsmVNrb
2ms4H6ztQ+qAsuRmMi88LEdiU9yOVMni
=or0o
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b48-5948-41f6-bc2c-19b0dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUARAAintR5n1AVv1B/TCV1duN1TEfI7lTZJ9cXiA87yMEv2Gh
fFoWC5C928SUnfau3F1n0gs/zJb+c63B2G4AhdD7redPnUYsvI33LDFt7QuE/n+Q
vssYgtj5UY25gzReeDOeJm4YVRz4X7K1fQYXLJNcIoavxI6Dq/6I0irm3i/8vi9p
SOUFRiZCnD16HjaxP2p2wbbwxY2EazNLpp6mA+OwZ1OlVNiobXZwHQe3bVUF8Kfc
n9yKaZ7s5QpWFf1OVG/5FEmlRcQZ6Th0DPeEFAAN5+9JPekg7k+B+I158dkjzYOe
u7f/YV6rqmfgrBiHDO2VXKQE7gPR9Re4n05iKBt/1AuxrGhmaArhMqCi+rCbdzRQ
bI4h+H6FvA2SgB3vEP6fG/7onoTPybUWgNF3nijuOmhteEy+MyBkCHFoHADf9wZe
VPYszyy4JJoDmBqnXEjB93zzmo3IPI22OEYkoaq3NM4wZgtV44klZ5WO1CsuBy4G
sgrU9w8lK19Y7Q1mNIhKch7a6y1UXnHk9SQ9Hc2gir5mg0kbeBo/232Xz6TrIL89
K782vlMuH4DnzbBy/arMlDM906nl/jsDbcFk9RssMBHPlIAXnZACiBYOhAMAGxoU
V2N694IX833M1NHTrUuL87nD8i8FOdD47P54J0/EtJibyLKmf5KrYOqH0vS667zS
RwEZXxUyTmXKL3Ki4KI/qrV3RVB7Zip5k7uTZcZW/nDnH/TZ7gW7JX39B1e1oOOt
bf79q3ZDEL6U39xi9YUKv4a8fY+cvoud
=ZiDe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b48-8990-4661-b516-19b0dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf/WxRWLFieoSsq58p1x1a9CppqLjmhbRugrho4gZmQiCKT
shYnuZnJ5vbOLDaDi0w6GGGsQsDAq87QXckgVe7koLkg8PQRjZQGPSsu9i6qX4bK
hEqdXoj/CJLbR35w5s0C+BTNYGqgS2IUE5gZYjt4qEpDmuO2caeaFQeKAshMHpj3
WacchUToqtKD4mlTR96FkD6GCIzg1bPlqEAgp7cd0iadvR4ClyUB1Pvl8CRmlwKD
KXe7ceztsroOIVJ0dD3AxYaxax7RRf5wwh0RsHAmEDVCVn59qMiY2t61qZx4FRFJ
fKAKEQM/A3FXu28gUyCbqMAPDz1gStdBZGOJNWPAa9JFAcL0U9qO8NWzLunLQ6B3
VerADiPYZGkIV0uR2imgMW5zkMG7IlQjjSu0CwTiyL/qfZYEKHV8pvYGlf9CoUfe
kZvGbZCA
=dn/U
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55966b48-e794-4b3f-8135-19b0dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//YuOdpl36sf578wRpM2/dTN/zkz+wru5kxcmiq5JstNq3
4RWCB5cnLWv15yYPMnrsIEk5yHOMlJH7fVBDTu0kwIlXYeWY9yNgKMr+l2ShFIDm
fp20xsiMXo/RM8/bLgbkqhfubk2JS8gcx4xIg+NE/i81tn6WHNaGh+yHf3ffOW5t
U6hpc/21ENPluC85Nww5q89+Bjrnd9cDvXPZrJEB6r7mtzB5a5kBs7n4vlU9fuuH
J/PI5kD433e0YaQjS6/REkWTLqHQ5USW+QV/J8M6rswNUzGuanUp2AgucS0Vj8dJ
/uKGVF1MUlbo73fd9CG/jvhNi6ZNF946uoitRQPtc3nw4HjGBj6pAHdmtUwQAaZB
Y0XDXmn0QEVQN3X848pJl21F5wHo9tl3ndwL7QHKR6QUMtzLTDkxUsgQsXAJv8HJ
HogQMA+q9UsfzZmJUEX8FydNYZUcsqA4saSXiun3oXcfc0q8sNNllSScZ49fmbxd
tceHZZU6Zb/uBMiyPQY+eYB3OoJK2b9L/BU1fCgc7ymmM/48Jw/hJoaKeLCM7NvU
qTYysYc0F/69oZXZ+qO3i0kEATceH5Ui3L1gIx7BlTmMLRu2ev80K+fgcwWQMOTR
okhQ5yMV6oM8CarCP1CuAXAtsePa09IeCopbzgMIXEyL0l1KU16SiL1EjVDlX+vS
RQGIPmHXR+ShyE+qXsO+Ye5OrQOjpRBJ3udctOTJgCVGZT8Crx145XNgtWu0U+Lh
GxkImPotcC08CcRJoYpGlkWJKawEmw==
=Gjhd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}
