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
			'id' => '00a17f28-730f-4e05-a62f-ac72ba945b14',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAmbIV8T5lBVlsulU+au77mIfpyFwJLSAu8ZW08s7vcglW
4rCMGR/MRQoJ7F+TT+E1tRVDRPt/JkGkOzW5NMTdEl9icpO4cvaMGaPgwLcn9BZu
rowjjnwFphi0egFa+12gRoAhN8rZvu5YE6yU9p7fd0E+sbXNzqxor6NjxtGerCVr
EDTtfh92foH0mVZPiqffxyQ4POs1QeKs/Q/68zt9YocANffZ2RctOKJczjyKaY9m
50h/bQtztOsvgrRVIjx8jtoSbmlQ2CR1J/bV5o8TvB6iVvUu/ReHRTx174JViIX5
v/jl8YvEe8BLoCMF4Z+soXw5Dmqwlp9PO9LkNHO8gaEiJlrKkxHrbGa0w86gvxE8
5m5JjvltnGU+fWTgWsUU95r2+Gbu7ABRtHtBFrJUcRMOZd69x8MEh4D3fpAu0FLB
GpfmY5HKtTebzFAOYWumM1Mc51uZDoMGbhZPHI0tfmFgbqeMABybShrDuhnU7wIf
A9E8L9lLsS5LRR7KyKgc89GhdClKn22Z+/cHvydnMKSdStzUn01jx3PlkO7FWtN/
yfofM8Xrg0A29n6b6cNRAeh8y9Q99Lle710pYzfq+YVKoqYE0JgBpRbpC3KOiqGf
vdEOLx+NuawFl1TwPHthMdvASq1JQn3axOrElLwXjlW12WvJA6kDc9lvM0OKdlnS
QQEGgK30bgCbm6eJgddMUyqTfkcOlBP9MypdNsy9C4UyM/OBuA0xtI7uU1yWbk5K
tKgfa2++hjnh7dnPOOLF64yQ
=BuSL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '05a20c58-f1ea-47b9-ada8-e87eb076b751',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+M/ZqIdqDPSUCMPXuRwBOvJX7iqhhx01wFUVcDG9qphpP
Uf/rtAMLSuK0iGWUiciQPkHl98QasE0PBXvlk/d3WvX/RPtiCCWnk6nOIvmVcfQ3
VUiLx6vTIwkZ5YENMppYHvDF1dZmXa8t5CMjOy+Mq5H4jRNWl3p8tsYc8u/qZnQ8
T3bfWIYYWNdMGCeMIU315oA+bdddXl5NRnFRZIucGrDUyrWRnt8EJlpsXZIxE0FJ
Pz6VKagSW2XKeG4ToaQCeYDkdmcFs/NJqLygSq4MrBBSxQdzCMAuftso7Bwc5JLR
ASNQJj0NOXXBFElTubz0RwK5lX1qvli8zQLn89U0CmHdudzmXEtWFlB0E3YmyGN6
s9OhwVeoqaSayk9uWoREUqYk0D/+1YV1Ogotz74tc9sK2gcqOBCmnW75SUX6FRz3
8QB6NIR9V8VTnDZSekUaWAM5tCWLcpQAoWujnehmWZJ4OL1julX2bGIjPW5/G3Iv
Y9AfJh2x7soeZBqjJnfOdq1Ewa6ho915FY1Wh7Epho6eulErZRVSu1XYTeQtFJXB
a/Y5rAhaa1rPzhUgXhkCjX7M/9PNfj2kBimjEM+K8lmSYCIhWSsh6cFk5bSvQys+
xobRXSMgf/fVxHRlfSSw9cpWzM/mCJqS7NK+/bQAP6yH3EtP201FDFj4k0f3vL/S
TQFBMsUUfTaBqd0EYIzRaZ7Ae1Vtd/VGZbMBeGHej5avVzguGAinRyRc6Xr+X2FD
sXsNaucegAt7EPDviKonYManxLOHOvymLXNPAEDE
=KCu+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0eb98b9c-f444-475b-aaca-5496cb44d9fb',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//blwX3fQh3Notj3nZc8gGYIMVdJPl44i++9GzHaphDr0N
VN1BnHw7gRW3BEEkGlKMWOg0by8pbdBIjB9AHOcudW+4HeQWbS3IxxzZgn/JxbDk
MClSIHLwzBjClUwzwXeXvLEjLN2akNUQ8nsSpT3aXm8YKecvXouxBhuna2XZDwUK
PmUQ8lv64d8PS9+DG7/lq1TqH2HzI1tQrgSoT5+QtpOfqobBegI1T8L972/0RYHX
1NW9EWYrux4T9dQWyQHbgyF49k77PM+a9QjJmOwitdJ0OOgbGHLdLkebafafix3f
jpkLy+qwl3J7JlmiBXARdIN9uYWoMbelbZGfkPD+tbdhQOfWpaf0BsBeDQXMJdmM
yVRUtiHQvOzczNgJyQZhhtplW1x0w5J3H4gf1OAlyBCMEgcSGloFZ6nBkYsA8dft
c1snpambsC4WWEa8vF/ItByF6tLArWfvPBWCLHSe79SViArsyQdE5oowvPKqr/WD
uf1gRt/JGx4TptmyuCtS0zAKZxCQjxWUeRmgJVE9BI0Zd857DwQEtUeH/h6iSAeO
PbKnp/HZiMBQKBdaRLPqWtYK0q2Dow5FP6bcVyz4Wc7hwtvwLP1Fysrv5DvmF05X
6aohufOHjWmMWm82h0XGMjjkWsQskVDA+dIRJMkSbi9cb7zF9g6qg3u73X3iUeXS
QwFFqqZiiiXgtcahTNeszM5XDkVQuk+94ypy1v8l8mtB7KkFVlDsafvAVMNuplP9
qamCv2XAGexGYgFrwTfT8yCQ9wg=
=e1Rh
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15e2f817-0c1a-460b-a777-4be4a646bec9',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAk5CwnJkoo3WsLEZsGqZDlJTTYaZ3PdbTDEtWwIlNIIhZ
DNT9ClmDMKtE4GqVHCB3C3abAOeu81ENAJ11UsDwQnFrSgeH4Icr880lxLblEz8K
YJlFKDXfmfa1IP8PsQnzrpdRjsx3VaCTfkM63bHD7Bj/qibmLumMuJg+O0tn8fEw
n9vASReohox5M6ERCWp2iVuu1WBM2oG8A34Gvv2+/ECzCaWJQobfZEmdC1phWy0t
nDYnp/XrueO+Yd9jHomFNkX389I/XCeCYYQ4OLrhW6OkZqoDlS7cJWCW7L0KlxAk
lFcwzqsagEuEyx026mVvt1BVj3JZlxINiPcxvi0VrbQMwqDE/0j3F9Ds0jnw4rx8
pTGoSOFu0XpvXGbx0EVKpMuHcikOy2L2Qv6MGjAV6wABKxtmC47b0lxOPZrp8BgQ
MRIFHZSUimt4/ADoelVSzWAPHFp2ebVoFFXqA1WE5aJLqvye5RABL6nn3ik+4r5A
BxqJaFgW+GZo8UcqHaMOvm7S1o4UNaX62G+cTE0lhgAwjmcNrF+hsezrRRtuqT4M
8WaaW+3qnznt6PDxm6GLfzFRcUu3CY5+g82BJh5y7mQeZh89INXYpu+AijA8UAcx
I1ryr++brOpz59JnePgkZTNh2kibRvhmR+4z5NzEGhRDdsH8VNHssRCdn3BKhkrS
QwF2pECRUTgJ39JTgGDgA8w6gb/yeYnCmvOtiK5XCkRY+uX9wz2tiukAvnMBR335
XjCfhodZ7TCOUH8F8Dj4M0WyEQE=
=ZOzE
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '19b169de-ecb0-4dcd-af87-4762dd6c7e5e',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+I6yYMT/SV6CTensCvB0TYzn4RlaFI/ExIcGaJmOao+Lt
wMQYNu7aYd6aNYQ2pJyYtnvek3ot67zPtihe4SQEhMSN/nxy45YLU4iE7HYGt58c
abca3zmexUvqjbPLWJitqED5C7NKOl6BVpb0mqUQ5JL+gnJsaSHZJW1ZH/qIjIWd
Zs7fA0JWpTvLnLtV4N5PG2Het9gH4+ZyVznwl+senj9kmXZvgsc+vqHwW/fjp/CM
Jyh9wYQTEVbaFHeMICJM4473Vty3nv5GUnCn4REF3ZiK0dxqFUyyGK9FMiJUpfPt
kG5sDDo/RM8wlfInVUNXk1tYCS85qk8EhmzN/Y4DEtB7WEVXfcKjj983X4PuH2yu
9K2DY4MpZ1ElL5GLbuVyZwq+N/aoSTNF60zMrfjNCiJmVECY+r+xl31kf+29r+UW
92nN9zGjGs6jdis/ycqpCctogEbhMgO1m8/AiAnG/qd6xuMz0E8aaIHcLyIw0bAc
Dr9au2uZgOxg2hgfQL4tDUc9zXCZLiRpjUs+11ldhsjlBFoo5ktSnuNsFdmIVlu5
BR/cmx0rXku6fKGZ59hocRaZnhGt9o7HRPbxtPg5xp1UNFHuLu9mOMPjjn49j+Sq
kDqK1xPBBnL/MYkOKZmnkHtxR9SMa8O3/S8D3X7ew9t8Zxg69a/yYHlmiSLkppLS
RQE+hJNguKe5F9+iZbFlnDNdjp7sNaBavfzkeKylbxOif3N6/p2OduPjERiKUZOV
mnDXrO/Qtcaq5tUQKHW9mK/KaB5GRA==
=jSJu
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1c03e0a4-1f22-4026-a7e1-3abe6c945688',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8DFbejy7SQxXLA4C51fkS37OXU6k+TzCtvTyEUgd0qdCi
M3AO2w3m/mi9riiISl48UEU1E/STXHKpBZeUL0FNGcZsZMaGrQk5xAD5LJC374iT
njo7onzpKdzUg/mkbn5lOCgmxmXKBBa9VcNzJy7kGEx3OuxwwJQc3qMBdwnp7u7w
eEh0XfmnIbaTM1gzxhobm+jS0Nkx+KtspuZzFOTrdkDHtI3vq53bot5U6Gs1R9ce
dSkp6FanCOjpkuEPjrApfXhqJjN0YKWD+l+iOHcoHR2EEqRL7jJ+GTArEgvN31L7
05WRVLuGhv/Uyek3z7uuLVt1kpaAm+mD9tVMIn+SGWK+Sqs/kAw/faRcH62+IpKK
dxFH18XA1CyOKbv/Fp0XS5zrUnF0w+NNi2qCkkYoRfNj5FQZt179LQ6zOkNBhrOY
5pKZU9N4IeBODXGENhbltZFOJh32g6oEEPr0W6u2lJNF3lwJBZf8NERY5Jvr5xAQ
ryiIaB0Jp0UNMeUPMrJNvjSwQxL4b/6PgsUa3ZAsCh7jYIkHb9gaJBwThaOEnHW2
HpHlAaG2ia3tnYkAgMkOnOtibS084ndJkhll7Qy8wGLw2oFXlA2bgIie6Cq5BJef
aap7KW/AAJp3o3wZGbD4cHKFUTHsEL18cuI3AbsZXPrKE+8AEP2k7T1hlJhi8HXS
QQH4O2cv3bL9mY5qNe6BH2gA/fBUCuMPxQI1B5fb7Hcwgh13UPRBswe3FlwGt5x2
kQtXTYIZ556PFvMUkDc9m25i
=324R
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1c895299-1483-4c2e-aae7-a880c7204a8d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//c9yVyrKmHdVBFcGO2NRMAlv4jDj2yvs5RU5+0HFhAyrt
OewxHnHRzeYLBTtyJL3fR5yASPkxSUq8LzlURzq1CTwy45JTnmMzWO16kLQlGqx8
BYDfACiGYlG9sB4alD4+75m1BJxh481Li/SyLJz96sfrE/V6tpNG6q7MNIm1AeTd
g8xblnrAXdYAoaxtmQY40euW+elzAKM2g6R5fu4mJjkB045YlFrsLlf9E4+fqbwb
ZFFWJz45oz/dFOPR1pYxbHiEmkbFMpRyo/ftn0d9EaNWHJytkkddUKKL0+kLoft8
oxKgE6YZ0bJ0cpoTCu8vunUUY2H0VRds7aYOkmhH6AqGZXkuIItpzmspU7n5TtJj
5P0tmxlUDmwAcRiUyOuNza7X8Ekhp+zOlEi2GBqfBvsva8nr98rnGaJ1YfVUqynQ
t1Nxq/P9MeWGnx07HAI5xjJ4XmTlC6zC7CmsjhD4zFITUUdHAACtrdoHIK9ltiTh
JYYBtwEmbn3f6mnNtBkFKbL8BLb6hAkNZan01CMEv9SysdOkcrhBd5zglZoR0sNf
yf4IHuB8Uy/yy8ehGV6aRmbQbETLSjDXbYjOq/d5b2iUcqfHul2sLfTEGtrGO+Qy
nvSS57Jx74gxmN/flvbS48vtc8kp+VpsmkG4eVXHw5tJyKzHpTEI+va0rNjQr03S
QQEyKD5UaF/IWfurEQzQQmkPhdOdoV7O7whK9e0f/ELkHrZoFLxP6YMO7wFh4B1B
85XWA238RFfcvgufxEmmYPt0
=GVrr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1ef578b1-7762-4dfc-a985-c2dda53d34aa',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Rb/agNDewvJaOptHUhwt9e6QUV4bOk/ecr776MQSrK7R
gVdNOATe+Gzu8S/7HPZb112skpd8pV+XODor8u21Yyy1E1IGoBUzKk6hQiHY3Xsk
a+qoWaLphPGHX0sPJzrvMihzIXUylItCBdfF+0yAHUBKLbXZr6S2i6qOIuLQX3iK
16ihmT6/S9HGCKMhPTbKHj3t6ZUTW+PYSaMw/tKheMYOMgYN7/YQL/dYjy1HPS5v
FrIS4LfLwdpIErgc01Vm5gMTiXNyXWtneSKAuK5o32Aeti60j/+XLBncIzETG9NA
fcE2DdGqwWFGnlXDkJLxe3/hcqBFK63Y1RpnBYzt7MGJKQVsCyCw33BU0BXJTdqD
DRv5Jq/W3RdwdfnSTESLCGaivSmu6b51cByB6d/6hK1GiEVovI0b/Ycu8GgkmWsY
Ki5yBvFnSDPdiUMbFfKzl9xQRAr2PUDHy3KnpEff/MLqOKRhKhhLpIYUOpyU+2dN
rT596chGQiyv5TD9J2W2v3p510HOKzg2bTLj3rrcULs6RnlQfJLoRTTAwEUl4h6S
Iy1t21RGa0cAUTu9ZnATpovPqwpyacY+kBZ3SzO1A8bhwBpyMot0/EU1Ae0yQ5cK
WOKmmv6eR+gL0gvzX04GzJUW3+cISYmnyq1iHCp8tVuo0xqTWmsfDMvodB82uxLS
QAH4jsTHoLPdOEQcfRa94TFEDvAiDUVQ3/48dXFtyyu3cD1jMUBIFbo17CZZDa/c
qzl9Ydzn+lwY0DmR84vLGoo=
=HRpP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1f5cea3f-da9c-42b9-a37c-ab8db030a7dd',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//fEazoT/U1MOnde/MZK8Dxtu5DIKh4ZR1KPYRvIdyYzkB
UWpJ5lTG+x55o4TZQgJJj/58qOyPhY3+QLN1Wk79eLMvqA8kF7fMv+KCCGvGgFBt
STnzlmEcOLYGDuQwwX5Esoti9vQUFVTXv8wDyheCEN+8gMDo9p/TRMbaZGqoxy0M
Q7ZYSrISr8mzIllPFQHF5RUCHNnd/NioOIXVcYJjLaTRLELRCpA8DEP07W8z9eSc
sO0dAI6S40zV52L8ngDbgMgkxm9QJztDazWfEYyHrL352d6lttUuxsLRixVj1qug
QeRPnRYOiRNiezA3PVG9I7Th/6fBlbqRwt3KiV752inzfMYU2C7QsKf+N3+ae5ii
mTdARqT7hA4tvn43nveUWOT38IPD66+0rRubvD8ESCO3fDuKfvnqjLWEWv0xoFUv
rcPBpc4AXem0AatD8IJUY2TDQ8nJkXym7ktdUedV0hx24Sd3bHbv+ESPG86/EK4V
aQev8YIqX7m5h/Joe0gjr216H3E0b2SoKyiQ6ClDyN3rW1qjisCpZlZPOihrZXPx
hy9xYx2AfHmU9kxZEetvtu1agSgk4S09kSR6CafktNBQVc/JyImDiHGhRQBtx/2j
EM+wtZtezRoQKg0iJwGnrOYZ9t2sDrniVSneWb3uuLNjeb6B29mRSd92vpy5c2nS
QQFCUnq1YWgMi82Bxh5tk97ERDpK48fQqZvaZeTvLtb0pmfywq+Osgu379iQYxVX
CP+wzcUaJDO9J7pNXxUx9JOv
=vELQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2060aeb1-bda3-419d-a4cc-c3f2ebb14b68',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAhs53H85x1Q+QluDoLp/04oLWxKgAbPDeb61sTflaswGt
9r5S0nvQSMHb/GNVtiiBIPQonDmIgJorv2QPw1A5vPtONENmPYdd8QEREXpW9eb0
fW3kJiwo0QuxlTVTfvJKJxLdBCrKydqb578SrCl9OCEbrWrVb88YvLHccgCPuvqZ
FwgF5gOSRJo2eI0Pe1teSK2IkBJ0ea1pL9/x7iLCeW8LsXNZN4lHrCqqGCgqvwxp
O9OjJ64UeoSVUk2eYz7Jm155UmNCYGB/7z00w/xucMAsJQPndrERpwkw6u8L9BWc
HLEzmH6Y/DR05TUK0cQZiIaiGgwO8lrR9WocgA12qXHLi1tyyGmOW2xPQvJDRezu
Y4kfMIDTcwDAbkjAYjRUeZE+E9ERcLoy8NMlNXOiEFPmJSK4pqbI4L1xIgP2x6SU
b7vwupprE8Oc20qxGbmGC9lpLe4T5isnHcxugniKMkuIPjQROhp2PXtQYhnBFD0j
NGnYa68sxwlTz5jAhdf3SiMEX7jXdPqANAFLUJDppiyYw2i5nwuSSzVqmKZzRASS
GZohQsTb4J+7MvQEHPYsl4y/O10RlRDRcQJcBDHcyjbuqGVUlJ80mzK6Ei6rkbiG
0z6CxFhxDykFsh3YxAdIuPg/PteXmpdPhBRZXaWMCbVJ6VlrcnZoz1bOvxZ2EmzS
UgErXUGl4cYLQNrM+oG8oikYQ6uP4ZAmKxBEW7K100o5oAKEp+ka268bD3cg+u8n
pUMMJmD1uS1cuiqDTpX1KRG6oQx7ATzYRso2BXceT66F8k4=
=ZH6O
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '27d5a22f-f88b-4b06-a10e-1017068f417b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9FYa3kSw+664m4BEhFSDFuZUKop3nG2uu4OgFGxYVmyh8
uPA4VV1wZBL+G13EpNFCpDQ6045Fg7xdv6mTdQq+3OfFzEq5eN6k70GMiX00y+Mu
fPvLGblXXL9u6HkxDJnv5jIdFqlX3sNQ4Hk7h5OhV4lqqzLOCDrNtcppmjzZTYdM
ztLrqXHf3oD894uqmSs2v5doXojEsJ1+/i2TGC0oWlycMssMk0vGpqGSQzGZS8Xr
EkpZQOZaxqDBtt3Q7w3wGyDXx4me9IEs8eM5GEZpSm7I0HThTrDApCqNgpVWzDhN
BIWu+KhkXXlwmtzuD01udGDmGNL2FTp2Gom5Kk10UGDTHEYFaeSwzYqrqyOlhF4y
IUc94Jt4aoGm5sY7LYPKhYzGjKFDzjfelcAABDkbK7flDPn7z3Godg/Jj/NDkwrS
ThLtSnAPpk7x/B+I+nU81ujlbJcPihyYn4krGYjxoPZO2ZBuPIvOVYSInTDyaVKa
DmD6/tQEbxlCKuGdvGv3yZ2a0W8FQw/794CZ7ukwmjr1u/VbTBEaw4fgyHNg0xzn
f/D3K0ZiY4DnyJb6U2wQBvxOeCRpCyj862mUX94d+Amtys4Igay54zyi250+2fv4
req+VdXQOB0kc2JFYqqmoPNlTbq8fQsVVLALT4jo/shGCv3FLX1s26R0vXWnhgbS
RAGvm9fo27Oekb99wFxZoUnAXlBVWTigdeNDlHhmC14JwLmu3kLNNoW60ohyEcj/
OuMCw3ECbmO6N/72ds9cIeAFn0nE
=ehZP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '29c0b917-ce74-47bb-ab21-a855d2ce70ed',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAmpJNDVfDf64q9Oj5RecraZoiCWK0GSgnpLog4+bC+4od
Bmxq+YQB2pK0zdjahAsdK9vQj9+OhCjMhr0sEA8IC9pZ/lgHUgFTVlphUe4cJJLe
n4eB2Mxmway2DRMnY4H/0nYNyFRlnZWzmOf4Utp5YErBWbOFblzJHBYAfOio6A8g
IbJo6PJtiO0Y6KMFlFMt1T/Pu22mWTvmzh+uwq30ENWJCYaKTDbPcltHYmTeNiAJ
VrRaliQtIdSDVrFWIRsOrljV67ckpktkjDI+gL/mKZgf6jlpHnuGreg0lBblma9g
CRoFeroO5KmT/kVilc76oDEoKm0a1HhrKK0vXwzq9pry6ptl+6DJI6vevmkMYdSv
FzqGLNSFvBTndlboY3cN/8Ns/mH3zkPRygVJyF1UtnE2y1ln7q/l8h4TRtG//Wpq
6WiGQVlpzcDt8t4xKQ4cqrcw7/HfnJ2bHsgsUEX9IHclkxhtbS9on023V2QqZnBV
dL5EO6CT5ub60D2fPnNew6tDuEQUqQp6/MgqGDRsov0UW0HpJ+ClehOPgBjuSBzQ
pR53Easuc0QTTKoQQiB2xsLbK8xklGgp+Ziv4/FKTxyy7s/ynbhEvZUXs+Sg7wsF
r9XCx6LEzFH1BbAr2cnt61XkncVa2YgEBRbmTQaXR/ZbOJa/XNR+w1cIdS5prPDS
QwHnfzD7lFV89SZvykgLW3t5yC8vLA9BMoWph4lhkFo/QqCHTfOQ2PCPG31orRFI
baISznBvuSKxuE7kFwhuv6PHvog=
=J/8O
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2b0d74a6-c10a-46f6-a457-bd9ce80cf2ff',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+LDz6Mk3nWeL9usLBLB6ZrHazdgbawiTilp5w8X7VxAAG
T/up8LIYCm3uzfM98eGCKcVLr/mE1Vtwzj8AjbQ8mT0RsLFiiEJCzVOyRVGjBOQY
hZRHIW51qYabXpK9+e/DM5iXvvhhvhd7k+B8s8B9NgAhn1ZazCUVmqcLXLLSZ/75
JNCqDIo4houzHaWs/BQTG8SNPfMNbLebpMr+fYBSXQ6PgUC0zK9OatMmu+k511tV
+6yO+EMEfehFLZXrhT+lSmiDPhjY+AKnpNJCtcVN9AHgV6QALBHExGJHtjcABEwy
V1XcvLyawfHlpF65rKef+h4fhOKdWDE9HDwxvefZK30HkRRgaNI1XrZ9mB44DpvC
H5uoh6sWktknIRSm5ak6UaFiTb1WeVZ5rg+OjqhJzNGqLnpvXVKSIesuEQjO8bTb
ChGqutEg7nXzn1r5rkmTRXnL4KBDnz2yoXCPBO8FGYVPKL/CfeROF0WVAPDC4fhx
pT+FhZFdcO58Eoll8C7eLfjC+HTT96TAymytNjptGJJ4xvWQ8DKT7bSo5MtmlWc4
k63YhnjiyKnWg0t18KaZ7tr6E82/tUQAUCxNE6EdADvDY+ACPwAcAnzrMDKafppN
dj9pmKrRgdDWC/0U5StLhw0gzOeDuruvxdZ3IPfdD59bheYZEJkYGpApDeeIpI3S
RQHK8lpolxxkj9TrCfF5n25Ckf8Kdj5wAvttZXCfMbzShhYKMSeI6WEyNehhiyE7
rcs0VKHfVxxnENwioUwDC5/5XatRTg==
=LJgg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2f705b60-a061-43f3-a823-2ae65b4935a5',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+KxtJLRKTBuJ8i1FjBVS9VRUpuE/VKtN7uWWL7SVif8vh
15THS/xsdu7vbpk8AovLlFuXfMvq9ikC/hKkXZj2/UF9r3lLcn8N7FD8CbJ6uF6l
Ui3nAwyg4weSCnKdBnpFKJx6djrBf8c6D64Mqv1BkvcWBX9x6JoyGYeMINm2JpY7
Q3+MC+AEn/na21Q0QM4MBaQXOs53owqYhlxHYiC57RnsZNY6YNM/1L3uOBIfZH5P
jX/Jm9IwaQU7Z1j8IjdJeQkNhgS5Hr/LWVcIE7PaxQrpvJDzGmVKoeslgrkd8Z9s
8OFxQ9a8i09k1uEseRHz8LqyMOxyikySsyq3empT7MdnqiXqDFBxJ4Sb/V3eG9Ek
IYobzqrKoSnlA65+vXSLBVVwQNzFb6266hzEuv1bzXdvsnk46ox1CyLcnwzG6S9/
clDKd3olEOxaqs5B0SMr7V5bIYU8adTZwQPivhuKxghzv0LtVbt8zvgY9cpoDoiy
DfISAwz6z/eGSfnTB9ItTiNj1UWYQ3PGLI8r5JZT3tDaaS6UCqdFf44c5yPuwB7x
c6N6Bj6K6y8oShhlCqUbuJxo6AC02/Qf+xY0ICVrmc7Kjg4SeiBvIGJ1BeJcWOM5
2J5i44j2HZOkTEB8HnVyLs7htVKiJvQIM3UAmlgE+4aAaZCJOiIouDeecYrdxSDS
QwG9K1ixPZN6zi0H4gaOl0vi6vJx4hNxsn0XSldtdEuIT5Z/oPmxLrGZ+8ocu8xw
odp6gmaE86+Yh3kx3ljD9omNt+k=
=L2um
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3848ad62-f44f-4318-a569-4ee9d8de171d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9FQ7qv3M39nLXj4kS/dIOeqOXu3g7VVGZzocdTXwioymk
QMi/zj7S3lKHRAD8QfZX4aASlXNFtjg6ZPTLWazSNgja5JSybI28RdS0L0yNr49D
ZQZ6QHZt449F5YUVv+8QPCJp+zjtXyMLEv/gfh4EltcIzF16VmHG2x3wwRQvLHsl
QCz30/rUUllYK1w/Ic62YXm8B0fJ3WGT6Zjwztuw88tGaLvdhLmpPMi4WjAgpIJx
mlUZxj9eA/CWA/g0++aI14mJ/HpMO0ESSraH82+apS40PGLf3HllMpO6wnfwj2td
SBqrBEq5Sq6NQ79HcB+Ra7Q546C15ZBbF+s54/lSOFP+Bso/WfBzuvFRZGF6klFY
6AKrB6Makmr7HOhB2hLrkLZruR5byMOQBXxRJyyUWcg7kH5KaLw8YcWcWioGvaaq
q/xZlEuAECg0u4jteDyqQJZjGo6F8lM9iTtdaJM0G6fjhniBSK2n6ty07YDnzKja
xpPu3Tgm2ZFZw2wDWNSXm0jfq/4hsTWKjyN5vuloYJnhtxcgLBNuWJZ87LdoCXY/
AP5BC+62LXtXSbhdRv1GSA2bTxSEmOnxdapJvwMWwjNjobeFAl4O1AeMubUWuhx/
1UN1TgrHL+pYkGq9GMalC121JUw3UqenlGFmCbAH8J3JlHxBZ9CZiIhM5Gm5VS3S
QwGHy0amVN/IvvDH5TBBTEfUKQYC6MWEXRnIgWoD/9qNzRubQJmbWu3RmM9GQmER
BhsNmnMD8j/HwfWBCZzKhG6ggRM=
=rdf+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3bcdceb0-8d2e-4dcc-aec6-775336644a42',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAg5S5efQU1C52VqMi4KAdVdycwpABO1VYvoqIVLNjP5Wv
RNruXa8mhgG+oyxYMs87P/o0DL00InIOluE7heWzyEAO5BlASy0Xb62JY+IoNCxA
sAPzGWALuyUroDgPE2BMt2ssCS1HD2ZCWJS2kEUB2SwuUujj9FtQjWCKspnuCyTI
qSicoT2aN5wJBa6FR0auuOZ0c76VlBP5HtL8K9Pbhkzr5Tim5hwl3le1JpZ+Ta7A
6K8SDnRvvZNWXWVeeyhUSn5PS/Vb04gpkEWMJdPk2Kg7Yi+DYsADlgawygB80EGA
+HXomQitDQV/ynaEytF3wplrcebYImxWlaE2nk1iGP3VOiDsWCUt0Qgx0P9cib3x
XSYfBmRwDf4UR5dEfYN/oJyFkmvbHxR/pIHYhymkfQ7rEvjruRY1lLXZ12fM3ZoX
vnuU+BDS3NgQpT/EEVFgauJqBv/ZYOw7vFjQFTDecELESmUTdjT1M84buOw4u40e
Wr5aM7xlG4TnstbVF/2UpzdJTabPMaz701GnMyMYCTYUHSZ9CHQCFqrqc7z/1Riz
i3Dq8V9fJO9whXv2npP38jrvXASC2AcMD2TQY0MeKaDdEGB+xYqOIP6HaG9WsU0j
IWtetVHEzHtzB46X5iRgkcr6W8ULB1KFudVZ78FMm8Pzh2f0SMQqN2qDxGDqLpzS
QQFYAKxYColhfmQ4JOzpQUSXTD1IF0wajz4SvbF4WqJyrmlHRBFhCptO+CrLTBdE
bQMagaD157hVLoD1dE6RSSSv
=EoWB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '41abb4a7-b5ef-476a-a8cb-d1ca6625123e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQILA4NlM/alsYWgAQ/4mAh1OG1De0a+w2c0x8OP5gUDJUfQKk6NcgVD24zg2WHG
1UchOGqme+fIyBTprKmC6KCSiGL8UwPheHvQOyRefAe94HhYmMtHoROpLCgBxAqu
ojaYrK4qGwGhlDs+itn9A2e3HPHiNm0ISQ0RqtmTzSznEgpsqVdrSzr2D2BxGVUJ
xPXc+E+bUt35Qk1Lnu9DGNfT/m0W9fY0dIv8O8+Bs6KgsjEhNW6FbI6vQRlvfv5y
/VVzkvZQIJiqgwq3smJ6buNoJcsA7E5DUtZzHOuiR/KIHnDHTYJjAXaBr4ii2Sn5
e/Ei+Ikjxb4ne4iJ366KzxxFaOgYKMDBSyNUiKv3fDCwQqMVwuFHoC+omX3Ix66x
+HOyTjPGr2lMBZ5YFcT1+9BylN7yQwtzDd3jg2NL/EnINaqAIUo5REorgoWRLRrb
d60vcAPM/PmKqOdVedpHQ5EXJkSkf8bibTL5R44fINqoFLHIhcP4ekd6wH1oOhtt
L55WOc/o0HAePkNtsli+x66GnOZGhG/OucxmGdDadqyjvCRuCv5HiQ/XM8p00Ncc
j3U+Nuxk84M6+DvvhsZcYDbCIYQq824vhT8y+2EMoTpuazL180xaoTz08690Kr6d
IDIpHMXTPVikXH1cXcejjHYysSliRCQt1fKHhhiJXwmbIrdknqu/VsYKyDl5KNJN
AS4u8LrwGZyNf6E/aXKvWB+inXh20EIZ/+b/0uXAndGeGX/yqW+TBVox1awkNgTA
7U5Vhx6U2IkZEoJNGD1M3Xr3iLMm2P/AJzIkG/Q=
=5wDz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4508d510-d641-47ee-a9e3-36a7339693c7',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+PmrSYR2XeHC1BBxJKGjLMKx+S7jCAMSK3gRFKtmKbD/+
Ra89kBs9T3fIAaSeorywMCn2Acj5y3UipxKFLdNu9+1yhJEtc9gfAWuHHkIVTYGb
deVlQcoNKA9R4y2YO3FfY6HeczyElOdBaQYS+Rf4KHFcbaBUkNbvrN84PbyCxXPO
ZA0neTCA+DoO7WvrncOpgQh3yClbjQTbRTXSiGIfBhMVK7CZjV1Tv5KceVhl1hXF
oIHQLy4WUxY0qxKie/TClcfpJUHhsKQR8gLOvZwzIao4JemAuzTuG1Grxmql8wPN
4Kgik6JaHXJiSyZXNO8fCfAXIO8V/lvRAcfyAPHQDMFoKNAJBBOSapUxeu5Ujmwz
3F2YX+lPE95gqqMsvydHrpKp2ZuNNp75uSPaSEU5hkf80/o4Z0ALP5sIkfqrpzG0
+xiDnN3KJn8udnM4pnkKduArlK56z9E8yHIAJ76hJgYoindIK7OJH0FF1iceooHz
SPwI1peaCjWGCMhihMG7sduLMNsJ91YXbAqQ7W+0RBfMIb+Kxj07TKJ52Jzgwnwf
fzNAC82ECkR0qOtnSuE809fq+AkFcfF3uNtaCo/huAQPO6CPGYXm82VbC+TMlHqB
/OMmfFsia6yG1jty5VGa4WBChd9h5Z4L2W42FKoUQH1bIrLqTkwZcvWMfrgQHknS
PwFRP+51lSrHNFismZ7inQFNydoORdG4X9oBkwf2r4NpWHIJJcooUpl1Q1lkhSDn
x14EKCZ9z1gxzY24JbDepA==
=rEjh
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4a7f1690-52c0-435d-af24-efa21418db01',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//a7CKa/0VBOJ2r/sUhiqSrnL+MwQm+EfTO2lQ+IflqhJb
JGh2grzmJxgnw2W/2WBEVpcRx9BDz8IcvIWt1dY63HUKRdbXWY1i5mRjg8zCVZ79
zORcAzLq6oEnZFwF1Id6QkB6Y6WBkLS0ay7WFzulfY9djxodub4Rn6J3aWj5xtFv
sCX9nL2nk3ZkdXSrpiIJT0fCG6YqLAd8IsvEJVXRabxl3HtMev4T7BzUfi9LsILw
lKYq14pcY4nvhlgyrP7AO6IuUL9g+5ZtOoZzRaIRdQdlcDvJLNjB9d5vesIYbC84
KUjix+NQHtrZtlm+Lqtt9IOy00/4TYY2NXiZar3VZ9eOh52+GAwqwkb9e25vx487
Jsa5t6zi1cQ37zkVNLjNqfsq5NXr5GXxQ1bBahEu7R/Nec9UQBe7ObzePkXjB9BH
+RNQkCI8697pBu3DbWzzU9TOmyvn0Yxt40emFOrXkULlw7F2lGcVuSCO0VQTRJ15
k6yS9j4BWGpPSpcyqPPwdPuwkL6t36ddEV/Cfukbd8Iy+4xGVcl1O+1t7Zfn0sOd
Tm0dMWLhud3+Fs6dbrapCDalYyFR+bOy+tyKH2WKfzVj7ZBQFV4D8lFJljnQhU7J
CJdMiSLLWBqQqBpXNKhGz/zIhbTh09e8gFRRVOLeTrB3gwNKmRRHstnH/ySAPgfS
RAF4H809wt24qUZ1h2uwo9nwYIPERHFzap/AOq8M/78ROTr+sU4BtiUdhP4QgwPx
y51IyE3bg3SVIJ6fsTnX74YBuZVf
=Myye
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4bdf54b9-7cb8-4f95-a735-dbec5864527d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAj4CdTsjj8WVChJKMJy+OdSeqK1pncg9wwqsV+7R2wYyj
nAEMiEb6E15+fgkg89VsnucTjzKV9AM0VRZehMpCC9uZn/wMGjkqAOv1k+9107hJ
9hNt/uGQblvb/ha3taJpoyda/frnM5LbWD7R4qfzqThLghxCENda9lz5xIPo8x01
RJpxQvQp13BvAQkv91at5jeBqXYmmg/Rl8Bw3JHfyRsCLCmQQj1XmFU4hFCGAbx9
m94M7dj5aGzF3j0qcLiYo8wLxl06s054xGS6qlCZGL+yUiMGdoYDzrwRsuCbePCd
75Foqo9eRJYZBLOpDJ6RVbzHuXtUgKh+BeQeAErApMmmf8MIfwleR60W3Dyl8rwd
V/B5hkZDFnZUz/8jAamw7Z2uEk1aRIsh02ciMYpZ84pJE2ZoR5UyOr27ak/HlWfn
hAHnvGv2pLo4IMKjPfOX6/06x6uiIUsfGoNMRCX4DKcxxNoAsE4BIPxg7zmKy//q
6ldSUpEyjK+Ss+ziShfrH7dVDjphM7R0Y7rMcBS8QOAxON3E3X6bwdQtip1ILwKR
KHJbMHzwHtByRVFS7zuGKRYMiMUVaXdg23GA9Yl3rQOuhnPABbHEonwnn7DxSCxJ
rCTUEsZYkKc+6X+jdIF11x6ui7Wc3CLqmVNy/Y7pMdAzJDou9IsgcvvOHvSp/53S
PgGCoA76mq+YeexyGgz8cm1W6E/JNaDnClh5LiP0uCHxKEYvPYhJelBcjHlCQ3Hd
k8mB9PTZNz0pdnMBMfZH
=mOuM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4ca2ac60-2614-4cd9-ac7a-aae732fd3092',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+P2Dsta+2Jabi0TDPX0IklHGagqcnMu+Glg7ZKr3SaZHh
xRHo4w64iQtFyVmamFkjYtNUNFi451ozz7VhgYDZbUDe0WtuEY+t6coO6GBFxeO0
HCnYK0Lq3J23LLpGi1qRk8oGNPHkGv8JU02xxpUftYxIvGory5AQhfh59s+6iV3U
flQYkqZXffPrAJE61J7FFkmTN0UKRS1JU5os1Btz9M5ZMk8wo1qOZs4sRM7ittrn
zcJVp41ZluEjwwrWkuZIs5xadvafmXPhu5yCrVwmOzh++Ksu84p5Lhm7zg0KtECf
EcT/QyWK987DNbyxi9DnhXcefimG1eF1WNgS7NIUIz3Isp/HsD0Vgbi9yxvNsaZp
aBxVrLiWqOuBPdmcJqLZicf8KrhySe2PS8LQ1ZhneoKzQnQgqyq1pGQ70nXzwD76
bPtAuQwK0xyc0NLP7ioBRSUTiRuRXls7fcQfKg+7uaXsgEdU37yhhG6uDTJi5u7G
7r8VNhDsFsacNWFmPXRDJqk3YACpRwefLxZAxa8c5yJwEQDZrDZ3ZsrTiO1tgmeu
SYH5KG8ETGT+j9v7tj10XgdiPDO/54pOEBDG54QvDTb7rK1bgviXLS81+tinkAsK
2V4hzLv43+5UeOW+oxEQDtd3PBzZe2KL/9S4JvZKmCN3mgfYdYq1mblz2+bT6qrS
QAH0XzUjB+94U0KdTADu3Ss/WpvVetI/Tg+zdUXgG09oIdzTUfqZtb2ePOtqFtNa
y+2VHuCmIwRNdPjy9PyJekg=
=6tAH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '51597016-3f58-4606-a714-170a4d863e16',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//SjYt1jhVADSv04dpRzqaCW5t+DM5y/zrCg0axlx1kyIA
QzzI6cb6KSl6x5Kw2idGb4JirIJkVVGdBpxxlnzElD+cZlRF8vhSrogM6u4s9nY2
gahrSvoedXGviflq44P3N5MTrS0Cv34LSNpQBddPqD2JEd6KRU/3NE27RPSJjChV
7DKu9R7/+iTqE+CD8SSnnFHXo26B3EVsEVk0YWVJc4UZZ8RYPi4lEDulnfG2CFfI
PONqi5hlSYfAvit8KCaYnDwdsVgFaoor0O8FmTEfbwv/kod+wM4emw8qtRQ2e0S9
E9MRsdsxVEAvP749/D/U17mZHlr9QaCf7Nwex1UfRvSYpZRzMJ8ZdNC4kIFf0Y4x
OTuE2dPdRJJtu93v5S0jvwDVQbbYa0QsIO2R85aJKLuqA4ZKtBwgb5Gb9KqDIML+
kzjhMgAIH94K1VQMl8kmZj9/GWBLuqcErf0tCyEaiDHuKcT8e9d92iXAzhdOzI6o
jhMw/iTZ7WyI+1q26006cVrYirTjseIv6Pkk5yPhgR0MYIYXoEACUkMRgoHBTVbP
J4l0s95r64J2V6ZGO4EvjSrpsgM8gNb1TPoyRqpRfjpOlEAEMjIhrT5hDX09zDsI
ylrVDqhF3uHB6EWW8CJyM7O3b/a766hcuHYsCUVOYSo+k3JwATan8mPjK0jMDEvS
QAGQn/k67Gz54ouHMNF8UHSXUIaWUrJF4xbDiGqnHuiRVS1FAHhdEbevWj9Iykb3
h+tKMlX06L7083SzufPIJiU=
=irGC
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5162d31b-0114-465f-aa7c-4cb228812fa7',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAkrqdMkC9xTPw54sFox14c9GIERAj+PhCCuM1j3aga7+r
1bOhfRg08gfFJszad/7lNOiaaZUNGXdkx7jm2W2Cwpi6maVh5R3Qeg97SgwNfyKC
feBcP17Spv7mGqF8eQF7zw7TkXONvIBSlh8oPrafNw/Le5+nl0LE2hlWL+ZNxIAP
fqdDme6oQ1xVB47e0j8VUqEqBt38Cez/jGSKX0hlXdYUrqBQ1gbCos70lZTIy0Cb
s7jJADSiqBM1U5kNbNjNV86NfXnY8JgPQ+t5zqCowVtmooy6/VJIXP3WXRXSgoJs
iMF1YRMfWDWEpwsoXjArhbbm6+oNUlR+s0dGJcf9g1WcaHHxpE+o5pfXGjoff8pv
oOpBRGew4y+Txw4YaZ0SJ2eohSn5x2UFfQnEr8rEkR92gW16+J97KWV6TztqWHSk
tUDG8dMXPryql6FZ0GHyG3O+QLVE+oTA+sDRTesydKoXHPEsFMin8yM4KUIiMy8q
5MUeBKJjtaHHHdDhOtMLTigWgQaF8Z21KCs85QbTmydyREaaclexfmutG+zoTgyE
GVUnzwVerkCJaHU415uUK0lUzrIz2QcxB+fl9Wn/RzwEa1ImBeJGW6JXPMxPiaxC
WGGDO5IySdBfBBTMLSbvXjqJdb/xAcBoqTg+G3IQphX9wfSw39G7fLt33AP6PCHS
TQGBAK+tAYhTGnRRiWk81XBWUZvtYKFcKoXDQ1xve4ADBKdwXdE53xbPsSbWO7DM
VrBhoZ7mhBu3u/MsIARBTbwW5Nt4YFe97gTuKeMJ
=N+K7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '527c7aff-661b-48fa-a241-392714f30414',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//RWMeQjT/HTqvGzq/dvsNaJ/8gNf2Zs6ss8rc9FYqOqlK
/pxMYiKDcVORfQ/7KHS6sRL/HF9/cg3YzextbtQjqYu0uVaV6ckDtxIZP1j91s3u
4C7X3pnvNPpEcE4Z0CDMmJm55yOown4XW8YOOaam1wMomCQAthxeRXDa5T01q3vf
4wp2amcr81IxfJCK/4NJmncMrU+dKt8M8hmb0jC+RNV7dRvvcLtkjGzvaCQV+KGH
YaWvBOU6uG+Mm7Us1fTwMetR9DsD8CD3RNu2dA/IVsnzGs3+ub7ncrzNLUXWcMCE
TcyFauoEvcg58ayROcpSfKWFX6yP68XVkR7Hvy/zCaUkl9CTVJwsPUzTL0yPreEI
wE1O2kvBN1KgHGIEBv4NxE6zIixMi+5tM1zr/hnrPDLSx0u0YYVSpJunTsSEXYH4
tA+K6G9EExwCMWBAaMVyubkVufM8WzILa6K3/DESQSmZVsnApqnn1LtRokWlPS48
errSrvvRAFZQYcybmTWQ+mi4L6LQXJhVMA+PpJEVSrMU7SgO5nrAQSFTfWUcyAfd
MxgZLKXHY2/Uvuc8rG/NOP+a74lgYPyh5jPZaWJIHnWC+EeWOQeq+U+h1pmxqKi1
02+Um5OnFpnA3m7bp9f/mnwIHMkvUL2DXAtEp/4Sf7XWI6deibqiedA43frtd2PS
QQHcQ43ThD24kgVKuWpGKHO/xFqlI+xvHBs8Ei6useMmXWQQh1+Md2ocdWL9/h3X
v7MTv2bFe+dFaKlosVfMsGf4
=y5WE
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '544363e6-d562-4518-a0fa-6dd1d5820708',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//QXoJN1x9OETBjB5cSlPxdo+vV363qF/J/TxxU57uC1wC
FPj1lfcXCCGvdFOuzS0r1ouUhL2WZ+KEfswlcbJ/bFqQSq6FarTN9OzgitsKhHlj
hRp7lN0I4RyhRbgYWKTH9o2FKccwxU069Uv6Q7ayuNZnjAMYwqdj7h9Uv2sklyfg
u9Hrqo00zsRSO/nMLthZIxYPtwSE3eSqq8b74megN7HYslpRSA1xC9n/78Z6OIsp
MPyWARB1rNM1uJvqjBrihqDCQ/4Skn/om0Psslr5uRPQeMhVO8x0n1MeKcLJeOyZ
IpkJRgJTWBGR7b1Qr7TrahPqIo1vSTWxa0SvQ7jNh7aAXZDur7wULXH5wUHhc4nS
FLsNYaK019cAxh6rvrZ+96795jvcWZCARRIfj8PXPvrtRxR6SxtOtaOLR2CrrHdr
KZ40e2sJMjAgQMhXDuZA5ST7Y4zv1GpsLqbJOxqMxRUAjDwsnfMRrFJz2iOzpIX9
KXFbMY7d7FKlp/r5d4TSsRHtvz/iSo8EGwOYSFpLfIbzoa7+y4x4Lehrj5TPhE2L
X+LGwGrON2yxL2sxiX5151N45ThXJuMUH2mJgSqfjZMESVTc8AE7/iXrVi9W3goW
KzNwGuLudjD0xux0qNDzcitXKujLeWrc8kMSWvNss49QN0VrpF0cV5tSSnWjP/fS
PgHNbsmZu9LaBUNe4nyFv5ba60CDx18zBwD4fHl39hvEfK+VuKGLopa7VnClLWgz
BdcwZJ3S/BAasSHaLusn
=BAED
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '552a666e-159c-41f9-aba8-727bb8791c0d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//X4piPdwyUZMHykv2Zw4h/V/7o898dTlDAn9wzDyNW1iB
Y6qVVwtTrUdqAAyzT2F+Xi2Um5OjkWwnpf8zJDq8QJITZ1d8aPgiYojAZHV023Q9
8IwdmuHNewGIY3I8pmNqeiW5tJY9V8VwlXkVsZBhiBvf6enQAiHLMdX6IMyWQdHD
PlP+T53RBn7xGiyo3rYcgrXERY4Xad9Yjae9wp5g/O5jMdg8p52bsmomZAXS+ufm
3+HHQjHFBMzxjjNBVhbM8jOpCqHmETGY2mS8ESt9L025jU/ScGn+/GSqqbhFH4gK
23+1KMsbkdiBW3SxX127R7uHQK5a+HocUwrHIm71uXryVta8mcpYUIiFxs6LtgEq
ybX1cGtPNnwWlsm0gQlIJcmq4R7o9trDKavBSvG7qa2atLOWIc9RKlrKUfTKsQLy
uO9j/ran+kibQ3emoRyE6vwxa8rjTBYb+HZ3vRm0pCNjqFX1iQkTMhRI6S1oXvlj
nKqhNUXehtZZbleaER1zca1aoVqIuEZq9i7tBmx6/eaR6ADDkAl+3xZ2LdhbqGyv
oTKuBmJU3aYQnn/tbV11hulTgLt1ZPTSpRBsxOj4aq3ESgmIjNR0k5B6kMCTUQRs
ISuwmFbbc0489o/XnpYWg9oxg2N3pQlk+8azkl6Kg7kT0oELmQ82ZvjljuhjvpLS
PgEzqb8P8fQwgMZ1u13z6auMZg6z3bS5eBXK+YA8T+AaDJY91On/FA8bztSEL9qB
Wn6BD6OQ/04bM5Y4k55a
=vr5u
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:25',
			'modified' => '2017-02-06 14:10:25',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '601d6050-a321-41b2-a722-b8b98c7edd64',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAwDayg1rl1nC+hMmANxzZTKPJXbWia6vDdt38JR8IIv/c
JWlrcmMehT7Vybciu8wz0hL2cCiHEXXC0AxCDphFyHsk9v7XA9Oj9MsqK039Yll8
5FjB57Yqqc/R1E7VrQNERFTfCM752e2yo9oBBMx93zFe7fHvxZJymcgvCbTekzVz
OgLo9OkMOLYAY4paUxcjZTOS0RV7XYp/ienDVNCHYdOdbff6MLuMpD6QZCcsKBrt
BM5BrKE3jqZCPDPFI+86NFL0OaWth7/lSisK14D3vU1tGekSM8MgYOW9So0V45sY
51Sz9NxIkNTSCvjWXqu6tYoU+Q/Dc2PF/wnTSDr9MMlqw/DOmQGoupl7smZNVDS+
U8D3f9Tkd/NKmscgOlICE6u4Mou002DEfC8X0G/4ZBWIiR9MoMF/GhhSQLrcL9V7
kKsf5TUOsN/I3gtgaz/d/XUrjm3zNcq5cI2ZWGTLxpFMP4p5fXLzyeAkUqCuvTUo
0uj700848X2jknOx5ERPo3gR1d1xuLYQJzhLXlnTuVe7oY4i0WHR53AKEp0QDE7U
a0Om9Jdexuv/V5BRliZ1AbuCFmB76A71AA0ORhkYHd5CoenSGVJw0qK3QzICDr70
xO1tSu/wTdrq74AeutBpBK09SrcsPHYAsBpffX6hRx3OR9vufAm/v+DBF6lD3IPS
QAFf4xkomh+IOHquJVnm4S2cu9PxhyI362sHINE3SOTP3Hf8JKosuFC4kUliFqwR
crN+SlrrJsWspbIOUx5msNA=
=ui1h
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '616a01ca-0b8d-4d1b-ac2e-4c884dfaf9b2',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//fKHtpdH3q7qCmZ+BDK2dzoajhT9oSzw1KlDfKE5q6G4K
e501BecY9RwJlXZQ1x06EkNs1GMwprntUccXtUpsiRhar4tGQnUthes+vDLuoNmC
aSJ/idDwVghT7WMgzj18upfuK4ITLHEmdBdPFpM2gMSvoHCNQ34MbL8ebow4QsCn
uyH2LTwSDzbDCN2EcP6FqzFlxCmTEdHzKhA5yO+SZzPL89Tg9q5yijUs/OWIiqxP
rYJQqnplpG8iA9i6xcf+ZsAOsfpHb8ll01pj0EEMue6oTIlke9uOZ52fe002ymCQ
uOXfu5ztRd4zg9XeBeTguFBXGVmS/UlXScU0kh+NZkNZOh0+mM2wZ+DCZo9jZ+84
wZuWCSj2UTRRJdKQO46Hsoqb0sNEle1jXbvE5qS8e++yFUC2RPBBnCmVUaIznGWo
Hf/k9egT+ibcRMWOrvtZnh9HlM/2mRv6WBI3rVxH1GxxGNwryb2urmS0ZQIGISyD
NhV6xKuBbjK7oP/xllz5W5gRKeicQp2kZzwOSEufkZ3ooQ5UvvZErw1wQ/gBvrpt
G1o5k2uU/fMjzVmk2bcFhjQ268ZzfGvAoI6vuwyuNUhZDNLQF7bKOay1qrRicKzP
nldnszAczridcbnv93kC2mZuLXO+WGmHjd7BwNAkKGtvpHHcxzkH5ooggAFZpIvS
QwHrFYMImRhHFZn9QWj9rKFPwCGLb+qk3rZRYcoh7mo45aSj/IBdKBP6wiXbO+fx
zTOuphHqi3j6NNVtRWptkP606hc=
=PHj2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6187e50b-349b-47c4-a0e1-554f00b28a80',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAml/Bad235O8RksxzsrhZwFyvHlmphTRgrGMs63sPYTvt
ZyTb1FstDS9Upd7K0sWSbqBR0X6TF9BXJeZ1yDOr/0KjZfgJZM9XptjtVmubM75A
Wl0z618sPwxBYzMPNPNBMQ2V6a2lyAfW8z4oBc4hD6oYItnyrBUzN5VsZY1Un86q
hCHwihAjJwWu1asDwOHgggNYtQygV2XvwdmidJJ243R5nDypBDqGdp+ky7ipR3Lo
xyyoKUB1Bps9txixtPkOWbZB4O1A1lpZYpVTUvNVv/lAeOGdnnXmWYsZG2Twl8T+
iuzMvAeSB54T777hWgn0mkZf4iySsuviyZ0XXPtSsWLgnbZbxqtRzwCM/PF0GFcH
tM9cJufgoqa520DHDQlc9Jsp6hHeOxIw1H3vqRvpurDRJ6XOsPAD3DFSM0awvHqY
Obc2//zjvTb23VhbvjccQN0WFK3POLs2CT9i7lvHEBDhcfl7ZJitdot8Y5Bwxm7x
MRdD5NejgGW0RNEt07YSYFB80sAs+O2+aoco9jpS+LhdX86CBsdupac+d1fSDm4Q
xK6CjowfLu/iKUt7Mldvtv5aoWXfy1pbRrj5aQPWvEq9sR1pfuA8eQqdPASLIOxw
tfZAlyiwb0XEfZAXcfJpVqREebDTOIS9clkBJIs64BYsfxdkEoqMHUovrwruhMbS
QwH8byOwYTu97ahClbqFc72FH7oBfGaKj4t6Xv+sVx7cALaY9femGrKnHlj3z7td
Zm6UkWEDqoH4ZlS57tGbLFC9/sw=
=2KlI
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '65126dcd-25c3-4173-a021-24902121726a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9ENAW6Kmu4TgzRLNrfXMG2EZb4h3a+UMG1di5xlaDi+NF
/V/xM8wzP9uzCmC2NUR84iPYJbOWzgeh7BNgVOwsUw/HwnH5aMg43Hjzg0B52bjZ
R7bYsiuvZI/6sKTUnPjMCxJf4RkazVsJZkoxyVr0mgNWuC//PwZLYOBjYanxcjZd
pqVcSa3Pup7uUKYL8XU2FMfI3k3alTDCoUVzDl2UTCL/EN5+CBpWmMNunkLccl9G
5OOo/g549Vi2qhGvqI6adpBgqFAuRNjKNMFtMHhTbC47hJ3sIF8fYD8NXiDy56cG
+Ls+VwKhWibxtpve8q5OsNq7b1UqLJ3CnmMp6D34A+/op2GPuzlDbQ6O1AJq0whF
VbrYD1FgeHjQkd6BAJdVRGmj+KSgTH0qjgQa8W8XS2Extoo6fc/JYmCL/HjSxqiL
agZb/0bvTbmD5JqeWmMv2MXskX7ufXYb0pjMYIcID5uVIoGPF4hwjIh5/Gs2FSea
vSwFHN+Qmin7Pj8GT+94GAcHHcSjPqKqN2ehYt1iScajJMHlPlSHQ4NRymDHWqOm
mAdQH6YSBDfZfKfu+5UGg0WC3yxFz9zMKV2ENSQNR7WkZwg/lLNqCzIbE2drJyoe
Gf0NJIURQFFGTSoKLku25q8mHpO81Px1AKQ7qIvwbPEl+ZOruYnW7Xw5guMdzUjS
RQH8zdyIWGwRrHX9HEZYaZUbLCzq1Ogtb3sjQn5uYkTXbNf2UNa/uMS6q4rx6FDK
M9tPoxbVV5XJgoifF7g3zUC296wjmw==
=Kmyc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '67c501d0-1fba-415e-afc6-3171676116c5',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAidVNK7wC2NeW/dg+xYgOZKFTiJ/gSuR7ilDyfpbzl8nx
CF2M081HQRnKDxEJnptC6AJ+1h0CTBshAJkoZc2geeRj13Ypd4v7B/kDU2XsQnvk
hjqvKwJ3d/KPJKkHONyrRXOzuSf6s5WMfgtAMwh5ncpJjjoB2cBsUiMgCW1DhXK9
aSFlqTOLx4wUs4AS/ljvKWu0JaFadBDhqIfBGgTqpQ9zq8HrU+97Sw/qngms5eTM
RrixAui2CEVvDTAaOiUT5/zkZq2gncIJ8u0zoFCN3I2kqlY7XT7KOo1LzFvgf8H9
3YcAWrUkIdE0gPms4fZeQJ9j24U1uDay9/XOf11uHX3GZ1jyv1BBXYO0zKxdubmp
c1hluNEReKlgbDHyw6qWsOF3VxC2XBdQlSctZ+mki04UFuFAaBlQL7mWT5HfEJlj
T2l/zpTblPyAGCFIygP3l7LTjIqgTnHRaw0aN643AipM6PaXSLPJBfwZpkS317ps
bmsl9F5N95inNL+sVNWUZmR45/JD2NUvimc3E6WwS8jH4tGQfwqTpdkVLWG/Eac1
5zhSRikFqmPJ/3JhqsrRD1wFlHC++enrvjGZeCyTNRUDsEp9RuOokkkrj7KNYEVD
FtmF3ou+avm6SImIkGXfJYpC0Yr18QuDhuu3nj8HcLPZd8L/e2RuZMOC2ZpMuyTS
QAEQSyvxaGyTtJS0s2PBJ+XiE7qxu4jRerTbDlttbiXHFR+Mn3FUsyGMb9Yfg+Qx
jkZPlW2R87sxy0cvQJsU3PE=
=sJHj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6973b98b-4681-40ca-a0cd-f0300383a43f',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+Jvx/DipskpbwnG/Zvr9uWfVHoDeqRuaoCAlIvj32l/sm
/F2+RHmaNVJzgsQQNQKg2xOJFoMQJYg7XBL6ZqWN93c0sKcCymOhN3Se6OX3+u1+
i4dWM46ZXjE7PRWspABfzw1wGPOBDZQtQgTeRC+CbswuQ0YgBNJXD9FvD4kJKkhs
j5Pt+ORhP/qnkuD/cno+3d+Qz9oATNlFHUdanzqPGWetHnUjRW2asMx7sxOIlLZu
pQU1IsUPpIgXboWkfyNCHkr/McoLkXrJXmTNRXPJrdewifu47w1MF5zZaaLXFWuI
LAnXPnvOG0Nx7M5FKKsJliSRplxdk/ZB7hwqSnBtDBuzG7zZ1uk8fgx1KV78lWbA
CPWwOpCAw9XXi4baTm8WZYKkM9ubSwoAtYjxO/S9wCYqqRRoPErLi33SuPq82P8B
+2HYcQkm4OHlvTFxC/TD/CU292LOo3BS779tO+zXHQbS0fwpkPCicc6ZAjuWu7XX
R/jfBM5Um8kFhiWFfJpnXpGjjyo2yl/fw8xEngCYVHSUgx6UPZvh9A//o9vVph7w
54QZBekmF5XuUJkoDoCU03p1Vi7FQMzIxcjPif0IqKs1KIdh/kgn0DNF6OqsTHTB
tjaReQ4N8oc5HnS8Xb1ZuiOTfFBw0aXi7NzGO0CLxkRTlLXP+2MvTlC09d5PyunS
QwENLcohtYibL3hOSop8dag93OObDR/kL0cyWSRfC3zapL38T7vEMP1N87EHc4nY
oOM0xS0+OuaB0vA/LAqI97w9Sw8=
=XF1r
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '69901127-297e-4a5d-a272-db449be1f4ce',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAhtXO1SIyA7xvlrOZuPRx5/VRPdCKOZtRVAPJyZOvbxbb
tIORReyUTQOdz0evovA09MKbzYs4FQXUWJShkRrCQJU0Q4S6zfI4e1RukpVywgCs
pQcdjdb7Z/Zh9jKl/lbwd5ahW7XSDVL8XNhh3bB1EUTIEqEmKgGRFb2AC63BpZsT
/KJ1t1r/yvEuUH70ZudanuGTjkZhk7/Cr+7U9GoULGHNKqZCr9eZafpmLlnrtgzt
RSeOoVIVLd9799WIekkBuNBMN5tbAFRnMNlvWM7/tBHBxBYIJmTK/imlMjUZBT8K
uro/5xUxZr3/8RaVicAuJw6xS5qnPg0nmRtfdEEUzKW9yho13fbXvOoyXUC+eL7M
/qoRKHy55BAlvP35cV6JgFtGQs4usrefIEB4k59+073Gi25Gj4LESeK8FXVtAjyx
b3mHGrpU7Jedb+ZOAxJK0jFCPD0pQQ+BkJX0dEL/F3n71NoxexqewVpfFyWcqTGa
PxgT7utj1NrXakUVI+XOZBN+ogXohbsn+tHZmdMCQxQaT4Vb6yKJmUGzYhHZJkTE
xuAIJW2BAUw5Y7ZfkIlRbRm79sD2ZjGwN0gUeMGVoW0WPyBb3VksEKsViKLbD1YV
ZSpDW63s4WYxV/saUQjnnDcChntY9TUdiB2nAdyKAPL8JFwAveCxTNlmq6UIEjLS
QAF/sPYLmgWLS0q+XlKgHyce23VKZat1ZQXoE6LmQghdQ7vHH6RqUcp06KpHz3HY
Ilryh0r7k15eS0yH8jjfbQQ=
=sK/9
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6a1e1e3a-8fe1-48c7-a870-4c0d7603dd6d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+IZsdhBmi/2KuvJjI8OvYBX2uVlguG1hGRRPUvtM0bhYE
ZYt3XyKryzZt8fX8CgSbPAlLfz+vpYvnXSoSe85XPMiVONrBZPnMTjrFbiBulcA1
oCxUq0iUSxgy/mDDnMkCBFNLiNj4h78HpDYkhMSIxuljBSXLFkkHDIEJEqhGayDP
+e05kzWrx1m7Ra/kqfSBlnJwpBn+LMwYRzbTMaPOzsWQdHPbqFBoh4zFeHD8XZGe
q9p6mOAtD2XZRCDLqae5Un1ZB6XsTLT6xD86TxRJHr0fXpjmsIyEuijjTliP5+2Y
+B3qfR9YjyFvpieE9ofPURKh0qQP1iHLyeePrJgXiIbWZI2F7k8VzLA3TvaNUnUN
rs6WCnhT7NsacSuoY5pOTqQULV+cKJHXZ+aB8/AMaaW2KQOroSQTnVxs4DBwz+8H
FgaVAaH79YRCwcrKtzYVB5vopuKZZWsLAEp6qYFduwBblbpv/+ipHSV61asxE4PL
HKvn6mEWMftO6w7Ku6zZBfXf0HWbuMy3ITOPQ2Dffq8qyY4cainhg4nwcc4k5u18
TOa32D+8dpk378Zzqspap7uGKUITXIxJmVc93V3nNmuboyOFMjx+6O8Y26WK3bwN
K6ZocYx2YKyvHYm6AziEXGq/klm9A7jnMc2HQC0QKJwntPYNBR/DC5I1IlBXryzS
PgEC1W4xCZxOy49QiXTRtEYA/n9ljLfZABZMhJKMtlJ3UfOJxEVbkv2ZpTN+D+fO
XP6Nfxb1rzucRBS0NK3W
=HiAC
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6c4fe2d0-cf55-458a-aedd-a91a1490dfb4',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//VG0H/9bBRofdwEZmfS/ZniJNJ0PhUdnkVz1gBx80UENF
DRq7Usb6v0Iw3tz+iiQE5teAehraglhmNpzzJWdvx8t4mpqBlckzmb9nLDrTN8FX
MP5lgjt6++HcvuRJYMRX82l4pl1WbXiQNdsQpY6zRot2Qvul5HkG2MBIk1sec4w0
XM0jTchV9e6EHVZts0P+r8tTx+FG5Y7Q6gkZGeXltEH7p/UHwZKdrm7YiRqbKBvh
9d8c+vpUxyG/2CCFiyH6zBZiKOvS1vzMogbfAUa0Cy2ccJtwouzqlDSUCWI5HY0K
mJbAMJ6agMsWePGvBe8F1bjYsitxWERPVqiu2wu3zapCn4N13wPvG/lYLNpZnZSd
3GKy4wuKAeIeqD7qBiTAw1+3Elq/kyyzaNeLec7SS4fhT5bcIX/1+5ZHe7FhvcmU
NyCUOk32dCzN7SKrEAjTpm2mM2xrb50K6AXoDBxsY59BeCXa5JTVJMaw5BGE+ZNw
wwpV3MC1/938HvtKT2XEzmnCA9JsO7Yij9P2U2hOlqYK7H4u/HvPLC8+j7hlySTq
xETb25Efjf4yKQrmrT9v4T/ROmnXEL35CPQ8wQpgBpfRQT+1KiCSBCgqY/ZXoNFm
8CU+SruwUT0vJgYW2jvKVTyMh3VA6pkXI5Q9RPeqJSJgtEtAX4R1+Ut2o7Iav4LS
PgFvV/9cogpO5rY2OTdPU6Wm+4GhxXOzsGiKJ+0AtKpuVdQU+lKAMvtsdmsNRdfu
dAb0fXvPib0veiQ2ka9w
=F5tO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6fbe602b-898a-4067-a8e3-1c68d39503e1',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+JtPdkFIM38O5f7yU1tzR71X0txfaeV886jCpW4y2pvgG
lVbebcXQK/6til5gbN8Of6d4jo6+YddssI4F3YGymevCbUXZw7Bs+R7ilbTJL3GF
gkz47w5mOZAGD9lf3ELUf4RgcLVYjSgymK6cZbMnhY2LG0MY8mTiiGmyxoo/Kz6x
lPTPw9VRP81mt1MPbKKXrU5eAI1DmYJllqOL4WN++6AXtI7kn+Zldm1Nf7GDbSYW
D71sa0rjSMWXa64pksT8C/Qvo91fcQUfcyJI0ms3FJVVV9c3JzS+FzDBEH/ufPFV
IJgQWPoby+WtxgjSPxCSTbB9Z0GENq5z3+pNU5zBJdMfDHuefnsGwaXZDPkbEZUp
BEzXj54Myw5bYB3mhFzlyM4tt6XMWtiExVOTv4I2m9fg0VYWijkgtzDrcijBol0d
zmBLEM3ll6JwSzggL3NQRAxS4V3q1jn/kUOIfJDJQHTlMjfRb2OryutmvbVrK7sI
aLY6g9IPn27gANGYryjwjAidn+bmuAf8bXdwyL8Bd9HQF/naNaVTMEDR8PiBh2Xj
2JqcOCav85SEdSKZkfI5VichHlacjEocuouqgUyw0S8fwy/B50zSzgC1Tep4KZWR
Ao8z7cxaa+Gmn/gxRDaKhpAfl5p2iP1Gb/2QIM0O451VjjIRjl0aa01BXI4WhrHS
QQGZVVNQ/0Jw8xoLvCEDfT8Z38m5RNILGVo0/KKI1nGOSKi6iNm49M8Z55Tcv4a6
2AsxooneNc1Jn3u/w0pkW8V+
=GNWG
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '76e5f1ef-53eb-4f92-ae3d-06ed9080c22b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+Nn8K9qjxCgs3QqzWir06MHjNheTR6s9heyqhBVxxRnKR
siXdYILilOx8tB8cG+sOs+HbHNpkWw3knzHETDEoWnAhZmkRcx0nTBOXNwTwulxD
v1QP2QCZ/xyfT3z8lZEhkPqJurNmhMlNYTJ8fFJYgO329KLXO4amcg+Aw6Rimzab
skbM+XL5XQcRI7yaPpW/YBhf4InRK/5yR8UV0SsPIJd0GEmg1NdttP7I0gJmHEkb
50q0IA7+X0lbGOavmc0a4FCTtWMv6/F4KS2XY7m3SQMAXSC3+26+Gdn6xnriDeH/
XY+LUJWJY8+ox7wbYyXDbWBgFBPdANL5KhpYsQEUzcq3q64Y2SFPZOMd2JQM1kgP
1NR9VlYI8XoZRTq4IquRaAaQNkUtOC3RNV31JXNzmUGVO2vZrzUQ9/gw65P2uWii
NP/hoyepCyMJ2cE8P0BdRs5kx0gpURI5mCBOqSQLVPshMPccYuVLM4HGQvBc7yEF
eUW4V/JCePqb40TSpNCFfkEnT9+esi5gECXEByjR6DX/4v1vHcrihNYmHlU8iPyf
ZeNBxQzPaf6mZGvi+orGcDuq3OchmSA1hFrLAVYI6E0UFI5zENq9HghYe9YdlCdK
e/i+uhKfWBmoJJ5Z0ZEQGsjjtKzQu8GXM483UkaBITcy0Z2VApQvFh6xUSi2QgHS
QQHuciUDo5sgkvb87Z/Vs9zL33KmeYpslvVBdgUPWlZJYcp5qDt2SGPS1Bn0sJW8
scKnmaI1IcRnpqxJi87Bxt2A
=7hta
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7a84507a-f8ef-494f-ad36-4a804ad45f30',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//frT4lWxchI9h3D6mlMrOYsVeYkBEe4PMhkKYwl6hkbkD
PW5PLsB3goJSCmHnTF7+YCHUk9b4QpkPjak4l9U6e0pB8bbhQ01Tmq4gjd9SRpES
D5454Xv6j3RdeKgKcCbzz87pqRyH/pPA1JcREYCs7cFhUFimom3Uj17w44oLxlLm
hxeDzCIns85z8/xz60XrzOVq314wVHBssLC2qAkSDF+7+7ktnGlkDlr0mAW+S2Ro
KDlQtWm5rqAnImGoZilGw86SEY3LvoSneUivmPwBibCRTxUYK5bIkCVzp0B+1Oa0
svNdeTB741sfIWbeeKrbxmEOZ363ewLL/KDfY0B9OTCtfnKbHVgGV519PzAZYp69
PPibU92OiwxO7G3p1COOmHnPjWzikScMcQFtegdyln6cEm3UqiD6k8lq+yOB61NQ
UV/nR5PPKrymKVPoEKSzrsyRPnAGluJWG4EbN55KQonPpf8jkaYjlTr1nFwJu8Km
+Aj0Kgn6gNnp8sUvg+DZEP+DREhW9hZkdSPPbpJdsxr+2DhphUYJbFMBOa+lahL2
auVM2r40kYj8Vg9WFo2GSQaKvy/GuiDm5Vjt8a24ga885AGbOB9NfAqbdIJ01wuN
e80lwv60Mqm3VUHkiDqgIjZQtNI/Eg3kLI3/IgU3924PG2P7ZoGWpYogouUBO1HS
UgEMfgSQy7neeZ+SDQLGda9N6i7qFncysLx/7vK76tKJmBRvFlNGYVywu2bUtd4J
PXOEs9xi6LC3tBPXwHvtkvRhvLBrx2twI5HHyAnWhwtbpBw=
=dvAj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7b780a20-833e-48dc-a269-192c0b0e5553',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Zys/H7A1NeW7oWmiFC/G4I3p1WUu1JpnIs1u/H/X85Py
rSRy3T9Hy2MXstNwGXKWdVtBKUMM1jMDP0bvc9g1DGixh/RBNEx9DjdCTtB+tuWQ
3YATsOwFlyqxgyjjgr4tb+vRdgxLbrnEI2gfXsQnaWFcPNp2oobvidnVf2+5YFfD
VYzSIPsvNB/R9/fH0XICOPUiS/n2vIfELFLuBVKRlc1FMLzxjQc/ny/NLQgrehs0
jHxm4XVhhmtB+ms+gpDeaJfGvrB6sTKkEJ0k6TRviQPdROJIyOLddUwuEXdbtp+K
spx9fm6ZDfUMdWSVqo7lB9WxkppxGzzm+I9DsOyP28oppZ9LHTGMfMgJi8pXmKyu
hk6ND1EG1A7mcUBSdD31Hnvg/gzwDUcQa0TSGcYSBDvo7vA31bfqrRnYHT9G+b+p
9Ug23SEtdWXHdp8XBtOTIscFR4B5hiYoVaIOFZG0/XMoE0fLsTWgBpI3grffotSP
0FDYO9lMGp1woCeQGxgTp+hr2YKnjya1aqG3AI7GDmTou4TyrQTIASmAXhPxBIYf
uHfBDCG36rpEvijU0nXZ3CtAVJqn7/zdzZs2SbF4BXtGdv+QA8ND0iDG3uwntQTm
B1xS2JHztW6jXcgqKialxbEoY3zRjtuxHcZ0FVx/9bUNscH+NdpZlfF0TwpmmQHS
UgHtAfQSXsiKnhHMMwTqbyA6AZHph5ESQRYka8fB2U3e9Q/jLgqHLUAZpImXej31
4iI/bmV50fCTqkEvfdbDQtXxc3UgBsJC6bq4VjqCROheHVU=
=dd5J
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7d8b4f9b-d32a-441f-a610-e9e0e27869e5',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//Qh6Tuy65YTa9FqWqt15j1E4Ux6bXPdy9Fr77GyTcIVrF
o6IlMI32IFBGa/rBIyMs+J0HwJMNWBJ9vCJ5I8+U5LVe1a2sQeedBZyVmG1KnLUF
7cn7LXNdVsifJOxB7yD2syu4s419WMU/Gt7YayZxrT+YhL4vUxtLgwTmfLoCxJqf
L3AKJGx7COZnrvrMUHicTisSU8ld5dmS4aXqIlNc97/6nEUruUTi9kqX0SZ6JMjk
ux8v7MkyoxP47JG0QCxcdZlKWwHsZnoqo52ekUo8Rq2C0zt4lCrM4+2lZbNrHHEG
ES5+yuzdyuqi9V+kLDHcuNB4LkBIvPw0fD7RqDavvd/wljaG43ftCBWPKborn9cb
wi3dc3V6Vu73lpwz09rFOwgvMA3Uda/YERj5WdIq+ec+MdZKsjOHo9C0+MDYQkWR
8pAV+PEKLdUhmKIUkAU2pmJQpWnbCqZfpnyIOKCRT9qzu3N6WZdSacdb9SpyJB8k
VZkv15UcYEVK+JOWsrdwtDg+u9C85KfINAg3D8T7/tV+XBTFNvH1svq/I5UsbN02
4yolzm1rC10uDSlRi7vElJye+siJ/haFiv8tbpdQOIgY3Ugvq7l3kGFVwIMi+ZSU
ADaYl+ycgHI61K8/KNX+AyeSyR+9BcTtQLHg0sxPFYs6weW7K7c/ppGGu0LSnKXS
QQFTinjaBU1bjzYU4z8c6qUPLpnQfPePmYAdQnNHiXqVCIONtZecFSrhg5Hyv8WR
h0Zxl+sl6cJZyYZt/ea3JF/d
=xBaY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '80315a52-8a04-44ae-ab04-f89b889388be',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8C6nj6ppLo/VJl0/mRPykwkvZsaoTyDOfI+LuR6/bwX9K
y7Fh3PFteRQv8meOKLpvDeNEWtLpICa9p9kRhSKY8mPv+i9rJ/8REYVP8ZHHb1Nd
IQQa48iW8ObW9TLLsNnmO6QLJzHVizA8cQmGnztMLZYcf8QLJuqPvB6PLdQ36eRM
zhLFAy2hv8WbReAuCqURc/0i5SDXUYfzv+y+nwqL85PYARufILqd35eFy9CKCRP+
UyOciqtJaCpQr2eSEJrNZel8ypmuGALT8rB3RMFCKVzBIxnyCVZd7Q0nkcFWtlas
0mJ17rMDDf+EB7mTeCx8d+o0sg+JHKGyP40JYTjXbIc20giRl1RxkoL7ZfHPFodt
CnGl3k6jrC7fyQL3NdubqGstNjwBEnqMme3iqJaJams/aJXu10t0wm8M60igWvgn
GRvZxCxN8s22kwU6TyRWEc0l0qyyU+tC8aduc4LO+WPdAJLPW7CshL4aOyAUPRXv
9fBL4De8fYf3yv+SqWic14arysnorZhxtmwzmjWQEYvwVEwa8O9u/dTMjR4b7DnZ
mSGNYpqJXywKZMwbAbwcA+7oYXSl7YGHTRzRaiGyTBYGz2GT0oAXT1WkRKxD5bJl
6HiWYIPKj9414OheN6xdjmLb5Gg1IJQoekdeDy54LHsdrfuxYTrOl+fq8CSaNT7S
QwFt4AsxTn7xfFX1YfuRRxC3X3WCxaDp7Q7p2GSa3g+KsWwq0tXnL7sPyWEn4gzP
Vd2cbZMeOTKnCH3OWHgg2piB/EA=
=5wuN
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '83890c3c-eeaa-4f96-a91d-fd3f48d27745',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+K0T5UCKWnMPSSTudkzGkwjIfY4hspfEwIzgoX+9uGSYf
C8R/osfIB5HvtJcOF06hs27mgNO2zgbiNghK7smoloJW7wbS1pboeB1l15Z/czIH
8/5foVrsEJkj1m4oGFEvPjBxzLWblHEWItzaj/aF69NxHYirBv8wNp8R6gq5hIga
HnFJdcMD/pGEF3DMyoEAA8AHql+8uIPpE2GOw8V1JUC7GX2Ot2gDLRVAB+F9Nfb7
JNDzsdXRh8rD6z6FRBhoQybyi6ynHVb12WLwP2PKOJ6Tou056+XMeTd4JnZ0SB6U
Qfk8CC9UYxczdkTsRNXeCtsJoKLRiIxd0xsveDIcVZg8kD/pXjHg8w/FUba7fhYh
UmzippRdMxjR+FClFogmvCPWdO2LZLvg6nVoq/Mukzl8ailvhRfhdMs3F/rYla39
pP70R3IAPU2Fch0rCo7Lc27/JozX9uMfZ/x2+fGS71Y+h7JAXf3RC9uSFrwZLATL
Eb2eVLTYloSroMjwZX0lOpxb06tEdFKBWnz1b+kF/U45SurP6Slwn3PBObgQR+4R
J8ub3+G4NyyHx7Hqpl3SPuinXO94DrxI+cVtJGy7D/WJj3FfaIn793GpCVx1Nj1j
lX3ahWjPmGVx6w8/FEZTnWCP3JX1A7kWCMNeK0Uf23AmlV5zmFl3WsoI7RFNwtPS
QQEiVcsy0sdTQsIcyy3GEHIfIav0fFRk/bsHM1rbMbYNaHm9ByDskfnY1QCQA9A8
L3ROjW+hjxLlixSc/yxhDrkz
=jRmr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8719dd2f-3f5f-42e9-a16d-0cafcc7a5c7f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAkWd15B3ibFEMzdKIZu3AZWRMmhLbIjmRMdBXHA+GiIiU
GBAxCkK/F7NeiyB8aJdJpDb1Quc46EqdrrOssijFkX5VdygppDfj2N7WGosMkxsn
uAUlBljK5hc3p5EpqZn8OXoFlvWo5wFl6MiU3QnrjLdPvnFYiZ6aIu2AsNARrVEV
j3O5EJqdu00cmXF24MYq/c3zdp5eWZ782Cc29ZqPAOWJI2SMsz5TjldevoV9L3dx
FWnsDIEWbYfrKV8+if25AT9L4bDx/2g2mabF1IFNJVBpGSKiAKtYYN7Q+WlgKIMY
i6FCyv0qSfr4cNTni//d6dC95+6O3ZfjkMG1RovFxIkkX2Olr2l9c2sNA31AjuaY
SY/LBdVcSbEWmyblag1w1cbkTfmdFbwsIEvONrsFW7f3Fgd3nfukRJ6t5bHiF/aL
xoquMAFPIzsRtPFIYCY1yhBr0ooIjrBUjOpprye5ibLNnbrof8BXjGDVh2CN808Y
rAfoEJ0oNx/GjR6mNYsER2GNGm1E9cYtWMK5pULlzz2OaHtBrr3YyeVB5XfezVxN
xtNwjA8NKCXDoaYunwrNH10D+WJ3z+wkt0XW5LwLlgUhGDxaIem9Hytpx4Lu2Y8f
ghDLcpLGe1dU/mMHpyrRQfpcNnyPEa8R+Z5xLnaXgVrb4srZloXpKHxMvgzR5EjS
SQF5Ku4Sxn/ADbplnwuDBFf8AWZyh9pGRNwb5+g0BKMUORj5X92RBIO7Mk/+YAsg
btOBtF1Wa01uKXrv33WUNLzyMOPazu/i8yo=
=u6fA
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8998301c-ba91-432c-af0e-b1b936acd00c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//VmiFQ0r4vFWHXMzqth6QWsMI2m2g9YbO6Czl3Rk86Fjg
SLYYF3qMaCQFLMSMPRk+YpuEiAgFu1t7SyU2Dq3PdZZDGvPwaSCRH1BcFULLKrKw
UvCCRKvaeHOHcHEg1YqayH09sllqIoPFbRtaxGWKV4cR0T9knlBiuJzx8sRvEyKy
0p+WfayW3Ha0wLDbOwEz+RKbkqn1nPNqVQ2sNBulqmHZtEq3VDUG+rAMDbqY2Eih
8Ls50uSdpCEk23utMGPETiKNaJivLGuavEQCCi3E+iDOlfr0n2fa8/AbcsUzZaXg
GH7e9z4bTMGHS/n13qrmY+Eyv+G3sjmtJaCeeljY2GvxR/a7aVTVMys5AvBWtGdu
kiSdBsQT6GOuzpE+YAQHfmwN+VpLMWZgroO5uujFbmaQyzrylZ02EOqoS3/Y+/IW
BXym2W5LHHXwdyEGHJaaJwL+Km4YebZxjhrbiL6206y2s2x3mOhTjPKl05hb6jzX
YodeO0xWDfIQTq4GhK7WInIqX6lSYHdou1OJdfvr7FV0L1E/8Vj/k+JVTSEN7gVA
xi4NflPd7EP5PMFapnz+2IMJIdfzifq4jXleHmojnjoI/bO6GrdrgQmuuFq8bcqJ
2oe3QuM2okv6B5iV6MNG8lw5n8YcsX6syj1K67eb9L1Wk85yHxbqFwyraTruWzfS
QgHj2ztCMsd1sOB0OPrbgcLjy+pwRUeigEcVEn9fccxfxh1Ev0oX3ZIWoMrUR4mF
UQqdy0DcNuoMTY0+VEdDAlnMbw==
=VM9i
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b352683-7417-4dec-a179-4383e55f193d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+NPGTtDpJxJ8dGzBJJDea6oR3Eo9j3+ivW39JgeOvIDmC
W71sWEim5LaQ1VJK7BOqQbdx7PBLgeXIGQBWpoVaAxXRBqX57+MSzh+h0yuBaclP
RoUWHxdWxzBoKfHmFAPdMxguC04VTw3jWa4an/BHscjgboNVngXNggjgcujltdY/
Z8n8XnyIQcs98oA+9fDstLpefnVoQXm/OWKdhpA/En+a19wXz2YHVHWApNQU2qVF
gvHSpaWnBy+JVTx0jTvlaQjtHQ7soo7ykxn2/gQiScshu+GW2qzildfcRfXl7zdf
1QVXxXm7hHgrrxT3SIPp0RMr9jidQ8isYHRtaZFarNiWJvx+gS0UQQ4xHKCn2tYk
71HWub86Xf7mWU5OeaRB/066/bG3HzJMC77atscUEI2oRMH24mvfJYVhnHts3ziO
ZIx89r/UKu5234KGQT7cL+FBba9dr7NBsUnCpje7R4lPrPffsggPcQKLPcRdLrix
cgWWysZQdADtQqJB8IU7ErgyQCUHaWsLJoKGk9EapoQL4wgz9lZEN+aKmEeErDrw
jtVrEUgc5Fd4wMniGPMcQ4dOIURLau0txSC7ZCOL/n69IdC1TYJXqeqd7pPB9qgJ
ic02Q2TwFyY7gfJPhIIXGwUnpOaNHq27UX4BoKdD3ms1AdLFlkbc0fPjM7ViHFzS
QgGk4oYPechp3lATJA5qkVO3We7glP1c1N8nTBMYDccexZBLkQ6ebboOiWTmbefF
3mCgLiLyPz1t2VRKWBi3Oe39Zg==
=7465
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8f2494d1-664d-4fe6-a531-8555aa805c26',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+IRSgpM6VzIQpkbl5OQZHF7ZHp7ebZtT5sGcFc3vRd+Ql
vgGM0AwgRF/PJYl6hkm+fwbkgNKRiwkSnPGD9bDJgDYe6cJF9Run+u7lBAtN0nFB
vcDHOFLsdJZDMHXGf2MqW8hayeCf8bZC5kZgWtzFehf93w01z+M1IKfi7WOnin+A
G8k+axesAlUxIn+jzbpjXtHNzWTn/U/dU95uMEaQ4ceRPv84Jap2FUNLb4TijA4B
zUKV81klTDarbEcR/fB8PPUN2S92vZFwq1aDVVg+vt4jeh5+lV+EoTNQ6Loj02ow
EQxfoKgRjmwZ7Z64vqcfqEdn/yQKjSeMB/r0hLzN8ory3SN47WlGB3W5r5WHNRb0
GQiLgzq0dyDDDECVI4NPMotcpnE3bz7SxglOt/MLxVGdhH/k2HgAf2jD5agnrA3b
74zsTDJqE8yKe4sVQVOkqYselXCJEyv7TFKJ9iO8gug/VA4NYrrGrXhP82w7+yd5
eSY0oqcLl37jidrd5vNowY9QDTPc/LqeLEV8eHNi486D/16FSqyl1rPXxl1v6pKC
yRjihTYnZtFw2hWQ5w2CfGv+qShjiNjJ7UlCeZvVC3wvUnoxrOTbkl9sijHcdWkS
ATV94E3F8bNJPH41A70mBbfp5jW3ZVCJ+F1LAF+eEWes3iG5Fcpm7E0XrKlsquHS
PgFWVs8ehniyCp1rZR1RKA+BO83JQuD+SZMlfT+mt/QlY7UnxzChm4jBmwAwVGrn
d7UuxZPF9E5iNsaWOqEN
=1iFn
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '933ccd9a-2fef-413e-a783-e9019a22e229',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/5ATHdqHnj+qcKYAnlg1kaAj5T968D9t3naZqv4wl2JCOq
2vgSSnluGEluF8V8HXgErij5lTHQTf1Y4FsnwXwPIF35hH0RJdbGQtosFYti9AS3
+PEaVK8PIVXYkcbqI1hgAUhHfk940QldZhfdK/FGEVjoKEmRbyz8lB6vpD6ap4lT
sEr75sfylZp5hAkowTmPQD9jVu9nybqv/svQMg0pJMC9NLn4Xe4kY/mxisLrwcd2
HreJHU9vIZF/2HRBNj17boDRGY3P9ScwNQ6iqiDW11el3g2qK5H9ZQOkMWNIRP3N
L07mmMom/DHi7y2F9SqhR6SCw6tqViCbcRl6vUCrEWeXkL4MwQFTTaVFDCFZANEA
qrw+EIol1BNOkbIv68tnbTrfjTfLag/0DuwCE67u6JiibFaYylBHnPAQxuxEk89o
LX2KzTcU6ahd2NH5crfRkxA0LXChc0w/BUgBzzIAvdluBLu1iHkNUpXW8UhqkXEL
1EMdBXG9hI0mbDI0C9cLnDkU01KAiduLWQESL3MxGFVu5daJfOqIv4aA8wWfER8q
EZS3OU6M0ZGPjil9KAhA862hRw3Hl/b3RkPLBWEil/7cDTaSMyfxkDSrtsNN7f6G
TOBInav1qUim3g7sjSjv/YFBns9GNhShz7S0TJaKsC0ZaxA4yOItuQrNhttIwjXS
QQEFlE1r3xJ8dwL3Py87hf7kEazqMTMJlr0ichNeZd1+U+NWNyosiOQV6Ky+zFoT
w/zXfoxRP8MxY3E1VxNHDmVd
=QV+w
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '95a1145d-3b06-4645-adf8-9ce65df16030',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//cRp42EXH9pEUxEa1W74t7YzIRyPPR2SXV+m7kPM/WBrg
J6l83y3kYeCp/eKlb02zmCM173riisHC5za4kkkkPRepBySs4xIa4EIVP+Q9r9o1
ZCth1Y2c7iCgB8l1tQzGxlrMYdQPCPugTIJj8TQ1YK1+z4wU21Nj976YX+7vKPnX
0auG/bUil2bWl/QTUcf4gL8bZ/sZchYY4Wfgjh9/d+VgRfwOsVj17MnEVymFtWqD
ju7n8ErF7smEYJBDwsjkGmsIzzJiRxDK1hxW2M+RXKDRB0tfdy0wrohmJR3mRblA
fbqrd6aoz85ttM8gwnHMQ8aAoGYDB0UbyuGwD2xB9eIuNiZJzVuGhwHOqV5ml0DT
R9+3o2rhdJJwCVUXPQxBL9tJvT+6AvINBumV+Uy5f4ZYpGHo91iEohLKeggRQPtI
1DbA3COsODnV681tKhfJNEgkLdbCQbi4ihhwKIUqcU/VZXnb4iF2IphNrhjxino8
xKKTvBs3jYWJRMsC2pr5IStmJapxDvxOhWcDRhaKy/eDJW1mOFyRMT5VblykrnPU
I2ifUlG+c4UmmOSdWF9y8tHFoJ9i1o7OYKtowlxRZuf9pkp34l9scrIJWit3NSqi
y+2fUWxSBj88DtLzS+cOFkjbn1GvdtIwb1WZSsLzl7ZqCjKWwLhBjgEzv10y7UnS
QwGNkkq26CMlVsq/dKF/1hOx5rMKgevc6YyZCoqawMCkBCKhuwuhqVLx9clA5EvE
+keYt71R31f4WUj9ULqX46pLUwc=
=B4QV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '964c71c9-8069-472f-ae35-bf9029acc059',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9GwlfhtldB+0fcA3Al9JhtFw0zYg7psOKixPsaxnV39kG
5lKI8iOqTeiSGH8jU9VHkosZuwRHa4+jWB+70GmMBSLngFOJRD8u7eHcSsIpjR7h
ezXCgu7RLxOTM5ajVhC0NmbBWOltBiOVH+pLStQWTChZizgiKN4pPQRvtKSf1PwE
UK0fSYLsJvCpdTUw6mxD9DMyHMC5LebpCxV+vI/4vrIzBQM3QXaOESF+CZguLSPG
ZIOJhV6cRpMmalrQBcO/C8gACX3YAnIOtOBbbj69UsgASv4e8vKwxqKtGVnfsOGW
uvCjX8SepvSaD/P8ADPn28DHrec73fQlKdHRwsc1wwL4aPYlVaeDtoXwHwYkjnp3
Jqi5bxBlvaZ7Gfc+xf34FpeMiBJZlqqkMIOFUuvbeeoRmWG49Jj7CLwlE3UNLYFo
YlaV3aMnQBW+Qzaed+C6Sxc7r3hDgYcPMyLpDKHIdVCThpblPZ2LKFEmkbDvOw/m
EM/n5QjJmH2zrb60LSNN1N32UCHCNh2zan3D+HSeymoO3j6V/5A7OP1+q0aiy9O7
G+NghJRhJ1dmnOnZonuZb7nPjwTcgNTYM+up4/mLE0+Iucv5be26ENC2UHw4BlWy
bI48/jzoO/dle6rognLSnC9YdFt2yurWR9cC19X0EzDm+MO849wsPZsRrPasuFHS
TQHRhrBBaekYelhUdP22zxfA5i81OdXh7vwkXTnOpGrWVCFgh7tpErfPOgM1zn9b
XHHDiGCJbgO6i71DQcjlqilMP+5dn1SGnHXabTFO
=lSTs
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96f0d027-baab-43c9-ad9c-ad7fd1a3998d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//ep1JT8IiNkVaZ3TmlCVnCajvVEJQ1wSzEK6R5Sz5mcHi
bkbZwcAH4tcvC8ZuiDlsHacZEd6qiB3GSNmeN75p5YP2QyrzMildJBabV4P0fAt+
vYRJfu7jvVecshy/ilhgK7TW2lreXN0+Gu16KGkLc2gRRt24WnrxhECFbQfY4vFM
yvr4acS6tpaEtJxzLYhElApN098KEMLTMnkIcpKVv9/cDe5t0pPmg97deNJtwzwc
MdGrmYIB4jnIJz20A9OmonHVT39zW+5pgIyt/0vhjqdG9gIcrtPb/lQKOMmWMBiJ
LqWWm+rM4JDOWFI6bFDiB6YzWVLfB4lY8bdq56Z3J91BJJQI2DghCHrhcrCms/lO
pmfd/jtlTR2s/UlQRuSiAXZlmX28I6/dwLboKuP1adNpEF0cwRyUirsiKaoYRrOa
jsSuyjwAwHDkJzzc686qtkeH1CIMjutgjQ9fdqkFKs4J3Xex34Ccvm/t8rqWE1UE
Xh4F3kzK4jZ0EV1hvCFO5ZEzzypJahLiwB4uB9WWWlqFWhop3TJO+MdrI6zuq3GT
GQ3+u97BM8w/j93YktD2oLrUEf1ScNVCgPWKr6SsBW9QvjPN/EOEv/k3XoSZu2ma
3JSfxWZAGm3aZxuUkLAE4Lbw9z7I43k9WJFrcKvCTURWNGkP4hh9KxB/K9ozixzS
QQGuvs9otB7x5a9Og8l9gbN7t1IDTTiO3ifPen3PlNHNKfxV/94z7xxJCvyMTguz
Yo/kbBlYOoqc8zlc1j5rvkwb
=dnl3
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '97ac5070-7fca-4730-a98a-c35bc9abfb8d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAtMH3HqJfw6BbR4X5RvNyM3kyNvrEEeyNIgB1MoD6CJHC
JN3Cj9YCggYuVlkgFXB+OriSCxu/FDzwblAd33trHwqn48g2Pba1B9PXp0S1TL2b
w5UJId8BTHa+rtAN0hz23lE/7biAUL7yN7fkxZJW2NaBX9pyUSFpYQ0w10C9LxLA
fnvCYM9z/6G/7kOkhXDOzQWjBHCnu0/9z4/4xHCEvZiJLfH6ipWxGDEXPmo9rp2V
RZJlpS14dgZzx3bnVOgYOHWlhNBoNs4k9iQdyPN+/0XVFgY4EhKq46Ioe3Xd4xzZ
WATCwJXSt/npYx+DinTYUZD63s38ABaMvSlFOHuVpIbQLVythh36kvbUAC52gbPB
wmXMNqJlymG5nHhWvLOeFh+t6dQwLxd3j28YfglvjQ0WRH/UlHWlsTuBKa3d+4j9
wBOYvk+ubWmmbBgm17PCBB+PcBMSBjtYYRLGMdprzND3UK5+DBVSwaxfwWr1yCtM
rBiLeLrPW+rPSpO1mXylNAiS/yTGmm2E8NKnWaeJ6mhSMejLume86Kti9Yhr0OYd
M0pB6/fAYspG2XtDYyucGj6gIGY6m/tbAHV7y/Z1RkxMRQ6XsNS+JvJBrDY24UJT
NFxMg2wnITF2fzgR4KF7Vfw8vPWkuK4y5COWMb7YkqMVhW2l1PbF//rlvHc35B7S
SQEg00tBCuGXk6fMaUI0oQHdlNLsj1Cm8Vl5grIxZ9RYtfoLUiN+cjbW3Az2+MUB
Tk5+YieOIynzSSV479V1DIKQJf3C7P64V4s=
=iDBF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9c4f0f4b-9f31-4e7b-aa13-c2873f518bdd',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8DUju/SMnVmIXZlppfOCP1vaVDll5JBvAa1gcgEsjRhzH
0coEqYiCHW3qTeknWsMB7FVbJRsz1U7+ZnM2ENZLq69x3BfJo//4djvxE/aNdmfy
Tq2s7O/gUI1owKqN30OX9N1zhoDgEfwIt2V5g0i8gXMomLXwxV40op1We/LDrEel
BnHezbvEYhLpwMwdp9O4f4Cn93WcmhqOhKnpgfiOghItk2XPlrotkVrSTV0Arz6i
iwSo2Vll/lVN+zvcctJIer8EQl4T+Ag7RT0PS55VUzFav6aVKVbrxfyljMhPgwsW
D4CdSgqTVJUMuX7zFhGd3xIprz2pUN22BdGt3EOS0bEeBk/oHFHUy8xB9D2j8wXf
rP+L9uFhyfF8zPmV1CAbHIgNRMvhQl51tBiFaznkY+pxFsFGxLrbI0BANkg4t0H6
5Qh0OwSGujhXo6h3RHqyI1cIcEz4Bim58WMKkuB7LQNGFXwzVMbHBTGi4C4JQ7CA
i+7rz0HvRWvkhO3PA4D4JVPnXl84iYNfI/FfcuzFZ6vf29CeVO6ec8aLnRY0gmsT
m07Kmt22LwD6oD91RLNah3NKRTC6x9zTMgVhm2j+UJovFQAzipjFdta4p2aJOoHs
Ro6TQbxdtgXJBBI364OimkNUG6+U+EM12XwveZX3tNkzrE2ISdOYoyOfk+wVBYXS
QwEpWEXYJyorWrbTKlt7u7KFXEIkLfwjDR6rd3Ui0RoZXTCS7BArjGduE6rGupO1
MyZtAM1XlCWzEa8YQ/UJIlAJriI=
=+/M+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9ebd2dff-c8f3-48f7-afb5-430dc2e83980',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8DD12QBpPiu++YIgwbty08Tlu0hjkm/ki0oNmDoaevzdO
sEbgEZsee8xcADYQXBsgfsHHduCOs1h+8euFLSaX8Mk6XhO17z5vuqxlacZd+EE9
mrQ4WKeOqFaox7hFKdb2MkG0kGQRdL5B5f6heFLHZo1Df/4xIrmMZwb+1kvNJ+nd
Pawyo4/NLzTk5OATbPGc+xzYgM88+4s3WghIYfnZariyxw/YR5nwoYzPNhb9ABOX
gExx9AzKL73yZwlHiJT28zS+tgpoyR17U9LfozBcAc9AK1tj1f12a0TuBQfO3xal
LNQL//TfnY5RoIgHnC7RuSqvpAojBD8P0mdRYaoNGkLHhzGEzsvxCsGp1GSlgPsj
jNbWE38DJdFHigs6e0rRfQ9DcYtAP/r/PWrBukUfJCTxjwU4L479fFKxjVRTJKt9
cJrMyFrjwJZv0+6Fub3+NoPyB1Y0Ak4Ahl9CSLRoKjaUDyuWyftRaiFZfMiOAVuI
IEBoidibNEmSo0z+FEMtTYE7v4gVk9tAO6U9J/UcbmAj40B7tbB0fooRT5k7B6Da
HsxujEUYUFumHdCMsAI/fgknd7nIsVszMvOzdekHleSp4qVinHwUe3/FYTxmGgXZ
pVnp855g5HOCsxFmZI7qiRUBf7MNUQZf8Vn0fkAcbflFlL55b+MmbNIR1naELBbS
QQFuPJq52iTQ1bQFbiDXn+FBgRQ2hzU/LIBLUGblgdo9My4XzlpvwSv9SUuWFyXG
pQIhyz6lK0T69sJ2DxOxKAJs
=PQWU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a374ea53-988b-4b38-ac62-af6e9b791ebd',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9FZehLY36ojMhwtx8eYBaUirYVH87KtcobGjmrf4mdjP2
ZeXRlIiNwT1AhlN9Gvzzaj2bNiV4wyA4JT37pRl0i8bRGZ6ybXt0IUlM2Ymntzz/
6Ro3Krskq0/IV8obeD8t4oGGLTBQQAa8C4LTzyG3+IJgMLrBI9ooUbhXGcFLj6yl
l8F10Qe2hnKXHpV8p/yAn6RraooirZnE2Wf3p5ZXFJEdRnlel2hrquS1ltInUkRS
rEz3R5sxcpZxf5qSE979rtP/KIYQN5MELP+8mEvO+hNU3ZcpstoJQKMuFzzafRvf
jxck+mwn7fxYp0hVf9Ju4hywqt7c9pcEr+dTRv65q+fhnWqeDbXzp8TN0ooCL7+w
en+28134n3NHiP+AV0M0ZHG1xwjkhYwy/nGvQAexnGTtr2kN/9hrthmdQKXTt31r
a6bZdQcHu18L5HQdoihO/sv5Eg6/BYtoC+h9t6dtF+g2QAURo93yNergJInTt7xJ
Nsu6s5WOIWvkyoS8GX8n4hHj5dcmKJaj5ehOSasVQwWtC6LvH9MT+a+/kQgwygv3
RWUvFn7bK78SdLJ/s4hSuNuko3UTNIZOqD7nvXxq2F+0xig2PEuaaVr50cvPDONc
7O32thQpgWoB9pUJHSDox3Qg8qMK4ZAgTagnmsLM7y5BM7XFyqs6yYz9TP1zJc3S
QAH7Dy+0uwJPHEw26XFtpaF4cLX9MArcnYBgvIs7RYsK9XFkEB2Dl6em0KaicN0x
qqHsMtzzGmHcZRF/kJRTBeE=
=nlV2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a48a0ac7-bddd-402d-ab08-4e7be67777c0',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//SW49SqkGtF96I+HLgZsONFfoiuf0628I9TRszS92Oolx
V0edZKGxgEb64V8p0txpj1iVnytDKb5fmJHTKVIEZ5WTeX7xFAX7aIwnZC55o9LT
RboqM9AIj2sf5z4h6XWtLKUg8wIbt4NrUeVR35LFxYBE48jnl2yCmJ0ADizzQi6W
+P6B7MHKB2BgY61jz+/FxkjeGnUlgEb0/GxmvGXlxbQhiiww4I8I1YVUKUAGe37q
DlAvtHRYFEDATDmbVbhnIcIgiYrME1P8kfeqD+XxwnRvzrNn/txQDXUMC2ekRHiU
Qzm2Z3LYeRO5UplopjSDevEa9wKPj+3S41ZIg2+hGPOCnBvMq183deqn5rOhVPse
L57ucIjMODSeZS+4FsJujXx4MZRHsgoVrUVejAipl8z4V1jf3UfI34Op5qXuF5RD
JLqBH/BVr8Doc44d4yDWlgIYJqhNaddnBEbiI3tqXovOGt+jMtfbLlMM2VUr8f99
NG7vPv9C7yMtX+G+CFqFNQQffuZBq7oE+wA6IbnfbAICFB8WIQdpJu0pYoLTXpLJ
pxfPq+dvgLZl/DXHdVMCTEUebvN20myZHCWJqF11Suxk7SIzl/Xoq9F0ZdgPUYPb
eWtGGkY/IspzrELoMuwXCGnsQbG2R/RBvP6eZxA7p6AnzTvjlaAq2vNB8QI4om/S
PgFJAEhoGiOpJvndPyLTU48d6NONLlxiMrcLwi3Bopw7XR7FcaqO7oC36LL3DCxW
d8se1oiBylnLRt9fQUo+
=AHDc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:25',
			'modified' => '2017-02-06 14:10:25',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a5726b14-c422-45a6-a7ee-b9d6275e58a5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAqEIlu/fjMu9yJ6fkoPqNGFNrzxOkupAPdyP67DpqgLyx
+ppHXDls/XIdCCVQt3pz9dqgNThMSr/Ub48q05tAByYi8UYFsSbYNXpMbI7vIcgA
k+V/iuBsbTGvgM35qbCwd7hjiGqb/GAFb/TE5OfQzW77Z2v7qQf4xVbW1MqbX7UL
90xp2ekFHZuHl2AVaSaRa94lmLite4rhCFALR2yY4xp5bWPrO6ImbhH6LP/aHqVg
Qrabatb0PXJFUNdhdxI2jw59Mjgqbwz3E6MTHlnqGoW6AaAltzk/FP7pw21dYL3f
jpNIXSwd2hmDH2pFViEYZycA359GC3Wf8m02YRa2dUlossAwVGrsjrbJXYBIerSy
7k4nc98pqctppOexnRKZYrHGYfUA9NbyhsvVzOmVx0a2vzZWVCuK7HXgKTzj5+8s
TSt3UEHfPVS3qNSvkv/F8z22crj/CqHr15Y2Y1cWfWk6eTDozumFm1cY4dLzry82
rsPyHPjY8KFOWhNVrsvVG12ZhSe59MChs1fHvSLl30lufrczxfOg9/WDH+6t4XYR
rmpK/XLhU9BLggEoVzWv3sKWnwK6d3TKNevVhSOm5CRdoEn+ED7WaZyc/NXYLLrU
h9SBtF3e+FLuFWSmsHLEuwGABMJD6mpOb10JZ/gIZ2+tE+lUZgkmnNMJC5T3nrHS
PwGKXp60BPJZ1//GME1SYKWTM84g/i2lIKDBXl8ewE0znufwSd/wn7waQ9tOatWV
UmqqJ65J0i3QC/bu076G4Q==
=yP3y
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a754380b-465e-443c-a79b-bc72e6b4856b',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//QnzljCyHq2MgjlZFzYMwXGJZ36//DV1D1/U/ibh5HVCm
R7qd9bC3E8ddgoh4hnaJS3iTp6l2Pbn1tKifkN32Kkdj1BaMJ2OVthicc0XT9nxm
zaZgGE1XHrLn2+/w5IZKnJvq8uW1jtw+ISCfgM25YjsMqf1qrVf1o0Tq6ehLdhM3
SfFoDYZNVvxu3aY5NDD3/tIoD/IxN1fj7+jjGcfJN1PYI/tf3nqee7FDMt8OJeoW
g8Zsz26XXIeV+iLhPCni57cu/tDJUIb8LXTDe3dsb2iCabljYq4RB3cp5cDWPiCt
3n5u3Qn/tnhDIFZHjZ/NQyfed/6zj0K2nrMvJcVRtad7ujJveb9NTA2Ct7IITmY5
ktOZ5Sw+6iEpBrPou3AwxbRZe/6UCwwVVOyg8+OX/1zLHCIrAXo177vSlPTu2ZK4
qCiWmaFTNel8x0n5mgBB0ojVdcGCXYDy+FwKpyyiqmKX34w5Hoq6jle9itfMSwXJ
ChUH9wm/b+f0nW+8zEZSyILfClWT/UFpSJWMzngLdX7gQNLY7/QbC9ob5RbvdIzQ
OHEl/qWuhPKN+QFpNWZElgbhGqup+G9odCJQqh0zPj8z2JZpm0wiUxDxtqj1RcCb
eh/feJn/Af/l82EW+iwue63tlWTHmIIuEVIFpqTSs6quxK4JaBJ3cYrkXsQCBkDS
QwEqGjd0njuzTStXHp1HQRPxl6D8yVdZb05ZWGwh8o0Pj2+/IZn5vV/WGs1c0nPB
jrxleu9dCGdNfM0mg393WPa3xU8=
=RhRs
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ac5fe7da-e553-47ba-aa31-fe9d35f8d199',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9FVwO1MgYn2WVg9bxjo216YUmaOKclYhpJzkz15VqHM0P
MQQUvvqGpg8KS6bFVESN3gCFoQI9Y41lDPiITvJUm75eVGfK9r0gNG2exsH8o8SO
GQC5mTtEIgDsc/7erBjFsmalfXvQ5vJIKqJbkBNo9s38XCeh/fNHCxTnluQUi1Ph
8jqjMgiYXFF/asYaoYc5CdptmCTdxbvpLdKhxtdXYVRyMLjb/CzxV/VMR7dyW5qY
JcXOassDVYhDAh2T5wqPW6uNZJNdzu6yLPdyKQnrN1xFGIBDITXwwCZAOID6N/p+
kR3aHkN9Qv6qgg+TJsLVdc5zh3Jh+YNJnZlAAofqjkInaB9DafjKxkG6TvZ8ostH
+p+U5/sd5j75SgX42wNDa8bJ8I+/c9NY8s4/+gbLW3ZfeIp/9BxDGkgQenwE8ZP1
GcjPAV1vgnDCACVCL1MU0w94wB0U0C2Jg5xQchw26ZEAWSzT0FGs4X3/ZzIJZ+sI
lOLbo5YAq+kHZ0Tn/i1IhMVsIh6TkCG5yizjrDZHwd6jzmVSEqdBAk7rHJjwkYP+
Mi9bxPb5eQ73PmyswH18/Odp0BGQ7yjRk9emENgbJqVAcjPdU9+YQs9uZmJ7SPCG
yIcFfqs0dZk5sgS2rav6xr/IzrqmtgOSX+VFYbWz5tKM9vjHKTS7/1lm8921zqrS
QwGz0qTJ+mCCKESTD3LcimaEQ7laI/kR5sVbMP5LpxGOIG6+qIirsAHOIursitiv
lDRotQ2fiNzHqS0sGCfUHxkxgKs=
=9jBJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'acb66428-d354-4e6e-aa8d-1c4ba559c3a9',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Z4Im7/u/OVvqmlf0emxBiSXRqEqXjwRaZu5UesvR+RAX
EGhKVA4fdJI45ymVbuReKdcvzquTeq5XQd8U4E5KiaJFmn+6aM+r1NnbwgOnio8P
7nGbCrUUnpkc7WNFD93coUrjmFg/06hTNIft8ce3rVy71ZZzSJkTJZtg9ZY05MON
c8NZZEi5xzVI09BEmF6eZS7QrW5lZ9tOgenp5y8P5lcZRqEmOAJliUMzc2pAET5E
F25b6ac6I2jLsRjKSLLDFLMLFdTiCH2U4fommg+8mLr42Lw8JKTL0tP+k/pK+0P4
Ht2PCemh19+wZ6oYycxxgBV7+qMrAQkx+MynIHaOFrL6jp/WX1Ry8L7UC+xiCW0A
ml3oKpgBVNJ/FQfCUZPerTt3n/S2BNgQ4TsZxKyNDovkE+QZeQVp+nrZeyhCE1m9
x3Pms38EqeqMhbNoWRbNSOIwMGIeRY617rRVVftejarxCppo8kQVp+VgQxENJwrY
ogWDs5VcEhjWPwxAdA54oMjuapXcNWeQtYg0kbrH2mVAQMrx7sL3REHq2CfppC+C
aLpfJlRv2t6EcNFcUOF17sdAyyVdumgCJf/lpbcsL7k3K1x4QSBqRLayAWXcyX6h
SMc0mEMLMPiVzoh5YploRTAi/uzYLAVaM7o2ryhD1PRedjch7vms6TD7mOQC/HnS
QQH6svF87gsuNL8DskKQ0ePbhv2Qu1izAua69GeC+HlTx1N1y0gpjkfdqzYQEO6o
NubyqLwvcDi+j70nTkpnq72c
=XpS2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b8981240-54dd-4d8a-aadc-c411a71d48ab',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//ZzLnqDbUiwazmBN9a9tDsdKWgnEAFZJRMNWw2FJIuWqc
BOSSQrKx2MgxKKeQEova3EG26yi7EOEhWZNNBmq5axu/Hf8MNwTa1kyx26V6c99i
73yVcWW5VoXuTwAnLpInOc4Q5yPLZA//HiwiJH93WmDQuxrC2Ec59Fegt8FwVkeO
vm5mEnrSW3Q/9MdjKhN4Dg28ZqBWf/54Cy2M5h6CIy69HhbQStS16QT1qIKuqQCl
2lJT6eRcesI5cRcATOMEsxTUVANIdhVdRFJHvRgackk5ObsY9xh6csBr0Yrw1Qx9
M3XFTZthtLLyzYvj9ezE2F19Do4X7A3tiAVaOaJMkeReB4kTRNDuHa56OEMngrJ/
YfDjixEnb+vdLGy0mJFUT5Ls0Tlh9fD+cmUnKjXtl4YonkFvwsG32P373EekTzH8
QMh7Xvuj4/Wq9yEd2hPr3ze8BypZSm18EgEyhwi3C1tyS9paetQHhRgdOSjUz0xz
CKQvZUuvW9tBCZgIHG5UFM6oFzDd1Hum7pzLITJ5cjPB6FgqA1y/ImB0dj635hGL
wRnMjfZzVcQ8LNrh9RLb8QYXGH1UPnNrRzxaISDxXenq0CRFzxfUd8HmX9YxDhvD
HOxuZKsTYsSElXe2QMdJqiMAd/RRxnCdwCEu+GrQrwmiTBwb9hDvHfHHRIrQosXS
QQFAx+u95uB9XU7SDr0wPMsPTJp49ytxq/bCeru8lUUYfnsMGVSEfUyOL3BZBh9m
QXpRAi4rhqZM4JzRUtGr5xVR
=EFb4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b9125439-5f5e-41bf-a938-c304b6d795b8',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAr9TAxKE8af9RSVmTbxEM/IBmf0oSIlFU33C2N0Y0CZan
/WLrJV99mWOeK/qbiQz9qKKYCqHN+1NwGITRaDF8Xkucd6EinjU6Z2IXcaiZqbPn
7lktRtBfGOQN8BHo7idTy1LsTwSpvC4bCCfYf5v3Ik/mA0rM0Lurkd72iDIStoeF
B9aVbhgu+vmS+9MXY+bO/pxQOt81KvqnggJid0a+RXazD03NTOlZSnK2JwF+fGV1
QYjYYgnZMorLeSyOq3/KH3VpS1dD9AKeaj+9GhXcv74f8qSq4yLxc6N8abMstzJK
Zvo+++sEMrAV0+vQyPJ8nVjQvpdzFbEYIBjmLkHrR97WjaUbZCMGWOHE4Cydml+6
aA71n7Tdkzw9ZPlccwKZ8vuAqUnF618DyRPkAnP1F8/jJ0/+Rc2w0nfvsarXN8iQ
/JZR2iL+3jYEpSKxrvTDOM3DRN9Hf+G1ci/mF0Eza87CuxIG5Wh2Hm88JmIz9iCN
BxWKEdMg3ijAs6adASpgva4xCLpiF2hgJyaFtQ1aodAGp9l4Y20ZTitGLiBx04iU
KCnSb5q+8SckSYxrbNkuQ8xHr24LXwyENnJ8gsPljH5VGMQjYsmy9k4UFEjF2lma
Eo8KTxvR6sj/lRddb+91KZTpiQ9gNlbHYTLWlgW9pWoXTdaTkyO0mLHqUjDevKLS
RQEbkfZ22Yc53JPe3ZG5Gx4WDZaqtPIa4E2TlFyWlLPg8BzK4qBRPGMQCHe5dpsw
DXP7ejNC+MdDZpOCgbGi6i2OJNkOIg==
=QcU+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bef0b8bd-cf13-40ac-ab02-e5ce75fdb874',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAu/qyV3G3xbtRZkUS3bYHpNkXK101UMj1FK3Do6ZpI5sn
/Z1M7s2bqrXi/14TY2DgOGIyAnK1O013ZIGUHVL5uKD64MboBmFOiCHukc6gd3v1
jXkWD2YeD9W27aJMJQabFPCA6bPBuiaO1VLu6wlgDobe0DmQuLTBK7GR1r3werbN
t8Ip1KE9eR6siA+Y/8rZnaGJ10i16sUspzl4tuyortqZCd+ylbWSgBnUWQWicV/r
Oe7sY7Nq2iadgTQxVO6a7q4D00l5YTjySGN73Qr1g1geImJ1mjU79QZsPM0LW/G1
5AVMKnWr7RN+7R8RVdIjLfjDKrG+P4PoHgIsk9/ZovcVMy//SfHVxUl7aoNddGYh
10AWm9a68VQKJz8V0wcexxkWBLpkN4tCYUeTUzK+Vtj7rMGwqCbJR0va7xR0WU8z
wgbhNl5Gf/bWsivjLgD57Co4hjiHxwSc7i1qVz+TMcZ58O6+65Bm63X0XlwL3wy/
WHJEoem9LtAlgFgzFLgJXyQnb1cdQ8ATUyHPlgyKIMuGUnw8p7KDSt6/hyB1/Trz
qUYAwmr7HYtIHYM/qvYfQeg8ObhNVF37LaCtmK+XdeDWWdy5wVbwnrquHdRx2lSg
QyOfqCcAcU7jSPywGmHGyUc4iPPzTQeG4EB7v91xwsmkq/C4KmBE3p1JHf7iQUXS
RAFIHiFd3PV288d7bSugv6BYpEkndFr7mZD6Jjax78SStBgDfph208QTo/EjRkor
5gG+RfA/76AqDhn/Xm1r/OVQg7+M
=xMtG
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb221ecd-b6d8-463c-ae88-3612f4d201cb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XOqWjZti4TFZZlKvwEUlamDcX22cg4qLg/xIiS/BUh2+
lwPvqKdLqoLuANpYoV2YJgVH+JG9fL/n3qpdfbK18yPQoYkjI4NikMA9l5umcdcR
z6gyEWAP8a3A5IZzJkIceuWJvZjpzF3KOPVviohN1duYlQHdqB9tQVpRXnGN7B72
S0dDXhyMzUEsrfcOuzLSl/APdCU9UPNp1QhDFnjw2hXzZG/lTS8ZtahkFrBT6qpV
XSWKZvNCjFNqOqV9gEnS/KnPsUUxap6NS2osehvCwpfJ99YGvgksSt6eXfRX8nIV
0WqqgPYKej3Kd0ppAAwQjUkRJjEpX86HorHUi4FIy99R1pux+pFroMhrm6lDNgA8
DstCk8GUbLMdQAw562IjX1jNVOs+3c6gsMtMbniuKsnmY7/yEeeSaIASsm2kuf9u
B+UmwaKnvifuwfyF198TWL/78kRbQXkDlJLA2wDYEbYxEuz0tE/BfX9TvvjIktT9
HVOF6KTNNTpyh//tgG3wWx3uxJTTRMafjL7O3BQ4nbnGIrLwUMXArsdRNeQxvBZH
heeutA/A6YPZQS9hld4gl7P3nt+wMu84Bxr92YER9t84NkvOFvxemzkH37OkQgGJ
rOvasV5ZmU/g3cz87Z0cmfFXrciP90rQaIBHjQQ6i6h9JeG16/TtRAgqgwPnCNnS
QgGbces8t9DQg9GuKJIhy7ACICAF6D9qeMNGddlPC19YJpsnc5LMZChXqzFTP8vd
9UZZv8wu/jjRtdyFDgpWD/n4+Q==
=I+qO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cda30390-6e02-412c-a017-55ec6eb9b76c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//R2y0/yrcr0fB0MW8moOUU2FftoxKiIlV8/10XX2DgAOA
lg8FhV8TNYMDn8pC/sQpS3Emkf/M5TYT19jtGVcKdTkracYDqL8ZRluMvtI+sxZf
Eu25Lst4lA3aYWBYTF0h3o6SecTEwZSmrKHzfbptCGBkHJu3YjjvPk2CqLqIzlEv
Y1xzTLAe/J468UjbFGN9F2Nq0mx0myC1FyG61devNhtskMBSQR+9kgHp1ilIbH2o
WlZZA+TFUnPF9s9Ib0cSLcEpJlegL9+obHsfYEmsqPnkFYo0AwbSuzDWpA02E3SR
N05DyHAXsI94Cs56U8jGaJRQXYPitPLXvm2S7jrHp7mHHI3Z1h2h/Nqg+4MPn73k
ZBbVI98Dg2Hhyi5RQUTlmOk/D5QYhyvlwd2f8SRNr/inqImO7iqhhzIMtcRFd5LB
KwDF2V0QTws2BIhAju0eB6Q4Zj6DUHkjmdL0NJ4g2mfgOHYSKsxYKWo5co8CFBPF
vT8+FCdZADx8jBoAiy0IuYVn5mhSN5F0UasXxjEjLai5Ledh3B3zviKX/YzzK4oP
CZFQGQfmxqIBkP5hQKbmlWdqIEbtloAwpJ/htVuDIugmtx8OdwUNjCJ4rqIxrqGi
OzkDOXSFwHH/bW0Jx9Sv1XhSky/kDKjmyX3ymBN0FNtIjPIjp8vWBW/qiuEps9LS
QQHpXSA2VxY0lRylBs8C/o1RH73RQ1wRiKwvToP+gJJQMMt3Ln8uxjgnT7hyYb6V
+T4HEVXRDHN0BQON57E7qfmJ
=MOtg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cfcb67fa-5137-4da6-abc5-8383025f61a3',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAnFHhueEdfsCO8e1D0tteBwSlaoib6NKCuQeyWK6d8ku+
+rwXW3JCG+YauK3sq5Q0AvU0VBOtzUUWelkalE3gqd7alSi/3qGGJvI9kvgZMCM7
Cy0xo1QZx/DV2sVRENu2bf7V2pDvgXBROtVQUE9qRLuyit3nJkwm6fULDZH7267J
USrV+OkspLuVL1P/LwnnKtUo4uTeJVa0wsOtdGxRYIBRoFPUtuIlABkk0YZRIU43
K9Cl5QQMG6yAyhPSJGHpfryGwG9Dgm9djD4YmIiDL7LI10u+qYVzTa4rSt2E2oat
y5rzWovyjb41qsQjSfGABd4NZxcMPfyZQg0Pr2+fjE21eaUsdmTVu5td7sC+HSh3
+3VjsrOmGJ928KC3985LBw5lj36rXWJvP6HJOie634IKw6ReaISFy+epPX4M11ZP
X1f6K+svsoY0MGn2z7xZaApZlB2eVkYpY1rCNFG3PvuhN+U0wkinPs84JQi1+9oM
PK5DZkqoFvQdz0ITEcQMvSnV2aFV6yai7y9DG6wzV3HbUc8iPiVIZB5ZIWAMYwr0
F7d/Hla49yKUCZ7U4vpXu9CNDT+AWekvtt0oWODmklM6tLKJ8TKl1QAnOHNPaZFB
FZcGPbjGjHpP4VBOp4AzqV5Pmi4ZM+H2IWFjl4ErH96NE1+ynKOCNbafkikTppbS
UgF2Ck5Zs/FMh8PnglSkMmqGIYp8/uhvXJrmjbmrcl+51OXtRKZxAcTcdzx4RtYK
fE/7YyAo5uIqjuX1CczNMcIS0xhTi5jOy3XS85MPWHcLhtM=
=xDVL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd178b00d-9256-476e-a92e-7c992fd6b76e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAicShHDJALBB5pPHrb+WmRTV6ahsW/siJafMAaelkh8VA
/U/ODALmJX/0d2v77CWd1wQepjO1QfxKDwC3gvXcu6cyLw6bT0bY6w6se59Shujg
bCdcEdFFVKVibXJ6nBdAHAIWPVfSb46M4G92fvZir04LAbCdx5a5lXUmvyKVTIsr
Gn42xBEuKN312/iFr/dnnI8HR9I9TnE36slUpDBKpCtFCfJ7jQId2UXiRmiijLQ4
EXR/Y25fPGRUzItcTfINlcQYZDUhqZpbY5VclNkdZO2i0t9/pZ3HbcMr1te2g7S6
HKTxbtMiVUl+xdV6xlioz0hMVy7Ii5SI2EOS1GUHBDS5v4uwNDkPa52pB2EyDISw
7Wtj3Ks4U7wBcNJaDdkuos8wUBxF2D0dUgjGg10FwC98emMf4YHQgOBnqWiAaEgx
6AFREWiIbUYw7S5myXc/0tRYULit7pRGsWPI7PqokWdwsJWHyhDQ0G1KgvB5yP8U
9v0jIUbl87BPMto2l+HJv9Ze/XNhP09ZYzI2KfOsIIx1A/8mArJIjDrhnYtfLUFz
6sa7fCPCN01FrK7aovHuVTqCQsZCqgqMEzUQ36PFdMANHj0rZaZKJqkkNvslRwOR
UomEPBHF8w0p1XlvhcdszGwiEjpGHmVWXsRU6gOBxgW/VAfwPoIuAdwIP+f//D7S
PgGURcm3DOJ58BHksO3pSbqeX5T2GiFOZWOZrwCtJJB067PpV4xaUVNeqm9ACQ1m
X4LSu3Wa5mj6+pPy5BMd
=Pknx
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd1a14ab2-069f-4160-a008-8bd68a66aa65',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//bmrBQd8p0lApG1/gSMs69TcUR6LFx4zgfAbGqo1QF5/1
C6eu0rTWoF70WE2LJvjvQY90p8oyexlFXM94lRGWUr3Gn8odZUs4KtBclJrxM656
ZOf0/KXvmNg+6OykCGiCDsKPK2G0jKznnALRYRFkQdhqXyh1YcCsU4cAUypaXTBJ
7BqkPU05dgEB5gFwBeE1Zo16srzKTBtPm6cdYecOOc4+y0NBOnW1f2Zjxr00VrqT
J/9D3NP3iuO9vWGwxw4BipcKK89ZW7xCNqbXn8ZWIUkP1YN+wAtf0Vz37WQVcCdL
zgqYc4TCHsQ9utv6Q7SwLZ5DGyaI8Emu/b4blTbKBgoe714toZDI6m16RmLBEmYw
Jq76o8lIosVJEtc6KBKEyOfkb1APSwMXpPG8IudvwGWX579mP4Bu7Wb2FoEnnvps
JPP9+0v6N+UX0fApXtnwFhA/4YLkvSncPwiTiWeDJ/zbKxTqSZB6OoiOU4BoCfXy
Y86gW9HggZxwj/X74oZnTssRmgNHkBHvT1SdFmRf3mJBlPOWOUPtqyXcp3vpnYnH
ABFMrjnFfzvo/g2bwQjA8QLdDNpqUW1pfIaB6LNdUN/aCrauLPPsSZbf8AucH7K1
cjjrs7Qmh/eWcpYjkwCCt+JyJ5LlON/sW4W8uX96x0bz5lqOJWCUb1vHIwxuFWXS
RAE83K9rPKYHux3/IO0OLwuHhJzGyUUKDYODUDMyTkqpCAGMxCV19U7sAvISQ+sB
ImUkCSH7lFK4Holed+SUQDtHEsS3
=4F6j
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd46945a7-d14e-405c-a532-7d4fe17313b3',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9H612kX0lcVUi7rBJwzK65J60GLP0VzwlmkEROEWHsSN7
wp/K0/ED8vzOmj2RIG294O1PbUA4YMCmKUjDzOkACsFDTIP1DzddUDWx34Tl+D8U
R4YX3YtFqgyhwyfJBH7AWJSKwdlaA+nPtOS+ykVbAwS6tTEVoHMso/1wRnBCHyCm
sq0QAKbjvwZ/6F0Luid6ogTQ5fLO7FrSWeG5W+5jcxdp+mqqcZ8DE2arrCocCeNm
Uh+wbs7AShJVgNl8EHrA4GuHCDeb12AN01LF9Zxln57yUKi21hAiLkIuu25XYVfN
LPwi8eS0guhmkr5olbU9dwXS2FPdGHYYrH/p3/xEXA8chEdzq51ymRo6dnVQrJD2
EHX9RRETrjnsMdHYbk4aTKa7/s/Yj1Pz81qS3YkvfCUBEzB/EBbF7Ih5f5zfYNFn
UO2k31fYh79mGdjBkGsp7B6ZsR9kRTU2yC2C3pdUW06pOfgEkRiIgvqQGenILPVU
j9ZEyGYvz67EPcQ+akx8onW8NdA9+LmuViO3jd1pOW8l/19v4Ro5WuIhGvhh9uIG
g2kkLozDEDify3071qW51rPuSxNQyRAymUkYSYWdSYNVKs7HtTcpW/QRaOm9anFH
/+tRofmO9Z2HjM1Ep70OY2vKcj8d9BxaSyOp1lWqah9TXsXa3Q47vnE+Bvz5uB/S
QgFqctLzK2hqU7GUuuwqxqjGAyiOVdikfC4K0I/7/X9gqTEwd+SWsApWmtQWXgDQ
3PDQ30o1p8dSbKR5/nWwFfrOVg==
=ky4W
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd96293e5-cd21-4c39-a7f5-8e7d0d323dd0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//QDVaplVdQJ2DRRrGkcKjlYfwtimzkRO/dnQ2SQcuUFYK
EE4AqoO/j6q01Uoj4zgknBaTOI4f5ej7atowb9sDE475Ag+VLzhMVdXQ/n4YbcM3
70w9rO1WruxRPD21UCBgrpqRwWUBBomnBcq/Y1fgVskK/z7TTG028thh5PSA8xzS
UCtYcxWn5qMenHGMdHFPlZ8CkXz/UziRBAU4scaFB0HN7s6ziroWSRpavOWoK02f
HV0Y6p1Mq4YbTR3iBHr7GpMZEF3w8dxI0vZwM3Lbu2sBreUFvg7TWnRkO9KZzovU
HPNmhElU+GH3ZH52bmqU0V5lGLrVZKSAaoScRalkfFnMQkoQLd6RWOV/wmVe6UHX
ukqcv9zJmVUtuwARkmh9mPhcgR2W5bTgEnld5IhtNJUkrPFpHHGnVr10lwPo9Ceh
JFLbC/Yf1B4hhM7z5yIX4ybGJeSky360+DCmvfgvWS9dtz9pq7Bw0shtmiANK3VN
f4PhacQDzgIvalW+bP2k3+qoJBUOXS7tiHRmX8OX7F53RUJ11/bpPejVCSOFxdjH
yfb0D5fNAtZlxSX43nzb1LS1V4hMfzcggH0vmX873HekmfffEmCcAU4A9+N/qMGX
D5IBO1wKoF8CdRvExWr2YS2voWtYI98EVwU9y9p6FS3Q6PFwe3cTxW2it705ThjS
QAGcD3jHNTILhNF38FKjcqmKFKUekFabWvoXo6/gTiVQoJqGrVQOyLEZaonfOJkX
3xfTx1gE1Kl6ZNTjnq8V+GE=
=KSdl
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'da20d7cb-8730-4612-a94e-a70af4f60abf',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+IxvdmqkvpfQYco0s4D32Msrj//o6TfXp4TLangWAqUC0
PrThnhLcev6H8Jl4xwIRAGKYXBcDY4UTUPuMhiobgxI6mM80l8qYRrq2gtoR2oWi
UT4hLWz02r7gjvxBNgRJ4Q31znMcs4NApqMHbiWWKakaS8HhwOSfCCQoFdvvbV1H
H0PjvAPXCfHkT0jl/9WLpF3YLpEwhbIufrpJ6nzKrLhO9Xod0Mp2paFONXIHYTml
k44O0zeRo3seXnnQwS24OwkOXiE+TU9Fmpc7gf7WLqyNxY274QnGECX4QxNd5JPm
UfSZW/cVjktn4etHnHuhVlWCbJxVpRAhBP/WH7Jhzm9mhYnf0LksGCfDZbqy0RsS
+NE2SNcXw9ihDVQO7He4ntI+IJjRcq/4nzOPsgf5+NLx0YG2AmamRkbf4XZUwiVD
IeBZmH3n7VhOXSD2uMyltK/1b9EtqeGjnkt3G/TkoIcjx7p22bVwLM37sIF6xiTg
V2JELAomacRVBXZJ+1uSiAvkpT14h+16RtEF46Ry1Y8/jybt+G8RwoseL2rB/7sA
Zim5fmtEzIfK2pIYfkDE4XJkx1+VvLKVlP0LcEGGGbvaNrYXO76q2c3AkfVRlqRQ
Wk70cZ18eaDMbOOSvsdFwGxGeXwD0/3jf9YIUpzb6cQXsMD8xLaJgPDsC3kJ0BbS
QQFOd0drpXKblETQ/xAhvIvGKvpLdp6+4gBq+E+wbPxjmI7HCO/GrySWxL7FhDJe
k/4tQpgL//XtzLHyLdsfi5Oa
=1kYS
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'da7c5414-40ce-456d-a1eb-72df91bafd02',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//XbB/vKCg4rw38R7WfHu36d63+JcHQSW4FX+OaMuQXQpL
LB6DAdbC9sG3S8NpN41raGWnn/b3CSMBUYwFD0KbrwceoVstByrtnLaYTZYWEYrr
OaLk60NA88q9wAX1XOiKUB3nRUnfVojhRi/wzA9s8b53TsGtED0uubMTYPnsKbdY
g89sK5VlRb3fu9LZOS4N2aM/ghzyg2Hi9gdD9dKPHju8GzqP2Fgp5q3zuZYxb/TL
zTvxTlSJOg/oCfnkQoaWvaBTu2Gz/qqfH/obWq/xCxPiN75PnCaA+dtlqDP/Lb36
EojxR48htoYBPnqmVTxBxJpYj1m8a68i9/N123MyyyzP4Cc3a0jlTJIpLbZzjc8/
s7JxCnSViVZuSZi85if+EzaL2RWX9QcLomjJKcu8NlK7IaLa3IsDI1oG8x9tezSp
vjXa0bpuCK04zJx7HfFD5cESFNB8U8Gf/xgkSeg1TSVNe66GCOklMPqtHAiqwbBq
YW9WyfI/Cq7lIMnAqWC54n1PQidiLSHjIBpJ6pClSMXUkS/d05R8MM8PktMCKOV8
oITLMbOZEWNNjtEGD5zfnwqHIQDVK15Y0zcAgZRRvtRXvdAaGY2gdEEnZ4mjxnSQ
qnyjW167f+5ErFH5SEksJ2pU2B3DBNOg2NPR7Ous7zy0DavRzXmV1QUxnQnUChjS
SQFG4vADtJYKQZty087tJTtr8OMwzy4X2DGEU0NeCnjw+9/EljBBkFZbZDM0sAgD
z7GrdBEBVwPL5b9DIpWteaxKLtedUUuOkCc=
=VAWL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dd5730d9-087f-4668-aa67-7681897fa4ac',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+JN/Zo2w4DysVFk8HlBzYYcA1UyHMv0gSYNL3hbcYWd5Q
ID5Nnkq1rihCiX+osEQd2KM5XzvjaaxuhyGlTX6JfUuUWyegr4Y5m3a2HZXYuSYO
Y8QQGr5T5lhtXSZINvdiUXtQCiP0v23sk861AwXPi4iRCBKF0rjyPiWsx0StjtB6
m4ymTx2fPmkbrr5HQO+2dRvPNf/enPp14jeGEB/Vjlvtu/YB+FEWju44bgiwbykY
+n3OXLc9yLNy6KuxYDEz/8O4OG2WGnLQ/U+wGDZuwwXORl7qoVh5w7nFn7KyhPgm
5h1PvA2lfCTI/BceuJkxIc/SRjD8M+PThAYN3c5spZ3heqsR2l2tJFiw06ZfuaLx
4JEqcPhEUNDE6Ogw1HyyPJTUZkNrQ3Us4FHU7xJZaYXppvxQsJhacnqGwH01hgTh
v1zc27RYGC6yNYJXqFaih466nCV/SM30cbFdUOFYrU8T9+5vuoUie5Nssm+XsXUg
yUuJGAWGY9RymnPOzW7dgFtwwkEbTaxW4nRTMVMgT5Fz/l4l34SVgDOTW1i1F7p3
x4M63e5vNYKvhQ5M3Mce/aJ7NzAb+GYDxO3GPXQ22U9K8NtiRAYo/M6WgZHubytu
bZFdTa1wlcnJd+T2fa2K77/TD4hvrgpf6e4WwlocZ3MSnnb1PHpUli+gPbWcEdnS
QQEJ8nW/WyOnNcjUOB4CYS/KTJ4zK6mrly5IKrLw+J7TDWQvx+uo5UG1CuBdrCdr
ng7KTlgIbXg1TCZO91OvODsO
=97rU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e237c069-a108-4f7e-ada0-1863904a4b07',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+Kjfmtny3haaSlbqPUGyGIlVN+npY+Ql4b6dhU7elSv75
Sbmfzk9rXKtEUSlZtScBCZMygEmh6p7V9M1n3TTa1ltTlyFgzbRjiZeVy/o1k+y/
WrFdVJ7SiOUVyWxmO2RSVea2JJcxc+16kgohhmibbO5jFvFQ+DEsqBOvvc3+O+0u
WS+aGbwyCLn//7Jppbdibj7UMh/kmEAcVE0CzztXDjdY2cPIBLXe9EXVB9iOx6HX
qQ6YeaaMY6JWX1nKnpp9wZM63ZqC539NRSzc+hP9X4FpOYZjauNawcCTAOBGG7lR
AoFkBCOSaojpnIz4ppwYIhGTjqUJu201UAXIhpQ5oPNpgxlKzTjQhxD9KIHzqZbV
RoxoP785G1KzqMPbW2qTjqhHkiLxkA1xjbTfzhfiCcWjuvzlU69VOs+KylImVXeC
QEZplPvpmRBhyBjW4S4aiSwPv1x2xVq74m6/k7afqHKthJzvOkNsMSPgG0O/21O/
NpgR74Bk8ae7mKdMxedV9wWf5awXn5KVUs30FfEs0VbPvRSzMGtNJh2QNNMyuydL
6/4kiNPXhIbEhT5YIi5+cSDDFzOBRPb1d1HEXly3+qrUFtvuxvSDYBPTwBBgzwdU
2VSd0e+bU06VeKvu5WGjCLNCe9pvkpYUW2labnUdgK5KmffoVw0qPLOsPWwFs23S
QQEVJBJhL4YNRZzNdbiC8BLCF6hLlM2Vu3QKFvLrnmb53LdHUIFkhJFwuYCUprDI
h2YQvlNEM3JIPwXiMNWOWqvs
=UbN/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e3cee067-5ef1-44cf-aba4-a590bf95b4c7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAjcpa8ZhHF33rafLq5bxMSGBncm5+9LwKbiLvLIb5rXis
b/VU3jR0xvd/XEvXeqLF/BWiAFoBsyxigEKHu5j3VsjU6/Jze28aEA5tokfU73kw
yUtwyKlmX7KqipTZ6/Sh6Xp7xnj6wmrWzyz1LgqbhjB1CXLTIH7GCKqdhO+2/J+h
aboGBMyZBpqG8poqZjRMpBoGIvXqKbrd4X7fqbvknDGJDdiwiLutOi2Dnpmm9cdF
02BENj+hYVw2EF5jrTQGMP54ZHCmhRWbYbvDsEQOdIG0BS9Mx+b9bXdoTe9lUQXa
j7YLNibPXDZwF7qwLLSThVhsjIvL5lcQDUh5OF3ZV+IFBw4YIMzNZsV0gSDS+NoR
5PGmpIYRoCWwewPQe8uwxYZPHDbgjXI6vJZrcsCt+96UeSmiUtOmivOLOuazMYcY
3BxkaPRxApsES5AlRWnlQ8mKwfTa6iPkXvP04T6Yiakj73uB5A6+Tst2bcSLbvmx
NJtxTHiBwe04ObrOnw1W6Y+5oSVZiFXiurOqRbZQtq2pu8Qnhfu7AAxRA4TtbO1L
ZVATcJRuNECLZYAYBiYLxqy8Jx1i91dCmky7jhM2b8enNQKsz+EeDCWpp2HY/Caq
6PxF2be4ZnV/vq2znikWGyQa00GFzDC2aCGB273d3E1CQc2xc4+D4QXzjB7aT57S
QwGu8Q1gI/oCjIoka7QPTvRRToPKprsLQpo9gmb28TQvsXrHe1T6HAn/NI9agrQW
QI+n9TXp5ujhZGVrTwRoJMQJz0k=
=v3hx
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e43bcc32-d709-407d-ac50-d71bb1ed2753',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//R1JprnljK6aO4WRe++Vu8Zf6Foa8My6BfhOQAHWuDQTW
AOvPq9hcXgexm1N4s3FVlOwVFO4L46W5TsstjCdeJU4cUfDRtpdDVRWqPNI374NN
2iq1uw8a2AUGylxx9QLvQG3yT+/lNdUnd/fhHrjiA0BiH4Rok0JnC6hAKXqASpZH
19HWtJPqCSYcx8HX8vKFmqB1rCgOtqCw6bnZu2BFK6FhfQuHJ9BVQqVJ3IQIfuJI
ZySVoNBZspdg0IGBDEO/KUid3TlZTMqeW8FZ8tVweNlNEdimgPERA26vTnrIhKEX
4AcrJgqPP9a3ZvA2wNSxUsWaeUsTnaeUAmNvJkizkS/O/b6EVIEYl3efzI8r9q1d
3vVqvbo4lkcl8vpqlqxOsM6/kCjv47qELp0lLrKLKPVAo92VCcSG2uxFeFnX9HAg
SFBnQVATyfJjUbtS1pQPKoCzwW1SbFBYU8Z4EARQzzp+xtx7m1L/QEN+lwdTw+Mt
4X54RbZZdoZ6YwVw1svGMlGp0EuWJBjuVZQSjtmJ9Nj+XAF/a6dxLOLM2lSwD3Eq
v1kvg79nFY4/lfh1sTojWXebrIbMWOhXPtkZTYTJu6wS541KGuBJuzuPoZb+ISdh
I267Mp9LWDmxmst7+bl3InIpEiZg/0JUVCgEYYequNPy4lqBS/lVK00jgDUfiYjS
QQE2yYbEQm/eyVmo68koXtCzQY6fbDzPsG7yG4hkn3Wo8pwVpXYxKDAnhKYlQ16O
ci/myXuEdeVdGSn3eh7oL2nz
=jCE/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eb6a57c2-c9d4-4f67-ac48-244f95e724b0',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+KXhUduAkPQ2zN//lacAf+iyo/tTu3pFYE7ix+JBwMTTf
A17yIisAP139//992wOaniDPhrJ2ZxxLcnQ6ygnBiBWV9pFQYQpprS01e/tRAETl
Ru3uY4w+Ghr3aqWD00clPezAcZqLxoUMy3TKkBJgcZmYDnOf3lInfL2MoIr0yjk9
6hu7rKLJkQaBNcuW25KBBHXgDcZ4t2UG3pzg9w7yPrkpgzMQELFjWxBBpDeAch4l
oTIffgctoEfPl7ka2CSjGAl+ksDlBp/C2ilUMEp8rK1NoZPuNUHp6ZUY1UkCPcCJ
aPiNebCtm7G3lfzbnTrM8QZP4fdoJh704wonY+XSLSqbgIVgGvLwVmYvUg6W5Dpw
+kHDRpf78JgX72R6XLy9GwlteeuU+wRYu6h0vErgyeeY8AbnD+RcWaf+E56sIJMf
qzFUR0HWNzSsFvxIex0GkJzZduHQcvKpkBYPu9HMOaO4nbm5mK3gDPE73RpnjEGQ
yMPL3w/q9gri48sSMHXPLnSFAtx1DvY9llRthYwrqZkMf51chVar8wkzyvxMis0c
AQWVtOJRAPDk0ZTjYDnlFfoEnMsds9dSKfuFJBIBtrNlkUBpfXFWDfIWmTne1Y+C
90v/M6xipypGG9t20aENWDOAXL5K4Yi5Ax/c9J/cICLIGgQ6ZYpdNNQodoqCF1nS
PwFwFg8O3XI5l1O+KWthNb+dKIcM2TA0zpqIlUCGa6/us2h29zmL8Oku/LSBKHa/
1Ezt/n3nDtitebRaNCtZKw==
=+WS4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eba0e44f-bd76-4074-a4e9-03bd51f64ed9',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+J5iyi/V9wJHaU6byyyuN9A1mNtBLfXhbjb+9ZZhPqi8e
WioT6NpQ4Yr50k9F6O/RUBprFjsTGsZv2AarQ7U72k9zT1G3FEw+fdD8PgvKf4oD
fjLbdcAOZyY6vVxATAhjhdMXhLA4DHcdVqyA/nac3ONpgDOEelDvy0N3HRnKEr2P
TJoZKaWaZaXouv3vweD2zw5EQktlEoKafgXvnGQ/GVJZw8k+WKsFtSaUbpg4vtqR
mkRyaU8alIWsZldde60Ef/dyIz8SbSviu4TSdjt4p+iBOiKmzEJ8zHRskTzECMZf
q13twoBka2hm6UlnHHRUoGz19kCPIuyoL/9Z1asqQdYOTdBIETTKimO9Cvyz8QQz
e7sOxTxh63vYd2mZlED6R8CEdlZojL9Wl+KsGj2QHJ4kwIupgZ4ipYR8QXv+zU95
0blqXnEWPMD9dmsQvrzv9t51v3d1Ma4sEymSMm47xkQ0fcoY+o0p0UIyRokYI5dF
0ZkgtUEMv5ScopVOxg3JVyaZQmCJAdTkNkE2hQ2Ctl5vqGGcXpoBkQpmhsDNZQX4
Mmt+wSv4jSu2fFs+r/Cnaijv/OW4GWX5zJtFNVWkhqmIomL2vKXOzXNWQImRB1FT
ypwsS8uTf2lxJhT5hYtwwvwDRQg454TFSX1VFyFcdMJJc4rOpTZv/yvXZz7yEmrS
PwEK+iQ9JqjGqLhx9O9zUzdG5u1h8WWmh5EoF8W6guQ8Vh91KuFlzmB7Ll1MScSd
2t3hghJT0wSX/TcFLfCq/Q==
=MDYf
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ed64dff8-5665-4fb9-ac7a-f02d2f58bf29',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAqEJ6gsk+5SW9pLNIVsM/kkuMMWkeK4tpy4aE25C2vFcK
5F7ON6Fwbo87zdbdUHyzek7oqZ2Fn11uEiT/Nj8E9fNlYA3ytdhQ03QIvcD1R17i
RYcE8tDCNIJ24Q6ITAbmVD8Nm5JW92LkOyMscVEQThic7W56zwbAM/bUweAOyFFs
vqD1KCwgs0Vwu1odgz0XDgPvVQrGgXrwHT4WBDOoWh+pymTqjvdrk+z9QQJTzbJI
cVMlQIz8sesfrHyk5d0sJdlUlu5fvrvmEV4sCnksj9M+6bX/JU0pHmQWix/fDkVp
s30f9iIQSpurdmWzbMELlP6VRwSxiZ4Ir6TEGDww0uwtFtNO8d0SgZ9g6Sgo9gEu
sDVJkk8czsnlrvh1/ThzLn5nm5/eY5Unvslj39/elgLcir3NHqEah8WgxPsEl0mz
uWVzxwHrY+fRGUHfkndEkXZjdvB6YMIiMmdUwX1YJoGMYFKwOoUOIB+vGSJuOobF
x+wVd5/jVPRHNFDXkdQQXkiY9VJrkZXh03HM5rXaFHvMF92lBRfSqbQYrixfL81D
DDFY7Lmo/nzxgp/7ys1gg2jd0M91ZZyY7INRaGfQSMK2jqV8Ak4jVzDSbgIld9vI
r9v3UmkDaIEImKIYmnmFumV6/J+5khDPFq6gkussrjpqZAIMG4YkJrFCPvYDf7fS
QwGb1LeP9qbIbzsX4RrhgCLJUeXZo5SNJHOM321u/9pAF5PtKkuUm5ayrYjl+xIL
Pw3M7PDf9wWeCGdNz+iXfGDZgqA=
=AxzP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f4e3e785-ad3b-4117-aaad-085c60d82266',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+Oth8QTNnTDxfFAkTPkprKO0O4ncUgrUOy5Y7j+KWLtXS
5ofw8AtHeiVnO2633QScmAbWy3fDjIM9WvxbQVIXNZeUP0DlFFixwkY0VStC5non
wLkYDMPC6d7z7odREF9bUq0nF02Z8+4KV48DPVG72aNP6Zt6w4TPCA2K9A2fRt/U
7ATqS+Is8d2e7SK42L8J+peEVdxGKHQAUE+R9R/CSBHkvW4QkVQDasBHyL+nAzS/
hLZ3l/2IqHBypjcE8voa4nTY6xocIKKdcUz4wMfUaNKCuyhsrUsO4g+L08830oPV
OwTaKTTxoXHZfVCBNyB7FxriUr0edBXl10x744Bbob02qLDFfA1dlsz0J8Emn+Vu
eDmaPZk5Oj9ipZTAbbPRNCzcZu+ATPttHBGX2W66a3VnWXvRqSB+FXaWClURN7b8
BqcD7w0+wc7wAaO4NowEBdslxW8j8N5mRjwFFQKy8l94PmbTLnTqwlXqeDPcWLNq
OenEo4dBxu8qvyHglIeVNIRCLYY4hkOylFlrYAQAFDmrEmfdgYPdmVVFi8XJnygJ
bSuxKKsyo/gIXTq8CbnNbXVAzg6ks6sT2VNFvtQYyQsJ8aEUWYtYJXtkm2LhNvgT
TDRa6Fx69i7Ww2Pfqn6dKrd3ReohEbaaQzagvw3Kw3ijAgvgXJgpyfupHURFri7S
QwHbR5L9s16UV+O4aubqnEwtdzilGsGv8FqiEfRM7JurKNucq7r/qcpY9JTOai0A
pZbn/JFXfcTpklj2N4Koz/riero=
=BZAd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f988150e-4f5a-415f-acf6-68f42cdb6a3b',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAwNg2vnxGTLimd2AbLHcHNcmRmXXCA7Wa99Wpls0FDqMj
cZqR1kV6EJUl7c2NgmFl/zCVXnY43IY9w0DFP5Y20NkxpYluynyI8D+S6cgQHbqY
YoDWtR/ZdkI8sxP9xMmkZutvaoudEXR/zbUkj5vvabH1VJiINzBKaL0+um6gmCHK
NYwXpxuWYSifj0i8WPqZGKmryC3ehkeksgXZtphfAigFiRHd/XyUfCFCosXildvG
4DXnT1NPSAEsy2BkT+SQ5nkce2gHAv0XtaIH+isg8SJixwHyzGudICRhOZcunemS
SzLlc16GkC6QNTPEXzVURLzasj5cMeMzqUtDuLbyUydJ5W7eeWAFBD6V5lH6G0BI
+SfjcURmdg6BikjSwBt3SrnNxGu354O6eZ4xmsSU8yIubQP+1ve/OdULXMqcXcmO
eowyvko6Ocv82MLQmdRpDvkSBHd8fPU3zmDKkuwbkx4Yvl2qCuGPODcKmybsXkOQ
RCnaONIyeDn+TjfyM/UHh9v9LJ8VLIVYGhVV7wDP9HY8J5CdIfWUr1j0bstZvpjg
s+NqtGyjoQzaziX3V9nudLOtnyP8fGETm3YtHs+PExdGdnBZfcAYSdJxOEOIMM1U
GnDKUJvb0Ym1LHL6jZTP4szgDYdiq+XdAfuoPVnGWyhdv8hv/8RE9BU4+AmeukzS
SQH2Dggy5WT5aT8uMweHYH6gBTN7kk1aV8QGP6ZPcLuX/bDf29MIeUxp9/SqN9HN
/B+zQxPy9O+ATgMMtnWlFMHsQbeP060xi6g=
=K1ge
-----END PGP MESSAGE-----
',
			'created' => '2017-02-06 14:10:24',
			'modified' => '2017-02-06 14:10:24',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
