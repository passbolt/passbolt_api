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
			'id' => '008a95ea-3cf9-4dac-a197-49e1a1ef1931',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtx8xn5Ap+AXQFZC2FSKRoDiVvTNaclbH4QpK1bTX9aEJ
5vXGF1lYIVQk33FekaVn9WQ/RyzSfSjYuhCWG3OEbDxU0ZoPf8GS4TcA+2WIwSDE
Q5FglJ/L183X9uJWGq/xXkKNt5W+47/zylHMFS3S49LpSSBCzTO65lNXMvNcc7GY
9MD5M/98tFTySv4avyNpNmqckB6QITLbrnkAa4A4qcEo8SPqW7LWvQBHgV1HvHTU
Mkb3CllDMevesTR4Exvs0AhHyGNcKn4Y9pOhaUqrwwJpqIPi+KZd9bK0zI0xuVvy
TuBDbKc70GCtOZ+u46m2V0/kBMBu0L3PhZUe44PUjEJH2nj+E+I77a2/fIKOA3LS
l3EDKK43YCHo3yD6Wq2wUYLyT+qOt5pqC0rfVyie2jUwixPaXijrWp6vQ8d/PnyN
AO7KX61kJ+8zKKPuPwXcijREMgKZ7iN5S696Ej/8JbZWEVa83wc1oPArvaLpzPeC
HFarHj+cikb2jqRynTIY3C/3kw5vJ9CbnQDCcPfoHrHlhq+rhTJjTUha1MvlFyKg
tBwA9XoqOx0uvmMxpsMp36ks31JxbsEyXxzD0TXZEGhe22CvDqlKIQgJ5BXafmpo
ToUynoHrGf2NzeKthVGZnsN9v0RuBUAJG17z64KjRVcK5ElnEQSO68ZZNTGVIPvS
QQHMVYlHzvTr57FvW+CBE0YzuI70ls+F9qweBQE5XbkYTh+q4soo9uTe/ZR6dTo7
MwcM78OOTslreut4aRH1qLhK
=RGPF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '01da6128-797b-4bf9-a5c9-6e26b394002f',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9G9qDdpIFhXm/4Fx4hKHud87FGYELYDDckypdMDfapAIO
H821qIruXad57rkGOY2nxtAOfzDuFRioD8zcc1OpUelkQyjaaxTE3D9rSviVtuSs
5M0luo4U2JQak09A6eEFicd4BY/Z3XNW3I2Jk7DL0+gTVudYtQFVyqqOBsjgjyGj
7dLEGMkpFU8aYZ3MPvafutw2Ad7M2ZXAjMEBDBKSE3clMiMT0SD3RIHInw3Bn70s
/HIfzZd32gXO5rsft7pnNs3ywmr2AzPrQMJqVt+L9NxI05By8DNItxUoDsVCrBBh
4wnhddvRyVtOKhmoVv47OsTqmvFCArgnnwvYrJwCuYDKJ2o4T8IZP+OCED+dQyVQ
0DUAa90EImykEp1EcjE986Op+eLDkQlYY9IJ2D1l0dgqYzsmu/R/S7rvc9zgFqN9
YXcuVkAsBIu928WxNPKYcdOO5IxXFnAoqmVQp9L1DPQRHrwVzvDPYmhGx/Ku658n
zyqK5Bf9836qZGaAjlI1XpNet6miMqa9vzkmCGrv6sceQa3EygYQvlsI31em30a4
RSDPg/uJvGn05b39k0koBBnDoiLJCu5pItcoCd6Gs5x9f75jKOP3Bc8rGATeKxWh
zeiU6QUmlS0FHOeV8bgFr+3a9wTNNIC1XxwMkNTp8bZ+nbXFGY5HPE6CIyZv/TnS
QAHwp5+i6uKxFUWHbP9fHQ4+Nrb+QpjEDA2W/3lGOhFjz/j5rdS3BQpGtJfFvk9i
VdYHMpE9A30r6WMflemFcNA=
=pKNc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '05291630-c560-43bf-ac37-ab29c2b8890c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+PvnV2dbEbcoC1YCIf8rNcsT6dQd+mi2bSHVtuZhZl/w/
jMctkjYPfYDB3pNHHQp+iuA7URf6oSuL0HhREYuJQOVDbukONHlDNltDQKibawp8
OyZfm5aevLjV27QiKDZvl268uX1Egi6akaUfNBQMw3lReSSuyVt6xfupYLmv03XI
sVyj8bhrWWG6epW3POG4lcGBXoYsg8Nizlh70m+GRZQK5H+rlRbMxfyZki2TsYJA
HJFmGZvqOxwGVubV04RzjSIP30ddXNH/A869y455cWqB8uyKWI1dyQiG5sOGN9B+
5D9zK++m7470fGQJ9aRACU5e/NMOymF1XK4AoYqiRuofnvXTHlCsRHgdIDmzLemM
e7nx3tbiO2sB+vwfuQRxFjbDJ9M5NNUPblSSlZ2GbmRTZqZ7cFa6XjlcQFViUevz
VhgdvsHszDSCybuMnT1ROwgaN+i8vqfhfu534RiZktRAxoN4MfV/DywkmGYUPiua
OtCV8DHi6PULJ9yJt9ISzpzdmwmAai9BloQ+wU2a4ARdJTfkbEPt8G6miZnIZMyc
f71WZz9WBT3FUBUFCCJ1p86Hc7Ve9o9siHLOUkx60bvAZ8zmnjVLUaQpZVzvVHc4
GXc3Y9G/SVqpzdC8CNJt4Ymwx07fHQfX46eb4w6gzKkUVoYNwM0ox9Rp+787VrbS
QQFveYbGhDU6MFklOJ99j84WQcRFYqOqwx/9E5pO7juQ3ALeW1+19XX2h58FYmG1
hb69uPgnIsSH8+WONKMT5rKQ
=Ik0l
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '071447d6-0c4e-4e42-ac61-0de4d98d66e3',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//cHsnR4Y2T9eoajqCM/90PqQJN/E+PZg1t6B3gt3XNGia
4PEkBWmCo03HwQmk4nAmR94thD1oLQYWD8GYmLdNyk6yEiI/6GYgjJUWke77Zspi
bAJ1n9qPR2kQYZwW+HyC6EAiQ86NM+nbatsmsAhh6/DCqCALSO6UpLwYXfm4T6XN
vWNzGu9Pen/WxfRa1qYgYPNO4T9RMz3o2OGWi1sWAQotswCFx2tlnvmsq1FVacrL
oQiVJ8wUzKIJpJRkmxg/odIscxqydtQ0xjKqKUZUGU8drTCDkUzakc7dWIfU6k1e
q1pJOcgLXFIG3LUwY9VW214vWGFOhfBDY8f3P5hOfKjuW7fOfEmjh8REjJglgA58
692yYZF7NTrVGW+Zf9eBAuBb97ywiy3ONn3GYOFbRnXWhXsNZlCQ2reimprNQtIe
lTNH0wwu8dIjtfF0NlAKST85eUZkqmZOG7p6D5Kjn8e/Jyh6Ppq6Ev0Rp4O4GmTs
SfCufSyUWs8tSK/2U5z9VLKJ4lyEBzXRT06Bv/U27WpDdH1PxGPznrxV9aM/Ef/W
jt9pXw2gdf32xNK1/+1z35B1LQpOz8FNmMJKn3l+PPdaC/wU+1riFPaA/ySER0GT
hXm47d5gJ3nESWgFBbG0plEaayk7v7jvTtEuwwajTARWcoea6IpNHRgCbaYLSC7S
QQHvF+G+oBr8OFU+wtQ1EPD/bQGKwj6ytclQDTZ6PhbUiQ2CrvORERUpjYrnEY6S
N+QcHo4hXEIz/KnaDckbHMmq
=7mwX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '077faec5-26ea-4a0e-a6a7-bbdd0d3130e9',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAod9ShNZHLKc0RiVehcODpj5nuvCZqwdMK3L89BboyDON
GY2OoDiCf/NJmFOWH75cPllb4wsehOUUyYnBPQJB8hJ6A6u22Bc93s0+b2DKf38A
gUgo2lsQUpITly2n2ZJj2/uD5jGv7bqreXW0QJ7tFDTuYoj4a/TibwC9wFrsO1eH
TIXUUnPC14W2CcknMywFEnIkEu9hcYkpVfh92VwStS9GfoQBCboo1/1nXx6MxrQ2
UHSa4dH+YHqe4/MMrFxQLQCLpcLEdxHehK1OUu7DJkfCSKNO9rLDfNYbK6tFf9SQ
Jx1Ezk2qL0X0UwoSqII6fZSj7Z00zGpqggwUivsbyr3wgjuQoZleDOYtmh++qGVj
o7idMiOe/iYOTqqCTlH2CukMUwAByjV9vkdXxengBH3KonR6r/V7ow2rK7CMaCMI
alTQRD5cZqGfk9cimKel8eaPlK3PmNDx/1FHetiy+PcMQ+O+vLRGOIqSrGYLD8rB
KBxKFywhUxnJXThpO6RnQGGjWHQ6aXFkjaBXRVk4mLTFjnr6x+W+omqNHcKQZlCK
VYeQJ45eCSezP2zv3Dl2r0he6q6e83C34kAIVNulQs6pywEzHeXdkkBm6AL4l1dc
gPeAoSI+/5mmpreDIFUmM/+iSPA0yqrXb5l3aSSWwNG6OnAcn8Cto5HoV8GfH+3S
QwHhbmFW9QuWv58V5SpS4EKskYPzzTAWwNHJfpx0+eofqPLgIxCoG2CitLOGw8QA
BVEquD2Kw9v6d+HxHQ9I9DetZn8=
=grY6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '12d77205-a621-42d7-a98e-03377a7728a4',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAjS423BX4qEAbxL3KSjCyJKbS4+tDhwFW4gF+mRaw+6Fu
err0JytHO3P3S73LZkqgwncn+nGQpWDeyklGJ06ao51kxjRkbS1oGTQsxplLfN8a
pNkLuD+0/REBJNpt1VvctrAnnW8dLoxQYCRGBnS2uUtaam9nRJ5Ow9QzeI3RNbqS
sarxtpYaSRN+wDB+tHDavfpOJGTgrfIdaQ6iE/QTelnHbDFDkkrY0u9HLn0OgTqb
zjC6Id6y7axg306/rQfkFcS0qjeJRmptbPJ6zETCueQYYaCb2lqCtX+wEz4YxmAZ
/gkR+PNRFCuwP9nPmeR+kCdqzuhSafHbtJRafTDQidJCAcARGY3jDP64EjyidA0O
XPboj70DVuK+nxScvpl8q5orYNrIUSVPG37X448WJBPWUf+0axkXDyIFQj0QhcUz
exTV
=ZoNq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15ad0ede-e403-4d49-ae80-afcd0916ad59',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAvcAJiKnzY05nmwAEGFJRhwi8C9Y+htuB981TdQlQNYB4
t0q6pISgTpGbodVY+/r7PMq6OWNXa+OewuW7DGIRXJ+UsJKLKhEfZb+g3wlAHW+y
sK+D3JGu4GQ+MOO4dKJhtIRko8t7wPYhuLTx+rA31+ETBSAlPBy1fEO3nMuwtptP
8ka358NUaJSUucAP5eFJasRdvldQ78FdYpD9UUqiEoheWmm/rM3jr0+OqBZS+QL8
BSQGiCqo1K3nSO42PnCMkBrrdrvKgvQggD83ItwtJJIzBsRlDBW4cuZBETenJt2I
amRpu/wwUtnsVbCAvvhpfelftnQhzj0MSDkQbwrXJBUIrD+OoFoHUyq1I+i9YX+g
LRdVhKkb9HsAtch61DfKLlpjMhVUtgSsyOKDIwKcOYoPEkOyozTFQVCfgZxKZz99
48lksxlEVMSk8mnwanzC8Scbl+XhLhWq265fGc2QgLi3nMO1ituv57Tgh3kVRVMM
e/o4vYYcqENKWS0ExDvZsSW1DSWN152V+pqkf6ZUyTaZAwEba8BXynfWNTpH4hxZ
1PXH5XjYE74M9pVu9A+opLFj/11Ob8imuXoqNOpq5sPKN/TLpJ95Js2pxMW5ov6g
LRdmB1+YvZTrAWNwypdYUduudbc0qLpind4agjrGOFp5t6jGxC56aY7AelIWL5jS
QQGGicV6K8M/gkiE1wKwQBx5Ec41TP0kNFQNmoSMcJt3mFwi/hPBeiBjVb6yb4KW
97rhfZYZJJDm23eenVOsAi2q
=fpDt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1b12168a-fbdc-486e-a8b3-adc4c5bcc48b',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/+PcEpkdoHsDoulq7ORI3Dqj65kb4JpPr46rAvS4gfO8FQ
X/c/Zv9LnF/5zMer0kau1SiDR/6xJEi1Ryu56RCrEqjS9iy31msqLaSeroF9/hqB
Xu2afrD4ljKZkAXYHbkpQyQCc6rHUgtUtTJtFUcAb717NDs9nw87PMyyEnqdbcmp
xT/JOgR1tpv2rQLJjWAU5vFhp5psYYEIjLtSk47F6aLdkf9COglhAykfh8iszzsN
CWFoSqVxjJ9Gyf5fJVh35JcB8Y1g/kDxCqguGyYUPc1NJQ4cMFc4RStNjzQ+PYF2
6fePKkFhVh03BRooqmo3u5IPzTHM41fTYFG/R+N13rRDXLML7eJaXvSYyz7ACr/6
0Y6CDq0IFcVyRRS0xhWuLt1i2O7xNS6Qg3gWbUH8+QEIBvV3kKD4Sc4wRf8EJvXX
0lbvqQxMmGGMoEA2duVCSR00UHIYvEloPl+3HIu4yNiocEk9jJ60YH6NEIHr86gy
OCDysA8GpjPSl2weQJfhhZ1iWkvU1zExcO8lavYG9uDaFOcxcx/oa/nD/6N/AK5S
ljUd7nkCWM5rO6qTAK3y8E9mhvyNUj+2SW6UAJmEZ614Wdylk556gX1wcqe0cerf
OVEwZX+f9h1cRYdHPWPer1qiondBvk/d5SB/305YjoDyfjTX1XnMWdTpk0Ou/LDS
RAFD8fdYqZGLcmdDcVCDEGlqZbI/wVrOPtOXmfpFy5mbsv/mvB7OdXJhaw9UT65z
A6a1Q1onuolSxQbq7d6ZOgRfGjyR
=K3+z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2072d079-245b-4331-ac7c-73d914644956',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//WzCsUmo7rTjlFXJMufBycm3msk97I6p4t5gRVVoCLyan
wEpYUq7eB7kSKrXbLV8om6qTWexub9YGBsATIeiXkw8/GkZvvrIbwtKlV3MIMFy4
v564eMC5AFP+dsxSMyCGclzuI0iP1o2OREGc5mtFkGfvDPl13WXxepttLmT2IPEE
L0Ljib20kqEo2aPzCQ98R7i6p8V7YTGDlt0URffRXcWif5JjZSVD/usoFaDb6tp1
Mmqg2m68cSXuvtQ3+/0fDf5390XbSwiHmoycu9cTgqfW38FsyM39AHxcMKN9RsOI
gJe/+1orxQDVYi0JkM0NKhgwzHFNbaEUw5FVY2nCSovUFUxLiWDdJUNd5jwNj1Z7
UW8EaAYTy8BEmwSd1ucjDy8E2KZQJEqMDd/srU3ncrCRPze8dLAPca9RzWiHqMkV
nmtrZxma0lPIo29AhkL7syh/U4BZTbbgRV9Sf2lHIT2pyxJE5pNXaca2RODCtOZO
9cTZ807K2aQWeENARDvn0Jugy4ZBHaTSBVtrtJzcquAsBtzmE5QRLf1VSTojOiOA
pHO6N2DJV7Ho3x3Vm2Ro4B//3LvOPFqGJy39IgP5Abc0G0KKWfpMC9RRQcR4UrO/
phuujaFuMNsRebU5uGD44/RbR0ZjilWn+tfIOmS/O/VUrd18WURvue3Kydm81IXS
QgG2dVWDuKtvuMZXPAmxzbH0UHc9+YKtGjOcVNalRSEr6S53rIQn2f7l31jVSVct
tivlciPfA/VYKla2e1rxIZgbgA==
=06PQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2aa3da27-0cdd-4ee2-a9cb-a948870873a6',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+Mhv/IEGm4m7qC94GP2S4Vf7RG/aZjPPtRl8UXDnsb6TF
NHA3g4850akXnJ0xpVl6eJ+lhF1jAaZga7tygCCRhnPiKUfDfYBGbMnCEuCTcpr/
pILy+rRcmEkPp0DhZu7AeZm/pR/BjTQY6g4kIe4WaXvgu0XmSTLDWwghhn+rCsTa
X/Ag9wteJqGIOIJUyTWOH9kFXsv4/T7YmopxEKc5Fl+NW0Zq9UiZAx1A+I+A/Gh+
h9lVW+AnKyHFBgANKewAKch1f/qpz5CSxUytibPXG68pYcnL81b6xjrkRWQOhum+
tTxSGh+PHJzPMgZ7zUbj8zdFOmKTxSO/PfsVmrLURtJBAdZODPC3Anj6iB4vFi7X
UePkq4DDIr3bqJbuGSznYXISIC+9dmnHq2HZ804hVs9Okd6iZgYnrI5N7BGrt/ag
T6k=
=5lsV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2e73346f-8d1e-44c2-ade7-6e8b322b4fd3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAubUQKYemWbEb1JVL8ngpKy/Vcr55bxqR25+VNTEmy8gE
UprllRn7nQ2MltZ2JQBwcuPAT7neV5Yu+lqRCa11stjDBtS15b1oZEBoP5sDFP+e
aTQOr4Wg5rEabF//x74/GfTeGU2OCgTZeah7H3TKxWnc27ffegvv9cFmeGXMVVvl
npU7ItgiCglDe7jdlHvmeJpy1T/ljLbsaPztOCxGKQXxygNIQrbgPPd3pMy6y7kN
EsG/f02AvImEKSq66/OpxF2ZJSIukTvuZ3fFWBxEhb1Ed/iFjujlhd2/4DCgqTs/
KNYPobZ79wXBv4reVysbNlW28+7vl6i4yGzFX3rktrGpU1ZOLePd6HSatoX3E/UE
qQOTyAZHyUG0dfRHodpLTH7HJM0nfe7Mr6wz+lJZzclYheaxc4SY/LX/BTLhv0bW
4XqpHWldd0TOekeZa699bUnCnVyiyuSldSYBnNAdGOCfJ338hZSqMsI+L+OtwbZL
Y3yAbPWfCkeIzBX3fNihPikSmlSkYOFh+ehCDY3CKJsEVJD++NkIi6h42QlIpz8f
lef6LfKVq6P4sXX80ns4v/DV6kbJYyrmjFWyquA9LstjPVmTm0BB9QmtBi2wlqF2
JXPYw5RDPx0J2wQySIMawzTTB2uLXBOiJhZMeBjiOOuvupkG1/f2Rfzn5OuRXMnS
QQFsb9+E7pam0koZSE6VyQ1hqVxMRw85Kn8sb3jZAgVAcCrkwkoxPy6wB71AdrQB
cSVCQ0d2bWb2WlbOqkq/01Ci
=NEjd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '303bada1-f268-4cc4-abd0-d8e15d726762',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9Hqipm+KjJxh+/gMpGI1CN/qsbcXRMgwAIWgg+ifvJj0h
TDJF8cHzPtxR6B1UBa3bRmjDixvHrsrQv09WOZl4s30mTm2aIwFVbFre++qTIoXX
4q78jmJWJ54iww6c+S/k0Yl6fPt1ahIdOwCYs2/lcKnD4Jc6WnODVuXtYm7hjV3A
dLHTgmjh51GrqB14yqzwVwDl9ako8RWfbpe4qOkUXHnwBvdCQRkyOp51ApFKcdYk
EgeK3AC+l87U9ErJ22Wd3sVydyFJhtqS71/IoM0CWp9hxVPYAfXDq8UgpXWDrLVJ
31KDpRuSV47f1IkztNdvdoiijMM77x/f0HT16LharkAujmxTlNgBoS/bRHVnAv5S
bq+miTvo5OvTd9Ugc9K+p45+kkubr3/uCcPRquSfYOjhqMBGup+TEUGaFiodJpdR
fNB8GG/zZEQEmTPQo7MY+nbobinZe+5DPR5JIUaKCObKmTJ85u5hbu8hSh55DUGk
lU3XpVWkyUD1DmUF2thUyM/6rwU/LnNRZ9XhZnzqyTy039+rZsh57lKgAoCLE/wA
4EDA/+qrXiaz0imcSzAGyrYa5Av23iOZGOE010yOskEyjYAgUcJHRkKrtr2oi5OC
YzYWpximago1s/P59/Ud1KJlVpmLWmzeuMKHzCeBdaMmDX9JRz/k4iuKpOI/fkXS
QQE2cyB6CM2+bIPoPCpzbpq4jN7WZGQqn+Gf3k//MIJEovEpmUjxWanDY+yM3uro
8YJhyu3xmWscRwsCpwtxOpxw
=jDnT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '32158ebe-e764-4688-a186-51a8dcc46e43',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/7Bp9M4qClZWhPw57IZYpaD4j0MwjFXL22MQg6eaaCY6/1
TMJl49vZHyeoyX5AarWDSIhH435muIIwFCHiO/ZUr1G5twNMuos+pXQSLGLwn8li
piSYvfUlpWnVVgAn69mNrnJq+qnOYY3OuL6In5U61Pdg838/cPH4bQwYDYLdc+kt
0H793m9TZkBmXrI+LBS84148jOHqz4cpHT1ZaqGphGXrDjW1TyjbG5u22xMip62B
qyl2LAVQzcHiXDX1glvDp5YZ2ldpPyVGxW207KqsaIVuM0kKG7lDT6E+rbLhmI0K
TEwlXx+6lH9+TO69/V/tqTWkHtrAuz3rELWyN6lv3nknouIxlLQEm6b78SccM8/z
QvipeQYr+GVVRKI0UVfawV+bjKv+Xhqn//I5zKLig4mK35Vxh9i3wuuwJwBATnYI
BnTTz7pOTJYMx9q3MfyM9Cf7P3ooVddWFJVSb49zslN4UnlfsrvYXyJUxE1ceiAQ
w0VxbuanPPgfzoHI/1D3CHtT37kkJ7ccEAg3abYg2/ASIifx7g0kQnR/hkJtd08K
VL5rdIfLDMtpKvWCPnz4wT6keoadin49kHasu+fM3MRt3NX5Kkd7W7PmyXTH4eM2
d3UtGbaScoQfKk4+kTCDygF0XkrViwXCjHZ1fiOQokFBrw+JdhPHHdXeSHfavo/S
PQGVNyRynDP5lanBfq6XfaF3wr6tVHu61XTDW3L123KTqq2nVPKg3KKSJX3k20U0
Q6PBQFaWtAN13ZGm2Bc=
=7de1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3689cab2-b0af-43c6-a685-e73f4eac7d28',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAmTfORg+f59YfSFkHPwzl19FFzex5/mkSBuHIa8bTmTwQ
khkH45cctVbx+PNLveEqsujL7hN+ZIQwHgbIclJJXNsETYJpfDuKz7ksu4cbvIik
F6vI9+Ptsrchtug0TTgSdOq5FRSqhDWUdwWdaP6NhGzRBkp4D9cJP02PctELBmoA
ymzIRIp95pjzO+mZg7QlOMRMM44Q4wi8gGsVraTE1i3tUsEixlzKA28hbFx1IiMa
SQCyAMGlXhJwZBgec7Y6pNCN/HA2K1IBP2IOkmsXSrpA1gksaQEa1oB86KAZ12+/
n+EsAqJzWI917Bgm6/zSeGk619tvk4lTFvFpqllzjL0cymJnaoSEtpBrSjvKX853
w3xUozyxVeWWi290ABcGCoDHqlgyNVE3IGQEeTcQqhMZ5NG4hclcLpwsI/jsdmHV
BbrMB8BUYwxC087W+GQdsX78t0XzHNzyROMchvYEF/WnUu5yHIdGMQ+zWwG0Gtip
i11+ZEe4xiPCNqfFDB+MnPais82cN6y8NF8DlRfRm097bVeX/qg5izIoyiM0jdeI
mZlpfrGCWPNcOeiP3GgHBHPaWfSUr6lVgEagvwGp2LeH7sBIIzdT7U/gl0awp4zg
NFi0gciTtyTXoqEANRN7JXvPBY5kud2tKkTlt5q5fr/pKTY06oWrqjNWVWaW+8nS
QQGsbiXwB88e3oAMVZQVI4WleUFPUu1IZqAFAiSP/Nige+cxF8fJDul9tDurA6VL
xIt1rHankIo8AbFf9Yvi/UjB
=NmPa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '38b5c4eb-ecce-4291-adf6-5332b2f707c1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/7BW3t7Ja3+d8jCe/TNziY13oE/Ye85kFFt/Y5xk/szFby
lUXXBrm/C6EoKeLvVtjRg89r3zeGIEnU9y6b5xn1Op51rc9076piUU8YiQmNRXyw
Cljf8wOZUzFM5qiFvxs7FYotp4yzuqJhb5bcatvVqXOyk24kKXyI1oiGTyIQZHEM
UNy6/FBx24D2qopfTA9tBiltzvy0D6JEk5hGfZQtli4HEzPzOel5t+A8W/4enWXx
qMHJg4c9N282oMhv0lzqCsdAkixjhlkYUr7iQwX0m3/JZotXK0uF1CAI4/UpnuzW
xzL7nJsa6JHv8qCOm/baVF2y29eK2lnb+jr+pgtVMMx3CaCMbgyNXctBc65LQ2K1
FLlnHFfSKyYVaoudBaYb8aasGBmU4qT1eYh4GZx23IzWK04zUCjuzzJSgjTkLG4c
HjjY86LRQrx103+dplr+tRu04B5gD24mqSHUJTwo+ZGrG+ZVmZQPsf0pfAANz/wp
WzC0b5Yel2eamm56mJGAbmIla9aMLR3zPLF5ZZNWX5So4JkO03uPE9N9zG0Tfu/7
EulFFdIZx3oAog6jzGSNz/5XMAiTc6vU5L5BWPTu/jVFF0TNqkJCHrND6n3k3lsE
s78EmySU7rWeVOKe1LjOl8KeHg847o/1tyPGgf7nXU1qTiENfh4R3BATFxN0t77S
PQG3uX58YAMfslNvmEjZE7c+LbwMJbAYMEYmQscIov2AwcrRMw8HS0s8d0tfABkJ
hyPZ2ZGnS5l6x0U2cNg=
=DK6a
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3af335ed-b2a6-4c6d-aebf-f243d94e2305',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//aJ4/Mv0klNz/WCni7Je42IqMPtzCzZybqEcTiHoA8TJ+
cK2OPP1FjuEIiLTAXg/uAGwb6YEORdd8ciUYST9fqyaOtu68fKCpKeGd38F5Hun0
MD8gFhhN900EGD8nKeqKdPe1raoyF2bnyaYTyUeQbG1kdsyCCcS5KnL+gAJxTJis
aqpU4rveWimKZXGNBUkYZUhl/drqALNE23v6nk1LzTtqauPnBp79q+TuDqOHx0/c
TJo8+KBId7Xi979obLaXIw/hEOm+PcLsmO7728BJNYi0KICgeHUQFnAp+nNIU+wT
G3OEc9CEu+ytaTA/Z7+BJRv8xMGMo8j1cBacUBLjPDqPFFbpJywSuoKWQynIcSMT
z/G2CV7XWBLOSjCISEZQRAW2w7Dev4TILZzlZwrMvSJga9+mjUCiVbyWoH8pbRBO
0U0stpds2v6iffQpecKWCkLLDsgvYYdJHd8UMkVys9m2wUX1hVSwiqoYmAqZaLf4
bLAyi8+oYyGHBhlSr8bxH3r+CERunU88vJGCcL+UnSoh5QWhQ3k5zKoF39Q81KAK
VGy/n6PREXYyMGz/AMbVdkFceUbxNl8RLHW44YITSSBmVpppSaLALQCbPFYjM1mU
dnrfsUoJ+HdSMLqkAc0msZgBdAJGDyzzj792/gIqE9G05D7M9Uz2ScaKsAePjHrS
QAHH/4kFKaOKPujBbLHK78rWnx0WYAHFbSJJteRsX6slthXWgSEiokL9ABkaRhsO
dReYI9MfbBc/KDlSzSfFHhw=
=83Ij
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3f35f8cf-765e-408a-aab7-9b179277df63',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/7BnnN3Cb6HNjtevcj8vAJHTlm1g0TZSfiIvPLOhYOMQGI
upiAjCD6J10wWXwxejdyUGa9q7VuIlS/N0oJgeESWWkukuIxQO29xk+Mt+reMKhS
WDRBcfBa38/o+DzSrIC6J66/daXcpyXIg56AHQYa6esz1vxSDBDfKfxwNsvKTxb4
GKhluZg7K0rVGfqeO4nH7G71Sltc7+twN3OybI99jiiFWcEQI6Z3Oy5It43D/yRg
DdZobDDL8r1zMDHjijYuDRWzCE3OHum3PdsuVh5za6/2wOyF17Ykwe8UkmrkFiRt
g+jUzfQZu5xTadme+SoccjZNleOMsw+MSe7+8tSWL0Uwy7A/b1h1HT9INRdMwjT3
kHCDOZvjEnCQ7uQU1P65TaqSGdo4m3qOdvk46Y13bhiw5KurRUo3APVISfUdk1CR
YJKuhCLkdxlo34gWLPzET6SUeDXKqfh/mKMYOpfl7LsgMIP45PwOpB0cG3vFiIAg
1Ly2+d55Dp2jv4y+3UTMO0U/WoNed4KjUMCYSxbphIpBDyPnOcdukQorBn8E1HCG
AJK8k04un9SV9hBBWDOSq+SLq0PswwA77RioDD7cKzPTuS30IRTYdTI9jT1yIx8b
dDeJtQet/YFKqJyPcxaagclnZWJkITdQLTqjfwa38xsjyhTFrFmQR+sRt8JVlb7S
QAE3w/C32T6FU+wO/7Nw7uF3nDApJNJbPeMO+mxo+Sien4JL6GONZHJdIFfb9XPd
9RTxxE3upoejVHHMPIVH9Ps=
=Rz7z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3fbeeb79-1cfd-4049-aa30-9b50d3af1b33',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+JkgDw4QFXhq2pnz+Ns0Rb2KF5br0m553zvOM/OvvC17L
gMT89oTP4oi3GFHrAz+E3+yRIdInGcmEYEDhdFVNcZB80kPDdZyPqN5+tJ99ilQT
pnfNIoX7FgyJ5x1VX3Xv30GHycekgswtCpL2otl5ktbNHEP0rk+3kh1RDdjbRuBY
kPWdOS/cZRVkmjRftVvvg+YY6Mb1KpmZdZ9tGe49SP8vySDU37g0hKYFs6785nrS
j1DRx/aiTpYn+6Vo7rwvuTprgVkOP9vLtvt9wo8Iak482Zn5FiO/3vXigGPUtTes
K9o3cd4qadu8IKQKyPX1o+7sub+EnEGkBT1cCcHjgx2ptNNkM0VZwDLBLRPKsKUN
Xol1w5DDfz5Hf56uQPbWRh2ucpjEqsHKCLBc550l4DbXCI6RArq3bJIBdnb5ROUj
8Uphwuu16G7KRDZ5hL3lI6I/hyNKNUhHYNFNE1lzuhP+DvQhXlDdw/QSMtVQB4t0
xs1irR8bhvpXhXqf8aWgMHsyK4V0nceFerxpijhhwKl4LWIKl7C83+fuSc0QQdTo
aTgTLjI8Y4XcuStuDO/SJ5+clwG7tuhvper9Jyd+wnQfvWh/eZvOGuDSAGb+UNf5
mDRJMdxvmwvKe++/lUHJjhtdQ7DRwaD+S7DGd/KgcSKrVkxchAVmebaMWFyVu/nS
QwEHF0okRVaFWkobiGUGvk+LZQG8W9sEuQKrk6ezRuL1+SeIkscmvytHqr77Id5r
DhaxgmuGOdOD2wtWAzP1xggWUIw=
=Ae4/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '407d0bdc-8b90-4910-a93b-0e525e7a8d07',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf8DyQxqWuF/xeOcduIDV603yHHPhINs8MPSTTls+mjlZZr
Yq28KUFJpqjyih2SQBj18TVGWhRprjca/wHum+zwdDJYhWlgU2xtZnX+J+utv7kb
0S9WjakIk6W4Q/EomhxWChL1/D+etRreC4WUX2OTC5+uMLo7u/i0w0zYLw1HAgXG
1L21mWE39YqyaRCa7TGhSgJwnzHzaH0s55QJqnp72LCseA7N1C0CSSfgdyiq4hG1
tz2cNlsIoj7JhycNhBOioAc8d1gt0McxUZq/fsWQIVRkiMiPVMdQONqEN1HUGkcr
PcUxdhP2Uxz0vuB0VZESu7hZVr+54N7RsjJOztHwqtJEAXJ0HRcjGDj/7L3vabNv
JyjKP3VEOirD+tv8UA8UamZnACznGmbEDMR5OPDQNkFWK9ZOcVUlzZhbtpJrJsR7
9pqd+eo=
=0Bs6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '42ecb91d-ec3c-4f3b-af83-21af4b401d7f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAgOdQm9LTMZmQb/kPq2XUgCo50oVi6gpR84TAl5yTFDoS
UiVE3V/edwu0n0kgLoAFVePpykMQdrAqJjGD1EDbOKVzoijesl6tRuI+fcM2n9lm
cQiDu1cGAcIEQjGE76toKC4iGUwkXG16VxI1CTRF+1P5A0nK1HI35zc+Qe1cVkqy
yjSzs5Vj7wS4wqI6IPFdMoSxJAj4vQ5lixR5cBnQhzr8eF3wPG3z87x1TyZ80vTv
rdM97n6Ze+mV7O+maJc5lnxJwA/cjPxUJJOa8bbCpA3lq0P+CanPBs7hmAwuO6fM
nN+3aoJBW4ZMrmrijZ9wk7mF9iOX+TqyLn1G3NiIt2aHWH6bSMZqxVv/k6H6+F2E
WdXXSHBP9SkCtrrvBbXLkYZcZ8s1OIw2HMFdpJQxFXLZ6/sOrb2MyGI8+X9i2KC9
5AnnVGRXB6zDUWJaUBw0wEY7yliEideLf2WzFLsLl6e6mYsIJNi/yv23s9dP89Tr
/MktZo4p8MlZQhkfzEzi+l8Pc6Y84xWFsduG6Zfqs/a7oF7zereMfl8B6ZCAaQwU
5lLcxLfmCeVy1viNJFNAI1ebbRzDRyRSV4dwJfxfQtNR5uLL2grBSeUuLNWwEZT3
NjsAOL15N9LJH8IoAafzSc3viqJremoXEvsVTtKpjoVil1fT+JXx909M9QuM8OXS
QwHEyGatRcpwHcoGueMmZRpbR6CAFq3zxWJwz9kuaDriOdV+FEQOUBH7QW87nALg
L61yR4o+K/TKxnUSh+NNZvWi+VY=
=wTCK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5815aef5-d30c-4571-a29c-7b6942fce5fc',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+LQtIkSXqV44z65ZpEo81czeAS1exEYoMloyxL2jwk2wR
G/2jorTV9kmMwOn7T0guyikhVB6i9VfnIwdSVdap8wdV/F/wAX8PNfOkAtSGbo7M
ELhlc+R4ExFhr01iKU/n2khZYzaLbH5qIVCECIocR7PzGpEkVxlvZzbtOi7gD/2g
xBsO1bkEEd+R3zisq3yLHFFIwZFv23qP15Eu2dfpQDqfntRHbXkLl9IWxj4UhBph
CHF5RTpo+JgxW0LumOjItNNr9BUpxRqREdAbYpLGqbCKsjupJlB+98crrftZZGX2
nfesAdMLATi5Ods0wm4d18+cKIC18QJwmN0eL1WAKmEFZnP48H7NbM1tpGDRTnd5
6eyR/2eXfXm27FnSji8WIOM+iVMLaa+PfoFeDGs4TmZkiFcxcMoYxWmcdul5Am2L
4O097q/xDFoyTqvvV3AqqWOh/cYIlHAiedAdKBFunPIpzNKtOD6u4GEyCyPjFp7D
5OoJjc+u/pOj3HUgL2ds/EXrfBYGoADVPcLqL+4IT5RJzk5FsJVke37QSsgJrbLI
JGIw8elTYmixEEeQyjfxJozNhKMlrATWETEeEzpjGnNaIdhNGDCzAsQI+oPYYF9v
9efNLhGmoA3rc+0U1IcZ0/8dBFpM2UxY8SqLNiu6mC+p7upl0WT28EWm3BIHwerS
QQH0Nkrxs/LNPlPO/EPYq+WgfYsICuBx1tUt86AWqJFTDUdDejzBghRbcKrgDfyK
iDhoSeR75LJOmf6hVnkzTZGr
=Zp+5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '58cfffb2-ac8c-4499-a227-923a2009f443',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAk6Ar+3NLCjkBbSzvpC6KETZkwq9/ZHbmV0q1YBBjvuBZ
zcmks/EQNbY8CkbFW1UqZbE+KynA+mTCTIiVaKSE2r3iQL7GTmkaxfgdxnfsYaUS
VKG2g2ofjbJjomqkAV4uhDTSdkcQF0Kj1LrKwzv1pCWx9rOo/wgNp+Yk0/x/8FDa
u8p3/E1diym99xcwgSMfNPaPBaQs7w4XvopRqFtkrcUrNsvc8cesDuhxR232A6tt
CVgsv9fvDsa5cqvJh01s3srr4tuRENPB0zOb++1TVAHf3r/qHeZiv2iPZICOrBrS
6XG9NtTs3Cpw67rO4vG1LudIJ8NSEeEMwX1AEqjz9NJBAbaBtl4SRIeA1qy7d6vy
PcE7yr6LOBEXKtz0eu1L+TRf6ak0/QLmz4AaZQmCRZnyD39ljiybOq8ofy/MN4M4
Qzs=
=eB8M
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '60767ee8-7fa4-482a-abf9-2010c4a600f7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//dO2Ugoba6zrqNLpNzvw5GsEh8phz6nAjKcv3Whzs0s2k
cAKQDIqEWOJBxk/9prv5jx1qe/397135ZlhlHy6gSw/ksEajR+YXgwY6/iwqFP4E
Z0cGkrAYyERgnAnqoeHmh1wu0xVEHUXazh/0PpRk3+THnRTB8c96JybfyCVg7Opa
MzUXo878zjti7zFaeoNEmtOZGq+Zd0wgC/qARdea0oPzCXAs13QvGrqv9+fgfNI7
FhMqR4VI4VRirKSQNga+QemcawiMbvYKfaJpvO4lsVvypPLFvb8+XoL9zUFB1IDO
x9NZ9fsorpnFPgh5bu4nDM8KvruYqp0hWoZ0kQmc5gcaIawYiWJInN1V53SWnoiL
Uur83cIeaYcHI7hXWu6GYXmNrOULtaDH9pppW0mIKtWB/xAQZpjPLOHDr/3C3Ytb
QIK1rbwzW7bHJ1WttOS5NqZND/aOKr0X1k6Mwco2Eyi7AP7xTXRFP0ccGGaINRan
xvV4/GFAFLNnqYUzULUEm0ggAn+TfD62sSfc4kMVLK92oKanKu4wAqsP1WddcHSI
Byfw6NqbT9bspnhONNFOpZZ4Hs5zrqBPp8OqYI2ISL8wUMh1ADo8rEBnrdwzebw/
BETFqtFBoQk2vMWqTIhvzf+SDG7RYotB6oF7ywNS6r9m7z5WOG1eVw5LIKyOuS3S
QwFQut4EyIiHQwFcZmSPpaKnu/rM/yq+bL/BA8CpGIFes6SuoiFwZ8/05G6YTXoc
6OU4Hu3+pcB7NlWqt6ZfshXgvCo=
=JOnO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6333421c-ec76-4cbc-a196-230717a07517',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkijZBm6gi/VFey6/vaZWh5UvYF3KbgcC6FCQ7OXA7LVg
BdQur9EE3ZfZ6GP1z+diJ1VSwE6DGWpwLsl/A1J4JnySLlpX9/g/n8s9uvhZFo45
/VCs8hzKwymbmxHtKlglEE1bnJc+NF+pAdt7ut7QXR9Mf/LIGZX59tjavP4zdO7p
Ua7vbRtF+Igoy3tEsbaqhJkQf90hKUVEZjXFlP7WGsKZ/FsO+/qWvtNLBybf/SLd
u5I9C1Qnckrl/+7hoqStxgjFFYEIEhUCgpxOYMkg67Hz3fVT44M+VQyAyTCZdXja
03o5i5329Qilz0PabLjBpp7npfAJCb9twXevXNd6UN3kNz4WwgvLPc04y9jbISCA
MkMuIIX+x53eSSWtrb0mAtoAUPdxCVVxnpBjXnb36VfMkD5Gi+K9FSuR6oN66N/i
CbStGhFXSo/ju+tr2HtiqFgaItEfZK+bmqGYBzK+PpPRUpwmZPAZ6vIXt8AA47H5
w3pMslxA/epzfJMcV5K9ubqWxAPefGhBgZQ7KSLloaJCPF3REUU20Q133BYymtio
l4+gyHK+cVttdO/R8hYpkUNP6saGcWhxa4toBaEBxPupghZOdXABdjY9LUDkm6vh
0RtZR6mvjPKco/VKx8yGFLlxJE4RQA34rZmAnUoE8Om72lSvAggp81umCQ9xc9TS
QwGQ/uaWG5rvu3gW+QravZuc7krqRD2efc763sQnOvPsVFo2imHcmyefmqXJr11r
YieY0Er40uNdELNMwl6jxt81rIc=
=e61Y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '67e689c9-7f54-4120-aefd-006eceba49e9',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9GzbBmXYbcsgh7EEnJisrnDlQsglTv9Ve7oqpvG+BOAaz
JBUWiuX7DI9R+ffrg7UxDL69lvYpRXnkEInu2VFONaQkvhkzKjtwfJhr0G+rJJV9
jdHxmgzSZYkgn+es7YnrFxhI4/0sRO5NZu/WZShZBol60xLvNYeJKohU+0ll2v+H
W47njRgHcZ4oj3F66xxDBqvT/7e203AIdKZt/cl+GIQas1nwuiGcctytY6sfTjBn
fLJzvhEwfK+lw35n0JiZbkFbkcyWwE6Q5fg5UXJ98jo7hfQ1dcKymGreRCnIpevZ
eti7JUGj4J2oYN5lFQTcCQcgmEcuUogcYdGFdbxmHiKOPsq4tJ6vGMyiedAiXU7m
OEVTQ9VEvcu3+pWn+6bu0hg+pB8AO6RRIl/kkExoMXKKsGty+NqmRgE6rzwAOiJG
+My/FdfCPY6grQrRu7gWxN9NBcW6jZLADKXebPo3n3Q8T7wQZUaSTvn6mR/bgW0W
OEZHIVoGsknFyvM/FR7thJUIjcohO7ZD/tpoY+AOxgvsZY4nPKiYsd8WO/nmoie+
t93fE1PP0pjp41zfBetRFYYImTXGMhsrmXrMza6OpAxedjagvs9NmQOnPRgc3UCj
b1Tg+LjhpOCmfG6Z8CPSzo+Ucht+HqV3aFXb9udiHYOK48D4U7zAXmucNNeRbifS
QAG+MJRpOkcBbfLU6nSvkllXXlNx4V7GIM6ou5HbRC1wWhStlUN6XMvtlan4s/KN
330uuiZNvfm8Ta5Q9K71QKI=
=/qY0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '72284c98-e2cb-4da1-a501-33ba93ff31c2',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//ZrKnV+tzq4lDrgrAKoVb6F91s6+KZtkQXEtmX1aoY6sO
o8yZu6TmQQytqEjmJtAOFWIkMG5Lp7nhzgX8d5NY7bU+RiWBAU7UHTMPDayUD8af
iDEjB3inTdRrHYCMPNj9cB9I0RJv2MfKnpIU5PXrc5sUUBOL2fgJnqUbHlMRXWq8
r9C60AtLqk+DDicmMEvG6e9rRu2Vei87Yb1F9HOMNCrdJDNB87eeuTFoBSbm6LpN
t+CTR6dQ/fhn2VQLuG73kvDwRRD10MtMhxXImW2By4JT+NgRzyFWKBWwV+SR0LHA
/d9vc0sjznN6DbwRiFcReGyB1Qr1DDntQjsEuHyV/OQnBg0nNttVtLJRPIeL4GVG
GBYqJtoeM1e722lW4xQwNx24o182u5hIte8tlZQZrVMQDgZfgIKpotTu/JXeWSOt
ntVGm3CuCD7xy5JeB4v3HMqiI9i3RqCGfRUJWJwCO68IX45WFLmR0LQV2bQPA1K/
WY8e4ekOrdPZpfxhzZSFmJYfIMQ0W3Gb/0MgKRZUdA6AWiIwxcw/5DZYNT1Lor/V
swFwu1ffGUL+GZj7QjR75utxUi7NNgHNmJrS1Ktghr4jV4LXcjqBc/yh23nrJ+Ho
O2pIUexWMwykqSBdUppY+0lTse0x2KB6s3LTQp4Y5aYL54/KRQpnWvKfm2oLPEXS
QQHc/8G0cWGTUPrVj3D+M5YY0Q+3OfQfGQNEobi8XeencZpzKxj7QIHoVT77pQFA
mEbojMONE/l0yFsFkPrGv9K9
=Z14/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7c162a90-8ab1-4b4b-a086-ac80f7e4d664',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+KZ3YNBXMjePF6Q90H0U5ftUu69GcITBdFpqOJbSdkbcL
GsxqTZ2w2YnYgGiZMXoEFHotTqCLHqzBqN44s1vRgl4Z59DewMNmx2qMxg3ZKngF
zE37sQyZZ+IjWezPLrsAziwQ0wA9+pUs9L+bZkEOyPyAKsBAe1HH+jUI64f8pmrT
Urb5yIaBjM2Zu8Dh3B5pNElmRVCH0bnVAYSR7zJsjhi3l2M/FIy/dVQ2vXLf+3JH
FMX3gf4RQ8JR1mmb8RlGK3mCy5rzh/jIa+We24N/sh4Uma/LCC4uPHRPQ/G/Blhx
SdHBZolu8nbFvk9P3VjAJLpa4jRcqBBUQfLcaUbKN9+pi7BXOxUN7vlr+DBe0etQ
KSjn12HL5/mcKeNyCRxgpW2tmT1AwdUl8UZaCzvbr+2bV2HEN3KDJVU30/4NYmLD
HkGNQQnnhL1roONIlzKERAwKaxJfH02SqaN43mWgc2Y7CCBPCrHqzsQ501THQxjA
tDwsFAwt/K/A9YMvMGAti6rlcDDzmn83LOSgcHaDZVOoQSkmRX3KEWMPm4dl+T+U
c0FYEPqDvVE2DXHKQkmG5+kCP/g/PEWOVg9kfSN46xqLeIfDZVy94SNFwPXS6vAm
TmUuhHPORLbXVVrGtBeJ/OGvWkG0CzH2S0HF4349Cu4YKO9rcVWfs3tmngHBiQ3S
QQEEhLjs2uzd2vwe+LIshsM22tQE+ZlIAsC2vhiOGQHNrdb8H6vA1+hTdSNszSIG
7g8vcEmfm21CtXVfEwosq8g5
=6ZAK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7c818815-bc88-4025-a438-b5b31d49e75b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsebDgNUGfUr9EgNVYewNUc7XbzHE/0CMByflmMSK3npX
HTylgQI8N6PO6oEh2VHTBxnRbzaMz1V70aRsovkmuDvl+7rIs8XdIoFuWrXKSQTd
Y8BCyc6O+coKV967S9/JPUGHOLQkP3GSXCdUSmsZIoOsjXghm0LnY92VYW9PXvm2
T9v7SRa+Lo6khJX131OqR5gHXKJfU9K56jeY7NcQ4wDGsySVxNVKGFzVnAus/eVN
VY95mnBCPr83KGNvHOn6iBU2ktoSRtnBVFgMgnw96EuXzCL/e+aAgFLlTsyxlbs/
aHPWWyWCP/R+X5bGGqOieW9wkKgFX+72XGQgEblUb82UrAcK5BWJCEkFdlu+L2aw
clk2/YdjcJJQ9YJoXGcwezLiRkdORuYC0RzNxXiwWSKKtJ+Vbhld2BZGrQz5RCUg
0cGdNSdOfIPdEZIS5eHF40fiwL0Nksbd21seDrD4oxCa5eNVuqzT6LuiHSVtgKku
dNlatKYXNIeJw5p06AKgGQk8oEbMHB8bi+dWBtkEDkJLy5lM7PmkPO+Mva2IBpS3
o3qIXuo8LLNWnSBgzh6hLOcOm8I/ZrnajfbPc9EnHQYiiCg0dFqnDvQMMFkX4JSg
nmb3uiViBz0mWekPsOJ+lFlm+dG4OEuQd5qW9OL4Iv5QDzsp9v/qo4l6gpl0pa3S
QQGhlLlmC1XZOLMHgzO2TGbjxf611sYKFpmgoejon7hU76M2TaKFUFpFt2qgKijO
kFgW3Y7hP8ISWY9IpyAXJn40
=cFjH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '81fac8ec-1203-4ddf-a40d-ccf08ab779cf',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9Ema1vl8NSgmfmIHfHEtT09t36jELuvXCxqlaRfaPGgh3
q8fjkQ7p/WdNAWsdzko0jB0FUNo3PedghbHaD4tle2TqUzoFJulsx4eP/aw/3t9o
nDIg9yVe0zXwEJXAKJ8J1MGLIPiPyCDLrUg3D1D/9tk7LfYd9CX5FUt0+ZYFKa2y
kEikgg4JKcL87uG/65u4LPHdEoCFzbBML0TyvDvnAj5BUj0EQ4D2GlDMIAewTnrZ
hzJAIWGk7GecXVOCp8zkPiZtI8NvewhvZVUacaA7MNobDNX+Al9c81NPmir46tyb
mbpQV6ehg6WjyzRSFJuSOP7zBw8qp1O2xw1s+gXspecr26EZ7XHYeRByJ501Chmf
weP8QQrsqxysM+gLP8l63GnCOL0gTzy4odDIpwT7SpnNktrSm19H16V3uuM5axUF
jldsSLc+IFWmfdwC+xFTAcMQM+Az+dn+pMTuvhKUmShIGWZev1OiL9u3Iypz0UIV
OuGVFwaYokBK14MAVKMD7yhJdURX9gWZsDQiaPu8PCBp6OolUz33F9WtfRedMZKO
lyTyl4GHRcI0z3kkt4xLe6RovWxFCwFF6clJl8mKGSq0WOhEEaHVVR0kXwDzjRog
2Ajc75bDwiWNFZcUqUKdnn+kwsQ62Ple0Tl+jGReIi3+S5Asf+oSbYHelSXhQU/S
QAGuVdspqzRbq+Z3J7hef0yCxUx5sytE573In3Fr543oqHcRfia35BpqayTHxu1Z
Wj60TB27QesvgbyNrfXez08=
=BHGa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '834a856c-a192-402d-a84f-f2a56f4790f7',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//VwYkPtwZkQTtf75sbozoV53Vmu6HiSg2nXjvFJSVHeMW
K3LnLN0AWhQgV0ocLGebTBnY8lKYiJE4U+ynUiUnwoYKNBulP8mBNbYYsQggouJp
avkW9tTvQYPcEFaoPkCR+cqNR9kVeOSdlaqzc8z5wyNC0gSefjGxeshWolW7tSFO
0fa7av65/VqdQNkWsvMrdJUxE4HO49V4pjbd3Ex8rVf0kzGLsH1gAlV8d9qv1yal
6MmzwN4KPmldLF9stZsq9f9wNadh2K/XHB5ANn4ELx9I7cwmpTpAd4CzKYaPeQIz
Azal31sQrENwuTrshoTr39Qlql971NOqhe5XINiSXDpzBQlV6gx5mBsTbX0wudqD
VSKEQdMSzDYLYe2Af+wUoRO8fRhEeCTQ1+knBYkkpSm6/AnQ6QplIeuj5DPSMQ1a
HoNhEbSXGLurqlI7OFMihIT2lFjqfP2jMicCV7TTi0h8bdObXKSU3nUgoFPWemhS
Vrlxfts1gx4bs57CIMsuKq8HfkGFiCpaG6SNqf5TCjtuV8Q40rrRPu67BuMsG+AG
VOCwYi/jv3KKXLYUnt+plWgFwpCiohnG+jVXhXGs6gn0yVg9T57XDxUpLywk1gnd
R65+NM6SbdpVqA+fPzguSS6smjUC39qp9ybp64Aqf8oTiybnLgO4sCI+3Qxv3bnS
QQH8uZi5PYL3ZJ1F5aSZwka2WoFwVCqikZpG901mR19i6Z+O9zQ/f9Qq5bZhrCJa
ZiE08/g6GEFimtvy5pSoiWrp
=3TN0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '83ad2e6a-e311-4104-acca-e12723ed5348',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/a2dv/zWVrM/oQ7MtYaTM8a7xrCEeOAwEZaX0DEcNXHqd
lnuD2n1MoySse0mf7D8VrD/nhItyyhNFgpo4Itxc+YHAHQ36bAfq/a6vswzdFqfF
AlisM/A8LlyKyYUcGNAHLSmMcRKuFvPPqdzJqWtzSHPyibWxuP+6u3c0+dznZmGU
sbmg34n5+iqg4ICKFyJRarky8nKaEaSX8qbv2SvVY23ONgdZialCgi6tt7eMzPI3
d0MIYPZBCierdX+s2ZLXt5zqEp6wVysfcZhmGomBPDch6HmE+yMp5hURuccNGdvM
RZtNtBBwzPBfA63vr+bpFJgDBILlOC9X/Ym3yosXQ9JDAegwqwhzzNV4Sx4382GQ
oKAdZBs3vdR6rkB5qdFBSW1dIIvyveW2tWdO2ZJ9mwpJwQDrGpK55qzsz9FICBKe
iF768A==
=G2Rl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '83f7645e-19c6-48ae-a4db-a297a9cc57b4',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAp3Es12TXNbQKl3997fLlrSlGhhwPag5RaAOCNJxurUr8
1pyhiHJEH09GVNPjwVgN/BGVQZ8kl4tbMYsd7U3i8FZArDJOBDyzJi699gsEenaw
ZPZQ5KanWdS2vz7vzsWtT6CD6Tf2aRjOYEJcqDlLcomHKhsplCcVuBD/Tc6qugF4
Hm2QkFlygfqrrOckjfHwPl4vTPMsKDj73tP2dyv9EsBRvGt1YDmJ20RxnKBC9448
wt3w9qLclHBqPSGGntcLB4HI3lJlk75PEr9QtuHG/tsNKBBqcYWlWoEKvaCDTMZQ
uRUtZ71Esh86HqSi8qY7B8SP8MeqvQVVf9LmNtwC9dJDAZdn6yu21niuv+MAFuKx
2rbI6++34nIYBEs5nTjIc/XhAlO3CKxmriXNRhnar9h76T+pXFDB3D86p0zjICso
rs8tmQ==
=U3z6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '84703ac9-c440-4e83-a9b1-09b467e2e5de',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAirBUrePRa55NdvdB0zVuuFBEPFOxp8ufUwh8k90jIleK
uLz9kG5r3m8e2/RCLBuJ4L+F2EI7i4EkNgBQQ1oOE8NzGCJ+ZMFcmgWKevVQM0gi
C7n/93KVVkNvKAtqtQ+S5XfagYNshEt+TtNLK3walEyJg6nwXgv0gZn+WrF2ikpu
PpVRpR+ujdfUh6X5YhE1TIsvo/qHlFm7bLSKJoAQHwasmECO/a4y3vWsKwqXKtLh
F+CUMCEiez+T13+qzQM+vMKcetHkCdjJaSAIpcLimxZwdySKLAvW20RZp5fckiXy
B2REU+/G8oShHa8EmLC3aUi0lVQyyM92BIEN0KF3FXIUAkavEQ7KPWFheLr3nqoY
k4HIO4K/DL2L9L4G1ySe35S8dkvoIYf/aFFCnylo6yDC3aKZ6iqPhKqlxtSLvlUk
LeNsFkoSnWzCOapLL+2cCFZ/GSdoAqXH9S+mi44+3RHU01J+H5Vh1JtuD7T3yLNY
LeknZEv7+hS3hkW00w+daUHIjnfaWBatARPo2kcR+NoEBm6OTFpStR9n/616vpXj
Gs662io2G1VDCts9CnrEnCwS093e/UX8to4j+3JdiNgBi7ON3Qc3nmj4YKmNJigf
8va6hfRat19aQZ7Kb2Oio0KdBhtxM+lUWVfRCugeJp8zLuRJXO3CuJqmir0w3ovS
QwEgz1T19LRffVGLmbzOdf0LJg1wS15Z4FXdbEBYKKi1Qp5zUExTq5ftz2qD4U2X
+Rp49wFDzSk3+m+dETvyjh+aobc=
=DsUj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '84d6c16a-782f-411c-a54c-b01f2bf5154b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Qkpz9cfpzoo4gL77Eoyj/PIRbqvWGa/t6lub6W3kCQ9C
r3rmgTxc/l68lqbRecmm5CriHnsdfalRWwpo7S5keYA3KHcHL4adD0ElbrgfjrQc
LApexhd1QmZ2ovHjo8N92GG3FojZH7QCJjYToJ6L7BfKDwEAIibNxFfsEoHUByXD
aLqfWpA/ZH1VtY61n9Zl94xfIYufg6aSdodI025B6xEVKV6gWdESOtem4LxAL3g0
3+5ZGyr6t+XlKp1V/vjdfrZG42fQNOoDxlV5cZVv6uC2tzhvafHFUQZr+tBZWw1l
Sl+ZKcOiS2iwzz3hLPCVQJBD0VmawCL1lBCeSJDXLTHaCgjHKv1yh1DpVpGy7CnY
MS7AR15cythVi5QzmzYIF/zMrdF9OUnsuXZDw9wqXFhCtUQfSnbltgQ1WtCEZ2Es
fK9R89wYkiUYcmAFarQsuUs17iPvs/Xi0ARazXGGZvepq5ZSSELEwOeUOijKVdX9
aKaKjfWRPov4YY4NM89Hm4NiHkNDZIFw7TRCzjGtC9TV4HbRC1UmfzGHXoILVlBV
22iGTsAQGyG3a7tzDShQ2ITy8yRlqL9vGpwGYe8jcSC2zC4FYucAwlkRAZoY0R8C
kgY8msCY9iajnpuQy98Ag4hAKGT8T8jxC07sf30vCQ6o7v846jONS6KxBKtWPM/S
QwEicXDhdfKjbi8RFRdRX4yOVPpR7Jyn94kKZnxQ+xxi8MkVvw+2Wx0eDitLOQJ0
S2iyaR3ZPOHPYw7SSYleay6t588=
=C0XU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8a8a5fbe-8c66-4374-a82c-4878c61b470e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+JHx9kStPfvAu7scw4LXSFmvLv5F1rQF0l4682JbkTxCv
WuGTmSvZGj7RZ8UckhJXbOFjEOGS4TbTJ2bAqqrk2Cfaa64Q9ND98yvGeTQc9tT5
qnOUg6uJs2wx2TH5Itdq4Iqz5y38S0rpJP/5mygUwCMMdvAO/8oi3CWApF465dT/
7K3WYt0XSDY2QqOMcvdlG5O7lL41UiqxOHSBNPBARO3pEPX0a4jvCb1XiM8i9oAW
6uYFwadTj0tTFX26uG5hSPsd4gpb6pVR18ir1BEIXvmOZWOWa+NUNZHclzWIslZl
hswmqZOpEXIwpDHLGaoJC4ufONpzdHI75BblDYEq7HpjBfkDb9tVfJ2+SYBru3Iv
LEZXRpyjzQjESPFTqk8p8q5eKoaV6rmw4uPHtwUN9WtOPz3KaxFYUEZN+DCIS2mj
xfqQPULzZSGaZXWLhP0/g2h8396REhY6fhRgi0Gp1cZOTdWTLy1tEueNK+OE+7pW
ZY55MkHpICn6R8hStZvJ8ST41mqJW+Ka4AGQ5FkBVmKke3vHRS2DRB0LUSh96ulY
HxwHtHBYGuvMCr3w5GDjsljaL6MJu287oC95lq9tJnA/fMiehORgcm2pIVd3jxVg
I1n0U/3MaIImDb6OYjcEJYrVaO/PfcGEK5pZthhDzg4DOpC7JZDH9Yrgqv2WYXjS
QQHpTFosVNb/a1N/L0gUXtznAHQfc3lMKUNkpY1G/61r52dgdAV6VEbryJ+cm3vK
s3d3XXxwmjGUEp4yVPdflXJL
=HiMZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8c9aeacd-a808-45c3-af62-761ee502e38f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Si3c6cv5CvRtHxRhTBASbYEu0TZ9xro8dq2tHjiZznPg
6uN9Iti5PnAS7aLgHqt58vCnK+OV9te9hDM/HAmp1l6kxR67oFKHJChnD6usj6FM
xoSyDbkZVtur5EER8+84XWXVacOcg/lZ9sbaOcCk+mXH2jVBSCpvGLYUhm4ltnpX
UEYFywGg9SXhnyktmb4wfytxbSoGzOtxKY1oNBlhM6fIQ3uZ3TKjAtu2qOXAUa5E
uE/v70TVYs34dUSKdqjOWOp7ygCVxjgcf960+pDFESpiqS/lNcDVOxVxElCZROtP
ZLEnDjOLgB7je9fZweUNCLEKRs31KIrik+BaSz5aRrDAEfdxGbV9meDtEMKUg2zY
fMHDb7oMV/HJuzS45MWzImFt74DxZpkWhLoMxaNZnPRPUuKifHJRPaQn9gw+cg61
6jOfHsYuWGhvTnYMRvsMtZRy1W+FOaBvxIXEkBwpVUjUoqD4+4bmywzbEV46ssox
gjibQstEEhUoE8GaE/aiLi41vUSacD8zxAWYQZHURD/HFhLG+gyK/k35b5s28RXt
HI0uvI7p1uFDY8qKD6/rKV/0q94rZ169miwVmoSYvBTOkiRdfYk060fb2/IJdrUz
v6S3pPf7VZtt4c14A4UTLr/HKUaPyZzjSVDhSFM2eWueeXFOd+FFqXPlfmggT0/S
QQFBUF+Fy/0e5FaEvWEYh2ZiqTv8kEhEVc1dxXobIOEZn6HBU5A8iK+z2UhaivGY
/7N0iHHjK0ik16WMnzK3yzEN
=Wbfb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9147c542-7789-43da-a245-62444170eca9',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/RzQh68eLzTbvmmvKI92g8E0JGT0kXXo3Fo8YFLDCnZkQ
P1zCqa4cRj4htcfA/Wg1x/uT6449gUP04fJiylY0v7DFkEdPVPPdx4tKKOxP1pNE
4In14D7ljBP0BlKsvznzerbETOvl3Ud9wMDMs3sg0P3510suyzNKtSzC+J3f2R+n
hlYskZ1JITm0rP3PsT0+mlPJBQyvlOv4ptVNH+a9YPknXoM25pnaRCvdVfX7x/2i
+RoVCFodAPpa/hrPx9olnQSc1RJpHyr8+JpjpYUy85f1/zDJFDedeV2SqRSHddVi
WJP/kYCqTmx0h2mIdEq8TO4vtgjCZK0ajPW3byX219JAAXzr/T7tOPtB2ieBul5v
A4asPlgiGX1SBWFRCEyEqeOfKxdM69MEYNvrNn0CapFBJf9G9QvPJIbU8ThS8Lwm
6g==
=DBrg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '946ff451-1d27-4b8e-a508-df1743ed6627',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAvQTWBk8nPh5Y7n2Ilqtmd4NozXRoXaEHLmFwquB84pZW
DC+MTNCPU8uvZ8EVuvtsYtQWyrM7C960STVEgIoMpGilS8/iP0NdWTFnhbRuv/ps
nl/AxSUV2olffW3LS/aji0L0XArWL4UyAPhDf/SIk2wcyZvLwoXx6l8pLoLdOger
9Lzf+sYVqsFJvCORCVASuw1Et5UynYPJj4QoHQ2FZLdcTRCW3U5jlsc+9CMRte8W
ISA06fpFoIIHVRdCye9OZluB/wVSK34usM1Re4ubdVI2ZtGjAOsU7znRTtY6X5Ji
/aeu2EZlptxNadbyn/WtcB3bGWtWzOTRhoeqoLjFuV+hWDxFSqTvQBEmJaFqQ3nI
Svb24bwcgiY6ajKLAJha2VMpwhp4pOmiAwy/2yR9FjVmUd51uFPnnKTRxZceaHHY
EQxX55vNfq+yMX0+XyNKmtE71x75HC61AKj9JbllpcwMXB8HqPtcfW4NLk1LwS27
xf81lkiG2G1cVFgTbUS8YifGoI0oDtSe770F6oydd0grzGzp5DUXC7IYAQdz9Acr
hOp0meO9EuT7ZSBQA0OAQy+nS+JVsg4woSQqKL9xv9hyyogHxtGkkc8b7s+1grON
QPwpTs21X7L+ZO0vtjzzGI+kvlKLH83mv9FGAzIid38d+2Z0lGtKZQWh9QZq8FzS
QwFB3bl7lSGMYxNIjUhPysYq0eUwYyx1UUWLrONY5DXHuszTOruzrOJ6piNKEswa
VlvH8T6LxBo71YQBUj0fgyt33MI=
=lb7W
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '94ee7114-b942-42cf-a36e-9c38159f295d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HXj7mEKtByc6ZnFYd7p79kebJfgW6cHO2CcPvKdZbC8E
n6LLBFO73UYjg5x3QNSnh6YOaqztpWe1jPZgC/u6pholgNJhCnQFmiJuJ56lPy2t
L0LaqwEfoAvXdLDllKS2tRwIHAH8pthTJidH9iD0jSwMZ2XaFVHLO14Mv7uT6I//
NK0OH7+TEfaK5EawaJAurZVd5nneAAd2/gmjFm8JOCCi1XEe7PLR+X2y+TDId8lt
YEEgzsWPV2Y5iSurg2WxSMgoTV33V68Eo3FFt84SsxyM4/zJqcwJf2H8n/d/dzag
S16KNtMHoI6b6vUbjpgfzidkYIVRSw3hETCqoQ8FEz5StzThToqPtLmshqsCVd5+
4ZMwESGJLlBgEVfx0SX+U6KUKMl3Ib4J3RSy6Qu/FOexXeSvtj5IOSSbeJvZ08Jp
QUf/t6g3VEZoBE4tHEgoJ0Zuu8zfvFmp2gEqpnd66RIQVLEyL9dzkTdIQkamke8+
ldG6w/WJ1Fr5ZdESJJX1wVCLtw37w5VNLEy6S/h1nc9uaR9KWWEs0urePfjmzKS/
P15y7yH7MJKvEjsVw+rdLZELirfMCead912bf4cluv7aRl+Drcq0l2k1zdtCnwIn
TBo0xgx9gAtdV7kD8jpTYisgrZHGIYgY0Q7fM6Nss3I6dxnUjj4teYsOWmNqwf3S
QwGrQOrgZH616rmGJhgHG030XZQl0d1Qk6+yVpHrQ7TmOWPmWeUrATLh03+IbHXX
+x5ab8YQQGXH456gGcXFYSrWJ2k=
=P2pM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '963e5a22-fbb0-49df-a507-77515c0df174',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9HejoEOfjt48BQcwNRlo1OlOH9IUtRwuw4ch94WjXvkGV
JRdtF/J6m+Au7PddXe0AVeWS7GHkKxv0SBzCbgslG/7X6ZFp2DGUb9MA4p/B9hpg
wH140dGost0Ppuh3V3sJGrcLZ/l+6RwTjSimmNp8M44iIM4tt/ILGRZk1mpooRrb
bBHik7px7ifzDSXMKUjMKTSey60K4o/yYXqH61HeboCPpb/yl3r6EaYKyaIQbEoO
15brF9oa775FELDW/65plA+6VFSGJshwK64UpnDlN/Gfj1Ycc8YJK7kJ9Aip9qcF
wlwXjACMjn9UgOIA0yuNODP4xRKV2Qg/vrfrGSjQl3xZ8WyQa51XrzO2MCRA+KYC
vNeYoXDK8BSsX4WvBCoEcqw81pX0usp3XEnM2K7xi65fZclpfZLLz2DYtFCEoV4x
SDsjhdog8ugeDYmfPlemZtoJ5LB9FKtuxSEYuMGUrfW0qViNj0bScInupNbSahyW
zZR8g9STTJAa5CIVvvZpI0T3uv8UWVA8zf06aweq9p2Xpm+pOdv38cLtWG0KYtGu
BeMBApZnWf8asrKOxwpt6ZH7+2hb0dW9KJk0EZaAS6mdJb8xP6SHHVsc985BcZgm
po6M3BD2b8NJcmHiCb4/v7o70m6mxBCGTNsqhmFDBROlu3m+CoJ2C+PfZLXSH/rS
QgFLw0niz328B2AmSheEgFz/gh9Sj2QQgHptw80cdEJZcBywrvTHncbc0zD7I0uY
mNz8fjmzsEkx6H8YMzsjNawASw==
=SCPu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '977247b7-2726-4b6b-a72a-9472dc7b4578',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAApVAYs3s+AshwRVG58BeA77gfuPefQ1HLpEUeHU7gYm9n
TPFLeEVruT0SX/OOpuUOWBj0+iIZLmTAsW1+1a9WR7rYPxDKsRS45LjX0A/OFpbs
GwItI5DQD8AniFu9fQai/QUW2kv2mpvaJW1mw0xV6IjPtzBHhwl3QP25PHOrNj7m
ex9pdLEvzN4no8DXuMuRq6onGnng/Kg+luQGEXvo+ugZfXeXiAownZozZ4JNjUCT
LHEOVm03hNEo5fbs8cnrGozeNs4P/n9Os3SWwnd7nOo3xk7qQO0W0QlO4MuIBN+f
ZCAcmBUDfDwr9jTTXICfvMBggnXzrOh+prggX5CQ+F4tWqcSDJEr7Vz4BQ5kgrdc
VSITETJyewIVtJXloYrj8INLWPLEwMhxpEatbETXd4Y5bc06Zrv64LTpASSXm6/E
1CrNQ6AkoAYPuN47CUyvnLV/vLtYZhww3RrwLfdJH/hilsX6zhI9RxiBZp8B4Dg+
m+jbD74yDs8xcctOc/vGwhSNvq/6abEcm1Crs+DeEs/XJynEdMJmlZdZ++VE5qUA
e1IHYoeBqcEKysaB7yK8uSyk73xwFmLdsEGmoZuw2nkj4vXgH8ODTi4H/QgHE6rU
edD0/bcAT5Cl2tNBmcUMJPSilIdIM7Qt3DBmGtTWkmKNb4A9IH5Q06nwCC0uwrHS
QgHGYXhhbvGtSJ4TyeCmtXukEtVd5ks0SR6Lt4u7nKB6ydGBZCFf4MDXCgE35p01
LGlFqjkniJaiA7W3DM27QCVSEA==
=8wYF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9ac2dfbe-43e0-421e-a24f-c9d0ec7b45bd',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+P7uAV5IyTTuFMwp7b8CEGvlw2XLIC9AFPsoku/vlFh3y
EuEuEK5pCGqYu3mCjGTldnfIVnzFdbtVTD1w0U1uaeSSAP6oifMklIaJYIKyS8tH
AfoYuqK9TtIpyBi1VgT0B6L93vCtV2os/9bPw+Jg8hfVGs/IxlOUAxMLlLOiZF2O
Bqxx9HgHLcQCRdYMytvQ7PfT0anXyRbw9VeWo0aIQUsKmn7VsDkLICYZ0jSrRdki
KoKNJdrGgO/XZQAfkQPhGU9HO8g7er0Xiq7I7UkVteN5DXtxFt1ZzobugGf/g5+e
Ts9FLxwBrM6By/CLm3vzQ5bVoyOnGiCk3jgLDzPAGwZQ7ZOHo54rMTVqeQsCB1Gn
8DeOn7d/k/32E4CogCq6JRKgPib4I1la10kwc3a6KRECKTMBVOBBU+B8gPtgjChf
fYkvu56zb6Tekart95CIM++IUnr6aKZoIGjVloivSrK4gWp64R8/4utIxoz7MRRu
4hBmKwKTA6uf+Kopbnci6hAVjlCkZj3VRDlRn2taSj7bUH0aFG4EIxtu6KkMmP/o
0ymafO+bxT1gAVi2LidEy03dBwN2VCNyX+WNelqI8G87cv4P5OyYiaENbNtM7B/z
4a1SFAtURUH0hfVeWhogRO5+Ti7345Ub17KrZqsS5r5Ae91gAX6wQkF5O/I6RAfS
PQEpfLKxjBMoSHIgBegxJmvLyzwZH/CWLJknhvdCUWH66rJHFzRGJjYV3vwGO/j9
VAlluxWmjFTUiFNqTNU=
=oQ8v
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a43f3014-4c2e-41d1-a116-574ad4af66cf',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//S+mXNQd7SmFVqNUDG4BnzpE7Xy8Lh9JjrIj5TU4jm1e1
tjIlfI3AkDrsGGaPcoDMK5MQJHI95ifpdSjIK60LyQpH/3PdNnxGA0Pps1YfWZAr
ohg7x93SHQVpHw6g6tV6Cl3DEOknySGLAmxl/Z1f9e8L0i6KgVZ6/eVD5v3UmkW7
76PHIQM6MaBVGGfyw3KxjYWO6W6eteJPzDafncMD8ixDQ7GTfwLTdOANXeDaHSPj
hs1civzLkkPMyd2/FSGxDyoXaq40KjMzZiYTUc4UdRzVMljIPJX/u84z/9W9tb4H
oZHx4BIkjZa3HQCMVoOXTUNPlBuTNDNz+XTYl8ay6d7aLb/URBca068De0Tz1Owy
ZHxi1ZM9aNodtQ33QwPZCGiNFgGnq9diH64L1DfKea+zxvDdIC8z2/hJnKq08x0n
ItnoVwJ6dBno3CCu3a9Qxaz/bTTMWcBdzIXo2UVjW4hQwbbTGEB/DSgfOBVm5He4
6AleVEpgLhauleaJ+xsZRxRRnD5/hodgEzLixOJVaEHclX3Narnd7RFlrlrKMTko
gzvP6hvQVSSEl0an9TiAKJQBIFufzDeVSVzh6YRhST+7QwrD7FqWH+vmr5ckmxgX
JLL/lMNmtj3hZ0eeQwZPhPIKZijI47ZC0QED+45nWzkf8iMMMaFzzS9lhWfaN/PS
QQF/n2wsC67wPIcGpMj8YHsJzXDp4MRuK0ZJMZ02TFAF8zwOASPAiD7GgILmfm8D
615KBtYHzjKVATwhoakbooLr
=HWgF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ae8fa4a3-39fb-4888-a972-e50836b2be62',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAmYVi6ZD0vs8dL4wHSQ7ulis1vqk+MjXp+Fq6agvl/7xF
i/KEoR86JeT1XrzIUVNlTcO0ElVv8c0N51ZA0J+KYLeQDcWJPKkyAElRIoBHoYT7
VUVo5jrNmS9NzwSnpKS6B4MYRKka0RIXWnmC4P35WXDfmMiAy+PMuIe90OhS7pSq
YEraA7/3EDYzsdIE5XFrDbFpTUYFFbh/jdmu5Xms6C1sFi12WieMVLEtN9CIbXTl
0/U28N2QSDT0oItR7PQuHMxV09n1WMfDOJqVGbhDBxmGWfnwQE5Lj/Ie2yXYJ+wQ
tGDhRHJl5u9DmCD40D73ZC7NxgSgKbTQNkHHTG2AkoyEE/P89o4tWBk7eoV8jzwT
mIauPKLz3pmwPc/7EBywTqBZYeBKzqXkjJqXXimeDJ73Tg+TCRX6qYxc8gpFtPbi
9hGc+O3wdmqu7BYq32VXfXPYxpfIqdQqznbQIwg4IFdGxgdq7vthKn+xA32Vb+tb
L+fOfgB6MWvvO3W74sRZ7hkNa3DwCJ9ef9r+x1LFGOikmEqnYA3VbX6ZBXaAXOeo
8e5nPjNRvSp0MZokzH2KhSGxFG4Cn2xmAZo581nAiIfM8E0GbxycKGwmyqdlkhUk
EcbeOvCgtMTg+aeCkPsiYpeGQBCDTpdVGs4VKuBT2w4xsEpa9mRLL2NrpYgW4+fS
QwGbOpU5oepajNCyWtBVy0CAFZ4cW0Eh5Mi6Ujd9Eila3/GB4uQGA/xpGxpK0AyH
J28QFsiMvxP6SV+nI42Md8oyl14=
=aahv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'be12f78f-f184-408c-acf3-dacf79294f86',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//U8Tv1HLON2+CGjioW6fSmHQjE0/e6oWU726QHFtLVf4z
iTrRGeJ0WSEDqYxa0PDd0XpSZHd4RQn5YOVLe3wRzF12EHx7TnN0UlE4p+iEF7ni
lPjrQ3gHfcFdLRx7C7AV7NEd1IYSUsseNgfPp3T1YVpaO85LdsifbYPlgCtp5Oay
gOIkKAN3Om54O8egqHsU3ERQI5hZM5uNSsYTDSrlB1EFMugfPNtlFeS6hiyropf5
5Dh5GTuZ2MDOojduwR6Hzo5jCoF86OR4B5dmGjJpKXZvBljZAR1orptaHgzYzgYg
wAhqy/YaWqWK5PbrOs4RdqKYaBl/V0T9Tkt6oUUWOkeEUfFjLYCuVmZGeVPaV8OY
b+QZqme9iFO/6aZoIxe2kBNrmobrUpax2DVWhefvYK5RQ1O5S5R26fJO7z3JIf4+
Bs/Qca96vK/LtexsciOe8H6HVz4IV6sxWGH98GBxSMtHeJdJtG4N4UzBI94dbqCk
roruc4Oq2tOb6qGWJAHuAi1CYnbVcSPur5U5ihQ/DkHZ/x+S41JlE/xQN4SMcCfh
fa88QJK2OmOe6H6sohMvgR/UR+aBi/lDxNpzf2+wlZMXOK0jb5cXmpQdzNMYtByk
GC54hE+MWD+csy55DSd383GJQYpJBQ53zeL/vQDIkoJ0yb2FRpagnVKvco681cbS
QgH9VLrqzZGsgi31TJRqhdSfk9qIBIjBAGgjJN28VLs1PhvnltcRBFrZXDhkDewm
/CyV1ksv1UHWmLZauNCj5QUXvA==
=MBKc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c1ec0ea7-1175-4f7a-a3eb-43941dc853b3',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/f0aZBCwWUVpfdNdTDubYKJyEU6YRFnZgYDA2W2TBFQfU
UkD2etwqcsUN011zLQz4AP+dNtEwkP9q2wGnj3oNoIkae2HPfpL6LyzyQvbic4WV
3Nbw/2CcmzPrfRMDQpwn4WmZD92fQHJjK2rjAS2wf6HfFDxXvxvGQDa0BOyziWIp
pQzoCkZo0GAAzOiTvcTy8hcjAjWPActUBkaei8lJhQWBPg9rx9twC17x7uiLtABg
DvV6qpKqtwqpHfij+VioKj42jAE+qHZ9FB8OS2OWm83LZIoYNK5uDPS6lHiEbGNR
yI1j+5Muw5aF+0bC3Yim0oO6VdOvKrYrGxyaudxaZNJDATUsfokQEV1/yiWElvzF
P9k3tqanokj457R1k2ekrgi8YSscZ1BPxSpxdvgFIWrzBK0DsxFh+Z3XqWTmx8ve
y/Q6oA==
=t3Z5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c43edb0b-6a8e-451f-a8e6-b586eb6acaf5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+L9lx901aju/GJv3pBQE7vx+wK8Mm1X3s7GiODdZ3IIWG
owU71zM7jNQ+ejZ1OWqHr5dlkg0UdwaoglUnu+NFKv+HLUJT+DAZQ8oBik+p83bI
y9aTBxBTkUPILu60INMaCDin72xf2bk3Y4fOvDwBSgLCTuFD3ljvOsIb87QRKP2u
GoL2GVfRtzYg2IyS4AaNZQ5XEmT5uRN9xph9ZL7IBB9l5TnzgyOM62fxByPTEuqj
ljzdWvQNcMb1iPzaTbCyvY4xUNZ7RKNrYjJiJRSrQXRgLxIblL4W0U0k6rjrucWv
7A0m25X7PDOmyYjnYURS08vQzMWcIMQHV1mERt0grg8mciEg6nGrbN5NaJH8p2Hi
3+o7HErJO80+jnzWgEeNrpcrHG/ZCTJimYHlgogPrd7ZFgRdnYhaOoHOenQa8FCo
qYA5hs6GbCw3+F12pka4lx8yDh/QzIcAfE52E+JERZ4cK6k+G8640oZqQgX9vWnz
oRlh7iLd0aBuzZVoSiZMSjPTSH/0fHmOeWKk1NyblL2d0FFq+quYuHIrScOL+eEX
FXHES2RCWfiuE8g5+hPH8LTmcoHI1Q3Hk/Oauq1/opu6IBJ0+TvWW6z2OXpW9vBT
PsYd68Y26ig0QCoiRdDaTMUKVpqwdltsDiSCJym43eS2D1tnJxEkoz8jlawh6zPS
QAFh4WoiJsncSY4tzmDLjdZTLds0tTvTylj2Jo2bRIunahCtDbw9b/aRM1mv3RRX
OCtvg0CJfaXoGVbNC/LklHU=
=4VK8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c5ba36e9-28c7-46f4-a320-dabca01e340a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA4/9bEc8m0KCg1Bi5jtIqeYz5AFzt3g8mrDwNgZ2mz00c
DxJUkK3xuhq0ORsM2EE3GwuYozknko/tlKbnPxky5IC6X0RKCXbrUM6ktmCb942Z
DQLSIq7gVoidg4HloFD74hocEKRXvBqwvWI1QlGESvzHQiqO8OvWOAkLEQDGcVF+
xBuwK2saDhBXPz7tTxcGpTeqg31SJrOuBzBoFTbAOroLJKcp20BsCxVcEJ5afA4h
kX1ZlyMJLmhEfBMOub60i9zcedmZ/o0FEjD6kwxTXvtxB9YlABUBpKCqju+3Rms7
PPS/dh1qEKQ4ECrLqxFXT+N+g/XTLsGhHGxfb43KQnNGTO2vqsasn2QJoaFdcF3h
kMQfi9n0Om34uMovjlM3BB+PjhgtZ9EgmiSdmE+g4vqrJ+ylPe38h+/CipK15ew2
lXdf/o+h991J5yRaTBSNTCmd3rAoa4948zTLhqA9NvfopmwOUhRG1+3C6V52zDVl
qICSDzyYcmJbZSuemm/r3JSz31gNIWHwZS6s/NpOeyQxKX6IVQJRBMfFO0M2F3Ey
LMb8yFrbBmhwvloD9L6H0a+uAPcZ+IRDLTENhmzVVsDoise8prqTObs6uU29iwNj
yZw4lPOFY6YM0z4ZWIPSNduimSAmU/Lm4XUGdW7TwCVX0a8yC7hCZiGV8c3pGaDS
QwHHUKcPh6D8Cd+Zq8J94S5/k8qOFGRz+cYz237kBoHzaJzs/NDpzk46g9XQQTma
k1yvbLLQmN0iaLLXl5XxNfazdqw=
=fBQb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ca015967-6b49-4df0-af46-cc808075dc09',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAgqL5FAgYjigc28II/HCahQrOQe7KiC3STSm+dWrvI1Oz
/5MfkH/R/BOLC+v6nhbc4Eu+EXqjedQRWoj2zRO8nT5G6DUFNYJuLC/pXz6VYcCT
l/Q87hh0hVwytvnuhExxu+ePmsbCSajyrgP1iPSiz16IEMbZ5eKzHKSpq0yQx72R
4D2OekJger69XJNsPcSpD19rU5crgK2is82SmFSLXlsZwehCapPQRsiOiXLotSRR
EfzDg4XNdWaYx0z1QIk7/zc4gS+ZyGvNKS24K1mGtsw8U/uDj6RMky42MT3ngJ1P
/oPl2WjTqiBLNslhaZ+datcmytUIONogrtUjU2QfsVHqFd+EiclytRYvCPh6CT4L
NZDP9y9xN4sSxFPNXnh6UHnahKqkx1kDZ66Cl2sQVnehmbG+PrNxXuFn6rub0gPT
5B/GbL/nFk33a13whbvbGTFPJIhTLXjmAZqmLfXH/vParESP8T8HzgvjQcdodKUp
Qf5a9+WVYMpOkk/7QEd+hi/yJbkfcTytbRD69KPFjH6DFzVXXtVkRmOAK8SQ9p/p
Yp1qrmDXytpK5oKjyTh4Xp2k/9dxICywLdGVv/VTSs0UUdersJ0jB5LswcucIQxE
GQvV4T1d+j9c8z0+1WCbDQIwYI/o3Q5VnOcnqXYCijrIjoA2ZZC/mKmdkF/83A/S
QwF9UmDHVkgH3pCynk2h/Tal8K7SKy/9/1T0Xraad4m5sqUju7fq9W5bzK91rQbX
a9mRvPaV8yF84D3idROe72ahA4U=
=Wcf2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cc319d8d-769d-445f-a942-c6355f597a34',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAn9fPOtZjqRjmngJdOlp0MTAI0oIX87hdjpXDgmft22it
tX+f9xPWKhC1034MUsEy5gP9Cd3boua37BXIENg93E9r4RgPrMc2IlI0NQB0diId
wK1Iq0XJCxifadqp772UOKQyI8HHljVJfX7I4gb4t1SmkEne8eEkKQC+fTw6+e2n
KkW07lC6fLLi13wpFqcFnOIQCUYyfnl/JIWRNW5m1lJWMeMEtloV00QrntdMCYqm
frpsnC83e6QM+llYJjn4+HC/7gkOy5VDYsoEFUgBrFRKCxLqBP+/TppWT7FE0RjW
L7hW39rJhZa18A0HGjIbbERtM+HvyZ5HxUj/sSVepufh+1ZHupnalehphraiwsgD
RrL82BV9xhWxYMd46vbrFbgQK9bMzL/kPEabugnx6NQJR7XJh6jgdt+fba4xGZp8
+15MyM0uoLS59+pGPXw3SiIzRcM1xx8rapD7Y1DOP7A/lJrrwScKle2fyVQCJ+mG
xGg4Mcz2Dax2rdCVsoV3vuBZjasLupKZ5+U9H5DCT04Ow133ajat5MwhVyrIj0uE
dDWA2AwkZcI+sgQ83kXtD2EinIYW2qkNK79hSUVTSWwolhkgUdyT5MpKaD4AAWa2
UiDbAiCFtlM0W04pSD2XTDoNYFhL1JXPIymytk6PL3MUhzyCfO7C6TVqXzaRKuzS
QQGqRIhjFPgF2zVwA09imY/p5rZAmiODxjE+znTkJPiOivIpbcL+zipAJ7r0ro3g
XSGZgOWdoxCG2Cd8qXygOyKE
=RolY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf067182-2bef-4784-ab64-7c656122608b',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAm/0ka+ZINXrgp/aAigdMV7waIxLqtotbj6weidfprFoc
lLCVmGBV+/TUYWxRH0PpP2iirO90+31L/pDXPMa9JVBysFMaRazn2FmwTnzfaRQ0
Fsy3XS3HO7y0pXFhspy4Mli7PPWlFDNO5Cpfgw1NO7s1ghxIsA/mWqEaiMO0ccBL
pT+CfSaqTgtZEa4HUszSuwGJVvnm2bVxTssZYdC4DnxlIyD8FWjXPO496M+YnHRu
xvBNET7UIBu1xl0TznACnwV9+B+tthoS16HrtLAXRt0D29wPJyQ7Rxh7pUoBFHeK
wL659tCyG3kvmU3b3fITrscZUem1lRDSdJQg/eLd1mc0tSPWLmHHd+SPL5noAUNg
1+VmCSxVTbfDx4Yv2YaIhNlx9nAdkRs49O+YELf1keHaSnY/pdTrka5LnfG/pndC
1jystq/LWf3xYSO8cI4/QJ0kz/a/rkMYdAgJZpS46ON8wKK2s+Icjx6hAmLlS/6r
Z0hUwQZlz3d+PgwmHXFznT1B5pBTxh0ww7MK9mb/B39tcwmPMzufKX1Aa3RKVwVt
+qwKq+Cwa64QLqAGuiin9noUlyLfal+OAV7/UWmMUtCviKugTMvEReqIu17ADXWN
Y9ZkwZoQO3Vg7/UDbTTtAPeCF9arUDyFuYwMpryjKfP6Z89CCWK1ny58tdRMTSHS
QwHJFV8mEhSFNDBvIWtjghqpbTjv+Upss3RVfuqlNCNG9DqKpl4vi0GnTY3MxxCx
pBNO+Api/Kzsub3bqaqn8yXuBr0=
=vMM8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd00d0f4e-1f1a-4a7d-a8b3-6ca128d9d412',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAmBlVhWzK1BJ7wYlJpLJpCwVLXHnfQGx8kUcQlavmeTY6
jHF41lif+/vaaCAje/pWgMbOZK6uZWf31I7Pr3OcS3Mx2jIbUS25milK33+BJ9fV
mRDY1pGZMmk+4WJlyA39uToZnlxxHCOwSfJ1T+Y/31wAmxJaCloMdg0F5Zz2ioPm
IVBxvNBN6EZYZcDx403BMZVwcsBBgudiLChu8r8uZN8RaCIQM0sAHS8/Ngv7VHZd
eoExyZ3hwCzxMtPIAyVW79LRwnuhUrttiLCHKh++/lwuUCXyLQlVSmUduIxRISVg
GDjzw6bIcQHfnycof8f/Mg6Kv3hr7qYhe6VucCWpmbNZk20qYq36WleZt1zBF0Mw
B67XpPOnht5EPAfq+HGBxAjGiB2VRRa1gi1v2dKdJa52QRaTKrh1ZVXB4+pm791f
T0A31YtdjRXTRMDJzUgRgyrwT5+Qye++InODIFHvPoFFoA/MeqEXHCV+jR4KspKl
cBpFHjkNmLjN7fiuhjR2yYk2PGm9fAmTFSBrp9juGSd2Z88UBhzYRtFR9xFp0ROr
vV2Zmt/bKdnjFGeMEHdZxoiHSmM9jmnHmKYxdq1209cYCts06ZhwOwcxpcVzDNNb
TbwZ4RBHD7iluna4TS9ZneUjmxdiq5pSbXdlgdFZcHoAFgc+zNSKMrVClRs4/ZDS
QQF+G86Em94s78PtM7dbLX/cXiCtgYkMxBO7XBVc4NTbPlRrKu7znKwFRy4f8clH
mgDuqOZqPZhaqtBcDAFcQSEP
=6+e/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd3aa477c-9162-4a9b-ab9e-4e1e596d287b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAhPwJZJHXK11Bi1LHAH0fpB+d5BYCzRJdxzuBemSX3COt
N09L8qY/gkdBhwJyUm96rGHUEWkuYPLDt4wJDRS5tWv/ZnWroM+gYCACXU40MD7S
ssKr1eW981hqdwFg8VfEJtpSztKah3lFXwtfxZDet88ipVQ2ibxjYWweBBaLwNXO
+PhMJSwOsLaN1qSnKPfupuPxu1scIrClpiY3k8e0ET0A7CvQ+b2fSG6+kJZTsN62
JJCd6pSqUJRxJCtUDj2rjp3E0eCDQoi2w1y7daWplj54GrRMA3murQU91aLY9X1h
DUZVGmtbE/KlfBAyYNFR68mmgYSBObwsS4ISA/+RIOHMqMC1ho/m7n+md8OEk7tW
khl1sJ7y7BYQut/FZYTlK2IyPsTOzdVnPBIlpkypvnF95pyBMK35HeY2CQne1PnY
5zaIZoJ7NeLxm1Qe7gRtef2tOq3iO1N5PND8epNoVg3Gjb6MiOAfuGE0WnsI3wao
M/bFCDX+jhcISPBSdtwLAI+Wr5/BJJTRx3cEG6c8CFxjAT1AqXZ9Dv5/7yf18KDu
iufRZPkUI/BLsiqwv+Zduiixx9UkKMSHGqWGIMHIqAJCFdu+dtZ4H48ihMT3H9/t
h9xkSV43GpAdE2c0PpUGpzg7pjDXOCdpMcLQRPdoATecVYwmfxdJqXWvFa89IcDS
QQFXYAdaTZ/CSC68lKrjNQ5nQJqUiE4RxP45dnLJTRwyGRRyxOBwoacWynZnLbKJ
1vi/TzO3o3DNdb/8YZ6CInwA
=t7aI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd4e67b7e-dc2d-4636-a059-be75c0109235',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAgwqmludNslg9NaUhGFqLqJ5hoahC/W6A/hCwmwiuxxp1
yuLxCgwIwJzFfaa1uiuiLGH+5Sg7KsrkQYREtDyK665AtpDGuzfsea0cfxPRHYWL
6c5R3/sT54Q6VZVdz7Uq7U7Z9Kvf0wM1xSHQ0qVYjak/+tDB/mUX3NUnYk/RpohX
c2JhCWVIxM9mpMPTffPnHY9HDimSKvThxVIIK2+noTl5CilxUevUsrHPN0TCzGz4
9Yvma3SEClgIXgrKpJzx0r+ZTbeYtlOxQHFKdKhDkIBeOIxz5NKUxvk6fnBdY35F
dR0lB+RUL9Pzm66ztesCaKMiH/6w50ivy6FCfesF5dJBAWkbJ2r7xGicF+HXQ8+x
Qzcxn4ezxqF0cgojMKfEgkWuZCt0szcwR6MP6/DZ/YruM8zFJiCrxxOublTrGfS7
FP0=
=OSgx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dee5a9a4-c4e1-4caa-aa0e-4c7dcb104871',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAsmM5MAx7MjIOYj/r5BKzrtHBBlMF4+dYB/6DeeEo6Vcn
X3tbAPog9ckWHmzpnRdnfoTPygRhP1oaB9ypEu9DtyF4go3npJoR6Rkc/eoCdHTW
X996em9Bq7cPecem928yts1F5lEW8k8+0+ewGcq/eofjHeAltKyXjeX8R1lqA3By
xU4W26PuwmV2iHUaws6Gq9OGmG/lcbidbAXHxCXMwEYnjpKhLRyBX+ZZEkNUzKZF
ACkQs7FD+5FtYhVa1nbt/hiz3i9YAu8NOGZDbIHE9UTGwVsHxUtb0Fdiz+R9W1ba
9tmMD3eWPq03Tas+mqEIZ6bLG1EC2HARzUMIWwQdXxWyVLadmxMGHY57unwuoIfY
4KXd1B9+h0Tt1i6XsInHu9O3kOjCMjO+MdHiCWEmUfVF6BttD2wnJ4chQBt9B9Vz
P5pGh6UzvRj2qyUmcCBmpvmnBa5cqmwAvFATgDszZiCHeJ1KirYeCb3GslheOknP
lQzNTT5/Dv1/WKSggL6aoeK2kNqn3EvzNr3ZeksGVi+G30z+pTSWSDQJa6bTRgbd
ND/Nbo8kBNpKXwZq2pLqnOjr9o5AQlK9/Li9aGI9UbNGQTtY7GqKZgz0gbJG15jl
B6oszzb1EzM/StRiY2XaZ63TOoUpbbnqaXlrjT+DlvhkahSYfBV1Z7NIB+5q0SDS
PQHsZCmUUjfppUlGk72k5eM/WbimKgMYkWQ8gk308ilD9tRb3kHrknL0MPZFmlhB
sUVhkKP2OzGuGiUsbAI=
=1QpW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dfd9c661-783b-4712-a851-40707cc89c56',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//RejQ4IuzCDpDpBZd8LRqYCaAj+1jvb/t7K42VZGkfjno
d7m2YV1ZKvQJitguHmrAkO02Socjjotx/2w1uCrqsVVQBVlk8y9hUpn3TcRHDhSw
2sSg6iKZoZoPvClzqv6D7vwdU9wa/3azoyzxhPMu6oYonrv1dWiZR0V74aRogvak
p+fJtt/t2HJqxzIcDsM8++cgIZe4p2biFmVIyKgeEfFFbuIZ0Trforp+arDeCwcr
iLeRZAzEKOaefSv+d14hosYfB4Gx6+wWbRHG0tp0n21bs4dv4OBYRU0NHAhh++p4
ywgklpID24HlX5zWH5SH83Q2GO0y+2CMt9WyusZFGpDsF4slo/GvqZb+MOYqF09A
55x2tYzrbgIELGLfzO/ggt++NvLESs5VJyZ3qy8GjX84puSIPO2RxkjvIDsM2yBW
FeRWCujxzw5HNhBLS4l2+MCaCzZsDt/dLfowDIu+dUp5nyEHMWGfucj/RN/X0w2j
XaykXe6msfO7BthqU58voF7kSwnhHb9rnjpny+NJ6END0/mzXBj8fTlTyKC15Zdz
wALFBcDG6ZvJy2umRNkBV0fGmVyfyH4d4qVhmBKrDQRUmNU6gxpNE0uoKuI83p0V
yx0IXxMfO2pA1SCMLaV99uSh7qjETrYXkIANNaQIxF790r9iGS66XwOavRWDtsnS
QQF0CahZLSZH8DHyiy4S2ovAs3Az4dvSldXdQ6AkGZKFVVS70whZMrV2T4SmMB/e
AmKgA/c2+WN4N08rhMZAnspq
=06JB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eb98c8ca-4b2e-400b-a749-dca2c3dbf225',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//UOmwpUUyvSDuc85WSZcAlQ/gw5jebDSD+KD3wuj8dc22
ueFEBjbeEZJluIxoAv/NQOyS/AB9o9DoWYDqlWo0e8CgRNtrYUDtMdpkqQBcAK3Y
LbIFSkPvEcthd2+INbSK+70hMq8qHv1nPNaa8TLB9e4v4C5udL21znPhbVnfIcUm
L2BaP2nX9aFKD3q/pmCy9WI+7pdm6AiIz8VPIpN4XtEuO6iM9jE6UAUEmpfcaH5O
BJBOSMQzSd7y8WThGoAc46qodODlc94pVHYPWcnVtW1cU1uFlTO1iS+tSH+5vKWh
Wo761MbIUxphY1CKF1Y3Cmkq+2TCPqQKzOsd7jdPsH2KoUvGEvPdvqFQHInVSWMM
c8dSGbYnbvFm/8HOkEeytYRikgj0O4UFiM/im4AmOyjyEHmY83NE3pt48Fzv4PNo
ZvKfyf0QNsFSihR/k8Buk0x/a3UdxU2EzUtSDxu+UCVMpPlDsA91AnXzIo+j2kJP
ibUrAtiH+yWuoofUFGwVTq2SysouzgPFGdOWkgD4WgilSdg28Pri5s8iINMT10zd
RfnE05m4u5+qOSjH6TOLi3KG1HrVDhUrMh7dMrtct06UfENVhXY65uZAAV+Xliws
CL2dBA7wfz2furpodKXp+y0jHrm1ZmLkc7CpRc4ng37/peamA0XKbTlY1OwAp5rS
QwFtNNLxnJSKpO7rIhtmNYif7eGFniRi3Mom9o5m7tJF9Mw/rd5DntOLW8V/zN6W
gb0nCqSjJrmq56bGcL/Mdi1SaDg=
=6sum
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'edb089d7-7b2e-40f9-a082-55218895f137',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAuT1v2SVF9pJd+4CdDXtCiWCMddDSPqqw7/5t2sjRZPBd
Dwcs60mI5BcgPOk4rRUs20AJSj6JYtj5YW+o/hbGTh5w5HdqQRWkWHj3gv2LcZZ9
A4LPa/EBeYh+YxwrYEl5oL6d3NlNmjcHcOe5NaB/IwLt7ilbb80zB3gWZz/uh3IS
u6rn2cRqo9wCz0CmCY4GJUjm3E3X9RhYrv1W1FSI9aPOHgu6q59aykcn1HSQPnco
Jlm7K8vyGuZrr8e2MmseTrFvDTy/QvBHZdqXMLyDuTJsinWnNgaiPCitXKBQygHO
IzUsXtUxv3SiAQu0p8TQRGh7nOVhyLUgyOBtnFKeKEAdCxmrck05lXj4B3CRosHY
d/X1bK0qDq8lJQaeI6aLNFSCFKsEql5mV///G1ada5OKkqNSyErTXDSQxJq58aSD
H6/OgMO5k3K95MfGeRuSXEFUmGo2rtGbCb6MPVM8L/i/uH1aHtCiTctIY5Dmpg//
I6z6l5EuvCqruK6q0tdwpj/NZN5YAtzT1KIXrExh4+LZlTtcjCPztK8Y/OecpgQ1
1ZVRlo76S4+i38p5LOrZMhFu/nJ9ByJ6nGjInk24SBlx9ykdH8JA1GXzIXEyz0zn
ky7gM4cgqpSrlLnlx2Xuj5Z9DD8cmLlpAAEw0NA/rgsWGk1CVoWrYwQyRCLgrVLS
QAGGG/eNbYYmsXjCOgSTY+8dIsnTh6tGZOAsbKTj2JPvHfczh1YBDTWl9jLdMbZ3
fyIk1zRWkJE3DHDEadL69hU=
=HOJO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'edcb59a5-16f6-4f5c-a98b-051fcb2a7929',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+P9AWDMyz6zJJl3SEepzA+/ROLj1jSruiSp8wsg1l0KoH
UnK4jgXImFELJpTFfRlnT7GIwBHhRjkqfOtDhYDPv0PZqAgsM+9OA4TG1sBpQOe5
hOrK+WD5WuYqJ1O3/KzcMs4oz0GQLUZ7yDFA5Ci69ZHIFAZEou1VFcxsfqrC6CJq
EP2C1LS3dPSYJo2+yy8Fdh+IbufAobVYUeY3bCOGmjDHg+KaNUs6ts2rXOWb+NoL
VayYypWeFCd2omQvhM5fMISnxBJ+Zpi7oIaNuduOUhRJL3Ke18Xde621tW/83eXU
CE23E5i96C5dTwvRwfWriNpssR3YT08Ith0BTNJXvws9I3WXll0FV8Mo6n3LvLv2
D5bCrm7OvkeJ9s2ixsNQkBTcXRiLalqbpbGuHcJiQXdsqo2q3dCJLW09oPccefed
yAlURKdKw8BFokCEi9kg76xLRr+ZKKFZBzcMHof5r1UveKEm/qpkPk8qa4DPgZ47
V3VmWWudjyLcIT9SJ6+n0GqFwC/RlDQxmBVzwZDTzygSH4e4MIgm0EGHBX5ckySQ
Nf+0clfqDokLxF74onNdQ/Idg63YJs3JREiPDi17E7QMQSUnWBSkKGbeqA22gB7D
kV25hMuOaaMbdGVxvsX36ds47gdjKDql9G5aZKjVeFkulqhFwQQfo0OPeg023MHS
QAGZ72xUECkzy15HClQ49EY/srdItcC6zO/6Rn6upQP++tCE5jwOnyvNzD7zW7I2
EVbHTNi3T158jaUWg5di2UM=
=jVgQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eeba9cc1-05a4-406e-a8f0-0f479737fb29',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//RSK0y8Zb0hawIhFP4o8gnmq9q24uxS1JFHFVvD6UDYdT
yv/bHroxZ5Jm02AWEmPwdtnyzHfxfZSQvP49ZwyMndnmSV7ENYMAKwGog4YLFVa4
c/7j9NDJuCMBbgHh7+lhad+WGkWohl4JO7S0s5DALqHQIxP0qIDn2SjYQf9NN9ym
pqiiieg0CAu+TIYbMuVTsb4YyVVGG0JCrfUwJaCKX8VRmipCi+A9YDJW5Dk/9fsY
YF6ieVAj7cJiuHE6QOQzaMvIeraEOaoApdce34D0biaxnH8CNN9u0vR+PIMZ7W1k
AGz1e54Aw1e2uVcWqfPRl8bY2aaMWPoR7JDAIuEViME7YbbZlcbr/Oz1wt3/2RnS
ckejr0paRKIz4cM47lRE0n5HlpgeaGXIbnMN9UarqZ/kLhjGaTR1QeZt9d8Wjh/k
/h3yVYvFOYx75YenwEReSRrAnkBECZDDMJb3DEvz1vf+shkQxYyDsN+fJNp767HI
nXoY4kW/qZAmuzS/KMBnwGPpzG74T1jMSMLhtop1iGC+KL9Fm0irOLjfjCyBtfXh
il7duPGOVdTwlP0CmMqC3u/AifBEpUTnpCPLWitATikRqjs43F6J5CCCyeLNQNAG
8pmriyvutVaK+Jc5/OY8XBhBc/a0W1VccBUu8hTeNKT2lU+cjFOtQn3oWTcrj4DS
QQFTBrzdFCz4D46ZBi0BcABkIiEXg0c3R+3JbHm9jdWbHLahhzHDMO838roI7PU2
fCAu5MRyet0MkS00uls3PyyU
=1jYU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ef15789f-7e40-4bb1-af17-35e1f24344a2',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf9HzAM7ZSPtVD6X1sdQQd5W7qPrOOpYfBqPM6hBoYpMM71
6WJrWQrOGUFDMQVh40hbUHOpfwS2TQhj9vk9en7DvTltHvezzRM6ynRDZGtA8cJI
5Wu+qZ+5jZUyP5tM8ZMMBrtCfhCJmKqhnniRDuvSVEuAOFr8MOkVSg9RBWvFRyY6
5FfJ/f7ZkA42Ikh/u1FAl0Bl0wn9ikdEI72eaNyLrPMO/Kexfhbyk7DbZqwAVwmv
r7ppwrwnj54dn5c4XNsxIVRN49eqkCEMJ3EKtHGZQXlBTZBH3bvqHBXL9vF6c0HJ
ZFtMgyMRHMM66VgjxPUe47StGvPW3YFSRGr48kIVOtJBAaCrRmeQxXhSQUt3O06i
W3tusEcjnVx2DM4yXvuc4o7eFhoS/oC/58PfCsLbcbdQ9+PY78fEhJNIXS/UM7oq
YcA=
=WmgG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f2b78d16-cf7c-42ee-a6e4-3d9294025a06',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAh0dQqoOhkR+xvtyOVX4osESyha6xwE1wFHGK9dagec+4
v/18K8mZ5p7wE5exEK3qrqspwuaMhlxIykKnL/j/HrLtX0O9A0vuppc+60r+8luE
MI1wwBE/pyiIVJ1HCVTDHhw++rU769eu7ho1xUyugXthTDxLwkwMDG4kgddRwhOM
FZHJBMkub72Q4zW15Ibi55lUtWWPwIyFU8VlXFEXEeeUP6Ooi3BhmyIFnopWALiG
i35NKRo7h6CTXSMo+pmbTD7eir3Uxt1OwN1pKFVT1sg7c/idMJXKeM3W/NkIASve
JoDoTPJyajvGxmO0o+ZQvoH3aOFT6f35MlNhcgWeh7skQRuCUawqtg+ij0IIbtBN
35xqemaW+hLrfNTwxWYxn3EwfiXAoFSHcHJkfS0GwCa+B2eAQeyWyZe/MtD6uLqN
aJyxFvvHdJ3DCwAMQt03fcv6BZTVwRWTLKuw1PRtTYK7JDA/iCag8zkAZhY7FI1i
kdNJkEsRsTgkErp8GMCHDiQi9eoQ5UdqIFAQrWVEyl4fcrVNLwJO2YhzcWb2Xruc
+qekTm6ofOwHM59NsaVEVr3JQa8PxlwUdcvcPqEL1YNn63daOjQ/SndmS2waSUT/
tOwbmO92xmxSEgDkF6h+Q+paG560vwpBgTkdFMD2trInp5WpEAoS2M+lC83NPlfS
QQFG9g02GpfH4XPpQmgGOEbW76M2/bRW29dthpa5snlTcS81cMgkBGkhrV9WNVvl
45UHw4dZqahvOyD1LUQVKXEp
=H7e9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd0bd7a1-be10-46cf-a5d3-cbbb4c2a0eff',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/VED2WnCOrRybTVT1BzOuKfhwBV4pvvj7gEMMWae6AmZ3
niI6QM7nHk/UMYVRSP2bU2h3Azv2WiSJvB4eDY8PPjpAt/nxxPqdmZqZ2O2nDQaF
QzAaDo6eYefMnum3ycCSV3aKYLIbb7QhDCwW6l8myrDVxT8ToVIirKtAMWQCDkOd
vuBzmP2weeEvzHyR2OHW59i6P5eRu/KedpT72ote31ihEdRS9baG9+ORGMtVstpC
HpcLgIp0PF+9d3WfQ7PKn9/QCuhbkIQ5P6GEmRp0V34pSSfX8XHf/Yu6P/qwun0u
s1Gktisw9fbT0OO2U+EakHDlJwGJ6Hq9uEf9HTt+FtJAASFrYdnth7RYCGPXQZo0
LfT+ZdL6oEhpaTzSZ8ax4Ht8BxknatnI03mVZAdFWr+x91SooqPpfCOZNyceiiwH
zg==
=qnm9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd8d7c52-dc17-4d21-a1dc-86b715f12149',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAhG58454jkSLSGF8OKmojkQgJgwhU5mPh3OJOKfyGjIGG
gn5dEaJYFIvXa0Q5twxQOGj9QqUoDaZ8kZUzec/2PXaCITPnaNZEOTfPhNOWJe+q
mQwzrZoqJFvZoZV0xyvW9t4A0Zzkg9UGRAEbBsqRtSWv4Ox5ENM4U1pJ2zv4FJvc
DQ+BdRC0CZHQr+jzJfTIA2zbh+5nrUpksXqemCQAdqXi6XmOQpIFJRISuHc6WMC3
IpHYgqxHzErANgYKhY35xki2EyCVmq0TFw8xdObWAjmMNGZp1XMZciNiepVCglyn
8wligFceNvC2G8rXjalmyYwBmW+RdS4iGJTxH7kan9I9AVoL+iB7FSRczx6oSMXJ
22a9/EdFrvwfIsUYypdFQkLvAjtnEbTFz2kQMo2ShFnPb1AYti45vVlV4+re7w==
=xXY5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'feef4f5f-a13a-42bf-aa6d-ef41bfdc843d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/6A7cb6KV9PNcaSRWRLOrREe8WWNHDRiiO7Zc8ALPr0F//
d04+QAhUz56UROIGl+rD9LaSmbBPmEHKSVqoTV+I0kHL31FWTtU/ioPbxkfnSsSz
XXGQQM3kAmLUWWNGevs2lbjlrUIsp/rm9CLnn1L/vtxQpBdCm9EYgDy058GIPa7f
QFsBLcVOZUTEYAs0DeVVpf+e81EN+922hGeo7/PCzMQdByN8krWjgMePvkkjWKl6
UQvjp9pvKCe75iyzdul1aXyUPaueydmHJfU5/VZF9jKvFcQeA1mF8UebussJiQ1q
x4pAKFJRUFFxW6BIn+CnosNXYi2xt8T4tbsh+FN+lKdQSzDqsiLghyQdKooINUSa
/pwouha8lPgRGDfhyhzvOMdG/LpwvnpA6TwBaTtigbuoSqjPJxCAV6bNXU1RWerB
HfGgTilFWSXHdtAYd2RLAX+Wjxp5cqHTJcmvuUNdfN/WTr9nKs8N1enNS3v2Efcr
lWjyoqC72iijIjY8fGipxn2DdB1ZFyiUMksc9BVEik7dzuCjuq+kOVeSSmxwOVPh
Y+a4JJ9Y/+OpNRHZdRe6LqaTrf48pQv+kbZEZu7BixWvNY2nVsdd3AU4ZSRuOaE+
ntT/UKFwevNo81057oIjDkCt10rKNlQyTbtrgA8Dlcr+vuQv0OlSLyqTXF+83IXS
QAHjSUQsmDdeta3e3t3FV67jXjhxavdlSZeDBp7JwwhqYA40Up0rK9bdG0eN/gNf
2OPItfbiZMtCGB5SR/fxQRM=
=li81
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
