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
			'id' => '02b8233a-ff6e-44c2-aa42-1fe978cf3fac',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9GtLkoHv9c5HI3XUpiZPzrKfD/I018PD+m7IMd2RBrX8y
WywD5fNvvXyQI1zFdwIhFSKeqLBWQMk9vamYzkSd/sWOxeD1Xd36y/olHQoV07ar
1ulTgAgDLDysY/ys8gg5/ihcjHqrNbEUpUX4vFsBjbkRyUnqp6U61XTeF2hIrFJk
RGVKldfEdGCr5Sezfp9zj/RcxFmmrrZa+ZM51WxpycPD4D11eY3+6Dx8CE9chTVh
M0bN1qEIndOiHK7P0qSd2u+SJmUBVojPgyVBnnTZ9Q6AkZ+P8SjntARulejt9PDB
uuJBuvwl5HB3Ux6qCF9X7sebbne3px+2CSqxi5qFnRjj7Z43MXy4FeRRe/7WLFqj
Ah6uTn2HYjH6dNWFxxcXfmjMVc0b0TXsMyh1YjQWLwKzaoZ0GJork3Hvzbmrnti7
ATNddIj54l03SfJX2VTZB8lrSNNKBJI6L5nx1L9iNG60VOU14g599+t+ULik1xl2
5yIl+BxUwNMXfvaeMJRpYZnPnZjN5FJsC7EhykccE0ZFKze5WZZkkyT2tdD2Rmdd
Bg5EMDrMKX/2utbyN8wsjxXBxJIo7FvmHpe0PY3ev+63XjleXvk58Djt8C8mfSHO
eqVmficHTJKBs6pUI1c0FeAwYkf02rkuQRxsYlm6NjL/qr5EgnnMofTKjcg3LG/S
PQHyfc+TW1661AnDRZJcuNIA73ZPMfSEWtrZkjhclnFRjSMQcvRDqZW76/u9vB86
2pBSwmVVq7hm6PtPEqI=
=l8aU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '04e20f21-f279-4ac6-a057-939d8be98eee',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//WTInsJtrlAWHnrEjwHWucjX3kxUG0kquSf73YjPyTsBH
pEazY6x0AUevpMlr17px3qvXkc7vRgV0GKnxm9HDR9hZu1M2VXSrUqBzhxM9vanD
C5ucfB1l5OF2kH0cZojEdlf7Hh7fNBAUJzdJPJizNELFj9zbQuESSAljOVLp6GuS
J3EIBVCrXVzxg1iYJnIpN5wI6UOgPFxXB3GDrh6nqttlzYdc5lyjBpOKTwW+Bp/C
natnhZz4Kz3ZUkTcvyP1W/d235q41fWn/n1kX/vpFhPV9+u0M5gBvdymxZBCvHrA
8rGka49WR6bCD95rs1a7Afwu21jRU1nRBNjVsrDJ2DmETR64tRqiea/sL3o4fO8d
gog/UI4XtokuiyIgVQr9are1eIMphn6NoWIHYTUUdadbQr7gNsCOYFzdL3Doqlf6
UkeNcA2CRaQKp4TwsTVBYhu5wUa60Q8rxE8CzbSUutLoZxaZiJIOl/61qUPW1pHs
XJRC5tdE+tH28lDjkaRTTJmCPtnau/JLOAWfbIeL6vV1Nd17TA2ZuHFvcv+foSVi
0zsr1gkmN5eYpry5gSUw9qisn3uHkTiPZtXlsbQBboBjRvm960Mt/3tCZNKaIVzv
t6FHluyHdnak2tOMBtZRDf/h2nQCZIHtCuQv81V4rtovvgqF3HPBcNLTYHvZk/DS
QgHd4teYuIo+tKrCFlV2zCwzAUN3i1uZaD+/pq5Kke5OTMjj6VmTY90KhvX8Eu8k
sMYjbg3nEVNeZlb9jUVNp9G/Ag==
=4hPO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0b6222de-4a03-416c-a04b-acd9fb4341b2',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9EO96G/UyJEKlzxa18xcx4G9GloV5/fi3g3wLlkw13You
zNzKxRqDEzGaEyL0MhkllxnQaxDZ+bQlssTO6Db7LEcG8BsN3Lqhm1eE6t3aIOXm
3QfBlRAao0WXM8wTr3n9DyRCaptYJ1DgaO1mKwkm9+EtD1rPIa8VjZF5sSpaJQog
XIncgcuJKqvYcMNOC11wM+2pmASLWrkrjtMTUgzLNON0u5E4DmqqSd2OW/THw21C
ugeUa4o42P7PJBGRBs6tuqvo2YsLheaJC2/Ks4Ljvd6N4x2hsJdWfbvSSaOna41Q
YYtAxP5ox850BSoWxbSxxUzwwvqki+RyBwVwQPFdMDM1Okui2kEQpmfx1AiQ4C1w
5GXHl4bJTCOQKUERgWYget6VlCVSHLmfvse597h8XtQS+14OS2WO83I7zT7cRLwV
67VzmKH2xnZaK0hBPSiKR6PiMAbFbe7aCsY/K/GN+nJHIBqpmWqyrNVnDEgNnCkj
x6Ga2cpHEw1TqbIxyyGf6Eom9RWnApPgShBn8xWFcWR0nZEoEywfrvH6ZqRMlolY
vm9nCoc8k2Baq3A2I8HrbDoGwuIWDsKlQ1zbHSti9JIvLACfk95xqVcDTzTuYCeX
Sm6lnCA0xjEfc14SPyddaX+7NdagfChROSADbE2nQ0avRbl8ZKYKQJYsgkraw9HS
UgHFFciBrupvyJhgvJ053VamotsD/l7qoHbDsgkiJ3BPK229K59J6tRPrvaz1D5E
3mA/PvvItrQAX9RvHdOfpKXZeFO3Nm4SVk1A7oHnSupNs6c=
=HBpQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0d065e97-608c-4ab8-a2a4-3e25e93644f9',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+JeJcmjYJ7jYJUv6Ouw1MYIB2sGlkWPzui1wLLapAGqsV
ubPIAEZuOYbXyPSnILg2fo+3YW2fmODKCNwMeuP4JpvuF/trwtOd57Qid4tjUN2k
/MJ2UXshRsU2UYvZ5nPWtXdCOqcH0SHNON0CdY46IkyfVv5iKvDnjcHJBFKYwIsb
qLxp/C2U/DSv71RAZTjkGdkTgZXdTDKUEvcHb79fum3kHAltSnH2fjJ5B6sSS2Z2
CKtVebhTk4OLchdIMxXlQmPwOu2kDpdLi1JUVAtN7BDg1tHq2EtoeJfMsA0oiZJK
ScwdGZU70NKqe3XHMvzyt60P1U/XR6ymh2fMedx2+o3lQPTahHcILjtCShm5E+Hs
TH7/wPOUc9Kw3sV6LZUp3m5+Z8XqvLVqlt5TOGKoAs3YUDhcV8RKaiFne0s40pWu
7N4Q4+Ptyn5KJJRHc4nkyh4Of+AEHkFMPNYIjIjfQu8aEw+42z+Ev3P5BpysQYIH
xwcj5wh4zkAY3RbAw3+oESAGWgaoEQYi2EnBHhubSHFPANDBbV+iZj1RgU5VZuC1
naSc31A77nZoS30g3LIxNMXQz8AP7DuKjFBRkIAqW8izp+UUp0Ew5/CvqD8OdjK5
XPmfLs/RhRMWdoh16Zb2rKkEI6Tt3lzJFWCmm5k+iqhY9HY1sUi05IMMMk2GhTzS
QQET9VbQ9ejek824ZIW82vaER55VS9s7tnrTEc9eZqVNA+ihtN1NuInDFh6+q2eo
rGt37BIfVFOEva/cwlsgmFZ6
=rIE3
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0d887f1d-dabf-405a-adc3-dafe6a0628f4',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//bA4ue5gms/k77RyrVGNWjs/wnMgU5tT6FB9BGbWmLPvk
FwW84yX8KvrxCIa+fTPtdmQ7K20uTueCMx57d3IrLZ9kdlWV95ckOkHQkCDf9duZ
sm6iwVIkH3ecUKXTspzLxarwsHAJzNsh5ZT/ox+6h4KaRXfasTef3aUeq04b0L3S
S6iVrfm5yxJiR6/rIHx3+WkUQ7dGaBe130QpagAT52HjQO0n35rIlaPPpjE0vDHw
QiPMeTehUaQNpq7xP9ZnBwlpj2Su6XddlbwTHAoLDdqoI+f8865zxh0Q+0x87d2O
RULM6S6h0LEc6CaOxc/tb7b5uNsyPknTahtoKijxtvHyGQxSteA4t0sLYW7f3SRM
+TK5s1KJ9u6ujLqbzRzB2bApS4p4udxsKbom6uKKUQEudcOEtXB97M858UPmIbYm
OZsWLQXEpBUhld7Mwxj1HXmoSV0rI84FRMHkmbmzEHFBr2WWSeeA8fKGsp6WbMDF
GoWHMkdIRM6S8FM7veplLM0dcCV5qbi4LuQGmueAAjNszKQnco+snNbOv22PNyqi
VIlGUmmWkKk4Vy3MZe/Rj3tBLneXJ2foYgyk8p8uhwVhu7ADtxwq8iVaHdsLY8za
czltHcintSAJLMrTLo3mpOMPaa0WNjUJGQxAeP5/lMwq+xWfs6lOSR9qdPqICjLS
RAFcIyfdZec2jfB8EdkfEhk0FmCGF10yJ3pbcuv9PQ5+BiJaR14l9itLrfg8+TMl
prhf4uenZyd/j85Zz+8slui3GeT7
=lCm+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '12746c2e-8c31-447d-a351-dd3cb947c37b',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/6AsSqwqmvKTlxyBd0bgI7Gcrw0hzl54y4wxDY5GopJgIX
pDD3Eapf1gY59po9qBoBstlnXCrBhZrUOwikS9qkMdSDxE7SdBy0jLalYVt74zkW
TnK3cj7CJVUyALdNeT14ARhOsMUUyaOojZC24o0JeR8jUVyJfHmcn0RbnyDyzuWd
EZwEV22A/F1mq3H6ta/XE6O6d3rF6rUPY7xHXs5CCWrrHUI0mZUN525iaxE9m5AS
m/MAOGbLZj5u3mJzRF+7ix0X/O3FbdsBrc1JdPWrBMyXElOdL23YGKH9uEpOYMWQ
N4HKlaOm9YXpMUm0dzF1SavSYL2zStgoO9R1G6i/N0OlOpOqHq+bEojDJBaOEUAR
itE3FSidLqgNcWumHj2Dl/LeOGQI/4rjsGteARY3YJWoiiyohQi7WwQxqYfNRuV7
CeCG4mzFgrAbedj+z5IU2On/LhjIVg2inp1oiL55Qox6dgclZ2fntB6OHEmg9PBX
hqowDjWvEzr6Bhrgmdsdcnv32R5soNw8aGHWTOXJiYtXGd+vEnX+Gw5tC+HMFqB9
ftuKHIhb/lUgIdqah6qXsuekeRZZ/yj2OnYbFoILlFdZpj9zdv4ViDyE65xs9tBn
ki6/bOVrSVnb/MqIRHbaFYDEq5v+BQ0/wrUpTMrziklv8K/tU1cBWHFWpoVPqA/S
RAHwHPEqn2QK8F/f6+MSpCvZ5S/f4O4GjkVBKXVyf4YFrnjoRzKNbNOeURSandVe
0VB4qB/WOEJCKsdD+OQ0jSJoX+I3
=z3gv
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1276ad38-e326-429b-ae38-978df6da685e',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//ZJqkE9c4Web80Y03C3yLRyu6P2RAPTBpqa6lcEyaj2Y7
doKErTPRJxr+PlKM165gJB/903Pt+EYHniy2IQXfXrb9YMsK0zhjbNQiWf8wfi5G
4uMx1szk31BVJfhvZ806UFhRVHxTSsiJ6+7EsMCik6mjTIy0CnItOdodBptWk+5e
+YhpmWe17PvwidQGZtO9WZyTU6uwHcZ8K8LoiR1b7M0x874E86k/ECX/UQeRLrmC
wJ3ox3C+ugeIzK8VG4SelecAKcRQxd2yfvnijeTww9AxnNi7V6scw48QHZa7BE/4
qtMtvkFPHFWqObppcxzfOECYN5ncalarm9vW6hyX5pN1tUaAxuE5OoS0RmnikdtA
lUB4VCsuMGxa2NjZ5vgirghIID/BEINMyUhB6mr6RPq91VbwkP/PyEIbUYZhqDpf
7iA9XLxKBkm5I25s84TID4B6U/jSb2HzeWJhb52+9Oj13zq/e5bNfwDpZ+ukEDdg
ik9vOS+14O6WZJ5b3jKIrFAQVGlySuVWN5nZzC2EgichXTfEk9wyelz5aO++diBd
WkB3j2xH7rY1Tzr/XzKsAM3bPLx/uadTbz1cRBckcJ1XQ7Ra+7NbWmGLOXJJ41tp
y/H4RlqFlU8o1iKquIxxGGWIJj+OFXeyTVg5RLCYqqpDkag32NNjvOPMhaFRmTnS
QQGFrMz2THNAvQPkHSib6CYH3kzwsAsZr2MxS6vEo4WQK2PoM16MJECD7Dwy0chV
BZWdxbpK1q3f7r8e7HIxIUBs
=cK7c
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1317c557-abc4-439b-afdd-afc13eeb638b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtWaDpnTvu5hYv58SV3Dz4O17yy3TXpsCrrnHW2ZW/NnZ
faPyfctixIJxn6DxN94e3BtTZVgFN5//gq7/CVmnE/faQEDI9DbDjF4IFDcjDHrK
/Bvpl9MfS0o7oJyN+uemqQ8Pl+WPaGmQps2tOUj73De4B9SULCYdkfozkzkLEhAv
JDYf3D6s651QdmISEg54habuactJ+z9NstyZmWalNmBCo96w+yHzgJsr43pBG7u7
lmSNSqW192SpwOhQzs+3LodLlu4QtGLnZwSNoI3XGNayEtUZ3UPUacW9fuAKYkRb
NcMKbC3jDLRtghDkbzMEzHPas39gNuHpKfbB7GLF3c+hbdp3U3OZ83H1HI+gcLXw
axTw8djCDzapjhNj1QDS7mqioSP32BV2XMGHKEM+8oWQOrKLEVaR6SGss3/26E+G
yojFdnZzmgb/eaZclzLfbrEyY4PnXzFKyWBeXSMEyPxjBgD0FpgDt8iIMOrCUFsD
Updu0G4lNFQQK+XLqugW9xKaBmdsHmHPtZ9KiTNWKp0CQ0ZGDxs8LXj5AhLiBYrQ
06CYJmRlAZiYFwnFEmtnXzwaYiAHNJJzkVqakC0G+LqzUijoI3DybgLR/i6h/AYM
oXlPleMG1vZh4/UOC/d6uMGPbJnUye9OlLRdZFY0UTVZkQ1/CO3OljZoee++2kTS
QQEFoUGyjtNhfD+BVANKQgleLH9598Td9L/cDv+PWzQW/9puDzgVRPtiKFXtjbN+
0lv7clGOnHyPJVYasR/UzT3x
=BSNL
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '13e87d5c-b73c-4aab-a18a-03dc3a7e2627',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+LIprU9YJ71Y07gDVWb2NfNWfxlIyXS+jsXCiAjVnk+OZ
e72t9ufDTOkBtB8DKmxLntJgWEldtDmfby63GWcX3ygOlUAvdQEO5rK7WjNAUp5W
aScXCny33sZQ2+V6rZpRbSdM0kgVzqCYMWMzmud0fZIrKZLRkOf+Rw3dkrHrND4+
tStmQZeNGVaOf7vU1yVLH1uoPZuYHCFcNcOqpCD+e766nDnyq+v7Lexyd1LoWhNZ
FvBfVvWlRou6H+39oxeBIR+0qetz/i7knH4WGGqCfMeMYZv5ojqudAJoIo3cyCdV
q3qbGx30AnrpU7IAcTNWWKOLiniDaQ6VaUMlCiZw4pFhOZvKIuF0lU9KQwfRC5Kc
lq2YRaWwoq1wtqnkXATSXualmspKG/vk6ZCR41xdtlhOeQdjWPuuJn3uLQLpd6+U
/2D2RZAH/HiuUBxv4QB5GD04QGfuUDIECa5kBp9G5Cfbw/XmpElt1BSxTJrPJw75
BUn3Dbq/pRC1croF68nxW/5mYPgvpTvnBea8EsMuPkDn3D9CPLs1nXyhDd9ryHjd
uLYsINeFPgHjVOv2s/F5dZf1BpqTjmDaSxqqQNp7JiZon3XSvrUNioe9ap2Sa2OK
uq93K/qQbWdQ1XY3p3zroTazZ8uVieAsLKyrFm7yEweuwiuAPU+RJDrs6awMXCPS
QwEcLnfI0hsd4JB+p17uEO1edEkVPmntsXMp8YPNpvrWl9pD+kgbUmRAwoDhRz5u
mKUT3g4YdYyM19xaG1nhx0droic=
=ZEmv
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '14027117-e7e9-4533-aaae-53a8f5b1823c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAkttbtjsY3E+tbtAXLEJSJN+WLAVXeqnCEeMWyyVVXELE
zW2ULu8scElJO0Tzk2QQCLrHAzCy74xmvDx3y6B6scluKUj1igRsamEZSfx7+/zV
nBOJLvTndDcccvNoyeRoF+XwuWZRNaevVYB1wcgry/0zmW6WU9WZaon/mRcT7JFN
ldMndWk7fZUz4CC80xazDi5g2/CYmyOzOVh/xScU6IheeMEvXeyYA/mMwMDqqz3H
NlNQvXpB9Vlc2JoQOhoblDoctAO6CwB1gk5tUi0SX3U57QdcKpR+5vFbNxxKyNBz
k3a3D1ALiuuSF/izJ2eco5NH2UV/dA/tzjEpcRpr1IanixIwL3Qp8kpoPv9N0pd1
ki9OZdYaOeNkl59l4tFUPtcouagCK8VzNbs0ztY+tplLV8DNP+dEP+aleV9fMo4Z
uHM+fibyfbuvZLt34RvvlAs9SwaiBJ8KHEmgW+VFP6k2KtHC8V20BXXsuKNwrhIL
38Xf5vu/whMpxJLKBaFOOwSRYlAxmmccc5pl2ZXwm+1yucKViMZ3jt+U1VKOEjDy
zmZjyXY5CWzPju/vm6AA+JGZFS4Jv8QXbKm9yIYOypCSQyirmGDCGaXZVISF6M9p
ldu+41sA95o4sUQ8J1dxUpkTkmksMKz1gtZIdUCrLC7gIRpXpbbbwuTvMPT7UuLS
QwH7xZsQMfk3qX4uWStIyBTSZ631Id0cE+E0eQrvMUdmw1M5u6+kZuElnaU0l4VN
+Slg0LFUY7nDX6E08GEvyBQafn0=
=MSX+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '14d88ffc-adb9-426e-ae0a-07814d5bec6d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/Ti7EGJnRKgJl6zOR+5Y7yc1seh/6aW+G3SL7xUPgm6jx
QQ3e7WvdaI2xfLZPpghEWyfwLetoOCyyeyC3PyUdhNSVh0tXpS7w8Twh44GMoUuj
U37MEKABoO/yoiLD9RoaJzkfdaxvLm4trJ4ve+kvf7BNeIV2D6uo8PU0BtEjhxFb
Gwzk1oHVh+X+Z2O3M7+Zw6wZFFSm3yv1iM6sXluuGSNbOZjn97nY+ntHnXA7eOZJ
AzmQT+8gGpoRTVgsn8qwVnitakIKS2Ijl/tNTbm5yXvuL23WZQZKkduGfVne6y9p
uWBqheAl+Lol/yznFWn2zlFwa+66SZUXopONgXbN49JAAePVw0dnFR1eSFW9bX2Y
DVic9Hx/EV4TpWvGK/YoEDzW3tD1ninEg9QTwynh2sq0kp1IEErtjn9xcZMjhU1l
MA==
=h+0x
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1721c489-50a8-40a7-a40d-59c5fff3b25f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+Ms4EN1pBNjA7kvGMSx4KgVHm3j9RpskG/vHR/PIcgEv3
1jVDER4L2LOARLRwwNXam2Q8FPqLbKtI8WtxZg1RfqOcDVJ3EwHdCjB0TljU7bXa
Waf3Ew7yjNTwwrzHSvkBIkFZ2S5GALmUYhPQ+WoIn7Zc0ucJQjCtpq3In7fqZtKw
+xrjNyfXXOIJeSIvM+tSSGFBdiQu9qgDVh4iPXqrApeFyW7P/yMCWczVLkzHXAVf
UeThu1hdLNP2r05Atg50BW9w876o6Hc/79QVvjga0E+kzIhWvV6PF7nz9AcJqCoI
/gxNNu1E80FxOdmhlbtiLvuOSNf9Pvb02oa3W3WNodKJ0wf/4VhZZ91lBoxaLj4k
1h1Bg9RAYcLRxrwjRDAw7bdBab3H0Lfr7UA8g72bSoWdTEWgPrtuxZq12XGuB9Xp
fvifulE2bBCzjVpHXCpRuv9x7djc9ipGWAeWdY614/rjDd7SbpNJqTt59C+9Qcyk
TQyGpaQLnmaYYcnEicbTGRu7lcfuSKc60z5xQLL5PobB95yDOyXWksn6e/2yTTMk
HXEYVH9Nfdjp+dpFhnH206ikRJngDZ0SmqfgiBdlN2ZOjmi3Bc+Td7k/I5WQjLJd
ZfrUdbfl5K4jA4jDHj9C8dboijGNshq6o+VfRRo4+rBW1qKkQS9QHl8x6XWrT6HS
PQGABgKgu93A5Rl97bhFfIA+tC4XhnbNnfVk4gGxWFa0aij8A40wup2h9l6LxjBf
qL3sRvnpzQMCJFgqTl8=
=ROLk
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '17675dd4-22ed-41b3-a2bb-d5aad274f4b0',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+OFPS302PgC+cNkqegAuTYQeyQPmmxM+JstMC7FilS1R3
9dkw0YkPYs3Rdd5JgEv/fh5tN11s8hgWlEgxkMwQHKSo38dXgBkIJgI4bzUY1Ktk
N08OPR4t1DNKKaBYZWwLAbgtE7jtyCGRTxhULV32utu3v/uhJwn18Ei+rDZEhbsM
PdXPPTxjrmNAPTIHZDOfE0u21uF6hDBKRHgPN6xdysIMIFDU6OKY8EuetgST6B5f
6UUFKpUOOzcXZg4GAJcO6Kys3es28NCmeiVsI2f3rbFl5PS7GjTs6tJawWoG236g
gWfjj0ksOfNI1Wy5/Ty3n8xryHCbZcq8REQfa70JSrh1mX24uGZjW1zFm6lMtrbl
NvIcI/ucRvwFwpSV+O9KTK09ja64CBnk3tkwVzmmZJ5m/WKnGlrY40UxRL9326of
djqNsMxDIfjR66Dc477rRNKQWhaV4obZKAWS7YenswHhFDlc2x/ygI7aAaFMVDkp
UuJ1iJUe7xDMR6KqiIXV7C1W6zT1yBR4BzpqDIO46nS8aE2G52rCcCkMbdilEZDL
Z1jwnfixb5gvs4ZOEZugAwJPIAVyithJWTPSzcuTGIfQdVR7p3Oa8AefK/Y55ZQ2
nD4uk/Au9cqjeHjY/ElPRQZKe32790pZuq2f1RLFrxncDds3q6yBwJAg4YM7663S
QwFEOL713Cd95QPJl1tQVehJhJi6oDH+BbecyswLPIjb0tHl0C+1alVzgkdd3RXM
+1mjosf6++5zZdV4AYf1RfKeucQ=
=bK50
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1a6a4caf-a621-40de-ad5c-468181537334',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+M0GKiC9XxWggn16tRqL4IGTnjDjdyRUA9ihK7wfNdI4x
js9IBXNKI9l6b3IMpmwTi943s5q9MKraZONu2u1vlj3KYifoRiJ/2KRYIt1TX4SJ
31oW7YQf2ls4wnQfVISSDUtKqXfCrFHj78EsfmNWvm3F/yWqMp2QF+norxhmxkn9
bBUJTVzcUIltHzK6DsrB9LLR4nhLJioz2HDaxvifXS+ntjWJdbL5a+DAiiYfO5zL
2vongG66y5WkhGj7GC5Q5lo0OxyFlQZe1GxeftngpHFpBRcuPwRqnCuqokNZt5bw
DWqRoEz94m9em9fYUxWg2O5J0IHTjqsaKdcQtdwkfgXwCZ6gV6O8mbcINxkK1fv+
GnJ/OnKS0uA8Hcu0CMEiK5rBQpGlDpzgja+kNF/VjyBvtj6t36s/G0MPded+bNQm
ctUOoMzvfmgVTTvkfh70zYdPJeMA1rXCVflxJ2pirbRN/cxNkvyFASzEkWuAyuyE
VrBoJZ5mlZ1MZSWju7qU+6RCJirwnUYJzXZUaA0i6nlqW8d/Bh2RLnItwzNxa9oJ
ohCXcpYag7Fv51vMsP2BRdqmf1q7Ol5U071VOsJT5i44I1x1QKleFzRUseauUGR7
p1BUUCRj07fCooES/L9V4Idb7PHTt014hDAuFWvMD7gyifbAhCm/S+0a4R4j9/fS
QAFRSFOT+Bi4r6Es5jmucJNPFrQEQzvbJf68z4vEAHKQv006yEzsy/4XsoXdCW9f
lElZ+HqSswExmdTpvViB1zM=
=mj+N
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1ba74e3c-c76b-494d-a91b-2bcf9274bb37',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9EjgYGFZwveffu0tkR6aJz/T/fOyhTouXOaYe9NzmPsc0
iDa7YTlpza48n+aPg5Eh/gkrQSpNgux59tF6RTI0LN3O45WvnjH/HIw2Qa1tSM14
8HhpgynkdqRvKVB+v/V1zggpiz7dtMezxp1voswLfr7z105JjqE/Ju3uE0mterVn
vkU1Ku91LDtDkN8mScrej6xwUJlPFiv7z85G4gfChBAlCsFonr9XC4C1mJJcHsYD
eE1WTywR/OVR1n9q9pgI4V6SAoiqj/tFvAvgFhVAHNlRn5e3jZpvV808g7Ms8WFI
LVRFc9unh5yl2GF5V6bZR237plSSTqE+zOeri5vUfmA3a1L5u+RxsGLsMncp5VnM
wEmx2K4BTMadXwNfYHI0h+EsTzB/bVk3gmhNpfoeHvIePwxqv6rPYyT5HgnRJ/59
W9C3UPMTiu7/iOHo4tiPMCe/eJNJ7NNUoqPj3/5XWLME378yat0LriUZtQJM9bXY
Tmgbk/jR6zDYILz3XEGOrodEzhCg7AvY+qyyZIAqMJX4LzqqL6C1zno34dm7yRgL
BzUQsK5tgcwuGR6SohZSzoqZChtNjDaRgVO0+1DPWpaRfUCOLryNDk2Ks2/kFCuI
eoIkGH00Jhb/4Ka9BgMit33uUDBbej6yiLTKdkFa//IJn0bNs6923u2oWKe3tNXS
QwH4HwRE80D3YhUVzcKLJEanKkXHEpbmriX7oEO3L91UQW7Wq1UeOYEdp4iz8VBF
KKHtBKks/yPtxEpUk03ypYp41Gk=
=H0Rg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1f4357c3-ff65-4d84-af4e-bb18e31b7f8d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+PUV4Hfp+htnhPFsQk/tunHqZ2RW61AOgUStAVj90LFim
LL9cBT+QRJdFCCU+eye5DBpVTbftkTxv49zO9UzrKgmVIE7UPEfu+ibtNJI4qZYF
FRrralhELB33YHiDkHz6oBGQpstW1NiaQ3n75CWBQjTgk7EXjxCA91FMIqO81Ktw
A89qVLThPMyOWSvLGaVTrwXyP4ySs/EQyLSwIdswISroZXq+TeYLF8Z/HT4r+xWH
z/TNZjYzSqppWGbYVuR6k+LTDANB4NRGVwdJfLyYMqgUyPu9B+oKGWXulGaRqlPA
8gy5QMtzPMqoHOMOriAQNXWrKfzRSVjhLyCCmsRVldJEAXMz39QCHFkXAUQrW1yB
hlZkknJta4wN7u+ZiK2IHHafcQ1jrFCdbmwHN0HuSBJRlX/CyB9Jz6CvXjF8xA8r
+zNjXwc=
=NZw7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '20aebf73-61f8-4fdf-ac22-17a6f3f41d2c',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//bGH4cc12K2s4PHuqlRBHkra56CKEnCJ5q5U9hc/MKrp9
FcKePwIpa52kXagJmmEiiq2Dvn7w9fINptGza5kCalpt4dN6DA1iThVIf2IPIPWg
Ibph16jaUbGfK/hAgf/AdgPgoO82HMCRV3JeK7WoG/7aUgCY61Ae3Y0Yx79wEpBh
YxzzOJIK1FHZ/URHyv0GNWvFS4acszulk4LIjrFUwuGVvtIS5SzdYxjEtiXHXL25
YtZOFs4koYNn2zn0IqFS4zgm3CjIQt2/d5jbj+3Qq76Dz4owVQv58zTcT9zxsLbN
HiyM3Ci8Tqh3odBRsGjJr3mt4tbj0IEZNyeFXyLQf/UeESGstXAQlL49wivs/8R2
SFD85Qa4kyRrIBuYmkQAzRBVvbS0uIJ3OXIFk6rFYCRWU0xi71BREZhzfXqE+SWx
Vw/VDU22zYvm5kl8kp6W0mDwQvHxU1saePlR4Erd/PvFXsaYBLeo3nI2/YhW5gAf
LBOFg/0k7f4OUlwMDb1+mfDL4eG0bOBtODuHFup73r9iNdauVnuOdOOvInC8QUfb
F6AXALkpORxlq4B2RqcFZjOikQ/risqeLyoRJOZfdWEbHLlqI/U0kf041U3MBxFB
UmnxrSxcagqtue8epD7oBuvXhro59BqJFkEey1+GwL+awwKRybwNRMKjaxQOl//S
QwHIG69E7/nwEHoKo25wiXnawSmdkefTzsyusVnZarZaMCpj6YWVdB4RcIdm/bA5
2Z+4oGNjtShPPFKNabeq4U+93Po=
=u8Cw
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '20e48013-e69d-46e9-abe8-6b0c6603c852',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8Ci/+LBaILZPnz2yX++hcqJq0TGePWqjTijuA71vqgRvN
v5ucZFEf8cVlCZ8peKEeLIX9CG2PFZ52ALRdS9+KiqV28/luJ6HHZ6nsgYdHclPQ
dhZaEm3sA6xRV9IsAtRDNSFoaZfZpl4+HDI6wdAFuSPpiNey6upZEB0r6blKvcxb
LWDMo3lhPSj3q7QnooCeycKeBIYcQU6DdgBw5SctXJmm5+P2MuvimYKTv1+pLkIG
sJnHbIzmI4TWOL6zQxPC24D95qJY3NAmG5APyXpAcVJsi7wc0D+3oHvkWnuzB1M4
juRpNN8Jp9OW+AGiFXpHn08zKLZWWfl5p+Ln4HDzWKjgPM/FsXlJExd7t1Uc3+S6
6G1yXqjbZDnrJCP6gaIULhIQHX4KupUW5FMmNppktRvFkdRFMfHZQgWpHhHU8yro
T0eNa+5L3wLsc3ZzrU4WluYTw9FAGX/HG/0FfxozjrjukiXIBV++kNKloUndGuol
3KhpHmoQVuCLCMEEGT/V4T4Ox22yscf5FvO11I9wFmu4Wb547ZabasnTSNMFXX2G
mR09SQxJTTEy60WvZOrbEu5HtSb0tnIPzBPPcnEm/oKYU+XKuYGyNasze8vrKn9R
/nZc5sqs++wksl5NtFZutk2PyaH+l5lmhtEv1d/9kzBgntpzoFnofS1mlczJ6GTS
PwHBY5TAI/ILnIBB0Hp1pGLHMLud2WwWZd6ZNPwDNuh7BTMCLSt0LaARA3KntpSW
3U5+Eap6hl048+16yRLnVg==
=2+Av
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '210d6f55-dddb-46cc-ab24-5be12e178585',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+KJG9ndWRbDozu/bqMvs3dCQHa17F0guERR3Of7P7ZLE/
99KHRg4jG78n3SDf4nB78RKw5OtF0413n+jRsCuo1V9E6ss1ZrJehG+pBy7HOLpa
7cCYSKWWZe6OkoG8lJcuNDB8OFKnzv+jLmyTGpCuBcAHKM4Iz8l6ICCFwr5cc1z8
hRq0a+40hbpGNFm+XpmtkeHOJ0xFh2vZO9dzrPyhO0Adsk6+kXD0tpkxOCyTSlvq
iCNQp26niLCSarx7/RjyTNeaIlgBg+BIrGl+TOsjazPQI6CAJSOcmUM2qq6yirpP
S1X6S3c3QPNf7oChIXRGuSlyiU0jqk+HLVcpha9pFh5fLSLBwf/SLXjiao9149Vw
/QJBKx1P3mSDut3qUSX2xfeaGP8PXXT2Z1EGwB1cpwZ5fD9QmnT1e3r369rS2eWO
Ve5UVnoTX2BkEUmaJZIwROFE8h9bpO1uYsHjy/mghRchfxtQl06mjEBP6fWZ0Kfs
9lizJ7FK4j+vbD2dMXMdjoR0fC+iAQ8zC+7+cxRNM92q+9s1rOInq7+dcinxfuXX
L/bNjjcghMdWmDyuZ8XM66b+qmkcLa1KHmj8WbpCf16rULNpav3Z/xv8TO8SssfE
iwuYHgNoZAqIRz5VuOxdvOT6nTrVIEtQk2ZoYS7fxE6aUya/jgZSAmV1QsbWsXzS
PgEGLs+Jb3g4daoJIYA0S2ZPkiD/SIN+G8CvVI2ox5F6RzgD4IgNLZ0jiDOtsHVN
sify0isBZGTe1fJGjao5
=o4St
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2112d281-a9ca-4e00-af69-dd2ef77305f0',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAnJlsSUH2u0DpAWK8fZxJpXn9Y5cO7EUnZg8az5g14ErI
wizKChNV7IIwreyJa8tYvV0IBZ0Whvvg9q/ML4sqda26Mnma+d/wx17Y4tNHU4/A
rM6Uf08nq2c/VsuD6/vSVe8gPxES5EfesmVuNQmqg3j7cQdGLjnPnxopXGcS3oZB
jOQ6kV84YvU43uo+KXnv1tM2JyZJFxrCfukDYSvZ7sT3uGCgk6t4Wi17ty7FifN+
sdfamWgqLqYw9RStE8elHBr9BdmCYakctlVrYtWiM8l/UHl3WLOkyVCK5F/VE6jL
wQXTgNWs9LxNxQ2uIktm/zqSX3Mx566hdkRxp+hblt77OA65v36EMbMJOj39Ybmq
Ee89cMqV9TZSN1ZdPkcoOraBn8IRfs160oqhdg6NG4tEM3b6eTUJraB5p4oVK5Nz
jZIOh6iIb3ke0Iijsbl3dby+zh2pAzJADaa7MJLkqUalR5doLdXZT5b2/iOcitvt
siJSUguKsC74uXB1xSlU3NabxPA0tbnPqSp/bMMYHSSW03B6I4lhjaaZwPKLqzAl
YYwuqVJP2rN2movGAGcPcLf+XcQGhVGaA6j9DfkttxMirBlremT1x99z+Z20XSSF
neOIuMdgT/qIaqvkp745g8LrqdD/rA0b2LbQVG98ZCeDCB/p3j+WunUWSIy855TS
QAGbpQvovMlqFNXZKIbkqAlVWq5t6VwGkAvisYwnYJM6KM26tCIZ7NVfO1ceiBRa
spvFnxjpqz/axsK/BV+sGn0=
=TQJ1
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '21dd1331-1de2-4780-ab58-b26f4278113e',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//eDO1BvnLaGEUEXph6wcp24jD4LSLmmITUEGZJS061lZq
xjcN+Hu827T9+D8hOmQmZ33ysZgdCPg8t66+PNPGBiksque82ObOxCreBCipayMf
7D8lhc1LzJxZtkAe5S29ToV03AjUYNhAxaHTPu35Z0iaDvdkYbJO8ZffheXl/X6G
5hMo3SVFatP/jD24XCqSsCxtK/pA5sjoui5s7VRumlm9zjFTtgxWYF/yShgvWANI
qE7vMBs1Rk4O/9nHdBESl9AAZw4tukNXD/Wt50LoVeC5e8AnpQbDrdwcxMEOYw7r
8m9ajq86sPqQG9TbwaX/EdTWuCTcZ5ztgInmNmlJZyw+UH2NbVTJmNtNxMnYtuc2
4pwcwrUzabTnk1fR7wbxzWI4Y3jAS0fGh4P+nhjYFeK1DJuVqCMtujVqCRd0Gofo
tEFwGLLbIbdDs8/IcwWGU+mAmfW15g5P19gKfe99OBmgh1/jvR8VjvS2oD2zO4It
O9kF0JoUCw6cgek6iU8em9Bt1DuEEP3gho8kDYLIQv1tGdZGM7wmmjUvZl529TYw
QxlAMTMPjIiLGvQmPqIHMrgOu1CSE1BI5qWD5kTgWLSV0rR2JHitUPB1aHGFAdTb
0kZY1ZXUFPcufuROrXO2Dy8VQjvatAozdNBUzbp0E/2Oye7tFb18ZKWK79TTmqrS
QgFwh9T+Bt+CTapU4HKMzuAPrlm1dEu1UO9wGYzdhRurKX/p1I9Hsxnz0odwdqX7
qtNhut+S+1Qaxqxz97mPsmFuTQ==
=DEzA
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2553ba3d-3463-48de-a8a8-69e7a908cbc8',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+JIbeE9tqB+6qxnwdVtPzL99pF/NqjoO8uDXli3GKUFSt
V8QsZeQnJqBfeulB0n5KMGRJAsDl8k8ylTCaMIL2jeRYzM/J2HksbBsNWJ7bLXot
64a5P11Ze0RJf2okHlTW8CSkzrmmQORZfSnTvpBzIUeS+udDyiDaEbwHcWdgybeh
dubJw2qCl5+gWoB5cOulxzgpDzKhu5q69T6SjV7Aa4CxqfOZSLXyGhXT9H/UkWYT
8lIrkdC3HsBM1vt2XWfzQHSpyG64iWLi4S5wuWExh/9sIiffiwQ86Wd4hk/GC8Js
djq0DjtIDtnjN3j1OtqNkX4KdbYTQHk8Gxl9qPVBllnA5z5IlzwVFT87P1wfw7eq
TpniEi2Q4yPbkkknuM9anaateZlVN7IPgOQszXZij0amUEoiF+MVwob/hEUV4BHW
B51k30Jcqd9O8JldMGz4lrNY2hHHuk51dxT+1eHUHHfzuYRBpw4KehgxxRGgkr20
nIhoyAWTJyLenljNg6hT7Q8wU16jwZ/EuUOOZdHKPQNkIZzNStLpLCZ75R2bIQQe
MmkE/zsD9rfQ95p65NTXQS2hw6PAOTwwZUKOE8Du8dmoi275CdhgHf12b92QGXJE
uCw5M8xSvccR0dwXyjollw1+vj4NJCctK2GEJNAOOtgLmWOKVKSRI534nH/PZW7S
QQHM9wDQ2TkvPwcgv+HETJhFGMb1s95WgAjfT9f+NjPy9LyKoAxyG1OUBnNK21Vx
2AjMwizCVLqfA7bPAcDwmROi
=A7cm
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '27168678-c04d-4641-a208-608ba9239921',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAuXAbluRKCluIU/6uYyPFCwUSYsOcX1EOUJenhUKluZpW
X15yj7hlWzb1cfY5M6atfZQxMkeoHBwAxX1lpSIQpqVFifq9nLmbYU9WtfOQlbJK
6WeBMVhIdwl8l6KmsgNZngExe5JbOpCHdDcp2TdjJWc/8GGL4kSE2QOuKtb72au+
9rB6I7SlDATT6Km9Wc0HiyIU0LfOuCAqFEmclskAZM3foxeYnBYAujtTJZfMbs/Q
IEBXqI/sWmLGHYs08mwu3zhgQL1r5GQORsqAQFd+LCKcuE+wD53ZTaCnqKVnUWgR
AxGLxe1VkjrnYOwXpZNdJkreU/nItHGAhlKRB79aYNJBASA46BGMxfxULX/o8eGx
2iw2E//ms/5Y0VbycFYhzm+4kj0NocEDTnrUgEVJfwJ42l7AHu2z/XJJrJWpq+CR
/u8=
=r47r
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '284b31b1-8558-4ff3-ab3a-503d652d420a',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAgR+V9m+Wzl8sI06LNbdxW56qLVT89pZhiYeXSzJ4ygv6
2tngksS4sFTPblwx2CZUA03IGswoX9dthaWlDSWQHcIv0pFxhszDib6juI7g/IYd
CNjy+bXHcP1EstMn8RVdRTkzFWnStjbXjnCuRugx5HitN3AlKCb5vjEaX6fcfrpT
XaN1NXLg42hg9isYLGaNuhCfJS/9Xe/T/dlj+JXY/MiDB2OKiXy8ZXk7eRTTohDU
m51rKNoYjUDM27iN5jp2LdRficTzDsboCC4R1tHPtFXweckDcpMP0s+cWuTebPIk
VCHmVf3X7s73FilD0gXdAFEXHNCCzsGwckNZ3MUBCWpTwEeDkxq0rv22RRLCktS1
UhwDCuLwPF+2Hes2p/KgiSIS+nhg/7RJWs68PHM3UlGbbR19JXuvwz6wT2hq52hR
zmtbs9EvPKbizoMTHB1J31+iOu+v5YHBOVOojHaSBGp7PlDEU7qfEDiJ1ZzETiAk
W981VL06TEB2v0Tizz+MllKNSM126ZBw+97Maimm4sKhTsz8R1D4eQX5OEg9YEZW
VD2KHlN/7BAI7xUG1OWs6Cy7x+xdcbXVyl23Pqz5YI3L5uQqpSK7FXo57iI/1DWR
LP4YcHfwSsWCN0S13d79ZDB6KZ9kh8ptrySlY5zL0D1EGYCqFlXrPecwT35lP87S
RAGTPjVvK0TIVjxRibSI2ri1cTvXX9ch02nZgz+XdEyPSBmxIm2CcO7CyEbOnFA3
PDU+8ihSNlhiC1B24q1mczYE3Gzn
=0C3F
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '28e9092c-911b-4cb9-a2eb-825e94f9d49a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAub3aa5h9BWW//oJSAMQDoMY5FOZTL0w26DENc2tIKy5N
rpWD6Q+mbTYLD3LHJ9Wx3LWRuwTIZWnhEuK7wbTYmulgSvurbM/Ctr+UGhZneRTV
/mWXhFqDJKSxntf795bvQAB9j8l2vXaBviuPLIcFvSQ+SyNa2M0GDP+OzF2iV3xa
mxe3OL+1LXUSADxBmxP1TWUi8eFoKSkcHQSl2ibuzhl2i3Sj02Xur19IPvW0UVHL
uyVW1sfnuvc6Cde4QiFP99O4Xtt2s6diOZAR89xC4xZgY2wsV4p6jNw8Ll+ID7D4
oCdVuk2I4pQgOyLm0FFxlKkhAWNV2VadaITCzN5A2gL2e5tQ7xbwvjBupYQTzY5Z
r37fLoane8rRyRV8D+ZnBYX6qOynXi//rXf5ptk8WMGi7Vyn5GWVe6W61qtva4zy
JcwotsTouLuQbRDpMqU3jNo2X+iFXWkk5/r80l6mHddYztzjN90EY+6g26IlvJ7W
2dn9K05z390h5nxKTXSacIfQtz0URq70ecZ3MuPwA6w9eR1sf3C57Icq+iu0KtZl
buY5//Fo69D/OFUO6BJ3mjo9xa04tx4LXh+2FZDcD/M+rrCPWcQNL5h33HVLuf10
w2vdvcj+PcnoMrM1DAoDX0WcnSh3WB2reUteQSXWrMP6RJtPfu6tSB74e1/NdfHS
PgGaGGm8PWZRAcU5dBainOP7/mMnjBufcKurLe+LjpHiRVn7odcMeBX2U5SY8jj0
8LG4XUzfWEmBUxaN3WtT
=FT7u
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '29e90e0f-e707-4e0a-a0e7-d445323ecd1d',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAp2d9sXLgP1i7b/2yTCqiAmkyILd6gjzOPSw8+x9U1Iyv
Yku4gBaG3zGxXjYXpMTipiaAMZ+eZl6WUe04z7kz23/+dzeyGY6GA+okpecHbOpE
1vQ4Eyk+k0H4hBWOSSYnVmyyaXuOCuG2vls+AzGtT9JV2850K2eKRUR3VIE2Qe7H
KiIEBLvZWMEo1CrRzNuA0XaYd0hi48phtldoKamTUVd28FSURtDATDeiJ3fDa30m
HtCEXEV/jt0UIXrvRlyJNrWJN4Q97y4Sry6T1va1Ou43wtJn4v47pNWuooIdntLn
w2J+nxlY9XjCbeVJP+enB3xhkSWABuXk1Qs+uPXGGixTLlMzKU2Ej/fD3/3oFo80
iuqZdHad36ZR+C9LG/trkkbUzr3g7FUe6TT1LE6e0xAN6/2COjhUhsV8vH8j8qh6
8vcAt8QGURaC1iXIUcMZU/nsjsRgkuio440F9353sqlwKLDd5V1HvU8hkjHn7he0
j3cAYp3hGDul2UcsGX/z5PoDS2WZR7+HqWezS5rC06LEraPfCIUP4nP+SFxXD02/
ZtfP+GF7WXzHq4XE9NHmKdeK43UrX1No88Glr3FmMKOIJbamCJzZpr/gaYo8xLOo
8idrZNks4AdPzpuJhe6FBBnSvznHjUp3AZ6KoPTt1zskuIV3OxX31bDKMVhG6zXS
QgFkC8tHRMWaCrmEZj2a3hALLXSH8YNHV87dLOh68qkK3To+cGR/165NIOeLf86w
sY92xydnnOdZnjSxG77VrH36Pg==
=f5mH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2d89b329-d9ef-409a-a137-8063600e51c2',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//c775u2joRsyXOTNmK3o/b+Wh6ghgaviZ5mX2mMHy8+7/
Um/yUTy35rer1fVy7OAL6NXwsSynvtF+rOFtQZG/eDR9AqGb11uPTHBY79p1o7fi
nQEj1lzhhg4aJPN9X0Us0ZH5s30IMaAU1FziO5Z46dQMz/z7z522uoEXmI7hJUWz
e/b97KaP6dqc3mPrSuCz+BiCwytQeRsLM3iSFi8vz050/bdIUHArRcVFQQNY6EK7
jtYsbqN6LBuXVZNGsTkDxG5Sl16W60uehNOLez0j7mfVb5p4YgHChEo539Vmq+iC
vTsAagqykxXmywVRKcdVrntSPse61Ze06SZKtYklEad5cTbzcMt/7+H6F5QlkxZq
PPD1zKw4Myo9Z/tuOtL1ZHSlgsQV73tMILI/GvNrzc7QKWryE4+lWmlz4JXqyn5m
AQuJjdJuj0U7C8x3V/bfBFx9JbXveio9Z96CuBx37dq9x2AMgD3EW8i2bL/j2cfk
Aq6xcwbk18gQqmuedxf2dBS4JPTS+UftlWFdb9LHqW5y4/RaI5Vfb6Vh/xKq+pi4
x5cXXHVpK5Br1GS3DJ4qD759rceAh4ZgNQWwqzMerYGI73tzfEbdsTUClZrocOlD
tOtnpplADK2/8RG2G97+l6UEHSlMQXocYU4vaWTragkvycIJSdf1q4lCkMPRSffS
QwGU6L235rqp65T3lby1IZzvv0VG8/hry4DAw0BqldwmgkhvBM5sjhV1nWH4VHvx
0U1UJirK6AXHF98KaMxMzXWQ/jo=
=AvUm
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2ddbd40b-62b1-421b-aa7e-657e5604475c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//aoLf5Y5h0dq06BUcaW09caeo1Czh1zYBFP4IgTFWX7Px
NiH/XAcKirtZLLt98xCOfWHtiW1oKg5NXDhxhj41Ej9v7WwdfGnCYjEzAW3s8GmZ
OJOmDk9jTuups4WniOsuqw+WJS4nBEB7gtu389gF9/vve9QoVGVjFhzYJkpaApCj
crM6eZhpoa+sVHSezCWXxMMVoPA9HzMtKkyf/OIX7b2dxl2LfqK7c9y0h5Z1OvyX
wzcmHLoKFXNO5tgVBV8j+ziwJJa18RuNqfiU8O4HEy3Llj58ux9TZIY7LoZwTmb9
kIJnkpt6BMjwqa6QXy6eCu2vqUmG1mzhaFY/fVcyKK7wmAUxofqp6GP5EGXKoHny
KsvlHyRpPXZd1pGWrbAchVy0BvtQYrxBoTFjz19oX+RVMlNxLNSztiTgnRN1W/xP
wdbOCY3yYNno+gCaMVqKx4qsgF7XOPq9+w5JaQeh8+SII7XpTseDNLjbi3A2Q6yi
RvyJgSeDhXdehPTj2Wt4qJMIocTSDN50UtOdt+DjpiThUY7ZXyZc0004uM/FAkAI
mmiPZhVvXvhVFTjilStoixHAqqvNjKhECwKVHI3TWC1hjCjfSZbkk1N8f8SYj0we
rwE+Aor6SUMVzFgUCDmgrashfgVYcnp/Un/ULvBHKN83jkL4xGF79M6CZyLJavjS
PQHgOcl4aEL9RTONF0+aPXZ9+eNAM5fGbfKm3e4fi0ltJjoL6pEyJtL+xX0mZXiQ
+z0VToL2r7Rjzz47eC0=
=e6PQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '30151ad9-ad38-4318-af34-7fb0ab3be36f',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAnd1ENzsTWKPioI/i/3UGGqPEhjGUva6JYKnNp2471x/g
2xWwrjZgjyeU5B92H8yGAC8nZcMgiNznsj2s63YD8pmbVjxma8SyVTC/kNn5kfX1
fv/+tsuigpwFAYSryi5rTXZZgvDK1OnMbmuZEFqzqjN12syIHcSu3hsh+REJcf64
7s4Fq09Az32EjgJzRmfqt2ZZfFF24M5H+RULQm68gYtWmf7gUkf0RSdgAmbLpaC1
dJ50tYBPgN+IyS07Evr0/PuvXrAuccJVB7u0A6EEljee/YqbNppe8KMbqc70oQEa
cLE9z5rpb7PtS0+ZQVTDtFMit6xdIN4VgEg9wPJoCf9Jbf+2idQKaHlbEk+cnNeo
LjTZ4fjBb6T6z/ZrSRaU+ZgdElRy6T/MCGgPSZx2Oa2HV1vLmcb754lQLwRjOyZ+
1MyGmyqF+ws5U7BuEO8tNIKkCc7qoY9Wt8zTgP+eVWwyLjVccvyf95O3loH6wicw
T71FSi4BpN5w0sd7Pd2Kl2RTvdebAQYa2eKR3aet24yE9JukGLPoj5XgpPsq3nt2
9KzvQ+xEbfYdW8upm3zIBH8ji2XonwldBHPSjvnOzx4KCaNEBgbBtoVrPcwmr9O1
TAt6gRXwtYd53TUZ2Ynn3PvXKkYROoCmQYIg3Z9Xr+bg/1A2gSyU6hy4+GRS60vS
QwEOBJmPxHjiRGShPVuuKapm8g+8Gq4aZgzRl0S2u6vqzeJqMzsx4T5Ibf0yVsdM
Xq0Q96HZfvzaqo/F7W5h3KD21jY=
=1vZZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '30b383b5-ce14-4712-affc-0569057926af',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//YD4thN9zFon29KGOdjWcYqKSs7v3Tyh+DzvyxOZVMgQ+
FpTs7A/msd5moaV/MtlAW9ZmH6GUfjUhRhc/oUsQ5GB3KoD0DZjsEOZ4F3VvDUNj
Y8STaRc27kpyqXstrXY7JitR2nCBWoJbxKoCTnhngVU3sZBKxH0DfDGZb7TKSVl3
USJPsA+CTBBrKCOam2h8QkPFeI7KWUcJiJYPuqotVuMt8fNpjbloYwlb9c8WykA+
dKHpZxkTSr94NGVIwMewVeIjD/m0F7TFUZzi4RJbWOOMPIJkOnoTj+5WpiRpNTnB
PKDP0HyriyGxgWfi3viCZ6QKRz9PNY8XmYqKnug8BJDC2GuX4t1Rppl1JulRQNLB
Om+YgEccUQ54eRqggkFHqaXcNa3MlCionzGJUSTOwXFBVdJNfLUmOkMPetELLASK
CFMlD2EmND27k7Dm0aiL3aRHIMgtg6oL1RQ5HpzzrxHHdtgfrHbx0opbqrzDbqww
W13UoFW0FEIgzjv3juo3Gros+vEJ9S3QhhtX/jFQ4X1rWMzzoozzl+rsnoSCw0G8
6IavmSUFPfYjfhQyarux3bIWr8Pq3bQnSrZGbaU5HSEtgQGHXfDZ+nPzvP5hujsp
JX7RivIjeaxPKOqm/lwK9byk8dopTmQRadyWe2SCSqu37cKhONFn9cz0Iypb1OPS
QQHv/T8oZIVHJS+D2QRGacQM5vt9tLd9P370d2KUyY8aplihmygzX34xCXO53K5A
7XXxyPRutDI4yMFMd5hZCD2B
=jRwQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '31fd6daf-52fb-48a2-af47-81c5210534f9',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+OCTDPXUurBjMt0eZ2asT3AB2jhGbKrNOVJiFZItRRJOi
tgyV1cLWYgQs+PTvnw3b3tw+0/bZWn0VXPkNEJycO1L2QTLxOJF9HSgSXLhE/Hwj
ntgQaBYWOlEZcbaJH/DxOsnOEXWkeLCoZjmEi6AGAkhkOVTNYa3nLGB8+xhVdWvl
hwCqPiytvX6RTMtzQ3J3I5c5ymDcSnG3OzLAfFi7dDDQNcjMzaRDUS4/KB223MKZ
fIY9bNT5MED+VkI3wxp0H8Nx4usr4incEbNl9+MVsNkHiK/D0zRFOX7gCeS8tNty
JwUAV+2HDlzHaZ/g5Ld9ORYxStE+4BOOK4lbMS1QjIao0aJgyjr5j1ccMbVRJsgH
3BcXYAFzAT/NzEbX5H43Uqf20cbOzh4bNGtZudE3BNm0mustnKe/s+IX8cB80R4w
ZXsN3JRMpvQVuZRV4WdvAk1yTJM3/tqM60WIL+pAaHV/hsgUzPrxq3R8rfDlEzHU
QNxgn5tbJxMFDJjmFzPfWeTqEGf+nZozu9UThO0irCg1kZdWNJ9zSnQTEYR90ZTK
ttbxG3qPTYf0ot7lSFSd3dnM3Q0wAC15DEclqZdMCAd/VQy+rl3VbB+boNVVbWSO
V5KW3JMBQSi6bnpIsZ3dHDdZp4ydpXlgSyGTyynN3dILlCYCRruZIoOmIXfuvyXS
QQEHvpbVS6lYjwagJnpQkt/W84+T6wnVM+2nUiVf87QEkgi4O4zwliIWZyMTJ+OJ
5JillhQTHlRG7QUYM6ZHNzA/
=oFW7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '33e527a1-997a-496b-a02b-8e7d1634a215',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAnv6/bn4Id/4768euyWi7ICXvk9b20kOngp6H7bkYsqaw
9vj5k9XNnPL548pG0rRqOjdrMUuvxNolXpbQjt2VSPV0Ajj9VsAlYVf9F7kEj4e0
kDEKSyQn02MM6Ol3QgLlnvZZVc542aS/ruN5YP30sHusiZsAT7jyi9yoHtYk/0m3
5qy5jDfWbunOwZ1MRWMdVhAwsyqaCFdWh32xgLVuvg7LHkrq/j6+ua5HtxJ6g2zd
KbZhE4BYZm9aPIJXHgGY47yazos/wk391W3myjobVwbrjSEMIsVKemU19+ph5VDy
p6pgZDxG/3d3kk41MaA9XXrKtendkxK2zXcVlRHxotJAAYWu/wnXq9IwHNNT9YEa
8Y1MSJJorAsueI8ZqVaX6GY+LumiTHckBwJ+7HtfayyAyG44nkU3hHA+7K2KKbuX
WQ==
=m6bf
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '33fd2fbc-ee65-47cf-a933-ca0e5bc83444',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAArE2JHZlNVtiHBG7SBbdptr8PBQIjvev1qmASWRa6tRWp
t6RK35OtSD7kehU+ff9zTbqUxrgY01eofeftj3e9qTnR8PsIy0SMIK1T/poO5kXw
IY7uMBDur/4TdLF8aqgtrMsE8juOyIoFumlhtRz0emp+y4lWSVCxV+1SQZ9kmLkS
oJLG+nyLPoLr6oXMDIliz+cgDKZ/0LTphS2Wcm01+2a7T3MAQGpkOoS+hrPaDgZ2
P3JevX57Mj8iVkxK7AmBRL89XMjXDJs3Onowbo7k/rrgpg8iLPLtGjOULWdM6RGo
cMdwHMRxrN4g/pYy5D8SSwn2mebJOo2GwAC/2pd/4oy1BbPmc9UAVNmnHoOcgjRh
DrQZPgosvHMUYO/vWHueU1ivxHkzJFcvi6Oj0aUw+9WW7Hv3Vo2dFELctMjztz6w
tfa01OC6LGHRSumchXhuCtafe18g1QnTvLs8GeMTxsBXOYgbktRXB9o/mkKNgltV
NCRUjZBK/ssFdebc5IlKjzPX6T2c0gOt0ibnP82V8S18irAt+a6iGGS2dOURF8YG
Fhl9KrBnNQRrJGNFts7l3VnbT5Ae2sSstj0fNiHtwZ4UGGO54fW86AtaRvmNmPRw
WfwYebb9Df9CZsZfVVtpIrdJVLXYioMPXgEUiJZ59FW6stQBDkU53U2kc/sPGxvS
RAHH0B4gRWHjE6iWkncfwbauZLfoO90OxHEcbh5PZyWOvFzrQVEnn92vTIx6FxIU
M+oD7fKWflFum6wOt1LBD5W4CH6u
=Em6O
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '343732c1-b64e-4ef5-ab3b-38f98fbeebc7',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//dr7ptlFHhe623D2yIRT4/g/kfbJ3CMhA/ZIloK0m1zAC
5kCFBG/poVWYS4ZPyhMYVfTUCkuw2OY7NQbaJo5/Efm66qVu0Ygb5C5VuwT+5Bxl
mJEuWA/mNGOiPyMN3il9zw415EpO6HPXoPwSdgipet7XI72QDvwxOKN0o+D08TCw
G6ecR9ts9bZP8n58eGnLqb8Myl5v+2Dq2FU9cD0yj1xCRGoWAouzlCDNm4ZNERTv
ga5YbtVWmQxofF77fdv/EA7McSOMKBGr0PyBc4axxoYNcb6ADUVLKrgJh2NrDpBK
dPxISxU7pUWKihBK/9WNrmLlHoInDjN3MY3Hh+ysL8brpo5GFsip403JJ5KlpuTC
RM+RNeGTDFspPlQPXqQsX2LQWUhSm1tkenIMDsLm9r5XCCY/AcYokaSOpVDUQKhl
JzwWDVAZXLZ4uZfNsrgdzyuHLD3PNIjtYo/Pv8hnlv13XFTuySwTBXFZv5X7SEV3
mHW/MkWpP65sLpIKD19apEJl86zh7hC/2j/LwFSaQJJBetNtZIGtzApPb05UeFnL
DClJOST5JgF60LA9bwXeA418peXk/BJxyJqx5SuQNZUal/rDQWJRzSeNWqHBgmoa
zzaNuEUHImV4TYLqOQMF1zjMi3PU3+z2Dq+vU5N5KJQA2TeWR7oZ8dq5juVWIH/S
QwEeOv/sd2ccG98PQzdAlBtLK8CIdy6nOaZuHEqcam0UVpZJ4HYw5/P2SoZ4cNjL
JfkWqY9dvB9NVZGtQJFccGlwsTI=
=LrME
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '37e28389-5e90-428b-a9f3-fb2e2882dfa3',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAjXxSs1HBq5Tpiz3MmOtrN3rU57w06aJgY613kcplWt01
TmdbbSX0vLnIa0+E5W127DCWnblPhNyfVHA0P6x2oUhbW8oLGuqvDu8GfybOGY8Q
ERJX9+wmVKU/ni4bSIUeEiN1spx3WY0Y2R3t4WVpB8DCMTSOD4DUsl7M82opmL4y
Yx6S9IEIJq8de54cjnKJd8IJ7XT3pmd1thnSCEn/geQOxNKEoU1MkIeaorMzVThk
StfVOe+DmFJnBUEvtcMWoxoLys3SJJyllJMgCIHWkRpDp7weypnKooQT42y4I4HF
DXAOVvgUWk5ezsSW99W+uwEmW9baX3Td9ssUxSrVSkPBjCirXViLew9/M6JDBuZt
PLbn4lT8ZULXr18xERRKWwuoGI8S7/nnyUIC8PoGO1S5tO1v+V/3QZ5fbouiTnw4
rrWpOJFoSRkjq0emth/SeWopf28KYYVhuy5jxkE8EWtx8wmmlwhCib5e4TmrLaJ9
7dpFDUOEliRBgrHw2DElBQeXQxPFH72KtjDWuZxmfE+QBHG7b+k+jm1QksWf1fBJ
p/LeYpX5rgfr8nR572DpnPszm9bI2oGiE25p4ulq5UYUlRLd6Gq1zLxzHM89yEGA
iexs2jT+vw0BkQj8G6Dxz+flHdpEgtRVfwPV7m0uCjdR/GE14Skc4XM4vV+rvznS
QQHifmyhvklyVvJ/C5T9aB79d0m7ve6uGpLun0XzqsnW4x5ilB5rsuYgQwyrAyBF
emYvxxMWrkeg7ppVK4vU1I1M
=Dl9D
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '385a7241-5879-465f-ae42-bce3842dcc59',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/VEsD6at5u+9TYnEzsJ3deq9lYIILV+rVsfYM12rxPi3d
decNvb0/kVbFtpF1PHItsOJjOHGjMF3BzUm3uwQlf5anHEo8OgKBWC3U/aieicfX
/cDDV1gUUFiNVw5Wimld9jZQKCRGaUxRVnIxmqDLuTqu4XCMpE1uPIXYVmiF3FbW
sYaO46D3vaxmD/Goh/TW67eBaHc5AWaj4N1ERsdUOBGVXv77kpLfkiI4GptDWaal
Qm9Y8hRkFvHw+rs8AOhmsNssBesAwVwLj7sCNVIRqMx350otzluD+m0KsUyfCvqn
xu6Ik3iDRY8XRvIs0x288gIYKl1Lzi21UmKSmjUtG9I/Ae0GMhhCk9ENjppsbgpw
xbGuPXa6Wyn1wfh5UH2f7QFEi7w9IZiD7jiBB4jKqMuDKEYLxpuvZcKmUGZ0kRcB
=hM3j
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3e50486c-ae7c-4d82-aa74-48717168b41f',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAsGHT/cptmbvyH/UqfKzfcTy0cMYpRzs7+8JH75xkI53+
J+hhI0nZEuUea1ZIHpnb7MF4t+XYg/xgtcu8v9lN7IWusYOFy1U7ydQfOPMJZJ2m
P4O6wASxo2cZI8Iu4DqkLygmrvb6dNSdt9vSG51TndQXchsSSGe3rF5aCkOq6ZeS
Gr3rrWIWbet/9uqIon6gpylYF+yoMOpxmIKYnjbos46zwhf3J9YN5KtJlFyf0cGh
FdEWd+Q5cPv5zrTdDliF7hPcJwd7Ske1nHAbZ+cJEBhrkX+dkegwp6UxLMmDoEHb
HILBhuOJfpKKELBCg6sDegHTSphroElm35oMrsfrQSbS+2Myfwc0V96vq6LqgoWa
jF5NgFLVq1f0EuqRWK++HqfbUsLz01OW7iG1zyqpQ9b+yn9SixyHQTBwvX0NclLS
A1mTsNJUUTltd+NQAHrZVkECVLeURtrf0uvaBCT6XVoVpD+BfX8zeELofKLH6Y6S
uY2/gUJsFmgD3vTF+qn6TN+0a2e3Cu37oMZP1lUYFMJaRCObdKqdo/W1j8+/Y2Ti
JyeGBYvJ0nTpz6+Ym+8ewmweTl5RSotYxLqkYw9mJqE0DbuaB5BqIPABuuiY3eyG
xgm/dc7WH91gZDx+XRUIlvahPosxG/hQPWm8fsJIErashz0a6RxvUn8+XWsOtSbS
QgHQevNwbnJL0c/QkvXIx6NhIl5N89ehB3CM5htzfrn+G69IwHIAW1LtMdxse5Y+
sKb2BJrdPbLk0UWQ2st/XdAbGw==
=e3Nb
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4180abd9-accb-4338-af1b-dcb4485d886c',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/dcdUc+ydrIe0kGFnRkFFvMlb7iN+yzcAP3fYnG1UKlfb
xIl0cE4Vy3/2gCQD0H1jfHT3SosBEKWMn8MjWKMSBp0LcH8Hv989ppQ5JeYf+Gcd
1TpC+RP9ckQCBcH0m72xgGLHe9zdVP+tTZnNUKnC+Ze9p1f8WnQDwbSqjR24pg4p
iJq6I09fLLTJkWcEvg7Pr5ZAfrkdwSJuWJgrLALUFC8YZcLX4m5WGGH7bYq7pPAc
sAKb7jcDrx+kLmLhZHEW2Ipl7N6wX02+c+rAlVYpMO6erDEf9vI5eyguL0kJIR9V
A/iU1TnlPTOJrQLC4yAnbJy6AIUEXtUMxA4aoNi6VtJBASz1jvVPsyufWNPiGKqI
dSr/REroocQHNIQHWY1qqYxdx9N2hcWkNA/9/JyikJGN3Jt/PvPQtxncM8EhXfr4
ftk=
=R0NH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '437652ad-ab7e-45c1-a078-a43cad97200f',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//afYrX5rp8xOWWOPUqqKXGMkucCDlSVxxbbtzObdnSiVb
u6WJ7JjTHIb4OTSEzp95ARctWmDuPIpQNLwqX88k9gn60P4hd3btVkg5mR/2cf3t
LuohWnQRniwartErxb9TE8UuINqsDYlVBnHl/YuUYG3eLVbTHmjPQ/3hDpaoyLLL
M2Ej0Wog/az8IoQprbiEXIIo7Rr18CM9fhEigFzkle+wspSWoNdWRU9c4vStE7WM
IF4dnLRdQREBpe8XW0QAna2WA8vhRWHugO7f70Frx4oOQMd2B3SwT6Ii4qXw3+Rx
l4EbiKpmKLDZ/H36AEYk2HmLz3MzKG4K4zTZADd6OAYdWMEZ5AwbALakm+B16uSS
+567hkNmOV4ACi/0naFLZtYdQvDBM+T/U8JMU1JsN1YI0TY52CoC0oab690arv5R
zSyeG3FoJZ18BivOn0CvzA2wz+QPTSRoL5Xd/tJ5OTuxg5LWeYQ9r2Zv/6cbC0W4
YLLevHvWjikvLpXSD+YJUBQMtvzPhdRF8xAI0hcQEohFXqc0yTF7tlyKAFbJvr5+
2DLO4itSdsZe7LfbPNxAdwS3DWNeFgVGwXDyTk52SJcxzNb1sxYnnz1CNsA/hzLq
XcarQtQC0qKrVV52mAK3EoiLEwf2SmjeCxGF4acoA4iz7cJfT2I1epbJHmEptQnS
QAET3HoOijem5pXLDN1q9foUXD5etrsloWyLUThwbt043DaBXGZDSj2Us+3EqfKY
JyhoieF8r0Pnazo/CnSCGAU=
=b0+f
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '449a85cc-96b2-4f7f-a1c3-f0955ceb978f',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/8CFe+vOnIj+vBkdnlFRhRfuXtN8bPb20Z+LrNZr5SHPEr
C87XmcesaCA8kd6MuXpoqOzouTfk+rUedrKko2JJjcFV9HxZ1W5s+b0eM8j/ZL+4
VRVLr3jhMlEKoWzJpqL+N/CUSdb/PKpYEBJDbWG0yvFkXTUhTY1WWzHZ6MKAK34/
Iq0uTddrDVDQXlMpIZNG6S05xbOeJJ+gvNaVjA4oXB4/w+I3jiC6f3AnkQodSgmb
pG6j0Ek3nN1/22iC7s6D24TplVndATMrEVH+O7vvS4dn/IkV+ZVI38s9DcCPyBdC
TKCAKZmicu9XGGI835YkHR+gz+N5BHO7rt0sifnhew6D/Wjsv2UE8tOTuydi7gTC
uzuURIEXmkNOl/0s2gwLB1EGvjcAajkd74Zlkuk/Rh5z4ZGR18evuygP7sJj8SkN
l3yxONnrueVVQFZGh6zcjnVuzqtqMwCSDL5Iyt9RJCizCdR3lEPf/Bw51GqaAJfa
B3eixIbVGCzWuZvIqwPAcR19BUHZsgHgOCdlIuI7tHu9s1x/dr+EssPQ6JTJ0R9e
Iyc5WMK+L61IAxb+3aS64jE85tLAJmAnVd6I7Z4ivGaiUaaavotmNzH8ZR34BP37
Z50AynNgQ0BDMh0wLeJUychAkwziAXu/cH1Wvisy3ELsjU2okpwaF/Lm7HcUlN/S
QAEDRYspxGRkRmavFyrD0dV/52UG2Qdng2Bc5T6PtIIxGvaldtuafJSzpK0Vq+P+
qzHywqeUmMzLWql/qvjXMF0=
=YZ6m
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '46aabdf1-f6e1-45ff-a524-9af1575f703d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAiEc7RF10eYSbNV4Aa7bcibV4yLoYC1NpDmryreG3i3qO
Hwmzxl+mdMu69EPeVO3bIxJwooL3BBEg47RtR8pnM7oKbwE4nQwI+Im0Lfxe5JEn
oouGakBfPZqztDvSmTOK6W6OoPMq0Yqh9CAagTPdLJnNdheD6FIP6itigDsJm0Xf
umbiag7p9NhElDqlidl3C2BwxstJjzpOeaTCo9Zz3HuKcscCNctf5Wb5VAaK6R99
y/AzdUifyo5Unexnfe2jEI5c9YWqOkfXKibTQ+ERjLotYHpPK7SPQcsw62RXmYD4
Jf76HFCR4pOQDgKH7/q9G2MO18fw8qqTIgAIL+Byh3huNejyjLTPTO7buBzidrQK
n/VNAGbzty+LQBA2RHf5xGoh5TI1EnjvE+uSnmUYNwK/A31EusiPepRZswgO4Aod
7MiqnJdRCFvFRzOTc0KYPE61nZ5WZzN/MO1GbNHaMZm/uKgJQp/XX88lMnvgwjew
GCihoz4PXp3YF/b0igTue+PokZAjruUphZYpvzefzgoXkwbZHrTmZ7ocao788EwP
atYj9yZoJDHAfOzmqXSU68vidEjZ51EG8CunAEiuiibK4NfeGvPdg4eN29ImPixJ
guBpNbrMU9cuOf+ZLDEw5KPfTdwCN+3NkgvdwwmUymhJviNeSCCMKuhmraKNK3DS
QQEpS6/jazz0ECTs1VSZxgB9BkSl4/18zxDiycd6aorjrtflx9FYNF5e9m8mAJTj
65HnFSkxGgfsNI9zAsDR7uPu
=uXgG
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '49ff5819-4776-4914-ab0e-f8398ab99d81',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf9HA3aG/mICsRfrb8d6I6J0gV3fMC0dEm4awQxhhU+ONGM
jx5jOmaEWQqJ79KWRA4oqXxPeH2ILKvwnWf26tTfUxP4XMm3jbHQEsj5vmi/KpJI
lBxilUPyud1rhBGDsgnDT9U8AVH3g5+9W2vo35+j7JpgYxGI6/rF+hC3BB04Iy6M
ywBID7z15hCgE+uidoHLkln3vuDSjKmL7Kl0tnfhB6m/ZgrsDriLT/tY4b7VLPzW
XDI+/PgtL3vVaOMYFoL37uA4gOBStCcW61dH4h6UswFbXOYIftHUw8/Lhm6TfaJJ
U1u6mPNkQuHkMhW5Pdm3uy2ut+Hcs09LORqoyu5Tv9JCAYU7fxRin+vxFHwaRWtZ
Dg7vQgUl1tGqZdEXO2HGWzjnMBpILU7h6HhkpaBDAnPi6MJ28ebHY02bmm+Zxb9L
L0VS
=cbRO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4ae800ce-766f-4291-a6fd-4e0fa6b06cb8',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAlReAriM033xYGd2Xv+YCyZlZnWNwGr3UuIyk2Pwzeq9U
XQllwacs/NCx7yWwdNTc7uwk2PdD9YkXANNoUtX1twOcMdsmrbpSAj5po1vjFrdi
Q20p7D++6QQGh51HReCY699OcGDna9wPJ4eoS32QYjFTXBDM+h8GC6THH/UoslUS
U3Ej9526hhKIqKSD4hE5dW6IOomYiGbfzlAfQBkQ/irKizA0HJ9gzNXU/has/JXv
AbZh1z3LFXp1I2MbxSPZH89FXb34I/wT+LOVpWHmc/zNSY42P81VEYwClbv+yPPf
AS2qNGmLKHTcNMZMmn2DQ0aNCwwB33KHI70+mkh1caE/b/BU0jXBsWvZL9BXFFbQ
jqvgBbvdn53Yk69RMD9NU1t05PWHLWjSyVl2peBZzcgNz3CLUyHBjmMaPYb1lfF7
0s9rVikjb8B35VV9QLMlTqLTdA8ZhBF3M/nhl7Cle0bLuw/MAqxaanaZbWA8NdZD
blwusH/w3TU1LyNicqUjpl8siWV7DPfNZR5QPenQ5tocussj/kMQ7NSX/YTqL/ix
l3NUjhRgGVmwuN0vj/yDlox4WpR8RKM2RaFitTrg/ATTk6aT0JT0ju9fVPBZisQF
jH+rL0/zcgpoPw7ZgD2ty4qMM83o2aSaLNwxo7OpAodre7uPnTuOmzznxuX1SpnS
QAGaPRoF0ya0TUDDfP+Q5N0c1NlQS47b+Zi9ucdUplc7+jjpZYvuKrQH6KEUuLnM
RQ+tonWOagbj1pS2jJdHE2s=
=B1kw
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4d4d6b82-0609-40ff-ae91-fffc669ce56d',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/9Fn4rSnck0L0h5lWJtK0cGubvKJc3IJtHXMzQJ3Kj8RhM
JlCbirnJpGBtVJ1bkwqkMkFKX8Sr38dEBlspjF6JrOYgHFzUbJ8HOK4oLkd2p2Wm
L5Xt6+doHzfhX9B33nE2hE7qewnkx//CuiKOXmvI7Z5iK/KqXJvdFfikXHvfikkA
d5sHHZ1NkDJ26PysFdUuhctkJg4K+hNSMChk5lU/nZwGqoqIDZeuD3jNAgQZ3oBW
o5OK41s273oHvCt3dW3gLva4puALPFzLX6p8zILLYrdnMi00r6JHLT0/DwhtKXzx
cX1+Q9rJuOxYvmPhNxgTZhNhWO69g7SxEE6EbBxgIKSf1IyeW8X9N1YG8/UUzUG6
lIES0fRoQpupD4WG9wMUmJoBysnieGpgsyhp9Aad7YnXhLbNLB5A6RHkUQkvW9jw
FLh9szkEEbgclHH8j0r/ULUgkdx1RpGVlteGD+7s4eezTXWiZxsq8FNWfjI0Utae
z6dBDOcsMignxeoRPBZOKmbvfLCovflkQNHddZhd5N6OZm9WqKHSeFeM7+J5yHTN
j44avYyUQk4mczX/blBFhFmZ028+cT2u71nXzW2nU4VXHx4WiGkpmLuWlKIaXQO2
OvXXHM2hiiiwWv0l+9LrLQ1lPdKTTeAm8jVh7TORF3DcszOdYaVdC/15VJIj2F/S
PQG/VTaiNsTFmbrT9yUbiTuvvIWTNeXazmvUupRkDlww2buzXZwHmyPc8ff+xjfp
pcJ3/r7pkm1T6jZggz0=
=g3Uz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50239d92-b694-412c-a4f8-71e2a63a5af7',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//VmwG4Cqw0092DqRHqT0r0HkKaSYM2ih29hcFzk63vBEN
GL4ybbIx97oEdUxqQjfBUV9WOzftzz+JYetn1y7O/eR1I6gmG8nH+eRSuFDBAvkH
obnTJXTrhJkEZTzgM9Y7dtVHXaoUsR2RQOoxNbOBltlTfOlgT8oxSZk5/4Hwg88f
/jMfwBuc9FN93SoCOuCiRtgbWCUUGJN3y4R/B/U/s8Vtqs7ouBqxzXaHLOyuqCNN
2HDdrjnc4z36n9JV+Z2cLEkbZXYkBskGFIkSu+5sDYVHpcxaS5JflnqG/71jn5is
rP60xtbR7dHByks7Id9Eh3dfCyL+yJpLjoIKzd0HLGtHrM6qAB8xl61yhthHMyft
Dv0fpsKsx6cUgjgzB6CVRUzcsJH32iDA5pxvk2StDJsSCmdtP4jdeMmTezQCxE/G
Gfyjo6HATf85COJAgrb6n2bdUcdXQuk0sortuMTVTMtowlVuefJ9Gb4Xf9ESo2hd
SVEEFhUjCO1z8ONK3wH78dbrOE+vrVqYQwtKF4QxuwznLdGZiQ3/tQsx+k/tMJta
19AqRoMhLHGrG03dPcw5IyT+um4TaANq/zI6GmKkumCKaqZDQOne5HyJ165yoWMP
l0EQ+mX0gq6dl2VrncSoyDo/1XXnaacQaDBZBv7N6EboezUVIH3C1+/6ZErjCk3S
QgHa7FfBaJ8YATSb79GSQiqnBixlyxwdQtBJUbVgXrt64rnokxuA5RA7Msa3EOx2
OiC0ElKEJIHA/OOHKj+vwnOMRg==
=MwZ8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50f484c0-93c1-4d23-a87d-7c34e4c3d966',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9FwSI0lVCBoJ+DkUoTrvBDQ6aUJpqESWqcuWpR3Mb93p4
arxHaDh57p2urLxFvz1FVVU2r/paZdEpowciAbtodLNopLCktkOPT7z0gZhE6CFu
VRquIYAkpjCMqs5rfBXl2XgaSdNU2TXO+4cvV2BnrVtlyT1xigKaXrDYN1CvB20c
BAT9jIQ99Be53NNrDJXqD6D0Vfyo1PbR2TVtp+nVD//ETkLkCQaUmwHabUGY9GWW
PcPOFudPkR5bQ2AzUIXnofCYqtLGrW4u+aN3j3cM30NHz3qcaR+BR9gGQA4XPfle
xF5Xy4UFpSKo4h4jGf1znyJbToI71ro5ze8WPiGBUR9k4mEj4FyZHngXtwJcZh+L
wvkk8gnSKTUod3oUCy9Ky49LG5j7yyMk5QSgJVZ/L7fcbxEaNSmLndLPkjTAt7Oa
/Pt21qgwrwcGNhEZOBv8Zwi1CRjocK8HqzLbf77tO/5zVEPqVCWsybh0kTLSURCv
5oHtSV+b4rZPPhSWvlbY9GcATWK1pItQDAbejbVQc+9Q7SPKCvMUaui5y3WAET7X
eGL6g2wuz0G/BTsm/4sSHJm9/jWUZpmJ66RyWAl0hD36ad67z0BLJcSyk1sAG4TW
+j5oFHW4LQTL3cs2ndv900cF9n1UBCh+3ME50DdPlT3FzZweuYmklR+FDjCFm7TS
QQGobpT8VYmrxPk6lC0tZoyeluvx6Hg4c6ivqsbxfR7Saqq/QGL6dFTI/WXltdIV
rEniSsXyUTe072CT0ffzF+pl
=bmOi
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '52820531-b468-42b7-a846-c8d0238b0a78',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAhBzTSOxJdfbGFXzoZvh0Fu2Bthp7TC3j3sXBdWV5VdqL
1paxNwvM84pUAwZsIm79/wf6a1Zw2iUwHt9wJiKPiC/Cq9lY3ztYRsaSeo+1T+BH
0Ypngs128AJ/fiKXJf/xF0Lzj87pNnHA6PGf3NxyDvagEY8dsr9iqIKuu1N7sKPQ
vRT9bBlSWcrwiRdeBVjA91Cg2gUM6F9yL2sJ13NOkJLTuCIQzv327KHdaizkPme3
Lu5Sg/k75TXIivI6ecEl6zmQqoc84zuHhgSLb9WRClJcwF6LMaqyWkcxw0OqnwvT
3wcb0OXmOrBE5RwD8xQGt5pzxPEu0XPUVOaKordHQdrlhI7Sd3VCCIOO8SU2CoqJ
ACXoNU+8DVerGGb/f7bkYo3TGWwkmIDgIiFbva1LBGnFtyjjfSzVtHI31dwLU5dK
sr2YqieqafhBg60oPhDO5OrjIMxsgykzOzk9kFrlWlSqkMwBrn4UmvdIOiMXKR+F
/QYcZK4s4R69KxOdBAtvsuoDUiGlzuYnXmtxf+/ZRq0X8GoEgHRvU15Hn6UgYaZX
7/pWg3LOEq/oSVbahaq+RmIRkhYkL99n5Rni18sDnsDTsnYuCQqkGI2PX/uZKBsa
2ts1C+uUYllDevMUlh4wSaGuX9ijq5AN8gEsi+3oEHC47iiNuy2W+b6VGZ5+FTbS
PQHVAL7Mdv4g4K6pYYfCKV2k5ABPYI2VpLStEqw5cho2ex0/wd1956wUi+yxm2vS
blXZxgaqypAhSj6W5t8=
=PSL/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53756a92-50c7-4c28-a65d-a2288bb05754',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9E9tRNbXUHZP/Vb1Dsr11qhDtrkgUNkgGz2WzjZ42YRqa
P1JOb4BO3Vmh3PII1CoRc0e9OuZzgoMhBIC2EagagNZgv3jb+1PeaSWrBi6K3vo4
6dpg31ifeXCqWJrWwpz8j+Y6YJbqBk7eC5AOZCASX+4UFDl/j4T2Q0SflPHY0Tqm
+7J4Ik6tvj6fyExQ1rS1q6JAlcr3kVLRn90p1ubnCTMRE/8fn10Cgeiu5hHB2u1S
MbpLx4LJsMRJVwnClKWS4J/SwIAAIgU4tKA2V8DyrbdBT+3xwBLRvctja3IQkOFk
dV6Uyjw6s9MsHBe+Jgim/6o4h2Auyf2rA/nO5yoXbjLCNR0aVl4cXvt1UdrwsArC
FuOz4cYSbeuP/hZlH4SgSd/FcfyV5FRKacf+nhAACtOZXDulCsK+yjKFnMez4Xw/
cagTPH7EwJRhOgT+WBYzeN4zkQwn4bvvlDt9Jlt1PDyit51P3KuyDvLc4Jx0Tx4h
SDAB9MpKig11oPQimet9yB+r9fiDCy+SRww3R5thgrouH8GCOSp/0GnCJDVBY7i/
qkLo/445q5r0tlVH1vGsVi/bshWq5TeQ1S8GJQGfAFAMuMHlFm/0iGoptIVnWHSw
UJA050y+JP/wZYFS7krdcE50i8Wj4ibsSxK0EY5CuitnTVA2EhMvAr5C/FY5J3jS
UgHTNL/S7At+40RKd99r1P0AqKoNeqCNVjDwCtRQRnHQcgemML6qN6/H1EtfmA5i
GdSBNaWyWqWdX80tMpjBnTR4h6NttsNz+MA/aJskavvlq/0=
=NGQQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53827f12-04dd-4d5f-a31c-2ac62a70f2bc',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAh2+vnFGi10CMNOMfRlZulXPXhP/3icKy4BwtUbNPB4hY
+TRrzEGKaK+1oMSRPsUFVc/79uX8bDGJc/RypnIc2BHpNFIdVVXP7Pl2gdftShAD
rH4jp51pUrS+sAXy+eYCj7BfBMNgR7f9HY+9A0l8N7rQwwJN/7pm0B1quMwyAGgn
qm7FQMfN3Xrp6m8CszAd+sZA7Cgo6bjmDNreoWnip9E/aHapHBb0Ed3WGeECstjX
v4CFF0LNPDq9cizFM489n83WhNtDDVrwsjtSsceRwMpaF2kXXpdgkVXbsLkLU7SI
t0uRu2k7OVIoXLzvZd+bQyt73V/YwIh4t98CF25+3dKSGkVteHUz8ZdTI8Lvw7nP
oYg8hI31TXAtVTDWoyfog03Ru2zdXSY+hHFO+nsBGaGWwOdKwfZffekiC1raIQfV
E2aXIlk/v3H8hFKTOfCVfA/jk8vU6xFDRGLn+RKHLMTxz/g8cePszR+TxygE3BhE
KAb8mAexDidXhWZ+xs6TJeQaTk7XlUYaOh9HVxbeaXcpXbtJvjc8bclvj92r107l
q5sJ51L2HxuHGBXh/t80Q2zltTdi+NB7/hyYowPsr5WUlrRrBoZ/FnsVHFFPpK2c
YVGDiWZo97QZB6XBF+j1t7X6W1K5/SWWzL8kWKBVfRvQyiAnziPO/oVimijX+xjS
SQEx2QiRF7FRhMH8km+mhJLkLo3zVHABB0qWUQelELKTOsZuonwzX8Tz4+v3SCij
pXqCBBUF6SDUEKj8081JkTqSfN/OmMUg+No=
=DAHh
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '54e8aa7d-24e7-470f-a819-fd960c40e1f2',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+MGJ4HZ37iGI2kB9F0+BGf2+IL/vcNlxNxf+9mo5Lxevv
aNobuu0cTky5O5eKFJjvFfIk3ZZiEBcj0fyzg6aysW/eImmQg6mGN3bvKpKveJYE
CrpmOPdWfIDgLOTTnkK/8Jp7V3LT2Ax2VNGXqCIh5MHTV71cbne6GVmmVNbx/L7i
zSc7iG7s/1MUF3vMP2DkjMnom0FSk8IYih9XgG/RZdoWNtoTOMW/yLt1cFp0yw1W
elXWAvnEOmaVEWoqjhA7Vuj//7ey5DwHjKlt1phOSjqLAXgV/MIC7iD5+BQGyKYV
ZaFYk3D8L5gr29pp3+0R3c9e/tyErreNuEwFve0R1wLVcWqOnfYECTRPR38BHpFK
UzYkFlZykAy7xCKgF4PLq2g58TXiGJSB02bIwdIXIUXjw0SssC35gs98qBzWTnMM
B+OXlzxopY2seMKd40eue6vNXHLGi6IkqK2Vyp5mKCf9Caz7Bdoj5t2LLMmiYpMo
mNtq0ljHbvjGAsoenwFTydLV6yCWKYVN1lFzp0SCuexnKKNIbaNiPitHxCNIlGlq
IpteYZ6KIj39Yw0uqrQon617PCBWDwSPsy5IKUZFctHn0R866+5/6cEGa44cTcHX
ufJvISPwLDznJcPdmRfQ3d0aBelnalXLANCHfQDi0FZ6H+FmkMu7APf6MFPSf3bS
PgG0XAYUwZ/EmdhpwLPKFv5J9Qcbp3eIe+nYfvF0oqhhFg2cxG/0p77wuDAWFnwg
OFaJvZGmweIwPI8ppyhZ
=z4yJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '56567c53-eea1-4a26-aae3-8a4a36d7a585',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+Lg+ub2kPKZBJ5XGYmCUTXlWLy2W/p6IzCR7tErg11ibO
Z8f0B3DLzaSQ9TIx7kZI7qKItDvCYeoBKKiswKFn8Yhyh26GHZhf6N5GL1iUUuvv
RyAkTHzaVopSWU2rci+wj+gmKDA9fFNxCKW6ezMjTRpKzHhFcC4a1+Qr2etQaLGc
twgFTymfqBrL+3fRgXUAGAJldbWFbi51dI2viQnvPdvUHSrD17WeRy38jC7p8mLU
7XWOC3Kfvzm4modRzC3TPPXn6HbgGDAMctJTLvLFo8nfwHHnH34muT1FMwW833M/
an7XRyIOLue3o9ht3/cA4jVednq1NkNHYqO0tO8i4R831fMufL02vmYCQsqW8Qh9
wrWGcpOV+UiScJHUoQbjJ0GtWLxJMnYXotFiHI6+TmAyRYXTkS1zz05zHjKt7hXv
NDcDmap9SI66D93yxjMolreoRbhxX+jI3Ey3LMxXZwg8yF3RgqZTX9nohgNaTact
Dpcmdx2AXw6H0RuA4yxN3SuAaj90umxFvKU7vxZqOCDvB7CL5ITbDpXLPCGtO7oy
XXbRBPkqQxOtEualtvtD6/bPRP+jVsnMBqfi2gGv2AVBL0I7mB/GFyfqZ2vHVGZx
vMYf5Z/NXCX4M9tuxR1FPQMhJhfyRIkand4p/smStAWlCm2UzudeqzW0qZJaMqPS
QwGe9nnxKHpD5C0OB0gPcAkN+HTIDCDSIN+/1dWusJzStVx0AMElw9ghoeD/k0eY
U8BK6fSr4zu/z8JhYpzc2SvFJ+o=
=4w6J
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '586a90ab-6b63-4176-a190-00324758fa2d',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RPL2NgDlhWz/Jl+9ld9fbHhyRM0opvwTDaV+tl97J0jn
ZKnZkd09B+lu99THMN4LmX0BsiN6lr7X0ltMtr1cptoEPW4UUCq6L1n+NT2goVbu
z5vDWrcoNC8+sPQE9O5tAE5bZtg0jsrgWQYKwqKXqcjgSK6Kgi13J5NhAKLXRmss
lNdyDCnCGGvxAqMSiyPGVaCNlqEQmN4ZFvHkp/8O/YZ8o7FB/z0VtAKEA89DB/R4
Xu9vB+r7Mf4+RSTDxEXmOGNQoXNX+ji59x5GERbSwbY51t+uLHds0jkyMdEowA4r
/aaw1rLlkgrhDmLylQY9dIKi1aYI0ch9MMnbDqyIpvaFB62193cOr3kdolRNvPmf
+lgsFwj89qQnXhjuKmjCV9DaFzQMRS4oZrCHR8DASpF1y/weDGjor7CzG6xZoEFU
eqC5Ar79W1mgzosyveLNEXaC8QJrlkPfpVRxyKCFuGeaLi7Emu1e73GM8A0y6MB7
PnhvBj41f9dp5ri64CPBqH+fSpuCo+HxrpOo+c8Vh1MGq1WP/XKuttCmq61ZndQH
iydle8+aNmmYXCRIsvrhZ1kOweXzlnN/hUnJ3Vowe7ZPbqeCGcyUR2XeS9Kvgyix
R8cs35Au/nsBLLieZEEt9ozvZMMRpn7RD36S5tNalvYJHXMrrhAiujZ3UqFQUV/S
QQGGk9P0g7XE8cIte0m2zAH6GDOwcBfmhzuSJIOei0GZoJXDsn6k24zJF456gNhX
Z3m7Q+1H+UUmap6tKD2dN6hB
=s34D
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '59a7a1ae-5d76-47f1-a7c9-cc9681605db9',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAmDnGrSF0AaZQ0yYLimflpNDFYKljVJ68C9PYStsLHB7P
T5Z1neW7shXD3md9H5B9PFLhkil1f4HuC9nGx4zaUMQLAqrYz6QJM6U6/+jObGNU
CaAfydwymwPNVsUq9ZAZWIlVoKLcmuiu6nhyu0U3P3+ml3layOGnhWBsBsS012Vl
jMy465sOllNtaG1oePS7GhEfDlVPBXBs2AQJG959BoO59W9zrLfpGW2uWHZaZYOb
ane2mrV5ZRRv+fvRieGeugh1Q03RDD42PqI3HQBF7OR1xh/v724JR2KSixQuelux
mE/dCBp87Y59P1h8xNuFncaOOORPeBHLtTEVxYANowWPytab8CllGHWBkyuRT36U
7Vs8U38YPkaRMkhniwML5jyuYjkITla8Z1Ee4c4Gr/Zri7OSiv7ADDdRMawq2AVb
TEYiUr4iSK6zumbNu7fk45F4Z0L77F1nfcLxz7xRGPeU2oxszHWs7Z96ai2ALnTu
3qni2119Y/+6WsZwjv74doW32Y7OsJrOLfmEDR62KApO3QumY5jLPkZMIGJKDL2K
yYON1F8grKHlAuTNvkjThLmRmOG2yzNX6H1Z2Du6v77MlVVo1yPy3E6xsUxmPdrE
0VCq6P7ZGFQ4KzkRONTweLl+GC+SDMGyrtPI7zLmp7nPNdbYgT5XGN0VgYfcGODS
PgFSl0hj683lhm1agKGowB4xSQN3Xt87BYX+wanAn09vi3ifnZsu2FNzm9bYEvHP
meDppF8q0+BIme4n5gVc
=qXcv
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5cd86d1a-a686-467f-a403-d169a82d7efe',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//flWXso1zy4q11XFbiYWt2drteaOerpvLECGn1Dct/aht
GVxVG2SqOesUckRI8eoBWFRU5+hpDIPzowCJpehOIqaSPPFjDh3bo+IIPAUKBli+
1EotDKSxZJJCthzUTGJIMc9/yo8pts4SU2AQbxKoDx0M/tM9ETNDioGQV1kudOZu
4Urd5FtoOaOgx8DXFat98Ssp2ied3WuEWRgMlH8IypwFltrm7WSy3v8z7P30XRDc
afla6uFjK0+JwJ3HMlf401Gmj5gfGqw6YpDmlRxnIF/EMY83XG4gVgSBl97M4QlK
cYYLVZqB8bfjFsgJYghNm7FN69/3GIjd0Us8FyiBiUBnK0WOEkhA/6eXUqmbCqbP
E0bqMP8ad0INpKSTbD2u8gtC97cL/eniExW6YF7Zjm+uZtm6EcV4kHlZuDyJM3AS
MJS98ER38IYLTe28ClibFmBua9rTKlcI84p63RriPIcA7S4BCc0b7UWuAOL+X8Pt
NxZMFBod9TJCFGhSWgdPUPsNmvkhjXfGof2lovod8zd10WMF0zZLbTMEuSmzK7QM
M6IajU587y3AsnVa5n5la2bPv+9t1XtmHSWbqev5BsRXHxtY8AhVJI7d2NMoXy+4
xYHnQhPp3tYy3hRo6rUWARiwfOPqu5153zOgE6YCp7qvFfvO7z2t+RPP88ckhyHS
QAHs/MS0fQjPflXhykMkpx4rWepYOJz6V0FSJjTTGpor/1hFLV+b4DN1Xy+ONPi5
Ffd7VNkZgxq6S9qvM0tIXnk=
=y8cH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5dcc2059-9f97-4543-a48d-9a1448c8369a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAtAkgJykVCzAt9n6ZEJQWdAqnU5NrN5ntnb3u5KR1Znzn
Shn9sWSpC87Y96VdFOV63cTJFnK+2vnmAJ68oPNay81/0pArPgt/dQcnN3A4+vnL
rOV8uypwZ7FaE88g9vdW1Jprcog8CSRph3CRRXJOPO50wzkbznOGE6ePOBIc1S/4
WSixgFRmcVmDRSmr2Gi35oX6N0zIsCDzZZ6qVf6R/gjzO8X5t1bKUB40AmINzt1h
2xXupHHwdBjnD8lrOVYx/fVuJmYpovfiDonN/Wg3SWFP6rhXC0NRmsIZ3xuI4uHt
SeP0Q9v8wi89+vPa1uhZ9ckPVH2v9KMGyPMBHF8rI7PzZiVfKZj9V4GezekJIzMv
Hvd26xROPxqTr7rpApC13rWA3SL7hvzIaKRZ9QwEC2Cw3zriROalzgURTeTnqM3G
0OiDIfr8VspZSSuEKBsNBo+AAiMtr4sRlWQrIggt4nO2LaefRxLw6yA6kAsV6wB6
9V4Mv29DAPGBGJH7dkdaJ0O0pgX9jtklVRRRvsafmnsC11Fe8DevA8G8RrND5Q5d
C7P75V1Rh0WvS21fpg/ItBs+RcAyUqXhMpXeV4dL1dBFXKFMzGLNVDEnLHes6dXB
+gb9r6fGpZD4IIhtBob37bwSVhb5W3gYc+zDEOiD7DjxGFHdZl6jowzB6HYOvjnS
UgFgT/RXsAgB2biO0ZnAdlRHWe/FsLCEjvRbtNYkbggYEbObd6FnQzylkgmiFHcd
xAW7dBE8Sw/haX3Er8ixmkSTwXLNkwCnQcfGi22pHgPVR1s=
=8lQo
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5f9b009b-35b2-4f37-a031-dec1d96f1f83',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Tsyd3p2dryCDEZOUPJ51kw/ymUdoh7bvS9/SsQfrr3rY
SJq7NPK6F53VwTDjHXVYc7gWuFC84rzkTrGG7w1XwoAva0itCAfooT42v54UCHT2
tcbEB6BDh5cp8HIwBlJ78Pm/vkzOfRCGC8FJYLnbA6IoY/XlQSNNLqRoI9JOCxs0
s1vsgAA40ehOSP3xVMz16ZbBgP1q/wLkSxrgddSuEEOjIshHHNqZcgmCjuZfjSCb
T7QAPwJPOc6GSyyzmlnCUEjar1tBLzB/mXZkHleLqegP5en+aDXrROIw5ppZvyqv
J70FobnSO6kEGJC8qhwF00tQfiOco1U9okmMpA9d6WP2Cf6hsOWrP3trziZmdZhn
2hnQyBHt6wYve0eUh/Hd5G/4AMCoxVJw8JHvOgrrjLKsSZfez2mh1jZHIx+7hSB4
1zETNqcOijxzFSOrZ/onzMuN71uvfGHi/abwqhe0DWitSDpso15gr0qVPyUX3Hfr
Wt1uQDqNwULrCiahMtmzlpqatpYBvH74uUGOe0MdbVr7XDHNuIrmIl9eUAGabAlP
4Lqs14JgUUUZc06SB+7HChL7bi1LIcqktdyiR7pgVTDT+AU9qz7bLFGFkZ1MLjZ/
ayTUEo+3y7HbQpKSt+68F8oHN6moQbAdajo61djnW3vk85VxXjpyBz4BGCJ1J4rS
TQFNQvQJ7/xbHLYBw2+Q94twmz7Xep+tl5ERk8G01ISAyvijk7MBgJ6FtOPNpOhz
SzJmBS+VjACH6QOWwXSCUiWUtGcPoJtzZ2J95IGY
=2fne
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:55',
			'modified' => '2017-02-21 18:52:55',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5fdcd2dd-5e93-4301-af98-9aa4a0dfe969',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAk+taIkQBaghGOIfVbft/pjUdTdpaxkeYfoAYWaQs6rYj
f8GhDV7pZqsnNbTMJKuTBft0KSpsbvi3Czd7eo1D19tAhUvfoOSDco1J2+18dxBI
i+//O75d/LjRsjb9Ei5GVRtwh7IDs6E775ED9M33AoV5A4g2aL7GP086ul9wEZeJ
vkngbYwYRbeuRwBmhfVTHogE2XAgIBV1kB1arClNtlrFEGuO+byi+P3CzaGZH/nh
U7Ck8m+e5lCTuvRJuukMVcV1f9J1vFFDRgYT7lGXqqYkYh+/0La/tRaX6dichj8z
/qu08IwCRxnDDiUO8EXdD0M2GxhVpU8FApM9ATcRkcTL3mLS9yJZDKFHVHOWSXlQ
mIRbpbNZA2CAAV+voXha0vPqaOJQV1fSDgH/rS3JF1P7gdT+cJGcPRGboIjUQikQ
SCclgd1aAB7aZuYT+oxjaUaibjai1dWbqbZ/ktxn1sKh+0DnDHP4U5+6dz/bA51W
gOWBNnUzDnVOuvyV3ivV4QTX/bYr2ToetLoua4wpxx9MEDAjAf0jE994xsAoaaG7
WiRdfS4fe1vhkNwbFyFwWKreJ2zS4sS2JoolUCB7iCQ7Ld3piawefSomr/MfhaGa
DSzRMehqqoNRGJpzk8vHNA61f3e4iP3Rr7QVnBkban9Iyos2/hYmrRY+uDv0a3HS
PwGGeWSNKEUEQcdSyza0fj470yqIGjx/VrWGMkBNRyBsryoV/wTxtc+4olWdV8yl
5vQQv9f1STKwFEl74cLJ+w==
=DLja
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6023d98a-d2ff-4e47-aaa0-7cae1559c198',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//a4FBzAP+4BHCWCbVZiu4K0nqLFFQzWxP4eMntA4nCagW
wJOcvHWxJBrXzgq8OPwKU4PrUDkEXDRfHCBqGcOTwAe1yYjE2Xl/XnyqNp+GNLIA
3ls+pfQxhU/+Q5oXa0wg2DnsImQtmigUoXdRRyCdGRYiIVENT/1Utm6Yl1Z63LNk
YIhZcL7JI67KPxgkqd+V6XStXyL9fn1JtsTCX6iagIsmnkJgcPoWeKQ1d9HyI6uE
HKQaBu4KVf2mfdoBu4nlVmmDs9BfwOGi6H36Gmu//PNN6jRDRzkLtDEKqCqdmePb
YYhA9/SJetcKp3B/EX2WEjOMqSXkWGmQYdPvrMRDTaww114hrbTexu45zgOj18Tn
mR6UxNs747GM5rFOjNSL1wlCkyU1Zo/u3np4Hf0V5i3wOIsEvYeX2q8mvodYcdut
R5qNRu9tRTKj686pTPPCedlImSssco3faWhV5j128X8dUapPIPegO9c2/u2FY+KM
Yg9y0jRITmESuhF2whXqXJoFCSofthnobDCgCvuUMXAKkzGOuYPvaa5Tu6uoV/CS
jJFDfu+Joala+DiD6vNRZO5LRh2fZMdx2TN/HVrTwIflZbrDQgp6mQqigvfRKMqz
pkqT1k6NJOlHybKGMTC9by2RC5pbM070UhLCP+aziorVJaoIGr9IXVx9q65gyvvS
QQGGjRDKdFDSTU8Kjmdau3OaCSmzj6xyiZ1L4Ef9R1awHE6gOhk7C1XfD8A2WD4b
WKOf3pAW5OvN1JxOoH3qgiYO
=WgGg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6106f4f7-1ad3-4b5e-a52c-5a5f916865d7',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9FrIvfflSmlUMJDktHbp8SbLeHAnWBLHIkys3egPzilmY
o+xmmwech1qenJryKc8RVorI5l2uBRNNTCHNtj2lIXP7EcS5vJjKZTum52tukrtt
zYF+bcmY/6tPfHPhVDRXaj4GHrihzkPcAzekAlhbN8G8cayQGcPbPnC1M3ixjL+Y
3cJpc7Hz6SiRNGUUKEASfXxiHvOvP4Z93Fu+qtp2IiFmnGq3U81gocLsaul99vEH
wydYErsEzEua9EUU/H0eIIEVDFePdqQ2VoyX1qFqwgMZzOdivHdE0DFyl5ZPxYcm
J+M+7CDhy/V6PdQiZ0LIe8zFff8Y1QdpvFCZHq1V4ukjMsjR5wN9TK65F18hc1LR
tDL/yZjQqBzJcBe1do2yK0hn8YhPaCbBKXOzsjynTrLIEoaoQh8H+lKVxLbd7HPd
AfgUJTi9cTQFQbjLZif0ti5qrT5Fb72cj7wM4P0Gb+Jz4LVXW3WHhgCVzDmUFcHq
4Ozs2bWt3k+9A79qRujS8+Rkcg2jf7d4T2TRM1viTXzY1ApBYGqmqBNsw3GWf5bY
swTqBlO40AG8UawcGuDV3UmZ1P2r5Ll+ctm3e1bV6/x8QcN/XBfQPoV9t4MGwu/G
1slopPqwMPRSA3jmpzGAPokjgmqpYrEgjrP0bwu4jg0FQUG/zh5h4S6PMkPuwo7S
QAHMLJpdla5NZroRZGrHx3d/yXiyUk5K9MRdRW7JW1jVJ4ZvYglhO0Jzs777lEIB
TmR143vI84GxCenDE5Xoeuw=
=YMkz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '626d203d-829c-4a0f-aafd-8f986087d742',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//aXi3KuM7FhVz8S3PH2zM73AQsrILt8Ro/SLps63APVQF
VK02JY9e8NPaOlynqeBTkYdhniZ8wXH4vtybL+5ig5CAwKyb0lIuzm+ys0LGHbr3
DNqKyGez5S/LiyzZ90ifW7rhfhYWSPofZgLOJu5mUFTLUs3/fVzGnEgbUfv6Yy8E
b2OmhMVCvh/vUmmj57xz9I6a4ZDonvh7jl0yI+zCMT6SDGyC+ZaxaejmCHj8WufH
qzP/3/uADjtvyaGBn1h7KWxWd2/dBTv/0YN1GKxzuoJQV2znAN4acOOmUSsPgwYr
UBMdusEEwcRDwLsgBbzjRNqXLsFYW+4ndhsOgALD97Esewhv9ByWfYFM6RQ7mQbQ
orw1YsHSR+RRrdnZYFE5MhSZV70WP+Kst+odzzCRNdTYBI5oY8EenhNjFnfuZAOT
preMXBYjYpTyW8CfQVkReHfnN/8iFNVbJYVpDkGXtr0SRYcFhJlYn0msVxDuA8We
wWAyvuVxOQ5PM7dzOKwaX8PEO6tvfoS90NkeOLFI79w5YKbdEM3y8hrrgGoGCQn5
NzUxPHUB8uidrfQDVFqv/Ya7sHy6uXcCBNeu4vyN8hHeXVdBSeaEWwV+4O5ntEc6
z9qurkzIVwYsrwkEx44psme7o4cXbxAydaTNaRkX4cvB5xLrxWcMcV3GWUN0eZPS
QQFr6jrqodnJW0EzdC0FKWOwmpDX6koZiqnm9sFUG2gp4XpodL2tfBNTf0Gu/gS2
Au98x4suA/Qdu28WOZmaMC4k
=VLUB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '62836850-cb16-4d9a-a169-f99e5ce756ba',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+JWhsrIHehlklSeo/lAj/8PBUySEQHbkG1GMV2kXocX96
OxApfjOI9D6jhHYtC2aTy5ho9Yp7N4Uw6zZpZrGBcT7cEcdgQuPaoBXik7fZ9Yrs
V62qyMeCHsGSePaE+nGyg3dKZfyA6Jp9VfwoCRYVtskauFzlh1TngjZXxL4QJ0d6
TtpIDdNB5BAOdIc/kgNWUH8O6ll0kTu9s6wJ3MYe0FnhPyWS22SeHC3hQQqze3dR
zCtSBSYHQ6xTluvgH4mFwZ474qcCeKcBMA4p8I1fpriyUOOrIZIvgH5BAmA/mhFd
E9mUWVdkcdz+Chx/RZzzFRxUmC/Pd97lanMLLX2m+ueNz77DfnvJhttL+pnoznyr
ihm03JRT20vB3RAtmQBpXJPtgcX4/QYivblM3ipu0E7P36E6KGVPv9egsLWJcLp0
pchTq00DlMq+fv0jn6tICzj6HMMjFhdL0mridONyaP7iEsArKa+ZUU+pJjzdDaHj
csOHOeoJAmovqEDhMFxn99pDEn+XPknKnKb/ZqrBb/6MVYupbrR50Mi+711JU9bE
r6S/DDpkAMBJo9d/QXzdQIHAHhfr0GEoFqRr3+7Civ1SCahfAfnEQ7w2ms5TdJMi
E4/J5cwV+fnin5UxqgmmDuTrp35fL8Fvepl7t800J7nMIQyPkN/oUHcT6bHUSnHS
QgECGJuIbHeDlFjngGEZM+DWRsGQUg/EJ9AKKgcy6Rx6Kz0U99HBWnHTnOtAbWtO
lEL1AVpdbJfF3UDmQyyLS3UT/w==
=+Vpe
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '64bf5eae-0725-4184-a2ec-e02bd2d6f720',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9FpreatsMAFlw5zgwZNihYXNxFaauBR99zrp7N8lJMkAI
b2VVuDqGgMJuatmzZCYmWcv5Zv6mTiv9Woy8kU3I2EOLevXaeeRzPzJ4b/XjcTq/
0q7CipAQwk7x+XSHuCEBKkki0BXIE3EQ4fH+gFiQnjNww1pkUV1hohH0q8jOuih6
g3Obj1z19M99wUljKqnZ5Cnj9H2eFvhb5DFq5RQaDcggyKS0pB1nu/fHIZZZ91/t
hsARa2yFvUoBO8MRiysxynYG8k6pJzL1B6JoQ6c3uMpQvO3jbgY7W9zg6yIisCsf
KpvD86J/T7+GkbTVh/WShqvGJ26susqJWHMfx1LyFrxuvNFxclP0n8Rvn5n1eF04
chQZPGccsn98dm/fzPUBpjJ+oGDIcMeYo7AG1ORbqKUjZK5ntCmqJ7kJz1rPFlg1
VYZos3oK7AnATUI/jyXPpw4hhL8hTg+GZoqV+pxDgZ6qa1/UDIcxekLUwoqd4LXw
BTGvK7FsgT45dTvNsQIBle/S4v6WqY9jMfDFax4edxzyjmSbPBCO6Hm1/UPpz74K
5ZxRkhB35804deUyDKncl0G5abzCJgQzWQk7HWU9CHvk1eTZpXQxYp4hPytwpzXS
TxGoChmTLAgPjKAe8RTXdDpV5+t9kRKsHWibYFVkXF4R8WCLq79tbH7iuBIWxhbS
QQHGE3SE6rc0udRTarFfjZnZPAsWX4uA8IdlUyZJMZAcBEkmnFOeGWvuMpoIwgFT
v/rGoU1zorVBOZt9U4Ri18Cg
=hEfq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '650d8c3a-2f5e-47dd-aaa0-c6e1f584f7b9',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+MQPai7bDN9bscBy7u+ri9Id5oQMXkf1GOOl6uAWL78KI
BCSgvOBo2WaVSAyee0ylZuh/92S5znVoTl8O63u7I/Pf0Yjmce3IiJ9c9KqTtu0M
3MaIB0aT1zl6dqjYIUD6IlqCGvr5zF9ltRyl8kpimZvGv3TMj4hp3z97r8IBczF/
WdvDYBa1ksyp9ldpqtnFQ8buGjUYEDb3VPN9je/bPDQweBSdD0blrfEXgn6o4rLD
yu9IvyUykZmn0bv5MICP1hkPxFdJM8vBxFU5edb8nBrD1Nja0NSdTlLZEAeCNNdu
nAkSG9qDXn/dRw9VythMxQRkWQTlylosBBV/Ky6AJESKdVo26/6Xph6kl6Inc3JS
IdzNA8K8qbzdC+ZSQLCjm4QmKtcfasFW41L36C49al99psgohhcBOlZAzf3XWfIH
YRcJI7XIk58uAvWYSw0yZOnRd/pbEVw5Sld7hz4Lxsql9hH0bIMQPWPS8spWvx7x
2jTSTtd0o+ZBDEh0+oe/aJdsZ0CPoH+jygG1IidrVKP43vNBreyGi8X8GQn0+qoI
kZPCGsg/jR+v51YhggVHQUtSOyvMdRLeDa6wpZd9f3qhi/NuRmdkjJp/DOCL7ff4
zfZpghWBSOqPyd05SvM/Ai5UXht2upn0cXtCv3FWWb5WXZpYe4c7qY108Ldv1v3S
QAFryO8kYBNizvpTQpQscoTKGe9gdu5Ber2QR6dz6wPbFmKJ5JS9We0lvyPxumCj
mlcLNA2GN1lfR8NHAjfDqzU=
=LuPf
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '65b969b6-66de-4748-a517-3c5305d21aba',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAlxm5gDmuKyhZDqvONEWDEuwbfwK4iHhozmLWMscigv2L
0FvNKAAhUD7QNSoKEGTjPK3AhqiZIVklCcYQX8RfwynmWXvknnIB9XrwArjn69be
2stoKITzyETEr0r8psRLkN6CI4IbcU+HwgkEhU8tzG6ZXLGxC1ljKqwGK4ED5kK+
JSkF3atdddqALke1/RFoq74tn9F7/4kN9Wgb3d3Ey0SmJxjfZKXp+6IPh61ihIi0
r7ISLn99aXSEZuI2e58WFka0VhDoIiDiuRKuYzuhRmbK9+Uj7EdAOIa4qj3FeqzL
O8I/arApbKAjrDQraLTXKkNazk+y74TF9s6hsV2GyQKIa3G85hrDiYOklm/18t8q
+AiVWUQ6pc1jSfDMcBNBydo1/B5v5THn5FAHMvqbK0REbStGBF1tavQrpyevOzw7
D8SfydlAwvKCTbLeoes8aFZYXXP7CbouD23O/oZoXA4D5M4iu6WfPY9tFvGWaXCP
AkDDnJTw+DkhacQU/XkNJbgvXEK/QtCk6UP/+VwX5EvsAlTgInBx91/wNx0SiSbM
WHPVdq70q51H1Nql3kssedDXZqgsW+qM/5nHwOaMed7Vz1MW30TTRHHiYa6LG2JB
TXFQrHWMHTD2pCo3JgrGpJiMNPD43Whe302CkZvfTOodXfDKTaDZtKEL3zT15H/S
QQGmTj/hlu0jZcxKL8D1t6YbsgJT8TGLF48eAPxqASVjZV5HgSzk8TqQ+IY5xBBF
+pTPtrMxcV9GjAeP7PQXJgRX
=xx6n
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '66dce3ef-0e18-435c-a8a2-bb1a6914160c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//TeOrumsqf71x0cB//hZO5tXySxS4arZR3RE8sFPRDv/6
P5VpZk3s+PXSiXLrMapf5f7FGE2Mely2w+c0zji5AZIEhw/1E8mXzGfBgU++mrC6
8/gCfAe6cIaFXEDqAAqJnyBHXn4KtIJ8Rs5MGxu6rpuZ/rD75E+TgLBrwOUA5L7m
fOzn9rkUBB8acmnFZ7os21s+WL/wgSOANuwDzKodR45FuvIq1r1vGcQDnQDUpwgz
0lkx4OmfUzQDQRtSdLPV9sNYonpCnW0z2GKbxyXB/NDz/ifEk9aO6O1x3BSrMiqa
ZxhrDzHMrcEfrxPSC2sxm1JTFOaYq8fxwb8uGkkIWuP2fK0CazRJel6DD6FuW373
k9OB9ESh5xD2S9gXa+hkDCD2TI0ORDECWYMacn2A6UzVhfFtNkWJ1StQboEN4kTx
YsTIee4zkg1TBD20/agMOSWXbrfI0ld/Ziv4Q0ElW/adTayGxq7Kh+LT8i6t9OkJ
P+X/Yi/qbvlUyRqLlhCwk4u6PYohbFT8VnOhl9nEAonDf00R97FfnQg/IgoLqfJY
RvvQiD3tY4be2fq64qiNtT3AYl5xAcF5JQvFfswGpTzoePaND+BIBrIr2h9Fa8Kd
XDSQwD76Omzbqu+tumCsJdeywT2qnX4TKAArvek4i3ulO6iztrnShjPsXvKM6sLS
QwGmm4s91HzhckR47+Mpte27sCA5HEPzCT6LK7ZjDGu6APgHFLv2Lxyxm+TGlPni
f/3Tu/ygL0/xLJRY/xolnYNmy1A=
=pspd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '67f3c151-acf6-4292-a100-bbe56e49e5cb',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//WloEj2rfFY8zFvNWnQmX6Wbqs9TM9MPAey1+5N/8qdIa
hNdiDGb7WuAh1qJqDsk/2tqhyRRU6sZQPT0mSvnGYHvgbWXToBt5elYcSbOBZ8Ms
sUl07kKfd3LmJqNrujnQDo9PNf31GojvcEj3saM3n7IMP5tmdJIMtLilYnekbKQ1
PVVFmc3OAzqsnf9k/9rGwdjJnQqHvPSh6OpZJl+afYzTXTUUx0KA1Fa6H1B3SCsD
33RasDW7PFgyHUzW1ge0PZD60/bhszt9AtYtZjJiA9Z/0iSlImI71Kb8BqvuSZJ6
YyyyNA5vAQpjvLXw4CXb1WSPIWXW10N54C/fNNCcnrAoix2loqlP+apyK1YiILGj
K8EXJdgXmf1aoZ8lj3zUKZjoQGJ7vEc3bZFmRucqwFEuvTN32iV7KEhptWfpM5+I
tMJ+2t72sgUQnjJYCttuGfglk8xRmS240ej0h45LGxE6wC6r9FV37ISNHCywwsix
E3SlDwjbjf422LIleDxnNkbPI7rmJrTgnTuc0kxjArNh99llcBFx1ziJ5xHa8zxL
wKOY1qMZ03JeRT9/SSEN18X2eFiSSyG1nlsVF7nKtt79kUDHjsZxnS+TENKsc/bt
TEXhw+tvp5valWkf02QK/yLmZf9f5Xn9SR1u84Mpe89agAYnpnmUISSBFQ5FZmLS
QAEEGyxqXvV2G44vxtqQpZ//Sx5fYCBjE2TbDAptcURMVxZMrWbDQReGh1HQxja1
W95J7N8e5nxw5XOTbEs79i0=
=YojM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b2f9d2c-8e99-4eee-ae37-25298daa1111',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+IdBI71+h7hnAPME6udp+PbdN4yBJe7o7gk7NTcQYDObq
2M/QbuIWUyefbwcilZ7vFaaIsnIUau1+4oWpye61g8vDd94kf5CAQWzUQzZSnLVi
aMzdUdcWdUp4OzJsFd4POfSINBhM44KGOFoHkCzuKcKgPElrumA2I3bQsRaKbWFd
kkLeDa4158TvEDMNVDgpUsB4tGrh7YfgH5GTkYK/t/dIMdU63cPvfIFpyzxAzM5O
Eq/WBmerinvgNek5sbJFa6ZYzufPD4WuOHd1GKPba7p3WuR89GZ8TJla3ATkQSTS
+ST0+Hy9vm02+8oQ78lNhHyQA3UUzRLT9D969/PLipCtav6SCuaOoQcwRxzmIAES
PCBja8AWgSUdOncfUuqCU6CNu/v3sHEqh3PycI+QbY1gGYBkiRNl47nBzw3ow1OF
zA+7zM33KEHSV2+HOdLMyKHzm4nCQoYvw9Vf52nKHBe2sTVWEgf80n6yRvXPARv0
nj/DQe0PsKtkklC4QQCaKDwBxjbT+y3ktqvOoGJxfvM67Rg/4Mp2BRkjxokhAMYx
Kq5MZ4YOJAWHoyE/dfLot4/X+vwsLLFd+Q014E11a0iqPc7qI6G5aVAngyKPQuJu
6hgFC8W2kcVkTpnoaiLAw5BvkVDm1Lt0YZnPyXHVVkN27JhwAu1b7l0oXzgK4anS
PwFToTst/VkwsVKmxLvFw5FN/ittCYvwcQ2tMaM8zrg7COwU5Ej9uvMZon6xO3nY
OFAprgqKsJMcLOt5rr++2A==
=fSWp
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b520a16-39fd-478b-a3b8-c0782b473853',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+MmZ6zZuwqwT5c3tc9nir6OcsPWaLudbMR5sqI8q49qKR
vF4Xx4F1d4ww5N8LiHaTEOJ3Pi+7CPTesMcvcj6LqjpmmmFwxoQ7cqiOe37Qbw0H
s3hvyQYxsWE5Kpy+L21BCvWiMoFkwX6eBx+F2kCEbT4Y0/9FXsjWxKtocPY4jW35
tVzcAF+47yN5uMKcPzMzy3uSsj0epcO5jBjKiwVBrtGzh4FbS3Am3hdtaucO3T5c
qYS7BVcTQ7alPQNqavNXSe74oyjCfDmJwJV1YRLjhIB9bqnJ3S9fcE39w+v+GVeR
SskDSLQr9oDEpfLl7lKsa1TUZw8daPaosYcVxh1GzLdHiXlQbTANe2hrzIPdcP6c
XESD/YHbSdRX626LgkFQiqW7lTmammgNj/OaWNbecz0pqL0/A1+xaaRg9GpkMXyW
TnI7OHbjHdE8oaPlG+2aNoUkaazKFoqTmMM35psPUd+6RJdpIjD6PeIvHKsv7tMr
XPKoTJG1MHLFv5sn49Ew8maLIg4XnL0VS9GUolyZYjzM5Yd6jwceLPmbMjLmaGAN
cmMt/UpI52PLVOsy6zAG5Y+yUcMFPFv0dpggfaytWAqyLT7r9cK73ANm63zyT7c7
qDVSHctih6mPULVz9THhX9uzNeQ2MmQhvbXYFc93jkVs2IIjvYJlNT/Q1ue0Yd/S
PwF/uoWfQhinz9Ksh2lWxXi8ceLTVG8Oz59dzccKF3dzI3xZOhlCza/RvUADy/oM
t+VSiYBn/jlk2NpLykgJnA==
=L+5n
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b6c8080-b7d4-4694-af6a-ca8ba8254adb',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//Tud2V2kQJFVErT8JIqaNBUZd2ykvIaWEPEWCciGfoeqJ
s/Ca8WhFHsZ5e6DazNh/pHMtjkADV5FuBiwuCdH+NYPYyKXL0B2u2HVLecbmXgBl
PZhYcagUJPpxMQfQs+52C+vXaC/Nsi6I0Dbmt6P67Nfad6dNQoG1YdUmW/78eW5G
h4t+mmKa6qLci0/YZpahi1qHlxDiOFwq2lcuS23rvumYTZ/5mJ6NYRTCVpww9So9
4UVuiyPCYRc89oqiyvc5XMkkc2QaMCvGJZJ9mCdFV5qY24p800+1zWQcLv5THxwV
b+IQBST+WkI2UxPbYd5JDXvd+t1/9HPXCY1GPPwASGPfdHh9coZFKIOL0wCZuUNE
jLUFg0injFCNFuxVjI6DIl10U1f3J6IFvS12rc2ZYOnOSxTZSz0AtVnx4Xafd93v
OG5qikQz2wOZc1blM8cRi2BKqNl7sHVixrSINbUHwdOwrk3tPSIaml4BnNO7ay3X
QNCA5IVQZZaK/FhN/Pl9NXqHSn+J9wgWZCB2VMIwTJLBpHlNkP9YoQw6YKznwxDv
4eIBZ/qeleuufCaQUgAuKduP0oxuM/UdY+9OiLm2XjKhZUUmBktltReIckM6TXeE
74YxhZ0uLNwBrbp1Z284NDR7lBTS5ei+EtnA3ydXBUvQmeNz9akJ3pbGQVKG3X/S
QQHWhCk4Y+7Xve9Dob2ioE+WhrbzDaVr+66rLwxXKSJyZ0Gtb5/Z2yiLmCWhL+Sw
a/LQcITqkZKeza4ZntF5B2zD
=XjGK
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b79b2e5-67ab-4e14-aa16-24ce26f2fcfe',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//TDqkOEtyeup3anTadUQ5pQ/yl18NSNT+35hkAkKaPP6E
vn6kIu8zj1tA91F7Yg5bH0CZfN/9ero3P4LCIb+9SQUIit5TZx2D+bNQvnrtppED
7nZJbymOnxKzhH+oLrisiDbAG55WX2LYlInS7YDNvtLEDMWYP5KQ0eWd8G1Ph2b7
PmjQyPJ7pGZdtHuD68uVOnA0VSXBbeFEqqHbjha8QZ487qC1LVyug19H5XoLh8PL
nsD6WSKDupb6yX2DjWhdzbNTqIBq71It9ddLrD/88vgNhV9eHDSM/4TDs2ymX0do
mp++1hqCOk8JQDmVShPCTzxF0X37WkrAYm4HbCLeokjElKizzd25vjE+E40PdlzR
T2vlzBKKQPRaepG2GhL/Cz3ID9QGPfqMRABBuTBY0Ze+x8dtsMjpTxMa8WIy87bA
usIYlkNnhpcmhs+CFM2TISDm1LdKMDt+jKvAnvNvR3vL/NFfZIGQCFUS4ZVQtD/L
BWT0VwZ5k4mZikDJMRf28eHWseCyyoroe6VvJ/58/hO5CD3oPlnj9+GvkrC3T3Oz
wzyLYFOXrVP8dh9CjuPspvsaXYQ5IWsNRZAdbCil1CsGKGyjG0P1AIQDI+caqfZ8
5vrnC2WPwB2Tm/y7053A0LEBAw8XIa+ZAfA04cyfJ5+Q09iQLKjLtAh1xzEELF7S
PgHpiFLeDFUn9fQQ/+QaOVFrd3q3GwQR9DV5MbzAgemhiloxgwP/BYyHa2hK9ThH
zUALkBYCnf0BaRi8f2PW
=Xnuz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6c427a0e-8661-459c-a760-1a1f11adf8a1',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+LGEfyTmRs0aaUO4EDm/OmJEbJQGhjpOJaU9ef6cX8ICE
EJ+ykEPkqcNg0kB+z7aaJiZhDUxcPdAbJm1GpYnE/eVtOnA4UOI6F9ArAv/seppR
QJPEhESvVkw9ZBINlpDiAD/6dOK0kR0OMhPpZOgtJrZxh/f4YyUjxEqwTdzjRHZN
5VatG1t6ZLAJuBLzYSNvYyDadpLDJT/DRmDjADB1n8Ei+hApB5GRptsi25S6qMfH
kmSJV8PeNekCW+TCQWaZ/ayAKlD48j7H2ZhsKhsNtyAI0TePiTJNb9M0jQTeKtJb
xM5fab8F6toX42+J5nzXMA4TmoRwmjKQtHbmpvQoobCjanyBCLY6yYv7wY52KOCT
wxItRzZUssjfc30ccUTFB90mn5WGsRJY69W50bLbckMKNFSUAD3vGjLKnr4TxmXt
LRhBdH2MuNKnAavUCuRlgSs//tczCrbvQJG7M9GlfW00lG545akust+5IA76Ci7H
jAQatXa3NIsuiLXjAjyTqYdqBpeah9+AZm37Ao0Fa8+QcipgZ3riChZlhdd9PjTl
t4wYF11Y/dOEM1EiEozfPmnidVdTcj9HlO4XDMnZUSk7rH34LanizPQcs22zZevh
cEpe8kPPa/JUxm0yM/VyFRaZVyEqEP2xN1wG7TD6Ytket//efDFsGxrRoLZA5OzS
QgFRLuAnxg/1CCjp2ZQM9u7qI8ObtWI74gZRkoKVOoIya1eZ/vKZoidDHMBC1hL1
YcfdPHmdBO1ZY0b5m/Dn1wQ4Bg==
=869O
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6c66e163-e07d-475a-a578-c0ae6ba0f3f5',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAlGmOMs/cRf4A/32hyisK66ouXt6MMhraMBeu4jfuLq81
iR0GIt39xAG23fkUB2tUc2VFhq7leop+GHSi9k7bDp4+lnYM9m4ZVI68td299xVy
nJunWS4YzhjnRfBIl112y2Jo40Qq0LMhrXpbYQ6eN5UB6S9dh6g06+81vSyD7joT
ZtCoM844p0/0QZYD9i00vIAKFR7yLEctRBBNxGRIPeGfuikcko5/g3cR/FcRHfaG
7XJ0modz0ZXjo5omye7KgV3npeLGOuXlqZaAT+aomUs4OwgMPF5m+EW+FP4FAAC3
awZRz4DYteGUdwUVlLuGh6FH79aK4XZ48Prg9vlZaz97VSM+Y3qPJyvuq1o2q8MF
IqOjuCRXtkgOnt1hx4DtxdHCjWZyRtEFweFye66AuR+uAUeBcy0LIw9W0I3an8nI
1fOfuidWWcfkhj35isIT3aXN3XGGGZgj77EOKM2Ie9L9l/4PUCD6lwZu892MDPFI
jbH+99gCkbf3cPCWJHJ3qvrz5u0QissT5FCEs0JZv7VMCtR9MJhnfCIdggHOH9O+
34YAjMIR4LJxbjzr28GxliFSG2cZntMVpdjRAj4d9LTJK3DhmsBZkEwuWjFBprTT
T8mg1FgfNfSq9g2HqgxQl8k0tp/CtycEoqo3Zg5AQd6Q1I3kwh9pZDoU15pMK6/S
QwFAsfjo2uxNxAN16saJU1tF7cAaItWlbegdd8j86RWKYuUeYCk11ifuAQ+8A38k
HM0x0lKVIjxRFtRS012xBFIAO68=
=mdqC
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6c8810ce-e321-4ffa-a976-2125131ec082',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAmOXDzeMs6iM1fk9VFZwy3jIdadt1heqc2FLrdMuUPYc/
638sThJCER2zG6ACSz8srshIbHrBbo3/qYs9sapQet3kb8iAdbVjaQDyzyb/RSr7
/wb7OrzkPrzazGZocdx7I+C7H0G5tqIBSab7T5BHQMyUUNbhDBiwrPU6C2SKX0dw
8XU2HsYQaW5ygE53K2WYtXP6Z2exqoPG3nEiMhSS1xaYOgE1bAQZqNtkCmXxrwPk
uSUqOwRfTfEXkMEwje4X1y8lCdy0d97pZoRkpoz7FHmm0vhPBBQFApFLZESil364
R3nKT598CaWiyFUoAu2VRG5fDFASPGm+msAuuL8x5VcZsbidJFVVEZ+3Y6ULT9U6
GDiheIw9LHMa7uZtq9CePdwiQKu9ykPKxe9asXqcxkCuPXf4Cf78xpIhmcvwmwr+
Q1+pOMFqc3CKNQVyiNnNVhkEE3E8YT5LkP6Er87zRsLpRBS0TDCOvkHCu89P4UFQ
evzDOp7cLaVKEMLcNhdQBUhINLK8C3O/KKjBCDlMMHOSFMvpsTg+50Us117CCO1W
HVno8A8vVWoafw6CMeq6Th8q6u9wfG7i/FxcvOU7TQLO9mqdXTIYXNC0SWtW2nGF
8bCW3rZjHkOoJl+No6jVLJXnGGfM+lK+44aspI3Au6Ec7B5gllO6/sITLLTkn+HS
QwEihdX86zCyGWIbzjSXXPesVK7gn1ZxgKZHX++MhH0gy7rsqYGmY6BUSwu7Zz52
meo/LcZDUYp69zgHWI6GuWM1V4A=
=GZ4l
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '73d5740b-32ee-4043-a099-4c6f9ec3b88a',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+PyR9D5DMle8Xk1b5I7ZVPylirhctvOS3zQQPOwUIZAKz
iMyJg1dkR1dGOnqqa4rS392eegM0GuDduVg3r2a1WvHYTzHg7S1XlAecqsn0B256
jAAR+un/VGO2RpBTAN96obDAJMX7bmsuGLY4KEQ/qjXhMl83hTRbtBjbwWOi+Hf0
G0GDv/Zqt4ODWon+7q+S+L8Hk0CllJimgCR/QlYQNEHOdoVlFWewcU3VyDq1/f3Q
wM56hfkVHdGTF/+YNht/+ThwqnyEApA3Vn/3lXYAtt3cX0yVrFFn+mKu+QimVAKW
UNzsYJbXpV99hvjTHzx89VC6ZQQfm4jImicvJetbYyI2jRAFz5G/rtAmBzyN3pW6
VrBOShSkaEUaUIDoYLatko5WkQWk0qMF34k+JCE0zELpdnYY7nbAIveaWBfmytVv
ax1XczWg0Y2tLXeeT+8ba2UKyiO4V7lkkI2wsIhbs8Q7jlPXN17l7dOQy82g+Vnu
gegfZ7u9BooT3FPuxPjsSHE3M/pwwhrVcvj0T5fswv59KSGZg5s9Fk/8JKay8rVE
mM81lgWW/EGAUA/n2FqIhGXbX4/5sy9JWGN9TN/uCj1cd+2unWe+T/muqQxmy689
EOVinetpveawXFeaZNLy0O5eoFJMfgBNsCFmpCEAe+qA+YIUsOU5ql7Z4Fu1CqvS
QAERcG3sDBnHzZYhNBFuIP1dkcttgD15hfJ/+U8HRTFXgelinQdIIvshmSnJxIu7
F5ntBPL8Hg2rGHNwxIy113w=
=1N3t
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '74f49f18-65fd-4a0b-ab4b-545da556f055',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf+LKmWimPmtpsAyUg/wzhejgcF7H1ypME9mfwmPVWFToK9
NOGbVhv2GUlspwLjAnHdyl4s/LVJ3ksuXNq8JMtPmaq40esHHDbdhQOjH8jjjKz4
549dPMgKGiqLRAOKXfIn+IykR+hxZsvP0pFEds+Jcm8ZENXb68sK9eWHRcACqkmN
DvGm7FXeNUToLASxK7uacGWCc6zwfFaXEnNVxaQ5XlZvXbPqkfej7vgxByCxTpqy
si/q3Yyp2dQQdq5RyjQqRox4nt0yccwpDUfxCa3MeUH64R8U0CQF/+jgv87iliXs
BtJ047vApYzjbVTAQM0JeGT/WxZ46L5whNZn42mfb9JAAZYtjPfJtFhREqkEZYig
0X76WkHDYOgtou5C7IymjkxQEdKDx8wPUEW/vhnfxSRgJOGstDgi1OR7WjMN3OeE
zQ==
=sSk1
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7545567e-46b0-41e0-a73e-103420605567',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//e6D8/MCFccRIxMvZdpi23Hyrw9eu5pnYx/7NdS5RNAev
6BTkTqqbls5LSvtayOHLhA5sYC/7iYFGO1Jnek7jWNgeh9ykc2/BDLoW2drC5j/D
2RTge25VaaLCcZmiJReD6l63hd6oj6NSM8YT2Zvq10LE+HZjMKWMfAwBDrVNeXEG
3/Lw+VpaJPscTwlslu3BiLelnjVs2o+JSJhnR7ydcfLRG5poLEbbcqlYnnEKBt3l
wdl95vyTrmI0Fxo8Itl9pazR7dRmP2IKTCYAO4XOzl2IwVimc/rtrovV7203rrEL
kU8bLcJ6m1tDkivoL7iSY5PPN2OzSgOOHDML4hfSZl10dGsSsjH5pcatTu/C/m1B
TTwN0sLPX92+Nebyg3uPLy3oirH9CpD4Pu49MLkr7b4QnxgRKBJzQKhT4Ph+HcdU
zVNCIkKI+9IcjdpddB2iSY7f79fDHLaARz7rq+qE4MDP9LzQLAgboLdTUH8Y8n7c
9QxRRNpJc5yKxK0G6+WDE2z5Jv+KiSP2ByErs1DyzvM5GbY4p6112U/T4FeagV4V
xaXISFqL6itLur7M1//KTB6hlSNjkYVefoLlQDe9IjIWgaCyi4JSyKWYYGhz5g/9
0uxQcRXupD5Zyi91zeWft36IKStEplJMXkC7VEXsLutdvkiQUK932t027cH7YjbS
PwFrFMCnhcISoVvGlQ6z7B9Jb4/ePDkOg+MIMwFsoRn13FMROU5eS4LUAP6+OrVT
wi4YejugqIE5yKqSnQDLFQ==
=WWaw
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '778c793d-a0e9-4da2-ae29-2ddc95f65014',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//XmjhmwYVpWUO63HzMDUT98hz2kCp5haPzPtM1YYYamXc
PQq427QSR7xKntncEtMoT97cjHOWqfPWdghPMC24MhTlCXOzVgvEWgcvWqXuTOf8
08AvtNmjT6yzGv1Umz9uv4395SSHjVr7/BpZDNSr3Kjrv58lDHhbjHARzUk52ylS
A59tI+MrioBuR1ndwiM6yx7TcRgF5zDD9YFs4Tor6Clbucjjn4wsyUv3Q9whBpd5
TGvkGWEbO5E/C9K7zmKzikvm/Zw1hglB740/ioA0nmgE0wsMVJ98tq3xayby1njw
pVAAEyH+14kRwZ7r3C/TX2ooIqO4RnOGwiVCb7MU4yqGi7uN8gaeKUU8/5MtYXxK
1C2RqzIa6dUBmLi93Endjbf8XefpjKhFnoSViPlzH5csSuOVXXQqpNhAPjrSHOXQ
rYERSh9cn1j/eWkMJQ/xqok3Eil6gL0kpl4OtL4160ah459yGA2TzD43qG0kF2iu
eyhJdAbyl/9Pwlmo+gDBv/S11j8X+6/GGwHNtv2OQxY6ogKNsC3DgeCfjBvwiZ9P
DV53YmsjEBlPqdDqUbAtJc5uj1GQGyodtp1qwbq7bBXrOpm3x6aomabxIzwHheIk
WNmbryJASAlBKMNeNjXV/CAlirUCGVyuYrYGYk4lJtCUjr+3iUxsYdWNtBW65dTS
QQFoR3T+rRILaV81s2ZLa4FHhrt7a5VE7Wv1BuQexc2qRWC49Ic9nUWmQ+nC6cij
gfjWJCwqFqIJV/zjV+qJWDC8
=beql
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '79570a1b-344b-4553-a238-466e2045d43a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+IihGTLuz4paakl8Fe6n77HjzpWZvGc2Q0dPHTPdxLF0f
5qrFcwioA7QAyAq5GLbpocpCPgDeCJUJ1zD7zABkAvEQ7Ku8E/D26ko0KNKonF7I
Kpw9vQ+X4vSQC6hAEHqPwdtWtTZBloM76ZD/R6eJb54g3da70BjCoczSzIG5L5T5
PuVV4MLsceBLLWiOsXxiAYW+TrklYnwWRf++9KPW3cqyT34KW42duWA5sMmITWO+
nEij4QO9SUhAGtxMfPfs8yGKh0FcTlg/+NL8+2KjBpxasg0iXNDJSuWTa2W6BbEe
DYSCoxPs7SRzuzeO5HfjkM7GWy12BfecXP3hozh0BLhQSYRYM8C4dS5D1XscwoJi
bNixFVpdX5kiOr7MKBgSeNlJlawWjLwZFYnemx6tvIRWgL2kqtJAVmpOXCc5pcqn
4sskciG3HHFtsagdSkVy8Zykh1igGfe8lXCcg7Y9RYbShcrSFQyNv1uZamxBFkaI
y8pN5wt3q3CJ1Epn4Anz2XRR1SCtRbCaH0L1dogofYjWl5GU6fJ/7qMbbD4kYGn4
ZUOOsU7M7kFN3f/6W7FnLFxv0FcPcp0HOgY+qszH/ncsOxTZximQ1YH/bybcqwyv
PJvJtU3AD7GunBRj6Q/cUhnkMDDroV1MM3rw2jBs2RKj0XdWOgPFrSe329bzm/3S
TQHEaXGyB5BFE8eB7H8t/mxa1PKldWxTPbHacDM83IqSdsAtFEDe1Qli8S4kEOIS
VkIzxUx4jxpSRGDq+71mGmGEZgrh/nX/Yf0aFJvU
=JzCQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '79c3f255-81b8-44b7-a2f7-7b89c30f0947',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/avG9/GRbSLczYPdeeOx0Hp3QIjBpGMtFHcIvLCALTl87
rWp0LfBYccw9vOMmsVTw3lL/Jc5TuA+WFcyAEc8syt8oMBMn17TbgSs53uyFjrWY
67E5CP3GnKy+36XzFqQxRi1sbhYNMykx6vjsfp1FDDZ2RNWaU6DABXBBwgLAZwxL
rjHOs+CEEZCAhpsNN3lw0C87ARxhPdzyPdeWyLQqZ3TVBrGfBwpOg9d6dyayifWV
LVony413ah+a95QEwBlkGAB/M4FCQmHzlcXOWblFjwh4Iw04YoGZOlLujq6IaqDC
WKSF1m9b6IjGQI7zdT3X6W12uC1Fn86GOfokJElabNJBAYxPNWAmXIJRVXpE4Ze2
hi1UmpyPLvoGKTGSdGWowX8i93/kRJJDCk9zGRGGHT0WDUPMAu3ZeaWFE8DlCYbe
KBA=
=AvnZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7aa24e61-1d01-488c-a1de-14859d06de8d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAApyJ5F1ONqx4e/Skiu3e29tMJGQdyDPpPQ+Fu0qPafnHg
A860nZ9fD1F7XdpkTjEwYJv18CgHwx1pg7qnRyGZQZtk25oEGfRN5HUkjM3dAVxS
vWw5EnrSkyC4nTlcDOdsX/XTtGekEHK8/Gi2Snb/474jFJv9jy6lhmwQZiE0PTes
QR/hEte9P9ZUO2LDwJhXD6uTZx/vAuQWyRa69vaMkR3TrTdmT48LGgWEBKp0xsUW
Cd606uk78PVK8rfPr7CGsJewVc/5Jgqz1/+gYEeLg8vNZv22v/p9Hgjt+KmeqXIt
NGkErvEsEaygaz4arO3+FShYKPMZb2MPqUVsZyaDijjAw14Z22mgvxuZb8ZSxoe3
0M51SD9lqgzLD7emwl6XQp9PkvCuc7TsxHv71/1xXma2vdCL8YUig2c2MnZwgjNX
URuTuSNMYIoLUpsJu6FoDfr8wNLdnVRdho/EgmzyQJHCpIXg0VIG2wilMxU6q/6S
v6hq3nHS0FBvdtlKJrFH4/ZRhphDV/BAD9MfBFUlalrPVuJP5WevfC3/HTpyc2RN
dQiDJlH2HF5rC0TZHuAlqRDyeaIu7pKxmJZudvB6QoGpTHOZOWfx2BQql0kcyaNM
10L8xzDd56f8KSHON0VQVlkNFngviQZf02m4JM6BtXpKVhEaXs4kTZaxT+n2JMPS
QwH0Y8maPpSxcR99rr4OdwA/HVV1O+HOk6fjJdcmAPJhZBAWHa7Wh6PQ3qnPD3nL
SbRMRB1Atso5vlq5Lgwa9+XH69g=
=E486
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7bb90fa8-fe3a-4ca8-aa89-0532f98109d5',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//XMR8HwkRXUAD8IgBlHbAbZkYHiEDwXTXPqbCz0iYgHbO
xCiWDAptf/JsOznW+5KlsnAiKGyGvmZFWO7I+Oyu3InZM1Mys34xfjPARhPh8gSh
ZH3gJr7TjkgcUVfdYWnW5PGu6prsr7uV6aTuRGFXz9lfoSjtLo1ZCY0AyjHlW2rl
yDfMamiAhCMFux0hjj5mATk5rZt7qu6Zmo8VDyZT1Vjrw0Vv9/Ml8L/RWCiqtzwz
cnEvBupPDGtXUYTJ7qxKNYrRHNCkfUfCAcOrQNPL/PbfZk3b6fgBhSgJz+9ccMGf
VybC49UOmejS7vU+rBDio00KAGa2wjAmVagvhS8F7bPEPC0piaz1us2CB+XKf9wV
VflRfHCTQH6/NQ9gUFNLVd4nqWZlyNhbkFCf3ydE/2r5cgGkwsFsnwnB1j5e/dGa
PluQicIvYvXCnmRWBh92n/Ku6rh41TqC6HNeGWBbvWjvOOlLSpFdrA9s/pbffAd6
+i5rU/PI22ujxrjZbzRgGmNKqZMaUF9vXCSLrXmd+gjab3wNlgifOS5cFQsSb10a
iKjtI6EG0e4C188UV+j1ALWQIugxbdq+sHFyZbnOVuLgILUq8p78h4kC21oLOhGk
/bBNdXPuAoHkk2GJQd1dJ5BtmXT++ywuLMCXpPz8B8e9fF58gWBpHPB+hLOTz1DS
RQGrGtr/ElMLddVewnSiv7M+t1Sbu0Q/a8v19hVUnaFUjhVvwaApJRQsaKVhWZKR
HL7r7gbFASvcQ92DZVrq3n4EOV0EYA==
=eRFP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7c849109-3a86-40ab-a3fd-f6828b5b788e',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9GZWSQ696EP4iJ/vveP02iX2UMKeuggfjfLZzykDQZkYf
Cem8rH69uFx06txXfohcEvE1BpwkcbgAa2QlW1bXvQ2sgALKkEWbLsJ2uonjJfvL
I7ra0GB/MkeCi9McgFEDQyG/qUO2UT26lJ7oPhBwDxGrkIp4TdWNYDRazC83n5a7
r3HOGJAA6jEIcChw8B8bwBDzyDa/KXuF+LmIRVhJQrI/gd8DL2P/j3WzSP4CPmze
npPugNuRh4fdt5FQJrzNDYqoSOAIXQf6aVXMpGB9du9K0xxefIRvu78cZ0BQqDDf
C4820ubvVSjuZpGfnFj3McBVaayhLqwNHVo5PXoYxGG6/6/Tc53DoWVw0xsqec5J
y4+2I+YysBk+y9H2oktMNZX6JIBpnhVkABl9a3XG1oBnC0DsWZEd7fzEa1K704VS
mTRx5OszpAPBJfOmkVMIa/tFb2iYxUvvnXB7p+6ZUZG5WH4mlcDJ48rxpSgEsEnQ
/GSnjWnS6KabgnJQha0gvDvPSm3aQEU0j48SzXEpqHDYoIFa8x+ad46iMr5hWEs8
OlKTkyIsVlpnKE851ReGPkhenJG8aFxw7/5p0HxKTPeASpihmQXwJqWl3WZCraxj
5qycQxRFlQWBGBsvz2GynU3KNwxp9meKiyCMcx9OpB3TdjkLYBLME+d5lrBCtPnS
QwEUrLQnv+YHezHz2/W+H4gN4Mf1vbRHR9A8Ucp+BDTFmIc2ISfPmQI+GkVJfYY5
SVH8JPiIQGJ6axPj5d/veGzsd0M=
=JQS0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7ca2d2b3-524b-452b-ad52-2cd5d0cd0d51',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8DjOnZSpRXupxiCnC0cjSuvsuLFF19jAKF2H8cRrKCAzw
K5adk68Y6AhfAAvs9HV6i/p/D4qXAyhaaPjzM9xvaE3gHw7hcUIEivteqvbE21sk
OC7gSjStXT+P1dwXUPqXyUGx3KQRkAJO/o1d6a+jI3c2cgQg6kKKHPsLHVArufsV
8XT0y+twhkPCjY+UfEGc0TjdRCLHTw5Z0sO4yR+25P1vagRO/LGRXUQ3ZOkTMWtX
DuueLz5YHcKF4QPl1Z3pRbMLjr7yfGtj9gZGMAHfY39WVKiUXEsQsT6Ao4S396UY
eNz553JbOFGWXnlpjAk/EaXlDDeW9hwXPYV/h5Rcv+Fu04YKoFSHdz+HEsAYAOSm
zbkxZk4oZ4EtXu4bwE+4vmHtp4HPqaQhw/WU6xBUA5OZ5RWKme0uj3B4InZHtw63
ZtJubcvV8A9gkBCHyXCzq+Df6f9X8UW2PcaOF9YGAm84Y17BmWQHgWpB1+rNpGWQ
GZx/qwgk7g8wixiLuVTs/MeA75ipUR7yS12+//uEB+kNNtytwXetDqBnyu+5IZQ1
TxgyoaLWsXs+FOkyPXZMovMlTl8hya8EWy1R+1G8FAW2UF4ek7u4XIbQB0Z1I2Xi
pQKOc/3N15/sJEfOaDCAOfns81q/VYY7gdGrBsaGf8MlntX6JA08K8b4RrJRdwXS
QAF3bO7ugZ17JxY24gXlDNMvbzRwXwOdokH77bfIQcHp8HURdyjiQ9SrnEK4u/dr
s6ObiBfAUZizNbeddbFQAUU=
=xh74
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7da5349e-aec9-4951-a038-f919dc9b0402',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//eCP2pC22oVVoDKG9/wwSx8Oq1Ccw7wdjw4v/Kv3/7q8S
0uHYINrkOTNYanBQcbML3ALrA+KI+4P5vxpN1dCifVaT2oIgFq+Iq7PYOmbEBG/r
9Z8UdN6zFEJzE7c17TFwr6ZCIxpUQQSqxc3gk2YuPjpsp2UYC4Xh4SCy6S8NAeHe
l1WVA5vze4cKkezhHk5wUyNFDRCGOaxezQO0PSby3yK9GDSjdZaGY1Whc6qWDCcr
YmwtgViL6QlvxH67N5IBV0beDOlhntJ9OotobaremWj29T+xhSFIbPmsgh2CEcUg
8+xeUY8PcjNdwpjVgzI7bUiKGCiHxA4Los4YFLllZ0NwmLJVwAsKhuD29JL6VFD4
1cUop9JL1xlr3U2I0ud6Pm14cTF42gmhNjkeNDT0aXQV6j+37G5PB/+/bXMPPSOX
dTJ6gbssZ+Y83rdSroSfnKK1whHNK3v1sDE9INGNJ2R4Wqotwqegp7BuRS5HzDid
shb+pfDIzi2QaeogObUrh3yT3354uZuAkSBzxFViwTAs4gPjhonMxkQ3UPTFj7lu
mbNOslJrg3yg1iqLcKeOZRic2efvhDM5foMoHGXEHrr/j+VFUMZQFuwAAF/ixNo5
fjcPQ6vgG4f/09/osnvTT/P9renHOCggYFd+xNxO5vNcZVh1Dqa4AEbWYBrN4NLS
QQHUFMYjAUUwqIuDIwNUkTTPewLd738JW3lJTeHpqpBQAXOrW7YaVppm4sgEK/rG
kGSGY3MRSQyMIba+81NaO7gX
=xYPq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7fb1779f-750e-4a98-a26d-f530835653a5',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9GmHKJQJ/GC8CFq6cJSLUCMM5flhYQyI8LOwqPVNr4Z7a
+pNdT8r/gnL7gP0W32e55uEUpVhdmti2/Ok6u4t2Mo2djJ+1aAwNfedrB8j2BGoD
9wnAB4ajzHowiF9wWOFqgL9cah2lk/T0CDtrqqWcyvq/+vwP8hcBzpgNJacosuME
dOLcwXJ7Oe91Y0z9M+BrVw0/6l2Ig70tUjakNrfXhYB5Q4m38hmJ/p4HJWF+W7BP
7dQ4DxT6XQG1QGUQrneCgamAKnSBWhfIyYnPIAr0Rp5CTesl5pBVNGM566Cufn8U
Tr4NwxumX55I9il/ItwY3EVB2y5B++Gcj9XQZkLo/SRUfzAazP3UL1pS8ekfsSzm
zgGz/oRYDnhwA5EyyIaSTsrwlxUdxbhHGGHXJpfaIpecJ4DxzACgSZUf8SUGp1gt
CDhyIAQPMRwgMp4vkFhUqeDR1mvjO9veD3TjR/N1XjcF/j1DKc4jyCAZcpXYd8f9
wK5+TSep1F4wSgr/Z9olhj0oS1HZE8T468JTducdLt1Wc/umOg0GlbF/C/FLBdCx
doIz2boQVsbcrYHa3Fhz9MvMq02xu/4A3Y0vaAoSOQPB8axuE57SKTIEArCcpqQn
VyaHs+bfFqWIVzg1SStCJqFDzWaNygwU0zph1hI+SGhNaMMS2+sh5Yby25w2o9bS
QAGz1qfBFokI4S7ATuNAGyR264wCH4X8Oi+2EJrKYIwN+Ez+pWP+vfBcKbwgqEna
kxrnrQYIAZprjOpojKC/xPA=
=dEch
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '82a54472-6b34-46f3-a719-b91b1cf67d04',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf8CP1rbxW579wXQfbvGpB5hZQT2xSuD5RcpFX7loa0gAuU
NvJs9cOId68rgPNF7cUFrvt1xl4e/sK1a3Vb7M1Rof6bUyF3RjfYCOF0fcYmHuYL
1MHaNfgn0M7fjZOwmF+yyhxSfrnvDGtPafqGrLy5oF+h7NgVSIwHANJRWZQacDJB
HUy9qe+YS1/OBN0WCrx4d2E56oF/mxmYEMS2VATe0RS06CtrVUBlwdbHZJuNCRqD
nTzTNCn61F9umx0hTFvqXQfu+SUJm5b7odSDuzjW3JFO+eYJmY+JxRAO7eyFEztF
/B16V4nWAt5hG060Ia1qUDCWIuNnTCpkZR0tuTUTLtJBAd7srESDocVJ/YBlYc1m
r9zSzf8/9OApbwCvHcMNXc/ppyDsHgRXclQtBqZGuDYpC6PysXM9yau3FvmDTmxE
U6s=
=uak/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '82e82327-ab14-4853-ad31-4b606ff27438',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/8DVNtNQQfrmSpaLdz65fp5suLVdiXaxc+La3AuR04zcc7
BhbbprL6NbvJo9MbLaJ5m4ebQLkO4xYu6tsauAylAkosXHd24iXaL2AGcGs/HvIP
A55WG3zstWKZcVK//70N8i+SeOqsQztdOE59wgzNqDl3k5JZqHjK5f6V+CPbKdTH
vPJvcvWYKMls4EqLDtfSa3B1ztGbJvPimD46TPCbRdI/zNBvC82uOLv54K17iAf4
9tzviash7bk7biaSGyetTMSutLv3UPj+I1gg/UIvH6EvoQ74h3P5mxmjQQ4HT1Fj
DMqrOU+sDPT/UbLW53mGSeagfuH3ZLjwQXKWoXA1Y6XrFkQFIfAy57S++pR8vKZs
cn4wNjCRqy0lLBBobnMLCCVp+48/81oK3ovl1MHXDDW3t2IRICfY6erSbQUVfoKc
lPABjYiKiBdemDRqgCIxgDHCN9ye+OZcaP//p0dM301R2Eo3xwp+cFIRY4rXzqnO
oP60oJFqdaxvQXcOdGlyEL6GHYGDHBJwXEmtJjIj8T3Ulv6Oi5Dpx8CLplh9Wkrr
nrH1lDE/NTE5S4PjoLoXZs0oqhMP/700S9k9FND+skg3/tbuqLm+cjIolV+kTfA9
9cSCeD5AimViL5wir3hsDlkuC6M9GwSSyZqP4VDMjJEUd6cGKQPc/ZBDj9xEhArS
QQGp267ZoUI+OJeJzwDFa2uihgzxBtjeSd9CIQsC9wNEDp3wa1GOM1fqxUPpLJox
HybhrxOyvM1dangHE5Btlvtb
=4A5m
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '85617a8c-b59b-4f74-aec4-a3a001f14dc4',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAkhqXtV13U3WS3+TteZqP4NEDnHIPi1ZNjwjMWAzUN8YH
cxzh0vxhGvyP+y75nJOJTtyhcfb+3opO7l0+OB8BOTaGeyHXh9CtY6aWcUji4tXb
/Icm03n3uZBBz6JIUxaqsITRZ67Go4ondDT4NCB9uzmdJIvSMn/7TpF9rPj9eSEG
yHzEIWEiQtdrdLchAk6bFj3apqKgrYMH8cTEMdBDsWb3S4DlyxJWpEYvucghffuD
efUW6SQDLsGwABv6oqqqyrf4Oi95jUfCOBB8lGNmusdns9NVOStHO1B/Wl0WX7+Z
+Ot8ArRzZysQD8+ZNl2hCWu95HCLNMnZI6kCv/Mv7OZn1+IDMmwG9BswdcqLsHLX
UIRZurhXlMx7MAfd1ekKCHmDTYyQGuvTEeCVRZKKdVO1bQs7diaJhbn8bz/Lyc9T
1qqkjFt27KNVbi/+3FhBdXIf2GZMlb6z/PdtT98D3XKV8CnT2gw3P7cDdozgY+U2
c+EBkqEAAsPMM2ri61E6oEc4SvV4Oe3Syr+oRuPKbkodkU6NPWoO0CV56t2rscg5
SPVrBdwIcO4aBNoXkA1nX5KPQ76yZwqTcITfaGYfSfW9FCaH/OEaZFWywrDTG7sq
tKlniRSQDtL48wxvCoNXdIGcnLsQjSeRX6FAPIpL/txzZseYHggl1VmqUj3HtZnS
QQHsbWVeOVUicYaBdU6NRFENSm949LISPDQ4TO70Zi/MuWDqkSeZgSEg5HS3F60n
rUBCHqrKL6ylNRZJoURtLjRs
=qgA0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '85b01e5d-beef-4b76-a39f-56bc26995359',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9GrN7DlAxE1KhoWK1iedllZHjIi74dLRAeAJtZ2oBShH7
Dqr+Bd064B4R2vpopnkAZS7Ps4xVJkQ0qRUlcbcsk9ZjwrA5A5pG3dNan8xaLASd
P9tX0bNp4f4T0uP9rYov5Au/v7IMLfn+dUsY5q/PAoCAR7bySUY75sXqR8tsZHUI
QmcbaenO1PkLktH2e0/oynL2vVZCVhmNH1IAvJJdVJXC6JJXHvv4h4FNYZoYEoFm
7UIdkpAA9vlTRfPAXmLMY5KZqhp725cE2ZNuafm1q3DamQh0h8J8xqotOXhNSVwV
b/bPmUqCiumB/QYKGURfiirP8I7RWbWepbqYdpCU1s1UPzX0UdnF4ixkHxEys4np
Ags/OP+UYvwchkYNAE4h9TZdozV8Jy8tymBw1bG94OzfhxMw14Rg6jJSxXlutWog
p1i03wECI6PkDeofaVJUfzRxcCwBYhKlX3Rr8CpF/slWcu2gFSWP8Ae3u1qWjdIv
JDgQMdMRtdP3QKvuFr5PUFw78+MfCIv2LRedzEuhz3ZHCEYaZTB+iXgsFq0W1cwF
WDAtg2U0LfMa3M2AuzvkxKyfqpS1CGG4bTUPii+5471E7eAmy4bK/3UBSprHNhTI
2rVUCpsGPBOzOp7MgjBWq9uz72RnyAOC+myDbvgBlbU4Nm3lV65bFbG55Mp3mrjS
QQG1a+Zq1r/R0WOjy6mdV37ibxqnbWv+lKbI7VNZfu6/Tm1i76qVG4kwNefd+sUb
/BGJFW4+OhuBn5z3aPQ1JGbH
=jfsy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '872ce58e-11a1-4dd9-ac77-cbeb5afc7b08',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf9GZz6JgSg+YTYbEGZ06ZlZNzOeud8uclaDwONqq9zXqMs
2aJo8fNrXVgzevxaC4LuKDsGtde+IJxIUelUKertUbY6WJY1bTu7j6KO+cy4+UbA
kt448kzvOZwfBfvDMI40pokPJZGXwXssqv1vUjguotsVzY/Ujh7TRBh2O5L3gsT7
yNDp34ttgQxtewJgthZ+9WlQK5QQ1PUJgdISM5z8n0NmITxzkqD7P4nj/RLUDTDq
2iEuLHUSc2XRd/ZpQwCteYBroWnHs3WPgO/MIDOyQoUCQSvHBIdHLv6qiaBLK9Hk
+MKmCiK8sYGBNobC4Kr5BKvceY4p6avx2N+A1mpoS9I+Ac1aSdbdcXaHcDrX52lF
XzSVV/VoKq2+S+gcQLDUi6kIA+sEPFtS4lawuKk2W5oLzd6xECexUBzjjU/lMVA=
=JGre
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '873f8f1b-161d-4d8a-a75a-cc9cfdd97cbb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//eBu+e/ofph6Q6ZRKdufe7UojhgeeFxPOJir+gLHkU8Mn
IC4JWy//YNI2yTwvihtEvjTFWqA5dzcFtCRWKy+s67aGTm6V3veeZuGtzzOENHkD
gUY/ES9c5JtnlmgO63HDtzzGkaJzhf9GmEtIVNAyynPJqJpWZwNLiR50jwhvqeTD
xnTADqXW27z+wJLjOaGvpTtoElN5AAUnkqn1XmUFwvxgCpPCp/dSVByZJ9zfoJWo
cDkXI1RGFXIAavqbloUY7eE/UOk4DkHo6CEctlFDUNdsNJRpVsT/zWdKo3VBggDH
5avABvT3IO19id5IFJcZl3zKg6N01butCCqPkPDv/0GSqodWxeyYjF0o3jFzPCE8
QIJZahKz98qKo9ze4csN9D7boXl0Oj5ECqtM8oX3bsjw/GGe7pLinhBR8cypizF/
jwbhN8ztVQp7TbB0vvxQ29fkY/MipJVclb9lC4v3Y9FBwLxjIgg9Y+KarsT+Y/eM
OP7xkl+rMEKvRvK2ZdBVEBcG0FZQd4XPIG8zJjT3kp9p8nHkWEVCAKJT4jJ9nYGS
2iWxLqpUg0vKD5efRpTG4z1gW72sD5xyt6V+ld/gNHt96K3PHWEwxfYWpYreQgx0
0Cx/fkhSFFN23B5cG3bxXmqGyoKprfeDI2u7b/NpCQmhsxhbwbMUEtLMHGCk0tjS
UgHrKu92ISIDMjH6LW/CLjypkdT6sMLiKQs2YdizAwE/qZ2QbQV3+LqPRVLVwEGy
Mx7O3109YHQaVDu9rNAQBL9UAEjyOUEKoMpYSHHcaYUnEY0=
=OR1V
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '875af466-14e2-4ee2-a45d-ae6fcf797831',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//f47gO0MgR7jOyz1ypa+MjkTWoH+x6o4+w9vxEi6+BncL
KHWzFq5IAtbCg4PmlhOMTh0J7jqo3pW5Pt6v2f/WY39nFSWmP/0deM7inhBTuJWt
oSXkP0dr0FCGdihQBV7kFjS5cwInPXH8aaPse15bsvOGTH4vMYM8pniy5giI2yO0
SDbJE3G/1CZhwVJWYcIJyNhTSFuQFTg5ONiQBLTS54eJyU66HMdnXyi+Vcyzwklq
zZINHATyqafzH3vhpEnRWXPhw4QXWI1ZXEHZKplULk0n+m692apse60XytCLmoO0
LdfYGeqBWEzULdze4uOl6V1HyMJhrMR3YckokoYiETGW46E+KBC8Hl9vHjWe2pXu
u+qF6KP6s+sL74Z4qfGCCjWQCQRH+L0Ahd10h3hpWYwd29fgFvSXIg/zDlPBkVzp
JmM8X0yG29+Oz6GjdNnkFhjOnw8kRJfd85pOFfr2E7zUbfwCy0QuDoPv6GwI0APh
skeNFVB+IPZDdBW2SaEIbDl2xcts2C/EphgPpW5Z16m1Lpdf/xmXdiGC8Jalx19g
0w1RbISOQTyUMsMs9N+9F/MfPIqYcIJSP+6XxgvuLPy4VNJGnROjvaqPtu/MBxAH
n/Wxf6EPMC54w8tsnI39DhDhflLrO/KoLS+uuq9dKCj2ALbKlbekMc4CIYSpxynS
QwGPR1JjhvW3G3l8QMMi9FVUEI3lF+My1FDVDCjG6hW9WAZsc0bTifZC4ODUUScO
jKqAZJIxBd8OZfCNDdGBZn9pl8w=
=RkjV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '879d68e9-96c4-46cf-a513-faeb830ca798',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgApqzrCZ5lEs8Fqr31m85bVJCP4GiMZ12NnGI/T7H4dmRF
jMsf0sG/K4uXye8kkiGQuj+NidVE2xjuybs72QMAfPSRKvorwV7+HfdVThTHmXKi
2Drh+wn7e/f6I10g4hmsLdd0Bn5IgqzunrphPZuYCv5xu4yhxFdsvuTZleO1jU0m
0w4H5h2htnY6PFFSKhKJiMHCZyQ3tLF1vz1Ysjro39sFDw+MzT7kBAZAHz0mTQWF
Dp4kumPo3bnNm2gTy+fnBFUsaDVLUuD937ODb98I6row+gPksqHvurA3moD3m8uw
s87hb2zQOP5TpQd3QhcclQ66sNlwstDWwuC/7R7cBtJBAa6txRmJMAz4Ha7z+V/N
j99pMY1M5E4eDEQOWmq0/aYp0l90oPhIPl4Sek7BOCuQArwlL1vclasx3nWz3qbD
SKY=
=WXuE
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8871784b-f54d-4da7-a676-4ec89b211bb9',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//WUW2VnVGxglD5UrleTv6ic1x0ugg7zSOH2s3Ygn/1O19
Xl1MFpC5MMYnsRODud6HeP8romCMVr2Le9T6U73xH5L8SyEZ4QFn747DiSrik5am
qT7QTjNSUD/EM1BJxmeqaH+f4OzQApZK7WMNz2QenXQA9GmqTe+xd5h0gKVVOWM6
+u2mfa9V3jQhX1WmZ6OzRi4VW59PECBiTSBGPo+FSPRiXDog3TtG7yRBJa/d8toY
1b2HM29rKbM2QHfrXg3eQo0h88sfsoJK031ygoAMjPfS2qjQi1bpg+Of0ERgERgu
hqt3BRU647GhJhrsVdM5r1E+FM1ty91Ma5GyAgKBiYicBJL+R+Q6Ns2Q67tPg0pX
y6jIEL6vN4+6CZW4ukliYPmcrLLm7o4kBOM52DPaGz+V2F2PMMoalY5jkyM7GJ32
gOOAEHyV1295aHfFH/cvGRoTzZLvbsPeUJWn0eho65DQahDHHR69UioCvq/ikWgv
8XDEeYf8Qv4JS6CxowHkBj12PFaK4VGndwsS+PBbBX83KVA6yO8tI2Aclj7edTWb
EZTPKYOjfhLH1PQWaHlj6YqeFKkd/073Js3pGc8HjDDWQebLP8higTSNiSo9ZA6d
+2TV5fyo8XEnYKiWL7lnqK8sQcY1tNb7BjX2c0bkLoakce88FWmC7cC+C3Hja0fS
RQE8QG4QsiaB0MISeJxvxft95Oo8yLCmPv0cUA8PDeVUot175oc9Lf7t+ilpYP7l
oWjoAlVGGzA49haT6Oj8PENgPxzybQ==
=tJLw
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8882c4f2-1dad-4148-a3d9-746ee8912893',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAjEcMLunITBknBSijRKRuiSWiv3/unHP3UebWiskyY6Sm
wFTtWy6RsEl0SEWaTzbbjmMaoywlqPVV2SrNfj6PSmlHUYYHgUhBzRVJM+1HzQKm
LO6DVJUlfwsrILodOLgOTaC3JsxxveiyMu0kWnTcdSrUFmlhTb7aKsM1zb2SwpsO
sxD99U4C3cYAZrA/A+a76P+kc7VxegtpkRe3FKPa1IMKQ9r27BV9nj7ZGWbGVAFe
Ar3LV8cRQ4AKIylS80vlq0CjNaA5NDCCQZScRZ46jA+BOMXMLZK3wE3att1TkWOp
mmGD7mDSmjxfs3IKaqx5ZpKNThQqNH8WLLcQFnZEEBTb0oZ3iEnswvexhAFXCR8v
bbisY8qQ8/DhED+W66EhmvUAqMW5MoCHTpCRz1Dot0MkFWnxCQt7ThSzVEBfK7LA
NpxrE7CpjaqEkZmbRAhBOYZVMWaZXTZbhogR3LL2iUpYj2SyrXF/cfXUmPouHmWx
LeBaomw/8KmQk+wDoPFrFfXtlio8oAKudWMY2UEMd7f8ZLHonzqqdCXFEzQruvBZ
PTWiiPE6D/rMUDdza2slxOs/NzZtN3UbGWIDTpwSNKZP60K6Vhd21PKw2clkWsnC
iAwmjnNbINj7LmJlRFRiu7hjftgXzAb3HNPlwVjvQxPbF3n75iuyy0Hs5Rtm7yDS
QwE5Xwj34yJbEAFwCTPEMEL6c6WiR7kTMpAA2LC7NNa8K9SlZVe8CMAGGBu4g5w7
3bp+YNGLe+DwuuUQ9fmFO0cBKws=
=0LuK
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8bc16760-f442-4585-a572-09dfa123ba52',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAjX3d+veXd10Xmyv+Xa1oNGUkG+OrucoEY5PCuW/0L1TK
SIi/Cq2iirowGk1aJi8jF6tI8+MWIbkra39RkxjDvTQfHVyCd6lo+kUwn7hd4U0r
Xb9XaCnlZ00Dvfg/OnKNGsdu0Mfhe9ehALKK/yeTOmqf6u34TaxIMOWTpb98Yp2E
Fa9M4NqVI9DgIuGLvGo71UTj25tZiUj6c1HM04ZhgYGJUovAMGJ8nmEBz0WdoWE6
uIdZomvs2/RzV+R2XgVXUByc5/0DPYhWSxgJ2M5iOZ/8INgBLnBEJQFeiwmPm+yA
Vcyjfzc0yMbUZI06MWVpanwUKYonPcd/Hz/EX114DFGkmus+4zUk20qIU7ILo7N2
awI+5RrZO+yCf9IAlzdUFtWvVqzNf3oAYlh4efyRj2y+qKwcxcv3QmVPRvHOfK5f
KKpmnsHqxKct6eNgX2bi4SskvWeHctycBPUajnNTk460y2RA9ZkmlceK4u3k+Jvs
VNmDr3g2U7TvppKxYxeZ9BH1+Z3pmbGB54VAO1V/qoh4J6eLLN2zkcbFCK8LH4/5
Sn8u1bXxbiyq+jt7flG+N2mMysBvv+iB6hk93UEV6f81LE35Kqs28IOCP6sZhqV/
uSBjFD7benjzsL9t2y7Z1Tez7M8otPm0GGaiVGIlYWFqsthJYhUwyX/Pu41x2XDS
QAETdZoEDmUpeNd07can4rNK/pCYY9QjJO3s46ysxk5jxxplbLCuVarDAKF5jkjU
+d66/xCCFZWGbv3eLh2Jwh0=
=F//d
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8d767eac-6df7-4a27-ae2c-71456ed76801',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//bdxA4BSjtvgd1HgH48MdOJ7oTzoxJaMYa6/5YEZyAAAu
HxEY+BhPvO+fVWtTPUMmlovfYcBpSW/U+jum8GWCIvAARPqV13Op1RI+/3tTY+AG
9m1bj2g1x1qVTBM2PlJDKNknAvH4V3bVBSHqHtW7FLKm9uE5Kbk3fLGnNkzSzlL7
bRaXB7w5tP/y5rcr3Wogff4fcKE/TdkRIMDlAJ4B+VAMClW9WQK4+0ss5cMXZZ52
38yG5AUz72eAkjD5eZ9PA5IeXQuYYeP7hNRoJSjsoYLpM3Arc/TFUsMdQGrYkOvG
mdMfW5fr50pyJjsVtd1CrChwFuEzGdpAabHRqapnup/SOsjFjEtExj3LRIB/OJ+4
618bGqW3gSyOaVKG9Nv8T+v6Ggw+EIxVx1xs+wY2Oxunl5FjEbwlEA8lbwAWNKSz
nlLBq3/MMxreL/OmHecodF13M2Qhjf1fV9R2Dy3BeDsaZx5lrkbG+C3XaX5o38HO
Ffmx3gVjtvJPaF0Ej9uCVHeCKUDzOIVMae4xn9ItqEwjpiLhWDOfWI7DPCHTztZm
XPX4X6s5uh+VTd5PcPcnJk3R0jrRS/P3ExA7opMvPPMaVFgCgNgVH5Y40Z5KZYaC
+jZI2NBTijLmGRyZMtQHZIjEspUvHzRXKyyAB/0N8Dp4j2qMkXpYFzgEGwpFMOTS
QQHPCXjCv9qvZd3v9Xf87IXdzeqiSsayT82LQadOBLOpRlSn/F1tP2FMqBT03Ug0
ZrT2bk2DngqgPU1UvDeniJSF
=mK6e
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8e8e144c-4b7d-4e98-a322-ae4ddba568cf',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAt6eOysojhYwR5f2C0P5SMKzxkpDWNqJTu3n2cZfYYjmF
KmB6AYkrLd1UXmn5zAzUvFjMNK7fZ7/68ZQdbmJdQfpj8TLr4+xJalYEJLGhuMQs
fJtQrYHo5lAQW7VndxYNmnDcL6jvZfuMVbbKIe37xOigt8UnzJtFV0j2FrNKb6Wi
/Oj86Q/+T8bwP0Iq4KADU/HS3AMIc+0hpZp5NsskFtEl4Eu+qz4TeFVcwCJfsdB+
IjiCAOJ/fmbt/53oy7Bs+tA56mMgbzkX5YTech88e4aOB6r2DeTeMJwndcIdJXuC
8+WWdro2FOj3c4b6lUdi0QJlzL8MNz/wRQpu+F1+cpmLAIwiV+8iEEmaeFKkWzqX
224OMctjzYeBvl0FAby41oMlrf/1yrh1DiyNg1uJm02XVzOuGGQYUSI5lEVJnboU
Eie1YwEIJStQ41Li6lrJRvlbF/0rMlz6HcVmhX6vLV5ywoQN4lq9GOpAoFnLwv06
QRi8qxQJFY2MwqFxzFbSvo/5Yn+XUpkEdPYMqnFjuBmyA2/sATaXGpXDqAP0UNIC
OqLW1LPWgCXM2OppOoGZdM6G7GWEhoe+aOkhGKxLb2UdsNW7ymMVnH0t6GCc73cN
dAi0S1YX/pdm+hweqcK5JVmMeC5yqttaoN7WZBiyt40+/goeXppY4b3ose3FlBTS
QwEhRx/tT3CJgSzufTk+i2+HTWL6iS+8vq7LcTEPFamo6L5V3xYbf9IfBZkEZluh
zipMY29q8u4SQeEkE1K9kuGnGDY=
=Ld0x
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8f6b5302-d283-486b-af54-3b80e533f586',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAsdVx0T5XA1wFhkdclSAZ2vxnJo2nTKn1Ba2NBLOu9aqv
nNh3kf9RQyId94OhX7DT+qJKjtY3ZpaB+6WQAG2IjhNgO4pnv8h8v2W5SvBJovaH
EDlMpNkIoKXLyMlGS3EUjy1Dt214SmKl/EAbGpRl4uVZfSHLoRM2k9BnReHQ2szJ
a2nhsT9mhRpPpLBeqe4LNsefFu7YCOy8lthj6fbeaymmif0PqF0rz708p95y2ADs
0MT3c+sk77/N9542XjmFssz3y7rhNsjcdPNiSLBKjBX1oNle6qvNylseg5va5IPY
j9k6ITYgdbfwfgVB6EcdhtsCJzG5gcOhl1ZiVmm0VZmSjgxpZdYel5KxY1izACw3
OBGvTwzruEKF4OiVsivCAkySyf2Q+i5sZQXC4cK2ltWPbC9iFptDqW+t62me1hPp
35y32I0kWVFXAOG2GeOAcqUrJgXWbA8RVru9nB/fEbwCkD7gJSor/s1RP4e+ErOM
T0uZE8VXKb6fEFnHSi71j/0Mt2X099hkKpWhCjwqavrtbBE69P/hzxNCqltw2R97
YBCNHzUyKHbMVPBaskxB5Iwpjn1PRFWBv4vP1i080xtt5nc5ri3XmrApeb+0DdGP
T6rDf4krkGtz/f2bcdT32eOBQ32RDz9WlPJ3sZB4R1icRHgm7yBT4CQzu6J1oj7S
QQHxGsO4dUgrLE7Y9GW1VqmGN4DLTpPOquXHkTDV7lJZsKqjL+G4bHhZm66tyFM7
ZRoKVWzJzgz37xiOpUkrP9JC
=bnCf
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '90ee2f68-9339-40db-a35c-41b712360b4f',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//Y7QyeXzERF/64YLeTtmZwv3Hv//ws4xpHvmCGs2vJH4P
/zXoSKK/r9hmMd1PWSeCcHK47zR6KcNwYx8qMgCDuREAKCJI66A1tTIZXI6340+D
bKWiGFxeKprmieULpLwk07jIy3EbD+FylVXzmdhakB+ZChKFBA73lNQe1/Tdk0mr
E0Z6+VjESIBJPWWtPmxmUc3mmqZpOIHe8VmIHh+xeZgEdolr/e4JJjJBGS6nl5N4
wBQx8a/fhUW2m3HDboVAPUB7+cEpZ+lTzAoSpVtCzLe6dFIBkuW80F5QYRmYMO2Z
55lVTo7PBD4lWSSGw35MwvzTXkBXa0ni3MEiRIvBG3OUyG7hr4Ql9s/7qyN8Q0IS
xyeyCLJ45LGe9rWtQO4p+jMSaWHNEnXREUwmg3yX7LfG1VC+bM0oIfZod9tP7qjt
vb3qfDZLMu88y7C7EP0wMhFmDofCSnzCyi7Q1l7uh7XChoi2cjeseE33ajeg4/4s
N/Z8eV/1BU5c+8H6BUDgwTg1h/PmL2MfU+viXatxkbCM3hXDHxWZ1joIXJ8/9PS0
gRpZXmaM4VzrS1i+ZqhuXHE+ycA6Oj29smSZn8/6Abgdlj/YM0lL7VXyrNcXmbE7
eIiCskuJpDjoBOGp2MHj8B3scltUF1mZflCOA2y1cXFiLHfS81PpGJQWYa9hc1/S
QQEvss0He2IqkvERCpxTfd1RIBfmTb2YSKQff/YbfJy/XEMPNy2ZOi79EgA3KbQO
0CQ0K5+3R3KQHS8Zd86i86kP
=lXzT
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '94296ea8-b6a2-4621-a66a-d7d2681e1194',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/6AocH0auQxYgD1ERtkRE6CtOt16mWHtWG5jc+IJHV+wcl
H6RzvaG1We40sp05K/YAXQXePwvrIyCmwOaHRMoV2qhyZVns3/RYKzmLscucgQkw
S+RX+BelZczGIiDKM8ouIs1983bSDwuI5fv/WXDNm8tyqBl4pjPZX+bRxFcJHYEv
2xM/VkQYkTAaTN8+u7PNgPTC9rANnDPCT47n/DICVdo5E3MMNZ4pQmv9uHumFsxv
hye/j5S2Rjx0kwQFoFgNdj5DXMtWRh5CirdQZM+Aw+eqwMCKXMkYSQTuyzmpDK8c
roLpSgr+DW/mtBcgQ9VrUHB+AgDDDANI+Jy+rXQH3T2JJ902Sj4JZ/aXv2frFNU/
SblfHrxuS+pN3X3syk1KR8CIAgfs7xQIFrjyAzVV7D6zDfvW7sPrOt5jjsNm0E6a
jrUtsfV0Pz5fctpC8rytEZ+j3OStzERmCo1MQsiXfpMbbwHakYliEdafxuKaGywG
JYJitiAe4Rk3FA/+GSiJOdHQZs/MCRN5JOySWGTSfzCBYHc8SRTTgR6+8VQczkgc
f50oPaKlr7EO0nTPMp9Kv5gufWG2+rfH07TI98jiUVVwCGxIcim+jD8S6HC4GCYa
Dvr45oW3gibQdb7ymyZOXnYgB5OGY7husgqi6ir7O5pTFCRya+QD/IuGJUdAx73S
PgFCeCz3hWDaTX1R2wy1D7ys4zss3vbChNaQlwJOlWH52tu2b+2xWN550I3/oX7U
f3VDCRBD+a0Mr1/6G8nZ
=BMvP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '95255ecf-91bb-4247-aae7-2f413d7d5114',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAvZZdU1TBgKaS3Ci9qtheHV6nmNrU3rLWNI91R+SPrqWb
6LD6DHfVfAk39u2DrimdYRoIaVZrMl/V0nuIyOLOVQsUMG/cvODenHKRymdolvMC
63wty6krR3ERC15Dtd6zFRU2O2jH6U4fiwfv15i1sfpHVk06sXZL5JOkGMdgBZQa
YFVw9sLHS9qMp588M9Eg1pR2W64N190IACgkfqGYuOd6VZg5PgWBnmK2T8HDxeXk
fJRgm3G+i+lvBg7NqWMTeKCVj8v2tPXi89mqO5/b5wuGnYO9OgiC3z9qrD+dIbYM
2SFakgu8T+u3GzPbUP1Ban9nRpkFmeDNGncvSXM+6F8N0vSFXQqxZ5V+Ij6g4yhj
MDbKCo7+7Regbwfqq85GbcapOdBne8fvHi6163adnqj7GmWvG3VpgOCmqO1i73Y8
r90TLtrWIOuuzHCBXlKpwgX8VjZcg44S+EkYH1jVd9bo6pj6TSin1nI2sXOj8cdg
GGWJ1sg50aKyHeueXRaidQcEwH3OSS+ykSl5MA7dWO7s8wtxCRkru3JRGrQO+GTv
+DV0E7vy/QcVS999qg6SCUeB3fELof24DGWvEH2CXftMx5PP5YodafcfeaJena2N
+G8Nz8mMJrGiiu1DfCfprVvUKsO1hqQ7EEb+FgWFVPxFWoPZqrozyTe0pBKDkdbS
QQHUwNy73wJubCnr+c5OkhAiBDyDB1lGHEjy+8QwqD9vWVDDo4x+kkbFLhQqIfBx
RI1DaMd1x9Y+tLzoDsWNPqnc
=qJTd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9550f3b1-9e11-4c54-a919-2dfdf5e9832e',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9GhLlBsttr84ShI1L732FB3BrVP15rkVGUdasiHDRVgp6
K78hvgbCdQ48JtcNpntT+zXd44Q3G3XbLdJ2kZjrwF7KFCCHEfcy4bFpPZ/vP4+x
WgUFIdHeLUFkCNU6OeJbRYbkj7gUxxZOVhVXrdaiZvdd3BykYHd6W1CkhXvTk50D
c81/sgh8YSXEb4jLKbUcIRviRy6/fKKIs+LftD8+/+RBNR6rcKcSUOewP03q/hem
cfb/cBJ+d0Lv2d19k0rysB6m66WAp75lllvfdolwuNLxXu0a8inS9GD3HQzbdqEJ
ZPQ3uyTP35QZY0fEMKUbj1/BXscyWfh9ChaG8siJ6uKtT/CISftTUb0JFhr6o9TS
nwds6kwrrn9tCM6Kc4zjO+WgsGbRNofFTz+UHo115AyiHOhuaFB2fso4/jMAc3Yg
W7y7Gx2Lp2daQG5onfGN3YYUqySpT7CUttJWMbGHiFtmQw6opbfdoyCjOG2ohlbh
dOvWE6e/aBozLZrIZhQgsS5vvn9beoAQFb2J23um5hoqMKeYRpBKr07tlwKUU25Q
VU7gmS1KQ+YMz2GEoXBLekfXVxdWJtIo3kl1JbHlQXCTTCBBZlMuZ5JYEy5HZU4A
OVeYFjxVyXqQybzEQlxAAnOYT/nKmjQJm2x0xOAMajcT5DcVpv3p7j+a5VzwC+7S
QAGM6ByBwGhlI9im9QhwDZqYz0xrQdKD+o21kX3+SYMUa1Xmxx6refpCLbEKv7Gk
UbOlfmJHS8fBKmuIwf6u5SY=
=cPWF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9634b170-5473-49ac-aebb-fca8331d2200',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAtVklrjcS6+m61yMj0dl1SZJVLE/crBIReXw3/iSN2lME
PPYgkwN/DQAUjFSN0RrTX8x59hXb8r0PcEAsE1nJxmMnpYNAoHI78RCBxkvWW8tU
XiY8mD2Q6c7gzTBKSFgr4rbDGk5IC21eN0UkW9pdfjrprirG+zhzLJb3dcVjMlqh
bwPVPqOnyzkFrLyEVGtXh5vP5OZ72rhnyKIqBn7vMMPLtV8EeVP4ugPIGOtsO4Df
UFQ7xUaSUL33EryzdM2nr56pQqomB62cnEcWxge7ycTlVLK/F/Jm0edgKjVfBVVs
ZxbK3qysF6qVVS+3sbAU7q5thAcXF9fm97uXpyy0Qhd9VP/O+2OrKfr+JOEzrxQq
SqMZWbBFey4JEF4KjKxKgUNmehNNRCnoIKPgcbrG/QKzMB3Ep7UGyCjyRMikVmcg
4ucN36wOT9p00mFOCh3ktxKxb/g8wt9uzbVyVWwQKhw/4eY3jV9uUvvvTrjF3XDu
bUuzUZ3YR9qbfQYn+RKjbME8EoOKbf0HBa4sS9gxpl/VzmEVbGzk+H3FXkgx0l2h
ahPrUHo99ekhuYJrMUROTB0RqOf5eD6asfE/ALZyJkDkTNmS45+qWOuwmO5y1Lfm
zH4y8hgvJMbUxQj42dAwRJmO1jWdX9LOgFqdxPvMlUmmN8XIJtoXZPXonUFNdGvS
RAGug7lliixT1zITudOc+L0xkm6gQS8+7xOtnNKpUO/+pbydQ62KySLcBRGuRVpV
qt5wmV43hGagMJH7lTU1GFdncJJ5
=AHgz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '97f95dff-ea9f-43fd-a7d2-77696c2717a7',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8CeSh6l/UqDP+Txh+cN7HA7+FvmtTKneIQYWZbHZ5LcuI
Wm1Oqc9w22tHkCPov9TKF3HOC295gBXNzGklXOlhBLqJks84Ox7IJXB4ecgqn10q
tPUPGrAGhUQSHim/nYausRPfYooyN9WkHFHyo0rvoWv3h85S5SPev0aoRUkfePtr
3MZh33+vyh/inlxUFIYnrCazDS4K+33e09TEWZ7ytMm3/+ElSKY4Wkgn7b/6k+Vb
WdFel2qgRuFF9PwXdWgcQ2ub7aU3E1Lwi1KmCK9NDOUoFFllfLE5jw0kjU1aGc/T
KEfitnOAb2uo/FOoztMQ3iEj+S4z7jBODHWG3aEk0MnQiUo6sbI6rz9YGx+1F/Zq
kdl7kjKVfta4IbPKJspEBR2gDw4Jvkb5c4ih4i+B+NWgE+Y/8dtxirffUCqNdchl
VRoeHW/xqYXGfJlwKq+iycGmrf5CNz0uhvJv+3z/5fn6VBKb0rBx8hUhnEMBC/Ws
q9m37WKaNPH6zyn0rm+SQ14peEHhKzoOKR19yjU6BOg3PC1Hwb1wnZC3/9yOPPmv
JM9DMrC1QFMBVYDmxCYof7ihHq3Q+r/58agg362lEqo2V3T47q5PQRAPPjybIqgx
tglNzNXOGflEjw3RgdB6BcPGEf1y0kVrcxPhMiH0whZiutfz8VazjVByKR7IuN7S
QAHlvIml14rk+cctj80Bz8mbIFE73z2tQOVKLVQvgIiDO5h53bXvLRGQJOb0+Tw/
v6GNvkiB50jtW/QEZdW6/gQ=
=SF8f
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9a69f5f3-e022-429a-a7b4-0fbd2cae4d00',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+OO02Mgaohyo3yj6zOpw64EdX6sga8+Gtgl2zIHw79S5e
0UfLQqoBExM9jyuOMcnjJheI953I4qevK/DMRcFTn1i+keaaO9KZbuO0qM8Uf9H1
iZ++2fZoFAxxbAhJBLu7vuWEiTlJ7xFsHqk5YuJH9Ta+8+K8IDGeOkJoln+IK+oF
Qv1HJEoUxhTu/M01y2/o5xRy5EuybG4Lhtonz55H7VzaPnzntHjIp4RJN3FbSVnT
0C2ifCndf3FIGrZEiIOom19tupRtanswXYokM4PwYGp2aY48jLiNh9bEslUnw1PY
iiCqVJo7SPY4X3KT4htvCVCGU6E0IIWRk3XHWZwiKjFb4DXRKQ6jG0EoUFJwiM1q
iALdK70gX06aNl1p8dLtYjkLIicQu1ISGvebgPHF1AtFRd1gU3YTiF9RC/hiLQo2
6RT1kJlK39PO6YmswrWcm9OyM4SXm7y6TvmHEsVx/llHziCPEdyOMYWYss3db3oV
yVDo4r5l9lsvX6sPl+XhwVQMONVYxk77zniasZMzL2GttCUm2Nyni9UcruwMya38
WU+FA87EAWL6+yILa6BTh47jjL+EBzyisIuZvy9LvfKaUAjsySpVC9OuHTOIuvBD
57LTk7CvaKcebFiHCiTCAq/CRb1vKnJVE7laWWrEG6N3cx0Gb500soWah5rk6mjS
QAGME+2vdH005AFBt6Vuk0vZ8HZgguG0QK39at5G6tsw0pEN+0RXdYqkl/RwrkxC
xU4KImV3fgyQpUysa7PS3hM=
=IQP5
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9aa1092e-9ffb-4165-aa50-4ba5544fc620',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//cGpZBwQiafgc3z+IgJovRZWDpI/kmsvHw4r/1YT8lGj3
TG+m+skpPFaSwvU3HZVGLAbcN0nOuojwNmBtWZAJX6yeJFpEjaFd1PwJUjAw3pgJ
hPj4dAvYqqVOIhVr9FAKCV56i8bgh0UiXugAyjs6IFkRFuln9VepuukJmrnXu5yQ
wOVPMxpuE7J32T7dgXl/DIgDWCGis13mJzLiQwAghwGGAISZDB2YDFPeTJ9gWG4b
NsWSBmUy5CNjZMJ5t5R7hre7s6QN1MTfiAQaVVHGMm+sSk70/yQgRm8AdtL3RHBd
4KK6iRK9JIIThlvukYjoo4LqMV3rzf3JFqRPEis2A4/nMds5aqBitn1mTdu2L+Mj
Ah/qj/7kQFOQit6rJKIh1JkAZNkV20WKpitMm5iCOkmbpCEipiQCPT2YN5Yac4Tk
8VTyDmXmRRw51eQnpc/xBoHioRdGss02TfTCzwJlbV0iRDWTNC99QXDfjtk0IrRQ
9RieLlQGqkasPhLKhql4B522V/duARY3PiGIJ+Xc9LUXSxuvCkL1A48RspjGNvUf
j5vvWOFYK9dWs5USvPNNcsAynOUujfX7XM2z7UX+drEUkkd0KvSQP/yHbHnx517M
JEMxK8dGnxo+XK/6BTVGDlBCT01hB2Qtk60GyIj+fskO93lwlTTa8UrL/VkJOmzS
QwG7XuIG76Ji3S1r+IsGO4DS8mkzayDpO6A5MzhAq2ciyy4/2ZcUG6ny+fOtG00Z
H+LRzzP8fnlmLHKeLgX8HetfgkM=
=BuIH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9b5c0bb5-a402-4a75-aa1b-07060522b85c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAo/AaevGa+wD3NQZM94tCxvf7MApW1P3eUS+EOPfih1vR
waNjdzw+KSV23itMbge+DTQ5EMCrVEnHjEfv1aHcwrBHHKP8HRHIxezr4SGRku9S
5c0FNne3DHuYPUXNIAHvP+gQPX0nHySOjX8KiiHoeHuunF12cU2F1IZxhEeX0kdn
STalatyTrRuR5IxBIdhHUKNqMYUJ50dNNOsq9U+AyUsLMaMaAyoOa26ukuvEQsBo
mY/pVHbik7CiYVeoELncSzaXw5g7jZdZxlKNMhfPnUmLCeQCGoQQEirdZ2fzpER7
6Xp7S0VngQPr4b2RkgQkKkg8UTMoHh1c1QcVoIhD8w/v9OGMrq0Xc5/dbkah9YDE
2NPIFMPVdCjo65ysQFbnFuZFyxxVJQfXThOWgNL5XNz04EW6g44cCeRPNVvtq8yk
W8UDRYjYgs0xQW287wdnNxw9+f6aFWDV3XOGT4a3siC4RWaTrsTRxM/R2LabdgM2
S2AHUUhfkePGBPrAy+KdZIpYNL/KVPC5XqTLvWna3K+2NoGl9A3NO2AW1+fRpFn+
YeQQnekAq1giaY/Dwjro8jUE5M70nEZFWnJlWNoV3Sev4mH44RLh+/4BbFJCoe0D
WxSz0AKJ5JL4EbrUVLex+SVCxBska5pDV2Gme0uQGy3768cx18+emEHJYGaQLDzS
QQE6Sg3Y+8XXo5rx5/d/sSY/F+Cjdqx24azxpVjxZoVJEzWSVWa2D6aIN2fuEtiy
nWmRBPXsn8YkcQK1vCzknavx
=YDP/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9c1cda20-e383-4707-abdf-b32a5be74d03',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9FHm5wCn7/dV7lQIhUgp8ls57UdG2B0X4L2bfZOVBhR5h
iRDqcvT1cyC6EzKuCpwsQiEVWy651zIbUktY1jBB8dBiN0Kc247ydeSmbOTwz7mF
5+T1N2qDG/zRSNdWq006YPxRH+nd72bNPnbxS299fCt2/nPWVUKA61DcckPMxlKY
FzKw7cf0XGCSg2DELF0a8dpymshuLhyQxpE0Ox2wu3zEoNF/CBgklE9di8RSdXa3
G8o1s0RHTEy7X0JNn4fqJ5ajzWAxTjEAwi1MXJc922prFSYDZspEgIY8vrCbu8Ic
gPsWm282r1qh62GMN0ELPMTwLFbFsqtUqORSNx8/L3XC5eOzpu0QvJFn/bX8YqFD
WjdHL7TyoB0phHPlBqXbUQumxHsnG11DftMPBuZ+C4IOSDmNygBNm/rbLmzklR5k
KGg5IKwwQ6fEidbh49Zhk+eAD+O+MjUHH+PmfosHV9Ub6zVujMsNlm60VX5jB3u+
a6x50D+Thsks5IRIbtzjYr1O2vt1AxVeb56iJAV/d5akySKt8ZZy3tCisIl85h8u
PjlRtfjD0KRANg3mwGLVR82QXajdmJGdkiJfLq4G+Bf9jAxVQBvHJIIz1EUzTUxm
2eSZqFo4l3oVcTSUtn+Z0ILvION9t4BkOFy6HZFZPw3fzlGalnjCY7fk5oY4wRTS
RAGf0NOy0ZvGAvXy+MGrL4mAHiJX7TPgLPAcoKvF7P9aMZIq1cWBdj3bMipDYqge
ECrsYozqs6edyV6bptTwgBgaqe1U
=FpKy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9efa143d-5ba1-4129-a4c8-ff147df81669',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAzpyRmZ6/uYiQ5625g9aK+mIB5AN4SXBQJdUuO96Tezp5
Hpy6NrAViWBI39YcxvVUoncr5hnHYpQ40Mo2M0PSTL5g8CFqcqu1wdA3QKalOxk1
GKi7KRErfAnqVpbZOtswvmePvhACN7Kf6M5Q+NazcD9DU7ZsWnSs+0C3L21kuVp8
UgLKA/TDiFYYuzlCPvYL8/9Z2vRh61Z/s+D9HBTlZfOHCYmh0KxNPF/U5eRF65Fv
xd2bufATMwmpPQJaXde2x+gaS3Ol1M2ebqzmUGWgHyvh/yqXqGR5Vbx80QCrzeK/
TarUgyvux2SZ6CxbwzG3TJRgSebcSA7tkClTB5bUYtJDAd0Mh+u2e5crS+OirFZd
JZRPLzo4ekdERfsy+RwY8pTxYRlIIQNKLdLLYuDH0GJeRFBhcSiiG81gbs6SK5vk
rpK0Hw==
=LIto
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9f37110e-b390-4f01-aaa2-742a00995d0e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//dBnobmMBiZFujo453bJ2Y07i99R5XGV/2g2NEL6UZVI3
31uSsK+UGDDCVwtM1pRENknwLIfd2YcMVQ10zq2WGSVhdrWkmqp57xOvdMNPxjoC
DPoNPpaaF8tvLnbG5Rzv7wdHxMVKG8OMmyyXCKnUkwDuIdpoP9EW3h8tdDAzoGQu
O0zc219Hb2RTpg11+OMQEokqtE3xvo8R/Nks+QpHWFLAjFeufLwEwJ6pfn3h/lvY
U4nFvsJHXDuOneevYGDS2dTzAztNA4LDSbx5gfctuUkXGFx2Pa8kH976sUQxCz1q
Zbt8zPRQYc/5axXpUK1+au+VsBNsQAM1qNXxNg0K+Hd27JndstS7tO6h82JkYben
1VicZ2wPgXFv4GtMtwksWcHExqH5F71+ednvjxWhL/cIETmv2LJWadKH51DPzwy3
YHQy0fi/miDgBSY1M7Ho/zHC/Ll6larsYTvHcH8lfP2DHUu9ZoGt0D2PU0jbDna2
ck+7ChjH/sFqfThtwQrHJDhKrjznuiAXM2TRutHRaQuDp4VpXbM/hAHrkr/wFor9
DUrvyOzcrhYof0Ri5H9dQglJQB2GB2ACjnt3swyDNJLHXCjefY3zGKNhbj4gnD0Z
En90l4CE0N3Wz7fHLDBtCtLobFFdn93EW0BBF1fWF+6liTcoA7uldaFdNy+6AcvS
QAG4nnIiSmirFE4Y7GHRTo+ItOrKWUzrT2LsxIsiQ6ZJhd6MV34Ubu/+B2xLAWWO
rxdydFkI/0D8ZOB1XRMNw+c=
=XDVy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a04adffc-6e98-4e89-a00d-73e58408771d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Z6s92r/EevLYJchJmJ5HRnisY3YnMN6A0MXpZef/o0tk
jXEjTcxUbwJWnNbqWrqVUml+zCrVeIGy5huGflhHnSztJpJpVq/0AH/ab+jfyWIz
2auQKZj/lHJD96RMyaNsojcBNqy0nIWGra2WJBR95Vwt85iSDpl58pt5yo9hzBGC
Qp6P1FRzko7No9QJOIVJBjhcriepc3UWQy2CTR9lQct5CIHYhQ8B65ULxnrG/EXC
kVhrKoWuqdO0PX62pfD1Xfd/RYQ+AWEcMX6AJOUFj+CcMUlYhWy4B2Jj9RsleWYc
tAIMSepbT8iPcDeD0j+tkAKs784iIeL1J82F5DFx5pTUlFJeu1Mxoo1K3jkSD+Xt
yaVugsvhHKxRMQLQhRG6UTT9Y1wyfCWErfnCOxcKg/YmEpGMS75yeHBxCEbLHTRM
IsB0ROysPCzPP7exg1H+OCObgyhu4eTax9ng4WHq2UsXA9OtpA7FWuRAW9Om6d9F
sPvVzhNTuKwv8rwc+rDHIfWDDrPHzIE1A1FggeCR0dUzX5nBqhDcYo+rrLQ+aUzP
lBMiSXWR2haJI+z+Uagnf2eOJ11MLq1chgcOQMJgnU/655gNjgqYqQ/DF3dg9Vwm
Np5vC8rynb2Fqq6fK9OGCUhLlIJJfl+NIv3VG6Ukg1X9y92gFKJ+zlomf5/kc7rS
QQHP/D7y8SpRkF4iG1cFjdVh7/guWQvXI6FrNqEDDbq49yUEqvgNToNXkZWeDkaX
Qxp1v9U4ukfGICx0Zj/zkCCa
=PqGc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a2fd9cf2-d805-4164-ab74-4487f35ef033',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+PvEif6Ais8qydwvWFOXdvZs54ECqc32uco9933b00s9X
vi9T3m0tYXKMl+IX0DclnYyKJT+qhQvcVRunKpUMEcvREWIXE1pWyN40TQ/L5sf7
/TjokmuY5U6gONsjH+GgfKzVTdCh24oZaLhgh1uXtr81Vyg2hYbf6V8JOuJTZIv3
OuujePqF3TGibpoa9EOxELfAxhYYnsvaUKqh7ecEcQsRDRldh/i7ErI0c97gxE3Q
1soV4QhZGtII7SmWnkzCrs/ce8vJ4JC1kN0gu5GylEOYCswI8qrWuWYdbzOqWZCR
ZMVIPUqhfgDktyAgqQxuMCh7TbRthnvMGOqe0BZsqc3qVKXicKZFwVa/ylgOgNQg
lXmwf4uJPyHy7ZbayscXuWzSuFMqRbO0L8ie5zIr+OjspKcs+6r/f4g5VCFO9L4I
bI0xMGOxxB5i5OrqVxtf1dndtco3u9TVLm97IXF29IuZ1hZgFPdysqUXXhl8QbDT
+X4+uY+dQ+m2hwwCXvWv0q6WJrUrlaW5+OMB5Lc1LMV2EAlbZsCYjJHSG6WOKD9E
/ABSAcszY2fNXLD9zz3Bl3BYnAwhuM5mjtN9FYeghzlAq38XKr93Gwy+5wEhxCX+
9eYtbbetfqdou6i0tzRK7e4WM95TfdRMD+jY24YwAOoi/Tt1g5T6aixRYOPQhEnS
QwGg9UwbSKRyqLmsJy8aLo/rV6+K+A6lAK2kmiY24TDQWCgTBgTcN7i+joi70lo6
XJrw2GnCEJBBEFqbynEw3hjqbBQ=
=5iKX
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a45ba3e5-e18a-4236-a429-9a4c5f1ec850',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//UrQyLF/F8O1BNyC+2n/7W6dNcfbndcqY/nY2D1F8d34F
gIzrXnTMzJsfY8XadtG9mfSt+WrPeBlrq4PcJMQ1sm7q0MouQbK4AjlPosrHCDui
druHHCA1LsvwZLdULKn5Hc0RNV9/HEbtI/etFBQM2NZ295/mlStRHulQbqfWwZls
9yY03uCScz1/9pymkE99NqSsv6rxy6dor71s/c+aV4uxDTp5r5XQk5tugInUAFs2
4Rg8zfSyeaaIPtfKAH0KXP+i3HvKle5ld7lFpm/M8MNOQHyFFJobxmcMnr7gJgC7
qAI6pO9AL495Uev+R9SOMCm8UwW3uq5QXes6Z8iuGst4oZ4nWMkr2ceXsTyvr2wf
Vbb6PCUFUqxazyE0kxTGeV39b6fx6ZbWeFSvKihfH9BC78mDMFIWi51k41VPdRkA
MLl9bNFWFRWhnFhYlm33QUFWGfo5qch11QTXZCJGSp3G2txKBIKGmJ40iXj/EOfX
R1J0UocGCZE2HYIRLZoLPHaGiR0S9lvwE+yhEpBN6asdstUupFRq/z7+kdr+Ksnr
nukrhKC7c5whrmOx8hbsP5duVSOqOKBwhc2XVnUHAhrrVVCgcCy7+MyUTX8ZIHIg
S6D5c5fE4QsqZPhBqL9/zp9FdcG3ba2hW5s9lC1LrXXH0K6EkZafRXuLwprDC3zS
QQGTGUHjhpOamOJ+JL4x8hTRZIcmTQkL1xJ5wDMsjU8r15iGH4OR+a6IQsu3ypZ8
PcL7UaUhMQihQZLzTPk5Oxrw
=6JRg
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a5922341-0d51-437d-a61f-23024784ade2',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/c99wNkoLg6i5ao9ffkYYnId5nBgHsWASDypmYTulyb8s
IzIAMuFzYqHUmcBdzpCHNH/Z2QIpm6Xs2l3jIFzwRmpa73/f83zMKDiSXXiImCq5
C5ON4Ejs/+mqOxCnYlcH7+lGJO3/574q7LnTrm+jsM85XoJoFHNjvMZOX6AQPWy3
/+FpizqXHPW/PYopkYergFR8B4ag4OMQe+DBy9Rhf7aQIzgLuFHO1t5FyQhKxfSP
hwZ3V2x76wq4MRk6QqLnSRNw5rRnpwPOzB3ConBOUXnC749oeq7Z2Wiyh7TI7HmT
HepZPdnv7FKTbuFlNDZ6Xqs2PSfIELjZWkyi7IqqcdJBAWmVvMBX1MDK5/2p4K6f
DvySDy0NxRh+Ifxv39QgVTZFJ3CBeo+O3wO3bkmLR6MrPYz+oU0TZIP17Ve06yIp
m7s=
=uI43
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a6e850d7-7a2d-4741-abaf-0ba21a70a480',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+P6qGb1ip2VS1ThUL1kpR3yOjNpYmXg28lGItc9CuPEFY
UKDziehBgudUUw/Q67W8ery1KYlS5JybmyXKU7nzlXxae1QFBQpBBAwlxDzSnWj+
vpUn9EPpQt2akVCEJpRoohIeQLTjXWuTDRXlSwbDxQetrQdGF0RVP0mfZHaIewIL
hozSh9P+4WvIJkOl8ouUC1731vtAY0aJq8GHe34U3355cJ4N6DLsCI7nAFOsDc4S
7f08hgPPQUU9i+bkDY2dUAdkAjOKIqCIRTdr3V7A4zSd2kdarAMXd/d/mL6F/D/j
+N3e0YJY33f+8DzzazRe4AwkcvErIR95/CVHithH/s3KTBhzZQFBrlTbKQ/ZTWcX
xggyNsP+IdCUlw7KS3mDTZ01Os+qMGTKaRm1oZ59dma8lc78JrYfIeDU3rlA3yYj
3VdHumTM+zb0IlTFy/mPHeIFFt2YdhzPPBh2+b7ea+WJUX17rYavKuLKLHyp3h3p
a+RojsCs6DaipPPaixKsp9Rhl4OFAGnE/MtrEbT07BWloOKVKNqF8AJS7730IYMI
F3OcxBUnD9bzgOSS4cPLJz6Azett+nIehPvtFuUNXtLQ1ANdZNNoWFsNlJhSJlpc
AmEqLH5fVA4OycyFUDtLkORG/sCj87VH8TVWwzUljKSdYguEiJMrZg5qsQzirfjS
QQE0l7ziIuTylkXkjCUCNzNPHoHe8fN2nJEE6iQYbcUmMDJ+lXRVfjOPSAC76RM+
wlyCTJVSJxi5dGodvf1vR2ek
=KasY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a83ce3e9-a1b9-4602-a77b-10aaa5e9298d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9FADjTt/EwWTtvxluToaOQpeZWK8azspI7FZLu4Midqr1
QV5rnrFfimzzQ1GB9Dn268hRpdjTKh4ysXlJrHbOwZEGorZkK8qLxjzfw15ZvwPE
lGexK8mvN/YDWSumGop8IENc/YXrjBy1h7eC02I6xAGLB2HNL5riL3F05U8zM4Aq
98198YC+qbyjNLbN7LhlGhav/ICm+NfJlSafvGG5CB7ETNtGgQJOobq+8/iHnCuH
VR2G4Y3+NJOoIjs6y//u20MJHzlZxjCrnLOhVBtADY2DdeoxIrfADJ1ADImwAZOQ
SBI2sjiZOuoPXzyyflABADpEG//jYTM/Aye1b7eXbPrM4XLHai55yYf9TrfByt66
LfhR9B+ahFil6fKePom3EnKK0eA1enwm21SXu4UFDK/j0yV9hLEWroun/3tG9Y1H
s9a8dA08rc9pwOKxG8q6oEZ1YpMeHaaPHa7XVslCTBOw21eavuwhgMTfQWaD+i6q
k6aX29xMpjk3vPP+LuicZN0JFXRgPs35WDifkfI/6rdjeYctafj32C0GtiX4ZFGr
uFPDAoIb6gEOGHQZ0nF5xZmY5Jz6LpCsCufECuPV8k88V4jUYBLPtJqOjCl+1oyk
E1t27FhiQuEIRCc2cFAn3KYNQ+z3pPoFlYMf15CJca90Edm2kLgV6Cl/5FPD/TTS
QQHqyaUYfbj12F/aK56PJXvuXErvF/Ki4Ni+A3sVVx+3VC46obU10Sx2Pp+e+rwG
jSxto6fvjBVLCH4fOJS9ySCo
=GWvq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aa40a63b-ec40-4af5-a00a-68d6698cfe9c',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9GjGKQb9v9Wa4dp5Fco9kvZe3NxSXo3cULQkiOawyGj/7
6T0Ht8T7AeTFoz0xO2enXxKsJxB0NgFI1kA3gcyJaiRV7K+/jpN2x9NAXSSrX5jB
PogG8TMwqKg8X1OxWHVjF06J4luIDmOS5GbQxYNZ4+P1R1POW2U4f9X5I+pMbHBg
ikHscjqjcQmx1rz/jGYmqy8mJkmqfZodYIqCWFznLFzYJcfCjVzaiIHV3UoAiV8S
Zo/iNRx9SpIFSNRlhsSyE/ZnUT8XoJC9l3S1bAenfLPsdzQpQTsldtLoRJtAY640
Yvth9IvxpourJhEbD4tXPTn5eMnMCyUZgp9VN0+aCh0+FsSOOJTSMm5aVrvobN2P
PwXmVRorcPJciboR0747RUO/CM+xS8vNGIizPjNUqID4S4QvON9jAiIsSMaQasDJ
7sW4caLCuOLjkp+g7yS9IcYgYaFX/RyVPOlBPE7sPSI/U54qyP6hhcp0qxH11ZX3
fFAU0o/p0Ce3doU66xIUnqhPiqC8k7di/7rZ4cSjVIhLSAXjhDIaQywHKT3NWBQd
SISgzZ6+EbgznbtLinybaa63baVSwrYXUTzdjJSZ2qejTJr8o29rseswDHU2ss5y
1/k5G7ZAcYD2I+DFxYMbT3gUlhxUgbkLBjJ9xzPN7GBvsSN9z+1slDA7QoQTOmHS
QwFKeCrZHL2fEBAYnKcUsDAWqDqpcD952+HRo1trEEUqf/Cf+gFEqD7ibwAdrhlb
4eD5d2G/Fl6GjZ7lIuoOhHwCWPI=
=dmi3
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ac169752-2602-4f48-a3fe-ffe5e8a7db4d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//TsDV4ejWU1gE3HCgR9QA1I0aGeRRnNGS0b55QdOtvI7G
9wh/r8fskVui0xybKJlcuz1cu+siuF0iOGo4w6xKQy27We5G/cx1GCX9vqTp8E6t
YTdU9cBS6EfFmRfrE/ibZX+VBHjvvrNgTg+OjofHDJ7i+60/LxstlvSj1hwP5oIO
YhHAK09lE/v8FghdqVBtxLRCVeC5yHHrMnp2pYz8pSVoLQUsqsrsUXx0I7VHPeqw
XMVG4HG6SQgtUTACidpThcPX4DOwFMnZZ64JkhV8w2P4RW6IRZ+rRdCGAx2/b31H
hucGKTaHB3xfoFWf3bFpG84Bv4SV89+zkVuqCeM2FUQ/TV4M4RhGjlEwOL9hziGN
Vx1NlPAgd5DCa79XMhZhXdIBK2pEtjodhoAMfJg2RXIqClwMBcCOS561b2wjRxmH
rti3Bhd1ooEuJ9f4otj4UxyuefijPnKYNlavSfG9xdW5OAJaUvytPp+E3imibgUw
cASFxuNNwMHP+kpfo0JkXYYjDzS/4wijFEhvWq2UXpEQrcj8IELpznMTJhWSQeTU
p2KTc1MoGBiQy0VmffbsIElyvrOAhek9WJbDg8LCT3PgrMDeRWifOlW428jUZJe6
dChlS726H/4EE2iPTfuCWshpoUX3XgWwKZxUlDFQV0hPv9wwv3uJ35PucNjoYaTS
QQEPvLb4XloFceyIBXQjcbnA7XsuJx+tmsl5RDyMXlZzW5sxOgIx5Ak9spDYs7ic
LVF0i2HdJtiEA8V0NZM0sPWD
=29uj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b1000e4e-2903-4d1b-a403-eaaeb3ad6986',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAnx4clYzPRsDjUNOUROXlDwRj3Iq/mPhC4XjFNNMjnOhH
Wa4ONwTYLMVdqpdKkRKDVD7WH20fTovxwPZSV+1X405H2GCiuAITLRl4LUXDhE3e
m359Vp46wH+8e5ClzCdIikJ9m+hdtegnynml0blJ8Xd+nRe0kKsE8O2gAkIDppUr
g8mt4FtsTyWQAiwTbvFPS4MiNHbJon3rIJPPpqK0tFhf2DMUXl4/sWMFS2HX9eKc
QVW8uJXcY0EkFseWctxQagyRpG7prwUddpdHe1Xd08kJXMHW8Z+R2wrmc7FLKCGv
p21cacdo03o2NA8VQbDOBZyxf9A6XfInhlFvoN9ampT52Jy8xpYFTBV0ZA3H19d6
Nza3TaDCYQP0vo+YkV1pbrVO/15ONcaggffNXo1Xn14czgUOX+NwJdjSvvDoynF7
suC5th6ppy57AphDLZfTqe7NP2IS7gTwGHdoqJBn3+nT39AZGAjge9QjJs+eD0LH
5GDkmvhqVHd8eX0PR6CGV5K6ILcjle7eTxztnVRhwWCedw0Z0BzfJXqXWDNQqvEa
OzluD00OZAfrz3k6tl/Xv4YqUTkfLTo9xtsJyXzyXe/uBVLaq3I0fg8D8fPz5eRw
RGjPdGURUzWj7nv1B8PbAsoT95qFw/TSt2ulbboZQQCUycwI1UNtz6jaPuz7rM/S
RAH+fXX2msZc2xxzKfon8BMAqhvgH5b1D8x5IkopiaYrauspHyvrVjgoOUiGL+uy
0DIyXR09KQ6RJGRRZk8GbzZOqvC6
=bhxz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b18d45a1-8923-4781-ad20-bba2ed9da5fa',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//ZMrpICmEBvn4ZELvbC9LQtyZCOWmdCDsdN1hPp5w3FOp
2wx6zVPyIt4RSRbCMl5vGFO3/FCFMwXC/abJSTL6kvQQhyZ6Lp1Si7Bt9R6JJ15J
K1/GGTnJpK/E9VOD2j8s/CAse4eoHD46GWwodht7GNpxNfa0RJOj2QJRn+GeXSIQ
ugVJF0/lZySDNhkpArMwU1nsUuyjKVnkmoxnx5mLEKkfwkFr3Jw+5yOZt6l5vOZh
+dx0+8BDdz6fULaTxpiJDGD8377ZP28fy7Y918R+nJZvQb198t11ozQswGRkNaME
4pMYkApt5V2psNixmWVVPldL69RgPiiaPKAK1vsu3ebtuhWIkKFoJsUFoxRrqdpK
JUhzOIY5lJxd6PgdKEQG0JEFLDcRrhitj6rkEGvRk+pzqqvtiRxo1qlZ+YXwkvhe
w0Z2BPN3/cX5tZuicCgBMd3EMwST+mF48/CSB6/D3uSlAaK7h6MSq0k81ZWscv5M
skADrAM4/R5bJmOCjzcYV6/eU8MW63k3UxqULbTlyZPJE68siNuyU04XbaGH63Na
JDm1UGSi2lMKdm6KXAngxoQiuKhDXOT7fUwUJKKI1+/4J3FuC3Jrz53ih94+Dfq1
b7pIMQJEw+yIGUia69mN1BFJnrnb29JgdqrQ1df4U6M2CJBu1yqCriApOKFRn6HS
QQF1PeNQBg8bvkXLk4139p65wRKK+dcV0BOxSDgr1f6UUqe7RPaubxt3dwlSZETi
6zM5ffgSb9XaQLtdawxWyFIt
=+8pp
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b1ec53d1-7f84-48af-a79a-b219ec08d783',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9EDhQVmxY8Zu9K++EWanIy+PA8vIRWHexjGnIRrpKZGUf
pwPAk5QoBGzEkuhy/idsNsy0mYFiLBrnOkgurdCUiqqtbH1XdZ3MEFZH989LHFEH
JI/mCMJmHL6IU5Ad6Fdm9mKdvDjoACU1dHkUF+SO0u1sI1pd/qJXZ1/16sN+CLxr
SAiDSQYU0rVDMtfdi1RvpDLxX+tu1z9JeeBqxip7TVDdVkEz8WOKgM9gq0TLYg3N
4oEf8nasvQoG6GLHpNgt2ewJo2HWvIeF43M88clGpCe98TzFIgwE9muWqJXIhtGz
gYNId5+GTJEk0oBx+SZ2aiqIaGpXlIbemS26zDnBDO8I8jK+JUXweuKeo/YTnDkT
6CG5IBvk3B7GvooLnwyGjV/npls4qq2D+bHKQzCCCBlMoLz2+nshQEqRbCn/nfIV
4PiIxWZ/9vjAqiluWlFdJnJpIN1txd5ujpbyvweXl0hFEcqja9bU3+VjH8Fwol3c
olEIpKrG4ctniDV516s9jaMIBZZAoq0Wew3YRgQmwbYtukp8T2UhK48o1QTzvHpv
Nt2ijyuBlN0CEuwDGLhH6BeXxuoLUd0YJsuj6hNKNq5lI9SQytqQPrtyE68/8DAU
EiEIfyWvb2tPXciQ+dJ4YVL3GQCGkDu88W+wIBxt3vjd6bZSP42z8kK2ZNBDFULS
QQG7R+GPBeJLaYsESHcMbkmq5dyx8JBlQM0eGoWJICTmf6LTX0gvzRbwxvADStwm
gJ0BqvO66MX5FSAEmBKj8lUL
=LMAr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b2d981fe-3d42-4fc7-a598-4eb8af3df9d4',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8C68t7g7aK8mlTWEkhoxCG2m1ojCky8/i5qhmiQY2rDPZ
kOA++Y6dgNXUjVfctRc9QOicy8FgVrjWPsyHFWO1sWZaHcCuMtdqRSnVDLoql/r9
yEM97rf8FjGoF6pTReR0w8FqPFE0+h+iwdWW74DoFah7AqxMryjdsVmJ4y2A9Nl1
8zcPvXnCTSizFu0qah2EUhOBlfIR/qo97GY6HH0ceyyZjKpyEBQtmXwQYLHNzq/5
10Jr7H9P2/Cke/UdTEpa0uzmSsepBPGmGUwb26uC3hDVS3X8ReZ9IM5l2YxTRD1/
rNy/2LM6aTsDap5E/gYSM7161pYIyvgbATZAsnqlSscGjAv6iDbFjS0ousS/vRAw
EjdRLbjaDmV1K509QgKU75erXwiQrzBCPemiaY5l712RExbzNlbjK+cpqg+2E4Bo
5aJfpDQCU5U1M0UvDrbQcqgtTOOrC/7XAGdYQUNlilf58A8WIovnazr94ceEsyLS
0tkNV0sJdYKYUeEmOqxGJ6YiwpHW5IT1e2W1GASrphXDRZBcLy4TSvoBCAebbEZ9
ngu+xLkk1zfwBObbwnIsPM/IBZLDhywzCEKjrMgkWIAgqLud+u5aN8ecwdi1VLvk
sBoeO6phmLKLNfVhE14Q1WTMDEci9iTs5n0xwqGjAeuvVdd1QRoHL5zfdsdW4XTS
QQGkiLlESD+i7MZBAYI2AJoFR3fOXdTPIHR0UJE8Y6+dmhZVieG98/IUNSFjQ45N
r8KVqQFHgFN4bSrjS4J3qHTz
=L8BO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b317d629-2e06-485a-a855-676c63b88d57',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//aDG+ZD/4D8n4A2DYhKuxfyG3584r2RnNuV8K33PKQ8f8
LrdzB+zfsVu61XadZ+ib9tjwGA3YTfYCo3I4Vwhbu2X4GZTUi40UP8yELN44dixf
EVwnH1pdkfzmDZgXyWPY2RWmeFdPS8g8rBdFaT3tk8qRFtFug0R9bW+5OlfMCBkw
kTWKvZ1C2/M9V40k01ME7ykpVHTT/Rji+Yrt34KC4ahtEFlywQ3W8q6GEOLLEV2E
AMHhMVrCjILCCifgxsiAc/VPS4I6urVu2nyQYm2vy6vI03K4xMg29G8kB7DqX5TT
cB7Hiw85yon8GPvSWf6P2B3aPZp2j7H9Ed420kDhZ/Du52qwbsu+zbGORhY5M+cD
+HhPlJW8U2a6oyvog1KyWNs6lQMYYTw3FvvlQepaW+RrpFdJnvV7nz/eSGIw8nTo
iGM/D8EIEeIEh2j/tnoZaVFgTjwolmkg2Ko4ekEz1fzGaC/gYUH9awNsKUmsK1Eq
9qWkjXFgPgxZIpo4WefvMJh2fUQJcJ9s+68mmNexn9zAbFzkxBPUOrQhT0I1wQ8Q
8j/AfV30Pu1CX7qSLG1YuWZiw2vGfAZP3hWpeEREKc6Pxy9wNn91VZhYUW4erMid
ZToTKjW338lCm0gQk1Lj5ebR7+liYWvUlqh5tuOy143IkdFp5pv5uQGrEsD0JA7S
QQGZdEr5CpMYyWYsb5sMTdQ4gL6PwpX1A4yK0Z96+RMDU/nDLFsLZn+3lwUrlw/z
8LOlYYu1a3GebAB6+yr6X74S
=6Ezm
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b4c111a3-3777-4811-a650-7242ea176431',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAxdz4CZimdg2ahfQgsbgftpn25xe6f8j5KW3QLFpr0n8/
FEvbcOs+k2lTkWfzb5zcFJnEIjOmiZ0cJW+FigEHQ065IHDJ2E0peCRXnOld7qC9
D0LaYZSMBKaOLxi9YxqQ2lqlhSjoml7LUaM/t9R5fFxGWOq2txEoIoT+CiOf0Uyf
QfVfCrTIsRHTcsRTVw4s7k6u4qZnQhWr0NvnJhHYX/ONaCfxOxYFpG5YBaWapxX3
9abdeL6JQvFla3rmuFPWx9wpWdA5PQFj9mSqttApA90PHvsIi5IjeMGWxvqqWkBO
kUutPU/oMLm0XuQMw19aoA0gBqIg6xDKoD+Fnkr989JEAfDv7/5Rnvak4LLRan9j
yeefS2Wh7XjiuMpjj9LpQrBp1at9pr7OKqMv3Ei0YCryID+op6NI1gtA2QcZVzqo
Tfs3O+Q=
=+eWG
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b5b08054-008c-4df4-a9ae-56a868158a9c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RBs5cF/BvnYhLK1HLFrzUoRdtS3ifxBTglaXSFsyJyjg
m4LypflXSIUyRCzE4uIidxJZQFf+f2DytzSOoQWfdW3AufGiq9vyriZYYUYUJkiE
Ri+Jxb6WTGHgc1GTzWgDRL7QNK0bew/6QNJnAQ/Db+cBzIAg/Un5PsfjGfSPu0eW
1Za9l4j4OcAMpmPoYCotEomYX4A0lDfE8aqCZrrsyiExceNGNma21mWkfg1iq2NN
eUfJ7ezVEJEzc8nmKw3LKFKVpHBXf1eh8sQZ5mPMjmnUMFI6JEYbELbqcKbjQ6aS
VyThXfNzftnRkfHROj/76W2qP7R55Zd+28h3RtOBOOoakKiLx5ZwC+EWIcetFsN9
CJe502DMT9+M8M3OmIzOIcRWMhd9QMUCNDzmXx1Um+OPdVJCBaTnUtrfanhktOZ5
Df0AgwEFCnLGasA3C1mUwkuxmiqIOV2QPfJ7K7IgYy0a5TT0qOuNnr9uhkEITUk8
YAIoFJf8KuGNncZ1Z+pYi+C9fU0pOKIzjgF+KfB7s5QqGTNH6zZB81gucvee6rDu
iwldNv/5lVSWPSU/LLzpvMXS6nV8F+RFqAH29z3FKves4OYGuUj2m+BUMJwgmlMF
Z5q3aP9WD1644MR4nWIzX/0TRmVvkWNKIXzQ/pAW5AuhrulCJV2luvc568xVdHzS
QQGQgdfWDXoxrj2iKGjRu359DjTzN5ogH4Ofiqbp6hZjDJ+ReRu8D+G66pyqfICr
EkB1JZzLto9JHG5bx1QeiX9w
=QnQx
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b8d04a0d-007c-4a61-aec6-8b1e29b90a20',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//WibSFYucdXKxEqBAAMqVoxlN4+/E7dErj0XhZwK0q8aK
NKlT/S/5eWgseYEG/TlwJI88sWmdBuVHyJUrDUlZWeaRm8p5YfGgu5Xfkx2NVAhJ
yidXuCA7I4/Pxsb+AgHk42OloySl2200CdbNipPJytQeFfI7Sg+kz83AM6gn/gtK
+Xrk/xcZ/Nijhe+4NmcdOf7PguNxdi13nWQvC5OZS2Yr5OpGTCbxdhfT77emgRIQ
JgKSoOy8q7kgM/CUNc6q19rQk3jWZUioQbj9CHg1LD+13fNgNwexH9WLjSPS5MvD
8AdeCeF24d+o7TcgZ0C0jkPmg8gfxWe3DUB5INpxubrVSYZLH7wBn8AGsu+BfXwW
o8vIZTPJ1tl7YqIJGLbiDk46XJsaRYdrkUUc+N9Edqz1aP/FmAp1SgrYeR+lEIF5
/P6z6PHpD74C8P60Ta8u3PjKG+axOu7UqB1eBfX7wP2InANFitQKeENgV/kOBANY
4CfsgNSIZifaJxcmXlyU3/B6T4zs5o9ceXsiBMqxUnoqRublta/n73005CGEk+bS
uODjl/ANBskRy9g1YAyMrvhO8bnw88pSQb5cO6qNvHplsJTgouk3VMNzDxSVsvLG
moHYlB/nWLhnf/z72M/TwJWdeBA8RCslNl3ECa5qT2Iz1pMfjM72yjSOHYd/PJPS
QwHB86yWAktvDPES7t5p/frToNSEHCEqVGZ+m3VuICWV91McrKic5aPCke3/QhHj
2IuDTt9PEs4lRBfC9TuxLt3Of1o=
=ICQz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ba4c8b83-67c1-445b-a52d-3f6c0a17d767',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8DpjHU24WncAbCm8Gen6B1Zm2UI49HzZu9k3wrbkHfs0v
izuU/x+4eD+hXfswAQzMzJN9VQmg3kWWmu1/uMiAONug3mv1/rJQOGxZP4FLSha+
0uBhVkgDb+6pWTkVEquMcuGlL3lJMf6CXVATpdcoBFfnt9PF08liDhO2+ZZ0oxY8
bxvkom6YFSq1OPQdxs6dVzgCpATb5R8IX+DPuTQFVSmyXmkuTGKrc5VfbDOxUDYX
5G1839Ku9vufLtfOwSFfHPW1EiKynIGXpL5SgggcNqAsp2w777GP+Uk4lCS5rFTp
8x1uZPymmH47i7D30G2EMjmNXeWmEwDbViY299McI10/EylR775mbW/2t/sweqeS
5aemWZTB2Abd0ymqM61pBAdgN/lHCkKFVznbTZFi/6cJWJM3cLnZ4Kns2PYrmLDX
W8ww2/XFiyv/zkqlaCCL+oVm6SoXHhvq85NhVJVJxgc2e7aWSFJinLMPOtDgglPn
oLbYU8LoHkHRRByDrlP+brIIm+zDF2iCDgKZ+SaFfTmRJRTNbDz4TptotJzyRBUo
KkU+ykILLnrINbvTX/Tb2+MltKSOTprXzcmOaGultcqHRP1sOBeAr1We/9lZ64uG
T80vakEcMjnB4waeBrQ4on/SdE8jw2pBHIlsCLGj+b8ZmCTNhBCoQhfiyB+ukjfS
QwHsUUVw8vbjYuOZtWKlz+ByAC0Alj4SzYnafnZhD/Dc9v/LJNnHTX6Ud46Ru4S7
Ii0z/ZwTmfjUJYtWBeo2EP9KLJA=
=KB9/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bb7b58a2-5ef5-4b9d-aceb-57ded5403f31',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+OfcCbCmj5o6cqCY7Obp2rxTBtsf112LabEjwBne0Pn0x
jM8DuraO5x3tXumee8GWwnDTyLBx6s0cLugPZaxIEOCgj+bLLUdGla7gtlyR12d5
YIUIgARAuukGuJfryRXSrsTjvb6tTkDe+pd+/5QVXK5PXc4LKzh7hcBs6mKNJy/C
QWWNAufadUQv+W9pdemDY8/DPt2salDd4BHAiJATX+fpdZXA9OysC+5RywlhzzM7
kzrwC1N/TFFSAKpQ/j3kGYIGAZVsHbtlk9xKaxsCZp/duKaHnmlOsiFAMrDx+sw0
gB0yY3yJkXYjVAzAxPI32t6c3UQ7QK2zpkF+PZPNYO6iaP5ldXtQ9dJgPKCddVqp
hz2w4H9oy6j4xbLPMzB9KE0lBURFemdbvV+yKCjAhDNhzI/z1+MDlcamvNjOJXeL
IbyUIzBIfjYuUBMT5fTMIrpkXxT9Yb1ZQ/IPxVs/F5h9mf8FQ8bJSw0MKueQMOLA
p089iiJ6Wq5DHmvUH6hjt+Zzo1VaGYo01M/a3K1lip6ZwXhGA1GCFN5a0cLoBZ5m
XZ7Cy+jZk785pgM7yuBId019w3Fe9TyhQvxbCFb54m5ZXtiQkzrS3uogjPxBXKX3
QuhI4X4NmzlRbwKpNVOdJpSVDItykEdAr38qhjVNkqsVQVQrenuqznbEJa2B5UvS
QQFBzvlQXUBTPbn9t6HxZZCIT9ikxLw/tkrxuqePSTflyAlX47aLlgYovkV1zfhc
7tNczKgt2S4j0kErJYliIskE
=l81C
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bc414548-3473-4588-ae8b-1e1b6f1f1aa6',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//TTJKlXdrAAuZON9VeW7Z3cC5eGrDMazFlA3UOcInigyX
+IPDn5jnXzt/qWM+w7n5IV5Wrn1uTHAI6zRcdGvE6qbl8k5C551EnguB19TQRGNA
fajZq5nvVw2N+n9WSsrX/oCr3vK451lUPFpQE6XvEvL+xUVtm1YJ1JgClGYkks9I
6mO3dvrE4mzgjssVIVvBH0VJXxbOUaPw6KH3nFZg8TWjdlyY96gHcapc97JulDTe
78hZXL3hxo7yMB3t8C7EtWW781hZ2Y0TqTRtT/MtUZ4WwA7X8EntqrOgDUnCBQjK
H7GhQXME7mc04Gtz9Y+i8lx7arY/nLSJgoNKM10PfSQT4zua+iT/bqx1F2+03IWk
HTKjvL9d6TyVLACqCcP1sVGEgki1f4BeCQNM72x0dmG69Dc1OcAtnxEqToOX8JLj
LsfaraNBZMW9Cunj7UBzQfOM/b3mNRuZLM5cXTlh88z5cniP/Lnq0Xuo6zZ3TPrU
c7wZhzVrVtNSI8YuWx8Nvv81sQgzoBFN9Otkq3uQwTDTteFldiD+it/GIisNn8N5
dWd+dF0mQhWUFtmXi4AW479QkFClxuS7a/D5BV01aoGjQrscdat6mqOChWcU2agH
mUnMfnN9njYjjLi9J9qj9HiA25CklTB/2JvY/8oJ0y+4SFrZ6MhP250IU3DfIcbS
QQFhfatkAgLyO458gOpFqOq1G2EaWnOliMmqsGpRgkY9ryXGEKBZQgWKOYkyCjYn
vd3G8tYMkReI6kPKf824bz5k
=WWjl
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bf24f17e-03f6-40eb-af14-6f117a98c30c',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAg4eYCgHK2hLk8iy0jy6xMI11IPmfFEXzXbHBkLtIWP8Q
7qwA8/RnXKZ+2GhZ1vO0L7Ox9RkwvJIqEyk8UVJqcJk/EtOW4OkAN6GXQsw2iIud
uPMRlWKcOMhIavd+DnaRJUWSrF9fla/Xgk0AfhKNEb7HWWxgpWXKe0JC6xBN0EJu
Y36CXq8+dPbMbg5BV1OqasZvP3KBeMla2qv0jk8vd1ZmeVJetu9o/IB/Epo74A09
We4CfkB9anScPBGoEl3UJHQdwSTPzlhRlTYmAamVkTarWQngRr5UX4K8YF/zkI58
bihyrp5+3s7c7XDStg2sfBzg3IODe3Pvrdw8PyZFyHrxPzWkE8vbVdQFdTKyHZtU
0BOF4bpbvkjV89jN9XbTt/GOc9HvqBL6UUXknAub8PhTrPK5rJ2O20PdmvFTkaUd
eHp7J3dCOmMI9UAdF26CfiPYBeYQjfA8BIkFQz0HpqVuyp3pROeb84B9lK7C1Jb1
FJsQVDpkxU0NCHmrLO2a7Ik5Y4m2Fi7on0SiNO+qDcBVTSFjR65n3pHhFZsnPcrw
rFIZMxULvIr4TcpeeoWpZ2bx90JaKSoSAx2rGJs6YJQoDJYpFmlHXb+s2iTjUuvj
Q1fQWIrlHfNH0gePX7Z6l+SQ+dD3id50P4sJKNpq1rBBCY6eZ3FQqW9c3m1ECv/S
SQGVzMDdcimRF9uNCWI0V/yU+8lLv72J0j1uvQ4mCx7wzOSp8E2Qw5ccheJqrDWI
zPw3SjEZjeB944h9hrL2bLJIfdZVcio8+bk=
=s8PO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c1032670-672e-41de-abe2-0ed75e78a33a',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//fX9Ms0L27YOT7mJ/YKdKemRawQNapjDls/O9dLLZftuy
sYDUrL/l4NL43lbKD6pcRwtDGcHPOz1hwmm7U0LEGBpojH/F54TL4GxdEUKo04CP
l0FxhKnMaFWp5RFCCAtli4qw+DQ71opoSXYKRNlm9EprYBi4s+xaLyYYoMZpj1yF
0jgdq4IX3FQQW3JnOFhHj6oRc4QYvTfKTEWSDM34l4d3h/GZc0mXAnRyu82Tx0uN
mzjG4yatbb6bVRP3V3zT8yqL+Qg24MnJPWuHAWJnKNU42Kp7fF1pdK6w+v58UpwO
vodDkF4tzCvuu6CoYAIFIVD/6TIHwEceiKiDfxOszxZ8bmPvxhmv/6fx07UUYtW3
jYs5iHZt73NVHtlNMIosRQpeZgqwnfynjl/UH5iYZlzDC9c40L03DdgCjKKsEc9l
VRWOAJuoe/BL1wmUQR7VWObApP4QBdCbYUVMKNUftNeCkYI8/vzqnh51UwpnRJYh
s+aHnVuKJMPcCjq5tA6HvF09ubZnWaYSm/h4/iWHyAxQqwNKn2/EIVSgu6qIAJNW
nZq8lTXN43eXuRa1FsGTniygwwYBeSZVZg8fLvYZKKdBJBVkJt5s86MI/n1r7d3U
d7OJKUL2ub+WHoQ9B5EIpltVmjcebg9HW3Qkx+iH0rML3n30expdsX41t9O+6o7S
QQHF9ySmSmGVHgpy5378zWca3ffebIm5mpWuoFUad902CQ4HPDrKSrTL2vP1ZQPz
ViPH5QgioGxXbhBhFcckuaFC
=9Y5l
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c14c4622-5298-4e27-a743-498a0609266d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/SW+uZXeiA/0+LqNGtB9vrOV3/Tqgo1dCzJB1oLvNo5tx
17IGEHEU+eS58/pD7cL6VFF3PpS0b8CoIiTGxVA6pJApl813iJYVbl6J4DRd2F+K
1weudpOA0ayibzGtZFFe+ZYRWWrC4uJl2PiM6ejZs+wYOPeSDVo6W9zTJ6XUqfuv
TEIMA7dDTa/C5l+JbDhK8Ua4A7SXSGIfuQTgf93HcLeAZaYXmWSlg35RfaGcFhyI
/Rv+Pnr0hjZ6Lrsi1bjsAP5cWLS3eS+UneWpsM4tRObewwN3nBwN2NoqY1oo8CaH
kpgklUV06alXkoktkl1LuDvsvj2YoNYbxNhCOIyfe9JCAYeGyHl85WW82UU77buD
fHB3adqPj6Y+CAbsdyyAcgVvReWXlNbqgLCKpiLwFSLQN9aEl6bUSAzvpA4vEd+Y
g2fZ
=jFj0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c16ac242-c5ed-4f33-a5d3-b244321b2af8',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9FY5mjx9yIZkWhha9dwnbZaNT63GUhqwHdIsBFhE84DiM
owgxSQyZtFu8JUpedm9tEqxcOvPn2D5isl+KbhEe4Yk8Neqlq5XeIvBQsTYH2/ux
o8GPwglU3/7CZm16+Pb5dBkhmD7K06RbsvHJMZxIwOxEMVvSlS/FUWry2xJzGsS3
D0Fc1LeBrHMSBa8pJRSinxVPEF41i2I2ED442fSH0d+uG4fLUMtZoGhNnDbdEZ+y
JDcBvfBoOxNZ57WV1FyMF9mrhF2vLv6K3WeiY15NXuXcqhNfmDfz0M0M1tPwqF7z
2r8OG5y60IIB6z8HuzesuMv2h8F1QUQY87iUou+QBnlYzTsnXctY2YSQCyczP3oh
x+DyhRfQ2HfA36pgPgX8ux1ipmBFKH+6HQqNklrM4i17kU25lJBefe3ChcEJyiO+
kMV8ANfoPW16A++c2TzGNAejK1FN/9gRSJHkUimoqRb1aA8UHHZcYiHKm9Tnoqy9
U26ys6Jjo+LCfxv9ng/lbSXrl3o6VPvKK2jW6CvCTRFSjYOlu6Z8Y82ocPEDtqep
cBw993vZg8Zikn3uDZahherXS2N06ka69cwnYR+r/eTgaUlwSBEt1qb36Az50HGQ
JTbT5NbfvLUcnrJVr1v8KSstaGJrEEs1e+/huvJIjHlZ31lcpp5pFqClBOMQVSLS
RQGnplJ/4ckMZd3TQti/uGXNeBgcPCT7FEhnP9K8+LDfGVlHL6x/uTZPsqQ6zkGv
wJq1fjdXKQWkcentiIWENfYnJ358/Q==
=R93+
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c1746c65-e4db-4f1a-a081-d75030eafdfe',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAgkxyV0k1SLi4pvtOQhwjDMoc3Jy/WlJiPQGW8P34Hax9
RMVxnWQRdr8bK5uaCJbKtawP5sgcFpMAtr+AnaBtz9K02oN0PWpT0QN3GUbOrQYX
8oDWEzVQJfb3uOJ6K9MOWyRl2afs/P89R6vN6y+5suXyqz0+1SbF3jt/yqX1b3Ax
52oSwzf3BTEbH/tqvn60jTtgq5O4WNRRKiinFaNqjcg8QWDLz2/CcGJgFY+1g96L
x8/GoaUHAwLWMhIAsp6SUEa5MMyXF8zpsQU24NtsK9ocUtpfzTloFsfnoa5aJm7M
ILlzY9m+Lb68Nqq25j4YQxkAwGWc4t2gcJIudLOJVafdY0429Z2Shwh6rmSv4Lkd
CZKo/t8wYAQ8KI5tzFDfGdRMvnJGNaAGimXyxn3vWoFys28OcQRQMrEqG23yA7LI
9ycnqYWwOjlpD1P3PXDK9i9XMYG5tIz5K88WlbhLO//2wUl193ve66ATJgEi2/yJ
LP16iTEZumj39YtLg7fsYLay03+HJ5eZ5adRwJ7DhDvhFYcQ+CVMO/zvPLvfaEPx
IIvvpnQ+S5vq0Cv1J9Ex7rzySAFkb1OAI3OY0AaJN1hYRA1PvIHx52AaOcqm7bim
Vrj4wd+u7b7t6O4l4Cxk9T/JpyJEuTIZqIUfreNKzLrSVZBZmZ1klcyI8+KAW27S
QwGcJYQ4LL5m3pYwznOWk6Tsc/bNVgEhmeBeG2bxF77mATMDu6IG+tmzEPkXvUtP
1/1ll90jKULroF/80jRKzP7l6a0=
=dgIM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c186d5ad-18eb-42b0-abcc-985a1cefb66e',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+L6VXL58R0YbpH/pbaYGkw3BVaLtDJGiUD3lktn0CQJt5
UjaviATOVPLkS4trTiG9Mbn8UDZQhiuBi5juhwtdHffcea7HrgFfbR0HkBAFRK9U
Os5QLYa69T21bxhokhpd4B5NUYvxcLcaZugCy0+69voYalJLZZC985YJrSLuW3Ai
2Bt9hw9tS21R7aktzQ1bkTrapH7WMAebNjAWXOK94XDv5MNsszU1b53n3yTwbOzh
MQICfpHyohq+jPgZQtcVemug7vFm+GzYQY3VR8MmN8wafEGgTafUTlk97/ygVKvA
uBK/cJiPwT/DhAJlybfDfZufFWKYDw3jAr12iscFq4RoDjKP7+g6+LST1Gc9mYDB
oqKICwvPg1ECMWYo345c5qXz7iF22Rr7XKgyvsKnXbqFtIWdCbvmGEEBPzzkvjcN
4R2RBenTWxezHFIJaBR3bZGcj6pxDg75BKIjnaXfZJAtt7x0J9lp6HL5Jh14Pxqz
98adgSXFOkplPrRyJI2VAul52LFQ9r0wzFKIHEB5wsubcivFOCxteCSkderkUuhC
fuCb8OkNw4HM4CG+INPqfq9mIfnz+jUJec359hHOdV7DTDB0yZl/c7DyVG0U1PQ+
LZo5rEAQ0IzsVtO6qBIEOeibPzD7N/ofqdHK3OkWAUloZvB0zGZ8uLl5FAQGXrrS
QQFhmZ3t6ggEVnBmP8xc/VeAeWgszAqO2O2N60r7Jn4gRnxY738I+X9GCWRZqM0r
vOvXT2kRGzhd6PtQDZ8zSzGh
=lgDT
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c1c30074-2b92-4a52-a019-acc7f9096983',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAlFIa4sUnMphbHvN4unIbustOQExyvXyVSQ+B2PeLadx0
VX4pmahKLKbJNfrwrJ0AYQEp2rdnl3QhDmC1gEod/tGl++RxWgP91mea2VsEsPBl
hupBADTMii+fqfhUMB7voE/U7r6sPdz0aokRAoXmx1ZY3zCoxdzBI94kD90lp60R
ZOGydtUT1liHFt3ZxiLB8ksuvzvjQro0BKiiyEQf9ZZtFekI9heBLSqNcvzFwspL
fP7Z74RMcIpcU6rJ9hr8L3T5gZOA3Sagw0dD+iOl6PzdX2FqTv7z8xcoFBz5deT9
SWbvdmuZG3jEA3RTmzkGEOeOXqdhNIJJEpP/8PRqeD/oiNcDZyKLDM0sFgOmNEwA
6OVMvJ9rFjtqS0/RgcbZxZNyc/Rm6UIleALQyIoKZGZ4GE1vcU/xTm19TljFIqCQ
FA/o5D7nCmVITADiXkjX25Vw6g2FGfbrA/x5vXM+Pg58zS6bHKQPgLrC6hMdhZSO
kb5HfvpPLyUJlU9dXQOdA0h82oUE+qXk1NLoCgdXPTIWbn0uIbdVHeetk5hQ+jCw
UHtIbhyiyjfweNgoRVbYn82ipII38KEOV/3H9Zrm30tUyE1p0Fi0DldePzIdUa6F
cbQ3HKjUVyp05LDs9uCoClRIVGaWfyPnnQ+/z2J92nUpYXPBVFxPV/Rpk2cn5InS
QQFELyq21Y5I+IUz6kj/JkN/QMmgi48Kb/cMjjIS6UVMZsL+A3ytkgjJ3r04e8oc
POCFD1umObyUbC+HqoZ4ivFH
=u0CM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c274908c-32a7-4e57-a8cb-2008156f4337',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/aWDRQzBdiuJbppegNp1Z3uTZUHSlakW402dUAH6qGYHE
n1YxrNxtRDchKhIhYv8dWpVtMY4sqmLXDkT49Wg8xZpc1c3wcwNyyw0zQQ17J16Q
aHNqMn2yiqp73sS3Mluua4CEdrwuiQvoIVfWPwcEUUVpfEAupdk68JYHwOB8gBNN
qrhOrlgk347BMFU6odE+N/9Z7Plc4JnyEBJrSZqe6UuImizhSYsXa5cIcTuOo+dT
JxusMx2vX9fPxsGfBYUrTkSfBx01wfhs9hy2dhYn/ZCgnnDYU6ek0N5A01UN3kRq
rioJiQp/S8K5RP1cMlIrvLIY0W/hDIXTnpyA3V6Fr9JBAWA7WgX7yH1RyH+4+dCM
nzpY+lsoIXVWwh7n+Abl5hbl8waK6nuo0HKMlIQfkLSyAX39LKKOkcBrsFyzrwet
jqc=
=BmKU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c2a57d6b-149e-4b59-a65c-7730208678d5',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//Y74XIEG5ynvUXMj0Y1oXJDuLTDq/kt6xy5p/pIcOiyy0
eNxpUfqOxTRbFwdZC65W8S235Jgj0vDRZod43zVyvlMTNlJ6r6eHVq3dXxkXc+T9
JMMWdK//lYI8hdDPbx8XtmGOINGi0H7ZSXXwA5yj17nwgVG+g/DIg2EKQYicZoHW
an8XvsyxiYqTGuwOXr6bqPHJrp7WlHpO6baMzgitju6FdqSVCGnkEEcK/dtgbBkD
c3JPcCMF7ZTLKzJf0nuFdQGDSFdUJoFcCiRvsQGyGeLSlShOaEnkmCPOt3g2ZsHF
KtHzWqEPurTWXNz1kyVmAMWeXmkIznXqwQ52tiPXnKmLSDPg8ODGBuUosa2EqfJx
roh6Fbj0mKRdb5lrSFeOounoHBnxHKS9mWQDx/AHyx6tYtGauFiZ/t0jDcrHSUiV
O3NRBpmIlAyo1bGhL13zvHZaQ/6c7LfSeJY3WFW7ZbCxUHwkwWtgYNoaltWVOkGV
k84+m3FKPAMNiSop9ShfajNs1X6/AepPWtQTEXEs1uwO5GnTuozlEJZTDxluPwFH
riaJse60Sgva5W4Iu3CY6jFHgjr5FmSioWxKVReKc8/+o83c8eRht5UTrzoHXPcw
Hw2e4j4Ia6vic/dJgyOBZ8QYcHO3jKy6rGhwXk/5U0dC1xcL+5/yjSfhWPDeN8jS
PQE1gJtZ0QjdrpzRn+tCPCHSi12t7sifKi2hGCUuKJCV+8FyXVLGdCDGjTqfPbTo
ColQZnUlWMtODdwSygo=
=1PLn
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c2cfb562-0beb-4e9a-aa60-48a190a8e853',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//TFeeP43VEKstP1TFOROymlmkTbya6srsNI+CoTF2wnIF
3lbYvRwr5D9rbuaBA5IRnnghHiXzhbNRF5kR1Rua+OG2CIkl5pPHkgTOhvJjMO7M
iXaF27RrkmFtcuSZTosAmVairc/36H+/Dn8ExLKOVr/zYgWJlggcNOZKC7uDJSBL
xjTfVzqneSUzGY+FXvACaAKFLIvvQMx1LtHosRoJ5oYyKeMxFnSLLMU356505Jpy
P//eZGWmrlFlNA+akm65LLfY/3MvFf9NlGYjV1kIRmjaNXKbPhMubohwgNduaL7q
p58xdhu5GvSgDmUisjlTBb9z7A830LDGKUwQoKIUQYfrLOM2R9CzMhb8rvJDICjB
N0nuNJ7Sn1eCE8DHVaKXFw2ad62gKETlljWluguo1nUh6Pz9dNIQOx2kYHx1IChi
Bd7URGjWQJmiutldFEL/gHDy7zyDOgFnEXjfmt4p7MYwmUR6fZK79QZT4+CjyhoD
bUTtjFl7pMfmeNTNcLwgvNtnQbHDa6zuIqN54T5eGiopk1t6KHX7bLKjxjsxbeVx
KtTT3aKAFHPR0WhzMWMEXav1Npyp0Q4H5aZsLFGxcKkmcYywSpyyPrljHa3xDaXW
/0+wE7s5TRpL9xgOqiPMlaiS+vobbLIO7HSQoLRWhd2uXYgHkXk8GNig40nhE1LS
TQH1bizuy13VgROvgdQAXDFfDqpxW93V5Ue9YdoiX4yApjgGyuyAuUXR+BCNb+3R
AWhBJf5I3qnAH4ZtAv0yOxxG1mUt3kGzcZMRfHLg
=4TiT
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c3d43e21-80bd-4aa8-afa3-ee25186d4b25',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAmMo7eVOCD5mr96NPLZC3yBrqO8bVjr8rmFAEfu6NqLHr
/0pVpPyQU/Nygkuzy/8s7m9cTf+xevnUmQrgXqV6t22rw7Vzv6IBfAzPLGXYdQ0p
wEaDlfbBVcsHsatDGPVQW17k3B+68G9isZiDx2R8QjM7zYjVE68njvlCoWbL6SkZ
SijVRPNUoR5PuhgYGLRqZb/KHQay7fcuwprRbRt2FebTA4Di/F2rWVB7Lc4WGR+Q
vqbyf42hny7J/DZj2EVXn4evVAuZ6judMJcVZgRDX0FR+nLZm7jcW6QhpzgdZdPJ
NoAm7d8vDhcwjrVBsylmzma7nexOPZRlYjeh3Py2/XJ3i5uucdSnT1wgwD96bbx/
famgp4SdIY4BY3zR8uHPzG4sOWmOwzUt5qrZE4AOhSkVeuXtWDMCsLUxlmFtEa11
yYbHAXSNlTyhuCafPFsaC7l3z0zUZ4i+nVzWcDcV/AE3zPDw35VWZyOQda+Dghsg
lkJC1SOceKtcj2NeyEtgYWsR4Ks6Emj4rZ/PBb9G0x98WlDszZ3jfgJzqkfcVD1r
N88BSqIs63Qza9pOJdDz9NxG3te6EBjZc4cuqmh8aXZ1JMK8mFb6AZs8aUOa3UdN
yhwQOTlse+vkp8cUGhCaSesFOu0f+Gb4PC7yaRpRBbhN7PQ+8nVObBkwANFuMJvS
QAEEA3Vu/LNG+Ntth+5XCaWQRgQ3vkQck6wPH4AMUAprY/9EFlh1OXUe4qSaCbZQ
Qmcbq7IXbEYhWK/NkyU8cp0=
=JMPr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c5197940-b637-4161-af0b-3cc5d4dab0d2',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+OMFJZ51ljgyFidGqMjuIWGZ1JyHvz9tcgKYqzx+P30A6
QmhEmmj5PvTdIQbT5XBOMqrFk2z7XHC8n+bd3xhvhFdgqIU0acNa7wH+dfkhiOPm
7ovqdPoJThkjHOHeoqX25r2zanLrHHxt8nEgb3IaiIhq76dQNFuXWKoZ7ZXSrf62
yLgpnzp4sMpiPu9SNihLK8xPEHsbiyn/No/6G8OKlZRE69HI5vRizEyhp1al1YCT
VzI2+qJA8Pmwma0Pb1zbssOijRJlDATXqplF7t+dLmMg5dr5LfVDmj9G+zgaTaHE
pVUBoe94MZAOi0mWLlfJY9gcSdVBgb+0Xa3UGOllmtI9AQKuUR6kfiU+VzTedHe6
nc+GDSkmpBi2H3KI+z1GwevI/TQaKDW5RQXg9MoBNU4QwxzYhkMzWWj3ZpeCyw==
=YpTJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c5286f2f-8148-49b2-a9cb-7cc21ef488c0',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+NnHpUHshXgnyIUnezDUqFKTOt/XwBSlhkU1pTEXsefMf
b1tXyv/Flj0mkz2zpqcACTSo5oox+hGNzokE6wzedKZ6GVL5QEh+kBA6RRykFaU6
sI2Khv6t2Z5ZvN6R08tJ2xgrQ0co+jGnqEA01do3nepW+CLM9IVL0Hm09CxAIRZH
S8dPUcJAzQ3D0dapaZ7cUaaoXc+r00skvudNTYOAXJRu9OoDj/YQVYnaP4Sy76Sv
AkXxHGAtG/Bi19c1DLfuVKxkXWBAV1JnzUH4G9qHSskY+lu3rG1oGl0iNNZHsj7y
HVYhoi1AADtveW+s7h4XXZm+bxsNKiU9avoTh1Xwd/byJ3kvTKSJTbDzG13cJfgn
cSzS2sSe03O/WSDVSOZW7Fp5vy0jiNq5gMCA6ieCiQJ912vIbvXk30g3Z22WDXcR
p6AhdCgG9UOmvnlDmjaPEBd6uOK8LsUD8BrzjhkYO8nhfOpU7OdmzuioPng9LP37
MCG8s4PEDU22UDqacBEJR4ezypxnHK+0fRYP85TI1CEAcTYPQfN5t6CwwvefCMGq
KvTioCSdmmrMm8jgh3609lCapjMYo2p0HPDEx//Oifi8ArABZ009ePK0djSrfbRa
TBuiqPfLjR/laE4w9sj+H9PQ4pCiVNJBaKbP662uNf2HzwQhGAF8GnTRZbxILDTS
SQGONMriMh0BHV+2KaeQGkFa15CABGMZFPABXP2XUMWDWCvo1nwf5UUykBEkYQSV
ZQuWLbTtKAFG75WatGjpsDaOqpNmtydHgv4=
=ZICC
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c82d21c8-3787-43ff-af06-fd906901eed9',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/QgCOdZrIZ1+DhypWkhnQH1jH5vm4NnGFyrYo+zQ4jKLy
woGAVCaVRIY0IjA+nDE9eBr3JITjOYxc2XMlfiomJ5ajzYH1qOqCKl/O0sAw0+se
FcLevmYNbpZePYDjVaVqslzhx8PE8uuCfzdWM+WKe8YgZkcv6t9FIs7TCO/Nsrpm
3Hl3X2R2L/ly65UUv7my9z1B3rHPi0NvWzz7BPTZx72CMcamC5u3nMgA+0wUIpRP
o8Es/sFIOInBRQoi6BLX80VJ+BgRrP0mqB1j9VSrq9hTbtW8QVVSRpi72epb53oz
+fJfpnXRNBjkZzFeevZLUsubJhHlhC5v0EfYaFlQu9JBAdJ0o7dnDph6+1Mkffdk
svvO1/R8zTOWKm2T/RK2JGKQ8AEnCt0EbnZsozKEt5ZKzGK3fGw7hedYO3AIphCT
Bhk=
=cdTZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c86815af-ffed-4484-a700-b57750ef573f',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//btBq7MnfNux+SZ5QgK9Gq1FVmsD7tuBcfOYTQOaj7f+h
E0Ad12m8ag1m4W7RSA5/8Lj/4QdQyYa9aCiQ9fqPMjGrWsoagWbS5JcQZF+tBIw1
2AULdQxR7IHzmtGWgAGnEpZSwNhYUmOpwCKsgZbdPlCLqgLhP5zGjEPkQP3hq8/v
vklwkl6Tq1VwGqauLkknFWbhh2/h/p9YR4jJg0vOl1t3p8YCU4W9o7CZE9ROAm7Y
Ruknifj4jKYe738gVD2t9W99ostt1Hxn74cmr1RwRSLzS2DoiG7p9iABBsltk5Wg
w1pSEF5cDfhg9lQkd+AL7CTdl2N+wIpcjH8FPGoXhzHwb4O9rKm+ZuharVNSozbQ
US4hg9giCPa4UB7gs0ZJULwAphbJzABoMo326gnWBBoMhgBeT3RZyS+lW3SoqbWC
cB2JVnVKPJ5/bhbXha6/0bxSIBa7hNlHA2vpLgfDWTpC2OpZtdeT5z86967AbwhY
LkRXGItIdUICLlF55QrSrcip2quj7Ri3FWIjR8WKkmx+S0OuiVbpoF3mAhNx5QwV
g3066wE2oFAejRcporhL88JHRvo7571xFVPiEXh2NKtmq7dQkV0enn0BLvAqM+1v
T1yppf/AAhPQqaOwR8SPbv6F0zNy7ZvEREk1Yw4tbB30a+cpGrP0CZSQMlDSKXfS
PwGzlZfXfGPlhLtUI+SgsngcAJH7BXuI5LQlbrFKvCoCvgeIG6bfrES1/k10NJD7
zunRmUmiOA29CXJGxTD+wA==
=J7B1
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c8a3c2ff-cf0a-42eb-af75-4ca12681a073',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAphgMjYUp8Luz8EiAYGDXE3ftarmv85oG3wHIql5YedTA
zd6BHTdiPoqXtfO1zh+iy9rkGAyDlTgDzEme138fegcWbWUUQrMj6ygbIrCPCBWt
32DBkC9SOApCcnIzLmYhEEN8QoE//J3Ju27o1hDxHURhVfc0mBmcLsOtH8b2cWtp
npeyR2begS6Cd5Hm6zj92xiuCWmwy7wWk8YurqyvU40rvgv6inmuIgdoIbp/xzdq
1zINM25hxd7FBcxw8eldEimFHUJKJtNWKZ8f28Ry7iDCOq1z2TwDULPX5HC3LOXa
uKwDsUPbO7Al7Vp5VoZmJPJ/y7cgd9U3EYaQq2Ph/zNK3+A9UhPc3A2oLE6pna5s
C30ncfiyhSLryR7LUwS60sYYvqbpXL2fvMSsCfnYNw34Lr+WuVIe5AVqaEr7yxzC
fQcTcc0K39iQz5IJSE9TvkJhEmZlzkpHyploG7RGSiS0CyAEBoOu14w/SwC8cXF4
1jegBZ/tWojB2k4CTNYFzojqXsnungqLmbdHUIupU0CTbP5UpJ5u6+az4wRkj62J
Y2zFjpNABESTCrf1oHy8obgbHHpaTXrz5hyRrAR464EZtCeilYqoqTnjWEwLx+lV
0aa9KbWZzO90vlOWBTgHg6tbXiVFBv6wyX0G4kG3miHsZu6Dcuypj8DAMBnGU6DS
PwEN2m1o7J4ODwz22wJ3sE2R4lDMlEBHDuhnk0AkAQSEIuZT5Yy+luA78Dv1G3Qm
AcpakXRVKRPGvYi3K3f8tw==
=jR6E
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c986f4f4-d1b1-4fe7-ae29-b2513b9c1340',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+J2zP3PCzDO3MI5rZSJzD5krQ+ep+Q85VmAZ4B996FsZS
ojGDiPzTtRxPmhoQ2semztV47VwavxSzPqoBRKTGyMPO8PIz6CzvmGDVFIYwELAv
AsE2OF22zFvHJpn3oLT65Yl0/LfJbaqx6WZOBsYJDhh3AATwTvC9DvOj3r9e9c0g
7RWHkwSpHLpbET0WUs/Qm7h9R7sfVpeRqeW/uE7eAiwLukhpYQl7NUfwG9Uf4DDo
G3B5TrdlDTq5iP2H3bg8kfq1BUnsJLAEjKkGO1kZASkToSwrjLwRzPcnhIgxhCbC
HLRhBIbwJM+IS9bMy0+gozuTaRn4sGTUBZPtiIElaIddPtZzmKdaUijAFoQ41zU6
YQKIXHKWtW/kZ7blX7JZbKZZooQj9Lf60YTvwEMdn2y5ScE8XMaa7H0rE3GieXH3
VUBeSJIa9mkdcQIqPDTm1/TbgfMImz8akBy10HGf4Cb1j944jJidnSx1+EV2DgaW
21hHNpqpuSNrr2OTgQjtyK2ahoyexl/4Go+Gw8yWNC0vtdmiyavYGS5oeNMAZeT9
dvmLniuSFa2sRxKaPfFrR2FyzMGo2SnFWDykJ0V5VwkF5DPMiF7M/zbCCUMVlvW9
gK7FbQNqO0LmxI9bCFgF45wyrK9geqLCRbMdjRttOT7ki0JEAI/7pksgqfY3QFfS
QwG5QlrClIjq384LKRVMEp8EVjkNLSgZAZaVPndd+5FbgfuGVHb77GdAvWrFRYX8
FbhYhs9LDSKrYo2PnHN53XORPTs=
=EMsA
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cb17d57a-f4d7-421c-a1fe-1dc0335052ab',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//ZDbG89ZAxUdf0qp2gHSzBKOHsaDyPnZ+U/EOfq+zDp4Q
AqxnPrQm04vQ/BThQcGMZy9oajwtPea+D5I0283cIKxp3LJZqyJfw+Lrr2fpG1fT
ggun4Z6P+HkB4lrtT3VD8Iv/YvVf8UEc03wmlvPVpwLXY9/2DBQCinys3ebY8rmd
LdATxpsPy8x38aZoxC1q21oojduuvsZh61uoh9fkevdGeYZDKiMMjfFBx8LX4AXf
/mjMXpTd17AdZrIcIgA+qjV8i1k8wZtCJ9nJ2xzP580cxv+ifhUGk2sZe4g8Mmoq
bI1GtxSdutSizmRqW9fPXBaADjCowkOLwkJsOnONvgTiYeUu9Ro0ZcGdIRRiQkjC
r5pE5HrzYkfZ1O+PBsrMCmLt8D7iViBperNd7ZXPjuhoNtHDkd2E/rFsKdU2z2JG
voJmR5U2+UbipDJiGafWGENgUWcTmTYVdwJrYQThxoyE9feGE77MsJ7kY+yMY7U3
BJ7ITuPfCuAR/C7kH12uO/j3hwAcV8HYhzpNhIJKuUA6mvyC0TDSMP+kfaGrc23C
owzgAjyGxhHelJH0YoMwMmtBF42S1gsvyLIDGSB1ZyOzXpD/f5u08j0oIXsoxg9/
eCne5K87jZctZ8JI0UMan+oWAQY2B1kX/xMg0Z89l+kigP3dWLFp6syMIMg0XHLS
PwE5adJyDmPW7jfwt6x+G/Uhi4gAxSAGAnBD9iPkgsjLTEiuT4D90tQ5HZcvmkPT
55aoZJXiU55loWOplSyc4g==
=Tuqs
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cdc4d2de-6e22-4220-a667-3600a3e5806d',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf/Qj++YnR3hliGQOcqK2707LZ3UswU5q9n6Exm8P5n/6EA
Z7io3D/EnDN87f/9iENTZvXN0szWQ6gGFtixNfJD3JAj9nF8fqiMaHbeymy7MKfh
W/veD0LpXo8Gsv3DiZbF3Q/CLLYMd2GlpmvElOt4AsO05xE0mcYfcDyMcffn25yV
0oqDwokLY7bapGq+S7Txc4H9MhVYR8JWl8Sl3yDbXLBG6JD0ljGoJYUxEm+yQy8q
9SpPrMk5jRnh8XA7zYUR3ftRZ6eMfzrPqiL/N7hbYNzm7BYvsxfXXINvqyUjRto7
NtzPwDe18iTXz1ea9kWW8tTSCUu1+5QLGujNkDAdpNI/ASB9ci2jQ1YyFGmUtbDA
UjZnfxqu7qdIY5Op57i/0fYwNQv5F7BqZ7GB/X+EfJ2xsDpOfNwajpS64KsuQLpP
=3nld
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd0525f7e-1373-47c6-a538-c670dfa1a947',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+JVLooPAOyRzJFxHZAznpIQM6YO0YujlgDTBWXfW3mpal
AdlF9pe8Tk1127SB/uOm/4E1vPFg8Ypdi3ms2UOYT+SLAlF9xMv3Z84KeFQJ0g01
CWUjtPqoSisRIEVr7p3eph5hLG1SGGX369dsnHxnixYUAni3dScFlIiKyNkVu/60
djzhQh0edP4f6An8xMyfm9XuQegn+gnXzGo9PnwfZ5CTkr0woZcBOUuIcC5suTZ1
DvwWVYQPtKjVnJqqCWh7N6BfOTp+/xwUAq5bDqqLUKxJmRnXIRZ8xl9dcdtUWAqy
CaAKZbF1C78KJZHDqbsHaUCLVSWtNSnGvV0P7xgEutQbvPdwl6rXh7/l0Rje01mZ
LhtfO1BGcH2N77JdU4u5H6RIhvlgKefbqlAZopTs9ySx7H/lhtjNxK3bjifo+22L
rTkS+HXNSwDUxhOxd88kaf+0tHHqVFROxkoU11lIgknaFf1q6pG06zp3Lz4/J3IT
YcaHhQg6gYUUnF3jEWwLnXLn0CUVlguhMhvwL4/TYyr+YH4daDNPDwP9cIOXUU8S
4t/HyHwqtP5Xc6pOCCLm9kZUnuy24gCR/9UFcZlbpoThZ6Tdboi++7mXwzSRc1Ii
mOQLLGxC8564TgUE5B0hh4K7OqTIYNOciItnHVKnRGfVwNHMqYHc+QSAUOze0ITS
QwG1z9LP6B1Df6e9cz3hTR+1WbU1dM/kjbJfffBMkyr8bDWDjjBgnzoRPkvI6ezc
TzGJhF+sviOFRHBnsuZXvAT180Q=
=Ifv4
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd0620926-69b6-496c-aec7-08babb8a8769',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//UpDLrEK+Ix0Jd5z0Jn4pwqMbx0tp5ha74/uaWwVTTZcm
YrRHEWLgci0CTdZFPYiaRV/SO0Kqs7Nd/qzjWPTxt2Hr+anadrdl4N6IwiI7sRAA
S0tDeENUgJcR0WmIgYwf8nfMvVUJEs6TkvlxNsWtW9thV6IoQi8daXEzQ4KQOJ5g
ATNy7M20WQWB+40hWBkFrpu23hUs/hGEVxYHB63nRvi2zJsGh+tb7jwZQM6MYapJ
t1VzGtkk+iNAb4k6KQ27g806Lj008tn9II71ea9rFuEP6P6ntL0p7UrSbXcXeCGW
kui7LyvGAmMvFWmDdZNNW+D8oiCpst7CDwAZb7VprUDpke7xpygbYuvf48TggJy3
40Y3i1OPUy17QL+dV/Zjp+u4tP6s90+YZqvAGcZ1VwJGfR/9QqhBbLhjXEEw4fbd
9MXWPiZ51ctIigK5LY/9dm3WsnJqYMo8nAGGFqhT0y3sGbDYCwEhmxgFFF2etRyw
Y4ZDVIhZwnZVb7AlfA/ZmYbR7ZPPIdUQp86BsIjY8+h0GgmXM0vZF6hLzPm7Odus
1bqMkFQWTQu2Cix4YLp+yuNgOSxbaayF4UI3OTcKdBl+eHtGns1y9LPGS0lGUGt3
T2Viv40WqY1XxA/mLxHamvsdvaUexHr862XagbRZl4QVESsr1nmaIF9eiURhA2PS
QAGtNC+5+0WeCgTI6kTq8YHncerOwomHDU+NuU5pV8UCkxganJmFa4afm0vBFf+9
gl5dCzVxoyEZe7Y6HF0+Yo0=
=9dYk
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd242391e-e805-48ad-a8b2-6bd3487375c2',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAphpyxI3yLJG5RYNoMfQ7JcXZCjnrvLhyT8tOdd4DpHnq
VXMvk+0WgfChOlWsF+n8gxx+Yt9Fo/OJ2QuTglz5+ny5MbJocJsdmuvAza9SrRmz
chGdzycrEDPZTtfnYtzYJJiXoMRM2iuHeScl+IuNLMIzoR1h+sxxW2eCTYbLlyqj
FJJBz8npzgpGr9kcNi/bBnlUcHpnkBTNZshHBQhMQJr+dM1GSjw21thssDdlFmn0
MR8FSzHJx9YrZNuf/E8AI8ym4bCEYvfo/4pVJPhg591RpTQ+HPkAl3kwEYuviTWf
cH8f+IHh5PaxPTRJ58RVFbZQKaGn9AWgJea7H9f3EujWviesXk+ArQ6gyWBU1NUK
w2sfO+JJ4j82WaDbdkdbHJsCCBLnPycNBrTFV7NhZkgJVBxZqsoKMTc1WOPR6WJU
v/BPNhxM+eCSQdbpXiZk9PY2CTqWBv1dThPFUbUv7eaqP5d3VLiE8y10IRK7Kdek
XJuNWTzlejVgmxYDNcu2WA8QCW5l5kodxHBu/BmYc2Po5GzgFPxm6wZzEk4pNg31
yNgcivqJJa09/bCSR/VKNe/vCZM3Ps41Ani/ewCIsU9lgvVCyEXiwt88wxWlqkRP
uBVXGZhZZUvV7RiATdYmGMNtJGyBGdt4DcEkvggAUA9Dhe0J8nOM0fiaKu4HRUDS
SQF1zgNt4U1EDAnwEq5HzPDTc+9Knu+6aIhIMhVMs88I8ZyhXKm5X0KnfVspt7we
tVIZx8/Ym2AIkbTe0PuOFNaKAoy1M0epgbk=
=IrZC
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd26511a2-7cd0-4ea4-a92c-c335ea6c6d15',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/Xyi4cMMO/jW00lIFZ1BXRfkFXGCLZ2M395iNl0KjzmC3
r/rMbYdJQR4GGe+q/ykjQthqc55DYmnu+ifgUMNruBCk4qDVRqo+qH3OHcnSk1MJ
vL+vJGJvcdvJfJgohaoj15ilgpzNZ3XDEslSEmeMOUASG3zuQ6r623DcTYMOeLhm
oRPip5fJVIa4QQxhGz5Y7KVyUv8MLy73uEEyBX0oGK8nalu5/lI0LplqGZeu5ckl
yRDNQvMizG8Q0hsfSjeClFiu0w79npJJ3omQ8a/GZhUN45QTDUUPs/SL+PzZ0KXM
fOxJKk0WT9TTIAje5gL101/YXnurcKtZrqbPHKAVf9JDAR8b2n29pUpxVZeF0XDM
jmRGENOQ3KUWGGeWFh8GPZ8or8ps0JS4Aa4VEBTq3ARqf3qdlhQMifRbBRfIRKmU
XViCcw==
=UTKO
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd46ef59d-6938-4ded-a23d-b1ebf6fc131d',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAkIO5Fk6jQFoUC+QSI/r9CU3A4oET3Xsm88SAqHfgjH+F
NvIKBNccIRrepqXiQUU8tUsoFncy8jM5S6C/NOsGJsWoOms0awxGn7Gt77LUZst4
9b9yo2s8IZ3ioqac7hP7oo/SxvHVsGV4ORPCJQrPCjjXCwV2nu3NA+A+/mz9+e8H
Cl++hPJ3xdLtHQ8yXqCKSqidm8VMe4zcb+Q1x/cKz1mMv3lQOy2o6NPSQyEixSrj
MTTejKAl1CWEhNltCDRsL2enMYCwWY3Uz8AsxkFz4jvzUH4Lp15R3i5G81yxjOiO
Mplc9o1s5oe9shInCW5GkdulKilx/AOjAwroBDiy7tJDAebLu+ob9+W1d2HX30at
5W+nDZEsA0NVrojZEIFp62i80VDEvj4ajU0XFlXjxD/oPRoWs8HIkJeaso0uV5Sp
s1DRsQ==
=HTD7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd4db23bd-2a69-4ae2-ad47-28950e3cfdab',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/fhKkinFGhdenUjg9GUPKznZrUBfkW3imQkZFi9rqQaeF
U3qPh0Km14QuxAAHSnGK8nJh+UUmh5qHUN4SK7Ci82Kx6J+gBWgd4E11PWVg3BMm
0+UcQYAfyXvsLHxcLzTJ4GFjzcMI/HMLLn1d1y8fzMfyolAMt/7DxqjcLaEB67YB
MDlKudnSt7BN4R3JPRd3M90VwMc8JsioM7SdKUlS9crF46ACSf2ROHBaDRsexeKV
TuVIq8Tm/N/9H13JIOPkyZdw68eapGDP+gZwi+mY102eDFeguAAxPlpRoBOr3mdu
JJCxeLYSCDQ+ZbbqpTfr0upSlj6e5bvoqMEqEDzuHtJDAZvuepZ2TmGDh65++4nX
HbxO+9Sw9gg7zkEpb7RA26EYORrAmxekPWhNnItSprrzUxGdNDJiAeCEKOy4n3Ua
U5BPtA==
=S78h
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd53d82d8-2308-46b9-a73c-4a795c3e65f1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAxBfss/NuuFUbRSUhFn9sx+tmauERiUJRwgjJttYb4nGn
NsB2vhEjD+xuTUWZxNWgj0tiKmBCC/++FYBaVoRfHxIpdtqF5EIjfx5l3Ye8d+R+
7e1mzTxQTo2QiuybiRaiO4Ldk3/xedSDQTWcTi2+RoxhtaTwiqqhSh7OUNbdazjz
k8WfjnFN6N4s/E5iOJkHv+T0nRXoupLX0yfNGvYecNwyndDbKC3NDZh52qpw+BPN
Uiq2IoHhMJrIhg0v0LRpeMO819I6SIxTGA1cP4RHPVqmeOjYrkCpzJeE5SGx8ZAq
OVmnsugxkSu+w8SI74tEE06gMjPMKDWhsMaTEXTtqIcM/pZ+5M6HEbqUqBqbSErR
bMGzlRYk59JqRMiZGc9GG9gSL1V2RX3uvMjNaIT6GPU64KboaPoC1hGODT4f5ga9
hrEVK1KHAk/pl0H7umRDyoGy0eq5YKqddUhvVWANh/wAUnYjGowfBRkE4F7dPRJq
KlO8+d9SewMb+C/CY0EnEUVt60/9iVzY/+V7Hu/se4f0XVEuYYHLtouOmARmQL8G
5jykQISbeum4j6JuvFZSKOsIdglokA86ZanlVKetvZV7HJel9cwvsmJns41lF46y
JJWNEJQ6t5CGEvDtvI0eGLIMPNjzIn3L8z1q0k9pYhuKPGVqRmKZxd0+YAMKwc3S
QAFuFPRFV01cUc7JsRFWqWVUR8VAYvND03SzMizi9Em7qDqKVolBJxDm4ZwUSwac
kpWItxH2dGYRU4hhoJRY/qw=
=kGdy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd7e37644-4ab0-41f8-aa35-ce8266e66f2f',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAubku9Tzrbn5w/7Z67hMXSga3ejCIrDucOltFTuDXYHhU
HtdyUbxhP5Vtp2+CbNmGQbrnesgyq8GMQ8xWKPEIj6jN+7lP2M2GO0OqBgWyFyil
pZpms4s1WrfclgcseLxAUhe5xGKo38tziGqDQEau62wRCXcqC8p2vm50mY/k6Hl5
nfuLIdsr8kNfvuvnAmu6ZNjx0ROF1aEUXEODSsSYMY7zSrx37R3MYepXW0LtmYAB
9ZRHwSwPVmVC3SEAE2ki7px9zbmrh47Vu0yf47edn1qg4BcBFdzvqTmBIeawnFgR
yGLqd8gvSHVI00UHeBO2B/MDQMaYZbHD3jl2kqKkPoz4SjkC3F4wIT0OF4nz0YhR
mBATpC9mUuqWmBZAuSaXHe35tlNvTzILuSfinej+er2WX7lPK8bR2iGYwehy58wp
tFAwY23wTRrc+CR2wRFN9ALkxBUlk5eBkIV8l7r4ix0CNnOFh0MKIT3c0Yk2YJlK
KKLub+xeqJbMghIxi+UvhrO60YK0vvY81tN6lcPS1fmxW07JADiY1fHTVLnNB0wp
9+UBDG3myyEcZYcgRmggR76s6lEqWN2U9AbskR/E+mYKKLLUq2GADkgLrVglBKy+
7LinvA6cp+d6wyVyiZJyLxuvhzgtVgniFrzQ+KX9EnJfX1exI2/CKhb+QwDntI7S
RQGsJV0pvlNAFKJhjLH2PYETneoUhNY8yxIPtfDxm/4H1jB8CgmgAKq9mydaMBjg
zSGrY9/RDWVPYBa3CWgb6ZQI59A7ag==
=Vz/R
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd8a1d7f8-b0d4-421a-a755-31f6d1b7e06d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAmFfvchU345Db2s1DH/Aqfw5DVbTu0k5DRyf9ukT6O7zP
0jkwIzCtxjdAmCx+StF24eqEloX5gn20GzExstR2cRoJtf3DRqk52kg5dccoWv5M
xUD3vqTZfLmC0w+jcbneUCHyx0B9c6bHIMhwFm+YDAVo4IOweY9z5bdRkA7yB9VI
AAk8nHhBFsTkbZGYd0gJiYoCQauA53/ztEr90pYlx/xLxDGBajPT20pcchvVeYkJ
WOzd89KEWqilKl6jX1dJFG1CeJshILWSSa/OIDOe+ikyoLVSd/kywR1RSGwn1xoU
NLwhdGXsmX/poGh3ARwiWc3ygmUqBS74q7MXofceg9JDAX9DmFFFyTwUZsl8VRUB
QzchSuh+rGJBXPUZzsbwvGrCsSjeV+k7hyaQsX3M2d1IkK6IuX9wFPc4nGPF3bMc
kRFRxA==
=q7vD
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd8f14656-4f86-4b54-a290-4b4d2c883853',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAq1Ez1Sc3E30lzz4RaDiv1t/R1VBeNP69h+0yKQWaHyoa
CXtGvBqq32uvUb9hdp1HPXETvNWP0S6i88qsJySHDX5SK20t9f6r6cio9Coubfe4
+CmNv8dzEAwmpRjU2WUCEpNd55cKPSVkTV8DjU/GqAWJffZBzIuT4a78MCF084Do
NfL5NuP/WRrtNbMQSUDru0zVw4Q7cnTkWv1iSQMHaeT6w4DzAGebL3bXo5TQ80iX
K6NKnQYyHLiIuq7KG8eIc5OaajELQTIjDuFJ4MBjvcNb+6Xgh0er43WBD+Kp2hNZ
4WYSQT1tnDJa7VsPGYr5d7Qv7rBDVHIe7SKf627E/I00tsdwaiXjHF1TkuDskDnT
mLRAd8LcvHcAsjM88oi2tbUiKjPe98r4C4/GEdZxeuFpaL3+4KWfD4elHBo+Rn+J
glQZKIz3umRHmQCoM6HI2SgT5ECKVyfFkDAQGKR8TcsDRvlhA+ILYVNTvCKs7PcM
0rsAdO2aR3v9Z7QV6cqv01XuzDHBCsWOsA15Y8hmUP8cXXhZvdMIFTGwXOD3AGsx
AHDuSL6t4QLi+2MQ4XfrG14vh/PalGzbni/955SAl+LCSr0hnCYLfuP2zM+t7TuH
a+opnkKLqb1ttXI5C4AfYwnpGuKpkiOxj7Pg7MOZ4DloTOrmxv+qqoc+bmq4HXHS
QwGGdoPGI2JY7y95n13Kxr7yhjY6EI3547pQ04/kp73apNmzttFncgg3/xNFl8Dv
J+5FhfnqO8negSP/RlEV89LwtjU=
=qK7k
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'da78db35-0ffa-4d32-ad36-bd8602d8a550',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9FAdcVufPI0YbMQO3AC9wyu5CPRA5Wlgd/8yHh5nYHw/F
X9eC6zsGe/GeyzBxuuHs4LPUKrkZjt9JDMvzIl4hRHymdjIQdUENn3j1jhcXKiJg
mboUZzKSIRLPvMdrJ2PplUDVA3VdWA2B2I6ug/xFO73LwUzpj54WJ5ie+UuPk7wb
rw1VX5vCadNeRteBC7Pi1scP4GTJdrERnQuSXHXFGUALPy3OfTTyisQiJgx9HIoO
1yYCFNtui1+Zfvz0j0QIMmptTmGjZt8hctEG/uYsANhWET/IXMADKnkdacOMQhTK
imhZcbVLD4CfwwQXG/WxWduVfePQHr78DtVcZPa652WJR7QDOdKTm2+rEE+r7Nh0
h/+JOLsBL0EGkHqWdKbcQhfg58toO56VEG3hIGETezbJRIuS9o/i9Csms/e721PQ
zsAHIsWO9e+ImVowH6K647EL9bv5o+nwHNPGiXZCRX+qgQRdUMovROp2IVxvr6Q7
sHazmOmDLhzK/WSV4DXpZvrz46V9zVxqCbE7qLuPN5c7sNMA5B2qwuNPvCU/jOZ+
PfKV+Yehx7yswu2wFvEFl2YKCjyK701tVGrGFZi/SHMUm153A5KymfjbcqeOv/Zz
8h4qs0CkVVQMJUldHVYhKJdrahag2Wgmj3MtSKRuMYN290IZHSwRvxW36waGdqDS
QwHGxvlpn7Y6WT2mawSdPCWG4xjRKHlaYWOVpUTA2zV5/vedxeKn4D0WGrMPvdgM
choDWOgVbYRvg0coQ6WryPvVE5I=
=PQ+s
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'daa61aff-40ba-4422-a08a-7f9681599fce',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//T5438B6IUkB0/OqzBOEUPHuoqQC9G2MGa8dQsDJWOSUx
0a5z7i+5dBM/b8lqZPGAXKrFzMkgbArNHomYKmfmA7NpJtpmMdb8VW7gMOs7f/1s
dD6m3PgCItrcZpUhgjibrruFTv94nKWpszhk8cWXw9ZDOUNdLiybrNpW4vBCNcLx
Icq8ORbIz88dOm7o44PzfTO8gy2jwOn/3BU0lL3pcCg0yHLv0k7wmSlaNZMgF6KI
1QlV0XepWuwisJTwY1jepfbjHhvehnDgV5Xr8xoEAwtAtucq3mbRvjR6tkzrtJ3J
xyMFDygEoOff7yLZPoMdvE+zBgrt2KW20hGiiAsCJFnp0PSFTU8Nc/BvSsYMwMek
zS8J8dRy63HE7MrxwgLCbhwOamB9dK0we4NL3CWEHZ+kCuFK3HbIHxka/WY8nc8V
I8QJAN87pb7HNN2lMVb4TR/WVy8KomMznEzMsMGGoAtqd+ATv1BR9g9ao+8cHf5Y
GD4HMHhdIhmQ+ZpoBklw++nvAdO/TZzx0pVQnT40z5rj6K6PtjukIIL41y/EGKDT
WY/y6+A0tmy0J2cw2BkWiLiSofScUdF8NsC2d4h4YpE7X2Kymy68HqP2ez52Pr0g
7xKc72SQUON3CgrmG5p3HGKh8+AhswTP/wCOjWkfMwls9rL/2VAUp720Q9yb4dPS
QwF6oPju+DiO4HvB3XyNt/AISddewPvnDNdXr3DtD9trKlGO0pLMWGbBqBLLjToj
P9ZpPijsXXP5r7UDYGshxa2DQWw=
=ne/q
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dc0d95a1-ee09-4b4d-ad9e-4afe3ec2b57f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NNmdrvmcamh6oL1so4U1WJqHwOW6OmPzVrobQBNIf485
1fvzNBTrzNFcZ7vjD/OIHTL8wTZDDXjCNdKDikgXuULFy+rTsu5EPagZbKq8u2Z+
kk8E8cPHQxuikmaMTETDyN6Z6wi6j2t4z4K8COWg3WQsb7KTdtZS8Jex0QsrVx6a
2mRddpe4JZ6YB4+BT6zE0rpZ5Rp/wrKJVBLafL7JGwETS3haQTQG3J72txRlfmNr
5PTg+BOQ31ldauaJZCPBG6o4kOJMzdCoMvDtMLP2VNKwfpu9L2XTT14n2SJ84GGL
BJjYjQ9lAp831QI7IBb0dtqfPqZfsdgAZD641xJG7oKxKp3oKKt4HkBbiFNzzDJM
/+D27lJUtwcCgVN9sQw9ZOQsTzwWE1hNmRqyanmq67m5rMnQA0aDNM8FL/DPYejB
l9eWw5EMWv3vaBAsO4PoO7LsVdbGshmTq6GXiu34I120BN8kO/ofzLSMlXX9FF5+
NKHI4Ln3wJB1As7a+yV2oTp0C639hcLbChBal3ZNdaw1DvVGfK9XOGicjC1xqXxT
y97fojtD0y2UAoDCEmyQQi/hXL0NRvlsL5sYkN+g2r1/GcvDuW2grNDLoC2Gosw9
N7NXt71j4CDscV7pfpLg6CYJyjWNWmKqqVBVEoYIm6CTBFAF64wA5Owy6DLg+r3S
QgFrSVZXXfQ8EZVUGEeQ8OnppXMMWOrDLhJata7OHk16ATreN3DXLv4Y9Sd04eWx
0LxaF5sdr9som+5pi1V7e9lV+A==
=wPOq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dcde1e1a-f47a-4f05-a937-85e10a75ef52',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//byrogt2Oh9ILKNxsunsP8iKLxhufIBhu3+EwM7xkIbFZ
BubjNG4v2G1qaQ9xoV4N2hZOKvZTsITxWlKamzH1T57EHMywEnRTgH6AQpjt8cm1
UvxoqUfvGoS96RpQVqiOUj0ZWXmfiP+dBuT36lWhhEb3+3XeliG5U/3tkWaNb2N0
lovtYC8nV62sWxnd1hgZPkWf04bCNOXFcOM6xWogQOESbq6k6Lxtj2NM3l37CEcy
UBaiRDz1XPKtpF8ITBF/JIKMguFuN1IX+FuGQWEqAelY8AEuMIwUfNyd6Ospn+BT
un2yvHnF9UaGfxxaginalwT5ze7y9UDu+VzTGf6HOgLIQD2P9gD7GSrdj6dujUGg
4hsh4qepalIhNKjiD6udR7M0WyfGtpswrOlBYjaqK0dRnmhCIb8CPGVgvKA38L6m
6c9+ymg0KnVrpjyNHqBZQTfPRsHsK4WHLzJnV/DIal5WGEyumMy6NCRR9Sv/AVvt
REy8q7zW8lZqw31neIdRZ0noSnn+sz65jhfMSx2Cevn7gRwxjTD3mN/Js7G4vLoA
kskcmV93hggOm4eZgmqec8RXK2/CJ9QGQ/3LwK3YyHgvQUQnuoDwu07oW8NZXXvx
WZmqYOGq9oIGXeMWon9VA1HGbaHjWb68P9dszgqDELUMxFBVKME4nmDRGpYNJePS
QQEoJxGy4rAaZGMO9pKnDl+UJnHnDvlwmCOVZlNjdQe+z9KAuWyx0ysr98/mDutr
XQHKVPWz7ATZkdF0qc3lt/RD
=sIIe
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'df281907-6874-409f-a921-7e09546539e5',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAoqULLrDEZdI0GMXRmMVrSbylBCs8VyziBW0FDzpVdwJn
0/+GbEbSiFC0l/vcYSJAdNzei6JkcGPsg31TPYNWh0syty1yIwjaVhJr55qoKvni
yyv8SbjHKW53w5nxNWcbvV0Tp5Tg0HP4Iytoev77wnRLFKYHeXs+0eaKu1uqXdMs
jslmcnyiErRqD7bqIpiS+6DLww73pxw/jTBAWMl9ejTp8V6pII6ZMnEQ9bI5deVE
VqcyiJvryoovMVh56N+4ninjZxcOkmLQgE2jZaHRjpzsMvhPWBzryjQOoDYOtrLH
eMhmCfHPxAaGoGtREeBVH46pwrVzYZ70ikSkExnyJscIoDrr7nis5QOYgVNvoHgT
FuoC8VzIOOEYyHiDS4rJNTb+OQrk6PGPPkax6Ew5FIH3L/QiOk6wk/k1/pIg1BCu
lqhbfnspntsxr17xMQaVxtxXnXdceO0dzS01i9JuvsvEqm9Ms50JEPIwaZQElm/w
WLDnapsWMy4vnJTbN3CNP/2dnFi5/lXjrKcgoYEWiN0O/Y8r0P+tytXxhX3KeesH
h4x1nt8F7s+k0cF/mTSQQlTYuLXlbZaz98CZ6zrf6TO2guKs5D/Nw/dv5Zm9LIfw
1dsAC5rKOk8AJYqUpOAejTcoHDEieDwxqnSPbJ6jPP10MK+lzaYoXy5xASaOZ+TS
QwE8B0z16TNpg9VUqBjSH6gGqgjjROq1PfuY16EvQR55LOVZPylwdG3gh4ZQlhLs
M8ttHbk+tqk2JHDH1BI1F59UGTs=
=v84B
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e3794d6c-212d-472f-a9d7-e510b63b94cf',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//b3HuupLnV4WN8jrxLHTT94MXJBGQVZtY30aJHScfGVz/
YefzZV2EPU8aA6Rxu/uMa86EbngKRSSwEUq/jHSh9SkwOtwTyqASoOHpBZEG4DED
et+YbA34U0muXgiZn/4YToRCQsBwMHYHfMf6fC0C2+lPgtAK67Rcx73eFPjXGlVG
NIKJ12rHhDIF/NXHrnRRC/APxM39Ra3qknvayLg4cy4t0GzmDQynZiepv5fV+Ls1
Q5xWt0/2lZpCu8mjAjX9OZK+B3A93uhF1skowRjFYibKNql3h0QWFsfnUzoGhKuF
xvGJfumesmHNrDMeAZJbCFhPIL7pW9t503UBvyYbpp9gVLZiJ/QmjFesBASYZR1V
2n0ph2GkVa2jFRhFyuWmqpF+bL49ZAE3BaLltY/lmf4f/zdgM6w74Z9OtpH8k6Oh
fdmG+19dbeLoj2GW5jpfsJOrm7uemY7A3ccOIlJkG22wfmdyvvsxLWdE5ty4fVx4
jJnXciD11LtPc/695PLUlXX3iQRXQZ7mSE0WMqK5w1mrPWNDhRzzK+gVysZjTf7A
aEUW7qgycJv/kWrGVA2a/JOBEiU5+eCB2FQmSiUUyRnExbAhcWaJbY+339UMFetY
RG6rsD4XXGK2rXQ1kFYaWc8Vbad1+TovGNgSjo+o2DHNXBIzW/II2PN0E6JhdBXS
QwGL18sxDvnBQM+pvfQNA6SfCeP0vsqiGrybFA0iXZhp8X4QIapSX1yAEncvvZjL
N5heNp5etNLyqpAi4ws3eUFpbMw=
=dlhh
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e5ba0f42-ced2-45ab-a097-a70aa0709e8a',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAs69P8IyI7CSpQKqTaTQnqUA5J2nZOJs/TgmgiQvmEmhx
oHdPAnxSXSWK2ea9GsbaytNzaVeQFxWP1G9HvkqR1u3UoMlcYwmoFZAe58lHO603
kZAryao5Pphog9nm8FZbFHmrCmMjUZIxjo6gFLoACQRds0yZYV2o1BoGvX8bEK1Y
bo8blan5urM/nCXBwVC9/hLAvi4iFB0jSR+L7wd6a3iAsyU5DtvSJtk+mVq6P79O
LZqf+SjAjbrCBOFLGi0hm4zSbO48I8ku5pCB2QamhvTBqY10k5OMDHg8wqlMoXsX
f9V/TdD3qqm5jRI62/Ffhz1XLnT6m4Sj+6M+pUegWdqiMWc+dJ22ivuFTqUVsgg2
VwjMzx8Z97ww2qk+Rgg6wuD/4rxz8z+I0TbbOTAGxZEOnXoHnwTbf4zCMwzCDfYM
vmZU/JujNCYMN7on4XGlblSvG+tDz4mtFWtOzHY1LnWEktnMpCsGI5ZerI8cXryb
ShQGazdn3/Hi3WoAEyKJ8zygGMHUixxWju81f6erbR3g/jczRGdpGzsYCT5suAxE
LTVI8borWJKqvDInc5urSN5h9RwgI8yz1NmQO+NbAOuOQ1RAUs866uLKyR+5Tl5N
X+rKhbGr7Zi3Th7JE+uufX5JR0Jg3zuPEzjzw1+IX6w7DlEFN+udKRmZSVoNHU3S
PwE/lWzTOJb6dT9ZGkcReGBB0phI4Gf7UGwgE5MiDkdOEBmEatwIjZPAza3qItR0
B53z6Iit3pGQ13Cid0GtLw==
=8JW/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e6cff31c-66a3-4647-a6df-b7a517dee284',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQf+LH9vKmLf8uL0bkCz2SGv59xrVf7orkvG8NYTTvWkP0kN
KqJZo4gEUpE4P98Mdnqf/kajZ+EesAtrqZI8mv2yYruMGRs6DGWCmLAUzGp5crfZ
sR46dQgBJlXFD7vM2RCmfg6lCQZwzyUEWwbIrVjGWat0ClYhGgxEpRpJqmPhF+Eg
f0YpBsXxwIHhvTbAHcvuqxoWLf3lMaJ419SDFNqepMG5kyFgWaJ5raTFOwtNxyWv
0pnPvhaWCBXfgg0+2YE72YVYUe+2ViqtZykeCXBL+LBDPtPtuZyfd2LUGoaJBNCQ
0Tikp7eB3JYIUbZaRP9S3Eiu62/hWQmuvRbtTdcFV9JAAcsvc21D9SPu+7Jh7QIb
82f/vs+8JIvKAqU+7Ok9VFcawlW7p2TIKg6IJsURe/o8pht0JYnf+YD07TRTLGKU
IQ==
=R0TE
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e99fada4-baa6-463a-aaae-80cdef49ca2f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/8D846+un+dujDKcfoTLnTM5BgpIXkxzgmCEXNO+aL/kcd
TnhawC85AX17ifBTf01MP/Rf/UVqySd6mS3EtcdDN94Q9iMuXwQWScYTyQvaoJCm
I1Nefh7xANFnRkiiCKNIl4WFc5BXY57Ck93VL+b/Ni8MT/6IQ7uIONDhsuzXbHm9
iztI0BUjCDmUlqSk2tGs4m4L4rv0zDoul1qoLv0qjzDn/7E++tvTJsYy19IUjUq/
/Xbkt6IuuxWkmJywVfS2G13cYJs2AQhF5HsAHEWokaRWbrMCRe0UbXAO8tW6nLCd
w/r8tyK1RROHpY8JLLexuAP/sfnEu9o5Oa0vgcHsbLJiNFfnliRNylVImhMcLVhf
dkOiODJKY/t2I7BHIgrCRHUTtW8DqwiY5gbVYEQCJqHBbR0n1GZ/dlkZq/yrQeUT
Bas7FBNm+rx8N8lIU5Zef0mCm+CIp7fmLBS7UGkeJ5Sd1pfwZPwQIooDrzvDe6B7
J2S6QhcytAJu0A5ZQrhL2jUTrNxIycBtqiw5c0ycEBjnl3+V+e1XKPvnnbVqDEmv
NelJmtuNhboHLGdsuP855lLgafMDOTtXgMFhk2wy7OC2WL5tvVG1D8JRDtR415cc
qSVYf35515srCDklQ/sVFtRd39CXFbBHCv6p8pgjG7ZiwD6OJOIy+46Xmt+B/73S
QQHmOOYgZAfWZ24NEqSaSxsxug4tASbKpV2Vt8ZuwNEsIAk3ZtLQhlKeSqPyLJh7
AB6c9UUWMfIL+pAOP2k3StbD
=cppc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eaf11b14-3bda-4222-aba8-866e578faa0f',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//Xy4thbRwNnJm0JZAouduLprq5kXeuMnq1kyb9IkPTc4z
QohMkFnIk/kUFrJvcYeNkS/LDQjsR6RlSYwaU9vMHk5FfWSCfMoonpBQzXrIgiFb
3V0Gsua1xpuV2MLjYpojqTXyvFyn/aum+RqjjpDp7kDuoeiJ/dCppDjQxfR8oomc
Q9ubqlSAlrcWFSIMmSL85b+0zZa8YJW8ORcBDPucytnEQ5LiSnTJF7krZ5XABacd
0lAvUPEA8NXULHrx825zCAClwCpR12bQmx6Uyv3MnO6liNkbaozOSbAxmSPlxNiU
IMtdPH2GtbxOTffDx872cWnO/lrU9UZVaGpJ++hthSmCz3YEEq0zfvQY5nYs34pD
fFoi6DNr/6XkIC7tkheUWX4AY0kKmovGTrHpZbDdGzfBYK39YADzFUOSRRg+6kFi
oTyb+GT7e/0C/wXbsQEfPIp8XK8Pf30fimS6O/0YsErpOOHUJcsriwLfzZTZ+K7O
f/r6B3vhSlj7m/9EqlxgczE4WoSuymmRAKPkLKsMZmpiaLj3+4L+78ENrQkQdpcw
MLi5MbfSzqLw59+Ggh9ajcQMCg4GbhEYbDpjm6WTomfV1FvrhCEe1wrrSq/4KUi1
PJA9aWJ1GU7X28a+ctjMro2JZboMRdKMFoyK6TSZOn5YMYemJw3xPrfPGhQp5SnS
PwGT3lAo7bzm4GTBDXeaSoN0LNfYEUi4MeLQ8QywjNCGp82JYZuNlFBwHDj1TnDO
+AVYy/UupkhH7crk2RsGqQ==
=XZDE
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ec0140c2-e924-41af-af79-da692958db53',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+IqdIrmh8QayPy9kg3cg78E0/nEkZHr81OVfMB4f5LrI7
mcOn+T3FNzLwc+jubllzhIJqZwqcwkdJvcX2KK6C2qD0Qzjwin76y2gRG1k0rHP5
isr6fDzHLD+4ByKdDOLWDtpuD0hgSqu7A0bsOrK14SGO4mbN862PDg/hewOXG1On
5eonSS3tdglTZjC0AMh2FrHMOM3waWcXPeyCWjSptIXb4Q9VEXW/s50CVdhvscYH
khu8jlLjyCYgpTklqiZzN/v1QXyFsJoRFAZnUaZMKdvS5rbqdJyKWu6k0wk0oibf
su07Pst6qGz4mDZ+vA55bhN+7HUf+l79F4bGyniQrnn/BRVQmyETCvWbyVCYUIXC
xZf2HWH5OpbW6V9PcE15TZC7FrdWucMzsFSzc2jh3UV5KMiS1aOZ4RnRFUmNjIjK
A7PaKaRwBeQt8t4XijQBbcogoQz25oUVzWiTS/jiA446AZ9hp7t8qEXx2FYWKtDi
DCnrQCjSyP2UyjYFbb6DnTIjb5qAfNiRYaFbezdyTOnHiE5/7jGGRLZRc85lbw/P
0sdlAjUCLHt6uXepxO/DxdLGZjM4WCA9uouVaLzwZw8e/1W1Qby4zEYu706V34Tm
+mjWVuc8V8hWlpfacUESbc9nOJFq4t2U9N7ZDBpClx6nrrZNyZCzlFghYtHsHdfS
RAHIA0PPTAhOKpirtfllj7zwvyCwPZ+4yk9KND65w3SqcdndPSkSb/0o7OTMMZAj
9UvGHibejZfXZtZ8EGuYRKYaHgrg
=3Svu
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ecbe9d0f-5ab4-4f1e-a199-dd83cfbd311f',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAkzYFYCu5GymqzQV4B2WJvCC4c4e+zOc0DPeYcUg733uT
xv3tbR+H8fwzSCD4r+bpl+1zC7novJAtfCdNOINtgkITR9/oPMOY63ikyRo8Gyjc
9my0wtCwB67GBz0p/sh8D38EfVAtdMB5+drGEiS7uYAIoCksYqNXwQQfHiJ4S/O+
nQ1Q8PhbGWo3m810RY4ODPb7Fuf2yQCQlftxnxrKK/dfPrQaNSNJ5o1wEl5mEYIh
3yi/ZaYok1+/C+2Pk7h9JDuUwPS7unPR6rdQVevTIcU25t+HDjAA0htodR1861wC
f40dunyFLE1BCkwHGT5xy+k1lBiPU/BNPrKvUByq79JBAZH503jfjyHvSfwynmFa
OrqxZE/Uvn54GOnflgpuUgqnP6HpnqgdFGZSvG70wKZo5Yq3PFGEC39O053FQNJW
Bz8=
=xG14
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ee5729dd-f714-45c6-a701-283cc4742288',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/8D/4F8OLMdGqeyyt80H/MTnk4FdMKjWKJYqYlmpGKEVJk
wfGsEO1+zl97jssjw0I03Qmodhu/szq7vOmZovQMHL/zisfgdPj1D5O6lOxrC7Nm
SRo7tatxQdaOX1Dc51nQsWjfY7JkFM5lO/1UTxtV1ZLCzSCBCTPs+2PqTxclRQIs
X+41dhWOU0GIMEMxYJQ2jIY7GuwJdLDnWQUJKUmXHHKXCcnGI9hObKGM6F+G9S6D
Kkv/S9URc4dU+ftS3vsHdNRQIAmgG4nL3IqEZVWkrLUy12wiZSTtc0n6LsZQsoPU
DquinvhzxkHGt7LOACAj9fAo7l1UW1wrqMEAVN0TnMZNfka/Nu+cOXt1Yy03EenZ
FSd7p9Cyunc34zByarjQhAR4BhCwVOKZmqzisvnIWe126nYHHOLXe4FvCQ0smTvS
9KRp2v7ElZIu0M4JawFYYhjHdkBxvgF8FmY+o7SLJneazxnYC/g6wxKFb0DA4vnO
gOkhN7MH+iCbmpZlAx5f97eZG0RrU8gSYMdC/YIyB1IR+v9u6FOYTizHXs6KdTfF
f3pCELg545NmLZotbuMSgk62GxiCWzQJEc/FQg2FHa2PZpMZICv8MlPFZylnunpQ
oRHEx0cwLfmBVTAeeDZ3Ate7+T7smgc7iaZOO6wHJvxYBg4sUnDwIj0Rgw7BgrrS
QwF4ghUTpy3rRr1uIzcIl2mmNHYVckys24zItuwCBvwIC8k4MFWp2aqvBiXZE5xl
f8cDIX08TehFyBC4G7zixoOEYNo=
=lCRP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eef2596a-92ec-4853-a5cc-ab5a9d3bd885',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAq3Tkm9benF01Kz4WH65IPGJXk6K4cWTI7PscWD4bnkcZ
UtMHs8W2gEITqApf2Xt2afbIXHDR8K/C2ZFqU4CHH3qFAdwJivDWyDo60sR1BOY6
pgp3tXSwspIKFK1O2QmDYTwWoWdsiC9KmEG1kWtN/jJuvsZTxp1R8ouQqaAiUUW8
c4V76Zi0Iba0kMfiAzvwLsW3d0TzEcY6j8VRyYOUqQroy2kDzKEtR3Y8p1W07+u7
oe7ZZQSEO0wfg1MnbnlX5rVbC9zG8mPotnPCO/ivDttHoeAPpalG1nf5R/0qPdi6
yPPtFpey6mybbnT60v0JbuPcT1Kscsc5nCkmLGwYL7r8T6OkrbeCQR/zRCuH6X8z
b/50X22AtmXDU4jEYxh1UTgO8lZLeb9vIgK/qyCzd1QBXNRfcYwaxvgD9EdWXFtl
5hk0MNgxzYyanj08TC1BEeB+NPCF5VBuTstXN40SpzxQFWYBTyu0gJy7sZusXsGT
2epYA+NNe/NVFZlgCdkr/jFJ53YN9AIp+KRtZUFH05JGG6Sh2d2whK4C3mPfLKCb
hhCKW304FGCHyCDClU8ZDEL1WGxcsA+Nseuaqkfx79FgjXNO0TK3mgga7CuDUUQp
B//WrPjkcZHjF5C0KCMq2hxt03sjFFqyokAef8e2HXXOF8TlOSg/We98jXosdKrS
QQEvQ8jscwxs81nHGUNTHiJMAO0vqh/FjHgNvcZI40GXEXHmzxUS5vTo4XDkbLW9
V3OArWowJoiT5PhMvuitR+cP
=k+Zq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f0b19c27-cb6f-4d96-a757-78ca0794d185',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//agWBfq/vdvHG3tjyWC6zmdi0P24UI6NnZBe/yPhBv7w0
itHA8N7XL+OpP1kYRjVUnVpJ6c/YTYX3WIWkoO+RFLgsdAzF7uy6ab5RDqhfLf1L
FOYLuA70RdLxqXiG9v1ybFhaOGS8vsBfvkWM9avylqEqeBhgE0g3ZfoZ05jikHW8
i2o+4mEtCee4/qc0f6hOLcyBvVhItmZaJt+2Vbtd3vjVM46NYhZOOIxYSBp4hZ5S
82uap3tgXMdGBFnK7H+pg/T6Kpp9SiazkDBYl40eRL9yxZu3l+UnGVbbMdB1JUOH
tZqVksAMHnLn8PaNbwdi0bW0DfhKrUaRlqKks85TRDoe5cXyG/EU9+MJn81c5sFN
xmq7lbhhZn3p0Smi57i3/r6FHs77lpXSedFbILiQPo8fcdu2BPM/X6/S3ABzysv9
93hMsZz097MUOmD2kXG0MPWsm5CmWVMqfqULtVsj98CNqgUWAJuAgXuo68+tNbH8
RU+Lho8HXjNvsTHuAJ5KLkk1pH3ba3YxYvafIFri8ny2TQvumNEtQ2GFfAvAJ+1u
OlC2XdI/S6vmdq6SeyZNnXLZlEtAryxwXi4e/rSDpk9yFDs1b1B4WIicw6voRs/Z
5rbkRmduZPDRZerF8SVeVgBBZgqNjpilnKbO+lhZ8Xvpsc9KBcOe4fIFvEm78P3S
QQEIR6os0yTB/a93nbdjUUqrvu1nh3+CqGR4t0LyJalZ6EevonG0A1Jra0UkUNz8
n/Bh8rIUxp5ZLKeveo58ww+h
=4o/f
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f6751ee3-594b-46b9-a971-26c910114bde',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAqlOeLnRnnNyam6PqTx32GdR0cJwwHOOKtq5UFVk43sSm
w3fsWB4u4fiB8HC4r1rNQpPSzXez6RKC/IySF7IST5Qr59MraEZ2KxVjan+5r0yA
hay9aG+jubDCp94RbZTaQWf4/OLrjr8fhm9t6db+CxYqcCebgt3G4GM9q2oHn5Jk
+yu8a3vBZDEg6jzS4sS7QDuUxRl8RGaEebaqLT7miNpyi/V2W3ye1jXLhr9iQLK7
UJpPdYz5+OlWbDQm8O5+TxMqtriho36mVR8xS+OXI7tEjB8YMqZd/GSnw1cmi1va
Wmk/mqPVGq9gPOB6i6KFs3gH+DrRsIbfSRYmKYY1r9QbX7TXC5EKR9D4+uraJR0C
l6JZMf1IfEZ00MfAv7MNC8ZffqCfKNFuVJYrTSXgVESHgBkFFclBGf/Io8PMqHuB
GvpGpS+s7TM7ZxOYxjn4qVIQ2b+d/b2b3G4dyNkPj+os2sy6Sb/k5b6hhbuuxHLk
GpomrVhsSewBwDMiYmCdyIeAYRNN2E0QsJHzQKxwn4tu1r8OvgAzFAS9DDwxkqIH
EV8AcD4Np3MLq2LvFi9YpsjJ+GTeNglMdp1YPEpt8FXNFR/1qk0vMYoYtKByX8Rn
qtVX2q1b5vEetZOI6/Z2pmrTx9/OC9D9rKAGBI8PEspBXW4PJ/X79rM5gPxHaTfS
RAFrqfw0t0d9XPIz68hVZo6iClrrFtI51v6oDxD9IjLPuziF47jHEflcGFN+jbuY
LwXpdHgesvMmTqrAaUI9YMhNtvwv
=EK3j
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f6790eba-f6f5-4d78-a967-4235896f5acd',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//RxWOeNaz8Wi8xfUTzoT8cvNqnNCHHM0PLOP29rbCwzUg
AFD47g19tH7M+JeF/ihAKXmUx5TY6xWGNRwMw0YfHaOZT/WRio8tEpmzrZ2WGQKU
LakYzs3ozRsfZVq+UVrtB9MWm3dUdw23QclgftFuV3eFSb7MilZnK9tyj8w97l7C
WMq7HZ3k/564cSTRe90r/4/vNaGsq4yyVMBVcnTh5JUQ8CBBWhINKldcXjId6hK9
TWRHUm7/+NAaI8FT4ZMCpYI4BZY5AjvUkF3BUhaVflpEtzciU2nI+r3zO2yzC05l
xkU/89QZ8dwOxyuT0mkxoKmat7NxLbqIuFvjAkZ9Toyuh1O4oJeh32soBa1BUYQQ
GTdZAJb/Be4nW6U3mrOSszCENiTeBVanFxdLo1/63pD/dWw4dw9rV7rYX92jeXxc
nnzt2P7FdsEsKuQ5PlpCWk8n9/E0Az7OTn7m7Geb7A8e6TSbsZWzxO4/XGQBmFoB
AMhDQ5dBYOtm4Jn7M7pliRHXQjedK4Kfg1ZxF9J/VFTf5k8WRAaLIaeOsALIN2Gf
GinCUJYqv/h1v9EraB8GZGbnm1iS9w+TJtgsutVa6PvSrnP5ge6jMdNB0sLj0gwm
BhsuZjKx1S9RAthut0x99FvAGr3GtAK1lzqXAGJYQoV+6IaXF93iboZSvLFI4TfS
RAFwnnkk1Pj5sDkbnvZziYUBRCqm2QSPikWz+HJUfDB8Ttwn+e9wRRVqMK2RZHWF
Mt0hXMWR2G/eJznyMCo7SGZTuKyA
=eM52
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f7bd94a7-4435-440d-a908-3a71bc014cbd',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAuVnIYuAV02nydPCdOuPrnR3MpDgtID8M76HeO9ZD+Q5C
X3T6dW8s/CzDKunvF84nHR22ru+zeewpwcWZITUcgO43vxiCqRIIk8DHHnIdHgio
Jl/ZBaa119nxlAo59Ee9e8guLCcrKdaJUV+XJv60wPf5XzD/yvczUu6PSSvf/fn8
Z93pxxpV/42H59p8Oxg3MHlgeATcMz9KHvThjRVxmCsLAv9vrD+UNro8CLxHySNK
SxuM6spLTy0c3aXcO3SM8W1XvvLJeCmhdj5HHWEgGC07LwDEI1SaQrP/UMxYIquo
Al3DO7ezeBEswyIUqePbcX0MGzYyTYnYYuVyV+qhsH0rSJrw4k38udYvGH0etheJ
rJkolNVsTzALhI+F2vS68aUXAC4wNi9KnTD3h3S2XfG/1qk9CCsdqzX9x7Tv/JFP
+FfKLyznMn97sN/aRbhogAOLB8wceVqO/IUb1Nwv/xLyOjgDfEjK2feeLhO4uOii
3mLd7yJ7vY2TMul2h0oqW+eHCsN1LWdTzZ85EABCpr9c/hiccJ1n/Ai6IKO9WxI8
t7TX2U+OShLpKjFoBsA7cA9XV551ea9UbfbS6NEWCofIfQx0IIyj/grHp16Bp0DR
nJ5zLup4pdzSixL/Cha/+sXqT6EyNJPtx9eX4LYdFRq+1qtUNpWoaeV3T6elzLHS
QwHnzjYLm61n9LMeSYsqi3tK7xh4jtzbilNO94IGu2Taj59tVBv5U7oALmoDJtKa
cgC0ecgAQIcXamoK24QzJOOWsRs=
=QeaE
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f89baea1-db9c-46a5-a149-3f4c2b54e93f',
			'user_id' => '27102e91-6880-3312-a4c5-db00c228820e',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxLCsYSvCuiAAQgAiJ9tcAL0PeZ/ZnfcshzeWYYM8EUjWlec2W8wPOVEWPqX
ScYbAI4NoPIy90305237bKpie8O41Hdk4M6j2NBLYq8duTcTbgmYYuZgtv2JoiYS
hrCRQ9QrEdfE6mO2VGwMvCUqohcWFXby8pX1rPitZYt6qm68AR6xwTBMQ4Cpz9X6
JlHCHKJ1UWZUbWfR1JJuDi+jwlkq7hil501RROVdzUT2bxAf5doqbQ6eRDZmDPtF
g/RhhF77pr7UQ5zjjyvKWLmr1nnzZx+zCZn/cOfFcZxOUzy0RofGlc4sBNBEgvOV
MW791ogOHXThMjNh21IAW8mzV4tTUJVQS+ibpi9A8dJDAcTwWiQXK12dkXsyQW2g
QunvJGRSNk4UNijw7SQ4KZvzFK5wF9nSesdapSPovtqU4azazwLnA1TUhNB6FFeS
pR0Oqg==
=8bmG
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => '27102e91-6880-3312-a4c5-db00c228820e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fb753fd5-0767-46ef-af88-ac351ea485a3',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAq7tv+spA7yOE8NwRytjeEGXmRncVgu8rpdZJBNQvrbG1
LfDMAj8WTvlRYQlXPVubJ6LPAMW+a+4L2TF4pYBzGGfqVvfe41gPPWu2hkBq/vhp
aEGOiyxXJ2jePe7s8nG5GLqus1LyIoabFfeMxHLbDyyQHnofCPN5WAgGVsej014x
87DdbLldNFrg+vdPwBlUvPOVpwxki95kbo3S/Hq2qbGJHgQ6WtUk6lOFMzqdUVgu
1bwx0uO3vm2Ptra5b1zUqRFlWT+t8cqZqF830HM4N0qw+WE8wWhSLcpGTx0Nkh2C
778/gsfosA7fIsLy754VZuEP2GWpeWNFK/AFoa2UiaKtyTsKZx80FsLl/gC1YQBq
vDzb2p6JIz3T1T2BD78h9zZBn5jiEnrsz1av0VWFndbhxfSGUr0Q+dqzLsN6Meik
Yra07XJkl7KNVTfn8Jh3Hf6ytJEy1sWBXYAfeRA/0GFW23mDr3etLvwM4vs3LYnA
hgGp8OdWMUa0mzAqhwJt6f5gjn2OxTkuyZ3F1TxLzxwEV6GvE2nflmKxT3wrKeYt
8FMmwkktdjRXZypT7dOT9j4QtOauY7BEi1dT1dwtjwCvRDShSwsN5C/dD+FQxF0f
cgjJ4dak6NawOD8RblTaO5XBft9GvTa3So3NIkiHRy7Ut+8fNauYLDMV6b4JPo7S
TQFubm7XFCeUr1K58enuvhHRH9WdHXnqJKBB9LeKwUMkRdGK3gr2po/jMH62I72G
usl6OwfXdK6N1PTnXhzXXgPO4l95Tt8equkd8/x4
=pU02
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:55',
			'modified' => '2017-02-21 18:52:55',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fdd39bf9-94dd-44a0-a0d1-7ed4b9d43439',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+OdBCkBgmd2YYqJ6KkYjROn3ac2Ud/0bUkx9KeJVGLQEE
/7M+7a0ShUqXgqTKV6/3rlI/lS7VqcFKiat12LiOKGDySBFi8/3JkbAoUyembWrm
7oN6OoDP2E8qk4raU/oamI3qbISdoGPDR3s3uUOowhC+jL8vagqZ7ZwaMCuKz+Tr
bPD/m7O4T76Nzexzdy//iIeyAWw8pcbujukL+awdKpyvBwuxtGE/fSdkWNLVuATH
OhLHnLhkoE82JTFH9jAkAQVD4uEryLC8q/MAhVbaUg275OKi7onxeel2DpB10A0g
O9gCMUTeC4Erz7WEElszruK2iqgM1nH6gffiRL3YEz1joEF269gQgMElFy0I0KaJ
o/HRG4/vUm3LF41KTHsmHZ4Xxo+iAGf+JZYEqW+ogveq5vUEQ3WqnoZfjZOoyk9R
MN75Uux1LMai40zgKziQr7s7XGTS6lwb1c+3U7AqYmjZZ5VVnpLqcoA5u96ndPGl
bPiSYZV1TVNA/J6WX9ifc1qxiAUifpdkfeZndCsOkbF1VhvQgZvsts7JOT5XXNac
U275K1/Spg2YHF4aILhFxILGAXZuArufSKK+56yUTi+G5LlCYJfmT14T73Kzcqq/
7v/wOsMm8k1V8CpfHu4bRvUmFweKRZ8WoZXkasjEVXDrDBVKwJbmqvfctL4zpvTS
QgGs2c7lOGQP9oefGrsjO9OOaMeed2xYmC0BakFD7Uu3I6vtOpwEk2rs7sQSWR72
zP5BB4b5GXwmUD2t8eUZMvkF3g==
=0L0a
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:56',
			'modified' => '2017-02-21 18:52:56',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ff72b14b-f53e-4928-aff1-08433f89f8d2',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+Nm/Cen8bb6rG6SCimx5suQWcV/rFyHamOOGNRH6jpymj
8VI/A4XR6K4KKU/D06XORbjo73lI0avHqmIGEhkFUb+aNEMetB/TH/U1aKNd5Ikp
ZqJFOEVM7ulnQtpfhiWZkhn0++HUpw9G9IzYYLlimUCRD+++kkkadsNbfyQFZqqh
rze2pVdzKWjN+GofvmbTc6FYcVPqD2nHCb9y19L2ybiDHIOhrHYmM87qIgy37Y/K
W6jPQmLe3hd1hHD1kbRyWmcHnfW+NNgHWP7W/PN8Pu7lTrEU8Kniiky7o/jx+QOD
Hw1QRbV4N0U7xMw6kfu+/fSLrZAAQNWNZFryJCtFuKRijTamI3EWfB8AR44gxKHu
atPbxVKQRIltxRBPMQY+5k/36WXYCZAKYs+yHEZUuL9VeImorUq+t8eRCqyNehTJ
Riuz4oAU+7RKwh7fRUFibWRL0h5RuDasZBpi+yswafk1DDrjGIoChA3NzOeHVps+
eX9D6MTJOo+fUt07q/Srj6FcHzxgWObT7cM6zJsLn6b1NLhXA7z4z6V1QYRYRgPN
i5SDB0MRcrAf12gm2SX511KF9lQiqFdlTk6oa9d4PkpMzc1e104Z9wWR942fFKBT
Z2b3yBle1Jwgcp3af4oDISZ6Ql7ZZIsp/kLC36g8keOveGV1i3lhOOo4aCCt48LS
QQG2SGrlkiEhtqsWsFJW0GtfQiNXhkCc0CtKyY3R4jvSH0n3cVd3uoDFpfAJ1rX8
OGHubMZHng5t/uLB/8bfYse0
=l048
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ffd27f39-0b2f-4f0e-a9b6-64c4b9006a10',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//SL+8uXeyMcWdUYG8261j8hNZwKEcDhNOLR+rIllAyBu8
TJ7/BUA41OrZ0owYtzSMDNElTuVfP8//K6KdHl49jryO9c57AMv3BZcGWYBkXEcV
pD6SAQEjxM5bd1jcoaxKm8bA0q5k+l2qdp2d1pbyHDwdVei5mQ577dXtJ+oOnlN6
JqVO5WTqsxlRgdVt++7B/64ptkBexuwYtLt8bnr7PhR2DWPfGUyRdESNyu6A7NDE
oeiEEHr79fni4dagX4c9bQWG1OwpBxgNerkl4lmNSPARNVZdUjadVnGSk43REpXZ
cSz+G/Q4+20qeI4G6u5vQNOx4UqpYc7aXykVxomYJYiSAlYudanHenlkhCIqdVCT
thFOQj9Lv1Yz+8IG79FKSkut3HArmNK3uVxC/Vh7fFJV1YGvv7Rxk2MiWI070zNj
QNb8Olfu1M27v8hw+oKIszrUohzFcqbWwwEmS4uw4XRnJmWqYQfJ5r6mxAppr1vU
SKstxLn1d0vSbVNrrVfLET6k/ReGkauYwBsXj8K0nYvIgZeOo9LqO3Uz+OvYm19S
E5EMbwbLfNmoaSDmphBRigsYuqurdGMN90G1t4ZIYfct4qxRL+cjRddCO0QuYUnY
piWY4dGd75zC9/ubXxgswmFEg2adt/alsfahA+5xeWXFU774sB9zO1AkBzpHx1HS
QQH0kTropKzjuaY6WSlIP6B+1VnPFi0WC9Y30VNpZ7ZUr0L6upxIfewd7VN6XFDH
Y54EcfULElgRTCIy/SMzjmtX
=eHwP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-21 18:52:57',
			'modified' => '2017-02-21 18:52:57',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
