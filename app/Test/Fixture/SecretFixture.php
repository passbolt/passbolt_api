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
			'id' => '00bc0e8a-451a-4532-a79c-a9bde61fac35',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/SDvL6awc2TX5RyX6t/gb4pdkxTDJfk4LkXaqmsftobVV
kcKwylPCK6HFe/jhfsU62VpUF2HMPv/DoemdzMQVhncLYtVXT90u0StNTEdeFRvV
AeNHrreFUnzAe3n9u1b9CuJIS3icPqhttDTgMrPDc3zyzKOntHTKaANpc4j5gkJy
nayAGE9Nw1XgIlS6fGkc6Pmw/IZHrH+eofgdsxkz23/jxqEDIzQ4Ywg6m1PH/jwj
nAyTDkinlBuOI5lmIKGJJb9OhnpLI2sORJFNbUyuS5+6jfDQzuycMoPSV3AXMIT5
K7585/rwM1EQS24e8zJWGh0k56UdSNSfk1LDz0ZoSdJHAUPgOkl6gwjrM9QMzHXl
PAUeACzBHEsXP4tHP7d2hfLnG5baM5TeQeRbvUHCshpVRlymfOtMaKNxh4JwksOV
JeMGTMNuFt8=
=B/cr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '029a9b0a-41b5-40bb-af2e-706d2f7529bd',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//XkKbT9YLG7YEKIs7k150DXTpxoBAw/kzId99Ia/0BeA+
sTApZCsr6itZP1Jrsf2+61ut0iARB+hBwnvpk58CvN4s7/KktUIp3ZSwRs6+1aKZ
xYdt966n1/6CZ5P6+0YBYfUjgvPbro0fHVMgxLGb1SgPgske/3sUNle1N2JU0iR9
ZhTQISPpDFZD8R2Sd35uud7L0l5TwCr5Vq+QR0SnFX8z04z1o9EAWg8v5XQBD/08
686dPkRTeSpRS0zssm+NVTpdnOQEtgykJ9oAaPgTokHdWjb1WlCYI/P9Ygq+sojh
GJM+/MkwRdw88O25euoZRG4cpwQOXMBOvxEC/Ai4vv3rSehPfeqC/pgIk4+Q4aJv
44jehnWcyR29n0BT2uCbD6FjyfKQirr4aSl8PetyuXTuoZPv+4kGHFtAIG4XIwul
zhiuU4gPMhEIvjN80kmj2Y3/ZSsWc4FHJ+ruC6YZs44PaHjzCdBQ0XRAaXQvTnAd
YChqF0IHmJ0GLHnYImmwK3sYw7wnGaOESusGQvJwd6bOyVsKwqfDun6wK+FZEaHW
TGkwTzSxlDtkbDSbrJBo4l9kkKOAdnX1Fg6WJkZrlgDTkfRiy49Y/vRkzsLC6AAL
On+/8a8DrZxK/fWgzcntXI3S9+aXG7LRzM3p+Hq7wL32Bccu5nb4i6cMpI57vIfS
RAExCs9vGB/D64azwKMZw4WGWJs31eMArfidzaHxjWiOPj/lOPh/Gm4jsnzjM2sD
4qbYroMWcbVeQ4+qhP/oM+TGsM92
=evBe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '06eeca33-050a-46e2-a176-b7b43645f6af',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAtp0aNXh+bR1vELHSntQOgCwkCsNFu4HaojsStpj6v9sf
bHRI16SM9G32kthreja4tYjX4wrTTYbOvhqTp4Ik4JhZ1Kpi0flZauxVYLuJHSyA
cKHzyfKxNl25pDoFdFkP/+w62rfd62MNRw8tMbTHMf1FgRFlXvxojgDDCdPcihvA
6aSfYj2qMoiEWJxRLPa8D4VtbKx1jUuM+H3cscpFfB2WaZwXSgJSIAb+AgIfAc4c
ZKeeUvSB1fc0v/AwUlXznS5F6993MSg6D4NIgKiKJCIKwkE+ki4ugjbdpHF8v1gg
VjuJ/JAfRzoctELGsZ6+LoIgf3eUrs4mzXoHuGGUNtJHAWiyXZf6r8ZBQkMsVTKN
tymcqMnBjrSP5LO1lLsyY+7oRPKiFrG9+kqoBoVyfV/vYDY0oZz/psKJcFoOv4k6
Lu6ZmhLFjHE=
=m3OX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0ce2c571-9d5c-43d9-ab1b-963fb03e0dd3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgARAAp5jn0hjaJlY8Y1gl76aDGHMPn95qGzkKe7bGNhTnGo9Q
nTcA/Il3vZMOtAGg5j4n6vIQQ7mNflO+AqhiLiPZI+I9UV37KIZRfEB9y3lCKjJB
OworE4PdIO2rGBJMl6iVI7zzB1woi+2BbJdQiGd43/+v9i/UtxtiE7DGt+AGNcvY
iHje6Cq7a1dKmMbpbh4D9bfuLfdRfVP+94SuMpa0sos4WCkjkXHTbPgmAj75qMwu
xjQl715DEb798pANB1at0sD66tpXrgrS44uGhlVN8KaTb+9LhMWGjQyQo1RWbXyC
VhzkwVDyK3NTh7zt+n56FJSXfkyxPtqFzcBBLq/F0rT5zAcs9PrXyYeYYXhbpy5M
awhX1BboLGItiJLMcnIoYPcohSm+3q1BOqOIxdUBnDqp5JiMDx6NEaLQVuvlfNZk
OUlRSEu2MZmJOhhn1MsSzHLvwOLlXQDzT1zmKbOzeyXNcDEpkTko6AmC9CDwp4Bw
xYxScpfx12+oG809kjaWa5B7mYbKvk2WIADD6gqxmP9SWFp+2CaESUoS1eo+a/c7
q+4ZfPh4QYS2fCTgQP7o6+hNIALTBqQz84bAIy7Brv70QS5iLdMZlXHSE9kyXK9/
ADHFCeMJbV5RjOXuDpBKDyq9rC7awKWpUQEi2/gc61rTA2U0ZRybMI9kquBp1svS
RwFvyVyDCV8fu5BKrefg6B3uNakg+lCj3dUhWfCTh123aaDx4jJJn0ccCNYP1X3F
u+UDk6Aadkl7FVHDdzHHt+M+U2EqY6a7
=Y7V2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0e500bce-8a4b-42bb-a1c5-b84fdea435c9',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA+p38wQEIh7oARAAps+wzZmJLH3mZ0Lfjvw87j3C6C97dI4rASzdKyA1+HXS
G2nM1FsrI/xV6KPPabFXjNm5VFnUkT7G0ZQorsEsRgai0X5SdSpQimx1iNYVVAOq
rnufFk3uVAPufxWCm/cxcKkjwFRRHz6Y/fFKW/VrtC3NzJwaNKRVtf9Adw6040eG
yAa3dYOcUDs6ZnR2h4PnnvRA2bCvFhWaeVO1Y//FIBtDonopFxGsc+3m6B6JInxz
EPLsJe78U1eDRA3EJQWVD1SdsvzmCiSIfRAQLy9WPaSYL3Shw2/xoSBBQcQRNNnu
jhk+eNwvIgCOiXV8uVXGJ8FGIfMPGxZB3PnlVoWYeCnoie63ZgsCQpKcuq9IVj9t
4Hx/6bbvLIEW/nu6mKChMK6LbuYrr0X5Z7+6mCEVr9rSsT5rgLsIQXVM3QKZ9pQ2
8l09NIvnWPXZuvazTDTHzUQJdrwQopo6Pe9R5qZVEnFWrVx4cYfup1gvm/dyCyvw
D7KnVVzfz4pDArDQleRmkE3FwHSyvdKKs1QJ6t7LBciF410b5NzP05I+T6jaZUdK
n1d74dAyedWqZIEeEt/q6rrO3Gb4oIm0be4Djlgq1jH7jJ11TxL6gedEX8tkCGc7
DJKL0mFRBc5KEOolyC2Ney+x9pItbcbuVnpuI8x6SKxSIJKkb/QHWTJqVojR7jfS
RQGTH5mLfU0eMyPSSL7SEiacnkkJjZU5Mgu4eu8VGV8xd7hLdnA1ozqMaqvze40S
OFr8su5Bm8wvIRipl8kMkqMtbfB4ZA==
=RGuZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '12e759f8-2e9a-48e6-a0b2-5e80e5c87cc7',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigARAAs0qpoLpP2tyZhkLWbfCcabMzf1m30W0R9avNPG/bVWEv
e5nOBxCMm88UI5ROvYMH9iVWbYoXz/nXeADwog5NGwsWSYxL3Jy4twFx3Av0QsYT
w0VykYsQc0zbWUFmuetu6G4uIdtLTDbm9QeYJcaQtEaJd9520TEggEMLgnw04jN9
7j+gABKKGclVOdCYkQGUt0dppmledBSWU5WaIhhKSXJPAksnD+9PdjOHAQgyJFC0
Ao7u5lomCBD8epwmQcSmSbxdLmtDXAjxFGkP0hTYtBoIcCrtV43tFbz3IWKCjBkp
O37DypvLApsnOUjL1zCKPVbPawiUghszNRA14eRZ0pd2z9r8ILU7L1A1w8cvw0Le
mjApR3rToinpapRa3FCMXgptBxuaZE+cO5163SzulKWgQ6i5TUdQL0iojdgSj+JA
tk1W4Ax//iNWaSuF//0R8pxMMO7fkSDbjd0D3pmLfjGH89wj9LabX3Sk5jFFtXNY
SbjPI4ev66xJqQmraccLrKO4u9dMBA8ftX0WMKLJJmeOe4T+NC+ujqb5gV0J4h/Z
zQbuvAF3SVtObKi9Rz7e2mgFBT8FO0wlFn7zs8LlFkhwwxpgMjsq2bGTp/67QrYM
9dtRLUOXipsScMzr7R8V/dL7O4DF2ExFzBvMqbmFc6+SdMHjZJ9SOsD6TFDtEC7S
RwGHD2NXH31Glm7gmDpZjBBuCQzDhzg3zXV8gO14gWQb8XxOpPWNH5xHXGuDh0lJ
/v4o71G1tgWL1dOCKm57PJbuGGPGq8P3
=CG6T
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1441755e-6696-478e-a5dd-36fb438a907f',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HBAsFF/i4sc/FFwNRl21hvgZCuR1WuyNZvAGJciS0Mp+
h91D+oKkUokLZlnyEG1RYphgVZj8B9s+IRNZ+gZDqN3+65v0/Oqqi5cnWMPu8n6Y
F02/iYRZDpf+O3qB7MfBKC7fjd12UCGi/mz7fzXzk54dz8srYXZye8emgiWH6pnf
kEiXFNARNnk/W+EWMF6mRS928A5knwzuIZDGNYX7qOf3Q0vA6jXGB/7NRyRR5cfw
evHSDfBl7AyoIQbI9MXYQUTqYtO15Yd5CYemmabihASbFISAfTAXQ1TezQl6XAOw
9NUIbU89f8Huu3JQKS3ADSjIcXCnpek8FqtFuMEhedJHAVxPBaJMqbwKHY/u45Xb
FpAWwuZvIBtIUSilgOTNtJde8V7YAW479I1dT9deLjGz5NtNvcPa53hSm9wpxx06
0hgG57QAEFc=
=AwH/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1ad98a36-d9f7-4a1c-a99e-7ba1ac6cccdd',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+ARAAo4Y2ubhbpQe9QTEX4HkemjSMnyamCH2stDYaYWMIUWZ/
lq+IAqBg0doglizsOw7grTxwr6irfDcpdUIefJeoV3AkvUL4ie8YmA6RChSYO6/+
LG4sTnNx6D8xQ95rsAP30ylKyG8l7yr0doSsM0amhgenE6hinhB1nSSFGmy74a2y
9d0I1H7n6zHBEQWA+3nFRdy8Hf4nTLk0U3OBNhx4gPPIOr48a/Jjho4QHWLP9h2r
71ftr18yBW/7wb2tLc5bgSX6EE5Xa63xtHXra+xd75YqMJDRAeu3XPHNn6/8tFwy
p/vkCq41tARi6HRrWt8adt4xQcv0oeG4F/EhkRFE0KdpqyrdLirrhvNPwIRkQ49w
IdLq1542xoQ8RIHNim/lobkeFbz/t1jd1ElRKbL0V+xTgYxVy47WIr/PGlbttnq+
3tKPVxUz13Wr5RCKRasCnwaFcwKkwZXyzPJ9i3LZiDP00/VSS+t8ez//QZ+bSW//
tCzQoYlBnIRH+4sq8ZfQpe9dg1/pSsQyP36PO6YH1yxXt4UxogNhqnym7deGemak
IlKMGum+92Ab3nyuGdJcdGdf8IyMRAtdsNHK5rB3jGUXe+MyvqQP918G3eQvzwrT
thBicdgDJtAUIEx/xWKljHbPAuV6oR23oK8PSFPA4NDvNGkYFCVxJlTobiurE/HS
RAGfV+qz20xjqz6BqJDyuRtd7j5iymPo+hahNmeFmXEBt7trtraAa6vPrI3ia7NR
NHY1G8zF+BtMO0T3rsiWRskvrDMM
=5vMY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1bfe674e-b07b-40c7-a3d7-4f230d51c334',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ//Y46x8tWjIcx3wxWxRCIq3uEVK0M+Gtsi8AlhDHQ8vwHA
MfRylbKwLBbK5vrg9UEYq2HtCwhHt4IUY9YHZjW9+3oqbQUY1bEGBN76eTAAp3wF
10DREPv8/Nd/Nd3jYVHjq8DWTwrWell6axjPREyZooO81CgGM7CGbwpxJX6La/lr
wcvoBOBi5u9nY3PRl/KJs5dMa1w7dQ963+xf2JGRD3jTqMxRKyVxjt/oK43mWajC
5b8LhF58Mg774nw/TwkcdN+Ho9Is/rD+g3VQ+UWddMcn7xozpAgP5OEtUnXhSr+I
F2XQjVFGtm7w6rG8U/LKQj/+qKHUu1SFcci25C3Hgx5e0fP0fkuy/SXjdGmZbE24
4xRwttiDwk+eA3xMrUW34V5RksX+x8GK1Rj9VDmEHoW0+x5pnHcmILudjBGSS60+
qwASyel/cYRZh+LIqWN1er+C209pSxFG6dbStibQTGHmmULgR/AAsG/qXwOYn/6J
yv+QdJHxW7N6C+bxCAYUV/Ums2i7vQv15CEt8/aG697b0ZHqL1Un9v6HRAhJ+Sgf
hDySX7dzoD03//4I+CQeXOQvEfWFx7Ta8dBLbs1lz21kcY3m+EGDmS+7dVgPrVY3
/hGWo2gauGgkcX1PHehurVnlNSCOxWm9KTFd8FBEK4OUjS8M8ByKAdGKe4A4M3bS
RQHp+yoV8nxzrE1rlUxC0yy0hFhgHiIRtdHTK8F21Kek0MxEfAg0qDndMfXGM3Z7
sp+A1Tm2ZCtvoTBVIE0NuITsGx7f9A==
=Sweu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '200e1cb1-cadf-4ab2-adf2-593135342136',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//Wv0i1HFHVtHTjM0mDjrhG0gONAaBzoH2u6fS5+9577zg
8Q1lK/FOnkxSjjgUmstCkckcNWiNCT55SJXv8uUErMBSwND/90w6k/a0wEY1VjwR
BssAV3FSGnS2HP7qxSrAD0EvYsWqBguM/2kC9qe9RaBOYxJc9y/qsVeP7QmrbFxa
suWmaYt+T0mqNjC2mNZEXxqMUNxa4yFksVVlr73u4RuhsP6qtZxeAl7dJGS3XexP
4SD9FkPQ6AOs/KKgbBIN1Z4Ow4Xd5qXZwRXGNcO6MZQnDYdC/6afxEHWT2Mjg8Vu
7WwynLzRDHo47GSAmz+dq8KbRRRJRKpLNo+TdrBu3eeHRFZOUmoS49Bjgd8k5BzS
XxJBQD33c4OkX2eph3aYSIboLe0zasjlzPEwUFuPW1j8245lMrQWWE5fp+53sB19
DStymbX7JDpIN9x6u+mkkMI1tRAG+SY4SP1VBU0rHLpSd0y1/M6l2lTfzPfwuBF9
sIC53H4C8UhhrP8iXjrJBlpt8oXHt/7vCbl0NvmLwkAR0xQNK4zNSMCuiJfGrffY
8M2jv33J4UumZsBM5NeCVSyFOBc1SitQNFJMK6RKQ79aBSVD0v5L0hNuCj30Vijf
4yUKGGBkfRaZT6r+SxLR9W0HO8t1Dqc2tElWa6G243NRVJNVkLGFV8rJRCUTLzTS
RQEa7SHl+9SarcT6VUs4bwoC7/Zfl/Poi6g3ORCZBTyG5CdT+OuIlJZKbMIa1WmY
j//mbcWVrgPTaofvdpzBQxk1H5n+BA==
=ia3e
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '239673f2-d4b3-465d-a773-781e83a33df6',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAw0P12ReHhxtAQ//RWLyxQRX2T8pOT0sKhjMQPHx/3wxGHZBcpQOyfLAC5nz
/wKPOjVwHYZKimbtBALiSjfFHwPJnWjXVKv5y47SLJYpbNjwEdt/DcU0H+Y6TJBK
Oo3RnB2IJfHBy59p75KvGp8gPLoJk0/oV+/UjtWmxEgZELA4bdRsM/a3OqO1La0g
p/OibCeHUFVzUiVqkdSFYIe9ywPmvXHEb5osZ8sAE9Wy/8n7ah/BwjnT3V87vF4j
e7gG+aN3MzU0bEI09iHK0MzydS1lR2i2WP8bfPgXOq4oNi9+AMbCPZreb6r6aNYD
V8QuKR3v1xwnGYm+fGMn9AxoQhXAdr81FO17LZsXN9epmIr6e8B6qolwiIic6T2t
kEIprn8bOYZqSRiv0svR/gs8ZO05fu5sdLo5rBpNlh3VAZ7opcTycQLKKOtByYbg
SXza+HRHptJjsaHsEZCVuHM+G3KyDZlvvHkFc5Hzvhl/hwRnqujgKjdMwyF4nJuq
PMt7kpVwKQVnJix4n912QQ0f3EZNI5sRmztxdC2jmYcnVdq0T0/2ha1i6dW5EH4/
bb44xDtIcvhqEW+JDMOTMe/mrBsLEFFLziklKSKS+8NjI+GTyD+09AG62VbAEgmM
hQkNtCM6GyALZNBSWa6d9/UxZYAb8RLcIJk7y2rhaMxUMgYHKQAsDqMyB9WN5jLS
RAFYb6Depb51S4Ykcc/z2edbMBY/wksetJpVIJEzl6JnX1vFdqqE/nupa+PtvooC
sxlENhm/gTU64lI36VTxkl5H8Aht
=41cj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '256a7cdc-1781-421a-a4b6-3bfae8c396f7',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4e/DeCIHsAzAQ/+L+JB8hmhvMn5yOWTiWQhLwNBcNktKk7+K+uKFYH9SrA+
2YndPB6X4Rhr8rkmmR4E7addWqv2lTkM8X4lYerhg8+gIHTJg9ab3r4kBa8OW8kQ
24fLabq4hac/bjHl/Nqzvoe42kKvfyFNv8LgL2XpbTGhuIv77eseaUeKMKnWJbIm
FjY1JVEWm/SM6kUtCb5Cmy2OlvVXuukA8H6I/Eo/KWvBgB1HIEMA5aLrlc51k+eU
R7Hx26NF4F2+iNH192xzMTY4fpox706Pgmm8LJij4wQ9VVTB7mQ/kwudd7YTaQkx
jz+L07eg9UWbJCGs2qUnVdzO+P8Gk6DaaLsgSWJzwc0gvlcWH4l+t2RRBO9be2SB
KkT07NCt+YjCiwzS21Toi8eEOhT3mviMCS5yYyg3B/Ak9Fp0oFV8sGHE+3OKm4je
n4Dd+m2bJRxOeNyUlpQxQ8GGcSywjatm7WerKDy/OoJ1zcQEIcaZeesE8d5qPGo1
9zBrI8SR3svE3X0xjIwdAvI8XVBEXN1j6azJhc27aEEgUHKcX2hF29DXAc6fVSAW
Jq0Reb+jA8S4YUI1qJj2DBtgW/6CBP2Vv8Qiw/tzT75/CQK4CmSlTGxLyCmB3qjt
UApg8o4rNXBCW1ELhUb8i8PVFMJc0mny93cktdxGm4W1vKFB1/YfdWYW9bIKMiPS
RwGvjAJaM5hFQCtoVDrupvwPWxP5/9TE0VPgS2wVvRNvcSjn+Q1h7444bbs1NAx6
9LJjzz6NQAhxNoS22UEJxcmltgbZq0A1
=k3+d
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '28360ccc-55e0-491e-a382-55faf2b3dbf5',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigARAA2gXAinhTLK9qHa6h42A+v5Ut60Ht783447kdFv2x+B+s
yA6B6OvA1Ef6CwTA9Q54hWl0o2yBByirRrlt8VS2JuxCYtRCrMu/PnXIwrsNKNsr
5azZDf90Z2hTjJq2RdRk8RAfNKzTrTXbn2ATj9J6a2HMc0taZMJN4NkZ384bV41k
SFo2qHb/j//CYZ6l44I7wgsOd/IMI1OdFYJ2fA2nRNYWdZbT5kIAZpUrVhc8J0Bm
YgCLqAyFdKfnEDIDA6LAY1vxWsq26onDdngz2HukGmNWQZbz0jh6sMHn5hw8TMRQ
7YOLmhxcmxqf9Xk5QF8/t9LQkF1AVF5ra294mQsSp1b5Zz7GQBQ4QlWmApeGCCsG
zPxJ9n239g0OxEcWpz7E+mdeela/oPVzc0yMiBNX9zLFHZ2WqBllc8vnMDHz5YNY
MDSxhWUUvDkg7MkSUzh9aBC5/D8xuVdiTYRnlV6AgWA7FCdUFztpGM5CjfCOcmGB
U9mXkUBeaI6OwWcXKQEqS0ighz3rOphlxTzjnx3ceW1+I/AbehbN+gR7zpVc/Y8l
lmMUE+Yt0FpenLy/gOQn2QNHgyFZEA5K0Tn1qARv0In8g7zDAz0UwITFvogMYmnO
YjIoD19zijy026s37ROImrhwBx4wsvJ0Yr6zBAKt4Mhlt5ZfDqa6N08XqL0L3KzS
QgEy5mgSs6Dx9S1DFOoMIc1PZxtMvDuIup3csb3EcxTsgDMg9bOXsqzFYvfqQjzT
d3betf2E5A+mkR7xfxAEprpGyg==
=KFRH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2934d7bc-2a58-4be8-ac83-da5c8e3739f6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/9E6iOF2WksSi1xAlYkVfEDwKR+NpAep1Y5ZZfwua/rwqP
Oc52zbUiIIRYqCNy4+74KoOjmVicBOCZjfyUwzhWJO429jpOtBNnjzOngNXRsLzQ
P709q+4JQBaBB/WSbjuM7pVya72tRVBF7aAnFye919GPW84P6qk5iO7r8RbafXu6
QkjnYiDz8Yl2iKrEIOFMeDqRzjDh+TdSbByNBtsWCJPMwYT6/DoAaEruLcFBQ9IA
Op6ub6AgOZ02yWvMKel1ZFEkrqNrygkU2dWlhMU7K5AWUobWDEaNaxKu3BXA14Jv
0V9PTVcv/S+9CFjrlBWUqv53tbkiYzyO6d5NGcb4jmsMx7joFFpmiCOw8tJAInQ5
2yOU6QzSCA3+ZyS6e/YL21JRJAstLwVk6DXV7qGJbTAys3uFiQDynerVhNgp13nU
9JoSQfK4ma3Br6U8MO89sdUX002+n6999yzTaf/GGRy3r1vqzmxc+7a3M1RRzTYf
FnyaXNPIAkwFx6o+BnKeb7YgWsO54WhslteSrh+C/cnkwjtLq21svciTqlaj5jPI
N8IH/IMUCEy9h0DPDjl4MSOVFxFKhHxNr3QOUsAmGLabrQgKChaMOjEXJbkJ8Cbo
Bfl2333AD8IaqXBETfgB8OGY81TTYe3IhPbj1lUXUfTGq7Ed1Iz0sROG59PS6yDS
RQG1yLqJN8rsRGvXsiiOlzwkse0ASE9vsYSX6l5ZmzF7PB0pDxpySFu14xiKVjxu
VZ9O4I2f8wOP5QcwIsNzbbxOlDEOdw==
=F6xL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2db7fc80-5aab-4f7e-a45e-2f2bdd3e0573',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ/9F29/K4ActrYmRFjIbyCZINZ4sxB4rSP6MXhec5dVIHik
7WlAOk2QJ/aPAfetumAbpKIiIo/dzmn0FyghRiFsvj0KMILV7hiDlFLXu1UPX/o3
HqPbXZa8xmNPPuIcf8VGAZXwKpXsBU+FbWZkAiodRGZ/D098g7akb95/INWZuWBX
hbktCqVpoS8x22gddsCy6qCn2QW2MUv0P/m6zW9h2dw44XmF+PjmrL7pxTkV1Mqj
lEyVleN/i5u+TUKuOr+daTcLleyNunm7YRlO8LIzURZs5rvLnVIFg3O25dQyzJuC
rPCE/K77PqLUDqFRcK8HAjvYIrN4bu8x32IasvdAopE8xUdMqJFMfv5w2yRBSa7R
sgPiVu3adteQ7+IwlaaVERqdZRrCsI3aj1n4ubYE4bY0yWupj0HYNFlUKQTyhi/3
goejKCdaftUghpDDYOh1lUMOmAgFU1Gomd0ATNQyWUGxJ9NWaBFDxnijw8HISJcJ
LXgf+x2X3ImLuJJ7e9AkKhVz3z5YSJQzETrMDwYaemQkrOVz+M32smy72d4CwUya
H+pcvO2B2wmzwDfUNQapL6PrWjOvCQtnhyULu/VZVUw8scrwWyDOJ9sgrauSlr0O
lXcHbuAAwu9XmEvcWsfL3ZUAtu5y1bGeThyJyUOry9ktXkm/Y8aZN+2Om8CKOGvS
RwE5OufxlBrRS+mVC2fjvct6pQUpi5+U4tMRbz5kleeERnwKRC/GMtENHwZvn/Fv
28wI4UfZfLMZZ7a6Ucnk2PLu/Uf7yWaK
=CDw4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2dddcd1f-e0e7-4061-a502-e59e0dbb0cf9',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAw0P12ReHhxtAQ/7BbwVR1BOuX7LN9iCltGOo5eB5qusCREmveoT/SWv8sjK
1CZTKhmATj+LCSKRV5d+d9hgKC12H3L/FNaow65X4jJUsDh4GI/TIXSrtZ6nhQl/
tDU0GAQWG1gihgkng3wl115tD2h/ic8iusi2RbJB1dZyjsew2760PlVDAW/7vSQc
uMR6KQ8ZdPfdThfi/EVbXItm1FOro19i9elAvzBiH/RqKpWCNFIN5KPRQA+Tu+Sj
m/OkPkLQF4GyO597Dn4+lQT4neWz2Qm1nN1rNkadYsd4Z2F2BV1vD31pfrv4onis
2h+khxHeZ2Y4xSqlD/QrmBjTxv3u8/vIpjDt0jMvw53La9HJCJK5g7LU/CzufEFj
n5mtUh6LDkYoZ4gd8XZ6Mj6+/MS2AQQ6QET98jIYIw+fHFvUb0HaU5Hxhcpyzldv
hv3sUViQDDMMJWxb3JPFJ7UXbvIZ7ychPanbX1uexScvRAdQXiLt98q+o8yh4a3p
R6zLxlTA6kMLwJRaI3VYpOQAyYfRdG9t0VuqqlB4hi/AtICUpJykz0TKIWGcCwaE
a5M3sMwouR8245OcrWWzcBJ3V3DcXJT/tdpO5cmifVnUbC4+s7lk49NOQmZN0LIy
WeSyeIWStOs7CSCchY9JZ/9Rwuxnfd/sygvw6oykTB/3v9ECaEkHil8OIjF2WePS
RAGb9xgk638bfxbyz6UStlH0IfnqY4CMe7rASfaETj3zxIkRCSw5gixsoAcQUqUZ
qtxMnDWILMKp3IAaZCzC+NlFPRye
=kVST
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '31e76da2-f237-49e2-ae0f-ccbfb9f97731',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUARAAlg34NdZEQ98F84EwMLHfAhrMRQN40WRYYH7A6BVqJahJ
jxpJ+XdiTN1jsQd8r/BIqI4fkb/t57my631u8oFdZzuXjbe2iu89HDzmnMSb44Be
SEAho9N1fo+btg1Xi09ArvTSTY8yYIg1H7dK6q/RHPS/lUuwBah4RlGgvnKh6FIP
ayku2A/ylgRtm5JZ3l+VipIJlXDVNENCQkmKXWmJkNSFXhh94nR8nh8/AARsovxn
PZB56VLTvildYRZKO6zef9nuyCmlI31lLE3qoJ6khLI0l6rRsP2zdOPwrsPwLpaG
qd9YYc9Nkd32H2Xp7fMz8aMKmozKPuoRfNyBKV+kUpOL/wE3WD0kY0lZifib412W
u6N8oRTxVLlWDtsGyKiN39bAL14KgKQTrNuyNEjCaMGBw+qKUnJ+6hsentrqsw0S
rYWMfQLoye84dGi/FR4E7xlBfTwnGp8RreirBomuY1HQXSdpawxtcyskqNGTKdT0
qzrocNU0vJrfZyh97NoGegEHruMTz1sIWmOgdO2qeppp8yKjTbHowPMWkzqGSkO8
XlnSKE9GmQFx1yw14XsvqdQ+lKWDF9W8zZiscN/wc4yXUhBRo4zZhoRApwsYlB0i
3VlIZPLiVcTj1sjnD/wKrHIzzfP/4TaTk34y0TtJL5FbJPh5uRO/OL9/7pC99XfS
RwG288aonHq2mxGRFXc4v+kD5QfUki/E+xej3r8DE4bIq/lcMuPVFb1yVBkfNWh2
g/CGMrMOd/89PTrO9VgnBfSE1KkAMm8X
=jr/M
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '33215f8c-7934-4306-a089-60f4d9c5e884',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA9nJydJ7HCYGARAAtf/H/inqVSQWBSAgzVh6+ep9kcKj74mlbcychNKPE6Wh
C0BNi+Uomqvl37kGxCQuKj3WllS+j8eyXxo8BdRsTpsAQ2CTWg8CiHbMk233q6EW
PYL46Vpq4bUMqqqCQm1jxVVJvzwy/Lvfqesz+6atcyRuU+VXKEnahdIr1nI08ImE
VA4PoRnh3anxWAwFfEEVfS1lz1XomXTJasXAapWMK7H2hjyQZY/Ce2WS7sF6qP5k
natWGN9k+OOK/lztBVdcPGsqlQfSQcuVPH69AqusdLQ6OKLkzjWbs0oimD834kwt
oPjyHiLZDNklyxtH9uDB5yP5LRivP+ZxILp2aV6OZItG5g2oJwxpInl0xgS9+BSt
jIMNHHQaSSXSW9zlzQJg0HcdAuXPs+z0XobZQcJWWZnbmHDzvdBMF5AaOfJPDZC6
ecrerVu0wUsoNX9VfWSXk7d/bzDxbalST+IaxOUTaUGwhtqijF86amtFiy9J5C4A
CzzU/YfYtCOVs+NSPxTdRN83gUH9r263yDkz6yZMXYSRIazTvPFGYCoVHNr4pCHW
yS3SRd8KvP3gL0CNqwwKvvoEquj7Us0T5tgj2udU0HfCr3ZbmECT9oVg3p8W6eQB
ynk+/rE3s1nfy5jr/uWLthv3QVkRbFXaqqZ2MQQf3W5kyl4ZAK26GlVjiMzGnDfS
RQGyHVmC9xHYDTyioKzaKnmmYj7CzMDoOdn+1e4dV7GjBOxbj+2PGAD0BfNB7SY6
a/li/PY9NVxBGzXetKG7CA8hqKAoAw==
=IkSi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '344bb079-0cf0-43c5-a9f8-9958177df68c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//e8xf524WRIXogC7O6HeGrSC1MQfEI0XsaV215SvmcNaV
ESdV40GQv7YKYm8waUP+UqXR0K+aPiMhCFEfrb22iM64jnhLzoeSVpKDEB1ono9I
ncJXi0N7NswXZgiSSnBgb0hmFtHdsy7FEcFw0NAoedbGwphbEMnIhFyl0s99f66T
fRJZYlnY831xr6aqmphN/MWxqAKh0fpfH4oGMhmnnoMb9+rQZx3c/Ho3nZ+S+N5Z
PqxesKJthTx/Hiav1mm3zdOuO00Gdg8FHI47wABpKEnsa5ThEUrullAdHKeCkP21
c5bh9BTsnLRjNgu3yNsfcXhYUWB+HePDY5JeqgEi6+A+EPe3Bcdb9zCI4NYPqYbA
wOGNPabk86x4x+PrOiNqO/SXydXYnF7TBlTYofDl+pPHggKi7qawS2KR11G+1PMc
ytXs6UGIWPcNZlo6O0ti9o4QKeO1RzilZlOUaDwlMxcUpDSLOioIqb3e3VobsvmO
mejvEqYoUkUz7yX7ixDIVpp4as+VJ7kntNiubcFKi+cfwZ9Cem22U48TxRWwGx2+
Er4HezwZIw1vj/7IyztCa3wm4BPA+jO3oag0YTF2tizFp6O2SwbXv4jbvWDWp9xA
YXJOQbiqESeZSn+VyfFq5exw7LKodwk5GXoonWCrVUOWwaQnjkVFnCrD6qoh7avS
RQEjqQRl8+aCxkOWymvAqu0gGfeAXV/oy6YFi1Th50Q+vJvhCONMAnitorM+LeqW
3M/mXVqJ457J2P5ccbG/iRe0Da6JOg==
=2gYA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '375a49f3-24d7-4ce8-ac2f-9e9b97e4f53e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/9ETXpcEt2oGpsmQupCd+hpKM3sDT4peSf3he1JM6g6XII
67zhrEFBedzJCX13jJ7ljwZ0B9vnxrUsz9Xcz0IyUn753UrTsLv5O0/fenZas1Iw
dWMtFosAnPlCPRczD+6TD2lDvYUKFatmUmAwr2temwKsknzfK+tqJ7tNWtcwd7Cf
SH120cNk83HaLnuVfU5hc7LuugG8muJpNdaPrs8fEyCr1qVQ0wq60oX1YyhEwtUe
U6yoffCS/Dwgxn50cYRnbacJHJOEmQN7SUbtwgfG/lS9NjiVo4mukec2C9X6yP91
F8nL9aHV7Hcu7LVacvfxeubZ6ac0Qk37olMbfqS/lVjVv5MiKse0H+gB/FUmwPjz
pTDw4UiWMnNY3/7NNnwKkKi1xa4eDq0RQZkXQWANgJNn5M/8GAEfMJt+Cn2ECCAt
FFDc6+DNsa2zUeNjTJ+5Y+BNxTRqCLJvOSBjm779Bkvrbh13wi0jOO30nGL9+zqQ
TDrxrlY22Tg+g0AiyBDgZto2TDIayC98A+KcS+vn2P1Xqnd4URCY7QFV1MIuM1mH
LdPyy98d1HOaW0gkPMxRJT4A13K62rCYtO0Wjoy333N6HDrFUnBWcB2ap/zdYyOw
3OD0H0H1fMFwBDqbYQiN+3cMk3HTcWCb8KC2yEXW+6RqtnbXr7nHUkAeGaytyI7S
RwHss/FmzF/ON+RIxYKlll3UCRVMLrTYt0DEhS38/mTDrrgnCTHkrPuwVyBRVOHp
PIu5jptpffsKGPL6ROtqujYpqk8cd72a
=liZL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '38b337bb-54dc-4856-ae52-5ab82b64226b',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/W6vmbGsj3+KnJBSBaniFzgxXZ5b4yj+8KF8+Jk3FV9Rq
25zjpBvj+/Mky6WqGGpuvuuOHuvKVBnKSxKyGY26kCvsRlqmP3n1xu22gJyOBMlT
6x11qnSJyZ7DG3moEomVRjbZ4sujz1bGvqbg7tLtTKpTkYB4ltqQE05uUagQsa3J
hI9SN908pDNQgMPMMb9eA5HqDPFRjMrLWmMq3LXHOe5nzAD9RsvrgfK36rXXLPYk
pn7sddgM2Tyf5hZIzZMbsslky5Au/n+JvlxPc8iviQTa9EKKd3BC+qeRhWAt/fhy
Fj7bQ21JnkEPF1A+trMWRkYp34vE2vyKpDtviSCuQ9JFAaNRQBAgJg3Un3CzON7r
bNFT7l5FLZgATt/oQzRW85kYcRY7ta/gtHQ8Y/Y25mnCf9B1TPRXynFG4ef5J9RU
7VaO5gNz
=Sf5q
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '45c0794f-2a4d-4452-a4c6-937e6c9d56f1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ//W7+gyMatfSfz+DwazpL5gchDIaoBjX2fE3AdrVRdtKbZ
CxcJzYaqToVTL+sp3ZEj2Py/3hE6MVW5+y6r1CzPO3zEVUr8rz3hmmGVxo5/QaBc
Hmck3aMBLQV7tvfTNQ/kuL+do6srlBCB1nEzwdHp81IkYLbCqB5lrj1jfOyKkHwj
rLCoR9NXyZkURAJWW5dhurjnY3qgvqBQaQhPK5inARWhULPhuPtWKmb+rmEURocv
YOAY7x/tRe8KxDMEX+834+QbVdBJW+B/s514sMmwwcPND/sLJK+mRarfXS+385Iq
3VK71UpiqndYWUSl5oMIiw1yu0pE5Juguu0HqKV0Q+cKbuUGValYdeUZOtTUxgIN
WQMnlGnAnu3Cnf/oVwxjmkPUUUDVmHP+JjOX5eysCyiWKdr5eyFQ4ziZeooDxfrQ
geogG//u5Sr/0J9g7K16iZSoSgXcYukeKs4wmy840pA1V/qpC5u+ZlDSQI37HTji
RzV4Uv5YyJsRSo8ZvpaIznDlGvgTjeQzHLY23DPB9Psx5+bigzV9iQLwyVwtIX9V
puSIZinswGA2pljS4JtegXHrzBZJ57Vwt1RMmuksE/1SUM1FDBCELT6TjCTh8PC7
yMTdKsst8dbSuJWrr2dMetQYMhHDQQAMoYwg/EtOf58pqPS6mjOm+DKesr6sfnbS
RQGEWu90YCyFV6/MeO8Uo/BnHGPzcDxFIZhpTc1ikFs5LPbGTZDqGFUM/2JCTL9c
7Ijit8N7vLfQCyH2+sRizWNvKIqb9g==
=wRaH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '468b59b2-6d37-451f-a271-1bc88faa441e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA+p38wQEIh7oARAAoAPkyb0NFdlwmEiE8xIbc97qqnfEm+BKBEc2IeqHHRN5
5jNzyVgzFjC8wiu4DrGcGKREZdLtw6evO5Od2IiK5g4ypXnsS6yCBZsMMBJH1Cc5
HCckHYVyIdHx9fvOANOy9Atu9vQt7bz+xzBiQpIR3ZibQ8MP91jrr8PX6Zaz9EHF
RELg6edxyITMxOHICSGM0aAsVFr33O1OF1if+MNgywJ+WBY/tz8HtS59EBzzgBWJ
vj+5udil9x6PVR6LEh596fhl1o/NmzTdoaTU03nw8nyp8jK84k5mpgA+Y1i87UVT
+8qrpfUlIhcXuaoLnhxOSQaFMXkENVsgxtER5s0UxMvmVhtLouAdchvU5fXOEyA9
FahY0PVz15iXNboRYyxiSklDnqBcXKfr5HXUFJAAJr/gb5b9Tz4GtgAN/rCcKXvT
1Ybu63UKsuPSB4NAOB1k66m8zOFqZXo8U8Q7trM2UA4J1Bxl6z8wztr9CschWdjY
KwBjUnolQn6zoOvvF7OXX8bGXvpfKNBmreG4Dna5AvjuJ6+VGLnBZVy22HAJTwiM
fUwnO4y4gshnLRh5InSlcKQbkWRrxYD729JXTBtIdhkcdHqaKcHKdmeym5CNroJS
mn0SAbKyYX5tmRDyb5zsCYu6HdQKHj1QCtEyUAkVkokzcSTad+vSNIOXS0e7Y6DS
RQGVOVqSSVNQ6xKX9xFHnHThe5s1bgErsc4cfM/xJPSdhUucE4XFjWh5EGnWs4WQ
cKhgaGJoeD9Ex4w5oG3buSnWr5RbRw==
=uaCh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '483cda9f-1bb7-48f2-ab74-971839ebaae9',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA+p38wQEIh7oAQ//fRbJAwbd3KI0Upw/3GvJ6lZRTXsXNWgDBi5TbhNvZ1QE
5+llGEGEfagdZOKPkH1ivKgdeG21YOTkywONOiET43JbETvdyP18q8bUKLX2CtLG
tXXB4ClDQgcRVeTEpHBjfg0vSz/8GvKzNsyYxx2sGEbQkkoAkWM1wHVLYL3l7EwL
LWWC21Q6gIK4ziX6scwDw0XBcDDQ8Y0U7Fiq+CRLHLFAaC3h5XhqTtwYuSnzpvg2
7SA6npkzSTiSKRPmdpYF0v8u9ZACAn5vYN8X9AhzOK9zXy5ZeZk0vW6sCz8UqCtU
wA1dot18e1X/9qKj7ngCX3yWqxsJk3x/sa/xz/taFZMJbkgHWdA8Ig9lUdQV+MSW
Q7cuOvxL0CqchcYPFSc0JPpyEOSoALeOWE4YUgKEEOMeO4NYvtOVXscd29sLriWC
GYGwD7MoTOseJr9Wm7WNisRTXSXijwprdC7mQJmuJfHEIVvAFDCfhbjajEQbEsz+
h2cVDvS+MwIC7IaeQsGTB5vE5D45VdqZ3KND391xLsG37BRfxU4aZ3+3lz6m1vh3
t1EQOY0fV+FqoPZCRyuYcUmuxNc1QyheCDA/zE+lc3wdaETb4cn18X5bBWbe6ekM
xKWbEsiTXjA3NbvXiMWqNKU5B6Ap6rXkVsL7vH2LWYWDuidMjQ68MZbRLsO2+6PS
QgF5A9G1i5jK7VAcl982E/GQ8v64xr3bdFbcA46vzS+vX7N3GSeARUkXqToEBgBr
zwZvxaYZ6+wPcetOABlhB9OxbQ==
=mTOY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4b026e86-4d1a-42f1-a2df-570929f85614',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9GMsk/tcRcOVjmURcbk1l6wlynA9VuHemiC4fIf9b5aRD
8pAvJFM9gDnOkNWAroz2LHdGW5XWpBw1Fi6D8tXPeB5NRuc8VdJNrqUKFSKwxXq6
s9vzYOZ7aj9gQgD5ar7GSKLIpSZJppoUuXwRDn6RL623jpn3nCIFjAXrt9GeUSJU
E7/BZyoBUR4Va9POyLkyD5c6nGASImgujmUP/RZR7+oE9kapfb+pVJkudX9evYwh
agFFUaCzCJiK/fJV8Dgz4Hi8+FBaz+u+LVZRU4re14PfOuFmdpL87aLoedkhf5cJ
oeIxufhm9CUEmhlLBlulovPIsyLQ0T8NKgFCK2QTKtJGAfDnDSYWircKNDsqlkV+
AnQVwf/LVK7XWxV530EOXnS4/qp5Yho3mkVw4u/ovUB6KzgxOrNMldSDBg4eevYR
m+3+ZdcMpQ==
=DMGL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4bd99e70-932c-42d5-a127-02e5b0d95411',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/aZBPzWrI802vVbaa6DAfmyK8no+1tJ/W8YFY3bGAStYz
tauzdU0PX6B3YQraK+vwCc/ymxlxa5Fe9mgxN1F8BNR9FKJ1Zd1wkaf14E/9DKxh
/1x6WVk/pLcqEEpk/zWf9Qq4H+k4lGzRrOhUwUjWjRKvzo+Od1w5r5QnLNFnit1j
7GtqBrZgQBYxK1FCh2EqfczGu6oWMpOgxuYgOfb6pz/Nev3YJOT1xCwoqI16kVY8
NVa/PRjlvAc3LguaifP1FzWNDj2UJMyTkU1VNWsxrq8i0w2zvmuSwB6dzEhlU3Dz
yhtvWKC4XGWdt67tlPRA37j02fTBGPT7KWKagjs2S9JFAU4VfPchNPQklunIRzT+
JkEY4aoJfA5pRMqHtYI68LcRN9qaA4KznqkZMhqK5Ph+HqBQpMQTwOUnEdP/gXyI
eGl0SM4K
=tMgl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4cc57c0d-23d2-4f27-ac8e-e6851ad1090d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgARAAlCZ7/igPQtOwY6d55q1O4lEqBuKComkUtTDYhlxvFERh
+dV0I1CbGCtCtdkYusAfP9wBmdUWWk7RwNgRUgMEJHexx6iBJ0xYID9qNN6SZP1O
NxBq1YYkCjhDnokeaRqMcpb+8rzZd5j1w0FP0vw4tOQBXCo5Og3VjHuTN16SzN71
ajmJnwCNR/5ZNy+TQ1fVj5Rq46WDPDTZqMLnBd9F3TX0m+96Zwo/o2WsotpVhVF/
CBhaxOGzxQjlOrn5+4Rvr8Zba02h6Bljq8OVMlc0rWb4t2ottMVbOdYsFpZTVeis
G6t3WXb8peonk8vuCqLiFIci948fbtJo436gVkM8z4gaXezD+Z894aARB6eN6Tt7
6tOsvf3nIP0/gT9Um8odzKBIq7Pv/CbY+JIYQS72AuFXiSVwq64Aj/ijmAxTbhvz
KmB5qbUYVEm0nd8rN545HXhQ6rOjebLYSVCoeWSY3SlzmNd/47a8eQs3w645fypM
fIiHuQKhRyBqctOpoiSJkYoZb7XBy7QFubuJmQErEl145UwP3TPg89l/CRbmIRKt
vJtWkf6Uj70/Tot07bFq+3mFrz7/5jJciPdzVqWZ7PAz7Flj/D0/kO3eXbpBmUBP
vBfOGED/1hu3VSRJ/7pXQdoNcEJgbK3JlqoqsWajc5lPB1Y3YE8J41uOKfCWkOHS
QgHA4kjYl2sHtEcmNHkegbUTU6Ja9Rx9yFE/uxYa++5lwnbLBtg1do+6uk8L/OvP
c1YCLOwVwPLdQWQ90X+COL8E4A==
=B0h8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4efb484c-4024-4fbb-ab3e-cf6c1fb4b034',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//bd0ijBZcoybIepLmy90A5OtU4d2h8X/EB4pRq2vqTSf4
ecJKU3wrC4dHsQZsIgRJMUIrPynkfUk15gMCFltHzvtYcJQDR2owHDmjpWLslhvU
2RoIEj4OK6waXnu9AixD8HRvavFNJQRbLLNCGoP0CQmpk+7ilYZNyAjG3ojWlv9v
U6hwTyyt9RgmVpy2umgDl/rll5gisQNf6YzVSoGqOJxroGYUpySAhhe9gdno00S7
hfL9OoO04545cu0SucCxMzYU529GjVwRkxyb0O6W9JmN9cSjkfO4NIN+mRXaENaf
eWM7eY6GeQGlBzmeo8Ia8v35STuwNC0KY8iLIHZfAesjkIZ6fCliNHZBXxIEaKkM
+npIjzYcqTamBnpvbFOV3UIplgTLN7XhfE2a3eju0kyOPtZ1JbbGc1+jbGen9IWg
XmFXXtH15FU8f4aXtbgaUBTrJDb24FARol9waR75DcqhS6pXGvoVgcqSg2TLOam3
Z9tkSsLK6AcG0cQAmGaeO5HazGyi3BeHHvxCb2ptCidt19JKr+aRcJp6QOVGn2W7
0zlIJZnj/gDeRGlRf6DcRhG5XYpOVPhWQKdytGJLQQ1JoTzBos1lZC/NluIs01D+
P9u94NHyZqy35P0wphV1TuR2RF8t0mZafn+JHhRPazUhmJW6WH7o1oy0fR9MkYjS
RAHhgfBH+rYX2RQ38UKOVZHfeHLKBZKPQSa91rEjlx39jUDWDS2G5TVjkoji66NW
32dY2HnyifPVwZbaT4GCZCuLxXA5
=xTb+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5391b411-34a7-4924-ad4e-2f4a760fad3a',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4e/DeCIHsAzARAAoQPu8LNngwBOstuh0KKFaoFuB8L3T7TYDoHsKMpwegBR
wFftx4uxIfuf6bf6s5hY152wuwYLtRfq2MBCBlrCNPezNw7+2sO+BA+638XJsDBj
yZmq9vQ33CWgOAM/W6XkmbykVkCxdpHuOZlz5z5DQZ2rqCVN1rCyT2hNjB8TdJ8Y
4jE7vkCKsnYhRcPQtS+m1CqfruczR1vTiVOQkXlLnUqsFjrBVywTxRZMiqPgd15t
9ZK2aOpJbZxDIMguAe2BMBEIi8BwYXkytWsAKHMJWjGR9F/PpWuEXLaB2vysGRBs
ZfzBtwVU48jEV3O/U6jW1QN0V49Y1H7UNyualEaMQILzP/OmAHmzfPz/J7px7mFU
rJ0vD46gHVBaZvbAaE36LXjoBDyEPMeqd3VuYWFi62LGkQB51cEaafua3mSW5ic5
YKbEC4qc4JcidiVZPCnUpx/4sURlOpzOX8kvkksnuGX7mBsKLwcUoevKfTssE5ko
56erUJ230qqfbC5kcJIvYpVw81i6++rAw5v1UI3ZmPx+j+i+3Gs7JmZK6FVRRrt4
KjUA7yvVb5+IpgKHeQH56scQetEtWrAbtCvQVyD2juRCDqvVAPuPCDFnfeVdQ8Sv
AljktDhTvC8bd2VlINK5FobLFL9P5rS6IzmVuz/T/CUvD+zDhZH0a8VZjJPEMTzS
RQHwXKBkQZMcKKTqvzZfj0TBNIWGf/asSFRfYgJEHlyR582vpQxj1gUKyPRzyvjx
x7iGBfLTtpqev9ejZiwrjlnSLmNsHg==
=thVS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '56da6cf5-73cd-4986-a35c-847f88e9a6ab',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAsrSvSE0aTTeUWJJnL2F5VWi3noGXH+gznrnKnHpqLxwo
SQlLYLOBsJYOP0fveRsKFq6JbfkQEjDUYL21FOGKwo22dJyreDK6/Wbb3TfL49Un
pMvudvwQacjwZHbp993oRclI5wbjD8Yw91RIhraGGw47iAxCdAy9bp1Kr+WhJVhw
l4rK43YdShKH2e5NRUxm8Lw4/rHJr7vd7lbVG2UQAZSPdoMhJzVYwoqUQ3nqaRZx
zmeQgHyV2TDimG1A3GqcwSQvQLHiJ7btJofCCcmcVlcOdUmOYfAlC6C8WIvRxsaQ
2zb1o9O7VGVKTsHTcKDSvQu3ykoA3kkT97BgeWsMGtJEAUS4HNkJ9K6kKp3XZMev
VohYzGyWyn2bbJGV/omzY3Ha88kUpRk2PC9DVvcEpUAP4BgmUOxI5u5VNvfFL9jR
JBNM8qc=
=ZOC3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5776c30c-41df-4947-a0f0-240daee20217',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAxkA6B9Z4y2kAQ//V+UMBBZIeh1pxgUGwlEHE1DMItEBgG/9YWWD69LBvKrw
l/b84yo4432HxFY+p1ahs+xVq25yNIH8PVW1T24Uvqey8jviLlbv+3EFUgvqwgzn
nduuUjnGrXk2C3FjukhAcrs3SS3tJkS2pPWN5pESgOh18nxX/OQSg+F/UtX4Njnr
vKXIN8uvk/L8Av6gpF2e8FtpGccpeA5hlDmu70pwT+jTUt12cOWZ9jhkgL00i9kx
XlyPyfQ2F5g8mLpu3Lk46mtcT3elsx7iP2U35XRWmtDxLGNpY5kHaeFhqM9G0JBy
6fSDMSYElhIBOxkFuma6OTR2Ay6xNqeWAfds27OThc9AAfT8X/EOE2/myxzoM77g
9HByaTMbJwTo6IfsQUmvL1k8B3UjLWfv/c9HkgRCoY/plTzujfosuCeGdgnoiCrr
uX/U2/8K8aTtEFDeg9s0zla2wHKkbjEPGcsW6EM1uWG5SL62aW8IGREYRZsJ3L96
soV+sb6MXeq4yfbEN7UpIR3F9RBK5FVlI8KQti/1fDtbLLRlkvFTIe9iTtihsBwm
gaUC5mfNeylmQ5bEcjrzU0X7jMLwFp313PDpC4Pv348MqhnvZAaWONFWYSE/uAdP
sIyQe5+Q3ckEOqwwhJfkrozdj6ZxYvhapqcFg3JZzqVLps316HHfIkacz1jzEVPS
RAEfdU15h40CFQlsC2LTP56l+++iPhKZ/I/e62RCFctEDp77kkc5uLN85UKh9ERd
OqwutLFR9+kG6BcvcgL64f6bWvab
=8m/R
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '59fa1dac-d17b-4326-ae0d-183ef3b0ff6a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/8C11IC1YHLiRr623HDM2dxGI9mYo1PGslKUXtKmnVPhDR
vTDW8CjO8HDVKqzwHq2LAst05a9MX8AJH/e9uMmpipcZcr5FgjGGmAfc3KLTTHjL
igPqfQGc4JlINpCq57caztM7mqN9eBjeHEPyaPWnTAhFwrv+vFE6brW3AmSKMA7f
U/EF2Lqiwio3d8jZj1aTnkobPyqnFbWRqJMq3W571D7W4jg97DstOnnXlnX9/6s4
hpycV7Y88IN8VQrPQrJrDtP546s7o2oKBB6I8mM49m3HalAOqbj+5EdFE+kBv5a0
Yu+ex+zc4e9XTV/PXrTvhofwYS8X0t+AYgQW5M8pGkqUE6AJm0WJpluahA1bxPlt
wnSLmqigP+/q1qIXGjE7DF7Nah1mPrkj4eG9bYgde9q0OgVkqeURIbToDOrkcgxl
34W50ZaoELRFY+UOc8EkO+QV+nFpNQ2q5IIM9GHrRYSby3tgjnm7gZEGIDuY9uhC
dAiQ+oz0hKh00DMphUvUT96GvKB4as+LoAqO5KXIi0VW2UYj+T+cwL0mMTJIR4x2
K541jd50jL8QctqWAnC7m+/jSk6EoLUaakwF9eUUrbdaVvK9N5DoolQHJWAiZjzX
KGOHw+hTIqfJ8s2IxolgmD7AFYCQoXop+QBOINEtJELDJOQWeFMH+diD/Vr5ndnS
RQHEe87IMymDMIplQ1vJBMHKBH2zp0J+7TAzVOh2Iwm/JP2XJI2iqYAmWkpLqRYL
pywWHWEKyVHJIrrjDKwBongt57Hrbw==
=OnC7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5c0ae73c-079b-498d-adc4-ab18acdb39f4',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigARAAjf48fBldRx3z5InVqQc2HjL5QgByETaAzDOIjbmc6W0N
BNkZGJP8TV4xFfvjwNmZAE5lFWJXR+KpzS87FRH+BD4YZmz9Vylrg/DBkdjSHESW
t4Hui+5pNPOsdYpj4uYRKHvHDrXlAKpgauCzmGEoOI8iYX+M6Q/gSiauZVWZ/axB
LmtCoLj8dw52KmMLRX/o+Ck/9LVGemcJoj3hby3uDdtEOnsqxcw3ZkwiKa/uh/G/
mfKQeGuvgmf5ZP8DIc6G94sdNHwkG/kesIYx50GhIFexqzkSCjCVjp0/yGwJYM/z
gpjcb5lxwcqVNpBeECul2WhGgRU8/CE8L7mSaw3NFguZNMvCR8WcODr16yxnHs1C
tzSUua5SRLNKdNRyA0zIPwoBBdqI8/sb1au1sYTFqGLxBnTB1PhvEo9NETXOQ9o8
U3EUp9Z4kT3ZxgAGO0FsjdPhH0UT16HoKZRED38oRQwpBnkmyysL5aRS3UBU9nLV
Uwectsg0GM1m3jPDWrD5NIIm1a40GPFOzSIOcDFrtoplNWJTHxMLz9QLakl6Oj/c
IsKZFwe+1K555BrulYGX+6wRO0IHUh2NBW/pC3/iwjZxJPSwhzwEDS/nnaajLP9w
c5NZNvb0bh/mIc0sKLNqCQaYhtXKLnSYyH9oWk9oahTi+kyxYAtnn0RqfxHrIsvS
RAHnsZmiMBItzghJAXx3hGd2PudjYXeYLCbfAuQqJfDn0e2BTSck4RgWbIQMH2P6
xvSpDRulYIrwtFzwshAUXM8WoX8n
=9Ajr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6a7c7313-2915-4113-a986-8569d8787180',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ/+Nxs6oPBY7tBzdq46BTA1jyvWbj3qYN9kemg3EovVBrNW
/EhJgdl+OroNmlYnqJ/T9Mplo1Cl+x4tfKhWvTZhOGXFTZJUxnJzRx3OAkQI93RZ
vdWRVg5gL4KkCiY1X3KRsQSV4J1uq4UrY3ClzeiD7vdx3cQZbmZ2zKrpbJnzXmjX
VERfzOR2ZwcnK+3QsrkQfWKSMlj/7szmaqPOy8CbkY794Fjy2ZH7BgR2OEUpvyOE
7/NGdJykMazqdj/kdY6asVk3UxbG2HbgEI9o6hQiU70twnE6W5y8doHw+TZVsIVW
S9LsMFtlqOS9ckQaiC62lwl87+jKAoNH4GpKBwgJ5k8BAJcEMSnyhcEawuqZid61
RTjBIUJyA/mHoq3tzAIRWyKcp4lu05a5UXyteIlBWBAVXT7BIy05lc2w0JYLhDp4
JFTurRiof99IFdg3MoU5W9eINQmibPXLF2yQDBktsNi9hLwYRWWB9Oyjc7ANjPJ/
f2pZ0mVyF/GXREHr/+V+f8CVbU4dIa+r1o2mmCTy7SZQBzaaCEuKT5+WQstqtx56
etZ88N/plJaqpVNcB6NbzOHuvFfGPni0X7173Ww/tbtlJv7TEKH0ZK1iqERFJWYX
ogNRWwvQNvu4trIibJA4Tj9LoVJeeyjNCZCL1rAP2Rq52tyhqoIR3MJ3fHK7mULS
RwEhG7pSWLOUlBGD28Swh3TfEeDOuIKQHbJAZLwiRB5Dwa5Ycz4A2snElrp8FPQT
h93xAEKB0TQVuRNA7TcnaHMZzf3ilRRo
=RAC9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '705cca30-d908-4dff-a03f-106f9e2e5643',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ//VGXXshmZGjgtwe+enNewnjZTcdwtwKREzu12Z93QcDtL
zaAf/XfjSwbZT5lBOD/2aZff1HKiJ46h8/3yku2JtdeSZEjpVrf94NCzjcUAYShq
/7+7YtNk/ii8yQ5Rp8ytx+uTvQqHKfGxbJuBoOrFsDTtp2j9j7KGGk5JQvHLSf1o
Pd5ucj9LpNu06D1pX3TtsAq/4eGBNjWCok3OkNrHwiLKndtKZMgSsUtwfeQvaOcP
j5/OhinJw0/lbs4SEhCGfq1io9HnA+YgeNa56D8O7ncCV67BbBEqd64txLFyxc6E
+k8vqUQevbyiR8q2Iw3QO0bFk7uduH52vH90VgrSMeAOolVWAeETuFhETxByAQFY
/h6IPcpPxx5lCcOxg/hzJHSvHspb+F+YYqzsMMyV65tZ8SNYFvxKSHcuqmMKxlWY
EAoBpYrdNx0IKX4YfTmyIhTg0/waP4GotRHg9hxOwavt0zVoXfgpGCD42OovqBBk
TpTIN9zH+kQayTBMcYo2slrqJPWv8myjmYHcP7qDUTRBdj6PgFnL5S8ArtaH6Iv7
bjL/WjI88uI/5AuafO47tXBX+nW0NhX3JdaihQjOauDX2ue3Cromy48XB6FLLA7w
igh00JgzFchwitUknWjMYSkXYv1+wA5plPjl2fRgH4FuQeMlUtanbYZm6JeEwgDS
RQGcxLFoaJe9eKsfy0FJh+mc/hVa+LoYHhGot94gQXF8MAasmUYCvIq2de3INUOz
nrECR7G6FFjxwhSlXI7lsbbBRjxsPA==
=z+Rr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '741847f0-e05a-4a5d-a3ae-3514445eeb21',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ/+IZu4IZyaxytT7d0Z2AN5vcyVERT/27Vl8X/Pw/FUqomS
m+xr/cBDmqmLVdVK/HVCEHGgXpK4ipJwZugnR9UX4N6STko67TQ+Z8M+UhVwdqQL
BnlG96veiS3WsQJ+c5uucg8S1nYtS86dEXLxH+9yqp45WDIoSEwLJ0vER55vS5rh
izX+rA5pAkqnsTwXE/oSt0LSYz8ch1OxQrkNHTpgms/bjnX4yYzmnjvreOqyKAEN
vI8XwzPgoCDXZEEA5V5uK6H6BS2uyFI/1j2f8pppfieaeC+wsIQwgM061VW/T+E+
KOoF9C5nHGnff5OVRjcu3CSg74nGIRTjVtHN5S8EFIZUpARrBL7cIEkZM5wmrTpK
QBcI/AXgdCgKLeWacdYQGPMNGsg86N0cBAjKIEscGmAlX3e3pqcxr3zkMGH0LO2+
GTJTzimjisY9vlGGyyAaEUaYWhV7TIK8IvHZ9969JAZSYLNFbJjR/PjTNETCLkj/
2oLdfjgNA484ncaUaEh+/UnVmp1tMiQki9KxL3t3sUhExVIImkCuEPZm11IlRChR
ysDQ3ZULUUDapYPglU5KmYCnpMt/BfXqPBe4pnWjbRkR+y6RqNelsSYXkX4PSHDQ
8WjR9oNWaTfnFclg/bdxLAG2mMMu6zDuYfPFGVtG9PYLDeq1WgCioqKdL3fXh5zS
RQH+xhPO5zBgtjoiGwDFHIOHktRBWxFIMXxUYoKVrt7l1NHcGWUgvCAwhlkd4ys+
xBJ1b7qub1o4LUPe/jd0iOOlinQcag==
=5yVb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '78876c5d-cd44-4613-a715-59b9d110965a',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ//WabCpfZqHoW52DvAhmYBwimTqHotDXTXInrLFTxbCMaW
mGl4jc7q7K+OAV9nLCFC7M84C+nlVMjU53zxVfzOx0CH98D1qjvrnhyZKUqXz7BE
dLS5svfmkmtWFQ92aR9yCUvba/oZEhSPzoxDx7A/+mhOwxYi/YJ856T2H4QYDaBm
+ZP6OxZd4zRZLquTIlP8YB54CU4yQL6UtetlJe+z3Cje1HfO7F1AefE7aRnSChSe
PUqKNZeapl6YVkqlEL3YSrT6WaS238e1jCCbJkzR6jlbLcJI+PIbjYvD4aI9aFut
gyY2lRs9ICH8yBJ/BgApLD9/vtfY9ya1znVqMhDGZ4/nLC3LHNJftKlFU2jUEm+p
ECceVzlXCTpipDRjuHqZRWbp+B2Pa1Bj1LiPvSPp5hDXRK+5mde3NlyMvgy83qia
OiLck5oCeUTstecJ05plgGL/o6Zmo0AIQ5XyqLtTr2K0IPcJO3H7plVR/7jI51Uj
Rxt8oAdDIaLczYIpxBcAh3GJ6ugIBShw1iiGye3cc1a8JIWV/faIxEZdjuGPoOxk
woO/wxnA2DpVvp8t9wJ30+JG8bAvY6nmESCyxgWUo8zXWB6pJKWAgYFgNkVh4nd3
WMBgG+PnE7zqE3j+FiAmf3FmJOX7E3GLTCn6o5j+eeJdroVkjN8YKeDH13MISxrS
RQFeppty4JwYY9hbzVaZXswSOpLDM//mTajgHlr9kDK3Q72K0Y9ZR5MUZrws2CTe
h2MJOv9eSudA5ZkR+5En1mtH+xJ46w==
=3sd7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7b8ca3a5-821b-4cdb-add2-874bed505efe',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4e/DeCIHsAzAQ//XO0u/Q9DCjII1jbSTTdp1FZjaYvPf2hAjz/SwV0PtNSQ
87CKOgM2KL0zlaXOMKgCSRlOEUf9AuX4MlnITlOvG6HNNA8WYqhY5kAeRRROEmmz
oji6d7JcIrzDNoudsbiNDj9/ZJEid0RxAbBPmY7kabx4jwvhEpdx4lpe4rEpmyOl
X5wqK+ZxZTuntcRz99k7EjKzldouYOJVCBmUkEvo5wypxaTPSeXJ4nHxbqbrrobI
uTCiQ/26ZsniAUvkfvg0NT/Tc239o3T4rVkc2LfvAG216cfZ26EfvDpviRFJRSOD
Mtfr/mWIsXvlGIDmUR8y8kEgKu81NJb/+QtzwTTWrFc3BlBv65BHcWlmFSNIFP5l
3KZIKow4XbWe9YWQNkpbHfQhJ5BIcBDoBPmQvN8ZQ48dfadhfEbeHEZpbKVBSxj1
9qvcDuhjqOHIpFqN2+6c9ecuw16VJLijqTNyqgrONKJYyY+t/fOrUGGQRTd4GYAO
Nm+B0ZXOLylp5rZU9jdpIPvzFgTe/LDUNUmRCy6dz/eiJOi/QoQn6mwvS6APj2ls
iVBB22nZ1dDOziAMQgWhV58UeqZvjZhlHFCwNdUW/40m8PPlP3PeNJVCL5kcSOr0
FuhWmkeWiWrYaXC6rTc+DANpxmbMu8JqMSG4qNxxaoM/dasKVUGO0NGjYMDEpU3S
RgHknGly0CRza/fus0k6jqrmkWvjblNLObOKopVqryyb6HT854BFBYGK36O2UQ+D
07sv2WvY33yhlBtHkk8Z/AM0ZfOT6cA=
=Vb5F
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7e02469a-265c-4087-a37c-ee84e536696b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/7B0UznjLDdaROMIK4r3itPetPouxWl9gsuGPuM8vMBgKU
SoB6M/Y3colVdGr9DWVS2nxNzqDRi5qp6N5xAtOzDERXsfZEgGUHqCeHiH5j8aDc
OSif9JPy9rxzq9WKL+ONqxOasp1hUpDW/M/03zywBcDkDCgcqAS1xw9J6faftCmK
3/SgmtJElVBQ9NxAWiIzR2wEQDXdp7Rp2K2hGtOwFqNIAhQCGmdg3xfBy3Bw0AFN
kpWBs6LpSLXqffcF8c/qwxlJcELuJy3o4o7Nnb2LtEcgI7txwfuQVBCtXKol/GMc
6OWvz35iqPY0Pum1l7RQLwiwuk3b4CagBPlQ9HLBYhEtcDlQQ1X6CvFlPX7siLcS
POQzaPkDsrp18QQUrNr2WKcUvzwRrGfhvqsfYhNeFnsMAxhmGePDVV/L+wlphKfe
6QE3S6m7AQTuic8BhWDlLPYm9gGOyrYnCy4gsWVLwj1fHMSLbEce4jDGmtVdteZw
1srv9A3x+cHNHeRpPj7BIWlPBgQrsh3Ochu4W7QmjLZn5CP5Zou/r1u6ePTzXmuF
hLOTIGu+h4U3ty8iawO4P6+k87tzqJQtkyO/E4xdIiDTvxX9gLciuQE7LdY61Hlg
LAtq0pwTfKwICvUom39JzcG76yi5DWSYEH0Mi0lfVaAQ3Carf10cWUgtjA/ugTrS
RQFNh8wiTyvGsiCV9RENibJdzWMlpf58Sci0FqVTtzbVnQcA7gxMs/pqISs8iAgx
r+mvlz4PHbLVixlfVmg9oFBLgzTqMg==
=sxap
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '80b9e3cd-b157-4a4b-acbb-57336c76dfe5',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigARAA0R+j18MG4ja2J2w9AVv3u8mJwVoFnREEvtegp65QtYsI
hdRIfFitylRQEJnRJjPm/Mhg+Ho2r+hrsO4i5RqrwVdMI5CLfEN7qDwuJWWXzrju
RU4G35iO3qRBwi1Qs3riUxDmaI3kNQWHmmA2YUnZcmjsrUwjq4itm5axgH/KkJnJ
F4i1lcKZrIAbpcJA1Ip4fQR5KqsQXOTbZBzGJp36y2he+hPPkfEwuiRMNq1piqIX
ZDP8Fu62ZxYJIxURd2L/d20zDmCiW4kU26TNKY6f9a4k5cziVYMRRjo/o8IabW9B
UEO4WXCRL1KZRCMmNGF4vR3pxy5VPp+c4oMitvpl13cBYIfYyz0Vm0LuzIW924Ts
O8TJNcOM9WNIHdhgzvPQ/E77zSpqMCBbQDKN8bCEmpsaeemtFEH7MzM7uHH+I2jR
T6q77rF2XCMLbO47IMPeS8KxdDXo0GcBukCscN/usTVSAo5XV/UoFQFCxS2aGXwT
uURnM17FyZOPDnEHwPx/GhyDqwz8ygO171ccMcPxnjlXVf38crDnRyhqLgF6RgvI
f+yZNs3YeM7KU9QyEFZqlQCrVfwUaJYdfDiojdRFJQwI6JsMYwJ1v/LEpkoCrehH
7up34VGiiFBHhvuPuoL88wiBzyoXCw1IemiLJ3gOQOXXxtWprUOagSPRjuVtoyHS
RgFaiqYV0raSG6cLkYYT2ZkHPRCBkj+gwHaqXhOS0GgIXhl7cXkw6/WWJ0GASAey
60lUS7vJ7h5PQcp7MqWle4zAMEpjRaI=
=RsGu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '88c547ca-adca-4b36-ac8f-d79060db723d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgARAAkbVz4h4q6Q/g9t+KAgovZVMQooZEg/T7COoBkXSczos6
8sRSR2SvbXh7eOQcJSY9r3BregyBNSguYnWkYlzgFsR9pdaM/96g0aN7oUoSB3N1
Tr88JTcCwp+e8dl+kUal+DbE8MF3nKKjmrukce2s1JM3R9NdXqHLR1crU+nRo4L5
ZCnleO8BXg+4nES3Vz9rvrlrH37C0Zg+BESPFgZE3zPx+bM8mO+qNRCOLZuDPt/w
gTSz5izJSSxW5/ChlOyBzietMB1Yz0C3tgiavTI/HYHJ+gT+q4uY2sm2tKiidZMU
WG/tlYD3sY0ESEqHGjA8YoziFHAoxLRpnDVqc4E612Mzxf+iVvj0ib9z5meYDn+j
zPdjGjc1bij9iiAhDj1fyOgw6xoSzAf5h9NZoIGbcWdepv8xBNFgEEhd/8AmKlbl
L1dywEpOmui35vUUa3E/+Rn1NixBMBs5QBirLXXxAK5Hfmpf1F0MzH97LiEJICP0
+urdF+v8X+oV2NCH5R9ByASLEJXvekM77GwPD3tyNJWlyJhFm2O/8vz4THJnrZJi
3EmsbAkbniJXPXKwjveq/5PPmB4lyu3ugkrEH0wvK4O7vuz/JKo9PShcjg5JdjL4
4Wrskq4svqr5P68JYXUw1gBuROvhtn/JfmatvqL0lNi2W/VB6qjlp6ukScJLKdTS
RQGWy5Gok1uGv1oznm19Dt2dy1OsZvpule+UFfqE08Hpe8pNCpI0b5nExJF6/AFq
sW6uaXtI+W7SAc4/8a0s8dGNBouI4Q==
=wToA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '893a2385-95f3-499f-a1a9-d42d40cf4e0e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/+LgnGU9HOwdR/utkWlaEcDagNqpBBDvs8c9w4cXj00Y1g
47DyCgU1gZItdNnAkiDN30eGtGik6mRpK0PRTAEAhCJOUjHz5CV7te9oiOr9klEh
3GVKqt5vXgNlqquaUXIe0DTf0H4U+/MfvNwpeXmBGuC+1nf7Bol+zJE4XkRpP8ax
UUazCDWnvnlsvTC5ymlI5U3Lbnd2aNPkopM9Zog9vXNiNasg8iuBmAt6Qp7W8ucc
v9lP/1hKH3jXUzkeb1Fp4HIpeiI3vqxWb1tLrsMFpI29OqzPvF+91djRwlRDz+UD
+hCdtRwuDye7/DwEGSy2TTZoH5sAACKfWHmd1xOFM2u/n+ICYNJGqgftVTEWpsPE
B1+YDoYjqLQXvgZgsdSee0mRVpQBUgMjSqvkhmhuGD4gi0Oi7gpzpGifYBjDfy2H
zd87IEEI0pBmQixcGVBX4MbLFaN0O7QTCJDHRdShkrqANLl57R3fbK1EBGWtA0b+
Ut+tHEq6PKg52iGJwf6+9MbIjkMu5odp2fS7/vAFfmWsVUcSOfERHect3GoYIKpD
K0E4Yh5RM04KnXjdSB8+vU9pwG7ikYGzfNd4zG5gq5gEyhwpgURfK5RODWGPIBoy
+4NaR6erXy92zKafNvh/QxlWzXh+bnEteUR0A1G23fT54OWejQy5pafMUkuX88bS
RQHuC1WsJyamG+w3HdOsdytbDfq0/i7eO39/9vdOeDLo5keWgd8xUTYedpDWjlq/
X+Ay0mbwwgtBmhZa5MgrIBg0GWNlxQ==
=9nT7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8c9ca2d7-ebd8-4cdd-a6a8-5741b3722d2c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgARAAnOAXc8TyiTEw76LlYwfahOwwgDNpMFNKNuTA3bz/no4s
omOar9xSjaZKus5yl6sx7AuEB12vstTXK0oquHcHSAJfoOinfKXfUxhFlOrquHSe
EaoXiehZvFay+C4Tn2+X6qSu+o9cLrq+m6I80db0Q3R08g8UodVWoDz3eqhxpKbL
kLFsYddGycOqfk9gM5XjqVPQsfwrGmJjKls+hAtOgZ+wxUpv0QzdwHlL76gPP69q
MYBNR7c34PjXhXyBAqxvktoALzkKgDC4txz4YyYyD3qt1IgFZV7qf+fLFM/FcJ2W
7IybsW9WSVVv8B0t5F8dvkUGdMtbiRydiZ9mCKknDP52K4mn0MzboLMJTBYzkh4y
uAlmpMw0SzeOrKRpVjKT/RCuNJUIotOBoPfFcJrwQDIL2rl0S1dXyLIV+x2OhZBJ
7eHczPVsOjItxkVMAgBcFr6MlhGno1Ad5yEQoWNE0unAGg0eWCgKCVHTgRlq0jVV
XoICgkJQ2iw/jIjGA6kV64Y0AWPt6q0NmyoR8ibqgF4ZUaB9UAgDmatWIeSmNsWx
wKE1O6I+zifgEbJDH+5ekV66ckBMH7fFwpQzeCCsMKo+Ex4azkSnjfhOLvQPXwt8
l1GjlxDM+6IwEXHUmEPBrHs74WbGJjXoirOw4Aa9wI9AQffbHwtYTBAYp9Aaa1PS
RwGdtgPbCJiYhBX/rvSRe1QmD84sfZI+0m5R6aw9sNqpT8lPDejIxZRgd79qDERF
6BnkCSbKlLbgdG87fXMRCH8sCLZJ6d8B
=Kf+o
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8d6e57ed-03e8-40d4-aa3c-4e7895027eb8',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ//URP1PQZ8ZMAk+ku33V1/4/caLUObM28ChcFUcvHio/Ll
JfwahfEcYKrpyDnhkyUVzk/D6F1Dmm+9q2WiCUXPKjwFF/W29FJJ9AjwncnsK6ET
uwlnzmKbhhFCmlBvExVK2Y0EasXPIZMTe7xk9pQG/jOGqrERIkncswf5KlnoKDBE
l9sT936yd9pdC8wgmEiud7u+cNgALBelvLUtEyCTSqOMjen7uRIPLfDiqpEkrSRa
cNYNY0G2SKi9ZIgbmd3o1SZTpeqz/FPfNtKU7DuYeKaZjLpW2C/HEPEuhrQlV403
P+FL+rTaIgFTUEdky/n+uyM9K1g452p13oHZ/tC9sNPGb6hLddKW4thkNK9iG/xi
rnD7nnPR719YOuVd23570mzxh4PR/js5D2OvcbZiF3lWhD/jE4bFBYPUSv0MggTm
XywoS5IlHC5OnCwXZKGTljDmFRm4rqxoxunbJ61X3GYlWRlLdDEN44kXH6tLDJgF
8cfo7TWGXqG4SYYU038QakoO0RPH4k0pM4HF1ogNuNZSqfUIfcjSubidJUreu/NB
EkSqtDQhbPT1O5kl4bvDiuk1Psj9U8yRniUUOqUtuXmfUlNaVjOTjVFxGG9HvdZn
NXfNcmPgv2rjcaVDqpexyBOYbl3aECwjM3KmSLWL+/GtG9M76L0GB9H1SDD1jnLS
RwFl3KgG450KVDsTtujM7pOR3BOMbWfNSfgTyzrcNFWhLjpZPpHs1u1MZyeS/TIN
GP6f9ctSjFQEoYw0aXOZHNy6IYvxh9UH
=cZqK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9131f2b2-4508-46b4-a956-14373338c979',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgARAAgGOxsFwuPV4cicGy6pEvh9N6Rd44LPzNSus48v/irjfR
Nu9srFWEN6FNkR0eTOah2I/VQuS9OyYQQL+Qn+vci6q96qMlS4+O3AaOh22zSOY7
q/2Swr5XBuqRfP2TJTbemABqFlufc7hRrkdOsRPkzQpG/gauwvG/IFqnK1OsqG/n
sAVTJZIzxx6t/tGLVtkw2xkABbDXdF0R2P/4ub9eKVmiYr3Ozgx7NNpQTRfV7cll
VgVg1hNYFuTPldHMyaQlbq/6ez2Bc2j7unTp9X9vb5E3I7xAk+Q9a4UxlZaJT0mn
jyxH8ycc3BpeZS2ji0DyHD+KkDtHGrEnroy3r0sZEjvDezKw4cvY/Ecf0nEAKOCP
xlzYiVagwM2OXIW8hfY47nbE8cJWUY9EO7YHuowglAUjlEbhNeTa+KhhQts+oA4b
My2XELDodjwNwI5okcNoB0yaV5uw6rgpDnCzQg41IBtcuJ6ljkNo3t/u/WPaFZT2
0hp3EevXTTREE1lZeNOofneb8poCvlkFmWDUlw2g7jxN1GzL1e0CrpopRPCjKgfo
YNa9CuKigGBREP7Aq4Y0lLytwlj/jo2cHa+lk08VQX0Dh6YneXDLK9HlnRu7NE22
BGEGs/xkm94eFDGWe/7ofsQQs8/OEPXtKsVIcg/VR+pvV8W6KeMV7omaVv0fOdTS
RAFALamVX4Cn3LyicL2Z50Z5b041pzZrpcU3g8rwgk253QWmNVRo5YrUMLXigjqv
T6bWtUYgpjAJxZkGKvanlDDCkdJr
=5nGk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9a08eb58-354c-496c-ac94-677e92b4ab4a',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ/9HGK6BQ9kZtGPCsPqqaB8Rb3/Y1m/0YmohCvo/1469e3H
y1Vx/6xsZrzF4PgshWn3KGeKnF4u6kZ4ych+YH91p8ST3IrxBCbdPc1jhSsskD/U
SVwlxbq5Btm1hQ3bnd0KGo+ostv1qMndWSsZ4yLF/GWLFEQYWbcaq5EMR6DzuUK7
dZmN4opyfEAPb+dIGU6gKvB0QLrCyv9CbuXg3YXF7FMYRMjNe+iaN+fVjyiVuc+p
Rt9uhFfKOq52i7lXBWy9OEtaK87J9gI+93hVw6l84c/GPPChXtjSuKhK6tKcobiR
x5zv2GnCSJ0nd1yM7Asr4WbeM0ZLjzDV6DEbdTHY5nCTbBptSYy1s0XhrKM9ooEO
P3KW1hlnIjqWW7/mRW4WzKQquNnGTtthG+5dmjosjMub/XTXh19up/dV6SKvNvhN
7NmxJuFM5InOOWayG2cQgDYGsBSYAIXZqLqU9WDJY+lDAt4jMr8Rif/g87Excgpp
WQ6+iYiN0v/j1Oj4/koElEjeDLyP3B9YzDeT9S+w/aCjHwp7tZ89m/DEkGr5wHeH
rRkJC4vEqMylhd9xCxdKkegyJrQfpYxnVsYGt5Yl9vlViqOZcPzu3mrhcSjnaLG2
nx0Ej7GKZS+eMiUcBbuvILu041XYvFjC31bekBo5FCbxES5CbdMDgd8zgJcE6ALS
QgFw3qjOMx9eMMqZW2P//wvctdt7qcGesFAISUG5uOEU/hLfkglLVen5oqxBrigE
jt/1NMIiAjkRgK1nPOItKPdavg==
=MZhu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9ac42117-99a4-47c2-a3a8-f8fa0d8b3387',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAzlz3zlJcBT3ARAAtTcC166qDJUzQQSzjC1l3IVsq6ouhfzlQNOzU+vLEuJ9
xQiRWh3crmimh/XSLffICugpnvbLOyQ30MtetBxMD34nk+JRWOkwIW7aAIaIqT0Y
GZ+3WImMyqfqkzAw17RAmF2OslJ524kJugOSHAnbOTHC0ybcr04zzvNRJbg6tsDc
GicBbydyWrzyueMhUjq1H4tzLXJT0zZy256npMN/u6R+8c+vS4AYU/D4KB4d/IQy
zJ7f52dUKEcvcVr1QKrikNSduHpOPz2PXK5QZOYP9iTayf0/cG6vqIW/6bST1j+n
JL28jZrP72nraUD7N6cZ9I0dIs8oHO75HzfIwqIk5QSXmZkGd9968Pst6QZ2vr1C
GlQdLHJH0fyb2YGbLTsatR7t431cHpJtevjbOsFPTn34LiAQ9DKAGLBj82v2Rm38
sI7XQ4o13uTT4LeAi3G3fAqw7M/aJEkezabq+U/IEhbMUimuHV19clg8nKpSDzPZ
pze/ZraX+uxcTUW9ltASMrq+Jquz44kJHNtBpcEE5308F805xcR8D4o3ZHYRVCam
3iHLD5tvgBxiJYJQZok8PTVOyqzsC+rXHs3zMA94bQITw3pY9xPFwpZF99z2U4ZR
1iRFvV28niAzuwIyb06j1D2Y2jieaeTS6Xd2Zeg43KH3FZgQAwljsLlghAJORAvS
SAH0PSgoWPWvp8lfMre/96BxJxI/gLZQXPORrSg9Oc9gKaNB997aqSPbWrhIo0fz
CszC2yzjL0J86XPOsGGxeTOM9uQJbmhLJQ==
=71yP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9c800b29-f094-4c94-a46c-741c64e210b1',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf7BTH7RLCwxqE4Mcspl3C3okXi9anclQ/DpvCpMk5ZFEnS
QlDrrmQL4fdylX3BQ9uQ7vOnk3FHEXf50si58dkrXDrcYyUj6tPaK4nZyCzbtg0x
MCFodC6TT9NZT/RbE7Pwi7LIs731zhoggrzl/X/fNldgUGzXEcZ0/yrOoDLFZmaX
Was+0TF6CCuh9/Jo8r8CwnzePVa2/e50zR2QODPMgkvi4I5q80SBPK4Uaf+Lt6iJ
c6/yNuv95rTvM/mlp78QWBhn1O/53EpggiW1pKXWtnmJhxSwEnddKJ3qpk//5h8j
tAPDeHK0PcUDAnFDW/6MMJYKXn3TGSPVO95W4X9NkdJCAeLmFeofSEK92q0hYhTI
TV5lYwaghxjQokSNQMZwMvZaXG0pvQAfL+1pi+unhD8TvcnaFkMdYdfnQGlti9Sa
G5vS
=TLhU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9d9ba85e-f14e-4715-a3ef-eaa82647e0a6',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//ZplVpqbpXfGuXgt1abFShwNB2Z0rKkE/zPIp3jHvlMxC
fSRRuwqjT0FjUdzbZeyHLK2HK+gsEY0U9e2c81XMoJrRu7V81PMtZ+jLidF1/oSy
R9z3A+M/pcK7JVSMpNcaZUkN6P6HmXDvDj2op2Uc4N19wLNWVuU2MEsI/Exq9NtQ
B5kDAVKl7UoOt+SJymIdAgAyzW2q3X1dFg7dJaUniXS2nrMQlRzjKa3D7j5f0p5z
H3HKenyHrnH3nei9u0aV9+vIgqJlM1Ftl4woeGJlcBl8kC5ZohHH3n+b9oDDX5fE
ODjRksH6F59kVtmzcUkLmJl3tIznK0yoL4PSglTD3IjcFkgvwXwqFZ9+gjp0FuJP
f2Iml+shim6I73dMoIzLqI9L0ZBtZBFmzZNh0HzyxFdnGLRCH3t3LKRaPEnyuvhR
75At0TR3UEqAOj0/8j4DQZP01TsOCshvhjidOOr8kaK+JyrRqdzMqbPVfRYSr9dK
izMx0w/FiIux6eHXsBOD3ZQL48hBZGuWks5mPfPNzDDOY8AGWJ5FP+P/pUgQRNB0
zrZWa7yUkmG7wqeAqltsj2DrhLc2sUfpGQ85r9Yp4b5sZdC9SxMxM2nzjV/UBtVm
ZhJ0OnStrtyxwK7ySP1Cid0csEnJWvaUjrUI/b9DzwSADAMysljWH6DAdiUsyRTS
RwHy4ap9guJ5VYqT/5JF2xX1xDwgsNDmT9oEEQsIBaxSDoGipGtDURLLeYOnn5V7
0Bh7LCF8IlNjk8COeuIP+XnrzLEadJEX
=GHqb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b35b191b-6980-465d-a044-522d75f43af3',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUAQ/+JTols3SFun8Hk1GVXqKxa+cqlEXzr7mgMfVsGM05Qh2N
9eH3CIbr/sVMNiyQ4zSX0V1/jWC/IjsjS19LwA9oGMEFf/Xb66focbaySgR1hP8L
WiF6AplWByE3LSlhOvghmXN1RNx3OAEAZbI9wIHR5doTIgpE0RbokVmxsFsfUlvf
6elIbg+qvr+zTsXWyP/7iLesv3kZ5EI9DRJllUMb+65FgYcruPe/XPElJFX/FPSZ
Pa9Qaajvo4iWGCTk+r9bfxOKx7ZVDLHPs9YBEdtNbefoh/Poj0SLbtj7WbxFq2C1
kWQ68ZmrAcYqGH10J2CfYCNXWNLfO39f8DbIBZvc23IFKhEnF3bX4PXfUAOCorcg
s707iiAk4jj7D3L/ThbENlSMWr7xcrhuGh4OjLoyf1x+1hvPeS3Aez0S7jlcy67J
9bo0yOWDaSd9mn2BqJDQ14WhFwO+H8Q2SwHLuWvrZbPp5ctwsFQhlcaK2KcecVdu
qt1wUxg1fQI+wpU2HLGbej2Av2sKkE8v449BtdbyfxYwKW2D6zIgslyr3+t5+mxY
5LiiGcc1PPsUASZ3oJChOFj1MYZJ89qAREedMR9Zk82j30AYD47n5UjVuDKLomM7
6ulUGLalAYGBEHzPog0OHVTJXKjkAm2avBYUg/miT70U/eRM4LMFkss7CFLYVIbS
RQEkelOUv7wBPKxCtBSediLhO8Bo1bEAym0vu5pLcdaOR4tYz5zvdEy8z0bS6Yjz
lFaOzllphCExo/TOjxWEV9WpwwU6ww==
=H9xT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c088310c-8e2e-4b7e-a59d-6f42743eeea9',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4Djy8VF6QYUARAAmVNiqvuAt0Rl2+H+UFHH/AIAor9h87IKFO+poWTTB2KH
qpoEYZZzRrCPkLfTzXXMjQnyL7ZyYeLrVcCbVy1jaQu1BKownnR5BDTb8KPiN0Vh
JnDYnXox7nS5pZ1OGsE6aY30qZ7aIGZm/92jLBwwZVUfp0wy8lu7uHp2rSafjg3a
OxYxdyKx7+KwvjCfHAkVw1T/02DgDcaBSjSj92mxitFgxHnI+nHslvZSb1CFmYTx
P0FZEYZME7ubZu88Mj8x4mcD6igpUg9s3Qd6zMiBGNEz3tuYKzh3OKnuUtXJ8Ob+
G0VmIcAax8eqwPYE7qhhhVGXNnRuxEoOWRR1+EZwZfaEzlGwL+IHZ60i1GAyAipo
GT+2uQ0hg/iIhsBobEX7Bf17iwA8CXc/8ValgbrQWG5jX8esr8Os+r5GbT3N4D+K
y81UkyLrg9g/Napm3qw8KXxLGT46d5xPJ62oBjfzDLUddg1lnsWH2DOwfBYIvCrB
8FHsv+iRgKk+QFS6dpf5l8RTyL0n0tl6kJeIgE7H+NM3VlGO/w9ahwYtXP1d9VyZ
pjpnBc1eMy93tkTFjERU9WCCH5qTIcb3Ul6zKYQQqfUAvQFOZ2NLsx/GpyE+yoZr
GjzBDTC6ea/sKl/3hZYzFE0J4MDQUZNA3rfNRzTpyza8EwLWqnGEQU8XOtF0H27S
RwHhgnBYcVU9SY6qUyQ0ff6kEbKwwx2TaD/3tRgDvg8o9NblztwquXA+OAEIHN3m
f0ElIa/76ZUdeHS/QGfADjDBDCFwrkWo
=UZZp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c9f5b0ec-b013-44ce-ac5a-8b3b64cfe817',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//ZhQj46BX6q56v6+vpjXvG7TLxrQEUpYcskj2rKFgpag4
JUeyxVrNjSl3GD0vVaG9bXzC62LU7QMJj+hjPyPBZbaAZIaldmSMRmQ97NFAYTZB
7lRYYaJ/USJc/jGAB0VO2dPLqPDdx93ZXxIWNZILFwLq6PMoxs4bm7jZOIgeUW1x
ECyZUcDjhWUwZlKURRHN2WW/vy825rlvfyAzEkSjy8klubFIXcEx4zucROtWSpu+
T4CDLxlcZ/XEk7NIGLzOY/XXWe3HqjNxpZ7a3KTEVadhgm6dYgW/bbb3ilCesCWL
SnI/V6qSv+5UpY9vQZvPEco0UdI6F5dLBtbQmIhUi7kw/sbbz3K819hmKQkHs2eQ
lO+XqaI5k+ZKLHiyxztsPGRDPESsZan0cMEo+wcQEyKOg19+ntBJ2yFAhhgXx3NR
8oo2uqKsKZKAw/NufFP0jSt25KlAoTYxSedsuugbHvQm5hEsj4Iqi7OvykgGqcs7
uDds2KZKrTOtKob+OLM+yBFBga3/uaIx0HL2jPKfhNE9LvpMvgp8SnbVubSgjXk4
+tVKxHaoD3aW/qSA9qsfBuZ/ZufUEt62uqfwflXMkYvDsDgBJy9R8WF1qygZm4wz
eN28Np4/V4Vpc4CBb4wBJ0SFeac1GghZdIXD2X6D0ar7inO4+sLX9XxsP9FbRAXS
RgFg86ixx1aMHcH51a+UrnTJy7NzA5IoSRvaS40BaeKpyEg6tL+FFxRV/Y/XxDNd
/UU0d6yQLXZ2H6dsEef88FCCfvOT1D8=
=T2Xg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cdb3c6bd-231e-4ce0-a748-da3e0b7568fb',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/aGRhm/cpgCzGJgLdh9NZpJiPdp3+Xixc4vlzIdxhN3sa
wLdmcpI48IP2JrrP7IYwG6M0TWktSsliZWvvhMOt/1nrTezxnuxUVjL4bzNYOYe8
hufzMdsiE3CoOh8W4wkgXb0csxuQUuXvHTfAfxrJLK/caCYacjPE0/JhJ3cO+Vm2
Fu8gMYtql4DwTQG8ndPre6ZaSdE0vKbRUf+FdLLgteKfC0R1iGODqYMSKXODFNKL
wLv25hP6WWkW0RhX0rM5+mifni/KjE2z3Y1m6huUHr32qviAGX53i7X7c1F+d1M9
FoJcxsqvihnS8OvcI67ywV6qGR11d9nzN6ThpZpsrNJEAX7NLIRIyAHDCk/XZxPc
VuEpZ0UgYq0QMgScwICi51iw4JQcDxjg3uKDSaI6Kyit5NrXAD9MGm+GifwounBb
JQHnKzE=
=8zdQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ce4649d6-7f88-4941-a4fe-9f6f1890fe51',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+ARAAj/utt34IDDx9MnBxfjZc7dnTiLagGF/WoEhz7NJDI48+
CBreJq2HCUz2kDLXjowmefXyXv1beupd14If3GKtgQ0lVyLG8RUTu37/Iev9C86l
JOnyFc9QbPtTOA+s8FBklw6F9eOi+O6gINKCqVuKdRhEwhXWj5imbgvSxTzJ95oT
77vkK8Y2W7Mt4KL1e8tehh6DtkY1WRMnVwBUkegAFH2xLB1a6ldA5eU0sFoLMLS9
8znIpKVrcgaP11rQ8x8PWpeLp8N/jGtueZZ4RnB3FMb3A0nEr/PVfcKTSNN7D7xP
n0csaQrnRJfn44/Ukn0QLXq5mhT6qzFuxUY43Sl314ov4DBZSEWhkpsvsqBKnp9X
4RTRUJby6bX7FhM0ZNXxYhMtFAw8AvX9tVIxA0cY8eXssANRnqgm1CWnE+YwEp5u
RDAcjn7Ohp+a0xuE3RVq5L84qITH1Daxv/1ggDKBu/YFF2Pk6v5gFdYXcr0Go/o+
mErV6EXaC3g3bt+Ic+jbIHmYlnyfYeG/OWYGDEbWb1YSRDih/2US+VDFfClmmm6h
DvVCdtFryRpYuuoumpZq1/pMKDN6Cpsk26OCNRcu6+KH79dpO1OELOlFSupx08hf
lyDQnoA3v00Goqr779g9v0wQXZF+Nj5SnqRTLDD+nS21Oh+ADqGpNKedFkeU4oTS
RQGa+W4PvdkH6MdTXC0enYjrRNnnqH6oKNwUVbX5MWh7kL4OCpati2iPQrfMy1Uk
uuHPQUsS0+Mto2Z4AVVtd3Q4wBLWpw==
=NUXJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dbd72e2d-ed84-4d33-aa18-d433029b77b8',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/+KGBZi2RJ4+Ro8+f6ghz7wNmG9czF6az6HHTXExeouC+k
GW/7FUJshv5/dAx0KxIKctZGdGqKs0Pkakpnnm+avYPTcHQyh1xFvWW99wR+VaWX
FLC0cLKjbBRYhIs7JsSu77UfFO/rSqcdgmCkqz1PfQ8ugWqZtkS4Yl0OB1C2HPkV
ae88khcmCqh+5u6gmH5fhwXjk+ljAK4FnxCla8hziU0W2bhxKnOom+Y4IQdUf96z
lGROSJ4Mr9QOph6VfpTKeGBOgcgkugEKgvk4t9zScsqRIIL48wzfm9AA+199A5zR
4QWeDQawM0z7SjKq0bo9jIer8LZJHIsmGsJ+kU/tb8SNwCoY4ZH8Vx51aAzB8fPt
WHlNE4Umyt/OveGX0dqwJ7fcTGTkBm6wDrOky+7erHjSpnionuUergSayUITM8C2
UGAwvheMAaQvRB/YkhWnAql8oNNkY8k45asn2JK8vnQvoIutaw7mOAFsKCgsscx6
GRhUk0Uv+3EtWsrXbo8ndJlIyg2SQq40JW5uC+pKwybosz7I+Od+HrJVOkbtojz2
0Xn0kWVxlZ45U067UwvoySrITNIfq/QkGgBT5sJJ9gDifb916KniDuT+E7onZj2g
7Koqcr35sZBi413V6qyq0TLQafpxR80xhA1jNW5m97CNELUSPQvUORqBvnBD+MLS
RwH9gkX6s6n6qyD8IsSbryx5QlAlz4IZYGEWHrAT5F8Qw6Qmka+SsWu21zb6gcXc
N1fNCQm6EFI8FPIAD6D600V5jk1TKTev
=HgHJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dfa44033-29c9-42b1-a56b-1321bd6a339d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ//ZBLkmcqgWady4YV5aFWFFjyXgkjnlneosuzfHvL2IQFt
SLfGr8/0buUEKNxlwIlpz2ssIjNUUyuxr+UFQkDW47+XKYHQNN73EGrI8PBFBEh6
DVD6xcocCSXg0WNVqyL1BVGd6qJv5HtWhw3lWepMdixnqj6y/ODG5oyLCnOVNjyi
NETTaHf0JuxQyAs3lyHE09l5h3ktwfYWIFnXtbFeRWhFxTBk5iiCWQ2KeU4/25Oe
xRxou3IbyAhoDJKq2jpoTNlY0j/NiWqqi/ex4eyB4Nsu8f5knhr770LwPRnRQsJg
S+BptmBIez5VkOmjJRuZr2maR4RwCmGFOoLaTJfKyS+ZT4lbSRYwY2z5YeqAvH3+
TWobdInYgs4AC0BRrmsb+Z9JLpH7vfc0RKCmQrbX6O22fn4vhxK6M3pfQHOFGF0r
cbByAjCTN/dp/po93WjmS9XIsV3o5jHCYhYvGotzNs1l4Dnr2kLLvPczudeqNlt3
cdXDXFXVcAhT+JiIwa1MKep7wGVBnBO8aFWC+wN+INIFBzs5ojrTM0rcczT/t2BG
eKs5/8t4TqEdv+mDjOms+F2qZ4ZQvokGh13Vhvcns50UtRRtRoED6aRvKyDh52VO
E9u5Poanm9D6rl/Yjqn4cL0/R17y4nTQyw58Q1L2T1RGmUsjI+iqPJGxnlCyv8zS
RQFGdutysCBuTsX91giUCD32iVnwiZ9BfYa3kHArrpimw2T+SQydkDQkQfFIdVqm
nrLkuhEecZ5nxyt9ltTXk2eBDpzS2w==
=/PCi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e0e34dea-ea43-4ea7-a747-aac4b9ee8caa',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/7BvFYOO7JD1/7YpZ1nWq7eSXZ5TemBeyjLsDSP0mbs358
5H+iVwpDDfqyvCD5Ps5cUzD9/WvEG092cpFcvrelwx4UA3L/FMAQH8rCOTJ48U4a
RdHeyekSCFEq4IrSxwKGHpm6NflzQH27p6l5JUafd8/TIJ9H5nZglGGoCjLzycr0
7ebpyq91EEZ+Ya8votpPp9z7NIaTrwPeTVwzYKwfCh8kt1M4SXB+ljrJoVRmnMSe
UJq6gU0YG5jaBGZKeU+tFj7WMIAwBG4r+jsTbug1OxVK9Ky4Re0AhxqS8eiharw6
gMJbvyzpa2u8pLKvoW9a9YTYCc/AxHXQq2fXBCrHHKi/b7PK3cih0XYtRi2Mltgw
6TF/7y6fO68exsIK+wtrLx9uBr50GdGqchHs1y300dA6SHsPje6+FJrDSuxvqARl
OI3FeUD1iB4Va9vaqmSJLrG9KqYfLKO5h/t+NiiRfLIG4PZRgRa4IfhxLe9ay65C
mPEmEA/VC58rNAo12TqtBHd0oxIWZmlzgBF6xG61rQLhAuBMfH4jaUZiboy8DT8o
JXznNha+21WVi13Hh1cJp6GP2JinzJwsNZDkn1jJ2+nLQ1+69UmEwhD32jgJENHA
7N1/BvMP29EgPVVEBNhGHGvF474AQLewL5Xcj/TZBxR/ggeynJtlCazAxBe34c/S
RwHIKe8fppL8Rs/wdP5dVwbKkOV8LgsAhKDbheD46lXN/I5RpQ/rYjO2RFybAp4o
xDUhVIbbxGY8HRkDL7mEGdx/xBwpexrq
=tuq4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e1e190bb-9be4-464f-ab6e-e9f949366cda',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ//Tww4X+WNHO9n1CXaG9OZzQG3ctpyxSx+lst8OZN9W+Hz
7UCULw0gG5jCvrOA80kWgw5sH9r8aujAhwDabYoInCHFRWfznrKuxsgiZD3O4CiP
C9UT0CK0QuqmjhD3HQB++CjrkNsm+3WA7cLu9P5waFd9nCWvqAA2nGRGiYpSDMqn
L9jN+zpMsqcgguZ4DPjKt1YbYAyX5V0fhzcrp25jsSgl3LgDb1Em124n7urs8ARV
to/HtNZTPEB2BhYCE3NpIPJ+PnKsft+eR/UcgD3OSuu0kt2swiurNdad076C9P6D
Gg+XpnczFMk0O5mMOXOipmZHdTmXHOEGZInZT46LZ+dt1Vow247PGwq/Gps59n/e
jrGPa4LTQoaVwDgD1qnBLRrftKQREC3HU2Lu01dbVVoVt6dzZMo6EtVW0hHOxq+V
j8q3fKGXCzh8PE/yyorcdk3T0FSM/VSqwi33GyOHOvhkjU3IbONIqIVQTc83S4MY
R9QiG0sKWWt6wZgbfNvJUamaqS0V0T2/+FHFggsB8Yudo5dZuqhwkVr3/x1L/wbe
5FKfIy30b3E7CS+sI2L4wNwnRoMCqqXyD7LZSisudK+Kuro4V6raLwIVl1HIeeVm
vy5V7a2eRIBtCQiBXBO28NAAYsaRAXTfE9mt9xB1PmqZmolrMuRIKJZDm1PBYmXS
RAEalPfNn3lURgLrXqH3b8q2uaskNMsiRNR1b7JhaVTKsVa54sCsCGMVzzjznMks
8HNlP1BT//jcsw4tWxqazujyNRmX
=DuGm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e8199a45-8b10-4494-a67e-7987cdd5f1ef',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ//d1fNVpgTi2dV3IXaucg+WafLydTHWxuy+nzD+w4x4UOF
vQZmvkoE8GGSdzkWvG4EqXSr7BNCPSunB6uLoyhSrMwDWk7jZkGs+AYDEsmg8bS1
gM0m6DETz/1vn95gXyOnYO5uav/MxYHDFuOlXuinAwrmlLna+5ALQC8Cu3aQ8WbW
SCs3/qx9IAhqLH2Kx9cL14G8UO0JTqJxUd8qFHD1wGQbRIMjZLbIiumJkvxJZG30
Ms//SIzuMUJ3QxtYnrHVm/1j5e4NjMXCRQSqbXSJXNsHM4GD8EQ6VO9AoYDh/o5B
slNegJP8b+chBG9xxJasZXFzfPK6Ur0asLDqguMh31fb1+tg7XD/heoYr80V+d1h
MkzLdBxDLvAnYpcOOUbONK+k83sRuEgNyduGOno1dJ2wocMXlPbZTCd/1DEa6VR9
rcU6P/x7Mrf77wgJiwgVWLdWiHSTnWwzcItpC4QqbvuvY7Zue81Rtl/62Q966pp4
gmt43uqsZCMR+se6VVE/FlOYzZI742Ypq2WdsX0/hAaI+1SX/7WYh2dMnBR3BnD2
0Ppvs+OROfcohH1MPlzQvRhLtbolyKDVMdS4XB0oL1jEXMIV6G68HiwdIvSfT+K5
hBoNFFnKpdJD1H7d8xO/uAH4q7V37fl9SJkK8DMTVjRTEba/MjPThISwojUh3ULS
RwGrdOQ+9tl/Q/f3wSQ8vFXcTHue94kiY2PJ/f/JLU1qHnJgfCy/6rrZQsfOABuC
nEVSZ0FF94uXqALSwndC5uEUUUhznpnk
=5gDX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e837f314-166c-4e44-a946-b055ba2630b4',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMAyQe9uW5MLigAQ/9FSLmrnDEPQYrpLJUY5vLylntaZMS9UCeSQZzLXyDnEoE
i6JLCFSQFO2Y2Qe4RMYkzAHAXJlGNlev1MNiNFTlxwkqHTq4XX2POyM0AT1yk31f
FwVRuyJ3yfPR1efkweqJu+6+04lyiJp6O0k7Yea0dBsSmydbq/qbWL9iEShb6Y4E
pQ5MWFQqoZo5yvZngqrg5U8blcGB6O2WfCyj3RBqvz27eCw1IQH45HErtcBDSgdB
hcYHxyvhLqKREzRXVZkuxnlgslY8BJSd3eIlO5Buj4tDkBkl66tqIPZhUe3uFrwo
npQi629l5bc6j+E4+/DyHg/s4B5vMg0TLzu1NLADNa8HkHzKZ3k7LlxedhtTos0n
V8SVVOHBYzFhUySEAYHcqcr611DypsSF4GTXG6b7yvIfrwMDqSW43WEobdBY6qOe
hkx5Nb2V16oJZ28Qx4bZ0olkXfHggG6yc7I7UNZF5W237MINg5HQSG1CqCeW2rC9
+AdR1ScfO9A0DuJDZc4QXrU+KHrA0U25EZJY9ijSv+IfLT7jF/lEG4ZqPcF4s7Dz
3UcnCb8mVvMz2Y3xI6gfODetxIZd04DR14zCtoLALZ5r4hWZG9bMa4OlcYfvhpgl
YXu4/xP0bxyW6vxASfUvKSqyeeCVNZIZMjQ4WXIkU+YbeJZOvAih1teWOpCNynDS
RQG8yiyVCYy9qtkfIC8iKIY0cxzPwYa8ZRN0qNbsmvtDlI20lo84LzQ7v8mgIfjW
zfOuahDzSPX7d/ofpSMw7oAMmi1+EA==
=0lh3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f0f6de29-60d3-4fe1-a38e-7e169d54f2a1',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+KyOjVkdUd9UgwutGndbyUvnPioIxEDIluHSX3ATtJCqW
I5/yEj0zSQX4xkXYjCSmHRDWTxfHmwKLTKwWOm8DbldAS4C7wQe4Npm1CVbESwdL
z2z0LuZsdIs7dC1GGtbFn7mWH5z9Q32iXZG1x0AQsZTG0+q3pY3JiiS++kkXYgrc
TN/w+A5SWnRJul9mCwO0DcJrQODolUirkZMjLluf2Qf5jiTzb2ipTKcrXJPwAnBH
ZcM2oEyvPPbKjr3jEF/vLpGKSRM+Z8g3oQOXOigF411jLvjOMLZrDw5Nz1ITeQBE
vYVn/FwEFahSKx2Naa6P0NAfpQVGaWYZAqxxVRgqndJFAX7OtL43n5qARwK9djm2
YkTcgsomdvgK5Z5EP9jKIlOhabkJ2ts0l1xKySR9IsR8iVsBn0a6l6LifrAEMEHl
+noYBOEv
=Bl+F
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f10d2e50-85d7-49ec-a8be-34183e709628',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA4NlM/alsYWgAQ/9Hd2bdBlmSjpeiZprMk2gyde20/wQRG7xnXQZoOJAdlnx
IUUCcYH8svVdS1ltgV7bn+MGVjgWDAA93649Q7ju8xeM/ydGTm03Br5vjVbM4QiW
JYdmAQGl/QCeH5phsfDZUk8/ffTilC4gOMgE3Ph2LYm2YXYjtryWICk0y+nPG0se
X6mBzi0sYw45QeAdcrgytRDEy9kZ48z5Cxp6edt7LRc5H3DPLVazOULgJma9XReZ
0OI5bmRP1gPnrZDZc+p50h3j9ZIu+7+KhdiL3ejOmfL46AvMGq4myFu0zrVGCQcI
GRXhgdyacdbvM9b4bshDecm7WGGLpJmmSW207rsaQEQh3aABqRCkyPfYW7Pg6Naj
CFGXP+Y1KoU+4SJ4IIbndNoUXTQx20C6Ak+0FWI8/yrzNmRRSOJn5rN3KUYyhOkl
pXbMf6levvA1YZ16NcvPMQjbM2K7L2Keo1aIYLFCWSiYMZYHn1slCYrvTLBgKC7P
VMR41/X8gsUxlNobLLs/lCveTZRi+aIQCU31bcEk5BCDWv6uuIqvYq2gBvilpUmN
VqQRbuzihiBvjKx9wgc3XZ6pyMx5W1UDZzcgoo4G+DeUJFTTAFJnC0miW49hc+h9
UIPXSqH3uTohIvRjANK7nV0NyrLPo+l4+RUNUmAe22MRYmFVL2wvfBzIeOO4Yw7S
RgELbd/OnWpPzsgS3tC2qhDAvrFeORik4Ks7gRf96tQbPdm1sDofdq4gsmEXpg6m
nR2dLnTaHBTPLWae8YF4A/KKkXBP1nI=
=QVM4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f188b259-b7da-4629-a2d4-6df15cb6484d',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+LyxEkW7XROUMd7xjKPW+g24rMwrF02WOYpPRuhKjL5Sq
CWTgsRlPtRSHpm6o8k66C7hRAHM06ImtNo4NX8lIAf8m+dROms7nySvty6NXGoZ7
XjU9q1yR3KcKs9eBYUukNVkpRCg0pHG/PZNbuoC698amacEZSxd/DLMADVu32w1Y
YBkrT9Q/AKjQ8UuB/QbqFcY5uZl+DlyVVf8QfEmH5fXvPdGV8ojO+tY4UjGW34pP
0PepO4eIFZx/C4UeSRNOqNKmc7WSIK9Jd6nDHAber5HYXViwtiLDA9skZlzwlXjz
7u++ZooVqhDu3P60n5jlt90wBfnRHbviaNWaLK9UGtJIAW6O3oxm+t7fO5WDnab9
tam/VQS8TMiucmbxcLoSjFWPzJRJ8kHFQHzImuXcZ2ucaNjQk/SijPzvW23oFVMo
Ye9MSNoJ9TD4
=eYn5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f19cdfa6-c71e-4a60-add4-89eab5d3bf56',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQIMA1P90Qk1JHA+AQ//e3V69+LESxPs4oxqbN9w681JugCMKU9fWlFJQq4oRPm2
TjudtA3u0LpFJXf/DhiSYMxhIOgE+XLnz/PDimETbWZtCIBVRaoEWiC/X7vvAUo8
0JLdtYn48tRRl67+XEM0nbucWexsGhN0qwGUkZPtWgmCsOVN2whQMeSPHcoiKZZf
0fKzII3QofNOMoUq+L5MbAFEbg+B/cNhqvT9hszA+/pHGxvjJX3lSNap4p4zMEjJ
6VHLRgonYuAzIgE6JyXhATHGbZTVeqEFuAS/mkvXTAef/s0RCdEHzuD3PJdFe2+v
hQw3zUdemSlG2wuW1rltDzkwmUsrz2as3KTCF7xgZ1WJHzXJNKgbKnZCbaedPovx
Zakd1L0yRIBMbhGmE//bbXvVxjaFyaqENhCa1q//fqMYO2HL8SBf1pes+RgVvL5U
VEBE9LqajABqdlP2/yZCbggJOCXv1f51+BicNlpfghfLAuil/s5HhmsYfXyevLZW
tIJUBxIFBIpsi0slLM+qJPcOEdE4r8lpiP0fhQLxZ6zAPJqi7f7jVVz2/O5tsCrO
D0hLouSuZXlH5FihKLgN5FSoB0X7yOAgEzmXPcJ970clBGgME9+9rInjhZxzjTAr
lWuJl6Ay/LRLKHkjM2wflMqAdz1ej7Ksnijwhu37CgFjkkXHAfJ1fMGrCrca91LS
RQFpa1t0DIQxfww2G24KCp5pP5XojxacktdOBnNOmJLF8PdZ7NJRFuTonEyS2bsQ
OpevasdHUfheOjzUkDDO4ehFKzFm5A==
=mnv+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f302f99b-0ca6-407d-a986-b71b4fc1be5f',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+P6wg2mLopjsycFeAOsnj5np1iAb0dEMZGfLMyWF2NH7g
bNBSKgZg/EG8EE1hWCc3aZg6BzhnvIr8JRNGaEb1XtQwT5SoSTeqDkcpK8f50U0T
DX1WWcTBuZFOchO9Xnq4EhUe4kH+vsLeIIr9PodNgtnGcDu1f4Cs67DeDkWtjcg+
+csKHoCberv+7OAWjVZipe3evkyNIxkDhYM/K9sjvhzDCUQ+J3y3E8FBT0v7Zhd1
2yyW5TZ/+DLNn8xTiWR9o/oS8j8YOFOCf/r/J2ucAkQbD+hE9QanZwyelpV72rbb
2AQhcRUhB/Yul8tFFUpioTVAr+UqqqF5CWoW8KXxutJFATNM6pFenjS52Gik30/O
2WiOyqvho9nyI6uCWhnvfMzYVzXfGxr+gWXSnXN2I4R7LE0oacyPkFQpyvRjRscw
PXTZaNP0
=iPBp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
