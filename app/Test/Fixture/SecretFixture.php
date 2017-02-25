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
			'id' => '009f009a-4e83-448b-a96f-a0c7fff7b95d',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+J9P1b1zOIQmpTOB/H+dzaf6vPwLWJSPy00ckfCRitsWl
/iILDBpOZfA5Rt0nm5c8KcyN+ZEUT8OiTdr1Br3c68GJq/f5rip/VgkePa7cZvdX
pHE3oclFHdJmbhYxie4Zabr7gCgzJ+JZXRPYnmA8a4fj72RsdH2Szj3I7+cMDtq8
ECeo15atnv0H4g7ln9/9aZLoyZYXvCaD942SWfpmWFncYD7EEMG6GxSEWYTSE77J
Z6ZwrPb15nsr5IOHyr8WV/VD6D0u7s1suqWq8JuhQaoGuXbaO6hTp7dEXLaDbapr
/UzI/g0GhZHp94Bh7MFvOUjWPIC/CDvB9ldTKWDqC86DizLtqk1635X5gyrwiaVC
6kUuJtAFb2L/UQcKSFOoAaCKdWio24lJLZPVAfcEZKo++2a9VjuE/ff/UtE+buoY
pbRv9wenyhNK9LRTqhTPk/shKskuyZX+h2pWCxHXJ/6N8yPEv6FLG4kst3URhtDB
ah4UYiKQWzJS0G0ej4h38xhL2W6u4lOOoGunCun0mF+R1++CZZn8MtsBe0Afunn5
cUNDU5t78m6q0nnyvov0phbbUl65YyrXSthVCu977IaWjr4n6n74Mmz/gZerlMNf
0NKCwgMkESCn3fi648GqQrL4CINB9+ANX29KQt/00zGHxeiRhEx/pJcvBK1gtqPS
QQHWPZ9eTOVKV+VUhVsHSaE1lk9ZNlb0g4rAKlkDyej6ehlS6JB4MFmTB5Y70Ftq
GHrkz5rt/LItzRYaY8cCnE6D
=F4V2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0377d2d4-f66e-4c7f-ab9b-4e742f16abd1',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+ItAts3Z+B2hYZG42hZaLn8ZIZG+hm8JAjhmteJy89x/0
GODkmnReosJCqGzy0HMwzHuC+4hdkarBbBCiVJfNCoSC7VC2imDP1yOwhMhteY2e
DuKxyFhfgfyy5dEkIE/FbNSnmxOHn9Dp+DVgSgd/UCTy5o+1tvIy5wS0hn9iapHY
3LxF4JJWfmsas3ypr/r+2QOvn/rcNBj3VR6k0U01E/gz9nLi+fmHGWlCqZwkNt9j
hmxegStc3G1gtv2IDwj0Es1SF3EEsVsRGmaOXlOmbj1YOtrvcSjYN9Wo5FfedblD
aKmAbbVqiTPtl5oNURJZmwjcbzte2eIyWHlsaIh+wFpdh13xcwlyfo+XVx92ZBmD
L88IHTzrqxna5DBFiQJWqN2vbja2hyTUlrVlpCeqr0x5mv66sIybkDqdp0NyYoFL
8w7iedBKpQ5FpeGl4k+CS3wYM8D3/xagPL/0/JWG3lTC5IeITVzEn888y7aDpLLk
J+IL/fAAHqV8Or2JATzJ79ZQOUDvc7Ac149RawD42Kl2J8UBqnZKePpRwvCTLd81
/jyqx7CXJ1jHzU4OJFPqBS64O+DkItaHt5aaexL14qWQ6rbSbA0MFXAhKfJBn/HL
4HMLC+MdGoX4+CjcAPT4YIOBZj/dQLSE3r6OIcxafqGh2GMAIBkXnpTBp9KBQ1/S
QQFXvNomFWMq1g+w0z8UxGkKJ8bv02Z6TaY1L/3RIyReO0rJe6RwHyMCFuMZmyEt
fwdEWcNlGu4ss61Ci5l0nkPP
=1kZz
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0c34ad8e-1012-4069-ad29-12056ddbd2f1',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//a7d8xz9st4FrlJSb65Vl2/huvFZko+uxstEgdxDetc5b
TPT8WijpV3B5I4m3Y1gR+xMijRwbOTb+BExmhb7mbhWcWq3fpJUq1rAzu4GXfOAR
ISE17Hdt0Hg6YR9jiczqJ/B1va5mz6rHC6pCj375D63k36RCcgGyxU347wjf6kBi
X536hgCyMOFIqPvsKZdhwbMTvur+RjI2NyCizkkr4H+2l35IAPwr+usIjtyJbQzI
Y061i1zBkpXvZQatTAmRMTZEfqWjeIKFKlX/rZTsRnsOinRUSxPRm4yghV/WhP4y
vfCJCatAMUWs9Jy0jRS9lSIP8689dSsCuqQqNuodj3uxrWkWNi/keudPl9X00Wj/
T0Th5gKwSqh1aa4gbskq5gi7gAbyorvIkWprMQxeXPiqhWUsayiml4pgURXisiSO
FH48R2FJWk3lo0JGU+8UywzBFfog0nfrlrcBGkyRnP1Qi2+3ldZtPXIYscRQaGZz
ehSeisDophCT6L8156HacuRMQX9QVYtQHk8liG+MCiOLDOAIZXkaAwbEhwjXXQju
lrYSDsaeiueoejN8siu7AEh7fchwvuht7Z1kUk0yYRHA54/GHxk8o+jbaNjytaY3
HKAG3ko3hfkx7mE8Nuzc4BeHf6yDBZo7MVX5q5EL3aYrbJT/FkJGQd5Lmx6NYv/S
QQH0WzmnNKKtBe5g3gn+MfZdxnu88hoOMQg1giULeM4M/KXg3zWqhJjWxkY8EOOx
usoyNrKFpEXD+otTMJR7ENPT
=QTfW
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0df84f07-f336-4d3c-ac8e-0d847fbd8c65',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/7Bbwvc8OztOgsvleOYRBhhGRqZL8Hdh4S15xVd4OPsUcH
9yonzNrUVXWXL7nic9lPumccCsPz8Nn+eK1Ortm5fvdetQnpUtMzU3v6xSdCqhmL
/zQpLASmciQ22p0gjC1an38eLe3XhiqV6PYb04hvp0/jFE8sAgXkZof3z7//YJZo
SeS1Bvu+di+I3Zox5sXcINV5s/ebycdQHisZKoTsY2owhIInvXepgBoOxqMRyz+q
Ps/MEVcv8cj5rH0w0QgT7bRU6J4iWKDL1L7BvK4C3JChX8LFKaeK6N3i0WViQyZ8
lYxyWYBrpggNm6rcE0dzRvZIAK1pRx1QXcQVdzGSHmojmjrhXBRSVcuvznhzeUVj
77mwq8C7Sr/aOxHWritEQ6788YG4T2ExI4x1/Job8CZHdpIQ14knVTfB8txOgzdp
dJIbX0f41fvqG1RG4xpAlnktSEH6dbgvC/ExiTD+ycqlxv+/y5/cgBKdrWNBx2C0
6MpH2e2Eo0CK79VhTu87qNC7j6AxsS2Slybz2/ebB+v9Un6o5poRkqcepGzj+ZPC
WqC+CRNr91BkGmHSQACOzQakquN1wMjMrMVNHh5LU1ICPqnU9btxHbIX5lDhiT22
TxnXL3cQUJs7a0F/EoDv8ARnNRY/G4WaA5Q1qWjz6wv4q6obmE38d8l/w1be/LHS
QAGS3jjaLaEWmFTNCxFz5HuLleJYGo1NbqM/9l7u8fGh+XSS4u7xnY+OsGdRbdi+
hvnFpIJTHNhp16/nX1DO7JA=
=+QUU
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1093d76d-2bbd-40f2-a200-2407ef11f7ae',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+Iuc6GGXf67ncl0Y/ZfrSBVpvPp4Nz0pASRJ7EK1shTGS
a1sZIT5MvuY7nDhvFnsn76FJIlqur1lZO8yv84C0RJdmjxUlOZYUdnHEP9F8K3pR
gLW/MvMZ/SMspSVhtzhIBSA4AjAX+sQL9v+U/mUAr4YNdNmWJuAJt0DfiHKpJVvu
W3/Y2OhbJO01aIBIgEvhCj6ubZ805r5KCNyRlJU9pg6ykAvo+fiu7YsjD8lyDgsV
kyBb6svGQQe1jC1CBVJtlhK9LTgWTU01ETxEJkjxhO09A19hIvGCp1+fIBK9UUVY
UzV24XSfy11fZm8UrlIsX3I+YxXERpOHOnMeddqAm/pe2kU2RxCYFiKFB1HDqoq2
29tdgNcWr/Max/uv3qE4BNVmtoyCyOM6csAMOGa/jdEmksAvToJwwjlENW0tebjf
6UpluqLh6vPXQe7GuyISpho2KiDuf+AHU3S8FnNQVgxDF9RuK2lsrtigHj0OyP/o
l55gRkr/KvYr7d+zv4PFWD8B1xmK69okvqJomSiBxiQu+qMGozMWStOdilHbTFQT
miIivhHK13CuY5LKfdxfB6x13nnRVlNS1FcNtaF5GkOEw/TFB3fED5avq7jl/Uv4
0rID4yE7hhgkQuF8M012KbYba0UCtKqeae/nJYIIqWTvWggcqRB5K0E80PTMA4TS
PgFaSdLvLLHOWJWBNCSti9h9lqB98fK26SeT6YVOtt4bKVBhTmOl9F/5s51JKNRB
afuPrjWUj4X5xYvrI0mp
=t9Zp
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '12ade50b-ce21-4fdf-a0db-a78ae96adaad',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAuftTcGN1Irxb0JfjECMfGBUcJdLLSeP/jz9RvRVwbpBk
B4sa6QPPfWXHYelKjqFroXw9IWw9xsUfW9Q2rLt1zNeyGpXL2YTQPsQFuWWDiU3J
0WPQe5yVpluSmBz8ZLjivF8m/1NGUhQL1vQrwyfO7xJpbZvJqBH0qZx4XQtAtOmA
9UYpjITttvG1Ydag0GswB2cGNMoBC2gkPNMdVzQY5o85KAK4IfErb/PSqPZr1jKf
TyDm6XVrAFE2za/vZLdX2DV4+vvlXBq/GTZjTFBq8ljGKnJGmyAiGa3pF4qvbN9P
J+ywB3Nmx46ecu3fYVOjH/E6igZb1TlEsFuh76dQLInoeOvfPYO9EGzxb1cA7UOi
TkMFSxYaIccpHtre1yxGESSV7f6Laz2gmnidpD1Hl48/CTHzxI9KUEknfTRDRzUX
SZHPtOW7AYC3PehNS04JgZyzylBUGJg8qNVATvLZnHRrQfeQDX/CaWiNT61lfpBJ
v/QiIHRozMelebLsy/tQFNre8MNLSm9J6NlF6oo84SZN81GzgJIfrF1su3O7gyUG
ePT8W9ZTobXbkGZ17EULHEJMuDFeasKbVw5l2J5TTptEWgzIgXs1vh5I28t+24MO
np/6htuNvfKcUwSstCVnhvH+joFkU5YfQxPtDYGdmtUczkZYQxJtWdV11NaTIxjS
PgFfeppInl/VegAYzBgo3Vc3sVWGSoXarU8F/1mPaETNdMk7IPnYeZo0187waGJl
QDy4Dle4Npm+oB+B5WMJ
=5t7r
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:19',
			'modified' => '2017-02-17 13:07:19',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1484d16b-1164-49c6-a3ba-c01700bef493',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAkgwqhKSvI0cvgtGm4lQoiexvzZjDCLZEkL3d4GQyFtaN
FZBDN4CgVsi3wlGlXUbfdBtE0FBTEU86jZoAjfoceMGyztUJBtizd3IMel+NhFSi
x3aiAp4nJz2BawpZYSOzUY4vsk0A/V7QbcZowhZI6exmLXdharXp2HQ4Hd3g4qfb
9OjNNsQXP0F1s3FKl5kv40YCDZzKhAUXGEAGB+0J+1dwnFGulBLuTrdNxjDj0u5e
MXKvI4QxfPBL2Ajju5bEHGSM4b7vp9DsWPicyg/bab6jZL0fpT2AgEcRVRB3EKRe
hw0Jd2LwMFcRnAn1C2dotEGiFQWQFuFYr4mbWEbcRcjTcCI42jNX8sVUNGylMnjR
gFNIEB2FUN+l2S8PvwsKXtd6IfKHoHbByGNx+3jzwjL1asigu4/F0k6giCJF6Dlc
j8bxGqaxWdibb+bLdElJzrBBp2dr/anKz8s6k/AqljEnf45t6XC1twy/bicOzPYj
TH5cP4/nfG9nB4H65Qq3mxbQLKVLbeTeysdHozs47ht2d3JO3TqGnoyVSXiTIe0R
sw0xbrYdyE9DqkHiFrjC59s7wxELPa1T4/q16rx8aBrtxDR1ytl90wj5VbC8DUVR
NF9eWJXAdVBoIr03qxbN15uCyMoLhvDSlbt3HdI31OYdVtaPLPeqRTqaJSqgMWbS
QwEQe4JRb1ZiTKLAeSSAFUHDXKYdwdPjfgvXFnpqu6WwT2M9cCttu0TH7krLUSnI
Xquzv4GbGPNs40HkuB6AhBK13xc=
=J+KA
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '14f9c372-9fda-42b5-a593-707d5dac8c10',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAmhWHDKZRQXb1VoZTxBtuf28HyfZpgskc1rLakXT0++ma
2OJzY8Qwe8N5mDXTfuVuHwLe0f9p2W0Ym1+f0pRblVtldfHnYVHmmuspmO+ZRuK3
SnmFEM2IYGyv6Ldlj+WtXZEF/3GpJ0NXOH8RghoSxviuRHnu0EOMGYRZmjfDeFiP
hwBde45tvdr0YAtEUNdaVPUtNMDaqMBlvRZJL0Y17JEbOdcIXJuCUmQ3Pwg/2WMA
X9JIPOgy0xF5rL1c5BNiFQAQggAFD/W9zrwbsJPEq5p528nDrldKKvk+bja2wHNC
msDTAye3/YNk3/piW8ZwXj/MHzufG2jbHMKz2xCOYvEgPwmln+Zzz9zbGscMA4Z2
aFToMAmh2etecsGwE5ARSmJ9hkyxPBqv1WA8Cgk7ERRlOnbON1McrQXKuxX+3zQn
zwyXUdwyqfpt/Vk+WXbTImQVNimQCFRGZBj74RgZr67ERoQ3pYMjIngshITzZqNJ
nNGULhkrdA5PlY3a7ljg+rZjWysuCynBl+vABTLqqJxzbDi/6DAxgZ0rmitxsxmk
UUwdGWDkjXLcSX/y015JBEVvZ9h471VEIji+idJ8Rhsg2XmDtL/5iwEB+PkIfybI
QUdNMeBfTdOih98OGDxU9N0xjSegQyKrMvOhQB5svcsBPy80EtT7ScBsJZ/XX7XS
RAFdnhBNZraIxRZR5bHpnmQlT7j0KcQBKnUa7W1kDG2tbvzYGLNbX53a9dnZdbLi
y/T7mJ7wrHEqEz9pOX2aOgqE1lnC
=3t61
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15e56461-e16b-48c5-a389-fb3666056d15',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAppVb0Ru+pZcompp0pF1OBchFcair3qF+5heiexI4EjRL
fKr/9CG+/O+WCt47RBid5vAwSmyxLY+PbiESNJdnCdyzEBQPX+32qQp1iVqVHp8D
opDQF3qr4OlLBhLoQIGWHT9P0ADZle810hBLNw6BwIz0RvQUqiDSPlrH1wKRlmMJ
lJ7lzuDmxC5147ZpDJpvP41dKogoDPCw8C5ZoFg1RGKkubMv2MINun0bdGjfTYuc
3fJ6aD4UjNLPOV5GXCGHfjQuvkLRnTxT6rH9LOEBuEco0LpTH2yh/R0vfFTsCjlS
/Q1YF37xp/dlHMEYk9IkoQcbswaLvS2fSyRFpdnfxbPXo1RSnEfu+z+jz1SBRnaQ
3ozVn/skI7B7TqJxNkOrUIijUFmvXuAHgdySywFWdzjH467ladA1kW6MGQHNg9+c
FU9drAlFHvicrtDGLj8CpoIFPrIw9X9OWqJyq2xCT8h8MujtP1VkYmijrtXNGXA4
wV+0AWpBaSJX/agidA3L3PFGwcRymw7RsSGDii0rDcidrMMDZzKhcVAZEEDh4dau
4JgsXxxSbzhpBjlbAA/RLxkg5EkfHU8Dge8SZLljbnlJh7VI2txq8iMNEJFdBo/9
ixx4xv0w0te/sbLl2yPdTzNNfNprIuiA56BPLx3qiU6z3VMvdzDax8sTrOW3EpTS
QwEGSRsVONedr0CoXk1plPXBMg7nUXS9Nn5UYs3gcRrDyJ0S3axP6htmS3PCnZ9P
WymhMgjlipcHRsGQun5hFxH53ts=
=Whdj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1b9e4bb3-85f2-443e-a8f6-612992f3caee',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/7B+cUY+3AZEDdYq+ZYlYG3ZeVAawIxymwUCUd+gLDP84Q
CsYVud1VT7S8GkWK8X3Dw50rSRdUIbnnsTGb+GJyqm3hldI8u9ewQ+cfS10gVYLI
q3jqtlzqatmDkzUcnVHhMRyvoMmy1pK1HTTApW/ZviiVGSwqZz28Gj4Fx4ZC6sAp
rTCZU0d0qew8CSyJ9vum9QuN3q8h3LxMvZPQqqdsbOiDIFtFtnyA97VIit2WhMYG
I0ur71J8+6lBZnWGNePu8j+YPjeAjaxoyh1ohAUHlxYJ6Mo073BcX0k3ML30ghub
MPr+zwwWUrYSbIODHigxhI0I3Fjlsgy7vxng/71yq+cfhaC/7mhgJrkQjrvi31G9
DNTPh45wlTMOWJrBPfbPyPhOylP550FbQuyZoOo00Oqe29Xv4l5xM2/858DciFbE
y3NUJybn95l68FpkJibRVn+VULUjWOMlfzARzEQcJ4ovUXI6BHtUnijEZ7/SWQMh
cmdQBzy5uzIcQfLGQ7JhJKJjOxqF871Am8z0zjWwHspek+UbLdF7Vd45biyHfY+J
gYRSAKaJRP80TWVFoYEWpss3FGlkNru0QL6Z9Oa3Mq8n0Jh4zfvUAf61icy5lblW
dWfEIZkmQEuYmQnkXvvAS+gvqxIVJGYrZpkyRFonHyKHbhCH2Zdq7gVKVSWMKYHS
QAErbKCrGaYGAl6J+KbE6klsFXckOwvT+3+77GFr7SrPtCbAF6/S8Bm9w1nsyyCa
RVT1BSD4GMpSBAhqKELYLsA=
=0fhV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1c774119-a92a-48c4-a6c2-cd22908ee22a',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAlGWUW2DEyHoczHDyBZEa30R7K4XDyKou1+FpSSFbSs23
10U8CRCarWz6ZsbnMauHVGjgKudf6zInYejDoJ71tfRUn8AS67hVFhWcHbkQF626
B+ykwXZeGdZxC0PFg+t0wIE0aiXJ21AmVDljZwU9KTYVgOIjdyemm4QuyVZQkdO1
zPsMEPlm2S8ffNAtCvHS5wsiaBsPFBXAw2h8/hNT6GOnTKIjLAY2UMnROEDd9C47
aJR3zM9OKovP2RKxdv2T+YwYoxZSGQAszfN1kDHgeeqzt8RWxxQQSXxuCwG38DzF
1XJPV+KGKcnkScE+4OP4qUzh7Si2OaFiyN8S8GJnmcG45MYkSanZOi8z8rXD8NT+
g9AvOPv6D4CKEADV4RIiv9yZBiUGvsrJ4XouBqZmohwTh5bVtEFOxDHhGyn8yAaP
bpWyJt+va63R+mOohajIU3mupLcIpY9Q8rm9v4oR+guz8QydUneeS1adV2N5V2Lz
3+Vt8fhiXk7cmM/bhb0szn40+u7dbbvRJTONPZPr6yymea9mwlZB4B5HyT8yekGA
0Ksb41FiZTnV1m/piaBCx7pHHweNLkHaJt+x9xY4V0eCpYgel/84/xBZfZ9oVpIX
NeZvEUs2DnbrX/8EUTs/VR8P2vtQahXzVUeqAkf36VnFZbEypRE9M7BJ9sl9sSvS
PwHNOP74DVhPayvs8wMhJqklEEeFnEAAWE4B9Wdi7wEIZ3rzYnrVKuNHCxBUfzL3
6nrqkJwzdKOrYpBPze5fQw==
=5d6v
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:19',
			'modified' => '2017-02-17 13:07:19',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '212f22d1-6f5e-4b95-ac28-57eba764cd7e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Oua7HhQiYG+hFFSxHnsBLsxjySwh+hbInPBV4H0aUqDS
EN4xz5fUJEdFGBBXpm/O80dL6sdnphmfDT6EQ6HP8ymnD38GveyoR5pScgCGU0j/
XbQuLrk2V6cUebONwbAyYX6emzCaSYjMY8GISs4nRJGU+4Ru48AWNpSBncOY30Li
pNIKgl4aoGAb88OaBLWtGJ8LFsT99VoMejE5Xj8FH50FGMx0Euy186THA41CgbvA
DpKeOziQF6nZ47rvNVDZalTPGbF8f65It0RjYLX726AB2+fonxg3INnlhwHffHgH
/tXowGng1nQ2T1ivR+gOuAAKiWG1jJJHKWwrg2nqJ0vX1FGUxw5Xu34Imi8uyXLn
qit+/0HtExatgi1NJ/rpwfdnleh7fEpOm3WSxpGeoFU5GaKWr4akBSwnBu53uVSZ
uLVpgTUUPKPArnARKPbgx80WH/gIRWpsI7r27deR77+TlaqSiNJI4/R66S9bYvpR
sQO8+ZUapKvQGRZUoaHNFDGQogA7JfCY4QxTw6TtKXpsAA5+zavefsb2X+XNM7Cq
bmg9ovPSywiuafnd2kBMtN8bLs9cA6Uy52v+sLcKrNwovZoDFkmEo6WY7FeBl105
XYs3+GmvpBs4YD4cAgxDeo+3mnfo9hs7ouBbWCvr82CQjntRZfVA+XtKJXUpb4zS
QQEdJjCvVXwB9XTJq5ZO4/rKd+uJ5ueM9KkJbh8NXp+tRF0rdvTWafgJQ/ibS2k3
ppWrZOGck6dHSRYfc9ANmipo
=eRcp
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '240cf0e9-fde1-499f-ab7b-6dbafd9ed143',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAkRzB0BvWfhrnJMOHoBL1m1EqhHzMBoQFifbNRQZq4C7r
6H8vSO48EfRkKdRhCY0WDzA+7bv72/QaZ5EyOq91CV5L9fbOL9uH3CVdZoUeHyLy
jLF+f1poYVl8Cm6z/PczeadMYiI3ll/cI+ScZ+EdfSo8CtsarYalNvz5iJT+pIap
gdJgeuz2OXZMA5sCwuq1C9y6+m5158KUnniv8nYjTspPP5IIAK2A+64cOa1GvSTH
1hbmeYNCIXT2zKoIPdBG6DpA7jZ2hsMYZkuY6/7orEq+704pJbqrueumgL/EkLNy
XA4Wfg79TrQXDnu+bk76g+HK0wjaTA2GWnRl5UysDP3GHmDcs8cXEGa8qRBp0icH
RuW47O2kq5FlqXc0O7qwBHG9NvHb+wkVoHgrZme6KRcO+BXBpahmCPXYnHWnXY5b
DR0rGtoO5Mjt6SgOGXCGoSVlXPTfF0JS3zmBOm0FCin64dOrsoVNcrYP/NaC1fRt
mrgHhcik22BoorfXPUrQgulW26d4c9EeFfhhqVl2IT/YTxfTFvBHWmjXhAgN+SpK
gs4QacO4piBSeaHXpWLlyBtDKTI6olD/u3vCny3PFXXtRBPsa98BjOaeDI7zfMP8
qGFozqku60CLI86WdXCEwlC1+204tC+uuKzwkyqAxPcYr402++NEnF9tGe4VG7rS
QwFSKQ1POoMTq0z2YdlYVKiKpzjUXF3MLoC9ANSxkr82cIVoAwC1+Wtovot+74dd
CBo63XdY+D/P34czjyuyz7n9A04=
=4ISs
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '243cbece-2af1-4ace-a8e7-8de629202b0c',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+JgcjVAheHYnyDpVJ4D06Rj8izeHFW6N+Nqujt57dadMj
0mSJOVQypowz24LZBXEAnJvwZifTaBKVITCAWJ25MotJNKKzQUSJkQz5Qs/nzOVZ
gfXbrDKouTpoIj+YL847iRK4e3HQdpFr3zK8LuvL0sTSQ/uoEd2ncEcLj4Y/g19j
yIi5NFvKpRyFNNdwhRmiXLUkAYTR0KWaL91For7t4uA+d6jjHoqinwPydwVYu9pl
LdPNxjHdYKB1BKvXXnVDTbmvYMe/7NI5v0z/eZ6uO6Zuw5WQJ7treQdJx8L36obi
IkQMG2KUFJ5PVfeXxncC/azfIiJf3UkWkaAUeR7P5axSFo69EtBVgX+UM2EFTRyw
EiMqS8rslut9u5nLzpSuSpF37QvUzWqBGnI62Fl/AzKoRCH6ehoIqNc08FiGAMU9
xiWXb1D0PCRPxXdIT7H/hNWhrmsy4G1pnWYDbx5M0qpRc8qAbnDbObUPPhEETf4l
EqVjhJlxmVomtjmQW2VocFpa1jZEJOqzyRTr3gO+szEXbO5QMWH3bPjye0CBWpbn
2H96GO6hBmaVivDZ6QQpL3jOcA+qiF9QdTz/OhD8n/lL2sZqEgeuiMqk5WpyvxW+
Q6lY57UKvtjWTmYQL46xSLxoGz5f8DbB3vaXTG9jiSnEX4Xq8bM4RKMuK4bLjFjS
SQGNENo4W4nJtaynj+xTHUpyTNQRkxYGLyigjL+xtaLeHcrDFQwJwTgSGpGY1FpM
22NBtidzy1chjTiptWwnpSkKfum5QwVa/64=
=NJi6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2990cc66-7add-4282-a2a8-cca46e270763',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/7BfvLaQqoJYQ/T45c3ZIy0IyfssooZI20Wc8GwZDbk072
Y5AW6pbrni148iPbZGHtFTvA4susAq64jPBVlBa5CroXPZyrFush7MZNIkzbFjwv
N5DjhQes3JAzCMQfONYB55hWLBG+jhmT038CnouxtrfrzUOkASf6IAS0TStscYU/
+pH5WRp1RmNAt3WRHPPsJe5TJALdyADZBNsgzy2vP5QnfiYsGpD/GR9X9kZQGsha
QeLx8XHIg/xCSu9eQZTH56nWMNbIkIzqgn+c0gjECvlIGQoDgyqE6vecyBTKRPlA
36K5gH9l84ndDef7ij2MSPxHznhVvhvh997Z/sqJzwCeCf6AvSzgTBnrL/4erESM
7lGe8s1GTrMKLVOVS215FOLuXTdw6bGqQhGwPJPO9t8e+zY/XtdaUm2txhTNTczW
yaykWa2CuqynpmBq2JnTcnV4Diq7z84KYJ06qYAIJcn1YtluQbCd4/6z+sEuat5w
J6xW6KMdbOX9WSkit1ULjm+F14lperdeMc3ve94t3K7vO9QfXN9QzeGhFZ5v+UFY
rRvGDZr9D7AmGwi4XPddytjR1QIlLriyYKbOsXoqD3uDJ5e+zyPKD/WUWi3FVka8
8WguawzS6SvxN/6BFaUcKx52uievN2vbqV5n2ATfLSCUBw5n23sPu8nlvY965BfS
QwGDhKhoZds7tQKTfeWJcZa00iXUaY4iAvc/ZXO5S0ZWErMk3nyJFdlWFpGOBNDT
f4M/5dPC7uopeYU1+YUqivMfzW0=
=ZS9N
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '29ab4d40-4d4e-4503-a51a-a153b1b04540',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAsfzFZVZ/zs4vSUZ7+ooJf7X5F65dPo9Si0OH0x6UcV+o
Lyias8imyK7PqIpsZHctPiS2oXDJTazdikBiR7K9PRlEqH7wRCPphf77vS6PIC+9
Z0jGV0Os9ZcJsZ0+gzEfRrj8gR2cbcPqyrd9YJirmVFyo5Y5BQtuT2vPWYzNiblH
ffcmNE8oaDYV6skxZPChUMqDKbtnR3TI5cg/0TE3UxSXgWXYG+eMTtaF90G1HQ4g
HRvSP5/wkZa80UxqqLP4Vn39eeQxgvkSP7G073xonMF0gcE4Yk6cJqYkKilEyuP3
9+6fYZ0MnjKmjLIAN8cQmekknZQB1uA7E9OTtxInLjPPPJ7ce0xgEnOqyqCfnj4S
uwGhZCjSfgHkX2+loU76gfC2LNJ07PzEcZ7Pr4z8TwHZnrEDwaG9h0rripUep+4E
nqyERPh6WWy6B+qbuNSdErNEGHrAZgIv5SYO/KM0t9emuvYR1+VCOCKZoUrh8l2a
kCgCVZ2zxsZU8w+0B56j7jx7oChoMiBjcWsfyM/ln4fSalt7Pn++maJGFZ2EcP22
t3cQPI+1pvP0leG/3iFRe+eo3Lk8gCCMEikJTuUS0bWQgVz370wOFANE481mkClk
vlHVmfWJ3Nl4hVLYv/FFxoqj0CzLv/wut8CEnq73ceezm57T96nd5kH4Seq253TS
QwHYygLEueNdkyDeFnVHzzJ1kFS8BSmbRVZebD4YHJTyXq7s/Je7Asyxd2hKmyRW
pyfNYuM2B1Gl3x+eP6m8lqNfmOw=
=kZ/P
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2c9102e2-027b-466b-a845-5d15b0e5366f',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/8DJKPXiWXuUibYATx9kUg/LYuwX8nDpC4/4ccHoX5fiK6
BRe6g98SkMY/M9AWhuYBGkTdqXRZ09XWWgUG4afIq0IvTtPR4mNJAx1euR+plb8c
po02VaFsiiLNBKlh1wSVhjaFmu8AaQTE/LdhhFsjeaVePBWeThWF9U6JfuX2g/QC
ssjMrrc0CnIbZIBRu3PUJMu9gUYmjodGZ/ZiwVjxqGp/LHxikSukZa+kFv9KnBMv
eg1iahohIRiXxq8za6os67rRF+OANwiENqnfQCYrZYC/5Ul+tUlr3W7jqBb3p+rA
zABtSjrVuVTtAMP6mqW5PxYCoK+5x+aEB5ZVhbhXKuaeUkvQ6bASpJ7O07/R2Jb6
fyfRQcmrHzdAwu7tT/74zsq5/7u5hIoiN3NtJzM+7Jex5gAG4JazRmbO1HeyBYV+
56MjeObBatGnYHTESB3zZNsPubTza6d67D77GcUaY4pSdkqKlkDdiMqq21Ch3UsS
bT2sMAgbpvsIdC+SX80e0TlAkhVyGgg57xoCOobq+IgpxEjvLqaqNR5sVsb9BCHK
f8XKNDkI5RtLGIQf04RLpkgbiCniCqSKhpxFbeanaoNG2YlZuhZLxgF6b54KzmM2
T1QldSaH3eIrfjFTwnk9ZiTaY/pHm3rn7KjF6OS6ZgmQXnbyfEdvuegtMBPqL0PS
QQH+gCW8KU70QTkRFvKNb04s2iIkG6heWYWIBSxbZkScC1hk50eLHhHf33VcXaUF
9LkpedSOjw6teNUHf9Jm8dJo
=i6T2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '31b4cfee-fd03-4b5e-aad4-8026ee91f12a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQILAw0P12ReHhxtAQ/3dx0ulLqeNsQmYYCU9OjG7nLtrj00LEacaokvVBNLw7Ej
aZ4mbsCo87PWs2NidmxM2bA9DlIbdTMrIurSCAmXQMuVru1VHRkwjf8VFenMqo1U
eZEDn1eOwuuM13dAkkbZeq7rw61EQ8Aiut+IgpWB7ukVquctlsostBAY5eY7I5q5
jYmFepzFPEGMx71sQTcyeb9rUrJEVsCGGQLohefvRlGr8Z+vYgaFiQXJKZnHsHba
mX/UJzv+Jpdc5TkgWKHwAMlxt02F31X3elM7itmujZhovNmWfEWwAj3MWImorC8P
Py9GF8+XNKh4cgvrFSAe5IF0hEqnh+twE/LdT+eCZDya6kFPgryjHMLsm3naozpf
hf/YRwzVnXfoLTodg8s4R2uoNl7VaDAfz5+zux6noceqpm9e/83LVSWJQv7IaUzU
ASgEu5OU46UlEymdvFgd1pBfw4Ed2Y2OrG7LZgMQta+17rWEwUcDjkxg3U1TnkvY
woxKllvrrYoiLvCSkTks6UV4Xym7JhUGgxsEbWdWxSa+suBtFRI+JrJz7DWIhLr4
wno6l/BN1+1G4zXmt5UAXt3aQXP+wOSdzcQj9QSskJWySLfQAzJxBXvt5Fg5pIcl
VmY1RUPgYlbOpTHbmPn3jeBCHFr+Wc/YMIr1jsDTQj3GvvM4WaQ2TBsNVRR/9dJA
AUDKDbqlUCcVv9XQDO1RMPBIgfHp2vAp6nKD0j8/FZqJ6yaL2RoCqvzSk1nUVA/Y
XowPrlwfTbp2lk6gDDqkQw==
=Vqgd
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3490a899-32ef-4616-a258-49de7b8b14d1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAgNt65XoJ3XlYS/SVmzefDHsa1YmaS0FPiJ4WzEjv/81v
1RrWQiPWA7bs6L2p0SmkeS/4HIDBkrNPg6rtp5V1gy0IpMNr3asg3aG5BjmRiAbH
YtpZOV0+xAY7pxGu92aEHuQgb4XyQWqb6/vwmOd63ttzj2sj4xLInUFr6dyIq9P5
LXJBpUMj6ztVuSu0J2zKXqZBBXKty/5PEwlX+NP0m6y/rA4RtVd5OzWFTLni2DpD
KCL0pgeLSjeYdGnXAdERmdSp64QfwINW0NeHLe34jIz46L0i+r7nhzaCOb5S4R4s
b4JQEuaUhsTDYoNHTJkruEyfrq0+mU946ioQM33B7EgbBuLtxn0XplWaEPw1FR26
c7qDotIdGfqn53dvoHGkKlie9ZuCLIp82iH4oHTcdHzbitlgXdCOKO+6nisdqpLU
V0Ll+x2IGXuwfg00qav6vWErhQS2nOePWS5YpMyHJFt4414jLT/gPGYThfZGBt47
gOWfe3waf0S392kXTCGW70fIrLvwslHkgoSNrbsdKFpaIHJhpf1B1D9sNnwIAfL7
4ttKvh7LDcC/EpUDOjI7stmpzKyeMufk07JW8I7v62wrnajPUQgHYmuuq3/0L/UA
nyNxuKDI7yrkV1oulvqfv3Yt5tYADq4rweiVbJDYz6lfWPf0fikG+T5pMKY+e2jS
QAHAYSDJ8dv/LZ4KZ/Ans8sGRMbn63c6SWofQKIPPqXRwXF8YZb0xYcOZsM61y//
hRiXf+MLYtuAEbr/guzRUHo=
=mRoB
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '373a32ac-f541-4168-af0a-6d191e58f294',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAuNO5XzxAt8pfVX7sG72UlGbx4Ebw8Tn8q+nUg6j3lsRC
lXmuUSMf8/PyYazLxn0v+Z9Jx09RTuIz1NgNZ1H/qVsftFtQBGnJXW1nZcFlu7EZ
dbptkLzM6c7fhi3OAW7Q0vFJTSaPHiH1qzJ0pTiPdRbS6nwGYmmWLKt2NmjV27qK
Ruwwyq2KyxXoZpXlzvrrXkHUABwjf3iEv5hVWAgQwTqw7F40yTtuzrrXcc2QcD7/
bKocpjChw/+/KCd83RBa4qqf1C8N2+M/2HjBaRiwo4I5hL5tMkb/jKbLLE9Vi5cp
JPxioqrZmYsXm9eO1oQoGSSWIrMduOlMfJYXoZQMkdegv+Oo/QMrf+4gW2UljIPr
ZESkYgKkxzg0rR0iU68gSWX08qTLvjncOfQP7VOklnWH2w37DIOWdiyd5pKQF6hP
nXy9GR0BLKSr7wAMgJlGJTblS1ugxGauyzwpKNB6fkyHbDZkTa4mslZ7kOMIPnGk
8vQpP5wyieitfotmuGsD7WrSRcZZYLYOzmAzxX5ZT1qCL5+2VsA2WdvDbOtjro1H
eDj2a197Pk/mRza6XpiEoScE+fwlGxeLeXEt56f07gnmCOOD7XOMIGv407mC6ZWq
69kifGqX/wfnw0NDm9z6kWmM9FPH9wg05kdtKrh9StbJ8UInVo2zbk1gEf5TzVDS
QQEJW7HCK5G0WvhN7OH7vwPiugs1GuZBQbCc5XSVljFORJJwXVdeoFC9VbtXibu9
KfeLSnWFA4jUS9k65j6fkU18
=4isY
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '383220b1-dd21-477a-a489-41a9ab968b61',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9FnBMEPbySvak4DYzjHxOM9qBrwE61vPQHacrRXHggENi
a8aUdnHR8oXV6cAVbu784JcmQSkdW1xghx0W0JFngqvVw26mGj71oUO9c5RVofE2
PeRRZd2QXOyNwkRRre/fv39zCq6PNeJZwI0060pw7trul4dgt1EdiMR3y6F8u+Zc
GFbk29RMWrfqPhgoeQeSsN42xwGZaxC4nWnSMhcXsVry8w3Jh8StG2VIRXZfEd2/
l4BIkMvhyyl0it63R0V5YIpvDtPEEU4Wu2hF3TZa8RuaWCj4oIxpXfNGAOZh3SV2
LaQqNXa7sZFctjErSOoavkyFlvNwQLEcGttP58fi2ptc7lmd/A/2VXlbdpHiUDm3
qdC6AN+Hilz2a6aDpx4zmTkFOKYrTlLmOevPBKOJpfecrx8jpiU+BU1XdQkqY3G1
8kp63v92NdBq0bo6hmpIC0HTg3RT6otlupI7/+y7+inBRTcQfCc/k08cu/DcunvK
7vGCWL+7Z6TWFqSVE0XDhn2uafxbaesGVjSoJCgZ9482Ea18E6Ka3CIKd+D3PSML
vIh3O8142ohfPQpMo6qe9rvIP4uGzD5Ag+57kSXZCU2/RPHPsmbVe9/3lP8mcXjd
YSvYSMusjTm3WjPnwjaE5KHbeFNKSxHJt7beBjr2Rlt2KHqmJr56SthsgOPgAlPS
QQFyJ8lNFKYpIqA2KHeCyZKoULu2F40JYP6YZqcc6vZnyubiUG3g+3Sn0OjPcUM0
q2hH3i1YdYqurEzWb4vR/xXj
=WTWV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3a48fbc3-67f8-4e8b-ab26-5d7e0424ff59',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAx1q6LsEd7r0bDuijpzWksLb/B4hchH6p+HJvzClXN56x
OcdK/8Cd9wJOHgBBCpe5j2+Gdyglytjv0c1e1gx+rh7M0OcuTz9VUV7lYsK1lxlU
F4DkCM6Et5Yar1HFFR+nbIJX+puivdpTwOO9VKQuKVHzmD/1HjfARJbfGNPht8dx
dYBrwbnuqzmbhFwGw46F1EhMCGSsh9Bz/DjplVcLLH8TBq67szjtuwSzrmDVr+Rc
KxcRE0Aq3jPtl3X0PSl5GUEQql24KBTGz6C8ftWFOpi7LkwsqqRibAMKejzGqDiA
HiR9g0sW327s4nNHZ9mnEkfFSeOWh1iFaOabS6XAMFyte83VHHzWtYHMWvFIBis6
Txnk5JMe+dkjSCl3oqjVXm+Oq++VDsH9rxZ2NL38go72IBpXevpQZtBFgNIlY16N
IhyKWl0nsTViZam3C8th8sRVVmUCWK61IBDFNuDPg+SYxs9hGkp6lZvu9qcSqbX1
y+zxAu+HXWhO7uGENYXcc5rGu4yRYv1p530j4o6B2oC0umsTPdiY1Wjv4bkY8VYD
xN/ktLpSNHbtx/N6XUlJfgpEfmP171sQBW97iubOrZEFHqT9l5q2rr4PfNKhzbUX
Vua2geeXT35wjbicUdblAZmhMqsC/HUz2lowe/z6+4g3eFV5jLKWbsHUmexgm2/S
QwGMSXtr5ev/o6Hjqzpyftuifosdfn6/xmI6bd+eKPnhbRtVKnY+ICnioSGvORRj
Xg/d+6oQEBptmaLmrC2+gxDVAv8=
=jEG5
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4241ab27-53f0-4f5f-afd1-a917df966f02',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAArBlF6HJmQl5Kf0wALsRtvE2kKCVWgwVsFqpmcwTfm1ms
rVI8zsdBMlLycr3l/aBIGinZQ1r4IXuCt2YWrD9p8sKVJEEPgLJbIcFzkPr+VTNL
6zryyArtkZofDo7naPFXgQzh0l5sXtiZCWcgwr34uJeJVWTId1geoQaCnlBRDWDp
3vcF9qM47inIFK4xV1KMIM897WnjhvXb6qZXeF0qrShZQRXBX3OeBcXGd1ubnWdz
WK91zIPh7+LiB7P0b2GccC9UV706Tcirk6Q5gkMkSNxgF6/9HKwpDDjohLr9RWx0
Q381zY9X234JhFugT21/RJA0AwU7IqlW7VnNMzIworIwtOfMgi6J+rZCUD5hd44M
d5A2sdOrAXI2diY/oD6VUKCm2R15eb2gfXRFffN0L6FATyT8I5BH498eFtQAn6GD
ppwpWUFvOykV0j0FSH+v7eD4SKLTiImwFWFhp6JUu8mexk63GvcvqNTZIeG7sz2X
jyQhc/nUFq2Mgvl3eLwmZZpOu7UuY3nhMJtHm2dCpbSoH6Uf6iDsZkxxuGoR9fUP
B1/UwKo0rH1k9iXMumoxnZGmw9P+lH0p5/+XBYFgphnt7bkchMsYx7eiVL3UE89H
gWzl1jcZ5WITUsPfLGglf7sqPWfHC0wfYXPZVKg5vCRwRLnaEIFfxax8+VUU/87S
QQEzoirv5aUAt1QiHYOm8WF04bze4yV9QokmfKUm1JVu8MCdRqfDEHWM3llvgDrp
eOUUxIpVwtbIMyNVy78MYrsJ
=YsGS
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '45826c00-337d-4f6e-a92b-608010b9db8a',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAlSn1g0oewEdhJBecKQcrUy02KnJL/9tYh4NrsvB7X1hb
8tNOpRe+hz25tdNph/m3RmtnEhr3sTpbZHRjeNROHV0sZaXuhuUEbYIz7lBSjDXM
WdN+otbUwE/0piJV9r5xFpsIPYIB6djibLlsKAwdyrJvs44Sq1556Fpj8K68oUad
x5q7nkjtFDrl7Qv5Xv8hytISaZ0Ox6t5zgqhemos1FxZ9qqMHfsD9tMsCqzvOAOy
8w1QTK6O9cDmo9m5Dc50zIMm/uuJCwFGOD+Ntb33en1S4T+cFwT0IIEsG0Tb6Gm9
9o6RxHWN2OcvcUPsX+OxHYj3EkefAaJZ0iC52dxMC61aHPqgpUbpGaFcesx+Qi4d
Xea2rHGxkrW6ulQT8Rw8ofURiy9Tc1Llswct3MybTL1o7vZy7ShsNLN3TfYOBILn
vMNXUjlTc9e9H2aU2JLiq2tvdrlbksieB3zAArdM6OwfxqrWgemMFcMGvM1uF9f6
srHPjSJR+lB+pTtS7eDdFtqRiCxe3E7qQA8bc+7XgzEAZbME9wVOHUJDSzbA2lDt
cNc/bNFuiCrgGiGzu+e6XI8f43FZ9J8XQNcB99ZAhvHS8m6IGYR8CKm6IAUCdotK
6uzQGh4ir9cpfkTWXQWdVk8Ty7fRYwM8x8dkhU77eOzQ9wzEdwQJEYlsSTgwWJPS
QQHosJEUUe47wg+X418U/TTSdpCXZsB8sy6Q1N/nypmr0x2TUqu5mpDn9dzIFsAm
oGsHBuaDRpHOc6DEjqTI2IZj
=KONj
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '45d5ea43-49c7-4562-ad41-eac1c9ae1698',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+OAlrhIp2/tqL2+vsGDmPO/DeHlZmlLvUvs5CTy0EPmNb
T+WNezSTXQwI/0BQHX37Gy5Vrs+xCR5zERcSBk8nBoPmvlC/jvPUFMDbSBEL+5hi
gp7M7oNaTmQ9z6Hsnn1koRUpKEsuNMwqODnJPhKblumZalXLbNmb3E/4caDHGOFA
Mtaev6ngLGRI1Xq4abkeOYOGIlBXXBy1NKDfmwI+o6ygkmPmuLG5FXCQ44u19gpc
OEVR9RJUdeFxqaQRKTKptPCoSzuNASdNDTVT+fS3IPrfGOsedPOFzd0r8fp9KZC6
aORN8Y14y9jypmL8+kE++TDlHPjMw9aV2RPH/zyMWTzyRatyK95AOUU45wF3TGNv
IYOGae2QBaFp4Azgt530TBxOk+7f5JUFBXTcrgUQcoVSMKTB+jJbxo0RG9b55fvZ
9JogMjMuS5kF5lW/i560GjfZg21UBqEUv7OVFahynpad8UzWQAqTR3c5W7/E+/NV
viNe9BxMFGmOZzSXtea9C1gFzLjOSJD0fuwSPoPk5ALhuzrfL6PJcD02jvRWgbbY
F7ob2lmcKrUNwqHD79KV2OR37fKBxd9xP0VwNq+4Mv2HaBHa26+1q6/w48daU/Z2
F4z5v1p35Lp/ZLOuuv6sP6oqasQzUi8sbDWTgBdw/remCjHmFmQoOuS/NyowvtXS
UgHK4x9lPt2jy/WoPauUpgZtN0QHbCLpgpOkJRH/RX2ZHmwTKGqvx3Y/PdgQURyV
dhkZUXTqCnYrMceyKSWaviFUabim7lPghVN4ELtn0DKZ1q4=
=jVi2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4b1796af-8b7e-4b22-a351-7a2264e0b41c',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAApYjOR1cGpBlfwVNqMSRy8d/P2c3JBxuWfLX1iCL03qeL
tklDdLDyw12bzb7LQ3DJ70KVU9+WmEIeeg5A7wi7ov+C5y3yGDYPAx+DGRFjT68n
qUZHSt4PqlQ2FnOO2bHwY1xfb2Ycdfq4PdJy740w2AYKUXcEVan/JU1C6ls9PEfx
mrptDGcdFwo+Org7OSYSvx8RCnjuiRGV33/Bew5KJsiO2pRspAaY0SoZNQC/bxds
Ddj5swfbC3/IfboKPYrzmd2ZOJaqcB2jj9W/RfaKYhJiye3Ov/8UUAuwBW2GLE2w
JqiHDKB0oA8OkdAmC4Be5rusmLgcsNwitQEPD621u0gyES1cYyuowWXyzrICCmSV
m3xvGlpKNnf5xr4flrYz7o+Y1tMbsNuNscTYeUcCZZHG8Q43tPce5Eb8xzSH8X5C
VVfwEXfT64/bi2L4eVNidiDWh95M0CX2hcX3RKlov5oGrlekFco6EdfWbjB8Wq9o
oPuiHRY0XIkY7ta9+u6zCwcHMCwUrcdRogFnO0cyiYqcUEG2FauS04ly3MpUMTGJ
lOMJ2vMcen/0iMGi14IZ6KodZU98C03corJ00T/VXhVE+egzPJMNALql6RgWrNMu
GpNtECHZK8APzz48+zqlKhVgDPuvBgTv85UchE7/er9bJl6SoF2Vh5s+OR2nZYjS
TQFIIsT4wLkQ2Ty4SJCS9Vwq/lmmlnjImz6JQTgrfyyyuwDsAMnJ0H7n2fEfU8aZ
jsWayHwQHyZJ0krg5Zl0Y/cKtmBkiQSR/zOEzlai
=9iJF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4c74bed3-b0dd-4767-a84f-741e8944ff77',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//SHkXuJYhoYq9EW70FNIPqW9sQbZj06jXdXEihvIfyD9v
zD6BYXEsKl/72z2JeyqJ04MSZ2eKomOmYSLEb5I5pr43KHjvyVFDD1RerOVwpWiD
y517PflxB8WzGTVg+UQ6I2QduDVAAXMpNEer+Z5m8mVXwNvQ84twy6JtpRQzhoCF
k2M/Fc88ZlMPyDApLhArzvWHSl+XR4nwc85vomn3lBuvfmk/v/cnG6r1D/uN76nF
aF+l2NXfkPzlW6+Yq5WHRoD/cn2fFpbIKGyV1VOttCDu/mVKE8sl19dQ8mHRI7r5
cz/Ccbc+uIw8QQlB8dVAPz1gjwDSWWX5GS+BQDPiB69M8WBGVTdeSvgYSagY8aFj
5dOwi6prgY8piW/Yq5HWto5gK8mtRjY/LWrwHMb3OpAzMIv3AdStMnfHHwrqWXJi
QGyWCWlOpJsZey/RXBXkI2CNYJPGUCL878QcjlFIT08MOzCG/mfA7xIz3/75QNIp
ywjo4fa/zWNgGwHm1zRHySC5Z2t4674+vkdLtFbAMOSgugKpU4ylek0W+7njAoKJ
7CLwOcGr5wAjLMOnPfqINHpHdXFM8FYfZlj6pt9Tth/VQhX1JW3vH1ig1hASlOcX
aHa3p2gqn6IOhSg1Y22wsnMrdwUeE3OB2Wy9xC7RqWs48BCLoNkGvf1PWh/25E/S
QwGMiebxL7iq8xRioOthZiOQQogmvNteGT5abk5j2DIxWWrlTyt7O409Gx9WrMIq
nnWDBjY7GE7pb1kB3zIYkbEZAJA=
=IFKQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4d09841e-412e-4cd3-a388-080e75687b89',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//RWTGrYClwgx//LPrQd8pwtRSdRjJ25h/31cl2VKIB0st
KAHSF3pt4FUhkumylYsrly/bPjOvywofEBZogGmaeACBOW4EIy7mVUMa42NU/tGK
hvS1+/SJiwZsRB7EeJXXh+oyOJDEqFTUcoj+BlnDHEwnDaGmjcBq6WFAnTWdyY/C
wXQn5+CeyBMUvNqwLkhnlNauvhyJk+TNaVmZrvSZ+ZesET4cr/eD6LF8RRfF8tJ1
ZLR3rR0+jcLFF/l4+rh1c6NeDkBuQAwb3SiWKjcjU4dll+N8ih/WIWx4GNFJ9WtF
x7bNvieu0soAJBx1qkfrmO1C1wygzl5UTnssQ+LbP6iyhhovoO180FelkdSxmUWi
wpelp2y/5MaOt3GGDF0HLB2Enn64P8w/rypauRxG+s5Ezs8bh93rePZtKFZu/g05
I4vPwHdkdwzoXVVCGzspTo8N/IPzM8+j7Z6aK9D066AIxsjvlFW4W4EAHORzIFH2
llFDYSKHvGCUYqY/PTgUuwqcsasJPUCFdpJaB08lqKiQ7yZgaIBz3QdfE/dZDjE5
RtI51sjnkqF0sePoCH84f6IMQZRuhxJ06Kmre10Tx+xqrpyAbDnBuWMNpc49BeLj
M1o892O9W3o3TtEgLndV2UeL4Aj5o0JfjplcNcrnWSmpnHKoJ/QyM40naxT/7+LS
UgHnmAMIL9uLkxlFhp4poiCcIciILaJ8yTbl+1sKqXaf5JvNvWljr2zs9IUNBboT
o8Elyru+52sHpmccP+UmjGxu+pgM57VNPWZK7fy0di5jy28=
=PRla
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4d41b032-76e7-4fd8-a2a1-dcaa69fb5fc3',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+NNc4urzVp5481knO1FHLrzzsRrI6IwUfqyw4FCEe5TPO
sVNrAk2wQ0zsAGGAbNlhZBlbSJDSQCejlkwNU/pJtGJ1fOFESIYS4pHjqY0OL9KE
QZGwnfuO8Jte87nXIPNLFR+rPSF9fEdm4FUE2kBLK3xbslSpk/1jkvcgi+LZV6eW
iISAfkh0pL4oBWLJrF4jWJ/VSizHXroVEYAgP3sVxAWCyDHDCNIAN1tc4jNtzzr9
16okTWJFohJV7i6aHsar8GeJae6k7sUP3MN3q0z4wD1igBo8kEFI0SZHRlFE6uvK
x2yGpDQNe0e0SqrQBG9dZNuhi0vj/7h9pKzrXFhlMJq9RhSiF44JNMRIPqLxIrdC
Oq3HVWq/51mvd1RocyDOXd9DsZ7xsDdtT7dZp2LnaH9ickGlY226v97xO7hmnGYs
Xbx2ERDwqZN2nQEXUbnr7pKgigFS7VPDxSQpAZV7OEJtXSmgtG5aE+hjJX94xo6D
MWFTdflUxbmvzifA3Nq2QXB3vmTczpVXYtWryVpwV7Uy2t0xFin/vrS/IuP6Kfkn
s3d1o/90uHNLrF/0WaXrGnuzWCtLiga+BmFti0JdI+sJ0ziLXuS4qpF/ToSfDfmw
Nm2Jn9yqsFhAQgXO+LPiRyEl6q4EvzBn7AedPVlhVd4aK0v0bYDVbjA9XOGPCs7S
QwEEEfF5eEbFChwhiNm7Zscm+Rfdpw5SUmTahD1ih+niAQ7DFZ5FBK/ymQOq06Iu
WSN6DcdDKydJK14Dl96ews310nA=
=QSUV
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '514977ce-4476-4220-aff5-c5ec091e36cc',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//aVEZR+GLcvqsFkNhaQqsdMX4s1lTY5GBs27POFazJx6b
sOeyleov5iZ4XlxwV7kq6SBSG/Bic+48ieyci2Y3nnVw9veljj0+SBKh6Fb34Gwm
eq3PDQKjMIrTswft+jtORxbvC/rYasYmFbVUujhxSJ/WQx+8JvENSJnbrtxoZfkV
AtBakNZAkCcXjVt3rworerzqbuLs7wrlgvKkH8PVkn8Rov9WYarIzNcfYqxQpdQO
BvrUhwmQWBxc8NV/rcwNtyIbadezbm56pDPa+90ISnzVoXGBi5cnMqAkWjv7xGr4
H6s8v7xcI86WPtVCJSz0VpEm1Y4Gd8uFupy4NjxOhUj6lCHdRmU1LhGEbzxXhMfJ
tQRjk6A1xQiClC1ZOYPL3STx35gL6GPZMTAusZsSty6wLs9Bn8C1jA3BBQM0iQUH
MerG996hUuR00EhbK22ygUwO09OI39Igv8BRJD7cOkaJqZpR6sRjZVysrWNaae6A
815hM/YhX188hpSJ0B1OJLfgJ2ZgeFyTbu0r/8XooNDLa8EwV9BXFQeHuzILQd0o
kiSymQ11p8tgJXWHpFen5806BqykQGDl7p/IvlEXJSEeMwVnbZGW1EDV+CAoBPxX
v3eTXn5j5PdK/D0WBmQmReFzxL1Vsvxcp/7MSTGHzPPmFJeiE+mRjLXq/HeUzEvS
RAH3dAClWBOAVKVBcdL9jwGwF/t2gsqEINzC2m5nsc05zaEaRejGfZvaaxgZKdeQ
KvjZXRT/q2YXagt/MLBpyoc2iOoz
=/uLm
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '523eb21c-4dc4-48ad-abc0-8aec8e574bf5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9Fmeu2tYcyiTIjNRfawiuSyydUPE86SHg3MeivQOVCk1c
fFKIt9HpCeiqtUdHcVxs1l7HpBbDlLG8iQsqZ5d1tja+mxVj8MPIDqBJiBHEJcFD
21Nad5cJSWiplscFIxWgoywjELCqx40HIAyecbH3y3z+4sbXQygaIdIuYEnVJV9b
TC2NiaPBpWu0gk+mn/R3m4qSD2oLfrkUheqVHHoOyu8vEDT9wwCV9YgoHjUvEGo5
9oXSuVwBmr3+6YHAUx3cGFrpD/GRvnBxKOVn+fXusjlZQjKe+ELb0H9YGLrWT8kF
+LOwlye9YWqzZd4BKBvKI/vceMq5vWEefGhU+Bv3tGhGUQAMUBjBGG5Qq0IQQ66z
EBj2CgcCHIY8KJ2vcYhx7CBq7nzI3luL4dEG8yqhn3fjWTIZKwAaD41ycXPD9WmF
1OD+VDqqC6SXBEnDh0N+yulnOTno8P5wB3rQznxFMGEbBGmQ54KFr+EHER8hzyC2
mqgIoFGq+6/UwSwsu1ZuFmmqZxLtxv3LSu0HrMAp9FQdv/ptsXlGasT+lnplxQOD
FISYJzJLIeDK27QELxp1q/kDz42cZICUcxI3lf8jwwLl9N0QKaC2SwrQTMcx8JBI
ZxuP9q+a3qFA4huneBO5xTLtzzDOOSjamv3CTXeqGKo4aQr5BrFMd1T4/PnMrTHS
RAFucegUN1COEoMxZvNkT2duwlh58bvNa8eRJ+GwPh083KPmgxhdd5/2XIhWfUDQ
zAeGVmRKOr9Sveay8XRwMuUZCkyY
=Kcer
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '53b1f657-312e-47fc-a33d-343224587eac',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAgXsTjaw5fsY3g//GeNqsDUh7h1UT7H19+H9GVSpMUN9s
XdvPE6E3BAgA097wt2lDCBfWiNJjnFAMJWnqJFqQrmKxSAeUJxaKqIaCU4Xjv8gq
LB4NFmCfZV06Nxj+GAzhKzVxdaECTLilTsm6jYe/P6l93ecN3l2pEzGL8fObzkPV
gWsra6Yr7lACzxjpWztw3y12Rg5e/xoFCUUqBdlr1D+9TcXyKSmMlWQ87a/EYeFK
U+2fGTv2TkGfCjn6q7HKgYG96DoO5ZeHj2Ttkh4RlGCdMcdMt1RpmAqDigryH6KF
0kLDbhHOd1YMbLQhXfVpmqu/+AYNHXoHZeY4B5cCzKQ+U0/jCdRV46faobExeBJq
5Y1pHz/0SKXRVn5qtY7jgG7iD9zV2GXB8tRaqW5MLrDCiwShU3p8RkeNNPOmfgei
VWckd8Fj+X2TzUNEzaip3IEVrrbNQlwRHaSpN62BnnqaAwQlFyJFWeX3F8anE9+c
Gnm64CBw2cVJNcfsIbvJiKkh58DO4b+iU8NzxsqraMbxrBeR1ZX/k19yaYsBmDwR
19qty/ZLuYcjlzo4C9cYfy8jb2Mvyx6IV69AFi3BUwIp4U6fLqovMw/ySjdeTNYS
VN6Mrd1i33vkZKot9Y/u+oOUcOdW8wsSkA+Io0Wx5n+ipeyaQ/9TftjUcXCoLUbS
QgEtY4g4L65Gbe37jzZU2goGrV8j/MmrWM9P05aG8WXCzFd05PrlEAcYpFZb4XDr
nzup9v7EfR5ActTVFbkrbqUOSA==
=CHN0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '54ee895a-b2f6-4ae8-a8e1-a5647352d59b',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//Ss+jVvdn8y1fRAJWSwVcmlmb4NZVSbkT+PAn8BNfBlwc
YCKaDtnr25xIEoscvKgxMHP3E1uk+SlspxgtNg1j6evtpUpsU9OsTvs1YFoz9Zx1
SQ9gsJTM9buDbTlIHkE92vQ1R6wWTCXr3rB9riT6SY4B8iNI8gF/eeeYipn6VDXm
FUZwSoYpJc81QBbGWpwGA/+8MWvSuNgaje6tSYd1c/sgrqbO+HSyRqfAUbuHbFQ9
LXYVCnfKxb3Itj+KRDyZxo+cbwNfo+VhKhprUeAwx1Ntmmq2Mz6JjQ7wu9N7kVqr
Mp+9Fa4Sl2h3dH4BDZ29XQcKqUxDRKF4xtQr8QUcACcgDa1Lx9EZgrCtElygxbn2
/onyb2wDbDDtY4eHWrC/KcgC4oePT7R8u6ucK8lgM8Cu7xjJhlSaPdbqnF3ooC1W
6JyTsApyIJtvFh0DxRi9jK64phHA709H7VoLfNgPWeHJAqjx4ZLeeDayHVBRPUsh
W6338m0I+Gz+5TlWyFfNUT9a794nzc2D0laFm8c+KACliG7X1A6znbAr/aUywnHX
fkVhnSrsyxO8IY9KagNUD+a4/g9lHzNfWpKjk7XtJNtmZ3aiThloRPNhQ6stznNF
sznQ6a+qqLRya9d8REAoj8IWXVv02OEZ3s6PKz5XKFGasOJaMXh3GX54WLZhnz/S
QQGxSJHDAxR1o0tsHFc1rerC2dHTQOe4K88e2jsZluIUVMMWlTHRlWoCUATYwyJo
hFlO7XY7b0IgqNeIqVJ6VR6S
=k+3/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5b4ea040-a00e-497b-a388-3c4f52a6d61b',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+OpJ8uAb+HNM16Rt9UZIvG8KaoaoFXw9ZEsOg5a13QOXh
heg0Z8orPArtO02D8kVKkRF8AGt657PDOjwOx3P0ZlJQImSy/F7zaEpirR4OO4y3
sGpygQpM6eUdJukfbQt4I1FOKA7V87bPf9tkAlXPnM/5St4OsHtK5P7PtkZtGPIR
1x7jXeoDDFqHgza5OxQ3Pg4V8oZm5ZyPjC6SgM31DT6gZCugpZxEuMolSe6LoJP0
k8OJrmLZ77LdBHPeD/d9Nl1W3qkddi68GhGh+Lbej0ZxDUc33kV1t3UovR/EXbBb
J/ormi+GpMVNx3xH2e5wMDm/m809zIq776r3xCwiLp/zxmQaurTxDy1AS/9vcNzP
I0XD2jUIoeNMLPfWdDHnyVRaqz7XHPOsDiLbbwPZGbW1+MxDSgWJu+sGmezbo77p
tegk7ZTKvoLbQ1XIqNEAZn/tkq1Iro1Zwafqs+YAqXmsmjB5SZWG3AlFMcRYXR/d
vemiXO1ifKHXeOdMLYsyMUj2PCxtPUFgMpRbTuj7IHnugRRR8Tclg/yZP27FL9wY
+MZMUevsQDmKB9y5/WVgFPAIXAfy8jvCUZ8oqOOktbpOgqWiLCZQRSULjEntWW2g
kL0dJP65X/M3fBh6g0V2N07Kv3g9w7itc6qx+UQ76HFpVFai0OeeIxEm8/P+G0vS
RQHr0/BEmb0296Rf54DAe8dj+NBh9Kh/Wk+MEmmTFrTEbqJwtuo7koxcXQKkXYxt
6tECV/woNO7TZ4HC2clyIb4+PlqphA==
=Zoyu
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5de1d4c1-8d81-425c-add5-930e6e87d866',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+IRz+j3oVxfe4b41xNCQ+ImUCGnLe3doKzSRz6MYlx2HP
vcepfd9IDvk6BmmLpkUGWaDNFn8gwbxhdcgwrI6oywkiW6+SFoPApeSjhL/2P42E
W2pJSYr4SnptNl/bVBg1wiXEut8w4MRnZkbYl1p6dCfBIZQhlPw/CubFjc0Lwg2x
+51J+Al9CujguYjp+geRDtbSh0cBWhZRbeayrnJgKCinEQt57uCYgJYL0XfSE1EV
J2IkFwZ41SD9LSndN33UdadeQ35azEjzSTjrX39blpch/yhbSPVkBW6Rd1MFu4HU
sf4OuGPkRmvBAW1hrjoaPDVdqeOBIiwKL+DsmVNRtE8YM75UlbDKQE1KqRjRucf8
VyYI3wA2GYa1T8f/IaLO9lCVgCzuE1CN5ZPgcMmIYEnf7+mkR8lQnOTSEHiArZFZ
pp+tFWaXZmROWAs+1oxRuGeMCo/XLkq0xhMQZjj0LDmyi+bFyLwV7QeEBZvMp+Ch
N2VFZ1pbExs8u6vIraFcsW/z4wBmTFC3V55x6jdsRIewcSqEN8x2vNfiosdzlO+4
OKpVZPCgWEz2Hf1M2rCm3ZNXLYfjMFwE0NRpxgyBSvPBMmkjmvC8jCcaQDxiFVC1
PgJBuQQc59qfIIPQfYmOI82pAWw73103nOrlE8USD2UekdTVn5IWthsHF1UvVqDS
QwE2AaqtlswUfx1bylPc7ji8t0el5E0Zp8kt/cMxBoVWCNTKcHVv3QrN7ta2MkOe
x/6MWS2wZi3b7dw3zUWI75SarzE=
=i6iK
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6181079d-133f-4923-ae1c-fe30a3bdf14b',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//bLqmPSq4yfH3gS6PR4hcpGmBinySS3BqzMxKQdFfGFTu
5b74qMi71Q2UUmS2cUN8aQzbd0We3x3yO0aBwD1kqL2puKraPZ7WXRIXgtNOwjNN
Gy3XSfHWjSq0lgEsPKtCCfV8aQuegloWO0H/pJAA8vivBp5sokE7CR/5YF9ujuGh
ZjegZLQmGfTy9zAYBdAN3wbVc7tNUFPvkQrHeiy09aZPuJMVRDI1P3nihqLldy0K
1EjtkKuDJLlj7Rhp/Z/+sQvonCJKp89MtZRWhAE5sGh+AY0N8ffTFkX+sIF998JL
qfPkEh45mirSY8/Xu0KcKiiDWfW2OkvKCtnSB6+KVaWto9I63Dl64NFPnhuaVz3e
843Xm1cQUKFZUsjuyIZ24INMqxAdEri26U+waWdHUdpuUR+qgCxQX1cfwC34+SKI
YuJaTgNFLHxMk1SeogzcHpBREFy1wG0TER9w6WtItgHMK0qB6SterIKXvaREYNfD
/z0hKhQZ/pUFuR7SEPTo4SA0UQZ9/XNDM/sq1jeudmFcIu1WvNLb6vZobs+UK0jy
oKsBDDq9vjXbeSHNPnb52RaEKHOPIdlhpUjNpNUjjR9F574/g/opG+JCPQx/G2xV
yOhOL8AZnqr9s4GOravOZ7i0/g9TpozFBLlK+HsX2vuYbW4zYJ67gwLaMeOlOlPS
QwFeIzQB6HmaC43qw/lO0C88a5cmSJXzVYgA+MMwBxOwER72Ivzdf3bB6wx3XYRN
2z9IZ+mbsOkCAzbpLNz78968pGA=
=0aGe
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '63a4da92-5236-4e7c-ac84-da34892d3f7c',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9GElVDCGqng2xXyG6aGq914W6nLqlCZb/svcJA+1Km50q
AqEYzA8T9uixDOLm6AzzMOTSbCVR7SE676zsQcYbOoVCMydduxfX5VJVcIiS7ql0
o2ry2K6IGal4kfh5UGt5GBw07sH8r3Pr4qFZxquK2nqGI5YAtGvYMV2vfnzYFIL+
K2KM/M5+tXRZtLBpxHX7sDrxKXE+8AaFShrlMAMZBlbfuQJ5Z/BMQhOod2vMY7MR
oR+G7yr9WDy+XP7Y4a1JSXesiSEoLCuB1Ji46vHAidpi0sKF7JW6oZMKsgS5MHyz
IBw0ZvhcFx60anyCa0jnu0Dacrp9eDFt5a252MaYsmeNrID7jAJTFjrBBstwa/n+
q7fEY1cfD1zzGzoylLyM1Srn1UXFQ5jGLBvZUlvlgOxOpVCSCiNtX8/dEtWOU0dM
pKcN8CGX0o6OUjQ885eka8O0ji1jPEjdBSJjDe8dF/2JlbhkkOZ0izL7VBOaQs4g
ofocwPaUoI71Okew2dUCg5s/No7FH0O2539dezQt1KZKpn0Kms0G/KzenjCOEJMu
PC6pcobZVsi2C86ZNwXGj2Mu1cN8V0o7DrKchXCo8Vt3SGNCCWNNY6zZAiYX3lIE
CMkf/HG6OtahdAcLmd6oBNwYFd+ro+3jVLWJtvmK4mhxz2jEVzKF+tnlJB7nNMfS
QQF4suge4ysmscP1+hdJ9hN3jloJQ7lo2PqU3VKAFJ4sePzHXu5BLLSnSZbEQW/o
Ra2phY5NL1LA6uS94f8qezvl
=Hnfy
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '65a1d55c-cd8a-4f77-a362-7bc89395cb85',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/7BS/9d50QAf3miMEdEbI/Dv05A326t0LUGt5ZSPsSZOwQ
7+Zb7JQtiHJVAJh5YF/sz3ORvALibW9b6gHHLImjfYCl+Dg3RjSTZpTcQ4C/EWIg
t6ANfmyBKtpG2clIB4PgNBlIFVVfmcSCKArLoY6iOSM2524eEnLS4krF1il/sSJ9
pHpdwcLSiJHdDmPam3YT2kL5tONvTxb6WQBnxPU9+7zQl9maWSV4K6A8XA5Ib4ba
uQ18k7r40AAUIYWGZ2miOQvufF4/kEbnU6Psv0c4E/Hm15JTd+U57UafLL4PuRMy
Fadm0bTkdTnFSQj+kA1kqDUFraIXPfANsRCscfmX0AI5w82Eg/H0dZ7jU8rk4i7/
vsffSDSp25On07gtzx6gmwy0QmuKXlZA4SY3ETj4Ky154BxS/umNJvwbHbKHxwMS
HwXZ9ph7S34LZjBxJ0tZKwIUbUfDMHhTD+cPqlLjuATzho2KkzWMvlLf2b9jowKd
E/D8sMfYOTAuYb6VzI4C1VfAXYEOtX6136+3JyVBKy6SnU4IWkPPnaNsPeHRp+Xe
11Lwq5sxO48+tluT1l5AGtrFvu16wAaAsdtc+HZMsRAx5yakuOXK1k0koTRd6riZ
bFv8KAU1lr428SN771Z0zkgnFTIwCNxqH1i4d7mxmWZfIU3/+v2i8q6oHHR4hanS
QQEVZXgfwYIOFRlJX+I7tw6Hvfj5jhXajKIhlJi1fHh9hGD9Pt3Frx01+5+G3ylc
vzUD9L2YrpLtFCAPniWUqig2
=SZay
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b34120a-dea7-447b-a4e6-679ec2396d6c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAlKdgLpYAFpVu4QR+ZecyX0RnpmhJqKZJTj9aTMDXReBn
0bz2p6w8vH1zFvsAF6TYF6C9sM+QS0kxew8Zo9Vo8yGxvTo8AMPQP0MtgrYNRchv
xoASrh6YEMS1v2i/Et/n30R2zkmkAymsF5Zkd6qFY9GOeHMqG/m1fXHqGrcAzrEa
mptz5Dl5MWITELqS5N+f1ZYWq4eKnaUp2qhP6LC/Bi8rcxsDXPCGMjBvA8+yai9d
4KBYtJRKD+VGIIn/pSRRD5iTwtwh2N++rw0UpvMBPQY1z+yNQaCmQ5edX/VI0Vsc
fORbRXZS42n8iDLi5cbxEpHkYIP/Omc3SwdSB3NU4jS0ofCjdm0RXFi4+8E4ikz4
phpBZ/ShOOy7SumM6AZkLsfC9L/3gk32753gLq7KW/WIHTjcu6QOrYfVxapdLz6a
DEKjkw+1bahS1tYSJxc/MsOTEiWsV5TcTUQrw5xBcXiy5N5UtQrAbrDY/h07TrK1
H7KhiV/x6Flw7vupwf8EcmJXXh0D6lms5yQRSkDaXAoZXWEyi6PDu/Vw4qqguAqd
22UTLQD+UaIWAoRvd1daobYfC97pbTWmvtsz/qdvgdXLuZo5WV7Q5vRWED/rSTi5
/EqkZB8JOcLmD5OueihRwsrl6ymtap3piaFtUh3MyhuJoNxweZI0LBc1DRNtn7XS
QQEpCwRKAPURYAPe4qTppYWV/xN7i0/pH8+F0uPYzpK04JPAkCK/u3WeRwXqp9GF
rVEY7EVLO1Lj0acllHoIcIG9
=cyl1
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6b9be942-6e40-49cc-a40d-ce99f4db3b12',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Yc3gwnhAnPCYUv3x0L1L4l57R5ogUerxdiJ0hdKHFaz4
oGbDSaDZKhZh5ziv5Sj3GlKycGemSSIZ4IZ7TyQcKfYmYEDP3nL519Z42ZDdSeox
mYTThjXvBJL/I1pZffQ7tPfCX5g477YZ0tUrY/uC6PVGOrZEBr4Umg8SzyBHNw7K
KtXu71j+uiuuTlL85NVizccDGck/mi+a8dLyuE6r6p7laj3bciMpSClXF8v76zSS
GPbzR+vglBHvVwCEgLlCgpMIdZpVPU3KWOwgEKuoYHAxYt/X34kmfJjBGNnO9Y+e
20YeHBSHr8iqGJQh80hBE7QEr6S7q+nV5G3C7TuuHWFKRAoNFd302WekL8JAyly1
ic1p5KXFdWa7ltx5IWJ76VBG8KU9jgZHfeQh0dCb4aCdibNXvdxBbA8oyFb802zO
J/X6TMtgtiEVKKmnhE9smR08ipbVK6gWN2pwERul+Vgb/bUQAn/uSDFRXqcpfXZl
Ax+sQiBK8xzgi1wULDqBQtghf5z4KjjvBZZpszno0wm/BmNRTpdcPEBqFkXUMjOK
CxR/BOt+ImvYJrXLJX29PrlHtwyownCdAPSkhFux0AZhKOJmgECRYN+d2NmJyz2K
IwIdKeiCXo6sGkqvOaGX3iTk38qK0opKFxN6nXw0PcDzMJ6B+lboZ2/Eve238tvS
UgFq1GyyaFdcbojM09z2uP2euCRJxZ+UCmXppkgSheq+zzMp7c4EldmLQbUaZk38
pygKkaTPQnoDlKAVQamuPkl3CAMLR7L9bJJdfjZQlpkQzh0=
=NYLQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6cdb9832-a267-4454-aa86-e7dd78b4060b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9GTO93K1a2zW68iev8+elSgfHxZGvAA/MGJdBRjRbNMA/
Xp38FgqjX1sMywwCG7PeG0TteYSUGTr1rl6O0uvR7rbgH/MO2+41v4M2kHwCBIzX
RnCoHnevumgkYwRm7lV7bey73f+zjgaWOsL7kk2na+HHXQAccN6lMoDiGsZNBQwU
nguxVaW1K1ys7Xymx2aN4XcgFJ+OUTpNrLOCt9KhNIz/xRrID5X4P47n4UL4tl3V
4pxTrekSJFyDiJS34jEjnIZXlShC2Bntoo0RWeb/ZAPUgIGwgedhupTRMMNJF5Xm
4HM07DeCYOVHJo8YOI+uTpyoRrYHeCgiam0KS1cGrvNbITRPwcxQqj5sa+0Hk7Kf
wRGHbBJdZ8AE9EXwRPXkfHcnyyhXnd39uFxldgjFCU7SnnToLQYkRCPhrv3dw7y3
/6LRmT7RRYPhh2Q30BjZytd57UjsKbjO9z21fCtXboaw3aZ3+g6RAlKeY9Ia/Csh
o21JsnT5fq/b/9uNrlkv6VmLMrAYoW4F/672RCijZMAlaPbPcCMsL7P08ZnYefNb
uvraPiCMXKMp7KUIa3bd2b1VboYRwvP2WTrjAbygrQBBUAKHqmf7qj2NmByfx0sP
KZ+l5gy8tFb3zSXwHil1en43DDdvgQx6R0gHj4nk39kj/+H5Cp5FSgdmJ/RfrXjS
PgFyw1EuDQFXRoGG+qPBHIyth2F8YQDZjtMAlloW2mCabhXgF4wB6Ezhhwi2mmi8
sq25wL70CX8Gk2NZCCh2
=IHYc
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '765caecb-016c-4820-a3d7-3f2322df5cae',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAjNx50S+y4PBwAALLcAkKMT+Qy6KgC+V4YSE3ezYL8Gs/
0LIHHy5FagHqqgX7APrnZXZWFewHVq9OPfiWrFNpI2fpyqhCG5Hi0bEPRE4bn4v/
j8ykcy11hCgkxiIXuK9Cp6EjOxe6EcER+lbVUXn9akCybKKULG6yhBg0cCFRt7gT
K+BSKVENxC61kz48JNKxN5R7B4nb8lHjzm/4oynl/AeDO8Zpqx/6Da6Do0r53O4i
nvDxEpl2JY7ajB4Jor9XwpjTXVbpwe8wkFuumk/WM441hRAKMjTOUO6MX3M3Ub4J
iFsPTjsURYKdh7gKlKTaqZULsy8yRQxUjKv+VMb79eR2ojvyQeka6BcI0xKndl7s
oaeughRriCHXrYE+eVLDJrL0JnT5wbOF+fJoY0AT+wSKeN7pLYQAPwjBMXEtta0j
yrcjZLKMGE9kJ+1Ri0mcKKdW06yr7pVBWDFmRpEkrMJtSSaGOj/MoH4C401u17z2
KQfM8ycLYxDwSTfDF/2fG696ZfSTe/NbJFulbzlc5CHogENcEQMdYrpLvWzkuEjJ
EkV/myG8Ndnav5Gn38S8Ce+tykGCpbSfX/sKtwd3fWE/wIV2CTwau2iECU8iZEOY
Xp/OK6t6whvG4Bin5JxzZjE7ICtfrUs2xO1mJikSYS6J+9Fkuxz/PEbaTpTbafLS
QQEZRL46Lr1KJkGzC5TRfqY3oj+JdlMUwfMWvnYYxjHV17ATqebaH+4EJauyyY2l
6Cpfn7Tlhxahk4daDFA7oAL1
=7DnZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7f5bba0d-7a29-402b-ac34-1b18dbc0a741',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//QBzMIKQ23GyrUWQ/jidgrgVYfJXfIKevgFv+/SROiIXQ
zPPEhzemmsaShlbTA2ZGwp5/urwyGFO4N2Gm2sOc+GuL33pswaeGJx8ft8Ez9w0p
pXVUe2q41vnTs87qHH+cuy1PNz3NTGhVn6zcrxdVaCU1PRUMIS0fNLqAzPPSqfOJ
W9Kh3Eg2iZooUdaN4eePGml+V4FuHxLMFgKdRnLGJrqlfrEwomMVZquLA7ReHNrR
93bb6yXx85pwHLuKZNMRItJoq+qjBbJw4JEVbac/I0CFrynvN9nx6tuU3dtULRd9
ENpLG6kN9MukZJ6YMiWkn9u5TLWqiw2ZjSZLj63xoafW23qSIfrB0I8m0YTXmrxZ
eG/SUPRbvfYG2Dv5J/1UEwdRAHbzPAIkfg9xmajdBHX3mau0ZT0C463J0I6xIusy
ZBTyjPiupSWiRtTRAfbQgsNLCGZxizS92sYvBV3FWHqCdHN7EcmLE0Xg5h621nFE
6d8tjZ0sCsaYQoOK7Pw8jjPCpZuhKkQMq7VtogB8WIaRfMa7qc+osRRM5n0u3Znn
1mrzYZfqJGMcLsp0qWGQtpNu/pN+Ch+4AybCcKChDLqfDwSg61ULge5fIcwNwrXB
xFTsl375lM5MwuLqMBhIbtWQ7M7rJj1aQWZHvmgACViVBiTVznuqOYXZQwnqgQrS
TQHdS5rVRssrR75yIQc8zImug928g9pzwq3Cupg2qbclJEE0SMm7TYk1bLmszr78
v4848uT5T8tsh86VHmpTwwqDOtW1P6lTxdd83mGg
=tfYK
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '7fc9452a-1c01-4d9d-a3b7-db432778d391',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '1a3db9d4-7840-342f-a9e5-cf80123a5443',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//WWGaGyhyukSgPdAKwS2+d1WOMMnYDvuJIRizEQB1JkH1
5XAKF9X3kMG31uNTHw5Og9tXPtmhVgEdvq8+wXrwPFAid51M33P0f9V0HfHFPFed
P37hTRoKjLaMFv/DLKPJwdTULXjWwMN6cXv1wfLviwoJJtV7FY0OaTYG63jfpryS
7wzOsEFBP/6KF5QvKB/y52GvX28C2gIR2qfacCBoXJ9jGQlQJqiXMicFzPD43wI3
9FB+Cm57rI3aa7nfO/kbe226WoofYuTy6c8GiPD/8VFgrWt9C+IFSRPCN0irVSZl
2D8flQ6J22sEsD8xr+CNCjZEs8r0rARCy5hUbqm/f6OWHc0ZM6dquWh/CTFFABt7
5+CMgi7xL5P5FflJFjV8GuiBIKyN0ucBFhuxLO/2CGN7/3YDCw7sI8Vr2dFPSi5A
J9at0U0G1TmUOB2Gb60IKVybw8aBOgvMtiRPYnKVyovjxSSiPD/0IZGb027rEj8P
O2RY0j/ps0ElSmhwS4FHpWwzmUKP/yyNUX0W7TO6I5OPbgGnlLeI2Yo58k9MGYon
L5ardaIWVH2qQE5yBkcrxl9iuxtMrkQEkUau4mNPXaBVmormCNdcfAt1CyWdhHcX
3HNpVXjmyN+YupMlqQOZK0aM0xtqfmb+7z/A0OQvCKtUXsDEOw/QK2S+vr1jxbjS
UgFa9AGn4Wubwpbd8uvUnHDg/7ICzTR3jY9HblaL+IBgNCXLkO8J7YWCVKs5cWQ8
eng07/E7kNNRZRc+QbMiLK59pKuTzEnPQwKrpFD83YV8fVE=
=rlJ/
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '80daa756-0a60-4a54-a00e-23095bd9850f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c1a19589-752f-3b9f-a0be-e258a077af59',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//XF4jEaZarGAX5cdtKHs03C+GcCpiX2TJvLbOWh77wZCe
0n3I9eoITy5N1rAs2MaxiSZw9WbwwbQyzeLwCZeHXNh30LQOOKuLH/0qB4Y8APTO
rUL4Tg2HCtdInmEqdJOfGLN97PnRRod9pTyy+XEIlaZh5cnwdIp3VTRsGR7YVMgU
5SsDlsJsyMvkYHk+YVrxKA6kdmAzk5o+MFX9dy8ecmfitkRRSZDMRSKKcq4hJuRl
Mw/Vn4QugDlhY0wRzX7MWOY3Vwb7X9P3SHZStoqEOr7bfVt5btmCm7god5Mjh1l+
OHqV54q5askiPISiVrDVFnKk6LytC3cNPUOIwCOqq3Cp4AxVIKPIB5su/4gdogim
XGGTb4a8qvXl7BbK+C2O3Wk+j464+rfNPjEuInfOJm1rpOoXQh/hgcwBiChr1Fol
OxJDKeC/y2s0S6qHR+MODmqnzxBtIt/fvUpoSKb3XRB/5XporN9QKgu0c6yf4np7
SZia+ToaYmT9YP9iH3HZtNw2iT0tNatklJcpWvtWiGX1tDABJE9YS2G8r0F2fn59
uMQTBn59JzgyPUyc/v4aaUuzIT2uvht1COpRG2udvWd1yJh/lzCtRzl72xyg5ix3
LhJRovGD5xDO3AiH+BtvcuY2SQmErRvewAeCZ1rjWNF0Xb0wERMuMr6jLNTtrdTS
QwGG0NZT1LPsdptiQ4IIvwh9uvYIlp3MxORgpaLEVgc6kp4DNCld/MngHE4IF199
ogCPM0/4l501gxq93Z6QpSkgmQk=
=1Rgq
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8170a0d8-f1a7-4741-a6d3-78bfc4e207b8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAu8oKhMBbWmLY+8y8IkrgRJm2qPzjXFsvUiyYWSz/oCIP
UasEgsCh8owtXnLtkDprCzJbt9VaHAj/FAo4XTVfz+l3Z3g2hK9vtBdtCuIoRbEf
rmTjU+mxxmzgxL/4WNtKdtzOeELxAHExtaqbqor9KSHphC/qxpDZp5gA4MqtToRp
egq2zE3XQKOjE+izgj6LrE3CtoqgZD9kuJATSnmF0kPWpPbb9nH1BtBUzsadhg42
RPcqh1H1x8u72iMEo5HJ28+qO38psoRJx1BlxhzlYT8fTWlOWPBfBQWfB9X+D/MA
FTLY8aeXXAqu+XEj6g0UQhHmh7PRHOBKz6fVDLlymOVTHh5uNjOTimIlE9HdZsxf
WQiTbYSboLCaw1Y9gm8ThqIbptyRC6XGNu3BF+11ENUHIENi2idq9G9GCKrnQCI/
t9W4C/Yf3psmQy1bAuOC++xihnJ2pv8NrQmx8fBpsUy/khkdSZ8GJ9237vMk56UB
Z0Rn61ied1wVM96MlIbhxzlvKpq1xxPoJp0VtlH9ZIF2xlJvP3VmjxU5sYb1llBg
WyW5AQf3Ap0HZlzJXe4F9ZRjZo2/PMf3GDe1I41qV4OfP6yTpYT2j5OsG+R8/nxe
0npImPui8MlYypJxHMDjMjdApaIaUOjYckisngQEgRNrBkK6k6mdd2PseXCHpFTS
PwFS1HTpyI4c+Bi1Cl+km87M38ViOFpGVUL47M3pBw+wkd6vEj9nkuBPcUT+e5LG
znNdm2Qz7QgzZhbq4kiIfg==
=pDb7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:19',
			'modified' => '2017-02-17 13:07:19',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '82fb085f-0691-413f-a488-ac3940a7bb65',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4a2f98e8-b326-3384-aa2b-c3c9a81be3f7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//aGNldh29zl4fi5pSg+WvpDwVFRMeNC5Ji+5t6ETD4x6U
rfJaHGcnMMUAFlMjhp5ywdhqFPgbJeebvI8dkCIx4SdoSahc5u/GG6ee3mnld9UQ
qP865wZCWn2ZtAoLy0o7CFRLnSONvnvB7n2moHHbW+jiQ7whoQxBK+v3vI5UadGx
4IwN/Y6lsTiWbqMve2L4yf8BbyAD2/6i6Q6eZL7+O4JbyJ5rj+OcwOZtXKhpYXho
4WVUhgUPcjvhkP8qwAonEeIeSRQPoNJPiCTcxJXuDQyoSNGDIeR7y8Q8l2CoBc0/
t+XILYb5/5jWjQIZgF4/He+nqHtdjb/ihlFYc6wWb8wVzhN1hi/Tw7hUGwB9O4Zu
029jp09MJ7QxxLDv7JKyhbX96Hf9fNbNHt0gOQv+x3WaF4TePK20E9ozDRWSmjS+
AbERnihRqcJfy7N/oTCx7Fk1tpDLca5QcMXrjb7alqskNjH/mLq64hR4Hzwkjeig
2J76wHDVD6qBNklyj0D8rzh47MA1SrWbw0OSz1R45VUSd4rVSa1AiUFpYspMM+jj
uMTpRvlupJqdyERWLEyn9srLpYdtcdIO2UkF1zYPz2Y7MDspDd41Y3Hx7Aq5PutX
noIxQBBGc2JZtZ6GbaWC28mL/vP8Z5DLA+/A4di1KjVF681FJxmPzp586wC3Xc7S
RAFFDtB9EzMB+t1EkveYQ4SY6zCoVm0jwebbJY7ISJC+/sib3IZY+tsvDBnKrfdT
LzhnlwGOHP+HY4Hu4nI5f60nJ3W+
=aLYl
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '84235eeb-d42d-4ac6-ab15-37bc47d63993',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAyLPjThHUquUremxKHPiu2Xw8wx4zvswEYSCNQ9wBTma3
3bPz2Of+77JXQumPzUHl35LbDyMiO+nwCTzBn8q+A457Ng1B/gP57IykQmYtEzIh
8QbjSq1OfPnq6xZ4lyz6Bk1M+Uzi4rYO8FroDBA1yGrFuK8D4v+W2nQE9+XpibPF
dg5rv+qxQ0APFyPXhpga5ub+UvUAODufJ68CsjOigsTQwnBInTB7L8YHVNqu2gXx
eO6LCxNlUfWbcWmGdiub2d+3MRm81j05ZBJxtpxztDuIcABYWz+7HV6E3Ikri7Au
5NUiGM3izjyvRAt9Ux96424X3WmZq2YBLuGDYs6NxZx1aH+NRcANbUa9ZXAumd+3
H9reANwxkEo24isFdJ3opPEQNVfawCuhTCwUx/xt3YkWMUn8w5EdBpEPHTIeoVVq
fQw8waQeINAa4mhyl09ShY0DHe0TWFWQoRWHpwDrG+TPSwoUPGY2u6vhuUjppBdg
cYRFSrEq0njBou2uaxtc6f21xlH89RVPcO64YFvhEogrd7NcXSYXvmNIlGHs88UQ
UznDHM7w490+Y3zxmF7qE3rTjr+UqHmoDHwLivqWOsgye7qxGIahCLUWZ6lzo4Ak
sko5ROCdTyeCzwLe2Cl6g85Nrl7D1v01TM9K7B/zBcPWAR3UZsg8j8pzpQbJNBfS
QgHgWyrBAi9ozINvOh0H70EdcmibuamWXjMzXhcHpGvhB5GeFW4QYqHoUprBCFZN
qD8GTiQNbYku0c7u3/kMnHJ1EQ==
=4zO2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '89f07ba3-7812-481e-a5b5-fb0bbf555d5d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '89295bdb-c187-33f0-a567-4cfe3270101f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//RPJBsBZY/65FnNcJbh61zhSwBrctjePMMK9ws+DRG6Gp
PiMRYpbjh93XlpOYVJh5La2Ir4wYI8GRrGms59vu6WK2UxQkxG+8ztPdUHu9Htpa
lr3KX/k8SP8sHz3ptItTUf6X0fRzKD23tj1ePCNslWD4ClQ+AeqlQC2pVl5UPhNN
CPDqY7U8DRYMYZAM3u3vCowxBzR913+FSXRoEntmBRnIkMtl5hBMt5BUoaA607ZV
WfQRMF28DLK14OziLaSrRPh0iy6NW5P/AsGVd6tftgLjyAU0RrSqZ7/xu2rx+AcH
azoTSvY/16d73IeOjrwjqdERr2NLBv/WmX2V9IJbItRtr2ymR9AjwKXT5Lt2lSe1
7/iI18BRiqUYx7Qw1RWiN/IN8sAWqB+0wBqQ8gsYB/OEIzA8NZVmDo9z00oCi9qP
cPxGglvlVjNSQGS8GNw3oWjWkEnFYO/0r/pFk/F4CqqYqvow9oJ+oieDXZZ/RUA1
fqG9JEXslAQrFAnLsDh6PiYHrZcJJ1nmE91w3OMaZKeNZEQ7UVGCEGKJCgv6/fQu
F09ogpGfAkL7PQ+rz8h1tI894IvfocIr9A0jXcLl03Ss2ipYMkoj90El18LT6Emd
wSmXbeB30oM/w5I1+Kdy3KyuA8XUHHuKDn9k2MaIpba/MzaNxVfdhFh+reX5y1fS
QwH1BwxbbJHa/BTKReZcJ9Fm5c8JUTcq3munk+/mqK4EMdxfLOBpKFvlqoI4nWCi
/XahWsJvpEuwvTzdEXL197Nguiw=
=Vkz6
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9aca5515-fbdd-434a-a1ce-936c55cb8d74',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+OAHZHUnHDgQSRc2IwNEHXjPpL44e433rdl0JNKmldLLN
4NHAr3qgt9WttyXI63fO+6PXZDbXnP8DbGImrk6B3M5atnNhLUHPphEjN63jgR/P
Q0mo5EEq9y9gs8lOaT3AryQiEGr22P8YKBZirNe8peWq4TPGPxitFwqtcGRIes2k
lqqigWqp61Vf+x2KTNsexCY2lC7y/xa3kEN79j8ZyURZjwYiO6maBQar3743+83s
3Svmg5YYznRVshdDAuQluOL0L8HFDUZSXZeku0ewhe/lr/O1OPnIi6y7YaeJVHkT
qrjuKHPJRvLrMHgXUtY6CXCfhD8LleBJl/rj15mxZTWvIu47MYMnBmTve3Sk5PGQ
HfgxE5llqJUf1aHv/yh+KwzqdZS2y3H5/nl2oxvtFpv8/i9tae99IqkmWxpFSUrS
jbBVWKJAk0KaBHlv7FwKjjc8MsFZjGzJHQ5Ktz0Erw2YaNB4uZWKzfi9QsJAT8Ss
7FR9r8jCr+FPR9PbmiSte/ESZVsifO5R9weZfPs84GkdfrCovpvRTv6bS4ZlnggK
qavZVlryJXCy6p801pccKGTPYyO99SMI6kPVMGRurDYPvYS8q0FTHsC1qJlT6r/3
yqgEOFtepKwBHPUOjhAjA7lwr59ZpI3hJ9WpozASXya93oCA0OS6pAROboMXEa7S
PwEgvQA4UW0XWMbQtF7Dt4wmUhi0B2okj2WdS52YfSogXF+cErcYZhF0vB0AMfI9
Bjfmo4dyJtXs6OuJNqhVRQ==
=5+3x
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:19',
			'modified' => '2017-02-17 13:07:19',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '9c773695-5193-4831-abb0-67bd3f32e5ac',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//bMi74k5CN2knFEl7E56U0+ovFjJdlXnWSCkpuArFnGTe
BO/wEDWK1xnGMk1jB7IuL0GVm3kFzHvylHAEDxIzJBxsSleQ1mJSkG1ft/4iU6W1
/6HWSMwKEPZ3gtG0kRdTCuVP1gmf1XjXEjHih+bTgwfp/3DZG07B2GWvm7KzcGs0
5TSw0X5cAJqOASIwFjSqgmCXz5XApEipvTrtvqE/I2pac2xO/jle5P9J/pcyakaw
vwBuGxtR+Fm3KChLUatUSkaZEUiendYMLcQG/CgcTXIlUUkUgg69Rgck6KbxJl0n
U1jfipV6Fns/GcPoxstUAjbK29IYLJaDatcHPMGhln1c2HAPs+juvliptQ9Ho7Yn
CY1+AN9CsbrPADiacvM5RPX+eJcAZ1ZN8I+P+XwoNsxRXEeDsy2NJetLykOduXA3
+11yqzfZGZvr2JRCRENRYNyWqlwEbwB7KR0+O/UD3z3njKtJrWwvTAs/mB3cYfW2
fb9J5E1zrDvwzH5WntpUI1BhehLAerVHGHY7foI3Ra9Xx7+oCHu5AGDMfzhZ/iUJ
1AbAt6QK9UwsJy9UwlyqN5ZYzj20hSVTLDDzdeeerLjfRqlxHGV1yuircqUZ09On
JWpImh2MYp6w+Rrvqd3ezoOOWeGZxxAxLXPnSWlM6L/x4Rxk3FIr6GlkFlgt5LzS
QQED/9L7lAhcYai6SvmmTT+2mwu/9RyAHMxsOxn5W0dCenoysk2bk41x1MlOTyER
5n316S3kBGGGqVo1bIczMIEF
=wdfp
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a0874080-346a-4a9c-ac27-e16604ef0dcd',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvTCohQGwsIJ8YUaw6RAo3Rqu5C7J9HGLP1cW4t9ZoHzH
NSK7nwJsXckjyqLomQSHtPeBrYTAlaIQZRNYw8x/6DFgtVwu8flW+OjxjpR+8Gk9
SBxAqIgZUqTn6cH9HZoaE4vNqdB1v6pnBDsgDnNWcokMG07QZsq1Ob6aj5YExRl9
esZ1CVM2Of9cZ1qqH7VNLYklXttdy4zQ/IFmXHkaD6zWIo+dBfuHpLtbSy6xy6pK
+XZq0j+U4yGwqzlZO6UkpqQoHUhi16Yw5w253z3fjyYAwBe3w9kVfMo1xSu4oPzX
IyDFZC+iRGe0Q8S9EegyHPjdmPfDZECmI5wIavadDsXgzBHgLeJCh/VfsD7x8TZA
iPFai3LGjzFXB5N7DrBu1NUNUVUXtRsWU7d+JtmeI1i/58RCE3v5G+4DO8+wUmOx
PwyXjdMKcOp/amNf32p3gpVTILMMzc/vf+UqLNFCJDZTECu4NvqABtBXqTSQTy56
Wge3hHDXWea/QqqmXtDWoqugeBApjYinTbxxjitUsFvLC3iH4m9+HE+CEeBtXrdr
k1fjTpqvl8iOPBDrZ7ZKuKmKu8Shs13wpQQOykOaCEWGywOVLeXAR8JNPZWLqD1S
td7Tx2yv5aegn1V1axE1jIcVz3TRwcqgLCGrVAXrlP7yT3w+o7if5b6/UL2LsLfS
TQFh51lIJA618r4MII5UqFJgAHazqDV3nEvHv2UrlkgbR63zyzpwQVpDoGz4QHjS
sSl5Gr5IciaM3MTSe4E0nERqwtAdHlkeFtwCjE+k
=FDxW
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a51ae40f-612f-4eb9-a208-cc6eb7cf9c83',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAsU3ASztDUJ63Dj/P4bDRWQnKjgR9/aufr1ad82QnohAP
qzDm9f3Pj8MDWrcJOzlprrxW/x8sBea+jCj5RWT1XtT9dtjX0kcTZpvnwS3kdHyE
DvkdoeC0LY8ifzOg+BerKGN4jBvRPwndsuE82j9rDlFJ2ct71VW5UM7VvKJpUTqq
tQ6hXNa8AgE5sPzGinSC3Z/jN46WIhYmaPSQWA44DRJJxFXPRmRKld0psZ2yNtpN
dohiIbxgGlRx8EZSZHtDplwz6mjvaNrQk+0F4oP0IEY8scef2A6p6NAshOyTEKpz
3Rzc1i0Lc9fK0p/oFy51xIOJmGfWcsBlNknFXJzyUJuK4sTrFoHO8jOjH5Psh74z
qg51lmIXSl45emseKWc9L0LP8zSS+YjkETxM0fC/3WUhBphXiKit/wXdQxapFeKi
Eoz5v3zeWmnjO8PvW+qIZetm2ZWGk/NKOJHigr+aI10RFQBDwtmkvWRxxL28rmOP
TsXG7O8GLl0gm2AFZPdn6fjj7lsoO5AvzSgg4bAjIgqKh5BU3TUe0COmrbrJ7BYt
mxDGG0KuE9CBd0k0GxCVS8SPfL4dimTVmau1GRiEcH4sedjDJFpoNcy28viwgJjk
y0eodWfmyYROspAsEuk1BpSaVGYTkIyQNB+Nof/1GF3jd3/TnXuEVuh2AIYV8mnS
PgHlvjB5YTXJ24uGnj6RkeJUm3e2bjs8RE4wWi/RLFK4P7cZsVFg+xy1LuMY/KJf
vM9U2hBP3Kap2uUhR5PD
=9sHT
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:19',
			'modified' => '2017-02-17 13:07:19',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a6b5c44c-9bcf-412e-ad38-cc9fa65fdf8a',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/+N7Ay3W1B4RimTwfCAZmnFYBru681PVxSdVHPF5SUV6LM
eix2LRe4NXdK8ztn7mrY9bVeVEhO07Dgm/YkdOnFdzusYtLbkfTin3OQjcEsEV+q
511ftwFJQJaWpey9GJ0dg/JkbiwL6QL2nO9oY+ukTCEj6iZFq3o4oBSEPN3xeNJ4
2tkDFbnK42EjZTi5ZzJ09epwRg1LRy3AZNRrv/cmGj5ry5rFrE839Cmh/Hjx6PHR
LrjCzXvJb2Mlv+fe9savN9/G0y3h06iB0Unaq38V03tO1fuMUZzMLXha4wr7eWnu
i7Ev86o2IPb26FcaxN4lH4Ql0wX9UWWcv54+kPEGhFc2IPzuV0F9WoiNtP0LIthd
IZKiAHD18ATvhZZbjfFdR14BV7VQwMIqJP0xUtaHcgS5lFN80/6nI4uQaui1mdfT
P3K/OBLtDciid1/qpb3wEJUMKsi6NcAfWH86SrGoHVfGmiuKhybauVIHLAbHL4CA
QKQNFYaypSlpNiIqaRji3YEq4bkJKQEPbP6oL6yCOlx4+3uKKCQOvZsEocn5SURq
qheV8UNzqg5jMzceE+DusTYYwD9NyCmLJr3JLAKpNBmb8gchUY3ipaQU4JCPYFYV
mLAdFHr+wbK2nqoqEV2SC4dj3whMG//B8nCXDCjq4YBz9t0F1JruMTU+EuRdTUTS
QwFckgspxG1dEPq7Dppf05gfPSu5+bxWTaW052JvhakITsKr4TbSNk72Om4R3zJp
3fdrSHGjQtK2jW0Vr4utrI1j25k=
=XREZ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a94219ea-93ff-43e4-a3dd-f4b793465e7a',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAi9kLN94g4SqU6JySKE6EMTRuazt3Gslmi85Zv6Pz3vjC
dE2rHPSjzrnjqvJY/bcVQ/6hSTVTLHp8OqKBEDAmv6qS+r0P7QvMBFVN6xZuIYan
aVqE+v0vjsmtmRTmDCpn346E7Vpfze8H/qnOgSnqRiXyY/BqpshaKzHnwedK8n1K
cVZ4dPlqzCIeLJwnN+04Jo8iirH+pfLqnPfkSmQCce/1PqIqdIcFjxaXX2SgKPCj
Mc+eY60Lx9ZshHo00h2Ux02jGPdWA6XXebpCgHB3OvfOHZSddpOXjWWS5JuKDpXB
Q3Xpa+p77E82kaAvmyVjKtCceY+d+ci2p+AF5jBK9etVquapegjCwYBkTRnI/245
cJwCMovXx13PCBLHOiiQJuGjpdCW+2qWplAdq5scIDmGCLlUH0KwgUjpkD8c+Mh0
0OY4gEAbYtIWnZDQLcOFmLhyoYutMQBa1wkqyynIdPaHuTLPiumqZbHT4leRNAHb
77kx9vUPpZIHK9AFS740iMo8IJR7lnyHjMXz3L8FdcBcGCkoMP5dxSODcZIARX0l
AGrTmiOVpKuu3y9PiuRzvevGuPBB8WyU1/tLbrRknTZ+UOZyZdArjg9t0aO9/QUP
c7SOhpTFjRKLqU3jA635ID3kQULqUAL5dmQk41gZideUIVquElyi7B5iJ5o6pSDS
QAFRGKU5GwUAfchQC4V6HQcA79pcSPhEZDrMhZTm3JB7GTWJ09vMFXHIInC3sPUq
Nm2PRVb8ZMyZhL3gSI20Gtg=
=rpe7
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'aaa0c103-8b48-4462-acc4-8c8aa9943a1f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'cf6bf684-8f38-338d-a7c1-65011e7d3ded',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAv8EwQVo6Q9zx7MJAi4hES71L8GqZao/Pi/OOvPIkZ7RW
7CPosGKALy77pEdukvvkLxfLTZamo8f0x7waZCDs1jKjFbjGrD/JwwjDcQr/T4As
sSHDfeQ6RdvtGBciv52dSKZECuaZVfRiRdAqW+Oqcy81y1qkByKuPH2Xa0T7gHUK
vaIS5X/n9kTf37/7/OfEFrAfOQaLb4NPTb4XSYhddcqXgSn9jINuVblzqXg991Dg
P8X5vx3oHkEl4aJFnVhBaVwubOHwr2o1iObckB09jiwDU1CkZoeEf59nBO+lh2hp
IetdTgadDG/Evdx0+PssCrtbD9MH4lVB9n74ButK7KCBsMWGOiiYZ+3tx63+DCK1
VZqeX5BDiXHcBcGdbarcsR2WPy61YE5NzRFkYiT4oqSfPRdDgPLavPLWE/r+f0Qm
9rpf80EVM/OEkXt4iuCrBdW0wnBhcl5EfXzB+ydzI5UK5DEdlYLhGMjueKq+xRKG
WaQCESvKuttrGRPxxd9MntOEFtxTjVhDS8Im0ZiNl+HmapWaH580Iw7hpuxnHgGH
PzYH0Up6AuM1fPokZyDUJkihQLc0lIJOxyq/9YIS6dPnslxB0lY2QrMYhXUzDvMq
9SxYGCzBjHNNVCGbVd9C/Juz2Ml78ziJSBTTH925RFj7drl9QpbXbZkLlc6LxJ3S
QQGnPyhXSijWmI9qoqcSe3qAgzT/ZgwaEoUIstyPGjLIP9KRi9e8vuVQ4FtEY83p
hJc/u1Cwg2sbsge42eedhy5P
=MnDe
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ae632105-bfed-4d26-a8b7-816ab587d2ca',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAnFt5kE8N23OBKY35W6NOdf1lujhq5HWq7KMEM05b/Pu4
6tpxly2r6KwZjgvmwS4VBmXgmJNRHRrcgHarGJWILRsph5lh9hdTFvoHXd5hGceP
23+au413GJPxXsUKB1bg5qtFupEsGIiWxOgiQtpCcFloC4Cetc1LhWpETGDkZ/T9
t/QHOE3cxXKBcQ0tEfJ6bdlGt8DcaobmQfUNOj1UWjeF0dX9rE2goc24BJXrn/Js
MHam0uAr3+gOosGm33fFc97Vky6vUhFst9xl8P9hTvGYezYgcXnaJAnByRSEndvJ
VC/J5jVCVuWDuxT417cjn2kzXo1F+lKC5U7R1ZcTGQjpIgtX061gR8oy714d2iYF
3RgkMyzfL+HCzp8ywSBF9U/rdDe0WpCwMVs3KdITfXAojFfio4XEvlQOC0wRXPom
H9yzxntH7aCOBV1YQmgkkiNVP/LCWkgjL58s5y9YXoVkc78q6ssE/m/FaT3d4Fg/
beCqtiCgy62uEXc9QCjH2SuLAN5i9bliz+YvXB1Z7mcaQEEZMP/4CERVYIULtFUE
1ZeePfvL0hGZ5DPsRELab8oPWJ2la4Avh30FClWS6h01czKcRTAPtxOy8yNPkuQ9
CJ//QniBqx6c6CzkTmFAjdNV4gT/xIs4NtohrqV8hRFzJz+WQ5Oka/wKi5E747vS
RQFqtm0fxlQ18J1vtR4tyM8K68as7jHo13Nl9GRQSIXBwWsedXtesKStSxHD8fyP
uxURGkNBl92c8DCWAqGbUETR1Zefmw==
=Z9/B
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af435d9b-acf9-451c-a1ba-f46c637ed62d',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/7BzWzXATYcsw5eRntoEx/2aubHgmgafxFkL9gEMMDdRXC
Kc9e+H3nm8DxZN451W+fI/3tS9oL3jWA3c8usHoMW9FNeuP8L4RxEOHXaShqxTC9
YO82cEDSior5iade92fc40zXjUf65PclNbXyZ9kDXcvkQQntS//i2UIgU3e1IHPo
uvMlURC0FFUPMoloXMKP2L/C8yi6AHl8mKY0twoHHmk/sCK/zQ84wCIHQhQPJZly
g2ucW6EyaCjBp+M99Oe8kLJvB0SuAB6kzEy5D/zuiWTw8TPHbvjrtdeXxRMjueRW
5va3tsd4xujqVaH4DdGU2ZD+6nkSQhwaEqwweOGixm8hTgOGHQakFeVj3fBA8Utt
1DIlItuyJNuP80sILsAWoThppbggIiU1fHwqhF9v9FsB1GihXaHZI87dqSsyaUTs
4jqlMSqWjyrFF7a12IQ7B0L7CeM6Xw/LTMSbLbcTLjLDp3ThbQVEoMgmOSVcHFVJ
f0pOO9iuYnM3VLgeZ9uaN3wsJASIE8eovzhF14SIVNDD//KjCVpLWkAyF5cXn05+
/j7sIQ89ECfsp+dkRlVsq2YbVOghgjthf0O5QvNn7O+B7RT0oG4uMR6rPiisxPlK
sr9Nr6g5YTbHaKinHS58pfxF9+Y9gP4c3rBbHQLSrsimWVIifmeFEz7VYlvLqFHS
QwG/7SbA7dDjr5V3x+tdS4EioKGmSNABof88Vh4QmQ1mp32h5r1STZ7XiZ8ciPq3
rZk4tN2ZNfRddqZ+T3+jaldj9js=
=G8QK
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af5bd44b-60de-4802-a2e3-2aa02d478c26',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+KB2nEwPDoHMY1sBr1IYMk8cmm0NydYmOk9xowI0HSzlE
acN1YlXOnhufHw7OmVnYiskVGvv9KMgvmNU6saG6VgDpcAR65Jd4D83FUqM66dW3
mssP8CRKDg/yKCknnhpFXyIM0L/UTI1QU6byxBLZWQT2IDpuZ+auhe2e2iA2/98K
RpZUN07y3uX4KcNweR5crkUZgtXMWmxA7CcJ1Hx4od34EG1ikKWNSf2wvBEimO6F
/qJbVHfxXAw7t3EazsQSmFTXLV7bygwBJ8bvreDZKY1ZiuDItJ6kzMIMiW5v7zzd
SDjCODuMe2LfFeuZ/eu1wum9vLoSmahVo5s4RNhcgqXx+SsnYgJi6keuTV+4cU/i
AlTD6tWhtuNRcpJCva3ltZkA8hX/oFk/QgLij0mbrLBnnvw6FLCPSdkFyx7ALLIl
/Xp58SSohQd5pxBcN/Kr/mbA+awfmgYAAeUlGDXgiQHgWcLhaGgNNX71LqnXAuiy
QwsS8KpXVbpjx7yBmKQgUw8NFktIkt59hxS2YASgk8xCAgLFmze6HVUn7qjWGCKz
AbMuGAQIuQ/VEOsWqVztxcO0e2Vr0fzmLvjioYJHonL0cGYIXCisRMU+PxGqHnVQ
ttapfG8jBS5wdq+LYIFnZStBVNHY6q63tHsfLeJ9FULhDlmbBBoZNwQ5v9XDeNXS
QAFAFirgGi9zU29xU2isAOwNEWKuOFW0KOvCmGpyOe0Co2GLvU4TTtLaZpGPDMhm
SUZY7lepwO+OZGPCqhR4qsE=
=vyoF
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af76ab31-64f5-4379-ac9d-4619d26ac482',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '141b9f2f-4dd5-3dfe-abb5-6f35d9cbafa5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAt+iILSSNrqWar9sdXS8YBsUMQna67ytCJFraDHF3eKK4
6lHU473SMF75eypr7IvSkoHLVKXegJZ4jn5ctovJoldwUnLI3QF4gRgM/+OWb/a0
8WzscN21o2xru9ES548JV/qykfsq9S8Ku9cHsO+aD7Tz6rTcX6rfTwiqWqNmfpJj
W4YQ0Y3H2tslvzCVPiTq/ZmLkMCmcDQ1AIHL6ebGQGfkfS3QuoFW1bHIqHOzA6Az
Fd5VKlEY5n7TZfgXfOw8MpjTdZCNScoGXpKkh/ket13pGj8yx+pFGj0q5DYB3TAR
dftoWtSI3fx/Y3r4Y6Qe+cTbWMkxMYosYzjcraRzlng4gAQgSUi+YCe3N1/whvBY
7SFVNE40kXLdgi1Fc+IfEj5M+TsoUujez5UfO+E6ygSei0hr4XWM84W4fCExiPvP
u8bUwbVz+BCkinC83Xyn12/UPBswuUVISKSgGq6NlFZy0ZNn8ltl/BR+9SCYCGvA
4OGY5BWI9ZN6UFcUwaDYl+/OFJM7ysmKr39AQtj0JKTuYgvq+p247Ctm+PRBO31Y
LJsj4IbN4wspJpZLz/IVkVSN/6BMx0Jquannc6mEuI8V+I+rp1iJUAqqNtozutdN
cKYUjQy2rEB17BYuzsjfgcDlcR3bzmFoxQJr8HhNEOwyn8UIYBiA8YZVECOdp3zS
TQHD4dw0p6XrMHzdy/MYU3sEk4hYxYMsacUA+Avgmt+SSvP7G0xIu880Cyy/vr/h
tNcAmUaxnCYK/PvFCjuCi7q6TndMwrSgCxE3YLbN
=JxmH
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af965f9c-c250-4200-a5cc-f2cd3fbdb6ee',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAhB/nIJftsUOwLWIYJQDcOVIakyla2/R2ZvqmuRp3eQDR
BZjOLAZPfFEsJM7adk03pGjuz9iMMmlqHE1+l4j8UDlutgOOjZXQul4PbsOdUV+p
qOTy6tHT7fD1jsGP7/wU3d9l/nVRSkfzqbMx9yfUnd9moqh0rM87aHK5EOlhE9PL
RK/IIvS3AAIW2Puj6vjcjttLi+IcclPYCyPjg/fYN8QnlIUgpmBu1e5lHF30Tlwu
XsLxSi2PxXuFaUqKwrUowA1ynadscWZ+LnczFaXVYckgUzN/gJ7ornCykRfyoFqD
c4697jOE04aQBLW9L3W3Ie9pzniyNZoCFEMBkzurZ6Sqvq0J4rmwSUQIRFAQTcFC
l9msie8ka7Tsuh919U9bZbwQoo3eQxAobXkuiAigkLgyzeaNMl+TswYWqQPU7rFk
taE7aMtam0/Yfhundhfqk/PRtquwwJbhFAUCAL/c5Wv6pucKnbONCvpjQogPUN71
jvR5V8hg6ggEBCcoQDRmRkCkVirIZax3dT0qHScE6FWzhe4dJ3gNq7bv4senZcFa
L1fDCqat5ojxLmf+HKoW54FRAiEDX4hATDbLz2mtUrdfH9xefRRZNLMNzZb5VTEn
hQp41riQEJa7spfF/VLRc/bJULS5FA7kD5+tNrP4Pr+ZzfFsMcoTL4kjpl0vMWXS
RQGCYUorR3fkb2zpeshI1vEwnmrGut1cHzm7UGr78DjJZzsEI0BVlr59AoZy2kae
OyfaVs9xJJGDsS7KTKGu5dH0y+6sbQ==
=ubju
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b06c2318-9c7d-4ede-af4c-821c39138c85',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => 'd547d43a-9f21-30f6-ae31-66f9258bbe17',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ/8CRqdIj8BqOWj351xVLTZbX40N6RyN1+OCYzLBlYQTp6F
45FKktcW/7infWnOytjspBj7yC2s7O4eYsOn+3NuthGHm1zMILxL0jBf3zJpn2mc
tvdudkC3oWz4DOT3341UijUgO8tGLRLTRmrlZgPcnC7qLp4cNPgIuig0r0i6e7M9
vkC8BWSuE2n6bMJUCDywicsNsC5aSOa6dKRZ+Y2RgKGYgq3U77Qc9vl0IG/ZNKvF
WKY3Hz3uEqK3Z1wKM+Vi11YgWw7UfMAt7pyCfJJ5a/M5GMdwMwmK4jehPmakzAJZ
WWl0crSJxP4xOqFYeLFutiQ/bHvrf74V1U5XDHHURaG3SAFGsbUyFk1puInRUbt8
7FKV2hiLnKn6tnb0a7640xQbf9s5aTtarQqm1qCHlAq9pg0ma46+ouR6ndqxldev
Vr+0SE30nY8J6t7CRJ1F68sXwM3r29/JCpWmaAFr7U2KJgIOKzzAB/MujU7e/GjF
KS21V1DI9igSBRl+FKhf4eztyX3zjAFZHcJth7qi/AYmm/ErCCRZU3N2n5Tz+ATc
0BWqpQMu8i9u2uemoqLnp20VruyshL3lgmBBcOKy7w7uOrRnflOIvV3UInO+LKv2
xmsoMmqcdBP2vi1l5Y5WHGGz/z82z5IEVzJ3y9qhwhxxuFxcri0XFO/M/C/UtBzS
PwEiW+0sDHsArN+5XIDDE1iYm4R0AP/Kv3Z8yOwXdByFIWq32bsgjbMQFsWzAFij
iC9Y5s3MJmF4dLU8PDA0ZQ==
=BUs0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:19',
			'modified' => '2017-02-17 13:07:19',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b23d68e9-d7c4-46e1-a9ba-29ddae98f312',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//eYPwNSVg25B8hGaAVmGHT4E9d/XUbTcwycfp/fTKWUHy
zCMF5T6PlLtgYJ1wu4BcwmzUknhcmS3KxAQwrwp83fxY6CFxgjLKZpDw/0OvM6O2
ScW+Y6fPsYDPltQ43n6MgLWJy/hHbjlM/YhHfzyPriZ5iMhUJUD6l1lziedShuKv
qTyzAUIdDSGVs/lUjBguXQTwspHydmSxG6ui5fIg6zWqLvtVr3vKFL9GILDSbNK5
XYLsPlkaOb4yey6I4qxGnE0UUbSYz3zWBt9ak/yXeLdWY91C4ygijm9abpM6nQMU
XMDQAp51/tXrMpyxg2Eu8uNXMFuB1sj4EFJq59b1aKiaEqute5qIkHOuh8KbXIAn
Wz1wySYqCRlHygL1KJVzW/GYNf5L91HlXQUQ1V/tPQgC9twk764ZEJfnIEJaotes
ryr2pUULpjA7V7m4JN5JEBpoD0iXIuvrMcT6dZa//H/3bQytFzr5uwlHa24aqBsk
HJ4687Bovl8eSEWzPlZtcxTfUv9lUbGyiSXOvbhlI/x5ejW9Mg1PGtA42QtHo5dJ
Dlsy7EVi/LBb2Q1Y7qPMI1o8VqNrfh5wgsSX9EMc+COaNbrMKI0UvO+Jd23IBMBD
JLqJgPk+jdkImwY1VtYYmoReuPkKS4FS1ni7S0UoejoubKL8laRjGMpciEjiJCPS
QgHTt15x4+lC7cjVa1SgKYAJWexn/UpOOc+uyT+8RUEpL9WABVbtnI/d4XJiaN5r
U+gCQ2d7+4RSRQ4tEZdd1CcNPw==
=B1iP
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b2d7a085-08f7-4f1f-ad19-bedc5ea23f78',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+LcEY2v8uSPD0hbbBTbVHptYgxxFCkpmfG14Dy/S1O4Wx
/AmcCEbWDWNrCecPHoFaXRXWvXKxqU9Rcq6sxPaPE8PY0Hz+pNGUMQYIPOvpnig4
YcC1oNA+DWCGd1oD712XiW45Q2Z00tgUPpOaWQHidkgQ0fDA858lF2qi9oj2mTNN
PeVrxWXxmNxy0iHvQWUvyBlQbb79p55TlXezrrk5DopMusZaG2PWprV8OO+uPsx/
hNoJiN2gv4N75ehHtl/ENm2iNLFhd5qSINeRwGJfYRgXHx0H9apbsKm9/87icS+l
14TOftJnFy6jb120mYfLUAiHL1o5ORfBSaOi83o8K8OMesy6WIVmHdZEhJpLmCjd
AYNXBpeyD7dyFuqIXbzEixH3UuM50vgWS5llsCV5hW57UppEthzfmWs3FF88rCE2
vb4Y7JvH50PddaG33ML6jWKhS/FVpQVdzYC/2BzRZ/VViJXaVPjz9QnHm10QcAX4
auCvKeY7Ghi8AQc6SlDwdsaa+J3ZHpBa74hCgJbLbk6lD3VAS8cvIOBU8SiSFGDU
fI2oyhalWtsb6uQQkO1aYs/fCa6L+W2RxZdlM9+q3pFKuGWil+Plu0pqHmjM/Xfj
EmpHLYdkGuDS77bcMgA/dUnx94Q/I8eHm9GbltPQQ2XEpTap02OTMACLC/bofs7S
SQHKP0hOsLdcEOXgMbEEL7QRy/aPGON6cYp5Q0Khah1T+VfHMu+kdy7LvVqppugO
LNJQKDMVUvrRTEMBIWdVamLmZhuyy/Mcp6U=
=aeFG
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b352fcae-5376-4a24-a2c7-b4ff5ff91182',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAn3BoNoqdqw+msDEG8D1Quu41kjFIOSXgYP1rQ2NU46II
la45yfhziGM9ntwhz8ReC2vZ8a4DiM14ix3QrElUIEiwNECJFC0glGcSS7e7TzrR
PjDPSQ9Of3Q8eIglLy9nNZXv4oCl+XUSdKEomZ5ueLX+RlqzN2f3W4uaLp9ScEBc
FJoFvSVVOSvcx1hNeTp/0dVdt6JvH0gPLqG5r+enpvMg/vOwJw8SUlf/8kP4t243
DY9+T7clmkhp52lUUCu7WoJQ+GAKkCK2v5A/m5SREZjstQoG2mI7edqqbTMaqpkQ
7dJ8IkH/24QWfHEe125OpH5Gd9M3NGXNUU1oBmD4wjcsiB/zhDlRNZ3bkOyNNhLZ
JNmkr4TZB9/12+qHwsEeCDIrUOvsOgE7qYQkoAeMvZQD6vH7VKkPn180odPcQ7Dj
pB5PHpVWFWPSoTAjSfZfVUxFe70LfcAbFlDe0t4jcZyq9l0SvisToVE3UznkAZaX
x86MYX50I7PvFooURL60RDOVm34yLopU8m6fMKoWksog7oU1st617US3L0UgmOG8
QdFXoavnpyXHHDA6E+nxChNPoMNbG/dTVbC71EzVdkwFEyiRAgo0F6bceUyC8O0L
LApuIa2iESUOB8Xh6a/7z8UJ8fOlhTlqoTV58aIiLd6huHEDSxD3rm7LvbgZpWnS
SQGYOf1x9zglBz8bGFMTp/pmOGU8REDv08p/dtkegTfukCc5Y81hJlR+ENdIQjpP
sOzQzJaPY3bzLUMbOZfg0rx8wBBZahCNEgI=
=XRLQ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b73aa1b4-8a5e-4938-af87-6af8003f77de',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaAQ//RN5Lkzs/lClIU5/cIPd2zvNIxeOy0fFvc1Vx59xZTMqu
251pKS9Tr9P34MHxxvkFZlmPa7KJZlTwMvdCmZhyDxTJ5nnvIowxtdTtKmQs40gZ
RYthNokfU0PhItNb4sSuPjcadP8/wzemRG3h7GWAjinWxHe21U16fnu5wZUfAniT
SoH3x/vtY6rL0aCnMzERmdQcKJ6urOCISLs+KGM2VBiTqIyHpse+C43YWPaiPIuK
4gBvJKFR5Fbdr6bdd9Bbsr3Fm6VCigFpFzkgeVUx3bc5Fn1KSEYKTeSIpu/T64I8
l/KOOxrCKtx9qiKcbhDqn7WKNdkvNIwq2KjTtgVaT6nqhgqqKYFHXH3mUucenDNK
X/utyx8XnTGx6WCU4/AS3wXNnTqu9OSKorRdKFHQxH7KU0Ixd8BpW8ZiG4zPMwRG
l68z8ChZR/dW4aWSgxniWutgcC0inFrRJU3ficBc/PzklK+YOnK7SesIBN767wAC
Ck8CW326Omn/o1syr7+p6S9O/nRVzBHb2f+ZCKc2jlMsHY2VMuJMdTAHqUaqSIYf
nwC8gy0QxJNneGzDsnmm+VvpsYfGwIUYBaFxQxuExvlbbkUTs4y/n7XZ5QgPCwdu
pqfVbSZs2DGPGhy4wzs5ckdNQWphc2nG1YRWb9osa4QGKTrd5/+jGRJdkJGchYLS
PgFYE847al4Jor5JhqEQEEDVksI1Fx3w95UCmbIXeqHN4rf0vW+D1fSwjnZfAVuJ
9gbPIJ/qiFPq3S2xwmqP
=S6o0
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b808a579-a26f-463a-a8f4-51c8e155c7a4',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => 'a93321f2-7aed-3d77-a2d1-7e058f41b7b9',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAApBBfXwlGFZWN6vaiHmAUnpq2NKEqu+Xe1gaQd8k8B45i
hPixB50hhiyUJ7A0p12nzn4nP29IlLR4IVq2dNRAkYH1DpfQTufh3s0RWjVt/GLR
Xm/XZ4i9rcr4VZH4wV2sh7mducl/hTfbvNM7QzU9kFjpl8bnm5cEke7/bNb/emEU
s1oFNdXQ+TqIZjd1VpvcPXmBREhDwXcHZXpFBnMxvTXSQWm+fzUJZOWsS7rySVdz
nFX6QuIVNY/1tBTC+YY+A/HHDdiWQ94yBTSp2jMiKKjFyjeQnvU9wazNMXRurzAt
KYt3yIil/ANacxHc2wtNhgIvE5WBGdkzc2GcjYD/N7UO2rpTTymoCUuTE62Bj7h5
D0YpcptbREapr6hoh7ArProqWY06Ua0ve42ldUp0yPEAxbinW5/jNA6sDx3V8mnu
pPMImhEgwuLTsNymILOTpd0UixTPzGDdRJaXjiA/IhTMR1CgCvd/xNRgKnDs2OrU
luThwkOyDAAf+uQ7mO3YcOqOlDPHAqqCVuKBxxmSCu4MLd7u6QRvdOfNUiW44dG3
sPAKhCLQftTuzL/7UHA1mgeJclihewX7XKK/UvhhVSYKICVP3s13UjPAjObL5GIh
W75jIXd5lw08A5mVA30f9KTrtB6a5CZM+WKHXFlPTTbDuML21SLL6ZRKJwL3IobS
QwFyuaHeNwA/vgX71yrVg89Xt0vLR9WE3Ptqx1/vuC837epjSPPshTyiTBi2/l8f
maxr546pSIMAHT/cyxA2auz3+GE=
=7Cee
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'beab2d4d-3450-43be-aa2b-bcc5d0dbae56',
			'user_id' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'resource_id' => '4241e122-62d8-340c-a607-150d8ca0c5c5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+DHMOZtonHaARAAuP+A+W+9pUaWc2MB6JbqDrAw8eW7XIwpti1tmGRssOLj
zSPGBpha7mRaqKehpkQd9Bj1CAA/zIVdptw6Mah0G+GZM4wJKXhilzENW2ZwCeWc
2mNmwkYUtwoZZ3EbUphFFEcuzy8FtZUZVdQwtP5qYJzW5/FhQkxdw+3wTMyQ/hzA
I6HoT/8WyTMxBk7j6IFrRh7KVwwBHgFMT64SX1ZC+/egeoMslxehzYR1OvbquAoy
bJMETYJ72yX69coz/EnhNKGBQE8OsX0240ihA/xNlCRx38YsoOF1t0xev9ixaYZl
yp0srlv5DwNtS1VW1ti3R9VbwpuS85hLI2W88cqjeT3IvA+R5w712MMNfiOLgOwu
LTunK9D9XaOlEA4SQtUE8IyQP+mCnfRCZ6wsehj3VFAQp7z4kUkCLYrRBuvG62Hs
GI5/Dccn7JNtSNPPxmamG2ra+31YRENcIWFpnOncYUbRIGzs+PQkrciJmt6l70Tv
Iwk/6+TTRIvThtOL5mBsh3v3PuuGrDrbq+Xu/nwMED8ZG1MqD5GSjCaohWhYGKGt
XrsG4FXx0c4Mr21a3nbF8FoVKSeZitiqRFphpgIF/8ykxbzLEbLy98UZA3DD+pyt
cV0E/T37QsC6wD6ncMDqZSO3DJuR7mr2RbGlhRruS6IRgssf/20juwmBCt+6QB7S
SQHouFcSEjFkI3JyhAvGJAkAkH9j55gpkjTL/P0Nek2UOBqP31BSSv7cXwP2dPkU
RBRkI7XWCmEegK6CuOvJ0oxkTg8u3bax5h4=
=DzRv
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'c996e4e9-e4c9-310a-a263-178b45b61b3c',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c9f20ab8-7521-447a-aa46-f8d8d6ff8746',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a9b3ae3c-9d12-39a3-afda-a4863b918989',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAjOCRsczDlVoq3I+xCYhJ1/tLVAJaZ1mOj2vGLnPs5XXg
Er1kioUe86SMi98KeigYnBhtwxfALzz2ap4omBOJJrI1oTt/nCLN82qod9kXuxGa
4mryhVPAv2L+8ID8qgtc0YTfGgpopn4TcnIMPJgdOHg/syJ7j/p/8ngOJicdUjeK
mEmDGFj5fsrOJkJSTir5F4Dmx7uTiK59ZCy6ZNWlO4e33ixBKNP8cgkuOAAJdN87
KG4ZnX35VuSWX3eclHpBYsPcKoFbWvmCgs1dTSvnbKP15eb70rmOyQT991QMr0RK
lNFEd65/F+V9VyhsvObEE9w4ufJtSZrzPpskGD+ekEjCrDMfMjw7ugMM6ZqIXetk
t8Yir2FYv6neQHtPaVyHEhfKyFZl1jPA8yh/p9m45G+VO/uM6BnJ7OJCxDiDgxuW
X0uHtelCEZjJIm0WcXJVzrjm0S9LqZAM35KnMSLvXuEaym7qZa22nuQyhUcVavZX
FwClT8yy0cfmjtoH7ej3SaHzeK2xYjVsEQ/7XjFyrPd53EVFd/TmsnwDZ8f9wAvh
BcXc7qOLWhbvjfOZNec6KfWJ8YF+Sb31xpImIWUoaweX3izLvmuIyRUIvAGhfmS8
f2+IxzREnSl3Abbk7sG9ET/Deg4LY6oabRrIaf25ebm1Rp6mvMbTYhDjzyVHAU/S
QQEcoOoK99m+mB2Ypzf2F2YZ2JXGJc4pkXu1GsQA8xlCsqLVqQojs9uhaKHrlWlZ
3yz1BOnhEZP4H12xc99VR4dT
=BOnr
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cf493e4e-6e87-491f-ae23-780b852505e8',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '31bf093f-dd27-391d-ae9d-f511ef41dd12',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//beqNmCqQhZ/7klf5i4if8bU4Vosp7I4O6q+khX8V1BRb
AD7T02Ry8PA3lEzemLQQHpzEH65iGGgHLrA5HQHU5/AOMSUiCv7yjop4BTtAMSjJ
OUrEfrT2TWFEpqQdSJv2fb+0v99X8WKYlmRFo/U17KdWiu6IOtZ1Z6cVxkE3RIpK
qxsZUOEdkNWVV01V9F5SHFv+r5g5hd7+Ub06UaLOo9elqkZRJXvc7M4pEfe2Pw+v
3vSZckLGUNeuWliMDt1vw60ajpZS0eBn7+ya+NfpQFjzhIr5CuMaFouYoMbJJewZ
heCiYfhZKej/jhIpiZz5TKIsfnjiG75tyjtjBZ7cIv/v9cRVD9MuT3DhwBglOfnK
5Jdr5kx0hiL4V/3xGUqr30Fk1ujKPEi2YwVaf/WNM0mRs+Z1Gv2I74m1I/bAK4ib
PFvqx1QWGOBbB2Us9q0QHku10dP1fYB+1Lh2NC7pA154GhCiOXqTA+y82CBXcAty
PWINvn+8UlqBI2A0Fm5ZXDW4fNl1VLcXfoYmjqb9u/OUZzHoTmwogOvitlvbQlEu
KczN6iwJ6LX/SDLe0Yk/iN6Wq6oMo8FB5YgAmcxDoLj+/uvSZhU1wvB8donohb7L
MVFgxWpAQmTHkggTJ+XHdECkF80DV6nmeYiwFAJJoY1dZwskTdQflm+L0y09XL7S
PgHD33UXIM+0CcmDuSV53SkZ5XgY4GJJlD5GK2OIDYO8+GMaBuiwzpf7jt00XZxE
PNCoxSniBpFA4/D/woqS
=mfN2
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd095073f-9dc7-4be4-a33d-ecd77ba4f479',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '664735b2-4be7-36d9-a9f8-08d42998faf8',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//fT/vT2WLT99FDM3/0HHMjQ9npadm8HPRHgKgU7sQBiuo
YlTEIXBHEsQHws0oPQ9IoHKg91U4WTP8rjENnEEfPVfSh2ifzA+oonH0M53Y4VCz
HtC46/Uw45rRgakiEjxCObsdwlecFYpPpXqt9Im8lcVyrlyzmfb/5UiBegXBuAqL
+OF/q6PIh7YoytpyGwtUdryLNdzbbO9SgtAxUuTfKz9jy2BI2Otn0Xtikf0AVcO8
2O8iCJG7+ZG7gN9qKdbt8cznj80pv1bMFao0a0bsF9qAAQaLdxAuOhW2NaUvlUcu
klAJBrpmPS6ehABvuum/KJ+MeMdc6WDEacwD3cPKYcYSM8GiM+Q25Gd5/1Ij40M4
ky9+zYDgHmP1SdIj5qsHiUlF4NxwZKk8ADq4RSaG+Vne+gMJx+lcuJfcaWp3oHYO
eBeSAfZC2q68EIZpGRkPeB/tSjhQyQV/WQ+MlDpZgo9m5taSDJeI0WiN1hEIXo5d
uUyf2NdxEi10CChaxhiZ6f141YwUhFG6uRkajfOD0At+m3M59E5wY3MXPjMGMuW9
pxxGq9V8u31FbRdZyhkePg5suFViGSaUFbkXKmbt42xXZKOXj+LJ4X+MIyS3h6kH
UR2lPG/myPa4ejUemY1myETNmhO6VTKQG8EdADyXFTZdW6wYnOQjibgy+iKUCvbS
QQH6mj5JJu+eGb7rqWG4YD6suIMem7XYzhu5jkYoVMpLhcHo/xyAEtYT7d4sal4P
lwl9U8BWuklmq3iFRKKaNIVK
=kBOo
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd12758be-a256-45ed-a20a-cdf4ead958c0',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Np/Ps2ZRbCyICECVhkRj/joI2cleEIcuYQwoj2/rXtXO
53tiEnKgDFcu26D2J3qXBYY9PWxkSKgEguSy6B5hsFT9uO7RWfpmlZxH37o23nos
6sQ6maNWBZ6N7MNZNmimTgcjpuL9zcSw3u2uXvWBawKhTIpjQhT8PTYAdoLlgus7
8VRVL3+W7oqx7kDpcpJLTee22HWXJvc8ogKJjhqWOUfTGUkmP+OB1GmAAUfY0GSn
wfqEpuNfqkklSaJw5hBMkVdp2KCzquFNo1zKWbwy/SGjI1u3nt6TxX4xxup3/5ff
Y4RNwhQhQwfoa5tOxNtXaqFwMFfjbyBKeJaP4pd1zJnlG2vpf7lVJPnT/oWwARRX
AREifPXzqHomgeyAuow6sGSABxePw4qrwIO8oMItgFyAY/XoEOgGKzUb850qWc6r
HATd80k1huP9Rr2EGWGdATTIZX3AIG4qa5skI/RBQ7r0JJY7L/aVrTskEz0hd6oT
3cVR75WWNFYfOEpo70dFzp2i2iKRGnX11+bFeD/ADQnRNtbgr7gku2m9JFatpta5
dUXLKjJQI4UQVE3UBhd9ZPYSnJEd4BzK0lfGtwLohHXLWrqRIaGkAk3PcJNzAYOT
2bbBKzcWY0QQTXvc3QWoleeLqpdWV06spdpVvrGl4ugVoPf5BeUiib5cGBq2oSDS
PgFEhyohCARCJetA9vqTsCGCeyFs86ojUxedhTOlhjZOZVlhiTXqjyMxonSpGh9W
YJqy4Ho4It6LlafWArIa
=830k
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:19',
			'modified' => '2017-02-17 13:07:19',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd578fc75-1597-4044-a1f2-49b25da4b88d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'e5b4e2bd-29fd-3dc8-aca0-401e55a343ae',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//ZdwKiWaMbiL3MJUHMd9yc6HDt0yWT4KEuyLE//xvNuwR
2FEWK7abjcouf81zbxj3GrQPLxOa8afZM/xxyWQxtwdG2e6YlIIE0MZGHbdnZ6Sl
xbIHCYvlcANP3TECoa5VUmfjck2wLhXJYlBcSNqM/NN03/zi2d058vkfTHsjyNJG
XWIDz56E3mSYf4MqwJDdIA3CtP9cFxpDTvTjTEFzD1H+eJdzqZYHdJ39GSSuz6Cl
UR7+K5cDVYJuLRwOGwb6Vuylrb5oDaYcL5t8ux9Ioc+rVQuDhi7UJ8reSo/A7Eql
YvRuvYT8X2fzN7nhjjIll1fOVKT2dqtAYnh6DVy2pU1KcQRm0gpZZYliq3SuWT1B
Qc+DbrAaeoyO/E8wxaOrDK96rsAoT357QafbUmFCZl3HpwArqqTEtQo19/hQcgkH
gRvi0bA5r/Q8rSRzQBFk0Kx13fsrQztgC+WMpkEcRax7lqZaizgZ/AJJ6XMyzoMP
jEfQfNqEr36JUKQ5t+35LN9zLZINK/xyTQx4Cz9EIKhDkCcexTaNJi2Yu8jufv//
Lc1MW3/XU2AQjk2fKAhl4eIgzBWiAai0Pv6kddCL5Tm2CG0hUdX57oyoAtwCrO7F
UVyCw1s8UfeQQDtDNxxFquXlP9AA6afgrLzkpkhGDH5vmrokzn453tDGpcjAimfS
PgHQ2zMkFUqpKQQuGrx1cXTIJi66Vye1rSKatUuTtv9U4LhZ3pkNfTvqA6wCYrMU
fX3LVmmp0sH1oNqL8GjY
=sUtJ
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:19',
			'modified' => '2017-02-17 13:07:19',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd63d9490-f9b6-4a9c-a83a-1696aebd5eec',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '2af40344-b330-30a8-ac26-64b2776f07e0',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9EjLvQBMkw0tz7WggvCE45q/FvCLXusqXvx91S5v5dRqa
rJuT1KOzt9Fy+n7kznpuFfIQiCdpFqENlQ0TJK58SbvHrQFR8Ghyv6SbJ668iiz4
VBTOgzVRY0mHiTBbqADxZMvkyvkPbMZGDk3kqyL/dXBI9h21MZoQhTUVZt2k8LiS
3caC7wAp5qYm6i7CdLWONZ9Q4U56pQzCTtmXPah+L9gpS4UHJ5IJ/c4h2tP1dxYQ
/+8UZyfZ7eHbIoVtCXIbzEm5dBXwVK6dRL/xgINZxWBZhyCg9uf2sKdZ8jkwKSfz
Toh1RfjzVo372mAKrvPsfG3HZKEoFVWK2TMsjSf2/RIv09vEbrqAkZLIMGUt16b7
hB/W6p1kt3x0N9fEFJZ18r+1kxGA4t2inqLz4AkhtsmTFAN7j0PvtKsFLn/kUu7t
ef8NUv/qv0ShW8lxTfQoesr+6Kxx+Hi7M3TTagqd8rVF/m3oMjbBxpZCBoZozyvt
qGoWpFfRPOkHNgSLj/SjmB5R0cafQLNk01X80tAeEJJg7CYbGqUpeTFYF3B8jyv1
0XgcH18zipO8tdZSx9xtNCdPq//0ikrbyvJ72YU5zUiOaWVvfij1T/lQEME/gE8o
6jGBNCH62cUfkU/pVsfaVx94+VCZQtCtcf9J4PLqBEAJoxFptzHMjJc//N847MTS
QQGjy14w0ieKJWD6rVBNXwhQoWiQrz8m3givCNYAX6nT4mUfK7zi+aGIkIeRswrA
ArOrVV1sABZIsTRr+gmHmtLi
=5jmu
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'df233d5d-672b-433f-a52e-3bdc1f59d5ef',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '17c66127-0c5e-3510-a497-2e6a105109db',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAtqeNKdb9W4kTPikI4Hd2C/4VLR2AiiaEzaRr8XZg5Tnr
4t2ggzf7D7vu9uyztMrYobw6Aip9TqFIzkgM2fWZVbYdpW9rRWZe+RB5idgSk2zi
auSnWUEKXWSUxyTi0nzCLECN37XJ2tepdMpO4bplNZa8wynaSmjze6JK3IuPUiLN
SymVB61z4SHC46xZYTnMqQ3ApVDGUZ2Eic4KSrZTKypj6KIaw9Y7KejHC1n6/ch3
RqqQETXurCYoV5ubyaKi6bL6VzjGHGbHp3bQhMDLA3McH8TAYFi08hnoHX2DQYc7
x8AyDamjjH3Re20xZqke06I1iWBhYuvV8mABHI2bVb2VGl59UQn4W4gorYU0v0Lb
jWqHunY1xIUGtQBeDfopLtWSNFKQAjz2MUC+IzLotlsfZjTOz/aVFBcBdNjZhixU
C2GT93WRFfkmkwDKj7F/97tm/z9WA4ZvIQkutLI9jbFyA6qCRPmMA3ANFIKnyd/6
L1Yk28WVVKRyn8sxuckKGbV65k0iV+tq5k4UubMh6hEqvpx5M1bIkU4vU6UkUEaZ
7UFyXUYH2ar9LRxNgkb9pVvfuy/E6FN2lwj9jSmJx00WUhjdGPaAT6Fog5/IzuwK
1nO1zf+CCDkXLKxUyK7dSRrNX9Fp5PwTInLCY6vCT+KMbcllbxvJJz9yfaDNpoTS
QQEsoq7FKY2Yi+r22VqBtLr5QZ/3T1M8DSkHbUILfBxa5uIwad1SKihHtWZdNMC7
4n5WMC4Xq78CO1ZS8YXanYeL
=9v8b
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:16',
			'modified' => '2017-02-17 13:07:16',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e148a1e9-7c90-48e4-ac2a-4964200ceb50',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '60b6f2c1-6fa4-3c24-afd6-f06b5ab7a0f2',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//WHyoVQeMIMHfj6X5/dV8EsIiOYom2I4KKwH5TcS+kRr6
EW/2MMbKKW1ShXCA8MhKEJqU5fh+4KiX+4n0ohaCorTm11Anu92uza7dB0116CzP
5c1i7klzHdxEJQihSOD5za7nfPUxZiQmSeC+vEIWUWkxE4XIi/MEmD6xrZZUSk2R
5QJ0qb8hDV+X8PZscl+xhtTUKBgEsFnW/eyxRugceUfaycpA06QHOiMrBraYZwsn
flxLh4RvpRZzRHlx80OYEDbzSz9Zzxayujm8GRqfdGivKdNN5pOQfHhSlVfDA0Ri
ZL+frijPdSUxyvj4x6l/yW+d/7J0MNtThApUCWyNX/hoUaefEBChl/4oss9jwLaI
WlL9f5xUyQOfxxSLp1b0jObqYqWtS31QJdIeEzjsEKrfVvqn54wjwNWENQUdCWwY
djzXWrwF6QPbrKAWrMB2nF+tEgbvtZk+YARP7884bpqs7JhuGjsVr4my+uA6I9Tv
VG/+bly+4mRggJQwQCs66OtcHG2a13yA4tpunbwPSb75SpcGI+9QUA3B9SOj63ie
3f+sU/FuBz6ht8LsWI6JOeTlm+7oy0kPS4vM0GDBMjSiCdki38zs25nVCLFYZoEi
HSpE6kfh0IUn9rNPy5CMfEeiwIJxFFc6JaerbJMNhjTnNfT7BwmhXDiXNRc06KXS
QgENrms7yyRZf/f7npGrHjUkH16ApuJpTe6CoA7zNBdlNFIuWJhx1nLjRhVuUz+q
4xhh4X0J0Higivuc+vhjJCGE9g==
=coPb
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e25bba67-e3e6-4be6-a4b5-764a71b2cfb5',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '8010a5ef-1e57-3981-abd1-98521c2622d6',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+KezssFHuhCWyW6USBrfQyqsqKbNSTaDgu11C96NpOwv7
7b+RiwoUoDIlH5KsHuYFevIRSpqW1++XN1Jfz81Ja3AnkQW03tDSIuO6bnoXhbVA
m4OEI1LGYTP+34sF1UM8JuJ4bRGEZu7CaubrG9/ovCKrx9bmRh/2XyAkcbbvnyVR
0Za9ZrEMtzYC8cUU03d+tV3YXOxvcOxn5zd9XA4Br6A1skDBUCAts4kCdtsheDtw
oszl/TidiO8sjyj8c/gI9+R9yunVAXXC9gdc6oUksZVI/pwPx+ktM+DJ3py68Ahd
vzdD7nHDKCWcGdLIuB2gN8BV6CyY57KgpAu9a2qwVN3R2woXifbFeRC0Rjs2mkfr
ywIAhah2STeXInhlUKUvkq25mH8SvLuLnGa61h+jjE5S5A8KQhaWatbwnQyczLha
TX8R+a/EO1DrOOovOB572VH49RaWu69mNf1qKzynyV7zVwYr1IagTrJSytv4cDNc
CJZKlHH/tnOiBNGs/INqFmYBYMqCA7HFokrLxMLBYioz6iMk0wfgZjcPg8L+Yh6L
FlMOucc6qrUMvwpijiGxO68rpeRoFUWWsIl7ay43shcgmBzJtkagzuvKCZ+PQrZd
Q2lICmUAjbxfNnIc+YSeHbi6DsxfeR0gyBoWOz7W5xJ4/RPIpX7lsxZ3u2D9i1HS
QAFTJQ5PCmtPqHjuECuw0QR9kDmjW91+52oIb8Q1AvfqsroFSTziX+3Di5Xf7zXc
wYd6XdbkUZKaGrysKDepOsI=
=ynP8
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e273d3e2-9c9f-4ab5-ac04-be39d8a8c91f',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '524b4f8c-b842-38d6-a542-6bf714df6099',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAmoyoS7BUBqzsq9kpv8jUy5rXY7pNzTFxEdU7eoJMBcRo
f0ftreuGixLaZteXHN81n+03vRucvfeTfVUSHeR8tgwGCKpYqyVOmPCNcSqvv2si
sxOO2KQveJl5Cg77/iPUAHZzOi4EoXRyNXIcJ/TCejSF5KvkCKUxg/wyLzoZHyEV
YbAA9eOpxjHaX32Mo4j28essPOJXPgwvwEtKcbSv0eEWrG6zG0BayLvM3nAIFyBf
mN5ZP/mO/VfcvIjEmHofFBcGkBHJgMweLrDirMLcuGucX1oKnsTv75r6RsZvS/Ll
zqib/IfbmqIyPY7AZcATyuzSkAQDTJ58EpSf2GnYf6Y6YpAmfpUdzshVUGJiV7DA
sNldpxkbwVWtgrAbfwBaYHrMPoSmrHoJUlExh54Pz65WlSlEun1akhvyQ33Ymj9O
irlw+ZCddUIZbrJyp5/3z2q9G1C+n/zzomcxoNuiBvVWvZUOor4RqWMhwxyx0xfi
++y6tHmCxDhg/VW+4kXqwfXCZ2zjXBAEBLwdlqvKjADukgG+hI7Yr6LW1o8O3Ey/
aWix+y0Iq9X0AYNIjlNXknbVvozCfitt8u/ODMgRMb/OsuDIOJpaFaal4T7y1fW7
hzmO+NCQ/B46FcO15wBNqXTdMpnsZESumF5uE1owLb+RJvYm1FRPT43K3BcO/93S
QAHxW7NdttKCq+j7f165oadZHfYHEn9BmiSlVnAT3XsmX+tX8yRavbChiuSnW03+
Z+gAoJwcIoabFSFTnuOef/Y=
=bEuM
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fe9828c8-913c-46bc-a725-e3ddc671758f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '45efd622-6d25-3144-af33-43b8480b166a',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//d4Dk4k8wcPG//5l8oWefrGEK19cs0/AR5O7UC4+RvL9f
EgzzYoSalXc6rNerZ9C54WxNEMbQL7aAzZffmgWBrCgszwduHdiUsgoBRg9SRg0G
oKflmf7qdzJskYK9lWTjJY4m/ESIxQsYcndMlBsLH9+ftEvj2HkIvJtTGE2yf5wx
MqOvJnko4nwfYEKuIM2vbXGt2l3o5aK1kqBirACAJ49P3cHRDqsecP0JksoK3eTB
aSvYfMcAgoFvjMoAGy8xbg37YxPncUJjSYj4U5Eec+rESgWBXrz6dFq2h+aP5oWx
POlKDFkOpK9TltXrrfJcl8f8sOGyl2HnEbfkvhKxpLD/+1UBMdV5XdGzRPKck9yK
lj/oiHB8XkJ3yLIh3gmqbv79gJFSz0T2DdnUUkFEi1n0GqmIZN1C3mXa/yJif/64
+9Smk5XZgxQErdQIU5SE8PqtnY+599rNhjflJuW9/AlPvC1P8OqDOLHCQYJKViMD
G2foj92RrZP8TX+TaDxKyAuaeK8PC4UNy1SN0k3gp9Jd877TlWUe9aw8eaj+raSq
Tnin0gITk+ui+IELIcjtexJBu86Wc0p6qzgtjKl1WNsztX12xh0lxeHPAqQDWi7A
ke3hcrlYKwqff54MRPOn9+WfLxxK5L9HZ8/e/jMXJbk+v9/7iftPDJtAjUOxT0LS
QwEG8AKnplTkOcBdTdlgPIHxYW+MKGvRrUbrsZFtL9F2VK/to9qygylwEevThSzT
EYei8xw6JGpFADaNdqz8CKOCRA4=
=EtIa
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:17',
			'modified' => '2017-02-17 13:07:17',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fff13952-1ff0-4f9c-a064-838f157f9244',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b84103c2-3b50-3250-a5bb-9fb2272c3cca',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+IVVg+QmkftN0ae5T8NKFbcBmux7UM952Syhi0FCJtXdH
//khrzVGT+mqi/doXH5R0iLuo0mRT0NsDBvFEjs49t41loJfRi+bEc5m1n+u/a9Q
0sKVq8M4tn/vpb9118EVXd17+iSBDMzWnz8oPV3JvqJgmIRRcD7gA8IfM3j3gRrR
Jqcxf7ZefzBOUIiuq0YUYg0JVfT/6DYtH+XS452B8LIBmsR5ydPqUgmW2xi6O+UB
bEX5cRBCZXTA3ZFuEt9dq0SkGEq+/BVjcYQsURZXF0yFpdv1/0uGMjsH6ApzOf0d
WVjjlVzac4TDLD4I/IhHU0o1XZWn/VaZcjmUPbszQDdVge7YRXtNXY0X2nYtKS6o
BHczdXdjnPnEzY7uIanjCFT0eRZ38KG0eWcA1f0ejL8UjZ3ZM+02CIjarkxkYZ6/
gbGleyF64aqunGiRQVoDfitCeMrEI3mOekSox09vP/gx6ZqtqUar1Zfkqp6enSeA
8BUc36aDyha0ETyBYYAjPt4sXn8m4O+4JTomYmy69UzWNZ6/7Zqjl4oeHO5Cxlsz
7EzXqd6rWgfZ0xxflswO6WjaaJQDwrbbrKhx9GQZ5LVLual3HhcOo5KgjpEPCGkq
i6u5K5qtqxl56jsnZRLX3ME661eqJ0KVTo8XRpg+P/iRyzo7Y4sPq2QKSCazRdfS
RQH98sNP4ZHFhH2FCmt+9VU96azI+heXk61LdaenQiUo84cCbm3NVPDDGx5fOPrJ
xIlMjC6bKQezZpS7BYmo6DFwJPvJvw==
=S/et
-----END PGP MESSAGE-----
',
			'created' => '2017-02-17 13:07:18',
			'modified' => '2017-02-17 13:07:18',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
