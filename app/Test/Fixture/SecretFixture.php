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
			'id' => '08194b28-f95d-48d1-ad4f-489b7d7824d6',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAkXlZ5+QUCv+9w4q1oDkW/Xl3wOFX3frCnan6JpSEPr3l
+LiNCATF371NjHF7q32f1/p3i4ngA8jb9h3JtjWpxSCW2OhLRTAo/fwLM5u4Eco/
5T/Xpveuhk23D3osnM3IjPVeHHUWdXswFvpKTWehLkjezyg64fvTPGjyESL802iT
IhcqVWKNR0ECyvsVshRmnycBEOmrTpnu9vl3Hkz9Weag3ojI4m8LyM/WsDLJCD9+
1J1fDGoyK+/KSMfm6CVaXCUw3Tx3pEC1b+0joHEdYu+Y9mCHt8R+CoFRHQVFuFPM
1+f1yvE0rNYGSGLDI9HArz3JMsicBx2DPtFCKWIBDiYwLUdVR0pUXw7KY5O5w4/3
pWG5MBQkI2MRfgeENWMNUODJMdZHP84ED3kXcOSAaGNWl7hkKDsPPbWKnrAse3Xj
6k8F7QDHq8g1efQvenMCTuvdE5nViWKfDiEMneFmADXCjCo+Eswd9zAB8NWYS2RJ
1LTBUnrV0R3c8quIGy+29P7ytJzzoEM5F3PLNK85Cr6Ue0ctKFHPqfQYe4RyUyjL
fV46Ulu6+dJYkBXMRG714Hzo1RKj6Ld9mZXauHujiMUBMQKy58Dl9UHgzsHcTeU2
fyhf79IEoAR7VJyMSbzNs9qY3LjyjmtFUxD7IDLNsGs+TMUQNdkshZ+EGtW6eQnS
QwExyvwPDnFw5mEJPK8do2Cut15h5XszuVqIoBthAP5ECo7LwrHt/aebhY4Hf6Pc
Mjb+EvT1Ol9ou+vL3ySu3FgyW5A=
=OTMc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0afbcec7-6f4b-42f2-adeb-10085cdc4f03',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//bYFpjhqbXf1ZvnC2pG0PDyDZKsSL0Y3zhdnGfQRn3j2g
9Z7lHybbixIGWISkZ5q6MRjV1NMaRauuiBBaLGwPUJ9JFxnWTRKTO514HoHivgWB
oYoQgduuc+EQreqLRwUAB5yW9V20EyFhEmvDq3PQ1mErb8z/cIRhek0DoBlB1KFJ
VYUfA9/nT43k5/khI1YfHEZi5GzdkhPQluRha8Ct1pZ+1sUqu4CnnKGGAruTmtLt
9KOooVpQjpsU7iDv3jwCNJJOOcjNIUGckvY6Zx2fBma3l20r0JOs1z+L9CvHoJMz
Tmnbvj8b6TsLxKEH9yZIHZLcMK+fDj6vdhcGSPtBJJ2g0z92FGzsO0lNGVXb3R4f
BwsbfpSESteR9ADF2p/UUdYyYWMi22cwJSKUba0VOOYTh0RjYW8gVqBUkpUZluS9
w+H93Of0Jmf70LepgPTTVgk9VFwrULQdGWXy+sxEMZkgr0JuAvJrTdWwmjKjXdAJ
xaAUSkLE6PaNnbJAUeDT2sz6yuXGwfi3yoj16XDBU/tYrNHNiSu2AmL98k7d5Ssl
8vb19xGKPfaXndKjpgUkYyzpo/Ts+1NFFelpxd1f7IqGPgeeUqOsSzMlmURh984r
GFavkdR4v5Uf+Cc8Yzy9Qg3QvKDBw+GOvptYNlxxLdgJ8I3R+VorhquvUIyt8OzS
RQG/zzp+R7yAr8jApnyxp/V6iqVRqMaKJzrU6Z5YE4d0E5QgrbsCew3FDlIMFMu+
gX6Qix80/GyhyxUsBRmFIUnIAhUK5w==
=UU9Y
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0dbecc09-9759-4d07-a466-a253c5d4f78d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//exsQ4Eagd/RpJmWh5i50zXr6FvEGuVvVc1VVEpCI9msJ
77P7a1bu2R0K12VBSBj2g/AblKNXz7KgqoRcAxbUIpBFy85NOnTK6JM7K6hcQsLD
0xThbrZK/xIzzqdgE0ygF7ZmaScfZTE3AdoITQ2GvSpKJGXIgHSkyRnixfMJw1O6
qdYZX9DlhrkX7hqEfy0KFlGDFhVMr7+OPk5b/V9VghCHCZNdPwuQZpyTH51JhSfc
YEuudkDyaMTzanNpuboA8DnhUFK7SJwpbtylkV3UvO/uqd4Rz4LwDqT95mvUKtY2
j0ONdXZesWyxT5/ZGOZG//RoxgIQM6xzf6LARp7tp/wxaEG+QPc4Y2pR/SKFfsbG
IVflcydNhLs/zcozGBFCJ8pjRwQpuOlHYehfxaayyhYSU+bZYJtd5Fnni/ZD6NPT
56o8kFHL4GRwDYRda+0sPX1LJP18H0+PARNgPSpRE1ppVGrb1ZKvye7632457cJP
h/3HezyGPKpJXu5HEwfdDZm+ZGPUfUniV69Cp1m3sBOruA3PMEa2hh5YXpyl8HCb
+XEwqFI62wYqasm/rMlI4xhH+avOIVOAy8go5sCFLYTjoa8bwS+HReJtNu4A+biL
L2jUsXo+rO6wE2VrUdS2N3/yUXHF3dqePJpPj19DoNQLNKEIx5GcPacfGSg0yETS
QwGlhPVjDWI5VuItRVlJO4if0lmrHbjKpP377pfDW4DHphawbA3gNhO/BRexUIQN
isqaOQD4s8dEMZNWiD0Vvufz+3Y=
=yWqD
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '111cfbc6-fdf4-421b-a080-3697cd122622',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAhcYP8TiPEMDzN8GcoEFv0isTNKNztOgLsLn6PEMHkJDT
dHJSN/j/JoCGwB5eNynULxO0zb79e38YeZe/4PBRLuBLgIQxrTbcQlgaPIpd0+lm
aL3h2uAGroYvLwlxg+ovioQhAx5ONnmrYLzJ6UV7Q8bw4p+LgfeEgINf1LwFHkIz
hUvnpjmvJpptTmWQuZLQUuM8FM2VrHIuI85uupqIfmGoUshHHSV3IcHjpMvuu44r
QPxgAkjaW4cFPy1dlwoMU1uI1EChISdp6qr74EKXHAH9saRTBPG44im13S+1uMDQ
rCzijAujuTU1xzeiLENY/3SFSj6BgOgq7OpaOOfR7sN+o/XCTLsIkN18Aix3+Tm7
WoC/B4/hPGYLWtrSLfPSj1yNG26U7/nekX3bwBiydSPay1d2hoy3P4yUGBvMJ4vS
XQWzbqQam3HAVTqG+wtYJAa5b54Dosr/HX+oTQwpnEsRLBvMShbt/pf7ssuaPNKf
HrsZEntMZYoB/TgsgXWNd3UReTyV9K+f0GO17NGbQ8znZpiqxNv9l1o4sdXbfY2a
0x889W9wp4J7QgY3a1Rbc9EP6hAG/HW9Yvp7dA28yTbt4AjFfTBXoyO8kn1vQ0On
dXBnY0nXIDG8iggaqzzlF6uSydiVNJaPAEIiIKAniTFzt7C1E+VBG100snjyhfbS
PwGAg983mhEkbWcoqYxT8SACrcIQqSljY2Fm5UZzcQ9lkfUYLsjuLujBkjptrxI8
rtnltOBDo0m3hMQ9+o5gvA==
=Zw0G
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '12d8ccaa-1209-4710-a612-96cca4abfa7e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9HDZkOv90G62RcX5vGSD+mgPThdKMXDjeE9NLA+lXuFer
GPVoDPGpdkoVb/I9BtltSrJAwZbhbgbZkxZN2/UL3SnT/fcx45Yw4CS1LMTYTqQr
yAVbSPYqPzQnPbAhpLx820ULpI7/6AfdQ6CtLw/FacGiNf2cKbw6nl0N4Y2RYKdn
UFe/lJZuXHLsiLxPnpOeXfmRkdzIDWulx1LmYbjrIruF3j8HJL/TpL8jqhuty2ou
qz3xTSpU3tTHZM1BSl92ZH7Xpd/XIRGyVaxPM/HHHCXa6A/puDerSgXSfPcdexoP
1dpGjoMph7WwCy5ksSDesqLWg1sVJleIrfQzXz5sZInXx9r5QD3yAiLu0GZcTHZG
o5AwyBAC9fYfkYAAiMXdzW/em0sBxBGtx4CVBisYb2J9ldBwxEttOGG65RNTLSmJ
v4auSlRuE/uZ5u21IbE2GzAGl6Fbbyfuyb5RMVNlNA0IHUZIgXkkrjX3SRldRrjy
6neNUVzCk/fpbqJlwcpzrgmv04IMkDYRZ9w3ZafVQnMrMOgByLDNDjw7wvBI0100
8NeZI2JnM66ODiEqlsvsM6JgrEw0g7x08+V4ul6H95JCFgDE6RrSAB0CKVM3fcQ6
2InRciyUKjmlolcuSUW/9jxS23OyKD1qr5uMeRRTsMtKrajUE3PQ1pNCm+cp+oDS
QwFeYZsY3fOwkBxYAMbpR2L2C9wVXMteQ4l5XswfnyFJNoPJ+G9MUPOC2PeSen+2
xjTYr86G+3xVjbJuJXvh+qRy/JU=
=DEh8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '13f3688b-64c9-4da2-a8bf-4db5f30d9e12',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//e1ZB3kvpF6zESGTH4fV8EalaHa98QOro8VlW3XOgNUfy
dw185LGPs6iLzwkedrLKgENm6UP0oex9rQI/RuSZk7gOdt2UFCSJ/XTHCa19Nnhn
EgqisMitdeLJyfzm+FjCxx3gRmqSp7aFZK1KByHrcmiSdNQsJpK4mV2++2sVnBlt
ruFC0YqWFxEKPUqaTN305bHZDX7/heuJZ908mY6sBoyFLy9lLGQWdWnwK21yC4Ww
pl30EvQynyY2igl93T3bfV0i62GQBhZNM7CEiddGFbndPa7m6wZSY8nt+NPc/zIp
6W87tAmjphzVLZ2O+XLN4MA9TP+sDgduCV6Qt43FWaNodAfIBMpWSaQuyKIfkU3K
QgxkhOWI6Z3vWA/BYzbQ6+sZGvs1/ITtUsK0rnI9iGGtuwFOAHc/L7+nn0mq1GC/
6Rm3fd0X3p10BxuHGkchKlqKrHCIzeZl4rFmcRck7ivNnLANYmgiqzjuxtVSumsE
hH372+8k+gTgdnZ+u/gI0MGHy5nleVoeedxkz2JwXXEe3ft0rU5ZQ/ZC7kzDYxT8
MHUHymV8RGoOL/l5tXbW8MBziZ5ClRZL5QOPxd8CTNvs4dGFrPJR/m0nygsQIaus
WJ6z0EyosqhCxQx4nUxsJU1+nTgKTQUv2/G5E/Q/SHuOxbQUsYCQrGXt9Gza1fbS
QQE8EejdqWjSMKjrkI1yO3MzMxSqEo6p1X+WsTC0MltZrY40ms3qn2Ldm5lx+Pmy
jyWEH269whF5I+OetZVOZ96n
=rgs1
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:29',
			'modified' => '2017-02-18 04:23:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1468e6da-8bfd-4a77-a819-9b127e3ad1f6',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//XKI0WGLxEvcSTCBF7iT7sn5QBdRvVN3xTPg//2TJuV1i
Y8C7flxjNyRzMOTqgEypc0x3hJaabAo3Lnok0/ZWnbEv/ByZp8L9ZRFaN0Pt7w4t
xXQYiF7zgM8ZunPU14tvmXpgzUSPUI1ZG7AABbpV4ZLHnMS1xQt1+XnaCLIimUzP
WIhVZT+Hnf2VeC3d4T6LeyP4bT/6mERrgDtEGzgWgxZZsrKNPfUhNSPWgPW2D9A+
yjXOld2cKVlQhENpQ85NolQ0DQMU8s7ZALFf4w3Beh93wN74qHU3LawR/eNHBwrn
26K4p4GvJZspomw7NnbcqEyvch3adaYP36kc5dxox2oNonw4gaF3L6YmG8vFmLSr
lDT4JwmIJzE+pAq1ToGwP3Fk2ZhCPXQvwUbljxSlsTmG554XhdgrlAiWIL5XLbuw
rdsHWtMaHPzMpVQreqSBXske1HSMa0UyNWsNtxSFf45GCZkuUkwXfyWFPG9zxX06
A2Rq8O/zzNO0OF346EiXstKEGKslb9xWth8LlG5Y5hOT8iCuISfHj8/GfXdZZKoV
O2l8LX4XA8B4W/3tS9dA/HizhMsxCbCwt0yTsLZuavAJT0GrcORtdD73QtXJn254
8HZ6VOLUyeGNbvS9k76EilURNAoricVv4Ft/ZTCgwZrKa5Eb2/ziSd+Hl6ZPzJjS
SQEO9WwqVkbsPToafHyC5VJzi4pK5zvsmtiJ+UVLdGA2zOytxL6Co8rPwPJzakFV
rs6jSn5yvmBpFDq27Q+uDOCfQzbk0Fql5Pc=
=3ZJY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '17de5747-1304-46f0-aef6-f457f8544718',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/8D34ATBY5cIu/nBjoSEJDCfRLe1+VXkdIf36HvrdR6oAG
JCYxYj6A8FLmRkQhKLEG8D/pAdeO7qGRY4ldfzVRAzOKIzJhanZiTS+Tk7RFVOJ/
wm61AVxekzHEowbm6QLO/TUCdqOkUw9hD358+YMhnudE1Xw5++W9Q7s60Jr+f9jR
6hZccgPWug8zfPUf07LyZyihWrUo4rQSEBfm5wbsLQRdwAnhlW1TjbUop3UlcMfl
3Njb0JKRnNFa3sZ4VYMJ5kJhcja7MoRm3e1tsVToBftE76g6gwaahaWIZERjMhgm
e1K2y6d+ZGFmiaupkA6+Yg/4u9wcq8bZYOoUXR0iK461mYeBGHwCrWyYKmY6QsoI
WroxYT0ZvRDDWLwjzGbeU6oTR0L2QflJIBtyWWqOKbDJ1Orrgkc06u8gCmIM6Ms9
QannSymAHYhFaicODqXgBXq7vsFTGThz84fhgWI0LV3VaMpnTRKt/j30kIPRqiCN
jRW5CD26COXZ5wktE8zHbARroS/+9lJpQQzjw+JomEvkuvpfqzJMjWfdUybEXWKa
ceCAVEp99s5gys9o1E83bZfVlK+noW0CsV3rJ8QDBhc2yTl8SA//IlNHGIkaJqsL
z3kyKV8iBWtESJprUADdpuanWT4jEG5btoBOaUB9YOeyjj8gTt/qTvvzWCT/VOHS
QQEFgAR2jU5Rkf4nSauLsIxCv/GgJXaYxknJa3B3xiZFU3ByS/4nZg5q+MDcmAXS
FMYoWg9k1bh8AKzXxvUdEMi0
=qUDc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '188a47fd-bdf2-41e0-a9cf-df7b024978bc',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//aTPOuvU9FAkczYBGb7ixLSqDNi8oIoaSAa4egGEsRZ2B
ormQjHiot2u6/4A/T5vmq/CtxU/IoLIh5A2GaBWKcJylUj2ukJnclr0rG2D8doWh
kYkab39P3HGPHguL5qh2wwwkeV1L2EWiPunHyUXNWFzSUsfCsxlOOhIfLnKUcYzR
nUSix5u3T4H/YShOr77KR8tJnturrtHLkzbM+0/2eVa8L8BZsoiGggCPOMBkaimi
Z/RElNV5ZV9h+M/Vx4RdrxDTAjyJRnQPCX975JztsoHtzmlnZj7ltzEDorlqCket
nj0e+AbqOCv1yyxbmovDH4/6HmJSSIthcTLTlXF52S9PjhLnpncfEQCiVUnK4uy8
QFq5PmjFbNn/gtJGEl3Q23uVFPTWiuRz372MwYrmlgBwAqdvVRei0Oi07TNSjpt4
PhXzMvj42FKCNJ6ISXczmU4Q30Pvw52fjQIo22RrcVsKKCeuCatKHCVCRGQlvOSL
9XcqBoCfU/W4xxcqwLTp1PIn3Gaq6Xq452BXk6On91PmguC79e3UM+3puerSSRzp
qaSFtnqlEMgr+bXLy62VPsovhF0LuqWuD3HCU/fhqa2aZ8UseicuED3QwbkPbpsP
ao7eLHLhlJEVlpVEhcQH8Udqr5SuZZYpyyH2lsyheiFWEuECPjQhUrWp41cDwrjS
QwEDhDPbCq695qrXIECKMiphspuXowyitiYkXB0RGL2SFPyr8EXV2pIGz8/ApeLb
uTZRkmnnJkQP9lDSgVfN50T1//I=
=38cP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '22b86236-c270-4d85-ab48-a24b1d3fd5b0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Vcne0j+sGvi8R1gYQ3x06oqnHIPe5N9W3iM0AwsSRNM1
Hm24z1oh5fOdd+Hk9imkaVhzFSNGAu8sjRBZms30dhS9HXVX5ir+1dnDnfis1UiR
yZgbrY1OtMxstb4gWBF04PJAMp9mUnBOzB/JhyB05Ep6rUPQc1kEuh9dMnKphmzE
Mdt2tmi8OnF9P6tZrs0glwrfePz9bvXs3PEsOM1f0q2gqPTgfsOphFIyxI+UU4ZU
J4XcJxiYHsfwQLta1Wei85ee5VsPdoEJO2e0PwtvFqA2toG1AxStg6bfQdWNnlYE
Kx3Zx5JK1VfY8eVEF6tq5yUlY5z3wHNmOYjNfMlHrWXNvkivKLNJy4tn8tmTXUdq
CGs/IBoc5B/yvQdKr4p48NwFV6L0HeOXxv4m7cHE/134n5oZxbwGF1kBmM0gM38T
HjptYVE62Uab3tHU6DQ+2pWufA2BiY6sRp7GhZQeEmNhq0IgNHZ/PstC5dOiTWAx
bswgTbBkkCsuA7aMgYBXzX1BfiP3uZzEGMXDHyCHjcoXTsCv4fdb9xfWuOQjSQE4
SADKIaWWoHmEm9bHfDGbhBNI1KeQUSeFgasDK8TOUrlTZZqeVN9vFffJaNrgxL3k
DS7ohzv+by7oBTHHFZCdu8Zv3gvOTgzoQ0U7nVCm3Qy6vFw3Mv7Fg+CffRiTi+fS
PgEAM3MNLTqfD8dcKAnMJcL5NFob574J7KRbUqddOpqQB+FEMyJS7pg4hIFEoB12
eiK2lEwNbp9EYnToP5yX
=cV+p
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '234fb11e-4da4-40b7-aa62-b5a7a75f4171',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+MJI8mHNahAqtD/N3nS2F/2bRZmPn4xeg5bCxd6qZ54tG
tGNSNu9fXjJHhq8o58uHNpO52oGAWgFcPRD8SztY03hUh/OgqqS4hIyoF30nCMqh
sQzHGdooZMlbsxfb1L5Fu60n55YaVVQ1x2yzX+rnz+5Ozb6fe0avwK1v0JIHHcIE
YZGCP161lH0vo5NAqRLZOdhOiiIV+JPdquYstAXGh3CpaV3icnJkd0fBebDmS8TC
I8VB8mRArE5FpEoC6yHdcuBdRb3WXvPqTjqcmLc88ljlHBuh2EVsQ/JiBYIVCewq
g0p2T0qgfNFqwlx6jGO0jLfT+vXJj2y/9PclVk0whTcEWnE6pxpfOZsFfET0Z5Pt
M52AnfCOh6rZAFvF5gXrKO69bPkqvOTlraXtvedRTP6dku7gU0hzsGZKfxS5AfwG
6ARm5ZfClF0Gof9hQJ967oF2syIVrNskhujfvUlrWIR5J8HGafBDTFi517lbumFD
LYrw0+Je5JnHFR830TZFJoHaFul+wsSmzYu/0RSRs0Z+jUa9TP+i9xdxXTkKhAaT
m4JyYjh27k1MLhqdg5F+gO+ViG0IakGO1Ag0oBpE5QLYNucGSh3JAXVaNchxo4ER
AbMuGODTsA9Qpvrj857iltrUHWGCZG9VUvZ4Ww2eurbjLc8eSF8WnK9BikS7W4LS
UgHZ3Kr5onzTjeqrOfIoTUlEvc0NYACy97ZZOkwKk8xpSb4OHLiGL9K+Nnu1qyKt
cuKNZAXN9FgQCmWyZyMY9ZvaU8UT4FHiku8wjl3DVgVdt1c=
=50D+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '26f1b71d-c522-4214-a79b-bd1888e752ab',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9ElGAb8p6vmCSeiDZnkQPLxH8jpx7s2an48M2vpLAPDC1
owg1kFdg7M0yNRzy8PXHRxa3yeKl5glXbrG2aQemhPjKYy0fe1WGNy9KAjwDh+L6
xbyty9KKOevcqgGha5kaav6q2+ww4sWCfTB9B16H1403Uuobe1YT2+WIt58WpOnz
Exe9hu2Saeivy0N6tYnVMJ6PN/fp/u6HyVTvjCQNGYNmK7BOAMsOuwIAoaXWkinj
cCeggCWpWWR96bXRpWZ8qkY41F2RhpXrKWV2oUq74Aazh3AGKzt0EhMu+HYw+8Wd
fmCw0RuL1Ueoh5YiAV/0MAuqpajv9TmVAV2e4jRQilD3wCph2/GbWaTiI9Rq6inj
zIG8XZokijMjX9ZQqyHC+HdrT4oUjIvtj1nN9jSg34TAQ3HcJbfVYapDVwG4hhpE
RNWs7AuX4K4SHXnFlM2s2YXxA1Vy38z18EdCigzl9Ubt6Y3zMDOJcgdHnW4oZWyk
7wyN5ieVBt9pTuo2SMywdtVKOFK0EX45axX3f6e8CXCkqx8hyjwlOzG3P3omEm5J
xpDO5XYeXWE72cFdCdpHoI33wbA+muekzSTpkDLFFPqtuiFJBjP0LUI43ULcXXh/
XncKsg9Fb16Zarjya3nJ7lsh8abmIE+52TSMeXusHr361lWqYF+WD/P+kx+Tcq3S
QQEAG40cDC9fjGXH03c3ZRBu7lSNGatwG1otD2fP/K6e9uRAS0n4ePDFxyCXHa7O
DI1ANmaMZP9HQLI2OBuQiOQP
=cuiQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '288a0798-ded8-4aef-ac5e-8f5ebbadab87',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAhtNw6X3sjIWYl228flW+n7r8GlsiFNVTCA/QVC0Uad/T
i7Ay2upSZjPbzYWQhufPDcJOQQUvQAvpM4pYaY3VAPY4uLlkpbGOCruiB/I1Bix9
VqPFl0wAu47edZoaip2fAzx8xZhlYKvNCERSVnXGhtxvZHWiYC0ae9CFZpkU6VQ3
4T21ZrPGhbRb+hvHgl0zKRhpKp27zonnGJLz1J/WDCcb1l4iU0WWeb4Z+zunQ8+z
rhf0sp/gHCF8nnbNbU2Ufhn+oAlCVd2u5lqIWesDcui7GwV2m49oTB2N9ve5DxPe
LFtM98ZKcqLwH/NRQXDRiSOg539MZKxSSLLO6k6F116E33RF9ovo+z/dk28Lb4K5
bpa37zcFxC7AkcDKCXfgnr1yy1wa1vckRxRwOSN+PDJDTNVNuQP30DrusUS6yZgA
ChbLb5qYK0Q0RA6wVtFdejrLQ3U2PHojvSToV+k31herV3+lvzrQ/EJL4tirLBl8
oBzcBNQONPwh8nVRstA37KESJmCFtcjPpIRu+eEhwg5Wy3gKjxHNVL6wMZWpyEkk
Qu4MmTRaEgJpwu3Qn4CrszgxWdS5BfSWNS04kx1JlOltGs4wpIaOJNxcmpWcN2Yp
iGQUu2P/rFvtBsT706WYtuFvrFyVRfJyPrLCvJ36NxZCzGeOTNVx9SfD46fYhYvS
SQG1YiRSbpDnYd+1uewNu8zlb3+zOWOS3LaQh6yMCUGtevz20HLCMwOOzi9XUpqy
/SIfPf0oBtR1PRhDf9qd73SzWdE6I2G07RM=
=8QIz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2dd0c288-81f5-42fa-a03a-d39e94169248',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//StAmRzR/Fl+xMkOXjMHLCM8Di176vvoEaXbbIltc5bf5
bYGI+6VK+tc18K5V5g9G75JNhj2GlaGqfi8W1lezlC2Gl8yunVjWbua+XP8BDwxs
b9J3U9lcJFhTsIC3aAeEGulqoJkXrlmLNSAstHZtU1Jw6jSshS9DCBS4555xQHww
0b8KyC57fPjxGhPM1pZsVR63hSNqwFAnRfiDeAkMjou05OC6wRrS1lzhgTwiccaN
2OdqPhk8HSVo5fz9p5Y2wQoOzu3ADsJHPcXxSNhA6ij6u/khvxI2Qf351ZfYj2Xt
02yiOCbI6P5Wj9SKePnDkW70n8eoSw+oE0sEnjTwgg0tBt0msLt79MHzfcC08934
uey4u7UgZhcpzGhcc5g/BFmRFTMA8nE6HHdg3Z4wnPr1n0H0A0/jNlHw0UG0/4dp
z05dT+IqQjHKHr2hMQz44+y0iwQkB8CUYloHJaALW76Ba8O74iBBMxMuiqXUfai0
qwzApLCQqVG1GHgRXLch8+kGESjbc6O46niu3nU6auKq+hFlTsIGgtIGYT+iMNSO
Vmv+Ig7fqkHsP24DkFDrqJIpu7QMCQp3xtTtG2lKLImCcbN6OfAylVG5MdjfL+k+
xy02gfzwMIXNSbAPzM0S5/tuDFpWBCYJh05cYe+awcmaL3px+mhVl3kjr2gFbeTS
QwGQg43GzBIFkmAhPB8q5AJg1+T40aI/OaJKfzFr3KlOMqlrHB/A1U4EbtirI3ct
qwz3AAaQMCPllv3O4eO6ZAAdhz0=
=aaWY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '31316352-e20d-4b18-a29a-18357e126a42',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+PpEFqtSH4P7ZXg5G6hihbut55NZ/mVg+2roAJoiEghGG
Gbb+ecdmmVkIQT15yMFqzNEkyvAH/5nPIup3x2IFu9LGqdjUm6hu/MVt0Gk6jDGF
eoBIf9yfyyEhunud5YTz41TjklVG/E9v8ngljcvcA866XqHYxwZSmp0RP9jUSutw
3vcsrvZfXKlhnGf9grRWlzyYdvrevXi5izSoUo65wsVQxcS5uzMEzFJhMUy9ozCg
krvozOX/El0/gyFFz5rcfAs0k2TEFX4yKGx7VF/iZrMimhZdz1SHZBE52mk7y2yk
Yv4Ei1cmHkjEuyqKAsc4mqbT1XMQFSZGP9fiCykhwwc+gE4I0IPuS9a40XjmoKgH
avW+mBz0VQN3J9GBaNrHo6mAp5bhVbRN90k4P2bEU8H4g8vhVZJyj3cWC6fYilUs
e7oOAgqO9HY1Ow1pW2RAuWW5JD54CClPpDA/yKwTo2bPMhHNE3s4Jgy889pVDe9K
GdTD5UxoG3m9CeOuNWtMRkku79IKFJhr9M42IJCbwK0wA2zIUll+cBh9HZJjm55U
sKAxC9mPugN8awoKwr0R831vu2BfsYodP8p/AfP+01XKqJFI4hCYKGZYGv+47A3c
74TfP30Hg0e+FDtGMIkusEGk6+Hi+EgIWnlfitNlF4Dz7EKFhJi6ugKScvU5LejS
QgGjmIKT01WQQ8Wry+Q+Nt+bsMw1yAIAX0SzbB1R5hWQtAyJQgDJy/B9u4sZ9ZS+
Y5XBV91fO7UiF3GOR6BN61m5jw==
=J/Q8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '39b7da13-07df-4b0d-a162-6347d225f611',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8CJXqK6rMVMPjn+zXgnNjEit1CtAMBYI6D3BWSc6iAgNv
tLdq1XZKKtOe/UDRdl2mWkI2FvlnLV5o0gzSe+t9cBfgVv8JpqoIwFk2+0NxEc1M
WnTJh+DrHxXyYdFYW9rmSX77vcyvHaMfBFUed278QkGLxIWpXsAACi4QHHoUF+JS
HT0LHwmRd6HWwPAl1yFsRk0RAQJ1AkkhCpgGDbv8OOVUa9wH6RHj5dYrIql2DIQ+
GH8wdsjiByntNZL6fB/rxd10y3SjkZCs3iy0Q3bRVgAyZ1hp5p6WOUKzIlaD9x/Y
uciz+45FA4i1gUkbQUvyNdNVtASggN9+vU3jBgt/P15lpZ9XFHveeUns9Trx085S
X+ak39gvnpRKfHh6faxESyFPMvtfV++2jXFkw+Gy3peq/uyRjJzm4yU+q+3y2NFD
9ERs4wjOMGnr5MQxWt83OnrdZg/q3ol8PqVZkI+7jT3CRDTcajE20Wl/cEu5fw/B
aMgtPYE3L1ORVer0aXMwwwkWqQuXM265ZtBcRyuMBQVshbnPCqwsye77X58/Wlhc
N/ixgMDmPR9vseAHy/7GB4nVkuLgfxIX5X6dV/RnUKqf3/R9m7b9HmAWP9DqSsjn
cnap0cJbjOs8eO99xAG3lKENfk1i2TtXwtaEYD5oLkuFo0I6GKbVPnSaKU8q7OXS
QwHmX5kZtM0IYRk1ZjxQr+C3BJT9LzoiGy693uvpH3fZbROax9kh8IPxSa/ubtN1
HRJDfoKvxI1r1zFEEtcIXBMf0/4=
=yhHb
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3b67510c-e6d0-48d5-a30a-7a6beb714631',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAwJP7VvW1IM+sTTA1B1n4ubUDuW76JC/53uwbP8TOjyjj
0RSTlid+3ZJaQQKqgYPNruz81QWY7gltdGvp4QMjnpN+vbvBEvph9FxRtyhgIE0s
7F/KBC6e+XMKv8HrjzWxEBFhUw2YF/QJxOw9QJaRqHdiCZLqXjCgqQWWwDwc5aQN
QcuDJUhP0hhFoBIp5EBiPSc4hn7X51FKWKUoefsmKPeXVaHf+0fativsWNIcAA3d
X2Is5cSj5ZAgWEBG9YHsLZXPewr+x5V+pmEf+9IRpoe+J7uzhc2jJ5U/axfyBEwx
qVNbxf0dDSAbIyxOzk4YQxZe20p+FBrwboKhsUMMPYVr4goost00pMSNQdC8Ydol
y5fpJhS2qgrETgwskHBZTlFIpGQ5OqqHKBPx1EncPdL59HR0yYilxvhzKL85CNV3
31J7q2UmOGkenQkfawH5YWwJ0IgMqYZmTm1LMDuNSwlHtTHV0PNwn6FduPn2+0L+
qV3nQ+wHqmYOdB6xocp1xMddw1Sgsvo8Mv9XHyEGOm9FzMuq09oaIvbWPeX2H4kz
9wBi0+V08+vyR3Rua2XFIKBsfRkNpUrJ4A5Zpm0+5Cb70i+HflwXlyo88xvrt7gV
7smHGISwQWxVQScPKGF0aImcwAkdq6jVaY+DmD6k2/tFBhTg89PFmYwQd4nQE3zS
QQE3WvsJ8aCDCT4GTzfnj3nfZj4OAiv3PF26BFAEB93Xx+9TmF/lMkeh6p1qvJtA
wrjZz9SfV9+VwAC9qDmVdN4L
=g8kw
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '44a9f1bd-4ac7-46aa-a448-66c0b9c6a724',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//VvyuMiLhbz4mWF21XyrszMdWaTiRCWPy+Fuf1xMNwFX0
rRyk2JI5mOL4dOtzhRwjhWHssvMBvF736DGNjsTRDiKWH3w85vRnUiFqttOITOaz
H5qaHHh+JBC5USfx/lujmap092xD2f5O/tzsJPy0gvBekOZ2+juaxzQ9fJUL0ENL
xFM9V27xbPtU1yT+yh2f82nNVV+YYV1D6zS7cgmMPZ6yCm4CHlC1j9mfOZ+7hMNV
c0qqB3Sv7tpQVOXu/zuKeGDEgXaZhDCm63OMhplE5u0yjcCaJfbl/JHAxXIDYxaU
tMJ8K9tx0SxsqWxNOusQlBeNllAEuqwBZrjLBZ1vNCO8cbl5S4PjnyOWndro9GWL
tKWRfyvOGW3VyKhilYm5UOQZm7Bx3/AXNtwX+GxWsY1hJh80YnFW6zimqCbuOmk4
0hpZ4fhmKTgy5F9XvlzVDVWo5VZqgAa6DFy67s80VboELVYzqnqvWeTnp44E6hJD
1Ai90dYoR0S8+xMziKBiOdrNwpSxD+kTfETe7LOumCcTYr8MJlqIHvN3I6C6vcWy
FSqYKS09w3DdcAOPVm6rlG4z4dzHGsL5Hkdk/fXoyL13O1aiks+FIqADaFeB+dN5
X50xzXqOst1VISTSzt/axcK5CVZdsanj7tFDY9JYiluGf0/uWHs7xbJRVmE5pUDS
UgG9vfSYNDFsCn5+Z9fjISRpFUU02DE+RKUFq8pE/+/XRW0a5yIvba6aJomP6EH1
fM/lNly8VLXXFWXnPE5pmVycGTADLIu1iSPhLZwO6q9Xi4Y=
=z0+N
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '46790d50-63ca-4617-a719-869fa547b4e1',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAi/wnCYJDsrBaaSk/iN7BqHekTy+fni3DRWj7LLqfSRMu
vUTYwDq1GFN1SSWN6auzuR/WFZBxLFk8n8iKGRTiYkNZcTRgfMbAKvHf2VsUC8kB
KjXrjGYRajUwiUvO4Vr7x7H9+zgts59uiA9dymocxyMah9ADjKHThSXKtf/AGIHD
UzNCCcOTslXui9+rMG5B43Ba8I6DGPtWcG2u3qjenD6r1LYfshQcQZuW8yKLd5AA
Zt8lGLdjjIT7AAxXLsJaNwZ8fqtroxCixKmFDBroXrtNy00ExSSaQpoLq6wcxdTq
5Gv74r4PXuBcgr2tqkhzLEOO1n2VwK0f7Y9/v4W12p1kBtJJS9wTImsfFY5tIaY/
FKJdWi+/txhjGW+fc291G2a+rgoEJtkPi9QBAtMtX6gutrNuPnnEypbGV5o7cdET
M0BhlItQcak8PvX4BOnzOJfpLKS+YT/mnpIZiqzFtDFYdLKHS1TmCb1cXAjvF6m8
1Bsu00o4ryRFAHqwId0CDrPcZ78woksuFuotv9s4baLwgXieFhqUW0/9YMIIdEGF
8cuhhS00YdLnaQJtX4yAzkqM5vYYlq8PCYKMVDuNrp0LTOGVz88wu3jo+C42m0ba
qOc4MuHCwKj2fh/ojdWTHDZtJ3Wx/1dftEdRI1NEfI4rm1qtM9Xjh+aLBuWmt3LS
TQFYYRLTuUr+6LnF8hBE5d1IeeBHVfp6MGa6Gbot5hoGvX35WkF6wcmdkuPiLvsU
ZjrJksgcYnU4PZ3mCjdYsOWhRMbsnB15h12wIiwI
=JfoO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:29',
			'modified' => '2017-02-18 04:23:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '48d404e1-e463-4217-a010-1a4857dd4acf',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9GoDEXWyddjWeqgNXkzCaOHCR+S9LoNjgqUZYX6dTDJYH
6yjBrxik3MDJ8lKCI2mdNckiBR5iotwntJvnLnuh880CMENWdhge4zUlJOUwOVVs
jqLzF2zbGRaUifQK+UoEQzU67eWMprIRO6XqfKq+bgk7jWqUAXN3N9Mqcy9a6S7x
sgIzAESsVuPXIZK/Mf6+zDd+AcIkIbhv4wCgHdFvwth+9dzVrfC6AH/v3CjJeExV
escg15V+HC09Vl7M2moswHg78nCSxKLzLE0wF+1sjGkPFEHxN8PO3PJHeLPlcRvF
M/wXNSX4TGHMS2NkV7buutrKyBaAM6A+FJdEiCwgXOQjSpwgaPdqVjqdenNnqaOy
DGtyFeaAbYCk5Q4ldsyvJKeHzrlrDrxS5S+P6nJzZM3BA4iTpJ7CLCRVUeX3TYqY
D1DGtFijC1mUuSwc2MyqkMfTKase1dzy3jPak0XBySBREH9qtLaTCzkXai2y9xSN
PUneNpQqVO5/20VF9gDEnaxfdqtco9vIEbLwNtNUee0X6l86QqeeoLo6XMy6eLsh
jZxyPByCGmUmLKvWT4p581F2xHDbr6hAYl+xqANR/CQK2CaSYj9IAwsffYfyt7pr
xeaPS7jtiBneJRoic5PNpTNqr4YVbPMfhSqh80rPGoinoy+bZmhTgJev/2plHiXS
QwE+0Wotz5KJAHh4qlC4qFx4E1IQm8b5Hr6lskv/ri58/cav4LWhvmWEtpMDdzUk
kbm+jUxAdgpdOQo1M/n+DfJ/5Yo=
=NFW7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4a3fdf94-cb20-4c1e-af35-3f889890cec0',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAArV5A0aPpai0fyTKhn0vfXE/yUq25vOOPZueyj+UPUg8L
zmFVVn0JgelAaH+dbubvWEk8SA4meRkZbZY7fJ/ThbEmo6B3NlI5ptKi82wwCGcY
JORHUy46FZ0R2pIETkBQXUe27/r1A4s7o1xq3yWsFV0oARgNowhUAxQW45v913+m
/rq5qE4Ad2rzCADh3/gDN4LYa/0+Pchh+48tfGbCDmqu1/iB/71Z5RgsfQI7jOEv
L19yBry4l2WnuUoZYnvJ8kpKwYHfrtzyhOYDa9m38qhPH+4ZgXNSz1gW3I//Qh5N
FqpP7t/Q/+I2HtKihvMUBLYjRQpfXlFSmDRmfwUpoFBjFMud8vk4aaMKdsFLHgIy
MjdxMGTAkJWepLD6kggb4X18df71JDMOT2yF1Gr3tujeNqBi32Ed2Mlk3itIyTGa
aKTNxkw0uv6YdgcW8DyjRZ4XKVCRXxz/nM5nTOnT8Tst8Chs7eXY+ks46E3jQnyV
i2Jp1nlT0tNZSZeboTvCUx7zUWi2ljQjCwD0ZkkBmv5pK3j0chuj57oV/V7isNfg
UYz63B5yZy3J2Q8GfeBNW7CmCqtfpqt3+V7d108oWtfM82SKV0LHFPiJdRixYGYh
7CF762Cnr7mVVbNv5G9MI6N/5gXYEAbNGPMB8cGOKHrYlGQVlOqp21HWKEN48KLS
QwGoKQ8s0G0qSa63mJ+Ai+UPQLHaEBtg9QIrt+P6ZR/5o7hPzThivUS5JAAGupqm
04aU2lRwpVIntbEo1FJ6HR5Q2J0=
=+LUv
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4cd7b75e-8d0f-4c7f-a93f-6205a11aed6f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+M/Yx/EP6Qejw9OlW7Cy7fYwVTdXHWuL2ydZKvTtqck6P
YJnSKg41Tzp9PA8D8Vsh8oCeZ5YbNnnS+zF2qirkFviycYD4SiKN7cEYMgHllh4B
DoJJbtuI3pLVA21NAN/DiI60otufJ/XuQWAAbhH1rty/Q72RJExpQWiXicmbl05f
R4EfqyM3VP8d6mwBXImqRzytAVttjXL41Ys9XgZgZSkl5/EyzLtPKegE4x2V1WAS
LfARcgfYE6DNBEHIOqrL7UYVwDUjly4GNlx8dqifQbHn0mIWAx45PjzYo3Hb081N
hV0ids2UNlWupLVmbV86blfarCHf/YKzMYz84//wCFO0CGWEi/DCqpkckloc2qR1
4oUtD1haTwi119wYbWmmSD0b34WEKRXRw8xnY9YXCQJgvJHev7O/BeKz3OaP2gtG
sHn1uzgjto7CMsXhAH64Eh4v8mMsnn7iCW8k3wJ5TRJW2WFcEsQqkK28KH5ZG7+s
WWmH6gbODZNKSHXnlp9DZ5EbBYAfZCkHIh/EMufUrawP3Trb4BiO85GVqPuKwtxq
SzE8GpFkD72hpLFhSsmBbZcp1t3Q7bVegFrZ87vG2mu3pD0d2MGJHg2yvNmz7n/W
mYki9U+ByQOocrQi3ZT4+1iUDKg5KZXEUITsG8RtV0eYzwasEmR3+T/WHi7oqnHS
PgFzd7GSq6uLAJlALaGKGysuEcX5A2J5KIsmWmvjA1IULEQ5P7KLCPNfBdb8QmAA
PRvSyFqTfhGqT5YGDWqw
=AOvr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4e0b3770-1bfe-4520-aa44-c56ca0a64529',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HeT8iN1FdAmbtcTdSX9vbprUExv63ODgDZwyhbuCI4Lz
NzFSP7QHx10h54dnHJnOtmd8DnaAZx9XagcqvDD0CPtry4MI5/F5Hyuy1u/kjqvz
8SyVr+HrbSqj0tUGfP4Q6w1CyVcNJ9svcRaePb+26pId6fcgn+AurAtxaqWAEy2M
2Q+0UYUq1wRxUrZAAOUMFivPcamSEV4cgoHOYGQnC36dbXWrlbBCSa214Gp5iRKQ
S5V83ScoOOwYQZZ8Xl/Ph1H6y1Eqkrh4thdPvCbEu2N5H/ovV+EQmQ04JPbDohqA
njRGYpR5XnzI/K8IqePAKYtJ1ht4FT/CWCLHpKPZPZBjggwXoAvokhHwU5qsCe/k
cTgnsinEyrfcC4T0iMrh2UphbloD0Jad6woJc1ieUCi+pRULcP1D6mqZLQ9an6xW
5yE7NkMksepl2KzPioGPd/VtTplF1gxBj02/Lt/yqzlBWlc1oCcmCpoWoJKbqtRU
xu+lU3JmZnBf8I9/ZDvpL+U1r6J+nFXsEyNUWG2+cM30ahTP6QEuz1nrgmIxv8LL
MY2scaCpb+vqs6G9/vdsP/7D7qBCJZO23HA//Vpg4i/4XxSOnYFQXdrM9NMeF+2V
AXY1MyE1Qp7vXcf0dK+qlIWnOjZYk0EoQqJbGrHoUuuyLzeWZYSd8CLsJuMOI3XS
SQHG/TJPkFRyOHlocq6bhq98ISkCAkRW9xLg3lN7uZ+6K4zMMwOZF7uCcTN9BRbS
nCkXC7vdphc7BktTblMgz4cl0wOxzx8upzo=
=Ct3A
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4eed7c1a-b1ac-427e-af24-f130e12e9f61',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAg6PaqnjQr4Jvm5dj2+RFSTEFxVXxZhqEupIeSEqdErBo
w5nmrXmgDoYqfQw93KMPSE2Vlo1H24EfLKsgb6iVQwH6OPCxnctMgYj2TkboBdYr
3pbdOhDoqQJo+zvda96eu1qiv6Qs7SDBv3s3jCR0F5Y7AUkBP4w8RvD2pomc2xv0
8DsHB7Pi/47B2rGabuAS/MMTkj5n/N9kzUyeCcbU6mhSquBeOG+DvuiciHk4Oz5J
Gu+KR7ILek4RLxJoti0+K9oc6PV6UGKHW1ONHouS65Z2xnAg6gCLsoqqyDNa9pJT
TTgFNBZ2llYOzX3A9pAWTunBNFmmb8T/bNw5QzLWPMGMEa1II/x//MjXU/DSNdBv
JuabQOhoVr88LmPxm/3af7iy7naDpGA7+A3SzWNk0zX7SBtpo++Rw9QtpiTUsNx3
CD2sjNrpJkV1m40JqEnchTwCMkyDQLsPlz0lyfgmKGQEJwpD5OFd3K/7yNxdu1eQ
iHhBCQAEMeIj2hJVALv7T8kRVXhiJnQvS6+0GpFnLjD35urtmAiIqFBBWLOwpjsV
YHtaavU7uQzwq+1I0UXWBTYNHZOyxxot5pCIZdMqtKgE3t6R4J8RfHotR24Sx2Vl
oDhzYQcsJoKPIN3kVrNR9v89aoHEZiBwVMrWo7E1AdMS7iftLZIfcAM44+R1+LTS
QwHUm6FqiaGWtvbpXMTo/AWE0Qvj/2X7uZb/pUfGAHx26+jsj8R80U/AkMqgieRo
G/gZb//0mf4ZN0jtXOkIaDS6WOs=
=j6j6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5186fba8-2092-4620-ac90-03ac8028dd43',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAlMFSq+WSPhJqNtuF003TTznaeySUqwAxcfh/X1gCgK/p
zcopOM4HLe3ZQW5iUUziThwmH7yb+T9csH3Tsl1ywX9WY8sAcPLAlkAFO26smK0w
MU/IvcH2kKfhF5wFkmrr8gM2AbiPikC+xWpJWmSIVx4ZdWR4HncADssGYFPbxVR7
S6JHMs3tJOWKGNCjlt6j9m4IWqn/DRfC1BupXBNyvTmMoCBvc8EDsPmb00G7olVg
ltByJhU/PDezmjxQKTih1owO1Qj1FCpEnL0UZhEXsjZOD0FaWMZYrmXPYnZ/BbFa
w7TA69VoFCCNz4xBlfl4K41g/FJhp/1Ce9DRB1VT1HOqky8ghu0AyNKHczWJ1Pym
r0P/ahkec0LRDnKg6E39THq7bX0+l+x1LmFJtQbBqs9I6AjWVAsSjYVsdWstsKuC
dbAGnrCCSxMNjgVI8MBnFP/xWyc2+r+Qus+yUWzcmiLW33HDJj9r0lACDzfxLofr
sZPEY8tk3qTdW1eMtgL+urhI0vRfG2WJ4jbewiFMSAgyQDr4VWbXooQXDSKoWavS
StUuVJUSKHbB3v6MMul2f51/YY5oD+lcgn5uPhesFWluxK1fIAbwJYnIvujOmiwx
RSGGEpEYr6e3uROaOQE3OcOXQnzbevDotmtZulDQsD57G5tpB1f9uigiHnaJJ1fS
QAH9LlnZfZ7xiQzeDV3J+bWOgeuQDvrnAgxnrhtIXN01T93h2jpscklBAIl/atQl
oHOL2PTajHJDjxVWG1gReRw=
=jBra
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '523eed39-cf5e-486b-a142-12ca1ce3c6fd',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//bukL8gb1R0wyyi3oZ4eQsDOEOewjQNDvNt1xp5pHVili
zzMeSW9ROLi9bL80JJrF2JUSE5NIvJUAqB38qDG8O7zVxJqzyjlmxHIbTM4S4ZBd
JixbDSrAaCdx2NxJywuCg1+aswxP9bzmjZNxyypyrsfSQh2AVgNsyhMX2wm9FE9N
g51ycJh68eT9XPkmtbWnLUrl/Ew8fMPa7YOyuqv94V8GhxoP0oP+mjTXNtIxgNZH
+Hx9sCKyD3sqHHw0KDwEUt5aBpLD2rpVVoClIfAfbOqJpW+j0yHbVXQxNvIVU+Zn
tM+NGM8kz/6qY+TI3sGR0X3sS933NARUp22ZMGcDUPNRUSe5yHGwX6lNec+QEUmg
L1KpDa+w8kggF0T90JcL4Ab5uaM8X1Nroh+47GHrYq2GmKn49EmULkF/GzmSZoNu
X/Ultj1HYgT/ICo/s4sbEGKsJoVSKKPzTKRUTwoX7TPt7KTvJRh8VhJGrS3vzXYW
AYmm8lnWTTaaXoNUNWHPSHl1JhJ/caujgP/U6jMJMwtyh2ffnHvM344fbbwHJVZM
j7fzMSNgEtn+HGAz8BVjteJGFz1hhIlc/3w8eJJeIW/vBGDJf7Av7a/kL8HBzVvS
6FfuobaFsLC9m3X+NCx2WBg0GKaPIaqXBdys8+sxC9h+bppOgCytLVWyiexX6EfS
RAG9pz4sRbh0x+HejJCFku8NfrvrNwHownIzvHUJLh98m7cF85jHy72VkLymTfrO
3Q7CC8/7SE8aVNC+ERn35PCAvCyE
=Ey8Z
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '55f35836-86a3-43c7-a034-f0bc8d15a28e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAnZIq741PZHtyrSMAi5ZjAKeMbbL6W9ELFksfG73TiS29
+cxsEh5B08P4dHc2U1+2GACo2UFfoXRs8HR9gdjJe3n/1LxnQ5jQueMsWh1asUL0
qUc5/Dli3Smx9zXKJbP2AugSRuL9uy1lYg+qIhudKTHVu9xwQCcSKhyGtMgCt+hx
wckT0IG86DA0NRMGBKMcrL35CEB9neVPK+hYbtjmx2kvoAMaO7hMimymSLDNvOte
4kvZsyKtrQql0xr0Q7dbTVmxJQL9ohr8b2l9pORK51rmV75NwwNgaj0v0yRMaIP/
CS52I2+3149WI4AsdeTq05OBo7zq8rx1yk05Wgd3tDM6TKs1wTWVLbMAYtx+IR/I
9jr+4U7jN2B5FSc3DSaHLVQ0NNvrJyuoOx/Nl7htV6hCQM3P65Z6TUI6qpfEWwOz
ScjUdyo3C/ch3LP+ivhr4saaOGmllIhk9rzdxzZvwynTGCwbwc4YyvoVCAvjZMLX
biOKpueVvh97Udv57csRM29Xe0eHKOodlKLhwQymQpDbZ7XO4NRGmzN4xrwJEcHG
akaGt0kJx4SW1GLXRl6WvXMr2MNbDxM+4+lyhp2smneaaU2JbGaOh64wc+V7kwUR
0uLvha3ZGDATX2b5V7W9+V/QThFxU6rOnXlOiGTf+UZZX0R6VbRt9o4wIg8NW1rS
QAGoESAHrmJn2BcSgbXYmByN06QhDUabxI+ZsOGFCHgHSyZ5DplIOeg0ie6cFGf0
ysfWN1DLjtkCY1l/auRQ1L8=
=B/2a
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5a9a2c7a-5ceb-4d5f-aa9d-95823d831917',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+O73cl5qVNDfwbr060Dk9xcSWp9PdP0JE3wkSKDt+4iQ8
lkxC117iGhd1Ofl/4ORlzA/A+Ri4YJ2IWrwxivSiIUoErrvR2BD1acednThBPYfP
BVrfiheTEjOffM+TRXAZiPQ2Yx7GtCM91Pif7JXC0z3Mx+c0709JdM3WUREGU4L3
nvuMB9KjeHxunBZh7U4UOj4inKKSsZx/bNgO4WZOPXrXTl12flRH6EVOvz36SCSm
QctZ+HpY7zJWUaRjz9fZkApA6YCpDfSP+OLldNcp7fG2lnr+vEG9gPkZq0XqULWo
Ec74/InoAH7NNxFYjSumnC9yGVRcdYImUHncPkKrnG39DyzgNEq4TVD6zjy6o0kz
nP8eKMqEXzzCBMhLkQErove4sliQCH7/0R+L167nG29Tk+DC3iv9PbeV1QxZGdQy
co4rwijkxTK2OyWDvILOnqLc6vhLZxPV9XMQSxvubjr5ERR+YCMeqqroEDJhx+bf
ib+i3Y9BHQL94f1foTrO0WrJycl0IZiz569wpALxu4KPzp/4mmcMY7YA8nyG34Ae
9MgBEIozwYJfE8ZJQy1wBeUBHTTSsRb0mzFJQ3OkT6YpJYu+1ChFe+aPZKqS3UMW
IZBCqtOImb5BHalkby9EtfvGLPoHJHR5K7T+HjEdzxxUeITr9cFCfw5HcQmnNfPS
QQGYE27kQy6yEZtNSgJbRUWvMvuwz6IZmI+nxaWMq84nkbLxyaZ4VdTMStdCmsXe
7Kx8iMAMrNReFL68SLYGEFL0
=G7xa
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5c8a0637-008c-4f13-a68d-c25d3b288737',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+N8H5q4V2QbCD76uBWOHQJlJHrRHbkxTqv6ZyHtpsXKPy
43Cr1bvJNTIuegLOtQZMsZzwx0ouhAf4dYhGsDKh9+oxTL6WtotrOA8closv10Kj
DwQu9WOIRJbdrx5ySEiRwMpN/8fZllkdmxw1HolIiewgBZALQMbsY45chePUZzqK
xMsa7wxfFGNEGM57AnoOK9POZjbsHf3y1YA5QMB0dbffSn/XnGH98448gGfSHhi0
VCATDl1UdAufXgfUr7f1+28e/buo4h6QGjXxDR4HIQPSNnI5BQtvo2Fbga6EFWeO
6HrtM/cF2Rsn2u8idaeXK9hM8fyHIHCEJJC6ozybbKG8OJxY2GD8UKrxhZSCMe7p
ViOmp9yNh3AnAz+oYRR3UooaC93MCT6y03eR+QsayJ+gcwl1oQ684LFBCEE0OKjB
2r39NUhCjxRRwxQlkpmwaSbjp4MVvHcCMPgkyC5iefruEs45R76pN0Xa4HQzJhC5
2dncIY86Eer8kqg3Ttt38tUGPS3foZoCAW7WOaLwc3VL6lJ9hOEDzbdcx8MfqXRE
b6Im6QY7wRd6RD21Rgwo7E/dHhWeA8iO5fhu9Osqg8R18BogdvbLm+nATsak+hBE
C9kz/1mlmKrsJMSH2Ag9gbgZC+xVRgud9KkJeKPw82M2D8K5f8m2ni5Zeygxng3S
QQG+RxCoxmcSs2QgDpYIX4IrQyPYfUFTgzunVhryO2oasMetvN8kRlpaX6SakMQu
hZ91AmWjFRy1b/uaeSKOn2md
=AjVO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '65eeb85f-2064-468b-a1c7-fc7ddfc5a961',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/6A4w0BalXQx91dNgSoLLytB+0bOKK9UkUJbgZ5Ha7WBN1
1LyelS008s1EK0V0l2MPF7ChwNu/e25Ts9P5qEFqDF3WGNv/x/cBMocmj2azz3s1
h5fbptbCpXUC/ZBXe9lCnYqU0w4BngWjluAWooM8AMjvrii6S4o0BHsG/uD/lmve
2TGBnN/ZsZC33a5BAGPqUI0b0PXFPDgE/sTJ6r9abraplYsLvnegNT1bcSLJPYW7
83QdCZAH8Cunnpaz+vOeNRzHecyNb9IcwAHfe2jQ3PlM1ckUWSUv8a+eHE3N/6ay
GUlff+FbW6x7UblNST4JZSeCijnHIU8u+/bGIgiN7VxRdTjk3J0f2TFXnpNmgePt
gf2B9oHCM3BlyFCPrfruEpx/mh0EO33mGWbwU8ZdZ1Ahu/obx5Th3MhVWQbArCXU
021Qw2pb9nmVp3uP1viqL6ATpQogMtON9tM8XMk4KnADrw/aGNMzLF91g+lO5I/X
WSaEIDFIZXQJQYJr6BxElLDqFtU0p3g7Myj3jx7HFm2Q7DSJzKZJjq1PzJ1OJLug
Yl4zjTJBHMU0vjJVcPkGrmLF4094GtwFfiaMjodoVtOCD+oPzqFpgA1ub5Dr7ZWo
hkoSjSLNN3xgXI58IKVqOK+F+jkhch8hXL918u4WiM/+gRsEEhFTohcTWFJ/ty7S
QAHT5pVl3WbswZTgtdLujlPnd/Ip/NnC+ZyiXRxWK2lOWg2Cbo1h/pUJv5vJx/Zh
lv7OcewsQbB1Kr9EqiBWaTw=
=9Rd6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6a34da6c-9d2c-4725-a848-8f511b05a12e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+KB9l5sSYufln5nc11SkofhXC1y66xh3CJfCc4w3KL8uB
iCQFqfGUNkIncnzx4A4cCX5qAOmtJAfV6kuYRhBzPhVq8BdUTtFBRJ6gPtdBcCYt
0EusMNQAP387HreHv3CAZONoBL5pnYG6SDL9WyKWWdm5FDfkBNQ8exkYWJC0YgWb
zfIrTFsxbVpVJtBcrIFRjb5YHmp2rUURn3+Us53VV8/YchpEFml2logx6UM7+U0w
nObYjDMtMRAsVQywBW8QKfqHW6T/mcxvv/ZPFMYh5KgGFbNztud78nxPuvx24AX+
573UB/jSMFIRjP8Gqy4Ia1o2Fn7VfXZvC6HxlWMeKyqFTEsYRPuEk0SjZF/qV7UO
trf7dJ/s8CEk6BbEH+RorZwIcXKsDyNw17SajmTTpO+CHki0O8lNA8O3E+qtxvDK
NjvOVpV8kDFw3rg50f6h5GzwADVrZgeMyibGW6KMM7HO9hUo95BDJJwjxBn/vWiE
KTjFSAwCapfdo7tugt3J/arT7gPyu7taRSQLGK3kwdVpwovrxvxyPhk+TJYQhYfZ
HAChF6Qe4RCDOFo+5qPLlc+1QKhyZeaMt60Y7U5kjY6+zs0evrQ3HrKQXjDQ50Ji
o23Bf16mdLgB94e7CCbYAGLU3VEIVyCL9JGxQWcf3E2MCcwP+XxVbJDvIiPkjsnS
QAEWUy3y7rTwKwVdti8Jdz8C9jEBdFoQDpR/kQL/VJ9mWyMl6bzgn9pyBpnN5MGe
aYLRvbidJzaWFn8NNMYMdaE=
=TtdM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6a40cefc-2b7b-4243-a453-755597579946',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAtEhKpMJQXMByWpvmQDS0h+FeCFKwynK8lCzvDfHbx8jb
U7lgrUr/oh7sH8/rUAx2093eQKmZ7W+OIxvXZho3R3B7EV7nTgDe9q//levRrLoR
SG4/S/iMLr63h+2e8g5PLcduQBbl9domsCLdXi5MrEqJfM4FT3qKn0VO8bcQUa7c
vUULZe+9JfHEGJMiYbWH0kpkzh84NyLzBNOkp2TmMZ+ERTc2Ad2auiFZxMozGur9
HPP+YbOkUvtCfC5WQjwnlwbvmgpYaX3bmkDp6KqZCWJGsFQpod08JeE/P7WB4ctX
I1wTGC0yyE7nZ0VsifzzdlBMliV1pMl8VXOyKmyRVhCi5HTe0Gb6U9DjyjWYyBAy
eWkr4RC6mVlNiXCaAK/L06pu9ATecDwizsqCe5S5PIwZtnW1SqXuioEjm3xlX9f5
C2gsTGpnE5JFTPFQ7FH77isWmlhnYwraipI+egHA2ohmOJ7DV9lfa0dOmm1eZhhy
79hKy7REEpUJDR6H7ahGViVBKgDhIxXdHLkY/IYdMNf/YtdXtzcx9Bjx4HOqsHzs
EnqZP8uVB+9bF4GVvTMeQDbORmkS8HDjEfJlZiotv/qa4IBPrWLmEHBDNlja8KjX
JwIwR/SilMWQZjHXyhFRb3B+sUgxrRsDYQKC6WoL/d1AC3MsIb9gYJw3fu8VjnLS
PgERR2YTW12uBxdJXUFjsh68WYlSioutOsk0Z+0aNosGKoh8y9HH1X57YJ+eKYya
degNy177pkrEvaw3A8FH
=5yCZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b168f99-d47d-4582-adf8-33b7cc62d316',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9EaM5iDOGcW8lo0wZhVjXvJzQDBhMn8w5DHr0Sta8bo88
pi5lr/ttK9K85ylr69DSdbZrn2+FKOOfA9t0FTSAdVllIb+bNax3J08HzNH1swMk
7jzR17KUJZSLDtPWOq6Xky0V8qpRnPPcqJCJeXUIkdg09rPRJiO7qfUkDGDMMdi4
eogG8B1EWq3CGsqpYF56nhEqXU35jBijHbR1HKxuS+gVa0kUeS+MG/3YKUqAwbUG
FHz6SYY18h/A6f+45tob/xz7qfpHTH7IUfqKqcW4f8LjE8+0vfWuhze+ag3a8Jo0
evCZ2y3icW3iqazTw7P1z+vDvlwgPYLi70t/+5lkscB+S5sQzuDaoG3Uy8kglEeQ
p2LRmbMxDBW6ILSywxhQtLyyw6eW+CqLGcRKD3Y6DY1Iqt18Gz9kk0DVbioBlt0f
9ih/894OWkEX/tVuXdyBkwV5TOmUZ0WCXRw0iUsevcR1mLdO6suNZfjskkplwP3Y
wuyyM+1aHcpJCNpaL8I2I+WG3+xThjAY+ngkgbL9x5cOHrSZQCKuLpPy1hmNEeIK
AsDd+lPLbNIFIr5D5/baqIzLSc3x1wDZZKMdtmyTYjeNbY+tkPzxbAZYetQsHlO2
rttW11NyBET7OaA3sxPsQM0EFR9MzhRQEhNvpgMe1vwzeEmFl0j7Mi/MxQ3+JXHS
QQEG0j0/+sLkh0fsk5ItpPqqZcXsrxDcL+a4Ali5WG/QWxRA2+4eIVknalhHRmDX
wuO5mzMN83+xHM51FP9cbT0Z
=1tnz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '72792b23-c291-40df-a7ee-56d69735072a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+MJcF2UAjr9tcR0bFbwCTkGJci908WRoNtsyeuKTJevaR
mjtuXMQu7tPvPO9xoC6826iNfKYZWmuuz7BXMiGiGUNxzeqqctBElhMzM9xTU+kC
BhEf+15n3vIMBSpu1OolShhCMq8qn7LicdYQydeUJeWFDFjeubIwOTeg3Ip5ttKU
kObf/jOF4L57SOoJYfDcct1SOwSqLk4FWFmBXo/oiO7hcQmS2X79InROi3TWtSC1
FC3ynLsx98xI8DtqOqu6uM1bFfvsoR3uhX2aHnYdpU9rCMLDg0171WXnOZdc3XuZ
sTs6xxyn+pIF3aR3HOv5+6Df+uIaICJfjknotK5Yee7b8ZuUQcmMRSx9As25pDbw
wLzCp6nsQ37oZuCKfyZLXDVj4CernrpTVojXwthy5dbSLHUPwMkAefpYZotSfcMQ
s1D3gdQ5ZjtFXcxIU8eZQ4JrDhn1FRMe+mT5nyyhYfYKPeM5HvgW+7MtbDmkB2Bj
WNGimuE5NBaOoR7QDr4hP/MnWY+OXlNci+9auu2foWyEnSUtgW4T+b/5KMmWcG5N
iwEpsHe5WWhCR1CERAtL7285w7KyRKsN6NzStrWy6oqBggtzM6BY2x0N3Fcvv+V2
5RUbeR/G1A4xdAyFIO6WPgPEgYVJvUpCvVhcW20+4Kj2CUwX+hIGPrK8o/JnDsvS
QgFc+Y54oNif6WmkUBDrBBqO0QTMcTatidOQSgxyKg8taMC9dtPj29KWq0EdmUJN
4UbSSgaxxZIPh6xKMfPVIaXNkA==
=skyQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '73fae096-edde-4c54-a88e-448a04a11dbc',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//e+JHtE6DtPmBSnZxB938DFXenForawWjgEpSjcGVXcmW
54yGc1ogy0fmMbSJNCPzv1XF9wbt+GV4/13hU1HpAEpXC9XxKIdT4rbfllJlZVzg
LJzipMykFL3vMzVDuf2Aatt1uCcRbRKXwfNtIfMA+UqjnhbY8o6vTmBeOiLHO8Yz
2hIci0ThUM3OiPctevoK9E5Usvtyr2E0hsIgGugz20dfHRpQmcJZJABrzRSOeRJp
YDHW4FbEQ7fyvtIEyn/XeFMWjKvoj71x3SBai622S8T+0jWclq/8dhqKac+cSS2e
cOmCQ1+QZtKU89BnD8hSeDjg/zCZMVBM2VRa0ad0cDQXff4mCiBXeabkhKaEa0Qr
7uixX8CrffJRRLR3+jb+1u7uZMAFFFTVKH4WiKKJv3DlR10Nf6H9ldwQlwFeuwBD
rEdD+OQgPmi1OD3wVAgYPs4sMxeZ6PqqYs5Jca3zNNkFfmRoNgHbPcGmff8LoA1A
d152GxFupP23MJqUmGWJdFGFuOfVez/I7F899HDCV62mZhDQB2BqhXb/92QzP+ir
zNEkl4Q1ygtbEf1ZwN0QyALHuLEsZz7qc9Jge6iKpnPYzOUjmnOCaG+vzuzwS417
U3wW67NcDh9XvMQlL+0kxCcQW6+qlEHJILiYGuFe2wIB+PdRuqnS75JGgVJk3U7S
QwERaW3n4K1w6Dg/i15x7HId6gdMTG7U5ryM6X/7iHX9zfclwm48X7f4gJZSCAqJ
WYm8StHqIOgXhL0DtD4UP8VZpuY=
=2Mne
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '77c0a825-440b-4bae-a470-0edbb87840fb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//WjDiaojbjL8PnRlWdhlBMr/AUAAsjGPfVty6n6xRCbDL
9EPkf6cfSW7xArtz4xUPS9jCw5FQyW1W8PvZD4MeumKQW2g7fCPMNjEWodGxoO6b
7t4N8pGLLIbrpZIQ1++iqkOAZl65TYkBTlJAYrXxnW0Xx4qMsbQKxuBxouEaSqbR
SzC1RyoZZ39wilDg5NZcIYvecp/7Gmu8DU/2hZ07LG/W3OFkbd27bNnPIk7KJiyE
VeflIb2bi3kFDtA1uTZWyEFvu/uqcgmeSt8AtwEmjjn6kDTMnHWRd0QM253Ajh0V
RQa8zmk9T+7tndR7deKIdDZOFndwC2EVNQf0cMjNYnOyMJBN1m+38wiLQp4IZ8uD
96thinmjyjnkkJoHluXmGqdnI6GIZ2aLdnTUEWaaSXp9mem/OqS98M927HKboAug
GP7mr30A8csxeEuBhvKeaDtSey+7t4R+7pNCWiLrTi5waxcLWdM0U17i1wO4iIoC
bGkKGYpkeE65L1vWMPwU0QPE9hoNeIec78YHbHhRDd42/iiUivVfBVE4fO0r2JMw
Yoy6sU863JL/YgUqr6TouBTDcxT/F0UjlFy3hoJHLiApbG1aDLkGzKUBtHW8sKFc
Vk8g1aZUPrJApYAzEK7dQTw5a2iZDDBYi1cL8BEfSubtYGaXtuoRjWCA+Zj27CbS
QgFctdPxqsH/EURWxk0WX1sjXmlB9tEmS8Ne7v0e31Iaike7uQf3drufLXRqq6fk
ovXfPGyODFIPFhuDIfLlW4JyNA==
=ZZom
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '78e15e0d-a32e-45b4-ae40-a176524dd1f1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwGiX55MUJ9opInPonXvNkUjJqJy9dXaX8yQRSn4Bf42A
+Se31ZAolf11LNVWUJ73+uuN+ynW3gVg8M7OA5vBV8Qv7PC6CWmzeADrmB8FY+Vk
eW5/7hbtJnknuMWNhOnexZBeQhMOCb9q6xYI5KM9OrIWB6r8zF8kjIDcjhyWZK56
Ktdnx55as6W4jGLZWS3t4qEXwbz2K41ax8bT7HWfy0Q5xN90u9oPuQbxmjUkq4v4
6cq4MYaXMaa2JxKwIDVSXGjrMre3rSR+1BRbEz8uPY5UsjiMlS8TSnHeJeemTSx1
kd9A++kZ3UjPjYuuBEOfYqle4cB7pxHxEhjJhjSHM9tC+0veVisC1pLc9pb9Jsyx
4V8IvV58m3dMhZa8cQ5DZcZZ+ZDSsy/Rr8TGWmESjpNHEAzJKH18nWS1txQnppud
q5X8G88jlLaJbYcNeyxLVw6t3Tr6qXgVScdwLDxQp9t4JVmR7mIMoXvi47mOCJX1
0/cw38FeytgLpNeFZkVeoP8JfcJxdEhF7ZW7pA0VXNxzJaqtjT4e8CsCtQsLWPj3
s3u16uygrQI1cChKszzxVDiuSJKDxdVuERr9e77SeIoXUTMBgD+Z2VtFQ1Hbt1AQ
9Td1advg986vXGd3/UKnuQsWqI7lvg43a1im0ALp8S/Z1J9R9DwRxxKnpcfEwSrS
QwFCANUcBF+pHWgzY+Y3nkOYwV7+t6N1hQFH11kIHxlhHJiC+j0Ngw1elGF47noj
gG25wNqjyh3cb2sEL1+SiTx1mt8=
=KHkS
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7f8a76a7-ad8a-4998-affd-560ade09d3c6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Rq7rCQM2E3gvm3hNEhkbhYhKDn9YkyJd08pdv1Z7BQen
ie0wUE18c+K7xQLaITvwjmp6kI8W7kNpiMELeTdpxzHG98SBu36B8JpR7G0iURCO
FeDNNPeSVpHAKMpZVVkpJvBROR2+q0u2dhpT5WAFanhDEoBsP4wVlDxV/pDo5pnK
5/D/WDglodPo8pVJr2YIwWLtd+5Nq6PzGcblZpomw4TbOJiH/X7UkMXBFj1On9KY
QboiFW1ZkhNWhKeSpw5IBArsRxjWNET4Wgx7xdR9KrhwRdHrn8rj/Ks/ugJS4VVd
ggNNLgyHBt3sHJI1pOQssgmlKg7xtDLvHDkNJ1kgCCPN38jhYx07JeyrDZCkQ5GF
AG5MpkwPU1ck00gcMR0NFWYovpBO3wOEgHlMRqi/PMLSgwxuM+foJV47FCgv+FUE
JHId8Aw5wSDSap52YAtZSuPxAZhPbLekPDmE2dAT3bYG8XnGq26loD59bO5a+qaF
v/XzoLUe0+FAwQ9GDqKb9WTwqSzwmFgS1XzX7tf3agkuYgsI4byAaaKOym87QDU9
syxxJSgqO2X8s7hl+yyt8H27vyimV34IikjN/BldRUB81lbQwFVKULD0RzIkWNiS
nU+lmc2ZapCJe7J1TrSbGmZorhEhcK7H2rAFckWhSh0l5c0pHB6Pv5rmKsTy1/HS
RQHVZKJf63V0GZNvfDq2lVTFaEGmFcaKYQ2b2rXs+fz+9Jg+t5n9JyvcJLFgKzxY
xay4IH+N90sCugO3Br0iQJYxVZYFdw==
=c/BB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8434b8a6-8652-42cd-ac3e-bf0d55d0f938',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//VIXwFJ8GReY/1s5hkAkTGw4Bcygj3cStvSwsTsVjh3X1
VNXnOcF/OGoZlErAd8EHWwaNpRvl7GXA7sg22AnoHUexucd64xPccV8rjSYR6y4G
G6jMuAaVdfdoAyU+srfOqwwP6OAyvjQaBq9QxKjvPXH8uhLzNuTqaTW64kijd+PF
3prNJwbxNvKnqJSncJUkWiBafxCNvE38ND6gK0xaqoCsFLvPW6F2yysHMfb2gy/m
nVJh+m+WAMgGH3B1PbR6dTCWB2dcflwsM3W0BypNDcMU0nxLjq8h4WNnH6vdegum
IhKcnOjcIlzUcK4Say0OUffJ/Vz1xRFonWXUC8l0CRRR4sni1pf1nBVl/6ALc8D0
cN67l1Z9KiZdc16dElRXmvHuVOhXjJVCTDvfnmmv1QMVzZmk47K3IaJof4+1qV93
yfEOH0d56q7Qe/jS+DBCF9TkNSK1/m0wAGEvALQBXWAHp4ID4Q0OCWjmGgQ0+4Bb
kmJR+mKVPrMX4u+lbw6yhUhNgtHAA5+egDdCe5NQRjc6SWeDFHmcxS3Wp3MHVUGn
1NQVKNQWoGHAcxTpIm0e9N1a5mBDl706foX5PYo898kCcjAVsAS4NRwRycQSbZY0
9tuDmF1zit+f/vCtWh/tLrQ/FsMXDeQ6pM0OQQv+TjBvKILldE26OsfvEwX7ao7S
QQEbPXjfow8Hm39Sh7S10DJz1v1DZkNXEIVZHLIEwTHqIyCh2ftATIBbgzBRRRTT
ziyElGtIAsN7GynOinSVXeAk
=rPeL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '85466ab8-9bae-422f-a7b3-14564c7a0430',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAmFwjxHQuJyuzQFDOX/T/BxGy0vUWGFUEPPEgr0GepA1O
8qi7xkiSs7YthmKea/lOvzMyLhzF8D1kuIwL3vlLdZXs2SWSqhT+o4sjVflYRdh0
cHqUU1ohhXB6BYTYczTredgAbnkkVNVdS1stSl2h0I1r4DHp+LUjZsiU3YjIo50h
pKm8j1uqjkdJhZjPZgnPiJDbMBqyJVPC3veztumoHt8kRghswcyGAWi+NRzzxoIb
cN4fE1CkZW1bFeJBsANh+tVTGkflYbkxuYyrZs7DiAyX2fJUp/P/OJvNGD3rnhG5
RdIP7Yxi3wOc5JoUy9pZ18Fuu5CaJgalKJlKKq5/qwLjEPf/DEwnDR8AkGJqyGh+
qiIJAjWwiFhY25MR1jPVnBJgi9rV2pA9tfBYRjPlUoDjPiztmZQSHSzQWpBMeVwQ
89MiqDRWJHQpj6E6MK9oBIGmrp6knALNSZSkQYeRp+xeAThqXEci0ppgzFyUqRLd
opvhqg3AlGRv2cs2fDVFw3rzGkFnh1JOoGLPmOH0oOAbbOjEzceLaFDh2Q6QwW+V
32uDSBwilGdhlAzLSd9vh3CnCjWbnGEm2hawGChlZXFkDZnkpnmlzHxyxaQ+s+6l
FLwTxl+XIadx/SJNOrYjFzN6fyS6JF//S8K086uKs4mwJxgMnOYFjrvctD/yHX3S
QwHM2vBAiqqLvA7tHBILwseZpDeUbXRBqlWunTe0PtoMWnx/wb1cc4pUdj80/RDR
KP+Rpz2x3C2c2O2P98Fi9zZuevo=
=yTMz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '85dee4b1-63d7-4dfa-a5b5-490d96e5d1d7',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAkpin3PistrtVvqP11FN+pfdIv8u1CfQj6iG6QRFZZbI/
JhHmlW3BpBHgD1JHX+RjaiBevjXRDm0ipysQtUwm/pbYOabz+uUEAsy2wwSUTb4z
T2hocS+JoMWo0tUg48LmclqSOkv2qBs5boRxETpGwIIWBDVs4tiOASnt71AO3uMA
2gSZNvsARBqlyqC+QYZIb3nXYhZNO99/enFTAi3fEjmOu039yS6RwVc9+JJ02EP2
ATipj/CTQRpeg60jk0iCmO98gWrpqRxj7qnmoJgUg4+++evwLd67hMQMM+5YYxqm
YExOM2QyqJg6BVDh9PKc0PTDZTFIO2AkwittdgSifPH+u5b8LiWShpVGPZ17/IRJ
eo+2oU3EG3mCsdqJvuDgzr99Y0pUuzKckU0gtIE1t08XjSLkgs+23tlvf5pL+xdr
c7J/lfnVn3dKtkxpbUZuyEexwhi0jpL6yxsaefBCmg/gOl8ghaTDzv7NM3359uM9
64N5USnxeX0NtQnR+7CqnvdNpbUZAV1qL634FHS2kZRpS6FpHmLKrbNqw3YyPeDC
7osWk+pdr2IyX/K62F5oTyu2YLJWG4b9aMIi8BJEkFcJe2KEbQ2PR/f0gWWJFJ2y
iPcRrFZR87cb/Sd14VXxllRogBmkffXi3UPmwglMP4aOtRobXX5RSNRcvXaLhlnS
PgGkQpcdDBbwDihyQoHj2TNBF2A8WmPDFQLiM6tyXJwGDh6xZfd0AP8bmne1CiKe
9ZVijxwrDoZGBXvx8DgE
=O7Qr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8963157c-2fe7-4987-a4fe-7acda1b4f122',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//TeIv851A3V0M7DflbBumgVYYi+TkGes+s0iFYR2XctfD
GKATfzMbj58odba+6/0oYPAyQGGgDYSD8PgWtR+Cp8GRbIbuhhyqJWT4UfqWiNMy
YDNgAnaQHww5fONeHCty8PZ+MAqWj+t2OZp4wT1da/8rDezbqKZUFTx3MEjplxTD
8WBCYuI999LApBFeJUfzPFGlIRWcLMPiZ4snvs+Xl3FOogPqeD2LI3O/oV1wrc75
FA31cFmGoH+gRsGDWygfDBlul8HeVSP0gsFfdHPlwFKe5ZzKiYnDqQAm5fQZwzdQ
dTSE0DygHTCN1ylqijyqtua31n5GQSTrNU6zIlZ7atZPRNxxUtpNBl591p3egQQT
OdrJQ0EiiKMQmhcrghLbONVHDmEXWnc42iRFgDzPvR4UhKzFpEFSJOHZ9G6lHVdx
KcY0xXW0JnYwo19IRFaPWhR5qxhzkm0eY3TfVHfiujLTN+uk1K1ARSlHiQnBrzUX
qGZ3OwOI6FNS3zu0sXOm0johyitlWVidqRQyqnwzS2VXX+ttV77KcLkFA/wrSJNV
Wb8LIpsWzULE0NB9ym5ppQyDOnPOj10HbjGEInkcQDlwb6OeFumdl9mvBuxK1iQN
hkZk51B5XfhuNTgfBQBofMptcR7luW64TThUiKBAMfRiUXGxYEwo5MRtXlGFf0HS
QQH/H5quPUY1MYHYyEqVfXVQwCIMD0moGB8AjpLisxlei7U49+9YE5IFsOdHfYIW
WcEi/kfjVleCcfOAANOLv9K8
=0gyz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8c1b269b-4d09-4c27-a0b4-1b9ec3c4dc1c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//bc/eYSdLCMu+q5pesPyJ9dZOeZ807Qm5rMc3M66W+9z1
h1nf78siA0of3kY00S+6vcdg3uxXK3ZFuakIjHX4mWKFAg09bkZhbxZHW6JT2ol0
cnO45jZFASl/M46lk668opJ8dtpoS2qQQPiHCK04YpEY/owTg2xspru53sNXv27n
8iRTC31tFM+7iooBHKfo8y+UiUI9E1o3/LkV+lIWD0KTTOF9vC+C8qd/CPmk9HWv
NSgm3awW5iFmFsTkvE7eXZwmM5BtEWvGTzMfX7UCPzkJKZCECuP9qm23ClFAXsU1
qIWw24JAY/tsfh6epqvykUt5M3tCscHqNL6S1UY/1CEbxaZNMGbN+b2UaZS4owaV
/Z1si2TOtYhBLHMbj+o0ux9GDMtQUjOtajUWkU6ltOc5vmLh+5Q9fifyhCEsWQLZ
5EPHOqOpXmxJkkoEdSA3JSZGfDEP9ofVE7blSbK4rY+ekvzsh4CAYe5+C85kaDA9
s4EBD1zKsGhZyWINpEvIDKmbOtncXpRe36vuiOEbZdCdb5+Fm7batyan/rTpyAN1
NHBYuBFNPeOsu3SR68moXmzduTwoLaXjt7vMDbVDsSwOP35rD0XjRenHoJ03oQyB
OHoUII9WN3x+sq6Ds0Lkebq/3mEMZUt4YYv9CHQ4hkQPgaxDT8A6oNgwJVYou63S
RQFFxgouaDHICkzfAu59uKtlJc17hKvjPSOuIY/W0IiL198zVt1ccDZwbIP9nh6R
ZP49X+OdiVO6ovHPXzH9lLy4kGs1Gw==
=BM1X
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8cf31ae8-0c9e-4841-a6c1-b1ca55450a51',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8CIp3uU1+4IoscvmwgpCijAkgp2k6jZZ0a1YWsotLTA8g
SpxvSSuYvMmB+SwVqtTsdOM4jIjo0sWDJwDTxqnwcTbknFR3VvrKPTdeAKW7zx8B
EsqeLSjZRmKH0hwqTNmgNK3SK8a+hFvQ4BiviH+rAEZV91NhGr6vFoKBlMcf1hvJ
pC3U3B8SVrE3l5G4RaGbetyZnYbgoATs8OPRWggqC4AgK5MonslSEQBtWfXxTtIE
r90c/03Yh3E/Pm63sV7AS73SOu+Sh4ZgFI6wz5mfITCS3GJKu8OykZc4XlnPyFUx
PXTX3uiNP5WGPQfrOZAz5Ije6KIwdMvWZf/Np8ARtWzEtSb344bKITkgdcJqgKkG
8P8eRT+nRGkNqp1jpdlY2dOjM2OP1X8U+JkDx3WjxdylQzQ4Bqq117g8/ZiOQLvn
Mi9d8taHN5FzafNBACSuGxEEAPo17tz7koWWLxu4/OEyOCaNoo4Pqud/Up9KigEM
xPJnOw7LkWDQbN6WTvh1pse1ZRR6swU13g9a0n4WU8Wh+YBKeb8QgWpNfaQQV4d4
QwIk3CcJnsMTp+RHG7lcJbxraEqsXbh+XGkLaunxKSgkH6dnve9V1TPsW39Yeu7g
Uh5g/N/mAzctKujtpPm5gBbRcf7/BPSKE9B0SRaTJ7fPVVwptAXrAlSE4YtylETS
RAEnIwxTBMyvSEwTBhskSRtrqmi+6vhmehxDD7vAak4UgHIv1d9NTf5uej6QogtS
EEnrjS+tH/9dm7RSMRVyhDuQiscE
=muv4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8dad9b38-9b36-49d6-adf4-b905c2a00395',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//fGCH1SzuTHuvOoshFtmeQ8jN3br/jvmQLOsSX/VjzyRU
jypNdPz8uV3mS1ztWadVyaXpF0/rSWFb4YhVQFN4TOBA+NPJ/Ruu3/e+SHKTZ5sQ
AWRSoEq6ZMBwdRV+WlXZoEehoxZYTdThu7ZeVMZwAYgBaO0ZtZNlVr4IiP+e4rUE
W5UQDThMGuuv3oV5RGcy1BnJ+WxwJtBgfPYpb8Qq/FEOHfb1pkuIUB5Ai+R2xB/Q
WsjKh7h0m6CMKE7N+Ja6yjhh+8+BhKJX5w9ixDeOoIZxEu507qL8o5enSr7/7+N1
75VhmAx0zRBLtd3tp9qltVcwFgq2Cq2fidLAul44p1qL1QpHhxHiKvGvjqms+yhB
itGZzm6BzcNqPHqcXzIq43BqByT69U3OV+Ce9HPX3d/FzFBx/M640hjwrRtSDBUR
lsy6gID6GTw9PHr4FBZDsAoGFApd4pvt5iB7m0IFJUhxTSZs57PdNNKNKciqx529
okdNxfdqocfF/PR5HnQPhU417S+sK3W1MiH/rKASk7rOneCtJ9EiMQifwtJQvWy3
N40D6I5z2UAQryfYRjZUjw7ON25Ar3oyt+uqpX+t6CvovZtuTcUSfOKv/7FS1XS/
5I5+44OUgK8nRkEqbzPlVIj0YdU1U7U5pKjn81mx/RvTREluuTzS02ox9ToIAXbS
QwH0yaw5/jrt6r0dqFwq+qZnGHWDN8UwRbfPL7cMzYCCtowB7XtyM11valmjQ1jC
DizVUQmnIQ4ulOeyEl0vVKd7lfY=
=tH8W
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8df720cc-787f-41d4-a701-30d4a98fe757',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NSaO1obScWhjmferBxKOzkmNKOsXV8GnxyUsIP1URu3l
ynvCOu7Fxelot9wXwaQXiKM8VgPbJt+uJ2ouOD74iRYu9dPKeCmRjUz6A86jVrIb
tInXxmU/ljP5TlBmZOW1UOMYwo4krgG+OAoRCgAj8AltEnuaCAhePLtFB7WdBm/J
g1xGB1r36/Fvh5vpMJhAcHLUpYFWF/kkq0sERO2mZaIf/w+AjsjodyI8tHWUfXTo
+UccPsz8a3RFqmjcEAFBliv512u5uI+4vnn/OTlNmLhf3SnSyrsgh5f6VW5LeFNI
NnDhbO1X6IADBCPzAyVAL3Am8U5ZnMp6Qgo+xtR6mUa/h8+H/S6eMLmuv8XwnxIm
CiSabuc86omOGQ7BmOffMKnrjio5lKSrbRDbWOs8iBqj+FNn1d3xEqGSmVzR1GXk
jf2w4LoH9FFAxv2thZ+cyYdt+S5mVwXUf0RYLcUcVCd1SeZ/Erf99pPlJOymZKLX
9be/g2Jw71Veq2qPIYc+2OA4Pf1Y2KBpSk4RL5DLDFUZ64fDGJSLi+ZEi3ibl2cJ
4FcJMZLyBrF7FZAvQA6PyGiJeoxJB+aln5goR/UMoNXtwcjK6SETvVIaLNJPpSTR
ha/4kU6AankU7Sqr2JRRuGN/gO/h6YVNDv+TeDvZbPryOl2C8d0R5Q/m28Vc5SLS
QQFQZliakffgmQRqvnbDaCD0pfX/D8r0FU0UTAPpDt8ZY0NuFzUXzuP8j1zBqhns
SJSMs+J/mbDCk3VxAK6UbHm5
=45c6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8fb63305-a4d1-4844-a78d-f183a2692645',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//Q2hXo7aIdaDmADw95cg3FZKJrdgn59YSrXPEbtEgzzr3
PCB31pKQbTuqndwkKY1UScUDca4e9VPCHMNnAcV7qGJ/buaSqgJwRU5NQ7J67AYj
ho4a7ssyFy5R3fXYWw0z8+Fv5DYbSWtEHfq2Nuklnk2HripMi6MXUyTT+OMZgEUC
kU8iFAiFFhyM2esICnE+AkV+/fo6zP0X/W/7r5OogYc767KREJq3PaSueUmZ6Yz0
OsuILjjZwp5qhpdicW9iHlWeksj2i4Ni6v9eA/lI/Or0ZTit5eglM6SrpPrCcG8G
cR5WziGgdRlbSFUz3ydbRSZkapF1/YncBgxSG2mUiTx79oIE5OHYFUbMvvZLonUw
ZADuFny9LVcc4SLY0zMMDZfN5NcGq8Je4kSJ8P0w068Ep5P74GadEy7/bVNluiuT
uHqbarJOkWIaTAdU6X4lALO59Qi5UYdlktTFgZhV1JQjf8zVmiuVVvsyIexvq666
seNCIOFmiEpo+5Ujuoe/LeRQp34b72fMDEtl+EfmRndd/Cod+iWqHURrGWSKPVVK
lOj1R1St8haZz40sGGa4jlcbEpn/N4FcakcMJu/VxsgfmuWGhAK3eW4mJhJHoip8
uywZdUIBZc5lDoJ6/c9mgkQbKLLjWYCNJQVtq6zC00MQWhLzz+kGLKId4NUgtcLS
QQF+qQ4sZhY5YLmyXUYxFsagBO1j6ynz3579dv6psbK5PkcRQFynnKa5O+h0nrvE
Nvn/OUoneK+sUDCzHzWJDuQr
=0y9a
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '94dbc03b-e335-47fe-aa0c-2290df53982a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwPademuOb49ijChvuoEG6Gmt+GYD3PIMD2fUcw25wayl
bxnnW+3QG3K8UevGPmprpFjDUX2l0BzQKA/aju+puqIdYo01N4Ai9oR970xPJZMc
oE+poadD746OWQiRJ/mLBax4nXvc7eKy1BuKEGyTM8WBJOXQrddm8WIDc93hGaoW
Re82DYktT/sy4rent0uE+fCwcCnHgkL758LH7gbO3aDYEeeAFxKqgvACpQGHZCon
rdmVlLmL+8cqcB7U9OS6SGLyfoFk9OYD1t8IAC+lHUcWetCXNCC3I1Hi2QM7tRZd
wx1lm2RCOX3p2dtPnesWL4NV1O1Z8mrEUtn8APLpQPBOvlqjM9b7XXAoKaEr4Sgp
em4HNfEMJI0SJpaiTuf6x0UaBii6FbtPosaD+8UwS+Gs5nc1a0IVog3Ubwrud6Ua
9QGbPuVlFN+w9K5b4JxFNTNn/IPDRHm+hqpnfE/ms5j5FBRM5W1dsx6N1sT8nWvc
aFc1OPp1hqntfoTuhqN0X5EvI1l3TqyMgGode5rWNIHvPkRspyE8wRmCAOHd7QCv
Uy7zT3qXBb15XcIPsLQJ+4VW6Lwh8SB9YjlWWge+vDRH6TmLmjMN5w2keH1QypKG
XQ43/GfkOG7377QaeuSUjg9KbOtjPQGSBY7p7AF3xEqwSTYY43NFSuP8FvChOorS
QQETt4eddqYAyt9bcOYabmJU9ZCs0zjpNCwYmVwFHd/Xl/8kkaV3151s76S/mRrM
pnohpjxAF2xZcRBiriCDYLIb
=g1Sf
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '962d655c-1a1f-4cc9-a6c5-9ab3653aae49',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAgLoZXiJbUa//a+L38TtXEMRTEnHu4oBqh+hhVxfcAiEX
4MvubVJBRY/wKexreOxq3odZVuVbPc9jcbGUFg+tv7bQpckJkI7k1BQSLiDJ9RAZ
0L8XTBSg4Ngv4cMO2PSkcBKhLNr9kvveysc78x2EPGDFGsbcpkgh+Tn30lE562q6
EqW67bOQeW1vXLmr5W/qMj5mpdS5rZ3UEdgmxfrESymKtpyuzWg1XKEQ/l0PNqxP
YbhMli6bk6Fqo3TLOzHdR8ImTaFPE3JTYtv7a3bg1Ca8CsewBnvBCRZr7beMYk02
HP3obptnpVyeU16SsPsZuJmYAyKtF5GU3Xzkpu09kY2kcT4ssv/7re97vpl+/CgN
pjstI5vY/7ZZPP+HmPaPvwUprLwzB9ThFTLz9tvTOPwiSyECv2Y9RTbTB1bplM/Z
0f8hwtPPADUfeKnfGRZJGYFDJj3eE+nvNv/+qCXx03xvqoRWLYVcJu1T7uwCI5OR
A2/H1u6ng/tYtUZ5zsDVVhtUH2KdXGUghVXGHJV2p8Y6PAs419SUCTqqB//uphQz
oNClbe1hUln3ZHm9Xl1Pp3HVfCTTlgQEcGD/7N9QRF4F7TXyPlHt/LvSc+Luy5p1
eLgxxiJ8jEfjYUnuU3eSGPjwejmWeY2Hao8BbL7tNFOaHMxTFwkdhUqrczhWGb3S
QQGItOC5JXyV1wx49dc91S+0eYrYNugX/WM6Hz+A0c4nPG/V6lIB/ODvpCSpewBh
alvrg0+uB0oGl4kGOKOA+0HA
=xm63
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:29',
			'modified' => '2017-02-18 04:23:29',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9a081b82-0a79-4b81-a157-da0f2ac00e3f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAs3pJFXNuQiVKMfvmJviHnpdEZtL9T/Op44JfZl1sHPKA
7gFZFMe8J88i3h8jBV75ppX6jj2pfobFets9YL5zzyj8islB4RNXxw8rH1jrR2BY
edg86TZCUlNQwr0l5IN6Yv19P1xP2gdKrG3w0jER2uy3w4ZdEa0uh43QtiAU9G4s
X2uUIjRNXZ3Xxl0DrsX2rUHNqUEmj/UMkN3SUNA/Hdmj37CpK549lKMy9JLMbETM
IGXebbbZk+7O9UZqyS1zeH5vcAwCbIYlQ45V8tHolOKYqgozIzm9j8VsjFOP2irs
lo2xyfUNbANcuh/4bOXCRBgQIOpqUYdNQ3uZPlTWQPo6NeVwDR7vAscG0RPvY+ls
anLNG1pA4OIBqD94CX05z2DZvP6SzYCRZYPW/gLZG3J988aHgow+54F7o33SmyJe
rEjSFD0LEu63Kh0kEMhWl+GUZQ7qJIfntSJbqhRoXh+NO6SnXrkfIT8o5H4IPE2n
pgJceV5yQBkWbG6JTEZr7j29Obw6jtOhDTmmVF33T7RcvPpHS5ib5zEHpkrAbo3e
93l6hsr0paUxQJ8pjwjA/J7AmO0ygQA2XT9oQ1pLurXkZKj+NY8Zf7+ErQLLeh4g
raQq+8Lio4Xdr4M0Hmm+98cPoueWyzliN0Ar0rEy9WB7TJHK+bCEOVBiyCJ1Kz/S
PwHoN63TrxMc3Ck6GKJpy6urwAag98Kqoc6D1p6Y5j37Gwq+V8DS0TtGuuT+WEmf
wLrGWns7UmQm60vOTVXNMA==
=alAa
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a45e917f-9016-42c5-abeb-30889a1b2bc7',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAhKJEHSdT3PhGDQD3LnPSyfs4OZn3rkgPPv13ZlaPKuVk
SD8qZBN1lFoU0H96/LEIkMFeOp8MmX8egVE5H57OpJeDqLBpiFy3OCII9YYcKQsf
4JpPsUw/dKwcXhkH4go5+mUbXTNhKMcfUfFeQbFeeMzAjdxHr0JLAAKnZPSAOgwc
OcyvmP/np0bXbqhPcRPqp8Gy5ua8UES5whLWnmhREdp0A2qxAFltLl4FLp9KZVrK
wkBzb0cHiSacUn+tgpho9WRO0sfpiWvJi21ZfcsDqXdQpXbVt4SHWiEk2vs38gHs
dv4Ezzne0+SI7jrtx4wVSifVik9ph4QcYhboMHsM0P+h/sYZ8jT+/SQoEcG3GjbY
IAJXM5EqCQooIEAMbkGKi61LzCXoKqnYwNEkiK9r6GIT5RW0sNLWiH9gSYhFzGTU
8AXB2vIkh+CseYGa2zROkiSWmC1G5XWrowhU0l7Aetxj4r65FLMmBfdrD4D+Y/0E
bGiWHF7t+t2mKr+AhWEe9u+dTz6FOHiKv9nlPEW0/mWFBOGgLnu4z4S1hSH6Z/Rf
Y3wtlZ3JioURUQKHWkNeDof6BXJ++MKShsdJagYl6PaIkBrirFKFYWyLQWOioAzR
TOEpuXjcZvkPyHj7F49kLi1B1F46AQDpEGB1WsqeekVpXz2GBEbs+UUXkrLklljS
TQGUx14nnrpgomdva8COm+ZeNyDfQ8HGHSOOy1SgxxwXLmRbSRPKw1pVgUPsGZtQ
3IoRSOmAyffBVSEAaFtcILRVQ1ovI4NO9U6YivfR
=T/IF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:29',
			'modified' => '2017-02-18 04:23:29',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a703a69b-aec8-4aa3-a667-b851ee55cd3d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//dYfe3z9J1tiAu9RklCvLS3RORxHM79gHdPd8QrfIyELp
kUr7XyB0B4E1WoUy5u8QJX39aJv/aUI0klOyRq410NgjE+DHtclTK0krVN7lBcPW
haXAsqbsNEojxtcn4evZRcUQlHZchLSzEuEQ8fdq9v66HVwv/xsXT+CAMmB0cs2W
dS3pl36SXm+Ty4OhKMnTWDO1/O8l75vNGE36UURaJMrSwIwL/sVsj2l3Cd1r3fU9
H2uZybjOWs+oAWctZEGkGT6JHbaAprkdNuZXKCQm6zwkQw5TTfDCbgQjRQyCF1mB
CztuHgnanuSPHnjnwYnzswhVqipgRAiNnGBvcWXMAMubGuCOj3fxkTl0AGKecqR7
/toQolM7rsi3ve25nNtKThYhPXV8xq55W0EGWgx+Py565eLrVZ8uNwtzdTuEY5xn
7GFcoU5C2iiCwoL83jgs4a7gkWaLZXwxauYvxCat/sqnODnTtZe2+Kf3Mb8jLF7i
GlfQhw2OFl98fjCbIivqMAnsTZVbVGuogwhD5lJSD5+xo9nkTbqUXjs5kUJY7YKv
v11lEkUpbMd0pXwpPoh3UcCZVWeHegM3gn0AB8Fgxlfn7Q6tm24XWdEhKPalPS/g
JoTc5DJQWyLKkdFh+p0r9xiUCm6kpsLNd7YNECLz7gEBDGItmQ5MHoR98Dbv+WLS
QQHC/N1Il5GT5NQPnpoITL3gAj00OlkFiQeHv3Rnqi6SPm9ASaRE+WAEThO0QE2R
tnL80n2ssqMW6u9fe6y9dd/o
=aXSj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aa2a2386-86c9-4fbe-a4d3-0362eee13bf9',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Pbd6bdMbOwpeeFzyYMHGrVrvWOV5H8+qDhgps4W2Kcc6
9MSuRyjUb9PCQ7Q2m8COnMAM4sh9VWWXrja7dEhbEC6LXygDWqIT43Xlp3Pvn/8j
30xzUTj1DHhVTkAQz8ZB8ShgFJdU26uRLlDSAKaVQzgkSGmqeL/bytLdqDshIuTi
SMZYwMlgQ0CZsmulG1iciZ5TAdL6zCCl5XwNZBOa1TqCf5JPxC+mDY9knK4urwKr
GVUZ2u3oOI7zWY0Vw9+p6ZIUh9X09IjvGKsMHVO4asfc51HpZvJb5woEmuCRz895
JDdeaGlJJtPjoU9BBs0Ztuavp0tSpSoyRruPT1hgZhyc7wx53B4QXq8Z3kk/Oshd
RQSTP+rfY1Hf3L+QWo9yGzcm5HfvTyBJrlnqRln0Dn1QFIrRjDYzbAK5YHB5+lXt
teuE1T3Zhh7L0ovehx9IpR+tEW8HJAmArmEz6vrQtZ7dPNVBbFCH8VS0eXIJlMMD
2GipI+IGuPah+ZwaJA11z1AEpxs8bzI2Z181ss1rPvfu1gX9bsjMli7GRw85VPwo
SymZj9vqrIbMzsW+ZTZIGGH6zcObcP/j6RO/msFsYkgVzWiztkDNiXD6Sa4i0g01
s4EA8xDPD5w03AC6ZXWpqvtkSt+FkTjpN0m6QLQ7K0nlJUwmcjOUDBnD+4z0r13S
QQEW1YrXiRtXVYM9SHLpjyc2F0BZYFPsodUozQruEz3JZs5XFNq7kLVyNZfIUOZ+
QmhJDMqevSx86Qvyv6PJ0aXL
=EG+z
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:29',
			'modified' => '2017-02-18 04:23:29',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ad28ab49-789a-44fb-a004-e00dbe150d1b',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Y66L5WDttBrYqBTvBmuawBE96ljWJZguFm6RGSn9tzUr
xTTFIj4In19+9Ys5QumP7iJuSwn2rj954/V6JF3WJxJeTR9sqPatHpZkWDanb0ab
iW1DttaNkdiZ6UrXDhVkVnZDUQQO+R429SHooSKjcbXQZYCAU7KSwWYOvPDr7kjm
mn1toZyqi9FggzOE93Zs+GUkBfr286Jx68Qmna1JW0b2dCNP353qnwKAkO7K5SQL
2q+nrjk1ASW0j0bPoXbpq5XJzQAdL5AvDc7TJaQA8nQ7I/TdstnujUZd9rA36dIf
+fuJvS5ja7uiVYjvrbIkvigtpDtb1i7NIE8e+e1UJWef3yTNfSDDAwTBTO7IfBO4
7xI0gB4vyyMzokjMDUCYNRD5YkfvQ13Mesfrh0oUUqwbmaJNg/WcQ4UMxwhYIrte
RXaWsHfcv9gSNF8TbwfuBXOMWEifTsWlXs9gb/bqOS/l+vded+OstIfXf3PHkmwj
6lkcqMcmshgevkV990rgChZyDEfRgRSpy/QSQb9bPtv83vFNWbm528HVWq1rR5R6
hHdLqws3ss0/OesuogRzNu1dwdf83irp3Tfgsxbx6pEbR26BY1Ryxn3iIz1hIg9d
IilYhCtIv29QlRpt6A4+WplokclnHS/WBoU7eHcHold/3TlqdMIRN3hUMxWjoabS
RAH9zMxpu7ZWTvtYKecXoiXjpEIu52OedaAO41FEWyG7uYta0Cjirgkh4jVJACe/
S/tGHW57KFO+M84zxkwNf/uE4aoq
=yWO/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b06f9651-637e-4a18-a0b6-3f450097629a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+JkfA1+jCwY+QQ3vH/nOLLK08pOV0mOgfs1ZbPQs1TE5u
4finopT00XLOVGeTVA2WyevxQgGXAAUJl5N9va7/6fZQl0BqiS2CLklv5MafP1kA
NccCOwHr/80tGCvadpqpNm2haISrwzzDmNrGtSh91+tA9or4a0JfeOJ9KJEH92Vj
IMLn3uZuuz7EFL77X28+9AVebP4c+hLGHgwTpONPiJ9oIV8Vxy+bxWiwmxr1LdCk
rH+y1XVtiHILR+YzldFOUXuGFa7E5SyM7Mqt7XndjsA4Yk4gYccn+mjKhQNaTacd
5nsvTekSaGYlicuQ9B5p20Mja2hUgUC6saLxJRsPLmD4ACOhTGAE3c7jK+gkzBLY
zi7xH+yWU/ePziPqt8acb+ufHiPkdfTazMe021bZZuqsexOEXGXRNgEQ59O/JmA3
utzOkMgiT7lrgufslQRuB1cDQvI5UENIr8SE3e5QLoostsJxHMNNjsLOdNfXpTd1
oO49v58qS368EC7ZTh11Rt+X6nR3OC5rFtfxZh8OwtZCdMXgSFAu7lnrVispa3r+
TkZYgEwTtXglT7aKNrZvFHp12noDZyJmPju0Bi1EszLmih3c3OcRpzaSbBe0qvrl
+pPaYvk6ouID3LCO6gLRfnCQGYKKGyeYyC8/wdFyNjtff2tdXUt/DwDt5JaZp77S
TQEzw47NNmTZIe2d2gbzK7BWcEECM3wAKGVA9uhTGCeas8IcYCfnIaw3ZgHRgcP4
Ijafr7F510PHpvMgQ9Rz/hczkSiUPRmr3EPzoOze
=Jn/R
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:29',
			'modified' => '2017-02-18 04:23:29',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b18729ed-c463-4285-a931-cdf3a7dfc4ea',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+KZSvafG7owQl+7Ual4N3x9ZxGjEWs0sdS7xk8qNE6QS3
s4awjENCh0zmSx2pTWqPWRG/oguRrfATp1XHsZE3VTMr/z1ELMQro9/8oIEZNXOW
ZPmgDu9rJkMghr1RIamxoX6TI5onS1E9S3BJlMXb5AW4GGOv44wWBcal8Z6VABH5
sc0Mo0DNIXYIWSPOwLGm1oTt9rdYrddPfi7cTzX9++vWjGjo9OwWl3H+V/z98x1o
BcyBK+Sjvui3As19iqGn+0Ai2vcWk26ePUB6MURBsyz0PMDr1nPVmdrg8PAcdYOW
OYPyKgiwi8AcAPbtSHCFVj22NUngTqkvNV/X8w0SZa6dy20Kd3L4wdE2tEY+3BMe
jttaKVStZl73LfypFnWrl90hzRCoHD2Z69c90vi1zTiwzsaAjDaDfyYuCj3kIYdf
Nqs4pBfCLTFiVK0NkRkMcpNaD45cfvM/L7wDTwOe9BDgkB2KfEvPbeUXsD8xMOLg
dW0ZuYCtXJHdJc/pUoJgbGqr2iiNiwV9ycnD4xRpuVy9b9PqYDAYQ6N4DpVTr7jH
TvypNeF9PBkNcTpcHWaNXjsi4j/EDaxwQz7YzOdmarPkNoviRdGMDBU2mRl2myrm
O5ykT5xLeq6tnR8sUJ1xg4SWxHhFlpo8lhszco3Ckj3gU5+er+3/qjLln3Li4aPS
PwG32DQfulqVnVgjJE/mPSSRAR7HxhBf9FRTom8Ua96xjZAP7I6wVyZzn+H/r9Zi
1yxa7FHQKgem6CacQ3TEWA==
=fZLX
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ba0d34c3-3871-45e5-aca0-12c234796a77',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//UZxS9+a6gXXKmFOpkNKBAH7TT0O2J+4eXrt4EvenqiRD
J8tnFTszyJhY1qjns3CEE6TL7Q0pDiMYSEiPr1CRt4fjbsAj8OoaClVF6SRMcZiU
yK7l6sG31dz0iRVt3ooV9wDESQMZbcGv8FJwHAZfgS7CM+a8cky1pIqqhlWHFJnp
+E3MS/nu2UozaIwsURt1wKXbEcwoU64OvWpXnBwERyknWFD45KYg8M52ac7dr9gR
XiYABrPpQ0jXhmT9VJgVEy6o+udJJ/V4TekYvdhQjn/QPn5I4kyhr0EgrMm4bUJu
GteG2Uxg1EqDEX3aBdk09kQ/sYHneAQV7FCl+O28ASnb2B/paTQYH+2GfIdBsFjy
85vTgy74iMtKy5pzbwVrpph564Kcg8bdv6Auxf18ufXVa9qe2OeU7GRS4PzxMfUv
D+OfRch/ck4pC/T0PQIRJC4Ja1rCqJAYNMpTSIYqa9bYEaYHAa9kGXmjXEkqu8a2
CCTb85IAKFJApAVDdUlqAkqbzEvClde+FSwFJ/ije2jFRMgaSNyO2E8yKlH7G49D
Wi6Q3kNL9Y/2HaV0ngqsXKXTnm18Qp3NMq5lidrZ1eShAUMmkAd2Jaf6u9L6FCEQ
ObofVOTSv8VOiiQzQewnfa9iJqF9R22GhV+58uBfebj16NyFBYPhJ65gizSiAeTS
PgGZL/eCanTLL0w9aoYB6GGqnGPCe24KWNG7YitM8wEw93GAIG21Bho4Hdvn623T
KMjn97tL9iQiVFeFBPDd
=WhyI
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ba49d22d-f6ee-4d10-a139-e2ea440f6f52',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAgrTKRmAI6MGpp3bNU9TAJNrfCruDKWRvXIb0Mb8ZzXKN
m3mo1SjZpSKhMkKdUUg4uTL5Wno3CDXlTTanTDXy0mdLiASOv2SqdHaBL52wUF62
WNTF0D0jh5ni0hMkRCDFyqnqTM03dSSgiyJaxjUnCrEigoEi90EOXZnZsOxrdR+H
yjwV78/6/+wd+tjxypKgdxcx1lSdHUzfTw8oln+lDeh9dazkO/UnhPetAnzULDcG
6qBXfjqIsJgDPIvxtPAzQQ3/cLWfWWB9Ua4thhHJ17w6+z5PB4v2IsELtHfgFuAp
mhtEXiskuKjmulIa2S0z5iWGzzD3xm4TCxC/xLZThAMccChOdscYY/lxSV9u02kq
tN7VVVfxFovuyIIl9xnvFRism2cRtEj6Y0ZLm/dPETY3W0H1cDllMXdOv2oQ5mRB
FrHWniCKPHK4GuN2WobDNcHcp5Ezr/kwBP41er7ZgCNuvAVmm2yqeMV1ZmkLOGac
nqxbQvgI+D8/TNrPjdLVZflqKAqcvwuydUe6lwqO2lsUH2YyjOPtO9NLvUtWaCxh
D2h1ar8Nfmbo4g3BT8ts1MzeMngXkk0QTj820TiA6Z7dX8ko5yM3q51iwNa8AXKu
ckkQ9+GjAxWSi0bYcVsKKIWy5z30EuB8acmXa6Sm+Y5VCoz4VDpCw6ZBwO0ASojS
QwH8AiuMEdp9uNP3ytwblytgkdojCALzNl4bbJHvDoQzYMxY2qPpDAeiwpJl7uTS
3Bqj5IXh66sxdMzetSG89GVKlTI=
=FVkw
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bb891ae6-7285-45ff-a62e-f356982613fb',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//dNeU+lBgdLnSRGodg4do0+QAnnAdpAiXaahQuCL0yVwc
K0ZPi65yKJBca4pchrm05+05qhTiqbdbzM52I+tu+dt7HccZknn8wpCXeEBV4JZp
J/k9cmCEkKq3wjOSbvG1obUFA96utwsgIdbrUzh/OJze9DtGq/HYyZe93BFCrNEc
RM+Vfb8Ug7S00d23e7EZrP9LRcIYDU1tFe8NX2HXj7ZjOXoTG3e1VkWNGl30CfPE
qdN1Jp+2OdlAIdRwCYeVf7jVcMvpWnS4kfiS8d5Nz+IJkv6lAyhunjbymq/2JkxK
wm/YIvBtko4uQWjRFvh6DJFYg50WAZs81eeZ14Kfi/nSmTns3qvWUM68tFZaV4Nm
t2C0PcdrtnPEnieOGjIcyiCrwGtEPFoSGEVfDAxqc/cjVNzFQRsUrWWXINV2j2FV
DV3/nElZDyKe9HYo07znF5X89QsXOyPK6Mx/5veJw+d7sHJIMmlgij2p07CWPVnn
2eBzSvG7Bt3VkG6+1hZqbQjANRsBKSqXuUSkPaDI4EzvqvRBxXrejuBaJzEi703Y
0mbVQ1JiEY3jSiwVtRSIVryjkXSAsL7wWILgkJbSGMedPkntQkU7kj7ziMuBHH97
hJdObfpwQuTBWuJ5l78TaMqCGyJE36DhUQGAunQEX59oN1HqUzY050YJ1S4LtzLS
PgG6YPoy6QO5gc8xA8FcfYQsd2qowv5qnDP/kb5Sw5emLitNagaEYn3QwSR2LXT9
qWBUuGHAbQnKfVwyJ7Qk
=AzuQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'be05d49b-909c-408a-a96c-a44b15bf5ff5',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//W0+hqnu0tlfx9ADGN4WC7UAwB92JmOAbT/QQSKERn4cG
UxE2YeyqwXFrOC86tiN29CrGIHqCv2U+iGZcr8qCwanq5akElz0KrrtZEpPYMLVI
U2MwCSzlpXG6PCnCNQvSYqGK3H4oXe0X+2mmXP6zRYxdUaQP7HVvO0MAwjCkfNiX
5188QulqjpLpVAFOkiLp43ddzNP5Skz4PzJp/ZqkjYZuDy3gOnS/JjmVsVahJ2I6
6vEFZC/E74OiY6bEwH5nenxC6JBFskuhsYoSrF2oqSOr1CiWI3ZzqIOQ1mC6eVw8
13uK/Eh/UUHz1h1p/t6ivI8oaHQ8VzxzQohg1vYdNZ4Txmf34Q9969h4+DofWUxw
HWhGGQY2B3B6aTtGCKDwgxVwZ5fKJFExaIoThGF43gBEZASm9p9HlLP+/ed5s7wO
pRW0kiIPldfiPCnGHlcoOz5+U51u+VBSIfzJ1yLKsIHbirt6N4HO6htw4zx/F47/
cWZuetoJ+Ep+0L1P8xVTOt1DYLjBnjdn9S5SBlM6BrIasjWxQ4n/vSiZTwTBEr6u
kLEKm89lpxbyiSwN5UvezzE8Mm5LXYHC1Z3bUfw3tUYo43Dykq8uyEs/ns2IdQKK
ChxiNgB2wF5IKPcYeS+WTozpA9PeIl++UGl2w/Z/2pZqLoR5e7+uESgKtYjUkC3S
RAHzG3Uu2TvOD1Iiff4gd6McEUFnHU12bbM1PTWHlydZlTt4JFcRST6nxcZbVQD5
0jBeIuk/ovg/EUusIhCHX/zcxbMZ
=4Q2U
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c69594f3-8087-4c60-af5d-82b6462366d1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Mq6hNBQv4o/oeGQY2lBox01kYow0BmsAYuNEYZFxFvGT
uG3UlRB0WWZkW3gtWln2sRzqpTFvKt2XvNMlgCkK47cp8pop/XalzJ+K6zFeFvFx
annflmc8cwPcwkxSpRe3rt+DUmQhxCQiIcwIQxKq768C2kDoQf4CvBp4gSCMDqqU
/VOy4LTG4+d4ESGWQdUeEAsfNIHBB3yBZYyKGwAgRiNIMc/xpViR9a4O6mfeGsMO
mbxadhlhZkA9sGQFIMmAF0uIhfNURB5zqouIbT7INeWO2iD+e5gXI2W1GgwCDa7F
EU+11I0vjpc6yQjLADNIOLDS6tgl7XfqUmxtn1Td9wRs/HIVjtx0+GatPGXNHunN
5up9SutpWIl6c/xz/BsDSf6TlJUH3xN4TwqjgDtwqdLnDZPSE6TRNA8ODSU+VVeF
2pHJYNgK45x26ba9MaZqsrlLlf4dFowFuqZzx7ZdmqhnOHUG59BLgPoW+KC9w1sI
PE6vZYJv1uwp6to8CLX+LhJrgUHwOEku2PeM9wo5XaztMFvslXZuDIvDmQfmcO5N
Q4EPSxt95VrFJ+23Qw0rUiO8xmrL9l8Ahh1Zm5PnUWvSYgE9MP+8jh4tNYDAu1VS
3I8iMQP8qYNSFBbibcNO1AMma6ZeDDXp2X1S2Iy3DVBWyxIsDi/h3xLVIS1pezLS
RQEdDWeGidlluhnPFOhzW+/C/78+lnAsCXN+HnJDCJtcjgZTuN3/r79igGHBXFPN
JrtHGkgJLtEuLRcRFbEy19Xua/8WMw==
=5Pak
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c9c27c98-a2df-4874-a375-77f8bd5cd73f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/7BByNexOACXYJ+/XkG33TvffAHLVoi3ZuNKWqLteb0jKJ
1k5LPS5MTDf1ae3BSLTwjFWNQn0cgzz7eiUV0/0rsT0LrZnBw2LQxv+OCJovtk0G
1Pbj0PiT74+nxLazKaNk+leARJjfvDVxLXj1M4nulV1lG7AFNRbzcTmojAtR0cbo
BOrWZuq2KEm4LrL9sVVzdnQjwj9Jxpun6UduVLn4n2Jf1EXFWGiGjEmGog3muxjV
bll7TzJa0FhuTWu/6vu0v/INh+lp+maS1JL5skFRXPGzswYaQCmnHj5wYK3bQrFe
pYok8H4qPIrjJ8KoSICyy4K3cROKTyNWTPBcVncqhp8M7C/qCRewla2dgEiey8i3
/hZoI5C4sN3HbZpalHkqvdMo8IvE0THjw3PgUSqTEcTv7r/aEzSzThmNI/pFAER1
MNUp3i65aC32y779DzTHVFJqyUkiENOz8xuNfzL5slSJH86ZtGBLhcjkg9IlryB3
PVo7g5J22vT73yJHZKe2dz78u9uauW0/WSTVeLKjoXpF8PAP88XHy4l9VlQDjJIZ
EQMHzJevBiaBOw4RFVaALGTKx+KDPWcJ6Yt5e+AuUIYR+6FekEP3bN1wDc+TFTnb
o/5H2XkADBgXqyC8GumUHSJ+VM5QvoE2jKdZi4EAga3wqnG9h9dcLFtjTDBIIwfS
UgHqkLUxHDUcX1YdIcLpTa9CdlIxcShspIYoVtXyqoyTbxYw2A4ful/7YOLKcTfB
toMiCYdufy771S3wtTtG2Lg3NbklyTP6D5BgNuLcwE7ga8o=
=HXsk
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb9f05ee-c111-4795-a5b2-559b055afe14',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HqrK1NL0a8PLKxKMSJfX4cp5UPsaWW/YVZMPwmDt4fFj
AbRgRsN8OmeR/kIuHtompLvGJ/ZGx0ttYhUJBlu6rIn7bGRR/roU4Q5UlV9MccsV
O1MqJGiaJn9GjdGqikl1pAOBFeNgB95USoLmopFr9Y4QhO2y2VB6Kc7jS2r5ExxY
LgUZ6JAyKO7WNdhrAV19u3UgKVa8z/PYbMyWbgR9SY+toQGyQbm9W8crOXNT5GWj
1fu9tiX0fkMqt4u2NfB9QA4Q58CF2C8eb4oRAYb1WEdVI3Ik5KBWUoeZgFB6n8Gn
8rA/L1XAvqR0uGIi6YZIkYOIwS1dM1RI56w3Qn/l/3USZQ0tkE4voXsTPf6FuEKg
nZa2GUXcUXUuEfGWAEg73PsumaLxftawSw0CUN3zc7zD5tJKXvT3XDNu0t1yF9Ex
oNf2E518mCNaL5DAAnOJ5BLPU4s8KqluwF6b3KjnXHJIDvi36dr+Pld1UsiqVA2g
cQSlUHoWzisolsA6sousuwq3x2rmi/S8Ullerdix/8FpcFUOg1/w+ETx+z8c8WMM
EVKijbJz5mG9wyDnPCPeaDy/ekC9DxxXjy2V+mKWKka8hRvPXmSIEt6Qz5vGMfSQ
Rt5YIFMsYKllFzUMGJw0D/QqjUK1WdNOgGPScg8+XaAM7fGILEbILxm9bCJ6jtDS
TQF3gmXgDPFD+6ot/O6SsGumAr9NdCGrdJhDI5tWX8FvQ9Zpns3IRZQak1GbbGlr
bjdL6ionx7Km9T3zwwFfu9ND6jrNIHNjdES8eVm8
=7HDY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:29',
			'modified' => '2017-02-18 04:23:29',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cc4f2b5d-cfbd-4733-a3a2-9515a911df38',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/6Anq/vMF3PN0olT685PIwaZ3X7gXCMpNCfOC7XeLIPJjC
0xOf343+H98XqU8LhXMs0A3sqKuteglxVqhY738C6JuprvguHQudBptFSMKLrgdL
J8FnrwyZXkRVQBXI30C2d1+zYUEQ9JGJ6lJpWJ6OXBxhhgIlT6LayhdiqWusb7PF
sqPuwhS2t9W4dHfPnt+0+D7m7VcZn/unXruZKvQNLOfyYG+d8S64YeNOJRGw//k9
zNRqxJF0S210B/5zlssBrt7T9AJSvcf9632fAFWRKkKHQKVHzB357BWx0oklVc0d
edSG+l2WYnPbfpnwTUCe+vO8a0PwD+7nuhi/qVgE5iLfzvm5+WdZHbIYVkq5fijx
QyYcLbbgAlBuInsGK4t2uteGxQLjHjpNDtyM6EBwLtmMQVuxSxrZ3uRPsWAVQxx1
7IvCPXWnhcouXr1TDPHf5S7oqqsouSHHgVM5tZNbv7sSJKofyHOQQ5ixNq97HOmb
kNXbGWn0+BErkHxv++QfIppb0xi8gGKAkzk6ihOilNm2uq85DubLpJdvw+atmGp0
RbAcRXXL9zRfs1i1H1j97u+ES2WBJr+BfLT3wf9WRh2sacnQFegUFjrLHOTPBzit
9asu+1JeTedGZfuFapGGKMgRN8pEoTSNwlhEk3tsZCkJmhfbWUGEteV4Kzub5gjS
QQEBG+V/nttVe8Qtx+XwTGZpzGJ0BVbqUQekhJ5j20O2D+uvxB51DD+MIZRywU7N
OV7d2pj3jp4RLOeh/UVzJ5vb
=1SbI
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ce6277f8-8c3c-4774-ac5f-6515b9fbfdf5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/7BrwXSM1fEMAPDFcuxAO/TVNEf7jJMv3EdZYTegaG8gVu
hA5xc6i44VkVvWAfVDiQhwjANqey53avk0JHjJ0JLNLvJV77COt915nAyhLzNJHE
pj/6pE5laJQdvx2FxOoaB9l/3jCCSKix9rjFjcIdexB30l568t7sRvLrTI27LT7k
3oeZIzTpC9c3ReNypunqNJcstvtdup2nHA3Td0Zj6UVmBoZB5fexjwommQD5yFAk
2ycjz6JUEKOETeN8jraOKw/z6UrLDXI6pg9o/WMDwqDVab8OgWcAhFvZ97ktbNdW
N5ti01e6+4I7NtP+WeNXkI+iR/EHJeqzZXDGMZNhqm/mNUJuYdcmfqrOcPuV6Vp1
DHamMyRDCbtweZAmKyVolUja1enJbsmMsquk6ZJMIKqST2dupiWdnRZPjru9G14f
gUS2SjuVVCUit6YIAMKDFntZpqO0PmXBLeGnQNVvJ/5nAdkpeNoVVaMdgwO4Acm+
21bRU8OxvCVdVCI59Ka55RHPTKKlg7aKa7T5IQ/1U2EL4Hm0bOrdBQjR7AjdlaaE
AMqb9SkeWQYqESrGJHNLaGlxjJDRhvoQeXRQZvTXdzcr94BqkCIB2bDTLH/Ari4G
5kqvxh7M/EPrsGoixj6FdNYvLj1SisylwopPBlIoGr9IRe1oPmF4KiI/EjRi9K/S
SQGDZYR/xmjvaPKJVDXmwNytqgwdG9JJuQEyayrHLhNrzkhbhztfX69tXR4Et+jG
qacy8K5LgmrtX4NAvqyK4cPuweAGJQPnKII=
=FqjE
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf9a7e1c-162e-4329-a94e-0385bdf62efc',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+I9PN1pMRY2ILPC7XIkCJfxwUe3c/oOrLsyoyEgT1bHDn
yhtkrSq0sFWCy8MfinNisRXeUcs4Si/60RAXirA7n8a/DAn2EWiw6UmZ9/Nz5+Fr
58iqIr9DycT3NY0h6dnWJIr0q/FwA/wg7prCAiuTPG7CLjzDwPI+Gs1gadAdnb74
4//RRwS3zYaSyDJB549/TLj87b8TQ022+UlhLV3N87p7Kjywpf3UW0Zy8LzaJAfu
YeOAC+cXGPloSOFBFqGSMFHZ5hui3HGCBfc/OLgYeQEjFPZCQE3PDcQhttRTWIjL
3FmfQQJYtywACJ2pRhdwVGcphLm2iyH3GHkEUeu/YrU0aArML7chFUVvEIW2CJzN
70n2splAkjYvcWH4D18qLV9KVmTRhuRUCCqvEDI09eBGmmWZGoXCAFjip5Ty8vgp
Cglm3MZyuh26QocwyGUgLdAl8YVI99RgEx5FUFRvBt42q73KiB+5AqKSffXVqyHA
zNroofvX17fGf2QoMEgHBjhkb9uZLFVZ8Ccw7SvwMe4wJPmh/1rQi7+Cbf0xszwU
K4+zVP9XIjwdUkrFF1FnztDBL14diRx/q3Qto+ctjBIbtt5WeIeRSQX4mL00PFbQ
rh8ulua+umbi03Sj6hxVjvcEb+KAfPX35RZjLMHQgUimJ6WpFLBPb1rFDv31EJnS
QAE6gvM7dGnYboHw7i85CKtU+AXwLCte/XURy79EISMIFqVfuPXE6Jjbl8O5O64R
QVYgChbbWsVjL+HCuywD+Zc=
=OCbw
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd375d585-78df-4674-aabe-d5afa81d69fc',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//UStINARlx4HQl2fNzhrDzeuBpBhBp1O5mYrOzez61Irb
1zdRubm75B+PgffE6saqYEz+D5tYtoZ/OUtXIyj/mJ5FNtRk+vB3e5YqcMaThC/z
+j0Os7CX7dyPEYp+vA0Ke1YCQHe7OjsFi0aIwg1PtobnCTzjkZWsHY6aGdbecNlR
BriXg7na6VzOrvbK6/rNQVWngEG2dhPSPJHF569gXB5pa2jiooGrSMkqZpvxwTq0
lL/AM0jgFYo2OgCQXOpjtmAUQVCvFP2ApaWeVr+ssV+6bM3eW7Q02zSHTg1OFzax
vuTaeO/0DwX8J6CxvrqCBJe41zcZH0IqTleDC8cwpVsgSLm3m7+x+WRHaf9i2qvr
c2eGa8TEMJdCnvab415M5/Es895FqP33TmOCXAxDB4Taj9nCtqUj9aEx46VUytB1
OpwHpeUTgoPL+TFdOQO90gu1/GQEJPwmyWfmhfOvYstN8Iy1uWyVUTMg0ka3BjXx
XLDfSgrFMczrrg5qFZ0s1PmEQ8ZTMTmsik/wAIdsAkVk10NOQvUpjqkN0pRChGn7
V5kh61FTlYJ//+D9w9GkPLa0E1OTU6rNT2vP08UAfA69s1PdN9DQP2JP6rx3wvg4
lvXWVnBPiiGyjIhzD7R+y6rZLZxuc4SEz/2y6b63FldEzj6N+KLUGGKIXMYwtBDS
PwHLz985t0gyy7BM6VfE2yK6tNZ5GqH821hjLHLQhTPmPAhGZMC9R9+ks8d2ReIp
Zhw3czcjYbCTbuLzX1DpKw==
=aCo8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd55b6229-ff61-4a86-a035-a20127b6e213',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAs7n9akQ/ojylRT38gPVU43093ih/uTOobWNPMj54MgXJ
pTVAxW++H90BONX0edk7PpRXytlzUvK2eFwoqDacYDyxDCP7cZL2gYsKvDn9KqsH
4TCHtb67ErxlPpZzgWhFn37mv6nCUeI+Pby9rRa1CwEQxq06k/4DWacQdGfzdeD5
YWY8GKhaRj+pbJPPTZ12JDEuhqoEBVKRN9UtgGQAHexCvPuu9mxiVbV4vkfNNrFX
KpRtkeHal6AkFoPb61WfYp5tt25O7MXnxS2owpydvl+TBZ4lRTIuXSxSraJ26mys
c3ZBeGZi3iHOhiCcyt8gpL/K+xW5O9/f45R47xPxE/MMg3SWlor7nH3dSBHETz+k
3Wknh7Kxm1xExCgk8D26Pig6kqW/CLUrbYDu4rtSrybkcO4ts8WzJJpge2TXq0rl
iw9OhKnU8hKHLGxOkA4ZXpij2KWpL8M2HpbOd3cyGT80GmKF2bJU3gT1mJH+wvYy
pvLJvnFkdzrih6yFwqQ7rEVDQ2PSvHAuX9q8n+RUqKSF64cDtWML35siXRVjS7A8
Qa4Dhcu3HPzC764D/hIgpthB+TptDDmSwFRlv0TWZZixhdtT59nBUax8Pf6ng+kP
LRm3eOp+c/u63dP9JoVUSbdCC2XqeUgE7Z7MM0C0B4T7RrTl3r+BvJjFPp1FMyzS
QwF99XAstennOR0DufzVeI+0VkWtbkBI4qG8KJYPMaDahBXv2XB3Aods8s7YBkmm
rnGLCe/Y+vPd7JA2UhByaxijXmQ=
=iwzD
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd6237e15-fb00-4366-af2d-4f688f178971',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//ZAY35loWk3HYBzRMidcMrjjFMy3ingFbaUezRXN0y8eS
4OPs/h3DWBvbnW73hNobRyRHlX1QCu+/BL2uhhit2xPjfZulB2hYGhG/EilguIRV
EdoGDqTfNRRQSjIcA6c2TEd+TbM+OkkB3tM6zrvBoN+QMJDnLlOOTYp1G83mNbMg
ASKl5wBM5y2wlwIt5UmUEnJDkMs34ty68pDyS2h8muo+WLH3arPUsnJg/l6m7nKR
JJ+0QGiaAecuOLtL3v4YS/ZEep+zWcQGS5+sMZh7I1iPJro+mKqtKdF/PkehqrWG
/zz/rN54Pj5Y7ihhoIwvKcjCAYjSN96lgQWhUGKj2W20BdMTItJz1nLFh0APF/m0
z93lqtQ2p94sO0tG9wM3hc2Z0+ythCXnNPio3m5VyYxSHsXWN96iuhS3YTpRHB5q
rwwj0JkK81x3XvFSl2/ddQ8X37xH3dlG1m27SrIVCHx0gCT3zzXuKWnYh0GrMviP
qljZe91fM+rvsORvO9gCS5eGryMHuQBA5QqY/x7zfBkHUZicHqO8fTVPoRyd13Jz
K2FGyasfzpAGZfu5V16QzzAxvKsRexaEE2OTlWA7XYT97XG/rl88JGIz606EHrWL
dWFIbZct3qCwLtqKwm25rNFs4xDe23dyalsOkqL0StmVdD9wrzAmKHcjkBAvOurS
QwF/lALbycvVi3Y4Ysq6b4l7CHK1+lJLrVkFkmk4Eh6xIPDrpMHTDg4Ezq4K8SC3
HUk3ttw3hF2KHxhDvIQH5NdR0O0=
=LNaI
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dbebbe2a-d205-4b42-a033-72486b4cf62d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//bkDmtc3+JGHfxpGFVERVOlp4axuEMNh0/fcl1vulySiL
ZfxLeOQv7IZLnloZiW2Jh5GU7sDaINipBxl5J8BJ7N5LIHrD4VnpppgFvPWgJeMy
YZ0hBpP7SMz88qJTP/PUhHGnUk7pekLPcvSliTBZDoZS/GKRtG3DjvyAnz5XlSrS
cWslFGTNNB1T9uaECZlpA8rFte7xvOSSBzPrjgy8nps669YfYIDPrb95Eeo0Eg+G
q2AaIcoHHIARy+KTeY0TKXJBYrDZRla17w2sMoUJALiGqH3OkG2hsb5imov34TV5
uNWia15g0uwc3X/s1ZZ9P/9oV9Ja3L/hfgKoL7sQIGELq1AIsWdh3Y56PRAPZrO/
MV6yONPq17I9N3djrGxPe+r/ImveD44PkzIcb2YkcwxoqrCvAVID35jslbV2Nnw5
PlXC7CRWy2z+N+Rz+qmCtg674uopSKyRvlpfCtRdeazFpUIybOQnOnqp7Kjy7V2J
6tVooyRqBwCJeufA3kYGnBCbjfc/lZWIcDaMhWNpCaCuHursvcFg/Vt/fvcCSjoq
fYu7V7LuDqX0ELBrzaysx7sijm7L+Y8XBSy4Q207lhfrgGgiR8LhBMoYUSOlqOYb
bQspZb6d5QCk7HZbM4FP2ne2AKMSjkkUbwK0DKM7wGvJ7hMU3xl4X5ew1ZZJGZ3S
PgHxjFXTKKTkH0H4Q1G8jw9+WF8FY7sFGVr4455Vi18ltL9hNVN1Kb7q9JVnek9g
O+Ztb3ZlfamqvulpOBu1
=sogo
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e2dd84a9-fe07-4556-a65a-ddd156d96a7a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//cvv8Jo2gBkiR0afBFIepOHpBnR5E7XT5DFUP74oZL5t9
dD8QdwUhTKi3fIIeP9P2PeN9diLXNvoIzMACsnE7tLnyWfo06kUTXgMi7SeSw0MN
3c6CZAJE1JzhOxcZvy16CrZkCxKw39tt0xz4D5lKWk+yG1rCvUwAVemfZA2g//v3
vDWv/3+a0DtE8R3t52MZe4G3q/pp454kSoSKSmyFk4q0N+CZUs55QpI40jz2aCHX
uCJBoSSgzbVzw9JkmUz7ZvarO3oBrzAcI8pmp6guRp0+2AHn3IojUJflXAxvlsO8
NZ0tGe9603MOhWA6vHVQuzc5Oa23ryKvIVvE/6nEiVg2w+Y8iku9kSmGuQ/Z1tLs
PIPW1zjr9yvuu3Rrr3da5+jpqYjTVtGDp02SrspsBEd6fk8EnmksKUUGRe+Gbeuk
sEi7vQp3amceXhlcyu2wAKeKHgc9ORdjbmNr+W7gthWs6yFZySQouL9svG2lzNRO
3K3E0QCPE3uDGF1Wdt8VYrI2awjgfUlVwO7aY5x4owWau+PA8+Z9sehmN95kiUF+
EipuAzVAELsKDl/tG6h3p7eHJP2OLNudGWoJv+DRQ7xQq3Xb6W1GMgV33jAywyZt
qTeFZ8+M0CzRoz9zKKgR/zuoKv8vA/23LLOsjMYf8+qqnAQqctqCDpSrRney/lrS
QAFCno7SaVLksrJSzf+tz/r7UpnKX0ZiP90XBU88tCdaiGXWWRLCwHD8yUL6XoG1
3JNiPqorfOEg8qE3GgrgYVg=
=f68m
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e40c23e6-d520-4501-a2b2-d4decfc0ad3a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAinr3L39p7cI82/o4e/0D8wLouofxcBLCWo1QnI9r+RPz
QopbwIbK4wYMCuNjnkNt+t9Yhehzl4hzOEzP850dLeWzIn9RkjQ7nWxsxQRkjQfT
TrtJ0d3EHsP5xO/2rHkYTPd/gGj2CAp6C5qTr2UMOvjhUqNoz8biLVf4PNQGYSTZ
JfGNtBjfcJ3fXpLyX+hBiXjd1Gp/LeeXJ7VLgyovJ5iW2RksYsZJ5kiGBfEg+LnS
uqj8ya6B8bsp/c5LQknj79dl0mHPrYBsMPUg8D5JsEbYLPoyFNWu0sOGcKzEGqOP
4JnISGERcFR3Te3LIFz6Jh7PBAfLnnQo1dCas79aor7eAy+MI+Gf/8rpV6ykAbMr
V+V4lOdkI3XI1a18vLXgSJNp2pUIWEVVdcuLi0Q4QaLWRsSs/QMGO560kPpzmINk
L2XDzyJKeCig8ZHzNswqlhWGpwDnG01hflq9yZSFSWOxX9CLpLbsSVKrTGo6QIkx
zcUvJ/porLYcywaPgyAzDANV9Q0unr7KHT8G8YCINbMILB1z3TXJ4Ke0wwXLLnpT
/IAcee81X1TXU3CDfW/vDualsSdRRt83sRrxIbkx2mVSvN+/ziDNAms1V5n+AjbU
c7TltgbvJYghkaiL4ZrMdcj9CImtkMdFzI1O7KqT8WKsRkwsTOD1RKeX9dh6mdrS
PgFZ9rJO+wuTjMk0eVklYB/U3LA8ycsvrV3L1Z/qQeNWVioOf5V+2EF3qG8GeURg
62BvSjO8Hp93z6dIGJXn
=jbEY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e6cb2718-6da9-4700-a827-9c13e956f4e2',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+MQIcZ+S1KoM6+l2gnrG4iIeopK1IZdXQA0/prncB4K3A
7EJy9stt0lOVfNVyVFn8orPCMvgk7lATQqw9EOmLxnHKVPWI3BdbuYN7YWFuUbK1
IgPqdAdYTRhyAYimuAD4DMcrbl55icCqrZWL5KJhs+qNUkEAISTDlgqvsjbjPXnH
TNsz0Bpk32vuxJEwd/syKyWb/TrCSIVrJjnG9w2zyG09MFVP/1rlM6pJXJkEs06H
3mMoGmYjY8iP+1SoVSYjtXFrC+Hu+uFvBippgHKWeKexed4y+XY7Tr4ZYWKK14uN
u9WQafn56QPNmRYoDSCyRzZzlYm7vxSjxgaBLTVftUZs6E8i5wyc25tslX1hKh2J
5jgo1YVNYOvtRDFCl9JcyECeD+4ji6U0BZsx1DmN85kTD/adS8Dq8i+PuKLkvyKi
YJxQyAe02yzwdCVzvgva6zDyMbytZl61l7ThdyCkTWakd6brzOTFSsiV3KxRNSCU
S4ok+5w8AHO72hwMQ5Yo6mj8FpyWgUOznrGZIXJxrBGrK5IRmG7bgu5LBXucGT0a
5VwBdf2c00NabRBHcEf2bxh6hh/iMmYZQtb8OgQRcdq79uHXL2h/oI9ECeWklE4W
ehqw3jLwQLqLKhoGUZVk5gwi68Hzl8H5jDGsVmDAslukETycWqGsSSwZ8f+kbZbS
UgFp/Nk5AG7v3Ic78qAh5s6Dvmzs6KJz401yhRykMewovMqhI/qK91bYJq3G2pWo
37TTOZh4a022ijvrdBXFwm5+IMpWKG/hGAkGYi652PSDtdU=
=e8LY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:30',
			'modified' => '2017-02-18 04:23:30',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ebe0d2bb-6e72-49a0-a874-ebe788c16f3a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAmNyzasOc+4NxA71m8d2ivirytbnCjQ8Z+lF8qxvfzvLW
rjrZz7hols7J/hsszj2f7jGn4i1IVWt3VIK/s1WR1ggMa5bU/Dtng2CddDNEB6T/
gNLwzhCCvHFf6SnpCwbF5sQsSn0zb2oaCcZktEtihLhzWFDiz1NPMd4nNIgVkk29
tG9cx8BXcpTYtZYZKPoerzb9FMh902yN367lspDo1Zt7mjbZv3eLpj4AEEVVk1Mz
sk+hykSliMqXOFca64z4i7qZ/Mr1NTn9EcQQqt/7FwSuCPi1vH1rz/B+mEZwQ+fZ
svLE6D3oS55Tg1MUHans5OWumTxM97MHaRKloyegN13vd+/P0mCL6LggoqGxEQL0
ZYOtRdERLSL9oHmR9kpCMywLrVNxl6ZBJezhp5dHopVL7jRevz1PPFgj1dp8Haf5
fura9j1BtmWPRnACa+IE5KXte4RKW3F37GraGm7YJb3s56E1JVHmL5F+7lroRuq4
3fzqiqyvQLPJVDjoMmuc+At1c0gi6pD9gkj60+t8sB6nDpNVO7/pXY/ZtCA5vz0y
+ufsGSPrYXEWtJrFODf0sS/34RCXj0qRoi5h9QVR3E+npaud4eZdTU7HzcaQuUMJ
J41VwoMVOx9CvWjIlPgO1lDXFt+G4qQGrNJAZuK5Pngl5usIg12f8Qo3kYg1tJ7S
QAGyYNalsrnBzK/ih48IcHhaZGD3REyWd0gETWTB2RORZuTimS/MNXBRATreKtkw
ZbSiVehujoaurT7ftNu4tAY=
=hpah
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f1372762-a053-4528-ae78-29a60ba6f26c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkVmc3kgdDLdx0y78PV6anT1S7QlrdlJgbZjWH3wDHEQ6
Dshf5C2DnEDWc4PT6eesdBkX1DHYZMuO6eNgXbRFiR3nsYARSC4MYPUahP3Oqx4H
Gsum7egrgFo1qMLR8D+mKf5/NthZtenBxMe+B0EdBqHSGIFMmsxIqKm6028MMsVy
dwxnNQ861CUYA3MR60JpnRHgdIdFyI7SLgbohjEOVbydy2B7ZVXxiocIh/qdhwH7
cm/2z4Sk8kWnUj5sEwuhe2UslHDzoPcRvErGSSg6IK34gsTthrdHoiuo2Y8oBVZi
nxaBmZDH0SkjoZjbRGzI51DVX/iSXOxgHc+VmI2U7eO/44wggWRiABaSzOtRSDx6
4EWbpkB6Q4fQQ0p154I3eyom3ilnefg0d89lGomjppmLTW+eLXGNfaADGls2Hc2r
rfeVfyl3K17EvijnpO7yudF21skCvjh1+g4BbmjiB0LYRGmsk/hTjNgP77h1U2ZZ
WAw+aQyjxPlswCWokX57flXGeladZ7Cpuomb95Z76ghuNm90Mf89FvKMJ5uyOS/d
of6TP6VQbJ9MDHONE7bGhfjq1mSCkxJ7wudRjeDBin8KtrS9zSw7El4IPllbX9/4
d4Jqle8cYEeoHIcRX8rikKVKssf+FcE3pnjhRl6Pb9BjvDyPpmqGceX6JLYp8s7S
QAFP//UUbuEkS6H5lv8AKv6B5sed9S/o88c7BKiu7HfWAhqktHcSGKjt6+OT78/T
U/1GPh+RE+XNK2nakgwKvak=
=4vX5
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f294c8a5-4642-452a-a1f7-c3f12f77a461',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+IetuhnsWUXb7s1ezU6wSndevTI3PwJRXMEI7HiUXKflr
rFL1Yx+BVaEUad2X1vETBkzoYDd+pDzoEVMBvK08f56vwUN7Kh+M6g+Sj+O43XX5
yPCV338NAY4PeaQjxaT2ha5wYf8a5aEGrla2MqO++XKWsCFXnYiB4yLGW/HnbAJP
ewSu//rSy0faLF339Z6kJ1b29dq88RaokG7YdQFkTfG+opr3KrFRve2aQMkejLxh
NbiC7XTDgNz9YPA5gQmXCweQVkXpKB8vo+xuZskvK7fkW3yEanwmHtOhiNy8Ovwg
fhOFUe2BC0FjI6BDX/tX+DkkJIqUAAqr1jmHTRHTe1AgJ3I1+CnMsX7WWhCbKzLZ
Z8YKYSeTBPiYtd7WBSEHTuEC4hw93O5jIwBKg2xiql4YkxQ95kGqT1dQH4SObUUO
nt02T7f7caYgr6NYiaMzUM2X2ETxGXPOGMJ4mtkIVNiIP4cZlTmSz/a90mbtmuSy
TvPcjgt/2Ld8vgwddgc8DR7lcEW+S8OKF2qhPDfg2rsEUEef/GLpIAMn88O7C8KW
OokDyIpRbpwl1erOdY8XKvlpEow2PE5QTln2MdNWD+nhgWyBxnBuZl/j86/NIKES
WW++ACiLtCLYq5al8i9lVNwp6ZOxFk05ayavA1samPWjWxXCoHiSmXJnFMM/g6HS
QQFM6qiy6z4yKsde5GP/QyCAp6MudgiOBTRerpfTyTlHBbDgqzKhfkToK6s4dCjr
IzCJex3bZhJ+/MX4UM2EQ6P0
=DFUV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:32',
			'modified' => '2017-02-18 04:23:32',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f77a1578-9d76-4077-a760-a9e1fd11afe4',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//auPgj/RlmSjzlEqZnX6UkBrXbszYpRsLvtLhXd8T0zuo
twITUi7vZ+92cYalp11D1EDA6yE6rCJkS+6LD9UZX06XpXJTyPo8Zr7or28QOV6I
xtrXPxWGt1le/ypaB3eY1LdCc7GE6Szzno5CMS3x+Bq3LD7pIRHLQ3E7QfgC/RYJ
X7cDV2/6EVL74F3JmdurJWu1FIK0nXSrclZAS+a/yzGq8XrpJRj0WMp+WVnRsLXZ
IH7q7TG2HYXbT0cRwQ5PGicmZ62VheAXE3m2XapjYVMvh7hFt3UsHa6QBlhrIXBZ
8SbATEAN44TDPHjawHfgEhfp2mT2dYgjfwF+2z8oUk2xZ2QBRI0u8iG1MKlj/anS
QqrKl5HXj5tnLdTAGPg4AS426rxRQ2zAiIePCElrJC3pJbMUYaWuhlglMBcy6TUD
M7qY9MQbx6jPQaqQZXofpmHtESMQLQ1f5Y/o3tO2LHJEBJWlTRd7eFLZ3UrF6LEg
Rqpm5Y8ZrT/lj//Q7u6lH8oTHNrDCC1ogbaiDCBi0MTTm6JJCDxRaHcg3O+03zq5
mbOVP5BcsIAaYz0rqfPtyDtLsvGA8n2mXpRchoFtRCEJxKq+1gnE69ppQhG0EOIe
ru+vYbgEg0AaDP6LoepOVjgWRvw+7L+qyr+oHyOye8G53GUeWMb9xtlWbkimRUXS
QQFnTpD2XR0DPCuEF4nsUesvXzDlxvOhqkAHiQN4hdyGkYbmBzsVA8ODu5AL0MAR
9nbB2xBw9+tepOaEmmERqYsz
=TZpD
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f77df63b-7a12-44c6-a99f-0c3aba523379',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+Pm6RuwM5Ih31v+8vFjMRyas/qfDmW5tsIE5oDO++qi3H
I8YyXRafJ42Nvx6TH5wSEJQukmELcRjrG8z+ui1tUeF6f/zma15urJLVmGjL7LMy
Yu6jDnZqc359KUKK516XMtPPO31iFmdWshMlk6x9jVfyImrSuSBSmlCWxEQcrGLw
ra1IUG3W2ki7X49xMvZI9ub3XfopnpbDPefhZY4akUEMcQQht9BRKoACwZPp8Oy6
hzxzLlGe+f7jP9axUHe0KSNW3Ar9+82yBmG5uLZP5aLtBaApLj3vyv1aERPs8kJx
8HIh3LEeATofOd0Q1OVMiSjuwb4EDYaabzMsljSZVUIeT33+z2bqorI2E7g6vuRB
IKxAYYWDyQPTfXOUPoEYtt2KDA7jfvRftUmTvCTQt3/BUwQ+tGsfmFgv1Yu+2m4j
kMpg//l+xE+JSaXU9RzoJbdyMvDBCXbX+ONW9Cyao6zUDXlbM/ndnsSLtOh5bA8w
vwlD+R2SGZMU07iDGAbutxeBCN/t4uNUOSgVn7FW3yMjkvpazTezbyo3oVb4fZDh
1F628SiqNf5LiT+uEjBUdFaX6ogeDqH/bApuOfaHP53iCaKcrHiXUHtg4TAJw5Aq
XwEng1jTWGKSolJkgDw0WjHwasynVs52ExFwHqBOypjLHMnF/Nma+BtmSk9g4sPS
QgEKRuIY6SzsNHzugIV0/ih/FU0iUr4woW+7tPTJEOVCPYAGvqCOQY2/+j3d5v0U
/W5Lx8MXfvKgPQVChejNukKpdg==
=UHnF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f8220c99-e064-44c5-a642-3359e965d132',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAoKRuLKRRwAaDbbchcEbQuPVXhaZQeHR8fSbvlanEztVQ
VuCnNPKcEOdbWXyG8RIgLB7sObIkXBJXwHui0grcaxGWhBMmlYvrW7k1wKY4cT2n
vVGK4b5t0sRa1+LjtlULJgnpQNvYOxx7nXVP0lYgKXoJkDm4xravVUM8u0Sv8KXD
Rm69jYmsM30E3Pzvav+/LgrCFORjt/MzbefQL5zY4DJA1gMFI1HRn2ahCZWdWzPI
2pZ9GLfAtyvswnb4hdo0h4mpffFb9uiAcsAAUuXatAfPv2ILu7MPOI4MCwYdYwBk
dGEPX0z1gg6HnIhSDAoZWXChfthEPDCaAZhMx2Yvtkt9zULXQxzBpgghCSoNjWAV
kfXARZ0TAq82vqKpKe1JbUcpuiZHp6EyQlcqSxwDE7otnlZq73CWuvHLk5qWJLY9
CHQld3d/ZUy0EocmtNV1RKcn6iAMw6dhEXpAKdA0E4lsCfYpnHx+Q9NzdMB5AfFm
rLC5fvs0oKArL9Wa/DXVOcSFIMS15xy54lF2cF5SoX5qvJqrANFGT1WS2AECZh8J
jWJuGQVtwHbSSo/tB6QUk1HmLmy+6BLPK0oUgMAQu3xpKpKPl39YFaIB9CVjRrPR
Sqt6d8bv8G6gftq76MPTYKi18onIpQgBmVshAaWq/xU714WY8GOJBaDYc8XRvxnS
QQFXmUmBLrN3lHzvVSkRc+mELr/Zr01USI1MAnzneENMU9908WaeMORiVbC2OGXa
FMnQ2dwHWiH+tWHMahYP08OV
=vec8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ffa9c2fb-e7d8-421c-ac14-e0b53e93a691',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//TzMHpr2zs1Gw5K/6nlYCZGK+gQR/tW/slY65Qaysu7e7
jPcqnFXUv+JyDBZDOssAwut6e/y1hS/oiOUszZe+JW3TqTv/gzUSHLfuvs4B8TEN
KzJI87BoTBecmJIS3fOCYmre1rbj2PUyW6SlhXL/aKSZfaSmtatlo3Ubco6Fid1k
YRjhF2yl3SYvQHLDqKhAzacf1Y4zavpxSBbtYBkt4IETTAmm0hOa5lkjvFZwa4Kt
/Y5pREn5LvVVQ33WBzApUnCtfxy/8F86klVo2VX+li5bwrAYX8oLS1TuWoVawdmi
12oQS2FQu6sQgkWXZ+txwpyOE3Fv7xoPmO4LL9521oj8tY2KPKn4m3gJwtorOviW
vxwvpd7lNxb3iz1a5IRKfWa8EOfupNSfR8VeomfZMdKc3l08MzcHtAC0gTY3xLBl
7/9TxOZe21P4D10uHki8sWr0WIwv4FvKzumM9EqWtL7irEXcajMU0J/+jz3zxXI4
x/qfnw2513laS2nobiSX38GET1k8RMlgJ1LPr/UN20qa1yG/3uoWmnvT1yewTMgA
g8tPJav4ZbImMmzFrGc+ceyQ9WpVb7XylsmBSVGYLJzHRwg93pVPUsk3wfOVa8xR
KX/pmHDDO+Rf86Qfm8zuLCkJi/ggD51ckJTK+m1l8W12EJZLVdb/NwEgOQDYxGfS
QQELd/GQtiuy8g2tZzDNHDT5DCPYjhHhg4dJH9PD2gt88h0Bz7RmPsFoVcao9J6A
PxDht8g7RAH/88gRWBiDqZ5T
=+1hz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-18 04:23:31',
			'modified' => '2017-02-18 04:23:31',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
