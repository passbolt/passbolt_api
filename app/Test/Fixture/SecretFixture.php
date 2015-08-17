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
			'id' => '55d1c997-0690-42dc-9f9c-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA4k7nq3X/oNreLPigWkoqCIRF/ZTv0lXL4Nzq40FGTnN8
fJBd93+MI+u82PKMo/iUeikgS3riJBPBbTkmNZOJFO1wgVL62bWOE+HsCAL9K2DZ
xQqP27fIZIOiCfW1Ojq/yEBLWkbKfQpJksa5WWnC00Rie8e/WHaQUu0uM9YcuRTI
frplsHmOfOlKjfshzyGGg1iU/zMlYrznE5+xAhC8AlNs354WGLBW5NhXDmSgYSy1
Hj1nMWXTXsUlCPsieAMltql1l7P9BaC65Ha0NBc6a1Sy2gQS/s/dr8k+pXlUlRdr
Tuojj4wMDYia7aSYKsNbye7h3thD7obekuHW7JX1YA6WrdXnvTKLyCD2SDCW9lDq
gBao/hg0Ib/u4TswCJHa87j4KuunDcrDHI3Pwpc9rXwX2lruEDLD9uHPbhl0qwfO
LhqyC4IS/n446UF28SsoG0Xc5+BmE8LpNnsL2PJ2aw4DNuUGK1qPnfyYpjmjKfse
4752nZBNnGooUtSQ3cC+w8Lp4Q/2VfVcGMFmYGl1orElbQdgj0Qx8AB8/8d9fVxl
rODvtcnKKR8ESgGzyqf5x3pujoKGhQeMLfYBePzvOjPY++q5w9bI1pl7uu892Y8r
otgoFKoLW2auO5YXZ4v0l3sc2qid+kn3l8N5neYODxbNsHZxq8GNYwvrX/ipVzDS
QQGjdLD+S09xdfxc9vVSyzqgyIZTJyAE0VNl31qEBfvbH6sQgOdmDytnoV3eE/P/
ySJricNmcUqTcDzktdh6Iw5/
=hBOL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c997-8378-4f6c-a540-4741c0a80111',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '408bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//XVZWO1l0KyBBf7xl8F2PiGcUJJu7pEf0mC6W2R0AjzQo
whqPEeiKCtVmxoE+UsWH3lHbJVVpcqEVuIrV61pOu8ziDQc5XXQ62yIqnqE4TnlZ
dC78nFKTvpHTbPRge86ioS9a0JKJlFTsQn68/21mn/L0lqsCoHTLwwQ/4hz+jkmZ
zgFa+gKUYq4BRwcc84WZCUjnXcaGnxOJ4m/DCZUHO6bZD1PIuiFRdw8zKEEmmCrp
doFbN+B7Db01qzokQCRSQ5b6LyvpKYcxIBg0WQOmuHLqSk4OBGE9rqy4MnRQRaB5
LCSNjw2qV6Ww0C20uhPm2G5AYEvn1ijFAQ3HfNO7/tLw4IXcE4TyFS+ADID2tCw4
H1dMUNEZ2FbN1wEoBo8FcsiR6PeGbsEydeSEXCTBbQuPz8QG44WQwkg/DfL7uNEq
FmQc+wDX4SsYB6mdt2fy7jqkmot3xLDxYpzL3B7sFbWZvKzDKvsuMHbA1XUSNE7u
Y1PfW3KltEueS8Kul32LyC5JFjOQrGzqNV6q1gXX724WeTGObBoWpFiHIafvmnWw
ItktymZX/GHBLQbnzDCJygzEh6QyN+eC1Io2574hhEX+CqMQTnyRmi71mY2UWM5Y
n7+Q2i0LQEny7BO7Fwia0PVGMmxMlm4Ip0iXKHxfjXhjQ3JDVagOWcjruVoxDwDS
QQErp8fnm1ZmC31FkRH6U+lE8rvcStAM/lMsgG8WVslJVArchBWm/jZsclZ1xhob
600JOvGFvabzyzQkVt6e7uBp
=oBKb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c997-958c-4065-b367-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAkStNx+xHb6J0u0RMnrDjgKDYwGR3iv1i89FaamjEE1gJ
XonWWIgdKFHhI0pxTttPPYEHuGi4R2OB0T2DLc4zOYmFqjSvE8NDGTUEwbnzhW6e
AD+qz+lvmz189rEMeH91p8bZA8+8xFZ/0lWBtfwQfBO8zRTs8DkseqE+kfqV20J3
AC3eMv1WOTii83DtgvQY+X+ueHyMVlsU6c74IFNvJExf7JJWYciSTE3hXjBprH5V
0AiKnIW1wCDrxuOH9DC1+rUjKYk8U2vDV+embNTgyqptq8DkNdji7xpaD+6MtUUq
4S45yBmeIm4d9zTkxfrEUdWozFdRUxrjXePLzykzvNlAdyR2aR1qJ32ZOb2AFi7H
QUfEjPHuHazfjh8HVjMHfG4UHE4w7fihPIVxAGv0busBy0P5CF5Tb7AdvrCFIECW
llMPzUoV22ePo1R1HT56ZmujP57GEhGjs+7ps99meVCn7LGWQ/xsHF9SJkfLMLAL
6ef02zPizMSmdNOiUnseUt3OAsP6ZPFrc1RKwdo9ZvT1w2xRkKcTA8uOSfvaEYbm
Nkx2mOwC1CMAC9l7AknclO3leX5vIhOQ092iJWGFKUo4O3CyAh0lUiPIIQRVVfwg
62XeIXbaPi506KZ3oSSJ6dvqDOmjES8ZZbRy9IMxvQcQowShg0zccpt4U3FqTEHS
QQHZiKtHiB67W/YMrskoZKQ4+mn/R3+F2aDDBW1aG9WO13LvKb5YNeeXuXByEY0j
Y1KEsXxcVJ7nq6tf97Jhdk7l
=Bxm5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c997-d8f8-4d8f-a0c2-4741c0a80111',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//T3KVN1BoF5c9iOBfgqDbdJ2EQEDpxnB98CJZ5Iqt1NCH
MFNn4oCU2XFWVAeLM1L/W01XeBQAyHxlnqa4p7puo6oqCgrXPGJxjYIeO5usDPGb
+1i0J5wvu9H57Wd6MNlhuf85eSIvbg9i4KJWEyYTsH5/0t9hVQVqkZu5RQR0vvWr
/P5tdo/bJvzZfr2SEbEl28oarFppCBq5INwxQEY7/CmgQtzXZadA0oSN14XFCmnr
tkwtQ26gyEvQsP73p3zcv9i2omxLsJsyOuOpASYpUWW4N+p+zn9ltYrBfqmLK2v5
/9qc6LEmZyFpoy69xRisgtSX7OEXlkfUwYg7f8roBKfFapUXKMFg5CH7ameL4f8c
FirAqdTSxqfw0U5aAYojm1359QukAib6glxpKOH/xCRWez0UB/l1IhcM6LLd/Bf+
mBi8XHXYSs5GyU7K78I8lWamMPsgCTD6cvKpJAO2/HyMQa4n4gnpu08XBQDxm0Hi
RNL74Hw9c/htSuQN+8zg6l/rF7GEtHmwALvIsnJJ7oKKbB6a+d0HmvOR/vU27C55
neqhnwc9eS/03SAbvroz1jQpeOStt0FYo+gxhVqT5NzWmBxg4PNrswMHfHGL42Zd
aTiocQ65E37HNiZsqf7isrscb9E67NNVwZtVaVVD+RPnqAfJgiUwgeXBz4Gxjp/S
PgFqgqa4mixXCB21ka+u1VQ1QCaWECY8me4uN14jsYg0INQE5/SU4kYV4QCOjrE4
ye6mjeRsckiinku/rbEh
=5dJ+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-0430-4f6e-ab0a-4741c0a80111',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//c5yLkQpjdnR4LBLR11/5ySS6DxpObrcbgzxdviCZWvV9
GVaO9nFIznFy7GhjkH+u1SqGy+iylG8qKqo+AOnsfsBdOtfvKkwE7aE5hbCUmEWS
MD1VHU4aSjS28yjwihmKY2VYhNcOXr1k/4m9wAUL2Ns00UWUqTAn4ipzFIY4ksV3
DOT5TDELXre784pG0eqHrmUs9M11c5DImFCUAv7fCob/U57G1CYJchXQVm1sQE1E
+ebzKNrGZeRi6j9j3M8BMv/JVoi1ghD5+aa6BurJrbueZvrsTaAhOWGPFq4LFtuV
AXj8nlzLlwCYnohT7FhbNJe6/Vf2N6BMvx1SFfxHUnYbeW2Ny4c14+Kdj0ZYmdI0
LXFcQ0B09GpJgRCp/xH8FFamR5eXFOniyarnn5kBntq3k6KVA74adXVvMKBqWqUc
CMODSaTkYEAMMsQvXsJQHMKnBX0MUkYVXLKNDsSHCbgGFcyHpASH/wLZPYDK+Tl5
5oxZfijq2THCPhYcM3RauF1YUvPmU2svWpuaCVt2jcrAlZVjWSOPgi3tmPcu2y3K
kBb3Ltg++AWwpCUfhp1St3yccf3R0WjQ+CIf3Q4EPOwu/T7QzvXr/76L8s0NFXE1
lGakxCN+fmFK3R0l9RqJz8OOeui7JXKBiq5ZT1lBu1fVQ4gBgmLaGatPO7M0QIPS
RAHIQgqiU6eGSN41AwuzUOqPPXperzl5flYkLUlXuvGZLya2NQo2kveGJjaHix4q
kS7PYrzbgag+zZt1mgxrZ7qEsmAk
=FskC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-0c88-48d7-ba65-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//f6DXEuVrBGyaA7WdGpbdr93VNuGCiBFQVaiVLBLeTMxJ
mteFvxXEaph4qwlfcdLBHnfuaZz64fptYcbRnJR+jCupzDoHmIiB/Prg7pN5xWXh
fKyKwnLJS2fWNlshu4GG3nubW2Yh90HgDhXziBOTzpwyhkHpKBO4NbY3RqrGC2at
22//vwlgaubYgjxi63E88gR5PINR0mGCZGY74j8ciUkEkXQYkb66tFmfxxXuR1qx
R0gIO0U2qJLLP3qc50Lpokn9kqJ380Xr3mn4v/U9mInwrgICLdj7GgGhYTyFDHNu
uPe7E23nr5/ZgrL/vTVdLr2k6CqlmzhEsW6brKte9JKMo5vp35ReSUPv32sVQbEv
KVKjxqJbg3pheDR4H0b6lJPTKVHzAYtiZlEyuuvrkYOdQXVxT6fOeP6sUt6mCosK
6uz0WExxKaQ3HxB8LvB20OKp4doE1zsdrx2AHM6itW/h3ElZ6+Jp4jTQNSumogg4
BQeqtoLBs4Nfxpu6CIHj4xamVJZsPOrmw0DGL/HVw5wjYQH6mVDD0pECZwvbeb49
B+wW98lSkh/8NZsvco6PzMfvSdmpOqQQP+pJvM4swkTnMf46UpkVErkzj1qU0lPl
dQHfZJT8Mw5LcJmeChFKw2XPXqbil6iDe7pEJjBSUAQ3BYe8zDAW0ekas17178/S
QgEA/kXc23o2ngck+y3/lyZuZSLUzVl+go+TTYL7erIsMnKjmlESiHiCTM+VahL9
N5EYySKBpfb0UCi/D2jC3Mm/0A==
=T73J
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-122c-435d-a22f-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//TfV2/egWiVg9pavx93pvILds6e8kQdrPvZiBZaOtPn56
EkNUh94DtdMxcPE4vbojfwy4NcAT1gnFdxnft+uz+zydq7WFqjsU5NUbygHMGWhN
8+AUa9ZOXFmjuqgqOlr0lCRfLgs/4LM6oN3kObWflE9Akc12nfh0/VLiQGbUlMdF
9ZDqQo3fma0EigFNqj42DZtVHf9pkSEMh9V69jFgIanP27y4OUvRqgag4ti/A9ie
WCNh3V1WQogL6MQvQnuS5vgTeaLtNVng2bfIAtWcaEdVMLgUZ7M8Z40Vy5hCgh3P
s6a+QP5AyWw+4vX+X5dU5jKDW3PzbC3SgiucGJtMc7i5X/CY4ojbd/Mtx4xKyC8x
O2ezjVPCsuIC9rkj4aVYJysOeUHhFbMotYr0GRroNLAORLYJWhXtbEnExxDr9kRz
To8Nw7yhBY9dtj9jsrzjmSA1D5rIbS8vR2mvQYtYHQDkE54u/934R07m+eRLJESi
YIBDQVqQcB2RxdLSo0nvz49UQfN71JZYy1m53bYnDaxe6yJb6gwKig5yxsQqY7fp
7j/yk1esBW2aLjGvZwPMsPEo12L4c5G9lgwGOQHuXAM8qVPZg8SDTth2G8fh7wHg
t0JVA9ffistHV5jhmJvrtbhe3UUxZ933halQ3ALngvfbbxZZ7F3bu+PkpdoKlAPS
QQHSO943cTV3x7xIqu6hrkYdsCVGF8uFLwNLy5Gxm+yVgk7cbo8os2bUWEzKiVAJ
mgL8FydyXqdKg819IQAgwZaN
=8WgN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-2aa4-4c25-bb01-4741c0a80111',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf/QQKY95WRoMswBG2ZKQ729yi54RUlzzk62P0573JR7GPl
jiAla2NfuGx3wU3pRA/XILIzfHcEvpKHy+aYZlLStAK1ylVhJR1nZg6fJnBZz+d6
AOxLLgHJcunF5ojlyIuYKmC7Un7Kid8IqaQe+lfGMW6+Yfyu0EVxWI5sjAQOTER9
QAJIQNh/zYJgUJhmrPNVz/cHHMJ57Rkvw+FmSB7Lb3woaZ4YEUv+Mz6Hgk/9qp17
RRVPqVk/eV4ck7PGUXjTGGdVt76bdL+tWqZF+RmUhUiwupz67BWWIdC0xlIklz6Z
D2O1RPBGziiPiBBclvHKgfZZvVDwwBV+q3TY7mQxx9JCAYl94rj1ixqhgVkTPCrg
IivlD3Fzt8wTiVttj5JeRbi8IlLUmJ0aaZL40ln+1i5bkxmh7z5er4kC4shgiHTM
Qql9
=wWGy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-3408-495c-9481-4741c0a80111',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAqnWN7VgbDiRrJde7eRi2mK9Y0ixprJllavb0yfZ77AIo
oKS62S9yckzjtAvhxCNawidp03l+gg4BsYeh/v5qYC42WtgecB1vrKWSmJIIvKL9
VsjSliDYpRldeEF4wuE/Iqwwi4Fz4w48iAjb66QVnP5sFxkUdjRnPo7QkZfGwrqM
eOD+2hwSbflulMOj4fPPb0VVbZMCbGQtnsuLbYfAXmDkVxRi4yu/ltMSbwIg6Fq3
HB3eArhXOTfKwbEXLiM7Jcap254xhG2ewDNlCO/QlXwJkyW/xYd6K3Si/WPN+/ON
mZ+/NpGnBQRyLXST3hPpcej21v0Jk1pW5wV4gIM01uQFBLt01Bcyh9Y3nPi0k5js
RDFxh+ou6oJolH3WIp/apKMpxKzHIVw/f4WMfA8ekcEFESzq9qFCD7nh7vEdTpgl
h3ni4jzpnS70b3ISGRUoGVcDAigqzV64u5ICvjNqqTTFGp3HwPpXsDm4b0/LE09z
6KirtWFYq2rfWMt+ShfkyL/x+AU1+9nJZQKdu0Ho8lJP6ROw96aWd6gvYVVvl87H
QEoZ9THR2QXfi+01YnWhljjhboZE9JfQkbKEO4umZ+H5yXxakDuwqdg9Lq0MFF4V
aBYYp48muO8e8fLJYxiWDX3aGIZLsZGs0pcpAUPkXGWkPv9g4uk27tquBp6fBc7S
QQFpKH6O7jvicTn5F20fmuX7LCrQUQkcMvLlq9/8qyalhjQHaAh7u7bMqC0QV8GR
sEhrDl4lZ3GtP0jCX3t+mESf
=xblo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-3da0-4570-a6e9-4741c0a80111',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf8C34lV41oAJ7V2yglsQiPFkR0aqLRSok05TV5/mDcaG+L
aclu6NeakewG7J8epUqIZeiDhsm6InCxjHonZOEGihutv+6lBehtIjUQeyK3qGz5
Id275Ro+XvFwfbp2PKcAvifgKb0oAEVV+cA2wR8ty5o4pVz9r/ob7PSExtAvuWYE
X7fm8SeJ1YJOH0EiQlce9oPIUfF7FvSDko0+9yD/MiloQv+yKwA92vLGddcc02jy
WO89bpHt//hcuN03dyQhAnl9Zej6tQDg3mBihHH/CNCFC72borceQIZU0oIE2d/l
OztpMAKe4hpr5F86ogaqh0MBN3G3AuI84QvKl6zjhNJAAYA/tMGuTzJ4MByIm8F8
Ip/MR7aCiziBDc0Szj7IK2Ga3mpoBN2RlurpgomFscmgNgPnxPWT/agyaFELjQeU
3g==
=hujr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-4974-42a8-b2e6-4741c0a80111',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAjS97T0CIvSi5AibCHX3pNQRfTGtFx4AV5NbsWwIr+C3x
oz7zlWww+hDbg4ERgfB/AWZmNecH/+qtGPJ4Y4tfFrvAV7I0HZRuQf2ld7W+icJ4
Prsc5G2M3ZeljFRZ4owO8orL264eZeRW5eWu9D1KpcqOmpjnv87D0ZYvJovZhlNd
KTiTPwRxSNA7iHgrXxguV/4Jkpfb4IMoyo93BRwiuk1mm4tR+zbEXU+x6pjP0fZI
tT3/lXOJ2hRAByn5Lc77tAL9UgEEWyJZVOsXrEL6cN3hlHsMXq/wHUpUK900PdvH
SRCg4PUEm7Rm2kf9KVnpbE9kA7GzYybc8YuCTpI6zuuLmICQ8xwCr99y+m2cf02w
dNe6PSpv4Wr6xhuI/vQ+0LT1VuxO4ehXCxLwshRs8olN5TL93c0WJABGNDD+2iAu
gChvNcwBi+VhwX+7fq2pLZCAc+MobHAe5e1fmtBFgkr3Yb2RDqw2kpAManiWbuDG
yci2cF0GwpSysl8DPIU9/txBoIMoNdgAT/7op0qpMGy7OZmly6Mf3FGxsFJsE39Q
+e7t3COEWEainZKCwssR0EO4vk2VUl02cbxmqi8VDMCUQMdbCiorDSK9atYhyJwe
fXiXHWk807L8J2B9t5ZYUm1hJeIFYGg0I4T49cXSPMHGO2c4dxgjKBSWi7KkArfS
QwEes3OKDkqGHjrmrSrz5yR5K8rXQ54wAWw0BA3XDKc69xxKBsO5/vtQaJL4FRUF
kVMqbj57E0r4XhMvbbR6j+++b5s=
=G8HR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-5f5c-46aa-8a50-4741c0a80111',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+LBHiYTR8v5+3lHXis2AaQkxuWPA8GLLE2E3Qk0B0T3XV
sVAi4KCCQ9d3DZlhw1EUHBKBrTDtdcZlS7qiMoRsY0q4kgvEzf0okReWQVBnue6G
u9T7IK5i6Ji6fcK8EVLpp3Hiho3ihbnFxN/rYVoZhtuOBcw0+WEf65YnvX971qad
8xnEhl3/Z67hL282YD8kN9+60zA0sM1hUEpUFMCYgwpEgD1T2CI6t+wUKjz/cAM2
KOiMDehyEEiu4E06yTjhebR7GZP1LypRMCXdREyh+QObZROeaFFF2ouwsLAjS7Ye
zoJ+zFaC9RYtZ1UwWKUM22UMZ2vJVockOvyzEF/Scy+KJKKbWznRU/RKKngC99Xk
DzdUT28Vv5ExtJbh+xK8VpnrXrYKN3kLEIIjsWQj0hnicchPiW/9HDeOEA9QTgz9
78dX1Kq/AyjxYxA7o40FWNjhoGRXdItPzidcsy+Ux/VgfgaJ5tAtgEt7gaXdyPfS
1ZUB2KeoFTvYu+oMpcNEdQH0OTefU8wM/GWhwG545GUX2BcmNNSbAAH8JKS07JJs
/Rq2Kx9ABQgts7DMwSKM+MHPC+OwuELQsUwaBMVwT6S9bOWVHFLSQPvMK8DBhoXP
bk89GQxM7cBeSHg1fn4iMg94MAT2vjzAltsSh2jKB3Nw+XdkxR58p+Sd1LjepJ/S
QAE0PVkU2PYR7R/KP9KocxCZs36LtAKYE/byh4LiuY1oOegTnqFG2aFXe4w3DMrE
cQqs4XemHMSPT092tjNgGpU=
=KVSl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-6540-499b-99dd-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9GMpmqxxGzkHkP+HMtaj15VGR/8uyST2t077E19eqKRE8
Q7J5dS/hYHCmtLv1825Ek+tSdIRe0L2CjfTEvYjizYwieAzQ/7X4Xfmf3IZ2myuX
zdUTVZYbVCyuxksbdJDfoVW1u84GNxVQIh8rg+kn8O/OtCsWfuntaPV8iLEkNXLX
tKZyFCsl/wQcfqsZJVzAYjOidU8qWTGCfN1n/QXwnTAuZ1KiGwkSFpNHJW9GV+h1
ycFapmu5eJGJQBjxz1zVbXI7JMGsm3ihto22Cjetek3i2mHRbNWWs9oNlZ2J6Ou/
VGHrPqiRD/p2RM14byl58InWSnp0qHysmle2qeObMxrCEHUSo9C/dyBNcM3mWKmS
NfQuV1XpDRtI7E6j/6M+3PbYvkaHxlZi4jRKfATtd98n4HaHlKJKmnuFFpMI5Pty
5EyccNa8uT+KLDU7YHjQoJkEar2LZalOowEu+dhonjuPvWwzBxS4fE8EEU892fmt
FwMcmBkcxdKWww0TMMD2KY/eLdI/kNDmp6eC0yWZVdnKHCoN5HDUZ9qLMYd4K7aG
yyTOJnU76U1uw2DH6HitcmSOg/DTJOb4styMj3QwpZLublH4+fMGIqXGH+5+Tdta
4oVOVcIcap0NA/wV9GJbM1lNRcZkCAh2jknxvq5aj6Pg2bs4j1amODxAp2KjBYLS
RAGVEFin9XMSDmM8aOSAK/7MbSc7bqK/djWZMbga4wIY/2F5eBHR3yiaoU0WzNGO
+SLu+BMMMh6SWs2m80Qaq0w4J46N
=C+j4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-6c9c-4dd2-a484-4741c0a80111',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAsdpFDzm86cNeiDI1B8SU8zxgYKLTEBkEWgKu4WqP0WLQ
8iysHbCTklX9onmkYX3r207BpdkA1Y2m3PWCjgHRSmW/7o3fA9240ornzC5H4ypw
+TgOrUtBDfDEg7Xg+8B5nXwe84EWo2tFduQiABKVUzxKr1rkJjnbZc7S2fxkymww
xOlPfYH/lS6bDhOqKLr9lVM4+i3Nhp13TqFdZyN+6sjHHDzG04B6kWTnAl2baQe7
Hdjk2xzzQ//5OPupWwjWWgBL3WMY+XFXmJk+CxNR1q4iJ9TtpEgcTXxH9c3hiMuN
kZ8LRNkcSdXcVPKPQyJ6VFx0/jxmcAb1YxEedUc4R1V0de5Xp9c89AuM6ORsDA5F
M2jbzMHD7Q+pi9lfyda8QAJFr7vLpPfSEy97pCL5pUQD4l1dWqB3hCgV0K8M/w6c
J7Ege3Qq6Q+25q/EE33wtB+IbDiZGDesNnNG7unWeK+U/RaPy6ro+Q85BHN5EoeX
XMtOBzbOSQ8DpALZ3CnfxW97NqA/8De8OLoSmma4TOjI8m4WwuRfC8W8Ek0WC8nL
tQeuvoUA1FgGtKZ7I8FeNE1WgSSqa4B2m4WaqrXubnINWyIcJEKUkFqSj+vfVzLV
XE9yq5a4OoXNS7bcR1zigDHnQ1Jr/sCJWN65uDQyZv3+Gc7KXxJX0t4j6S+X3lPS
PgFYZzhdD1xESvLDViJ5RpMehEZyGieW6CwTxzs3Xz4liX8t1O2TdRNcYk8ZxobX
i4gVVAT0RTXtEpmJ74Ky
=lP0s
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-7528-4560-83df-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//ejygXipY+qoin13vPB3cbIwSUPSNRIaDur6ZiyExduHu
iTRYzcKIzdrJzvGtjsg/Hklnrn6Dst9T3jcJsWyqyk37ZW0Tu93lt/cHJiQmsGlf
Skd1i4eyHgNQwd7C9CmzgQfG7FWqc9yLMyjHsYZ27P3i3xDoYWHp5ij/m9UWLCnw
/jaa0hSmJf+I1OLlYDoMtWQ35qap8jB2qHqMVzvGYBT3DS+AFTtSqj7NUIBAtZ4M
uU765jtpgQNHsuPJHByJUf/PiCNA+pHZ/x80u9elbud+S2S8i9mnspCWTOZLFIlc
Lo1XcuNQ33kZ3B7DRKTFEjZnX1jMNNJFiqZeS10zRlITpKPJu+BQGixj3Dcrmm5T
tO9KZkLBzzLokfQzFzkS4XvkLSxNnmoqT9dyv57GHyKdEhpOuvZvQ3bGe9jEUdCE
LhR4AWZ6SfwGGRdJi3xKgMBpdBGHMDsouxKpi3Z0JXDKLMH+CYRQOTbUsTtyTs2e
AX0eW/UbbvVn1ZWDYKInvC7vk+NPQ2vFa0IYShA/gOcGLid155xYUnBIjxUJZOP2
1pFJVXu4xGC2C8b4+joaQQA+ZEUw2ZcU18lxywOmoMw3gb6Oi4LdKg/yRwPkQaOH
uVGSsbyosCO6/PwWi504LXP+liKjKusahDIiR9998G7kBf8eOuUCnV97w+b/T5vS
QAE0v0X6wnUZfwW9oaeQGsfFH+2+BX3nrIEmRHmVvgxCblopuTbwT24q+r6Tq1jb
ilHdKBYNACXcBKM2niKmq9w=
=vsUe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-78ec-428d-9fd4-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAApDpWa6a7Ki3EgnNnsZVdtUoY338fwi+qRb0dZcuaTIqp
0/hBEyM6yOF+yjzzDE4zGygXtM8qbRFWV2kf0kkvBy5CdEkpoZz8uuRKF+MC27MN
eaEJpcaL+YDa0pD/S6wvrkSePFHLHZF7f4N1tvD61jc87uIhsnTMcTbm1Hed4XEc
4/4sskalFsF1siRQsAIQtJAX7/1JOvfJ2toNcgMBYtOLmprE1KILElsdUEAd42YO
y9W62M09t4gNp/P7NeDQnYq8ATPtukOFNycz8FU5KLEgiPMMth7NEQKgZ8zL9gyH
jQdyzqfIepGgGdMm1pxYZLRr1T6XNRvEPuxriqj9JkQitL2920alaeAcA9elsEig
gTx+/rFcrKr6eKEDL2wBZrM0igcPE4Dl0YqQTqpa/T207oEzjuW3N326Xn81fB/N
XQkcfVssMemdsU4KAxu77TzyT9RfjJb/kkPyYssPkNauQ1P6t8AP2mrkBzjuQQ//
dluOg3bbav5j8/aO5uIJ9GwtHkS2YO4zV9p30EZyqM958K1S238laetpaAPEs5OF
SgI0MqgMA3g3yXdQIdZhAVzeE9LQstM6Z+QVvHVeMVx99tMxspdH4tCdRaK+QIfM
PMy5epjKjZNEwJrXiAwArq915PJq/RDj+aabuNP/pWRflStYEDC//TD1mf2dp5rS
QgG8dqzTHLxwarMVWuFu4xBt2yY7uvbZsp46AsF16/3efDQOVwk+xIdcYW19CBpz
nsMzWbicENIwodtvlwA2bicxxA==
=pHfG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-7fb8-48e8-ac60-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//RTY/JpqxA596dH1IrW+Py1BPnLur91xJt9HiimIp82kI
UMbwE8gD+bEqzNawj+tJojOmg222DwZ+niz47K48eYrE/+S2c+c/QPoIEFVM6AJN
nZl6n8drjQC2gIP+24/QomvbeFuR21xVyt9pRc/S0VzOGoqR++/wqnA8xhgUjsRu
fgmWERLW3yDjKbMP7TG9Exg+oEmDx6YuFzyGODv574gZW6Hi73oGF1+N2fjpDlY+
cLPEh2aLGkhTgL5wY2T8SNgviXzXI2dqoX904NYoSr/n7G6LKM8/HcxhFMyRaDV4
Mgx1hiqwu3vuDEu55zKN10OBG7OXry1qNWXOoCmlT3UUCP27You5XD/iYqMfy4Z8
q5uGi9PDyrCcfKt2SjQMWUOsQSNWsU2KOck7s7RDIPTUAQGOZ21jdJTtmtnuOPEj
aR3ZMulNA9KFEsKbl0mba3oAJGOSnu/pp2ZeYLgFLs/bIUlKyIxkamTFFzX/1UQS
VF4HVUCpCh/IEYSxUhR09S1/lREtL6zI9AfvtdKx7P8fRZFLjbmCUj/ErDa2a3cC
82oiIP53IaZoKuvzb8fLO6mGJLY1Ais9ea7iNP0NflX1m5lsN3NexkBBFaIMNQMx
1YgndDiKqPPnR9vynp2fsqP/Li/vUL8xdwixwIKIQlwFvhU/XG5LBYkderb1HU7S
QwH/IGX3gffedMnxnSg0/gP2QuVKkjtY9vqLmYHvwMk4BmOvRD3wJm6EbY5L//OM
7lwiHzQpXko8F/O9ijpApCDLwz4=
=E9i5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-83c4-4d86-b6aa-4741c0a80111',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//bpbBdDyY2dfdxPagvkFdsFgPqSjqJUJU09+qzKCY1sqh
jgeqo77SaXbTot8683obknZqeP3pDQ3P4tHs/1j7Id8rW0ye0SkD3dM84Rabep8M
zPoqKMHYlzmx/qSg/CG8K64skflIGhwObgWAEeiduzRjjh1wbEV3rZudhQu+DORI
wVQQd/rkbM6lJ7kCrQqxuyfiUfr/0M5kuEYOY2nOIDZ86iS/bkLtdyFmYPZYtdVb
Xu4mqZJYPPvvhedGAGqZ7VoIAEWIlmMgql0Yc/AD08GzhRmM+ukKdHBmIxQkd5k+
4nQKTAsgsZeE0Y0EqTmb2yOnv2QqnV4mbqGfmkQRDzg9x2DHXgnz+op+SNSVW6vs
9oyL0HEEZZi3vQnDr9KvkeZfljbNuouaKlYWsBDg43oTi1BxUxTIT3dEO0AmHumG
RpltNIYb88wQJh9hOSq8v810qR6xFHtc6zyej+IZ8smnebwde95CUbhFRAI+/IWY
fp3Wct8OBAVhBTHEi6hR5HBK8rbh8q9Ch1uWbbmOKgNcllizuDt6Y2nb1WgN2KI7
o3KjrWmxGz02cOISxNXUZTMBRNIexpuCiqjXRwAfMAzPTik0DBa7687nIyhCgD7S
N297es1i0B0PbfQwTnfCct675o9JpfnXdpmEC0JsyakrgJRk+kRu+sSPWDha2NTS
QwFNqonA1/7ku5J+HiKZuMyNBk4brbKj6cticpfYwQOSybPPqBQ5u/dhKY8lqHCE
ak6sRbA2DPZYp4uMu1NiM9dgWcU=
=qa3u
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-9320-4091-96b5-4741c0a80111',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf/Y+uCjuhAemvFFMaV/rA0rvpg2k14QxpOVVAWbORYlHPf
tTpDlbZRhEyIdYtCb/0gDyPEpy7p2aytnYNTg79U3+48l7k+NlTRBU5+9lhs0HVJ
lhI29mfKyRF8xFEmkEgkxj80MYGiMP9gTx0EUWxqJHbPKyeXRYzg0Zuuc/d38cbt
l9ItiAjxI8rzqgSVf/HYKcILhLIRc2oVz6UDzTbZATzkQjE7eEY+8e0M5Kckv7Nl
FDs6SQA9XGjr9xMqxzgib2fvvKyGnxFRPCIqJVCV7QdPUUnwqkKzQwY0DzHsgTAE
+K8kuj91Os6FHM4axdfsfGX+1l9l4Tsbr0B1mKZl+tJBAe5O6cBVaU/r7sgEa8lV
BnB3vtWAFzmpsZoN0qi1ImYe/s8qjzTz0QgI9XTNYxn2jDAkRQb6WArPnJN1aY/Q
tc8=
=6NZc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-93a8-4f52-9ff0-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAxr7FjXwaPYGz2aGTEe8/qklO/Qxla88d9kaZ1SdXqDxn
HnNoGQfZGJe8lVzl4+N1H8b3rWQPH/jAK4VZ9DucAlItmQXG3I1O58qpWse5eP+K
0inOifAvOgpAK6X4ZTQPUkJRAdZvMljHYwec/nK1AKfDVxD+PCjgtA72n3SgG7+i
VkVvYMiGPlcYXSCE6x5ZsCJ7jOVXHooaSAEfkxUKLKAD8RgMgIrOaD54mUTakjVZ
l9SWI+viUMY1vP49cC8mlrTcEvEA4LcNWthNKI2ExbtO07o2LZtOqeRrvHxwQDBX
uoDuYS6ZCoH+75ufTYtvuRGBk+EI4XoYjrPBpQu7IAVk6Vi3uVf0OxcMNPr2Dpdn
dwCwqBpno/QKTwtGOAZYRedf8RUf5I4BNJW0hWyAQDDPqcfpIc38QZUBPevWbfd3
+/JYHB3OfdDFvaMsHVBvjhpBGQ9ycXw8Mzdw5bd/BqZZtzaeNS/LmtI5etE3snkE
pPQVzGZquvNLC+LOGmhJVxt9eVwxG0SVag3fjTSNvOfsh0PStiIiUcOkY8nQ78jw
X2D6xBZRFW7SVIfMvHrwROY8Zi4G/kfkufxfOgGAGeefII9+Ks14vfKxQhGXIJ0Z
dz/9vTQgIzqYNQoS+ZmC7bfwx/VJoy4Q9vX2DwwOr4G/wDMn24tlgJgpxXSfRoDS
QAE0fFdsa19VL38D3D4j4OkR/IBki2DM3SN6+OCmYQeOkaJ8b3rKOj8rjr1n/2OX
pZkz7lmiR9CsNmqKaYK+G2g=
=2fSr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-9b68-4df8-b270-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8CIvc57ycIp5Kh3S1EesCs0+A1pMYs/y2ACoBtxZCPI0Y
EBl0ysodHHhDRw0jvYtaZp8FnP/PDbDGeCSkv4k1Hx+1P5nLCuKI6AXAWNZYGkEg
3w5g/asa4babMmCbywO3sxztTjQ1b4ybprBOiZ7wo202vB1pSIL8VtX/E8g8FcVE
OU72ppUq1a3P3xqSLtLNSSRGzPTgaG7v76dl2himqHEaJgIq0lTtrzoBzOC5dNlZ
jk2FrRYDui0mmZw/8QpB+SpP2wJt6mk0JpKrLDv0cvaxAE+S1KEoVz0/HI9HQOCV
3/Psu3qAzYYK68LxxXJWQ+a/PuVjCZczRn0bHiPFySK+eOzA6hIeLJWXVQcliccU
L6Hm5mZA218nbY+ns38Vk0/Z/spZ5qIysgPM/lf6p3IAgiUGYdJiuaFHXkszs/ZG
A/wC6fPIFfIcXQRLLTyzwkzjRqMY83vLvITklvRDCeaBhbdixolq/yfnNSVyAhDz
4t0LgkKPaR43juU8Aw7qIHEw5N8HlQybb/jjgh0Uzuct/TdoEHq2WEwF8RVHu82y
PRecry5Y6vejN3DQv3D9jhsmqLNv4w+dqcFjLe5MPno6Aj+XBVvXc9R5FVqNkB2e
gttsQd455s6MCqY2znAzc4N6D35rV+mNTFNRjjY+aJc1iSiGGhgtspH6nw+T+ofS
QQHj2BqKAX/7JTxlpf3xQJgfaHvhPV4SowYUAhGTtwQt9Rt6wYxkN+b0tiPtCRtg
GafKfZQZvqJA/2w0GaI5u3m/
=kFrV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-a974-425b-a045-4741c0a80111',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9GrLwSqcg6oMIUeNK4Mcd6zlbBR+9JcRtXtheWmx8JkpM
UZRygh8zT8r3XF7RZ2SjF32tWhSuebh5tMt7aKKckBgEzhdMaK8ICsU1iuAVlFI9
vHxXtRbhsmN/foqsHoNPkffhoJ2w15X12yYxO6ZJbmH7XW5MCSnLKK279eCqJax7
lwstxxZSZxxINU4FVB9yOWqRgz1l0RYmIJRBkiOiDfYCcFUvjemGpW/3YKwhbx/P
RFvpX5qrE+qgQOdsS5vNLjhFIkpO3NqfacQoT2cfhvYZu7t4qn7AUateAnUIv2AH
aT3/REZ+PBoMR71XONjpsMHXXWIf9pMQPrw156om3OLKLQNSau+2YcS1A7cTqyHA
QfJbOVIoT43IF4aU1NtHjnLwXoJw2/Q32//FwVyCVqGVPtluv+MEZN9hvLiSPFnT
hMvKE39YKN+eTLCXs/zftTpvzkEVi8vFYrIvprQaDx6tRV0UY1LCCU2Zfh7aPB1t
6UsYhkvDVycMQTvIQ7PtU3GHWAJrNKyU5oeooRGJScC5wiYdqiZGZWnh+YaHx9ux
ZpfaX10xwhIVl4YJWBjTQtJXVyj8gPBtJTyeGsaQ6p2VEv75Dh70hRwdl4oIORG0
E1hec2rShK+8TkJ98Y6EW9Ub+0/i+SbRQ+MrB1tLlNn3bznBvgfxaX+/rHHMw0fS
QAER9kajctWNmj7XOtyoChsww9eAwMpkLI2ie0RORLgnO3Uwofm6HcWp1WLP4XhL
NR+IjFabJKK4qubcK1SYNqw=
=tyGb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-b0a8-43d9-b999-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsB2BlshWxVEbB1c/++jmlC8ZOfJ2ZWlr0QFdNhoKnlbG
+eAoT3kgbqji3O1EQAxlYoU2KSUGiMdtJAugVRyFRBk5z1PoqGctqziq2qsQ7jMF
1bU2g4FK49GO4HZ8fGFBAKnT84fntXS5tTWG3BjwQ5jRxnMUemAB1+hzzpF0FwpQ
VEGd6mtsFey00Wic5WPaJgvwdLh8H8HrM3eHsJBEtJmfgyc03EOHBxEE70O82Gpc
Bb1VkSIqwe2Fu1wEv2nkkVsDAMnBDtuzpEEt1gRDVvNjFad2ulHD7YIO1hZDLSS2
3yAy99nPwzxNMUO8znP73zJkJW1sKQ94EcUpwRqm/cVDsU/sAqpHrNdDVSvh02bI
SDUVVbpmi+L6emTjo1fPdiNJLSm19VL+Nmh+od0DwLc6vJ/XgBGtVFlrgJM0U2U5
O2FM/UOY0I/wJvhyX79N9k4ctGeBxYKVdm2N0DtY8h2sooWsSQnL30njtjR3V4WS
kCrBbvEaXvHCv3PJM5v44+umLSWaVcmM0C2KwtD/BwhuCs4I1NjksyEh7E6McSQN
qP4H0iPnlzisKWTzdw4ZiXAyg9jkUet2PZ8/wgX3UNL7fjqbHWKu0kIQyXDjV+G1
5mOtxP7rOeqBTh74Iy/GISUbEiB8b3FnRjsfdxfUznrCQ91Fa2eKripTwqNzOivS
QAHymtgr3xtGgRXcLZViR6HAfedtC4ieO0wHoEcjr3LbfjlPNIxpMOcgzv3Efwm+
2E8gvHBpJpKGnLJPOT5s5Mc=
=Ydlu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-bfcc-4dbf-9c05-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAwiKeZaG/P/sM5QcY7uxXPaYeH5nhEKqBpOQx8PrHu6fW
rXH1UKKKD5bD9406oIJCYOKvDFwE1YnaFhkCzQkvSn9E5DPM+nVA97Cp1u9+9lMW
be+V3ZzXbSYY11SL8v7yPGVuq27PQERoc1TPsDUEKKwlMgD/NSG1KXV52euYHx+6
G96ReWUPqiS6iAtz/8FofadrWGOKxMWh+DE5aUsqUZmtOvLtAmS9vzugqea6LKry
9nPY1ASWo+wo1PlauX+VM1x1HiluSxZyA7dljjRw7W4p0g89PGzlGxkR6sQ+h5Tu
MZIUSXlHWt1jCl72RxPYLHu8+M3KBhTnDp0OLz7JooD+5akXt/QaNypHjpaSV7vt
xCNNdegNX0RrArPNk2O476eR+Jnf2d5oSsOQFJu70a9ly3TuqWQ8zYfNeGcDFU4g
2Tll1JNGbdkJUrFWV0a8baeAkwJcBoMdU+ljKN3p6yj59JswMGvD4ZeJ95IJ6enO
3uCGotSqkNTrM24zwrMecUVgVv6nqAYMKceJyj1djHZ5Wg79TkzszoCyCfXjcITo
8nSX9BvnTsczd0B2xUVgQkNgYm1ccgxTO18uIVlpnxoXQtP1oZAO7MQt9a88RZaH
BkTzZ204+tj42CJtZfsfwTrGwwSNA1rdetawm8aA6X9oifFHd/sF4QVdqhfYMFnS
PgHEzcIbiccP+w5lEtWr79pgGJEPo3ugFbdS92sz/AtxoNTixgpa3Kz8n2XXhFLA
YsJMMqYUHlLsJT/RHQcs
=Hjb+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-c6e0-4aad-a2f0-4741c0a80111',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAv8QTvYdTC1dcFHks9DMKU/xILnddmT0LuxnkJ2Bfvkio
mrv2XXz2OF6uOAQI345U1YnsdbV+CzI+eGJkoCRlexyNjwjN3wYxcRXI6odEfX7I
YF/tOVzmGkYJ5jJnxLEzXrM5ybdN6Gbt5ieqvGPjkZQRjtKA5YE39n++AYincDIC
+rp0Cuq+Z65Ud53XzeNwMHWdeUVyDetarcEyil8kCJYG9WOKX0ShoAEB6rISXd4K
MTPPEh6u9aRvEUDHDPos4amtxU9TZEhRarmt4HkiCCQqQGhuGtr4EkZ5Mu71yAcz
6H9Sz+tyYQnqLd3f0tKrFkDZYp6WriTxWeC3cgL/EQt4IKa5wAIHLU8u8U9XlbCg
3fGfJ9F5avTKlR3yGriZSPI7jxyp4yp/Qz+LyVknk4mqm/1uKzMiDsduWudwgPPs
jTshAjqIuRwrv2dxNkHhUntSPDtupVEgL8ss+0Im9bLgbGaRFFcN1xTZCV/9lCMh
6oZHBjUSbdKuywJFpUPrOTtti4tM/IduOdPfQ7S4379n2Ird4W8uo65gkKszi68A
HBwiAO8JAC68ezr3fLJ3D/h1qxVcff/nlmAP5cuRe/nFZn2upNAxF96cN3XO6eSj
BN2O6swbT/jXFNEjyrzdFuWC4Kac5dnxWVtWaWOhlGojWgeWc42zpC63xQnevSDS
PgG8d2PbCk+VKg4fDaD2zd6HM2rOY2BzObKz2GO3VQFEw1j9LNp/wsXGE2r1qLy7
kQl76VW4Ku0uuK4LeD8G
=v6k7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-e740-4925-af5d-4741c0a80111',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAm4n69YdTDHH5DvwRTgaTwlFmAfqYv5fKVSBf+ypNZFs/
0GhIBdnNSKhAm/T4mIvmpUWKUZvMGyLxBXrw4VqsFx00OJjgg3UIHoW5wNDTmj+o
5VOqJlw9gLAWD1dXt2ZfnZJCHLjbncEECDX8pfXbJDqnlktquZN9DvBdzI8AC3tc
BH9c6yEIMuhfIu1vWTxA4Bp1Xnq6Vq+Ohc0Pu4l1yL0z5GgxECOs1NSM0aFWmbKo
371h8VOSJoN0gHheWHj3gTdf4BrLPZMyRt2VHyHi4u4AR6FJgt6tVGjqycQaCcxm
uln5xTUC3cgkzHDgz2r0LPrYPoZRxI8J0EWXDaz+Pjvc3+8ndNgZnJqpFONyYqG3
GWMcrvYiJuhy9Rne0xVRYm+dHj3aSvN2ASknlj3P87/7fnyfJdAbOu05ntCQfzsp
7Bo2m4t17X2JLt05AzofKLrO0dub86e05fcsPFa0+Nnb7jZ2ywqakC5DpzkVScen
SGKK5phQuyf4/XKDcGqbVaFLil707FibUSBTwTn/Q0sKyRPfjqxxtlTfEu6L2SvW
cJA1P43kWY6NaZaZ3slVSHqB3g1mKYTCRu1I4osGFcSRIOBFElNJNc9AM45+SI+8
NmiM1xdMwYZlEv6E14eC69Aa46UXTN6qmlBbIwY8RPq7j5BGHfnCDIcJsEneKKDS
RAHTSWQZVl0fBv0gFMysKn10fNWkg0OWiPx6znj0dgEZpKu6exiVfN1tbmnBt/0o
W5nR02LBG9aJMj41rfAm/vzbs+EX
=3eEy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-eb38-4371-9c30-4741c0a80111',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf/Ruq7Hrj4xqYHghUBh1Cn6ppVHhrvTHurRS+y8iGVFgFZ
/oRNUYlg+D93ESOf8WnDpciMkEDjulRCawrCx+Dj/E4UTJkhnyIWieVXfxFI898l
iE1JhvPB1TDNdB55HkMoqBv6AupCEWOD5tbikcGIfyAnk/W5crHpmqeLi0i09seQ
Y4ZWes48wM7ioUnJxf+REkLy81Za+f1HkRU3MAUHyNAj08expxh5kEAIDVQ5UUd+
syrcL61JthMxgFYgL3mRyZCcUdpqeMdPZ17YxG+QfCwlXcd93Yp/JVD2qeLbpdvu
2SzGZD/tosxlWdP1L7DFDHMV00mQ0Xv5i8nGhDAtudJAASmZU0mQX8TCq+zEW3AX
csSdewiAR9qQPTUkuNjpsN+Ckd6qsrH3xRdueIh8IuTXao72TmZ10Qnd9ipmBfmh
GQ==
=XZlb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-eb5c-4659-8695-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+Irp144vaSrrmsdhsSMCbM3rKizk+zSlfRFeOn8hbmREl
nnpbFDn2WBCzaJ5uP4uVvLWV9u10B1kw2DuL7qEGD27D/WR2iE9kWq4Zm8Pv46AW
Q1kXKPj/uDLrv3hhTCwpqK0hJRTKKFbn2pIwVXhGTwpagnCnA7vzZvNIdRcuwvY0
iLegOBkzkAHhg60j/yFvI4XRvRmpN5TwnmYRDd4lGzT6zf88fGhVP4kEYjOrXwLk
6aTBFFdvRPMHo40imYCLN8eaLSIz0bTJXO/zq10kUzr/OsKTpjZaVO8pFG81DA7I
hnJy9BCq2oyR+hmyio05UjGoss/ps8r3ItEcBglWd5LiwTC42sXsIC6qvAQNqYWZ
+SxAUr2naGXPiXcdi+r87unZQAU5Pm/2jn/dUO79hMwkt3HcvFq1IjMojRQyJvzB
BBczwg/tLp7cm7k2LfahJuKe3M7D1FNb0Xvml8sKynBYq89GBt1OLbUyAS/pZVV5
rclMPKeIV/4+j7lwSe6UYaYFmaG36KmYlitzZ9A9viKoqsXnqcuHdD2tfxqhDqrO
8RNz/76K1WKYdylF+XwOPTKH5wAlKJbFPoiU3mLGIBkSBU0qXkw0xqnMbb+D2FOc
68n7NFGvB5WbSN91lz1dRX1n8UYYLdTc1v75bmBHtQ6IYwALmLctqRuiL/ancC/S
PgF60gK5oE/Gp/OcQgaRizdNi+fCDhP/8+rK3eb3r+kOxpcwyD74DvGjKZARtqhj
3dCRttShdjKM3eTGd/ER
=rRW9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-ed68-4b1a-93d6-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/8DEgr1FNsXJ5wAp+WTt0knFKGmX57HdNa5WArWBGzdOBm
M102/Aw6CiascGxSwpz4j7riVPptP/yaINupx7EFHfR5GIJaYGfdtyf+bJHgxCpl
ho4MEznflCbpIND9lG3YRBofJYRVpK0jm1cGkAiq5GaHzZyP8kfgV98LXeml3ZKB
AFW7HjrU83jsXLcUdZrjiMrLH+nngsb08vg9mvyFHrubTLideWdfbJ2pxFsoLTOo
WH6MZBmezb8D9EHrDDSRX9TLZ/4lpzJc4NmYWdgMVepSZM8z3jb8hkOKZeJtCPih
WUW91Da2UxGtEH6fvEAL4uyyQJIVS22U9qqnK3cmMFynUF1Ir3TqiJ1jJpOoZ1YO
BE0sY1j03moxLN83en6tPJlE1mPT6hHXkwyY49mSC55DVFm+vN9qW7r+8vovO3Fe
/3cezzeFxvkECPe+eVowsXjQn5vmKHUbr1g185MlEDKaRF35rdxl7c0KyQjMfPC6
EtXfwdh4iR4LAOWpos7hPqb0ODEmFhOxuJ5me3cvCMY0Dr1tC7QWbAs4cYEhWxwv
xCvJ62vp7FPlbmbCqZG7vxWmw+gcg5/QLGggds6IQvzcqOFGOezGDJig2tRNji+n
NoJ3UlZwaIYqwL3oPuEtJO2KmaSHwDptYArHZAkYdcwBCvGe2tlPq0xG6Zyr2nrS
QAGIpnvcCEV2Y3XonF/PAm16rak4FKdXyE8++LmMV9FDyjwD0fFFnSsQdc3Fa4H3
79iSaWizMfhxrfUHXx/EEF4=
=ANMo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-f050-4f19-8358-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAyli/FCLLylxJj+ivkEtPgi0SPaVVg6P0l4RtXVB3Nvjp
oTCtdzE8+oouFOJVs862rb76DEmPbS6Ua4q5wx9pMhoFUe3sWT/SBaPiaHWEZvf6
D1FwwVTqeVM88zXZxLZzh3bOBWi/2Sz1vjKcSdFsS1z9wQB/zv0V9rPSz1BHHgXi
FSJuXIb0ss/y6O4SkGbQvX6grwE3Yxyt5mtSLbcKlg4uEOmovnhCALmmw4X7Yf1N
LlB70hwUjrA9eRcECpKzd2xRY4oJn1cT0wTSkNBnH162Rr++KqepyG/MF9jmmcmf
yq/YDz6WXVEzN/VHl/CvgP5AkP84hlx+5l6SIpHrXXH/nzFt9ihrZ8XPsp+ifCOq
dWc7YPg1lPasZPA/cCWXWNm+uSz6bPHwsjbnrU5rMxvTSoE+OtdVQx8EeVhZJVqJ
I0a9BVD36v9Q0/rOu05Wiqy9SQV0EBwhyl1O8vPwCy9SsYC8R1K0Y0zJ0nzA0h+x
pp0jpltgHuEzG0tgwLxIilPtiG9v0cj+as19++HxqQ+hy2gw/YZFREJvcwzO4xoe
ygjjoZQzHVP7ThQPLKdIgYQwm9AZrrAkVMWJdk7tp5a1MyUdc5HnHx1Famgd6+x5
R6FzhFB0rf9028AFpb/LBbgWQY7kYQ3N+JQ6k12CGBpO91OwJ4LiDKmzcYiAO3XS
QwFm95BhUjPucZfVWOyOx0da39GpDrkat2tJoMvk4rwcJt4JViicgX7ox0FW/iyN
jk2aL0Duv1Sikcf1xvLB6a/5arE=
=BjbM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c998-f504-492e-a21d-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//cSHJK+tQ7GMj0wDFeu7tVGEBiX8NL54xBPP/07QWMD05
bPDsaJSxjx4NSD49WRc4RCTx0aiY39tWEQGsVb7eobEbqmCAeRqaqxiQ7HfQXbtw
GRVy/D5oTRlYCKZ6knGfzDp3DjSh6qDdO1rGHHag0gYKYgJqJPFq7nfhmKnaxCOM
2JwZdRyZ3qeL7rsazmNC+DGxmIetszi5qExt9AfSURHlaYkFsnqxcGwFHWpu7ZL/
CGxzrbcrfPII1nQNAajnSQHnXI9jC9Gro97jUpQ1DItvlcrL2hur/IpFZ6vtU1sF
YSoMzBWNWLBo5aWXItSMrhTlEMc3jf5axJIkdcckabFWkk1ChvCHVHW3Ce7czISG
x7cly0G0BmBl/rplwS9C9sqAdupM2sgPbKu7dxVdlh3zUKJ2SptKCcb59vbPsMqS
TdVOS+4OOb98Usb8TKYTB8EP/53H24tkuK4eyjFHgBo4bInD9lLZqXjk28egoSLE
R5Zab/ut0kVfoJSy6f1hYa6uzK7f85U5Qd0l8EK69aWPTwqw0jKHkpd9oetMsa0x
WGB2zy2RELktBy9bwFLrJ+9NCmWaxWJNuWQcbYfMDL+LUpadzojTbSomziF/eS38
5iEYxGetXwLjv3X697SFfAFZba5MffNwVTc9mmRqWG0dKI8m1ouayf6ok7U3rdvS
RAHzdbOX1hSJYJw3S5jV7QcRLsDMBpHUND/ri92Vkw4Je73WuhTPggP8NAAVCsEU
x6kcf9MgznwyVgQZHAXJ2U29IGSE
=tPVP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-07b4-498f-accb-4741c0a80111',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/7BJoUPJpnpqBbV27WCcRKLDq5ksyCCq/ytrfwWVCCxxUn
GCqeo94KhM9kDMb/A37lZOFmoMNf+Zg8qxAfcSDtdqkDO2yohl9BZA4QckZ4eg4W
dawymxmXRQ3wvak4+AVh2VtXrBGrIMOpD/0kmRwEGYV8hpvG9f2jAikTU5wru2Vt
lG0jDCZMCsR+uxWtubcViD65WiuwTDTnxKYHDqc2UZ113VSDWEPqi8jRsgK1Guo1
OCqKSVZ/yl6b5jAf/+M8tvcDmWQV9cxVOKFvCNnapN6UFZpdda2pvoDjOTmhSoWP
LExcwrYxuibXkU3fhn86w9hTi+G6hSfK5tl2hM4NtiZ9sqtBgf2QIpQOQ8uBz6g1
l9qgyNk3k/u6eBEOuAoeEd3LNIYcXGsYCBdVO47pf96UoRblP0sRmZ1aMwjrKd2G
dX9AkmEERaCqKBHzi2OfQF+i8NLu9T/q/lPoyq6bQ9xkzEecePQkgv5Y0xlsEwod
M1F5sCXiW2p+HGIaNEJ/S741u7exd1PLkCw/VLd6462tdgZomh1KnI6TacjYRf2Z
LrEGBqSpBcYIQB041nO4wNDIDiQpUypNpZE8LBYknhDngt4dv+crkBYc/H81ZAxn
Ro3CddiyH0t0s7+Z/kh2lTV7olC0c+xaYBkEgnLk3aMRve+HynUfeHC3FgXp8+7S
QwGlRnFxMZk+CWdf/RiO02xZqReuvjN94EtncycfdszIlwPwDEAe+FM3Gv3+knVc
TtrQsoRAzTioXhyIRblFdzjwtoo=
=qbU3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-0a40-49b7-8e8b-4741c0a80111',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/+OYv9r0QYqgBz2ZREa1YMv4fZzlF8sNIEljN+daTA9Yvk
f8miTWnTS2Gc8ygM69QN+T4Z1ctmPMNm70NJEMS47ayy5a7QFXiytJgnYBxq9Td8
JAXj7qIZAeUwp4ey9oaRpTeWR2FBkTcMAtjSajFQx8Ohk+1xYijNmTxZDNzRlqU3
wprsjPJ9NCfzb8Zrl/IJdo2Opvl4zA3USOdZ8SzV6hdhscu9lLLoUv42vJVuloFn
+LCfiIsRDjSzQTDItO+F9n/2BwbnrrHqmdVWs56QFUTA5tuoOB/M+EVa4TYOjcWD
r6IfcDJJSXK293QyuSIyMekfOnKcu3qlVTpr9GkF8JJ5KyjSMAbm/JzAW+vquwk5
2hjaexNqVtMkhrn6Gm+UwjxkpUL8cLsuefYjJa66WOIGhCieXdGy5AmSp0xUHo++
H0N1IiMe9bYjXDzko/XdGKxwfX4625+o2zIL1QM32ftqxSRNTYkg6RwbkzHUwLfm
24vnkmPfjdizG8+/OfcWv8Y0dzAYnWlqVX7ns1SNI7Jkh2lu1H5VYXdNOGrg0Fwo
Z0ftDcyvzx3I4jcYke6q8z8Dwrsd5cKUw8yL6l9NPJxBKmYywOIzmnW07o3ZbUtU
kRz81X+Nz3IlPxeWnytoW2kCbz75xNMA3LlHdS70LnV0qCcHU56PMK86Np2kiDzS
QwGmR5stHRPaMpdV7LSZTlnI26s/JFARNPmQn9+vicYhzdcogtJJ55bIklrn9TP5
VkcoYU18C3xFcvNLmls5fqE8c6w=
=G6FB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-13e8-42c6-acfd-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//RtMJ0TvGFWhwnOaVLzM7NiaOzRI6GvFTDhVrnMW2BHJ0
Tf5TJQ4RouM+/A8HyIgMQxCoAzAlY3ag+6izQJNPAEBBSasfI2CnxTW36tPl/CDd
Q3g77q/w8hj1IOxFNPf9Ip/AnFoovAjGWGn37H0+VUP42oH3HnGjA/sF8VEHoFwY
zLa7rs90vQ+/xwng1JO++hid0bpa7yf7BlgomErRpaCrdQPFLSAaOWx1Bl7iFFom
/JZfwIdYOu0UpTru0PjD7ewD+CedCJxfuqsut9Dtq2O+rEHWnvLwPM0D/HshTSTp
yXBVblmhbYPeTAv6qagyQ3lStfyRc1lB2R7Ak5Jty4BKiehk7IXEH4Zcja8rY7ED
Z0YKHzhzb/467Pp8T3fq3PrKipcoNyQqHSUYkjxu26xQSjiCiSalzRWyVuR+9L8M
YuXLJZ1rkPrxUJHVObZs9PMtna2Ud2bhHj51+QolBgGB8fnbxC11LAmBFVK/yXJu
1o9LEHP0U5QRQFHa450Jsccl1wpdv6vxou2C+wE4glRsSpxxNl3xZE4cnPgpXGB4
3To0S4nRVWeOId3Vkl8z2G5UZfojo0wd2cg2YTo7UHgxnMOt2/uryhdpvxzAMeAJ
OwgkvYV59MhXCbUCUSj+fmmhGihs9CpcWBUlC57GD1gv4pPc0QNFO+TxFuetUIXS
QQHMw26WNADtErh1rT5Rgx+Y2O635jDWl+yse02WV2xv1tQCGdVJ9BrmKdVOjwUy
6huwwEzUr0Gmx+ItWgHUmrxA
=sOWE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-1d34-4de4-af15-4741c0a80111',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//Xv+Ee5FvCsCOY5ROROgw/ugBRHIlZPHZ+UpFnzRgF6n1
jgjZLoZUwHYGRfmIstvNjFTCuepsKnM/8vqkPSAeZMQWKha4ANThGGT/ivZ6Fyuz
SQdBH9AlsM197K6Q1lw2Dyo4KuDIfkN1K0GHWJSivsqCErScTmOCDwmlUvq1R8iv
c2hlX5i7/SVOK4RXeoKXd3HcNqx/OZCGxmGcRAB558+iGQhGMARFf1HLAnZ8C8pM
o9+LbryyvajbCrVMhcWhcYAjH7bJdFtX84Mxss7lZN3fu1Fpm8rnpbdJ2gw7IE4Y
7y4Pku8vIYK6mk+25Pzo2dlbtn5b0Kj/u1VTBO0sI3l9IAuQ/50SeuIRI3WIB2pr
Efq6IzdfJMaxOReS2NiNvRV+dVcipcX4U0NpKQXO4bztqa8ShhGGt3zwEoU42pUf
cTb4FV65cF+DE4hEoJs/EDKCZmMEGhcThsz+iPffMcOWkydUNpL0fEXOFL3T5KQH
F22S3YQOLFMxvHuiZ2ywhUgwuTLr288IIjFxjepZb2jIrxrbmDNAxuzQH3l4VURt
DMKrkWehwfuKYxT0KngvArGQsgZNsI1J7c4BGHL4hW6qM0p/jl1X9EIEpBQbGH2w
pb/c2sPFNMSFE+RWASMuEwR5ngNdaAEYKDMrptvuyjBSHcTvTooG3fbPk+GjylrS
QQHyxM7oFaerchjGjzic19ryBFmdDU37jnpMmkLClLQzp2Y0kytGs5nTbcinDHkl
ilJV3wtdwGBn3gv2d/AuqXAR
=Y73d
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-3700-4962-87ec-4741c0a80111',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf9HerywzhzS8tW2zKfd12J/r6Cnh4uc2ft6XsQiSg8i97g
Ztim7VeB65ncd6DOm9/G0TAxckEQq1SS1/oAuvXZA9OCz5Kj+xcAVkPYnAjSj+lq
nG+8fz8ABlqG9XSzYyWbEebS5RlALuUvqZ4APORfHgBzV5hmrAEkArf+9J2xHhWF
7xKcBAxJTv7uD2No2jl1FjyUz5BObFWDodauhUMHOZLnUcjs9xEUCQeiWLLcKNkV
oojPrZqMs5cqY9tnC7ilfgKKK4RTgIjA0jtejLF+uMaQHRebd2WkMTZsyA+iLRPZ
8i2sTu02BcKqYe+91T10a3Z3VlYiaNGI7ebZ+lHLrtJDAcaaww8Y+hOl0lGnGjcA
FDyrnBJbWhLiMysjCIeZS12970gpAIQv33myQxlPLgqj0MEt19GWtghKKlG0ICc6
6bjTEg==
=HzDC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-42b4-4ade-91cd-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//dTdeduAW14N4HCUsEPDHLxzz1tODCWZ+fXpPjlwCXNjX
yaYE3SflaktYq22f8S7WGCkD+tW/qG0BocdeRme7uhHenqOw7G1d3WCKfoat9iOi
/Mg/yqTIZnLWP/ul3UtGAhVa0KupMrWtx+gf1GYSASt4vhP6uhbjV9TZ5TXTvk2D
Txjybs4vW3HEElUoXkmSr8prqbEM0S7VMag1PL//FdT8A8/OdOllC4FbbISJg8EB
nbRUQjVU6dZ/PO1+ktCEhSv1qN5Wd5qCnnQm4+FbeMO8f5CEPGTHw54lDvqCqXe1
mwkxBa/MVWnqHq0W1v4KsSvegsHouGH7feUYYIVlp97abP1qe7KMDkTffNUto/p5
dqe3q4MYJ1cE9qzooNIfw5oz2w+43+GUfpgfQ1NjAnXoVdNC4Yv20QX+95fnjmJ+
swNdToBJ7wKLVqFz+c4+TMkqnsGa19kKic69IlYrRil28niAGNsgDKWz39J/K/aj
vvLw9ac71hdxQqwq8/bgf2kxglJZcPAKNWQ9pyOg2KT/gSZuH+LpP2GNpbqJcosq
voDNX/7GT62k3QPaZvb+opTYANzcJTyLxMlJXguoEkfkuwX9FyBpUId9kM9yHiJw
XVMzpTL3X8D0wTMMcRZn42keV2ol5lIAmcCyuKxroUGUarcthknPa1VCasYWH6PS
QwEYwAQbN2yCqwATwSBKFo3oO/EwduBZyN7PqcjzzGiykcWwiGNyQk25U0ZcYK4T
PXqifE8xrq9lz/ibDcKDJodXIr4=
=qnoZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-4674-4dfa-9811-4741c0a80111',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf/YG3OVMkcn26Kqt8juu2eSfPf5Z5OoyzR/ItReIR3SxmF
j7Njm9CjGyB6mAwcLvzk+biTlbgmqabhE486c0XPUfroqgj6L/UCMam0lBO/zddy
JeXcirbswIVwE1xrNCzRW+6/8sLmoLnjT98rf9swRYtTyWyjZQpHjzge1N8FcGVJ
VzQFiKzxyuT11y5SRapCkYrIjoMQnCFP1KVH76hnEBsc/nHbWNmyHrUESSXsWQLb
9olHYIuK6UNEDdOWlheC/SjZEcdbeFCfBU121swJ1VjUbSkFOlpgRGFSYZzYyelW
FnzPHfG4EJYZMx8Jw83l7OdUi3h69vqg1k7jcAAuDtJBATKsWPxaB6iOA92lF4Za
SzK0cgzzwR1nAvM6O9XfndAr2dcI50vw4Idubs5s0y+uoELGrO+uZhmAuJPC9YxP
xXw=
=asuZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-5500-4f3d-9548-4741c0a80111',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+K73YgUDUiVXGjgLkS+/QO9NXCRux1csyeCGuPzVXG8bm
Rv7IkU/6DN4eXALikCtDo8GlH5POBRnQPzkkfrRAgPFpFGOnR4J1EECk+NVebnNa
ROBquGu1746VUtBhiNImh/TnkYsgWYts+5LpQPGlVTJ8+59crnDW+noex6PCpl6l
RYBDITwu33J7U7dWjFPOmaQJovcBd9YFr6hh0LXVXG7UWp7ApOB1EXXGGY0tDi8A
UFGxqTrA2/T57xQk7zaugqHilr5ZtGAs1h6ZY8Y1A2zC02/M9VQz0jGApI3EFSGa
QNlC3AUOTHpsHfTl4yYfy3FROHGaAU7rIAuJqYng4Z6HJuNpR4PBSM5Dgu9VgQuF
ZAgWy0/JYHTQJGhA/ixicYCA1BkUcctkSS6fT94vzlvOaK35zjCEg69LgaMRrrFh
DRXxAgyRut4hzZD3pJ+B1ICU4s4S6XH10Mqq6gFQs7z4ZG5CY12ue95N6e//p6Wi
j0uz3yvpmaPkQRT5XGfwPf0Z5yeeznVZRBtavdR4AYvMTeuTMDzLfzi8giUjaTpE
rYLeAlbwnzG5Br9QXdKuJEnSMA2OgoOtJBJhm2fqpU0S5M+DAyWuZz7xBaMCgve2
9LS0K6ggU6wVBG+9U0wjVbHly53KuqWfMkdoFsh+qrH1V6rxwDbocHExq1cA/uDS
QwH3SHu4pVhjPt7SHu6PoY30Dg/U/K+oEW1ds5Gt8HjGjgoZtvrWVZgan/getLfs
bQaheFCkeG2+U17wcwaard1/8iA=
=n8vX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-55a4-4c63-a343-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtYaT5HCTau6HYcy9faLTUYyYhwfnlm2ynerXCHaWsMQU
C0uJXQ2UHJUS58OhRJD7p8IqdugZnBP/cuH4OC3M3pLygPdOvISJiBbvn6UUoVfj
zkrf8Lg4Okry8ZLTrLUX6n5lX0m2stXR/38y9orUmCAgI3Jh8SPLkJP2N9eBlWVn
XSTYvjY39txXOE36QvDvA2GRC9cgHYrcu/ri/TtRY7xr4lDhI0QGo1Mx4SzMTuCa
+76qFbAGrdRd/wUjWVpViW31zSQhBdt6eHMgACzUBax/50CPxNao8N7EQxv4qLHA
tUBWnycteeSR7oZdWybz3p6fjf0xofOioOf6dYCur42qB9moVseOPXH/uJj8BCRg
pnwwQK9q19BOymSLaAehySevFSLvhPUdB9kGDiIpla+PRlKTwHIIqKJ3+2MDABgv
xNIkbSPPeeH+K0N1kNoeFRepud+pl8DGot2VHZQpf7L9f4nX3AoTMpAid8vmTdPC
nVt9FGSTEp18xQ9EF+M7zwFY8I5y/pcoK6Me50ZIFy1DB9/Np1gBRSySJfI7Lgm0
i5CSGsZk1gumfwx46RRj0kPsdzeluG3Bt2CDY7qg4qtxpbfOBKs5ER3ggBRkfPTd
TwLaoP2OzkvlgZNmDeMT29XW4S5hLib7xTg/mvQRyNLmhFaHBYJ6TEMKjzutYBnS
QwHO08V9mZXoVCYouvhiPIsslBU/YVqZv2axEiHACIJunuC0W9ZkvE7a1n9BPjkN
Xe/Y5lZFdrfLB3G9RGA4dtkrqik=
=xr+D
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-5da0-4d26-9087-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAw3pVK79C9x5JdTP6kgn+s9d6Ug2LwSHx2edSio35KWyt
/pAgwJYJTMqlcm57Ee6gvbr0dRKNU4r/aWZFOxksKruj4p4C7HPpXP7+THg3DjL/
jOviRNZzFsRtNR+oCuXhQmpawqrVcKgZT1hgvGgU/TPOWPpYzT+sXjY77eaTWpvu
hS9xIfriJy0mPpxLaoERuLWmcUXtEzCY2cb6vEmRQdYO5jwZtVEqJuL1OSDVrXWe
UU+/QxgCApuDMoaa82lBqMyX4X2zmCVr0U+lhMB9eRwklZMPvB/imTuhonbuf/dV
SQv00C5Rk50OghegqkVIxAbaSfGTYUErowcL5GP6Mc1A8lzzlo/i/e1o7vXuZ/IH
UnvqTAFrFuCvY0rko6MXUN6GiMnD+ZhckzBFSCg+vJa5898VlPnuyzP7FlD/+4lI
2QfFfrMyfQnUd9CglaOv8l+xgZmjyCGZ3fQ7qyImmEogG5yFt3CpCX6jjNovroim
EqJbEJSPNLwh45+rl6gAiIgo2pTEE/iObugFPtCy1fgDHVeN3wwO0tsKTUQpV5Vq
YQJ4BVJdhKEu4frN/JF0K0046I/sdpb2KrF028dQKBMJGn5AHRGJuPKGVhLI+0fB
MhcUNr3OqZ3EzQEuJF3f1YHjBYgQa92Gbk52QoE8O7ntPGK1WaSrKSVPgp+KE0nS
QQHU2JLetLy0guB9U+mx9p8eDWcIh9fIHMvVgIv4RoW7i6js39f4HvYAc/fk25Gv
b3v+OFG3rKtltWKN5AIG/Tv1
=U/Ve
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-8398-4e98-ab53-4741c0a80111',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQgAmAlgQMdGV7OH2rhY9uT8p9tL/F/BPqf7gc33/S6SQr5F
zTtxR/QCsW/NxWDtU2ZSlHy7pO9Bmsy8INQB15mjLYdm+lSYMTkB0JOkjiNQsJkV
SUX8jPpsbSVI6Dpy4Z7/Zv9lwmYQN7b8ftp+fbpJQaxW8MiIBIMyEYJZS5PfpqwA
aHlggwW8uZffcj+rx2L2/Hf0g5ne3xjP7OZz1xKVHtn4tamj2VYzXajM6zTVEk0S
0QHbHzP154YTW9+6Yyu7nuladYo3TWDjnnlHWnrzLsUUZmQ2aaxvxutPlX/G04mZ
gNNg3I5fi2vAw/NHFSTBJmfEJbrhysteEL2UFQSojtJDASL1MfV/Kv4y9+PTieMv
n4saDCDhMrGraIxB7RgI2b8NxWdCkfgDritgGnet8pJJlFD8lhUkn8g18ZvOLYLH
a1TZqA==
=OpOe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-9448-46f7-a2ca-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAhdUkUYX5NCoqDO/odz9poGh2lEB6Vl4TfNOKaaNQzNXh
5PHfmMMTwh01L9i4C+gcKh4l2tORliTnRZUobM8ED4Pv5fGHOypkA7gOzqm5mLrH
sUmbt/7B7AZkxGAvvJI/qZ2f/DZDhPTLoRi6/tqnOA1M5Gk2GcOMnbHW1YJsxFr4
K6wj/H4UC2Ws0VDDT/Pw36MvPdVjavcLvYuCPxE81LnL+c8JkAQVgcNx9R7lfcK5
J1u7Bq0QH1JBj7WIC5eYMGc1lw2hWckQ3oBBY6elmt2YhEmBbOVDp1iyqDKORKgQ
SZ06O3exX2L7Gv7AcDQkP41eottPlfC/HscbXCqgVB8X7lUfUHx6fHZTFZoJ0EUj
FvGslPgOn9cl5m9LMlCQfvudWfMO87w6xYS0oVCljvIhJtEExLB98GooW85tOmaL
SPBAe7qXwQmQsgTm/TgkCkA8nmZv8lsQ2tdip7Xn1EndEJ23bvDGKOcMVPBiW0w1
0KQeRlQjpXiT8hkZTzzlRco2tUWeY+VYIdV+vUGrj+UV833jm3Z0s1l7HWAnQngv
AK2s7X6VygZ9tuB27yVuMWc2BJ3vGXwSLFDAXjl7LQ8tzveeCbLSZGQ8p0wyUNq8
8lp4VJNYAMxO/hQSzYJiYf/P8THJAeYFgIcq0f5jqmqWDWjPAzrdKoWN4Dhk6/HS
QwFePozZ3B+SYdt+mSF5FDqk4oVWAQ00b7I/zF6+lomh2nHV1RLWAu2iadZbIVJ2
pCemmMJgUczDUEWrcSLYSmzVaY0=
=cv5Y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-9524-45a2-9ea1-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAk3XJB25bpg4HI9kytCJd7jN+7LDejoAOa+zwubPDR3Ui
HdNWRSwYbaVcdWVYSddfabKjOL0yG7Z4Col4gcurPCE+AZWLBv2/uDwV0k1lkG9e
NDBU9qI+q237/HIJgBUVeDZlENmSDTS+6ICx+Hrf+VpL3RYkWVjMxY/Hk8nPs1MG
En5JXgKQ4ov2KlLdjmLLcXqMl0J8uDZU49o1ZJ2hI8ratFtS59S/wsZjk19mgqte
Du9wvhs56UqxuS+tQ+AG+iu1z1Yq8zgY82F/oTvcU70e+4xpz+Q+CWiu89/+7S7M
pZ1DuTeYdJ0DGil/ybjdo1Gt+fk0vdQ8GGDUBsJAv/t2AxGnoQ72Pyr+Tcc3Bzim
wbW+4atuaTnUBdz46gp0/Lj9Um4+kyG4YvrYEqAiJa3soIdqBEYFj51+PseOU414
JrG/y3br/Cqm1gnr9rZMmcGKYKDL7qzHhIjf1fdD5vqhMGKCmnyScCiVjqfo4LkL
GVSBQ4lv80Yw4/eLcfqcu5eEejkNqBSeI7XeBbC1fmKXAFmf7PUr0TgWktSq+FEh
hb1xPuSwfp33ZvQQRoWbqRi5hJ85RY5FizDCTIRMc8c3Yn/84qbxzbR+yhJPMgkf
/dlS680DfPfgvEDPrHJRV7QgvQ1cWjNoGhKW2mmIQk7cy0Dw3BpR5CMolKVF7G/S
QQHbH+CjwCVeqAEIwIRrOomrq21UV6Z1xJFJgbjkxaL1wFADinF0h0qTKC6a6l85
34BDfMIe5SCX97559ewAjJtv
=dED8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-9c30-48fe-9101-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA1ASbduyxg3YLhp+0qklGzTPoSw11VyEZff8GAMxZZLoB
K/vFampbpEQpLMtFvHy2TqoO7IdFmHsVfsyb72UpBNaRpkwTiADYvAlqaoePhUmW
nXlbk6oQoxg5Q6VT6760MspL/LV++OIi5LnWtf5KmYfeLEDl+bNHoFkNmtsw9Ayk
8eFCOna6EKKcREESiGfeIvteoyiOP0kr+ooDEQTltQ4Tozt/T9MPTMFtiC7hInjw
+cLzmUwIHKOFwoI3xHHW9E1NMZIcIIiFw80SHtsWTeJBsiQlBoiR0Clk+RfgE7xg
UsQFa+W+FhsZe5CykPufF0mde5wHF3U9cI2GF9vhRS0fuNCHa+KbMq/ToiKvoLfc
8EYW1f60saT0PDDrwra9LW3urJ83Y9WgozmHzgdCOVl+CAGM04sT4y291pc4cp3+
swa5pj5h3UImqX11OA9zZP1xCrKTtXibLoRinNTwszgy63cLB6BaHm7EpqkwoASG
BvRzIaMOywOR83LePyANZGqakF5b0SzKGUH1KUlKrToCMxvDqA96EO0J7q4QgV1D
K88H55kpv2Fgc3kftTPkEtKPSwAQj7YNusXm85leRF65d6xHWInmyFIqUI/pbVDE
UN0SKXYxBaLuHC3D2n+/hIxsLlEeeqENoKNfa4rIGHilAePVo7pCgWf91719Ob3S
QwFVZLwRC5FzbrSgZ25iP9UFBAaU4Qsa8jqDPOwGBgqrcqlmWmuGaV1CdvvP9Tos
d1bkpFnn6zxJ96uRs+Ir9Hvpiz4=
=6ZPp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-acf4-469a-862c-4741c0a80111',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+NDYgCJMnLR5hMYGOF8sFVC3Lp8VdGzhkN2SzYbgyyV8b
Sy10vzsZZHgShexmiD1iRPX32R1Bqqwlf7EkIN7LCUH5IuqFosQxKwxAW+ReJJzF
9PbGPFWFBnyHJegE0r1uWVtEWVBbgLufiq+SXlcFJPS5eQJM2RGRELsTgLQQL5Ss
oRlACfDF4OJ82V9Iw8VnoPt8WQ7mpVTMcFV9b/seIAANeksuvTGSQKK0yxHpR11R
WuNAYarD+mJiyLneOclf8f5suc4iOZn2mOn8UoJtoSFxWJhV7JSeTQU+kB8ebRiv
5t/L1JzBgF3+Ckb8gVuKeVFGn7gMyS+21JGAFhPEZliI9DdMgeSV4D7oA7Bw6pAN
yPd0oULUlgRUSDxMnmhSQW37XbBkjReJOhoV/GKv+NlSmxDO2V14i9tvzCqCLdP5
1a6FIMqEdFazSz2UqqU5XC3AAuk1hRoId/TzQoZqslmQpvBCFtTvwx4xLE2t/0OD
nA/bsZlopHJgy6Zx3RE+NSzQV4NAKT0yLw9VcxNTZH8X4FtWm4m214Cxth3AQhl9
HNLQW9uYgVPXZQtydGBJHbTPSfakXrIXlcd0FzC4dbz79G21sLQThaBpWwAKClRd
ar0bnfLwPSRLQa7JIEkiqexOvoy3oKXMnbtp6FV2VMTVieyxLT1ZozQ4/79KMVzS
QQGw7WjU+GefsiRD7w6IwHi4YnPJbIHrPWrI0UPXzOFycFTzBpTIVMMb+7Om7uLg
gVUDLREnwHnkc1RYq1qP51UQ
=RN7J
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-b1ac-46cc-aedf-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//dlgGnSUGQ87NM392c3ZrPxnLiNRKm3zGjGpkYTqnHHRN
uc1bY/4m11JMSe1Iyx8VY8JOwwZoXoxdUJ7M6qmHTS4EoIHi+WC1Ap85G72SOZtF
fg5dYPjvoQp5mbpF8pQX64lwJC4sLxEGtlQwwcMU80uJhU+L0s2ZWSCcA7C3xWQI
pKPxyXHKI5cB82hOfrAvB/fGumVR02jc/Nana3N+p9ZuLUz+NmerT3JKaUVvH19c
S0w4v2UHJvSGWyiWXPh+1PH2UFtqigt+w3CWxlEnn6Ht2tImlVYUy5Zaa6dnH1Bh
ECcC1/yt51nvk/VtSQOymk6HgLKIpV18Nf/ujuM/Sq2t7UW/G6T4HpyObcB9veNq
FpgYZaa2xyHRxNcone9V+OtnOLFoatx6AwK6yCX0VOT/xmqZNuJAhn+F1YGnaW8g
MsWVWZOrMBK97bVsdq1kYZMTlCC9FU8XJJOQPXexjBRB4ky9pFicHH1LI2UGEjzR
jw3kz8DQ78VaNLsy0fsPxqfrGGQjGeO0WD6w++NRdy4ngzxmf4q7R48/63+DoX2e
R2qKaOfHiU70kfA8HK1PUfj5zrC71f3pBRe79ExLjWhakbCbXftHUGR9wu7EBUUs
CbpoodBhjFhyTi89Padjki+zuh4bpU0vm0xrgl6zUX79CBvjb/1VZF/a4phszb7S
QwGGgop9y9rm7Cb6D2oTsl4kADHaMPgQVztWRkOmeIWwCejnpHQnljEZrRTht32U
w44caHdGpJsN+pHsWwUcNSVi+Z8=
=myhT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-cac0-46bb-b759-4741c0a80111',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQf/asyv0YR1R4cLW4jyFmTHekfLjsDONE38/SAaqTAApcdX
3gOMYfSuI3x0Vkr9aA7qcx8ijN9RIYA9yd4TWSzV2HfBVLvdYGbnv58PKa7Asf0q
ZFyGHp0f4RnFmqixiY2tfjBsUnEcyM4UK6MeOqjCdcVTv2qakZLc9f2IT5QyiEJN
11ryVLv04hDMxuJYTVRZVahLuVU3sjkWRlNAvfvB5bct8T24AsNkh+rJnvWEMnNQ
zdWxNjO6ngb/KfG/pWuSRcbJqyzCklVa1/sTMGT9+ZAIYgONXb2wXYzMxvu5o3B8
dZMBEATkkGTwyZwJUenUwbCPB7KFoqeieCXN0E6zGtJBAQmee7ShjwcrDTCQzyAD
EZQ0DQcQIWnTK7gGNJV/sIQtBoYE4/HzcqPy1h/HYqiLH8XNSfGUb0P6cZFN6/+e
kyE=
=HkWs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-d624-4a81-bb94-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAiGUfciYNTwV1pkyXZwXecebrDp5miewoPwVvHfXUQeLY
8ABOvhUDJdJ1s80WQ7w9n60lLMcaUyWf6itcjXSXdi9c0rhlBBO3x4jbCG1YAOzg
kLiZa4NeHUpHfzSdcsZMduEaoR+rlG44OX3hUVivF3HVC+g+Oe8r6+DPvuTDBjnH
9PLRHQWx9xabGmKxQwacZG9jE/j1xDI4uB4Sp7KZ0R4iiQYmWZrx31Dgeke63jsT
lmpgytIP1EvD2XgWKn+n7MCWDRSHg+ERYLkXtg9a+SYsi2j1PJjTzeX/9LyXDR4S
NCcKheZ/ir6AMwi3UJk/PYeRfiKLPrxRReo2sLJTv6qynHkTtJbUshaCmzjx6Rho
3Ahd+wAO/Mal8jM12djlp+1cBpEUwPfeTK4pVCBQYN+Gr0oV8lQ3Q1S77TZPiOs0
3z0JEzp1vVPcNZEmAQo27ptR1IZIezb+bbm+yUbhCAFnzEgjked035vqG/rJshoS
zB9MnBnmhSB+MshCarbOLYiMBbL6jY5LMiFSNdVslB73CxOMK6Zf2siuIVY4+pr5
Rn6hRfWq/ewOtskpb4RaS7IzXkI4Evqb9jdNslZVL8JxjR9K5j+ij1QfLO+k06Yv
eA7ZGtI4LJQoz2tQR0RvMHVxepALC9dczqG6JSPt91/9fmOoGR12q4acg7L061DS
QQFrgp4mlBacFU1YCT8RC9mmw7tZ7zLeG6SiOVIjZLmFAfoqEUAFSR58tdRyu6JH
xlWKB/FRwpabrK5GwCGxx2qv
=iJT/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-d9a0-4555-b48e-4741c0a80111',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQgAwQ/NrSBfbmCnu2YBhXKlvkG6ODMX0P+e7fJlNWvvMEjj
m+8NkENLxCI52mheNd9o/Wr1bIirZY/o7JW9HQ0lTbQ/oy9kHLal3BwjsFYYCWi/
70m2g4609dluMrXOqvVLCJSUN42fzIgeJBn8YyzHUlUDMNCEAGxZBWYD83yIeWoF
+e1J2icRvmg80xpn09NZv/GH9Z9cMIlevrStXpL9zVe5XmaIKwza2WGppwlbNucp
fFtHAgf6tc+x1iNglN6Ysd1nhu7NydNHBA8gcalDAIM1OaEBLmarp654cJ/hGDhb
YTe2SW3wtF+u6StXqwCCJqlhJijGMo7i1kPwTiOMX9JDAVy7SWCSL/lLxxkS9Ui3
bs6QvPLk8bvh12DEGmGJAguiAIaAY1ZKtiWzWNoQmjTimPO3M/r9rjyIm7nMjAFS
K5XIfQ==
=xMMw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-e34c-4c09-9a5b-4741c0a80111',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ//dyxsiPBPq3QAnB+l/EIJyb7B8C7m0lMOiTY9i4VqF7Q1
2+H+Sxt2sm3LJkhGNYdOW/aMyIKTrUXk6RYOEsFQrGB2y39S9BUAfCVPswHeOqoA
y/5ofuMMXUVcNzkmA2ISkR41lS6dGsAZ4J8euojUtwzQYyz+HGsTH+H8pKfmePCt
FpAgPiC/W3lrxd+LMIm9lVjzyIhmAy/J1gUKQgYzq3qVG3H1Rkie4ldcJo8fH+I0
j8dQKkI2q2sDSCfkpcqymbAmm8gNa/1vI7k2K6O9xGTGgN1oT8roDslyxGu/HLrM
x3KmO0KK/uFbFZhPUG5ON7TZPLPZ5PVCYDCF2fXyDvjcfIHPANYTFgHPLog+WU3h
4hl/9WulvL3dMekClP/sEZEn1fbup7U0Rw/HaFqKZfEWgNXs+0QpR1JDwq1gwoJh
DvXjCnFRl8xI7NJrBG38V9qkLCltbaN6sZ+GQj22aM9aRfEqsIVbTmNxVBsVK7Tl
t4xIixes26u/xZxJD0NXXl3PNcjQzqi+KDB0Fv0FKYoRIU0YhFXYLvo3GT3dp5tt
oEsRF72mAlsyG0Ak7IyDDxKnAbEXIQ7FL3YnlnNADLjQ++alOK5cYkcrR5YWfjwJ
C0FiOC+5TLa+u0HOjqi2b49kaG2tsw+ibIsNVypzpArySf98cP/i32DzlTGbeYrS
QwGW+0dxi1pjilhrMGb2Ou8mdrJEyeZdfLaPTMhD9qUhm1rp/qS7LGNYXzYuoNm5
WK19aYIoUk3fC46JDEwu18qGrtY=
=4Vd2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c999-fa2c-4c59-8331-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAqBt83g3Q2kSaut/pq6IpY4fdvtzX4fx9ZJ4HG/nQTIAy
xZ0pNIBRGlwLK+L4XpfvGl+OYa02qPUJUgQI7XDM97QlqjcOk1w04Ptr5+94CRGE
rjSnkybWB+RvM3T6Km3zVXUTQCvYv6JO774F3GG0/QyscKHS6VV8MOZGiPQ2iiIa
3NwcaJovqY0y64q+lNmvtCW/82+1hauVTV2HB+JJT32Qs77Zk01jBEvBM8Aymmx+
qF6wzQn3FC4s5fZ4photxkS1FeJiPp06z3oZlGCwgN/42W4qcTH12b4VPSZ2kwPS
+sZwkU5UMkMnLlWwxcoDkqrKSta9TQZKHS8vYmT2XZxwAnoCy+6uJwP9OQn4cE92
HI56e2G+UWFfcpaPf9oLyAQqkHrKivXzyxudDPgXCyZwzNNAc2SDRBweDjSKNzpf
cwczlT6n0F+pBTFW4rV8SOJolGVMMQC5vNbt4DWfG/WY5FGoTPbJRugOHFMPdbTk
5907fGwHFVd9iySrNB34Y5d5HqPPka1ly2Wb4lvnSDJMqw9//eUfyOpqYnPc4sYI
TCmteU9ghGF4wMWMVizfN6YoFNuV1VNB3f6P5/IpNC22v9RM4mCj9wUZQeo4xWj8
92pL8g2V2eZIZImBYBopFpXIgsvhycDTwERVEybaEFpxHWKnGMRpGd2AhdVYIGLS
QwGPkofjSGeSVJRp9fpu04s9gtBFVI+qHhG2jmk2I1qGM7pSw/lsX/wNh4FNmTPQ
Fafts1eBARTMPNOrtvmNRTkwkJ0=
=wvrP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
	);

}
