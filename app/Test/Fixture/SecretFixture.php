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
			'id' => '04bd65da-36c7-461e-a175-534955d43e42',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HtQ9ezW+v20KKmlziyS36G/mVze6b/fDI7ZuPPDHZwOQ
FLC/3haSkUHjox3YlUYPTAg3t4cx22gLd+7vkXztTYeUEAW4QLvNJdbTjxvYjOaP
KCH9ZsWUADh8I6exXxLl82urIfeBp2DD7AafpwJDor72IZvJ0XqM5Av7yEsET4sY
w2fvMrUXwNypIQ06CVJ2dJ8eZ0KXbnAcTc/Se/LgaHBjGZwPNzBNfNfLi5pg05iA
qvLfYSZqeKwSlFznCNSOq/pBuVamcteLOJL8DjYnUhR2K4GTGAKauoDFg4LE0nit
P1O19idQiqhiy9OgLl4YhOy3uZ2EBxqxPtPbVaowwtN8Ph+MIDvsHwJ/jA7H3Ndd
+ZkX0FTNuC7lf/B/skOM1A1wFByYvlrYfCetwbaH5eAXamMWJu/LHk2oGi4m6aKJ
wR3Yh0lOiX7zkWgxrJt35zxemDuEW5iR7Jq0ksfFwZC3lA8A/RxB88Ew4NDnYYcE
Zl5/t6RztA14m99nHBm3sL1EChZpZAe2YXa5DwMPo6SeLXXFllh1LjOkdM/x5OYb
wYy82KCaQ4IxgNqeZxrUotDCSeebmggLnO9WcYcUX7fUeoFN8g+kTHS0rdgu4NuN
hS3J9Tsvu56rBn0QoZLtEAZdr9aJcABljBs7AWLRVgB9uA4Drk6rdjS/QECjuTPS
QAEJzyY8J6ZqFX6IoFW4YQG6brw7ImX2X3EyAXbXgdWpnEu3Z8CnrOMu1MtoFZFx
i4XNkRwLQrqhYogUMumqvT4=
=8QMK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '05eeec59-86ac-4cbf-aaca-cf96673abde7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//baoYxOFFXTQenFdbJEtJOfgbO74EAeSSGav4o17GX82K
V4ulvMCpkZq3695kZ2SWPvRI2jkQi90UCoj0uclGkREuNYraRxXVvKuD8ptSzYV7
7jz2FOmiJaSDssyQqgSRcMgbk7l4jp1NNu5fkWtvPFqT84wCXCfbuQjgHCsuERej
aBSFefBhF68cftFNJU4bd6RcBIQ7HSdf/07Jwob47t8yUhI0gc/l6+89OjukL49R
3loxAOV2nfZsNjHKnLMf+Ejlx2CVOE0yw0BoNj9YLs2bwgU0a+ANmIQ8MY20rv0A
iIHjUSJxy5RGSdzoeGVBJms+EVz6SbrCsfYXbX9DVD/rn9dyDbbrqOkfphN3Fztu
A2lMVJy3lEO73J/foe0ELPEHR1fT01q99gwfqJJItvpcVyd5iXAaBEQ/PRX70esc
9WDU77xtrl2rMcZ8Lyj0eM4C6pb2oWyVg4s5z5dG8snBdR0QvvzrGU9Ocw3JwhgD
IJK4NnzTGgfTqAtVGw/D590G7u/d1kKuSlgL1mquk5JCKpwBy8vIo3o4kdTWBZgJ
dC5VlqrdB1bZwvQ91DSh95FpkGVX0imh98NaWCxAD3Dix0H07XpYesQKgAGRHjY6
T5H7q2UbgQvb5jKE049MWuaNFihG4TffMqqHwMBRoGhFkuunJpvBUil7OMbmMrbS
QQH2j8xKGXt73b7+ynew0SJRoB/+IS/hF2tIxPUdReOwPObrKy1CzwjtuIuCjbTE
okwXfTstsxolAsvTVltkG69x
=YnsJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '11835c8e-ac37-4ba1-a768-4029ac7bc83b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAs/I2yC6J3Cl2lUk8ys6wIDwK77pLwVG3g1ZGnospa3Cf
DBuQrRIaZFNDcpOXXwpNTLIIPMYziuOIhN+4bcQlVCfkmqhbx7F0u6owYkIuyDM4
e/7ZYjIjeXUVWkFQ8WenICkP0TPTsxP4mRWDt8a/XASQqyyA1d+oR7AsaRhrUDvG
fc0mgRaNtAqHZsvu3+r+hxzfVzuylM8qLH64ShmFSAESWNgWnD48o2wvnw8geU8W
iljEjm4LjdTWcykihMnC2h95Bj8rbtNpdsUfX/mlTZyDkY9pgwalWHOiuHZdVnOW
f33uDDmeaOqjjzGkCakQ2aUKVHvzQuhhAIIIGST49PgkqwKsz0lxLJOAPBYEeayM
pbE69Tfxcmwqa1oRp/5YPPSSkRrvYeMcVJrnI1mmm4mWjJH29mqXmYmv4P9/LXxT
Ghda0hIhmgSsCfajdso//W130vbd0uURGFuovq1l9pRGBqZF1Fz4h9KdJDrZNXOr
F2fLwPIR8R705xfsJ6vAKDBISuxpee+GnZEUHBS7ViwbwQx0hbYPTrQ/hZculH07
nzXruWVUgBxyIBP7iaAyn8DlsowdM/C0eKAHY6EPHZvsjqDr8bK/hjoZf58l6ztX
HKZ2ltoCA0/G3RNCSio/g0QMUCUsvs2rJFCiN7O8ygIBZqtun7Z38KeuhfkjG77S
QgHUqf3r6Z6/nSCautx2h54rF+Xze77PgbWA1oVdgbB836lJpRPK8YAgNCTF3cCm
kFKV/vAHegK0o7TtD7NUtW6Xwg==
=64eQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '138239ad-90ad-40a1-a190-3693e2a8b8f0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+O2OgEJQnYX5pMa213Py4i7G5c3gyNn5SnbA16w7AuLee
g0RcoNJiqaqYI2Q9Jiu+Z1P8nbD0KpxAc+0E5eUEEbalKvSkJbC/9U+U6jLvnWjK
jX4YXDexEb/qV5sL9VCktpDAeTVpPy9Jykul8Hz2Eqs6vfmFPFRJAm4H5vu98Yc7
TKZJRb7mGY9/IMlfJ70VdtB5U4sjVF1AnpLseyN8NpmfQzCL52JdY/+nettQMqY8
DMTcScGpXMkumPnwBrtwD80ehWmZ3jfbEEpPYyleG2ejCFesF3LE8amGs1w1ACO1
bj6aNRefw/rvOkrJtfTagC9mi1OEwS/Ixhx5E0gWmnQ803/o0TrCdxXRg+aDCyvk
4AkDCbHxgUcTiets8m8E7mTSy8Iqd9p4Z1EIDBXepixvBkSLqH5mY+vRzxR4ECEB
wmkY+GhWw7k63RB6xCU4S6dBGhetKPDYOwdGjn+eTgtlVltJbKgqNp/bdCtIN5vo
3dqVKgkTRgC3bCtKeXocAly55VeZaaa/OnPI0BnXimPbEPso9fx/bzu4RZIzVfmv
PtCQscvHxotwcToXfAQKf5S7QLfgV/+4qqvhWT+EROTokMi3PzCY1vjMOTEz6krh
V/nOLNkzVjeChx1BW6mYAWEZrkFlfh/hX+fMUkbcHfj/MW35CmCUhC/EvzCL1zvS
QwEftpgw14QsvbYOVY1SOg7I0e60xECHXAaZ+VdKX6nlRMWwuC2GXdmGEwUmwluM
HzHS/baOxQ91IjSYfad7uJi8gmU=
=Daii
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1e512db2-c258-4c79-af54-61beacc51529',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//bnzVixEjuCiKGzW5lB647AQ9fU8+ESgmyOcVXjZ/SQjM
gWoJsBiPJLI3Jvq2q0LYANAWrkRkcgH1kFngBq81yJ5QGdlzgWMC5eHilxG+S5Ah
PYAoVmiKyP+I9c2q+WqvjPglAa+R/hy2jek3eTf3JgxNGP2LXiPvK4lBcAGDG83C
1JeLwXKThr/5V0igNx+p84EXzLY+ipHVwAOpkdfVNd78graHQK+XoXuVjuM50a9G
pBKH41PfW7+UQs1DrzA2jygmVwrs+fc+c+qRK1hCoqqnuJUHlYscPHifPOl3R9ar
CMVckDtMpXuIp7/DK16kCxepV9XU7FB1TZvzJioWr4fPkjKOY2ebOGbN294HGkyU
L2KG/KACuL19H8/i2JoiuooqVpe2kLYECRWJ+gJRA4vftGQM2BproQjvMbr/h/FX
L9+SaZv5MPbW3fRCjowbmLpCojYiCpGPYBtoesUiLa6XVBHTUvlfHq4uuRFVCPkh
Sb3PHuaPmAT6B1eRIkQUjuTSDStVmIk95HBj6yG9ZHeyHcRue2luSaYxqZmGtbl0
NuEOzfu2fKu0e2LROpU8sl9xZOKovE5uKd7JP2EsowqsgXUjk8+Cg4Ix9Gs1Y3SX
ymIbsFyTnzl8npzXPUrWyODpDU6zDWIhb+PZXy6f0AAeusGiV6htr+UYBOUeILnS
PQHGzoaohAMlntqSZ6eU4sBSW5MGJrjH+DKANZIiwgbSZ2Wdd1yl1ATpWV+MeFAY
515pCtSmuUhYEbGK+4I=
=lWEY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1f7586bb-5652-4395-a5e0-57f13dadff4e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+JYpCCU5mpNLIOVMBAlwkK9AWkmm27m5PSmpuhNewnK2d
dRW+Pv07j/xx8ECRT+XEhfKn7Ab/KcgGTERLeCpbp/NDWBeC/VJcFyeTN1vey6gn
68Ges/Hj0yEieTCHRNaY31wzGT80J899Utw8jJ9im4TC7zUkw5uC5wYRpbEXRm3V
3nLXgTwHwWAUV04f1ZaCNS0lwdMbyD/Mp1iE2tyojyrmemol0u4cUyqt+cFxZ7h3
UAKY6BusuSVdYLeXcFwGrKXp6P9BukL2g1A7I/CM863NYl7U2ERsJmmBRNTQ0KXA
ATUhedcBXSDhakguADHasGR6kNIJMu2yJG97IOSgEmqER8gCfTf4r4vJhq1NE7fn
p5VhxsDBYcR31GNOXM1MC9M7VWnMihjXhqdmYnwIqnNZ7+hMl8CKmtsZMxnztp/3
xrjOPwdkkyBUTzfVw9QMdc+HlywR3LutERPAb10tMeOfuQOyFJv7+2vPRbXCKZ8v
j0HfuiK7fPbmadacpSI6eBg43VWvIMSM+a1vvnn/QzedmfDSdeorrxk1L0LdE3Gy
4I0rwUYIePS1OyS7XTcZHUg+9No9ymaKTGsTLFlIpM9g1Ct8mcpQqm8NKB2ASwn2
xbM9zviEPw8W+ff4gUTIWiNKvnrdyWZp3gpZ8QN9zTTdn0EEmXu/sTT4YdKdn7jS
QQGFeuC/lGxnp+QZSFrkdxLpH644db6TdlA5IPz75B3DDnbu9myjB4pkBpgW3GHw
Vn5Vl8SZgJhCcgi8o8OZelJj
=QSme
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '25b00eae-dd02-4d4e-a3eb-6b1a8c145c28',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//TaPsH6LDLK/5kl7TkXalbFkYcSL7CSqVBnZjUM53zFLW
aqvEAWBrE4le4iAabu5tnxqHv5IxTBjIymbTBaSxZBt64Vlbg8pD0oBPTS1bl6s/
KzgHLRDRBgxjUR6aNvbyAQ03Gtl24CwXIEeAfLMBcprGI3je3CFQOLW/GSR8RLU2
UWGn1Wh2pBbeHSU3p8/i0m3CyYgdvE8wMyNyeTk5j9CBd1hNk2OVRLwtjjHJAi6T
ovtRQZZRIVs9ndBP8y/McjQ3KhEeDyVaLEccnrvW9b5L0BGxSj7sm1c1lQmiRa6a
tGDf7qcb8efYXzFPkx4gVMMN+jvrmzVmaFmSi6KoAGSjCMxV9LMiUTvpbfBlOQaL
mE4Pp0TMktzwaVp25+q+0j5AmGyRLPW0RBh5DKeCpBW181W2jwAc5jixkcQPTex9
RxeggbZMLVlew9z7VrvWkeTw53WmZgsvyaioNJdsO9xB2pYqFrvvtp7w6+nncCLo
GvvedCgU2BNMmzbkbLGHBxkkfrdlIkjg686lIbS9YbIThfjbz+Kq7bGN091eLaol
Tnt2VZnJVdVgvKYD37NQ4SS7yPfDqAb97m2Dw6iVsfGW5NStSaA/xOb0l6j1bVOt
zBdp1FNnmQ9d9Pngc9lUyk0WVtqldN+VOPK67ONmWRWGn5Ru8gbJeewXSclfdLTS
QwG5tVs00HmOoDI0cV4mHCevOuxuMgWjLu7mE/o4YRZ+lwAh1vECVdLQdNmFLLqD
dznVaR2TTLCNDqukK7TpjaZ7BDQ=
=8YHV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '260cb383-df5b-47d7-a1dd-9e6e3694468b',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//Zd9pzquYGq75eY1wN2ImG8k7/DgG5tGcJc1wCE+OzguK
h2yFYAbyvDuRLpxeb4ncZC3SFbXTWz0//Q2RxW01mmOnWF26X74m9/lP46iXa4IW
OrFa6n2KZ1FLmexg5yKcirdMY33vhkMHuj4FDzXKA1HZqTQHD3cY9LVwi9wwXckb
Zpnqgl8OiL1Y3KaBbE7YIgYuid/jMosuFBeuN63w3CL8dv0r2KB1Iw+JZ7F4v73l
6RcIzzG2eGDH83Vrp8nZNz0G+VFyX49wkhxKwha/Zj2nJHO2SR4KnBn4RbqvxPpA
JlMIdGAVLjd1UDo8oeYAlMlIjgfkwEdSN+U8HBPXExqy95y6okppzOrdnKx2Ygup
L6RkvAj7Oh/ajEpmjEra7+XY7qbdLNW0ADEHdrC3gAXIfmXdVgXlNx3t4aRIt0Fm
eMsPleXFD7g23fg6jg3vPXYyUG9YNWB3enmMArjOePnEa6a0e6481WaLpZrTWz1y
x9rp24HnIHXy+IfhdXNaOINCWz/jCgNHyo5Q2umTeD7mHt/FKOZwuIP2Lx00nKIn
MlLqgfPeSSf4Gzv2Ta6AmBfWp4AWArfULRdvUvKgl25jHgkLUOv3oogy2uzAxUVN
R3QYcouxIHyUYmq18lUfwBejrBCvK4uNNmJzkTZnE5GBI59luYcLkotO7FdmVjzS
PQF3gEG0p6lbDuCROBmL4jEK0xL54NoHgMlZTdcmtI2TyGc3MbKqGDshD9/7BQXx
m5mp/86zF1MGbl9CmVc=
=mWTP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '261fc6ee-0b7e-40d4-abf0-2691bed00fb0',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAkhqrwQ913ISsX5hkLDgLZ2HaID/IKKBlSNbHH5/TOelF
ScMA/lvX9liMRYTJslErILAiicqa3/TgEM1qSHXkHe0c7os/X5mJz4Y9Y/x5zuOm
jn1ypK4g0av5/kA1na2qh45XSmQc+8//+jTQnSI9VcvDUunvoa6arJvr66D1HCUl
hnIqIgqJFr+RukksINABPnonGDAjptqMd4YJdsuYyBjWtu1ocej5Ub4FI1DyOQ6U
mYkBX8z+qD7LJtpZIA/9BhFZhvy9Vv5MmqPjdv3xnp2WBM26qriaiJ75wR+Mx32c
du7R6Nd9VVpQTveJ5bCo+SoQSnseaPg5EMksD4xyRNC0YbcQC5wKB0eKa4tEbrPr
BMXndIQ0pw9T/Z2Aa1w+wI3f4ti4PpJemtCgMfA9abO9HJLh+3Lc9Nu55NYs6Qu2
yuyDnoiGzVqpJ8bJHKT4QuW4kTdfVgLe7faeVDuSeaWB61aVZp/Z1nLigotQc6uv
qFsJqfbiPKVhGYO6hjnLvUWn6kPPE5qSrSax9TLmWGwGbvVe9ToOJJFGVmV2rJL3
21cQ0NjLN0QkqDZGPpEbAK19q4ZdUz7Dro//XxAs1kqyFnvQWRKj0OiYK2929sK4
chCFzn42vEx9R7ltzikG5pAsaZ2IiGuWCmgsNy0rExTAKXZRTGkAh7EUSHwkXvbS
QAFvfCpIQ3N0LeV/fonau1+mX5PzWxYRySnlNvEtqphxYQFRDBzPUTJFU6kEVzpz
+9Mvk7eHbNsCFeyJ/zarA1o=
=Eqk3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '267ce6bc-5302-4a07-a974-6f7f3df450a9',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/Ruz5NeRJWZdbuSiHsZ0soAP99PmVsIAuiJdlGIVL7y2M
6ammr3S/npg7sHkd5qnq8NztAT8O+XHJHTljQcaqGBJlMjRiWfSxo6iHAGqX1aQs
lWG7IUp5iWwgqFK73hRs++YMaAVkJ/V9YpSFVcd7xcfLEnuKMfvtbNkK8TkYgMIg
KDer8MXssTpxVUeEmZZ+/qJ8LXTXnnP1Se5EOsti2a3r24prLoh9Pg5692DakHfx
/6vaPvx3NwpqNWSLHA//Q/NOGB9V2WVm/JnChVmZq86boDeFdfStS254NuB1yHEt
eDM4wo+u9BbuKoU69UZ/ybRNSTWsnB6ttcjFnS6KLtJAAXpmnDENR6t1IQ/rzw20
bsBqGaD1zIRioO5Fq0ENVbZ3cZuC74+Iu+JY34HX/DFL7o+E9VCim8px8ejYgiDy
YQ==
=ZIhE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2af70b12-0a50-4c2b-a8e2-e098c0f97284',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Or1q4t1YGtMDIJa85iqSFEQjaWZwl72kHufQfN3rx1BL
otNhI4kNO7gxMygtYW0vsdgfjpNP++FlFPE8KVV/89y4xnLxHG65xGbDC7w4p3AB
YdK5wMhfz9BjXZbZ5eZBu3cZRIo7Jy3rTChTICOt6h+lKaK5qDILUlWOh8za+KLx
In6BU2u2hZau1JRbQkyGYwtK1DgSMXEALcZgDf8mSvHCsHFO5iw7XFBPeOJV9/96
JXzm2ByAa+l97e7JS22OVjvw/P5T1Xrf4sfJE8Ox6VT5RfhO+KEzQLN9vwbuPtD+
rTEZ1eIne6B/GHanSDRtaxcbRhamZHaNqwzkRmaZjnQfcY7Qpj2fVsf+s3CXBiCR
WhW23qs2jn1u8M1EIqHZAK46USQ1i8N+RHHlJXKhYFKgoZPEfiyfq2Bm5Acdea66
LYgQ2OCFRFFzUgJrVtj1csTUvD2XrTFVda+Df+JyKkWfMBHuISVUJVma7ZCIJZRb
zXzQt4OoejZdDxyfaJDjgt4tRWyosrMe7ws/Wjezr0MkIEq/9kc+TmkHZaXuHM15
8jc2bJ1OKdTrJAto3mPIXfbruZv5je6P7Voqgl/ue9PMfCCiyPq483nFJWAF+ZdU
ACWfwpJUmSAKyrc9YYD5cz9MzFP89B8+Nial9jb+xnWu1aE/6I7xPYw9QNS6Jz3S
QQGUEFpdbw9x3AzUIhfFMwXljtxSZnaI7ETfuEnSXLcYAKE9EF2dBMfrCFy4HY8P
wg43JPI9+HZEFqIo6e9MQLTS
=aFmq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2bf2df3f-e22e-467f-ad2e-cb00086548c0',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/XNQJWokjMiiIWhihVUtkyG18iYLVACsWHeDZW2m9afiz
cHxiPuXfPsTIQ8VdNpel4ZJ1/aVBhRyInF07fNZnwTJOPLYueYv5GSqCeAE8LmtU
uNQkz3O+L3i+V+Q3Go+a9UR6PbecGE/0lBXaD+3Bs8ewR4zv7Ezcr+IbFM4MdRog
KvEggw94JXu+dOEeLNXjhuS7jFN/3RovNk+QTI7FTns2/W3CGm7uOTWYCBrxr6FN
OVJHyROxzD9qFk149SQ9tqx/jALGaxQwQroCWL6F3r6DJc/ggPqaIwIlSbW6WKek
ZPBbp1OuhWYMcs8X8UounzABZQgJ4yuN4N2JKvXjqdJDAei3PL/Sw3RcRjI4kTJ9
PvMaQ2Oi/kb1mUyMcWNa16o5phUDyCOaNV+JEygkyF+e9Uj58PdLPF+OoMPZv9Hg
472XPA==
=dbNZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2cd41ce6-8554-4db7-a4a4-5b007cdcbef3',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//ZfoYoy+zpPA3jOU4DDJMsTwHt5KcF4Syx9i70ubOxDBT
gNU3lKJ5wAdL3ZPiUk13Sg0CPKfqxbMJqOUcN6AqwE7pOB97N/qTjTeJmCFMXU4M
s/gf65jjTRxGwyGFWMRab7E0VQ5nGuKZ95qVSVZBDfDbCfGVBC16f35ZrxWKAnAw
tiMIFK1ponEcaSv6RegQ4RtAoXhAtv3Eb1ruIgRFo4wuhzKplYk7xTWRsW5wxJIm
q40XzGV75+LgMgKUgEIAO2Oyd1G3yd3Zq6ems96ZV0px4S6zSdHExO4RAlFHIovO
btHAqJ9BUmwIgulX4zqgzKknvpCvMkJRIv7H0g04sMcSogVpaBup0Q5I7Rf+k1vo
GsEJJ/O/veGnyZg2lkKKvzpjwEn+ESSrqLX7nBSSKZS2Cf+fU2+Uq5vU8L/IRMde
bs7mkIlGz+d7halGjk1dXqTguLtMr++th/GWiT5gnyP12VioVe2G9dcJyM7zVE63
mm38bBQRdaKqxpk5DWlIxo5S/YgJxMwvcICtrbHcvNjnPzYVbLrnegrIpFLQktJS
SvY7WH7J1cQvxebyZepV+A7FlAlFMA1P+HStdpjXhLmYyZcu8EYZFEMSAXPRidHz
k2WQkVBFTqRi290q+m9qErAENMsE6Q2T62NeSgiIiO5rt7Upn1fobCdlNbq6XULS
RAHOV2Ts/U6IjVClnRkOU+m+8oSm5t6igqeN0Jv950FInuv5IhuTQnopIQDuEvQJ
/tbjFrVmqjgKTt4ivkUiCM7GEJNJ
=Y269
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '34bce00c-eec1-4023-a5b3-3f2b1405a252',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//TDcB3Xmw1GObIBLc0+rlZgZcOibLBjaP6EKqIryKkEUx
gTe8Z2tw8uZMs92gfKF+7jDu4xY1f0gudasEvebhN/lOHT3tG5ofwl72UrqRnt68
8J3gt3fIf01zd4ZN+su9JaNJdWNGkxsr74SBdGmV3wIMZYjWf+2Z6IWtkyJes6O0
6jRNtPt18Grd8VmdycD39xeowYQidamM22SnhOPDfUwQNElrT+wW8jIswi0NT+Pw
fenstsKk0hF+gi7pUyMVeFyL6EK9WTKFt+paSAjQdz6vHwa5tiKYSuvuWgWQbWWl
PIn90RBBlWAqLAtFl4ttZtuNYIEUKhaUrtVbuT2ILPcS+ZAfLdQGXMKVujhFZT4h
APAlEkJe9OULplapmNE1nweO1AboXNtbWKO9QNLMGH5azlvyid5yO0c5hB4dkioY
GFbGVtDhBU5hVBL7XMucXFEDOjy3Lhb6mUNBThBpmn6sMYFbYLd5IXAakFkqNbGM
KZS/HitIPbHLQaIl5BT+KfXqH3XRiQ7dazZN95WBVcbAw0bV3wKp2/hjZ3ZE0ZzJ
DQfbopvCEDRtATNe820Ae4kbgxFpbM/oD8lYYNWEtP+xJg8OTJEzHX7LI1eay2E/
F8bxxlzkdy72MMcE3FdD4ZRNEW1iSpRLxsx+cRi4hDiQFcHAZvHPQjE2ctpncyLS
QAGe7CTZoSzeKwji0yztU/hweHP8eLet8w2AeEeLQ0FG85m5MVlPbyLNjj7Tk+uh
oUUqAss/ciluN9tDTCAXK14=
=WOB6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '38672eae-66e5-4e21-a147-63dff6f286bb',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+PTGRI0X4pj+ABboqdw06BXbDMTzmL00PbEirvaMkVJTn
M3KnUBFClovpXEV9Xpdvr+Uk8411FVCEIe8WWHOxOUfMzxFyKAzaHTRm2UIDkzVS
rraWmYFimJ0TppGx9X/2TIsfb/aPXgc5H4KLSV+2f5KSSzA0JlVEUvQxqZY1n8au
yVR7eHBHg1bIiNwUOYZPIdJNCQWVhzVqE2+0Jjtm2zzdiyGBR75/JtydE2Gi6KZJ
xhBnUdbVPZfYURpX3M1B6C3KlkIpnfe62ww5lOP+Cu3wfCe7GqpW1orKYRIPq8//
pd54wFL+ZC2NtXnktY16gL/ZSCbcJUPZ67+Hb103TNJEASUDxCj0QA5qH1/EeP3j
2Si0ibXmIJCfOEqsjkN5X4v38HI2l2hPB9QimQcxjVY4km64Cng5WVe/uM5N8FjH
GeP6/X0=
=riHr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ca32c1c-2fe7-4b0d-a3e7-c6fbafaf8d24',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf8DIefPrBztvLALH3at4eKLFEjfsx590A72NbAqyOzg7a4
vuruwYp9jpIhBGX5iKjH37u0AGGWQZz3ek38n9FitfmAzpBmloWW0caoNohpiw1L
qM+Dp/6I/UdM6GQmVF1WzC8kiR/cfJmoZTgJGwuOzxwhYF11TuWuBIxAwq/r7MFi
gdFLjNCKiYgkrEy1TRJfSXQO5wUWUkbSkpHTidRXYlxo18Eko7ZQmczxtttHmSQI
YuHFnT3ZG01bcD3MdHfeAiuv/MWQXUt/qfdJ6rYb2iUPwnO3P/ElbLUhKk84QBX8
2Ky/lGB4dA+vVJ7hVRKvHF1LAs+YoF4SIGwf7I4gc9JBAan4D7FypBLkB8ULSF7O
1LJDEWnPXLT83lafi1N7rTvw9h0M4B4w7NViOBJDFRNM/t5BSF7PdAtLGLCYpNoD
RHg=
=eq/X
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3fbd0100-00ac-4b90-a9b4-30ce011b84b3',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9EO7uw1+SY6TkPrhDu8M/8mxnEkq84x0HzkXOESu2g1Te
H+kHpk2gS1Nu/j52c4rKxneZ1/efAXSJksxV6JP+YSFBUYxnWdx0UTn7yxSHxNyP
fZE3d+VZsnCCnvGoU3RyAW694suVqMLlbfyZYyn5ngvmwmrpG44jex9XXCNhGYqr
1Gexr13XLBV99V92fl3K6vRn0+MBNod2k0Ke7I1yUanl3AvNfAc52IsFWI9si/6/
8rHEYEEqIbt3WgW7FHfG0ODyAmTw2J4ZvN+xxVtQv/UcQ3wlLLWEu4h75INTHy6m
VQpOnXXvJ9cGpCi8q6oek7indytYS6sqoPas6lIOvMlyIxI0tEUAGZZ0bMOMhyO+
WMXG1WDF56NnjGsbcn3HGyMIdJC+j64O8m0ECmsuy80kypvktX5mjidaehqujmr9
0qaYuD4P/VMs7ApVTHuLg7Ks3jbfHiLfdt4vFAIFydjBkq1iAMQV8HbtoVp11pje
83W4JlCbIiqjjbQbahKdqw4dQpqrpN9ii9BRMb/dh2//7anfbGwxEqEb2qUX1bLD
te4GCj+VvLLVVH23QDy5CfJkoeOpIwu/TDM8SInGZPoFKwmPWTjnagb/j2q17xfB
Pi+usUmbt51m0J2RTISb86wilhspDD/xnsVFFCF91an7YeXYk/AD3hCqwH8iTgbS
QAFGpmKndnQw/1TSEYA+KDk2HM/5bobLKAmUAjrBWWzvdC2KJ4WxgKySbzgRX8s2
TH5Rp5V9HegLQFVlfEU8wSY=
=UFss
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '41780c3d-9f9c-4849-a363-2bab252f5bd0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Wwg68B7lSp+RDsfq6z/2vRhb2jpif2zNlLVzfPynAreJ
pe5adwdNYpzgu5SdybU6vfQ5Y8vj+kGkd65rcyBBkpfOXeGKDOquVPTahsGxgAIv
9zcrz0GmIT4msZfy4u7NRQQrqvo1H+pUqSA7wYHzU3KvTYn9YPK4VCAsrzKX02jD
7SaCMSUGKHZhoee5FnOZPUS8AI7HV4OjX4ohSqiGRQ/emwKEeTxRAw8d3PoycS7z
DhmUytLzLbmFybp86y6drdOiw/RYYn4lkyu0zzvC3ibbKbadnsk1FMQW1UT0szOt
a53KYAfPDwS4lAwHqHPvpcWnOzeiWjTlHP9swUSY2tzode/UTvM/vVkZ7OFsUhWl
cHP7/TEycZZKna8a5OOOy/9BEBNINh1Kiy3dyYqkZG8nW0RUIdhVGeA66Ci8twcv
GDqMDbEJpKIFAiPVAh5IjjCo5kKjYIzYjh02eRp2Rk00tchMSKhXoSMk7A7xH0g0
gV9lDxzAAKXSnR9EAgfT4qD2SDrwEWRoXQawRYCow0uS1oJ+5XMaLNrxC6arx65r
+geFf1MwoNwBZg1VkhdwdXoIlUSAsyBnwV2slpTUMGIBuZbxAXiiTtcZlGixjHLc
Tck5CFBotOMwNhvufK2oyfbTqDhwEsvEvbWDlSpgEJF/ynFFQasOLl3g+fZd7tzS
QgEIZk757eDx80N32Tt0GqPGdO7X0HhYdOXakfBVLfbhsb1rmsOj10p9DLgsdA1I
ZLkY+8p44jxTuVFU+guqf5xJyg==
=H2e4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '463306ef-b46b-40eb-aee9-5672aa9f47dc',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+OBHHnWmoJT+t6WYWpbmrzC9kmiNm1cesmw2ZUM3NMS07
Ew0HOGo/cBfL6OOJpiE9Npovg+Pnbc/AWDCE6e8TXAO5rSpyHJITPD39R+aDcj3Y
WdIXLn2GWdXHiObdCgAF2a8LKU0KvWkFlb8FMTKwYiLvvXDiDTKu3wwtUhLqLnHq
xnVWZya/Qo2+2ws5JhLOCWV3GEnbEdXMPm4ryaey3JyjH3hgk0eDU158AwqbgLND
wXafn+sKb8cQ1a5GkJkVJ9LsdtwAfk1X4w0Hw0KtCJAWSByJjlaViKJEp6c14DKA
PEa7Xt9197xHOccO3mlBUlMgC8grdbWRUrtbm2+W3sogVnXyPiwoPLN9QT7bNoso
/1zbVjIm4ufgZASVfEk0r0WtFgC7kOTA3HyqdqtedzHpkRyRpPmfhrFeyMp+1Us/
IfnJzAXAPuhv1kDUFgtYzyLb/1VOuTWdKlJu88Z92stokwbggtoZDku75AtE0XPf
NfklQZ8sjUVj09P90l5OHeECWdvmM7qH+XAobvQzyyYTYG8DWR7XuYZGIRxLDzV/
BiD/69W7d8MYxiYHZISMN2cClCNeXtffbckm0o7YPU4ibgm97qK23/SYQP2s4X1H
PNFjBEyuJAN8nfF8dLgnUfjb3t7UJijJp5OofN0Gf48bgA+6sAB5nnP+/dLS4H7S
PQH7YkCWS7vsTHGKZNg/qQQJ0f495yOY+iF87ey2ZnMnhPhJpT/F1fTBrZM3Zzlf
I+cw2XslQlYyu5so5/g=
=ch1K
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '499b210f-dfb7-48af-a654-c47cb3d40376',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+Pai+pHVOi9DSox6RqA57/POK2Eiqbw8RA1To3oklYT0Z
bd3NdiP35YaVa7dj7PH59tuI9CuvKwkeOf8fhkt/CyG9t99Gr94iEBXminh8xU8v
sIFqaJpAXTCAPxNGZyqdNTW7+9lvgZkBP9js79T5MaLS7mqZxWD6ZAz33w4wvnJR
ct5+5SCIQinyEeeuuGOTDcwHqB7J3396Tk0zerp4mmERhFOYS2OML9iEubLHOzwx
Mo0F7e5gWXoUmaM6+dumo6PL221rZyFmwqs09B20iIEqCJWMfzX/TejcH/+g96u9
5Fm4lKpmi0xRMpQuO1FIDgmjOemKRG34gxF7F+zlkjoSqCPvPm7P5rozFHaHNBBC
AYojOFwNZ8VNwYxvGKpAsaL9N5G0BSXWhRXv1xT4vp/Z+qvvUibWoJynMmCR3YFt
Oy69rdAfGgL6FdoHQXyBbk1K549f8VoQMoHeXmzb2+XiH0oP5kIvoG7DiobTKAgf
M3dPEhp3BL20dgcRZIAygA8yw9xXDjG2vnre8An8JdwnwH6jukd4vx/VZVGIo90p
zEbmtCymqNMYyH7/IjBVjzw/0N2DBHYSUpoCkp+oGpDhFa6nR+AUi8MD+YlR4gMi
ThJ4g4Ah5/2eG7ELknyfCbIDqzgI2PvdqmWEoHFHCBwmwcQnqP9oPJptWMWnQRjS
QQF6EGehj7jsLsrn7osui8ab7mtENzp8677Dd3mfLZMjfcWDbyOeWL+cIVi5+13I
4oXrO7s8k0w27j9ACPrtqimc
=cpa5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4eb94292-1ab7-4cbb-a033-3582173f9437',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Vi5Skkx1ZCDQaHUkI/8te0CFcmlkmhF7V3/W+LqGYL5h
V9uqc4YSFV3tCSvbi0zs979mCA+CvHJCQX3KaJSGaRN3Hh2AUAvGhzOtihgFInsH
ujwsMmNxVMX7DqVXIAxvNmKXQyXxBZE8Z4NVRO6ZmetWc5VsUfJju1y5+l/aZJ7G
A1DC2njkpDktd1x4FOfRxRrqZ1YdncbcOuSmAlgJdJGpYRAZPUVBgUV0k14A5qNv
Y8FJMb3TU30fOpWh6UWD0Y7jVSkUTRG6BYNbnbhDT7L5e4ye1qKiNqQK+cSUXKai
r4VoQJOOJAqVKXOzNFtWZSU62naB6lcmM5+vLAgyapu+XFfCYONrAbAJVcxvx/5k
krAoZBwoJtVGB0xTHY7eT5mOifrh3Ml4AtwktKevjnpn4tj/X9ecM7mdYJfQwUeL
4s9t/PxePPVI2bMcYz8A3st5KzimC6rzNXRjUh/WvqB3MejBiZdlpuG1g/u+fHjF
wXKaTQl7+WkGHfYV8juF92nmTiIZ9woitKrBr3H0Mm6K/Omm7lJuyDFg6YbiEl2o
0unvJTG6oWDfVjw2In8Q+jStKhwjQkCy9fpGlm5kjGOS1TZnJz5Q2mQsGrwOtqZy
cHLAWUvBY23fAebu0TfXwhQKWCn5IN0fxxkZL9KCLTft8ARBgGMvDq+pKJ3gLFrS
QAGXvck3xrbZKTNUg3Mw649ow/l0TP6qvOnsxAQMeGCI479Tax6sIJ47C6E2qxh1
guHJoRLC3ehOpXMowEm4JpA=
=4mtC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5942e220-64ad-4238-ab3d-644e4f9d97e0',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9EO3bIDVgN93Oqd3guYzjmkR5rRGFzROAqvWcj2w/+7c9
T2B/D2gNPxHRbaD+dPrEzDabsRG/Vw6TQ1bTmqE48GRWQLIIIlc4NOrQKsTQdjXw
Eo8VJDEGwj1O32hNoMRLGKR+owkL/U7QI3Qu8yIOlvCsmwY5oArpq50wRJvFNcnK
p6Gk0Q5KVbjh2KUN7h8rb1HhG5wDbDdtfWk4fcs32K2+oRY6MMR6SuCyjVUdq8On
ABUpncjRbeFU9DqyFgaL1Cu1CgmRjMJLOSXgBa5JTSctyTj71NPjknJVulgb2RZS
RLWpWEAwJJLk52buaXdHDU/a9KEt6hwJDGxCQq1l7yEXL4P0MCgus1rlSnIohW7B
1f4PASgYyCH1SLioiLdGcPGtMuMK5ZpAZb2zkeflRLMZlTciQ3RWw8x4ghYuZiBf
hBr/mtI/7mNe5quay2BwhHyx9arJ6Znid41P2/NM01cssfrYyToT+R5tENEL1RAW
dSutRUjE3Nfy18XFwuElIMhR5yQ1J76h+Xkb+acaNZCrKne+/9h2+sWcs7tUPQiM
aw/9sdDU9xoNiy99UV1U4wQ4kh2oVyJIRXEhZMMpC0x25T/j63g9eRpgx1ee5m0y
dsICK9twjxhMbi/mLWWY6YRC5gqIaAjHQTYmeB1zqSa+bysxwriqGqX7gUdXYUHS
QwFShrM8dPThyeuerMicIR2txFbx5vfnO/VzcICakHg3F7K/4/s7lhq26jcWy18F
Zsl82s96T8YtFxyaeCgJ1uIAzEY=
=WC1p
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '677de62d-8744-405a-a1a9-f2e246f28c93',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/efRGagUpG0AG75KLyNTrcq8QxGIdqEbWtMDZL8ZuqZJJ
hRj1eK+A2psqCtzjdRBDSI1Qi1CBKjgp3VJ4y9kZGzrLjubyvq49iZC9wRqCs39k
8lAwcctPXVZiZGR5aCpjYKZakQNoC23KTdiNb3vO1brwK2fjDUMjgGZxtABAEqJx
mr/eubk4bl8STX5RnNru9j4kNb4ahhlDRlBwKL6U8hW+Yx3NO3sAMTRHO01vXOvh
DLD2J6USDML1kDRHH5129GkXdUY2rB1+dcUcpp37m3DgFDhAnNUMhn6VmlQ7ojZH
rwjtJyC+681nCiujGgqIHqZZsH9Z2LuNlye2tUYyENJAAZXo9cJM4J5Y69kOzpnF
lXCNjW6hd9DtNXsLkg7t+1LlZnjnBsvuPsY1/IrSOx9bRwJJ1FPEPeDrXr2hIvYY
Sw==
=5CeV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '687948f4-3218-4ca2-a71d-7736772db65b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9Hbols98nw919H5q2JO0rOJYO42c39xGWvnYzsb4OmtX/
cCsoHDATLwp6lZEOU4gu6uPJ29tcYTv0+hr6R9+0/Jsg30zWN0+A2gHk644jWA2A
6bMVUWG0L3OnQ98tva/bjSyrRuOkA40V9aeOKoipvlxqOoBgAtTDZqLI52mUgOmO
WI//2o9yeWp6b2iLSBy2wZR8SvkTENBX4mGXpofGU9054yzeLYyK30daK35koe9d
UudQPzTyG2Vm5JJ1NNZHY2v5Ejr0Cj8IVqLx3fLQB4my3rnIZf5ao2BOP/90vINj
TF/4XysPdT8icc1sdASqt4NkaLBWPb2ZW4tV9BOjpxNV7Au8olIgH2VVHLNYDeOh
pjeG0AJkStD7NUswozvmTyjc0ay1T4lrw4/OJ8zBpyt8sxvCI/FLLUyJEl3E4meW
Rhe3zPw7KwhKMlhFH5lz/4ur+lsgGXkgNywHppag1/Q9APQUkEji3KMgKQhkeaN4
ukt7k0iP49P0KpPET3I6Hdy124Wlf0ZYotfprgWplE9qyYO804an9/zyNRKGNrUu
b2nOtyIemC3ryabV96dzOUjYynEzDBlVpMPS3UTY1zjOS7Ig3UbH13iO4CbMQRbz
lDND7cdMMPryAud3wDF8ti0boCJcjokFaXOlwTX6WHyLqucqJpVEEKr1mO/XqgjS
QwEMuLoLP6CB7m3XawTeOxwwGVdUYSQ6SrHmq4PO7H1EpzEHjrwgCPGHiVDbJrbJ
qCtrGlN7T9Izi75RLeVddE/r4jE=
=7/9G
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6c5568b4-ee2b-4e8f-a92b-889b94e22d96',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAkupz+qpBc58tcDZC+U0zy+QCVT8SfV6RZ2iyZdqqCbRD
qwS5Hmo6tpGNHa5tL5DooPUKzbApQaFS8pB7c2W598kVf0CktY2lIw/so13tBPfw
ldKCXM1/Ii6+sfwcHBEml5Xr8Cb19c6lHa/cZ7lG/16YCFJ2nPgeH/INLCpIaNEc
o394pmESezkcVF1Spw7mpkQRrUiiLrsjijS4MDiKNXpP5s3FY1D4t7Jrn1EJXRnc
j75E+4Bs0kqmbEzn+JqvXIIgGdTqtLAUv7dw+6IR+efHqkNbH7i2X33SmemI9hEe
OjMlKvNyqeWjQGkF+Murdd9G/I571ibvmsdIerbFQXSbGG7lR2c0AYnUS+/PkSCc
2eK8Eer8q+HoMdq39zq+MHfrQ1tZe60TVhsMZ3ncqM7W3WKC1rD3lrRLj0vjWinj
5b07cLkpyqj7Y6szsDXWr9JPTFEJ5t8TjLigEXkQ6YTm5ookiHvXUUiTAiyumBMW
nTVJxfhrALObZY1rXvU9BwoFlGLlD47JMEwtBGR7RJbgvKG9KUP/c28JF41M17+i
/Yewj7pe5Ay3NXfEFsLNHAmkmXolBj6wjvhM/Hv6k2BnXef7rG61FYMcwHG3EL8t
ZWi/QtCFkmlJ+O+A/udXsOirlwPTzIywA/ZqXBif4/bCGelfQAGU2FjxwLa3t9HS
QQGkmtZNxNvyNIvBlxnQFDwDii+wKCI124WsRRTNQWdPyw6OtX0qmZbljhZKvixF
yL/OMwGfosC9r59szR3Hq7v4
=+k8e
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6dd3a5e4-91fe-4d17-a028-cc8c1d8b354f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//UT8wOlXxQ2nVepyRZFapQvDzI8bJb181BnIG1bA+L6HH
or1+Qgy9dm5UnVTAGa+PHXXZgNLWoXQO7914GqNH1gg5dnejglcyWH8ht8EHAIuc
Qh51oYhWjsdqlzuMeUzoi1ANUX5VTl2tBZV+JCGBkrGquO6NY1yjjwMzU5TF06I+
1MPtrn9jqzGY4qMcmLQdUy8l8WzSGr0rLwxuzIJzTkHWGcn+76gsOcxfFO1rZ4cJ
rp9C2fxXECuLqTVR0B40MaErSN7WcPmLAO/7CzkdVMeQI3YCGYNgcTfWv2lERIlF
KVxkISAafPifyllrAA21qknqAyyEpd64w3fP4QHYuAZI/gozGgVG66uqmCP4DQA5
LJPEovHHfnhYfkDUEoGxTG4zuvhjhesl6P836tR7tdSkCH+AU/etBTcVlADstcwM
kiQ776GOj9jLlYpiEYoPXEJ1Rg96HL81b4NDlV5imskFnIElcl8pDBnwGoGxDh4u
xxbaUFf4Nt1uRxrLp8CXypwXgpMwlMxCPwFlTAEwioQ9QAKkJ3YLdFEiErSr6Aql
SmV9TQPbaGi8nMQcmSE37AIGaSiMYRi14BMFZybdzhdxGsu1ixD/FG3rCZQAf9rf
c2HMb0yJZ+dlaBSUENiCQIXsW5V+KDUV4Ubdl5Fj8ZwKctyUWrkPjStH+Y0l+1XS
QQGNE+T2LKqnmoTdlcl6bN0BJ+FCZnkEBwCyuxXn4dwckTR/DRsdCUMnRiXtdOtg
oZ7G8oi0GhQLHPGO7Z/PoK5e
=odYZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '76fbb704-7a60-41f2-a683-b9f5cae2f6e6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+JxYKmsfTPLbuxFCxY2L+Xmy2LrcBDZgZB8ckjamX9Tav
rqeIbzMtPURUZZQbNtc5eQbSVb14QvRll+HBXc26aKRUfXzO3REMkVuLL0lit0yQ
JktHukeW1Fs4/iGjC0btq1pBAyJV350OcNnPopRNOSxSb/j28o9GYfLvE6/g9NQa
lMWe5i2KW8RU0sLCQLUP52vE57KsM2tXZpfEJpU1btSLPuIFwQtWQLc6MbTkHl1r
ax9f7K2kHogjdxwBJbOCzfDYzfXsdmFJBx75cxDiBlc40AjMOnOJkbFf+eBgBXFh
8qU5ydJrf844CuCJsbfsCeL//CCNlL5upo2YWZryW1oGeJgegVJO05WDh2AYB6yQ
Wh256JU9JO6BONbg0B46dnD5lMm7JX1g6EBIXbXJDb2Hf/jUoMXHtfFviYX6uoAJ
vPz2hMNdfr9EQja8BWK+Bx70jW7gxmMM8RMhwIFb6E2wFRZHsZQ99yiNFo3qlUZd
nVGBcVLTpXISVueuEdW1nhYgCsmrZmu8I5hBtRx3ynysq+UKZW2Km+txu7HLv7DO
EGejLFxjSI9NuiNk+Dwjr2q5fsHLP4GfODtIoQGzhrzFKn3zwQEa2nFdZFkbBhRj
UZOcl2Ezw2P6N+hHITByCGg0RlAeQl2Xjf3c/RxYI6ZMG2+sQPV1w7WN1OOubFDS
QwFsBOmrztGRbL41HO+x3XpRoi6PKehzWYMEeDn3/wM8kbWpwPPyoVeRvxZtcweS
iCIc2L25ENww17xTTRSuPHFtJwk=
=VaGm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '77c2ccf2-db1e-4159-ab55-55fb58984b34',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAApwOvEuPUe5tOsDq9m9TFmwftQpco3EwJ+3T1HnHY7br3
KPHT4pX+nMblJwmSllEN97n10JLXYrW8Pn9vtz/nDwJ+k5n3z/vZRWZLr09QPl5M
iLaCqGwGTriuvXOGl5p/1GX6+bAyZbzU9eKOdxT/aqlebWbgq09cCuHD/0qGHaJs
FeSTkgJ80ZgE4ham8Y0nkdUUk5zIZKkjuJEjwjQxkvradYZPn3L/FGekTx1H3Et9
ZGXwMu0KS9uNvysFmqhS1HipJxVJkfOIrO6bY6W2dXLrQIUMTQESy085KaEPexMK
qd7t3x4XG/2Q6Q5GiOgqom99v2bfrv5zOyClVvehNojovvyI05CmmfskBFvjO51U
fl8FvIW3+fkEZEnAK1D/C2R0CJxZvCzRiELN7qm6d+iF/3/oxoFMjDmBtFh96SAP
FAEK7AXf/CZh67D2+gPtrlIWNSMuPrS5FukmtQd2k/nEhjeJI9Hw6qXDDHplU9Ei
6I7RmhrFXVitiLb31chu09l5aVA7e7P6wUOyKR2bskNEZLUobUq8CVVg+yYj9jW1
X8w2QT8cfFLcHTl0RTkcnNGIO77NG+ZjH36xh64a1TQKF4dWzop3TFe/TSqXhTR4
y209AD7Rr3we+0la9IOKIzYZQ3DBEC+39LrhYj+1p8qWMG7gydaFSyUxlSau0BLS
QwGv8vsF/gYcltAcTbrqxEg6mO0rvCFEYor3JIetLFL+eoFzYWSaidgEY9hleb/i
kzxEbLGXn1YvQXmL1tC2r2/XoQ4=
=URm/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7e1fa3b8-1505-436d-a073-3123d7613d95',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgArGB1EtgTc5nkkq8wm1/XhGuJwtweIms5awnRLkIiOflh
Gwcslzlk5k/1tDoKQafwop+zWrdMlrcjJgYfhMTA8O3TpRbUtf3/HO0raAVrsjpb
xLnVgIq6BpB83xATHiTJaMNTEGqMp5J6bcMhfdfZZiDmRWOZB6waGsP9hulOblrj
F70KPTvlW/gwxFWghQQtIAbqRzwEGCRDBTAzdPcKkednTs9qm6hNKL/p++CAZfSp
Ic8i93sgquR6kMFeHwD80tsTZm3ZTlQgi73vApzURcyW2GecpBV7ur9i6fO4pbXd
Lv53f5QiRsv32ZvSlhh5kXjtdbIMtD5ZwqwBmGAfjdJBAZdGEsAv2c0Mhenb7b1B
BoPck79x7iMPpYRbFBXgacRamYoeNWNQowfeL0IUeWgxYwtZAg110uN8OWqkBsU/
v2M=
=0yqY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '82749799-e167-4731-af47-2a5733891e82',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+I/sJE+Y9A0cZVukSYXXmbQWz3qi2icbIR7poPVBFv6MZ
Y6OKqPbimEe4QgL0yabC/1GOxCzaCL3rLee/ByzNX9NCmd+RKlM6URoSRy2s1ii7
RZeN/mo1iTID6tF8RFV8cW2vLCuJUY/E+9JGctxF8G6C74krC9ZV71VKgtuXJ65T
x1Nx+/4Fs38wvAH55iberkZcr5TL107s+3GiDebH+WO6oQZ6nvqdTcOj9VMR1VOO
XJjW4rgHeUMt6ke34ZIvbUeph04NYi3bZZlGpbPssIo5cuWxRKx73NgsqjlXpVAQ
aZg7OtgJZYFENuTae74ESNpdL0YJ+2Qlm4jAxAbYyNJCAVFBjCBFCSBL/clkU7RZ
rQQkZssAP/+W5y3Y31CG5iKdpWYvQ0nm6pvSbbgwTEjCksIQ6v9igPK+VkTTxvRM
uIZ6
=ank1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8937726b-7f47-43fd-a003-799fd68de404',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//dv9xEy/Nj1wJoBMDulvVkH0YE/HsNR2FRnWE/c6ZmMc+
2vcwp5PD3AbzPfALDsnok5JHQFWmkHSImHlvL+jAogfdORFZ1MSL6JBe0C+K++qK
P2xPiCZA5DUmPnyHTZlZ3bzhGD4FksU9zuMH1onqZAubn96d4AQm0rcfJTHFlS45
k+q1RzN+HEwqXTUmYW/n3qB675n1ybyhOUr7LEk0oR9YsfHE10qnsALipDzX8kQr
A2E/bGZXX7QKC/a9OPT63fJ5kBI3o2bcK4t2OyAaJCU6xko/9mkDyd2hB/8Ap0NO
vEGsrs99j1DZxf/FkadQ+lkH/oomPm1o1IMl2HR1ndJB/X85yM3QLDfkGTx7qq3x
ma0bCLJk9Nhf9WG8p4OFmFXJbjZxYgbQpYj5uJOpVAvoNzXMtY5b3fyqtCbYNTG0
D63VB21r8Jasw2+phNtXz/0QfDUV0Wu8XEeb5H6WQuvemmSrLD7hfrqg6xcEO6ix
mR3v/azv8zi8/LiZVGuHU02QjOe42ibCoHpr7SREPL6WvcKrTe+qWKAGGLdygM8r
3Y+b0QEzvmn2B4h01DL99Gvvi4HeYVcao7BKNnJ+GXXlFkCrJYx6hRWEt1nQWhQq
ebGgsLKva5BjjWWdyRtG+fEP9/QNzRlVUeX9yXbdhOXmEtWVfXjybRPbiSnow1jS
QQG1qc5i8a9NyBaT0XOJI6DaCkJ1gKUjEQWYmwDoEttBnMF0UAr/0touJ7es6AMi
oL3lj0L25Wi/GbSOVcUmyEgi
=SYSc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b08f4ab-1604-40f4-acb5-e113e79ed2a7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAnaCmUStgYn8jZLguLs5mD/74h8hzZt2l4IrIgpHw0zsz
SYfIFwPmmzk5jCLY1Sj0r+JKfEwVJI5ocgsm1qUz/Z8qQ4dTWnIyAzRk/2YmDknY
7Ab5Cq2frURIJt52+tH+jIxIVjl1Z0EgA4hq+IDd9xajRyYQkoAepxETrlkJUSsR
5BQcyNc3rLcBUrGV8Kh1uc84AaLVP8UP7IU44E5bm6T4ZayEf4kCncjPOOIawiD4
v2q2sXdDEjgsgxXsOkpHo+74BJzL3wCfMmy6n1nOkbaBXJ1QRp9CqPfTe57G8OeU
ALZA3w22bvtLhVcAnFlwO4TKJeQJ4kXa3J0XmNlpF72t8E45cgdp7ewl7jj1yPxP
Dd/73A7vkewNDgH8yY+Q7NnHnkAkvytRC5RmeWYItefMA6lcTbTxDL3EdSFMAz8j
mFKv8wz0efhWHk0omb8AEi18Ck7PzUxO4g7pQq2E/QOeAce6FQ6JwoDhCerHciRk
PCJEmpthfv4OejJSfDR+nf2sfjxGDsbblP1WeI1dc3eMltQAr+OpJcXa8CyWwHHf
j16eBivp8vohNuzdpA8N1do55m+hez9l6+OTell7i78wSc0AjZodnfkWTm/UV9xQ
tRHQ2xR9KHs9dLVOkN2tEeZP2r3aF903r4DngqMxhM+7q7jIfMh0w19XvklX433S
PQEViyexJDOr2N+0wEh2pU8t2IeqA0nelIKESzFIz1LWpg/4LEls/1A7XgWz2kD6
qDifF0zTiFpLkTi32+s=
=qf6e
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b22c576-2055-4e91-a64e-2b96589ad9d9',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//U7wHvvUBUvs7QDvcpAO2wCnH5pENzcAuZhw6LylLQM3f
1qRCZfBj3nD467+/mEFBnOK6qIbM6vStknN4QEFMYeUIDlYz5v0trorX7FG5x3x/
XECangrOuu6HunqtYkf+l05FtJYEoBKT4pcjrV91s9ztHgNQYzu5x2VeNQ69Luo8
p59F6ij3Vp3FLlCC6ygPRlLOduETdvkFXQBBCbXcq7WMEu37rokfRApG8ZAOCoba
ArFutYB9jfLUEVlJiNbIGUoe72+Uf5fYcMrx3yTEcpnY+WFPQc5e5AQMnkdOECRK
CCJK/jO4JdH3x7WoupoLc1LRsqxMeF/GV+YLlax4W8tAFvMOhjXJvQOU3dHo8pV5
C1XGBosXs0FzSNI0vkx52l3oTQJXLQ4jSH7t7wLRh3ZSb0TPh3DCme0Y+bAaOB2/
RBvjVQjz0/quZxiRVhGiEc11bHnE2V9LSY4MA8ifFIMNk5u3K/OM0oP1M5PRbKDZ
1uPOTPIEILdGlCJqRyEGnw4Zh7NaJKENeNPoOgqjs3ey3u/8Z/5zBdPOFkFjE0di
FiywN+2mtNe7B5KrESRxrgiwJnX0unR4Y+DBuMclH0iIe49m/Bh0nqvPVfBB6bFn
xI2XnV1t1y2EnEVNB8EMKYwtRiDrnT0G/AImFBR4dnuEvB97MOpOyaIvqPux/i3S
QQF7kT3RT0ztznKirx3o5Z/pb37C1mZdV39HST3zx7LjmmEToPQjEo5NcFt7VDjc
FBfb5AEc4SqfBCtX+6xxvRZc
=wwi1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8bcfeb09-f2db-498c-a759-3abc8da11286',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAhMCNUSP3CNwaF6+7EUmI2MNpf6lfOx172k+ArZy9u7u+
MVh3TtDhgJfyJ52zep7MFYGt572s6WeVivTAAYuMiCDeW6zijCbeZaWwT7cfdAlI
KV00v7cPOSSee9EVWCVryup8rvSBUiQPrchy7gh5vrvxsBJSdLRIsXFT9Rj1LJTa
yE8EYYyCwuh+od7/5y0LYezSCQhA09pYipl/UqfzpleWPAk7FfBrxUEzonrY263F
0e6dA+arfJ5xmUshR+KmtfXbhqYXUrgdCQiV5r/2Brs6kRTpHE6r7wgUmFBeahwq
injJu9dVFhuzutMyDUas6fdbR0RixhthqLk7V5CEYuKz2NZ2iimCgnOnaVsZrBH2
x7wol7izVsSKXJYRhWtiAzkI4w/ft8p4Xhg2TIrOwb54D2HudfpWANFSQeRL+1Ij
XWRMWkK1jEbEDa3l48jARUFSq6IbcWi/O3fEm/GBLTz+sqj/Vw7dYatW+gkRBBM7
iVIWY1raO/bOXx5tOaisuJK5mdI89y75F5hY9EINKAFyR6VqtBJOdb8Po79vpEeG
xk64sgznspSC6EtZPCXhFgM1DfjyBmbvxp0IKywoUoWwjiIjsJFAZ4ery1qWWAeZ
+8idFDlwThZcGROgfcIKHwPAx4A7UHOy8nCeAyxEO/KHvNZ0tVwexFIFis2u6ZHS
QQHs3V9OuU2ehU0W7VdXU9EswwTuOlan6sgwpcuHBmTh5Mx4PyUS8kwcwKI+J5ei
3XUiyHvURNI0Qa7gL/NGoDod
=FHYD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '90fb3673-d91b-49ff-acc9-a58e3e7ae778',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtqOgg13fa+VXdGHsuHdT/AD3mhUehUsLt3mLtGT/mcCo
tRXwHynUaWl6bE7bjcfEz1YQrj7spH22mjnROu1dd1zLKmTASwQY/XMGR7u4cM7n
H4LQ4y+9jVymRocclRWYmYmGYVYoz8pa6/+P00448XdsjiKsVVVLyx7J1gh2bzLl
SVDFdR8VXkLduAJapL7hEqfNPuRsMSSLw+UdkcZsO5mftRgEPTW/Fn5zjem9kn8B
wiSq9DBpozA5E8Yh1M88G5GxTrHBGJdyCH/WL/UTREZWtzbF93GCAqpHquldj73B
sUIb/23vVgZ/71YIsDt0Iudbubz9qKWn2g4sR/EHQhdOhhnV/gVUyqUBeXA1RFOC
lP/gf9Vj13daDHkqNO98bJ/BeuZ/TrbUc6aAQ0tUFopcBS+D+xkYqWCbM8xH4Aqo
fwZKwXJsLVe2Mt2RdGwZ796gUjztBycwZgjWiC4G2bURgBt15S9SYE6uA63j3icj
/5ip+q33Elsn3En+wa+y5Hh2/h8UAvk5/DwwXIfh43K2NRG6oXAu7795/XRmYZRe
/1X65YlPiZuNcbpgvxRZQqUTpYcV3YQUqCCqxgjOgGBw95GoYp/tdFKYznBuM7uY
g0byGUXbfq5Wm0HthuY6O0oSWmsauuroGu59ybdpOjuNicOSG01nInasBfsoI0LS
QwHtuZCn6fJ83ZxbMsX+TMWYl1ioPLiWtGU50VXbnt8tVjAnSeTXFXUMSlSlyWDy
Av3p3dIjfp7DqR4PrBdLC1V2tfs=
=GeC9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '97abedd3-72a1-46f5-a320-d77765f41b95',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//b1kJyQEl3yaKClcao/1s2/au79VHeDmidThlUgZ4VEBv
p0e/p8vZ45uGyz2bDtrwWAHMlebe1ibykqCZBdJSj1oyq6/c0stJWrCRmrW8Ii74
llmOGZ0ECg4yzWAJSjdqhvjwripQZFejLRvgLlJBxf3cZSvIModvsHO2B1mewj/X
MO99b4jp6h/BH6gTL6G8PiSJidYuKSoNrXCbuSt/isXq/EfUyLfLHQDJvsuyYz71
nDkAJSghsnbNa8pAbYQV6LCvGlilZZ1xvCpKNxN6DFtiHoAkXbtlC/mLJnbQMIdW
78iLj4sSmy+UdcTpMxXe6QU74f0MUmnuPw+CKt6jlXHc3VySRw2Eh34GK34WKa1j
lE5H50cflfyTbGoWbWxmRD93liO51drc4Cp+l6GxYb9bWyaTWhAndqgDwoIzOjkr
aReoprHLoRnCBG0qDyDimjHh67ED24o5bSk6YudL937ugqUX5Ockq6+HedsucFxF
uyHmOW9XjAeWKLtiw3YLbEvVTOQT5i2xbgbAA2jPIiP42dft5/RwQiXukRgfZY1r
h7NsV1+lL6XlxM4A5GGSYIvctqdIxkPqg3TvYCnhelGoAYzuDjafgjmaKAGeTna2
y/wF7K8Pj+X4tyFvgYbyj6m6fpbg4f+tgvYhuY/KcvNwZQoCC0ACC4B5VDYAZSrS
QQH/aBpzetEAt8uc4AnfKGigj63bUV3Pib6h4GYtyFmX3UtpK/Xdmg69aRe9nsPu
8ms0vJYda4bl7q7g4Go9DeD3
=fk3X
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '97c248ab-1f28-4a51-a1b0-efa22cb2ac57',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//VZ5I34OHY8mtrag9Vc6EI6fgBSzdjMky6X9nmgC22K+Z
EHHwDAYNohZKswzxuEx76oPhOer5qVXxH7ojGpPeDvAYiR40ZMpvgu9AldqjI7Li
KS5FEWa2RacFroEUtzALi54ET+jG6bVhRu2EDd14aTAFZy6CunYMYLKdkJgj86U+
Eola7Nn3C6Qpx4ibGyBfcF0NdT3vdIlMlp7fV6KOi6uq5/15uzFubYjL2YMdjTjU
V0sySXUEbhwGFZ2HKfiHVSW44WE+wVjq6af+iBJgISEh8NUSP9zUCitUzhodgodz
IJslxBPCULY+sUj8p5mO3zhG2GH9d0kuWBg+YRUCWkau9aHzqJpd3/AJYe/iQgMh
wvjQfHiJpY781mU1UMyd4goAY42H0mmz6pgIN4oPOdm4GGFWicZyk7XMcCRoqxbN
+je8vRA7TAnl063NdE8/kBwvztkukoqU9bwaU2v6W3Z2FAqA09gpee9qp2voXlx3
61iOJFJUkKWA9jKr19UQdeyBPnPBPFhrXwMt2lV21KEmCtuAnmIwMfNaWluDa2fi
peqmTvuULB18tMHmdjfb8FXILyBBp8lcfkI4h1w/IenLw9A+TCffjqzon6hJ0+wq
6SqnFGl0Tv+HhDhEflIyd5eSNBJX9MZ0z9RZxhD9GEAhUtlLI4/u+3/8vnV1O4LS
QQErAbpCsGbAUxQCdNb4JotTtvCCwspONIthxGZuX3VLAUHoobrdIh0YTQUB9GrY
LP6oQCzDrbTAxZeeeOUvLrYV
=MV9F
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '989e13dd-b58c-403b-af7f-df4deaf97ae1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//U9D3votZvMclR6pEhXGPmqoCFNiFiv7n1j9X5PCHK6Vo
L3emOS9tzJeTapla8ZkfIYUYRtxN/zl2QRYMcCmyaKuFBAxyJvyAn2dD7F0pwa9b
PpUTMI/xjgr+1QsiU5JK0q6vN4+QOasWd5NWqiIdbbCjXYHXtwsXoaaAeQv0gHg8
ySWcmUFz4+okmhDmp6B3v3pK9ez0SCTFvdsI+V4u9SZ10V1dKJ9LDwuKZ3KZ1kh7
7VS+DvqmtzKfhOGZ0XVYMQyvNZujrunUmWWuf4m9s4+7TlWRFBq1W1lLeZWXn9ET
tAg+El1RM6vuGedcSunvPTy21rx+NoFB7m86h5cxk6xmYgkpSHJ4ssqvsxA6Zj5/
AP0cWyGnjFJTUGJUelsiZ17FZ0SaEECeuHDwTJ2dkStSQBGbpUpyJAX0NmcSS4nd
6P++zepJoQ+h808ik4fgKV/gt3TAhnoIjm2m46/HE5p3k+14zLvkxEL5mpHru9BS
n9yX23SzT3MLS+NZErqqFIe9eeybfMK9MMoNkmnzr4Rry90zZ9aJVWqPbWXPlbUy
Y6y/VN3+N+coXPYVhIFShAvUJomqESB9In4K/M0bMURwFMhNtdIne7NELMzGTaq0
+QPYPjb+3UECw3aoPW8yOw+0qPpw5JmMjgEKGyrhI825EGmlQHDhQVmbqkAXeyzS
QwHm9KWbX2K+XeBFIHl2mp6aFZp1bTO0ODewNjyK6jdHhsPuCgqunwKkuW7ThtQA
eE6AMyjGxwGOHo7Byixc9DocQ7c=
=wQP5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '98e7d79c-afc2-461d-a723-bb00b1446fcf',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+PQNdMkk4CsyrVYBColUKcKg3DBsKYhL5uERQvMXCMfJj
vKSKIs1lsSI+QazsIXWec1YxkgJcinFBgMjjc/6dQZDwV/bEU/XsiB4wFDde4vjY
WCVqz7+Rj37JTZuhqFSPjnpPVSnpy9nXQcp13V6cMHYoO/rTno0ggFW4j/zC7G0M
YeNdV+9i1hrdIAGdjxTF2/Erf02TLs81u+ReyGX5g1W8/5mOjIgrVvZOtOvrdeLH
SsNdCnKa/9857dXTpYStjSXdTtj/gveNk7Zqca3gUdjFDc0UBsffHzPJEnIPabkO
l2CuAFvwzUyeH9rizNGH4bPunjBiuRWDEQsXmF9NZ4nSdoWnbVbHK8zqkoYWJ9OP
VEgIFFal1Z6PDnvHxoGYZbn0yvkLZWZA+/1CSJBO8SWy8GdxURBmFI3oqrQiKi1N
ckeUW5R4ItpznlJ8h/1ySjUEo5JywDg/6q3lQ8YwUDtzkKlMr+FLJ60RdUXBVuT0
pZTNWfoH80Oij/gJEDev495RPGKrkRcfMKaMUk623THxyvZmN/lAugQoMFVKvdqv
0/cmm5ei2lg6TaS5rLMaUq2qG/4YDblDSMS1M+AwzBNNzXqRwWL1l5uSkVg9DVeP
A6jwOfUv8Kmcc8gydZaepExCrqZ/hK5BTEFRaizoUiJ1o3JEGC0YbYSNDe2PrtnS
QQHFyKV+p87SJO4f7jzLF78qbjBfi5898wpR+eHLsa/SNRTpM2grKqJvpy4d8KBb
yYXEZffa6BbHD0sIC5zXtOvZ
=n2+6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9e212819-d954-4f5c-a286-6381698ea1cb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+JkwkVeCFujWk1vJvFZ2fZNoJbuu4DQFpoBIfIGX5ogOO
tTgF36C8FGdSUuySGzFikkJsEhykUoE5DM2apWl94y59T7K4jSlz6Hi7tYOm0VGp
78J/wf8jZCGfxf/mwT2Fz8S3SUcK02jEHketoFL/AqGMWSzSZoUYwUaMpGp7K6Zn
madnsmDaq1UqwzEATE6e0PZ1RjGPpNf+g3A1a9sT7NxjRD5u7dRuI2rk9O0SjRyS
0s5FwpiCol3LBIy+hNhuR3mp08OcmkvRpGIni7kICx1p0UpJBPwmP0EZkKX+zZzo
tXQqeDnl/GdEwaUqrpUkqFG/Aw+Fo0F84eRaLpmmnUfdDtf2qG8r+YsXClY2cCKC
SQKtD42E7UXR38agrVjfII/zmOWmgRbJvUTSVT/GJCnASJnj5Iu2e3hj3rsExEv5
f7e50v0S5j7dHB7hhstLMh5ZhGkOONBl4+/WIG6UXtNmiZSq7HXFuDbH1ANTwxNB
03UnEi4Dqx8ml8RRZyt3LF7uunyMbcpFP9KLwuf9rz6wFc21qKmuKHdaJPMCyh5w
Trtg/cvgHf8PL/vsqIn5GIDh7UxR/3z9VpOxvvAQnT0L/QDdWR3Axm+bpwsUO1Gh
nGclYtFc3wb/LTwkV42JoDqKhHBuA6Ycow3zMAAu2LhrytsXXMvpnpOFDqWm2gfS
QQEG0hl97em7SZKXImEea4jn8uUCJu5/JNmiTbVvS0qWSrLoANWTC8ErrzToMEdK
bi9yOfyVUpi7QBNqlhdX07ge
=Klh1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a4d8623c-f5a4-4816-af8c-971eb193d0c5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//enUop6Vx5xnjbB5qdrQwAjN1/sWNeT9hrJeX2+wTxDTd
SGHQE6639YIPZ45WlNxBLiGiRThiFeFyHs4BMzXL8X0UoG5ROQvTTVjl2EM9BO3/
G2wsFIJSuMxu3xtg2/M6RVwKQ1R88cPkf+d5qTamoHRCBSIhiKuO5vaCqM43UEMH
pHJiU+7+RljWQ0ZzuxJdANBlU/TzZbfKJKDiFq2e3G0SonpWGNQXh37ckPFZjsag
xxJItjjFzgZCkqT5G7qwWltuZ5wFrjfqkOdG6CmkislRhzWiIqkM6Uo05soSUw8y
YunXRMXKjs692ogbWc+nys0RpBJ+97LoUwuoCmQbFvLpEwZIgeycTRWnfhrLeagQ
mG4LFpiQRi0ZvVpdxx1xL8SPlh2J0wO2xuzteZeQcfRZi860Fn7yVQv08GDWy+pM
mYY7BZo7uYybFATVmapYGqNZZ5tXV8pwcCFyDq999+Vy2W327OzSl5TG1soVgss1
9Z/NbXymYod4OVi+6NX+AXXV0Lg3sWTDJ2HZYkb6Olf00W3qtsIDlvSugJM/XD5R
njpq3vsNr3cpPucVOLl0KQwQO++2BxxBr/2U8Owf8GwuknSw4CHCwCtsVmkkj7oy
EWWKM+f52uDr6pNwbkvYG7K7m+d5w9EVsXuloYUoxRMCKK8TEBE4eirjfMN4pzXS
QAEY17uzLwFUVHrpodvfIWmNLY9WK6v6kOqo/+eS+CwTqpeipdK6HLXzw//j1u2J
MFDYB0Lj6svgSjuYU3TmJos=
=Dpz4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a59f9a15-6653-4f7b-a955-e3abda3afa23',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9FPcw9nCSnkd+Uv8dF3ggL3SEdc6O8qU9jWHcqCbwT18E
VpJVqH3Ol27zPfU32G68ERjwQ6Sspyu+YSAxMY1/rI4h9WEMh1sHP/ufu+bvRE+c
o8jTuEejCPYdQRf0yGvBx16CXknomwRSYCZShE8ULPzdHHvQ78RREUJGs9bkpZ5v
ZXs/1irxf9YBGL5xMUyXP4T074XA7bTiDfbTPPu5uSHzna/Rul8lBPj8i98X3xiL
sAkUKSIF9Odui+9CBgIWBhB3QYRah2TbYcjueSIvNyjtSdsv8uVyIuKx4Ulc0N5B
yOGMWtbTmqyqOaAZ3h5mR/8iwBP4hxrg2+wVmA/4roxasq/kYr0u8SOS2iY8TARb
l0OJ24YdRwTg7FOe141tpY8/8CcsEIiQTG/avbFZyMVBEawXC1L00+QNrTVC5B0Y
KmajihwBo8ud3eimafFuMj7Su//zOImuRg9o8j6ypy8njsmViKOCUZxxKEr0bwCK
4RMZ2cZHC3AobMloELkr6fT+y8/1b79mtg81HE08LOH8NKaxnnznw2LxdB4yEeNj
4L1ZPa1fcBirT0Xcc7xuPNAu8jK4+TtjQ2esFYxC7mF4xFuJ1Jl5R0NpvQ0hJ8jD
eY3e0PU+5/eE3Ools8EwO7lNF6jlTY+au23xEzf2oMtVglTm5shnq74yQGfwMuDS
QQHgpl+RDmGKAQd5jkoCdpB0/Cf9bU0NfUuKvqTDk55rl7m0sl6Gs6/r97Xo8PmF
7JW0DUZxoCroGe7zMdI4/+hn
=ORf6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a625dfa8-ee1f-4c50-ae9f-9ffb95efdb3d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HN5qfHaA8N5JzcftMkQMQUytU5dF/XplGo8/o9GQTLZR
wXtS7ggWMTAChf1937XdHVEbnTyxNodZc6mP55qRyLaK//yTDUr7vgn2JQit0PPw
tlG9dRMoXS2otaD4Pqf3r1BJpvPbJe0ljGFxlYamenILKDHvwxiHHQDBqza1zmB0
oqe9gX6wrB2EJxflhRZ+ChJrtTAYq818Fn/sIG8mAgiBqlYHHzKSaOsBNTmChQmW
p0kn3MtPJW8J7XgNYiR29geW3ZL0iHEXFjtqRdhm58iQzs5gsGiKs3BLp4uwU2wQ
BLePpylTId1aOAOhGwolWrHHeAN/ck+Y9IPgtX9ZYXa4dRYsWWqoDJjqb7vphcpL
cRx7UobsLWEWybsOa5SOyYizOuvhSJPy460YDa7HBkRY0+vMacux2QnakCUsRLmT
yupIrY8mHnLj4b+OprywAyxxSoTvb2F0CL7G038ZtRx+ZEW7trmpUurpWKZKh80S
Zhp8emhJz4eyHo+hFc3LvTChPmyTrrsy7Pe/kDAiKGkH+XFKJ88LC7usYzJMzCzY
1ToW1RKa4wyaJGoEi6QYqqwRaiVUbF1iLbarL8XnZF1cljlgB+Tajh5m9kn9UFBK
wAaEGPQ78eTgGAP4icQmxd+InnQjq2TqSLnAR1V4RfaV4MpITMGJX29qi1QAtOrS
QwELySFfL40rjIFO5EOwO9PBdPwT2ElUGFQFTQkCHrR0A28IXi2D5Qhk+OtVEQRr
YNGzZmDZGQUXOMV+vGLgw6/VjJ4=
=dWpu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aa4c6053-e806-4654-a3b7-c230a2ad53b1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//aJFvtYJGPaTIBA8lNN1/gSEHZMD16HrH5ZGwNO5ccIE3
sQttMKNjTgvWGA/T0sUtJELl1gf6qfqvuKNLuGB49ox3TlYfh//nGEe5FaHlOsAD
dheuHMU2qv9jTCa9o7rlWbmtthDHGsIEZubyPoH3r4rs4BzJqTdI4etW8SBOppfm
HJ+UhLcdjvV4ybeGcZ44O7Bg4IR7yXQFRWxaKepB+G56iae39OpiFrZJq0fuVWyA
QE2AFTaVVJFzrr8/f+guvFsLmTRljLL07iBVp9W/cfL6wLpD/jTxTzLje+c961Cg
NRWNN9N3Lc8T0WKICWmWTvnktTuLEGdLck6nHov1rVOObcTjS909uNq9Q1wxuirP
66DmTe0c2+5ZL66BAt1DmiEFhLdxUJetHdrku18A5tw6sZWLjOlCJX8jHORvTfI9
NMRGNRkuf8s0H1/1tKg9BaOFnjGtUuq/Nk0S8QMIApr2t0aQ7dHDpKyY4z30Rak5
HXpTnq/VcsWBFy2Tw3R8ZpPfAJ0Iy7JTkEIgC18/HPsHw8khKULr6y7y3XajKj5e
AWCrMA+9pNzyYH7zBoc7pR1wVwW+V3XrzYtUpHUnQOm2AT3LRGZK4B3soEMTJNcp
ztwmkOW3EHeGPYsQMwI8H9Qpv7VNAV9ZI1bYY2c+ExSNhgh3BNsx1gUXowJHiGPS
QQGKAqg71fsH3Y8o5WZjmXac6VfE8iEviEXaLAAnK3onWKLrT+tx470QM2547LK3
ERqRtbkTlw2t9ET2NsLLOd/I
=NUVX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ab66c071-30bb-4fed-a194-9800adec6582',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//emLD22id4vmbLg2+HzJJw88q6AzuP2k9g9a84x8uoJGz
uVYg6Xw2xtrjbbyya29ww728vYVaaIpkWdJCLsKKuTVEXmI8hhLYTdLDQgUz9i7Z
7sAkweCpr5pvoTk4xDICLgdw1IeHjFgfLp5GIezMBPNliiiNGvv+T1dkxbHJacey
wTavfk2rLA1JafdeUY7lGk03tuTiWMCw9SWzWMRpLm8KfFoKsiOfc/ZAmasvHnPy
MXjnxM3O6XaTJ2eVyTq70Xb54By9itKS7zR8ih5MAxaK0gnSbSELORa8NwLjBSdU
sBGCTk/+0qKstzjXB4d5X4auCx+O2Cdj7lFU/yv/9xkl5OVb9BqikIYA12j3DuN/
Ax/ZXqlOFS0QfBclAUerE7b6DSQvm7BSqs1x7GTI4BlvkIxpl9MZ/i9qfuuJXRvb
prAi1W4lEkIxlF7yXOeIMhbHjcC17Kbi0kKtwUWSjdlLywqr66vjjG+JtlAa+F3w
QyUGZWBBPXJJWOfMJ7Vu9aD01ilmnoPUL1Wh+GvR1DaP3+qZEu6MvB45n9IGxvgB
xxg4frm3q5BEQlt50rkmfD1TTr3WUbmYZVAI6ltj9VesG0ucrooRJsd8DnkKWnQp
U6DVPFCGlwowGjhVfjKRFQKSYwQt7M4ZzefpLRgHMmjpvPkakUCbYpfd11s1YKvS
QQFeop/1pGnyI4Aw1i+kvzQVDg63zI1BEftotwnMGgX5mtb4KpxxGCpIg0tnL0WI
qoZF8dfgl7B57NS0mKeUfH3t
=Q852
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b23fa2f0-39c3-4d09-a45e-1bc5716bca49',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//avaCrdiE+p+6LTPsOiOQhMvdO5G28ZXvigR9A1elRjm6
/IaGm4xlL2rWrYYhQCsAXvPFeW8sgOHKmE0dhSDwM5KhNhY0PFJf40NmduChenuM
FXEKiDF2KvGskaStdAT6QID9kX3s6s7XTmA7gUNVHkG6uoq4YisSc8bOe4jHlT+8
ER3jkQhSdo7Q4DlM54KzvsULAD2vM8LBTMHsMacG1ioKYh/brv2A9zkkSgDAIyyX
coUUqt+XUNK2xDP7zNQLap1Mr9qfl/EyBRRNdcDDkKeOqz6Kq/qV3n1IPua+BLp/
6umbVtJlk2P3N8Kyt9K9AmmEtsR+Cq6UvQP/pRsj4p44ZMoUH4/9s9DOcbobkYlN
UTaQdkjX3e9egrJARkDIKqbqrl/uy3YguWwL2AFrwiO/pVEQEBDUZMZzKD34zjeJ
bJLBZ65sbDFb+gSOVVosKC33WVUcjqyXaIhRWOWvKC+untbccPf40K0zPdoI19+t
nhkUfLHeBj6Ji46yc5VzsfoQGTL+fEuSfksu+fIvofKscsQbGrSYOmlQs6pNrPGq
LzbR/+uAMgsaQziJPGLQEBLIANLGBzf6R3gQAPl2AWeudfa0uLAtwVWvg56fnngr
exSWRKsxSgwnw+GLkVa2PLqBk2gQF5gdnM9PVzZmeZDq4o8hKBEgpei99eJnVDrS
QwGb6kowq0ctpZofB42ft8Kids437Ts7OpkGKB9YAVG3d5Agv5NKy469dMPUtnCN
mQIaCIIDiTTyl8RbYwM0ijYf/qs=
=g1Zd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b35117fc-d00a-42c3-a0cb-c82bfed5aac1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA40cgzcgy/jiVX1fsxXNV7ULcbBa/J+oj5/JBltnU9hCX
OGvUfbfzZ1TAwf0DDhxdTQk4NI1LDyMqCQkioOb4h8HmtY584sIn2VQ7pl4mqeWA
avxuVnzgQivq8vwT3DP5p4kYbazIgEb/idv/8s392DjDMBeMxMTetQ1zBSw3CrBW
NdVltjXHs1AVpFECE2B2V4c1EASB8ZnrNLQwX+DwXT/0UJW9FMHlYKNQw+aJYul8
1EeOwiR06POjfjZ69I6ksCBBEXr+4FFs2graIMAopdYt0JNTytl2zHyXETck4nHl
58CjACJinI+Oqbw8l2Lc37YNjXNGwTu8WSgCml2Oren1fceOwnI0N/086kSXyeB+
2/7ixsmhqxeLcnknlVE8QPllwfM7uNznMvWAtTkxd91n6PVN7KN7u60qLg/Bgawx
qYqasV01QLN0gNcBy1K6XwVUUjWY9V46W28rRcxbhv+Eur17ksY1ghN5QHTHY4ev
oz3y0wHt2PA3cZeBOIFaqDzcFzryNSmjhiigvBD5BBevF+atkurW8RtcgGl3qL62
/Uy5i7Ob4jJUgXHwFY5ILy2EshD/ccW6S97wqd1x4UYwMskWUM0+juul9ZLLAJK/
BcTcUQCZcGOg4z86VlS+fKwllwLbvpeqLgPvMD2M3e8nuMf7J1byY3/JnKeBqnzS
QAF4LVzg/IZoE4x9XPmS5ceeGYIVHI2/WoSMp3xd0F17jbXR1Kn9VizeA8Nnk/9w
+E3xyb4c3CjO7vZoAPspCZY=
=Vl/n
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b71797f2-28c9-4531-ae21-6f9019df53da',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgApVsNcRqIuQEPqmUNZhjzEYHtr/uXXChD1mnmlTGLev0k
poLPf5kT502xk+u1F79q7BMMk8kNNw+VN0Q7sRxhf4+f4P7K18LL6bd6ETJIuPcH
r76GuSIHYbL5Hu6kkuzzL4gWRh7wIP6eW/rOOv7yTSDIReENeGh6+3ZChXMQuMpk
M58S04pjqI+d/8djgAyLRlOIrU1e1AW/92RVmN3wyGWBH7vMSKKoMAnTJZXEfrMH
5vg2VPO9hyQHu91X4+v/6vL6R0rjOIs2wHQ7XZu/W79ewpiKUZuVtoyvvWBM+Dxu
DpEZiwa3IOmREuUeDukYuZQWFoxeU+y6KolMXqC4KdJDAXee45OllP968dBfKdek
891AiNC8WDYMtSL69nKuzplIlIeQuUyYkD7BD4pRz1NZX1QzAgxx5Mzs7MmhwQJi
XtACXA==
=Ogf7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b94f3477-6e14-411d-ad61-eeabc3d50666',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//QWvDmYwUJJ5cfJGFugrnfQdU24VQpdZExW6d1+H7fu8w
nL491WlZ/IwFU6nQW0fOkEPO+VEsKd9JH0jGXfDcTgpqJqnke4IOclH7jNgCW+Dl
I95hI9R6viNNCDCKVW7BQEuTredkgJv2KlYJVzsGKHWaOGlSmnoQyMFUldWvIoGQ
oYX8fJpaPwYCmAnG2eYyQozY2EgDAC5JRUPLeW/+BhhZTJYhPR4kQbqI7VsO/mLT
sDMZWsGP6Hcnc2jh2RS+SIxrH/ZAgLhxU/DvibvSHsYVAnuD7HhUc1WpkAGU+0WI
Dg+aISHTLnmkTxWJRIL7dv5XEY95HP4OMF3ZuRev+HnTayvT0Tbj1VQ2alm9XZHz
MXgAZmffO6ysvfYpx/vxrpngWWaY0SHn32yoBnbGMX3ZN75PUPef9dGg3IsRW2Sj
r9p71xWnsxZn8cRdfyZ8NsQNy60pNS6EN+twiondngQZgu1U2djJdmWaPwhoZ8O7
aF9hOKmotZXBCGn30mB5th4b0VKxyX87fdUY2nGbbsYmnDjAdyRQjvt961lMLevZ
Wz/XAkjIlQpMVabXLX1fAg0TxtJoGokAGdTo4aSwL0ukooqEBXYOiTNghMPCCRcK
JzFcLD+nLWz7nnIrlihH887uucvb7BArVx+OjRjiRuitFPNUm/lBfk7+hFtoWN/S
QQFFM4IPs1QGOyegIPmbmnELj3b13bin46pHGmN/4QjXtMjQx0P19IPHLpkjtdKw
1BIM7BdR2rInFYMaYh/uYc+F
=YCQD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b9beb457-2110-453b-a521-63ea1cb319eb',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgApapQo7ftoMh0tJhvBTlYv/ynjIckWAUt4GLjurLR5WeB
GQazHG3AQozuorNaeyqOaClecT9VoXHWkJv8vcIVGhc+fGl9W1pgPfPG2OU6JpsB
l8ABYGlMaVL5tf+OGfnlW2xKrAuR2dwiCioejiMBKP2zCbBOZgRjzlgVDzPEIe20
AaahV1j8mV1pSBW49XNP3VACBMi61iAVzG/8acfch8ipzQogFcJfLfL5Jb/7vE+I
N5PegMV7sjqcMGjIdPg7ldCgEDChXDfv8wRVYGZNKD3H/b5+noBQERu1nmWlJx6c
t6QOD6y7L7Cgu3OKhc9L2Hk54xBXdhSXzdXBv8RFntJBAfR5s5rnIiEiQG6fImBG
jA/PVzBprI0SgJQr6QXNyNPMyiSEIvquqt7T7+NCxui6sbWeR/7Pj6BEV5r+yXC7
9SI=
=y5wG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bb415372-2131-4544-a8ec-433d54f21341',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgApO0Z/DAqEZdM40Myc+V3sS1IxaTttvwKL9EKLIVke2ll
hB1RWa9WfJh8Gmkh0HtW5P/dJ0stdAB8Cbqo3r//yj0TE0JHLs76+FMPzescEAkw
Onz0XZ+AYwuPIQSYmPxLidHy6F5On+w2dYfMrk0rD9TW58WqoSFFhxYg2dypRuSF
f4a/VMSSv8f1R1OaLLyFRoSBM7oKGktCsv91TvblVedDvkDfq5TZudZoT01Ju/PN
UhbJD31H+L41jGn0qpgqLvPjzQ1l68aOTJToRCpgJ1Q6V9nV6mhLNZtbQVH+NjJb
u1uxuepSHKqg3ltpqvQPIi+KPJ5PKXMcMT03LLY1itJDAcCd5Wr6LC3CKUnTqS43
/r8nSuEtNUFvkwwgr0erNPPg27BqF2qXbDHN1XIXwfaNtaCIRjnwqzzuhWK+tGyZ
6XomYw==
=+53f
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bc728935-ef05-4b84-ad07-406789974dba',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAmQMX6oWVk+1R0+EF9OXcs7j4oaJSpwzAaBYoagtx8d4R
TzRmysdWMOC/4JpSCEDDnDZcyf5AG2ZaGEBqqmI6v6Td7RSEnr192UQsWIPxn3ep
sBMD6pPVEXso5maoIfNL+9kkvFQ5Ts2VdUgUCz0cs0/nPl6GbNPbVE7W+sB+78+h
bKmxXHVX14Nmqg85Il0rQR9SIWSm6wre0/j1rg7l7YLfGeRNzwd8Wtl0v42XMzxI
MjZNyEOb9q+W80cBoBMWrUEo0IHJLjEAQgTDLFsZl/eIIMB/nvsbOnC+Cl7Raewx
KI+80MTsv6C0T/65peCX9eC1rJxMXgYkCHUPHHofyTvyVOysSCYyZ9VAltKk5gLc
Q2STZ0ggBTgJ56L+Gkp648PIsO5/1maz4TGU6LtQdbosHA1C5A9FwNIZnaR+wyHu
zyiPo/Iaorp5KSwMUGrJlzVBEzsmrUYWmM/5jEZLuoV9JKOC6JTQ1eHvTyMv0xIy
TAUt0i5GJ6WVzOGspw7tnIt0krvqxjLL1A64mdma4+DmfzNNFy+fEBFI0uNDjlP8
Q7NvmD5SV8YlpHAJbxyqrE6mmxBEpA8FhqpIjiU+KX8lG0tX8H44y4eZMv8zSTuk
TFT9PUBxK9rfinEpd777k6xuJrOJd6KZA+HYMPyLlwuRz1XHVn7VN7FXRhfv9drS
QAEXrx2oziBzm7C/wAadvS1xt8e/T5NIM5iw0tYRXl3t4xQOtMUm+O6+tz3Qk9PQ
uG4+URTUCzI1TItRcDhI/5s=
=uoea
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bd3a359f-7933-48ee-a71c-8fc7f2f0bd16',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAwI8PbzQWn1cE/hmd+bGXRxaJMfK9wEiAI7a6R53/xiyK
f8FgTNihd/Hdn81YpMrXW4vrlCU7HkFHQUeGU0P3zVAsmEn0XV/NwImsHtYC3cSe
qvIXeb04tLBtwGev5ZoS20V0gzkuJoMCWsA5gcDz2yZ8pqO+HL/C3trAsSYH+9f1
15d0lmUBuOctpb7v14rzx7eDm6cnZGSd2aK7VtCHWQM5pSNRy4lL2v1uOISlvLFe
opFtpp1NMhn2cyLLiuwRea1lxD7dYKlkNyqMGEfYAGAnxLRZ4aUUfDQaFYyCyLgx
Ok680dFhoBBLER4rpZTI6Ub6TerbPqXN9vD8hn716NkoDC7ZXBdLJGpLnQzeScVd
54uY0fqP7glU0gJdESX/v13ux44fV0WgWfGIUKmJhQZ1M5qQOMRcR3Ttp4X9S4pf
eKEXe0DAqzvMkhJ0V+FwwW3fPCiIeavWQ6drwuCS9HafSDdqF2ihR/IRc66RrkHb
oHpIFHCeEDGlQVHd080Pph8xBpTaaqUo0hI6E3u3cXQKWM2D6mu2EwQli0+j4QUn
RDnRabMLkeNprKfUr/28R1dHisdp8O7c8Sh48chfJZCrUxayeSC+4dOMXT9Votj5
rwn6tjF+RIWArcePYXb9xfJRtGLoX2KdKyV3gwY5KoHYqZESLZh+UTDFF706dT3S
QgGPyDKee0rw1cQpLoh+BjKHTqGhuFx2rdvfSjYyCd8kKlCU2lf1eoYaiaz5cVd1
tPNBsBpaNGgK1v//OcZn2guJVw==
=cRZz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c21cc3d0-5e7a-4d7f-af59-998693cc074b',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//Z7SI9aeI4ZJj/21DAd/sZVNevMWZcwDf2g66caI3fO++
smfUSdnn+YmRDVZo6WBgSAmbEbFu4SMFEKhmorkns8f0qkBbZjAhv4dmV9Vik2cP
wE24v3zh4KIejr9nXzWF1QXjirCB8+CL/a/qQPicK3Z7L1ymwCU6QKYB6OmnOABz
jyrYHan0QYEnxnP3kom73vytMfz7ku0HfoOBKd5G4iSYl+yoEiXFtM2fBCA17/JX
uF59m1/K71BKEAo4rsBPeXXveS6BiH5zo58sAgmbtZqAXuNz6V+aW2/4xj6LTC0l
TCVSn8fekzMCK/boD+J8kMqJCAcGcW6YygLwqjJNVzva3norUnOASdW6g75ZhqBA
V16LNpKe8nwk5SOxa7fEZDYIummigFxRghc+bBRgveNsJDEWAr14VAx+2aJCCBd2
il/Ym9UtGelTmU/2YGVW4p+FDYmKX/J+Vtq4+HEaShVENEj55P3Z/qv1slh+21KA
VYKkZ69WySsVR9rDKPVT/gWTKnuGq0II+aYjvqhiVDuzHu6HtwAM2Qvl0wwXalvk
SluKYt0zeVrbCxDqt01Ie4ha/fyp40ZHkTF4/upGHRwOFv3dYDA4vPVQI98nWJAW
1Chr8QmWtethTVnAZDIKX7Mvkwg0vVp2Z5P09xUNKjcQMiXeXi1oW5tK+tCFiP7S
QQGFFgwprHvCJc3awE9LhcURjyWvSd1gf9X2EARhnezi+WPERgLcXFKXCG/ZIFmf
YnsDmnDOyJPb1EvGu54+IOkp
=Ya0T
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ce04e72c-d60c-4222-adc3-6ba5c6c2a627',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAozZlcNizrywAvH/OQ9CdBs7EqorKKgzlnLhccdrAZOtP
OdGeRsHLD3K2EjxoUOTkrxr3AGYCg3tK81dnHRoe7q4PPWSDMEU6RWKUWmrHKPGz
PUYku0KwZlNV8ib1iXVOE5f+LTi0Re1uGoMDOB0WD9mAk//8BENH1YTgYvqspX0q
1Hg2sVFbd7jhZEDrBKaqcUVtKi0d12lPnupqwHVWblVmzwl2bVfEPlSPV2L7TRzM
4CvMF8CBrPIoWRqxyUX65RH/jghbpjDTxIBgxyYYSIfLsWORkPAOO9Oj/R0bNw51
ARA45NvoaFCZtMOY6sFkmEpyhVQGTB+kD6jQFmVdvP/JxshDdKXTjLIZh3hFAl6u
pp4eiGUA54LtyUxIPj7+livJTtVI99XkVGTTriuM5NbnDFHn5wubFD8aoSS/L5oW
JesWXcsitRWufru9Rn49cYK4E9WDh1v6IyuK6bgnZO6lFULojUVZX2TwxlqOe2mE
0OxK5/o/H9+9vbiKqLreabkwrXVc3+6FTyhlmkOOPRK68PwLTwvTzPBJq4Dxd0tz
j62rofy6km/1d5G6boiH/9v4EKZYch7zglR0ZCGPJL4ESRclrKVa8LcbyE1tIM4G
Sn6/37Yv2RwK+xhdISEQxsfeA4Q5+cHTrTNhLoUNB+isE1OMIUrWVCfT+nzjhI/S
QwG0I7nf/XGtfugcYBvIpZcZ81xh1wSIMBJNzkj0PPbza+SBuqKioGuswi3qn/tF
97v5gGbdUuByxTgLBewleVuXK90=
=Mmds
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd28f88b2-944e-4612-a3e9-0366fb14c003',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA6BKSN9Ht3nAF1Qz5T2QltIKTA0EXQClK7tkh0hD0reCL
bryU2n6WgmUcactG4/9vleVtIxqlXUYqaxHkU7ydqqawTKDCD4tvsH1mMTsb6mci
pPmYwTq4g1pL80DxJPUZ4OlJtUQrdk/4pzT8AAwG382Bjkl4HfppToFr/7s9m7tF
rZQsGEWttlP/ktCzoFXFRUMgrCuAQ8/4j/dn0GD3wO4QsvYki16V/HPftvhJqROb
GKpNsF7uWYnrnYWAleZ08dj7vWR96HCweomK4FnZ8sOhT8z9QfOfgbL1/v+GjyDQ
UxiNqYt5UHwb2cmyWk5R1lH2G8dWu39QYm4GoihLquuBJqVOY9LAOwlnYYp58KBh
ZTBXCQ5KlQVOfcnIKM25UZMAzaYN2DZqjBKj1P0jF2L2yXR25Yk8EeJO2GXPC0N6
UnNRtCoXOFczyk1G+hbrThlg2Xve90jjK21zz949Lgo4q3yBJJsMRSLFeZbaKs5g
KTHPhzcvUKV3MFiPc8w2l+iHnLUwKqmMArA1sC1ffR/t3JYuthdhntGzZL22drRL
xHz1fXJqKgb+XZlQTkBR2mIkDmd8yMWlMmNaOMrRNTk5/ICVbGrLP2Ao7I0iTY0z
5Rq0WXTD8P0z5wrn/XRKKSoBM3lMj58sA5CxlWxJeqzxIMA9vZPYIqaz/2rPL3LS
QAGsZYVWAJYv3iRnVpnnp+YAuRvz+/DunQ3zlifXI2Bx5tchcqIeYsnXWyLUgD0u
BYN0wuUq94vg2RJkFiq5YGw=
=cW3S
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd45fc732-9c30-4ef7-ab62-53b08620beab',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAuL/j8smXDz2wwlO6MVLp4Qr6qK1ORQnp8sfuHnN4yz1T
77X5RDuMahais92akNHjUICQnh0JkK4jioy4ORW6E7ScPcgRt1RbTFRH0qTS2b71
NR7kLSprAxTF9H0uDwoiVliHRECDK4rw6shWZ09o8qouGBX5vevhKmuJvEt+ldCa
lJoKKY7udmuphz5CKnxhvUQHKn28rr0nvYPjrZpgf2uxLBOyS9S+6W3vMK8453pO
BSNsd4qpQPn5HS7t2IJ/yWMQBPUFnMJxBrjAoCS/2R2x/7zr3sODJwJV1ECjQPp4
b2UgrMz28mVU4QZNhwXCmlsn9MGN1BCGaci3rQD1IH4wSnOywNPXh2LAkOcXnU9g
HpcuvJnEKvfe2AZy0MZVtx1DY/FxYI5Rluru337QiebtBI3Cvtcj3So/3SuSre4l
yKTszDc+7D+WGGkgu9Yps6Wd+jFRboXddg2UfNg1hOc15fcXKj8dUmwzaVkdP1Cv
Y5n2hqsejWjzVHqOjBxJX9YhtipxXAW5O09bie5pqq2A0GOZyEQlbNFDWg8Y0Rb1
F356hP9yBqitIZCpFz9ZuoePUMhEDG7mYk0RFB307d48Tc3uhHzFKrUQq9n1LPrZ
ORJq9qNzxYqOC+C11dsBtW6SWMl7+r69+HHtujNdzWuValO14jHGQdc+uSd0KK/S
QQH8deiT47d7tIyGQGt8q7F8wasHbCIiUqu710D4JqvJdT8cHV5HizoJAvE/Vqbf
EYjJncoA0FKfAKuUJdRj70n+
=zccx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd890d60f-000a-40ed-a37f-e60c86b09650',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAthPf156yogYAm597zx68WeNxr/CSl8VhoeDaTr0gIffO
f2wM3uyv++AYpQGjYJ7WqaPQ8H1pZIZCaI2YWs8DbePg4jPJSfiDJ19bdJzMPY8S
6MQ9FpjQFWG4n9tCip8hc94oBFS5KP++P34FMZ06IxWn3stjQJ6XIMfrWtYQP+H+
xV0K5bbjNJxqTFLnXxj5KytWKBg9H/RdEgNB/bWLUXjXXyfZ2m48ZsKBZH10CniG
lkngXSHzuLk/9V3lNUr9h6ufg+BJ1dfEyK9Lw811PZluo5tS0CYiqWVkM4U3B0b2
moef6EYIOUc4KFVyh3kDjC9ECTqss9nQOgyXDcDaQNJBAUXU63i2I8pxi2GnVXml
ncqIQRvGOPDBcdS6N5J/7NBaxboHVYqODc1miEGYjgAAPPLLXwO4MUJ/U/qLrF59
Lkw=
=Ufkc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e1ab6e99-631c-4be4-a74e-480964f93b0f',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8DRsuC6AtuDJWA2/0viCEyVqCpikOzHa2hbN31qpXPkEg
x3Gty3aZsXLkhLi4kGZLH7E4cNvHEE1UOWq+iDGeI2Isy/XN2aQUSqQiZ0+Qg80Y
Y0vZ1PqjET7Ok0V0+KUWtvitPg54ITPOaS5dk+teX2xoqUnbSKD690PkhWOHD1HY
Fs5K+ZGkouSF+s3g++Q7k3Hcoru50fDQZdHqhxALHLdczFL5K+u6AbhzsKV6+ukb
DYZLIw3NhkjU/afFFjuoi+SIxbQJyNVtC7n4m025BfGvSFn7r4xdgZ5aWP2LtYTZ
MCsvde48WcsLEuuFwiACYYP+IDdSXlxHVEEzNqYjtd1KxiVIEWzfg9SRgCFyDbN6
9IHuIJHGz3h4X/6NtfU1jLehv1c05SwhtDJGwNCRmjGEus4FpL2G2MEVUl9Gun8l
6YsazS9xnqyV73C/eJcL16bpiPjGEPzZhYuaVkmVssUvtIramZVbHFs0sArizHR3
2spaXyCtVCIdqQmP+xSXcED2Ag8D3xAC+rHFYDzxG9Mp8AOTt0ZneH6iowyI2kVd
mEXY9c6Hg3JjtDumHkIF7P5YY4zsOVXZpDK7uAGA9tviCTQfLmq9xduMgFv9J9pR
yYW8dzdYOhO+qa1osmlFuVRXcCgo16PQwUHCbrbi9/trNicp6XN+o46fzOtFMLPS
QwEy5fLF4litMXJEzwas/QZHF51z1psbjDqbHjZYWVIPpI+0I7FOMXVsnVpKKDHn
bDA1/1RSkLl+vC5m8qIRxf1kpKk=
=WIeB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e586a509-35c3-4dcc-a72e-cbc7e676bcf4',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAphCiMtEDz5GcgLN6e8f62lYSApXJHWIKYhh84uaMhShX
KbA7oDIY4SkBpTZz2gV9pACzI1RxX0+Z8m5i7AI7+pSImPSZo9pCSOn7dybjSKfg
BtEioE0NZtSKpqletmNXt5FHcWO7nhbWaBqOlo/uNI4ucLJ0IFpwmF4b5ICEIr4c
Df5q/j2tm8USB1G3nXEyxWWS7MMjEtOSSkwvF4RJS0kaCOOrakxBPhgLjjqHWjBW
iSgS6wegvarRSg/LMia/wSlUUv7XqfFE0XCX+qvHTEyjoFfi2WT34jGWoZsUA6dH
DV2LZMjyG/6avrbojrFkyfb8FG75s6iXcu1WdGiAZ9+QTwAMg7M7srAd9iN3ANZH
GEsfG2cPHO9XhiH3URzOVO2/outHzfuyuYQBRkjXyJ7AHBRaLeJXLcfKKe16aIZi
AdhHHsU/HVRoSNN0+Bx/vpFR4V16nSSG3ZUmMN/du8GrRQJ+ztDfp9rqU7jgfehe
9+YIDqskNf+/UwZoi3TWLp1tUi6vak7UUgXUzIzoy2XQ6oj4MDU7dOfT/7YXAd9H
rE2FF0jmxx6YnyEtzcX3IDl5FWMF1VLp7tUq9YjplCX/YAVfOU7tLj3yC1o4W2i3
LOmUlD7JIqW7/MJhzcE0frC6qc2BtMp1rLMzyu6Z54HwFKzaHeDgSgnAQR/8L/fS
QwFiBTsjYXY43GR+529JFULVng0rBbS4Rue68H9Fhg6rRxx0BDq3tdwc1xLPSal6
tg43iehB1qs6PT74R2K0P4OTOME=
=Pimd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e5c1c69f-f8b0-4a4f-a5af-69e7fdd5f66f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAk/++3P9+bYRk/cEnw02cUrTSyGYmmNrxp6Oumd1l6xaf
E81t5fK+0FfEpuq8wJAHdZtJ/KhNHRQWyD2Yzn1x2ZCOZ2dZ2h2g/mQkbwjf+ewq
FUb3e49Y45CQqNpM94Qtd/Te+DzVh4igPxWu77aB6IpYp0EyD/QX0lQWfLjHn43S
8ZCJMkTLgTmCDhmwpI8gCw+DubBAp7TYrytSTSWrhSc/HXf1WIbWpNnf1lGMhe2X
j9D5puVFy76IISZ5Nm6HzKkHr4gg2pluGIX0o2vEBQDl3Xeah+uHsHI6b6/Eo4BE
iC4tdb+VbieroCZZqDSBP8nfmV7TPXDeieTF41+0uyBQbCguWHmMQfmoLgfoGXH3
2fdxmUx2uLsSZDBGwxkJil+Kk9LUr5tB1Pp6DUBQV4/uPpJpHqPafrLCnby5xJOn
z+35D4cFZv35UoFcmaTVBeQziE/vyD1AOWSzjN7A2z7MlXkb4ZH03mE6isI03nTI
DHCVdfqiy19I67+H/aGXcu/tlD7die69ZVvIxI/Gh2923f7HFwpFb3WV1xlTvlRr
4MvpFCIlFwm+FVeC+kZupJlmGLSf0rT24mL6T2h4mTkPbkZQsZk5hvOi8SirwlAV
z67jwPaRIPl1Pcn0gqS/85QWJwbfSSkaq7tiH//8CfB4V6um71efSVsQaJ40YaPS
QQE9QO7a+P+yzcR1p03ydl2ot7Cr/xXBnVpYh5wc+aVneU+Mm1kOt4P/E3rfxaib
zSNdm1O+mFzm86wuRWy6dUSX
=sjdT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e73634e7-b8f1-4cfb-a3f2-bb983754000f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAoW9JrJp1yaFryhyUUn76/p6P36/KWhQsM5fifksRDjmH
G06PpfG6lNiQqSTufBF94AnT1Hrdniy+SCvCMyUcHjvVBhcIj3yG5hGxkXtJDJeb
XgpwxMWHzWdxOZ7MHOnmhQkiZH5dU9DclTRYSYttJgKZLWxC0mLwktstVNCo6RSK
M+SRXXFxbv4UKMWjT9KGt3+W5MQzQ3tLsSEdoNVOXvG+vy9Ig8vOz7HDwfMozRxJ
Bpp9DVM1PeQ4YxAzeGdQI1t5Xg4ddf4gn/Ac46HpWn0T1zqiySAq8KL/QVHmeJfD
q/bZbSp/Zf59dWv+zpBPghFGVwZtjrrrOVS+SRHLe9CmjAGzzVOctY8BIjT7Fb+c
Zch5LuD7xntJ51Toa/5ty7XNChKvG+Qj9RhiQw3i+eggRbAO6fDx8m9U+vDytYsK
O4m5fDCTIRMZRopZ6Zchf4SEgKbsCvqkOA69J3V8UAobE+Q51GIfni6xPirrJhkS
ahkwrGP77hA0rwEdqECRQ99N+1RdUO5fJw+U0fwXP6c3khdKmaP3v9AKuFWpJ91u
Xgwvwilet0ioqle3b8cjyoepxCOAcarqNz68Gxo/AFdVlNQQc2kl00iiVkDZixe8
5UFeb71r2h6no84NxWHUpTWs69jeQ3cX0nwO4gJMRzw51rooetsj7/0DN2FUi9rS
QwElcFfBbAssOxoYmpK1/vKiuooTb0XNukjC7ymZDXSS2rZZbJQlc+QADCLk7D4Q
Gn3LRX8hnE0koPWGmoirFPb065M=
=yReS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'faef6a16-743d-45e5-ae34-746ec9ca0008',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9HZnpx0eAtab/HylPAb+Riff+3Q8XUwc6kOEFwlHO8GxQ
54KB57KdoLosQKagAt0h9N436e8o90tRFoafEQVI1YlrnLYeCjukoF8uyyBHn+pW
+x2K+1O3Ur14y3HmolR2lkj1BjH9dsgZrmWw86kYSGaqlh4Yc6HmKE5UqOaL7Xun
rAAFV3QjzT/szBhug+u87mPx7oxn0h5ZtvO5Hxi9ynaKbo/fLHzbeC4ht5Bhj4x2
030tafpoboFnLq8O7yJ9dGOoMcabiM9w1DHmDrlaLIRGGKxiXF1byLSSZi7lJCet
/QhEv2WgWpr4zsAyQbODCZWp0L138Z3Xaag6OdogxShmpj4098n4ecUI3vy41vgx
yzIC5PgmmM1Zeb5nFCjQwwUUfTRYBIkwx3l1n41jjiBjFPUuT54IJdUyOnW4KPIi
/Ml2+6PsBUYBHiUG/TrxDRvYS8xvjYzLYDt8PjIX9bzlhoRLwEx7iiIwOxCfdiml
OyodXgtjAMmF9QVaG6DjjPr7bgLgS2MUA0t5rUinZe7OWyv6ie3aC8ivcKkw0TNZ
Qp/RO0A95y+dWe/xQ+DH2Osq06z0RdWsk0Bp3EzajYNA5nQZiBMgEE86XTDYaVD0
eyfSI6sAxRnMsluj8V6KPfkDZ8RCJvl0FCa1KZIWbh8oHr/TFfokHlz5UXyd4Y3S
QQEtz8ujIVWiQdO/G1nLKE/tooeTMwPQwoDv7Y4MA8m5gx/vHFqZ5kWJ7Lc3voeP
jk07p3CP4rJatCXawpJpsnsS
=Y5y7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fc6af850-5eb7-4366-ae35-8f3b100d6afd',
			'user_id' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf8CBBcQ6t23d2dD4cqmsJaa/EvFYC5tUP1UzP40rhPmkss
pFZ2R1uSTxrDH+zyaTar/dAGTwnX0olcFIppI6uf7DgIFay762G4K/5UsZdRx55J
LgfiLE3AzwEmsgZ3AirJ9dLZahF3lLbw4nc1lae/7GjynM08MXGr69DK3E3nN4d1
eWSoCV8De3VR5t/tEZIeuLR8DFDj2QLf9R8/vIHuj6V97bj18gyaZocSULLLyzRi
AXMHrQAzv98FAg3bOvrUMPPqhHOX+K8T9NeEBbpZLjedMKZaOdYfCh3hXwqDOMyM
Yz2irkfv/FmYKCDkgqlm3GxzcrES2MhmF8tHxbwBYtI9AYWcL0hHWkAftvo16l+d
LEr2UzRfhnvyAdyMOqCDOYousiMAFRwLCZ9+Ba5T+U8FmZwgXAMh7Q/dMFvLlQ==
=Wu1O
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe09d31a-92d7-46a3-aa10-f1923404e99e',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAmutp9w25rXhmvsNQr1RUTlWb10+x3bMqQUrQCtwtM/xE
AcZVf44+Cmg1Ooe59Tfro55V5A3Py8hZeAtnNtjSm9I2zv+lcG5Sbm8J2G0nUvSF
NdRExKihC2yAbloXZR7l6g5k69HoG/BrfjP+fNmni0RlIsDaLgB0nt8dHFsGdkDT
ubv27CbICrY1tXEYWdXwKdYZl0oJJ9OckMtJP7rN/Cgf7zxWPl0d1v8HtexvrfCp
qx+9D3WUQMvQV/zGFXZ1rCDq9A2+bpEUoOQYeWijJWhdQpwf6GRdvucc1u+b8il6
PwLrfArORr3FXkzf11VCYCpuV5Xi11wu41KFa914Mucug3tbJPz2k+noGjhEPA+1
mOtOCZbZNI6YaGbzdLhCNyG9yARGyBf9uAE74loSXEWGYphF+nT8czNs0/F8Wf7C
vdLgYx2DgVzm/7i//aoJzJ45r8KmzznDfJg0axFPpbRENbcAJW0/qV9Q4nJbcDkv
GiBcbS3XdYAPxRFeQFyUn2LZ3SIGjlj9fTsccdVA0So4WZNZTZquNaz1IWLE2gCQ
wAQ2zTbisgXgayuqaaDeQi3HSX+tnIzVekttymbeANlFI9uL5MTudDSQMdWYDt8O
akHv7KJQxP5BdeM6BT55XnWIYvkQrWpxb9VKrSSpr0jrbfzR8r9gK/3Az15nU3zS
QgEU2D5+HEgk7HP8YTdGuBYd/09+wiPHyQb9TZjbbaJKipfebKbwUGrkJiMzzGoZ
Ez4LyMOJe4J3Eupw+ufZ201U1w==
=20Ps
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
