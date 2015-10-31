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
			'id' => '0009ac3e-0adf-43a2-a923-7bf465dada8a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//dznqrdfi/4OTO3CnA0vlk+w/CLYFDcuzBMM20yef+XA9
nE4SOgMu8yHzH8BWSxYwAySzxXR057FkDf8rVsz8bzq5WKcLnCHXJs9JFQj7UKc4
VEhloCLrvF3R9So25D5KcvedSbKBfZXFzpYbVD+saU0Ht1esFMNrrYRt/3aRH6rG
qX8vGKMrqzslvbK/IuxYBTAUL+5lgj4H4shaM6qPbQfTMuNNrb3C7ue3QaJaOixC
QHzkf1EH19seojzXfWtKhB+r4KbqTWfqLpvCHx5Dp7zcLSeDDSH8Np+Rltenp5fc
Rio3TvW8QHfFlGiVg+UrTmgIZA+f5dXZoBVaPMlTUkOwyW/AMEFYvwa739TPxM3i
c1dLPVFKteJeknhHg08iuQ7QZlSoDVLcA7Hs6CvH2QR1L2jHrefzdOc0Ijk76lne
wb+ZwRQJyPYS2FLJxGtI9NErGVPB53RJ8P1IIz0aPT9/iV0QtAeQ0jQcFYbfPbfq
3dRo6AhiWUhRPLUqeDAnAeDp95T7mvG+xy7r72sP04a+mCOI3Rlp0jl+DuG213Fq
oKDH0l/sF3e2ZNwT/b8kUpNnGPfyGd3vwH/u8g/sK9PBK0wO0peXAgc7u3RiKHVQ
kD5Vttc/DGL4GQNwN3Bwh8Y0KRUntfoLwjVmzTmC8roDo7SypKBq2/1AUflt3PjS
QwEdOL2pyIFUkKgjC18tsyvYRitI7U/6++R8DGNxhT86bzQVITSJPBawcNz3R1VO
IWTFn8Qw11j5qHKVr/LVnR7cT1M=
=od0S
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0065a0b4-9f64-4109-abd7-5afebb1d03ec',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+MFiCyhLHat+2C8MqRjo4W+k386sIhFpPV5ctPVpPfD6s
FDEgj65u0h/8FwXWEgVV7qMFbQN5/oqT6305on45EpR7lMKTqm8YI3OSO1H9PEB8
tGKFd5AINpZZkkVpWv6WqHohiz5MTd8UHw8akIfYFjYylLBeS24Oel3xBdbY/uxQ
pLIqecRuOWgU1zbmyCETiCxHPel1GKDjEEaCH1Q3hrLrqJu2uJ6wEKTEplauXZ0+
I4qwzX2PQoVH9XFbZAXb18/eAkMnbproseWhXtQyr0U7dONr4Ptnj5DhHBATEOax
6amz9eT6qIeaet+0UoUTL1FJ8m5HJuA2TZmBqEMT2t46vMRhgo6vD9joRBDyuZn5
SDibVRWc4PbZfeAWTLh3sV+rXIQUXITK2+sF6C8rYQtiTkvw8xSI9eO7fqsu3ZHQ
dsx+Lr9kp5dsPPTDuoPf1TAHIwJj/Z8QBRD6LuvftTk7vzKwF75OPWhan3caNose
rJh62xmS3qQdvCrzltdTrYgB06rowOIL0y4JnmpgpRKL3HxPkvmc4xJttdQZB5Ek
bUkhUpdTdU7zoTAX/bLjxDaWFTb8JIvxAPFfqbSdaH6mxefrZTAlx/u4f9nI3qjQ
K5IbIXd8NeZlhB5RA4mNnGJicxiEKHPpCArGV3gO7l2S9RvFe12YZOaI/WLoxSPS
PQF0l926pMy1cN533xJCUCKS18dO3uiEexSa4rACYcXHSQG2V3sYMP0R9T6Xuvpq
CKJfeRsWy3oIJYB+HL0=
=jCsf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '08298dd7-99c0-4649-a892-cc589e766262',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+LlNw43hq8fK2Iv6VPuRLI1d1Xe2f2KaPoeHxMF/wePzU
JUjCUat1oT/0DT2gv6AkFR3hTWAgAMfjTd39WpCLnDlc6yoi28I5W74HlDKq7HCj
qv50BdLsO4nNgYCNNt7BKUH5/56ueW6Kc6UNHb2nZjoo79y5FszbaKE/OfG/yUqo
lHFh97UBEz7Lfy7uyF7bMZf4GeW7lxir7oa2o+XMruEEssDnlUACyHR37OYK/pBP
s5nvUNrB2F7ojtYMsrXFqsKMWTtbT+wIZRS/DMWO8P9HJf0780psejIYy+FwmGq4
r5vtBKS97mayyh7ElQ9hp62RmF1EmU6yrzEwbB8F3ei2p5bZ+2lv37I5/M++/xUy
3qYkZps6EulsNRYN7HYFLWeI+RySmYRMVTrWvk3jpJn+j7ywDD51aAzlnPMV7EyG
C85OnseYa1hAS7OLqEolUzwh8AdVv1CdiaBhr/nltMqPSyhRTd/MRZOpC26nY4st
/ZIHJ27FmfNNACf+la1x2+768mA4UILR2DGbLBfvjDBJvanLkyuBsGbptT4Gx+se
Rt9AieDec8GCwrp/uu0FWI2WjqFcmV7ruPfixoZaZXkTv9mHs8SE4ekhoECpsGwl
Y80jeIeGAkjkk2n+O+HFZHEzNvT41zNtFqipikfOZtAknplkkWIxfxZyvNbDIqbS
PQHvftT0lhuZSfV4RWkeOBIJC04RTZmTU9ijPgCp4ROZqBB0pzHNtRByYvxslf3s
aha6MM0pzPF+6aNbupE=
=qjHn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0a86452e-df95-4c87-a68a-4d847aa1c184',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAueAvVm610szH27S8g2nIVV+TWpYJ/3P2z994X9XTj9rY
UJ95+9JfESmALWValaHmpZIrtpE7V7uepqRIoFLA8SZNNo3UntUxsgoiFlKWo4Q9
p0x56GThhNa7MM5lrk3Ic8AC7NkPyWywZJE4zoBgvpe/5rnOC5hpJpBUCXH9CLUe
cXqJw5C+WKgf65vEXylKwjrRMh7aNzkqzchIr4GcazjZUXAuDd+yTBtqQnDTdSxo
K1rPmUjYtNKmSfVa5ttbFxYTFBC6xPxwa3d6zD6TWmXrsw3qZaKf9o1v1U9GN1oD
y8Y+C7OJ+II/Ie1Ky7QFwBp38L7K7O4drkiF9MZwWZSpiiV8NOPKLKsuP09z9Yoj
kqGNEBVlwfnGYFIFlIwPxUfYkDF5kr1vpb0zQIvAJdIxoR+8EPitaVSDL3sgZsQr
8EkrHaIhNh61kVykvoerIY4B2JrHIFT0zn9G85n0pvSuaDusFkZSYytX0N1iEA6b
pCI9qy1UKiedkOWGFqUF5G9t7lHcM0bUW9ZGToQYoUbEpXE6eljpMRyLV61lUehU
PfF4ANGXm8QhbOvQuHJ1uHI7W49W5kfwe0P3R5CseeTB6MIiipehraoe05Lpy3bX
ODqz9Ds9eihxx5neJoAumhe92ukV68ntdHCC/CsUM/aano6IrkioagT2ecDdQXXS
QAGy4m7RzF4hhs91MoLgE0nsrh4IelvSYpMoJrWk5jUPhD9TAFYbMywEW1YJWZCE
XWZl7+Z81FSiGDdJmpwCwA0=
=LY0l
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0b4ab99f-9635-4fc5-a7c3-a3875368ca58',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Z0N/O/E0ygCls/p14UHwmfnn5pbTkPxqljVrr98NvUPO
9zpEmO9H8zHgIK1cxrP9m7RhzAd1CiB+QsncwGoDtePvCvxmeBseImP8amRlf2mM
IBsSky+fd/9gwLB13OCZe8AeNwpHy2GWd/MA3V9b7V6kJg3Yf4oZ69BWSe8J9RCM
L5pvJ/y542lxUpMTG7SBGwaJvl1paY0CotH2JzvN3IHdHmERfx8l8mtVEBkGikAO
ZtTClzDQdMGoGM9KcLAzf1ZuaV3Ye7W1DTCogTKgpSHNumhkLJ42EqtO3pUhnsQZ
0K13hAqJ/mPWE3TRxAfC13xAvoaNL5fowxaR32+gAj/dlZzzcQoaVJnRxuS1IwiD
wU/FYBBrgxg4lQRe8vlLmRgW+78NF8vZ+IqgSajBJaSEzE7BSnPw1M0dPi0irzcK
IBVbYHshFLI9ZMTrwpbPo6HY+bPNdJkP9DVDhezLnidFxr+6vAvp0VEF9X6Rk26z
gtRrrfF4Sosmd7XuTZfKXVkJdrqRraSvsreU6xrDV0UZomHDaEfyKWjxnSofh1wf
hnHrY/+XpTMounAZsyye00ua4K9EmUVRmIK+vlgyCABIyctjf9sEyb/tXgtPCOso
BbZ2wPcubrhG9IsMXgRGk4o70xTcDerM1Sm/mwMjXAF8NjyNuqqkR/pJcxkdro7S
QQEHtDw/HJtdFu+oAULH7CFnRznA3ql7F61t7C+f6Uo+vydyweQjZqFBrKiY3kFg
6VVATXWy8Dgvvhhn3J3xKxo3
=yYyG
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0e002bb1-5808-4522-a894-551809f3438e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAvhv2ra7K2DUZ7b4bYuXQaKriZX/GTecxEn0Z+FAol2zP
F526kLykfE6CiYskoVf2Pm6Pj3t8BAWQl6OKd7NFJMuuft9NdnLR1RHQxmtsrKQZ
KmDh2psmJ/XxLUMCRfcXx78tfWt3l1zxWdPfHBCtzHn4gSjikUk/mN/iSK63dGRG
z0E3HykTo7riPJ8oxRR25v8sIOG1v9x81Xv9KZbqZqWx5m/WqpidJl3ZMbpgZ/Gq
5fOpVHOdmOrQnRQTzi+hK/fNKkRufNmi7LaWJczrM9wXcPRzV+KBtmC3hrfLx4uf
FJg8vZ2lh1X7Da6aKMYbod/Jqtfyz4fjSKeljNzyWZqVJ42lYBTecemqMMXEwliF
4sCV4tNfslfZqneFCuKkjtOgqJ/7IL8K9P1yE13iTDbeQBU1njelin/RojQHAv1q
eziTsYT84DC1Vy6Q9EKlfJFO3G63K20QVzUmUdVz0OWlXPmVF0VdKhSPjtuK2I8d
9c9TRhgrv/f2t34UWnMQ72yznG9KRTFs9bcrZhpSAIX3JLN1aF/4ZjvYPektTdYx
vlqJbBEdya3aNYPCrYHgj8wRFQNR7KOyq+1Z4HguuJEkHhAJIJT5CGbMh1TNLMsL
79WdY7R2bNckKH/8Ol+pySDUxuKMm0zgu4KnHbn5Rrc9RIqkw6JSm1AS15HhBqfS
QgGNyuJ1dlMxFSe1/EoXq4DuTHn5ZD1+G0Cz8nTWBekjWu0jwkKiY/b+HjwydcVw
irOZWBAsAsHWg1n0Nyth28vVeQ==
=EFzb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1f38e4a8-fe57-43e3-affb-32a5961200c0',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAiSl2hFCg/1EKyhsc2EMXkmc0TBUV+AcZAlHAb5XQdANj
96G13CzlvLXvUs7x6Xw16Rrfifrvh0wgKyfgVEmU3/pnnIZmhfXuPSCiRDYbyMWG
LVDZAe1vWTHo+uXwVhKkK7Qvts7Ao3tp3z7lvG/HW5418wuvVB2bgxaCsKdVmjtW
zOtwO76phuGr1ERmG19kS8C1OzizTVXPbmxVoRZb6TIbRJ+kR+iETVMxqy20XLp4
Ypn8Ldud0nv3S5sNuPYWJyy+2hk9akjWPSDWvHZ4+vUs8Mmak0Yuk6T4WalDSRrt
HJPYTjDJRTgAYPQ4TaxM5NdjleTxOWR4GXudTijT/jECjy7XbsBY75bLSz8LofAi
ck7h4gIdOgHaZuuIEzXhjIR0nopEN0+awclMVSGsUrrM1vkaTkxE1jNyTbY8KWlw
/4bYzNbopA9XXkR2xItRR2OQ3lJsDWg4wYCQCNVtxmTmdO21ueuIc7ur4sEHfzsZ
HmLtuFilfdBktjbEZ8wGpkBBHf6nkaGcWCa68wRRgjBk6DPKJCJ31Sa0C8wb5MvC
b2g6cOPmc01fGKM5hYeJhWRiFFlHNvOy4nDcp1ikdQ47TG0prGJuIuti0dh0OFAp
o3hj8nD0CTs9vObnH2WmnFcCGr4Hqjk0SgRUkp0W5Vh1SsdJqc6ksc08YECZ/QbS
QAHP6XfcT+NfxSJ0RUxppKwHdOI1KrG8s7440lBk5d3I1OPmV3urb6MFLH1IDqQt
kse0DdrAmUCo1oNU8l9fLtQ=
=JHj0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '24288630-3ae5-45e3-a00d-6d50f8328ba9',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvtamu95spYcqDYccRPuzmAZ2cOnzCeIhIAjJjwfFCVhg
zZTXMTZrkVJJ31xJtS3j1pwQYrnPlYRGmZFLRzjcyWpsNC5qrc2scSnwlxu06/Wn
wkRaNfudU4p1x46x2K/CWJGxJZczaUA40pYsYDGt2vt1NDhN4sGEPx/aHTxqMXkE
oHZS420oTQS6+lcBJ432idn88zgMSYPoczs9jswfPLze5U5fn2IiKyGrfbrz+X0m
f6JPbF5ODOMyYnY+Pf10p7nAP/rmiNZox0Ti+QR4qcZSUfNtGBOA0ygYwAUh9b8D
qFLmgxIZfMQhajbeJG0zpe22gWcjCK9KKlGRDWZD0AFKlZYEBdup9cuBPipP9uI9
FklgJiUPuoBtDstUPRWzlcC4bDBlnM2Z7sTMe+kc0p+dieW2pqqcv20jxqMOw9/g
CcOUzw4ZgmiEUN7cBd9GyYCxVubNBt+UKAwsYvYnrJQ6xi1CTueOvTk39Gzu4sPy
EIiCJNMVhwc/lyV/VGZ9kLKmv/A1bL3Clhf1Q7o3dh0g/+lm8ntKOnQWWNF+bF4V
vPTGGk0gHrCW+KxVB588Skzs+LGaQz2+XpbscKpj6ypWzsmfnuAk+BpaVeJxREp9
aWKlIOCSTei2Fnatdz5wXKvSAETmTtdwcGPwnuGOH5lW7+a+fBL3o3Rj2hJxOpvS
QAEZdL+zRQl0rAoBgzRApqxLHf+F9FdBaAlPMndmJpeiPo7jPAkH+omi/BA2giob
mj2ayp1zcSxtt+FZiybHDKI=
=b0cb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '24faf37e-06fa-4f81-ab68-1fcf273fe285',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9FRSx+80Gx42ooavpjrTc2ySO9vXmdQd9enEIScNJO1bY
HyuwxO7xTSalbsEJC/0GmXohe7bX1OnBc8ZE3485clAMec6PEfdhzgQ+vhTL77jT
AbyZWNSNOuwvatPqXpL006uyleLjA6dq/Y8QdgpexSrI/MfSa/zwP/XCDq7kWVY0
plCXfh1/va93Wz8gL0WigudvYXSh3khqJg+WdZnYQuO2baO/DOL+YsGTjIQCLwY8
6lXph/8qh9EF6+zThTiJ6PK2Sp5sok7t4ResLUWqkg5MFPmazoPnNUZ4ij73M63P
0TMDO4nryItg8ly/4Jb7SxA5IKumJXWkB0pNG2lrYNOq9ogjfxqef1zPzFOBql6c
q1kU98UeWW6wVPNE9IWAFAAl7fmGxpLD/WA+C13bwkY/poFp+bRMDRwNXe+UxNuE
JDtlmDT5zd4iBH7N8PQKByTBFJcazB/M9ng8maiGIbmLwIGX+gQ6nryW73n4RcF7
Lmx3aecTCZC53FwYjEYOMLDUQwuxMQOOw685r6yOj5lnJMedAbn+wRvkKUIzX8yn
tFjUoTUjRzyu2plAWPHh5rRuIsh5opHT2U7eWA+eVdNTJhjKkFluE5Nl0wR4Ansd
DZlPdGS4O3aqr7YPsl1QvyRNOI/nLcx70d0i01rydPc/Xj4F2xGi/rVqU4fRaiPS
PQE8JE0HcWlVKGWUKqYswBZC/SHySgeUvdFSDgNir/65ReOWB/Kb7D8zRpL4jEdv
dXIRYmy9clJXCO13VMA=
=bGgk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2d47924e-3be6-4711-a1e6-420abd6d8691',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//ccR/ZBGpxOpOo02XRs+KMuNvVJarYTH1tY5KuJECpfrG
KvMIHsiu4aLHfR/glPiaz1+tVy4gFYmH4SkOmT2wcPYx2zYlg/oQ/37PXQLi6yn6
rNaWLN2UmKe4++2+YgSEfSPEDHT9L7sLcdm/6nvWMomMB9oVjVqdiO3SJNXr0+VB
mEcSNDAoJ1HDssRLPWY5wH8vOJSzbeenYh8/JfmezR0ZXn5ogC/n6uDloqLAPiVw
KNDLkq7MM6CUyTVJXa2xiBUUS82Z84qwr/EGtg80PhQ1SJDqLxp39D9f8DEKAesE
dOqyUXFev++NyJrfoGaRkBu1GtNvnT+yHOFy7elAtK/PDR/bSGL21wBPMjjxsaHE
BymCaTh+zVbs4Hrn/8Hf1qFj1KY1xMPWG/0V8rGKvnkuk7IZMhJ78Q1ZUHl19ezz
wDDe3xi+TCWvpULx6z6Vsfte3lJ7bJ1OYvNi03SYdU1R6hGgj3+/xMBCDDWHHelu
zcjIFo6Ku8FRVhgB9ciqWU7kQ55e7Vm3w/f1/mq01z6VsoaJtDrDwrczSB4s03fQ
J2mX71G5UgucDTB+vSxNIESX/SjM7NLIKHmN/sz1wEXcWAegw4R4V2F7MburfIzh
0gvnDKRO4vhFs+e6gH6F4ftonQ5nqfx4/amya4UQvdVkwgjyVG7Meg8AAdWidSTS
QwGWvR3fOWOYlMOcC2Ycnv7bJm8PFheWftGlj03ihT9oTYin/+QPY5+Ae3ERE4EJ
XmB4lZtW9NM8KuGYuvSaBLytpG0=
=WILz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2d9a0100-25e2-468a-aece-fd363519760c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/7BMU9dcwybZ2qJ8Coah7yUQqABhIETc6apaMXmvPr+ju8
dDudjm0/Y5QT5D1mHf3zhp1vhIjmV8MidmNoYc7ZCRWdmAgdd2x4GSssj/N21izM
a3voExXrESgDmSwZZ1RqaMH8U2ULwX9hoq6wJUfOH6u94xyW3X1/8seLc0EVzyHo
X0YBV0t/W2iyEykSYsAHfepyq1ZSjz+0cDQwjjlgv5bOcYzb1tN4TyqzKrjD6N/f
GRSADcRqj7ZKSATmGaupjl1AxMysaVERAzpG6Ouc82PR0kqjHLSBTUD3L2BJUYUi
QXCKTBwR/76B9GNviVBhwO1cHRN0nTFarxWVAGhzPqEP9ktYA3wWyovu2/e2jabh
bOPYpg2gXNYiFO7JyQ+zPf8IW+JZK4s95zS9Oo6gVnU/bOpBe5TlWgzqYRCi5D90
bSm58vvUN2v1Z/120OJuO8HB8AONr3Qbo/LCDzEKhE7U6ln1MT1bL6Ou7EjmEwWC
oPCBbvdSxATDOzPofXOyl7clh9OpotiKETQAaHNf6m58B88w9r0B3bylhQ4YOW9Q
zFXhGmUWxWtLvdYB6XXfR8v5d/7mICvtcaGn4pzvAxgbtX8093cdr1+epLBc1tWK
lLNnexWmEv4M3NouE1fPlas6sPIvzWQWJo+eLIfjolA55xxXK76PPftCfnb6QvTS
QQE9oDWKHQYpF5v1poglJ40IsiEwoX9hlSp3nSUARjeOLRLJ94RkZn/US19EkzGE
klJyOS5/bI8qtYR+trUmF/8m
=xcAu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2fc953cc-44b5-4076-a1a2-ef3855119051',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+JjD7ZuhAKfn2nksUccU2wdzIHm4sAWcPMszBR2Bo2SjU
4o/T6Htggq2LKzC2SjJPJ8TEUrydFL4/6Iyo3tuHHr/Acu/+JNdvosxeyAMPwwmv
eO00iZXWSeyVIa8mMdt4JlhqUPdNSYYfLniHh/8+8ddeftfDxFYPngbY2TkAFoUr
ivPnIceWmjdrHZBN0B6LHiy+FQdvSudStYYoEiA79ZDFjnewAIjpmwxVZEGiZprF
hZDYUUSUU9Ld4Q/Y8MC8B79C/M5m98W3/lJ7PJirl/6Yx5PrO0KV+MjsJbwllbVN
YkDEaAKsPU5koY1ff4pqU4s0hcs35u93rN94QtVipNubRK2K4CK3uKxRxlbmnVB6
EgIzzjHtcP1zHSkSUQ7Nr7K+YyBBa/OzZiPbCJnNL/5o2FEeDQBMGKvyD05HbCls
YpQYG48UJ09DPqQ6F6hOsSogJcIYGzHkl27KkFAL3CLRbnLOlIf+CTsi5HlKl4o7
FEbBezn0KkuJWj99aUDSFLfL+uFa2v1xjfcrMAHK9jsklw3RcB/i7htbU0x3Pb9N
J8JJFDp6GCfmePmUB9WC0JLU1PkUeBbjtYiI2WWpZd8Ejl/TEsQDKfGTHlEgqaki
ihKtlXKtnw7Sl0Gtkw7y0xcXx7pNR4XKelJB2GI/M/CVUwF4Wmv1j358gL5deY/S
QwH+74fMHx1tsZo//3kl9nf2/F18Jxsr10fZHCbKkpLMD2U/ZqoJJawWYjq4uRnJ
ic4Ej8j15WmA+Y9q++WQXgxsVag=
=S9V6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '30d27816-e7cc-4872-a027-b4af00e41cb6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//SmBdTaRP/Z4BaWUxFwPcomOcsYwVubYOsDuflW42eS//
OfymY75HnFG9c0pBiT/pTr1cIkP8o7XN+7kKflVku5mpypGQ4x/lrYTnarcwhQUZ
4xnWyDmDdNhPwxDjoSBRqryUqX3EGff9uF/Zs4T5MUj1hwp3zpygcfdT3MN44V8B
kV1VibGxoU7Zh/RqwB5i/QyXnOrDFCwOFAoBW3/Ffabu7UxDHR2bVZllAoHBnqtn
4RDZUGhn3dTdXiKzhECswU2byuh59I/Ybuz7aM8Rycb5cdFg8Iwpw5rs5G+XEsKD
SGdxq7CxJGK344P/VgHnnmMhahVP7dzpBfi+nHCLbhvK2RAeokc4L5agtYBdZT73
Pg8OUqskTd8pNWN3wOW/ncAmbrMk0f9e5TqiJjSIO8s08LTBVxByQYDYA8peGMh4
8Mgh6nhcC+yaMJ3fUyVC9QQJz2kYPVttojVw01WvuBxHLskYEGvlttHgTGRRhTIi
WhPS0e38tJHMeOau5c5oDVkx8ByKm520WbbZFI6+FQ81fBytnVySY+9TLQbg2ySg
5FmpUS8vfCi/RJZYg6TOwzWpwo8LJOIoTT6A39XuTVNoUJt/aPe2FjOGUtzQP8Ab
hqtCIP5cPOAsk/Fy0WmmdvchZk7ERZbuMHhEssAyrtKzyadZJEfbRtm9a+AZaLXS
QwHDIQu9izH30MEUp0WLcuMWSw0YBFynQ1PBnjD9aIc2kdS0Y6qFwNAYR1nnx+LV
mcacJYjAyYd2JNxTn/rxGT034D0=
=Q7Av
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3842d9b5-dc9b-47a2-a77a-7ad9e6b12d62',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAm7oCiAmn77r7UIh1+xzAWr1zPeJ5j8KW08pkfh/Vyxc/
LARKfvGNjmu/NYq5AxciIbbWmDdoIFTxEId/7ro2WWcjrIH6GIivXqvdjDVGGbYb
C5ryiRorVcahyxgDvtR8+MDzev36h3UZDFusXpHIuFh2mbFEid/vljIfGEii9DRU
3ZoKPOblRvDvrF+XKAwiZp7dBnO0boRrhHe7e7NNSqzmmabe6i1U+DfXyjI+x++e
UAQkePzwaX3PvXZZEarxQHcQhQTAbN88jsLBlrCcoNqX6QFzPNfmzM+DRJ1zlbfv
pqKtXU58VhgT8ZTrvxZxhQaAj0zUuBAjsSpgseRky40MIa8a664M/9TRJkj8PYri
HVRIiSdRQV4oLqEYUdEzPRtIeB7BIb2hbN5BAudlashXQ9r+WB25mUbTKpVyQDyD
AWQT0wTMYpR2eqV6xnu3+BAXm0oVFsH2rTyyyH7pL0ztioxRAT8p2cLef9NrgWsZ
yeZ9zCqrw0NRzziTsMvvyoL7d6TXNbjHm19PB4FOBBOydvUG90BUwExwc6Yv3DJq
bffuaPCKfnV7UMqHQ1X7lhqxj2eopaajH5ihPKN/xYASZVUlG00lcaB4N6dd2iOf
8q8Fn5OQA4T8MWPPCM3HyEGIeTdeUX3fNngbyIHAYO4bMUPB+Zi70cZxiJ45l4TS
RAEp6IE33j0c2e3T8NZ5eUm/KJyqbza9SrokCBEY9X4RF2gVws5M+RqSYs6AXGDU
QXRXXEXrIuhzK2FazjlEZIRdtQ/5
=ebak
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3ce13ec8-c8d1-4035-a0af-57d3de050c49',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAizdtNTxHdD1lN/zPLDAvJ5XAvnWzYjl4Mhvm+3/0DL/C
VkDjlYePEy9u6++qrw8/XC/NavVNUaqdErenIUkt5vSVSsMKKTCVGwHw0ONu9SJ6
CGM+eRLND9Q0q8ZI9cqcSz5SvZzQir3azZi7HaWP78Ty4ntQPy9ZhiszeMfdSxXb
zOBGHB7TI06yz18xhyDh/ymHNUUF8YLdgiDd/ZD9oFhjK7ivSr1QX2fz5SdVIMCL
IWaIzu6xi7x5mloHEH1gmu57KfetXEk71sR/9iALQRM+/ixD9p7KmEOBCja6H45W
LeRexk9Ftc5okepG0lYbfAbHHELN1kAQNY7AB8HLo9mo7+81j1WK5y//HYW8vDZA
4YXgWqWpW9xOoqYSM71aUOJlgSsU+1qEMmevSOBqAdZuXqn0KLDqZ/9bcAUWP09O
6gAbb4QFWkYI+CGgsu0pmAWl05GY/M2U5Itg8wjEmfno8MtKNDel6osqpXf8nlOY
OSWiL5tWAx3FJRD18NZfhyKZeYafSRp8pGQ5WJu6Mx45DYjilIbitSUQpvJBlD1x
uH2mKX34S4DwoQFjDUAROR6Hd1niyLDJoWxZu5nxlCJPjnCNpbc/8sC31N/ImFHJ
UfXqjz1iD4DnBh8T5ZDyb6+YnqIUkEdTv6C9QGFlluYqp8C9YrPCqfpcGxfU9+XS
QQGmlJz/zVhNWconwa4SkdOJ/T5rz5KBJ6c4I5ZVSGzpcegdw1VBR5ruhY8yBfGl
zlzRf0jgYr9zbu0Vnfgw6xEq
=crNW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5401e232-2e48-4168-ac91-fe8187aa6589',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//UGOdKoCOMhx28dN7C+R0Y46zP/DQOTz8pEbIHCz5VgHI
/drNAuswOXoQyUhgc0zfrKpq0YgylXhfIvkVJpZXJKbIt57zrbOMGTARb0w3Bd5+
jFMo4HVpxyi+pXDZHXaMpkRQpk6fAgIkvrz74dRDoyp6ITzUiojXlu3RPSpPOwWX
08IObt0c0QsV4I3OKI/OqlEj4EVn04SNbcUjptkZDNE1wvOU191IF7UHROYi+jVH
Bg/WwdkiPrVmTh5pA5ET5nBgtEu9t1c4ZcyE89eAyHiTPIo1x7hqTI7pY3crblbt
BR0tC+XvkQe9NWBujzVFjPsxu0y6D812RYnf/dxppkpz+Xdfvjn+sVf8W2sn2RMt
WbVydVS9uHFZi83Q9G2lF0um4jiY5xtiKUdnGey71+N6niVXGw+wi3dT4idKHkM2
BXzEtzZeeMVJ3xsNqkVk/YexfsMKTnyX55t0lLIAwzlx1mnjpuBwKI4IvjX6HlXD
qXvSpbVBDfdh3EGUyDH0DtG4g4ghwvCf3NODRmgRta5AHvTcJMCfs3bem0quirYR
71QLX03GR66dO33CpS6QOxgSl4NYNW9KtBeFvQLwAyc25IBDyaWOdFarjTF9djGy
IzPYD9k2pc8Dqn9iqFojximywzhEtOpRnhc2SBokI/vEVQiwdsYzdWWkVmOPdsTS
QQGixkhAwizK0SlADZtoOK6GM1kJDfPyXY18lr72JD2mYSWlfzSveBVkAOGk7CQA
t06uh6D837fDgWrW7pJsKbbd
=Nnp6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5757b811-af1f-4c0d-ae27-ece1b9c474f3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Qnw5PbQqu/HB/F9FstSD3ogeghUfLB4F8XUratzrhAR+
leZAW3xN7vXdtUFqKQ7tqQIDz4/bbOILB0fvNxitYev28CpnwtCFnCjVN2uVvHRk
MSrWlPgTs0CVDm9IwvY34J1IQk5Zkn0EfLKAmUY7PuxLbtcT3YkK01WZHkpQeYSX
i32NdSTzsTNTUcJyuC9mOgQ7FRixovWLJGnVNDS3ATuW6UTDH1zYXfl8gTCwXYNd
vZWKxqTAPEhIOOup0SUISsrIoGV9HTfLFnSagy/oIv97tHXIG70RmHEbUlkPOoqd
+S7bkwHhCdq6MXEpfQIsKU09scdh+9pyrAKDgd9OXXtpsCQHQ7/xB7Jh45sfmFC+
vdeO8/5gByuE69J//TRL/XmiI3MZZEHgB9eoa8pbhZtfNhVB+qBgIrG7GfTkMWBQ
qRtuuCXqiK1sgE720FFFNTuK3GnyvUOlBNASY9MyCoG2Qc0rqH8xeLAiXVRoXSrK
FYaox8+r3ceKQle5xw2f9DB7hAtuLxebw1WvKIOL1M/efkHRvFqE+goteWIPxg71
xhzMw/YPDsasFQU7P7syFPeO9N3yXVisd08LGiKQ8MMI3LGfx9vlLiniTtOcNHEf
GW4s9nJ7S3CGe956nPjSt1K9qjtdcIJwnkDlr+luQQlflyLOVMS635s/TUjNJvbS
RAF1/6EM4BrXPyNFYkdGpWdjwRR5zczZWMyXzOZqambTSGZdZbsT+pfVYYrAQUjy
p6IK7UUo7ZqhHHkvj4HkMFODICj1
=cztJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5b7aab07-ca4d-4605-a517-d50dc695ef70',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+K8mph27s4hakLSU1a3LTsdWXCEhAUCPsGfpWtSiGwZgI
o8EPQxx9COAQNSN+npl0ozY/J6YvQYa3OcHDV8bBq2QA6jwDHYTH/cnPSrrvyrhy
dBqFSwIbU7gvp/Jxycg/6pdCWgqXt4P8e4Vdh0YJ9IqC4oBZoMmZ9MXnEYLQCdqU
6nV/sDLzE3/+l6OXOsAk8bpO1QJZeynhwZukY7e8TCQjsrRyYxpNihT6nLReW2s6
rpN+IrPfypmzz+crVfYja5eMEWPtk0XYw/yckmcQQZ4CjaJZeC33iSZQ1L4UG/iy
ImhAQ6+GPpGHbg2vsMv0IsD1FahtnQtNOWgKHjOxAJmZScG9KhJ23W7+srqerLnE
lwv+fXrXZ/mLZenFjMFNp9xFjr92WKxFs4dwPmqetcmTqm2vyp0fO7RveTKM+RQj
zLXz7qVGV0aJCN8Ytn/tSzDXejNxdeMMSdS28w+2e2endFff9pY7jJF2xWYTkPji
mKjd0767eINo1QqO1Z4s4j1halupVTij+wX+tcCKQXwXaCKOcaN7b+8OzgpHC0vU
XkeSkXrnZx1eBlky2D47EG+fKgFw+JX0MQPqwBP9NrkE2JkRUihvSReigRA6/Cmp
qX7w9pWQUEhVEhM9d3UJmFDK482CHXWhUiTFZiFCRXzy9YWWIig41FcTblyWs8fS
QwHdx6Hw5cXOEDAuffOvs/pVjL5RVTtHrem89Jq64RU90s2YyxwkoudiNFfWH0sj
UBTPoWLG8kqxpcMJOLOG8Qr0m7U=
=t33d
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5d309f2c-3265-4fd4-a24e-28ced708278d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//d3DAze3wav9IqkIY98uqEn7eF9Tbhq0KKOAkBYiel/zV
Qvbay/2SsPQXJb2HkW0ea6jjhmn4gJootqsMzOF13+3el3yOMXL9eqqGAOVMgGOI
g+BOUni7Qb1H0wdGyywsUSxd8HGkpU+6JzBHmkeSjH2VZB5ZZXx/1BTvQ+HOkvuR
ZNtKACSgWtwxBpyeAqvqcE6wunnfL3veQvfGm4W6PNih1+PKPD+WduItDWNg3Yk2
kHCrzvqYuteQtxhK6RmF8AVnBwzzwvl4LMt05EBiPbaGv2NQYaROW2AOg/kUASWF
wPHinPbg87btRjN80LahzL/eovCPK+jgNETPvF+X5A+ZCGLWvU4oz7Q3+lmrjQd2
4yQKX7JcPp16Oq+PzUmSxOXU4r3ww5jYqiiXCGCwLrPEOjQTjSRusHD5FnIskpev
8eUkXQ2UgkE1nEOdeAs/ouQZmf/+cUIi8QJ66uDcz/FTA7HyX7ersl1+1AWoCfVL
C56ZEnspGltLOCy2wtDVUoyrjsH/umOOgLXKNdcUXPh/BVZfH8BfTodleBLTooJA
xt6xycqUBJYWmyNgBFT+KgBSj/qH5qrAk/OWqbq5nv2Dc87mtoXWHZ+KrnRbqCMw
+OIO9V4+eotEng/tBCi10KbGqys7CRW9mtcNO+oiEScUrw43uJwF8nAPC6ZrBoLS
QQFJ8oVDE87VXuluxgI6WClT5AfWh+SRW5Q2/DeGoe3Gs3FfC8qusiHt+tCcMBkm
vRW6dYUJ2fk6Hvjevybh+5yP
=SLu+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '5e851775-1bbf-4d22-a458-1508baf5129d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAhoquAYcpXQZSgJNpY1t9E1jRRVOVeGdxIYe0nq7sH37x
ykA6U6A9ZDD5T4YRY58gnqs9M5Nf3K8u0jqoB46uqx2nDW8Yt4FT6wR9XmMQN9l/
1q3yZD9z4dbpGSd/hEdRMutPN7MKT+lEmNh2CrCilrNFgox5YWhw45AJc+2xYBMh
BHhHhEszI89TelMlnteRXWnekSeiskKI4yvTK5+thEkFbg0lhDmSaCzGhagDP46p
Yc/DjJc+kSbCw1CkQzfJ8RjtczO3Yn5ofoSfS3AiODSgEYLkF9xASDrjqSJcDw4W
xtqnlaOR+e53fEftxDtAlnkh2KQ/ddqVs/VXFTPv1Lf5IWsTKHo9z/dFoId/9Z3Q
ZiHbO/iaeGj4LzaYr+/ZoIc19+YDlsM92g3yTy5fvYbVNmlTalqn25zdCk7MjIjK
guTS15/bVCrX2iKgt7aH+NstkQt1f/jeROdjZ44mKMsCYE/Yxqi+C85oTmgOQUGh
gm9WgpELyEWTvjH6SkDnkMuDLfSF/W2Xcj30ZJRvTCbLw4SWRs4Ra4UUAdkuE21t
fVQSCunGcXeAs4hNzH+iaRjAx96YgtD8USYv8dfqTuDAfQHfgtrp02gH73UVULdY
a/6o8n91gzkZV4xarRP9gH8AjNrGXR0yD+UBvtjJI7q8b2nbu0Ae/BwSeg7lLEbS
QwHjlOoZRhJsNhg9I2M7oSnYQVEbFCoXO3kPrqEdXFbNya5PPatm3K3AIdppl9N5
IcxbWon3eOc7P6tMtHxIxEtVp2Y=
=O2zN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6735ced2-8e12-4afc-af30-7e44a3414943',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//amNy4z6iL4AfV3GT2SK8c3QffjC7I2jI+HDzJaa8glSZ
QeLPOwr2pIbyfilgNmigJGr9jmA3huulFaW/O4PF26hOsW2KlwUt6X57b9BaSHm8
zZSvm0mXAymQdE2YDqD8jECMHHix/iv+w012kvt6SB8obTExNTv67DLSmCXdYuhp
NstmdONhcMFl0uKCtJTQ9aBd43iXe7zPppBgb9c4s7C+oQ0PCqCXR+J0vzijEfTz
/9gC8NPXUne1r4+sbrpLpE1CmjhHbCusMXYNvgpKMSuwzXb1Nb939c0wguAgk3zd
ICdDd+6k885wCdb0ZGZhfCxH4ZYvbqsTHmMzwACGqtOgP+Kak59EVY3SIj/dAX1f
vjpS2Z6H6n9q7fI8JgzkR2F4l+gxFbEGE1Nk8bE8VKoT8unFjDF+WPTMQ/XKDt5W
UWqvaQxIm5l10To1j+aznN86ZN7/d/mSJEkn1V2d+/DD9iqZPtTrn2IPao1IboZ2
89q/jKMfzzZpc8CSSinWV/KKnmTW7BnWR4+NzfAdKo9vyemBgkAdpdftuaDKVfEd
ztcSLJ+p+XrXBGW0vlAOBfV7hXGkhlqiRoW1Yeubkr4fsMcA5RXEcKiI6HsTrvf/
OVV52ePmrLc//5Lm6iYZLgREaudZ0IVWq/ot2v8e0FkNITTyPChSeeIa9h0v4e/S
QAHvMTT3P6FxK5wJb5o7jlDeJ24YoSV9pqhbu2oSxIxWrrao8b2hYBgXSohlMcct
9sH9W4xvWst7XYnX8Y8sjDo=
=QpPN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '676f92d4-2557-48d2-aa15-8564206c559f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAps1fXai5MqflKn3r+GZWcAQb0MrJNvkGmWFTMZiCg1Vo
FieLTpc2CLQ/PMMYc/M/6/9vc4ALw2/KazE/vOMiZ0GT0Ac8mMnGSNAP8Z2AD8n7
TNG2zd/eoyWmHN7Sbe4/b2zBlfPEM4qrOVocmZpcUjvCyEaYv/SonWQwUpf+3olu
N6nQlkD7VLMRVgt6kkw0Qrx7MXs2S9cGjJtE5Yk5tfNz17AKS8pxjN6/o2PRDY5v
1SOkUVNgpXl4mipxePCw0ATHwJsEHYfokVcPldl9MT4/FfT8UstnmR7qNQonASgR
JsuHhNkIZo4PfMEsRAaLaDYKjkyTSypnoJD3NOOA3OYl1gsjtlfm6QU5uAHV+0tu
DNSuNZ/DwW0WzZ7v7gV14AgnWVLIZLybGNvzkR24Wj8pGIkyMFs8romQrAwzCof/
ExoUrj194joj65VKl4lASLntPI9YkZ7VEOL63xIrsdqJp7pdIHS8B6MmEo5Y42PO
7fzsIUcaBKK54mfoA0wUU4S4AnyoVYguVZs3h5IqeTOzLqua4fZSUnimer/umNhF
tbjvkISoxIhBAGjRguPvlXowIoKOizNkkXj7RCYLVWljP79ktyjajR3uXafzyQPR
DR9f21rCUzpAt6y5v8bbdb3fZSFRp1vvWAM3nCKmhn7uJGuRCNmQb7AIeChx+iTS
QwE3x/u54RRxM1A3QL6dqEwzhjC6eMj4MdrW+SiS5eLxpFbLWYBcYGCcZqYZIPYp
OuajDqUR9/WrSkLn5lKhCFuPC/o=
=FAzV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '6d02b4fe-5939-454e-a4f0-0f8db453ef9a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvbJ4djhEFfofiX2iaDPY8rSEFTCGEo46HGIw7pqPgIXB
DukydJJHtJ8MzY3jvXTRHKtOKgpKwR2QoTdEQqYVV9M7DdFxfNsOu+Kla5M27Eev
uDggCA6ES0yKOg5uxkMgFoM05c0DO96MINfIoKwI6yOLr/dgoEbO5hAlj+XmWaxM
qKxnOrmEyxlmSFZ5TH1WEx46k7XlXKOjMPbUdVlCs/oxBN7rMzYRKApiZXHra3FJ
rZu3GIgWFKFhPR23NRReqSaFUVhEiHPWxnBoLWLyjXOwYgktkRC5ueHzWkruQLv8
FP3DxpIKEasl/QovTKOpvTRzMKjwGx1LOH/0j2ab7ph7LYilBes2pUTE4W9CFKul
/YESsAQ/MLZe+bPoHSoVJhn+IX1X6dCWo4tVLSMRMgU+yuoOlxXVGy8i/GtKQERo
xXKEwLS8Z0IhxyUJAFq+tNXMaOGPYOAnlu8k0AVMuQEmU6NSYSyMLqHs1wAZuKV5
dzsljMVptZ8r/Y07DAtqtAunGEH8DPykBNn2JRF9mvsCbjTWOyS6ZtlMYP00soZL
zPg9+cmDehOTeqng5ddhUc7MYd68F8QqIOu9f0OtfNYgRHsABG7PfHZTtTazy0gN
BJz7+qXW7dZS8A6aeLhna894rnJ4oj4sgsvRvildP5dopROpBoZapv+xTKoHObzS
QQGDamxzrSybzXqTPkHI/+tEN/M27YkQO4jGzgjaTuKGINeaqHBURbNZl7MgeRVi
fZzjmxBSUMMtcK4cB1ApetPX
=6hZb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '74ea2bc3-fe8d-4bf2-a97d-0e2d99b2909f',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//cQbSJjA4M/okYJu+Mn3hLkCOARxQOTmODtW+xhkbH4Bv
i5mHtyLP1RrM1h3jNMaZ5kkb7BPr7Vl7IMI7AJuWz/eJCVWU0vFB/ItVqL5MbZWL
tBS4jfo8EleNJWK6glcuX7tpXwhEtkzWof0klp+pCpFPMK5WIfpkDILUcyoxXXYG
FuhX+jrRaZcT3WpR4iMdnzT5qVNRQTh8tamEe1TPAVFM0zM8X7ypXt0q1qjoCNy1
5ZrktQtjPhIZqa73t2ulX8BJq1qPhOiFQeyHyG5QScmSzphlgiUJA9eodeDgfU95
bDsJLJ7o5YZYsWCch68jQjROazbhM+7f2VUFSNI+Bph95W9zb2/qOvo3/68CQmeO
HtCrS8q2IsevIo9zns+2EpSuD3gZkKg6jr/YyycxWoAvcculofFAjG1k1aoLxxEx
f9m6Rlmy8UQHVbiB5mFF6z0rUp7shA4kccx3841wTJJQJbwq/nC41wYC4AylEkD2
HKs/5fksyn0jIWPJ4nlsbMBmk4mZBZN6vXeHCgrG4N96j1qEDFttAouDIEFZ6kzx
iRqUnhKV16roifZppbxcx6LwIAKURK/ddmYHKWUiRd9maQ6//97eN0Q/Rkmqoojj
7EUKfeauWnfwSVtdEnOdhBYJZoxLujik8GxA/MlFYOrgQ4tiST4Ekca7tP6WoUvS
PQGtOhZruGY9r/+2XEBb3SIO/LuNzMoVhbgN/fK8Nm8AlqpsiP12v0MK4OBA+SuO
1ZNm+6eS6gf28c0F410=
=Degs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '75c9da78-5acf-4487-ab12-a2e77b22c185',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//dmNqii8bXXB2O8bDUVZLaYvo5Iyx+crMezc++MuL6hJD
MG5F25wf940fcopSq+CsmYO6sMDt86FxdKYDLnrJycNnhbTxgYDt79gTqPiqznUj
jTyRXYkIdhuGf/egj1EEVs6jAJpAu/Rix4/8jjRO+B+FF74G7aTWYBZP8gO3FOVB
FMZgkVXsfVPR9eIuHF5xlcnPlPnVGNBU0tHLc7TY30qcon0FtpT8HJyJU9yDyNOb
5APQPQLwcCyKH8HhgCOWwNOPtQxNf7C15Mo9XrVJHu43ACqTTo2YW7sOkkPdAKRY
W/fumGKkumjoQfgP5cuphdamItb0irmCPxI+L2Qc687OuRDM9BCagQ2fs7plsC6S
e6jWmlrTFrva5PUIHfWsAene7HW0v4gor6ZIDDaBErwFJseXNbBQhhd/9fFc+3pv
eCUFNEH/HOvV4z2GR/aCEwp/5PsVztzM83TysDIVF0AaqEVmG5wepePE06qDM4M5
c+SAV/w7QD6uOmYTc94pbMw/EiVA+NmEMaKvzZFwApGFfvrzMQCfqZxX1JYbATkR
jJ8yg2SJGKTQDqyMZr1uQJMobUQpQ/n9oCH8YmRMuVvH8tlSvtSNy5Dsh5odL5Xu
sRn7P5kkm2LT3PbVI1p9ZOiNpKA6xjIvfjuDaZZ9ButLLEjv4vFMxqRfyAFDtZ3S
QQGmFDAr4pOplPL0lRx5ZhFGsfT2uoS3jTCT+QQLoaIv63Uy+fK0GN2Yi+n9yBBb
zhitRmmXPl5ANg08l9bouI8i
=jhv7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '80225337-3904-44d9-ad59-d4e0f9a53474',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//WZ+QPY9UHPdWwVinlaBw52vON9KJ+laD11p6TSPkSRjr
Uaw0KVtHPCJJn4myOB6McuvOO75XN5nwyUi0ofRXJGDscXS408vEfddZD3d0H6C/
GnrpBAas2DR8y1o9H2EVF4wkwoY/y81AaY3xaJHxRChVQ4AybZEkdPsw4zJh14Dl
y/vmoZn3GMv50i8DnjxJqCYX/KUJ11j92oKviBkKRpjzVreGhrdV2DTiW/+jYOil
63nyGkKlg4ZO2c/A4xaQXNVGMFMglM/tQJ4i7bcMr/3xfRzUq7KjzpkfD1Kwh7sV
i3E94VnGaBbqRxdsteGE8FAu8uMdluwQKAqb3QSik93LKqchvTVVTar1JONKIMAq
okxV8fGGSWh0VmoGMkKeL2yTcjS2S9Gf/tRCZMsTJTYvaxNAUOErt8fJyibaYX5o
NbP7Dl0efTiSwL1sHr8TwoIqzfaZ5jUoG1EJTumOIQ24xP0X1eMG5KtARKB2rFbF
kVDWFlfjNbmJ3cpRq+cl0org0huvVZKEUKXn6lMZUu+VHbLdZNJxrKE3qgZIyyNQ
p4pIKlVu99PsfcnF75bBkZ4ZLqsquVgyWzZwLqqJJ3ZZUxZJxYpkAmNnjrLC+/DL
wTeHaIHsdOO5Wpc2jvVxfEMqBvl2SlTALZiFPlEZ7mKK0wQ75ZWg/x1VemUBrFbS
QQEg4S0lROIXtVTbo1qZsALMy7Xmzs3zf/C1SzQ24sOxH8bvTGmrwcs2zs/e6y3A
zkdDh/4V3t0FqIh8Pa8fL1ME
=3sdO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '85dc2584-2932-426f-ab5d-b78c5031daa6',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGARAAoWY1/GIbPRbUkjpL9a1qHZlj0gO1fvxEfbt3CyhbWEHc
KbWJqM6Z7DpwER4r0YPNbeKVfhUoNOLI6N7ozLHdOEVkZcCvsAOqZ4sdUgbgEFKD
9wUPqYchlLTojHGFLtHyBc/nkDNotwsQOX7WE2ktkPS4/1GwEkvQz6ZRxWqqTpM2
ZwaoOA55HWxYcOkK3udpS7h/P6xhER+CMqfYPs42Pd68K/D47nuWOGUgHXjpg80C
nK3VZNBQhsc9VQ4eEYO60vC6N+zz4osmJEnwcqqdklTLVYA/PgACYbbRvI2b7OfW
EBelPRYFc66kUCEG62SOJovHHcXJMAVUA5BMIaBfHR4SYtzw3XKr5FZMTlxdTgJc
NqUhIYdWGkD30iHddI1rj0tOU4Q7wq8rwVzV/YZJ2gc0gvkpP4tvbYuCO9Q3jdFn
r+WcORw34rI9z95ZWqKuTc7si7imwgMbR4Vz3fnAQRJheD/tUjMqs4/9MMMUXCSP
5LmkqZrxqbb1wb7WKYvnvWNeiiGuo52Yc88QG2v2Wtds+NuN7Y8+ZwMgRj7JgRtc
Ao2cKlimXjBZB5tn6V7lGOxpCDmIlv/GimWTD1bLdrsLC5/ZYWTuptOdt4czD2xZ
tZ5DmkJfm3W6tU3zrpM9sUOYP+9TmiVA9eUOa0ODAJlqKhnt1s8QGNGGBPTLmGzS
PQFFOg2rO+AkfSLioVSa6mlNJJa3u5cxirG+ZK8cdngkEFVRdrW4cF47m//LMD06
9yDQqVjRcnG7N71N6fg=
=+u3M
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '89bb22b0-13df-4f19-a5cb-76d19eb886e2',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+P+D2JYcmwBVXcS6eIos3i6hdT0q9VHEWVvA1Ev2Lhgfj
IoIXY1Sdnd66Rl/Yx127ZVr5ZPtayMtmDasPjuhP3Odyjgtla6zsUn7ZZeJwlEdv
ohmdJwK6turUT6ZTkT0aJMLSzuGekUD1OgWYzuYJd3gqI55sp9Hg7gm4GSPYPO5q
tNB3NCsxHyrFI1JNbktIhVJhV9E68ASKJgEV3ygUUxBDfsSgzm1D2M2C0fqYX9cG
6TknO//9MGwzh7oJJ73AJdis0OeCt92QcDcdf4dAVJeVOSrQ3+iGFtKKy35oKoiF
uS6LxlzTZ3z3zPOu5Ei0nKKe6T555kJOAMVl2OwqwUw61Ze/pWUr7JbVJx6LpN1H
AI2ck4hOAypVBV9ulEt0h2vNjvL9LRlJNw7hmCz8bQSjFz0VXNneY3aYeYZ8mkDE
3y8Tl9TZg2vYo27H5paNMdjTYLAUK2YgiDM3OlwMtoDF3mB/zud3T7NJkXUG3Wf1
kvou4Q4lo0j2HEW0ZB5CSJ5hDo2y5ShYkJv0HmH2a6fvq2Q6y/ZS7dlc08bpiZym
WVw3DBZR8lhudKpsaz9FkCh7EGcCRYiflQFxKOmz1hKnwXmgRK+NMf9+noc9zYn/
zVA1GTKGtWccwdzlMBcyOklrLdcfnjqoqc+sII6Z27NC2phA7ZYBtgOiWzTCQ6XS
QQH8fY5ZKEC1S+X8HCHmTYkGTGVyLQF/WBGo/r7VPe+jRIg9BoIZc2Ocg3j9QqGV
cTJEjxevH+YsSEqxlkoULQTQ
=F2pf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '91ab0762-9311-48f2-a692-85a4d4028942',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+N1ynObpCYQs3eGeafTr237i8tTReaN/vpGIckvkAfwEB
UIkTgoWzmLCwqmLExQiQ+XKi00zAkUqbzmMFM+ovBHifkU26k9UQv3BL4Jut3VUV
VoKR8R3olCreGCD75K9pbprloRopfcQhB5UvYIrr1uXSRqLC9ww51FlYVU8/4CoX
8Nw5X09ha1uEmTrl1B++nWE8II3wutkgfvmgmcSXgt9i2g8dgqAZU0NwYz6A7u3W
YxwkVSnRESB3FdFTB0l7AuGFIIZ7ACr7b8VC5PEiFbFnml6+T08xcS3mjZ+aLhe9
hzq8wXYuk5q+I3zNXTkf/db4zUSL/PZlFpOvGRzck+931eXqWRVG0rLe3Z9ZTE1p
MMkWgR6cd/JvhufIrzxJx3gkJNEuokj3ClNYps7bu7GFydrdw/0C1DS1HjDrlenH
4q49nJ0XzbtxFu1T+9y2ue4k1xu5th0d4cY/JtDC4eh6Lo/lhEtxCxdIJc0qJjcI
9X3GLQP40z5716Svn0pG4WDSw6AYpdYcn5N5OwMZM6huU9GA44nhHUy7neftsdA8
dSylo9ftfoixAokzVGucg7zes0+OONTRR3EXC6USbG0KcFuuJZnKIH72hpzANLqn
VuVUUmQr05pBqOX2ELjzly9GaoW4wyg6VFswIh23l3IliWhK/xptOA8B33hArjbS
RAH5x+y6cpbGdi2NQJY2wy42dEpbg6NDVG6nqIPOyiXvbpGjdH+2v5+rTuoBFY8s
kKHRvuB99dZmC1SsQ1UYFWIOLcpE
=cne2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '92f2067b-63be-4740-ab35-0200cf901cae',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//fRCfCbE/PADFovqHjzk7TjCC0hLzbJVJkB/fhQAzUt1/
fepJt+yNV8lZ8IXeiqIKiaRUJSl/MO+eHL0cStvTUPs/+WiOVPmL9kTaML1v8QPT
vo78aanvAUbztYnxpL1sm5jQfT8yMZjC6swY2AyejB70mngqc423pcOCsQWfGdnG
uEMmDaT0UDYAkmkhP54G2TrNw3zDcltNHPU8feBWrMqjoaT2k4BVo/3pTjtFg8tX
dVaXiy8RfypjrwtZAnRrazPdFVBVqxHeBZeoc25WXR2bZ0SHAXyNU0wiwI2LDwdf
Al+ZqQ8G3O8O4K3Q9I/nlk6M9L+uQBCg1iRtUfkzxR1tCg/lWXYnJkqmXTNAHmJZ
GUV9GWpb3qugFN1KQYq4y4uG49uPt9LPlsP2waLmggE9kbwCDQBv4/YgvsYuBmUK
5j7gziK66HxvVR6waZmWozrW8Ko+RiaLYVvpAGIAvrxtacqW9olA9cB5QR6EUMkR
3WZhb+c+Aub1cT2fs64RFyOAIqbGzGHzX9MwFG0EyjKABc+bISq93l9dnMTzEhJV
5I5wCCH0q3q29YV29gHyT0XQmsyT48RmNcRsEqg40ZtBYZn2UquccbOkBxN1NlFs
HICeBVMNhyRUvQmpgYjw2cHmeHI9QpM2XiQ4hc2N/40IiOLoRI7t5ACZYpKr30LS
QgFIIu0/gWIbywrn4R38gARdZ5ChilBecUlThj0yDcFDEcqcLXeGwXEZN7zxNvMu
fQa9UZbjrktvNimm5ErK+Ui2bQ==
=qK5x
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a19823b3-6099-45de-ab3b-5d68e5a7cfdf',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '408bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ//fiFqZHjabXe8C9Igiv19o0AfX1otHVtnbX96qeuTCJBE
Z3BwWRYNnUbN7JiMX54UrMpwgW2ELbTLpyfJgpm7WSRWm94XR1fcPfYICVxF6PPm
WEZXRgMqFVB9mvGq0X+0l5sxKvtYRATVbXJDchdSDZn9IsaJ++y05u5UoyfC6sro
jciVtl30NBAMbmIFQEmyksvhF13e1SOIfAiUtaxJTiykIcoIAS2mJZF2np+uFKSv
BfRnsrvBKhX0PsIE8AdgQAxVGwlidrrJm9qnfzbWO1/A3bDbxRhs7WPfBlSI5/hx
BakzQXUfPr9V5B4vxcQ1q0pBmLjcfUuGiY2qqkoVVlNiDEp/IlCSvRrQ6HPLxQ5H
G/TmdGTY8vvSzg9xLGkhmvPFD496FSja3x5nskDXYjS/KxCeDeF1P4xNhDojptAX
PNhPzc/xevDTtEaFHXMZ3fjxr2d3S7K9ZHgKw3RjJbTHJVM1MmcmS9oWnhdDDGXM
ZDL22bvzXEyu9IRy7TpJt9dspmrEdr8ODEoSUMRDJoyvawvBDjck+5tTKTDSObN9
8kUqdhBIDRnsInkkTx2uMaT1t29nAs1fTRwpO2MvMHWBDmqW6z67y8A/XumVY44O
4ZquXds21hMYmZq9DbiuMHlnsGFf6BwFDvPOaCtsXyGIv+7NQ/un+Recwhe2IOXS
QQF52mU0ZBNjBgf/TJRyN+CUzvGmYWCvQG2NyIdsS6R9/1gubWnwY11YfnbRUFLJ
jwDAUYJWpVHW7KnLTyUqAiTy
=+i4X
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ae516c0b-dba2-4071-ac2e-68ed8beaabc7',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/7BQUKUlqtnh2+lZyemHk4rvivajgzc2L79eXJEsn+LGJD
5n3jZtEhsoeZKHNYofgN0GWjyGfR0ZA4k7e2xA05sz719L5bSg9XRD4hvdGj7KAw
TPfWPQzpE6kKOk6UGVKM3IACJKyR2xCEyfefmNv2kL4TGXKnhtrg5pYqECpFWPL9
4qbP9sFTXodqLSFh2WRc9ACMGBkgU45/RuM/MfVr/I4z2dCU7iAq/W/khNpOouRo
oVkAZG0DreW60+SsiZT0PHCSQK6gipovO8pgKTgTbdVWM9lpdp9e1pVoXTkM2Kwy
xrMpT30w/4wYGUnK6KuTKqCZPammzTsk2gz+546sBB/Y6kys4RqQSNrguRaK2po3
O3sIm5MrNwmb64nOP/2xR8sQnNbfuGcbdiW1lD7ha2WAWLQBqBli7f0VF7/+oE9Q
tawWFFXzieXeSkN24GnP6gk8WeugQo7JDykHnBfhLY5djc4K5Aa++UqIGzxofiU9
poOFK/pJ0ooX9XrhEz48lkSe+lw+SPrui7eHskTb4XFsqh8LNqgcihKNlukNcmEU
ZhYAzeOFdY3HYQcz94iy5di/pJtGIkAstKQrBr9K9IEVLSGPauFYmmi9xHDlGl5O
5ZF3Mq6D+rs69XWoQ9ae0WiwXZSVfpJFuBwOeA0JPACbS0EkkoSkN5PZQOYdk7fS
QwGFCHxcp2icktSqBzGQzVTxDtdZKHP/E63ZI4n7wGRTQn5jyjp7qX/WFO5vv7b8
qIJdnMEP5tpPE9byZ364asNdpdA=
=Wdmq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b4f56ada-aad6-466d-a6d1-27ccb2cf1a08',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9EmBM/fAKNSKmY6B1P8+uqIHPeQdSkBoL5FMYbQ+49qhq
k3WHI3TMrOg+syiG05bZg2ih8TxUgkAHKI3aQSoalNm3P5eKC0po538EDboyChKp
VGCWXcivvgnVWSuS04qidNoTW5pZJftbQrDAerxzuoUO34gBW4/7BF7zSIJxwjsZ
v4fYy+sGZ/06Bb7IVlS/4I4mKBAU2CdnX8XMCDzOLnfs18kX557yn08wS3X/BtcA
IT6E3Y/w3VQV9b4EcxvelqqYOijglH/e20mU8PvPDSOfOTguZmth746MCtW0nQwG
eZyDxU9RvIKRkGSuLGSKrbVv3/tx1TNAawM9M3I5PbHunvZhkOeROQ7Tw7YbnjIk
DNLFVCH/vMXq1rTOEIIpiw+d5iL7y02oYM+jxDN9+luMc9Cc0J4bVdBRvgndsEUb
qKYIEWL1XVKbkJ+yCxeKz952Eon89/sUziOx+umCCSCVBuEpwr5aX3LxW/JHlGoH
P4QuP2yzAp336f1hgDINMs1imQmd5XwfYSe04YfJ8Q07t8D4fBYXRFs1pIvpuN8R
qd2NN9xosv7i2kKLY5DHdBd9km1Xayw9CD7DacVR7vfabN+iojwE9Fn/rg2wyTjZ
jYmuo9PmR0zN+sDUlwF3G5HGWDZm1olOnB36HUS9RB7SszdR89P4ugW0eBNOKXfS
QwGwZ7epeFZ8wkuylPLW6+TJtJ10pC+6n+vUhh6xCI2S5DRpfGfys2Yd4yAKsG4S
ZYsC1nqIgzxV3ssklPEJXKJvTuM=
=6zZZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bb4f02e4-9d50-479c-aa2c-ab4770a238cf',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkJAT9tJ56h25r4Pbx7nnYHxbKUdlCCeGJAkkoWentA7t
JnVEoqDwuC05icB21xkmtR6zfrunOes/OWwG7cwL1HxyAApM957TaS5wFkuEtm83
ZqFj7bhEqYTqNNaWYOqKaPj6evAfxauD851QHf+kNBGKUIVzN2wQRoHSZ0L6DPeK
b0lxQG4vn2CATrEaFs+rzCU8y37vTivM3XSnEcoqMdO0s43qHTJmxKd1pe6Oc8GP
mnRPW6ZOpICL7thkygI9WBPKQlSxhok6lwDFBXNUZssJqNlxnHQmPKtMyOxIelOC
ua0BrMpDxIzUKqJSOopxBoTNVQxAGUoGQw4lq6qp23+kn1OJm03ITCVOF4uaQ6ur
Gxkn8RRbFrKPjQBbAFmzO3Pvg0kBkJaL+K2Fp+d5hF80Mt+PIL2DACmQGXyV+AA/
rQytpEReMea1JZHumB+Tu5YeIiH1B+01x+GVnRIZM8uKvMeLXByhdouLaERv1/nx
KRItg0Do/e0Tx2UosEf0v6N6d6/z32xR6j1vR5XuVhdK5sc6Bhp8tThPPrFip4g4
8xKKa7AkGez2LG/hl9QXYq23ThPXSsHau/d4SX7G5nzosN/9ASiQvUcqXDo3KcNy
u2BWRo13Nb9duneMwZLrVKXwX3AFEpr6V5eiVGqDTP1pnDA0Ob7tNIRQMFg22EjS
QwFYg3k99yJI66MwjyyvSfawCs65tcmb5b2oGIOQxgbDYx4BoMKl/XL2UhA6IExN
AjsOcJyusFOqgG3DEoIvF+Opc/4=
=UDIb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bfbaf8e1-cf89-41db-ac80-760f602d9707',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//ec0BeGnfkTt45ETP8F9hnVp3Hj6Woz0U1R+MPA1OyzJP
4vqB+gLCBcbY6eSJaoig6q5JXn0mrVApex+FdCWX0avjDJOsCZ9pNwhkcUmnF4BU
OkwgW9O+PMq51KX/mbREK6fbwW89P6NKckoyMBvoNtqXIckmB8HXuwDwx+NOxVPS
5W3EwFQ6HwIlOtv3CF2gq9cWXipPF3aulcM+mqbXkg0Mtp2tWyQFiVJy2AdhR1VT
2Jmi1MrfDrB7E2Iysi0Zh5a0Qq2aPdZJvmVK3J/AGj63dNjQd88qg8MPAX+XVwHH
kpuXXoNFe7WwMyb04ER9Xym6168uof/mJgmkJ7UDWQtIGy5+dkdLevs+X8B3xSxc
3P8RaoY+4SkB2ZU8Y/0kEJOc91HsFgUpQLij0Mw1XMtUxJtGstQDRyKpKaKH9Tbt
h71brouhZgv1EwBM1j3VHx+TxGFP28csoQNUOmjS1mjgAowkRRtQe7RPOcrzU4QT
2EHvMg9dfUziR3mBfOyEbZvw6tnESyI9a2Qrx2M3zVLXArndyBJxaAYCDbkKFbS9
1vRor3tnrkx/IDkgjMxcldPVyLg/uErRSfg8eWiw0oYiFMbpXkKymW5yI4P8qLia
ObpUDTB+w3TYSMMpoXScpnpsrwt7avRsKms4JZH63LMlm11R+jMYxeKzLp/QmbvS
QgHc/gD+XXbNKO/OOHwKLKEgcb7CFy61TipijE2zHXdanmdbQ23tDpTx4K0llTRC
d9acZL5d8vuiqJpz4mvT+HwQ1Q==
=+r/B
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c4189fe6-147f-4e50-a6b5-5dfe37dc1646',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Opakm88Cx7/pjqm+St2NuYNxHPwO5zDc4x9iOk2+Tbng
dgbOuU6jm4kDjOt9w2/YPcD+PYSJ3i7FP/4JGpW3sRN5woJItK68211cPpJ6XtC2
//rliJHkzsdw5wtGROn77VunBs20MxnYdNbBMcQwvXwzm6dlFTkS16cc20kTXKWz
1PrUFidGCeE1B7oxae5B/aV50SBLoNbPvo3seRu6x0AaUPXUW+wp/eCBD7csZ8H9
nY78WXeQiBN3puBrz57Dzd3VmFfIsOD1VCUS/chkIrPyQ8B7ZZ1EbHWyvm7iOLDL
V+fWYPMGolG8OOG38lZ98om+9jw89MBdn0+Q1JeqPRA5lEpO5OliOKip1Ez7Gse1
P+kj/vKrxuk9Dm46fFMmYc1a/61NtbTpVQgxeNaTImJXB1aE8Ggfjmf2c4gIoGzW
WLQ9aukO/eI5SBnxXUqbmbiobBqGEuKgkeXFKRI63S0oVDrYQ+6uW48oEawS+gVC
uYp+AFDTtXdjXy8Vnjqt4WSSB+iIA9uFHatnKw+Mw5WOmziPCOSdz1OO2MWeVt7g
K1NF7J0T8WsQRZMO/iMmqwBgnlKsnuM4XyMX3PcQAInRvesEpq2Gu7g+4P+A6Jol
Lt1PZ7FtCsjYFAeKBsOtHbiUOiS/IykTLs2DetGjQQ6Fr2Aaeu5B3r2VjkH9p6TS
QAGzSWdeHC5/ygUs5YgJeGyXhHdLlX7UiXa3K0HwtglqMPZmQnMPepbpJZZIA9Ge
4cl3JvSVJzJrllU+JFXJmZk=
=kxo6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd2884404-1782-4441-a8ad-0380d9a1aada',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//ZeYRX4zhLMFalD70+I362F0GDQLc7r2mPVgKunTLRJz0
9gohlJmKlLnXW/FiYUCz6bFKK7Wm2NEhQwgOjRN8benQ+S/ks5J6Vbjo8auoyAUC
ykkbVILLr6bhDuzjtN18U5dUo0RMzl9C//Hvl/kqbCKCvNUnSGg2sAUHYZbP7Oa1
RH2NPq7Zd4upP+HIOyPAb/XyJKqqR541i/8l8T8cz8h6RaoE8lWsN9wGAmYYBrKs
E44pHHL1ICQFaecnrjqE0V8iHIvYCP7tu7UIAn/rPpJpJ09T5oqzsYiWSZJlSi+H
3uHXIfyT7Y8hr8kXZwDqGqIc7t6bx7jHR6keo50ELG62MMep80erUxX/w3KwWx/H
3C06VgZm2k5HSeWFBd2Z+8FKVn5OxnFDfq3/kAo1Fa4XUYBAdMuMUcn6KHFuvGUu
Q0R1X0T4D4819ICYcyPbu/UglH6XaeJ30bVP3WhtQVgEoOWsnpQWdGi/fbtwQqHE
sbGbsa/9zhCHjaEuJD8MDKNrLr6zst2cZGxxIeTucDMK+uhFBCT8qEsty9lvhECD
f97jdRfZod7eh0392pEZncEiYHVgr/tTUlEp4vGUpm0WjK98kqwSzXwwzqRkGhDy
rILAkXf+QYQ9Jfd/GaLdOnNbj0NdXRCNhM/oUbCY3X/eNR8HAoBUr1IG+3EKYS7S
QwFYoA0RDiM3ciVToobRXHcF/IDcgab7DG762SbCnxnJF+JWx4VyVj49IZvQrd6S
i+3IXIAw2foRrNfrIWp2PNNKAhg=
=ei1g
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd406e5ba-f78d-411a-ae55-39cff24c60a4',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+Nbt1WZ1Bxud/38DxOZ7WXy1MsF9qluO649bpbzEytKY0
VvK8CymJOb0zDDwm3J699qp/R09718cEmCPPoyzdm3I0ezeFjAtPTKwTqd9O8TzL
B94jPu/9kMZmaS+dldy8YJze7nE1QLLJoPWjhCUxFDMOhUWJn9SxOj0/pEs0kwnS
zmR60WXrr5L9QM+cXRJDPhQxHGN0ITe8RRwscVON4hEKouXAAUkl2O82xjeUD0Q4
8TYwTTrUyaJhA4aeEFN2vIcJPzbhWh/9qkpp5gzVI870EWXkbEr8KtfIx7q1O1Cq
CMcJX2XDt7n8Sh1At2wDiQHgW1hgnyBRKf+rWQSPcJ+rsWxeqlnG5Y94IKm6RNPP
bK/Z5Rllh31etnAK2pW+s1ypd9irev3oIPVhQUGDXhiiyp9OHo4oLNrr4uGpTVWa
KhOG1GV68oF61Ih20JxOLK8qtYxAhmmcMp3Oi+EB+54sto4R/l5WWPeks3/lr0EW
ZAnpve8rMO6+3v/S/BJWHNmoNsgOA9PVXlPoUEuxvyu1CReEfyBhxsXntaHaayxI
CJu6zCh39zsxD4GLU56A/9oGNBsNmyp8YLV9wyQ12JzIfzq04F2uzTliIacrJByG
DHzXKJyBVNcOZ81iTIpLjf4GkN6o7EH9IQ/w5BnPM8oCLqMoee5K4rnUHNoeuxLS
QwGNVDLwZRdlnqrOoQBrpZ1fG5DVZgMfdegIAgLywXoh0uRMz8AoDZuDkNjHvimS
98DD777oJIz2oaKUhMIB7xzP/F4=
=JJ1k
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd625fe1e-9e82-4fc0-ae8b-d95b93ce9447',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAyU3I9gP1bHR4KMsTnh1ooRugpd+whHuf61KHGX6QQZK1
mXO0lZ6JvIbn2BNoYNUn2OnAUPtdupps319MSFxuswnL+wtxZY7sVzenieyWQSk3
S7BYur1Bz+mUyd5j0648fChRqWnmGsK3a1eTAVCL/m2V45vVO64f+UrJtttVRNK7
JtNycbiIU3e967/1ptQj133dHkQeWWgdY0P661QbO7e3VqfEQ+ZFKvbjYeelZMTd
MkX4kg8f4VlIgFMgBxanhKOCeealKLxeJGXZqR6zYvI22VkmQ5u3gb6vH3GQnQfN
qjzsccBaCvhhVggNJOjtGXKJ/S3sLLAa/uJ+6luNmBSrASOLpMoobhY0mGs2Ynkz
yfRXtYONN+dk0Yk6h8aUCHoJlux+lPYnSvuvrZp3bryZxgqaeCKK7bVACwoRW/+1
qo6eWwL8rWW4c4JDvoqmxFZr0TDQF9aV/hMQN7O9TZfqqP3H0FSG85nLGVJeUzGP
U5ULuisXdwmyAVVDNHCENyulvorJ5xcKQ1MEkNkIhEJRv74mpJAIWsb0s3/qaT9i
m3AYFLM0fQ6oolSytKpOkkB3vhx48lruZ1EGDcnyae1pXyKV4qIX9mVpsCfgS7bl
RE+p9UULKYsuydfhaqoO0RBUL/sHa3xpoF0Nco75nCjXQqpTVpqCDMy2E7PdBczS
QAG2M7wFukl2dFSxhbVQtanpeEkewTYylyC74q+K9tocpv8elcTe/uTM5sYEI2d2
46K+LH2TB7WMMNMiUoTmuwY=
=v1N5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd92dd8f6-f458-4a15-a989-0060fdd9d624',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+P6DEv9lUtueHVjKLnQKBNkmIxdOtTJg3xfSY2r4kzBjs
bkdzKq6dRaK/WDTbWcwelOUw6Ri2Eu3zPeu6GEuXIdKOk/uFx4W4QHeIHGMG+jhu
vDG83avlSVrDVnGkFCe+d5zNVWcDWa2FzjJwfsKrlGi1hKbjlnlqqNsoJE3xFwmp
Z0nk4BOdDFXqVGYz82NP+/mhWDjyUhrnWNZjoEvIvS4OMHlh1ueBR1kB2/gOu2+y
r9sbTRwK7640C/5ZJXJmMcqhAeI651En8QJVVedR40Ala9/PxN0gZGNa62vanU74
OqagIMXAO8qatIHHQWdlGAkpkhJIYGNdyUC7sJ2qCxullgWStcj8TVe8MmgBQvGE
4CHGf6TR2LZIl78WvhWnN/9KOcYemBUL2W726LVzKOtZnXpWdXn7LTSuuQSW+bMg
AgqnXElSMww6Mm8k/4f3F2wbm0Espkx4f3bhhjzj+ANqLmmUDHSkrjA8Co8p01ty
OTEkH41OPlA97WekJ6crXEqbXCeuLlZ1vfIsJkZW5eEWhQGw4II8U2COEhp2OHuH
BNr5dJud/XWkXRDsFRZ1yWPdJbz3Ldp1t/BmxQiJvRJ3uBFC6V4JpraNSI+R9DyA
qIEcsmEe6wt+Pn5nguCvqENZtIoVJIheyX5bQA3ZhWIT3VZUP2naXsp550sMdivS
QQGpT9UMuwxitGoA41ySj0OF9CCJf71EL2v4wktaRo1kas8o2SQi4FlirTw8t/L1
F/g57zsIoRjmdNon6EFJnpe8
=AX/A
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dac16c72-c048-41cf-a339-d14dddd4861d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA27/v5BQTm3G9QXta0ege5JbFlucM0iW4ET0u0l5iqbvZ
GMHatGqBjYev4BwHvJ7WiiuQeKEKyYSpQGwRUeOpNltg8A1FMC9yNc2eQD3swz/i
ET5M1jUHjbCPKoRqQuA/KmbGxE1KKrjkXPwIJooWAy6E2/lDAAUZD45V0Q5tjyLJ
cCPY+veSocP2+zIlKFoFB21lhLSQKc3XfYWK/0pVz8/X/VDEiZNFF4mZEyFJ0NV5
lsqMsMekC/akiyEUzbIlGEz23poLMLr4fhy75fCqcYc1sMilvARGEJymCyyLKZ1g
+IW6GiDIprJhYP5V881TdNE5nbbYG25WbGJYbU31V6V7jf8UYQduU8C1w1N0hAOD
fdB7SE6mN979Ynax9KSnEtM6OKjni2Ls9Er7vfcBsRoR/L7OrUR0mjX+hmJJ/j2U
76u10s3USa2u7UbHVP9hdk9omiqcDi2n04mEtV1HSf/IYnId3Y79F/0pqHBu+ibA
Ik2DFtdTx/LmXbSfdNnBNyyz4W6dBVX0f7Es03kUv3LSTn3tlqKCLcWZOxHyd5Df
nnQdIHUuJXPlx4R1LWsDzzHjpOotDsr+JPRxt6NdD/4UcNQtyfxbXXkeyWMcbPiQ
MvWB5cHx47pS7U9YTs75UMovF17BDqjZBleTDun54L6wFDVXY4M6lFBFpiGiTdvS
QQHkny7beOo1B57S5OBmwbbbzh1TVkDzDpWqeH4CS4T2pcpj0AJolAljh0EHc8Ys
W/UZnpup+iiISKUNGBiffJPR
=pazF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'dc7fa969-d2be-4a7c-aadf-13b1c36d7377',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//esjZGbowXnFprt7UVdnG1ldRK2pI86isjkdEYzPh7m+Z
LIz1xRpgN+IVYaD3PGjuKCIv2xIyPOnLo7QDtnc4R0wOetUSPYhoyvJtwkSAgooE
6J6r/Qjf3Dmtc/rWCT/hoRIvNVP9DCdDH3AAbtc3ShscE8DBN2XORxRgTKFcMZZK
tXDsnxSJ4QnfR3AUchYa8w6I3nt8HlEn1S+AWqsNpid9SnizfljZq3oj11NTY6e7
DRy2mFlsgZ6MUWqhqpG2CKTjqOL8MM+LchulfRsprCRFlUZkhulLCtfI5EqDxojN
0DbeAkOdZ3BwKLnLG5/XTVHsVrSOiKwu1YF5bDNyz3rMw4R8llP5F3uzPmIcLhEO
tzaeqY0RtKRlXsd2ABwcPWWonoxOJILJlpuuZvrNXDVdXiSmDUjsa/JQtJQu+m/S
YnOWELbzO7v68BP6UqK/6FjqBcrRd0w+vRgo78dPsjjcbIpbJrCuylvnFX+SoS3U
hF+0051Zz4vakK1nFLORpwYHfP0mnTEGnSCVtpvr41iQs4/jCdraKUk5KvQNX5VJ
IMT5XK2h7ojukt7FzWJ/N6kvJuWzJzTryNb3i84MqQNflhaQn5lNaSOf4DEqMurW
9VtPltzu6Qv93DwRMZvpwTS2IVHWTO1Mj488tKbvBUjJq630rfKTUf+RL2zSEtnS
QAHNJk9SDh920tzeqGkXt3d8s4PyQElcb7C6ALFfqDcyAEZTUtU6IjHjE8akCBjW
I/kU0cZ8QtIzUe6PAoapYIY=
=n/kf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e7074c30-8003-486d-aaf2-611de2cedccf',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8DiLurC++SiXos/SbawdteLj/XzPIf68vxaZt15nALX0V
noNXCvXiQOl677b3P6H5dWdwdgGnUiyBpcgwCZfpNUHf5M2kZbWb9jgNj8shv2OJ
s8AOJvgsrfDxbegVDFPr9nfgBtyF6MRYXZRnIGLeE1NFsh0QIXYbWBUnu3Iz4k6B
JV/vxkNUVPhHBAui4rrno/j3i7s15EM2Rer6ngcD21YOOyXmOD91+ox5ezqqvXZV
vnjZ4SojG1RHjMFdUKDMHPhko7epVnO700Q7MTmEu7xNbCcBXoUwkJHZniixOve0
RN9BhP3EFrjjYEKbEZgB/Lt+5A9az2c1/XcaiwGxtqgOFiYBaOdmCiUCjYQWaqG9
hjsGZqfsv/EIxNWCydCZtEOioEVy/zPtPSm/gvOp3DM88MTSft/R99vFNe6M1iQx
RfwRCR2TBLXjTwVtmvaEp0a3zv1JVRNB/1hrT62Ljrhdokjz7613c1nsN1ybOVMD
/wYaCSPsQANQzmxprso5OVymU3S3HAhYnOvsPW+slKh3sO7iyohEp5h6aKPd7D+2
rtl9Gg20iFcg0Kc89hPO6v0DdPVdxRRZe2UnpFrzQNPHeOenVZAuwdMb1M8djg7/
4g6LBM8TlKJ1fruxxRQ7mnkgDlIihv0Qzj9fhcsWwWL67d9RQdzWkTK/fvbHxBnS
QQHhegbOi5uAbO9hQgJnMOfMoWU7j7tSAWk3CLHNPL76Twnptkj9EGkPwfqESdd4
3CuwQf7LOFGJq6NGe9VhHUXp
=Ju08
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e8713936-aab7-4a23-a84a-aee2885fed13',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//V2Ozm6C+Re2EQLQqj5k/+g+17wkHzzUgA58DJ2F+GKHs
VdHUyervbbJ2Kp0MInvchIVfW7UvuthOg5Tneo/1TMnraVnXBdRyH1A4nvgOmtrn
KEuGQRfMbsiPANSjlsoBD919pSfxlEk+3GH7H1Sy2q1Ea6wsnclZj8WpF/alugUi
C3hYaoimtJOv0mGDF+VB4LBRlo+9tnu9+ac5Lwitpolc8bOw+axWZw3xxTeoPhSK
QAx0rTeGZRaSyUOTj47XbiIFNmLV5rpi5nTG/ufXFTNlbfBuGWcwR0G2/mDKKpLK
Z/KsTcjB/f+NhhsOBAte8RogXPFjBdQWvgm2qBBZsVlQ2i+qZvlwEZYKdupa2KpH
cp9amr3e8rk0he9sxeL8l93k8y7f03pSWpYLOMGebndJ4hqCPjxdOQWBOd1zGv2K
TEjvkOSrTyDQQ4+PNKkpULlGKYZAVb/A69DcUdb236E4DgArFowOTZb/66T3oKTW
O0vkW+5wqFR0dZwUTuzJkRPQitrt3FcARYJQkWI7wApQLEuzbkQ+k25d/EwPoUQz
hnz+I687+5BXmT+StzvFkxzNdiuC9UU6Mm34UlWor4GvD1UzUebnRERRqC8KJEdF
jNF8STcItHLJgchrcyPr8/Nw7/D/07IYRj7a9ocSbmNO454dOhbf8ak9PmtdhK/S
QwHpM1D3FM8RLonL3XGhVJMqn/FmyexP3bdghbMvWa7iWvHjYIBTvsdWCOIMvr6m
AaIDo29rm02n7bT91WaJcoWvJ6k=
=mW1S
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'efdfc011-76e5-4062-ad3c-a51729426719',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+LXVX+mGEeKsyVgKsQcegpWyvr+SLxlDZwNUMoBZNodel
xlArGb2xUTkAGbJcHcN5wQ6fvuLOh6vYbp1pwFuTmZCfIZgeRyjuzV0jPEo86Y7b
ojXUjVoClJo0EFgnFs890xaEPPJqJRq+elc0VoXzP3xC8cfmqid9dlQmcdiLRXY6
9nrqUZ0C6VyIEqoT/V1gWRbQ5kS/GQZ5Ez0pNUW004X0527SC7i6cMnm25nofK8T
w8/pCKC8p2petsRAXQFPCuT6ZkOJV7wq68vMpjC/gmE2sJDJoOuzNSZ4LFeoHgBf
29XRdsVUSfMB8LoXb8w4kleL/yXbl+E/9og8YS51nhlU4R35coMRxrbvRLPqbE6c
HBK4lCP3UWuEcNiaw9IkLBBCWsUzJqs/SUmvCpACA4W3WS7Ib0ua14BbB5TrJbUA
+0LchOkBVv2DSdNPQGK7IB/Ff6NRlX803hXdu32crDgEhKxE4LhOvKEd86780YRa
P/fVDe4Ey/9O9ZIaS2eYdyzl0Qqvjcs3XHz3vuC2CBxkGNuEM8+VPPyXczfXlrxd
fvocAGe8DoPYAqIFPNc/r6ozdVpwaeF0UkWEezdq6BNlDkC2k0kFJhoTxtIFM9F9
0SYIkPlZJI+3FrceqGMrP7L3ruBETyIn2RmM6wSTFjcFwyVjwjpv7QB/s/2SuPnS
RAGdS2jdtU4v422ZLxjGhUMgP3FYqIjrtyuZ03q6AolcxWnJa1s9O9zH1Tmpz+ec
wEKwAFas3l1jO9/fr95hPWS4CAq7
=OHhA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f2066c9f-1623-42bd-a3f5-9c3ecc611f27',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//WmUTogEqfQMEbknez3LoPhx9nQdOhxHCbvyr8deALEIO
YDctP1za5MYdN8thhlk2/Kg0NqUdGdegy9hdDa3eGnl0dyhiyLjBclcZ2bYgh4LJ
oc5VotL6+g3/FeOFNpwnZoA4Tb/PF0msFmzNO6A6FtzJII+4cK2u8ZejfY2wvLt1
MiMakqAFmd+xVAiVkHn/7czd4y4CqO71Hspz1JkAfG9taSKax/iS4Cm9sb0CbqaO
pf72XtmrGV5za+FccWD/Z2oSIQSSUUhxia/Ilnd/Xi4mCvn0UlvZ27YyTD40gWNG
VJuBU4JToNcANAfMFvfX004bhD6wf9Dw/HtaF4vF3lqfQzMNiEfRHTyJyyG+j16a
U7hS//jum/hlgti4Ux4mIijwbI1HRfumEObnoNJdHI0OUm9VtkKauycQgPKVuzRD
ggahxIYjWSI8Hr00KqNLxKsSRBLRQIglEL99lsbniPEHMZqTsCYbkA/Y4S4MFGkz
DgxZzWOEx5/sZVeEPlIgr9UhukBobwmNHLpmifZhGQ8eWaEI/nUJwho9XfrkOg+s
m/hZuKKfa/mxyE8iTGkPHDnC40O8ALAAQ8Fe3oG8IeFN4q0vLhY7OLwrRBlF0Ydg
nvWM6Dn/5WAU/sLLRer3MJadldlxMBIFGBAHg/5wnMA9ZPQlqkqZOBlrUaOE6OXS
QAFDPmZbpLseCHhCE3EttMTZfQOT9xRi24RPiowbGRB809SzSHHW49oZnveOpZ/7
2PF2PtMMeYvw+4WEedfZRGo=
=ISh+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f9b6cc2d-095b-40f7-a66e-8732e5e8917e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9GU5HfDy4txvVS4wBQdlLkI07dxhh0TkYSRzyN4hl1oPG
b3R4awjyur+mGc9QOmWFGOZHieO7YoQ/YNsnD+EqsQXtg10De4725FTU+99AllHp
Xuu4A9QCtFXGxVN+QYm7csUEdtBUVN9VF2gnkTwlOdAlF0HpG7d1KC+eWUbu/D6T
f705TZ9DjQ7mKbm8ajxxnsu33KeEdseZGJz0Y9Tok32/TPwaapx78Q7RFRntEz8U
Trhr2ZDIYQp+zINshQGfDCDZgoFxd5tpRqOuf4p8QqKlNDVjhYg8O5AnoszglVma
PKsxzmqx6BOxTtjnFBNlrScmEpNtBM8uRZzkgpTELg5hz4S9GdYGp1GSUt4lyzWW
51wquQdAQ9LJufYMEvqbpQS/p6r3VI+M7jhDr0VWVaRExTv1kS9tFqzWLkWzLVzs
GRW8QbjACAN5SuY0ym72KvtjU0GplKSC8w6dhzRyqfXJcVpVDpQlcSbXWs2eHn12
fha6VQ0RM7voDsx4TBv0ywL81pqw/g5HKDpd0brRp7gazuk9n3pnvke2vlDDBGtM
QkpqQX+9hDSIKLPax7bdtu8mcJkaDv7UA9/tZAIw43YPueco6iRD2KAgIOHWL+8b
g9y9Y5fW0aNdkENX82+wUEl+2RfQ5eYjtHaSFyO3Jf/U+9aTHETqcdPx5yw63f7S
QQFOtzW6GdrSPan90isgkYol1BsSO9MENPq7m9F4OSMVLRuIlZHKOkojMacGApD1
RZkzYRBeQf/UWJPcsaCmDnNT
=B3ZP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fa2b4af3-41a5-4c0f-a806-6f3ad96d33e0',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+OYp5B41TozCJ93o4qYXK9XILfcbvf0it2jTt1McuHYeb
5v4qvuA/sJvHQEPpq2kNRGfeBKIhYPXXFQ3MT02nLoNLx6KdDkt7IrCMln2sifIb
/5WcIpyJ4boD91yNszHIuljJ9mggq6/AP7w5N45GAyGofTR9xaU1XKv7GZVWFxFN
xeY2jgmOtWbkapYFW1RAJs7j1Sk6+64IubrHc7zherVaQcHqu4pONNY4sfjB/ZhZ
W737s3cjIooBphzdm4WGH50WZQ/kOyG59qTimtHuDnM5Am9nTdYM7w6yNBBnAsbS
rQYk+qAx9tXQeLMu+w+qk0v/9j6lo0EiogJKEW6U74tiLHsAEGjUz1cIu/Z8BsnJ
GN1KNchjFjRu0fpdil4/jyFuahqh6KKA1xsQr7EK1sSLW9KFofH/hTMu6nAhNiVU
25gWOLTYN5zorH0IUd86cHEFXw5NOq+X7UKxnVpV5MZrvMqfPWbRJz7DGQf+wj0q
ShIHGeyBvaVGhTS2oUrucWnN/O1fdYP9e/5k877l4ZQeDjyJrSGdhxOuVxbxXpzJ
JaUCrSq3BfcZSt5mTp7+/adYjUToNsMBA3Purx8CjdK8J3lbXIBSt7BMgVCVLKI+
QnpkKz3qXXkPk5pZEA0XgD9wMUwSFWbnLYZ5r8eQ3Eoe3v/n2nVXiyQd7pi+gR3S
QwFb9KjFVJ1og6ARvM8lpx5g+Ym9ftpcTJACngysiZYpBgE1TpI2Na6Ow2jyZGEi
X/tWYnCDMsU82ap/M4esAqSQoN4=
=qR0d
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fc5d78c7-b59c-42a7-a219-f504d1dc4de5',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+KYsFTBuf/YyBgbJmZOdqoEA82QklDLwBMJUJacwfy9lI
3qOyFkNkkdieWi7TRtdYVxjgc0FKNt2Bdrg1pG4aAcpzxmHWsN5z9l/4zy9h1F4A
WgL2sthYI0vI859IzBRSYWn8DiCqFXvPQr5KUoGKovaOoUDUjyuj+ihvfXay9BQy
6z63sSJmXLC0TCKv8nDEvoGorbQ34ry+58VUjOgnE4mehzvcQs3RAqzgjdGbKhcM
QfxcIg0xIwti8hhAnZADuKG1kMcfFbUAFPTORyoT53aSf8JM6ndODLtaVwRxxF4N
neBxc5CfR3kKgakcYeH+zZsEvD32ysh43Lb0MRLcaY3VFFFOa41X0eBrokxUofia
mCMY5B4uJNPdMlaG48Mp7hcvkS+NY0pM/gLHFuJqbeDnsyi1mRE3CeCAi0b1mxVv
8wPMQF7aS+WzrXQc41bGWSXRlaeSa0q7LyIHqWZt8ISbLjJfUwHYa76UEjkg2316
t31SbRQpeKZpDEeAuQkrlUqVS+LAqqXY2kjU2P7jf9dViJdPuvaAWJaXPnXZVn+K
3jFMNW4rKFgSN+zknreSlt2J/nWEqKCLtDQVPKfkPnWEs/yGSF4iJb7ncBQ09WHv
TMsEYef3RSvlwnhxlKUQz/HsCQtaT1xP/bGqD8jQIFD0wD8FXQO5/eTlxR9LuOHS
QwFe37W1Z0xLLY0UUE7EzJqg/586hGMw3br7hBPjviulFoHSKZ7RhNJov1g9ezpM
7Ms8IRM6mzuR6O4SWSsx23etJrA=
=QDFa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fc67a85a-f27d-46d2-ab7a-491419176c7a',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQILAxkA6B9Z4y2kAQ/46z5/but3jWN3UamHyDgfgYtjugvgTxj+H2ki7ePmod9O
5+nNJTEhu2aBvV8U4a0B2VY4e49kdaXEa+Yzj5hYt6NQXDJ8IZLTHVtF2vvfITZn
widxOLj1SvsnveZurfuE/0XqzvHBSw2hlKM9NbeMUfCOFHm5AjK0Qlz69wpuDcPJ
Pdt4dREYExX09qHCylunr5kJtJlcerm+tH8NeQaRlhk7W99ts7PEKVXonpJ7IY4o
92rTlLWdqILYQm92cEhH5uGv4N/nyTTYCJU0nGQIiiatlvcw05NPQ6YoQL7I0hpL
wXBlbpUNDaO6Q2DlsT7qjUrllfGM8jQeuzcEyesyC+FGZCvfHeLDDJpajCiU4XHG
yog4f9SnGXk9tInDv1qdym7hfBwec9uAT10SbUXLZVThrSBQMENJJfp+bsMKoLCn
e637LaBzltVXX3JL1ziq4n/5APjOIRxowsRhhnKx3UP5tgtur25JHWdgiiS6/K4A
CRPkuc98UC6y4gzwSNiJyKr7Yk7cmq870/1YuQyzFYcrjcS88vyI8FrYoF9ADy8l
WzpkRgKLG66/8bDR7Tni/xi9xPxOGfbEz1PgI+F22mySUNIrmAvx/rB5RP7kllFr
YzFianZK3GlmBQACA4FUiE+5ODePE3FKJp1P8jfOR1urRY47NDSCa7zGitRxXdJD
AfdfrbJqnzlGujAw7fKnFYzpmXHDTFci/oRUtWwhtEWy4I8nhyEyJeXPQSAS5lNM
zl3KRrzhx7GDO18OBeYm1oHzow==
=HIOY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fd6fc5d9-1d29-4657-aff2-6eb6434363d1',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//eApQwv63cU+EyEPrP5YucUU8vvD0yyoyl71POvNwaBAi
yudOMN4r9ctnKdLCx/hdXpt8IQDXeTTYNA0eQN+VGa/jlYvLWwr4DGTGGPvqwxeO
NbKBnKfEu8USSmmLNOCdgGPOnv9Z87Hxdtoza7c3hrZ6Rf92o4v2HKose29aWv4L
p2HEw+wvCbu/AnjbLXPnkUM8uBFsJpxNwiS+Fr1joezB0OARTVFB/EJJETJ2wl2Z
QBwUZAl6Ix90sozawHD1e6KGjitSNWxA03vV/gtfeMYWchv4Hrz7nAhkxdNW7Gnd
SFloYD1eAAokvPwOVLsSHrMzzYuXYpewhax+H43Zpq9noHXVdOrXEu6MD5r/vtpi
A+KJ3p3rhIijMrSQescUcABvLOaKtovX2unWTK9/otCvEW/jDe8d7choJhI566Ey
ax5hyuaTcIFpKmOAyvpEy2WgwEEWnwQNogfYxhUnM4Gpj6iFK+weC8/cmI2nHdNq
v2jQsXSqazGJ/iEHgMk7/tOLREWZBRi4oiiLB3JLb4o36M6zZQRvqkDhkTF53ymQ
scefZIU9sBNZMlFzRQZLkzqZS9uezoXwtoxX3bhdx6r9NlFEzHCSWPO8FGttEmoS
pgBmfIiRcOPsPS8EhqmO37E856SAZHzcimeSI85mv5HEUW41SqpR7kAFsbYaYEPS
QwEkKmVHaHLUh2G2ZASEtWyHAZIEraPjgX+G8h+UcQcYtOE8RZi0og/WtZRFV6zp
1967G9YbW4pm29vsh/XMiFZT6zY=
=q64i
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ffab47c4-3c12-4421-a93e-292e1089c72f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//V5leQeYXOMPGrofssWcV4hpASm4wXMXJZv0EbERVjfg5
iCGs0VO/SJjod36NpxGGnSm3gHGbYovSnRv2DDc0E+5n5vNJ4jUNIh2vzGwbgw4t
ZWmHY6zcHEmGNFUme9uWx1PenyywTcdl+TSuqpfJeiT6pcgNKGF468pn2K1zaAiS
pb8MumphfqCdwe18QyscNpF2xf1nO6zccnO8m7sMqgKCANpRgf5nue4Lm6PzbD8G
qxOgNUwq2M3LmfG5Xb+mNF+t1aalhmsAM0uQefaXokEu6MxEIQnhTqU4MKpO3rF7
cQhKNy4Tfn8LgFB0jkUkacR2AAZuJ80i15cywFyxw9dZ0VYgEO4UT92gJIE7Q6jL
Y8AzPUU4dxR7IgppjlPz4be+HhxiUFNOg+u97eU6AjCpr9Ke10EKZjRKtImkrQEo
N/qTN9f1IyEJb1k17GTvK0OMCSwSH/sSAxxncrRrYZ5CR459nFcgi9AQ3wCgBuBk
4GKZ1ugcafxUUrX73Y7FhZ5+54O6Wr0PVwm2XYs1V/5xnkyPt3L18jlHqFY508GO
PPgC+CJ27IgixhR5GUk+N+n+bTUBki8GAabrWe+3AXWp2VZPKN3Vm/Kx/kYJ4A8X
pyQqVDHr1OAexJwenXezRdEs14JikS/qHenfgQlGk7Utwmf432JeLD/ow05hzNTS
QQG9SC5dLRWzNPSNsSN8iiSoWxOSwY1gu3qixboQZ6DwUFbHP5i3DCkNPejnS7m1
/a0m7CqeLLLEPy6eLlyaAZzq
=uNIn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
