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
			'id' => '064f3240-0895-4fa1-a66c-f7c76f9d0b2d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAApIS+RsZIm9ROWuw8hMSq0asWMbUlyDRi+1UL4QRwqlEn
l5mKA1ekRdju/6182ovkFf2AkglmAg/THa934eWKrRejhYwZg6IK2YLkztXnSYtx
aclW0DltrYfvTGgCm4v0SL+rc+/jgM5wflYZBTlHOmcpjPvXj5ZE16/um5a0invg
zAHNORkUuhLrGpCyykV+C/xUFF5uwJytHp0ezXhBjkCinJD2ptkIjS387JEIj97T
1hWLCAkZe3YWBzCvC9DA7aSzeeQ6S93YrruXsA8oxYPuL/FnE8cjnaHM+nh77PiC
PRApmbO8kLu4HEMUnxOFj89FKHuTDsgLP9e1T5ZQ5tGQGYR/Nyzhsw0RsvkFnuwF
MgbQ8QomhjtDMhdiZavHWkYaYfT9K1c+1/jsMsltin3bvkyTBc2kHAHgH9jNZzHe
kpsOQxMaqLcxL+QbzfKDFMHGab/W+JMyLRWL6ZKOh9LK8UMhEAmgoXCpcvGypmtW
NMbWC/7BdBeia3aNaTTVvkNRqMnFYHdsrlC4uMxTIyY6eq2vK8F40THf5VBGQZug
QtHSJh1A7QLmKv79sfkJKW0Df111FyWZZfrmqRYCXY82BFMCPyMZhiYnDjcE4/0E
Dyc2d9TAaznHUrcMG/pzfn+UqvoLf5MgOJhd2sw5powjMMoBBZXr8zffHNkc4nHS
QQGj2M88vo0c69UQ7yGwUT1/krp23k/5ROkK711Ih6Ipjknz2U0owJKGynXIfkYR
Fp7imCV7NqKOVy99g0JgIGQh
=IqAo
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '07b63dbe-4631-4cc7-ae95-0c8bb73b867a',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//e+hwXxW0+5i0g1St06zdQaONwxw+vETJN5jI9B6McBOl
cRpuPMF0hv/mVNB2511L3wDZ8X9iAx4/MhsqjRHEhSMYlvxDPkqvgXpgBu6PnKXU
NUoY/ruHcNJtemoKR70UcIHlDczLXRH3HbmZQ95g88IeXW6BU2UY+igYHYp5sp4U
vPiQwpFFHbiV9KKcxyEkO3zdtWHlT3vs1OsugkR8IDgHORAU+ksm1Z0n9V8xWfXN
IclVTQgv2Auvm+6tYFTI0J3PVpvlf3HeYujUaQzbmp6nTakXeDfsaNup1I5Fuplt
hKodsG/UPXOmjTJVTvrKCrnmNH3kBqucj/+t+Thn+zoEJEgGq/Zv6ZSFu8Ix6iyD
b5CjSAaYE6VdfNYhgjOz5IPv1Kju4wiAyKtEgmcSe1jJPExgHoeKgtIvgHpMI6BG
irV210wOai+lxTkW5SSY9Nixk6cmTPCIfz0rNnzDXKO/92zUTw9518Y9AP4IxxW6
NXqwRsPgitQi2452RQtmaT2nEODh+/DDtY/vyTS32ugnrieZ5rs+l8HefJdr4pRx
Cesh5HYiIGCHjTkTxOGB1bYcOskUHg0oShRsBgCILBONWj66VKO/0o9KlEmW7Wd2
22Z7S9SBOSpUl1BnnxDMNQiQG0JE6rl8T2jJWNVbvcJC8iA4BXxdtVTLO3xrXjzS
TQEg8A9DnQlNlcKIdxrTqRAYbIr9flMCxteA5sz4NB9NlZJtAd657TpczJtN6k/L
juMOzPaUCApjFxoafeplEiQ7/9qIpMG2LP/IaTmA
=Z/yO
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '097f9b59-f3dc-4af8-a33a-adbbeda5491b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+LNc9WjJUhM4SNE95NjkRdrM53RsQzqtbH/RDrtjHzN0B
7+36b/ftvkzxScN3ngt6TMZygsCXEvtliIc/vN9WM8yuDfvyDWjpjnHuen7+OuU/
ZbSQ6rZNL86HLi/grw9qQs92lpUyylLXTUD1B3ceYjHWeG7wapNtayb/boygL9G9
ozhl0P2d6D3i+sfJWlF5mKBsxUJzWBvBhd71f0S/Uq2R6wFCI7jUxB0xtIlt25bx
iGUWbMaTcSM0AwxSs3lgd1ppzY4OX8ojHKQy4yOMTalPiRvINE40EddhEjRfIyPe
Msir5FUV9qci2GPOcnGh98A41i9y8kr/eOJKQN75Wi+zmcCEtopQN1y2DpQCbUWB
aQ5NcwnuiHLRjHll7HuHdegkHdsMc/YgI2Faw/EJ2AoPwuEHamCgxOdnapv7ZO2d
CQE2zXy1c1g2IirDh/BM+U/zUallXR6xyO7k0kqCFMWBZ0fT9+DohXdCm+wqUd6v
i1emlA8R6FW19jMGPJX+E2zrs+OzvKydN3+f42JV8D74Ej5BP91Y6sVkpg/4sfNj
G1ABPboKX35+8L8tqLk+J5VllzXMRxxv3+f/1nHUPi1p8Zw2KeHNjNGwK7P08647
CWcMihUMKpazy4sUPqJvKJI38PA9njFVNBEogb/WnKCiySeQOmzPPzxWMWzChAPS
PgHh33NF6ybJ+531S//K4FH+PnOJ6Vehi7DVfBl6DPbd5NA6jGDfFX4te/evZhg0
hnrx50LhiGbG04XHT36N
=Edd7
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0afdf6dd-2355-4212-a03f-779e3baf8740',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//UjZP6AU4xJMhcA4ySlR6ltJETAkWIw6+UzsKLP7C5FZC
WtR6H/pS7TMYbjra5edqKFG43pM3yL1xodQs8hESF95EJ51eAO1S796mkHrqh+ap
7ndDt6gawOnRf73IrANFwl4HPrWvS1EK+4L3gQdHgUgSORX7FWEtjShbygpzuSbH
YrrWJ2zFoLmDL57AnKrr9gek8SUP4Yw3K9fdComh7G3FUngc2BN6Tys/+OS1P/z4
r0RnKZUQHeQ9PTyqzAUuyAmiMS3qOsefUH7xxQY5zPDUSAs+Q/9ukYFJzvKahxqX
ODGOgTA9GN2u0JePZh00YK8ArGt3d3CDemOHrcMYCHK8bb3Myi+i3l+Dfd0sKrv9
JyFm8fzMoVpo9IwV36jRWyupfazf7OQW1ygMxlgNeRSSDTE8s2BoJOMYhueXpaVg
lxXrgZkfhaY/WyTDwjVp9B0LpQ9oZet5j5Y/PtfnLLiN3G7HzQin/T39gxhy/2p4
LMnlZag3/stJFECPmJQjWaiIQSEYAOOCYf+skLoXcHxosWhZvscDvJXVRFt1osCb
4QbZUIOxC7kKVdaCqK6karrA+JlPxIZ4wFww6H8zQAZjjmefF7OhwkyjM0lXnxny
fjlli7Rd7BPadWmGK9W42234Ncb8/GrZWeQvycpo7LNdbb4gQ7TDV69edBWH5RvS
QwEcohRBM2bkr/BeJ7XOaMYg19bwlJdJeZHGroJLrYo9swJ2JE2jcgzz+00TLNQH
/u+SryfLJJNiP4OD3Z1yrdEC8so=
=Jubw
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1509f1a2-8cb6-4a36-a7dc-e0e3ba5eff8e',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+I/NRdKpJQS8C+NbwByG7Gq1BGRgBWZJ0Qoq4pZLmompi
aXpufxPJTfgXCoQEEjVQ7ocNMjKpgtpoogxBDy/tY47XSJBIV20ymPj/9ZSwtq2F
5kyCtWkiS1jcnOvkE5nF6EZQ/pywf/Plvb04XOgaQv9XFdK0DhDFymlg0b0ud50v
F8PMJrKycP+3r5mZoRtiVOSp0PLNvb4lt8ZMikUrtjOspDnPrDctPCh4eEqc8n5r
j98jiD276KTC6oTn85uTm41Vfsr0dT6cVuMx6m9IRLCbSwrwG2ASvia5Mo4wOl++
YF9uScpgWZbMKvxX0bmHYpCYewVupuDSxAaytuWJVDN+RpKCDU3jIHxIpPeHkbdP
jFU7Rxs0o57axHGq5IAbcbJ6QZr772qWq0z6L7rQY2J3abxtYrjoJnCG+VRUTXNH
+FNuoyD2DkU79mOzPXjPnlDgJR3SQURYBe08D7DRDuTpNOU+mFdF76rr6OCd1UJI
xIazH4uImh20Y70B/cCn1QK5MG3mQE6awocwdzjgdTsgUmHYEEpEjl37aPg3BTOH
AW8pRQIhh5N+cPXMaUqFI5KGUYGig9cHmfSVUWJfSkJaBLnzTYVILuGHe30X5HaF
BJekNABbXd0A5aC+EXK8YPFyhybFvXqpxKvxosS9pRTZK9XgIBPTCaHmsZ2Suw7S
QgHnArXdSuqmUFZQuQzRhHCyDZd2VDKKDdGSCqGpSP+H1y7wxHWHjcgcM8g1g6G9
PQQcGfKFkf6QsYn4kuE8r56TSw==
=8rZB
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '193d5d70-2d8f-4600-a8cd-53a4e9287781',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+M1iBhk7fPFuxyZUhEyaKaF1APKZjaBb2aOY1JGNITJxM
jMuIuenfiax67feWqcf3od6LeeueDoMv6oIwZKjpcIyM2cIM6T/OgE+kfTr5Lh7/
0O52vpR26+eXgpOGpkM53P5LV894gT93bJZRhkYFqfBNjkAMxPIJPuXqv2gRLzLx
Nu2bNUO35XHnvGM32mSlD9uj7/wn222TJQM+M7R1YokEWTz1SCLoB5cfiIoRDs+Z
aAPPL0Y3hmKWXhaMN3uMgZjWwIrh0SweBwxrim/1ouGTFO2YPZfcb6Jx7vvM0pF8
+MMGqLs5527JHXphMyXiEFK29DG3BezJenAF67k6lOSHZWTiU2J6LUyjam3x0Q7s
0zGNqWbbKJ6p5KgfzS+ETQTKNKNg0ojmvbhSdcn5flaQzBr34b9eTvdADaxJ+rzU
geBCeKwh6OvHKEAV6kERjTWivxGYQdeCHNBBPO0AJGcLnnuTk4nnuWyW33HYjkyq
VA6jbXK4Dt+lw7Ku9zKcE39yfSY3EmX+oE/e2Fw0SuzX9m+fYBjqxDKvhNZDZfrv
IX7Bq1p6ikfPhMSusBW3VpwNxtXv9hDfK4LSHZ1Lc5FndeNkkWCbnd5DPN4w0yMu
kzmTpuZ33iXSFycdTsHPg/mbpUYzITUIUOFsyIDTsMrKeZVWw3WAdJl2zOef6KfS
TQFltR3qqhzYAr91fgwx4Fq9UADeQfDDSTX4p05rMrD2wdVTigRkpJLoEg8wr1K/
u5m7W7Yf8jrwVtzXMkzCXxQla0nsBLCOgypcd+FI
=o1GA
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1a57d899-6ba2-48d5-a1e8-b0c235240756',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+LFoHnnP9nWDge4Gnr7pTgZo8yExV2WWalp6LuhdlMOnF
6r4hCIhou8ejNXRNGGo+Ysl0LLwaagSKmDYrMT4sjC73NrFyPgETxxecttCmp0Yg
4fw+k1vyq4KxcjVZQH54ztl1yQsoc8rSY3obQRc1CWOGrA+gnyJaSJH0IzuP7/NE
3PCL5XyLk2nUsDJyhn0Bq4q4craicBvtYKpOwhD0r5Ugpb6p5AvDFXKu2TiDbbs/
Cl8Tl78KfFjolx2rgG+VGlC3liZ8EwFQlhvtEZMdkgwIPmzSJ6FPylcMFjUhcR8j
EgKGNZZQm1vxWvYdW9rpB++xWQAXw8iFonq4hoXhmsipnpjemy7wzq3iSFc9E0Vj
vgLO6gBQa50tQuuUnmPZq9rm7S1w7eRpME5Ds749XxWsxwfcOf3MAwM5kGhPOwpE
2A3RmCK0HqxMi3831v6S1QNF2uKKm/yfcV4iFxStDCTtZr6uiHNWRbtiv1LPOTgp
hwbptxR1DJd3SkzokSScTd7ndfI2YUOQ/kYSXEEdxyOiltpEXglNmz0Uz1t+fPg6
wyTruY0pdqGDXy1NjhRj2khuIpeEFMdjq/BXCdbI0x0mcGtxba2HjE2QlXrIBDb1
69PQnlZZUi0F9XkKJ69wVnFGngx5mruXfTHRq32s0i8W//pIMs/O6qvWhCjEMCPS
RQHWPp4t7NXipOLNFaohCPwoDNses6PDIN4Jlx07Xzi89QuAHuC8DHVIkAWbXw8j
fIaVG9lMGTjdhXmPcKXoQoo77nqtrw==
=/Iq9
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1b5cf495-49e5-4e75-a368-d307585ae6c3',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+Jfb1oy3cZCg7q51LF9ILq531SbYrDgIHQNzWwyorIQq2
iFfR2O7OLv2JDO30yRu3YUqri1fqYeSH/njwFi+fvFz7tN2x6UQJahZO9gXn6vT8
vC8S42doMySeLZWMAGzW8RLiSWj4s9Nzbzd7Z/P8xzxBYSsE+JlHrVcMbxEvzNCB
iHYhuvjQSBQLVOdxrYxUueyMwBONHElUfbszsqq4i4o1BEPjunx2qQbibKcy6Vtf
O/BqOorjCqLWsLw6zjrTkrh4gb8xYuJB/CMIjfqdinNgOdPRZfNVBM3FxCMxh8t1
qwm8dFwpTk/x7YisiWEeYAD+pfxGEonWH4D83cy9MpVPCQvwqBX/o4R4XlOBtZCl
FNuAC5xzUztpQe0rL42QZ/aByaCSjHrZmkcdZ+1Bskg79r6JIofalnkowYvG1fgJ
773qc9glyvMc9Vl8fNxln9P2Fdg88e3MgySf3Vs1aqeDVCPzsEJ5VqX92Bl20GC4
oqL5YnCOZJENyuk7/1IwRKkVQFlBQLfEbBVfeVKj0bLNwiOmij/md6PVNNwk3oLO
1rsoVfhI29cQSnk3tpIbzysdaKbQ3x8bOZYxdkVAtDT7IWhP1SRjE0EonrcXRg7X
XNADwmjH4IgMWBrnXOlVRFO9Jq8O2TEvECeMGnJaqj5oZJr8vD+wORu2UZN7O73S
QwEF/5ueurNn6yxz7TtMJ98183gSlogJgETtzrb4vIh0esN3FXobQ7fE8CBr+FO9
pT0WUybMNDjBXSxUr80AmTgeN1o=
=+Eju
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '241f4fed-1f2e-4b9e-acf2-22375addff9c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/5AaBI8aX0WzrPN5GtKP3C5i4GRkBkkGcDN8rVhzNeGXrf
U6cSiEHA/mBzXk+AwYNDAxHPli1VElk3B5sG65FkpGt/eNFlbJc4ZUHSzysiCC6/
UDu5JVA9VRwHPajcuiM7/REZnWPh0W4uvph1jiPg1lmQqADs2bTPWpSRMibL6ClR
uzfNGZENqEgGyYDchGoqor9OYu2PyhLQEk3pCB9RvUZ0x5KhadlZKZyt143FWRPW
y+Rde352d0SMbxu+5Np0T2XtdwHXPhPkDmRI4E4NPyfuHyh0MbVwUawxOdPQY1/K
kplZIBOjgRMsLQCw/QuwZVmgFyqZxEaB/tuaS/ga0Z4aIUXCU+YI7+NlHyOb1ZbI
hRwJ24ubRJQcJlw/zoNVF8Fnut/Tzy6+PHDZ7bMPdeD8XOyUO4k7xoLS+CJz0KLP
dpSU/7SGk1wO1KL6HB+bpGFvLwZzlfgIUZ90A/zFTioJYcwqmfWlyUpps8aou14e
9xvpR7T8TRJbHizARXAjAJVE1/wc8IPRDdkg4wXYITG3BfP5ez2Gb/b0eiod88JH
OyD9pf89MhgYdqjtFnSUacy/Sr9QdPfkDxKSzLcawWphzRYXJaNbDKs8pnTTV7Bp
J2aO2etIFg0uqGB1Ad4g+7O910xFm8G0xuUBz4UIR2HjiSupF37PmbKSiyoL+0jS
PQHVaASBseX9hVU7h60tTFm8gwChYFW034Nvv3HFHdOA3c3i0Qfm+7UqmZT1H+kg
f+RsyWOVGNlyJWqbG6o=
=yjm7
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2477fcb2-d2dd-4938-a48c-677ab596f403',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//X16S8xRm8G5GOYQTVZZyBzQ/7Js7nwVe8llwcHNeSigH
Qjpow3PsNylMhC27odazQQzAFG7cFpA2iMjpfw8/TYbP9Xuopyo51qYJEBMnFFkn
6p4fmYD5Vs/bzOO/wbMdaljCm40Rq7BEYuWVNNJNDuxpDvMt7zdpTdf8cSp+Ah2y
+PH38/kBMqGEn7Hq0ne+djUf7Fd4j8+u919MV1YdMXUzDJkVnTQfmipZ4k8203P0
G/Xj870bc6L3fcmPI2tBzNdGl34+pevHMnI2x2i0tZZIzUfaTWPiHGlELy56F4t9
iSXBmcrDQ/tHi1uHqP5p+XQcEjQIcoGq2O5z9on2EW0+0GOfjSDA11tS8QZV78cX
pUImLtaxaN3A+Fzj7XfgoLKqFXdNa1ZZuqoAHkdjh5GRNWsjQiewQLHjOsFcUVu9
r9kGPSmCmIys1Sd0rpkbo3YbHFf/Jh678/rubztuxV81BDHIe1BVwLqBcakQaQbx
xsJi5quSkpxxQ/JUsECam2Vqo0e2wdn3DS/0gq8TtVDjsUd70Wsvd9MtPCSdzI8B
r+IKVfl7eM7/4Kxa9NGDsff11EXOnV/I0PzHhL4Gqk2v/L4oJoM6WQt93spRd8A2
RzvRjaKpXremDnR6qRRqpWgh1jOTtYeUf/PWrx86xRiTPrJXtY8DtqViZJguUXbS
QQGMmK/PboMAP7v8OwPo6u8JInX/8qZhPMDOvCCHKbNj4V9f4TBNP5WfhMvK5ELe
58ZurpvjvdhQJdYdQqVQGkln
=1hDq
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '253a1872-d28d-4ae0-a135-cc604e816376',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAiiYrycDoRxnSFeo0Eff++jKPmAP6KuETz2cmMTRgRv5B
n+flbaezMERBAa87eHm3P7ltCVyrtrZ9f9h24s8WuAzciQe5v5QFppOH2TxjxbWo
Q+N+GodW1LSciMv77AVT33+jwF6yg/G+YJUkXziBXkxRmgrOkwtMIY9cxDG9Dla/
MCSce0WCxoCabGifByteCc+30LAJK2wdHbzwaoDxfGyxyUPLP6SIEosMVMcqNdhy
zwEzoUKy4ZqMK86kmCdKAHXLhyhH5EPuZUmJUc4HTzTejx7sqjLeITnZCrHxnEcG
XTGxrK3jrzaX5/sKNShZ490dVy7W6xHK61nrNWjBQEFbHKPUcliyWSdnZkSwBuqH
Qp+bcPlKMffSHh4Nwa3uJli4SApdjM+sYrFZ/zLRb5F4QvXUMBwSo5VXCRvxatl3
uRrzPUi/6MjHjifeQs8de+CPaJYGhPMrfQsIlQWSLROyQvfH7WPMnigeg5iMIh0B
rlDNyG2uUqvdobERN40uwLKz3k/Vr6l3PUl1jVYyq7noW98y96vC5In3cW2gOfFV
bstY64D1/chNsbR4/I7gwkeonuxt7gvHV+u+GBiKC3luU2+1g4hxS2BQL/MWFr7t
CXGLzFkCAdsJK2+ZbFRhjReeGMFE24L0cYEUlQ195flYZTNJCWwKsrBuPlnu8bbS
UgGkesVoER39zGoPXfaVoKad0LKNYzgxcyOn9yB0gse2dN1VlxVTYoG/+lil3n2M
Rg0wjmv4W6lroyVO+jhkiwMAQxveLVvL9y0/deqajIxIy74=
=91NK
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '26a5e04f-21d3-4439-a193-53ba6475d5d0',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//f2K6cRxLjKcgwyQjGUKGvrGvMKuJ6KnEjxawQYI5ZFOC
Csvlv3iBhCd9Tbq1Z91gP+0+kw+oUdrJnng+fxNv5ZS0N9vTAMOuBt9jUzVae4Dw
VnovfTLHPUN2x0nVdFKXs1W/ekuExeRDL7NflN91aR3EbDTVBpi1WtWp6+kJBbhs
HfRehdXBkYdtqxJjWP3ElSRXjvhXnfiKd05DMajiCUEQCk0JgL+Zz0UOKO2UYwPp
N8SHeEWW3Alfk0JD++Xse+688ikrrSplFYiLrBeV/bEpYWIjdceKi+5baerhDGcT
+nN5BRxxP60xDKAUDbbGjPLI0D4tiFvLPsOPbmOUA60y0r2584o4fMlXx2jXrn37
FaNH75sqvF6DF8C/dar1ZhCgJwil1whZNXKgDnpiOSXwHi99ZWIrQuJwR7DNGXh3
hbGUc4hrxV6GQ33bWFMO/liIpUTiF9PTFoSDleIyaJgH+GEWnjlHicofkYA2ECON
4xkHH3xves1lAxqfItQL6wWqBSXnWK9qp9QjnntqbjGuA4J1rvbsxgSfSBOVfTy8
btqrDkMOyw4WvKBFjMCTy/RJiqLepcetZOcpteccKQ90EqGZAB05PX3TIJRKsS3W
U9rJzoxMW/LiTQYxNyLlPHxpJ1S8hZfjLSeGuGm+lVPJybYf6L+D70uEZU9SZmrS
UgFM25qY7sGTFS4u6+VTtTubK8vBcWv4SDuFzvwt3ZFuGN0Ns937ODhXTXTWfgAA
aXv7gVuzt4Ti1Rc08+12ksZSuWfx3Vdximchr/tpQwcY5gQ=
=nj87
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '271e6444-9888-4efd-a3b9-3f2f68d92965',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//easOLILs38HNTWQGQew5IMzksOYlVW9iTpmpHLxMMTU0
AH0wx6pXKoGQ4R9E8X882nmQHL27Jof5YZCsE8bILtNN2A+T4CFn8utqR9PI68yx
AtHN4X/xpEPmOue4Tz42oVdSnr2eYH3FVPd6v9UhgxuQwpjM+8t9WOKXF2rfz3zG
MrNJ0R10pWfU2LVf/VRSshsQNyS7fnFi8F320KeO95hD8N0WTflP95gw32oU+NeQ
wFWfRo1EyZyyhyfA1ACkptDj9XerOjPvQj4/L8E/7SksmcPr3IMbCAXxeEOiNvT4
HS6sZOelLx6wMKIRPoQKNEphHlkYQGifeH0jNWt69/xqE/1wT39wJqL97dt8kbKE
hJQKUV1gu3Iic/qXSQRziM83SxDNphZ79+g98fP1QIq+HouGIQdpO9+ZqUhsRDZ+
EB46v3rTNv1RR7JCQOo2vdOpBGQuIpOqEEBJZRyZyrycSCpRaXqiEU9QNr4w2M4U
xr4KlHZHPnKwqzNiIvOPKhWvz6tYC0qAdieF5ZKVkvcV9Dd+17W5Gb6YHGSsV0pl
DuhLoXlZgAhOWLYyi1zVgrtGYSJhKaAcZzixGyE1Q6GLQroLFLi3kDTKGgNOCoXS
n44IDILMY/ih5nkB9pBw+QSGHTEv31+ZWQOH8s016vORnYtJcHgDnknE+izDjFfS
QwGJfRAorm3dYF2EdDDe7PO5mudOKrS8Dcgi3iO2bZI1iUnh7r4ZDjeGz6fRFT8f
hwjb0ouE5ZIJGTKvusHjxujQnuE=
=KHja
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '274fad6c-9adb-457c-a91b-3510580630ce',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//SLGgCuZvGsMcvyRK9I4K5aMiUZ4kusF4ivl4+oLz18pX
4OXOH326is4k6RV6vud5hkMWI3BvJnZMoakxtQmQo4wVlPiF+xb1Dxi8mqvlwJvt
GCDfR25FjI2SPLoNgQQ8l0RbTJKvuRJMNOlnlr4NgXafK9rn5Bnr22rTu6vppnZw
rbzVRkex2y1GAktxlY36t6NUCoMJMBSLp5vY1mCepOayPJFr5DP3c3XoX6vjKQ0C
3guu6N+sHP2E7xTPBhK1oZWm6BRFAZXD1u2MpSQtW/IDW4p8tSTL2bkLtw6C6MND
3kKv8ZwgjAUSbwbRcnT7UyIzGbB/msLz8YTdDXPPMLs1iXO+CIEr+fe85k3T+Z4h
srRuW1TdgVYDeA3aMsXiH4dbfvpdEhzmlrMn0zHRs5CdnbiSZXwcgx8W7NoXUySM
w4Oian8xI70MiRGsTRWy3j7wqwAPZyYr1HBLaMcQQDW+jLtm1GNlVYMpDbi3jLgP
27tsAKXWeQ2PuBRL4fUlqGDe8QLXMTxeD6sqbEt4SGq9znTmLG4P/NODOoMFyRAQ
Cc6lWPHjLfJuPBgTPOjXKwvgaqdytnkqxj4/u6FLb+2HJ0q6FwhLSBf2KYPKUHno
5VfRLA8aa2K2nxV4omiU2Ir+JY+cg3kpYEw0wAh8EBXxaNsK/0wImul4scKHBjzS
QAE2oU2JTtiOtCEEY0NvZ9F2gJr41Ylq5GAuE25VLy9v+IN5u+ffwe8rREVlsA3i
U64Xd0N5c2hAL0cMCMAzd6o=
=siRL
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '28c9df3b-aadc-4f15-a92a-e5d90975efd5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAqktq9B8Rzjw78ZzkcTWJqBle9csB4HIcmc+cgiRFyAv7
cxmbrY4kbreM/BzMRxpyDByHZZbFsbUPoMbGONnLPMz5VmWw5ZF6l+scQtWvqemK
1RpALkpWJRTatjxyCadriAGe146eYZ56tUnJklAEubb+DGKbyWPjGIHYhz8YINww
Nrpm6XzzqisKeWqyNIKg0vRkFQ9Xii0oOkkuBXUo6hgbTiWqI/LgDuYQkzARtnT7
5iXExzWY8fQ3IWC6BkIBZhBmnqyD34dpK7uUG6789Vf62zQctQ8kGIk0dhTluwHL
bbO2lN1VEO5KcQpDgsCY2FcUxn3p8W5MDBMaYD5yoy3CkAE505IK2+qJdwSfpogr
/ue9nSvCoC0WKE1B3x0kFZzC8VRbplxdv33n8kzBlIcLwZ5oQf8qDaDI9Y30PsgQ
DNaa07P8VV7MQoFfQ/MXGiXE932qKM/CYYgG7aBSOFfACcnRlF8/kkLLXhUKw/R5
ZQjBiJse0+mE/vupGRlJ29IPV+WfRpU+8XSV5lE2oEZ6Kdw63u8DoZrN5lcNrt80
+JHzhPL7JoY7Nv8j2W69T495Cdbb1kMBBGTwitPKaO85lLm/mmMg0Uat9wSq1XGl
XBGh/d4Y+1NyVONmbdFx2TPw0mcPtZRN4AyaCFCqoSgHlPfS4QAzNOU8McPJaS7S
QgFVe3SYPCThAxqOBA5AilJc87luQ6mjahGKg1h5DGajMyVpEvw+TFyLfaIM0wrQ
K6XauEtO4kdKsLCBq7ylTTyf+w==
=Yidw
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2f6f17fb-579b-4841-a83e-5720f74e590f',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9E0QeMhZ0LIoAaX+whiwWkxPkK0nY2LKpVivUHzw5Q0Ta
msdqGXGE71DQygh6KGl+1624cJbWrOG0oKzo8pe3wkwhd9Kw59EC2xWt1xxw8YdF
UCNkdhDduHorH0UIZlPQPd0aD80L/uPurtnabZsp4m/Ye0eAvO+o8ybMULxwwHvW
a2+RRvgNu9oo8e5GdPns5U7CQTsXuxAKsp38yXIQLwGzcNsoYAYsADsUW60tK7sg
UNslJfhYqC1ZwN8AbYpaesEMwRED9bi0IjYjViP9Xgs16KFFr4P1LjbHW6fOxZeC
iVNRHvHy4dRqN6Nmy41O7AwDEQvocbI7xvwgcJISNt+mwr4dMCGj5/DaIGg/ntje
cftK9O1RkfWBkP7MWtpMykMb5GlGBgWSkUYgdQ6/xA4T/QmdpT7egt7D8D0AtpHQ
WS8xJC+8tBSzLfQelrbNOjAZVGLZI/p9ffkgSDrUKEeC/inCdmrEBX0iA+W/Xtp5
GO95UTQ7UDEN3M/2Cj/DMNfzPfrJrOBeyRxXuyFoV3Qxzfyx7CJ35YMAGN7FSe1b
risZDnlUo3wQ58Un5Wp9xmFViJ5S6h3rQSYg+3dhZRa/wqOenGXXQRnFV09v1wBq
s36OyXOOJKKF3JgjMlKaxqI2ADmdWw3gj1OaJEBuwE2k7tALCfUJVo1OMCmQNlLS
QQGHX5xEDra9ijp4RdJ3ABWFIrgcYucEZuP5siGR3c6GBbkvjSQBDGdoO8GchGbR
/bDydkVUZleQfVb77z5seQP+
=gyWx
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2fe4e688-8b59-41a3-a941-174cd3b20dc8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAi7SFRlTWwvyEIJ0zzlzP77cAe2TZfC+6dWK8pYDLWzBy
nSgAkOPkTdsZXdlip80VKTEwvGOhbdu5rJzBD4WsRDRMrDZuLamZGM4YCwkR+p6k
0NmXL9MuRfW9Ot53Hrr+DK0iduKWIgvYJrxkPoOajzd2HoF7K0WU21Ey4+B+LEql
8wWCs9HG/r2zNwW2gHuxc2mx3WjBeXlJHFab+hmtZcGmu0lRx2afPqO6mSlGNGwu
W9ido4hQhFe9dV+R5GKcxmRBws4957GZnAQDNGMPP5tumb4jA1yfuCc4/KfmJ5lp
hexN0StB8wEtS7n4fBrrEbEC/LCV3z8zbfRPKxZvhIC5E2vMC/IBkT8XVdzFDYWJ
80bz1JwMRrwfZGv3kpDxUq2oRffgUb8FSdunJSKIyu7f/gCW9qm+EKS66mz1fHrn
T7hsYmtHA1OHD0CHR4JXRGSdp+dA8TO5ZWMxeedGPieolw2yQlPaRyIz5xhhU3ta
kRjlfsplfpRfMp1BUN6mh6uD2z8UenYpFRdxAU9GE31GuGzMZXPcuXgZ6q+zKLmr
TROJ19GHFafqRA5xCB3aJL3AgYvxE7ZTIYKHooCLCsy3fBGVVgx1HHsf3vsUkXLQ
K2sFFSfbWaywAQOcYHP7pQeam8hCNAGUg30rVKDjEdzrADtqxHZOopTJOgZ0lAfS
QQEIQ5I7dbbBeFlE3uI8F6iP2E+GJv9T+SDLktL+NhiJtzHoIYoM6g9yaJh/K5fG
8fDl/08ONCYQq0HIGQqF3Wwi
=zjvk
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3351338e-b1bc-41bb-a302-b6130b16d036',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//W9VGct0BlD627K8rdpbZFHXV+XqE3yPAvPQwM9z0hxlt
oHoOGSAGpMjPBH4Ga90XOG+sN1mas7IgVL+I3u2VzPHvEA1KSWzh1q5Wzg6lh48q
EaA7ESQ5Cbfi6/Bs2kpUQ45exTGWWSEvfE4xHQnxqjjVWSa9W6Decb4vs7iu1P+N
1ZBTDU4bnayUQV+8bn6uwJnP0hWY1CVXvYEZZfp0Giu5GViwt6ldTLFYGgAo0CE5
OBHtvJhok8yaL2+qsXWbCplgfLHmIgjXDNTNL6y1oLqz8XBTlN4JuHZUDC/QojUl
YpTYaQL/iGKzGMPhbgVoLahbzfwTddDu4MRcx//PZnkPkdTVJ4kQafBZwFiP6BVL
hRF9dxo8Di8mlu+KAvoZfRuUYLxJxhr+TwXewMtp4kJsjkb/f9mfBAyvLXWYr/8/
VzCTU5BEv5l4wRQgx0iVuedV4zA1igmz7TKvhn9+k8G4DwE9amLKEKpw/z476KDq
IGMXL2ISuD9YNfmAt23zUC5ccuWRpH5VTWf/h3U/RZJQsDZX2gRVYjKpPUaKOchA
sJ9TrZJwikUi9xIg1i5g93S1l055ptyr8D5urTypnXegs9ZlRuSl4DqPkDDPzxZI
0i+MuP6ibf/S5Tt0eXZtInt5phVCyuT6GFG1wTTHpgnQddTHa+pqLfgKc8r3e1DS
PQGTmN52fMt0Px6MMcfnrMk7KHrK68akRnZQ2S+4fCevPBqm3vkrF1zXC1x9z+B4
1NhuER6He/h3N43rkdk=
=mcfE
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '42d63517-9356-47df-a2de-537aaab3d389',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAo/EocPYGBFmmSj7UARFvWEeexMV1W0S+2aYMAA5ij6NS
FZu+hOB2MOmAZ/M/j+DGiJAKYVBP7Nr5vOgY8iKZwO5Az7WfNKM6HFDHdB+m3drh
kxMF/pewu18RjRWapUwl8WlS7tMRM9PAkuyt2UvLItfp4bmcGeAACxfh752vhwfX
SCVx4AasCobFDhG55DWpJZs8GkKqTS2QjKEjoRkh6Srj4WTp6+DDd407WB3otDBT
MsR6mfJpHuWQ1g1mWp+g5YiN97mL88/CJed68ljG8E/en4dfG3cL0tDM6LzdNG7O
8KMoPMhbk/wHE7BQ4I6AZcDFk/yuLgXKsvj9oweK871LsmG77WHoUvtVKt7tyS0y
/wF50/s6CNuPXCIN+i7I/FLN8mqOOgahOCjEaKA+dA+0auivjGHekQF36ABHRxpZ
Gpw5Ei+0Zwsecl0KW8JwbSVSwdumtygiVxiUHb28Cx2ITfgo+hpFeJlU6YdhxOOK
5DbSx6zZazxkwRCTnFFGY5XksCdCNm/GJ+v81Vj/7Pi1iaMp2tnzP+OlIgDm3Bzu
5pRpJ6IVbTxZE4QnRBgyAalLmPrxEWkRG4+niAfSGpbdd91H6B9lxgeZtEEdaxD7
oQNIxZ9gLwIrEDM8gR4pFNq1vRw9Or72PYZwoAjUqv3wc/y7hU+DROZXVZpHjOPS
QAGeSKGTbOHtDVziPFyhWSkBfkOCyqENw2QxSEtsSI5gzq1st02Q+43uvfKrOp3U
jhICvVuAzSgY+7PuLdyvgtU=
=pwom
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '46ac19b9-8b7a-4d28-a3c1-8c306ef4a184',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//QHQhttFgYk5oQOeuCIAOFtfUhG6je8Ad3drwtrx+Up0U
NHEeGzEXLgbOP4F9Z8hv62PCMes7FwJoRSZa0FpLtxL1JOCgjY0Z/74n6x/i4Ax6
eECJobgtS3DBw85CyfOogIory6DvLkAGpmxINoV1ijQgu6xLmadHxCpfKijtGSYf
hCx8ok1yw9ESD4+X0lRPr3XAGzHOgeMzc3Q2WUnHGBabBfsj+N/N1xARU6JR5ePj
8tk4jukXv7S0IxIkHau0FKlF8qB3MRKYna+BEJxnQu2M0m+fJXuAs5eT4sBf0Yc/
B1M8QLJJYjN5Ay4gBUa9+YEGB874MowPhiNc6rNXFdX98js5h+IrH3E1AbT2NAuF
LJbGhT9JvG1xyGsuWHu9v2W6snbGZiJdOywME+Q5NZrLP+qFsCJhDvBNHmFgQp6o
k4lE5fKfqtsQ2zJPFYCc1wGAxIfv2vOQNbbYYSTTn7o2q36slW3tLv5SvXdIPRVb
RD/tGTxZ3jT+8dqm/BJ+tK7OedvowQlfDxmIjcwjkQRn8RNgS0GJ153a3gazw4xj
iNOSN9VRZsNhGg8ESDqIpq2GEgT0nhOc6ghH4nu8A8cGYE4/OBk127n5X7xeH2eB
flrjDORu4Jm90Ch67BrFeG+XjaiA6oOBudO92yuVNZPCdKZexOPIAaDYv3ln4FjS
RAEf00glp3IgCVU2JtRoEvzIlm5NsiV6zF6hJ+mE/L/1VsRn//xllX4wu3ug3bDU
o23qrSjNSiY/S64oitfbX4sL9Grf
=DSlp
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4a747adc-b9e9-4fb0-a96a-c5da3a0671d8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/8COZKJ2sNBfO20sC5ubo71vQyfxV+ED5OiY38mlxDNEQ8
DNjW2rC5dTBSrU5DA5SEKB9F3ySrsZiWx+4KRHBT01AC0/ZUcZq2J96IZbfaY9Yv
FnpqxPvSxDOcPbTKoJ3M1g8dJcRddLOziOrzuM4refSKgX0WksjqrPr4C7eHwN9U
oPdf9Ly5hixGLhv04qzZe4BdQZlrnXbn1737znBaCqQ+A+d2mI8MRLAXzyIp9xf7
R0w0BfVz7dEHbSrCYuNHOE61HNLZKstgOwC7E3dc9pbN/dKzGK6B1smCcAvY0cvv
U6vSb0hUdZH+VAA9g3xrE5el2Etgu4+yU1xn+yV0IVz7n/q0Vu8duVsPTEgQjjSC
sLzBsRPhr6TlPfEaLO/xZCJyHw06WY7dWO6AMopfraaR644tfJ5dmoHVoUf3hDD/
Ypx2+AQx3h79gC5ygWxOm04x+Tpe4YKhXPB+4w33cIp1pxT/7cw0mMBcfom5kbTe
AP6b9xrxqbhznTZa6O3zs/8j+A5sANo1XYtk7rwuHQB1OhgY+Mx0f0uNTgUVKEsP
CFqcyeAuDwdzp/5xe4ouMQLeoS16gM+6rO0Moj19Wmao7kPTtwU8c0kdzd8VMk94
Y+bGT6dFpas120oAOWBlQKtQNZpZVmW5d8az/6vZk+f0sqmLi6orPuZsXDKjPz7S
QQENqU/rp/yW5H+SWD4XUJAnekpyuuBs5vT2mc7mZlout+apZVEMZnS1b97Y5tQW
JnYjm9xDof9hOJ7yUqhND0sM
=Cj68
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '50a90512-402b-4fb2-ab9f-f187a9b22e49',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAxpOCoup3TQnuKGcMc4tBdSUy5p+8aIdPNYnH8WnJjPM4
bAiYAcPWYc32kBRiY8Y5lp9vO3YzNgIOULt+jzt6WLNk/TsO9yhFOCUjV0KuRRq9
XsN5vcM3tZ1UO82x4vmZsGfLF001mmV2M48ZI/E/ePVpj9/kfTt+737tlJsKTY0K
ML1Hv876idwTBo4BaysUAXwmpPw2YAsoiQpG9GgvgqyXv105tfBrrn3LD22HFmhZ
GEOzWVRsuCWLlKglZFZYYxTOsLfrHoBCOXq16z+WYbEVcQHe2YsUZf0z2bzO1d1/
5SOPaEa/1rI1k+Ka94sypj7FzlB5W7dgOGr4P7MMvskeEWiAISX13H7C+3PxPCeQ
AVpqTOH5s3TMcO+2XweWXnO3VBJX16Wt0U74Toz6phOSgEKqunQrYUOvMTwp76Dv
EejmhbeQYgLiwsfjUtzfU9COAPCTS2ffp8MyJ8+As0Fa8qfa/5L8TOSnOlfAJUwS
3qsUia5SQbDJ1ej/ZRAb1mrcrVyG+PxnCp0NSNhgxeFuhxu3sVMm5k7hQ+0eiBGz
FCmzGsc78esI9ne1FI4qRtK1PUu/MEGJ3q0gdTEMf9IOmZZ4qjiy5PH5lDkesIU7
X9mf2yVL9hWe30uh8PGxaMJInjRCi2HGbay6l2BE+xd4DLsaEX82geKSO7LF5uTS
QQEXwt0442MS98vY42N9YJsVcTbuJr7Erj/0iffROg/uar8qOK7qflZ7dBvIEVGV
TAKTKph4Gfv97o3Eeu2xMUfC
=39CE
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '514f4223-9c93-456c-ae04-6a3055d9f943',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//aafbiFcA0GBNSZ0MwuId9TKqRjlPP1TfanWpEVtXQy+S
mg7OuMXQGiMSOidFSYyVxPRog65iZEmYDXNNDPjvOCs4OQvVG4YC/HLIAxKkOKF5
aY04Ee4jtlEy7RZ8/7jyTo4LwWFCPP+9KbSE4BRcHLIvWc8E/cXO8YPuBjAgynkk
SxzKpWPTNW+jBL2k1o+JLbNDug95VSoymcLDVSkxJj3fn9g/6kCgoxXZNJGvwrxq
O2a0R00/FOKZOZopcG9rsVhbtvRD/78U939MsvoDrdCNzW4q7dYrppN7ofERC4Jg
p0qSBXdsLWuSI2FV2u4cRUnwUA/H21/Amppe89zqvkaUWp0rcAUdagIzFk+bAQX8
Dj2pEsCx/yVsU0V9e86mzFdZfzYUpJumeCGyIEXJTGzf3G5xzOKxGcH4aA0XUd+d
Ef/PFP0SJZ4BOIMEZRTCqC+2wqszKw3gjqDmqwvQLRJutzbnIc6NbgZuDQ+IN+uX
CqdQLj+YszpeWyMnwjNFnfHC476MeF77SrRs543bXBixqii/6FFRmX1X+tc4hw5a
GWrNOLoMqqmcK/i7Goz2EdHAZFPw4H2EGiRF8gC25OKI9BGZ6AgnNn1wUygACkkU
fCNW4O11eiXMgcx+IypciIIgpAEsZ35mo7FnHgnS2CZH8dz7hbCogDoedipOvQDS
QwGid5iGD71nX5cf+ZD/ZnSsPxSsP/qNwFAWwkmaBaICNC1B4JRkToJPlDHbwGd6
fs1sxePBbzY/DOsqecXxiY4vJGw=
=H+QQ
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '555f3a7b-ee43-4de8-a0cb-9f26bbc062c3',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//a5GLXq3bxfXYP1xSLaE+J6B/R+ajnV/2LU3hbyHQNs7P
BRZBt4R1xDg9hqgEfG20WqM0MhV7FALLyTScxoORuwB6laJ7Ep2XuE5dKyvsKarC
yBA/RRKtRH0TIp2XOALmXQc5XRmS00bKc/bvs5RjFNA7bNFXLFcE/5FmAlYJCrSD
3qWVI0FcyvD51xcv/RcHPkw7tenQPhMVmfxuzXcRuSa6S0mfzuz9tvhttFe3slTS
Dq+wSRtRTNw9pGfvU6JNhYjtB/Ua3Kp8jaK/DNp7l+9pHQJEIWjslcfBu32kaXYl
MHcIPmy5AMOhuovBgkB7XP93sK1xyGbodiug1DW9pMqEW/YfTdP5Ng4kn+Lty4n1
AdIrpG0yenlw7zMiR8LjKyWS5nhojzR7nJBohBeSNT6FXRd+5IEKdyEYvdvt+dpN
wuK6DgTMFROo30jtO09f3pcis6/pESrYq+b7OdUwKXoP3srfqcdfHBZpQV4leP9k
t4DNO5NNTTdEpP8paicD59hGPQruEiCYIuQ0S8UpFiTVje9Myb57F4E46bdJ8JM/
tl1ok0hl2w7ZwM6YeqZZVrd3EPYV6LMqFjqQWOccaQS0O3heEiyKMvD36tdaUMYh
20uIBCEE0xTw5CE/p1P+4PNzZj3iz0O+/07/RvBfkbRAP8eWatdlx/Z7+02kp3rS
QQFwIC5vPRB0KfVJA0WNOWdkrQ/Qh4rXWPkk3BP97KAu7Y/P6RKPDvDXjwU9giQH
o4xtXCYsexRbl19LiP6Gevxf
=ebIl
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '563dc9f2-45e6-45a3-a13f-9fc7f992034d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//bcLX0PjzkTy49c7UkgPhaDehMJlQUZq8vFbV7hwoMf1k
WCrV4w+XHAgrMnQk5RSxJmiwzSwZBTZ1MT4bHf9OyjnddkHit4BvP0XMh9UMbVfM
UFpzmoQDPgqvVQ7BOj/EHSmmvD22FZtiwCqm0VswBNQVCdZHCslgDxGkkA2BvOfL
FI5D05dF+aFUNcQVL5EpleVbuPCe+KNBre3BTCn4usYsIoyP5B/nuLXuldDsxS+Z
w6wiA51U6Puy+cC11rM4gynQg+fTbnivM9yRym2/a7d62tkM00TX113Azf2yA1Dy
dEz3Gk2NVtPoV6n3D0YTbnEHq+UCtlXRB3aQDKleSFGQvnJzicu7oOOQeMuVxFS/
Z+NPfK/GwuWw7RJEavhVbUlS2SvzoIBX4eQGyzDkP2BC10xXZukzXSLc8cBkQqHv
0+Ir85MNGgD3gxLB9Uf3Yr6Ixr4P3l2XCO5ss7QCVA68RnYgg4gRSK51WkWRyiDt
VOkvQd87CGrksZpfrFbqmTA9P3pKP1a+Y3cHYCEPnnjaOQXv5lXFgM5OQFoWfHM1
xqibRfFYaWiF07+1fqs1wSa20uBm2UnD3MWJzCUC/ZzAPrJOgOaFlwEmUoQwKQFx
WPjIUVCCuwcc8delu1dBfg9TIiUYGktwNQBF4XrFJlvTCPPLyPQxetPei5T1i9HS
SQH7C1LuSwIPXxwIN5BvS+xmY7WJSFSEMJ6MS9Ra07rEK+5s+udMU+upScyHT5nZ
2CWCBOWL754xW4MCmhvEc0Zw2pzw8hWDA4E=
=zTEM
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '58033308-3b26-4598-aa8f-6d216fc2d1b8',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//ePHHUVBMQE4Hd7Goaiu/uDXq1tS1IAh8NpkBfffrOqWn
UgdfpGHYDegQfFl17U/Cle1VZjfofbNi1785JFVNi4SEUQlbkXmEqdXv0/PkTI+X
1TakZe7B20a+uhuqAW53yZUr58EhnXFIwqwkLYHY3prcyIBj1Xij6JNlPzg8ASqm
KfkYxC/sxlJlZa8GwxklRH7KIBxpqUalQtYu/Fse44X+APc5W7rgqZhT0CZheMVr
5PwqbHy1cgVVZLzxUUwigRGyfmk423czn7d9gIbOuOW8BQ5xrDS0zS14vdmj9lZI
zex7hbEhQdR8zdcWXsI6zcQmeuAql6cNOetGvugbXv+UU8LRObSSIOaitsfiZoOp
zqNWJuToyKGrsA4+kUlUt4m6Ux4Yh0wdB7EOh50teDN4eJIx1FEdQMvq79gsVxjn
9/TU/4hBtshg4Bo6YrLKJCmBaY5JGl8oy+h0pBxMB9tvgNQK89BfcFiuCo8rC23L
KHBt+fny5uN34CEaEbWkgH/2BkI5gKxGepis2krhKWjOKW8f8GCO6VQ+mZt6pSIZ
vz8JS5jKVfGkl67jKGVl78OZl1APAkGltutQzjozh3ydmAPiEsxaYJi2JMBR+W0x
MjoAv2+HDdPcH/fakWa5A1ubK5dfYhQBPXuLEkZIFaIfZXx5a09GatTrNGUL9qzS
RAGj9aQMEnRYS+uq+8JDXKpPCdm9VjyeptA1i89+NG8I5pv7JwOFDjQDilAXqsX6
1YZttwvHjAPwrsmkJOf+XuYVhIit
=H56q
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '594dcfa7-ca64-4f79-a9f7-8bacbd668ca2',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/6Al+XezbetjRBQS+C+47T9tIRDvAsLf8o9NGdgfarDX1h
NZSjV+dok05MPAFNjuyPWcMNTv97MbVqgLiK8bAk1vmoDLk7hh/BFfnSleKDCZPR
d0meDo4JhNKkUyo4BknOXhk2GOX45E9+ASMWw+68RtqKAkPEUwqPhw0lW/J2GwRP
kakBWSb2xuXUjQEygCoZWfGYV6JqntxIvxhCrS0ZFgy8EZdk+wvYDSNEG9+xDnHh
KRTjEpbVPRUUbE0IhI4oxHzZgqU9yj8B2mYSC3RdFTh2TcbYzHgPlFI/SvS0n49q
s+kBiWaMlzITvMqAZ1DmOQcplDXe+WyB0d4f2cVVmRaPhGF9FhflYmOqHiNye60l
dnqQ3U1yYJESk5AwVShhLW7JW9CJoePHFf4z22DM3sLMGXsSXrRDWA1y90mC8faz
ngwoRvtPFbSZEsUPxAAHrjfSKKPSgDP2Fw65IU4nxDAFBqZjicLAWnjIR/uQhO2d
00/BZoxsoh1Q2QEueUqCWxba40xzshR5lW0FXQ6JjGhsaZHRcbQpc9R9UNUXY0Xu
JcjJY1nYTeTuqL2bKpB2cLyauToz7g7t3oMJuVyELMiIQqSLGO0/GBLr4MeWWzn4
rquU/hFPwcDoCBu0Ej1Zu5PzjMRHtY45ZgHkTWgD5DNCqeoNDxWQOLvabGOG9BLS
SQExIFFnMiaGMzmUs4BlK2iaHSbbnuwlQ6wNltbBClDZefpZFWr/T5HWOclTB4Sg
VSqLaRpPT6+FUFxbuHzgZYB/lbokpNvoY0o=
=Syb/
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5c424d3a-74a5-44be-a745-e5ee74df6f4a',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAjaZSztjuPV2kF1feuP4m6YS29GDeWXh7A4Qe2/Ihv1Rg
n+IbB8Cx0yXW9UONgSXs4NHJC7E9ZVpfP/og+NZj58rmLxmS1BIujvD7SMkni4kv
eDhK6z5AGZE9zZ+enTMB6uDri+uIuos9KdI0NsmMUANu4IKagyD6wAl2Jau7HiT4
uQgPqCbfkPmr/LD3N8GlDFdQ45lfFs4mZgBJyY6EnPbW6fLQ5kyJOuWUJnq/LAGG
JBWBSJXueMQVWwrM19zp4cYw/qprmfBr8zWRhl52QDzvdtRPCyUCFz+3fOD984YC
KocDJmowIoR9Rp/7RGhT5mSYDJfyK7MxqcgDUO59GjkuWEXYqEPE3rlBnD4ZSPJl
+Y89GDcF/aZ2mV3U0e4KjM1S/nP2qSQM3wqVhxzs7cqPaUJfD4q41hvyRDxyoeJA
TJ3FaQuGUW2uck9f5G/LQZ3+fQ+ziA49PgJ2I7o2CSjHMxooPv/1Bnuv6Uad8yxQ
FN+OmaUktfUdV3681LI8I8kf8OZGYabKyBHKBt6NT/Evnk5OMDfzqIyaeT05xJWZ
nsL4IVoAQv2d53E2sDIRATpZqhflxiy3z5/S+/aiPWu7rv40Ez8+FzRo3fLVJZKQ
TxwnfOPv1z8hFsL4ct/F7c16GTqpT7bBIEx7hnQHw8u3bhVglpBJfofmFc0oiLDS
PwE1wUdEKd17MvvcyNUA1hcsrvK20l4cMSE3/hbp6RfM9VOblTEf9Pk/fWeeGxZv
JkvpD1FDW+mRY924iHv60g==
=jg+I
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '63d5faa3-2340-4e11-acfd-6f74136226bb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAv2rdWCLs+yKdwmoMdKZh22yUWNpTnepcnaGrBPdKlS3L
FGet11QQFmFBF4SdtrXowmcRpE7N98VNIXiQRJb1RxN2B9BV8/ZX7GdexmffwrHh
YDTy5WW4vq15YALQxGd0XSGXYwRFBUd/oXjGP38MSsoqt+/LfxqH+3vNGDWYTMLh
gjzjgO3ZA+11uLyZpY5VAxOeTiUsk6uW8bSDjxQKyAWklZdIUZPZXR0BKvgvIvsE
z1yceKDStJljrU2kMFqRvJO0XzvdipjEHloLjJDslEz4RQB797yMBm7IKyBo5KAO
9O14+1f2zdcRolY5+g5BETI4uRV2NY/JA6C+kw6pGEnWewFrrC+S+oIsIqVl+mdY
vHhv+0bDUTS1Qiu0532AyvvRfw/XgeKEV5SlnNS0WBN48pHwl6E+V2/TaoMApC5Z
e/38zTXRQJ0FwwZkHLPByXMsPl8bGrJTDk7Br4JJ+X+SYlVzODsCmTFvopE8i/RR
GkFQVWdzSKcAqWaFaxz07xm1msFTG966LQ70HQgLYUqAt3UuGpKy2nvsJ2tuOido
cWgUmzzzCj18zjfW+3IYhgxIkXAeZ+th6EEe+5IssQw4hK5wFRpvDijsDCgYiOPj
GWRrTmx+3UuilqJRmVQG/3aLpevnH6bQ543PwzeXm3s+ZGBsf3UPymVkZSu+CjDS
QQHoxViHvVNlsYF4PTUdK73zATPQFSOCmc3kPAitL+mXpNggJPyIcHm+nDUazppC
lmGWSGPS1UQ8+DefQKIJVixh
=pYtf
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '67ca82f1-1432-48e9-aaa9-bfc81f11dfeb',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAApUfMjAC7ykOCXCV3TjDwe62RgQwF3GscK6k8y+cKZBk8
d4ruZ7QDrR/+JV16S4k9jbq7sibTMR1h8knEsJDlQFU9FW4cQBBT5MOsLSJBL+bq
kFWiWi7o6NLZOVuAyEXQFsGTVluzU0whVQSyuKM3UxjFV0v1Ct7rkDHEan9qPxHC
uFIxSETYMTn/LZrMkrnvWMyBiRIEfZ0K3x//vf7suULgZxAV416fDMUf0e2gs4H/
WJLQWHswMUoQtjnbNUh6K6YmdkJMgk/MnDQnuX4oy/P8Q4PBmOVJAl4NCowXk3Kp
E9gbbc3Ap3IwS0TwtH7vOX6xzF2oyoktETXgWr8Und3ezc8ajgKbxjCMH2qerupi
69ETPZhhMkd3oBUQTZ51KyVA6LcmafhYJOgzQq8Fbo+MxgVK2t0XmX3RZ5hYwCp3
owBb01Hza/pHtBixlH3aUn40BVuHaSycgW1kjbyXdm5QDH13493GnsrwYXIdp9NZ
nN+lGP0Kzop4I4/w8d3QLa1Bdaf8QTMUvG7yXW+tM2wvUPIf0fWTDM6A8m02LCXB
iGrutEmLI9s/0e1L0Pn1nZFm45empUsNcxy1WM2e9O56nVEGvgxpO07sskxyA/FR
mp1Q6u2FKEjczbIPkE6rcQ1Qt9MWEAYkNjh6S+a46or6JQ4wsKLqwWmnTrLmp7XS
TQHEej6CoGExXeyCzkkl3aAFqBD9G70en3dLocAIanro6yxDrp1Au7D1W0gors2I
sFYLaetcRQZKsyADFzq5aLLlz23GFjVKu2kK/40v
=Gfw/
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '686fce0c-9942-4ac5-aec4-6f45b6e1320d',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAwHDw79OHRTcgelFiFhXULUhjBO/u7H/kqyj3yBU1XyfL
2AJ4MlteXzIa9Yyp62EFl3B6v5+AIEbZ8LAOcCCtYoRCtQhTTob6Y7iOHeMdnXYu
2OqBQmZaG8TX8PByyQHVewMVJaZhRbAT4QXkO5gr7muab1GqGX/N9atDPQASz9k0
dIhL9f5ZsbjgsMoXduSlBq4v78HyixDUBPzH+H8cRGaUSIJAWmrz6uOCPTzHeH6B
8/8EFQigC/NNs9Lh6Uj84Db/RcZw6EgSC1qx0VfZVWbRBmAwHWNwQkecwcKekS3T
1xddbTDU8vGM6VMp0GBJXg5ENvDQecujEHERMwERIhlp/e+/ZY8zUayXArn6F+Kw
cBgSAqYn7xY9n4I9VJOvTqURPm4Whyq25xy2srhQowpTyGWeWhbQyd2PEUK88n3H
A+icPC6sW/gjcyge+UUz4YlBk9E7EkKBmipfl07FzAHtZ6UCnKpnpk3ngoRy6EZq
z/pPJ7du7oVAhFDHSdqSfMfFsNvv98aX1Wd3EMzkM3O7c6jTw+3FVwumR5oKYCmE
wLGODzA5UfGTVy/LoW73TOblOEVJIx282I803IZqA6Is68MwMVmnLXZYyG7dqiK9
FsiP9+di2e8b6T4/iDXHArowiCakGr/uKExBckXQ4G1MkLhomUwSXKzoIMmXs1vS
RQGRzhpWlr1+GoDlPVHydi7UI8pOZJU7Rc4avXEGc6QNiLuvxPKKTbk387ZEJ+1x
3EfOrsuzheXjLmaQu5GLWtNopqidNw==
=5Afq
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6d13f670-aebf-4dc5-aefe-bdbb9c2f355a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAmVwIeSkhJXPyB9Y2tyMAHroT5+L6n7IietZqd55kFXZ4
/+vHuA7g2nw4G0bctWeTSbVoUHgyo5Duz7+Yy9CGuI43pnkYD/+3zgNLO0aFbydZ
oZIyfApAnwi5qgffVQLO8t9xvGsXl1NeYf7x2hyzqLI2d1Fu69yfUT+nfMZwtBfY
LuB02ZJeFjo+K47KLQxMkESFENNv9o9AhGjaFBkdACiwxoUefatmym1+KjrfTTrg
3eT+h3dhdvHmPmfPyzl2eoAcOv2pxz9HzcH0gy6HpdwWzScgGT4iIER8FeL44mVI
CSu8MAMoTfWNS923w3WC0yJzee+4c9cv62e9/baGL94z6kUI9HRkExvE0XeVx1ac
qqPhRw0SdcizeWgea4yWUudg7+PZ1+8GmE8pw/tQAu17JwHkM2DT9rX7Rztfz+8M
Lr+AinBvL6euIzx6TRRuBHim8YeZRufSOSfLfTkfu71wqSUBKMy+E2MkAxmUazZr
CEHfd4mI0Ho/qPjdQHOTLzxOApZ4WzJ+Yn8kdaA7A8aR0W5bXLfrNs2gRmfQRKO1
dcitq4x1MndaD+mRnJ2bkz6MRV2kirPq46W3dmca5szw1LNwuPTXwqgKLCiOuEqy
bqm7tzmUBDmf5nGKsDh+Pp+4RWS/A1TMwkaVdLOkN3i+r2rx/CJ+j52xcXCKx+nS
UgGMD6XKNFTCOS75jTtBSF70JWftL7Rm23ji1JRDrNFFXo1gS9ESTUowclyBtNsr
DMZcvecQmJsKJ4aMfAjKBQBBCtlt5EcB5XYKbuReyjORIC8=
=fu2N
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6fc78e52-83ae-4c79-af0d-f2d3e8b94c70',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+L0kIoyeZUKLUvmHVvBVC/Jyp139ay9lmFVhjyEcisBxg
4VxCdIaGcnV332S38jholSvqsvUlPgJEsAvkIzBqiUkV9Iz9g5Z0uWPRXhSpFCJ6
yVjhiULabsG26ZvcLNFB3Y3WWfSImvABrZOoPrdPsOV9L7MAc8L7Dr6imuchaJR/
CYe6lNPpneCzJy3YH+BEKuODc8NB8mEbW49gXvr6jaPhMJHwoPTk8pW3gXTNgdPw
pdlMncyaT8a/j6fg+rdZzhIo65zRfbFcJ/2sgOGngtcwfhDeGnguWIZNU5gBlRZH
WCyeeoIWrJs+gimwSDNRIhjLL7sCW4D1IbixytykECgeSwrSOuTfNgmHVJ/iZXaM
BN/OH+FQGfmQ7+uvyhO90spT0bPE4VDwRfyM94Vf3/6FrL8IIxPcljXWhXsM/AOP
HFvUyIMgJsP8TVUa11F7i/K0o5fhDKg9XXFz1SCLk2HSHdap6kTvL0VnXvk7GMPo
9NXDf+iRXQnJo+YuCjkAKnsc/DHmfuyF8xwUOalnVNPKKqHeZdwuxgLgnCrKsf9l
FOpiuoMPTHyS31rniIbXEqJILvZM76MLbTsGpGUu/iytvK8dEgobRq8SWl5luVIk
ZG6GE0HTlPv31gFAUZSFQKQL9r8HLCWoli46bINjTSFjtMxJa1LkXdVEqGRkiSHS
UgG/W2qGN+fRWofE5iDxGt8Sbzi5xbzPR0iVcOf02Qyshpy3AKXF8/1LS5OyQOZ1
OUFlE2vhMg1T2R7W9zwiX03dfXrBewUSZRO6BUgILSdpxjQ=
=oSTO
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7163778c-670a-4501-a595-0d711ad4d7cc',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//RFrHi5zYKS5eA8Oq67X/6774SrnxY1fBKYLQZTqE5CQr
fCGK0UVAHSdTc5WzGRGkwwoMC9wY/jiQN3Zo09lkA3xVNm+xxb370LVufO9jtSGG
fxQSe+Wt1Aa+/K91GSFPEKj+xTMoa+lGNpSPj4WpSQIeMI4Mb+S4pVlQCTqc1Hy5
vokPAALYDoA7z75jJljb/wNIiOAj58X9qj2HJRlRTXYrQhahauPdTunHf4bMJORC
h3P+lpPHXCWwHltIflxZ/ZVRQ6RBGDr643f4l12xYzblvA40FrfcPI9xU1im06AG
fM8hTE5wFs/rSCz2e0eTqVoY4Jfp/XWfVKA/JbwZMT5vWlhKpnsOD6V69+Nh6HNL
DkSzupzazzaPCJh0Cu19sO4qS7NFzgQZlvgxJdm98yK4kaRH63VVmdWqHT2wFDsB
y1EAM5lUqt7F5oyxsb0jnlI59/fZYdAnfIDQiVbaztS8hvFxDMYBgb8nXtlpGALX
uscQpUAQfCiIMDIPASkD+io87pKHUQbva73I3F6KfsC+CrteI6RtVi5gOinyHCQR
YbPui/BvQScyJzDrwUq+QdXdnenEz+eXIZm3FifyWrArsjOHtD71PJGbElwZ7hjH
m4th/oKjMKt9V/eROmkZXglXHbp6QG+hVGQzKzFpdbFgF1zbMD3aZLCamYUkznfS
QwGRsMRMyvUC/901y59W+YfpXFg4N6hqP5PDc+n3fIc4F5ZAXOkXFRwAdM57N9pY
guDhmRXeu4tVjrSBPcc376kwCG8=
=WrpX
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '72469e13-f1f2-43c9-acf9-d61f01d11603',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//QHnudmhJA8AZDppr8DW7VRMc35lNpgRwdtqYA9w901Qx
YmoBfXun230zLYaQoSpnoXfTYDfkK++jWt9lZCmHz5S1synNkzCeKT8zEnNH7ah8
5ghO0laNnkMufRT8249d6GofovII22clgmzP9nNI9OKjbXUTRgPpzDtMu8483nC2
R5G1s9CT3bqQYwRvDVUFO+oHSeQjaeu73QptEhJgdJRIvrrTkA/Sn3OPxqpAY7aO
S5H3wP+FYL58mTBA8FzQp8JfLa2gPZfii+nyT9SR5EtufliQ9aYID+Y3hQMFAEPp
ja/MhEggpyXV73x77julppqV0ND68jMMEOWiFLOV5ibJyTmCL970oYgF9/p6GFr/
eCVEoarw9VOf40KJl8VhHJYFkxSaEKmqpOrpAFOKJSuqHsrRb7RT3xnIRvua16TR
x4jDeaPzgS8gad+TMmgXQdw4aeWpJY9PttNV9IrRbroakvgQB1IJ0LXEzn2ShnSm
wJc1GgZBXa6g3+QmDcPr13NNhm/G71uUbiVJAMI0gEqDPSAP8j4Z8gycvLjGrlpP
eCBliqkKbGIVfjuVlYUTPTHE4oPW7U/7I4DmzFXiQ1cPR2OtWN9zfsC9eIZ0s9J6
0ADGOL5j6mlvCxtWD6xigFqYp2hsuOc9Qjo6d5NHuf0Er9QoPSCoDi1RVERS4dzS
QgEb/pWYOxPPktIjMAmACtf1y2b8YtCiuRwlESo37KqzQQpIjuCe2B9eFnsrTPBG
X9XLkNXWDc+cV/6ulT2DzCljoA==
=VWWw
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '72595379-8d24-4aaf-adf6-ff5b05c04206',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//Qrcm7jhi+8XhBNwJc0F3OgYDIqAmCHvDiynWMMCY5mVD
pU/B1syrae46jnjUm8bBC205RPYl8PglsLTa3kOIXWp7hOKFx723oCZU6AVmXx2m
5gh/AdG64h39RdQwuB4cD/czmL+4f+00pnWqX3fZFmpFszqTwzFE2zX/WPqCTS2M
3qoSJ3UXl0XsUP3QIfg+RKJ3PzRjWN9i5FoHiUwiYoobp+095N0IqFJ73S39Oljw
38riL3GEgwQrGZH8L5IYAZXmhZVL/tlYN029616mobLzHbvuO470XiIA0r8Gfjt2
XOO/Zu4IYMBuwqX+ENKxjUVYujfhhgG7Xat8EiKZm4/1H7qMl4syCwN5DCtBxuLb
LjI7XxvCFmkSNAU+lzd5f2cUzI4mMvGhyxqy0n74GyKIXebd6AE4enR43K5n8tAM
8/BH+e3Lu8iJ/g/4ah9iLG1VkpcsdDm9mWhMvrTyevni6DiU9bgxgcaHsu5y2sFZ
dbU4nk8KW8Hg8wa/BiW8kwfpjGBpsPxH712W8YWeRMSOA6rAIHvnaq5NIH053oK5
mc0rePXYX+XxXk1I+jVm2BMPSGG4f/aQ+Muhqd310qgwIWN+x4eZO8CXBGoPRNUp
0NfwrHmXUKKquLlVRqJHzlTP6cyN9Tt0VoKwgw2iEgI13bsAXl0y8opBR8OtFyzS
QwHeEq+aLdZon0pExP0PUdyRk/zJ2L7dEV600xb1F38lZbF6W7ch65pR3j+Jo6/G
GlzF9WBLCjv3blNk06HF0hVZ5W8=
=KY4W
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '728b505c-5205-4b1c-acfb-2b7ea98bb289',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//WdpOBOVK/cVEQNIsozruCPZonJN15jTZr7qScm7pcTEH
M7dNHaExBU4lU1GmCMY0FTRnzz/i59Ko8YDXMnF47qScA0nDI0yyt/a/T6FrP876
ZnjK6LsHPzO8NoVwAyNvHubfeSFpTMwIToyVxjcWVgvcR/eXmvUPPypbfnFRWd+q
9yt1oE8cu/OIdZbGwl14YzmnAnpEU7/D7C7Jvq6zFzxswv562qD5gCi4TinfgX21
mZCd3DymKF6uIVA/uje3xRl0jrdvBQ+c/2hTFXszpGI83lt7wzyqoT7j240urH4n
2B35c1IL+BryJ3k2JHyLRhC4MCKd3Wgy+sSJMhI3op3b0ez+/5FDxgRhIL9JrYLx
FtQBrvA57F6wKLn29/+c4Yz/RFwk3lr7i+mXnRHT0Uo1si6rAbbhxeH3Lx0euy9b
mXcK0GjtmSjv2yYtBWpw5+1ZAOYbq3FPjO/obeO7IRSQtRuLf9d65Za0eWRFI7WF
VITVPm1M6JMPqMXsGgFOixby/qohQ6ouIlReCb2rP4aYEv3wQdnMTi45xc7ahUOD
HYSeBdcCJdD+kFqoXsu9aK9BlwsBamaoTuGkjtaU3QnPBv+Y618b1bYQK4Pq8C42
F09pfBXNHLB7t5seyWd4pZJq7q+JQypO+ST4EMzRwZYpTiqIcGHpx0fIhZZgSU3S
PwF67IoDxMdrbmNcOcVgu9xpFgWEVPiKUnMBAiFDW4C1EEe00cVv6JwlBbzMSSTx
Lc/yUJ6N9XSmdG6t+Ha6KA==
=4RHe
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '735c57ad-5e42-404a-ae23-fe14135d0714',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//VUeFx0TcGobKxM1G0jNxEWpaSMYqATT1zq7wQmZzCbXA
+4Y7s2j4Oq408/U/Q6BKjRykjujYnxyjfQQYPh9QFXgJtAykUPWKSIT3CsjXpGxI
MYskt4RPwbzyNsP5Eicn/a2zFcNtQ0h9IipRez1S6FqSv3rFyJYQemJRVhGVbhk1
NCQ7WDv4ChGRrbnyTFQQIV4Y9zP1fv6MrrbDbSIXxKUpd8ks/a1w0SS3r7ui+kGU
HM5Nqp6mdOlHg3DDSGVOx0MUXck0udRrsVmCfGac9NUyRTGsW7rFCLCTmARkmcjk
IZyr/u5mKu5O04eGrEPsuqgN/CVCr3KvW2nEhcyOknpLfrsgW7WArNW4cD7N9hVn
DwVKI2TIOMJj/Iav5CLqDjzPOFigMi8A5O2fGpd6z2opzmmthEyFom5VG5UXMW96
hKsZPEhGld3uGPH+isIjSl7QzWRCTvQd6X6GJ2Zu2s7D+mzRrojEGXtYpHsgvdOh
Fnj9IVRKyGFA9Qalv0qkVXCpKD5IptQ+avaRm0688GKythnusDhCOOow6kdOhWfb
CEpnzUzA5O15oRfXc8sTJ627DXkfO3tRQjXyrunvT+Lkwzlt9k5o0IKbOBcMyLs8
HUz981xUgFcX0P+bVrPWblUtZSJWohsA7+eCavASv/t87+oDawp/c98cVcAMRaPS
QAGbWBxJY1htGxnVZNI0Jvynm5We1uobkPU3qH0NLC1Wjm18tABsggI7PVjRm1md
Q/QFGYiIVFEWyzj1JLKGjlo=
=bfMX
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7666adb3-05ba-4145-a7d4-b40933f361e1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9EWFzJOca/jI9zlPSoTxbX2i2P8h/UkAp2zcCFOBss/AM
OSQOFfzhmqt86aj3O8XKnrF+cXEXpbvYhBnLaUPJnRoiMazk6enhbGyoMALZ7B/d
zMLX2f36bMh8rWw3i0jIC9yVrBI0sWhLbNyTsJ6c5YMZsIeeIMXG7E/HynaOiiQB
WQEPGVYAjjXjk09wqogBfHOzGcYcV/7iRZtQWxCLgtYy4ZcuZ8Ii5jesxV24lqiD
izZJac0z1OHCWXGwclUuapCdqQucN5vQU3h2gpUdrHoiz5bcmnVnE5vfywQHmtai
Or+tfYVgJ1LBLO60O16g0fBiaKh21HUFQ3od29GI02POKcjMXzkbCTW4cNfnpOPa
jaQjtnd/fcBQDbAXrLpUu3vuF4jQYvOhB9N/dbUOtTQ5hn6SWAC8TX9WoDGlaqBw
lqNF30CLafJv3MteiGZBZ1BdKpGOd8mGbE1iS+av2nCI5eKXlv3Y778Uxr6/T7+h
qAnVOB1xrPxASa9sp/DxxCuVJ407gLpXiGGOw1CW5fpJ3D4hSBb5ZjHKkTHrgWLK
GAkzvu0Sn6Bg4oJtMXaPeO8CiylqDtovXt/TLi3NGqKE942LG7167KUJBJsNQs+V
UcMdivr2li5MeySe/3Pp5rfoLNs7cMLvdO3n60JUQIaHDSLq/iBiyjHQBtR62RvS
QwG/cMa7EKyFvLidY3V4a73dC+3/mVrWzK29SolOSPDEfBlZtLCARN5Fzxv3w+rV
+WFeijCLN1Mi6q3+ccgI4VlqZz8=
=TjbA
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '85cad56f-a4c1-45b3-aa40-e2ac0d20221c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+JXIljF2KHjtzeMjxX0O/dVzbGPMnqJCMVvKRtzqKz4BQ
lFk8riuQtmnGZQpwvmJ024n82PQXQF7h2quZEGmaAcxzL51oRTCqENUSWtu+TzsS
gwDKGgdt0BEcJm1g8O+5YtPjwUuEqkFUm0gchDe0FKxCKIx6x+if04q7D6WF/ADp
FtndLJpLLiQKE8fuw7iD9ROVfTBw2SbNdxooTUsGDREHro7/3gcAiMBK3rVIRU9M
Bi400sORyTluv7UHoTeM+jLoe1gamHfJVD3hk1tU2anYBNxM6fb03qzRzJzq1z8/
afDSy2NU3ZFVMoIRLGVY7VCCYCBz1WSHxoKn19kCfEYiGGvFsYo3xFpbVrftkFw/
KlZFnptFmlfexKvMXGXX1eHJXV4keZr5oZxWuSQrrxNeO4hbURA3sVHMy1TFe19y
gxPgphlL45LXFb4Lm9E4ZVMQSHIk3tISis4ySMAy229s8GrfgzupcAVCqboLJSm5
Ymge8KHZFcgHhDumQTAfIIDw2MUcXdge2O8PMGKnnrvwklYd1cPEDDKAI34R/hw6
1SWbH9AqTcZ+CjceGois29GA7bKk/DHJM2QtsN+OhsMECsXq1UqX/K1CAMB3bZIv
5a5Wdf8zJ8Fst9SjA2OnJd+gWfKoj9rQ4HmZqE++trwkgdNCSQXZG1ptsEvz4prS
QAG5F2QPhoiCtpOmV0j67LZxwMAIJpDId+gOhjSKwROx3hsd31aNmt4PicpC6bK3
mkBWFYVZXsISTeymwxJ19PM=
=4tGy
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '86411b7e-7c0c-4c30-ac27-550729c75fb0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Xzb1ypFdaQ49zsNaAW3ayrJxLAMCfsMS1lNmJxEffiZi
Ul8lxwfjB95n+/oWUjECoRg9sqaEjmtJeJJ1XS5nPSLlpyH5MDDH2CFGBO1uCS1W
zzruTYoRHqxM8j3+ED/lvDTtEW6D6ZqvFEQ1Z3Aq/3lxXa4ElykPJw+SByOKtBQB
TUV6tNVjboilmqX1JtuvPxQLriIEZXhObJXNy39bhx5gbgdJFOqq6zecrLHSP9HC
C/X65bBkaWDtUfU3sPJQbJZwiLx0+4Xde7iunTpuAN5VCcNSdl4gkMV2oajN/eiz
Gkz8u5K4uXXXdFC57KwmotzwM29dF7iV/kVf7eMdo4Y1uy1U/4W0FtIfdkPIcjM4
vSBiZtUP0QFpFzxOqz73VK/PhN/Jp99SXdP6dBR6xtPyUvn8JVoFXZSr/uAUQTld
PnnJTUZ2ACuu2Jpt2Qk+6a+14ZND4FUJuQq32TUaEpjjyPuXwJmRZh78Lsc76Exz
CL4vrhLnnkxycIQ4/xYU3V5C7bwcRZLisPPa0uUZrBAxlGwNuLPxDzmtA0QB77fk
Q8TznQUAGbGglVifUej3OOxMqA+AYBMBVd/YjZuPnMUP7VyvM5gLIYf73j0ewlYj
EvfS1nVl4DSgcBJUllv5ea2Q3tekGAfYpC5TXs/0m+wUJ9/N1C1mFOS9SCCcVhTS
QQEUMmI0uFf5sYZhm816o3cqDzrD1b5kTHhZFTmaYf/imsTm9ASVn6Jm1MQ1U/SA
qjUqRekbapgOk/rEiZkjKyCv
=BHfZ
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '87e443dc-52d7-4955-abc0-56bc7d4b1ff8',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//X0dBQQLXXAUOdGUo1+W3cGbzVDkAm/ZLphT3nv45UX0p
N4/jLlwA6tUp6nS71bVGYB0AmAEofllqmW2f0a1bpNCGG0rqfDIFQvRa735yCwJJ
cDjKIHqkc4Xe/cvgHrql0o86lJbUYzn9Fg9EcNK8wfZ3kSE2lr7BedHghTHhq1iF
1i7fZDnzP0ygsk0Hzq0VTvSCOB1OyW+TVHfPBcxP3aNZkyYm3193CGtwPseElr3w
3sk9Mihs0KbP/u1pXG/79tM2pVCX4xxMml175EVAN84VPaFNBUvEaNKiTBdAMx4n
w8/YpBl2s7zsm2M2/LYf7sc0f+3o1fhGt7um4ZFG7K9sBABq+TkguU7Py7Eu/eFH
FwFG2dXC4VH41gLNbui7E0WfFFDfd213cGNqQAvyxQcpJAXX10HV1p1CeOf+DPDN
Y5n9lanWaFTOmO20fqlxQA5DlzQ+9AMN/TwKoutUbh9K6UuNflyqGsfugjJe5mIA
X3/pedG1J4xOMHdryLzt4CDjjGK8Um5ZmOgRFZRKznhuo20p41CkJOsUFSi10BWp
xw4jivVzcqc/WeNomG21/LSQOCW+WAOKlVQirH7H16iP0/g04mRwnQveT3QS59Xg
dSzqFrTYMYjxfRFlWFQ/6gy1DufuQpoEg/K69tiIW20ktA4EzCY6nhRhawN6Jt/S
QQGtidtcy/rD2//F3IWXAfQQ0kZ1pP++oMuKeGEIrURpW9xMo1frJdOK15C2aTHx
KYBTzIXBWmxMar+dEzTbvwOY
=9PVu
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8b3dc187-48fb-4b16-a8a3-c2c1c1f63343',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//ZQIbDXYrw5Wz3C3ayWRM1TsbdwaqzSnpcdDKJgu9Fhy7
NPl9mdXoQxO2j9Kb2BuMHyvwSo0Qu85sdC2H/UQdEXzY0S0Mx+VCFzz9zGhxVhgR
7ClhVvkYETB+xw+VFPXL+Ypb/zmC8y0s5yvnqKuJL+a/5BPF9zZxH//xonE72gdV
OBPwKOphJuHAYlinTjVi/Bf76gVegRasPkGgDeBdc8hNCPh+lnSmHR0RQOKwe32y
ghkOiPmrvbj+Qw24DXnMZBoP9kL8YDE7DLL+g/V0JZK4fcd//DXj9Kq87hgPb1t8
rWAuc0lHKYdRm+vCHtxXWPTlMl1vo0l4ea+oul8JFL82OIZT39/PKyFlLhWYdrhR
WNnYQcq/9Y2rtOmolQMC2l/lzjwieZQVXESyrJyolhZ1aPEX+4k7dwJGF2THUTOZ
9uXD45IuHNIC66kD3iGrW4qUklxW2dSsOINkShzwu1DLm9aUvEHtZ/XEqWKVU/wa
3gVLCvpLJxqtD3O+WZ3Sg1Qp/nL8POBOfwl85rmzfwgvrse/KirFu38/XZwosQxy
G4dEZO1x4wWlMx940A0oHu76FIqdSQPdBwtWvGEoNBQFYGzZCRm9c8KD2Q5cEeDq
3bQ/SMCHyA/L+n+ExLv3bENcNcdPwz2vtzw6bPwjUKRC8RSSyNv1cboI7OuJsLDS
QwGAZaDGevZdvY4rbeUIDx8YJL6lrmDHhMsNQ4tfpDVpvMbbOKjmuOgeR7k639dR
2qy5EWS1P9eJdn1RcGvv/pKSPHg=
=A9g5
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '941df689-5b4c-43db-a799-2a703a5c1062',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+M/invzPZhJkdDj7ztH9eE3eBWJi+6M0AOXdmqhEchiMZ
GvqCFSeWr/7dk1BjicJrZ2GZAMAG3ISrFr61P9925K0PvZU90jBnko5dgaDmsC0j
7+9t2upy928EryAaFh8UGK+COiXSm4UvQpQdoNSrHpFZRyPyv0Pehh8L+W5yTu5I
atCzlcZTcbwZpyD5j2I1nrCAerWZKw1xBINxLJxyZzUbo40a/7ixYRHQPzKM3E3A
4aGjjOUnZVfzGy7cjUkqdzp/ls3evsca+B7YLfGj39Tf9TnDY8FBwe2T722vBHgs
6rqmPfxSVgWyprcW5wsqG5NlfqIVz+WPJlNXqs/+CqPhj0onTQQABd1ed9TrOzus
ToTycGFQmvnxp8aAGYqG5hxfXcnthWDAUU7iHqhZWNuuQ9Eas1hXFTYtpVdHTnun
J6jBWLaGKFuyQfPYkK5rjBTJuaWZYo2AiCwIyBLPOn1oV/2GArMZpbmSr83TAmbW
Y3SgsdMaHFtMe0E4lZIVIGiwfaxD6GupeoZ5lO/CTD7Hh2g3VnHc74v09x59Fpsz
MUa6CPtqd+Lmb5nHSoiJijcBnwhKK2Kugt+OWDR/Wod5aScP7oHEMEXJhA7Ej9+3
+fGYl9+o/vwnHunc8UG/I1jXbouPqxNJdj8SIwFhmQuVdXaWEk0xUjylo9rOhknS
QwGl3IAvLhy+tIlS5kumzSE+jB/PfBs9R2DCV9VAe8lh9WcL2IYfu0X+c3bEuaAR
hICs5oJMHJ293VZQnXySNqvouNY=
=zC/R
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '954ffc8c-a0b2-4628-a203-33df46cef0f5',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAriOI18jB0nXZIuHTGb/b+pMri6bJjVgWj01batWe/omV
NHGpe8TdCanEBzfkGM2t7lbzkq8QGqvHtPYM+oOzQzvLfkSNczCCk+t9bjKACeFa
NkGrHesmjJTIRzZAelNS/A5S9KUv8Y/Ti3Wo/25pmvKGmvKidjf/UP5C1IaCgh4s
7VEzpcGceEJ9M+ZLp3B1WEhVIat6Q/hjxtdUkjJq3WJHZNzTImgqj46ABFXR1c3X
UKH/U8RKW63BAFIMzPZVMc3Q4DdRJtpi/x5li0NeKESfBDLpQbqszsaREl/3pcgr
23yTLZyl+VKYCopfmpt8eUDHb8uELw4EN+I3gmVYaaHPwbWhfF14RN8m+e2OSIyE
MHb90c4BwBF4gJljf6HRYdt8Bbzq0ZwsgSKk/BwMME9wcsUa31Mh9Dc9e1OfuHeE
wc5ERDXKlM1895NHwGHouDJbtYDZFD0651VS4XTZtwYByw7ATEQm55FWp0+gsiH3
Y7Mmpq39I7XUVR+/iaGBD5Ed55xrey6c0QGM7DzSFyjnQ//174WnJvdzfzkfyoXz
nHEPh2CmkQqVNhQOcfwFlL6S4GUw+qoDGrzRH/0tKTh5429Tj33qjTcCNOtQWigE
RgwTDTEw3Gvg1vTmMLW6LPQsOYz7C9BHQNw51Pdw88h81iv59bROek/ZG17ywVbS
QwHrs9R9W0O0Ytllg4KRUh3pngkIJkQi+EoOHblDlA72CKso1sHQJk0ge56Is10m
XpV0gNGyu43vBRK1aN+3u5Gwi2E=
=C1Sw
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '95789d27-f8e8-44fd-a66d-1a81eb71fb30',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/6A0x3OWpqRFoHIW2QaLVW+cHZ+5Om3N5SN4PA/lVDnh7d
K/yhPpV14YPE8tuyAuq7JGENFLqnoK18GwBRFvnWhskPotIDPYusGQ4kQYU0OsFE
SseLiwDJIW2izF6ugdcEtg5F+npHpM23StAYFmrckEdPPW8YkZq4hiH2/o46RSfV
U7CZEzuteUmRg2DFndWICVCal1EyR++lY43EtnxICdt1bbVVNrFYL92P8kG+3N4a
ktBK2VzxqsCNod3KnWSY0qSCYsVIPTN4DYA95qMYUMbDXbDRdizeBrBIp9tkEcy1
JubavmpzMAXB+JXsAu5hiJoiXWnkImAm4Qwb23b9sKhyCIyKLcfU2EEQf+8ZyMMT
AmH4dS4HVg/OuSu91lQJ5ZLb59wb/DQcX07lkmVGyIFqSr36a74++z1pFOwUPDyB
IEwJUZHuMswfX7AeTNhLHt60XhOwn73cDVY3d2XeBtzY+KaPv1g6z9ExM3vdXgih
Kt/I8cB9KZtpEEmFWgBjfQBFWI3WoC8LufByB+RFG0WYMSVd/ClnKiUvnRq7M7D9
SgQJK8gcef6X48sQQOSC7+fycA4MB30V64Hrdh6RNpP06ErMAXqga/0+i5ccvJ7O
E1AH5kNq/cW3NMROK+2HWDX6aKhfly0/EScpwbgLHRNf+ERoi+rFQCqjM1Ui3CrS
QAF3Wp2yCkmB/YIAlm/DTSR8xVny7oXETO84B+mqHh/jZxljOeU2WaJPIsTJjY3R
70BIb70Yfg7rBVjy/PtMN1U=
=hbST
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9bc20e90-12c5-4d42-ab07-a0590a389e3b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NDtFjdmhG5YfdtJa7VOAoMP5Hq71HbgBKwKwpnm/1B0D
9aKXPgD2SC16JfWGVEbPrVq9YbhRxn56Dqbek4DoKZ7V6HNeMtu9GhE9adgOJyiV
T5g8KOAn4XkYidKAMp7Dn8T8Ai2V+pGeXm30B9gQ8Q4zXe03e42n7QgaXahao83j
YMda1k43/mf+ufJhpOKOWwW4zgrQJqZq2saRorykRpbkUyjM3QEZgKeNeWbZSYSD
hB7AJRrFWyxdov2jVdUcInnQCqRri5KJwKG3Ip1BEckX5HfVqaL41IFPf6oOl521
R74wOWWt3wyBlnUMfxpeisUjPFmjNXyN5+ITCGbiEuMELDk/sY0D+/gMFQTbaLFT
b/T/qd9BAlvkw2+03LlKl31DmQSZeKN0v+afP+sQk0Kcih21RyPfoZ0ElG+gbsMO
cHusnUo4lcgrWeXMYqRWL3NYeeMd6n4nJhPXVYofSo3xtN0TdXxIVKg+MxgilqY8
ZE2nSjQRJvYjCjGwAxyEuozTB8uKvGvRtWgzRYdiQnkea3SC9BVYjf3bWgip8DZX
M6Na0tUe0aA0fz1+MW/Ymqgibt7CzmySS/K4DyPziN1aHYDQW1RmjyYaxW+mLnb9
KTvqksvVpNiRjePTP0ozl4wy11SWnh4G2SQldsXdBBzeeInXmOBV0FV71X0lHGrS
RQHN8jV21epN4UG7r1doMogA6kpDdcVrSoB/+53zFUJiR0VE8C8FvvIUkDzeHDPN
+Yv72rhx2mBnPD0xZFA5lHCXXlid9Q==
=hTOl
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a6733375-8125-4b7a-ae4f-83f35cc1726a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAr3VLo7pF78IkkBYvz9DMHJCIhRqZhvH6SXB2SW1fTbIv
vuoMxDoMCUKbaHrV+2MVc5gJpUGVLk0BTq+xH7KLu0uEEBt9eP8HKzsGkGMyVIvb
Ef/+MT6AdxSC/eVho3m7S0QqGRKecNAAz7qnPZspW48aPyVBz3PDeaeGmyC+fi77
uSZUdotEtn/E8LaebX+K4F8+yXruV1d6iu1mrqQHhinP+vbvjy3rHz21Fr4++XpS
LZEwonRuYcuG50qZHmF84Yu/lLlHt2ogaEKW/uhgoG8R6YkS+EuOzMm4nR+tSeRM
jsCalDblN9hz2ATVx3+6TpFmwN0N0RIEc+vOnhWEb7RMG0+SEJYkZucMiI98RDzz
4zR5lemYFjfXDH01WficA9k+WHPP7oVqAjHET8/7e7jq3aRCqDQT92FidgJxuSBe
cR0DUsyYd/QYUWf0/0Mxtst5MeV701ktSGnwaHkQexWJorxRoCsOQRu7oa0s5j1B
0BmUEFhPjk3CJmg64hpdfoWIItx0Os5eQU9/vlXBaCnYDTZ3V/KuFOj+x/aNWWgQ
Qd1mEGPNQEmiRSqpirZ6Ugiy6sRWKUqQ7QpVVDRYgC/wGyofBWW5fohoT5j6bMy/
YnPIyM2QzIpKwewI8v52V0KbtgZeMzmwsJANp5jUiSDagYHWa735afBIFIYC/3zS
PwFjAoX3xf5XIuZyqMEJFCzrefTgUsf/EV22HkzYwP3B6Xh376I4LHxNuJcD6Fhw
7mE3j2dR7nWa4l3QM0uV9g==
=065A
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a8b32deb-4aa7-4dfd-a539-df3b2f821ec6',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+OtP2rMjRTJW4PKGrJx0IsXRXxray01zJ43A4Nh6bII5/
VIS0OYRSU5w66nDjkE+parJqOFRgIHHBhKJJKRz7O3NUrlodmS9t8CCvRt8Y1vQi
L63wJyWWn0ixvEZFMouSydrnSvK9ijXYkhAG2StbAC917LvIn25KTmyGm9KUWLnY
VwF7TpiU7BDY/cDhd9DwrZF6BwYcOzZx1f1S82fYpF1jpqyYB5bu2EKWlvDpq8Mx
LVoySHOlwuIY1HmmiCY3EVvFYo9Gef5QUxpBdQ1cZ/2A7dsBq0YpAOM6LmfYnLEy
728yG3Z1KvgN/wN0Uy6QcXPcu5YaBOqM0Yu0kSpoYFOuWe/bpzEEnpL8C2cls4Tb
zNAJz/WIY53xjH5Izfpd6pssp97CrmWo34B+6rCUG34a0f/mTSHHslVC0vTC4fVe
Y+24+N+krJkll32cbnPP6WycT4k7wezqUjq9a00rcXafldD34AY5g2kr+xcywxFZ
jqLuwETI/dn1gd33Fp1BGU5evS/So/fRHIG0Qo9FLQlbQiOZmpTx+C3MGWCpE5t4
CoDE2ROxISOWT3vea5xtaQ/1aYtzbSou+/mWom8WDndPOvBZQhHy5CDtmbxphKtX
aLfGwj7egdlE1Hum6kK4jpPE6NDiHh0WJovIbXtmqIxxfX/DNjSXZQrFlxyPQ2jS
QQGKNc0zM6VyGVh7dwvE9MWs5J+kHreGdS/e4aiHdDUWci5EyfgM6jzSTw7pO+/e
Pt2Kb6CWxrgHuxViC6ItFz1d
=T65W
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b58afdbe-e986-47ce-a31d-4ff075863474',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9FKF4NMVD6SptlqXBt+p+6ierHfvwoVhfL9XTQw8tE+4Y
DZLlZGqnPzg/OfOpfaqOPrBGB214C45eTiJwJHeFqJ5Nb+kWEDWdmMZNzcDDGNx4
D2jLkX8J9GPYlJehJN9MsyDo0vEXqReP9KFablO7VzmyKILlurvJ7SklAVnNdA+K
n7yPEH4x1MuVFgFrp7ED/0/nRGxkt5FWsvXK2blzzPH7SbBSXBxhHmaa7K5vOMnX
kTbBHDmy3hExKS+4kW2ASeKYXfF9gAgqHjRtqX6lJIhmk1eZZGYBme2m4lnnYJy5
0O12X4g8Lt0DSWeiENQylUpRKYiYoelp5jUXK49gMKyhDrPL18hsbwVBwdJEnIn7
6BACzUViyyIhaJiKcDbJnMoKDmdN5uPybdD3/VlAg5JjDfUbbTe1a/tw7qqZH4bw
qCj2sTLPqBUmuAdZQlXoDvjX9fGQTWuT+JYuW9k2JLU2gB6PAT9WklY7w4hy/EaO
vzO9Iz0JJP0U2/dhO696U7zsbbn3pmsOeqJCt8H6JiNyF3e5vA3LTsjfArSjhWzi
oICijb0FX33G2KWJ+cMI13AmjVHtbaUClQziSFdqGk/4gqX34T6LSYhOAUWt7E+i
18NjHJvsCFBRmPbUbMln+G+kuU/Yq2+S3mpKTJDkKoMNTzi1Hmzhe4qYT4MRY7zS
QQHux1a/A5n3y0fw1BFcsOyTQRlz1Wtckmb5+qkQq10aiA9M9Gqz00NrxuQYNq7x
R5LbcwSXXrDv8vDDLuoG2jjO
=SFl2
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bef8bcfa-96df-4726-a88c-f81b8dac129e',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAApEs66/w44wwIGpB0EDV7mQYGt8tI3+iDx3KmMXVO9Fgb
Uyr3ZQdjvyJTSxjOCMSOg3eQLUXxDzt+XZIWQbuCPNePM9qonMFcEqOliNfCjxds
7dB5OcSG0yw1WrA+PLKfUbC/lcCyl5xM788rASFxF5AODr3h67+TzGyRiNE1QFYS
asfQu5kJ5Y9O8zsdbOL3P8UOIEjw0O3x3sUtNnBbbQAw/nUaQ8CNno0sQyfcvCkZ
Rq2Q16pEjtvkfIg9KVXJNzakurRH4DcS2ZpUhmH23YF938h3LhHfoG8c951yly2H
icFLpUsjFgj7vVc5cl6+5crB+xKYCCRXGtW2hzmEJAB5l3eujWjcmmlJk6OgHZ2K
3x5PyJNFl7U9pC19DXoMrznQwVmvkh2bpZ3E2LKdfvU0aGEHcBxtL/upUQkoMLxg
W1NPzG7kAv7a4lZWcY+zTnZ+zrbLIOxDtMnimbOlqROWIbbe6HNaVNYw55Dt0GCp
Bkvw9FDuXD8at9ROvleEBChWxfCHrLFMOLyzwpXILU4ABryNt2bqIREjoIoNj3BH
ideCKCNn6Nh2IuZMMfAX/TM7a3macNkD0/5BywioFcc5dt5MrfaAQCP47THKlWCQ
BRL+dqUaPc3tqYT1CplIjDdipV2ddI6Jr/NRxtknbcjLy+5Q9v3bIvkabPusONvS
PgHxcrGO/dZ6bDu65VHqfkd+Ok1Rbn4PKu9N1N3JnYKlF4ezFFsOBxtRVEt+mtUS
8wHkgnVWjwMNBxK6lydJ
=1lvn
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bfa916cd-d072-4586-ab12-72761cf6ed0d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+L3m3RtXy0I0QgdDrp02KeApcUNzVfn9c2NKkrWDveRGD
h51JVHUbxRtOLBiO4Esp8k8lzNYY2SWkkHx+tBvY/ZHgkTgs8QPto+/qCJtwNlO5
FmZKWD2FcWeVktMnbx4B/QxU71MKZZNl+HVBTAK3ivJG/55P/PmkPL2oedxuAce0
JrBCWXJ6pVHwHWXOpo7+ZzOHlWApYIgnUWjHrA9eQcId/lvvG+GS5IS7FBHlfpQh
1e0OqUPUj1BGpnaFPNrUvbAi30cUlsSdT7nXOSFy5ySCEe4C8AW+p/BHSxceeCVF
urXTs2C3j3f6eITtJ7s+7cKfGkphTtC+JT170JyPw6r5r1x2tyQTYplxYdi0htHy
t/VwzD2EyD7ooW5iRZK4CQEVxRen+uFuEzCDgX1fq9ffJkPObl6QEqHZQPyhgE0i
D9wMhhxN/sACBBiy4J+c4S2kFjn+a53iN4h022LZy7UTvTQ5DQTXPMTw1TJ9BXJY
m4WNM+BShJB5yFV2H3a6FZ7fheOCVi7RoKQBXQ6Xcjwyf/4QrpHhiODznJHFwFoj
qH458JpG6AOzoIQU5QKiUvGUS9LxwKO6IlIl27aPHWAwgl5EnCHlZe8+w404rdlA
kyOrzYG6+ZqO9FqKl14ECu1npLYo3hk9hqzMfYfBZjv9FFMsMlDvm6ltPb/rdw3S
QwF8W3i6wA8FCQUsxv0HrUzEWBUAfRoAZ+5DXqDO7Ey6b6Gytj9110MXdZNNbbvL
PuK80cCrg1sp04uwMINZXQ1vmbY=
=6yNJ
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bff22ada-eb65-4622-a96a-9bfe5367c97a',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+MabvDO5+SMy5k+NXXCAh4r8/BMyCD0McapnBO6Bn8Wxq
G1jFM2rg8kMI0J+oXG40Uve97xPT27mRgurQ7aqQPl5HQB/WFAcmXlucxn8Cfsae
fvhdAvXLRQTnZM0oNnA4nebNvbZIo9jV97SJN6htl2VJ8dUSB33ZwJDafp5BJ+XF
s1TfCEjYSMqUfrjULogG8t+Egm4emm3pRdiRqyw5GwITC6z/y+H9IXgMgzJ4y7/v
kb7SeUUu5nj1wvyVU51ZgOMQW1IhWjLDlenTGPH8oPlDiGqrSAjXJ7zDKziEXmh9
MSYo4080+9+D53hO9g9iyQlyR6qH2Yw3gBZ2DoZ0H7vr1Q9PbuQKRg0GSQL8zZDB
RsO0+Owk/hzCpGXFP6btgYdiKkQIG8+ljk6SFbQxhJEiIv5ZrVWZ8zp0VygCGazY
VzygOgbLOh9DJBN/Fkb1j8PsOjbBOEUhXofMCdgENOe6ZP1kdb+ul3RMWDdW8E9W
FagMBArfKtABCTcg24xx90OnMHGsOC3HJDSzmt/FLdODZE6jvNuBImO0ky9DXWFU
QaGNVq0vh5cY0jfJFKEI/VGUgSZ6fiCuMV2/4309syOVe5ZaNnaiGLHdnyYthgNe
MrycQgbdMcSza8Mg71rEFdAhp6xEIqGFp1SgKKzj0zsREEyU3cpzpERHFlc3e5fS
SQH+tdzyn9KQt9oCGcb3JuxzGd7Iq4LPh2b6TcPW15QDN0BxcR5TBALuqgzBhl8F
Pj8Op8B+kthdSdX+SrRnqE0H+YG39cl595E=
=7375
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c6fd812c-ae1c-4b18-ac8c-fdc282c7caf7',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAheOhAxNuyaQcs88AXIyce4ixdUOW4pjqGI/wbEo4s7Vg
cXS2DiE+FDKKC7jbapZmmrob/EcPYxbrYrAIp2kBtCDJOIxKcd8GfYAvvESPf2/8
Ovv/kGsjQ8mko8zObV6vgtRw1EqgF7t5O2avasEZI8rGjIsfBkMG9fEV6TQrRxev
bCC1F/w0RbPcJyqFe3TiGa2Df0/U8LfjTXC2NyiHVOF7/8WZQJw0HCOzRKNieova
q0LpZ6oVDCR+09EntPOIVbRIXJjrkH1ixYORtJvoEIDI4zDGji6hD9yHFU8khrRz
Li4Hc27Vxf6bry9lfo8tu4PKoEMAI2nITvj3dDhYxObG7hH7INE+1Kzt6jNaxcdq
tEPFeU7c+U1m7nmWtclDcvcXQxSw/TyofB1CSptJP8nO1H3FOH+ytjUppsisMfHY
A138W6jtZ5t3aEJQ9Uqrjq14vl8CMSX+x9veyZjlOyt3FvFmndZBizVt/iaV6Cwa
dIJ0k5de8gFc3ENxD2dDlC6z/IfQb9Z9PYQN4aRdW+f2VcG5KMmM8K8P+055i5WP
sNFxrBVMH6h+CnQkNdiWS09mt2anzUp7nWZSGv9MrZtybQZrO7bQJwiWA5MtS58p
Mjc+ULk1eR0+7lO4G+cardHUVCrcuue9A/G90/4/Rbg5MZt5UZKtsBHzHZMs6XfS
QQFPkIyFO7gLWdU6QCO8hznVRlKmv9X8JTc/2fnUXQkr3byHbZcLOHTiKhK1ptjy
4CmeoyXiAyvL3Mt66D/UM71q
=A7B8
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c8da2381-a3f6-45cf-a971-0cbb2e1b8375',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAhfgS3eUL5AFbtZMky86HxPChVZdheJnTNo8ITqIgzrwU
XlSmvaVwSjrYo4lRujlbqCBqhgGkRWLxNjF3+v7tsGtBaJi0jKSooyK9Tn8HvHmN
PkXi27v0nJeWjvzyqBFk+gZyytDg0Mmi2wVAJswBQaaS5JUHfb9CDH/z7jwsgGtl
PH7MQr8peMzfZ3Tosz14d89PfmEG17cE5vix3KXKtIpHo29lf7lBrLcvTuyo5x3B
Ft+G+CnWP1KMWnwsj9Mvsu6aP+JWdkS/TVA3ZKQc+AfjoWlYc+EMLh7XH0Ew3ZrQ
fMjq5m6pKWM90pu2G0MMSh6/Ux/0pmSp2JZQzoleeKH2l6Mfd+eUiQEWHq2avaEU
OnRC7paZ65ZYmYkgJV0M8ua8b8mUY/Q8CVAeW1FTMyzSpqN5+/TXhfsTr96a0u6L
orFOROKXUbDOHIzNivCIbaQiA63VjEw6OJWRS0HdqsXOGfsLr/2DJMh7ZVcf7L5U
eNGLFIE0yZQRIgRu8cOLGoWFwlWnUYv69hP1xXYwCiZlq3KNgo1WLNK+FQPRFLgR
RmphCp8yhpd++WIKUMf7R2U93WczZYaGRFJVx0pr9SZ0MsdvEaXsytUFCy20EGd2
Z7PJHYZwp3GYzdiCOIaw+GYXKusvzaVLBTvQjNGYcpCsK6v4YWW11DDgVcrqervS
QQG8I14OXQNFKkne6Z7PQBUImpC6YLtYTpX29YOvDkwn6hCkK5C62advi1xxbvoT
1352A1zsamcddbBYNtLC65WU
=twmv
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ca30cf9d-f2a9-41d1-a489-9328816200ad',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+Mwy0Lk0iUgSCPHhv2HwkigwbrFpyhy5IbSbaos4E6Zq4
qCi2nlbCXh5XKt1VXxFEFpDF1fYM5bn2x2S5t2sbkO3GkJQdpA2COO/MMLbMkh+I
ui+V5ffPGSFl2HEHIWRsVaHYNgeIpcFvz1mSld8m3fCjntTom0fbq2q+0pDabpQP
bNTr77cQ/2j60uh0ZiIJal2BbkSIR9UyZ8maAX6hYmnr+wHvu5kcQVkQchoSHwIg
7M9Pmbggi8VngSJPyFxQ4OdvmCiiT+Pc062BfRyE3eLt4LHMyuQh8c1FsqdnXvei
lzQs1f99VUGQ/bglEfMn9O0QrAkr/zsbCuCGSIGP6EYKSrnMmrwNpdztW2TLc8bm
d6q4tqRGGmKJjQXqNZk6NLS4VJfFSyufT/lLhoGgv/Wlu9KI1JpWIbeiEp5RFLOR
8p2vvl2GVmazYHrLki+m0iHaQFOMxhKBeNV8gaTX6XRZvOXGyJVv+yfmCstMm5Zh
OBNKSFWbzmU0lWvEQNG9m343hvBhLLlXGNmfo7KWv/WkDQXOl2aTEivqlmBRNytI
zX0VLr/Hg0NQQBE4yZWCitsRGXQauotCJVCnUpS7lCqQrkmSUBQ/Juedc3Hr1WWu
i6fRSGL/un0yFz69WC1Plo6Wr4fyAlAfiD+U2sTNxmPHfTGfCXNDjratzJYXhRjS
QQENrRRxjVZWjtnlmfV7WlXMKRbmZOkJ79zBGd6GLoefX1TyvOR4teGuA2XEhe0b
JpGdCJs+zkl2uqSoqZIfAEL+
=3sOz
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cc39e9d7-3a96-40be-a164-22ba260b922c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//T8j7aykOnWgZfskRPXiBlYMq3DRq2nGT6mUbIOOVfWFJ
HUforQN8eE39bSNlfAb/R0VoDD5wLLmZzU7Bp4RyXQqOUmvb+slfs2qCp2nfNXwT
gZuAdOy1gDtubnv+Vz1EyXfk0pMzYImJx0UjE66SJtysi5YVvw6TbjOMK8kmf4nP
ayyd1CuAarpFRqfB/lgKdFvltNjVQ39tNGlnFGr8dZzK38elJx6A9/tx5zT9Z/0b
d2bnLgCPKg1kIcrfrotiF5fvooo5aIBjgbT6UvDzFlM9k4rMYQ/1OjfO56qkrlmp
5KQFwwR0ASp+Jf5Q6kGnGt4jKfqmxjHN8ti+wuHPDjYb4LOPFOUtcejipwOOeBrM
dsuxv/844UybsYHtcjdLq8Rn2J0WTVXMEEaJjNBBHtoTqBNF5R4cYZtc23aqmbTl
IlFZ9Snpd9DBEEOB8lQOlfT3ISsV4yXm2H+yJIm02D8W0QxtRFVKWwpIBk/7No3+
wbgqsSvuHpOALa11TmY4z1OZ0lVMCn1Qg3CNGJ2GdqZRMN2gNgXaH2sDNSq1rAfe
DkzjkTk6epp+97LNdzGLI4Zji48N8Uh+dTOOnZs0cDs+hXiwlX3jVX5P/J+RNg7x
d+wy/vN+tD6NLZCoUIXqZ8JRi3ccibMl1qcnb3iOsGpUqh/KSkZl1tYuiN8KQSfS
QQHZIlxqQzuhD4oGXLdJvmMr1pwZL2U5GFeP5Qm1BekS6InMRSL5FqG3YigPplIB
p+ESVFAqmrUyY+EqIL4h9i8b
=9CCW
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cd63d230-389e-4acc-a9d7-852aaa73a9c1',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//U9c9dCkE2G0asfT8xxD6dO4oqyerqeMu2k5YZBPiNo+v
pfawYCctSLYhIcAbTIK58Czkr9/b6q5GnI9m8J1wHj/9AC/sEinRGEMrnFaFBtDM
aDmTUv6FY+SSO28jCt8JPx5YIJYDOBA29BEWUEABVOCxqZz/RCm/+WoJHdbctaU8
GRFqlGZYMOgwm7ZeeBxdsfTxNPCvqv3hMCDRLbCJ+T+NlCvKCBusemYntOv17s3u
5xWvLHnz23ZM9qxmd1RjAoPlliuVltNA29k100RbEPrWq50UTWL1hg4dBDQoxTpz
WQugGZ05bLZ11zQq+SSFO0QGIK++Nc7IR9iaW4LY6dt9PMZxglFftZ9gpj2cHggs
0a+lXrLDILP6PDnWcOnxElZ4NsNDNUj5t/qk4b0flPtSRYePm+c8+/2z33KiItBY
aauOjlOE0UxIzXe54dHobhD2HGpZY09g5XqBgohxFoAqHs+FJy0EX2xxJsjzrIbU
eMp4DIA0fwS72g81p0iUWonMEsjfnHJq6LWKT68w5tKP1QSEs3bskWWgVX0e8bw7
dFwCAtqLKDKz2f5vI7dEDMG7oamqX4DjAi3KF2HnHdxa74iHv+rjTgGUNRkuF0dw
SX+7QoCbnRkMM6KG1MRC2kQ+k5ZUB7q+jrNzw6Lp0CI47WFKoKKQy624sktpOEDS
QwESUUaV+Jl3C5NlvtgoWGjmRKjULaBniivkyzAx78QTQ8CqsmD7RGxQJ66F+DeE
6JLw9rkccRJjzrUIlBbcr5cR+W8=
=QtRz
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ceacce63-12a5-4cb0-aed5-8fd22a32b74f',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/9HLJf9+ds3PjGXLcWx2v84f57K11LW8/WZn1gEOnUXq0G
MMaoKMTgVXnD/ZRmggMjR3OODaXr3Pmw7FmXj5bosg9lSLoEhSlyANDSdlvYfmnG
bbiydmzjPNzLaEEKVLS8K/Ac4aBc+o5TmigfzqdPyFvKoT/aleBnv3BfQJ9qo3Wg
2n/M50xSlKVxk2kMm6rBwiE02tFTGYrQazoC/X0XSkdc15iLYbOnpyGMpJmxw1G3
lmuGJGBzYYlXId8KqNapf92YO1p9ZTnaJZEnNAsZSHt8GAzqPplxigrRMSjNLMLO
0SRdGIbhrGf8WPfjUsOYctSyDhYaa3Cikvj7xftTAhon9CxYaEvVasd7y5sssvpd
+I+Vo0McR4I4jhi2bUAvTrKR5mazMz2DT2s13Sy/CsvNKb1GL17GBJ6JLI0+09O+
vdsF566ezsPV+QqQEPnWda2Xi9ke17jOW9EjbPMqYV5W1VbQuk6x7VZscwmG6Bi1
kyD6GnTCF3O9GdpxqPxJOKsrxzhaRzI8OKVLZHFyYxtipO4gratxe8u4IOtrDKpx
kEX4z4/Ao2A/PZgTwFeisMEWPQjjgdMWiQw+pJqguZQgxW5PqWDUKP0u7p7BFJEV
XjPsPrknR9RpZZDjStfJnsnHbFlA/pqBbGTEH41O30Yzn6q/ArFoNoLcTLyg6jvS
PgFQ4xFWtgVJeqI+eQls+1PVnitUnAbDqhiwlWEXyAY/KVBsxB6iu7JOCCPK//iY
LT6+VsDiIbUG4N7dhOqG
=AGWJ
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd16f2d81-1d8d-4e4c-a060-66c2ce441fd5',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAArsy9tigAlrxlqye3fo9wZjfwLlREVRTfawlXHo2BiAa6
S0Fze6dFyi9F77ipufhJ1epzQOGq7TziNDjTKuuu3fFpBVbsqZNbk+TK/iW0aTPV
R/nrNJfGsisxwatKv1zchjdAHEtCoZa9Oe+EO/0CR6m7qOjZEyJBkGi1CKm1yKJu
PKnUTI88nl+QwRq2GlhW5ID71QBofA/eCAaLNte9CvhwH8IZ+f8HaSgs9+9mepzg
tIRKOSz7iW0hp3qmnyQQ9kljdy1t/1HZdgCSPYaEuf5Ie9sXtLHwIlQENk/qXCU0
z0MZpKuH7mbKDK2x8oQQ3UdykQvKbq2Qic+i9vUodDthqJmX1jpTrNKo4Axzc5Fn
45q99v5vBr5RbRLSfhGJWTqPNz331qPpcQXiL37MZMeXdETEx9oGiSP05PODh6dO
ksExsC7hYjzTNauLPpeTJa4Xid0NY4lGgnQ7WZreRPx2xxEkDvGx0WvJtnRzIrY+
Q50mkg7Mwd6caB+uhDNcw4A8KauJd9cBE/KdYlK2PR5zjOy0aO4Nn20W+/INzOGd
CyWW/E8olbXDDOFSsNvg0Mzq/sg/tiAAFC0vBvpRlnlyf6mfFs/8Pc9VjSlsfO6Y
4XhK4Cd3T3HTPFsgEeAFofTbJ+03ajwFyRSfTeQ3qnDcEAaKl0fXxnQuDKCN7+3S
QwHKvYyPhIU5k09Tk+mU7gcXixYbRkQh+ekBRH+DsNODRKBqDsSNulBpe5K23woC
ESd0CxXfKcYKjpo+a7lRSTbDL6s=
=Z4wU
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd4eea3c8-b1b3-4d8d-ab7e-329518ecafa9',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAg2gbUjDIp+QdkVTZyGbb0cLvJb5IcnTat4XwmpC5lY9u
fImjiQsGFgDcUEYwpGTntoQnjlnQ2bkYHWkhPq1S+b1YJl6Ca+KpA2zfkGlsL7mO
Av9behBYzTs3XkyZE5QS9s2cq6YMv+te/srT+61K4Hc7dRd+4KnMW768ohXi5c7R
9s9FQL+keMGSOp/zwausUZY1KuUhBjbNIvzbxBsZYOo65UsCCguQTIXpTJbUUbiR
WCNvQX+X4Vu5ejuH6EW78n1fVhj/GFCJsFSP1jwlsPdZqQtB57N56Rk8QR+lVq0r
bB4eqvUCOhhMW4x5RgMkSHJKN3kXK4mMyhpcGn/3feFqyuXijRrXp0f1J4zkGgby
6x5AcxKmJhJHlPHC0b3xXvZpc6KE3dzBOCbnR03lAnIN/Bhp7yqHBk7sP4M1muTx
c/U0BURnuey8nSU443pJ0bgyk/QC42eAPpQDRvkXU22MVCXvT3ijmBg/cBmGFifN
qy/pPjtILI5LdFyL+B6Ikf9rVjVlOeFqErAtKCBNXqKAn5nIWhI9uaynFAWXIed9
T1SGNY/O/lD2ADMp1gMSUAvFtJxCGIqg8KyhxHdgtYJBYZ0gQ0+xBGYOPqUMWbWX
C5XlBN/j+VfG9ugkH09cBUCn1CmC3I8Jw1LddifHRs2r7TnMXcIsAjnI8qGmqCPS
RAFK6ixjOb5lMkx+BNWB+Kqsf+xamEJh8mR1To7EHhJ1MIIiUL/tS+mW+7XuReJI
MtGRIJHcaLxZ5ejQeeku/Z+zL8b8
=HMIy
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd6c2cbae-74c2-4246-aab5-1ab62424d78a',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAkzVPnMzG6f8KCu2PQP92yZq8tjsQncgsumLEji4DEJFk
jh2+MZPy5KIJrRvz88jQqvFdHaIcA5kMsa5rp3uw+m84vkRaQOx/igfGAnDfALL2
UcjKFrg5ILI/zsPgM3bfvlK4RMYI7dtXGsdlc6AA5cbnKvmTnYbnwMsv7Xh/m8Gu
ryd2sxhrmwdqGRmE9g2JPzCZbCwfXFpHBo/5vnLOtOzb7gBLF6XYDNG8POiz68Vq
QxrJ8C1ZT8tGTHtLEEW3f3RVCy+miTwAbaJ+j7R6rZwLehcumNvbYzD5RI6SOK9n
nlSyYqnDRHUv5lvGFUVPwsCu/6F1DK5yMbs0vrWwLzRp/u3QrnkO9bXwE0A8DeRg
FgPDPiYKddA+735v0x9f3/fX2AzIGMpXGEFXT1VvLaXtBAn52Ey+cTv2x7BwP0s5
VQDPbkK51vwpePMdoTjO0xmqhEkOzYGpqm+QfER9LpqPiZgC6YvQzhSoSYau3pzT
yC+a9+LI9wWcHWhOIOu8aNzgFd8RqxnhSjaAOChnGMV4/KTsVu2G6UtUtmZy/Kl1
m8rWusZBh+63hmR8wSG6DkIszwi2Q//wR7pL5ZBZu22BwQwHf7ePQwPmBBindsDl
Rhb2xLcGucNa0u5HMQHLkI+dpFWSpfltquOYuQ6jVD5nmqYKUgRnH4lrXrrZ4aTS
QAHIisKIkVqEKbGpxB56tBqNSpzUmH846s4m9iXVVl3AUEUy87Vc4ILNsD/sZNx0
fOLbg75msaIUbAhOVzgRd18=
=ovyU
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd988364c-22dc-43c8-a474-bcfb42ef6b5e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+OJJ4kQCkxK0hB72mcLTpIfp3+O30Yf26kx7fuz15UQe9
RSwc05DIyH1AOEl5ARzXIcYh+U95b8rXN2YABrctkMB2XyMTe3xzut18fUAkFJmv
EOGFwlZNqLJTgrVkylj4nkqfvE9+ZZMYYsCZxzaHYUz7OUuTjbxJVBDscAysUfer
AkpWm6VMMw8+O4HeL3iLusbduoL9Mra0/WceFyN1O21BktA4nwIv1SJEelhVrkDU
zfXXUHIFbrOndK+EdogluxqD+t1wQli0plCDmlYvRBotO4HObUDKWKqCRnsWka1d
IHanSsAruxt2RyQmXsVt/hAjg8fc57AUkuOBpmqPm0CYMvkTEPYLI4z41Xz34409
H3s7f6Wg2/JnnUsmXhZh8IhU2vHB2yKYVvgxWF4pmJ+pkQKs5HmN+U4CDHASDRb/
4RooD1sQZoV/rOEmUcjwx4S/+yJKKDF/ZYXfBJF/r+wa+PVPHzSj5IloPD5J5O6Q
MFqq3Oy7WuHKtmehdz1goPDXz5r2fr6Ak+B6FBrqJdOk+E/h+dVxAITNAwScYJ8c
m/PYlr53r2PtzzhQk7hm24kidJd5crnJYQqR4AYgF3QLrqi7mVgSsT2mMUXH1tBD
iS4OgHJ/n6bqamMtUEkbIM5G6T9vnF5zuDXzphJAXc7bVXbiCJ/BypTRXbcJTvDS
PwFCctf+FPcAJXtfxr4rczTf5jelR5K5eThNUkp7+vc6z8SZ7QA1XxVck4+24t8g
AvSfjn1dbpP6Y3rsUeIpGA==
=IZai
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'da772a73-e008-449f-abdb-e8b9b5aaca60',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAiq2a8wlx28RlfXHstq9a+Jnk4svYw1owrLTmBbamzMIi
ctRFWn4eSD8pfTmid2gmJAO4eGe1fg/aFwzr53AjnfIZyi2hFJllFeSwDRyCqmbk
PsMdk0R419WsRvogUeyX9A2f4IJ+zxnXk+CsDgQPVuRRtQkLQaBZkQqBRfP32Vka
ooUOBpP5Un2+zVEVaMSERHjPNyk7SNPESJkrsC5Y9H17CpsTe3mZ/uCcSY0BrAix
s9zr05UVyjRegQjItx1os91GzBhZsDclQQ+okz57UVpY3VoqLmUREdz6R6lRk9sA
Glt5mg3Iw3sWy7M+L/6qe7RgW0M4NfzO+lClQsXvchxF5pXMsA6/NywMbcpvAu9Q
/pR84CeWHnpmL+bG5AufyWxu7fdd0ymV+LdrwOtUBlIX8BKWkqmd0T9+qTfRJ7TO
hXi7aHVw8MbWUte8Z76nRzvJAy+tFE5l9Agu6c0ndtzU0RNXMIoBAkq8p2ZJAsun
y1twZrMS3bijlzc1FR60qJww55HouaXPjjs0Zz530j9SA+v6smQOHIy5EFHbjDw1
A5PAZNCGKj2v4tnGaSNVXeHTODrNpyCk4FccVNkaZ+tNWfHssjIsKTT0kgr084jm
qEHn6JDnqZtMfmRi9Lbzm10ZFXyo8PU8WFtlNiE/crHALYXUc9Va9hPVwlSQ82XS
RQHMFgK51xnIR3+i4Y8CB9A09zhqhdaCcwtU+bKBcB4W879K0aYoDeh6Vw4WYSj9
vnjTBOhL7Rigu4s5rqQ0IVEoeQVo6Q==
=9H4Z
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ddd76e3c-5620-4b63-a582-013644535df1',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAsL60va7TC84+tTJBG0d5tkAldvSkn+QvzwoYHSAkHQAc
97lt5n1B62B6jwqOYhHUuGIMdLahXgJ+tROKZSJq00Xc2UUtpmm/NH67IZcPsHlq
wTbVOicn+/ZHEfqsxwayawRCoWE3t6RB41xXMdDn+fay/m+aTBnG+0JpZwtw7ZeO
3LBDUoTpRLtPo1xEPUgWqmtfv8VwkjyJZGVyHoa9Ox7G5MmDcrKMdtiyMtpJf8T+
upW6r2bR5meT/kYZ4TQmydYvx+cXgwAh+LWn+pKeXFlHa32GQcdGc6SefVflFuKr
TosOB7LhyraLF+42EWb03MfuYrSF/NmjDHnEZ5nhmbwwSAwAo0CjoYb5THIAwI9q
mKWKljO9qw1ksedWWJrIGDvlr/oqZqFgrNWWN0zjrY8s8sSkfwYz+jgPBAggwxcD
EOIx8pRDG78FD7ArJ+GA4U6bTaLvpfEWYoSw1X5bJfbl2nH8zxtLIlyQzJmPiyD9
CqX+Z/AG4mHGwLn15DPlU3IrAnaQWAZAVNwUkq8pMfItxEar9eEhC8t97810+Uwj
u2kfQOrT/D9xEja0E+mwJo64WbeeDHtAIIMJ2TPhKKbC5jujW0nyUVzhfntNzZGO
VYpLeCCtqHDpD2QGuiV6EeduxjfM+eGyTxLaebVrgdT/MjG+6C465Lte1gX9A7jS
QwGqWNv8DePXWQEucjuv8NNbbsifIrmeXOz/qeOCBNk4vHGfzP+FXmjObJx4SR6g
N50kKVMGZAlMVxNHY28dm84o9as=
=fANb
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'df0ef507-7bf8-4fcd-a619-6c2941956729',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAi8Udzny4OdzHF9h5hBuZcweDiE8TY5r6U6ThombwKOLP
PPj+Ychlo81OApJakDdjPd8jXF90jO1Zg+1OExBk6hIUD1JrG1Mhal+qo62g7pbD
vvLkUywFJ0r7nOTa3D3Aqzoz5pWt/Jyh5H0DKpDIDNBxsMUYWmyXL+qwx1GP0wy4
KiVPq9XejS/d6gG63JQiA1MvJFqx6T2A8155ctgRFqP630DUPUQkNsKZOlIWmQHE
a24HeED3sUPRGJPhugA3j1UEsKmHSzV9826V9Fmm75OzAgJpG7XZn374zEtAZc/w
B4LzzN04LCRsehncLI80iG1U6dtDLYLlJzcewJ98ylJJUfE05nOf1vnlt9MsT/Hh
we+HofLt2RjTiJ/L8V95kfKaDpW+MTeTpH2S36xxfCHlN3Mtcsxg2YWHqvTbzxd9
PnBw3dq0ut5gRZxwHyM0A6+wPE+cF3g6VBchkxMi7tbiQym9PopT0J1YXqMk9Yy8
li83hnqraTBd7WrQs3aj/Vu+n84WpDJqlpG6MbbpBOlxvtpcGQ3HPVqOtc9tSjgd
yVDRm6sbk71iM81ySFlRCogM+fFJDqGCozc9wgoX1FaYDlEK3KZdHdD43Fy9eST2
0FaLk2qeyS+eBc6ODhjetxbPAj83YeHhx/ndTx4ivunIq0ORDbn0ekYhm6fxIsbS
QwHSw/ktu69BmwcIpOilIvXjyXkGFaMs/nfXLM4lIfrBrYmUzTtA1H21fosDp+g/
1PxLYv+99ImqnTPKu71DOIiXfcU=
=3Uel
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e91545f0-4591-4446-a15f-c66e7dce98c4',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAmeNmKF8XXySszJqBorO0F1FSc+L5qWsJtDjHdRqWFGhV
61XN2ZImDCIcNT4c+05W6TL3zzh0GvV+qQioeskhfrBTGj7cjhjb3y7L/f1AcrwH
L4s7rRPIIi3wQNDHqykD3XL8kFbN8eujlRe0wP7/oAntiDdakdcS2U2zzAOibXNL
dRYYfKIOkPveTuxIr4y2IgmYYYDqlr3hPBNt5qX//Hru59Nw6W2rSpAmLfAuCB/N
NAT/+BqOYM/7XivdvNJpCriRQi3ZcDJ5i0DyRBinWmY75t1fL2zPJnrvzhCe7ZFp
hmZFJHlYQD8kAr4GP4wHR4qpmWNv43BbNZtrAapGoHsiiPh6V+Afih0kS+au1Tf/
13oawdErGuEXAkVF0E2NCOTiMoQGTzciOcXwpswcCivfwwLt7afV9xqbRquyajqe
LRCCGGlgUtdC8gKNifObBPkVhDQsyRJyldc11NCe414UyhIiR4cKolFjI/NPGfnY
CpOb/gveHKLVqiGXvw0Nq28k0eNZMPnK1+6qGMg5M9X6djLxz1LCUJZ17vl/Zu53
mw18nyhzNwQheYYpolMdhDALwldjz1KQ6X7G9Ock/O96+4vNwQ0MpFV1bmSzHqMp
COAOzUHf7LWFSsbeWebLSCHIARQZ0DDsk1ARnRkwzPVMFbtQg0aIXUPMNMkP4S7S
QQGii8LYTJEthLnLlILNeIOriJnFSFGAxrvyffEZ73YU/XYQz/co9JJWkg3Su0c5
Hrba1sTaEYl8Kqh1909eTJpl
=r5gt
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eab6a575-ffc7-4744-ac52-8ec3e1ddad73',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+KdxPg9HidRPFhnJ/17gu917meipsTT8y8FFqWyg4kcV9
64BRCMUvtrYgvv2SolQvIydI0Xqxdx7kYTvqdPc7TgMfnYEkcw5ysdk5ScZdhd4W
TCCECphpnDQ9ECg76Jx3vr8oduftG66SdVelVZMqs3USOOlEEoi7Xu8zfy7/smLm
V90Sfp27XZ+lMT0NwaD9CqWa9mOp6s9pZq7VLgS+PllIQjmeCXyInjsGhn0npRpr
dCaVfAIYcwaHnoKmjyGlywoDaHXaJJeZ4FgL6ekY3POtwYY0e6GjbX2nN346nMpE
Wjme6ixU9HXOm6m3bbp/EPKRYOybINURyBsNerdjbs2ItX/uq4kVm+nLw6m5RTB0
3mDkctMIDvW6fxdFTH/tV+tzC7Kd1dubtaeGx87n9moOl5Bwk4LvwLuLvs9fo1Z1
jKpz/wdf5m1Pig+9lJmnHfPtWlmgzI1VW0Kw/EOUVhAaW0aCg+OuWK+6+4D9zGDX
LDxWy1MGY64mhW3Ej6UIcLVbqPHdDPcNtyq3l6UGhiDQkg7RyMzOxnjvhK0rMQwe
4vmd1hYUYo4nXhUeCMH7I9qiKrZMUxNZH2/ddgh9ldkKchU0mYDCPeoybA8rFPp/
gknGjXMJ9EWLyfrx8lBHoO5m5UfKzy/J2/sjU0UOoCZnNzPmpSc22qOc98oZKZ3S
SQHXQfy6eX0tXcfDLuGUXbwz1jzBqIszAnWj/QCXNzPdKRnPpmQ5eqAIa0VJFkgl
cuMjypROgmuCPC+CZiFVDG71/blMqXLzDFI=
=cC+G
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ec277d96-3b49-4397-a716-3dede2269b69',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAg8eHt/vPsZtFz5qw8KwbDUi55ksHqGsa5kb7kANe1/Q0
BhhZ09SoGbFmacInuhPtbNgoddmRCdlal1NlE0yyzPvci58owFGi1wg7R/XTMT+D
fg6TOx7Wn++zADWbRsBcfFRG4n1xTxUJBFZqioyP4QeM8KsPKU3jaC7hC22tXPRE
gd2+Ped4Gs4ZmKaBgLNExDCwV6sg+MjY5HMGXWOibG8mU5ZJ6Z5DvK5RISt7aqTX
DvF+iy21LXmAi3eFYyt6OaNiXBg/VPIZPuTdAWk3wxA/nlzJSCatKPf4ZsSKrS/X
aDVUgVH/iPRycXkJUfiMvrUyc3m16uLPJllsbLNpaDqrHeF51AuT+drA0yX85uDP
1mQ7MqupEv0Hs1Npa8Q93MKKVdKRAovKcexhAnfG6V/OD3PkAi7I5jIM8hteBoZs
qb5MEnf3QAgFn218tsTck4kW5BcpWDW70j42X2no2jrzghryinFYqh5OM+iKTzII
S8tVfAj4gHV1oQgiW4wHmE0EmG2MEC66+xL5uoKGAFI3wLL0LwMrnE+bgH8RqTzY
9cToio/mcZy2qVHkqb8laTgUSwHP5T4/MULPQ0VXIzJUNmzRjFHOwQ8K3gLzgJFp
riEhG9krlV/QdcF9ip3Ay3Y4oTLc38Bd5ONeQkyDxKxFW183NrJaBbd1EpSPtqrS
QAEenQVG+wRXQwGIxfPB4tIjLFeGklhrWYKyygLqKQWJdo0dKXaPsLcMCPCxpRIM
vqQP1D96r6AdKvEDTdTc/tA=
=NuiX
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eebad7df-8a95-4a12-a7af-9a9d1d555a4c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAqjQSPgy3Hei3B+A7BURcpao4dP+VNrUoFbM8FkShrcQX
frgDLkaBcLh+ndfm8OWXmlWPfksJVzqUxgZhfAZXLkU4DSK/URW01R5Ltp4h7ZnQ
0nThIDfwPdGdDg5Vl4DReXXvBJ30ad2CzHQr78D43+q6jF6Fs/ZMrWctJ5CqrKI6
0JmqVI0jE8gXVNDcCEQc/7KLuKsC2JWetKg0NyC2WWYFWphs1VK57JILPOtSDpBd
UK5eUk8t2AocBlBDyZHdeOSSkqhHvAHSy/ggy0PD4zuXwaevmYxaBzXe2GuKZG5s
6ipwpbjvo+oQ2tn3sH3jc5GaXX/fxuVAu+VefZmaXtzZCc36pB94VpRlrVp4ofNq
XjgNuGiDwrU+hkIZrTPkPNC0zjp0lnR25LL3JlLgG4MMcm77eAXrnDTC6/h8zwOK
6DZSMbUnwtQjsQKLgs7RWt3cH7NYhUJTlbnD5H8DO0v4a8DPE0/d6lTmLJYqJv4P
Uzp/V1dA7Pa+SINfDsY9QFxpT8hxpVlqA0QBbD74Q96VK5D/Ig2OcQvJ2rmD87F6
tLF5WIVc28xW2jccILKijV8EI12jQpjpjyhfPoI/ThiG8eXFqIIfJzFdgK0M0/ev
X9t1KXNJCRq8NJSHarJ6k3FobTjonT4AqkCMXZCxwgJJsXbRzdfgDwi/Y+MLsjLS
PQE0fEYjHBTtedd5/qMkWoruwbgmMPY9P7Qy+nmEsNxXUZZyn4Z1i7NImqNINdWx
DNry30hj4UHENQes9bs=
=Suf1
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f07c6cae-f8f8-405c-a4c7-d4a62d1d7102',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//dmYv+9oGqyEQRK7cBABcYgR1NvWc+61sFk3qGIK0E1sq
E9i55rV83JOSh8FTILPEklKVA55ywchH954FZ0HSfQyPO1PIMVOKUMg+Gtby5aiA
bmFgAtx2Dsdhtb+luNGnIkGwamfdhLWfZriix6Iwqgs1fZiBkiEqFh+hRnPBnX4U
pcSPsZn3C6AXtwaKSRDppJ11PC50gzeacxFuvJ+W15k8hO6gT92H6g+COOX6Khzz
/kklq8UN4wmvk2zBfwRCFxdynjRm+p1uy0DEsPfqLGf7PKQCkPSWlyhB78Xwbd9i
FMlC+Bjq1Ii4Amhf5aXhM7AAB7AdgGyR+crQbX3knoBKEK/VUcH1FNymlgI3AU6O
MPQxBuvX1MahE3pHglZ0iTdbAnmyrHBtwfxXLPoPtRJM2yT8cjLExw+dTDdtwGLq
bozbeFt4ntM8cwtJQqkMLp+wnehB3AsGAOfV/NZGry/9vBLEcZfYebcS6V8pYwin
GD8+4RkiNYQE6+5HwakhKkaRebJh5s/QOdzWK7PGZCav8wD1Uu8Q81l2X8ZViwbZ
FzJq/soWcCmlLQy8MwzefznrYa9mVN2sYB2Cw76Ckfmt7QHj8aUquBVdIBW2sBnl
7DJ3qDBIlGqbPZ0CuoRYZpZ0XUW0Jzcl+UFaBYK84yzhTPIszIXnNAqL0QPXpZrS
PgETz0xSijnWvEq6kJ97BEgPnhH0PvXti57yT1Cl83THOlbYoibaZ8l6Q5x3vMUP
uJE7ho7MHU+lzPaBjXgL
=wJfw
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f0f7f7c6-7bc9-4601-acfc-e269f2e65cef',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAwKLNwWHjHPqEEHv64sv9NSj6CAZ1OeTT+23H43AVHuDc
5a6H8PuJ1QTjpXyZeY6mOppL6PPwRDw8L4/5DbT69PIlscZrDlYIwK8s01Lu2QDy
2/Qw2p3JmL4iWJ6CPKsY1juRRkPVCLNSeD7+80ot55lOgJEG2P+WSiChsoHv7jsu
vWLUlQaJHbHWtkGbanP+KOrutOF9koLJ6aB37/f4rohfLrON45tqgSoIaKvFE+u0
V/pll3s//OsMUNB7T8BhvVjvA7Mi9ow7pL1iM59hGojWlE6rKPBnZFjNh1I7xD4j
xSDSBNBmWVP+4BxW+kVUdu7i+KGZ4yqGcMU+ca3JuvpqzduTATa6gS0zaSyV3ovm
nS0E2vq4R4PJarhhv10pzs16bhqeTRy95LiSd7a0DBBaQmBSu84PrIztQ3MANQr9
4vgUYSJVQ7ozxK/W6xU6ASJJ9fEtNTnfJKmNBlHXEsWdZS2v1WU2O+PW5QCC1Yy5
x8KnAO2DAlanXAal71Of2uIHY+XK86PxKotrSX1wS9LRBQ682nlnasQQr7mcZilM
nZN9C/ctnD0V2VntJaIgEI4PdV8Hd9c3UTSxsnODY4BtibHmVIi3WA1x2mkg/zPb
IE9Gv7Whx9AbD49M8ysFfEYsdhagfkqJhMXNfLHs8uyMAKbxeMUi5jiKPrAm6ajS
PQFZNPUOszsSgebPrR/icPcJk2Kc1xS9YVrsdGc8PdGBYNBv6+svmqs0nBR1yV/H
j8gymTfbcSohygLp4ds=
=/qKx
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f2bd82c0-d6b5-4f75-ab4e-3373128d9cf9',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//ZhXfjggFnJ8cypKM5dGVr8EF33z3emlfU8Kl2AFXTZrv
0wbA0BOMzs0cfgMSFveCnv+pYz53imixGli80+xrvDdy6ze9veOdjEdEdjmPRqTm
N9B1u0LACMjQIhOFYljowAuKai6xksuoEeQE+Bn+sF/v3fQBWorPR73sKTgRjusK
MwvPY6XLkslvA+7MZeoWrOZj3EkS830bT4nh9sILwlXkp1aIP/BaH8IFoRhMGgod
lCpoYrsSqmPWkAPqsGSENcah69ppH+qYzORMXK62O1eKgVf/lR95JX425drMCOIZ
J5xsdAoVuSwzJaFPSv1+ajCmfFIxt1ko20WECUIP+cWUY3TsMPVM7YtTLFTj2rd4
3Up+09CFSC/6MGWGGnlUdulUg9KfedIv9piKBwOETYUmTOa1D9X8Vg010su07qQ3
XSiG4aKbWxaQ6eq87nH4OmcVZVMwAaRnU0PQSiQJvkQ0B01n0edknUBGByzMNDnJ
EmTbqMgCLuF5FSrdTBxAFRc2F5ihfLsuwqwql3qpIFDLAPEDZdOTBsXFnUoDbz4d
tQa1+WzFniTBE6k26iSWNnHGKdJHZ5/xThALCUoTZ9nSMVJTuOeL6DhMamhy8fUw
L/QVuGtHcdpzuAmv52prVn4qCoChQF1UOrTQSb7XpNhDfJSy91S0QmZVH5dWmhjS
QAGtLlx1HhqSxVUaSnxCQ9apjMx8F+X8viq41k4/A9CpgsYzguF7R9/k0DzsXdz9
Eoctwh8AnNlZMowN4RQzAv4=
=NjJM
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f32aaaeb-d3d2-49f8-a29a-2f77f76db9ed',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAnrxf9Yq+Ew8dTl6kJgMPzX+yXlthFm/iY7/cUG63tUEC
XLpsjRNnVdGd4xuqRKCafhzzOxSx74J+mzk2l/V05H5dIsfTitI6SWo6moc8mTd5
NCY9CEPw3Iekd9O2fHGDunmn3JwfpujyDT2rfAbU+eWLim8nqEutjMjL1RhTQQOd
Rwy2UXV0J2AZrmxuYQdrP6JbiNFLmp8Twz5IDvIamoF6MVZEGl7l7yh/JRRGl72L
iBRPyRCQHVs3xCXqtfslxk7ET38sKek+8FMmVMI8mg7rbOUijUnfa0TkuTMy9DYG
O6AmiuWnzrla37ijVIUpUOMy6VgsFdD3KHWpZ8MjR3u16HQYx85WbQ2vGyY9OYQH
p7B92r6ibv47+tcgZh2eGC/ZovOLhS4KSVA083mNPfWvcXMj01e0iEdFpo7URuA0
TGanmhMs+XD/4tAxGfbYpJUpEgRPK/rAyevitPouho3Iyz4lkadNQJ1MGvzujVhu
k7cM7L0nwOhgII+L4JUOZFt+2meTWVDZ44W8vdUMo8f6hp0+UGaNje5u6tGM80wL
m1JJVcw1W6wMHZvWLiQTNcO4PvxSuDRNfxTmZrXbIWHXM0sqoUTrFDyLKpYZFeEH
3WuwH4iNGYunZLhyQ3ZJ1+2u4wIDdnKDmEfjCpkvRCa+y6hgz5u8O/ZHov4toG/S
QwE/UZ1cm+MdqUZL/ZNu9+RgvtQd7tEEN2VyvRYykPFMxNcQBi1P1htdb+kgh/HE
8GrNcZpTxQ8DKhP3hCfF/nSNpzM=
=c+qQ
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f3c72fb7-dea8-4295-a2ed-5be0e9170585',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAkDD/ct46qTknI/PjrQBw/7BlMqfer+K0IoBometFUZbQ
gu6/NEvf+C1LnpwvyHcVlN7IDrScD3InL4qFZSBFwr8vbok9VgUNHgz7K3kS+48D
NlGkAF+02sNe8BtzrYtXdFBGiaU5bHzurMA4zCIMQexTrnMxcmxt9gRxurGeWSyP
ws1tMuTyQcqYLFCDeHQtw6JY+kennCdzmxxwjG8NYe0xB8xVmphU3XWpY83Cmm+9
WHqRWK9F+RUMARCfPmRWRVLlkVEiEWRqJjDKlb4pjqlWPUAPtoXad872gXyKqcAe
hbFt29mAkZ8O0Z9Com+VbHZ2JLFXx3uVqP+35O+IvaECvtkIud/AKX9zxeBu5Mdm
17f3dLYelR4TyjBUj5P/Yj5zZlQ1AdRJkWxLSX9FZwPeyd3sKCBqucdcszXf1XfQ
GZJuURJw+62+1YvEcqLM634/w1nb/Ek6KcAJg+BznjyJq1cM61c8USXIIwwWklfP
kyIrnQWmguEAy5cDD2Dx02HvQyF2wNRU/GJ8G4WI4vWtdXd0QbTIgqisCBO+WQZ+
OaWXj5yCjdtPqj8OiCJxYoLtpjvEA9mcT2C+IUW1BkZnPhwu58ALJosIGhmbWvZI
23/NEWHBrSGoifrmL7vEL4gtXNSJUxwJnEZgIACQGO8iz6GD7ktAc3WNFx+WNejS
QgH1lxfYZMx0nGKSVW6AjZ7PVPF6pN1MapKSLkfnu6nQ6iAmyEPdq43zz73XriHG
ZsRR3rcGavN/snUyAdfUaBAYYg==
=JdsR
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f533279e-9135-4c5f-abec-17dc797acee9',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAgMRd/udkCBXiSBDTsPNu9Q3+lhc0f+yoYqoDCPnyKkds
03Fw4vMKZJckd1EDg+GCYNdMbsa2V1an9CULFcEtUaZGRQa5A1/qKZ8Cfxb+cPZf
+AVZVebMiDnCRm94DiOABCO+dF2LCAj5gWrgQinXcUI8is29EV7rOxH071KdbgGT
0ZMtBQfy5w7xdeBVFV1U5rwk2FiBqzshqWmx/EEgLbPw/QPZ4D3PoKMZzmbhF3J2
s8tQSgm0L/qZEosseWiOPTyEUvJ5176P72hWZCJarfJxcC3DjTQDzs2X4crY3t+t
YCFjmjXjU0ZcVe9sZ9ddtaU34fVFnDtIABYG1VYEr5/5K/mHhsu3kcwJrtaNS4xO
RZiEA8msf5pam6Jqt0O0mR7j2ju99Zr+XLLpLk795Nok1lI3xXlUYc0pDbGEEfVt
R1KAJ3J9YDr4ykxTcTQ3h67zNQK3WAGQ0DHvk7DKYr3uAeH/AIfJ8uPJcKQUkYvo
w+nRLyyyXhB4IOsBuks98FKXUJ4aJBOD23wwMZ0/d9SScHJoknm9rq9cmsHxwEpO
Jb/5pj7OVDMKsbZ79cl0q+BT47Dz+N/pdm4R0dwOasoZNFzbNC67EuekZNIlpCyi
kZZiVt7CDehbAf+2b5Bua2aJtFnOjE8FSik2WhdcHkzxR68SznDtFasnWbgCjLHS
QQHgV5/QCsFg8gaYIlMsfep3tc6KL9cEXIA8KnpWqMhbYcAQQb/2AhwBHTltilWi
AS0RGERQJNOLY3rAe11SORus
=5dKN
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f5b14a8a-f6b6-4aa7-ae9e-2194052ae4bc',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAhyL3i5ZRLbkER855Wz4Yns7SbkAm7Hf2KZ52aKVrqqFT
Y3apd3M4b4ja5OVntUwVieTOw2fKg7U9SHJTFDOYGqi8mqh5+Yxdi4ntNKfzH/mz
giVoykcNp9VdbkJkPAkF8MqAtabGVsas4UjGHQsrRs2uvC803XLgllaPL1SLM2+h
VxsSc+xEQ0prGkFem0OyRM9Lbl8ANOzLMmyyhcSN9PIy9ahLGZZOWxNJriU+sjTC
TjvWcWimPRFEKnznNjkMRv/aaB1bWjP/WyThLsTiYuKEG2rghlpSas96X7L5fFhN
D58Cmo8tOp+r5lmzYnD7f1UZvx9CIP/5OR/Wep00SmNLbZua/jC6E6cncJ8UlWwe
CUaLHqL3WjgsKZImC8vC1qmp3lCPN0GK55rvrNQ9ypTMZ5vWZOU/L+lP//epbP/Z
wdq/5pVYRip1y/RwGZ5YHi7nJb5snUPLk31qcPM/V81AWflhH+S6GefNvtATqLbO
ngZYoXUgFIXEaUaoRQMamGCy7FiWYhweo/11NrxLph4BnReTyoMPscRJDlUy/lMU
+HoEtgI6VuvRL2DboLtWwB1coZgWlHjL4kMxf1Xf8lRp8sY7pa5CW8iwtqIIswMM
EnbL2OeDxKx8fUZWIJxqccVo/G51NBL5uN7TVdWNevWt1eVNmmSag0zUEKUeDAvS
QQHk8pfUWsLnpNPCHz8NEfs9iLwLD73madi2LARQZwvkt5+U9nAL4FBm+te9tghT
bYboAgrCCK/V7/PL1CW3qCa9
=qm9G
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f6638b47-bcd9-4aeb-acfe-f6665bdddd1d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//YjSturNWYA1en1BK94M1FnhdDNXecO3NtNzaOHE1nM6a
kK4qnw6v/wrzuf3X186+48nNbdzQcNCMl+TrANyZ9e2zulvhLhpikd9HVakbcvSQ
of4qlW52WMmtCDLNzWMzgNclcKye41LA4VRgIAY+cj85CUPNADIEdMeWNFYXFkhH
QKlFyi+8RWME8d6p99o4H3LDduH34vNRCuYYzJ58p5mGeZEwOCNbxstQ4F1v9/VS
ZSNNOd6AYOj28ckyqjnlLF09UOaS9MhSXF2TBAkosMEBP7eW4ZtcKtGlTzNk+klr
2e+/97rSru3iXh973+7/pzQwkrdH/z8vXyiolVpD3BY3sLS+BYdW7NMeVU9hw4DO
Tu1dEeuhVFPp+ojIYyeidAo7qZzee89ulw3eipgJtSFBxwuwqGzGd35N+ccFJOmK
4u0Ku5of1LMXDiHNfTORoAKJPyCqCnzrdai3COzJWGtJKpYMWiRX9jEAvkXfc0GL
A6rHodKzqNoBUP+axKYu3InyQyW/eQPb925nA8U0ccnzNjsSRhUfmeeB4bMnkpmG
zrej/IW0INeWvwhN5Ph/kCI3beHxHQTtOQUylBaZ2Xo0APLw4rAyFDlgGfvZWmOu
rBvKRQIuMSjZmGTRXR/5b6Plw4zF5+dEy90IRAFn/QLBIzeHLdEDd87bSrf60+XS
TQFxg1A+p3CYI4lfGdAhw5hMNSr5tFjCouWjKpB4nHy0kpmVHtGXB/wAiooDw6mq
Mw5gYz/s4JjSCUL0JCK0btj1tfqZd7D/Zay/rVgK
=cE9F
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f83678ad-fdb5-4021-a7a9-adb0b8e53c5f',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9GndKmh5URw5i0RLeqIKv9yN5lH0nAWnRZa7Wq58pFHtC
ezEdJ5d/5KwOoZ574jyYud/4yJ7QwUKzaZ071C0DU3imM9HQ7Fo01e88+/s24ncX
adDg2WN7bXEgbBRxA34tGqeuZemmiP9RUue8dgHcqlYj2E2MT2vqPUryu6DZ+Nu9
LSQxklf44fNfnM5UM9kIgUsU9rK7rlDohZWK6d/dpFJelywDi3A/hsdps3/U9ohY
/rt8fUFaj97QZPUbOUqcgia6IzyQiipHJ9by2/mHFNkMcIkNVJEB4NHEndVu58NY
BRZEn5/LVWmUmo5PzHJc7s9b5lSErPHHLQLjk1jceUOk/roI5C88JxGsKVcu2YFq
NIDDdS2ro1Q6I8IWyA8algfIBMIjO9XTN0TU3cS1FWrPR0nnzXS5NcQiqUec6DVC
/u88ylvZQjqcbtuCfuNnhI/4Ee2r4K6r/OwAlUzMXemIQ5sIIcQKMnjjhH457Wd8
XlwGCRdWpqOVwjOTWd7HA6IEW3JRS/+zvZjdL+qbvTP6Ei23Uf0yU7HbRniIJcSL
yFsVhwSaSBIo5AhNeVoPHXzEt3TKkZIpSdZC/3HDKfqLbI2nogvk84B2Lhj9ck3N
Kih63H6pGpV84dwTDtyKTJcNOM7tCH6yVpWh+JjUSee1ZGcj7/FRxgeL4KbHWunS
RAF8//VO4Co96rYWJtcSOLPqE3GKHaE5EdTQ9Pr2OvW1ha7oOjEg+pbRO0Rwc9xk
GIDry27FvHhmXRxnA8mHavRBloHc
=tInh
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ffdb4df6-408f-44f3-a8b9-952ccec87d0b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAnkBWcVKCVVhGE/kvYKtSfnCILUThfHxWy3j4ijV2ZhaI
YOTHDVI4sobDxDQCAdD+iugNnXmeiTefL6zgWBgtfu9llFCZStYIxNMCFqWQNo8g
iam+P4g8VOcCuq5pk0ZDDKxwTCJnGM0sIAOR+vzzc4vNuDP9PeJcn76bqzmtZNEy
GmwmUx92wOSBmAH/K6LTXh7MZ19N6I1NQpqxnNvMyRu3ffzHv1K7p8pqRGts2JDf
v+0TQ+QV/rbjTOzg2ANBG19ey24+tXtVXgbbGmHTst4KVY8cZQIH+G9NysJqeOwq
W7BLG9PdsJcilVvmEk5DseEwuaHDqqQtbhflis9mTiuUQE5f9YRntOM4t6KCxqsK
svNd7DOm1/dps9L24zKyHugR5gatGLP4cr+TiYksnsqYdqGGyIvrVRN49NIip4Cf
AL++x0BgHuar4jzXwXzWu6zVagngKF+xpa0idrtr6a6OV4S+ZtA4pGvaaD2wn36E
DppVXt2xHM05YIAbJBGcW+qZxBPymNQL0ppqymQawOh0kyDu+GREA7XobO18iRGR
yAN4G/vP5umu64QF/p7AscCl4I/Lg677Xp8V9+tepH2Ob2cWvJGTQ8JsTXSwwUsw
S1nE14nv9MpsVpuOJiZMQtF+JhvR9/u5Mmdx80pkbQrwAx3ekLRJdp+RbM7oXuHS
QQEyyQ/aLwHyKdLNNt54v7SbUbAU2JMO9PNWZyvqT/0dsiVL21218QERCPWRTWlV
X+TB/ejomzsesTUonvaEHd0+
=H2UU
-----END PGP MESSAGE-----
',
			'created' => '2017-01-27 10:58:33',
			'modified' => '2017-01-27 10:58:33',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
