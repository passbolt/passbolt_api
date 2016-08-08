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
			'id' => '0330e72d-8cec-4322-abd3-010e2efcf2cb',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/8DQwv2OU9+aK3RT22yde5XY8BNpk89YXPusLKzUoRH7cV
2wBXWgKSc2kntSwzL7sYmtlDUqu6wyd9dbqXyEcbk9/YiE2+SpDKt8OvLyo9Edpj
11LK9lqwxBUskwZlxlxJy8yN8dEBa4Un/th1alJEYkvNtVNCl2xqAte0RwIFJ4Fl
mq3OSm1wDIGonD1a89ajfABx+9alkunxVkTmuReYzMMorWzI4Lc2XMs5tS1nhDPK
zY/IbdGEZGn3GKt77xzOkHlFwU/NuSB9jJ6LNALHSTeD/HFL1G2P1V6IoQH1YRph
QGOG5sRcNqRyXzCb55L6NOjhSxJ9BMuD4+G4KwzLnlbdNkaDfqvdPcJo/stAHzKM
xDL3Nqo0VGHK1aLdf8E1dbXD+DsHVixD6rr6lCZ4C2VJI4cbYnmUZHikBcvRzDGG
s29SXfGFCaZ3rcR07DrUxK6guWp1QdfHTtndcwzO0N4pkezv/VxbE6PoWqLQr8Jz
bLweKnnrLw7fYO/DF0l91j3jyQjvXD9VK6EO5M2meE+nhM4S9zNfwAfacewfKSU1
EPhMhwFBMdfHPtK0kmXWa/+u9+TUAvl8xT7eV6mcSSLXtSYq8qmmLVVfNS/2oift
3VC37leWwSuwLmSYhtyckN1b2Sfc5aEmswn4aM3Rfs1ZzXt6hZDUaDkaTa0wDg7S
QAFPGuSM3Oo1PzbM59/FGYC/HrwPZE7sON+Mp8hbVd0f8La5SsfZ8b0SrFiaZucN
IXg7bZ1ZpcbfkBAP3bVV9zw=
=2PgS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
		),
		array(
			'id' => '08071b65-7da7-40ca-a627-05c1075b0430',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAgV8NXozH0yVx60Q95EY1YFwmfNSdh0xA0KRDIE+8DJf1
s6dkcCEw/JuySasmnKSx9l1p7Sgc7dtbaFxYeo/kkLHrjfm3NGUdudjv9WhxU8VT
rfqJozIgwzAAA2THRCJbhVGnPGeIOQi7m9j9n3WRRPMBJm6D9LPCQz6hp4RTVrJ7
D8QyVB+WqVyp3CmQP5D3USCTmWZJDK2w3rBS37au9/J+sRhLqyfmwHIRrfR2qY3h
zF7RCzlPYes5mX5NX1Fnwafl728RtNLn5QJydsKWleHVKK+HoLpZB6sh5yXMqFOn
1caFOuA1tA9nTd1CYuHU/XHkWIsfDeI4V7IGNQnB3ODafqWrYu9o5/FYBQjBInKJ
K0TMBHvB623M9VJEPx8DiO3f6p7NxzAlSrdWvUZSpr5QCav06SEzZ22+KmPwlhM5
aYmOwOH0uHoZKlHaXcSJ9G1/ebXpAm3EbvyXaj4mxZnTrKZjNfTP3iPtWyAcPm7U
VPGcAevA9/eRJ6kuHv+9FT1XWOeVRFdPZ2k9Jf9IOeER9LKwcxJBNGuq1++Pmeav
WQBcMnXh805eVM2TJcLwyCiFJVX7SEpvTS22c9ixZA7kFxx2deyx+nM2ELvFhY39
TG9RRbuQEgl0RNFU3Ccabzlc04L9yVbtWxaV8t0EhcMW/aykLiXCa9q+bRrfZqbS
QQGS5IIll1aoFgLJo4DkNmFZolAhW98K1o4k7H2DjV9vsWoIn8aDTm2HRsxR7irL
xcxejTYU2QdZvgyq0adtbqJZ
=ffIn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '0a3dd146-b9a0-4b1a-a837-1cd27d230a85',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+NoCPqWzsVSKLa8G2okkHKSL+wWd3YcQAYiotnTZarDep
Lyz6zZAFazfU27J1PWgu97uKlbakYHdOSjLEC5g3EuxM5HMUjyX2tLAIKQD9lVau
nMcSRngR9dO44v0pdfUTe5AvC54RIkbnnVTK3+SGyYaAmBpnQYoTXSBz/ieeqcqr
6kH0OCDCicHMc50L8gMNFD9xvFW/MSbiFXcry18lukQRo/HgaAaf74k2oTtiAIng
65CI9O3unXaBJVJJNCGP3lltmPwlcIHZi4vrPMNnc8uyd1Y4pZCyJ+1fAZ2qPDHc
jSZOm8lRoPPw9iJz01lVfraohp5bHrqmCI1617lVNTwzL3cDNp9x9CG6fTALQxf9
2xfDbMP+yykQRS3ZlFVvSWwMyjFbIA/VkRbQ6VK/NQ0lhxjMrwPPhWGsRy6Wzvpq
JLB5qhjFJyoKYVQhaDooCrqi7KQOdf6X3pS7zYuFAjUAi1PrNnBvsTeyx0mQ+x0p
zg+1uP83m8MVwEQLKvN4TOlG7gtppSgHlV739wp6iZLJzrEL4VklNreU1yWFEHlq
N1xNigLmvwYd1FEwZr2Z6vUSg04Qw0zqFYTCtdXATzmeWslbDKI3Qa+/NbBw3x6V
lVleYqQHSeYBatAxXBIkdBF8rHUesWzIP4gWCP1mvNnXUuB0V5zfY8J915qj00HS
QQEDWFzAWtB/F5259fojQZIW7MUg5MyfdKPGrpku1+M0L285q61jOnVgaWLgZpke
IEF5Vo6g9Syg95gYqiBWeFjJ
=no9j
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => '0cbef303-5195-46ac-ab16-e708a2a49964',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/7B6t8Vb9Sj28673Pxmff9EuAkM/C2yHv3MNYX92ex4hia
+QrZ8OvJ54dLfV/ESTQb9OpAZnBhwwXyNuz8OADC7EG/NHjMydwteOtp3NoqorpH
/UhOSVgGuN5LmOPGOqb1LNeqNmLRCkwellsUQNouVmiT5OoVCOYmRFM0M4iNv2Wd
yyhPtMfFn/+4XLROJYOIeV5Rop4LbrULdGBcPDcXs8XLweKzlkrk8YmbzwwoL8wJ
q7dciAP7kcjvZL11+90LztWUJfGM1rFaFozQLP/s6aCtQEwBYCXLPqdOysLucS4X
6R6L0oSiZiyFsv/46zsJD2ApCisCr9YZv0wuw0Ipnx6F5XV381e0QRNw8iFMTxxp
Z46i7svrG7OMdcaoNVqlI9jXf5QJXPRUjuKeFB8MITQQr8AX2uA+KHKecsv8BUNT
2mVeNuXB+/wtnISeqt/kTywuZBcdg76ymNcIUIF5/ADlrdpGULDTDFOMGRRBqVCy
yey2HtvZO+IfZ24iRNiwElvaVBgDMvhda0XBn/jlxPE/xGZ7OF/Qg+7e/4p9x7eW
buBhnungWefqCZEuBJE5O3PKd5vXHjK48nCSsRmhPYhWO6CHuGQR4dRhO1qv6RIE
ZXIXN8cp4I3lu+yx718OOdt7mQKXmOcnaossXe7RPUdNNw2wnTQVTxGK2Z/GJOrS
QAHChF8exOC0nydvsmpIXdESibmHffFhanYv2s7t51lgPb3DUFLyELkubjG09+kB
weX2ZVc9XX/lL9fqiVpMjvk=
=4exz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '15bfa5c5-6063-4de6-a6d5-dbd31284449e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAitR32HGz5sy3vjj0SCGYGob/zDlcIZA1ttQCpdb0k32S
khEoLzkUrjF4C1x0uwUpGc9svgZZwbqxlzPLytJMso+A2d1uZioCu0rWfe+1SLF/
rEhQX4mpi3B6iwdE54ey+GuERukybr6BERFcpDAiLZVDwEMSdO3YuYPo5VELCtAd
AvbjT+/fRrClzUXi5zWMdxhf6uZC4nSk9L9hzReEPl7dk3uIanbv7WIEUvYUAjyZ
VOCZI1IYfWQtLi2EpWaddCEdg6RzNMT740KRB/35lglpeczJvpVgpsMLLOSg+NF2
0UPOZle+/VziZFz5R/dxN5H9+QFqmFvq5DJBtv2e0uftPJs3bUXuydkor/ZCRO6z
WDbvf7sFiQ/vwgKYuzkpiraFFp3bfSPaWoeyiqhIISW9HIzQ1JgRTVwJM27BU9dY
FH8ZLKbIlxo5mNlES6gpHu2cr5bIUGYTk7OnOPaSpVJ5YFSudBwG1BVx0YRM/Ewa
e1r/umDhys3azmgMqaMwG2wqu66bYGYzky66Dop74388zn/ozx8dXD+2oTxWLErG
xIg8rah6r8KN8hOTSUaFtqiIPsh5u7HwNXjp9TuAKg+R0S1auN1CSmPUCWBD2ciG
tm9i1PH4cJ1Sso/m4AbrgEoKBq27+lReHDDpeIAx9wDWh8LggWM63Cvxqcx2ZlHS
QAGAC+swULLfc65mS9nu90ymotww4J6xuuPHh6LYNlRH0YsvEq+ucDYhVHq621uk
LwNMwORVxenNriW28rzuuLo=
=6x81
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '19163349-4e33-432d-a917-41593ec25027',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+N8c8xH6Fy5w2KgNvSeE2thz8t951KcFAvCp4QJvLf406
jHRpI14AXcDeiZZeMCdPMQ6IRw1fNnFoy/dpLqtwQVPBBDaxYFHTx2i2nkINzyOy
JWELONHHADsxASDKWungMCPJ8mETL4IPvupaUfOIwGucT5e/PJ77iOxchVjBIwVu
vdYOjtOrb/2+MfKeby90LbEUlR/p6ttjpHYdg/JekTAUiH3thsvzT+Kfx7XIbaGU
O8fbLacMGgpVG2Sf+LweWtvs1nQlTPrdeVEZv3ex65+Fp52G8l3KBetJfG9vkJJ+
nQO5jhigIpqG2bktzKcz+1SBTSQYW4GoVTQWq1fUQJB6GlStLcO7shUGZAk6YLqS
FGaMU3jW8A/AIAvIrPVWQUebcefjID5ZXmmxoOzKefG/sC3PMqUwkkuWzEoWDKs5
wDkQTPfOa1uXnsQFVjJpFrCUrHYRUrVWKfjf9DWmfZ/wOYiNC77KJebPMFw8046L
FoTCapsaxjxqUyU8siohv5dCJdhbbQdlSG+hRBUpeSdtwzqNXS+KfuB4WTSW4ACE
zN3kQNFkO1qh2r3lzCjtxbXgUvEQi2OqTf1GsYYgK3UXiCXZSPBXzNOHwzCpPHlh
Z6zL+K0KlvDwb2/OCZyHBzPWRskEcc7KLhbO2xMiznJR3hpCTm05x74qbdZ5QrDS
QQFVo3IjB9PSI622Qv4jkjNgLCZtsI1pBQx2dBFIqW6dLs05yYYGP8tjxUH8fi7v
ZR4grD9GcO6ISAAsT/1sgOo+
=Zz60
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '1bde0eef-33c1-4090-a708-d32e227bf44e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Jea0Lt4DzNilaGg+Q52VRkn3bsq/Y2Z+f/u/+ftNibvV
smV/VGokZuMzeWlJ0u09jQb4+zmnAqgC9hFLVnmLPVtklaeA3ES994Y8DqrmulGr
kVY+Ka3OF9daRXeiDmVSgCQLTevi3HvEfuuwP2D6PryAHQDGWixfblh6XLj93Ni9
qSYAM6yW7zTS2+gDCrC11Xzaohc+J+NtmSA6pRptPEKDMQDbMiHdzLwyZs5v1YL0
BsGzOOzsk4Q9Etj+ktRMb3XQAB/DBRXkVTc1/AYRZV84+Err5S+PJpTmF9t3eniC
qiB7L/fnHPqRiP+tDbjJ80nk465UmJh7mA4Axj2FrnK4p9wumcmdX9crIHsfO+ws
fWmY0plvjabPpZL5CbYHDALDPQ61BqUzisi7AnmEjtiBRbiPKw5Qbu61ziBsXSQX
F72YcXSWuRroizH1PTtiXgJmZfs72pLiu6L0FSr+6BEQayrU7WUtic4kT9CXoBk1
oJ5hW+FRPl+rGnC7v8Ra2CkWG90aEr8/hs9DqlMXsRto7IdJYjH9iiCcPiTFXrWJ
hE/Avt7zIhqmQGwMAH+7AOZERY6HVR9ItW+LC/PBprtws8XALTT8da1QFNnpCZJO
eD4Vgs75VpepScXpWtrW8xgvgIatUZRguBMUpNmyHl4AXNngTf2lQhZwLqg67gHS
QQFjcPxVQPWWDjrVPXe7cD8GyrcGFphVZKIAXhqtsrbwUZk6lMwuMzMcA2ld52BN
aZ8yGChqRbeN1hxvkLLzG6/V
=lYnh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => '26df3219-dab4-4be5-a7ce-764dd445d09b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//apQB1Co0QYr3dNHNFMnJB0m9pakRZfaVvxDMJwWQ9vWt
lpISFE4K7yYEm7SVmm8AsBKHgggBVQDpvptXhHVLEMf4mf22GLC3hLnlFSZDxXhZ
P4NiWBUFTUOonocAMsmidmsJvqtbF5hNMRV69GsRdikIWN12H4V0FHxAv3dcfV13
UsHdAqLSiYLH1aqKasfRbOFM1gwE0d9xVbCTc9UMsQy1j+7alfOWpoGrBwZQK73A
fDuucEchhTPdVsem+RtXGUicQrBFuUoew9t1v/I4PUS7oKPm7J9r4wXYOTi1Yo47
eq964C2nGzBDq7ijJLsNsDedCBZ7h6DmhA2oH/wQfItP6GByBK/G/wXyJyq9StUo
UAFlmvfo/0eqo8U09zkpsCJ+LDqGkuiF6rvTuf8joSHIiZd2V/Hs10+i2SAbvP1m
/BgDi1pqkEXfSDocx2q4s6jOacesDVgwIJf4Vc0o2ZL/VOmcEsotEqlJesqZfYrA
cc8WCOYBK0yP0dbROyGh7z/k1UY7TVPivY6LeMoLoiSoQTUCrhfzfPRDS2sxvuDi
tbslXy4SPFx9CRLtAMbXi38uZaLRqj2HsR4eeDFSAIfrK/g3ISOnctl/ZpDtFoxq
lyePXws4RfrK4VTsc22elXom4TesUVlMVWo5YLxCEa/daN3sfbC0Iz9iiDv2LqLS
QQFPM/LHevEQUAbFVYzMSQ8SAct4eb/4s3fnqVaogfR+A8vmmcrFD4hPajyWnuNr
PuZHfK6JzGZWQcQBTJ5UG10c
=KSjz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '276cf969-1d82-4a20-a97b-c4190bad5f00',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAo18zJB/9vfyweSy0Iggdsz0a01E1A+N0KpuOL/DHIltI
eE/MMlJZjCSgcuy3UNPVet6M8E8AmnnNH8RT/IKxBWSzPLfpE+BiLQXob9cw+JKk
bQA0MT7d/xVsuebD3Gcsh0zw4sl83mqpYzEb3PHjqbsL6JqQWwg8tRXKJTFT02ro
RrG7R6xCDJQhjFeO7h2POKOTXxSMt+O00KV3yTgDvtYjSYxlHqjpH97Qu3xH7ct+
4kymbBG1qgXumb8QT3t/rl8hnAnkUlrD9i3O7qNY244Y1LJe6o7IH04eAY1We/Yv
GBt0gGIT38WG9FIqAxK6a7wg4Z67zEvBrtYPxGVTDQKVuuFIPMCB5tQjZh+oGk/h
p19Av1Nbd5u6XOO0gBm2H/xUZxzzLuyLBRmh23uUwIV1Q6C7ccbpoDeU4d7RMGpU
Omhc5VtkgwEk5YqbyDJU8XD27gfqbDMEkHvWecv/kBlSBhyOXiKBfpqFqw5/3ivO
TuuvQM835i51b6rl9D2qOanadpKncD2xeL3z24fmlB3zYB6AhmLkE/DzIt9Hxxbi
oDtYtT4RVYQ6bHnEEV/X+s1SdlcBzRBls96YJVVOzT3e3b2wrx/OEpbogXqg7ZAi
w07qC/xSFOcVWBqqZRhuiFLhEFXYA82lBzYRg2PyG1hhwCUuYSI2Q6o2om78I5/S
QQHZjTOmiZx4B9ghg/Gbc1tpLRY6sVaiLsOE/B3lS9ktVggiFiohYh6lvgHyxde8
lXpW/fxhldFQHPLW7t0vb/dQ
=j/ZR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
		array(
			'id' => '32a41174-5274-4563-a4bf-c5369de0c75a',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAj6gHs5vPUQBkU+1WTS1o0ZLcQgpIeSDt57Cmm12NmsjR
WyOrkSwtkq8c0BdmZAqBmUBBgAs8T2O1Ux5uOmV0jWdS50mh43axUYl4/2aTucl7
QTRRtQfudhEm5H70U6r2SNYH4xQL9dJOlcE9JOnrELjofbcfe2h5Yria1OLmBLiO
sUNUPAB54JTBRw/OSopo7unLyCXYVCV1TzJeqH5CYP6IZ8r/S6XWDHH/6kosW3dz
YDom9lrfAaKe9C1PQlFZOogh0mfWjXXY/Gd7Jc/igdySE9O840BQ3ZCBGAUXJ1T2
sxynflJFeQQ4zYHBtTDZkPaT5BZuJQU/m/Snj2NI4NJBAR9IDwfkfoGf4V5kk0jo
YKYUQwVcDz4tn++WCv4KPPEckR16KFcXbmhm5NtXeEycEGwQcHiwB6GW7fIAT4iQ
ZSM=
=v83t
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '3370fabe-5e8a-477f-ad1d-3438643ad727',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//dME7KjPCiLthMQ8bBk6oEIAXFH1oP7bY8nbncwvK8zlP
4QtQ0a54vGJVluSjPKOLFVIhe3Clx1PoKB+9lQD8fMwzdnYRAi/MGk6IcszOuDHp
TLh0Y/4h7FgTdV8XiF/4C0nrnzZygWRfg/6y+sCyqxwT+w/VH46eRP05Kb8T2dRB
fhv2Ms/VlxA80IlXug9zsdbYpUxk3DSVULlHX8JufcwHonpctm+oRhkMyzHPicNl
U8TA6t55+FMhWcx5zJpPpoPoziJ/wNlTO0ayQCHhZFyoA2nIX2DPnAP3XP5iR7DB
FFDLWZAvMPUt2vPjHI5tvBsqkh3BUEmgQhHhkyo/Gyzq289u1lC8Y0fPrdPLqK+c
cbrk3MCEY2VIWvCav1mSAzmO9/9dqPuMG/9q05gtcfDAe4/V05optxvRvnfVG7br
zb21Tj1dVgCguiVwbme+dJFsLNMU4rL8pohnwlPQ+RetC2S/TTiS2JCdela1Or/8
pNpMKXo2hMfkRrTCm+qMi+OMwapd+4H+Q8y9alT+OMpvipV3nei3khUalyILuO87
Csghgnw9Pzt1OOz0BJHoWdPaO2aV/UvwhfrQ0YmODBJIoRd961GmgO/sXWUEuGz3
8VotrnTtMyUxAFJHR1VHTWkMmzARr1+SlwknHnPn0Vl0yufNn1sVAVhkXdjyrJjS
QwFdWP5O0x/WuJ4RQIZUvsXuTAnaZtyB7+BqBAQ1yLt6LrnJkblm9nowhg/H6LX3
K8yga18vGFcUv3GEEsERXXJgmgY=
=6lIx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => '38fe67bb-10ca-400a-a7e9-c57a53ddd563',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+LxbHx0LBtrhjQi1B/m80qfgST2dMkxQku0HF+GxSAbdS
GFJjJi3GazMxfFLh3ba//Qr/KvBSYCOxcMhCFJ2PMumi4Ly58CxFLnzWBRLrpNaq
rGq6dQF2mgZKQcFTkqXzZ6Kk2nsEz0g7tc8jt4bw90XDA+pcCJBteusqzjV0Xd/k
lQSQc/Gt1ug6YOEWdFAAhKz3bCvI6Q8+Syd7HTwkeQZYBh+4HOmT8RAtAiocAoxj
W2hSVUaBNsRuNg8Itu0Ipoo6vaRyScAH7ADXHYmDer1iMu7gEflMM9DAVjcBsAy8
uz1H+VggivRn2DZOCE5o6ZN/A9FjI/Jxk7364yaX/Wc2Pjk2b4pniYSqPviJdQCp
rwFPxqk2Yii2Paf5DBmvKToxaS0f4llRQJSGfYSmUBYuaL0xctnJcOKJ/RVub5s9
+ILIxplJPQjRjm/j1Jc/9+nSycD5kC45uNEH++EtIYBPRiWyV406KUHN3qK6yfZ8
YTZvqKXU1SSn9KiGzcoUKBgwBBdGQAf/i64y68aWyrySsDVCygXRPzQAN0tmerqX
RNA1SeGtyFGUf4wGSMKJ+MINFF6L1ICNmziHDXp4d4DGQKgWpyaugldHss4D0ldK
Q3bn+t8Bh9ToNFn+DBLnPoiNvfr69TdYGOV2N3hWlXknWoPjdNVYu6j/YslQMs3S
QQHuV6UEL0Nm/W44a0UEfDPhzLyerG7smKR/+jBJVZZ8zZf/M1hupD9BFFWJOR9r
paqcLWi5YxCtDnsWAAj5Go7v
=R6/u
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '3fe8d3b7-4fd2-42dd-a9e0-264233339e2b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAm8IbJsfrXwgjhU8YESH4vsQFufgfOzD0eVoqULGGLO1j
JnnTWAf7Ya2V2PKajqGEZ9ZjRvgdi7NVud05sGbBF07ofVJpYRLayR1w2x6Tn684
ztmB+Yb7fTkjiy+7ks3ktKNyaypFlHsOSWC71s0gVL6+TuN1Yo0gfMvfn2JyH+dP
JAdNZrWE8mmIZQWeQyI5ruleYiLjMfDNRYjDj0ls5CJ+W6axiVVMTZiyJmQZjnKC
218rXAvmEUDvtz6KpgAhwBdIh5EEP+SMqgzzzi5SJVUlY53P5nLjDHo+nUzHZY3O
LB+EL701VenBRe4A9qdePwYog1LhMacLfiZEd7yRbD2FpN53jwzSeK8ibPfYbw46
Xm1DvUTsKQUlBsLAN/9zdDDI4LPRikWPfSGDk2vWjdpBJHJ/msMzKCbcestFotXo
GtwXG42PmjI0ye7kQKjRbZP0d/YFL6/ELWpeLfw+zIA4GpZXt08P8CfTr9OUoGrP
1IPNUwBtwafJkEMVxqSxxlVDxSUmAdw+vBq1umH10HJzVGEiD5/xso7C9vGhfdoN
pyHNVm6yZ762VK2ivoRAT4ZgEONkIUx6QojQmA0avyBLWwUx8Vlx2ZlIgKR1C/Sz
dpO5VaQWUdn2kGEX+Q74+P4lzFKRp3z1QQxpadhabGjgmSfLS/itnlLsaq+Wz1TS
QgG1mYVt7JUQnM/hZvL/QOG/wrVKkWhSyrd+FgtszQxo4ozRI/a530zs+1Hkjy9l
YTZWsRmurSd0lwLQ6ESaR10OFQ==
=AX8F
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '404d5ecf-c304-4d7a-a9b2-cfe17dfad2c1',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/Q1cUtNg1DDgxY9BbdqdrqVFHx/m31CRGjdOa1uqIQ56n
0TxIawjU/S4cGnziud/G6Ing9chi1o2MKC5jncHcwxsuhTexXNwIFS26cF7Y6XQi
ixLBUegInL0sYifMYRIkxm4R/Gq74jGFv5CL8Xbw8xZ0k0e3Iw8dF1QrhACf7O60
Sfy2954iCW9l+ILEIpmjhY9Y6gZl77nc2utxt4lXSVM6AEHMlkkhwVjndBhn44n3
C3kw/0JL5HS4UkRQLe4S5Uu3P6k5yg8/HvBFhAD6gSnXljce7/gBeNJrRWZXamPd
UZD7aUxlTCF4sReY3UhXGbInUG0Jqx/Thy+l7cRLstJDAclbBcsIWmfTEKe1LXl+
Uw8spaiAIpY0VF6viF2sF6+PHtD35KOuzL8GfxPJT3aEP401pnsllHrfzVYZ6Zrh
Nh7g/A==
=R2XB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '4a2375b5-768c-4892-a216-18c620dada20',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//QAjf4x7eH3nsjoyfmez4T6u0F740y97yqB2//SEyg2kN
L0iqxjucerAYWVEvo6Fgyd0X04LgWXcRL2Bs9leJii4TQ6qvMrCxho81/1ymvnDZ
F8JlTI+u9+WHC+9uL1CDogc6OFnQLPp2zYkN8gXz8ommAaAtdqUrx00nVmBu+k0e
QQknsQGFNe3AVja8RX7KP21TZHmAl/IgsRcfUs8jjYAmOuaBXkYdClvoaoWjpMGt
XQ+CyvJUuLTRz7ZZMUzCKl2cZk5SLvORWZGBbq5w1IYFkYbDUdfhfnAvkFS/NrsO
rmn2hnaCN4Pti8rHPNlkMeOSIN5zlnwVKZnuu4fCX040GetUDFBu3Eks3r2pB1UR
jFz6HNqzd9ojg3GX9bLIjZuWNkWJDmj7PhK2y2E3wyZRtz2KLPkBBjeuru0DC+yV
+ID0vLQdQlc1nFKktPva1w0OgAG61ksGPsFuuNQePHe4i3zj+IzSGkkDtN/Ce/GR
o4Ahh0vcRrnSOMHwdjuqZ5m2PkJP43wnOcBwAIR52iKs7KlkTF6Cf9zkM33lpAvq
bqc8Bp6bCy6htYmaVIRNaweYKRFkhzlxwGQTyf5oUuFTubpAMKEcetM4VD4fLMBs
hUOahCLpoiGmqfS3K1eV9AIL9grwq7L7VPuzff/w7vmLHc9LgtIn/fW8UoOzCinS
QwFD5d897KGOW22RWnG9fkZztnKvhD/8MM4d/UOZbv9vPcqFU9TV9j76qqXuXg/W
1hi39/oma9jpQ5zP25qQmvy3EU4=
=0DLL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '4ffe1e8d-2d1a-402f-aea2-c24ae871c5d8',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+K48qlFi7v29GwDDMZxDFHU5DsawWtNnRXKpOCP65BtiN
CpaMCQpOSPsJpcdMj9Fdk7UXLFQo+D1yRbZ27X7RgBtpFSyBMc6VAD4yLw9htM/u
GOnCAbM5Pm7F4OPhO+lDCmLJ9VNOQt7JUiP0OSAUoaMvU1i0bjbLxiOrCVVPFLp+
1OmTlBpZ3EmrqhSLik7RvTJhRiUwXghKVmFXykt1qviN7vDjCGN+HQXl1YzluXkJ
rxryBO41vvtuQ/MZtIDlDZoQlbqOg9EJgt2CZ+vlRwNTdhgPXBCKki+hlEUIgbRC
11B1kG6yCOVI/2P6GaQgZAxW7P4HU3qLA007i1bj9dI9AXPpiH1EPYRCTGqcER6C
7vRdLQS1qrgqxdyb5tN6l6ySFWsB/lXqnFgwpoJuZPXhdQhr84yZyxw6f16YAg==
=95Qv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '5117de35-5b73-4d7c-af61-cd7ab90c8fcf',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/ZWN0fhFNVHvaAuB0knLAIvIHuejg4XqhVGq89xcjeV44
njuWkl6DvjeTJd7hOBkCtfaGk4bLqwVCw6DS4pdsAA7dEVos0trvRzjiMQinUpsn
vou33Klcb7rJILixbTkiK83HlsQzPP/G+nzjAsqLuTfI9BIpFeDr+WMSj/wul1ge
u2TGAk1eK9n4bExn1zq7J4OmLN3Je7u5478w9tWmv6Y5vF8hz2RA7IFrtz8B3NGs
laeDu2wSoQxmn0xLRKlQ2IabEVsbMIKCt5ZqeD/ND5h0neVyVDPweJnzkK0tc9dH
bw2mC6PfTuHmNb3CYINWnDicez0Z7yN911LjgNqrIdJBATzRP4wDwrX4x9hpXgw2
urTQEX0LcYDs919207uLYCiYPuhavN3M2CdVPNnlmGEr9kS7wPhmnprkjRVVx+Lh
yxI=
=s18m
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '5616eca7-0236-4007-a69d-9e8173956f76',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAhOsSUYpKczsrhrrUQE0WusrnGbGtK6MEVnJyuT3blkAG
VHTdjNeefph/yMsuICxQAGHzo1S5Sjj0QPbwsIsZmKTTTF2gctoaot6ZyBNE1Wvd
6iWdFoP5EaNmPILWc/Rm7oMYi7bWKtmSy6YXrgLUK+AZHmr/TIvaGNznEbTgN1+4
8i13ED9WkYiFlE+pHzf+bm/Xu2hZjQznnN/VPOHCDeqLAFNIH8wxmhyFmL0Y25lO
oIz4uHR124971vtEpnAHlra1l7h8MmUoeBDlHEXJXqZnScJNbD9u+axktf0nTqFf
wkgdgNzT/ICT2Tqe1JN5FQyutCEKgLN6T403/0Cg9jSZCDv0vWMgK/g3IsL5JKE7
msxc1OPYNcBs60kTGcVsRjCNZvIkGzIV1ZBlrKFlQNHkylJVJHjDKZTV6KTQwPAN
V6vUVwao9nbecb/Bdfab5Y6jpeswf3+9FJG0Tt2ljP74azAtSw1CAsOJhPGjUmzF
G1MMgxT8kpBkU46jxZQbYXLdckwOAQkTnSLzRqHNVOQ5WjhffuInXGLSGI8REvop
4vvV4fiw3cpROMnXiumE6SgfdrmwmS7bj4AlYyIFUOsfZut09Uk9CkwYoeL3tTxP
8sVQqcMFUyk3FrwUz4RYL+xKoMBITvL2EwMWhaur4XaB6+P4LPxm+waECN6103vS
QQET/+H8xPBvwqk1mjTw0D3ciktStzP68lFN7v+R2915VppY7ltpZLU63w6e7Wml
FkwjTlt24QyL9t4/5FztTyJ0
=bQbz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '5d6bdfd7-be77-4177-a5eb-f1fa408c7915',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAk/GncaPj+48YFnrnO8+aBjrwHqbhEdGMdSAu6ULsNYEr
6+vJPlZdPOvVcuUBW6wR7RYSlvHkuneLasBfB5mKU/VPBoR34tqn3QwI/m7A9HyO
WfpeT1DfCW7asVFW2g0Td9q3YsB/4RQSHtGCX5++vyy5oCR0PBHrcZJ3tyITA2cK
Mc7Ez8NS4JN4q9OLSoQlnoKPdWUn+7Y00OhAdC8hDNJ+45tq9QpwwbwzRzm3XVIt
tK2/TbDkRKorp4rjy5DVEsrvYF4glvii2AiaWxOCkrhAEfkPcVqRtf9k7g+PZahp
9/ssvGPS/6Jj2DLEScPOkZRH5n2LuqY31+tQS7LCGvT+k19gJ6WwdmkpfECebxR3
EIsJPUsgjpAFpxe6Uqdcw6lc0R9rLOzOS030Dhq+nrpNPrnSfhGoHumbhOv9u8bM
65wMqiiBccQTZvaNfxUhcnzq5V3Cbl7EB/gQ+ZBgY0OxtSd8ONDqdCpWQaf1MBOL
WCohda53Hz4UcIj2TzLZoyu8Z+J7AQ7RHOBzBYuQF8owyWlNRcxsCtUmIs9y9Hh6
Y/y3n4T92lqL4/nnD8U+ireJeJtD2Ds9k7WC8iez9RHe/E8SREb81MAvzrWg79Ym
gtusFghZdx1fgRD+V1eZquwqn/IdPvVKkWP6g9fXQmzKlCpSvDnrhXiuBtIMM6rS
QQFEKWXqrUhiOjGrswMEPIwGj/cvdHzScx94f9bMSVD7YszJyf2c5CelzqHF9pn2
PTotWjhwC7Ua1SyDupNklEjh
=yheZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03'
		),
		array(
			'id' => '64eefb04-7da7-4f43-a45d-fb539d126b9e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAnfWcoPx9Wfxa3tYACTj+kZRLlwKK9r6sWg+BDMlU8k9M
nSgGXlWe84RAoPlnkwANAVuFmFNxujNxNmUUGv8amToxqZpshwOdMe1LFySKoc2G
/DMSseXGdcrzm5xnEjfc+gFBfyeGpGwJSptZuBPjjeKuZNxCXCf8QVFyVHMFN7JO
IsN92QVD22LZkgqtl7kAfX83T0A9jCl8dEJw5lrp6zl9yBpC4vQ9FechBVj+STX3
OiOL5dW8z0esu/oBgmBCvbkmLuqhEyP/TmEplIpTzVPHGkSQ4vQzVaXtjFBgilBY
eIdNmjF4YB59JMCTzrNkWNFRRhJv1eRZJiHlse9gFJD6OQAuTZ1QFvFgHCv7nNZJ
AiTBGSv1e8V5yJE2DvQnz354I0O1IFdG0AWGAUyBLy52zworZwTYnby7DZtXQlCf
I41Dailg/ab1O4ADovFhR+J/oEq2c0mvOictOKISNcZ/CX6Hj/C28GNpMINp1p4j
YSqzMqoORe+iHIwLHr/K959OYJD/K9bKu7SbKi1JGX99An8iyWGNu4PU22/atWyp
bCVc5kEGhDpH0WiGGAA3v3Ceex9SzEZmQbUt1sLM5l/Oz5aWn7R3xtJS1CiBuxLq
+L2iP1tm01wvTIT4I6V/AQExDs4Jg/qND3oYQyPGgoWU8HiXQjv9stulsGzbp8nS
QQERSGr/BvCeo5U1B5t2GW2/WqqBfSDPgGrEJ9YZQXbKgQb4RLjfHgU2L8Z4BzEu
E6GAVlttR+d+hP4CuAm2DQYm
=0KIf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '659ab26a-464e-4c0a-a7ac-93dfd2d59e24',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA18SBjGfLFEruJXslQXKiG7Qxh5H/vY4VXyroBpgXvUwT
/npMEvXrulJenFpT4ji1HZcdqGk8YoiLALgiL/Y+vpcrrxG8P5P7tlCEx+o693KH
bSZ/2OLH6zuf394gg3HaGhCDdMbNvaov+oc0ef2aQWLCbs3h5L/v/j8lvfp0vjxs
wAXHlvmVEV4DmCUDztjC5SLMnBqUs+zHR5mVB5uacLihxVki1NwILIhv/hL6tOcH
mvb6HUbWDQRTo3MJNAXo7sLTykigF7qPqfAEIZ0KWX5J92ch92UxRPOn+3kVis2y
IjfZiAvBNGDjPSOsDwFQzVEyQ17ae6zKOUm3yf2QL4hrgtEFH9MVadhFyCIEkiqI
/GpOVD/zjvtmGqn0LhgII01tpJJTbDyb9/0Lxer00y3YpfE2yAC/gqd5xBE0cyuE
q3GDzVuHg2BlGMO0py1/2CUxc8RPoZ00BuK1jBtf0PB3qZYQVofZYzWtDzhgH5cO
IEZPHtXAmybtgwsf4GyCKl1GdEOe+JV/1BvLIDGQVj0fMN9/Ww7p264lVjKCxrTM
dc6ee0owLmHc/0OHmftGf+RrWvycjiOtjRHYZ/DhvkLk9QV12uMBqzvMhWPA1aB5
Md3wxNGmFgOdr3SL2KQQU38b5O7MPD8eVCF4ZHbIxVqyc2Ic2xwAq3lwSGl2vmHS
QQEXmqCKH1ySvCegFg1Llm7iUr87QBXs+3xH7QEG4PU0hjRiBjNIswy0jasid+RG
HY9jKQnb5/bfBYKNdfdYA9py
=z/Oz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '66ff62b6-2b79-4014-ace4-352c5b047c50',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAle1gkhVegc2w3DLXM1y8nj8DuBcvOP5RnAl1QVtVwCzi
gdlr97JaTt5paUwSeZ2lscWj+xFojqwLXxcfrCMgH4910wgUQMdtcWpm/Wvw6lnA
eTkVjrQiJBdMX268Vb9wrUfuS+Pkv4vZHLC6bIL0tkRBOX3AVMjrD7IbxDksVVzu
fG6V657Ppq9DYzOldhuIjKI3oyZM5rSsJiQRFS1qWN4obZNNWzbzv9wZP8roSDH1
gHFrOcdatehLLq5zCah35ihOY4/ptYjZRe5Fba0hS9WQyIfs8nSo1g8TrRgD0ZG4
+rLw+nfgU7DosiKcQ3miZPFKigrp7A46q9gR567cMSu90sHNwrA7LD1ctfGzlcu9
SYJ6BJQAX6GH+brB2O56b/RPDR28TZLaXF09QRiwT16H3Eh7CzauxtV/goi5ehg8
wEAbJg3ps6152Bvt3sxRxPINtVeLKvDdbkAeUFqmnPx/f2PBgkUnuCydoWPt44VX
Vt2QvlbaidaxyT6VHr1wuYLn2K4vZ/NCbnQFhTz2p/3UC2ro8zOBqtdhEmTTMwbR
gPqhbWKwB1cRsXq9WFPbfsmeQfHJB2iIKyGg5OM2D50fSRwIXxceNCQ57JOxw/sS
aW2qEJ4j8FL/mjJTGpvKk+mgTU4mWcYLZY6KkcrwAagwAGWGsmJZQYPk8UxmFw7S
QQHapNLHeTtHiQdZNQwaDScB0no7wU3xuHmj6s5qjon8v3UEioPC70Pd3wUvmHia
hVXQ5mRK++Xdq8sCT+z5jgwA
=SKgG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '6ce1b429-1dc9-4430-a4c5-136d70570ffb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//SDVp89ddSP4y5z12NrlLcyPpj4ZZ5cSYJKNcGK8GFsh5
AXwXEshPDfupWQS9U1tR11etwT7FXkoNjrGgu8xthiHapuUq3JMdxKwCCZxySvpc
xd4VkohZzyUxLOmTO12B8Y6E+BuoCHK1htfkkpK8AzEr44RhiwmnzQNgIK+lXAph
PdS93aEWqSBLVGj4mvC8WkXJBg5uHzW9R0UGRCynBSO46HdAt6EXE7OWZsV/ST/M
fSNK66hKS8/8AGwQijbUkJqyA3nyTq8bOeKTUgMTgvZLeEsMuPpDalvs9xSzeref
pTVczSQ/p21NEXI0oQCkvm5Y6ZL9KDU4eevvh0l69Hv243CoqKkkg5uEls/wO5iz
YobVQsSLof8kH7JQRoOKkhYIO3Gj70BhQM7crCs8hSv3Do+YhpSm0YA/GdULIA5+
WIpW3e8VK6cT0FApm1hcbgcFqUuiB4cWKW0Y86845ouS/yZuBEroQbXoqex3fyun
YUvnH+lcL0thlF0UXp8vXGw4m8miHbs5/jOlob2Fgr4t/r5IaTNyzoBIOlxwU+93
XrO5k4b3LBZ5e1FlJvvPjyedHCy8qVZbkqjtqeJSzQChMh/OMoE0+XMlc7aS0XZC
mttQCTuSzaOsOVnQ1KuHC1/rLgL3EbwqFGJhij5Hk/svrMdA5Aifg2yTZKFnNXHS
QwFh7hu37GrEdFPApy5/Ew8C39Fy72+kYJgxKh8awZ4RFfmvzpsotEFvcsmWuXXP
FICWxHE1CkHSW5FP7NZu5njDy6g=
=xbi0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '6fe302bd-9acc-4ef3-ae14-aa66fd412408',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/6A4selt6HnNtbmiZTyLhTG9lwTWht8IUNRFpEh+9DhMP7
ZMmTpsBCh4QW/S9vHeMMJPEszxmmg4ClcUDtyNspgrPQNdbg+yQZZ6A3V3e8mt2q
QijwZW+soL3cZvgTbmGcfdipypX8djUg3Ce/G3jJ/uZ8KPE93LU1O7mZXbRAgqeG
UVsJXbEcJFRqI23uVjiUNnficaONrKx/kUrG4K07/42psaXElS2C/2wwwVU6Ab4M
fm9a2SWFOlkZvPr1VCcTeYU2ypfyV9Z7a6zoxsRgMVoNNZw9PSj0nYULI9KVSrYF
s33MZDgsBsUB5aKCsrXvGeg7f0r9pnWrYJzpjGwiduVhrnXwTBCNpZF+e0qYdmjC
sdO46bBtC3wuS8I6OBIY9Q8eD8Cpoe3yy+v3P7WuwOOsi78iYojAfGUYgUm0Uw3v
D2IUKvCSQ3zZFfbGv+ZoEajGCw5RnLZyHf6FAJKZ3TJOy8XedoHgdlohnMJJshtx
UQrjcBVDfOPyfunUsFbaBFmOqsm0WcfFwsBvNk4DjPnGbgSMiGfuzxs+p45Tu0nH
ZhNkbJFrHKAETfs/Smsl5qMDWbHvIA/2J/vs1WWALGd9fBYCVhpZe1+2qBcryxv1
Dngvkak09uwriTiCFVTtY6TaVwFT2Op4bjbuW/hELumqrit57UjmFpjCqyiCEy7S
PQFkvIoWQKtPKeUflB4BiGOPirBYm3D0n3h1BPhPhSqBX7LJt/67qq/jnxsXiGYh
7rAbCXiUJrddWv4g+2s=
=jyir
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
		array(
			'id' => '7227e626-c8e9-4417-acda-c3247d0b3644',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RGSDBBySPaGtCKCL6pQGL+st0axC9EHHtCXjvh7Y35GJ
GxUCA86BlgRMaFtwbaprvIC+Df/h4FNKop8ng/anUmlvTLvE84Gz+z5yUBBGRt2X
FxwKHE4RmHvIaSEQLiJTsH74CbmslQCDmrZ1ZYit5v1Ubrdj12e48K6xMDuAyvNM
ydBslqBc7+Ztjby9CQ6V5cwIBgNJ0joUZTKERYxDaOVg94ZNZZprkc2I74Oozrmg
ULuyOGcMSmHibEf9YK0iEZ8LJ9nOjsIrT7bwlpRtOb2AqT9dYnC/IAPqFTcLmH6W
U9EMJCrQ7cqQXFuMXlPPQdv4h/KmmDKmNZ4tnGY7M5m3JEB4tpgs09Fsj//blBai
v9uZeJj0q3Utc3aBEvlL2ixYDi4ljJJsvnW8kqOe/IciXx2sk390Zr8NQ83AokQR
4HzUELR9ncKSSrk4JZb7LIV679qi4PDzV3/fE+/XPE5MRSIgsJqCpAl2ncrGUt7O
sd1fwgQ+mYGOQH+YTuWbIIjfDtpzdZLjiKLENYigFnlyirQxIcd8GqX7RsLSZLSo
7+90Bah+PzP4DfWLWARAcJXtA8+/+rXMKBAUzE48PsuVwb6L4gpkn3TXRoTD2PJg
B9DykEiYjDUH5S3gzMcTNuqDPVVopGSDE0q8Bkn4Eoaw7U6wEzZo1IK4XnPOPCnS
QQEkp/+tvccS/TN30R8fPZfEzO3BZ2rs6G1+bNZggvGD9Rd3O5CaaxkF5zrX0HB4
vHtVvQDuphSXuHeI5z7UR6tX
=3wXL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => '74c43e6a-42ed-4928-a9af-a2dab4f36a32',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//atjFYjN/8nIdtGbYCpSzmLlDCtZ98RQbZf+eicrwZTBA
OlMC/52cEj0fxdbfkWfN0ZWVS7hnrv/bnvZztc5q2BJU4CdEtsKhRdMSRmyHtD9D
Szad65tWOYbBmptlFacvLYWRhq/RI8HQtA2yT86EYd/3uCrIHAc20O/KU/ldXqmi
kTFkgLcMLuy2kr8blHioj7yqD8K+Uv9wk4Em4Qz13WR9/2+mdkDtgJl9HTtIhwc4
9xBVPlUXA5/c7cpkaPR7omD/8DE+e2i0NLXiXKADEFjRg8y3cYRTUYXVyevS89TX
yiFKPFkoFQxBk46rRvt1v9XNXoxxqtMpgRyN4EcOjHRNILwLXXJnzimtzFaqWXB2
BN7Vmc+swctp//ElnsoT8v0BrWVu1QXDJ73/+7wHXlYSeQ0ZBf8WRDu9SnSgy6w4
PSuj3iXsrz4tqtWYVRJMnO+UtS/2BoZcXXk4+BcK02QMzVMOaXQ6rqXlaTfBivJF
5Nsp/0Hcyb+Xryy978WqNtNL7xlcf02r3fk2P8wqVdS5bMjA45lRgnKyJcV1Rd+s
O4aAALGreuxLrZpW8CfZOfS41NTzC/uPPe/0ykV4tCcehVgC7W4cGBlpkPZ5NwKG
AeThVK6eWb+Z1RPU48DDsFxziUj/+3nOKfAMfXghEdoAxrNCDz+WIK0Xy0p0f5jS
QgFaK/xzf98gNMiwUP4IrtY7JzbCP34WGJ+xlNFx2L5HR4/4B9wPYb3ocnGvnIfk
CDIkIH7yWRc2Dvubp2C9ym3QEg==
=whX6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => '75996bc5-d4ec-48e5-aea4-dce535f60972',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAnx5NlEfBNntsfR61k1nbO6cOdfC1JnmCoi0bG6EO0dEs
iwbe7zcS+B7+roQ0DyHOgdqHsZLyAdLZhX+r8x6HKJpuAU0QWQ2YGbq6YVNINDh3
tWZJ5WezWeoUbl2u9OIENDqtZN3YjCM1zUXsQNLFY96vTixTKw99kBtTaS8WkHWf
qaSza5twsWcmooM/avb7uWo7wTxUY1Qs8af3pl6ynMid2qkFxHfByO5EduowrUWd
NDwgN3qPcBAfr229JXIqAhESoUigv8hIg4IY7Z9f39GN3r+NuF4AhQE6NuNbi+MV
gD76onUAgcQAihaIfcUQaGEPGv90WcXfbgfH/+ngudYomw+p6CVOQjwMzRzxVBig
/Us9BWM5YZt9bw9xNoDZo0TMEHesRhaBuWboxr6HsocdqRR8oz2q2vntPIZt/C6j
H/SautQ2hKFK0r9jzBa4D+mPvqPYg+qyX0SrASd+yPwP/NeuGNkFhgBvCHLPw1aQ
O5MOi8c7uBtLGTx96HNPJ7YKm18VAbcm3qaFWRWJFl9HS3bWaR6idlpwqukXUipZ
OQC+TIYkh+YNe/jvKlAflReLsABoRZLCL4RmpUzOQcl/IzkhYC1DRIUW+WTSE6Tq
0FWuvwOsCI8y1yNTepuK5OnW+vVOibwVVMIXIrLphsJDcJD7WkVBTxvhLvH0RFHS
QwHAqYNpc+gcmHjIeSPQA6Tl9vZSalItbp5mphR8MinfpnlC/MU/ZKgsIhtOQrjR
PSLnPICSAWQg/VT7LK54j6EgdpM=
=qg0g
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '7851c097-2628-49a0-a6c8-e47cdb151259',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+PEUiyAL9pHQt9mR+pW9HhschxMCwSj80JecLusxnjIWu
AKdj/ClfU151FrXC3i/lGGogAg7zG2aOCof8fAFva4wRg7bxyGKdGKudl5L43X7x
pZzT5z1qZ/aZWix5fcC8J3vc+m83hhdb4Y/tlIYjxy2nAO+mFaOhUNCAZwan+oSg
mi4pOxWGGDK+PKJVv7/haUDcZoPU+YzA2ONcsEagVRwAGB/tjztwxLzGx7ishM5Z
6GyvsLGNUKZxIjxP8ZriZDvkI3/6E7v5uWwVDY0Uz91glCTMWlzkH/MHOpr/dZRc
3sBW+wjPsSPcfazXGTZXK70B/HcKMKkvrzcXPg7xoaJAwymdncKihU+CaaZ38GJX
VGZjMDo3cNtSniCJ4nSSk/epIyhhcI1scwUCJCy2P8Z8hRa6TSqE9+vCcaIaeTkj
okMnEPYPGknM0c+jIfCrpYsBuVijnfh4N3OffVP/v479GYx650uvKm7WuPRIGCs9
hTkin6/2GPrBM4RdKB6UV3q/YULaqgHpgDI+fDj863hppCEEjYAHKZWjMwVz42K3
cG3JWZzggzj1ljyOgLIfZwdZWnq8B7RZK3OfMQWB/4Ors0ULPON7lhtx4lawXVYx
la38VqPseOF6Zokv1biDBMdT+s491gODqEwG/KR4Vrs6wGQlg7iH41W+gZgSnk3S
QwEFUwmIfxjd+u/5dRTfbOEkFAVdJvMzsYsADa+O7l2hdeE9iR6Klt62l3ZEOOAS
wR6r+IMc4tUHFqFLuURJSi6kDNg=
=Fe14
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '7b879274-11a5-4717-ad18-10d8f7f78f92',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+MJGWHiCSNvYe8y6gf6RAal0H/p53yL8sVV9tBn1Rbq9r
2NXI+TX1INSd7l6hnQPCKFTVtI/1Htlxyl0j8x40wC8TiEJmOHL3dHS/gMDYl2QB
MLmQYzy8KrvTrJFa1yYJHhpTq8VgqIryRoSe0PDW6RhDekpylgvAuzvjCmtrdfIw
iHsQHvbAxI5j3NP7+QxsIb6Ll6TPxLq08ixnJXryyTk0RL4jAu9c04plH74AFSt8
GrVDszKH6iM84YFyWOnqjAv0oGzwaA4w6J04ZcBK9ijhGwW/oCr8IfFyLkptXzSY
EyisYS3NJOI4U42j3Zm48dbY9IFLQsfCCAtwE31xaK2xEXbYZaoCg9f7iikEnGtO
Llc5iZKMSG1l0ccEGZuyxpkKcfH2GH8TfBol90QPVHmb9cBqm/eBuc2MOmzropCO
G26WDRa7ujyAdQqoeYjyXoYDu0I8IbqEpMmx43y7YpWK3TMLlVOAxfmS8mrN5TS8
wkUbv/aW9rp79Z+cSWMVRUi8Bd7J2uxqIBlctfES4q+h76FsCgp46YPEaGhIVryw
TEHRLC5ijziP2zstlLd2fXs3q7PSRVUC0QZwtT9Udvk73B13OEcMhFhxcVw4Pbye
O/dm2ghw4GXWYrXnhEHCtn8j5GJKune/xGoW5+jFaQ+ogzsAfc3Sgj9riX9pN5jS
QwF+rDBXVSBNsY7EJebPQughSnyBn/ncRlwxcZVAAU1vwgd9zJST/yxU1LNqJnTk
QoGRG26EQ85jg9ipjNHZN4UF0cY=
=hexi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '858c30b9-615e-4d5e-af1a-5ee3a3c82b64',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9Fdi7mqQNNqIZqFO4qfkDBzFXJv47hzPVZ0oKfrw8RC8v
2qTKrfWqjbvhHconmp4609lJtbxmNNmpLFa8W1AucL/FgycpCjJgXLjVCEWf5ymV
jt6MSXxmWG6qLHnyFI5gqfs4HYVQLm4qOnN7eXPtG1lZJmUQ1N40LTmUg6WTEW+C
zUwS0di7e7IsF1ZRpY2AR4THTPgFwpx/+aZIj8dLoxwkHIHiK9ri77grDMGc4uZC
hO9kU+3VKOjF2wq3/iuYMZ+bY1mDJSEnVevk2/OJ38vUnsBYxcJXVajLMWHl63Y/
nBEOFtKtI5J3tAX5eME4VkDri9OspkkzUk36+ifZC1zEGNhmaZBYPrHxfyCkNnLG
7IHrlN6b+kQW+iAmEIg5tmYNIQmWAJKyRXMoaqtGKol6PKUPDn02nW9KLr3F9S5P
0HANvp/6/SZLd1AnN1aQX+gll0ThpgDtdF6tj3yjiW54fiFgmmTIEgn9XihQHI9A
+qAp8hwydQ7uItZ0ClG2A+CuglnMNqex/AgWZlekOoFxnGV9QYuRyJSniGVDEMHh
Lp2Ky7R3MqkXVLi+p6CyOlIhdpK83BHC+no5qeRwrUXuWNORKJBffThc6qAxUmLx
47Rcuqj2cgoQoGeD4hBHBR32CzAD8Kjg094puNZNbN8hd8ValxaOhdIUmG/0hlbS
QwHfnXg5WN1sQ7Xpc6FpYAgne2AK91N9GMT6bNOf6rOnVpGh/d9ualpTSY7s1gxk
MyRrcpOgJ+idT7pYbbciNPYrgiM=
=Keyg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '8f634223-a900-4569-af3e-8a8d7a929916',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//WvMXCdANrdTaX6sZKnBxocXFlUIdOtTTpP1EYlFNds1v
SqOdpNeV6TOMrJOm7Ri2lYhFJsf3cIZI8skpEcaFuaDWTjzw+a+UnC1OqMlpUJxj
USROjZwWnilMheNKTgQWYybOKbbAircgETddMxsopLaAruGpY83B4Yigz6zkhbEQ
G1TEUkOmk0N/PCZGvBXbooRKC5u1EPdlKvD1f7nl3P16Hbb3ERr+Ptu/YB4dCkrx
X8wD1ETql7FqCvm/06wvl2OD317kArjTY/KLFABOA0ZnIMQ4tYPQijyuR+Gwh8GE
lVjtH+l1kGzqc7S4STxiYGWuU8/+CiOMP/AtzqejwW3p5H8ENHcMeTuLvrbLICt7
mvvlDpGkoWN+gcuLIxrFG7c2hFdqYv5are3ltKrDQH5PYLeQzK1q1Fy3LjJHB8Lg
6qG/wvFh/jOD8OalRHJSE3M/eOBUm6U55EE+S5zVGynatNhIie4UmJDXDeEDe39D
hTvr52Yvq1hBj6ms4y9NdpEXQBKO1GMyb7YoZlgmen9nv0+IhcakxBKbebowYAJG
wmg6KB3Pz5YrI+7wOz58bUNH3eIdrbdKBuoox85ljxoa9ffZqSaSHls+J81L/1+A
ZmxVS9ybZ2wRjvKoUv675cs+huan8h1woVU5pAccqsmqyO/qChOuDt2nTklOYa/S
PQHLANAsSOFldUBQn9RdDvcIqaxzlmxpLvfy/6H/bSoASoMK74wPZGU66N1mSF+t
wAUHLmO+Zv7rm4lWZ6k=
=aM4R
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '997d1d35-fcd2-46e3-a21b-9b2e793910b5',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//b6iIn4DS61voawhSTur3Bu3w1JoEanqS4XfICPMOxf+L
vwWJYVY/mftGtHxkFc9gNXYEnSd1tXPJwxDeEpxmQjKGQE72pJANl28OaQsKB1uj
CXET+ZKsN/3zJkpk5KgmzdN/wxVN6zJVpJuBzqUtdqmItM3IGl5HyRIAhPfgW/Nu
hjmoR9JTdLuK1EqKH+NWBxPvOsnCJL+DX2LRuL9CduuxmtCz7+8w1UX8Oa/Us5sl
+w4ltwAnnHg6/phy/yqpJm0+uYzIu6YjQm+w3rafPI4DVEEve8+udqOY9bhBtWLL
Amnf5NirxCKjoKwECcSgtnn9eOySI6E9PmaxootCoSSG0/Od86FtPmUVfBNx37BG
1ALoq7h5PinJKin8HMbV+5EgQAI22Y7blHyiz+haFoH8flcID+tKzmoS2ymWxMBP
q2UsMYZJ7B9EPBXovSAnK0W3W5b8jI0dB/HIJY1pfi4200ynBNQMEre/6vc7Xs3N
K9eo5v6yHdZ3PrOXz0rw+192GirAT3aaRj81nJegD5paDEAk8QzpWa60Etn5SkuC
xu+S28ad4yWclusmgeAAGW9VVbX6b+oT6Zh9mfnCaDpkzOH2v9FK/Uc1frsiqqg/
14j2qE8eOXE9Ma/IutI4rpdb7Mw06sOc+YXFRXcekKZZaSZNuIclpP2g1yP0kffS
QwEvOccLXuHYkaXUCNSodio+BINCpj5Ri/y3pp58nW6Kq8E9qdlbPMn7UuCfjimP
eiXu0bBwISzlVkjHUIxfCdE7rzQ=
=t8un
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '9a611b71-636f-4cd8-a750-b5ae357f608e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAj7uLwVJqJKraQLG6H99+boyu978etbTYLlyDCQeoB22J
KvcsqLdmkFqGhkNbpOvkxNFPiJvnsolFoU48wcoJSXk1UTro4Y+itugroI/NbBRM
QP3nRIH5Myi8oCV2SzHKDSef/wma8+cq6nxDrFKcco93VqrF/kFRZBZ34HuV4WMy
06yxCSHk1qegN667rqNguG/tfsTTAScpaLtKqzlsv+XbrJKpth3+8z+jSBqPFSXh
m9/Ai5m4AUn95cKBbu/KvwLxVd5VQ8wLtMvxrkUAdWFgJmEaPzo7dH2QvoqjB669
T4DTv2sdU9FHIP/FmbunRzsYLGiXFTJYKqtOTbQiY6Eke5FzPl7lHGHHHw+J4TVn
LFqgbY96kfz0CILHvidG0z46CVlgnqHwR0YM8QnTcOwYr2Kvhp/raZ1MokdLx8vg
WOQLpteuF/TIz9mttClPsor892wAdDW0Ejim/ZPR50cQjMww6ZDO7Bd44k1MbSID
FlMx6tfM+NhaSuqrNlRFQKLnAUIejYpz3L6HILRTQvqiTpTZ7Z1Y5wKqivkmf2bD
bf0ifVbkqJBNDXvrhIsonlKj3vvct/R9xm1tApEhJR0eMUV3lUT3ekjwwL5NYmjk
32UYl2tdSV+GXWJw6QfUCWNv1+FMwZCuH3uTmk+eVXFUQTEdNpZ6eiwjhm9SqebS
QwHuQouHO2YgUCc3cYbo5B8KJdgIGO1lLCrXfHQqsrME71pU8I+Gpolr7hK4MspU
Y+PLWKUMkNzXMlWctF+pZsEUrQk=
=bAKt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'a5c7296b-3df3-494d-acf5-6cc94c589c25',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwTgi6J2eX49qvNQUgEKrock9eJywqhPsCS7UKHagY60R
3SSFWky++B30JL2jHG4Ws1kblCeVSLj11OjufyH+ZCqUqXfWZWx5pGoRGLt/fCEo
ib/b12M3ooAO1IGtF+s/aReQkvX4aAgVvW9WnSKErZyBOJ8txtI/3rjffBIU7WQ+
q/RUHLhqvkjuwxryGuC+tMFPZAMWnEzyfdZtjeyBr6bfXQXQX+1uGOIYCDwCVnod
Rlz7PVisnT6p0HgrNR5nBIY4fKV/g79to4Xb9ESXJBTTBd9x1EPLRe7ecLvSin+z
nLrbcKMLunIh7DelQijcPU0Ti7oOzNSpuHU39nbQcnMVoScdOvQb+EJ9d5itmWzx
skYugZcDlUY3wNKQkdl11tANXZxmKcWtg+AGgRrXBC4Ov+zqxQE9y1FgtB86EDq8
cE0QxL7Rqr9YPQFS7SaxIi7VKE4cl03GoE4jgVdjx9wGLVteewf018hLTzjiQ7XR
1NFtdoQU3CclujB58iqajDPCew6N4yuNqMQ4jNv+Di/iH6zXlnWV1+hrx+V5JVAg
gEc/iHoVmbMkHahE3pz9JkXHKUKDWZh950eZ+o2cGwcTwPe18HDLFJqHY9ib1sUi
8PtYugqddJm3Nq2RkswTrA7YIluUS6PioCSF7g5jpSqqb9/cI4dtsPg72/UDSebS
QAGX41j39gJVPuJDr1cwvdrAbckck7J/hQkz3zuje88eU31GoccGhKGxxUnJvwjY
4DANbgTDAwN1XUzHJBc90rM=
=vYou
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'a686b6f2-6087-4259-a7bb-6d903034c20e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9HV+X1uj9llnidi1idr+VQmWNVKv7dPONObSnSQWEwQaj
WNqw/OYREg00HZiYPkrNIXkeriXqJEPkrYlZNjs84W8e6yzTAfJCygClaQHdu8QJ
okrfOOGE3WeWR1tdBF2imi50YVuufAD4/mSGkON/jmLwV+SwKRuRwQmmRkfxnvq0
6QIPXH/Ra8bBEGZeNE9cOSoHPstt6cdhjm9bWiGf3Cnr/zh+UrbxEdLeKVnjVk+P
DKUwkthuYJJdM17mRVF/NS19G4yE5X9g1YVLAar3A/ZBAeOjs8DME64VFFDEgL2c
3WPJmXk4uYeiYgelxEER5Gh0X1a7GoaD1H7G0AjAHeLiVzrq29OZIIOBR6ZlHazF
qA+X+2+9oe2CkwJyEcVcbtRNi/r+Zgb/E3CJnwUzuJ6F3hUDxyrLR7liOy2IR6V/
FK/4H2ZiiJa2jLFlIULMGmGca4f2+WAJ7nJibKNmvjLLkTHw1FJBynfLOawwII+x
bfxJ5pjsqZfM58G2igWaRXr980TIZlq0f714yxUPnRVr/Sj/G2r8CYnLr3mr9KEu
akJ/zXsBpDf4Ir8sqdVVQLAX+aYyoD+WcV2noXfJtSxex2a5c9SL2keE9J+G88Ts
y7RcLCKl1mC2HGbsNmPVf0nsR4yCRT1TYCA8O/hFWds2mzB4uGaTU+yjLnReIrPS
QQEhCGcCq/NXJRuL9bQwHBs0eT8QQqAoKVGwuqHHxs0rWnLEUie/nnIDqkYqtykq
D1o5xh0FQLUegk9dDso0kt1Z
=ke70
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'aef322b9-632c-4ab4-a603-45b6fa5594e3',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAq9/7WioGtEfXGhrMoGVEIXGuw/Fja4XdrIKBdTyqWbGf
zpFadxoSF6Cx630taI8bXQDatxroBUggft7TODWhRc8bsVvpjIz5HDMhXkcT9XuW
DRVOy3DxlJngMntQMOTdLGNbJ3JQaSc49HD68GgKm1xftBilGjwbfgRYziDCVGLq
o+AozaP++Hdb40fUIj+8KCvXJk2AgIWx0Ph/aUN/T3/9jULXAKJi9IcM/RvgVzSY
tnjif5W7iql1qToaTTp0JeaAQASIprKaColDuD3O6U/7JXarYqiU/kVinrjU4Qx4
A5SEygxcnxlHKb3GCuQeCWk8TOYaYdpq9hypIEK1frnfbSN68e56TLdLyogzVw4F
brZB1LOlSv/VE/byatOEt2e50U1mywjUchHUS2N/5ARC1KQQgrs/t+7yi/x16DEg
UFdkxJpAKxoPWQYHPLBbAm63j4YNWU4vLGKij9C+nsrergEM4h+O6SfOEZD+S0P7
jyp/HcJBMHASO624/Z2dpRaIqsy/KmXDF9MtyKVUNWY4phwKxWFtP7JTcXx1/Cq7
w4jxaAdnNzqnd0TBHExIDws6fKttWqvRAdu+PTa7VDzRB8J9uF5S/gF/60caKYEt
6zgwoVHFgnqCcCgTtFCsb04ifeKv/PqoOrisXZ32DO3hA8bRdzUjQZdNrcQSlkXS
QAE2NoeurwhTJb7ij9koEKtucEAJKOhXuGpT8W5mG6wmOcqQ6RrWmZPu4ag8hB4Y
/ns2+wEf2lPEOBr7/lvNVug=
=nujQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'b2bbbe86-8c75-40c5-a274-bcdceb202816',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XhkdJAlPrUSSOnjWIVe5pCmH+b3/H6HRseAoG9bljta+
CGb0irgrinuu0I3TnBi+6EjwiAI27KO80HvJii/QzETE+QAz2e+d9gsr44TlmDGL
SxYPGZPR5BpB3HdWAANHo/iguiVxl05AudaNbuzbgdiNUljm7uYU6Xe1rmdw0h4C
ivJeMRE1A/zW7bLUdela2im25+Tk04hdnuAZZRSzW67WtCzJ7+UAlxq/ZP9wJTg2
pj+HqbRB1d5WoNpLQUByIbUwuNmt0J5Apg/yR8uw+uHqN9hyH6UeDDZk8uQrV6Wt
GEsN0ZgcfThNRrYSal5mJ7XdtwwjPalXTiqQh4dD9ppCzD/m9LryHiYU9163GMh6
Htmp8kORUo5iiG4r/UH9QNCWjas+3aTV6yhr3IXJ+B4rThN/yIljkN6xS/oJC/p2
B7E3DmmI4KIBUXbBVkoXy6W98ELVkMxGHfD3LVb58d8/8Abcdqe2lEB4va0d+Jmb
dTvN7NofrBoq6ZVJbsbzZ0imSRWXBF6e2ucj/4t3/JO5LjqSr4tObByg9Neylh9y
+/bO0UzSYqC1tpzXa8cye6KSBV8kihqkhUQy7SvdiXDAd+aidfp5dxuzTc9lv62L
Mdcy7NqFnCghrWX4ufIoblGxx5dTgSyMGqweY0wA3Q8maKk/anWfbw+dDJkLds/S
QwEqydU6RSWTvFMpN8PqETHsgP97h1yXDlpna61ABU2vLcJdfJyyyshj7hBNbJij
vJC6HJVrSx7IYVbUaPtEm5E+7WA=
=uMLL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'b8633720-8f89-4566-a508-f09c7edb6300',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//XsJ7eNZJuyYrgNUx6S87ExEzkK+0tO92NnD2UM7Xrz+f
nJgmQojSfLK/GBN5EXbmGKQirC7e0wkC/FitcRZT+qD9aowl+mduglS0lTNAXVVf
Pa0Pt1Z2RlU856D3fsWdPovu/KVlmTkZhKkann/Uu2tCXJ+IkosRcixoebMiR46+
owvRdnClMKFrCG4lV7YV8RtRP79SG+h011vfc73FWmA4REIHEjuZM93hqR1tJyjK
4K9YUi3t5KJQqO8s2BnyCtxSmCEwm0ZMPOyNYaGBVOaKAFptLgB8aiuSsC+djtiE
Dp8uQVxscFvdS96PHV5cEiuiK/66puZTlM4jyEoSiLbYxDzv2aeOi62SKJe6aKGS
eTakOwDDdmHuVXXx+qI4aPp5mgzvAxvJ/cNwbfE1V+EkakP2+jdf4CS2jz1TCWfK
INyPu+ZKoK4dpl6eAXJjn0xgGZ42iznC3BF+xL+h5/Isn6jegxTlb8hEpuYCGaRW
QkoSwcE20ZIPDxQGlPCjLZDYAa/6s7EgyxjAIJ/c9yQU3pbw+MPIg1CR+cFwfluT
owIyaegJbb8nHMBDakD+VbpiECZGfAvVGxhDeGq7qFs6gtZpgb0up2aw0HJe4KK+
5e4NEI54ZVyqeYB7mJ7flKLez5Qkb0VjdEjjU/ettDCHZqYCyPf/IoTqZIRGlY3S
QAE4g/gQdYL9HwewmssqkVUoK/WLovvHVSto4iH4/oVlyVmK7PDkP/vrTxq2reW6
8do58SLhZ5TigCsEg5zaHgk=
=o0sS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
		),
		array(
			'id' => 'bc253a0a-fdcb-4046-aac4-56b9e96a5e4b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//clx/f/v0uf+it8HgrFafsLTGfc2m/Th5Et2DgRgTHSvm
+TFBDjovZsVLZrPbf+Dcs5SMDhNwRW/nZly0KnaVHwMN1gNBEiCEDqh4WQMm9Vkm
IG5477epFecOH/FMUXLYsvCM44DgRCQ34ARr8nd8pdKDf2vC3ByvsjagpnZSau/r
wfkbBzbwOO5Fy4Hes0Kk7UrOHVk93nFs0GeKaHQJSRX+s2ieTOrlRM2f9YHqC9fQ
2qAKHHO8ZXGzwO56rupBT+03m9syRYAqyuH11bZThvz/hjODcHMBddybp5zyUZwA
CG1JnAMipa6ldNN80Hdwp6ZXdCYIDb9fJSpFTFXDUZ6wX7Gb8lwnHu8YS5Gid1fA
2oU8Dw/NpNKsGOC2JZzFdy4Kznl81ACJjRLTTHJNE2RXcJCW9hXf442Xop9LWqu8
WGbqdKZnqDfRLn3Ax7R+MWuGOyUep3wZ6/q9B3QPwj8pi/ssTZQPx1f6wriiB34F
pOK3hzkQE0lLU8zwK/f3EhDkyIl1ejQYBvBaRpwXCeed4nJZCucPGYt4mb7QfKqf
VoqOTy3PeoP8bpllONjjnVxKjRNM0dq5w9BxrAcrBlzU/FT7XpSrIxK2NqPZauxl
VAANfKXiwot9ECMQEZmK8mwGMN/MJtZ3sX0ZrlBjpIHNq3Ak7tn9XSjuV1i+UqHS
QQEiwQa4G1nfv6vNpEeLKPHBP2v4wnfAxTw3QAocw26ZJxyIBiqp8dyq03bQ6xNW
krGXVyX0e9/E4K9NGs0qqfT9
=dNsi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'bd2c072d-6924-4b5c-ad46-e50b2ec3b48a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9Eqp/P3U8gssHhi3PTHKOYnfxyupePLwFPq2W9QHRZbkq
qrCjSPvSfYtKYmSwIZJLGPQIIGV5UZC2uCdb/ecdD4RiRMSTFZk3RCc7iZYRiE33
88Ep+AlsVy3DtnhAH4A6b2iKD41rdzcDQVxIEu3TL0cU0+zF2h8E7ZhEizNEWctu
83Sbz/1q5OIe2Pn6HBMFJ+B/kWlvyMW+lc4uT1uBMU8rOKxez8RJ6f5y+NBxDfe7
ROcvk9KoRzRTVaVuLvDHBw4syBPEx2Xxy7eiG6cJ4h7HCneWtv5Cvpk9TuRvruqI
pxW8BvT3koUQ5iVweIRmP2/tWUhy7GwlKxHdI9uO4Z0bt19tdZGBIBIYQ3f6ElX/
0eXyapGCofFvvMqeYnhFjr6f2O/l1BkcPERMsIMC2UuDAJf8asvvrxDzbpPh5N16
qhkFkW0SuJnpBNYZpM0tUfHe8w8lbKB8RxBr/E8M0J4sPHXNFde1rxHv5VTb+PX4
TFC+yW8zdBhMnLSUn9Aa07a7Sh4XebMxHVYQ8g7xp1XCVgy74hFHSEucDOvSfQbl
u3w17VwAfsV95l3KDeUJOc8GMWEq+484/t7ROsOiGx3yPl+hzWzXUHChB6XqowEL
7i+s4s/jxrZN9pF0unzf+XPKb+vjnKL896wAtyfhaQ1ldZer7qt3Vxx+/SD4hQrS
QgFZzIwmdykwn88h/e8B8/g4hnRapYgcXb8JrvY6wEXfl60VGvV11j969wY70nRJ
21zaVg24f+gKExzIbiI2xCIH5w==
=fPkB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'c159ca87-9a63-4113-a9ad-09f0c5a0d629',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//SIZERdWPO4WT0cGLE3QTjbluDSAMTquxYdVFq+QbnSLD
soYUvDDN+6G5KHY31ODCo/4CGzNZv8oLajGAI4QUj+fySIBjothTjfS8ksfdWwBM
ZxNw9ZQy190r1JVKgxaixl+tBenbUOT4yuJAIgEXyUUlHZquTqo/8WGe4VPwGPyP
6V+u2HXJQ3H8vhz/uCHcLhbB/MXWi+thiHWAqP5gDZiBfRTvkYUL91xt2Y/6ZDmz
zY60ew+SXBqNtkGkp3ErQf/IJM3bm1aG9N9phXYBVsGQW6A80KWnVZlvFrBFbEZ8
JtLhNwETEy7M0VFc5oqK8AS0vfiNlwcnAp3Az8SqVIR4BajOqFUCFXizAy1Nrpjn
Ve6hHWVVmkndVCfI659FJYFs1V+CYDSXMCxnBjElYU7sj0oyL6hiEwfQrEwdBNWl
iTJ1w+inOyCAM0GNXYSvdMULelfH698tlmLlarcnunWNyQLQ7tFBZiKQPTmZ25o+
fm/ZiSnycx3D2KBzF9O1RJvj/yxUPnVM3CHkZsFZq2FoclHMPRea7bUXhfXIhQWf
TK1jxwrG8Vb6iDu3MRfEZbLGCPjarAanXfXZwtqwlJvsFBylFE9dHuA3NmUQuxxt
Dozd1CjbozS/x7fh9JmqOC9Y9VlqrNXmokPGbQfPoJ2ocGeRxa64qZ8dc35UMqbS
PQGZJMnsu9T821VOsq3WGHesuMTWv0K+7Q9jVvvHNBmDgVLLMnFXivf5Bw0ImHPP
gaIN8hExtQJzQF/3QpY=
=9KGb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'c25c60c8-75fc-4d4c-a093-3753b71a7015',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9HJsHr4zSEmkfWO7wWCkKZqVyiOkceHyryzg9aMhszSkP
k+GPD184vpdwJZkqPYH3nL5lrQB1iO9Wa9Z4IDuxGm/g/J2Bb9IyOl6hEGm+Ehw1
GdqeL3+or4msalr0i23rcoy/+plg8lyg6R9ui/NSg+Db23Q6wPk2FknO5X7gsM9M
EDp6AI89cRXhtxIZEJUVRwt2Va8uqKzcMHtATCNV+KtrSbFnjVLxp4cMpIqWyogJ
w6F/zHI9oJO7kE6+bikCHQL9h7MxBc/Z1kqpQpVye3gc62NGb4V+POaO+xTJbC6p
RRQ5JjfsEuzmVcQ3Yki9pf0R+b8+Qd8ZF/aJaMLtJ77CBR/cfoxkEW1Ru60jG5xb
2xCzXDR1m27DVoL2RsRItE6kgmC+8U7xHYKX8ndfgvH+lvKu1dJKljV8pwJwlKrm
dTXujWsgmz7brRQGuhTrL62wrZDNggwkohCsbehSySuPazUjrxCU45+bSkJ9Mjee
9PbVpMctG53taiM9osUlfpcvoboRsRNrx/N4E0+sCDoVr3LcFl+dP2kgFtZrMdoc
DijOa5qX2wfQPZU/a861gUqZWXLV+eWdyry3kEopo300Hk04GZIGeyJWdYIyECje
Bek4JFZFOoDjJeTC3oIY+qzt/WJasQTJrZUw6fxubVdPyPoJJBemiBUAG1e9KlLS
QQGfKnA1uiqhjGm3TAeTMOJX5gUOh+jqGQxWRW7S4PcwWWk20o/cXxF3gZTEXVMg
V2RA/2+bX+GIOHRPv+0MY0RM
=4RYD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'c5fe9940-865e-4394-ab5c-07eaaf423e77',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9FyhstQwLRgWsq4hvdWcr8aUiAP6Ii9KsZg2yN0OJ7I3a
qUg7uCucEFwx3Drdx2arypy+/JRPVU2vvOZmi9ITlNIvlKPJSs2iDD9IKV90x0R5
1aFHqn17R8WzOY9lttqAVnQ/gQn1IbPivq6KkVLerN/eJ1Cx3D30hrpKhkgykni3
W3uyPYgzT/XqQTng4UDGsgqPKqG3AQewEJnkk/eZBfRENgkXRS0bHA2ogDLWRbhr
00p8wy7NW4gewiz5JaaCVIGbX8trI+jC3RFGAE6RwIVa03KNHVpKhZNKNBnCNOTW
JZB6IVoBJU4I/3JlEcXnMeDBUVa5UhuURsUXM8OSUj6CHTO+i2nK70jL78H0DlYa
mzxkYP43ffJUn5YLr5guji8NmayDGP2VkU16YzVr0Gci+x+CU5XheV89gK9Rzu+p
fvsXOb1Z9LwXkCX+fKTE+TqRYSKM7ncd9EU5vPuGh/4Ixcs9P25V8OqU9bwnXan3
avctARoovtr08pIjgNYkeqmoFbKK4krbFyfYsH19KW6VZI012Y4zkU2VV1DZn9lz
Y2L2I7HpZJnyebM76RqNmPi8vpMWtN45gqWxWKFzbIa4m507RYeJHQ4BbED5pTPw
+uELSSHMKgUGq28vmVetevNE85Au4OL6oc0emn61agmqbHrjpinvXFoHopnOmtjS
QgHdIBG1mMLcBPQYlWmwk7MaVxw0K+G+LQ9b+wmLTM2xvF8G/ZFjSJhPPE58fPRc
I7vE3L/JB7H90s82kKhAPnvUZQ==
=sAWC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'c8f4ca6f-f191-46f2-aa06-17cfb71de611',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9GbV3WlQgP8uBF09/BE/JZeMb10z1k0U4Gb2uMUoBkoRD
NjQ86K6cHiPlFajkSnVZVasSSla+gcH8UJZhtbrq0zK3COuDXDEiY1C0PBSrxgLY
l230Fj3uZwzvmnKZV+mRooyaOM2XAsjyw8EzRl6NF+mRebGASVegikFpizKcQAcX
AcH9ZHGUUoH6N61Efwfl8WZU0l6gluEqqGKlV1tcwIQ4WePp4Z/8hDYYU9qB549E
rOS4p8pcn+TxhNTK5i3awSaLhmaq7ZX2N9WVxCu4NWV+N+flca9Oq13Pj9AP9J8O
2bnrWrqc/kiEJsTx2tm3HKBuxmsKZI4MWaWMHi+HN1asoiZOwE53BxJxJ/WFjk7+
MUW8g7w1ivSC2ODK06/2MYpc55xUmrQlqiZRmNIphMLwHEWY4PjlZbMWhMTp2J+D
PJtgdFGZio6s5XtP4j7030SAtYKYWWL3oW912E5ExoL2aPZtbYjXh1cec2GiNfjr
LP+SttKQDaqLdfLTdlytIjhyeuQpzB1fuMddmfe7erYIrNwuyR+Sb2tOIoCZfU+N
rSKdXt7tQfEs5/bHlefui2N6iTUGd47vWKPQQBefAAK9QbGmxaB7zsCNMHfbqneP
GUPxRYhaR1JGPxBzmXYDDJyH40Pq1zZ8MHj88YUIrzi6zU2UtKbQF5W9iA0BsQ/S
QAG22fDXD41U1quLoPsTRYxownQqgDbdFmH4bq1CwgrOF26v9ZdDI+73p733aod5
bgygnavDsitOhz1H38P9YSU=
=QY8a
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'cc5c1f9e-7360-4aaa-a793-a476cd437c32',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//euTJey1ZI+xo731iM2XA1Ps2rXGTKUfP4tE1jMbbHGrk
r0TrnZltSJ/g8XNOGLa8u3yOAqIf3H8Eu3QKRj3mT1TyDBoogPySwaQcyGgwmHBw
rcheD1H0op7eaR8g3Q4nahoGsejU8GLaFhoMIQ/ODPjuez9BHtoD58WIpQGJVQeD
djb+b/GzehgFyn5ZbEXfMA/o4H6MKjN+Yz2KvRPLv3x2f3tFGQ5ODlUficIEM1gf
sfzV2CCGRY0jCMTtx76NosuJKOKcz+mlpGs+GN1+l3UMvcPfFvGn+NZC5qRxzjjd
cu1FvUxi8R7q8z0NhhtxjB/mPfpI6TKlLLe2aVTl5K0gFiRyttVTUDQmnc2EU+Pm
YqIkSskMRvcoEqt6gQ9txzgL9uhYidam+/qPTXB+57a90MiZuSRj6POK58YfHqhm
USwT/ysrLAG0LAjyIo3N40YKsrp4DLKyxqT/CZUe5DErnQ25jdcz6CoYGWp5J1P6
4NPSXMOOModNdCXdDylalglZ/5QUjtGbuDwJYyyLI4hZj4tU6P+Pqe93grR/HNqi
oKsjcc0BpwXHUsLa40XyZH1utUiNzJp94M94Fyk5S8DDbwyeP9UqYvI1do7VGDXB
wvcd+/zPZFT1DyWdB3k49ksCnLuLxPvlpfhigCAg85GEuNtkYju1XiI9pjoadQbS
RAHpOeXi3IiMUACYPzoeyFIYOM12rX1A4tAyx/7GWhWlcCvCs5byNwgvNnSglOrk
H+HF9TnT1uIzQ30SRvDBATjuZx+n
=GipV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e'
		),
		array(
			'id' => 'd3753014-f053-44cb-a33a-2e479471c791',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9GMFAY9sknlGwtCbGxEsSk4OgEtf14i9h5tyfJb8Cw8DR
v690Mh7Jy5hs5hbmcACneav2x48sa3/4W4+O/X5TOQuQyJwfUXqaCR2nB7sWlj+h
3xZUr6S2IOBu7KsRy6hkfQ9UfE5yDi4RSwePYdRDxJ2G9Xz130uA6kLheK3JJFY+
WMWNDcrP2h18rcjt2X38QdnWiaY3BTnGaO4dhCpyWMxMGUKg6iL+yqgVCh2McQ7X
3LSfezAH7J+fPLFc44BwZZOFKHpT/sFEYX33oQskQNF7nK3bbkMQjSFC+GWgfjWu
s3LK6wySVltX7nXCukjqaC1jC1Mj8TldXugdSWPqbp/R8euUFiy1oTCqS9Dpqz6h
seFSb/K2PlAeESWpkEzRYjuru1xG6mJIcEuRp6ELqrhQS6sndjQNAbwj8VUHUoq6
U5nbznZ3YCt9YEZs/xu/XCSillF0kSGCzWwHm/IQdJYLNLYcrKTBtYw/OstgCdH5
TGgcE93vbQiRVMc7KYS0+xtuzlN0yDo4hQ2LHA5k1VMNz4ofRLIrrkEv8F5a0y4E
MKqJE2KSbcTzHrg6Q03kIsNsxWFoGDGTR0wHM+4w56b1almJOfCPEaay/Ramvhog
4BSWXLyZqiazBoxuto0w3GLsWkwFKmEHjA1abxgHQMFqW6lDyEQwF+dX9pr14ITS
QQEIZZbUYuWTKTYtTGlHhZDWupmY3CV8jy7Q5fQhKeuJaa6sbRhvb4//kSTBpRpa
dn/r9c/BVOdaCTNv/LM4bQNi
=+WjC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'dadee36f-eb06-4847-aa23-56f2242fdcf2',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAtI9EVmrRUVM5b9w2XUYzfjyTAgMx8/tIMWQWfHE1X3dx
//7KNlFOFo8/c/oWr21P18lZowfab/cp/TfYTB+CfyYPG0IMDDt5UmwtVByAyS06
UtdHxKkBYGEN5JCl2ckkH+7wDdRus50XBVQH6SDGEP4E4bzBmBvYGKtW4wa8YDYO
lInmKP1tfEzCxgM5GRgJD8pnrolqJYzpF6kdpUHIbPTTvO8ISz7pv5aIAXYywNOA
cdYXzQlY3xy+remsk7F4xccp7BLH/i2wWY67Cf2k7DbZA0GgdMwE75fExu5dgi53
UaAKuKVZST917Ql1KZkGLGUTYNe5kmRmM7tH76kl568XH829p3Y75/DKr7Qr4/7A
NIkI1X2waZoj4tS0rMu2skq7cPU7PeXqAHC+nBZ94AdpGsXd7VN4SvHDEwqzz7p/
az3tYcWm/0KHDNeY2oS1NOiMehZMaIevhuzR08FEjWDWGJjWBhpLpdpYb4A7WCoB
K9yR3GvSp1J0zZM7JItl3byXE6SZObWR5CLbJEdGVJa5hL7+hA+HOu3sFhxLiLUB
LClKxoo5OUnZ3FJoYze/jmyZsw45dpZ5jK1IaLcuA6dNcu17qeaQ7Nf9sXhL4oEJ
mvD32thB2al1tQpNEvt3Xe2bzEQafzjNnXB3XYZqbhdhPXbELwDpOwbJ+u5Ajw3S
QQGWzFfiZdhxe+H7Av5I6MMVns69BKP6BQBdLrFwdEe3iKjH+X0oycIeU3GEOCWb
mQyjTSof/GVm6argGAHYU40e
=qjnk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'dcbb3cc1-d7d2-4258-a73f-5240682aad7f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAoEwoqKPCVozotMQLkEeCblazupqRSMzN+Am6/JT93bT9
OOLVddyoTJbuyB7qp+9xtaurzzMr0UQdUZdYeJTIAFBCaRap3HzV46SBD8rahRkj
aEsR4f+yPum0q+CKpvT7iTm2O7XXXg/Ox584pjU3WidYYlJOPktG3vns7MPiwF0c
iDcWmumBouv4KIIv/tE5/9fXEuJI/E+zFHZppVv1ibF3peHKv3Rnjzg5CB/oGAi5
KrOOdRNNLipi7wTfeLnzzp6mrn3pvUS+SgyYQvM+M5f8R1+6TsAhSCY1ffZY5guE
f6cjVDWFoJxKeO+CyEaKRBmt0HOrAvCwK8BtzeaKuc9fo8tTBxGIcIGktdgu7pLU
vWrX2aP9OcQhuqeDKGywJp72+6nHf7Z9iY4Q3Ka6bjWpSmd+ScqRICttLORuK2Gl
xPyZ51qoL3TeJRWNNmp9/c4JSJ07hh5XiABEMOR52y8f4u4DUbnmgykppJ2KnqCp
jjJjqavXf/yQq9UYGEKzRaIS7j5D+0FZAit2YRrRRtHrhb4blKev84D6UxVjrhhq
DKhOhD8V1kf8AGJ5xkaGrDWfuMwkM4Xb2ScsGr9LaynRL3qgIqWjWalz3E5rwYmB
40AO3eVjga5CXzyTqytNQnIH9iQ4rxnt85G4nbGcs9Tzfo1vfH5OiR9I5d9nJvzS
QwHKttHXIbkH4OzM6kUdYnKy9fJ6vNgY07VZh1k/K59dRacacUB3azoixFkCZp++
/btHs68QmNQhJQHIVzTJhU6j/kU=
=u5vk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'e67facff-1841-4907-ad6e-7a293dc7222d',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+O1HUIw4TIy13brL0VBGdCGP8RlLd4/eEu5y+eRlOI9wd
1WMaGUo43zdlvrhiLldoTmZKNE7z7ZogBIdUAtL5++3DKvDIElxOM44/0bdtgNVM
yiUTm+pqZ2g9dKzdN4od59Q7CeTEqPC/mdrcH1GXL42GnHhOQrOtNmQwSstcwfjv
bFScDp82HCNCh+YOdBwNj+9Sm9WKgYbo6FVHZJPwdofoS8wkepGWBy+6Xq5lO/48
TJTYGFL5U9qdKLh+BNakbhXtSBCqjrjkSgtNjVP45N9ICC3Xe0Bqzd/gxpglau03
GJ3w1uWlCR8KXn6P/kDJ5HGXU049RWPgUKPnKzuLnzwkQOTrXXuUSYyjP4/iu5c4
ivVrS06jVkm3/jjAUpbG+lhMQjIP7tiJxYftmm2UiqVBfuE2jQm2SQuL/5MN2XGe
z3t0iP0J/8KoLirwYRqg3eBHclykEkYpHCZbckuWcjE8Na1OUUDz+KLTa1yt8bXz
D9cK+KjI9CrjOLSqMY4mhngAUWBG5uNnPlHAdBefCNK5jCU/8iy+xKBopfS3tDe0
+W2W5wImd4mcWxGnCVRwtXbqt9lMwkpjIa0zknLjoLBUAInkGdQ3ZxisRdiFo9dX
3UnPLVbOfbplk837Dwww2ca4zVpFvvkP4x4WmMkny3vMo28EpFalehftNmy91GLS
QwF/OgHkG6VXsxxild2oF5qw+4UqZf9Rb8Bt9kVv2rEAF4pSTZKi/SqSyC9no/Nx
AnEjqozsuv90TO6KJlZRhCz3qKI=
=QIO+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => 'eaa1fd0c-7a2b-4994-a7ea-40573e289f48',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9FvclvW1Uida3oxfFKc03mg/vSXvdUUkNHRxkSDcY7eCr
F9h++iBA6zGItUaMFHRneAqP+tO17X9MbWUpLa+T2ya8ayNpZFd1Pt7yw5IN7glL
Xa6N354678jk4vJQq+ph6olNKKj2cH2inxIlLBHb70r9i3pAnYO+aVbY49K9QVRA
RlCUc2UzoqsvEbFq/+z1CNQ6imsAWPTqV3m0g1/+GOPPF9T9MYRNsBUsZXdK/z2Z
RjGFP1IhVm6C+aW17wpXjdCnzl5uCZn3wBg44pWbbAkRwd/gAJlHZOpiRN9b6wgn
rfvd7cCrwM2lbR+/T9UuhKaQftlKelmjDXbykyqoEoH5Y6mTeRmBJQ5jN5zfsn67
BCzEKS//FwNqgd2Fqku5PFce7Ke7nbLk2jyIe6l65vFmavv+jawPsgTX1HujwqH9
/w296MnhX49KHFPxGdIUJ0ZMTzb1GnkYW2oMMGMp9KUKDHfwfY96DkU1SOgLLHF2
xJPNX+MCbbjPf8GEXhTOdLvp2Slpwbdkwj2AyskBEQVmcfWgR/lZKHWIsNs8BDFd
fapnkM0o5cJ2Nvmp3IvwSZOBoXCs6pnNp/AaXENAl+QJoVNcbg2BJg4zJDF8J7eb
b+r46scGQD8BvA7uK14vFaL9hzo7PwUAuk3op0x37EmTCO9MReXSFex4SxOOD73S
QwHe9JnOUyRV+7KkdQPZhkuXz2By9gmGjm8aEYRVFOmE7Wqorp5be6zeEx99DSvU
+Bgi8cBVQSar+rCgJH9w/F7s1l0=
=AUsX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'ec2747ee-fc4a-49ce-ac42-c4023f92c63e',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+K3qxZoYXoGpeZGETckY2MOZLyhgFB1O2LG5oo3U3exI9
B8RhzzJ1pqyJYw0x1g3frGBLf+zTaX01pwfD+Qq+EOp1tPUW/rzv65UisWXFKJz4
vY5RkNofXXjp1wOGTQaZyUzwmC90or2vbuYFKtdq724vk13dcM4xpyh8lXHIy+pM
tDKynFqKS+/xZiTqB/9sCw29K57xnYlnegPJ5ckSLqEwEKwy+g43OkXtmDK0FBg4
cbZSSy6Pr3awSimY7dufNv4KjOWSVTcHdC7FbFsLLTN2yetg8fFXu7//yQjOEPpw
IPN/keYVPT/Bg35+23hhbda6J1wVYN3PPLElkxtXuVHBtDUB+kSPe/Fnz07fy3ib
7wBn1BqGC8/AOBB9RRp1SaMMzrPs0rU4a0hkJB2mSO3f2d9W4pIbmEnslp7AnYWL
C4zk0nZ0VM0e25+3MJ0leSBLaO0jb8lvB9ovkZFyrqhxrXVHVJHJy8dZDTXA8ygf
+jMm3SiKhpzIAXYgCED2plfIH9PqlQwfdX8YAdbV8AUaLMRP0nzvxsfujYX6Tz+V
x4NcdudVv0STYs++1wQ5lkL09V5gsHc4Czde7loVcSE9UqIaZ8yn4++5YLqGMhdM
9RlHWhIsiCYaVMY9lKTImYgA2Am8QbAumt85VrfOaBpWw241LQE70g+F+TjBviHS
QAG/JdTuC88jLcGstd2SaLnRzJ6wdMdSeLtIQiBTchz+yRNAQFdNoLbrekEfL84o
QJTmbmmTl0jgiw/B9dcIReg=
=xVC6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05'
		),
		array(
			'id' => 'ef72ab49-129a-4bb2-a801-942cf31d2d08',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/7BkLoY+zAZTui7og8fUbwf3k0SO3abwUoODVGsNoW4qoZ
mh/lvtq9vL7ZmkxWN2FrkdBTPyjsPGUg81RY6ABdpylja8ZpG60AD4LRyhuEW0yi
czxBAPhm2HOK07PfCD7kZcgLB3DKTdgVl1rFiufHZTV+Jbc4/EOpDBOrx6LSEKix
aUP1VvHjC2p4jBttpK2sk7cl2rBPUOwY2bWsHI8BEaLowX9KjD5ngKQEkpueir/I
KLO/v9fv/V1mfWuTbINwnEfDvVLrW5/7RCLTKG5JmrZbZU8qeJgBTAwJ+BGP9VZk
hrizBFrN4JLPN4yRUKsP81sUrUIyiTZYL9gHO4VxKxhkO//t2yjD+E+7/vjoSkfo
504dQp+R/vpCNVYKdINncOHktvmRdjvUz/QHGiTv+tXAT+psbmL+CyenZkwtFC/S
vnGo2wVZyBNX6pMOlyRJom9fudkh67UlQegJ0e0Ia3RO4Tc2PnCdSeDZCIcvwfwj
a/SFre5E1aJxf8JCJzrgqx28Nm+Zmsgsu8w1WjhfwyQLJvKpi4M4KTbybjXcehDi
6+gYH2WSo08MYydoqScOfRxeWGCL3s/32NDMtXmlyI+rhCqZn5yAKs8UYGKPC5tK
uu8Q7Z2GmmemgFnyXk8KMag7aZlgl4jHyECOa4ExiE0K5ueUgGJseiHjbw6NCkrS
QAFs8FIoE+B/S3euMuAmlwO+bum9dRlEPT0s43x2XAb4VMOUt9saktLTg9gwse9P
zF/Vovp/l4ZHwK+YlC9kuIs=
=a4Xa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'f3c11665-a2d9-41d9-a4e5-abfa551427b1',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//YKk8dKW5kpdjnqdsHDCjUrFz/WKu7Jf+hu9vJvBYa3wy
1B0WsD38ZaRQeMTS9tDXGuTwZCX8LaMrOF4qUIrqTJGKYLqGxof8XjzIfOp5H8qI
/by009lzRbSER73SDL4eWMqfwfoe2HAj4OSrSr+zUgndFbwtFDn8mksViI08BV+5
HN8IHEMVSFdHcqnzgPwmiRiiq8xgNcay9Ho6mk4nyoM5fhWBJhN1G3SBIESoTPcO
lvRMbvaiiNPY0TxVZKST2w8oJRJkmEXO2DWE5gwj0Zhzr9z5u5pUpn41bstO2HCq
S4sD4ooPu0ud2jutb0P6cv4XLxXYMGGS8EIylPQ6Go7VW9m+Q00/fk8nbUTUGrRF
3e3kmYRpHeecMCcud3KwfgPDQgZ0F15rETW6lACOD2XnfPvtHzORvwyfXr/zL93t
JJuUt+ybDDp4ZE2EfSieXBVnmYbzCo14QskIa5BA16OsHE1JD+0ofkIw2FSnTmji
sEXtl3Bc0Hj4xhReHjBB99z8OcYRhCdV8Qn5oj+CzfOm0s3ygEuC6Cyf3fhRnibL
YkUHuTOKLuSVenPSDQKL2b7ehQ+7j340chKfsCD4HyoYkrkfK3zYcISurYACjcS6
C4biM78VPyo1q1A6xY56ktLhiwZnhIJGWamFglkPRoaBTVgc7rrEYYlLm2nhES7S
QQHxiF+VQCU0HkPo7MvlBwNszhHTyhSIz37d7nNMflY+K0oKwKiy4YOUQa+SCjkc
FmMfBDKcty1U0ujftQJwgIp7
=GOF+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
	);

}
