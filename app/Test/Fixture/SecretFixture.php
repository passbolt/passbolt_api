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
			'id' => '004ed24c-0424-44dd-a07b-96bfb3c2638b',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAhgQScKPMDSK3CykFB50+QS4+Z1ZPAF5kP0GrYYyUElUE
tKndXBpFBFRFrNEIuK+BZZ0NS8pRKYo1QAvvvpe3KvoIl7X9zWK2pBR0NSOUfYrM
iukct7X+zmJkyGbYhxNS9hWZpsly+uvE1sjENiVybYkwK8h37aOAtsx3FTQHco8N
YtDSx0EM+ExMSAhKjVIoW3OIKpofSv6AhHAsr+5CZQ4ff94Lxf9vopZR5C/WNDor
f/DlsOBGNzXpM4DIvgbUU1S6iia+DeHffAHNpO1uEmFLLOg2ychSOx2yQIHHo2sL
U6uHubq45y8Vu3dgMy/jsK6bg8cw4f8oR29/mnDFDtJDAQFhafjua3w0M+JXdif9
7rpuN/sltQonQeU/osHvjL8i0Sn1Js//jUuLiXrouoBwslVJAiuKVWAWU9WRhI9j
eYOFwQ==
=2Ftc
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '00728d26-5034-479e-a003-59879464d592',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//c9g+NNX0a9xmg3iMFz9SGq6VADhcbol/o1fhQuRNJBlt
xSSu47F76d0Zox2UWs2oCDxd+Dm9yZPXe/Wy5ujmdKifNi3JtwQajpyOZEjT6gQZ
zmcroUA3KUxTWdNxPLDAogIlOiatnXS4NBHuHT7zNhZxKeQF5Z0F0BGBeQ2zJmZG
HjWVR8X9cnz0neEMyJVAICu/JEdyzXyUj/Qm+9OIy8WYeo6SyVlEl61EjuMeIoZN
IFOJGUZzqNkwkqh/7P/ogIKzW5ipp3rbPQ8oMvH4mBriiNo1aC10z+2bZM2n/YRr
3G4uDXV955y5YJIF6SiboujUNUKAR6Rl/TkSg/6N2U09PBcJGIKNqPtPmnf0yrvE
TSS2waWxuFrbcfxVADL1jecwCrfdNukWhSG0eLE2sAjDj9HP3ubnIPNXRgLC2Omn
nODVNgEDCXAECKb/AzAQx1qq18/8kuYbTPaTI/6CPs1umVdHZzsD/Hs7EbHp7d0q
ays3tPcoG8oNLuIJ6Cn9mDGCCODPYBrKyJxqIWV/gxI5225i9YPRnrpUHDadGq0H
bNKDGUlaMkla/oTNy58bt+/mIY8qCEahOhy/80S98oyAe69AWp9kMilkVXu3VQT0
+1H4J4IzmpKEkstRSzmzReHIY9YDdcPuK0l1Oa2qRhJ7FChpM6ixx/FBcdm7bEbS
RAGpEF0EmbFY3k/+kpijcodeg7GfO8Dw1FwpdcXuLm6VG9jaHJe0Itt9urpcQnPe
P3JT5BuYf+n8EICols0M8j5nufwg
=aQSQ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '01a22514-6f5e-4dea-ad67-9f863627d5c0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//QtMw6S6MSszfk9X6voo2TvfWUj2r3IZ/Aq6zm+wGxHqn
I0z7jvGhLkEN5Vgp92RHfJm3NmKNi+8DsYWgHEqTu6e7Mb0M3iUGcxpst51uaPLn
4FpIbGbag18JvoTlNukCfCQYg5dQb5j5c/Gz+YKgnFM/avzb5tg6oxAvVYyqpCtT
CJfPlqlJeDVrjDbth+PriQ+aqkEAsDZbqCpy45s/aGgdDW6tUlYm6KZxLvmjip5T
miIoYi4J3z8SSB7fWToZUSZw5U4YKONTpZmb/Fp8xvb7ZTr8qLgBe+vC1m2O3EeG
THlIImmK6p961EcEqalE3RQGkHOKxFvbuKSaf3BpdGzOP1lG7x7bOWt/y3nkTe1t
hqc+7G1MAh/A5H3gloyvkCiy6U3Qo0bQ3xNwGdlHJajLjQOl9SIEk29HbVnDnqeU
4WiFA4vqdg0dglYMjBtCcV+jQ8Ug7jjaxnMk0mG6zo5TMpxYnQSHd0W5Tqt2E8MM
QqUDXEEEM5OyYjsEfD7VGFQnRb59PKyAfDB8CSD7cWz6M3r/uKOZcZNJtLbITmP6
qWOVvAajDhJpC0tfPwu35Q4MX94hHTlV72wsY2pXxKNjT9yH//uQwYAG60+YYjdV
UDLFo8pBdTcra689MEc6rF8Fw+fvSrsDFZMS49S3ymYLV0lA8cPSiDvmNzTaaDTS
QwFvm9BWkJxeB58gtEf/Ytnlda88sOx6ZRz/hQDDYQSCD9CkuNSyVfhzhWeNBnnQ
n1xIA5fLQT/xbGwexu7Ei2z6Yok=
=dpYN
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '02be49c0-0e47-422a-ac30-9d76d2956ed8',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/Ub9bfoKB/zZfsT1DGJLvCvJPUmPl1BDk6gf9QDz5PbsD
kQ0eNE2RaKjqbEAX1M+fHXaHDPXLdYO5vUiM+DBkO4NXqbFbxfJJA4pBayLIN4WV
o/kARynkpf95pqXUcSJGHT07yJQVpthPBINz8yztpqaR/k1QYwVm30cFIhgaQqL9
Q6p83n33Ba5nRNuwybflfYsNFECzRqj2wFjn4fkRbQy4wTLnyU7NYWcZWIJxdtU7
iDLCaLZ5JU/aBO/kcjgRMY2qXnT4bXm6pJrjDYSjBCbnL52ko4OeM85BuwGuHdmv
Oytmofg8Z9Lr22oa1WMHutfNchGB5ZEijDlM9P7fw9JDAaWrynarn7Aj2FY7xm69
MSanWWMFtDQUTIsx6yer0XV7j8CJ2qsO+p1ZrF5EpWTkqen24QAamespo0k2eSAD
8lY/NA==
=GCMe
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '04cc9c1d-4732-4e6a-a761-76973e9944c7',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAsfSdWd4MUMP9jfMp6cxIN2pv64+qEMUrCGKWo0iohCMr
Xj+32F0rhNVzraV7BCrnWZWMBqB8XqSfV24pllnWhwetbHmMGjiGmACcozHgRIM8
ERlZe0VKOECiOYV8AwYVIk8Y5dVzyf1UhdWC/9idWlw44Lc2IY21piofTZ/+Bhjb
nCovoE/n7aworW19rZqaks6RfwUR4MnCYwstuE8JhaMSga/WL+SMdHT1B0Xk/B92
MX09tRN7Lr6cnHZ54K/dsSXf7csTKDBEDxy4MnHgmPwV6oTpz6HqjJhH+qGdSPWL
W2JUSze+Qj43bTjWhFZQ4sXvhRAy/cnqJsf1Q2RbgkVxPsjPZJwzeOLFag2tXx3t
4avt4kl22HHYPVj+TgZL1ACzCcaRcXZfpUT7JIEZCKVNyPigglPfIHC5FLm/l9wO
VC5Z+yH7eIZTNf3qB7oGHlzLrjRffYs//n5lmFKYD2QUgxJV0y/suH2LVpZtG03Y
Pk9LUrqPVBucGDC8/6htX+09v/3jJCoV61vardfOOMcWl/YaqWY7OSDP5WNz8coJ
2W1xQ37hwMyKJ6mB4dJgSZDYXC/tQ2gKCwITDYGFo7UCW+p5f+gDzO64h6Bb/tOT
X+QdsDKe/hmxKeUt0RR2xaNivhRzrrcgBERPN4TADngo3GvPiF3FLXsxZcflmozS
QwGxSmL4PlpveTjvPprVHanvRr2MPUdPNKTLXAjfBpJpbRtPG4XO4UwyKN520WPn
r8LlAwNXxG79yKXJdiOtBLxVkoc=
=Jo5C
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '04d0fee3-aacf-4f80-aea2-332a65752362',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAArh5HPf1LrsDOPnbtVvK3zTgO3Q1d+cdOskrCtuDz+Bz2
1TNlfuwrsshOP++xX0XfyP2o1TQT6rS0YYnlyVrovM2XXTZBoctB/01HzC8ZI2Dt
Aw9KS4lYEQuDNHUFHVZg4fBe2LgSevTxPaJAbFRnC6ATmU8LK/iura3j8/C85DJP
quf0f/U6fI3kcAeI3jN+muT+Pfo93E3Zm9oDZDeUcHSS1szBtUypH+bBiiYWoZud
li9MSxoFKIm1msDsaRVQoxyuDXTuI/VthBNRM9vnH/q1wvSkEHmqYzvliSxQqsox
Rn4IGFiSnxqfIKWtEyC90MZuYFAOjt1+lCJlY9h4tLMPOTqP8VL/W/fxqymsDNWI
lIqzSaBcWF0Ty4qk1KgTzFxh3F3lbVWBOhKdmCoEBDVn3VtbM6zF+StSidRSgKmV
CA5ahdQBYdWAknTitWA/z6/4R/JYeWSUuEGvhDy7eqXNWgCatsgec0zSKMHjUG07
ZcwrkPpWprQyZxyU8LRYO2rrTrYM5yqlhnwICBstsE8juaNFaQPNvesEZ45KMz7v
KxuZd12tPFHbOI81Sq4M0hjue1tagVpvAMItR27Du1XiB+1shGS/L72Xct3tlz0R
m7+xGy5ADvp15AFu6cNONNpr8zXsh0hhU1RjVquohXdty9gnnsPDSYhjHIk8uSrS
PgEXhwWoIr8ZKotNVBwQrZWccOn9qc833/sWFU6auE5B7pwzfdGQttLZ6awTZ5lK
wZfTJZMxTuo7TDbn5yjZ
=8n4/
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '055bff9b-b4be-4f52-a407-94d65c90715d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+JttCtcH5fJxMcTUGzwoA4E7bC+BN2SHf8Hq64cDuinBg
M+kp6wopZ+JE9EOcuT5jUVyvftD3YjYpVUXlJMofx/HRY9iPMnnNQs6JBb3eFm+O
/mgFry4I+rzcDNCCtCrH0KZPY5SmA9p7wg/A7PnZb0bXifk4Ki4E6pr3S0RMPOHr
18RamfGjYsW3hzCpFVbWSCb+6ee0MxRh8S9TNr40C+bcA28YRxTY0DOQPfnPHw1q
Qqbvn4zmsMolU4q19VY4/nJAnFDb7sBoDTLRyCdcAENbdVqGAuEgHhCHXm9/LNmI
R0LkJwF6qarkEBB5sTNWeKliuZnuOJbn54u8SzA3B4y+AAbJCRp68sCTid+qffIj
9PEdkAh4qA3aubZouLvu/6l7mAEmDY2rBEqrIz66tRvQ/JQCrnDqQb8GuZv9PZsT
tAwuneG8oomBZzfQ2zjQzg3hkbq5ySsPGNs79NufP5J3rKzisWBoQDGewny/OVZg
ZXRmV4vtFwOc3t0wZZe3pr3fS0vZNSMgN8Kaj980KBxtegAtjE6H1JzZUMkNU0se
BtkFIWcc5bWLngDcKaFm4wZsFrd6GNHV9LSV++0bdwXJvK6FTEjBx8lNvyT/Rv2n
h3D3WbwoQ6mBBwedJZEKCcWIH3Yfx+5jrn1AiXMfdoPfqDplACXeMzulb1XEOdfS
QgHx/Ad7G6fuOfIJ896mVOx792jzrNhnKwCJOK+7kbX3g3BrOGbeHwKcLqRWY9tZ
CrqytAGkCHyFpjSfp5J+XaxVBA==
=ppm7
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '05e76e1f-93da-48bd-a943-a4aac592028a',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAyEdVdvKUFOS1vRMQ9Pelwaw1sDxlClrjw0SPm3JUbrG5
XouVBn8qLY1xIdEfi7L+7i5YpbhK8PN0GZ7z+Qe6rjGkj6dAUOK1qB6q2YDc2TyM
lJ1LO1DCbuQHKn4z2/ujndmkVsL5Ja9d3frT/GimR0+IOD7+I7fwlFmlGc5hejcn
kyRz8WzHCxMxQ1wlDrbMNtuweA9CPAFgsp0OvlphCH/gvdMg86um9jN2kzSTaWFe
s9IHKHKo2h79TcKNqZzftqsMfmjF2B8CLEVbEx9OOY4k5afaqa6u/vFr543rffCE
0+24s/2yPPJQR1qxyaCwRymjwzb79ZdR4RWOWvPpLZ9+SPRkIjNky5iyJvcOA1RO
STL2HhjN4R2IKoG0GTQ9xh92Uv0yM7gE1VGo9uOQm8Db8hhjPYXGt0Wluz99DRuM
xMhIW1UYrs2G2nC1wwXxTubs8iLhRmhaKSlzeiLGtpTGFhf8dwnN99YM21LOAbHn
xtY+q+51+epvMRvSK8LLcqKAdK9FEfLB0k437XE8+MT+u6sC9SVdKteGe8brg1rx
2wZMYB9HrGV0GzZFDdsDlOtNLhOh2oGeuVIf/nAcnojq+y13m/4PBWc7jg8HZr6n
8Kv9nVRUYqFlTD64V3fo91H0ys8wZe5STX31Bl2twv4mNTBPxio6jptZDT1UAazS
QQGnevEaFDN3celrKtwQwtG/kyrV600FmIoe/kcMIP5o4vIM3D9D9+uncWhHqqhs
HBZiD3eHGxN4/h2F4UkDA/2l
=u4I5
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '06b819d6-9a20-4801-a6b2-a17f5b36ec0e',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAkjm5GvevBubpOMIPctxVJVFvZunMwIhkI7ukXyzbKkce
ASkZfHai7XeY5wl/HX4yiw3bxQa930FBdj0flDT82jWH2H8WlFLmx6IXUT4W7Wab
cXIzU1qbFHZqVqCJIXYsar9ApynSLOb+3DXk0pFa4xicd9dJScZXnLv7gmsd58WY
Ow62bi4HqIThVOaBtCd5oEJG8zhZet5Txl2BQD2kq3VSa7+iFqj+kUBoqjy2yzJB
LcrpM/Yk96VmJalPkMj6JKTFBsP+FOwbjza1jF0bKf7xtCehu9yj486OPvQ5Hjf9
Aod5/jM7zWk5d0nPaShztmEKypng+DWu1DbKakqBmJVBRPEnXmrm+mx0SYOxqC8s
Bieo1tO9UW/Hm5cI0k36MO+gy7PhrQrVbU8unO1cAnuVHmf3wylUe2tMfW2jGt8u
ZMoFrTYRNsofHkXCP2AIs93xBygPOVagvcs9+npzq+SKs4U5Daw8kW3GmzDxqemn
BA4x2W9tDD6OYBkReGwX4D7M8GP7GvtJtOdBWg+nGMsZ2wuKvb08YxD7Zy5m8T9f
Igd8CNw1SND9gLk4OtZMx1cv5BwKLTpbm//QfD31tp53AgaXbR2IBRuzXBYF3xrI
gZTOKRxA3lzqL+PqX1pbrq7jC2Wqqf9rVDtZzaTnwmF1p/IBm0gJObkRF/IHC+TS
RAEv2/WWWQBokPd8sSHPvwwcr6W8hplYuYhOcekQUos9Y07rQ7Z4gQ785ZJps2cw
P3pgE0pLree7m3SreoIapiSblfPs
=pxYC
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '08a69b20-c3b2-495f-a7a5-16673a2bcd01',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//bTnf1o8Chzkldh/2GLzHUaKrMlL0dIm0NlWusF1sg5IG
MfmuPz0PonidUjHi9GmE1GKEBf2jnk9XUsuxXmh4sOFNCrJ45hmDxj5ZmIJe8cbv
Tkg/KbF+BiFHhoawdr27lxZLOryxIh2YWgPTRUjgIKtS9qMgUpSmCp/O/xNhWuQg
1WZw6C7NpAiBVB47kwCdpTxis8mI05/TweP9CpC8k9MrH/r96R9Qnc3Oc1Qb90VA
i+s5Di+TPMPeCL7x10qzCOyPKsqFqHjEBMkNPt3QVlIT+14olvn2xV//Ga8WAtSq
ByG+jJa2gn5MKETGuNAmoMrgy/qrML1jPgVMl6Un+0nW3xXvAYLvtL2Pkj2GBLAW
IAPHf8J2b3CAUKofsrj29eEEd9Vqo1y9jqNg7TV+sKZBqmpNXJDZCK/cAZdRcZjI
jBKMpjfYF5nZdl1r6S1uxrFRiGO1WZjrx5oNRGRvSkpOMb0wS8X4tQsd7fAhG2hx
LsjW2eee8xxXFckNn53r87tOV7HLTyxqjhWM1uVC4okV0d5pFdoqJNqbB5mvSKr8
XmNrRdbNTo0t93MjkTv8/kp/J2MH/DaRhfE8xalRGOVH15sdiBhThon3r6jqJyBJ
8yXPEcmED5da8RJKHOclblJWvpGtvvt4CNWsH6WO9xY4OgWfFF4dtS2Uug1AX2nS
QAGnEdpOp0ENUZmFsKbVnR1J21bvRQbSLK816ehMbkbYGeaGvieFLehUMvlAEspe
eQ6Y2PD1pCzPtTANSIDGm9I=
=9esb
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '098d5aad-25db-4ab0-a2d9-9b66422aed0e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAha94gss57SjIGUsX8UdXSS8Y9fYJl3qh17VuKBmFpt3C
0o+Oy/cZ1V/4+WfKKS8BHQb7WjNGOWVbsXmOVjdpx7L3O+2HQd8/1lgB0uquH/Oi
SXt22P4cG1BDBLXxtAZ7O+fVE/QD3hQY3uTgyu9EI7J5j8dutVrR3YCQE9Y2tD2c
m6IsGYOFAjYKL+wVfukvuInaXFYPGMHleQrIra7ub0vPPaslgzAsx3OY4af7Frfr
v0HssWuae5cUj5BuQEYtxHufwXRENRnA+0WfGbGLgI6ZsccF7TH9pkyVMldFY012
znKnFUYMwUxBnTuHgihrQj/+NU1Byzt0r8D17in0tkZ8lO8s+G8XBKPFnGoO0j1O
sIZXKhnFwtGBhTXCk/PFkwI+z+CP2eSdj+XwRsOhG6RKuXtrMM73Xd12esc5qo9/
FAFhkzyYJ/st24cdUnglWxqSfS49dgcLHvZ7IgmOnns8KioS7JZVOh0COC7Y/axm
6VP5+31GDmgt9IgIN3TirOaZKR68Fu4JN4lJu9V/68iRSe2pfIZQiXlvMoqyuott
ADl6s3aoLIcJOTHH5OqRCA/5G0o0tI43wtIhLR4BgKC/Rnv1UgHNEs3NjCQONOmG
1f1VAEpatMR52q2WpnmJswNZMi9hKLVxP7dfS9fRhpT/BYx5rmOkrpPnnklS0yzS
QgFS+31Rrd5eTydGCGjhcDh5qR5vwEE9//JNcg7wvbzidnGZ4NnuEgJJhYqKnPT2
8A/PFU1jISdRiC40dHM9USnIPQ==
=o0yO
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '09c7cd37-bdd5-475f-a29d-b8906a51d7bc',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8DczxzkPEJdrXejFuox/htbH4/4E8//zyM35mB3EprNOq
uCwWsxJwq7oTfaGWhtToxcQJRon90v/9RQrmP1tDsX9LVBxQQwXZa80fr8aX4m+X
bOlGeb/VUTnH2dQDEb/uLVTbCocYJC+1wmv/FfGNl80zvmOY8wOjbv8Bu9sYrk+w
gfIfHKmhQ9e4ylPZ5X0DaoCpVqS8aWldDm0PjYAwhToC2ZNER3ijpPgDznDJtetN
24UKcL5Tuf1ZuRcCVdY+T1PnAgzz6fkjP9TToiCCCZBgY3GfFGyigrf+dL76e0Kc
5gxrsk/Tylb+epN3EmIRxyEY/1H9UFxTgxeAkbhLk7bcdAPz/8z/6mFEt2FPkOiq
TR7mwJRVsTkAfzHJyfYnDzk2StYd0iwWDjuLbN+ivJdcZllV14HsmrH8/idxVtm7
zSaCEMUNp5U/0wSI6/Mvm+b0QMtEfRGLcxN4Q3Jsm5VU6F4bMtwhUFXdQU39S2Ai
AcHdpRYecpch2LAh3OXkclYPp0bakdNoC8IlMaNYCWVvjVXisCs5YbMr5E0xp+aw
/ejhsy/+tIosX0QXzUmPJBm2+dAKQoT/ZIidHdWv3H1z6O7rshbjDtnGoRkTkRoO
QPzoi3Q3r1HBpIJPFv99q4XFT++GV37KjgCxa6a6n3BTdBVa6k57aCU4UqS6WLDS
QAGI7YvZz1dpxJTTfUqP/0J/WqHNujtKom9x4h+jI+BhAlnh95hvi6qoWC4P9RRu
Vc2u6YrYPvV5Qj4gpZcDZP4=
=UvBz
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0adf1ea1-dfe4-495d-adf1-e3ed8bb62f0b',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/7BmgHYDT8SrktLGXXaM2LK/MtFLBYAqZub3MPVO31vbOq
anF/viFUC0sFpRF8485CA2Vt8Hf/4W2rFQLXiwjNEgKmtCOgr/4xHpCjLUcXemU6
6n/CDtyD986otLv3gTujnzTi6urPjiUNmxqqv+lFrsqueZtwuCRVBJpk0bi9ogo/
CuC35YoFxhdWJfP7WEwLTTcyVrvOm17O7Hbm832p0SaoiSE/09kHK98kJyOCc30i
ZJjBJZC4fwKZNnzs40+71nkdJgRjz3HaBTTgZU5sPtie1iNsZurNszd5bEtn9Wk1
cGnUYxTzQTpepi9PZTh7XEEBb/G56Yb/4oq2+3yTZGpWdnqiBY/Nn1xptzt669AF
oM73G4VcXbdG5AM4k5AwfcJ72XeCBQdyoALloXE32hb8gBdubyMj3SiRyXRW3w4c
P3Mrwgn009cAu8UTmiEmN39UpzzlfLC3Tviqg+Pkorg69rMGaa+RA1XWbif2398F
bJggCODUi0ZDw0BtFibfdoaA/PSBMn1+7MOAM+jrYP2QOKeue5kgBbDrRv/9BvVy
nBi4SMUloSGy9UXagWFjBp1QcV322fKqHvdBW42YjHvxp5DEnc+0KARbPxmZ9quV
W5GL4gyUdZHKTsHJS5zGyTjgUcB6if5Nm2B81zENsTF9EoKUF33iH6Mg1zc37yPS
QQFM26uFChPZ1avS0UJ7zI73ah7IORN8zV2P+u5ZaLAhI7f1CUFa6t9GWh43xTa7
bjCM6CFltlW40G1NiP1Oj+1c
=HjHh
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0bf75511-1a9d-4de0-a9b2-d9efda5231aa',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAApdHUkASDZN19D64umS+whyi3Fb3INk5pdKCc5l8f0Msx
rLIGyZHjQ+4+B9hn2fUPnlAxfR3K2SHCoTX9F/yHDIKCSaps7rdetN4IRO4jp3Yi
yvqYs4Q67YZjE6aj382BQgwWKWhkzF8RpHtMXRyCMR3qIG71SBbuozLuURD9eTkW
ligraQDTZv2yc6yB75YUheHxuGUbJZX/cyfKjrhFXELvzkK9CI7w0JwXRhMB9BA1
JUtyfy2zRCc9vn+IrHxVBWcI9zWeaYHM+kmJxCVG/filZQ8kC8LxlqXNjvbhGd1M
POIPJFiUoeEWrCsKetA6KZK8BPJJR3ftSPKppYsrHFqd3ix9wgcMxXQjxd1u5t1W
VLlecr2xZQX0A60aCeyNtMra1+H6XTa+K8mBnnsuIA1lYkLFxtMbG2C2v0V41H51
kWe2HdXs0QMh0xvs+3bQT6wfjobjFSJiVl3thrFTkMTILV6DjmiE2KXYYH6G/ubD
Vx4VHrrOqdvnFkbk4J016rdj0ZVp1tf5HmrXgRGehpMZp4Y9+JGQfwMMvswoLVhZ
J4t3JMwxNLxk+I2CmENt2GGP7YtrgnOdnylYHvJrljQ5Jz/Cxk2vVyOryZTo5rCO
D+WtX052z6Wg0HqMbdVBhkPwPwI6XEoBF4z6buRtMDYRuHPI9tpEBTIGcR1VDqPS
QgEq3VetB9oxc4Jblh1Td42Xj/w+CXXyCBh4uvGlFz/IaXqvcNbTWxGsA4KJGTjC
AHAAQt1sMRILMSdKCWfhv0wC8A==
=3kAo
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0d61fa7c-5d21-47d2-aa23-81de7ddc5008',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//dZU3FkFy7ZjRTAf8xe92yLLWmKj0VkUN+vFOZMBvJ1bB
mF8Yh4qLxqGAMSKubS58KWAOE5Wgu3JPH7kWqoFViniNr8+Z2oP3+LeunlRI94Cj
IgDRkTmKMTSuIj138F3wEGyhAjgLlxhaMrOyrNwqWUQTE6rcP+1rDnJk4DpBkv+j
6iKpERAoFwx1oXnEQIsdxhdEsxQm2bSaWLqAXT+CViRsN4kgTNX46jEPaJES76gj
T50hc3j2sV3/ZzHQ7d0dCXzn2zWt7ySGPDJet1rpXiELBGSR8sYMpZNtyZtkZJ+3
qavL4AgXuiQAtpWU4OMGyJrSYf6A8x0otzjkMdhqz5fHVDuS7UyM0TsJO9Yc5OyN
XSEdiDJ3S3E59S6P5rY94Lr98MjVGJBFWQ6+FwHCWxLIlGPjoQsKOOjbkc+JjIt+
KCv7Eeu/R5WbQrvVSANpI/vrShUmzjivdATO8oQdmXzImT0dnxT2BET2J/TYF7MZ
9sgiS+R0V0HCRX+ohMJbZtw7Tqjzzv60zup+k0SC6xyxkM33PzqjdBi2DL6ISMFh
OE3daIv6SunLVpUY0/n08lhOPzTbtSlCtqb9zREPrQ/n3a97HByI5lepms21Pjmb
ARpditVnOaHgQmmkSL2+CkvSdby0D0BT5UHC8CBr3qCvBskXkQgSXfm+1Z8C4LfS
QQHrKFjr64UL6cYy9Z8M1bYhh0u024cJpRy4tYoFIWeUAWity/7/T6oGFwRFmeK/
krUrU9WAxdo08YJK6qnNvG+8
=8UBQ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0df6a530-0e99-4e7e-aaf5-66dfc576b337',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//c/0BBErXMmuk/Jqg5p9qMkoMGOrwy/pnD8HxzLMaAFrx
CiInrLlP6W1Whs12UBvlhwnWKtn7fK3prHwAgyLwbEfG+GE1ilASS/qMKNzLJ65z
BtNd/UbQTwudHBWL1EC/1TDtQ7Jlex5coYmP4Cc/Eog+vgXCnPltLThpK9/s4HPy
4Fxmj/8c8lku7P7GI1VwWJnf5dOQE6lHvq6Bj1AGxmi9MKgfoi5cV/22o5A1fAz1
PGIgbihtkN7D3L4VkAzHdwBcx5xcelOeQ3GBwcDVAZDhb40n9O+8DfQ8qWOzX4F9
STA+rW09wMmT7BHWdy/hM26x9UXq+ZrJxnrYKc7zkChxFfjTx2PxtuYIXBSNvA3W
TT3XmhNUCj5KZTIbGMFCJPpadDlh2fUQFivbBamkDWc3VzgXZ9GRQYjoF4d1ch/v
+O0crqTPiQ8LwDuxuThq4kxJ7OlmFyYmzNGNVyv/egcGEL9IX6ysFF6La5usUkFF
vLDsNHHB0Mk12KXunAY0ZsTx+2XS+Y4XjoZNV/8NdvaIHioin52lSYiYEt56vFBT
Dx7dlSSgxnBwP/IA+FRm2jIorzecARfTs0Efw2f/hxIJFxibAzK3mF9En0hbBjRY
PNxhur0v0YpT0k+jlbByDPI8MpmpXmzBKWVG6/NDPSiZVr0Zon3Msxz9+n8UDiHS
QQHD6iZoO5oOu1lOKsOx3/YOBFMyUYMRfsqst9GZLWGHWL0IhWS1biUlu8T2yX9l
kuQ9+vpwCY6syfU/g4Euv9cj
=xZ+M
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0f7267f0-e544-4dae-ae2c-8a87143b4465',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/8C8TAuF8FoWMEpN6+Ema75pr5IiN/ZdPMr8i1UtLOCKDD
dCjGsiClGiu6WKheb36r9wd2u5qU1qt0R+GIxLCvy+xc7G4CXdXAgAPKK5zaw1O5
ZjFtjVvhcmvdXdY42db/AO/5/pOLrrPGZok6JbNfMhpNkICqQWKyDNTIlfbjVHuD
qUI8o09sqpOQ2PM7/fBlHsRHVhZYSmGzzH69/6d5dKT06Z4cTCMZjRr5/bUJ9Wui
DveFZh5UtdheHsI6AxzEHvSMG5tRMsuCwYH5aK63ZVYlzekJg81Ph3WRbRKLiuSA
t7dcmhj35rWS6SnsJ/in8xE4P2w9RSjGfwCu/fcjGR06N4Qn6W508xO1sUyKmz4W
V1erpbBHTzhx9taedEua2kKpmTi+W4MLof3NoV/r9CAFouda4Schkrbqs+T9JHS2
kqfTbHUMrQnvUvo+focBVKY9BwmlITE5qS1euQnyAumLkqgAAqtzDeecxpTpGhTv
7cEFDUg8CdpfaWNEN4mhMGc44S00nU2qz3lAE3F+AkiVnn0Qiv3rs1Mcj7j8bzt6
rlih07pbXfpOkAzOpcd1oAgjDzu+eL98ibMhMAeNDBxzstxMwx5t5N8+qi4VLEJF
RhXLxftnLsSKvc97GdP/b7WoL0vqkzOJr0cXzpg/ySntbPbGeQ8hYm1o1egZf7LS
QgFBG1MTJ5elCnUCckpjLdFY5x1hkdTO1QRU2hjSHsB/NOm7L6RW4N9kBpM/748K
s4fNjl+f190o/LpQE/Qi1oCuGQ==
=5U8U
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0fc74259-2206-436c-a238-a474e2850fbc',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAioVkER3PMd4yApgfy1sIxXXw6OAmo6SuzK8twhV6eXPm
83POC9L5T/IEumv9vmP7K+IzMZnKB/WgUV6BaLbP+bug03kd7Yl0yS/hvEWyirfK
NqVA9uapRPlA/n9yePnPVsGhHZ0G01kCSROeXq3xdRYE+R95BTLQY3usB692tGBP
xHFHEYLqgLzj/44EyFSSelBvabR6oObn5Rb85I3WvQ5PdFQDyV0L+QyKlvr6XcEF
BHMiklX5cSWObCTY4PtQpBZVE5XbcZ/mdxKqwBTINsWuiVzYZtH1l/NjYDZdcgKn
dpJRl/hWu/LspwHX21jKDFPnoY/WxTHkOnFscIEyTHhEdp/nSi6gcyxQ8jZSCj8X
RPrBfxzInoMRxbx2Z/UiEigDcslpO2jdFmBYtG4JgfpxVyqItAzCSQrw0W2HtPUe
h/7JU+Ep/DYWsE3wn9Eyv9ogi5xf51XN8yQQkFoI9IIa6ujD9zWcZI7uWBGSrXAX
f0i0+G+9CIbC9Lxy+UJI0pu7BING5VAGrl7ZBp+V9RAltlSUigwXqEAE45LxcBvh
n0lfLzDWEPjqM5n14MPRKCY9+mincIfp15qFeIoIkuk/Wxml8jQwu26oWnNVqReI
6YO0ri7qwaJPCS9Glf7r/FDldGWI/DUEjSCUUllsHXNVq2hZ8xl09Kpz38e6nNLS
QwGSwR3zMlcQPHxCh67KPqpN4+R6slsPQM1I8n/AOYvq50rR1ZCpGgDIIEvAnbb5
Of7pA79SnpEjK+QfFlfHBpAQ1Kw=
=t/3S
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '129d1236-1974-48da-a790-b12ee496a917',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAo638zl5tATyYBVu0brmz5EftyeMJ1HV2F4cOBKfRuXfm
lXZRQu2GHr13WCkidoxSBM2KxxyOkrb37q6fMqinag3ivrGCr1xk3gSKWq5dytrS
Xy8BAscD/BWSY+HWRLmMucy439wk3E2Oka7MR9PR6ue7OVaEeg/omsYLwsD1OkZe
du6JXF73kN2AjQ9bTXswmb1U66CpYdnfqJVsEX3p/Stozbrwq/FHlI+EXGrb4XzC
h69ScjOc4aZhLE7L9r8XLQhFFByFe8o7knQUX41uc6L3dmMFnvhOun+wHeR5h2ds
3pGybbVTKZqhdkjFg6MOT/wPSuR46GoEYGyR0Pm7VwuDtu8l5OZVpao5/wC+e4F6
9HGh3szYD2e16cWtT9QsEWBOdoGF66Ks1VlxJWFgJr1o7NcAmYGcLpnIhiqErInj
byBENN8Kjb93VJQunBt/OBKevsUwJVzcp4GKZDnh1wxb+TnsTzcWB6hELHCCIw/V
a/ACyBmEcTsZJJceFNADG517ux8lJdYf67nQgW4pzsrVdagXoxmz96ziZtZsQhrY
FBPkxXLHzqtrt/bXwSK9VBFPr3J3ecm4zn+WUxZRCd+ByrcgJNzEAxNILgwCdm2F
Lb0BTKgUOBF9k8ADmfKhSeBWbrCHityQk/aQMUSt8cdun9SugzwiyBYvBZt6RYfS
PwH+1Xuq32uiOEYR3eGD6GNlEsdGlLBtn3AA9Y9M1dTs2TF5mtFaR1PXW5ZjC56R
fEshVkCa7IfToLwwzAuAQw==
=UPXI
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1314017f-38c4-478c-a439-48348acc6af6',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8DpWITcFgL5VNw6RvplcyDnQSxz0sEYvhhzFDSzeufNm8
H7c7hXiZj4D9Yr4lDoNfxpNsS6aRrr3ulio4VwCooz6yfXMiphqjz5WOHOwC2uhk
kWuIQ7WJZ+bWBNYajn+2wecOfRQSCuUHvGgMArxDwYKPx58axFutZXwkcnN6SSxT
2bmQge6R+NKWbLJI+0S9M8vDYfAlP1r0VWK/ME7OtQoX6IivWN0p3LyIF5U0ahMd
wUJhay3XORAXlz0+N+2yMSu1KXKDjrf9NqhI50xxoAfFpJhfFXeQlmXAbSV2/yN0
HIQcHTIiEoDq1yjKtJxIOpsV87kJI/He/JLIp2yauJXmG9WFlae7XJfqN8lZH6Wp
NZwh3RPLVNvMD6SHzzY1NDVHtgMMSBLIaUP5k/0PbXRMp/EOgd0OJ5LzmXUWBTqw
XRKHkum0EI7gnxq05ByRrQXNEFuFMa8ATxjAQ7jPp+iBPj1+3RK7YPKk+bxFIEZq
YLRSU/EhADFa87bY4yiUdqvMxpNYtzqMkJ5kWRDDg0emT6Eg7XpXzcYqh4MSXC4Z
iHtbPo+P8dTC2aAPYBKirmAInMK5m0brTfNDRk171DA0qhl5HojYtzLnXGuZqzxa
aXgH/g/2Uxjnbr/VbQ4ewnhGQt40bRa7BIpEJ/L4vOi6B7VtLN8/54oHu9hxFvTS
QQFjWUdQjEw9jr/xcctUD7WYmTnO/ZoHB/g7tUPzRzZbzcRVtBRtGN2H9tCjQyhl
w8MSVDH9Vr04dTaNXbENSyiO
=r3r5
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '14708ac9-f8a3-4bc6-a8e6-7d9ad625e8e7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwSFQh0wAsQsh7vFZPHOawS3tvTn5vSB74tUtOcU9y7On
Z2NLmfPu0hd/zgyrxWx6/JLhoplPaKS95sW9ue18L7gi+DzB3i61jhov1g5vwmU0
ltlJamq4/p2LZrdTps9jXxkd3KIJuHJHgIZ48WmYbhiWrbj7467e9AE9cZGPksrj
1y0tjP+z2zvMyRj4z4uqy9HHv2Sr8/qApU8kqW6mNjlB0WzRPz3sRDYrNnfC/i6M
+zZtu5+rqfBQrFcH2+rikG0HIx3AI4FcTVsyIXVFOnYegmSYB4JnKa2QDbV8gIdc
bgopFEj7gzwNC/I09Q4yZzu4k5b3BSL0XJsrsY8MTDKEHv1F4ljfDEyBNGUWiafX
IIDEfufglB9Q1nwNizbSVUgzsdimSpCstn1Jp1jgrDcmBDzZdGnxtV8xO3PZZVpR
K1DMfoLhxSmNYTkBao+G177iYZ6YdXGchsIxwYVS9qw3859Ux58TFPfoenKq7ZLK
idlvtN3fOyXhBXRNirpz23ZqRJwDLit1c1t8QVkQK78NSPGIC1xa7q7sTTTKE3Tq
rx8w/R57U1izy2uFL9a/5wWBcvhxvujdxcNqu6KIFTvzWI1wBiWx8eAA/F0Hooqk
2tIpbIj16N39jyvdtj+XSkeeLVikks2gkYiX1d3DfAJiacdrkGZRCjbwbIL03rnS
TQHLxPlTwO4qHrJpbTnYVO/b1ttSXXdYP/SKM/46CAuyL/psNuDHHZyRvIeKnQBe
NTFeEIwJpP+3PXmIXOfCQzR0yLD0YK3G4D87iV4A
=5Oue
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '166b8a8d-dcc2-432c-a9e5-e47e4d5baee5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+JUNPbsx84t2cP1XF5iq/IH7ytEsM59fJbFZKiraGL9Mu
rpoGuCPt3q+YygzxBkr19wfRU5GIjsPEV4xgCmhx07ATa+misuzUZG7Ed8BztNOT
tsMSMTLFSP2rCSAaU6nq7OOjyeYx/9fUoOQ2Rei/qoBwOwnUzqqvA95sjmNYPvtB
Cpj80fhBP1CNDMn+UHtTGChmkNEEII9/TwYze/dYK+NDtSKzyapTP8+bcxu4nzdT
hVyfgC4tpc+uQwoJdB6nOMXZHNacpsm0e8YN2N5yZcWr3VBE/b2+QftHDFnSTm6W
fnEbPrbHoB+YVWB9ZPhiW9NARiR4fSrta8GlJMYBd15WWtWqGCCSIslZcodefOdK
xhrm4d2acxS0egcJXfM0LyBbkrAwyCmQ4DLy+fmkRGopijBEWXuKxLvscJI0KYYK
jrSSeL7MTvM2onqMg4zEG9TVTqGJPfdGxiSgMfJZ+iO0Ny9OR4EST8iuz1OfyMM8
FzGrZvDUCTkwy5QIOaa75aLyIKnjbj6OL4nAVY2A3f19jCf97y3Yla6Xo63TnTnG
Gf7jV4Ox1dFx8ICB7oCERCrvNk/ICo0c4jzTslCBCds37YN0/sEtIpKIrXZzKk9M
x1c6lNlaHpfTmLRG9hMkHhZ6aeEmiDCoA8KV6cNLdLD+C/bzgHdngPrRYJhzgEvS
QAFAOfU2MH3V5f1URUXCgyLGv0tTxqhQhk3ObjWuL56KS9AH4v7CbrweUyROYjrZ
mVOu17eMGxU/PJnj4N8urr8=
=hrQE
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '16bf5877-c4de-4d1e-a678-26a2c1471669',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+MMq/Pd5hJekQC0wb9CZ7Jv91wBhqtX/5CHLC0bSHsFP5
/ih5Km5mmMJXx0O5EOHyBOut9Ah+dJnfN9gmqkJMKhvcJ1egEUNmrVg8vhdJt1Ma
UBdeF1mlf1p8NNyz7xHda1bcq6wsOWHMk1vr5qdd9MsApwGn32bR0UFRsHI1DGns
tgoXjz/DG4IqWs5+GwToWdl+vRVzwX4cI1kBbVbr8zB1Glh/yEW2kh9IHO6T+Rm1
uifSKYT5NchNu+DcSm0vfTk8iWbldKRNEG0U0pBUR4u/hYyW5gDhpWxKuDoWlp/0
CDG1Nxd3Ts481s/Qal0FECnIirU9A56W5tz1GcVvxIxJ7TKBJrbHDJPbX47rk8yF
k1rv9fy/7/oekpiaQdVMRTwvIGGWYWL7IDPNAPrE+ZGY8j+bUkXnbSSznjhIQKyh
ZHtZRkDIsUVupUK8HFsJDD6j85/E80NI8bFsHJ77Uja+BpcivVHYKe9YQ7DaqdJM
qAk0o5GoYCsb5xrK7chJ26w1tQjF697syzzyyw7t1aBZub8GJs+WgN0g1eBpzWhb
w4TR0oeMOcnFCMjdL1kvONMNg9Vnw62Gb2HHEpgI6MxvxPXS+4xOc0mlQ/SSHRs5
U7DOTxKadDZUSosGKybu53dFisirRj/udb9Zzi0Vuzvpuht+31eadAozSrNu3ELS
QQEFjtzp4Y4gNmRHnM77DBZCHG5+aYFJ59LlaUPAXbavg7d63DAEp3BjPq0dWAhy
+Jju4XW1SNHzA8wRC6oGQWbs
=leq6
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '18c2766b-87d9-41ee-abfc-6eae4050cb08',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAskqXrYzijlH7eIACF6WkV5s6SIU4iyMpS+ctn+FcxGYl
K0OFCUFWyCRDOjkQnXQ7WN7JUAqSZnKAs2jAsgzUD1ISmBa2yi1VlK31ms28LRJa
f2vv8Nj4T0Fvxaktwd19n83QcMVTU8DQZLWiNkcDkmTM8pRRC5V0w0qbzdVDUAV/
7CKhvItCx9t8GeLwz5Vxbfh3xIf5ibwHe6QofvDCxbgPq7mjsBrQzommOV9x4Y7+
cClkVoIKXpyOIif5VwYZAPl9K7jsz0b9Jt9l8QyU0fSdWnQvAgH4Ywsk57cVVtpG
shL86blifVgJd5h+vdcvJQSyHdcTDic8rAiTgtQ+EtJBAWgwa3swNfj6S1BYQeHV
uhCPcXlNRzA7RQmTFO4NEE2Bq8zFxg2Q8+HtTh14ZkALqOhtm65HCjjLqYkKpt+9
yx4=
=CjmW
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1e050e8a-751c-446b-a221-8000c257df20',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//SC6sxrrPTXqCrGy64mv70SZqNqitN5A+y360vZD9blmy
Ze6wEESVPjLta3FvXJjZPU6e57C1t0OhALd2gqi71Rkp/V45vTIsi2aD85wMhA4X
Z8K6jAg5ICJMf7qDcjRKnsiLq2rsABN8wYmWt4Twg5KKvGjwlZPgQH8kApUtlkyV
MkhPpy4xmwxI1cvCgnlYE4+2fjEcxCtlBAHX0sJwUGk9VRTX6OcDk/73h6SvYvN3
sPILOuLQxbUhM4K3eg5jLmSp1pmd/c21hwGmsez+jy3wdFADorC2KFIau9iL1LHB
WCAzJaqq4u7yuMeUjZEwFAUf+edXbKvT3xXXYdZ6w4Vp1FO39eAtOs2Z2vHyfSgk
dRa7lrqxfAtqV/XzWU+BpvpnIkEBHQhmJ9SypX0IjgFDLVZ7n4p04DB3N0Z7+NsY
58wKGQbwGNY2c+r162PcLf4zb+bOB0kcTAjKyreOTmpjjACGzkTNdcJqfIlAKPKu
Ty2W23F+Jw9uNIoMbSRwLfOoWLw030I18K0JNhxxJajnDalOCrrwqfQ6Gqhwjjp8
uJM80IZ/G9itfXrjEG731iWfYi9glQVVGg5eIwT71eCmSZM6He+B9L3Ff1uD9z1c
NqxOj+nK8vAB36WPYbIe9qTa+i6U640KNgPI6sbgHxKOJSxxeBQ/koba7T4WdYjS
PwGUB+PCz3iaJq3x/Kvaomm+ycJF6J5AO5XKHA6FrOeGZdNV+JnguOdWt1jRQsej
yKzHyZt9q6YbeyoTs79Wrg==
=tFyd
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1f0fbfaa-dad7-468e-abf7-a5c32a354b5d',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+LTVyeE71N1sh8cI/PE6mXZOdMV1RVfJNjTxUVUDx7gB7
9S4fUq8HOGJFSOtSuGzlts0Tp5dWwy7W/COKhSn9suAdNCci7wPNNQd5PrQTPcpg
U4MQNmklKuwa6wZbqfTh0D2rks+WD+rLmJ21T2LxsAyOpgkavEKxaTeprEdXfVkH
yMa3GQoGezctmgUE501SROrOmS79xY0FIxffxJ6zztmwauUMLJ1OO/ukWgkCmEai
J7VDUYb6G366/32qr1hf+9MFcBL0VL3v3p/1+x7+MuQQrsGLFbSmpCbREQndCXYE
UA5g0v1EKt+76hvvT+h2qzBsOVTxyVEbw1wkiBMEYvnXNOHJrWXQYFVWNeiVGmhV
x5UES+GpRcMGcVGtk6k4QtPP/JYylydT1u4dnPLIxThgPGD0I4JC/rFDc9QyO3lv
1oYbonBWNuMGKg8wasldWGy5c1j5xEU2TNp1GwWzJnG4pa7UVKJFawDaXnzIum6F
XtMT7ZprcXCBDVfpXkH/ovrKNave7XYImvXOALJKEKNgj/ou0aCHaML2SApEMLtF
CtmtoLfICxUu/ucceUN7+FEyKIgeyc5XJ4vYD+xoDobZuvjqzI1gRofOA31efDZ/
wiDJNQuRtuUg+7dPt1vFIzliQ5TeX3SA37ZuCc/MjkMoLMj9pXH+QE5zUHokHf3S
RAGRPjQzSWKyKbjM30h6Md0Q0AFugDBFrNLYAOwPaRIMDJR3whrzEfVuvp/NPSoB
fKxvy3xwwwRmgtn6Ov64DoUPm/UV
=IzO8
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1fdcdd51-44cd-47d2-a586-dab77dcc7063',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/8DBShYw8i4co4lh50gMWKRTjA6PPEChNAuRfYT7LYUc3S
A99meswrnKOcW093mxnR/mpGxAPa2qB1FHo5LlaxeoJkSs3sqgfhiefP8mTFROhV
cN0qcbFRpWHKCsfZf1atahy/UTuXMNVKMhvZsi5zSa4K+6EI+V5xFP1xwh6uj+CT
g13/QoM+CEJbsr23dxISd5OxoGfl1yQXqc6hC96c1AT9l3S9okBYnGJwBB0BMFtV
7Ci6aD6pQCNOefQLJEdXlwN1L12VfeqBcESEyyPeHL6sSxcLrIz9wj9XAcrKa2QP
qlqOstYhjdJaijhTAKoeZRBocHksnPSd8clDWRI8mI7lQQ+ZXXI9NWTQb1w3UAwT
JEDCTJ6MAzZQyFwcttJ3EUOPATwru7KdcjR5Nb6s/T98nWi9LzUalpBxzDiuGPU3
wTfj7AfdhHE/yaf9QC8qHllwDYddWFh1zfHMtYnm4qERUQT3x/dM+/PdfztQHZH0
WDsYwZBebnzuYki7kCGGoAtAWjqUti295EohYwfM+vDZDBaslf3/Pzj99+sOORlQ
1yS1IqMn8GPCrYrjcTZGHJK5kRAgZvlhCI7qiLk1K+81CVbW0G/vBwLucWHZNf2X
wdD71MVlKXHfxxhnCleFUop99jQblS6rmduXS17u/LuXjV/jotQrM+Nt4yS/X37S
QwGYQDEix2vgcDVxcUmje/yONzCTBc3Nq7eHZtyALdQpmkvNNPwtBLX66Df66q8P
P7m4sc13vRk7wx19e15X1UzV588=
=Jr60
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '20777dd6-4d59-430b-a696-f6ff5834cac9',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAqw0/l2of4j71cIYMySQ3cP5FgnyOMn4gsuzJp4baZrId
vikPZY8qjEvm7MVlS5bhtLfw86CSfTjxTfZWB93QzbyaTNfyy7LcvL7VB7jKh0f8
UwnQLAibDs+9bee9ubAaBA3u1/HFNq3Hw3L/FCOxwrXmmUEftA1TStM6lNn+X5tW
czV02ESNhl0T64qme1sprlPEmgw/pc6AY3+7PVU8fY8LcnJPaQ2zM6HWEbNlkUYh
jwiVLU5oUXJk7VPE5U9TWDKBlcz9g7ywATd30G8an2OXH/ajg5OUgor4C60UM0TW
Q2bG+ePuq8qtJCY1p2DJIO+rdmj1jxjlValZxLL2O8qkUFVpoqkH89W7bC3C98x7
1K7KDInfLyRrMiz4H8ZiBRyCOeT9o4IgtFlcBr3N8HQlKxvHBaWaCdyP/xRIP7KE
k3wPA8kb4hpO4CYggS8RdyN8BJV0v/VVz+97o5FJ+zVGdulyji1CIx66SAKAtLmd
NriCCPk3hSA96hJJ3tE+uPhRoZm+XH5JgryUKYaZptjWr+gNd7vsI/kXAKGDyaH3
WghkWzxJNh7qGFj254tJZdomQnp31n1FZP+aXXd5OAdemxU2Km9ycyuyyjHcT0M/
SS1q4N79M2vOMWsFnlX1DaY6pRV2Tz/28I0lJGqgCAmtyiEyFnJvo7GGAfC0TvbS
QAHuCOOlbjR8mjsOj2tU7qJGXDZ1HGEmau6dpZRShNhh8ENRGUigeCz3LnxeMnCP
pdSs1C2scTXbB7rR98WOWpQ=
=a1VL
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '20f7de74-d71e-452b-abc2-e7790e6d57e7',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//dapm+iKGbl3umPM2IIZYY0Ww4y4IzeGwwY3F/4OkV0Oy
DFwx/VO8DF3NJlsdUEyoq4vjvioSG/TN3vQIotaSEoqxkUh4mjLMYBSiOy9yvftq
B38LXGu1YtD+rYPunc2631MW4+tK5zlzB5xI1ho8ltSxV9c1FjaGZyZ3PH+w8E5j
Dx68EJ4Jn/91NnQymlmQ6JGgsoipOHKRGmmqIvTIFebm/tNDRvyt3i0WWFMaUe1T
AdfRslrQfLaHFtJ9zxBWlhl7mSLTpfC2qrCIJuQK4toCgUe9ZpD8wA+CSsXZxYpV
1Y3EGjReQOR1g3YkReYxtdSaD3ImyHrRc9h+NiZnsI5vdvzDucMzWn4VrXCT4feB
PNV3oDx9G1l7OJY8cv6LrdUztDX/iq7KEbR3fULyxmX9LLZe8Af6l4PNPLR72Jrt
xC546+XwsJAHZhK5phKfuw/YvDtVfmwANNW98DVgCPtG+MLCEWCR1kOF8yIC1c9D
X1fsHRcozYERuPs3qS1TvvFfE/DHUZ2ey1TULzT2iWkl09stFHT5ggxdmoMn+5Sk
F3Mmdj22h93wjgXM60nKjJBpTfCq7DVDJ6HRrOSoj2qq7jJDOLafvXry1Z0uj6h9
48bmGn+76S98L2y4bRwFu8fNKqARQ61Q3xNCbPiZoMTYC1dcf5N1pX5tfTGK8J7S
RAHFLiuOIvN4Gyow+6evNZMUUduhzasys/KjQaonnB25nh8VDwngpKCzcGdPOkdC
xlaJDuDH1uLKt0Q3zHF2aMguaQ1F
=JtgR
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '22b4f889-1ed9-47f0-a315-688968ed449c',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9F5OOxEdSU0P9r3lsaRaETfdF5uO8BQlJjEgFtZ5rOwV7
tLbg0V987aZGL0ObWOxWwaLp7UWmjyYDppmlCaFSdzNcE4Phocuzka8Oke6Tcwk4
+UhriVHiXFxkMMxdSF9n0kTDLjKBHtLATpfkLWiaSgb0gxE6bw+iit8Q40ZXp/W7
L1T8dnrAx+W4sG1Xisx6WKD/Rw+WAeEkKC5N6RTWrMCV5jSR8qBA8iCihnbJkFPQ
spGGZ2bOJC2XC7+H/wBaXrkn/2p4amZ+D0m1UbHqqmccoqOyHd9qaC+R619TpC0u
sk4J0xp6RhC5qgH0vry0WpkfEcDAS6yFdZdLUMVFfC7sxXi0j2oW+vtzN91OLBNu
ylkoJw9zdwOtCl71n9qCL+G74n3wi6U5LtE4l15y+vlOVnYbh2yWyxfTZnTRZX53
8rZaq06rQCoUqNbJJA04VMjyydYiVmVFtq2RCxBzaiH8MFFHttiDF9PW+LXJsjFN
c1UlBU60xsgw9Sk5ohnU9bGPIufrvdnVGLgj0FBzmjWWq9SJGgP+DE5VVPE20gRG
vFs9LZOgSQ8oqSxO85WQLwNjeuSRnMKpvbsFyRadg4GGNJq1f1oweE4AMxPIXBIv
0UjYN2IWV0rS9TwhFmUMW6p18HDMuB1TMGSZv07tiESbaDD54LQbtyK4HBQY8WPS
QQH2cQaDD42xe4yBqPC5/ELti63a4KEOOi3m+adX+oiCpzApobhfAJ4zAtz9jsTJ
Yu6Sb8aC+8FK9uC0bWRP/Se0
=lMhL
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '22bed46e-11f6-4972-aed7-465ed078cb9b',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAvJFqOyjczFT7nrWOgT7u22TXzAfDBxGwYzlC7t6DxzcQ
2OtRmP9t7bBLtUHs7TN/S7mkBEXRXxngN26oXsI1x2fCoZ4XwprsWhcuSZKtlQRN
1EKzWJKdBv4NPSX+yTp6orFG4kcx90UfEbGe2NRjwUdZNl+MLr1oBWiu7vQq7+NY
VBXqZs+0FpxuUEcvafpivbyuen5L8Hiu++crqu1hMC5ttE5JcvL62N4gZrikkc1r
xtuG6BrCq4zxvz0KbeY12RS33dBdstST6M5O6vf0lpGWvRQscGjteYzsAypwyIPL
lvZGy+tk0sApEAqC29c4cHsODrOVRLYKXzObaLAc99DcDwDDDMC7GHVgfXzw0Oae
D2qrk3aj9MsjmVZDb5/72WwcZxeWf2YRrb8uB0vPp67Rk7ozDop3j3Fmf47YkUey
h84nWyAlKoynaFl/D9JwokCCrsz8L64SEAuX2+7IMSdODgz/0ePIFsriBT9mo0Zv
GqsWMECi+T9tJL3lFh0cDhkF8Ms3pQimip/tibRUA2iq1y1XWjkevAFcW/mWkwHd
Mjs8WNuOH9rlgCunQARV2OsF1HqhPFYNPEBkkuiFWnc9Kp7t8EDaYid6U4sveitg
c0ZWLpBpmCF5ms63eUlvMHXSSB/6i/Q0e8OqKlGt0n+loqzU/3pDGGzD2rJiO/DS
QwEFE7oyifbjPRPNq4St51BB2NVnQgdgmiIrz9HBgXq3G3ciBP1IyeFezXek9FLo
j+9Z3BIkT0dfeDvU+OHqQTpGBs8=
=dhOJ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '22c3c50a-4e50-4b54-a857-d63535e46afa',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAtGFyR4LotRm1Ss+PQp8gEZVPIW/5gfc2IU5XpT178ry6
qdPH+1IxfbyirRbjSqm2KIEBNLV5Yz5FopqkBrHGTStycPOtLyv2jIvSo9dKSPzh
4mBXhN1l+J/9x7itiPQwwe5Dbeu8FwA71JukRkQnNgh6sszADKZecR+2QrNTUha+
l0GhKjZ9dtc9pysT6xad/8jNeZDA6PnG59AZOGNUyGdeS9AvnR2EFnyP9GQWi0Fv
CH4pgYx+2m/Uas8taOj9xZDvcuM5gcsT4MOrxDDkRiBN/dN+hlH/4X6MSpAxt2br
AwE3eAc4rulvRd54jNtjF5ccKLhEWS9hggmkY9FXV3pwu/Oou1dMLshwtn3e6WZb
jFQ5tGI/DFi8hzYbFb+GvJ9cnctrw6kXslS0X56CZLuWdZ2c35+TVk0V83jcMJ8O
lUq72JLi85TeaXtwjO9BiIuj7cqPCQEvRAjFH/Y6H17Ey+nbvQtUjZ6PFSQjhCsa
9ICBajx4B1EXMIHycPE4MoOVKd2rVMfbMgqJxUqbysz8DGl7A3udPFDoGG+3S6e+
FfUw0Wd6JtG1j4f0H4R6e7AoRWNxy1zlYMdNJPxgdkI34XU1fSPdpjH8pWwWxRFB
+cjSSnuSF5TnQ8/a8AP/E8z81nf2Urp88j0pn/0Z6oKdPdREoQptcX7E4b5CnMLS
QQFUMJoh2+5VxHPBhvcp1WPAvA1qt0YYoruLsPZHevSato9VOObW04ZZIjbZBv8h
ZtPdQrG+gf2AvGowzK+DHtHD
=tbd0
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '24e4e1b6-935a-4865-af2b-34c2787735dd',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+NbFlP/HXSmozpqdE5CknZmxnT0S0zSUIfdolWIuV6qra
jG0zXdFOKZ4s0OZeUj1tSUOfDXENRc0mDVcKWtPjjCcdDjayJYGB2gMFJABGKH2H
8cE0jg8rybELZyFwJ96wkZDDdOj1NEupVIsn20ljWYkf2JjWszZ2Rbfevdm4MBYA
xTQN3LJGsVnIlZRwx97PhKjlQZ9aa3fO7JDwD9oEW23489ftlNQ1vMlfwr/AT7oF
Sgk++UfOyW2IDa48yrxHnbx/ifL89/GMAE/IkNMxSvMuS4DynsaqV8nongLnRbwy
IEgGSpyx3S9uPMznt1dOg+9iXocZWroKwlvrUfCjUgMcNI7IVgYEohlpLhG31oOt
lX7+9r9gyZrlCrRWfWjnywIeUrST2D18UWkVhdwiZAVaJQnprFh49pLVXni3jx7m
HnjKkKpGC+1Nw81H4mOS0unxxMoGsCpxR2SKu1Jdu0BW2DmSIsK72Yp+nZkd23na
aHnqmLdPYRhWR/F6MvK8MP7f/yxg5tjpYlqfznhDycwDYdihe5kdRTYXGiLJKh0r
7iCQah1r+FQDg+coaHp74StM8Mg4nOIpI4dLNz7XfYs/HObz7TGYjk+u+NklzAOU
tueuRE43OQyDpLN+8BmM2HY4keXtrmG+H+aaHjCwgGfPV1dhOrOyuc+lpP8lrQDS
QgGe4qfnFozJhVk1RHTyPKrI5B0qN37zsormwNSgVWZq9kgCUMEznnY9M7KzAfrh
bYUYUj6A8Gxw1boL5+q4TQkSVA==
=a/3I
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '253cc21a-7ccf-4ceb-af56-b3acef8b96a0',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//bpe0Q+1x40KpsKtkccghSbQTMTjA92v2yHL/P9qsVJyk
DSfrOiy7VXjzFfTkQv8p6dtmCgEtM0xNtLY7ZEDBi5EFuN029PW3LSpgAUr+camx
N/EmXvtVIOv20xf35nnuj0p5tsLtvq0F/H9gzx//xKoUMinF0vSReGJG35cSM2mx
C2YQNh+Qc/yig18O1kvR5KwvarmK+e4t6/zDx/IfAGLE5usJoNemXocRzpn5GI5p
1ekHxp5Akv2nedVHI158G2g+pl5T/+hR3KqqxplW+0LCmu+yHbGMddiyIIX37kkP
RUx09AzO+JnVo0BS+i7nenA/4Re0fSbX27Hx+6vTP8/7y2izvR55exaYFQHRFVx8
QcsZYoghmZ3O49kxIQsu8MCgdKaFwUyEZiLb22q23y+gA8F69qwiVc9SZe8yrT8w
HeYhTOkxGoCJaXUwF2e2r0lrYbnUk7aZM8Ub/vc6pCudg/m/lbbGYXvkEX12PLZw
8ut6si9jg60jTo1QX64mEM5J7MulD/dNJ9IcSxi7juBupSdUVMe9nG3lQRXYhjdp
jIyJGjbFrFGupkbGfEFvRvY84ZXNPlDIIUmdFAlao3ZM9CbkusiNEk9JvHkOT7Pw
pZRD52wkViZogP5M4AW91o4/k+IgJWewbCiS4GlTJaAFrgRSEhyXeLgd96BIs7bS
QQFtDF1xGwgypcXIf27Ew/ONPIeIitrxbXp6DgJPF13XulYnNtf1QBR5RTx3n4Mt
xr4Tsg+TBs2Lzt3YcmuGVsHv
=Av3w
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '25838a95-697b-41e5-ad00-8dab7f072d71',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Lj/k1cSSAEZ1HV4P+R9571MwOCz7k3K1oVqewfH3TTqC
knnAAuXLlECQtSf45g53tUgYHxc6/R8284+teVcFoXxVc2j5Dpo2tC3KWtMpZDgr
o35AaIWaQZoPDuK/ydRNjvatq24SyZDyjQHPIEYo5yuJXAyRBHRWt+qD1C3oA/Om
t21gKhrW2JSAMmBUpYA8FIngRhGnThVczN0yAZQiTjHIRaAa1mTOmeq0ESJ1z9YO
XO1g2KHkfrLVsfPJTndzEG4KLcZFI+9IMX0KI38sGRBvBCXes1K2WINLsOfj1UY+
6oQyIEPfMx0N+wMtmQy4SRXzdncqR1eeIClVsQ1PF26irjOair5ea19nlTscIpjU
sGKxuUHszDzFyTFiN5zf3XdVreu8IlGMpnzyM/3n90Z9HB3oFylc2Osn0SMBbbK4
ZoUfSdk6YIe4CUoUkvYU/FZBeln3Cen52JV6auSCZusyWlTsRSgBlqnk0K3lgbvI
kf5H39P2mNCObk5oH7s51Bcap7151V1qjCm13WURmCem/9CMZkYTGQ3o2xI3jrpN
KT2nq0stbQd0Av2G+I4cuMIenyR/2NE6zJINb7hO5yVi4hYkFwapITWzQGlSWaJ3
p/fd7qMZ2tME8CWFJz6pqQ2th8RTjd7RoIEzrX368yxcH1WlS0SbAD3/ke1OoofS
QQHgBC+0DOBNfXHYuhj3wj6rRG/ij6QUKsTOVJofnThcP3aFG6//cvzpOwWE/zpF
ZPZNld+VwcArVVzyHk7Dkkqb
=lwEk
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '27394179-2190-47f0-aade-8cc72befcac3',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//e9xlE5XnweX/RpZ4UnmB5x48QO80aBkyVTRRdfX2dZn0
p5/oIRC03Bgm4zD2MD2waf9Jdxcjk9xOSp9lqbIVUQq5iD7BXk+k96X5lJIwG7ev
e3CZP7DjhVN+7e4GDS7xu2D1uJ2EhNiAvHP6r7DmRnvvnU3lGgwcwb83GwfEHCk/
6T1RYVJjzklSiXXRVY2GMcx7C3vwdFr+DaDxHUjM0gbZLvc9XJgBcUNH30fmRYMN
jTB3LbAt7k3GemxhH8oHrtC4WINcJrnEsDI6Evse0j171WMnVyw9oIkcJc+N9+bF
68+CJYzmBreyLj49r0Iag/u37b+nMD9p0O1uIu5C2c8+p5tYds+4g6vV5Xcqv/B3
vpRXHAEagbAuRF14gclGHHU2exeyYL/O1GfLmESy91P6IFZNa5GulXt906BAvi4S
0V1JGdRISenQbCDbXPl+0reNCr7/RDvn4bULN8B4jjKJGXplRi/IXyg9lsgM/m/e
sGSfD8616OV5SpLXmQemieAeOG5+cGCpK0rhhD1E4hzKhq0TP9m748tohN6TAEUF
IhJm3tXJCsEbK+6bh3jVTxEkGbGIYh+UdKERr2LgbH5DYRRJpAFOdGS7GKZCKudP
j5ivgVjI0h63VK5701GzafLVnAbnJMTwH0SsaQmuoN57Zogoa1DjJI43wHAy1D/S
QQHowGa1dAQyq90f2YB96ufIIOTjDAO9c/XsiSpuUSyD/AhI+HmrckC1XPfLDlm0
v65Iao3+eh7y8Zv/FHhbgEmw
=6vLH
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '27e4704b-c76c-4545-af6c-e33c69f0155b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Mn0gdC0XlF/3W12dQoCAsXCw608N8+OeRrsnklxHPjZu
8aZAy3wF3TqyoRP6kPtGYXT9vWMHEhnt/SgaYXIR3kLQddzQGz0/D7lhV623OSm5
4wtOTqOqOdI9WWz7ALFgnD9IgonqxTzdhQ7aeukdwMx9Zk0YRbyxKaUhLY17h0kG
6AYfIH1HGtwkN7izAobM3wI0ReJSOLOebxmWPIIwfmX3hBXCe4qaS61NOaTDZPwp
WskeSSNUFwnpQuUFpW8ISyVZal5QbWoqM/XtNvITJSDF/byOFlEMS4hTMO67vuM6
YIISvUyUwwegU68COAHFrZeXKL74YNQ3EQpGVBFCLMi2sHqXJo1e85tsVcu00gR2
Ta+ATCfK24r4bDO9et+jUluJMhRPvZKJ8h0hjCIcysH1NpH2YkOBNw0hBDPvBGc/
hEUDeXgOkxv86Ea0bgJeKyBEmar14ZiF5z0of2CdfIzHBvycFxVBabGBB6/qG8jL
z8+dJXUIMB7wiUA/n5iW4XZBz/hMhpAsHJkSz8JNnUMX9ck/kMC5XX4VqJr6/nUQ
ElHj/VNzlWFY/2BBcoboKsex4WnwDXlnK8W/j/+crzWNpPFBVaoc4P+qQCzg5oGn
k34rXyR/gr5POxdnjSeCzd+LbARl/BHk0a1Iq33hP9yQkZH0FXDnQH9YT6D2Z+DS
QAHwW+64G+feFLc+7j7sig02XIMCaNb3hUu+hX1Kwtbmx89jYdx/GJl9tMeNQrc4
wdraRIIWR+SXSIZaWAu242k=
=LvnT
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2950cae1-c1e0-4092-a7f3-48c5028a5ccf',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8C2Nx7yLJB88msqm+MIitfAS/2clszjRW/8hdR9ownzpB
G6fY6k1Yy49NOcaZ59c6KU7imj45VRqLImYpMIwb11tIsq1VKsEK8HMcFSlzO+5P
Kly45B6s1BZuEEwLkcwERZQWMM2nrIp/ExjbOefzjE0PAiOTKmzE9AyCxYOtc0Ol
RhlLfS7DpvtlVgPGAvf7To4bl+THjymEH2ex4tK+fJC8MBwtg4i2XZ5lMJX6qOKz
yL7xeZbeN3q0+68YKCqcJRjC+1vQQuvirKIq+IexG2MooalLZG8ulq1dp6pUvnsU
IITfcnaolFl3nI9MJPBp0BV2uvLKb5o3aGQmNRAUFyh0XmudxB5NOzxwNFBkf7RX
klGTmKGN9mQ/6PkeQxShjHPSTFsWJ2jioYaZhtxmktejyChDij9q+k/d2SOeKe4T
lv0Mww5/FRgPBgUijtB5LlFpz3uPnOb2o6XioCrVSogZk7SJh4fVKHTYd4G7QI7g
bd/jiQHAJyplk4pO3NJz+JmmhE4312Ypa5MAizFHZ/2MhO4X+bTqZJVPgIeJVSw1
lIk9eQSMldA3ObEafnpT5CjFZ/rEbTM4FGgtvLBfMGb2uSGLgxHNjCi0MCLwh5ju
gjh5HdiR0KIeWGuciUhzQJsTwQaL4P055lwkwuNb6E+f+Ven6U6JT4O7QUaMHVPS
QwHMS8KHD7gb2ZUbNCDxJFjPRt1g3BxpQAXgcpGOb8rafU5a7dQ24iAfmYdi1I0d
ML/RlRzV9vOeTubepze4lAm3Tlo=
=51qj
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '29f73253-b89f-48d9-a61f-9876ca9fc115',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAvYIZlmUtp/qodOVaXV5M2adNh0UtRylgG2939M/E4KsN
r8Byq5pOfye3AOZ8yogy+4QEtOY+0szI0/MWVULgZi9/lcVzOUyao6QlKCKpz6dt
VYBaN0PpMZ6NIn3mfiK2Hp6diy+u+SFtWhoSG3hUgwT/EDiDdjdRfKieuNDYziBv
NZ5SPgZs7DpMeGTQgx2kkoCOmPHxw4gralj4yNqGO/AU9XbaXHCbqvNvypccCaWS
RQjFiQTPA/8QEu6QLkF+fZrqLX2B3yXgEMFgJp349jJ6+JRmlVP86AdfQnIf5VO8
dXFB3IbYbYOW0VB6/u2jEjDUvrqUozIrNNYGpJkEzaZ8tlGDbwd/WB7DQibVwuH9
71EUGMInT+TRNJbFr/w+cZEL/Okxz5e82FphzvcYC5mP4OWQ2H+qM10n0Tq64ssG
4/7tG3Xt3d7kXgRZN3uETO/lTpWnt/N0sQRbXuGNJUSjVdt9f3Xx7DB8H7LfOETk
+SdPcXTLz498xb5mNgKxq1+KgwirC4Zf4j+/p7yANuQ0DgtZkBzVH7PhiskZOwnK
YFjZXQtF3h/I/KJT8sTOg2OZpTVqoGlhiqNbKC75mkt/Pe5Sri4cqTj0F4ekz7Kr
k/uOxyXu1iRW4NFLmzXpv4FpaaD8LDaoWsaz7sl6fhGh3hLeanLYJxtHVMAuT/7S
QwEkGqvGbPrWwq06OEoxVA6nfftgpVY3fuT0z0tfJkuEV9qrDsv7AdfsqejgSbAQ
9jO8twlygBSER2OJiqw6stlPi8A=
=K/sp
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2e1a0c7b-aaf6-4a2e-ae88-cc44b311fb82',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/6A2373L/XIJfqdQHghmgx4Ip+yuXwZFsGvqMzm3w5NdTW
34umgzrAcxaVPkvMwiSCjsmYqOewH3oKCtrggf/Aqjswywjk8MY2gnmdjk96gb13
xOm2h50sWiIyeWSPHQvYZUOW+6F//LZnr1eY7J37a6sPoUqo6BcOV5UuxyLGNQu0
wLAQyrdy71+MsENStc5M3+K/PoVisqzyEWaaSswpuffe+LXwG3+pXHMNwFsAtIFL
EXaNo1VWJna4Fi6TivUcYaCWaaA8V/fYVYm+vcImFDw3Z6GeG3X/JdB1iBQQxOTv
YKfzP8asCPOAHbNNoGIRHVFJSfUoIcB/qPm9ScS32dM+rjXVnzzq3R221PHv7sR9
fz8iUr49RiVGmxRGvT1ivzkVKmyd0SyuQy9yG70FVh9ll1zfmOTH1OSuTX/rtsJZ
WwmTtLo4NL4234ZnzEiD4nYsD4eLhk1y6E5ixrNmSVZuX6UTyT1VPgN4ohb6fLl9
KUW+7Fd+tjCRSE48Uw+8g+kUGLYpUX/+GE/QC6EUafk5jmUH8HfYWhBmp16mnik3
hA1xyLmHBtq7u7jxNQN7Vk1wJJIOJBL8NeEfIx98KynXDm7ccsJctZ3f+EEaPO3U
ItE4Ysb1lYlNCOVuu9oM6kFfyzlv/s8pykQMnS6587i6373Pfgw9RNplauyQJ0fS
RQGton5SwUMxshqvxrypeGOSt9lvicFDGKe+Ibipf8cLpzLqKaujCd7ZQYjXkHsR
6/ZEy1avK+pnJkiku3FecdXyaO8J6w==
=Xj/D
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2ee0d0fe-7fce-4e74-a282-bffe2e88594b',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAuxmrdpoZsdZrpTrKKz6CK6WWjcdezpSJ2Rg9tv7ahcw/
wPFaVDOWnWSenDwCxDrcyuRfROtiU8QP3j755PL6dtncLJ+M2mSa2X0M8gWuaF6I
pxNHYaHiFQzWAWsXG2qZ360LFyGqsiRHyJwp/Q+Cq7CMB/rnToGtlREWEUaAGpXW
1nCXG5RJwZBIAedcno6rO2uctk8IiWakplNVe12jqAxn3Fav00x10f0HKSeKfOIg
Ey7sQ9Rrmu38sIsMzhs7UvNJcLAqSuMNNPt/IxFe0w142yYv3gblJ6CF5ssEKjOg
pDZrmUxdrNDNZknKkh4/o6w9sMw8RSXgRBFZtz4pYb3/pRb142i6ZAofug769cUS
Jr+ezouWjecmL5tz5bwAWx5DOAxXRpOy684LYJuevscGqvWDGq69APMBWmiUwZaB
1848LRmV78pGrjYXn+dLuxnKRM+gZqJwRA880XY8o9B4XbAcLR72koL0Mii84MzP
J3BpKJffEIHeVEIWpSop0L9mai61Fd9t5aCUEDB4p4i15poePmCQNH/z2QNqOneY
/DtGKZmWDBrGLscmWgH/JQD17bYlahLwRQaZOG4RwmRMyTejWl6OijCCPyojEKFO
5KsThTveVDJRXGwIn8MlZgY+tU5ixKtWHm2YjSY0Wo0gyh70a3NzumW96HzoFErS
SQHMMazt1m19fB8Jk4x+BWd7bPXg01qxgCAINDQgfQCGUf6eNTkP/tSTPMm7AEDG
0pJqfCtQTs9jB/LhF6SuQHOpYlG+k+E6wyQ=
=l+Aa
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2f7a2042-2b6d-4d00-a817-d5af4be78361',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//RQr7DNqx/U85krtGwkeAPBfPdpHKNHN0sjG9fLfDjJFK
g5Uf+taO0Fxi+bXdpdP5mS1+F+EDy6GUZM2OcmQxZTZdirzfFlFEeDeMM7uQ4/jn
bWLXfr07Xq2ZJ7XJBW0oRRlsuFWDNiYR02v8rP9C7LkKH/S3FZk97OGlP4PRcBZ0
MBMd24Wlt8q2xEj03Au67Nv5fz8z/3DYC3GZ3tPSP5T3iXPyfqrbUpgRdpXHTqG4
3MD4Twx5Gku9q54goO35agIC9/PpwHMulM6CqDYbHtq7kiXaOIBRW4mL+2yfOFBU
odyOxEqMZq4xs3xKzwrqOLa0m4vRGroq6wpfUnBM4fcX/w7Yi0W2SiAcfuKDH00i
jM/1oy3NMpt6VgY89wF3PypxhDdp4b8QsHO/xVCvhVgZoMl+UsWlRuUZIL4HsWVL
w2toxcHoiXjHK9caUwdsG/brcuBu1+jVpBH7b9BnwNsHb5qKrhFfRbJHKDoAzdID
p4/fEa2k75ksXP9+HZWRHIq3alG69Lbc4NYDAZSt82kq3mg5ndyZI2FDEoFTyMHf
GG4Fk3+Tu89FPCtGtCCLyF2kj1KV37pKVxe/lYu/cabnU9bxjD4ZmPEnT2kvLoAs
SwTgT2JMCMQ/LAu2ks0zgY1erto2UhWj5kyxBEDJ/iYNhU/RPJ+0Y6m7i76Vr1/S
PgEeYkTtMrYQB+YsW/hsTJ1SqdVztK2/Dgw5gMhyhecO427bm7NdYoNS+kch+vuE
LREqjenapi8kfPZOZHm2
=c+bl
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2fe3e221-00f0-45cd-a62b-1ef2b9ea493c',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAxz4wUBA4Y2RfN5U8DzgYeT1Go5z/6q6VEdtYGp58fAdO
McLbzL7K4G+2Ly3TRB+ynQwkiVzgYS8REuaMEihRtA96cDKGFh6wjraYE+emYRTH
YSofVUeyB3uFlLDNvCqVhJ26i6YgGlkC3rIfJkw4ZxE1fvn5tNIOG5aSXJCS6c/G
gA4hYQY4vEdKXCU19IeCPGhqitkFRrhlBi6YsdNbjb+Zz2nr3lIy2OY7US21AePi
VH1vF3fXFny60ZnrmxrOS8pdlQG9ZvGzXoEiHxpeVFeWr3jMdHlAj0HUATkbt0Ah
I02YP6MxC8l+GrGhHYeE41MGmcV0eS/3LxowG/G7WFoqiDfI5bTV4Gwa0PITQxcb
Q3R9c55r0j1GpO6ZNBqEZ46WRzDQN2GundMY5skgZ3ocz6VsDxPlHDtWwgx3ba2D
gse31B1mMyrGHVu5vTmIFh/6Hycf51nklBMR7lUOWT6PXFYIzI1AumgZMOqYpU+L
T0o6gyGmRAkux2FhYxDmiU46gk9PoC1i3fGMl/KQe2DIWy0dF3CHCxbaH1ed3+nI
x0URgJgwG68Ew58Ru8tYxufxEb4TJaBKR+Y94MqCd2xTQl+HE4EMT97WsD24t6Iu
S8JS5STQ4ZclMGYehTW5tJJLC2N+F+v0/lgvxR7Jww9etWR5z7Vzlbd5ghtJ2oTS
QwHtLUAKe0IWIQ2q6M0WnGcBv/x/7BLbGJXXn0EKsqJwts0/WU0W21lRNXSP9geh
njzSLU0g4d2RkfEUp2+11DQitBY=
=IQ/W
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '318f30b1-37a8-4a5f-a639-d7cced1ef6f7',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+JreOwqYel5U2oCW+ZhAYKoeoAdEP4XYqr6BmAMouIOzS
MfD+zz2+Vhmp3g7mToIpLsm4/QjAb2WXMI8ivgpA3iDaQ1d1jDx0yU56ys8lO6d6
9IJ+Ovmiqth9B4elIs0+dSw+D6rB5qjrfRtSsLsEfrFhPLQ0pjAG/BuTbAwlx+Kv
0MiPD1H0bLFRCGSyca1S/W2Usr+UDSwzYiEXI4rfDVal/39uW0SwI+k2/+FrlaCF
AA31Ya+/BrjIYOrkMAcU6/pZ01KnL3t+wa4cXVU4f8mReEpZyTTuflfQtBbe1evm
Q34plnY3eVld6/OxR4MQgaWbqHCRhlY3nxUuhzmB7pPdSmyucrSW9rrLrNmTIC7Z
mwbtvP/Y2BMypJexzJk5a0mAZncZ/14Y5fDwPRMnpnt1sHUxRp9HlBUnN/pxYVd6
QfvC7N8IudBma1aIJ7ga8deTapPsBx/QOFMeHEqWD7PqyArO/7VI1p1f+lTiNyxF
SYxD9RDXwoG8eyetnc1m9QRJiUt9g41LAuY/T84jgz1XUNWId0IrVQ+ug9K+udNE
YcGj6G6tjnImGxgJI82CNeORX7L1NR39Gq1Qg8OapknT4WfAm3AV20bgAoAdGGGa
y3GAWCi4LHDMfSukW4Vv7hU2nnKlwKUulRV0T1VEDSQlN+VGZpSeMNb5+c52ruPS
QQG3GwsMRV/2buwv9eaUtAWBNspur38mjR/THJNt3l2sKL9nKlGHvReru0S3g7fU
HlBQlVi/LebBrb7q6wGpZCzp
=YxOH
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '36aba6f5-5d11-43f2-a486-10d781a82fc3',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9FkxG4YUg5YYi9yhpqrkI/rRooy9Ddr4kus6Hk/LvZ2nh
FJTRyhHOOjCHLNeYY3E9/07o1Xq+qXbrWrfwciv8hesftXX3gl4qXRqM7KxhMFIc
lCmBRlY4wvn9L9rD61/rjqcVo/BBBdCWnKox63pjfawjtVBDEaL4xlhzH3qmbpo5
5U2WP5ylsyolZVyXIKwlMshlxN+O/+iCsuJuSlG0SSXma/17d5zORAdwgnfrPwQ9
Pexk4K6Peq9b3UvL2DGrY4FW0Atk2NOwtlN0RG4q4klWYx8PoSeX8GucgrmAC07I
DeFJjbt9MwEGCG0MtZI5pAeebOGs35kDnfqgaKpzT4ysVQ3Ri1ELArwX6pc4gSJP
H+Afb4Wu/d9WCK9J0EiptDfYiNPeZ78Ht7fW6x93zQOvaKSznoMqYs3p5ZTAfMRC
Nm7PWE6RZv5elFKm++8kWfBcSSQoB9n1MufqX/NE+JzWAyyXVbFI5BRw3XejA+N4
NNxnd9CFgP1wyaNpUiZu6wb0/wuImluH5cDIDcO07b9uSxx2c73WlcP8O6Yld1P+
jWbmRHYIZAVxv8bZ32cKQp1P9VZJxHE9FkQV+pYpwj284FI6WiKRYV9uWRSuzH3F
nAsdslyCElraSKYUKOb/lQS3ASODc2RnQoyjU3+u+mObWNrW8y1s3DkcqspSvcTS
QQGndeIdfRL3L5apnIJGntMsEQq/Lqs9HjtPtWYTts334OYQDfcqsF6yy0YZh9V9
2qYGMev1RJ7AAipRoLdLP3jk
=zdTh
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3751a6e5-00d1-4ce4-a128-bc13469d7745',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/ZHW9r+iM5HEZCOZhUyhEo1NEsmAZiIR65oy6rYLiFIX5
x5GvL969yFfJc8lb2Ajx1eakaVXwNWeqCzlc20O6FY07epMyeNMKcWJN+hlVcNF9
WjYJJuSBlzcXCasO+Hp9JMgFcWJzaV0NX+V6vYRPeSq37eMUhQz8D4Ot/7gYNKnc
5JmIwelHF7A7V9UVjDtokJzRXAXwIscYfngGkGtfpf5hC2BtTOjb/WjzChLa3ww2
8smKgEo119GzslJDzK+JRFK5fyAljyK8av5g0+fQqhhh1j0UxHoOhy2b2w8/TAqB
3ov7LX9mMwhaYE8t8nzr70mm7S6LjkkeBNE99pfb/NJEAd+zB2C3/jtvgqd7qPbT
qShnrHrqCFMniW1WYhTkBS0bsHtzYr7MdQQiAjEgyxKEhQkbvE4y7c3AwgEWJ2Tc
f615qaM=
=wubs
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3815979a-b480-47d3-a059-1749bdfe97fe',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9EoPl8KWssCUR+4lt1MH2UicJ+OYru7Ft33mpfBPtRm0x
XGgyDbog2T99vqCgFmdAFT1NXvQMxV2JmW3BBp79hOoWV3AKUqO6WO0SBqNgFAvG
dbntX7G0Niy0xmYz1YxofbibnvBxJG7pErYx/rsnkagNsCKuiBOJxZbZ/AYr2f9y
BQRhEtZEHJpUYpaJRG/JfXA1/goAIS3ndzzHAYanX5WrGVuRg7oy+slzxe8nwCrk
HP39fu2/odb7gNTUUiZxF5vK08ZvPl+AWkiWfgySbXGOQQvXesqnH5EbtV/TDpEH
ncERY5PCyhHLVcmmiCxCAjOIX5ezxAaN9zEOsKgHUeMa0K7OX5xr5zu3/XAZWDy3
hfdeiU9m0tU/CEweaUECwk60RLDkT9+9uUBvLpc2bCrxFKaYjJMEgd5GWuUrkAaC
E4sVUOJfVE9AlStu7j0PM1uG36MbIIsiSaonVRysMj045xFrTDbb+3+qnBXmkFfQ
s3pvon2YKQaARdD0Hc9JrcUy2g2uduRovoHxAJSRfBBtWfoubI6YvCu7MU2ZHSry
e/Po4CzerVUfrbfCc26/lw6jAKtSL5K2QI1xIJXp6VToBcBNPf1Whq5/eBMdPKta
9ESYKGOw7ghCdyBKSjzjinn/qqUb69wtnRVe08q0Qma3V/updIVsbSE696ScInXS
QwHrVVCRJFT9XadafOdj1ddhjuZo/7kJAWoedvEk17XqIZHhZ5r8yCiucz0c5pIb
1h/hHJ4ckh7zMcwifeXt0kcI6hU=
=5IN8
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3e7933f5-adbf-4fc8-a42e-0e7840f40aa3',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//SbVyJyZt89lnPjQ5jOu7LG4fhxHukJN2UNmlHhUi4Y9/
LL1KlN34nZpjehBDc2J4Z2jvNYf79o8NmpO9P1nAbgDPanLK/ooR4KQtfyLfj1dO
BBNy+Yw1FXoshUnNmsF+3J1EuLM/u8ulxYjbq2MSZLSNRcegvYJhrAIYHww/T+qC
FzqZknuMV8Gj5llJ21/BBFnjJlup5axTS1rZBLiyvEaEtHLF+7+TfQXfZkfAZrIT
gkFQ5jSE4NzX0sLPFXs79+85auA0370EulhCrJkUsWq894pJj2QU9cy92TPIj0n1
9ShCp7Rgx8zuybvQhjZsMC44vwjTMc79v/KpGgRW7XMsGW7Ah69RDniO6/o0Iq+u
hoQOgLcwnMEZ/MV4AosYqBcCIRF4iCf/BvyijBGI5253TjvWOJuXHlbO6ioIb8Ln
sN2Vtc/MHzvkFvjRTpaXD2czKAk9pRAkWQs4Yd9ipC1GOf4E5GfTW8oAtg+ipiSM
QNEyO+6DJ0y1ASTySj+qQOfqfT1cTjmjijHQVKTrplIka/s1qrTkaWclLvxSKQmE
mxHh2E39oTI15u40LUbnXQP6fFGuysWHpMrrnu4gu1zlainLMOJ7BYJRiSnbBlqI
xDd5qOCa0XO3A6DY9IzsJ0n1cD9dpTETK/4J68Sf4D7gSZ+H93+/lfngWgCm4EfS
QQF3P57tGZkKCsPUZ9jORerxqwLdp4gzGMQUBCjJ5muBhuRZqTZSBMR03/QCHoVF
1z4+OlbfGfS948C3s9N7wDUd
=JgGX
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3eef76a4-5271-4658-a140-c915c2b059d8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAnh1zLkiqJtbQciY8LKkqUFvE5fqude7YC92iD0YpPwWX
JzJ9KKMlnXeWv+Sd2dgNXmVU4Nd4leerE7dt2OPml4/RVyatUed1jMLJ4Zq1vr2B
ACqwU7Sput2Cee0FiGVyB0ZBx9ldwZtdz3TffHHK/X8sJGlK7iWa/oLIMzwA+u3s
Iwsb2Irv7JUhyfT/8gxjMP7ya+CukJ4xhL/Ohq/S+AgOLwYrv1TQ2P3Vin27+X7I
EpK8hmjETG9r/GUb33x5NedonuJudRmFyQZAnLb+tgxJXghTRXHry1SSF9M9Z40Q
3U0f5QHhK16gEL40LzbQNCN21EivpA1VGYs5gngzbQznoFP0vFhyr2qwMCY5Zunk
G9FUlSLf9iz1l1hLYW6DQVuslKXVdtul1nZoeF/FhA6gdWqty7tPGE5t4Cyt55kd
opky8zh2kYchIkNxqgc1AiPuxUZlNqjngYuoGzA1jRtX7h0Qm6s/JXPi8kMjT0Ux
9IkBp21JgDcS3RpZStHmJ/ZkUpHu5FoX57CTyEO2rnSwGZFLs15571sneLsPxnfO
AvHNJtQra1YUWbZuSgDZym9hMhNv/enQXkpfp8xfV6KEHvJsjnk+kNC3eZD9p5pa
CzHg40eToCAdZ1R+9ihnqOEH11AR149dslp2TR3Ne5mmoYyso5xNBfWCVlSNZFLS
QwFflGhlI+wEpB2DMRUVU1uuoLmoK8UN5vJGVIzDkZl7cmUlAi4REW0wR5NptkOZ
R1fyTAmABIoFv9fUsbBXhF9I1D4=
=5ldy
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '41d0c27c-5388-4a1d-aaaa-02e209a3bbf9',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAqVtKfjjCtVsof3ce23R7QkDVGy4HH3v7xJIC3R1Z+xrZ
rWiyZxrwN2Q7XZQxOe4BP04SdtnD9E5Eu70XB35AlDfZ1J/3tMAFK4MTO+6VrRvV
XMCTkfdlApRto8meA9IgoIX/u2kKn/Bl3WHK4ZGDYOElVcuH0628smBmYtrZDJI/
3leMU/Cw1fOtwt/MJtM5KIA/u2k7VEZDihZgpkHnD5kB6Fo5jZogt2re4+N4gv1W
CKRoCPBRYpWjaVUu3tv0LXOTjXYwOleIl45y+kN7+udgbp/5d/lcmZ5kE8vdkGXi
bUaa2yxnwdby3gzLm4e3d1xPOwdBO+Wy6+sxcgXURSG22b2lXvhT0DBjrVFGrgMV
3R3peRnJyhaqls8by5GB+WxDk/4iuCbGu66Ex1JDVKlHCEmT2kdSnxxesn2aGoJ3
MwvuwS6NP0aW+jJJgDMFY9PMdo/FJUFWar8dEKi10JrHIqzHjs0079lM6fySjcgm
TWTtIf0cS+kWq8h1632YX+oCrSdW2zM0xbJLVFjgp7xqGJ06USUYmjs/u6Sz/TPR
iraxglGOKMQD1ymF75qKHb45K8ZsijFSO2kyc54Fu3QNS4G9rgMJXwJCSUoFhDqh
0GqkdD0vJyViU5P+f5PvwtZ/sOz0Ab5fnGmBH1ImHnw0iYitDLy/sPTXRtQ5V07S
QQHVfIlhZ0r+b3yG8PNz8NoHcaAaRRML4/Z9/LqGK6WEyJeI2lnh2DB5Ego1L64W
kHYdVTVQEv5rttp1xNqt0w1Z
=oMmW
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '429096a2-70d4-4b9d-ab9d-40b23cfb0112',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAifyi8QHKoqZbOzwkABd0EToacGJwywxcefeq9s8ArDZP
myyUsJDm4N7G10a8H/liG87iVKW8N0QCPJylUso2X2PNkw2XvdvUCIBU6K5oIoPu
ur8EpU72H4lTLO6dDAqAAtYDEjM03EzaIkriRs74vsU9rRZgWhD2NwmSDDkG+pRs
Pe2AjG9zENQHmJZh+lw1OzRfFXt2z4cpw+230oATo0Mee1qkVfmoNqA2vSHhAHuo
Rv2B3uzQDNfMpISsQRON4ZmGSRNd371iVzVs9n77Q/wHKEo6tIoLqxcrLZVwZA9c
IJlMV9wRLLxy3O1PT21DJXCvL5YZGHgeP/X7h3xbDQLzkANIMHEckQUUFEOmCtpF
tff7be/eV1CyvnpVvW8bnXIU97FD8ZZDhYS+X8VzRUoNyHvfJum7GlAqdiYJpi58
rE+iERtwmXgJLPIdG6gCS5yHlmbbkgLRJgZGghysl98XgeMKkta9+S6bqaLNNaGx
6DKzAMhWNnvFviH2Jgp/ayc6VLiJMDh2F97dzQX6lDKadIfm7V9Zfl7fw0LwQD/S
6zdLtW6FvN7yp7/N2g/IHP+ZqeuOQPgO2a4a457hE8Jo0LJDX8SDfxt0EQLLtkIm
zI67wH9bxnWUh3xiTOFTn5JC3xP9oFATDOS4hcEoGid7UZrC88uTHesYkpgPs3nS
QgGk9kbdyNuLQGtOv+7dQc6JQpaFmdx8odjw+yZo2CnMNCAejY40Sv5v38Y06OTd
vQqV63hzj2YAi4szC7uuNiDDgA==
=M/iF
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '43bdccd0-6d71-49c6-ada1-190c0217c442',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAmmBxFGjLbZC6+kvvN2AHdwuMkOz8NzuA5/z5pMR0D9PB
7wGwnV6zDQ9Hg9u0rRXiftlbP4jMKPUDrqb9iWwaVUoP7+lC/OltE+YuraUZ+A4R
ShXThGk+dsyejt5wX+idJLTgqjsxdiK0x+TE+hLMlFJ5fuo4t+aVG7uH7DancNv5
cwFzj/bxB7fB2EYDiEOmz+5tL1Zv5Cauk1JF2zIQPXu9qP/Pnl4MwR1+drltrLRe
NcFPr6Kssj/VE89xYcy2cHmxR83lhzRFWgAqwqWN9D6pOgMLmb8DbyMKYQGfdpRr
6kedJa2i6lHpMnRJqdLUE5avJ1XvUsUWgov50PjDmtJBAfM8UL8zGpyOMx5QrKHR
AvjAPIyvbDhgzo587jSuBLhFccKbG/pCSX4JuTY19rv2ciTdxzJ2WtzZb4xRKImi
xzA=
=2NgK
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '45442c74-2690-4bd9-a68e-85b40eb4fe75',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAlwjl76Ri1MgAH5R46TAopJhBfyuf/1Z8uQUvfSt92TcV
filccPXEdVW1A7tNay+XQFNfRLdu/kceGlIqRbPCk6evtmC1RcHyAPS0uyA8I3sG
69EAH9YlBO8cmbJzMUHz5jSo0/7ZejmhtZIjZD19s5Pji0BCtmRg2vop3lzpsLjG
8YbevGKNEEAp7YzFNKJUXEObNeVjlFhWnIukCn4zt570Fv/wL0MKTh7bGRvvwvXd
FZKki79qp9Rwau+IqRQDC/e/uE9PtK7Jbzz9t/DfeIRa5Lu1/Fjv+bzE5YPVzff0
nuZ+fS4TS+JhErPzfSMpTe6ixkOBI0YmfXtjNyiDipecBRZR1mXBs6izeODHgjcw
aaNqmjWeUbDexymMsmrKY0KDaFax03J9V1ak4eGq8L4qzopFxy7YZtNQrQwL8xot
YCg3aHlTC+AfWvgp+ZZuxY+SUq+7jbtOq+0H5OUWo0gbMJCM8ybBvbymsbFT5v7K
5WuX9zVKzKQazMQqXAKWoIaOtTqGYKPi9cOC7E02h044/LaapiNgjjFZjpjjYo9X
2k+c/F5x67d3HlD2PJ6weqQ3se85pjzV+HdqUeZEyBx/joWYCUQnLYadzbdhwFKf
gsovupTSvNeITBxFVspeB01YVypVRsf2hp8KwRDcl5l0Qcbum/aIlZQW5v9PIeTS
UgHKITZnUoAPq0Kz8q251VYSVloGS9v/ntpiybnDJ4gwu8vnN+PD6DHyHBt/gChp
91oHSLzq02S80qAeuCC4l6S6t0vHbK5Xjbu3kv86JeZUol8=
=UTr6
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '45577048-1e21-452b-a8aa-5317d452f4be',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAuiXsQFO5Y7eJHRXXX28e/Q/cw7Xa+zyZvBnC1oIFw8Vx
pRXiSbpkwSzTuIF4ylcOZbaiEcTc/lmFCr/XnMNEwIP/hq33zZLebIX6f4gDLXyO
z9bdHCObKjxLTWNmbgyW5fuLsktu8t9HFKhRLvkxdU5PscUWcLNzoI+OIp0MhGXA
w4g2aU8nKmmCC+DHCE9vUde1211nkDG6CZOeR6aR10XItgIuc4ekUTmta2bphCVk
5e0P/Wn3Dy5bzISFdFBstB4zT0m1XeGRvz/wF4Fq9KobdbZdAjnreldSOo1fncaf
O/AuGqEpDh2EezKlMFTO+Hjzx3nHQT56GoZKFWmqFdJBAZPQZhuoMuWLtedecNy1
ilbbuJVWk17F9sIad29eDKFdTS+3ZY0iLkn/7qtwiEc3Vs/16S9RG2B6T0c4kkkI
kNg=
=hgIF
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '45abf542-2ff5-4c51-ad3f-67a96030fce3',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9GqpLTEwg1fnOAgCqz8CxfcvlkAcc6rU5CD2/DClf3kf8
Rjw4yKZw/8pcrJAUxu6nGuOiR4HkM0lfD8kgGbO1n2IkoZrf4XUw0+rjfdcpCGtz
0FX6eglstXGu3zgk7v6rtpxRjYhkNnLoZlJve3T3tHWIDhv9+3CmEujC7GMX5qs6
dlo2xyryXEHOQ5EBrIwYoZ8zJlBO8KYd6iWefBfNhNNfIZbGy8vejN2XLC+P93OO
AxqzWNkaoOfISgCp4P/Np1xi73xvtL5/CPhhYRe54NYsCSgjJFumZEXLrfUijcsk
Ym0wM5q6jGhIai1vJVW0TOIOx7Pq6hwERDA5v/P03TKlINd2nb+OmZOxeydpqCFp
WkrCH8Khhs4bxIsbgoJq2yvAScBEkQLmnoXn7EGBnd/HaamF41yokH4ndLMi++M1
+GDXl43M1v9UFkOy0MZCJstLT12cDiJJaHyG6Mc1Xf76uVTtVLbDoLkmPhE3IRx6
JBidsQU807QpVUQC7Cu9F1EIy9q0rd8eLN2p2bEYEDok4QvJP1vE9nj+OmaTbj64
zEVSGvoPjCLD0o9JVJgKUwjz6E1drv9pt195Fx4tB2Td7vv8Mrpf+zXuRG8jixHP
R2JoMBCLj2fZscvessm7dtO9J+J1p5IBCVu2V68la7AWfod5nBaFUt0PCPki1KbS
QwELPUmrAVv0aK7z+kmem44bgs6HH1Y09Ndx5xiFWDte5vEDMEYhmV/GojKjSQFN
vleyApcl38O8NsseJzmIHsGmBIc=
=v505
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '462828a8-ecbc-4a12-a08d-94c0191b5033',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//fG7QPWHLvs/niZcuC/znth20QWYZr724JS2dbcA+VxKF
cqy02ivte/4cgIwv0ad/OlO10IiHu0aX27USy+KrM6MqV537ZnIaEgYaUE2KQU38
Ij2icpdtD837Q/i+aQ4gcTtHdIt386eVqCc29owibchdjeBZ9XqfruJ/A22H5BEj
Gkg8qWTrNjOCgNqeWd7GzyiyhPfg+cE7u6xkwggAn/Rfz4mddjyUxesn9o8zaayC
Ip2SiqWqnvvI9f1rZbqkloxEV6Ysd5KcUqEQ+vX8obCYjfthXC3VNYib4OTUo5fu
uwRt6C479v3Ev7CN1TZzxu07SMFirtOES9kMrTQNlwKExfiMV3/BpYk6ZIujJh7r
tjHSS80eQDL8utqiOTZBpLijxEzbXwyF5/1ToR1YElkCldcqUWGheqBkk6+H1btB
WI7/ncqmqbGIlkFBfs0IR2odNttQcEAAstwrQcvua8weBjPQ2JflhYTcPzWoHLVC
lg+QBhnEo3wFnN7xoDkL2IMgn3Mp9+p1yHYUI3GpLKyNRaLOlkJzIsJ+kg8aFWoT
CEi5FxWmQmbNSR0dTt+vnwOtZR82Wxsf5HrVKQqlusl8d01YcZagJvuNRLKoC/MV
3kTwZtIz4X3v/9ksXYwP+jhU+eME9rYk5nlnKHQba5xEwb0DRNtdtWWdWIocnjHS
PgEeUadQlRJ9wxF5E/7n4l/5QbP7XhKXLQzCjMKBaAbNxyzV/QfhOzqHqXIJpsek
/2Y+6J36iu0P6nvjk95V
=eAjt
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '47687ca2-70f0-40ce-a9ff-e965d553a815',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9EL+JIqdkhDs1vDwDkVhsdQeHkP0baRqGFT5bv2tghubV
IGHhxeGorfwjXp2PrVp/ewxx+aV9jvRuD89EY9ZE5lJshe/+R1ETPWIqZeoCsTVH
jma0+PW/cQP6YvDdBKeBgeqPSM84mDOO6EPxorZqaU3A72T1Fh2ZizCdfcKmYxFy
GFdM2sL7+R5akCKABrfjpYuNGlg4BLEu+RFP7Sqod8mrXVQwkhA976tHVnN/S2hf
jXo4DOLPCTUlEejQiHnruvVoFnESPcg7j5mZXi7nRwC6q5ebqVthI/l519/1qx9R
kls7CXes1A9NjNyPNkOoHfaPfSe7iDc20hM+1x7uAC6Ep1YobiycZxH6iW3ePaoZ
xo0hYsPySnPnJFwvF7x2kFFkIWMEVnKl0h5rMAZrrfEajsNmCAcAFwiV9uk0N0kE
neE/A5u+zsNQ96TzhNW4Ckig0OI9xaRJ1VfqGOGDuRWAsgq4jgpsTyjFghDL7dse
+8g9huA/UWlegs1srt+MVcjR1Pz/YBEEbHZOi5fvHF7kWXjZCp6cho0+XGxW5oLC
tWpaPN1VH1CXMP/09T+xZDtBp6M7F/ea9O9ssXolrvCqfUiFGoe0gPrnN5LyH8Kb
NZxsDnG5if0d37Llo/xbUtD63jvHmB3/nQ6DR/ZXdEsYdQubXotjqCWul7Nsu2bS
QwE+F45UnRVCRd6XXRl52uvhlyim3wj1tVEoUUNdYo4inMy0P05McwdL8vb/7LOK
7aa43aRRotR2hIF2VCoTOdsivgg=
=4eyb
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '47c94b24-14d7-4060-ae36-f960c9aaf421',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAwanBc0z6J5oS+lXHypbNCiSETZ4SwE2Bs5nt3LrS89cr
s/wrQCBbpJ39/F1tUjLdsw6VKbLC9K/cFS1tAh36sluqYQi8iaylgJ5LFd1MyNP/
JJ1A3hCyLSkP9hDqWXf7DW4HhBV/pwDUK7yrhZot646Z2Ce5H3jmI8f+KA3pw/3q
FSg2cLWn5j9L7xzlRuHRR/MjD7m6lfdJAf+k7AgQBtRwfx2Al1HR+rPajv96mmav
27NkbA6mXMiyYJPRoLJeRiay/CtXeCmmzUtfmaSXZcQw/I7JuyRxqT/VTOgG0992
vP3ltsEHNPxpXKCKydf7z2BJXFGxmx9x+Hu9UX6qpJF+aalBc0j3Bah9Pydq51bN
3z62CcUcGORf3EoKz9gqZNl4SJPCFfQ3bdVYLsYxYpElWaIdMqqeo6w0be+Xkypz
gqbF5f1frTHONCOzXAOIAIv/FEoK9wcM6GAYSG9o6oy8tLllAv0pJi5fGdTlwJe3
nOkl5jyK9isjDp36mZ2gpJkMzuh0mXobGI/hqUm51RNZWV0eWL8PgbwDkvQ6zFj6
/bOIIGNhbKwhpnGOCxmiPZt8TKbT8aAIqQ0kQJbeRYP0AvTDSCfcJ6vkC7WYmXUz
1CLPuT7bFaK3Bk3mEXr8ViN7OEdGsGGn35jPMOPaOLnMacFDC8dxgOQeLoqROf3S
QwE9HSB/3Pr7n3H1HBfXn06pRX9pYuopheLj6dM6nfTTowjClvMfzXRib4NsgRhc
3ewdQtQA8XICsxVm4GZAF9wFN8U=
=+oba
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '482a5eec-788d-45ff-ae41-66ccc8d389cd',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//V/Qs+bbV4LmbinfPU6mBzPZkgVUkiN+v7H1jGEQ252FV
IL5mdIp/njxYMt0i1Zb5N7lGXSa7n6YbGS3xRNdDHefpwlyOVmSRh/NooVLUB5KV
o6Or7U3stQYO1vEQsJ/F78KHq0itwssTUz6HR9PJCZpCpB/tOwg6Dn99ydB7ll0K
m6TIpOcQp/hK11HBZROe1mu8H0OYSuuanwQkkljaYHojaxmntnYIXgwSbo1emSrH
X5Vsr5o8sWo+OZlmVopLKzg1opQhhwAP5NPp+CyO3dpAkCajR5Zu+TouwgFU5syd
HDlCv/k9ON60uHTTHI9E1zhYqF6B9xLgK3tSsaXBhqekVZzeeeZbVICF/ksIjJpi
tfDlO9cPFjhx0LNQYNxh4/oU8nQY9G4rlsZ3KZicpJCOYLJLKBEXV63uajUdyYm9
Mq2lgt9jIDKWSW0VazjeB/+ioRa6fTR/j8AKnbonJvzmgdWpyXP/zr0xfowqj+NG
TpvSaCbZBz8iYNMsWJzNv+wQlDeG9K1ZrtlbkNX3RoKdpmpp0a2FS8r8aXKHkHeL
f4leB2RrWDATjHHVtUpgLN0Px82aqXLM68W2KvigM+zk+ANGtc6UhayOZ9Y4L+03
3sXcBO3bxpMhWtiKnmaO/yjDawH92r0HjOzln10J4ep1AzRDejCmty8Jkp2hd3/S
UgEE2R3CWlhCyNfoG/bQkmKXcrZmmFmpdUdO8phNKPA/MBc2G7W0tacqVmqxxxkf
ZvvbYHU0ca4sfVgoYLBPPzA40XX+rak6Mu93UhhoUt8mbF4=
=kF93
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '48b08632-aa8f-45fe-a82c-8f188ce62a8e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//RlittF2rmZtJ8MU9T/H4vBCpZB+VvJfEDXXjUFeBacDT
JvlXkJal+Olosz03VvX731yfC1+qqmeH9JQl9R77iSofWg9kS27RPVhhNgDfv2Nm
THzxsBcpPiUEw25SicmbUB7BagLy3/RmownLwOmWwLC/qbgHZM1Sl70js0v7ER10
6urUlnC3qQ1yjhEl3cO5G5IKvCFjBZWp/nWu8Ztg9M5ttDO/aqvNqHEe1gFbetBQ
nzyWSrJLEY1ob06OIycXN9X+0PEpP/fTkjdrvv/3M1etcdI65q270WM9b8inCmg4
AOCZRPzELltt4V7oXtLx5wUc1cZN4FP9GynqJfMDCN9198GBE/2Zk7oeE2/hyCn7
DCIB0dbmoaLB5qFCbK5D6NZBRSdBU+i24VD4sYZjYMFStpWNjbNq84f6WOsiWIVb
YxJqLtIcVnvoAobaKW5gIpQma8eup1ipxXCdiioiYynh0UOz+v+mP6YQulfXXk5+
aQc6yS9CqqBKZ8CsVDWR5Bb50Y6MBRmqwgmi93/k+MSoeUT6yHTpkwJBdJtpLZf9
DUH9ev4592qTWKHTbEqWZ01YBm2RI2zg1ZnV11ublvbhiycWgQxAkWROEbkIdriY
2ZdhsWd7+Y9JlWuHTZPBP6thY3KCeiwG0XUOM6/xkvF8UoigVsBKUaK87a87/CTS
PgElrD4K1hLsWUjkyNUB0aOMvsu5+P0DkbRQz+RmvRnbYRD2JoEbhxKo4XVGYv7Q
xj1K3t3BNNm66NxLRuuR
=I/Ko
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '491a01d0-29ed-4c67-accc-e8a92b5f68b8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+J8rQOXrxUQW5YJY6HIucgCcFtIcpbbGdAxvTCWU/cVfi
tqUU/CKaEM4k4PrN4WmLZJb754yBHwzITOF4LFCMPvCrokwyCdpEPKJ3vBqt8Q4/
V2m5bk7fI0MJpNmAQBiyPxdXUgOvsj54a8tyWkSMFhbrZdo0p7jzS7BoDbzgDNpc
QaUFTdE5J4rdkFPdsm8pAF5oxdnOXUFehQK9peLoc9cflqzSQW/utnb+goiSW5qn
TVdALhD2ejRClGvLwuWUHuOZC0IV+5D3KzwfYZbkwqo6fXlQCbw8CtCLTWKOLrPH
0vr5Tq8Sux2JaNh9kC4BFTL1gnlV38C/wRs6S5epNgIaiw3i6sf82m0prz43NPni
v6aYvTuI14pihpQJwjg2FXjcYvwrNVnJmhnj+g+ON7uMIr+AYvXJPLU7HOoS8vhL
ukbJsQ1knyZAlVbK3/BBYDx2Sv8T/5AVts/WaLck5Hywg330ARpDUceHljeg1fxi
hSv20/ThDiQjaw1nFDe0z36b4YT3UyBvF67YLnR2UWAcUQu/+oBHrh8Ad0jGw18K
RSkrUmcBsDHBUCk5OSW5J4/iLDu8A0FFRWqzcCAilt4rz4tHdr0LzlplJI8fBH0u
FPLUoE7HcfPohEqoCPxWb5PoVVf/RUHJQ5o93FX/0qXDXx24lfCB6TiwBSpvga/S
PgE2nciqoqqdwOozZsG5/R+0S2GoLRYjcOfrLUawUBw4MUDBqhB8CZ6XjEhjwmLx
xv09aDmxLzYEoeD2ZUy3
=RRPL
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '49b85b3d-35e7-4e2a-a929-b84baac11d2f',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/T+vQRCHs/IlDbMAn49cZNJJt5yNg0ckqKFk41Kc3hm3C
0xUieMNQb+tjXHsOE8HtNv6nPjT05S9CW+V8Zfidi8kcCeS76+yUfIBgVrMqq7z9
20luvhsA80orVSbNtcNXFUtcD3T5qZyh5G1ezmQXneB1GUTdoMtBXBrB/vCPSKnU
XuBZ09HX/4uu78HARdjsNdm6G9/WCRSfEAkpDdqiTW4CmTRnvmKZXGsLlYlynQfe
5GtHR6Rl3TpJ2bejtWA9XuyWrzI2ZccGx/LS6g9jHmW+4Kc6cmiG7XSmBK8Cftm2
Bz9tW1dzWAuPI0yCV+6Iwpm9clolfV/dUjqlgfYDv9I/ATasAS7XMvOoeBZX++r+
kYdFlxkEUI6Vgy/AtB+OPVIKNbmhDjfnBaQhDeqsloX2ZMjZLRXvxWu31KY49763
=fLQP
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4a0d2220-b281-436e-a2c0-b335d04b7088',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAlu4oqw9JE3I6ldgu+xNCy0BKl72XNWhmEDf1JYAH0Yzi
ztdSkbH+66g95iSwjzC9DNpH5NXkCYSWwiDTjbcb6psCuUxjwOB97EYvm+pEipPP
GBQCM8nDlvMEpZ/1ndBT5soo8ZB9EoFx8UmHjIqVbbple2KiNfC0sI+1wYAac7uq
Gyduf1K0b+Zn2sV4Mv6KK5sju5Drzen0EUkwAfWnvUoEeCuS1T9A17GOqabgBWAN
o522OnOd9bC7TFowSaqE98MyNgXLUfkYG7+84BuDNMvA2Da5/T2KtSA6YgG4/7oA
LLPbjZRpPRZ+VYqm6XCpBOnNcOx+JvtcXxsuZPW7xxa98KrtuL2khkF6zWX6bG49
q0ml9i+FcaqR1zfq92/f1YIEHVTUxFygRySt4XdFrEvvWImj2Xg0QphpCXDFumy1
KjtQC1CXWKtTZuCiMe9lmni8atGJ68lBkPJFfjbliU2KrZzILHiER29+C2+J9CzX
MvK/JgG5li+5bUoaGEFniDdkgCQvArTKO1bQ0VDkLhsydDnx+BkUPNDEgE9xKRy7
O/m1vhq+m3G1gCSDBB2mu7RgRUQXKoZ5XLcpR3VtC3HgW1r9Ijv4GtzQosm8lcMp
RXs6ur0nzfv/b333wh4wL2Ji+xrZcZy576Uvywn0RMbim4vdOUalusJoD6HqKBjS
QwGT+MC0m3UbchNRx1VuP1Lar5RWOffKo6siRBv7ryW6tt4Nvda3972xqLU+fO1C
srji5CWGg2peKSfpkEx3HVypI7s=
=2oSp
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4a6aa922-4fa8-433b-a6dd-a3057214c9f2',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9FBCLJpztIlNpeSUEnR3tVIIz/AZcO3diSi3bGMbjNksu
/ha4dbiEl00mF2Q2/rwU63o42QBLW2VJs97vqSJaiftcTlGdkcargXT90+osSx7I
gi+q9lnZsu7AJonpmL8z5AwDyU7urYgcT8+GlRddnwL0ngP1JugbzBcDwelT1CYh
2c8vOPTqVY/6eezyWWDrZ4JMfvI3+mY3rvD1mwuyLrP2izgfKhMrn164N43EzQa9
EKTLm5xpBgQgdKT+qXjlzzMAarmeW6ZkxaFWAXodXhd0E2Iq6Ojbju+tKrMis+Xu
N2DU9BOSUHmpA/fOrPRDb9v0F6Om1Lx0RBV3MqdsW49Bq7Xl6P92Ea5G63op978m
fCrphv2fv8Q+ZQZPbgGHBWDrlNQRYRZX7lsUZJvUHx070npR8BfCls4ah/abSn94
35OS4b255lXoPmh+AxSTvQsqKoM3dvXXjc5nAaPILKtgD1h5WW7hb3tV0GVBZ1sQ
DF8fXQfgUxeZIkind1RVJcBEl7jsXazta1gtSy0QAwq0pnpCZqsUvAmZM2qAGtYK
atlYBM6y0BYhklGrChN8XMysaTZYrflre0Q/YL5vZYjWNmQ5nbnJDdwdPISNobU/
ucgtspm7EftYpY+DdAapqDnGunwxZjTrM0CYyrAUtFg6AjQwcknIVno+a8crM1HS
PgFZ9b1agv0hF/FNBye80Hvpqpb1WK+5aX2mjq+olylJGHPnrZZTRjbREdktK/Nt
C7YsfF2Z+iT1VR3TIfiz
=vNbH
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4bfc6517-b21e-470b-ae4d-464d859c8a4b',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//T/FtZ7SipRd6bdSvWWCowGC+/gRiCLPp+ZtCVVLVgQdn
8fKO2K6ye9hDdfjxfFCbREwLqFHRkXuNJG3YwK1FlEUiKcyXE9q2bDQbSCOuiL+L
8FWyYpkC/aDJjfvk/ORavGeR1TBa/MksJTHvwCTGAnrJ0TdFEpDGY6/VADiOagOI
Gcq0Su2Pxlpy0FBJhFY8hQrQgh75dix/ZlEYb0CNaWw0+t/aEO7Gejv7sMSJ3Jzy
j4qL3159oqUYqJTa57uHaDpPmb0XqSBHWecBdnmSka+HG1yYu5v4MUZi3J0Fux7m
r4bU2o4cGP6U6neENjuEJXlExWronSkh9rGuZR3i/JjgX+M1XR4ekYBVOUH6++lM
ozprZ78AxwCkCaVk5q91yPkQmX3fFkco7D3IF0EQL1qI0knLJD6heXYB8ubHPMWA
WdmP8tz3gmJNHl3vE2SfN/8jGSKWVOdD5gIr9RWfYTPCp+Qzn9SGQY+EHXg7AUaB
aRUFY1A69irk6p2DlXWjMQtAjIv1AI6mAlVcnn1bJL6gtDUcgVmLVHudWHK8nKEM
qe/n1wDgC8wz/BgypSPqJVHTFJ5HxxwyELdtfjrR39vvrKamxmXEpL0cbNECLT3K
D9OdcLUtwHkq5DQ45VaJc5S7B43nCLGcAOH/9lIfwFBMofUmIOHFPudHIXsaB0TS
PgHZ9j1jtZL/tJ+6UDu595/jHwT4fzBVWAPr+EZJ35iqP0RjNphHuqlSuncaU2ov
6UDXeZHKBSW3sQAYO1xz
=F3B4
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4dc88209-fc83-484a-a1a0-a2f8f0c3c0cc',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//QUJSnVheJtVvV5IvdLpuhb/rVEeffP66ryUxCxbBduYA
eyJSWekd2gvEpc8OQio4XzQR8pSAio2AmwTiMU6gaX3TOQp5pAltBEkHC3IYKN18
oPLUAVwf5pU9TbhHkbyL8bhWBhWnVz7kMRr42ZDKX37ZXiA3U2Vuu+agvZQf3UZK
gSpyfdl5sLtCj5F2CqA3qlLkHSXD0ZzA5ey8XgXsIosAtD0QXP5KTTTFA2JtlZiS
hqS8DF/ia42Xc0zjs78ghO4k0DCIMvfAHlE02fcO9/8xGgYE5KLTlmXQuOk57Q7s
m3HhU9JeOLs14zhHn2YV3zWDVPidfgMebUYLGfcjho+5C8k7ZA6fQjNPJvSjWpCE
40LHph7FNDuwBs4ul7kTyHrBoZJ89PyxQOpe0HsTI7fazX9s3bKbmjm4yaEdM4Cw
6OwlkhIHGorPJCQB995wBevoFcSOtnat94AjiKST7fAg4ofYaCKqV+nNBw8qIwcK
MiMrMRxw++yzzdjkcROxt/9mrATgby+3V53MZUgJMRI58Prsv4ODZE4f5RIwM/Bl
OftNvii4CRBJ6AUaI1CSR9qPJV7xlUpywgE20TFkKsAFounbPjSDpbTJVKyRMIQf
uEvbh11FRND8Zut7ssQdqrAwRUHIhLiF5kdAaqraAJLhUBwyoCwee0ZAlttkjMDS
PwHvVTji25DIX+5LTwikeAfE2q2MV3nl33SflwG/o6I6/XC/htHrjL7nLBViRVnD
uQRopLWheav86qFS+mMFIA==
=k1vt
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4ee5a428-319b-4f4c-a919-a5bf84cbbd2d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/eHs+74q+JlxtED1Yl229ZWnkRhU6U9sKcJHulRGF/2Jj
rYxD2BCmgEmItOd+KWG2GnVZqyisHfbW+PZyBN35XXfKjBFdD6NIg4eblEQNpSoE
DOKw3Ws7d3ocLZ3NJuOZSKGZPyPkY9gEjy1r+8dSdR7oPjGaNBFyZbIW6+8UnNUR
aVqkzlSuvPeAqaLyPCPpLVpVYz825k7OKMI0QPSxV8EPlWdZYWlYPd1f5Vvtw6Hy
ODwnzOpQUzQbrLtanX6V64QgbNBajMN0cmTq/7yoOOLS4rxg1TgJ8MCphPActf4I
lakFzTYlpGN337iW3MBjEGdgn5YYP2bZIUIGUS55AdJAAT4kkE4lQ3dwrld9odAd
QdexQphJ1UiRsdQsC0QlNY4ZVlTFOM6Mztq0ekZUcco6o7svO1BbQbSD3MZtKmSN
dA==
=N+Un
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50c99e18-b8fa-4eff-a718-348c65cb6f8e',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//W3ZtK/E3gtcovcmHdMizegXgcghmNNARdBhlP+4TNDv3
oOfKEtU3D9XFBa63zLHuMMiNoAMuPnEMwrsdIn9P0VIsNwpQSrp8zG8L/NwOPsl4
wqAvcFhn5wJ5iGogR8innkhzzazCHXq+DjP5rfSMv5jxjKsdcIphivxfFDG8J7Mz
VMP7Xln3R5YKlJZ+oR0j/7/iL2sfHR4LwlIytTD65SWMIc7xkacrGcd/K6ndtwnh
VG3GeIIg4Y2nnFB8CZkw8r2cdD2ov7BJZ65y8ONzeX7ahV5sYer6o94kTVdbI88k
YxG65exwUqdqt+E9+SaVE4etS4r35QumwDRWJ4m5vKGkRMVQeJ43BWpQLkqUt6H9
/7PbRTgfCHEORurlUOy3I4wvEGLqHVLdYjyDyu7WsGmVzSRufXXTI5dsVUyYeauI
CmQlISPtz26i6LVME1BDnT0XiBJidKM0zSNkNbqsrxzir3719Lp9V7A/DanNVuQQ
Cdr9KJYEeIG/cqctNOYlRFEPiIV99GjvyspTGqqS8weziydw2YOjdPzxLAdmW03Z
YE3bBNjsYGCgkmrKLEiOCwb/J1bf3Ip/1Ox4pduQCfWqjTi3lYJbMzxoMLXkHajE
3OYt9QvX4EbwQ8nMHbWXw5L+FiUWp5WOjABAUPXCxJ0yNB53Wi+RbxkN9WLEQSXS
QQHmfCEzBZBpZYnNcFo4Gf/OZH9febSHVbWPMXQl6wdRuK2I8vLb4GxXyZtD9YjG
smOm4gHm65vLQ+oKI6ZtfKEu
=ex5C
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '523ff02b-dd96-4a02-a6bb-921d91c15a0a',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+ObAZ4nsFCDjXFDpPVuk0hfZj6KD2TBFwNpUK/Uy6rQV0
pXDIm5yT0zO8uossaqUCA76t8erFvyNnHSGivMt8a2DqI7hDt3Wj69hUahUQUAf0
uEhkpCiZlo1CyI32myb/NRjKG4sMlaENM2C8RAwsc5B8kdswjER1Ngiqsfyb/PB6
abNvOo4Elp+FpEVKLI+HMgEb1pn72rZgSQejdWeVkek0RKjwjvnlqbIlhq1t+wLv
iApvtmQsbMC13vJGWqzDett9xt3PGyHYekMQL06a+K55glRRRyR3REDaG+9i98Af
pnRZeFE4RnhJ80J+2z/tGCnXNGf1kQzsWB/MxvNVANJDAT8sU4uU4vamw61lKhQs
jndcmGmstTmAp0k05Nqr9GMenDwDokWHIlG3dClEF6mWQBSnVre9RDBdicZSUDGo
ivgDHg==
=pSS3
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53382fd6-bf6b-4680-af97-e93ac3369f9b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//T1s6LifwDJLTKKh2/j0V8cII4MOIoSukZn/t1X3w7c7h
d4ZKh8E4mX4AML7TAn5wmTUe29wmX4UxJ1n79njC0jtzA8wm1XlMWJOIeO+Hj0JE
MDjOMkIhGftmMnQBhd2WBAgQ5RLRDfeZNK5xQI3xADysQhvm/GUMqL4/7OGTfSvL
55LkF3yyMr//5AcG4HZO6zEPBmBZ5iOHtFJCuVuzkjGCQV7C5IJJFD1Yg423cYq8
YibfAmVAvPipVFUTmh96AOYx5wHRhG6eEft2W9D4gsXGIIXk2Jb1WMJkNIjKzSHT
Tg0c09qDyj89jJHrbOgHYXK242kj6MVbDe8WEkMi8wfPGbroKuAh1qPNwhpqmX27
jUwlaRIEu3YctJ7n5rmo/en6gS6J82pM6h78nhWV//yeDTxHjVWtZReth2+c5Xmq
BFBs/jNexM9Nycip0QmLY9pvFuXlqmMHKkpPXDqLJERNdHA/WpjamAJB5/gfh8dY
eUcXaFcdrVtw4tgzTmR5D2cs6RC2WN/mWThzVSyUavvlLkOQ9ON111d8m/HfYz92
SoAyt3JAdDK0+5KnCRB/wufP20O7WdABe7X7/vnCl4NUeLMB2gCJvoYrg2HMryNj
c7RseBgUlEvTqwRzjtDlkD1FTfqsNtGfIBdW7KXPHPCvAiINWEH8XwKdLQAtZ0DS
QwGszL7d9dFoi515zTquX/JqnH1/yuqbhelrz9qlrzyImPY/9X3nTCE4Cj8iN7ra
q2IPhfx8sFj2/cJvh0v9EVCv4wg=
=/aum
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '54cf5f34-16e1-4163-a785-ac7fef106297',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+M2aay8lQRGlTNDa3OHgrTljSuEqo9w17a6Dqq5J1dUR3
HYbmMkuBCAuhb2+V2mbiltyAyaqKYx8bcJ7q+Smga1Hzttd2ZtCYkZr12TtUH/5u
asSvbqDJVra3C822bPknp26j46P6skwxUCjzfowUYtcWOWdrLiMRWbRVzMUmQQWq
lZisQjvJp0X4UlztaKWqWroH97sRN00KCH720wealEjN19xIoUmSk6Svrn8am19z
16qL51gMzTH1AHV2yNEylJ9tOiZGIRx6ujHSgh/p5Mnd1uZs/Pq+wwPQak3sHBB+
xaQGLvzhuaJnqQqwtJBvsS7/vhPVuP5pf/MfoLWRs5Rk2M7C4CBRSRPtliefCeKo
eCO99Pih21V+D6RemvcNNqXCato9nWITL7DpcOoxn1PBuy5+hEOOmgW+vD0xx0mU
L5+aMixtnPmOMAvV5ueMIDF7lZgbrgGKX0/jlHrkB6Yg8pJ4zMO7TNFXIucLizrH
7+KJ3L1As+rrIoWcTOXQUExI1qfYTndyfTgWC5XFFxjHvt0PI8QhGCHPvZnFNdqz
NfL8OGulaha7O+BGsfwcADMZsIovwS+fDQ/hsShK0klHEG7w2WC+fCfueMfYYsfP
YwFZzpEpSmq4F6BX8FgM9bAoiE6kCcxQsDl59Stq+xBoRoA6sFC353L9exnt3+nS
QwEZEFROFnlIegLF40iFafYKY07uBYv0mkGcfrhZUG6HSuj4/aTNHZH4m1NU9Cm0
Iq6Mk0/dK5IVO/m+7u4nuPfJaGQ=
=i5Ym
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '572c46fa-bcf7-418f-a57e-461834934aa6',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9FPhZnlTJj2WSaRKVvB6Ojgfoc3Qn6or0x+cJLihDLL4p
PQEv+Lzt0aI8ni1xz65pt37wjSkVSORzzQbqLqTGpcCeb0PXYgKinjfPYfMnQoKD
mgojnJok0bm/9NGcbNgh4IHOIO9Y2Ntspr/VDiZBBt7Ws5WK18Brpysa+7pHvil+
bAVH1eTP2vVBu2iKyageIoVEAhliZqAOUdaUyhJ9Q7kAS88k6E6MWqHRLkfyd0uW
aFjTIj4sNxdbnjhoG7DtN1UpgiHPLb4m9/fQrNv4wqEvi9RrJY6vhkRlI5m8HNy6
P4QAvHL2P4wbUz/0vLv3aSEbbNxl9s5oxFCwTVpQBADjzcp2+sZi69BObzLsdgdH
v+HkRfLK3gB0/se+8A1WJzgW0AAhF6PH4lkHlksSEaJfldtflBGuY7KOU4KDRRIF
0TaOABdlFAB4W9YMIzfJpF9fUMFEyaBVUU+DoIgDfakSZGWxTloxyNdg3CIwMfWO
+Cy9wFhf6dkGo9PF8v8i3x9p/okbmHuaZdVE9mDEfmV8oe3kzJg/zu0GWMJel0Dc
zSQf4R6PG0fmLYewJWfqLfx0pHgLkSTxj8hGlLxY4ska/Tx1oGNoSh9r+m/eH5ym
b+0GgemEK72+FhDOOkhOHFSYRM5JLpmRhsZ54TKz/VEpeUBHNUhKgF8zdt0UiPXS
PwHXxHncBwD6R1pvqzldCEJD5qMnWHF6RdC7U/e1u9RDYri1SkbeHoQecPsc0vWY
OCsOaSsbaEd15paD7pJLuA==
=R6SG
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '57d716d4-3fec-49f2-a584-37ae824abab1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+Jz55XK+y7+bkwIPUKKAlc9Z1L5HSWU9Nex/QDyo5iYT3
rZT3jlZtktB8FF6kxVRp1l2LelXCZUQCBmw9IvuTI4PwEKWdXlKpfrdS/L+6ZJhA
ujcLO2V5lKEMgICyjnG1IdLCZUZe6TmBEZR/wzlxb/ImMh7IiuwoleQNFAgqKOuh
DKTHT6OQLFx/P5o3fB8xgst56caLtUKBXzUqGPqeqn0+Ds4haUU/lkwDlkU8Z4ki
lu+Upfm4rA8QppsiwLxyu+sgeTKx0q1THUn27e7tkKKsmPJzvgiJF4wl6KxGjnkD
LIqoTUfTFg7gT6F1r+FtKwMyPKiuSa/fisfClUaFizGx5/12vZ84A5fCrQlGQpY8
Ine7uBa4un0oHfqCkrVbmGgGxFTiy38Sqi+ffUWEZcGcH7V02jdoosTbzcoAIIvd
lqT7oaoxkK5upD9GJxTyBOg1x/GuaD7fwLO8lwwYIEr7rTSPz4A28IdXiyXo44PR
u8zflGdaIMbO1HetFhHsW3E5V2fykjf5VXxcuYlIgfFoVUb+eKow4lidzLUZyh4b
Rif8GOERbCMTPo0Qs1YzZ1JdyNj9IqZ1sLsGjwfOcxfyWxqdsS0oD2grL1uGA5xf
9cUYU7B9z3hFS5G3gsuCxIMkve0LPxYsvs9BA8BXXCi7OEUWUOqUb+L6Xdlqc0/S
QwGv6GbM9S7tj3l2Ss2WCVnGZiafSS85+Vzh+/d6JcvaZtE0pHKXHBB8+YFDldjC
0DMYD1JL82LeeHYgx1jMxcPbQl8=
=GEuY
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5b0bb84c-4048-4cb4-a691-ab17bbc9010d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+MC2tkR2t7z1gOyNfH0bqoHw7VsRMdknFetIxSrBAkSx/
wm7Dpf8AdQo7O33gnBtcqhN2MvgiSJ9IUyEtEd/zyXHwGQfVeDOS8+UraSi7LyTL
I7gUOtDxZeemM43lnP9A3xR2/8nccryTBUo8L3K9Ax06L5Oa2xkCGeMlOv2pZaKk
CHpiyNWfMg3udNCD6UnepRJH3yxbG08G6AN2oy+1Rq1VZ0kMExcAMQDLzPI1iq7v
zMmNBA/20iVzuJH/Me2h2x5Th7emmhFiUri3qIU97td6Jav50TKki46LV0F7FcNS
44A/Za+3curP+tgDrzChTKIGPJsD3xJqm3VgtdFSh1gaCI+R5S24WLQXXcXXcPC7
9xbulWeubNI0IBxXnc8O1iHbAXtpi6X2e/yt9GNNVP271COULopQyWNCh+aBqHwV
+WyPIejKBGVPIuGBspekoQVivPeMdoR6ofeh6ycaHsUATQPLAPaqm2Z/UrNENi+L
0tbuh8d7NM7Yu8xjwzuduH3RlGFMZM7yfXySw278/Evl+RLdGixt8Y3s1UD/OZbw
2g19ylp4CB1ShNr8f9uKZ5X2Q9TMZDrm04WpzMeEdUWYLzo69wgu0BWy83V0hekp
V6eBE0EBzSso8X0Kqs4RZU4AeaHwcS07hwJ+9lnTzvGHak2ZsU0CaFlABduLrmPS
SQE4Fm7BXoKSZTbY+mot/aTIG1nOlmoRFrZZqmssYOiXFtwrNQ8DfP+9MDYeheeD
qx8rIx0Ju8DMyiVpLoM6lCA05mjjuF4VjTs=
=6wcN
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5bdd50ab-46a6-4e33-a732-5ddf7e8540dd',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAhm4RwheW++qdtYE1NPzIDcOzgEjOVTBT+wsitdEeTfkz
RIw3pptd523bP4P4HyBS7t2OMwSi5CgU+CSw43+l6SWp0sWNSPuF6AZnLmWU2Rfc
kUOuYdd9Oi2+ygnO4VRbjkFyzLRhjeirbKC6pFLlOmKLBzB0Cb9ZgRqmSaNcxaZO
BWtRx6biFqfGFbe/2qdVBMLkF/jG+C62Ojdo99mqpLZhEjRsQTFoaPQ271vnOEPJ
7JoB0BVZBe3yN3rGOme6xlNwpBnrRu0WgCcqHu9q+ElI3OmpHvI7dJrDTyHOLGYR
+Y+r6f8QLBxk+X8/hwBpqHPcb1cJrML2jZbwBwoEeKNl4Xewmg6k1i4D80Ax9gbf
BTE5Xw2ht6q4JkOY3NMUT8Ezyq3t5wBYJ4GeHRmRuu+rOi490Q4EC0QfWGIQAmMR
iUUC95ogRiJ6UXz8RrKxP1hACzYKuBHNldz/nM+pIj2MS0wV+8XDfNxjIkuimys+
pQhjZuxuJwPc5Iy3+O/Gi1JKfbiyEPp2S7/2fekauSnByJVCFBLXSKosdb5B2haB
hnaoL8OhoM7X9rYaju4xviYwfLFoch5DNzBjhPmdzPiQMqtu2h5haSapElLN0jEm
cqmWGWP1lXQ4Bg90sGkquCuKZK3KSeSZKy0mt8j4Xsq2JGsL3wVsZEhgT/hPierS
QAHrhS6L+ZK1/C8BUeEVlDd6aAYu8LYk1atEYZ8Sylcepm3PRoOJ2LXCBNvKB8JI
2gwc4qjinAgalEXz59TJjsg=
=3N4n
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5c69b0cf-20be-4955-a1a7-9b045a85e889',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//fcNLJE2JP2Fivwx7uWbFT5vkwC8wqlCg6LI7vHZJhOVk
XEO4P4gJ4YSA1A/v5nkAAHJR30qN3Pewenp9ZVfEG0tjVbwKfI2UJum0UE2MwfIC
ujDM8SCeDQVkPUGh7xZoBnme6aB403qm3Iuvd46Jk0JOsyT0Or/LZJZqpkXOdp/E
rNudiNSGqfs/2WDSsiTPkImjl/P40pOEZ8bu3YooYTAcg5YLVqvMebxmHi6T9sY2
SKo6B6yuyvGM1X9Qrfn0NHDMwdDW1ZMoxbATnpF6Y/3CjawTKEmrqLpOIw2DYeKP
FS2efO+U8qIe7Ps1iIVhOaN7pB8qRvPDG1Oqu2kfva4FuT9ZKdalAY6UuP8eFSOD
Gg6EWcBVSxhNYAdq3gLBoebRkgwpvzMq2VcbmNcv6rInwXs46wH+O5x9oQIjQbMy
9lo3aB2cnVLU/t4HEQwJDrt6WB1hJ+vjzMqSRoJwHA9QkbqH8Af37sJUCTgy9DHL
n/IG5t6S2klIGDQhmzvf+uklQc2d4pwT2AcO9zvpQ7ymrn9YwhQBrSrzVF5Ni8xc
0t7mTkv5+T32sNkco7mkP2U7au4Co7PMmxtkZ+EI7fDT2UESl45l9Ffy9Nd4+hBa
oOTjvAX+eH/sjLcJatXlA0qurXicN68FzRJ6FKKN+B0rxyt738Ga2KBarNR/SUfS
QgFI9hkiEaQgTu+S2H1WfrA1V3xznxgUCR9LuDdVclma5EBfroC5yGW5PlmQyq4R
+pmOnsNG8PLYPZ0AVFMo6r7oEw==
=BoVf
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ccdbd35-26fd-4757-ab80-58c85e68eb16',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//dg5zgrdcpGN2wo1RThDUp+JBBALTnJM8lRGP/NP+9dE0
t1/uQtZiyv1Ou6z/a1odHixNxF5noKY9pnMjFV0wYkFNVhE9pyf1W3TDtQMWa8RI
d3T2iCr5nWPYYsWc+hD7Sk3WXIV3oOAQ5jSUXQu9hUlS3WY9hUa+q/oxjtWPrms5
/KH+lji30Da0WcKalq78aYrr/mRO/SpHLfFspO/pYBpZmS4jQ8/jCXVLSpxrk/AR
ec3x+d5CpJaa1sJo3laC9Lrxu+shWKclRmdyl0ff207Mh1IwdpCP6pTLY2Hkm+2/
m2yJ6cargTUgdtlZY0+4jYHUSM3aJI63/JFzodZWkp1gvLLKG4FqtPavPAh6npgf
3463NAjUcXRjD3XE9XdUmWSE1KRa0pfpfOtZpU6f2mt3NUMoQ8KJyeUqR7iRr0TT
SbOLXfz9tuJjqf3fAbTxI2N2etVG4GiqlMiKiDSbhPadWRBI31zQXyw0gvVmzJ1p
Ul+06XL5reFOz4wqYLZzJRzjYiTluilXfE8T5p4VuyGFZFdQLsGX7XR0SeZpZW22
3ezLHOqacgTNO2Ms0qJ8DaXMZNJoK2KwrxxnCJVUpeARx7p4i90FwsN/eVWrvref
kf4Q+HdGBh8LzoO1CkwGPL8EDER+6wGmSmBYV7jK0bFHb8iKp3BB2GP4WpTPw9HS
QQE6Hoi7a06oewzWuIMm07dXW0QMQYVmPcLzD6FKYFnFZJHxSf6QlUnflpisObc5
zSal7muuS3roTErZYI/cBFdR
=5Mdg
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ce782af-3c70-4af0-a75a-7927c2634f78',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//UG8zdzjzTTaRMCytZ+8hQ4X3UH3TNYGutixl26B6eMP/
3o21IKwyGTIjxyM89YveBJz4o0XVS+p5qVbyk0gBYrCZ9zjWHS+InpHc3C6YJEqf
DC4WP+LmbltKxcTQRd2BSTggGNIl0///aqShO9R4Gka+pQz/e6woCLYLNL6tiAnW
W+vEL1hjmNe92dxVChHcIRJs5/6yJ7tmegrIPHWSJ19TwEkw5VdI1gDpsa7TzWUa
QvY7fYaBl0/E5IeAaqP00XKjyi/aj8ar73NlCiDvq6m+nPV9bWkn3lqsbriov+ej
H+904w+WUiYOLc4N3OdH49hXzVGWy4LOus3k1f8A7T7ODgeIG5t2j2tLpG/Z6u9V
fRKXcmnesQiMUQdGR3OaXp8iNcfkcXnkyZDWUMHsiTiTxLuFRPT+XCtE9qwf+J7+
akBVXXM78ZBFt+7MQgXRatGA8TqZGMyWpK1yopvjhI22V7yyc+ByI5knpEGHOlO1
LYQwtiAevnygVZHAOHPOt0QnpchznCfWNhNPiEWrTDAEuNyRzJXaK8iKtThSsahZ
fnEKS4ctSpj+L9dd0fFFoNv0Xtf9368F++1sbliFXoxGsalfZKErOr4ihMpVoMr0
BYayifpQ8UgwzE/w7PRNhBXJQfz94qzgRpQIq7C8nMlQlNCNgyDADh5gpFKAESHS
QAG3wjXyH7CAMN69sUBhC2ThrqnupOlbsCZeRJK5b9Brp2bRm/LEK00v2BRixhd3
8XEW6vY4/vWFssQa0dcbjlg=
=e0tQ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5de6d595-c797-4f8a-af13-4501f2ef5938',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+IhYZNh44Hyf/bYzqzpQobyUVmT/o6o1WiiRjd/Y7w3e6
iogXzUaNmrrcG3WALmgSp2TBhts2rpIqV/XlA+9/eMpnIldxNsh/Pe71ogLnn3nV
mN1yNwhS47gaQt4WMkI8Vf7QGJTdP3AJ4ZPfx9StgCDzdFGbpoXvuuit8kn2XL3c
4vCuGmJ1upVBH0KxJU9x4M/T4j7tnQsKUTJmxxrtPTB0jTtzTxLtpsYm/bIBwnVs
YJvfUnEuLtt8NMDFcicI3us4pUgb1LNFceoaBMQ4sSGHGS7bbabduvIQMWf0ncMs
d75HLui5Op5pRoozaINhoKoYPkrdTB30tE+IWU/9YVYNZcsvd6fEyH0N+h78R3sC
zmURrxT/3mbyPvEKxk3PtrU71Qso/F4n5umdjtpRJ1QSPxq0b8c3FN44hxC3nU0f
3m29t1DE3lBJGZX5jW3MVUTNdVm6/4k7MwT6cw7sx8656+yCQoev5YZBdVkh0ZjF
brOnfVUjWAWNy9W9h4p7F9nDNBaJ1no1hFpNkr8dyQlmeD/ZzZAyKLxKiiGm4iDJ
IrceJz85qg5JYTk6naUKIPOXGsHLRNrI7Qn7fQEzQiBYepua9Jq6+GUjN2MeSGFN
T8Yiv85rXLbb0RPGElaCa7c7gieGb4WDRLnrzAB/9fbTRxMs+cmm8IzkhW8yRVvS
QQE29TNXiAzSrdYfa4XRHvU+eMf5Yg5HkJc5sqjWL+Qa6ZoL2g2GwgSo3zMa4+9N
cqNrE6BT/iR3ji61thqc4bMG
=UwKP
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5e38395e-1e8d-48f9-ab66-823977037d36',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAuSz43fcbNZzv0JRitVfsS9EKY3ozqZKDeT417Hszmf5t
jysZyVwtYseH/l2nGQbyGlVqBQ39KB69z5t+l7XuTuhMwJsO6zvmXlQIZNi/4eI9
wo90/sagQcsX/UAu5JvKt3IY8D8+0SP95TkO7XpgvnfMxixpMFIc+tRqlv9yBH4B
7h8KsRjK5fTfPTXUbALoxZXcckiVL8jzSTw7RhDcruIuscfwlsztKfMPr49PB2B+
aKvboH23jQsDiD/LlVDKkaj/jDVXZcqlTZ2Ov2lT1HJttLfivxhQV2JpFV7NplQu
rsbR+nb+X9/+SEtEVIVaK5W5BJeWVnpKwRCgzoHJw9JAAXvRJTZIbfg5S6fR4wNl
dYDTzZaWULfI0P9gUcqzckdR7l2IJcPrIzUzekqnevCeY9aJyj4zEWiMTLjTWGQq
/A==
=oeaQ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5eeaeb2e-9502-4182-aea0-1aaa332a2832',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//SwhYbMNdZhFHaDUnmZ15SvGGBuEZhRS/M2EJ2HsMtONM
+CKRrrfjD9PVNAvOY43UoMZxQDm9xs5skHVQ9mwQPRAQZpwVUWqcpzch7x0K2M/9
n/W3J/YlXyzFtkZjaUWm6Oh/50n4a1sV+QRCherqVVzFzwf/Jnot1hdqPKGlbL3L
dUwYwKfMoUtNONZY/rMuYV9pZuSEfKjN9SXVCCqh4qTdeDhVHc34n8VreQEutl6p
+RMdXxMpZcJrfBTFQrVJQ04BdGWJhff0KV38KIUmUmP2ZsxXhbKaiSHtW0Apjcn5
65/rQIksb12pkfIxYqpVNtbPaP+t68o0AnkzVN0VQ+4wesTNwjS4/Q8CJT4Nlre+
xo11X7ZbkXvB6EWq1bu87ayZ1B0CurLHi+OburGyvMAP6Zu6q054rWL78cUEWpb7
3ELf1qdD8QuxU64wF/6kKAkIVtHZBp7XFJ6Stj0I1ouHDj0/fyzx7TAD3tbtWUn6
iuRM8tVARBHB8FQLPDOu0zREMdgqRVoA80GCkDLLMmcD7z0mPk6p8z0xN7x15FlS
gcriUV7Ba/SXgt+rVU15vxDrx1H8ly4Gl+kn4uCyZXeOIDcH7iLLnnq0csmivDn3
MQApd06KgffgKq7cuMaLFiC58njmCFgWhVvqSXAQwAgSuoJxgwV1xhlviulGgTjS
PwFiR3ZSO3gLVe/Rf9XpyBdEPXYXUkegu4vtBb2ECBtEa6DLUcwuTuR3mR4srCri
y/TOEuJdzCWDKX7WaCFyPQ==
=Brmr
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '600b99b0-84e5-4c56-a5d1-f428bb31e0f2',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//fm6yVK98wBQeMrxASYbDC4KXOUgC61kZZW1rv4UKG45p
Y9oB6sFrZRycxgZweuXw+HMHsqyZ8SkiIjY/Q6aMWdNcpwhWWN9Jbv3RJMd5+32U
tj8/lHr7YMzyCUN1qozHF78bXuSbp7Kb2CUOpcYGwSYtao1XJv/OgARZ4vL8+Q7L
yn/BbRhHz8dyYI+x06c52m2G+Ik5HoFyUKmkL1Eyl1o3Gru8egi1rdfHiloMvSfi
QXWrmd0PKngeZufPAKsl8fq1QJw9kjOIKG/MyBSFU0AswFHx5J7MgDcvNGbaCumf
O9MKCHz9tb5R8ZfbqrD6TzB3hS2eix0+cyJoLX+Ahi7/J7JZSXIiqAtRzHF6bP0a
yjeGYK6bbfH43MEC9jsiVpwyXsqSdiamx964zIXU/jF/WUzXR4SKJROzpc+LvDRH
N2DGyR72mBrILnglySaQBKhKYzf+FBdDimTp5O0jZK0VcFxCFQWEqUNdL265OH7H
kzqdZfF4Igs0KkvH+SMR8zhwJun4R6/ZI983Htpeh2c1TtF49+D52QH5pgy0Ziqs
UtRyEfYm60kdukxiS9n0Re4tx6YuoNewKji/zoTULY1mo+6HXxeZ+KGtBu1Dg04E
kCbmvGVpCIKGHJmHKrLoULIyKxlTKSX5v47qpI+7LsvEr0zSLFQ3ztdJ7JEtq+fS
QQEYrsGD/Oe/tbG2lVDEoxjLnjynyMil9kBFpV3rKTLHC5dtvNK3pIT4xgKm1nJY
Uobm4G+UPp0yX5Tj4DBfuxl/
=ZGFh
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '607cdec0-bacd-4419-a2ab-be7a9662dc15',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//T2SDQNabsABbMuV7xbLiNd885GnbN5+wX8v75ZjGxYZK
zbLou+h92Zm6UT0BY/sQh0H3Hi+gBGpqfmc5rzn0M3vCu4gA5d7wMU8ss82i2IIO
BFBN84IXZEEVlw57w8339KOVjJ3ukBflfIdf0FUjVk3PRp4EIPve4N8zNHrakj4D
ltOJ+Zhrt1/RNyicpuGI/lsMSSCfnOhHbZrvbIFgomAISI63ep5ynMpiLvZ6Pj4X
htb7kXNPkxGjyK5RfdDQGiPKCsbOOMVzilruP7oi8fawa4r4mOR9Ii9eQ++LarM5
6Quj87g3xhUGkc5B4H+KoGzXz4kXsNsRuAEqirBFgVAug+WpJ7Gtfr1cJiS1RhQZ
GLeOLRcz3f88PLYkyI06WVTROPL0AS3yPPRL9HC1VEnoS+jzqmj3s5M9d7yqMuXf
45Z9SH58IhHCdLkYiJrbdymSIpZE+6Jv2+V8+3RHPM35SqJRO4QrFxlUeozefKRe
3dDcZjSh5/yp1QeSqVo/YDwZrh0Uang4JzXOZBVnRAns+oR7aZEbb6nAG+N9+Pfm
yROg3GCgupi9bATyoyu4wgadfb/waDjvvbRtJkxRo30Kjd2wEMcbwWaeTUtgBvJy
EX2yhZKwf9hNqZohKO4WauqH+7FxovD5qbtH66Z5WotwcpTfZIMInK8NKumgarfS
QwENdrQw9YXT7pmnQwLMGhlFsn3GqdGfYYRFNHn09tn09nzeiDkPmwH+jNVdC4PX
IhXGzKx9qLaNg6KG6GzyU0LMcIk=
=zrsq
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '60f2b025-0cd8-4393-a75d-e47f800ab8df',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAArC9qptZVMPow1grJ6Ud5XJWzvFtKX3X2jaSuJQ+tf27u
khzAcBk9uhSi2p2W8hG87NimRepS0F5vmW8P2FgD1aZajuZwmKPN4ZMb3V1cD5D7
JTbVIQNiQfj2NvLJAtsu8DUJFuqtTfybbTxl/ENDffw3kZjwyHXOmyq93Sylr1Si
AAP+r6kKwC2EnGoNzaInbxWCcnfcHFjZEmTQEg+ZJaYLdrGVwXcKp1ULHD/nH7FF
oXiffDyqnml1p1wB8WKqg6opXYJgmbjD2Dkfm2bO/a/+F89TDqSKH+EdyI3W7het
3PgWEs8U7UDg24e7jsS7lZJEDTaVOeYT2paqCJkAhpEfHIYH5BqaK+8goVJjrPMo
4ePEroeMFRTfPMrV3ypFCCXGWxGXdOGwB7r0TcbNLcmMQQ0UWJc08StzKBCHRL70
vqmtbPUUVIXaLCsVVsZltVntiO8ziAcx59NrrHPMbK2jx1tEQI8VRql58S5ZSEth
vly43Uk78T2Coo5Vnbs6Kk/MgMqUspeMiJaIoJ9sevv2sQ3U/7RYVXLVy/xxEuTQ
2lubSlcMRS9BS6u6FRiatsK13qXMAd8qnBTj6wXk3Yeh7q30Dux8w17HCxwer+72
EJFnlwt+hAwQGl+nP0rUWSi1cGCqKCHbqCR6VFfbIvjR/7dW1e4cHARQUeosGG7S
UgG5rrJAId4LIH1VuWmI4eQtSPXYr3lIjajGuZIByAT+kI+FVX88HjyQpaqjrZtS
v9gDPkUKDZ0FbHybKB6MFqOS3jcX3zdGpdrN4aq7zAZdEhw=
=veB6
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6210867f-dfa5-4263-a51d-3b9a2cf7263d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//b8mO0amtZjA5P5pJch8UPXpREaoHuxomW2ca1/MBgiPg
OqvITJ1SkV47TP56rbUC+6jDN6quJ+ix/NT0sa/8XhYiYqnLCWSWNhNyRM6mO1MJ
noc+B1+01Uajo5SgzyxmC2oVjXDbbvaX96hZrBsJUncU0kbUNNqv13qwgdgX3K/h
mdwWz71u2Zr0xfaltLFcL0cpoYoZprqghLp5xe8MbXoXMothdy5Frhp3olkIuBwu
z/O9Q7wQiLcCrIJsmddkmkQr8aUld/eAC0skBpP7umd8P1asAKlZnSIqVQ4PJK6Q
+Y/n6b+R6qPJj3TQenrZ1k7YBRlb/tgBE4zoBJAFtYj3av4i7vMSh2PdBymoN9cz
Vgua5wcyQYzvXY/dB/KpFMsR/F1MdjeKGQqQyzJyeO3apm/tuMcAulLQ6b1a66mx
YtbxVCWzzfM+CRkJKXDtdVwvhk3kNkFqG/7iM3wv6XvS4bP5ZTDNkkrKfT2t/WJx
BBFE99d9DhIOFD+mxTEw4M09SjkVXTLauNQBoxN2QRE8vl76PGuTYinNfroo6Qeb
CXv46Bbd+PNeMU3Kelna1eqMbFETWOcXd3JLo3pOBFl4w6+hHdVYjK/tpi8ydb/B
S2/IYiHxECC+QDGtW/dhHgY7BhPnxY4i+If+t1iqF1776znGI8ySIc9jDDPazzjS
PgGhuYZpo8TjxuTWx+1KJQwnfq48+Iz9HQZJLwYzUVQSKTz9DDfUiuZ8WYk1X8SB
X7o3zXGfZoeGiC7bbJYb
=onOx
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '629571f7-8c27-4bac-a430-32f651a5d7c5',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/fUt3MXsbrEu5ik2SWiOytJcgTBDWKQm47vkFqHCPLX7O
kiGUKi2c+zxgFbS6o8bYaENl/3T4S6WUTD0KSTv5Jgw6uFX4hYXOl4hlii9aC9BI
HYYSKFdxGiV2Jx/0Rl/H5eMS1tz7wE9oDRvPg2HwLKF6PEXiEjI97XxA+A2sRMEr
dmfLxMtpUyhYk7/nktHYf4telZIA98leFgPJuPS1m6k+AA1VBG4dWIN6xGONmtJC
LEzNFfr5eN1OcNVXT/aZF9UBTvvktwHgIL83J75dztzjDa/msvdAW+51S6Th64j3
dvd7229/Uz5BcckvebTvzORA6fp/81Tb9tUS93g5AtJDAXI9iNcWh1QYxBpPQt8N
DPT8Jxiv1ii/YWroznONiL/bb2l5JdDeoeTaeXHROn2PWOjA8BwB5bHuk2M8CZ1N
q0w99w==
=wDQb
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '67913b0d-97f0-49ab-aa02-8ff1e0b04412',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+Ow+rlF23LgfZJzMYFfvJEQdJoLhcLKI0cE7R8zBg+zHt
7D6Nj+2nLthSmqelln5ACBFCHse+GtPAkoKQ1fZhYXHRxwW58xut7tgo5GS3Ava+
vbeluoyiXZvs94xKfbHwa5HgHdAKTblAexkNzaltlFpc80ijPKMi2fi0xOFOXHOm
JARxRljoPKy7FYARmdOKxJ/Iyx44eIu0EddllfVKz3zTgRkHv2AUbwaHYLy/nHea
dOFenT0D6Xb+SO3YPS0qWJI54pN3/C+zfQ4Zbzandk+RH8gxEd3f6epBFSxkhOaV
ErkRmi4LZFXESZsev9edvigst89nlCeW5z1GcEI8YNWgDPFiYQ6F1cCn4NIFYanw
UQ0stD4QBlgs5iPvI2E/55moBhI1kNa5a7T3Pk/vtXJXuOpAtsKmnr8n8wQ0Frfq
xR8TD08lI3Jqs4/mG24JXOQReUVket9fJ7sgCtf1vEIlaK/HFcgluV1IA49nRjJt
jx2H44D9ecnTdNywy9mHWcvUIzFsccaFsCgmju7SiCRD4ksMdJrJtGF/GnxraPuV
XJMWJu6oo9LafsrlG27Ghw0sEnz0/T1kS7BVv1YpVSxvuNwktPzg/ImZl3TQbnBa
uZDRjdtRZnVsfDWFldj8gBW/RGCI6JrDJZUuvVRV/mNU2tSoy4P5QpxYlfhjaprS
QwFTiGUBo4IY2xsCGq8GbQInHvKCjnTPf4yXicEVHLnKGBKJw4kdUKU9Mkr+lPNS
8JiwMk1lrIqhxsuRnlTS9DNdS1U=
=CQG8
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6c918a22-bf75-4924-ae89-ab04c58b1ac9',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+P8lWIp4IzlyyRpe2lfnuhSbGhc4S0/Vn08/Mmjejq/L3
HQdBd/NXJ++Kq5CrS7656QkBcLmkGUk7LByg5FB3Zt3lmCStHrZprde3y1tvryv6
AX8gP8k+d/PzkR92ATAWHKNtszega24ffIvB7T2gjGMKErPbUQLEBcr8J9QFqum3
hBB/jIE4946fPxcAqGV3gTFVZ1B5dbcEJkzF8eBHVnEQTP9oZUQ2CLpXeYbXNj4C
m0YannYzg3xjleIif3qWo1WuJcHw5GkWZuSU7ziTi8pXl1D7BBkylMlD6C/LQK5E
JXrbiSZOFWDnmqBsldX/nM0jH9cKj2rHTka092NkmNgnjv8VXgmrAXsZpk0hEGOn
JZZyKX6m1iQKH+d0sRtB5CY+U0/F+kpCMH+t9IPeywHUyBAkD1OeFCiI+n8i575A
EpAMBv4nz9B8d5vVoG/nHJBWzqi3ZEWmjmi3xsPNHZLRnXlL/J7r/hPRfWUvqvqt
PQw26BpaCgHKWtRa1qJyvVyVz9kjFNTiDVSrt+p0OxWqOb6YmUG4q+Muj1K+5QTB
ak1wCyi8GEpEVUdPoGeR8YovogI4A6zdDsguSVg6TL4jjicV3AvveB1xPqFggvBO
lnT5jVsx/gaSiuTk2Bxc0iLMfnrSR2MvA0J0mmUkv81y7it+v9U0Zmyr/4BzmnvS
QQGdwBGGN6mL2kN+h5Dcohe5Caf3g7y3jfiINBgX9gq5/ilPCe9wuY3BPA8uYfsV
L9Po8G1ukTgj5z45cmT1CIjU
=XhEX
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6d981d9f-9c78-4474-a701-48153d7c805e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAgRQYNNMQSIUf1uzo7DltzswmutYZRdOgMe2xHb8KVKZS
yAbfathNzzRWum2Mr4RYah4oymLGnlGqEEXMI9ro4hzgNDNddyxCMbcD/wWOETrU
gVkfDNMRlthbsprO/ks3kN8vW78bbkaz6ZXG/EEVmynbV21yGoVUKrJ7EP0ymEV3
IXU7SYNkfYnB2U3qC+sjO2yRM0sWFVnhKKDaWY0prVQfCciETChIx2jkaiHM8M2T
rH2/gv0Yq8duTmpOwkJmqqGgDgGySPXA9ytQ9ihQRFASFE7pk9dbQiC1NvuMoxry
wiKC8vQ44wDe/sk3dAbzqucE324JsaiWsNLpCrAb04dlZOTfEdu1YBl4P+TcunEl
7fMVBREpIlSj/MtTPCln/plFvaJXNnEmbBfw76Z/Ri4qhoE/VZ0PQy1ABWYE6oWB
spvZmjEqnvUe73iRnKuehs4kNbgk2VMtNLjIt5wXKT8uwmN2/p5Cou4WFfVuU/Wy
dLoH8fseX+iUh542Ty5LS6xeEuuchNxDAiMFLozdIXpGiloKG7Cp8ZNXbYqFHl4d
g8Zgtyl1j4YWpkj4ajJTZw5a1338vKZRXqdvaakNYddQcSYJfI1eHKI+n0g5GjYO
JLVDOqpu9UFYnyZ0cegIZpNBHjIqImhLAPlHJUKeBKTRXjIOZ2MeGjQiAahK0zvS
QwGaAz2E6MScVW3Acsgws3nZ9CmayRd/TvSJKke6FFNt1QnwnS//7OA16zP/Mcq9
xeDFVuiv+oQhYmMYbQl285nEpWo=
=0y/o
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6db914b9-c368-46af-a7d5-6a2a14290d6a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9ElbsPu5afuV/3o9zAISLakmL3A/E87ByjT41WchdIf5M
/qvmVKoqCJ+wOuL2qaTB8JbbcYEehYlY4hUl3NfNg4JGHijYeS6tcPJb0pec+e7k
q7SHBTCb+7XLJhQjYgCYH/Ow4HfFpYkK80DlRPhbZYSaArWYrnw+OAIpbloJ3yAJ
aUTtfJ3ybVYTpJuYS5lT4HhlObqJHDtFtdll1NqCojXNlV4jNHvvuB//y8Kvo2GW
Pj1fmkaeAQy6LznJp6jyxsQEedDrbPDn9nG139c4qanCEFv/bSJSJb5FLoneCOJ/
S8u0zmgOPAp/ZQekJStxZWpsRX1hvjB0LOdVlLskHSg6Hzu+A6j66UHTFwPOtmdx
3rEDnmRPFa/kCz8+dz6Puu3/SoxNSd7O/Cp6v+Lc2R7vQUYiF5hyqFY3WvkJzfXZ
ji2V3TIEXdVkpWVJPwvTNpjN9p+mB5XMeFVGOeNLbk97lQrPo9mfHjr3pe4Mp8R5
mGHXUgGecRKFDcp97Tq9jUpusdKp1qQ4haj+goV28SSki5HeOFia4XGKiNMwqZuO
05rE3ZtNdIRTi5JEfcDX78GQp4Tqyex5k7T7D5G8rKSjcFKxcsSlh0IJ4+8kn/fQ
LjMlG/+3LUbk/4QDJ1guorrSBBnt6nGjiH0qXYFxcTInQQiLsFr2gjzyNlm/PHPS
QQH3w8CdNXqjsOdVP3MS0IFadJG0X1kMdUlQGzbV9LZ2jUymJSPafNgduXCQcbdO
Uz/KONEv35u9uOR+WKw8wAlZ
=kzU0
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6dec15df-bfad-41b3-a6e1-b69d9911804c',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAqrFTBeorUoOVFx4qVEs9ZfZjEtPPIufGuUgwkbiVmsug
UxPFbPSwmZV7k2PXtjvnQ35O+rgfHinVfCn+ZJHSmPlh+BRhmvuJX+DEtCgdRi+v
4q+Sr/rgBdUmf6mwelf6w2rGJspGm0vhnHOGMMw1nZjEC4r1jBk87gJoBpamt01Y
QQvxX+isQ/GCopheE4RqHYg6uylhsrNCcu1wbmk0d3SjF/o7k6Vsj21b1IZm7Bdv
9AGuFPQc2H+xqTRuYFweWc+azSVZ/uC+Xlirm/qqy0u8NQYCFKL6EJPJJ/HcMQOo
qUorYf//c5+pY1oeKySYYB96W59ULREVcTzTC/i2vjsZy4EZkgRxWExEhzFlnvjg
KaXhwlhlhe4U50D4UE8InNma2Lpl+XTrXgZiMm+yx8AEYJZ8KuEpE6UkHjA89hhE
ExHWVWgnUSNMb14eV42w8Fg0Bw4mH40plV91Nixg0DuxSjz6aZ999YOa4L5SZ7hV
VIVPYUAAeE92M9kmeLq/3E8Cl7JaHY5rNeuT8ApU6Rkkz7+QvGkducHH9KeT0snO
V6/t2C7yTGwWGWJ6knCG+9roSnP4BAiJ2h3UlmLrribK65/X/vJQTiWTTWSyddN5
j1QpqZWpFl12FMTXYK1De4XVkJ6S0UbzOtdTRkBr8tFcjdCg6L/K0MNLry2cn4bS
QQHgudbAIUlj/9Qmz8S2tiz7JDEI3MHy1w54LfTj9I0krVGH5bcWS/e0Qrv0ooEr
YDywtJZ7g5IMPtgDUS+W9MEV
=OoJB
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '70a2f168-71fe-4625-accf-0f073a99a625',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAj6GSLDinXQ1bocR0qNHgNT/LZjyMQLUreedwhwg+WSYP
GFYps7bBMDAGGHegc1I7GmeYU0BfyP/Db4iESBPSA2vNW2Oek9BODI3ToZa1ISrH
L1GMChIB7btMUTQgMTJqZVeMupxq/fSP+8TBi3ULux+vWlgF10D6fX4q+I3Y0wdF
1w5fjsDJOuJvyRtHM4St5r4RIA1shSQDYLCQiFjUUiGL7OyeSUXudadtud4ZgbIV
2LBJqpU/hXAtFJwbAfJN48GghDoeBOWwQMCYiBj+peC+cbnMgSFoXzibgZMGpYrc
G1/Y3JqgckBdoaI4HURoT85LD708eJEARD/sPb9QsdJBAS1KRwVFRbWtqioEbw1Q
2qR8E9zs+Ibc9/eMkPK/9H3wn3Xvp5o+4/72ahhFzGFp0tu60rvn7f+Rl5GiJ5AG
z08=
=Rk6w
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7690ce2c-5f55-4f97-a295-f9ec12551fbf',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+OVky6//2nzUGLFb9MtSpZKB+VHNqS6cV+zUvLAAdhTcM
nDkKyVpdrd8oseqjxn9+Whx3piIcpdkOPkjy1YugvkxPbk4lap7YYF47ZHp3Asvw
hiUp08jE1xOzX7/YQmMRVr+rjYdQ1MmJTg2bR6eQ74A5mAM21Y9yJ8+DweCd2Usm
yOUo/olPZJ4+HPzJres+MiBihiHs6ncDg+PyaNFF4y/cP41mmX08OWlfMRSaa4Op
l0mKEP0HmDY6ZhJjzr4HtsYOTPO+xpYUBCwaLtzB6OYAuAxej5sQIh0wJt65URAx
jPqTyI6r+ZYz1qZ6EBtXd9Tz9kYMapuWZsjqkdcziB69caevaPg7QuX/SPAMNxCC
gdegU2nBRcBqccNVHn7kYexz9zaulh2ajFvcItGAMU3b/W/eSk1LcyH9SOo4upjD
c6BQA9Mou7b8cJ/kkMJezZzVEdrOApom58x7iMzVKdn4TB4YEVViDwykNnzdwqDT
oxxrEwC3PnUWfTtNgcEZG+M35yWQKKnaTjUM/Dt/D/dE2cwmqrW5iWIMRspUGu0H
qNusBPrKmWpUrCDCCnFaR8hjd8S53IHIk3Y7tmedY3G1t3kbAFVjbWxMYDpbYlF9
T/7Kp0u0WhbYc3+zqcXUysiq+AUyXfEt+R3qZBmQ9n3Z2tJWI1JsrW8F8QjXl13S
TQHe/C5590NMxyGZ9L3CRixdgLgvyxddfk2mLIL81gb+KFGJUWCpbtAdDkEFxkFR
YxQII+P1h7s3gqvjE3/T/eL1gTC7Nn4gHAU30c4D
=jYbg
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '76a1273b-2e16-43ea-af1d-1acfb9524416',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAApi7OM1ipNswYPSRd1MoPwsNpgfNMFveKA9KklsFhpTRN
U3XFe2SWHgW0ExWkWcuXAYuDce0LOKXUcZ1x8bn57QRYrNP12sKY1Xc/bAwNaEHu
aVWS25FeeOWrIoO6Cu6i1zoDsnePhZmwyerDYTvWH7EYrF+4weaARvgB65zPYVEC
UHjw+/IMhXz2p7ISoX9xziD0VCtbN0MtKvh8y0xvuU4yA52mss8xCWu+CgdUu2zU
eDDsM2Lmwjk++YbYvg26EaLPrjY8/7fwp1O8xhgPMuIDdPSzd/mho1TtMyxw0jn3
307rHYCtBQ/3M8gdxb09O7xPb2KJu/uSZKv0UBSJao5MtQH6wkFY8wXWsRGMTG6S
b727Ylfzx+yz/fTqGsjn1MrNkZYa+9tBykRQ183HyZ+K34jyoHtZZE1JH2RlimLh
NLMnRnoEDbkjHQjA+3WQq+dKkTuszp0rPKNRk8PD2u005LJXcRNWnFMsd740TEme
Yz95fsT1nn1O5yrOIR+D6imWoi0N5M83U2wHwwUarXDTlA28qh17CtBRcSNPBikS
gLVk3vgYfLX0EpkdXyh3GxYT8u78cHA5eUw71YNKGh3aQgO+7FHLLqTj5eL/9I5d
HjJBeV9l0UGmt5tsvkxwwIr3oHyyBeueDzl5Tj4i/orGpvlpftC3t99voLcS5kHS
RAFNkZcTpcblg7nBy95XFG9NrawrHIxGEE3+qlqgphmD5FqQaZZEyt2A9Mw9BHIl
0ryavsGw+9/zCUk2MsIRejMoIrt3
=irxq
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7a1657f7-12be-42ac-ab8c-05776538d330',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//cvAXcc2MqkVAzN/LtM2CPLxwcZevz+BMhUgyQ1eRVCmm
EDm/cxAUDzNiqeqqdmNSwZ6lmmQjeNQXhHoStQewDmfrCJAuVL6kRIWMx3AiaoNK
QBNoO17OlJQ5E0MxCxxxV/Dbb8abWerNd5iptNUSBViHS1oUs2cW5LQJpZZd+Qa8
YdXRTT1Dykn1xgiEvbjbqCh2IcxmCh946AMs/ZPbYVenDEZXNQp+lXR4jCMcC0RX
OO15h2pl2c3jtAWc4HF4TWieWK15Y1fMcyxE4oV3wgEFcYK0StKJ5NgjCrI245Z8
vtABc2rxpRmRxYQ7F0logxPpONa8/0jGYeJOd/u55xZ+vG36TVAdcf7SkqqLokHi
ff6CaZR4606v5i4ok5+vIfHvCubT4sFoDEXtSohhFQQE2Rf+AIZDDWBI5Wbc0Wgd
OgcoRaOq+V8Dv17vAcLmBHJNpt/ssbH3ebo5htjG2JRlGOj212V5UFEKVS3j9VDs
5k6HYFtERR1oGhCDAAvAyxFk8hRjksqSaRAMmAeZ/8hfs/RJlPj2fF5KCkVNfgMm
UPotaf7lIqA1SCwie78wjn4+iCpQsOGswt/0kUplusjXrFXlKfIKDAfgD99xlVbo
XTTew5ntcjlPvkxHG3/kTIVrK7Je9RRI10uiQ7ojc7ZcnVyEMq8zb3SNKiCCK17S
QwHkfR5ZTXwdsw/E0C2sxucqiymroNZ6r5P0rAAzl9rY6sqSUBec10nTPM5c5fsT
3flSTaMYEjYniMKW9bYJYBgtTVo=
=bweO
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7f819e57-d79a-4e08-ab69-a6ed8b453183',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//c+YfOl4Y2+B5A/0lix+q6SIUEX5WqTzpB7qBoLxtMxbY
F/1WTlSSQ97hVcSunUBGviPayd5mr6MCaHlGjkz1kuf9t+w+6Rvxz5M+RveAoSrD
7UQ86Beiu4fKF8RP3GGHB6s7EfOYeUnMNjmRso31y6gsJH23BC9eWqeOLLAnIIIg
LzK3koBQMjKwOC9Cg2MYklEMar113YLDCWYGs0Hvd4abz5Vtw5iH7/7qMshZ+6dw
81RWQmClImByrUKchixCBFGHK3+l9zY4v3zt7W3J1/zNXDmjOa7Yv4rlgdKqcMwo
Oj778MiDu/7uMpL/OXSdFj029yItAGSZ0jIu0327OmwoQot426VyOXxiPaAL2dsH
LNwawsWNwOyePl5C5ispD5ywcPIjFEM+5CkLytC6dufqvmzqwT5ZADQwCFjf95HK
tOEKGEmLepLuF5W3GSL3UUcvkBRA2EpADFaK+ddnrp/4oSAprkPm4CH07/0Lw9vY
oWGiZOGn2SX03TLNGabup6O/0u6jUe6bzpTY9uMbuPWCIjD0urDYCA9E4f+ugIiB
23oEbqHh4hZDCN5LbmAtzFcpvmo5/Yj/VnA5+iUuOV7Sc2ARGmWKicBA+tSG/gK3
fNYCe6o3b52LAmU8VLgFgKF2owsPPaXAHWFVKJDiSttfcA9ZrHzNmO+hR3BKTynS
QAGRCy1lHr3BYukyvnTC1Qr6UXjjUOiZD7yavDTB8gDg7pvqsWvVnirTYIzaEcxf
14//JuhnGe+bUlbE20I0WYM=
=oxK5
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '82c13851-d9a7-4184-a812-0a888f2eb2f9',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAApcvqMbq+Jj3GKQx9aqD+v4oSLLbU8duQNacg5I5btUWT
Xi2FnkKvyaJPPPJ29nuw2XqwajvODUwT+mU/5vHfQz1AzZEkbs1zlg13/hraRcHD
cp1xg61/dROwwnG9lccKy0aQfYLC4nlknW+uu/AcFxe+D04+12TlSZjdV+0v0x2m
KtVYTYxMqHEwpYMo0aHihwjKRNPmdVueNsprjiyr5hOY/IqMRsH/YNqoSyRLu9jH
Z4D6nQaDyMMRYtog1WQgf5QXjlOcho4W6ulYf2WTtx84ceLnBowb4+XIAEHbJ2Tb
QFYSNqnKUvHBRx8yoa8Fhu8hvWi4swvXnItwEhVy3zcHh03xbX4yLt4KHy2+4Wnv
4WrkTyrAeEGfNYXvDC5jtHLbLF9sN4AkSeqLPQuzZqhey/t47V0Y+qC2ASbtH+KG
K5aeDWha6yN42Ixf6f5MXAcb5SlcEI41cBU3F/1HaeVDjIxABNB0Suxh1WgJ6nLo
2R1gB4GkkNen4/OPNbS6ETi9mWFBZ7QNycbRZjkcuGC/tqy3/sI6pNm3N4vrHP9I
pOEFxCo2ITRO4BFJnyL1bCaUZrSMjhdIE895DOIF5eo51r3j60a5r5bWrSpuCPAa
BXu9L72bY3OPWL72hsOMjiW1EVObFJX3xiZkzdV/iiTgr3FSf+mh5xLyz/ioVsPS
QAGGdjRWWNvnb/zgQK/7ME/ouMaWmdO9V7yzc2dD1cAePsPeiGkAeEzJxM+zHRyg
3jYMMO8GYuA9haxRbvA5a64=
=6sEj
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '82c24795-d15a-4cd8-a4f1-fe47dc131050',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/V0aHWPxVqRe+7cJadAwCpixW/BE1IJli4fgmsBkyN9/f
1dOq1knjoQVeVKwn6/QxSi6/PwDI9XMwekaF4VoSmqLTIGgVUt1enLpt11KH7YbP
O53EjW+pz+IAGnFwmIs1i3T9szF5ohoetrJlgSzwe8srRUn/KtoSAk4ujWVfaS3b
o3sd6gHy2Nrrodaz+RjvjLd/WJUgkMr106mPr3uSkpPm5YFZf3YvhtNA6zG89dsK
lzuh8g/jKmlj8IgFeuupR4u79guKbD0i2wrr+B59j96iC1owJgthvBLub/oTuKrS
3ngh3W3WZWf+c41A/uaT000y3A8VzPIkhsG9gjUxPdJBAVpipjp6DlL+0YGMQBXw
O8c4+hNGtYH3ANvS9CuIu14nPOL6VhTJ2JtiB6xPlEnz6iB6hWcTrXcrTCPZDcFn
50E=
=zo4S
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '83e919aa-38ab-4c81-acc0-ae8c03576367',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8C8q0UOUcthToxMtUVHoXTZOHw0rzcMmGN/CHE4arVM3+
TK/ZKo3aqPJi4uELvFvVRP4ELXTjdKf9KEUIBISN0kzjpIiebhMja2iLStrvRDDS
bpGxyscp0JXOM1Nyg9Mz3QPx8iicELck0xSGqdOAYkFIy40RpGuvNqJU+g7JGY9G
GLbw3OOlaxIpDR25ydBxDqbw5sPsN+gdAqsAOJtJBsAY7tXi5ASp2O5N0lF9KuOM
OjBUiv2J6p8mT7+ZEAkdZcMmwXGMLGuGUkYt4060Y6sXGkSaUuk+j4w9ul0z118G
46uHnjgOPihzr4feG4Sx5jo2066ANzFDwCx09DNhB1Gl5sF+yNCNXQlGabr2Ajdz
Nk4/L5LTRyWOpBqtrZI3fWNHj1yELmeDd+755678NSGoPmWe3JPFEYcsOc+umFeB
Lzql/2UwJLvBTEas1UxJqeGz5hJ5yI3uM7NrLGPsaRg/Zl+/8lGva05+T7kEqWmW
d7rhACXGMMm/rwTgkEHm1lhwVDZoBFlmt/0Lio3Bm3KNRqEdxwsQxGWutXk4zLRG
hcmYWFL8wlOlNLTvljfMT2Err63+kh8NZGvYGADDkMOD4lqRvYKYgxRYjHzxZTRw
CMnd9u3Mn7ZkrM4Q3P4+C+hCzX9V3eh6VoJuwk7VHvDbplxGadrFa5PTte31v93S
QQHYyb/lY1ATZXRzEvseVyEyQEG8pQ+PfDeTcIn0qn7pxcs4oZMFF1ChcYI6Orzv
N0IjVX7wnua7fcRoy9tTnAQE
=Jxvm
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '87d20b41-53a1-47c1-a8ba-41491dd485f8',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAhljWYyLy64AR3tlZ4WCG7NQQYkDd3H1TCbBLjdW+XndE
htbNPvT673REleCp8HjUlpAP6jMgPVtyTLsP6/FLhXE8hEPdXFp0FHu2P94XGOuX
qPAkIQHIcZlZfSrxIsDuS8BS6MJwWqzS9YkAIIgIJKhh+ItOq7Q/iV94TqrKuMao
EDUfOoPSoGZTgCAaOIjvnDTn1Mdhe30INW6W/opjUIg/d6lOfaqLsmAhbXdN6sIu
isKkPIkfT/C+oPIVloI8MRd2zEVh8q+verL5K+3wclY+6SYexQ/vKCrSjJpggAyX
E+XD9lnpnqVwb/Wn2bNM2OXutdar0sr6gWYQJv1IKYQQevEm9vUDLZxmKPzWgB2L
u/+hMLAX4+wlPhpzQacNNoNJocTHLn3f8mVujc26tu3omisXTLZlhJ9cX120FVhs
aq9ApWrGIxyZQT7OopkgrB7BHAMEefl5pA6AQikDKaVPuhG723zHig4NDB9Gpngd
vXSztex1qSCu++yZzptJH5t0B1MN5ooFkyGa4ffA5l0UT8m3QVEOTVKAYRRAtyU1
wZM1QFEyGiEXTDqPtJIcyXNgC6QtW303ST6wkfXg6EPv73iteo52mOmvOkzF9J7o
sEr/UGiM43rFvGyz+nKn7kHb/ZdY3YusCqz+6FIpDjZK3RVzECrYwPZAOWD2LO3S
TQEO7TbzM6MoGE/wbzonXso+urI+wblrVS8GSM3vC4jjnWJ0KFEH1YAoGOX4sC92
ZU9tLspcmBraxq6h4EBwsmaAKyZFcrnTYOIk2p22
=XEd6
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8a525132-2bf5-4d1e-abe6-4fa8d054cb3f',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAzLg5Y9kICLxg16l9GO9vfD6W4aOfoWJucFGlH+W4gmwG
q3eVUi0B1c4tG3HjAJJBaphC6NPtHtUYbbJfjODNp6kt7/yaVXjMHxfcl7UYcWAf
W+gzG/Yba+qJ8z1/JcnKpSnff6DDYSWsKfORdG4o7qJVZzYlaC9lEpjiBzRsytVI
8VTnNFMd2BvVHnmCUx6DTCRYYD5L9UC+H9siTX6fGX0JoBqOobgTsSERXm0FN7BC
MpZ6oT2CZ8xp0z9Vw+BYcWSUuoFW5E6Zf6F/o15pbPdrJkpdh5bJBSGDFFGElWdd
UXHOX0S1KZdc5jOZxIqUZSClwFIQYPIKnRsjsUSq8tJEAQ5y+IOWixnStmOPWfRo
y1JessG3b8nQPBK2/Xlp5j1ACeFUF3zbtG57y1VGaM6SvYX4GRlHsj1EeclxL8td
5qpODzo=
=M7tI
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8ad54b0a-f109-42e9-aedc-401c2a9ed361',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//T3WX5IXRG9WGyCJsG9aYAi+RtE6D1Z0Fvs/D5OFeeHH8
z/YqnRvIzPCizuC5HGDab8qzOLQs3ligpb+dxT8c6zRewlMTkodPADGDtjo4VZR6
ZNEJyVzpUpqAw3yebI7KydzsMxgjk1nla9og4p4zhhyMqhsqYtS90NMsBjVyd2h5
5NtX2QDmxDbT9x+QPT90JbphKPGc2bq2Y9DojckGWfM4tqApcewl6I8CPaSgvDuj
u7EsyPWycZfDjREQC6kTVfAXnpZiGLhPNlo4/qitk/87ZruMCNpIx+dupvBqgKZK
WTYu0edl8dPahCgBDlObkaoj5YBJEvy2i6X3QC0H7HHoTOvNp/b7CXsPTgDkr17b
BUWd1fgLOUsgd5PcfkbvD3l1g8Q1GqBFA+TdRtrdMAD/4FpvgNlJkWBgBTgdbqYZ
dGgfp1EY7yvA6KugJRaMqPSxTx5pEM5lZ/Esfz2a5GM2RAhf6jRHDijl0k8bgwi0
sRw5HBd6yre4q0CDWFFC+eEf4nbkwR2LXdBTvqZ4NpKWO+JHkxcI2zCrbqBKs1u+
tAJFC/5CyjUhELvRH6I9CmAuaqyIGMQNsgLBzLMwMfLLAaS6J1AsUddZJ6yK+tkd
jEc4uP2ugeS5USaQuXLnAgYS9zMTWGRBK0TaV77cmvqUBFFkBfiLEwUMlpRUUh7S
QQGOzMGm+m/usx8Lzfa59nIGzQb6mfKrSJrVVnbAKfa/+e2ARje51barn1CGnZqd
LqmjH6gb8D+h/1mxX5A5yp2r
=DxFP
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b22a83c-feab-4d95-a863-74468a6866ce',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAnISNwOA9NNQyDLM03dXUqpx7ELBLKslSz4M14NFOHi4t
eV7ExeJPLhFc9CNHk19Yw07O9FtuFG9IrxBiBaeS2u3jGoaZVNj/6OSEpIzbBaDN
q5m2G2QwzH92/u2D0z0qAT6c6PXDqYqLBrkqdngExrEnDWwcozKdmkLTzpn7ckoa
KnHEe5GZ2Y7UE9RxHyXZ3QRu9ol8zT5BQK3jK0ibvkTzohOzT6GWxleUPEPjo7XT
ppRj82JeRxX2vsnOd0UBMg/L+b1h5y7OtnEjQTI1tSplPTWnhyaNJkk5NdEXaUz+
FAqtUi/GhnFXVmzq93gx4e/QlMvS2CSFwDEUDw7g7PsoA/lBa36UJL9ACiuIx5XL
kgsrsMRCXt6vNPqpgNH6t/Z8BuJK4XQ2nCiuLSCZCyqDHml9bbbLIafvIY8hAaIA
+3L1zCCnpqxC64DkK6qJIbdtNJ+ixhEuvsFQT7loO2hlN5lW2ByW9CEQcXx7XS6j
eY/paXb/nF+Mm45zMZxHGVTJbt/SEKoqrjLw0t3siNc1+6L79lnab8r2R8TqL5iE
M7in9Djj9S2drEeIFBjSQ+HPs/ZDEUkj7TH12KZKlEgY/GWrpd+NFeN6+lfeq3Bb
yT3hsJwFKwAgU2yXFcDq0UY4ApDvGARoxQ4sqQuRSSIZnwTwxpuTUZ+b9rpsSFLS
RQEl+IPr/O5PFzsqXlVsG8yQEaCmHwDdfJREMguQRWHTaKOP6QEfUWTq72vy+tUE
MKb9HTF7LWUTLDg6chHOkhHgdlozjQ==
=oiAm
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8bf06fd2-3661-4373-a045-43d4c161813f',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//eahaRD7unvlpW3ZL71SYOLvh25/4XQ3sq2DfizcXmMHe
7+aOhDe/WLicVokMM2F/kJPSXGXipnJB/WPcYe2eYtW0XHsNa+SBSIwa4WgYcLdF
+C/JoiHMoLPkXnCv1biHqrlR4H7GbUvtzFrCpHxdxiQ5im87MyOV6X1BKIsrcuf9
7o1rTKCXgJWGlfCPqCBx8T0lKhu1FlT7B/D5zaDtU4NwO+UW/tIbDzX7DqwFE5gp
IVsIStNQzXUnDRMpEbQWuHFw5lFdnXPIuvQcVxVhU+vCifREJY6xu/RzUGn5Gt9x
5vnM6k9fdm28UcMVcryTxThMmEI7qnHlDUaAQhVn6x3obfZZLNiqcVhHf7ipa2xc
hRdBXr42w3am8t2JDRXW8JP8kiW9W3XT+w3V12D3Q+wM2ImyjLqBDx03ppEuui7K
jpN6O6QdiyaC/eN0dlJzuUjbHBAKztYRcJKkz7n2UaC/TKV3PCsozxBinkD12Npi
uBz9efW0UG0AsPmXYDMC5PBjUlbnv3UFPH9R33i0kUYh6dhJiP0thVP8Pf/pRICc
ZbwNfdZbTJT8yiVDYucmssHysRnpE7mFKJV9+vzeAzP7MVlhtYy0GpU+3xkw47o4
x6q6cAqzacUbf9wI4G3P95SUtHB2EMtSERqXLEVBN03ufjEcWhUAljPt5Fv4fFrS
QgFBEYRH85I6E7K9WX/i4n0e0MSJgqRei7h73NSZqiT2lJfx3UWAR5ywQGUTAsuK
57Z96g8APwH78CxsO+DBdz9Q0w==
=Cxkq
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '947f6d07-2bd6-4080-ab6e-d2aa0391114c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAnzSV7mBDRwudhmG6pU464zLEhLdBEkinOZxd14iH6BX/
cxr6aNsOxBXAeoyvfJFTjkqD0mp7/ycGF9JVuC4IpcGiE3OxiuaZq6TWUpymibcM
/BELKurqWs9Uc2sME/IEozoLOQjaOpS4h+PtkJ3OMBPvrshjPvdb5pDus9asrdIW
nKpqQQUqa/KWVeVWIaTrT5+JhWl3QxjIE4iC//9UsMhVnqA1E0kgmKljwoRRsm6z
Fr2v+ZXaYXPLI9xj1nS4Dzx2ni/qomr8alAz56jxwz2d05cKTi76WY1o22M9/o7n
6/ZnVRuejFYcKP9gthTUPYBm3US++Nhn9y7fSdo906667e8mLZD1RXB+VpJTxUBF
I2TxNK9gs6XTHoOunkzx9QeaNwyhmApDgqxbasecmplAsFYC0oMH+Bk3NruldJdC
tby/1Gz3nQtqUNzixa4IPRfGaQ5G2XtsluibXX/MRnYvv5XL08owWBFUcaNjeLe7
ihFi1AdthKDbEArSYCdwuXKaxef2HclLFHGRtmTVt3nLrwzCFC7IUlJBam9X8RMT
irjj5nIGpHWlN4KQwsA2L+QaBwsUKeJc4E54FVYZhTmWx+CWbW6eKOcOEg/c/Z3L
HswUXKLsOsLukEeyyxLIrkaADvuQkmSA6siDdh20gh+DUjE7N3UtgXhlDGGAVCDS
QQGnE4qwXyMUDGOJpjOve1JuzCWpYJsfclODo64e4KXtJ/SGY+YYFNuZHfMfpV8M
utRwjS3ugjRHduS+CsPEmbzY
=tR2L
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '94eea80d-6503-4a87-af38-ccaf07716dac',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9HnAiqSgpwB5Gu6dLG+zaIN9CPYX7zdclqPbYV5M84egv
UAwvfW3/JLP0wsr0b34NrmCAuAj6MEwi6FJA2dCvxToPHQ9//a6B+SRZ0ldr+7wS
Pe8sk2YrNE6AM0RGSsJIemGxu77DbVRWkIbZJ7hBMcPhyoKIvbat9iNmhM9ZTud3
owMtnCMbkJ+gUxwJGZysju1GaG+u/1EVCNQto75oIllti0knGYqPkkT2/SIOQ0Qe
GLSGb6WQl0XgivTigBem9RQxCPK0km5m/rWcErJq0sjA9mIyq481tF+6XlouLNHB
2IQy28ublQc0Hw+U7RwkFPpj/ROs2nIXJhgwiLD3wSwIHYh3obe/gK9GbpaB5awt
F3At9sC9lytJN17RbQ0BlMsKs9VtSgqxmchxdO/WK0AzaHE7OusjROzrn2Do9JKc
b0VWTfUseSA9KSGh4vMMpNsye6rMS/Dpv62+tJCNQks9eDp08zbw+LpNbyZtDc4M
y2agmgfivYOUk+04GXRPVjNpDuWVYnHUDTHyZguAnbwxFMPeGytnAJQk4ubwBq5f
kTB7ZCnRqtLanjH3YZZz3h9lWwWwe26L82wb3TPhumIsSMhwt3wgRHgYug80VRIu
hqRyVMUglnPHh2xlIvjoTebCt1BnCmq//LIU4J0nLYSs++CIOc9PN53zN7d4slnS
RAEefuqYNq9apwqtWFGP+9w5kgRK7y1NwJ90yPDe9UTF/4gtcMLrO1uisdVJEZ6C
qfwgQ1TaVFPoZR7FX7tz6Xk/gQHF
=qdNR
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '973507a4-5e9f-4327-a7b7-c1ce0cd67a94',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//YQJujDBvlwIVMq2/7sS51FVx01cNy5snqHjrJSneoaRF
ENxHWhjH8a7+mUZXpgy4PzKGrHiz3raReRhnpJPkFmyZjHhakMMuCVrhmRdHCVVQ
rxLTE+8YmJehUxL+yr+J8iai0hNDYtp/ubhSqDGZG2Oq7HUMo2f8PPN6X3gEpikR
dJzKWV6XkHT7XxMAmASwoW/n3txkSkb+e9TaJ1qpIwMCDZ6Xep65lFQ/Ufp/XZ8m
sMhP0WqnACnHqH0jlBh3VeNGKAiyEbVbm3j0w6kP/ZS6ZM2YQC1GS0BCJbRMqwzX
AlyVRrX+RXgvC1kJRND4/WlGyS8hIEKwz7EA78FABpjg7BvDXTdOwkf4xcyFnkT4
/oD/LaOdODc4NyiINS94dSCSOMEtO8xv7fghsrWlCieumgCtfC55a6fe2rzHR6zV
dmh9yKnq22EXb7cSviVN0u23ZY0lsTjxOLq0QmRa8diJ+z9aLTPk5AfoimGT8On/
O+eRgB+JI2gZOIz7ZwhrdIvzVGr/jQqXwdP9JVsFHNdFMoTyZ7p5Lw3Vvm7dvmJ8
qjRvkAxt63nxPWUuAxtDX2uURHLc3TOTP7PW+I9jjkoPZ/dlQ5QrreUU9BxnGrIX
SUKHLM70zj/RyTBUqM5AlFi11uYVBUXlhZgDuvkFEDFEBMF1u25IuV6o2DFL9yjS
QwFj9mRhUuu4sk+XPdbllcfICj/VWREYxEY6rovHdlUO1hidX+jxRQZBru3L3mEH
Qf8uax+4EKf6QcKgDe38RwMfvgA=
=bfXH
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9898ac8f-2126-4c9a-a22d-5dd2b58d4bcb',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+M957vS+lWyDXtCanLdMg282INolv6pwXVU06TIu3smAk
L5kHe3zja1bSTfOwYzrxNbl3Wv5NJUuEaViNPWv7HwTiqUunG9z9QLTZHwi/wiez
8P2pGhCuIP5PNhEuueIgT1Yv1srUxbfQkkCJGzZEGKJblczfFiEidB/xvVerBP6y
HVtser+HzNTRlKd0nLN9GmfvEBOG7oSLqf/uLH+GmQq54aysirxoSeDB3GnDFvI8
ct4HLz/l55BnJNzWmKuKDIFDf9Aub4DciNl7xc8Wy/AHeXUwlNQ7SG0f4uro2uyF
syOmTBcvNIAZBmTClLIL0R3xDRWZOX5LgXJZZz/NRWaq+nOSKkJ+FaAeAdUwMRZ+
Z4p/vkUDD1W33juSUKN8kWjw5U3ngvifAkeQxMq/49thkxspclWTVChYwjIpmQDO
KvDEXnNlwxUEm/bMtvPzxTZbEkP4mX53F5WEgbcAYJtMypecBkuAzwnySsmPip5k
8kE3PRYr4sZVUFUFWfyrh2JrW8eiqcA/8qXCrEixeFmyz+u6ZvYgDGAC/WIX76yR
0qPWGyUI47ANqx2nLkH0jdKjGwg4q+vbk4CRugr/Op2zNogmlcpY4hn/KwMhY10/
r1b56nwBTjw4a7HGbw2LT5g2FfkBORoh/Xc54r06nReVjT3Xo4CXeoaFr/05UR/S
QAEvy74++uptXM+dsSS1aZ1eh8OTUKQzH8rtp8OJxuNPBtsKrdqWUF6H9Ce2SVC5
o0/a94zfhTPwpjMNfFWZVtw=
=fKfj
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '990e4586-1d14-406e-adf6-84bf40103b0f',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9EKPN0q3fDl1x2XAM+x6qgX0267n7OGV28wJ3g5ZoaIUJ
V3uMeZ3/L0OKUc/Cc4cj3Dla8akqLcCUX1ZzswWw7KPIejlPapFrAUIWYnFUNl+i
iZ5Wx6X0MEkM4tna+N2PyoinMdIJ3LEaBGOCYkC92koyOMuGzS8w2S/Wpsb0e8mZ
42N/PSGuLJvLlOrfqriggS9xynZ2KZYUKf4Y3kwj3XgV/Yq8UU1cd/p7Y9rTOiei
2w8zqBJnFatcj1gxjpWKv0wiA75mDJUvTNQzRFRkkK5Vk/wC5oWl/bdWopmbeQGz
9z+O5091zXSODNFvaooIxxOp+SfL4byOYRdoRGvWFPCFH21B66srxs4WWvyvEZE2
W3Ia9+pe7BJGbQ4cnlJyaQlDTAbsEYizUtjL+FhOft0uS3/74so5Ol/FmHwzqBa2
c1jftr3NLzezvSA4q/pN8p8QLjd/LXpp0VQpzXRYVSIWRbG8i2VkNB8r5OnCrpCM
MKJPLhyU9U/Qjrwwoum+UFk7MDnMa0Xbfd/3muGw/UfG0dFigHd2iUoJb9ZT5D5Z
Vdd8yvY9n2/rHtjejoyXmIc6aFqXjpasgPig0TjtjMYhxucppKGSWDxtyD9uB58W
V+Z+k/eRDyDJshjDj27kjfuIUWv3hmk+zE8TexJbvAECvpVy9Tv7ZvcYBv+jD/jS
RAFJw4t6PqUZiB88ooB6lu2QnB+GaN1epyWpocfluYyXNYyDDxitiHCFce9je+w+
hslaaOG2Po76wjwQdk4E/67BTlIK
=Lzcm
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9926dd78-e67f-4737-a51d-2b57081b02a4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//UOvzCe42mMucZt5+3GyJ31ROycjlAJnsRHMO3myEe9kH
KvW0tPVo6bVjZb7MBZjOh+NvKLcJyR83PTuOsJlufLBddia6cDxRp+/5BbEmSnnH
hpR72NjXZR08OEmQtJPjOO7IJdBng/h23QAlqQZjT3zCfI4z+gOsSaQ418gTELZB
6Uv1C/7hWKEIjDVKGkiGgnZrecnQyeaRXWx1Y846Sl2TIRl/+A9C3vsAQ3Jrv9T6
fuiPMm6UGqBolz82hGbVs1hhkqwysXDoYHuBAO51wkLTBvECFEbp8uGaf5/vh5pK
xQ5HyNaKft3hBCWE4X/zD7V7JH6lflbYu1upihoz5BEh5qzPjUwNiR4YlDty35x3
kR9foquwx1GdEqKJBtGWA5HoZOCrc8HtJcHbH8tdcRLijkwzQPJBzZ/ZtIkA7lsa
LcEixnKksSUr1ybaKATANaXABKOJGFPzyjJ68T3Dun5ME9jrjG1L5Msz0a0Hb9ol
2DDcVgYhv9tYb0pGYXmhFWvTgCYIvIDYn3FLIwogufa0cwRXAavXU0pNaQ0pE3sx
18XsvfBH4zgL3SINXoY454LxWi57QXuSYQv1WZmDkbumEo7IvvhWtXfSxqDatWpn
2/Khc6GIIP48KcUOhywDXKui/HKwAN0YzcEWhThXHXurfMXlsrV9gOnKRo9me9LS
PgGKNp2Z2cq5L1TdzuGKR7Qbu2UEKtsrV6HK19xOBU5OAr2jI88hIxFLmpRLmGoQ
YU6DsQwNJ5kEItXPGSPI
=3GPV
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '993346d2-402f-4faa-aff1-1b20686edc02',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAkyOx9Xl8EpnuuMW+KIx7oMNjSuOAn42SBYOqbecW+yxw
eHvu51SQcXIR0kJQ4sDGdLhil/Tn9AFQm6epCNjzM6LqEeYYxMnI69mqKEYZJDa5
Vf2ObpGcOdT9h6QquikcROA1S9VnKBrXzIb4PGTqlo6z/EkjS7Z/17CVK1G9bYOs
TghQVjNI0UHIDX/ZeMaPbW4ZnX2mf4SYy2Qo6R3CNV3XzRAU95pA8AH/zHzqgzgc
NnbSHmLOtkquWw32Lj6o2GFCEPoho9lz0jO2/Q4x/xX8BPxV1M25QMc7qXLqAqqM
V066nihCPLDVp0KaRpiM8fR4xf1vFkqshr/if+PsMkK8t+v8yNYCk6zcsQBwjdGr
XAMaxFCeq3FT6oXrDMH69fcAm/zTuSIZm8ByCzloOqy1p8m4wWVFwA8cM4U0q+nE
6YoIzALU01xvOMTgZ79tj3jzlCJk9Od1x00vdduNDkI01Q1QjIKE8NwLHEPKW4Q0
K7gUnJ40usPZNsu8gH5gzJAZMJ2xL/2CwdPNPKuUEtfOWWCAG1W5WNJNfdAWlbwe
EhHRnTP2QKEoHHZooErfBRBSOnnUsE3BRIb0e4mx5D8gGAOGmP932PbkAe5SDwex
f3e+xCP5OpGVi/bxZFEBymGccHrwbbl08uCtQ0BeONJFdQ+LPeIcCXGEOtFktoLS
QAHO+D+wwbxYbx37r3B2he1MipFJnFOCSuJdPLZad/RE3cTZ09/ZmTW135R66ZFk
ak5xjqxXVT2XaniHUcV1jaQ=
=atMX
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9978dfa5-7b09-42f5-a363-ec15a4833d4b',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAmD66B1rd6s9ZoK8eh4HFJyQJ/zCViOPHISa7m7Gihv28
gTw4IqqvT5TMiECTVjIXnPflbGcfn0BBQ2Nw7KkbiK3M+g07Htyaq8hCzZIOHpy3
yEaHB5CAhbSgCPvrPx0yPlr4rAFqdw9ILN3as8V9iGPuazVtRP9QKsDVmHZ9r8w9
4OWbv2J4NBahA/yFUUCu/oMgxRVyLN9BM0fyYiHR7k5LtYWHRxtxzVfIPaSsrPve
ii46uVzxFYkCc24PGGToqKJIRI3xY2CZZ7ekCE2VNyi3KY8sOmgE+QGoBsmCPnq3
jSjr+tTdrMKH9J/BzoITR85ppf7ep9GPItbbka/kGlp4+fdh+fxCBZhylJqFssfY
JPXVrbQu2zGeCdxnndiP+qUTAkE8R5jS0Y4F8cmJHO9ScJ9RH1q/SCGrYcn3rWEQ
ItdXNH+eFmhDQR3Ru/NtFzJTKsFKONptgG6G0pv6j7Xf0yY7uZ5TvaPzqW0HYBbt
RVwdAgBSbszZ7SmPse49yFADRnD5ILlB7UbKnIMun3wlcAqDjkN95mZytmpQscms
lB1HUXJjfQfKsjhJtowq/q+zTFCWtFpnKxyrxQpmbKVdsSRnPvwevD3eEXZgOt6X
jfegcq642hKAubCagDhkONWZxm0MamCniPGuo/dbjjDWspE1PbfF1/h4qWsl7AXS
QgFA0U0w59Ojl5a0UU5py/ZgJqsd80QlDRqjTg7zJhCYhwnlQoKgxv5NaFDf9xUt
rsi8nTHA2+uNZdAh9LUdaXfDbg==
=iQnn
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9c10e2f3-7575-4dc1-afcf-e0b9a221a64d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/T7EXhFjutcbo6JBXx0C5Jo3kzgVn8k3l9wNo1vkuoM0O
cmz7ctR8bQaeScQ7UaZwqcfpjkH5hRh3ms//L4Qa6YElc7InPvzkaAeB/KGpoftT
e0WaUkhXlDiAmU6EvBlIYqEfGFGZvzRxK/pCsbFuzMbjoMDfIMJbhMFahBt0cCkU
hgnTL/eAHbQvRUFvdZuan5gFXpJJ4Goy2AMs8frkFMpZhu8vhGR879nwG2Hu0Cq5
3sasM9HrCeGOMq9EqL7GdJhgfZubekyDpXOfUPnMMIQMmTBbTIGdUbu/sOxK0U+i
cXb1QzNl7PfzEV64SAldbcYTlEKC0vDJpsjzby9bE9I+AXDCsnWz4Uwfs73Emlhh
pLo+9d4lo8GGH/wUOxiKunwHMr1gY97+NAuc6XEhZsnyQbCAVznb6DRxYPekXM0=
=8ZM9
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9f8ba3b9-e72b-4e56-ad5c-cb461b38c3c3',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//SMjLAlu4boX0Dt9myac78ZujIIUuUtY85Ch1yttU2rCH
by2TPkT9zr5VpAxhvj/muhFb6tZemBrGpd1Ptzq4I+BO6k4i/vJ2l5IxJTqKxeoh
1DcC7KYPSx+0zJI0MoizzOkvilFYdOVR7kESnBdFW8SJZDK/wLTpm23dSc8NkVe8
NQ3LXLlYrJoy0XIXInrZ9GRpjJeKA1nMM97X98K0WZH11NXRTj+S6xCXUFUtaMiD
01bVZdi1JXepituzJg3bpc66M0HVBPOQO6ZannHf8gfs0bGQE8COFXpgRZG9BLVH
70sN2GJa3vQMGkKw3eeqJjdt7bFXjD5vTZXA1/8qYLhGjo96Uo6IvNIbgqF0EDIf
40m8w7QGKmJ8q0Zj84AwD1o2M3YhoxeSh+Fe9+qttdfhgXOq6LiPr2UQJIxQEMPi
UXukDK1rkb4iseOg4LImqu+9wcGl6NmcfRvaBzC1JTqFOkr+nBsTsHERpWsvnGpA
w5OyH9lZvviiNu9NBFUzU8UIzysk36riXOgBnl3p79axTZ5zNByWV7Zjy5gyyPly
2oEyCD2Pe9FkUIMx+rECtE4oM58IRK6hjMMTsyCRIyUA+WH+RkNBMZ5GI31BpP13
cB3G66NvTLdDphRtV6ZSCt34IGuVdID4pwQfH4zpGN8RjNLs1U9vaTPgHP1oUYPS
PgFuQXqdMG6CBuHdrf5fHzyF+ukbgFEtwalsyZDX3LjHgagM0rDlfqaWoBtuJWMc
+Jt9wv8Tf9ht6zptLn93
=/fHb
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9fbce053-7eca-408e-a6fe-6d02e47f65f6',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9FuhBXrFKyvtvaKihqdSglkzVjyV614LIGMKjeLRXy+AM
xk3XVm9VPakCyaO3MhPdlqaQt3fPYxJh0ksSiA9lCnSiZf7lokeUfdNOZtNqjaM/
LnXTf4HndJMlxGDNPZKuCVfvzNXlE3NM42uvCpDicj04JEWGM8Lu3aoY5Tjlo+zD
EnkWaz3TXXs1EQWgXKiPsMOfCa3Cb2NB4RWFxKzjaxoYKX2wYUKZDTx+pbDt+IoU
4MbvCWhYwPZBmVbJ4vSGlMgDDAZi+LG3BpaE0wC4UvyBMuUsf/mE4xdWIivsis7L
c97tUtIP3L2fjZQAuJX/VYjSGwytjbFFPgjqoTOETsGvTWyqht1QEdoG5o+aAoty
fq0XRLaxaahLRcjRiGamgaUSZBj1UPo7rHaSTN7VniZl5CiEiNq2DrCpeTdLUdpI
hE/hboVSPMjFBWsi+8jNQNV8aBs9YQQBmN9MQvxPwuso6M3FK8Ltoxw2s4Qu4BV0
fPDAHUe90yDSZZFOKhxPAnmH4So5UOfM/HfgOJf6QT/2+R2T6cn0WSkJI/BcWlUa
o9e85JYPUDDalKb7yTGtfg6WZwnlrX9sWFz53FdwmO3zHnnsZ6MVbBQ6vY/axgvi
SGghix8HCvbher2iW/iRY+S14ia9jRH8sYERTIc6r0iEKjHPGcyN0BFbyOVv+VfS
QwH0VeBDIYalk7265R5v//EnwR6ZUcmRyK1iFe8C/VkVi/aDpsTxoiqewbdHUMuc
uimo8t3n9qKJ7sRD0siNebUTYyo=
=cqFi
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a266a6c3-bb95-436d-a669-52ea8f8ddb86',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//bFToc7THs3lZaqsh83Y5fOOh2dfT78rNlX3tqCP45r0e
R2+OwKwnnojhwMWTvV5x8Wz+DKcz2wVZcxsFVgdA3NabXCIalVyD2vGFP+rn4IFe
HNZvASWqqeeV78zPGRYWwYqh0DaOpbmd583FvFeRe5soyOw9AqtH+nYqXWOLBZdc
vxvc5NwUi/a0WwUVQ+roNgqb5PKwzK6Lrlo9H28hxEwMlQP9lLOVGz8TOIBF2tHP
vYFNUzUqd104Dja86RzH0DgboM0omDQmW6Utti9GADPcXHxCTEMzAt2/mwR9rn4C
qgXgP/Fo3YW2iOV/QM7WAsb3dBSDhABzk99GO2Jw1o+/Fz8qP+IThKVtiXZ7vCx6
YZzwiFYik2ldESbl5x6sAuwxgkx7nWn1vq39YfunDHge+UkRG0i6baLqFjYyPwo+
43YrFD+X8rK6upD5YvCZqTBnc7kLdCN9twNR/3jy56C7tI6EbjpNr5ix/kKggg+U
XVcxIAwkr304Nk96vcfhbSNw1Th0jhqRrPH+cBRfBDdkY7tezdcrX26I4XWrnVEV
T4cdHvhqxZZyRhKfkLK7oymzO8yiQZi6BwuTLBzhAveZ19qOaOG7TiPUplkgypzL
2xxsBwZD4T7ULgI1qjaj0bXj7QCIvQRyM4888SixCB8mZzucTj90AjXG7EiLnDjS
QQHxEn4MSpyO9wtkNorrfqeJ/V0Y8rvN46XLXF/adIVhc5rUTOKcppY6NkTunr/Q
dTQV6WI2lsAywKWf32+eCGvI
=W+ma
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a31235c7-5b80-4842-a05c-c54b9b4f3709',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAjiB1HwjhbMAJIo1Y32dW7zlHFc9hKVcf1N4ap+DeNBi7
WBVBubnc1TpOIQGtYB75PalenzOhtnQ4NxjdR7Idr1S5kXss3BE8S7pAJSRBgQ7M
lqQh+bnwCFVYCN27fkwv5VOKGUkMeRfSYDdEl2q6Qs82KDIlC3lgfjKaBLS9U45L
48RSZ1TzTYVv+X60Zbl4L/0TAbEDWvrgN+e4gZtRJzJj3IHXS2leaUxxIbIOsfqG
cLb7VibSDcQrOiI6jtTtng+aEmO7VX5yRGv8rTPR1lh8omu69LDFIcUusaOT2hhQ
V5bAUVLa2CqeFX/bKXiNIZTr6tW+dQmnKKhvhcqG0U0w2FRXpHmYRt0ppYbl0Kh1
4p+kBpIi9VbayfUM2c0p597G5vvUHlcQzd4nx+racKlCBdTJNKGc/nOR4jA7o5Ai
qevkCuPwOXa6I2gZJBO4kuu6nFgPgYIxMs6IvYFd3IgVAaXSpZZmWbfZvvhaL8MO
s0WSphzRLlQ4nAARKfOX3E6eavZeYR3LqDTizdIherVEJ/xsmQsDZFy8bMqZkXcI
+Pjth9+v+WniAiBL+/EyWukg9dMYv60WZGL5Ajhv5fHV491LBBvUIwDLJ39GZPNq
JuSK1iunoetd1/eIBD24SwR3NPIAUwX78kGhKs/C8D8R13cpJOpKoK7RHoC3c+rS
QAEmejg9domPhDQXZF37aPafhpj7/Y9CYi4ed0o3SIunCz2cWchVnngMIf43xrAU
XWsyBGHUvfs7G0kNtqzErCg=
=p8Er
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aa30800a-877c-4dab-a9c4-aa8ecbab3417',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf5ASM0JgEKK70jVhXeXtigC0gZjVNEAE0kFcmEJnwsaY29
i98vIeXsBc+u/Hfs89piyxkFi9iJKKoNWCR8gkon7HxBlvVn+b4froIGvdiH2OxP
6S1nHiNCeuohT7lcH44ipR40k7kPcoW4tY5qFpPka6UL3DzTY2dc1tp1k21Uyhv/
FoyCXoQmztE1Iqiac7oJIj2e8OmIf0l91Q0mTzwlf8dOnjTaoyKEfbIkP0hpM57T
NzFohEBJRPnoT+FYtHv3lBiviwSgQJOB1hgeKjZPSyhwnIGhctXbUiQ3FQhRiRe5
MBATkY8vJGd6cuz/4uRtVRaos/Twd1MJUOGbKFqgHdJCAROpaSh5FXf9wUVQnMjp
hJaOULxP1lztdc+K2OLsX6wg7QtNB86dEhWroqLpI4z9S4oEnKo3dMl8aqGT3c2g
ttd3
=hyUe
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ab41a2ef-a706-4b77-ae6e-394f92fbc8e1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Rcc+esasutTtRwry1pVKIMi9hSr+TG+wD9h9EMHK49K7
hMATUJ/6t2yD0RPT4uAa4q/t4NCepx0QbLxcw1EvUBaC3B0xghE+/biMuMbLhIa2
tPZmuEi9kT3yeH4oD2nacWfyIpF1TIQaJD6SQangbB0pBE+yt5l9RZtszp2oZo/D
jpKRL841ZP4nGwZAkERozjCu34qql0KFqilaoqPHs7fBhhyUR/v2WuYAIobBAqLU
KOPqx+vlk44dFUoHSRv7uR6AYOiVSJyFs+mVHu9cu0tKMy/0ZjA+xURiVu25j1Hh
JkF60PqaJgEePUya+9/KE1wuih6CMIXhOoRZQ8VLNUm4dZCssNBGqjuuiModbTdf
Mq5Hjen/72rhkI8ShGIOu4wz9t2hyUf9kpS//A8OmDTVetbgLLGi3X7izA9W1oHy
OaIwU70M/FrrSDhKmwNX2HUyxPsXiCnSAa8TiVkK6AvOJJNbhENkE33truJFIwUK
19UDlwVJddNLAy9ooyhJR5ytegNPhsCxqpliwi4koKHLkyFU3XSe6TeidopdG4ii
8DRVgLOdrFR7POkShZRlf82yi3813npGXjyRbH7AGI7YXlBPWNRoGdJC5bnpu9or
oe1/s/3G5+jhbKI4ai6wK1XpNQuV9ZH4Iu4WsFs+VdDWlAlSis5GfLhA575SE5TS
QQElK1aFfIQ3DfIaIqLpe4lCC/ZbqVuTGdjjjnrMHIWevI3q25Om8F0+h4eV0uJb
RK4AnazqwWoAFzO++af8nLXc
=pxN9
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ad08f61a-63f0-407f-a6d4-543c93b41044',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//SczFY0mELQ9dJ6tufRk/VAH4TUisYJXbP0rYlXWYQZR0
fHEJUzAKRirX1WZKLIH0QT3bKTtNhyDJf2XyZ3SRJ3VmN2jONUTZC91oF9jwZRsx
tt1vH0md0Xj/6fIswT4qeIgNnqJ2E6y1slDWh9i7dVH6TXSP6IKJGGGQr8PZPvmq
E5/QExj8olCwIicfMIgZVNVQIldGb27Jl1FZCRw8zZs5CBngy308yhZJ/ad9n+Km
1AB3MnOQR9E0Gy+iOLTMIRRq0wbLAHDGnvqNbLCpyF8hJlwaDKj5TTgXg00fG00r
Ji603gt1Q12wNab6enjIsKfkyYaNuUg+e8bs4UJrytKlJCCvNVXlVy+iDc7QG8gy
/ce7O3diilVG0qOBIDSvvhbNxto4XyGoy9eE/3JQrUbjO+CzKQT75LEpuhjrb5At
xFdGVkSKxcebhzFJNZbUjZ/MfFK9F3CnL/+SQmrDbiRYrpxNBK9yV4Yty5sSKVRC
Ear/iFNx4DTeKHoPC14FBKF1dNzAvUmXZb+AndQ2y18Sit07BfIvScWL/I2m1Q2x
2ABQEAGngmwfpt6v+ua8yN+dbcajZs6tPcOJ4+I1QLUexHiGkQ6NEw6tHvcae3pG
eWtLGnemL/qSz0rJxHnoFxRw/zkSMpxYycqde0/2uPvgGqnRDOeloZFp+4FeBeHS
QwG+E4L+EeR8+0R9A9KsCmrTHGqmr8n/Cy08+AzcwT/9Qj9upkJ2y9Ah9qtCJ2j+
8xIfxILt30GCKzr9br7UA/WE8Cc=
=MW5N
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'adc0a75c-c240-41ae-a4ea-9bf44ce62c33',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//b35kSIB1Zz8pyYSEKlNWjbt4gvQtzahwHZstrJYKKUEO
/ZXSYptD04I+qOCpsC3AinmWcUfL2RIqF6FbNVeHHCk1pAF4ObTw3kz3wcr4ls09
EsKjFLwiP/bCeK5tWGyEV5g0XRaFBZ5T7vGOLBCcPZAX1/leYfownC/mo6HoWizl
ZrGLYvRDBGAQgx5N7HFn1+/+LS5Tv8xxyZq40HK3ILO6a5UnpY5wTEW0t7z4zaPR
T/BiS/4nh8uMaWPswRghvKlMtbOyf7Gefs/7EtEZ+1rVqfWDXlDeQML3DyBfBwlR
MPRamoTQcwOhYCL2oR1KQP6tZROJmiAifkmI7PINl6R4nnY/gbl7oarFi+STSsAY
8AEodnOysOlGBFp9DUtAdK6XXEE4LE9j7vZNaPIjsr84eaeTYhB85ykvlyZgUVBV
3hFaVLCorRRNZaFyAXZl7KQr9QKU5aT5V984k2iH9vFp5kbs+4Oo5m0uwwDtiFMc
AiQOOC+9J36y0Iknx7fB/+4LqWN+2g3TnknaD9kfbQEeUpaqe5162UC5cqydWobV
Xm10kFai64PNUIzymIUKDA8+0KlAlxpIa8hN1Ls9txfG9EhiBLpdTdOVfOdiIGsK
m4Z3Z769GNKbJey5aoeK8PBf61odwBAfOcn6LaJq24ztL9ZX0hPG4MU+N7dp3ujS
PwGbzKpQS2tSWpMSX/F+z7k5eaM7w9cvfHdUMmCy5CeP3lXduZa8IyfgjYo3v6S+
vf7Zb/CQ6UVBXSuXweTRVA==
=EpcD
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b132fab9-6d74-4264-ab44-db59dd20e792',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//WHANoIEUd2vnregdj7LTWJ253bz9QsSSVQUCedV1HbW3
qfhsqTT40wM3mYervGOrbNC+6P9mpLpeDCpeL8A1sD/UpioE4PrraKsSNYwzG9KI
wzTQ3W/uqp58khDmhz9qU7REfNSoz5aGtQqeDOGF33WNibvhaMAuiQppY9RLWBDh
eGPAp1GxyXnkfgwjOLnb7FTLcPji/82sJtdtHF4Q4HeNkCjEkBRo8NuP55fd+aUa
7wow4c8DhXLCddkmpNufgJLAMo+5ToTb8+fQSPTVH6cT3GcerQeHuuiLvqMhrWCV
E2Wy0/arJ1sqjw5J5SoprDkURZUsZ6x04xqxnGAJL1pBLg+2OdX8rRsjl00HpEgp
Nyuejui/ZOhlrRLq5g0qTFOhonZ2ffyz9l0zRj/rhnuduVbM8fhl6d+JhvFNZs41
tEalrLcf1gZIe71LWgWCMxPuOgw4OBzDICPuZd3wJYJPIvh4k3aXkWGFuDu49pfY
T2Jg087ue9dvu+N0OneMfgepyBm7htapy6ryMmRkvwreA9SJUf0z2Cl/N5A0muZy
z5q8uxHrdNdYpnO/aCkkmt1kSjo+LBha4pMBrJDOB/7Zha0jFql2t3JqCOwmInuN
S5ELBmcivEK8+XydcX6ts6/zCuclqHGZcW9MihB/yPfK6JVPpfoLJbu35vs/UWfS
QQEeVhX0Mi8/gJ7Xv7nKBHeSEnv+NyTCZvmuI57g2QcF5ji1BltMU8YnO9EKggcv
wQQOPFQgjQLoQGTevHX/Drnl
=j1uS
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b41b85d2-1c72-4e8e-a468-c80b22ebc8ad',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//YkMloYQ0JKkoy5uxh76bj924WDsa4LqtXIfbaMLxsxPH
r9Bn0C3T7js60ZSKFPSGqmaT5RW4KGbtf3zML7i3EtEY48u7Oex3T4hCevhv6KPw
MV64tBT8CrBaKx2Ywn0Oy0yQc0gSXOtKBHg+zF1ullRqP5DQgSLdS1OPyhyvt9Fm
Uk7eNQ55LyUujCnHZYHph7jHLpynJwSTJxuSVDAjGRuLAWJu3RbZ4Fz2Hnnqmrsa
0UDQcmokyGCQwEAnmkQXQ1VxvMEUOZp92KcZ46H0SYjDnP3bEMjG2PHtFzm7Pm9F
+glA2lzWQW04+WZy+MxVSy/RH7cPDtPfI/yJVPv1Kgo+9UVwiExPlIo8S1UqnB+S
OoEwMRCVhOhDg/jq17lOTUCNjBp7dVPYM891FAYIAdrVh8U+kzuYCFD4FfSCKD1S
fUdtlDqYlEzBHHwuRfBOWHndHpzHBTkEwFyqlJ3uuWw3FgTFpwykEQPIhl/n5UEp
qUXbfHRUQER0VaUpL3LqmZmNkHHjvqtFcj0tclMy80iKmJh0hFllF+qCn/g4nVNG
rkw6cz6hpVCNBJRo1pzL+QkAiaFNDgSCFu7m7oipLLRNSh7t2ubyzsYFS+mc2Y20
/oCAGNaKQ9LVgibuH403NwKoNGQ0VgS34vDPq9GSHAH5satD7nxoHfKrEj0OevnS
QwFCN5+71z7lj/GDJE+dP4n9D5q50WsOw4CZhvYV/m7Yi2GwZESVbA9ltu8p/yGe
6/HmPcUjyxBZIfsVhSd13yWfDKA=
=sEtQ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b5aa3741-973d-4d43-a1c6-5f3d0a48bb2d',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//Vh9K9SqzjlxOCpPdmRGe+bDQ9/0aFlqUEKBSpiixmbo8
27GkTltWFpNvqZ5q9X0zKytayHM5NBOlFt7TiFaoMTILA/sTSjQbMjm21E+DbZNz
KA9xY5cjtsGfCaPUAOzdRMrm5k+c7iLm/i5VSfLKSgZiEU4w47o0HAlVCW9J+PK6
Wdivl1MdNOA9i/IsGmNEb4mhVls9T4kwxOxiYpjrcuHfwED6ewYfkbdrGEUenJmW
QR7IbGuRo1B9mEO+qjppTKe47zMdbjvw/MOQQJ7KvxHJ2iFZvqHvx+SLiu9EwHPa
LanjIOffVlPwkArEDdjak6RFcO1tY4e82zeE1KJoUBk1LMpUNd0lIyW8HF3WURnp
U58abCllVoypd03CMck2T0xMEJ0GxhYKEYM7fokXJcs34uE4qpdB0HYUHysIa0cn
0GKDuwsCnxNw/G4+AmgKgGA6cjNHkHffcl/sx7ZdFjGyrJ0f5E3eEc/GCwURfrAP
Z/9MGuV8/X8FmKvA+Was8QnaeQQu9MHxOj8/DKpImlYMhwfoeFn/JNf9GyF/c0wF
CcWHn/fFmHONWT3iD8h4jeGmQ8g2vSbcBLmoJHc1mURnzetUip5FszinDHXjEVq9
TcBFwOJROeaThn1bBPT3Fu/fgm//04rY9AOMzUMriEi/0fiXsPK9i42oIAt/ATjS
QQF3H/ocBmmUjflxnQq5j0v1ZCTM3+Bf8LkDQ/J369mDTvA6zyLJtiuCUQGyq6ys
TsLXNQQQHYWM4ky+JYtKXtcK
=sAxQ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b80f63d4-a4ee-46ad-af57-62c382eb6991',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAsEPpk0XHZd3Qn+eOLrdtZlRteiVX4TB5wT9zNq7gMCcd
4Za3WJuo0yzNOfNCN68kmp96EODRrIfFcdgQRt/uB8cKwV1WNsW6a39KnbZ7iQQT
ss/rcTBHrsbQCO7l6fsoHOhNyfRZKDP9u5TdaVI2GhaYHeAC1kKyp2jNc71MkbHw
penpTMI77iGD2kJcuOotl7P/13SDdXOWeYX/vmBeu9+9yHZ6KzzvKuLMsWMcOXni
RPUbU/sFdAHrtTa01T7Has3XxluI/Ti6tCNXkkr5CTspwrUdXVhtBnYNki5Qcd7h
10VZ1BVoWna+renq+9JJEeykURvq2vuD5MXFmIX5+tJCAYzlKY8LXrLyHUcNqaON
AE+6fUxPWla5p0cgqOHS0PigXOfyXNa+Aj/j78F8Hz1tYxUMf9/EF8idbIN9q3Kr
u18Z
=bRR6
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ba21c0b9-1f13-4b04-a0ba-4126e24eb087',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+NKogQbYa0kaIMPw0t+e0NsZjtA4lPl+pA0OqZs5irtVd
gGRJLZYJLPERRcKu6BhfOze60kz35zVZtZUEhihV6yQNUTVAo8yvvGyi26of0riZ
E9cgsYwepuHzRQRZTxIFExA0U46PMuop+ZcaTvMc63yh5Qg8n2NrGFrb5bQ0Y2rE
y9yApmpX5T7RHSVct2HQ7IRqyNwPMqAPfhzI+B4ixqqxUOtBE8zZIzP0ISBCMqj1
1MGfUaP6PIgNuIpk63RyUx02OlEBe5RxTOeDU2mquCuhttJzaT4i4QQILOndBg21
ECKIllqzO+Lgei/om3dUG48JtsZI5h/zb7g72m735YLn4yKc+QdJAUSlDglaNMvc
JTj9FvEEC7faKz4zG/ya0NqQXZmEBsvJpz+vISeLWpFO0WD/BpU8z0U23oXaWkmJ
0EtBzYoVfBmPwIFrg77KD1zunwyRcqBQx8F8Zz3PU8kJl6A9VowoJgwNEV1o4QIY
8tSBFDQeD59B9qHbifQgXWDHZmYzPIYOwm6zW+lZRXKJWs1voyzamBuFmXDbSKsQ
VIdhKTqmGw1jeL6mjKoPuXuBFw+Pf21OkjUqq+Y9yZ0RMwkWtjdoOmKToTup4dBu
AxRvqsutPSEz36PT8J+MCUiTDNZSmtv1hmkaKbKv7JIL2iixfJS4/nLHF4fkKODS
QQE3tsH17RpU/dBhXylxz55uLDItSZWfr8BMphGladJQEJ7rsgxRWrGhmeYnHeqc
HRVf7zBGIEYQ3ScqoHum0HPi
=FO6T
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'baf017c6-c06b-4878-af14-5d9fb1266742',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAmxCZ7753HYbbcEBcjyqyicp9oB9Oiawa/U1FAj90b+3j
ImmzOZCeWBH6a0wT3OPgVD+G4T4QB3ewxlQseiCzD5QWj9BkMZXVGwKXqgNORQEC
i9pRsEDJqEZj5imj+m8DHNPAJC1VTFP2mq0f6e7Badl4EA9fy3YzYud1VAP3IOsY
j3+B4pQQzYHqNs8WZWwgcqAYtTYQWH6JbTxLDOwy5DKfvMa7aTXUkkoRAYdlbCDH
Oigtxqa1poqjCgoayOslDK7rnIDTogHwQag7s0RREWT6OYw/fFeJeLL3gO3nKy0Y
mxtt4USGO83bmruozo8f1hX+KXVBmLvHL51kqeL+09JDAal2oS8phYWIvVh9W/Dh
YNfitqfGlSM4yByps/1gxci8lL/NViBIqjUBY0J9WfjEr9N17BbJSnMfAsAsYvT7
Q/DLzQ==
=GZMx
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bd6be008-e3b8-41c2-a82a-537a42c45323',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+IwtPAMPbY0InNRLr0IokyvzxzdBVPXexTE+piY0RcOu/
SSVjUSUA/nLxyMS3IK4NJZP8YEO6am6cYfIDGIU5LfzjVLVvGoSlQUnAYDuyqwfz
ckPb6ApRAZakmeAdKm/WJZBmjrwnKHPAQZrSQ1eRAaoHHKZvVp5MTUl9PYD6uFiY
vYWsr3h7dN7zGJh8cHs43CHgkmL7ILd0xU0A1hJ4Cg6vGskH1XUjanxU19XE8iy8
lBycGAYXOoIPNIaRi9IIdZY9Jgm44k3gOZUBIV57JDOSgX7yjZ3Wm9GcRSfr7ySt
SKhcSjbpZNtAiZLXVxE/IMHb7PeGrv8ORx0/pgR2DUWIUDowLIhESs9HRQvUjvv8
Z/fEPBifKBC214vXZfbKgNoNbCVAwM1zYmMPht0uKkE//G6+4mCUiL3kKeJ6giLL
IPX0q7+m7qQ6Pq07uGMVVKzUcYThhaNCQLtokCR36KI1e6vUagZBE1B4OgxqfTJ6
EOEi8VhovDtY/n+2HZClPBkgkTeUa4GhnFRh3rEUeTCCXsvzOpdIENz8SZRJGD1c
PVgyKfowjFSNMYjJpcov/TDA9bMziwDbvY6u/dav1FrGsBr+qN8HtKuwm92mNCAK
dcPdfoi0m2mYdPa4Zb1onE4yhMhdWXz4pkdYf6/sw/clRVPuvruT5o2wE07l2dTS
QQFhGPmZoFyT5rlxb2SE1FFB4lhCmsioXCm2/x5MXOixvTSus0U+7e0S5ZuEKXvX
E8MDZvaRqzzzrodr4L/q1cTp
=vkk8
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'be21ea37-83ae-42ce-aa52-f23761018294',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9HM7u1II9JZh2JghtFd+mIQ6lMlCbcYT6kyMpWMnYx29W
1OmhV6GdGpxctPtUYaqy2doukK9xM1ZfmGdiZUWwE0sn/wIYkb+3qCjhsC8cv5hf
FgIM85T5fHUubpxRCGRJvC3bIyHwrPO/1C40VOjWANvroJ6mpy7TcF4/pn/44U8W
rXvZpDW310AGqRUy30TU0BRWHazSm3YNwusP/j27tmliIpeieNCuATHSJhs+izLf
GsdUduSwISNqAkZ1NUCyyAs7MIvdb6Kw7bGMCzWctkgfEZXus/vb0GyWso+8ch/a
Dp7WBTyaXaOj+mAMi+V+AGXNmCm58Npnyscg/UNMxd7YAtzJlXzeMwhxjG4CtuFc
kSTn545wrx0GLSd6I9l35AWSTM1dJtbTsI3BBi3/GBHn7/Dbz1o4mhXKw74iAP6l
hg9a6Mkj9Ax0pFGK8776OOOQv0ilNFkfM2ZGDVPe3cqe3giGbdT54BM/o9y4l5Nj
8w85Sv7MpltZ9E546FoyiTfCUESC3O0pZPAwFRFe3e+h0FecnLdR4iNo+DdU9uig
Cu3ul/EOi2K99ckhLASOdo/XwhMWcK5LQLA+QvmANYLjcted+wuZDSZI/XlVPLCv
RJ1wFrIi8FSCaHMQgJHU4A/aATrFT2K6DVHXRG44fVpfnroelx1nOwL56mXs9ynS
QQE7RCsf22184DJ7AmOOCTl3+CttEJsyuH2aDVpSJSO96gAgun4br7lavxfXKSzh
Z/F93uqpn+oTkqWC9PD1nJH5
=Oe+v
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bef50a9d-0458-4242-ae04-72e77ed4534b',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAhwCp7Mt9G45EJPN2QxXvz5GktmC22bUYElp6aT5PA2O0
n951UPHzEysMXPiOttMWGdGvrIZ5Dh9bF2H35E9FYHkbVHScHxtIjCneyiehZgbT
gh4RveO57w0v1jKV+3/EOaHFeZnpRLzkTP+H/LWLXhcvCN5qDVyUHu+A026/lLya
H9AFLD50KQ5Tu4tsBwmSS0QI53jP2scvpn7sRP7gkqHTl/Tq+kCY91FyhltHVh6G
wm1B6m6yF9qnVIOH/8GkMD9/l/egrqK7NSkeKjMx21u5NenQRSw5E7Y6yh/XUc2y
Vrw+PcWBPvYHfnQycqP2WOWLoxUd+6lDwT7kHb3rOD/T/Pot0Q+H2VICPYlIZr5u
d6PdyGNykXCMO9iRm6Kg50bGG5NHyxCJdxp+ia3kaW7DMUkDjmst6fizs9ssUgSg
FyzVSjHS7P6fiZoDsaGMC8Z/QiVh6riEAgUd4ldnuKPyC5KBDYl5OufXWVk7qLWG
3QFnAkd2nh8nrT+3iTu/aMJw0SxgUzUfJp7nnMME66myG2k+r8xJlq2GLtukK//l
hprZe7w4WkzEY+ibbpDiwHN2IUwyLhHuy28TAjK+Uq9VaV1Fcf18knNATJKca7pv
AyDeiTZmCnjr7CR/G5Aob6fbGYjd+DW+UbKKThs2Fp3wQr4b+cCJwcmSn+ZUY1PS
QAH8fviGGD4jnHyjhWWw+S8+BWAKVcykm9fHBXyJH8JhjnhnHbZPp9pqSgQ82aYE
RKSZnRkGFb+qWoB8DTAZsHI=
=6ITZ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c09ce990-70f4-48b2-a5ee-b89a9058a50d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/7BSXrp2PkGrtmLgCanBSd3sXWr7hhVe0JbPKm13ojCiMq
+p1Sp7M0GWt6zPZNuCH/tvSfHmF6C4TrVRQ1L8cxLUogO3riaHCfCLmoefN8dXYx
1YNn/a5jY/vNCbDqfdV3IxVp155bU5mDU8I0g8CofMWtr8thK1MXGoqTqTe2I3Yi
F1+X89hxIeYO5uJTu1fAut5XUSnKNDUUYYAuMdL1QzsUvxa7Rkc++ojZV1xzt9bo
+Y3qUeTXglzKtqoZyvmzNMuaPv9MIW8EUDp/pqjXh42HhYw0B9V5HKEQn86rIen8
K2Oe34qdxYkP9Wog6JoJPHLn+TaaFiWMx+OQkBLmXYddrZ8ffsHY6uPWjauLgPsN
3Lhe+VXps47UTr3y1Fxd+dYJrzuPSANfodhjYGN2hDdMdY/mbIRCZ/oIPKLZn93F
l/dEbm1EFsNVvjfqBhPrrczzkMUMuKEceTvEYkYf6zNbjHGTQbes4ukTYH9On0dp
ZAkGqtkTjxYeOpy5dByJQypAxWkdGk2m+9/ttfReT5xzwWEGD14Me8Mj4yyeFe5j
mWfX4knpuONY7glM1qSo4GLi/SLxzufan+OS3n+KQEF22MN9Ej9D7KQj6IEa2mp0
Iqb8XNZiBct163a1GclkJ3SF5g0Kljk7/4SEpPAAvHe4wutLUXSULC8Qiacy1wnS
SQFRMS8nVV9RzpC9PayW8IlKG//DI49gt0RtSfypKuOYGPq0766EJ8dy+nE2oJGz
6t/2nswI4UJGdjYDkwfoCg7/bj+bKEsu4S0=
=LPYO
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c15c0be2-bcf8-4978-a568-7b73a7c9c9cb',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+OX6MHATPP0jKIkrTKIOPeP4bsOGyeqiNt1EHjU2QXDzY
HB2DCzSONj7ZCZo5q9v0D1yaljjGBsyAxyTCliLj2DF44NFDwFT9hPtnWK5fF+UB
YsjmwFFMijPyV9EVc+oj+UpIqrGwLA68FZt3IvykHlVd9qdnQp9KfqeeoVcCn5q2
LMxA6SwSsTtviDnNCdfj1vlR07vDTqLVOdXqbRRGt5Yzg/IRi93c/6PiMQkxCImW
9WDmsLcV5IzDf6525sUg4oG15qLTEPLLPPi6YuPx6CiQuKs4NJnXST1w6VbIuxI6
4EqRhDXwVydjnyIplIXK4WJd4Tujoh1nANWlAUJmM9judShHRfavy0h2ngMBfu59
GtxrmHKFN82X9iltGH5NtjvmBPWYzR4AS7rAAB2jKD+xgKQieV/AGiZ2Obkmlvv6
0GZNVSxsfe4PyjH9MGEXXZgDCKYWrcevQygWFi75oAsMkxftJa3XYSoPO/5dXFzt
b9g/9dCHZKmzuCeYZRFi83BB/OhLd5y2bqmfiBBUBVv20SB0Tv0w/Rx1n/8vriQV
Qmmm5Z+t29yOWgyqAKmZ04rZZkYlTsQ0pOi0afmyBQfXOhfTOwTLKVZZ6UKQmP9k
Fxe2jumj27KDExUFrX5yRxJpHYjV/t5zazo7DZpEoiIXTNuf2MAplJHjTXFA1OPS
RQEf99pSzuhHm2JP3In/Sbt7/6Q/NX8jbm1kwz8rsCfaBgdUOEadWRLBiYsmkxBI
nwxiG3UJ/qw/AX1GMc7Bxt3XByFpFw==
=0uPM
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c405bed9-b41e-48a4-a9e1-dcfa9843ab3c',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf+JtuTKvIV6QkJjSoXo0vrgn4Ds/RoO/EFJQ9WXUaxR5Vz
wNG8Qmqw8CrY6j0lKGNsFcO4XUpG2mjUOyxKRFwWNN+aOtEXiEZOXhsHROslhfho
cumFX8dcA8RXUCM1aufR3Jien1978N8tSU945wDzDlUbSqEaV3rDbBWT279nzVC7
YsGFd1l7VvvxmUbsPMMwCbGUFB3hz5VBqzbKHcoQ8dIL4+FeTD4wsA2fovfQ+PVk
NNe/9HA+r1X+MRQ5WhT7fb1uDdO/xbIm7KQ2BiEMn/ZDcOaWRX0vG+ihEDU+sPj7
MMF7bQP7HMQ9Na9WFJcmBub2gXUo4Ag7dIxvZYlQotI/AaChDJHrcVzQG+rzn4CW
PJTkrfgnUexpPiDLiWC5+PJdkKpGGqDoy/TzB6jaOIszLn4f9qVop02rpS07TS8y
=UT1m
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c5553247-a25a-4cc0-a04b-f288fa0ae985',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAvoxSNYI3BZVpuVXgiwis8pSGZUr+tWH7/K0hCULBqhwi
QyqZN2qmDzcVdqCs5R84MJDpoDOQJ7yNh7fSxhxwgayHal8w2PkymnKqacGRWLIy
073cJf+IsIlFKA790wxpgHZotvAY/I2PII18ifzTUj2aFrYceDdXDo6KT1YYa/Q9
Rw7WHJPJasuZfGeNFB+omRCsgTw/KoA+gJQXSiSgwpB2JAKJNvFuN+VmFqHbx7cI
BuHeeuyUcaas+/tEmn464HOODSDIQbPAfvMynS3lbuYtg9bWYbm0t7SYSy4UpHZf
aJdIcdTES9QmrCvlxoxVQQWRlTN5k5JkQA1cB2+K0mwuPwGoyJAdM+b3/Gd/K6hz
LKE+Lx5DHccz1SWSngyZv7NsRE4sNIEe07o+YtmZYAdunMGyn2S5T9NZU1x3x2rb
13q+UxiQczDpW4/7IZZ9vtaXpdWwM+TI6P4e8Ji7HFEcRNh+xiD10HdEdBlnQWL5
aNedpcn2rSWwK7rz74Ql5W3piuNsZWMsyHg0V69QBIfVRG+wtrQzk1ZfUOMTeZGt
19RDFUAAGHul6iVHd+szPE7bChUpkq0KpuzokyHJ6sDLNNH+W7dKSSl/GecWjOHf
lhIUWgHN66usQb2u8cZfzTw2NTleDacdBSPf+DPG+/WjK78aEsguD7aVQM7hQzjS
RAECPd6Mq/6YlH7p1anCIJNhrAhKn2D1iMkRtCqE0/GQDhVY2Jkx/4gpe51yfKSR
6Oj5trKX8CRUcBBhI6rCfHdiFyNK
=Y0wl
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c6276da6-de15-43aa-af2a-26a2f82c81ec',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+K+BYdNXU+3hRBDs4mkTm1tfPyy3wmr4Ius1LWRcfisjp
WrVXM6XzilgIHKuq64DSF7XpAyWKUOvncAFvzT/v4hJGNKhMq9frzrdUUpD+kHKp
z8k5AGR389noPlFxwrj5gFhIK15x1sziThRJ3u1dYxhjH7cCeJAu6ar2/bQ3HQnP
0NkPXg+KrzmPd56vgcHqDzIKVf1xmAEJGjqLxqz7QaGzitWkG2OIVUPKbhaxN1HS
Zcdn1PMhKZgyOSep4xX1S8/ulFuG2i0MoMzFAQlUvoeJ3YrRRSlsAH38fygiNuXj
WgzlK9zXGuvHqM3tLai2N4ZSzNHQW+JyxTpSJPUtQNxCddugJquK250JmpF9mfG/
Hi5+NTAWxtS2YQ664DCj3MndVib0zVr/IaJP1a2IjVChthRwH4D5szhYFvjMamcI
Mqq9s7i6TH3m2c2qOv+XEfZj0RX56dkZAzOrh/T7OmtQXB2zo8wvu75lchalvaMO
2YpYRPt8k57PEqejnxHeCSURhq0DM/frg8xsqSSnMJ/kEU5H04MLvgMp3D51DR94
Yl/7YVuRWXJQN+tvsllhwas+9CA+f0W5hPfP0ccpBPuFes2lru5paaZ70ukUh71D
qA3FhrahFQwNpV3mrJJ2MltCHbjai29I8WYhohBt1e3Cmrg6M9XNRVqguKvWSl/S
QQFurkFybK+3ynPwQQErKTa/xV/mq09F02y/XCoSPN0KPBfUS9FD31M2VJkVXS2P
AuSIozi+YB39ti2sg4w/Xr1h
=SjEU
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c7a1aa98-1fa2-483c-a52a-0da1635e2e94',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+MRV9YazSm7BAwed4ovPCB/m6NDg1SRkoLRaB0vS6lD7I
TvwlltB+XOCWNPLYiD4ktyGITpa5if3YgvtSeexzm/w7NQlQ92qK4cOG9xt1q5eZ
s3yKjbVUoCp5KjhDY35ihChJBB+ZXasQE7DXo2uv96bFOJiq8KOxUZg9Vj34/o2L
yyEanfBru/afKT92O9gThOeBl0yQhb+xW/zx/Z7ryO22hKWk+ubGrDtZs6ZHbgqO
0UnO8US854/9pQfxC1wuYvT0bgVa0u8oEqBufXGgBgFwdfymFr/X2k67l/IuNsBE
y7OfSRMzzoO45POjK47NGirUbYcRthjqoxtPdvswfZgd7HGiyJ2z4BaaIlZTZnPS
dddUUewp2Xn3q82T4p0DFm4SClB6d5Eh2+LFT2/LHodePBals0TpUe0Rj7HImg0D
8mbpISsX2WkOGowf/OsZ/D6lNkaHhtZidv/IXL63+NSgO4YmRysUDbm9UkwYiX4x
FZRpExy39VyTfPH2WC+qyLKXExIJX529HDLswfSNZET2Ed6ITs8dtNWoQb7PRYZa
Jt38mF9AjjEuwP3UvIQv5bcznnwo0UIdpOCJCd/Z3bx7cKi01SltROtfMe6LZ9pM
twHqnDIUF6uoK6HhMDWIXOMpgl0hxTT47iil0I2Tv98tShMLEA0JtI2s0GLhrd3S
QwEM5/inAUdBsgyYwXXdeaWwjPCXKja4hB1WSYDXuE4FKAkSUw5vDa82Y0MnsGas
vPXtKwU9s48tQneotaqIceYBQ+E=
=7q0j
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ce652174-c0e5-4ce1-a826-9851356b5ee2',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf+JEbP/JcTCsmzM/xSs7BLMPPBk476LawoUPkAgU4TbvuC
00SU+3Ydl5XRIedNtQJOG0teosw3XTx2/vyOfqpPJDKNc1UKNFNUzYp2pEueJ04I
vt0qMFWWNoTZyYR13MQHVviCGXw7ZHESeZticYZEypQk19BOENXEMx7xokiedeRG
ChYdrE2Ecvuaf6gQs+ktqRO/YLfQ6gyFngLbOj+ToFgmY0MUyAj1E9kH93U6j64y
qPqkB79mGJT2CqdvjvEDJ1CtubI6ky1W/Jon7OfNH9wmN81avVeHNltZ5qU9pk1k
K3C5rPf1Kawh8ALxl+AnXWR3lCVsZ8J5Mxg3Mpx1C9JBAfqHixx5wglvl6Arp/uR
EZbSw+yHmQ6MWWTeopcB9mVrWJi5dPFWE0liN5EHlfsHlmi+C0PbsBOhBShaGgEa
jTM=
=0TeE
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd00b6430-8a07-4240-a58e-ab5ec3e72fc1',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAiCNMj7pPB/MCayDh4D4mg8b8pSSsViCUvSt8Gw0Ub4yL
a0yc+DgvrZw1dK0vKxX9UwikjDp/v80/Y0w+Bz21gfyzCr/RdtpgdvdMCJyENFOS
qMV2wpvLEi37ioBgTcvpa+MQgslrp3dorAWyxH4bGjA2zKUhfThNfGrN+Rq5wnoN
po5KGmqv7b1oihh8dibOUSNFcIjCfzfAQLmup76TQBRjUEd587tJHuzx4NILe8vs
LUzArcptJzFc7n0Hhf9Iaphbox/7d/+z1gUVnISIzCA7PO1lwB2dE0QI8P7S1jrB
zNUC0xsvlB8IHRlCjE6dr5+tBEwgPKbeg3aADWBijT+j+7ox6K2dQn5InBMXFO/K
03c25OKrkkd2G4O7rxlQ9QBsLOuUrZIfi6kN5mqkKl0gzmQplhmI5Urwd4wuGYnO
SkZUJAjPD+hlTqBvYUHBmvMXg8K4bowUvTur7aL42aUt4NONyQFAw29WJ65b20Vj
orDLjEW7gk0YBdZ1qWwhrNmd/0MKTr5MJJagysbn92jhrjCJBGZ/t2DfbUAl7xdS
jCgaKj/qvQoCJR7zLGpHyPxvySxFW0cyu4Omz7+mA6DTmhMH1+dADsyNCHiCOnC0
X2g/E6S5JPU0aZM9TyzvoreqcmLk3GdWd/yD+lxXIc9ASRej0s049IHbP4CkQADS
QAGEzp0goqEd0NC4szVCODLMkNfc7+7JVhUS+uZ4FHJ82yQzOeaZG4fo0dkoQeqx
PPQS529RKagYO19J/ipUWmE=
=DSKR
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd0c07542-26ef-4d94-ae35-ca647d551834',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAxjydXR/7G2PL7AfpXy5prbaOtKoC1EnLnEHzl+9bh6vW
HF/5q4FQabCf2A4CWK+HYWhf37/aw+L078CHiEL6eXTZKPOMIVJAepYGG0VMvOIT
FR6mqOC9srVzCSIf6TPyH3Db/Fk0KE2YOflSbH0lCjPy8LJidPV2JnO87d7qjPaE
QSBzfbmvk3AHlTNqfEA91dIOUBoogZt5ZmC6OlIUFCiiDwq1z1RKC0C/0sAxncRN
PqZQRueUacbl0Dh/UUN2UEoAkma1qSI0QvcWp1KF1xm/m966dKDPhC7ryFdZZpMj
HdW/QfiIdO7RviH7LcsBrqaFtsH7abGqv/qtENjNj341YnvJZAPGFqTzUwT3xFyO
7dLl9oXbmhUXpSeXuOuQ4hG+isY3R99ugaaEwyx37bWVUGGi0JHA2KmBfO57Bd92
JYg1OARBhnCmmeIdQPVHvFuA0x3rIkxHVaLL2hzqB2PGVpo53SsahB6BWYH40eR4
D+zWQ9sMcnKQIqKZor2ruFO+OOoFhPxb5C1OwSFJG0+tSS2xAiaGdjlKIlS9bJKP
//zYNsyGVGhizjQi3Hm8ws44+o2YFKuZ2Q+1nKMFQ5W8L2lM++KqSsEnL9nd0SIG
QSm5DYPXQChWZ61VOR7t4Fl+Cerb9XdLV0MZPJos7Cp6P1D1Mwl66YQ9M/oVQaHS
QAHweuHsq/S/viOhhBXo9lugn32pIvvdUKLHt3kAaqhsPCOa8V55RNAn0yw5jrtu
fW4XUUmSzuubhr4NyFOX+B0=
=XES2
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd1c66650-e6fd-46ff-a328-ac5fd96cd86e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/8Caseqwvta9WMyghb1TIHeE8Yz5EkKfeUokyLbm26JQLj
iVFv4r6QqOZT5aLutHqc4SEcyljgfTDAxeK8mb3oveMHdiEC/5Bz3wahjTdWONK6
R5wcYqYj4FamNu/FQt9JXxsKGfibS3/jQjNgYwD0CXePhotNBrsyYCMWcVPewdSS
futfIpAsGnUqyaEgeArMJ4MtlSJsEUBCTWR8LKKTmxbdZtozAbkJvaWj8Pgsr5Fa
U8S0oOK19uYo1KHhY74zpqWD9lKwYsKun2kxBhNzgBxzUXB7WbjSJ3JkfzGVDYV7
7C+OBNarIqiqajkq7zDJMUJShvO2PUiuL3OYZq2h3gsU39iXRJpiEbXiqX7AP8t6
2RArCkbKC0H4FWtGmnhniE2afrCQkd1/eVAlv6ujl8vjI49YCd3wwI+5q4WGlTm6
A2NvU+oP8uXlFAPIfgeSL2qO5tlNgQ8oBtwM30sI7y+EfMXV633p1wL1pfOCEe/Q
WWYlLs62d1P0ZthhFAotP1ITt5lgRJ2kjCE6V5XI520eIixec3UsWY4QFvZoVJB7
6+MaW9eHqa2zXPI84Hrjf5D8To8jqbCYPAJtj2mJ3lx/Ja2J3yaEvnCi9W+2Dmhp
v+SkYSCpZFe5gxTN5qqyNGxnE4J0rRLAr/8YByqK8iXUy4HoBvL2FW+NxEXI8wDS
QAF6doL35/dqmSX5whRwGglSFPMMNuZqyM/y9CZ76LNsXHqoOaSwcyC07f9FnKGN
slBO4wctWqMUliCVxYamxPQ=
=t8Yn
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd2027f87-d599-4147-a9b2-6dbd29c5b6e7',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Tw8VPhTtI2RaJ2YCB1SI0qoQOGy32B79VeQ17ki5pdkQ
Ctpl5PMVHvabizwJOUzMsrfwwSHaYYn1ZheA3VbGMbDftrAc4sYdU/MhCcofd5/q
R6w9RMJzy5iWK2CwN0JrXoDaGzuU0n8RKBtHKPyWfg7NhqATKzWFGvaOsygsfopi
ty6xSymqJZExY6BA0UddCVWfu9AU17Cj1+xsAlxJJJUsKz2rIcPBceFNiDlCkiry
F7z+a2AlvlzNgb88Uz3eBhmWonBkY2X5/AiWZVKvTBYq0FWozR2Cn60HZ8R5W/D4
i99yfir4BmQYBiYc4dwjdtKvTAzP6Yn5JnfSqojVk4peT3rnZOoOczCiwJNhBhJO
yFVh4mTYkMtAZVIJzuehRtF5ehjKu9gUrlPXZvlvHfoHROL89JUBwvpWlr8zQWQU
4yTP0YexG8tmxU5bkybbAAIM4TuekRsFisSH68Q3tJ6RpnlSbyGwePnnUZwo4IUE
EKgVmDSEqxDWPMMnKb5pn1fvPAW6zhjbT37HHE0f7g78O655uvruB51r/iBpcAOU
/kOQYTG4/edjdfOF39hdSP8jXsmP1u/d2h3bLwAui2Yz7Ayp4jXecV1AmiE+lwuv
wcxmPh40pasauQK5ib2GI8H0Z7dZLv4Tr/tG6ITJgTPTuYu/6ka6Tn14h2iL4v7S
RAEIHf68pPTke8baPhUQ5ct1qTNcW1vvA6OQ1WRyKBcVRTLHB3aIf9dthKBRaDPe
/rK9VzE1ys5vhv/XK1x3LfL656E5
=7JLT
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd243b4f6-1773-455c-acbc-9c7eaac5120c',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//c0I+5p+OULpV8hUiJburhzzezCKeRJ3+qfxm6JzmJL//
mElsW8eDJNhgmrIWUYFBqCZwHubPQCTbvKFPBK42vqHESk/LM/Z1VNwcqsBI6Ind
4aC/TqXbAiJnBwAUlfFvnTH7MDAo1cmy7dp7TUJ7hhJfNeJHAS3ID0VBtXa5LOET
wBfugGAZtzb+NOEqnrig+ZucVtoRewLOQGjPdbPsQ8DdG6D9jKDxjRWXx/WHbz+o
LF4jbdFJx/Cq4C5L4UF9MQZn7R2EtRFkn2eySMPRMDB5ZYPh9nfHVszXVgWjozOg
JfVXFTUu0TFgfRY6U9mc5YxxTQL8IU8fMUOZkG03J2ZOTWOCYxYwfegIWox2zWjh
DQsS2UzEDe5o9u00n21vKbB1kwijcI2uvcWUunLq76eH9FsWJjqTMWtkGlZoXp2X
oH3ytzDWFHB0wCYKMaTBAbdFt3VAJGhB0bUtnTALoHLKG+V7IjE7rGNUkoAdW/mS
90AM9qfNWKGoChsTiWHj5EEgvwC5kLcSvumZopQJwIwiA3Rws/ep7M4Gp3RS89+s
ZXnQiKPuSTngSQKc59u2UwIgBRkG9o24/JKMn8rinIxKsF6n5dg3I4vEyYFVT9p5
1fJqQ2pGpAFLC39wMj2L3BLmE4n/SN0lG2wADtby63lIpZNLkE5iNFxpABIP9rPS
QQG+ttmIA2RWiSECs7gNTpZe1sxgxiwSwJ/b1Q8+Yy0lGyin4kb2Q5mrhpe4piyD
B4knp0SdEShz1CHISs4Gy//9
=vOGl
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd24dc3db-82d9-4ada-a831-1eceb04b4b81',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/6A3Ej4/jZKqO4mAU3+EQff3XZ1SHc+9fBSZ5sRMC/Wc4o
9cJ1JqjlOnLgsb4wV/bMNRSevBkiBD8CGVVOi+vKM3eJnhv0Q/ckuMg1QEuYvxBI
+YeMpHe8cT2WDtk9iq2iDuTk71hQs7+9JutlyDvp26KAKDVmMnWnt+Kv2vMIOD9Z
39TPAHmNT5edl7gVNOldqPGGIUgOYzdsheoQRagEszOivcUcKbafIlp8RFqM7u65
CUzfsgSLlORLLbZ5y38wQd/dUUkPFvrF2pZcFGk9Oz8qrSj6ZdQn+1ZZxARXoAid
XT5b1mdxLAMafoUYx3hyQ0buRojhOwkPNID2NPfuQQAER9GpRb4r89YAbD1MH/Hh
HyaL75FTPweon9vLWEGiQDFyr7JTrfgYyUmmzrwYTeX8lMmNuJSa6JT9t3x1Asly
05yJnGs0w+kteZsQA4DL/RM8Msh8hcXpQgrget2FonS+LjWR56vU7r91z9r607VI
rD70KsVbH+4zEgGJg4NWV3Heliwj2iRR655z92UGE+WWYIJNNc8BNpU4sqODJUnn
W5kdsiZ+xNgg1p1kpxIwjRcyVAOlTn5O7fzWCdR1GTpFpdvL19z7gh5F+o+Dw1eM
yfBkzzWXsVqiFsZDtxzXhz6UT2YFEsuR7wCos5FNRelYwE7v6DFwfkHRvHxK8nnS
QwEBrYyF5FD4IT4y3kNhDyVdwdQhN0+PtR0ELtc69JitRASMuchuh9/RtQ27TX6V
QlrVRYcuG+rDrHftWnpSgzgxWzE=
=l/mB
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd30b8e89-5e8c-4ccf-afef-0878b0bfb731',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAt1jSdlRojJb1FcNuIF2J2pTXDeWfBiCa2icGVsP91kqS
WjjygXt+7+UKzMxMOfzmyYbPillvmpGqoSkKJhsfcIIg+9NXwDkLjM1RVYlXFXfn
CBzNIWIj4xhih376QBxEW+5yupi6bCKGutIK7P6F8OOB861S6zvVvlTBZRYjn39e
XmNOXXnKfb2t7we3tJlRhJkrX5XWSNG54Iw6wuVfUyRYBuehQCp327bpqz1gsyGC
LCTkdC3blaTHlzi+zVzqMwoCAYLVEEXALx8JfOwtBVIyMqUISNjZ11DDjdz0/Krf
wA+Gwi/13eKviwTZY4yY6duGGkmHQZnLIRi3S63/VAQpuh1vPjz702ih/lwjz6ll
WfQ0Fu21h/v3lECyRauMd/0WiZ16Cm5FOoreYyd42wUNYW6aWb0pivfLBfO4yA8b
3OppC8zMi2qMbaS9kHL6C8OnL4g+I9itnZ6XoM/68iVu5qdE1Jvv/j74SySQQ0wb
95gEAR8M6v1wsiXCscqUA7h2FStqnCSbU1psd+60vi/cZfzD0rUKHD0WMRcZxIkW
ZX22Px0XHgRizcAY1jhGuL4aE3XaMZTKoIrX0XUy+qXlxbogYwNX+44omXWND3LK
fPkxJyn96A25zahz6lD77Ii6ZSjcRfdjbTEADXZthpij4+z1wfbvvsWPo5BZvDTS
QQEgUJ83MQtKzOtSN1+Exlf1n7RHZkiovw01pWARRpR5hFENztPeKiLnGdFhjsLO
ESbUAA4SfwGSvrtNn8RxxKJn
=ItU+
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd50149a8-c955-4941-a094-c58f6db487ec',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAikXfQXA8YTnEhSJbTqrYETwTTRfWoU3XauM59Oep+LZi
Y9koAG1se3Sv6UPa3RFQe7aOQAzXkIn1hs7+hoOfnQiNTvR+ebfoaZHEMB941bWN
/UYG+cM+0/8M1Xbv/MdsLbrkxcH4f/tIgsFdy8yVko3sHroJR9JIFQzYlaQZ3kCL
ZXnXbS1zApgvRpc5XaRQWb+/9944Q7VHe+PXjL+TCOOkharJ0YL0CQhOAYSnOao8
bf+vfI7n2Kv55aixotWzXKPTKZHMcBphdQw45RO5Cfmq76egP+JORG+2t7gC40RD
wrWUNL+x3X/apLnSTEFDeSRpWmEGFUFuwrX4Xaew/Sat6jCJPO1crK8jhLBUaud7
NywICwXEnE2C4pScwp/VNkmJKlwehp0hA5qE9UmhqxzeAw9kLNMKJPX6LgS83ExO
XAOmbdrPGZ3hnGFXIN2RTWRSC7UryBKQK2YcWta+L/Gp8RVRoXrfa3omvSYR9IEm
9PaYJMBfMkgBNKYOdaD5dLFni0Fe14fMxE+0RGNdouX87vhVjfzRDBTOh3o10X4d
ByEnN2qA3ZxG/f63shamAGCtKNmMqCihNOto1+EekJoNn2069QYBkqK1FsavQ4rd
lD/qWDWbwfbeKsYpCoM09LkRgbE/s9UTeKLKLUoAyg+IsS76KJi83n1ahSDU46/S
PgEZ+ohpKK6AgQERenPKtpemZ26qIWgPswez1eJ0RYcj/cWv+CUwxuo/0VgTm4wy
wROel5lExAhd4A/ATFKd
=wvWG
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd5279970-c615-4741-a46e-6bb2449cf926',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9Hp4Ds3ctpGBNzsmGkhaA8HCgg1GCE5lNFqMLoywJAjIv
7RO5w9sBLmnzlAEIF2EhZ/P44W9ms6y+KP6O3LGzbTUjK86gAj4I+1qRJMhhuCKa
PybOhH7PAzKg78ljmQK1nAsNA9GLF5B8+TqTdqxGhY+tiOt4AJBxWNJpBVk+wG4M
I4thXgMae0dkXm4TSOsqQzmRg6adtjz1Qbxf4eeXL/Uvoj2bd4LZhUYbrqLlx6Xe
aLrUT/JEmJXvgzdgIt/Xna409qD68hdzWHFJ5qtTKvFYkP/U07Qw5bMXpA5YFzQA
m8YAIJABTLf+oXJfdHHV4G7ZkM+C2ijafSArISiLTojlfe6jWlBVvnaQcVMOUsKO
h1HOjWaFm/iCBhPuhGyy4lGOemL59X6N1d6/Y+tI6puLLhLbiS6BjSaiWoKZgUCa
bRhkyN3Rq8fCOxQCU4Mme5EMq9rlav9PfiulFKxJMAQcBsh5MZCTAtZbjmbpVPx3
xmkQghsOCJ+Ebm7HY78VKEwzur1DHY9Fzi+TU0tbplvzXKUwgeosE+DRjbDRYFth
pMrmqqa8b+0gdYWKT6mjQZQxheaAFAI1BOYfOzd/3MWnCeviHpdOiMMudSUyw75C
zI2iVmyFUI2ILM63V5/V4xmpS2T94k9DO6F3AYjGOCbz5q95c+ladzcoxG2peJbS
QAEHkhz3EJNfe4ABEFESS5pRAewQfD+2nwl8WIksUzCf7Kd5MegzQaSXY3FU/XcO
Tyz+/Ac4HF4WsP8loUxU1N4=
=0G9B
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd5ec0c31-479c-421a-a51d-dee5d5280ebd',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//WARS4IZfdJ3AxGPreQzK9oO6GAiA24wL7kvcuA5idaJd
QrFjqkGWdF0gO7CztayFm9ms9r/5uekr46da26UKqtfH1BM6C6Br+0xqCDUxUupg
eVBn7tqGif4NT8y/urD6/vg2geku616hYzXq0+e9Kc0JrXOVhWwS1S54vBRudGQm
wByM1w9l3oIRw/Avnw0vYwiK4cG8OPj0zRPshTmu7j0epatYth9+InIAA6UIzCvr
RkxzAT0/65Szu0m/Wgy033fbE/ku2iGIV2V5MU29ZyqY9t5m/cf17zYz1dJqNOTq
YyrE1m4Ev2efhlAYz9hZZbvM7GULXNHd8GOe2Oo5PONvWLNXgHV5gNgmlBKhCNxH
7D+WFErSH5U6pALHGJlqn1Nxbw8MLQtdkzsSkrIVa6o4y0hGU9qHbk+YK4yYtopz
w12IC3m5IEni9GjzzCSM13fp1KJs425mKj5fr5FT2FYscF41sPqEypa51VaXUWYF
vA2hEK4Od5P8wMSrLuEfP3O9R/Q1H8QF1sHFiScIV1BpdD7dNoYgT8ykR0GotyJA
FsOtboFep/5dg49VPUH8RkfX/UgBGYp7cZZBkeE9z4Bc7h4M8sIAc+Au+EN/pmQJ
K0y/tpwQrbXS/AgGceYnMb/CDb7KRyCWSG+/ga791SnLqZJnZe5qjlx/Tcg3BMnS
QQFNMekf3c1YP4vFNl2SU6wAfDqK3quLsrODgfwcR/9PFFGYfei3qc+YW6aogkBW
We6lsvUE/ZQ6WsGevBUuKL+b
=7PeV
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd7a86835-8712-4134-a388-a48e2d02111e',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+KwrYFcdLvDvIL2vr+KcqmKcW6VdO8mgbeXXoCum6vWzW
8DQUpXBut1c//Os7MsGrvZ+XfMfaT89Jo0cQPdayzC4Espda/DDCmi2/HzcYbLgF
JjSaei+Wq4vGzNNikxu/YJ+nva4BVrZeMLt247sC4B/ZtQcwae+seFpPTmtUNB2b
4hKrTVS5g6gLgaEQsTzITL4Ec9K/ZkvxT6miaUtLe0uUKez5E2kDdw17cFAE6QRF
M4sLmXhxxc7soxRSxKbjpJytO84CNJap6TQ+/QCFSaSC6vdVeH6wwIzHv5ZAfAk+
mB8HeaTlzp9kR/cxe7NcVO8iEyMP7DM/PH7hcLc9YNI+AeRXWSSF3Et20Lyjcq24
HzVZJtNeNyZ8oUsIqPFjjn043i8Qc5O/ThBxR6Z7lmCEvk5tL8M8nzCbCtekN64=
=0e4g
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd9739984-e114-44b9-a02b-24f4415e90bc',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAv+lE4rA/R5LNLefRz5GuzEbRgveZIETWmxcfliRTcN1g
DoiSTaSqdjpiwLjpct/KTiphmm2ventQVvw4+pHJVJrJD6D1vhgJYWK8Ir18cArt
KlhMFW+gOv5E2O60//kTuH2oSEQZhB8pNdR32Z4V0IlcAXqvR6kzA1aqYTYYDqyQ
hS1mMPOCtXcif8//bIAW5sGb5q6rxfeZA/y8vHr3E7ESqiq7MDPgiwLaGH+r4z5u
roDwDB5alHiIoEk77dS828lMrXZjTZ5dN5B7NmNezahKaXa/ETYLO7bzwzdKIlZS
VEjKslwBOVJg0KU6TRGAd2llkaztCuwEijj6xsR/vRARKg55dlFtYDOh8bjX2luZ
cQFpvF1Scv3u9Bb6F+O6/XQmDn9R2yLmkU0q6EAZpsjDJveOGAV3TQUfjQ5fHkyv
3XvS9SgtycBVbqY1raOjZiAnnuoKrCXEWSH9B4kWHIc5Y4n3niaAY8ZyMHhEbvGB
FooRK4trwbIdvzSaQ1U/GGbBBFotzy7uL58N/6NGU63UtOR5xLDab5JTDkmcSIoJ
NETTzbC+TBJ+wNnOSXb/XJT46tMaVK9SM7AeiTIuyu5aEzQev1gkW8Sy+yfg5LwP
yQ6fwvUtwFewz03AyP6WclcxeTmtZyuK3CzeSpTAEZR0JAF6drILK0VHgtGt643S
PgEejWgTv2ipdLzMWdJ9xvHmyzru6RzfDBl1uhkM7lxBptrPjkfPGIU2HbGjDZVZ
d00mzRhGWRhnNJDVrVv8
=wMpq
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e00888d5-f49c-4428-a213-dd78ca82ece0',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/U7KBzJn/lK6NYV7E94qRcOjmLT6D5E5O8xReBR/kjMXr
FvceAa27AXVnfTIRtMMKPOqvVibPn8bs95r8hiwUR9byiNTHVW8sVqgYrJN1V/ZM
WY8BW+mYxMi3pe2lPteMjkURM4/ffAsXPc5EoAj3nZUazDHNhbOSoEJO0k4pvxPk
5YwSxZYxWrOgs5F4h8JtTmrNAl3SsQ034ecqiO78D8yU+91afQV2i+Fjtqr+vFNP
V5poBt7vb2tcFJAgPR6jk/3JkPHzN46wj6wvjjo0q1N+zoYMca8xgbRBkIZBPEoq
J2e9Ljl4nTXhnngzVmZesTv/KGukjxH7lujICMQ1f9JBAZzfDbT8GOV6kBrf/+Cc
3kutGM6YpKuryTJSKunZ40tobH+4QSRADs/36VEv0L5Q5COzBgJ6H3uCSAvkPdJ0
X2c=
=Hjwb
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e13b6906-a8f7-4522-abd7-ae2442cb3eaf',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAtPhdrM8JheOP6BnsANjVRIcgTp/tTE2cRnUkTuzZQgA/
afxQRga2WEVol1Dk8cdQrEHPVmLdy0qAlqxWrsfdK9vfubnj+S4xQf1Lbzbs2YUY
3VP7ZRACuu7wIuDCNITGEDJX/IPCK7prSPAbJFvqz3AfbOZ2d+AoyWGeiZaqHsqp
x5M9Nb31S4iAeeL6ZdIxIWJrvRuJC0DdntKWx936OJj+agBLL+akCVQcFJncAD1e
gDh1LeBqpZkTice7Dz78iW05KxvXYuYghcFAzw4TGXQIpWyT7F9AooyguustCoKy
Ypk6JBabTckxDdFiauCM0Bia3DnhtehT6NkYurga7Ks25YbuOP/P52BUWov1SQdM
SgBMCCC+fe4j2SUIps1RXbk1bmvBwcQv0oOhZfG6fF5JCGKi8m36IAq7SNR3bLhv
i+5XWvHupEw0y5w2cj4f1aVa49nyUNqvOZeEkljHr3wzOCBtGjdou5fJg+Us1ag1
1Cpjd9qfC0CHOFeiMQ5LdwvWxWpYAu2NK0MmJcROrZ/PYoMUpaYhR61y67k6zSCD
6domkfBTKg4e/EqmeLy1GG1Jnm4wO0bJ4Rry6B+sChGjrI+XjSCEM3r/VrKEDCfL
8WmyB1j5lKnMybaEh6nBgDPapiFuB5xvxjy7jhcK0Qg/9S2zlHcdXKFTpXSPf0PS
QQFgEyqHHTP7vLGZUcALyaNbYn7j5TFhVfRHBTdo/JOSc7Ahzz+Mlrj/7sescn7J
BD0zud3JrJb7FuWmw175d13g
=jP22
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e204374a-2628-4392-a30d-291721250e4d',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAApUOqPH7q+wEk6VQAmxLUvjFk8r9LBH6YIfu1wNOihZiy
oyGUshPIEI3ahviVWybV56RWl2Afm/AV5TcVKjoAwkSd61xPmy5HUvzYe1mDsAXH
M/XjDJ+WCB1RLRUg42ukcXTkQeeYHZ0RZzI8SlN/Qfbv9QYKcsrSymsarKjZMMlZ
MbzGCpcrt92OwzXUE34gbd3GI2h+gcqqmpw5Airv93nEnxbyRLqQYFfGFytY32gH
gcnwmvrfZAhoVGBWXZbNW3AVGjyw/kCgkssb9ZsdeqQAiHc6g/FVRYEvWDj2R71R
q9x51Q15dd7zL6HbmhFJCAzJseBIL1eLfzlm0uFjzj9lM0oph8g7uumhGqwnGMV9
Zn2JFFFZGJ2v/MCvsXP4pdXiNVwG0w6WLJyZX67hwCDK8XsXPIjJS5yC73redqfM
WkvlUXuX9HbxxECBDVVeTon+GYoit3b2/e7OBd+Q74MXZzCW9N7rv1r4w8fClrpx
mFY1LuB6ZgFxbolj8Lg+hMhIR8mzjBQamg1ZuKCK72xvl8Gy5phwNIUti0E1KNdA
wZO2j5BNyVVNaGB2PfnrGzVCBSJv0f55FOe1Dm3doxn/9Bt2TCzD/xvz4rj9GfxI
Fn5KnqeP1zn+8SuhWYae3J8a2+WS4MF/JawXXfetavXtDWwhB85aPVE7gbl76onS
QQFu8fYVonsqVq2JTU7NA0zhiXYJ7tX7O4A5uW1M3U7UFytgN5v8xE/N52+GhjHn
K11yxPwRw1JqZXQB0zXHIYHy
=jl69
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e25676e2-6cf7-4451-a533-6a339c23a426',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+OQPOFMOElD0xHnpZ/mIjy/nLsQU9y3Ibcd18bHGITUCF
x/ixDnmEbgdt1eSi7TV2lWxyNvySQeQOatw/MFxyYHlJPh/2k2Ugkd2ievQ3r23n
Bkdac3bzCIxkTnM34DlNjiHV7R+2SJbx81442j0bBiJElz1Y2hEFoKAMApAAF1et
QFWTW+ZDdQVJNrAr3xPfxuULx3hRlZHb0cD+cldtqekWy42bEBCfKt2IHYTLNytZ
/qpb8ztZFWKfw/Iov3CBi6CYScclkqiamQEatAbPPYkMsJF2JPaN2lxXgXc62V8J
QeQojuiCPDtr6zfBb2Yhl9aC/Qv3Hkff46UTD+8qytm1nzEpkOInUwHv1CR6/4xx
tUS0iXnZyA02qY0n/pexUcGdwz9UYzdZRmz2pZFuRhlxxn2DzFj1wK4R02owGl5I
oiWAj6AKFaX6ep6l3C0VD4CRJ8R76HBc9NsyRYV8NXtwDwxwfXpN2nPtDlZjTHzY
pBPTyl41qSyAKOZNu8ISHHacEU/vLYSirrTggz61NBelzXTCmr6Nfd++zhW+cLn9
cFQ9Muw1UVYFK1a6Wqtbd1e6dLXWG58xugJj/tO6qRVDdItb5BC5FaE/sgD8plGy
F3tGh28y/n1g02bU9C1A8a49iEy9BVxX15D1Bq7jsTyVSuf3u4roZAFDFoY+oiDS
QQFGRk8mlKqhI04gTcIcl+vvcdsB/q33leXobnDraaU0USflPjj6AeW3/STnQbyQ
OzvneSlA8Om6MMw8p6y/6bum
=5xIC
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e2631a9d-2cb2-4bd1-a295-82ff8ac35c95',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAn8BrB7jcKbeE2LTIyXB9CP4G8P8sMK2QJShSD87A540V
IZbDBg22tOpK17M5wvPPoK7F4vmHkqc56kCzqrD1cVbABeVL0XKWcEU7HM1rly8r
3KySZr3yD03ryeqRczVsP4vwy8/HhE5lOE7lwclMxBYuWl7RW/V3CBPGwt/8KYkI
Z4fP6enJL454v9L7xaRtetcohrMc2kvqB7UWjCHgRYwI/Leo5MQmPqOOQQBpGLXf
5l9EOLgIQUh/cDgmOLrUWl80T7FDsuiKLVCH5nsIrpPyH4IMaIo3CzbFTfMi7vw9
y+tElKTIGNaFJIR9J0RFLave1iaxLr4Xxm5wwMN/W8Xxfnh82tMVXWFfEpFFbY26
gRQveglAwX3qW3NqGAUmrgTfwOtuJukeC7WV7VlYZpVgzRyQcNZv8IvHtfBV84g4
yfQSwRekZY78UsX1xwwNpso5W2TUaEL1tFt1wCN2MwR6khPPNE2QxlYSBkQ6HJbk
zbCzrplp4qyyDx7XwmN1nvz6SIG1Rd3GeAgcp4472aLNIufm2LnO2DZzjT5CfA5s
kKExNB1LfhyFh5gZPC0FhVTclBFr+GRe5UzBVbwWftF7gavFD7XCrz80p8Mqvqks
k2eNsoyGs4LkTrBxBStvuK2aQ8YK+mFslTz0i+lJjRuf5EixUh7H8aq/hzePbMLS
QAHoqeMSiux+xItme/EVQp1YBaL91DKkTx6seYC1sxxiaxE0D9cJvGlqYtKrGGB1
yf+epHzHsv5559yD4kzt3S4=
=CKBp
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e382c663-f74f-4e8c-ad30-61ba0e6fbb7a',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAmng0Dd5bNzl7Usjeo00Ryg5Ns2bBgHFphK6rq0h4HyLV
mBw3+2rVgHs11RV/ulKvZsHUVhaH1N/IHid7ndADvNDRb4Q436tVztg1RjqRPLsF
FjaFZvhxP6woDxTyg2uysB9NVW7gs1Ck1L+sOCePWdsDPv6r44IXm81GSQC54dI1
Zyh9zvHFlZIUtvJrk4jCA1leo2t3AFxaEaIN0v0zsJMKmDAEv4eEQUUE+MrCcIxL
rrMOI+oXJh0gzMLHK43NkCrQKV219h2Sftd2SbOWF3KFE2oWposK52QE5Kd4UjLT
GO0jZD8LnErCBvuMtHsys+n9FLWmYs1b1gCvKpmqA2aS/4TzIF1nV1ryy2vbLphN
ldJcpgvkhUwMzQzSkMVSi1lnSeKWtPADez8rfUs3GCloeU7u81o1fsH7oWyuEh9Q
pjfIrgZ927oNCmKnHO/+uHw9NEH7ls3pb5cm811dhhTjZlCX1RtcAL0nw+i9kWLE
cWXhkr16WRujsRrWN8dXQwpI0bHd/JRHgLKR+KHXlpWPu0XOIFHhuHO9UDp7YSXw
Px9Z/cfQn5KES15LX2r16noyt4CJdXO75tBBXZApiv30F7N92/xOjFIFLh1jbJA8
du8GOT6sMraOuO/7jKZvuMSMYS2pSxNRlrtUzdF5HaA1v6PCp/jRlf+7g5xGJcvS
QwG4Op6O2T4/oDWgPw7LrBW6jEycT32QYlLPuRmFc4Q3x5H5xFKOH7UWZyUOLJbL
hL3tQ/6aSUxIt0zp+rjE02z11hI=
=GHA/
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e544a8f0-bfcf-4205-a8c9-97d57df5762f',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAtAx8m+VJCzroalNMEfySEn5TOT+jtBF9CtJA4zmOe81H
qmY/ozIz3koSfmTXq9froB0AEBjhnzCbDpZroOrPd2t/WAiaGThI4bYCPaknMLlu
JCwBb7HQX2CZe863Ivm5ItmL80vb+AlcyS7yIjaT9X6Lycgnc/fNVNnwRO3l+prU
6Pi0Lt4ViIL1sH36VHATzSaCPE2D5e0+ifhtF65XHZ+/Dq53uWDBn17xfawLxFyP
pYTt1RNveqouII0DbDfPyl1DDRhbyjlXybzMN//LKm7DUd8KAusyiAspAp9wGgxP
W0WVUWClvVEue/Zan/+jvViGN1ishozmsE/+pfydXd1cTQvGqTM4JQonG9TzQpkg
l4zRBgilQn80tidQUyKMzZTWYjdU+8xylPZ0arytJnPNB8Xbqjb/sXWvbmSZxMVk
C4fr7DyoJtXvG++sUA/9PWroaQ4M5+qFef6sVxLoaPV8wQlhldjA8EuDue3Eb/Bc
/a1bwUkK+2Ll8SGEQfOzSGYbWebX0lLgYgwDAisnBwmPmF5MZfk1NpoSUB8Ka5zV
181wpG4pKL+Op+G55EbqZF9d+X8C9Zie5bllLdkbUxhnkz33F3bW+27vpbqsVxjW
MvH0Fuqsw/oFWyV6GaKo+zbGusa98YzTQ5T6bkJOOEviXrH8qkiovXjlPee4H9rS
QAGf1vs29jEwL5Jxupr2+XkoAByML3gZqdgrb0uLdQHp1ZE46ype0qnEcRoKWuud
n1HPh/HOEATx0Tpvv/THQzc=
=r6Jv
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e56b9eee-0402-408e-a2f1-e304d079049f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Z261Yfvxh15dhyZy35eqO4vnSFCkJ69AyQQYz7QQZIqg
pUSBBzHgmLZZu+j9nwQwNmdRIn+H6QnXWy0DC5gQEVdQMHnxVvj6CK+T3JU6sBdq
uZx8uNyYNGceONyHzQMcSW3dx+9W0z7NqJpFEUEyjC6ha6nOmzU6IyqqC309vCmQ
l08L+a7/qE8Hm+i+c+OiYglFucFxO+zXnnatpyVtlQGB3gqlCf7SG8xkbrTirS6X
GpVfxB0kvK4QKyNWOuLlA70ycojAghMJLUORK/tuEZUCQcAl4tE/iubNkQJKobiv
rXMAnuY+jcCX0F+HU6MV7DSOfseiua/idp//Ss/yP82WwpZ/A2pWuQ3/SB+9DfCd
EolIWiYVJuFPRXYE9jd8NlEtUthb2oF/EVNhOUm+3JpodB28GSnxavDAtXayg+ox
qsWLom9ScKoD9vJMi55A9U+fLDoT8Pql4se3JVFLlrfcKL4jnxDO9fBeIeUraR0n
5CsvxlqNMyyxAWv55JfEL9KP2rXYAuB38A9uu6uhwPFj+FRS6IM1jPBYfAF4CkEn
+dS30eZshgUoK+jbdGklh1XLXuC5jgELvgvihD/5dmsicPgATyi2AYI017RkFIGx
0nGicLGH64l3eZQ6rimNuX1QPrlwn3jV4GmuOye85QUUblP4cLAuRi+dcZhVGDjS
PwE0OHlxJ15zV392kbWYErYmfzdOEAj/k+kbQ6pr5mD4zNK5O9b01kCPMDWC1/p1
TJOQCC5lXA2wCotiml3p7g==
=ixDA
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e621cae4-ad50-453b-ae3d-ad30df6aafff',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+NS0WygVS52pNHWUJ4186LW+LPM2/FnBiCV0TwJJ1xhuk
vtFwiqjSNJp5Dl6MJZakNESOfCtTigY35g1OxjARvhZAEE/Tl7RNa4Lkbwwpq2CZ
U7/H5Fzo8lm3F1BRzeznEx+nm5N4nc4sXQrpdoPaltGa5wWTDzVfhuzesCPop9iV
P86dEpexI0+e0q62/reBZF0BTb4HAHTNOnFLjU+SnJ8uH1YZs4Ymnq4u09tFjKks
t7Py/4YEE3jPRmmkkeW8dAjxbU88zN8iY9Lu6wMzDoI/88I01fr7oBUZgRB0qqUY
89q8Wzvoko4KhANGt1S97CkJvlzFBqlrv2aDcYnIPtJDATG+zp81m+MMwpymwjqN
MzCMZpLsS2wB2jqNSbwGbgiiinfZZJhnm4qvoK6m4WkjfEq8pJkbMw0scj7wlR18
hKoaQg==
=DEzo
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e72771de-5bcd-4618-a038-b08aab87710e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+LjGH+xux6TdXG/eVGuJo+PFSiElxxz4yyfsLDAvUmEcX
pMxe2uNPQ4lXLXKe+ymwIpfYpE3QzixgCXHzTkA+htVtLR32F9YbOsunlBhe/n04
ZPhYwm1XosOPCg/muptej4WpoDDHNBb2OcgEeleZ63vb7knmsoiWMhIHMm+C/WCV
b931Mtl+5WiEjrHZ7syEhvBJZrlYvvDKHheRQ1JVoLZEBwpWYppP7OPPnhahL6Tm
mDhaEhygBzEHIr4iM4zu3fLcHmKoD0bmRyqOoqqc4EY2972FMjWeWA+D+c7vjWGD
c5OyhGEtgLUMBrKuOhahaCdWi/PVYkv7lS08bbukxjW+g4cVp/nrSITXyyFLciEq
T0Zr1Qtkokp1bAQmebfcCfUgbSTaQMUXcY39s1tuodequQSovbLP5fsaAVjLg88S
Cev7Oka4qPRQPNQ9V3GHZAg1lseOyRfYin/ZsZK/iqqY9nnMgZEffLQLx9iETJkq
nyYBszkgDCudSbl8UtXOOwqgmDpqFTSz8wRc47Rp25R6qtAyejH3wQvURDK3nSGf
emW7bCMeQCuj9vNiDekJql3OYndcsIhtfPcX1b0LJKZhntjajWZH+BGsnGE8GNMb
yUaAce7gQMv0o482RpjYRLn3iATJEDDWbi2+MT7sWbu8OMM46dZYyaH37cYIqcbS
QwFIpybt7I+L1YmyUYlONPUvRe/NtqbxQ6yIWHqs7kUl+VX9IQ4P6sVcDNIXbSkj
oy2KsB9/FmnAob5e+ZyoaL69JpY=
=z61u
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e7f04986-7cf9-495c-a39a-50d6d99ef8a7',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAgiLLbO+jT+zXO3TCWFKINCG6qnbvo7JGpdQJdhddev/H
vp1z8QeyZ/KWm/P5lJBcOXqjJqB8+EQvK7nJOgtbLSdmxQto6UYrWjCgzSZQsKgv
3pDa0VGp1SFRITiXefP1MQSor2UJDkQrPyi6uGiF7UTdaS+t73dFtaHwZJwFuRpH
G1os369zJU+/4oiIy14cW4P5rhcd1WsFWY9H1sNmQyG5hKdkSPV4uEH0easKqXlD
wQpwwdGMX0YjeKSZaGztCuTw8V5nsn+pE5Q6R9gTaQFoZf7W6IomjokMg8mFSEUc
ITSeGo8ExLhhBDzC/XoKzrV9lN6m/LQ1hM49KJsxKCw/2mEIW8GC/Oq2ro6e8L29
31ZnIQCIc4S5a0Wz/PclIe//nsIjwc15XakV6Ax1rvDQ23ZSrIHQaHyy0OTgg5Ft
uF/eVdk/PVc4Zvg4lo/TAjK4zHp1SqL2sk/A9F/E5LgYyf+G5dt8CH7dUO3+DuvZ
moDUxlZMh+EpR6yCMoAw5iqMGkeidnWUs83fUn6HwMdG+bwDOfbKvnJZYtcjRNwo
6lMpQwQ7TpIYqzyHSzyqQHLr4VsB3xQszyYeygruHUII1s8ieFRdj9UEIIzUhksI
dLcTQec89xzWg3mIY7nrNguV0tqnmlj/EV8X60oWx34bSe1P5NdWhvTwxWZmTZnS
UgHj2kTCZAxY/ms/q+rCTsgoXDAHCbZgBcDmPnHjMW8SzkuUWIrh2fyjaTZyGSR/
S5x/uStA4+bt2pKG7SLiRzKgmJPpNEAOK/MlYJfbZYwmwQo=
=6gbY
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e9d1b417-3572-485f-afc3-b5aab853a834',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/bAuz1gP/UoQuaLIFoVQU4jkQT8zkX+r8xC7O9a7qG03Z
tNfTGoXM2qXxiJAxYpy/6WhYXL99Pn9nyTHrq6gkDUkVkO+dGTnC1SawbRy9dEff
qZCMdY5W6gsTvSWHQTe43FfBWxxO2FdxjQPM2qVwmIOeGZN+wzeD0Tcwh4ycNput
MuhW88lLufVsxEbh1Gd3Nzpl/VD8Xp+cMzakwHtJJGBxksrRu2uwOVc5q/RgWI1x
cZzdcmk7e2Q6IjJPcXGmXQmZ2EKt1GI3MdJ2PS6TqgDETXQDzsOe+CcE+9fbleW1
nX8CINFD25wE/bFIGwxE96jsLP0yjL3R9h4F0J6aBtJBAUVXpabk+sv+iCNF+5p0
7hZJ2yVHCerr8H5bp6ouu5Qrya6jq1oTAH6iD4RO8XSI8WS8c5BWLdxE8qoj6E78
urs=
=y2yl
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eaca304e-63ec-4d85-a01e-f88429021447',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAh5h1zifzWMEBYpOU6q6XNxmutgW0hzbcWz8OQcYHfYtQ
DlZux5XJZ3/SJyRHZ4SzrcgpYAz9//koq8HlTviGkPoaVK6/KYGpRHG25J70bHXm
+YU3LA6Tm9/+c7h7eqtFJKeT/YhzojuI8L3EOOTJJKYs2Pgv6Grm0617RQcTP49X
Q3YafpZ1pUIHp7AvyYZNdaIsOOTKAY0fJxvFDtGXBhXVf95z/yljRNaiUW1kcSEJ
jG/AY1+2tsiBQG55RiImk502yhbocn/xQu1PkOMFoPI6FN8lfOh3Q8gvfWe+fwMp
hm29tM6GJ8T7ILjTQ3Z2HHiarot5HX4PeubhDQd9wnMYI4fCaCXH8MDNsgUvU/MM
VQU33krScRojaxjHtR1lW59rGbpKYEjHYvgU4BtSDZAzerIzKihdTDbM/gxHX/Fx
7vCLw27mkfMCiHWuRp6fM67x/RAyZFzwtP4I9CEsGQDjJYZ4bOPvjrPnmlD9qU83
Af9gbb8w1rUdc954c2mtn/MgQgYWzYUUZ3tOXx3YkHp3puEzGDVdCkqx3xdTMhHR
8YP9NtcDOh1CZ8eHuCGNCpUDwzS/1NAUIbGIz0kVOUTMGSp1AuVi4styPFTzfu9C
omTvxSrYvq/B/GF2U1uKO7XoROsTDirtOUrIRvRAJHJOCJeqYdIoXE4bS1xy18vS
QQEGT6ATHTxvX+KQ0Sr6to0qh7+BbBcC8Jfk4jC7StONH9n1gStTbvziGoiPj2kA
mR10YiwbtyLYHqKd8VAgg3hu
=7Zdl
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eb13236a-2e80-4f3f-a1df-108def731788',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//TZ61+/GHEjDg0MxFUHaKs6Epgzg5uTEA7JwYrj3NgDuD
62SpVdbyH8omb41JEWHesAbfTu2vlo7GzuWG9EW8XiMbYBq53TRVugQM+qoI9ytV
EzAUXiWef0isjouInnfWFLf6/SVcumS9Jh/cZn+rkv/6YnsDA/+1niYLLh0EiQzl
8yyQEM4vI74sqWky0JQ9SlBVzMbbLQ+0HVlSOZliqyi+CSold7q8utl2CFlNraJ7
9IR0lyHLQb7X18MJhxx4hRckqLF+BeS8O0P64St18fk17JhTYQXmnXeHSxgcnsTs
uo7TqZP3Hna6l03rqNIqAxhOxwZKtvLQ/l+Zhc9rsD39pRiTm85F1BEBmcdLAzN4
kqMrTlQRowmoQKPWOnWvKyzlgio02XXJhuHavh00JeFl/insTiSs88/GRDbyLEw2
EnxTbOSRZFKs4brUXipvv7TY4gkTaiEMEJLlAZe1vt7vIe7xgwCvBfXicL9RpuEW
4gWeRTDtiQJx+TbDCkZfr0VBZeoPBryJd11J1rPcOL7I3tNF1HTbu0lwSVNmlQSP
7EUoEZDA5OiSNTVaBYTmIdtv0/NLYsplOHLqc6eVunJyij2SKpsBAAdeoYbsBcGa
wEmHd1GC0DiUbIbu5jeDjm4TY9q1CPf7MsifwdntoW8xpcqTUF1du6K5MW2ZgUfS
QQHSuUTtig5uulP8nfyDWC/RrcT5taxVZwp1VkhSCsIz0MQ4iub3rq0Bh9nx4fkb
QB+kj+IpJ8hlk649UNIVv5yY
=KXSq
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eb464c96-8703-4b18-a848-8ee67b70a1bb',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//ZB0zw/9IKHnw0MtGkI6plXBho3Cm+doTzPRZ43GFMr6K
6QtXK2nnWY3SPDR5V+U/LUainM5zaDoBt+OrduajVJ8S8VVKE0OrVAp0ZgtwaRve
59gP2XI8xw4BuPWf783ZeeTiE7sHGeoOANq3XQRcCS8XZ/pvSEbLRBDZvUNyxZek
c+x/V+VbuiSj5MUnAH8h3wwVgZLzSH/+7QUMx5UEjfH2uMlSIXcc9JRQoAeT9/g9
o+5Zm+tE+uFV8dvb2+LnfSZD9Xrd5uyOti3e47Jykob1p1HoOEH44pofMkvy5zzW
v20IQF9p7E3qh/f/Cel1gpTCOsDNuzYNdgoDezKNcegEACK9ppXnvPaFmGDts++9
AYVEJhozr8ItWqUwsz73DgaUDH1U55pBrbkLdELu8IPmS6KCQhY4FJI3TTjwBPLP
FdMlsYSCe/UqTC2XG+mcDlhBFNv448Ms742yEczivNQHt78jHnlNeI69QY0x6IKx
WS8zwJABeayFfH6ralwfP4VeSQ811iNlLUcz6viY8sncsgFEubcwZ9gFD8EA9R8v
/02uWjExDDOEU9nTejNcNJnuEPDzUqJ4+DTbc2dn3x45OjQwCpaAun7KoDPEsVx9
b9PVD92yyniQYzEb5KKGrYXXuG5ceEMyn7Dgiih5Mt2hsGwygCpJEVYq8XH6M3DS
QAEFJHqtYsslERljGa2OcXcQNCRbbsO6WS5657sa2TaBeDsKTO1fY7y+oVyjetUu
1BbqIx4vST67Ib1wFmLNCKU=
=QYyY
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ed21792c-33e2-4f75-a2c4-5efd452d9e9b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAhqaV0RoD2muQVfC4SD1Wwvoypld8ANne/TeTDgejOrfh
s8PxdpvyJ+zTUgcfGVtb5//eMJPUMRYz3yxqqFP01JZeNjy6aH++UQMtIk1DQ50+
ZFbkwBnbdBoJ2n1gxTU+nSfoFY+mT4OA1Llqz30NyXBOJ+d1dtavjy02agfRkSkF
bfyq3+qTPChODUUBBjR96r56MgZL1ziRQNfuAAn8w1qWs0q776hqGIENywP8hJxf
Ojh+VyWDwW0OiBHNQcZVVE0OvOJVOOw+77j/qruFtdIpqGNtQfGCWkblJTQtAAXj
MmClAZJQplXrFBAJ4w2GMkwxi8KANdZeqNh+hdThaEwSefeoeVnvc+WYmYc0/Xcl
RjYk4Y2mcyHp3w9wbFs920vtNKpXIH+taD/cF3ZrjmkopCkXOri/JkK5bADG7Hx+
6Pqw15W39j9HE41//JA7M+BXJNtAB6RN20WKGsskaz18DB3LX235DtCo5B6iR5Mf
WyHWh4yfWixHpEmgfRV97dWptJS+Kk/DRCM1/Kv2kgnmir7nsbuWX+QK2zPkWoLU
VQRwshJ3F7fdfV1lKpHJ3BBrR3fWXNBL94/Mn5c065FIiXnrkQyVYLTitzXOgjJS
64x7KhG3aWyLUfbgEZ4GRUB4jOTyiN9zZB64An5kR2w7m/omapN9pPsTGidU/d3S
TQG17JsEz2TxHN7PyO8g5QuZ4B5RHexC8MK1JmhcVXtnZOfJapKIfEFsjBHDTSSj
ZKUocGq7SLMY16ROm9NGYrGHM1zoj5+4/ETd8UuG
=3Qsp
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ed57c310-8260-40a1-acc7-af2d4a69c4f4',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+IV/C4ErGvvecqX7M+0efCSoMBkUx1Z6Gdv0oBvNk55eU
Ww7GMdGP8zdfi4OKUmGJxx7gaGr/Ri1zb3YRtuCnkHGvlnfGCZfI+Nk0E6+Xm5N3
QLzcLujiMHWSoxUglf9ca7HG4jEeToqjWNbL44A1WQ94eIHA9z99soZmGks5N2Ar
VQoMVtJG+Q1iKE8lIJwZ8hXFaZf0qote5Pq5fl438kbKTEsbpqsrhy+OZ8jI0knV
kZMGjYt2qaK/+fUM05rNG2+0bjW8AlJAjZFUSRnF8PjEZNHpmNGG6VO/CYHCDHWq
BXCYXMiD2WeNpb1wCj9RTSw09yOWMGJoXNlZyRv+lSZkEh1NPS8MSe8BJ/FUf0nv
8luK0iq4umJTvyGwg6zCH6fwRKnT0t393dWexFDX/HX/K5rf+gEblcnRBoISR6ut
oeSkeF7HoHhU+Y9krWX6yWXeAnFLkxqjc1y+t9wSj7qhF5Ywyo4Y8oKkB1VNjkKZ
f8i7ezXRCsYMD8Y4+fe13Kfm/vw3gepxaDRV+GFiwo5o13FJ/Ukt3yeaSZjFXpSY
ZRaSe4+TV2v83VpAlTxcrN2C5pchlXxeXncSR9Y+ju/vqF1+iOi+CMM4keEdpx4d
Buo9f7lTTqPgz42BSGlwOIDmFvxeQpu6O4lKZ+vPZatGLgFjWdYD7tv/MetAGWzS
RAGNoAnBjw8aoxq+4wmZEWH0Vo0GhO+Ht20z9msSiqKYBfsUXBC/p6nZP93Bkl6n
2WrAGHflq38EoUh7OdeiIsMOy8pR
=QOgI
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'edeae065-0e7b-4645-abfc-dcd37cdf54fb',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+P2YkJDx+I9DFhBT07Hbak/p4o32vS7wZRaO/zjWNSKQ0
6sEC/KwVgQQ8tvh5WiIUDCdfv6X4OQ6z4j5O74hr6k9QPOf67Uwj1N2F/dpMcN7i
Y9Lh1hEDZ8G1hRD5tlf/2WE3jqiMjoYcQ+fwJRi+cldvtNeVU834UVvQhuEuQW21
86LAvQOmyl+TC3UASqi82VPpg6p2gLhO19M+qSDrxXO4xPRQXRppJjadmVCemOqs
XJsEoNNYJKrxg2V42i58ETZ0myc9tePKPl64uj7Ac8YW8Pv8cQu2G0TGRvlMKwjJ
hQyZr2bOdWb5MtI4ni2uqANTi4Bv6V19F1CBIBx33eblK552DvStwIP3IWY9Gk+w
TtgDi5PNUlPUZPTOx1TTj63zKVU9m6xA7Fk1BaQTq5/Qw/L2mT11wO7G3ilxtDAp
jpNBf4DbGanUKjPT5LxK8pANJ+awrd8BrMFbT+w52kSIJ4LWZIjI9gvrBBE/HF9l
BKqCn0gsH1o491wwJoIrBHVNgfIASHSz48kbyIbVDRWxZ91mZNwFL9mD5mPfUdB6
w/DJNHKiyCBWUAifM9KoeyIfu/UeeTRq6VwLzeT8Wl8FXJLGT94E9iySXQ/pDcMk
VbnxggjO+4Gap+fbqFfEW5CtbKO2FLjJlCZWhOnZ73eYXdnsgepj8+mjQcH93KrS
QQHE1plwe6Z50knq0ZjO9kNAQU6yyosRniUX+TxNmPmMCGW0F3hwP1lSKxM20Na9
BCkzweCjexsDBleHRD0ucTYU
=mkIQ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'edee9d6f-62ac-4486-a8a3-d562e7759f0b',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9FVN96PM2QO7YszNKXBoRKjGZBnRScxH8PJHa005umzyh
ujr760cCYu13IyvCpk/8WV80dihs34KiCOpZOAPhaxnoLYE3w2wmlWHOXUZ4TVso
/dXOKGs4kydkJNSn2K5/qHwdCFNgi327OENGvaG/2GlQbZlsBKR4ZgeX7XWo5afh
ggJeai53L/knhMEPF90eWna0+Om4vgdBnLvt7j6k1c5mk8XRWZZwbwVLjLGeGWq7
dL1LIhh6XsOBNJIL0dDwozRBfJooccWRWRsKaDqZTmU7vJfpPo/LHAvSSCVtbuTH
JCnCn/facazNChuk62mAQVfFXVzmikZRPNXMyZsBKTQ2Ec7BzMHsPJDKqXJIQ3mg
Z0k2R5PL+LGAaVrpF2UF2ZLqj3vFVtvpqnKFK7H0W+FjxeeMdaMCblnbhB0zEo+U
JqmZ7Aag8lJQ3LiL4KlMy83b7TTpdZlK4/E8917XL7Vlac4ga8HwNHzYQfCGC7UX
tnz5d41SsiQxx+MpedZbIpAW4tsoX4GnLFEhxPngpvVPbpgT3o6YrrZKFbDX+pgw
ErG3n+BtE1KhpMSDlPk5bmER1IYjmdxsJt+5rJuoXEIUfaRBcZ7gAl5kMj1arkP5
jBY44bWGBDLDua70Jr+6fgXuegZfmxtPmbv4Y4lcoDyr8USXb5WM1t65swJb4QjS
PwFx31f8nuVhQcWRcIu8x+VOBkKIV0KcrlOHbIHQA9y4o0kOnde36m24f2LUgmDj
pB2JNbxew5Bpudo1mldnvA==
=sZOx
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eeb8969c-ba53-401f-a67f-887f612cc920',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtZ5yyKZjx+UURqg3JuiwNcFp7l4nfVbatkjMijYRSoqR
591xb3GFQutUCRZVAWKAxw4j8w5YH1E4bIpwNQ1sHCldSpv5HD/xaj5bm5xwyxet
cW5agAtgCxEqxUAv1E9OxKFtT0XrHYKvXAUU1efes9xPiDCoa/KCoZoET7WKAYzt
jBffYfJgt9gZvSPvffwmZAQZM8WZ4zNiXCcKEM1YcmRO8l/+WoAXbkpNtyxGPqoL
AnqtX14bWCBtYkbk7vv820SrZ165dvjN/YBYDwGSsvHUqH5tFaRc1fHLD8g8NWvs
EDWdiNokiKV3eVAS7kfb+TgtLVFf/wbWChpjdIrEFeeC5aGWW2+LNJ0halGTzbOh
/3l/dzDKLAjhArCMn1h2ghwryJcBHog0feJAEYlD+vfC3A1TDwXHwfNtI1xssH8O
thqVodJmi2q4Jg+rhFadNmBkUoh3fDwwi3uuku9v8QJu6Tf/NZXKEXOz8RhIJKyj
EDvc2CGYvxr9XYufagGr2ioa/vWR8Z04wkJfWfYrigWepZD7ZRWWFCgeqKCylV2Q
LksEjIFKdwx2cG/AGEv93xgEnFMSnEMVfvdamt9wYgL4G5sYQGCkHamPzKrHUNha
pouk522o0TCLl1syurTDnR1XH37/dJtr3ojEO4TY9M0oVlZ4+iP1kwtYGSjCiXbS
SQEj4GFWaUfqopFugnzKVm0nnMTEeM8YjR9k0kigua9BQ6/B5xaioW3ByfQWSgWS
46GMkPykuvmqvLWMX8HQ4zjWqpcX7drwJTs=
=ZfP9
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f390fd52-8465-480b-af32-49281a4bbaea',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAk39xk3o0NIK1xvl4nI95u9Zv879fwSgdDKc/h8xlk5hG
bIU/1iq2DFgFPb+9Uj7ni+cZzuKWQ+v7q/DIQyVLK2mD6jWekIxg9Wn5XOJD71Lr
Ci094noHf3EkfdBKJpgB+FVKQFWattJvDlSk4z6jntWoLJdv11Od/ssY7CUxDN8S
22p/7WpuKCxBpddkd70O1vNsmCn6kTX1hzNmy4AKKGnSv/IzcA6/ea83+YcY8F6b
ZrrxyBz9AVW+U5ji+CrF6xTIXSS299qtc+I+ca/8G5IBufwsZmtTGD5u7McEp3RF
AFoZMmV0ldEDcv5t/Ri1ceitRAcmpWqe+8R1gYItd3HR6DkX4oNtdKIoc6XzEXFD
WZbJC3wCLtgx6WgwLLkuWuJ8nM0AKiU/7LrKsl8RQwDmmoAMyecbbYMvMhl7MVl/
02BviHMSr52pgFLHbk4c+u09g4oP9fZXBcn3kZA1dyhyc1jovlczr82Mw5VMxiES
tmpl6xPca9DSgnYzgRW7YbjKGzAJ3B1QPBPHuL2cSkyTkA6D+IdtrSXmZp120j/K
BzOgT4onJzXOG4pWoH6AvBvfReFgRpKc8pCVUHVUOrMK7doS0uEo/Yh/SLA2sDBx
wmpgKbKxEz4nieXxF0oacZEgqIe9V/TwPYKVtoDFO9+jCO08ZArsTy5xVq/umQjS
QwE5Ytk4whcytgytjsOaueDSfZ0H/4nubKkQLfkuEMxKZ8FeJwhxQOhzFmjbCRUZ
ZTNWshH37P2Ys+8vxc4pRzHsK6s=
=wrNW
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f68f8ced-b81d-4d55-ad72-ae643192ad7b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAq/1ejr63gktCVlsZCV+8O7h18jQOdmbYQ6jdXr+LCfee
oRgip+WW1LYow89qK0sT6TgZ9VBjI0lI222vC8N4KPL7/IAgVNUcRyOfIyZFDmsi
CmUle8HuOZND0zOCNv1KNjCzVRHxlmhzWXRaP48b9ZgC7p4gIuY57miGq4PuRlJ8
L3Xse/R0luLFXnaWygnZu2nI+Im53XuuFh1Mh2crx1Y+lC2jnVDvpPrY38gtOk/V
Xik42TJbTutd8KiCgMgWGjkcxoU9PSFZWfsc5C6jiWhdUI6z1KicoAQewpKpb9di
Mm/RzIsEKkCcjRr6ieBBqMW0o/jUoB65ooxnokoWuSUmQ0KdmwbqaSAE5+ygiHFQ
P21GocA03Cx0Vf+D1/twSObQIVfLMry7nvKNWqjH0xsCos+O0k6A4Y9sCG76rzpS
/wYWllHZfCJuETRZ37yN8fX+/GntM8QFOsx8hqojp2s17u1czpg8g9F3/t3VzUk5
iFg1VFyeId5ly7gQpMvdsJfRO+5Bulj31SIrlSs5lBnunC7MY/uK83B2TpryFTPP
YtoXzul6M8olPcq3mT7u9pAo2YeigBfaGoinQql7g7h/v6xLX2PgSmpGfF1v1OAY
eb6RSeXcaEHI6Bz3qcxR0OFCBJlgUmPyh4c+KD2hj906ZKqUEXGKCx3eXrAF2DXS
QQH+bB3IL2WKCYpGJMh6CCjvzDFk0OUR+NMnbJvVcdji3EcuerlaCYI4Evuhx3Er
dvSvqt/TwltLvzn3VRO+P+B2
=m2Da
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f9bbbbcf-9456-4677-a095-8831f329d21b',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAreQmSpxyeoum83HS8vNXe10tjaBtEzOLgevqJtoaNLTK
PpsJqDbMo4vX7DSsmObTXe7lOuF5hYMZgY308c+GnJClh6kZ3KrLMCLItGrGhA5s
60B7UBeFlKNE0c9/Bsk1GlYttLESADcbiehVW0cwS2dicnBwEXJ83k7W0IT0qU5r
WEXw69gLhv9uSGP/kXPpPRSTRYhI+0Ox0h/HgHhcb6hXA2GREN3yFkeYAgU6sVRh
r4SuGRu42A9chbFgfNXdkMjNYPPSO2f78UhCQxvyESp3n5cCtuvfScCpDa5LyPgg
UNuQKJulYhb995WwJPeHcZLhr82wFKuxbTsRPymVs6MU2NcSHaBw9yta8R3xGcoc
lzTKohuntfbtxHQxjxvw5trdwaq6r7ZqHO6ljPvbWlivXxaemGx6eqhkjGwnMqMV
R8ubqe+RpUkdwm3670gXDikkZUvjPy3FnZWQO10JpPJZOFig8qEBgB3Pg64I15t4
WiwzaIuCbH6yobKhV/dkamXATTP7cL1clXraGNQt8klULviRA1CVVTmyBrc7So5U
1DF3bisSSKWlqL2+zcc6Ve8FdDp+8LyxQ4pUtbWsxUpjqWBAsybco0kG/lfO4wRx
06Ik2vlHKJ4TzchxZ2H90o/zSbw07Vro2V5NmIYRQLM9evtnXMf2U3bHZpEqHL/S
QwGqscCOCA+vtEvMZqajy+nIz9uFdfi2pOdRvG84Hqz0pO4Go17pjKx3cZJxIpLP
Qi9e+67fNHVpX9iyWVZpic9XDXE=
=IzS/
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fa616810-ca05-4362-ad91-3a5ff5f558c3',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//RsJ6byJMg5A0JOoLQxNWFS/v577GlsGOXLo/fOx/2dXY
0SKL+P52O0PVOGcgmh1QjyMWRTtcwAxQ2KS5r0oDh/zcJRmf1wUYiCLP41Z1g4NV
awerR0xzmsOuDWqiN4wegG7qn3dKmoyYl2iCf8UjUg+PIh2WnqQptLlu0qSL6apj
6md0W5icCcn1+gN+ats2EnPZsDybFtu9mWgy/ZFyL3J5FjuK+p5beGbjePo5cTrK
En6GvQqTLFjLJvC75DVj1opMzg+JdArjEwxoGFv7OIFsn2Jcs6brv/jlZ/tBdkuQ
s8mhtXsFFv/GzBw7TH0nIfPCxVCF8qDTreMeHO0YzL5D+n7sT0LtajkYb5tKeF3x
VqzWTJgL/hmKHxhETLmN/Y8JxnXGp9v6s3Jdt1XD8e7XJLk0Qb+2Fkc7xU1bbWpC
CUjrbnWquvQH2R8W8CRD1m/eUcrBU95ZKRQF0Jk/Q0LX9OtrY35nlXI5UWaKrF1+
L+1OkwUI8JpS/s0WSj9p0LWS9Dpj9pK9/Vqy3Skb7V3Bs7PKs6MdVFK4nG0Lakf+
14QFFOjmLBzOgi/vI0n80QWM/tT+9fOXwvd5oqgeHJj1zCdt35laNpu/Oa7gtt6z
IDrJd9bOWsxOlF1J06RawNCZ1Myf6C6JNgWU+PexASOSAv+tJod/xPrVIGJb8YLS
QwGeDAlao90YgVVd01QXlYkhUHetIe7/7HlWG08JzJiYD9VphAtYUczlrODqD959
jsuj5RtBSzw479JWFkgfoK/DLyE=
=z5UO
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fc49c293-17b7-4e20-a012-614470fd8273',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/6A9ac7nmuuh9QglEY8dquaEFgNdB5SkEYywyAQBfY7y+J
uB8sfjuXBWY8I18mT07nHJTy1JSUZ348Ydl9ZV0GXL1TBy1sTm4OowodLxgHEcxe
biVAINXoZxd73RLLVAjCiXLCWJVrWtzhNpUWQ+19Oz3hxWpYettOLZBRHV+nKpQz
69WAD7RT6aJphYetv1z5x/bdG8XwcTOo+Lgb4XEqyvbVRs6fIam/0zg+KoyH7rdd
CZwNQ/n6P4FbdKDdgZVNbdBHTkJhKod/jSpA4p7s/YISHcoiZIBl5opfecQlaOm7
VnP4x9/lSzs0U+y0YgkFIW5qKvh1YAWshMj5dpVY4984SeIRn85OQ5WUPBQvnIQj
mAJYKZYCjob5w4cO7QK78tQ1R54Y7yHUoLWDbgZgR+497y0dLVRI/M40NONjCM1F
BcFOrfa6mLu1pzZW3im8JL6t/Z9/1C00PiL0mvqi9NFJ+G/6mFRRLYb8QUQx8rv4
iRNF+tACwyXFslq0AAN6w9V+oplk2VPmfiSpOdd4H5psyWl13bTA32Tx4Bq3L/W4
OTGyy0oNJFM2Z/PNPr4lQeg1RwHt7CelqhWDA7wYEBls+eGKjZWeavpPf0SkQyYo
BLOBzuIBpZXUwZU3vTWPaQukzM+5Vlf18JHRZBn8ky5jb1wstDrMcKkTI224FGjS
RQG5CiSLt3BNe5TAu0nIojip8D9vi4ARXQ9IC+V7vhi1VHj8wtRS7rAaKnJSFqdi
0aO0qqdM2sn416Fa2HIjLUnJ8A0Tbw==
=Wxcw
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd95f93f-6d29-4bbf-afbd-53ea01d058ae',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+KiCoYvMQ+cugXQzLrFG49fxl1ISOIP/PrGzImJpSrIgF
FxodHFnMgMA+xu8Fo5cgu2+r3Si9PNWKJQKHzZTd7hRCFycEKIFarS2lXAPoD50/
0f4R9Sbvzy0Ss6sKkOdkSCskaPztZD2v76qOnBpNfJIGK/G1YDV0xP2yFml1l9Fn
/fYCxUpJIaWuEnh9zy77DDgosCjrDRsW6vXLhFN/CiSIzSwSdx3qfXDDD2b7YMNK
P4dXVGTPDv87z7iB1oaNpC5p4+Qy735P97FaVdQlG0xwrSHNJPsS8CpeBzjVTySy
Mq9w5x4Fq/rJDKOVd4OrrKSIGndj6w9cUVcGy61k5JaTIKKVYjMuanD4nEa8xaVN
+mLkp3rMcfkxUZZZL7Yo198Ccz0/Rh8P5V8nnvb479ilqF0MXCQ/Ci9HPQtX7bz1
VQReRFB22nC/i6lltKPtQx67fMs2FWxwodUzxI62sjvg5FtbMhs65s9bv2MruZyB
lhplwde6gRDFSt4rXq5zWn8lqo9FT8J+0eg1gEHYiTyGK/dyA6qdORQ4ftf7a/jN
0Sg2cyKAc7SiEDfj1CRL1qYL7C0CtVtTVmR7WAAKi4tQHvR6NPszoCuLQqc/BJid
B5cDlfqAeW6acrdyKleGCLxGCcryFKC+FVkdXor1N58F/0Xv3IKkCXH9KrUzuCXS
QQFA0MWzWT6rSmznd3Kc79kPqvPURc3kTLSUSt6KJMbCc+6D0n4JAV8vV4oHZ2JU
Q+gRJQ0JE6E/CFqusHW5dGA6
=AaZC
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe091c2d-d98a-4f33-ad64-806006ef316f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9HAn9yrfFPE5Li3q5EJEYWK4zz3/bKNArzs/kwcVe8qV9
Tm4CxoX76HN9j+s4wEn/HEJhwvi2PsKQ6oSnVXoEkMTJnPN96fMUIUKXFaK9r7Ft
k30f/ctnB4cSjX96/ZAmFWvjyZ6lQqC4uO+pa8tIzSc187NVi/elhz2hnjm0ZU1O
UEB3TDIwnVIkPQkHQyKeEhEtgpSA4yBmygRy6Lcz0YMvExCkA0rOGQpsgfwTXGSY
r7WBCfV8e9DD+stQP/0erlozkwhcmIebzgvHtazDkkJKc+2WRUleE2/M7O+KsokV
EFDg/ewTJ2RLTIp2yGNQjiZsFCBHEznH5G6mVl5RSIXP5q+nrYZ1mJkWGq+CX3f5
V7U1INjPiWw1NSfbgePOahMx+vsa6EEfU5Og7JelPsS0X1HoxWo4I59LxctyIL0e
Y1iIa7jkIVgUGlXNLxIYQQaSteyB+UT4QogtkJR/EkEMN8vRHY7VFRaIcdj3vzrW
C46I2nj1Piw55w80zoR0iXRf5ObEfsScuKKryENA7BT3xa/+MMoZZWlRCi3Jtome
9IzRK/4u8PG5XV9Cl9UqZgBwmJBcbnAOACGANDP9g+FLWp+rWUUvzei9aF50mpY6
bVv9cx1V1iZxpvpi1eXJltCWS6LtpdLKfbg9GFmCB8lnVnMeasGkOJ5m+5WvimbS
QQFj8OcxlSDN3/nJXp4sbb32uQAeI7KnboSo2u8KcIvnD/AoJ4up89tor2FI0Osc
dqNA6YhFrXuPKfNsQlBaaGSU
=7YQP
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe326444-170c-4332-a937-20f104a5019e',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf+O7HXwFvvxBIXIHyuB598HGV1M+70kokbkrnvKcTl2WmO
p8fi08aqmJkPjpFRUDYv/FpkeiUNzVU1TsI7kk6LuXeDCorQ3Aepiz+qMiMahwjH
Ij45+u9ayErzoQik4DZFobfBTTf+tBbrYua2iRLAfkf6fzrJ1IE1ZxXuzQmPGKB2
XlEAo28ycbIgfO75qOA2EjJ1iP5d/BbLtxS20+9pPmTm66jQmuqID6SlVhwnUbGq
LaoiKQ3/T3Uf20tzY2tb0JXOMDIMZba3Ie5+SesOoTCN0P8seaGl19eCcGOJGk2e
m/EbJQobVpMvO7lfwwjJCWv6/Qo8AsDpW8qMMxrZBdJAAett+lMdm+yaxaZ2UI4Z
g7BgzsBpA1v986z/C8OHNtwt7kdy/hKCYvoETAQwt6ZE+kim2iW3bJ1jqe7hRbl4
FQ==
=MhAA
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe6b1770-85b2-4684-acd0-d02974f7250f',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAhmxPFwkTIjQA1tS4D1PbE07pi+XX8Ped7CV1WNX4nMFf
LbiIwq9CCO0dVUvZTbTdl6gt/NkaLGcDcM0jyXb/w/I8LxzQlCVOhP5/pkvegIAA
dQ9WX3wCT0zxSV3NiEjw9ZwrEE33EJfw9bPeVg4sT4bB8BTFLmrHGT9E71uIVde9
sYhBMIVRJLM+G4R7lyd+P6AD+77EiXeceBtftpVWC58RgB4u5dJlheRu/ZBWx14U
UyQ6eCs4keJWn6vtjX+ErykA7/kb/rY54DaNU9MglbM051poma4jUtGOgqzO5HN1
CIsPmVpW4Hs0mVF3GqY1cYbNLPEj2rR/CoAvGNZmH3N2Sh0i/740/eExQebpDE0r
FomddHt4cuLAIcIbwZ3XSCnIyVGz9Xq5BSSoHp7p5Y9EdKdxPw+FFiQE/UWOedSj
P5LrEE93iRQaowWhPjmiqMnCXOnOGxEyRZT7rBMX2MZlomX91oUgRdHm1AFXg5P1
RPff2MExbPeQKfcIMqWup1duuiQHUjokvfDysDDzgAyD5Y3cSc1iFGvVVlfdRUoF
BeVw6UNTb3jyHxCp7ax+1ZbKMtWtlM/CU2zszwNwDiL4HNd3F3f0m36qtmsSCUNA
rFoBbwgFOFeOzUFixboxxvFgUVn6UQU0gJDqhcJyb2MU2M8l9Zd5lk9ny0VHAizS
PwG5jNByEZdJ+nE5PQOKq0ktqgQAxTjc8goMg/pkjWESqDRlToQB21VNoGjdIYaU
WDDfaF2azd3Mh8sHWiFMyQ==
=14kO
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe94efa6-6ce8-4797-a296-133839fc8119',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+LMMHjwlsxUEd2lXs/SxuZz6Cj+6GziwnT0+vfRvC6Knj
EO20vp6qZxTbla8c2TJwVSNzL4wLHev7fQdaz1bgoFuuKKLLXpV3QV4gX2BzKm9A
QJ8VQ909VihEimY4gL+YbliXDd05Q6HJUxIUVi/9pDwyN7Iqhz7lt1r9GEqwNck8
/CKxgXe8qddS38bSkMKGHVyC5SMF97ltM+5Eo35q4IZo6fZ4LUVivGcph6OvXv3u
I0AO0SsNKIj6mLLrOMoXKWCCir+EDfLpTGw7dGd7y2TRoA9iS1TJikSFSyQqIXyY
s4/JJ2xwHcC2iEE8NPIhL3/6tDLT2Y8gMgke8NdcwekiY23tWCBpnB95TDVo9KNo
eNONV5JdzZmLGaqaT+iJmeTDkscqdJJRjhFDznu/HEEoU1DyYyZUjXnP4LrSNCiP
UCJOdMjZII6f3fcq4EwI1yb58I8HsgBamV5SmobJPDzw3GAWPjqYdO3b/p6OukHH
WANb+FFmYYpock4UloFj9LxTdpGDbRcqmln0zNgLwVSLcrYwNfycI4CrjdxFFiKl
CulpGnoAu2oAy1AqIcOp8pUixR+DjAF8xHGbSLL7XNdhhbJEBR+Q3xy80PvPpvh1
OduSaQQrzYVHviguuBH636hCSW+W/FaYILD1sWh1Jt+iQye+ZnCTpgfLlNc9AXrS
PwGDlgD7t9j8VH4L/HnIY3/BBe/K900b33YHJgLWYd9RXTYLoluIfy4BFiguuc6e
0KPquEaDltKLLUOdJIpzhw==
=wkEX
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:27',
			'modified' => '2017-03-04 17:48:27',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fece7f0b-0018-42a6-adbf-cd19ace33de8',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/TAf2qVjqpAFSf9sw7OSOXCpdJ6lBoblRIwQDVe/NU+01
5SMYq9ywqzskCNAxg4QGsszVlAogtkPhTf6uHzn3/wCVH0yB6aD0BUfiQWPxHOlT
jMrIV7p71aE/D9t9RlZgPVRtVk4yqbveoLKuowi7xHJEyp5JPu8eCQPXPBOu7CRz
1qnJNU0g80ehAOk3JinxKMUUURRgcj/gOCI+uXH4DI2m2knzyJcy0hirMt8Ag6uP
heUjGU1KBGzoj1FAgviJ2GOmkkBj2LnfhtwYzPYFogs58kmOGa8rb5JQAZoBS17Y
jJBmI9Kp8g1zQb6t7DYnTl12P2xbe4Zv9yDgLNh8D9JBASYEJlUVWYNX9h0ZHsjp
PA640Zsx4Oae/NKXKjWkeHIgkawxr4GI+edK5x+uoCryWUU6jJs4TSqdkYUZFnjJ
OQo=
=aeoD
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ff58131e-77ad-4f67-aa63-e1af8ccc86d4',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAApkF72by88NdWwVcT4UpjaUdGc0PC2+uwwDZjJalBijRV
h/tA7mIWZ/+KlcFJD4jj90R2NMKCybZuDQZbVrnbv32Ji1t/fwrwFrC/8ZobRzUM
BzQsJ7sPoqukJJaZnlOcEjj8uVE7Jko/NdFp0Y0ZQ9CuAzb7PaMIH34dSA35LGZE
EWe5dXhWj6pX29m/9pWszj1I6xyLA5revBu1YaikAEsah1qntkw1FQ+x8mLd3yOM
8lcByjL40XxaTtfpXEyhi0lA0uyszcwVRYgBHzpjQE2QIgImJjUpobBy//nRDxPN
eTV2DBCHmfAq4yckwg5IWOvL/ZNzciCqJjlwI+pH68upd7wPIneao3TVO4aEbmOm
hUAHt4leJVtV5lMk0L8zChGj6I2XfBWP7AiNQHnooCzuFE6RXU+SeBy+IbFbmgJE
iZLDC+C3DmPV2fHfuNuFBv0j81qM/KXgt7J0jqml1vFFwwGGOSi5N63iLiU3p8ID
KHztw00zfaekxqknq5ffylSrYTpTlBMmPhbj7VjvwRZaZDMPhWrVrUzHK62k+hW1
Uaz1n8baERyb7MaYtq960Te12qTG2Q/6DJqv880s5rgcfT7TzapI+JmTZVFvT+ZL
J1lipTcm71GI2zEwO4mwyaJeGPQp9y05WezFvwcpL/93i8WaZ9DkL6Xwt3GXz6HS
QQEYCQfF6W6hhGKltCxwbeTKYw27CBPu7kG3WEoYmk1NHUXb24OvdrxgFy74D0Dv
3aXfY/giXfPAAztPA37UrhsI
=y1ys
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:25',
			'modified' => '2017-03-04 17:48:25',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fffd861e-db40-4c1f-a3fa-3d020556e8bd',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+M8nvRXl3sczurLmog7JhXYm0G9t3epXAk3nocsCETZY/
CucmDB8sxMI6tt4+1cU73vCdbYN7SSfDQqWabWRYfCxGQ45LhEemI8D+3KjJR3yA
X6yWtpJ5Jrc/vWz/a2UyUsFMJEd3e3sIgGjvQc3YsNEDx6OOvURzTyRhsln2dBVC
7YcGGBe3+La8RknDFVdeae2PjUC48WC7KnJzNJKjjZDBwRMy3ZrxC+8bW0EAr38I
N0j9TDJi68dSh4Ha8j+hDVOJc6ns1il6qFrnHERbjN+sWVi+6zPsSBXY4Tbl+RDV
Y96xRqzfOmqZZS9prxgKgL4m6r2rI/d0e3fWc9cr69JAAVzWR7lRlTJgZ9QhfQ7R
OI/vTqiakrFWu9FtxRHWOoQPr1o0KOV+JX/t2hdgu+8hjfyct4TXvfHAhlAqUC45
oQ==
=t2uZ
-----END PGP MESSAGE-----
',
			'created' => '2017-03-04 17:48:26',
			'modified' => '2017-03-04 17:48:26',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
