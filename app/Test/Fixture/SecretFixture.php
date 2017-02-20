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
			'id' => '06ee1aff-1cc9-48d9-ae86-ada6f0dcb160',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//fA98n7O1gGXo71Gg3rcl1qx2hG4dYn5krAhy52KfvhIm
7TJbiDIJZykuQ5BYNrL40b9DA6a2epNH64/Oz102AxMevEXeWRbZZCgCNcz2MP7p
pcmNzCTA6i6SyXV0JZIMy4+6+3Rtrbn95dFrHahH6SkVd/+wHALQ48NK0n2Vk1FU
IQ6XFl8FgKmarU2nbKyujd7+1qxQNlz1MI7OeeB1uHctAXIUXugX9p1L0eyb/jQR
L8jmPJAiZRRC9xlktKFfo0YWBsSrQPB1c4TRDWYroJTgHTanyZqu6B9on3UBJe94
tGXb6Nc/Nlk/swm71ZSTR6wLlgGix5qbIbMg2mKTSaTOw46F9Ipd603rKvW2r9d/
ad40EAR1vY4ikMtJJEXC6yiHnsbcAznFOcr1bBQ7MqCoUXZj2VS3XXBZp6M2FZbL
jswr3lBIl3CT5YBNjpqIoazkJT4jEP1Q8bG3FdY7dZFzDAuxpR7jAtMei2ZhtmXH
0poz1msRPApTXWtGtpk/+h1Xrb1gtLU0yCSJuC4amRh4zo8x63PHCYKcSY8Fyqug
oW6yir6kn/3Gfv8ekDXGhD8dP8OpLAV6TinIyTmsUEved4OyGwgVpFXMXSV5z3cM
D6Z82RFBV3Xzufj7bB2FbPdnNZmRiVDN0o9pQygSUrVgqvP/cKNpmHURQ5RHUVLS
RAFtJLDIO/RsYY65GTcC3V5xQ2fzuOpA4DNSAb3Qw+O7mwdN8D65KKbzr0ROjkTm
RBs0TUtlehWeDtQUnLnS/hGur0HS
=HmGq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '07602595-0684-4d0c-a816-d8696f4e33cc',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAiDtTC3QJw4jfTWhbu+lNvb/l0PnDOsCfrzqvz4ZrSIx7
Psj8/vTEZR8gnh5k0yDT9A13agta3RPeCFREyZUUCIR3npwl8weV604ajYAoeR78
+x5MBqn1y2l7AA/cplUB7SLYOW0PK0Kpfb1XXbuX+h3+3qQEY3Cskc5fKrWpd6xP
dIUSovqXU4X1d98FWSPw+G/oC2WBxMpAoHljhj8LzOVwbRv3CASxvR4kNhNxKLTR
vmtGunJ5Bwl6Aox2OdYg+aWb9z55j+a6M1V/AyV4Llfo3PfJEHVTv3lDSLO/nhWX
x0ChfW9afCzd2pfbA4UtmOdGaamraJlfMW0CfggdgpHy41u9P5VjMkN6nCdirbjY
89+oKth4ewGD3P93mjUA0cg4dLgMGQKwr56uYu9gNDbRfiJ2H1l/e8K5ZTiCT4HY
GlYcAERk1p4JmpSVyhnYe4Si0dDQnyy0ntfnFDTGdutIgjaPIQmuCTsMHrC54R+E
POJt7zKzQkYMuJfWaXe9iUs45vySDfgYRNewwiL2iGEbgawvmm9lT8vdkhAdHeS+
l//rXpyO4KD00Gn399+BxXJGeO91BOme98GXQzWRNvgyGs5w5gu+r/om8nlm5qEN
BQzWhHrRd7aM8F8u3JoR8wRVdyjgWGgYjLqVqscfUNN2L4OdnRmcaKFE2Z74wQHS
QQEP1Egr1hjxTdXzHJFDX0H38ppJ3NwJnmE9WCtHf1j9HFl7bb8JIHCmmWlYGpsD
pZxFBv+xh/lioFpvQyerkRK9
=g94r
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0917f9d0-dd7a-41ec-a53b-47df4dc4be7f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9EOo+e35oWtKJhVp9QQ4vxOFcxL7L0ZcDfyCZk2Zt1CU9
5XfgyNAZ8vdveTiJ8ZYUMLYUDQveb8VHlReT1cG7Vkmas4u/96fBOf5fg6FLp8va
gAVbJyh7b8ltK3qvNXkRSNbz9KpC6heuNNUoSEC53AjBEpD9c29grv7R5rGZO/5P
KiVH6opnwTDp3qXAkp+ncRkG93F3hXACmX559klJTNUoGiPSwZmqBTLXByh2QK6T
Ykm+wo6PVe4CTbPe9kjM0oaUBKY8NXtVxbnBMoBN9VYd66EhOFFsfERoJmwciqu3
ET94Np3LOitp7W6IuRr7Xja0T+pHmze3OHNIrX9qX/Tlcw4mPfCL9QSZ9HoYl/9X
ykcFYmg8HR2HEnzr8hVcZs7Ebk9gzztN0xsSHitp4XpDTGTcQbpRfsSL2ZiT4qJ4
kSzCLw42c++BDnNeS2/zHE36J3tG2giNMU2dfM4njhKwPEqY2M4AFhIRqYnxJKwK
7wWZbuVMYGGighLZya77wS2ieuoSOQFWZWKNXf+FvI17m1YlbaPp8nFSNY1grmxS
vscC9M/tIPtRCKtSHLvsBWnnFdgTcpXnS6C4qSs25AiXQvXDRUGVHaPP0IRNIiue
p01N9xBEbEmMiHiZwnHVtgOLSZRzT21HrDTTIpfch3qlzKfhUrvJAFxzXITnVQPS
QwEEw7ZdOFza4Qy57SOS8BT21Ym84fULhKe+jQlBSxBrgbMmgw+XamO8mdYGCIYE
MnqIZBKw1Mv3dIqWtusgfmnjwRQ=
=OcUK
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '09538e88-51d8-44c0-ab20-8663b8126cf0',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+Iy1we0Ga+l86uJqz8YfzEUt60KOoYtPYrh4zcQ8xeRgH
sULF8CJP0zWCA8U2xT85IxqsaKGeUVb3gUJdlk8PjKeaISvA8mxBR9I7ek8EhOLK
gf4DoID0dK8dwmnuPizz1j6f5iwxv0gyGuXt0BAn2Z6wlaVuWh0BL4HeXFxHKwMc
6bXW6V35K+suWxKrfFzBBjraTFR0qjkEkX7s6ZZmYllH6NzE/YmVHJBlM5O28PUk
89+pt69ImuKoAHAjPXlS9Q7l0W9NsR3me4Onbz4C0y44Niq9h7iCot4aOiWjC/i+
yQfGCPhjhpU6l6vYXkuk1sYZ0YGxhEvuGinLgoZ1xtHaTVBizsqa4wbJWEbRs6aW
6758ywFRzovPRdY01RoybPcw9aJUUdBENlCs14DA3eetSOphbNZnCAAT07ItoAPa
4Qd83eP37KU/nl8gve2G20PZkOI96+vvafvb/CEMcIwKfIju3OJqFgbWlJlVZA1u
mJb7beGlWb1w11W2pN62rhtv400fFR8sQRGHoZ7jblmp2viq822r8E2kk15jeL3x
oY4CMef+uokrnuMKRNriHrKGjWZ+KQrvFXtzHrSEYZQb8M7D8YF0EsdH8PC4avpw
KDrZ1mRBMlpeR/k/LFQYahXEL+4rBYlh1xusIWHfFbOVzv/zZwzHL89Gz5/ctaDS
QQFtv9N4W8MEOuT7oBq3/AbBA2p8tj/R9S3H8bVXsiET1P6iVkpA2OkWeuBSSqai
dqk2r3+S/JloBTKreYONq9mT
=2w7B
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0d44589b-9d55-439e-ac81-ccde142fe41f',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAlTsWVCxdqs/PU8zH+CE2Mrm23JzX2je3AeMVyjkiyFws
a1q5Ylq3U9SzY5raoHWElBTfY9Cdec+xpnkI3x8WS8dDp0zwFfX8wsQhIpvIPczx
zAdR6WK1dHvQH/+oGjbjEEOXcJhH9IuFMT54fEu7SyCorDoa8Ik3iU3z4R72ZQpF
rSG18JniCwZxo0kVt62K96y6JSjNomK1uMLv9chsZOQFTZj5ZyncE4pkIEOLiVJK
WijGWXB7qbRciLWoG5NgzOwVfFYN+quifFnYe3qWAQR9ydcuYWAorEsCrahkQTmg
cRJlb6bMRWv6TWIZ95dD2t9jlee7fdxVNwm+c9l5bWPCBpLRa0BGRHfzmSon/Igk
74mdc7pkbK1E/trFjqot6A1ZFoj3u2PKsIngDoOlrvzgMQxVr7glg23GB99pIrVG
tG646AppaLxkmDeNiB+3Vquh/BmA4usfaoxTS2FBdGG9+0hrII7ptJEUJY7BzgTZ
GHl2rnATUEzFrvXDOQ6tfOqkocDZaXkOPMimxe1W25g4GjKmLHmUvNpezmLLwWo4
HY/FL+narKDJPrbzS6jb73GxMLi50dX56gbi/CtHFeilAuMlvkCjbVuPwIllLgV7
B5mkfvLGBRquQ4p4cbsSxnNijmyoazDwSyJcs6HHw+D6rWkVmQQ5kxboDfZxs2jS
QAGQM7QsDdGqNn/FEkJLPoATVBkCMLQYvFHlp+UcGkskpNZ/tIFEc03gwS5IHEvU
J2cZcn5dX46ie39H52zQ5nA=
=2Dq+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0d4e2081-924a-4748-a799-ac07f778721b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//XeQ1SzLVA5YKC4rSFd8IUa/lBGQG4yV5T3gJ22mhiQSH
1SswkFGhuOAtVKUFrOYK77bQmk3ku6M2iY3xV1lfcAhgyfNZzYjOM7xZIKBMGY6s
GkPUxwwJresYgpRPMaCHBFF782haAIWqPSCksicYAEAM8II2SC5PfutBXL/YprbD
Hg6Ui/MfgHvJQLkspR0py2tBgQBjzZlkTxHph0s4tXPJzkYhtAnV19xVVtA0FbVi
3UOXi8QafFsca1gB60Jp87f6aBhz1igQKK5OQyE6tDACFp+qvcFPPNHCle2Aezgr
kVxxPb0Lz7gTzYoOCzvoI+CrjncuxFezOfQ7SQpym9oxDWWNhqi12PdTkACVjkm3
YSrz0GHjMEDbsmCKyHgmU0Q1fUEenDAlII2L8kzhT9EY8aYICO034qmOjeSYr4fb
TMGmJ+44D+z4L69FZfNeZHengnQ0AN9jKVB4POyhppvQl7HGUGSzW8Ok8YJ2+1NU
TVQanoWI9qeiy9xilEdDXVJfs2f9HFDa8LOulTgLQpNzfs08W+uR0Q1/y+lu0FvC
wXh9xU6eTdhS7E/5o/sAPMqY6GJqSzVkVes+rytT2kSxQIQ1xSwDa/iq7SRz+s4N
gsMOK8OSGqsw3OAIlvYjh2et4QeIDEMsdzNw3ENZTQ1psrai6C0wLfIfzfIqW8vS
RAGOWJpM7dTuuFqhGKGxrHxv/G5SIXNOj2suYY20qH54Ewn6LOVZnlMqbIkTmh4a
0MDs92+59Wssw76x6jXGKB6RLGvz
=91CZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '133a92a5-d61d-4bda-a708-f0262541a06f',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAufkZETpw+fgm1np8VIXs+bevk56SG7B6O4E5241CT9a8
dG6j4kslwb2LoqV51GEkXpUnFmI1mTAXtGcVjpnXOO+AadzCse/HvcEHT8vfnn15
hdOUFNhFQG9hcmkdRYIn23TWmoNTWUJRO5Yhf7/6Qy5zCaztwJHsAH+fPztTafRb
r4HDag1leOzgeJBhRgG4992gZRJfoxr9H9P+nqyTmtf4SXyhYuCHVNhX9TOY2E5u
pQ0XOharOnscriDKKbC0LImRhHI/w6wqEZqgaXRNA8ULV+vrDHFjoq8a10PtCfqp
Ei3qKnr07kGOUt6WTkjScyp+AlTjskuJtTyhH4nBEVLJFMp5DZIijrpYWFSBaJkG
QTUaFl/cuyfmHJMEb7KRFR+gjF6ci8QqMtyoC2tVvkU2wHgTiZAOOtQP0mfdjxxU
/0kMulA8Lm9/W2BsLYeuncNYCSvjUafpbXkoCtl+d+6sA3HTu0O/rxu5AJ1+Xwj/
yu9iYu3Ug/747Xm9V+jwWubMbAHieM+sE5NFx8xZkkZVF5s76mfpCTTC8IDMCtIz
lJyrUPFyfuvdcPUhS0Fs/jheJFNOlLlWqwgZZlD4NaGYU0iCqlCGs4GYDMin8Y0s
wSOqQ/ES39vQXzKLvUYScEKASLB2z77vcl1woFT/BBmBUItXlfwC2d4+DAx+bODS
UgGvyJ64Q7N0D0KtEMWTtzBsLfKJTijM7w0EOcm6Lczi1QFXDaDO7brYAwwUVN6U
EQqxIoBlcb4+UthM3TTOcyi5U+bfvfNbAXobv6VdBqcfJ48=
=Gy0q
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '154029f3-c72e-4b54-a7d2-b48d0f0a8cb6',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/+MNqA9Ut1kEf1OHvwvNGKZpPJo6q6frAQx8FQynC6FQQf
hEksQunXRTi98VvzmkPXvGTudXqnnJH6ZMDL8Mcrjy5aD2y9sQAg4n/ZyErcRzlM
ASkCq1f5wXU2wuY+oI1e6AXvS4MmERB5SiQYvogR2ScvxDVy7KDxYUY2YY0wRQoO
+WWepIqhkZ5QHfyIcXalFFulqXt9SA/afe83BEJ2vYOwL+ferDEBbF+aU8JI+hp6
q457tD7iM0bHAE7LbEGAtAk/4hCsHKYm0EyoDaoATZdxcJjDqxtCWEIlSAaApQGv
JMgpRvVFZQOVO6vFSVSoetQ4X66zfyCpvUYbGZ9Txl/WpQ58ioq5646D07A5M2v8
64mbC0C+P26/53NX9UKMo7grRDrB+hXf9b7n5/J/tF8KJduxwxs5GhEP6/SGuQPP
CkiRL33gAaO0V7OWKxh9xWbTShHQJ0rxmKAOuuo6BFL6p30oMfvv0N2wosJxUPjH
xyZeOwq/On2qmLQGFJ1ZtKJ43J2ktRLtjEuzHWhPLTs9cvY7jb5f3qF6IvDdhj6o
7DxZUaVFoJXS9T+aZJu4UfmYBrZkrnCvbwuymze2ry2jrB3UaGeFSJZbQT5YoLtx
eQ2yhttQUGETtJ8cgNfj+BQtLi7WXoSP0+06yQpCwAItTge8gxpOtnp1buFJkAXS
QQGdBHWi7NWPw2cJVrzxoyG2dUVLootR7IER1LmuW1W9QXqdSlKO1fgCdHWjX3t7
GqHXS5hWoRJNyVhbBJUKK1t5
=Whar
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15a776c1-6faa-4b33-a587-c98b4b5f0485',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAnIFJD8ui4wJNYWPS6UJWxsv4gMHdJtIZXXPxRNM1xQvp
jGAOBLN2x7kTNqd1Suew2mdoY2aSpvNcGyT7tF+TB/+dCYbS3uXj103YkAaLMQLo
yYgPLC6ttNEyJZ7+sMXFPklZlJ1eBN/YEK0fo8bXFSIm5SS/6wVqsZF8lc+qovKE
4WibbwBX3yiMIPZt6MMDhmAM9snU7PoKS1k3RfAeTPd3+TP2iYiFAudtRWPsyLkU
alGckdkWKXnNztxhjr9EzVmOztZifMEMCZqhhL2q1efd1ACZvRTFdlz8vvjUL3J0
kNLk3TQ97qVBJXQWf7VVCRsCSvUh2xnemNg5r0VLGUpc2pGzhsayw4J1Zqe+y6xX
AvoA9+TyN3jlqEHhZvqlk724/obO8CcP9RGsQ3W9MdaX/CNkGi5ZCoCpkAytqQbV
2WoOKn7utr4VDnq/PrI430CCftCMjadYWcPQ6JuJFqx8EH1ao7QaGfc8f90AxMvh
9mWUEaxroTk9tQxWbJkgq0RZzUgCtyhsp73HfBpD1+4/IJxcLehSou3EBB3XEp+N
cH7Fwt3u/tMV4T+6mmR4Y2Eqa3E75Mtlcy5pcg1KVpI200Amg4d8s1u51bIINDT5
934VlMz+MLthqYqutxMpliySq0cW05dh0uo9Dr6/x0yF+2FdIr8m8Yzlcu4vygvS
PgGQM61G+ap8Qa01o2cPOhx/TsxKwLu9rNVmi0WnT7iGfu5ona3sFKrI5/Ii3N+j
wvl94R8vmMYx4G8jVtUi
=nPu7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15c6b7ad-3014-444d-a596-1fe66feef472',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9HzgywVKucFTJ1+Zr0rUJY28BBfI9M/Db5ANSMkU2Dq8S
EmzLM23SyFW+OMSmIp8IhMjbGk345G9jULZbGCMSEOZOIxy4mxLJl7ehso3f789N
i0S9xlaNn0kH9J5346iRIe0RUL2VH2p957Gx7zoxK2cVb52ZjeizkNgoG8do8AdM
xvHmoHV9prJl1KRe2NGeZIgOBiwrxUmW0lczAdIU/h9EI7uuQ/FjoM0LfCPifyaa
cTI9yVocSvmxPv7XkKIzBw3PlngnHMui14pymYiKGLUsKXeDyoFeSuD13u0yp9EV
yfJUGR+gtkZG6WY6zyFaHA8B4eISaJG5hHPisqvaUpS4v6BSPHW06WlXY23e5EuO
sLzJYH4R9KapQ61iDlev6Q80GfmphVrvfj0a5bBsd3ycq8Wm3pVkSy4RvHG8/fxP
t5lAk9wLXBPwSDsaYMcRmZ87FIDYA3gWmrHaqkruWva2SdwsfvZaw3vCc3RV2aaE
EmTT6JpeILkvNu59AzngibmyZnVY5g+BnS5OPsNTCy0o9OtQvzxHvGvaEYvOVK0S
K3MH1fP5hQ2Pig+U+YY8be0hPpV4FNYMosSPfG91BxKchUgHY8vvpAYLt9qxVs2Y
SdqxwNSZsQp26GRu+ziieSuPURTNMzIozQA0WeLGQFJQ9/bCi6mbjE4YKWjz8DHS
PwH9TPeJMtPG9g4DUFoHugr8iXNRm7cTAF/s8w9HpGcJkVvONc0gz1UxjF/4N1v5
cYXnhF6k3dhrAeVnyR/K0A==
=y4Lf
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1719ba50-9cd2-440f-a641-485e5c8d3b55',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//e1qvJ+M7o1o8pYTh8rfm59FQa6k6mWsaqIrlcPDh+9ao
WsevBO3TZ+tvsZtF3aHtVBSniwgX/tOWAIwraT+4mv7fxcAV6f6BbOR9XVx0JmNQ
DGNpGJ88tBQtHyGCYosZeWpioT8qOs0S9+HBW1j7LUv3bZ5969kOUvckDQRwHGAo
0Cb661qVF8noEqRDk12OvCfjVARHLcubYRIBgvQvRFIAejypK5o3/nJYKG8FN4Ed
wDH9OaOdFpsvrwfTFYpWswk3QZ/AKDAJYw6NYXI0NaZLCnyibCS8ACbzu6Ud/kSG
Zcn6Ti1vxSrmHwn9Xhip6mHkaYwOsjrDiDw8xwhtGpGrlX5E6/4JibjZ9G9wsS0/
6SaCJJHkpicgFsCvv6ThXZxF8HZro2YisR+Jg0R8pjb5InzNPa5XykO1k3tAe5cv
I+Nh2OaJUZ6QkWJqz4Mcm4cs6s1mH64Rp6kkhvH6WwRDMC+6vn4U7NqgMXafljYM
BaDvnBQ5Y5hn18c5J1qirw1JzyEi4ZStVaBHQv7jtsHWDbuLMQSEvgff/FJ7SiHL
KIz+uc5Prhi6G9hKSgvi8sTALGftsFWTRppx6T8Hjt7mFQgt2shUV6xw/Zd44mLt
6CKIeo1w942dpVDdsf0DOIKQHsZUutKxpnZNz6VMZoG2mSU7BaUPAcy4Lf308iDS
TQHQFRilKNKjsLWZWGHojHhQig6brLdstm3YDHfyZa2ZFv0Y47zYfgQ5c34bCHNd
sRTGw/yZQw/mibvtTjbl0hCxii+mzSj/pcJGhPZv
=9gLc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '180b1c19-f780-40b0-aac6-85a7e3bc18ff',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//bbWtC/tdUcn0lE9PAnXl91BmwL/7NXOq51xbouputJVY
myYKfdN7qdenWDFIfjkh1PnDMI3ScABgkkxDY03sXa7sI0ckDL2CS8R7tMdBdbG0
+aIwgkKNaTdcO1Fcyy0MMWOG/RSHQ8HKjr8+wzgqMqJZCCYlzsbmbuJk0+0ViYAt
Y+jNsOLCy1cBEIUET1UyqHhWH70hdNxSKM/Hdix20qR3aBhzmRJ2+JeL2rfGHpK0
5rPN4c6oJgV/sOWXpSFlZ4cP8qPYFe3V9y7uI0Wush7uPWk0xqKxUyVSlBJ+4Cnx
Xby+Z3+ewttyKTTl6lVc+j8bKPmPxH+Qp/JBKbr8aWovS0aohYzYbmyTFqxRNdbR
aTfQkQ/NGOpElxOwnQCg7wjGRUmIPYpbx+pIejn7JfqvDybTpX9HauqdxD3GXYrx
Gk1q+60ljck24EKnz5/z1qT260Y1SQ8X8SEgPLODDi4HI4RMmSsI197ws9mrzak0
MsdMqOA6rA8NzMnszXiW5TQJcXVpRq1CSJa2097e60+Vh92Iy3w8ToHo10ibO1S9
pi5bU5Ey18lfkBzbvnhOh219spDB60LRmMyXywoxq6Qpw2Iwb2Jtd2A5GlxUtE+T
vMTgSNNacNmUNhpx1UL/V2f6pCLl4Oi3lkYV/ONpAFLoz3RCpaKMTn6TfTLBI2XS
QwEljrvByYbd4N/YjlpH/M/sjfeQ7cl6AaYyzkc/dQnxDr4jhwg53l+LnuG983U0
m+B40pT+Rqk5fJ0EYZzuvQejMRw=
=aJTb
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '18a3dbb3-2132-40d2-a934-fd6465d05e3a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//UvI0nD+XoNjloCAOXibxS9RfxVUzD9CeZrHIRAosQXhp
/2NAPSw1IOF4yYL3/Ys9o646SxBuXY/TFJouWt6GBczqZKrYWv6/EQKJI+XENl55
7YW5cm3AnpT1wyVqUFZo405k45MrGoiQ7pDNy+pdHT3cts/7nY0DO1aqvxobExTZ
EmwnnpxgEzWHyiqKPmYpWW0b7EROuUDIjZAe9xxgqmXO1IQnvSJtOlNw7Yo47HaW
BuHrohMP9Wnmcl7rPhODWgt+kacsmrUHua9LiV6bDkjtwUPolsTEe27WKUFKvos4
HeGhCZ7JcbyHdtQFH726FqzWDsB60+yo1IGZ2SGorP5InRfbPMoys2upEptnXB08
/N+VTSXIaDjSPMBbAEZ0eX/nH1ZyOyR8xkKze9Bh/MSK0fIROWmkoAESB34x/jrT
arhfEuFLDkXJ/j1h0oNco8hVI+cXi3kgsRP56MLUWstva/diqNyXo3Bve9ObIxPI
Uhd7S8EWJg+n3zhpmWQnkHjqegceH72SifuNsbBYh1M1kPTaaHMqcxy+RQPhvq15
7jFyIDth5JGo/Dibb2bA9yFrO42aFhHmb+I/KHjQkKAc5CHU/xOpgxMCBZujtqUL
Y2M6nTABqJYTZyl4fZ7Ce1fa7kNwzUDF4FbrCaIKPYrFvU/zDChUvPjYDCHWXULS
QQFC/HXe8vdyU3lK+cmZ5toJDNZSN85FeKg32/NvcIwtufg9lBGw60xlIHDop8Rj
KMPHGOYyNnoyN2nWvUEORIKk
=Tyvj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1c79a1cc-4b82-4daf-aa11-6c080f2f73e7',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//SGOQUm7KIWyrcIR2HXtYhGkNRynQYVwLUY+VIlytdbnb
BlOvoz42izRTfXTNa4reNQFtwhDdN2ZOin+NO9FYnHOY0hmi44aCv1/pKYdErJNU
gby6CwJEQKspU57b1b9OzQmsFw5Zf83cTogl9JUCkXesBaVm6nlBO+Z8v0VvgP13
CVq0YuT+FasLvTgQSAWORCCLaQF+//gpnXw6SVYXyg5Lnv4Ro2+wo5wqLDzoRWEx
SX5ELJMpM76QWM6IlA/PgW+qaNOCOAdQDiKPwdb282YsGXHCXHXIUYFslit10LvD
65davL6/GxE0jKftGSg/dy+ZIe4WXZznN/GOCiiUS6ld59KHjxZ4ll9cvG8Fv8/F
6M7UppdvyAB/pCf3UlzIvsj/s4+MAVUaUG39MTUVBEwK6bcFQIsfNxLeSA8GI6Lp
MkCndAY9BrLky1BmqU0TWzSSAN3SF6RsIWCrNfnsFLU8LGTXoxAiEEVvls5I0ywF
XrgurWSuW7s3/ujEAbsIrM+GRbjLHBYh280enRmfYib3+ySR/aLWJrVq/OiL/O0h
1FmjTYvOHJG+50YigbVNKdxfz2xMYhpOXmaAC+UWE4YW8zOtW9rDPmsee4ErdgB2
+yiqXGtkl363gnJjPqA1gI6A6UC6odKqQw5ishKRUXoG4vf7Tj6iSMCpz0QHEfTS
RQEBioeo4DWpCVbfvCahLe0VSEd1Ky37qz4R6PNumuxgtOC/Ow+69Put8bRYg7Yl
kRVQPSciwFq8LkJ3KnbzuF7lQTXiWA==
=g8tn
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '239f4ca3-4c30-4e81-a500-80947e0255fe',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//bnZasQ+YCSTv+iaNwGJ/Luo0CXwwYjACv0UQmultWz6b
v7TJfwDhW36bO5+yEN4Zwflfo8kyMMECzhn13oAFSzWQqMp9X3cipe1hZVYl35zx
L8kn3GM3WzUQ8x7Ge/tKGK8mt0DnsU+frt5ItXAoYQR30NmuVOYbsRRYBwGlDhQu
sPDxz5NzvAMKfAqqS0Qw1qbPC7FsNxb3TooDLRciUVkOox3t0B9JBqsmKMcjQ6qk
9eNPoK3cqhYIeQkmB17JgXv6uSShl1G8I4rrnxzioK0S52Ecj9hM187ZEhQ5XFW1
fY7yQVTrozKs5gHvn90MVin3m5mKxhhkpDtJezJZ3wNOirZFbpmCNsnMSPH32+RD
Dq4IYxLCqU9OxplwYQs159tuKN1vecrD/cRNYgTModGAbGoxqTVBkjcC9M2prwAF
LmSqGb4K89qGqLh//5TbBJIDtThOzQYuQbveWLA5b4cHb1ccLKMVl/HiYkG25GyH
MpzE/Ju5xhSL5Mzx289u7V/IWDinXubq2xArZWWRRO0JnDFB+MrtnzRkQDp3Cmpk
fNnjZ5vjEd2zLkqDw5zKbSwkY2uxJKwNKm8O6g0Stz8e4TutDA126wko46syuOCJ
XiCLK16N0BdhDLiVuiz48h+xV2wpAu0R2cQpfKnX7V9bw3y9EhL+kXHZrgw8z3nS
QQHkR+H+d1ooKKxjFMuqF/3Hd/mJWfENrfcFv2ncTg5B86C2y0apw/Y7lrZ59DW6
6YbE+3CrNE3Vx/XeqeOXDfhM
=sEBy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '27da2b88-10f8-40ae-acc4-786b8b511f0f',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+NuzaFec30py1rZys8nf94T5l3eNlPPyI8DAuvGXMzAMI
DmKDaC0TgB6398RPsTfxuPwl9irC/CytE72sdR2xSEIzz6xqHH3fcUlli90St1WP
VK0LTESZmaOMQ0j1sKCJTevduvTpbJG1qMLvh2O7h5rwVU+2q1pSd1lHfTgsl+Hw
rrkJBv56x11oKDLp1Y6rBuV9oVe3OtkPw+CpwLetZ6nVPt/zSeQnKYpW1jELgcpJ
PSp6WPvanALHO5/f4V98wwu6LcEWngjrxUuD7IdzTt32h6DBqzX69+5E4qJ8uoYs
DOAva96mM9nvExyd1p7OGOglaNV1v2S21jn5+4T3tfz8cdImGyvFwVPrLaD2NC+c
ubaJZRXNieSAO7RoWHkJJn8nrFBsiN33AOjZlUqfDGQb6uEqEM3L/NTvwKaofqXw
ra3J1KvT9R4mx+gLGhrjw3ZyOF6LNEDz1MBp5ogCnwEWKBB8R1TjOaP8Ci4qFSOX
nkusVD4mUWtRCNOFiINwD362l8k8ye4HgijqmdSf0Hfqq9AOsysHYGA03c2S9tgH
wAi7c471mLuBmXC+IVXGcrg9Zsw94DKSVQ+hIUMWdBQHq/Gw3Ru/9zeprGcugqQk
U9Dab7c+qD4zBeIan78rS67WGLvkQqRxKx3rhaUUnpxJV9v6MedatV6sLMfsBX3S
QgE8VXWsp7Zd3F5gdeJUlM8Efar1kO5eum7TLVZUzQcr5A2GjkoJYIxWVnyTXC9O
jG8ISMA56MnfDJYOdeoK3l10yA==
=w+vF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2a799155-dcc2-4753-aa7d-83b16381da62',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/6AyhgtTfhq+ouNXlbLXBhZXT6jhpn/gNlzj54AnasRE07
U5A77RlFSgt+F/TL1FQwX1nXi70PTRiNwZdeBBdmG0OKP/cVsV4UbvIXnU63fUeC
ENU+RGr65y1CLtRSGfYKJ5eBIPyTjMB86HcuRh1M6KnTx63OFXO3nF6rIQrBMi/p
kUnbtCNPjNkBYcmA0S5/Jum/uWh53a/ATU3HVjkNX3PeQDT/LWKEuhf+RmpPXC1q
1zs75otmbxJj6sV3KEjovIGTC7JmwiKLcWUQJpZ4VCbJT2fi4wK+DNLePTLiJONu
v5cuKEcTtqouklZPxGzJl+VyhC9kmDKOb+iVKnVBPFKymQIDNUxUb1GyeuuuimE0
j9sxsDN+RtbRbUc7u4J96SNUkuEZEsHA7IZKLrtexhjrXuZ4G86FzD0o2H/DcINB
R4pFA7iJxd5LjN1C23X4WponqCQrSO7ea5DXG+LEbGUgGxmFSrAIiXGX2W38lOrz
EPhkTKlwgb/b6psMUSvOmGRTimV0ziJ68OC4NmgBSmtIoGeDtQetbkaEogvkksNT
BnyNfW/k3bGbkUNlwSE2aeUtwISo8ZC7+s6b7kSrf5ONeY4pqz2bXTNB2K5NY0f7
K/FWCjQnhx/9qRWwq4+06HvV2+xrXdErhE2fX2pb4Exhwsmt77BspqPOI5CyN9zS
QwFf8OtAymiRmOZ4ynjSs5s9NR4zi8V6hbapWf/Qr6SZDOMaOx6FRKALZ7VWgf8X
jLXLC2MmfbW3wHcxZD1p6EluVkE=
=oMHz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2a831386-010f-4352-a27b-fa5747555641',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//e36bsEGPe4zIO6b6B5CtbHu43AX3x4/ozb9DHWHUmZco
ssISlUiUqyDv2cJKUCxDoYtHwdiC9ADDpsIW8gvoU7II8vjd26ELdWRn/aZKNCKY
ptXQvOnXPkV8y3+NHwnvryLG/r/BYHxYYMRQLZwXXNp8EsYpttE9XrXOJ3j6hkZ1
j2Uky2lcxRKv2ONriNY0cvqyz3hb9nfLMeGZUDjA9ol8rYNnQpsyw266lC3ExuMr
Mi6l/tTq57ZFlbFlV8yXhOcyA0h6H5r0zeT3I/hGaGgQozAx0NvcJkkBWyFHxsOf
B6oFfMEzs/IRt4gJEvaRExgxO2l43DPrGfkRdtmJ/Mu+tf7XkEYXMe20wrHPYFJ/
bc0aKKIfnmcg2IGMTJ1qVWqnRmlyFLvNi4x+EKMxBbNWCgQpDAGizcJkqUUwjFVo
43Rh+Lz1XxxbvRBT0VODKwZ0YCSgzS5wSv8PoqAW8oaMV9WtpOg0csvr/MT9DXl5
MtNO4wbGiaFAZ6XjnpyWWmPXvfgStZx+xecQttz0eRNJvujimNlGBiIPEbNMXCQs
bwG84lPZTlCXmwcYdV9BuRaf5PnAETw6prw4XOaxKow+io3FiTwj39w5eb0Q8JAU
JpCiKn4QjHJUC98eAfKuwl4rminNToO7Lq+HV8gYbLczZ9T0GanYXtnbb9F99B/S
PgE+NFCbGnZtQQ/83dkK2VxWHAamxbrOfdJjpTuMPrXRyombhE1QeEXUxcg86zNo
j/4ROtUlGoi/QIYMPRxU
=z1Vr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2d9acfb6-cd93-4e47-a6f7-36d7e2fb9a01',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//VSsCVcSg+jgixtfEXVDp0mAJ4KFHjffLpRbvGowKn2M7
xYe7fg+XSiK484WcP3bMMnQuqp3HMQ97GgSkxeT4D41LMzQLk/qfIEvV+aKkjKN6
aKrJavRV1ZABV0HFgMRcyhwu7hW5I1JU4TO+lfBNYVmcNfFfDYXUZvRcISlBa9LU
3CoVoKpbW4j6LwBByQsTGiouhlLPqK7lsHgDldJuy/aQv6RK9x4IDezB+75h2PZF
M7lrQBzHGi1LTMRCcv3J7diqseste521RYN2jLGd84HVWWk472oC3p4l43F209Ev
O7qaEBQKzj58FcqnMgBVLorIQu4nAnXSuBdovEHXZFdOvzguit1gN6VhlhUFEwDh
xvy4vK99xc9PN6+nLa6ilmFZinoZVVKMVM5FfNxNjBOdoFmQ0UVYdOgY64Q2PAA1
ONRN24Q3yFqO1RuO3yUhzVj8+87Yltq8MGPbcCyAkz6ksgrOjL36mIKiTXqi+fTK
YyNSDlDbsTtsxiO3V/vSmLcRqgt+4scmY6HpY+JO2Jq+6C3Izv1QhsPfu1bzC98d
4IAI8ClDxfE+tJOlIfcRzR1FPLon3quQHpHlrpiuzX6OpOZ6EJknGfpSrZ/EIHF7
QpxJgIUvwaCXKK5fLB42TJEW83UFG7KZGMMNk5YVA3hxdwlFQAIRC0eagIVfW73S
QQEnKy2xOiTszpW9mkI/DqiHYX8RTDq7xfEBdfuAANsVbaUAq67stjinj0KZIjE1
SU3X1xJ0A7gyZ1IktgjEFX3W
=68i/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2dccb86d-cc05-4308-a791-040759493cc8',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9F6PtijeKyKzKR9Jz3ggKEo0n0JdrbLCBER0OvIr52aS5
d0iz/bJO/eNdVCMevycs6a8NDIkC6X9lQrngkGZtP6QFncamycWiuo+wLuk92kHm
RztAmsxBRBtfw7sPReDR7UyJMs1PneOQKWVurqIZxjtqd70K5RU5dDbizChQwzB7
2XrvjRi3W4cp5BtJ5fQqduaihBL2DaEQVPLLua9GwUI8p9WMbtLtihAtoOSlyj39
4TRkWMu9/RY3DIx88P5UewJKqO+D4ETx+xM1+uqRVVSaoAOOKwLam8ZMceG7IBT6
/I1ZR3r4a8v+NLkvlZB66vqkG00ouKb/pj82r413i0o/+PZSsww++j7koAMkT3Ag
einscGVqS8MJiD1TPvfFmyUZiLcPgzOhQo/f4gRr1vdexU0RsvwldVXUv4IV8zTx
JSsNz3YVGIuGl2HKXShQAsq7iGVUnOOyriusWGt/M9sil+th+yRIzz3k2R5vFFLw
6c7TFCx+zJWw81/gLYS9aCk8jRxGhSrUJQVDPxnY1TpOvjszkY2hg/tSsDMR0B66
w06AxngYb0VeRlPdXBMC6kobS0IjY1l3VAxrtwO1qVZcoeUbE8727ba6bdt45k6H
/n2TwT43GoXAmKZ5XaaP2HBeL3KwQLS/h36CRIaFUonaTxDSUmMIZ767otGlGsrS
RAFgN3toBEDnFIVp8A5pgWbAtCq8N3lgvV4n/MJMqyf2JmDSah21enQsiFrvNif3
h3NbGSKt/lZnX4FAXHo8rzYaGdhx
=aGuX
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2fa5f4cd-43fd-4c63-a41c-5cefa4ce4016',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAkgblmcXMdiSXQU0ZEY4OjqW8KFppyejl3Ab6L81fQMqp
/F2q3scd1z1tgvi101MrMK6N2Y3Nif/ACSjc2hqjI9jtGYkkZj1sLGQ/m8nEQq1o
nNTyhiE3XadAEHadahFoq3pY+LRK0a0mbPH29bZUR2RHAdxaoBfr13imtHxKHayW
XyeiUwJ7eDDbll67NwXkDU+TXEFhEZFlcNTyigmJhuz/Y1vP28rcG1q2VBko+Lom
DBvDVM0ul5SJByi9ukyvOSi5vpD7BgNqBrrRP27i4FCSiB3FAxF74M3uHKqkSGy6
NnjY47ueJ+4MWsITGAhmg4B0Rezh/zDmteJpgriS6Ne6a7/Nr8ZhfTjc5It5cTTf
AYNv1D6oit911Ia7nxJhA48Kd4Zkyw36MTwYPQ01IqPZzCTZp3N26xKuljIAoyoa
vg5HZrIbO4PrO4AfBIwu7tX2Z8pCvy6N1faGWH/7mA8UQnyIi12XHYK6IaEm3RUn
Q6CyAvf2Wtxe0lWzv1cBfhIIQlzpLK+qgVTQEjj4dCW++astP5/4s7U1W2Iieued
0s4HwIA8JC8G99sLSa9Ev8wBsMHfl4aX8DRkbtHF0Lwxc8tuO6ou4Co1Aok2dDZ8
VhX3ao+okbYA0IUXMypHP1ueKecLZ7mvovGNYPz7xJp8ET6yFO/ly3L1VgxJ0oPS
TQHiIz+mM5BfQ/YpDUfqrl1BakiTMdPmp925Ho+mZZvXpn6h2dNe0LNgkc3MM9Iq
DsNDsskA3Zgy2I1+MBbEENk/cN2DBIRqBt9Lw8Io
=nnR0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3114e6e1-976f-438b-a700-5c1676f49ea5',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//djE73WVlrWKvyotqMQF02Or5KpcqQrqQDDMprXObZPP1
SRaWW9I1/1LgQ58xJYGGNyj1VUcF+keT85uDIO8+Cegp0eoyhnDTmAU6L6EWfZ8r
quFMqVoijEwQFHehdk48zgOYGf2aVVkg0Ox2/VDsp4h3+TMYHWnrnzIlhVWZjlzh
iUct0xkQUADOZ/D7eSF/pmqRoXuUc7XzGsbQSuM6/QQBWYHJ1FuI4dQjsWMrJ4SH
Sm1CdAxqVF4kJvp6bRRCL41WipiyR4fJhwU0kOHTLDmifvpFq6I0ynp6SqGHbL+c
HsQhMYDXbwThfLDtpCoLjjhkIOzkXWoc9ZjUQiHU7KSLRKq4P5neYrHL6ppWPOTd
zLoCgQDKVFRYhZSGnacgYHr0KNoCJwi+Ii/c14cEk2OieNhm1nK9n1Wwd7IWaGOA
YBf84xj/RQnft/dRtBAr/gfCHU8G6KyFvWexqYpNTVTDvBu+7cYNg1Va1tzLzn9E
FkWebOn2B2BGrt1+7zzdxh8fFlDEfGpGQ6jVsQZgG6DY2nutosfHjaqHnoyhdnHi
deQkGoHGcLAKqpgdbJlZvh6tO7GJ/tkq9E6dfHGbPOWfEUtYP/++w7SJ3CRBchOe
yJ2zC+SnZe1leFnPYdvdzsFD+E5VXrGZP5HBXoGpdIsL3y4WWKiyXL5cFwrw693S
QQGjnp6BFx1BLnNE2HdN0xdZFMc5jcgVGutOYvEA4tkdDqolUBYCPteXYUvLiv3C
1oAEJWXgPYx56Wlc2MRk2BTH
=F2h2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '314dfa78-e63d-4aed-a637-e1ebc6091c2c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//a8cvpL9Jd66V/bLwV27+mhpafmFSQ5Avs/7qz+Fg418l
xWpjQ+sgL5oVkEkd/H2dUH3JMq5NS28Z1M7j8NNZDTZIRrUntTnEDcU4aWuVvCFV
v8Vgju8V3fVP9OY5VO+gvVpH9mvNBYB+W/5W0OY7frIneXuJQmouD+1K4NI8WABI
dQZUBL8jf1ICl9tFrJyxRl+fyEhLLhYpeF5YRglpGL4hZM02Fayyris7QSdk1Amh
bW4c9VGFZ36Mk3Ou15EftSL4nRGkivUuh08C1l+6SZe279d226U9henhmIyVeyu6
ZWOC9zXLAGEJouuf6t3RZOB6q10vJxF8zM8t1wN9f0RvxprofT5BtqdXoDwLraHw
gEOJeRZL7SflM75WVP7PrZTp7FkRD7A/155iqgTDTG28+v1w8js0+3vBReX5dEQS
55560kw9L+4Ftm74lDaxTyeQ9ubVAr67JWEK4eTKCBFLFUn+onw4oBLPmzMhYbrT
EEbc0+r0UhDEblXigw9dz0IrvBGwUe+kZ8Wf20ns/+8mHxs1H47mV6/Ap/lnYTiR
5uLVqBHGMFfT/jaXbblff6oqv/ISoJRRY6Ekt6QMjIhK1VhG6woxRJJK303Lj5EZ
BiGyxdojU+xdWRXOTZVnaRptkayfCDOq8lk8Q2RQSZ2+W9V09YUDDWAQEz3ONlTS
PgHYNKSP8afrsJEqrCJn3NroYX2TulL5dhrmBsxuHlZ0E0oVl5aj+6EB4lozZrpm
bURicw+wD41F8ED2MYpi
=Kbwm
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '31f33827-b214-4d11-a970-e13f95d546c0',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAsnweoipZ4FaAx0OKtppZTPW0+1bY89Fyw94RojO7b7Os
ukdYDZNOplkP8HbctTayT59NENQmRf1xLsZXUT2P6TR1LFHAw/pzgXi+3JWdJ5vG
atCRVRscZ3G3gITCSpdimrijbpAf2X6pvRqSxTmPu6yHlfZALISQ68AY4HNCohKe
ztSGCWP62LOCPbunbn4/RMh9nwBQktZBbmy1c0O36O0gPiUVO7dOuFBZ7uKvy+1A
H7eE333ff6pGr28VRFKv8/JdN0BHpmyflykKGSHElUjFYNkTWzwTQVoSJPOjDQUf
liJF1V7FizrOruKSswE6V3qerLLtmszOov3MGgyBAKeiRs6lD96N5YAORULh/pS7
XIospc2sJKBn4u5QlzgTEjm5ZzrSlfXmfTeGqCMVEUGaJvTgu10BwBVFroKsksaS
BcEFjjGk3AeZ4WEErAOcLKiMrEqTacSSsdJvLmHt9PwL79cPq0A+4EO422BEkuqz
C/FCF6SP10ICdBf98kHYYIW8x3jBWyVclSLnBl+0tdOTTosMEWlWDWHAUjaZMdbV
cLIjo2umRXm/XmNV5QN+1B/PWgQ8TSEgFNjkbc6ZkgRFQSDUEcahowAkt1K82q8x
6pw4RxgKleo9o0N3rb0c3QInpKdXs6+IV4i9wmN2Ttl5lBUe/M01kBSGGwKxWWPS
QwHnpG5Bxz/1FuMY9OUtAGyOxDaIH0mzVCItyTzvesSdmB7vOGYecn5/iVyV76UP
csEfKD82oMWYP0Wh+4UAg8xsQrE=
=5LMO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3758d5cd-9a42-4a01-a36e-60424a2c0246',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//a9lO2QjMWO1j6aNJpbtl0egkOl4+SgmpSEYu2IpgwfMQ
Bz3bngL/SyRJuDfKwlb06j5Gh0rdOQd+Uo3QNz9P4zZ2AHozQ+UwDUaBmWydnBCR
hylvV4S8+1SN416YxIZ70ejAmX6WJ8OF9hdy16gCe4WsWw/cOCGoYfM3fmfCV5Wt
U9RFa9GEnzeEP5HHNh9ZCNEbCiIBTzm7wpvVWS+bWioT4g7uVA/JR3WFl0mnOwEq
p9g5BIQdCjgPDT8Qs5e5HGtzliZ2VdEu1CIdG/D6uIugX13BT7ZWweCyK7vsEbRz
pm55ftZfw+IYtanM77GB3CgFWEPqNnwpAXo7wUP7n9dCgrytMw3XGIbzwz8BbIUG
TK07xVomdYh3cKxeu02vXRefBEF46jxeCqQySIvnuBOWBqOzB2OePNe1cbyaATIc
vffLufWid2T1DpbgSnXzCXpIIHCnAxmDsB4rQqSB7cOuoGuPu/EsJUs5GVBXZI9c
zKqaTO9zJt4o7jcIJ8i4gimaYNszimJ2GCVidbbgMk6aMQsXlh5lXTYe4w8O1gId
KMxiMhqE2sXRQAN7S0nNf9kuhaeS/lNXbX2eMCbuYqoc0/tXurHkoueACIvvCAK/
WvyO1wr+8gHlZxP85DfG3gC+oMJJhGjkdQwWRsfKCSOxTfdX59ix4HHDQ6+Tng3S
QAGe2hC+QGIdaX/9a21Yic4Q1gW/T8kZzbeiSK7aE3BTw5vFXL29dssFW0mS4OjA
AT1Fshakt4j78Vm/EuM66CU=
=hvQm
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '37ae7742-4e13-450f-ad26-8222b0b97c66',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//cOn8V7COA7MbtLQG3VjZzuMzbRpR8/sAb/URJOLGQJzY
uRmS4y/1q1zFEOkT9Drs32OK3BJajnRpqyl3ZONdnrCIAkKHj1OwIt6ZApJwdtA6
Ww1uvVouXotfQNutXh0PNPpu5I/W4kIpSygh4bZz24vsy0DEqTQa6rUlsS+Yn052
c9N/ILcpL6wyCJ3iEk7GDyq2U5j1ORhHg4QYeH92kbJPO0/eEg17qDoxMRDRMn30
ZrSLhmhl+fkQ/K8OAihbyz9EDnylFsbOdqsm/3yriHvFMi6fy/BieJPCtNfZBLLh
CNxCuchxCQjlskC4+yKQmf6uN3KwphpLA9i1qHyDTQw3WVJ+87xbZLu7twpLu6B9
PDlazA3ynB4bbfPaC7BWWKnNdzh5r49xmVmecJ7evFXjwCDeS8KLwBvsAMaxvqlL
VeiH4coOh2FlZ8ygYXKEtsSQY6ZLwoStPW8rCy5Pj+YiljEiEfc2BFozX8SgrfkJ
jIxUBruev+cJVjC7G89n4Zhu62UP5ZR2sQFCpPhSEDNl1ElcxxmGd5A9Rn/u1QSw
cxaHkFTTwlWZ2o8v0WOnxhnrjrpR5qLmhnMPweApV4H6iMQdlwJXOVWbFURk3ewT
M/OEtPDNLdYkh2z4w34JXyHoBD2caEetSzPrexjGc6EcQpYpVo9qUOkmYkisE03S
QAELtcP1KCbPLBjNXcnBwlqJyEeFH/bNHRxsdy6svmHCLgu9dkLWgcs4pgRtmOFp
KqKajT+j5X8vsMdLNiC9x3M=
=pTzN
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '38882a5b-6003-41d1-af0d-7a44afccabad',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAhB7X7h1hFSNue2P7iDScuClqp8NsNiCm2u8+boRTTuwf
GU2Du+nZ270i/A7cHAhNUTrtbqXIkEBHyGvEqoyq6MWWFum9YToQXsO3xoWwty9F
Hjm7G0zvxaSlAD3je0l3/mGcxag+nNNzGuh6zKFNP7HBFhLIcTMInb5xdDA9n5Bq
VdmAl1AnCJhOAO0V4n72LlkukL/lR23vHFqK8xoBV+/hZ/uNUBI2jlO6QqF3w4Jy
dh+C0+oz0ToQ8zdxh9wYapKYjoqtSNqgFLA1qGTzr7TBzWqpftusoF0adQiA0N0U
6R5PEichvLvnn4EeCYMh16TL7Ju+VcSwuXRh3Wk7cfUnoI2uDl/Xz7QK2uzZyukC
X3EZ7fahFpXkYkpK/3skJQ8V7Tc38BNLVWvDdOQqELBt63K5Ky33UaKBYBrzVYNT
77jhUMaBPspfEYYuTiGIDnhwwFTCHYBrJfkg1RrvPppb+pAyaT1iNLm9SJ5392Qv
8DAZ/Vra8SSxiPMhWSFY84jRw8GEHy7jOlM41O7Ri4HT13jxrxh5Cxa/ZOzDEnP3
cHFG1WVNLYqYofYjMiAQZXbukhaDGoLSfOKSzyVtLIEq8DdaKJrBgihB4wW7+LSy
cvCpeM/sE71eMju6/7ScxGl7tmRWTKxd4eS3WpD2jblkGUYiHCo8K2YqQrOfS6jS
SQEisZWkq/jff4a9d0n8XHHBTmURy4edRVgrjrfKy9BFis0rrLMdpOWZNBwfjmcQ
579yEdj1PCrazmZmwMqcKXoka3ci//RKVFA=
=WL86
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3a634a2b-7035-44be-a115-133077377c57',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//f0bh1HolcmLImTc16t0035iNn8aCutemzxqehSIjYswa
vx0zONofNr6cdkoV52EDAiwpXXOSA0npYKEKEcIApXF5sB8NwVTgUKFNCuXHg9tY
BGBdYG6fod2gflWXS5uWpzJH/W1mMghYim7Kvh37eU4QU+oQVDHTaFyquHBpC8HZ
KKs6TnUK1WT8EJrrfcPQp/grGE47pf9jxqOSWbC2XC4kl/NgioAvYVNBmmQmQuun
Ckv5kZ/jfeN5D89zh7wX8X7PNL+eQL08dCOOZpt6gMt4szeNhuHQEUtVvfMISt8a
uteX1yPcVNkWlrDkamCS3x7T1jH5rgjUn0qIQaCgB4g31a4poKbFK/jtNWXRtk7P
S4w9KIGpf+j4UhV1NzfBa0SlOIPEcGhyz3KGd029dK7qWxGlMkUj1gszXa9nKYJU
j/PFhJXer8cAL2/25R0xn4/12dya9lWAQtDdwUy8nZW7ufWPkpceEkOwSs4meLh4
SRUU4EzHfmC0O7keAJ7BrlATEgz0R7WWt+kxgFrSt5F2sL0wYXKIxYney2iVICPV
a8I+8sevL5shr71LkMXd5LH9T3NIXbLUCFevRbxy2QCqbmTpiKghuEmUH5y87Yij
TZD0DIHI4J6X3Cn2NCOP4DJvJn/Qnua2MaVJstJ0dH1fY+8LOYI/bgmm82/qI+3S
QwGXHt6HY3kYDSpcr8D4lJlW36N9WttgUk7Tk/mhaq1iA9B615h8Y00ZIbGrE2SS
cd03zG3f+BAPu16MyOnGi1l+ICI=
=dl13
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ad2eb5d-7897-4e1f-a55b-25dcba797b07',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8Dg8bw4RRgqRLieeAuBBS6eyby6Tzlcz04B35/H1b2Wfs
I0X8vvxAg1CXlfthO6WUe4sihuqo1pnawzkCjemjcis24lmnniujto2l25YiGnLd
8YdPdqWG/WURun37tma9Qau8VhRFjvkvKC06bT6GwxBStDQLW8anCPtUb49/21sa
sr9PTnMbGiB8MqtYepoXtDyr4VyaK41yQFfPE0PJ/Q1zLkRytl1JKGsglCkdvUMf
rxGIXBfcxZL46T+UTJ6oG6yN8voiY+3kYRZdYEpz7M4uZJwrkW5+qZTXX43/kS7B
XP9tbbPbw/E2YZjxgRsy1YcYQZAclK9uh+sPFyv01fZp+z9XJYM3EtTmxjtb7YxE
W6V48nsT72FzOSTH7vVw0ooMomCH2gAEyYqeJk56u2QXYyY0spmLx5gOR6yjkNiE
9EdPEYjMvUJIyizRnZdis9vFsI47IBxt5W58h8QigrpMjPjzkUDHbre6CWVjlSIL
EoRUCZ2jztL5c/z5IuyTHH+eSaVmqFyF0kBiwg01U3E5ztvc4Zva+jcgHAh+7j7c
E1KhZmkgEuWwjLW0/kIMqvXe4asu557gPOTkRDlk+e6QSCLUHK9Eg9eICuwrcxSt
ZHQ2BDZo33x1fNeCFi8iJTupq0VTSyfltxsPS150et/U/NxbAy9x4KV8MEPFPJTS
QAGcV5zmZKq3Nw3MAkrWaKruL3eRsPhI9sbnH5ouGdJCyDl0Dkk9K0f112UOaT1a
iw+1VUciPa2elR0UWfXm3eM=
=g+Ow
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3c850d80-382d-4c70-af11-9a8f7574f298',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//WFvYt10WV/p2SPHcwGx75D7rAMu2p2XR1MFXHU9SgtqO
75yMFSuccgnmWWqFD1s1e9zmjATEhd47I1IjakObRqmFO9rav2CgCW6mu/duPG1J
DZ/4+6qg3tt/rBKrpEnX3dSNrEFgZE9JXlaLDtBJiWbjMm+8hFh7O9P4Ke8RH/O4
72Wi5Jlj+k14OUwWthDCuABW4iuqS0KwYiA2+/nJ4Rm4tOAXxfNth4WP89SeFRvT
iS+nZORKvjGu9/FUp0g76GJm6By1GSIdoJ1n3Q7no9mPqGodSiTvIekSsu4E4yTk
k4qmS2yoHq/Q5FyV+9qiyGZuMvmJhndb5mCqnur5xBR8JK2WtHeeap6mVHXKmD9R
RePuUovNK581AjRWkRHaguXwBYy9+C9zLa3DvlPEeK/siihjktdmMDoAQKG7HGnM
lqQLx7PfqSFVVS4i+To24HWnrQV4kLRRQTfMXpSEHOVWZS7SDAbHYQ+hWSwuwiJP
IOIlIV5oXRHzF6V7qVo0EAP2hiL7+6Y0QvFvjDq3/pZe2TuPRajIEUELvPhcsv4j
om9DFNeJd0PUGBsJYZnjA22lng6C01AKPaovc5MOnqrgvuoPxsHEM+NSMFB12R/G
aCzQvya0jEmk5eVMch2AzHnAaFKlbPGhx3qEkWSIqn/uG8oV/LMehMWmPQ3yb8HS
PgEAWHIh2RidqPVq2X4DvNCOBpY4GQgVnxcEznVGWK5bhaN9VE85hy8ollz/PxZB
HiojaBXjyaCCsqdH8n6l
=ZSiJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3db966f4-07c6-4e03-a366-99b2d8ca5654',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//VnFNjAhRJX2pch58Bd+s+YQAwe1FVY4juQsAuDSjSy4n
HXqheT0QsDAxxM8PTra+d9C7TBe8UowGjMbgCGFw4lThG+o8jg1V6HoFf67b5oZ1
AtlJPUTZcwcaKmw75Zb8R9bPfTEaC3vIdKpGtXLeMLU2/kusQoFb0920tS3bu+2Z
gvANEvThV/2BOhXH7Gj8/2zTNLV5eSzz+v1RsC2fzZx1rFc+8B3wUeO9At4aRhdT
MX+ZsG6JZ7Wje/iiTeMV4CR6UJu+EJR9QvaR/rRsOlDixTiLjYIZfkMBfX3Vj/V4
JzOiGq6xIS5Oby2SleRLZIVW9YwGFKvEfqncQiMW2a/uaepNQxneAMZbEeLD1nYw
UPq7gET2qpKlenzAzNYXnwqS2irA4M+fuHxDbiPh1v26yIRnlfWKfcqaf/0iF71w
0TizTbS9pCrlofxyEmLUBU2wDwi1ovX3ooefz0NSKbSHoOfVmGTgT4gNN5jayyhi
s5ioGekopz5wrUsN49dVqx9fEfTiF1b2uF42SOeys0XcFVxrOU8rnxV2pVq6vYdd
L3a92Hfjtjk4jB6owlkxwhmI/nO3yRkX2uQcm+IejUSKgKiCmbXFe3YkNommjMV5
F8FgZk6yC8AvX1a6hMlCPPYSV9FGnqPcqXugFLH/++S1O0JZXGkACTL7ST0ftGDS
QwG2Cez9QEdqv1n/bMBl9XkMTWerGq6lItupEVlKs/Wte/xqUC8BGBmiKzJ/XI3l
WAnDLQfCTwuYMQYeDw64CJ3/uMc=
=RS42
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '49b76c4b-3224-4cd6-aa25-9f24d600f35b',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/6Atkf6ZYvZPOC/taqEJ1YFZsE5PkxC7RS9gbTohMROIrP
uJUDwntS4ZQ6zeQbWQkC5OvASAcN0fj7Wutbi/h6Z6dgyLkiBgfNj0Jg/LWFdAPe
iPVo3D9CQuzo0lfagNSppcnXzvMhW+tGTXZXqHfXCZ2z9juYc5fjmLrF3xE0tX5Z
XirDETerY5AQ8bGvCpYR92kAL5zaUJ+zMMRwaAocz7vw4SwB4c8nQv8mmx1FwtTh
F2Tjl4TzC2JeFEjks+dmz/zExOtwrHFttkKeZ8tsGzoSHMWt4sjO0li7N3s94xgG
8/GDf1VymthEgJNOQkFME+UG4ScNhard9G/A8jxqPfKaVhEKy/nuU/dYDSuEU7PX
ZjFofVvLrpFhBylTY3MyIy7PvGbBQpUeO+4MV+RQubR7bN52cRQET9JUYLGrZYDX
A2XuGpr3OPyVE6mWMvyKRLtekaAWrGq3us+IWU65iLR7LRn55NiQfSf3k91NPMAx
KWVs1yTFuBCCrTKovVydMoBAx427O4MiwuuPfuNOcvcp5IJ2t7FuRhc080iEl56E
GzJy9gtp0foBftkQ1078qyDXmKdz+Zv5H3hISASRwpNko+LlWL6sxBtD47aQYF38
EdfIpGe8F6CntJ06km0Dye6VylboxXWAkMlXtySpzACBtHtodh5NlzPq64MmLYDS
SQGNLcRPgm7Tuy+qrQfwzK2r+/pRQbe+PR7hh6Sgri5DxOFzaB9fNYUAn/ToGbi9
96/fWdkDejuDFbQlj8m6wpGScU7cnOGdf1A=
=semw
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4a33b7a7-a7c6-449b-a5f9-2b314251ffb0',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+Pia9Z0ptruROqucVmmbPwtVCMhiVEII4YOzkN6PsNP6W
+NcFZqlryjsp7SADeIo0gSJ4XwGqfDS6vUWY6/3wws3wFQQynT6Bb0Q9cJZvrSS8
eVpqmf28H0By5JoGocDJAu1DDHOA4bXcqX0T27DXhli475BzKvzkaf3/8sFNnHut
vKxrAxSvq8g8gF82EV9bU+m2HUi8dQeY05/XB6YVn5n8w9L3ghHphnGCU2cl2kUI
BqCGuK4PH1MKyqijRIeJGTAQ9kKFE2uSp6fTZMVIpJFszGPNyMePTKn3Et+7hIRv
048Fr2JKiG4biLcPe5M9Mqzbg36N5vjdTI0KVxW5RBxjO9IF/uXpF4GMbmx/CIog
qtue48BG+/oOYsiZ2Qw4cWw+1TM7/FgQcZYp+680xQ+SlMmWa0zG1aMVlb3phng3
7hiDWU2daVrN40QfWvhKQspVU9DrCT+WcnbM4qGCIxoAIgbYTpy6hWCHoSgLZZg/
/5Z34FTN222FfJm49IC+lKUve2fz30+nmNUO7TCn+61eU16gT25l0lTcU8/oAHQa
axTBgthBByvGDIQHvx4prIlZZecc7ebRM9e8DX492fRdSh3/kmu1dGAbhcNdTcyp
aa3xzmR2JPQihww8gm1BSloCpst/2Yxh3wOYbzm++jFUUYjLP5aqRFw7MRTsAZ/S
TQHQDCR9ZHrQDhhccTpxE5ut+SpI5luT+qV9zY/VcFZWHh9XyBDomgIF0eSUhpoQ
X0XDpnOy0o007OortHrevOj4u9Kz4BR8oa/5QMz5
=mwGW
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4afe7a56-d3d2-4c0d-aad2-372695e679c7',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAnms+8OlX8aWM90TqBm0gLsi+UAHfEgF1jB/xu9RCMT3J
Y77v7esSil5VWXQf6YfdAwAKSRA8Ci4NUMRXylxy838tUiUof4ldrq852/gH1K1M
opwtsSpw/i2ApYE5mvj9Pb3aPlN3uef4tHYOEwyIMU3Wjf6GaTFFm9jo9HsPnAs6
oyYID+3IOF4Vl1Qtq8mfynzfd7wguH5LT5uBKR14d9rr1KGVsFKJZIBr05VfAiPI
/GlOH5uGxr9ZS/IyUODoAjdYlvztcjYk/ZhMPYtbpSyqsBqMGJ7ke6ms+Qt6fV+A
bOqA/7kwVg1eYKHFpeHOjOe4GVpQh9v/aXthRL527O6cJC94MOMhRZl3CYvmq/Kp
VAW1eWs1oNsaFlhmA91Q1YIceXtueJY+fsDE0rgcKjN8/mF4Wauo9YXMMY8u1Cqc
4z2o8e/jS9UoLNOy1ZcCoeCujHuzE0x1x2f4dawLYSrIQMGLPOTeWjc2l+xXgbNl
bZmac4wbkg7TI63TjSyxfF2BN/lABhT4JMLiDCJLKCD1Cjz1h2Sm73b0Yy9URMNa
0CG12w0JogJoKk8CNa465zKpwa5vbk7sX0Jff/6dy7U3db7TO+bEnptJrye/uwFg
CBje+Zy8yAFChS2+zwWIkCIuYLoNYJ3hDhdb/BXy/oExgPeZrSlY99OesvIiY7DS
RQGNUFnIRhmtr95i1bTenz8dHasVX8yG6umTSDdh/u7l3Af5lyrs4pSIBjmfxnm6
mQkGY4tXTrJNhMQy3jQRYzOiyc8eyg==
=VL7d
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4bd224d6-73b6-4bec-ad69-6bcecff840f9',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//f1f9Jm86aQ4UGeJw1KxqpCsBruBMW64Tw+mMbIp+0vRY
WfE0SMuB8cVE/ZEo+Y4YNGzW5iK8xS2ZM1g9d4vDgTuLSBBRngNbsq+hVI++Rr8T
f7Jg6iWP+bGvNoF45v9omiEHfHd/kK867WyLy/cLLYp9pejtHAbfSufNyk6IYgTY
yeN2gdi9yVYX8u7CJEo3l+ICAePqqIJeieq3vfuEwy9AtCGuOQ0/YRVbtR2SU8x9
CgphfWuNR+BMXRuZYG/9umjDSmwd3YV7A29+y71bDiFvHvnk+bo2zAwX3elFnoJW
YzzdNy/Vo9CFm0sBtQl1Sx+YDwwExoR7ZVkyI33ltbDlmTE1WlMJi1mXCYdfk2Al
qhSR94LFuaCYf7YU/Y1sMuCnAxeCOxl7OUp7F4hGyYeYyJe1ZzTFUbmj3l8Vo5rO
SCb74tLn46UJvUqIcF6GR9K8x5TAkXBcOLsz5r99oEGk1FBa0e0Lx+h0O1fjs8qZ
J30Sx0+iEPL4SabCDPsTJxV7n3faVndwaFOfSoR/qqbMVRyuOVPVbmbDcHPe7AxM
ADH8quYKyvTFvkHjgD5ZV5r+cHw2utlJQTnT5pQRZW2gNhtLoKyzoEm27/9kuA1t
8CsLbwkaCISv5ZbYwF5htjsnW4cmLTZOhZ/8ldszQB6CBZ5ZuhhgmpblZHptNyLS
QQHeem+3jISWhdu761oFXC3Uyr53E+Z++C3BxK7NxcYLluSCnC7kppkv9NyLmJOM
nIfpxqL2SCQF8LqtHUz7zKZP
=Bf0m
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5192047b-f36d-4021-aa0b-90e96f25a0d2',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAt1T3IoktBmE+N/i+bXSgUJ7QGI/sEIKm3rG8susTutFF
QIQaYnp76RYxLidGpwEPSV95q/eUgsUqB+uy1LM2iRHfU4N/NuSb6Knkl0jvma8b
GQ7D2UJohZUqg/gMNGt5+isakZZL4Mib6bYQ8JYuIb23ixr3N/JECp9p1wQ2VVcZ
IfAti750zFFtwT1q0wKUNpiio5Qdaddmfwa2HIbnA6blgeYZSNoRtJT98lDgLvlD
1qinAg5oBA2RdZVMAZrdnFMdPA5vN9ZgdxFDGOE1TOZ9CPzfcI09FwiZKl8znH5J
UW5sMKXrEd+pJWmdE6w4BiyUoPwzkGldyXsrXumXOgMoW4/eJGq7fhmdO2PvjVqG
krBGhMku9xFm/5+cx0mz7EVJLr/JjxGuPZfhjHmXr5nP2+SUFvmREKvXlVirGYlV
0FibbOCfMlnihrm2xQePv7aoYpbWEDCHKNj3anWfN3z1yOa35cuXJ+pb5V0I1tZN
Fg6t69IqAf9MwW5cOoEuJUCMHRAhCA5o16JlSOrSS/txSK+mUl0Nk4to67jIJ9CN
z1MS5dHh77jHQaz878K9a5CVh3xoQlpiEnS0u9hmFMAX2qlTRsHWhkveyUgP3uAH
ZulCEBOzdEvX01gH65WHb+kkqlUraBrk7b/HGm/TnusR4mE8LKOUEJpoPd7yDfnS
QAEi9pavonz9w7bJAX2/Yr1wnVc3vuMXSZe9xONVnqKGP9+3v7udTGsPmDziY7QV
x9UlOKEwVRA65ugmyK76bWU=
=Cscc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '554f5d70-ed56-4409-ac9d-c1a04572cf68',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAoF0Fy15KFfab0rCu6l6xRDjelIdBaKq9eqAp8a62i5tg
OkT7Z9yBCZGIXiYwNNnluECCBIHleIhzXj74zdnYw/kfbfKuU1yD5UxVPUsdBchE
IOL3Kxx17oLKcxxObAnDyrDAAcvvhZlexWOdv+dzBHYScrtc0P4wiQPzFlizY3p/
klsqK8lkJc6hx1zCoIO74wE9inKEPjG9eKTH/GlrWD6rVaMih4T4vwMGfVUCZYCm
11qQaH8alz6XvYZKDOvgTuED98XC5l1ZiQldWRPpYynJDbsJ7yH7ZXqxYb7iHFl1
WzU8FdWNQYxzsslqDRzdGTE4sDsk3H0/NYn/aXh8HNHvxT9Xp4Hy89b3LbJNuZKQ
Iu/5Xcj2kEsC0HGhyT8Y4OIvHvAXlK4M0+ZYJkSi8tLgQyw65jcT8vYRTk9nn7ey
l+IwEQkVZbdpvOgxf4RhnQ6ZTwmHUJdaC9WqyN99JF2v3MEX0FqGcOr+ghtfKjqk
VSl7M0ZuKWupdaS7uAxszJsHMiML/KgO1maUkvVoCo0eqhPOe71USoUrLJW+vT8G
pUhNfIqNOKuZWeP74jiy3N0QwFchbudySKZLllfcyns2/RgE3gYKNTEUYCO1IAke
tDyZ48EhdVCRvQcyxVAUPkE4rzC40Xb+NZOnwa3lD7nFT6sPSk6gLCd11koKPHvS
QwF7vSik7PVf7W86xelcHY8zevIPu8WBvpVuhORmQIYYdvqGYHLEqNlQGugB6vHz
1hsUdhGqVmo66HhQ1Zwtqyacwuk=
=uump
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '562b817d-73cf-4dd1-a5b4-3e9a33f14614',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAjwg6BSiNZN8bBymXBVYb4gjmnr97SfCaxNjZRidnb2xM
CnIhRyVcepGX4GJeEXzV1tk2xAUCLcTt+xMwmCBfLh/DDE4L4IjE75iZd0fsnVrE
1Mp603tcd5l7M+vnN+klHQwMDmljxX511qHvI+oEB4GUoZ4F3SWlCKF3bfs0s6Xy
YznzLBlXwgZ0xtbEieHprVbI4B9qE9j7rY5cn3vfMgvHYlG4gqrmmrNwcs2SBGxj
Bnw14jSZUQLgj43DKmyVtr6vPor+JdPsf+pkkZ7CoQ9CAqtL9ZxEV5NWZ3reXWvC
UKeBykwnX9T3GgizTb8kQo2WmS8TGKCWEB3PmBs7V8EcSu9glL8KLOH4Xi+zzPwl
JjmjWiqsG5LPlNM0zHQQjp6zGivOIKJrD8HijCaWtQ3spbFyUQW3gyEeCWADZgF/
5wjgEFvDQnQSqWpxcU9lJ4e2bsWQrDMhronI2EI/dw0BRJF3BDmysg8GlcPyc9ha
wLbBhs2AmkmDj2L2NiMVxOtLGpLP6nWXpMSd8LADqMpYEBgInkRboUut2Ig9M+LP
8u40/h4vLFSMbEPZZUIUK5tfVURNSveqGMMERYLLY99nYiakUDEl3kTb4vHAO5p8
++o6gDx/fRHAJ78zsmjuH89wJ6aJhPSTTeslk52QucuZRHvi2WI5agXIOhnjFF3S
QQFp81bV/3u6F5zC0jRA3j2nla0YXb2UBs8+55eOd+mTEc4CXQpYHnLR5jOTKmqa
EQoKjY8WVjrAwJ0A/hYWx8bn
=bj78
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '56d959a7-9b66-468a-a585-a4b9497e1153',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAurJn+xnwJ2m1d/zODlCcQOBjbfDrw21evwetJKlVTVnf
jtrT0CJ8rniEuRKZY3qcPjEUgesYLP/VW+VScGxfRzHyaef6sIxsJDimguIEZXX0
KW+rWpOY0wqNaDaZe9stnlaW/a0rohimkGw0tXXJMYJ57IsObJUlLplOg0ystXcs
Ru4NnPxIrG4909kF7Kqzsgz1Y8tqk+9QyyMN9byh5K8eF+0DBlcw3y1nkaVmryUb
AFtt98r96tDzsrIwiEplLZSarJtXYcD1rB4kLZ7qoZMCzo5QR0162TIdI4L/rR63
Ek+KYeAde+n159wPTkBXUkvi4bmmcZLzLZt+8Snx455+WmHiL7d/I44rHrc0WqhK
HTaKUbsW0TBqe0ypiP5T/2dCj+qjfjzqaRm4xUVbSvQ1VMsaZUqJgZWqM9ZiLDv5
1jFbG/UtgYnrpgODzQdpOkxzxM40Vn0U/Gx0eZyb8KBGUZnBBHf9gFoCNxITujvG
z2EwwtJjDrwFM6gG5nBYTWoZXUlHpnq39LwfajVAiIFoMF/yZKMlsjdh5o+NhgxD
p5TdNBWATNd+il4nXxDoh9pemnGBbx7jxaeGw6TJ1fnDMELMj/drrbl3lvHoai0y
VWKBsLuvASolx+YjLidefE+XlXfOlA1mXNr076QMkT8QbE04AAXaaRnDm/4jzALS
RQG65A90S1pPKP5W9gYM15KGmsCyd8CACkA1z+g5kHH0xlZCIzp3Shd6M1cQ+tZh
1FenfSorVND8RHjusC8C9+croS0aZw==
=qI7O
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '577b6a7e-d00b-4fab-aeb8-f01965e88b83',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/6A8+dZHo058FehM4yGq0Womq7J0rvyoaz93vic1eG+RjZ
ypSgtBzG5Prrbq1c84z37LXl3si1JCTP87YemiVVVWBSiMp2qgz77wxUltXzAV1B
TB4DEK0g3zCgLaNyu5o/SI8Nh8Z//O85qryYeniSqHoc3IjOfTgHWXiqMk5ZHbkC
tRD6e9gZtibvL1pgqOfIOzeR5msS4kMiKg8jdPH5OUWdMsaOl5CjBSWuEiUWVfCi
q1UnHMPIXKHnkv+Nm/VXSgTPDcDEv3jEBGCLFk7X054M4U0QaHgKWPOFvEy6kRFt
JybbTa+u2+7jQk4RqviDAIP1Qx53ZT0yqrfJRCekQ5R3jiGAf9vhq3UZUVkA3wcz
sufoLZsHs/ZldoBpueTIN13FG1Enrtg5xKVrUl5n13nwKybhyhlZjk+p3xv7C9qN
jNAswK5SiXeQmNrkOJY61necTjetbWnOlZhTkOQAEPOy0d35XciJVsCBo8E6CMFC
e+0b6dI55ziRC1pBo8jamBh9kpEN1Q+x4bX0cCzJTMizf3ActAhwmZn4nDi9DSR4
Lu5hHwHgml4EBF6iP5IV7gWpTfEWBtNARSg/FJuLOeZMfFUZO4EMuV90+7Qx2c/F
nCJeIMOLWsVaT4CJsdRqex73Y1HpaQyap43zvrP9U77oiRPcHVRgq6xRKh3DEpDS
QgFzDNVld8Q6NlpigfGzZmIgZVYkYkFI3Y2wApiDnvXejWme4NxsZu2PfmfzVrJ9
cQ6Hitp9IZ4aQM/n5ysQODMgZA==
=B0l6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '57b95a0f-0259-4c4b-abcc-5a4a5854f185',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//TKe8qb6nGJSGc/6+4WxBdIsm9wJWky/l1g+cGKJlbmym
d3mxEO78mHtGUlv4Wg6ko7LbJGMRC7SM65MAzNSKgWy/pFrDuLaP4R6SyLNxFXHf
X355P9fSLvwR1VRUqaaeCdtIwcufGheLVG+7q6fNySAH7Fu7kmXMK5x4ISKtDo/W
ImTTezODMGo0eKza9sejNj1IG/gaILbtcc2GxyZK18fEtFg44C3ZaIE4SXT+GNs8
CsxbUFEtDjdl+X/+9+MFyzmNqN7MtdU3eCGbvqfbn1hkn/2mCYHxh/7IoO7hg7uS
Qq8y+ejpShLlRzmwnVKcHf1CAvC1Xp0b+ZNsYj6jxi2Wz5yQM1it3FeC5g8lNLGF
gW16bXZFnl9Q9ay+2ZLg/a2jPZvU7CawFFl/WYi84oT0HnqISl1RwP5U9s9h86Qp
GoM1rWpmC8KW58B63/bdZQTpp29mfA2JtavY/zHNuM54hEcn/fJa/kWyvli2idbw
P/0iL8tvDkasKNeFu1tX3bcIVOtizAn/4DnsNe6QFAoqjTVat1snL8hAqEKstbvn
sTwiIkU2fzBbdi4fraoQ6Tu0xCpIEe9QHRLsPiGZ07M1GMrS11sdAaFYrqd02+eN
1J6Vduqm+yJcthAqct4npP974Vb8Thej0qWyKQeBa5F1I2wS7UTxsoebgWUqsifS
UgHOUWChfatqW15nqbsQ/GdXGDpE83kc+QTxZ2jz6pNbFyI0J9uM/QvQl8vHZGkX
uZfiuDjKQ6ZQUltr0c7x6+ihqjXBVtWpaB5vRAr2sfCLWIc=
=1mDV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '57e1ca9a-0f68-4ae4-ae53-5fa0b4082e64',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/8DbALzV3SQSNihrLInkZoBHTpHKjSK0LADw/0OVaoQQes
V8d5MLHRiVI6CnATEzop3eHhIaxm/w5MUkhW7TsJzk1NlQRO2QytMgeNWXKr73xU
K/KxbAZ9oH++8/Sm0pmj/ZrxneeM0vfnn2wspxy7rPgarNo5wxhWVGHx2pQXuDAB
KrZ2eKqndXg48v2dsCOfRIfgNl9hJboRO9ESJq+rET2voRBCfmqDXKtHDZ2LoS6E
i1UdmE68Vc55EOC0xKFcR+3JcOqThJUA4bbXRk/8wvXEw5HzAzT1PEnc6qhiB7wB
wI5n5dFuRDcZErgiMgw5JBZpSjPbKVsZq5trPdMAFCAzUhfCwueWavsb355B4auI
hCZCSzxHFMJiZ8UMkw35Gw3yYlm+zwRS78XAwOd/78R8AHLPKLljV7pJ8f0X29q6
fABKyvOhjcCNg2Ot9wBwu2BcvqSCi2fdk7k0l2Y41/wyEHMbtnOItXNWjh8gZHRZ
NHE7S5JnZMifbLWoLbm3ncIJgaGWX/l8oGa0N/ly7YNBZ2ahYXBbTKf6/0hEbCTU
mct+CLeWkpgn6eb2kurH9fJrLsZeiSLtFPrKYak0zuFLUsqSKMEg+1QwZWCGFFmx
44N+2LOs3xIyZB5g6JPcSWlu2fP2zsvgT6L7riyJSY0BxhE44dLHp8FXNjSZ9hvS
QQHn/AWpyYTyKtXbIunp/ZBN5gYxi+WQg8eKQg0q56GdI+nfDw8t5Jo3ojP/mLpF
X+Mpz1ZU2UGUeT3vfM/cISr4
=O/17
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '58e8c152-1021-4d99-ac44-72c2cdcf58ab',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//UnOLMa2pLzjybKHTosrHta3A61s3GwM1OOk3U4Bcqp94
rT5efGgtOPLikbzTfc9xfmlSD10AuHsRFfV6llGHFh5Ege4nNP7e9D/oRnGT43mC
T1znX+9BcnN/ZeCIef6GqdWzCu1JjZtlg9dvbU0MbeCSVpwgg/RBqUlbwymgbtj5
UTEQtDiaSUy1wOIvjIXuzgSKyOvXOCTZOYiFaNFUc7zC4eBq2OXhku2ln47rvesJ
3WT4B45zOf2A52z/DAMH28mjJLcISsXg9cPeqxVSGwytp62/xowLS0JjZlq6ubE4
3uh7V8HQB7YPpFGmwvdm+XISXTCzK6AfBAs4IIH+imEwdXChk+/D1iKMEQrb4j6b
U46Duy04+z590CkjzsqZ7PfZ/4oNfsWOHAxJWyz+d3uQhfxmsmyx3fxDMC1wmJd6
1j2Vq1LSiyEjOHH8zZJsEuVqDhG639craFb0lUAGfxT8Psku+oA+Zlx9Hes6OMju
KOcbFFt/GbbMzpIaMt8r2UP8NvFy+DapEfp5BHJXbA5jdBsTlqh/XBksLtRV/F5t
sG+IaW+fIZku8H5w7AG0m48JhPhuuE1Dv9Yt8iK1m8LAxBubwlMMkXVDi9tyOFKv
wmFtNQLbkMqYj7uGcv/CE6WKpNeYxyQvx2PNTiKrnO1V28Rm9sDyIZYQMbgeANLS
QAGZxZz5kSI7+42fWToUfFQ7ozuhRa3ld0aMi5NnKSg10Kk6WWdWRut1MWyHhM29
D8Oti+t0rEFd3rgcgqMUjtI=
=73Rm
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5a74ecc6-f889-4e16-a939-26d0ebcc2322',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+J9EmfWO6U9Ie89cytpUYMCXfIZFtk4ub3NJOC/1J6EDX
bR3HrpIqAxFhhzhBUnE3CiSXenWUG0CfrFxQ9X9CjGRyBg34OaEnXsapdntxE80p
E7Uct/Kfj7LI4UdZ4E2ODnOX0UlByeJE/Dx88UIjzl8hhl5GjhL0Y4nO6Zo06ngn
d+4QbGPXuK6vqYkZq9S+L95VV/yFIRt04rbV2KdcobtJU2bZm11sLKfmepwh2opW
q7eemYWX/Dzu9ZXwySAwoXrlKJNVVzUpjO7wywgUFOY9A9Hs1YrzScn/+J8HhE0f
FMDEMbwT6Pir/1+Uc56u7BhYfail3NTBQWN7VW8FK+xd6eu1IL+x/0y7mkJF4azO
kjLaLDTgupOHethdIzKlMv/uw440U7KwTh7jp2AoJ7obkbG92S45y6I97UwmskQh
SHNn/fYMdYic9uuoe14UkqC4NClaO/GTdKtE5g2hw1fNPqHxsvB7O3S+cBaZCJ62
YcjdiBeHCdu6idAnpVNGdBvcHqrVefM06hXflo77Exf43qehQfip7GifEPV6S3kS
tFqKvHhsUKGqZDGxBFZn3qQlDMzI3JVH/oDiW1w7PtJNqTv06mHT/YvTU7PEvFcY
TbHwsarcfuRXz2OBszp7SLKtTMCyycpK6GvDfZWJJ2ahHjmmPzlbUsqmG44GFjPS
QQHOMYRBgW0AbmDWjAE2r/SzB91r0eZFIWyuT6wzr3/qLQhkiLXd0fSZZfSZhPBt
qLWxBYldwtncqS9RrSAxIgyk
=PWlJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5e20bfee-6e3e-4687-afbf-41ad3f355704',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+Ifl2svlH5uNpVk0pUw80+449N8KTbKIvanN69nWnh3Ok
WdJIi/SKrMjbzOUAcYj9wR3PYXJX9BV4LL1ua9v3KTAepT4ToPtn+0E/AROVVshx
8ksqIw0A2bOw+ONu9zKmIV+zjGo4gQFZAP7bKu95ITTLA/VXfbvRyjQVa9hpbAo9
hwIjRBRQ+/G9QIzdU1lrIWx3JL3VMlJMPxG9I3GvfC2DgdrMv/ea4TJHbM6PB9PB
lgT0geQ0GRe8MzWUGq87V+HKhoX+U33zED/z6unecnE3pPAB1b7dQWSR8YwOnVEW
+sK8tAiguaf/pZJXTtkbz04ix5TpPnuJjobMu5Zlq0QP7A6F+xE4P6+DT7hNnrs4
lwOyJXurm/s58gj6o1A/3oyUBsmyuck+fe00VCCDQNM821Z6HDeQGwQiqR8Edt0/
qHAcpTzGqLT6LFg7N0luvclwFC6JaVrAqbfblQoJbL7lf2vnwUVjZLGGDM+W5ct1
7WScFvQNYUqkSKi1FEoX2ODPj13KQZgRcE3fQqVZILmZkqAvnEiwW2iVrqnT8f0n
bFb/f1gNZVOUXdbPM27g+dI7s7dsmRywPrpXmzYziInk+DH/S3wbhOS4JVOCH5o2
0KTkCEcERrYUEg/0sGgx7dqZ8TC2UU8ocPRRDl2LcoNELUvt9Gt4VCMwmRwuDSvS
TQGcEy3qKNt5VssE974jCs16V4MPUFX2bifc5AGN2OrCAmOifqqWy7OovguecsFu
O9UmknlOPUM/oLeYXFI2H0o8W1fUIJ/R7uDXqwPq
=IJgF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5e734506-eee3-40d5-aa8a-f9591fb9c1c2',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//TTIjLXHNy1G1j7AGYg0kY42aN8UTUZ8fqgXL3Mx9m9EL
Uc8lGlH2EQ1YmOt31nF+US3akoUkIumrezEYJhn7Krs65+t7iam/LLLyEPiys01f
tILwtrlyMJpLLcxE4FPYE6vJcDq2eu9T+JWU2EU2GdNFXWbxPxQLhz9epOAhoQ9T
JR+Lw1KMWBBSpT53k3K312aJV4xzoa4U1A9U7S/fwVx4i25+904grVy5TYQXd662
aF6NvEPai/+hxjFAVpDvrQeBY6/Thn6lnv3vrk+Nu39Eps5AAkbsgkaQjHcWGaCN
IMaL9X5AASKzl6HmvBfoYmGvpsUL/QM473re97+6pPsAnteW8Uz7ZRZylIIaykcs
uE/Bw0GnbgM1tOwSUtUk3fdKooG+gbuP+LQUAlpA/CrqJAmRqPf1dzsV0BDjQfzQ
lL+bQxoQTFRWCUVrJbPPIL3js9D3H3uSLjy5PjuLReTLkGUbj2Y5J559jjvbQyaC
MBrY4aenFfK5p+TcYo1xHbnF2lnXx+vzhNX6Sg6+uKGw5x1AAb2K96OPzwYSOpJa
WXmyo8gAg6AlTPKJWTQhz9ai9G7XFfWenXEjr4RDHHIS8xUSWZaGAdGdidfOHfb8
5Oizhzf/Drt9jNXVlmpMmvZbbNrlXKnYg3EC0QWHRdJFNznjGYBmDSuggOgr+DrS
QwH5ttirUG+vhiA2pTerOTBJTgJl6+6ERUp3abYpEd9UsIwUxK3Dkdm87kHSsNmh
1MYNPQ2NvdMFOi4aEJsCkFkLBlA=
=m64K
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '63e080db-b327-4f04-ad06-fd27b32306ec',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAxmA/If5YFd7jXtp7waFQPYxkzRdgnGthA/MNBwb+kpdL
XmsZ0zaaDekEASdcJyAq5ANLZdYTY6MuV2QYdRbtiL/OMG+Kr9wQH07TpVGPVEWf
51F8TVeaElwogC4rZn1gGaOb/cGkCI+EG4FsEX0YwPGlDAl5pzLSmpzKVSaWhMYf
Pa2McKRTzib+kL3jwWlmiqn1Z5y4aY8aU1V0vEAwcVzgZNQogaiYu7bw+EOYp40S
AnJ4FZ/v55l9xa+pk+4YYh86N9zifF2MtuygLuQz88eOFEHgBzwpbQxYV5cqsGwd
QS4dphN/SH0PUFtRF4c0DVU1GKMyDcvp4cgJPgrdAhXg/M3tp3q9o+ZtvNMEgsPR
B7OfkhXROJKHGKZcN65PMzPYWZyGnnEN1p1AiR5VW5e92kKnfCOT7KEXJsL1uOqb
negbOw/MKP/qubbMAIqx7BjtYJv/lHUsMchCpx0yQ9QaWT5NmzwW4nvn387ETNx5
c89ts29MrwX445sevHRgUn/W9isb9Bu+pkX0sp2oSkIAGH8yc4BTCK/w0/NOWiX1
Ae76zs5pJ2AyIty4qq13J/EE/rzcDiMgTYiijyOo8ZIQweZh+a2yO6E830MphAH+
eJjNoKB8S0yr4AtvOd0w3ZStN3kR5/LbJPoK679y3C1P8p8A5DGItaipMZcnt6zS
PgEFr1gUDAxJ0dbGpcbyrNy3bmID16tkaKojw0jhBtHtsfrCJDWONugBQTqAYn05
hlvDoO90ay5+XC3lVI3f
=x9mr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '64a2eef1-583c-473e-a2ed-eb18016e7482',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+O9erD58YGSJhwdc2OoKpmKIt62rGBkfE5mHHPe1xBgcE
rqsWAEyC55fAb2BDlmYpYXygcFFoIGvZRtEW+aOUZh6BZ551Du+ywyR3BsomtFNe
Pp3bRpuilA22Hy2tcbaIJ8jHWQK6dgZvJ5n5wRGRRd8eO4Ipt4zuXtVbi+2DZ22F
hT3/MXXopOA/FIOO71M/jYvjqF93Tt0zn8QrQusMS7ABpYe+PjrAhorknlwJojl6
YcQiR0PJqU9J2QEP085lNpK6BlTZ48qSn/RDqCU0GYsrC0JYwnQMlFEzzC+1DfO0
zszr3/DrhfBx84rbfQQo5jnxATFeiSfSsRo0h4fCmOCSqnL12vFsaQ0WJBqzh+dk
4V0FYgzpN+q2OhFkyE2Zo2KJiZEUG2ANBWKm84hwnqrzdujYNt1/D4oUKa1S5vGP
z+Q8FMuZ8rtYHgWKkJfyZ2zLcTP1d6BCtWhhZj5L1FXtvo9GPe4qQR2yoxGCNiap
WrlQL0CwXE4HDOSNKNJeENVDIAaycqgx0Yz6pF4jQ9sIzUNqKaV9tJPG6Gyo7J7m
e0DjGBxqeltJpNabqCbtTniIktat44VmwytalAB0j/YN0OFupjhBQHT+oxHmLyrG
RT/+jddZ/Ht9036JS7XSGh+v9AjJfM+ocVSGbEUQpOVLSwoccQ+KjGWfnQUMaivS
QQGQTgRY6IBKdhUDsPp4quf2qzBowG2Xx2vsnlCmR7NwQL3HKhk8CJgxUbijDwe1
RpxHvHczZgZ3RGwxX6o8qQaT
=C1mh
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '66099148-5697-43d4-a7f1-e5a8ed1d470c',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/9HQq5dmcByWTQnDfIcHoqi21mnrf7OFHDiMhtiqsRy3NQ
9vaoywY614AFDDAPzovKtr1/fPrOqNxQTAvLKgvlSnjPNRIVgqgcM5twqPxqHzX3
l5YfXCs3gS343VkLtN/u42wygFd1fZk9XIOUH0ibhudebiJDJ/0G4PPUa9PTMjMX
RUfjzq3Z4nYAKgndGJEXqn7u8pfXyvaKok0So5ceXnulHhhFRrZ9H7pdE+z0HiyH
qGJlhbcIGZGU6hg/PkiQylk5D/0YS3zz8Sj208eRx2esaGK8k7KXCieM4LDqIRni
jppZdWKRvKdRAlyNWqb/CX98hhkoSndzpcfuIVpZjm3fDbrO0hecukXt4L/6bXxS
Du8e6OGAebdBtuWt5oV6Q6VJ+4AADApq8l3uxeK8B/ELMOYrC2sp2wyNBLSC0Zot
4HnSRD2mTbIDSXyFisC/zeP6l76BVl4zSzxoBqYJXM3qileP22lPBU+dTthyRtgN
8GTcY2Gx/NWplEVipBD6hrNgAWW36Q2kh7L07dHeXgfD+6zOBVMeA5WsphOtuVut
jYZXtt7+HQ6/VepBc2fpwmHZ5RiMRNvfzSjHS1SrXTHUPHNYtmm/5cVtaENwxFJY
HbNIAgJtTf4YHrbB2J0ngPbnxJkfik3SBeTuX22JWV72FG5SOu9FV7zvnWEHA7zS
QQEIqqppWCfwb/lJKSYur6A9/s5myH56UlDI/CmuPlvWbyPoKk3B0ZHQVj4VaiPU
3dtdH89dz5+1lMSUpilOdaMH
=bREZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '676d1271-8644-4537-aa21-e3593ab0cc2d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+NShyXvttmvMWtkLpzcOGbjPIyUN7+lfVk741/DZkTVqz
dIGu1cMlNmX2UM8ob8BLMuYgkAcu4DupBEDCzTWqg0wGbQdb8O4oBiV7u+pep3Uo
DEsijGdmx1VYn+sRmT6iJ550ZHieo6EWb/VqyS6DLhSk4NffWMRyc+ZN+I148vnB
jLYB0HmoW3Ooig33E6bBRLz/sVX/cHEdQZiN47Fni6VVmXCBcS+qIR3Fz8LY6FyG
55S1XBgQseNWCp1LjR0tJNN6bI+p481yd5+V+aWPaS//kB163c7D89D4cmc2bqwS
K/FCwh8pLto23Tc2qliLeZWjiB1hKCV95Lfi4xE90l6TejhWEvubibIcNrhoFpj+
o7U4RLR5yKOm0SdaVm1fwDkWuMDZY2Gvcd7+/6unJr4FK8CfS/BNqTikTSzLTATK
BzABTbMe4RyTwlf4ek0MdT/k5M1YUiI1u1NZItws/xQCSQ2ppZfQuRrsl3uaqB0U
r5LoRT43HomBbWvpRFxCRF4JZ5wRD91wZZjxTWeSKjpjCRKyOTOCTXTB4D0BNczL
hQ+HzaWFtM4yrrbmqidArL7sw84ICUJM150lMZvub7Rn5i/On6CILCORNj7cznIA
BVyMzKWbCuoxatQTzPS+ui3obR9y5HXNiR27q8RFDRiebhFhHNdFLCsb8WkQNPrS
QgFSeID9bMk1xweBFHu6/1LMQ+Chd88b5C95G5zpGPavQGdvHcISkMYIGPanyu+C
vRBkJWYSsw1TsyUCHEqNGRtBOQ==
=KsaL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6832e52e-70d7-4987-a9b6-551092d54ce7',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//UbDlSqpyb2KkU/ztFP9jFW/DJ65nkIchVRHBmv27/KG5
9G2Nc5JU4Svm8M7cO6cKVh2iSrCOUi5FoJp2VnFs0iPGSvZ1O0z9xYNlu5pYGvoY
RqtxDgNzets2axL2kq9SUM3sNnsqSLc6inVh7jJ2MG1ExIcKL/cUjwkRYAAlIJWF
4vBw4Lw9wKj23NgUllK7PbBpN0X05NRO4XgKBBNqEvKNCuh5SZizELoFu6+LKsrv
DXLFbdZ0nIKJhfOWDnD8OU3a94DXLx3eF/peuU8I7WTMPeQravGp7Icj8oDla8hF
cHGlVlFI0jNsdudumYZlxS7M+HvNWamZNBhy/4zNkhWxxnO1xqXflxUMBV0tz5Mh
LpNBE4jm56TAsBxFgCQwKoao3i5Ie23aosVKass4oigdr+GEkZZ4YbvFfuBccC7d
sZqaipBsR6uZNBwuTRWvn93CYYorwK8mViSTK572MmBqAAf83M/74q2t3l66dojg
yiP//zOa+bGTGZDQ3nDPkusBXnmZfKAe5wlolgZdu0t9HHN9rt/HkYtmlZsUNQbA
NnsrLdwRF5p/IiWICqFL3yI4dvGQWRhVSxBbDJaeiLq4lRpdeqNUTLlh51xutHSf
pgJtUbD6isuj/WC/7geop2sXYaSRSF+KosMXl1GagIlv1Ai84npHFbvzyy9dq07S
QQEdBrWVi441a2c0h8xfYZ3ZZ9eY4FUzzp7gfi5MmASmlO113cblI2rk7F+d2HQm
JTveYHhzwgb983mxBPdd7FSm
=JmX+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b581fda-f97a-42ef-af2a-891ce2696dac',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAjnWsG0m3zZdmypauXqNJB3jHSFh0v2mO/4Z8/+3/t9cI
/hN2yCmoqXKenFBUtWbokHS1zwe8LVokI2onbz+ERvjQWrF33HoyoQzqeCMN0SsO
xZ/9OqZiqqqO3zjGYT8wstZ2yyAbbvDxiLx/sUdmPaUIA92jo8uO0DaAniCNxQ6h
eS5pLcl96MVALVhjX2/g71PG1BpUcL5uZs8KbtewvQ+xj7nXl186o8xjVKuEIWP7
UkH/khVx53Frc5SaP5Uu/wOG7uqvaaKRCTiq1IHwN7s9n2sN/hq46fKYyEFJVrqL
CyfqN3z63sW0NhelJuKoVmRfyI29+Bvt54moUrd2lATVlaqJyBIygK1Gt6pm3c8N
2xi2pAkmnIhJLfl/W4pZLzwYAmWSGUWFJqEpWtG/QvhteQvOZGtWl+rF/nFRHLla
fi9yd67yooRwP2H1p+TJpSE+dpHzmHetyh7Z+y5KyolhrQKfzJUAaSwGhp8r//sc
bpxWQOJa7qI7TVW6pTwsC69aqUzYvFol0JO+q87GKqQpwa7+Up3gCHWCtB5w9uv5
Zed1aDaQmnnbfH3Sei63HwIkXJ4uEzxLeCjqh43fJiotlqwVSGnRz7V/D5qLHptQ
p/KIqIE+lSfWOoTnEeNj6pghv91a3cfqR8wtkjTMElrqeyukdbTrFC9G0FlAvnvS
TQHbK8V+H2eLIe5SfITwVqrpZJ+9qJ6mkq455j46SfpAnR7wnDXptWfdZodnBydl
gvciOVN/4TkSzCilFV+UEhqSsVSKgRaEFPc+achC
=jH2j
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6cc3f877-e42f-4203-ac90-0231df9ffca9',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9FcVU4gG5et2gqgGWM82LOQWDLaqfADsYrZgbFEvbD7zV
VDOdXFHxY3zebY0nOfnHfnC8B3Nd5XcmF7oLks2zcaspBV0GHwxOvYDXUBfRytlI
9/4BbjjkaJ7fO/LKwB5HDfZb7LCvcBJcvV74TI5+3HvyA7gQWZGa4aH8ysXFOVDX
wSPZHL/uETzAn/y80zTkW0oNBtaAbGifBkZ70eAPaX59R3CVjX4uH3mj7QBTfTDV
1mIvK0mwH2iGfOklEdaUsD91I7y2piyYHDhItV4XDHHIBti8WMjHGNSaPvfHo9Ns
tq4TBaVkyDnh3yKP3lcI/6kbfV9eW9icWe8ChnDmFSCVYFt5R4YdK6DV736JQyAY
tRy19DJmKZiNGgGRw9qlKiEwMxIVVhf6FXjfbqGCBXiYVqph75Ktt/XnRRBksHbL
0I92CUEWbOck+CqS3u1W2D+l7E9qJuiYMF/0JajkzcGAfvvX4Sp05Ogafj4CV83f
uk+MczFBP1A1cyI3B5wv3XhHOcI4IR2PxwUa/0lg55hiU27qYiJfDQ2KxXE+FDDC
pq7RuTiLaf1GozmvJW92To28W+JVdebGSxwyb90LD7xhBR4UBLZL2F1wxtil5hfU
cVAKxkyLYNIhdW7HynCQ6I4leDhTkXZEGQLzMdtcsfpw30dqGTLgBkx399piOwjS
PwED4hHl3HLhhkCqXo6HvbZSV5HosBKnrAZZ2u5+2jikMeZcB+gZUxw6uVQwQChY
iGhpNnUdgUDSg/MmztI7Vg==
=rrzr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6cfbcd2c-e03d-4cf4-aa80-872993879852',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/9HUBXVZY2/oXbogLtvaWkFTXxzFU4QGxcWqu9kmaJj/jn
QSFkqvjCR2i9zK1V2xiacLtnm4ErHMoSLYKj4P5TSul0JV7fvnnIn/d8GahIgwP4
wNiQqTLS0GZY/EHkG8jEdFDLR/n846DMB0nM9MOTZq8H+Z63LscyALx7Yi21kQ+J
48SJHp8oX4/EGiS0XR4y6Y1bjzKmQn4jzOgK2Eb4eWoG9iqUMP7nTwq5wHqk/fxE
AZdZsDpty1s9cFNfRh68TywoPxIFnuBqvqFwSfA5MoAWv8mcKS/pv1jZ+0s8hgQb
x0WD7iJZIyLGxVU2JpWCamCj7QAYwL791khCLGl5xa+rBiYLpZsc3OGfcXGYFySJ
saDQOJ8qfu5GCdeuVBgtmsd5HbQ8xTiXseh5GOIMLQgCS8wCKYeXlDR17Wo09ROT
oQ+kKJYzQE98t+Nh5xPiQPKvB+uXCAbSTi1a+k/PhSUzzGoBZTTm+7WWGXBFN7CD
6TVWZxzUHEV8zpRZjXoYZJzlDLMOkKraAFbT1kV2W2c+c4pk3yKcNQhsBHSc+tuF
jmIdLtUgBvHtzcIU0rnLqzIcjNq2aIZTfzPBBMNnxedEJUhqYrD852XNPZd3j0Gl
fyKCGQRJb2QzukdNvyTDnp+04B43R+09rGEFWso5WLV5r2rnvw0WT6/L259pV2fS
PgHUji0sBJqNQ284vd5Ufwn25jKUdhBf8blnR24MrA/kM4RYpNMrrLGkoBA4LFXp
jdx319HVZbPJZGeNKqa9
=ou0O
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6d292564-0ec3-46c3-a802-6b63ea1fa1b2',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAkN4CSDmFJOYYb760sNqFUTnq/EdBGdRxuWKmGmHsNS5d
a7O27l4f5AoKTbzmZbjIkmr5fnrAH7KZxg2v9LFgxHM7uHdnQdxxnoyP/5M8Y6Tm
OnEPQeLKkdrbFxwBkJAvu1Pm3CIukqr8nn3/fN2m5vZP8pTS51b++ref/MKAliVz
51YPrvrhxCJqcphxkXO2iiR2/XYp66y1ZeYUfc65GNbs7o3YM6NecoxWpARGpeNB
w0P+mkhp/MgyD5qZ/FAxEWIt3UZd94S/JhDSfywiKJwn9ddFwOgUN/3q/FN/rEXd
TxzZDSiLt24rWvdj4M8UMhVGt1GmFcfWYpexvyQ7yV1RG3fMIwLn8/KjNNgefwD9
bA+uOTbl8obh4h14HJL+7E0vlYpSsYIilBnE1HVl5YVOmdK0ykcD8IhmY+CsDq7g
5eYNkxdFpIwd745zCcuSURTkTwHObUlNveSatVeGxNRw1m/feUmQYUdHZL+c3V7P
xsOmt7Uf6SP/bXRD7DEXHYTIsQEnIg/Pkx6E0pECxfAdF7TPYyK6BvO2xzHLHGAR
dp9vKpoWghqNwUFlt9RIOA0HPLe0oneVKnhhjLkO/jc7pnCB/phCC8ZENZ3yk1ft
EPbBOEtxTVPQVOqmqF6lxO9To2vH2PJ8NW+jieTd7dvaUI1b1lt11oZylTS/hWPS
PwEckdxOhiar5rIYBy4pJho6ni6c7S3f2mNKRdixM5vV7ehZqCpw2JBcH70Qim4N
saIZICrH/hRYpfosL9PGJw==
=NDkO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6d640704-fa74-4f02-a19c-42f046ef77c8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Ytg+m9YYv+17qB7GDBqlve6WSCQwtP77nnI2T1hsVn9h
US4RLRtT404UTpbWgrkMLVY4BPOTy/to52q++OoESC0SZ2kFBWZRI5z7HlYv+4KK
XC1+APKZ3m6KeOVnyq4mYv3TWPK7tBUQkB5Vu1ZQ3SKP+jHGkLUEJi/O3mtG71vS
w9AZkwAoprVUnDSlfpdMhdBLMnRxzYc5wHiidSzrQkD7xgaSxFb1oJjeQRZicZQ7
e6+9X3qNR4NGHiv70mH/1u5DjsA4qlIp0fNKVt5jAFxaUDfwHH5TsYmQsrbLHEB7
pHmKCMVNn5hy5+rrGhAKITK0ocqFEStnKTJ89m7biODWpWvJt5CnL6iyAXFmQbpp
nhDuNoBwOhMDJkeaq0OT+ZcAKfSftmqJxUYgvQxiVxk5t7bRgriZQR4ZdpRtCxkL
C3dmvjLtbHj1FOvt4JMZ4ds4o42nhE2uzZ40V/fq/mpoUrQicMEn7wIX7eVE+K6e
yQSoD1ZeqCh+Oj37kEGPKKy0670+A+WiDNM2eptn1x3paH+npktwKWk+TA8rI+7z
pzWYnMjxAAo7YRB531zrpnSNlHzyETbsOmuNkvLoGKHlCmrRKgYW2E8tBKXTXJc/
qnFL4ZhDVhMHNUt97nim4mWAqjgfhxlMPdvEYsvA0PeRdIMZtwPgDRJMHdiVglbS
QwEkTh6G9x6QFfKmK5pkvWAK/ozcVV5o+os9GgSNMp5z4MHDKf0iyoEhYjOcc5cl
NUyFulfbpGcE1txOJ7uEB662Hlc=
=Awx4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6e9ab853-64a8-4609-a58b-02653c631475',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//QCqYQ0Ew22a9LdcMJ1DpdTMagtZMvuJRNnS5LGgcblh1
mXUJgZMuRX7IFy6/lEzd0mT/tUOeUmt/rWJXQC0yCy7Lvse5oXRR1n+kWMASIIGT
0NZON6d24ZmdvaTnBruoJtYxAKpp85do/Ag3TSdipTJt9M1fnYN7JU/sCVuftdJz
zBoru+5wr05wSC4+gWZuBVAn8nLBgv3Wsig7hea5RZUCPSCvdj9omk42hcxEE7HW
XXQhf1ZRXNP4JbvNOsyb5KqiQG3Nfk/HLbkS3ErP313VBU4G1tDhNdGkkDkFvYit
/fL3KWzLIunmuVSHRuRW0X+Azkke1vqBFBfNB4N3z0N6fnO14BHoXUfIfQ0dFE4f
jRrRLfdP34GJ/NPw98wINlgk6TPT/7Ne83hUoUahJZFKX6+ORIAtaGcfBC30hJag
m7Tk5FQQhjwcLO7r4iLmkwK716HBpBbGZqOz5U4GDyx3t0cCXTjB4psmi0sIhaUo
z7bBlCKRdbB2HikfRngQlCidCk+b7oWlgSD7VWmcEtr0qYewBtVlZeSGbIZDsvak
JQCCEfo9qUmrDmuX/YHxOVV7cDrgwRExL+GhMgUcWFEgZ2rVqrxXGzrDH56Fs9MX
pOZk3BSMlEldOuRQ7yNna3NU5FP0N7E8wPMf7/K2XRRtpMznfE55RS100CsPjTLS
QwERnzn6CKj14XubS1/29U076RNM9EZGXqrnE2Wjza/LsdCdK+3bo2ZV51KqtSFP
osOAY9nWCIEoyRHMP4RfKLYvLI8=
=YHqu
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6f29d3ca-fbc0-4b59-ab56-7d2a03275552',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Y/i3z9/Tb99XOHMZobIKB4EGsxcEu2A7gYgKCOYg/oKN
8y7TI8cxgblevBduRQ74AgBPygvRSBV2x3mwypDcZdWqULDtGs2/EZdGEtMk+7gw
93w0QA9ZgfrRuC/ju1yqfL1yotjQDjHSmu7ffZNlPnGDYydQK81hffzhZxoScY60
RyzsM33C3dOTQ/QT8zY3MEOPO3XhFv9puFzUuX6QOGrDrm0P1/XhuTVbzJihJGDJ
v2/GiMbAfFXBVnMOJ1Yd7ONMckXPWdcyhaN/s2SB33c50i4N/NZ7lxh47yHiTXM8
dpuVzkmsj6nSC9NqYc0RlWfqtH7bugcszygeqEKI9XtymjZr6rVb3oMXEmXMiGOD
UskxZCi4kGnk2w+ve+BBLqeY/dqmWZA9FUu7dxodtYJRKFdDZ3nva8IrFkmQnPiZ
UJOzV+CxZZusuqQA6OD0hIrs6+hHwaAt2HS4KDqU+tq4kwJZbkuKguKW7t9876ZN
b1D/QKrCrQw8CUjvt2Q/UkOqWGYMZwdXPVcmQ1HojLegxq9dEZOiQbjXFqtF6g8/
KPo2r3ilQvsjUGnevlIi3Neh+KI7s+OAYv9O3B5OmGdMifPr/qZwCbUmDFXHdnVP
OIeDFv6nsSDTyquyTxxRAIvDrSpiFGaTzW0ElqxS8vbRiZt1cPE+Rd6mzbbJMNzS
QQEyTSUq7tXSh5apwNM02EGZo7vrbonyBQGwiiaD/jDl8iyLbuz5hbPfVxtDGtSp
llUG3u7/FZ61aqI9ynRBfRjG
=xnnV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6f343bd4-7e7c-4f25-a58b-58a54627f251',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//QL1b3/tFJqRGEM5kSlTGHcrq6SHIbea+ZiiBUO8e1VBZ
7oYClhUPWLFiXJJPc7wI1l7bUO4iN098Oyf8VKgMlvGyYSCwCTaLPNr7FkGapOZ1
Z2bglzSrORVTcEMy1oIX/bOAt39rUafLZNgdzREQq0p2/9smUaOWO4SswERwiFcx
HvuCj5jUmsa37jKVmom3X+z528CTTuEdqLI6p28E3tvQZEO8gtGZx9xb0UM0P9m4
fgleusfaR/rP9UJYqA7Tb9BD3WTqlk2e7wZYDmqCwoRS8XoNZXdIQXrYEMWWmIqk
3Oi3qzvmcuAk/L+/BuLgu27w8o8imqcxW6D/BO+leNyH6h6wT4eRW6pUOD4PUf25
kwCcLQ8TSxZVNbQ9QVx7qBlIv6UDxpKhPtcRM/Wo9sQVBcdakOT8giPeMyndrTkH
2R/B1FWp3O0domMuwRTu/RinoCchV92hpB3hN//IK9i1SpxseROXYwx1MRkw5ecK
E/MyHDVEncXe2f4efpENd9rmBZIReGZ16syip2xxbWtzQnBriBFk5Ao5UqSURCuy
b7bjtvzoh191qZsb0ya/PnkP8krexayIW9NG0RiFKbXlE6oVV0h6yY8DQp4pGbpV
Oud9Kvwm6xKaXmbWe/GHyjD/EQ43HX90tuJjowAANtT0o2tkCnUrdiXB6kYVdOPS
QQGzOUFIXKp1kkWrfu70dz8saJHl7y8Vc6QSONvPIHD3vtp/S8p7jApMkG1A5vRp
cCtfO3G0qXKBbqZSA0HGCkKE
=V1wE
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7028d80e-290b-42f6-a76b-ac9ab4c8f12b',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//cl8hkTSGIE5+gpNsyIZgFUCJCgUVnjZyeIXZBPR5N7CJ
VZgOrXBZnzh6WgSUmljGQ2PZ6txeqBSSfpa1OzKTsSuI5WjxV0d3Mfxw+8xXZORP
sOEXqszqkV/7DFuLiTk/tDmKmf3tX0eQAPJTeaQXE9RbwIkmxr4sfIjGXXEowcYq
IIKfiCr9xLN9lXYZIiCY8DNunK4Pj9EMJUaWZKLPqxyemCBRDKmIp+LZW0NBMERp
a8hTqWBvQhF4I9i4i0+wda+ntD+HIdkpVi2qTvbVMU6NBtF+iYZkXvbIlHaXZmc9
MBSy9vS3NMypgVSJTLXoqHubDTqGODKqgJ1qvGj8VR7y9ptHUmQi2Hza4afFlBqk
2mcaIObTfj5hBAY38jjubsSTMPfhTzoKI13lGzEr9QtFX+JUsAhADFKzIgcnM+V/
QqHL/gL3/SE4Q5so/wSEJ5r7LZxNqSLNQIiPNXLzj+SL35sdnfI86y3NDVF57Fk8
7fO8Se6jjSLJtx+LcpDf+cPvwkRHYOcgQ9A2EE0gzoWe8K3O9NmISPxlnUNI6++J
izsrolOymDt6MwDazY+UJOQvyDFcPwhH8SPz42QKRrrypu9l8RFNtSG2SNbBhyEM
4DxptM+PNWfgt5+0FrvqO1JO2dmAN3CMTwjPIL+VbCUJAoqwNRwhGFDZwm8twUDS
QgGtivhDnVbcW1VeUmQoEz5OAkHdecyd7dGiyarrbx08mJwmUtSnsroIxHi1v0pj
5ujrTf7POpl5pe1RnzIZv6D66w==
=9cwm
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7304bc44-076f-4414-ae82-6776f87393d4',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//dP4jOYb50gOtMAgaOIsP/+wigDj54pLZxIP/jNBAC76n
k0m7g71b7TjOOhH8/7GmsmWozTiSyqqGdpZV9dFnbX3335VjVJCuXWCYNETgc36G
nTFPyrtiifFbRI0np5G9GLpZJQvJKlJVK7sfSNsDi9mM/Hukwr3Vucn63LaQwcxg
N78W4Erzx43Hyc2mvGO17BuUWKenVkMWFcYOvrfGxc0FSguvo7/0ysoZYU1bmCOL
Ija9I1A8nO7LT/KWokavY9G/Uaae3svU/DgsEaDqnPSRiVIeSSnEAOQOek7+XtXv
RC7t2IO/AewqRrgZBeV5+2/fiwk07bzTP3oyvVWAqmc9Fuzn+fSKmi6xovevA4i2
2/aUVPjnFTVQpxPR0yUAK7IDNNOgGZznGTSGRlInICFX6Yrfynz7mmbIqKSROM/t
vLCuCQL1KEWzr8/gP6SQjQ9JJlC7gHvrsZk9Tq59nJytFSD5h1Dx+0lXSKO15c1L
tZsToKfSSuYKZAuH86S1ivRiLgfgKDy53CfRkXRjPasYznDS88l2pMvpT+nLRnz7
IbGEFFWTdvR5Ww1RXWIzJq1tnXVLjc4HWRSpr+k/G4DeL9FAyL4RwBNmKxsRzoEP
XK0EUbIA9UkAWNAnWBj9yjGuQgEceB25ViNO4FKB+3nELygCtlGh+ogS3qJb+j3S
QQFwbOl5tD/6duO+OYs9asvveBzUDx89TCrJBvD2oPrTMPFJydYFuqIUxMToHEzV
Un2nKfadXE6lPto2/Gp9L49c
=UqHT
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7792befc-f515-4752-a718-7244f34da857',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//U/MDfX5kuaLKFx5h8dWGfQsEljpmXv5mlKqQhSBeN7nA
1OX48Smxkrglyxzpw4uF2V+4pP6gp+BI5yoMXZZNjmIvWEjeEW5pKD3o5zFRDWql
HL98lrS7dnSpZfiQb3n9BFlkm/IPhTE0FFqyG65GUFgwjxc/8TIqByRB8147dhz5
eWC1HFV+bd8+n3zxhoGFeciKJNMYTpp9xroSChxCjR5x36kD0JH9T9FPmqmCyQo9
awLvvLpd74KuqD8ri8LnmQJg6Gk/hzaCvl+gee8OA/e/kn0fBJ3ZG5frXL+38o2M
JY3ZxtGPYm6pQbyHWo8GYXTspjyT4EOhsbWZKZ23O/w76b1RZEOENSmUjCkmTpQX
FfODQ8hsUCAdhivQyIZF8CkB+TH5Xc/te0tY5PfG0aHqE0U/PwXBNXhH0pSAwEdY
BuKCqXwpHl37+RBt5juLvuHQVVjQZQP8ri24YJXoHangcrhRuivFtbDFUSn/iurG
3SplFHO/Txi6xOxtbwa/GZAtnRvpre+BM54MhrJgWEKb+1JitOxABcDq6XKZdF1Q
TnkMfGHHmrQXfJi9TcQkAmltWAV3vGB2tVlJDsFg9C+7nE2BadnK1fu1Z+/PRr5W
ZXgxfFzTp7vA0Kq2BiLzAvQ6DuLdphaoMisfjJ5oiCP9crk2SU0Rit4j1QkWSODS
QwHOBOHZFoz83v6tbJcvBVTMVzStEs5uWtP8K0KQbBZMTzeks/JWDh7JeK8R8cP/
hGcYgWkS/UdfRWdC0W/8c3NktQ8=
=8F9e
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '79960910-0ce4-432c-ad84-affc9958fb6e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//cDruiBm36bObgLxyGy8W/PtdDnsAc5DKuGmrbO6ZM0uy
JMEc4zZ0tIyfNddNMYG3UpFNNGUeqBCeTLYgsYE6av3+/DhWSodIiV1ReTmt1nm+
6NbEionNa9Z3XW3mUJfxJp+fxlz5ufUwa3vyOeJXapDTQEG7N0BEc66Ikvv1WmoW
2K+6O5fkhQTLivWFg+WyF9CDXHDXZ6I4SxbkOngzz2thSlw5YUS4JgXaF3NS9zWH
/aigfIIWFdeceHHF2B3jFaT40U4oKtXfRA+xa/qN8BchEiYrQ90++hZlZMm9lCXk
SI4PBqUVrLRU7Nsx/uTeZLUHnq+DhafoaH3jX2u0UNBH8A2LI1nl0AKQoXgUhaup
JRpnEhQ3T0R1+EhyJ1NYift1rVjoy2Wc1BEDLsfyTalBNoGD9yqIqp0IKAgsnLZl
KUYlRXNlc7xfUF8VHlKs+7k++jg79o9KW1bVASlGkNFl/jYWG2BaQENLHmQiIbEH
uPG3rtWWo9vI4jRn0VtP/79QnxJlpFXg13r4xGQsy26kc7XyAREOv6EJNxJrWDgm
IkK5wM4nuiX2T9SF2hi2eIYS1828Z0ktYwKi8kzlrDW2T6UhxZP6nZZxJRSO47f8
F5V2fHHk+FRg+UaKT+pkGnYHauL56+Hw+5cMtGoJ4e0tK7L91vUsBK42Nq8jSTXS
QQEhErlaSb4mAtT7SF2STKFMvbFbCLlqrgyEnGmIpcKQlgDp+YupzYys7z3V4GT4
aVibqEgMSfW+h4HdgozGQ40T
=wbcv
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7b9dede5-2e53-41c7-a07e-fcc54b202ab5',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/7B+zfZsbc6l4S+Ad8bG8/ExM2KOeg9mAN1V7mnEfHtnbm
TN4ofJOQJLsbv4BMhQOXRoCW18hI2q7nUkZTbygGzxBIPv3V//hFJlzXGw0sKY6S
v2vj7ckpffU2HRsjd9qBJNcjLyfqJa0FrzyxNNTJ/f239iJgx4ybaMMW/+9dGuJ8
/XRSxyjsSeOMHFxppwR/uKAbUT7Xwow2InowQrIYgSJuyk2YGxxTVsgRQY5C/YoG
2Or8KKi521S/yengYqSsPxR3fDALJ064afeWqL4B2DLeL+eNzC36j2RqChwS50ML
eR1tusfCZ+oGDJV2S5eXXXbP0kDvZf5J/FpH959YzSaCW2qRgsw5JSWOCmmDwEE4
fuKVPCNqhrwRzIosYAeNWClghZQfvMci+ktk/jQQHw7jFjf9+ozFsOHcnwVvo/ls
+wFJh1BmCFpEfOK/dn7eZWTppKiqTCa31XUWndyw2JAkQ3QzmJ8JOtYVw17+lLn6
/CEM0olxC9gw9aVHSvNo/N5QG5lqaPqM+qSEtN+Xg1KPHbKmLy3LLYYQMhqAOm7A
1Xf063d/t/YIxKBdMmuH6MyjQOvY8fQpwY9XldTWhBH6hVMUdKivQMIGPGNvCEjG
hsjgGAicFAPiQlDG7AFCtLWlI5AHjWVbKaAZcNkrOGdMAGFHMoCi8GNhWGzTKD3S
QwFjbdTJe2wYcY5nmEqhbT8U4G8x/jJGOMvUV3Xmhx5hjd8mMUpGpjZQ4tdFSDHj
AseboORfyplAhxomcwbK6d4orZA=
=vDTB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7eb7b384-58df-4bd6-a3c7-68c6b8b5d616',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+JclZmybwnpJc2jTy+vho+0BdDDxKhfeiiTu9jPfij0uS
96MZWy4hhWKZaV2lU1VkkjH3XPylO8+xQc+T26nKiKxUGZs7XtRRUWRBGrKmnnej
iw3y9mDJYrhBGVCFvryAwFSQyH60wljY3HJxE2gYdWKuKm/zAllUODVtWS7UFip1
Tz/FGo5HTgNovdYJTk92Cp3OqdEfeAkGPofTVrqFhMmjJo+jSb/+wdBSTyLozVsT
T5Iu1vhqkJxgS32nd9hjNcK5aJ72LeA1T5+ksEycLVJ9yLJHKPTBSjDp2Sp9FnWq
MZWGv0v+M3AGzqNfzIBu7UDz9cd72LIhTQqfwERYQZBtiwJY3tVLc8jDpB7Fhwxp
XzwBrkA9arYXjs5zxyNE0UJnbe+BBhLewzwvFN8cRIAUL1SzcIvsRb08YMG0mZKv
JRBkxrk/KyABFecMasV2zV6QrgJbX9OsGWlXIe01aYnj07btZsWBY+98l1dnvINA
FQyEvTX6BYKj6Bm8IapPs44ta5XiimXgdztan5RdyDeyewqwaHBemQ4oqBBHgqDd
mAM3PNK+hZolJE6ZqnIeSvib7dl/6CVo5zOzAjk8dqPB6n7oLCD67PnYGU5e7sEO
ZI5kZiiO5dgdWCFNGnBKecw3rz/gdOMXn/TUI69fnCm47CVbhb5iwdRE0CpHeMDS
SQHCO0g9CZ4xxwh1bhzmM/cyV9wHtrdqZ7Xmo1EbTitmBMdyNvVP2v/6bGZmACZ1
NQSNkxZzI2I+g6YZd4WqFv6NJqeE1VJiO2o=
=VinQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '80b60b5a-39dc-42ca-a861-6c4040c6cdce',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+NpFhy++HHpxrME0E4cF35gH5fNamCEAp17aul5ljiQH6
MLiJXPDpiVnxOXCc1aJeeLBQhM1J1OU+l8MvGTtWlazv8xGBO65uXhQI9jY9KNEA
uKz76gxfAWr41LQ/zQlkQ+X0hdGfLzqxtieORoK+aQ7lVm/FzNZ0vUfXTQ7CtVK7
R4xJ8jHpgC7+m/TTGGXnBL/8MIHCqRHftd4BkAvPUlxa/RhWrMF/JGPfYS0q4RSF
xxtyftBcAyLKpaOMFiAROgCcpXvSK4nDlpMhRbpIaB0QH1UFI6zAAj0qV7G5nHg/
FnFU5ChWASI2tv5vSG8TGeRh7rdFPik/nyJ6R6HYxFPZPQ7NLo2OxHpgYhiz2urg
5DSDzVHfb68r2VpazDx50yg8JVw/bJDexy2/f4hIRbhxLGNy8VMk7LEdTqD6poGR
ZE5uKfROQt8bpoiHsgvwaA+k9v61Hbk10MiA4c9J0zqqmcKSihZgX2sWYrU/ewBF
X9MCmRgd7Cx+AnHOKaV1aBSIM2OoznZShB4lpfSaZ1f5lpE6MYuLDvHbpoRmQ4Ji
NEAlPq4AwOs+nqn4uKlOPkB3m8Ov2LfGT5+7oWox3XbQCVQKKxguwt5srs3TXoSg
42uhYKQyUfXanFUZq4KCSO/Uzp5aUOBoQHXjI73CUtinX1RzLtkK9leTV2Gh3+LS
QwELRIUtqJpIfzDLS6vdEPu1onca8T5glYyVa2BXZV+QoxJ+u1IexCu9j2kVYw3h
lVK/am9Z9UgejfzUAQe9GiR3gZ0=
=0Dks
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8450dbd8-7f38-472f-af15-72f13805197a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+LRkHVICreFqW5YCoWyMA4EKA3YREbWV/WSIWuJuXrEvL
RP7GvGaX9X7+SLNWVge4dZ8kTyBWkepfATiuYW0AaVTZW/nFA5UXyOTcYSXMDJGv
FMkeCXfexCSmwBs2xfvYbkw+nxLtg2pKwEFGBmAbdp0FqwZKcdqJMb0Ioom0NxWm
GSzrhYjrIuE1J9x2KRrKHmJZuKAplmUfuBErKaEioAJQWV62UQ44WlhQCw/NVbi/
XDauIzXDmC+TooZ/bR1IngEAv6/bYAzL6ZWbvicyi/i8L0TCF63Xt1T9BdOupwlD
/J/fMgQwdSsGypKm0yf84mhJaMSZyNvIqZnf46ls2N5WZes8kYlUaABlIGICoJGG
r60zKrU43VQ3OM7CPzizht/cQd0mFXnrUcQWehEkPNII2Kj+HThIDlEsmCGX6d2A
8sBRdBrphEbjFxCS+CRnsZdSSXtZWujy1egASgwyr/+DWIK/NCJOI3WVjWHnXqFb
7mi6rYwXs9YbdKsF/1E8O/PFhSGP8cKcDphIiZmWTeV397/kurdEtTkuVsJOPpu3
XSRXEq/rzgskv8NidzSgkjsBW1sjMhHImhOSXwUKToiDto/cArNetPeXyujUa/Pd
bOQ8g3K/KG9nf1Njuf0HcfcHWMgHd7tNHDh1n2tpITWiRNs7CjjlJAnjRlVYI2nS
QwHygmc0DVa649uVKtKmKZRIvX596acgxqVKfnPgUTbjZzS3fDIXDmRVhItXplLV
nbhyoR++LlHGkTcz13Ax3o+C/mI=
=7R70
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '84d2f021-5e9d-4f55-aef0-ce02a17b6d4f',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/9FPreKbNkpuZxj6RWbpPL0TDa3Sw0eRvausI2ixeY3DZw
wjtKNfbWZWcDsIIQZIKOmSTIA+EormuBJlUwgRo1Tt0BdZJgA9avzQDT+u2zi3ex
2jIegiT/P8KHVyK0XPbVhALIfRl7/AmF3yHgrUi51cuhYC88hJBwho9P2smTsB29
koeqbtp0DHT49NXcJvXxuvawyvZjPMIhhvwNtU7e5m/xq2VlbFdlVF6wLiD0K+wL
L0holsqVdgiRWNt0GDUJT6Fz+m0fCUAONoeZjlZXpjV9hK5DhBeadGEqjx9pR5H/
zx020eLpPPLhZyC1IwSrTZYADkwAFAfiBeB+e36l0WvQC602QS6unMYVAAb1Kkni
iGlHeq8g7ySapJaj+T+2h0nBC3Fr6Nky3CPbQxV7jWwtwVaZSyt8rqILDROG7Q/Y
m6nsjq1kJyDjz/CQq2XoqorTWwBNI0NuUHO0q9LQZOoH/hJ+v4u3P2HYnaUet9Ry
bEz8adx0jFyndQdlYJRjdRXQdjNUbTGQbEiaq1skXVnvwcBhesmqZemATkxBLTsa
uU3k7EI701spZQ3J+qkPnv+IADU+n3dJdVWmK9gJqoTFwS93zEdNhxvUlxiN3pa2
cYzWZptYZTNH+89z8Kvfn1lsVsj10+Gmuiu2u9fFADuwmSETc4+RwtFSrG71HfDS
PwGIAvzTQG0XXNFYr5QBlS2mJGRERrLNtDmjU6/xW/ygY9yDD5CXFDkipTxOjAJp
ztSfwt0uStZ3oxMAIbx/WA==
=4U2l
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '84fcaafa-8be5-4926-a579-9c7101ea8cba',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/+PTcuHCqVy4qSkAyWxCbVZM+uyrTNrSwVSKmibEdNhk2W
CMlTw5ouqZW43BX5+Q7LagVUjpGX+gc3b9aj9fE19vwpH1KWQhqqsQ1t9ph3Hf1r
8/WM+OCQJ2Q12Q9JeigDB4VcPkLcbDK5KVRySWOFHGZkN+eiC0o5qCNgnvPh8TfA
buMbumklIJZWhr+C1W/PgDwMHsiHJc2fdW5mKh8+g3ig+J71jLXC6E+Gm64qG6SO
f29LEEY9kAqTzxXIzC12g2qiPH9Sl7dE3GAowC9nf8b7XLWusm8FXDEfzlxsN5ct
WFgJEg6J0/a02ICtveGmoeA/rdjEMv48swly5Nz+/AblfcHQP99FJBvE9mEUetFH
SJwJG4KYrjIHp/oJ4Us9aVscmekgp61ysuM1sIX4tBz6HsUMKRgHoh4JbFL+sASE
+c9y1uiDEZV0LaHPrFEEBOiy/Z0wui9E3eUqFi+yK6rRW+RhA4zlZFhLXeBzrDAc
muWsYmkG4NqVg2MPRC/ys4jvCwGL7j5zJGVxPvtjs6EsaiWmVeL2q6c2mUkHH0EP
Hhh4hxYyYLA8eJCTqmA+j+/wDOFpMyp7p9jECgUSmxHukIuz7hb5bzb76ShY+Lor
2oosXtyGJlcCQwEKpanH4l3HTebYmxylWd1rHdFZ9XfLdTtorziVjh+msIA+MGTS
QQGZZ3iRedvRZ6rCLRZHY8FZ6RalwbhKeHfqaTu62nmbsBcedwMzgNRMQqq1iuIN
csxCBJhC/PhPTalhv5h+VLZW
=flGX
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '89ee0b7b-40ee-478f-a040-d4397a2bb9af',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//f6+XIY1aS502G7NMpiY244lWLEArrdvsHccobK2sk1AE
ArUpvR8YGPXcSnCHgXFTQjeGAvjMBaBRbJO1wJkdp+WYowfcEeDnAxaWwKLEl+t1
Sso1Owpp1f7ZcYSjM/vyPl66Bww1LzZ7s2YD7+Qq/anI4cS09kws/etU2liuJS5w
HFllAfPvnH2iIyIlsNflpLcm2JJUD3UJPxDmKgJUJul/jnxZGyAza4crdVmGRHMZ
JzSRbhGbpED/wMCL2wK7sqTC9dEpPIR4isQQ8nUOEct4hpPdpHw3eAMIiPxVQFk7
Lmru7/p7nMUPBtQv0Tz7rJxG/nTUsgxqWst/2PkBvHYmUS3X+u23Jss44O+MmOdb
US5C/k5QfWw12EGEHLBoGWrRmq9PcsS6ubwHLy63F78md8aFL3tOnawh6+eG7RHd
S9vfZBYHeOGcbHk6CS0Dl90+KMGX4YQNtSD6DIkojc7AlCfqUbxAScEUZqvzmuVW
1S24ycfN9mIcjOAvvunGAzrsna4AGPmP8DcQERvg/3NVZqsDtcuZ5bxOW0qKBFXK
ok0DNWeAyeGhuRNY5JGOHB8gykAtJsrP49/i0fKjRQ20u4IFwcOYfWMSgW9tc3ZT
gUnUU6bnlOHUdL1GhJ8MEt0m1AIuFW48/0NDHnyc82VSvijvIl+4ALV+MxkVTpjS
PwHeFFeLBQbFc9uJnJq8WVylosub4/CQO2eF5NiNpiadxfEJVA3+Vh1TP+gtNVy3
fblY00nOD2RJ0+Vfo9rFUQ==
=fWC0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b65e18a-c723-4c29-a7a0-4490c6744d3d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Ii7JrT/rXlObR8YSweq0HG36hth4oMxe1nx3+fpnBQI2
BIFvnlfHJXcWbzsKYDvsYx3uzHsAmXoAdDAkqejZxmxoX752wRvbkXri9IIGgVUg
qpos8ngjfjj4+ELoQTJDqwa62UY0JWUHChj6izTYitRiW8EoU7UiKFPuwxOwGtCx
0FAnb94YIhLnVkXzXi9Z2sYX7uxcxkdC1JCKS9mUI501/LovZLLwIVfUkvhLLWvn
KkCXo6vfQPHHW+1B6/wvheKBxlMqOiNoit+bQCChyyXTYS9CqUlCeJ0avHaIIzGF
KDXbHjc+H+N3tuKfqmgoAMi4ayfgWWZF2vAz/yh+DmLQagG1PPUTZTjPJ1WQwSr3
ko0dpxHCB3bLlANs8IGFy+SM1TSOjw9sDTA8VfI5Y9q3GbIdVXVbrA4qtZbEhWX8
0mEIr/8sKWmjn3FCDWNRV8nl1+Pff193S4aA+VdJTuTDG+Rd/kxsQrouSj6L/k6j
BEDnD24+yP2NNPKGdN6Lxd4SwvsBjdvcZdFmHezwoDDGbyFP+zMI4qk75d2r6VoD
mXW9rUqLy06PFqGn9g+786oeWjraZmrbtMcVy+I6C3i6Sgw+vChaqlQAwkb8QexZ
yoOL4ZaW7WUXnpIJ7Dyu4P+rEiHD4lis9dAjJGuNTMM/gAU3MWQG7I4mOjpeaFbS
QQHrxF1dUkBUdyr/l6U02pzLJ6KfFOeT9LVrICInLv2tbkWp5oqxHH5Nq5kfcmCK
2JcHkvMhcgVv4ZR/VttHXOBc
=Qmrr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b84c057-46c9-4f89-a8cf-e3da280de801',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//VafnemwoE0gtgYTTtKeSRMlRLxHIVTpf8Ih2TJ1cGXn8
bEO4PR06ltbQRYASbkqD+x55pLRo7LgO7bbp5Ouct2NP5XQzpzOqEpyeNO6eZQo9
r7Biq4dcxtRVW+tJVQFUxJI+khZgq9YTp0cR6teZT67DjoiK9gemS+0NTf4qXL3v
7PdBhU4W+Ew1gDHfIodd2ofZo+EM8jcxfZaoFkECtZfKjPam2fecOmNY0c7NDIVL
+zj2fDojf6FdMQFPVGewjP0oLxi7FRDZexGDahYQ73Nam2OWHPL/CyvK7x+Ra/UO
vUPg21MaTSFH4fEtUh1uXNEWOPhcIV65dpIZdc16dikVCHxvdcEo3aGJ/CZ8wCq+
4KEkRrfdncHTU9FM8gSsbaqTVR5DQ7ULSHDwzrKFzCfE8SAy/4CFhhlD50AvaqM4
p+nePtU5AjjNibStp4SZJ3OBl9ImqCU5tjlaNWMZBSx7Tzz9dNTUQEhIS0uDEzQT
CVehnQ7p+fNXaeS7My4EJ8zR0G9lxyRvT8wjafekz1pf2m1ur0249HCdRakAz9or
wRAzKDSG+OPQLf/fFr5zeqjDjVAvEQEofEOpjN327SisjqADmWm3ZCPfq5hoNGXz
UHK7vtYFGpNHLbgLKpOuKeLs/yiEMAN44XKkD3BP4Tyz5qxwH1SNNKHWomNiQiTS
UgEKEOz3msWo4VSoHFnCnb+lOklAiRepNYFEjnht3XyF1dATM9oNLJUsPjI3h/T5
PiS2ZWCzPux77M+dvB0H4cFTWg4PZaTP7JepQVkEEEzuW3k=
=oqxg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8dee8d01-7987-43ea-a782-d100d3b0dcc1',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Mzn8V+rSfwcXGAJPostQzkRiy9kmRhNxd25u5Ckfo9TZ
ByC8N1TiXHPHZ8UtVeZYOQ3h+R8KReM2qdz925WM3rEW4kE542FHrCMJyLhJPtl8
RH760ScUGsyNfFNN51VMdeoGwAK/8dIX6z5RgBdYgAZ8k1oQhqIATZavURuMKgh0
oh98s+hHaxG6pzSu10Rx1GwiNzmzpkzjp8maY83LESxPnLzSCcjVmilXwG5VQCwG
r9XfhKJTdpe8RZiUSQXXMTBHwgbMjc8/qEll7WGukfeudjkgF6ioH/0ydtktib88
rL/FxGoB2KCNGOTK14QoXDoiqMeY3+MmltdOzdbTJ1K0VFyjicHkGagkmIqWzr/L
mqKBBrT3JqfJ5on/XEcGgO5i3uabxTvS8+C4NBegSESV/oVT79kJrlqzsbj1fWe8
IcuODAjaGurgqcjN8Cj+ulNvNrGPT6LOQh2cQx7LQKUTYmeLVOFgK0H5ncBK3vID
pBiGLlfTMizwDTSSxPEs4faoEgRxxOv+NAL1mjh7o8d7KJ/V3dV+hLMJKdBX5LUm
CB5iFEOd375WkYiTCZxVjkM6o6NJDf+JGQiWje2/JFXW09APkFPVJRRvzd4yyInw
u90NZkyjTiYe3QnMo9hNg8JE8KdtTUBTpoHGCGppQDiVQ/dAsuas3veasGrlbdTS
UgEMJ+8YwrUDi35aGB6RZFtcPtvpm0M+6FTYDq1BkqyF1lqJl84bKd5wctmCmhTY
XDLk1GbA7eEaqcjFVplqYkdjtJMoyrbDVIUdo/rRtdQYegQ=
=aOKk
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '91448cf2-4cf7-4850-a82b-884dba3ff8ee',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8CeGFQUEsOca09tvMo6Ow3RNaUfCsATdODcSGTIAAInhd
N7ZVy7+9FzT5Nvco6L2ZipAh79TRCS6QNqBn6rr25xH7Qf0yQmYlSBB4tlFAhZ+w
3dccWaBXZ+L6dVaFJDRnhznkWovTA7GKCKCP0/uXLI0k4W3c1EM6PJrgLkCQyhUk
k2Fnrh5M2CQF14pz3UB/ZzImHNLq8WcCkjs3WLGs0Yyq30Nr9V2VUAtvaQY5/ED4
9gFamjTNYCOOZsKs2IFr6NKDLAsT6pKsUOF+5TzeWmCx/sk8k9pO5f7CUCHviBlc
x4Rsjnfil6eYHimMefUmbzY+GQk8hZijb8szM8Fq6okr0sGoW5+6ld3/rwDH06/t
I1T5Bpf2Ti3545gB7ySwzNZHhnHusVIuG+4T4NRY7vimxnTtVhZ7EdLgXKzn+x8/
/rSB0fhuV8xmiswuIWDKBaalqSaZ3WFeYvNF8kSyDK4P/CfnC7BNPsFbQpl/cjh8
owJMJp8kaT7enN3GdIZeA2VD8f01EQ1zefta81xpKMTCLAG1KbyoXMPElNgY33dn
JMEiBjnEUYFPM+MJwbxBSZxKZWYvPkHBl9G0tFZt6NEf/XVVbiHOl7UOs+hOnNMI
6580U5JtU2APp1Ujza/kDeH23KHmv6b3VYT+NF5OF/QJ6X9T21Z0Ddrs5Ao83NLS
QwFclIQr98ELpYJ4PxpYbDj3GmC8v2VYNlBAfIcbuJErJ2JwkFOJ9KeotPBqdUa3
rEqeP7F3JXcfqwpPIKJVsn3BF5o=
=2vpc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '91fd510a-275f-4704-a9e4-62c9bb4dbbdf',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9Fk193RtIn1FmGZvCPf8mPp9tQ/Hnyj2NSCj3DLCmb1dn
B1N3lbFc51cY6O67RcL+T0Og2rDPiOgFMqdooVt3taoWm8831Ma3kWogPXTECoyA
Ok67IFAziyo07KlO2qpQsIBvnjZJ41GnoMVaQIjhmgqqdplR6jvrA/QfQK5amBFf
8HSfkjhWlYYwKkw136BYg2q+fAJHJ4XMhao1aq0xfax1PXA9t1B+QlQ9GDwhCS7E
ofosbuKPZDsB7VypgqAT/aykAEFaifGac8vuXQx64xWA6OWmx/qE+zLb/qvm3ctg
pyuSv+1G7cl2ySNAuzQvlHeULkNbFvMoaHJPSpmtPA7JRXm3RJfNt7q0J9pIPPkn
rkkaXj07pdvtDBR89SK936elmZLPFfSBaNIXPD05G9e5+5wlm+zIvGm83ANoVLiH
a9UF6VzY1Nib4hvbQr/2Nafw2hNuLgsWL1zpwyJcm6ATBSiBsCtmfZrkDRyVeh7x
eWhmuUMwLclWT1F+og+WqU3lgEsCCRvOJtPbrnIaeNY2cm9TTeYapIkCmtLw9+fP
RZaeFwF3k23RlKzKFRrqWjt4MEcgPJSb5ar1WqAofJddweOuJQYM+1qewpG6vZTp
/PUgrfjOz8vni1TEhyjl28sZwIXzk0mZOcZRvlNrTc0+O7Pe8uAIJJMiJHqso7XS
SQFg810E+l5YHNq5vuyOlJ4QgFiEmUBvm5zGxATTEgI8eZetJ4VG7hGDPIpf/AQw
J5oJ0VgNEk+UPZ3g0TPRsOcnarVf3uyzP2M=
=VJ3F
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '923c1b4d-ed76-4a59-a211-7758f7d7a34e',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAwWnZdGXUoean2hUUi7c8VjIZ38SDPiZtI/fNFA855aZ7
HXQrL2vZm215DMl5nT6t/6avdXeiNamUZMJk3mmO/UyHMPMjTdiWeHE4UjCODFQi
paddSvmk4WS5Aj20GHq36zyGsV4uGVYounKIVt9OMMlPorjJvhJnlAGGj1TirLjE
NG5RGBNmHbNk1wSeWzAWG5FsMuHYW9l4C4HxlZq+xC2c8TTgv257yRAT/IbWA9hF
Gah0s/lidLuALihcT4hVUeiOkbjWtsMErK22qoqJbc3MX5PflJ865w8IqAZmD6u7
3P5fPmnfzSX/EL3rAl7tDZUuEcg/hSH1bzHFkJILLuzM+WwLlIxVjV38OTiUer98
ueo16UFrpUEB/m/nJXQcBEj3B2+toH1/sGedMp8yFeNXJQRFPQVFrbWn8Wj1X7Kz
cwZsgi7OaP1wHwPtrExC+2Kzc8NYPAwBFmmAzHq711WxgjMUqtSX0kxxzfu7LUrX
aZWAJbkh/UmheBMgUpxyBf7Ur1XzgRYc26aQ6HZIWEIKXNUuB9iLOjTPfmlu1ZFO
0a8dG7QydxIAt+69a2zLzIZ3ARGK8WNOmqgUBmczAZhkfDFktJRcEYqdO57Puxhf
V+y0ciC3hCMx55sPvNS3W19KGO43aMNWGC8EL1zRNmf3CdP5YckVitP5r55HoiXS
PgGETCWMCbshoxfSesXscTKJh7zNqiY7oMoAET4x00KMK6qSDwxSYukGM5y9LmcH
QDCMJCH6yXbK4fnnkNR0
=n9h6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9453c989-af90-4cd2-ad6f-8992b929ac87',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAApqsjuPP3gFl3/TX67ime4fOoJcPucqSssCjp8xY2v3xH
QA/zWk7GyrBiGR2k4PmaKwCnsiXMACPFNKJdydawqn5dz5T06rvK8mvAUYc9dUj3
7yBfs560oWKBDnNyR4DLE7nAUPzsA8dPH7WwJ9pdBbbAIpq/qhPMKlXipJ6EIpTM
1RQkSdoZ1I1v4XMJqcviA68e58VKpnqm0CKaLhe6wwBew4kmHtP0qva+mQF3FqBL
eac9+KC7ogbyxHTMmRywGZxr/TqOsqjKTuSoI5Dfef9jOEGZqBi5/yY2x7683tw9
GzW2kOvkcE+Zk9Xv6XzKOqZJly9GZV+86M9J1baWYt//7fG2DRxz4xrvr2Qb+N8U
WpwdaK4mDANs2VNfWtVrjSXp4rQyNI+HxT6o5pwc8StS6hiy0BXjvsoD0AbEBnls
U7oyswDYhns6oSFTeP15Hl0YYzqKWhi4YecQZnFeOF5tzWB7wDrogUjrp0CdpPe5
xMk30Ci3KKNbaqakDRwS1xlSiMg85NwnYgzZFXlkLGklFOwfw6gKm4bO7rYgbWTd
WfjzvuUuEAcTap2NK9JJd7dHePuTDmAc+l/22Y3ogPJwulfYiZdtCDwuvcVB8s3D
JUYMMm1Tn7MF0MYg8RQ/wX9Yq/FEEozwRyaiOJxzWdv6QKnz6X3/3Lbt3vvZATDS
QQH+61VSqQTRsRnisA06xFDWEDmGadJKYTDattYFnx8J6sMrEdfRpyjIINhlHSoL
xjkV/zIpHov7clyZUChzY87E
=Z1Oj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '98c992ab-efcc-4dd8-a0f5-62c258725386',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//Q4HqdJ4HkzN3b9J5EOv4OG2g99vCQIbfUy/zi8wj9Q65
MQNYbbOpVl9EFA1pryyxYy2EO2Md1S2DC5ZR68Qs8mOJsd/XPyOe1Yj+Y/ZB/nX1
m+WxZJAOSVPtGOObTgIIVjVlexbXdIxVGam/acuPaqqmGhhafsleZEwl7Ppj6wyy
vyLhobp4Hgz8UprmRDfMAuWcGOCSOWvHabTxa3rQcM6Qcvbl82g/sReIsfGJANPI
/YVPdO4uJR2oid2Ht0MY+VT6irhxNdBzfXFO/Xp4aFzYx5Hv975kR4ptvc2wyqCr
RooHVoIOkOTZrM7LzMN0d0oaCaimofqCfT1DK6gzlczzuW8ytdOZ8fypcVRyD7MI
QUKQLSNop2qcaSNqPD0rH2HSPDo2TN16jgL5j+6B3P++nuhHjE5xFN/KTftqeS6D
iZDDW7HAIYhG+TEM6U4W/TsCXGm3NuqoBXoq7T6MLd2yzmyXx0hKhW4+zeksMvNS
jG0BFnqHrc81l05GXizfqVkpD51rpUjl6rF/LHqnhn3Q6dvB3cNU5QFqmdld39W7
luOJQa/rAd0qbC5BBy9z6Oya3LWIo3Oj0VBeY0EJESPmB1XzvVWNStgDz89rB6SS
cSWydsMNsXmoQDQCsF1QHkiJLTpUjRrQDiXaiPCOQwx5qQT3JyGDPArI9/mrLxvS
UgGjIqSbkVw+IyeRtJKguRXq28Kg0l7QeueEfYGVKx0ueH8Xclp1PHAC5de6KUgA
Bf0e8vJcZLMZRq6uzU2gtXqPTsNE96d88T4uFlLyasy4JXg=
=cmQk
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9bbe0737-e778-4ffa-af71-6e301618b46e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtHqWdEXzbctX7hg+ORCPJJCUDOZdroFMf4Lnmy9nSGLP
UPpHXW1oevgfiIPfkjDS9t89XgkXtCRxLxezMnHHQd3+sY8pg23p0DefQR0muKlt
H370MwM2zB3bS/zqxnlsjZunx36CyKjDbJmXqgAhj0UZL2MIJ1PqnoMjXcb56W5h
aVoRY8jQRiNFzq+eBa70LDMFQ1j2bIw9Ihkr1KZwwtsThMA/l3aYQy2FkbvMKrk0
7/AlYyK+ktDj/YN68vUd+7zXQHNJf9PLsd1ggHLntSLl1IljQOHq7HiMGTpKve9O
YjMAZTCGYAzMKNEFHwUCPVOZMw1a5Et53unGNOU+6SBnG0L6eHx6ritMwgkbOswp
iKMqUb6hfHZyh3ktGsTIXYEPO+X6HOtnCKTTCbr1q1bl4RLpp6bVNuIwQtjH0XTe
IatDG/4dS2LB2UoJ5P0vCx/PTx7W3LWOB2Qi3P2NEPoa/8kJW5SLIAyszwtVBvEE
G6qVPy+A6A9GXGhkb8s0W1r64AALSZUVMU4bP33JOT/Ka6tAAaYBGThjgrDvWQCp
XEtMsSUYExCELW0NV/ESrmJCP3KqGXCNKdFsAsmkBYfxv0YAoFOXrBdN054KQSjX
o4c5h0cY7F24caZtLfzHToPWM9IVvP74mbSKFqajHpjiDRUGrv7PomX1aTUvqPXS
PgEaKOYJ/s6cpVjXwNvtQEq0e9BGll/a+giy0ypW4C32lRl94H0dzwoqnwuzegeX
lHCiTOSD5M5zrl4qZ+VN
=Lp1n
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9d3b2fe2-ceac-488c-a34b-6b990ddc7793',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//ZDgb3anQ+cOJgCSaXdWj61CYk1pST9tKgDpatFmg0COn
xDKlldFnCukn7EeRkXOUh1CV5k3aGS7viUszy/DsnHWlcaWgvKXRE9khY/nluVaG
ePxa+p6pkCU5E3GSnHuF0tbD5AcbqdE9tI7GFic2oc/DeAycjAZBBvf3ByBXo+QD
tT/Ij4guSBff13O0SXwN0pv3rs0wpuxKcDWqQPQycVX7ZkcuMkDnxM4x+Tmq+CoS
rORQT7C6FHzDNpUBJ04Yp/6wuvnMepKYEPL7cl9KL73Opxia9a7IecUwh92Girc3
kjiYZ+IFzWo9j5V7intSqUj4t344UOeOIVEMePk4KxPnNQts8GeOfeTF1mnmzdkY
BvhOTWum6A67jFCQLzgJpRU01ElGYsSy6sf7w/VVByeKAIgq0oK9urbZlU9k0yhN
3mq+jyIce5cwaHOQr+8GjwDByQm/Z6cVyJbd7thh07GMkR+3Yb5DQMz9z84B2E2O
xaKHIfo38VupGlOavHkynatp1caZ3RF1ypvHcf3Vt1oKJK5Gmyit4y4hPNzl9Rtu
JotMpz0f/IFUUIeGL8h/zbgpglMkwAt+ViQvaiBJezkuKCjsysAa9aYcCG7jrThT
e3FHUK4AJ+te7mTAGgfAIR0Hc7Htfsq4svMDnUjTNtUM7xhxPsF3IVM5iD3GhvnS
QAEHpDDYVvq9Omu2Q1OcbdMS/FKoHiGLJAIfJRXyTysIYr5LN8c8QzicF1O8DwMg
6qlhLacTBibQy9YiurU43sI=
=tE/b
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9d5909d6-0a7e-4285-a039-a05d70c13e3c',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/+L12I846mDz6fJ9MIkvZWT+m0la0qxLMIYIS2O9O3AcnS
ikGAgCd8sMSAlKP7k18C1X5pueDOi+Id+bvkSRNPtA+z5ioYxmAloHqJ9euWnKXA
6PoItRAkOcgRH/VLHozJ2eXEHA1myQhZqnxUyfwQulvePPPmikEQTSL2AVaAXRQZ
2ax3QkxsQ2wKx1N2PyemBSS/xIRfctuvvqQXaY5XipLspI/shgBvM/m3fXzpxhX0
NcZ5ueS4ORn2jSickglRfUyBydSgMTG1VYgGW3WWYbBUN3s+A9ehMWQijbKosXo5
2AZhGwNqnBwapW/ZTH/y7HM2r7nA9wCp6gdVLpQNt7VGmzRbnC8Z7tRtGj4D19DM
rtWvuhBjPANAOpRTok0/ArRREonnIKO8myD1NJWWQ2sdWGiXsT/8DnPPQ87rdqzs
GCWknHjeL+5ycfC8byxFaHJQGe7ht7iKGgLWOViJ9FYu7iq8GY+ea5pR8MeYfI/c
t+IPGycpnFnvFFDJcCq9ffNqeXeMZzpUkOg30VaBTplf9XKEfVQi790iPAHuQNnc
LMFEeubQey06PF91WbHR11x6DjOloKvhX1Cj7JZJVHY8uyIEINwkf8cWLtwKUKtg
PRP0M8bDgYOEm85588+WPfDDIDPzjm1vRoTv85uzy0423esEnJffys1gjItFAObS
RAFJ8LPVghMnMRAOiQ+Zvw3KFN3t01Y9IBWhUlsEh2WqzuwvaUakHrMYEQCKrO9u
oJrqP5GKjgk5S36snQQ+ONZlHp28
=RZwg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9f002ebd-ab2e-44db-a13a-996b1392da34',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//QfnHSWatbpZSB6iOF0KUQ/BaUJV2VLJ13/5NJ24FsptQ
SUzVPg8hF6COvoQHazPWLSBDLkZC5MLsxTA1fVPdFnpfh1ToK85W3APS7s8v/Jt2
xypDCRh9OpIEg+S5NK1pXUAnGMTb1eI3MxzLHayCJFEHOTLO/kc/6n7zeCqqy5sZ
wYbQ4OFbsLwxIuvMTyTJDpaRs5SyCY1mNb3PgjnojaAjFBMZYV5T4yy6YBxHh8RB
Cp7ph07YluFwmuUk+jnajkY6ZQZgaRCgKt+mtk7+uH/KA3waV3t/ntRnUTpeze/Z
fdkkgcwEfnjEEdmKlKrZ9/CDyoY5gRQFZkSiWw5BSR0KYTtTIfSpqtFE+YAnfmbf
GrqTpvXwnXkUUI8HruHsc4s2TORChaNluZPySS4+50CEE3PDA3++4nOqwE4pHaO2
Lc7TtwsOuMNInyc6jrNA0Iub/DDBK3mUmufeNtROch525StS+MoTxIh+rF8I2Eow
pf2qGMhUr+Y77WEOkHYctdzGHF8R2vmisgftcQlVfSq5YEwn0DgmSHX7anKIkQEy
wTiTK079YyY+57eih1lbAhWmwTG7So8AowM28ggQ3tQbC/J9CSdRVHw3oyFdcaXE
XVvTpfbNla1yNak+6DHdOHf9SQny4MtWoBE0+HwV3i5YUNLWjagroFuW0VzFx4nS
QQFJW5jLeVtG4litydkUj/rcknnCiVN5WG/Md2ewgZzzN6Bp01ufvoAivLFs/Uvc
LFpOQGZbzt+dBw8+qNwqsL5r
=pVO9
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a3d9b1d3-d941-4777-afc5-f37d218d1a50',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAjsmLwVDgkbLw49ej4nmviod5nJLc3oax1F1/VQotAcTM
b9mPCcyQqtFKGX8S5rLzJgeODmEIaBenwgMASG8E4nAXHfp50OzMh1HVzuLWOcB4
SXEGoWihEUahOakrGx3RuEasOpODDLOP3fKezCZQ7NZxWdgrOpHe+N4Hw8VI/GHn
3ySXnykLyxBRJKYHSjpMUxnSAPN5c8jsab7f9R3+R/lBHNE7VvvZ4p/7p45Vbaoi
mRE+6fLaDoReq6SB8B1XXkiO0B4LuWMi7innFFRBCCIuLK0DqVFPM3rVUonXeN2V
+hS+aAVyMbIf6FawVUaMuvzcljHlwAJzXTIY7Qh6g6PY2wjsWjYtTmdWuWwPphor
Yw9qgYfFxZUM9BqXiV+S1Gbw50nQre+igoCWIDFGq3Mv2+9iKZ+hkyRLEd7nJ7wR
KCIiNTQzRPVgmmO5n+ymjFv/OC0zYhJF4Iiul2N25P/DkeOoA25BiOussKxfqWUf
A3gp3TXHgEzrtH48is47FZntm6FlGLzWxCjRUdPmPtnNC4Awtdj5HTnvgAAr6R1g
Ms4KZPblOaKpeISqTgwpK1cWheWmEESx7A48ARR8TZHr/PaFzLmS4KUxV08FjUZO
4kv/zZ9i916V/RkOpCee+oaSOIjl7CFLW8EJiNIzvlr+mnqNk93NEC8GMnH3bMzS
QwHEWcu9uTswMMBrLW6La3iHDUYQfZZC2r54vAb6FP8RyBb6Z48sQYDJ7aylGzrd
z9/E+2GG/iVvQuwSiuTDeCfx5AA=
=WGLu
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a546b01e-70e5-42e5-a2d5-ba337603371f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+JqCDTPjiN23Ijz+duPYs0Kohk5uiJjaaq4js33wKUg3d
LU5RIFjvmw4qp6cRiCleitCwPsfeJYXrJF6wgQUuM9mt89StgzvyXk+KLadXuFbe
0M4Oo8vAgkEH0LcSastDflCvpA7hH1X1eNu+Q2UIZ148yx7CS2geYjRi1LUo2pWY
Won+s1CdebyWDeu7vUiPZSjZ116/5QNmsnpyz48rE7H860h7ToMGyIGsuhMh1LTS
v3tZ4m274mxKHl4J8srIV81HZqSIwdqkaK6Msvd32AVZQXhUfxcAYFtRaU5hLA6U
LNXKzgl1VaDn2d+M/9GFsGNgD4ZDHIPPoyUrW21qw3RHSmJujNdsBiQASbGxCHCf
J8FPx+lI8rXA8dGayRWwsJUorhZ1b69Cpxdt3C65Q8pf580F4cOvWA/hj6wa5ICI
KJZ9m7mAWSrTd78czkaeGdtHE/3K5BS+UYKAJkP3FNxGdhXHQ/B3ma8DdS/UqD63
i3oks9ivAFDxnT2QfPTA7NXndJvLI+01Glze8klsgQUe+HaYzQdxXuBb+eLwxU17
IsaPYci/6lfVTARZJ7RX4i2HS7N1bCwzkr1eUxpKL4XhdyS09PSpEuu5+Z5GO+NP
mGkNvoPCUiCr5RoF4T3Q/KbdqWdXRPs5dLGsWY94T0O0WB/xWilp7WOUjOzAu37S
QAH6Pu3m+9ChzIxsQHD/4uwQ6S2US5FI0bxyA2sAXSxvZJuO+u3z9QRI5N3rXZzU
zTkatnXd5vQ/zGcDVvs7ZuM=
=v8hM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a66f4b70-2624-443a-a26d-01cb4051720c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//WM4ijAP3kx1dBP5PU5nQv9mrrpvAXRQk4ij0B7Krptic
VIXjeNnGkI7EKUQrTwqPMsf712Koer8/0EQ8x5cvnoFOP12qij6jTlD8vf5WyzJN
EfuMBCW81S8I3FtIFUUZfQT6hzAHwos4xR7x0Y3LrRrBVsFwUtsLFz/TQczANiuE
0bwT2LvJQg8PihOXSMvZVLQBdhrX6BmkKiTYIhElL8ElSzVyGi8JDON8G3nZ9UmI
X8xab7wxqstM0gb++4DCCES4VY+gw8nvIwHTSpZ9fw1bLPNUW5CFhMZrKqJ1Upm+
rneX47Fh0eY/eLVOzGZUt5LvSdjW9P/PRF2HKo6t4N08c6d49wPr9/47zJ+tIJKA
avZPiFl/H1p62YWVjO3jSceeJkT2L+ed2/KaGBPxsVN2xfDWp+q3nXS6LTTDCQ8C
RSlIDFd1ZrhzDjN5S4uTSMWNoOt3togHguOWzRVHosZtzc6GnRycQDh3H7HoLMk/
THkkJzZPbi2pUIbEL3JsB7C7SMHQWo/ajeAKXeYiTrNel9ReRv7EobmpduL4z3gj
P0EWADIULBzjKiu8lacYb23ZznWUfAEeEBk/MTWcCxgJFNiyL1ZPMQYSUMffVKS7
v+uGK7JSzO2e/l000kLhzyjsIf2qu71HEFzKxE+QJeOKI2TxWuQfdTOelJqywMXS
TQGxRwND5fgSz5i/BRaay36RYIY/Qo2/icexOjz8pbO+bE3sOvkd5rD/17GIYRKB
d+vaj9juRtD6xGGoJsqaD5W98BJB7DISPm93BL63
=Z0hB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ab4f16a9-f3eb-49a1-aacb-9064df35a63a',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+JN/Q5KQDtvCEwFG0NCpQutBvFChpU0ZUGIIcON282kq3
3M388HaNQRLbuTh+mrZMmPj4d9ainCgxS32eLbq+dIFLCCZ5i5xD5p85RGcnbUqL
flG4rOZh46L6vlxWxq3nbGafN606rNo4SDF7BGI1bbf0t+KVXt4JhIUkrvvoWM3E
YQFoYbUA/2vpad44UB6jQ4J3AakkxYa4D3yn2o7kicpAPhNO+DKQVMajMsBO+8yo
ax/fbdCKdrA3PH78yq5zEKCWhikkFbh1pKCz8zbLfTx/03gxOIB+c7PlFDrvaaiP
dx0UnrMP1ZQ5HEtdtBiIpyQOhf/U4tJSlHwFlyVFZvAA5JWcdZo8FhDNDebHG0dD
N86wyGHnHow+RjwYWy4UlRpDGAgfLDPoN5o/dryCdn91YmpEs0J6bvzYxVWU+B9e
Vu2gu5Y28FR/hC3yyUkjWoRMI6B03gLN9sAlD8uNo3fx8cNDjPJ02IOvzuZ/IFL0
iHATaCDro0MY/4Ux6rx2IZaH+YfaI15LMBCS5LHSShqCE9ey4t9xoAT7j8DBy3gC
t8gW5PCMZKC/WyNrwhRAlYClCgn7B6sW9Rdz8YaPApxewPGgce0+va+NxjUoxFha
lvtlXsutVijjKKLnnW8vilIjQGpyIMWgr5UfP9yBkD6Y4iHhY3U8C+y6MJP2vibS
PgHnsIthNhTlQntc26H+sQPMEfiwZGI787U5a3fstIVA9yAzHgZYVm4xxILw1O+b
yDXqOPjMscQ5QKjBNJcm
=2qa5
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ac1a50e5-6c23-48c5-af05-849c401e15dd',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+IzA0MJ6de24M53vv5wtZEZfVRsOKPjCQwn8Qv7EyDpOH
l6x7dGikzFOrTogoFqZWznkzUf5amCe0qQBnyoBOmKn5yo8+wrgqjisvRVSKiYyS
ean0XNSBq3VgRQ2J6IfSENM8EYqBiAotHmqJ4zR/OcDegT33X983deisQn8c5lap
iH6GL1yWgcOWN9uDhxQFVqj1rnstnY6M4Y/XJokxEO4QjPfX951EWjn473Nv8zIp
uXl3jpRy9oCJYmDfFFbXQu1b70vYBC78ve1fU8tbAsYqGq9929m7Z8kqJPKCLixA
Wo/DjP8UrSGTNkODFHmdNwX/u5kA9l+iO2KFEcpslzZstXTCscxfe2FiKK/c48M7
BYcVx3ODxiK75M7mOkPd5GtnI0hyKZK4UU5gcbojsTAcukUdHgHVNeQSc7tKjdOM
PFr5WROgVpJsBIflqShWJmkad0UhsNhMUwvcLdfdrao12Qz3SyXMhnsfEyTHxbGG
8AUx+AaHcXPKT8dlcsJooUYukbJBmydwekx/INgXVDEOzBSjocjE8ZEHEorHE6rF
+Ku/G1+sASjLkDWfjp+euKl7gnWmPi+CIaKSxJWV9CzzhfSiFUg4QfVEiqryb0Rr
N7r/98UWVnR4lEC9lXuEpby6kIERD89awnAQkgS0ifVRZR67s2weftwV8v3yOsDS
QwGMMSkyHvEdX8p3+YoMle+02GV2Fofa6qS5Vpb6NCNVpHx7PuB5YgcWRtFAK4/S
51Jd1qnpFQmbsEXJ4fLUcaQQh7M=
=1EZn
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ad9926d8-1430-469b-ad5a-35673a8108cf',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAiN0JnasV03XUIxr/4S70cWJgE7rDYL9tzz9BDa40alcu
iNjuWh5eNPYRArxMYARu+Y7hShqpPf1IpOH8XWsfBeP+d6IQ4Cj9CY1iq78+LF1a
y4WRnwij+UpLi2C9fXlYJcBIapXt63WS/lzKa4F9M6e0k8i3cyLIunhsJzq72qcp
2ZXL0Opy7N0hgWYifn8FMo6uC7CPKK/tWsF2lXvsG1PcPiGEtjRYbb5U5TMkP8QH
H4mbwPBVoN0i7XNsgmSy8E6gTfCvyVpay2dDPIqlFIIpf58KJDARUox+KVnKlQo6
+o5ZRC3SFHQr3e+cPczV0bIfbbSJQzsFnfbRK0CQS5U+fj7Txkf3C0h9xopar/7T
ozzH7De17Ve378mcCY3Eil563OaYOxnx+v2Cv6SVsK+5G1yEa3lANCybFxUVP3Eq
fUSFJHo5lPaqWHxm6coyL+3gEmJdYmGBB8fVcv2t3eV6kjPaNrWXNvNh75U+8GKn
wkvpoVKdBn4l8qYl8pw1JSijo/DiRR5iSk84TgvXLgZhz04sTM11OtkiGushL6Hk
UCkHvDm51mtp/VKuXBqcLE2bdiFqaZE5fJnnoDNKySJoPJlY074k9txjyaJRNWg8
a+URoCDdxThev9cCMkcqFdDev3tULTVhhm3asTq93wBwLnrljJZZVxTHa4Ksbm7S
QwH7BW/pr2QaX7dATN0+NWFw4rua/Sr1mYGnzrROd0+QD81vfq5ZjJe3gr+1FkBf
qLqut8nDU/RsLeLcR6fF4NF3O4M=
=lk9+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af5b0ae4-96da-4b0f-a771-6edb04d0e50e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//U0qi5ZeFRz2/dZpQ8JJatzW2nlKH2hvY/eODccKV0m9R
6i5jBdWDfqbVw/My2dGg6XynYB2qHxdvKT1KjjSw4WdhI4AYZWiRF5L0I1t3wjo6
bWNT9VDK1K3MGWelZvNtve6+MOWFEhgT2inv4VgiJnnIMQiFdPYSK6pvONzb7rMv
HMOv5xfAhxvEgqwieTE9ubQ5TQ00ZUS/vZ94A8Wkj4sDy6w3jhVJGJTnV2krUMg3
2VXAOBZ/vjCQMjHuVG/0SBuPGUXWZJIkP3zsqWABsxpyzHivZazbLQd/q3IUpyEK
+m2Bkkp+llaeON3J5QFiQmH0meItuKdTY4MKaQ4ta/le7F0HXdIj3q72nTh2Bvge
k6RTVzJ8z3byo6s+lVF1A1zZYARW/ew+1VI+/e3gvnrr+UxKlOSJgio5J4X9icPd
cBzvejs9JMCozGVjZm9otKLGgOFBKhfI7QzRkEwdWdCvnyRpNpxkHEj2W+dSTRg1
xKamhoGUNhKxAqRXnHjsQUVrM9/XT9jnkTWhM4Mz1ON4ZyBZIs9ha3AA+XHp2pcV
i4juabLb9DKWHrauAp7OCJBzZheyRyqXT3VwKkS1+F09kqWk901Ym8Npv2XX3rf2
WMSijOhGxIhr4KFTvAAwKo1Ox4nMPfe/j7GZjTRxbj7/+g/N0u0S5MJJ/gxFiz7S
QQF23NCd071SilTkE34g3TpTSmtzrD/CF7//MNjcpaMt7KzMh/WWWEC1hYMD+B+E
gnDAeU15ztoSE7KE9ujwiydy
=MJLc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b139c26e-ded7-40a1-a1b6-09ada17dcf58',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+OPCZxeNwJFdFpqiDHbq7kKAjH+J2U6tynvlrQpWtyHhE
Ts6H58LcMIr/A1effKePxRczazsrALFBjsg5twFIpTqJTFW5hm5TeGfi0BQC+hqY
tKclsr0lTXKVIH0WrsRvMHYecVbKua0CNW1sK/U8ovRqZ9Vs+GsdOQ5qjuuVFRop
ibMw61cFeFs2gjjKoqz9SaFCN+329Rpv+61tjb+pVwX68hcvhRkslokT/uBLc2/X
06PqG4jZ1XRjFf5F7vlhpBA24SHhHco7o++ip3JKVX903VImShprNcDYrRelwfPc
ac7qOPuSwC5bk+ZqHGvexb3m40wwwSiLYPDyEKT2viahp+NJlRI7IZz4K+7SsC0K
skq7ykGjcjnXyp0TMmfDSWXelWFRpAwau3JfyF4xv9nxJyblJTp+mrD6LlcZkTiT
Lg8RMqi2vX1MCB1qoMUkL4wYOexALZ9H4v1QkupTPoqCCvhFI3gVDOoinIn81rDX
O06i5TwnOZn7TUvg4diaCMuZq+j6hfT00r2VtTi2ZC1FProbt4OwMNd/nv31hTXY
d4lrV//7LbSj+dCckbbaBviODz+xsJ3Aiyfs+XrJSRtz1IQ1mImQfxK827V1MqBb
fuKp1jDvB946W5wX9+Qx0rq6DfSk+Rx1rknx31XfTa5xlpuhNWZGANlKqpf58QHS
PgHqMpKjwYMSCx1/Db771Ni+ROCKZadoACTBRDWn4uH6Zjn8kGWyUWw9ck8BBGvh
hpLtXBpL6+jtw7tAMhcg
=hRFH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b176c3ea-96a3-408a-a76d-8fb4d7e6e562',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/7BblbdLnA30nmp2lc0rzyNGVadvdNKhu8xoIYHWSy64V1
L6R5+xF9zaZB3rS66mAtXTK5zWwv8BScZwDtoq8WrAQytuJRr/sjG9q8uZdVkfdh
k3r1jGbi3EiBsVNtixK43X+anbRqPNuracuWmd/imkcLX6+OO02EQa7JTFAWCINg
7VBslAy6u3XIr0d0rIRswckK+xQ3fPiUrNlmqK5b3KVSjZc8tAeDknSiSQsW1OEj
Dq1t97cEePDp9JXM1BRQktT/H7VX8uhV1En6gnwFytlf3USEq4yvayPCpXsXIcbv
VLlgmainwB6bemq65FuTutf08p8471npAJbweOD7Sn0o18VnPVcs9raSX8qXHH+W
970Y2MdU6IVkKkERak6eKko4N8hyWrT4IklEb3mhAXh++eEl4QH9LYXNnPSj1j5l
cZZD7bsL6OMy8GVTvlIP6SPavXwwXkm8Na196bsM3WtuI/4njN3+Q9gG3pchFB8y
zoeZa/8OvIR149ey8fVcVZDr4aoo/SoRaJ9Ng/P7Cs/Ag5e7CRgABl6l7jbOkVX2
O/8AHFQDSCBIgqfY/vAdMchgfnYsXDfn9r4Ouob69oJ2l6EmRQgkCJDim5J4XWeT
dPH34/QOo/bNlph+uWIusyT6RaPfwBCgPk9wvOToX2Dr2oyzhgzu2yrkk9+R+tHS
QQEOzxcLiUVbmIgTHaLYaw6sUApikn/I5ShxQysC4sp/EvmHsiqma45njLKL02uH
069xFfzGUyDdwsZC5w+esEZG
=kEUL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b29edd29-0be0-43e4-a75b-7708a5edea39',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//TAfz75sl4IXQwp+snvz3QCs5vKhFg5mUqPOwkMZcqht5
Tb+eiGypvrO6nl5XfH/8HLgnA6f3XIMwl2YTHnuMqScCbEENPgKsKYpkw5yz98pU
//2RQdXXiuhoD+33wTHgB/ff3fVMAeT8uhvIHCiUIm5/61sjwLjVLl7Wbv/JjGMf
zJu+AFb9K9VlnLHYxTnFGefL2qltMYLOVrxXcPqbTBNAlPqIm1hlsbMG0WDULSY6
Y30eAjj6IUaQAjpnrffWrGjSXLw8POx4ZECcdAUvPK+7/faAf7iq0rxdP8K5lrXc
wJFm9J8EI0a2nz6JNphE02ybLVxXCdgOt6nK8FB+LFSN2zlemcheRv014/qGpvwz
jexOOvhAp951wVT2bNVn2081j0+qoZRaRZns8y8h2F1KHT8FYhAlyXUGdRr5Pjej
JqPWhcBllJ8dbDn4l5Ko+F35VAIju1kPcUIO4DzXdpRg00akTDvuc5W5ZadhrbV3
FpheAHw61aFip//4BLWYmD8cIZ/2a50f57y87xVcTpUEGNt29HdAtk1x1Pj3SbLd
cSBjngdYUrhkDZptyvPcW2wxzkaDl9b5MsrSpWetBwWPXGfh+t16mXuaqo/pcxmx
gizDT+KdEgsYFVPSspMA86Eoq2TORuYV6ZaAFXtD/EQJINMjUirn3PKHj4d8KerS
QQFGn3p0LN8qH2J6aUrz5jKVr7aeYkMDvPwXm6uBY9ERU6Ik4H/qvemxq2VHsELr
tuGl537Qeb0o2ZINC3XjHOIH
=vvud
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b2b1b792-768c-4831-a74d-af47ff21ff03',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJARAAp6uOEMucYhLvVk7PmscYMAup5M1wEfIBxROYYEjhPDgn
prfC4NWO0AOnXyjNqbhXVyMq2KUfYBdnCRizUpBYDwsj8E0xR2aYOamAPNQSfIZR
w0ssK7WERkmtvby5CoBGZma/AFiOqi5w6+0Ppu/jZMdV+8oYCgXhaPLluP+q7E2+
+3mz+p+7UE1Ob9CiG674nbTnCm6aKWxYldQgmaqfGpt8eYm6G9KnnIsBxHq6ajy4
vKz77XKB869dKQX+T2LHBsI8jdekJ40kuKWU8NNbNogXceSvjs5w/ET7IfguDNKL
X9MjkQnHVvLvTrpmqolX0XkNLQ/Vjhbv9O/AD7S5rdlcs4kjeJ8kXAVWsYU+nfOr
Y9Oj2Ia+5jccqkoVwiIqWcI5UMekX3D+p5ruQsCEz2KXjwS1O5ZdVegDG0rmTcRG
AWF2SN7z03+oVfRWG9Bse1ERsI32TS/Cx8Bhjg45qFBrApwZCQey4/8i6FTebSlD
+OHyT1WbYQpCDVy8OL+aHuVciVTf2iysbU6fQ/WjhhTt49mPJqeGbJt9xRHdr2J9
65NM8j0EumbSdhm0Si2HtVkLWugJBImSHK79EEQjWZrr96zERDPFJYPR17uAeqqS
Z2bYn4tM92ypjboK/c0Us1W/EJCTVsy/tns64u4jlQCjU7pa0SfTxwgGCMo8K3nS
QwFpkKtpH2uW6oDEzIuNcgVHXcS2ycfi3ti/refbYbptSLvYAGruxvkY+vpLPvpu
cyWvRCUJ2pYSyuJVJ7Guk/TwBXQ=
=C+Ye
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b58901a9-9a8c-4fd8-a7da-0a931d192339',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//ZuAu9tVCyk90H/JX1YmuEFg7S+hGzcEzEbM8Xq8ksXPE
zbLNFl36bS2YE84l8UhA32iMpSdd/oVU4XHPbI6GIJlNROqi77lDKkj+3OtfBODS
i0GXmlhrawX90KW7EAGmoepZC4UsgeILGqnXuzObuTcHC/XXqq8jxOv4YhXP+LBS
mxa972D5ByJaWG7ajvGT6YC2K+TKtCVx5i/vWkVE7En8q6gWo95vzSxx4GiDeojH
IOUrCleBpLcN8dmoLZns0ZmieLI9D2nOXfLGva5PniFWq81mOaPnE5hbe5JnxgSf
tHwv1X4AYcaceWs86HJMLSs8gJSVffeCNJejAsoSCB7lIQA2GyNup2ON0vbbv5dF
DVCAepLdLzq0rmMdLPAB5q4/4GxVhOGJFxRq5QZnPiPWTS3wq9EiYTyG4mWdDSiL
I4ma1le+oxs9ozfYlPgwjsBX1CP0MS2czMAYDIyEp2e39dIyIAJUB7PlBLqYaGs/
V1jMO1c8d30Ar0aYbx2STsvk2y1ufy93GqJYnpvg2d1GotkSb+4kyNMra+kUzJw3
aENixy9zHORNd5sa0LxZUiFuaeNTc2U/+e626g87lIfEnnMiod70nYtnyQvc6N45
xVVhOosHM+F6kdAalEDMQwG6M1BBeITZdYpmzAkfgLNvBlpETUnRsZ7U3TRSto7S
QwF6S5M9bP/4iHwGQre1Z/Rcop6dkqearN4SRxKUCd8fx4APEgtgfQxRpi3uJqNn
BMHqvscxsbf3G7sJRdDDsDpFBao=
=A5Rn
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b5b52b9e-f6e9-4b5e-a6a5-ee59cb662bc5',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ//R9WdJI33IiyS9bHOLQ3yKPcdjMzbUIUTN0xl58rSHkXi
f8ZNxzNmY+3V4C/UW7HQUD3JEdxawzcmpf/J4ZdYr0xTiXtrVuaovwI7LT/57cfz
po3YQ1S1E1ASbd2/4q+MxjaRJWclnGeNN7BxWuG2wTjIA3uuNEnCCAHE+UiEvKGd
x4xb+ZYlZxgciBAKp+htVXuddQT5dJ/jVDPVpWgBSi0dsZt51w/Gj+u+MdT5PYN8
S3jRh/yax0NRJSdcTCFWl9p4MD+irgHraw5Y04X6+juEZYTiEUloY1Y30XZIdFzW
zj/uLPQSC7uFSZxadW7FwxJPEjVGhOJWOsSUwCbTEVX/cEeaTT9VMgxkPt5pbY+c
t3GwmGkR9gh8KEXTCWATQDDWOIPpVpp3+prNZ8GwrS1OJRlvi8Mn6MbI5Ecl95Su
O6nBGdkVX0Bt5Jr6tvR2qGFxCSr3O+R/Bm84IB6UTz1WeTRZBqB4W0pge/2Jj7n+
zrhbJo+sHam2RSChPJZAQiuREkmXd17lHFIoralJNSpOpLKi2suO3wWqVuoLzfGS
at9K0RMK0uB/5N7/qS0rR5og+YezM5YoNCJ1bfkHIF9M5/F5mdkaaFsAkQPV+xyy
06tt5WongZMR+BUBn7ClSs+U/Pc1uPGwE5UPuEi0V0Cs7yaiD0JdVWJaOEBKgFnS
QgEEerjE8JzlVhJae53ADxve8p3qotlu6tTNRvKDVK80hNCHqxnmw99sTvJ3M8Sk
VlScsoOyG64BnudKc0kz5qqiXA==
=sqkk
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ba86b431-3aa9-4574-a7fc-d7fdec355540',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//UXOVUsgYZ9np58Y2WhZjOZFzDd11mMhm3K80gtXtyXgI
j8WRCOtBo7b8fzsCkTO0AxvnbTSFvgHsUvYl4TxiF9c2GpqA7hFnmwwISpIKbbkm
YKQI5fF2pWflIkB4ZW+/7vCuUSQanPpNhJXTlQIh2cMMX3Reurqsi6x9+onhzj7G
AcdsJmVQchs1kxJAkEp9hRkhIOnriaXXho+C9cq+NvMWKOEW6YdgDP6JtH63sKXZ
4T38THlVscqRQSG42OaXadwFu3cM+KtSrJohP9nviSMRfOlmdXMLmPl2ObXXCS/i
mxhw9yX9XMfzYsQEJWVjQJ6QQ7aUHArpar5zw9MOXWO0JhatgnkorgfY/Yp5CtE1
nVwzhg6ymAxXvI4U98/WsZ1IDPtXfRlCnT0m0JogEzN8wVziKy5LiHR7BnxqZdWv
7ViuTakI9J+x7anQL8LBWTRnVKgTNhutN0E0IlZiA2RQvimLiD339XrsL/+NK3dM
NSPR3PF1LiiuhTDSTU4VIzY9BJk5C+XuknrAMgsJN0DnazMuFNFfoJrIths4EdxO
iqlnIX9uLslbazgcunfhzPewVU6qg21UsuC4NGGsitUxCuvTRmNZ+So/mRtSU/CF
zrf3qxtBkUYynLJs4yw0hMMZxDjbuU4S9KAOLez3jq6ouqPVw4QocGfNJTVLCA3S
RQGGvG3R9Q/GfHpFYa/jW6Kawe9B7MrFZJLWS2kpUaas84DbVRbP4BpmqOLdTJ1d
HIiluxOLzfBRO2AZn4saDGh5R0mw0A==
=X0La
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c09b0d24-dee0-4ab9-a703-15e17db66839',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//eGDDQ+ZUb7nTPxETdyzkReNX02p6cesJhFW9PJTmkIN3
gkUNtRGFaxlaNVhV7j8XxWPBYRwF+HdcHhaNJJxu8WeG+wWdEgH/CfBmHr71ei9B
iggeijgVhqiqKurPqFdQ168qLFmonqTwY6SwX6O9ESI+QW6xUSANwmLhSoD+8xFm
AvpbDc7Vu/CHbvoM4LSqQdH79a6tVmMYzQH5dV3fVnf5iHcgfnzEfvVtSJGbF02m
P1kP+pS5AEWNfITiQMht5hO7SWaH6sHsLQrsH0tG1B3a0JUbE8SHkx/IvYfdepOE
1G/x34t713Y1WeGlpZpJEavs0bHl1z4jYDco/TrTIksGWsOM5Og8TvxBksNLRPXy
or4xZSCPCJquvNju5v6KfKULNAdgQsb31qx8Vn1WXYW5ceqSDJsqEreM0ZcmBfSr
HtJnZ4EeTMSogm4fWqY3eThqRJYjoyMtMmiBbLhuzwiW8X0agk+CABQq7IL0paJ0
mJyHmdgq7F79H1mDDYcvib3sds+6qxO25EbqyGEHKmxwNveF3gxcr3siC8DvUT8T
CdjtcNo1M0bgESa1QnCJQAl6zsu0vjhQ6r728xMTHaJeCuSdqY1si9BWHBnCxUhw
YY56+dZEzU55tMKff7JLdfU1aVb5uByOJQM7vheNehCVIYPjF7BAKXogVJwz8fDS
PgF5+LTqv7gZpj9/pmVwxpkBMgFAnxyurslPr6cp35JgJTl64BzbdEahYTop61Bo
WTouU77iem9F7kld80VP
=lmQ7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c1a0a6ce-8a34-4e65-ac05-9c15c41e4b41',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAArhdoYHayyLSfjoi/Yf0B/kl+EEmkchmn1i/KiCOiDtnf
5QmNBpWgM+W8Cqcb7+nOAoj/cRDjkS9FWweqG8eIkyDOu4z3EJ6lrop7RAoDNnZJ
heLYcYOrAlpD9JAAkq2zQSkS41l07jdaieCT4ftmIBzzfl3FiXuDXX0AK86I2Mll
5YQuGwn1K5QmKbOVIlbNvbE45RZzO6NFvHOUA5S810ayFf3MTWvHRY7YcIrqgbWi
eLM4qG8GnGdXIvpB6YbYzs86gtMz3iNvoECXWc95D9O0ACv0idUYFw/9vEMBjvQ5
Jiwgsn8HXqbuLSjo4Wm0+sLQf/YbgFqg3ENI4feobzA3vvqX8Tpw5QsUi6Zh5xqq
0qoqzbwjXIdi0ToBmjAAGFbNt03vvnCNE+6YNs2Yp0RJoSuef1EyAKRnuZrnwt5S
YQAX6neU81hP2MkirAKuEA+SHLLGZTtoN6wnVU5X2ZT3yX9ubk4wHuFmPgrR+wm3
mU6WjLhYfXuyKX+Moo+aNWuKReD6+yualnb4Q/YLa3w7Kp/qip2V1c47pk7fqErD
TlUP1OwmfOwCvnJ6p7Tuwc4a8sO4SiZHpLvWudTCsxDn6ZXLTBRoLC91zTfByWsO
JttgbezFz4vZ9d1M9Gul+j5H9bpE4OMuqqYGF3YRO20qkUsNJo9lt/LVGhZvoBjS
RQE6GHG4cu3j2699Rw4p131183TPurRw5FV4yDj+RHMBPjCNVBqXa6sGJLXUmvdN
K1AEAcHz36pPkZVqwxjvl4Q+7chmNA==
=6Efo
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c26f6005-8f82-4507-aaaa-b183f80bf63c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+KT9u+Rdi8uFzw/D4mp3gbN58EVXYd3w5YXhu63IKTACB
yqicdnpjsfNZo3LHwLY44Cb1joSY5xcSAsBM2SAIDzF2UwcczoOKW+boaZsvbIiK
/Ip02BSGXzjnRIPF/ao7dUelYhB4A+5xRVeuHtn9j6QmMoAVV3pOJ9s8X1HypVRY
tabQDqvH141T/8pIkO9x5in7Ji94yvu7CV9Yo2YfuDT9wVBdOTT5Gecjr+kgKBu2
f+rBoq0BD851W/+xTaJxqHAOlLPT0tLS8dougQloOWCyG0ZGfHk+s4Kc6roHLK3Y
oG/fK0ppoAdEPHRDW/a2aI6I0ZU3MpkW0uF+eNHgcXlw6QFeLsjJIOwpPMq6Knkk
tc7uBon53Y4a4lEjvslAXOelZi3OF8TdK+YTJgoakH0sCcCkFXsYRddWUPFYjK1a
pKicfEkz5X6J62RxI4Glbzk/13PXyX/46/i6k1YLxF/QvJ0mAcjdBFpNXtTr+8qw
ggW6Ivl+ApL1GmeKyDgxBx67nUaODyR0vTgT9xMwbJc4rQNzI6YOR/K0X3gtV1Ct
JG9IFMhCgIwjqqXzv1agdtqkphucJMsne3vgYtNpZNmw9sx/PZru+NrzoTuavbJ+
R6W1Hv8I1Owv5FW7xi4iMQrUC1eRow37C1X8Pmavzz8SEfedVv30ZxUKG0uy7XHS
QAEBYLkiUxNp0Ow8sDXwSS0FurugUbSVhPdH6rQHh22yq5l62eyQh44JCj76HQSW
7hrzx0RQxXWHFqXfFjckO7A=
=gqHz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c3be4389-d285-41f3-af2a-d3add040c020',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAoGLHrvIKKs5QNoaq15EYCzi4jswBppZyhLEdpU5kA+gJ
sn9uHnaOrOSxKsXxqJyRoHbPqXxK8WG3ijNQOpDBrNihz4wtcRn2s0T1E+TI6xCN
1N64MgkIhpE+hlxAmdwK+sZMqxa/n7PzMGdiBgj4x06aMQXBMDUYfdNvgjVmYVON
YXt6VjGzNYb0FwUaeEmYaWLbI/JognMfZ/JzjHrK/GMvMrPnhaePp6tiEtikyZH0
sRTAgZOJsAhc0l9Fy1nZteyBxmjpYx0ZJ9fgNhXhHLPq9vbg2aN5fXE1LWgzkMbQ
TWc5InoHmto8aEfwZL0I4c3CyaRphMQ+AZcgJ3rvSgFPmxvwXkLaFwzxqk40M8Rz
Ww0V3m9koB1m81n8fvLUWyBZ7ZZ7EHZfDL0dT4yk7ZXwanokmkqhrXw593LBYY5u
mvN22kC9sNWpvKLrvf3dfzJY42D0VJkIaMjWlFxcO4UBXOcdxpvTbXr9NtYPdCLU
fWcN43UxAQ8acPA/DEmnLo90E8eG0LVleOkItZqierNZeL6TEh+Y0NFbLu/SWO0m
ruLLJ7hihKbnE7hknovGUHg8oIUBiFaCUHIOLqDh3dFgvDgxtaZa57sxLy8TZ50E
QTl/R9/0onOtnZhiJ4xSEWQyr7+tacn7yHp2Ks9QSFgYTDwWyNzNKEuiQUykDGbS
QwGkos79Zsryh9GnIXiF1w9/w9PkqA1naohxVvxEZPeK9vMI8SnfHaZB/R+ATrkg
lVxcKE5tI4vzaa0MketT4tDx6Lg=
=lBMY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c4191a94-e586-4c5a-af5a-5bc69545177a',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//a3aita7AgjUR+RsIQOdsNNkC88z7J2eWpVjrtz6dyWPT
ieB56qLfxNorWehub9qjRc6tpYY052sNEp49YUFjFDEG58dt+iOgk3l0gsYBiNu6
pTKizLpPS1/ozzFVbMRcRKu29vOLjsHWi6fbxhYo8AHqaDCScelkpRdYo2VeohqY
Ea/Rzpw0P6lGozn3jBlNwzLoLumg6P8UpxvjQ9tGfUXwaAIzQaVpaqf0M3yjYvGR
Wcy627QIGm5RX+oj+lAsQnwAHZuZJVDaLGjenxmezrXb/loud21hFro0JTjcgi+V
57TN5KZbt5MPjrj9NhlMGYSYmUJlUiIE37LrV6AcJqiEx8rLUxUzjU0xLe3qEDKi
J9tNIj59CHwimPbO9tj+3+b7Q5yjDM9/g6fmWwAP9PjO3UYwdANsgRpukHuspt8K
Qni8SxRoBVU6NsA77HfHpXInFOfCjRQ7yKiiZDpiA/YV2+Y8jwF6+L5n/olEE2Pi
3oS5f1cHuDlNZuabJ951/++gUOq6h1anSmpf+RNaWYbBUYxSlkeJUA7Vu0BY/iyH
79p0+bfhbmnITRCKIpCmTu92WqltG7PMH/Fci+PhQO6vEMRUM0DE5KZzkXlFrpQO
xWYwy6n5qizmz3A7arz0MtaI0yLU38k45PlZ25qk/5/ScwVtv3olxWYCgcoFwx/S
RAGqNHAdBWne05HQvSZx92uoWliY7hyuBKUmpOfyEKQR8aGLG7h3xBYd9nK01uD1
gZxP+HbLdsDGtl9zNlW0lJaAi9gi
=0uzz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c5a22526-aee2-4494-a0cc-71b9f68b5fa5',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+P791XS+kjAjXqkeqPfkCXNWEk9Q0bMavUhiqsVpXUXyA
gpexiu7U0Rw/KdfcCRMYX4VlkqldRlKTqRsXZ/y95az8YH1D7p1T2O1C9R+eH6ww
nKFRkBM4CIxVbZ2P6IoLyLVvwY73TfffYw1GbWqytM5zaWcCHlPMv76lT12c88/u
1ePHAqq8EWOlg+7Zrnlo97LuigDyUGAQyLmrvTRLw1GNqcEHjqvpSRh05UBmgFCc
hC+hE+UqHkZ4yzBKgcJKUU58lMqMYUvPLl0emfh/fsKqVquYSdCGz7SxE782wWGL
zn6lOPwy5y42uyO5zcbkdw0TSP5OWA0H5GMgOVL3yczS4m9LzqL5NQnc2/xcloZs
0UTWus5qCyk6RzGNvwwen5PxNBT2lng1ycUYS8E1evUJiK+8cMBCcjn/opkCnfMM
7ZfUGLlUt7W5sGDRSNuPiACsmiG/AdpraD4fP4Jqy554uwkZ6m6VvEtM9QtRnIvp
+BEn8MpBzR5oAEVcr322UtQ22PrIX4Wv59y9vETDtuKx/kelE8sM/DzWFoI5X/FQ
fDBBhkLGIbMELBcLuTFBbx2ixWQPZLrataYTpr6lIrdEEa3bKqAE0xnFsBlbXCA6
ASF94Q7r/t75WYc1SDnl47HsG+SDIW20bZmAayMcaS4JNNmyjVSQZGgaYOJCJ4DS
PgE25uQi4Yg9jpcI5Ei5XoKsI0RJ1gZtbxFXeXCl62+hlTa/RwpKvm3PzF9peYpq
zjNy6JOJhFZejVDy094+
=EJmF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c69c3ed0-2f2c-4bb9-aa82-dc8aa5e8fcbe',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAArYu06jJmWAVXvdwhAClOW/UsS/6znzOnzbN0N6zLuYLd
cvW4iqR8adMJCjaODibgAqG8mr9dOzrMyigcjBr4qoSG7/7by/zm/CbIRjI+HzXe
+vf3p+u9EcpgOGRJ83zMOc076SxHtH/IfXlY1zEbuPXJNrZ9TpBDs8DoFyo/JHo5
QfR8JJVR8m0gXJcNcj+HZCjomOlbrmYJb3hl5c42nmcc1LdbAuCponhF3ii9V76V
YuGdN9sPIqQQ2QGaY7sOxPz9YmW7ipYmCkxTremTeQWFLulROIdzFXKJhT8jtd3B
NX92vhQd+EYH6g3zAd4Ikv42bmf0TxkmTJmaASQxzYlg4Gsg7AIZmtoRTg4Xe7TS
EYYrqMRAlU0MaC6I55dUbEsO8Q+IGcynD8lyw5kjNNhNXXicgBiuTmB0r0pNkdjh
JZHTdP3iLCBv/w56ha3Fi2jfvEeI4A5eVP7/Ff2CyxtHtZzvsYHCsf0sWbwoWRtT
JGGvbJd+GYYZ1SWo2tcDp21LhBGvN2uYi8CqJwqfSwBKsJDmg6xg4WpsD3CLZKqG
oqvbDlWs/i0pwQAo24QbclpN7nJeEeCA1klBB1DMx6PsU3wDV6LofRtmkTAToPKF
9PE/Y8s2CZ4p2P9A1I79DqZw5il/auxB1p+C0mI/ogUbj3ywoazHeNnNLVVpAafS
QwH6lepBt7c6IX+pfXCrivi7TXPXcwGpamji9mg+ITq2usCBi7c/MxbMCd+LB+CJ
AlIXFa0OBmozDroSmj14sUqaFF8=
=kTXs
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c73a8438-53da-4d5f-af4f-521df8e21e3c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//bSaw8dL9zYGXkIx/7iQv/W+VnqKU1f5pm3bkWPDlX5Ic
ywP7JyZfv74ymLa2Miu+mDJ1QJkYXyUiJk0FTP5/0znW3vDIS4wA8bitfZrMBxlJ
Pit+511SkPD8T+oHBLsgPBRXME9MZ0g8BLIGyUIKnmf0tFIg7stkeeaQby8bwaqj
u+8/VcPGyIfOhY83pqPUBs2TEA6J606/D8Xcx7QXm15CJUnQo/Gh6AEB2mGqqsuQ
2SIUaFANNC50CGCEedFH6L+e1fo4q/hYdmdm4DOapOle9AFl8SDs8KYM/2dKhSUY
gsrwsPRc5HMXC9SeW/zsXc3zZI3U/dwiJzaW+ifmehkChsk15l6rRLiEXCrd20Gc
jyKzxKGS3V4yXbqQRPRtXuEYmNezenMTeA+O5SPVXLJ1mPxE4Y/seHEf4S/i6C8d
FZtb1yu15Pn8ZcZPbUQJJy82k4PdPC4r/y9OtFBL6lA5kEqblkwqV4e0GM8p3vNP
ACQf1yYdZrC8CSVy0x8dbgY6BZcygt4r2BtKehx7UnhBsMhI03GbNUjDG1SQ2prG
SM9cZLdBbsGqGAiGubtUfxeyyimDBqWXzI3EPVdaOKYGfiLw/4td54FNMj0hllWW
gJlPovoaM4aRCN9i5sys2BZKfP8W8/w51ktua0q1xOFso6yqsFA2BnlUGLD9fjDS
QAHhU6WN62yE0Db50Li6U2LPU3ODXoLh3TU2v70Ob7QyUHh+mF+xileOhOFWcNpb
O/9epjFP+t/Nm54JpQJtLhc=
=uQjY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c7917d03-0299-4f94-a157-29dd3bf5a697',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//ZS/Yjkneo82lT+VQ6yp66pysXCf115zKq+IVtjqSc8dK
ztQiiqbFiLAvYy1kLvkpXKfxc3nyLEuq4Isuqd0RVb2C2z6NTCLgWkxfpmgkxq23
HxlXL0fSU43AJHjYH3XYVZXjMyZJE5aftWkRsvRgih86aQcjab8O7cXgLnynkx0W
8aFDz9t7OSOlJIq5xUgPpma3f5IBx4VIRFQB0z/0NNXODCiHF3yGpQKHaarc7JoT
fSREok0qbA131W7BLofw6qjVqiwYFkkpWySgeTxcQU8uvWmpAy0yDvn4+sRKkpxB
CVXQnL6X5SILHsQVS2zqwgspmnTA4c6uyaT4lNu+B/S9Sp0N9nsxf2Yuu7KTCBcA
sATWJch2aHfjoJDhEwCSXOAMDV0/1ZZDt3DeLQzPlQqFegfNQowHZnUgukzNA4xy
g7BaaCk7L7d0aqYoYRdwWs8SDtrlW5APnRHDhsgSgRpbazIeGM8yBDWSsxnkOSzu
+fTEq45Y+SMYAYSAg8ddQAcikIvwXIxuNb53Wzt8PNNImNB2yMhoTFbILh5ze2IC
R2Etar5RYJNPXsC/L+SVb8PPP2KBWa9AMDipvqL9YyDP9Fvgsa7TK9xOhOYsTNG+
Zgz1L8TzFT07HA/iXRPMFKx7wnG4FqWU1uH9Lo/ifOUFWdtDdfHfV0ge94/Fwg/S
QQHXpKefOKNZNVcNkKL1oTtm6/lNBgeiktov1Oau3LC1Eu626eyfP1stLqtDX0JX
fqM9Dj1TJorOiBGN7Hgjr+DD
=pBnt
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c9d16281-8c75-4745-a186-b84612a7018e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RTf60Mocc4qNGgDh7w2zOQvihfFUgkcGJNwdpGWYII2F
D7ZiAs8gZ3+t5DZ3nHoUBLvaLqdw5MPP4mXNUR7nPnXalQiT8igyxHT77hyRgS+h
KhQUyggAN6VMNOiyWK24Hjrq/E//hJDyFikROamGfywJak9aL+pENR9QjypnSNXa
5jyMCQwCMAkoWGtd4YB+K3uXJATVCVMwjBjbY7cjzDiRme0HxT54wQvnzrB8VVEv
UwarJNG66GvtiVwQ3kqVENP4Z8GCTHvWAXJ4JW1AoKSccIuc35UQrykk8crrKUIA
AjJyTvFdlc2lMkr7vfUr5pW6jkUI6GNjNnDTq3VAfbXYtcx6z5w1ovjwITh5Rm1C
/7FRK+cfT2IFvjoRmbPzpjiXLRLNNOKiZvO9nB3ehKHgyNShC7KpSDAgtNOLhB8g
3SZU+7AcbKhQ1gF0n4EZQiW5V43bOwYO+71e1BSv/Z14yiqK/i2vcN8zOdEKCNdl
EduFTK4BN3BTUKpoWMOdfEBmW337RvZPLKAN6G82bPAF6MvjLVIlpZq7bExs2x9Z
R9G5uTyD3zyEeQiehLZpg9CbPG63Y/iMMzGN1eLimXgeWaa7MShmBgcsSjzjS0rJ
FxTBUKIs7IO3NzHwAGma5DLMt7+o2G4SaFTEB6MISLNIW3vPlJwsyNOjw3aZV1TS
QQF/gERi/wv4X08KSadan8TRLmUXU++y/0Iy2p3YLWzjyG9DyBUWdOcorq8kuNqs
LneYmeu1y6KaPT2tFNuw2OWm
=QBeY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb6d589b-87c2-4b0b-a445-69fac732545b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//SevJVC68UCNboTxWfWiK9G56b58DzsKc9mUe7Q/BkIRL
SwjQXpllEghO2xyvLcIzMYlfyJ78XeU9yCl9Qh7xQFQ32a0Ij+HFsvSAxCrt8wHB
qb4TuuoGJM3AxZX6UKT6zjPxw86lcqfWZp8CPLW+NK0eueC8+8PF5kL3AMaXcKd4
Qb+GDejK+f25l21Hee0JqwXv0cv5kcX6KLpDZi6q7N1ti3kFRO1FRC3Na8FhAEYb
QwSwmm0hQDjaxA8mFnLqjemx2IxhtVX+DOeut3drE5vlHms6JdSDGI0mO/VO/H/1
VHC1ElgXAh6ru+TqjMcVAOSEwNsZBRGP4LxixJteoCHKzlS/5qDO2XGk0GNDW27Q
nTk+gduWK3uquCvFExrW88mg9RRdovyr99+R8FDEg7z/egM5vUS/KUMptO72a2Jt
6pqRcviJESNaSm0K8rKzJOSozQVOO/2MuctACfrR8bMOAZM0r2q9B0cPUGeG1dGZ
XDYs2IkoygSyKlcoWsREwFaQYGc+euBV0Qd1e6vUn/dUfCvZaXL59cHxPJAD4aFL
FgjY+x8OE8i26Bciw95x/eyyRfit9RPA+cMUME07tmFmhtnopsdJnjSDC6S7Tp4P
Ctzs9M7z998wHAK3jDZNLt5oDZtia1HTumQwvvnDw+IHx/JyYhnzLNVXd2Mi6sjS
UgEX4N27TZ9yPvpzD+YQa5e1tsux1s2nssg16qnXOezx0vFMCCjXB+R0KhKA/q80
9Erwpb5IkgjLzAWy1ZD8wHxQ4Qmv1AKnUxhTC441zFQpefk=
=5rMj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd2a5985f-2603-4df3-a0b2-75b2bc05d3d9',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8CVkuILPLJWrmzSQNxtIw82UH2QdAc/qcByIfjfNf+dJR
+8jvnB3nJOqOGwTKGMhCZU8bbEmTKA4hOpMfUF+MC6/qfkEdrxi5TCkHfnyPqr8I
YHS5pXjW1Sa3y97JLwMPUJ8Gm9+t74uxaTqiqQKFU3/bpDoDz/bbcWEAG4GnZkXB
UWi1np5LnbrF2ygeUoBzS9pl533QUcL2GzCEkS+X4gn9EXf1zsr3w/giw2nuT4LG
YClcxtxLGZIxgVemeVxbUUOss8bilp9xo28c9Ml201P1jZwPheNUlm02iAP9nJAn
KnxoRFTtM+6N6LebQv9ECzskHOiXqtwY+tzXDolM6IJOoX+C7pfwgjCbdcO3NcdF
UQnroP39PmgyP14ipgNsCTMIbcexVJ6TYWJW/6cggJ3G0wgnIx8aUxaW6DfGkUvt
rjvbtnzoofUPnNBifma00Y3HKm3qcNxkAYGBTFdCM6fO9djuuxUdsqLB7TDUAf6W
8Og8FrXaYSyHfgPp8ayUTTpA5NuRRtKRUVYXvHnVqNZerb2mnIh2XwfZsk6Wc98q
VPCV8eZt62CSGrS0rTCBqCAC9GH2p2MnsRVaTnq1x/7qfLV90x7xaf4jsq/gmfes
yMNmwbeQbQ2WVpmk/UGZzj9vCMuoPLBl2b99WKeL+VovNIBJnpBJc4orV7AGU+DS
QAEDhGY4u9cQNzvZ0PY5cSsBBo46skD4fa4qnFwxs35U3dwXZD4dBaogbeW8Cz+Q
R3aguDkVeHXWRbk7SuwgC8o=
=mZut
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd2e0314d-92c3-4e11-a732-7f021bed1dae',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//Wx2hU7x4fLC6oiGJ24bAPk6WjL3tWKdt9IHMxY6z4GWP
7bhOy5tXFX8p97EzDUbvD6VRqfZmPHyHIWDbT69/EdX0siawADeBPzd7YtndDeTm
Tho60dx1fNfXjYLLPuKIC/TRYePHLlWGsDnMvmzshUINZ4Lutj9MJSGYcxmI2r0Y
01eK49aB4esfjMgB7P+0F1yok/Slj4YQdAtqMLyGLo3xZF0OfCiZzsOQtLJNtngG
g51w9rHJG33Uy4h7H/8vLn+kp1EnogT2V2fmrRkW+Nvfg2D3mryOuXYutecek0rM
5K45TcwlGmJrmlCu0aNuR+x/IT2dNbtDzoxkWImL+sme7nUtiJ7XI1gThQ4RWxeV
QsufUSECCM6VaejwLOqUfi3CWdhjEz7jWQjqUezbhPgnLxD0jED0WFvTNyQSRWA0
ZQuxvsWsL69e9spntACt0x7SS4VJcH6ALxpR2A9rRGIAU8enZSbSacr6ByBP5lzB
qhp25K1UtfRKtdetxceTUau1Tg6y9EK3IqxwV9EejS0DU8CQwNu2h/QHHHqVM7Pj
o3/t8CmbM0uIeObRTchSJrKUvzf0ih+/CB1t5swz/IIx229qCusI/o+yFLS1fJPm
M7/szod7eczGiR2ZE6/uyJEsH8xK8J2XoLLTFhrfLQk+/FF6U7BambnJ1/lv1rXS
QQFRzA/G0HN5vdXm03F89snYl3GA4NHwG4WBuZd1tlGzeSacIpmOt9+jJSbpPFLX
VN9tp7Li1KtBltGTuMilEvoD
=0WrP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd618cda4-5131-47d5-a554-50e9cf10a61c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/6AkFoJxsH1gp/QxXimK0HtlQiwQqkLxsVDsDxIuFoFXKU
k8tn7zY5P4au8YOa4jWvlk+sm/PtqfvYURld7Pba0l3R35ssSNXvIELgZjbX05ES
Zj6nPmykQvm5+bJGMTn+Han2VmKLo6XrLR8vzp/1qPn5W3y8OHurqFSsbLWqwWaT
ayP6CG0bT2mmtJjyXe6rWda/qr6VqqcQkZpZdci+w5uGMdSz1PxF24PiWY25HnTu
QBpLFS6ypdjx4pDbVCpdFMhLQ1u7TjSOb6nOb/YWJ6RzkYGjigpGMWElf/uNHrNi
u6mvYCJ06H3Am7KM6msGs+Y/y77jW3WIWxiNd78T3a2VpG8t6ue69ACAHaQpxiKr
X8B3oM/eMX3QN+IpFi0qY+l33xUF0569pInKEyy2GY5HahBqWyeGynSHM8xK2dOm
sJC27K6mzlMP1s2+LjPyg/8n/zqdywqT9mfZ7FqV2j8IMDtt7XLpYgJEbWaYfbYX
3X4Q8IPWdSHw02Ksh4+bCS3F89tcZ44R+eZ6Q5EEyAhvmdEzQZ9fcku7ocNmnNnJ
xSZmaC59NxufjS3Ck4Z7bpIO8+FueXfKt9NMVr1d6lhP1QqvzPk98nUUAa9iKAD4
YVj7DgDYffedeZNeUtHPcUUOUUuOIJ002OF5Gb8RS3gwYBXmxtD6SWU5MqhxM8fS
RQGcrNXyywVa3WeffoLhawlwuhi9nrl2DQxSqz74udQqlTQBABrCT/631W3WdsRH
Ijovm+fYweGRawSnw2bzDAp05Gl7zQ==
=7PUy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd82d5318-3ff0-4ffb-a0fe-1cfa05c1dcd9',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAvwj0YTTP+mHJlFXOCfKEqiRzR/mJALnYYuXezKyjRGBg
XGUgJ233X/dlX9qKLUmBLa0M3JvFowaCUo3J64yMOBfxo5c0+eRUDI8WaZijQjbI
43IBJubiqZ/uEg/kCTCFKQisIBW69sqyuhrhMHI0Eon7x3ZlzeRIDoAKD+993/1o
tiOi9DdVDxiYiLuQYSwCI5j1O7JXELk+i1oCT59lLDl8Uf/Zk9Wrkh4HYK2gAhv+
sT38uhNwnTBh0XIO9BQ7NEPoH0ItaPNUEQc26Tx/+8y4Lz1VqRpD1GxE6l56iu3/
GAjCrOnQbETRAyrXb/dSYgfkL7hh0xeUK/8N9HDf7G9v0CsKl+HUfo7MdVBGX4s+
E8KgB2iqmTJsMYqPmqxclwKiXnJ+a0DkDB8AeeFhqi5IdAwC7YhzvTo29Ib/7XZC
KFMEErbvGesm7lsOdFdhq1EBU/J8G+ysoSDjLTNytNrwEb0Xk/g4O+6WyAT7ip38
Le9jKZjLOeoxIQaKGYxrPKWgmGBqgenKQW30Mk4BsZCLJhIX/NHuvSJxRn63gHR7
8rYtpPcGHIrnndeln8gOLItB4BkjaJNRGcciTkbM7nEsPtfSfMR6Z9UyRMdcbj4y
6uXJYXpn0vU64zQWPV7XMK/hXQUPbtkjmdSBJSTIu+XcZgxjKbQsika5ZlIHe57S
QwE0oAkKx+eK0BjmpcvnlFl4EJQQ00dPGy7HPlv3O3VHxkMDxl5wHWH4te/5zAQw
7uG1Tzk+690NZ2daKf7OkPkm5jQ=
=dlcZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e22f1a41-7395-4f31-a8b9-0f37da8f0c55',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+OhDhUiMCWRIANxbHP/vJ4jzlmVyF+ZtWxV7T7tn+ZTj0
VlmKxx4400C3a2nTB8w37zsIYMIL8XfCs1+bC34mS2UTxqFv9XP5W0y+er9i5PEF
laZH0CN2EcXfdfEMHLWTzPC7ys4xg4yMCHR/Af7wid96MQgDIx7G12a396fpTcxg
h30CM9kQciWHrp9zdsvcYPRBabmUHtaPoeWIEN/sY//tRnNeZ1eaC78RxAwpuDj1
QvnHdNe7tkX5LkhmddUmWxcaVux6ZMDjc5JqWMLaN49s2L5mdXFp3p5nZ4TwgwLo
6eG3PGycxXDCxhI+1gFhmFHL3+cs0s0aZp8mJ960RmZ0bm2vimRh7bo/23PmW4RA
Lu1uQBOFGUA7fLOieh5iVFB6j7ancbiI+SPFonFBL3sPsZJbg4ZKYNbIO1okElTj
t2TT2pimXDOz/Xb6o9apB1NOdpsYJLwX1yZkwA8fljfyYXOR5jRTXvX/7veKlpo4
Py6SdzgldV06JSjduKv0NU0PtAYLPAOxmXmy7JrQkKlEoH+maqyQAJ52tBU7M5uf
/GmpiKAHhAlDZZOQzWu+rq1/czJuCQl6tyV/SISPyycVZeroWO+96Gx0zeD6vMOk
1+8Q3siQjWLRXrwAkLm0n/iMDSyTSKHNBJb7CwvpktYsUPXN8WQddpzT8dKhCp/S
QQEnYJf2fL9iXFtk1WYpDXDsH2CA9dpELsCvUW/wQRE9a+QGGz7v1ixRL4G5Wb9n
b/9d8AoKT6FAPEmz+MMaT3QL
=f+ox
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e416ec73-c838-4be7-a875-767fe8c8ae8a',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+MLUVLWSaxB3V+mpLD41TN13E0FP/FWXSBMSzKDp0Nt2X
0dInOcgs+5CpjmRp6nwR3HRZsOPYrLjYa+gCb+L59zxsWuPSpx0ATRkfOetfVmtb
lFyMXNbxVcHvk6RfQCsr82orfKVmltlfO828HYnkZ0Ahfxgw8FuuABYqr+T2n3LR
W8d5A9qSPuGolRdVazsm/kMBkoz3v2FJX9YODLF4+nu+YujBe96hrLgQLa3M6vO7
3q0VciIWqaAU7eEfW20pQEWgJPYprSXaM0YiDKumNm2hBd6htQXTU6BgXp+qadfP
e4zvfkl0KI+fLPu3EsChy+P0h4UJi+GJxZQdFWp+GxoffVT3mhI6LyH5crUjdSvz
OqBB0/jZJhEBGgTPWb2g3I8OI+yia1osk/nRii1vF6/BpjqLJt+Uv1bP7uYKTVyU
51cp23QHeBUVR6317mZ5INytla9AuQO0ai+T6qH5gwcVgpJApOvc3Hfk8eJTrRWb
XsdSLfCqzM+pnfcpY7aKIhp4KXO2nyHYtfx72pkJ+6lUnLWhPqp5oi1BmtoNBPI1
VWYt49QeU3lls3V6RJiMeS+UvKQ8c0/Z5v4rQic0rwEdfWPvhK2XB6etahfe8Ssk
dTkKf1cH5jNdvG9xSqDOJvUJmDI1ua7lzTSzY5bpgyyhicRhm0H9i4H73jkom4nS
QgEGxUGQDzNWXki4f6wgkKSkIvJ0DZDfhxRmQRWbZsuq2rSidH2+HOX4LMEFHSfr
nPatLiieU/CE7IQLlTEv2SsfqQ==
=VatX
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e429c8cb-dff9-4cb2-a3dd-3ffb321c9d8a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+PX/QSKoaYFym0P130QXXzJ35YVymu4FmxRRZFF+lfmB2
rOtpkQa9ZKvWD0Q5cnnE7GufgwIPTAX3qjpBtg7sZSZN8AbmrmM2lDgzEYuJ45kH
fEoCko2UwQse5v9rVmoGHFV8nTkCP6qziVdeExPqHSMjnUBaDdbheoUGo9E4TEa5
5v8hSM29T/57I3YHH08EEif2VKMqmFKMlRUvOPei3rZN4QHRT0c3oDch1MI0Dad4
C3RomnaS3+HfwGxZw9MlF3SuUdR1m6nzIp9QjKg5WGlcZGVUfcPyVJ+wPCn0FYda
0lcn2VBXhKX1M+R1U5CDSGDDw1TnNP5eNMZry+PHFFKbeFhGDCE717ShGLE7k/3h
RoB1/fkg1Ak3GUO0sKg1CZmETgUGKO0+VBeG6Ew8Byuo0RqJEkERFnZ7LQZuKNEH
2lWe2qJjnmZiq9sca/kgMkZwhTnu+GpSqoSntpj3oLnE0cgcTMlqXe500LdcEGzG
TqrLoSp07BdcaDEg/4h2t34qAYblCJKs2W4CM/8RXovGjMfYO8bVcRJRqyA1WhG/
vn+wK3ibrQLCHgU7A3Vdpk6kmMhwU+8q2m1nNypHn4/54ycBHXjIz9wEKm4ekIMo
jwikDlsyOWGMky0XkFsEPSm7mqeZ1H87U4JnR/U/xAtrtdTzuYT+b63YZuxdKtLS
QAHg4F8/OYg4JANMe637HMYPYXfjgQjPoqmUvtjhCCWQkiXIqgUEgbnDkpfYo7OX
vRqdDNPjeGR1K+7srHhOTHw=
=7HJb
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e45ab94a-df9d-45d3-afc7-b909361cab1e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAocbamcUIWSdiGgZsbwpZd2UbxteZbiRwXCrayJ2tzaQ+
yBz0qBx/PVJEoc/Q6PGiS0ge9BC54JK06qeVhRCRTxzhRtdfIeqIJ1ys2wlvwqqw
2a2NxedMrsjWncqX5ZGXL7NJ50JYXV6W8FUs4sSOW5IqgOijwWCnb4DyAVhic5uv
7RqSwjl+1ODVm/6iTezb0aL5eVbzssSlkyZmcf9Ka9jsI7eIZjcKJXFPGArsXGd+
knieZjQkYJkNlOBFruMik+LLR/EJQx3vquSf4fZ6Cc+JjeQ4xMGBQxW8mRUMOlLI
03dHmwZPp+pcgP2ki9RJnbza2N5cvnA5AxSSSt4FrRZb5RyFYMq6tl4MdmxUf1Hk
6ugG4U5wNIe0NQ9Llzy82G0cO01mo+W0+EejSCkWI3wieCYU8UdDf8KxuFeSlsvu
vOZ774A49i0ZSCBAPE/ngxiiPHe/jTP4vg5l1HENZPimET8816dWuch5slndzC1b
i+bW6gdh9vQhy3/2w/icqhiW/F5bEdXqwM4mnojYATvueKyYVe1ackGSa/PGTNyx
z405C069Q52VkLOkUIgEy7wqQw50wLcTulfN+Ro8DRg0YHZKJYf1+FcEfteKv2dD
JR3k3VcYo5vaT/EDQE3Pg578JCg8yb9huD3xB0QwXFjiBsJTzoxFa+ECu6zmhQzS
QAEYLPRy1j89+xIYRFnme4VOkw3Y4AYKCdYs1u0tpyQaoZ9gBRPp0dG4K1l0RH1E
oMLxu7dkikagcdmAuUwtHzg=
=y2L0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e5c16321-b4a9-4e91-a8cc-a52f351fed19',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAsZrNeaR5Ijlf6bqfw1kHDiRZMqzgdBWPpybqtQm4kq/U
HWMJTYN5jXgZYWK2XvnLnwOdh95/gu9nruhoNTS3QwAjQbNAjnDE7M1hu6GiZSNW
NPlZOkVWjVLgEJUQMAhmGG5tw1H9WMY2Z1qg5IUQVZbnYePZVSQAiNTBkOQal7fD
ORs7IJIafViw1++zTf5RkhC+zCTdZW2iBANJUn5dmXdQLjuGcuGs7WAvZ2X1mAvI
oOIzKB5axk6XAucHWGFQo2CnwFTCVDiQdKe8v4ffkwBwtkChnS+MM3hkJV7bLBTa
cTNtCpoc96oLE8NvLiFUxURSpitNUEOhaEvuWWZYZZJGLgOqIgDJP41ZTR3mLsau
YdDyjVIpZKBVpajNOySXmIH7XnCmoCmj/wJhJc7iO5vL0Nlda5ttO9mGeYuoANbq
243JTUNcuLuTysYBaCCSxDg1EFUq7UKQj7J5AJHZB1JQPBtrp57LvbJomJiLxA7b
DftIVNtPVpZ0j32CVEoTW+eWk4cuZjzVlNX4XL9eJMoTebAvkdM2G1YF9As7cn9D
+iI8MKXQ2s+1Rrb7SS3IqNzwa2B7uaCHN/wFUQUhiwkqJZdAab9Tlejc7f53e6Wv
b1hHFXrIV5HE/d3qt7pOY5rQ9V4AcDiep/jXAtwXA1fRA5H/2gP61F1JqJO8/IPS
QQFVX28lg9gpjJshSF0pzHirROB3lycSqpLLrWZN3pIWGPml8bXosgdKzOl5gvoE
GVmkR1kXxaQDs4XmPYBHThKE
=6IDM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e610d7ca-bba1-4008-af8d-76258a2ab2bc',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA3w210nUCuEJAQ/9FJxA0krmx8MjXSHIN3XDRF0aqeejcKN8H6JCyPrIsC6w
/uz7NBpwIRsvHgG0n+JNP6DGQdyAgmajPK9mmp9MwEC9N562jx22WeB4xFPwiNfm
S8hUpy0LdR25FpqyqEdmi42CsZJShEr91WfK/cUSK3+4zjbSlTe1XmOCDIQKsyNk
RFkgwpba/mVuyei3d/ilFoD5MKU326QWx/buGtzGPKcXRhn6wj85wZbJKJNHFk/8
JB/5eaJW3G3+SZSYR1pLBYzshZ2NSWfZ+oidQeyHinEvj4H+zZ2/RGvwEwesSZl1
r9PAZcau5ZBZiOdtywgUn0hQ2YsZziuTzqTf8SC1fqLLZZYhRCf4M1P10MvXH14G
GEXYRDiYimLGlXll/r6aaAxvnYp80oBpATpnpSJAjy4T/POsJIE9W/Q0nqJFTVVl
VxznuVZ+zEOoPILOS8hVSLFU8plylKmSWe2cKOHHsp/FsUwaq9j6kjrZKtGNp4Kt
7sXzOsTLtq5qQZFk+YLHAZFVCcBghB6AM3kdRV+Im1jhUudesqEv2C5KOqzJP/hf
ZCQUrQjvldEz2RkqCC/D+z+lRSTIS9+/G7b4mvyn3qoDzHLU+Z/+FmOPx3QUx2Y3
syJtXi1W5rMgfqSP3ifIYWrTih67GwDea1zLdP2Q1gKJqeJX14CWnQVXiwAJGtfS
RQE6lIsMGzH8RBcvULUsB0hYAuCy82thTgFX2r7L5Rws9ZSmnkTr1bq4hzhjNShw
5KnIAee7+Ey9WpDjK7BYR1SMemINHw==
=h2DG
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e746575a-58b7-4d08-abf0-78e9c68b7a8f',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//ReZHu850UL2D5ax1b5tEkEsRhTEt9bS0jzdhlt0nlRw7
zl4bLJ/5lwnYvkZTFnvGhTepgU2wOlnw7VBG9CxsxrR2TO5T7PQ5i1gtK8ukPbW8
cKtLaJEF5o9VEMYy7DaK9+47Alwn2GbGbnLnCJfsK4jFbGUbwXR+DeUNkc81PvzQ
q0upnXksao1Cvig5VD7unr19woceeAJJmQ3/fkDoGPjavWUpUTP6udettcjp+YTB
vexF5APvOKK87gRGJrPTF9KJMYlOkO2qQG0pY60D07SMpUDn0QLAUyaAPsX1RPaK
PVF1+nGYVYXnBBZTw7W+X3kt/kQnj2nZBFG0dt3YNEooiv8R3+YwsWRYD9XSNgtv
U9DgnKeqvvahBTrXtzfSW5MC/VfCZfp1LYz2Z9M8C1Fp9pybxOs3VK0TNf17nE+d
HNiBfJ2Des41rKKGLQ2hosI9xOilt5OfWwMeAswo3PDNoUy0Rl5Lt7vIfuArBYyp
3ODe+Ax76+jhxTxn8BTJq8hv4Y+I826/3XV7b8sI9fcyfQkxbcFvq77iSppdxnku
p2NY2EISMrO7tfRx26atDsEcd5D+Kij7PkqBSVmsfwQjnXs2hDyPqsCtTUbz1fc5
++buO65itkxmb9t+24J+xPEs2fPQ/92jzYG2aDgBt9R8n8aBTO7tKUHMdv0YGRfS
QQGlIHMPEAczslyXtwg8CzqTihTIiwXNbbgas/HoTTX7ZhJK0ONZ35uFHgR/uVZX
yo92sblM18039/ZNq66KwphC
=up4W
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ebeee75f-6544-45ed-a3a9-59020298646a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAoKGeWcot3cfBCy+9y6xOAOwDwvKrthGYruD7ewsv3Zpy
fs0+64ARWjU8zsO2AMX4R50IAKS43c7Swct9TPACS9VdGDira1vcHgd4bB5CN7AW
WJ8nLnuyoI5/yyE33cxpA4j39doShMr81YuWOzEYM7bvJ+j2km+t7oUkRkuqupLk
ZJzUTITcy9jKwHvsykjYms61BXsf9yxVnzv7yxgKfbfWnC/cziU8jZ7DQ88wwX1C
Cs+FZzAhjNRZHG6nBDrhBc8F+ExxYCdQOcMLWA/YeXMj60/xGTy233KVGacgCJ4u
g6tPcgTC6lXlZStf5fmmrloBLCTF+5Sxx6F+Q/sTQIOXoyikXjcTN5wATsMK2Gla
sEr4dg/VRzSOKLhxnimvuRigKuPFbnB7Abskm/QMAcTX+58TfZ+iiqj+ayzbwKBT
VPNSZ9ic87TPbKA+d+iJzmg/HZAXeof6YUgyHQmMaQChbneFypn71URPNQfIwn1i
D+PDjxLfpjOjhcTc/HHiGb0SPWn1A2AG+sF9tM5BoirypxOpA+JEap/bOyCGdgqz
Mit49B5QyFRu4FKbsVk/GKOdcbNUnle/Uh8tyWvV2yFB5u0NJHrLG8shxSLNksr/
qzfncrRCkxMNhAPZ7gO6IZgiHJCpqiLj6+RML6k054JLcmaMedhYOsD4HAsl1IXS
PgGX0CC+NdoK5J8z+XTTKiCLanQ0hhAFM/AkrAfNiRwoFVhHYc+V/ImcE0502IDW
v6DxlyZ9FaF7+b+lPVeh
=Fl2E
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eced7cd3-c00b-420a-ac48-fbd75f100773',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAnpAd62NClGXwbK8OgOX+jKYVBtzkcFju+qZ7DhHKaQUx
mdl0zu4N+23ZbmtIa9qH2LDzT142wFpfaDYwV3ohFI8pnn+6GBEdr92aAxESjhAT
P+xHwbjawu1FVg+DhVouQK1FnbeXOAT6CvYpn3WTlDn0XHCzKjMjWl0OlBtT32ng
MHSqSn730YUu8HNgflI0ihMqXYSxK0t2Wz4FRuVv6Afb5aScF7ORhQcR6Mpqy/hb
dHzAW40HL/qhXaq9p1Na6yhMefdLRc7+GPVUNAmqTosEHwxXL4pY5/+2EAk7WAEl
6upRb0eIbaVzg3iXyLNgSQYmqU8edp4dbUV3juTGBXeOocAJRCCHdj3SN3G5dU9J
RJNhaH2Mz0yehbbLwb8xw1YK2dXQtX1WGAmkm2gLn/KW5hgIMg5hIa99R7uBiLZz
c20EPunZ8bhxKsin7P3g+RDGZzSj7zcZNGdY9Rc2JPQ1oDBfSN+bGSAeyWG5Zi+r
gOjEeiuhguI5R7Usc+5ahpPkoNTKBxJoAxP/bJoZYmq+b88oSRnlu+2LsKrPacK+
s8RUvIS6bRDBexxLguitTQUlLrFdmEMPb6pV46jEJEdFqxtlguUsjgDduNTjRpKM
2QIWf8aLRDI94d0CK/kOHVVLxrUS8IeZGOG9IK9TjKSh4zpow4iSyLxDrJaeubvS
QwFOibJr2xjKXJpMTxLZCNSNNFPdvUwaODRaaUviMExWbcT6wTJEA8JBd3tsh+jr
plmjPptKysk9bFPmsGQBAcVEDRM=
=WjvF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ef685dea-781e-4d30-ad6e-5d10b5ddfc4f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwvbHXCdsH11vLhtR31LOj5m6vNuABe0pvnq4LgLVrxMz
Np2eCe/4peGVXNnE5ivA74ecsMWqaUX176mnM9r7TbXGqWpxhlif1QyeSRvGZuAK
MYRTgD+CkKkvYaEDjLSx3dEP/I2B0YjvHhMGofQmTCYP2b99MMxT0xe97/Gzk41I
EoA6CrRecvgbnIKJRmibAlZ/pHeA3uSail0pANzyJVKdp15aLUIGCqVMWxScmuQM
IC/IEHXVNGqGxuSjhU21HyNGw5+VdLGOlVtTww5f1SFDR28k8L6pOLACnzodfL6X
BF76kAfuG3KQluLvTlHU9aWqobJWMiHtIGscR7lYZsC4RmL9vL2Z7cOFGK+dY4+Z
PhZ4qDl5ost6NqPeQhGD1/bOlEk/NgkTRIGOF89sg3bwULGve88mrptlJ0yg5BBL
2seIZW7kG1DVN97fIBfkzrbjeJYimjCMHYptA6dRKChSwdxeba1bTZddybykFdmr
eTqig+Bc4lp88pvZFIq0/yNPwHfSIw6rkKzeHyWslS5Bq8JonM69r9DI56rPjqw3
Ir4wga6Cr9SEgNB1Dwwq/wtBvcQXQQPMFGsu+4WbqPw6KJ+T2qA/oc1mW/0Z4o1O
4y2N9ZO14LyyHgUtFHNVBk16HzF5oYt3pvm1QE8XfEW2VkiVXvLWBvNNOg0IZZ/S
SQG98hoeFJW/OZiKg5H9wxXm0RQsGxSqU8D1MAEJGMQbQmEQbV7fSh30PT8w3Xd8
eVeXWMBz+uRu9NeQkUthWyiZOfb1m+c2mCw=
=nZPq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f1a54afb-694e-4f35-a857-428f017d075d',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAtCBV5lGe35kUE5/7/7XPGah06sOl/v4r4HgzSq21gNim
vs1pH9XuxH85nvEGngqyrQKDfS8z9jpHJu0NUqI1Jnf9sDOC8fkxlmo/k7asAW0Y
pMKZ1e6h0kKPAbWUnJ6vkpbIYSjlNHP6nDFxnlSno/gYLoRo900IxqunkvNaZwoc
wXLVh8D+CsXrvCvDTEvz9waqzHIqpsngF5D1Cwax8cV3okYhG75G7yEGEUyQSPv1
rKYKUIKmxBpWCy8lXq/T47t3+eLmnDvKyG/JpHy6MSqyC2nzPHkYva3jAsXiILLp
zeL0GNrXTwwjUYWPjsf1MAj41dzUU4ZqbeTGigojLuVdd3kYYEP+kkSYlJV10Lfa
Qwn66neGo9zJ4+DFELGKfiA/co97/XqdnQHnP8k/OuhJAyvOsjzNJZgGQQaBO1ZK
kB9WxggtyYa5VeLjecjQQIx/iZmSSCKgv5g7WK8ZhUB13YIUu129ezexLiSjHAgq
oCv3UONT/+pm41m0jP7KEyzHNv61staa6GSXN92483LXT0otCcRhPqUGmOr7mR32
4N5kVBB2flY81dqylVWfDJ8ARwFiHH48iOJEqy90CurhFGfJ0zEGf8I/FW7MNZxP
9XZFd+wfGNAhctoLv4oE/tepjhQr8rHrH3i/0ifNhHIhr7oEunJBE5OBqg2IlDnS
QQFdXcWkxjdLncgOIRexjvnJZO0SZ4PMKryoAcaooVzSbJcr2m0ODVVMq0OmhwTI
WTDiFdq4f9yGaPzvOlw9Dh/6
=b3X1
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f31091c0-21b0-45e1-a384-395ab34236df',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/8CJNrf/dbLbSyPl3KeAOK0d4sloBSdAMe/FWgW4IlCBRu
+eEBdVmcmBSYodOJge4xsDG6oSVRCli/Bwqs3O0jL51jUZWsPFND0tsws0WbvFAf
rw8LgtWCdxnStyWMsVGyNRpNHS4bQeHe8Lsi1q2y8cqKUvsPdx0uUK7el6ctErv8
7hF8871fLte2Q/qgA8oCadZqZQRNVh8dFZT3t2bJBN6CFqWqSNTvd0Cx7T6mBxSB
3OO7f+ELVN9TXHgNynJKtlpbW20reL82+nZfDduh8YCKD47UyiOPxpcapQI2pOvA
GoA0aHyBtvn2jDEv5+QK1eAXMhAmbxSGyHQstBza1UOgPD4HAcZymgMujQ+j2P5y
1ldYCYOQGokv03+ORgTmrHM6rUo5HxHFs45PNlYtho8VSAExFHRVojOBwH8v56LI
Ix58F7dhLpHAxBAfd12c8c7QevoGOUc0GOgSxoWdE1gMIAUQ0TeVsXowr+059bgv
4ZadKikbc/3yO/9bjm/Vfa3BUXxq80ssDcu7hD4uD3r5lOmMJ7Bi986vZzP3oVAB
pA8ozXRo11CKRW92zfxjrQ4nw74mj+/0cXIcej7+9kjVHMk5D5LmPWyF2afEB7YZ
+wXV8LGYQSdDc8TP8QRepAJgPbzqXiv1RSr+AtsFbf0oq5DwR01HiHf0n703uGXS
QQHmSHKlC6eFfCEzJ4k4nBPuCkJ/17Lq6HX5Di/Aa2yJfb1AD/BYwPzWkAoVmScN
Sq2LSJf/WBAOiKktt5MZV3Ag
=oOgQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f4611c12-1123-48b0-a3dd-b6051fcb6a5c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//d0Qo8bgpv2+i7AOjFmczT2CHrHag4yciRRw7NPcaTyug
7stk8gUFJPGIto/3XG0Al7aSXC1xwt5k7kwgM5T4Gtj4P/y3gAKth/ZOCoUyKUyG
YJoiMJY38Q79v39cP8LaHlh0bOtnUpqqmo0sKA3cIbWW5EEEFX6Ca6EbOtjZbaUC
YYAJppOrIS+O5ZZDoVFdRAxu6iXvbJD4Ek0slj76jn4eM+RoauK+mHmzlpnYfsT9
PjQTwx/e5nyAVU49t6fnSqhld/i+ppX9d7zA1ArmCmzGw9GswafycpuQ6KrBmHRT
xa0iQG/x+CEFleT35qwg/7VGwB/8tPGLdVtKuY2W2bdVtYomKniIKvxhyURmE6LO
nqS3wiVmtxPFYr3EdBvKRkP+seTTSOgJGdmsGFiBfkYHQdBsy19tWNcLPphoi8it
OmhO4Mh9yj37L8H4OZpo/mRR8Bt036Gg2Bayu8wO2mEl2utAQpaV4SpWHiloEkAz
l96q0JInHH1wRzmOmrgkERb1aJMfxsSckGsdFh/V1mWia7BXUezLe2XoQgj/QquJ
IxzeH8yhSHR2TUxaz4urdpCmh7sOA8Kzh/AXWw2SGNk9DK3nNlMVuOK7p3s7HagU
63PduinlYKWmcZDvyEytgKycxoV+iB2R7hA6aj/l+VP/7NDO2zUUW5tJ3AcUD+LS
RAFypV8XUKAl9jkceCUqNMGtUFJWEDoSgIBz3p7Pldzallacj70BG+R85p9gT7dd
Fn/FZiS4cEMfjSi1/5wVW7CNcq2U
=c9gh
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f47d132e-f957-4019-add3-2863175dfb45',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9G7DoE3f5URT5MP9mECW0za4+eWLyGE+c16VVNFUFbsXz
Ce4JKiZqu/D7qmVDtJ6TCeo+m75c153Z18naDwcyAflPZSwZbUOhp2YaCFLL6P9a
oyr1+KxF+X1NGEk1ZVwP0wUSF+FedJWpmmS8btEo1fb2Q7UDxzfeeGXa37IyXFVK
k+HWmj3DuFAam7Dpn85tx4mgme7h8MhR92UgaJBoOXnUZVZaOk3gLEpOVRlEqWo+
XfsWSYphjrqzTSpCdIEGMdFsLjvmNxVsBVv+c/fTYSYL2cQmeMPTvjkuaYn/oxgo
7pta68dw3agVHOJl/8A2t8mHPz0E0L4uiZzRHKvXzb2AFOHYyXXZNxoHRFmv12Fb
GQXD8K5Ybj0h4Ny+4NV43bV8SuWMpZrSZRIzzNqVtv9N3i2bwpXiUn9+huqeqmHz
+ModdBbmypxCszZ/SmTDIVp5ZFZ6a/ekTEolPXp5hyxAdd7JzZ81Uc6uZ3jh4j5U
mQBByftORFPtzvB5swc96cl1lGylGBvRLnVOKHQKzWvAZrotjTO6M6QQ3oLdlFJu
xyAM7fMdico2U6xjEOM+F1sBqutwKatgbLuseh5qhB/opzeWnhkSOqpXRP2RVNcE
cP0q5rB2vAmjz3DIcE5VZ39nuK4G6e/3lEh9oZy5UjwWjyHx4EsPwLmPN55lBLbS
QQGz8jOAY3nj54LxPZRHPkeL1pQmldyMZ5R1auBfZ/fWpCwxTyWzdDl3GrKpNRE0
Aqb1bX2JvQAF22uFoCIlZVK1
=oBg0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f5fb0314-abf5-4d13-aabc-9cf38608245d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAtZf6jtm9n+BOc2pv+sdgkfDMPNf5i2jdTtiqezrcCQYU
rDpCTSuhX5/FvcKPr5QEVVveBKL1fHWKhNQ0nFigJNGbdpp0NhPkFYJU0o+Uiczs
FwA6rC7xJOiYY+rcMl6r6porT6vzzqkLrB95JoeoJI18HHPgCvBVCxve4KIe/9vH
DbVbOPgCqbGjWC3+z8OUc8szd5abQzPedlYOe+KBgi49LTRPN0cO7A+ePXzaTDhg
7lO9CnEizWkipRsdPW78SklRqQrAGjFyQNyojS3Fn+fk7Punl+kGuSQrhR8gdVUu
wCswqru9CsPKAWKc8UKe2/VT8dyL3sZ/K1qCs8sJRqeZT4ar50yInF7vzT73GV/Q
vnCit6dZxfSYjoE4sUjm4eoU1i9bA1zEKneYP96HXevlCb/5gk9Ph3ciNq22WGT1
bgLfmWjevOKd/J0AHVlTa2ppJxqc8b1Ys2i3qKWB7l5TT+pWChmPhHs94WEE4dOK
ew/W9c0Rr0aicLMxt1/RR+gTIc0hxGesJykQddLtCpGmdGUFYX1z4uAdYclijyV8
txaaykIlbbjF109GolXDrJgMtaxQLKRpkhPorzw9HFGDAogtdrYpYUrlKwriCvfx
BHk7PSAiuNyG7um6FrMKq31ctsGr0/h4PmbL40rWs6CWtmtHVO3S17OaKwODWKPS
QAHISR4wqAvCTT0cuD3OplmfU8dGsEeU70wU8xk4/g+z6GZ4A82NbFhZo0Mg/kPD
BH3d9gcDk4wpNzNFdD8x4p4=
=wUMf
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f6b95dc5-aa08-469e-a3c4-455157a6648e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAhtlhNhbkf4pOh/Qag112SYZrFxCNX4K1pcD7SwSeWCQq
W9HAkt+1w9DXVCqt5Y5sOX1q1DTZUk4UGCCIgUdkAWB37pFerYkmK6GepgLBbih9
/BJQoUg4nOBlFkvvz1fE7SxqZLLsm+5vO7PRVrZBYqjxX3ZLtXJDtg0/qpPi5g/M
/Wj/7aumDDT1ugfhxsFpLlT2MMSWHmxv9UnL28tKeJGuVtnkHrovB/oAifTgGApA
HbOcbSaI6TojvOaEOH9D4u1rR+8Wu5m6gNw1wJ+uePCzh7ehhcHzpdA6U1AIKdX6
6QZbw02y1KuqXRnc8xlFUgXnF+Id5IqzW22o7haZx9w9NOTPUv/bsTpS6267JbLH
6gzpqFZFNJF6UCKLAv7PzvidgkOLJkHlHtKqmaInGzCDyQNb0dVMh2iHpO84J3hL
oS4i5vA76ncdxfmADjeChq9ZOqBFOxdw0M5fO5q+tcW1pkDKqDzPMsMjC4vPPnDa
kUPSg519gEDFAqWQlfRSIPNC4aR9p00SPBXn411UU79AGUnZVgJqtLhCHV//u3bo
xx5vWP/A0123whHLPR22Q3h60hIcRK13N/iBr0XuuVBS8+c1ReMG5F2JdvrVHKe4
T4UudvTfWsDBulNvqn9Qu3RT3nFPkRWiPuk9ROklgjv04AnohiwEHbXbBILK82rS
UgElrLOXBD0mVVp+vsIuYE8tD9+NNcfItQcP5pESsg8dfGdx4MB4n6cassvneEha
bkE4ClonD7xduAOTOPzp8u7Wm1j5zDmwCvUqn1eDr27cycE=
=RVjr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f775e34d-07a6-4ffc-a655-b90433a19ecd',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAj/QNSQhSdO/rSoZKQxy6Hac8V/VD/LUrxEz1mRhghD/3
lXuQVMO3qcuEzxqvXQY/Jqnu/MNTqUtrPGZ7WPUpghQp0Uz6NZnC8zb4pd3m/kJZ
yRPGGfatgHN2QPtnpfr/FjQMT3dhDpatapQuLghasgmdUBkkutSgUPva3tUDB3v+
KBTZXHatbvk+UGbI9eKFlr2UPK6iVUF2ClneOWDHboGTzc89G1D3oivaeJH35k+8
7EDEJ9HOkh69iG1b4d9xYrD9qHpH/uXc63VcZPZOFrBwWfplOc0KEMYy289xLhE1
YfqkssrGqT+vLvy24YGQPUPExYnWk4a8HHv8gTY+H6/PG+fAtB3rwEgthrfhPNQ+
7hGR3ol8lcTVNxMQa1OSOeBMFxhWuY4BB1Yemry0qZqTxtY7UPYgZelwsz3Oviuw
yKFI1K7/1ZfFIJkSCxSSqwMNPUXPQegVpu5VueGvVY/76JYAmZrbGkmdefKwkFgs
bWAS5hOJn3hlRqUASUdE6E3Bzha1zETfEE96eVIFVYbP3Jpbcoud7gW89idARxoo
PnH2eM3RppAHo08OFAzcamRVMU32SISt/bvXXxtksidIEzXf6ZeqA1CCSga8Xed6
dmZ35M74WfLg8JgHhODuFQKnxK7kX8bcDBLs68LWIMGQbG4i4bM6s8/VPF2bpWLS
PwH2IMLnbE5b/mglDAwggk+RNaZTNuVurVlsJFgSBbdOvsB7Y2HbXHI9n1scQXWn
1xSxp1cTHY7CCvw8e80riw==
=5U+P
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f98105ac-195d-49b5-aabd-358c292a23ef',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//Z0pNaaIsR4GDYKRY0vm90V+3nexvYQlR9AOm7jYg7/wk
+8HyS8sOqJ6sRQYaJYLZgMjCicXHZW3y7FkTm51SUyN3otzb8w8d8I3FtFgiMD0c
ZRxIl1T+upZFitDgrSH0lRpjYi/ZkRRe21hvWNCDdbhkjsrZDD0j2fGl48+O0Ub8
WGavLGFS9Bjpj82HX6rAfvjS2P3x/OHSFm7/3IOsMuygtiGWIbMymwC6q0Sjwtac
/HwTq8uIdBlf/LAOR2I80waH9oxz1jMMEZaUBhD5hQ2CIE4PZCr0q5DnNHhQkSGE
QIXqj6yO9Ioc7Z2B63PNNwxU2cdAYCUN2UuITa6AkoJ7qKWYhZpECHd5SH15a3dL
n1jpRXYvzm3vyAtU8bOF2yCcvKS74s+Lleu3kQxrFKKqHnD4w/a8wByElL/wVPG9
5rjMKNh2vHCbUlYMNXOcfkMhg4aAyaN18oTYYDUg3LSVobNHlScMUkDGnoT9zHct
ZL1gPaNB+PN/99X1qbkbw7P3obLAYlTu7423PWXkixDOEcE8LRfHmmushcVHPEkC
VIZ0rm3lGs+3LXviALoy2suKFx2Uao4nmYoz78GzpSEHSaRI3Afih5HTjA5oXo5G
0V6lQ05Vqb2c2clyjG8lLgaGLOwAnIpc7Hfg0ulzC0Wna0x3RvU11yLdGxpsURnS
QwG5fTqIvCt115UoH3NmlxTtraNTRCmBc+pma1x3ELJxZ3CLMkjodPcnheOL9i/w
opLT4MVU0tX3tkXB8fc8ZUd9U7g=
=KgTO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fbeb2cd1-5a98-4e13-a459-1014e24cd9a4',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+P1g4mFslc/0a9OKqjYqPSJJcCnsWlua/vknNW7ZH8oHi
GKYeyXC6/KiqHUnbt5lpAaJIYDd2po32QZFSnsFlbyP8bGA51TeGhrK8IfNUUWHB
q6IYh4LVVYfw0U5ueGTxm4Bxt3EcIm1si/kfkjmdXG/oQp+//MKJRscwgonxg18Q
N37HC5Q1nXzFQnRAkWSuW4UZGIyvy1jjCQkSbG8iOZSdn7m/mObaf3omhGW+myPY
YmJh3eoWd6QDUIYjZ3Sqy4gkv4P1s64V6ge0xweey6DOxDWItH2xyMoSq23P6Rx9
uvYhCeK1o+vfDtiTZ/9QBpukDPm9UMpxmQ22b5P4EmZwVoFEvBcoz39VV2TcGuAD
2fdXdVde4dIqgKEgmwDhC2cI5J1SV4LumtYvBGKW0V2VjR6+CVYMcjBqXJ9MzM4u
0Wysf6D5184AGWrgolZb1JYBsJJbQmkMfOwNHif7jyOtLxwhOEjm62wLlhqdZ75d
L+kWD7QFd7bnUiXpQ99uMQk8P7naJMq23CQETleb7csHpdoolh73J8nplEeKS6ra
BAbMl+/ScQrT5b14rb5V8O6B0Hg5lGI+LZqUsPYcwvWP7k9dDmEdvHpIuJ92XX/L
kRTg82HYmNt2wt2MIk3qJUBx8RHMB006TxqB8tNM1MRuRunm9wGQnYS6EUBRrPDS
QgHd944qDIQTWWmGYCk4L5qqpUPPywPe2qohk/nsuDCEBCvNeRfJ1xBHm3j5pKlw
s7OnNhSZsm/hrx037kyYhHiRag==
=KpNJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd10868b-4b6d-4160-a26b-236920b55691',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//enfF3fagBfsX22yCGKN+x03IM661fhBzpt9dfZThzj+S
Mooc428DZhEZOFiH5pL5/Jubqg8ENLcwlgSRGDdH9Sj5hIcL0qL/dzKZgqvpCtXv
Ob1KVK9e0Hmn9vXePudNRNH58+TE+4Rzr8JHwXoYIKFN1CbfrRKu9SdGanAGNyJH
cDTnE+Rql7zvLMK5v3ULN0RyBguNfdu2IjGHOD35riGrX9RaeaTz0/FS32/eSPuO
10bnRvydC+4EJ7PJfX3MaQ784otXnc16BsEfc/KpSJIiZjfgSQFbJPycOPAIxFTO
qzjGytmtEtrGo6SBnGhBQC/MN0e5Fc2ntQsIBEwif4/rHZWulm+J41UnjdRa70Vb
R/BD0oJDrYGaPRKM3sZYPXNzfnO4i3rPCinckcQWX90GPgDKF0peHwLEHPe+YggK
PvgspM3dqHxew2LgJbeEbLCae5ALGmp1aA9IFi+5BCyDk9TpXAle1wXlVGP8ODEI
EHr48CkbctLd4khZ2XSbeaezo4SaN4TPNJoDyAiS06x52hTH/FIvl+vbn498+G/T
fxGeEgpRcxA3aYIXnCJolFiN3IJX4XvV+1Cih9F9DqZmbMBEM3XfSseL9dxAyc7q
bSLqwF+mDjrOUE4GV3C6/J7z9QMCYUxiHYZxQVicr2mrrUD0fWpbwwDEFiIXVZjS
QwHPYvarlcR3eRaKlGMWvL8InP8SuOqi/kdn/HU2LkK8sUlrr1+Pqjo5u9ZHAhD/
X9/DTPF1MVWTagLaa/iQMKgniDg=
=mEhI
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:21',
			'modified' => '2017-02-17 09:11:21',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe4a6f1c-9278-4142-a524-383396972d7c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/5ASSGG6dWkKmZEZ9DbOYLt2HzC9KRynrMisElPnvP/B6E
Tw5XpmGAxJWSB+Brrj9bxUqCxbi/HYvPGYTjMGEz3B5wSj5EOq7L/xc/WCPDTI3W
WiqQwi2Kpcq3wCC28EHfRnu7+oAYVQo4ev6BbrJCjjmu3SnVXm6/B2F965ZaEYcT
7etezdPyxjzn3ForWItNSmBy9eRYKk5J0SXWU0biIddStnxaODvg3dbrjo0haPcr
gosO2N7dWoD36UeYeLv/Uf7FUngdyz2b/c6F0F1iQVb1hVbYV0j1rJrKDs4hNtZ7
rgzDzyAORozbVcYcyPE4Orv2ZzZF1g2NTFN7dRq9Sq1axF4FpvrrBWnBDAGpVt0u
x/2kuAjRYOwgnygKGXsuqxp0fM9yHksq7QsPhVowTHGuJcqkWyMwkCB/yDYTbmEC
2Hb9Cbeg2N7F8xvx9UMh2HZPXeNyjF62dwLkwKN0+lDySWWTuNA6CvePbdtV6+Ep
JfZDR9xA17lIlMkymGioY77+aimsoQicnGFZ38elWyI1N31akwqoV8Gs7sM3gHmj
PApTvzzaa0Wo7c5mklSV0e0xpj89G3/gDjL14oVbllhKzMCrdT7rdzzgedG1/cQw
+BX1RYoVk0kVhuuLdku/fw8hXW1OamA806IIBm8cSEv7PosuVZu+LjgXgln43jXS
SQGtcbkzIGKIVaOB5LRyZS7N5gAKenfX4APCMuZILgIkSFPgEf/9g02IAvVsltdH
YIVY9HKcDfDVxNy1J6sDx3YSoRlUqPHSH/s=
=dNp8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ff5c2e96-12fd-4756-a057-a32dbc7b57ab',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+KyLxuO7y59iZkZD8iZuq2e2tfp2qV3uL+p0Mpn0aMIo5
Dk2l0+lA5keF5KQUZGLtsr89AvUbSOAtm0jvgUhh0nY7OkDyqZ8moK/X5I8rZ9hN
TITfk8J/Zt0Kgkm2A56/gn/r4LqEURqwDuQFWFYhnjbm6Ckol4l7MbLPTdmd7r5G
kdlAUSg6JLjK2KHQ+PXYmwBlG6hghH6N1CxiPExWwM5RQ0M8uCKJuMENwP8bbNyS
wS88Tphd/PG5pjZN5QCDr6hrZNsMacOjitrEajZmUYNvw3hdk+8FYRa7IYrj+Sya
RZlbP3W7mk6Q1e0kVlqqkrcm+EecAUQy2Jy7yS85wgNBLQQrPJdRqsKcVZETnqJR
OhZecTc4ram2E96LUTPjFDJPapNi9DwP61YlYJUKZ0lbI0FmPX3J8YbUilliK2dI
KwOTWe7l1CkxE/eOp77IzapqmaVn85KY3PZuK40l45ROA4fOxhVsEV/dHrK4sFQt
V3dcfmSOgo3Sfg/4yDKHGKvzukf411r4OuwJYIa7kVJYS/fwaG2OmLwmpsF8KHKh
cXKWTpifn/sD4M2Ix+4kgJ5j7W8sxOaHBiFB4ScL/iFcHEZRH88tYHXTc0PTMpWg
LLHJenPuq7avg7gFSy5ASazh7PUUa5yaMPzPelRcA/ZofQQ0S4D50dJ79/ASisXS
RAEW5nfaKIm2kWVFUxZdUDqo26YqzYKLx4h2/ikVkB3O+tL4bu9x9ZRxxNjnWJRU
1+4ovf0NSvxWgNcDsY2gr1F2I53h
=UoZq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 09:11:20',
			'modified' => '2017-02-17 09:11:20',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
