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
			'id' => '01e268da-cc15-44f1-a473-cd19663ca4ee',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//U5k2THXMot6HEqTfxgsnSCzJUH65Sy2VIs1mSRhh/1Zk
q33iZI/ojSNC3I5W9/NESHXd4Ul3llMDXUW0FMBu/gKJv6p0uxlYv/lOxXlvv4Mz
YD18b+leNjZlgtrWnhYlFsaPh2wCu1sHYcnP0fuwKj6Wuhs6i1c7ni0B/tD1eJmV
wzKieLkDmYL86epxJlmUjKbJ0kWP17YtkNq2JPnOW7azkW4hSabzwbvswgE1QpY9
WabU0ifLUHe0iZPbZNh7O8uyke64JvxqbpfNEqnLZIEcJrGKcFgKyyDotGiv330R
VW0vq5FrrQLQ+EUEEY8/Ifb98lbqbDfV6Ypz4laruSmLJxzhNj4dbC5h1Z2p1eTw
3iLZFvA6rUf55XBAQh31AKtV7mSk/koelT0ayIOmGckdZB5h3wx4k375ePTI9myv
BLXWvl4JE8KLUPIP4JokncNeQYumtyuvBZw9XZ61b6fvNPkMNrtW+ryNs45KC74v
inWyOwQ1hMsSk08VoVFE3b6WYYlTWG3YzYvrAc6y6ZkPTzvxl8tM1EX+ASe8AImb
8SiPxz/NlUgNX9tmbKXd+BaWQeswkUw2JkE6I+alOZkj2Uo9d8pgPRkvpwNfN2Yp
rujD/5/AsxswAiMAR+C2jvk6la92cb8NlrHSm8+rsTOFwW+5nxj/gR/tadhvixfS
PgHs7HXIokQumIaUFHHlSbnst37F1r350xk2SYNPJ45nOunm2AAFP332pbgAeFoO
i+l2RryChnfZ3CQffISa
=5wYC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '064beefe-3829-4c80-a404-a832a4cc69b4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAjydGQ3VowoqRwh3u2DQklc1k7TX1e5E+X11nuwK6mP77
NjIOUTyxNS9mjxX91O+U+LnD7WjbtCgVs91rVBExXN24ccGIYhC47p/w2Ecvd0PT
HXNCRCGeM1hFaTGe2RQmg97ZnodcEb280xtjKy34XaJUC1U5zdt0lBuID+uMklKP
iljnFcSntgnZvojStWwi3B7xYMgCnbXwch51gGWthEe0P7U5OIZnesBeaY/sKxVT
s2FFCJ/m2cbvjyN0Iym0KOmmDHWYjwKhy/erzUm+/NhknRR8cZLBlBZEZxr1kpb3
/3ldQIZkb0Bn9f7xoK/Y59t6QgoKxXw+r/SIexTLwaU3/TaSmA9zxGTc3I78LQHs
9hi4BKrOwSf3V+QorEaHZ4sSeQPQc0/apXB6dHw3Gw1VsjGjf7VgYJdxIkFBTbLk
+8/sqUCF+QaybyHS/DNlEMbUMu/rKxyQtrmgU8LgKS5jso4Y6L3LlUvVZMCBjUXf
N2i/Yx8z7KWlLc+ZWDRG+JuWYqdtCizfafT2eIW4PoBiSIIWHnSsBJdkHhma+leE
lTFG2vyuKmNxrEMhlO9BjvYL+1DArPEBOvN4IRFjp6bGuw7OvcHMQAz5ccyn8cdh
r5ZrZpTyc/9WpmWcgKho3T3txY23ZvUFDU8cMapUkDSje0ZXgKVW1cms1Hre+FLS
QQGOKifYeTcRdPbdmny+KyXBCNFbxzJ1s6X9k5tjK6BKQoPE2f+LV/Yxr8nUDuKi
sP5gl6j1YoUCyrKEi7ZoteOs
=IVNg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '083c2d30-70e1-4e2d-a959-d42685ebe6c0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9F4vi2H50ON0R/SwiySfeFzYIhFjVbt53yMCH+holPwb9
NgbLANex/7qxMh5WNN4Yi6RHN8CpUrkDmneJFY/oKToO7LMUXQXNWmfSDcsT9ehq
AWqS3KqSPFfyvk9kLTyTaXsAX5+x9R9ubDMOwY+wrnunp6puztcfYPCXutLg36dP
sDxZtfAiiVpRzJanMq4l8uVuBHFEbfBXEYmTpBlUk5FfVj2LrAGIQPfGg6RkIh0x
sXacI0hYWQHD/UGJroZZJ24+xlwByyfFso8ulJAtOrcIUPxfmjKizL18Rf1bb33J
Ybypd94GBJxmp0uvcUkU5VAxlxr1LOjyUBJcyDu74hlJ2CRDP2LgEIoVbJQyxA5c
9fjeRJsRV42WikVULRAys+bK5s46SVEGcYtkYoagpzgtVRIHIlUSlHs7dATmaYA8
EFutmp3rg+YjxtViDj7iafMhDlkbAMCfSjw1NgpTSqfheO2y8inruoYYML+eDuS0
+d+WHogZ3NW8ykMMMTzPW+/k0t/8fb5zb/tEz5YXFcdc02MGENHU99xmQe74B5Ju
kKaFYiqoDCat14qo2vVIWAYB3H/qf1hr5+guBXBMARaG/OEkguF2IHqT+82g7ADy
+JxwxwrnYrw5/NPGGnl0muHRh6uRzPolWrHbfQRM52sBseS0MNk1qX0inOrKfjfS
QQHNTCFLks9CKatLU531NGOW60D4IBbGnAViOwjfV5xZZgQmZnryV65DY/KAZAlK
cGbyquGIDhfFJUUvARA7rBIP
=/PT4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0cea1534-1719-4d32-abe9-724da57c6b35',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//dWDEUJnBoXeHWjW1Pws48JWQrEBOXDuNRyagu6sxWatj
+rJmRzvav/kv1v1IFkFgzfCWldSbUr2UKmXyJWYPLVOga4jD+o4ZMTnI5i08wd20
76W8JEtaG4r1aFTGMuOnFAnw853PHo5kA/xDzjvf6h0nw9XpCUtuZNhQJvTkLtfG
NEGbLLRFFo5HmGK/Kms+K38QULDC8XyKVrAB024phvvsGFs23h8Z9KLsTO8cw4ai
k05nACRKZ9qBr9RpHq/1Z0VzwGxHEjMztWywv8ZAmMy2C3qm+kcHj7mdVfmI1D4b
i6l64YuBcBpmdPwAhjY6yYTapyRCFj5AZTtZN46y6GTcM99y2vBMM5jY87qe6bXd
D8Rms6Lquh1RmnW/w8BaUcBBxBDb0o4M+8/83GZJ56MfPt1GsZEeiDzLxxe0/rTv
zMBJ3OVgKJF0zhEm7LI4HYECrCLhvm3GXpjZ+6teMZXVaqHj7ptzFo/cswwBfMcc
gl8CbHgTBhH+riydd5tMCXNiu8uyojMBiAMtc0geF0VciKBRTSaSUmtSf+XVwkXj
P8gPiLO5o9tHd+mXihbAGH+Lvfn28zGlhHe+ul6EHpQHoBJ15xS/xJwUx1r2oPtn
hGRxpuran4czPC7Rll2bA3R1tuL9wJIbFOaflts9gmWPl7ZDOG3NVdvVt5C1c8XS
QwHtznKnU4YsPM8Bz4o2tkgK0B4llgaG69WLHFzV9Ah+occ0UecOAyifkwXBbRiK
CZiof/luT+Th50ydXvjwrkg51hs=
=Ds4z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1ba210f3-b4bf-49b7-af0e-1240e71f59ec',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Uz/h9xbl2Bmt7gIRjcm/TXvBFBEjrspl2EeSENdJFJcZ
1/HlRi9ghFsifudIDAaHQYRN/flirNFjX0ipt1/r7MXqu7xDXsmhXz5Q2zzBZDXR
aRK3S6fjY7y7smCZp8RTTbyJKXXQYUfxhFEjeKjuL6n6wy6ecEOEGzv2oCX1P2Ss
FyaCLXO5QqTb5qxIQSQT7UZxSiMyksbVdMrPdH+Z3ty252whjtkI/n8AtU4eDeWN
Mz0aS0z3y6OzYNP/vvqEQfpCyCCS5xHNtmyZKGUmJsdXP3mW5+cDbAIHYPOOs0Pr
OBrJQqlsTqjzfLa4nPIQHQdrtUdbkH7fyYpFui4dqwoFL2iLYU/Zj+lWCuV27Y/v
QP92+0yiJpOiq27JIm5U/eECcHcbwvOvj0n4TqPfZhidoDFsYYKC8f4EyKvUQIC6
cBOuLRJh7V2nGaA96NotgkVND8YdAUgMs3CgZxTAbMtc/Ofuy4wDWsdgvRsvEmXT
WHWd77b9COwFarxot7ReNojFE8qGd0yQ04mgt7Txz+bxz7W2YAFJGO4ApD444pg4
N3rzgzVLMsK0hgg5TpC/SPR7rXV1b4j3XNfByCqeOP4IGIU5awSgbdh+lG0IenXZ
DwVcfCC06hWO9YTWCy9tt0y69Ec4SB9KAAgBER9ICP3IBbnI3mMOxvYfhXxwiB3S
QgHoDKXNUqoUPt9oSZDXS+fGRT9O1p5oM4G436RwWW5jXDiX0oXL+mr6wEmiF2YX
KnOqFL0sXt/90hsJ1CRdPATCtg==
=TJMn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1e6cdbc3-0d7e-44da-ab03-35b5b7558202',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAhd7RxIYbtxvoEd+UphA183vH1ZPjygsWNk9kkNBoE9BY
BvEd4iwqwYfMbJ9wRefcYCkQ01UH5vdbPBtXC+SJIC3+zff49VFvOI3MhVAwhb0r
80lU7MTdCUbRtMMnIyV0NDBeSQyOs47KrDL37NgHY2KRUTMjGI/DfeRdr1ODJDAz
yxK5U4sMjXgpR7+/azAN8+nedlEUNe31m9S8jKPvF3kMASDLcHOs0fMMhgjOuVQD
MxLjsnMODDTPN2Bi98Ij5PRTkDqwGCPZQIkohT/IjbXG7evpw5iCICW6vpmd+Dn3
pSrpIUOCffYSWbwj0KX+95PY7zXeBQv5l9zZfVkXe39Ls5ZW169/8v8BKFElh2AZ
5U39x4VFlAbPIZaZX9P59ksfxrcoeDLzVR3v+j+RaEQMlP+L9y7KrwK8Zmw5HMDR
42i95H//s3jdaV8a+JReHnje9IzCmnTdDtawcPvYtuyyrgU7Kj/NhZHxqHIUL4R0
URaNc5Y+WDZd1qkcv0+PIHlNsvSqDMjcFeC+eBoL+juLeQYuFJU6X/a2RxCvtjPC
kS2UHcAMmpzZzEpHT4RFM4RpQ5h/FSpbyms38WfwoSeA/HaV7kLo8Q5GonDS0HJ2
niSW4teCY+UFUb4r0qu4Puwh1irWgofGzHOGD6+MNwqG/ipMDUb+1GfHK5Hf/+XS
PgHx3Rs8b1WH51p4WntRHwIkA1hvqSA60DhdcZPZ3eJxkH0nD2dOuoe/92a00k/h
K9bwbQoARHjV+uZ29XFG
=8CaT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2d431baf-9892-43cf-a755-aa4d473d1351',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+IFszRiKek/qUsFI0OcQ3mmUlkBCFFn15fSQYPZxYFWzK
7gPKTb1tC4guw25fsCMpKui4d76POPZ9WACUpU+lN3JEIKK4oGkLlWGQ4IZhmF4G
N7t5y4BU4lJES1EPQr8NMaghj/4IhVzIaNui1QMad/QLFCh1JJNmhHbE4BCgPPpD
xah6JpH2VUGELZ7P8tV+1K9q1d3jGWRsQp8zIUmJ6YCE9Eu+RPlITGUy2hPtA623
i8vQmRrQgtroTlwIoSrb7QaEdc/7hzroS16yfWwA+F/aJs4iz1Deccp535BtvYpG
FrFvQj1VP04l+j8zeZdb3vPXL+GG8Ct0bJ5JTJzN8EhR4zEMGyHtjcpzz1qjWibC
rO/h5t9UDp0hKHfQKaCP9oJo2Cyu1Zv7Qry1WDKOiuN8oBU20QlPxq8OCiA6oOCG
L5WjOcmQqxnOTqS4cKOndPur7jOVsvcAJ/tzMKnn7OHgQ+2IHlGW1DAQnv8/IO5M
gdoD3nr3nqjm4PBUS8Q1SWIXDn2yrModo8aLXJeNojfJAlL2LfN0QAproKT63HGL
XaWoNXIMn7EMser4je8i4DT4M0JYJ+0mH71+jx15/hppRLEy6vOWVBuMBjCJx/WV
lVJUqd4xW4G3fFWLETEGr5JNfoWthpX7quHk2rIKjbjea9JZZ9NaPKEgQ42Fx3zS
QQEWV3of5EzUA9CwDcNOIDoz2DGCCIm4HUBVQoSjqLnWb1kCkJAMb04BmNi2k1Rf
oVoJ8wHx1I6CORVTSChWbbpV
=Xapn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2f576e11-e0cd-43ea-a03c-6bb806a42f23',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Ui9pCQ/N8cAXZuqZdjA/1Vozf9xojQPxNul7ULMRrn+3
Re5LZuBd9Q8s3qOGIbNKiNib7dK9LG+xmh71QOaBiJvtCIQPtrOPnsQdnn/4BVTi
PBd8TopX07SDntaImd2FjFJdOE5KPHhF/43NY/BVz9cf3/pyUToaUwT3YKKh7WC/
X0ttHsSB8jfL3qpEUNoj3QGvUajv7pFMG31J+Jn4eaanDT/yd1o/6ISbFjvKO2Vm
lwSSYwB2zFJjEDkbeTs1i7kEJi4szLjEU6S3PDStSFSoEddy6KqZ+Q8G6nrY80T9
jtHq/+4JW+B02SVHqm04qBvlINxahYqmdgT+G525NBZmQxEuetWsIiXQ9w+FAAFU
le/kLh9pH4yecBploRI1hW/+q5/6suDmH4L+Fs+nJ52gX/+iUQ8m+I2T7EgbUwaZ
JPmHTZtRbUOXNWQeGU41Een9z4OBg+8ne+E5TnDNcENiKzVjk5B9bplNnWOfj7vV
DJJDA3ej2Wtrte3sGwwiuhF/7cISw5IimaFAPEr3FdMjhq6QXqvtNRvBZ8UjhNwz
LESg4DgVf/YQeEcmzkxCw4CUvc/XaG0+CNyXOHVF40OYvLP/7BCiVWNEhSX8qO5R
okwTtr6owSQextcbvbt2B5GLGuiDjjzwBh3du3fpIZUfJWpv7MEfQI6ekmtNpZnS
QwE38S64zr3hwWYdoEFR9IOKySZSLUDm4A/lRKVbhCsUu5ULUeg4tXXFhxvd4/nW
fNkTOb+AQH/AYICwDe+TSQHV11I=
=1Ylc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '30f7f2ac-22b9-4d95-a111-e1fd9173f3ff',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/8Dl7YkxS8sE5TPBYwX2l1hFy0ANZPOkQjLdCBToOiz7uO
MpFzr3hdS6MrgVMhgqwPBSLDLOgeW77Dd75IkPMI6u4642RY2a1apwI4KW55735t
nF/OqFwHOSCNedWUx7IgmHV9c2nG+HsXWyAZlMFXtQ7roH1bFJOaDWXzH0ZSqwWF
TRynYrD1QqocbS1ultBvPhkZ3ZtYxNcGfWfTBoDqfmPFAcBQ1SbWia9T3aIs4MY8
KW8oqByI9M447oncoyu8UUj7sr7YdO6Rg3s5qMSijmGDM7Pa6s+ZZyNMn9zfFS5+
U5Fr2BXJSXvvrPDOeFhJWeI3wPkNdZO8dIq5NAW/6MO/b6NctIMmxYGfbOTbEYI+
d9LOlHl1QkPfHZguD5IS6KEQDnD+bhCxar6Hl7xN8XLBim9VopXkq/ZHw1ZoQvLK
n74to5JLnunoa6LXI0LgjE9o910fgikcmcuYV1ffzsYbZj0xXEShiG0fS94076j9
ATSA+ssvsqFIFnAiHz7g8iiVFCv5pNzjN481tUUo55miXDpLN8RUZR9M3x8bK4G7
HIFKxnx4YDHs5bMAWmQFrc7zM2JS3tczjVnadm5icnlhVkAoqWwsUR1H18APKEpL
RXhYv77wGFYLv89JJ+mXxM1qwql6ETj+g4pijJ0BMAa8KdBpPMIyIuQS7asA9rzS
QQHwJZ2imz9B4x1E0+8MjAAXGkOfEH82I3eBsmqyApxxePCnAD1Ok5hLfw1hYqEE
8guSRIXWKZR4AfCK/WxggOcy
=x1Ag
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '337691f5-035f-432f-a605-6a1d58f59496',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//dgyYiCe8zIIgjXwngIyfihS4GnaoQ5r5BApaC9it+kIO
VIP8Eb/bV59T8nGOt+pDSP+OWl29OxKVOOYDg1O9srtjL8MaIkBAbTDlGOLH7cpq
u57EGREQ0LqCmIawNEn2/idv24L3Iw2qdCY4SmdIucKw/3v144j1LAVSF+CpLiUq
XyMRWUbKjFeefRP6cjZMct6HIqbAjAHWhDUZAmBXm1616SObpCMD4SU3BeB1flz5
piNCvyXtDfpr+a6rtWEuMSYEv6SleX1lTE8pmrh3KMKf0l07n0B4SlL8CQ+PQk6e
2iv+qYynnBrbCmhhybgS17D/0zSCM+ZaVzvT0/honh/g5vI6HKQ1EoHHE9BAiQgX
ZJg1TB9/Qmg5rI3fTp72qJbBx8ynh4nGUwOmMjmhuTyVDE3RgDCM5ePLtq709YoT
C4HVt6Nuh9IVffFT15ae0OVER/4I+1lhCQgXxTswovqHuTh8hltNFvWRCgyTx8iO
b8HGvmiRy/GrmC0V/sYzpm24Cfb0mU3RF9laflhXY7a6oAOghPCVj5+NyZWUAKfc
/SHylgHPXuu8fsTgTmoQoehqe3OlRSngpAWoQwusJS+nCZj5JS6Ir1dC74sxE0Wq
W7VXw9U1bHg9TyguLO+YWOVk0Ci9dkPsHR0K/14lxwsOFUVYlnyOBFWu6RZvMgLS
QwGX4+m8Pe8s7SmdROX/RuSHWDqNrNl3RG7/35RO4/nqRX9Vvs0MNopETbA9qPWg
yzTOWpfxR1otSyUuU5wq/Jt0Rqc=
=bZL+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3981295d-2416-415b-a133-37990a018d07',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAw/rxKJcWjk5i4bCgzcQWUE+o6udKaL+0TW5doB3SVROG
m869tGm1dqcr0/uMgtZZvT4WQ33r6ClYBpNApFDKt5rc5Qab8Ri4lyY+/p/yC265
xqDFmV0aU3DMahrD20mZU0C38gkibS2OxQx/CjxMSsGC08W7gRMJzXlXl/BBPBvl
nfW6feBD/0wiKG0TKoeggdNnzeDG1p5Pa457KBl/fxW5JXdZXQmiV/3YovLAM+fL
i9xoRzG2c4lAcLp41PFmDe2D2bMkXtiQeTVrjkYz2mSD49fj3YAwvffkLEf6PJ5y
zNNFt3alxS6Yzgh1zwJMSvhvTOp0sCVZECqm933nTPpBm/XIxbmcGy4ji7O5PVmF
bcC8stgFz8cHnQ0lyGtie3xAfULRka+v2lxfj1LXiDY4Oxzd3WnqwIL5BGNvpkON
M1xY7nX5Yt6Gl/6M0BfKur3etY+y4K3k9NdHfUeVhPHRMdLmJq9Jed0wihvVqDLf
nGONRM1h5wiDrpaMAY4aq6v+9SHm/iYaF5Pa+sVYdEEcOmgIha8Ir42EpIPDJ0ZE
cwk3jBmzqjDxBz7uUz83xFC/Vvl1ebtfwFcP1Tqqor15Vwlesrgk0aCXzm9dlt5F
L1ii9+0fsOn3dcIp4mmVclSqGEcrwUfmON/hzzXGKWxEso+jJtbTX+lJlXscLprS
QAG9enbSLuLSF6Nc54XOb/ra6kij+O/9DKA2JCvHZu8xgHSsFKV542xxbL2wtT8h
UB9Kw6dyzK9qM0hocpooqnE=
=vW6d
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3efddb61-ff31-458a-a80d-4e4cd7342e81',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAszxFiMwLcXDCNPIh8EKTsw7OLJh8csXgexvHzi66DNLV
1xGRj1/6P+0neUGNTa/vu+moxnJV8O0Ls1WagcEKiL/vdh5Azw9KNZyUPG5Ph8/U
UfPMNvvBkSzMeNmkqsLKu/E6qlu+zZlgwZmKLCkrP6Xty+MFmeoA54Khabc5qjX3
GGN3ND2UGmHPGziEd1m8a4JQx8JEREe3lCr4M0Z+I+UyuV2mhajLCpsb/tUlTvPf
0SbpRAGOut5Ro7N7cm8mdwGmbDA6SLbcma/X8GJFt4tGdY/1i5BKXNGERMGwgQrF
7fPbQ0Far/DJ5udS4JeuoyKzQCUfSnbib4vzF/3/JI38IP06Wqj2NfbpgjAi1nHo
m8Hf2GvfXbQZuiIf0GCVQbigg0+ToJnajhOGOgGl74XSqCRpXjBY7RoeVD5znFuh
HaXmaO7QZvRhSY3w5xGleL2Ggpp+jm0JjGcJDMplINsO0CaakCLYccfxi8YxYq+H
zdmtl5Jy0EjcazsAlcJFyqWBy7qWSl4aR0NKupAUFQthO2PQ8hPcnSK5959qPa7c
0iaCAkM0gJNDYlEKRzrL0qF8bkEdInJSa0NeF/yz5odLHujckzw/NfGB/XRrrP28
bVkFGkFxBfBRJR5TC2W1NzdEvbcdh8S0rnpN30ro0nivJPGQDM1Fcm4QJPC3NdTS
QQHVfavQ7TMT5lnDrJ1tJIhByFYH55KgSFQR2HUK2MV6RVEb2Jsap1nxUWhdteoj
VzO6lsF5POxpvZ+zieC5flZ7
=eH4A
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4084100e-6471-4824-acac-7c429e1ddd42',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//SYZ1wYgDLRE2Ufo2dz6zbsCpCpEI5QJ5dZo9NeKCxIRR
v5AuXLP+k07CtlOMNZZLxeBTh29GYr1Ku6wV3Tn6sufXq4iOu/9xSiAmeq7yr4FM
qCv00VBW9lvQYc+5WbIKQNU/baCrZGNo6lLpTx7ACTCWlVttTqNmg1a1sKvO1+iE
LEPArCpYbysL6xMj6jkZdrPDh8D3NI+tYDd3hI3pGHmgW6K+ZpqINa2mEFiV+BBH
63tO1J5heM0NlORBLh2VQCy8Drpik6M1VOcRQu8ylXTPFeXLhQL+UXwUdzrq7Tyq
VH8kWTXmY1pjt/ho+DSz8oxuJ2QYpG3vaDV4pYsLFuR4WnGhV80RqeKquetISG75
Axkmi0XZHNXi5tLVo3S9SQ6zEyuKO5qz+G+hfvk0jJNfZ7WhOJshUdMydBBkIDme
wZeGPfiWmzZlZPrR7q7Rkr/hP60x/GOqYPnOPrJ047xlaABZfh7Jw3SAXCm0YXhj
bDurZMqj+eVAggU1gKH1ufusBk3QKyzT0QZfdaZrZpntic+adqEO/CX4Oj23STCt
qNDm6BYgCgUgA6fS/aOVz6wxdrJzbDfYP/BMBMvF5dwPLFGUspHRqrY7QdgvpI69
/EGO30CVJAnpmrkt1lr2e3Sqlp4vWg4GzNwrmEs4RK6qQOrE+MisdFwooEP6j/rS
QAHVTHFJmh8nAkGVVn1oKbcK8sCLaIdRBjrYuzekLp0yaVbWM8aYveMaBGm+xEjv
+Uc5EOSE48VYRDN19dMNVSQ=
=coga
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '408b7eee-a7eb-48e6-adf4-ac6f2aeff4b0',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8CLFz7zXbvxs3Wfp1PndOgGD0SopC84T1W5qoRL2Ibd7g
cOo1EQeKCQnU2+28XvgguiuqLQA1BaN8WGiMuHgVZRkcf2Dd4E7dYMwSej458Vhr
lYtBZD3xDGqYwPEab4rTCpX7+fnOjV9DKFo2CvrGX/QszRbCuqQK5t3HFW0bMN5d
OBPlMiszeIgmrMAqfunzz3bBIFDaWkM1fO2pD4PeEacdM0yiGDYPfggmjPJQjRS3
sFn6p+uL9UDEQ0IfjwmD6TWITAfRf06CovlbJF54YQCEhyO9JEHngdADnOH1IPiQ
V+c7D54jgu0OBDkrzcN88gPGGytVpytxfJnBme/EtzFawl8MoYgo+5T4Zs9YgKNQ
mnbxa0GiqyCKzTBdewbPnqzKdn2CdVGTK59dr2mxI3Ego2Q0drpqPoGZ4cqcaG9D
t1dMhEZFKA6xzFtdrhzkLjvQDN5cKqXCj0sOaamXg6XANvz2gK8O3WgluE5oPz+l
WbNWqDiWoXw874pNeeJbr9Xky8DnBHz+j+qhL+ra+q0rNIvXZZ83fDKPqwkcUC4V
WJVmI98GvNZVjmQYJqZCTxfuHpg1+mJm079r6yWoQvm3dOWVIPYJThiJEM6QysiD
2+sC49nXTAJ1MGaLYly8LjLO+KNnJn13oL7ctWHo4AxLDs64PlNBMbJAbG9MA1PS
QQGIViY+NJubWU1UdVX6ADbvgZysSRxcrivNMH9Y6SJRx88v1qL/svAJ0n3mbqsS
cfGYcDOMCfIQbEKAhrORg+Kh
=0duO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '431974f9-aea2-4e64-a2cb-12dedcfdc269',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//SOc9lwnuk7zM3niAg0+71FZ4ERzHnshuXyP80gUJ/yp6
o4hm0SeCziZVqSonxYce5OcKQbVrxG8FQLZM5InjgyzcwLEpOULYRx8ZergmGEnG
27wGsEjkjAxs1+Y2MjItpahownEn97UkydZGeIQEWIqXW8hVSC+cw4cHXixksdHn
2iaG+qzdEIh5W2zkcc9AuG09szYQSPmpP8NVs1XS7inVFqJhe2McVH3aWHViC0EE
gvayDtyodUZHtidLx5DYZrizbx78p5OKQCP9dUnbdzDZcyFIncXLEhbF6jIfnT6X
rrKYvxrKDAF6IvIE/vCdMN2Zen2aZwCxgQ5A5KKILZwFbqZECTH/EoIFVqUmoSCi
WNpqs3m9Z2bTmT0T2TC0lxPat6tXQPIE7E7czq9CVzoL/wVlsOLegOYckubhd+Pq
JyajGi1wXAGHhI6eT5iGcLoOXAZQLLv3/Xp3s5PIN6AfA07KP/dxp+TMkQGI8HS4
+U69dWhS+tpwU8fliKaJbmO6vKAwwrJqu/w4ITtQ8xT4rsyy0K9rQrKyNTKErv2F
nSwMOCpNThsLxTdwXN5iN5iMPjmMu3aA66GvZCUmRM8D2CiPl1yuifsDf2g7hPd6
7zK9w4rPQhtoMszSPzPpG1onvL8o71yJ5781L5zwfq6FjE1xVUGE3Dhkhim0Un/S
QQGSn1T74DAacQg6C+6cLu6/DzFOXtlvM8vVtKFpJ3icwsZIoiNMG500paWOm9Mi
z/ffM8TBR8TalLRXG1MKJtz0
=Rtjo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '45b5fe22-b36c-4ffa-a0e6-d81a2ff9289b',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8C38FPBKrGWhJrHprAcbjIyREb8JFnjHoVD6SqP4PWSnv
QzBKaO5cP82unCyaWha6fxhGn8g09aI2ukxeXs455G4+mUSUxWUtYT48e8Zs6q76
Eg/ytWe4ZA8y6BTV6ZYUchCA2umw4kSxOioOY/QJjBtAtb/6gDVkXZLkQ4bEwru+
ct8J0kGwRAGVLnQlphIh7HwNEOusdtLR18JiwRe3AbM1FS1Eref4Q9HuMwptul1O
7rBwcmCslgO2DhdFQaesUjCcGpAvHbcZzPH4OHCNpMvBqIdtVniafsexIf8EF0SJ
wfHAxyDizZu5oJAQPibZXfNXhP2Q+s6plsfsONVMpkHy2sKDnl+vRv3Ww3Yn5RJf
fKq1I+38kQvVjRcXZA15YfQjLYg4qMOSSEPcEs2CFhWWzZkW+QMkYKQaDnUtCq9Y
FleciltwrD541zvAh2OAsDmSgFre8ypdRgWwRoHgIrPmB2DCMlLi7662gpzZ1fUj
epKtY85GN3yCSCsg9w1hzasShLTFkSeuZIxugUHRf7lXJLHeFsbph/6wYkyx9J+i
xn9yAQcrMuYFajDRn74MuS8ptHcec6OOAwPro+RQWH+B/n+JTOgH3EY6btfIiC+G
HLmDNSf5p1iuchke6NChVJlPt4CN3D9nLtM10Kz/YkwNo6AD1bj7jZJx++ZO7M3S
QQFFWkexDYu8oS/xSrPzTJr5UCnhvGoKFH0v0bkWOqZhn6tgTDVvb+vvb47jvKaO
rOAXV0PF7jFA8OCDJjjRfBvK
=8s2d
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '45c713e2-82b2-42c7-a2b2-d96fc21ebe0b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAjbjLkVVNa+iRFPqUovxRK4TLFMkmQ04A2M1EbootrAVy
37TotfYGCpSXymsEgJvtyG3Yzfzx7OttqMw2P3S5g9wEkchccssoKUOKtp1x4bt2
Ziyl+YPUPReepmqijHrZ8qhUf0Qn+hNQE8k24Oyu62jZrhrJ4AIJAKmEh4KNR8TM
DHY/5/c23fGJubdYx1tMHIH/tHPDKb/D3FklrNOc8N40bTyoBBu43N2shrhG8A0s
RsBMqZT/LkeYpnYkYL4xk6C3uTFD19EPSaS5XNYRitL2OU2rIHw9syhyXtF4SSnH
Lv83UF1p/PegMjDx67KVXhhDRBuftzN5i+vNxRH5dXV67ePQt2jatU8TBKvypSAp
p6X+Bz0Xa64b2nobIsxjMk6BaYz2B8y7Z/yPSBiUdQrgHdDXPHx9cNfRT7yWitAR
1RA0tIw7v0cN7+37EOE2W2w4Skt0QVwpuXk27ZSMdMlol7puM4J6FybUK5nkjvso
oYf3Vai+3r385b97IXfIZDAGnZnUViEACvxIBVNO1LLwMd522Uv71y2RARdKgMR+
mQ6E9/WwdxHS8Sy8s3cM3d0pB+K8RT9p3kvJ++X88dLjAArZ+WoWHttCikJiyTDz
KDEXHWC9uav4Zwnu3f90w6uKgSKbICiiGhN+Xnlb3mG5kOSA/o+Ng//yuMC36bPS
QwFBply3oY4q4JGJgfhcThdg8ZkuM/aeobqk29wPnuTMQIvWAVKJUYq9vd48ZL8W
lXg9KeP6ZWkMIvSfK4WTMm0iDE8=
=R5MJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4818b116-b2a2-4fee-abf1-ec40e79f87b1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAApGAAajHGLht7fNpxQVV6r8vxWf08poOwgR3DSxtLixdf
ZNIwo8a6Mf/7n9drxYtjflqX5uon31bRieaC0xfcSL29ZSJJSSp+p7yYx8rZxr4R
tG5IvRuTrQmS53lGk9fgbVF493KlMeEZDRzVNodiU5tgdRi7DgKfOnvpR/CcIpAm
z6jn3NSaEu5EfuqkMaG1hFzI5zTXpuKEhsU/O9rIavuWo3bW3rb1f5TwbwJwUAlj
OFJ5P+nxFNvYJgYEIpoWf3WUqdL5wFQ35n67ATF59IjbvvS6QZy1/aFQ48P6VbFF
cIAldf4Ky08vd4pvmpExaxrhgk1KCuyRWaUTk9oD/GTTIiik/H56p56mKpsRP1+W
0NCsGerffusbIf+iRSWDqQw2zEi5Q+RBQTMrf11bX4oiJ+pmKYY+eJEKlQ6QPQuk
iy0yiWeK4uvH09agK3YRVNRRU+N2GFsudJYyqjhkqCanxDqLJfubuVbCC1kMZYT0
O2cMZiCNh7jG5fHPUvq74riZ/jMfbDqyHb9XXLurD4UTWpY+VpDqvY/nwNqeltNl
ym6YUCSuUt24pwhVL/Whsx515gYc0T2gX11a0UCJ+nxmPTnXJ0YF5FfIVGFGGg8Y
mMvmYnKRMUYXkuwN+ZLs0wTzEg6AwT/5CDh6sOFQsC+BeSsUDv/ZYO0q6sMrqIXS
QQEOf4asFzTFqsv5LTFpWj5wQk2kDnNpOH5TYs5eqEdIEGs1kN6r3HQYEwNDc+jr
sBBBLzGJoiXLTc/AZfLmnLEJ
=uAOq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4e52eb06-f88f-47c0-ae61-910d8a324572',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9HkJSqWv90xpT1TzZhPxq3l+HLDg0nUQbGA6HwEtLtszP
T9FlP8lheaAkCDO0ltEKE4VkqSQS/SVzbFB+WOPOjDL41HYletiMnNOln7PcGfro
fll4HCBm7HC7b9Wg7CLHXhGuklupdm7gKvhuvgQxn3wxqcIccfy9tIV1kO2o05T4
kGB4JXbNmaQUCiDmIOkQ2aziHhj/qzgzemJkxoL5SY+r0az+hMNaLm7S6x25N6Mc
80FBSM2QP1V7ImgprUFD6zGNPTjaEmJFLUFOvi61VtZFEx6MkNXlc1tJWq9gMgBJ
kg9GmuhohOeHHYWZ5qz54745CEkt2q+DuXMIT8ZBZDteNBTZuOeHYGDZ1+A9l2gg
er7v3Ov39XzPWQx/mDBmzDksYsKH/Ne3PYUuEgxHuupB5225L2twAjZsgwvRTICp
MMkPkhyjsFOVzpZK0lWS82PfH33rZAd7sQPS7x8zirmaWPx3ErNqmX3Ct/fzjScZ
k9Lff5d2WqoRwO1WzzlG5nFY7g5HBbRhvvFtNfaReRvn4fAg73Kxd6Xofg3u3s1y
E2bDdOY6ay7Nb30us2qJJdnQB5KT0oa7rxuPTYvrTg8N5PXsLWNRFV/5z6r63O/w
b6cmLKFSmJubKgdOi1aewTyDwFBQFtgKTjJE0/r78XfpPz/LI3Tff2JPI3IQTxLS
QwHVBADsM/sh/lizmanAb0dOkFARDLcYfzTtluA+7yuS8+x3Ad6/tx3zZCvg5Jq7
OKOQDD7zIxROqq9rs3k/516/AEQ=
=cKHW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '565cb6d4-4f44-4b58-a455-85c0faaab34a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//cusEWZGcapf/ZTYwgChpVjPc2p/bMY4QGzV+Y6v30mTO
DpEuKsAg73GXeYKw03lHxcdfpiarrnDKMX0zKdBBdXp3HxfRFvwFnM90BtQY15Fa
dOf6jQoyo/xSRC4m5XRNbpLa9ySpAUTcRq/9WU60dOGaT+ahuqU7G5TXOsg6GzcL
9/li0gf349KHPlQwi63pEHcoHGQ+EqvTwMVUkUBAksZYHV9V10YViTV0q7nilmjZ
eUU7bVB8fQghh5y2O5pnfXwXLeVhlQrIv8G/+ceWpRLf9CzlyLhLwh+qv8q1+na9
C8LbxSuQabFQFv92dPMnlhr4z/62InkNloiXDitpN6zWQLkZuZORGyuR4iDy8aE/
kMlcgmfY0yHijemE3JOEh0aywHvyATGmJokholKrGnMCpKj4yy6WedklN7EUuYry
Kz4k6A8G9sT7QwXmyVczTARFZGmd/oNPlRIFmDC7PI5Ua+wADx3NFRoWqGEYZRmk
93wncJOOXS2o0z/PnoolGBzhVqe+juhcIe0bK19fk9IAsEHyc64B5k8rqm4ovrbM
pjIBwRTSk31idwTjyTuWRvfo5qnKp5t0Q66sKfoh4nJNCfeyVlzIlY5FMD275/v5
CBRqbA8t5EcuBaj47EzEMtttD1Ga7tR+Wf3qDNzu0Ouccc6/iR4Sm2Udk0kmx1PS
QgHM3B3FSjvbfAgWAWcVwASnggox00Pc6L6cKlXEgEPlvpPtFXZRrcbdATa1pywE
kxXuaRfUoZXLhe7BKIw0pM1aWg==
=Mcyd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '583c3a82-1077-4f84-a2ea-98592a1c556d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//UKggYNMFblJvW27Wyps7Yk7c/XbxsDQKAGh7PRpZy4Gr
KhvzU4JksdkxPXdcuXY6oReSgS2ouST7dX4J+H45PW8UzcACUcux/6VvJh+NdRz5
TisZ3QrPzE6+GWnvXnYMCseMCRB9vO3WK4ChUtHeJjCdAXHL+i91L+GTom/YFtio
0UMoaxxuysnviJk4fC+z4e8fzsOdRQXiDNqmzILhym72JHgkwhzgA2FEv3Zb91YD
Cw4gfCVJn/jPboCz22bQaVKUyicNIQHcJQ1u+kCsh7qnIaD/Q/wnvPRDCkqTL5hn
8wMmhAzAjhX1C4FfMn9fRGrTXxgmtVi87nZY8ItafwbIOS4bZoCM180YKeX07gBe
gk7RHU6Ob9mh70FOXbmits6u2i/wRuYAprCcq0Voe7iD0zRYYp9/Qi9slzuX7+Vq
I0jfOT34cXYmCDIEQFIbuZY753oftzNiuQ2wDmNy1Wqr0MdVCrT5NjrDmKKkoCrT
j7eJJPoDvCpIo13roMOmQackl9Wqc1H7FJnfwozgPUHI4Wc41Uo8KlQe6UTHl0Ww
SrqiazYrv7d7yVGGiat9Nsj+RV9ewvmxANGCfb+e22IhbG8SxBLa/szNhY7UJQyo
wIa9LcKGA+xZyfxrAHl3B2ynnItSnnMRuhpa3vcqvfy34TSubCe4QFB2Vsf2LRTS
QwGcOv789Ax8H4pYcrVjx86Z/hhmxYbjWBcnyGI0m8SEf6c8xCURZinY4OFoNB1F
fmDZOkGJBG5sXf7rECcxB1Fzqjo=
=FFmV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5f992577-bd0e-4834-ad29-6235d27ecb39',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Wo4UVgy1bJbU13b6mNquWzoFixrXVNLPsyyAf0R1urjx
WqcFjuGpIOrvrZLKqctSpt+e8JRg1+unZR1huNaLJ20z8cHZ+QevtWwmCPaDRR6l
KrR5DvkBXGh70UbR4jOsA6qG56DNmQswq6ZEtzFDMTh8eZBhLW7dgWKZIkyvsz1a
nEmKqsIjZkC8zOaoxcFUUjaSLazHrkxTqebaIMgzmyT/4PJzI7WPHZGTxYJw13Gt
Kzy+3XOI3RaDT04UssbxMFkFJcPqR4KkDKjpqIuzeKkHTfX7TmNcjh4bSX1opdSb
EjXnp6VnCoeM2+9Y5Kyf5yMI3JD1mjgw/IxeldCpvpt+mjhNPP/e9Kvv6fUNFPrq
aN0Hx/sXI/4fguq++pUmWwmF6m62uAApJ821eNny80BNY8rR8wIW1rrBNenAzIXE
Bes7tLF4Ifc4TR4cl0Ffv1BMIbYv+bZbwZatYLUREJTe8HSZKDo55DofocgopKaK
7DTqWL3H2gv5jG5+391V9QjA0h87Cim9zJi5d6MS7g5mLg8JZUeDXKTv2T4BzdL3
Bsbek3d9lGLYhDZ40W9iCjfPGoQj5+7EHrHIY4UpaudcE0YUM8YK9miQh7TnCe2H
WgAhpa7koOnE6i2U0p8Ie7SOs2b1ACS3+NZJSVkbHerdZo5tUoqNeQCh1dbHZjjS
QAH8FoR9WMhpUQXKFXmOaoeHTNz0jBzJjLNnfffuFHcVLYsKN2f3+/ftqRvnImCc
yUm9eaE8hrzknmhIN8vgy64=
=Umjl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '67fdfa7a-0635-4c63-ae1e-d735d1582924',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9GEBoqtsXciEdGPGgzL9bgNkKegDSEWtzZ94BdLHrJ5+U
Z1qobnLg5Ti7aw149zJ4Pmq7Wy65jU3zOoVHqyTGqGxM8Uw3dYWykhqYkoxDQLkC
Uiq/vZq/edII7vwYi7sKcTwKYu3n9rb3PDhHn46qdU6vLN+oPDXQDg+caLmMxrZ3
DRUB+cfTU2adlBzpysR95XBgRh4VYYZ2qqj14jn6/VKNiXUdrHypwSTcVf4CLIb4
Uy/U90GvIjZYL2K3A6yU0RCFsHAQUZUNrfzPGfvAhkkQ4NhEtRJw0cYNXIsSHNIh
/XfDOptQmSaAYNJCMgXFqZfsW4domVsmJuMgG3WJhjxQRDEBncKzVpvaNnzftTgY
mPU8M+8zYzpXeo1/5j4urxZXhjTXi9WV5mn+NQzjGdlR/xm4wjCbbzjQWL+WhvSX
F8EatbQOysOfIVc8fO8Ye0F52ptbfoO2rTY0GuggdFBOcmwnsCHu/ZSYeamRsuXb
7+4mqRXDMOVzY6Lc1J/ZEjsjHHa2GvSWE/LbvmbZitS132oOLyF5HTu3xuMqXoU+
hi+IkWHalLKEF8CQHZLOI5E9f5O1gzKZjtMDm3CvjmrVOiBNXtz+Nnapmk76095F
QnX0m2HkJQ47LMEgZuHdIAhwjT2AFKWhLKoATf1SR14ZdJDlSRFfaqpWawWprabS
QQHh2IDIwYdmGbPe+YnZvKX2Pt6Ovk5+iWIyOqKdMaQkkcbgS/hU+ct0I6sczi3w
HdJtJ9uq8mb9CScV/lAv3R4v
=uI0v
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6a554080-c334-44a7-a0bc-ace89ece5ad5',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//eji0vqe5YWB6EWrEX2+3+4eA6facg0ULqeNg5dDUkJtD
FRPsK6zVo6v7LklEsIxAfdDqfCy8UE5ybhCOZ/v/ez0wINzZ+NcncLx04pjVI2yw
s9q1rN2bvwIJEs6efKNI88fuBwvCclRDzRqFXczKfDvIn56u+bBHvo8WDqbuynG2
vJdTvPjzUFa8PtrcqqiqQ9fUs/j8UNtpWCeYrO9WxXxBPFxxLz8LzS9Ml4ymDSl7
JRgl8eKF45wGe1APrTo7mqw1fCZMPEbmKa/A1+8rgBUStaBWRRMNaF29SO/Kaozn
2ISwnTGu9ZObgJzcGajJGqBXhU0PzThEndVISU3M2kx9B6X1YO80FXHBDOsL7qRD
Y1NseVjZ/Phk7DJmJPoSq3jWUJXXn+X+8YhOLIWxEwxdjSfEvChvC3phCSoIV4jG
Lo2E+scJkwMA9qrlQzf9V9T67aUxsNx6khWn8LBnk5KAvruUw0BKoH+qP6qcU7jf
3bSq+zKonJlBwHDTbKxBRBVaQL0hX8oE/11KR6W8eoxgSkSbxkmuBt7JxXOFLoNk
nOsXoS123bfuBftthxGSajJmKoz8U+1hmvhZZXQtSDh914Dj6omQejYEzj2rrCMO
KR87r2zX6ozXja+31HoA07U5Cv1rPiI5AKCjocdJ7RG6irtu2XIRLiu7K7KPj3DS
RAFiw8zaBOdX8EQwQUlYmOWOHAGpKIhR0rp7VVPaXASNjBy3dFXFzrQpL8kK5HRG
W30sCL+9Rf2e8O3dOelY79TR0eEG
=ktcI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '72dbbf44-46ef-43b2-af91-c3540d842f62',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//RqKsVC7oTErKABBe6YpPpKSP0xK7SisI7m/noOhu2PiG
6erQC0m4tZi9x+ASta1rBPsqmhF5ivrklp5LP1R9YFeeTmLHh3DIRpp+XEDEUxz0
1RFI1z6UN6/89Rmsl2YLMC9WhZaKHBmm8CqNVgcGBn5D4P93llE05rCc3jex2GrU
5EUADT/6Jri/VhvCMJeyi1WIbUzPAbIizvbL6R5xERQJyINNP8hThQTs8cAvudZE
7klDynePQxBc+J1m1AEnBveONByvWa5ZUfQaaT5wHkpaf5x7jhjFoSPknCPUzjbH
hy2sxpL9P3efkn6u3x79TTzF/sa5IhN7f9QBkvb6z2Q0qO4teIcRZVYlHHQ+l3gP
HnpFb+FclsZTi3i9dKijN5gax8OG3WVk4BX6d6GZjbzfp6q24vyO4ZZDw2+KFzfy
aN+xmszUHKfv1b2QXFxpWr4rFuFkSoeavbL2OIjSLG0MLvmbBdq4stY2Mk0H/92p
fJyOv8zdWTwhN+oJ0VfwRx4MEb9P2avSJxwOX2C5vnyV8YKnacQN+7GpKPCNe7cD
OaoGXDI1lMgr3ww5IpfEqgLZbkOdv7KslMRVQl5QqbdrGk726AsWwnliWrauGsvn
WrpC+P2oxU/RkZAruFBdUTO/mCy/tg9WMdGtXoncDvUZZg1RVOULKk9oRDskNELS
QwG7UtGqoMyVX+PFBLEZaKWgN33NOxi28N65+RWJrTxUW2VrtLC8LQexoxx10KEo
wqucHQuCbsQCPrCmfVxJxi+52c0=
=EPtA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7547afa9-7b40-4252-a5ec-9af505b88790',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9G8m2eBp/Jwv+2RpSBj3yirBYmQA8cdsT2rI0cIbaSEcp
oZW7lBUQGjbFFMTPGMmOpPkxg9IgzA+KglgpoL6VC/27IhB7tDHezYPmt83tMo1t
IhNi48ySfk9c1tZL4c4R0+n7Y3PUlg54+avueVQQEhsvsANS//pObvUHqHcKouq9
4oFijGcfyPeuj9N4y0yMlBvlQOx0pifa0nBNsP2hkY0x4wMWGUWAFNyJ5NfySemt
MJzwPedoEjs/O3OliIhDTcOFu5L+5WSalUGl0fNzmQzzDlpnw3Ue5cLoo1AizJ9g
ErgcLnfVsdgDVuwAf2GAv34uhvQ5IRt+kkzF+fIC8HXn7Jz9D6dpXacsnjXvmVeX
aSvbZFqm07XlimWmDbievWRsAvdHh43tELu54/lT+nSHdOd2xSdzkbCPyyJ4uAc8
eqnTj/LDrfXwLABbKNRYEgt4ldGIyG9qZRwhPRt/au1wLk0ifAmJ/XZHLKMRWo8+
9V5SXgrzLCc1GNIgSuAsncLmnlbdJoIE8QikRRYlPq3RvNxMs7CI4iHGB5C+Jzzx
GvajsmblT7aRbzOEwAgfwuJcQOZWgq7zJThQkgR7hOtw7crbryGgfMGmnNPqufp/
xkxjTRIHJbTwQkLgfzeRPs4tR7cWoAvI+a+J4JC+bHPJ3NpQs3T7o7X8VQ2pPPfS
QQGaQYhpsvHrt/PvB3asEUiBGfERQIcsbwVWj7AMo9v6sN9cPxEiwldBzMUuBKEt
1dN+e6XJt0sc2wZAAy5fFo5m
=aROE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '754b4e7e-8ecc-4b8d-a597-ad45244a901b',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAvT7UDEKB/wFySCIosIzjfIBcz2WQk+UwKgmn3HjY4HXn
9sof4J5CZGlySTNiDnUfEhvjSQhxWVIm2T124W0kkrbw67P6gnYVWPo0zFWc505H
/45D2BQJz4n9gY23vYh5xDmjSU4TP5RU0aRuis76WaR/4RJ6JeaCL3JJ47XzKoqI
U6cYmb8ZN5P6/f9rwNLH0V5TyEs2XPCr1CN0IB64luC6YfgIjjYKsnMy08AFW6mA
WQyru3E82XzxQUthNCe/sdjwXbiKK/c3xuV28iGc/Y5n996Th7lBMrGYpg0yasrQ
6uHyHNZlKt/yDAHAdjJDf5bADhAJ3Nr9teh3NgWJEzyPQf8iiwn0HOpfCPrrctBY
D6Pjol6zTvZGykxr8SkEePblqxX9wOv/csv7filPPM1SLvg1FpfX6PByPmeGrtWH
I2M+SLA5mFjCU9Ic6dUw4LiFxRT9Y5JrKdoUCQJrPHHcomDkc6D4TcGugKatswxQ
j2B9kFEpr5SvMFEr4ZmCu+DE15yG0cAESovxKUraddC9AipFmTm38QdaVeo+tAgv
+mEyqvqnHKcn/uUJV93ezz5dpbYebstLGFWv7faIOkR5Z9uL9kO5mj+cyxjo8lXP
4u7KMTnAkc5bKijYoFdkPmahYrcvQRyflve2tWoGOpvybJMBXZCVbF8D8mgTX6rS
QQGZ9zfDR60v3076nrlwA9S+KM0KHJ7y6IEbgM7HwZIelhXwHpMs4eWBcy9zZLXq
hTi+MvLvlBQLZvyjUCGbq24Y
=W5Lx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '90f0f2c8-4760-44e1-a4c5-fac7b9047ff1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAmnlkG/1V+OVZZCVxncy/xA0zClVN/O/xjQ3Faa/tBOAo
MOvVpDRuYoGyW+SUlXUvNpR/cJco0S6KkN77dFUNDYWEoqC5zlnUR7MYgPzxOkk7
nk+gYtUX0RkC5hoXqrHcUwZ4YSnaKrUW+z2ItUAkoX/1rJglmMW6t99v6j9IfNZh
3+09X8mcmaTJnXoli6v0Oq3wAsbty6LuZ8hWpjg8RH0CMP+PxHuG5CT9m1wle6TN
IxlF6AXIDndoltjJlbHgnv55JAmGQeVcHl3/KqaCqtg3MsXqHiDu62BC5TedFDTK
2W/9xyOrchxqqgSJ32j5o5UBneOiziRRUygxAqXcEsU2C6n7Rugqk+TsQ2J3Ox3k
cxfuUwpTgLJLDktJE+8XSGfsNq4SvNZaC2uMxxG0EbV0C3Fv0wT/4hAAZZF9ZbnE
P/n9EafB/YYN5Rps/xUyiMRuSjhpJ/Dwh/ykj6O9kNOLGWS2sqNOxssJoUQZMSml
sbZypmqXCWqLfgr1ix+WcNSS3G/RfNkOrjEixRRjhhRGJmJwqUKVjMXQf36ik+iw
3GFLk7FA47ppQ/WkVEmF1Ly3VIZvlzc6n2td8xbIUScJgV3XM7GShccjAQqy2ESW
j/i9l4Lb61KqtW7WCHUxpRBcGLVzbPwUtE+IEP6gU0Qutafy1PYwdYrWhw5WnF/S
QwEaoeG7o8/PVTSpAvuOLNR6CbIciCvg4Wl9nYk2kzkKL+xwfM+yb6+eFusELWTz
WPQvsLDJqOK8HgwMIq4RZRKKymQ=
=oekl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '99246557-9cc7-4b0b-ae9d-0e9114648923',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//ZWFIjZr7FYSbdQaH+Lv0J1auZXjwdSyw+C/7qNuaU/F8
qEQF6TDzcbCM1FBiMSLYsQGQZijmw9GnVAUpigU1dpjrqGFptrTGzkpZ31rMht4V
ae/MnzhLB2PFRDYVCYFoRh4fRkrWICpjkGv0kA6+GbVaba6kLRRjw1K55Qwv+fZu
iLj6nejEiIUUs1/YERr7HsNJFgASTgpxIDY3QCEZTSx9HObUQJVgp+XMkWTTruNH
cOlhz6Z4hiaBMZVdAJq9SY1GkVZUxKEh/PZaYTYdBwteWcayqSM7wFIND8R+9ilv
aPc00Pek2oBL3b1l7bs/OruKVTVWIMujAm3c3SjbTFdCr7Ib5lkFL2irA1ed7WVX
/oooVOZ23xZ1QyRk+9C7viMBBqzIGLbYeJU+cxbqojk6T/pnFazL3OvrnvJZByow
OC/0lpkIbXICAL8RWz2TpAq0WUWXyLV6KcGQqNKxyXWxnWqmYoDhQFSyhgHAA4cv
bpV7DH3eUmJuvpoOBSUKUFTa8XRDkeS9b2KmM6D7Q57VZcgrjk0mB5Xyuqt1zf4h
a9eWvTPn63xz64TEqFfQjdLae77SM2e2qFMkRY0JRfn2GmfsPymEy/eO9gOGHM5s
B551qZnzhM3zRt2WIXeAYOjRXQDiHwoHqG8ZXd88v5DQMdkP1UpbC/Tzm9g1FfHS
QQHHu4cWpvCF2NPJnSAqlsHbH4qMM68gFbUGKQ0XeoV79Tkvkx9r7M7svdlYZDXm
4nHaQ7aHlrT2RQ+FT0ugQ8i5
=PwdK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9d2af207-2ba6-4ddb-a83a-1000b420c84d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//RG+8ZYigV61j4+y+H+MXBihG1LuSf75BD4cwgwDkSP7k
PgdtneIZWZ00oZ5X6ilie0uJACTdv/FKTCDJtT/vQjGaxQLJwjRCQDxrov1spR2Y
GOBBoUfkI2KaEkzaqBTTlU86D4c35AukmfXRfzDUC6n69+ltmZXunKedu3Zjxe/l
5YaGUe483j9m6egWsGAg0IDOk5ZvsRB805IsKjKR0lcAkUp4eIAh2n5q7epPDnA0
5kH+SKLAvl3wMkx0M1Ozxcyw9/tovjncHf0G28xVilZB09isjQVQdvIZ6BKYo+Rn
pBZa5Byoz8GbNKv+k+gaVzF0aA7UWyylNR5Mhd2JfyDyhqvfk+ke1dfVdeWS4hxa
1OLrYaEKcw49I6e2V7YpxoCHmHFNdM5J4qiJM/Yj+ekTOLnHVrK/rR8lgcwltqjI
Y+0FuUp9XPK4k8hD/DXh7fbBUM6Z2QtTCI7W3iOAdtR1x8J8BExS5OEwFyRtEgcm
ecLDkDzpVCcbAOLhKr7ZgDyx1HkstNOBVUYbEVBt7yGsYgrY1uLVbp7Dys8D0frL
zkTyeSTUiVpsNFEgXCCbfeI93bcwmOSaova/cIPrenq4c20NwiDx2Fsc6G5HPgel
T9ORp+0Gxnm8mm5gFOIajLy/RbIP07ljljzR8lbrddB4eMUV2pt5F6vnGABjd3LS
QAF/63Mpo6FZv6agm4bI6gncuisP29Rb77m5BPELLDl3nT5VtVjYF3syRx8oIRU/
ZdoAvGt+5PLr67Md/Kq5J38=
=pzJt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9df1b981-04a5-4ce0-af04-6b0b1b89aed3',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAmNnGOPCA4I6X5e2XHGxHvpYLPAlQf2SjRtO4G6fw+YIt
Kt85Qnj1tcFI46b9f/7Da7D2t/dGyA9uqiYMl1Uf8yvqtc2yPS+grW0NiFPa/V9d
xGSrzojfWRCGscncafZAJtnaMb8qFp6p2SzrsqQfxtDdsYTs7KRPi4/5Fx2K/ES7
Y1wBLv16VaZ38vPL1FTO4cE4ZwoFlbD+PI4Tkwete5O7NLG/f50o93s66b29FnJy
QiQO/07wVYlc0CVGf1iZMqJP9ZQY0LRUJTCtDIZbZPwarIbxFM8z4/p0oKWlZTb9
tIC80ABbWGDaVfsYQbbIz8Tg27ZZ57A8/vy0RN685S+akkbkrE1uhz14GM/9V/XT
f49I3M8Y3w2jV6JWhdscyD8w2tLjAgauoxa2QeuEnwOIXLD5FFpQz3YSh+tmjunJ
dtVchne9N43kFChx9mryQN8+pxmdyVouKb3YLlrEGenmNlrFaHQ8TX4s76CcTuJg
jVLNui8I/jS+ohb1y2CsKs08HYwum2ixkOROU226UNyprcIQRWrYb4ytdGFziD4S
h9dPcHu8uUE1jH+qnNfuZCMREKYfjdu6pA18wyihfSJwKqCMjvADtYcNmjxmYvnx
eigfTwC0APr+H+OVW4NZ4jvRBZkyp60LsdFj3OWETmi21xharwWajRqlXEKCGSnS
QAFoLFBWnaaSBLKOrvzFTaf+O12k0pQwRX4tJOe4DVuMObj2Hu5YD4uzcKtWUaLW
xCt0BU2JDaMY8ygly9N6xys=
=YNtO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a0b4788e-18b6-4ef6-a87d-a71c832cf8ce',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9ETWEtu2q7RIshb7FLwkurwOlIHosOlvnGaCHlIt3/HDs
k+mYAG7sQ6IUOuGDQ69Y9yhlXoHyBK/fU/T//BfRro39iTusPzg0TK3o0sMsZV7c
FUX+HK7ZYu80qnkBuflb3uOFxacVbdH1EdfAGsMJ51dGrsTsVljedup1Y7Gix4Nn
Hq+3O15NUNR0hll/z2n5SVmS1DcFsAsLQEXxDTMeCcIzV1xafTqEeeIGaDjHnTm1
AafBXyqo86II2kiso0p6b5M5XH8/041eLQMu1hrW9CkCTNk+4DXeE01nPwRjlJ5G
tR6oX8E/BoLjOts+npYH/k8HReRIBd74c6HL/BO43qc5r3eYoYM4RiiOc12perZ9
YrVrmOqP9Rs2orULrHcJvCgUTn/86r1iWOA9Ha2YBfkcayg97pEQ83Fo3WgFvEAU
nb1vIAWBd5VJFQGcIjUEqzTCksmICL2TKLx06njJ1csaVIsR9QLsrx5+lmSSk8Le
Y7LLavwJqdgRymmSAmy2gL24+9D7Zcd8ftBX94J402of2zveo+DXVP437QuATJyG
kBlMfyxkMqBYAkugktrrlySocFZzg6PSHq3i5LwOUktbOspNFPBLdRzxJ1A8JORs
TKxa7GACUc4dKhBpiVBTehneVoOVKeJnZq9nRzeqetlEzS5/tal2RtAlcEw1HfXS
QQEq3hWiYMDqseQ+AG2J3sA5Yt2/Ma0pq9FMa3It7PtVgAQ4g+ePHLw4c5yMk3tB
N8M0/0pphBbhf6wg54YZET9F
=ddWB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a0c5a89e-c92d-446a-a322-11616373fe9e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//eKEZNVRaW0kG/anl4mc2oKYGAudfWWfsnW7873oM8sll
eisA3o3h7JlYyMSHcTFL8c52/bHt0ul17GGS3dVdP9FcPSIjCaRT5J1TapJcSCNl
Npno8bMPGHbUTGq9COODLq3I7nvkw/F2VhJEju6Una77sYmR6IVqswn50Fy7bQz1
lz9Qqe1bhVFodNQEVJKJAe7AJAd61zDvhRvWm4+3uHbmTZRo4AV/otJMan+hif6k
VSCK4N31ynX1+fmJSdgGsiMVtox7vnVWmeWPj2MosHYVN62LWajlSwq2MMlxw1y8
z+BSF/2vC/yb1TlCgSSij7Ipk18JNjg0l6JH0FKxV94dSNGRpcdV4hvoirK8LTWB
dKjqjT9xyqRENenus4cC0JNT94A/Q2pfZkfid0GfgSbE9vLxoeviwuuiu3agdSPA
fnP3aryYk1voELtSrMDPJNtTGlPYRRcmNn0uo/3tVKac1ZSLaIavVhf4LeJX1bIc
Uxxz2J9Eu5gn3LRxOY5rVn7HuJqE65hA3VRXdWk7VuJK1GKHM6xHlbOa6a/9kIww
Vo5wO8UCsoWntEL3kfyos5f485aBjfKnNz/Aec4b4qAbpW522qv2C/uRbbeIBmF8
kAMiZYl3aOo9UXzqWaR6g3C+7HoU+AqQIvJVD2AQ4dEq4v658UjvcNWVDoOsqxvS
QAFICWWQLFrznZmh7mWnIxRZAkBPLSY/GYIuQYlcPeqi9qq7XVubzMmYyJFmcmtg
vOnxubE5Fd1MVBVxVGAog0k=
=05hI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a397f2c4-ab94-4158-a5f5-6d168c428814',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAjXd9uRH7jThCLjFzYR9RLvCT2jFwWV0KEfAk4bQk95k9
EUDTsSNNdaXHkS8MpeTR+n1GRBn4John8SdJDoBxPWAIlReNHfGnL2Uknn4W5p7o
pvIQdjEMuSzZNB7wgRovMIvZYnprO2AdbHipEk7LgZSvNR0HKsxWYZaQKNIKzjGG
c64KtIWAIajC5OWHxGowXhU09v1vAtdBL2WESCz2ELu0OqbQXYm3Go5uhR7C3sJd
LpxUp6KOb3ghaPs1KloF1EPpo2sgj6KQ3PlCK3/NsKBk7F3i6Nl+HqeuSTTL3LOB
Z88HLTTwBGhk3EAEHSTQmApaSuBNPAI1yGIsmQM4a3cEJy1otf9lGfWRNp0Z7RIc
uEWl/+v/qoplr9ZSQ1lusSLgjSbMUzCkGSv5unxVKb6etG4bEyi4NV0aLJ4/5Tt5
YXPJjYbER8UaSQxiPTH1kibITPjwzxJh6shpGJbslHLsI7aa1Dd3OZZ++UEUFx3N
JO10x7jFhikpXGMmWVu10bRAt2vjKw+Ho5uHo/b1JIXuqLNlsm0oxHuy0lXRMKZq
qjY61MeI2fOpYKBHY0pLM+FW8xYee3bNnPaFTGAilGngEo75H03idI1Q9tH5aMtT
t77CfFq6VPkqHczbugBnEaeCIVsPHr1CFJB5vJ191NTQuT9ntuQI0BO3fsDAU+DS
PgH/rYOZd5e655VwtWZF8Ubt1WcQj120NiqCvQyglHhiqquVKRGwp/sH2se7IszQ
YkkuRPZ8BAt2hyrALU84
=F6IX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a77402dd-e0f5-47ac-aecf-2aeed3715159',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XLuQbV4FsHO0p671Qwy0M7BTajkTpjfXKJVzxyJJj4bY
DmDL6/GSn3y8/pIZ+43n2XiGOyZW/EG+3qrEcnLPg60/upbHjmiWeBEfQfsgrYY1
2D1UOLeiqBSD5E/kXbGWfHAnmBeOOox2SMpWM2oj9Hio9wMw9FBHiFb31TUxXaa4
4Y0ag2CQFfd2XqhigNPxEAsh/v7/ujjQNUXoHH1ZGslNiA7E83uJTxeSd4d7g6KS
ALheJGdV1Q74TIAzy+JA9JqIkVRM1PGR+mrqUwnIEeHAYe2M61LzToUM5odCEwPS
0rCdNSl0rkJPvrrx9vJgk1VbsgZtD7Y0lW5K6zeTgRPHjhx0Tyh41Mmp2kusYjs6
8ldeQD5jZdUgDovdKGnSBa3kXesOIMl8QZBGhWpk5Sape7JeUbUJSpgSi8m906Zl
X4ePvYu1e58KkARGhKXhEiEkB+W3ppojEKYgADeCnKp61tWfswIitLt6jyAw5M3x
hKxx18oLQjpi3jgHcZDszCSE4OTGekxA1tA1+H7Q7suD7AFfOYOK3RsblWsU10nc
A1U7XdDNHpZz4bTVWfERzuktwqVccuOfGgPrxdUFU0K5G7mWpUNWhjNWAOkP7gFe
0EsW8i/xawZJfOuHvvsuXYhbMcQxiiBLcyNLJAeshGAiCNQNYkF8kaH+tvxR9xTS
QwEp6Nk/a7LPdJAqlkrHmcEvdxJ0xCnxaW15nztvr1oyaKjFXdUWreG7MBEYSaCw
9FhEDhW68YQF+D0QKns+b6SFP6Q=
=Vmx/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ab7bcc21-2bbc-456f-ad18-5b30c89277e6',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAqucU9hTjaydqyvXGo3QkjHZ0ew3xJd3QQYJNfndP5FG7
eVpIrIzXsAisgPW7VQi+7K8H+6gkN5q40fSS5SBv0coKQGQ+gzix9v2MvJ6BmtB+
MOgBGnFrEFVsNGtF7MDBSogwOVGj6VHBa7zhHx/Rburs8F5cNS8qbsERhynp51+x
uO5SfoJ74TUuLTkI3BQgbBU3bk969qoDiRzRWJrwj242O2mku1FAkYMRnUZxGGXN
Itn1+1ImgQ41bp/H3zzcM8WEU2GKwjm1xWeCTghf/WREtVxk9PWCSRMp7OtcngEH
R3MbRqslmc75F4SeyE+1JJSen02tFuymcxyLqApp/dpA1Mr3tPrrD1vWx7k8kuwf
dNtk6i0yo+OMGqRNuHe3hvfPKzb9pD5VfjbS6aD3FTOxwgaB9+LQ+5L/A2f+r2MB
jaOdeCkjQHrM1WtjnSxxBJlS+W/XLQqcgiw0L0kX1YJrZhwokGQqmydE2JOzuAr3
doZ/DkTc+3zMTSRXMdvG6oCF9NV6wYct7nS9hUpdfXXCzMjfrMkojV9PG2kdwcEt
Ks+HbvEHlrBbh1GAGiMXUtPW+EmKox7wolnDVauvah3RNWRqmgdLMebl+aJgoeUp
BLzeBHFPGm+mQobnZr8SJ66A7yD16X9C3ptk2n2Re0ajA2zWPoitnUPR7MdhoGfS
QQGwmb8dFPQi//lGK2inIeWxhAL5jF3oMcYoZpYr5IY+mEHUkpngxxRQp9cxpw/1
gSiC+kKYNrEsvQpE0g6gQHKO
=zBL7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'acd49f5b-71b9-4349-a04b-e5c5f1599316',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9FdyQ58rlKTAD5y6J6d+hNeGYRKFX9IJavKPmaajQqsQY
vrNuB1VUDRAsj6318MoUHQx5QEQRxIj55O8bQ6VFltbw20S9yYVZBkmUlLQy0+kl
6yTK9mwHGscLI+/W1UKdc9/p33O7KaGHHN73cEzNdkHDkHY/T3lRF+eY1OjL/AsP
lukB+DiB1abx2csbBF3OgiafIEMTWmhGLEDjSz2C5s70/Z0vVbQXVB4QEBm4kYcz
F/oVn+eeHD4X++BgufDt928KX1b2XeB3WB7wnFhhrt9okY7fB3/NLZLxSsmRqBjI
IiKHzpIbQzydIqBypEle0Z5oXIy6XUHgbIg5O4FKawonF2u3Sw6ycBnAp3MDUdny
bp9DS20B8oZDFtdoP79zSaiyLusKOcU4d51EQYVpV1zjz3xeNn5xoSb7AkuCJoDL
KbG13ZcC8Dn37iW5O15pkK/bh2wYWpow12KKa96PkccBZKjGiDqGYAHDH9s60wqc
6rwUKG2lz5H/Y3rxU+VGzO6pv/9xKfRXlN3otGqgZ7AIaMswXzKhaaByYP1s/uNs
4TJXyuYKlc2QuzzVcF3A8uLApZ2lTkBEFUUryR02b9JRRNjyVcWuF1AyOhgrYMbW
3REUM6TxIsqKpTqtwbk16aHMADGeGadAwyS+H/msGtL5993pNTCtswpfpU850frS
QwF2cqQXDDEU6rneGx/pGn+vzQKCfJYSdCoMeEf7iFmsoH9xEB1uouP3gDhXmA9C
m7QDqyuopo9R77u7/V/hebuWdo4=
=Xzk9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ad7b353e-542e-400d-aeb6-bb5e496a577a',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9EmoJ07XARydTu02ALnzFI2l1dc8hBYA4agpSnuZHrlJu
p83vvbcdgxOS9mdk7ex/MRgLl8iD4GCOqZEqZ4IuHOMakf7MASBi/WlDPfGI8n3/
9aYRz/vKvFi8eZ822ZxMCx9W0IoU/klpeSlHT0iLDAZ6XkUe+zIDoLKukjt10Vam
HCPv6d4ZwjGomYygWcVI2ajlqPkAnd/MVNd114ffpFsPNFdlsxa6F2EGq16vRNeZ
xUsPBzOyg76ZMZWhBtVP4GzU/OoJxtHR4QptFongYy6oTkQwVLlK+2K3CSJDBGnB
WGMzjerq1jGCWUvyhyv7mxmEOGSWe9QCTVn3gEntwQGnbrLsfx6qVaOzFLzEan3z
JJfpUtbmdFzxGOnm33C8XLCq6vN61BFtlRRCVBsH4t7ardMNRjAmeGbv6IhROp0D
Yq+GpL97x7YXpoW03iQ8hoZlff53IWPvTW2Y66r/ad2lr0pa9bNlnJ/FvlFiyp05
vQv8rQpsBC8YWd59JWL/yEFJnzJF/rD4DKz4bXmjeL564u16iVKKemMRjQfOBoJL
9jv68l4ACtD5Opa8u4pTMqcohn5zNTt77HAx+iLhx52Ft0EUDTqs5spujUSADIBm
3Xkj4crTG/lMNGb1iTscHekiwLiWLvLYkinZuhCGigOYi+q7LmpX8noQC/fJlMTS
QgEifQB/pjc0cx0bNgFqmyHhvo4f/sjUZNubxBdkFK18Bsim3y3Jg6DssRAnAxgs
WJ1PFmoxUApksuDrFUxsQa6Yug==
=NwfI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b855a87f-5dea-474d-a39b-16981d629f91',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+L7rxNr4mZXoukVtI6HnmnMXaIdrUmu+NJpKdXUozTw7U
YQ0heuWv//cy7tRAYJD+fijkv1O0TRiWaLVU1srm4xZ8/cxIu8ufiHEp6bF+hHJv
F5vrknu1rKSaVaJSNjTwHROQxLBr0DBZdfLR1R7RrMqifFN74P0W9Kr73C/7PUqa
UwPFGf6Ww0R6zRtet0XBE+GreU86CmE5PCFFGhtlNl/CxuZHwsQWqsXFBLXB2spI
BnvFKg7kS4UTaxdXfy5twe1H/46YW7EabH8MZJcXID5K5GCBWC1FmW+YKODnNsgO
R0pOnAKichpqraJhWTiiZegAaSgJJ4SBN1Tm0m8SZCtowsUPeuzEed0qxAewM2hJ
EzCi+AR5vnHS80D64B4amMlYX+ysOlOGxz2cALQMBJalVSLvsXmUuoQ5Tx794F9z
NyXaCnI/zDgn1AlOEuk0tWKMNEZJ+VwGmw9VUQdomu9sarn9UUtth79/xYH+sxhe
TF8hUBJR0yMcX4CcG7qKL38ESNJ7a5bK7HWAgrlMgo4zmq3H3wpK1Fn0X8QgxKJB
f7xldPsrqKzgzVX74HqAa+ShezBtgHXAaIDrdz8Z+RzXoFp+Gj6a08tnnbzxZV6I
qUd+viM8IepduyYufApaPaEi1GR3Btg/0dAxuy6Nw8wKpmKlFL5uAchu5CxGFP/S
QwFMc9V2HKQqBoB9Ur1MfFZ8oGXbLfMv3A4dQfypA6vqPnXXbrymj9DfQNDIwFRM
9BauzXDwVISXx+1XPP4B7HmqArg=
=7ykz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bab60d21-a215-45f2-a9b0-939d6efab85e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+NTm4PInuwtk9qs2J2Ne0EXlJpa4F8Kxp1k3Y4Q3KAJ17
HB+JqtcgP7kIN/YfhrW5SSOi4obQE2tqyCXODqqR1QXW6DExt1q6aRWKwb2Wb7dy
Sv7HWfDEfd3WF2Sdw6UB+TctJ8v7DEBfm5znonknztMtGtacwnSo+G4H3GTSqHlU
CpBjasMWhmSVNeD7OaFHDw04bbDhDLVym7X9kmRrRepz07B3GjdoGY8IlVeE/Qok
m7tmcQMe1o7/yELUUhUjuk4up8CJJ6oXCM5O/zYhx0oHKGceS3LOpPPZJS6p6B40
MiHMh57etft0pApPcLnC3MbavXnMxkAmzwlv9IjoAnua3DXnjuvIco3C0B8V4tEb
BBlRv8Of55sr4JlGyTS4V0fwZeuKs7JLF8dAdZFRooU2hA69DrbB7+4L4yNkWH3G
TgwEq1enroLnOHKlSU33L5CZihBPDaWu9CZFiIwyf6dHxRiZxApKIPj0PWjhWEFw
XUw11ipFpV/vqm+/reTAE4VsZfj8lnhh5FAmTRIJHqAZft3AY+xcKLil56+Je4VB
FV69qTFSxbbOiWVWEIEbNA1py5GkKe5OeJMoDGnSjq2vy++ONdsHHx6SagCIw5OC
kZTa4GAmDyKQDu0yzOwX1PZ+l9//DqKIEmPAvQrfC5iFht2lOkpU7e6j7bZkLDXS
QQGt+2pWlS/OnsXExLugZc+XmkvhOR/mNqMO+/Dn9L8dzi9XGRtFLEnv6JVsgZqj
wgKKnwmX0aCtPMF0TGemGbVP
=NDwR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bdb54edb-2be3-4096-a4ff-35ea1f3c0a5f',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//f52WYRbOkGRWF+vOtTBUjviJVDs+vK6Y0qLVyZz3uxWT
UUWCTYc8eBMwqDDTjAV4yNsdHZJKiH9TG6t7rL5aC3T/w34Km1zQ8Xt4z//7zcRY
QKacNWmuF5GpMvI4nuoVFfu03PsKSkEZX339gsgDpkIyBZnizNoxqRAXz+B61RCH
sqI/DZtwxWaEn+h2QbR3GMz8Um0GquqereyfHNg9wkuAbgzrnLCl+BpHg0hebVGs
SS8FJLAdBij9G4qIah/rTQm6Duf2CSHrfMJB/nM5MTX+/lqVIUjEoKOw5BTzWzbe
f5iUU/TPLZeXtk/4Ht+/6yehrRetIxn3sVZizACgYYqAy5LyXeRdUIJM8il9bRlW
3UfbFM/PzyN8Pb7o4xYEynS4HXTnHf9BWpNoNQLhTtsneo1ej1oq6+C24MpWbb/u
YqDvhMi0T4RdL5Ocn9OHXM2274PeOkGvoUV/f2fZk05L6MrDqJ+w/PgatbtkKbTN
iJzXTopEfLiDI8p0OaBB3pfapbfkp8skdHuQMJA7OsEsrpVM4WVsDqmoIZbIlDJl
CAxG5GG0SIC2ahV6iTv9peP/TZ1dbVlSa+WwiY8eAlHYMy0XlZkbkyn0hU5eMmtl
lM+GlrxmyyQrcpVkyAV9qY8enDVUSwX+VLmKV67lhvYR42jSnuYQO5751AXFnL7S
QQGBc0wkkTcEsMBYJlCot+qUJvAcqGTCPNXgc1z1NZm+nUDx9IKCIzb4/ifWikCK
s8PZsfX6WGK8dxB7LZfzm4Ch
=7gz3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bea70c26-1e58-4982-a5fe-ca6c5aaf509a',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+INy9QWlz0R8K8Ld/iCoHfo1+JZ7TxiE21nEIY6wuMO+y
JYZifN2vCj0MWuT0HUWNEEIyvFkgktcWMVUeNszqUqSEAqKMohNBXnylnwODEQxs
mr4iNxhWDVYQHqZuzU7pVo2fjvq7sxk+nqFDYRltMwaww2z2IBppDTueWw7XHpiG
vT65Z27cDalruv8pdx1weBQANWgptO99N7uDcGmjsOoyE/sI0bW0I43wOPCMnJVz
ea4Ilmw06KC6+3AB19169+yJBe/YENL6tyqyB1SFEFDzHHeZfEZ7y7qYZJPTItDi
2z2v7ChjFUtmgHD2x5/R8GBjHDZrzbXSb74j7XDEWxfpW1kzIqwXfwHv+fXNDK5X
vx+CQCGedXWVfHaMaACCwtTxAJyxAfDJqG43SokUHh/b+FdBdPQduLb/ovh7T5Ug
tIznqboFA5aUL/TPgUAlELfZmp5qPDZ7d74Vq4f9Z9k1FBiHnBiPxZRSzVBnjAQ+
i7Rk6Zas3fzfc7xaD7uePe4rWcnrSUgQK8kH1WJfZUgbJgRBb8lJdbHDPSzGQhx+
FdiQ6lvwUi28I0XkzAi2Pps+Tb8vYTTXiDExC9/DFER5vTBNjxYwlskSNiQhCJyo
YD1nGH0G6AJb0CTjEkTFCYyT8wZx+eLqaUus5xuVqEyYpo2VbFBdEgtKrIC35xbS
QQHYYNRLUydCBZfgITvbzwvWjAEJr8XVxW9sfNwcmZs4z7Bram1Izii2X3xnF+af
Orm0hu38/Q+8r+AeQQxPcwWU
=yLdy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c89f87c8-9372-4e27-a2cd-d19b9752a1e3',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+IlBbJHylFeCUQA0PIapSjU1V3j/FkQbHdI8edqT64eZT
Djf5aH3rqBzbZq3YtMSeHwOiOno5mndHKiS6BruliE8I/UNhVI22fOYfdelHLhBW
2XAH9g3o06py6937RJFQlc4SyLvnpdkZExZUoRz/mQ3qnvfTN2WHBpxkmg1ijL/i
OJMIAjKpA0nhnyrdA8UBilJC+tGM7aIP4gC4URGMP6LQxw1LymIJr4VWxoZb9F2p
DZytgg2a1dpNMnZJizx9qH38yzh1VZwKLlFUPQVSsgHfvc31e+zDR1A0qsxI7ubl
37BvP/RD06ENPMw6ZDBWb5T2QWxZApPP7qYFy/n7uNcjxghzfsEeNQI5b7AC1Ii5
5ggdvV64ibPLBYJN4VYjDwMey/x+1lS2cbaj0P4goYbiJGFtBI8e2Jb+w1zNknz7
p6uj3IUZnK6yeAp91fqi5J3MCc9BsvwrP3oKVs/iFcduDy3cycnB6F3nsTZOxcX0
gV7QJLoIlOhk1VP7rEss35qeQ11TKdinM5bsiXtWDHbR/LiBxDg+RH+a5LmkZA4e
4AMwmfO4gUwwVX0o6xIpcNQPOhOYQD23sdsoLO+ku/E3v2/eJ7IHUqQe+FtWYI8t
LSGdihco1Fjy69Ie1ko3acYwPnLmNlHPQUypY/W0Bh8/nxOESewlPbXit39KhqbS
QwFgBEGDQACfYwclIrxtl4DuSNLz1wL0BeVKQwO+nnO2L69oLbFfuLyVJlRqaPXr
fgOMuVOapBIxmaL+hxWZ/qvoxdY=
=FEN3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb03bacf-b73b-461a-a0eb-2b5c56fcda05',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQILAyQe9uW5MLigAQ/3W/xqFSq3bGDo2iCrbQoTFG+E87kcqCrtDWXqhLUY0SwU
hIYZCOLwBQMAZyTIqZZyUXqucvKbabfDH14YzTCv/2WFs4acdcARAbOPscyRoyqf
ESAr3+MyHQHJWIb6kqGsX6oz2IwiFYzyYM8XvVBi0ImLucDrzwdYwg9KNMtZxyAM
8FL6dnaxwdXI0TcaHcX5FraEylpTUOSE1GFNGBqANwInGVMgVg4aOziGkbM9rz1z
9kKCcPWtkMMcrTr8vTB24B6Y/XsOoGiWh3tbz6rcMI6GcTiBfLMfAaDnOIA09Is4
qWUdlFMyFWVLUJdWY4/Y9u7d/7F0BLPF/snzIfRPKbRCdiXecO6lxbd0fzqEBxO2
LC7zRy4tEs8rRa47S3LwQJreCuB620RJbUa0few4gVyIGLXwEJACBCPDQKVV1UK+
Ir9lDdCNZibAVdK0szTnzg1Qda5+aPG62tO2jLc23fuI8I3iwhC4iYnifiwFBbbm
gvi3FvHFWhHdkcbgV6pPf09SYxlL3aBh3rLwoMd8T+NVAdz9cjksYo4CXpYl+HVJ
Mn857rL1GCBBqMrBNhPxRSumr9G+aUVyIDngDOi5UCTdNrbsXgMUAQCOe/fGuGEE
5NloH3BQvIONzyfyOBGlwTW4ygUZYhMN/WOAXTn9Tps2bgWZLQbCLVokaleQpNJB
AUZhRgkMpGDzHoNEF66EkkPEmvxXx/RKa/lcUmThGY0xTpeuMpUgMcUSH7NZmQcX
wsNK909g4ho55/gj6clo62U=
=yuAT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd017dc35-b039-49b9-ab6c-36cea50e74bc',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+IUoqHALnmf8cPnnZ3XhyLYTi/gk2L5q9jX2GAcBcQEcK
ADGmOFUIk+tDqJD6btSprJFBVE7cSh9L/F3VbFP/Y3NdiSmnle9XF3uSJhOx+1Gz
nDOdmYrcEBdiw9YycnUeYx8V2fEgudP0SPdicPEhLFBxxG0nGN67gtHq5xzylfP9
MTOfJc5UL1+yF/rE3QYyuA4+YjivdIR6tODGmkgfNC2BwWGbdbvrv4rpU9ThCPP5
zfZemK5rPAS6PzZbsu3Snf1SiCqwnEoKuJoenCtf1943b3OOeV3TiNO6r67LrdPm
TKfFK+zd6WrCUd7VdrL6hfJ0InDMRuFcEiBTJJDZwoyPuerPYPxmF83muMNuDO9b
f/l4sYn5qFSZ9ouckda3k2W6RcXjGRRx4NEe9vUt9e9SmH5oqk5J8IY6Yypdh/6d
kUm4A1Fa+TlKgfN2UmeSbFFjOpqK4c0Hnlo9T5Zsuh84GSllpsydv3V6jwq5BXMP
zPXKi+eVaAxr5el1FIbuvxzfzBwTKPd7tMvufZPb9fu9X4fSW5qZln5oAmWLi+/E
HVUio1/hBXYvvQRG4OCq6SvfzPBv1L8BYZ/z2F/Vdjwf++ySgsoD2QhNmjol6KIg
1SqXSaIp9+kvwjuvS1+jAcd4fB/giLlasWPEFYF1lSAQjnTHPqp+XzoEjx+vh1vS
QwEq34WdYCkR8xhtHgrRG4WJGFD0Ex9qijx+Mt+t6EAgM/7CELqOGleq/nuKlbr3
k3XdNaKsgalQeqDGJrni96qzEbY=
=vjBE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd3e92d41-87e4-40c6-a92a-0a862d863d58',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAnkr8+Z7T4EBxvP1pZgKUjz+s/wfcrU66Lm325+3mPAyo
O6gOOasXUVYbhdOgFDIvK7TzS2ELPUCQDRerO2pWrvpzkk+qSzchk1BxpkBgPJsg
0DViiPHRa4dGayXYVnMhzvrnbif/CIn73LHD3DzpriD6RY1uO1sAyHddBJgyK2mD
ugqxBjMpKpyPck6Y0m5+Bykc0wNREmTSY2y7bzmzx5bYbZo7ZvLTxztaXvljeJY4
Y+oNwEBpBkc7ZukzwPi+HPmVnhHBb818s9wnB2dBQ0FpxnkVgQL5hCCa1TtcUG7H
IoHL+0OlbOc/+0ZbNKj8jEyiXxaksKMcBjzYg33Uh0R/VWjNTK0v9gwYjgmwmVmM
dNJBeb76YcFeqMDNk5el9j3IB8ztaHG9dR/rsrf526i4hWQqLrvO18WROcpaP4/3
RnDfxnPh1skUwAKa2rL+QIkhHvSzBUk2VMVNz4Huec9OLsOf4r5MTVLhClRypIso
dqzdiy3bxhhnpPSrhmOP0SAaFbdEZTsLfQxTBoJXx+suoRah1AXREhKbTrkuEDik
u5Mw0IxAq+Au4i7TF8lznp2IbgwWKcmLPawFiM4ao1ISTeNhfXcSgWO2WPdNcGGX
Pi0noNvTEtr6e8GRjLrMChehRl3Y/T+xuvLRqGSSb+cuEjttE9jYlaMUKToe+9fS
QwF0j/14y656aC73oXuedfl862tv5d6E9f8yp1M1COEkULkvga0hmSqOtGbTSnGU
6JfrBkvQNQL+gB824OCFgfBbfX8=
=dIBB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'db9da0f5-6a0a-4c8f-ab74-6d387ceaaef0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//e3Yv2XNQlsVEsi57eOyPA2Vo4BzThM0z0pEChvKw3doD
dIyHuY2GD7jTTcNpGsFxKWrDCvznqQECijnEMHa6XxXeG2DQJWxPK5R386xTBuMH
8cCEKzdE/NwF/eZ6RfKk6WSlRILujU9ckkOPM3IPpr6+EY8l1htxEuUHThwfyNRf
xRzbMgiVnGD8yneNoADA4azt4S5Bew1u3JPygAm85VBWmZ7FbOrq8q2TF4wTkZ7W
RpCGm7RdORRshE9rTIDS78kLhBct7+Kzq/DjzRxSTAhQEnIcU4QJ30iLZrIjju36
GQyQd6gjwawR8wCKUE1IWKuiWUVV9p2P5chIqTfntrAjRkgRF2C6TG9NfNAC/GJH
2TZHoaWzgB6Cl47g5sdAY4MWUZhaxOI6/qS4HEMdad2e+Err2c4Eky2MdNQexpRJ
yWUxIqzXa1rff61pDXvIsTKzblqyWl/fGc6em7dNvNIbZbTQ/qELyVtGES83bOc/
ffHHogNgvcS9mRUpka7cy5nMSP5XWrjPm8K4ZKpeKkyBaBTX94gRe3yQ+DMVaBk+
rcm8sORJXzMcuYhr3PZvhRB7syTznxabqz9ShGF+w6YukDDtz1IKBY2QWVLxdKS5
JG2rroRfrejjTeXxQ2CIaKkF0iOSVClcs/QP7lw5M/DP1zglZlX7Yxgwp5yDSdLS
PgEzjDWD2ELB7CX+JyuMctkd4yug4O48deEMiTZLImyC/hlgoImGsPlhRq+Gd9Oc
9cgL0QpKtXvZp1kR+DRr
=cWvq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e4ab0e53-1bea-41ff-af80-545b643f1954',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAipIyQEac4dvlcUX1vljZHrsiGXUrC6Y254xdbdo62sc+
9ugNZ6oaUpXg280CQtYWvuDyzQZpe6R52onkLlBr+zBWbREouigZXj4faFsDGXlM
B1IN71Cl2AW/YSHGi7bKMz2PBClXi3OtYL+XeRp6qSAQAN1tQDPRo6HbqN+XoA2N
T3nit88IvHd9KH7rgqqXy6zsHs0tXDN1WHPCCnF4g11o1ru2nmX2TYzu+lifn8xz
JJ3Y4LU5BV05UFL7U6XmswMt1Yp1I9dnRxWMfwKav2Je53yLZrYSuNEZe8+DqJuS
SvrR3lXCrfx61RfH2k83sK+dQDsPGsSyqhg8vYXj6bUCiU5X9zENbUS7gut99X+y
ZW1PaWTmAKLMXq8+60M2BuEg6ozHlKXhDGKuc7k7W5KSdNwkO76WvwNhU0XMKDBL
RCNf9uLVy4lSlemqhWQV0DNSbt49/JfNzAeRfmaU6h0Byoh0N/aPRKUNJegHtbW6
d2L2CdX+coAJ9w6AMBmxV6HsqVD/oPxbS3c5Vci4tjoHGOZPgzhAi2ewOrAnpkla
X+a/KThVbdokTbUI87DfFhu2ML3cMhOgQxqHG9ji0XTD3Uyv9O2aOA5Zz7ilts4C
amF8eJAl6eEJQV3o07tBSixUgyishfY+o6LtgVKej5K8ZL9oaO42Woh6C1TeHATS
QQG1bx3fY5PlW7vPKDgUZMBhYEpREicVeccT5nZd6A19FIqhe1MQ6MllIdbw44fJ
Vo8hNYgTRGSSDODsZlpq4D3R
=0nRL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e783ef09-6b19-42bf-a7ef-21927acaf3cf',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAmCyygyGMhacxBiucJNhiJWH79487gvvg05Hm6+wRIQlq
/FoP070dWbcmQnGBcNZV05RyDzywK4AdbjLkp6o+dyRnLQlKJ6RZpBT478iKYQwv
Vv6wvVSct5hwxINqAGXKHZq4tmnKVFV7cyKREvHAE5cneNBhotyrXhL8qHpZpBLe
eoFn6s7GOGMueOVwUxox1LLj+B9ZbziQPi5DJ5tFenI7m3VjOw8rTVd6+kpzRSlm
T0SV3JfpG96CnsWRneFfPavyo1NPgxJ+aPgcJFIl3QCFBh64pTNL9sOUtyS73uOL
xk1KGAFA3BhrKgJjC/tNWT1G1gWGfvIu8O+VKtKw1IobuX8d/IVuY2VjvagwaaTM
0fRWnpdBmWx8rkfIhhvfpmA5rgPzHEJecpS9OZ8N/caZm88suRDza03rHKrS5y8G
JjLd6rgNnf0/RWPRK+BQIELRMuKtFSjpVDX52cYS2tn1P6tyOVL9LYyp6bg4SrR/
iqrMeQZ0gXZcmtCGIHoMhLdn+fi9Br3Lzq/2VIkIOhMKiXCZ859WbL6+DwK6u9h6
Kd1aPtq4IFJE54gXJHgvDEWrRHgfX572KpMtiA+XSb2qIhE8fu/P9mpaZbZ+Vy6u
LBxxcS4whCz3A9JirJ/yUyvPQIwUd2QVa3l7XM/qnsCi7LGm7yvlfI6VM7e+DFXS
QAHu5fld+ZPfmMs7BmnFiMhtPyB/K30QUyX3cLz2GYMGltbPxzO+u7qS+MmmcDbp
I03evwRKpQCowjpCqEB84aI=
=Dp6x
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eb3e7e37-4e1f-48aa-ab27-167eb8b12c62',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//VhWHZRpZKUNKYK9DGp6ky2vKAXCRMrs9p1GrJoNRImmQ
0RBiG0V9eP+Zwov+5qHtT4jgz2m50uiscpuDTDnT5VoE8jqksvQlOEmtu85G3sK9
2Zz/U836iErj81OKCWI5dfer1t2vma87Br9cZmI2K4a/k0wrcMEi/m1627WAD5Jg
+PJ5BYoaTZRhO59i8RowaBB+UUX2jVsviBVMJzzFqQga7/l4ZK9e60ZPRF4EC7I6
3xkD6MKlMQwf+N2+ElhC4WO50F1ZaqNtaGn+/cIInpiHCKlPViVY0+wT86hLhNw4
pFexL7FjldUFD/iX34ujNJpMmqbu8izjnFcG5zgiOgJQXwCclFKei5R56QTaPDOP
lR1EtSgB7XfLVKJoOR3CqBu36UdcNc9KdrY8NCFO5LP8tWdGx9ymcvn3nONpnIzF
+ikijAsMKydCzXBq3a90RZ8EXuehrIVYtVb0Exbj1gMt0dYdyEWVnAsWJJqEeVd9
eeyDP5V4zwbQURqTvu6QqL54H3wEAK4cCYO7IppBZrYyq4irb6RzO5NCIEI0Pvn8
6207CFc1dN5miHU/2u488k5nUxmB/gnDS2IW8pZIVI7CJQpDuPrihF3d/dp2iJjJ
0w/kAwdyOouokdTkhu2UniQLioOQf/xYbDCH3ZknEsju+h/GKtYry8KpwjvXRlTS
QQEYCjAq/mP8SPy0sLNTFQ4b+P7v+03/8vp4MT4LjHJrn4/jaJVNpk3F3aBU6TAB
u/BsxLb/mCaihQ4Claec1yB0
=SpWe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f1eb4f75-09c0-4e6e-a41e-8df5c23c5a4f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XR+DBGfDqYchJlaFl3Lirn4yvz6lHe5famTNfzeWYqvb
pjqSq6gC7fLjID78LrPnTGuHb9ZcHGcnJdcLB5mWmUTpSHEko+arNGhF4nV3nIWv
BEdAy5gsMc7mN6ZPudfSwgJ1T55P1ISQzhLzS4y2KOpjYrzs0AJWdsSbbDnA4U35
gPshYWfR9b7QZJ4s+tYhAO7hwqiqGrP5h6XQQVYVowB1hPMYUA7eHMRCqMnRdJMQ
5HWvjaVxOMJgeHzdZII8GFxpdri4NO3cRRf4SXaDaUnA5ogK1uIEXZRd1D/2k1nD
x29pgTVyA+WF/pWn8ylclYu1GV8AHwSelIq3DHWsBJWs9FtblGpNdFpiVoSi/zfF
b6nQ1NrHwEUWYoN09Cvu7sIDOh7qzCI4eCehTNLBSIzLbnaAf1bJNEDF6ARaJe7p
Fz35ICdCBM54j+TmISezgvWtF8B5H05TrHZv8NNOPLbhVGtxa7MMTfn9QR33F/2o
Bw81D2siIqhCLfo/FCBlHSCgopu6D7VYo6KgYohnbIEPUuwKiQ8zFbzgoTZzDSwR
F5//yi/vLjkoD5nZG5LOt6PWVgIiJqw2+LerKuSjccEqS5a7/dtObIeQb/fONnCW
b60aqJHiyZxjCERl1QkYypBR38iB1zsV+WIVMy851fs10xSebk3aZS14/YQ23ujS
QgEnPAO5z5g1Jba4gTfnfYTX5K0YtaTy4n0O6MNWJgeTbXSw/SuS1d85CuAODMUU
LTsKG4YQra1THSY1D9BXf42+fg==
=Dr2p
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f258f8ff-4b57-4278-a08a-c18530c8abb1',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9E0QV1hKGZH1Co5QJlf5D/yTjgYmvthHi44llLCN/P1NP
2FLzvHnI8HoGYMs3+4f2YuykDKJscJ8LvGTQXpJLQTQe4NREKM9mQ9UcsWdLOTGj
1YixU1b5fIZ2HhI66i6jlD8FUZMP1E5i0jt6rIfuHlbARgrFLRyadWS4jce0f7rM
/gokumJwt70TUQKhe0DifMvldIOUY8DfDZOixdkxBV6bOVc/cR2ALk+qjzmCnRqT
84LU9UEHMYueJuMweHpQ0Xo+isvcYHfOxfZOZRITYg1+TNywYl9it5LWBoI9Ok1Q
fmC42QmxpHPPdgg6+n0b4GTiJlcFhp+GyEeV4OEaaleTy9EhbMXrS0TkyiRMXDI6
mpDyg+UZd5HHvp3vlGsw8sv3K6VTfBReCreTh2iCt1529ctFG2H6VIkmRK8J2Lkw
jX9uXfdhGOTU2pPJUndgMbuMu5hgzcagzbZUqOvwg6tm/1AKASNuT/cc077QZ8db
UYFFMKc9uK1LWb/8saFeqtTfOXntUWjO6+m8Ajet/nMybactJJ5hxfhv7aevIhKC
AG0hpjOhXIDxD9WBR6kmUNKA130vdQcd7kMLofTmJS3/wH8JH4B9RQcxjT0PgECk
XXwrq/cP4i9gajpFZ1u4XTkFCi9kJhX06+tIzk9QY6R3vfd0UcSikwI4DaCc3ODS
QAGwCTETNx2H1dgTXInMlpLO9O4zI9PcbSPc7loxVaNouEbYkRFv4+4II5F2rOlU
ZLXN9ziq3K3MfYn7fwtufWI=
=keBG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fa4321bb-7d08-4ac6-ad5d-822c47d674d3',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+NkbGrdzpJMxf05QtQOnbjxs//WgK7oEaOZVpkSiU0RiR
mOA/XXgyA6w6rtVpYJbC2G+nUHoard3JnDLx7mHavCvXmHH5t6Ddod9S8t+F910B
M2cWtnpZ2RvUXvJqeb0w/NVY1RZ3iyqwegWBOFz//TEtVvIR4qT1o3GaseCiujhe
p8yyETT6WgM+G5fc6cmJJXBdbFzmLQEhso6rkNU6qFVYVc8IrDW8pzTve+CI4WGt
2Tv+JzjIG6THHCo3c4U7Jy8alJZDQgUU5ukHStQg5v0rsoAJ66TzDUTY4EGGPlMl
9KvQ6iItOgWe9cnVdZFIhe2eMleqPa7tQ568MtSxxXOIKJdrdPQwr1X8sztWSHJZ
PHQQQyAqLHtHcJSdrCjF+H4GRaSaNgXdr1P5QOqGM0mi4bXCGZF2XVsIya0PZ7Jv
c4hrmyNkdVOeSt+GRqhzv4gB5WO/NiIGOManTvWdJvz7A0SgnJiajf8wc/+1NS6d
t89WyM2d/EmzCl8ezgwXeQwIo1Xc/M97M5XI5zVz+FJDxlIcNprJEqHspdKyiA9Q
ZAhG5ZXF+14xvjvYPUT+NRl0e8huyMnrRD5H5/R1m0J7XTWgXf4WLgtXBvPSp89L
kV6GZONWL+J/9EFn4oUHDJx28RxwnFfMsBazF+ZuNOpxeGYK2vFhLIgpqlCnKOvS
QAE5MiVHKQzHGbLU8sJMMRNK2WyhU6yg9BQJsoOvrRR05VNdiV5gvoEaX58lgi4H
00hE5Ei++p3pWZkRea0wUuU=
=phvd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
