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
			'id' => '0732d5af-9288-4218-a0c0-570d6ed69d39',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//bBvqOeCbsRr5tfLhWWidWkOSQWaDH8LtdP5U4nsohafW
IkG98J+NvIyi4dDTeXVtQz57QWH+dmDSQjDfH5Evh9G7F8AHlaEAofyHCf1y4PIe
IkR3wBYlbuV/L5fDYoU4eT8WvravCsQmZi0kI+DT70Ao7bfuWgkFEdnosit/0kqN
JCVct7MeXWtNKW3MusqAD/02/y9tuTxLAyDTi12eQIVo9OlQ4JlI2pq4W6oeOxkf
oS0ym8gCTByLyfI5loEcgwx57YagBWsfJERROGI9FuBYlViPmxG9kOP4V6P9tLIY
mONaGPjbTbRFp3bPPxPZd9WG3RpzP3ylSL5Bq05C33eb/yE5f4ZpRe82aESm6Og7
FV9Yy7OHxZxQlcPj/tynpiDtp4Y9XdmyAxfXQF3THpaqSVjz/Inoj473VLJL/3lc
bAA00amJd8uGxQCAqUTU2rZ/YW0PWBR1lNMd1g8h8VIq9hG27Em4rAXnWo+x0CF2
Uiocexa1V151SuF1J13sKpOGLI3DoS5x3lY4K2rhWHbHWT7tr8svMj3fl9gbZGCU
sYHcMflH0xXe6mmmDiaEc0x3LRTAg1gVNeQjwNElLToBKbIrX54Xj5bkLGWtD0LC
koWvbd4hUtCgpCBSx5ApxKJdHmNoYfhSwIy2e44ZJecohGrnhoJ90/a71xIxfwjS
QwHPye2NV9pDTkUySi8Pp2z3XeftjwHiH//DggbCPcdIO3bxspwIYNd+R+qlbrX6
2y0NJEdd5uadaBl8mpkVbk49zqU=
=hA0w
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '09eed707-383d-4d3c-ac87-6d36c2cbbf37',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//Sl6jofoRaJXUdnxYClIMSRT6K9lsb9T8SxLIe1mDMrG0
b16LMom0t9AFOTtoYYQ6fr30cztn+MWnwQ2xZquZ6Aq2pzyvAVjW5XUpnqqx6C5c
Rg56GpyC/Z/THeQXmxEqYryC8hEkB1Smm9JSV52bKHp5LgeBiG4fw1Vj4RDkJR8d
cq3Mc/5RfGYFH89do4m2ZCPSexLo/mrL5dEZE7anNREcOi9EVK0osZAhTj7Hge9K
p7rLHqA/aIMBAdMa9bAB2Ygb+yOgEOmKk9JYaVxT7borOM763HhWWls6iA+QdKOP
2LnFictjPV2YA7HHRJKhZ0zBs89eZJ5MgpwYsG+LWlHi65QHgzGPFg5Trcle5uxG
p3HnbyPHSJVg8IOxJNsnMvPI/9aY60H0RmfEZXC/cT8ctEjIEBBNBvhHWjnfSmMv
QGqJ5uAxBxEe3JzsjLXKUrOXuwbnQCCrUeCAzS7EvNq2nFFlTHGSteYOZ1KzNJuL
aFZCYXoSnfszQii4rtMN1kzqTwmlZApn8UPXbU0BNNUMpK1aoTgd49bCIAj8D6ag
sYS1AVRFlq9tlgh2VnsAowDENgsRUVBUNURlfKH68jQoF5ctN//fztOT5tNfpwNK
nXU4FcApUq2trYsm9PcQ0Ym4fTAPWCTsLRKPPZUUHImOzjH818qsfjyozyzHXJTS
QwEKLrXNU9Xlg/GTPrYNiTW4+AT8khagtxiaKDjBEl8oTyZkxLsomVUwKkHUWhY3
q2MfUsDVQk9n1VryOGJ7gnTERbk=
=phhh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0a3173e3-6d2d-436e-a258-8dde684e6ab2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAArOzIPc5M/6feKnbwrYnZ+ydFhW2/4DrgnDzkWgtBMbkW
pRc90HQRdQ9H7hz+NfuZfBwVZcxqW89vPg5tX3G9cLYk5ZA78B/WEolitLRLR4pp
73piuG/n5kF86a8AlI/8BG8QVoEbzFZBCmvOEeWeHIz2MW5NLhlftE8kERrglPdM
9fN1RQPHmsickVA/+dLeATv5TieXmpBpsH+ic53p/5FAGXA47mG4hAoo17euKxuy
bgpfCcwQt9heeQI4oMrqqPEVlSUmZQOqTipPRN8rei27POUMVUA8kcemRs3SIes1
+ChnPXlq5BZldSyr7AqHLtR4JwyrwKoJg8/NtUzl+GvxkfUJpspdg1Eb1/v58rbV
X5plb7tuizuz/DWtd6wmIRRS/ObYY9+IY3xbcgNsBdVZpPa/PGt2A4ihzXbG5aW3
lFqk02sK9285RMNELQjqGkCAnoni8O8FL2p/PxNLU5nhvuCb+z1WH7w6tAzMxu+d
00UiiZgF8riMoCCuJY7y+U2N0A3OlJDUYNQgQ7PH1vLxxcF6MRyk2M1pAEXltePm
95ygBZ1FXf5JULL2zY49uEv2ksEwg1fW1HmG0RMBCDyKAIxOC0r7Xxwks5u5qbGG
kvnsSV1CRzEM+CF24EeHTTDg5KBAxL6zWrvdnkwuUeKupOi13ajc2BCScjBG0U3S
QwHng4yBZx0w4tRuBNpNStjc32n5rvWL5Isvl5gGI24zUTe7VlbrqjeXPkz3208o
Pc09y2z12T1qS0TK5CkEHWilaxc=
=tkAs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '175b557a-f1ec-4cef-a3d8-4435f32ecfa5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+IK1gqzKRjiaqKSI3I5QUHjMp10hxBXkFx+ykTuhy1l9V
19OWqmjUzOAEnw81QyFp6OvYeonGdTwxCYNSuKP1QJZ+1gOkFiKEp86qk2ExEkss
2FCwKSIDMJZRy0JyegE0wKtnJNEQo5y+sBD7HWdrniCRNZLdmWVBd/pXLWzqDPlH
G9ebR7s7VyV/Uo1SzjxbwRBKFzzZgJGeRVBwlx3DJMsLzqI7pKOpOIy5t6qbptLM
K+zYY8bKZtqjJI/zT1luHpZRQxa2lR10kXF0Wa8uDd6X2XeYezfjFIWpFugqDjkA
dII15xHKI0b0M4boKxexxlUfbTSqZs76C4r//fhTKauicRIzOmGHHGoEruFVp7J9
a5b2i1y33KPQ5n9xNTaQnPEMXKig5/fhAk8ptsA1S4N8BHUEG7S3lUX82otwZY/C
aVzqcI3MnaaxPKjbz/7ISvvyjXSNhpFVvMyMF4/220Joan82cadyIlhAYfrwKZ6x
WaW6Y9yMsAeZjn1e+6l/e6M/FtsOoxNNmVyWCYqqhqinhrbK9kuGqAdGXFkq0Oz2
0kOz/DWYgNVxiGoMubUROrDjLHnD/22V6yB4e08+XcOVHP13PlINOJmftZDgREim
ufcc339CcgRPacWABqZoeol1rkuA/FMm0s00tzY9MpI58XAhA/3oKYdFY8H/i7zS
PQGFSTX9IfymJQR9cVoKg4Nj/QArdJMBGkFjacbYwn9+WljLd5vG3eDz/GcGdOqC
mfD0CwKD5QXy6WyVzXQ=
=Ir5/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '18be7226-1543-4965-a9c6-b35bb6b2d265',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAp805Lcnn076dsjtCrU7zczlv/ekllFC9zswrfgR2pK9I
S6IE/Lz/wm5USglcvx6GuuXatI+5mvk6ABMToRChnnEm2Xy0ZLytXY1FsNuzfK20
IKU/Pne0AJURM66XIX1+T4ViwrlpDwGf42rl08yUEaXlPEuX8wlwbgMDSGJMTbTb
VTcXrIxzrpavqMR1DN6TZFRcc0hXAAbtYgzKe+DzebiXVsukhLrWADWg9bn3EGRe
G5VszST21Fjh+oZVk1ol47BFMcxnjSJwUjCJe11svaepMAilmv4Og092NRKSZKIs
1jbmoZXjuxnEjpl5TiUBCXzA05u6x3NBFGbOMslICm296aoJQcPZIYlrf6A+dp7e
/c7HFy8fAZAy5xH4zTlyQsdYnR40cM/PuilODeLKKR5Uf8azZBAU/XjGMREMemyA
AMQLBzeGNnHQJu45GFknx0FJ4D69LkGdkUihYpTOtRdd03SzPH9baS/IkG8CePY0
tHyGgqQD9LYR99XQZYMicQf3J1L5ULwt4tfQIIf6HDtubD41osQN+mOo8RsJEC3o
o3jNhNHabcZx5sY9kjDVDuHgIzae+nqIBIanXM95BKC99Abz5gbGFs6CV7jHbcAt
D6Iz6qNSC5PIBF3apyiqhITeLpHZJspT/JmkA7qu5ti/qrPlFXsnLptxkEs3tULS
QwFtzYu2fVwJ+fKTAjo6dDd+9xivzXLpRzf8uw8zkN8JmjxhReIDSdOmh56YOidb
Io6zw9MJ+XgT6n2uJbGPGThuuE0=
=9jul
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1c92dc4a-9c19-4cdb-a10f-c96f37f82a53',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAiO84ypryQcDaN72g/CNEYn+COflAFM5FCOCe4RPWmcov
TzaVsEWWJO3iDlzE/nOt8/ZEC0YOszCgQBk0e74ETyZRGmyYIfVTz4i8FIQNa0np
hQeMCZ4KL0MEyX7ut4ToduS6NuvFrDe1xktn4iByhaA4QYNiNGr1yUO5CsqDd2W+
yFUp7WB+JsnhLfXZDWDgXQY9hk3zXxXU0ir86WKZahGnML4uMJROP2xf6bNRUl53
h/ZG985WZXP0RqJ4fjMhd0aWDIY6FIe8lYzjNM2D6+4lJGSj7AmoOsCCpC9q8YhY
WYQmGuGgWewqddk1ZOleuNwqzzDa+wiEzEmDUIq6ItrZpzpS0wk8OspdYQvXmNju
GS212FfZBubNKdgT2liqM3GVfMKTLH3WpVp6MxlEGfhzVpmngdZ5e9T+bRGLN6BP
6qL3giuvkBAOaOYvNGPS+zQL2cv2QjL3ZWx9VqMuTQUlUjiuyRL1UWdZXS7TORxj
ivKLpeqh/KZ4fgnGVy9BaknGZ7TbLdrxQpXjq0dyeANy3N8YDnNwK93i47n/RA5/
2+y5B2DED7qZbpkbbBKnZnmYQ4vJ/obXM6dvfAT8kxYwm/yEfeUhMhek+/9xpfKi
ostrNFGeW+KpCauBu+D8ZlBANYHj8iL4gFTv98nSxGIe7BK7D49fskRjOFHI7DPS
QwH1F9adARhF105G9NF0wzZ2VoayO5zUyD+80HVIGX9hPvZlhGD3MX+G6Myq7gJf
zAgpOeuNS/68JXXVXggLEtyeQnk=
=KdRD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2a6e11fa-abd2-4def-aba1-4ca1645019f1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAxHApfGyMdL/MUomgVWFVXOBKaBIJpMPph7NSJ45LrpEC
6Lr0NFjSkKmCjxK76VVrOdYV8QD70MnHZHSMuW/Uj4FzcEaGrHLzYaHJU46Wo6r/
QB+VMPovxr2CDWypYwyt+XbsbztKgdz82COqyEBfQCHxJiQNkDBv3uc7iGC+37/3
B15xN5AhLZSc9GYY+mc0jqGwthHyg5HVHiIziWP6jzfL6nuaaprz9VqY6BzrPgQo
f/A8bhSW5gpheSFvHH3eQZstHDwnhk+8xNqb3tW3YvbdYJ+HGf3hK/X0jb1Up/rL
2d2SnDXQ3zV9SGfUoHvlTKF3361E3Sr9ngFQINKRLC80p7RkkrKZBVAZuRRUsLLX
4UrbbP+OQ01JpYDz9i8nahETWTZVvEMyChvLU9snQ8F6BouJinWJgkMyK/nPmWsN
bXcFU4zyd7okybTcxizZzlR8k+Vd+uC1/g8kn713UpVFpVQakWLF3UPM+pVdTr1k
MveoNcmkqhLN2HjVT0VxIkW7YAhwYU5TEX07n0WM15mbEeO4BMd7/f0FYBoiP2R0
L8SR/g2csp7DvGaGynoL6OBdpjBej+lJIHSXXowyO9O/EAkII6l3E1V45ZcIrFGq
2x80GrYnVpw1QHaPQ8qouWuvOZOrUj9lYLA7ma3xkh1VBsZevb1yYCmmoW9/gTTS
QQEWwv/EshWSZEgGL/v925lIwWdkUyst2hcNQ62vk15YocTGFvLXNd4RtcGx3YYp
c0CTfZGh6oXSRonSjWbjeR+t
=wOO8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2ce72cd8-66ce-497f-a98b-51958416ff3a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAovairZsM5J958YAlmN5nwUPSaieJS9/aRIeHY/6lEyFt
0iLArg5BapsAO+UNLNQ2ctqFjDxS/6bhrz4AtMgjws1DKSvuGPaleGbkRVXXPYYY
1GE0z1yIY9TqLQMhs8EfaHfj59caAj52y7xARJMtZF2ClTC12EqzZniBJdvuaWV8
0a4JheuKDULHU8H7XepweEWcYBnj6ASgamo0UOd49ln2F/y3paHlV/4pwLbgfEoU
0AorSnG0vQbiqAi24PirAxL8kzBp8Ub4q0oYbW/WqCeGKtiCONEWcVHxR7r1T9Ip
9J89DMw462V12XqY0oz/hvE+jXpVIUfGvQMy1m6YBPctV6ggm2vyrWah+W4Jz0yk
REy/mVyH+7348f5X89wUTXFxzQefkF0AF+MDVKbYbDix53G+JwVccfB3KbvDJKnw
Ot1zTdYDfZo9GW9p0APYtdHqXpyDb+OLrdDCU4aydF0CuZMkWWhErw6Qw26LOqV9
IAmUqa/7dUZkjuNkBYBUh9LBU25ZYZIx/1CFO6uRFUaCpyHamDE8g2CHalN4sqUf
BoIoghzNJ6ApgCcJ35DIm3BxiqHA6Zk2szUC74GHcz99srBwLv2eY5ayu3DwERC0
DdQnfGDGXzjsmwuUJf55uRF4vgjA/CltE80l1nF7KPDYpZl3EJd/BKnYN7jrp47S
QAFFKYlFUdFU74khMcvOuKowanKIJ6l6lpZ/BGbiFpSCaBlX6DtwKCiT23+eUzeO
WhtIGz9UQ4CEI9Xo5wD1rqQ=
=XmKy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3c63bccd-e635-4925-acc2-cf98b8084e0a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//eO79IxHlZptaxRvQiDLlYeH2TE4xleFUO6ddMwFA9jRk
SANXmz3NDlMV11z5cg0poCAlHo5wqnWw6SprH7yUhSEABLrGOuqgsUtuWQQT5vfV
WZRma14HEAK6OxcguiaeAugFCKXFvN1PWb7nPQSOkpvL7AdDndMYGVqCk1DRQStY
oxNgGQd2rhNZbEikKcbuvrD16kejsmLH4NTcoco8Fx5flNxRfYRT0xkJDRAU6Kcx
VX05vVS12RJuFuD6S8AjV2eJ6npf5zBuYi5XX4/IB3P0hW6AWiiD/Ts21tP5bdbJ
aATSabUYTRji1fr/pMUZx0VTni0NtfH3ECD2B4+pjyfmLo/55Z5jL33sB7dPf/Og
+D3INHaJCvW2pBLtEdKaI4ejJDgyVHCupWmu1OpRjY5cHJZFNynE/s8aUokyduA+
0+Zx+5kZjKFDk3dqbkVwcPx+1FjIAOyG+thvuuNDycGty+cfIo6JQd39DO9ZiRdz
GbA9WPE0cWTVEcTjtvmAI/sUoEdwNTIEToJBTDCMyVn5W3CIpKRfpdX693acIx1D
VeKRXX29041zOIL7BqSQwuhY67FF5I6QCd55SDDG3IwsGgb/m4wCHbdm1qtfvIOh
bcePfSTKOaP8nSVhMH6wYLAR+5R6zc1ld88J5HbhbpPq9nFKzhMWJRAkqvvQ3aTS
QgFKvl/bMps7o6qIv8kLrFwXkMrJ+0di6AbLkStPkq8wm1sFgFOtpTJr9aXQknKS
MrLWwgzinajSKoKf42MstErE7g==
=tBid
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3d6863dd-a7bd-4292-adaa-f7e44476578b',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//dlRNfCs1JQhWwV5SAzcXz6AM3dlHZHRU+9bCzHvK7aTN
6rKld9J1usF4g1IwZfwDgjnKeS0i1p3XkNDtkpjobjQiEPsHsPGoIWFW2HhMV8vx
yRNRS4IL/WLO8t5OHettxZgFrgkGcE8iRxAbrE9fMBBSvsTGMorfCzU1HEIvx720
yy9sXa6N5TZTHXOo8GTNp69s0iC4Bt/at/2fJg4M6woovMUP+h9OiXkbu0cBDA66
kUiHVl3vTDysyhIvwIMkcHYum6rtx+a+7IH89owjnpxQw3PKYHlsS8LC3GHppW0I
LryT5kfGOPIcbEcamf6Rq8QJIXzvWIbxfh46ZMQAJHukWEqK1vk/GE27xHoSDrmU
ngmu4LJuh2l153Zkv6d7CoXpfrIb53hS2pfwcGuDttlm36mQYh5+RYhYZfvQwdSs
gXLXkFuhwl4ddinMiA4pmZ7G3dqAzuYzGuVbbj+BLLYb9vJcJRxFVYEAhs3h5ZCn
Gkz9PX53Hl5RgrONBwDwW5lzKw/+fi4/3GW7kJxiIH9W9bKfZOYE/J7SrhYEy+a3
l3rKJNhuBsuniGHonOeOg5XSSJirTrIp8qBXKCjMb4T0q3GDLJT/I5aZWW8u5Xrr
aq/8ZGyazwauxqTGHOWL8q7XpJHCR3Go5AUZq/i/+8Z7vS+hC7JgF2+wpaH3oc/S
QQGaarjlmOR/KMBBuTzg0X8oYTczDYNcxggmgl9urRaxp9NaPISdsDL8xBjLkxVA
g7RRqzWBSX+J3+qrDFE6FfNg
=bcnZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4016a252-3067-419e-a9d2-7132366f5884',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//exFmEP/NUKsLs53Cbfyxmqig6Hq57XsYZAjiVT/JEz8k
v/WTLG7p2aMDk8w1H3dMNla7dJogmWfmJrIMoAK9LZ3yu0saxCw/UslQDtfZJcps
k+WJGpfGiqmR+mvoo6tkRslI3tmqi6R9CBZvAtKTYHHbi7o7POejTmPteqO+qYaG
JRcBOhXo6QaGMAMvbV6yUMDSZYrieJKd8XqC+2ks/TsDcMy7AuG0OzLBAXTfMmR5
lFhgFeF8STjF+qqR/Ro/1kUye8qHr/Wi/gWVjLqiuqjeD3a2NuN+mPApPm2cvpUM
p9lyo5sDpFmuCW2nKpXS1egxF8NCUT2zLnI8xiiFlHXzMP364nuzInIVBoGbM1rE
FXxvOr2s5h0HjWeSMhgQ6JHfvLgzJ+NlixgacpH758AzH/e6+wyP0vX9YYrYcI4q
BZITyJSLeewRkt2W6tRChldle8u5ZEqGPMgK6CPlfPNMKGuTFUJLB0YsCvGYHKh/
hhGcCRsqmL+UK202UlXe1DT3RU2NOgy6Xw87z2WEll/G6pPuRX70DQ6rpA20gu4A
CkRH+0kDSHTmWAFVLRFHOp6VEj8M0/P8vBAM7409cUrmJinzO+o/Vnc2200hbXUG
T5zbDzdwDwThw+V3zaCqIKoXeBfH+TixWy1WfI5JQmk6lapA0SDETrcav3MS8rzS
QwGvLzDbwunEwIlV+Z0q/qC8XVNHCJZi7eWexEpvI6qHdO9fkSK4H75hxRHZU6ws
0BiUJbCRF8Ae9FIQ+XAzrKrdl/U=
=pjJ4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4050db08-dc91-4490-a967-e3df9ba971d5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8CgbBxO7M/oFZV/cSojLHYz9zDLxokKe7mwSjZDtFaEa1
5riKQSKsO8viw/F1r/mIvJxXXEN5/J9W6Noda49t+19on4HfUjD6FhGdEAw7KPT6
9ZCXwTG6sQjhpNoLBML7o2mTedDW3vAkzRwAVUeSso/XPo5ltd2xcln3/bILue4E
c6+pT+W+5zGd3M/+o+CW3eegyrcn5WcpGm84lp8AbVNsfimLHt/0s2iFOY6yTKBr
pNONwoPUbB6NblTBfkJaEcBjHspgaYmVk7PxtzFaobr4HKDn/s0Ahtjvi3/UiRRR
iIoemI4V6hhbHvof0P58pD2wpvA/1uKBBJgpdGm89s/+udCr9mc/r6wLMKQc8PHG
ggClQRAo/M9AVVmxMUjHY8AMHi93F9GiTNx5JfUFAQTK4UKY46p5KtSApQoEevIx
YBNKbHoZitZoTXzbW97mnLf6m01bp8CY7lGKhrq/rd8tVJFGNmdY25N6LdE8eBMj
/O5gzDTg6S7ZtmIW/4bd/hclnZIWYGUCAg+zVz7+3a7DjCxqcyEvz5cSRN5ng7n5
xWu2AT5RssJpsQ1BeHwSxaw/8BYF24Urvu7q6C4XGaoPg4dURNILGkgogiZHXi9P
yKfckV8B5eAuxXOzyRdKUZKR9NiniNYqGckZ54jWBakljqvXpnP5RyJZKZGkqH/S
QQHeXNbX6BKy6PAoXtkJZoEMqHngxWoZ7CBcfitA/MumHtiqogVzcWl1LwDLvgSE
8/rHyhITLA7ndKP33YeXNUb4
=UFNb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '419acb7e-12b9-437f-a847-18ed8bc8cad7',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAk09BoG5pVuXlOvHzQ1WrAW9xHV1q+HTgaIIMcEGe0YJS
jhtbWmEyvsKRzf60np8OomW84f3QV2OECZiowq9DFihJvnfwOakuJCPJDRDYf+4B
Fe+6869vfhLy1TU7sDBA27Mf0XqtiXE5wK5N8eFQQMYACBzzo2jlQHzk0IMI/bLv
ZgZpV2vqiL20MKykkC8Ns7I8LuRqJzpX2j1UoBYccyKcC7nkudjIRjrRfrEvktsG
ui+ALPWZzOovSMgD9Ckr1EO2H4wFfL6kD3URKF/03jXq8JLKu//DPdUpeDDtyRmT
KPivUEZlpQOusNd9zjqN5XgMisvE/th1r7RJzbT0TdJBAU0HLS7YnDuxoEx/lxlm
nAOC0QSl16pnRkC+Th+jJU6APLkS0Vv9XkBjKcjV7Dor+aAkEZVA0eJIOEsVrFYt
VqM=
=ODjQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '450821d6-f7fd-427f-a0d7-32e16f78d785',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAq3rk0AJ2QNxoZVecxa9XtcKZzemt4ATRwEef86QX+Fad
GmwrZsh3yTJftUwIVBOS9nJIHeGOkKDADj6jVFAUTU+3A32jWaXFsbIMl7XVfjms
odGB2XYaW504rb+fD/5Qpm50Zjn3g1SYx4ESleSYuxmiqe2U0PEV37IZ7r08BmPd
ElHik1wf50yr5D3CrNMZ20lcwqINAfC70QecwF2dgjFmno/CpU0loFSttUcbUcOF
zc5sGlS/YgEGJTIL85/4ua6bnVE4Khh/UATft66qLkqQtLDq3Adu/pkj/yTj7Prw
AOBbXDubLwkXWNyFlehdB6dgxBetvJSgs+vmazRre9O4CzpKE1vyH5+EBX87hcUp
1n2s2xRqNZ3JHSx8z7WUltjTrPRDhvI1wYBlpDdG/1EVVm93i1vhbBYca1/rznnk
BJOyfXAqhaChj9eijpYMh5NISEt8rf/to6ASpRq07Yfyr4Rh9Uv5kTjTDOV7B16a
zV/e2T4aXBLUadeBn06o9WiENH2mQ5Rwrc2GdmeEutSRwUUDwKr/DIvmpIAidJBh
Ami1Hsl6NQx/FRdNWyBmx3z99tnIs7vYq0jjapBIGmrxr6pKWIAzXqLHzb9l07AF
i81z7CfZeRRBl2goJvo75GObub6bNQlpD9OzNCJ6Hpc5YDhthokZcgCt7x5XfkrS
QAH8WR9O6tSM0NP0TUCz+dRWgljhCUjGrTtrqLomMnNXW5stzL+WII5BxHisML7+
fVs/UZ9uIbRP8W12FkeN4qo=
=gAxV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '471e6af4-10a5-41b0-a604-0a74769ffc58',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//XV5hOVgrmWLgq+ihGOiAP587fN3m9kTtGo38M3zEAmRS
VS/jiQMB1LEAdUfbfoivmrAQwDwFegC/J7HWlWKjNOhtGzDry9q0tzg8IzJ+Cjb9
GlbFNlwYKJx0w/c7GpzR6v4sjij1fJwcPMpQ8iSmBOJ8HREXPc96GI1NzXgmowHI
suAEGQKIr1rKwr3mEYttrOfws/Vi1PlZX2oD1dEIiO+4r2nvKSVmiFT5O3jzx8Nw
y4vHkrxlQNemMxrfNmIYCbqoC39vJYRbT6ApskfkUOwzvPuQrzn7qHalswPk6gAM
Rcsthk/d5uwXu28o/2JAcY9t82NBk9hv9kzwknGjwJV4Tfn6huhPiyCmNKbRv+MS
rrt88sZEp8lq9rRHgGyE7xKZ3AiFGfog4cJC+bvbcjWr9OKOTJ0Riwt+Q99MwOP4
IiEJMUel3xQ6HKGGbt99tgTn4KCgUnttraNnm3eN5I+AobnsY+pGO7axgcqhS2iX
YstHvnXZAD+shJbJMJ2PXIGuyw6tqzj74im9f1YQXVYVHxTgv+ihVyWD/QaQR0oO
fUG2HDsnrRLOtZIfuHma2mBN8RAxyC7k+5v9JEip1+SswSlg9TFYAM6MlrWsjvGw
iByfjMeEyL19VX6yhzP8nCHRYgy7iKh2c7ASI/3qNGxcgSCL7hOYIrNhMIu6kl3S
QAF6uRHRZtXJpPmie3RmnPNpQS8iGzxEr7/FHwPphxS673lWVklinTU2IPrXF5j+
JT3G8+5yIH7VWpI0zjXjQfo=
=TjZy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '484a9b2c-a885-49f6-a9f8-0e028c68a9f3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9GpWyKZi0/wHNP/J5fEepXYNmPwb5s8ISN+ULdXDB4y+k
eSuit95W9aCO1Wa61RStxU1XS4c7S4kAHEz5fv1/i26R9NZ60LwWxuPyOF8erDZ8
09Tz18v6wfOlRZopq5vpslR93tqO0o3/7aPKPfkviV98bSFEvjdIXTDA99EQFghb
pEyBrPwfw9Y5barF+C21NPPUUhzoZ+BHvawL8lNLeQ6tgY5dfADnzQ4AsVKnKP8+
qiRYLkEL6ciBg1rwtsYaNMCiD9yWCr2latlZ3XNpO87CKKNahVccshcsHvMj9tJj
tRQgHvopcnzSoQ+6QXeGRu32xHS9xTuECGrLdtibk/gzOe9NtaVKm4nNDSYyHDGh
9c8DTetLVcv4aBz/aw0zWunhetPUqMxHD5b7RxglbJfxRggRx2HaDtF+VfymKrBK
vtzKNVImPSXcSr6nVTFM0w0Q1/Noi7uiRm9U0q0USFm/6rc6GGo4BAEfkOYccSmz
DJRD00xNkIAEtjLg3LefFfqhswoe2Tr4kZT/O3lOG0dct+mp3YnW6+Jp71iEAsYt
o7r4ZOtmiaB4F5M7X8ydnnpgulPG35NfOSmpns6XKmuiQrDWh/qswBwmo5sNvK06
5VO6nFcYYm4gbKsP3JRcIhAsuD9sCtgR+pG9Mypy1Y2tqB6VNRNoELHfLga+hXHS
QwE4qrIskT6U7BB5+MBtF7D7eh3enmabUidKF/0TNbFSTFgEy8uaW1q9O6wC8cOW
yQcNAhIYUFQaEpzoTfK7D2/RZSE=
=3Yoy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '52b2ea28-0822-46fd-a18f-8b218eda6d4f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+LQB1eGRBTex+4BHUqZPib3ooAxkgTRX5hXRUjOLlb9mU
mAAps8n9ySTR9l9jqivmRKWfT7BIe/0qO8i1uOwuItbAAcNgm4imTcxOBtZtK5Cx
Y/QrYni7YpvZGM98UEp64CfywSfuRcyddfhoeDVw/dQIGM9L0jgM6UVa62XSv5DE
oMf5CLuypCrQa84lYeUHB9cYZda8Hh7GgRurbpr2soizDCoXBaqhvFnUetLz1jDR
HwzwjX6kKPfuT9u0/w39ii4CsyTBLvMXkMIXzUNChN63vEgHTt1jwIStzk3TR/a3
hFtc0TAg7K8P83QXuf7lmHVyJlQrO8mtmFXnDwZKD0V+f5KZjB5aVo8GcpY5xZt1
qn/m5Il6BP56ca6rPHLEhdgEWS2/6PO5q72NtZB2BB6RGakrEkPOIpwnVJvYwW2F
Yiof12MyZHmVzuUETJLTxRtMkXazYCFlZeLAefwc94bctEY26bdxdkamAvSdMDUR
3UGtqp3Rw8PKgPiXVNAUXgCNdN4yTjs5HSQRY1meBDx88BYUWn89ipORvWc2OTqJ
jFBW4Jz4YLI9O7U++6nQUSj9g6BCPe6uVguO0tV/QJVZgzqVTBHt4pPrJbUUtuRj
6nIhn2x75uQXuDToKKmAjMShEDyKcEbCpWWT22lk7gJYy24mUK5KqSZVr47YYfTS
QwE9M1n/UWi677xyabEzEb7JOQdz4qxrTOapjxQdiZfufWN5xC3zFjIvMjAbyOlx
B0PTqYELNucpqbVwt/iaT1d3MeM=
=IPP8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '57d92db1-d1ad-41a9-a0ae-1159016ffe56',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+M9HixTCOMb90xMK8C0OmHG0D/qzqa97tK0r1ZVEsPp2d
6So5EILCOQWSChb5oTFU2G1ZHpvaJEg7xIFHoCCrwdQtw2R22A2LY1gV7jDQZh7O
798BVMwmGOXlaFDnK47q1OBD0V9ELoF3rwkutr7AUHO0aJDvmZ7jd8VzmA3SsX3U
CFixivz3ACoLEEfO5eFogVILsLjzbTWWjkQ9HcQmVx3ZytM8b/xPfIbr6JZOMx0z
qfdSa+8lirqQtbMScB5PKpL8YoKWoyDjfVcxsqhIrNkgRgeN2+tOylXnmi7bXY8M
CxhWSpFldFqtWGz22cbm+7GCR9C3ybIQ0xW6qhYAxUVV5P1/qr2QpBDVpOPUJJPy
SXRFG4OCm9hnt7Gt/HrYT8mWy8PDwInsQuj78+zNgXbPck/3PdZT6KW7mccq7y7z
NszlllEtItKGq+otRGBSM83kuHlmLymu/0g34cDzMpWrJJuekDv6T3ihINwl+n+z
TDo89ra0tWjwSCQe6b+MmtXAM5M7gqgtEk49racEBlflEgBXffmmL53IAeaecmeG
v5vAQhig81/CfD4g0PKiMmB/F0dAd06vKvYeaDX7/DsKd6iC0mgsuBt25ouqigUl
3EhGYD9zCgS6D25+u6fgqyQ5otA9TCFpuonVvwWAM4tngXwsTsreFrw3Uky/Z3nS
QQHDH0YRKuBJF2OzaSe4EOds6RMf7eGaSkp1uKkJX4VNbjLr6s8w8ekC3vJ1ll42
VE2iHQCF2bHkvETmLuVUj6sD
=Bhix
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5c0f2687-1147-449b-ae50-e2514cff0cc8',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//V2J2Qoj5haUp+QQQvbUQ4eu0NLuYxS426DVUWAoJeQT3
qTn/EtIiOc5sgZe0hjDeG6eR1QV0cTaaq/f+2onX8pE1dzBWftBhgvLPHAG33PR0
nWlVk2iTTVyKfLX9chCx4R2ZyYLN8zr3Po1trzeImWNotjK/6HbbbdB0pKaB59hc
UgfgRuGJvma5gvS8sEtTw9rW6UZ7TgmntJQSvRjAR56ybrgDzwyUBe2d1f8sX450
yEE5R04if1ozHjLNsu/PGWAKQJAihXlomixaH+Ar2+hClsdo7XQiVR/z3xyFFi79
NqcKjeZg6UNMHmBUDOOdVKcfJ0iRb7gDIuBDa+z2mr8wcAVXJpMNc2IEFYrGzg+I
XwT8Ec1MkUCHC+fcZ1/bJhvtiKH6cK7yxNJkTx0IK+LxNkUp89hsGLwhKiGldiLN
WrAEzcTinX7UCza1so8NwTCwgbo5ET1CnX4Oxor4xiAgZsOFxOii+45vgTBW7aAZ
CVojYojCJ/H4qyPPHx8eb2DzsPXw1TTea4FrG+iD6+3vaxDx2asVAD3SbamXh6+U
g1b4ZO25NXmbMeLjjRY2j2riTyI/9z/Ovw52VLdekrJjT1Xl7pf+OYYy1+BBaMSI
VpitKw+p0XLrmKJh4FXZbN+NDe86t1iWSbaoU+pFBea9+OzFaL6osIARouxra/fS
QQEpZ1LUiau8qFgQ/O09tI0T2a5XxDTI/VEE/TNbA2gus7jI1hpGMgukrap1UqlJ
OT6Gu7cXktf8xQwsnpzdJH/q
=nwl3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5dd27f2a-3200-4718-a55f-9e04268d1f28',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Vyowr70yl3wsuPTBNNU/+ZP5vbjs6PTqIWXYKToD77to
Jsp41Rb5lVj+rVQ3wUMsCppkheGA+B/UhtskLXWj/A4enxRsXPxEc5LUE86NaNk8
nfbXMLUkly1J00ISxknKsuwILum+5ewlwMeivXwPCO3FipTydyY+1onZtZwBBMcJ
ZiUdaSgmBUWHJCHm5A4Kj2vGG0J8OdZm1o9XM4kUjOXIjkvibmbEHjG4pD6o4+GU
n1zEeLf3s4q70pGBBnBbKg396id1NqP+9IMvgQXDsuz1Jh3Eqv7a5zl+jYg+tIsK
FeHDYLxnjluvr06YRG3fVbbD9bouIfsroZJ3QIndASEesz3M3ncFg23/w2ojaoUR
QZw4G3o3Jt+uiICek7LHu5unZAv42HpxULlRZKXlCOTavNiJvjg3mxD0LpTzaX5u
eLiEMMa/V4/vK64j5Fg1YSBwzFsbsbREMvXmQL0atDP68Ma27bmcd1rXZn1xanV6
ficZCjvZU5tH9z8S7n7hjdLC238IkjUWZXd/RlF33cj7SX/p7OakCHce+jA1XJUH
qsxWskoi+lkcbrWMsFivmxtQdhrBX0LHY5E1W4nWP5//I8kMM1gB9X1/TwhGltkw
F8dy2s1Rp45SF+voKGEvCvazVHnqVfYICX0oR4pk/ilYhCZLBsego77JWUZOIAPS
QgFkRDTBMRX+HMSfl8B7AIsnMWtKFajJXJxQr7OdQC9KhjxFLg2CUIhc8ixEM99K
2crPOX1pwHSMRbnPAgNj1jadhw==
=hbHK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '671dbb7c-b201-4d80-a2a8-028538d0f99c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//RSGlsL/g3Atp9o0m/c9BLQ8xAirdlxkYSRW81doIieid
lAHQBG8XEL3mHRjv/I3XOwWyBfQI8x+TLs8d8URDN8H91YOxxYS/5F/8dos3xcGm
5oScFdUNqw+9bcrF939HuNUhozDiM5aNcwipzH+f0MimNK6qEMe61C5IUAinbXz+
r6GaaPj6C6/Fj8mKDpYCd5DZRWAl7lQH+zDnx5oHJ4BTmR15XcA9zL4bMmZgA5JL
SxnbaU3emMHutLRDnekf/CtY1o7GGe0thvjPe261k+SpqblVvsmejm74uVI+UYCD
oUBWXq+gcXkOIX90e8UUhR5HkLNXC6JGl3p7W7qvRSNf5T8GfKXxcUAd9GEfaHvX
Upf1K9fayWoFJfP+7FhgrEu8444d6FuyQyh4/xlvbDSgTzGprNAkefQopqW45wRr
reIHSt3NMlHH32BdSEBJi83wnjRS778DA8k0d5PaSo/D3UNTcuta+xXbXIBAYSdq
SoM9n1/iLyzwDEdflNQmn+38O1XrNCHsS2PatDPouS6wOcNN/HyFZO6kB/lrEFNQ
51Em0fGx5ZBx8Ar47ecn3NkmAjHC4KlZoESb6OeIdG5UlHa4wWS2zRBCZI7FY4Xa
yHJlQsQG+eRVjGdRChU7dNBMY3zdbe/ULt9dvpQn/nuqokWVsHfhwnx2eu0Qc7bS
QQFQXjNyiGo+BAFFhk1IFFDhpb6hUgOleGNRfqdQ11/g2Pqbl63aonrEMm3JAtZI
PrsdWwQpdDjiAvLH61Yi2A0f
=4UBO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '671ed7d9-76d2-453d-a334-d30b1b2e955e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//ap5wKWizSQg62dTq23p4zEbv5blh6SF7HMltZvQ3/WRt
zTv2S3gQdIcX3/T0Y357ArBRaBELrvaJ+CGRgDe9iIUQQUFd+mU/Cl+EXNkdjIV8
V93TSYBMVvQknSwEXMBVO0jGOhSAMn3WE0ongMam9h7ZjQR1WzbgWUnBK/vJafan
AeI/NfeIya64caDa8yjw668QkLb6K/8GdidWI9f8mY31u3+nD7Xp8nASiPt/4Am4
Eihr2mbMgMgs7KBxKtTT9WiLKawHIWbOOCNDMAJyroqBYMztPAm/4/ycc62mGjY8
+6/gYiPjjoqoNQ2/dLl/vqNNryv4iztkLVo6+nUajz9MCdIAR+7H++vW4KhE9pcl
uz4CXe482oUdy/9wgp1GCwAVSVsZzj/0Cn8k9KsbszG+f9ht23ayofdXWaMeEe87
8e+mLw2/xsvVKCmP2zYps2vmZ9S15eKgC3To3W8+mTrEaZtwytdqgdKzZKAusz/7
ifu6Ti77qiZMNB0m2RB7YlLPafmtwAtUZK5nyrkWcZBKovA4KyE0HvwxAQvE/nD0
ADmKO0rOnCP4k4/7e3BRfLcv+7GSd5h49E0ZVHAAjIOsaOC2OYbk3idt6xDJ4U0r
sVTf6LlFlnSm71LQGSzKjYp2848234tbM+swtBRoeYqDHII1sNqtJVkjhEHlDnDS
PQEAiiVoXJWHEnKDIgDA7V+2knNBafne5J4tqDqdSwG/+CiYa74Cyzv75QV9+LR2
H8bkATVbS8TqAsZblU4=
=r0hU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6833c513-d5ea-4793-a793-44676b9d7bc8',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Qn2fdL0PfGriWi3XZKAPSmmyNUd9+ThQvk2Gz8Tb7D92
rWHH+zXDWSucsaMpSNmXpynyMEKeLiKaFWL3elT9EIeTrxLrJX4wjFoW6bEI4wWX
fH3BJdqAfjz/9T4xDbiFxoMSES1tSbBusCiw0+6bosN+toA/34030+9JbzGsBDpm
MsZC80t8bU4LyToqWRTQ0bVDhuSG41ElGifLEaM430tGIpD1y4fKUk+SokCmR7qM
FZ54FnI0UTnPZyTJAGWjvTyjVMxtSuYMPp/f66BGY9zEXBbsBqZWFAlteY8J08La
KftE5q+DMqIoYfnXAFMAvTA3V8hvrqLU163izwEaDaGr3WL66uusouj44yigcGJa
ODgJX6/IXHebCX+zm+7EKXL+evXC+wsfxQaGYSjjVkuYuhfIK8I1WTzn/N5NjsZ+
PeofS9h+8OUMTd5/O2LSJyzo4e0WMLmWs2URQTTYqSk772nBGazpmyHrtlnbHYHr
tCOjGGF1Blly1vS8tBSVXmpke1R6s60D7huJq6yXySZbXXNoHnA2B4/P3ikA++kj
2CShgPWLYiRMWLhOaqj61+Kk8ouj/tlTI3qOGvtcMeTqZ95BRy2Q1SOqD8DsDxYs
mgAWQYjIoqDY0tiQiJquEI6sscstvDZEnU6V49qoIh6tTcAvtm2Vai4A/IdJoVPS
PQGJdrAMBSt2OH5zuJe7UicdKh2A1L5GbXby9yWbA3WZzdYOGCmzPau6S2c/mFqF
8q2GNDzQxigVTvy/hfk=
=x8C0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6a74c171-cd84-4175-a342-3b4dee017e65',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//ez6dxGihWwzX84lQgUa1vb/Ml7sx+DPvXYHW2fHsOJhh
9WdiDh5+SWbIabTdWqAZb4UpXYgvOA2JnMlqOEzh0okpaJmifR45DSTDLkjeBytb
VPcFRI583zlfeIis+luidI3c7jd9lpv/gKJEVZr8Hp7o0WmqyHIaNMzegydlWbEN
FI93JvuPDxTPpkarbp94N4QawIhRdYTXw0/wXKnm66u0aVLjh3iUSULSEK/f1vRJ
mwb78IFsEqu7GsgHZbjs4z83PQvX/WWqDqcRVunMPURcTjNlfxehCIemqe7JuQIq
e2UncjGLARDfmXX+4xBBAiWTqmQ/lKRLAXS+8B83aUEuRuX5fHKCtIofsR+3IkTU
PUPjP7Hdc1uJgj2Xm8+Xiq9JKKYEryRXnZdf+QlKqMoR0EAW5B4x+oW5w5RYbA4Q
SCFR6LWFg9Lymsl5YZGo8nnaFq5eF6VXMwYSKtm5mc7A6Y0Gimk+JXYXaznLsPm/
oZaLofNj1KfjKBKCyXJhSQ+8Slu6vi+VPEwZGvrJX+FloBQvo9TTHTiJTOMPj6qm
c8Tn06+4Tbv8F4jylGQwj+wMRO/mgoGg1gxuMiWbFcdhKVvtH13ONhDKqxPdBwuC
obFiUvQk73GAuYyC01eZeAMlBdCBIpETF7xyUkcdMmX+ubUBzQcFFFmppjZ6fsTS
QQHt6fiNtXNGN3ElAUhxxd4MIFfHA/0PezxPwx5Sp9S1W7HEOYocvOplLH+4Gfv6
tDW+AXTlK1C3Fuao6pmMbqdj
=TvIa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '70bfb601-bef3-426e-aee4-c9a70a681d94',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//cIr9BWV5FxNcKzcvMvUUpJjOVL11beHPlWQFvbIZOQRi
ol9smYfa4YB/9fgBprLfeVXrbl08p6zwz0zuK6SFsuJwuHyoCFFztDs07jiBTRxD
KuM1AwlMiK3JvQZOCg4+wok9CKeFbagLLkQ9Sm2PWamgQulJ3YHzIkdrGg+oVSrl
S280QJGv3uUAQW/Re3zCEzCwm7/Hmkr9X6+QyxWUna5O5sFoJ1MwiDIfzoHnRdGL
LpOnWWcA7JDTG7H/njgayXARr6VGqqNCd69zqiE2tAstq/6CBTkFTFuCHV50/RmV
0JgQC9LpbEWvTO+bvEmDJAWb0yKm1FNimFFTefnoAV5u3Ypio7WrnUVpHgOTKq1b
dwo0c/fQ72qNw0VpUXdBrqXgqHZb2I8PLzRuPrNNqKK4K3/QMF3VurqeqD2vp6HP
g0ITbbplb68VWq2RNUxKS6wbBtSFLesVJ1zc0rs3SjCIAIzkosfUj9R3/m5TBuPj
IhG6E0eNMD5e9DcS9td7A1kfZf3BvTAnex/K8j4Tf0VcZBXw2EHwcivtr9vFpRmT
uKYVfmmS/xLbi6bwi5B6NMAnZ/cn+XzTHStrJUpaczacURA6LWMKdpyDpmZPvwAW
45f7qqgbtHQ+ju0lbMq6DuWpYS6Y7eKC3eZfQJHpVJXWXCJ51UclDPogO5pM4YLS
QQFXu5vOxX3MtTtvrGDhCWSqt4vvWby6NUSefbYpfqZraxH1Mem/aye8fmKgaZmd
OgnHfaI4er0LdoBnxTkPp8JU
=gBX0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '773fcd46-5749-4d84-a2b7-30a174920ca0',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAomzC67LMiIU8ap/Ab7NAuG5hwulL7GUD3IRQN9LY+Fz+
EKRT5c94WBww+hzBE0gPDp0+z1yTWLbfUtPNKM37qQWVIfCIFx6C+q3dnV3bNuFF
mwarJ18bO7z3jsaC2y04LTKEpE6jO/GHIFscXpsUmx6kMTdhEwzQGyMvXMt/TmSj
QhZ6Dq4IFm3moifUwjZ74+yDtZOKywESyO/0OsSx29EK3GM+Fij3YqroIs7pf7lj
772fWpRhqqhzk9U3kObO4cS+5jvjiZ1/l86CST7TTCdLAQmDCL269cdVBqCN1aSd
h2AhLyhwAqxrmgImpPq/at24LLH9cfVZC2CW1mI08xjxM8sm+JLvVLoPPmGwJpZj
1l5ssZmSC0LgD7t9pRU/bbYym4/gR5kmJ/hO8PDjUL39ScHuiH0NbgJaRo8N5X/8
mPQ+S6FRjeYRsaPcEsmx+zJI96FGbN90hgVDsXFggqrq8s0+NzMpXBUrmLlrsLL0
Z2wFVsM24G/0ivTrAx6r/eM1mc1k4z+PlrHC21yXqqSL0Np+A8yAHNhI2rKxCRV4
CuCvl0T0XtaHpib74x7B6YOTiY0uq2TFJCvBWlAsfBay93H5XU3ib+YjFN39yoCx
ZIIwE1dMMTaiM+XK+kmotbJrTgybe9uqG6wq/YqyUmoVM6Eb0V1RVkZJZ20nR97S
QQEcwbRRQbdvxzsGu6uIOi0AIPl6gmjSnz2mqfTydzlr694UB4W/+i4yzZieuiBr
7rWLD9VkoXMam1lHLXJt6wif
=YwEN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '78c908a5-2c14-4748-a670-2ea5bfd04de0',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAvXhSrkKF2DWbhyxRvr6iGTQn0Fqvb2OQPOVSPFMao5Nd
ZEaoeDPcjAXCtoWxlwwYpfsNsn1B5JkDbVAPA/6EdGsEM0ZecZ0wD/vIu+qdKVN6
e0s1yDCCNhtuyZNUtvcKl6sHxkChnbzKgo1lpZzKbzGRA0HN0H2KkI8wh5r2OdQ9
mnMbAYbGEvtgYqahlgK73F3p6JWyd3yaUT6PInbTPnY/EsVYLeVwq8Hyo1P669bU
+Z/gW2p4dfXdyijAyxLml3ZHu0OQXsnLk98Kof/9vkR9zEg2qvN1ILKZ/oDg7UqA
VGicrfnkXKj8nsmjoiwFCED3wFf8MbBlZz2n+t1oN+rqeRF/OJ7K782oDVosNjjn
+GobokPAg6YGii1KqdPBhPmkM/i6fhkyBQuySCIJYdsZ91nJCzEtUr12jH8HaxW3
zWFCEfbwOxxuD99VutLtRvsfNyvpD9KprBplgOWuq/oUYIwSg99RiLrydamliDg5
Q00+hWKBZoMcCBtYyLSH5bZ3vAUUVziurfSO4adl2A/jZ+txyF2Px1s3wWqYeLih
Dph7MBnqqiV30mygZLrbVAv3EBA1h7gxhNdoUnTv9spzLxN60dhS10r5c0ezzPdd
XblEduDLnQviP0/dbUubJgUFz6vameuSucfEkiRZVmNkec5rWz4pl//YyMrrJPPS
QQGHBPhY4qmR4PR/2Noh5/GwVGt2crwdEKEftCiCVLPA8gRNhNwDkZtDwWJ5iUqO
0vS+oyzspRJcXxEzy1ryTBC6
=qlna
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '841bc726-af8a-4949-a173-3a859d77b5bf',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//VlryoI8VwrEG8sgLnRs9tJ4RlOylI8K7ZAsS6lMb5OYr
gq1XZrr6q8n/2Hcn7UY/yP0kMR5BAATtErDv4ffMdg9FFY0f2/1EGVKYKoa9mF98
0Nl+LLWYSxWAyB7C0EfFh8miF7MSvJAPKALV9UZkpX9Pis/gfaqFj+8PsYNq1pv6
CRaLmad1fY1eJedoNPyNOReggJ3qKLGhiJjDvt0y5I0pTegkpqVhdd4t7qOvMm51
Tx3ZXxK4rg8XYZRN+Z9W4oBJz2NiPWq1Vijx2TO39Dy41AYys2rYJvypUm1t5GnF
bBgbY7jZG0s6B3g0mrJ5VqVSP4ZPKMIKSDXqnc3O0ZGDm8rBj7xigaj40IK4O7yo
UtlBSZrMogSY//J0vg3AAJLtttbSmx/eKiDYJTtJd0NmIcGiq2l+TsO+nckAL8t5
gv+E49/OuftUnqcFTu3MtXy0siuqkV/CBZvDd+baR20bKpnLcKeBR4jRB26Puuc5
+IwH9So1RFF1l7Ot0EcqSVrbbgRIRVrIIxXxCXAqM9sKbqahPKOmI+f/ZuYVhojB
yWO3aGoS/LwARp4aqG/WDsOyMoo0cUijg49aeFnNfM7gS7piDOGkgX3kasGCuEu7
po2vDGL9M+pWCSGyO29V3usXhzA9aYtTdbuK+8o2cdeFUprch9YUko+SJ362iSzS
QAHbm7KSCL3tUPWx070lQO7G2gxK5305Y94lHpcCSfncoCZFNWRw+oAVhCuTuPpw
qCHcuWngT3XJ+1o7C1f9++k=
=zvM3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '84dfa887-c77e-4a39-a6e4-97aa87cd6337',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwznIeob/lBSOyx5t7Q9xpwDdluNCqZgj4ZnVnim2y+GF
ikIMNAUOgCVnYp3ETliF2vfernS5tVMj323Jdmg1v48PMFImcQ4W2PYIWTyhx9CZ
pMVHjao1YwjtzTnMAWvAfsiFlueA3cAZqsg6LgFTrbrkDygHJVveic7ojOZyN+n9
PiaBNrp2kd+MA7bYsfdFBF9c+HO0jhcydxWw8BkfvplDTjY5TcDZOBTN0aFY9GX6
NM/g4oTAeECAg14CWNbycqfOh2zglD1KRgpqcly0qwtYytzQbB+09vpff7J6KGFT
MprWsVOx43CVoMbg17akt23uC35fZHlK6kFrsu01Rdg2GhEqKLZ2hUXJLjI+bN0g
1sf6DY9INajhtyZcPikJvyBIH2B3Ghr4U1ESW1N+//nMtdnSG+5DsLZJuiz2rnjH
6xOugqK+CIdRXo2rY3k5TYxEE62fkdlgN41TDp6Zb0NLUUJpTBPpVUYTdOFPb6BI
jOa1LpQJPraZFv7Kq2IddnaroCUXrGz63a9V0yzNWxEEkKdgzHBcA9K9qoLSk0A7
sf800g9g4TmMVj0m7FiFFtUqIt4cU+zx6/qCPIlVhfWPokIWIi22jxdnR4LtRfvR
J9RgJ3eG6uoOY0Lrs8VL8r1eZVREVUiGgkFZboHzdg7jsmkC62IoxGrnjsDk7+vS
QQGnMsmrR9GMz4cl4F09sAALj0Mv4sbiHT10tS2FXrtNxim7t/FunXRRGHoQ3nXz
LMzikqMlhURLcNK9slKI27we
=sbEQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8bc8c329-2f2e-44bd-a28d-59118d2c83b1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//RDV2W2a0TCI5cuqqRYUKQaR6bJBUpECd1Bi9snp789G4
ZFjhh/egRQQkCAC2E9Wi9S3FBQ6dmHEvQhDDbioL/fEAsVhwdkAbOmg6brY+r4E1
VFKkTiqv3TxEJ0SDv58CBNvmCpNM5rlcOo4uMN7cwckQOD1MpaZd5PImLHZzv4J3
6m4L3KBjM7RHlNLPK1eOMFgGHoGJV0RUf6XtqxXdKX/lnPshWWZWjmFuzmiF8Fq5
b1wcVyHpCWVMz6oNEB7OPZd2uj1NFZ95VNrDbw1R3aYym+UO6nVkxnZwfDrjovlI
h5vHdlmin1/9Dtg3crxXnCi7HOGRvi+FM8evCq904gKq1ey3aqINuBZoCx/XOt5Q
PdI3XwbcJrlUbGQDaETgto8jxB+pMlcKvNoLBIPn6e7LHttGLf5lr3+OpKopQIJF
FVYcW//9MIoedsq7KA/0VxZG9V5zDK+gL1MZSSrBX6nDVwpRBePS6d4QdGc7ggSi
DxwSMViBJN40wVS7kE896qljYsnjOo9itCyXByj2HRi7XL6/Dl18dl567qmHb2+k
h6kjd/PsyPurRYPkAMWOpTFt/hGVju2EAYXFPCCz0oZSFngRaftzQeDheFkBbA4u
YyIg84V7yE/qbO4sz83BxyHuijdZSacR75Mvq4oXsv0g6KysoO+0hWamkyCEQP7S
QQFW1AyPH0/ICa593ZkCkQdBOVHVbJhwMO1hLs7ncPKtBncL+Ob3WL/VFqDLC1o1
BmaDF5vjAO7FV5XAVfx5HEto
=TiFF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9923c9cd-38b3-41f6-a7cd-32fcf9e868c6',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAirMhY6K27osVHGOYD5Vq4GtK+zt/b/XThgrDp208Bs4d
ma0hDlATvkw37twbKXWc8/CePSDy5XvxTXtV3D0y5hFaBlV7HptZzND8+4I5QgS/
poB6u20IhcgczQE1en0aHHfEBAQfh3AORLjOt1oqhL7gpKTPiCgp9TrGMEEzPlgj
cxrfztDONVz4gmx2GhZDvpx/6t+uiOX8QH01ZwM3kacJEq0LgPnprlPhgxSr6/WL
8eSq+8F9N7Nwa+wdryFrDbxSZHWCp3TEtefIjklFvSyfbfE1SDFW4OvNszrO4evA
ujqoU2XG57L0a8POmkYU9ffz4eOdZjGRHdTy2jOf2dI9AbVOV4iUs/hdOYDBHO39
P6Vsxzcr29B4Uy8+rMgjHNnPs5Vr1LaLUYBv0pc8bIAmUCXcfmAz0Rw8UMctkA==
=A6Ug
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '99bcfd6d-ee3d-4448-a116-86d3a4dc842b',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAjoO/PXQspiMQkzUkrKeDn8NRyc9pwW7pMKjihf5sMU/M
7wUGmGq6N+qyAcbnNn9a0/TlOTSE6PpA8XIhiMYl/y0q0oDlzWQaLdUZP9tHIS3/
f7XIWksRNgHMcmpgfD6xqKZZ02ftbe37HokqoEsuV1szWTW7GbMIIxajTPfuftXQ
TBQ4cWApSSHu/2rYkzUHHzud8bHRSBpGj5BMsy2oqtLwRtI1Xq9ViVo6IMo5nFcb
8ynm/3qyiEiSKZfGQh6wdp56M/ULOZCqZeHAqlYGK4dIpX9cOck1KokVYviNSapA
QzNMutfWkbUeICA+TDbjoX5fZmZHPG5f1CUGuXhX587JBqEHkTfrwFdWWuQFG9Wa
+sF+6PC1JtjoAtOKgfT1I7znchyfbsjuuf3LtSSZuT/h19G3t1yo7KMMJs3U6NP1
ArAdeqsponFpUcBcapgkdnU5YZCcn+8iKz1X1Se+SBVRm5PEaYDiUTw10gmH3P2f
3lzzRBld55PDtZ0aw6/IRZcLyrjID5lisI1z6qo+fscRrs2Do/rHK5dm2Vc3wWRY
omEcZu7CZ8ifpRp2tckJ94ZlwKA9VPXygcBMQCDdopuUPkAWX6f++MczbBmrN764
7aWdfVKbAYDmIuH4jd1o8d383O7eZ4E4fOoCLOLlvfE2j0tCcIYkPG8Fqn48kavS
QAGLfwCsjT9y7ZXPFIZxZRyFq5o069JUDK4Opl1fBxriafBKY2A4552NHX9+O872
0TUylrGNkhCVXyzRjK/yMV4=
=a18B
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9c4c27ba-beaf-4552-aa47-41af6620f45f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAqj69REXCARwD19bdkJCMNF7Tp6TjHqmLJuPOxLDNI4h8
kWrl1O3c+yRM0DXz/4j2rpgU8gGRT8jd6DXAFy9CSJv4ss2omieeaM2mOuL82/EG
w/UGW982cl9H6EfJRqxGuJlRbp3SarT/yTrisbJ5dCV1GNpk5RyVUVDU8G0aWZg1
/K++KSEcnb2J0F8p2h0PUTv2LNkMZHh4zmsy+hqeXtzqAYupPk30YIb4CaA3jIZw
i7b00M8QhCTjBOKCVe56Moc0sdlrZYcX+txmsHbDn08RPEncJI82fUlV9oSOFLea
y7ummzZGYwau4+hYE4vp4on2bvUPI0bYrgOOBWIrCstBE1oOcw/SfWamwN0xIa4r
gIpiuecj5jtUuvY7LNtHmAVfoIEcsC+Rp5/ic/YjjabIeu0FkGZjVQFOlZEZpy6U
+AkIum+VVs5HRRN0nCEI0w2BfulZEvR3FPTRiScLe752JmZFzBoapj847o/EmU+p
/ZfRfyRGyABcR38UwjUGOrk7i85fdo9M2zZX5HkfTD68k28VgaTHz7Ge2k5qryb2
cjAs0Lb0apRxfhgm3KpiUNERYRFYo13KfUmF5eGYcbmn6zOxV7gnrs2hAiid+IUk
BAA4cM904ArAKpaV25ZKpTkGcB0xt+mZw1ra/5Id2GIYQByLV6iAh/Qv0E9Lbo7S
QAEZ+6JHWofK8jZd273uCxKAPWCX8dzPbECjyS/4jjlEUIgLrLZHkvblO3OGkazK
yNfRxpSl0D6y99xMelYcypg=
=M0t5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a514bde7-7e19-4c09-a01b-c5b0bd6bf1e1',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//W7bBsmZhBA/Y1k3LZlvmFt3x8nNI9TSAcR90lDrSajA5
VpcPTfxctZf3/XL4BWi/pMykN6gnFUjdPfNxcpgnpkanuq/0/MmUOei9udKJLs0U
YENvULw731WQLZaFCnQR6Q73HV6QmJbgm0gUKzL46R48vRalNpnDtdEm0OJUx9Cv
nWAdcNZUaq8GPagu6jdI9tYCXdA/YqbCe1+SKhAi859n7A1bTvnMqMOJ13NbIguC
IC7C2NYWf0Mrp91M/xWTx/B+djYsLISyn0nxeSD5APFr+SMB7OnqxXkbVGTRNCe5
ih+Wbjs24pYxiMdqGIR6e9d6HHzqd5Gxr++Bz1gi5lyW0ljOgUOOTCWQ8NR9kWdR
5GjBJ0Cn1F+roIdmO98Uz7Srwo00wu39+4N460aQFYnjPnBjv/MZHHfWwJnL3ZAz
gL+nFD7F7KwIWTyJTUXhbCH3ekVIpRb/gf+OJBtsGNfJI+NBj66RYNkERQDyzy/G
1t1zGmHtJ9R8C9wn9OPb/BQue7lGhtwxZDSoMHRpBk8W60ymO1gHXbip27bws998
UUTqkVAUHHqefn3i5Wi0gYQVEHjPLaDAmzW79PjAx0SYm7D9QKR0YgR1RVdJ88Hx
7+1J57Z6ub5ZTDEGDXEOv0b0QOjCzNxNRcoQVHImNk74Vol1MeWPJFIfjDGpPfvS
QgFxIA/tAO4mHi/Z/y9ipiTjHk3C/wmZk97mgQSKRmPLvWrf//I5B5V5gBtJVJ4p
BDBG8LhQWOmSFfyF4dPrMeRSrQ==
=k9oa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a756bd0f-0500-4177-ab06-d2af83078730',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/buaFHD9hlIahCIDI+fGVgU8IyY1bSdeEepXKh44HFgYw
T3h0UEiiOqlTggX4vPAn3yPqFG23bCtlH1TyshBxcL3/EDriafqmaV0JjRI7793q
x7z6kcPvrT9EdIOgXS5qTynhCETMlViPd9o7PomuiTVPBU/m+aavNpOAKQlJlWoz
o23R7w276orqeRwvfOF7+7dD+oQASP2P67yxT1MLG4ypxRllZI4EAuWGDLex/9qm
hBfx5IzZ4X26S/uvd0JslA5ezyckUO9TeQbXORasMCvk8QoEsXnzRZcV6P3WCyUk
zMRPXRyy6BMvJd2fMkJ5EIdwjvFseNPrOuyvovUCJdJBAdJnNEejgvJwXkMcwlzb
UJ7H5RtMJUNACRhnEX28k+rRe6WxNhCVAmG8j4Bi/KFqmMQAihb4pRpIMSKXis4y
9eI=
=D1Zj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af405b90-6979-442a-a79c-38ab1297315d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAvx1LsMj2ZVax8hSD707J4UwtPAkd1udra5Ufbm0i+ela
aCOVcb1ipb4FNe6v+A/YV/xbGj8MUcMSN7Z94oJYRun4XIRBTQT5Xsa0Lsts9GYL
al7CnH70R1gi5LU/JGwQwN1GZI3L50C4meIapsyxLDgT8HrDuxTPV3Fngmw2WMGm
UFtnNGA71NJ6wfSSciouavYMyyjGEWoVfI3o6rvA18BaibM2sr+lmWVlAajfAnT3
3zFMpPPIyAKScdpK4mKt2sH/eRtNW2QA90JFGMyIFtf9o4hCeEoUxuZkATVGAmFR
+J7HQySNMB6VO0+aYAfzD0P/r4PAcxtMQUL5Uc+4q7Rg1IQIdA2I3odIdrYitECf
xeis0fuYjbRbrZmmrTF9+5j5Nj3CGG1OgKTdo91MDlAtdnHeUIEM5PTJB94qwC3z
e5CQiRgvukKDKS0/8mIGrRhmzOtxXhBLrjJNR2tD83c7vyE7a/e8jhxSitd9la1M
A8jYOizS8CB8E3hyVIgkpooNdIW27ouuVd7UHbU2F8bGCGwI1RBo7+svBoC3J4ss
Fn6u59A0UcmGSgqPFQdFuJDfG1YN22iJq3nldBnNeP1GM6F22rdqb8gmTObCh0LI
b/tt0fGm759xrrfxzxlrWLkuDwsyEQk86l/jTgfhDiSK1CNShzU7YvOzlM4sGw7S
QQGQLa+WoK6fERV+uxJ+xN40owCfzCCJV8mqt73UXgRedFz78ZM5uhPF4b1VfDsI
bAw6ddaUGMp/zjlOXPx7mIaR
=y6B2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b1679946-7205-4303-ab9f-7c3ba39486e9',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAwGNpqWpYXqxHfVeP3NqCGPkx5I8ojhjEHBePUdB3eS+7
U70wHMT/IZlk5kJTgavmmM8UdcPNtkt9wiL2B8fkTFjnut/x3JpA4BZ279pfqvjN
8M4Lsw4D+GCKm/3/VstekGse/ukRMcJ9P664xB2DIQ/+cy5cZSzrk/N30Eq5ndnr
aVoc3avoPbMb7MZauIn5g5+FtDhpMhwi4XL27aWfrSyDnFhrbBlPE0kQCEr6E883
hgEQqV9YkBLve1fMWOXg0JDdPP4WonuPEbQY/t4+iG33peYs7T0MBaVm60rav8GJ
1PZYXNSQpNXOvnnDMEtkRk2DmW479Ar6caZ/wQg3eI6XeleTSAU/l9+y+yhqCzug
Qd8dmhV9FTTcHWpiPj1GKa4+biEMSDqqwZPmbazO/d0AEIpC7+fo0hi4OXYEkwAu
RNBFOZBxKBJM7KjNu+8Ab8mdUeMvSdfsMin/z5eOisY+Q3g+Ya2Q1x2WWg4Vjvly
c/wADpT67yHz5EJs+mMrvcq/0FCI1z+JmtBd3pGRTB8ewKei+SpkSBcLlVFbfTbj
2E+iFlrHkaoxTNHPKuS2n8lmT6hdZfWQjaNibnxQC6Zf+V+DQlTcZfTt4/gmpPnf
NOzYGO1hx4xK2hOwhfO0PYtrVnSGcRWppoMkV8Sbh3zx6LG3fL8W6RlRlKttgEvS
QAHMkz/fvKswNDmf1Y0p/BWA+gi1q6RadEC9RkDAPugtkniw+Rz+dtk27IwK04N5
losuDk2CGTv81ECQxagjpBA=
=cEcp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b26214a6-6475-42fa-a288-297ebeb419e0',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//eaJJyle20ZYZn23TYRuUkbdX/Qn0wJRsq4qFoQewcnm8
I7PjsF49WAYXIgi4ERErQe+W1906KPEsFUENo5ZtFyPvpqwBK1EIhgVQLFkykKwb
UbNpTC6RTsm/eR7Hf47mPu91rW+jVIfRCOKI4LKrKW/rHdttaTH/WqGwJ0UmdFIS
SSl5yMa9GXXulyFlp6v9NNihbD5Dtyh0brV0DJwncDdHlBZVenyGe35UA72wmgxK
lF4HA4g28xNYcsJ6Sn1Fd5sTPjMBt5nc+ELlR2RFCIJIDZ/L2JDo/zU6Qfw5Xzv3
qzxqOQVHHAm9z+KJUPoBwP85D1CoXOuhACc81gStvXPP/YPvEIeRWvnj0Hjg8TRY
Z/h5j5aVPCwyvYwhu8E3Jtt/f666c8UHFoMxVgDImAf3MXrNTneAtW/6LatgONuv
bsS00bKFMi/UUx7RI2xVoKfyd7GWYHFDej9Tpx1GwQ/tiN+ZtFYjU3doVaRAXWSj
9XG6Guop0ibIawAjpQHzkXkN9q1TEYNAdDKAjfk2IyR2DjYRlP/DFSL5sZFBy8q7
5foxdtTdxEhz30Fjn36xCvkXo4JuKiw55cn6CBApjuGuB9G/mGlu8WJwkx85CbDy
GymoXlvO08w1+Di/ye/XU74hKgQzQGLcyLUZB+WzIQitABZ0hTdgxoSS+86mQa7S
RAG7C9YR5q6o+LxeP2UKmCm9aHya80oyAS95PUXBqa6V6TPM8jBj6krj83PFZvEm
22fFVLZpvImf/5VlMldvOfv3Cwzt
=ur7W
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b40ad9db-0a08-48a7-a250-5846b5140898',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAlq8Gy74HK+mpNswX7qVV4J0wx4uJX0NsrDuTbb2GdihB
tklJrrVGyq7hbU3vgu/CXWUix7PTOUHJnA1bH+0vpnWuA+GuEhiFjxo8f8WKhH3a
qXf2VSgKcpHWKY/N5j1u/gtKXDLnvaf6Jxtj9+VrcItBvJHCqT6IuM747et6BJMw
f6pOpQ/LX3rLZ49Fmgo31xgUdmW3QWhQ6xoSj1orHVc1ozrEue0bTchZzJsEDf7e
Af3NCURJr7ooXU5MWdV/1jEZrOzuoZHv5H93hOjCbDzKEo6MynO8GxYviSMiZgfJ
QGr7z32zwZn24PsoLY38TtLWIzY2xXWzxi/mMmqCaNJDAU0AVKnKDbuSX6J6fgv0
YKIpdGw8cEYNFqJh9Xj1/pRXhMhwfAvOEJW13vODBDqkLcGUGI2oOm7FMziU7bv2
z2tqdg==
=jbsB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b7a14ada-6c2c-4a64-ac43-b435ef90f8ce',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9GfcYdCyXrIkewuUj91TbhqdWqMlRsVrooXT+wcd/45qZ
f1rDzY2PCTig5LIUJ4LgpFkU9SDewwnpYm/eCpwKVy3z3jMZrLpYXHL8WY/gMDSJ
cR+eAepDeyG7H3Y4a9c7mIoRgODzQPIJhPA+WkNC3B0xxjy4aF5EozKFng6bayR4
H2ba3OxgJD4x848+Jj2cvvVRExzNMt0IDr+Dmhm7Ub9NOKwRWTOF3UCsoGoRVabc
qEHvAATw3J3Sf8Koxn+1nlDp/GpPc7rKAmg/LfgsslitkuuQtLp5ddckg0VeUAZX
s2E2SAun/17zy9FJiX9kEt3xQY/NX1TLGGUn02+b2NlbekMBs6v9FrntjVsWh9Ed
L0aT56jB634DuNf/gXJ6VR4pslMYpVbZ7Y6Ae2jTf4lLQIovdJyZecr7PERaLt7F
cGKz89gUstJIMzNpPBav3uM07hSPwVCAynojh0szr9fLnJO6DQ8TYt05TddmOmo3
UDkJI9Xz8gUYuLWdK9hK+njOtdQwSptMIVxLHkQqpt2N++lPZn9cd13v+w0LW84y
BiQbID5+j1eF3KraD0Wxt1R954fUOc/ZZlE/GZAdzv3gh7bgYthzESJEuTcObWfs
DgxoRvMZjYOP/w4zaeTtkifvPR9FnoRKoay9ttYQ4PdoqFcNFWEzaz1fYjev7LTS
QAGyg1yVN/fW0uDXS6BsizkZY/8dJaFrLr34ZM6y7+6PveZ9ziz+Mfc4z4gyOskS
xdAqCLnuNNRi9LdIImq6W6A=
=AenM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'be54584f-3341-4068-af22-aee8c662f2f3',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//bFm5HWCRsi6X9D6fuM5fvIjVU+jytEDcnh3Zamp01iog
rbgyw5wemLRk/lQxO3zCECKwbxI4cKLwFwaRk+JWWmnkOrlx9ly7Y0/rq28u4x0v
Sw4WJ9d4qgQSbmHRsuIrAAxv+W2VDWSos14l937hADg8yYVgTf10iRAppQgbpcp+
wKoDyvfUC1RLPRgKNQKR8YTQMiwN6XYciF1Nfxe1isc/H0dXcLEU7ik8NeZQ4tuq
Z8zzb7TF40aa8rLYohJA7itAOTLaODl/XGa2cuuqZKq91etxrT53YJ56H7z9Of4a
3YR9qQsQ2qY3tIDmTQsSUgrVubGJ3jJhBfHCFYm5tW6VLkFPlnRftQzQuhwlo/MS
99lvzdaqrpPJwzVKNjWCbxqJXlbEj8mY8mmkcoTq2wSUfJJHhMKLwDwq3dBrOdD/
0IvGXT55YrdY93ETpjNF6VauD07ScpE0mlo1g3GT6sPu5Bdk/809Z3k2lrc/NDGD
iezYP1Cfj13cwHt5qinv3ixL1DJ7hQ+VDHlSASqOvZia/sCwcITJQ8t3fAc+mnq1
5QVvtIxOwX+S0LpQ8gYiD6Uq+2pJIgyuswOvE77782n0UtHV7kxISli96XwPGpJ5
qxWbbnl+gDbJCoKtdgUouP//lwKzcUdbgEgBnBsj7apXlWLyn3+JHxlmY0OXtYDS
QwEh2cDzzh/cXJeq9O08PmSjBPuEcMfrR6MRSHuMd9QeBDwsSAvZhNMRGpD+PICX
BSIMFWrX3qBChn14mRFZxt0GZDI=
=13NO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c39364ea-037d-4213-a550-a208b29d6b16',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+Pl9luGOQYZ/q/hzbonhz0840/T3wGnMy/bTh2KLLJwFE
Zh/t6Pmgsb87VeZsxDIlxsylM3Ddb3+yFGnYLspzTMi+BwQPdNGjo2MrclsZaSoi
ecaShXWXs6+42OSw9k2WLdETchxK2j7N4Rh9InXHIL63x9rl+GVoBePSfaHXFcyO
pd76sRpGzZvTZZxl/IK+N3jPiNBWpIZjbYP3asxrRbrFRTUfCXPBSbPhGIFm2Xsl
I1jCjg2l48SH7+W7uztH86PFGlI0ojicko++TBavL9ufwqDfTe/0bECLaLn0HMcE
rpPxooKrVzhg6tyK6t/RR1FCvcpFFpPyOdoR5N2ezxxs2sEn/U1iC4PvnoMk8H+I
BkdHjKL5thNGV+pJnR/+RNcobA/8K1rC7iqhNbxDUIb1Qc+1873HaiTocwcqubxZ
eCnO2aJ0Zlf7w33SqtNbd/jv5JYw6mV/8lGERjgL4OHlMDenrsI+PKN7sdMbzOPL
f+atxtMV8y3lhJCvsC4iABdPjLUik4M+hI6Ie4R0ZQ4CYc3NgQw58y2DCDlFPR2Z
khf/1+uD6mGrYFhqvM62nrR/qpX1mpux9SyIvqMw4vCmI/1Z1Na65N1mEFpq1Wy9
peKhPnJNxJGOa0J6FSTEUttOsyk4SuSXDAuuLKrc/V4xGoxMxcW+gjfivEpLueHS
QAGl0ba4BeC4HjyMAtSb3qYkOXAXJQGzf3g79p9B5tcB8JKihqFWlVdy6Te0WpeI
BO6N4bRacpM4hRKvvCnpDmw=
=eWRH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c6921d19-c7cf-4f72-a367-5917c21190fc',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAntwbswKxtl9s8Y4PqsQ5sz9be+g3HLOxPFuwzb0WZHQT
Gv7EBlaFTp9yMeNtvwxU+V2YU98rZmixgEqQbQflSO2aTer2s/mueNayzgcpOx78
Cr/3hlZoM/cn7Ah3zw7F0ef67YQQUcx39Y3JluWabComwKwY3oS+rSJW7R2808cu
lc+0sJ/tHfIH6a9Zau78kJ8dsMJEWRJIXEgjPtiEcubkL1cu6rYw66dyAfaZeNHr
lp8uhb2w4Z6gLCzuLlzsPeMwzwwG6eFh6cuwV88s6e6cFVW+70BLxkyLx9sb8rup
f+T3IcSi7D2Gb1i6eHiEevjcuGbs34IyrcetE61Y7Uu7aRLZS2OKMTIFEyvly8Wi
WbTfJR++BgHrVE98iKHDij80GzX1yxGTQopTeQkkDThgeo2Ul3NOUh0MvrGP9lK4
rutQYbPIOe+LfPIz4hdnTzLPfvxMFlzmer3OvS/2hvWPFUjsmhtFLvfLAKy4AWr5
/d1/oJE1o4KSBz8nERQH67xOTzdK8OxupBXtbmluPrt2/Q1MY8G+Ho82d7K3YVjN
Y2W9frSUM//2rFHSMH83j4ecjIqIX2VNuXS+sVKJju37zYOcrklrSldwkjVB2gY2
xhV2uKrhDBoOq5iJF3NAHe0BpUlsXjOmnu2PakBVbVLDFMxSc2pzxhOJQUCtW8vS
QQFxCAmTWYKOECqXaWrJmAuugcOsT0E2XvTw2rbCYy9LAarRxM4r0f7DCzVfO9ke
mriM6Cms8kTSl0VGcdSGMhKi
=tbhD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb27f86d-1c88-4133-a529-3f2b3e6473b7',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/6AuVclkV/yC1bBxV+1u9Hi5NkJofqBYyhpxKEoc3SyS6U
ghTII3wBr0uGjsmIUqqgQvluqiVfd/vk972OvScaKY/l5lXpAxfQFZXDflb/3fCh
o2/l1T0t+q700fLSsjEM7FGfOsXPVGBCHxOXqRZoiB3aUj+tlD1mjlckJ96UCmDX
YABjcxzCkHWPzkC+YbzdNjfKfJedR8thFyr6RIXtV7hTwmpnF23aMsdFnP4lPPpg
b3evzByNS3X0SXMp5GmCX36qPTBJ+Fy63Q+6Skd/6q9fZLOik9MHuV/a/vMo5NOV
vW2IAoRpIq4AEgBKj/m1zOit5KUPZt3IMTRVjti2d/uQqyCYVcMlOBxBMok3S0mp
a3oR9+xMXvZ7pntaYXvKCwhPxIjwMdxaZKmuPLsLLetOR2344WJoSVcfi2KGi19H
oHfSRK4kGOweyi2pxB/uTiIqlSqcmhuk8awvy0HBrwvvZ7tegRoUp3n3kpuezId/
SbqEd89zwTu+bCArnkY7vXtnAFNFYtPZvrfMDcmnO+6LKhYd5YRoZd5uWdOHTafN
Mic3T6zHBusTLVtyv6UqW3NPVi8VHCebG07uDNZ6M+ul17HpDf8pA+0Qyj+cGM/V
cNo0oiXcW5Y5CGou0afVjoA+1xtPbon62DA8+b+rytFfW6eY288wD75smehaBSnS
QwF35cjD/DZzMR9cmHA1MQe05WCAM10nMHIgmOgyPYU2OGGwQK2z+RN1KBsGSley
m/oNOU6qEjTeyu4PwC2LpUmmaN0=
=axR+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd0bcf232-5b59-4154-a05e-b4e4ca8d43d6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9EwcXHg4NYdI/K/aFzPECexkn7KwGOj4oZSUxqeMhW/qB
cdi6J9eGJSy/GH+zTtrRJbUs3zSxvo29gzDgJUDTqFxCGl2+4fEOWFZeEmiRUwAD
OaEWdYkd1Gy+9xusDAer+kFs54wz6YqrQcAMipHmV/QR1OnwtkbsWRWRvOn1M8Tq
u/fuh7GO+v3zHKyHyQ558y1PqbwAD1qldxzVZkvU0hogq21xbWQU/Yqp6PYobsbB
m9Z0G6asGAjd0vxTtlb2Uq5jJ300eOlmbHTR+wGUSkPQZDGSeMgTpr8p2jr8xZNR
CHEjCHMRqNMdB0gfQ9ZwRc+ZEFfKn7i2n96xsnQuAA3hql2byjJ0LlrTT/VctL0P
TxiWykGBiiHgP61UzFhLOtiilUis2j4h3MP5S0SKkwUqAwITuS2cYolD3Cwr/Xqs
ETgCNh/l2hymKmhT/MAQlIsA1BFu7ukpNAusEiMFIJZH7nUvE5XMstr5a4Xq3WSS
CpsnWpc+PZRvrSTcRsO2jFfp5TAS5gCgCLureO5aanPvZfKj3kLCb+ygmGtMQ0Pl
MC53nDq24fUcFOZXPpF61QNvmex+XX2ic7Zk+Y+ZY/NuvhZpJE8VxomQVG2PPfOi
LAKHIMIVfP+mq+ajA78acna424Gk0MOj0dOvJLOuHUgPJyW55Yqvnn0gcd4CL2TS
QgGQH9n5O4kokn2yVjP/GpByIReQJ/irwND7CnwA0eCFlA5+WiEIFd9kL0qHndFi
ACYiVvFeuiPgb/K38/gVxInH3w==
=+1GD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd852fd94-2d04-4e91-a7ff-51ebee39036b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//ajbpyrRWRy9SK6rvi1ps7h05vABZUtpn8FGQUsnDvJJH
j/TIN6jnr8ZXzKRE1lEQPQgwA7F1FtOVXDQKRLA480AseHxeBcHWl2uoSAu7W/mt
gwzceTAhykK3wWeTp+64iz1E0W+/XKdon9Qi4Xk5Z2hT/qNlDatuRC9oB3q6/S3X
gbz8Ax+o02cuvT1A7yotVap92CJVKMqHclGAnuL1p0vB280Ed0JqhBCA3lueANjB
rf9gknaHfLVe/XZBqZTHj/3IE9IpArqcv/M1wMOLpT2ZdRtTZKOVJebXFRdPzPPG
rXpZb2ni5RUPxDGdwt0jUw1gnQrpMc4t9DKX3WgwvNy0KB9EfcZxIEXqpGnh4xxf
9XCCmwp5/5t++qnDJYN771sG+qgafujvhyU1qkpdLSN5w5RZp3UKd3k2aIWMhCKF
HT1/sGXg1KGiIGcW39Z5rPaFDsdpTbwA3GVlhP6s7Wgdv2LjqTb7c8jp7jdUav8v
Psf86tbHnT9xyrdeDj8logWIr6XkfyAU7Qn/hbg/W7uAYTIXBg0eQoo9gH6W94TY
aYA2LBJvUbQKE+dh90/rK41C3Mh5E2Srm8yBiPbm464BJA0yt99QuB9hFSZULM4p
LNXuw6A+limChzDD1BI4B9yGgU2X5A9zr7iBof5/Fum0ns2GWPtotympo0ewbULS
QQFKzbFDQOpNHSEl/EWZdaHmRGjhkM4JeRtaqgKSKCVi2+6D0OQKE3T0IkSO3wSu
m4SYOOyqSY/XD8onahTo2/wi
=NDud
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'da9c9a63-c76a-4495-a687-4081a692827f',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/8CBhtKEvnxFlgCg0L5iyTecOBzWPUyPbIK4yAtpmwRjN4
YUbWW5mdsNL/fW5fuaqkcH/IKBy5Vth8I5jT9nSpPMMW8mn7Rq5dW3URAEX2/RQM
OWwboTVmZ98q7YBDWZcs98wW+GTi5DkrJin45l13CB3a5b417NkwWE2kJqM6Xeg6
bACIcbhEdkqitm4ACdeUJXr0GXKlDoxqXsSoVFQkqs9EHbdsmjiuqdwuFSCwytQg
Na3jc+gNNUivjmJDwRg/XMe49klHL2WL4Us8JXDGdpiVh1T3QKTfu8U31Q2kI2o1
kpCxzmiVsOp31vwrK2NXBukRcQaLvTQqZtPXSXd8ubstrBO/4o6puLNjGEja+Q0n
EetQDfgNdC8dXfzd+nuTTggVh9MUaPpYlchbJKzwp04YJn0tHwjo4XJ7WBJwO9P/
CB/r0vkhjU54tLp8ad16uTmQWP4FS0Z8Y8gNCzEWzsVbIS6U7jAkbb2MoIZmqf1F
36S02iKgWz0SWh3ywtLHK0miLo4AroTNbPP0bDOeY10vH/mKUtdN80WIxHM5q/xt
zDndWw+icvQYKLuAokHGcdFjddqmw4fzx2t6MtcO6KWoZ99An+VOLfc6ApgFjpMN
TxHEDXJcnsUU2XR/v+Dxc8GYfqMRFYSF1dj2BgGSK2Qfy3Ry3aCEvqpbuVw8jiXS
QQFZNFsrDIrZA4MevUGqgV6de6hSjWC/CfYJab4bnJERLLC8Q1IvySCCsr0t6mN0
uA987JK5GsH7G8eg2OdLNCiU
=0Tc+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dff1774d-5c06-4a07-a202-dd7245144010',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAsw08wOeNrGL0sec1fcPqhgDjz54ySK5efKgUfgq8CVky
Pi1h5nNZXNMqnQMFlxg4FFQWwEGDkaNucncbePIdiwWOYG+YYPMjAtKK6qokoAe3
Sm9NdlyYUjjChLL+qW7NhbaGg/xIny7tGCXSU2Z/KyQ4MxBWdAssGx+IHVeR/eGN
B2zrAWHLwusC/3p8eTSudq/zMs99z6gvsOAnrtS0cPXb+GaAVG40EZ4HIL+MbWs4
kKL74BJrnapSNjIm8UqGOEDUcMGN90c2UchemfQ0nrDzHgE8mVGb6oZkSrKph2Gm
dWdd4h4AcJjjFz+VofYbTakusPOu930dZHqPp7JME9FXFNNFn5lABlMrscUZXnOq
F+EkYwNtnmRpAHXExCyzvNu18DD7xKSAo7BzX60Y1TFfxPdXJwj2fRukY5b1K2Gb
dlYwHG486Qsh8arrBctEvOkomzKXf4XwoxpVb4kJBorkyAYqUHrQ8/a243FeQMO8
OpeDSL8HKkb1n2bjbYG7JfvbU2SmdEnvseP4irZYNW/li3smj2zQktfYzDbbzpsk
6LXuHp94eLAQi9OJHPrIJJG0buzGu7K/7+Sg/obC3yguzU6VUheSi3a3SwdiCA3h
lsxCr3sFvCAWj9DFuZxDiRzp0hZNx8x0WU40pqXln2urXZ9pmBFbOspvvJlAVenS
QwHKyFYTrRiN5YPmmC5XwHGfLOyBHyjPetE7Wtt86w8SI0FlEDOcnvJFrbZ/rkI+
bxYhIjGVTaqcvAJhWyJ9ZI4S9HY=
=2hEO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ee493b69-3e17-4ec2-ae1c-2a41ab0fb396',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAyEePAMNd+ZRdJH/T6vsmnqquv0Dic/dCo6m8vX3MNFJo
uXGekH3dvPIZryebeVcQ6WathLrOGOatReHvwyPCp/WNwO6HEXYZMHymU3sLKITj
Mm7kD+ayahvzgzZ2uGksEgZsE6JuSJ+kff4eRwkDRWKyV5kv9aYZwG6W6KxhBl/L
LGFeW9R8jcBTv/QIj13mg0dEKqjMH6r3lQrBCjfRGIsqybvMazwMQIQTc27nyzJz
vaFdXCTAQ/wl6v6WgZal6njVExDhF15j6mXktgS24tc27SMt8WFnW5Ito8UhDu1X
vd/WLdC+jfNqyxuZrGdKb6BClrCtCZUnWxNSCNqqTOwveLvoMocYNGqmA4kWsKYC
5WKLy6Bkp5GQ8kFD10sfHc79uS1vWhCJh3n5qNKtboWJsvwOMtQHeOhaqW/GyVQj
lJkRlOkRROzT4WGMVtk4nweckx7IvwNRFt0PydIlj+t+Ec12h8ui86yNHtc/yWSK
8thfUo3fqrCh84aBhQKl+yBkLsdyvPmX4OlWQZgsR/ihSoSaEvB0mL9BaW/7Z4jZ
xGZ6WpO6iiEOuAdase0VqGK1ERfROwp6dvlHGZMt4mpyhiti8EpmORPvNZjCOYAz
dtI8oJc9Ygf/f8XebaXq8SxAOcfRRb+kc/ns6fALEofdQFtis+kZdyLXEHG1ZwnS
QwGR1A2TJ5EP/TTselB51eUzJ1KUD0WtFK5Ym5qFytvmwoDkCqbivUGelc2CeWtJ
yH6B5zhc/0PIHjaWeM0WfhtVwSA=
=9A77
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f6c29b96-0378-4d6e-a2e9-9726f1dd8343',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+LionG4Y1UjY2EfdEqIn1R/pN0i4U3QC3wPDSfIeqPl1K
WGA5+qbdPn69iG/pdRINWBrT2mRZIx42stsicMSiXxRpETvZDBjTkwjDjFLYHfOS
AqI7rG4Fe6eAjRKhtJYpUTJYWRTmSgR/rL+vETt5i29rYht6ZCT29rhfhRRgHkdn
JuaicEqtiWqLWorWOiF9gt9OLdvhihoZkr3vhfgTOZgc8NOXMcr65biJjTtgjWu9
Sq2ltV3HZjU7/UmQphHatfOj8ga8myGAO2rgGyX14cf9n1LLkMyApr5PcYh9dX6N
j1dtLqzSTui77fcS9WyV7KirzXTz0EMmG6RKvrZWCBVtwh2jFTTN1xznE6Jkiwtb
AUeipuqIB8/IPSjvn0lTPiu0GU8riPDiiQOaeusgpHMfllBQ/mAwiLpH6H9YPv0W
Z/fCOPhKnZIIzsYFO2L+LRl1lTn2wfwZv4aSWKnqqUjSwRBLE+7XghevCe653U0K
0+FQDU8m7ZGT5iDtkU9ZauTAc4KDqky/z6pouF6mg6xdkF0DOL/R5AuoTO1Mbw64
vzZu/zEy1ik7prIKOolcYbSBQ4mcLfZgWVjek3Zx/FhIRz+OxJHlrhJLRWXg9u0l
zDiTwjz/s0IvG7Es3b99GO7Wx2MbdK74n+nAkQ46as/pBP4eCruDxhwKwyIcR3fS
QwER+dPf2lx122aK9B7E1tn83L9jaWqP+Qt6UqtPUSt1qnvRNBFdRDQU9kaNic5j
WAmzb5OiOSuDdrM7AnNSg0slIWo=
=pEwf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f6c79128-0891-49c4-ab47-7ea0c6bb72ae',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//SjvSDzgZwFrZQuQb3yx9Ya4qpIa4D/5viHd6+uISaUhB
5rUPaLUWaZtoy7MC7UvZJP0k8tETkDK0GjtapCOT6M94syLibMIvvxs9uj4paHYj
/+ceWetS/WQ2igjP2bPEVZryBJkdwq4iVHvriSjqFlb9YLc319dQpNzGqf5rZGRL
TcNh53MgtNWT3u+Sy30xVZCZk1YzxKYnZB4s7JPOas5KXPDgdqp/VUwk8uJ2O+ZW
4TilZwrW5hspWXwpMJa4kAHc1L/+5vuJXPwevj2VpvUPDgAcjkcXAE/LQq/LvJR9
ptqHTQmYkVklEU82TsvzrkENih3EKShvieUNlDe+CwF9KRRc793HnmARhwLuvzgk
cX5rHwY2GYcnJF8xpiwNhD+C3RuKiu1tDAYA6t0Uz3oM5YeJCwZBQkXjw1op85CF
7Cf2jX4agp56KWY5US15zbn8e7+wEcZtD+dyn2NJJQyX5O4v+3AQ6FRfzQwARl+A
xj77XvS2JH9WLE23Kb0JHbxrlA/EaUIDPMDpm+mlDAHQ1beDKhJ2F30XF0fFI0mj
mv5VF48e/k5WfvS6x3lV9h9W/KB6KxsHNRLq3Mexw55zeoBe8Gd+Lg32Z5525GAv
e78FYyDaMpWToU55oUxePDCtIV6Sl1RbCwBEMkLNXeQ0GycWi00y6/K0VeZJOAzS
QQFJsNZLDGB4tL2wP+PSq3Zdf63KZQGqWosudS/FhFdvfhskTwUHQnAl3vM8tVIK
TJxMlFPya4RrI3wcTKQxTiZO
=XFvV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fbe23737-b51c-40db-ab90-eeed6f9b3c9a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8DDh6iLL/qCbofEbTYoseC7SjpIFlcwYJCvps3jVVphDx
GwYS6GwUbMb5Fy1q32i3kCVwMFqIDC7kcXdyRi/uRkPf4WPnfvmx3+wpF3Fq2U2T
LpZmg0f8meP2+nrd7v0kZ0djqnjQnQ5hCxfFybxwad2CQyyxh64HIRXA3FM+2KKh
aptLokOfGP+cj95pcIQoRKQFNrRPpAYHWypjR8YwsLIVmslBuDBpiALzjDpeQbVZ
KobWLmiBJEzqM7WmG4bL/M3DYZDcR3ax/jiL5/QnfbW8slfERU5MXVm9K2ep4yjv
9E1yt44gLa2PUNRX8Atuppy85RDio5Ia/ABZmBumkZQ3DMme/DmxPB6Y6LIHFAWY
J4prqz4kcwjet3Ro73/1vY7Izqur/a2h7I8OMYlK1DBeAFX/dKZeWP/0olhPnfbK
kajaXOkzt8U6zlVaSGdkcwrAyNwUorKI7A+SNwAJ4IDkBjlfRu5BVhjGgUmq0gLT
2kND9LAkmKv+mcTD+2vfUSetelJ1cX2g23xQDkg/DZbNZX7CdEHF7IrcvXa9CVhO
5xESer0oRpRDc+FxQA4vIWzdB2r0ynLnu5l2VMkCZsDPgtnPkWIPvF0/tr/H7iDo
XP8B0ruPhQdQh9xaWCy4IdAN5nrM1QItNAzJvnh5omOuhJjEr9qeqy8C47GyvSjS
QQEvJtWlGWvUhHQHBz7PeHX8CvUauDPyB0SVntyjIFcmxzD7aWxuLK37embRMvP/
d/kgkKePV/UDuiElyTWhJN6o
=lXXh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd9e6b86-7697-45af-a898-1df310c62365',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAw0+NlsTnygZUPTmbWRdDhTC1s5cfvVcbn7wdaFzKuiJl
rt1eSLJfQHJ0gSDiT8smlm5vcURGkqG1XDAti89a9zJYIls7SgbFVwl/7SwBpasp
SNQg5E45XWjvOKrGaRrMNV/2h1QVO59X2lCGk7cLHQNFBpKIzNrOf/XghHxDgFmT
D3NbxQaqfJEyWwlsWzhDUlc4ZADf7rb3CcVi+1scEaQgud+yypsgbtTL7FisJiGo
aCnAVMpUWvz5GZ/Y4n9nvSdTJ/35Tphuhhe5GmBANVsUoInSLxZZfrEOIA1oCUgD
BfUPKYrjdoPGhTwhR7zLxwTQZvu+KnI/BHqfOQWkPfmPcpSfokg2ChtHNbFjvzWz
iSzwNA6P1BcpwxAMD5nulG4ZakF7r1FaM5zhH+/5m2grqCbnj0o1jpJV0MwRBuN4
XcDfcsPxo67NI0R1EuJ5R7Y9fqQfR51znGZ8s6HWi32HYXdM4vfBRBEEtedw6n01
jG08GkMVkOK3aovytj6uvtC4LOlk4NLLp+NLBEGqs9DFhx6FjVvAzvBq6w8BGluv
RltsPSj0/UUa7Y0kC9gCbJ1siiNoZQGF1cpraNC9Zro6RfCQp3MbQo38MwUhDGSY
rzPhnw2lWrH3f0Im2oNG0OOlZ0UoeChLqRmlO+w/uXWnlK3Z2OH6St0MqXDZ3XzS
QQGzWAk5Qi/iU98h27uUgFTVHsp6emaoJwXJXj9LAQXl77WpXqph+ieJo5wgRpEz
WzTZYyaAc4UwAl6wG+SsbLAx
=TCmA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
