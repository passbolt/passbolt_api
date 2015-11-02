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
			'id' => '01dbf859-5a5c-4485-a49b-c3f709ddc004',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkAo3sdzFdy6Dz1nyFmfmFGjua9aOzXYd/WMYWLIN32N7
mP+H32fIxLtJ1t2Q/cRdEitKFwvpXO1Me+xC5sytdac1w5FygmNODa4UOnwPxPlW
DcSI8SF/YwQuY707dkt0x3/71h6KpNXGNihsmxWoBHoejrijv2IsbAUl1F4dS86z
Vul04zI1Z9fgaCmaL2p10F4MTZgdHgJUD1/l9EMDRwfKpzV+so3Fg1Z3NZWtpyJ6
/CutMpRfb/tdNaWDsbvn+qht22l8YYlxA+KkgLRx+1U3e1S7i0rXLBmnyDW5IUA9
4tZX+BeSqD/6EWuBF2N3Isvgt+kz60Oz0yrZDZi1+1j1arBcmLNgFi583FcSsFs4
3F1lifBZEAbjn0HuXjZT9tkx5PZSWGTOwL+l7jAnrxA6TO2rduNzSwjh5BGMYunw
u1ckx8+Vi89d03hxuE5YX+uXskWJMa/mo5YUZuYooaWiW0WmLgIkta+WQ0a9jkso
XmXxOa1+lQiekeApGDO2T32JSUNg6FEY6vZbBOhMc4H70WbFPytKWpmlT/D7jcH8
CGzJXSFC5eFOqZs6HHDCZPAkp46c+bDrw28Zj77csBYe8EI0ZrK6nqxX8P5lQrdN
7PQm7gTfoUB50y8y+HpyBZqVChC3MID1DXC2VFND54IzQM+L1rzbNxDMpQKXo47S
QwEBooYzoIzvMmBlw5xr8o2LA9VrLJQVXR4ONC4M/jdQXKnUz/PYWLTL6Qh3Bbdc
PfN09P9mstC60LEtD+U/+SQr4LQ=
=saVR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '06de5532-ba17-4a56-ab24-fb710ffbf71f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//XqGUebC3D5qK4dtC2QTvPY3DZ+3URSn7bjHCyxxGuAq2
XN1BwoarVNEPwjoFILuzuzPgZtguuoEDe7i0L3sLFiONVpKr5ghtp6cxgDzYXNoS
hh4x+ceOQ6mHMztm6NXEMEj9JMX3n7rVj6i/LgrlisB5RG4NnawYVLmYcMByh3xC
trfBX6W5O00vqCdujQXDJyM6OSeJfzoNxNP+TD3hCqFKTn/v3DIQQnvzkIeZd26M
6SOs1x6QBi/VvYy4KL/x6yj1fD3petQV0vfE7QDPKZcmU7qjXrQ5AQ2PRKCsboff
DiG3S92Hd8He2HMuDM8iER39KDo1NEgOswVuxSviRng+tyO/2FC1/0F/3xT05erS
kzFGkRx1xswyzyqUN/4e02HlOaMrhK2GQuZtSJDl7Dpl4rzbeyokbpz+cgR9ALEZ
MUzK+qqfGahltEp3I3nbF3zp9bmawN6IH/jLvfMnGM1csbVHR2aGQ+BOzDQQ5vPt
fjPBqSKRETtDUeeqiPazpqhGtqwHLQqDaGyFiYaXT2RC7X7R1G4VnS2LHNVVTWeP
AveUxEQ9Qsa0D9Owo9NbEjePytSFCksOYS6qurXLsmOnLJg19Pgv+L2ApIdUEMeW
mr4D+i5pbUdZHhoIPTYbjMIvLEfDBr3wd6nZbrCowtvFNgmdZ0Iro2bM8apf297S
QgG7B596DOajzpu7hGcwQiajM1umFX4UMV5/lpYR6lTXU1qCO+X2zDmDuFnfIs9m
xmHC5sDjXtp+X9x14M+JblQw7A==
=vnVV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0b7155ba-c87c-40cf-a669-759a160296a8',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//YRG6pJTjLtHo5ezxY2KxW/LnvSVgrpQPCEb3MxNTDbkU
0jNW3GmDIqwXagSk6SLE/+lfdrlAZHtXzoY5kElFuXad4Wjzrs2adWys6i4nccTF
7/tX/urm8PCy586VGXxUSjC71NV7ETuIEeCldcn1FbNH+4va3IW//zaSY/4zf/tm
Ho21EvzrgHurjmVAf/3R0atQs1Ix75Ac5U3O1gbKYo6v5meP04X1lVlXA/1qv5g8
KtliTJaSVvpCeY4ZfIo3T2AXds5ooDlHeyruEhD5cGe9bG1zOfL25Tej4wEByXkS
pbNzjZY3qvUTld8myUxrcDWnp24SbDi+OOz6fpBdsNMbBH72r7l6qR/xT8LcpFWG
5QOgx4Jg2aYzi+IS+Z9lWWnSPLm5S/hK+d5KkyzbLfvYVe/5UmKfZgPjGzUSIG/Q
5hdF8/NZw2xPjJYSRhVK8L0MznlZGCp+HroINSiAg99VSs6bzAGmW+dfUDHw8S2u
12FRglcg9M/Bg7thyP4p5DL5gQGY5lvQ/z/aaEPDvhA2Io/23MEVGRNnQQ6KPOKC
HQfROX9aIpeCC8UBMUXnvIP70+A9oILEAjMmEj0XUDNPmYnscH1sZ6cp8yAUGiE4
cxc8StuAM4b8PkkYon3Z4DeMduL6+pjMsqHqd8iSNsG2aRkr73gzN0fQ5rjSXh/S
QQHC7s6gMVCVYahyS1xOAUGnisUzcs7/z/4pD8QHbCHf7dlW+pUXNyHvbmG72DU6
uUrAoPlfva4kkTRY2FlXbvqY
=oB2+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0eee85a3-7908-4016-a9de-c13e882ff65b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//QgMOzm4Mu51m/1UHIDo5zGfmQ5mzrTPtP9harEd4WyJE
y/pmpR3KTo0MLodGBBrAtBhNZiGGKT49wN4NaoSuB/FFbF62ocRqNFRB3rIquScq
kQFMtfig8FLhM8R/xd6RwatQxtFAWKELLbpuBdm91dT5WN7pvPEd6Hp89HhDKTHW
PbG1nzX4sYoLmw0I0unTK6dV12ZEQ3YELThBBStQ9NvgUEnhSs6jm7vepWu19qRK
NjQkDyoEH7G5wRKdxFdXOUvKOqCw8FmCuhEG+j8rn5WR5vVqzyllaCtDOYqbXMGD
E368JvzG1zlqB1BwNyna8r/Lcpj3ltZS0l8sfCajacvT2cui5sAiFKo+ZdUHzaY5
gKCcKSO1ePJHKQeSWl1PffkyUP8VoBDqwYGGE4KSHTTpCAnBrQwN34DrTuEgPknA
Wr+AlL26cji9+qPwJj/b9zTBRGV6QteZnQx0Kc5X+RnZYSnGuM62ZHsOdeLOjmDg
ns1OV8uqBK3ZlMimZppaGUqRA87igePb0wQue7gop8dStHBTWiu7M/JgNP8mtahy
y5PA9NQ2/ydyyPNsDfVZNzEraGcBXd3Mb6zM1NJx+Q4Y3XA2XtfHEgLqqQuPdBTL
hmpxn9K4d3FYmSW6IWoHcamYHlO0jIqfIaQJ63gpeHSOW0qcQDWDcdmRXbppsVXS
QQFxccV4mJdTtJ6fH3Zcg+0QKHog6qnmmJWOM1KxRX3uRezy5maEDPlCIjHtQVnA
hI14+0/AMHP6Sfzuw37Tbadr
=9nio
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '140c1641-2530-4b55-a393-35b11494f6d4',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+JMtnXl/ZYEU7QLS9AsrZIYg9H4lKENVOqVTL5uWCJt6c
PmwjVQNi3AITfUCrF9YsvKWxiqDw4i25yemREZGmrw7OeELFGb5R080F+Oa0LO1N
oNSR0+qx+yJBa2vsxFdh9UUB3TusvbXvHeTXEFj0v6d7+opBWjQT0Gj+7fmUWzcm
/HiUAf6oPBAI2oZweK0c3nJiLfvR2LiJyd3UTnYFCzw5EECNf88dFiATNG7/G97X
CoIcnD+ZU3Ru8iueFAGv22ggUGifkRuP/6uYrn9cxvk7B39mIks0CxuO68/5MOHg
/ox53+M9F7VtLFLDs8lXyHaXAjOlktWmePNzOM3/fijKZDW4ayYg1HAkB65MFjT8
1pVdiZWEx84jz3tKKipZPq5gFFBHIy6LMEnhpMpliuaVmjUB7eKJ0AJphavbm0FN
SaVDdfXpXZpY20Coz54cHd/0EX4URTSxWnRffevCCLSYS3XvXex3ZXSybk6qhVRG
R3WE342IGHLprDWMkdCCoMXFfG8geMnKue81yrgPrgN0TskbARz4E3hoKCAyQX+/
d+Jq0bVN6W2TKM40ty4ggWV680/gu0PSwMU1G1cUlrXFmLyVhdPCgjP/xlTNWrmM
RCX+QS3lTmyROR1a9XlGa1oE1a2GzemUNdjQAk1HxWU1pSFXXtXj2V2cZ8eAoJrS
QQEOBtynO/kanDMh/WydDXCOXV+kvO7fF3ZRt8b/OA6esrNy8/vs4ZEVO2x7g5zG
6kI5nLD1jwR54fPYxAOYGHPo
=lhbA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '14c7af3d-20c7-4ee7-ae99-26afe1190244',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAsjRNQmJZMBu/9+JABKjmHdF39E13HUasSBqmC3gBAJGv
fcg1C+RBMfcfsOXex/CKH43sOor7yZ72LrG4354XpCkygwq9USRSFB1CbWS8ifBs
lj5O5pxuwj4cBacLrA8jXVMJil04zywAnPdMO+2V365f1Cx2TPqi2MkqsQ+WtGyI
TZvTvX4rzYVZWFZcrjPMu1PxUNpL9LCNg1sGb+iOI+a3m1tbW/wEZ6X6E9ZyKsDG
9xx3rrSqjEvwIDlHBKO18CD6DMrAr81rsFNss5/JKF7uY9HVWJaa+PkuWOvc1ELm
XlHlBfn8eCyIWYNWV5I85LGAmA9Nz5fN/KH6Ul+rYNJBARzERHSNjvmSGJMe+Dd9
uOblvxu++X3NpBBptRy+4BYbJnEROcxDhm8WJcq1QgVrN7qIimxSGX0b/MvPzRtJ
VoM=
=9SNQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1aec6e74-08cb-4455-a411-eb22009f68a3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAzVXe3o1IVnQNkI23RCm9ZgxUETux9CHbzgk6LQFeH5zk
sR6K5Sg0aiLk4B9n9gMz0ev2tTfQzgI+tAzR4BomvekTUSIrfu5OjeXOOVS/v4PV
9hm6hL+51/1GeE6s9xGOSrXBWy1Ly3ZhUB1y00t3qxt0jsxyyiI0yp2J4FjFl5/R
JTGbwI07KUag5FfML+1/qMawWzvBth8ALBPTizh9G6QspUtlPan/blct5wsBOrDZ
7ceRrBTdkFbPod9V8fNP5eEaWYmKb0nv67vwSw3xF12r+N0fSUIsmRCHMQC5E721
/DnxcozegnQWRh3V5yZPBAS92WBDDZDuf6P331Cjq3bbNYx1qjEl09LH45RJomB7
D5KVqemuNad/MVFEsov8u4iP/OCYE8i7MKiHOo3djaFCWPuEcOM1Si7Gm+uXrBhz
ahGTczKApOfPS1aWT+FkTZKO0dvFRVqLk2qAI955ab2P+CBGb0RhHokpoKq15Ujr
KIY4COGo9u20IfhcnmJefDh2FYvceU4SwVYxzWdygGpIFhaObSGHhtz5M8OPafJz
oo4TDLgrQOJm5kHELg5Wd1jTfISGOtOovw7SsGSPsHiG8QZSq/37YXi+rddpDju3
12F9lEMtgE8qTLgWy4339k3cNdKanyfLRNNGsQhhat6U2yhQM7DYmT1WFOsslo/S
QwGB2NgBWEE6D1FNb2+dDiVUAq65+mG+FtDiTr2hZgOd5ZzckyQO6rkRja3avriZ
tJbR/SlyXr+1QTth0hP6twCtbMs=
=43nu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '220b12b5-65f6-4308-ad09-7d679699984c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XzVkM1/hNjnQzO09tU8F02fUXefWhkrcV1xaIbl7F0TX
gdLNTD2Cb75oexqce534Vy84/R8ujTBdnVtVBDbLgHpF1L74hxKSjTpA6iUAfP/n
QUV81CymuDcNr2Zygw0VfYGjDvAEsxpsgNB1+PiaKBBgPxX40yJfYpKlpybLi1mw
r5lXJPN4X9wMh1wGzShlPIj7nMxWMOmUUVpik6esGu9cC75+n0Lm39Lbl+NwOGs6
tShxXOOlbKxvPY4F0aHuHDFd0fZ2RaUQKAGJRTFGx9Q67HHuE1Wk7vx7lHPCymju
w2y9gQj57rlKrTUeexhgOr1m60ihpS2ORnjRjXElRraJpx0Jrrt7PNIrB16Fv9lN
W8FAMaHGgRcArazFqrroUBQgy+dd6lhOFrRz2z+NxfLZ+jUPS2PgV2eGScifAfvk
IsxSXi2Po3hPkIsdfuUz28lXjuMa6/fUJ4u7J7nJZespupAbOp71QjdsbFL0Ft2p
TDJUWU1H7IjR3dGQKs9f9fdqAlzbQIXxOEoaDjFcEDhm3O25y1hxTIfnXk03laAW
3VKUsantSpfdEQhOUv48pYq9Nm1aMjjMRjCSgr/O4JgegixOP19/70KPf4L6BWgB
nZ/MsCcqVKan972Cev5c+hZe7dBrHjbOsgSRhN/uqgm1+XMM+WOGyk8HBsbJn4nS
QwFOrUMIE+mchimg5dMWieyszaf1YEV8b7FVgH6y61VhIwMsSr3Xu997igYXmf2T
lp/dX13h6UwJFLvXV2vtWkOIq08=
=p6MY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '24dc1bfc-7c36-465b-adfd-31aed0aa249f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9FGi+AfNZfu9vGK5FuMBiHd8U/Gj9vlkj1GPm5iqzurj9
CR0bWfLONdmagizwPllTXDRpSJ2nTPgXRHvR3B9usKPM7cCrDcB63oLnyuQ5ZutZ
WroiLQC+vyyDcBXZ7ZsnGiouQdTThpNi9gbI90hIIeowQkJfhut1q7pAiyxOpFI+
DlqtCd/VlsK7079tjJmXgRMbx24FBajms8+4E2pjN6kJBMJniJs3uo00ngIVMKBm
7qa7Zz+zdwiQHQ8frhQzz74QeQd6gni+oPaeyIv4oJ+hyyKUm95ZfupI4jyDReFH
Zin9zs3IjjyRDYL4j/Y1xaO1L9PhTbqo7VMYVmVKmTDoCNnCL979rx4tahVUFfZK
ioBlXEVbcPh+Nj9qdofJxs/OslohIbx8MnTO7e0JLH2iVumKg43IRFQ5Lrqy60rW
M7noy9Jr7DyDbTcUuaRR+Bql5eeo72JflkM03J3pldivUULMllyh1ikLhv306w1V
mkepye1DZHl0PGDtBtrG3DAMmd5KPxV3gJb69GWGYZ4gj2oND71YXehbjkNPU5iX
nNiuCz3y7SE5l3OV7iEGwHzn+Np37NZIv7LJz/0gR/ftRpAQXo/YH3AEw3IvAzDE
oND4ukmnXlzOxeEjZw6auEtNfPxAFg7cddiD7zJEtVU9d3Q2iUu7oIB28unHZ3zS
QAGmzyuk9Uh55zXKHAQpywYRpPPX/LTkyxw6tWqMK13T4GVw6iZ5XT5YZ/fuSHK3
I96sqsZVdr7gnfpoVTi+INk=
=WVVw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '268e1966-f770-432b-a40a-d5e48bd1af5e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAArL1mPsuY+3Ssvp1rwntYthdFhgxXwYmyOZGqrbGEJa9/
j7wpJpC8VymhQklESisYmu4GyMfQxVj8g5t77DW6aNfgWiPM9kR6IzTsI+Uj8DZa
wU9tOrp6I1EoQm1OH5vP7hESLx3llqxIFlS/EAHZhLCWoMjS9KRuXbkGMfr0Lsmz
IUxGUKgEmuzKTcouMHzsqTFQaMC3qCBlr9icXiPazZIvMsOKIliMjXIKDnSS5t9O
1dI+V/TdjxE4+NMueH+ERETxCKh+knOqwW0znbOlK7MSbqSQKE4nCiiADRBzQhmR
76IfCSLe0FaxaLHFHoInxVTbM4FhVjF0fzERjn73sS3dQShMFbh0W27LxYet67bO
V06HNagM+cDXe7223OgqVgXmTSVNzArF918y0z028QBBcnHetlCS+8p++a+uu3KJ
Ch+jtm+dKCq/oKvGMkPw+7RPJPMjBkY0pFPnDqDL2za/rqzd0Q/2bH+qk3O+PUD1
xFDVbHWbqikRfR3aoMz3N+LWV4AZLOZFf0XcB4vCaRrWdJygoH4e3Hz31dijG9LA
i2B+3wOgWsS5qs9xBvpvcLs/JNcVio5JXGEK7GR3n5kaPf7mGdkotr3bk2++g8g4
Xp7hYQmuKcHjAz57Oz79+GBgjld1Wig8rC3MVZXHJho22g2TVaeLnz4phEJ2nSDS
QwFR9zyDukwa3G2SEua4eAN5kY+1R8VO9+pRRqnVHF/JyCgE64FRCtMPefNyrzl2
T4mnuxUUwx7XzHvyNCNtVqZOKZE=
=joQ+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2b5741b0-cd2b-44dd-aeab-08a2884f1caf',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+LUq9o68DDGuIc8Z+myWjRTa+3PuBybOVWnVOJK5S0PHa
bmyagVmd5tDaK+9xMx2odsqJrhFhipHMMxgfF1tNCQOb/ji90HwqLZD5dbljb5Qz
pahhtrjIFqzb+W5h3jax281oFobPxyMpCI1TxhlXsHOXToB0CmVgE2gst7JPPKVX
UUAaGdwhKMnjLMqFINSqvd9QAdUDmeNAx/3Iog7T0TF6eSDLiX7bivuteJPu9JNh
bOuZ58KZYIApEXvHD/jrErH+sgonu5pZh4I/YVyPdRXdKR774Yz34vO9Ckyt3fVi
iGl1zf1j/Dt51J3hpZcqW1gwEeMrZgq9KvYzqLjWldJBAWDhvTSdaCzAv2NuAYmX
oA0+PTe0aCJyex/dSIUO7xbkxafFgtN9s4HC9YEOHba+Xdv9kU52hpp1RTrt7nwV
2KY=
=Prnz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2d51ec8f-62d4-42de-a852-b88e3c956a27',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+PxFUK1DhFdogGpY2MFHJ/Ll06fZWUJdzuptq4tGtr4qo
kOsxFdPMw1rqIiPVEPMwFcmAsE3dLnSRUhdkpgl72Sct7VHWRJ8AXY0X/DVEuIAP
ns27VTevMyBKLpW5gmraZdUon2sHVu57jxp/2XW75RzTtlrEDaylqHMdufKRXTyT
fG1GDmEvEFdnBzt00DGd80K8/TbLS7wmyWbzZXyhwhrFZA1CobrTUPjWZ1k/MEzt
CtS3A7vRlFIifrGyKbfYW31ZzpKwIr3LEu9Cuij6020025Dlf86A40kMdOOs0kHJ
Uo67LV4oN82uGRENwTnfG+rP3tgW0Sr/oSRMs5EHDprKp96SAJQzVN8ctsXfq+nc
0ZBT7b+vBcy/GqTSK83ErxApnNodqhBWBpqHrXmf5fAHt4X8Qq+HZ/fWd/GzRBaC
F9No/rp7qymDGmyeu2mJitMQUd8yo8fnyc9+ZG/EenrS++8ny1ojtadv44YY12RL
wQMmIXhDWyl4ifr8sxqSbjzvhsiz0ZWkpAZJgNBSZUqHfCc6jWdPhCq6Lc8lhZP4
gWBX4UXJ9M86JT8jsF3WIIAKObC/5Z6tZzOMjn6FTryi+8LN1g9FpVhRKcLuloVy
Ddf+eNjI14iFwG6hIQjKjB9sU4eGxQ42x/g+YH4Xy5BQ1yRvUgLbUPM+7rQB5arS
QQGnOHpmlJ4v3W0pKp/TEf47+9moeXkQuRbzVCfiLmGYsV8Yix0dZ7RLGiyzVDuS
aPJ//ZFvey1wQBKn18viOAOI
=U6gE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '36b03c10-5e29-4ffc-aa44-c6eac5f0ba7e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Sd1WKAvdsO1Yrkk5aE8po3wcV8BxXakeJ3Rw2hiw5H0N
4WMnkoufFYTAZp/8LdpYAB+zhbIw/7kBRchTXOazWNSBwBRME1AEiIjaB1nE0b1s
FiZSa5a7o4QwS6PZDi7cpY4Xs7LeSs4q7dUfbOCFojSfU4/UmwUfZgb26Cd7N0L+
83aLSXWF8dc6YerZRZ7AS2bvy/8xrM3ZyAG69bFCO1UF3jLqsZ1iscEib6S4lWI2
vNBxRBten2djnP0S0n4hFZXOil9BLpZI+CgtFINl1tQgJYLbivjaoV/RZ30C8n3q
L5EfxJGPArX9IE3VKqNMqggD/H00UY/hbnyRTkqphrBFIEfJX4WdUCiMJl8+sAOD
nPyZH2Ty9scQ52x589KURTlVZ1qu0dl2kGBXegv8tJkIbIVM1fJ56PFTFzBlRHiJ
/UBC1yZE5BXL0mU+EYZ498h5pbeFy/mT9VvVYDuM5N3vqbqqZcHL5FJ34Slc9Wrb
l9fYtASCaLrihlk9hDl/VAAkH2332NEyuduUPglKRoyy6PEoAHUT/hgj6ANMRX6G
GYDqrfiHh3HNXbtxmyV/WV6T4Aas6wEYS96eSoQlpKLD2Eu99vtlc75CCjLZSXKw
L1PWqVUzq0nXWPPxfb/hmxb1xd6GeOpUrtdCCUoIbpA1QXj/K8D5NQRzX5JpNR/S
QAFmim+XKzVQOJ2Nv2dxv28U9B9obQtl+g8mLH0s7n824fFHgEHAMAGFR8aYnA5I
n3RpNd6rKhtoAuFQz/brGhs=
=pqRo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ef7e191-97e1-42b4-a366-4ec15184cc4f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//WG8BAakH+1/Cn5gCUUkTLaPTmmeduX0EgCf8fq4Ilhrt
P3NP9x4Jifr/n6FUqNqIs015Z/0TW5e2JKUl6mcDz0KpuDmznzKs2jDGua1Ualy9
uF0o7/3nReXmymwjKe70yu6qxMRbU/ZR739eFrbf/uT0wHfD8sO7ejqdhH6VFgm2
VaotL75OdoziOhdkCLMUoudnSDcBUyTsv0rt9U18wbgOGny4tmMPU+ivdrArFFmW
JJZmQ0+nGL1lI9ZYQfQOKyxClLICTT40mxyAtWsWoMX61UXxkWFXf6KsVQypiMaD
mClTSayTu/Cl77PcfRufV+Jg+oz8U4ISAzxIiDaF2kKztttmNyqhTbYPkmfCTNgT
T7xjZxkQMZmT9Y6yKXK4HLi9ZvIqfHOF0Ulf/Ke5Rmv5rTaj0d0vvoCEKt9p5LEY
/qXq8RwsltQtJIkuT5lBEOWBVa9OqhLWknY8prqeZcqn+N7O370lR0Hf3mWUIocK
OMNwDr0SALlTkIULVyTvkqDDHSeDVMTNOV5jmSUpO3t0V1uGLXy3Ub3GF14ng0kp
QeL+TZMOpdLNaFH5AyEPPEEmB1EFHrz+6ROGCTaoFv1eCp1nCcviOxNHmNCnpGEO
2GQf14GpUIZeV9+5RJkU4xLl+rg4cLs/W3d+cJQlsoLBXdB0UkyC2CcMz/+K3nTS
QQHB3C5HbEC8NCKuHKfMGZ+3ItGUr+zTKbK1T4xBLg4Owpd5Xb8Aasgrhj17i97+
nET0QNfYsHtf6eOCFgEuwQ0Q
=Wqqr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '404f6f84-a4c4-466f-a359-e1df82992c98',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAhV+wMOyt0EdFzv0ZhEBwIOUbuJXPhwgzPewPSbFQOu+J
75FBXvpNKpj4Kv4MIk/lyBgbZfZBPW5ixydAxdN9hmEi6MhLRSLScsGaDhGydGTU
YzdPGX4ypWKsD8fEa1Is3NE+uFLypgBu3umaSWCPD6NSZsQpEnkB67QjriTUu3CG
o2x26gaXrM+sFZBaAKlHebQ1oNpgLK99Tt0bBQ8TPXoUUzh+ggku9DPG3kP04OJN
tLbDSx3AJJqb0acfni9z0CzR0e6txd6xXtxPtn9g+5/hcJfOyWlS6FouVm2qVuA6
LTph8cl0XelmcOueBWR5EVv4/8TvQkoBwgjs74EPSegYP5WDJ2pzRSyvj9dwCxGa
K0IATybYvQaUvWzmaBIQFJ0JWeZVSwFUPL6Wh8m5oUvIXFPg51EcUGNpkcGVC1TJ
/ozX+oiMVgg53HAVO8/OM8+9ARW3fMdKXuUTP91BwcmyzlOXEN3j3ie2P5RXeu9J
/ZfPaoxlbpGdl1HwiWcHFv2sFEyPu6TUPiUAR799Ro5stHpzgsYfRQVK6GyI/PnC
PyN9ApWh+ZXhz/cjC/cI6MncSkizr/LObqrQ71a6mSqMxmNZtD68Qp1ZZKyw62OP
TsnW9JqXn/VU16Im9QJeVWSuBqfy2RvajSH339ATsA9m35ZQ55DooA11LdGtN5rS
QQEnMoKEXfXxcEu9YeDankSU/CkdaAFE44iWwdRTyx27KV8FP8CTSRHRsAgnSYUm
+7eNVs/OK6uyuf7gjElFInQA
=h55a
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4fe9bd38-99f3-496a-a09a-89de7bd941fd',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+OXazQ8reNKzRsNGVBTwK02wTGQ+dXXwwfXfYiU2MoysU
QT0bLk08qNQinIr0R8qwF1I6UPqAJ5+T+4/ZAMSlzNRvjGPP80MBpkpIMtgd/zU0
c3QQWcnPo+pT/jL2QBNweBmelhoqxUP4JCnb2yaB67Uh9uIbiYey5fR3g0HohZF7
W4Iu0xyMEnKmcP+E12/8RfUtwGQsku404iv/XeO9Lu2q1F0D75Oi2KIYQAkdO12m
YfLpwrW8DMi/8dnEs+hEr9YDNgBQ58tOpKICq7RlNJjqnC1aQ0J/xPnkJOdGBX9t
Bx+Y4UqsedwirvV+QB27OKwrdD41tkQi2222NqS9321LVbkztRKcd0I2lvhePElG
qUQ0P2iWREkxmlq32FlMGznPeNmdPHmlsY8N4HQaD6xttoruk7gK55FydHm5T2eD
tfIr0Rl53RVLuLe7TE0MowVpEhLV3wByxfGRY7NHURK1ptco6NBUFB1cqrcIy1RS
tcVqLRjU87nvzHFc0jnWMJaUjww+xfs44C1MzEAcpKwWC98s9ByWvQuJku8pXfJJ
L3No3KrVesr6g6M1Ouxt7vRwmCDdtNr0+iDoCcVCgsUEq9nbJbMDWZcSN72C6YTn
xvg6bdNx+5lTU9bjT7m++407GHpMPNWgTS/YzsEWND9XXDvuzs7keQ10Sb5epAfS
QQGm2eSxuL7NTOZ11LhtNnpwuNqk1oyZf0ILL2Yr4iUYuXqoZXOSYUPkzqIl3VRy
9Muel3yeUIE3hrklMg9wKkEn
=UAlW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '57546933-ff4a-4287-a091-4f44fa401f49',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+MLxoGO8WpibADA/PPM26WSHNrLH0XlmojlIBHVEOoApI
0cFedaaddwbiZpXbm+FzZCyzaZFKUi7j7zn+Zqc+l5xWwM6oH7U88Ou6aDsB5AHb
fbq4xpHTBjRp7v+1drEHLFwLeDtPTEvio3LxQq9K+AmcqOf+xuXUAu32FEnzppjC
ZaZdoLqlKvNNo806j490RRPizvaTfk1NyhkwEFJl4jF8dlRlt/Zi4yPE3ryqp1d5
JQCjGaiFpuPwuYvmWGIeX1fu4UdV3jMRp5M1PF8SZWJId24yWHNkWu6S929AQWqI
5YY/nNGNNrnbqEddkmEcZuHHoXwu2rKDGKtLmHabaJKiXkfdZ+0JaGmSTRG7PZ8g
OIi7TIUELWkzgW4qF1KplCFjdjn8x248ffx1gr/dThkevETpOnTkJJSOFwAqLHcv
IJxri+zICcHtDhAvdOu0aMNO0RHIQJBQWk2TnI3dJrAmql+jMpS7URbdYetI8TCH
p0OZf/7AUvtI3GyhqztjVuVn6rh9aMB1+HdUZFwiSPhkJE3hYj/3Nixacrvaz81z
JeFIBN0TjmA69/hy3Ld3eo3VFTo46Nl5mJQc91q89DRYIMXeKqjhKujK5Sih5vAw
MtC2I41KAVwYGq8p+mzns6xQWrYrZ2OKJ4i/5Ju7M6bDt4Dr6koq6rygBn7Uh/XS
QwEP1LABvnuYdC8Tb83neeNNs5V2SmSolFoNYITQ4kprKzCnaonWkX/1z8/7fdbj
H6w2EnVDWemQqv51HTDPiavCvVE=
=omKj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5769a075-0a4b-4b76-af4a-7630444b4af7',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//UcxLWU2covbXff2A+g4UqXF3NtA2906i4/sOGjFCoyMz
QsbABPCXJ4Cf40BGisCYwwdlowRJb1wXebhuu3irWD4/W1xhVK3wRTsfMNTJYSp3
1H3bw6+kD3c0G8Qmfod2qHVioRXd1xlhhnQZBZHcWR/HBUiyONamahEbyVBcoAlI
kBPQH6+rNcBff1XajW0cDwce4u7mT24yry12vbhSlhNxzUpxAAOW/d+pIE8Qf7jU
PoSXi55sB8shF7bZ0XyCyAAX9bLFxIh5b7uQOMXAkgvlBqTC68G6kzL8Q4jb8dnu
HMO1AsOGVcU26EK1fRQ4EzidEft4qzFnN2GxROZRVgMfc9jhDc1LI/Pm/BrpHW7v
svdaqQLBa+pOlFpRfQpki/v1udGnT97ldkeVX10BqKBrafG2KjMPhLSbWi3wIZwJ
OXwY0YAZoT80TS5ghiplSCthsMbAVcpNk8QlE+FM1YSetb752VgbmQYRehjfkKCL
ZNlLEEUJhOHu3L+6WAcTm1+a9Kua98CBvetSy46Et3cT42Ck7hcVwvYjPeq8XHrY
/9/tZtdPE6NLrHRsUOaxQXxZzi1xYt2+1BejZZc7hL7uhs5djRy1ZP8bE4q4dK6K
2/fVMpfrN2s0c9w2C8N3pGUHL6kq9gkO9QHNnZWI/V2E0H84TKfGdNxdlddzOUXS
QwEpOMauyQwHNx9Wz2q79tt1IiyTu1ayrITh6XWjh0Mz2qGiVXWKquXz0Ciuc+yE
duED7OD6ctAgc1E9JBQNl1rmRHI=
=U6uY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5b3ee9e6-1ca2-4cd0-a21a-dd382d6ae25e',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+NFExfmEl6l4eMumnkDEWVJ5jG9E7RKy5bNVhOo67WvCE
Ka6+G+/uGwJDbH1XEzcBBdKKXg7w610v3rmTRQ7oVZUYt2ivNYthBOVyEy4IQSEt
Xo31wyHiJRQ0GVnGrk5EU/PiJ5BSsXK6lxZP+/aI2wDVfsOj2zqBddt38RHNUYgO
dccNssH2fvTS48QINTTRs7tWQo0GTWEDndK28ndkc1p76PcDPrLtGS74fcNKntpe
bdMkBEAFHQLP3tkAIZgHlWH9OGy4DL3lVeZ4lf4w7iM4UHxd0WhzlbDcebn1//WN
xIzo2E8UE/Kfg1HwgMdHr4AVAaq4B+aAT6y+/TV+5tI9AYNCihpteZFmMZh9ELYL
Zt+AsusqQ34Qs3DdUKcB8rgsujd4qqXZt365K65FXJgS0e+plC8eYTpFixA0FQ==
=0r9g
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5f1da319-3d30-4ba9-a953-4b41387d0357',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAuh2JZXTeGMMSglS4qeBzNJDoDSu5+PZas4P8mDfPkApx
4LrU2p995nt01PGqDCNIFlBjTrpQrbI3Ntq28q5BrnzTlvaehApmb2XbohfiFxY8
DLX6lYv6i8eY1bydN0fBSiefFaTKC6Zd5ACOOYB9K40r9fxkiB60hMyE2UCSPyFD
x4EoVWZ9SlSJ+VKjiagb61T5lSCTygSi5XQyRIZTNdn/0pMm6bePFnOEhXckEJYs
aB+cHjecZnsDit97LDK+SXdwMlna8N2w9oLTEEf2iglw1V8cAEy0c4OPMvuZ7b2Z
5e3Osr6PRyO+nfaeH5yHcOekc8Ub8ZzBeIJsIrEFz6YkxsTKG/43ydOsFDMtMJQu
ZFE5QqVSJxfPPNMZkqW8L350ldpKty0Ju/z2dSJ9zX7vqI7N8uktbFlB3ehpGE5G
xsjApN9NFJKcSa1Ub1sA135jD5sQp4xC7nCVwUrWNkfbiCd0TiWjham58ivSKzL7
p9onLohBEJ9Ir3g5w53v6AxLc2ApH5GGCPq7pgEeLD1bzV+w9OvxNKTGmQkwRAP9
v87vz15cWJm6mXLuRN5eXLMnCp2G7wBi6sX/V783iaZZ1GdexTO/1+IEyMbPsqBD
rbi81l4GstDW3krvHSiFTHjl30c0i9sgyULkyS8gaolldHkViBd8YHvyUsOSQF3S
QQEP297KZi5PXx6ap/1YlRmLnNzKHRtL4ygdejtZRXiSIl4Lh4y9+qqwwSTXnO8B
B95bdEgwKCuvNLofMggufN/A
=mn7E
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '612d8edb-f0d1-4087-aca8-14e0356ccc09',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9HrMBV9tfnyDi9al0d3RKBRMpn3mAYZ1/lncIhjxPg0q9
TQp85ds0FIemlYV7cHid5iwaV5IucFasqwg895feRH0ltiui8sh1G6gwS+qjuQ+y
/g0qBFHFPYgRzl9tvGVbFFJ6T/u9hnyaaCFJcNeqdVmeBrWW12e7Zsag9WB9CN7f
/YmoHcfSdYJSh5rkEuoJo6GgN2YZB4oSgDCrPfCHLVVgbqkCzGiqFRonWHEaxlnn
17+bD2uZKfiWpNDOyXaF5NRnlJJYi0t27oEiPbX5lIve+aRP4SKYwDGYutgG8mdH
4kTxHa5Gwa9eZMxOeYmH6qmagaMfL1pwzuaMB+Ys33CUK2ShxGhcuQ/TjUbvknyN
2SlGcPL3iLamv5PSE1k5Qmrf7zkmEIZWV4P0IMRH/g9bkuR/loG1yzYk6YEXylqx
TMXEhF8ipPa45pWBcS3LvKsqFy/zxlcQV6im3F0IKzC2ByISg/t6Xx+AvYbZIZ5v
xJ2PKkLlEvAgyNBdZNxFXt+MxpTlKjBANtIqABESJCbjHYa8xqkvInlYCJKiEpX+
SBzMmM5wPez+b9QxGtwuMMW1NSNa7B1gtz508vRCnYgzkKJfb7Ips8jt/3y3yEl+
52P7fSbrtUuIfYrpJVpk7luXdFSKmNk7oopytrMn4Po9Uf2h8Auh7LF6LtLXnjPS
QwGkhcLiOJiX/cMyxF4wCfSqwUlB7lwPS3Eisn2zOzjIzPxEqA1CnfrAe2aG2Xei
zY20rMshebXrPJ1jxQPHuGzLuqs=
=3KZF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '617abd0e-46d5-436a-a665-95d1be153d5d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA7HvgnzzYBHM777au9R95uk8Hrlr1dXoQPZ/ab/QTeG6h
HA7EsqOEf0s8Sm1ZLRUrAh8Ys79ZHtDfzfM7JaimTM1oGToxJW3Lce81VNo2TppR
iT2mF/lB37WMwTY+ljX2HTMb5LG3UsScs4HcXB6Vdx4c1dUwyt5DUqhM08+ILrVb
JIXjPPxMIYuiwPsspTn22rfbmo3d4vl53Jy0Ed/Qv6uJSfkr/3fjQ7NlW7IphJSp
A/EmWS0E1sucGy+8JqGCSQ4X89L9Q17aXG17cG7is2TRTfb0VwSd6pgVHuQbV1Gv
8KkqZladZry61wSZZWIVl2uXI1GUhgrEkma2yPuSfgVlvMJcRkxdEl5o2gtJY7FN
5MrzIbRJy2Mv156fwdINDkjsplodC1HVDZzOPUcdx3xrplR+nVoBBPDGfGQdTdhe
Rhl2ROrS5JkxcSgQ43bwWkyJWqw8ZtKSwFnbIE396JD4n3uTLd+dOUCMq85HXfFn
UkgUDmNf0lfV0LTOnh4EC8dFJ+ecWFsuvR9ZRJqz2ZDSZIRy93cY/kYks80tICXX
iDn3BSzMNrUaGFa515C3vMaP21EHRrX66IesCsWujjZORQTpzv5MA24fUSIzYvEb
KfINzSJe9XWCRIWyFI1LMxK9pCv1WjmyQBwazPQfEMn2UjmgaVODeS8isbHCGQ3S
QQFsjeewSeMjeB93WvjX2BOnB63LbZEaeEimS5dIAtdGDTr9/I40cuiW4cLWmJYZ
Ycfkjhg0Cle392rv4tjncjlV
=8+A8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '630fddca-5914-45e5-a1b6-7347cf1de2e0',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAArXgT6CczsMnUtkM0xIUQB1rHpCBdh1KkP6/gHx0ol8Td
Vo3nJnyKQJcKcDhvMjT3n9gKfVw6OLO9hmf+G+xS2ArpPANuHABNzSUq4SrPzgaF
hRpWNEvWJIBwLg87DlXDB2IxLmSrJAIdk9dp245ozqnZIYdOeihxRQ9BnDKhSBRb
JyC2eHTS7LTA3EdqgYDppjyEjO0cSBIhfobYsm22dckrk7nTEz3Q2SUBfXRhDPjV
tr7SMW+VG5kgd6rGP9pC4eASnUSY/qS72H2nIbOLgHjUSdb/91UxoYOuxAGK+rHW
S3DkhNnvhNg2r5Gf0YCrczpf2LfHUD+IX7Zcq4WhUSNACyaRXsLTS0adXDF4V/ZM
YD9aqTKoSG9DwjglZHk4rtZt2HjeMzDs0ygOFb7EqZEyrBm9uy+lkvUwTGjStWNn
c1V38ipPRBlE0j7YKt4rdRwDsq3N+zwkGoGZ0Az43n/GyIRiFmxMGeIY/ZvGX86g
moo4IUNXIfuEyCyaw3XnSODpF3U6Tb7+2v1P5OOmJjvmzAaOHJuwSmFHPt5wmmT/
cgTDguDnPuv56doCN9IkQMNNsDzQV2QordxO+GLZD5mqlAEY4dd2WXMybs623msI
Xa8rebI1FvHXXAeZaSAHtG9iMpXF3a22TtVBJjRwwqAurw2pjgMxupUtFVloqnrS
QQHYE1oAfh+tze5IWEOB9cLtOQUzQJ/2MGZOoEecZV0gfnd1k6MYgSSP0MiAbaAx
eZ75QEOne4c+2JmoO3jPl5iZ
=zqOQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '67d7a55c-5a09-4dad-a566-fa70180ffb51',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RW1kpjgDMBS4TBt0D2PDalZVVRZpVpbPnx7G7Wn27JUw
JulrylqLwMNBq8gPcT8clT6Wb7ZVAuCp2ayMMyEY5mtEtQIdoD/Egf+rkjjcMlE7
p1HEpX+HDfXLKfbbNhi/uydnIt45aWKTwIr3aDXA7e4MQMrD5sTjvlXg58CzUMu/
dWV2GHYNaf2v5MICGqg+RiOxx1YocDJxrRDFnqGTlq+E3LDxlbXxHzG8dmhBJje7
gUfIj3MCwzK4irusFtvNDjqjM3e2tFOxuvVV9HIw4CYUFZ0p8iveEMo8jfqORuki
qcOex3SHw5x7AXAarlDccwXU2okRoVDW/Ob1lA3U+GPFFsUahg141fam4m7I5DvR
lVzbkjMXL/62MY4YcmeVhfC1F9aPpNnX2jvr+9jrke3WqdCl8fhkX+bPEZ5fOExj
BZasxazemypstEBTo7Ka2KWb3bezDSAr5WWAA3sYnLtM98UJvni0CrGAY5Lb0H26
MVgZYIxEmaZJYE7fCOmEZOJ+aO4DWPMsVIBnzkHTb6Zbgo2/tzyCUlh7nluyx/bC
4TLdSKc5IdQvRelwUtYPEXNEAJDR2S2j2Fx32Ic/Y1Cgrc28hNRrzAMuPr1oq60v
GA0yKxw/wQGuOUBt/5DLZ+WGoFGIc7XGiPtWAxykPIqklrxxCsSt5dWYNJj7HY7S
QwEuXSpjGeFy4B6SQ47FR52vAgY3Hp7hz9pI2e3yNLAVEAqHPThHlggaCgvChLoZ
WVwHDJt/0xf9r58Z935LNiqd2yA=
=ioJw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '69299ce5-b710-4489-abb5-db2e1b58ad35',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf9FGJJ4K8igNMPbmmVr88ndKH/q0gSpd9kSOsB/LBpFla6
ekmBtbql30u74Ya8apGPIMu/cZ3GeTTHbWQfg4HjBUOCQokb6fudOdRIxqyIWWpD
gFWKPz7vI42m9T9kgPFa8DWmC0wC2WGzpOGY5wyn+k2deZLwdVmFqvFJdlUqfZQD
gO08zHvvNHK/js0VGzI4ESS+LwT8jd+k8rdJ92ygerEYEUXDAM+aR6aLr90MSGNk
rIKlKrPJcNu+Fw0IBZiWtwwN6imzAxe9N47w5buZQl56xFxvUxOpjeJzjiml+NHw
w3DT3JtdviYq+4UnNyBBUEuZ5UisfOTtwjtDIYI/QtJBARlrG4ODSkEz//QHwg5f
OcxGPIcc9lR1IWZhliNlMUrYv/rAEfLr2JYlTiUYKDN6bPsG6cqjikoY7NnZ7Qgs
pJ0=
=F8w4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6a33d709-d006-4f14-a725-d445ff313502',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//Wa5IMB3VtWIdqKvZMHDmk21yXc5ZawumdiCciamqY7NC
EHaz/OWfRwGtnrWalvE3kza821GDacU6zgIpS4bw76+Wwpx9I3eNomlNGsQSFm3p
RNk7FROpnCOihpA1wQ8jQYhxIuY6tkvnisrmS1i1yUEAeX1oUd/EQLSgfrsa1ptv
hz3NiUMl3zNylwzTwhjsgFXhAbAW2WhYfRonIFCuivYI6mhvaYttaVA/Gu37QjLN
1+Rd0nGfVmgfpvYl6aRI/FNxPrVrfIuETZqMra3hMjvlCets0C5U7SnwJcI5M4R6
IX/ecG3B+LpMlubchWXgz4RTXlxe6oTrMLw2s3aP1PGiLugPJ7FnnpV6kTOINP62
FXb98nvKWV6bmfQTzqV91DagAnqfJClMnOY7aXLu3sLMokqMgr020G+CVLWSA627
Pl92UREk7WeFHFYdzV+IISEHD+GEcUqSHcGQ0sQMxe4CEXVLgKETEyGOweh/YEij
y2KRLnmB7ibVnN+ZbbAxn7RKD46Ok7vGhxeY1Y+JA6CIXVq2UWlLf2K25vJ+wOJi
ZwKSUlyjiJkJcHy+J+MZpSF/380evkU+71KoRuj0kOrq2Z0P3iiJqkLWLaBAcIcf
WJG79Pg8pFLaTnJ2j5mhVpa8BG9RO7OklixCUNf6u5kjXrlLGCRH97gnuvcC4XXS
PQEE1hmw20FEHMKGS4CphEkuubnVz0kbdMvUR5mLWcKD6fRqFZ9lRUkdu8upYybZ
bVPBRwsrvSOzYKgSpDQ=
=2zBH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '74b1d154-67c1-4615-a792-ace32ea73ae1',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9GmI2You3CVEZwQ/KkmDP3uWgoSBHFkfGPLU+XTboxk8g
PAtm+hVCIra7mZZ06OFlNGZFAiZdC+6OXfLTjYPUAX+2nA+gDLkD1YkKAQKt0dCt
Cx39tGcyvtnTbSjJjYKo7Ze7Af6R1lUIUwIF3L9Az5L+obQciL5kcG0do+zP18lw
eBhHcY3xG3dxYxTvBgKKxwNSH4zqfwe6FvPZoTVOyrICR2reUmdUHzwrRxjPZPw8
7eIeKW0f/p/cSS4NXtbHG1678OaS4edtuo/nMl3URMsEGU4dmoBUe2/ljcKE/+Ay
0ql9dnq3PFzQmvpC5FrU0Pqd4iSV3+swUpZ9pzh1L2ayO4heX516rJBpXiUEuaNY
MQVpKcd9EPLiSGVAi0OUfgt+E2Rv7P38jrGOHJj9R5GicABSh8La1ESqk0E9bs6J
Gfj7szP3/D9zYBbWl1Mok7GzQ4miAf1R3tBCAPVNBKXOChZa2VrXzmvnF9OBQrUk
/hl6Gw210u7I5OkRNYfzPoM/XZf2gpdyy1/FfAMT5fh8FcvcXlfRSG9TUKIpBnbb
0duaitxGqC/uICmS0hiIsglnBw9XUJtq3BniNmyjYtDh/BxPlkIcE9CpaBFeGz5z
0AYknu0ceIfu/MIqloE+WFSGX9g/o/WOxAB1ltFxwlhQiscEgbdf1US8/lCzW7vS
QQF+cIunj56wLjBoZAo/UC5+TlUOJorUNrVTvAauZmlXqWJBNN8pPmjQLodWf0JS
fdUx88OqC69FAUiep34b4wUA
=FM3D
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '803367ea-16f9-4b32-a1dd-2838e553c94e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//ZptVO9QnFdP9VqSd3FeI0V4+L+MrAmy6VijSqL0PiLyP
HYMOmt3/zWnWfmAyiOR3YlfJlYN471ME+nWYCGRjignWOpRTN2YkkjGdOlh3KYiw
8YjcxM3yjbi29w8dQhvv5gn+O9gW9FdbJjwh0TWz20OnBfsmWmNoCy1vmVAIcqOJ
dv/wnBakRaZOzjWok8x+0eHNVBJ4du1HAx0G/JK7E2JwyskWLYEwqbat7QqQbw3e
B28XWJMDlnWtll/IN2Qm5fg/rhLAo9WCbKL9xq8sb8QPHVfiQMoTH0/q2LGiTUDS
oD8bBsrDahmZeT2+w3EUpkXJgXJhz5ua7iE5UQvCEr9tTQiLXyeePyw992RnZfrP
d5tlMx8naEhbeykCeSt7xfvkPWfMPsdaGv/RUGJb+IyOM8ztYDklesau2eQWeRYp
LQJKkzS0HcNDwGNp1YrqGobd4PZ4rDJjcNyTPsnNbm3ss7f1ZCTBSNvAEu2ibjmz
GfkLvj3EHI3x3cUp3ThJQCf/K9iua3ZMdLbEnPiCkMLeI9kJFB29mY+WC5KxUE2c
TIo0JacFthc2O8wc3wT3Rzm9tQPZk8E3j8JLVoCy8jkhdXi3rij+kxFGQrCU0V1S
mDJEzbNRzBrhUQ6bpRz5QfYpvFO9jYoLCAcSB6Gdaa/hjKwuJ1kyolDSAo1xBinS
QQHM1mQ1WdE4cA8eAq097Vgdk8QKCY5+rRpfivrIFf4mQnBcICkPILR56DMWtrb0
bNRLZmWheh72Vz7yEOyXRj+l
=P5tV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8049efa9-2c02-4f61-a6f6-1f21e8a8cd71',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9EGSdnEK/e8T+ZaTxkBaoe3PwLa1ZpqGfE0+FPlC0KyYd
QVeSCRrW70lDSNO2zRrP0/3mdlg3UJnHDS+mso3lp8IGF+XhKhDkDWJT9vZJ7eYO
WfvIZa+NeB9UR1kcifzNN5ybzwPIoQzbXL1PsrgM8wU1Q4nRqOeekYq+XrxmATkO
LzlpbZDr2zKhUAIQZCJKxL4riRZ2tx8f+SzJLKV+58RBHKI3RTlNqSREVZjVU06d
/1X/Xt/kBU9x/Kmh/NcyVKYmjitIRPWjHtueMwqSuer7BvQLUARFtmqkNq0wxaOS
7fUO1AmOz1s5EBbIfpJu26JjHX/YtVTq4wmsjNthbQNxsrMKboo2jLWqqo53cVkF
R5fJjm8cHwf+en0uQfieJ6ojApwyeFwlPj2gZ+MQZnmw0OHcBqODqYkHEdUuL9JA
V7Mg4Mp3OHir5H/10NQEj6isLz6DWk8AY+i1MJV0auw/3qPJ+rzUMUMYvC5nkuYy
O1pGhmbSEyScNYF1VyVbZ9Slj3N7DNp9RGCPHe46PG1LvzDnHCfUyojw47Q2Vdj0
tsy5/cpgxlIKzI7ABMjF0NLToYMYw9jW9V+7NnRJhnr3QcWuKFX8ISek5YO61K+c
ikDItgq+Kf/mFU5wNV7amRXOLkG1pxzXb200guCjAN5ZSWMEfyj9Q48POzXDO1zS
QQEiMAh1Fv7A1Ps1xyjdmsZFHW9/G5zKIaRRy6EfNDkO7CxWCNgvYka1KhBkryNG
hW+zX/U8nE2SSzdyMG4V0+QR
=JLJ8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '80839bc4-fba1-4781-a195-32158bee28ee',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//QHgYMxoWd/70Xt0fGO+z4rERezL46TjxNP0FyK+thQvo
9xM+7VEtIeYtNnU/CUJxOKdgoMgPFZ0QTosqFuCUCjlVBBlM4Ah2L6bCl5dQdn/v
8zuStnR6aUL8VdAgose0U/anHXSy3aF1nULxuFZFSUmVp4tBxl6idflDYnDOQZZS
4UYXJt9mg9Vjz+fetP57nsgG1gXdPS6mhVYIwF60XMfQEkYui+ln441gg70sJsV8
EURgkJoqwqfEyObIzyYrADORxi7bhiZHypq+h2NHqk/912GPWNAVpjRSL/qcFs56
zEHyUTC8H75gnihkMNHHp6d++4dgm9vUYqB+LrzW8+Pt9OaZUpd623byHwDZ6JTX
rrooZ04+Lw5FCg4+0oISYErrYM5O4diPAVO3TK417+TIVVG5+1eX3M////eoB0Fs
iJFHREco5N6GgPsCwMj7pDAEVrNTV8chcOVvPOVvyVC2LhEUm390WrP1/zOHtPFS
YaNDi52WhqTJivHT4F42lnnfkkBByXUX71c9bdsBk6B6/mUPiYAIwFqmuLQK8wIo
b91ij10zH1zkJcHhii51zeG9i2Xzx5CvDirYQe4cVgh59gdeq1hxa/qlZeCXikCo
nX4T78/WYuWZgItJ16U9zyCf1//EwP6qDKN2oFLDedfJoEd8lh1AHVGXMP/NcsnS
QAE6CiwT8Jho3gCIW9i0AziuT1MXYvbX9l6dTZ2k6JRc362WLaV8N0hnl/ZiWeua
hCEoNV+PR4PxgLHLun3Nr78=
=coiT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '858b8076-d79d-44d0-a422-a722140b379f',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+IYN1AzIIo/jPkcC7jrk7sRu5LHVfZaQoa8RfGMYN+5DS
19LVGBx1pQjLrgP4CErj1iM9AAPlWS7s6I39dyy5b7WLal6HQKiGaQylpgWP05Zq
W+cz69VxmgLEe1nsJIIUWnFuKF/H4SBGfYJAo0H7NuKx4IuEKfKPn/z3/rSdHAYT
KeKX6W4hvnJZGxAQrTdUQ9D/3hnBLhOtHJPfnhAWvrtLgjKXKYNlLzEyJUCXYKsU
K3mKpGdmP8Snb78MBzwFOf2Cx1ACHgMCMNwzkrXgnpwEotM0xJ3Cp9IVn3UdLfzn
mkKJtIFhaA+dTDu7fTqiVNA2v0GPZWrDOuKuiAfHwNJBAThcxaw+RBakOUENm0S8
nb5BGm0IKIcu5G0A72jirTzTGpKF1XWaiqPz92jmQTOqGikMfmt/jCk5/6u8WqJT
M78=
=HYtt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8772b89e-62c6-4ff9-a490-ad9e187144f4',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAnQx9WciLJDdr4P/Yp1+l9I2lIau8quPd2syRcU/s4yzY
9CsniDa1Am4ZBT6F/DkzXYUxS30Da3AF8f21xr0JVghKrYEKZpZR1OiSmDD4DgKq
ohnuGmIXp8Yz5ZomCMQ65ZF0ecIKpJle2Ex/fP3dZOouZuJTW3dpr9j/9tHNs+ZK
SzP5Z8I/ZO1B/pm3RyyNsj4mKjBtOFyVuELCmU6+WqmZsf7D6WPRp89QhU1M3PFx
Jh8AQN9/jymT+ptKapwjguRbDTUSm7VtMjBRJ9Gj3TKaDCAVKmrzDTT5Wdpy0AUk
NX4F60C/mOCGX9uEe9mRPPKqylVqG2dQcKiKBXmdf0fl92mBTeZsS5R9437vuDAf
BDAEflCYiGcbXk9Z1UtJQndQDqgZ8vC4F1v4pZ8cYNwtXV4dGFz8UspGeRQJun2r
Gw7ul2Gyi/t1Yf2ykD0Ne7bmpv6EPj0C4RF0tZ1SYuR/lH1oTdAatpMTWeeZoY6W
++Kupriv2jRG/gPyUW91HTwYn4K1XP7vhz9qIyXHCBDsPuPZAZthQIcvyWEJmJcv
Mb1mwq6xCU/bukmBH7/RhyN0HSdMAiPISD4tmLjGeK95iocguc5l1DTbsnItKHoy
SyraZvuKZkADd0t8tNrVoF7Qj5Ro9jw6D1e9s+YLACjAybhHJC8W9U/HQE0TTNLS
QQFNJcnCN+sSNEjrxncSIxHNHOrLRxO2Ri3tv+ukfWWdnKjmz4C3DnKkgi5cn9eT
dApcmAjDTpPYyEtc3aD+yjxC
=Q6jq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '90041d4f-6f21-4c6b-af58-b23329843c38',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/UVkaZJBVFuAtmFLlQrmcREBcSprMWOe/Ogmbe51ckQen
B3j7pgMfEIvgk06WSAnlfTFxzpIahKVr2fOKxFTKwSeTZBD2jO+dcNI3YQh3RZdf
61E1bnCZRxIZzOfACMEG2rqFroCMlaNnQyOPyQ76r/L4SMIrQKDnIK67p8ELxIQ1
kUqIx4W0k8DVxA9HMsPnZEzNQGWsbCZd0lZPUSbGb0ZIHM/vQvuMCOqTqhR6NRuV
a1fqIDL0YR74JpSXoflZJM0irsBgQ43y2N1ojHoluUbVZk5Mj0J1lZf65VOkMaE+
mc5g79ef0ItRIU/eyqxXheMkmqZFYP92HfHOn4Js5dJCAXIokDyzornTF140Ad+g
uPe9efnpGICxT2QIQStDQ7nwLFDv8Kswazv0O2c31+fBldkyZ7/fVzeSyamIslzR
R5Tq
=HBLd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9567e135-18c8-4aa8-aafa-388a552fd4a6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//ZMAZrP7SUZwORfN4y83XZzAHXvuTt6M9SDAw8wgkcPBV
6yp5xaRzRIMD2MtPTtRJHEMOL4o5/hRDceWLdRdtnpOcbC/qX2HD8CJR99hepN6H
yXZryh6t8kLFyH3LCyLouRr7e6cRP+uyiQvXwv7tENX6Ay4fk4KIl2+fCYrW1J1M
HDGiofprP7XqFAzzm3B0JGNuxEQvCKd+22PrmdoKmv6M3djxg+6RhpQMcmhEL2SC
+7Y8XrNMMaAgtgkzcQqHNPEu9qHmlirrrGPRp6BBVhDYo9PFDg4KZZccZ2Rw6ysh
HAPZ/4ZfKNuyBne7BDC7BszBBg0rv6arpkIv+TYTjw5JT1Vtejqj6ntEopfnHo6t
FxMgu12pCR2Lxis71oKru1Vz/PBwmqk7r8X3J0aKy36mLfa4AhSamtWpT8CyLeEL
hPlV9/niSKm+8io9TGMIWPp2icx/Gl5KRT6EgdiFDDPaKxNM8x1EzyBche+NzJ83
/LP4yo2/FWGRZYKtb4cO9LQW8uxc1nclN9BykBQuZWCDYyR2OhwnIYmIFXKtUnJe
XgkTBuuVzmxNbPzmw2J0Y/kIDYh5aGypv9C79YGsUin+bfAuI2Wn69IJRafgo9A2
0ziBndY/12/T43PsLFZML+eA9EfGbUeHh7pahS8Hr1JPxwqE9eoVhE09b7KnMUHS
QgFJGY3q67sLuup+ZXhCwLw5CRBLBzSUwo64HfHpXgMaeDHWV4dG8zv8m8PAI7rk
3ldsX8LFhkloY2kJ2/q/jiuNRA==
=sahg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9bed5ff3-08b0-4927-afbf-6976389ebeb3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+J8B+MkIuY9KTarffn8KSJizSpi74XsigVN8p98B8hRP6
weqn5k6c6A5mTBXot4ARcdOwFrC5fb5sLhG/CWJRRpucXTgSq6NXKvCX3ZUXGwNa
FxNzV8bqc34cjOEuZtGnGt4l+INkogdXH7H1amp/CSgFUUknCyRxH90PO/JNwtXa
tVHsHuR0VyHHZB0Y2Bluh3sm/3Zb1dAlcJFV+RidgUvU/7gxnHi0LjyHR4xcMi2S
x9xdmBpFkGRPOTEabHOstQyc/MX6//c+t4mgiKtHkY8J23XilaLoVW5m9BGAU4ik
0o0W6RJF+i4H/wUZPhnIWU7thMXzqJ4sEIO2bAwt4HmN2QjI0p6DWmaKtpNT2qh+
D6AEuAuAEzgG5LN1Kxihb3n4Z6hqc1eQnnv1IFJAkHj3fkv6vs07LYXp6Ynf4zge
2Hl1SWVZPPDMTmRmmuOpnKCMCMAR8RqnqTrREybVcOr+K9rJ+wGmD8dXaUIlk+rd
ouOGTvRyKtY6CmtLGNL31lDxO1ft0TII6MQhF5pl3ThJnA0wHCiG8EvuoZj2oTZj
KSK6Ryi9amjTJaw/zfzxrKxAMjflr9MgwuzZY9qSyruiqeJovxm/QVP3Xu+2h3/u
nQcy/FARiCNOtFAiL+tkWIz/eY1vD5eTSx0fyabpTucESIB4M+eWMkito7GAQYDS
QQFV2sfCHLwOU5IpD0n8MITuNrm+hpDJNyrDCQ9d4NpA+iVmRhlvQNnkS/RF7Fgs
U4F02uffDSahH7lNqpm3Wc/o
=ALBF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9f10e737-f410-48d7-a944-aca944ef9f35',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HrOlRI6r0VKCfrwZMTdDMv6hEhXqAlHav30oTeWJbcWk
FguLKqsWn7PmL0HRgmCJCIHI53hL46S2D0TgShy9g3sXoWI6CdV+NjnwOHCmZUZq
qPGm5SNjwk7/NxzE3ALE6NR8eZLIKOxztY3ydayp3FOKhUp1ZnXrKgPA7hfjlO4J
Ukcbx9q4NTcLwHYFdNSlv6lPuBhKonrL21QuWlPG9iPhDUfyYuOYFO1Mc8sNl1/n
r69aOoY3nW0e3s/CcCbCyiJiB5vEl17AVUpQJnzTE4LltVN1xcwtzDEAT0MsAC1h
JkG+iYIlSsCPvjEVTYSt4h8owoiXuu/HfGrnIyEXp5EE/YdJL9jdnHGxv0izrXQy
IqQIpVFTEZmykT5iZYUNb/PbbxTsHSRqmaNaFH9EfkF5WZ+G5vlhCvr/p68REm5k
+RPzo1S5GdYpfljqDTbyFcBkc1/fmuIpmX2+bZ6yaxuTaecxELQStmlHpgn+So8s
C9HiPPJ8VPggK+rSh3aU5MRqtqMdO8Wm3AHyAtxGvNhcRaEel4dCHRNUcMmolaDT
ahTzMbNSq9zetriv966EDvMsDkeR6Sj4R39N+we+Ct+LQ40SPeUEhR8H7A2xNfM+
sr7iVXbIVxA1Acsv32TS+bG53tbWuWFbzb4hXKDB3sguFas7mxEwugD/YOWzdhzS
QAHsxscfroTOrs7jttxHjVqaCSaT4Rx6KJ8jIBItfX1I99uIae7qn36MVpyzAZqT
d5s01piro7haN+mk+wyNp6Q=
=16/R
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9f2fb273-3645-4d30-a245-927ce79571f5',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAlspFhZRbSdBUJDRMjMbi3lGIHXDBIdc3z8RwKvLbrRk6
s5ZSB26PSVQXOqrfjR5+8bIDix2F0LdzcU7nX8wvN0Voi68lWREKU6YnS0UJxaaW
N32fDdzaCAN+mIZsw0H8LdrAqYAHA+EFfnVuWdrT5VRKureTI+I+I+eVIrZwB2ra
Z4YRlcNVONtmxuaWMZI3kD0TLdCgWkAZRC7bkbyKKxFaQ0zYSJwmRxi0Q83lvSsT
Eh7eigNyDsRAVGGAkdttHDpkMHsdVeZjMx44tth7DeXVRxyccDI0nIffrzUL+MDu
KFmC0J8a/fxtAQCJ2DEQIwxzOcPshxR0hFq8HGiXPJOVMN0+zB9ResroF3y9Cay3
V4eXvwDA5jXZOujSzkVbtBYdGIJX4zMlcG9Xg5UlO15X5ktn4wn6PyMa6M4KyCFv
4qwOhMDuUajvhg3xuPrrUoendpd/lh6ew4XEue3yyA8P1uo6upKxA98Ttn98jA1A
GFsFr4RLQw9s57JJTZUu5L9rx8dfav9jjugGoKiFRf4iW3JJoIKNcKAhCuwdyJ8i
St15t7BLwIS8BQT711uR7MRLEEUjYrv/xNYEHROZUfLpVXQ6Rch1hGem7DWTHAX5
1tyFVwJyaPwsTpgrKGPe6qp0MhViTnU1aRnaMISEkVSZYjegwiBVOjsb8RtWqbbS
QAH+jduZhJVXmdqqt9udWx58mjg5vfjNNfeErR/qNHEqDBPLxLhLvj+GYeeYycMV
gHdrL0uiUbG7RS58yM2/O3Y=
=0PmT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a9162bdb-fffa-4762-a11c-5a54729cc010',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//Xea9xn7xUTLorhjLyaiTApQGGHfTomjJ+LX7ZJNu6oZm
9aicAjq1E0w3fmQDevc6M7Y5yXaNYDMkZVTIVGd72elB+AR7GU4nNBtd6K8BPHFw
rgkue4gV5zTraJYNaD1jq2zHBJbkNBuoodVEfchykEDU3SE8CZIWyfVssrYfVQIm
4ZD7kpizoUla5qBFOxSth+JaF1+FL2Lj/ODpiEPgpHWutB1B6TInEWORgtj71I+k
2LoSWIIwWmQz0N51fP+flJgpPoTuYL9sg4Rr0Ej7WXcLkMDLM86XvpIOoiVRqUc3
VmgayZZCsS2Jl8YGf0BVW7Y95t3D1Cvk/VYA7jWXAwmvEiT5+ub/1TJ0WP8xssYH
rppXErq8MG9vjPFchMfvZ3RIhGaSvQxjuvu0eYx4EFMe7MNOwIQ2jgNVaxHD7L02
RIf3A3f6y0AfQm8vZL1BMIv12qM+l4g++Sn32jEYhZqUN3b08rCGXZSd0Di0vJTb
xPoNKg1ylT5k2I6wBEap9hq9nmKp2eveW489OxRQMT/IJpcWtp5KZIM946cskmQS
aveARpQ+VbMwfJkQZYczA8zNPOTHJK3djZErfzZF/lWBVFnbLSsDXa3eWVyO+Mvs
HxbTYLmCoiu6XLCqEhiyUu9YR/XRr7xcDZe9X1pt806OarFpZAlPTOzMVuOJKYLS
RAFSCpgcUPrey0StbU+oNBHeFCSsAqR6+GY7dfxorj/xYFt22ejq7Qz/ooxUUsd6
r2WoprgFr69STkgm2QkeTkCkwQWC
=DQct
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a9caf0bd-2814-4ca3-ade0-1954712e11c1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8DvqB7iX9ccwjf0edK+UR93HyB85fvrCDO2jBzwoyQZ6U
tWt/0XKzQHIxBKsXOPl3XvXzCBAvHjz1xZdBr2KS+jL3UkoV0wBZJ+NsJlhx4axn
UpIDATI73nh+4tOlfh2zQdgVOuCyFdoasarfu9uMK9VDrKMArmTJ2zZEBKcMBQoo
mw9g3VAZAU2tt0Fu4vu/B2K81qAJC7pipIJZc8APO4P9PyR84anmbsZLWfWev9H6
5Hy1LqKvxHB1uCCru5g4+rCScjo3oBOp2UEv4ixmJFNeS0MA6J3arQjxXscPRs4I
4ydW88Y/pDlUzMfvOqqfjBvReI5SkNpn4WLQ7HOgEmqZX2eDfhMEinZyk20XXpwX
ls/E9IygJa6nqllB0rHLy094e6/VsQccCR2DNtktL2BCwLi+vWJ/QMJwLFzqGHZZ
mRXxwq4+qThECRDcTpuNWxU+tDdzgKEAX/Ir54ouClYzAAL8hnlt7UgGya48Btxn
9vG33W7viDNmLbpAzenVEmcawh74QulYn4grFVZcd4Mce2bAab0q0ZLTWEcGoCsA
fLTtm7H/1BUBRt+6OCzsGFSdWlNa98m4iDiig0oz5DwanBG/+wdkrv+hrrpZ8l4y
QQfdvgKW/APFHR90ywI4fv+hCVZzWwptoXy8LjHFxTW37MoT8OOG97u8rL+g5KrS
QAEG/oo/FQhntnp+RuZla7/jXAaIu/PEcq3zpx6EnnoqQILGKcPO3rwud3j0x3YB
3oRzx0YuFD94H6lm5dIn/Pk=
=vOQZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b0f23a45-fc6b-4b8a-a822-9dbb9d3b9ddb',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQELAwvNmZMMcWZiAQf2Jg/QxzhUzOgLC/HZ1D2FREaBCtGpRBFQWWZ6uyHM8Fab
0cMS14YovvDpFSfyyZN6zfJvfCRrCVypy84UWpg4/dWhDpP1VeMLCNAZyPpQmFU6
uK0w0D7ml7fpSmYlX/D4hphAkx6hEAFiVrZi9C5vSpkFuEsfueZ6blIvDaXTec7l
/B8qQxVPKpoDyYdSaVVStDTEnxJxR94FCcgBjHnA5MIzVyAu4unj2zKyISdwKpWA
TVVHTP0sRf3AABG+OScxFHF8XVNyMtzfFYxY2BlBnDUzxbaAUHggqz/QrmFoxVD0
EBFOKmfGlVamc1Oj9QJ/GkYUKRvY7eFx4VEv7VWC0kABIRKcsj8jV11CNQ8M5wKn
vbTeSJyw6VTQuLdWxoOJQRCT6GpE/TmBTsu3vyoGDo5fDDyDx8pkEJBu054QUEzM
=Nedm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b15605cf-ff97-476c-a35b-f9294bea9fa2',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/R98Z92LCx3Il+r/Ai5sCZdcfnfyFRpl1cKLOdIVwg6Qz
oSk7AGu0VF2+qaCCOkJWeX5jlMG5IZ7jI0yXx2x13I97tnprzaSXpPa9Mp5zLl/J
2Er3rLfG7F7/wXYUAEBbD76wJBAcK2INkUucsAvU3HSMVmpYKc/+zoCL3HsA/1sO
M/2Sf775c3jU2BsWw7Zj2XB8O7pTYErmqKXrhxUMURqMXv/RIT/57oqhCx47gCW4
o3z4XLxU6ohGY8juAUBXQBVuKmvI9qoLdm/tsZN3G8SpzTFK5H8hbI54MIG/aUff
o+OqhLS6zBdMaotAksryS/uhbVcRW4Q/yUy2/o1KxdJDAamQBsyN1DZMjMslosbY
QT2geeadzg6zRhOjjdJsyYplCXxLwjuJrRrekOfF2Xl4W3VSvDxjW+R9Z0r/KKEP
tDeCfg==
=AGk4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b353f679-08b1-4fc2-a885-0251f8c0e776',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/R1OGSElF854xc97n+zd7xkq9BaqgXZHwBmaBEKYyLzoV
iIJM1e16LoTLrCtAEMsBJpMO87xPWxjAMtnvJ1w3eMQWoPTNM8ZJaAfwh3inRGjD
lrrv9VsAPdZW7K4zs1Dp6FFsJBXZbDiv9jHS72DM9Y+tvUcQqMvkUcGM5NfK5IkG
KSAun4QurW4JNwpYDt/H+5F/wP49xnju1LaMVdLTb3NORK75U2dgh8SJ+MYTo+2W
ugiegwqL9+GczaBdUkDFmdV7Nl6IZpfCk2ehKlVGzyTgd8vdS53tz6Gkz9fhoac/
Ed1Y2Q9D92qZqKHUrirb8zhRfbcpv4T32wb6/JgqxtJEAedQz9Lu0x1WZuWHpyTx
2QNXBKfMIT4T4eDAVwB/o8V67Gk2f/UXRXka2pXvzfIBScxGHb/WCws8S0KCJgyp
FROrHd0=
=0kSB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b77e1b79-38d4-40ea-acfa-7a550d5592e7',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//Xl6/BT3yI5JFG/HQMZbxbp0t+lCNKxSGx/UxJw2pxbc3
BrJaGm+lj7rtMpLIc7P9/OTw7yc8s3SNvQriiwQmDI7DgpNkE/jiR+cyObG+3Edj
hnqDT3jA8PPfxLavyVZTM2q6ay1+mBIH/2lOVNSraqWBzV2FQdqBSekC1CRhniq/
rAmDrviIW05oQVOo8Xm38KXF5PqVIRuuwzXHCaTH3qEWHY3g73zSJIyZ5N3jPzHH
d4JsGKUH1y6G3tyXfCSexsEdx/bhemOGmdoorC+DOYcO9ilkc5aIuksyFoscSctK
A3M31Pvexrn3XxIiRpv2uBKc5GfSLUXLism13caKOp4nkTOwSLCxYj9SIAt0B/63
y8aELJsL0VKwcG4Owrhs05AvM5muTF6WAxarAnLgKU9PhRCAv4lnQwmq9OGIeAPw
ZFwXOEYtWrFRKLiQ60JXzpa16tNUUR5QHaTkc5bbOudKqJGSVujASZrQ+GVzuYdJ
0GQE0OIFlW5aUbxcl1WpDOCEtJT9jBQp8q1LUzZzaxza1mfkXIpJerRpZIrftG4s
79msH/4d9eJ1rkTtBYZpRI/e0SFqFZaX4ymzeGlWe76L52E3ZLHRS0Xp6wF+L2iF
APOQSgk7eQL2Mu9wVC7MJubUtIR97sx18AxGyjVECoGQttfSySAsYp5sw0ufulXS
QAGeNwm1nWeNEQaZgNjFrK7GlKcyynxGQLGlmi2k/9TyJ+RX1i+B2Y2U89DBGo93
V3orusa2qNOkkjAvjoFKYFs=
=7EQV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bd9eeb90-5299-47fd-a8e4-cd642b7ee224',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9E3BsRQpHBooQKdi6RYV0zPz5PBWtPQzF42qVLUWt588S
wLwApGoZ0K3bKX6VeJZ2QO7mWBytWSPdG8XvkLWQ1uHw4QJ3CwTBAIX7inI33OIO
UIgyli81mzd3aXRZZYdzrY4pwDVSZiUarU8MvGtkJwLRFMmrNBohgZvHX0XVXnyk
pbh0DhxLgpBUDAhEwonGjlzRSeWM074huc3tSIS0qgVrz+ZZN+hhJbw15SYcTEcw
xNlyHsZ0rXFPKiJ2O6E43gkGhO2ngdZM3I1jBlGigmOI2EXKaJmgFASczumOiRLR
SfcrbkVZf/bDjGcI7DfVx7MxIVvtCC40OeX9HHV1Byn0a/yHzVBiim4nhM94PmLO
IWbzTJB4UqXGBQDWpxYC40jVURf+6M6ViTvUryLMN8Y3A2hLI1Fk7IrVsxO256B+
meEkNUhplD70XAOCpYftC3K3n0DczgAPDxVoaKuBksFbnffPOr2IeiGYIz/NYwDI
D/l6M7rbTR3bqHoYZ7kbkk/o9TNfn+wy/Sn2a4b3qBxa+mKWJBRHcxfEg2cc4vli
ZGzrzWlsCohT2kCCO8Z2A5YnPGfw/hqFjWcdiM9KS3S2xeRWQq9UVg/7Hef5bk1G
9KCdqNxrbN9C2Iq2wDUxh5Rlt/7+VFtANDvwFXrJbFIK/9XxFjvKwxpsmiWViyXS
PQHpDd9F9tEdI0cAQxAVGHuVP4JBrl/zba+CkqID15eAjGpfWSNQLpSZoNrVCZ08
N6UavroZSf+5MNEKYMw=
=HrCt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c1a6f74f-c9bc-41a3-aee5-afdd46bf66e4',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+MDZZa9ylmjB/BvLPoZnYTI7Zy5AxKj/uSjiLzfpyjDaV
+18yUh48c8kQNMGc0sDvEszPbCMpNajAv2acc4NWA0QHVWYUzr8PfLW4IfiMfvr0
BzLn4j02P41hqF6lp+h7ng1f76e9d44p/RMLkI9ead7jiULEayctIWEWwENeXrpS
LT3JeOLA3bzNb/t6D9fszy7noMSJbtJcWMJUA/RQkYYv0cygRfLuZ1HE083GjT5v
iC09NknZvaLJFhOi8Bv6LEQxNN0EJHrWvumtFIQseRHrZ35m6V4/77Q26vJ+RT36
6BKYatyoOH7EWx1IxWBl4H5kNlYyMp+Tck9y+5qR3Bk9mJlK9+jfGtyOkJXwp3v/
l6KF3Ftary2YN7WtP4kgRZ18MU2CRMyk9cvTg4xCeCib0x8QxGlE86JUPdWJ5kx+
imRUcjkOuNvysnObUXKKQbjz0LKI8E2iV4pTbiAo9zl8ZLidfe32BMR8+rHdMwUo
/dhfeVjekeF4+h9Di23/dbpJz/Q7Dc55znuR39eECqPX/3VJh+PuY4qXpmyQRNIY
za0o922BwlJoJeeOo2J2n5WR6COO3fm0Mk9MczxIf9Mh3M88M7VVytoOZ464sXvV
rz8czRwOegVkbrX9JSualStZ5SqWRjpq9RLgv0IV/Qechd3P0aYL7ZwBh8YgaNnS
QQEV3XV2YZHAc7jKYPbaNCgvNmKJ51DkBVlek7wRzC3+ur0KM5zZ3evIw7qyuW00
1z6VnZvtMOUUCWOpFh53PHhA
=Hmri
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c2b3de5c-b9d9-43a4-a1c0-af608a0db84d',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAvscbJlUX9bJGGrQ3CAHH+hldvQ2tA5XYPI+8IIBeukQp
dhmAaSvXIf+nflVcQuwi5sdbxnGAsYUo9ZHfpRHZzA2C3nZJJFKHwlc4JKeZDFAM
PPArlX27DCm1q6cMIMiKM5X8FdNNXqIywxAd0B1Mz4UJs5HeJ5/6sejsRWXup0N3
r9Rl6ooArmaOiMfnFnK8UZz9nRC5cMFjhOzwu8BMjeC6UPvMJg7ci3FIFm925J1X
uUV3ef3d5jxYTm7famp00CM+iapj2kK32tXm27AMjD8xP6StjVfWKV0ptc/oHQ/J
nbdulPdWbDGR6ogxvdjOCj5T1gdewDtwTbcsH2F5LrRwPDCZPHO01lfsZY36zSMW
mwFHW16lLWI2ZLd6lZiM/LfKG7calVA7i7XMu8YItsfYFXLZI7+xgyfNDY5bDzE3
kXO0njozmbL463VCDCkgir4firfumdYyXJEEQxJxAV/I2Bz4IWj2gykfgfKJ7O4W
TB+7YVAf9fpHEF3POKz6rmRjIdycuroZValQ9EhFWuAqhvx9Wt6njq1p5wkh8ERL
GV99IUbi0jTOcXGzl0qoEocxg0dY82IjoMvwpR6JOSK/Z/xzYkI/5WcLgxjp/+A5
zfkOEUQ4iY5lceqeocNPft46KSCGPc1LzsJNIgSee3ugxmPnDyJXgccAtb/CfunS
QQH3Rv8BnkjzX/E5r32d4tEja/n9gFvcdxDRRH4FVLv5pO2+60PjHXU7kfps5A+G
6NTlsciRgZyCLOv/H7m/+Dp7
=BAh7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c73c9fb8-ee77-4f28-a466-de9a041db15e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XeDadJR1QxkS9PxWugf1ntJEdAQXTRlQkAn28ApyS1sG
FkDQ2pwX9zw0rx7Wx+uoRyO+3JtKJZFox3LUtJwx1rX1+9R5DkZXeIuZVWzTWUWV
MLAJXThMClDyVN7/jtN6sj55IOrYouqdmu4zNJypT2WKqvjLLaP5Onertu97AsPY
XiW26vKy7Xe+NTC3Zy8ghefNXb2fSXc9nKQyZJAt3L+Ez0w4OTtohSfy5XrhHjs7
fHStZ7542/61AvrnZ8wodWdRFwZRgn+jDINrA/SyJmaEJnVNhrtopz04EkGVHX5p
BVySVw6w3gMFlWJ7L+yyXZgIJkCh71neOhxE3RSUQgEtzeZqwYLfZhAgJFMeihl4
TiqnCTh82wPRTOhLgaMoYfrFKasDrTD2ZxjXHCBGMhRz/feSruyZ+j4M9Jku1CvD
1GlF/ALdWpjPrHvjgzuiVO/IzZmTSVd93LK8Od0OI+8FNmDSeNP7FbKwtqsSzyrz
EOsbwO35IG+6Co6FRcHEIjZe2TpcUVH3eoKl3D90TG+HSfGJV6UxykesgUOl5NX7
NDIaEi6gn/MA7YvDB5GXzD8MzB9J/bnNQch0ysly1WQ4oKg0nkFLboV4vTFzkntz
ryVpiDZ7LzpAlV4xl3GfD5pkrgs/8lYalFhczZoek1u1mLarafsshiZKm2KaSA3S
QwG+NlbFGh1IUTD2gj1hRhHYqj0hdlduGYA0jdtg7Ogc49+0KxI5XxbweH1vr9DG
qBzQ4HNP19WKEXrldxB63HkKWg0=
=ImaZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c968b37c-deb4-4d08-a5fb-bc75e3512645',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//R8wTWRvqs1mRN+Xmzt0cZbZzn7DUDv2OMHnbqo0DHkBp
u0vuxUTnfHtUFXoWdMRspMpeWYcgRgo4pEUUNVrlbypIiY+c8vaA+3w+o4brt++X
Kh06tshfC0Bp64LpUSY6J/Qic+USrsXoZ0vZnCkIsd0dvvfJLn2OPD1Yo2o/ZR6C
MupMcszsIlhteACMeRDLb8WIDo6ubp11hxaRG7SFyFyQsfq6bh7N6vkPSRlLcR5V
iUNu2gQMG1ep6G5kwKGpBylO4YikS0Xna3rHyeZuaSHUuiH3zie/zT4nEGBHGw/F
sLIqpttSDjSu4e9bjU0DRlLNl/s9yzttb+4PX8y7GxTkRYXkmWk/J/snBm/jLuPc
Z3rb3d1xpffLeY4GQhj2Ax33CKMuk69npXh5BGqIldg3Aq8/tleciD+qlOTbJwOW
//K8llH9p7s5z0eapyFUGMuY7oLxCV7Rtw8kWQCxNBSvTN/rd2rwAY8D5pF6+k1V
EBx8NjbJbGXZjIyjaiX0CcOjduBY8IEODt+XAIecP7CxEAAdtfumb+qYdvbo6492
Z1zkrXm/YsMn5s9hxnMezevH/iIzNczXkFxXD+rIcgqLQjCLJQCaoJf1I7sppFxs
ectHv5cXME1sO4tNHTAZUxTM7SxFYybVuv6abEJ+QQrl/wVdJjIVKayMgzdGjZbS
QQEW7wIIEFf3R22FZ8wo329Bx9P9g3mOlPq+qP0eoTe9EZ0O9k9y/z9ZjjuRLeD6
U5O3/97u8lIxxu1ZeB5AHqtD
=DGMj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ca9ce131-748e-49c6-a4b8-92ad1c940fcf',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAj373ayUzvxFROOfZtZsH6gWAm1HVQ7vU3WUwMk9gJxbA
8J6kkdgsuYC0ktfJLMygrJhiUuFdo5pRq4/f3TjLhniGgVVFwZ+5MD+K0TO8xPX+
FDSCWi4RrQ6NMUPScw9m+3M/XvfeLBiei5PEWDpKoZ+IBGrnE+vLhcxyFGrasUtT
zv1aZ2nu1eqhVcJRq8Kt9b6Xbyu4JbuyUGLV86S5PtqXljJeXsJdttJFLzjfE4yU
mMpQqUzu6hgaS+32AdLGRgcrYyZj5PkT7lr9zvlCCMgdRekUlCzn5quYzVZLNrpN
M616/CGnDZpBd3r5rQMJ27G5073/B7iDbbH7kPwp1R9ZVKB7AUV45YA76dtIu+aH
5+G8MvJZyWtfNicqCuSAvPIiJGPz4pi+5HSB/Qk7w8KjHh/bVrDUaD7W13nicquY
M9t3DDFOd2+bgu1awtOemu5disZvVV0qXBAzf7QdpB/z42Csp4KKSgmUg8TCtEt7
4A9/RXA8xWIcHOmxc8oH57JCAaTRaq4JrF3aHlYtOUVTBEq3JoErVPo8p202RGlO
+gTqrTL4YAyDEnRyd6dR4NlsqhBof+luTf480w/R2qIpJhHzmKq3/D4VreKjdG1A
fFDIvrOVcTozEdlQev77NMRG4Pqf7cVXimldJdE8EZA6JZnPyjaxBAcN8jEo1yjS
PQEBa8CedXyY6VV16EhIA+m/caUWkUdE4hZB6vCiOQ9nsXv0YmwkDK8Hua9uRQG5
tgw0/uaowlbZI/O5chg=
=zInv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cd012154-c964-45c9-a328-92ad0ed83230',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAni2i8enhpUA0A8MSPUYCBHsmThhSSCPzXA4Zht9rOY1E
G8799yf6egB7KqLXbJ41yH3xOO8QLxItVFcvgibCQrLEKej4JmDFuvEYBFTLgvCj
kVgTOJm/ahlFDLshM0XJ3CRjmJIUz6DXuATN/hLxuWytNrKKVUt7qMkBqIXr2Hu3
m4H52vTSHEeFmHF6uEDkf9wjDbulmypB92Aqv2IR+5isaXCoT2RUUKuFx6dBwmn/
cRjmJ63EF+XBQ3gD/Dt4G9Km0lEt3gommgAu6HmQAnh/Jk7TpWqLG8sn2cAPrKQs
JeLnP03Kg+Yh8TgJYHKABE/SL28l2sLpb+KuUXeDZgO7bawUUGAdlLz+uaI5v5zV
hel9akPSW9B/oi09FzvvDLS08i0cRkg+eY3l+UJTI3lIFIKhwFUHHvOmCBaSGorB
BBCyUgDDxZJMd14K49UuY9NnpYHZFvZ39StPXo/1LiJCQcdCYBrt4V8tPkq2pof6
/8K8tuOPOqmh1CBi+ikXy+g1fbcGpicDBczl21ifVT07Sk/OHv83lufT3JVhEIyd
Kc+J44FejK3uDyq0SjnqMm/soLvRxAcXILAMlis5GoPnilbceXfo3RGUYsEYBHcg
C/dEZ9O4q30n9Hr3wa6a9wHiA98bTdwXJDJm4TFX3GmV18Dzi0G/2fKwfiK2AMnS
QgEpCCZTXfh8IgwX7OOhn5HP/4PZCp94xEh7rN3r18q8IwcEtmiyWpPb5N63Z65U
ne76wZg5lfzi09JF2yoGhxPC0Q==
=1ePB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd2e1204a-9a1d-445c-a28a-d682b0cecc6d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/7BaKkQwZUwpJ+2BDl+3jT7m9dHB/c/x+VVA6OnDv6zph1
24xN4Zaz4yi4tBCXSm5i16GbqVAuhgxAX6+HNyAIwR5ncY84FqGDSUJWivjCNktj
b4DRbGIq7dd6hsRI2mnblbnRvg+6aDm8p2jU0wVaiOTc1cV01zsZMm9On2mZmxRU
yk9ld0kfFwjud6Y7Zr/iVul8fMi/XgCOgEaxtIiWExI1orwatMmz0Qq0wiG83fWj
Ixa0HoqU6w8Pc9im/IF9bvWPPrKPhihKIyJtAzuDBqxwTQOhZ2VRTqiL4GZv7wa5
nn+Lsib3Y5zHHy8+iivtgV03pYM2KKt5DEjQzhJZcrymZdosC1tu9AE2oR6E61eG
dwRI/6s/0kQrdME5TUF9aSpSnT6346WPC4zQl43xd8+tv1lI9Cd9j3xbAHuWq+UX
lpd2X08lMpG7wW+sU0ObhUsMjYV2Z2bqX8qtU7FcN6HqY4UpyWCC9mps20+wf9HM
/AZ3JyC10/U0Vn4GLLLvhMKI57OO8606N/wXySOYNTPy4cTauUJe5rprWJCUscXa
nQ80E40z8OD+1bxhLMGSFtGtTUOwn2vswU1vQ8ZOBYcWx+y5f5o3YGZa3GrNfK5G
ENnLe9QWQFybaCS5M92hV1bLpA5n/3f4PoafatwL4MM71VXIKxLMZQsrvPIuKV7S
PQFAKhyTmZX2MoYcQyHhGsB/U2OnlIsGJChBN4pRcAXk84JPMCznNE3u9lCHdWQ+
rmprWbx1Zkz8ohVDoio=
=v49j
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd6d7fb9b-c995-472e-a912-fd1d91131a49',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8CMUTm6orsTKpBIg6HM6nYF/wH/jOlIgDtGihK+v+3AIf
4GkxcTTiZKW07Fa4wucaYJD1K1fCpqSba1PXazzKdFvDBqd//uuubkQ9B8tbrxy4
XtbYfJ2p/+1JtVCBbfLiXMB9BfASLOsEyzrDNI3hNCiwHSOfSXyXycthiXT6fzf9
mOVYGxzIqA0OrgOuReCwQY4yD8TAeM3qkftL/9gIl44OLtARY8VzSnwWRoO6G/0m
kb8EPi21JWlMIigX4dzCWugNmb6FXgnn/mYXvd+RbbKBBXWk0VmzIf5hAqu4LDo3
DIKxqB7Ya6Ywv1WTbFcwujGX2sGKXbQolh8Ka6p6DOFgbI9rjFe2AXHjZJY42eD8
HrQf4K2jmmKda2UH3NIOwiykO4ZgIF1IKpc0NS7E0P+bG8BmEVfMb8Sof4irmpjs
/zowROLQa5le55ZclwgIVYwHFdtuTkJ1vwwD8BiGStMK30ybVQYvgQEoagOOZI6z
Hf9MPIdDDiLqB3o16JKtDkqulvbixw4cKIhb7ER9XaQeRpaGADlpLiAj8CmAMS03
91Mg7HH938eKKM6jFHfw2B8ucZmjN/gSL7KT5iwrcfEIjJWJp3elCYHpUJgldqE6
wFNfmwbleMPlJKb8nnj1fKkeZ4qAQVray8Vnp2tSJGiTQDG/3xR5LiRB8q1bgD3S
QwE7k0GvXdvWdSjb9hEhM9XXYw1MU5PihNtIyumss+szqkKT+2jLRbidExRL6+KD
KO9ROQD6LgwSigCDiAHeGNP8mzs=
=8iA0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dc540eeb-8920-4a44-aff2-3a06238f22f4',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Y29DzbREX2hADPybPBe/qF/ilg/tI67B5jFFU9l3ano/
n2UZTg3AOCtm1wl6EnH+k3uyy43fSDQhiOU14GYqe2ib1eDxO7zQOvGqmsDWT5K+
dSw+rkMOJnt66WKKOits9F+nwpNwqSPKTRFCGMNzcCLVSsHWebiElV4DO5bzpz+I
XJAkbVPA8M68AcoYit3znvFiEq4pcW1QwauhexWs6AjMnhXtuVsmod/AToERZOB/
6LYK7hYBiOAj5SyW0oIQUHlbXZCbX/zHNupAcplZF8/jS7Jlwe9BT2Lv/KKnfdOA
/fmwm2LhuQU5v+0Pi4l8/CynrWGFO5uxLxbQEzkKa02ihyusUVbadvWlnJ+2PLqN
nJxkxMBFAjmgDThX33Rl2vPGSKw3qzM5WU58rm1bIZo4T98k8/BOZirP3y4VbH8C
h/3qMkdK2cIA381nvORHrRYe3NnkF6mvV0AyJ4AtUZKglfisiS0Dkviucjnp62U7
ycOe7AoFZHNfwJ2ICf0AECtXizNBeweZu3Aw+a1qX6AnZAkwFgZ79ZtksrGFsl7j
HXP6XYaFAHMVvT8yh0M8neu2Kh4SXHRl0DUfr3DPeDZt1Rk6vOS+LJ6SyB1wHwgc
cwc3d1o3oU4UBTS6QNHH3ssxyfOXsJyVm9VAi1BlC1xEpN/BvO4AqNMfwb4nqLjS
QwGZML4asAofwFIAr1ShHJjY/e12Wrd3/vDy9gu86uDYYziptOZ+UYlRFhsgQy3C
9FB+qkeUb1FLdkwAyu8n9uwx1vQ=
=djrC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'df08f0f8-04b8-4e17-ab73-a2ec1fb7a1ab',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/UilS5V1uehFa/8A9UUmCPzYA3JcFeBaEj0LJsmY8yfrs
gPxyYz3Sfl1LvqjyIPkc8SmVJaJTjhWS25BdlKQJ53/t+ihtQJOBBuKzrM1uBBAH
xdSQQ1su7eoUBG0/bShHZJIS7LcBUhqNSQD7jhNVw9giFfBonb9VmN+jah8CIt4D
egtUgX92VAhm5u8MnvfuXOLPtD6J2UJG2f98AtjUn7jtFZ/ENXhnuNVoeENkd0t+
YdK/Zo+XeSTIl9JpMes3VvKWf1lHB90CuWlJpk7JX1t1tljMm60D8dCFoaau96n7
+cxa5nc6HfKsAsH+m5wmSlCbzEv2s0koXLX2hJ8HetJAARkXtoPb17ltMGqs3U95
a/uURCGbyN17DGS/eeraawsQhr77g2zaZUzNI509CdpUVQ73mR6kxCrmd3x1befs
hg==
=j/b5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e1815241-9870-4606-a725-2c6e417e65ac',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+PQfdKpUV/Whb7g07y3TI4BS9SzMy6i4vwLA4qfb15yzA
bSAu7K/xiIjo9y/7FygBmbWC5J1ZGnS1X0IGq5gwbjnJGMubaiyzUykXyj7f1dZv
wCeWkXP5qOTFOFHeO+yxS8RgI/25uPOsGFHOJd6L19G+YVbUP0qjNbIAONVW4cNe
jJ1aXzkhwmr1BiX0vfKG8KZDvnstiQatE3sZBRqfF8AGXdW5MOAjurVGEy6sY8ZS
mB0uHspDIJ1Z9LBg+LZO1Gtiig1O2MyJpsP79XDDR7tv/LRulDjwpoLPrtUtotI0
2PIVu5JkevpFkLA4R//dSE7qnfbc6Y2aMnvlmPVg1Lkv4H2pzDvaJpxvN87yHzec
2QLYMnHK0kAd5RuU5/saLHQusaohIUn2yAzkTRQPeasQg9202Qr0g9xIIcvTLOgq
D9Ka2t4rxxRunRKEZVdtJCdt7XM9pQwK9m+BfmkwG9wFa9ybg2KRuPBqC0ZSN0Ay
DXqHdjKMsHGn8uV8S5h91RSXQnUJzUUkcV9EARnobsQcIvu61wVvteGCmVreeqmE
Dnmx58P8wfaC+0AI82NmOm8jvLiQBmOHPPgu1gGtYTN+w2mi1igTICZtIffcAz0q
8j1gNONRM9OG1Co3tMA51Ei2PB7E3lQDG7PGX9hUxX7BJFqpYinTZtlD+awkWBnS
QQG1yjbWIcLGQhGrJfXXVWVUlt7hmbteNmEVMIemf1uZksP1XaXYbWHOrpoKKeYD
PKOSbZBVHblUpMsAUldSz3Xa
=c80v
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ed8ae128-576f-460a-aba5-e94750552176',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAi09vjMbcFMa3aDgXZhcHqxF1j74BhQwDwTC8EKUKVns4
DZhJysbSG1w2mbX4eC5JZUxqYu2+Ohwwd3WKtsCaimxB+joiyITNNhWcl4n56CAy
oOSvX8+jBdvP8DSQ2cF1tn2gP4/UFQY1OYkVz+T7luNQslahFk3eCidD30xMn3DA
cxqCfwlSmQqLaGv+PjKhD8GWBhnd/4o9Pmf9jmLeZ5nJc+ZHigHJmS2SNdZdpCUJ
sjauzl+znMzIw9pHll+UjpwXgKNyLyDexrs9ewbQXO8Sot0ff5v2n4jgke7QaCC4
ACwXqjVHOmBmFYuIUuyWEnAM+HTh6xyzxIluGWksfNJDAfJCzIzmWDvc3vE1ToYX
eUQh02nWAWLjQlzhockNj1BDWrerjLTLc5sj2M4jaKdFq6yqaNJbofk0kOfVdGOe
uz07fQ==
=+d3y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ef7f8a33-c96e-4145-af80-8d5a43888801',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAlCV/0HVN6O2OZPsDcqtabXRge76TSblImz9NQjEbCST4
DI41hwcNHykg5GA/wz2paZLrjqY6S9hfWLtcxMDGmBcujT/3SyjELsn1KcSfwGQ4
BfEo5UHMk97gOLdPS2vcr/lzEJ87OELgOXyBqd1eWq7mc5TKxcSL2hryXg33pV38
Uq5MCubz0mJ9PveyfWIuGbUimiJVvLg6n1R7dpfQAe8H4IVgyoUZ2GsfB+U81Lth
pjfdu+2F4h/lolDaFJgEnw5WOgFMp0BNXaGuULSC4CHOXB0LKbLX9Tt50jczL0iK
8qsZgFDyUbNenYukAxHkZDiNhgbtrTMI+AjWcpO+jaxCeptJoGnNmkXyOsWLApaz
SWg/wjWfGDk/YGm9TF+ito/yafgAb7r15iDDSiw6xtHADk8sZlGuDAes7V2PNSdA
9Gl08h8NhFDKfXTzJL8oxUaFLJ/8B6zu3trpP1dDpndyrfmBaD2Cr3pQhLmJeiEP
wSVa4yLHoQs8DcKun9P2io4+iBAP1SZrGDIHytODS2KhgsoRg4FYsU0TFVrfunrU
UabgmmlGez4CLA6MDY413bY3oNKOamDF2RbLYMS1QM2t78m+AEEsfkdd8GYtl9Tw
sGAJ3qgl76JFerPnKs3aq71L/MJurnOs6WXn9EnbII/YepUlZCfUzuLcDUgBAHnS
QwHxrSMpJ0hG26B1T5qplqG2yVM4NsZgIMEUh3xchQYrFjb9Ln0r4sQKHAPCWGDu
lvTz7KnVhZHMpWjfrzfGiOY2SOg=
=J1p8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f0a16437-15bf-497c-a02c-2895dd9f4b79',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAvFl02u23ZrhYhMPJEckSeHRSjjjPPTFbKGJWpPLYzQ76
Du1mXIx8ilwd54txjlQQyr3gGGME1X/dbbhgYarLAZ0VJY7vO4/fbaJmGztpbpre
CfHZ/jLTQNEyRjpaRU5QKfRoksB0H8jcVRFw8954f4NhzvoCgF24AZHH2El5T725
kzcQHsWbhjp7wv0ePvNSB7dVkJZ73WYhkY5+yEwPwdG2lLOSDFRMxrOnuOWCkio/
q+uMRASMqi6/APCYlqwh22f1SMnY4xigOx56h8tNS8OW5dOQLF1BExjd9ZYMDM9d
NeNNwoSpQW81W2MlogZ0gITKaLZWU3qdV6WqLK8wq1h/mAi3WH8QLmHIuNdmx/Te
nDTlkJ3ut3IEUYUdWPYyhTvTC0o3s+sTTiEWqRC49ulB17jEY5/wdu+iEwZIv3pq
YhDFxmre0nB6gg+4P8+l4rjJsjk5YEqFKCt6BIP2dryC4btlVA4DoKfz6ph9hQBd
OY24Rq8lxEAnTizzD8N8QxKDss345lb7UIgjZWARin1ZtgMpgKOmISNv2QCPC6tq
rWmXHg5++mbw7+MHQWnkDSP2xNEEXBv+MTTOEbnhF2IGD8yNHJzVLIKwRCCRqRo3
ypeN9InoQRrgLPr06fxgZb98NQFz1sUE/6hc1PitpWtU0qV3fthMA3nYOoXWU3XS
QwFNE4dh7qqeut14qTTnhaaYU7zmBHgJckdHTex/J9o6isWM46vH2yl9PFP37Ogm
1sgYiP2xuzBXfzlk+Vo9QqUEfyY=
=/8F6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f230f61b-5bcb-408f-a08a-59feda537dea',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/5AXQL0zwX0978lmof6FfSGtH+B5ykG9yiOpPEDVi731sl
8UZek7HzfdMIobnMJX+ucCdinxpCATbjG0VPnWnBqsnBDmSEqfUxSCJdwDTaT8NY
AbgSDL4Y/36p3bQnX072wdXLvWge2e3Ry0Zj3pNGYh6Y2puWwYM0/7Fo2cGBs7+/
9Db8RlOY5JDD3iOc1fktJGSJYI0dzvDnkv8RAIxJWEfDdTBiTs/GKkdIPa5v2x0m
E66Znf8ky9XfFYptJT9r5hoffHHoBE1aPfbrJxnOSsdBMOf+IY60AgW8lYMxgie/
nSZaizR3RYIDqecdct2XweiIkye1IliYW6HUkMxwfSeRn0CvneSKq71J1Hraqu0X
Pxp83/b6TltkldTsDYTLIysB25O+yzzdSqGcEQZgzDyOnRbRG2SUGPtsTEBYi3TT
9HEa3KEX5TZhR/Mn+inBiB7PGViNt3ZpvXENJXZbXGsopxNgJ2ucDb6drhKgz4Os
8gNfQBuz3Mm2AsiEMA/tyTdTyp4L8IoZYnok5SROt9gj7fkrlVX9cvKPdU8JikGJ
W44CWhxCWVnWLTr4RpD2kpwPpOj34PuVJAqBQSD68mMzBROY4zSz5+1SwWf9sV76
ToOMJkTohEVBTFkdo1ZnT4WHp9INysoprXNaVytC0tHzIMrAXnAJJV20bpz41SDS
QgEfDHMiqdvY3bq5ky2aTAF106vrLzpIp4lETpu3n6gI8udGHU37VIQJ3iniwPpZ
zHuF20G/qarBmfglGRT/Y0qYKw==
=3oQn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f7830877-4beb-402c-a48d-48f9175f2bd6',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+J8CafTCjEh5CyNFlU57woEQwTd+WGGeU8S1yLzF8LUye
f5ixBh70LxxP/1bgITk0pXT66yNjEJnDPLrWtElIurPBaRqqdHLuxKIHxvKAB9nV
iZ8cS1XhYnwr4uy+FNbWyaVTb7Ct7T4O4uzPc8M/Ghu24+LI63qc8GaB0u8V83e5
Vq39Iukf/zDgLARgGJUkn97ujL4d59Dac4rnqqjEwu6kHlLKWYVFkwrm0LvKmV5p
zTuvX/QGfRhuRaJS17UhOB2kQlC+bzXprCSn65zUJ0xELnjIhKTYhTOEFigQZc00
W4XB5gRiXwu6FewPUInn7YVD3Wq7LX4s50tVHDp5eRzVno9OwcAkgjSEgGLDiWKT
h1gz5GZsrdwCcrzKik64yiw5UmUzaNfS1YtNqGZ3P5k5kZDJDx4neRaVhLT/cx1N
UjbftEUbf6UW1KcrJLobWw4hq7oMY//H9c7oDMFrO4rE95bPeG5sSxWvaJ3w+Ig4
SHcw8cuL9uCxXKYTIGqt/5RLlFBRYldwT2Y+Lf7qfHe2t3Cmy9qSODccs0mJeROM
JMGSAUnyw9IoUgiK5qeM6W5cXjvmjWXEChx/SawPYQrLK52o+qyZ65O8uQlOhqoD
/Gi0IcFITUauJBpP4oLYPnPy4gwll48bjc0fjbBR8IWeDFMhrpV9pCsNFm4dppbS
QwGbISo3fauD6YDor1O9m7PXUvvp6j/G+/DzyFLyivk+l0V38v7k1vMHlV+o8vRH
PrInUPeIqMJBmNTzg04YG71Pme8=
=cRrs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fbe1f3cc-6599-4681-aedf-8df2467017f7',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf6A10cA1ehCLEKa7oN9/TdunB0swiGkCr/kzrtOduJ0Uxx
rijoU4QNTc0mM34EVCWuRsLYsZk5HkDr5hhZxnAhzjral3ufg8eMki5JXod6rIzB
2q6a8NhamxgOhtmy2cF33ehHWk6Gddk50IlYDf9sbqT+gsBp3KW5/DlvU+/iXcxa
uxCnBxloyyPy9QwkE4FqmIGpE/Jk7KmPnOEnb7byEJv8+7D2CTpIeLg/rdVVAJjs
PsHyzXvjJ0/iTPqzGlRPsg8TnSJ7z05ZbLMmW/ykiiPr/HIfX0kJe0QCvb8/eHSO
kcwDSElkzlsvEQPtZuc9x0tTZXo6qJA+Aq7vgigSedJDAZPcTkxo+km3rXYAP16F
XzjOld0hVPQyXebxmgzHLusmZ3YzzZg225voQVeqakJM+w63Y8RfCPJjIw1TYGqI
1vfZcw==
=YAme
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fbef873e-46db-441e-a366-fad171ce1b52',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//XeO49xwno0O6LgeF0Rsa1VCrbOOY4wQlYVrPBdlEeZbq
k2wKV3RNcWyYTboSWJok8LvNObuVf69KvXaaDRN6F7mhaxxhZ6xoXvJvA8LpO/oO
RpLJhOw6i764SwnoFxhCSwigM2PWJWRweo0AKWNHswO6PGSVdI7fBZDWAwlVhg6D
tx6R2Grt0vUGYK2QPzaljb30MhfiV5eC7J1MXPcpwW337M4m782sTjzJMm/sOmwc
qHrn3jDyWcEMsmiRq9Vns46K2Nzvj5IYTYAfe1O+jELDi0PzYCsH0SbJnfBt3XYd
b6QjoXDhMtJy/taJ30I3ocC37Td7vGbZBRukJtul4q/vhdZd2VK1ZR9JFWi5YQ2q
kXRjbxcVM2wYXt6h/Y3uIJgJdNIKeUW7fyfpTVRFo8xbSFrHwUgXnLVU6G7sjVbL
3iBNqk2zQ95J6g5alwfY+ahhnEEPF+4euo/dxDvgDtLlELrYnmOagB/H/5KAHezF
K1/b18Eth2YTxSWINfIz8xUa2IU6lFlXAD62eJt25K3F+s0EV0hZ/dSw6abnwECL
n5pkISier4OOPVdpVP9KWeOEXK3bssR1NjdShHIKCbtHREZsrJ2h5dLlUn++vtWG
/zioMWSszxMEAIUsbGuXBQU9AOLSmbrx8KK1fN5I7fbgiYOEdPSzcsh8cQtj+IDS
QQGM6O2GNGtIVq1VMjI2eM60XpbE51JRjXYFsZl+0ckpM0V6BP8+QRX9feIHPD+t
cKubrbhnNlmVRev7bWSCBJx+
=ajWe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fcf6b3b9-482f-4f8a-ab16-ad81e4460fc6',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9EVVW6Dhdtt0KOK5DY6skbNEGH/OsQvG/4pBZZy9RH1IP
ksP6cBBeJZg7M+jyxfVTBXNbxRiunCbVMPp64nvYJVjwWjtyRxThPJDvx6nB5R8e
uyUdu8kaejfJfmaI3AmPj3cZ6vjprutSue9bXo1pUx7m1RG5smSkRakJPR4U/lql
I2ZzdXD17x8sr188HG6sHPFeW4wbf464GzuiJD0nSN0BYpVOJpkKi71uVa3dGOxA
x9UgE4XFYQPq3qBPZFKr1gur+2il8r1EfvYz3iONL8jt5JKr0pA7KI/EnzYTR4pq
7GsvlCWA691X7M11NP3NBkrxhRKGvp4iTKfY3oeScoblRKmAyhjUAUmAGkAPq1xk
Eok7RXzu3CGXsiCHApVgzID28eU4r3obEXCc34wNJK2z0NxnpwK3QsTP5VoZwrFB
snLCkQq/zOvzrmEXlFkTCh7chQgrFTHbA/vBI687mjAPIn8mZP5Po7IL5/5DedCu
uKpE2L2+CM88Q0ZWSBAnCqpFBJaLPB+Tv35bA2fuso/jc7rWyxHjjjianS8gWDoT
+nF12ghV/teIEQEEfeQRDe0MUMQJ4Wx/LPyKHKU0pYPliIcqS2F/OFwwYxfck9hU
d9vcKWaOcBa9N73FcgpTjV0y6yUogKV5JOBn20LIzyICgSLcyhfghQZfSZXZh1PS
QAG5JBb3HU2G+9a7JNZZcv43ukjc5SjcnBcGuxcEGqQdPQDZ4s/uvI+Ofhe4YMXx
HAKHAagGcKE6Spn6nzP+SDM=
=1kns
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe356cf8-e6a9-41ba-ab88-8dd420713f5f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+IbKAOS20mD/7hHweMO6/sq8QUWcSc+IyXp1chbbxhnBa
ALb/1yAu+I/K9ZtX0q6IKqp7as/A5iNnQpWE4HR7dTIgPh84/lkVq0M/fDo+0L96
uIVU4bEeS3jhkw8pIbIo/WkN2DyR1q14nH3unznv+nH1TeZFRQEskqrpPBlDnztV
szxrUeOlshwRxtr10Gla20W3QEQsx2R9vMsuQvgZDmw11GJ9Bq+HwCjPx0u4K9qR
8yPI1eNKIPW/PNnkoDA+YB49H1N9fwusxEt3MjqeikSpKTkTarXinmPmiiqoiL2z
hDot2wWmJlp/rMdH+iffHaPD6T29TEhVyeI7CX4RpAYx+Kk2JWB887QSiSo7/v3h
wrkNHp3DQqqa5Rkc8P3XMop/FLGQWXzYcxZaEkRnHB0P0fEpEL6Xf9FUs/jqofWa
GESHvtr49h+FDQMHB/Rr1f2/wE1WMhveZshhzgpEaLBAT0IBjtHlhW+g9O9HjX/v
jiqQ/jliBDibhLER811kdJaqsnuE4vB6jbOXG7iLtuiUkBEYZRmcnWc5VAITnmEh
J5hdPyYDB8sw8v9k0jUengMPCUP992Ky53VzotjOAg75FHTgKshLz3UlnLpDj0Xs
BnZ4xcuSSBKOxXf8erneGucnTDWLnV2JR4Fo73YSnXk4GrR1LDsbrRmxUlby/mXS
QQFU3tOaN03KqNWm+xTqbqyiaUwos1gcOgrnM4mTMrFcVB5G8735AseG2+OmtyRO
Z3MV+g8zf/4fAdLDW9Kmxy5K
=lkcG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fee5f344-51da-4458-ad7d-19fe06ed167e',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//d99PkvXXwzLIhsDnNsBZ/eVl4nbkVrbIyAS6eMrrSzzT
GtJBFKbvqvRoDGJ87sb19MfqlCOjcU3npjVPmP3cE4aqKiD5+N7coDynlgeZuUr5
7c1fAItyz5nZMy2Injli38UTbE3dB/Ejn2DdhTLq1zSgEPX+f0hVLFqybJUpqCtb
TiHRfVabsKzteM07LAE7uBi7TVzv+dWeh5HYczpDsAeYzKMkus5yx0sDYGquOJG0
024muJIBjneUycV2TJPBEMS2DXn3z4gOarlGDaqa+nVBzri0zhair9Uy9Tqh8acF
DlhZQDy44FclM64IkZGUZqhbaACcJqYvhhXmnTVq2RnIKpJiLBTuMQt+pspgM8nr
guacUG5y0CDBweTeBD40Rsb2De6eo+UYZRxKiyAj2ikiZBEzQD+8YyI+TysZTgbX
5eAJKMJtIgge8Fhxv4zIjeWHIMTp+DF/48W9Z+ILJPyBX0mJSnWTVolt6CMAJJ/I
VWNViEZ99hnM7OSaDRRwaxHDT9wzajDBj5wwgcy2Y8fxD/PmlR+XBoPjYAg8LCb2
5G5PdFMGZUnZLz2L4BnFUZWUJs/mCBxbfVwXC++J3SuJdKZLPcUeo02TapMQ2xu1
bjot468g9+5sodw77FLewJlp8QaVW4f2nE30TDtLgP0UyBQco36dpRk1ual569TS
QAFmHMWVvTmsrrJdX+W4yORvmKC1YAczGR3N+n8df0v6FtbCHBRuFuh8ipLy+5h9
mhewsQOflK3uLB0Jqlw7998=
=Yrg6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
