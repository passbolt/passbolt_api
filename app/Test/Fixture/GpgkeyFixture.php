<?php
/**
 * Gpgkey Fixture
 */
class GpgkeyFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'key' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'bits' => array('type' => 'integer', 'null' => true, 'default' => '2048', 'unsigned' => false),
		'uid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'key_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 8, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'fingerprint' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 51, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 16, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'expires' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'key_created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fingerprint' => array('column' => 'fingerprint', 'unique' => 0)
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
			'id' => '01060c71-c0e4-45a4-a10d-2896df142baa',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
1RIzS4UqhfIPH7vdEAQ=
=GnKL
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4096',
			'uid' => 'Marlyn Wescoff <marlyn@passbolt.com>',
			'key_id' => '0735B1C0',
			'fingerprint' => 'E4400EE5E49B86B96FB7D7F48BB0AD490735B1C0',
			'type' => 'RSA',
			'expires' => '2019-07-03 10:58:34',
			'key_created' => '2015-07-03 10:58:34',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
		array(
			'id' => '2b50af49-33e3-4d16-a6dc-aeca428c7e48',
			'user_id' => '47ad29dc-f0c7-3b9e-aae2-9e657397b60b',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi6+SUBCADM9AAyXMBp32rNl4Hz2KqIsBYO7Fi9zFANzSM2jBCEuZVAE0pw
wVRgKOn3DAcis2RtUyd5p1n3v7okuYOEzATDxtRq33vmRX3U39Zr5UzeDD6rM3rd
T7RuaAjXhQA/O+ODw0eOATJf0sYftabVD6BRv93iKmOo1r8SEU56+GX8NrqcpD9R
p40wxcb0fRLfEie9vCex8VcusUaKXzqMARy9hctmFykoKl9cvvHkW0XYYnHWGr9G
KEZEytex2fpHNlkjVv6Ic1PUY+PxZyZ+6V0RgwMqztRdX8yrj07FoYgBi5+yJbBF
WI4LrYMAlQp+/vOsQuPkX2+6vI0RFHuNHehTABEBAAG0IFdhbmcgWGlhb3l1biA8
d2FuZ0BwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJYuvklAhsDBQsJCAcDBRUKCQgL
BRYCAwEAAh4BAheAAAoJEEdQAVv8B5UxId4H/0OD+N+I8+0go+FqWSNiLBKrFVDl
brqwrdcGMBFDWCCy95zQX2xxIpAhtLr0Dnn80ecu6UklzHsw8LbybsHf66v8daa8
Cr9yC5raNQfVGifdB77AkDuA8rjRP4cotAI3CsKlvV2YQRrCGWCRyIFI/u+UtOea
rdM3yv/JasLAHw8ExhDXwxhYkKgNwn3SZciFSaDLL0UwldBS93qi6pjYsmOVA1sq
OgT61Y2dvqgiNhIO5cmiVxlDNJcrHnCEcSepU5nmv0A72kNfmE4mOTnG4rtU3NwF
GdTntH26K5BfyQCDw9aHZklaGofuxVGeZ7ZwPyvSj5HZrPP1WgD7LnMHVJ25AQ0E
WLr5JQEIAKeyT+yEhWyjCQhrH5QUgieFUu6IV2npC+ErafvrtVnUeWLprWPBnDul
qoiTu7QbzEXT/7MbwlgticNq9QwINWsNSHKBvMjOXEFRhOltF4AXYTBJThnyFR/o
huGQezmjV4TpN8yXtpgiy21W+/XYjrY8PaIRM1Adc1hSmMSiuzLu+VtKIs+8tFFa
s8acWmskgKww2vHHJkxQ+ubWl5W1Vxa1Y/kOGjMc+qSHZ0v6jEnkUjaxLd0DW0hH
G8weEunPzBBSJKf7NstZ6c91+4LUCVoNqCYMkq3ymKRITN3vmJSfU+jIXNOd7OoA
+TP8Bsj/RS4opQ6kRrL3tficrX6QzcEAEQEAAYkBHwQYAQoACQUCWLr5JQIbDAAK
CRBHUAFb/AeVMbTTCADCdL5UyXBfCOp4D/T2L0uUL/2d37nyfOU7c2FCbVuviG+s
BAVypL7KaXNF6gp/FGidSUZDVIr+UzD9cv3s+7J0h5OeZZigyPlUbWaNy56CBJEr
YZH5u3+i6S9cc3udbP5DaD9EpASWUbsREi9t37O6hTS/OGBTGxH5KpFsVBIWBTL/
+XQPsd+YMdoBVka3yKSzkFMLRLHt3K0/miwXj7IK1g43mcYHUCrWxjjQFVjUZPBL
QiL18WhPkMO2lCHdGR9skSycwGDnt5WVtGnn0x7+JV0Yk8RpuopfOWo83+oBzq3c
erR4fuZF//Io+VikJe6mKufSflOpCDYfSOscdAA9
=q+Sl
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '2047',
			'uid' => 'Wang Xiaoyun <wang@passbolt.com>',
			'key_id' => 'FC079531',
			'fingerprint' => '28C3C228F27C892A0583AF534750015BFC079531',
			'type' => 'RSA',
			'expires' => null,
			'key_created' => '2017-03-04 17:28:05',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '47ad29dc-f0c7-3b9e-aae2-9e657397b60b',
			'modified_by' => '47ad29dc-f0c7-3b9e-aae2-9e657397b60b'
		),
		array(
			'id' => '3177d159-207d-413f-a248-a79428c7bc9d',
			'user_id' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
BzXzQTAO+Vo1Nkyf7B8=
=vRc1
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4095',
			'uid' => 'Frances Allen <frances@passbolt.com>',
			'key_id' => '477FB14C',
			'fingerprint' => '98DA33350692F21BD5F83A17E8DC5617477FB14C',
			'type' => 'RSA',
			'expires' => '2019-07-03 10:48:31',
			'key_created' => '2015-07-03 10:48:31',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5'
		),
		array(
			'id' => '402842f9-25ae-4cf4-ad82-e5bef801b1b0',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQINBFWVIFEBEADNf9iYgEVVxHAQ06XTEtx2kpm9jW4kiwBUeJxDEWnUPACEW0Qn
8qA+WAAMeFppxGIjkxW3lyI+TfV0Cclw7h5GTSMlSlIosrNqFRDvj/q8ghZLAccy
5rcpHfLwHdmGR+S4qzCxfJQ9rkBdZQkde4LpRDmbx1EkFeed1FXwoNuxFfp7cBoo
/Z5if+mf+6pn1oLAy47PlASYltPvtj/pK3ZNBatPz5vfBVRjTH9UrdXK8ZjnWypw
ACln7pe1vz5mAmNJdpPhxvAMXMx9zWEookYQFCaeOKI9t6t5LX9Vn2wAfHqLV94P
8trrBRHYgAjMI/fIoOXxcSBEBM98AeJMgMjwQ4/P1o0bvAhxitNCIgqeLtW2bR4W
G+8SF6ALcZM1kGt8a0DSC9X8dtHpKSvoCT7GgCXtuMl1gptjprzHnM1thhSXZyFI
mVM3e99MC101JG1pQpmyC91KyHPWcwZE/ugIZTsJQwSjPeLHcGbp+5cLOWArH64Y
VdiUkQ0SwPdB1tsUvfekoNBWQgCNAL9yFTXOsxNM9AsZ+r55kQvp3voMdt49n6z1
9P6sVaPa3+7yj1W5LBIV0stgxixbXBBTnAx19R+23FnmecfHYH8cIiFwJsYWsAYB
CGFzhP9kYzU7Io6TXAZ03LY9KGZW1aRhZTUuY+JErWFYr/D+9skZ5GE1bQARAQAB
tCRCZXR0eSBIb2xiZXJ0b24gPGJldHR5QHBhc3Nib2x0LmNvbT6JAj0EEwEKACcF
AlWVIFECGwMFCQeGH4AFCwkIBwMFFQoJCAsFFgIDAQACHgECF4AACgkQ0/H+S+Yd
cAmFbg/+IxF2rEPKzLAWFYyWWZM6xIzAIzrjCwhuaEDkeqAz0P/1hQLVWETF+Fac
6CRwRvU5nxdKXViEXN56XYXMcTac4lAB4w7mbL9Jvf8DND31zzgAdtFnlcJb/T2n
eNu6jpfnacw534kE3mG/725JoiZFxDnPMmkwpmyrNb6KFCCibT1ktBq5aL3hAQ4n
A64cgLHG1nMMgquGia0UlqBIYVvGiuSeT2RFi2/yWX4IsWbfLRnB6lI2ZivDlitF
6JNWVjeJ5xVKy8heFeq7fJKqfZDNC014IqQdLRwGQDzLougnySqjna/5T5oYrFsG
Gdq87UKim6Mt3kukqnLFWTuLRvOm67mAO+Mj1W0NnPkNZbLsS6DWEr3eUpMh0LDG
KsWGVLxrOXYMcXpq0f8wQDDm9Xhh1yaK+1SXNVAiv9C7lWYbhHp8UooEYHJGJiZB
/FmJPW8IR+qMyFJDclymRmtY7j3pRlwx7ZbfWRb68IBN6z0GhThI+STf7Ku6KMfY
jBDlX/gVXwK51EqpRMId2fhH+KX+pAfun0rAO2Y73yJ+ImwXwFkURpat/e3g5zAK
pBMir0/iu9WJif7LzrZRFrdmk0zSh4m0mt9ghzitKw7NWyr9B+cwc3dkVZovoWHf
5UOlOmG8y+p9m2qcZ/+5UH0M8lY11PRjnE92Ek4vK4t4StkEfba5Ag0EVZUgUQEQ
ALvLlv4Uud3E3ep5DuOoJchOTDnpxgcF+obPt9zlQ1VksGSZDt1PzusVbKXvkpTG
WPmyqA5S6yI+ahDRbnQMFZqvkLi1PkExOu9xQ+UhWT9Q7k3th46KN7NMZi3UEHoB
AgmQZ4lsJy5s6ZfPaMLW65YvoZTe/FWGHsyOnr/Vk/yUkEVeBiA8Wz43HXiyTYrM
6XCUcZ+0lUqIGGsfhvAoxjmUS9GUAJqoYtMfzSUu1NpIj+gcDmzRj9W05sCAWulR
dDVgtO8Z1Ayd5FdEjk9ehLEfBv9B7qtQGHu07ygMMvANMfIHfXy7yI0jli9L7Dr1
RMxrYd7WWY5jBIcCuWaQOe9IBCYw7Pc2Olgp0eKphKLB3WSGgewxvs8gZtBuLLiQ
ADLCAzogXciCp20EQC3oBorVcL9yB030SmiQ0waxBnTHrhNLhzK0d70DFgwFI9nO
lFdjqx3j6bnGWCyI9dbNsZYYaW39tqt4SKeY0OarJtf1yqErslrmMwFSCqPuygwf
6ywG7VLK50Wv2LIMMgK2quTWgXCL3fNWg7aLMSmztQ9wQln6tk5B0cE1Ufz4SOUS
dct/+u/tUPkrtb9jKsP8Mn4yDHIqGXA0khGVw1c6PvCeZiBt8+HJFnGOy8ALtPcl
f0UXZHj7zMXtBs/33VD9VbeGdFtXLjsD6yNjAf4JyWorABEBAAGJAiUEGAEKAA8F
AlWVIFECGwwFCQeGH4AACgkQ0/H+S+YdcAknfg//brhAAqb7kd67ONiEpuo4fRih
ZRKldFnPT2/D/TzFdeQq0s3DTaTkHKP828CnplzsCQkTDh2IllKm+HpMzRp0nhAN
b1JRZ0iRVWSnJT2Mo2msm+khxhTD93YE5aME+B/leorh9ntZoGxfVCmG26bNtF0T
Iy4HVFd1i6jtZXQffkhL204ULxQB4NEcClP6B/AWLkZHg68/QfxnJxBrHUMcgpj8
s1Ws7HzCWhwwyW2VdpyeddtOnFj1HC7UZFPAfxeLX3RND7WjnHlI+PgC3zMKV4Jr
S34QOQ6LNSM8UV40lIZJaJnHDRO2lNYLFYMBOwxztauz/7aOMNUD3Cmq4Bd4wjsc
aPkUwc3pR9WuZ2XUJd9xsWJeyYtbO0G/Q/Q9LhmL23sf+Y1Gs1MgaT61j0YqRX5y
L/Uyf5wv33072ecukuWvAFFNWWwyEgDU3z8DXSanZ7WyWb50AXVEeR8sQlxx58i+
mbHV78dsJueHFaKlnDG3OJ9ixdzluGbhYZWI3A3Z5mui9id0QUqffCCK6+t7NQbG
9Me91FN0P4StlpNNwVSN4bX3OYWQBTcu2V/F3YO/4mzUtmnNUdehMyWxV6WwUnUZ
2eLa+/wjTOZgnV9GK/avt52eNfkIft0c/wkrYNUbhQFG7usyw/EaNIqO2ZahJxLx
gJf4InpB2dxOL4K2Z7c=
=W+0N
-----END PGP PUBLIC KEY BLOCK-----',
			'bits' => '4094',
			'uid' => 'Betty Holberton <betty@passbolt.com>',
			'key_id' => 'E61D7009',
			'fingerprint' => 'A754860C3ADE5AB04599025ED3F1FE4BE61D7009',
			'type' => 'RSA',
			'expires' => '2019-07-02 11:28:17',
			'key_created' => '2015-07-02 11:28:17',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
		),
		array(
			'id' => '63b2e336-ad3b-4217-a5d7-35a049803534',
			'user_id' => '22e38bf3-6505-34c9-a6aa-55a906f18476',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFdFks4BCADUicDCtQcFBO/icQhYC9TWMBA1IUx48sjLL6FBWqT7c7DFBlaT
WwHMNMmvYBQM6iubnJi0Dbwyb1RyuG4R0t9YgrCIAopF9FmAPgJN0YU4yFLEKnia
tetnhpPizo6AWri9ldVHf6Uk4J/oIcKO5z4YaVHLsZf84dYoAZabgzGA31RvzD0G
hKDVOlFQxvEdLKx+qYNtFBgR2waSUxyW2/xmUKkdLpm6PR+S1SezE2WxzdgHDO3V
93sTG3KhAZf20O6/eBU5XpAX89mw8k26LfMtutKpBDpcvUGS8qDFwDmn6WL7+4fI
lQz2AW/niFp4748SBnsP7rBJ24tMJ0WSKmV3ABEBAAG0HXJvb3Qgcm9vdCA8cm9v
dEBwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJXRZLOAhsDBQsJCAcDBRUKCQgLBRYC
AwEAAh4BAheAAAoJEBE0Km1VHIVorkUH/im1vwuyhuJib2lez2G7S70eaI9NVw6W
UpWGaiP79f5PqVV4XOO07Q5t3a2ZymNGRAaLrZPli0pJuuQB+ykoBpeSthftCeqX
75TtoeDZ59ek4y4x3ULwg9SiQdXwppTY1b17YKIkqluHZ1rLpu3npEiUzMwCtzbE
K0tiGRsYvUTXXdX6uu5jvH16xGv2b/8IlNea8+25EdMDvnAwnmE2Vn0pE7TCS27W
xMOciC9PpJFCgRDtOhNg0DrRVaifSs1MAnZpj2s1+M56EopKFzOitI5gOR4n3+DD
3jicQJSgaNSsM9gFTbt0JUlnwDjUuVoA/1iJgsBfO/YM+h4QEs6Nv3m5AQ0EV0WS
zgEIANQ+5kGaLTLg3aYsYu0nRSYNE/bnn0u9B2WTBEocPUR+i59GdKmUW2HfRETz
7bl/CSa+uzOZJdV1XdgYg2y1FmzZwAE3VfaWm2FbZ42bqqZxnOI1HUBiGxXKmN2u
TT8SYPcf2CeH6t1OcwVVKjKr6xBVO1iHXg1gL1HCRsufBg0IQ2P+Ij7umjc5ClV4
HGd1K5CmV+iPNQQMeMMGpAjXe4hoeo5hsI/suEfxPf4MBICQsSvnCx6q4pbjzQXC
fr2H9TF8SLr02etuKfe3pZ3KSyDfKTCXG3Xcn+BhKDBjAjR/J7kX9fOgwwASeiyM
7gGn1SGZVeUiXSaEW6G/eTlHphsAEQEAAYkBHwQYAQoACQUCV0WSzgIbDAAKCRAR
NCptVRyFaCC7B/9wniEmSfN9AAODw/1EZIe4nYoJiXLI6ptkUnHltlAqLHa28dhX
EsJVCcE0S9MoYqwLdRzCf8Xwyp/IzBX1aJ/s/+xwKIuLN8IsrqBdfuXkebmsJIPn
AkzgYd+v12SbHQ7czF0qwOLxvmf/0tO04djjHpvcj4Ehu3yZm04fzF3PEsFH5xNO
p7I8lupj7/H1re3+iGxh6AYGyF0TUyKa9L6R6nn+o6bncBD3P6FNIsudQlPvFDb5
+2YQAmHw+SYqibMglLLzVR6B7thrP2jA4UbOjuVn/EScABz+Ky1v1gs5K83eL6Gl
CWQvNJmewRlYeSEz2aJtTkbYh6XMP1QHwhbT
=MFMh
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '2046',
			'uid' => 'root root <root@passbolt.com>',
			'key_id' => '551C8568',
			'fingerprint' => 'C1ADD56F06AF789A2EA649A711342A6D551C8568',
			'type' => 'RSA',
			'expires' => null,
			'key_created' => '2016-05-25 11:55:58',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '22e38bf3-6505-34c9-a6aa-55a906f18476',
			'modified_by' => '22e38bf3-6505-34c9-a6aa-55a906f18476'
		),
		array(
			'id' => '67d8c56a-bd34-4610-af0b-55a2b003c7d6',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

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
GkkO+pcgU1wQ
=Zopv
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4096',
			'uid' => 'Ada Lovelace <ada@passbolt.com>',
			'key_id' => '5D9B054F',
			'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
			'type' => 'RSA',
			'expires' => '2019-08-09 12:48:31',
			'key_created' => '2015-08-09 12:48:31',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '6e71449d-45b0-4b66-a96c-4b220b3ef1cb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
LhhailjVzD+qRWUEbzscp1Ih
=WyEV
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4095',
			'uid' => 'Dame \'Steve\' Shirley <dame@passbolt.com>',
			'key_id' => '959CF1D0',
			'fingerprint' => '03E6535C52AFD7544C555829DD8E26DB959CF1D0',
			'type' => 'RSA',
			'expires' => '2019-07-02 11:58:41',
			'key_created' => '2015-07-02 11:58:41',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '704cd6f1-4003-45c4-a53c-aff5932eefbc',
			'user_id' => '51925e16-17a8-38f8-a923-6b1f75337d2a',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
xmbuDQiLNivOI9WNWQ==
=nSho
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4093',
			'uid' => 'Ruth Teitelbaum <ruth@passbolt.com>',
			'key_id' => 'A3A2AE97',
			'fingerprint' => '9B78A8F124D440FEF5A52159546B4787A3A2AE97',
			'type' => 'RSA',
			'expires' => '2020-05-27 09:37:16',
			'key_created' => '2016-05-27 09:37:16',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '51925e16-17a8-38f8-a923-6b1f75337d2a',
			'modified_by' => '51925e16-17a8-38f8-a923-6b1f75337d2a'
		),
		array(
			'id' => '7466f626-d793-4499-a0f3-f1a207e7e839',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
lHRL/q8pHZye
=2YrD
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4096',
			'uid' => 'Jean Bartik <jean@passbolt.com>',
			'key_id' => '24AA1193',
			'fingerprint' => '8F758E3BDD8445361A8A6AD073BAC28524AA1193',
			'type' => 'RSA',
			'expires' => '2019-07-03 10:54:25',
			'key_created' => '2015-07-03 10:54:25',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => '77ab7288-9d74-41e4-a594-0ea758ab85c6',
			'user_id' => 'debaf643-1874-3466-abdd-ae64ed4e2ced',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
w9E+yhN8gIA=
=qtKg
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4095',
			'uid' => 'Orna Berry <orna@passbolt.com>',
			'key_id' => '397EEAB6',
			'fingerprint' => 'E2E98DCC84FB41F69603C346EA62E0B3397EEAB6',
			'type' => 'RSA',
			'expires' => '2020-05-27 09:33:17',
			'key_created' => '2016-05-27 09:33:17',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => 'debaf643-1874-3466-abdd-ae64ed4e2ced',
			'modified_by' => 'debaf643-1874-3466-abdd-ae64ed4e2ced'
		),
		array(
			'id' => '789f1fd7-f49a-4395-a8fa-c9b201144256',
			'user_id' => 'f0c51a43-35e3-321d-af6f-4fc48a460cb3',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
4Hr4x+mhfw==
=PxBV
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4093',
			'uid' => 'User Test <user@passbolt.com>',
			'key_id' => 'C9D7E7AE',
			'fingerprint' => '1518D8673F353A65A8C3F412615186AFC9D7E7AE',
			'type' => 'RSA',
			'expires' => '2019-07-02 10:06:10',
			'key_created' => '2015-07-02 10:06:10',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => 'f0c51a43-35e3-321d-af6f-4fc48a460cb3',
			'modified_by' => 'f0c51a43-35e3-321d-af6f-4fc48a460cb3'
		),
		array(
			'id' => '7c443cba-fa13-4d8a-a313-27d8b8927892',
			'user_id' => '8a53f107-7ffc-3047-ad08-75effc419d21',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi6+NoBCADDDLnrYnzqJJCCPRtsMy27QNnb13eD69xv15D5vHWzXjl0HoNb
FpxRQ6dBIsfkaxfmEPb8wC8ExGd4/IDB+69wMOy8x387kLEBDHFlwzC9ocRlLBKc
M7Fny8vLluh8ybrzuvqYreJUdBhzQPyYtVlOniU6P+jxnGOItp5Kcuv7fEMazVBo
oZj84ddo3yUuzq2Dc1t+lX6buJ3gMMalAMJSBjuyRabGy1EGod5o33FOtVdncs4D
x1xxAllB85a8voyfju30Xd8fhzGvfSg089D5Zwn6Ceb/WT6wZGdYbQh1pQTQZ2Ja
Lu3FQzpVh9QHLUmgV9llWZkyo7YArUlzGjl5ABEBAAG0I1Vyc3VsYSBNYXJ0aW4g
PHVyc3VsYUBwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJYuvjaAhsDBQsJCAcDBRUK
CQgLBRYCAwEAAh4BAheAAAoJEEA+DkoSiUh0lDsH/jpRKnBrNjy0U/c4zoM71S+x
icFdZ5YONLhN+/vNVEaVhx2w5FZYLUufgR2F2SwsvZ8vmCztPQyArUpSz0QbhTXb
PdTwMdtjJt1xqOgo9O07l904KMSDnpaWAfTp0LjMPOyVAdcM1+NVG6j8TC5547Vh
vlB/WSoge0+dhVyzmkDO4gQA6CPQiJfzuiItYkuX0FWUU7MrkOjL72zUot1uHuZH
GNGBYDtLcONzwauMdfblcZYH8YMW/IXCX0EEY6U8gRC8af0ruanWeY25W0/sJ/H7
MHTQ5/2rAOhRLQb7WiK/nNjGvLwwUiRNwzVZfXWRBeX7hgSvJFZb1QaIHQUCINW5
AQ0EWLr42gEIAMILiYSI8UVcU8LTuRSi2l7ZIMVymTAF1RBcMnaVW4rDIdY/FyNb
4ZoYmcxP9fkbNAedqmrloNJx0/ZnT6CYl1Fu7KQRM8kuunuD7xi3/0Xwp3OMMAns
mUUHkY4GKMiDeZ1+1QGVMN8ACRJioAmK0aTHSdFhx0jOfU+HDwG7/Hj8qo8HmTiE
sZ6SWi8wp2kdItdc+pEt4JO1nxwfHxXdBVgPJaxkWuCyZoEeuaoLz2N8YWbLTTy7
ElHcmcHAXC3noRd0p80Nvb+109H8lwv3pFkzFcrqsdM2y6+dYjtI/9SCDtBSZghX
CelFyDJ0msVfujGBoa1HU8RsivAR64sMBlcAEQEAAYkBHwQYAQoACQUCWLr42gIb
DAAKCRBAPg5KEolIdMSqCADDBO/i8DiNbsKtJwHM9y7dXDPpb8W/Hu3S4EIx68V7
snoUn6E9S/sOdqubq5kuFk+S/a1sE84iq8T/kDBT8i3d2e6LGXcYdxQXRcUEKawI
f8VYyA4J8UKXPLLo1J5A1MN/xc8Duuw7mdLDnbXEjjSFG7G7BBYNC7UBHmdDxOK2
+yt46o8ogqI59iREelIiVg+n1gU1mnhbFIdrhonv1bjr7Fu8nGz/iLynAMlxV4TF
Om211avkc+sUYjZF8k3zWGsNOEz3Cng3kaUFRZ0rmqVRZTRcAJuU1k7M9eo6I2j4
C1bPERQcYXABssLYtGmFVdoC3DLBcCY+tjrJRz7DXtpo
=Z4Om
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '2046',
			'uid' => 'Ursula Martin <ursula@passbolt.com>',
			'key_id' => '12894874',
			'fingerprint' => 'DD6A88103741A623F8AB8F43403E0E4A12894874',
			'type' => 'RSA',
			'expires' => null,
			'key_created' => '2017-03-04 17:26:50',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '8a53f107-7ffc-3047-ad08-75effc419d21',
			'modified_by' => '8a53f107-7ffc-3047-ad08-75effc419d21'
		),
		array(
			'id' => '8156abcb-218f-4412-a48d-3eb4fe92cad1',
			'user_id' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQINBFY06pcBEADjYRuq05Zatu4qYtXmexbrwtUdakNJJHPlWxcusohdTLUmSXrt
7LegXBE3OjvV9HbdBQfbpjitFp8eJw5krYQmh1+w/UYjb5Jy/A7ma3oawzbVwNpL
wuAafYma5LLLloZD/OpYKprhWfW9FHKyq6t+AcH5CFs/HvixdrdbAO7K1/z6mgWc
T6HBP5/dGTseAlrvUDTsW1kzo6qsrOWoUunrqm31umsvcfNROtDKM16zgZl+GlYY
1BxNcRKr1/AcZUrp4zdSSc6IXrYjJ+1kgHz/ZoSrKn5QiqEn7wQEveJu+jNGSv8j
MvQgjq+AmzveJ/4f+RQirbe9JOeDgzX7NqloRil3I0FPFoivbRU0PHi4N2q7sN8e
YpXxXzuL+OEq1GQe5fTsSotQTRZUJxbdUS8DfPckQaK79HoybTQAgA6mgQf/C+U0
X2TiBUzgBuhayiW12kHmKyK02htDeRNOYs4bBMdeZhAFm+5C74LJ3FGQOHe+/o2o
Bktk0rAZScjizijzNzJviRB/3nAJSBW6NSNYcbnosk0ET2osg2tLvzegRI6+NQJE
b0EpByTMypUDhCNKgg5aEDUVWcq4iucps/1e6/2vg2XVB7xdphT4/K44ZeBHdFuf
hGQvs8rkAPzpkpsEWKgpTR+hdhbMmNiL984Ywk98nNuzgfkgpcP57xawNwARAQAB
tCtQYXNzYm9sdCBEZWZhdWx0IEFkbWluIDxhZG1pbkBwYXNzYm9sdC5jb20+iQI9
BBMBCgAnBQJWNOqXAhsDBQkHhh+ABQsJCAcDBRUKCQgLBRYCAwEAAh4BAheAAAoJ
EFsbMy7QZCbTmr8QAN8bxdsTAmnp0SObQ+j6FRL/4rWoHrLPizJIccZhdchKTohG
UU8wYyQDhCj/tGo5F0Nw6m69ldv9GsIBKD3ggFIbBHvgcCvSC08T3tH8zh/2cOJP
q0rs6lLADQi3miTUJLdLgzfGFc5HG9BxIH4b53KCtOPavQCepA4K1kJErFGEGXiS
rVnKv1XrCt1anV5uhGe/k/H8YWBshfgnuhxicVO+er5scS6eRWD/83k6r7hSni2G
iw0kPwQtyi1fB8AyCIQ4faPXLcN/dkLgsv00YYKhuU8sInXMCrz+Iq2I8VMz1MDv
6jMKMVACNt/8HaK5+STGe7Z+LqwvUtvTQhO9I7rnpc8NOcGdhPya0ie2i5sRugQQ
GpgGsWnERK8kqtxFF23mfSw0pGsSiR2su/DgQLpSo2aK0Ukz3UK8jgw6adYq2gTP
K6zFyV1nubJtgzUF8+W3vDtLvPS0V5+kWTIntca//hEtd1i8bMMPD7eIv1Zm72tR
GIkhup5EhMWtekKuAH0YjalxDO1jnv+iz8nzgESHkomPnrpYDMzyaLJ0ePmSFxxH
rIF3/MJSQsLLS+6lZnNAzsG7N4SKcQHkw7dRf+PPJlvJRLg+qjiBY7+CN4/52+zP
m3WzYuFqXTqkTis6y8gBerEwBFTLg8XlEDhiUNfbqfjfnGTcsUzEBu7mdxNyuQIN
BFY06pcBEACd+wvbOKauI73BBd2yYC/qt0gaJYASKTdYNf8KIvbxIjofu3tPCq/J
hIRdOHKUQ24WOnXGfDiFyEPfX4HTV33oZQFpyOejRPxTiMon/E7xgXzushN+Xykr
JMBjXVGViGdFNKcUl0LwfihBlpatnN1H/44U2Q5yzb3w452Jp+cnKebFVobQJihY
WvTSeixgNA9TAvo3AiQirUERoFb5ajhEhQ5kOz7vP2sq9gTtFERydDm99JR5bgp6
CiL+dKhqS/QWLhgHQnywR180UIRyG33P3Ez5CtZE11+cfzJIhJfPE3hjfsozVUu6
qncWILPkGJww2anr4VhL1cl1UI3AlkiB34y9ceTXamC+vnIvzsciBaD7OCtrpjdy
T5qRYvnyD4dgnsSsugZ8hPKAIDb4HQ2+mTnwLb0oWTzO0BuC2Wpdvp2KeJ+4CUYe
pHqU0E/+AbmtMTrUUIYHCOJxrXAsRA0TDM46mxmJXJ3IjI7IjIPSz6VjwwPsSq0W
SmMFRcvLy8f2pTs/4dQWY8dru2JrmhhDcROti38odMXqAgQ03Z1hDkEx+i1bKJlb
VDtRVWqdbeY5GEnacQbh3/P9mHuzdxUzESnvZ+Hu+bACdNLrZzJej9mXGvZjOE9v
Tyvizxcdhtod+Q0OzGxIndXAGfEFUd1MqIkfPrvYzHpPvbhQpvpwMQARAQABiQIl
BBgBCgAPBQJWNOqXAhsMBQkHhh+AAAoJEFsbMy7QZCbTZA0P/A6NecGCyp1xgyf6
m0X0WdE9dnwUQWbmlmaDogi7WGv/aiaeHM42PYeCStU/qqjQ2j6IjIaVavPbnBcH
e2RaEK86K3Og5RDpcQhic9w1/NWH1csq7rhkBX1342eavg2wn07XPMUkYZYZw+kA
NWLDrfomo2UJSPTHvYhLsRBL9JLNmhv6IqSEiQaodHnMZYgkn9og61zsWqylfTh3
U8K9pxWTzUrjqIGgVIIMknvusZPgfKp1o0CbIqWaMtdVettvdLFrRxAuIomTVdsu
fPajkk+uAzVqSdrwPXE6xEyXQqidSYW39PNmfOyP/PkkFsW86SSl8bXq6FioyqvI
qKJY8/3tWviche4opi0FYvRvTpzciCHiC+WtDm8M3rEtgH+pT2qbrsA+GyqtuXNy
H2lDUMNtBhm4kaLtcfbx3r81J5GAFr+9IhErh15QodZvKjU4KK+d0vSVgiru4xTj
BJbxO2/ILQ0KprBGoiMTf38AVkuMWRkIr/wRJBOgO0vL0rQGm/mAxnC66tEUmoIb
M/+Z2yR4wpAatzY8AzXrk6vwtEwtBPUSHuyPwY4HB97ftQRS1Vs/41L9UXTWB5hl
dpjWjDJgJn4x7Nv9VHqJuGQX6WPUYQ94lBm4EH/OahytnF6FIKWS0LmG+GNlbh2o
/egdKpXSkvk7uOW9taOksqsB76FR
=lYGs
-----END PGP PUBLIC KEY BLOCK-----',
			'bits' => '4096',
			'uid' => 'Passbolt Default Admin <admin@passbolt.com>',
			'key_id' => 'D06426D3',
			'fingerprint' => '0C1D1761110D1E33C9006D1A5B1B332ED06426D3',
			'type' => 'RSA',
			'expires' => '2019-10-31 16:21:43',
			'key_created' => '2015-10-31 16:21:43',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '9be3c9bb-611b-4a71-ae8a-3a68aaa20b2b',
			'user_id' => 'be77c78c-b767-3290-ae92-d5e5c590fcf9',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi69+MBCAC01FHt73wQwudYIPpD6XUtJXOoRWIMnLYFiBy4Y4VSpzySrtr4
TDMmcJFclLHt8eDGPanxMrIZjqVyjy7xPXN5PUpwOZjuqjvBofiVikkDP/wHcyUC
IvAbRw7IQvGF9hAMKEbBL9Yi4+31ZoxNOYbHrRxhW2gzUIPCo5GbKw+aRKCpEd3E
Y/Pp0biqpnfinumBMSrSDFCHg4/xtxr0cmrQrZEmlBwUiN68SsCXtayyg4zkPKNh
5I9BSY3esIqkHedzbZw/uoWBe1a1XEUEDbtme/jxGbphs51eaYJ++y8hBdJXsQ2N
ovl/U6RVQZejtD5RSpeFBIGmn75ch4XPcneNABEBAAG0G1BpbmcgRnUgPHBpbmdA
cGFzc2JvbHQuY29tPokBNwQTAQoAIQUCWLr34wIbAwULCQgHAwUVCgkICwUWAgMB
AAIeAQIXgAAKCRCs5l4WutVKivLEB/4j7cJpZTjtjjUTUNPoqfPPII1JnXk1seMc
fuHE/NDuiHHNhd+7GdbnKJzD39yIAHbecLJgFaCJX0/BqSkWSutihllUi5iq5YyF
0voJeSJWittUOmqV9MmWTNppe8RpPYnoB5eYqz1AvP+0ZyHc3Xn9yHFWjRvmeASX
tO+badiCo7W2hjRrD8K8UxL6stUVAmtLUgTb81LD1E9NKDMKlPH+GGOXdWuaBGOx
5YPdaLaNoDYv679c9JSgPqQ9Y6KsgQOQ/H74B+cZyqmjzB1/uPfVElhyI2q9tz/g
AsVNNGIoe0VOx4cY4veegMleHBFlTGKhi0gMQZF1RwbGs1e5BSRLuQENBFi69+MB
CADWwFXTvotgQn18YVUj0D59i4jJxaI3r2wbEinsz0hzEvN3wjCyRVuuVS67LZuW
eX18hKgO74hAdiiFq1nKfgDsRSywyjcyIVLP97AvUQYFqDZ4aNNWQBrXEyTTPlqz
3ujbXVPJntAArrkpTdl8i+oVmAruz/cSqX2YRmXWm/GWNRU0M3ndrNT6qCi/+1yQ
L2sYeYKG89ujQRbX/H0xFmGDgHEuPgLrc9CrpbcF24FivLW/Sh3Ux6VkORKG9Cku
h878xX8+CMfRgg4rSdFAmnpUeb2n6w03EHJsQKUwlaxYmUZoOIeU95i7QTvHfLnx
8c5fniAEH2CEy+4hP9BHdcfbABEBAAGJAR8EGAEKAAkFAli69+MCGwwACgkQrOZe
FrrVSopDOAf/dbSB9A+o3JD2UEJmO2E8RuxXFIYSH5/veH0yLOLGSnfg3OHcwAaZ
EvxDmCIetr1EvZQoJV2w63l4szm554cIRN0E9quISvqgKMMaE82OUFAhoOBClQEp
46RxmUWP+FTiqIxUdIdVsmOJ8LRYk+NobG9JOFUMQtTxUSiE9AE1TWFUu2/ZdKA2
BAP4d5fxuGlCGwXxJg42p9cw5xCcC8GTmvqemO8iVlkLqRrihMjHYW3R9DBOVUAe
3ZdBR0McqR6ZhvSARWBQG80VjlvMt8/7SzDdeeXgBlCo0US81hAa+Yxmk8yiyEYC
cOv3QJcgi02rfOMbtFrfHqeEPITCru9nZg==
=Aszm
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '2046',
			'uid' => 'Ping Fu <ping@passbolt.com>',
			'key_id' => 'BAD54A8A',
			'fingerprint' => 'AABF40FA29BA54073E8BB956ACE65E16BAD54A8A',
			'type' => 'RSA',
			'expires' => null,
			'key_created' => '2017-03-04 17:22:43',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => 'be77c78c-b767-3290-ae92-d5e5c590fcf9',
			'modified_by' => 'be77c78c-b767-3290-ae92-d5e5c590fcf9'
		),
		array(
			'id' => '9fd0696f-f414-4aeb-a0e4-4b0b59533178',
			'user_id' => '1df25f7c-acf6-30fd-abb0-39e6a7462328',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
eUNE1fxScsE6UBlfdvKJapc=
=s/2R
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4096',
			'uid' => 'Sofia Kovalevskaya <sofia@passbolt.com>',
			'key_id' => '08D8B763',
			'fingerprint' => '252B91CB28A96C6D67E8FC139020576F08D8B763',
			'type' => 'RSA',
			'expires' => '2020-05-27 09:34:04',
			'key_created' => '2016-05-27 09:34:04',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '1df25f7c-acf6-30fd-abb0-39e6a7462328',
			'modified_by' => '1df25f7c-acf6-30fd-abb0-39e6a7462328'
		),
		array(
			'id' => 'a5bbbb42-8d00-4e3e-aad7-613a19b50c48',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
VX5VSIUYQFVe
=UUaB
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4096',
			'uid' => 'Hedy Lamarr <hedy@passbolt.com>',
			'key_id' => 'A812A61E',
			'fingerprint' => 'ED39FA1D15C0B2A81359A872928088DAA812A61E',
			'type' => 'RSA',
			'expires' => '2019-07-03 10:51:31',
			'key_created' => '2015-07-03 10:51:31',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => 'a6c0f32e-04d3-433b-a9a9-fd09c8045c71',
			'user_id' => 'bfb648f5-9f37-343b-a616-7f3704d13d84',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi6+KABCACr10aZS+MSkQAuyducjLJ5Pb9rShxlmIXClTYry2RaoeOe7Pp3
jNcaNLtXNUhxI56AhGSuiRvnKC+yKTYWm4DmOEtLD4o1hEJ1SZSOKx4QLqCEW7dz
gK6+S9fOuZTpQuo78P5rAjtXo7PvI5LfSsZDjZZtlzkaB1i50bpr8u2uzVOC/2O+
TPK04iY+/EXF5kOt6qCgUYJibk1hnzAxQUZ6ORawNzoPAI5OPZwnwXlq1BB1YDSt
5vWgHJjhq+7V086CW8csgWBZwfQHXLH/Wt19NgTkmAkn4Znz/CA7b9fQivxFNZuU
UeS4IV8u8hX59rx0Vlfk2/abaeF+Hbyp+1C5ABEBAAG0I1RoZWxtYSBFc3RyaW4g
PHRoZWxtYUBwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJYuvigAhsDBQsJCAcDBRUK
CQgLBRYCAwEAAh4BAheAAAoJEKcRoHMXh+VNqtYH/i+pb0FDVEtO01HbK05CMQtP
1boWBnO5vv50zLHmAbWRcUTZoQ7KR2lOAhWAH8mAaqL5TEr85haU0Qa9MMRfm8x3
p344cxJrZkViXBxqTCUSaHj53v2npDlkD6o41yHazzhIdZgWKWjM4zdvhNM7Swop
THswRjXp2S/QwzmiFRhwR6rgAEBAxrU4lMxVsCHiam/Hy+U0mJSK3jN0/D5FOcMU
laFaVSC23Gdh6K4ho1dmWhUczMp8avgaqP4M+rk6/8AxKrprdBykkHvvvKFIcWDp
zYdyYYzXq8w4evCRDNpvjEA6CQZIwYvtIsZ3aIFjbbN2UpQuDwJJ4uzAzhE73lK5
AQ0EWLr4oAEIALzPqR1QG576wp/P+uDvrJ0EXqGD4rEG+2F3xclfkWmpRkCKQBWl
Kc40j4J0aNnPbRwclIa19C6GUNBaTIHEQ73tYhdREwku+7h5YO64XWrPH6bHLDCK
+dBpwf42bcCTQzfL5P+M35UGDai32f361xzOwbsXEJDBSkN+5MJlumN/LyPN/Vb0
xAMpx1uBb3qVgIUfq/UacjTJZjc8GjpqNm4vRw6M4/BVoBKPzyfUB5sxMkQum7yr
I+VO0gP+Md6FO5lp4tsXpSR0RSCd1roHdH7+sjy8OG/xXPG+05xitO2Ji3eumBM0
vhK4iynQCU1FHkhC1rzVVICr7UkXjvdJtsUAEQEAAYkBHwQYAQoACQUCWLr4oAIb
DAAKCRCnEaBzF4flTVM/B/9DbHQuNH7t+dKurPFHoJNTXIieMKzGSeoeRIr1R8ap
P/G6+xT4pV6UobPBH70SxzT8mMO591hnuRJ7qRA1Gpmq3EV+hSHD7SmczgjLbC2m
KgvL0uHHtV/a8qDh3CLDrW2/jsIHKGfDHkUP71+bC8wJ6mmRysP7LLJRcy8mtQuS
ZDFzwL3PcSJHaBjjxsk59FCv7zEyVrOSNwq6NOuYNLf6VDp6LSGsGPm4Bjb0odcp
lw8+0yy1CqS6XDpWdxSpAadsy3Fwv2pWZhHcaEw/HK4ppQUy3Rb5CYFoDnxAyZAL
wlPmpnNFp1iLbkFM2+/gVSR+9Ty2DNFPgRAov58Cb+oQ
=eX9A
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '2046',
			'uid' => 'Thelma Estrin <thelma@passbolt.com>',
			'key_id' => '1787E54D',
			'fingerprint' => '522E59EAC81C5D8470C45077A711A0731787E54D',
			'type' => 'RSA',
			'expires' => null,
			'key_created' => '2017-03-04 17:25:52',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => 'bfb648f5-9f37-343b-a616-7f3704d13d84',
			'modified_by' => 'bfb648f5-9f37-343b-a616-7f3704d13d84'
		),
		array(
			'id' => 'b2c47ebe-b700-4cb7-aa6d-f4211f5e4c44',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFdFp7MBCADJIBQnJRuqNHJZTsFTK8byR7WJG7EpEHL+lS3qeOLoALYB+y8N
fYbNDhGvpCWNgOatzGX0+PyjhZfHfGwgM/yGeULmWKdfpaWIEcmgG2YaKucSvBll
urDnA8mKlMZ8hXAZTbIYbr+IOl084824A0O3PoOoTYYPUk5DPtlbCP4e5JUF5OKb
2VCjHxJbY+zstpOHipqmJJH5CejyZpmP4j0IYPDtUS2SeqdmFcYs0Nv7al3+Sc5s
z4vbc/Doiusyi00BWYXkI0yX3DQGz06FeFAgaQjIdChu207JF2UY+rylPTnMi1/Y
x+WKvP8Eidtb0+brOQPebl+oDq7c9SgXKWkfABEBAAG0JklyZW5lIEdyZWlmIDxp
cmVuZS5ncmVpZkBwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJXRaezAhsDBQsJCAcD
BRUKCQgLBRYCAwEAAh4BAheAAAoJEFIHxWR0/o0xagYIAMYenAKf5GzfYYeWVtQ5
tWmaXpXE5tO7Tvks6Q1TNnf3ZpNUfnQfa7U8zMpuzI0tg/no5jOSk19j9480jsR4
6KMexj+JYiTMqXZB4/etuiEmxcJ2AnIF1/UVY+fvD6Csf9NQD8N1P2Ph3N1MRukC
5YwaSsIIrj2qXE9n/N6TjvFmGIc2iyLWAG05/wfmydeA9ipHmt6luxHBEd346gwe
sTalLlfr/LA6Iyl/WtY3a2v/zjr0sA55kfICoOkXsV3dXh0X4J+jyAKyUFZmaLk6
aw8N+ak59QcjCH2/i/DJOJ8Kt3FsjgEnNw7+8NSL1f21UPhfM71pnXC+aOz4UAuR
PRq5AQ0EV0WnswEIAKp2ZNBEqWlCVnxdb/cfsUOiLzsSyjoRCxeZXs6C3PS4ZmIV
n07v3ij14xCFjTAMQChmTDfquo5HV4sSd6mtUcBOXx+E9D4rZQ4oweFSa5zF1xZ4
rXGNU00OT59UOEkSvGHCsRGGPmtdSIX59131RCbITcHusF51vIq4duRR351c9tjH
BWWRmeZQHmV3Ysmh+GSEYR2DK+1YtlptxGvZE7UbmsnV0NAGqmRmIkOvz1ycfLcZ
27O272jEBBUsU6CgulTbPscJtkAR2eyeStLJQuK+CUp/vGxIOWMiYO82iTyKs7Lm
59X4gVq5A8J2QEY1g9e9Ywhy7MixJm2p05awMXEAEQEAAYkBHwQYAQoACQUCV0Wn
swIbDAAKCRBSB8VkdP6NMapPB/9/SdVnB9tk3Paw5cQAmRxjpNFkj4KH3EsARO5/
st2+X9bUOsdvRjofOfgp3IL/aN5pPciW/oJ4bQVa2612dFZwPfPEQwOhXE7Ebumx
jzy+4Uu3OxgxMg/K1Ju6liP5+46FVeE2ylQ1nqS2RrD4csnx5Uk5BhWlF7umI6Y0
e6SstTgpNb71+B2DIFg7MBYmfInnhWBuVlzauEzQksVC6mpHsoj6S9KxvblsJSmE
I2MAaVoAIcU+1dnX2PJ9zRnovR6U9wugJwOOMQfzOnMfw5Kyiy+4Plc5hbT7k+cn
x50EPBCFlm9nP0dVemvD64PDjbWxVxY9w1gDgeYfdaJMRQKw
=T+AQ
-----END PGP PUBLIC KEY BLOCK-----',
			'bits' => '2048',
			'uid' => 'Irene Greif <irene.greif@passbolt.com>',
			'key_id' => '74FE8D31',
			'fingerprint' => 'FDC7DF9AB0C61C33B2D871C25207C56474FE8D31',
			'type' => 'RSA',
			'expires' => null,
			'key_created' => '2016-05-25 13:25:07',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => 'b922e45b-d96a-4422-a444-86637e9644c0',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
VhJINUw1ojbw3d8=
=5xjf
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4096',
			'uid' => 'Edith Clarke <edith@passbolt.com>',
			'key_id' => '9E67396C',
			'fingerprint' => 'D5FDE007B7B4B9816ECE25F61D67BAA69E67396C',
			'type' => 'RSA',
			'expires' => '2019-07-03 10:47:27',
			'key_created' => '2015-07-03 10:47:27',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c'
		),
		array(
			'id' => 'cae22c8a-785d-4927-a70a-9d762a03f97f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
JqiVwWjaIO3D
=7Goz
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4094',
			'uid' => 'Lynn Jolitz <lynn@passbolt.com>',
			'key_id' => '8EA58CFC',
			'fingerprint' => 'B5D364ECDAB5B3F79C6879B85F64D13E8EA58CFC',
			'type' => 'RSA',
			'expires' => '2019-07-03 10:56:50',
			'key_created' => '2015-07-03 10:56:50',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'edb942e9-6705-4537-a809-1aef4fdeda75',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFdFk8gBCADsd3uX1zydSNTc3V1G74qXiMTrDi30X4O5SBaFM/UUG7mQcu34
HHqWt6I1YeH87Z7Dxq3hetvFbaWR8ZfvlPAruXIzFHzJEZzlCLU5YnRBFM5gvGkJ
VbeKSKTOr+8oVeXnio+Z44T+UtBxXS+2G7wLYYTNd2UaM2WsiJaATeFiD9jZq22h
TakgoOWKIpUrg4eKD7K8Iu+qm2l4btm7QLRyCxhP4x+d1MtwcXArmMXenUvnr/2a
6MDwfddmIcf23I216sJmNjEDWgWHjHoQibs3tx2pBKzY/GI0y0NzhPZCvTdbNqTZ
XAsOfDHm9OuziuNRCgIrx6WtekGB+iZkon6bABEBAAG0IE5hbmN5IE5hbmN5IDxu
YW5jeUBwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJXRZPIAhsDBQsJCAcDBRUKCQgL
BRYCAwEAAh4BAheAAAoJEA7FR62ef6FSlucIAJzL2Jzzka2FWpSPpC0ijp2uhugZ
GkUpbFqBTx9twO992/BvAJeyU6Kfcss0obqNZP1c5sLl2RMvc35WtpBvjkedSUEQ
poET0FGhNUi2ZyqiU8bdbbm8EnDuLv0GG06x2ZSuQ4AXE+JKn8H4saFnPmX2h8a+
YfhKbtS01msQmMviVaF1ZpxTugTqgFYU6wp39O7IR6DMmeKgSwElW8Gd/gJMWx8H
7pizEq5SQCFzR0soBpIOHg2QMDAIWS91zAHdQQoiaI5wAlAm4IeIIY90KaurRQC0
sTziZcdrp0m65FGxZ6790nGhhqLc7u8N69CcxKNkipong4yKkFqyIErFv7e5AQ0E
V0WTyAEIANdrlla22hZ1rg8wFPgeXKJ06JJRS8SRt9WUXLAuIuO1Xqi5uRKXpril
gcC6t6QuipRg0ypwICcBEI2Iem9hgksKG82GVz0LSWSENAF35EG0eE8fFtssBiop
avvLKjiJa+tm4hqDYPHP1SvCf3Wl1PKNTjwuXwH2gX4bOpGF86rWUIriIgmfVVsH
Fy/A0pQoynuYwKr+C+i4CtmZFl0F/WTahVhVTi+KqxMU4z8a90b4gG52ZWGQ0LAS
OahnyHXnicPQQBm80KdC1Exw6Z9QYMXj+ZMWSUvmKb/58S0PBXRapN2KDyhHovvd
ZGHazOFTLnwZvyCmsReb8SfpyE0dtC0AEQEAAYkBHwQYAQoACQUCV0WTyAIbDAAK
CRAOxUetnn+hUqjUB/9EqjSSK789BAFVnY5fymq/vMnCBkE4U/wEVp3/4/e7c6Vf
dyLmT0ULORfOVOyRPZCaohs3+2mUZJcySRHrK0SCI33H7dWXTAj8wYTE+neznZrW
hI/7COYcdEzRdYmFEe1qRJvmXWiSW6C6TjARZTdF7ScBzGhzRUmcyr7h9KcqJ2N/
bSYDlRHigAbDq57S1aa+cN92RHyAvQkJ8S1TBF3/uqSFz41hY4GekegcEg9h5ATl
X9fv56KCnrIdY4NZx8iYqEmjMOZ/FRz9fpwxWapqn+V7eQl7mpSybNEt3gevuTmc
/RFZsK5btBeTdj8jzgg83/jvsasJnTLgAoOdSv2j
=1k/p
-----END PGP PUBLIC KEY BLOCK-----',
			'bits' => '2048',
			'uid' => 'Nancy Nancy <nancy@passbolt.com>',
			'key_id' => '9E7FA152',
			'fingerprint' => '459B102D43F683E7EFC523610EC547AD9E7FA152',
			'type' => 'RSA',
			'expires' => null,
			'key_created' => '2016-05-25 12:00:08',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => '27102e91-6880-3312-a4c5-db00c228820e'
		),
		array(
			'id' => 'eed6db71-1e51-43c6-af1c-b47de7416aa4',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
a09IIZln6LLZOig=
=jibv
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4095',
			'uid' => 'Grace Hopper <grace@passbolt.com>',
			'key_id' => '9BC6A569',
			'fingerprint' => '63452C7A0AE6FAE8C8C309640BD9E2409BC6A569',
			'type' => 'RSA',
			'expires' => '2019-07-03 10:49:21',
			'key_created' => '2015-07-03 10:49:21',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03'
		),
		array(
			'id' => 'efd4a767-3a1f-49cf-a243-53e665ca1ef4',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
b7c81OKzWqN3kkNCTtycnemmZRE=
=f88E
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4095',
			'uid' => 'Kathleen Antonelli <kathleen@passbolt.com>',
			'key_id' => 'A73AE279',
			'fingerprint' => '14D07AFFDE916BC904F17AFB4D203642A73AE279',
			'type' => 'RSA',
			'expires' => '2019-07-03 10:55:55',
			'key_created' => '2015-07-03 10:55:55',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e'
		),
		array(
			'id' => 'f99585cc-cb39-456d-aee8-0f48299aa263',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
t8bJrYvwZzYi
=Wbeg
-----END PGP PUBLIC KEY BLOCK-----',
			'bits' => '4096',
			'uid' => 'Carol Shaw <carol@passbolt.com>',
			'key_id' => '82945D3E',
			'fingerprint' => '57DE7D79ABE733A235EB1F84CDF8FC8682945D3E',
			'type' => 'RSA',
			'expires' => '2019-07-02 11:25:12',
			'key_created' => '2015-07-02 11:25:12',
			'deleted' => 0,
			'created' => '2017-03-04 17:46:28',
			'modified' => '2017-03-04 17:46:28',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05'
		),
	);

}
