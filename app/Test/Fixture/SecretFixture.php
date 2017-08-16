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
			'id' => '04c325d5-fa9b-43ec-a692-fecd853b4ca8',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//dysS7npgzFGNEefqyyJc6LH4GSsg3iRA3FmkDdOm8nV+
SNnsaO0qgqw7tC2smyrQSaO+xAHUMIJCEPtEpmGEQ83vAsqr2I58KSiAOsNdpbMw
50/wVHykRNUoOudtq058wIpPQcpXk/W0mvYkuVru8ZvYmAFF9Q+RfSN3O06HJ4uH
fjDuwly+QdyFJEa1rFBorNgefElTM2CesOVIuBk2C/DTllj7gG2UfkRcmYzUtcAn
7Y1CGrBo74Pk/K43OZrcf5hS1mPELFP8KW7kJUu3LpLrX0qOVN6kTPKRhzR3/uVQ
hgQd1BEWyCrSjpeYD0UfhmWgSKKy20cGMdIimWR2A+vLPGfX7QH3m5J8BXlHYKIk
rHw4Y3WWBqQdhnZLVovvFQ0FxIiC4Z8Ud8obbdK6LitlAGbHSCtI7tzxmYjLqMLW
GelAGvz4M5H7GpLnZeEuhDCyH6s1cB3sZXkDv+KnxrUuTVSJFoiq6/rCPYUsFLss
SiOrxR0RmppKAsnHKVcaVtt9oI3YwMS154YXo+TxwxWbd6x/jnPiEeLleb2jAMwI
54he8HF+MCaoQkV3aaHUrNb1wIWCi8qcqiHV2JXz/rwQ+f1Cnsc32LsjADAbvcW2
TNuPQVkPwk9jV4ekzRTl5xT8Ur3VhdV3zgpAD2XittFI3/7E2peciYtUu86Wjd7S
RAFZRsdBhKIo8C5kXxtS6P5dyJ/UmiUwqbNR4P/EFc5spwgUHybR68WW+OwbIFYb
ea8Yi4nCL/sbrOLzFT1UYwl6uT4w
=Oc5a
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '053741ed-c4f1-491a-ac38-bde0130e7413',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//XUmsTE2CufRS1gMFRE1lnMsHLqu1GV72GyH1WaxXi8l4
rwJHNZAMw9MhZupI97/KNsmLM+VIHGH4v68apwKKp57SqRjKjOv7Aqwk0OHb6ke9
eE4LaXrYBs2ADNoJ+kHX7iOC0jqqB9vwBDl1dkfJLgx3FW2Pwhg7Da0nwp/oWKy2
4fHPGi23YKSNHkxGQYEizubMcfKd8HSYFHFF3EZCL152EKzcD8Mm60cuLyrIdMN6
hWRB5mfOMWPB7RhJQ3oGACqWZQjd0E8AKseAAzrkQZ+2JgbalnK8A4OFN5zCu9qI
jRJF+h5q+5qn3nWm9OHk4C92FTiR84zCEpd8dCDK/rT9r59aq2QzdYlsQAX2tge8
NvuI6yUG4De2n6R0iJKo6phMEtC3E5uoFqbzSDrHXQnT8QO2dlDem+KNBtiG/9GC
2s9TSjQZuQdseVD6x6s6vsYlo66feEjsZz/pyeeCtcUB1N9oSWxKFDlFdoIylP6I
iRQHrg5xDqtdZkOl+5XltwM2yvA6ZeQN2cWIK+hGPkf3O9G/JK73Vx5BUJRXcsTb
CE0TP6tGtyzcbVRwUKWxjfMzxTG0WMyHw399HY9gDqtNH5OePmbKagFj1yAffo8O
fSLjBqRmnvie26TQFakzMqXeNqyll9Av7QQXONczk9H3qNU+V0J4NNXq2MYPPNbS
RAEBbUgv1BkyApHbRVNYOjQy3tY23yQqqqF70zi8qen8ewJazKNg8hqGT6CuEPtC
4hR59vYJx4M8rrdMIEWCA7qOocQU
=4GB1
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '06e9a6e0-484b-4605-a153-c0f073e28e1b',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf9ENXKYw+bcjJaMMrQ986UTnGCk4fhzY33sdHXUjyENpTC
UKmpv2Pa0rvEGItL5J9/S/da334+kcf0wTaTgO/I5rAsrnE9yHWsJ9JPbCOReQ9S
2Sv/k50gY1eeh6ROUMVIfRK7PzwCU5ewpqH6DB+cdKR8m89qV8diIxr7rQiCMVhX
/LQ72S6L7qUvpRB6nj5nHcRy5vp2qse6YfYakWBI8jwye7jIOPeKaA+s+Sq0jw9N
gNV5fpkRST6sfzc607KJfpN+9ZLHuoZ+drJdR67izAHiWCS3OwXqEUDdbN+ZXbri
iBgZLZVNeEHME2lfdW88pLsWm3BYT4MMiJJGEmx3xNJEATLSYJWeb17O6doKCM+f
swrKt/o9xVbPDkzEO6KMYoX8Detth2k1ylyJeGfubl36zI3VT9EPFAg0AQkFzhoQ
XlFoYbI=
=v+FB
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '07708445-3a0a-4584-a1a5-58f4ee82d360',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//S+AUqdV0DSUuaJsnZarn9wEv+BYvubWdSBagJwOWVagC
HqlnXGB2i63k6tdLD//n7MzuptEcMA+73NfUvQgNN4aVU7siPK/DlDkVUSzW4q6a
HlhUfE7PsukU5SSsBz76DpspoU0dDOssrJSpzjQx0U+YoKmx2srVMU4mNaUEZlMr
ZULmztMS3WLsMoU0Pw7lOrZjA0LE9pnNroVP8Dk/EMKMm3oJ9pRDHdFKjRs6zBTs
AznG+vwh2UEvjCYCdLszWWbgBBK/jsh2SivbZyFmwQthRUmRT/1nrGRHI+x7loG2
iOKeRzYAJ84rE+XeCN22b9MLi6ilUFSw1NlxpUzDFwgiBo50qkQA660Z2QlBByi5
yvF9E4tzqOk8/8/fYrP0cfWS/rvhWZdSkugfZD0xBjY3tH6YQTgvmJUUPAtDomC7
yQdaET2zAQTpM40siCPJOYxVgYMWPpnOzg9aAUktuvET/s72G4uR+PZHiLoA+DMw
8ge71lzWpGvLWoRDMwJs1Y2JUPkb4MIZDLyJk3Yjr60WeV5M/xMJrSfARkyxOzwY
sznJcZTVNEmAXCsY8JnFzhotCpf0o2+Jq6en4FgxeijFcci1S3KjOGgejKeM7U8x
ULYNREtt//9LgDtAOl3hz6+7Wm63+hP72pH8NJAGZgPf3VHceRsuM1XZUM3NU6rS
QQGNyyhX2IcoVsARSKzh7Vz3uupWl5/i3FQcZpkRQD2LJ0/FXkGcXuTGZ2ISzMCO
SmYhogASwhmO8aSaONFdPOct
=w7fw
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '079d39f0-28e3-45c2-a585-f18831143734',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAjc3H1sJHhD2Tsp8uA6OUYS//WuOLcbMkg/tDT46g/8gU
tC5Kcx5Ncf8fxjU4YY5GlhgIncuM/e1muYEew0QdyVfpoxMrUpVZLjk7JC+cYUAp
93DtqbdUlA3v/I/y+TYNxQpY9PMMFkfkAYyRoyOLxcNHMAP4XnnXwVse7/tHaBHk
r9P00M9Nb7RhNhfMiVVG45MGhUUahgMsgqj+dT0LBRjQuWWuVzM4dnudLm1ui8zo
L5iimXtcBz5QW0I7Ig3eFJa7SRzgyTuIQQFtpbBq2qQId5GzFX7OAo6TFGHnY/qY
ZSSR4hjWf9IAEZxkDlSXulBvaBa+CYTS9JByqIEfxp/93AgG7/BO4wd2fmiL0WUe
Bdtamu7qR8TF6bcNlAMsl3PcLJHbWSQDochYtKR6WIdHNZ+bc3CPtip8btxTvWo5
0oRHjR1CmZ0McUmtzK4qJiLHhURcAQwI+OXLJuNpToPcOG8aNdMS4aG3/RxYgCoS
JfHnKM4MX4/0qFFrWXZWmWvE+kO+YLygkDBNPfrP9YFTOl9rpmaxKLnTUCMU2huT
BFgu7ZuN3fJzgUeJira7uaJHQV+KItrGa8VAhjCsMX/cRI7XN7rp0WM1Z5ipioa/
cx0YSDmCrmlFOLw1fqX75eKtUylvL0c40yXzA5Y8fXSL1YOB9ACMgCpbS8+QeA3S
QwGafLCb1iPalKboAPvHV8HmTRJ0gTixUcOgAIJtXGK/8y0M/+wZYR1K0lAylsJA
fB9B/bMjD49UaWMNeMg7fHLznD4=
=tJqu
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '08917068-247a-4283-a02a-07a4150caee5',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/8DPYf4Km3J9fJ2cc0dL9wdnHBoE9tUMN3jimSrhg/bbP0
a13Qi4yChwMWc9kbEWuqgnw/rtnRNMIv40JXvkgtz6tvFhP4wbcQsKbjMwlDWPi8
Hud9iqRfoaTpGnXjHahJbUTfRPhY/dYHRhFdR9n9+vkt6icoVyL290F+LIxbqu3V
cPTNdJdXe4IUzCP4efu5p8oI2Pb+XBrHHAAu+XVS5SA5ukVrjQN+ignaLgxt6b59
oQ2QctNrdlkbZyHzCueay9ZS5AiDuIEQR4z86FvnJ76JVT/a6EAf/NUlXlmUvlfs
qSX6m3MhGyiv1JCsZ9BzNwKsZxQ4PsqWw+ED6kDdkshcXcvfDcasND0EkK6ZyKr9
SpVUi+Du3vrcIGn1nGfVXaDQNoVbk557I1KAQdd6zZBK0sPUW41ZVHS+z1JI+TVv
01Ty7NylMswafiViDHHWoacPfHoTkHXBEHnT9gg1ILUzR8PhJ75qRMR9TQN4F9aO
Ar2PhgguQEoLozsM8T3YU+hbQJAaV+6oRrgyFDNFH4hGPUlRyLB4YJYDR1xVhZpz
HJ/WiDsZ8WIPSCZz7VSt6iYT9boK04eLeuqOEZIOL/CDYGQp6rOgtNHFTNc4i3AA
Jh5p5vvhPbktbjbCEhAJqT1wIDzzYMQCoisIFehsdM3oWxyF9sEH5eEvrwNPZmrS
QwEQa9mt8auBDcaC5iqglIlJz02NqxADF1hGoIJpkPNxvwyoBKFrA8xvgsHeDESP
6KBDxLcCb8reV0FqRkWfMVvWlyA=
=MDHI
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0a6c2f08-6fd0-413a-aea7-1fed47ea1ef2',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf9FeI4ZKoeHI39wtt30QV8q7RMOrR45YpG1dAiK1sppp+k
iBvdQc5ml3J7vYaqhw5wBb2kBLMSAb/BVp9Y+KNSe3LdYWPr4FMOO3cOzMmrvL7l
22zgs5zR+rSnS6q9t3qf9sORx6aG8puap5j+jCFCb32HAlwSp3Y1Lc6yu9QkSbUL
mPpvVs4uRV8Qn66DsBHADSTWh//p5vPsQ36Bj18THwyJqlcUi+37IAM/xwcAtShv
qHU9STYPdmq1L7RiQrcaDW9q4cgevOfVJGInqeT3YU3qbvg1bj/Gd4bg3Pzr8cLN
gIg88wyZKpppb7+Vcs3Lgf1Xmgjx7x51Fy0nx5hq39JBATNTqHUkygKaXv2B4z+x
OyVDmRzOtoL+N+LiThG8mEx+QdYbXBSsSvjtnfcArvLBtaPpYII4zciUrutq+4LK
vag=
=qCrS
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0b9238c0-0ebf-4212-ae2c-565c8f353215',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+MMxZQw0tZ/1jaVnXCLUaFPhrwRc8I0hwZp8RV0BvS+Xm
dL4BH49GeYf8VEukUictw/iXgQpZtu2YyI5F9Gf56dxB7UfupawovRNMW51WgdlT
r3Sv+vhydDjqSk5ArkA6Eu19nk8uV7Uti6kPpjIssWF75kOu5GpsYebA3EnQkd6r
Uvo/gpXBkagdF8WRe5/unT3T+tu1MuMMGK4hjSg6yL5ChCR4OrFbrgFQiNC8y4OY
18YNTW5zJdof1QGffqRguuC2qygD/ii2vWwm57OYog8GmwxVp9KcNRVdpob1DwXM
Z50BygUb54fW8N8Qw8OEdXjt1tcP84yFZqf0UuVO4tJAAZBe5eFLZ01/mx1bpHyj
toepxUt4yhIXQRxdBiGFCttOohuKnnQu2FpnIQXMHsam5E2TYVvHIh0PkiUKkh9S
bQ==
=D/5k
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0c745fbc-8159-401a-a992-dfbd1c907399',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf9Fclv/wwvFg1jyL0rQXRFQ+q0QBW3oKlb4yZ1SxQwV/kw
IV9QpBk/fgGl8cddWI7fJ4G3nFuVQCPs9b6O7M9lEH+KZLpanvJ4YetAqN681kI/
/tPnH4+/nWwWM3s0XLPAoUXHOxY+YL9qAAgH78jp9HVSgdfrje6frk3ASX0SICBQ
MbJgypuOCJoEpkq1ZLjNCVL0RJlAYluX3dyDF/Ew34NL7QfyuazHzz+sLmG1S0KZ
TOvcwh5zqCQbcs3fjG82NXsnJI659xkEji1sE0g//IS9uWmm0zSlg8tuIBf+GN1s
8tbNDNwqDuz6u+WDnnI5qxGRZGtX7y72STxDNFCSu9JBAWqu9I8P77FkY7gQNBOa
8JsgCO1FgQXFFDR/zwno5GMpgWVGwNyijWQPkGihftjtVTntPSil4hctqdyXgCOt
7gw=
=/fqI
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0eee3fee-9ea6-47fa-ab6b-5356e7c83884',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAqlY8tMccnFPk1Btk0XKcvi6oickfqcgpjARuhDQ+Xr9F
xkROs450r9WroVZ1Xk+j1FGZodJI+AhCKVQgdu9UrbI42iTX0fsqCrLf4ZWngpGk
bGN5J6FQmJwdTOmiUeWwk2JkyWhbs9TEZ8gNaFMXcz1w09WiwzX5UYdJecSv9wn7
KDvYAX0w/gvEU82Ked2U/oUhWuAzJ2lHo/SB5BRQhaZl0RrahO73RQZXRBmc0YOt
yDK06JQMEa7mxsV3FFRfSQgzZcoVOYFnoQUQn/gc7RVZKQpV4PlGA8JFvKLPf/gP
+eIIlryWBkIYnos0TEsfThsAPuwOOwSCynJwWs4Bd9JBATWM08jarnt5woni7WXb
2KxcJ9roC2u+RwuiVTlAY7jz3fxZxtrt/hd9Kr7It1q9T9GXkEbqQEtEoq14hK/p
N/s=
=MCU0
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0f551eb2-c005-4756-a61e-5b65c7724440',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/5ATfLlKYqkN9W9ZSAG2mY65g+pAuGLTIhTKQMWJYpA9b/
Yp7vXzh0g5aA44kaDpnLu/QXUKWCDnIaF0LbZvHEEH5rzO5qRT2p43zqhJuH8GiA
kcRYCchg6WplQnZ5svuS5MjoyisFE3cyANO00D0a3fRKeVZ79qg5nCYqrj3cAnFo
xM0t89gtVSVJoME25gj7C5huGgyaKduXGsfvaZdZ1JJWHI2MNKZhUpAAuk9FKOrM
TpMthLXPvEdL/Cn3Jyj/gjS1JqYM0h9cVu2Iox34McztQB2I1vfxQXE1qBKDu3qi
qPbdVq7DcdeVvTzyV2oFxScr8FhPFOpxphopc7B1YzUetpCt0PPIEpQWrYlcUYje
Wj6rQPVzjKtBdwvxaNPk79unpeC1GH4dPohtnzFom/dz4hZANF6xpTJnVcL/6xE2
ya1fntMv67CsgkHO7FHvBm+eUfyxZQEENtP4NzkuxOZpPDsnIEPRTxnO5VX2/DXm
K+B8bp9HclAbwhl9K8LCvk0pTXUY11x9ofCEOu/zHixn2dhNJaQjt7Dfa6+gpuc8
+Ucq18sqrEyboHpKExikBUsQHIa/lAonzYGUnDuFR78yd/bmdx8A6IY94sjopsPR
ZkmVnls+rF2tfgkZ+ok2eXiHYJ8PYmYkCZ9GWfEXk/T1LB3D4q6IiTCzrKQNs8vS
PwEE/2jp2G31Z+oIqI1iofoM+EiXVXLAd4v7rtUYtsOGUNZnFvvbT+x6Fw4rFoj9
Vr6K5lps/TLgLW8n6FMqYQ==
=KX5p
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1177075f-788e-48bd-adc1-235c149c318c',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAvEd30lwHLc/StKQowmKu93yaVNlszs0xK1PQRgYpka27
i1Zei+rC39ZcBSvv/f+cccrNf1m224P41K/SPNX2MzPH2qjU5jbJCkhb9JTX+VMA
l8l6S4uZVz51QoRkIcoBnyg9Sx+Iw61K01G1ZhbXlpPMLHYwm40XU0KWPM6I6YqP
sfQz5vrKJAuujFEJYOy3JEN57sSvNqp6zJ+xtv953W4hKRknffhT0WI0mvs9Vwmj
lfqdXcKJp8DsdhAO6GnVV7eIYSjcsipWb3bl+zAB9MlLWxt73IqllWlcM1rExoSC
jH02VlKREAwLCr4RguflF+VUU5eAdL2GKSSELy+Ha8wL9AwZzAM9maK/jVwx0MoC
qdrW8dqMMqsSmNeL27HrHCVbRq7iwGccid5k96RONosK/gv+z0Cyqyq6PGHju1XB
wA9k1w3YlFfzpYcCpqYrw+NKCQBfGUyjDLM4ZgjNnsACXdHHGnsCPF/5ooHXk8am
LN6FeODt6hQby8xWj1T5cFOmz8BF6gKLpumYiEmpnt7Ky47K2u66e3UF+DKOZW2o
s+HmeJMHokvrRWNy7yJfqHa/fsSLRDFD+n9iHFMFg/z6nzyYVXb9oqyH3O+ph3Xy
rLqchddNhcfpZMpn4rkCnbzd8cv5fuT9Uxkl6/rK/QQvbvz95Sjl8loMQP3ceJzS
QAE5qJxhWjDNBhrukeJRwaUftvrMYMZajDE08BxZmZNjJdYOuCxt18yXioGVhVu0
Ee71m9woqnIarT2frtPImIA=
=mGzr
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '11776e00-2151-4caf-a6ca-68452c85d026',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//QuXMTV7A6jrPDUO0rYdXV9agaGG3kJUqtX5ZNx/zMdfe
nzYu1Wmg97ehi61WGLcRPG0MeQcz8fjFcup17d2m9Q82Jd/n4+2YYhQ55CE0+J5R
y8NN/oxa7kcU/japfj+FAevQX2BhJDSghj9sRITd53ipoSUVq/2OPSmaOW1PXTCN
55VsofsEummc8lDS8aYBMvSwdbFyYl4rb2E25/r1r6rVlGcw3kCqtn0BlyXHFKSP
g78t7baOEDUkMb7+8X3wJOZ2u6OpZbIEgYo64mB+Yj39Vp3a/faYKW8qpSEeS05E
UYFnzt5W0lK8BWOYpP1iUaJHwsgKhEqbkHy5s/d3Z2HUlvXwcekKH9w/uZoyWzyS
Z+j7poptGKgWC8eqVkqjBo9XIuaIQPLfKKqCw4EWNHKG1dCulI4nOityFq6sIJHQ
ZOlSeL1S4DqIdhFtOI4KLDzp++nV56Hr5gt4YhLq8m+kz3k1IbEW+yhnfHSGsyxx
4D7FZ5KpHkJ8mIZ4nCjAYSI1iaVQIWVAxaJK0t0UY40lM/6cdsnbPTv1meSw4MEb
ePSNTEKdhzDqInxjbW6Xc6/LXBmO4/MZpAqWBod/yG8hJC6tv/B0uweCcl50OYy0
Qy8ZXaQjyf1twphYOGldGbI+BpvbzYccvsEfHDe0BU1/57NFoOsXPwADpaTZkvzS
QQHsuWo5wz9dkqD2FkWfj9B3Ll5r6ULhdShsBmNHPNo5KF1/GzFFy1FdJtaDWPFu
f6be0EupCrA4xYkOJabz8/cA
=6rww
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '128c3c5a-3730-4573-a61f-ec308e6a49c2',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//QhSYdaWplBGG8Vd7A3mkOC/le+PpfKNfAlupqPtyGNId
4Nt54LHiK9rVig4hEkicRt4vCRGWnbJOoy4bd7WI6tFaUwIIOAren/8wo/H1R8ro
jYkCUDJjPycUEyjZr2pr8Vt0yLQ+J28OxQQSmMvAqECZnRaannJ7YccY39AJ5x7r
2p1ZaTkCAVBenZcveOqmMKqvR7p1ypGv/c4xkYOZ3yF0ao0atM7AgpXQ8nQiBrFp
zr+ZQvHVI2J85mggDZl2JFepqitzgn6MRP4I8ItXc3yeYucj7uE3pXQpEgya6Qqr
PdhotV3XciTEHMMURw+lvha/p/xgBwFQ/f/8TDg4RIHpVL/VM83O8VvPLQjJVbOR
K6JQjgVUuvmlWGdiHkdbgWpe6v3Wt+AXVxz3e3NKsTAQmsP8c7ccTZsd7iAf+t6K
4tBrVLL7WSR0+bEEYkbz5ZOa3zSrSIQT0t23Efl90cAVYb6Dkfq3WMEHpH0hFT9X
MpI3mk+F0iZ9veprA5hAIjlNlyHutOCUNMbU0RbnuJjeCunqWyST4vYo8mw3rWg0
xS7u1g8GJcx3Ofka6JMm12G8Ef/Yx283pZoDZFogMhF29TG2MMkk+/ZLZpTpkK8e
AAJDYmYPdMYpp/52eenWhrpcm+ctFfEfepy/BSZzxvpn7E/S+4vtrWILBpQR2e3S
QQHz9a1n30Ajzm3jRh61nWg7ZmAj9DU7wsq/PMBJNz71NA6ehSlZNSeRQDzSmtXj
KEOuPpbpI6JxlIWgS0BN+YjG
=L6zI
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '12972227-5581-4c96-aba0-697425a794f3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//TPRt8SNSygQ/md4e3VibtZjetR6uAzqKMcq/Meg/gGzT
JT607/++sqbiwREwRB4yfLare9nZl92/xxCXS26yJKyVtaCHR6NTL8q+OA4DRpQ0
Scj8rUkBSngt8f6aVgHezFDFyeHkeIl1+5YkRRaIamOD5lWf5kIivOLTOEUd2pRv
/wLzs/u+ebm7YqptH10s4n3aPisRbrr4Xa7oQDtl4AkAOoshCf65+erU6nNfOgOe
5L56qvzpA+roLaJWajCOqlM2/TAXyowcCN3t/tFYPATI/YjpX+hcjWas4MetG7aI
HcHxLGgFIeq0wNEbMUZYb/wyndLIrICMkw/aOaCdLxFjUUiBibWylyCM7unVnw1Y
LI0CeslzFAgxyAPECl2+lbj8ZM33OQcW7c+z/DY4V37HFrbG+RMRL5TLeyvuKjhM
+Qq39zbTpLIHI20oTP/mroU692K92kGpbhQPX1hWE9nv54BxyzVpdi6/dSeQUX6+
tqWsy+XD+FP3H28f/ayzIb27o8Zuk8MVLDn4gXjswiS1rhl2D3e1CsG0P3xCktRg
A3ErY7uVZSn40agafgdRRJZMCn9tKZ6gJQ9HbaQ/KoAYKFvvJTQCFJvvOxyo931r
1LZOsWfSmvVb0QH8sUouKccfoZHsrxf/FzML23AqOGw3QthoE/2gw+DLsmBpQs7S
SQFH9LKEp6HKtefbgh0m6AqCmEwko8LH75CE0WTO3rIceaONDtfUc6m9c8/1ZBNP
TN25bhllSFknEULw194u05Mhqzg939KF5CY=
=hbBz
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '13c5c7c5-b236-4e5e-a1a4-3364faca2c06',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//e0JEIN93jG2Lm4N5DQNRKKQCfJZYCxYf5yjR4KoMiMqc
YsTlRQihWgGt2kLiPNKbrE6vlBpG2ZkA9cokL7KPBdvCOTCXrfR/60JWZgJ+7WN0
p91VYS4HQh6+15xbEhq0xVfRBE89QBme2ByDVAlbwzZjq2tyuRtEaDtFQUT03HqT
005DFhrtd8oI/aoOAjdEeZoE0GG+WloDTwBZODnKCgG6I3/nUWxipPboj53vvWLb
J3YHaHDdklE3acE88khq9ma2zAEb8ZYNGbj2cB/XQkZBtKM0DVWqT7KutwYJqtMJ
YD/cTiJbuacLrjkD8Vtcwhqda0ve0Fdmq/WTFN9RUh9y2nS8+ppZeI+VcSWs4xYm
EH/Z/G4fMDcsRFwwR+6FHKP1ySSnjd9PsSauLdHodz1CWjGyBbn+BxdvPJaAISN+
6EPo7Q8iN3yLYzB1D6VhbsW+7Sk7x90Mvm5qzVDuIZY1ZkYZiO0YkSbnEso9usov
1IdtYnNNlhaB4SsdG8A+JY/twZlHvc1DWPNBJHNNqaB82FvT7CxwyR/XY7kxd2NK
21CgqDLzKmoi/o9hY0uazgrp6MxCm0yzwwrcT06xIMo8nUhvtlP/VvPYWzokStY5
LaOI8O4AeCkF32ICWbvcVEH3b/LtEGvCM6s58RZfcbK9cDWLi40N3DTzZq6ndpbS
QAGwM8ejgD4a2UWE+G+khyZgbnA3+swYDRjQTKQMaV8sF0FFp31a+QztuwxSzYTz
E4nb3WVn1Ihr0zKSeJdaZPY=
=nRlq
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '153e820b-cf48-4a0c-afae-eb6b87500788',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAhYd7LxTAdrUB9hvbln7ypCLZv+mTBXJJjuJFfoHSYpGX
R3NwaxK4rZ4CC/3cUhHap03QZv3Y6rfjsqEYFVRB9Xkc5o5Q2Gd8dvMeBHPEpjCI
U6zT8es2GdFLOKXHZIMPoVVLgMYW+pmhG3fCoDSWSIjVwb3NUxwg6/ksXkVDepzR
U76Yc14sTHgk7WNLrZhDO2sNGhdHKAbHuX6BugoHw71m4PXP3L04wTqKrfoz517G
6yJ2IF4h+7dyeNXPmALX/W7V6tU0IYxlB0Q4XpJf18svPP+XB5GE8MQ4f/UDXsoc
PSmt0+A6DdDJ3WcOFF9kQ6ZXxGjDW4ZGClpVpivWndJDAUVXcM3Oipa935Jua5Vp
Kt5KD38UpvElPY3dfY3sOtEncvGuEg1lI3lhgsjHmQwINjL0+BKYLXoB4IqbQ4o9
HosWtg==
=QVO7
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '160eb8f6-3f65-4971-a679-f45b038bddfc',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//TWERwNfNYlBhOyxfdmkcWT0f7ILNzBCCsYQEnfxhToaA
OEq+NSau4d3znuUaTXP1IQZfadm+n1wR7R9E7Jffa4FaHEXAGJudnDkhTVXpeAxM
UdZJ9xTbdunTSfv1okx/odGB2unoMcjHxOzc9Ql1Sow2ySCwwXn4Pp7QswR6mONa
dh/ESaqsTv96V8r7YPL2OYQY7FqUJ5OvjtOWw1hHzQGxLPLFNCMLhKfgTwj5pZrX
GjcYlQgNt5DF7uCTfwUYyevHD+rtCDkRdLTSuE3EScOjh+ZFjQmtF801HExMeedS
qy9LjVnfjKPnG7uRWYB7hGs3tUWN/ItNa9nHAVbhVawJ4i/eNfYvWZ/gsTREdXzr
50+Sq6WP0lcKXoD7okt6rraCJbL+P0THoPpj1OET/suBjTzEoLREdUJrtxPZ2er0
2eDBNsTOb0z3W/oi1OhE615NBb/aiHE+pRG/V5AW7WIQ+nfGjPzJpW9BFarKX2FU
pcK2m7+p3Wsd+NPIMs4Pwq80FZsiskNGBVFBbsU7vLXt8vWuSJfZbPKRHK6Zi7Zq
V3YAEj5+PW9TLI+IPSnZgDqBM0/j/Ld5VcAXSxESDFhfhF9e2/pRQjUi0hfjXTdX
EWrv10jcyjVaGSHgO/5HFsWjbd4yANbHn5qSX9xqRp0miC8hkyNK5/Wio2jFo0TS
QQFNmgVAt4MvgtlOAKRcfVDtiu9n4NfSfKHrznHDTF1OeU9ZBU+zxkbtXdkD1iZQ
MImKUijq8ZCwomgL/V/3rT3q
=ODDD
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '168a3707-b348-49a2-a553-f1f1092b65c0',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAmxsmxlIzgLJC2jdttFuwPEl+H7WpO915A2vJggeW1Yp5
J+qFteLTWEb3LcqDZxB8brWDFxhcat42uAzvLPkCDjcFwAeNAPfuABuCg3/FvG5w
TpFBzsXJfxRdlL4BDnDEpMP74sPTrWMAhdInQ8EeS531w2Slc/m9DtHBhueECZ39
ERnIk4Ots/QlcA1RjxqgoayZ226V44p7OMzWRwi3JFFlr27LPK0eGIwwlLxiTD5g
PG5v2y5+fD+DGYXhD/I8hBiaaUm7An4jVJO0EDWgeZnbiJHVdccUwkYtLTcMy9EO
rQT7df152q5mIFDt9HnXemj3YZEUTCelNNnWQOPm4FHtgbgVvxmHq92I2fqdw4bR
UJK0HaX3tuuj7lDX7PPFgrQ/TyzlJUEfNA4teHR7kOQjYI6qFs/ffYr1eug6i+t5
bceAsJuy8CaEzryMc3ozxlVTu3U4mWTbfvX/vzbaBdxJynV1kTuswJ4h/nhNDqsQ
wTDu1NFaIZovoG731GYkJVkX7R/XoVsalFLLo9vNnZuOpc46g/5yk+KIRNirfxTR
oJ5fg3ImdBfn/Td+1gAeaK1qeEOQb/oRNB89A4vZPpgc8cxfTE35TM0fplbh8wHy
CPej71pP5nNKnap2eMOwWiUCzV7ALaX1ZQuIbvQH9xaJQDQNzSsy8sPeuXNFPlbS
RAEBWgnLV3ZOB0Lh7UdJQeLYUpdxLyATFKiTZTiXLNbhvmNxt8++7a8+HnovNmgp
44kN//2QfdrifURpMxkX79nUHZVv
=kP/1
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '173e3b4c-a13a-421a-a0ca-35d9ed3d52a3',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//bVE5xJFQitDNbrV9ZumCM/fZ8Nut+EfXC2mk/RNdJkw7
V1mDX2SsSGr/Y17vXnIdvxiBNnD/W+AYo0qYPgddBaTvF5Dn1ycxBJECJPWaIUHI
gDlBDXAaZkJeahZnqaVW19ruyt5Xk4znfddjjXl6Lgl1X332On1Lge9IrNxIU87g
xZIOAY/gVB084vZMB5Ci4+DEZlhvc7wPZS/6ueFgksZrm/A54EQTBnKwTQI8Kj9a
dqYQXYt5DVXc+Dt5p1RlJ/VPSb8qiOeyI/zvbdiWKu15q/C8AGwFKrLZ+efP9XW8
6SA8sUjOgHzqn4Hun2zbP42Gs3EmpXBHMWVECbmKj6fcMXJ/3HsbuWA93EXGhMQ8
oEGEaljm8VR9qe8QOHQEqugmj1tJ5OeiFlJfYydjrTLIMnBDYcPG1HyuesZ31Cey
FMhkdzRvKa7iB54JHXgXDzJZJxV6X73DDZInh+QmbV8yl2Q0ibSiKqI+CYtKGeSU
aqrZIEnG93Ds/Ob6miZP+xTWiYAsPkbrf30TDbScpeyR2CaKBqNAWsAsJObmQvtq
FXrRNx2rMPM3+Qvw2GifEPrnLHSeJmhOLEyFrgbJ4+ANiAb8rKbRF3h9HhLLmv2D
sztWE9ZI403xUE3TwYPBPAU3ujZGSm3aTdDpb6KStP32GzVPKMUXpvbRJq3+iSzS
QQHUVgLxxGEm7RcBE9Eugn7v5A/6aMej3SJXpyKmRr5TAom8D4na4nGlpm/Nuta0
cwd6SaGq7Swhm8HW7E5wgzWf
=rqug
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1934035a-134e-4d48-a52a-97a5140b47e7',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//aX9slxh7pRdYsGi8GyTkSJOxbKkaHIwC4kb3rJQH1wfw
GkeNi5ikD87EjPvyNUxfEQUuIgw2+QeZh9vGJ5P90AHnHpNhfbOMzN5PDSFdtlS+
GGcbZaXkY5rZ8Na6/pxWBKrKtanlWM9iOuS14r4IaGS81YqeGy/67a9xifm5hD2n
8seG8HT6rfeOQJMN69nj8gwQpPu0M2lYNWcZ72QE6TcUVyxdTXC3CWtOra75J9vh
Bm6+2ktMh+WV+ZYOXCPJU8QhtWWDnpQ3lmLSjmZXb3IQXR7OjClTj6uMZXhuM3OP
6kjRVMK3rbLrxfTQRixpAysaDjhagc9sBo8yI18Edywhi1Cm+0v8nedMNNuqL7TS
2YoVEOBLajpB6cA4VbirIemgK+m+6ipYj+ieqX6SD/e6wRnPzTrcHkOg4THozeaH
Y8dieA4ixB5A6PbWMnaoIeBmXza8Jb7j3q1g+kvwbgPsM4VcTHAdRfYEl5/9//DG
BD9729MLFhrP9D79vKyX2Lus+itHpBrFrxEfbMgdpucqsM3pxooOdcN9stI+dl3H
oLkVHj1ABnibcpO6bLoVuOKCgxfMP9GN7A/EuH9k5MCZ1Dq91BY6Xox1ZwIEBMtF
P0TlnJEfUM7iG+0WtKIdaC4qpJp+/XczktCAdsjG4VBFQitnFsrkdVceD7LaGAzS
RAEP16fmppaDDf2nKPj71pXFaADpYu/UMZzOxYmvdnZwYOD4UTp4sBkuXKSHwQtZ
jfaz2jqtcx5sRuKO0f013Sl0FjNq
=uFjc
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1b27ce7d-cf79-4201-a16c-3c792ce5e7b3',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//ag5VuuXlPrAFI/Vrh1ZbYtF4h7BUfec9HyD+RhIVrQw9
FFki5FysqxVP385ukUbUgdVtOouGYEUFaVY/o/SBSeFufslwL36oMAivHHGAv5hJ
AOsHmb7LwizXwy0y8fcrJsz++CTI07RCv4dybTyxHCTzXjWCQc1iYtHZR+Zam+el
CpPAWfXGm02156ZZ08rgeoq23MaSeyWUusJATGYxVZmpAdtsKIRrfC+StdzZIj1w
R4DIyB4rodzADJUCGBgvS3s1b5NHf7WLFhc1Ygk5gZw/x31K3rFLosVntAqdrdny
AgWKRDEZOnzX/C34zPfQZZKxUxoiwXSsI8sjhau4vnaXzIz7uWYCIEDMHQt1Rh5w
1P97rkMbd+9GyUZCAccJrdSn5d/SJY/LsQkCI2l1N7gt33KFsTE9azmV5yAcH+1I
cdgOPZLO9D1Ijy2gX4fX++Q4c6OdrkP6P1JkeraNXw4b9U4wJDuflAoKM4g6K1j9
c5qjtcY1aVX/N3XtuWOtBLooSkdslGVZLwVUst/8U1jATZIgdtkTvbJwgp640hYQ
WL92ivwpyvucETX5A72tcMCIOJ5A5EyRp+bPVlKZilZtnzFz3jrgiYyM12XeSFSZ
k8kWhtzENFuNZ3KFzDjbdRfI1DPdxeWa6zrN50qN06WnxQIdDFEhGIr8LATyazHS
QQHuXudlgNTxg7pH74xH2TQuDy5Z2EgZ/rtLdhDQK3YKa0eH1QmEAMFbx+Vuuq0K
rLFq1C7eXHoA4XwT7/W+WA0w
=HJwv
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1d1b7d58-c14e-4702-af91-789621bff8bb',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+IjsTF6L2QHZ59oC9wTJUb5rIYdZPSckvo64awo7uQDfG
lfa3B6cgg/hSVdsUZIZT+iDGL+HdVS+rFcDZor+oQsZlf9pNNijUUYD1b7GboNci
91z3CC7vRu89QqirhTo7YbuGEi/5kbIXgoRYhfcUEkvEJZclV+UkZF1UbFDdUP0a
0KuyL1KELdJuxSdohuHhKiThvCP4jWcHuAqsTlhDiLkH8FYCwnc/y9AVWFrXt9zn
7Edl89ZTytsLq50kjRYSLot1IRNcVLN2aPXcb+g0926+FC2fcLCnqkyYfN3zggqX
3HLgrZRwClV8A9VWsoVj4tByrVcAPkmLs6DdGKUqaIKFjjG0iKvfE51zv8UQ04qG
CMxQzfHXoam7oJri4UKr9wUOTEPpMdfyoUsSC+r6Mijq6PUr/vk5y+fuy5UWy3O7
XgcffWzJVXEcPLRBFWUKQaOnz3dY31AbBpIZrBDP52DW1/cln/YbAgva5IdyITV7
DnJn1B2e0T7rzh4j9a6S/bLf5lkqSES5h2C9T59pON8n5n1ux9q/pnjBqzIZt73+
jSFz6uuhGLlavi4MSRHmigWpiJGYlf/7PrGoNeNTRPvOPdeO/HJ2VZq4TDYrIlw+
ZsWL2HPq6PllcvVYJjblaKl2Jt30UquAYY6OT5L/iTITEsarXw/0Qzk96i5ubEvS
QwFdYqLZ/Qkv0EkgdIESTKUCJGwnmZeOX5yqexYH778Q6+VM95nXNBX6J6ofOeku
dhAm6+3pVdfRGLVz0jd8RZUVzng=
=wDzA
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1d8a6df4-8ac4-4d71-a835-2b5dccc515b4',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAmTcqK1JDM5xt1AGKZvGVmLelD67IUxxmrn9BDYW6P/r/
MzIVCJrAA4RhqLy3KOjZzbUaHN/Rwd3TtKkcASiXzHmK2Qw6P0hAjgAEsMO7mqcT
wxP9HZOUQAKJyiVfL5tIpHZm/GBbKuPK4ex0PuXlzYkG67VWrdAKgyO7ly9/kqdM
T1vFFsi7Ym8xNxOMcVIEyTLtY7acVW0reK4bfpjuhlIdzsm28bsTp+rgP/zsRDeN
hPjYdobu901KDD5qcku4vNifTL/BxYaUgoDNccRy8xC0Ijcaj5NiinJdVfwA0jdO
wCzWLZbdpjR3USC1Jhqci44WRxxuL3nvpSnojNOlynLLkmq5hG2eswwKwfrg9TG1
lw2sP0wSpsUaqpPVgav0MOOJtkXmr1qBLPJ5VBtQqEzx5MOYvXtvMMev1qOQkLJi
ZRU+fWFwATZ7j2dp/ViwqV+DxiGrUGjrzTpi9DupGJXiZ68bfEwt/dAVwCEZLlWX
fF3Q2Etm3fUcjzVW7mVUJyx803iLICpRyS3f3hjb2kOuuyeLiW33Jfr3wgopmLhf
h5UYCogLJLCQ9gSI8KzdnxLhSrHKJRVT2Z4d0RpgOqf0YSPH1UIcPmtft4rIiwsi
8SG9OVggZi9K2mZW0BZr4EeyqhEhX/QWyM2O7Ja/Dk+sVEhTqtFlsg6Hg9YWMCPS
QQGtoVrLBLE94+IP/MoAOXm9/TNpih80ZJWEqSxN29Nbbx1xeIh/Glj89mqd+l/o
3QDz0IruFobWnJLbmYgagxLe
=DZZ0
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1e121eaf-ee30-4be3-a4bd-daf49286b5e1',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+O+ykiLQkhsQhn4zbmVNHdgKR7IodFckfSo/oCoyk59/m
FaHB3HvQrGRL+oopp62WVbxQhVFdUv4vfIKbyNOrCA07G/7iDNJnlIexCOeBxXrc
7M760PcPEPMepkVdW+vvVgaQbKBrkZNo/kHFWHyCDn4D3gE82xHN1FI3D8F6zT7p
b6CfZcZPFWd4p7aXN3segY5uv8lBaVvFCJApEDwENf4+rmX/vlzenE3gTUkwJZlr
QSqo3IRA0StpBMAIaUKmP8fMpblu179whCnx1WmRwBL9e6/OOGfv5uoIW0cNFDBz
ahSWgEV5InUW5B01zksO4hYhEIJS0soWtilI/jJFyODbQAqMgNW3xPspnodCe5mq
K7nhCG7QdMv660sjTt+LLMvOsQNtGcluanc57ybOZNW0hYEHEsyJac0GJvb3By4a
j/EwwOUfOyzO6qe1pzDNajM+sHqLPy25wEFpFIMdGQE0n4d4S2uj3RWJxihtDoFP
eT4liGiRlKLoPh9ZCsR7Zqw0zVNdryPQWHG4pBGmSpGM/FJJTugomRIyMJRhAn5b
qj+RRcg/oRUtFeLJ72XOevEiTFukF7F2JSqI9GUOjAuSKifAd3cRJ41ht98j1YVK
aiDjfIA9N3ISpI09O6oPbvBwXuOTOVvWANlAkI+f04IDiHZUnncFVonMM7TJ36LS
QwGM1H7rVHbqBNf3rTnNX9zyXSDVaHRKLMBFi6KOLukMxTzjsRrgvfKsGjk6S+5/
GirGo0AsXSTwEjFEhaS/dnwxaSc=
=44fv
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '20b46a74-24af-430b-a644-7b7a11c80018',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9HNqgHTeb3a101yOtaogxiqmUkMi1fWMe1/pRAclKghzd
9yVYqL62+N4+dkb8rK6zk/djSzXbUheggHIvldYgsUe2CHU4syriV6sZSZsNedCh
suu1ffZV99zBnJ9geBDT6bqO/fdc+ciCuH8+zZn/pdtmXb58urlYgxNwn6tJTA4s
G3Vsqa1RdX+lYotZjCP3yFuaYLfTJ3GQ7gzNElUNdldr/EEbbaP4a5UGR2ljwEkZ
WUQwHNUdq7SCihEIk4dzENpJgerpX+lJlH2U9TysbZLGruCt4U2PMHOzO6q/4VUa
fjOEvSlkRRYWEu6wERprKjcnJppzKAC3dxXbejQWIJPle8kO7u2QTBHIK0TWha3B
7ACAdkmvLIQMJrVEUmnC+LYFINkPJ+P39CNjZZBdyI2w+UZqnfoT/hzv6FN/h5T7
N1fsiHReejsjHhe34NOd8BI3ItNQUjo2yN80l+DCGfuHabs2I7ItFK1VjEDY1rAf
LO19akYjblrlqdw3N+dXCu6Gwei/28rTF9mxemSNSa4cFJNrseomJkMeC4l0gHNU
vWqR4ugShATpItRlq4chczs3PHtB56IaK+cqlw/8TrTeP07II27u1WDJm5G9cd+3
No9pPEASJsucqf5g8I+Y6Hy0hkWLDm4Zpt5dspPmHfvjzML8lmSNJsItrHY3+3/S
RQGjWAAJSxQc7Kffq8GU+Rvhc64/iPLKeaaHvVTfoVtNsPeLOSXKgMwjAq0LHxc1
pwObjIpgts+BCFiCYJwda0CsCEkD0w==
=PhC2
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '22ab005a-78ad-4698-a47e-1d7b8ee2aaa6',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+JLOwSskUAJfOwYDFbSKas9QJEEnCizGGBcNGx6Va46Z7
0eA6cMc00NAnr9bNsceWTuBc6rbGxKB3Uu4cwJW2ecBqJR+Q9ckPe7bFziD/zb5d
8uRPPWOHrTNqndXrLZ0uvp9uEthJbUPu7H2/LQCmabD1XDczvTIdi8d1NGYb3gaL
di//67SekW2kzU9Qx0Y1M5gjACCyi426hoEeqYWBOVPhxz07dZ9KOZQfUHXSIRQn
g4kW6iCJY89ehOBHMUdDgXNKZ0k9J1qbffJaL2o25j+Hc8FaWiqXss2z2RHRDgsx
7+p3lkQIz5iRwTWV8BJbpefS7VCbF/3FvthaCspb7tJBAdW95qxAnCvT6MttyJeL
c1Kaodm/FvLQahzFIiCuryCbcdnzoIjMPQshQDICcI1YY5N/B1Kc+vCdUU/oPYrB
jM0=
=8aac
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2432d860-926c-4ad1-ab35-c3b112f1bdd3',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9EKDDoGrdSkppKMz8i+EGSSlGlhhWwjNsviNo+29KVkUY
0vmR2gM/gC4e1ihL/M/cUjcZApmotZciMWUxZENIb30rFz7DIqJN6+m8wmsrvpVM
p2/paCX4Z/spiZd1LiN84Rfmtn9fId8lxG+iPp0zfxYm8UFduUSy9pQ02Mo5TcLW
9e8zRWpDnbuHB8u1ewRIJ8gxn2eF5gF2A6h8Z1O3g/YRq2gLfS+RmEQrykZg4Glo
QQUDdzU1uwXQmZeVYMRm5gsxbbNh8ja4dBBoyHNquDx8ODWpph+lC0YgdGaV2SFY
eDGpRFBfMI4gHBIEDp+5mqYXA1x85MKxJNwEKL4bWlev8pa3ajTq5x3cjjukyJIY
WPojE1qRb7BDRxD2MGR/NGIzEZEfvKOB0/dEQtOcbXkL/4gdvqpFBVoMeDBG1l3J
G/jAUuqLlyst1ZwtJp6ndQxbZlaP6ZMhySlUDzwEpJLaARezcmMH4/1Qu6FzWB8v
UUo1dxiFyGDiHhAUrMOhSXCIFIuLzbkrWtx4zAN06SVFZNTDwQsfpPu3T6YdCfdT
u5ec4/U7PH1Wz8ILXe5uWFLsmByzhC4X5icTMiH78+L5AkiUNV0/wb3vZW2JcIIu
DPtk1RSg2eKai5O+kpnlfj4vLy/RmRZKYoFUb8Ewz6rCEuNvuImcLQ2BmIAxC8rS
RAHzx4Myc3mtIeU/Im2d5G7OD82ksAUVwlNBQLsIQaR0Gqlu2y60EKzB2RZtE6h6
b+4HHyVBPl2TP7Fe1YVacDvs/Ck7
=RL2x
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2860e02e-d97c-4cfd-acca-3283367fda9d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAt6A1MDjXPHvy1azHwGv0KAnMQWF3e4aws64LY7MiiubZ
rKcRdLx2TD2IyNMcttB68NowBzFR+FihLq2jLatCFYCAUJwoEUhghAgYZFqV2STN
lofSdTdn+kN+VGuUCO4WCv09pTqDNCuuIDnJPy+c+JNejHZhllc385AlBbq4nGXg
AlIr19jUKc4TIgw0jF7gd+OTLIDVKKTEIWVLFaY0ha4hjDIdACzDfI+J5VwEs2Zm
zA+h346DtH6Jvpk5kRXhzlZ09vaBc8GVNJrjVlEKAmRuuKeX9hSIvh0XbGspcBHA
xRGT6RXy2GcJVmMRjR/MUM/eplmFB2/EIPpR4pf3z7SYpsSG0y+PnjTWLqHZ8cOL
uqvuXyXpJEMskQ29vHmq57/mB4w2A+3XcpTatQU04ptobMhUjo/j2NBsDA0P4AS+
vM9CeykCFp0GnoxOCZNrUXQZuAgA3TTyHk9okH0nGuryRIpFaqVjx60UjOm1/S5C
lP+qPNYnCdrPvMTQbBztxi7gMeIuSP/QnsPEtbZ+0W8fDcH3UuwwxwXLJeFn0ESX
/1W7ZMM5sTdh3+DjF4axC7VY6RLVzTRGrp8dCmAIr3CPuC5ssrXDQV9LXNIItdNy
4S3/Y5vmI3LQ2o6wa3dUElwoeM7bCPb0/vMgEwZJzPz7cFWuMD22XnZLjCLLxvTS
QgEtSipw50AYAyyNiblxf9txKTBHzoWxA1X5aXm1UBlflx/2ZPSqA89HOPWKQ9P4
fiBtkrkdb/xeUa53jEipogoF3Q==
=xw0o
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '28721538-e66a-4823-aaf1-013d63236414',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+NVl9z1CJ+Ec+n/qvIPFNkApl5rV03vtYo5R6wch9M2er
6yFQ8/T2pUct+YrwiRvcbs4UOwf8Xd0vYG0iBOChIXwIZT+tHEiqn2s+fexwwqMc
UaFoyi8fRab95mhrPHffOyVwYaPFy7BdmEnbsbM68zeJAC1ClQGGZAFo/OSZmT8X
uTo+S+gIV4siZ2UWl7Jsws4iv6snzVgPlp2x6ZphS/vrpfVRIPzxCpFhFAUX7Q08
zFpNXjlXHtQ/pmuCjuOf8BdduwMdLXG8iVriVy0jq1bf9tXDWgO39QjmuRupFt4U
XHrlIrOsyEZk8VUZdkVb/o8tnYezkNF1mKdLfyV7LtUg3UgF1cHK4uNixlMJAJDo
jwvbDdhnMFFD9ly/Plx+9lBBEvmtp1nbNd2U+3oNDELRW/fs4N3EITepNTj0POas
7XbcfJ3kGppgn9i0MXga66wVRfDnV2MTwfuR8CIgjSTe6eJ9l4ay6t7c7GU02h8X
PFfjyALhNTb58XFq4oyh5F3KLoe79J7urH7o2jW0iQZbNGgXrlo3Ov9YtEMZKe25
Ulsztk7qQL5qco4AWK1115PQHGP1hfEVmHs+6Tm4p0On5ybE/Rx0Fo695NM45XQy
+V0b0ffUzAk4qpyE0DdJ0v6iKnXjmrhuSR0Z6wxd3cI4TZY3c7sPC3/Lha9+uNXS
QgHHlyhtB6eGP722wHlYvY69knewBDmvvp9YQWn5Gmtjsg8DJqJ3JUw2e5XXp6k8
c4DnUE1tWD0RUNASJi/bS4HRQw==
=uGVm
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2ab82657-f054-4fb8-aa08-4784461fe2b7',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAnovs/oQKBlMVq/4butgG8bCwsE6ruWbPn8LSClvgW8sh
sfI4aWmjKUyWNOQ1pDqQPfrFgqlr5tsoC/Ao09S2Bef+1JLy94qPHwgT+mp2UHoZ
kQAbRqCgob6UQAUF2ryEQ92lG//0TQdP8Slga5r9KzB3tql2KOl5moZMAl8jF0bQ
+odCUKEp+JTwPcOaABrmBcfjbAec7ML1nasOUoOVUzm0OM1JF+hmbrQPfiax09w1
Ws/j8QYtzwj2N4guQ9f84OG/To7Y0o9omGBj0Tmay2WEaPcUkeIlGo2rxmIrQrZA
mwUkGu6gNRPioUfI1fYkt+x2nafnrFQ+lEuOZQQEv70b8r06Ite7WzWMsEeLHNZI
y7QgSciEwWvMJxCDm6HrBVY6fqdcCy0san74HSBBQJaO1oSb+mA4uitBNMj+sb2q
QdTLwt4QBR9LhyqQnzk41BFoFCiJ9GHmdNKaUW/w0tHPzcS35rFhW2BI1IcAUo8k
GhivsQPrY864akGOO1AWRBi2iWsX2KIlUIqDnJwVaCurZGWyxsmv685cWZqPkPgC
2yG0EV4J2zAP0RSNUfI015MatQmTK/RXdczl6SrJqx1qbUmgXI1XDJy5FKeHyE2a
4w44rsZ9eXj4qZcHb17PGNcMgV9gc7zso2tEoiKvNO8RpxWz+WfbZ/+sxkJZKS7S
QgHndvPNX7bp0VCa4rYvzFwOTx7Q4mKFKOeLRijvk0+47xSWIqVaXq6HaZFY5Nfj
DLoZfEvzP1ryA9AsXLKZS1m9NQ==
=Wqz0
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2bd0bc81-6191-42dc-a61e-ec8f995c44bc',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAgTi6GjwEmKgs3wJ0owhOEeXGzfwNnIBkRPGibEAiXy/Y
WgZ7W5/CoLsv2ESFn9NLex1TZjq2jm68rXWyqYHWRBV9mc3kfCssNiBpqMeGv+kp
lENUKSfWWLe3bT8H5ArYFXrP2j/QU7aq4zgVtx7m/qOHlhDHDhjUn+mdOe0PMYra
dZoMP5MaqiXpc5hMtk4Dj9zOtCXGJbpb/yi0EIuPs4m8hcYXe9D/k8vf3o6A2e2t
Cnv0xaQfLil5MnpBNJ01u06S5MCvXSV5M9n6im3wYrgv+ZIPr5WDbXtW8lo5VJZ8
H5fS42ALjDVKTab2Rc1OB+OHlK+lSn+MjKVfgS0ikToICpup0vghV04/R+HKqLCc
dZEnRkTSWBAL04iM97wD9i1B2sclf+Q5bjujZgIo21bkVLhL086v01jCf8gH20x8
CygzUnShpeNsj1hINGCV4P4HE85cIil5LHKRYkzm5qy5AiSam051fx64c3mwRqmS
x57aHXZqbvsVs9tQtjEKLByRQew9FBwQwWLU/t9no81Uj73dXnuUEZ3nIn4HvSFb
XbL4Yt1wTTVu50SLs5K1MTHJYaOadniWyAaqAdmb/oghbYF8ipb3TH5VQ5aoceIt
MjFvk5PHg3uE2+tQ5s2t+00ZVOzRxNMFbh3PHSIHYD+yUS9RYfLRBNM8IWIOA3LS
PgHMOGfStz1U4fUgLjw/vyFyCmMHjCzT85lCU+Llq+7auEt3X0vEAKOzrg8zQF7L
bXwp+2Ugoc3QbEDCtYXj
=aKHE
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2bda237f-28ba-43fd-a6ad-97320459fbc8',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8CvmdRFx2Kow0fDLAXe0BF7L8FVbfPjiddxI0vuLwKxGY
j1rz0fvoK9TBhH5DfkbMe9LYdUU8ZBXy8ongnhQS0GP2gqyhGxAD+nhWqbq/jYeY
gh0r16PAOvRQ2euM3snHN4LsF4Mr+6IrCFguyie3IcYsp+tb2MI9TXOfgZYXi4Q3
FMbb/n7YDb9l1qfM4i5DYTYxcan0paGXOhSb74fRAsBeXrKwHP03G73zKmL2QRMl
jDbJbNHa0qeCNf9wYKHe+h0FEsO4+1rwsFCh+PJICW75uBlwi/m7UcRzZt9cWMFw
iqX/OcZ6G4OTkCByFTwMcMCdp4UOVWA+fRL4TN3ca9WKk986QGkjTsJR53N3wivU
wuLSZJDVxghcdCnpQxgPdmNGKW2sDQ7SU2zNJ12Khiv/QRC0ARKvh4q+qnNjGNyg
CJbHDx5ktEouQi4P24ASQVsL2UUyblYzcld8us22zOZ0oKJQth09o/vowxLP2xk9
b6rCWjIqEUVtkeY3QD+GauyDcxjLRapMI0RZTxkwNPcVbYpkC51wbJQGpFpf+ODb
CM31HJzD97yWz08eqI92SJMpa5uovPPjWpLhlOc1Sb7q/zZq7PUesQpAf3BgzgdE
odV+fx5lYZ1Ate1yEIhMpIFyqseLebXaHLdn4f/2d//ukzy9W++ksEnMO5T3iYjS
QwFzRNppOFswr3fWwqrhICmfqiN4KAKL4NoctEnPkhyGJUmPBB4fY4kUAGbpzeWL
mhNkCIdb21ejUg23WysTzdNvxrQ=
=dvsB
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2d9eab70-d8e7-498e-ad70-4c3d9c078846',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+LQlqi95fq5S56ayBu9XVQTktNHkEy5HS58KtupVSvwN/
TRflLl5APW+09X/Hr6H3TKYlek34U0Miy5cNnlU9/fK+Nm7wBp/A/z/y2T7fvzTh
AyotuPGGc4yS760uuDfKX/P52s5aIjjb+ssVnnQzXx8mCfteS8lyKhHeCylLWqzF
DR/QhJqiQn4roZx5+NvAlUClZwsYJWR0U9yOTQE+Kay1jlIXWEk9EqcVnYoWefjd
lUGxv+MKlWBoIcXSneiC72Pt52or6Oksdcr3CPsV2VXOeO3fy/SsUdNkJYtMMud/
9/iGjgzEY49vWl4J+JVSHRb34fG0Ts9e8Lf5weSm330hmDXUpKmW5Ms40O27dm9n
nRfd9U9ezQyrtqa2/mDmaInNNYXMDkqiTuc6kQmDMdrrXvBO/eWbVCBxxuFpTKZL
SFDoSSTazEzQnzon2BPVy5klY8nV+ieqbRiEcv3iWD6fK88zVgnLOzNGlAumyKgb
mNV5azBw+YHYqFLcTLlLZVLRgLy+oA/pzTSxcJWd9jALCdiRWQolt0j4kWongsAK
odOj6Q70FxPcK7NJlDfNvOaD7fkmY2M1huUpV6YFSEaExJjD46pthIviXrxmYT33
sC3EnH9RcgwSa6Jev9YZfGToU1GbOcK8ndqbRPegLTO3rIVU1BK5dlKjf03l3FbS
QQHh4CfyvlB6u+6zgKCpzGiiyLEG7QXVho6/FKohvAxf5zKM6mNHU0bbekaLqvt/
HgmzflPF4MiIU5pUpMnfHxsN
=1ymz
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2dc056ff-7aaa-4bef-afc9-e7f1df32c4d9',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/7BioQRIUfVPBXwNIdNhJBKJdtRCdDGnpZIkdKhz5gFlFL
dJX8xt4y4N3BY+ccIZyNYCF7rvo2ywkkxmdxTdEKCgvIms5QFrTmDsCwJHtsTZs5
wjdT6dVC6UE1iKUzNluYZ7s2YV6E/HkJPCc4AlbdVpq/4TJRi6XIV/KA4O/ihmtd
JQf7FX85/mH0/haxfopkqpBdNWKHP9ndaSyc9A1LN4jwfMFgtrl3KqAsP9lzehBe
pIMsclnhKxJ3zC4P79YQqDk1Vemopfcx487JD0b3KOR1MjplXe5KCt7TZU8maheP
J+xVVM2zfsy/6O6dWgsl/XPYc+Exj94RgQE8SmGjNeD4Lxfz0liREakrtEZVPCHH
FK0E+eAdCIZmOPxZW7YjATSMRgPD/e3zr2g+A0FODQZBFvpCrmtWVum6ZYm1OXRn
xQTpufo1x5QwEL/hHzHiTdTHuZHhqjvDwTwTNgBo4EQNw42OdODE0te+34VdHauO
Ah2sV9HozbdwGIFBYMOqobKmM+oHhKyIYt+skthcaDI1I5izwYzRSKWfO0Adq7xY
UKjmxLmLu54gt2OAjUFf10XOZFYKilt8MVCY88PLqYjC/os3ptTkuJteYUVWuBwK
26q6qP/7hZxf0+3YmwMHaCnoq6jbWjPV4NwXeTmFo/5DNybyy35Dz39qeQrPpBTS
QQHkZgQJ+jbHPvwzz+S6IzQytxMqgO2yHQK2TwfdOvELypkfeVB95qxKphLRY+Uf
rvjHD2jGGSD5kOySQJcJVa0s
=T2JZ
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3022c004-c7eb-4def-a7bf-a12d509ac9cd',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+N3hDJLcG5nxETJ6EbfG2BQ9jG+gwrc1TIclw3E1qcqXu
uyXJk3DVdNW77UYh2fTx0WAsgvH9u19fCd1Q7V/D2t1E3ixmmid5GwHAMUFaSvlz
vpagaoHo1bsHVMxU9SAoJh8saPmCMIXXuAfwakNy8+Dv40ZZR4htCrhnRtduArvm
7VNHIxlWGclv6IGw4wx0uCkGeRRiJUzVKGrj+qr7QiD1bAD0bof+IxOhSrVvbRBh
ewpzuFbbK//s0ZMUy3Y5BELcHdfkbIZHXrIIXudys5Old0lnitqPjjsrWw02NVeI
oUWMzpxvH5F2VvSB8sCfer0ucZkBIO1ttkjBo4dEneUTirUSkjenNuwezfVN31E6
ev5hCMryEgtbVSza1ZAGU5agscyXxzvn7Bf15W8iHmQuPDXycX1oZb8uJnDlkMm8
SVPh5/vpRHRgRobdFn7lsrWCcXwd52IVxA6ugX79CGLkwUpa+/FwbQQwd0QY89b6
JS8+VlIeMIDUH6Veko7DCSN5L39RXTAssZHdSfReDoQ6ZfUmy/msxUpEDqqsI+4N
K7XGwTynHcEysZgIMpYyfjWJvABnSU9uxs5JMPfxi6Rq+7hD5iP2kf8koPUlS4Mi
peU5BPMvq5kWsHg/HSTEKrbT933/eUyXXUG7clLXrbmrULhNcaBXB5TTsMZ2SonS
UgHKXp+HS748DaLXLObaTFlwejB4g+cL0QhV1n2FFKzJxAgupF436edw6JqxPghD
qb3c4/xa2IpIOclosJW+XlWzonOQEMspmE4vIxSg81wU4Wc=
=IcAd
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3179e66a-7b6f-4ff3-a683-b9f7a07c2284',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/XVvUOB+UfJ8X3FNilWQ+DJJxjPHCQU9LjLV23Y9S0lo6
dEd/xRBo+JrQknIO/9whBCRw4CpeFhbk/EApp3mbmtOwwSnRg2dJyFYi9373dRA0
v8F7fuyOvfPXVZTk4Ih+LljujQH4qiXg8vAwO84VP19Ti1FEiFLCJfzXjONZidy3
oiB1RHjcogu82ZmtZuo0PFMm5U8hHyPz3eXvGssg3EO5R/1KzVDGREmYsJge0Iy+
dqr4pK59u1nYu14sFVjfMJjcX/mUPwW42LNp2I+xcsifYw9CW5pgaWzsZJcCR0bB
sLyB+2uaGnnkkJlA9cgpG19fSpz6b9MndhKInAOR5tJBAUV5QXqX3xHadzZkOjQg
mfY0zCmBVHwp1rVDvaQeg8Reu28n9KUbrCWdRt1/lcxlUKNia8Hkzkv6Lrfv4t9c
tnk=
=EzZE
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '363a07dd-490e-48d3-a1c6-0b712e2e768e',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAt1UYjgbSE7ovSqzy3//vcflwnRRdoU4Vsh/d3LNI2mDV
OBilQ07CA3XetkEaYj4ZSBjeHSO9MeT/elnO5BNdEh1IEnVsHJ6bb3Zwv3749XK3
C5DBkXa4MNUlS7TlllmwUA+xmjMnyGJ5CnZix35RvTyMVRljwuWLpqnWgTYDkwEq
RNMxAhQRhdX8hJ/T9HN68rtpkYoBg8mzis2xRqcPD8vI45fcvky22nWh6CpQJlEe
YRL21m5d95g66Vwrqv0Ko3P/VaeVGG0PWsLqg91XVViYCQkaA45ej48/ytJvbrtO
z4Yy6Jf6u4rVEUP3Xg1k4ToGyw29XM27NIgU6K7Pw4IQydQ/nF3E3sYDznTDCzvX
b9T52H7FkXS7FM/XepJ99fyIWgvHRjnTJ5g1O51Q1lnwmopkyRPVtQreS+5Faeve
x6oYdMZQmiXCV67LqZE7v7ZdvHu26DTDKHl8qLI04KDfi+TdAVHkF4wgjDeRxxqv
3m2Wu2q/rRhER1HoMdbg0G6gwd+Nti5wIpcoAP2e08MVIvVJzG5LNrzpdNJMsJMD
//ttrOHfXEOMNHHm7dJ8lbOxcG6Jn9aa1mxd4OVypOfpp+ZFRQVkpIdZZeCit9Gs
ZGiP6qNTbrC9jVtYbBLvdYp3Bh608AAXj+lZAUiayTWmLKqPQsTQZlzUCj93bsLS
PgGW4f6Wuom5O2gwjtVwMhsIihh07DAW6JgbC24+FE8Su+RHUC0JU5zet4nSj5Xh
7pgPCatdvZzLPoHxHJm0
=7Ctz
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '37fc8631-1935-4eff-aa6b-8075652bcc3b',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAiLqV8PBGrrWn752jvBBpT0PfZKNEQUQQ+4ibTgTDEF7J
TIlVqowhavACOKUPgtTJ8OthBSLyN71zJqyIosxQPxsjdrXP9XetpNWLhFNvGSs/
d2F5DfsTvLqAvuyhilQYkXP2GsjuOPTchWCThZZ1PA9TeX7a/y5yO46FYmejyN8v
EApvYfBRiwefK9kd7cYkQDZbsmbBoPnVL3BxzdUriZg4gKOvCInwFzA7ZfCnbhXz
/5iSzM78rb14Qnp5kDqw5HdHIOz3v0sJ3ReZdTNVKcDuCFZevRC59Ph/Q0jzXo9S
GOaDmBkMj+RGzaU2qekXs9niEN4K5bomL22VIB5Inx5EPygm1Tj/xv1BX5NRYvWO
a2ml8Ch7ZOsRTZXtxVqPE4562upiEXe2GYqa79nDV8SCdfe0yASz1HJp/LQoTM+G
GEmOwcpiXlglBT9YDVecRrM498Wah25h4faVixquj9gSemZZmkLH7QPayAH4MMNF
TMNjLlYpOCcF3EpeOio9PPMuXKaUgr5SeQRmcXRML+tcQmyjBz43lUzBpmnCyRgc
Lj/DzA7ONrDrEgnJXyYIC51d86pqGhYxdvMLGQXcfMo3YF5St7njJDf1PwY5kjLP
uvAQr8FWYQsKv1cQEKbpoMbF2ZXSNPhWDzLfXoMgmE8YhiclD68+JeSxLvWi/BPS
PwFUYZUn8z6AKaqP23QEJD2aDoC0U1Egpo7kTUoFDqE6Csmylt1MKzsxTbeHeYX1
pQ80uoIJMg2bHQPMpQermg==
=4ZMd
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '395cb592-0312-4ccb-ae17-93b3edb5a5d4',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//exZ2iRbYqqGpF3gfb+cnomfsA0/A1ESXU2u1lnOsrlwV
82SNeWE33RLWJ20lofu8IK4bgDmoQNCjWfWXx7PUtPqjMrljxVy4Jey8CNqaE74C
9iHhILqc2s+uMJ4fBoaEhLih1dm25+iwRSgvRzluAmaekCg01ZFeDZzqdIhDDqF0
BYcHCIpfd7+76JLfswPnuYP73U5LsKXcPiprtJAFxllyMZo91ihHTbEejTd3Po25
z07IbSy+/gZqpOFq5L+NfwpYJTgtVuEomkt0iObFGG/eGFD6tpbGvrJ+BAekUYjl
p8r9OLZ2/4g8TLLn9kj5PWMoQt1WdMbS4lkF5jo3V7JM9BU7vPwXAuAyvlOesSD/
xTAeBo/F8Nvw9zni70D9f73Qtml2XTUpvzaduhbTFwQ6hgJEiKvOTPQDpRQ1/oKs
r9BeZpvH+CrRsdBERhRiKvR7ZGEyWdnZIyEp4MXiWfO3HIBGrpaLXQMwraUrSjwz
NqBxbIPlqR7GBNeoBtmye3ze34upBq5FvuIzeXD6OlbzgjnqXyJDW8sYS9Zyz2bI
0T/PIh6eVPMDTQN7DTt8iNbLCzGx4V0wVkPS5OW0r4YOqyCaAtXCRJvFqd8dBBfS
MjvmgCNl3yG+vUVRjUAP8sXukzaUFalwBIJ/EZ57cqp9Qt/1pPNFid8KnMDMPczS
QQEYZwDjh3yjHRMOeLvdnD7oIHp+wnFbihYlDAQJL1QkK+1iEv/wKf/bbz4JAxfK
iSr6b9IQ0crBpBkbFxWGkRyb
=YS2h
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3be01d68-8109-4a29-a6c9-a330533d422d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//WYfgQk+vjiOjj4zuHleZbYkKk1lejqZALI1ptbNCryQZ
AMUWM8wExi+Y66eaP4dEZvqBFsv3VN3803nkkG98q6OiEkNdpcSfRX0GxDpy5AWw
7Ztq9VcgWK33N3BpjcOGbO6cdXawyo8LD6+i77TyYj2y2Dpg19eij/K6ZM0V1o2h
ePg4Z2iB5MpzAYtmI2sBDCrbHTY5VQ0jM4St5YdfNochMs2qb+pv4D4m249TCl/T
l48KaFROII11s2LPP/k7xi4GCBjTABn95duYfW7S4Sdel1uElF5/QqCrprCZ51Jj
OjtOIi7YZTTRuxLicRzFgddroLp3tlWw9cLsd1ULPPuLZf3ZAz64SQu/CwXPoBXZ
4OuxioeUDP4G73WsvBs8FWe0Kv1SwO7UnA+pEKb9Gy7SShMDjK+4XIN/IVq5SfRt
4aeJVYsvTTH2NHMjktPXQPj2nUkbJLo5gaDtRPIV6QpqtwxXGQXo5s5zbFSqt+ol
n0iqKgycGlLHr9kr6caM9rxUW5HU98/mmir9+PSuyJkM3/UBLD/jo0vlgOu9K1nY
8ovpPbVLpniJkdGxPjwUfh6Um64FpZfcYjLglpHMMrgrbXerT3txbW0Vx2Urrq+3
l1jW4WHgvfM2S647uXIqFwfpIDgyilFiNWt2qywISvKFkguWud/9u2psSGXPnmzS
QAHvjoH0eP/X2Jl/H1ojfj7Ybnx8F4TKtecDO+tEJwPlFGhbyN/YffQRCQEFOlk0
jdG63GnSApcFMgZrXrBHflw=
=yz3/
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3cb12653-08f6-4f51-af5a-f54d0196d808',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//RJJ+770m2Zd3Sm59cWkgwjfuDTGPltcg5M0jDXcFc/85
yPxMipTq2B0JNyrL1J8oAKkU5wu345y5dJCQ4Tmeb+JXKzcVQedBH4m1vnP6X735
uRDD+c7cjmZ73omF9Unv/zOvcqU+MPCcu60Nqyj7iRigBM8EjY/yD4yjckhX//Ol
UJ8YqA6FjP5CTDsAdZpKjUWJq8Lh/Qu514Amx0aUv9X/QtvsozuUQs10YfrXel5o
djE7p7tYB7nwjr35dKpMHPIUHRBdgn2ky0g/NmfQ+YMWpGXdyBsO5V6Ft82hRZEu
nH1Nuq9MFyj4AwKXnmtyM7aKrobZaY001SsQGdii5p4g6NYG03T7LK8usZONosr+
bfNzsRQbc9B/MlYZP61W/0wH5KIO0loLLEW6AjG4mB8spZVhvwA4q/hTyFMr9/Gn
IZQjoQ6EOrxXSe2zqPnu9qCq1/THeW+splbBV1/O5cPi2Wv2w2Gp+eYtPXnKoVsG
p0z/SeiSOJIu9OZmmPg9Z9/8Y2wo0HncpujFgpV0nyrrn/BGAm7O3xtog9j3VcJB
EtGh8U075i/juYZl8c6MAvYrzp81oZwSVHhkOPsdQOR711BmUuTizSRBPa13cSYY
OTs3EXzFfwWt0ckf2p/O+DJZcdanWG5DnH4SrNJL8CVQwDTzy2AAnRx+lldd08/S
QAF38UuI39pxNEB+InRGE0Bp7abqopGtrVpe5dEhTCwupfwdL63dxv7qI6nl1obY
8D7HP5licuOHY1Ax3MqBLoM=
=M5Pl
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3d104438-1cb8-4e1e-a99c-d84156d8775d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9FHVvFE3xNwuZPK/YMqoRYm6drjadcD+d9UKvrY7sGnxo
HNDuFyJA+xSkDK2qEzERn1mKidDtwFXDOIlfQnZcjfik2Pj/f7XxYsQCF5ucVAeY
yi8W3m7YkKA9x7649reWJbUGt/0xhFmilB2X1S48Eb5SB4DPg3Ky0RRorGp3Qfg6
4D+eujDdoIdOSrWzlMp6HUEPKWh5KHoRGDifnCpeg4jHtVE7mxqCc8kbf+S1+QUg
MRskkp5yRiyPtrsP4Ak4eOZtNpvC7yw0zqf3nOcsZgBVN5SMBzV9yNe2LhYW3sqT
fZ5Kdl4FWffuB1ebdExIFvgqZm6tHeA3elkQ1MFcZFOvoeJX/joFphjRDH/bzkN8
96AW7mbMIgPka62eyJvgPbMaIG8LeIrBV+B874VF6duznapm1JFWyvUh+9z/vrsC
eJmzsYBOR+/Yro3th62WNfljEvrd+0TpDvGhoZNOVJyivNKCBrfLYu5wJo8uelio
dxzYd2Ulf7b3lma0zvbQuRjEw9ukp0w0Y89Rb+R4EmhMg7KHA/IW0joCcRgZFJuv
K8pSofp2/zO8Lz1Wpxj1DmhHSPnTvdkz3niZND8M3xk4YLwtdQ0xdb7MYRFrxQTE
c+iESXwGp70DnLuYEhTFVLQcK3moGZ3s1Y+MbqJUIpIA1J4OamBp4TJi/NqulBzS
QQFxxqetHWBL+0qIfNeb+NeeZ0gl81KvuUFvXiNGaUhJavrmLX0XE+O5Agprl9sv
eGTNS5b6hPxh7MJB5I4mncxL
=W65b
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '431c7f50-f53a-4981-acd8-5fea08ed09d0',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//amQQk88RUX7SfEt9gSAFbv8FfIbfMc2fcm7/DDjm44T+
k5qbPkT+0T/dFS35CKCVAUhrJnB+vudH1QcoFzIMhsIklkp1tHMq3Y4yrjRllFnf
EFWswGJE37m7s47t/jgmlG42KoLoOX0ZKD28vb88MXW/e03PCt8w42YuuZIpcizQ
iNeP2apLokJweCfoSPFUGfqdl55GxAgAZC6fsziFEBfEEvRcThnBPjDzGN1DpNWf
/QQhWUCZk0vahdhtmrgsRc5F/ooyFRrLWFDp8xz4MVCDSQT1VTZrSCTMrs+jlPLR
zQvfqlicZ2yOTuW+GeaE9Q//W0gjfje1MC1cwx3Dl21nkzJcru174LRGvGOh6adH
hxdbmYoRpV5hbBTut+gGdjCgABsmvY1Fub5C0IDGufRyCRcvnePWwpQ/nuuzlPc1
04YXIAVhWfuH+ubdgnwV1uIAzcjrTmkhdHLxBmahS7MPoZymIBgyft3mDxMTPa0v
Y+89dyqL1xzhCeCZHJ42sCtNbq/BLNOhMvCUJtvW62ispg+tO6mS3TTPeuDWdxZT
VNCoBboPrWdc+77gpGL41o8Ovy6TQWdI0E+weJjW5M0m/PjjM3ufvJRsow08lnvJ
ApSDaexNM/siJ9gLw2iCsj/ex+iR+9dRB+JQuex8KfV/jQdk5Gk+x0lC3bjmDyDS
QQG3EQ1fvCQEjknOboHvtLWUxYfgP3oj+xcB++57fVuUJPLo+sd9Y8+9UpPRCb5B
enMNR9RI2LlhnDADHhISAuu/
=GYYU
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4392658d-2ca7-4af8-ae94-df52bdebd727',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAmVK+mz3QKwI665v3gkR+6hb4qolZeDtX5+Lp6NjjLXAi
UFXl5U3Qu/i/XEE7yb0LMhxYcA8Vx/Wt+g4cTF/muj2b3WFDY7cvG9Z5NZtCj8UB
MHyTsHmt1JYFaw1GwZXu7Lc7MTxzxOI1jcVyxPQCjQrUOWS30/jvwF3yM5VTXp1e
IvjqnF5AzK40TcZJop2z9L8Mvgr4zTylp3hq4clBEJ0ADYc5oNdEAgQ0oFqSRTHr
qGc5CRnj58Y++vJTOOCV3xPfMP/qlTz0nj+PRiMu4LFHksNf5Y6AfyHlPxl6rGrH
Ww4cAVt0hSUL4E8Tb7sJfc+0mNI25xeRtR0qBvZqxmAvS8fBX6AMTrX0R+cknAxk
j72jxjqvB1XH6y8D+lNRqk1hDp9onh/4Vh7Cda68uWVjtHkJmTiIOTJEmkzAR+tK
i/3gsfdcWWk0O21EX6q6Ogt9tIbfAFXaijRYhIBF9ntzUd9Qt4UgDAx2KqFH4n+N
ZgZ2MQkv7R5vUlSlxFfRtlRmRGsCtxeuMzjo3Yh91+dMJt2GvMeyYgXPxm/Z5Qb1
iLOGAJTAllmK0qlm6lx+sDp6MolOv/jJhRZuyFEflBgaHSu4rSKdFdRtMYbzMdBm
FyxpEUTNxjnrn7I2eaagsfbHtHAYXYm3bFAbt/+guoUkTSDvDaJz2UWNUb8wu9vS
QgE0yaOs4q0CUPXVr0Md03uftxOSWi8TIJFUaqMqe1gazaKibPHKOKeCWwibGtDQ
bjq3EsxLin19e+d7WMs3zZLP3A==
=ei5K
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '46b5c9bb-8137-4756-ad5a-cbc87025c44d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//WllxbSQoayOnyZiMCdg/L77jmUMQPkmtTlxDa1eCUdmF
/kbucyRDPo0NivIVSWS6viUS8k3OrodIpycij9Ggdv9MTEjAhrrFQ0S0A8ElGf6/
0V18lHZN8fR63LWv14MRYgbTsZWs/i4FIk6H5mLKI7RJtOy7TPobMcM88udEEHN+
FOJDnJfLcde3uQnArGOv/bAXycLZlgE4arwBEnUxewqSNtAijXZV9wE12NpIlfsU
+eenE2r4UkCD+2mJcZ7oeTTa1JOtsrXQhoTgtCTcAP4o/DbM5WSYlyVFATzDWcCh
L1jEcoA+R23nTxgvev+v5uagbSbGGC5W8JuNel/VAVpkoU148E0lyspEojz1Lq8R
1Y6zp6XQi+QEnpZj3WXbBm0AoLUlF6HVmU+0oMgA2pb3gHweTnONWpjc5abiMoJt
ryGWscmhlVzz+xHutVq97K74CkdCCjMjEaPzU4rN+lHxPyjkSMV5UwsdS3XbNIXP
Ow6k5DAVQImMe0NUfivI9ES7tEmHDFrExX88cjKToFJio3uBNp/3oxbQ9phMjyVW
1cu4gZYUP3BkvUT7AU6wnS2JBXUGOK7OQsYDRbmg9BkUrHsOn/LzZZacUJIgEwod
Vdgspon2tnzRMGhkxnLbXnl7qpdi3zsMBfyNzy60E3ytOgAwKF0aZlItethKLM7S
PgGYJjke0PHAyEhwpmBo2Qx0ovhviPVT+eggy+VPJ3Me8z6c7CbOJqG6RMe2Toj5
nIs8ieSPu20yV1EZPJHN
=6EdL
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '46d31123-cdc0-402c-a89d-7fbeeabf7d5d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf9GuuYHeN3alA0VqfAmgYOM7LP0Im+LY7FXPLJT7mmJ3TL
UROzzl7bi9qeyzW+RAoWau5++kZC0azmKVKx/TvHhuArtPsVT5qKgN7oHmH20ijw
5aF5i9q4T/+rQKiL1POVFIL0tqm4O2sHcOQhUyry58Lr8YrKxWdaxCZlkJsIIAyI
IBZPdllahJ9ny13xdq1CNg3kurwqZoNA+I0Dq7zlX+5VZ+cEpphqpwvq4jzhjjof
G/zU9FDZjRikyCCmM87Dv4PkKoNkKtCMLdmnMfqLz0g70YjhbrMYL2OHoE940jeT
ytO9yVsZRosSudMPq/vpAR1eNDBx0c8IpnHlBaXPkdI+AWC3OaSIBbE/Wat4PrzJ
B1LjUR+aS33e+hwMEJ7jAUvevKeQFDnX0kZ6d4gFqduutLojR+eBEFyWNeY4Izs=
=E/Va
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '475cd120-cded-48f9-a1c1-3a7d11a63ca0',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+NaW/gDXFOgT0SQmb9qAtSusWPd3H4YDKRN6Ff7rPuyHD
RFEvCERm7dOgR1baCq5WppcpyFbHBIAqCXU9ykLlLFln6haT4Mo1K0cbR2U/aAGv
4dOrAQovn+EFk3aDec0/Wxa7nst2b2CgvtTPfXzmJ0Q6aRLBp7YEdIrVIYBlAI0X
MxiqEGdv5vPItYXhJhsO6Xqkpfi/MaIrqdxx9OSohhbVblVRLQ7RYn+/A/zKJjjp
58fTtIYj8oLJ5WIXd0d1hG5Hm6JPbwZDYkYd+4PbY/bXlbdRoNK8KXzhA6jKrus+
nxVX9zlRjH61hgkDhbBJ3sGPkE9642RS4ybsrcFLGyZQw24twgHKHX9bC9GFVjOA
tjy9WLYpNv9z4TQyJIdIAqQYNy/0ykftN6wf3SGV6LnRreUn0MrdAFFY+QPe5k+l
tKE5/tbOLw6XpcEMwfz3lA4lKXyqnGKV4CsyE7LUf+7RVul8JFLyDjv86LKL+xXC
LTS3E+kr3IC3P09hndNvBhUv5Ht1z+ByhSXvm9kme3WptYl83/7mvUlZMrFK8HLz
3YFEiOUuDO0HQXZKtSQ980V5Dg6LK15weMDU6k3ePimMmylN+5vJecAfIByAsohv
IuVbAlf2AJschbsyWuxsUU3w3Wv39cmRVNPlzWOHj77VWgq/lZ3cxvk3cHr45kLS
QwEP20kSj4pN7MDxdQQmDIzMlwdwpmiZCxkKVcE3Hv3L93sK7wLVXNNTEHOph9le
uaFRBsrwQygo821sx9lSW4RQZqU=
=QNtM
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '47e8296d-83f9-4806-a792-b7c48754ea2f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAwI0DnYm/06XlUet4wly8K/wkWUscP6vjeoOzeXsw64xm
sNM3XDrCCXClh6CZmIK4p4ngkmcjP3+RG1LiNlaUG355o3LDcNf6LP9m1mTRfGaa
KLOCB2CZI5SBSjNmjtcRctuCiOkr+0TgHEDLWk4EjR/CC9/x8nzVLubBoVOBPrwD
2TVJuceUk/92wZmuN1zGw65XV8YOUUgAu5ltMKKcExLakwW3Nge4WEuYShBWXiAI
opThcqibOv9QCn/m7IezwSYBH9W3hb+mGOazpbU7oUjFn6kys9sheuQgOeWsrTtg
cPu+kufhjraJgkQHPMNfpHzfQKFJqeopC0yplLyiy/FHjJ7piwhxuhBb27azxft1
LC9lils4sa+DZtJ7KepJc83weN6hf2CSzuhEUsvF2IYepjdiqmyoe2vE55Cgn+xv
fGHH1o8EyXonUrSNby4IY2haFbl+CFFYZ5CRuNQTpCfQHPvzELOyPqBzBe9SvX1R
8TtaBArwMKLYW0nkKczxPMr/Jowv1ReoK6oNXEwxpnwKSfhtCbeVi4xy8pym40Vo
JSdk7PzxIhXcb3fz8P/jMNtIwm+ymRVWgFs7xy3OULZH4Lnt1RsGx6ptrwp/mLZq
NvYuWFIzFC5uttknwZIEcFTrpWg6haNxWQj9i/s7/VewweQlEH+8/3LPUCnqVH7S
TQG1TsbupylxZYoLnh5+RWYj2zrP2t4DY1VZvVy5/Fir6RPdJGIXAv7smG2Pts3a
aFh2ZYqMerTzzoby0edOeCHu1Kbus8tHft4N+oIG
=eH4s
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '48801e7a-9b76-4a1a-a37b-8e664b5a68b3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAop0R682eRkTcriKmlYohsSx7jJgrjWwCETuSh/96eEmK
AdvqrLGvtHuRipOfexB0EBKI+mhkaKOXVaInJEJStbo1JObcHQy2EsXLT0G0csab
xZBRgQT9kIuXz3/LhpbDEFaKW3VQutLLFJBVRcbyRpeEM1SQWuvBAPl98hBcJ+7L
k7Aj76hV4050QZbFmJ1YmhTvbQ9pwC5TKz3n6GmhgvPSb8JBStXSeOQ4MxL353p9
UYCwp1Pi24aJT/kbsA0Hju4q1iZMVBcDbTVoMlCHxlT7oQnIZxbYOQsko64itBLd
Iyjlf/yh8VOnNYLI80BH74APbvb7R2yaseX/xT95bRuU8vW6TcKiq+KXXt2pxw3v
NsujvFPEF4B6zXm+HwjuLMt9Hu5z2dvgN/x7K4m+T0+0MG0xOIbMcMLBRXI90Ld5
Q+t6EJDGlWsa+3e9/IEDe7mJVwAoyDX2nv0+Bb9BeBNmVSzME7PGc5AFjIhnJqvD
T2RIOFpHe+6SZVHdVTNy+tODfZu5fk4HPg9t4dBLtzoPguGTBzBVdvO0QF9xrhWL
vXggyoKINiYWZyM3lDGF9QP9o0d0PqfuTIxhnHDcocNxz2AxGUlTEOhvg/fGZ/w+
ZhmfnqqpuhnEV0aEumgV7S/44lUHqUvRbv+D8Q5aAkjNoa6LI8u4hfBadAsbVS3S
QQG2MVpb7SUErZilxHceA25B0UKXw2dvcokGDGc9MGUHcgTjQr+Gs875SzcHEs+M
3EyX3rwxivNRqbAs2IxmAKoq
=QV3f
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '49bb11a3-cd3e-4ee7-afc3-1363fade043d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//cNGDq5Ok8Jl4+UrU67XX+u4yoeIXxwDvzTEZgjgErnGl
ChzqtTW6Zv6nvg0g26wVtNRlUuGqkibdXpguZTzJbrl+vA8PvSW2XdI8IV3r25SK
yc7ZKa1X2xLDNV/zb0pd6w1R0wlhoxqnOb4ia1mh9mS1t2l8sZA4R2mmF95tVZRA
70k0pE1ouLcjcmyHiODTQF+HHM9U8sAKoWPfdBElToqI92AT7G77KEqzk9/i8bZ+
lTMrEfUOCsdfhsTtuiTB2/PdS3oSXyy+n3e1qdG12jNihNUQHQTeBR/zdxRCYWIg
vc8RTQ7uM03eJ+I5t9Wk8UWBepXkwxUU8p3O0ZST0lx8uWjg3HRbRhhlIbUxhhGx
Cbp+yOCQjvxocEcf8nh5HV/0XM80CdIAOWDQHJUZLTO1Tq6NkSpejPCSAtnudY+e
VNgbxV+FrFW5zWutaWeHdTxwGpUKQeKkkbYXMISRxTlq2MeusIAL9s01OdSZcGS8
Gha7hPe+IGCQSjQLWACJQIMrwbf1PbwspaauRxQ8llUwJHkREyL2jU8evmpESYW6
wrrcduG1VdHHggiOgGq9TEyeXwN6krKf5S6EisTfwJiT1hnQhiOw8erQoVGMGJ5i
FlKTSmfHzBL1HySqTGFbjSY0BVgPZ3uvV5vSRfSeAEQ6HoDZqjjruTHlZRGqtsTS
QgGN0bqDaiddxFdmxNh1f2+K9+jpFk4v1HIa80gBCpwwger2CK5ImC+QrXCO81MB
v7/5moEXWKSEE0cKATWmhT1u5A==
=91eW
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4a6ed5ff-098c-4e85-a58f-072ace46296e',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAsWsYgAtZHnn11gebXQt9n9obAwKPnjin4H1xPPkuABJJ
fiC07KjIteiKUYpT9uXTT1q9N+Opi4hGoVX0GgSPekx5F9qRK9NS5C043TtYqAW6
3hOXWR7kdilnacm7cE/oJJ8pIWCIi0p4qITdCNdjvADzbUmOlLOgzslGMOa3Hq2A
Hd5Fn7VOqHTUFcsHxme2ek9hXKEsJVQBySrxJFVk4lHvkLk1s5wiW0Mpr6iV7F3F
v6gwTZlAKvg+fNj4D6BQNsMF5BN0pBCyGGpdxY1I0Y0qStaCs0T9Fd+bo7pFxf1L
c/ad77ucZ8q5CKfjlVBAq6uP7ruMtFYjKTEvY0ddMqVAWD3PtwFOs6UBXMZL0wsB
UE22p9dJ72158LVPZGsw35cnq6ZFVSxu90HJ738Vs3zCMaVq66T/jnEmg8DPJCw2
Kg0CF23oS4/XxF/p/Ai/TobOXmk7/kngf53C1FpmPvMwoWCVSKzBDN1Thiut8qtb
G1SilaNjvKLeRiDdJphMjzokUHEvMykNUHG7FvfgGnRQNJSD6r95UvIweIiYizn6
25i5DmAyKQVXH3SUd4M50UOv1oF9AtP0z3k8qpae99ji3DpSYntnHYTYJQ5bhDOi
8sq90qif6u7uQK0yC2xrDiPRoYiC+mVzkEXr0YrELbGxfZopFHqy3B1Ant+ndCDS
QAGHxtEK0vu352n5o9h6UBVH1MMADn0CMZImH8iGHFvBA5fAz63xBOIUvn4T7yU6
S46jLU8K6YMqORMseWiiEsI=
=jZpN
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4abe6230-f167-46b2-acf4-29cbd4397797',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+LwTKOLba0eznvPbi/P5KZ4B54ueMQ2TXWR2UY/BamZvk
hccbkQAXTLCzrOnsXXc6pkIzFte5ZKwOVvToOo2LTvPnFvjfNBAXEaXodWhg+B1x
ifgA+5aXXP5eENypAHGkemUCkO3/oxSCbR8Wc91Ehnbb7jqiIWYqle2+RO0RurAp
Ma2++Lk+8o9jzsXbHxWmc54yBtFidtzgL0RTYLLoCst3CMEQxxzSKzbsKzpZhW67
f/QNChBhmuGVevyLcP4dBeyd/tjLnhPaL7mnjQWvQd89bsDEZUXUxoJr2x2VngcV
xYJASrXEHTjYgJuagKgfSq5Af2BW5+I06BOL6mKIktI+AZksHxplYreZTm34JTXv
PUUCLY6aGpITtMfPJlUDQIrEQVBOrQ+i1eXkpk2Xj+MgIIN+28hp/Rgxic/Kd3s=
=W4Gn
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4b584791-8812-4bca-a623-2604e0108dd4',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9GBroHEXEgwMQQpa+J2iYqwtZArSUzb1cjSjJ5J7GeCTB
NsqQbn7TR5uPNOwPD32grv31YLmBLoRG3f+8UnsFvXo9IM7XRZP5gD7nJIXH7YR7
QVgyVMu2MXk3bTalUS8h0dNK9wb0JvnnedQArL2Jf6pQiP/YVc6bsySO6xDX+mrJ
ndphHM/IZRdAWzjZICyGXk56zx5nIG+m+Mi9nT5r+NLSG2AFAWjPrg1sXSeIJsb4
dXt9UMDnob4Vk8Oy2AwJM3832BdBg7GnTJiF80Puipqj0+prqygvaUlgALEPS184
7sNlxnKv1fwSEnejI8vccBIhKCzxdM84iPzvXzHgY+HE2IUajLELXhYjhlE1Ky2Y
beVQkdR+1DygklqmjAkp+gqlMGpq/UO/bZT5CdHkqIaZEiZr/K5RMWjJ0onxcwh6
9DoDz7yPrRfSXbSfeiUS8IWnlHqConXWKq5AKsgVRv1AY9+LFjbZ0c46OrDjsNnn
MLXK4P5fDrE6bJuo2kJd64+jumbX10j2lPt0Nhuf+THoYETZY6AqEOp35cCVX6YQ
7KJkKuSBVsKwme0tuPV2+P2oVn4rwPoRJkLQ3pd8sc5t/13b3BtON/0XFwoLnZjx
IZMf5CDpPJw9V0NkFi6OjbUWF9cp546DDGAH6xfVoyPLmsWP0oJEQSgPKAxeQf7S
QQH50YBaKhXmY+TbKGBS7KZYZ292BH5sUefagCcxbfGXgsyMMHDstkhP/koxkfaz
PiqiVBxg51+tnziM7quDrI6v
=tQSm
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4bb66e16-113a-41f3-aa8a-9c198a850053',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+MyH2SYlXc7yKVxKHrKR9elfRvANFfxnT4BUD3gzcBAHP
oaxv6Ug4IIAAS6u5KXyxJwT/vmi6gU5IKdo2EkQFZUAM+y9ucvj5UtUq4c/4xbVx
C2CiWAfEdbMltGQT5FAnbQ49DQ82+RIDEmdWU8tKe3rulWHyXo69wjTSOpXCYe3/
FGapMkv2un9NU2gchnNdw0C0FXP7K2tzolfNmM2zM9K7iwOHBAFnsv1o3PyCs0F2
guE4pFdJEaOQYjhUHD3c6JmKiDcln023bcydlIgSiN1KucOVLsIj3IAV0f2C6Rh3
bgjhOdwZbH8YHLNCljjYwYdG5jF/MVYCEXYKlP4tBRqfGGKWNd+A9VwS7jSfFIBZ
rg/V6RjZdR5Yp7RoiOe8g1FINiE+V4AcghD3QfHapYlnX8zXCZyqxaKwtZb0OCCl
c7kzwyoCCfnTGKZmaHo6qCiud7XhSqrsuyQL+o2SBCMJKLoM/812PL1FqJAB8KtM
+YlCoBclCz26ZWLIb7BQ8ZZNizsxxdR4H3B08O1vJztqDjoMzurO84vwuP8+2jb+
Me8inYg6yuRXottpSnIBzyMADpnj7mW95/iavgCEYk2/1AW5wQmX/8lg5rvWi3dL
fqWKojk3Ycg6jRY+fiWfWMA7fy9l1s9X03nwCuaqs2HmB5ZAJIm2/GCWNXlztKTS
QwE1YYJYj2fYEm6bGZksV6JOewPZlJZDdhgVfNVshX2krORHZmPevmoFhM0KWuvm
P3sat5yuMpWbO3GPzddB2FfrAd0=
=FoXm
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4eb91686-bd6f-4df2-a7f3-7a53b07d292e',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/d2BX6snIFD2+RgrzJqyKjtsgKG8Q2aMEVHmopdtBueO0
YhagUkH29FPPzeb0locdPIETNrDQkN20mAkHeiEsPysBIZnckQOAGqf5L0+7fL5y
Jj918RSsufI75SQV1EY9rdbbpvHZFYEhO7ABDwIryqLocxVM9qW+fdtdsi5SOyV3
OiDvqJyzDmJxFESfrtGKBfIdU5I+AAfLa4vN5PiGTXXz4PSDg8PPaMt72R8q5ZA0
d8ehFDSD30QjMpHYE4dHaQz0P+mEtOIVQl5oxWUdPJSsg1OJObXh5kWZEakSpwa2
vSjM0clqXhqcRQWvLPlFHRYVQmVQGCn4rtULOW63+NJEAc6Qo7YgEKfe+yr9KAcS
cHYIB3m6/EcL8/RzCH+X+IbygiRRthgMQmHqrkyrW77D6x608w8VOxe42UwKgj2z
EsTaVzE=
=p2z7
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4f66c1ab-1875-4464-ae8e-6c43a8710882',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9Hde3Bm6kGUDKMv/RzZQls1YimR2CvzKe9SmnBBfpXT3+
m53iaW+XSsbECZZIt8PTjz40T15AZkRX84HTjXADtYLhDgMXIM+TPznw2YGm5Bg8
C6QaeQ9ksuPSZm2Srxg074C33eCZ0niYZuqrxtJLdli5402lklLmv55lb9XGe+ep
BvgKwW/nWRoHCv+DLIdAEFuDTdAwx4qkVtQNAn9xTTJFKp4jXlab1RRjPs+Raojw
0CB8XZjmYXoQ1ay9a1a5Fxaowzt7vKwgNAiMEER7/LUgxxnP05Q4GDlvzVofMD/r
2qFjZUu9lNqM5Zt053rUiRjGIyLG4XjFeZfqswyWYEGcPO7jSqPoax9NmFAHFz0I
RIAg/PyCPWG36ZabmS1zpe5IqTKPbGbAxZ61inynPuYZjaHaFKGBmHbMs/OqYBrb
KFO1S3WYM97ODMAOU/iSBH8XSoVn7ZH7V0Hvxoa/mM/u4it5/LEx6Yzekiu0mBOs
OmzeWcrCGg084IoFHpYvi/PVXe3jhyNAo5EuMXCdAfiSUKvaM2HoLhQJcVaL4Alv
RuBndZk3J4zhOYg0j5GNX0ngXEZPIFoZP65O+rchux/E8iDRIXcSg8fix/4kDjYi
vUGYfBdRbBRiSAGDqSfJXhf7oO5i2Lv+NRzymhZrtVuYKCPIbr1fo0aBYYaw8IDS
QwGD0K3sQlwDgvHQvfqfHBmRXQ1LMZTSr3Y2vqERahq4WXLx+OAbVifCES7/fktZ
lJh+tnQavJ7jblaAog6uH97WfcY=
=DUe3
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5046a79f-af41-4f0d-aca8-1465464ab8f7',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAq5/zfISsPM/4yrlC9kZdDPXSsgkDoY/y+SdPNoxgddjt
ElIcfa9tbMrMdAELvv9UKhAzOWOu8VQhuOGe3gN52ZzsPRwWtTSx2yjhSeeWCGwu
EuuNdW74iA/l5pBzhaXjp8ixfDBqfOhHMSnO0I0WsuGS3GR7uDxmZw+ip4wN0WUt
bm2+johi9pTbsgULRGnYocxt5dkPX3G6H+dSxQP8tqJHnsWsRayn/lJKYr2NsQq8
gG/3cVuFg4a17S3y3l4EBnJVnproWbglwoI/7Z03Yt2UwJweobRUpQgQ8UExomzh
nfS/7L2PkOdrjukstIVvjMFugEYaaUdpfI13vJRLEwvPNi0BUuyUXo2p0XglyXVE
6nSIaI6t4GxnbKqE9vNuONa/MEAUkNkBRcBSN8x85Lz57mg1jXY1VYJBpz1yNDPd
sZI4ccFZJe1MBS27vVtYZwRDqUsncnekZ93FzGtRpSyp0Y56j4v47jGG+nlEyHn7
wUUI+igkpOnZ92AoUuRVYwVhazHeRoh2qZ86SjFZTfKsis+Vm1AubQrgaLNixE9b
ulaTVer6LCw8XW1sAoLBNgCyP8JdCxBEB4gU4PNJ5Br1JQX4ciz755R0Mfoc1pBs
2ZP+72P+RSnbIMAhAVXyfgpMGyq4iep+EWB+gTYAWbB1KFND7DNLDKaNbB5o1p/S
SQFGM3v51KsfO3btN3Thvsv9gTUlIuL/KjYmlri1zmqyhtBZDeSoKqurwtuvnlOj
I066vIUmWHtHMSNN7EJ/NKi8D4578Y/+2mo=
=3wj6
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '51c3f1dd-3b93-4d05-afce-b554795ddc1b',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAhTZJuWq3NjEgwIzSG69jqK9UgPJBVuuatX4zosTxEUJI
BvjDCgDVVDBz96NUnOiG5YKA6Bd0yB9xI3iuyBRrVuVkP/EGNe9h4Hc50vonknHt
ge+qRb95mXfzAGlnk6caMNrW6jVo7AYYEV0EcIc//Ff+YyuauFet4XC8ItoyzHWh
w6QwOXLZmCzUemElPya6X+EGtF6DwXZzyA+mgvGfHrM32+tNWUjcLnreS91C6zve
myCLrjSg9p1uZoHuqvm0bsFQW9jY9lxxYCkd02SjP91PIWJXHcrVamTF75b4TdtZ
va3tLhorMBi0Y8xF2d3cylCEcYOc+HH3AnLY8fokQNJBAXQ5CG2RKMsKPVQxDdH2
lEubN0Y6UcxEK9XOYUhUlBX8CyetQNRlGfPMdP92dMjKW8r659wnPQvi+EihaONM
sdY=
=aQcx
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '569bcce2-05a2-4de4-aad4-c5b53d43883c',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//X/3j1/9HfbkHmfgWzn/dsbi18sLknC70YAWsfFt7//FB
s0lkC2KRFf/6yNMHheZn8YG99KW2Oefqt4FT2PVf+HYR2Ep2zEt3IydFtRUp5Ex9
F62id0CYgoJ5lJbw52F+ZiWlfUxKItpToY79zGNJoopO7C2rydW7VUYQldbkl6gh
naWxaOF4CEjhAF+vHZcsTQFVGLWXUBSe1BX+4Bf7TiwQ4PRq8Vp3dTrFdmmrHAch
+8oZ7fuehJtDlSfN72ryIZopmabW9EpH9Ra8eV7Lx43m9Eor0wfs8fkC5YNUQ5mS
H87l3ZqO1GP9UKuVs056slWBrV27OV/VzdvRUmfV3x2CQIS8RgVQNo60IKzUQYwM
PGynmW3SFAt5btzlkaA0pyvtLxEOWN+77B3Bk853rmynAdedBpkJu7RcsZm/z5JV
ZAn2YnbpQ7RAIyoFjQtkzp1VONTxBrRgUjC79n4bE+UKiKXIByj5bauZ+hUid1YF
X27Ji2cCW1yZ2UWG+OAJYewlS2hNDzT+/HNvkcfZFp8AjTRxyBVDOO8oyAdakgiV
ShNBTens4zZDiRT26e0o4LdEHpYouOn3pMl++lRNLyYnuMbU5dAhHpUzWcpKrGhL
6IoKskQAVuWct9WjCJ3BkyFdj9YsfEkuuBz/Ah5ija/c6aJUwCLNhrA+Mt23qXDS
PgFkIGy221IgQcvF6sbwG+FHY5y7eF0WL+mdewLS7u/zd1lONw0R066zscaf2GCl
hawQdJVQf7C0UMblC3D3
=ULSJ
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '57fe165d-8d88-4f8a-a1e5-f2222e888a58',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAhJZ9WNuvfQBvEpGkQeZFGgfO+nJDpSW+uUKe5D5rYFBD
ruLBWWJkIMopDQDSaVzsm3ziH5AmJWG4aL3fXOJ5M86Zo1HGHk6gP3vNgrZpxrOI
OPO2qxZgecyvs+uygDUZkrYsc88Mr0MdIFxElDSlr2p0ZMvU16hkQy+In6F+CfYQ
Nx/mXzH4QLilc+vyjwD+mxKv9Hk02Hna8lCQOSP/sOEgkY61xNBG6XV3mFxrc+2j
sTOXCX5Imvf9nSfuzVMfKgbd8MA8VxbbJu+rHGRJumWcez7TwRbeoAmADyHa3VCs
UQTKb/IVFB9g9VcSKraaeP/m+3ZLZC/RNAFDpqnJM/2q3VUFx55IPX7jdgRqQBaH
uFWMlviaBXbcYRk9c0trrx8/trI/ymFT5cPLA6HD2XD411HezZ2grJN6cwzvtxPC
tU0LfCVlMN982ARc78otrD/zkJDJOaiK2NrkGZ/UqU8RAxTVWxZcbfJy3CP/nspD
1Fg/MSVxIvgSH2vMg5d0/xxLLO9h2SyosTJvoFSb6F/+RjaqkGhYEb7BAQ2eKTgc
anJIwWokkGDNcf/b+En4nxdeMMAA3fPr40UfqkW+sLr0CZRMcHLrAyIyLC3KzI0g
rwbuMSmykPI9bMeehWfbGLnRtQfS+y8T3ksNsZEo8zRbnuxunl6IQr21m4NCHZXS
QAEf1WqNal9OTnzpGxutlIJWQMUsTANZ+stCTs7/eE6SNOZNZ0uqVUPf3y/eycLF
v21CdhrdKQvpHKXUs+T98xY=
=WfbX
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '58ad7713-a811-4c90-afca-e169a354517a',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8C6lb25FiAziSBj9VgdRgFLfIOSaq5X8Vo8VXd9JyHrw9
GXb3wGzfD6n6EW6pfXFMIqHz0t4WQRMhJvyCg/K7jWI4DnOfu4yKr+nVKGaAD3z1
aHbKwNM3WSibRPNQtje6u09XF59OgOAfxxPDtinrfDcfLpHV5HLq0P23VhAxatnw
Qn8tYlEuM9XnEYeqfVUgSnU8c7ssu9URIleAy9FFjVkYdCyYzY9SeCTdkOA9qAQb
ItHton0Bv3XOtO3a+rNXgGJZZWPnlORC34ry3DgMBQ9uuk/Oj8rDZ0saDosIEbkf
W0EpcsL94lTUk0vTAW6B89VDw1po6rXLpOY+2PvsMC1ypZ4nkiyVFpHO1j9+SwBj
l0ykKgh9gtq6nVuOJQwUQ9Gn6zAr1D2W+W63BFHL2oJM959TsDs/4+HikSivcUcA
AUxtm/sgi0VBYkvISt8yyGLxMKkm01qgEB7Vc9Fd6uYFSKm+4EfY6zgt0MBoh6Xr
LGyd/3GX/L+PcoBitwYBp2jjHS/B3W+yP2g7JHRjybvQ5cS4Km6ykHr0nvf+7syr
hL9tk3DW/w1y9xd4GPoNZWz4I2Vdo4VqfQ3tiQuaosuatf2xwSp53VQ4dknZzr1X
GE49FMPOqbKDMKIpD+5UWdXtuUluH7XRdVrq6UswwVmPPiyx2RDKwvHTS1pi+3DS
QwHqDPgAPeqOeUoVUR/gT/pdIEelzQpbLVnwoWAv0VKk3ecPMRvvew1JcqHjHnn5
QqT9x9br26p7ZIx4N/JnxtU0EZo=
=GWo4
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5a4a40d5-21d9-43d6-a994-f59cadda17d5',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAApdHOGoDRMPbaRIOLIZ0lsWyKRPk5p+4bJQuJ/+O1t3Ha
OW8nRFnPHxYexlZRN49gCBBqBTvhUzdeZbWlxgaYidLsMQsFiSjf2H1huSvZ/YGB
0qBLBnkgi9Fu4rQW0EMJ4WMaYD5WKfCf/q4wy8cTFa8cC494rlQP/TXJCNwaeLbF
/3jvC5O/InkbKUp8pzFRQM3U3fcbgK0hwBC9tX9IsoZTKBbuWYhNkN/VWoSEAwd4
B1CdmWRbCv3Fydn2WlorEDWjDAjwfPhxKcx2qFDX9/CKtNUyLGDSgaOgfm9vAq/K
UEhXYFuao2JKdZLgqiUWkS+w7PkKWL7haZmLbv8C8HY98DiyWD2mb5uJcaaZlhzh
kQ43PVX/yEECEwAgv6AtuxGpdCqru86Z7AqzELwtFiZe2f83W6NueYSBuDb8EXrX
I+8urpmuUlhIjwcCa/jKR71W+H5a6nnQSTeEc0Cdxt/ns+ADw0GwZKGuhKowGT9F
Mqi1LaHhqCVPMn/9MK+Fhw8TyUdqYgfBQS4h1ukaD3VymL+vwLcuUM68TUV/5jjK
TTz9fWQJHdk89aS9TSkXXBASzhFPFHLGORUzbcJff4edqIl0zBw5qaioUxA6Aq3Q
VlEZqWNK+r5Muju8F+Mrb3gdUFM+wSpWfp59R0cDc2eWq7Wr7+pa6IR7lyWwbXnS
QwF/WSNLjoAoVYpvFr4eQ6CcnFsE98vYBwIZp8JLC5GWpTBIlhcL9n5PnDukzOYY
UAYvS7yxzXG+GR14yg2pB/xf09E=
=sSz2
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5aef54ae-7f49-4776-af42-7c58585b808a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAioiArqJu3dbtfUb3AwAKloR+CEsStER9YGQhtB0VStdR
XtonQT5/nsnv8aHLU2vORc/Ocs0FLxcCbnwaWRSIzW0wa8uOp82p4VJbUYuQ7qb4
P4jcOSzspbFHUyqnps4sANACSMrnLDVNwLCLNxNo5BJhILieWSNJWTA+SsLFYZ9d
0qvT0oVVHoaEDVQF0UN37Qr4VQ8ZprLNnYgHjqy0JFShqdNImVCKB4MA5zhG68he
R0eeDNUsrfmNsDPpC4lKA8H3D5PO/cvyFYejzgg40Ba/aL6JTsJD8TzBdCrEOsH3
GletmU8Gcg24VKBJLJJGqZgmByJGKoSSv298En7zpN7iS9TI24V/jKDxlaVMyGDG
Zp3Fkvk79WtiTYlK58OkVyLdAWHH0iSurPjNyFkBHHWGXquPjiiF+KSQHHtnOOXl
Wd+fTcZ5ejwNqW19wW/sIiv7GaaqLnRXd/O6UXJ7J50k1XcDvQwRUd8p5EtfQ8AJ
lDRWvHDJawKEVLLItqAPWMLSWaLmsywCionB6M3DLtcnrlEJtjZcw4xgEkyxQQtB
8evRZeo69S0uzDBbH51LwqE/cgcBAi1QIAReNbyj1plT1uQ4qC9VkKLO3Sp5+WSX
RelgofMsxtq0OEG3dHGLmKCyx05k1tGKeKYwSKTscVYAPrraMgl4qk6LBsGzEsPS
PwGAMrKlbumwuUIKOFobIYzMZ9+9XgiTzyJA2iIGiVAVynkFDE5z7xjl23ZpYx8O
TzJhC2bpGGm5Okdfx0nUoA==
=I88q
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5bf7ea17-c631-4495-aa8a-d540970d674f',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//SInz+6mro6aJZYlG3yhEV8n1+gE/39c97Y69Qu0jBOJ0
ZY6u8tjloZ9HqTV0yn8KLP7tUqNgh6FehXgOfGSwtuFXLuoopIW9gofaPy2QUVHZ
+R2NVk6BegJ3OVx6+EWRXuoSQ2/8VglCpBB7Wbop8udQUqy1752sQG17q39rBCSO
cGZghdKtXIYFwCJXUT6gVGGx7VdewasDZ/u1GPCMnalRUlmmDwW5EDUq7+KpS9AA
qKq9VClzSyk1jQed1wlDQ3CGTEAyCboSJUQg0c/enZ/ARYEdioEkAknn4nItlfrA
iqrIBL2KhQTPcEb7OT67P13mA6POnLnZv+FZJs+vNnbWej4oQuws1M2WjkI4SpY6
lGmjV4fVqqMIm02PuhRrXhhTTckEW9FTAYe/+cWceewlgRvllE9FJZMVf0S72M9T
WW2Dm43uAJmo0YjZ9jkyMmwDMRt4XYG09woEnf6bnCmcstECK63mZgzOSqJse60x
WHNyL6j5nW0rtrB+jNvithNmdWSN3z8w4+KK3oFJFXSRdPPX4WS3TsKS+9GJyd2K
q8HDCksze+GBJvrJZYTQKpHMZOUyot/BxpTc/qtsX6pSqXgnwbapLL8dRs7J5T2N
OYk/6hX/yj5lG2bWtuHncw4qP3C1K6N1MeuQCLYsRgWR+Dq59Mhx+DLqqdNmy1vS
QAEsRk7rCt/ShB0QGq9n1v4VBGhBnYZFV5cHdRbFe7i8TENLo7OnVf/k7dxPW/H/
qobkSlzJjy8DKQXBR8DIaX4=
=5Ui9
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5d5300ab-2c6f-47e5-abe3-7ba446de8d11',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+Nv3vVE7WoHvDstRjNTRTkaOIH+V/tUeBG+AzzO933y+Y
11J14ls2ul2E+MtuQK9PHa2Jt2AYzMPd3pLZVl/mTuEYQwj2rLYYsyQX6xtKSBBy
MVLuEBvhtD52D+8d1ttAcKynB0ocuk/xw5xvnihMfx4SyldgInU4a1/2sYK87p+/
TfRRE+F+wUr2mGfia0y4E6KZjw+5zABD5XP7JdI5zReiS6dANY3hHJyY8O3LgN7X
J9eFTLbqJ5CyEIYN//s+qTbOyQA+dBJ+bDucHqEHsu6Wk/tcRtwRfybkC4T4c8AD
DSc0fsJw9rORFo+W1T/WQ/vqXvTpELOsnV+qha5cFzAAaAzFKyUBCQcxj+lNKdtK
y151y8TuxjE1m/3EtEtFXGfxaw4VUvfymDM+NQQgldifXn6oPKzHGM3snbReXPKy
uqd4+fjYrqAr1FjiWjLXabNUR/9jGdcGCmsbSL7LtqnX8N+GEVeGy21hzNYa6rHP
N6A7opTTan/ufvCzs6+E+bn7HA+J6oVZ28y7cqOFYBCXwwlJlcKoCylnXVVSRvxX
mGWJgG52H5b/voaj89oqGkfVjhDLWAu0kwDGXxXltGccKQM5a5YqeGIfUuVlk1uj
166SVV6bjYJId/Dm3dgnwS9hqNyyShxrfzCpVr23CbMF9z9ym7VVs5koj7QXhlrS
QQGyEnlDTgOf3cHRb5P1596+uqrm4Cr4CNrh9Pr2E+xWvtGZK09x/5Exyp4M1fOu
R3KLgcf6hmB6SZn5ZCLBFiiG
=Mm8u
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5e9d68d3-a8df-4927-a2a6-584be93ee777',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+Mc6F79Qqa4H3+vOzSMiPqeI1s8ztlPjUXqZQkZliHQTC
vneOR4+ovMZmVj2Y1FV441wRBfdRP4c1zbrK6ZXLCrsjvMzYoHY18wM3W/bLhTp2
nM4l1bs7KmMcX+nIyrDkn75O0eWvCQ0oreadWFz7/WYjuONmObNSpId7I59qudsH
CUIdmHel5nhVkrIEjAvBUTUzv41YCz2diibxyZ5eWIjnBtOKPM8PEbJPCLa8f6Rx
s66vLkYdKZc+O2U7UlmpblTG346+TP3/+666NwpVH3mjflobhCpT3/Rc8dPaL/Vp
SZNZ5hYuMo/fe+8QnAhg2aYEuCVCPOAmI7wdloBySdAGE7eYU8fAQNM0KmdX4856
kpZ24WHwzkTXgr221RsOX1btBA6+T5IGGZV4ZpG7mteRCF+q3qs6LpBR++djWunw
SQGfGsiOUuvdjX0kcyE0LlvMBOqU3+IE6Ocbd7uxrBfmW7tyeuKxwzkWJAhKHbCk
owx1COVpv7SkgneFVrK682MBRDLIjaHyTqKhZqK8myC4sXAI+7mKb/2YJ9Lk6jga
W4PD4LpFtPC+VDGMoaoP4eOnAdZ8yqpGE4xcljz/bA+2vQwrtvtYpHAQ9odrUmY5
4FBfF6gL0NkGVWFLys+Yano1k5fjHBbzOFPYllZEuqRziwMfNOA9x9Xx55bofMzS
TQFMFZqPikRANO+/MhywldCFXe9KL5wQZPaoyJP911t2WRRLurVn/1xCjxCagZMR
wyf+gHm8+sbzGrXglxzTjO12rmmJyHGl+PgPwhnc
=rW5f
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5ebcf03a-142b-485b-a4d6-8263c6a42e65',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+JSmQfa8ZhVO24kaPufkhNKkLGt0etzXbVKbytLExQfM+
37ULp7X4xzPaCx7py/4cMfc4LAhk/yQJzBSGLE0Ih9PPlA1M68CC6TEgpboAxitF
WPXQAHIaMmKDfTe9Pn2OvgChbz9Vd3sz50d2feiiW1iTU7x1JdEL+Nq7hGZrFHIL
wuoDWYADJaAm/H4gF9DrHvMUA3fD+vpH9tsjcdvcVT/XvX0eUmw7we8IAqFAwar5
d0iTimjx8sEF6rghkzi7xCmsEMffCtSO5FQB5wwkS9SH9UU2XzE3WRBk5Whz/yGc
00dio8R0AtrUN6qBpmUCmsh93oJt2WE8J6uevEvITKnall8vsU5wgqkSA44N1dHO
2h8HAIwoF8L9ARBcWK7HTc7+5M/LJs8hHC8NkW1Y9j+8aeSlijena6b3J9c+QNgf
KWFz4bJUyXMVmJblbHW2tYbRlps2aPl2Y54LwXWbl0BXGCd1CyUEPewiR6xBMkOi
hzGrr39KtxpVagfgE1aTZxmGaaYyICSuT+vr+OYvENs+JaX8hQYbFCU20Z970OTi
qAPG8e7zIwu6bjwvsujOS2Rur2g6XuGYNWqlwZ1JR3C30WIrQRilZnPX6VDL0lj1
hMFsc56WSMe1EEgeI0+Dv5oiS9wr+xHQGxMPWvAkmM6W+p+mUKqBQ0uyCCrmLPHS
QwGMpmhVNnmokz9yj8IUUQV+brA1KwQYroBdOV979G/V6G1raXVUn+5QWse4q7xF
aIfzer7sUTMrmPKSYo0lj/mDPWs=
=FQBo
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5f2dd5e0-c5bc-492d-add6-e71ec18df507',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+Ke9+iRnT2ncpV2zpyNYAkIL6jMlIN/8tU9XcDt9m805R
WUYWW9hnXqvYaHoIE3e8iclVsHcmzR0ip55apygaLPvEJwwNB4U1WrFoVNdHDv9O
RSL1fozD5rdYM5NOvFL+UNEn1VjOZ3REioiOfepxBymY4bvCuNpuS8eMK7J1ULqy
bGOgLXLyhQqPZYoAL2FTOTwVPV6D2dodVQ2yVTTHQEH37c3jKkg1VPDNMCcTA/P+
qHj6TCnF81DaBq3/+7nJ8yFioI3IFoVAYDi+Y/0mj4/bk0j+b8cbNGMVtjO1ehC9
Aoo60aUwik7VGo5zbMx7G8S754kVwjpkmkBV1CRxwpIa2nl9qe8XRut4VrDACAUT
Frqe8+O2NSvs7c5eS0MJ0yRTEHCLpy7WLq8sDDYWfJWu5OU7oY5Qn6eHONF1KtAA
Bqkkw9sdokAd0U+OOAl0irCvTURlLt9zw0CR+xazz1ytBsehcs2P7vymfPtrB3oh
Pvsw15b58EMgc9iE9QxwGsK6+vIWZBZInQ8n5BhN9FBwp/5qYkPL6B7Poivt0BG2
qpqY5fPobUam+VjK2WsbEK7yAmACVkb4U7RbczKewAHJaog2iMdKwW5ub6H6moP1
bkKiwUeELsP/R1iAYp9gPlL2wouvAkMxi/IwxrcTpP0tEOP1CkFMw1OY5ULxPmHS
QQHbOQ2UHdv26/Nt1SQzS6dC30lPCDPY5NvvK30SiyYwchs6rab5HObTpMgaO6Og
VPoVWPV/uJJskA8nAcS3aV0N
=KEpj
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6178bc3c-790d-4772-abdf-03354b8b2e4b',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+KmWK5kFGjh14KPcBVTz8zRJGRwg1NatF/kOeSd+fVkeN
2Nce7mKbPv7dmCvCXh78+6X4jad1fBT4zT38jjPQQSlm3cUhjJdOmWr+VmT9Wvs1
MNjsWTBkcr4/Csbn0354DmjjxntcwgvWFSpuohUgT1PgSVzlttQK+0jy0YY7+cK6
1a/QJcfhIODDpFTN3xAF2/6DH1B3is7GfqkllrnQLpiLb3qfHQyVm1WFSOsBp+MX
d0NJexvOK7ic9ZbemzlmTOcwWSJRH2Io2rYkj1q3siCr6pcMCxPawq6gnn7TL/+5
FUrwOVoj8fk4cVOsFfvq32cPsFoqoiJwsJlv5rareA4BA8qNOzfKY6/KlMjpOcRH
PQcFHeIhuPSHDB+2EMHBB0FPIsfVgFiC0sDBk7K2PB4zTjvtTdxH8QJfPU6642SD
SbqmZKnDH6JCi/eQSIXqC+hFs4Szx2LP+7XVLj4k+9GZXZNtbdkI4orHoqFeyczb
qfRYyA3YJbXdvY44x75Gy7LGlJ5GishYrjRjxvIuPNzjL5WUbsiEt1b5V6ZbEHh/
gZT2NS21ySrynMmp8l2AkmCiebhQq0X+PWYMEDnm3ifiWp4QPERmCVCf9HgoU2eZ
03yjQzM5XZR/nqzjYBB70ousJJf+RLXUH4ZZvdHjYOjrbxBMQrjVR3x5wmV8lPXS
QQGFdY1BfnnoSNaL1vpyIDo9CLBbQQn2JXmU7nphlhy5ibZdRhLiWNUR9PvSMYQ/
dGKx0JSy6MC7PVPIEKV8VCfw
=VjHn
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '61b83505-f4d0-4ee5-a362-d7e1569a728c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//bvRP0WGHEuMBbufc8UW33JOKG6us55eWGWQUHb8l7g4M
FygPQXvkjTAN3LlIxteXye4eNzhgkFEl3Qd7ZAIjsihJ19QIQw1/9JLcjsGCzJFI
t/YBjWH4+PD+HOcxpGB/aT24P4yg6kPTuPH4pNIAF2QfHHmFYBzEKhD6mKhVgtkJ
Qc3PoygoM8rYd0x/biroGrQ/P+zKSbuTn1dT0CSFCryI+j4mKX0aykA1e/V83QY8
Y5/l9M2fbPNnZUNw6p4fzDd37QYjv0oPqZdfhNJUAwc+fcYaFLPCZlfGAKDEHrI8
nC96DlcFdyItuXqzZoxbAy8mSUJapJctozkqz4PEH223HJz5SW+5AUqmfELOqKgb
WmdgXDfvkK99Ks1SXG3ALnH5L6haT9xjd5rq8ouWAu+7tbtBH24qfIDehxyQVTmt
hx+oKI+syeosujby0+eBfDzEFj8k4pdpH4FBAv/JY1OXZ+3kYTjyWHkSyZGTrwtf
Y0f2yzsq+4fjQdb3FpFuSS13VAXNgoDNhtCp2EtoWFOoNFq3guE7U05Dkx5qff/7
l7nLdVnMAMMlvvXhnDV7thHaHIgGDgQvWszRXZP0gP2pe9m/Eabjyry0sWQ37N2Y
K2ApWRJQz+291y+DIVslM4HfElEYFRNxJApLHyweteWzHbENtZAwT+W05p7BScjS
PgHOspWHDxVIp7wqDQnElHXwvyQIldnfYktFymELEY1U09p/viy4/JN98S/q9UyV
smkYQg6GZpDhCHvrDPU5
=Sc/0
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '635c2f3c-590a-4a75-add7-096934cabbac',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/QaTQ0lP/pS2r4q5hMZV3mQim4wbgcyd0Tm/5VTe3LwEh
+e1pGz5pwyoP5WaQ94eWvLkb9vxbn0Xwn8jOtId9osxExIc8oi/bCS7VHPWTt32u
DiT8YFFY5kuQtGylYQ3NXDop+BhVkBfOHXZtu7oD/s0sHFgJbHAFLaoyVhDvlGtC
AkMchB428GWNwRZqX0NPFbcVuYrLAE8mqkp5p4ehr4yg7vk5jK8svjp+crOY+eLW
hhpoDP3xmY61RRYMj12CjGhnT6yJ9m7GkMBPdKRf1HlCRalfwxT6cpq0kMY1OM0j
Nn3zvIvwDkzhIsVEX/QyMtbtK+OSfpPA5egskEPyZdJBAaMmOEyz2nmcQgd1ZUNQ
QIXm0aB/fxJWlbIs/4KYerz5EdcHKd9U1Q0O7TMlbpzAP7uQzlDyeio+EZE7jrhu
x3o=
=1cbv
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6376bc04-87a8-4058-a12b-ff87fb61c676',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//dhGX1rXzF6ayLdEZISYOHB8niO4xpL/ikLByKEY2UdNn
ONoQEyXgq4/9JYit0AJgSDDWd58Tke+Y5K1tRYVXZSXKXvWUKWTnw2c2OYsjdDRm
7hHBce9PugaYxPKzVao35Bu+EB2UoDNSEDRnOsq9UMvQTOw0sEDCZMkOtJI9Od2A
DdIsDxUwRRwA5El3E0FVCVyoCWTFB5RmymE92sDDt+Nx8v7RaQ63UYX73AGwdtCu
tbn7s3xBAVDhZU6upThtMCCaULv/gessD3AyUknoeAyRd+0MKgv90VtNOgrYV1+h
X6FT/UMM/HKwq7R+DJejfJXN2JAtL48G3tAzrvuHu1WspNXJk6M2iaPqMJD3utYz
PEQ+2E6nQ3TrqzKRgasM/sy1ThzevMPE7Xg9Jwnfa3ro2i8mbYsYxQNpXlNEq8qy
G3oyNDYoQM2CSZPdkBKPiP+FwsOgIptsvtBVvZAqoV7xB9PW4cTCHhwblNP3QjJP
XeewCWHG45aAica5zHIBMrf4ySPixyngp6e4PQV0Z1BMRmDxyfslpOTTl2KquM/H
A7s920qBwQAiADybQFUISlHAFdUbcjbni0cZbj84PoU6pB9uG5UWpgco/O3r8TNS
AV7LFjlX713ssV4JRDULQpfxmDuGDapfBDaClXSAUVxJlnG1JO99mBj2egEradzS
QwHYA94HPxxyG0moVCo1Ju3e40gLxkqV01WOFk7ctfynoP+n+4c7z5UWNsgg/y/4
yK/2jT3CIcaM9ioaf+FnU/Ws4WY=
=sqat
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '677ecf65-0f2f-49f9-ae6b-d2d460a267de',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAurSeaTZDYUQNmZ0ay3EqLV+1obb+xD2YkkFe6UEg7ohQ
NjmyFgS7dI2OjEBEgLZnWHnTzFQi7/bsbwWaGZljsNBjMKCu4vqVECp7EtJ6h2aQ
0XI/rCG5OaVdu9l6kWpc2qklK8dEewHPgkmUfyCfxFsh0fWAcQSghagOQibaZKno
ctwqlGSMYssEdKPROFJGUUSRv7Rw0eBrDjgg2dptvdpbdbpPRZrofrTr6bT87vA9
mzcpmnlK5xdvgGWR+Fvu1V3T5kzpaX2xJjA3E1vKlhkX9RY5M+8ffP+NqQiZwUJp
mw7cqBOiNCuXkF7GxMMib4a1FXhlRbmt4DKyOSSi9uZEM992FIRt7p0/Bi7ArfeR
Ph2qMrJ+TYjdoXrPifhTQEgVcU1Dr8/R/ZrOtDo7QIZowPaGfAo5Ho+S+6hV+GQq
+ETYlfq6tURrmrKj89vvua8dxB1Z0R/S+U8MkLC2lwD0ksqlJ4PzMPM1Oa/Fl4VI
pjknIExmnjEVkKNaaJ4KFFPmPrAILAdOswb9Xlmub4YEZ6bEAkFTUNNXS2lZYEW4
OdJPv6w7azWxqi/E4E/2YFpJqlN3LK289iNzpG6eG8CPcigaPDZgqzws84EllO/V
H5Tzh0nw4Ua/53GHFn9Ej+mfwjBn3xPEf0btgAgNuS3NJAFk/X2avUOidyPo/mTS
RQGc3ajlooViPytqNFS+rnRFIY55OUVnreBq2Bqz3Yxbu6FdfsCIReSwIpk9ePK2
c1NNrbOvXtU0uvBZJvoNGV+5Oj1uQA==
=zvpE
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '686711fc-d074-4fab-a1fa-160ce7ceb4db',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//fXbcRXVI2kF1tspJ4sSmsc9HlOq11+nng+3vXM4VKXqU
rsktM/+XWJltwPCD7/l0SowaB0xAss4IgEcVySCSg7M8lLhvpeYImGrokQn6wQ8x
jNKCFYXtLKDeM94OcA20AxZhulSjkPpIeJ1WBtQEK+Q2qMqZL/jwZXz9+1xKBqqW
O5zu8xw+IhmXP5FfLWSuRIxrYOU4pa4nMFYG/ZrXE69jl+waQg+faxGJUAUzwW/n
BVVqjcfInU+DTZw96MJlPMveUXwx8dFC6zqVUvNT1s6O2i4FUmd6PmKAsE8j9t41
Gt8X+y+29HicJ7DhL/hOGyGrIFxs0R/T0ZPZ5ruzDgbX17ez7EhRHZI5f9vXuxzg
xlKra+f2GJj3+dNc88GTAw73uUihmx2BTK6w7rW/18YLS+03KaQczQBaTTa+tIPJ
HStPmw8neCilkYSJLRWaJ7Uy9MekUqOp9FSDZZ0CLStQFIrIEXTUBIeUff7f0c92
sw2qEYiQ/tlNsPRGKlKEwQC6wzGDlqwabQ2VkzzltndS8ssnpxbTRhOGBUs+tUNk
PaGxM/3mypk8Triu0Sy4TLb44sMEFMv8O/E84reO9IU92vqxP1fOsr73BwYSI9Cy
tSGg3l68LBSbMoy9clOrS+YFcgfaTpOuXyn2jWeiKEvjhovdxXm2g9yF3L+JfP7S
QQFE1w43pmfiSh9Et5ElYd+IuguPLEi0uEV/bd4GABuB8dpQYrBzkCf8ZkW6OwFO
IYQn3LGynoR7ZJJqBLnWciiS
=58nm
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6abe8704-86f3-4034-a4fe-8afb19c2ed2a',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//RSwEcl1XMILx8SFqv4iCEsKdAAcFXuyb7b41eY3H0qa0
F2BQwKDkEQ/ZFl52yiZ0rBbzkVKZwpjf61bBSN9+aAL0fKP3g1VvykCr4j3xrmKE
G/jtHx1cmPwoFbLCPyHWiOFql43SFcfWu/S61L9qVGukowoH427EyBk+4hfMg+Zc
USLKn1dkL2CSaKTUy9pbDAUPhvhgIntwtsbjCDLECI1r4FBcLPTFvsO8ac8moBwN
qUOL19ip+/JY/IkHinXD+ywkOEpVgKbxAvGprap9Ci3+EiEij/2/8FkFPne0WFzO
gS95D+g9/XhQ72+g2zIZo426R3Cn8QQI/HxwV9cQQnCC1hxsJ0mHUuQCYTEmQvys
SNr28rbX1uMCK6Kv3B9WoNH8cUSLlQf+tRHUg8p6fU9jTxzZ9t2EdZW/75W8OPUE
8ulfB6w3EEyvNoavtjmxplBvkZ+lieUKnpXVF4jjn8xY5i+YTlSX+5UjMqx/j4uV
lrBjD6lQuOGODIUYOeyGP8fes3gTUHLqyAH0Cy+1b8fU42jjGoZUsOuRkGDDOTLY
Ntg0Q7DM6ePr9GcKiMW+DPTULAOgXj06TJV0oP+QkKnqXAmKNm1s868M8fsEWiR2
TVmKmr0chrA3MGQWFmlalKgcBbqLEp75H8RKRgp7kncp7p0OSA2ZqDNmirjRX4LS
UgEei2ML8SqDgWAXs+efMWDuy+l4xgnfmVJC8nmC/HqqMQYGpts18jNwD6XG9XEa
y2RRnSIABWYfiKILs3AZQ5O9anT486klMesdy+K3C/WCx30=
=qTHc
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6ba0a7e4-edd6-407b-afd4-e6404d095ac7',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+O1gMgaosJYvhq+xTt6f/RY3st+Sy/82odTGiquqhI+Q3
oGOq+w4IY5znyLUew8cFEmqHzJo5X2HXF+Eg6o0tuLiVkCc8qz5ZiTWA7KamMsMj
i40H1Jdl5gmXAkLa7dpuT6x3MSvYiKlvSoaS0D5Vtz0i8ftFr8/UgfaApD1vujJj
kqwtM0LTQuyH/hwbDawaQgE69gBvxJMzleBFHK2NXSlPING6iCb2xzefdXqCmdBH
8C/aBwADSCauD7g26uBew9D4veMS96jPF/i2k+gnRrVW7slh1wFbhXhI5kQv9G2y
Ru/7U9LMPJ18ar3WPnPgKxwfU66CEm1U68+owqJyLLZ4mcS3Qpvz/sXDwnEYs9ul
wOPlwbWq7bIdh747d3CZUfqQnD2HVtV1ETc00rhGY22CULV6dyoreOcSTN/S1e0y
6rISe9fMJ1hpPruXA8J2vgF1TBmV9bQumUgW8Gw6fhtySM3KyxFVh8bv8eXvaPu0
KOQJ9UwpLnwOng3DLvT1nRhZx7/oAw6u11iZ47U6XbhSHnlJELtpa8QmPDYMokjG
Wljn/TQ+ZotzWYHJQt4VSZDuwCNECN9WHEghOvc2U+Tt+Vmp+JbzEIXLNWgBIG24
EI4d1ZE9/FKgYnjgA6PMjm2ROS1zKVh+YE/988RdAKX8nFZx0f6P8MnyUfconZTS
QAHMahTfJIpVxI7xu9q7APsujocITlg2jVnHw3J9rcS54ASw2PJlcoaTxttmWvsF
4rLxEvgMgF8RZJ851B2qp/M=
=4jPe
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6deeb60c-52dc-407f-a08c-529d86920bd7',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//SYxY9E8dRY7wkQmfg8dVi9ySwSEse6GNoqYhcZbcSQJh
XWJl824alh2Qksv8R71VYidbGK5P4WIZK3Pu/mwxlUr01Q+IMUwqaEx83/wWeKNM
QVe0tFsmxazCmaAE7DNsobbhWEQlqk2OXYNtowWz5euK4kAe0e16+zjG67JPWYBx
99f5c9gpjrQXbd7nowpLuoD4uP5M2G+/2yIz+2QyNKX62E7SPJj6NniOCrVH9Jcr
/jFnMPlfmuvRawecnwBqZXNMl5btdau7OexfELzPkE4yCndSRA/xSwSjKlWgFq+w
CR/vM1zomuomxrwJYVdJW3+xKebM0iKskbeJz84sl0oNLxRDERcpvnAToOaSJ3jt
NSF4IEn3/0TQclbvPhslZAIEbuPh6LhnUmH8itAwtO3EYDdC9ZeQPLHrMTUdnpgk
5o+Wox0xyh3ZqE9/XbmneqOXcsl5+B+FLACEHfzMg8tYstCrwLyNgQ0bTTU3cafE
yh0ua6a8pNt+KCSoK1YjtZf3Q+cujLeaxoId2xj5OBtfVzaZIVvXx+25bQtoQf9r
9pXytNDk1e2VWwdfbTG5qsq+WitGZWVPJm35sTdPT1Ih7U6WZkrdxLdhjYMooqM5
28BriMd88VZH5Xr105NFFhHNLmIPNRjq8m455xEtoBV6zuVYF5zpC1k9aYp7XDjS
QwHgOrOktuIrmZHoEXBoFjebzoIOEM/hiclMYZ/wuGvlSbP7hKsdGnayYjltCjbX
drhrwsvSBlV7boonVd45fPzJDMA=
=28OX
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6f4e8ff2-60bd-429a-a814-4f4b7bc20112',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//eOMeRw1zjwIPe1BQ5OwtEDZsoSS0ioXOBDkB/1WlToX9
nml34vtMuiKgRN/F9yRG8xRZQJ9V91Ezf+KCyySksCX7Dkr/IMGjYlSHsVvWpEtT
+AS9uwEd1+16iVkGBZWaod31gjtYDkttGG5kPIUUP+rE1aCuHtIoLSAqV+enPoEW
Q/wDUFXEKY8DWvZEpu3cxWUqEQTixs7kv/f5EgKdROSDV5jYHDLuE+vo+4DfuASf
qfpO+Ap50HQbxUd6AOBPxezMNpXpT8NjZ33qeszIxMWJjx/OHNmtOVLUdF4ui27q
v25BkX7s6MAf2uLQXBPz7QyrcnmEnMzCdOpvCQELB1Y7C8I3zBipdaMrBIh1ZJf/
KDBN+pceHlOVH9sjFQFjvh4tJ+ojwX+xXNKAxgbjoSh7ZihHx1XXca01l0TJVPwC
f9i/BTNY7R5oI+HRYQvW5BW3Ltw2A2tUsIiC9VH7585QezpnFh93mEXRSsDbObPA
NcnbzfNrEWaTT/ncQM681qa3DMoBYBoo42CzCgjzt+FCRxvL6xf2+m1HKXTZEicP
QqkPv1LGqVaZ8Vuf5zZnXolDGzmMxaL7/cYnYkEESSSog9stIMOrnT5uj0U1Ycdl
WTHJ40A/LttcEXW0qQfA6rggkWUrX/jqdIedIFED73TE7EO3kmqKJkTpZ/EHbKbS
QQG5UyGVZjbvv//RKWqOSqNnO7WniFJ5rH8LwmmHL6S1nmWnAwZAgm47rsgNEmdF
Hhgji4aVesbyefUuQ/voHxU0
=R/jp
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6fed0f1e-d2ca-4623-a417-6e8cd8694584',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//UkARk6lYhLnuHbRLnEaJvQBCT6g35TsnHIZs/UOyF3kc
K8IdPTcFxTKw/podYPn9+0s3ciLE3SpmYbUZKPXqiXpUCPJribH7+En8Dj+QroDc
Hl77/dOAGZVn8T4lyalM9hY7z663+PsDPDt8U4kO6m1Y3X7FT2mvGN8HNkFWmOhs
Jd29r7Lw6ynwfPw3mrxL5eKcEIJylSu9GDoLJZ3ZLoqOQCEBOXZEPvOtxnitTp5f
8e0N0gg3z0XckIbZmt/tdQOtwN56nbLFWzDnL1jmL4CkUILbtS4ILtRRuVMmwFOn
DYzekBiEvNUZEFTFqH42EMyq2dYOdel9bWrZ/LKyZVBWSMbjAIHcl3f8z6Qk+cIL
6fI2Nghs2U4JEsWeKP0nCdgP5GP9U9xx00gJBzoo0oIUd/FE+/lYS9r1oiuvNV8d
tR4tKEc7A8hOpRSEvBJ8LD74BOlTSZncIaTjhqsDXhs9qluubEnj/50Um2x6/hmD
F/6A23clkM92WtW7SOSTUBt7vfPmzUWVZNz5YEHlqN1FYnm8XVcRbD9Bo41MH3UH
BjWiW79+4ECdLPGr/kVDzoMe+aHiXnRLvfXzcZ4Y+6Ia57Zy+1it/lyeTrPTApje
OUOuX7WINJrWVxFLGSNjUtBwq5aOwy6N/uaNin1nwBmZvCo+HZ/5s/WvfJY6z3nS
QQGZCMP87Rqj3WLxS153E+q3CYXsVb1J8RyUf1wAQlzS+bMQTVb09+lYaMcTRGqH
ZRr8ObgSInvEhoGY2rvsQ7tx
=vvaZ
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6ffb705c-5382-4d92-ae8a-808019f718b6',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/6AjSGxNfECIdj77lT6XN+C3V4Lq/p+ZpicZzAjJ2HOVJn
Hq5tNuxpmpOSsMUsHwD/5ddOtYGfnmwVBay33EoH2AcZbI6Un+2RvZPPjRZfxTz5
UaHVECXhi9KwT8jABbMl9BtCuqtQ/m0jHsn0yuKOxjQru7Vb5Blt8wYgIJH1mpp4
93Xa/hLdKpY6xCuSZ6ILP1yP1PpV7p3FHsvARujEB6y0XXxxy1uh2EEU++oXvhZ4
PbDuAPKATx0Ho+Uk0LrYQrUG/fS+v+pz/sWkbayyUxXq+w/t0prG/nMZvmC+ftqt
BEGUUPE8R1SXCmw11eW/vl1husQ4g//wDNlozP+q2UXgzYeQbO0pBAVpRgsIgd5k
DlvGpgXhS8dyeYGJKylOIpF7kwqp3tltjNA2dMkCLyR7cdbhwh46ourrdbZB4Xt5
mytBBdT2NV8UnMf9Jmv/vIxZiBCu00WfJZh1X8/eM2Xh8ToIJ+sRfZ5pe34LhW/y
MJr/L0us1vPIeQfki0FZ11kCVLlZHk0NkcI6KBAXdpfyOM3brdq30pk88b0/BbR6
NfyJnkWoicaVbJFnvcjqAAUGb+JSm+SHWuAGO+el2g9MEDW7i8mxicmLqGQlw2Om
9h8lCA3hkI6woCYfr3y9yG6tHu77UeIq4l6O5SZxYUJVrMfp8qu4li1Q2McXbHvS
QQEuPCQK/9qrTXKtUdxswajDagZ/Scm4t3lWxCbMluJ2LJFC+3PVpTn1amJDXrdk
IJVT3LlzY5C9WZ8xn4e67mQo
=YYZL
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '70660977-2cd8-4ae3-a33f-77688c37790c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//Wl7nAUTnK0hzWhNdcRPUz/qgCKFvKJ31EX6/Dae1IVuV
X3UJmFCIyDcyb6eareaKNLCbZo8gqsjnnbskConUIyJXx31eQeMoqoPNwy4Fwnnb
Uwal7j5uMO/bqwA6JuxecMbBp+07MDrblem7a96blvOumfKQsgzTGyMjHv4tBLnZ
w3xaH0cC5Wy+aj1LCGnMLnIyMnt9Ktao5Xt+2IhUE/ddtigJ0p987GEi/JX18+VI
ZmpPHwHtxML04xDzvzcqoSjjomtlWn7886lKQcQZApnokJtg6k7lEOP0iYuhYFSu
N4G4l/yQoXrB00Ah4SFEaoj9i2Ytq+v7gDQOImRYbD/71bTsDW0wRTukB+rwzQVG
rhyIoA2I0AYDIdZcjnlemphHk2zgoq6ifOYQ/steyDl4ypweCuJwtzxPpBUvZyn7
0hy6UxfWWlBhS6ELQAPaWmo0MNfqmWZc8UGa2nL+rF+o5bQCBwO98OGVwDLB8ACe
Trgmq3BdHcu6aAHe9RHfdI0UMTZbqjGcZE8zVcHIFu9clKUZSLsU30vg3hpDMEah
oqycpj76HJMMOz+OfXevbzfBwrm5KiP3VXQjtegqzn53lOqeVxJBtAFACgoX47zb
HSXefr2nKxh69+gdkkyvz2FXP4f07K0xnIjdnIsSU4IIAXxxPkYdPkC712UiVwDS
QwG5SeRwkbz9yjIpR0ZsJijOEsR84MI1/oE8cqu3cd6eUgjp2o0f5ztLGLWp54J7
XsZMfbRVNfob2ulWWDJyY8RWwzM=
=qU1A
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '70eef9e8-20bc-453b-ab50-c1a063fcdf3c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//WChUC0SybYSVzKGb7mwBo4hJPVmqYM0lo7nycfw1c4jd
VXAFZr3oB7CDLYHVYMvtDGDBKfcjiWi32rp5iUQFRBHbaYYfC9BbwNqV8B1wWO45
G1H0cJ8hIswZtELtta/qJyfmwcRJ1PfqsHy8wCX+6A0RYKm8KHJ7jJ7OagR+Fha0
e87dh83C7hVn8wIWO+hjYztz2mBluHIYppk9kYo4xa5KbkIY55/PTodyDvKsvn8j
+iUpQq9Fm8jhWsCJoGT4a9NBWXHkYz35utSsUx3LUzeNPrKE2kUxx7jQrea8sTTf
2Z0+K8EEDqI/aWZeO/ABDnQkMJj/Nqe26hzZB7wg+hoJGfkQXnUTPjlwuiE9Pc/O
qrTQhKwboUlFg/KU2qXzRGaQorR3g6ZQezdvAWk3+XRXGh7rlpWrgolLZ9AiqQBL
3OyZml0ejxU8KASVdA4DhCtNme028+GampQN8m63TRBZiJS42uhLHd+MVyWO0dBo
7eOWvX1WLmKV/vmbp5xPesVlXY3fRwilfOJRzMfLSYjzXjCCt7OcTBSjpcSWLETS
PK/D0k+bA7MuifM8vtja0754lElVQ8HXA+w0h59Ri9MuiS9lFJyIqRXnuFNX42PK
inHl6xquYNCJwVxRtB2YVmUg1rzDkGyuyBsnkD6TwIXbDzxb4aqOQ5pM3Yk2S4vS
QQGUs0XqhsS5B9xycFCvdfzPOh+Slzt08beunrgrkrhfyKgCRmZDeEqduNWj+7J4
ggr/bKCJMI1b/h06+jzRsqDA
=Jubo
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '73bbde30-fff2-4932-a53e-4a9eaf656b4b',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+KO3SvQ1p9FH43xwsWf/UB8dWp2JP18UNH4FOSPhOV9xw
w1OMnF9a2vB/hN09BDLizZzYyyaYJ+Ah/L92n7pZLV58Bk0703sWN2yDXbe+/gv5
1mMdSyGOyFiwp+4zAhDlMd4aI5eE6rtbwKHrCMxZhPiJsGbpiELiw2UK/ILmpBQU
zsi6JfQWOFkv1xh/d7Y0YZ0dglp4td0Rco+HrOemPDlJnpLT76CyUBwnb0yQmryG
dxlmBtM0Lpm/o/x/UE259HLji09peAEONlKSr3tsqHbhyIRbUQIXBFNk8HOAWLXU
alhN3Uk2fffgN1nUwb91/97dF/55OmKrMDpI9V9lSmN4Y2umxmOqO2i35DzVsesx
VkuQ9NVTEww3L2vKWHBzZdR36z9sX0/IhuAMx4bvMWv8uWCJLgwxUAakoGD1g1uF
INIqa9RzObyUaw4QjUPfrBGEw2s3m5VY6n5Bmnzr7u10wpp7vQ2cbe0aC7JRk3nH
HLPW1s10RtN8R/hkgrK0GqDzJbC7hxRqeEgJyvRW0KlTTl/sizrkUZVeJVhNAgLj
4cQBWYyWp2Waor7VRNO+DUbgyUOO/QfjhgV8WCCCkXtuC41PC0unr5ZvcMEj7+6N
ord90lfqhOMG1HEKUjUBaXFOFxxlhx5SboQtQz7Uedala0TXtd6RkeZH7/7/7FnS
QQG4yp8QgJjMKZiIgK0IIHrCWr57rtQapixdsiJ+FBwqT5s6eaAcIZKgCzN/aaND
BZp+e7MC3DUVMBu+7UWDWYH8
=n1oU
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '75be5284-74f7-443c-a4c6-0c572ac4b60a',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAkLy5EPEH0uCw/yvKM00jDm6m4mduZB261HaEVlGL51Cr
AjodTyvolV/QTD0qLcv8pVBagLEOVPx2NfGm4nkfeoLbEv9dPyisrt95v3yYEt+m
PB/Ms0vwEOasP+wUzbHi0yij0GTXGGc6NES0K1Uh9Z8fxqSDMcfWoCvuFh3blQ46
rAcmpmXev4nxOgaLn/SninZKDdkXzti9XH4HF5ZdPGhw/RV5PU82xmA+UF0Q2Cp3
a+2aVlKpmkTo3pbdK2rM0ejl5ypvsPJLWM7SEhUU0w1AdM2/agK7NtunoM+ACpeD
X2Kngr+R/AioDgsofJ6X+hambCVDUG/exbJ3iQNf7NF3u3f0yz2zDxZW9ZHs7qRe
H80KKkXI/z9Tu6wC5dZFBgSq4MWfbnkoSBVvQ4fftcIUwX0hN4SwrPFCYO0Ce1KY
zuzI7QyXNDbGCxmDM3ApDmgLb1Y1RUoh/LrigRqyyUlFS+oD0Uu1PtSVgU5XluVW
DwVSOiLOSgGcApPWuWdEkMRIxIxzDU+StldgkktY2HO9cjwJ0/VFerqU89aNWI1t
lxYwdl0mXOvWtL3EEnBsjZjdAIjPoWqq7+Uv0Z9fUugC3I9sjW2DU9dcSj0KQb1Y
qb90wkNjW05mqVxd0D25arkKzYUOAP0xkGEX5cESOQx/WBs+mV3Jt7Sqap4zJo3S
QwEAObMpsZjW8WCodgm38v93j+h0msyquHEotaFgb7UUo/IJrR5zSvIOjjOgWv5O
1fhMs/h8JDZGsAjDC6u8Jz9A9Gg=
=lj9O
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '76d97a86-f93e-415d-a9f7-b713a9ffe9f2',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+Mv0c/HFPzf8UrX/Sr1anDgCL0vIrm5L/TE6EPGubVmQZ
nrepWf0jqZbj3F7q6cFT9eQ7uKU63aZJWXdaW+c8qcUA742tgSDLrNajoLRrAUsG
T317Z+0oc1RvL8ii25+CfASWiy3DzDpA5R9HdBmkX1myDLJQQbB5yu23aT6lg+k2
4n7olOk7bAwsYd4PCHIR5wYiHc7rO+4pf1VpvOvpAaIb/cgbhgzDxeUezb6l+d8o
8vlz1ze5L1/33vXQDqEH3P2KTbIVYdu/P1WbAFexWJ/pP8eblc1icXVgslZCc7vE
QlfTeWxjGg7/GwHhXDPQOQ0ovMfAsfQ+/X7wZQFRG/pwe2DEOOtZ6NuB4kH3W4ec
bgYWWqBa0rEgrwVfvfP2uCZ620S9pt0OQngmk3q+EBP8qhnewj4M/NstNEdjfqi4
VjXHqshitp8+pE8htP1//WPEI1Y/8xNjnfwyxJdVGe4tIOS7BGI5CbiS5rCFyC82
75vkmHgmGeTV9f4nxVkBUaOBJoQvGAeM+J9DTGAGfRfEy1uH4MbFPBbv+MwHaLXp
uKoO35jO7IS06QlD4y5MoQwGawwveD3Y0mqeTZcLteR96/CFzsbVChNY3Rr9Jfsv
4Gx3Ny1OgI83JbK47JttOVwgSR8fpI9UvUV5Fanjh3wiqwqZmJyoDJrpirCUWkvS
QQE7mXbZ5bOLf9olyzkTaddwdDY0moguweHML9Ik/DWjGpqPc0OUxF2boWMNY/74
osF5Z3VxXxyLmizsuyoOXt4X
=QvkE
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '790bbe2e-8ca0-4563-abf9-03a7fcb4e0fd',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9EVkoRCeHCaN+zghOMjBnuF3HM1VLGVHwVyGuFRU1Zqox
LLnQvBjy0eHfyDKhbBLCMqbZDKHbQVTYh9SglGHT07zCp4tbhtGvL0XwQVRXyAiy
CB1NDxKoTs2cVMREmKQLjvVoS9S8q+Hj/RuQZkt/2EGN8NV9bqSlBvbof+ySpW2l
d+5CGpDhBC1q7IT3EseJivSweKn75GOj9vFJUsGZJvFzbtUvprSF+pGo6sHjXgwH
j5lJpRYv1kx1DdQkMuPSQIhrFc3AM1wyrK/9nIOOliWJQHh3lWALLtTqCidIYd+9
CyI/fKdUNq/tIblZ8ybrt3/JAPAyCG3+Adv+n6+0QxuGYfDxyNaIzlUwhaZKbOWC
AFnS00Iy2ZKVT+W/y75WKY20ZCz4Hd39yiKYpkZXPGNM+Izz0Ka004UPSSt9nYhb
A4AH8uxMe7QY9SLA3boubwPF5QQ8V+Ll/RQK9P35uA2gGwvM+fMlVEmQdKQMHl7H
l6F9pG6+eQFaFj+b/XV1irNEUUsD2lC2zBneMkFYagMqFmxrOOkKSRFdtZG45mo/
I2ax5yXMuWwHHLSwARb3oBhhI4NvoDejO4WjAa2sAZ8J4BU+fFJizBCSQ594ra8b
Hv9XOSZtM07XE3pX6LWncoNOKW7rqvbrQ9dBFDFbhiCCSCMXz6BqN7/Th1RVc2LS
QQEE7oqNNfeddwYamtn1kk3TSN+mGfYr1wnjeukFg5hQhOlvCj6bNIHFhuirW4TD
eGUitbacF1rpGG0X141/w2Gh
=PIXU
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7989c4f5-ddc4-42b5-a8ba-381752f74067',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwToBMvDykhbecYyHvF7bcNQpmGS2MVXkgpjfrda53MoR
XySdFpdZrx2+0qvjMkWtw7Mxu/WKsfA+XvADdMfOHEBcclIsNEz7RTH/QetP7B6c
Uu5Xt2Qy4CcyttfPYy7oI4C951MW9kQ5+K6du2eY5tIniLSYfGOQ2hcJqBddsc1p
bcJKr9dy0+O+SRaBNjnufSXpIwtITB5YCEZoANehueQD029+48JjQkjuPNLddnqe
1x+LKuyQe8qYBqmkScNoq61pIdQtKa/yMIIpv6k33byiNwDN91UVUCg5sQ5Z25RD
h5y5IGrf5l4QjcqgXlGk2rp6iSy7z9iYnPkANeeMEZVnT1Dgb8zSj39dVrZ5YXQf
aVJGJn6143SgR/fxwtCwadhr/60swK9ilH1L5Mds2ZXTmwuxfW1VKUyeEkYt0nCd
LGxRkIr9BUnrH91FnC3vA8rOLQyDs/9/vlGdTu24HpYmQvXnQsCKmIZ2ZFJEpofH
mSHNk+WgPA96J6ftufO7K7KUx4C2RnPC1gX5aTgBH4YEFQL507mzcHb5sJECD0DQ
NEVrAKU3t7TQShvqxRICmL+O98RnjnMHwNqvpl2LHXQsoh2yUXltUeHNXrLfXY4W
pu1rExuEbzVGhbg2hEtpNS+XsQo/NdJEfgCG3XjIvm4Bxd8c0SuM0yaAzvWC5anS
QQHD873FgLQWLd5f6xzZtuFicdI2L4nKaL7Se+/jQw2WiGsl2SR9EMhBPugfhKjZ
WKC6o1Du3+V1ejPYlWLZ4M0m
=NEjR
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7d083e8c-db93-4fdc-a2e3-48461afeab5a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9EYRUgCjgQNte8+sIYmrVEkIkYy23jO1OsxrBp9SFRxuJ
UIkxnDV6xqbSzo7+lRtVciWPINmLFvJEBLXt/XbeJQp92tW2PG3zcmMwYxHitKYg
h8UQdINDQeB1zLEmf/BZn3hwm0CqiHUBsatNAAtGysFXaOjSKtagY6D5swKWRjJ9
fZSvfu7hzb4+GkOfWDXgMIR+T4JXYwYyjc3MV+bVGHMTIn3xJhyiDFqGuGEsiI5E
ZaVcMWFysJmEFOeb8VB4fqErZ8/P4T7BPs65hzVf3tmoEjSnfU00jXZTr1C7jHGb
uM91f5DEISRCIJGZiUxYmt+plG9ifdJuGC3glc+w9ngeMDuO2mqyoYjZ2aRVDF8N
5q7pn3WLWb4HAnJVDX3MTSWv2tZqlnlhDsJvdK2EcitMTrDAc8rRfvxwK2tmGHvx
31yvCa6n4lgJl+Hh/anSMSfLwosKMoWR4/9TSWuiHxN8KYb/5Xok/MbWoa9jdgfB
idhf4dh6pi9M2rM8PzHUcAfSPQcnycj/NA47fxUfv2lFcbjKLECh90BXuh/H1jwI
uP+IYsoVqcbgdwLguXh+cL26FTNcCFDfKmBBfuYbagyY4m18R4NrtNzsKAOq3xLP
pWKDcyJh3tm1fM4ZWdAwThMEtpcxz2QSC0HCFSHt+oRGumTegzwv/e+5aSLqtCbS
QAGJXLTtiOpTypeCf4jH/1E3OswWGJn8Ei3CuACNOXN8LGT5MVgyvdaDjxUnYfBF
Ad3+miKtWJxDzQUczugziHs=
=9z6H
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7e0eb113-5f4e-48e0-a98d-fd1563db0f42',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9FEbhA4uIfpwI7nSZ8ooP36hj6gIQfxJl/0rHrA4O+W3G
cXeqtulIrzxqYmyStviGJ1onSR5TYpL9PdNF57DlpH3Z+ZrO5zwt7DaWh0Cq2n17
lwzxRrb3seIYJH+X/4NcNDib5weEdEdZSg3g22Z4ZEBzleBeUrgcJUb2utDZxUBM
ZMQ6HMt1wItq6KXFPtRGeZv9MO50O4zc57b4CrPLvqMxo6dhgzINFDPeMcEhL5DH
acZJ1FW8fQma+ehOgvKD4sxpRtzRvII1hzBei62n8K+kdb6ntJwQh4JxDglsqafJ
euOkHi5xNUUE1Qegw1PPDsPX1o2S3yzv2/DHFZjefDtFVlj10uQJOjH3+rjQAxl/
ytgHMVKsbuIyThynfO3sNPvZdGWFr4VbDLJieQZzkHeAd1K+hbgr/z3JI0JBw2GV
V9VJNXD9R8rRaNC8ytarYp+/VqDnXiggYJnS/+90SRgBWnMfbIt0iXj2cFLQOuwi
0F6jwR57D1BfX1qYDX1vvWKPO4a6KTgb1ISvXb13qetPRIji2CsN6DLG303cdsj6
w+/X0gcgSvm96e5sodlWs9o4ev1ZtsHRjV9Y8NenI5ZyQ5sbyFlQOn4RgV+drvCL
4hJUZkwUtrbWU7euK9Kf5vcZwrhvIox8JPyutATc8q5Lp6FrPUlNQza5X4Gft4jS
QAGNHX0SNbHIsfrUsO2ZH2TJTxSzO/ri6FmL0lPld9ViBrNDjYl1KupGP4EMXh23
888099cQZi66bOmosk7zkSg=
=yzyw
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '80037c5c-7e15-4ae1-afab-a3f444775805',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+Ismf4WJCOs85vOKgr9h76O2wKOfFqDzUqT9thzWu6aj+
8Mahgww3Eopu7AN0R/gQMK26vsR/lN6PSY8X6h/MFFzhWJiOyM1fVYt0fdEp1/rS
BLy+YcvsJVMk2efSBk28oLshMAsz3W4q2mNC9hqeFbqxHTBb/FZ2O4K9wcvMbv4/
Dfj/1sjheEbPZONzFG7LB0Ol5j+Qr+dkqBcLdptWM1J5JqU7iAloY0X7ZfFTk1Iy
Z0aueB8k2OosFmR3257rSKVrdRMaj1mZz4Dvemy3B2/J+Fw6sz+UmCXiuJJS62GT
D9fJAHh7ysVJ8R+BM0drOepb73aLEmpOzT3dyewOZ9VQG7A4e3wIYcMg1/Nh2ilo
l9PgN33WfTpA5ogpp6G1Pg6ksAJ3ADZNWVDJluUedWfTDqvQp7ERc9Z63xEC4TVN
AhBk6F/Tbpf18kitlpCspihUPkUunpJ5BoA4/O9uGB9XTsVYjXvdZS09UyJhinKJ
AgEzbayvEwhuMg6hZQDE245rNKpzgiDoJFhOrV7Q7ZBYk098uUT2x4pzbynDAT8y
nwB3EIvF447VSXWA68bFbDtnbh5g+qgEmhdIAP9XsHy3CWGCx1+pVN3C+O2TQfaQ
F3PNbZH5cPoFlorzcwnS07c/NEwiPEscKPIxWjjGcibrM+DJ/YmOgSgOFJkiVSvS
TQGpQEkUUtU1QWYOsQ+x73dxLbN65FsQusL/U3LBeeaQOrwRxHLv6evWnzKHyBSv
xV5HLPwatXiTQW/eWu+rtSgsA/xZN3088w4BAom6
=DiFP
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '81179186-998d-4e8b-a259-596e843d12e0',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/8DGMXJ1Qu3JBGDTVhL1yI5iYr5ThWCL2gbpG/SsL6bXO6
zTurE5Ddpy1AYU2NrwGWRdDvclbVZmIiUFN7u9+BW1sDweOP0hIPIJsqAi+NGLlI
W+AIxhIvnPVKtQVxv/Gr+Ez1nucvdOJeEScmwRv1xuTR5aMPlT+TNbrcxaiLdY+P
g4wHlkKn8nDucOkunt47SvGvnffVuRRAuvmiBx4MEGu60Rf3Hy8hmO3EnQkjhIDz
VCq8q2PlHIQ7jX/NNy1GiRPmCDeEoGCRV4W6VehfWkZHRFMcM1rlVrn3Eqcz5S1n
OMMe5z4TefnyPsqv/MXxzDorim0U4hw4N44YNg8tPhIb1ZBV6wGcNJoN22ktAjzU
PjEPf2Gm7JFdj1ydNeiIYsvn3I6Fvoh5fDOvoCPorJlw4P/mdhKQYWUHeG3drVF7
Si1zohgM8nv1RxFbl17M5wyzv3crPIp32dTFwhMDRCYJSsyyk3qVh/rwqGDpfQ7N
0khGRR5RtAtHJtYdQ+A2x+h0eireNKeBhHr4m28bxsA7e6EQG1u9lUzM2tQM1ax4
A9YAGH2nCPhWVpjLr17j8qOv4jzlkYFhKJwBO1c+VU3sDPTyZE1HrM/WRwwM01GV
tdg6E7KhalYDNycE3dUH1cjDH0zpfcazD7ozy2yXDFTA6RexKaw0iYyDbKKo3ZXS
QQELyLKMMn7MENCohg+0pkuELLkAyYM+WuHTrxc/MWsaHKJuslI/jzM49Hts+dMV
KU8TWBqTUeRCErrkF5IxN0Zk
=oWnp
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '83a413d4-49ad-4b41-a21a-dda1e3f5d17d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+OUemLNAxVsxidueTXqna8tB/Ge65f1eZ38v9j3cK7zXY
MwjJX6DH0xUas53nA3FmO0SbX6OFenq9k1gw0x/HyJKKrGtEDbAduGKGzC5f8nlU
IvsvyfBXsni+HkPWIBkToZP88OfGcQMKeASR3+Yelqq5y+dr7c7fmN5zClyZp6yJ
B6CJ1ZXlnDsHz8nahMGcDe7L1CGDtXqO0LF/jslkH0VShs2PeWEAXScCcpEkf/Am
341bZ8+Kp0K67Ea4VkmuyBq3Olcw514a6en6dYFPfyTagXLqhLtBvlLJ46SqHX7S
553MHrfMR2xXU4Kw+zoYIJTz/3Dt3q78dn9TtdrtHhy7anuaSUtTsPn/GdX6RW3L
XJws9mr3dwnWqqOG56BS0X+G8cOzFibAQpIVLm+/ssdFf6zmsqU/1VYQGp5v+bY8
3JPjJzKPPB2FczjuW2mp1qojADnpQ/tfzvNsgClh7ou16PcdUQ+7twnMYfVrGI4P
GUkOa9ZjXT5leqkfWTzIi+h2P7M0+kFxtCddcMcurOnG6CyAOsVoUd8MPE2FM+2o
3YUJ0nXMntrEj3kozHhCa2nnFlkVrsprb0uGYffmWdpXxSKFBTc2VYPwWDROTIQc
OsShzEW9oMHiDppPDB+c/Pc/ATUgZPHk6bVdqdOkEfbq/R5ZM+Aleh+r2jfcof7S
QwEcUiXQp8nvD1yf9rMcMGrjRZsdLzhtGtsL0TXjjaQleOAxp+lW6ULH4m9Q3oPN
swP1jCE2tOVvCnB2gjln2IhVTlI=
=DO7q
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '840accb8-6386-405e-a29c-48d0de1c83a0',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Z/oqZp+iUMGST1zD7as4n5t9BZdEzF3hsZR5EwCCDoqE
SYTtCm9CPeghRvs/+QgV/zGOQbKMcZOT8Pn/EC1f+YLk9+G+hLI0pjEetm3V6bSE
lcXRsq+1s/QeAQ0P/sn/V4lNol8yus52H9qEmZsty02Wlo73yG18Q20cSNTu2OUj
ASeQkgqWk/m3/Oth9zjsO7uqsOh+5XWNaWg024dgp31vCMDFkU+o2SIwKNRYFkDY
A+5JUdSWirlIUVaQhR7CO9gtau/nhCh3AX1FNldWob7ueyYA33s1V7DRMti1LBA4
l9wzLLaxjlXowAcKvT1Ij0aFY3i2KFhn8SzmvJF655n8O2Xn9LHAU+iyb7tT0Jes
WqQEmehng9rMzL7t8ira202SmUSWi3S1P1+iqVSkUUXMClBZ43WY7P5NHINRh3UZ
1EttnjItdPnla2lpoi7MPphx3y5wZEDYe/1PfEp7H3w6XwwMU/dI2A9PDdcuwFI9
0DfC9oe4o/8FzqU58GqgIN/uUZmUtZ/qP0Os7gYKAHl4y62fDnZxH6IBrUq4s+cP
qtOB4GLlM2MdWx8cTRKfbUVDBkplnxPGLdb1Fe6J/Bclw78w8j+qumh+gMLuMUwU
H3zPu3agFyUSZwSPjXg8f8MCvTj1DRBctc+knXGc6C0gOUbb/wKWJ7cWxLkgrcDS
UgGEeQAkYma3rIWPJpswAlL7CappIDHPwmQQBIRraObZGFsaMpNl1l+fZbYqKG4M
52URvFduPfgY8E7+1SGaRDpZOgE06RXg8jtUECZUh9JYnac=
=N6Jk
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '84901841-2ea5-4837-a729-b33b888c3c37',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//VyiUyGMpikcudYq9TL+bE1eTjzpuQwAvhIUbNzB609fy
8mFjknYONWAHOno6+dMlGULPoV3s8y9dmxUxvv4urPzWCGhxcmdMDNmfepcA39z0
q84AcXbBMR3dhu/L6ONusyD3bW6LMvjjz4gGwJC0kIUCq+ozb6eRe7kgTyyHW3ov
5stBenP6luDiK9uY8hErnqtcKVonhRczgpqnypPurM1GCKSnEx2+FLVYvClt3mWl
uklbc2cz/oalq2VQW4M4UBW5ygjkZuw1GQW4dGhRvZIGp7DXmFcwZRuyx9WkkkDh
jGO5EfrieTb3PMXLxhWTz1YBagxn2iK3dxWYESyRXpiqsRtTKXOH9h6R8TqScIDD
yt+DTOGtjNrRptXLMPcv5YeQZjae2gsw2VioGIEYaVESUXNxGmbOHJ5lIxb2GN4K
D49gOCoiiGJnKTvetpJSoerHJxcKR/QZJurVrGrmkCc1PHiNZxT1UGnW5QnOmzRo
EWtjznCEUmpMmjWWYsE1FCiV9pfezg1IEMDjtlYt4iWCrJXYNBnxWFiHLERyp9MG
2db4ZJS3E8wzjyI0Y//DuWDF4CZ6jo+biTI3oi/8CESmxoM3yzH1OP7QL2qRupKU
vk7A6tBcsChyfzC7Uh0g5xvpA0ZduS4h4Sr/fZsk7GXYosm+PHb97++kTqq336zS
RAGKWlgbVajeChmS0Urkyaamm5oX2bWKFv2lCfAIAC3wsIf3g3C2xUjRKKUCJRWv
Zf+CBFciVhNcP0sYKgEuKYMWxtQe
=Niyn
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '850b519e-e414-4c95-abba-f2820c32745a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//eLcAnMPEKcy3uHv+wvVMZ7RnfZvWupJruaC4GqHYwzot
GacEiRAipzDADtgwtE2g1+zZam1GqimFv2jIQQl1Sa3FLFhiDtv38YIfrV5dUDXk
817bRIa6GgZYAQvayhbsMp4/2YQIwuDFqVddfYur663I9Fc5mFlUfxd3/qcWkT8V
iPcIFwd/dejnXptcScQPi7BI5OpZ6Kd0pZVBBURSEdQ2KuQ4+MTy9kjXZrQopHxC
+oQ+97wWV6RrWoicDPnuwQdzV6q7MpxZLBv8L26q9dLwWHYKAh6wT6Ndi0kVTtak
UlF3csF4SCIRAitLS0LyRYLKMV4BUH6aDBhr6/3kBM+lN60tqBnEPpBK7Jkg9S95
u/itXhbhbyP/Vh+ojTdN7hpV49l0qEeelbMlQq2zeYw3bIRX3gQDb6eOc5D39CdW
YhnWiheiHEoOqFoFRl4ztzlxqn7nYI9H1ksLSl30LtJyIuXQOKkRqDtnxIJuLgWF
Pu4Ty/FtApntWJXGEbEPMLWvThoae3JB7CECmqysRvDkcpzk0rIY70kyxD02B/sp
NzcqJE4meF2lq/oAdkYboGw0QpSHAk5X0mW0ospcVIuBEhjZqRII6YjUlbO2Fape
VO0TQjG0rYM6Os7HPwu+cSb1H4towHjMokOVXppeqo+kz9qQaN+TorcfBb3LFvHS
QAFcf3bYON/yIytPehC9Ia3jgdp3RDmjAp5VrRMn+RyAeA6RcOgQUPaOLghbfonq
pRHwS8XjxbnprEpL1hSrPSg=
=bxsu
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '895b6084-7b5d-48e3-a6bd-b2d2ed3230a3',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAoYQs/ZCb6ryRZPuTubu32EdwKL2X27EYef1pqwpc1ZxY
n89H36iMi4vqilVxXMhSu0uPcoQsmbP/7pi6Ap+CFoH1M71IcbOYBqIxhBMcaNOj
s+d0y74DIVs2gFE/q9bcL9bOZejYR/gth/Du+1b0eKc+DRwPvIx3FFdIDzmsnU59
PrVlSrNyRr0qc3ZBb8w1VpdnVZsulY3xE7GhV7gmdpTbnOvc1mqxiPCBCvQzkWkP
ZiijFvEvEcgSEbSFbOEqWm5p17w/M+vqbV3RCaL3vF2mrNBdrf4mVWKxW74wti90
SQy63LQV1PDW50iU6KCQdlLvepkhDt6rvhGWoozsF5IT4Gaaezp/C4D1T0s739dH
wx1dCX0C0npJHrwo6j8yrumE3C3tUWyJUEbj0F+Ii94YZ48ZDhlR5FpMRvTyMqOr
P8GCeLu589uvlN7Xjnm7LS/krmWEyt0rCRCeZil03kGQcNgoQtwXYPEKq3dylwme
dZweZxjgaLniJrBlpV3YUc/LWRS2xvn+trGuUoCxT9mdgVFSZQabSrDg5/4OG0DE
oSwg1TP4/RJ9xfIN7nzuu20lTFXAsQ0fVHvPd24Lpw25zP/0tDDlMws/TTRdd8U8
09fKT2YFssoCo4/J51M8CRz5ShMNoq/YZljUA1hssLn80BbSPiQPpCWqnieXDILS
RAGMPd3OQ+QvAFissAr7bq1bQHu3HKsqcfPw3VjXgj+nM5QzX/k53+N1VvOnyi9n
uFwUCytMjjMK0k3VgaWEcSMDQOOu
=RB4M
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8a486e39-d17d-4f55-ab56-3654abe1d3d2',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//RaLz+xnqX3n0KEQoDxrDFCZX13zuDwg/djlXGmQgveBf
cyywf47Ak2oN8lNKadw0rSrIsX5BbeLjFiZOJlaHPLtd3VyOwu8z/FLEw0G3lOjS
YIfuL1O8966Zzxzj8L7+RnKEoUscXsTEQ1qAV0y/1WUNqcfU816jJl70LnVg0vUQ
8r/ZYgUI+n97+cGlkPT9y9wO5zGLi70Um3ptNDh4tCknKiMTZSOOl7eotaaoxroA
ObiljkkYBHk+m5nHIPwRl+8ShbNqd9pOVHpkhGhe6r01DXxjq+5t+BoHcXU09rr+
irLxmfK1yWA2O0QsZwO++z7e3BNY6f/EKuD9k/llbOIuYtsvCCKILsrwROtfpEmA
etbb1zg/BhQ28SVntka3TI9sLj9AcoQkUWueyqKSwiptrlFSowWrGFqopvR3bAe8
LibI7FOHJy73giGhPX+RVS/jCQ7geGnJsIcjDxTiTC6ZVrcn9PBYPCrl+krKTtZa
gp5mvXz+5rqoO7ugQaD8CbAEy662ZCNCqrIvb9f3FJQvcLfvCYPvlvBXZXsbeDVw
Kl1oH/y2/xEboptVRa2AOZAr0okcSMj/OXof+hP/YNkxtDIgYhXOkq3C6BwArX0g
kW3nlTpj0q+XR1Df5i0IdHyG0nYaSrGUTSWRiVKvG6K46IyFGMlB0U4129SV3mXS
QgEvtYM9BBNygesWU13FIpGWxYQ7s8NQPlTf1swVyqGyF8WCktJMGGt5ERPa6/lC
GDHFmvVoITCkOCpLnsf5RsMAcw==
=QbgM
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8e1f40fa-ddda-42ca-a5bd-05908442fc41',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAs5dl8+dg5jqi54X5DD4uuXVqiAFT4jhsop0TUNk8J3HK
BmQfEfA4e+aqMqXRbSF5AozULhqhn80rI1SjJClcBLO0GVxlOyVFHBcoojaZd/OX
rlVN7k/B0XDuo9KKq6SmnSPMAArhi9+D5AUn1c+NE0x1SHfq18QEff0y8X5YLUya
nnYtPbKZBX/ol41pydML4ULzg/Svy5F+tPzlqg0kupg6lReMZVK7wE8S61iGxpBB
ZZ5vJBd9KV9c34HhVRHz8GYohE4zqPB6lY298KbCBXFjc725qIgs346jlXbf2Hg/
o2PfIdNLEYj51cYDJeoZkPLc6b7rZTotXjLoY47aNQRcA3kXc19YxUXzOWa3MLFR
KJmNipKZD2RNfEFHzFYChFhBxDVSu/mhis0JpnFnyUj2r7s7uYrdVuOVSVox74LB
SZ5cJPQ6OmZa5gDrKFn1cyh9NZliWYdOM7BeNuPLsMoAaTSAIFaKSBi7vjomeveA
tbuz2qWDSg+CE2FzxCL2nAKUpwUSAlZBBPH81jeHasjAe5s1Ha6C5PKt2CLsRPeB
6FCAD+puJ7/PK8+5SAMLt5fkr+uzLGg7r+M3FYkZ1kr+MIDTXvSVsT4RgsS+2pwT
uEYej2JeGel7DcbfsFyyD86zuFBJMu62rZUOxPebcIpw8vYrVBsGgYJgBxcWQ0XS
QQEDxY5qo9JCV4oPnqcOHr/lBkZbaTxIlWKfVcZ9/QM260Ko8pUMI5eA9DmoJmHf
iUk3vA8FbnAoBgXVOcjfqSep
=3wYm
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8fe94fe9-e344-48cd-a198-4cea12693002',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//bj+I1aOonIbg9EB45xEy+DZVd6woL5M8ADouI4I1mlXM
PB2Y3BgbGPi6JN+6vGbl5ovLBYDdkaZDL7lbQ3pByQXKnZ+frrvDCAv3rXIVz4HA
79/5/oucWDaT5lRa/yUNgTcHuZxGnvys7dBtzf1g8rwngUwrnrdrenoFWbZKG6G+
4S7AhYjYqeKNNCSovoQ8ReDbVkpliWfmXuBl+ps+rBf+K+Qnw965GciyHsLGci6H
zNXdoLRvWdwxwIa+s69ku8jmRqOIB5xKdTB91HoG51+oyHeOjvG6LIRvx+xUoiNQ
eZjj4+J+jLFKarLsBfIb76hOZQv2KAR+7cQir5/gcIT2OR+DtSJeuerRR81gW0Ss
ZhcZ8P2aSR/IYyw3rQ3Zg5s8PoPUZupSs/6bOjVYMb70qX7P58a07LQFlvhsNRFc
Yn5KpTfqGwlQ2kLzdV+/Fpj+sCfNWqvJfTSBJ3qhDYmLeO1r5iTUOJkLNMBcTWuz
JURtIquM7HRYedrfCrZLvtnr9Mpm1wjvP3cO+SWfdVuxthlHWdR+2zFO3ccj3uF4
sUox2U0b3IpjYZ2JCBO9Vg1eT+2tQHHi0TCZlZ0PU/Q0R+a3Dk0+dvNgyoFSoq/8
JOP3MDEgQQD2dpCvzVxdOybGBOCiX+73b4MVHpfgtkoS0/mpKoGXKCCyfHYdBmfS
QwE4apOoXtcKBKcjF8On7E3cKNHhTQmYQopGKUbx/zQVibLOwmlJUR17dNNFfuFB
bpAbic69EV3H5Ryn3tl+0X9kvzM=
=dkKw
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '912bd922-3ab9-4373-aa1c-8328fb30ea7a',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAxXPnlldhM5scomR/Htruc0zsmZlGMPxGnAUsoosZOnLB
BUDKUEvfA05LAW5H1f9zWuBwV+UIy/vSGUzdo+V4PtGYM3t1ayEZWhgHKtOucE5P
4ZwoQ+ndhKtw+ywyWrkyeVEqiE2/aaAEF2cRQNpPyouQIiF/7Pf/0oiji9ySndB6
f0O9x/CxTzhRsGTsVo4pNnMr1tvKl4p8Yhtas4UraukGynIXVXkvLnESa7YzNTSS
nHM2btTb+m5AoBEr7tLI0Ii4qkun4wjmNlBzW5A6UV6e4FMniAbJJm2UqsmLVgy7
DPt3TX2XlzlZtISA+xpcCxAYzOihZLnLfrD6HsCAGhSlWBK5rotnuP5EWwlu1PL2
WmBx4ENd0X9mcARvQ+jiQOwPQm6ky6u07UcQqirDcKTm0zSNdgLXEFOX/FcXai78
BN5aQv+Kd8yk0JBAJcXyYQ9swLRSpfpw1TzXuWxJcDZMNrFXNa5AzWSGjf1LMQBW
1Q1DFqCs0ffjeQxubiHSbogmx4Kc8Jj53GMybBG1e2bzGPvQIjLXlchZ1m0wNS5t
ZoFupAXGWO27m/vdvWQJTGDpYeVsJOEsxmG3I8F5xlrJwWIEWQpyXWP0T+pp1u//
JCNrq0Jh3sDt61lngDv39rCTU5ymbDg4lOoqurAWgyEQmdpMTWbTX302yRwmXpjS
QQG7ID4EZC2nSpzjIYz3Z72iqwhYt/FdN4XH00leGRBNgHrif01tdZwlKEU8qWhs
eQ7P4pTSYppS1uDui1m+iCSh
=YH3s
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '918fe463-eda1-4191-a4d9-2e3e71e159d2',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/5AWzXTlPWHDc+T1QtsTgbNnjKAcNjQ5nG3KFNOjBiTv4C
FH1/lyzHbuvNnd9eUejPemxX4mk+nQTpgsmPUBxPwvS2FmFhBwWmleUpMsqj1RkR
h6My9NbTU09VopzL5enoPfXpPUZVAbRR1oxpMlz+AsElhKn3BVvtBVTiLwKeYiUr
9nhH4PQrf/074ASWmvrsUoaXT5PYOSCRpJuGGWeTk2RvUvvOqN9VFNZg6VdP19EM
CKjuQrF/AOPU4iaNrNeMt7gY4OQAisbn+YxylO2cLe2gOBcUfI/b8bYyOkXhXAcm
LxGkRl+9rZMkA+IvdMN3qpaei09nJq2EY4w/BMEX0hggGArJ77T1WUMN9Xh7I4im
p6zIcvYd3bwnMbkwPv4npD4T5BJerGGKab5kdTYfpocX3ZZlkJLDp2uuxxzzHut/
RqhaBvsMCbsIV8MdHgjtGNgmI9lvREcNfKVD8/UFdHYdvBhLI0NhuREZVH4dKOKO
zJ99mYiKRN4Vd8PxMa9hCYG46pkBwErN8MuPTUtQMuhpMB43dk6PYuvXBR7iiMd7
N4ivEqCz3odOU1ToeOeslO5nxqpiOWSoaQLseDfRFfx0AnQmbYh28B7Z/W4gSs/t
CnYsQYWRAgwECI4YzZcRsKI8DDuqcbBAC5m8OkXjwBX7v92KND2bRyQwIDxyx5PS
PgH1C7zSneHHmVkhQ/cWSi0c8u2q8ubv/XlYVlXNAR/LpeeaazPsQCTkk0z8yrWz
nSXhaSpoI7zKYrz/FyKH
=J4Gb
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '91bd64d2-8d89-43f4-aec7-538014d209dc',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//XTtAESddNA+tYCM2aIehGoDBeJQwz/GsaPdq4ks7Cj3y
uZzUQCfeEHHkQzI90AmjfV9IGb9YMbnRZCbExGlOWv5hw0fdngXiUejxJPhooCTE
XotOdKcqv6MbkuWMF5XKpqcNVlpX6rROGbDXcKpeRhOkRXXbIFQ0DzS4OC9PwlhZ
arptALwLzaqrYX/xuw8b44myhQIJiCvyvoRqv4uaVzaR94xLiGw9Povf9OsIQFQq
ekw3RjCLN8C+m9P74T5lkQYlsMeqfnNglyOzgZ8I3s57cSiuVYjJtuQREsOWXYO2
ZpItmWz4On0bninMQQGsaI35f6zEapB8SePQKG5ECsxoMMxCp1ikUn+q7gZ3bBZI
+Yx0nEDTix4P1ZYZaspt1d0bY1jpHqX91z4eGT2qSXGxTNlN3kIsl3LjXL7Er82z
Oci4AtZfLbMYE5pXH1Cvvouc0E+3BNtjvVL5GnltI+4GTb/sg/bg+fMp16Hy5y+8
7JykzWezLpHFuCrjjiILQvbvBmgMUavUR/krUt6E5b4d3CIzm1XiePDE2k2bd9RR
9tBSurtN01yHdAuhxKHERPENMSKu4Ya3CJS5B+bn4d+nxoof3ky+jC5MaA5hf7uw
gVyXOLv94iSdSR/5IdF+crcACWdU12m3ARujv90b8mao5xJIOrUqahcthpisfcnS
QQHeg6i5UxtPhDx7cJw8OX+++41WB6QIq6xMJqeWMnRSbAN2+D07gRxCEcy6Y1aX
z5tZZuGCDNYzh9fjuTn0QQu2
=szy6
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '92a99336-2b71-487e-a03a-e4f8fd15a93b',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAoUAkf8wgQX000PCsA6UWcr5BMiIZz4Jw97P+pKzc4c13
iig23E9897LEYabU8evEj0lsK4WaXgGvqXJZmlG07bpE/Q6wAky3WsyVVvYkJM/O
RgFTD78A/ceVEv3nF53rwmxRz1vJDb42wlhTTpewUraMCCFMCOkrkNoIWP7krh9y
37g5rFJlNHYHw9Nap7n+UjbpsjgE6AoHEa2QZoRqtrgDCtLOeWpCK/3HK22pfxbs
hiQ78gfvgI5WOeTw0/agSn2LqQdmjKx9JEmFrkTa602o0uXag7EggclVY+kOnOX1
WomqVtwNOwSDLQwHPD8DaSF9BkzAJ9eYRFh/7N3slco6OVrZZYuO8HhmUt3B6JQs
lVrqVBY2LFb2XFDJpr77xITTi1AUvlkYYRxFQ2FoD3WWHtHWZaXpJor0/gTqqELD
Jv1xLgbTiTvsi5/izlcv+21GpyBUbK7302SfW72CAcwDRulZ5s1y9f8cCuvHwKPp
wfzYX/tuxOgkd3AfVaLqKzrM2LE5yLe/BU6n9tgna1ZTx9dcbJTc5z+56aFf5FMG
CFdxFl2spgWVKXBRYhJgW+op3mMz6vArtzMDFm3pb+B2VNBtTMKR9VO6Ssgt17f+
+SdslnSq5Ji0AbHmUAcuSBdjH0ceWxc5iHadhM/W00HwW/+8YR7hyqib2NoFlMPS
QwGg+ujcDTCwUnh2jShE8xl+zdx4vj7s1fSXuUByk+xZ01+UdGH4vhxb/RUu51Nk
9Raatky8LzlBumrSDNPAGkz4pg4=
=dmGC
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '92b6e9a6-c79f-4d89-ab00-cd23e8c98a1f',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAj2dOpxsinyDtlI9h3OWksI7e2PUaJOFwzNP+DbTft6xx
l1+P5S9vaXbpy0gzRx8bIfvWS8GE8sexC5QHmE1ChY7A/V9Op81I51VHaVcrhQp1
LvestHtT3vMVdheJ+BgaxLDUkfpAwjkfGLXv+YxNyOdqvqfEmAQugNw1kUK0Am0p
fQK8YmPmZsfQQeiKchs0kR2/QDsODS7iWsq1aYhzv6O2BNd3hVWsFqESPShv83oc
cOjkqjQFOCJZgWUbRZX8pRYKEn+AooonqyGs643zMx1T4l7zg2D8M5/zExAcBwAG
yZzea6AH7395z8S9GmYcOhQ1rFCsbwIsir8VBi2QGFAq4qICVkU3JFmomhyzg6UT
5jdwkMFFy3fAv9dkusPfKdhFr3o/tU75GhiqQJqLBl/ZmqQ3rqjJQrFb+Ki2aVzW
7dHqWP8ZT+QoDoBzt/2nPxLrqebgQqadD2Baz9/mGqFXyrKCbeFrJYkW1QAeH3Zp
Q8aaXbFoJArc6FeKuh+YlkpowN7k0Lb4oBeH7u8ECQsq98gbgwTVGigEdp6asvdo
qd9iBhhwfnso3+hn0POmNyJbL0R/ki5uDGXMluMWVYMoqTbgcp1i10zJnjC5dGmS
AUfhGz5XtIXYymkjM5C0c7vbJWrrdIFCbAQQxbTjF5WDSNPHjcOe4VEp89RHyRnS
SQEFULdZ2p7gXQNVSAS/sKnJYwBVF3KYF5lZuT0ZztXLFVmsBfIhk6NSoZBtq5zj
tDyOBvhP5jjdsIypLPfhpIKY9YzMxc5j7PY=
=QWAF
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '93a0b027-4b0f-4f00-a76f-7eb0593370b7',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/bG9OohBP2idnG2iJIRMHQ+ZP83CmBbF1ZHLvmSk/Chpl
qsfbp5pZ4eXWIktw3AtCzExpNNCtwGEu6LdK0K9ZIEWi7O4iaD0mRftBuX1dTWkj
fW+SFd/4HBhvkNpeeWi3uds8t9yVpl2kFAz8HD7GBiEY05yPMyPjHLzMt+tGTlsZ
TvUyw6d03hCdgDyj1632JcqhHR/dOrdrrmz8FX76y9rXrZ/oH/LnYku/UJkW/yrP
IeoxqNjTn5Khe5Mo4fEbgbO+hTBPH0G04NKPu4SE3Bc76OolLNCifoXdcbgZ9Eoo
2QtZ6se0wbqli5r7arjVSyPaTLJX0yAT3m9zz6iNJdI/ATVmyGh4cxl2kIkWEbFF
ABD8Xr5bHW9P1MrY/1M/kkCHDZEgqQNJeA8700UIt/pTLQF33SWLyjwMn95YD/zT
=ceuy
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '93d79ab0-10c3-4ee0-a22c-40f6ba2256b2',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9HFewP5aLxCyPBDQNPf2RjFspuwcdbhnuUNxdKVOaR92/
FL3ojpqG+0jt0UhpCLbEOpLQzi19g+iE+hSof8y0whkabLpeEnPiZ9BMUrTQjuQ0
Vz0TNtzttQC3NgTMXF6/SRrCCi0SIwZY0A7CMUrhvEN9Pe6PawcXgRBAKynhqhLr
6wkpvvRUwdrbfKLxcDGTwgP9LFb5ofKUo/Cfy6kcnhtV+gXyQ3/5+90D6cbI7EgR
bldNg0G19qu/2eIjbQ1psRWp0nl5xQa5rCMASq+8LBTbeeA+P0RrkmCJ+TU2VioY
shSjsfUSfXBwCHkWiIs+5xZTaKyY0+/VUdkD4JvssGuz9/uJ09tIS/pFKqgpwbTf
72JvQx0hwZEixMSjYtJ7d8luT/9ggnjhZfLrgRNQ5vUVjIGitN0ln7yTQ5rFThiT
+4ath3EyBt7b0RvmEDJkG8itjYHXAtuu1Ph2GjezWuruPO1+vlcjgRJUK9UVTHEH
SmlTSs7+McqUHAiIwhuo+3+peJkYoC0pSD0pWeldggl+N8nk3MFkOHagPQHt1RjD
ULDUq1mblCFdqEZWe+qfQfMCQXs2FbBzEpYIYykngU7Lhjnm8nn95zAciQwAUITi
qObpRQY7G204WL6LeDDU7f/78Ui8ZY365WlnrAHk3aQK5aDpMxQkSL2lhv2g2MvS
QgHSCMP18Yt/43Nk/dY1VhQwI6wJFbzPXwSYgyjxiOnBwPLwsTxT3mYbU1v9LagN
QHkhBZ6k5oA71dRKHiVE8LMqJA==
=GzO1
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9435f179-bf79-48ab-ad13-256f493a90f0',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAsf2gC0XTKNpEA7FZKta2UffAPCCtkEPELv7s8t3Fvme/
ZDvrczvbXkQSucVEsR2/FVEvJG55IyiTvoJ5gSWngdWEQPb9JR6yhaEscb1JyBSF
S6TWMpNLR77ka8rdymTmEJ3iIYdArTgTecRf4KSIstKogMYokGlb+hq8ZHWbi2AR
rxINMMKpUKuN/bMPWJUdpe3yXAwSHQh2uwnpV6+6kj+28bDdYnudjjep/1mzpqC3
1leaXMZIvQrOAux3L32Dg7zAslC8yI7UDNE9HokeNRhbQU7n6Zm6HceXemMdNq+g
AACB521xgHADQPP61rLc2CIf9D4E3NudArGtthBHfzF5AzlFhvH9ipEz5NEAVW+O
pArX9Rv9383zWvfJJ5QHOzVcYmd1dvLX2iZZKDlEgep4CEpaKLUXVKlNi2BLYWvX
V1x1e40IjmYAnACzKFupcdIFMdlRxuFzflyfGdNfHj3cgIaMYyXnqo9Wjey1/da/
Zbwy94NQs5KSzaPzdIMFyTIjaF9GW3f8DmytDptewzhYpdJk668VVaYW8PsRMqCe
06j4HUHJ4rz4s8jFWfqD2051EQpYgyrKTvm9gfn5za+rYsl8SuFFFKP5cR3ZR4Wh
bXdw/1MkC45RAMOUavVlYGKCXwKvg1Az73dmVeUI7GTbqV39zhb8dcdnvcBmmyTS
QQHIOYHs7ZX1JByRvParUsxQ6yz5wSh79788kmUFK+5zPdhKODrKsIfVXGor1faa
37RrnoPsVMrz3qO/dy/uIPKP
=u7Jr
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9624836f-7185-4279-a69b-232bb4ee8702',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+LksDZlIwVoyk+XIYYg10YILZlGtmtsKqku2oEtDBYovD
MkS3S9DSa1UbmShWVvtgcRqU2/yPe3Q+WD6CxJ8T5pTHQWv9KBTIg0BCAQHQUDCc
kPnEI454av28n6zZ7hPgffs48Xgk2IHK8IYph/lt/tYiXNLFbtBsKjoZ2+r2MErr
2GsTNipi/T8BMFgJqh2smwTravPi977ikXc5E0Nm1D3BTUvdoXHIzD5QDxPRhqcv
/8ZebrJZUzWo7wvYlC4qg8q/0ayvAavJImTEx0U7XsI96dUqPuYxTB2sCAbkeijG
UjDpN7WxA5l+rHBO1hIs1OE5YYvs9A2yb00n5F6weReZjzMQEhT6WJmcBovVOccL
VaEM0CuTRa3hzvVFn82JTnWtWPYBB/roKJKOfQx4oPlrLPjpIeOXZ+TBEo+AINCh
XSlCTMYVT1vqnZtpnb1i5FV8fAAxZoMacDQO/ZARTbJTJRl+k5TPwJL0lsKFnn5x
65OhoFOHx1KVqjBSdBycV3PtCB1dFFwKU6mm39BWCWfSJTgGO4d5B3twSwwWt2xH
73UnkVDxCp4Ymy/HAV/6LEqNEsB1eShDKol9U0BzytqMR+GpIwn+FizX8GWgi0E2
T3MMU5ZUcfeAJAEKiBlCDjdoFwDUFt9vZf9iEhxDiYKuG/JAcXGPJDv1GNC8RfbS
QQFNB0jMnvFsWukQLFfZu8E6jdYb5Vd1Ii7JJsfc3/xiP9YW90Vl4RcGYTkbXLVu
rcEng/CDhe1KV3Vbxx7cm0oo
=6qiT
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96e6f3b5-961c-4912-af43-f6f1dbb10f15',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAs3ilGog5x/ij30C2AEH77IHSkuQlqUogfu6zsdGJLd56
bsAiVJsKq9cLWOYusZdF6F9XxZisLwycuEDtyAaV4h8XPQmH0zHBWNOJ1N+dGP2i
IsT+B4wcgaJXEGyhzh08usYU3fS1hVFJa2LLWffJX3bpcsNDvqrMyPFftbi8rX+Y
9nm1dMD2uhDEkBWvyM1PgdSwmScAjsU4UGzsqzA4QlGGpay8kCco2E9DXV4L3H7k
E535iSKnV3UVKG2ExodflpKLd9t7VqR6koPMUWCblHI58Y2wV63NPsrRkUnLsu/8
+G8nVMV/pZ6lLj4P8RtqDuywGVNbOpA7xd3kTxgScQxwZ4QT35tF+epNme58lTYr
oeSUNUph54jzkPYksmMGQD3sCDbxjMlZ0S2vtoJCXI7FJQyeb6jqXSkLPDCHMsS5
yHSSBue+iF2/f+eF5c0rx3NlJHtjqhklToiz07tAe6aUVEnBRZC1buTREoXhIjRs
mhP2sz/vG1svISdXwVHgk9lwwQP4LN4NcA2vifRivl6oierLN8stzhYUxTmiZ6CM
GxD+mYIrlHEtcr+799DKdmC/UZt+OSGiIvhQgEtHfiy2tM8HGB8BIY8Ane0F7/bu
aF9pIf7uCyEXFxtkKpc0VgteAoNAFpfCIYfUWN2IvMZsSdjLpb0YfjvQHaIR31DS
PgGncO49eo241BJ0qsk1Hpx7f6kPeVc2OvyP9ij30aKoZelu0tbAd3Nk+9HNXrxJ
vOJ4tFcvwUjEyJpJLmHi
=Bo/t
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '97d882c2-04ae-4cf4-ada3-1a3ac240c4d0',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9FMFZNygE/unSb2p38Cya24iz+Ge7Y4riJSOJwdVwDem3
j559WXCKoY+2mgFjVwxgLmjRhymWJ6XQ4mejvhRO/fnR2Yy10a7ZK5fy605c3P6L
Ayt23dYcP9iNn0CYFCD5kAS6VK2DfjdjqgN7zUxrGt95wcK5qNga8ugf4ju+rjhT
9lPn1IH8eu7OODcUkMVtvK7gKGKdtWs2HXQowTl3HodIoKK7+wzPi/xaf63nJ4JR
h6NkqOVqVLywTJ9WQHui1Q2Ji2ZZjd0jQkDgmuzz476bTj2XJFGso+r8So0dztRx
dDMQmme4T2u89s6s4h9gJkRwVi5Ic7im4XzPbvvqME+MKNRoKN97R3j40/uLVRzB
yxgNsKyG3z63NYY95l/L1iTbksOxjS+dt9GdDdrhEGq1SDiUxNO1yia+mbbFY3cv
JAhNKy7q44HkAtEaMAhom5ZvLAI1wWIivwiq18BhUrtD1TIBVVYzyntzgccc6pED
mxAapIT6HHiO5EuUgDK2LYNwjCM9Gj1vZjTXPUcqNayvo9ly7/l8lwUi/BAEs2HV
4haRSeCScBHVGn9MH9kRHKuyIvouQUZ1FjPZhby/zoEKP5jFyitIic0wDPMN8T2V
DTiL1HQCiDW2o/gLu5ewMUJiKOsY7r1bDZUvHj/ekeilP5DskpIz1FgiB9BvQ8/S
PwE7CSG+8zriLWYR5AHY7k2S4z0B22wZYxezxg26ji9F7MDdTYf7NulYaPHHJLXa
nJeH6pSgADs2/DUc9dTOuA==
=trk8
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '989fd7c7-ef51-4f53-af81-20c85259b97c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//SOG5e9SXDHybvSSiFKA6dApXhNBT90qXPZrQUQ+URgns
BSeIUaWGqGJingGHl4E+VbwIoE3i+tkzW/frBRiWYkop6sD7o9UwiN9K0OSoB1kT
cA+BPT33M6sQ3p0KkTKvpWO5cwJt9SFYxY2rsPuHBY93cpruWmwJKIIc1s5nWQ1+
KPSgdF9xS7YtvuqbSkgocJ8OzpXF9jFvtKMli856mY0PtquZr463puerrWzC4+Tj
UG++SFdYNsepAkQvxPOJMj89qQ2LZRUxnFo5gvxQZOzdjvsKfxiWl8wyF3QJXs+l
mR2EWktgoI7YIvts6nL8QbMTZlkiahLBbc4r+4wip1Y46DJA7JL2EoUJzVWoE1+M
k/enkyfGDI8TCgOalcSyxQO0sZDuMUMR7N95KF3r//ekuqXxeg2H9NpI6l6KSeaw
NKnS6Q/MnIinN2EXXUvHJUFYP8BXN/0GhdDC1D5+3WJ2MZ/YU39zHeXABAWLoAWx
7sHrGPyQWsGKJILYBYegO89kxD0ZbPriVhU8Inbo8qzUEWPUYrAW33T714q5267n
OCqZQW4IbczepktGU3tO4cNaY/IDdwxiZojDBMPq/1atcNDfixqddzsSMtIQUlkb
vCnk95NJVwo2eGAUWBrUXKmZm1475/oFjGpP22e4uHp0XLOp3l5CRxZykJXrHGLS
PgE/0gbpCVUtGr/FmRBg3UrwxB5aZGHo4MCmD3LKnH5CpAtL58t/Ro5ZYZhMwxuc
rl2/c17VGlq8OTXxwUAW
=rOsA
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '995d43cd-8b30-4905-a8f3-32cfc307e63a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//a2108IDDcUv7z+7P63U9ngKWz65aFDrTLwVLW27UT+/U
L+KGbbEWd7OevTa4RexaDT6T9BeaWZS3uUG9kPvxDQjnYQ6RatmBTlTr7ncXbVPk
IZ7oVgiRXP0M/iBLcR+ICFv0rTnQIC2xo2u2yrytfyo7SEaXlzopGpxs9zhce85N
edSPBOquJjboLmcx9aBh74HRH3a+oM2cV8wcotCDxBGifzlYYlf7JEFgQzg12lJd
aTmrwBm2TSuf3eDx1n4662xDotMwlj8Df9ZIe/F1ZQvqUnoeIEDYHGpxL9fPimTg
IkgXFnl2sD++z15a/4X7PBU4BO7aTb0RsuhwKuYrwWp1PClc3mZfVZjoaf8z5pjh
csLgBYO7kHfAPf9hhCv9hkHiNZ2wXATFUnNfz1Cnhofdx3TyOQ+y+2TeCUDIHswG
lhliau0uBHIxz9zbahRNyQKXn3ScFYcQStP0H2N9AdNJF9TuM9808nn/lYODqApA
6Fd/ydVkj+pSNWwB56PIUNr4omq8/IyPUEdJc5SVyGNtJCgCP1+XItqvLO608/s8
5j4+00/YCXrrjOhdAS7LrnvFeAvL1JFS41CpWDE4OWQKyiNv2olsI9rHnDyxpsrm
O8XLKnRSAuZ7+YxpHcls5Y/36wmE5ZntZU39kLE/8S/VUCxRjhFLTLYtPK9OTF/S
PgGvCOzg9mdKxg37ETF9oNY5hODb6cJMczIS7PoH/TJvsPeRxl41NUuCYJnyTtId
9fJ514FQlH3XXtAJLt3w
=5+Wy
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9b22c377-eb8d-4902-ac35-d9e7b86820ce',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/6AgT7w6EuHLUGsr3r5oyIz5ETG/gG1IXfyET/WohxVepA
YUXyHgzsRx70hZtO/2dWoszo8rj2GyBEfOkLAKSnee8OUaz8yHtGq/VAra9/S1Y3
tSOZ4tGUnVnDD/gCSS8E+gDtuoQN1dU4tTvRRBgwbrUH8wcpk3hNLeDp8/sWlIiH
wbsiVSggI7Y/jRaRTH0GN5QnU4t6SRfWDQP2A0JZGlbwVcJ6lgl2+yTmOLLs+Zo3
ROjt3h1ppucIsJw+Zm+x3KQsEYPHstrX6J8Xg18Ae3ZBjft+vG4yXf6vOEUEqAAO
O+wHntZ1gk+BMKnE0wypUTQrtRZVePdfZzsWRjT2f2trCT9MhcNdk+uKBYycfPD4
ZxXmkdkjNXUgHOgve/dTvH0pU8JWrxCqObJVUxOBwRwXeURb/M67HRAqcwSNtr9x
Vj+1IKGm69LjrvTHCaKZfpla+FJP4u4fZ8odtJbSSQqo09UY4kjKg+82v14yAjo3
XNJsWXLT05aQ4/yN3GgcFZnB70mY2kJP1UrFVAYlrSpbwU3TYPxZiQ0TaYmaYrGJ
quwNqll2zpy80Wf1vKS6rsA4WvKZx1DdwU29k7igP4vO3GYAPwHnOEbBG6LZzhIr
jkJu4SZe7Oym1AyzS6tLdtFUflwy/YR4RUHh81/sKe/oal4DyvXe5tQMqwR+WZ/S
RQFtcNeEjA6ObT5vFUtaS+hh+KwJXc/40/dNCLUX+QYUDoSEvGmKYnjMQbifc0wB
rD9tEvmfAr2Os54I+POEn0ZSsq56hg==
=VKww
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9b8e358f-4310-4d1f-ab30-fa6d0617034e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+K3OmjG64RQB29ahcv5xaMARloVA7hk8hAqhZB/8ZUKbZ
vkXWgz/APbKwBx3mJsJZBzlCVQZgZy2STZSKf9M4uKEXvsTJZgpJDdoeEMR9pSSB
07iHkarf2ZuosOgzaZtmSdSSwjiNx18WnO3ctZpGG+2pjAQuIsY+Aaa4nkrcSeVq
wkj0qlik4qI7/C45zAkW4J9CBSbdAZxPvV6Ejg86KwScL9ktSViY8TKcPU0FKVbG
7P81Tj0Q5J5KAX2QajtudtFo8rB7UEcYj2BSHhBYyAKuJwtsXfOVCSTFZ0bz0Bu7
FAtqofEj6OGnVmYvz1XrgCNXms/7u2e/8QN9IX6qjLJKvtfcFXNv8LFw+xfeqDOe
0ivUiXREy4pTAAqCF5pMj+aEnh4rqxA55/Y1N0q11K51dSmM4/SQFtj+H4maowhD
lfdoJJOlTz1T0dyGnDuJi3ebuNGMNIMB7p75L84enklAue+YlL/63SE+Pg9kptAu
MPdB4OrUBKBRu+uZ6Sp0nmsj9mq90X4gNggPNJPijqfD+czQ5HjypOSQBdHSE3Mt
MhcymN1dl1LvHP8HfN+Zx05w2CoCV5uZ43rlJPtBGTrwBUxc9F4WKKjgNCcYzED+
a5Vuh/UBdq5gzkzAlkBIULoygXEMweS+g8BhxiAu2cg5BPnbq9o2oWU/B7GcjLfS
QQGDwAP5C50/5yz0p2CfpZj66vKFDqUX7wVZynC9GiS63X8T0oppkqtZwKdhvQIj
UosENAMLPxIvKA8D1qEXlRFY
=mQzy
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a0254356-5737-4942-af40-cf1b213d7fde',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/eV9rWTjuavlyyr3qbfdJLv0UE+83paokOb2b3V2Qr2Sx
diMY4V+rMCJkGlT66B94b0/Tkh0cj6SZa2Npcj3j/i9W9YXsHptxcwQ9B1QmMj8N
Fc931kLgpwfE93+1z6QT2cB96swYCTkrUzXVUCox6DshLwvHcTAnYkb/z4+pCM5r
sFx3c7/zvKkXmn85p3dwDmBGwxs3usBnhkbVhvl5GnjFjo+x6s7cZJ3dN4fT0agD
IjidgtJd14a0XXOouKAGTeSnwd/DI7HIGfbhqgFySlLs9ShDv09YE+vwibUZEMHJ
LVRwZivtiM9lDir/l7NZ2iG6y2VGrP04O/qaVx8ugNJDAVYiWTBLxhV+0pBaTbO1
CPd8bk+Ba7bHMY+l7bt3RPgINF+J8MUuvwPLrkdDNnf/Xg8tYv5XOEMWepn9ieJr
IrHDLw==
=b88S
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a0ed435c-0412-4035-a340-a6ff37042cdb',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAsXbX8xHBsFta1I9ICIobN+jR80LDOC35OrKSPMJbOVST
R9oM62sz99SFle/Vx/8xk65agt5v8ApJecwWLZgUTlfT6IvWEMipdMgaIIldABud
Py1qGcmZwgyMUuClj6eEMNE7SPqgyz6nC/sxPHma1UGHbp5KNw7+xhKP7B4XqQoB
UZlHH8uE9NeNHZ5fe9pCHKp9q7I362oEG6ed/f6S7RCto6nPItGUsK0IW//rJfDM
wq0ObTcPx2CC/rSyJgBBAr3RJTnQKa4hGc8aDUz+fkkid0heIp8Yd/wEO/D9suxI
GaIQBj7PPLLvp/MXUV/FLVwea5KKXhau4M+qwCpMJtI/AWGBYzKkmDnlmAVHcBS8
Vol1VMdsTR6MzWot/nrEqZ1dJE3j6MH96ZwsUCligxqB7BZUx5fe9a/hr8wBT4/Q
=ik//
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a179ff11-3688-4d9b-abfb-30a8e17fda68',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//YTmT3I8zUo5V8dkp6zRLCNs0+Bfd6UTfkG6gsa9UVuiG
ZASEs7crvKag2kBHy+j7CvfEYloaNfdxTXrGv7urSYrtJyu66uLArt4ypufHFYjM
SwOQ+M5KP29hfv+YDEg+K5fxw3wjq4fg9K87a2cJ4Okoxel0HBGvVI00rWdbhQHi
/zWVCWl7CbMBrZIJgXSfeXTdyvzPLIIFKBZCUyMO6MfFz9/siPlKMFlwhpolofgN
Mcf9SSsE94T6jG6k54t9WCA8Z7Hi8WYX/kkC2xnJMDPCZGqLawAfAMkowzQwkaxn
FJx/cQMaymXuokEJvkUKahfA/ibeZVp30NkgDGZ2Pp48U7LeYj6nIjVcOQq4OUZ5
kkm0kN+VVvsw3pJtxh9P2Jil3QqlcsH9mKyK86bQ9dus/8/ZQiRMVJPrTyutc9jl
aO70VZNiyA60dkg89CX0juWThBwgHqvS67VTpcR9cE2N236zlsQ0Vec6sHvJVwSL
hAYKvKpIs3ZlNMjJ/C92UbmkF8zEzqOOeFVkfjIopduYAGbC0DNZ9U4QmM6bWLaq
NvcEFdPfaXGhMfztKVaX5RQBYN0F2KSZ88aiz6HInoBC84AbFklpTnIK7EBZisBk
cbaeTu/pAsIVb6VAnwvwmWjPWh6ifdvJGAcpHhXLpakFmOjYQMYdzek9o1J9t+LS
RAGsGYskEcxCR04RDgGyq4TyWUDviRIDT3FZu+N2/Iq6e/vsFJZVwhEYBN94+BE5
JzVSsOa4hLFY8GEFBfPU1w0r3LoD
=hjT3
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a1e31ee6-7174-4f04-ab4d-cdd70e61e264',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/5ATzpm2FY/jJ++gnMrPmjSw3Mxl93PKRjAXyo8KAnhV+J
IdlmEGKVUwvqyn8S5wgjQ+rQALPsQ/bxb+Gl70mRNI9fH+fLJu82/AGN4zQQ38Q5
XA5+QiMowVgDyxvHBeUigJDScs4FS2bJX6JnlXtMwRfMvjJB+2NHYIX8Yog9gfs+
RkD4W+XyV3PbvVSKAEf5nu4KLfOee1MNzYBYbDvyEBh2k9mot5K0VSRWmfRUWLD5
JH/oijV2XnE8+no1Vd37vOrqR3LLu0y3RWQlSrVZRrVltEBxiqh1UpjoyytHqFXc
Qn3xIgnzBZJXbirYYMTm1JYUEAQZ7eHCT2BNMaQgwrJ4bN64ZKG0FVnH9uExMBT9
xWVB6TC6paRcrGL02KDlPOoe7QKKZclxi9V127L/7y8yjbKY8Cd8ZT7BO8vvsHmt
u1skZpTwetw39kMffY3+9h6e58TY1ESk8VBVj/vLKgHscRlygOaJlyU4tNwbfA2j
UkmJli2TxbSmLlEkipPfljj4DN5YfflORWtsk31IRBf1KkWmmQDYbnxQGK5nAgrN
wTagdcYHZ1uhjoqq9YSfGgYxCn8FscdJOnin2x63q7F+pKOZe/5yuDHoAsJcXlKu
+t2atkyWkonVOrMJnAufRldpWVRvqh0ttqovoUZQP5rKWD5449T6CU2kCAClLnvS
QQGM2uYzZ++j/Dc5R8IFkKmcZLZVFzXA3wHoXu5hTMmnr8rOqhdBPRi0O7tRBRDo
POS+8KEy6fus0K3/N92rCsNQ
=OzdS
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a4361b9d-158e-45aa-a21f-1537c27fb45c',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAn+22no6szk1N7gkoCTMi77l/0fadNNIFtz7B+E5UhYdu
coTxe/F14sM/0wd3ibdQqb2h3YqZtVQR8rxWLUlMCGJnvouK9fTGd1MauSA2N/N9
JYabXocn8b+c5eNxNcjY5qmbh4d7TsANHq6XMaP0L/S02qplotX9WaOdoGwP7j4c
ozPBjm0eUZ30hRUnieS8qtXjU0pWOZciP3a5P2iLb3PJ8WkJvbMB00lXUoaMMHdj
Zy+D2RQqEMrisJSo5ZYhu0iwvnG7f+2WxGH36RpfCH9HF653NkrmAsMDa4YWH9yL
VFGHik+GO1A6jQTl//Hz04OI6BaqU3hG8W2dXSDSzJ27JCTM0LX80dasIVWJUzti
JcVEPdVWdZT7+W07TTrjxdnqU1APSantjG+hNbCzed7KRcPC22LFPuZ2QUrwksur
TWnIxsW7TzaV/tcaCXwUYKrjGn/CAKYW8nvJk9Rl51fylHtAg/9PdQpA+SyD5L+A
rm+oV7sgrABYS/21fxcfwUHvdOwnzWjUV2Mli9sFDarYpQDCSMVTPagK9PZpHaF5
7xQdXG2SKNx4LZ4APV8e8nruOIW/ABb3A0/Unb8LtJKQWH+Gh0CqbZDNBaU25o2N
6JZ3bmH7Deu+aSuiKkvNFU92TNx5Ur3jqSIRxBMO7pT+qhoNkd2hWR2Y1COMzWLS
QwEYziJ7jU8x0Itwgu+Dp5OZf5GmkYkyD16cNfVhvh6zYo9i1qatKcwCK3Ep+CM6
9GQmZSwE5hVgtc8ou5UD2ZqgUkY=
=VoPy
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a7097c45-e1ae-45a2-a771-a31d55640f63',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//V1kduE/AT3+BCQ+bl5Fg73TmYiwN/zzjLftLdLL6u0he
xdrLfXIkOhwECsLlC7IwI0O7gN+yTzS3fRP6bzkHJ1qWFKnhvBL0A7U8EwNvb9Yn
4WyODDPEsQ2kgC9Y4HKVAjC82UWINPhVNqKVSbsyTm/d8MuRMgNYiiobY65gXBGT
AbXvu3mcQHsWWdcgG/ZmFV6Iw9kYQ1tKuW3XA+dTeoxtNFedlqjpnM8hlKUCnOwa
IK2DU4xjxa6p1yOV+M8HaZHNHqZbyb8//BjX5bfj7EgwRH7DaFP14TmSR2Oul120
L2uPe+hRS8Zrang9Y8GUM7Clclj2dh5hJcwHLs712Ykwh8YisRUbp5SrjJr/kqgH
odzixiTCenmpYLMbBS+sGGEJikux9Y858xR2D76yvBapD18nm18vPuaUkk/4S7/P
MMbF3ES0IxeKk5tU9Peeq3+zEgb/PgkkOJe9ld/7lfoxUuI5NIoK91iCMbHJKIv2
nxEhN3oIklsyNSKO9NvhqtoiihpKGFHHFAJmkVEGcjsWqScRZk2gFiVZbuIqel1S
CdBcr0dkwlHjiW+LULCua8roVtk4RpeLhsuI6bYC3mpkqHrbPc1WoMPtEp489pNm
oV1TTzKWyUulM3BGZr5Rgy01qMCFxDWGr/qNp63gN9o6WyvSpgY6PFa9+9UABiLS
QQEAvNxyAu3BinPhzst5+R5gOlbrmrbmowuk+2VU3obFrJAP/cSrQGKRhlB92Gnn
Zr8BVzR8nK7+DhvGnV63xbWa
=TFdh
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a83dfd78-9bdc-410c-a3eb-5e5dd0d46fbf',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//S7s0AvItoUxdRq4PreFQempaDbouX01g8eDI9TAV4IyL
jUUb83L3pv3PqMN26fW1bKC97XVIvkLInJtpnZWcgLk1PdDdFaH7VC0FNuejGAJz
E1OzC0u2P8t4NihzZuZM30dyUvRaHQHG4NCmymZoM0QhADkmmNqirhl1CxOYSqA2
XXPPzyMnny0IEYlorN7+PnUIkgobKWeMu6rowu5iat35h0IUpKNvDgrIddb8V+gj
iERi2SIluDZ2K4xeduuPZj4jkzuAwJSF/7xVBLEGoMY16c67Motk+Jbjf9E3ljb3
gVkOYGkneZPMi0b6/O7ml+GAbC7jyOdVxQ+bRTnBXAyolSOyU5n8IrOzfHbDi4MO
nJ9xodgZKELO9gyCMs19lucjMPO4lp1BIulxVNW2fRzIb+jq0W7aU2hLRqlV4JkA
GkFY+5kODVCwmmL/FmmsAjaZRXRglzRsaIJ6GpiuKdLofRcQd5USWo+kQRnXI5eq
FIpoFUBUYzGWgjVKi1/c+IGlKKUOJQv8yZXm4y5f3PznQJIDn65+3zZv/57oKZDi
KUjmXiRLNMIW+T6pELD4kbghoIDeEqUM361S2SbYnlEN9nnZnPZGxgrBnuzHwDr0
6Gl2VXUrOIwdkqCinWGO4KEUOibIFKxNWinkDaRwmXnNaHd5KEYyrB7bZX5Q1HXS
QwEhuiXQuqmeHarjmuACLMuu0wBGCI3qvWzfG+IlJhtd+DciEpEnW9JgsYYeTpkH
e6jTieXkLzzMwFzOQLOzweri10Y=
=oAvg
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a8c8b246-84f9-4eb0-a0f9-7244b64ed8f7',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAwmJiIEiq8An7WFttu2I3eF7LhuzmEiWy+q/IYHLkeGiK
tejiiCii2UVEDnDdTRZFyPYBc2fR2EvdDbKXwUSF41dtsOmvAUZPmQQBfZOS0mJ7
lsnt6tBFLpjHGKmmhlq3CCWH5xrdtLcXwCG2BlW8QCDdq8SxqZY7cYP8jFZL+gn9
fTah+7j1LgU9GaajXIUchuBYHxPjeEwL0THyoFVaY/yLpDsfS2SAvSG/zEmsH0EA
nx8XNjr+pQzu1N+QsPjTcFQsVd9yareJeWvHvKMIykyvH0M2WCInBsYwbZjhh5Hh
KGRKlmeJ3isB49ixb8x4QdnWlHJHVq8hmlU56LfgU2xW0t88Z+1UFREKTzmkbhxl
UtMvdtOXJRAtOBs/wt/N6Zv5C6lhVQzBa2jvic7PCv+c6eR3BEsprw0QIW6zIccF
Nvg73YgRtpOF7Pw8ZsxcYRtKSZR4aRGdAeneAPAW0lybSm1zN0aqZMGeyPt9MueU
E4YXC4YMMs/acTXct38qWW4bD6+mU1k1RTd4d0isFX3/GfJqe6BqbPS7Q+Fz72cT
luh2NnY5MJBOucTG41PHNyzayhY3RpHiLnJh6WEMymDaobecx9sc/ZBrrjMHJLky
g808i3UA43gWIJscU/NWUAj3pkgVoEnl0bAXPxww7nx+3IrafgGi2FAOpoU+gfTS
QwFan4jT8LLw91CiIs+iAqmxbAZrg4lWkY5wVRy4hod6Dq9jXLZ8w+r/sDZcCEyV
ateL9piVTm29KgjtDpyf2K5kFRs=
=Uu/4
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a969e6a6-a24d-479f-a03d-4af95c5750e5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAu/RaTor7Q8A8GlAUzicK3tZOr6sZc5zeWExg5Qk0HIF/
NXLTbyTgkfdcqA15NLKXktBw0+3OngFtBJIyb09tDaQ8o6daV4EKqLUjZqu2Jpbo
J2swCfVnWxD15T9D4v4rVuPNKwvRrn56wSktJ8QohO1Mtxd0XA47TStGHFjbfZW/
Mc0uJiiyqTcfr4yp78oxkg8ZqPA2GOMB1RyLztGrQj8W1apo4teYO5fQdCE1vyOw
8R0SXkr6Ir2jfPmrrVBD/BAX1RewVw5utsPqSewiotqZFZf8glIbmf9HGpofTX0b
1bPRs3OIFGhAuOuTmyizEoSV1FcesPmd5BLvJYIoZr8EglnNApKhaUTkIkQLa8VT
sLmQTO/pyEJkhhp5JbZBpR3TBwsyqbYksSrIT+vO5FQA9fgZR70TymI9Zhi0pkjw
3ZgcK/KIKn1mDGBhQdYryNAHQes1Gd9U4IewFM77PWfqALced6QHnZEdvZtcZOL/
RWetEajVhboPLUdHDkWcZBhmrsdz/B283oVHZ0VRX9kNwWVzh4ukTCWhVRSGpTQ3
Vn7FSXQGPtEPxLemQn3LAr+Xa2C8RmPJULnI6lbxWLMhkoMgXTMjX4+lxEF3ZX70
StTxDJTP/kfVYUWSFlvxtSHGAiUrOVdmaUCo8a4Lg+nbvX7UHrqA2XMfuJH3AB7S
QgH+NZXnccGr4vmtMEK1iy0ZkK0NgE1YMXNSe3RgHY4T9a7hXB2LYm/ZRsjb2GVf
qSRiixm58g/j1xX7TZbr7AEwfw==
=VwS3
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aac39103-2068-459f-a9c3-312bc2567b7d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+P9vtJ4oswL9lq6ECKYgTUon0rYrsH7oBnraThUUyrHFQ
AkMtPhu2OZT31CySDsyccAaSYGApvxT1tvuROypbVzNnbZfMpkIdIgfHZS+1fcYt
4NGhlaQp+Cujoq8e160hqM1Ev8xbfxAPllReTWY2Y0jacgDMxwe2LzrmNWKMpOwM
VZ1Htr1H5jVFgzy+2yqayvp1Ex2vFarw1IoSMDFB1xjzueYZhqncMJiyJS56BEzQ
pJ8ZlPhqlaI/gzOjqdRAyVYvhxys+x2k7JvmlReEThHxuzeOqfR/Rcyklcb31RxR
gaOp6JYhcw+q/CLgqB2UKG+aaBzkNCnG1AgJx07r/Yp20LZqOCoXFHMUsJQ1bR93
UZdgG4XRwO+xMukRt3saPZYIVb6byvwEqhLNa2D/bXCJt1wHjKeZAQCFUqxDNjoT
7WIDon/jl53lS2kcuLKzKlUf4BbGC4iZvWrujjla+AJuqZCpy5jTuLBgp2GL1+2a
KsRX+2ajWWRmJNrIQ7CH+/gQXaa4mFGMuNRbZ+S7Z6ZvUFgLgP21qHIrjwTHBU53
nxhbvCa9iUfYXjCWdBDemQzFf9yqX1ypWJslcspgycCOfYhk2drc6hkAAilRY+qG
36QuCL1uCB1lk2XOz9KVvX2OyUfbpohr9pdt09aF6f541yE+p6OhAjyC33zr1xvS
QwEKPTJowN73JMvfDluDBjSwB/k68wfXnak5mtCwWZ3yIlgefBNFJMN04cAseyBg
WxvlgvGZdY8wjGlc3XL+8JuFW74=
=dSsq
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aad65bed-f2f8-44cb-a710-0fd98a97c62b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//TJywvbnp6VdHnJGYCqQqFKcAbgHEwBlWLQrjwAbLX6Qg
NFla3BCx1IhjnlhUd1WXCojEZX6r83LbtE95NO5VQZMSDNyCdNz2RvJyemwUisTi
cbbfv9Td51XuTHRCDioMld7lZWhKj0SZxiQ1bgi4jkjHDb1A6tz8XJhWQF2N96xg
HTg7RdiJHJkZyQ4cirNAOwmqG8soq5HzZgnvqxoePA+tbmgP4Boyyb1IztWRKnjQ
tWuUa7zdJRitlyYkXDN62U0VOhEPakGIsrJmXnRT65LIBwx8vKi317IoUbPbkCWP
pst+RV07r0OBgUp+NtJFv/zA5Y/7gsg+pjfhNjWlLv6moMaxMzjQAcp7AZT01oWu
GJddrYuRezC0x4gijLPfzfo7U3IlTxrYFEj/ZPysz7Fvim11B5ze/ak8Yi1fTIAV
81WNulCv5YLM2I8BNnmTy0pJs6lu+b+JK4vFATxumGoaD6pp2BRzXsiVtbCE6R0q
OepkE+ZzMycNolIfbs7hyTwBtWE6WMYvSme1OQrxBpyMcUiwzVkhyd5zdZyCy03m
RAeAeDS02LTK37v2RdmrwLnBcZnCNkXVnzOGRm3IaFdcJbBpfzNFNc12SivmWuIC
24J+WqbRzb2rvrUi/GAS4yq99S4xmOuzHH3LdlBiz8v9QKJCOxbN8n8phAbp6xDS
PgGH+68dcbw/2qLF8PsH+eUkumEuC3nB0gSXOR3RdM/Ab0BgpI2H8DxU9AKvuZCG
+oqsJsfQCQjmBRqRL+gu
=bwbP
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aba17dab-942b-490d-a6d0-2b2e9beda15d',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9GIoUv9RcFdZKGz7obRu8OKYB33KDJqV7rTfFj8+HVSr5
p9O5+Ut4fxe4l5iRO+NbU3BKf3NrrX7mVFIi41BXcNXHryZKUfhXzzFB9hFrCtt1
rqbZTFjN7QTxdstWDE9IEpd2/XVONkbs7eQcY9Yfj5YBblVlQTzVsRYQ12SPVj7p
RWW079Mq748BWDhTJzvotrxJs9P5L2lOqtyfxOMx2Ovuo+ll9EGbOxvU367JZF6E
j4ineIkS4ut9C6P0XSlQ2D9L4Wyy6sSg+rga54HzVYT/zr6Y/wa6XDzF70JsByd1
cvlJ+LSY/OwssUa/HlfwbeWN95bKG0roE1cQK8/jcNFHPKRguF7AGBzUmgBsNSxY
Qgx4+QD4ghSgbvSN7rSt+4MLxGyqHNMwQW7276Sp88uUhC+6+4aAvqWGELINEEkg
LKoJXVvKpV/crg5H6smKRm4CaEhyMDGnMdF++xigN2ClxDspfim9otpYF3NwMJND
GBkzE+UzFzQrRORQJ5V2IvHepdbnWJgph0ALMjXNcR4DQIMz9DziHu7uz+AtcOz0
W8m7o6IloyHg3dsFFCxnTX5//E1e7vEsewQA8zJQfw2z1hvW2LFfEYftgWRS+Gem
JsxU1q0NY5VzbhKgJdQbbwpZlQMq4heteSmzAgWlc5OVHP7zwq7KCbRO5ImWxfDS
QwHFTZh9QcLJ+fXdwxWn2hnj6K6TH6iGYNPeTVnCKrwyeCl6Y2ThRVsFlHmZ1GkE
dLknJLfKAoC1XSNpdPXh1Hq3X3o=
=cIMp
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'abaeb237-f936-4597-aaee-0bab733c2120',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//YCRrxgglK1ggLcixi/amkvOrgNemTWl38TuiCBXLRCHR
3BONCXlbU2KFHg1UE2ciEQut0NvhIndwQwAyJYeyQ0S1bdT9XliBTUGSjCLioEqq
DOlPeNhQxx0pXI8Z7/6lBQWdhyo2TfMQOfAOjVWv3KJkho1ACZe9bzyOEeuLDvCP
cp3v6QJ3JgQg6blokUdVNkXQdEdmdC6Ud1MQ68YPHS14l08LsumBlVitwoSYnnMc
y3Prnej2Oz4Z03/7IhMRES4kKPpp2nEVEwteXjwXZn41VLqTSaM/gE8TIkyqs5FT
FI9SsRn2rhfmJklkglEndCTEjXHMv5QUi3m7KNUMucEGwnVF46wvSQu/Tk9M8b3i
EtWCdmSWr8Au7M88avXPwjM/ZESaxIJ6MtlpVmB045B4OfK0HNfwrl3JgVMyJDhH
37d4t6Dq8oqHl5RaH/0gPCHmph0PXbZ3eWCSRhWL2eBow8NwaIL7vzWPfcoPpsOb
OLPlmhAbnfeegssntPeteq5Uaan/J2Sw5Ri1rv5nnJGdrFbRviACK0jnxo7kOADv
QBH+b6s6CU1+x7kTXOS1XPPNUTSzypn0IMbOhs0NWLUPL+kH97ZZE+2luETIUPy2
VpssXxIlNswdvx+pZY8r1jsMd0Md7amLC7VtGwzRzAdQkaqmuk37w66lgpgrgszS
PwEKxbdCGKPp6sXhx+mw3NgqzKXFGz2LyeLCiUUeFWTj4ivpjkdGNESsYOOf7Vro
2aB7+Q0u3L+65tdUdg2Idw==
=jkOM
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ad452b8d-ec24-48d1-ab6c-50d5601b2861',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8CJW1h/ioIfKqdUtBr8Nr9WFiG7raBwCQYWtsMbQECC6i
fggPybEq1qmXCsSgABYAisaki4747K5/QGaG2iM3CM9selPWG41JjiskI9TbPXkZ
d9SKJxGXGzlcY1CotEjNs3hT5i/E6LR/fzZy9LM8gojrVUpAs8C2FATPAXVQrK6u
P4iQ+ElchkStcQaSmJUV6buw9NNxCMh3b4tt34QX4+gybh/bu96g2nf7zqhTSltA
s+QjdLFkffHmlUK+I63nHdW+xa1AXON8CgvmY7MOW/m/p+v9o6Yjphliod13D9r6
p9qi0oWygLVLN/rl5F9ydiGtTqpON2IAxd+0y3FqovjFWYqybq30KaY7i5m98peu
A8Ef0UgdauombdvQNW0584/1p4jyuLZdW3A14DZeoAitc9FgcAjENufewaPJx4mh
GXJnySjs9sI/Z1jibSLC6tox0b0phUKLQwkryHfpWv3/JB3xh7pwLUdRlky1TSsJ
O13u4FC1APjwh0072H964roo6WtkyBSExTbAnSopcvw+9TcJTTXKhlbFtLXtt2nL
Lkf9dGGugAKqUaE8akQJjh/brQs1do2MhVO51aEWmpCCPhPXQf2BSZv1u0ctZP5A
GFgoa4HdrNTtg0t9wmjMjZBKQw7id/IQIyQiFvs1KSOJtjXkKBojulL3yAQwDDHS
QAGIxaCH8g5skUStO1nmd98YwPwU20Ekg9jcLylomIezT2mcqf7wED8Hsu2i/Nfl
ptuVH7hQlEZAnePFrxXxSOI=
=MeST
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ad60a921-b23a-47e7-aa53-e56ec65b4c2c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//UrpeU4PXDgAO+AoduqYGCmS6hcYyUhV5wROVRyRb4bBg
IVKd+tCnCl6qZbYg5I3Y8DF3kWOtggL5Fo6R3giaUIk5eVEsm80jVXh6yUQ9AHrM
sNe9OazeftJZWvDCbyNOcZN707lfip0mYw+Akp06EcBw7WoQ++YnsSDI7+t+Ua9v
sGOLFRUbX065tYtG5l1MhgqyNWpEdyr528AhHG7kM+wpetBti7mz5h2MVTzpXGww
y78d4OAWvYJImq02iB0aJSVKbx5TTvNQ6YHDPv7Y47X8KCiEFLwYGoNC0fjkZiAk
m5whoGJUYIk7qd6NNfBuYBFB32AS66kaZyZTtMZ/iaTqvFA+6P2e8XrAspDn90kS
SyLP9eEggeMAO9UjYHBbKFziuh37N9D6zI+Rc4KAhVsFEjYY1H4nUzkPQ+Hjuj3C
rkJXyRx2Dvx5KwictockS77WwMJuQ+zOmIxtl2Rlxx3yhqlPox5g8zsfYkfdXq+4
mdCZYQIVE02hv86UZr64XwnqgH01OZfinEqZQzjKYKHtyLYFWVIBLCHOE5bqTfXU
Q5trWwAcTw0MBvZd1RGXnvednEqPwdGzomhPCuerXZrdBetlOX9KlDEIoQUGLWOL
j1ABzKZgzuFm+DXRbDsYqjdLJ8GcWajDCUYfpRev0qGtnG1dnaj8GslalM/8KsHS
QQGDMua6ft0molDaX93zEVINjsWjVHU7OlKbVe9amJNz/sN8YffINh96ba3EIcW0
11VNyZ5HMH1B+iUHeO7rM+FA
=dNuM
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af2c91d1-87fa-4212-a246-62991ee1c082',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAlVDGk/YcFOeQn3g4dZ3aYzo5YaOu8Dp9bgPrv5olmTCC
jxO4NzdqujUcfF/RaC53yLASZ8vivQ/+xsg0776s8YXHTnychcCMHPBiCQIcXSo3
6m0DSDUukpN/U2XQLsZ3mAPPDyp60MGYSaAZnkVR6FDk4bnoVCgZE6VrmQOi8aGs
6eTUC+FJTkPOZ9dvq5EDyoJEKEygxkgAtIOxKbusCse0NI14QEVWqORI+ccXubC/
7hNqnlXOwM1TwoRgAZ9O1Ek+3UgTr8pbimEiwfOtPiDLIAuk245wD7KVPpNsUMlm
vNh7GKAYK89Um86cmHS2cVePkl1iFXjDLh7l0T2LT0J6kiHFXDSKdZWkwA1J8f3+
BtPMLtGyd29CMnnoTIFwDxrXN4jFVxCYaUsISIO1fdtw65M/9BLl+nx4giT3MW0t
CkmLs/N0By3EmfpDwt6k6x4OwcLLvWh/eDADPBgxzNARKgT+EtSugiYyQDwiJnze
YZ4pVyhon2teRp5u1Ezx5K1vp3ADi6JEVH2R4scSXEEKUNS9bsD/P9kFiM97dPEP
3PFyHatIYi09cZiexcsg9e5vNMOiCz1k9HDqmCLTwqyJXbUqZyGPcjbYgClFir2L
DsK1gRDOexHlNY+OFccPpL4dDL71bR9aen1pEufug/dzb/feiD07xAcHQe72T4jS
QwF0Z5JSjXsFSrOhWdER6R2JCxIcjT74V55M3X+hWJwITqXqzLR0hF3emMI//+jF
1X+UGjA20/5+0jtUDIPobnYI3kw=
=97ZO
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b0d8e04e-8713-4b3e-a7bd-40266fe56e7e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAxNVLNKWp2uiNbBOA8E08F3rOO462g1Du8Gqs/TvmV2uy
oOF64FcTzxE/TNRh7w/0t5jAoQnhrB2B3NmJZAPL96ye5CVl0KCTEOKXlCGWGU9R
EjPPEESOo6neQlEF3gL0vkBoBlpSvTpWAaPpuSjcj0wjYLybMbP0raDPVyELfJfr
M1Rh1l4Xqt8WUQHY0Ygj743EZOfO55ret7TklHFI42LfNxQ0hC0aCojqLhj15QMl
l0iOhqGo3yPQcgIAoKI7bTLh0EibWjzigz4UVgI9i3Sd17XqV620EtTghoNPvnOy
8s8nrbPF2v33/HqtTYmaJn49R8BTn2ekvc3PB5lU6w1HWoDKQKoLer/43yHUKOIs
Ye2yWzy2YAL7ouEQab44SFNBF1DYB2yu13sVFq93jH9lKsHnhmXl+IIa98Hd4v4V
3VD2uE2qOY7dCkf4hBhKaYBkP2de49L3FdNbiI/BQQLHrSTayexn/dhavJenl9Tp
syWbA+EffH85RwkF89ASPq5NH0WOJdgtY8KzeFVRDxN6yIUX2FZdsEdiVCIYYrTP
s1rtFRkqd0Vk6UU35hnGo+xVvlXiTQuSfbrmabJml/p1rd5cmmmnPd/nn8Ajm3Zi
kJ/azIFIrHrU8kS3fJ9HTlXKyM3uA7HJBEUGJ5/8dgK5EFcKmMbTY9w/rMavPgfS
UgElQMWsuPBw3qGDzQuHcVaWLZnUsrlXOIZFUWuOzSL34UIxidi+pVAqM/9MWtpO
VHYddVW2TfaeJ0wiyNtELsYAZGaaL6rLinwC7LPOjb+UMbY=
=shUP
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b25907d0-204c-4b1d-a58f-e9a75389c053',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+I/W+mxgkboVy5NE0svxIFlvWrSWgcDkS9neKKyL5ITF2
FzvJZhd0AB+RcOHBlbgyfV569Azq/Pj1t7W4NrxmbjO5p0QSZP1x0ebqBCpj5fAI
Ux2N/DnPwr301KEhqUSgFB49t5jUFNQopH7fHe6fmlFPtmdC4PmcN0OrchvgImxc
QBSesAPcw+4iPdxTrZMOY+K4SqQIFfYHDlRA6ta00v+ytR605TVFhCj2Dwuo53k9
ZQlx3gZSGyGlucEAvRZ6v/XOKqpRPr8nprcsplwrqviLAJLgVh78bfrpdKMKMnC2
LnWFUsQUvWrEuqxvibWGLs8gwqTsbxOd7rkaZBfyNJpi7Qdvow7QxkxEgij06Hbb
+vY3xByG75yBe3nqCm9oAZZZAklZFqWfSagVjmxiqplX/9figcJouCMPcyez/rXg
TH8FWVPI3oT59gS/iP+7LTtrxoi1eJzHxcGGtuWYTP6no6aMRymMTVmR0j/mTAr4
IuOL1HU7YpPjp+Yo1pVMH7jfK2RN2uYn9EH899B0W2PdSu5Cx6Ql5Ffg1shrffEZ
dcKvHxeXiP1PjWeburNHG4nCrifkLQl3wtm1PzKMGVKN1mxJ/e1021TwJBdKXPmE
PibLgXEn68ts1mPw0pVi9yScT/NH8xPWZcPcMst2AO01F+DBTmJ28HUFxKhNOczS
PgFoREpRf0E70n5h+J0qE978t4n4Lg6f64EsqS511ivFoxDCNsX4fg2P2Z/Qb0QZ
RWsByBuRdblcrrxef1u/
=BMrl
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b7d3ac79-e7eb-43fb-af12-2164ec272521',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//ZHnbHu2r99wi0viFtxNn9AdFj0563d3LuhUbQdRUmfbw
7O6TfZTgsMytB4Scnq/zm4A7WAL/5dmU87Q7teG8jB5/jcMv5Dimzkkkx36CpKnk
sYs/j0hPunJiVlOq7BLeWTFTEyTsXOfoTSGJpO/EhVhzsuU7lcAjlSYmQWfuCVz/
Lrn2gEff0piIkftkpjnlAPA5Mi2c2k37f3HN2YVLRndPc+NQeQo1IDiVC8j3ie8/
wImdzgemh8h1fS8atO9yUqMyPFAhxq/t3HV/K7H/1AfFl1+qIeliG3KlEEFTt/ej
E1Qb0gYn8dSs3s3sc6yBNdf/IB9t5iOuxcONmXCmbeyIdIpOcPxMP/innfIPheUw
rab4MZZJ7TPuCKLLpNXluxLIqbFDMKJBAMs8XY2UIr+cPHoQaxZAOubVhShuMueY
gWipwDd6lGu6WI6Rb+CWFGi9jUewfEPWOq2FjQxD/Uo697kTSmiYd3r+Zi9Sx0p+
fJH93jFMRPthjOW81d68kAJeYZMARJK+rfcEjNKJ1xNPkXUWXumIWDO9h2WQdVo4
K2bRFhm2UfduRpovDQJ6spbZNBhlS7OVfeR5wiKCDjjQzqopp7cjKmNs9T0uHolQ
GemwSB7yrSC/bO/dDAQ7miPjOexahDGyItj6Qj9/Nz5kwhHewxgFyDyt2Q+dMYbS
QAE0Poc8DTjnwIf0dMF6M8rZtCTPHyuEhvYdRthHBZdPz/Wy+KQ7MFnXBbC8e98o
OMq8aCrbUf72vThG0A3kr/o=
=jTYA
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b7e7e200-2783-4c40-a545-fc57f162575c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+Ny5NuREcJQ8nI4/nErz5e7kSqBaC+5j5aiU/dbWO/gge
E2pogrjflzCnI24/0I5wNsNiO6unkesBn7hFOcDgb0JGH/sMKsgOXUes4X+TkZmh
PgUqbmYL++2W2ZJ7/6q7LjojysovzAnREPBi8+eBD/Ynjyk+GFk+Ul06JezLAe6B
pgXFIPU6eSBDwI6hHh7OaQ4xV1IYMmHArRfCwhesbFEP33RyxI48WR4FlrukOqLs
mL92QQLznI0RFKaNTQXcDO5JraFO4tkHSw6o6OP/hVWyUu6mJ6yCnhyPpT/gK2ns
hfiouRn5lEUbA0hFLOHGREvCAeBmZXTcH5hAjQUGHjifkIUY+v0gcJ4PMADV7t36
zBR+XlekijVYPqX1qW2VxrjKXuMqrDoWUqUSzuQwH/6+ROCH9IsG96BpXu0sbpu0
b2v8CxXjnIRkwD5h/KBfH2VL4lC5Ijt+sPAK5pV5kL5mhhGwNTs8mp7xMyDjAhxs
fwsUFHLMA90UE+jqb1lr7035I0DaUsQjrj16UZITfAIi+nmG6FESAAwkLOJ53be2
KgKMaT/kpvExzLZtrebxSa9hy07L5XL5QmkFfTdAfkX5EnKEdniXGEC/bDfyHiMJ
lbvlt/vwwERV+hAN+fC0faCHsbU7VXy/MZalPmdp7rNX2k1Jzio32NsnWlUTLJvS
QwEI6DQbaRyrXH//nvAoVPqksE/fndxTEh3nbWQ+sPu6j1HqL3RtFG/FW5om6IFN
OPpqKytpNo2QQwq9PgvDMOcJGzA=
=D5bK
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b8cdf38b-898f-457c-a089-0d682f81cb2c',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf+Os+I5s0sVLaeho9nhCjx+qA+odKmxe7tZXhvKkn7/iQ/
K61Rj3cwf8wCDz0M27S0OXJfGLJ/q7iXW9l7kIuGUw7p4u83/oLPEqidaomTjEoC
3TjFI11g7qz0UNJC0cFjmzuOVgTfQcUHEsVuhwhSEUiLGru8OBgo8v47X8Ybd5CY
xh4O3sbS4g8DXX8B/wAJ1QDux0c70FulMHt9vNS3OWCW1zg7ekYqCv67GuUzT+me
5g0YB6IL/36pdndX95fDgEAoAbZBrA5Attab3JGaZTIGv0e0Xd9ct+nUn6UACif7
/KXsxzd76HmjnZt10fKIfn8gKsatr/yH2UbjJo7FYNJAAc/HCf8/XdNt60Hbhv8q
/99yGKgBMTkYcZvxMDugEcxmDeo+LcVbWtaqHhGa2oAMLPgOWbm1QsdfTb869X5x
HQ==
=QUwE
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b941e873-e5d0-4578-a8ee-aa5ed3c68528',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/Xy0s+emKgo1TCp/dMciVJNC+T96/iDVcOeWOCV4zptGv
16YctjNsULOgSyQ1RRzC756QyGLERgvPvCVTCYNQGYipfG7C6LpUb/2NL/Zincna
azg0F9bjhaFeYbKfTf+Ee1N/ocwViaf3kON1X+DO/EZn+aEFt+PzCivn68EqwJVf
OB+TZQ9DyxKAPxwfV3fOLLzzJIBR8oz7Vh8Qi4DSGyRC0F3FxcoK1zxkscmj2BiE
SWqQHsCyJq8NA82otfrHWn1IiG+n+u3rp4ekE5g+i8rtZmxtUaXj9b5vtEBaPIQY
dDcUmJ00eLMxmov7vlGv+B4whEh2PYCKo70/fh86ZNJCASEQzevjr/i2G9H39hIv
AS9y8TzORT52tGmoK41rxZ/WlqsfOccQf4YkTFE1taHP4FlbCeAQ48Q/tqPEwANM
7uDF
=HBCB
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b9e0d8c0-4e03-407a-a427-fcfa51425cfd',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAjvM+9IMVDa31A81v4lOn/MNMVQYiim8rC73xdlmRoCkW
L/Q3A6IGKLsjPfoSQk2W7zLgBBBFvhgN1DIb6j4qB/Qn7WAIBrxxia6SL5NOGuge
e93lKpvt/NTtYi6zUXRcEUE8kKpuCRjoMUeXsVawLhRKYHXbnkyPOHPBaupEyHhm
pnWeh4xd6vhTDV0sUsUS5sAPIj2GGu+xU8ubtn704/VhVh7Ov+koY3a6zq3lXa/h
TwKSZFyxMRU3TMuO854jbj/iDMpouAHgnw9U/mTYLOqdS923AQvYzTMvTfl10TYJ
fOrSfpgmg4jwu1gck1TbdTlgSLFyweVewXwCQGrhQg0jQZkdhw3PTWzGW4uG4dPR
ai+nbqjPttUi7f/uJ8Q5EZHZy4Ohjv5NJHJ6OyVtsssr4oFAaYT4kqm8bseB00b1
12LK2epyZuiPeKxifC+k7C28JtT0OPrXmJYq2V3xFmtsOauUjAkISKFQKdT2b+4s
EkUkd6JMF90I+jdpPsM4NxCnkaQzgdnkbJdItAoqTl42FxLLmrCXBlGge0wPwt3l
irDajrZjcKHWlCchlVAYJbfLEfOZqvEy5D1VshAKAyx/xob/AN58C+s1XzA9ZxWq
8jk7hrqY7FTTP4TMZcbUQ9RHw/BjVKfnCvHipA8zZHuIZ+tXU3h2jMSv3ExKbZzS
QwEFdIjpzk+2wW1fGqVO/dAI0BZ8LanneFblB/4AgMzfp2Y73KcP7fWirtyrdJYV
C8tDZhXVIcbr+PnoYE/D25Z/c4Q=
=zekL
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ba933277-8f4c-4bb6-ac65-97bd0b7ca2dd',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//YhUSLX98pgfJsq4eiBUtReQ38fvkxgSjlGxFi/5onyyz
mfHbn66vKOo6HVLFLVvbLNxbEsP6JY7KjrzEgMPpZw35uyjxQwilolEF5Aq3ixKH
uDiE/XQpKhPaDPD0mV3W7XZUXPaSdelVJ8Lu92FZ/vK1kmozXi4omb3136tRc0qf
39qAef9zPb+1wsgApjKmlkWRE3Wktw8NorFYj05aTRzAN+TqQbJ275YOTWNYN9uv
sSlm58VXKCHx6/QoNg62Wtce4t4wQfMJpPABt9rON8xJfglIsdSJwMf1N2oljVDm
5cvZlM8NYvXMzT7qLhRwXR9fjCi1sO8YSom2Fswf9ibibfToCpdiuYbkTlFoY2PF
wmhQsVLBMnTkPlr7bP5BvkqR6s03eJe4RAfCmoPzbhvu6Rhlie8mmD8L/7auPlbM
YjvqKdkN8U1NDekOf6b20ImPHjFqfETxCuDZUc+NNsZecpijIrQhm7Gh52al0mFH
SVnnVHqHzKODOPXbUSka9JT5GacUtVZqnPm8XLAIGxJmexJJDsEe2/BYHZLhgSg+
eUtxKX6lT9QmICIvVbOLw0hY5mfzAUH0pTCovOFqmp9eQlYnsp/Ont0B+bCO8vKy
5xx/973Wr9R4QWK3JX+SvBcjzw4znVtblD/g4O1AB3PtGt7rPmosADliN11UXKbS
QAFxnssytXfNkgxOqjPKVlddz+0X1XRj4DatGYUnWVAHBodIEYj+I/A8/AREmXQX
e6f4v0m1soDENIF0PWdRkTA=
=6yFb
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bb33d94d-e1c0-4e51-a78a-cdf8943c44eb',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+JTB7ORvTY/nXOMJbYHwLiANcP61X5ajhqrfvKCdw0Ap6
Zft1VmvrgkZjhKp2pr0Xx08U8PrUfBQwF+x+eVSqNZ+0y12p1pe1h/Q659XPgtss
9iUIQ4P2wbcf5Zbms45/ouCHpTe6iSeSuVMyDy/blYLtFWjE+0b358j08hK0b+4V
gLDwMm5nXKSwTB4ZTIRN6MHw9LoH5WswInOBz/Ws0xpTgZU/KjAIJTdS7+D8gV5G
B/HsqfHp8ZTouXWBEfKUtxt9ICJ+pN3DiEwYYX4o7gkg0+rFKUlvkZDsRznn/QIs
01JBhpSZa1dVKiY7I9VxC/NNQn2Fmw9HqXj8OPPgJ7SqF6PatcuhtFF2+vZBdHmB
QrYLtRjh9vdO1sZYXU9ZOo3OOgqzl+jm21/tv4w88i/mdJ6m5EDgRJBk2iEF1659
ebyX2HMlGPfvwOARq0p5hwkPK7e5wwU3sTGzyrKicNYmAKFBGRjm0O8v1wc5fz44
bVx1qdYIXzrcZmK+HWpqU+sYr1UPJui9d/ehK71zN3B4CtZkyTQ4MD+K/1Dqshm+
k6RAL82t515Xf1TzoRuagGOqYtVEPpovrNqUCPlo/+4blhfPMxH9kE1evmXNRPLI
2UGOz+yomkAfRRiBOFnc5yfdCMXFrIuAyJZYGPFn7gnhhxY8sXuHEVLpZXJUtBfS
QAFGxanVxFANFeAS/Sz8tFptlhDnaz2VMfIJa9Ir0hSRU3M0TUZTGdhoswfb6HPk
+f2QgADTgYDP3ouYxhcml/Q=
=EHHF
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bb97f18c-148b-4b00-a97b-dd8bb81351b5',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9GO7h86fb0d/eHeCm1SsLsk6b7KvJBEIzE3AhjckpQhcx
ut36oOhwHARwwaVxvvTt05u7xq9ivM13EveilewHf/fAOySmaI2bcf7r6+DM0DKe
jHn4lJxR85PAWoULHqMYyo57wdwA4ojOVl8I5OtthW6HSj+Xw4XeVM548/GsNBba
8YHTGIZAJjhRTSsDa0LGKNCbNYN2GwbajD7FMvJdztBxCWt1DygDSxe9ukaT7M1Q
ItfKUxB5k1L+VrKFpouBG/y0QY3b/HmDNl3gKMqumS2xfn0i7lvFHCFmIz1pmhnx
IDlMoGsvQPESacxWNhSHIqsEKxHlIYj7qQqrT7ZkXahAOzKTOHhGR4BU6uCuLqSC
WwtVO/LBbhDorUz5Otx/uWJVpyWephCrpWNG3fqPXk2S9OyUok1/o6qInLpwtIk9
wwfQEMZwmyq5Ma8AuE+eqVox9zIqsR+ozkadIBdw38JHOLJ7Yvpg7nTtsNBQgwMZ
yxGYu6yXxlLAxCf4LEDIxTqJ0qmUluENTQ/BXl5NTSNaCo87E9dXH3ywVlrLj+Tf
TmHAuQ1TiZnPwUcGwdCahgQO2F2cvL1lIfOekutEUrhlc6cqdLSx2D2hhJWVxgDr
efXuLLDqXTRVnjHkYVjLOXwXY4B7p7E58bU7g3XFI3gY7/nRpOBYJTUPUqlPKZjS
PwEtE6B4S7HSi1Hc708obngLNwcwuWzeSKkqi+s3QNpV7nM55+5RPIKcseP4AERg
drGNSEJ+GzDrU74D5031zg==
=rG+C
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bbc03140-8883-4c6b-a4c6-593a187df115',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/SoHZE9CiovFkLf4fY+FL3hXtWv3/zunnaKgfiVWz/imk
Y0LlafZC8/7b7w9ImTiGHOulrTKJFIdAxa410YsErVwkhQYudWs8k+ZPPtHZi1It
wGpXSW4i45pEpTJajn9eb1Q3EsS0wCntA6Y8HFoWfU6ZsV33T63uMu/TWwu6Zq9v
0G2BciKRPdaOgSxFO6UKJErVA3rI+SujoriPXhlxe5AVPvndza1oEkaXw/c6UPDM
0RKrRLKsQgaQrvpjpoEXk1WnmOc3yAnDdMiUz2KI8qI+YfP3yMOFCSoYBBUnfuqP
IXvHg5ilFqYrqMO4w0rHIEDSMt20qXI2ImL6ad2Ro9JDAWx6G3bFOJpR0SAqyaMG
kbl/gW2w3+NZ2BifQQeJUmgAvM4HJAvDaaFVRGgDU4loQ7Qp/pMi1DoHSaHYpHoH
9tCQQQ==
=l+ja
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bbc43dcf-0193-4191-adfe-fa2621b2a8c9',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAApl2vOb+XwTFyHDrNwB6bKFElOGjP20y6GaZae7Qidgg0
LmPUsaiUlHWM7HtsSBnYjLb/3Yza7QMYhy2rP0L+xAKPhaxwAXOdxyq0LGdvfCDn
K3u6PgcQSiDAatAS91LoJknSk1Vz4E+Z0iQYAy+o2xMvpcj+ZSQKd+JVD+uxclK+
h1GU7KA/coh+s3/63sS/oPLcLDjpHYql/L4MFzkUqVwdeNc/uhn4R4uTARRP8vH7
dx95eVILDCON4KzFU6YRCFPK1sIq4Nerv8JU6MO3wofqrudD/4E9Lm2e1M6b56fS
zkmC0F31uUfypYTfPuDU/IjygKu8iJQEQSDk32DejXd5jF5iVwSuTeTn5XXfKc1c
IFYkIsUKNC8mpxzF3pL9RyTk5NRzNHvAepCiEBjwUmTzhhBlBjBL9jEbJsES0yz6
TijUqPcM1fAb5u7XAcJxOou0Vzb+B95W6Cla/tAiDjAgc1CuCYb5Mte8vC/jf+x4
HMXNx4GGjmQJgRakkZ7/fDAl5ufS1tgLRaz+NdF61Wh8BtGtUuoQZv1NU5MktAaB
VXRkdcdbzV3lQ1buRcg3OaHEx+CZf7twcOLOwnOXBLzpZZTf4zYnzJUbOZK985eh
F1CDXL6CjHzN449DsBdxk0Sa/E9AliAxGA/42JgEID0A5m5/xbABLM7I7OsjIbfS
QQHT2ykpej7yAFCkklhOteSi0vdSWYOpHjXzfdzIft9Qim4asPU1qEr+276JQXA/
Vh+WJi6cVrOZHgIqfA2Nl+2K
=2tOd
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bc513ed5-9756-4502-a1b5-4df67083836a',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+PN7whBhAQ43SU0c6lS+r5O1/4yzUsr7mlwaNnrq7C9Tg
REIViszfu6VuJMjSgjkDAJ1YquyZ0LcQYoXmj5OSw4/W6NXEKD5Nrthe+fRczMoL
ZZK4z0owGqQQqT7doJMNFwUPwzPI/8GIk8hdHUP17UXvnYT9RJ/ozORYfW1FYkSF
Aa12ZCGUuIHiqG2W9KLIIYINdjjFJhF9qTSxNko1lbMrM3zhBpAky7Y8g4rSAtIM
V+NUKV7vKw8jvdyA/IYgg3+tbBL4rbmlozrUUiSmu+3/E0s5rUnW48+YU/cIQo1Q
xmKqwkjJWC/pJogMlG9bPWid/ovVPJs9CNcODw+UQp1eu0Tu4vc78EzlBSFKcVaO
A6afVi00N3iurqB6IzyCHcwNFCFsKb4ZizN+95Hyh7juYcdbev2m2TPfijfUMeLj
l/9i2INHiyu4QGTl+9FkvUqXBxJVDjBVIQZWnSQ7X1JhoisC4Cm/nTOl1foEqAMZ
1HthKwj5qictxkIlx9IEAkeDW1zBUUSRPfksc0y6PGjuxDbGqCC00sdqwnNAztaF
CDlLQBmmClUE4TjPLzzHaUtHQta5DijNsaz/TPZINV3AO0bY1JIMV8+81UAVt4xt
js0Bqhjlr/Wez1fOZ0wCvp5I/AKdazFYPXShdBuhxr2hVWN/WE3m10E6jdS3fD3S
QAHV1vDzk/p+gkwjv08pS9EmQyJcHL3wGqcibC9CR63Bi9c478GveW7Ew7epY734
KkqgXhBnkbMArYFGP1awimA=
=yweN
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c0072c73-3bea-4521-a399-59335d549e97',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAmHjbDd8PHPXIZDiunl2GNfvxim2bllf1bCpkst8YEX94
4zyQqKX3XLZSqwUy/ZWRHhsUulKmZr1e+DdGD254YjpO59wpNwH/emqphrHsWU3P
mtWSvq7oqSlWosU68OgDBOkWgvFwUndX8cvMmFOV9Tg4KyORHh6PQk3zquQ0c0gY
EexBy5NypVtt4xHHoXyBHttRIPlumlGSs/2B1gudapYY2RXk9pMUq4WVhRILKCSB
OnH9gojioUVhhc5iTkMZb4f0Eezqohf05spaznDrOEu1yAHIiprhwjcEYkfPv5n4
nfm+M9HY/2/vGGNiC3n1+kdv717j+Cfu51IkQZciYBs2pYNqyAucCF46jXzZbzgU
5T8ORZgOPU+IC6ujewEAlI3M/4GOFiwIFfLufW4wTRy1O626Wxa1fTpKNChYoa5W
JtYnsHqpaKfKcsGe/ax0qDoUx9LCz21deIZqsfCBzSMBLaacmsOtGMLWG6F5QC61
7cwt+22nLvc0jCLL0YhSdIDbTcXfyEGQvJ9iAmC+E972I0QMdGG5CqEN6MJES1iz
jnIcYaKHH7otNBR2Nmm/Ew0C8r13jHKGZ/UX7t3NNtf7mbOIDIBTPjq8OLR1PGal
BOovoaSfIMHlWFKix8XHsTawnThAYKsXFvBmInDwc6J7YF77ic6cs18XFJcpzkHS
QQHwinxS3R+ZJwHuFuMMCxUwA+dCjkEpTyNJFRc/JGRNDtAqipt7iZC5kU5JM8Vy
oxH7XxR70KuKvojlmNE1uRO6
=oqCg
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c276481f-0fae-4995-ad32-ca58b28e3c88',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAlJ5SkGzJgHxtnsv79rDEodtDI6FDCYF0/AOLY+pj6DRD
3plZBIi0+RO+ae1BV2WrEO5qxelJbk2wsMIx3yjeBxr8iUWTlK6TEpj81uY5n2ju
ipisOR29/i6tRapEa6sE6ctdak4c9W+Xu/RqDh2rAfChvhCk3dz039zU7bWP9yBj
/YaL/IbA1/l8zmJgX2JXitXBTGP6xSE/j4msWHm4TmcpRwDvRIVz7Cak6/mwiI14
8/aatuyB7nNjeXUGYghaKLTjWAWxPgc239FE2a88CTUMzj2oaIbHP/w7r7RNc3xT
at51cxXA7Va4Y9PsRUI6nHoSTh8NYMbLAPW233SWMbVEfeVGJvQ4rlcyHPwNV9OA
Gu6eD3yi4kVvM4ePL9j/L3y7chODZRTqJL4eZR8nLCAOu2pHwX/r5syHp7sP58qm
zwt4bVxbMF/ar533PA/kPrMbopUKaGjDH0es42Vv+Ee+OYe6HB6mpV77xNLE6qdx
oBOxDjecyqMOWTPPZmWmUZvKfKMOf2MDxEjiodYxb4dAmUXurdXe7y/HcnPZbwoI
IMDQ2LhfHhXBFa95Dxu200HAM3Sv5CGq4AlH8xj8mIUApbA5/MuY73J3ybqlLqGZ
s2rPEBY9Ni6B9dgRTgYoyeS/0zy8zlwS023YveGAIPKQB0O3PBXY7qz6WFaD5FLS
PwHFURCRBG2Upx7zQarl8MPg4rQWtvWM23xxl71+3nBuCPLToB/Wvdt5MsXWl13b
y0xknvSiUbMDsHHA10lO6Q==
=4wJb
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c480ca10-2a7a-40c4-aeea-a002c179027b',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/ZLcsKk1BaoPwDvEyq9qURVuAmqyJf7aHVCeojMGJaju9
dWVVbbqqtoTqU5Lssl6LjiD0rCMrFCBjyyxlFe7pRIW5KTGZrr/PdDAplp3ACpku
gBw/Rl/3UXMBkPpyoT9tA/+5TOD1S8tZb9pE+wCPRSahyeOopNFVCCIp/FXJUPcE
c4+EokgjrpECDT9TuYB6hqHtQRuMf5gzj/RSMME2FJnUBr6uoniIqBAixkaAF5BG
BEer03NCsCKYtEDo97EaS+DzE/1L1CTjU8Ax+yki1GPHufiR9kpuZHHXI6EEJA+R
bHOQPU9zU5ZvcI3buFrmcwiPAduGuBVZwIS+hurw6dJBAfVT0JzPCc4/vDuhwK8c
nPZa7AvoLR5fe0LkW53MpLxmWOFoN3uQI0SiPCkPlgrnRemhvcwcBlMczgkcfzq2
Ltk=
=wqPI
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c58dd076-c7c0-43e1-a1e4-9055c8daa4c0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+OIX3d0Z9QDRxgWAF8nsKo2GT2Rmb/2dVepbOTTk4M94b
QY6VDg4XCwhfSoMbyKBJvOd6FyfcrgTnyZ+VNUXhVg4DxC1dG2WOuTvt2a8SaPJS
eGYhdEVgSl257LabT3p+TBoCpfXj9JOTm5JBjG50UnCvnK/mzLdXwwj4xYaiUR9Z
BmJcxst21Cm3HbgzoGDPrhv6Wq9zAym3dDHZZ7Otum9nQn0JWDv5pGqzlsvi6wnk
VZalMDBQtW3L6KzXa10nNjWxtwgxRe0IAjIYQLe7rL12zzBGc4dX5t8nwPReRwwk
JFXtWMmXck5OE97s1H3rzw1CJbd9fGEDBYPS5cy1Ln3mwpPbk1f9y2PpD6FG4722
2n4NoaMMcr9k2quppXCZFhExNrSiY9dK4wa76136iAZwk9MbZca6ioTPIALtQNDg
GQYaxSYQgelgTQQYpYh2du8pDU9MN70WhdfRfY7hqJ7OV1WqWR6CvUFkN0KLfLfX
/hIE0hpBHywD4gl0cWzgrF5RtO8SDhnUTHsCXE4LxpphX3pfIavspMrAleKIgYym
PgpTn7DuB4lUJwBVMQaJCW0Sl+t0tIq+F3mKzflXcrsAQepDZjmEMaKvNNLtb/mo
T/FJlTE9rB7E01RTHrlQgeWL5hexrAuYmv6yyPqhAyaEoimHXqBjnDFgS0or2KjS
QwFlLUPFo4nO7NMB9ZqfC33n8C27ru3d7VXRjN2Nwl4Y6c1GcClz0ch0U+2d2/9a
ThITJ8UMlpHx+X7IT8ohtJQJJQ4=
=zRuY
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c6c796d3-41df-49ab-af68-45403338cfd6',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf9HTh7mKWI5Z9jewyq5mpsfxp28VFGFG0QWF4D+PGoLLVw
tX27C4hDyuFXRyBL9CHxnuS1izzoyyrbt2V1h+XNb12EmXZbt/DNeDqlkhugi7oS
guEmnUODHqYWWV9vo05adRt18ej6vHOlkSRVZI1id8rHMze+v/Ax+7UWxSQPibUf
7UAPrifKzAGRa5+GNDSqMLhxlW5lfzYWa6OPrutLMWByFIcz3sQV/JoX+wJeFDS2
CLWv1k9goEGopKnEr6AhfuNp77IMCgrM57UtmJZ71ONu8d25Jjhc9pzkaDV/PvhZ
TTvK1oPLN2V0RI/Xpp7HRY7U7oucyA7xu/NOR3oacNJDAXadYnU+0oodtcvlUyMY
hwkqm7PGZKRK1hv8X56UAgMOb4yo5+pFOOdeAl1DdkKIYt+sgmBpQnaGF/r1sGUM
EFUS8w==
=SpvN
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c8d91691-381c-458e-aabf-e1fb60363c39',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//ToISjym+ukDyGKe0FSDNxrZy/qb4q8bEEdcGdssZelBf
7TpZ2IuUnTvX/W22CI/Sy3SalodcaApJy6O7pJRsntV3UWbNTr67+pzcBP5dBZRp
39twoCVlIHTDTMWqj7rqQzJ0gljwafv0OirITZm1FPUgHH6F1/7d0rCCZCYs8gGQ
EPw6Vlqc3Dv30Gu2pA4riPOJIFMUmbq7fMBjIV6ZqQTfiJgq5E5y4opq6M3O5PeW
7uZ73fLC/4aDa+egCBjQjxP51C+qcBypUhC473H53ChFsUQa1ZBCivQEY+wSBSS0
eEEb629VzwDaYoMD+/n4xivTh8PjMamzSGHQw4fpbflUV5V2qjNwGN6uRM9phrpw
OocrdEcUkU0NDIm6wiQCAQi73hyPwvt7ZpnwFFtiQbX5gInKmQd8zZCV2N9h97Qa
gHzq/gY7ZbqY/MKQB8jrvqAfMF7bCp7AvFctcLoSvLhn3TrxxHvSyXCL/FPeWXWj
rSVxMcgmtGcc9922Fkyq1KOxgZukrmIsmurpCYu+7h+reY7c2/Zq6zpgtOHIZmaG
gE29Szgz/dtdQ5ZCkfc+rWhrxQC44+Sw35YiVi1R3IkAry/YMhyi8SQ+HM9Y42fJ
sFeuA+wK417OkLrgAN8awdiC97CLma6vhZ/nxYzIYIb1OCJ31DhLZ2ROxF0AIk7S
TQF7+0sDTyuPpub0tNUO/GZ+PDQ8eu95rR8lZhuD7kE0LJvQTPByIOt/5pNpmlM2
PBVSiq/hYnrMTxqSAJoQRy5v5kPfB11USWwsYTKg
=jO1R
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c99a038d-a3d0-441f-a3cc-302f2680db14',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+Kc8gPiP+NeujkceTWitGzNyHUVfIRlliWgVBUHgae5kv
D8P1UEknxlDyg+3UsB7IAe58lzbEfP9yt1j+wuraZOKf6AktkJa2wCpwNaSFPN1/
6Ta/zLFv4qafTqnBeus9+rwjyQHS3ZZuiLz+bm7Asg4wEySJ801QLtB1Y/ZpPqiC
yd1NCrb2jSH7hDQqwtWvSkyQCOtBb08A9CuDsQzQBrSzdXeUOyDydDwe2ZqImIEC
BsVhNoDeJ2yV8yVbqBDOAUcG5RyPK+Sho9OceUbtqOOs5RiHcdE01ULIy2Mdu83i
sN6/MTF7UxKvBHZS7TSKleedWqN1HlPNrUcImouoxdFmXm8umBx+fzd3Kxp5y27S
vEqz2V7NlKpjJ2QdWWs3JWl1q7UcK8BXXuTRbjgdt24fBL+vvvT8hAXTSN1PGDyo
sEWL6zeucRrooInA6ykVbKFiCAg0lvOgZC61par8AM5izIo5TYmuQd/C1vhF5Cid
oXlifdGsNpkV+0OGMF7L7YXHt0NXiR3lY+4kvxl761gz5+Hbk0aSSZd27Y8SLs5d
qkCkIV/s4ngk/m0BfDzgN5kMs/jw+5JN8D2yqwr9msZz0F41gs9GrY7O8kFjf1g3
nqaxzcZ79iKWKsI63yIg4evpIuPyiWqdpsWSMl1Uf3JI6SGq1NwcnkOVizBvUsbS
PwEipmd2Aq2DSOKouoDFQAsKj8C4IQjCD1W1hzVwlZUr2AqNRLI4cSNWV6vPE2kj
6eefF9EhzS/Ty+E93Pe0Tw==
=fcm0
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ca5cc755-9bfa-42ce-a1d9-4fed6a6298d8',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//cUxhxcM3tudIOAhgiGBpjBVJ+4E9eGnYquoYBoKQ/iCR
b3GfR2aT2cWyLRHVVN+WE8VY26Uw47UIfVf4yMp62IWNrRslhLkLpwPkj1UnkjZ3
c8KvBKgGlJ4+FwMBxAhTTm6IINL7g9q/emfMsc8bei181gAK6wKt/GOyM9ZKwKxC
wkHM2AXhhvIg9rbtNZeCC3aaK2+jZP6dqc5m6/QmWXgY5tmbR8kYGBOwxRbrf0CN
VuvZzFaIpYvNGck/b7tsz3XSwvjdWo4x+IUgavAcS6NWuwSewBC4+7qzxb7qHTMA
oSYu4/rsi1PHk5rViRnlohItMSg0ES8dbL9Ev/EX3bSQjkVcoYSnGKCYK/a4DYcr
p82xabm6dMybWmrYkClL8RUIIDgkAO3+sOX7St8CByOR5ntebNF8nqQix+XhyNsy
tQPVv1I2i2IPgrS88yV/gD7OUZsMydCF/65XqsFmJdKEQYyxebBiwLL9JmwvHC4T
LVdFwXbHPVEIFKET1sMUYcys4uaXphI/AwcF+xywEMDx5lpa0zFGcX/zkdEtF2Pm
6SvxPEeq4LgT5H8VXXlFVzB0Yw/YM3dkY/TdqVxuC/m1y5D9WeT0AKDybOXbu4qU
xuGSmzAcVvFqqCrzj6HlBN0OR0QF1jL6PoVz3HENqMGJDSlHEsHctrwCodqMnJXS
QQEEF/BQ/IP2s+vdUunYKw1Uz8hiknzxhPOF1/scDc271grGmB9f0kNkTl1AUkYv
Crfp099a9/tdR/Ug03aTmPjz
=IdwJ
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb3f486d-04e0-4536-a26b-2095f96d8fea',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9GwVCJmT6TKSthbgUZkl+9bKkUrFFHRt+XxLCwBIm+IdJ
NmR8dZ2rJZtePg68tiQYzwVRtv1DeE15b2apaY/J6VJZEx/xuOxyFjgrKhxHMgea
wqraIGNlzhT3F6fU9+sUQpgQtDJtbfz5fj8X4yeTa7ks/AwY2xvzF44Zk5Eapd+S
HBp64Q7yU2lneUg6Tu5fMYEEA1SpWJZTRi1nD1fsLpUTBDLsg7i6SfE1qoZCAD5K
LAJcFHaxjuYGMGQuMf7j4gViw+lqhWUx8i4TRLnHiBCao9pdQMpiKw3+HyqxiphN
e9OyGWTJjoqO6c3zmXd09p0j1P0N2B7C7NPaqy9diZKsvoS8nyF76cTkkTGtweSX
wPuC1nOG6Epom/yJoJdGhze2XWaNR/xm8SvQvEDtV+hWiXL+3bsg+lMSiO9A0oIs
9xsrLyBR6rGv7hc/JeAW3eqlqHL8fkCGJIRQHBsLVDQG2zX08I+b7TOwrBqxPN0h
D3m4Ll32bPXHqVxTTyrNd2RSAbg5sjkZnodW0Pg1jgtxwZWVy19layGjcYBupuHh
7HkUrusg8nMdGi1f+Zv7duiPzsS2L2psEtKWU14uhztX/JBb3tIcvo1l9ItgMuR+
b5lidvpr5JpHFpuQmJ9kjmyIu2Xra6mk0E0qpyPXeP0VVL1nQzdZfM/f750jXaXS
QwFr7wNd8mEQYFVBCNppfkq3N1ni9+VMfIQMDfvpQFlBzAPN+cVJ/6L0ntaXyOq1
XxcPrrAfRkf/1dMR4aJxN2PdR0Y=
=xFY4
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cd944c03-eee6-49cf-a97d-e622e3aa94d6',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAryIIy9uThqzRv1bJFYeD18YifSlvzyDjIy73n9Lpbfls
CFUXnRSzj0VjGcUoETJKYlu0xaX+MW0GiN56Y6RlIZ2ljppPasuVlmKZvUBTEOrY
LFwXFhZYH3guK4AOhzjtcReD+V7zDGF+4X2jhcXz4C7jn20ElLFPDhhCkGbbY2j4
umps3TM1hw0ELCldfWKM6KvoHSe+eFrElquR5AJv0k/9xTl71Z3nc1nOPxfhnqIp
lK64MTBLajWp/24ewns9BeXuCi68p+Lv6CwCqIIfjE0IDv4qzeFGjYwFC9Y25UrS
1uQza5E5eNqWyF4E9ImIVNyv1h3Tsl6nj29ZGKCiB2N2hDzb6xXS0ixAbHFkn+Tb
tJgLNTEZKMRmCK6W20+nmJyXMma7bFzWvW+zzUAWhA/1p487dl4Ghw9az3fAd4jk
VLEvncuezMtj5znq6CKdGZT+qIlA7qT3zEKPxJ+GFUMT7xDlnNHIiEMRROFE7kMZ
auBTecUT70LJspOD6f7GgiJMwq7nJqe1FyDPAPfDWbtMSxSZ3GH0AA9h3+OS5W63
GmA7p66XY5qi/FiMvYCLpqP6lBAaooJxNl1b3DiwXlHXuQSyD+kYsDLFhhJcZQZ5
6QObTpGx9d2OvtT4geiv35tWqicoFPoNzQBeKrE8d7zJZk5ch8VaarBPJsXpESjS
QAGY9OhLwlul+LxGizgRjT7D8XaHAi7qNxlp2eWEJwsTlJYGqPuFuV+rw8fTHD+o
SL/zFs//PlQYmEf5/DlD0PU=
=9jx7
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf514b66-9c53-46e3-ae62-298445658656',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAqegCNHFwMaBzjI6hR8hRbKfNyK4eWi2Dz5pL2mArssgX
l6xpGDFE6ja1Q6pQM60tWA4T4lBkRDVncvOLxOORd023EmBd+gu2+nj+s2j4OrHH
NPGf3an0A0YtJ2oYQIAUmUh3D850XqNmkm+ptC9TeRVa1XEPC3dM78xp94iBfpbp
s16M26yzxoOkRBHkUughEaCkaHp2TEvexkO27OuGHf+TmEEBkAemFZouWEIx9GdD
iHBeQMzpjVWL6Tx1usu72/Y5bpKKuCjI4D5rPb7fktcokSGTxuthGXf/9uq6uJGL
Iy8SJOWO1MKgahJaFElqOcR4sPt1OXMFWovK6AcNr0vDgWeId8ksa4GQBFNgOS7R
ufN18TjwESB9IfGlX256KySQCpBzWE5ZJPcNuor/EWHVg/IvVrkJE3T2goy6p9ny
E27SCBIq5pbqfWkwial6gKhN0P6aGuPYwXH+EbTYya77iZdwMAagJDKz3Mk7DUJo
tdMZM9t/3ndkwXQSm5SlTxAFPdWjQuiv1J+/G+jQpZlBQCG8R9AFT8lw4JBCeZ9V
skT0YgLbjDlldKKzNBh0cjYZWBP6BSi588Gy+oRGrpeTiWIWOip811O3EjHTTD6e
srq+TGedXbd4jVHvrlAFgkxnfHjzxhfvc1a2wRO7S4FxoiBjmo146B74ieti+tjS
PwHibWTfanehk6KfLBxCW/cFsNi3dxgRcxcZJPl40GlSnL2xX/3phnZsR3rk3Tru
ZBSiTb341CdETbsn3NnEhg==
=1vYC
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd46f1183-6dc0-452f-a602-e5ead39fac35',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/+PoWpjFI0A8fvdO8ngKLVSnDYiUZZx6Ed8zO+PnYQI8Gp
xzkp8O5xGjjSRT4wUK0W3A8JRe+tegmCxzFe67Xyp+4lB5FqOWvIZ1OSGJkl1bla
FxRbCnaqDbZkjukSJuHNzxk+HH9XmPg9eThBq8nCNiBlWqBlZPkYZx5g+fOOPQiM
EXq9PlJWR4TZVd66z7ATH0Lfa+GkFQHJPtv1SE8alQ017DzQNYW/ifdmfCrF3Yma
B+xeOPf3nnDZcZ0z5rJUqD7DaApIBOrtCzuoPJtD7SMjmCA81icbD1YK0lRoGlG4
rlJ1SfFOmlvIjPCjVa2vuWg8x6pv2JoqApoZVxTLIJMnskmE867KN7rKNLhAfZZT
Dof0XYkU2A7ykRO77+5fPCoXdsPz1KHXM/weol0sLfX3LKbmnlEZ0rlaZkC111u0
QgNl7fnq115+/GmrvEic4sMTZk9u6O13JtqTFk0VhCkxx07lbveMexxPt8QTV4zd
beAcxjx/VGT1xjJuvVJRdNyN/xYCByMu4kkgljZjpSTepjj5XLkrw+ji7jYPY8eZ
XX49UyvBraY6eEykR3aCqbacZRaqq1smbvNF2X/+avUXglnqZtCKTLbQlicPuoWd
epfNsvekJ4sDyPFzPMr3eRwTzt3pUMfHDOAJIwIwp4VZbMJR9Px0WOULgj0G74TS
QgHBEePeg+HOWdX7j7XyYR8tUV70k0xrhtznmNkyPUiO23rheFHivGVePrRMHuAi
gQvJMpPiTSjssaDuXgBEI9ovzA==
=Jt1i
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd47c0025-0724-4f29-a8da-f997230779ca',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/7BLG+XztIdBMDLm9fPl0d/k6MNyagr9loLiFrdL/hORnY
e+9dgU9Zek4ivqzxf3SivKxolajh5JinD4uv36URLh9oC3XTapHT9vMMaSVdOqvG
RWgMK5/4I/zkVENc2IaGtAt3bxe6cLIOiLD5FzjEfKY64qxRHFFAkBrR0kbCcTWk
ubOMF5KID0Rq//a9rbGvuZ2BO6FzK+OZeqTYY5HPzqUMO+ZFbD1FDTcNVKDdXNqE
hbwIXjMOXA6NyHHZGLiY+TRdI75njO+RKKNWwt//5vjxMN+762RLOE/Lq5rJPjDz
MFwuiM4Of5sya164Ds1RrnIYTtAyALOfI/AzRNZ3cdJK7BJVaWtOPjTwIW1z8Q+t
dX50agihpnpEGP1vKvm970Bp8JuzJHsGk+866UWl8Of7QFYhRVfqFgYcI1dqqamZ
8OTdHVq0KsmdggJHylnsQRF+BhKqNAkm78khITziaFJitTvC/NudX0ub2OT564YB
56VbvzsNTa6MLloFk9vwQUVW6M/Gp74mtOtuAjuUO1G9plMBmIX1/CXPIVUmDs9+
bqIzU+x+hmiiNl53r6VljOePsT5VmmYVzIKxHwyy99kCk6Fpgo3QU5clQnu4jAza
mWc7XYEvMcqBM9napgVZYCuZos2s0KN9ip6alCfbyZH9jvtvlk2fwv2wuFZRGaXS
QQFpspEzYeZuWRMJLvXLnRCbdYeczo22O0p23yD2YbVd6gWLsFKvAT6s3Zm/jMbn
cpe/a+6grt48YcAy8HJkt+BU
=Bgeh
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd8d9dde8-8ea1-4e1f-a08f-6bfd3f187ae6',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//elwxP/QYkIPiv/n5ZJCWNZy8g6xgLSeF5wO1iVpNcyIS
DxKaWtd0dIzReStsm51LKtwFMwPgbQgASfRzRhbqj86CnXCbLMauaACC1qJRwRFJ
aEdrbQO2Tb4A+RBdZFDJ4BZUrvAdjQRjiRaxqQXry/Soh1eseo/9pJqZ1eLwW0s4
ZvWzo8jH0qUQdHcIYDurrPig1jpJHc0hdlna2/KVDc1BSTOI/AP3Kmz7XCCFI6yB
bMoK+mT4TfNCD1FUzjFkDpktz3RbcDX+IdC50oPY+nQpC6TiUQk5uy0qhiqaTcxo
iv3N0+qpla4BSOFWKfCw1kBltMKah58Ls7JLJvetPsNGOp3PokkJX4UryT0EreSk
oR9nJHCc7dn9o/cwFiabiKQGLdhouylPijCiE0C30T1qSwP5oqFPAjy4iy0S2ZfF
G26Va3pPDQxfFHGgRndO+9VC5zKu2uA5QfU+61b3JswZpBOZ8uD80PIKIkXt357h
wNUMV03+P8yF7pGMKEw10nicopzNTNc29kMSWNC+pCQ5TWKLBzZJSnaeRkTYuosR
QaLpCPf7SmaBZxqH/Y3bvgtX6tK5jOyhX/6hVFf/rqzSEe6u6Qx+Y2+MuUfpuijw
NA+YuswrF1NZnReSz/o679yfxvqqS5PgQheQim3boXFj/aGhblVwghGYKdRlpUzS
QAGaHJJ5bX9vlVnq4LvwnGxaw9ZgbFRo7ss4LUz/mzesVWk8WQBk0c132HCaElSP
VWDMQhNfosd8upSwq+0Jym8=
=vpeS
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'db7143f4-debd-4c51-afda-5e2eeb5c6c1f',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+M/tlJjWU6/55+tZMpx2w1GjOWHOBC98uUR9KRd7F4tXk
h2ZSndiHGBpTXn4LbqFQ7DmSQMRQ6G70mB1njAD7uKEW5lPE+ex5Z3ITPDxXXdqG
rpEoB5d5MRbUZVGpJ8sz1sfDLpGmQO1mD4mRXv11I9rYFyENwL9IER5zmS/7c9+B
veqxlu3YRy4ahRsEsaPxv1MdPA0oUNXH4lSySz1q314POQffQ1N1ZrDrA33eiuex
lNoasu2115dPVVfJKNn13PygpJB7patMdV75wMZ5+p9TbWYpOWbdTEV35OuUAHQV
4W3J9NVFqPNF8Bp69fmI+7K4JwpoJWUGFwxpOTL7GxwRx8rGvy3DN+h2tRbZ088P
oVO7w+cBYMa6b79KA2cMyQlAKDT475ukmNtlUH3C+FEAhZqPyGteIUVwojfStQG1
3CZQ1HlVhMEwwfFyvAHVJFYI5fgq96vjmaqxHH/p4n7usikJBadXv6vLShS6rdxT
s0tG/iJK9YOch0QILeru87PeyGf+rGsNfTSa1aj5iSh9Zp54YBD/mEJFB9xi6f0m
/YEiWMmhMq9Smdvycv5Z5hh3AENtBoktq9z1G5ijbFLypzsMLEonA70JsGE2DriH
9EQkXo5fC4AVHQlHk88VfzhFAXE5w1FjcM8Ip0j+MIP+1ZM+nX+4jHX3i2fc0THS
QAEvrsdaptdJEnREtqKRz3P3odGRCVtSUR+so+Xe4cuTU6z9WQwuvNRpD69ugrDA
RJRO4SNzXsyTmKz+fZxUIr4=
=miC3
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'db96fbc7-f53e-4361-a618-47c394f4c33b',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAmCNWVDZCFShug65tmNpXWIfdaIbL9NLK6UK98Yvh19rI
nuHko+PWlTF3UPQhrFBFLw2hCylqw8Mk8U0LMBM9O1O1o0zlnSYEkbtYn37r16ze
b00CbrO2ncSZIs2fH0gKx4Q5nW5WIr1ceepHgbJqqU8h6hcnbyN6wwpss2MEcyWb
Q8WacQm0XSekpeyk6XIPMjGcPC+sl0BSgy88lmVOG1QMzsn7x9H3CRCNuPBqecgv
00HV3XddcP9hY2r4xnA61fZ30ag4VLXXc8naqT35bAB6XQsMgzCAKqHMRGjLK4Ky
ZS3YCEBZIF8O1hjJazrRyEZ/ajV8Tf9jX9SUoM6O7Vq9NhtjsYMDrisisB30lFY7
nHQKAPAvDdaCcplsFJJyiwJIFTYronPCCIzHp6MRkzzxrSHzyZ0/8fVoVcH/hqbc
zQNZ/guMMktpy7VUex4WjjyqPXp2HSgx5iN2kNPjIJzRvXmyeg6+UL01EfMXbojv
fcEtXHn6P+vojgb6mTButJLEcNB48OAhkV1Zys2vhohmtra8y8CI9BWUOJ8lBs/Y
vp6hHJ8ILPF6xQKk39Y1OPZkeJvn/Czg6YKiHNkBgZo1UGzodT1irheQmO19wHm5
TixJgbl5zk4WKZhnU0ybWDMXu49qHUuyv+wQ58ApCzfruMGU3lcfO1rjKVvrumvS
QQHUEN30HCQiBqDAQFyhV3ToZRqmNOvnJT+748k6dG0O9CQoR09+iYm97FKW7KX/
kd5kZ6tVHrlq5IFaZhlY5ky6
=juxj
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dbbc0733-0c68-4e50-af73-ad18f2f35bc2',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/V+qrtHeO3mWbbrEHbX3cKfcs1nqbow4ByRAYFN4XcVQR
FWsYxNz507KlaSm9vJF7ACctp7W9nsn97Dv/KTEp2xyAjHdhQuJTY223B3FB/m2W
l6uBgrM3AEnsSL0u7KDnpo9Yq0kMcM0eON1+yOolkFl3WzaAuaPOKVkIPnJ2ejW8
30F3F3prGcJfgyRYxoNQ6zDzFvzxtWe1UND4ZgU69pRjpF+qUuIRSx12FjyglEMj
mWwD+UIkUSKndLR3pAYnQuWqh5Zh81gFRumC7okbFqriNv/n1hMehxI8Ct+2y3rQ
8udaALtDd5lhjCBkevXLEB8+RZgwNIm5ngrOd4KNvNJDAeVHKKbebJm+WCMxRh6Z
e49SEN6dQkdjtNsHLsIdnyTEFZUK2IyNJb5dwwBfxeLRq3UmGAy6OcyloX07dpDu
nOKKOQ==
=PxwY
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dd074ff3-a629-487a-a2d3-2c322c9b2986',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAtzTs8U47eoewTfLKY9Hq+yqBAD8b/ySN6C/a1H4DOFOt
I6KHS2bxdRR1GUQkh+GtGmTJqQykiaGLUmNTqk/RX9Ut7Vd+CQDtUTl8z0JUwXLW
vyD3ftdxEd3cVnAy2VOczE1JT4zPa1575Z3G+UGndNXhlWNDtROAHUB2op22ilBu
CYTCwWRDqqU0XVulRXfo/xzdIPRzhsBaJa89lEOxMuBBpkhmvlXdCUQd2Khwptlx
TWc7ReqUTwLgUFlIiw7X0pVtM+8RDpf8fj511ibGA7pv5DjeVIKE9B0gumi+iUXZ
RA2ef9XdfAFVwQIihqLnw8ZveyVgHeyS7uoxKs8AWdJCAXOrd2yk18Fqt0psifY2
r9vjaVPMJVOlvMtb5t9+C0aZ9Bf3leI1qU07pjgsqQCGiOt9wvLajo+7sx0Wh8p5
S+rv
=nBIw
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dd41586d-d007-4bab-a5af-4d5f48d14ce2',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAnIwN3jUF/0eyUjj0BNsqOlQcjt3RkQGs6lHHxsr9qVqG
Sc5asnlZvYO+nVEHnYLU3iY5Ni3aFf0obX35e6x5O7L4Md/R8c7M6b1w8qkGya68
5QxEdrNiUklu6nfUkL2XFRHfLMWRlbPTk7mLXTdvoD3/HFYIC+a2ciAil2VTdrAC
t0uKyoBW8TztPwTVwiKMqy47/lv8c35tnzmCkH8mJATMgJ11sQCSbu1tCKN+Sa2q
ndqoRKd/fpiER6aAjiAkBvO+8AKZioJZgyBRLg7bJ2SdJJ570pZqS/4Bvc/CEKPH
Dzcxguq1srzNt1sqa4vffYXB9/eSGDHj08Al4fWqfhcqLPMj+MgtNXPSFRndtV6N
54pitKTz7VrpzjRdp9TPPChvfGKQ0ZJqI5lped/WHYtl1F2QeR5nHbujm1uHI/Lr
LKdpCl8chgKCIWlqcdpS3vyPn2PJXg7/dsG6NT0pk1wG/f4JPauTR7qgeEN1d7on
eA0Pf2n2WA/+v6CKGY7dlcswPbunk26wnFUliB7SwZnSZU26JqzIJlDCm0UMp3OW
osyObuAz+d54wVqtCSZSa/Np+Zg3zQd+nIohMXUkKgQaeBhSJWlUK2ONlYqEOwtu
Xv50EBhfcumuMO40QZM5Fblf4EjZ7UNgWB5Pc2Cw0b9KEWj+siMv9O44lGeNZYLS
QQG1dTMNlXMJWB5u2AnV6WDIRC7xAB7ZBX/gwIYLbckBackk84eZIoUP1UpZDEDi
y2tUec7SAAco/99C+maczHH5
=t95U
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dd52aa2f-8213-4c3e-aad8-15878495bcbd',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAvrQh3PA6nmxjFmyjfEwb+1rHWrwxqP4BwN98OmCSXLYB
zBsNS4dCkJtYw767nJAZxikDxwIzNmwra+KYrmR1hykoHL+iy15yJZjZKogvSxSP
oxbdVfkkHqkNRfsPRscNDK70SSudLkL0woGTRdHQjyA7kSdG3wKDD99fgJh/SI+6
ApUBArWJ/lDoepvZwRHefiRyarCzLacfktIpSwQ+znDB9EHnUVYn8A4TEkh1h+2p
jqycA/xtTVysuQKYgEaITIJ29UFfHWI/wDuzVHhcKCJyj+kdVcUAAhOrbukV2dr4
4DHdI5eXr32K7mljIYXLlI2NWSguqqyyc/dVv6FrQNJAARWYR7lVZbwT8bZ9CKRy
m/hMHl9Kc1TtXUJrhRymEUW7+WPgkZ8N+kuVDAI4U69H680lOhPutXlpSrgYtxq/
oQ==
=vJbp
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'def3f131-fdf9-4921-a69f-82d7196a1014',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9G4Sf6uGcr859OLUCnfGUWqOA8CldRmwbjgkvpeqviD1v
psoUdTm7fJoodvqcck873eFY/JWX0LZO1XuzWWYlFBFHlh+wRDyhlxmrmg8DL5XF
bYxb8XbXLS973CnF6jSqbcZAPSRiGGRfRJCf9Un7BQC/YN7uCHZ72Lj/rlSotpzu
VuyHzF0rGi5z+XuAk+bYlWfLDvnCFiDmZK6fGceNJPSmDTYDLgE8OCRWXC+Ll0Yl
r2vawXhgfFfee4D6eYcCu/fLpY4sPyzBoq+NRKx7R64ZlbAKdiSjkb1VfXUi8ZmM
4zEkmXUJjjbZ4w6hJGdxOyKZzSgII+jdfpYpn51EtbSDoC8UR3eI1Jq1bHeZr4FD
1CUIAWDW9lRzEyQrYvaxVKhyW7Boy1HRjGpA/YLYfcM/xr1AuRaxl43o6pahB1L2
v9G6Q9Kg5PptltRab2s+NAvks6Xzm9jUPdh9RFiWIZjzxuIKILhREm2oShrHhy39
8kUiEmNSvQlCzP3z5zFrLL3d5cAsxTfCl0Z096x9rwrefKcVdgwn1kL3PpZKU6Fw
QDwK7572Qa6kaVImD59HlT7TofKycr7bDxkOuMCt+rOVoxxfIbDmprvVx9TROPZ0
KgCNDRIupux86/JSiEgsePKiyYvvB5GggqHAVZ+mamAddej3HHPkyPfMV7RJNffS
PwEH1HAAqxHFpSH5adeDNB2Ztk5pc3iqiQLFTxiDliRr3RiVcRw3fVc6IHSjrAxQ
eCMF62Cpz21YnhxKiEZ5PQ==
=7zt4
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e0991140-b1e5-4363-a2cb-ad99750cc653',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9G38CvI8tObKWovnnz8hyPC0hLF8fuZfUf0RKnrPSBpHj
E+WcWHWm0nMdcxRbT5eP7JPW5pYNGpXK+HwFqhKIwzP34EQiBGHUL5ki3kUkoH7K
VIbvb8FSOvQXvYQ/o7KR+ajpIPNwqJ3yOMTWbC7Ueg0mr/pkRdaMLi3O6+k6ytnC
dI7nriqfr+B9oLuM0HHXb3lLv6nJIYUe1oo7nS0zWkueXyDmW55V/Dev4rkUw+ab
3sB5cCxc9HYQp3q6P83Z5NK77nGCvymnydW8K5AqllRYUVaRMgkAHJ8XOCklgyt5
SChb5/jdfPJxBRU3NXmgTDg2lOflskRWtqaAWCcTZrthb1AWWSn97meBGqek3Jru
+av8ZUKdZHPmdrlmMdQl4B1hCMw5+Tek7lMmbEbx5BVTWa3PcVmkj9ljaIyXh/9V
jeLdlkygOzHd1q6ZtGXe6Nb7Ok2b8nqkgD1/obsiBfaHgkSrSfcws3vXaQvsp01g
GyPk4LL0/obmXPtq1YTOYIgTNFCFlYbJ8dUWNVnmNsKqisO5+hY5Aqd54xIvBzr+
zIQqyWRgd5oQOcT9aTLa1XdofivwOewjW7sSE47GNoEsU153XT1BDpSDmP0r/U4I
6SFVm28Y9/bJn/zBi18pGRiPfuaHB7X9sfiEzMVysZc+6e0h+kO2ZhM8Zeg6r6fS
QQFsE+9Lz+VbnQKZRq5l7Z48+3uHTvp0nP2G/zdJDJmHLU3OTjfkl2ifx0n+HkBd
ZZpgk+05zbzNxccHPJs1U/0S
=RyFc
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e215acc8-7a43-46b1-a7ed-508c516ac75a',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/eBhltvERmN81f3WZvTyX39MwhXyokqGq5D7DfkFBoAsB
QOVoD2Gy8Oc+LnnHIl3nB8wsXT/RSbY65xSe87AHT9rEtVL4YHd4VKlLVsM3FjM2
TseMLy+M02q06SXkzUCVDLb95caScs60GJrm4nxPafUwG7QfrRMP4fuTq8oyYno6
+X2TQaUgmuYzbbbLYxSoRwfHTNSlz+L+trFzXhyDI3IJei5xcnOusEBtHwBG2dY9
OiiyFikboN6sHYsXDXKajuzVQD0zcE0s3KDkS5FwGIljcH1MI51WTK4LrM6cwkWK
Dj5vSWVTx7AM386tTe4u3HcriVNzm4/km6CzpY3eptJDAf9kucKfa8FNCImc5ayT
bxwnOF2BXD9xagwCv9tdIooAV02X56owf+rhR5IEyL0C/qTfLaLkYi2285qmquTE
o30U9Q==
=PsZA
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e5c305b0-be4e-4076-afee-d2b48a91f081',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9E2DOVC5H2zuMWPIvS0kcSiX0Sq3dFCGIvJDUAs056sd8
C+LFjLioByyaqtt9/xFXJ00efS2C7BZ7joNj9O8CKOKcjpnKqkPfSPUUjyAuE+Ad
eEH7JC4RPqHHCC4glZzB/I+9N1OuONCrTc1UD+Fqx28lelnP8l8hXoKQN29NhfBk
miOkFAtx90iITsci13N2WoJaDmo+aZuqUg3qsu+ktOgordjAGVdH4B4mKTbPaK0s
EIHcYcd13PxpWK0+5TGXO6fJLplypTI8wpAtROgAn5ZYHaoy+8huTrHlihfK82d9
c+ZIaNzF/evRSyI1Bdv2hr2ZEe77nw0B619LvnNm71nWK9mRR0cxLySgdxTt/4pQ
dMaHcmjzHc0JKQfGcD6wCuLmwIlbI2J8eolHIw+e0rYi3mwBR36GIM6tVOOiw5Xy
clNu7MLvKErgFdTPctTlJ5BFOHyDDAca92nnIKWwYiMiwy4+IQ6qv9yismETpzxq
YCLSm8T6uW746JbklBmtin2TVNAOOaR56YW9ukh09qGN76nhcwA4WdLbi1Z5n0iq
ABRvFUUsDbYhqoepKlanb1TN2YroTNMc8NwVfyn+rhed+ZdVGyHOeGX9WA7oETCn
DCUDyKLA39bJBQYYcwdOPOZsbQvgwMM9rsfK1mKr2SMrzFBwXeWBbS2R+BbXtorS
QwGHctYrd34TX5GS17vu8klpJ0ZLmwlFCqrJf6IFCdYO+ssh533QQ2oCBvrsY3j3
GmfKkSAXM0WzNXegrbg9tqnecgg=
=cViB
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e5d2f8f4-5026-4aea-ae79-8fab0d3b916c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9GIkTguAXrO9iz8cAutnQYW/ie1s3tz20Timv8lZ0sT56
UMvm9JxnUVW4QLHzrZ2naiZk3EhNv2imUIseelHZ93ZJxImpONpC+gHZl+lgM4Xm
jllAQqfMG/CpGvFNv2BWnR3/zXkWw0yggTo4bJBaDea7Qsv3Rmh/3jZGK0kG88TI
8Ph1ZIDLZ7l0A3nw9MDnd02DTsJrBl3RUvSJHjNMmjJ7icXVjbbxR/qyXs6mB3fG
1b+qYx/RoQmFkw8JBGo6VI88YAMGvhMCCRwMbYZrjxSpUzTTdvKhAJP4U+GYfXe6
+oFrX0c3Qbysj3y5TSnEwRmQvV0MwTruKIbSEJAdHR7vGuyMX5C3XJJqQCr63koS
Gwz7gjhlzyAwY7tMLpMCTQlwpXf4Tb+gke36ZZGZD4EIl5MQFFDskXQdnW7RqpwL
+B28SED2mOFF9hAtwMPG0qYCYW+iYUne6XTJlIpLdx3r20r3uGDLTLLCxy6Kx3La
N/fxWY4g2MZxWOmWf6N4ooQAzFx9DEwDFMRDG5k1GWPEEUIlsnz5bmDHg6PQayra
5KvuYqirT4VpkkiESNf1Lk1NS3wGPlpPx/idMuNfp0xxif7/ZRFyS/lnd6Ea7MAN
gkTf+0gjYzx2Y68sTlpqMvjjEl8qAmOpp7PU6PYzfO+h+wEd0v6RDh2xKqLceivS
SQGVDunNlRFvggVR7MEE0dTTz4OjH9ZreSmzgM3P5hbTJYKo0JFR9oCCVayEM67r
/M26TAwn5yt2OAsEFvYmiCex2jOlgiw2o8g=
=cGJE
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e6bac9df-8f2e-4283-a6f8-220dffb5496f',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/S1JPubuqWORWo2rbkPullDtBpx9wJmjntQNl4YXOcQcH
MzQZFv82TQu7V+JFs0x1RD9dsPPGV/6eWlNa1U0wxv7QYiVah2xSMYFps3tlj76E
t+idV0w3211bb9Bai4Gk2tbaRGojVTt9Uwyq7N6aDpDGiP0iOG9D1NCHORP2UwiZ
KR85kcNvtM/P42OayQu5mPRGH5UOkOvEuR4RpHQvAmrGjYio+Fk0I6mr0yzgN9Nv
UslJHv1a/Jzc63e3rWIgZtBqraxeIMzSKo1qDg4TScL7Fs+IF+/1gV4CYORweygv
twmaT2k4dNrwGprqlSpwZ5fGGn9I/04vpqKEAhGiVdJBAReuUvPMu41c/tXc98GY
zKAjXriHmlDWjceGmr6bFZMbSMT8kwTvid7NamzwQzrO8S5r7F45TbXN91SeDcxH
ik4=
=qC2r
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e99006a5-36b4-467c-ab6c-010fa97657c9',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//Tbamy9cWdWey4izeOI99cEiqi/KWHGRwwdfs2gsSk1Q+
AoKfZ07naepYaseKgNyVtoeGI2DZME1mWuV2DwfbSgKL2bWA18hqluW/p+5iRdEJ
LoGyIbhvSCECoR/x3PWWZyh/FDhegHyDbKqkAoiBHMOSserAVQ/mdkXX/ZeDmC9D
iqfifdITWhsGPXsjnmn1BCmRz2m4iAnCsJFJO+SNkAaEDkU+qmGJz6P1s6iEm9AB
U5ckQ7lcCFt2lQ+Bj1Hj7ugyys50foFBkw4K2Zyz+0Bs0KbXbufjd97RG0i43tcv
2xl4qW0ar1uLam/Ng3WnvdXIYJeO7t32Ov0cBnHvPa6NE8V7ExBX2RPkaLnJSk0j
UQVLK3XN45JeI5XYYSyd+TbSlq48B0UUt3pRtv43TIPEjfSBhq4GV6+1tJKDIV2r
hGZGCB4lG5s9vvAWUnehWn7hsqYdYAOdLbf6X4ScOmsw8G45ygWEEIOAANYz1BGx
xn7R+LTnPtCxeasG6iZ/pfF+r9F5/jK00Xj8D+o7Y+CedFrWYtTL178foatL3ZZ+
F0SjFWuHokTtiIwH0W78YuOs+6b6I3jGpvrJiFqQOnwXRnNfYADYz1pekhaRJ8wf
OeQKoe955ABao/r2NKHkK9grwnU4MHVO+RQ645M7rnWk3XtJRukXin2MJxPOCnTS
RAHOmi+Uvap6Gwez4V7OsKgMMbexC0Rid1N1QPLD6rDiEexPJo4Yr9Qpa1ZbvDCa
iph3mSEe4awcyaSuHh0LpBiVHRYV
=1CcW
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ea90ad46-e8eb-4d8f-acc6-dc02071c4d16',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+ICTBazrN5gcWwvaZEb//2kUxu+DnKHUELrmaJ1dPXiW3
rfTdxG/sHU9QmmBQ3RUXpOtsVIJRrjrdeHXdbYnoOtAuvfxZaHebkZoVbT+5zqJ3
elsayXN5tCz8JUwUMY2UF98Tndm5RGWMBy3dY461wEpI6tPLdSXY1Q7AMJhugiuO
d0u/ZVoBJuCyGjw1l2uwCJX5Qb+HtkTTvQT4u5Ej6SOGXrB3/6lQm+lyinUNrZpn
mfIDHFEnQlEp2IEUpvlpOt0DjOcoQACUCmNz5Lz5BJjm75/+UvH8zyj5VEwm93lm
qQyDJSUKwwtC8FYo2WQo6wUT0WWnjz7N0tCRwZVEp3jac+pistCjaFECkdo3jM89
NjBhxDYSrKh0G6GnL6nkOGXKeCeq+XdVWBdgxpEBR6Shbntl4f3SYStK6ezhjKuj
CAHmg/lDE9wOyKCU/JaHq9Yb6kiXi3n9VAB5esFrCrFOz7jgVdcUjwlVku9zgRb6
Uj3s/IEoeYVyvrhRGh0H6RgW+sOjpXDeAAKcZDDfUgRgEFt0v+EGD0BwmyrOr3w3
dlPQaPjDCcqYfWbFeY1OjKLZokrEjCuhELsboolUnlAQTISNZqy1hDocI/GD34oT
5Gwk2SJHEckUaNxYjusLuCY+dD/+PkMtlvcxsW0itWHWJpUozBKtqnMUEGzsQSHS
QwF+OZYXsZ625m7aaDfOstK5zFyZ18cYpiTDemI8IjdxU6BgB2FaK9OqlEMHdBfQ
s9M2SX0iCz7oI7QiP+9ThXyyW8w=
=jArk
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f0af8d08-23f3-445e-aebf-c65ba6e5d32d',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAuP3KUhqcXZNcTEV82odJghm8cR4y9iESRwsdaAowf/3C
kS49EufvBRv4CKSJ8QE5K7+Onm+wnb52panY6Iip6VuScOopp7eS22WJWsOGVlBc
0oL9HC92sAIn/WPgTqLGrkRhOSlhCxY4rgnVHy8TCfeufXA8aOKj+TQ1onw+xHDW
29vC0KXuSIABR0hSMj9ZIWl/C3jhGDvzezY6wgFkZZUF+gwXmOckv9YUv7dBDN0x
uffce4emdrtDKwnVWj5O8hiSK7ZkskKJWvvJPcLqd75gzWi/HrgD8fPCdKDHtDrG
JwBUvUyBdeWSR1vx5XCrLD0GkGExEgUk7ZX0N/OAuvugk5ODysGRLxrXJd0eSVoJ
M6f2S8NyS2pixgJnhc/EZGCno6fwqxzhlO3RqjFAh0EPSjILq4rFTr959mKtfbsT
0rJ9imcxxOWlK4QEZupingBsMrxtqKWidfMLhul6D1/0ccsWF3tYJIzqp+Mk69uw
VCAbbYTIsJeXc+U+4+t1pjga/H1bIilETxNMhepfn8ao1zfsUjhgKQnIyphGQ9uz
t/7ZWhrW3Q/dusJEZ9i48DKo9JEdyO0ko9isBOTl0Bptli5vaKdUiEzqfQ2+wsTb
+/+tauthzdCfUtiehe221HhxjsRRFIoj6dPTtnhSjf+EPRk/TzciSFm12xW/YgzS
QAHFeivW1jrP3bPRqkdk6cFpBLTF+Ue3ny0oP6HvRTFdZ/ALwRZe05ii2KXaIuNi
x/Fh0uw7gow1NIlSZZ5ug6s=
=U6H6
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f110a00e-eca3-4944-ac8a-889e7674a965',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/7BtORfJf2kOYeP6MAFVaeiYELdJkgNxak6bL+TFJh5OQp
nfXyZMKBjv3W+yMqbiXFtYnSUsfHjr4o5NidN/irEDXabC2XtWDgPJB2UFGrXboH
RXS0Vp8kVK6iLYG2BwjyUAMv+SQaD7qya54zcZbKR3bLEZdNb3/kDazaozFhx7s/
FDYm7ueR8tvdOww+yWBF8R7FmLaMCTI38cB0JfnOcF/T1gIXfZMlxLVPBaltnh/f
abaofEO0F1nIyEH7czxW1F7uFhIWxtNuPOD2qu8OAH7wOzalfsYOvKC2cAxITKWH
1LE9Y4c3ln2zdJKBa8Cd1MueArmyBqFYPBTQxjUQWF2+U+yIXRc27NN2lCrzi7nj
i5AcAvj3XXl4ieruovkbi1uE8UNqFgGE86nRl2owfK7pa++ftqM+ImbFT2+nBoHF
+1du3K+Up9GMocESV9dNvwd/4mctf/NelUB7X8wRGCf76XwP4Cz+52fJ0nSiL/Ne
mRbkZO1AePC2lGuxa3FF+KxrNhSgGZQ+9ey9pgjwOjBhP7IbpLJic9Er29hlRZQa
aG7Tl59ms3OzBvufvOQ73aI6YdR9efLu3l5D/V6InWwRmIOPXjfYqVSbLmN3UZpQ
OnSenP68vSe7BjEjc3CbPXdU6BuT9NREZUD1frPFl+J7sGa5lY5RyjA49YOJjnHS
QwH6MIWjbQFjUIS5vTBhA3DBPUs63F8T5rR7soI3SV0TIiU7xRMt4ehJIFLbBdCz
9bTGQvWMZ29arCEslX5ieSR+LFM=
=Ov+x
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f50e6f1e-3cd7-4e2e-a600-359abfa45059',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//T47d/+T2u71c7oCRE9t30j9uMCbpOh0oGc7hk1abtIDY
kYdFPhQiOH8ZVpOkypeuLNCZL3Z6MXuVBzgejsBtKVCYPXZiXQUW1HR6XVfgoDQL
ACDey2JULiDqPAr1LIdgBF8S8vU29lX+Naum3bHFGLvHmRNvNendMW0TkOZofswf
IhJNEZSdzmYtOFMLSWighIOv00gsh3q9YeNEOtNqdX4f1zSVs50JqyrGWa9DVSB/
0ZW1kyzWgZYDq8f8YGJlk1ddnGlns2UxxrHftn3o2hm/82VrQXzrRrGue0SQnECx
z5rNdMeJklSchgdkFg1g4MpMzQ/RWjOBuDl6iR15nBp03Unq06D/2C4Wtg0vd0Iq
ARdV2kGfe88Es8JPHAK/5YjYSuJokWXM/T4gxPJlgj1vUERygePgdBeA+OeH0po/
BIKstWGBXpuwzKUZPZiSo0d0KQZBbChWRE78hft70Cfk6yJbSzNEytNSYhHV2WVi
maaVwM8aX6qB1AFVezmtDmakiqDP2aMTQNsPEplpBeVolENBxYunnBOEP/neimj2
yD7FuDVQyZ7lWZKG2koeai0EzRjV8SDh817Ivd+7Abm9rRPFKDXiqQsJC7rJ+XY4
LbCoEMggW9vitMjayVPwLwO/RGN4XyRKyk4tdinncVuDfCOfI121r+5iR8azJRnS
QwH57cck935qq7FrCbhxBNuB6TZ6ad8yoE37twslb8w6ZT+DdgcNPfkMNE4ulZh8
sVf4cnLHTu97oZjEo3QWDje+vyE=
=Abme
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f6d15bfc-3bbb-4a89-a74e-59b1e55eb85c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9Fz87SNl6fHIW+o+2yEtBHImyQ6j0DfIviD56mhudWAGQ
bsU/zjMRAhd8Y287/z6H3BJCNgVpPwEAktOyfNIPG3WRPNy5+qgU8IdAbxTdb6mg
ylcIvRjWNWLkt1jIF0TDVMxZ68goEJ1jrXCV+MTgTdQXNV8WU3tg+tsdAiRp/P3I
cE4osqGKTVf9VUhyJ7JcmMHd/SYMrI0x94KiPVWp4Tsp+EYZahtdnnafS3oE4Rv2
caJVmMZEGNlg5qPUjtNmRdfpJtRlO01TttW9naSfxVJwEwc1OHGh051uB4Vy9UNF
b7QLO2hndq6Jslhl4Euy/gBZt1BZUn8BB8OEGFr2aCHYKAOJatz2dQ9/bRwI4NkE
vU+3ZcbHgUreUzENV6/CtJFvUSfFXQMziaS9umqNwf4JEi6F2YkOBP/FB/As0vHl
XtS3ed8nMEFkuYs1uBbFsbqkEZqcu4iy/9XwbIf5pkL/wsVS7EFk9zK9sdlffVU0
4A3o7ltp9XPvREBOybPjTluinVahhRisWRrCrIDOwq3Dhc+5iyNISV+5evcvfd5f
YWGn5YeHPeKcuY/aSngFeJp/wMTa63Ox/RW+pnmKF65zz+NEOU0IH+Us5URKosGm
MZepHr2Cn1UbLBDuTZ7F0kVmLkB2sxM0zkVac57bAsGHtYBPBJYfJdKGyDdaqnTS
PgEU47sso+30Oo+vXlBvzA+Y6KDwn/+Pr8GZZVPo4ZN6Tzk+xsjoZQ+M9ocWh+2x
ZW2S3TtAtsfsCju2mjCg
=j9ue
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f8070d55-a4d1-4b27-aac5-bed2a57fc4ea',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//USy1kwvVaGq3GWWkktOAGvHIAw5QFiLGY4A/JnIgw/c2
CgRPf8cg5HZa49r1P5DF/lmpIFgPEDAluF+00ugMrzvbVsLSZ+AGNv6EFX2W23hA
P3DOV9KaG71zgQYLkOKOPyRoJKaq6pEX1d9KzxXqAJalKTi06QFg7cb4QRr1bc1X
/YZwoLUIRF6ZpuwtUr+1yIWc+B8m8sac43i0/vS+dU1nPcC2Ty4KkLJksqajFR7b
bXHfpjQX6H7PwO5ceoWbLdxZ+dPkENl07f8U1Dl5VpJgs/10nXMmEbd/cjAjbzWF
vmC56sJUkjOYPlsurzhhowiIyfWuE/plq9URShEJiGpLq6M14wl8uCyeIp/6TNXw
h4tM/0QEaIjEP0qi9sXTYjvHwmPuFYN10A5R+FNDTQi4EoeO7FPkuP559aMMcFUA
c6pDVxIinWIloqzLemL6UvA58Y1qY6qCrK5ZIBZURt7kWonvZky0KAP0Beyqot/y
e7WpYfD5RNLj6xn/xhA4wDtZNdd3Eacs8xuN8AxhWaU6BzRh5Sdwmw00CtqHWqEL
0pIOrcE45hfEm8mf+QV+eJGIs0VQ5hcq/AuPsOlVCtX86RG64GJJ2mQ4bcFs9XIT
27wFZtc54FGk1mDF3ySArJPgSjYULy0f/WWNmHWApyCM6hc7qzgg3pUT1F9yomfS
QQGWo6+emBC7+j5Xq2hv+o7xFjJdS9v8Sr75Qmj19jxh8YfkivRErzUi8SScpQ6J
BlP66AhZzhC52Q4kW9s0Lcra
=9lHs
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fc1d53bd-ba2c-4386-a5e9-6deb2465bd88',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//eSk81YmN05YKcp7BvQ9iSBvEgnTFdBP1WCOskJrZzQbl
FWqfbzQ73s0AZh1C6VEq1KlUtjr/zvs5WcljiEzL1MfE2CJnwQ7a0qgfeoMSNl2D
F3k3lodIxm/xNuc5GtyGeRX3Hz/Qm6T/XfpSla8VKJCWz9v2nly3tOVsAVMwTgce
IRZPGEchHKzrsp+klvjtQIbx/OJIaTOBjZJmLUrOImkh/K3/1iBgRH5pvTLkR2sv
sM8oRZMRLNHx6FE4VoipWQqyGYl3a7/Vr+vqlYl1dx3hvFW7TEQvF5+Al9PtpMc9
bRJC3i/1FOxH7mSBOCFImPLWFIONjMYsdQQ0/0095qA1wrtieBqSR8QAogfx1pvL
Sjzen34QYBnFZSlddQvVIgp4mJIiEqcEsjzt8R5BwE9pLXNtTZirRQBUXSLt4Tn7
3+9nKA1EtYYBYClm/zKiK66A7gk0YQCsHSNVgV3jBZ9tFVMlPeVqEIyEAcsaQhHA
oOTSaRSmkWyeWsxNRWN0BxB+McvPgsfZgCvtVqTPRH7dSNLiKGcSI+L0ICSt8ZUV
yqicb1lvn01hwWbg9XoNCyTQVg14XtPPeOt5+xGQa48gyPHulTClB7KatBee5lLc
T4vyCTy0qCmCXETxxnIE8Z/QXn72dW29A03oITj9MwyocqLJdLSoD/4FkyqM+l3S
RAGF1ajQjVbsGQLG+oNKViUQSWAWqBhUSoq33qtTOivLL98qYICKUlZuWjFHHCIv
LaFZJ+Vm8q1cz6j7lYZaRZZTdUk1
=haWW
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fc7329b9-3301-4373-a68a-b9e83100fa2d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+KBno2uORGiP9z013YhIC9Zy5WRsHg4RxkK7V5eGlWOst
qKarRI8cTR7/bDvGphi13KOAJL2BHfTKGDWljwXVUSsMZj36+5L3MNXTe1pAC1Fw
G8KGLSbJyQaSnTONQdgk9s5SenV5pNxAK1732qOxhyzTvyawauLCTxunREifO4Ko
HM8yECG8HO3E2ZbxLyjrPWNOiROsVU6xhOQErQc07WYvo5WpG0xQ/NgWo3wqUp3E
0meEPObn+jWslaTIETBRA9dNxi+ujmNvj8aUH5qV8pcVCaC4anH8hNO64wMbBOTr
nP471bfZ2ZuAR19YK0MwcOWpuvPAClPHiUp/viU1HDeCP+/aU2u7nWpU3HMmWOvT
wF8eM/l2p6iX2K9g5/QVucJw0HtL9nbu41s5ntZt9C3HSI2RvHTUdZC2kpt/0GTr
PDDXN1NvjRJDeoK+V7o7DBdGzqkt6e2opkgFndkVaM4RT/naagzq67A5BM7IX/mF
zIZ1eHv3QWRTJWuHCCaCGF3BFsxAVtU+HoUcGrU7+0b+fJYtNm6DRmSg17VPUU5P
Bb5t3L2N4ObsWbxvVwDDgJnci9YuJ2RFQuT5SSzZ4SHjqIEEFwCviumj9wv8tX/M
HGQwz4KgnyLNJrQIqtYRlVQwVmJfuHADfyvG5w14cieDWce1h2PgopbyNXZ1jyfS
RQGHw5FBG1xNMA+uST42Sv8Eb1/T7eU1EFqUs6+gmHVROEnbScLBu/0ThM/GMg7y
PWoI43XkLv1Ik497idDtdZ58hyBggg==
=fIKZ
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fc84617a-459a-46b0-a898-d81e1bf630fa',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//XREmLnYIaJ+SbMTM6rjwBsfn7DvixEm0dxXJlKQhQ2qx
sk/8dqr0Tip8rMHlB2YF/g8wl50nNtk63c+DWGqMKAaoN52+uXp6BX2/A7lWhgYY
MweH96f7O8PE7o8ItwBxlj0inMpmpXfw7zZxQh5a+FdU/6f4dmxpaCb8NoCYnJZa
DN+epEHmInWdW+rSb48kgLuhxm4Hz9d12l69uzM9kvjAXVhbIFkiTG1rfwydpwjw
KQrzdsy7bxHYpWI9RS6EDiD0SEfWgJGIxuojnlAYd62n02jbA3lJVOWwLVEhYt1u
9230JUoC7sNb1ngLRssjwlFRUwY2tt1QYL8ZzPUxav+ojvZyldp3mo7Pu2aLnKLO
EA44NJZSLMyjib/NgaeNhKmOyKCu9CUr6s0uiSYQr7hNJxVBIw1SMdU77oc07hDL
fseOHgdUDV+TNUvFRWpqpEdPURBi9uwF/SRV/SDQzdmgEYcf9Ptpqk3lI61/kqft
ingPICVx6BKLYinLhsezlp89HILnay4glwGQvFDPDAPbkBs3UgwyX780rXOou1OZ
T6PE2sfxw92rdzhqJXsR+SNjmCaCrJSXZ2sfBZl2Xk5E1JGppCc2YO9SbtT8hCxu
2kGJtY3cc3G0npPHv/oNjySKMfcpyBO746rPgSDYSD17OAbqhORAfBYITJOh3urS
QQGUmChIAajTfTDrqQvBrl6jBXc2LAHlfyu096WYoO7fld3GPWSRytO6Dpktf0N4
j83RH7ZKnh+dy8Pqd7WsbUUW
=WQQD
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd89440d-ac40-4751-ac02-62e11db1112d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAmETRkVEY6e1C3krlGolVMMCqHE3wfKrTPazVIjkLs+nt
D/71TlE9z1avKrxG8Ti65098ir4H4KRzYTh4MFC4mLhgFSl4r5df1IWbaBJJP2h4
9B+2HYfHyknD/6GOWxk7YZNmVEfIjfjwRocRBfYzedYceRJivDz8+9Mmtt7TJFLi
eAaq6qDxbGCLeh7/W7pfF16XB3k1mqCfGRJFpIlrhFYJbF9cYBZeQNK/Sm+H2lpA
GUcePrt4sO6V/kU87dYEIro7xtGQ9HLy6p4rAct5uNb328EoMDH3kIsLz75VoFfC
itQRc83S8cNcM4jv9BoY+YQtUoSWCu1PKnEZyiD2m3YUpxbYEhb8jbNBHuq7UoTm
PfkKzYBxSqbPXyyLK9HWo/A8B9C7M1C1HkRAAt47B+yajAErFC2R+0FVw/NuF9oj
OBJNdTuUOkN6NctCvZupEzWp5NJHt/TjgyBP23T3FpTfXG3f5jEC3MR94inAbpKd
lO6Xe/zeP2m5B5Dlq4KA6+81k7KHXZ5rRv7+HE8ZiDmz5zUGVUCC5idvHNoPc8/W
NdkX4Nr/oIncbMxAemJIECEjPy5tTPvxRY8i7PwVDaOW9BZ5rgKALuBVUTblqNAK
DUsdJYjKpgWNss8sHrXfCiCgEXXeR+TW+Dp1/9jN4TF46IGVCD9eFLbnhsiZekDS
QQG4xqjtunfHy5YfDdHKWSGFcW5GiBAiQdjc4F+39H7te9IXhP1mk1kQz1gyUuF7
SvLoRe7g81FAVd9abbYxe4P4
=4Tdd
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:42',
			'modified' => '2017-08-15 13:52:42',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ff00abb6-470e-435b-ada2-a0c843e93f30',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/a4yvLMMPEycv6RmduQejZZxqypx+mOFjzhVBqpq41+kS
48Tv5EKfGpokjd6rmD3bYTnGAquuWQjX80aTktGtBDQD+78c+v9N07axBikPb+m+
crSRMWsuxCAHHe40cXGH4yBYhJWZQgWTx48wbPjFK/dgvhcsThjJ6Ydeyrp7O/6C
w2SlCjFE0sHqFR+f7lzKCZMnO3psdotpATzva2dhBtgBJBdu1ozZOhCY8x83eRCK
qNcrFTBafoH/dRgiLsFnn7XrD9JAQnaP/6x6z/A/zgbo5icrFvL2AW9wq7i+wnXn
lEhFlyf2lytrEMxfchm3FNHWXLcMqVRn1Dh1deD3gdJAAUxRY3QLgRpHYX6qdr5T
aR+YV5hHIUoCGysA3rTTeMzbpxCil/oj//NfCxV2P5qTIi0qnRBgDv02Ja/Vwrjp
bw==
=/4J+
-----END PGP MESSAGE-----
',
			'created' => '2017-08-15 13:52:41',
			'modified' => '2017-08-15 13:52:41',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
