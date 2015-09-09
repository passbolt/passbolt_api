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
			'id' => '55cdda4c-0a98-44b0-99c0-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAwDhOjWSggglczce/pnX/d7vqv+Sleu0F+vllxxakIXUr
vQluHODO6PqoU6R4zyTJBydAuCunzCZvLm/5SGeAawXUOZlwPjBuQurw5qDXj7PR
P2Q8GFrzCNFRoSVHdg1t/CTFpDSDG/tBu0negQyeK6Ry8MuM87W7ZFRhbTdoVxVb
3G4ydzY8IwC9qbaPBoa26xtYG/D5tNky0al6W2zaz3ds6u2RM148bXhLpjM8zNjk
x0Nkeo+xmHgiNw8c+fLq259BBdxEMpyHRiSh50LH79KD3i5QrGhm/ituR4HncX4J
ARE5nelR6931d30iukWOSJ7cY9hmnU4lBt9HJLZw5XnPomar6XnPlWviKnNjxwOQ
yo50kmtE9HSqmq3qrstUEsbyxY3akiW70rGM0A4wcmXExiECIlwOeNDL6DAZse21
GFauVqeiAO+kh7tKPCYkcM8dGpbGB15qTr/oG62FLzzUigmD0WPHCfF35Nw2bAuH
z+E4Jnn/iOb7RzSGYSV+mvhf96lcSeaB1UEf6cTV5BNvzxfXCnILgilNkEwVz+NR
jXEJ4Sg+TsGvGB+0Nt6L59vF/iEgKCI3tK90UCCKHpBgHyI412pgUZnq2e31L7ej
M9qQHwQOCnvhkeyH9gE55XKpep+/Xdj5sXwHXO5LuhxORZdHkl1feiM6abhLBI3S
PgFnkqxtpozmtl6lnifOKBjYHQnKX8vPT03jMJlUMTNYNkH5Is/li8loEGrh5W8g
ELNpup8JB85Doq/IRz77
=RflI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4c-0b20-4683-808b-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAiDhG5fqbZXGcAeeuKsRabZowrlSaBYL0GU8Ci2TMN0Fn
ntoJrOhQM3dbtAHqTZAJeKTHiwp0EsKig98xnb38Nap/Ilbgt3/IdY9D83g1FBKe
tkknr66oG0P5nfRp6FepA3WPhn70M4USdhMk/X8X4JQuzuGkVQzr99aax/1qZzVI
QrvoyWnhdxODwhaiLTn9eKdbcCKlEpNDTXs19DkeQVnqQsD4cv0fiSS0kYUGWxpK
aiXogucJ7ptV1KqSLjMlWT10bf5UY3yi7Z8LpGnXuEc5ayKoTc0xltrEd1h+Okj1
PU4VZWIovh73zHlMJNmJe0tDTV9REwoUPaUT5AK/zAyoXOd1jUFpcfpTdhp7DiVV
pu06IgxQ8Bql9yydt7t1jRZt1BGkYvjAo4tRrREgfNXeIi5iFqNO4F2My2dVwiMD
un3N0O/bP79QNRDUer5gO+6hP6eY7fMFEPVw2Kso8N5+p6lauzbS+4FmvLhFmbFL
XssV8y/2FuNUG3BIrP+Da3uq8De95TMlqPfxSIqMsSoto/mnW0fO1EknBPiaYyF5
uVwaFx637DoOkNy3O3bDuKfU3KpbLUvs6EC0g9ekD/6ZIEP7juVUQQzflClqItrK
YFhw/Vt7+Nchb1L2m/w86Olmu5OHlbZkpXIvTOVxZkjaaN2T//znZEA0lvyUxv3S
PgHfzLbbiRHJjwSiN/izmYyWoi0WgB7bQDla3PRsr/i9Qd6eLIty07NCRDPbQzQP
wguEOLvMPO0OVWj8MeBL
=zLyG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4c-13a0-417e-86a8-0e13dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9HEBzpRBqc2TCuOr0DXU0LsbUa10kD/61JR64e1kWtAc7
frQtew/dBQEUQHNpasqMTTVRzO/whuUBU8g11wjeqAWLjZPgysC9rldmKm3RdxHx
Mu9RxqfE4+keuF5ZGYGHPJZZv+fk35ofWQZt5K8RtQ7LLMMi9vwoQmDOyw8sq++S
2bS2x2vqOHAe/l1/hOUHFmlBvMkH/YBtl8JnzBDwoTbEVZoOaX4SyhImfGInvV8Y
0LDD9WwhChqBO6kBOfQ1qM+fKTDcoCOaawJ7fW5dOk8fsT0d+bqaEvatEjedWd8q
NIkfDNcoC/h6p3r8WqmQc5SuwRASEJ4Fxo4y7hEj371/X8vi//9gCCVM7B2oMoFe
ZIzphZpULX8GfrAv/6/wiNRFu4bnzTFseoiMoYPV/gLz5xCACguorNh3PFpqJfrR
yYn+cD9c5/d9OUetLjWbzXE5+W1qJYRONTN8aJrEOhV8lEs4GYcpv5KOG99YR3KC
OHoe+OtdsVClBQyCElmU13wOKAFw0fuAbnT2tsD2GeusZu2Cop2iqOunlfRD1XFn
wVoaQ0JGwQ7jCMeHOTSUOUnne8NFURQ6RakEZ+ldRpPaemdekSisXL3Z03MHdN96
oarYbqtjsJkOAr7xAkIl7FFlybakXeaAXi6tI9eA5W5gh6Bh3LhztxNPovi8MgPS
QwGBTq3HB38A5LcrBpdXn4LXpuMNU65ipO2ghSLfVxHOkjN6mIOPmkbsjTgBnw9N
IL3Bc3LiQnZ13EwYvFC4OUM6eZc=
=Th/T
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4c-4b34-4aff-adbb-0e13dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAhpJK+vLzQguq7rke+rXoEmE546+3lHDwBbmAM4FkDyXO
6AkSyPUEUDkKPeHstNh/u82cKeyDVxAGqWZmGAXpbTApVDOjbeY/ZzCqXJBpZQym
MVm85Wu8/BNDUFtzYS+Re7vwtINSImtEezXD3zvAH9882LAQX3v0EeOeT1V8eIPX
1Z7ioRZ4foms3Vyie9cO8MkDlOQZrDZj4YuO4v1AetV9Hdy+345TMT1doXlUFlMc
Qt6Y2mkFrjsl4JGpdE2K9ssM3GIY7isBuOfi2UgUuesJS1pddlgUmNUW1HlsNz3q
OrKdJFEPFocc39engFp23rynVTQafvhR6Ncx2674eHrbC3E1IpK00qpKIJT3IJEE
ftigjJZ5OG7soEtxRPSm7XOeXqH+LcK7YGXGloYaxbpj7rBrMXdqvJzZkGHfPAz3
TXnxSV6VCgv4FG/hMLKOfxTsYmUNAzDeh4xrIPVHd7MyAefqK/FmqIA1IfZ4CxG8
WXlIExgMDMsFkokqdiRzo+PcCKpkSMN2ahdNgNWff2YlJXCTfDjkHZpZUnMngo4T
+lKeH4hPU51ck/iOnOCqR7gV5my0e5Bir3gwRpP2kNI5rugeTmTzjpWX0emp8HHF
5dY0syFwCaJtrDDM+haS46Gr9aUgL6Sq9x8ArbRobJENLaOmP/3HW6L2NC/fBE7S
PgEwOyOYhRbC+2+1/b42V67o6Bho+DWzAZEE0R3ZchShs2YC7M+CEx3I6rROQ3EA
sHqg7miZbvrimO6j6Ejv
=51KT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4c-9674-49c1-8e0a-0e13dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/6Av8zQ8ARYobvHa2RCe9nUCLQHJ4jQNR5Iqa3NS2yF9ll
69KCDKu/aIsN8pogERXKeXzJboZErjIpAQQkMNG+PTlistE3Z8hErj7eLpooHolt
Dkk94VAY74C0qwMBhBJFppdHfBVH0PWqbjcGthNMaiZforpvTxWR85xdLFeOSgca
L8aE82/+H8KIo7bqnuANGNPzASL8+Ve/LyzDHWR9gQbCpKER8dlfKbaPK3r/M2mY
nQzoWuZ+69LhW8imRh8PXQGLeB8vlEmVTqCgKeEpXZAktmZovq0cYfxCfvpT+xhS
AjR37vpYy7cJL8J2GuAHLcUj1pAZVNlpwiUBU0bzOmYL3wylZDPnyRjKW1pPysHU
2rWJ6KwnShePbpXUJv3aFP/6RD5NbRPsQwxkG/fxIKqyl7En3OAlA9iboYlwP05V
/fRyKw4rrrrTViLO2IRVMGyFc4tMNsMLwkM8/Xi4bwD62OzfjhnrfE27sUQUDbbV
MGN/w7/N5MBVCo2GfV+qlHeoyToWgug4w3OqxKCmoSjP4u8H4Lc1sg6T94O26SNa
RSvEP5tIt6vgujFCL0+NRsvvEqjLHwNnnFzOB+NQkkXnqlTihwVQzNKZ7x1dOr/A
cahiRgItD2T2kktnFhYpK09U0MXojZocgarxQxrlRh8nB+jcFUOEcC93GEW/7mLS
PgGCOuLrAoPDJi4kmmnvSWpZkWO3byeEQDZTsdD0JKPz/VtEMd2p+ujlusCwMiTn
RVfb3bKxb0Cv1EN+B4wE
=BnSG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4c-9c90-44d4-9c7f-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//SpRo1hclGZLFH7uEy6rRuiNV27S1uC+eDbn6QL6dgjC5
trs1B+bB0P6/biJXYlyTZE1Jd2u8Jj2X8xCSVMNTBoeUAcmseCz7eV7QlRY/YkTk
GCmib2qrXYYofzr+K0RudVOYvmGATijHj4WzRu2oPo7hEDX1nHYDdrsxUkJJlwje
y3Iof8SxonhMuxfuf663bryJogb2RA2L2Cz1BIC24z5QgYogrX9GHfpU4GS6cGng
8dHYiWEcn/YPaFs7ZXXLSK8nD1Q0AL1ha3B71QK+uFFV18XTK0eYQ5bcdp2Cqf0p
4InYAxtmqRSzMKwFrxNZkdQfJQqb0RY26rHChacvFlFFtL4P8wndeKyXYHlSf94+
LRYXqSuo0vGLODxG68Z/WhoikDLWS+338kP0Akyg5Y8vOFKRjo9VN/R3aHYOMqbT
vyMMS851Z4U0i9eNPrZ39khA9r2XUc7ZzX69LzuWCe2uZd9iJxD/D7ooIw3Z+VZi
smRfW3cgAeXvWx8u6BR0MpstSGNLrn0FfRCTnDNcaej1N+58xK63dk4QBokCs+zM
JzvLjRXzR9KKTtLKBAuUWlA+HpgbWNsMCZzGTigjULkc9Cy4oX5tVKzDy3HQxlhC
CjVrEKpCG6tnMzVe4/+fMuM9lbgcyc3OR34sy8HkJhFDhOxqaes61HxkyYGFdOjS
QQFA/X/bHciiU58lNQJGekLsqACHS8v6anedVz1PwFuPPNYOSGunMy+C6DS6Q5NJ
6a5Dr6shi3OhsEsgrOr88wDf
=n0z8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4c-be80-469e-b276-0e13dbeb2d5e',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/6AjFSWrc+a28nYxZA924UsOs1qTJfM8yY+A/L319PUMLZ
ke7EECJHFNgboArXokL6BrZ/3hJD1zbMK+kn08bdpVVqpkSnvFO/S2FoGQCKCGhO
MUMKP4XA8oj52F3czJFQxaxktbvxDZcBuUUTVThgebu/8+kxG213BBcRRkbhuo/q
SaJ+iIAfSGB+Xrezp+rxlEyjV2HthLTqeGGpVTcv+4KczQ7a8CFwH3w6e6v4PRNy
CUiGfmWq+8eXGrqVeB+4FdWxvHbn+0qQMBQ1DyL6fSvjMvdEMstylWkbqJe60tvq
5G57ot3ZJO2flcUKfCAh0XWfKhJk8X1MTXpphAwyLXNYauIUoNlDxG+aDwh1sTij
e18wVawauVHv3rP9GFEjPOtHxtZb/CG38yJwMlnZaeBfZGaBEKTGA2Hw928KciTV
L8KyWMmO3k/OYD/RHDZV5KmyIZkk2cChZTRyQK/kYUsgV0p4BM2k4UXDVwFrhZSk
TIePUDU/x1mlm/gmFyV+GhElS14fBSXy3mpWAx4oB3fwDdBvsGLKXvke+KOULHUH
pZfrEtvqQFGlvdeUCbo+779h9kIkqcxin0G0A0qu71LHfcO/Q0+zY0AmqoV8VESH
/qpXhdFEMV7tI2D+EBgLcLNq0ub5Qwf0hG2j2tWkHpjeVx8YX5F53fvpmHOwwwzS
PgEhi4GjHVYDEnBp6iv7all2SBFbzJpZI9oVLO1L6U57tS4XR5PoAWithpA2T05E
F0q+MLlTK0QgtljchsRa
=eCjP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4c-cc40-49e5-8121-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAr7K8ailrfjt+r9yI+rB9vo1kf5OH9yp4hX0PldbFTDvs
fl1KuNHEfMD4OkyIdQx0gaU+fN+ububf14BtR2ZjJ2HK0ncuPAMvQVspWy2e/IKl
tSnAKxTYAQnBmDicy4bqMKj2Gq2VjDhF/d1M5tFejaWeSUXW3q7hpZRtzwU4TIwz
JrfhLC5Gb4PG1CkXdzYmExqzrB8tUnhGf3n5ouO61/o1ZJRSer9g43ROeBfIHSZD
lGKy7h7CUE2oSm7EZzaBPmdbXOivMvANeR1ifcUVswItdFjQonY5gTKYWdrnJXLr
2VIPvtiHXwI94dXTcLGwHkJe1B/hR5A38T9nuBs4f/s267gqnpxeQvejNtALUg76
LBTjVt6JApd/D/j+ziB5w3fjPSGj4zu6XwbDgRPhEdAn8GMixbrIkSPOcJWGfasJ
saT8MD+LXVTULA9qQAGctXWXjrVrNO1837HU8FoYLMEnMfwasxqE0YeF92fvr1QC
HB2Big86s7kWMtbQPUlJe88jscnvtojr77IZXFTbzphEQQCJ+s+OvFuYZ0N22fx1
QyeMw5CmZ9qIEnBBBrlEOy6xOwJ46MRP6f8BY8pRRiiHEnzPsEvJok3s9Lz1alru
AAX18BDFs5x0qWE0dRY6GsqE2d1rXNyrFNwbI3bVXPu7bOavXHbQC9M1qCPrGi7S
QQGg7vT+MSruZtLm8td64Pcl16/3o2pOFtdSU8F1eNH61Vvr6jSTaz38Idr+aPOT
jAQ3mUTzOS6m1aN+x+IoDYFO
=uN72
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4c-ee7c-475c-9403-0e13dbeb2d5e',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '408bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9FoUs9Ys0N2Pv76d6Edu5ADUkHPBrCTlHIle6LsCiTj9e
JMMjyGPoom6YE81tzRtmz2dxDECpInRtMpAj74tTQ9BhX6MYeLjZlPtn1XvehzNs
Ja6Znv6nU2RwVRmw+cWGU7uM0xEiKRzHKSg1zT00YYUFPxGBDhzadTq5JG+78XPe
KgimimgJ0aehTRlwfUeIyUTtZwNPD4SIWVI+t+WWHfbfoxTVEZJyjj+Dz7v6LDwl
xkm257hhLGerKzG0GlruNQidplXVtvshMljcwRh10/0/AFSggPIUNWg6fslXVvB8
LcQlTR1CDqTUdnmcQNlnL5yhUD19Lqmo6s+RLU2WAx8ZuWiW38XpYnwWmNbtPLy7
yUDC+fEVqSkB6SSXPes9K4gDsXBEM5bNxMvoG48awQ9fKtMz6Dww7VQpHhKxzTZp
P25kxXNVB2p89/agS38bw3p5ccKijMpAdOTj9IC+FhZ5SahdVJtmKYiKEsSgNI8V
ei5pH8IpMHb9m6TkrymzjXHZpHq4Gpzqe3OOa6xpxC9PZcOj6UCBW9jZFdqeC157
Pgqzi92FhC1yNdYcZlR+9EfSwo9hxhbHjSpBLWGAs4x0zFLPy19rND9WXkH0j77I
r+pRXVGj9WbPQKefFOlC9oYtL4C90FQQ47cE5YP1qph6zXB3GYojzzdOVDUv1THS
QQGsSqW7ZnBCJE0Ip1v5EQI2abbTOQQGd+Cab5Xp9YUpBeL0GsMSYxOwzMsFgkAQ
x64s7/p38kUZ88Ncpi7e+feU
=udUI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-088c-4781-ac3f-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAtvpPheUjg4OY345ZU+jOp5GKKDevVfLflQ8iWjaSEj0B
dyx1qTSiMfmuCeIspTFMQFtTk4co4mu5dDSxO0Wb7phet7/YJKnTAJM5lRYIiUSr
7jb4+xBfryd1tJsfVvC315r7aej0Eo9KddCjUQfhGNT4Va/QE9E0KenmC1G+QHeX
Q3US3QgnJDJizoUDlbpx+ZgVc75UX+E/GVLjMsGYB08SDLclW+hLTQ2wMY2SCU70
1kbbytMGLWCXBawCT4RmkvfetXLkNlq9paBWMliXcYM99RT25GCrnG2XT+kX2eco
DyF5TvKNans+Co+/MH/rVfBTKU5G1nV7aHEm72GwRt9n+DM3UVjjPFoSK0UoWYA6
yxtndR2re97OzKCiA76nvGhHFAIAhkLlnFYzldyLl+kL4xnbmHJStL1RXaXcVidE
sgb6CanJBXQmRe7Yj5ninPx1mHti88TzcyUfzfCwsmfo9+cCjKBSSKaqLcnJeg/W
ezMFCtRAkY9vwm/shEBqD1CpSwgouCM+ixo+WMrdZAbujU5KYLkXfZGyi6XiaVlu
FRVO4HFr4zoB5TzLbiJuQl7AnOvaY6NNvViLeestWHyERStPE1RxxwyMGamB+arP
SF1Iu2ntibw4VT6bqUBkNczx+U+t9NJaExCWu2c3yGMCxtGOgDjggq4bevCYWpPS
QwG69b8B3ee/K04c394mJ/WDCM7tVEVKUkfDIX7NSb5xNDQrtpiq/GbS0ta00bKX
LfFI2w7nHBD4XQZAVSsBuxmOiCI=
=Y9YB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-0cdc-47fe-a8d3-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Xot8/6jXdWemyJj63Y4juY3e3dLK+Wen8t6xMBQDvQ44
3gobKx73CHE1NZlWcSLjRhahbYDXG1r28V28oBYLGLKAqodGrgCJ/SS4EKKFDAHO
VxeUMr0AveCUOw0b3LEtaZX+59VvK5X2/2x+twLsushWx1EIMyByrPF+JtcaaWtf
3Agf0zO7S3seAtwtzpBz5dnp39FqD6DLBN7cMPtZdQGs7J8wHnZmSnBVmMrHup9D
xJw9yjmrAx5FRlYD3MYzaQ52yzCzNWwt6MDfihvH8nULJNSouaSEUDcbMnrFuSw5
6OjVPygSyAst5fBUDn/GBdK4JTNKByGEZtP1KK0DRyf+9BOEtardXU5aiVmAyPaI
KRDsnPyE1SVc29O6QettSb+e2prqDBjONWwCasP/iNmmJ9+NOdh/DZ8eFyaulfDP
arqD7/k1rFILlj8RPWs5vaaEv0BYWt32d92lgvtsmFwunoIXK/uexb0pf5I8aRR4
C1s2tgNdEvq4QjD2o3oFHAre89MFQxtu6808NSCpufzHjyb2NCtau2Rtbcj0YmLA
+ZJ8WONzVwgMlK0B43ZIQCWA/rvIBWBJcsiy56hhabpw0XSgo/RhkdIOaoOjLMPC
LoKLzGqFqTXWu31ALAtzf2nSkWonXhNS21SX8HsbnWNJIP3QLjCANCWLxagRBnjS
QAEkCS2J0pC7LUnhs9WozEE1uDTlEj00haanFNaUptPxuDG3mCEgh9Ehc18AQR6O
w3hs5+V2H7nJold7PksVoBY=
=//jc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-2bbc-430f-b606-0e13dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf/bmYDO9YhAVeaf/vzTn6JyYTXGWiKvMkYsZGPWoG5yU/y
SfCI9Fl0qIDi5TUj1TjlHHGZ3AgJYZ5sVNUaBrGJX4663T0ykTPByYdzVF+1PiZe
32xFEq3xnVFtCGNQZBtICY8uRdM1kQ0jrRc8D55W/PavOQe+zmXb3Pte6DHXIwm4
2p6N/1tuUV69XAj9fNzVk5mg/ljCzlFsTwax8/1CURFkjNNLunzMVGhrMgWx0Yhm
IcDD3s4tCBaSLHRuFPW/VmRYSTEfIfSyzSpibw0dDpCA8za7I0g9MbsScmkIZd0r
Gqo2STnUTLmIl6VkigVU9C1FHGZRA1041OwTsO3t89JAAZVy7dIW/ETjnkGr2hkV
63+6Ciy49qTbR2UiVsaDd259aGSSjcmEcM4ThieHqxQClgxSsvxEnlcw1daJ7WXE
DQ==
=e/ex
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-3ae4-4380-ac3d-0e13dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//Svk+h8XoRyMz5GnONdrbzRbBQyni0BJQ803IE1pKSsDL
PbB0lwyKq5DWaQQmfi54UDoz7FuOJMFnPNrtgq3QCGhfzKiRzznnGL0n4OtKBuK4
LoPnHWKlq2J+qZQdNbUI/lyaNO/pgjX+dAMrUe3ZkvtSFsM539b4IZ7Pgc3YHBQM
StsQfxNJMg2EtwzPKqj4pi5dbw2kpj/O1cRaYNTGT8DEM8VeVIQ5Uh4alaTYfOr4
eOLI3w5U4+KJnd0kk1K04ukusZZFkgiviA3bjyN9JsaxL3H60I2md6Rl8JD3on2C
sbWuy37SgEl9Bzr+fykWr7TTLPz8H+32CsQjk/if8yzxpwG9q15dTAkHFwx9ltNq
jTiguL4dwUtGjjCHyPIJl3n+jDVXichcYQ5QItO48xuGia9XZT2FUS0INIZ24p6u
09fK+P6B3zyAnDh1CPaqsKhjAHv54ntUF/n/oLahX2pRqEImAovjZllPSWE0pucI
80/5q/VpcevKc2kT+x/E3OmCqwZ2189hLApe98mQuJh14R3xqfmbboegI5pg2yfw
ObLTCe66T3RcmmfQxu2pQHVPveFEQ1F3zZwKknG1CQr8XdEgksYnjMf3e9Qisuob
CUuJpbkq3iY+tBxb7qD6yrUin0yixALMSlMhAZDUp49xibm5zkI8Do1jzFyDpFLS
RAHBDK/A+aoRPgIA/XEXHN+7f4GduTCA/rYN9v2EJ6a9fGGHaDpBMQ6TQGqYWtDD
7ZXMNI2RHNr0QSHGPDk1ciiqOHG1
=zO2A
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-43f0-443a-87a5-0e13dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/6Ajo9W7qStKbtRnXWlmacpdAybqmEiZ3ktT6gDrEiWLDG
Kyo8ce0JEpRQ7aWf5Ui55VdF3zKIyJ/MzrqikpX1if686eNLiEvLzqm0N781HHRf
YmkTiW4rCNNWsFdJ2v3Yybxofi6KIkau5/71NONlXAL5j+QtO/eclQJpLbta4qgO
E7ya5azZLdhnnnfap/zLrQwxYYRCOZHfLb/MvVlQQgWkpgU2Izwj5uyueosS4SdL
q8tWgs7frfjszcU6/zS5p5h++xCEsDSFuf61qgnFuam5x2WCm8HmEA594ZG36h3v
ruHG7PIKdK05vW2iK/1ucKyPTel2lQ9K4F6+a3DAZlw0INSF85YgxFJL9sPd4yZl
fLnXSlIHn9tC8KnuPqCOngM3z6CN+EVH1Y4cUMijS9N+x52CG83jFjcnC4c/nWcD
bkD+nigTgZC4PI0lYgSV4hhAoJbl1a6wPK47TlQLhFvlOzmgo7OvZaHH4tacoTdl
Om8hNrhBz0d77oY8Zo8Z1fva8GCrniXnGqYBOOGqzl2bY3ugYNx0zFNYN92o9ChF
AHkQMSc49m9DAQm8TfWpiiL+4CGIKjgv+dW1gqilGxMQqWGwTHIXGo1XXtYvOqzM
3bPqoRvea3C9OdudBe/Yeu0YuU/y2etEvvhygxKawsPj1HOAl7WbEQIxyOi/XqnS
QwGGHJnj/IbBG/SDI1RHhIeW1DohWESFos+PsSTTCQfk0Tc39rHx3X2Uc5yn/Bny
/LnxiU4Ixs5EiiQyWt0w1+WBdDk=
=6rH5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-43f8-41fc-b302-0e13dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf/c3KdPzcVDgxGTbs3WL+ZnkgiEYFveYuaWn22RJMv4cGq
KSxuiIDzxdXS7bY7ugL5il1rYeQlwH9bBysAG3VMO7uHgJHirBC7fRH1OJf0bwqQ
OV6jUEv0GYrdbXzmU5QjrKYpR0rdbPtWuCp61L1ncYMNo44swKeYmtWtNu40GD1w
8y9nc51KlVqFlZ98yE6L7FtsS3TilZfHaYh1cbV0e359Ivh9TnY8aqMQbCWGf5fZ
1+BK2HeeLksczzYckOt0gmfBnQx6D/jt1O8cIX6WT7cSffIyckd8s9kq9x2JKzRJ
TfY3rM0GyycfdYUmQOLKAR9xhUHkINTbaghPnQBuqdJAAbJP29ghUsgexD40T2mL
0qK2pSrFBUHEbbZsUmomENLD3p9X2JxFsR/lTPk+AR02wEaEdD46QV2dBxHxt2Uv
sg==
=xlR+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-588c-4ace-a504-0e13dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+OihVzkkJY+CP6B+Sb9SdN56/mXvED4JjKgSjvtojqT39
+DtX24ZQdWckkhGfFYX9EZseMt1X0Wd5uBTOzrI+Jls16QMhH46tHBpoFcWd3ZUu
LOSmAeiWsxO7qWTvwwDusA9pn2AagzjH1R8iKBtn1iLDNavmNDX0TtOOEseNgXwm
+HrUwGM55QRWSdSpBzwPcV287OgugDMQd7h6NUIqIMZ6wjiQrjrK1aTnCf8MPer1
OCPDZp2pkE3XREMBGsUzpmPTJ2vFPBQ3BG6QMSkWToe0pUE+cn4q8sB33rgaGoFG
CPtY+jGu2RZyFOUXmWxntTEnb7PQBK1W7698rXPCYpAIvOBahD+V6fKKfiF+HjaW
jqLZ2L2xVqynNuRQNkte3D2cqSPZSLqWczlzzBDrbjhO67idmE3dzkQL3Br3N3tD
JVwl79Y1XzET80teE4BpBJmuFO+tzZqE0kqGAuDnXXLcmgZxy06oX9fmZRWCqNeB
q+zx6JIwGIyE2ocFfOt/kutUM6ksiwpVYKiViLpiy0ZgJP1PsqjnmfkH1g3BNpDO
U/EQ18QPSVthm9Q+1wLRDUV6wP5j4QzQ11KYXwsc4m5E31YolqEuzx0u5n2hTux9
OGeSg/aFc8yVyQsXYR69/UDmewO6IC8K9m0/l4h2UsWXpO+5s4XgflPoyyvlodfS
QAEXeTCMBLvxKYvyscQQzmE9fQuUHNcq56JisVdivj97ai4IERbesyvkZhzgEzlT
CAFACIJ9YCgvLs6j8iulpAI=
=ftEn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-5b08-4d96-8531-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9H2mV/6N66Tqq1/tot0lVeF9355MilMLaPgnb2XVzX9u7
VcW52t2w6NemB+xpoS6dlN18BYXHRp8JkcfvhuGSmNkgJ/3pPjMknG9sUFbbY4yZ
9ziG39ZzoEGyHDlnPukYybxNq/wkoqqIPOYoXctM3L9MWKgkqLYlaOGN8valD+Ni
FznVyR0BnPHJfcPZfH+aG659famL5nR7IZS0I0BrBHfbbrcOVzMzLahHEi0raJ9n
2RCIGPXxYb8TaW5OvcLFnPtnpCt+OJBVaVsI1MgK5JYRpD4Vv+e8GFFi9wJ0Kwik
FYN/CBXAKU/sP2zlNt0uOpIZ+KwVYQRHvi6S0zwF8ogVw4ZXrXM9cal0/lHAzEsK
5e871tfruHr/3uTb4fYi7Pog3oRF185RrR9Pr9v47tf1hOS9Gr49tySL4nw5HEMy
6swOKDahhl+BD8yXLQpaxhWMu12/y8NC/2zqf/M5/Ct9bWCAakNGjAiN/e35PIjh
bUVIW9TB6MMmr9/6NkuFNaRJs1qpK3xRXYpv8SN4sogS39zk0gBMu6tzbM+r8oY7
KJa26EOlstdFHlyrwGrMnF/3iUbgr98O4dehSO592s8b3RZY1/gfzkhv0UXa4Bhz
ZejxkrUqgrC9NxO/S0tXcshpaFgzNWyXMM2zQtZS1S4Gv7WLmphT1OSPklvsl5jS
RAH54dQ4Ky5iTKZPhjKWfer2eEoZNONIR7rtyqS1faKuGNRrZR/n83oB2oFqaYRn
alaTxvxyZCGTIMdFGK9whgaAKBcY
=Fb8V
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-7490-465e-881b-0e13dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQgAvAhVYSuRxWUk4WHoz2cveT7tZk4dvEKcCNCh81KZNerQ
pXOGkafmBPfobLxDhKT/up7JIC5QQMCCYT5YJrc3UAFjMrNcdXnK7dSm3Hg8uRk1
BoyBQXGSk4+LXDj5M0Vc7Bg4GhB5Kjk9TDEyYORQt1H+cJYT6omcI/jgiqNoT+Z3
YZhV70VyHg1YbgT6mckZKb9HX9RB2qvY27MR5pqAPirHiN3ZcTJ6htSe9ApVNK7l
I8VVhWdjWlAkRyy91Az3V22Lz3FwtEIpO0fl/jWGatdwrA2njecBBuwd5puvXo9k
GfoO0R9ynbWgHd3glcsrqqkRUcQ+np98w3TioJjbmtJCATDI8WRkOMU6o18eXpKX
ZBD+eX6yP3WdNzEHBc+nMyzqW+BBk8Sp4kmuITb4iBZ0nnYnvu9gN4zrJaOFX2di
eM3Y
=ut+v
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-89c8-4a59-be3c-0e13dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAlAG1y5583u9yXlJagLDMSVL9oqOQVyrFQzdSCUOS5eFT
gkMDj73fBu8VCJl6Q4aSGjK9+wZYLiKqhhOt9DMXcEcIPL9uSVh81sXe4F0oRPJ6
N2kKdeR1t36Bo15v2G4x61sD6lqc1Fpq/9gDMyn29fyanfc5W9Rd+TyKpR/0KMdb
3qa/H8J6t5w8cqiyHxZ6BpZsmKDoHZr9xaK4zOxFYb9vrm4eal4EyVK0jF4EBYlZ
iXfsrtR63nPOnjuPzmckMIMWd4XKohktfHAA7633UcqUeWz8la26kRZ/8I4367/9
Juw6SmnwEMGQeDXOgOWLO2qD7HEmeEyblLZQJAq/JbmyQp1GcUXWNUtRQZOjwnYe
L9ymDu263yKDB315cKLPAyuoYnfXgI2sbykE+5eul/DmYVHKOdHc6TxZ3Y7kH+Ad
EwTt8nSrSzAv3gavin97JswF89WuqK5LkY58Sxz97YBa3R1hv+56kdovSNi/MrFk
cHeUYrvZbpquNuJxggbs/dAc7ykn/MgvHzsGwSIWZpCBEY/61H/FjtqYX9+WNe5Y
p+9U4Gjc9X+tOYe7btuctjbFrozEGMNy8Xg5Vm3YJ2CMEsJwlMEj2UEj3hUHce4W
aHbKLIiF5LzVL8VvjajXcC2Z+9gTjeeefS7oLRXiOlZW3Abv8mHYcWY0e4mhOPDS
QAEVw5TRRPdTihmPZxzG7FxZTcZJGNpl0K6yWyjOGDLCZOeOJv0jb4Vg2AZIVPU6
Jm3LlagSiKfnyL+DVbIkFi8=
=Kq4u
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-9b2c-40d4-8765-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+JbmUy+SwZz8YFqXeTQawx9g9xSYlRU+XkXoxyTkdvYo5
VwlzMAQlBZK9vtgoKv77+vhk8ZlIzk/jYFvGsUIk9WZQEP4XomuTewZKAcfIgVMl
S5Rw0kJKFwI9nFkJqGw/vOAR67z3B8RL5QmvlGbacz8Ve49fAUcE5fysEdrhpZ8J
sORYF6Ny4mLwIunocHUyoKkSY611lcuuY+QAzpPs7p2oPgLUeD/RfNSsnhuCNcl9
O2MyKb5vr7tLaCFkKDiwGLFGXCAiEcU2Q9eDcrJerreTGaoNpv0E61VO8ChN6kBl
mg4oE3Mcu1EgRGAg17YSxdRITC0NwZ54tEk+2nPAgMesDsrlFOp4ffSPDZbP7+qG
NhHApNuZ+CDyQ9fShop8JYG0rkRDHqhtnos54GeF9Zhy/lsVeXxiw5BfkoGccsc+
DTpcAMCKTh+pTOEnGCwi4cA/Tz603enOVl32uTlm84SA9sG8amJ89YSZylttq/Ti
bBxfQnSKSbdmYAfZJGgbh+xhpittMBA/wYMSNiDdEvNbuGZd1lOEahVAPo+2oz/o
du4L0ZWddCb3cC2SEhTEcLEPS8f6RH2cGsfBVZvY+g8+e2eeuETkbzdfaHi0oSPs
sOGVAabeNIgBxsxjQ7x3UvDVTHpjrr7o0k0X5KIiMm5nz8yEL0+a0w1qIuwz4zHS
QwEDqRLoY5+CeDkoWwHW7KVlXFDFSi/v7m6vwSk5SLwmOQib9dzWC/myV591DMtZ
lFsLx8Q14o11Uav+Z2Yc4Rt9okQ=
=7cYJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-a2f0-4efd-91d6-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAkmez0n8NPRoEyGEScXxTFLX9qYegIo8slBYpjBXhJOwT
iNuvk7S36JPV0yExDkaImMpmECK05cKQ3jZhx1/L2U8LaIuInZbNzyF9M2r7hcRf
0ionCNCMUPePqQ6xqVKkN0GZ6YRH6q5MdN+Zk167D4ywgrqfcooLb0jRAo4KGEXm
LbbITVuL7llVJW2YbLCCRt3SB/FSldvJfOJC3pho+PYbrHRDVnej4Jsiw7kxUmyD
MGmVR0Pfhyxx3JAAHBgKw/8SETWzWc1nLg5QYxd4cI7RnAArfZN29hIssQOvXQg1
foNJe0K2WkwZP+x9X6u8srQZduECWY0DsFsQT/W3JzLiCUgQKt2tjFEj0Rtwg0XU
hGZoClCjvNVzzGrVbUaxGudQe5KcGWoLQaXOreruKjxm+N736fCmLGUyQhL3QWEe
9vmmtm51jNNg0n2m26JmWebqrifRs5KJr7Lmcvhnbv7zMDJnVDWEYiPdE+lrctb5
w/OhfeVbo2J7tafbUcpeJkygtUWbdUtxsAfC+qN5ybphLXquo/JFtzarYLmmWqX5
4PxKdGhUDHx4caDFR9Y379bukcYhU1fv4aOv8vCym2jIJEveCjZVWZPJCpPtMtVS
B3wt+CJCEQw96rKmt9KIVcj6TEDb7k9Z/Iy7RLOv4dOMhtDjtISDo/8fXu6oYhfS
QQEFOWUqeeZubEbBTKCGLU2cOJeZhbOIE6h5lzEfhwas8V0AYv1usVinIr+phLm/
Em3L+EEL0b9qcUPs5Vqse+oO
=0+AL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-a304-4faa-8402-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAundMaiPv+ajimqR9CMTOg1FSQj1vXwoU/TcknYwGvT77
/ZYiMFdup69PBUvhwJNxBlcjIG3gGQTKfPEDvRBO2EiU0glo9yB/K6eaiYfMrdgY
73rF2Vr59HISUGx/4RPbOKUDGgVI+37B9eBK0QuQbj3zAcEYmwlJtpthz/IiznWc
g0jN9CYnb52SFk/PX6iIAWjwz+/1TcYj67xWNEWP5cTkyzohj1tfASH44tjlYEHq
LxVara60HCZEGCWAy/J9l8ObltjPpnrotluGhM2wiqBQmldgOmxqrXy4tr+0ahpb
rwvSaymV1K1BdFOsKHsaYEKUIkqIWX0Q9SW2D2e3Ra9SwKWAgp7hmGpQWKU7Kaxw
3bfUGlBR+Zgg8Vj2baMUoNmZxzH/HIlpzcqjW3LTcp7ShBnWIPzpCbj69u3zbArj
qRqZhe0v8xFpw/GiYpBU5/5o22MWEazRzsBWyBNsYWTLBDTfbC0NlmV6D+0jVXik
4UcQWhyn1sk5Qr5S5vLe1YnyFwEKggbNE+TxpyOWIFuxXaBz5PaiwuVW/EH2Lj7R
gyiZqHNEdO65kEsNEXbk8EZAiSMF28UQtk8NRtGadu+UhJG5f07fmahVRF3MaZ/m
u34WpIKec2vN5riV2uHGSdKtrUIn2SemSqQDVLnhTfoBNgJ0Lqv2QmTPfjtS9pPS
RAGQaZAzfFEniaampk2uILqsmTuTtPngVfYsTkecsL0Nqoa9kAunQEMBWUGf20Fb
lBMC81RjymXxoXs7NiKCYtKIiys/
=sn3B
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-af28-4f24-bf8f-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAk+sqUQLeVnzNcE/0Y6GvylR8N7eh4tTgNA5wOM1mJGvS
/PdPx/175mTYGbJk8KXDSe3xCzt4wwW+FH8crM1NVEVsJSNHRNxGDqFZzGYzqc7T
MEBpfARnKaFSfujMgAROsH2EPIjNe4ZLiAUTHqCJgp1j2ocLqpL0DVHrd72Pfxx7
KO66O8FgAugCuQ9lDQGYaXJWLJWnt/YR380T4a9Q4gYI9ms/9A3mMGeGiQnDl0q0
FYvkdhTWYSh//PIGchmGOqW/pVQ88DUDU9FL68A4jAF0cGbAaSRIQRi0xZJGcGXE
u6lTFokNXOJ7VnqI3kD0U7OM4GbvRUNymYEiYe18SwvpvnspYvlwtAh8aAm8AM/e
n8Tg8Z64VO+AiPlfD13cSBjsEx2PEMtVz6dm9wBz4md3blaNPWvXOPp8xp4QOhCn
e+UxcmUf3MBRD41evi1Dy5Xrpt47IgWyz8hQC1NpLX+JJ5t7ALv2ggCcJmkw4Q3s
zvYgK7TsQIv4eFqE/g5d2fw8VTrMGiImraqR67tdC4N82f4c3sRiN+fTMgvn4YIb
jzS1t+5rcn3Wjz0a9Crtncm7R7X7rf6P0Foy7Us3JfgRvIoGrMm0gFiDO9TO6/lq
aGDMFhfCxuJxovxSUvI0rZs6IwkekgEpy5ZW6jiYzSTIFfohWvIUIv5eJzwaFqnS
QQHuGqtcE/ZyO16hkvc3beuQD1DxFSdKRHXl7x52XjFFvLjuOcsjYpK6XeMiYwVr
33HkDEjkk9+p+dvGnrTfMD2C
=ZQVi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-bd44-4bc6-9e63-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAqWppd5GktW3U7jqoJ36OlOTw/icSwFvlVBeitTAcxUph
bYcK80CxSAzyG7E3NMJkm8c6OkF8eiTZbUmivsQL6PvoePRiOuhi+gm2Wyrlk9fz
9fp+L0hHSF5eXVSmZvUCgorO9lT9g5MlDQdFLzHOp6pb0Av0vsQqxTjJYnaK/Y/9
oHPbF/h7Xr5K3ah7ab23zBTAo9mZdZY8b6OK7FeiKFycg+eqFbjDvdWV6W4qpqGH
HxFuUJ+KDRc2nZY6lhLJ4ZWOvekzp7KFsPVJkKFHY/jzqX9nfMz7AizS9YpuENUB
tofwP5CuynhIh8hokVcEeTNYDjVB2wmB3XNYDjs7zJrWcY7qpqUVrmNyj4qzWKTg
l2mYHD8rVg9xLGX2lYvlMVxWjCh1oeSgVNwVlKxfsBW7TqrkpD3jOzQNi8/6uL/n
Is9knMnosYOHYaUnO63WFdQLYbn4tuiB7kQH3HC++DQlU8vx1i+bZIO7fIy4UIKd
9sP6ewx+KSTbXxr8ABp5oJtKPyzSNOO3lCV4AtLxAOhFWXY7E9/P/4/OOZvqJWSY
C+5kqb3LYdixXwPk/sQkd65TnIVjIRvEd5mf1o0PK/98cCy0E0t7DHj5uuPI9ZA0
7JwkiHm7LAsrTjDZrdjjAi+wMT3dN/EERop8LxYgD/AWhnqahBTBA2WrRWP1sULS
QAF//9cX1EPPHsi9FZfyEkrjG5a/FOoNU4KFKDMv05JtQtI/vMg6viQoC5Zssua1
TkzFoilf2TD1xEIGqEl4v2c=
=Yred
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-ca54-4b0a-aac9-0e13dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf/fWTpk/hOD/oQk6xOHJ/+f7SiovpqmOH9zvOjm/9gthcC
AY/K1aD4VeAuT5bf33woW+BfWemf2odEPrQj1hOUgrkACPr9G5bn4l3sZBBXM9yU
mxHoLwwzC9LrkpkjZaVJmisGc8MuftIrViT7Q69OyPbOsTYLbhdt8UtzKVoNc49I
EuY2fW6I+LrcwaIUHRSlrr1eUgSKPJUp/aDWsV5b0ezsWr6VJAWUFRr6cyUzSn1A
cuEhz6xUEIjqZsjGaFzjcfR0YlIbRF7w4j/mIZqCcH+7kIFI95i//9+Iskg/7iUl
owRZkKpDIhxLWG56QOr8m+mbUqItVnh5Rkdwagf1L9JBAeeTfNX5rrDOLffO013u
AwGP+I3pJ/7uF6Xud8bCGsPh2MfSQldcjx8f6YojKED099AGMG59EaGHBaMMM1eu
GlA=
=eOuY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-e60c-4277-ab88-0e13dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9HeTF/eGhKyayTT9q+dp30ou4upd+YRAz06Q6f6Nd/plA
AQxdvQC/FrDZxw8PfNuugIrfnYuInbQdTVgP7DtXnBpLre+Zj2vnvfdiBt6z5LUI
BN0spqudxbGNVz1hgNq6JfXk7HUeCezOpNr9ERo/XvqrWXFzDWJGrEn+qPAlOQkc
wbrmCDjyF7CSqIpnvKJbw2PvujN1GpHzZZTx0x0LCyipVKA0rvN9dWBgGQDVqE80
faEiVFvPWPvUuh0ksQyOFXW5djtLmXGN6Q+6Hwl7PICdP9prunXPpuz+FkubkA1V
AHe0pW14Gk2eR36AtcYdcoKSJ69Lr4UgiGUdWEUgxbsKySUvM8oh4bv9RypHxIdo
N3IlG9TvwrYR29vc5UfFxn8lXcL/eZJdSnDrfubVZbfeG84iyBPDVBGaSVaCYrhR
DA+tdAWQxZGSJ3PX3mCFAvgNzKucjyjBKvcF7yCQpBcsgJLCuMVktdaAAnBbuUUq
th+VwOI1dThn/o8xQ8fvyYD5sYyQvmVKkDADTNhkTOenBCitRWWAO5Mr0h4MCd50
Psyy6lhkLwpE9Rx9OQidDUxI3vBpKTJ0XR7wgf1AgAP/ORHjTQBzCyLb1vwWEQnF
tseU6inT/fOw40Vax4zoVYb5KhXrfxuf/22Plz+E7Pw3ZQZHwCVsVE76vVCX/abS
QQE7kckwNlivDuCgcfp7JZQFsVFLwLDCRzTYdF4mssi6Yvm2OKA4L6zFrOTkSX9v
+y2bynwwb6zy8C5T6DNjNahE
=lo5x
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-efe4-4af9-bccf-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+KETI7A/so5JKLhkP2TWQEvTyjzkzLcfdIxzqU+6tYrsn
gVOHkzWhBCPw5CR8pwvQqak2C/o5L9XvPyYyU8S2xPxk5RY9ClCrWDgpNzdnWPJU
b546f31dfkXJa4xXDBthgkH+XYuSlk4E13wx4IkmKkEUuOemXiJyHkj9OiV2j2Gr
9+4ttJ7SzESIiwF4fXp2yZnaqh5nlzkVYkZcu3fzk1oW1yqcRcFz23rWb7psjw4U
ehk0cQjuQnEsjVaVVfQitf60eotx5cLv/lkUNYE+XKviuvDYr+8mZFFlsU71ZAxx
nHw8NyljM1xO0tUsjShILLHkfD/PqtmqKLFT+xHjy3LUkOooAJwsjt6i5mge78kA
3OWRuzCsg1lmTzyRFjhiepRVFmE/F5jsg9U/8bQ5RwNC2UzI2K6BfXQtvWTbY67P
iT6eV/QGGriOYeQHys1J7cfTzrhpGSce7a55egvaJab/gnE+rlpbQcGvKrMt5JTp
1+Shfg06adX5CrVGfDQV4srtoGWmx7b35wL8vgdi1PCdP+SnC9LbIbZ3vGtngiBI
eg+UWHKCxaqLZ9bYmZ07V+H5vqzm17Cac0Dla7cuWMTebe+D4fH3HJcN3D9xB3SJ
tR1n5cugDlpQbGvNj+0Lp1Yao6zWyVqB1h2wqUuSsY9he8eDs9EvFY3s5UJtyhjS
QgEG8McD730Ri7XdwgxPCcXsP6zmo/YQ3jw/CrfWYFLH4zH7VOMDuco2chAdio1N
aQjAOFKxJBMkE4VlGoKsCvwA2A==
=kY0U
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-f1f0-404a-9028-0e13dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//R1LYPMujIvFd1xLWoQ2p+FjNamkRbNCrejZMI27F6Imr
0mHXFc4YxCK3U7JrFH71EwuMO2YnPgMra/WfXDkJWUabu/PZJ1Jd0O6azvX7oqeL
tt9QH39equP6ErhLetw7WNyMrlfiZ2MhTyjnz69S8icNLm/wbm0HZqoTFlYszA9G
1YUxmd+AXcOCaKXG6C+kSEB61LxaVAAMJ9MMJzgOS1+sdN9Xeqoi1HMEbxkeSB2U
2g+FSGrzBvf9jIHv5lhqn/9PGtN6hniedyYGXMafqQf4rBj42YJy+PYn2BHf0lWG
vfrOYqKIYaSX9zxOLsm698cEfHQE1GzxSB7/9NpTppRsKBMpChkloJCY3W+J79P7
tDfWkiwnQiUfM6UWuUVuVC84E009eZ3Sq7rJ+wMPSCgdMz1Nt+84aFp9nJBcKzHZ
IutBPirDYU9+XaNKu/XErT3vI1Ub2uWU9zTUPmIl2w0qlwVFXHhAz0KevAdjyKAV
3LZsOzg3ocDdFRi5JN3h3lzeeSJokUSvqG5d+NPAfPcb2jABoRotv+qPHdZvr3Ef
4I/o5AEHmm5tuO8XZ4Y6oA/kBdt/TOJIpFcSRQNrcW/Gz/4UIocv8Z2Bef0PrW53
tyT/i8x0/RPUQGzkIWXgyOvwVkgu4Tv61uS+gwaDxxqoEXfpr1YnU0Ql39rzi6PS
RAE7fIMQLD0fPo+TWvyAxwfaZP81EQCijiDUfXu1Hj6iD9PHoDTcBfZ2tbssGJ1U
3UdkBAAA0blzC48M/taMVglT6ToN
=zjJu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4d-f98c-402b-bafb-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//aV1ZVEwjk2Evu4mZR4pqFqViV/Q0qxaxtc5S9XAEn4o1
J2MBP1mrVLqzl4SZFVftXHlzr+oveF6Nd9EYlpC5qwAXSmr79Hol87e+OAKxNKd9
wxvMqvwMbP7eUKb3Ch7SWNjrK3HOuS5+mT5Lbx/IUw7M3NqmTnWJALneTXmxLCiP
1rKVmb/TG6+EiOK5DJf46KUvA1p5NfGCh/cAPnhVseT5LaeMOzQC8MoZtM/LPzCN
YmpJEBDF2L0QfJaRo9o9iXq+eemfVFEso9DvUTDErslwCtLpSveotr7zC0kpjjFy
VWNdOWruocWZERfY4A8pp6rLiGPtW9LCxFFdlF9wwFPm1XyZRd3eNOd3dJU+ix2S
DdtHqfL3rlOYufSFm8Tof7vN7/Vk/uHPuJMxs+OgktchPct4fM5rqLcB/PjNM2gW
ckMXS51xudp61XKY9kocL4spy9hk2cs5a/ASnSLmWqFPdlmxRbWObAy5DCDwFIVp
Ztox2ygzzBlCReNDu8nmtWBDJnoS9iu37uwmVO697azRDsuYrlL6TDKBrYz2KFFE
5+IUxoEPZskfB3JNQkvx9X403uthfNot6P+v2aJQTtaIzyORxSLOq4NKfpvkAemO
B3JCC2GPRYLAz85Sv7jKWikms2RhQRsUZbaIgggarN2VSrZ+xPzVXVp86zBDtF3S
QgEwDLx7jSySJjLY9q6BLl39u5yWq3VE9KWD60DqDtE7mdUDCBYbiczCLndV1VU9
PtPsbxg2kb+shSLyY4Y9WuYPSg==
=3Hoi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-1ff4-4e61-9773-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAknnqp0PoR6/WvZqnrKxB6wQCkYZbg9esCMnj5bEOs6LU
b3beGHqMcFWhSQsphpCX52HNvUuoraCDRzrZyZPdS3gvkurx/zL9gJDg+fkWcaNo
NgUuYAuT9dfIVfvyoSD2Exwxf4Duc3OQkJU2M8FNngzOZ0zrv6acDYhlwZXCE2hh
W8Ccssg5V4vdF3yvgHFsBdsK+C8Mdos0jDwrq9v22FNNpVFBkdNGJUurI6gexXb5
qSL+1C/Ta3xW8YRtSDEzjP+Ro0eYGUC/So1ggG027rHy6IfdD3RiZJ7xlNlfW4Ln
s9WB+PSRYJDUJ3qdD+fXvUaxln6xHME5ZPGBk09c/LAm7V1xp7NzLaPnd6GkHosy
QudDxOJMt8KtJKUBnVrDGLQhlGMtAD/ZCkStM5Pmjy9XXa2TNroGhsVqOlIWDdJl
+F6aLwQdNJRpqiliBrADIN5bvflH7y3ymAK8sXp6VYaQ/lSVRmJo9YbqHrLY3W8K
3hKI9Ft7OfnU6RARcuVGKhKBG6AX/dPaIriS454oj/goRd8TPSm0RQUmq/T28oB1
QoItuMjHiuUdE3IivzXH0FUEJqNhGHa/jWAvZfTuM4/4fCabz2TquwsgK4YWdmwo
q1Mhzk3DhHf2n8iXG333LNAarQr25Sl2BZjXdWO2magL8gcaBDwVSoHVotyWktfS
QQHCi9N9O/iFVBjCRDl5L9CQYL84kfTjRWjK7HaXVXUc8Xkl2wuu/7BK8FajZ6lH
eIcVl4KpCxh27nEjDBWVdyRP
=4szm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-2188-4ca8-a95f-0e13dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf+Kfm3Gf8HhP9FLyFUPDxHp3CvzjP1ArE5MNlQeoASvKWp
s77wbSCHtG1PHQJmMovyS3IxagZ/RVaPiV+AEL85eRLci6zFpuBzw8TQEtzOjScr
lprTms3mZjuoFm72qMFLfW+DPHPFTGpe2Q/hxoCRLQogclsWu3ndAIpXEa5ks7OD
fQdxD8hXDaWDqo5QFulLDu/ODK/YNbzxRaci1bnzkJnFVaZ9YO4rnBhmw3RPELZA
39uNneOMvxxvSjq4RAkATZXEk7/GK8wi83a5xY0wclalvitDLjAVjP5XuAn1KvRs
ZqegliPzdiGZdHGcVacTqw86BDLYrulvm1bMBUxUCdJBAb1ggFzr8XmMXuYUhC9E
lMm725KFDGRxXpZkhckOa51LyceCtjSqeszF3BOfjUyb3BvKkhp3idKt1kKn0MkD
mgY=
=MwGb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-22dc-4caa-93bd-0e13dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf+OvNtYpTFDdu09+3q08uwGrMYG43Zj/SjP87EB/bjpHuw
QjcklObQ7W92lryO7GimDRlU4hj2OUNA3HGh5/gSq/KowMbAXo94VFpIO0qknDZy
lzMi7bxvCtNeao2hZ4HZ7ws+aO3QImh8tPb2d2BqzTtp9w00Ixa8pcUNtN3Gkn0y
zneetbsaw8Pm+/cjhRvDSQAHam0XAWq1w18bNLwbALGsMD8JPJFIrp48/P7IOpf9
2701zUmHPhywRAwDf8zo2+RCtHZUCXNj23Z0pd9ChvVneqb6WTg9fiOcX11VknBN
yNcKDjiUzKQQU5p2w0bBx5v5+ZiL3K3bdUmFz+9d2dJDAYiGZXqBMOyV3Zjhczdb
d07BX4BoXdftX75MoNBFUf30GgIOt6Eop4z+rNWuU4XF9PAMZLn+fbw5vtoPWRsl
lngD8w==
=JmlQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-3444-47b6-9b8b-0e13dbeb2d5e',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//eXJnGWqpWVI/9WhoP5IGf/J4yjz0GPEy+ROd/M0WIcNb
sWnhhTqd+8rHWulMGc+WEDA1tUWeHKkPXNmWeGlJ2F/NyxZeo9UBXffIQKqI9tQa
RTT6R559xVpixdnyMJeWpSlWhoTd1hoY83sbZKSqgP3t8Acjb2/DosdiplC3i44q
Ox6aJtMkVpT2Sd3iZjHTHe6iB2mdnmlZhlTNhhvZPppVovWtQ46du8oVEGma/qGY
w/IAKNUmjG1vC7Fus7dAJs8lv54I++ZkF1R712H0PAmBcIwT9FLozTHkBgv0TPqz
/I8LCr6nrOKHoRAghl0NXgD9FvgxOztUy0aZmsXuF/DBxiXUhpGDiDQ9HFBqRmDY
oVSagcZfbD/HeDkzLL5dPjD3fuPK+gixFqL48MfWPkoxhnUoXBJDOHUOfqR5QMOr
W7YHT+DpX7nC+gxS8s3xAH4tcS8Vg3LWkzhEUyV1wJNqIvcxQvWPmdCU4PMFMhb8
jRhxBNxIm1ZRrdmxaMMVfxr1+IbtchZftTohMiqZhQ5C4cQOCzFCsyGTUF9vJ25b
Vjuvlq736RKvLEWO8L43EjZyEEACc4ZQDUB8JPKyZ+2LsZbihN1hEhr5IrLfJVhg
jRJsbNnRtvuMAbhF9NEJQieXsq6mPG+nH3Rytt87E9KlCb1/nHW2QU08IY8bjFvS
QwE+/5zRY1OUW53+UOLINGeKNa7g34RLUKuLfoJJtmebZV0zNF7wEsOR7ZGj9i8/
JVcFqdpz7aI61//uyPFU+wu5qpI=
=a1+i
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-3880-4725-ae72-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//cZz21lqXf0z4nOa+v+kSBlcE/KK+dirP5kwE1WHtTUpo
hMOaA4TjykXZTzDFLOYUR+mgw9p8mOoHlLjlQbp1wy8xECLeEli275Ga/Op96z+x
2gNoeLwzYbQtlBinjyOdTxqmnxlcsE+T5Vu0LTBW56sdUBonsZy9xlFR50xxf9lv
Aq20EEP0737dGzFH0aXUf6EA3vIiBEmjWN3kUtgpxLzFQ/hyjmJNsIRj/NzxtvjA
4XYoVMx3qIk1HLrTenzG8LRsD0GIHqm/NVRCigMgCtEHRGHTU2RKfMh7+y1O6GeZ
O9HDRomiVVDtl0ZCfeFebIn5y2jiFOno33QAtzZlnPHFd9nfK+Df193t7FuwUXBl
s0xo6rXcR4nQDeBiaJj5al0KsZZ/BJZId2M/kX4COknD9OwojnsnCcnTFgHBh+3H
f1oYzGykNaV/+G71YTa3M602v+cLBXLSWA67DlW+ApOAQa4iZNHt7bX7+nzbI4cv
N5P4p9Yydg7YCyGwkj54A8SXwl4UK929uvalI3voncKrHlNLn8S7Uihi2RtgmQ0z
MNG7TyH1UYLHh5a772B9WN2hQ0d7qdukc7bFBdn0stbamOZCLwnb5nv7Nr9UGfly
mtEVOWN4QLIfmNScKhzGXvrpRB/CO7ovVFdHi+xPfF6UGBki+8oqHZDFGc3g66XS
QwEENt/LtzbQv3+Ej0pFGjlK6TdsCn3RGiJHgp/tkbZxXLqbiLUp2RC5UJDCW3Nr
crCXZu2CxOBnFzL/YSFR7lgbnio=
=DLml
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-3f24-4ae8-8184-0e13dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQgAjiVWDeGw/fMLtMKyYh0Yf6e7Nc4OvP53VqJmOU49c6q5
E0XmtIS1KmoaUN6ex3m48/nNpOgJaTaIoLG5tc9s08m8wAQzoVkKKASgyubYHi1p
0kq/VUG4tBlgGU1IjRHSVo7XIzB3x7Ibkx3QZyHSIQ7rGpe16dSVgG5agfeapVqF
HpbQ8zpqG34U7ewgm787PR4jNZqEant3Gu4OWHsDT5hw8L97WN/CQnoLvRo1YnWD
oDvyyJWUJa8vuaULPlRpB6WYO/IwuufSpmNvcHPBSGOI6Z+a75RSVFVXWzK18klp
TQTVeTU/TcouYqWz2wP6sqCV4phf/qKeFVtfzD+IYdJDAW7YyCM/uTW7h2eWzS77
KVaMqDCCu4ybZWhqof4LFdXsxOvjqWhEAsd6OrMM276M8UN9jWQVs3GoRfi7+WNF
V8OYBQ==
=/E1t
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-49b4-4b92-9a61-0e13dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQgAlqvlwkw/2ISssJyAr0z/LMks88xbswcCa8Cso/uQkwO/
sA8tNrAc2enoN+1JWmIVEZ+EYKkGPsTxpSIaeeIFtY2+Ep6qEKMo41QFPJYDe0UO
bhGXertIbNC1RkCbHl+9kAEtCuweDTskQmIu45FoK2xYUtKx/xDSxOultCjfi+s0
/Q4oJ+aFTOytcnqTxG09ZGcut/dn96hnwGTNDpf/br81BLcZpQdV8GatOoKZ3HwB
GECJzbW4i9/PADQbegzOTFkw5DIAVObbJglLrvUVunDug+X0LdVlaaoDs2N9oJ3t
1l0K8leGV+EvvZuxbk7iR8d4T2R8KEVYoJShiJ5rANJDAXie1ZI7hMLc8NazW4vG
lxGsYxKhJg/eASYYYdU8lbkkIue06EpPlZKnKrI1OqNUrFCmMy0tNej76HTjA0q5
i35Bkw==
=f3uz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-4b34-4620-95e0-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//a9BPftsbHexKyXDtUM5RV9i9uOIVHHBzPun/HLBCucSQ
4cj4J+Aua3LU7coEjw4R+YxVJtAoyQW1Xs9eaicaKaLs2zvvMTK24K6sYOKaV9ck
VlKI4GTlDfnYb7DOiAlP2/8bfNCUbUfCreSTvDoybmCKhppKz1SyA3HNh0p0c9co
n1goqctCpurlpm9wuICtLSHgSPzE6L/iekwMf5NhiduKhgFDtWYDr3JfW/JldpCp
rJU9M61/S4lwOcF/mCXu0tLq9TA6DTy1aAu18fuuWkdIy/CF4y6yfVoW5snpE3Ed
gm2ObPpVS0OhFjL7TOl1Kt/cB//osKlPziiVQhQV2nzfiYPH0FAKklIST8WVgUb2
gP9aVfXjaNPpusNVlkmyMpRZIR/6XT3DiLkNmyRvrz8BXJY85n+aOUzviNRpRpqB
d4Lhh0Eop2cR8CQu8qmx8iaYcJNtOLAzTHqL2od4QC8DSMaVA3KZjiPrCGjqeOqT
+nfPm7BzgeJkXWSNFUiJoEAvkV5cNHVIb8Mu+nQRykC4xlMrWpw9R9NfrlxIa60h
8qi2U7dmZzIhY1FWGxeBlhqQ+giua9eAG75vCI6vk8d3UAoLu0YT4ukHIbvmJAvE
6MAEPXoMR4d7oZTwwyFvMRt0JiNtUw94o0dy7k5xVASovjBIo0m6HhnOm038kJzS
QQHfReCVZjBSp5amJW83GM2czif2p8DpkL7KfxzO3Fct600BwYhY7opwXBTj77m5
6Sij0d/SYFMgJICPg1ZTtZQM
=c/0X
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-4ed8-45bd-ab33-0e13dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9GJUj8i4Q5VV7HOP4t5EBVQQfgKxwA0ci+g7kG/puTL9D
khIMuSay0r8yjCFYLau8L7qflDWcBSUoWdlBrD46eTGgaYWYPgBNsuh3zRaU+a32
IXQQ8wY4qaotUE+CmTgjkRPRuKw2tjmD85n1QVdwX8Kw3R5Sd4of6GmAiHpVZgkH
mUEwiTLCIaQaiUlMVByvFTw0EMjAMEnd+PbyvQ8x+YPq8vZ4k7k5XqEP9saRYEQ4
MOkXxEfyaldROdAWd0CXjWF2Gm7MExX4JZoJ0CEKtGdaQJ9ue9cPOE4DTSsTO4bT
vPaUv7Nenl06aExeuhn+gDBpffpCuVL/5Tm77RgGLGJFtJRWCUAajsljg07ZDfh3
C89c58/5yBLGKTU7xat0w2NzVmpw+vvupLura5Z3gKMZjQx//JbZRbxiaaaGTkVa
VLUdgCGeAj1CcWBBy0pnN77pLQUs3l2xyEr28V+oKx0K3DbdKMTudKUd8AR5Dk2a
09kjkB+xyhnFnOgsux0wtM2xnipndJ8ZHv22xALzrboq2SpTCYJYqif9WUvpL1LQ
8qmpDLYF/g8ChMsao+qEQcSLE1TUfdBGvrvtITpaGa3qUOqmgBcgrMYxPo1/GjbV
KYD/IA4MoWX1c8jc1Qbn8ICLUFLeEeLgFQrw0Dx1Ovvkqy5wOm/S0oaziIZ3ffTS
QQE6vLHvBidac9SCoqYysu4WhVJOslztOxqFfyOYT2JzS/4rtYUbVBtDgolu52Zn
YYoH2YUx1khLEO/Gh5tDEP5g
=AVC3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-76bc-488d-ba39-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvqExzAX8Y4B9ygUb6xsVOrB6Es9GaKJ0xpN66QbtbYUI
AnMT9982a3eb/07NI901Ouza4K8Mg2TBX2C9gLlcjiXkbP23G5LJ0sLo5LX3fG/U
zvdhO3nS5C6nkpmBytyNRIqRCBixLRLS4xdYuoztpb8WYospTbd7MllxXz3CFWEm
BlW/CGeU/ZkRxeIE/X0IVXPHTRiuexBUuOzCchWLgAmNkdu/GkJiyg4jRrgoIbba
cUPM4FqAJE33Fjll45JmrRHsg3mbdPvrhW5J75f1FLPPUByW98fDC51xfDC8PhdW
elwwJrU0re0bk4qKqyMRiWHHHz6rGyDsPKD2/v13bQS06MofGPde1/3WNbe85aTg
mey7dK8NCclSjvPuHJUnD6jcBXbs9pLGSabKLi1zwPW6suP1dyug/yyBlWB2rpxA
gsEwfTxGVzMVL8mSV/t5Zqa3PPnUlrcpmxtEW7Ru2oBZUA+2co+p95axW16K6luA
1v5uT8d6IOwL9dMDtw+qTXm12NzgStgJNCTvphZqfa8kADge4Jg3UI3sQG/UoRku
h2W8uHhXK2kKu4UcVpZTLgAVBQE+9786IiMoKQBcVbhwBvla5LiI9PItl/X1Yzkb
Z7MGVGVojc14CiJjPKWcIOwNECC7Ry1YoXQiWE0MPxAcVmm/0hufzVlZfYb0uMPS
QwEcdQ1Y+qbjCpGVgDlH+9nsO76pkVp+jiTSzHhoOU5/pcTVFFI7QXQecaWVGNaC
EQn8zQzNfR8zWlL2ayePcsGbbF4=
=TCzm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-7bd8-44c4-abba-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAlFepKCwMBHpXhYp2vFyJZvYUqUyGnpujnnLe0DgQBWBb
IvVO1WrsjL8LoqQvpCM4lvsYli085cOFUr1+EDIROZczBAdAimcmJCPvUXJbbaFP
hzbNRyXZ8fYMPG5djJM7nmLiLzLMkunUax4JnIQdGk6s6vR3lBA/2nbY+QkedUvQ
EZ8/JYEhLi81MMpTTQGZMPz/E3qXOwlJdkuEWchXU/rgmIlC2IqMlYM79wOV8m3v
igQo0WY8bWGznOi/9ofkApmO6mq4N2tEPqaZVc4UYDL3/Pr/EpHJ/5CpHIMWDZmK
6NJjVVKOIvduzv5EQsP7C+SbH3Qv1WUDXKvOzUV4gF3oCfcUGzE/iriBtdjlu3ZJ
LgqURV7WsRBmYPmIZc5GcNxAq0LbkdcOb14w++cyMBCmqR/Z3nfgXUyToR0CvSa5
EBmV4qqoE/UOJbA1xDZNnt17Td6x9B/svrzTXZzN6ZQuew0RB952C+9Dmd52RYor
2tXXc+N0+CagDk+jEOvoLtwWMnGSnCSUzV20ojSVVvPwEf0pr65pN0b5d0CRD6JX
l59gb05DXTUxkDtUei3BkcprdM5TvNJlcfo4hCwemmR3Xokpe57AxCxz1lz/NjfS
WU79cSa3hobjFoUm3c6D0WLlRyNEN98Be90qEXsAZURHE3ORaFgODJOaKbfejcbS
QwEXOMh6DEDpmUxJjARN8+MRXy9Hs0Mr2rvb4T2zPhtLQBkkeKdPLl9VXaYnnnMk
UDagBc9fA7PNyxdljti46mtQa+E=
=15s/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-84b8-4d01-8111-0e13dbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAplF4Bk261SqbUfi7eLA5YGBjiRyRKNl+8wh/OVmV2H26
QMprl+bbU+ipbDhAr6bDUB7L78QjQH6rPFpruPbRmRjLynHwKufP/pyWvLHAboJA
GeLo5ySC7cifVFr5A4lig80hOm3gkZMVPDcxUdPJ+X0i8UPTxvc2SzKy4l0qReqm
Atzh/UIV2YwQn0faul65RVegW63vPxVGsOsvENL1iLE6VW5fKhMnbBlHfBEk+ReC
+VwI57Tk1J5P6KsM108TVBc/uD8sX/7XvMNn9n3pP0IaxbJFKa6FKMZI0Gax0F69
9OFoUR/afk65n8Q6S94pQKIoNA2SbsmHXgaMDSMAy+06QuTatGo2Rbz8EuBqWqLb
oPxoo2GeaFw09JEFwle9t6vwNMba+uHOBhTWZJ5xD8Xlycs9G72MlsnRuP9FyYa3
mEJ3ifcIqTJuUBO6TUVQLUAxn9OSTLy+GeMPyizK1Zi0zdZNo+FKAUBCMHRtvgef
ucSrDS0AXXO8ke5fm4tBQCKRJA6AGmuG9V5OR1mnCrNoCXFWgr9zhF7li2DdKtby
61sx0OHtxs7qxHiJGClhHpbxduF+OI2e1sYdHx8jdb+dbNz1wczGZ/TKarfTjvfT
MTQejzckKoaTlF6DNPBKtDjGlXSJYkiIVIkLqi1n0jek/WsQNpd7p3B1iO2XBgrS
QwEJio6YzawVamwlJZO2fcD4Wjdxjvpw2AbZ72WY4mtjUfJxNsQdV8Ny/c/7zpBQ
+tz/FwKLlaNdlD66rKVIE2OmHyE=
=0cqz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-8574-44b3-9700-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAmAeEfDYV5vW+usxiF7xkSsf0KH+FCQOlwFoZRMxZWdhS
6cxj/FdOPT/9g5eZeX7lOTsFqP3q8eHN8IrqirOlUI+gIIibZDmEId2LSILI8PXf
IA3wfjDtzDYvqavzs1m0426DJJ9TxzijpBL7+uayEMZI4X020JywAX5kQolOi8NF
DXN+jIfWcRzM779PoonStFH0+qKYNIvt1VOzCrTpXfJMXC9PxnYKIoODH6BV2K+i
1uYgA+Obls/UcSiQNGKlGyv1l90C6E68NiedQd4tg9uFy//LLickfkOhQfDXTUt6
73rnlrXluZnNkVt9MWmp1mdr4CBkLKj90nCqrZagYBU/7iqGJVVjqG5AqG0Ebpj3
LVBPZ4UgGs8Y+j6Ogs7KEoQ3F7AOLDBQQH07BKlMBzyyTbSQstrswZFe/ABIOh/r
kWsqfMcO47dYh+UZQA0e1pkNrVifJvSAAoz7q7tVauPaCaGljfd1RysNqwtm1h9/
uY0y2et8Km4+tmqTKTluzmpEEllv0/0iELZEwG9SYOgcRZAjXEMKEQpqP895PihT
tEFgWP9RozbDP6Rv4eDceAeunXlDDg3o2GoFcIGutj01u8bbIp2ypJAxxbET24MY
CEwNYWd4vz86Nc8e2KEWX6PHevFKjhshAySBtPNdem+7Tx4Jw1chDfKsxR2yKkjS
QAHaqXiZ/UmgUxaklSKMEBzkNN3GDFMW3IIgmaRfIJw84olfygH/gU/WRCIpoKL3
bkrKrVGcNyMf3OSC4pq2hmQ=
=9PbK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-8a98-4b78-81dc-0e13dbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+M8DmVCY/nM76+mJXVwJK4PIQfyLa4UVNK+LuoLQw7GTz
kNmKQdCoznJht4EBPl8/aB3jvgPE6vyKuwC/6McewIT/1O7ojeEZ6UtIU/pKOHHX
2lpKiYAl7PPrRnezVPcozwbxLGWzoIpMO/qH0jZM+lCXRSX0F3qua0sZPmm4DyFP
WZ2zOpHNgmBu5zq/EXtTuAiLt0EY+6E0n+BjFlA2FdOMKWLlCH9Jq47vVrqR8ujc
CMNBJtoUaHsvr/PydBo0tyEtPj/LjsJvHS8hsoVymYF59Ayj/EY4YgF0yrMxQjt1
CKDNfcwnNXJVRd8Co/qHAexrSOd9aG+8JARyAQv8y1Re4rxdUMmQIs6uEQohQMuK
Z4i7mjdvbF9qag29byRoYrvbFKs4fSJ4pBU04mlq7BFrACQAAozNHxWGrYdBx+CB
GWYAG0KvHGesYF484mY1C7ddBSiOh+AvHn3ftg7EoZxSHnuT6j/dGuz5GcfFtfrL
Gk358nFYTxDT96TuBcDuFYwsr9s1K7RaITXfn/RBVW9De8e6ntKM/5OKNN2IU9aU
XXQ4KYVd+vmzaWhiHbvKk7immuS2RN4pM669ceAZXSNvNoz5E9jnkxFxv/5HSKk1
mHivfWMehw0AvtRBHf+lOBtZohbEIq+NMmMkPsuRjp/fK0EqmpK+52aU/KlNb9vS
QwEr5xm0rUNRvtsfT/NXjYImUz0XnQyQyg5TDXaROiQxB2VJOUBwVGnyaMhsJPpW
nNPgE9PJczApoFb/Si8yjVH9luk=
=PrAj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-a5e0-4f0d-832a-0e13dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9GyEC+LDfT3kUDtER8o8Wak0hmLwvlxftD20Om9gmTyEq
4WeqsoXq3bKrDXVwovL3/me1fxdSRMFZAYCkDASwwG+CRhhBBMfBNFC5S/0xk3K2
Z7FJ+otXt6TWGecIi7B67uSGVc9SacUvuNDZ0AcGpeSCBT5YIr0PE1+w2Uc0KM2c
0q0JLKumuVprSVcZIrqx4w4hk/RQFHebBx74rUtQqf69n9VwFiei+3982BFAQcKf
sUB+A80R40fMtsut61zD9vBF4b97rJYrbTXQPPkqnBn0uAzdOnMkNWyOQcsb5go+
2iaj/vyuSBDd7ZMzDN5hfY1gFqUAxApgjLahxlSK7ooukrtzRWuMzOn4jE/A1Uau
SQ7Bb8qsv8Pp+Vcb1p5pbCo1nJvJ52D+mcsLyJOGTTC57mcP4glF4TMbC0yDxonK
Mm2Lo0IYNfiyOt0dgTCJUkvRplB91tSylf1qbMDnflMdf77X8iCiBDIPVFMg7mj9
C86HDhSkXBJctxpu4GZPIFFVscqpKp2E/yBpX1/vnQovf2vpzE0YXh/cwVs12HRL
07s/LCV+y+S8KxhY46D/Gr3tFyWcbKl3PznAsKIOZa3RG/SPBTbYFhXwyVtHLuBW
r7o1fLLOSE5vtCB5p/dvvOdU4onEANaUzB7t6EBND7g4ZQjbtu24vTmJv5Cn9ZTS
QQHsgDJyiCV9LYGfedd/4YiEePGxSjFlu8l7X1GpTttygOWvCK8e2EJbcHt+HNCf
E3ZIazB4eWn+Z4x2zx8DcnYz
=zJYd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-abd8-4611-9c2a-0e13dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//cJ+rrOThxG0qPdR/RiGHckmOsb+mA4hz3V6wtYIe6mKp
gjrcDmP1hRO6V5Q1TpPiH0ZC8+ACXzhu+32twXVcA59+gsoI0U627B93kjvlyTmz
SbyViVUIyQBeyH099qf5hc2cFgEp6hQd6JaxBAK6dB+gtIOGABHqAPMnRmPX9rAT
S8brFxyX9jvqnSPMzMnHCIfUjLhBAGkRrOurnwAS/FVcajWMG5Y0ei96vYS4MXXg
IQVxgUQoOnyG4Qv1B+VXt1vJnNp7VFM/dKZjaqkXcyLe6lHXE0gY4pJW84mFAHxy
B4JK+X30mi8Lck11Cg0RgK0aLHqedDuyx2mKzzHAAIZ+0PwVn3lKLt8PqTHRMO7n
hFhfAdn+ZxQoii8rAMEoDx2yH43m9inOcoHlopukCFkpp0rad6ryIf3wlwoRqEOg
8jnI77uSv4mlZDYV0Y4bTNhP0rbJ6gPDyxBfhAjUFN1TtjlEtSW4ffJiao7xiS+Z
Fvbn1bEZGGmEYs3dQRhpc6GS1IBRzMKIbXywH8fJQGHXYncfUTyT9a3Df0Txj39/
4V3xQxm0H0+wTHrjyrevHN73HL66Gc+yUiROwKmqik4XBHn1d4XTR27kgGE769I9
oAhsH/jLi+40W16FKs6x/0vEyqHy4AdA5vUNmp9A9Ft16XoeicnYqQPa6QiP6FbS
QwFNjaVkJ4sYjcxtoLmrwZrlLH7Ge3xjf9JwNm3lnYOIxrM+P9WeMEDbR2AZ5NaN
JMMPVN263n5jm0YiaC/hMhGhQWU=
=4VzP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-b208-4368-8d3e-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAt2c6Tv7B0FRIZaqwv3uI78vz7aZNkaLR4tvmTAF4uknv
QmDcbPsv6gj0+WCxaqBZcP3Y7kkaXhkcJ/zkF1fQIzUkSjL113NFmvAWaPxJii57
0GiX4XS7UrgtHIKbBAxoMyf1IYGBRwkovhNQQmHTPk0I/70qPPib1WaGvDKrFDv8
rQ7BZdaYPDKsSdrcDjSJBVYJs03hmBv6pycFlsSn7TRFvTaclqIdBcJB9kwMGxZp
uthWQhOuklOSYsL0VuSEB68ijdMaw9ztJHT96xsVmbJexF/AuTB504x4jfM3gikg
P5XiePPWOa7ZQTlvj+9Fv0ZR6/xeUw1Nfhd7ySiINIc3ijOmfdfHTE3pVbZjBCBo
hr7urV3/I4G3Z1lz17cAY+vlKad9nX0cDrAOq3mm3a+3WmNmCTy3QznkbLawFuIi
IXOoZuG8L12953G+ro7Fhfa/oRaE99KBncJPm6O9OMCTtc6KF7BuNRZr2OPVzJWt
E5PoGHWEDW6rhNELpqvpE9ISkzpK6HVnrklGUMWehWJ0F8gLSN959BfxsVNGH6LS
eaoCr1nC7U2ir73MTyjrdGvRHVKOvwYStIcviVjimVhwiiuqz4QUjkA+x92SvkfJ
lA+xULQ68unrJg0Qw9t1Xc70CzwtJdNXL6/2cSBKV5vx2BFcNcYtuytRxroeumjS
QwGtqTWckrkIbXSFgTfviLlHyf1mANuMfMoLrwKZYuNlAZMBD1B2HIqYrA1MmB/Q
JZw/eTKccbx9VYqZRo7R3I5viIo=
=L587
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-b688-4728-8d07-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAoM5oHcnX80kCI2kfauSWVj1znc09CZi1ZAU3Pl/dYDt4
g2t2RT2VL8UttMDuznE/s0J6gFGoQjXoY10Mo2VsUhf5SQ8Fe2Z/63PniZLXXoBG
uEs2dbMhUyY3Y2tDSml9tJ1YFezJ9ZWypeXKZQ6ViRylajLAJU3/v6kxb//8ejUY
O7jZX1BVilnKOyciotSnQ0CBqzUTK/87Z1nurI8dWRaN8g6TcErKKZTYDR8o8XzJ
XI1X7kSGoqa6idVrBJp/GTWT27ysqRiCWmN/fbTedYUP2G+aH0sfYORX/T3YUIgQ
OV3Nc+R6y3Icvxp80NsBkCZaaptCfIF/u7Gpd0OrGwExyuJTPJugqME28QuRCwy2
Gjk2RVqPTpPi6UbR/qJqe2L5d8vsFh+7NZtk8/HPvs43mXWC2evFmN8mChK7tXzw
EaVQDJzRAmwTzRKt2LgBl0Q1zU8uX03Xuj/Sdinm7d2Jsr+DoNadjwL1b3f9dhYz
hClZpTcfhHk6kROIQa5MOLSlab/PNiPTM73bruLcY9svDow77Y2IgjVkdaSXApYu
ezzw1td2Ut4nisV1bpY3osJpzafyys4o4+/89dzgyWjzSviF1sSmZsgfWp+BQ11d
ymCjk4DPv9RVz2dkOczmAUt3X9EjAvLvn5EVBeWAHWEYms0MPW1qJ6CrXWVznkvS
QwEd3XSaP0Usw/5/aw8WM6HEQ5D5rEx2+dGOo2AcYJT97RrBZ3XNrFM2FQeZvmwy
WjI0WXz7VQrvn/AkuXFvtDkUhgU=
=nWpG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-c9cc-4727-9752-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+Oe8apOGOf29FKSnkvB9KeajPZm3kDt0eBKWqmr1PC8i8
i/GnMN8AzsYCFUmPYuxboWCNqOLYimzTCSLsO+OZqygV6Rru/p63HjGbGDapKewu
BufGTur21at7zmI716OcRHOQM2uX0YGUwH6YVt5X45hNQx+hvrglmcF0hnIYlKeb
fIzyTii++VaE9GUkUxANtxwAUuh2uvbg2PJl4b7rAB6KLarDb5lo+s68Z8jTNmEa
Nh5Yy1yCIzdRujglT2WP1FMhpF56RmiKp39N2jzLm2+K/LTqDAgQg0WqQgkmINh6
Q67ui45KNYadg7Ed9oZKTlDDYQfZN186K8eHDah3IBYJwuBdA9P/Hcl43RD2z802
S3BhSNLQbm9gu6e2aeshmfc6JZO+JCHJGuCxNtnTeNfY8Mqj7agLxeQaXbJBflr3
fzh7D5j9anDNPcq9PvLtvubR+V8q3P9UMsvs+ibINj9SOSiS1f2YQUa+ryHQxvxM
jj1WA9X+UkE6nUSTDR4r+qIG9Z9REfl0BRkK5LGEECf2nYOdUDhAbMc0UIhzLqje
ueyTcLhzS2xMZfsqtBR2VHC2JPgW+gPbcckCgP0qhOy2VaSba5oBQVgAWMuj31yy
Ygr3X4JuSc+6aS1wSizI39dVyByvjrxsftv+z8l2VE3eFmG2nwkh+IDo9LOPj5XS
QwFMYQCvqVP4OQr+bNcrnFHqRZ/ZmQlGIMJ9NRmD4eGI+GxjyRB5lzS0rgo1rj++
kfsr5U7ZZtzSKKOEvouGS3sSLm4=
=GgDk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-d368-47de-9c6c-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9GRUd4BwKfwniWaM0TsBvMinhfr97uT90Y3Ds0C4FYb3W
PtqjBIUcInaP4svAQITDvuYw8yJwSiiqVAlaqcpM2A78ubaq2R/ocnTpJKZK8u7s
uW49LE0aRL/EYIlShsnqIcuacxpJ2VGGs1pkET2E0iXGI4zRB3Y6D+1w0RayR+Aw
swp7NB8MAZR9xckUJvuygZ9Uyk1oERcFW0R2XOK2oyqrFY5Ph7V9Qh3axWnjy0fi
n2Ojo4QOC+DqZ2C6W01QeHF6DktCKBSM3VvINdfO3S90DOfjOQbGBgDcCraUxMtQ
NB+qCiSJPgZemo7/5BPDt8eaP27n4K98gmAYOTRRcCdZ80c173xDiDJ7Tg5sQ9Ss
nKSMIfhiaqeY32NAN00qG76VyQX8RpuPqg3y3m+jF5kbQEwzBOrfj2mUbge1Z6rj
ywp5SbWwm/dAkrzRbprjkCp26S+MSHTRI38oPzaLFVnG+1/bMtrIlvL6lsdFaZan
FWm4CnT+iKYDAuVDQwTPR8dEPWeSkHrkI+f7kmJNG0xeTszdIhAs1++oOLKrzM6l
sect0i3nZ5ZZp8KJZkk+AYsKuMMPIjUKVL4cn0/wsFLc7FdUHX5NzALRH8zteq5W
SB9z+/PebGZYiG5PvF1nPCF1DtwcEXAiFNb+cD6yCJbqHYgaR/BI5poqCJotcL7S
QQGKZA6G3QIc8yGf1fF4kGqmS0dnXqJMdshIDSnozs9phR+YBo7MUfdEO5MiRz5F
ExhcXm91KB2uNpNF6O0Y2UJt
=rqDN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-ed80-4c8d-afd1-0e13dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQgAnWBnB1eV57kDLTEkhS+7i3Q7S0h4x5qE6IrP8QGTduto
UgarWTBXdVHIZdv3U//Bfa7akUBGTmuAivViI0WecigUKJKw4ED6dpJdoBwbU4y8
wFIVjp4Kxmuzekw100UkBy6jQCLDQry8zKvfUX/Hn+nYasR/zvY0azm6bwfemmWs
IW6Lokd4bfSD/8490+yEMXdJJhmv8Qfoxr2cuViANTTGstE0tnCzB5fau7UrwFwm
iIHDElmEIhD68+bnWaFAqf+WCweDlZytIi79HNfhxfpFhAYzwO6inx43bg4+NmuV
WOIUIWTl1UYKwRMjhfW0LebwSetWvuG4piltMLnGXdJBAZwIgiEJlS0EAYiwTTrP
DmRFwtIv+bZNDaLwQ+TWvxnoBXtarIXGoeSt1dn3kfAJIweNNLAvPu8ZFjHdayqw
1g0=
=gm9d
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4e-fd3c-4163-93c8-0e13dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAApZUuE2edrI7iexN7ue5NbwrmWdjMeJPyhTu7JSEbinIZ
h689Id9Co9zyab60ZUqSB6OI/E4zxE4zbXFv8JWC/kfRVIWyrOb6UfAqpHNfWePy
/MsA6WtekKl9ZcTwInfSrDxTpb9cn3eXHdVkxmUiOSeSgvhowp0mFHIfQV9SRqSW
XvskpE9pquWhANFkpYMbj/sRm2waPFCo4mruq8fzfnnsIwX3GJg0uQ1Gl+prXRqI
qbF8SO/mfN+VQMwOEouUxFEuxJjANAjihx+dHy9UMEhEG2BqisQPfhZofcaD0qd0
PAC2zEZx+/8dPC6ZJOnSsEBWGTy4ZpnHS7vhFcMW1jVc0up9YwZ0xy9YQmTSkPja
h0AJh0hccCHnItXLD64bZPdPHD6Ch+HQGYVPyIO4NsmTiJ1M2r3c8oebIBSAhQ1n
/H3YXYiJdMH0x4WcbRp+prwGHhKeDI0D1rKAPAuLId9UzECkogxiw1G3cx0rhv2Y
IgUPlBBjlhvDxCQu8G4cRJWHY14ACg/fekrFKx1+BO3rziDRxq7V4iJXNI6PAfvP
pglFLjncsIKe++VxCsz0BdfoOBd7z5WQ+aF1kvOCMnGj6Wvj75XmVI2gvHbWkmKT
XEd7t+xiK18bE/cOnZUMkR3n1wwavFr8WVbEsRNl5j40dtCSX2kvoLYidvn3N63S
QAGNBE+fYPerlhPS4NKWHbCV31TfMX+L+5/TYR3sgrYmigxUdnuAisO6XV5hrZzn
hQdTTEb9HjvwQFJ5Fnw+RQg=
=H/lA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55cdda4f-3d40-4e7d-8e18-0e13dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAh4LZDS/3gKBWHhu6uP8nsPa0I0E8+S4yruhF8Hikj5Zz
Yu4eGSy3ZDv+Z/wIp1TCHY+AFG/SLflqcsV1fF7MHHeiKQ9J1ZAlE11E56PtlqTS
PA7Eg+7j55joq7d3frkOTQaC1c/zZuH3oGC340MXq+RICjOAtnPwmghYtPMkOAcd
AZ2138gwJwVFUJsZrX+WgwsPpLne60dUQ0tkHDagsRIqOFq/q2H7+HpUYKlIXxr1
3tZW2+yFxAS04t7SUOYZ9Mq06NPosPJlast5zoDdcsxqvb3HYEnesuEeiVqD8KnE
kkZpf8ErPUZplg3QqEAlnvdMfe+ToqfK7caxOwaTqyh5T0AEKq6wSHIbAj2GTYiD
vAYXjCvZV0vzBZyEcdhEFZxSEKEInj/X8R6hQOaKcdvc+wFU/TdyD2lktnhYbxpK
wdtEMFYhDWhPifXD2KB7cnK3py9DEpTRVhopgnhEUufpFX1937gLws5Fbwk4JtiX
Q18XcAetlSQREuLypbjiURo+GAT/UI9stzQ+jkJl+9b4MGvJ8vtSuypR2A5Vu9Zl
dd7yXgW01xhaulZ7toqLNro2/NyspxQDDVqq5GoRgTGkinsLMcJ85dvjH9nv/GGg
yoErvuFHFpfWqw/on+cxUJqzmB0pwRa//tgvOrowtRBlkppMkQn4tHP2dFTI8ZjS
QQFX1vlsiepwqs/fUjRgmRqZcU3vUSQ3oviZmxvy5mn0binsVvrnFT0Aql7Wa6m9
chlPOxGGK70HXK8MT5Yc667U
=iyqQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}
