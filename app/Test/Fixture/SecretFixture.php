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
			'id' => '02fa792e-371f-4517-a488-7469c5ef2e7a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+O/4v6eQnheVURjM9MZ5iZyEu9Ra86kQq9GZRmCDH8kWA
f4Fn5HcCn2zZQvkyfCSDUzr9HCrpmBD54lFc4w25OGYwb5Se1QW1HLItcCv+nLaD
JXShG044lBrENREbm9cpbiTEwK8T1xEX/+ghIhJ8kyuUHL30nl1hvlJJKnjXKTAh
CvQ6Fr1pItsqeVuTKs5xRRfX8ayIz6fXyj2CpDapX6MiVcFeAHhLnqBQSHG2mImM
8vvN38YP6/wkJhPY/vCfBjrerAUIs19Yo/dxs7augxi7l8oYB/zHNR8n4icbL8uJ
n/XCsf7aj0XmJrJjrzKqDWts4ZshOH5KUiEqcyMPnsTX1mpDaPDnTgYbHlaaBjDz
3pgPl9He0K3GeOaZkNFCxr9QPLdvOzXn/MbFOh7pAEmJNTXPdksf4Y2Zc9blH6/r
4/hrvIIzFFA3vdT7jVtr6hFLmYnOKGZ+42uCHD0TdUWSkCBjJaMbRiOE7fISNyTg
dzP50z3ZhuDoBCRBVOtB/ZEujG9HEEkfJhc5EU0kqpgBV7UFSiETkS/YwhgZ3DoH
D8pFnTzMGU+gYmlpQWVkh2vcjPezdk13IsQZz5F196/a9unN8F2bKJm3+HP0wLW8
E5PVwCerrHzfYAJ+9kXFkF3QLc7ZdFC8LIrWpx+/L564OpiCOaTXNU4zhYpokNXS
QwEcRA9KT9eNwShcTJMcp1JDjozN0+kUSnkdoOLs/1MiPvYhurdBUdavcaBWaNna
y+BM0b0pZKBfWcUj8LKQxo9y5Io=
=aLFx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '074907d2-86d4-45aa-a6da-6e374340dae6',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAkBIPrU22zAbC8YcbOvQfca7m8wcCaQwLfkq8cFDB5cZJ
SX4RkxBwys4PYcZFaNnU9OzjJ0mAdJqprXCHoXrq5g2F+tANGzezZ32vqakjxvpY
53FDWmeX3oRmzEDSIFvN2TLQE150tzEdOHhXQvtCTUADpsBzA9D3LCdwyIgJSV9z
1nChyjytItTSebgS2cnUdfMr/6XeJ7A+naSVK14bIA65VkdHCMzmZDYnNd6wbP5u
iwUEkXfnHUDuX1xkPixOQ6we98CPP6sHj7P2ASXPR5t6878E8pciwO3Om1+lFuyb
aBFUgaFk6oDmz/HPSH2WfAPWQKW9S0xBdftQJiTTwcYErrHOvWJMHG+u4bWlpH9i
brKKNbHalKdd13AJzPSoOHSD2XGD64mzeRqRviKKwgoUbj+P3rPWJkPMwPiR3wrD
uiiQijMdNpa9UKdH3UW8e6xttSxRdVweWKW8DFAuCi9u19OVc8v8agpAwjNx57zF
EyuG/Uz7ImukUz5T7mrlktefG6S4qKXC7YxJ+d3PVYyfbJPWHNBOg10SbmUruX0I
XfisV6qLU47so/+ZnTMMwnCtaHl5QsnPU7TcnQV9j6hKNYg9PLILCEgZE4FHmKCR
hRfLCcfJfA1kWQy4YDpLhUOmYG8P/wfwA+W2X8sLLH6fnoUUP6jo8GiaINQr/avS
QQHd/fM8rdeHLeECtED5QP5Quv+/bCwA9o4MjrwgFd3bwoefg7/ryGhCYJypzwcu
1C6KaZuBrA3Jo/EO1Cig5WcQ
=B42z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0c8ba445-ef95-4252-a369-2fbba6fc268e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+KfLw3BZl/Z1d4XPnLLXGpdHnRrvrb2wAj+6luNoXpzYP
tB9/VnEjqbqFrNnD6oaWg2ikxetXWPX3YWogfUXsar9MInjMZAHhCRHZ2/ZsjVEE
z98I5GiEBMHHAmQh3J/b1mMdG1730+uEY9ZkrArCnuQxldGfANoc9yYCX+RVHLAC
Xk1A98RJ56my1gfCw4MRTo9CDl2RUKgtYR/kIa2lKL+th18WJLwazy/ZMAEXAAJK
FBbdBiDOU30MCHxNVrOr6It0lffYCJkBgU2sppudLU2QLlwvkGIzL2kLIBhyaWGz
fzMbG0CDL97JXFw8shYb+l3y9hPKmXECdLBXsOgIQBEjmA3n/RuKhSQqAzjQ4fUo
8KNZY5OqUpw+4ECQ8izeS/UA2lr809HEkg5dlW3/Dp0Bv9XACNPPI33zbdj1pgX2
aoNvgFJloTTyh0Ots5GbxDPEVEy6hk1O+u6KPzpWRiLnQB85g7qUb2MilpbIeNxx
AvjCLNhgLWE8AIUB8k0qhsg3O52HbqEV7ICB8mOaobtADLFtilwUQUdleMZwjfJy
YxlB7J+3Tz5m0fHbcDa8RNPlIkGK45LeKntc+7bONeX1jEM9pBOBWHf4hQdziSxw
idfvg7mogI88oVLsZrvmyT1/+Rg6XaTfkSD8jCx7xwpXkiiLbSpulGUUGYXebTnS
QQGxDwOQeu2rH2VtP+R8IkZEMMzbz+rzBmrzVHQxhLdf54vSGr6REI1D+VLiDsiU
7TrgiZThhaOyAhIsqNV8vymA
=MjRx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0e9b51ff-fbad-46c8-ad5e-7ffe142c5892',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAlUFAYgoCZuB2jAudeWs3qs0g77TpVgEu0bk8z9orfegK
hIuayCaJO++CCcFjqgK5erLBRFLcn2C+HMxRWRB8P0nsjv+SrlFN/zWuLuV3dLhT
SqnKap2RYygKAVa2bIbjLGmj+i1bF8+z23/K5EE5krXy3oeIqIuu8I+Oox0XRxKw
yxEYFll1qMdoaD8V70OofHnKYttuwnwQrg7ObQ073HsxJYqkQYAonYGoSKwutfgu
9UtVsxU3EP2Ng6agbgAKy+ex99ww62xomqpUAag4eyK+9mR/Y+vn8Xg/ga4fdD0r
lvojNKjoQ1Y/BqyHqREVqkQQPEHNpduwDS9JUxIaiNN/GcgDvLKT/rq6nSU/azm7
smm68Ki4hLD7jmfBPy7cUo6hC0DOuZ4w0XTIknlbXQLtU4PLBiIM1aXSnZLvIMaQ
JmmKt+HyIc/VN65haIWXupHiFT3JYtIvGmZDD/VAlek0yFJL1VeCONYb6uuSMAbk
2unDqNPx6tyQjyBuZyCpveusXcME45kYbCCdPzMoQELuRMC4XdsLlyMYcE1wzHm4
LSXgG+Nsanle5XwbmaFQsq84mTOby1hK/nJTrVD4OJUqC1AOq3SlLkwkuY+XOe2U
0sT3BIWrL6cY0QNyZGJEuKntONtBcTYXkNto87bs6TEbmxkGTKyOdC/5mGwS1hfS
QwGGG1ZYAVfikRklxM9Tf9KoMeejWB8D2WhEtBoKgybLHBdp19PezkKdlV+Omy2/
O0qfkv7OarFqParATwIRp1PE7KY=
=cFoX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0ef57e3c-cd56-4e9b-a4b8-3c807520c0a9',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//T36Rw2/ChHowat75U7rubqPkKlfYkC45aIJSCamS8V3t
nALR+MvcgHicqPId3wbbAoOwjJ005rXc+84htyxOgdfiNF5rj2OuLwI75xedIAZ/
4zNEWLta+fy8ky2hg6bTy4CsX3+WQyFxjxSgp8VTOjAvWjmS4vluEdeE3MiO78DU
HgRV35D+aar83mlRV0uge0WSbcrHx2sV/zqOERo3qXLdjy4AqTyUHZU9ebdB9LPu
8VRZ1Ce6JKqxpydGp8Fbyulhu/S01ANHT/afmL4NGRvFnK6g4uMDUlQgXNkNbtYT
VieZHLA32LPOBS6ayMM8Wvzhc91god/m9et0Z/+nz7IaCr8t7ct/4GU8cnqOPaTm
FBCk3pjzf/CDoCPRXeIY01XOEx5AiFgsUgXT1TxaQdRa6HKy9835WxrfPHL+w7nq
tWZv3HKqAw82Gfm0kJyzzb2+u5PW3lHMChfxiWaelhcGMK2Mpk8GrTkN0pNXjkEl
GLPZTL4vuTl74R/rPrXUIUfwM2zhEHF4MegtjpTeAcMmPwvKrE8FyGYcuigiCOCC
RTuHIzbkV077KbvouGQvrZTBWaF+3AgqR5ON84NvzheURvsep4oV1gbzrv5sPAB9
hina0dIub/6IejVt1XF8hSv4yodFgsA/ME7ZeTcdhfJYwrXT1BNsJPiqEz6pZOfS
QAEY4PYlDybIhzMtNGHI1JYzLcboAEJ1Basv04B7DCVG3rFtPIPREctLY63s2RMf
V/lFz/HH+4+Pu1rJMIJ6RTQ=
=DCZM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '11f11eac-e18c-4118-a167-7f81a986aa4e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+PJkOzScOTmghB/M63kOeR/6ozzhIYMTkggLyd6w4Xnqt
ZAZpcnCWBxegdDKMzK9ND+FDatDq5mdv1K777orzbB6KO7Labwzw67oR0P8U1hLA
5nbczCmLd1nOdxzWv+UIPFNUwNuqkAt9Z57Kws8BZheljr//QGOEAImXniwPKrMB
1Twha8JWXJmvqsYDl3mozYuEnElf4niXYxDIIqEFPx5P9alyFd78SrbBqmyzrN+R
kac3qyALeJD62+PyK5oIq2Q8ITwDqvRScK5M/fbvF71lqKsDzbBL3fzGFclVtwIB
9NqS3w5HLUrDH4hC6QHoCPmGVpbJ/LFNsjPmci2plGMhR50pySLVsAAAePOaCZwN
mAnOh/3sAFi6uTHtNZ7GGJQWsuuDJPs5TMgVsQ+SJ+H9XeAjdB/0SogPHLQB7E/T
Ceplm00z07cAksJ6w5UMvnxeVax1PXZLbhQZIKa9DETfJHUJakQnVZ1cGZH86BxS
Xzzv4weMr/jO7Pyb6R16mzsYaAkDbpe2yXDSU2QkxdZRTJObwhPWmmANNyvH77PY
MhylxaRmHQXUe2ULDVJmAxgK2IUj1zAZr68XHTKeURg8TDFFI+zSru5yK2OB9WBn
c2MkEneYmNU/lOWrpyg0/4cVSwaXXpIRJvZSLwdAyY54IbT/BnelOkp3jlKUTmXS
QAHaJNLTM+lXfd20ahAThW1ddtyOH1CzwefHs0G+8b/oPpn8Np82AmP0AjdWooBn
GnC9gjXXniL6LLw7d0hXFAo=
=Wkcb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '13d16391-9a97-42ab-ade4-9aaf1c3ae48f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA60nD6RmYsUvN/lGgGyq/g3rWpGBlTSqpaXZIwwbIi59b
Jj1Dl9NtES2MMZhGB0wrPBQxIJTAb+XELpY8ScYejlH/tfDoJaP2ST9BtEpvpsTX
e5Jx7TlOssbE7cymXzc9xob2rYFjLUOO/XefGwajJek1WqUb6/GCht5laSmE62g4
bMz26fmWMIOCe900y78SFbsFL4elX/dUZwvCWaK3LbHtHbxv0pZV2tsoTsj1CVjz
qu3nrY2F0eHf7KDYTJ0mSMyh2MDugwOhnOM9NapOs6dSP7/+/yxsBMukPSsN2NPZ
tYFYjXmEMWuqcrrsUdGyHduribyR7HDPsE2r1uYd9+hqApWtdAoj5UXVRoa3QIRf
QzPBiYJcnu8+JfibSQHmH1jGLnRH954bgdmYMXL8x+9rCGuvRClKIAPqeoNa//r/
RexxQmrzjAYGh2T79l1ThHBPj9Dx7KYNUvtOaP4L/ECT3DcAKBu7rhzceGGQogUQ
/ReZXBOCbRCKFJ5LKT01Sp/dWMt1A3XrpM680qUVzQhrw8Dg5ka1souTo0W2Yv/G
KZlfKvF99IsBPAZuBapbTFJQdhaz1I3ob/IgMR3DIY/NNv+Q7pimE8MQ3HKNujiS
IWAqIPLHxwtNI6r+5zG3ogaWXH2nbL4D4dN4qXj2y9uXDQ5meMM9YUsnDv/3ajjS
QwGUlf8zFXSo0mG8j8/qZdgxBXTi45DPsIqS51pk0hYEVF5o8BOftUASYC6RPM9D
aXITGZDvvCXfkRvyKOxVn4wghbY=
=rO43
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15786241-1ab0-4c2f-ab8a-56b120227d20',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAv2Hi/JT4CDVevRl78DcNYnO3hc/+j715X/jCY6dkG424
VuvY1jNYcmAyrSYDm+0rFUWGtv5fCI18e2HMStKbmgYaG55ttxl6WmQ6U7pUzTxZ
B3oXa7oF+uFE5TEHvDvvGcPcezuVMe74t+2M9H/bDEQ4CUmd7WwSy7NLxptAZLqJ
6oBNjYc0eSDap6MnjWqFcHa3iiSX8RndLlVzasQbGnw83sJvHXA7pfYSVbQgkj82
SH7IjqyIVsKIXnuGxKT1NdZjsEj388TxI687mu61UXcK1aVnYaSq8kZgeSWKpUlr
KsEl8e69G9l5JKvG0BunWQdXp9dE+SBBbO8/6Mpu0qLIfo03KhfZ9Vmbs0Ut77Vk
cjthg6ntFLATr6QKStc/QnYCvvomLaQk9Km2TrDDsHqLwWmomRTQj+Rip+kXsXql
E9gkXvOIIpB14pXsT1xBdviok4NQ2SlQwH1IjdXf3cQp1xAiSXmOoMk0i42M0YJc
ljg8LJjF4ygXBLYNdUlKWoDvMfDHc9SLBhz5hP8Jtnkwo10+CVPzJeidEKho7jiO
VBhhtdODvML9Umm7XLRA2NRV5WYFeVwtlJ6T5MRVL+N/7VMpEjregTQ0VsYsoZWU
Td+QdvUrL1Q5kGXq9YqHYd9eFtLXddAqh4cyaMdxmO6/vjSz1cMG2e+GsRHD+TTS
PgFNIyFuld+lb1gX05PgpHcJp3g4PRMPrRJx0WVt71WzBwdYuoEUtJ6bxi2NW16B
ht9Q2uztmExCOluAFJ7R
=lXA3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '16d5de10-9871-44f1-aa4a-9d2b3534c93b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAhENXxBRaulOjtiMxzg9mQUnjLzc8FlPIeUUCkoYjlpZs
MOGJFPhwry8ZWcWT0nHdrz3cVYdqDdGFMTN/vGpZYSX9Z2um8FtwbkYaIOmD0hCS
bdediRblwzDgjhl3uY27QjcO5jYRxzWHnWJaOB7ZNZYeS94HU+9Dcajz+yXkWMCj
ZluC/Snobo9Poph3QSZ3XB7aeIHfE5+1gJidskzLamxjyjFzgzOytVgaWrexNyDw
mlKrWdi1Pw8sqy0rNNn+yH5kuiN6SV5KWTlLTN0zwIotZf1JzacMYsUqE6xeOkix
8I3vRbZt3fUUzoSBVrtIl7fudSSvq4gu6j6jNz0wnbSUExSGN1fKEzXaSTVLyGDn
XMR3JGVTGyCD8lauq0pSzxfXhBy8tbmK6N2VQZYif5+k6G6U6TmZJoqrtj7DDNaY
eKX/xDWf82uJLMYEC53rJPeMFCLIQaxlPHdoYT2/sIYTXjj9iSqvK8Lzvpb4aYw0
JmWvO8NpfuOb2SkJPNo3cIkws2AfhWARREi3Lfzsgrj8XfBVSuZZ3T5DxIdhJ54x
heqmk1bcdTCuXxRZy57/MQt8wrH7n9zHZKHo4J8j4DbYqKhVVT/uSg1we3cVnNma
Leb5LQw5KMAXptO93Jh7gMwHsBU9fi6CIXWLBqOdlzQ6IhEt9OnA4MuK79XY4qDS
QQEKhdibzdgl9CSUkkTL4/yT0M5YA9BKsCjn0REtcY0Ms/rbFxHk5Z8xL8uWDddB
fVB9TbhLQD+N5Ji0uHaeF1ds
=vSuX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1be64ff5-fc8f-4e7f-a992-79ca32bac5c6',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAvD1WivEmJFJsUEsuYTSqgAhpkSF3yyVie4BBIqcn6xz1
OVcbUsOZ68Dn8zb3MQ/QTwahNtLlZNEHu2+MTvYQ8BLN07oqZeyD7J3HSpzYO/8b
W23LBtRLXacEBM+plswaRWibxyJ8RUKVNpvMkJo4Mx5XRA+jO6Ck5s37g/4FCyBO
pEKpk8nN+msAHA//icbuJvtJLo28Z3P3aL6MUowoaLS1v5pGOl09/MyLMCqRUwsV
2SuYRssP7N7olfG9zX0KwFXPLsi8NBNpbDdutijKiyrK0lU0JCsLlKBE/v0MT0co
IbLaYdpMAEYnVShh0Cj3OkRi2aQjObQ5va0YcOXNN0malNtqhO37Z8F2A7G9NkXB
XoSXcfp7NtN0g/tpXzGABiocI42sXWFFJZ5RYLLUwMa4iflTX0kddiXfaUR8c1Iv
/mXVec0aLkq2MbRJwIol0jBm3H56LgIpdQ6VsdoBd3Hl+Is5+KXPbpvgA7N15nE5
KjTy+4k4gBZRwNmH6G0Ox/tRl0ondwbiJlZlIX0iztxJUQ20GJq5iSOx7HTcjJmb
sW5K6EcNrOm9HGCzkISbFr7JzM3eohbKRSEYs4BvqBhW2tRmTSn36z8FzRGe6GWu
N4adK5Ix8V3BOgcB8MNQjcxZC7CxI9PBxxGjQsJObN8QGZm8Q9v16OLUWoNIMu7S
QQE17Bh+22HPCH+58zJKebdiIEoPn53kbLzbtI07MQtNRA2Ra5oE91uNHqNCUt3L
SPwfaYjAj1jsKB9kDUEh6kdY
=s9/3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '23b2bbc7-766d-4ceb-a309-dc464ea422eb',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAjOjkWEor8c1Iougg/PtgFtSuEcA7c7m66TpZBJ9UBmIJ
Y4wNETVY/RU1kVMtpv77tTDtgfD/VnwUZbCRwXq9En7vCyIBecd8LoSW2aadtunQ
58liznRSGYsxcbSQqVzPLIPNHb2IIdX43RThXSL4wNVhMgkc3N49kgugLdJBBsI9
3NjWr8uso4Y8x+/GTVzG/ziEmvmzz5F/dqjzGVHVzv8IU0vuJ+wzDhgRxPkp3LkA
wBa4ZHMcA2VKke5kipqueD3tyb3pCimTlEZNGETnJO9KEvEgy8RD3QpYlmi4AoCE
pFywikG2ZOcuvLy8lWiowMUl57oXZbN6TfchK1T82tI+Af5zGo/HLp0p6Qo9TAnM
oD6i6bRF4FAKJ7/xgumWhfUfg9+7w3vaU4xUToZelDIWrPfOEd3XM1QF1W+AiE4=
=e9EZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '30884ea6-9234-4cca-a549-5976663ad4be',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/7BbqrPyzLAx1bAf6rgNoMTouMbwcrr8OqRxt+EzipBkY6
XwvlOnDqVauoJxRDSM3Owl3nbbZlNIBCokGjxh9g07SA1di8GfH73QZcCEMZE81F
SWK1heAi8ItNGD7P2Ohx8u/unlrMyocRPkxhRVTA5ZhwzFPDfNbXNc7l593/Sk37
v6d320v04AViHkTZN6nH1xdP7lsM9WXV8wvIQBSDOi1jgjbtqUiBXOkI8ZwnLQVA
KZAxe7pGHlGWJie8ZRSJI27+gPBz89CikGcWiYLrQsC5BUCYAYFr1+dNf+/t5G9Q
+Fu15yWiZR3RKNz8Gein+rE10vHTbSZ5C32absqENieogDK7lt6ylv5HDo3e2BKp
GJujUmJUhHVC22q4tu/iTr/gkbYM1qzB3zSr0tkLGjNfCV3aF85b0gZkcDyOUr6l
rRNyb1xFPe53t7yfKd+UbUP0N1n6C3iBoltvWBajuXI3dBBBj8uhY9DWJRHhM/wt
zdOhM39DbzcZ+Hfk4d6sH7YvuxljjQ+92WW+I7ZQfdpJS8p5rcN0gpNL4eATUz3m
cwFTggqk5xR6Ys+xMPGP6QEaz4aDiw+eJ7V3ws2Pn28oEBSsVIAuY81sn9m3FD/K
Vj6howETGBYKFIAwtnqEYFwKD9E93k4nBaYxi5DR9tQbwQDsmsqY7y4j1Qw5D9TS
RAHhRz0sIdzJdQuThzXe+bAjMOcqcJo9Ft/qA/dq9f50lv2goRFlKPCLeHSlQ79q
6MR29SKWD5r7pCgNlM2cCMrCq0Jy
=cfLg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3907f8f7-c1b8-49fe-a660-2651726e0359',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/6AnlRPWrfKxWL7e0Xx+zTPCODzwCpSH5YjEC8jmSayR7z
jtkbzj2syFNT10o8W60AMWh/H29n+mr97BTiS2ARa2uf5n/OBK67XyXeQKC5WMEK
DHU2cDzBuGVVkFrK/U0QXUNls3L0S3FaUtlwGZyaaKsikfhxob43pqHlE6TJVgpl
i0O8e06fGIbJ+3b87rgS/SzjbKUsPYDUOsdCJsvCNXc79x/b55Ad9HRP8I2RWeXD
5ToTEVYrU/oHXMic1PAcDJoJkkRoZ0LaYBiOPrOjtuDn3cB1YcYhOJJecGXCJ9c4
CdCUIvcu1dIiTITOe7WPnsj1BTRrZGB0vD8uTUkDDpYmPIHTyd46fdN+XQMNbWfa
SJ4zW5XyC05kUFS81sgB8lWOjEaEb4/XC9AQAQFd+GGcrbgW8a87sGG0mZUqOmSm
WnSSEruOpGnOCOOmBxhM3rwj0bkFxsn+zz3MsaEMiE4z9SdVZM8J9hmUQqZYKl6r
xabp8U/oYC6SOThMWwDkmgCH6v/ZS/ndQuUBGMMI5rkbX7EqbmjCpRZZxiry+QtU
lR/PsVXadouWMQwvFQsHg3HjcDY+tUc6AV934Hj6mz+7kpEzY9boSLit5S/M4cxG
dBk0770d9700gEGgiy7JueZBH6VWzAowlHpX1Z6IeWPXdhFPf0iAahXDI31aAcXS
QQErGR4yRIw0DFfrrvQY+Jb/TIDSxaP5sClBFY/2H2UfOWshGjVtYXejEMmlPyVv
DWFdZXkGQXukUlD+SQwJcNgy
=icav
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '498d8377-7500-4ec0-a7e4-3a017643ed63',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/8CSb/3JrcJEVVt6K185+QEud+qkvqRr9B908R7ZyfhB7T
RK8aUhAzvxBa86Mrrk8+949ZdTumGbgrwtvwCfxPcjNBVeY0Wg4BQA1y5PRIWsce
bmlNleZZEbY1kcsHprrbNiXQebTxUU0j39cSl0+Joku/vmkwzPaFkOhgtdClu+S+
nOgxyNfI9HjQTLv/XNxT/kBo7T2bGlpU5TPsfWUgthOBGLYw4RRRioF6m8T2oUED
W5g89BZl9+hlOzv+Q8Smvfg88BW85qNw3B9l+dP1+H/Bx8X7eEmW63T5TJVI3NXa
4o8M2UMFJXyTfUpgbk31kmxKorgXfHE2De+BP2CDgdHBY93Cw4Kuklad2o+lVrGP
STBHCecfaudH3UMhN7UtQLf6qgsNVA3+ztm6ZVURUD11SGrmcm6ktzb2MYIsR6cW
qsJzcBuS1RhvyTjQLedDfEdFC984D66epbAG/UgQIXpVxywmgXLPVk5MxP/3ldg4
GTfWBfsYG/mX0pZKwpLksj5fydGSrLPNbIbuuU+Wge3R4cvdnVXPiuKh4o7uR1n9
ETQMcyvzY2O49N/5CfeLwS8zSBn+7ExDAiJooHChwpzRc1fY6vq44oqETABGSs6L
sA8J8rFM+gARIK1IlRB6r9S+eUjb9bgjHdyYZGCTRSAr/cKKFUsZeNdNydmLRC7S
QQE5Zv/2Or95WFIRRMlX/4xsFqBVak0XFtuM9fICIrcerzoYXDZKVABpwOiRpUwj
1S2iCGE9/wyMdUmGvlMP16Tx
=jNmR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4cb14a95-0e37-41da-a42c-ba412cdd5369',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//SeQk05Nt6m2G5M+YxG9UMqXkvou84fe3BJgwFNYOnK1+
n6bj8n0cKb1OrSqustKRXJZIWN+szmXyeLy8pDK3WlyeIlIg/8XSJ1y84z2CyaQV
nq6lj7D062fjGDWLBH5ApOUwjmvIfj1KeVBS84I8z/fF9CLLwTW6uE+Dz8AHiJZg
NYn0FeB5XAknophYlaiCwloMFD6Y2486nrg+TVS4CEIyxVbfSQ9rwvBm2kVLSn9H
wH7p4M/dGTbCORAeCb3FuPl6soxldRaPT0mY036aexMpmwF26hjJrauDgtL9GQ5b
pZod2wfbvPhupEnCktthLwjGbN4Qv2grcaDJhJvQ+mEpp34WI2itiLdigh23JWQp
wqRcLp0lv7CItagFxuOpttWGrDb6MZ5uQddTv3bg2oKAOn+WUUIQHqkkjXlTJRIz
BR3hfh9N2KjBjegPko23JTumLLxD2REDIlb/BPWoESpP3b/vy0VSuMfPYz36udzS
gW5jFYA6glGkOLV2jDb4MlkjCt6jEvJK5jSmbmSKir4Egu3UQcBC3uZfnCkVM1Cq
P1+sBssBEHzBrI7QjJMxxtewgDlpp18YAc7USAdmAZDZAQop1LvZTZByBrTj1c2W
SHi6PhR6oZ7UimPiDfytNOG4y6jmM+dFG8MzPyF19TqIgzfufIWlbqawlfTQygbS
QwEM4tU4G8t9xKA5TfkU38LppRZEcUTcSZeiyp36UIikvbonApdbG2+KRLDtHGZE
KgIufOHsWGRv+Q98GifGiN/crlg=
=v0jV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '565a8207-7186-46ea-a37f-d65aa4e172e8',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/8D1obs9bfiTxLj5axrsepwytiVXb6SncXiD7kJ5ekXtIb
L++kPEMwTnkjYAd6NVb2+MLODXccgaXLj9kGepQEWyk7FKPYSWLekzG/eAw7xY0P
+qRN20NLYHE09r6wG7uY1pm0yMt6SwuEvqoyjYsUxKDG9o+YOqseqW271RFuNJq4
5DVyj+zPSAFTx4mB4U6aBAxRebss766LHxIWixs10VsNErmpCFUvO7FTL9vbwaEa
bycz1Ta4RZBdWhc89xaGDQulLvnmDiyoLu6vd59QTOxWrL7exap0kCHVS+ULlVUX
j0PRMDRgKXZai9cAER7VBBweFC1HwzLaHNGo5lHnzt3JhSLjB50lnQnXyxKz5RoJ
Z7Ws1WLmTzEWyWtIQbPYdVwM9a62yh20PY+aMhkhCZt58+gluA7HK+467yXD/y6A
4kmc9063vcxiZUOxsx1V5KpVr7RyS0nT/slRr67mddA2eriT4vLDc0c51KNGBzSP
qIHcSGlk9K2Q7WlasZpxjnNJT9VYMFwFGZXZuaUupWEZbwDKu+kEAieZx3TRw9Ru
HEATHe8SN8P2y167uuHX34fOffgN/I2fMSOedrovkd3EelmKMeMw80DGGwM0MlTF
1ctSSQxhan0dt7XkG3p5/WPyi8TK7174bgoBCuuD2PiM9JX2/zsEMJLeMmKXkSfS
QgELDqDFqUhgGOY7nB/9npC2SktRPTEJPv+8TBH8qTIkIWZFMEJs8BunSXZlQspx
aw/o3uWTm6HKH5ImzzMj1yXVsQ==
=l/CJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5d5d4a2a-2ef2-4505-a0fd-4a0ce69ed2d1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//RfclRmV4fdAKxQcv0NIy6aXbe1sOxIrjDr8NqbRvHFj4
u/qu/FkdQKF2PTzckY1ercz6ZsORFf4gqmNyel4Wa0qYHYugrhVNE6G5vFL3yl9q
TNB7y7+yJgvC6zW/fxzVIBZY2fpiUB4BCpTznndtQLg0Z1kVzHOk3B7IAWy4wE8s
EStU/M0RVteJNFSyri87Psju5nQHYELQfSWEvZyzs6zrkTYMZtkzeQXH0Jl+dEyt
aCVs8cnoAvRkRyitSsRL8V9w/hWKnOtXfZihfv+QEr/f14TYeOUn2WnQLnahzBxO
mwt04uiby49HZbkhk/k2Cv0T6ssFEcU6422csigNwhpegllxSIFFluHdVJHExuF3
wgxp7Lx3Eb0pofYZswgF5/TeB7t+fbiH7gfuH2zGXSPSIhh1ZkNc+qiuBvaX3wqg
0m/6OTLQIxNFImT95WCg0Dat+iMf2l9uTa1mC5Yhl75jeT3Uvgxo6xiPSu4tELIX
OZ/imUNSUZ76WKC/oF+fnEEYm2epowTmvTdvVLfcDy3GY9UiM+OJ5/q4oWI7OJVT
GiCPRbW4HhsJV0HvXTh86aQD3/mv42G9mGa7zBjv3PMnyKSwkGwW6CNqYfcyu3BE
4H522dvQD22iBNc5eESgg68Yv+wmI/HEXgUq55jaM9yxvGsUWDCe+OnwAauRk9fS
QQFZfeWJ4YV87OGQjPpA7VkmrrTkaC/O2dAMa0iEDFMNXSIeftDDg7S6zSjN5Y9u
Fh3OwWPjjd0QffimxTC7uerz
=Q4rR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5d8f87a8-7bbe-4820-a07f-963f3854bc21',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8DQHVYhtBPSA9vNlQOgBBDdqGjx8OmOuGMRaSA8xSZz7N
kuoWk8K779RvyNH6zFHxpR25c41Kxx+M2m75ioKZJHKbiduL7zJqC+DfpEoaviSu
9Q4IA/Gxno7torMOs1wTb0t/ogx3rP/+PiG8Qr5ETAg17KN9NTbMUbaCO48VbBZb
CFCd4VTBmQPqKAqY00AogPdkHgZvJ7TKP9/SXjUPq9YGOTUspIYKyxF9uSowLz0v
/bUoN7GUglPn8PEh19w7WJ8EZ6SNByY30cqcbevebqcp0+5urlbOvzsoRPA1oyj6
UIuoNw/Nx5cVgM7QL2rZj16YHIw1Xw/V/GWYRZkgkNXNNeKwIW9DKmFtdVWnSd3Y
RvMjHQxitLB6iC1LtImz1nuNEIBimlbjvmmnHEbQ/kk0h9wlY1e7fRUnv/wsaGWz
ms19eUrewny5qXYN40wl6Yc0+h0eXR302MEqvH/2/TqmIHFaUpqZEGIYsrG0JQsz
0WYMTohcYOUuiZI+rDa2h0C0HBwoomrOah+qjqoH0ovMl2HiGXepqPWr7HDmtSX4
xwl14HqI2voyhYtrF0bWNuE07rlVTHFMb8A9bx5LrTNp8AZ7gJDhWgZwcTyxPa5K
TaAEF6otFouwG1nI1UrcCERbNrinxmsa5/ePbsWoYTxyFomEcFaBZyE/cZJmccXS
QwEp1Cpq9MyUBQ9SRjh8hFcsfgxYDMd2ofldFPV+c4nldV3qdGpjuw1UuLH4AoYX
vT4iKD1CI8dN5Yg+9QS9+DJ74jo=
=AZBp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5e951b62-9c2e-4803-a69e-4b7d29e84462',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+Nnk9nMvOqukg1tdqZGhZZGJixrRctCXhfVooWNX1EjD2
vVvQa5TO83GCl943v5g7E3JZB9hqJV6PV1lyES3wHGBLu3PX/JhYbi7yIm5u9ZHj
8EcAle/hsuOP8YC/l9JSB181ze45OOwMA7yla0GLk1SzYz+zh4ezWd8zuZV2Vjzd
rSmD2GQ0iK3XM6ELtdnqFJFn4uGjPvqvNPx9yR1ayfo5ESqZoWbLfLYTDOO6aWrf
f3eAg9yaOE5PYayq0tkrs3jqQ1ZcYE8dh0xp+G+XV3WG51Nzo2JQyEPKf/Cl7nhj
m0pwcUG/ydO/5dP9ZjsbMFbIG48wGJa4FdYr349wOjJJs0dfvI4ijQBuOwo39cmp
75uFll9giemHttDtY6t2Py34Lkd2Yays3Rr5qhtF6hcIDd2XdPLUUmewwEBKgi5C
EYBRvVNLCINgKp27yjT3fqaGFVFK42i7M8Qvjg0g8itG73QEACDoBimNUedYKSDP
/SSZ76XlEROlp8pkav5tYMqfTsI67Yyi6IUnDtzlhfcQ67vG+zg3JIwU72Vbep9w
Pi9Ziha6piw2eehJgfVN0uCkiOdeVxZI/XlzmDOUtza8C6u65qQey0mCxrB2gCNP
6qP5b9W31FU5V/d07eKVEyif8JU8MkPAhQdJEkttjBFB0l2I+LJkQyFpSuKqkajS
QAG9J0aS65nPMPIBmgC5RB/bJfdSEKStB7BEFKkZKOUZGLUgM8v4GHLLi8qqHrIc
2hAE9A3/ZLXOb5rw22SyoNk=
=Fk0c
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6071ba20-f7b1-4796-a5c4-3216a0e948fe',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAl3uSuHYNccOcBGifyRTHJmrW6lR2TVxqz7ATgPffoCgu
FwxKNAyORYQ401sV9juhyzWyi1Gtck8fPMNSOIlvgvG0qo44hIwgwB1jdsXbhcld
SipGFIr9g53YWXzlDcMNY6jIwDgZDFZ8CnmAMTVEFWc0ERStzlMtXEBjHqAhuJwA
D7YT9mZAoOljD7I9vSWPvIrrrgQFTTab+ioR6g6mMAtMS4JQ+tHL+M9AM887GqGQ
/LE4kc4JOZNhB0FWjRey1pe1dC8rEKz/gjUBf1+uQbJdEEOlO6Ugb+/gShFhNo28
P65V7zOR8Y+iVvnW2CpP4MEWaTTkLs0Zk0Q54VnWjNJBARnty0soIoxaSjuSRMWy
J1szKW70/l2pSoByjmb6jXwrpmBJiptt9PRHazFAV/K+06UqhIEOBSdnGlbIpfgs
9Rs=
=Qk6W
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '665d95ba-18fb-4f9c-a320-9496118e9b02',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/8DHjGCC7kAkguYXAobuh7P9S2nd8RW/pkZ86IG87pqBt8
H+DGjUcOOWFsSGGuVcjpaWGAN+p1DrZi7PHjXWPk8s4fr/vgmd612lBnJlabYG77
TF9I3NCOd3fYj7UDb59M8rEgf24dHlcKfwcRKSX9qBVqUXgS2gPSj0ZWcuFRoENy
2L52kjWgQ3hNMKGSoZVHSJo5ES/L7iFAe4m44rvDFzPyjbh1vRLFoXhks/sfAWrS
jKJZoqFjEPOlH5lWMaeUgTdx3D2dZcCbc33i6Cu7wxeL+7kUM7x+iz75Mp9U2X9t
JXqjS03viAHbAf7zTzqY8OVxSNtsvZOx9OTmwM4SMURHUVwXlkYE0lSeIgSyMqxo
XjkgorK96PIUNdkV9vGQdWvSP5830dd01vRDybZdik3DES4Yh/uhgl65Aiar59hN
KjivvA9lBiX8ESwalP8Aw6gmPGgE4YNoWF+PY6memOMqM+fAkrVfGGCMPrKcux5Z
tL2pibAatfyYFeenRrqkCvd3HYF4NUFjt3HfBJKTADSwvlHOt1ed+pB8DXQRot+e
kkRztZsLwd7+OCz2Cib2qo8bMROdPI1KKegFmXmuNGHnAhc4aQFV9tZVML/qxQXM
nyuCUG5TPer2wNYFB2qxQ/y0KH57T/reJmmtAfH9hTSM5bZog+EeJ1v6wpQsZQ/S
PgGiLdyYYActki9/TvPSEdEg7kf4F0T7I4alHO6fNXoBJxeXjSHmOHabjophijdV
xuSwF6dCzEteqHV3ByRU
=9M+r
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '69b1f866-93d9-412d-a0b3-48447afb1931',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//dyDSw3vY9kJ7nVy5o9YNjM0BhJBNVczGY5GqpnQ5itJH
MAEnBfikBTphTYOI7GeGVVxV2dBqJH00ym4SLmmySIcz8Dj7BQ5T2sRogILz0PwW
7lGUChJUAI5Qk7C8uej1J9sv4cVbCkpazsUziAZiUEIT3NXCj6vGMK2u+lTBES/e
anc/S8NkAkIkrIcXhEzk+gckpoLYi8uw+3N8wDXd5IGGIK5hVxB5rj0FeMf4Mj3x
O7VUyOjP1V9uDEnrbucvlnypH2QOr0QPU8N3fqGquS/iU6PXPp15XFzcmbxOd7r1
OTK8sTpyq0FSoObcOxjWPiW9k1qd4zrqelNYbGYTistVRB3EGxNlbUgNvbRf1g0l
iFywsoLk3qmZ58E1HdaewUQKL6FX53ZKS1zSkJS6HYsLlZ67nLHUxkH0ATJBjTL4
b3xTUCh7NZqApLui0aMTOqcZQtNvDdq16TKuOOlSoEg1irqCcHM21WYH2KECRC/i
eufqzDUWjSPmBPnVOM+ALMQJcmC3Wjc1kOjXxuxwSu0/R0LG2TZr6u6yMbCVLQhS
D4kalWN6tOQklWueq2TZSGEoxNwjdcHUD3yCbIT+lFI8jxxsImvlSakqPuMXELQy
l6CFldBxMCFTS8RQYm9hpi17mg+bs91oNb5844U8bpiBPMbSjI+RCrA/vtMgW+XS
QAG1pmij8G3tmljvcbyBWcEaNyc64t9/8mE8zJndPk1/WorOGYiohqe1p0omCyiI
opdKSwx0xQ21OFnd4XPoSZk=
=hz0v
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '72146daa-6d72-44fb-a090-6cb27de7c35b',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/UJeQrx7eDFIF8erFk7gDuhDe0vgpZt8LIShr7cSjxQtH
BbYuEGYkYOg3HZ13tD90CMXuk4dF8k36G/U0k7EiHNIkxDulS39m1sT/jh95RsQq
GopyGog4/c6bz2vMyIDpiK3iaZ/o2kYKlLOB3HYeFlRTlI5cgmPj3KnLn5+NT68q
9VL42o6BhqdxjxjVrNgwRVPtbacB/yXS2d+oW296Gb5dA2G6JxiL8qRgKvYL05RS
o/RL3SWtYbZ/mqQ75FkKvjyUjyZv3SH2+QgGgdj4VxoIPGy8rAqx1R+/OliM8vcz
5+EZpgW14WF8TRg46MRu00kC5MM0uIeii4MxiaHPrdJDAVvehoSjbogKXzBXiUko
KCqYRIIFbPFna8jvpH9aquW6Fwz1ecziI3rQFUvJCt+C1HJqsnQYMRWWkcV+MprG
yGa8cQ==
=OFmF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '77aec519-92f3-47cb-a8de-365eca8ce757',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//SCHaqxGWn4e9zupjuUg5SEDfvq65kew0ZqdSuTXXpiZD
Q7E3nQlIdhSH5sDXRWczlI4k8IQ+CA4Ch5Xz1k1s8ceLGXRP0QfoCYDT5kZBw8F7
SIl+am49v059dqqQKYeg0wqQoKkIlMtoG+XAKSKJCd8lJuyt2V4E7dNJU/OeqCa2
ff1dTTCLY/8rNknu/JIJ6VH7JL3P+grr0tql+Aeu3KIdN9xARFejgMng1Lmcm2bH
FZ2Xoi0ScPBhE+wCEFGuWPV/SHdhjRGcg+brOpraJGRd39ST3vRZnzqcog9sPZ9Y
zXyJsy3l4zVZQU9bUpr8QB8R+9I1+gn3US7u+UO5xlb24CA16nYP+S98RPe9yz01
7sSRd5ZKzMX2b6wsUFDurvVW9+jwW7ND/NQVn/3L5fz4Qdlf1z2P2sK0DeRQabCO
nj+7x0Lp9gHoaVefR/6gSW2ge0XAwBzf9LJ+pxJx5fdRJ2RXZWeFOT1WB36HZ6Z7
MJQ8Nrc3ifGxsXHRolnrgmSgj8zl4P9bFzmeUSlGfGSLAn9QbRomVYzIfdKPuGPw
Ks8EIBuY9+N0c45F8Xt1r7zghrubnljYpwqUQr4fQJQNqaYjI9p4ZkL27KWIbvUd
catd3DC+gg+PEcKdoq0XgE/elWZ6Hq6Obj8zuFrIEUb48i681s+RGDTu0UfoaN3S
QwFrkeNcoVWcnRAIrOUbzrLmEot0MoQjALq94MSGUsxSnwwjV1GHkq1v5qHGVYb+
kIfGmI3KiQJfLm2Op8CAUkmmgDo=
=MdWA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7e289699-d8e9-431d-af5c-f98c67fcd30b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAkO4I/idTkL+ZiVXdWNxmSbmRy6D+2Vi9zmF3CARzaeED
6keJWgrofLC+5BfoFJykfT2+rGbs+M0wXPd3sfsaxoHVj58kLHrTjzLOneTGOxg+
vteAjElmc3n9EsJiBbemGd6WIedHCe14VoFzjpDEEu6fTexhv8/5tGusrxK7t6KL
IyS3gwcmjcQ9GjaGQD0iPZjYcjK+16vUjgCyk36NnbHMseSJSKE29th//E8bHAIJ
Ze/QBPxjALEBmsBO7yMHLu01cuQ+FkEprZCMK/snJIfd909hb4aMtk0Sim89KUYR
jPAE4SO9AQpNdoKP3+FXbtZWIe7DmKOZ/P2+oZQM9Rz6W5TDdalEqwAtPBvbgBfd
yA1b14vXMenJm3cPrd35IO+e8qOm/RSG60yjJVHq/QGLw04Ud3khPmhrpZW0pom2
OVC/Kwex+je+kT4CH0Wd4oD8/gmZfsgpzkkwlu55dqUN9aZymyKdglLfFHuiTXXY
HsOgO3RLGi3NBfGJEAkQbrsqZP2wMEYVTbjVCzEzUWZgbrP1/gDtcGEQXxCQjdUH
0pLOoKJcWKWnnGHngOWqgoUbG3vkhdCZKOtsA5jVEU26XQzVuLFS7mU+WOexrDQ0
3HOeMTVyzlNO7l0HIMAKEzJSkTdnPoVcsQ229xVMHo/+pjMo4PrbsSlO0psy7eXS
QQEhuhOK7mM6yOCznPzwDDYHFIITBTxlTOIQ4Eb07PvUElcJnUw3Voz4g7b54GqX
jw9CSdKtt4n4JEX5d2BKjDRC
=ZUJF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '819977ff-f831-4e62-aac0-b81bfc104868',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/7BIvmQCYRikUpYmVkhClIhKlqV8y3R9606ew0Bsm6Y3Od
y4vCjHr8wikZjsfFxpACGxMuwS1l+XgWpJyfTMj6BTvCuLxoqHrg6+NqnBxkCleD
IUMCk+zuTWZutX20Nq6aKnWbFHfna6qMP+Z0duKZpslZveV/tQ5y1t+ICVc9MUD9
wX9I3kaAVc/B6m4kUDrJg+3l5Z+RYKs5zpswMqwkuPNtLheO48eAombm9eib79i8
MPXntwxPdZq4TRJCkTJyEwErWfk2S75tVyYlBeSuAgTTkCzbh5yP6SWyySpfEMLG
X9VA7ce43wiSorqiMgplCdM0qI91FIGgM6y0haopEAzsprdAe+zr0ZHbkNgXnj29
obgP/iwUa4QGR+Y2xW6qlZfPLwpbSgM2UI+CUTltZLJJEFaRauxHKikHRm+3aE+x
1/UXY9uh5oFg0BfZd4iXkk8s0ftOaoEwCiMU94acgfuPRpiuTLuD5spKMVgH0P7L
hdCB3B9UT5O7IoWdLtWm0a74GDY2ytlT6bLWL3uaITJ6TF5QKKQmUvmLHgtBOo+9
eaUDLPblXS4hQ6TY8NDOq2LmRcMjMyv76PZfIfvlZ108M7DJ/SKnP8VmOL4QhseN
IScVAF7/+RNQ0KOT29txzzVU/4s4QCwLI8uiwp89I1mqIMcy0TaK3v4CrMtib/fS
QQHJAx97lvvMYjQiHlBrzsX2FpG8q/4RZ8XMn4byKTB1H5Fs92JlHyaUMFJdbzA/
i2WZxXsrpeA46lVdntX6OXA8
=NhiD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '87313ea7-b638-4e54-a328-a06c78724650',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA5CCEqte2P9W3jFCLQ5ezUOOLMWOWENU1i42kuTF9Qp/2
uknKwU1nrc4eChmPIeQkQlPTMvL+FiRU5iqVMJghtyqNHiKcF70cdWwWXFcMs7dX
QImcKDuKFetr8zK/wpUXekxMlMpIGWPqjEJi3TEoRiCAjl1YNuD+wO9U1sZyf/fc
0k4VF9iV3mIJStReJL/2nvwxmzjmclBx2xS0d9BUia4IfPjUeYWtp1zhT6+RoeU/
QnvA2IXsIzbY1m9qinMYCxjZTGtf+Wjqz84Utx9cnRGQ/K/YChkyp0o+bYsiYQpJ
Wp8WNppfYYfX4SQjZwDCk9YmwzoD44muql5QQTGBlGLnCk778s6xLX+rSWP1L3mX
cNoec5zedXybJD0OQvsyjW8rALNJTspRwwbIXPNiESsR3joI/zi7FQN3HEU9S6fo
qbyVaXOjcslXtRFJZpFS5b0Kb1EE5+duWdfDV24GklM1lVofjsfSIUON0Q9ArlrI
fg5Bii7j64qYE87DskiwNyQNyYY7ZBxBb4oRuuXfQz9mR+Kle8XeF/9vl9FvfI38
fp/TFQJA3JKQpcPSUGw7B8u6VPJWPLQnH7YAIZgg8r+KOXMDo6OZWuxWI0duiruq
MqqmLjJbiZdbArQwnPZRO01/8WtztdVWcAA7ak1n42NzV3pqpl3E18UzlIYlVcTS
QQGfJhS4BcfLJZkXVU/1isdqYpxIIhvPwHo+DJQDpDPRBfjQ2a4ZS/01/dwZPy31
lJrM2Bdvk7O4mpp+FupG1HWx
=jhWZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '889690dd-495f-4499-a181-1f347e2157a4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAt+J0XuncGX+evi+KSBP21++3JmkmXjZTFSUdChpqwxRm
HJM9Xyao8PUrh+Vz9C3MxfeYynleJMIH/CK0B7t7+J5pBTjwDl3Nj7om++N54sKv
7uGNeGR2Ivd21ZxKEOvpIGj4OPO8tG4sSrzlPc/ckThuilEMbnkJst4Qnixn+nqR
JRBFxQ7CViUJOZQv24PSDZJrTJc46ISJZLBrMnO8WXF/ju6zGntdNjwv04PhE9AL
mfmkG9Kwh7qCMEwx9Y84FbQFQdi3t1urtPu8XzrWuGC8iLQWJWUmu7JSvVgIiAvo
v86hh+qbY9cLAcuC5NmJN2APVMIBzdtIb9P7BjdLLAUUDq+E1YoXBINHHzTjyYPk
4BdnG8TqDJdcjhmLF8PfEsinuruRaAXbedvjXd3EPKcE9I0Em4qevT39DUO2UPVO
fEWkmTx7eqMe9ZcBBwNxeqsa214t73HR4qVABS0l+jJjsMFn0r/mDI/kSjzpTyEN
9lHmYHE8QtJfL3iyPY2Ye0K2kvw+DGO9zg/XdCDxQInq1I7JJClSg8eqxwEFBgFY
kVTg9DAmDGvhJ2ltgBFRk9qoTbJBvsTs1ytIFPJfLPDwomVChH9j92OmFG/ms7X/
XTedMc4TqTQC0LEx6BVe8UwJa+6dZlR7mT0yCb7txpU3Oik6tLSzgsumeX0E8EbS
QwHEVI6PTeDWagkldQAAd4/A/xSkQPZQSYYu6jwj4RX/zOF8xJTcfiPKvd2ZQcIw
cp+VpoIBdr7gy5Viphm/R10/y7o=
=ICmp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '912ad449-a7bd-4fa4-a515-70f35c97cabc',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9F0Xt6j/NvxJj2XRnTQnROiEq+6q+TLcFrr/doeqN/nP6
b22HacB5onO2ztk2ewG882iytvuw4nmKe9NFfX8QOrEpT5vCa23tJCIH9WYTSePl
eNM+a9rrEijh5rN6DJMBxSUMdK3Th5MSETg3YGy24aii9a0Kugfx73VTao8/4Mdw
wIgD127lVpzxfm7jd0wA2j2+2sVXmo42JHlDUZLzW11uRNEM1rZLMA5AH/Bm2PgS
U+oynx+Uu6LayNRhSbKAoclNVZuBgo9+arvFX/r024bkajGWnKQG+kUJxx2ltpt8
PoCr6yaSiT4CPkySOF7v3aV6a4iGvkkQ//W5Vo3+/iUo2Tkzq2Sv7r0Whlx9ciNT
s1DK3WkKVXAdRdNAsgY5vLs6p4DIDTTmgDywMTk5bhiWjcRWc+k/BEqLJgprh6Xv
1xV2tj01RExbKP8laWdqmZBONQSSAMdTPGFZ6cRl0IXCCZeJ9IbINBtwM89dlAec
wQ9GvuWdRUw1uagujBuI+Ykt3QsUME6ciFV0P+hs+tlCVPPUsw1OBtnCaEcbLhJv
rDSwCLNuEKbhXTWaAwFvpZLZFC9igQLK6hUnsu3tt5zH05/958c1mKmTMMbElC17
qSGn0fsCZQTEBqKcGRcl5z3mSR5ocd9FkgrHlOKFNKzSnWA7CnqBnYOpAz48D2vS
QQEg98CwKwIBTmL7ipn+BBLc24e/5qIPwYCGoDHlwPWRQyFrAXQVvxzoONUpVsPO
msBlugp6Guf7JXubOEJD2TGD
=zCgo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9497e1c9-d4c6-46f9-a6c4-93e01837765e',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//eysaRjJ57UedqRDY4xAtIlxYmJVyknnHo8KMP8A1ErRh
c5hjFdtEnpW2cTkG5xFz9X499F2vw70jQRyTtaatBhsw1DvFee2eqd3iI1UEszGL
LHm0PWxmiC8D1VQaSdZhzlp01l7IRYjLB8d3ndqnF28Kmq99V+2NBU3q7MwZKR9y
uauWS9lvwBddOcg8LnxLyBtgrBM4SJf5LRHS52tHwKrM0KdJLIj3PE+YK1PfAJfa
Mh9838kVnwHfM8RtXLFBTAmVI37ZRmQKNgYm8zcabvBF45GHvdbrP3xoi/NoWXub
hzAKDjKwvl2l09c3wLlSce4QDZv9x/sE7c9oZA+JcTm38O8TzQNAHp7uW7Do5bD1
XlLKZN4U7RLdGiKKLAqYofcgxIF6jPZOMc4uZYUtmPuVO8Yfx4cEgHnzcjn/g0Sr
ivQF+zv/32id2NGjXc5CB4R0U4HVU+ewH/PJap3WZw+MpsyHBRwUbMHifShvJs5U
UjyK4nxn07stfI0ZoIzUIS4VRqrueYj878cV1zggeL4JtseFYINF5lAoBZmUbnYn
/N8hMsPbwBPp3HaOhfM74hEc3/Ozs6qEqEcz1BPy0lPSHCzF9jgk2FWfLuCx+3J7
o34ydUA5Bjz4OegO4OKF6MOCx9+E8/jAj/S+bWQLz7/EmbaJRxj8qwDPn9NPmdvS
QwEgd2HRRJyfkUuvpUenRX4uoYf+4aKaylYHq7IKud2MFi/Rip4gPUvkQI1C2fcQ
dWzXJE+EIGdTBmoKtPVnUn4CKto=
=0dzJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '99217382-e885-4411-a200-1f5dce6b6373',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//eypnoDhV0Irx5xWZsLZnsakvtGTBMPEaH9Kw6oSWaq2J
qZ4kmiWAoMMPHd/hi6eVDI+GYFcNM7nX7YOESM7r7ga2XS0Pefnj+SGj9MvsbPdv
SFJDo3tyEw1e5mkNn7uUv63M4nWqHQ4U3OX8YCsoYWshqqhb+wL1EZjtQ3YPysZq
8J7V5r8//iL5+vYXYDavVCP5CK8wYdWjafztFGZnvzPkBQXBc0CTxh1p9s7uxqfq
BlxAYCtnHRwQK1W6w4pfeoFOs7oxP0FU92BcvNtH10iytAWkkhxomjmwPESHAHuX
5c5eDBNLuIKQTreydUiP34oc4m1JXRyfuejPORDptq/sawVZebyvnbABbJhC2O86
wnOg6NcQZlsc4L124pfW+t6mrASsefe1gPPPb8jZzBLnFuX8DmxZRfd72KhDQBK2
QKiyOMh7wQAeA/2lC05t0z7TEDrqEOH/Fn+NFdBoFsztC1mK8wOM33fOJOduFhqQ
aqu2S5co5Sc2TVJrTptGKS5ZhAzCgU3rltZXsjqUi+bMk8FegHZyA1Ih00GcV8Fh
9tzACIonlhXFbE4XWp4LDuQvxmz/LM0aONZTTMM3fH/nKHPWW0Ann5Nd0HWqwraJ
T3+iq9rPY4vFPpTA3dmQjXGIqHWXHuKn9oHTUBCSUe6BbUO2CLDQf3v0zLRwjsTS
QwGoqgzDvKek3ykzg1IXpp0N5lpQ6eVNO8qT7oxh00Qb9j8N1kKjh/DyMs0AoEJY
QqjGx9n4KSXl69CIig/znGeepkY=
=EgWR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a8e77bf9-a43c-4634-a998-651c72c5b66c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//YgNwhJraKGWkYFdj9BcqHhjltlkKFZIyJk8H5FsyguKs
iYvlvz0nm1TGGjnw0BBd9W08yC5mfP2KePW6bWtCuQEByUrlsfUTE2//MJG97TUA
C1n8zFQRL+Ke5xqSN6ItkvU+7/oRBG9gHnF5TkwxLXHvFt1Ad6oS3haJ9CGdOJSJ
btrbay8n1CdhBcdtiJKnS29Gv7D5c+/ETmzkwVomuKHTr0XqFItg5UrHuWrKOjQq
rw1pxYscWemln+QakJvsHdEeQ4OYlvP4VyexFrOVGH9SliaHq25t0aVEplKCY2Mc
BTGNzQ/Qj5vAApUvYvEC5knpONDBGkhWinCfX2S0z+VcjS3I1yKm0QRnUx6gWuip
ZJu2mwkcfj1ZF08/c5yMq3tUpBZ1wNhveGNQ4QSG+Vzvo7kc8BQ6Y7ao4k3BuQJB
FSPfjicmqYGApGpN0n8YD6wAuL9Td/5aqwNVzVUjwRMFUh1+rsNnNzPXxqEo3fmM
wPo1fPMz9TE9BlFvOaYBSQTIiWQDYYXDzSEz9U/1Ep0X9/BpfjqWyS6Z44Bc10+K
DVRb9ggIB9Zg7b+ex6XsWN2gKz76t1V51Msz0YUq9kentslDwGMlC7K1U+qCQni6
LEQQH3M57OCOO0KbGXmKZNwaL82UyN5Gf0id4SbKB5Ab+0chx/rZtIov5yoDf+rS
QwFPSv2kIjeEBSf+hMqTQh/c3jbkKjux8Fd8nfxqP51pF6RNcjJ0sFhTdNGBEr2a
zi9a56tYC9eYAKPWSq6VAwbiC3g=
=TPFO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aee70f43-8a89-450a-a020-12b9cae55f44',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAnuCLRN1v5VC4JsR02kIXqrsyzMF4x9xuHS/H4OhNNszh
pY/eDfh1wluKiBvXZSj+THg0ki6avOCtX0TYW4hXC/LCkzHVjIcNM5l7b9rBGmgl
HpYNwwqVyCNT3M4HKBXNc45/zXW3SxD7dfOQ0iv4vqeYVWtWyVTrcOs5bZKQSmgw
dhCCz9t5q/wWAGJ41jB2w/4/4K2bUBXp3fZL0OoHBTisOp8OECWg6/HAUMcu6W7t
EnZVW9oqLQizAOyp+z4AB695lC04rMk+pomH8NKVrWLODtJx9ZJ2D50Q/gEX0Qdt
9xBz7l6VRFMqsUQMWF1AP6mSY2wCo8oE7Yhxz3qHrsnbUcWp/JXNBanakssYbkRk
aA9Chh5wIbqYutDD5/X/4kbiO90vUOFmjZX8ssYKPA0pQtH8mfEXMBnRhmRnMpcD
lRYij1ns44T0IMdeeXIT0P+PfukIJyOD3sjoinuLngePdXH/GWN0EEtGYFAWGC7s
KR5dyc+/vbcagUJHvoFdLpmG9lVLod1ss+iCbtfgll7EaOjcJdu7npLhEPr+Exqr
jpcpuYLdosfcM/QzpV3UpqM/I1PfTDSb7wmgSRy0Jzkd8TrRnqghrEX6Gg8clVdB
Tp3hYHjFHaBb6D5eKevlJCbSc1k7jkdn9SzZRupnKCC0/vt3ZAu87H2hlCg0+vDS
QgGm2m2LCzNx+jXxcTYv44KZUvubpJKQv14ST2DueUudRW7Lik1RLIWeW4rgiFxY
QR41K/3Q3/NEtGH+tYv8KHjumg==
=uFg1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b05e3a2d-e301-4944-a1a1-1532043445f4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//RsGbQ/cZ85x6hMCK9Zea2v5qb3VYiLgW6TeeRIXVAq6O
S49EmZk+1mi0bX5uiuTI3T1gWgNmz4KwuAZP/sX5zwF0Q3t133TEBEkXds1KaHOb
V/LDS3gbCESuls2bOLeb3aiG2juiPv59jnIAgXnUXbvkGsjnxTvH1A5ECYA6fLuP
uGoZkjjR0LX54TZTHe73cuBfCOO74QrFhF4+QnwQ5taX+YNTx4wQVStVba09ozC3
kaecgh8Rccy3MiMsOxqZVNqtbipM+BQUlfkT+DfwkkUsMqnwGvtivGB/MM2f08l8
swS3bqVftvrDVmGlSf6l9u9CxVuC+peM2xVrLTc7yw6RqtJGUp3XRn+JoeHaQ+P5
OIu0VnsC8DhtOhojfSbB8KJsW2TLMnNaw3NXfld+6fQFAcljje3CvlLR6liFOT/+
KuhVXlcHUjPs1V3yvZmFuS6+wczgSxfPQh6314APLpl+rqnvY7Xi7Oto094WhNI5
eUElK1Qnysbs8n2MnJlOi6ITX+M7Md5ur1vUVUF4rEZz072wYhdXMb/sXsQdiHL4
vuHMNdPx7TxkDrPWQKKv7Q19ZACHYKB01gsOrNGQuGDYKXk+rP5Spbqkls3xPTGg
eHGmw3QlXctUFWtNb9jQbzXP0w/59AwqHHMopMAeHtK4XIGt/WT+BDaH9hWeTJbS
PgEAg2vPUFwhMv9Ws/SEsSd5H+lTISxMBEwxFaW98zYJSDnrU+gTVTUynmtMLJ0m
r4UGZih4H/N3PuS05FfU
=kDV0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b5eeda56-dc2a-43da-a49b-749267081cff',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//cn9bt0L3ucIeoapzxI82vDX4/p9cjdBj9L/Y2dx0LDNY
gCNPiV2X7D1zHca7+22RiNpJtY7i+l8KgJUcLM6UI+i4z48AB7GU7fUC2SYMaaxJ
9qQCc/qNK11nXkZnBI7zWvtosCAfqB7cI3oiQ7O2+h6/UJlF8uOjZ2/qWuFZ4IwE
CLOOWpxe/kiJp3PKPV2zfFjrgziGSEQl/zHlPChiNy5E/2qTwVdPpLhK/bU2sLCV
uJiAeI6YaiIG6BWaqqe/fZlXv2b3QdsKEmRibSXtg0J9dWToBuZd7Z7C6PnpxSK1
yQJn/PF3tBR+IfjWTrfTtQmb8pzm1aqMdXTULaSgLWa9DJ2S5w7SpJoPcAcUl2u6
+TQCqtLY6SLi/xyyD+43hQYllfNU+nbcRZBiZYmWfBUg0M1zsCa/dBmt4YTQNc74
K7zwwGJrRzlvVY9c+cspjua1Smga25mOaSWLKqFxrC7n3hFShuk74L1FVWdC5VbV
ssBcWLK4ldPs4/xyL4YO9Va4DpphVuo8d8tzignW4UfAJBgI0uoJbkLbcdZSvREY
0P3E4V3EfZMxXOJhBpdsMK3vERivTU1hzVWHbn1Ku7Wz4YCH0qQ3Y+GA7xtUGoVN
bk/txheB8dZPkzf2mXvuIiyKNH8ZlBD2gnjrfaH/6Nkc3vmjLi0Hj/PYid5eGD3S
QQGpsRruyAmE9JxsvOxrKII3BK13dh3n59YVe79k9yR2LgzY+02JsYxjfKbNzjeG
qq1rkTTdXLQTYMqnJz4KBz9F
=KL0o
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bb746115-e49b-46f2-a3ee-251ecd0804dd',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAuo+PNWRL6ny8YzVqmWXYSpPlk3hWMHlAV3QK/Fkdn4Xy
w4ZQB5SpbSsw2hkEZLyAiVX/F5QNLs3yMRnaOY77yVafwtD07wbKtcSwXYq4hRtZ
zrGIVmQdRAH8HffzSYx/WbtMEoLos4P/VybHmR7bNM45LLqNzVqdEh6mLNeiKwQn
Od59ql608Ef7wzsyeOv93+nC+t6tDRx4yeFtPRWc5lm+iPrUuA4Of3OZYRS4CarK
zV0s7LZjljgdR0f0p9KUDa0myGqlPvRAgYi2Hb2OF7mX3hEs5Cdvzk2k1wHelJd6
QmjU0LpH1e8v0YKrzYb0WOwbkXk6avKgwoyWokQ1f3iNA34fNXr52EIT8q4reyYX
rPPi1L529CwuzRR0ySazGk4/f8mUz6jnZSngKC43nWiScc7Zsq12j2TNyHOHKbXi
3TRvaPU6FVePBbafDawat9O4bpt3r3eCoDHYxY0q+SygaTlWrSNabqnH6Cfxfp4Y
f2592f88Im/Wf+rD2PgtjemRRTXlD1ubgiBbqIsyAUT8Sek42YWcCJHDIJif4tik
EmIu7HX83lKM+1vUbWNXvvYUN9u4da7f7sw4HUN6qcn6MSXWwNUZ2mXSPEJcEjT9
7XXqt/8lJMifcf4Kvcnf3wGyx6c1u8kYZv/aGbU7kn0F52ZQvCKVkVXAba+3bkzS
QwET9j9OJDdz02A33r1hV5+hGPwZ/fQ2wve0ucy+ZKRnH61nQVJCv8Y4YQIdBsMv
a+rcVm3QPaeN43YMEo9nDgKbtFA=
=BQ2J
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c17fe247-76c0-48eb-ac03-7aafdfb9eb2f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+OO+vlfKBQ8fPeMgLqBsM+YbT29JRM499PQH/iUBQ5kaa
szWOQLR0ShJ2HxnIN02xzaveOlNCXgRMQ8sgSbU/VnY78I+Uly1jlRhnTQTYo6Ij
UQKB3yyAHbeGNNCH4xDaPHf9DHT/SHTJ614NimxmJ7l4MrvZVKBfxKvo+xdBZYd/
/ARjTEGILMWrwwECrbTMPf16N2KCylS/ZkG+ea0gLq0lzrdMgvH4gs8mpMdFquRp
Ruk/5d8jzlRe+7PyUiTD2IY8gIEFvLPv841k8MARZAeNxbEijmKRGFDauy+11OwG
r+3jycrq098epS8TVVeKq9Po1EpAMwC7zBYT9MfNd7G9Y+KXJA/bFhCu1z1oLhPT
coA86rFTgjJN8RcP3RWRQkIKMXKbqWFfYnoSu5UDX8UONYBa8kVqSpuQKiQeMI+1
v5aI3BWK6cXEFSyIiyZn+zByFAn7r/hwzZRF0xgsrzlDVwpXGDCiQts4iYdf5ODu
gXfwv46i8te/VuHJrWqZ8LX6URbzEVosx88DUffpw1Jbc0YElF8LfgDjTL4wYxmz
Ry2oz7uc0LHWCsdHK0rXAPuWAjjgmBbgveJ7teP7Eoq+9kvOknEpO+XdUN5u8Co6
Z4b8Be3Wr8Lj7ULvzqVTRUW+ac4P6hxsl9epkbVQbIQOZafEhSe/aFYF3YaXWebS
QQE1juyhBRoYiDIhLOEHQ0AfJwPnFH72rtBbftl/kYoSoF7Iy7j8AOgkMyxTo5Cn
MOfmc/13LJpu9fOZA00udrfT
=tqJG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c4cde385-91f2-4431-aeb0-380a5a129a11',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//bdyx18sn57HaBchQSUI+sWVJ2Jkye+YjUYd03XFMSBru
FuwhVrFMdese65XVIJcXhu1BYz/5hQA0Cn7c7Z1X4KrWu56SIRHFBWeFUwJmBGEz
Gu5/ka84BHwd+yCmYz1akRKAXV5HHg6qOiC1KjUBLxMpDiHWMKvR6g+v1R4IhhBt
EYsjuJTJDro1mUwqf1E0Tq1c4JT5i6JfMMZf5ejPAfHddvZ2xan0H4ma6LpSx26M
HGudAcTTYwDxGoBgfx3HpPRO/Yhvm5bHFBCAj2+xZcuUrN5sGduSnu6MGCtv8vhP
z9gnYx2h3sL9XfC7FjMuWuAWQ39BanmND9UAjkUWCpLnU9aBroKKHlTepuqarvBY
CJy5orUYNC/w4kIr9UrEwyDL0zrHoMJyG7UiAcXxURa5w/EGjcrp29wkPlkQ+oIg
lLO788erZefmcywdMU4CCKFa6/4qxHVBrb87w9qtMN8+PjJXdWhE5yykHtjpLiV/
toSIwAC4T4K0VW8w6+InJPWrdJKB4QZrAP9fBHsYqD+o28vEFmmP2mBEkG3PH5nl
rWxaUi9eyIEP7ScgEF7kiJLB40S6jRYAh74N/tZQJzXnS+n46wJUJ2Hs1obonnc9
50nhfo8Wy9TtOj3xHw8lWVLZPlzAcCnGuM4cJi8TO41qCkfkXlLDbvqUyn/Pm7XS
QQFA+rxv2CsYkUqkeQScFp42e8XbSr4DKDQCzYHS/FaRn6ez0s7u3lR9FWXm4QEU
s4HF5E2ZP2J5CoNptlza/HvS
=TtzS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ca7d5421-46e0-4429-a057-9f10cc0e90bd',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAArh1ZRHtTlBoONSMFqdzOgYbXxcmcjkHL3wvNj9vEcMxf
aktN5u1ZP6atm1MCUYJNRuryn/Aw3cG6IHjOsyqcgSE/XTL/PTff3dKOvZ3KBQxc
DsWZ9NfXGwBXig46NMjnFIv1/rRpy31z6cseENZSEuL/yWB3N3YoNI373vLM4mCf
LrU2pkYzuxsGSi64GhjLJRpsmOH6AYagNTW4+EDy+aNTESdN4TGP5rCIOkvY5HFN
hmATJ75DZ5l9LIvSsi2zlLBjHpAsneEFYx3lCsT8mPCEH7rDNdbxphigOadcF1Gx
7sKte13uxeRKSIPeuJZyfBVtdHz0zWl3yAAjQU4aF1wuXSfrr8Hf6YXWoh1Bjorj
ur4JYRpgABRZB13IbA0dKtLmEKHPn82RXaa6ttRnB5O7+fPCT3lTSmhaWJCnZiGl
2UovoDgHDACUI6HA2c3rS5h52FLNr59QEu7wWLbZQSyetx4Nhihn0Pvw4B1tHBXz
E88sAwZn7DjsY4Ct0deYCly7JL5KUTzHxo88Et+sUD9E4aMT2hrJI7Ak3+Y1EqrW
0c8ONP5f6je1CJz09rEmsa3C4PIQzpHH4RupzQ+1Qt2L4aMsJ23pa+Ef/yizI5ft
++zIwBGk8PPfG4oLuFHLbGr1CYohVhF+EprWJtr1CzyVaUrQQjBIxqoBHtze10PS
QAEW4iacde65IQBUDIcIm9s/qfXkQ36wZg6BGGbrOQvS9lFzH/ahFpFjSJn9PAaY
Ssuzi16vOwGhmJfFOQTRUx4=
=usIr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ce5192a7-561a-47ae-a784-984d0dba6783',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9EK56Zi785GA8IJtjPzRqr8UTZyNp/IQiQauZ8gLPUZep
NtENtKd7Iz3OJPAvYNtrDIn0OkOzwr2SvWsWnMAtr7DmZ9OppOtVE+5hxoraE03O
MaduqETFwD4lDYjxGEO7s/fTcK9nuCKbYDHS0+fG/3Vr9xhpnTg0DGWf2R1A18H8
CtnDUgJPYuSMoHvEfN8+qG3Oq7JpDSXFXtSOWIYnP70TjMqwl+AFG/mfkh+nLnnD
yWEREJSWu71jgzyqbR4oYyB03PwVmmbZ5E/Fxu2y6zJTf+Uot+s/ZDKs7DnSs+Hd
9GqgzANcjNUhLWk6INGtrCgtWKUrhYsqemFjVYKi9fHE4xN3RfNk2rpKBq4QFjZm
sgFv9kAwKq8ql2WOszUYjyZyxTEEk+UaNFYjhCRO6JlvN2xDfvUZIuZYYw6Fzu48
socDQn6Tfcv5GWvlT+BhUKBnrVkbUvbAXsA0ghf+12qY0hvePUoWRL2fH0joZi1k
7nuSnilgfUN0eIVMyrsSE0pSnLoxUPEbjZdiOZvl0AAFgRu4dBxzmaZJ1lvIWhqt
Em3ohXi7DpH70/ynPZS76Q+64+3aFxR1g5RnnQj9OKG/senZedivJzfwyWkGDjMw
4rGUcMsCKopaMXjlurWtV222+lNRzDNbXYRvGjrnDQARJG8FzoiLU5aVJoIhWtzS
QwFgBJKXUO5QLNEqMWbRAhI3z388RZH0iA3Y+cgHym8kCjx3H7Lb8yvf5bmMOxoZ
UGfN9UdcB1n8EwBdNmC4M+z0LI8=
=BEgU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd20113ed-d053-4c36-aae9-435c7f4b667d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//QguZbFkKxxnvFKQMmQyxvOzalhZBwtNor3kZrMw+Wiwo
DHkECdtEhIe26ZVDzFqkFnzllUAwdU/gt0RZXr1PVJR957nKPUAu39S/UUudgVHG
uur7QJFJ40kopTf0asBtYK/QdlTOP/rj+tk160AkDbwMWu94eFAmvr6HopiTpHMG
VpEoe4XSXAZnJ74VzulVIXa3Kx+nxhyyr/ionoBZpDSCr/WanziLTWWQEunSa37b
rV30u8NOix2/r8yRNsMqqpuHbm8UrRj5O3dw/2UD1FIMWUA7jodV83Ct1a7KOKji
LsyZ3bVRv6Dar2ohVWkP7x62dGckPK6Am/7JN/COq+uUQMb4aZp8H3aEc1FwRWQd
r4Y2TFTKuQV4qQZMDuBVcxVB9YaIu4j/DcKLJYypFpagGx2kYYD+UlaarawYUKnl
hQ903Xy32ZmYW/F2VAy/FZQTpX2S4RZFWWbc+jh8xpTeg3JIeFdVqbYsKnJN1QSU
QHXpr6MRkI+6jPwgI5VzOjnddSqyKWYcSCSVuFapiwMvNq2bISQYgGX2ieFhqREz
PL/uyi20IiDOFvf24COr32RGgHsFwSx2gqFfVn/5Wuk179tK+PHbmBfWEKvm6xKT
afPTZU1tLm4ACTWYFMKiIwg1O2RK8i+5wlAMc5i8EpOBJxn7NtIytV/3yovDuB7S
QAGLOqZEVTA3f4etMcTVxmdz6jwOEiymovm5qSx+xBAWJn3G2DJDVSey60HoCyIy
NWCrBrk8GmXwh4ytfsGgCDk=
=y6sA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd206226b-9c81-4c9e-a4de-efe0ca76fbbd',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAucDzcHFRbxd1LRrUUdJLRJTKNqeHynPryEVZVAVRZ+NW
PYEdmd2a+R4S2HDM3PLbNICDqTeDDjmZWCYj6HQw8orYiOrqOyz5rHj9x/ubsBjm
tDYoBtJ35zyYC6wafaFQ3ptLbqN97b81Csp4AIiHGmQ5NBpFqBK9wzM3UI6haGsC
f/J8xl6ZcF3gQVvJ5mPFQzCrScPSYgDt0TbzWlnrd4eT/zz3eVIfeYagYEnXHWGX
aAp0KLf6prbtQHB37ENLyNG6IrDYLzkeKJ2yp7fJUteQItr6wABtAw+yp9EoqnRI
n+zr7HxajVbeigcDZu7F43w78iISNBjs8sQusNtXfzrt/UDSAGa4VAlSiJjtykDb
7OghgGcs2WY5T9d1YGHC7x4Q9x0jYExv207wVED5gO7WunKqvssRMf66dXVAEhS2
7fq4Agq5DUmPokgo87ojzb2B4F3TBFiRBncV2TCwGi6T6Xflbe9YjVEH5G30v2M9
QTf1yWGNjyVZwS8jvuaQZWAj89gBvTTua2qH7vHowFJnUoVSPMklq5s+86T9mpGi
4fci2nHTO8nEB6JMbt9VJ5LMxzL4kCIoIMA7tM7KJ9qI/pTy5HZHvMtZgStaNWP9
L/Qb4vS0pT8CpHDFOQIFo9dxLR3oYssuO3PgnkZtbj0EwOd6Z3sO11K9Ut11JUfS
QAG90Qt/VUHLYk8tcJWXVPGS9Zgm0FQkMeQ3pNdz0e7bUtGu3a8wvJE6ivBe1+Wi
C/t54aOBXKpJAcojehgWp+o=
=gi7K
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd455f5ca-5c6a-4079-a1e3-8037e72771fb',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//dlP+7IP9tqdndKXWmYLsvvLg4T3IHEL5rnYi9oZ2pMsc
EUqp/7dITbcuji6Ci36e0RpzgT5Uz+Cg8ZqfvfIaKaf43lgPWUsLYnGB9HfVGj2I
UY+ACI0kErEGM0iu1U3Aa5htA3qgxhEOd3wPyQFtA4zqBiT4mJsGBbe2NMfK00Uj
yGJ8xhCHI6yTMTBe5zxQ2gEcJQCT5RLp8AF1yb4tiW4V4RS430jtRpfE9z8cH+hm
PiL+MYaUkTLr06Fst0Ew1HtxSVDbH0v0iMpWNlSAobWgUCiq8Aelsc9yIEXJAcTW
dgTGFDzSrliAYjnWDuvBR1bEknvV1jkab1r7H3NyCqMTY262Qiqv7ri5ePfzp1bI
qWouTlNlQPKGikd8fxkL7X6ZLmdyUzW93HQ9fLdCcyo/IIhPbJZshYBzGA70AWNX
sjyDpdOwdo4x6fK1/HpT4psv6s0QbaP7cNZuyMY9ooPHJtzaZ1Wd5/1Pg0+OARxP
2giOTJiCWd4u+v5FEwc0Dto/0XoY7/IOlD8z/cOTrgBpJ0d+WA6N25Bav+hZnWeq
e+4Z965Sr/Jl7ZwyWqoZ+nTRS+gm5OMmXCXDluEUZxUGrzAZARgEOp4IRydue81x
id20lUfca4/tFeJJNJgFqjBk7YagvNHvUuwKEY3S40EELyd3YiA12uX5mIYnQo3S
QAFb++ngVaRHGEF7Uv19YvPD2YvVqfBdu38fmB1duhKzldqW+cGyaE4xtYs5YV8X
KO2WaJrpT8VqhW07wij8wQw=
=9Ytl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dbc7e141-e5bf-4e9f-aa54-b19e53f5b34e',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgApXuF6ZmvWpp2iRn3Iut4OXL1+bk7TKikeC1qJXeGZ8+a
9RY3dCE+q3ZVuuO1Dthn69+HOCnxhJ66wWyTE7nFPtCsqOiOSSEm3HngzGYyDrrD
up9JJkg+2IFIXwXit9hGmDneWuZSkb1yDpKn/+pjFcP3FLj3GTYW07HxNGT+o8Cv
BFP23BL4s8HRm8icNWwBQKbRK+4tboQo5JAv/9hIzZCkCi2wul/X2gYDoQdHUPrR
ikSkewSQDv1TS5Q8T/op8QFVAZ1JSlfOgC/cysdNWGTmeW1Nasm2xGw98XxqefAJ
dlWvP4cQZllxWNrSVST6yom4+ecR6dZ6sUhdaAjOldJBARLt+Az2TTRlH+Y86cq0
GnclbFG76Fm2MnXpxHmEn0fmYkwlAglHpruQyTDbPKMMbO4LQ7I84/CiiTE+fzuB
9hI=
=z/l2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e45e8229-a73e-4bc3-a48b-38dadefd8b1f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA5LX/fAySm5oKN3rO6yVLVSaRXp8KuZBLTDE5R/eZ7NZm
yCy7tpd3vN6UPf3dXqbrKalJvShm76n/vw7dmgS7Zaici+v+ZR0ryNJzDLIMZs8F
9+4TKP2r/fTN1phbyrM1S9IVFL1TxOXFOlEF1fpddZ71zX2i+yBkt709FNeAdYa9
eRbgdUlLpYAKxOFo3lXI/V5SjuM3JOzaZSIXmwEsWTG+nxBzZa6WfV9SQYjnPEfQ
fCj6jkuiD1tuK2z0YIJBolVkfJGrwjSatTuddqnSTUgHWziJgzyWtP2LGl5BudHT
Vz9Fgst/s9gaRd4uzmMMcHZKwjYoDrhEmCbtdIgm+CBqtncjikQ+Fhh+jolIYR1P
b3lT8IAS7bsx/WwBm4znLK/FB8u3Xw5gSZlBam25P/PlAujLz4A7r5/uGlPv5vab
7mHBOxqr9FuwwwJp9MFQnBZj4bN0JD7zKh+QtCKSkfeNt2NlT079cIHAn4h3jqd2
RQPnZJQbFdO02BMhj0uMYhKYyPwhVqjdbA/j4I7PW00jSLJ4/gIaOHlFFvWTCFur
a8eVUvWji/Fd3sP/3BL1M7zfrRIgEwS+/JGUCXJyn5Y9eIlSNwj5j8RSn4J5SrDk
YQDc7vb2wIbEZ6IWjoaenm3YTQs1GY5/GIfaEQ+fRni1VAnDVm79QCXG48pCmpbS
QgESCIkAfNjrV2WlTMNybhr1Hwhf6JLV0f9d7SendJoiuSgdV0aD55v6YENfWxK4
cflcnQ0sQHbiLBw7qkENFoDwYw==
=89Kq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e526a56c-ab2c-41cd-ac10-5f9342f3cfad',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAjtGx2pei0uW6EkUhKksFp3kVLZ7dv67OW3lrqBh5yGrI
VlZB+Hz/hVGklfMOpmy+recOkruzqH2sZIIU2Kl20UNxI1duc6D74CAQ4dhXzdVD
sX+Vg4y/olS5JOodiCSFwo7/Ke+gGJnaIiDkesoGLnq+ufxN2eFQgGJ5zWG+ndmU
8MFgN8VxpOHvZEvXARDyxpf5ZctvidM2x8j7yEzrHV0OqINFrT7j/aRk7FiwAOwe
yLlQO+mU9vOnLrzV0thZDx4EwhhyEFCqdm6mv9/HXFTDJ+yq3Lf1F7DyZjNcXD3P
nitx38w3Dn5lItxoJIZE0pz01LQFbjhot/XwxuE4f/xmImBrSn05qyv/T1TnhtjQ
rpxPCP3jSpRzTHTn+me+USqUhiYSaCytZ8ulrjJBbnsKcQNtvCTwX8CAsZ+0fhDR
jGaU84FKu/MkA2rTM04r86mah+G9NTQP/+L3+axZRW4g1M6T0PyHv/K1w2YGZzU5
/c4Vz6ja6JzlL/tZW9aLQhnnnQ48O0BczS6cMK48oxhBHiHv59wk8KPQ7gOdBtx/
JrBKIPmXoBjlM5XIgbEPgaNK77Vg9QWmcTJuidXv8Joj1MK91+0sX6jEDl4BA8R9
ATXceNrTaNrfF7j7zRe3S6C7E2kHmXTBYmi7EyvtWeCOIUtGEsgc0hK4D/WAl8LS
QAE+7mkKBVQvw5GD1MY3hj0K+UjHPRswtYNuwtIlRHgs9L1qBm6sofcrR6pbZ1As
+2majhOf1wt9j4Jia3emkFU=
=o80z
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ebdad82e-a5ac-4388-a6cf-50274e3d1744',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvmI6krqupLpoh1CTc7HXMhdZI+LEmH2zwdbh8S4Ntxjm
NtWHSOE89AZWBkGYLIURM6i53P8cDtUYFQ/P/HIELLGsqqhsyxociU7oVtXZezjl
gbkJyM942XRME7iwY8qOnwFb95BZzHrb/FOF1SsiGud1qEaZf4q2+Id88JN7ViDL
dq7W4AKQmYWAwdHy5SygryqiEGt/8iEkANTp5dFtPCCyZUaug/cUPzvtxSSYbm6X
eKypFnxcPmoWTbsx3RWw4/FpFap90UfGtiEKl8gJE4dH3ee3jP83Sm5Shb8LO7Vy
yeWRDeGZz5Dk4UzvQU5TFKLjuFkOgh+Y81qYItjYTHzlGOyMoSxwv3BeMzj3/oOe
k1tnO3HaIIIhor9TBWTSL5MpLWsZJWi1isI2G3STqr5KMuiMa6Zm1GZzdb7fVgxU
6x1DBbX3HDldmXOaOYTUK5T0OOLmECpsxs92a8xWEQ+N2XXD+FiZXO3Tkcda0Aq8
lVraG7ac57FVLxpcmuQLi3l8tE60BjqRPcSSwa+iKPodpK0EEgjPucmMZr7RJuGM
GBX+hjlaYHF4092SSK6gPkmUUIBtgBGDO2pn5VBRDcebwxdbeZ2y7LtPOR3sexfg
rzMtzXrNakz0O6n9YQ1OXqLV+dd4l/gplRMBDPSN+WHhjB6vLOoieXrkptFBvarS
QQHwePuLf3Q/CX3+2FV8mqmqFqFNq+2Xrb7vyS6qJpTi34ftabQoDJYLRK4FYtso
YyRV5J004zBWmE34Dbwjvrgr
=qoem
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ec2f635c-db8b-4bb4-a021-cc8e8694d161',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAta1w/1nt+4IUxBlR+d+eu4UYkC7xgrLgVQ5XjFE0QOnk
Z6O35jLWprMFQiRccTlSoQFcZhBP2ElgFtTQktIJZq0YyE2R1oD/QeQPkMXa8IWR
TU3tn+zJs48rpGowr6ZqxDTr+i3PMa1F+2qWkvy/TKtvfLe0+BKL/S5iXIcWrx/h
mTL8ye/FZdLEwxxEM3JPulsNHGIjzWix86oQ0Myj+fmyvziY2p7G8GXwXEjtZwDc
Ur8w97o7VZxx5kFL6zxW9Ye8UCGX2vQwhZyEUWzQOAG1Z7t214OhKrjfjyfn/ifD
g+e1H3Rb+5g7Bs3DjJyV4iKx71Gv+h7L9p+ERD2W7ZAwBWodTQIaY7e7qtg/pg60
hwRuRjwTT30p+PXFPHubIc+nPE0tGZ0tIIxWRE6LKPXqh/qrGVSvUeoWnpTeOUcN
U2xHA9FvcBcUqFw5/UR/30K/qRjp5LLojCUGCX9tII73tGkeM8sl75eCicv1hBmi
qqQYrZvu6hlKFX6bwWBQNDwNwi5h9rvChazTPMlCsxz9CsWvLe8L6o3Bp3myqx4t
s9mwxfonKWs7fbrYpNQ3SJxRriPNaahjr6s4EgSQgJ9KbyCsvzIPW8rT0NVQNcwN
W2AwhrMM29ZPN4We4N+xOqMNBYl8fh9T6BrTWeXbSl5jWTVXLMx3K2OcCGjXOCrS
QQEd1x64zpnKdqjE/EOIkwfiG3cEKonTSDQJBJ+Ru6R83Xe9CcHkxWdc/kSYZ3+7
HW+Kq4LRHQYUS73tUr4IBC6T
=gNmB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eff437eb-0419-4045-aebc-8daa7996551e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//TWT6+ZcCneaxruHGaLgSOYQgbRXv3X8tnlXUhnlO2ooY
qyKGXp6bd3TaumslBgrCG7ZszZ93gCDzO/a8dD6rQ5DJSg+TWHvLe5Lyw2tNbGSm
k3PupSz8wnM/VGWetBrxhMdNva0RVrmkt79jUhfY5yy+EwAH/iXWx4jqm3mdodOk
DayNDmM77oks2j6CXDDxbG+21ZmlHnabPaTb2GlbJv3YU91wQqYHeMC+gs1UgWpm
VlakYSaH8mroWfr5BIqIZZzO/s5ZghfocW13DxbrePT5/flIwAww5XeB534itsu0
Mg3zY0bmKE2vOp7gCkUiA78Ka0DPuiaLIzP5QZ4QENB9/7gwCqJS24mhU9c1wDb0
UJEZJktMsdRDhCF0V+pH1rYS637SoxEqKMlduYd5MjG3Y87ZVUOYmvclwMKaWG0I
/Cihks59Udmc/fB02y0ND62oaOHzD5xs7seHYYt1vxtUbxr4Hd2nFHRQxkjoUOqf
I6LYLWRsZMHsWwkuJDGeAWv/nGqJDbEvrW8CzU87gcwcfB2Rlm1a3l0Aup9OhufL
bD2qDdHqOlFYATi+ToTX1yv6n/oqq73b5SRFQJU8OqEDoLJbmQ+zGLGDSie4FOSn
YwAkzsTtul2uwsmRD8DqF2IoO4chnvJonnph+TXeJ2aztXBgkDyoa5hcou4Udr3S
QQG29VAilQ0PxNrP9hWGAoMbOflTxFWskc2nmc4Y/mocOlzj1ih+AaYECAFq0r3T
LUX9q0IkVe7ZjgBqDSMoBGEV
=Ub3w
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f013a483-a548-4106-ae27-f5b72892d60e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAmU17P9zrUo0auKP4wX68AO+ILbwLP2+rkETvlngPeUxg
AoIXH5G7QB6KS/okUDbepR8KUuyXX4ewNbipfKSD4as3j3aNrMI93hIy4sdAdXGq
X4H+QiChPvcTtWYLjMkK5YjGh3sTtuU2VP8td8hMIMuH9IKjmAaEiZj+ETDp84EW
yDy+xSdHIQxypOw3H58fqFgXWzGxuwc8oCp0bk5Bi1iCogjZPOsSNdplnAaDJM6V
sDuASdfYZoNRvXh/YLLqOI0mOnLJ2cx5D7eZfZSS5X6EtZmrbKtAetEhvdILKYqy
nP87r4FdJeNNtNPLiV4gom644PLdt1xv40HR/4Oz0x5Wb2gikufeDo/LpLBvJArA
wULJ6/X7EX2zNv1C0Ih0W70XbwHVaBaEBnYxs0AWG/69MML5rCLHDYf0jcbX3XcP
NjgoK3bHk+Pv8qtT5FMXpE3Cons8riYYB0et5IsIOXMQX8CWqLsS4rdwfGyKNWTl
Yz9HoT/mVPEmikLVQrm0lqBAoUMwfnMwSsMQOVt9XYe2FzhlAFsELSubW9HIcTr7
G2Btdg0/kTwTbYSoCnvA5+NUkt0aMBJ5VV+5loT3THqOhEZH6TA3i/sxGyjiujkq
85CDZbBEynJXQ1mQG26oXcdTKvBVILBpST/n/vTjDT7/ODd10dnnRoZN/Ux2Y63S
QQFZuHNYEJmQtPC69FyLKbeBvPvM0wFgElbRjaSunb0crMiUtZWo/hdd+Gk6S7Ho
O4Eu7YmHJVC75WWs0WwDa9Si
=ZSQM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f01d3dcd-e64d-40e5-a270-55aa21503301',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Uig6aKTvY42kTT/oSb7hxRGd14MSKKEAHIUQErp1mq42
AUqxM+a1yDptnnjISyvK1YBl5Mo7Elk0TQR5b/O3nvzaExWSkABhR6kueW1ZX+11
7vJifdbZEgMC+HM2/S+Qf1F+4xZ9g664UgZrDw3rTdCFp5BecVhN+SpBCtmrRGc1
8zzEbPYlXnZvFil/6h5BdVeNnowYK9Kq7MhN6gxSEMc/FvjFvLq5/J2PVoqcsi5H
4/BYOKoPIxKFTuAIZGJ61PtCGMEwtObuxT3rG4tgAFgfOXGk5egeUB/e1GNWsJv2
8ObJyysr5DrXdwRYddnnWNRJG/SKB7rt9sZLDOFd2oO7hHRvNVJaY0bLopLPni/8
Rn+uxcpd6U3B5egc0FmrAuRluPEUXGYmtyPemlRsvuNpmLLrWptbH+svPyNzS4lg
7TMlNs00Gk8sHTA0zaztnj680Sg9H6stsvk5vHhw/5jSGmJkYBSKgMYuTK6JQ/2/
oCSvCST6SUfn35Lnc5wjRRrQGR+UCMilgz5cMDzeV003SnIrma/kD+VvHbubELm7
sBds4/NPCSkjc653JUPcZRPkQayS1CTI2cGbxCoUnCJCQExfyXCn6wvF4Oq/AC0S
OndUfNSPPO5Z9fv3DaiW7dnPmm1pqy195jmVP73liZFZXAerfEFeIUSA3oBqsofS
QgHM0AYVQELUnw37Xxv69jtxp+W+PrICcCdd68UIbAjB5ZK7S4bcUXz80Pd/3VfB
wlWcc87CZLGTQKkYrodIFahyUQ==
=DD0C
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f2cd57f9-a1bb-46f6-a7e1-55f8396c95f1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA5duNukwyNTifGHvFpgF2n0mTdP9O0+NE58l00MPkkqxJ
YSCbGIwFTMLOp2ClZhZC65sl02uFmTQ/NptfgBf1ddgqEarCGh0/jC+B0nBF+hzv
tlsTjuxlNBcajivAAvMl9Uul4g8tSCfvXoGS+ehEKh+UlY/g1KLZderfEC8/K68o
UKBIGyvktgJEAgEbguq02QDf6gOMAiCZe2tL6R25/SJzA3Q3oRUHSQs/uhmSFRfe
cEVTMXTMN6Ugw5qsW1Rc1dqPBewd4+RuLj9mrBGgJpxdwD59kihOFML2lB2H/hhK
b3YWfPO6A7bvuokHY0cDA0xWRMlNZKeTRTrqihfx9sKCCzQRZBfezeQ1u+bpd56t
dXLRH3lMtFnuIVGDQ/NbJLNUBOls6UBewVlg7WutMA/ZHpPF4USUPbqwRXXUkxDB
gAjswCoH5zj3Fs4qpdR5/6p5XB/5zj3NX2KlZX1mF4/zj7+xqYV33FFStMQlmc/B
u+kdQEmcH5SakoVGxrkBkhHGtPZ5EJDkKzO+j78BDzq7+l7oWIahgRt01a+bKSnf
wYBPhLzAQP+u1zo7rezbfbVkuug48pzvFs2Ayic9wBlOZQRmNB5LW0/p+G2l5KHN
PE5/JbS7Jo8zUU+ctovd8Wv82FOiJ+w/SiFWZjM2pwgtxqwC6wZQHMRcp5kr/hLS
QwGwykaGqRspq1QpDTW/x0Jp9hVcBsu3bpSYbdCHE2W2HGov4cXrDaNctNhmyV8l
PUoT6mUZcrlctztilWIwHo0lrEQ=
=REHV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd2d3177-5b63-4920-abaf-827f0d018404',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Q8KYVCpBQgO0nk2Wt+CFoWPXFib4lRFr7eR3qwEJa4Sn
c09p6ql23EMrOBERx2AXmknAU0ph9br16sfhtOuoXOShZgCUD/O4xNeMDCaXZv7j
OBdvrEjbDsklNn+UDQPRJfPJ7TkW77laPalMnkE61JM0R9l4CGdBJy55u7ffm2qR
2Yq9hnyoNwhdCIb/TIl44ak2zfS6eMGqkiFz8rURZ8PCGqCCRkUP1lHv0JulBMG7
Fpw5tlk4PCTuQJ+/Z5zT270anyI08GePF3qAbpqBnPqQnDubee9ptWXOImn11p6A
tt3H1Bns8IvbA+fqI+SxAFH0UHBz7BKqxyeeq0nAPCF9VNyUAqXwuXjaGOajQGf8
nP+5egMaOH84WdbnzEK/j+0JPwaI5Acw7Qn/rrYICUVQs4ACU0AOga3Cw/5TvsOi
+SRTUb0Bf90CTApX6KGvvX79I+PfWs7hIUenrfSkXqq1EeBTeaHFqW0JBkHgvdnz
zSYULBAWioQtixvWt8prWJmx0NB+6pNJ5toJkSbS0weE2/WIWEaetlbSjYZrS58r
AGzcdY955I4pLc4rIrDLxIf8oZO9PrhCwa+qBrX2DC3XQAudDR5bIvxhY+/EgR7S
N2sWUr1ThxM+lI344SiTOy06LrT1T0A+3FhySuMGSj3hXJsobWRwTJ5UDV9pYxnS
QQEI9JcwCOSE49I5StfLYZ5ulKjhli8fDiBlHvBxW3aB7bXN9jh0rlksupd2cUZa
877FTnkp7ArnlgHYlW1Kdd/g
=4yQX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
