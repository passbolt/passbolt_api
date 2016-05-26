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
			'id' => '03fb188a-b00f-4188-a4fc-214ac31eb9b7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9FfdEFNaVCT4MDq+wfZIG9qU13toZaYilZkNYzcx0RkuV
AxkyYNIy3ZM+X13b6WW0At0HZPvqo9L0CehwqBO0wllnc08NMFl0kLczQWiVI5GR
MKQt+F97Yh5RLpeVcF3D+9FEZWwc+qXB5Edx8FOs0EMii5TEcDXAr/UKsrI5fJGD
qVXwptkfe/xKpwjOEJOjAaJLG4j0khzkOV7RhTlbOKoRyyCGK5dzU5x9Jhrgy5Qy
MLOu6FWcUVX5nlA6tLmazcgP3GzUQQdWXqpSWhyEGTH7xXRFNIw50puoJk3PejOK
DohpAUcMcC4/3RyTJRhdbZ7frhFx8LWF+DF6JHulN3wvubsFIl4a7OZSBfLhkB0S
1tlyr/7FKcyfIS25vB89Q8JqTYVGGRNYok90d06Q/O3VEFveE1LQZPpm6CmDJjxf
voDs9mjtIOHcO1EZLihEJm313/FY4/qjTKqDUVBWYfmEe5WxOM+6ht1hdywky4Qr
9hvaf4Kv0lucyiVzAV99PhmJm1zQhLJC1aGhKQ/4ltDnTUMMmHG2f+6y5VuJM55a
Bw4gp/KQbbZFUKddMf1iT57ukccc2Y5E6Czv2J4ev6bNWqycPSQ7qlWsL5ZHrqOH
6kiLO5NbHCa5ITgwJW7LD9Zqxx+xwTOqrK2gnqzWFlBFo9rv7M62GbTXqsYU+m7S
QQE+62WbdeC6R6yUH2SCG6PWCteuCKgDVu5+MTv4++ZLV6s2WcMoi0ouhkbiLGJL
Z5XjQ2aOIrUhgSO5oPqhGurE
=ES4C
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0ed7f605-566a-47be-a5be-826c38149e08',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ//cvAXByHK+dq8L3O8brNMwr868tBn4XpyXgblaabx0YQM
XforWEFWATTLFX8i1GBnBlCPhj/BCUD9jLJMMpA7GRx93rmndyyqbLHJXenUVxzn
sUpDaaMQ5NE/ZSbooiaaSVb/k1duKSpvLXGTugMckQW5+0mQr+wKlwKyWhB7Dgk+
p+3cq5ZTFXDnSjx02MG4uhFynjRqIp8DG/EzFZ8NnjMysn/wqN7NG6H5YmwsCeOb
BpAeSyPRqNhtuWto8zfKcqs7XGyY95GDb3hohFtyMYiMeEgf/KSa4PdNNsg/x01Y
wmojS8vUnAk8oTx2Ul/IR9/pMok7fLEZIWlMoSpeXPMxmkVdS4oQupDRruIswQyH
eC+oMFRkXCae39A4/2ssBsz6E5QOg6CW+u7QZc7AgDGcJdwzg3LO5BlvBQ3dGtoo
0HHTdnbSdX25J9tYbFXgZzj44GYI3vTlCzCD4WomPuN66OVWUFMXGyHt+Xh7n4fq
rGMsQTYU09ifLQ/3iCfml11ymkQ5gGbs1ZpTEcPlY9Jmx8goFIkEV7QONwU9xxeZ
9C8Z3NcnE4RvqqayfeHWLacLfSAp6f4AgDA9nLmf8imyNDKWw4fgypIFXYiwhBi+
spq4zcSM+lYwVR3zoxD9y8bwUmE046VjNL9OSCkYphXMCmq/KoCFhD2gGGM+5MTS
QAF5EQar5By8afbVlfoQyKW5EqlX9xc5Z2umnLzBykQISJXWzcWrsXBp+6bUVrg3
EuBzWddGruuvAtHRFsrhPSA=
=9QLL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '0f12a29e-9155-472d-afe1-fa99b1e020ee',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9FhlY9UDV/ZdfITmg1lBqsmFgYwnygQh4QB6VnewtM8k3
LXKowcKimSXbC/RGaXSfMIcvI6bHoadg/MZv/vAMMsYZg9LI0UvTOzmRkcj/l89S
VdTZn24OwjgUNOM7YQysE+sNEyZpO43AWYE9WJrGJPcxtUJTdiHwNT5DTLToa93p
U3FTlGPkBtTtzG5LV1seBv1GPANtytUmaT2FgHsLdVXkYCRjeN2udVFSyrGBSzUC
JkMvBHgdeQWeMXegmwliQDiYG//1jGQ2ws20PY3BSU98g8sKCcYY7H1bCE7ZJoug
AmD29LUj2CrAQmnie0pOTk6QtdkauhV532WzMhEYZjEOnvVrdD7itCypnnfVrdvz
CQNd85h/UGMhfPgZbbCIpUJbI1cbucRGcCQO8m2GzF0JNN2OztY4iCnqkkX7NVmk
dV/FRM3x1x//yGbNiykm8m5Koy6HYCXMGY6wu2+KibtfCd2ONE/+QDkudxrIr+wi
3zQ2fY0aOsv6AigOQ11V7oi+swvroWw3F2qoWgzzXSzZVkGEA9Hd4jrLEp2F7df/
Czkc0eTmWqCxT5OV3W/AEF1pLNAiS1sMYwHKI+JwSsSqVYP1vTQI8nnMUKLftYjT
nR480vz/vmpUGMQIANryeOF+bv5RuMwJn+cocLLJViqPH1/wGmhoCaMxKWMxXwbS
QQH4XcuX138AVeLBvvIYT5ZVgvO+WvyUoDJnSSD+eQYmxjA08jO6d5zK29gIDN67
XnjflaGkf0UQDIKsLCNlQZXo
=THU9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '11fca535-4ab6-4de4-a35d-c3ff1ec74d42',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtyziuoqCA7cBpDqGHsNlB6XwfqtDHyU8wg18S6x+4e0r
PI+tItvUjf4kplByR7xbWFa6ARCGZlybIns5pUkZRhczPGm6zAQ7howmiM6liESu
Fee0UueZqI0i6dWVG9A2u1FnLqCnz+Osu6fwK7sM2DKCmQZMuoE+4t1d+TN4w+X5
ZHQxkHlVuz7sN+XNY+0XI66dpeyGsApXhmD2tW18UyRk/zGP0t9dk+nsGufvRFJA
9zqZEtC9lIPG6b6d0qQjtyanlBHrLrozVniLtoXS5TIRJnsKWALtB3HB1YvIKv9z
gmoxLZcJTV2KngNv1yTuk+OLY0r5pmT3KIXdlXd+o1uNWAx7mQ21xzEzzcI0kUSQ
rDmcS2yMw7nKx3f2GxlFF9S0cl1Vh/bGJEpaDTB6o3MxnuvpEzjhQbE8W5mkf6el
L+Utkh9r8EhpviartdHO6/DUionUngra5a70DlZBE4gujYMP6aWMps/o+6ofpgrP
WhnK/9eIP+YhSaZpwFTleQcCYM43xF6Hx8TlfjVr9NJcyqYcX9GwaWpC82hgfuwz
/3vBLjBhPHT7+egzpFXcKDWJPHrbaem/YIJcraa1/Wif8ZQVtF84sjmJDUOS1y+L
ztqzv12b7jIf8Sdh1oP2BAkhheUqx4O/9keMbgK/Jr/nUj44npv3jncMCSO7kgzS
QwFAU6uc/j73OYqSaZeVcMU9VPfJs9pHTTQArcJAqVloK8jTbE6bTmebsr45HpKa
DOD3fp3RK1fopKLNM5mNu1UkNnc=
=flcq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '13b589d2-7187-4c38-af7c-277a1a5c463b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//SIDTX812lF+lCtZfzNepW8LL4MhLtMDPA0bbi6Q33xYN
yjP2H5bOtVjKd/tq2cW1CtiGv6olQRE+GpuRc/ZHogLf3SZJdXOY0pXwvdUcVcco
6DTLwYJE1LjBZ9QY3LIgcWqvBpMdJEKihjXWZuNuyE1BCpXaaHfV9P7ZuVmEEGCI
AEEKT2LNVFZYwJEVbgr2LzdL08l5C9yhAwv7vHwjRAJgsgm06pnRuutc7DgQmXOr
Zf3YEq7TtKD7y/pBjjLXyRtZR4KaelqkzxVwp8KY8l+PPCmE680YUD332PKcZeUI
5U0eyTX/qwL11MlH7tSY4GbTba4XwXHAQUtxt0pDSxjjf5qMmE1D2xNsbDHf8GGw
vnQqSh0JXnoU/Pka9yOMNE7TA8bZnsMelIr+bwIcXiVvbuw+AoKBuLPBnti5T9j9
Z7xOxaVXBp2HRKq7xgANqqM9y/tWw7LCHMSnWjddI+dkSGfjOf3CWmKUD6dWQYym
8lqET9VZiI65reYqVgCRCGCvcXlT5Q/cZB2qcxQNzllGbByJTw5zI3I5DXYTKNVC
R1vs0fBQsPb4VNKMD2DRLvvScfkylRt3tYAfnjLnojK2YzNj/MW11Rr4S0j+9KHn
kxb0lJ8ELLljLrGEJflQHwfEn+EakS9ppbVxT36VSjzoyyzcildL/ZB17AbQtY/S
QAHemizsFMQW1Vy0ZUqxU3LNVcXQMYvnmqwkAu0slKAyGteOIkzmdWCNTHa0JAhF
JzZJ/tA181a29RPLln/ev6o=
=rSUN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '15a5828d-6b55-4da5-a2b9-3586f1626dff',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9HVGmpAeWw9q7YeFY7clpYcnJ/0xGqN9V8JGSqnDAVSjj
eQ5RJKGU/whKoNMgQjZ9Nfsh9qiB5woqp0twi0LP4ilfGKTM5ZO6vQIE0+aU9oCh
MEz+G1ju6npq9zhQnTs0ujnZhpkvxwfjp0QFLtjqiFDWJdbjtBSJRjlAx9i/wAkg
XWIK9kpFv1dh04BBCej0t5uXhFzYdhsEKjKliOA1b+n1wr1S4h9SVmcpLExSiJXs
lCB+WZfgtLbUBgeOK50M+p4TDE41U5WYLD1gccQRPmqXIt88IP9PAlG2nCyI3+7R
fV5wWJ2uNZAdCRB6Bu30fl40ICQHLMrB50MDrodyOGmuGkY8nksT/wiXVPwSEoyk
km1f2ksWj3AshjZUhgMAuO+Jpm2F5XKvgsgCG0tFgnUuB3pNYW/y38KheQsSQSRw
Wag2kBwVaZbuSCZXrxeStQ9A7dtn1LPiwQPDKiBqDP1U/wJcAlFbiLsyc9f/QAHj
9lZYcHHt80b37sT4yjgR3dE1+ucpmoKzn2BTpTHcSchHAaVkE/jMPYwT0bVMz4Ei
1w8nTDlckDCjsf3rqCts+tXCAarSySc62EbvmXRrKxaQJzq7MSV8u3B5NT+HYmHh
eZ4MMYx7svB7qazaIg+UU95/WvR7x6WdfgyYgdNziW50brMlO67vBslD+EDCY3zS
QwGQJSIA5lGOn6jGyiu5nvBHdLDkIEH0dQdW5pXiVx2UCHymYQ4ze5ujQpgTU3jo
LT1ala49i123ZWc0D/gD9hn/89s=
=IXiW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '1f63dadd-ac0d-4535-a64e-934b64a29c41',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAjqA6sTtyRIvrNWNlm7LHZy55uNIM06mkD5pWxj+En+cD
k5LUDbxSVTMP1JyNa6gaHSkhHxo98poko1QTtqaUNSHJl8vx0D+E2E5bfYljbetK
P9l5lrMH5eFitMUHnoYm2EZDTD8qVnFlT4YyALvPzPJ15sJdrKHjVLHq3B4m0Sjq
Y2qf6fJiWsLYamxEyEJzTet63MQpDwp7v1c7AQBTVPweCoVtUOZatba+Z4JptrNL
v6mWpCUo8dueQEqpWumxI9rdjQcci/wZvrgAmR8zXcXYkv4JJilJXJOKyccgnzjd
ksECMZOzGV4LbbqOXfdzzb8e3d9vTnUvYyN1X5j3UPN5ij9XU3wAeFK/yX2/ALfZ
hOANZf/Ui1cIQkVJ51DJa0ITEf7w23m8f3FK9Upj72pux54apJf52s1oPpO0z3nL
d695qj6/CeV51yddpDUYO6EqAo5mn144iSt8dR90m/1+mKBIYf4voSz2uts8rJIm
bkwoZ0E3pfMXuCUFg71LlMsZKGBjHICryH3/JrRePOnx1l9byp44WLWDFpKRALsA
RUzkhLLinh3CQ52kz5P3LUcH6lRB5Gm4tqEnDe9VQ8dWkmxGOPD8NXW/vPuGPrYz
nmKSC8vb/bt1AQLfsNFMnu4PTl6JC1UY2Mb5nxyjZ1Mfxug6Aer1ysIfl71yFn3S
QAGrpTkjolRahihGpoKA+S6iJjq4vn8/fgWi4i0TDxutjsyeuHgLiFP36EDDRQ/J
GAYb4HAyV4kFc7VzbtHYefI=
=ca60
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2584fbf8-8d78-4eb5-ad6c-4870316d9963',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+KRMTn2nI19A7k2g7atZBC//U7s9LATn6ZWSh18XSpo85
UuxMVqoRqSW9/rWb14OSzAri1qDLKrGOvMOWAKgRx3nsw5MBzIp5U5yu4yHiWD9X
F4c+4kRbcM0ha6CnPZFmHGccOyV9IB4psk8l7cmJbjyd1ik+e/QFaKuP1cNkPFkn
d3X4i+poWKvSonRUDqmhUnXOTYsvTAt6vrJyADdTXdH0ZuwQg02dBzuRVE5adF9L
bdAD6IJsUfIWjXABI6UkITsAG01dzCXPiPi9inVt6h6gFWFeHE2/Rs6eXbR6rnBm
IrMUK8aNEdmvm5/AhyCJtLSR9eJIZFO4eIHSXSnfeMK0YnswOgL9yQyv+jszY6cx
MmtWye4VMBo6lAtCZPaR1IlebhHyXDvcILIgX2taiw2+3Afapowq+HbEj4GiUxqd
30lvVuSkFMvmjevy43W/66dHY62q0+G1MILnuMgxCoa7tkhMh+9yvbmGI61e/4nm
NP0huntPzcE41WVZipCpZgGo9OlqZxAIPBNePYnYICyFlVDEAygrPAtCVFtNlT8z
WXDhwbcgCSltFZYdoWphfmyRMezW7zFdy3JbMl5pn8DI7ggiIf7koiNl2kknHugc
2j6KVm1wEwid4g6Q4cY5HMVTZI1RHIUaXlqMoLmnXKlEEArdcsWxDVG9cIIvrhbS
QgEMl8tGqax+p2kzlWS9aIG0/kF5fAvZ1r1R0NY2cc7++LLZ7qOSLoSn4myYs+//
yt136raUQO7RUQwCCTIYcE3+ZA==
=vKNp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '29943db9-2958-495f-a624-b72caf2c2e77',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NAu1HitS/gjuNyDYylhahF6iDDyiQQgVdr5BJXBC0eZ9
+6JO4cInaWQm1H8Sz+XgXmHsGUev663Ia65+ILMY8AWTueRIRwOEHipFx0s2sTT/
lewkSmOjAha859vd6bn1RujikSZ0uvkwu+LFeLiIPGCJPOQXrq0JwjLgoJK58IV1
mYd3h4JWUXIQvIPe9+Du0AyMG3g7K2lgcH1wZ6jmrn/3GaM4wrRQkmSFWiAb9aKf
JfNJo1akLl+ROqNkIZE/ScYsnlLFQ0iBntjnIp92SLSLZmvGjre5HGU/ILZhToSN
/zNvKY6RpQo41FX+RxNBAOhXeQXhZIQF7q0zCJY7mtLB3yDd5wzzWK/Pvd4RTQdV
LN8d2yAOouN2r3Q2aCYpmNxOJIdf54rNX5FS/KUlLKQprLhXdAFmpxZ9ZnVTeB8x
ULQpC10S4OZ5j7uGLI7kNvSsaLbT28JjEJ/O9F6RMDsu202UYHFwN1cfrv2RoK+v
qWNjTbeZqBR+Wj+84m6lw8TLxoIXDbm7H0cyN9PFkC7kC0Uf/HF4njq4EG5XcYI1
McmIRq3HwkXqk4DYjRGp6X+PJzvkRGZYqdeE7vmegqyfcze4W0YJ5S7rKGqX1QG6
UN4BDnnSVclq4gBMhbA00EA7rIy3C1NOI2wQSs94ego8r8CUC+ZMADqfSZ/GBKnS
PgFokhUQeOv8QuoR2R3zPZdg3EQh9+cwxp0CEtJ9OEjdUUVm4IEO96CvDW61DpOX
NqEUCUBCsJr8BrBWa/vX
=aPD1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '2fad06da-3e49-4c9b-ac18-fb977dbb5f2c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+JD2uvF0qFiIoPFcj5Ecp5WIxBF/oITaoKgCGMxNDrNxQ
Rl4c7XTKCC/D/uNkaqxPFQnpH2Reu453nQFsz9Qr0CZH20w22p1GvFeeVotZO6EQ
qTNf+h/AxozEVm6OCzWCyxGHBZtUjBPVJd6KZ0I23eYfBFk9B9flpaOw+HXRCrgg
QQp+KkhiKml7F23fZ2OYC/XuGnA8zR8nhoFXSCzEvNYcLfI1fK4FC8Udg3Ww428S
MQwOXpjmgty0zLSxEyR3erfqResy61Hmhi8GtQaICnm3LYo2vDKHev9L1S0YLEz7
W8YctnbZOJYAgW3cMGroDkv7bvy0MZuzA9412UvCqJkrFsn4A34fhFVNKLChdfu0
aJNTM2tKJxE5yADJ9XTOjQag2OeAtaIcr+Ietgk8ExTUdHwVFyhEfqzFkr+YYWzG
CXl47FIadhINGuXpXq542CnA75qKkJn2Bdx/nZtxBZGVT7q3Xa3qlBlyGE0M7NxO
ySJ+OnabzKhP1PsDD2R1NAQzb6lWvUgCSsGQVDp7HsEKLDIwultnsyhKJrYI3h28
wUDrynC5SKmUHjVUo2GAVcucfpbfuObiM/C4GKN0882G0aUWpozvfxIqw24ovSGP
QaBzB+hCmg4YY1zag6RAQ/zpbIoz6c95DcCNVLNfw8hJQz3901A+4W/lqm8UJ97S
QwEIfjoG/PXUgAhliJ9aIARYUiKWKeZjJxTdO198BRoxJyQQCZbsoXDl89RJcZjH
Ik+VavzGJFhkESR9hgLO6+g0z+I=
=NLxq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3151717c-932e-46da-ae08-3d35e643ca4e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAjr9fTbMDBvYL9GxujbVFcWQ3EEaaci6t0BKDIU3r7mpg
bHb5u/Qq+ysKeDxFwywkLCvYhrUI2fHe5C3SyEGemKWZV6QTpYrMcJnoFMX3U7/J
qLpfX/mK5YyBiFvdOo9kIb0o/wv+jpiUqNX8f56GJWVCgYuZ93h3sxZZJg98WP9K
yNd3XUHxjhYGOgRrXyTI1kvp5cwAs/XPq1Hoybyd7oPC5A1nxQRehn3QHpIH2GIv
Cf80LIBNeqMZsDa3uBWWn2iDI8O7hYzR2YZMLZw/moWlalKyTZeH97PzIrHKmMcC
AB3Y/L/sf64MtvYUt0enBWBPcpUk7DnAP4k86n6KVtL2VK+Zil7GAFqdGlP3PzJ5
QJa/yiSdSngoSDC4yXAfxBT2I3N/OcwQKkiJap7YJSFvm65w2Pm3LWNvLxu9t/W5
pcAIwhlS/D0bDUIy8ldoLAG+ay7pD+G99JnK8nTVtO3UH1Y/yFnk0vJk0Gxe5ypI
ow/lbSMhf/BWvCZmjQYqePQCKMsu/WYAECmfiH4GUxfB3m8c5XGxzkzZvoJh3/2T
BiDb7uPfbu6IZspznbhXO7XS8xJ0SRliOkKUAxZA7Mr9O5caJXQlXFV/44w+K2uY
VbXw+aBjJh9iZzZeUeRgJuZ1TWaXRKWq/x7hMD9Be5bYvZPxVItwbL2nP2eCGDzS
QQHkt2RSeFu6Ek+ZGd9fTO84UYm5Ko/usOlctTvY+IyFz7pbKbgQZT7jnb0o0pxY
Xjw8dxQ0EPLIfxuH7bPFKNAV
=pHJM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '39c8cd2d-2045-4b93-a864-83b1cf5efa1e',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/8C6vhKQ/NkPLPbf8Y7W9zHxJMJ9WkaXJhTLJjxFjWX5nP
YnSTJ1ClF9QHYHYESPbiueXww5qHChWefo8R+M3mQPUsC+9JnRE/IzlBoNAj2O7K
d/nU6Ug31tBlsOeniCIVhNz8iq4uskITmvNjxJey2jRFFdYIo+3bjpAgf73lqSsy
oGiTQxm3de4eNbCn1+ZrpTEnLMS1Jokw/IrawfzopkXv0w0io5PDqn7MgKWZ9S4f
5LqRnG3HvmZvAv26+8wVAZMg60azU73jSErtVPa+lmDTtI7m0aHIjJVlysTD4tEu
hfWExe3RnTyQoEsUgR5qjMMSeGuleZftRE96o4ZUr+EP5awGy+jvzvgSacp2QzNw
zA9NA053UVzE9k+htIqFuLw39AXOG6Kk6aMxMNy4tT4BR6Q+wmXv6OUCnF9V59c3
1tJimost1ZIA1RiJUkTOeazGDP73GPnkfkUGuP26LwAKPEPPzN97iC+LrJbJqbNE
DBcrc3ublTPzzxSOmpjKewtnzWmhdQ3mBUEURGS6VruHUFBcxYEzTahTqceYBuXj
4JTngAQ34FEk8+ZIuPj+ifPj4Fdlj5A31p5GawdDAzvxLuXHcNtia1s1fI2Yk/7p
k8flUDe4JHLXcfKZq40Tx0KjsyDtvPuHNcmjr482O5WOrH7LAQuE5kFOu3UceErS
RAF+BD49L+CCqqUrMsqTbBmWyQcyOoI0eXjMdfIK9SuZ0/rQqotsM8dtJstfAet5
MDkejIFUzzzFwJ53rt8Li+Mdq4q2
=ByH3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '3d403828-3da0-4a49-a1cf-6cef44744e55',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAgXhatPNdAJYLUAg7DVLNs9ofD9y3ZfF5QVbt2mTb8Pks
Ox/vKFEOVpLVf4bf1PttL6A7QKEl7NL1EPgaFTuIWSwbYXBiw7359AUf/G403CYu
y29GLmnr28+FkHGdYru5769HPfV85O1EXmhP1mZ/x5o8J2DNGdMyZOCmrsoXZQ5h
EsfdHEYK31yHNlJN5dEIq5tnYBqpR3qVU5JntYrgV54MiTsTNqGBFYuWuVqHrkGp
1zs5jyWzpLs23hH0Ms5pC2LhW92x4ozJzGdeOBrMZXBK6KDykNYhZWxPxAmQPtld
iv7ZpNeUK9tUhiy4LwXNx5dYd1+Uwhj2wHrPoAbG69JBAYgatI5FNcwPz/IEPXUy
8asPRm0XP2sNoNgWNO0JF7j4o5ro6RPIJhMUlULhMnUdjGV7YSW88MV4ojnwGb2G
u4I=
=urRL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '404e4a57-4803-4460-a5fc-fc8c5b7539a5',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/7B8tDKWdmaI8yuL47LbXUU/7XnDJ7DpDmQD4yVmhrovAG
d1zR+zofQ9RAHvsRtWDQIDTv06xQHPXAK7LOQxiwZB5YO0prR8CIAQgALwibkThT
6nzO9Sdl3nzuFrcdcQpS0P/mnEoxjcFBadSIaqcZwsE01M8Vaf8/s4okVuKGtLT8
lvB7RMDxDLz+WofSdHQmfn58sCJJ1arhOfRa7dBxXytCTFl8G1aOY1kMQQLg4AhX
zUrKjcY8dPJLxpjBRoxz9k/+S7UfRgzLvtv+Ej6YJk2Yxpz3JEmNWwKCcc5WojDB
ng1t5AMo4VRes5Gr+t2HDeaTeS4YPUfa5oTiNfW16OAMeHjnAWlpTcw7bLzlOa9a
CUxkQbBjoYFBd1KmB2ZKlNXJJsj5hbZpoYezg2IMo1lhkfASPOrC3lms/ckVzDW0
8XXtPKKVKujJB6mujdiNhKJPcVMUih9GjO7wPBGUJTWml5fug+M+sW+KZG6EtuJK
Kl2EhpEYVuPUxAjapgH92rMd7JmnKsNvSdOqHKTOwX8fcHAQvqtgKq8v8pXe08eF
BILS91r+JAZvHjeA0jYRSTWSIaSx9QA+2apxF4GUk21rbI1XfCRgWGGCzUHIaXte
ytQJTT69/a4Wc7bRObmNF/tG66zGfwD2z0cz0GNqNPEx9jSgD88BJvwDGCTF+qPS
QAEUzz7EOllSPz/9/peNKcz6ZwZSyQ9pOYBFHrZnRdJXpucJt6soJq0Iqlg8LSyZ
wDw3oDYGOR5xTuyxkp0u3so=
=3baC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '410b7f3c-1600-4678-a833-5c3736bc8942',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//cgM9DFOz/A/7QfPt+YaN8bEKYIQf/+3ZtRbnvV48BQxm
gXE8uHQ+JCJCAf+jEy45PLUsNCMH5ry/8ooP2dIDSw3HWFsGqgqWk+CrJsCkM4sU
1b5Yxc2wE2PdUwusL/A4/TWIpBjzprEWQvmJ+jkm2jSU6QT31tqdVamu4Rd1t3Gv
wMT3YAqZn4KxUPDxjcrWpxlIadUxBpnS7pvPO8tsWyn64nBLXlPrsLVRqjAAhjL7
6DzgWZyupP6Dt5W7SRzR//fo1I4gciuBXjSNQQGQeAo0QPt5fNbGd3NgYyO/dQlz
hkQCu7pZEh647PDEc735JuELn54HEsfcKoVYuusoxw3drb/HyEvusbGP9hfWWvL8
nfLgOw7BDi0Nb2tsVjSTuCQ4aroH95L0D3JcdLOBXgHyXOGM+CMtpzmyMQieQvIg
RHrkKr1KCjoVrOE/UDrptnjGlPF9pYsq3WUfYSRVvcsn6SYeLrfUn15KJynRC7y/
QHI4Qit4/SznvI8rM5IVhSfEA74VIkTHEpA0k1KZ4tUPm12yqsB1q+o/bpe8wWLq
kUuWAkAn/30nC09r8kFf7fsqQyVJIpLeIVkEodGwEAarAEmnCK715L8K+hlzfVTV
LUzvac68S1iUR5OLT4US0i59ewnqTDG4P4e8jkUN9ju/a4KvESiBR2Nhsq0RdpHS
QQGaSDvGUt7TQY54NXkI+Ke1nosyKdIg6SPL5kuTUAB+qgMZzKBSDqOHz/z+/yJC
XOEJNpzahIR2budIWsL+A+8g
=CFvw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '456c79f4-2925-4d83-af4e-a28da6974396',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAoyCK63pjxyf+WtgZVOpi3wSuRyPwRxuSxZa04Locma0a
8WjayCiOw95F5T/5r02yn0ks95suxEHHVc8alcYe0B2vGBPNkcALDY2RJVKr0RWK
eaGLGdx9pYB65so8uTQu34EQvbGEwCiLl5wOgABJOwWrcIcVx8UONXp6KJ/sTCYv
FV1BtTORawke+dbS+pF376e3OXoOqNd1Ik/SxZa7SvrXhCU4g6SZvdlYQLxiFHTC
QbFJHb/xc801W20++6howv21FC+YLqyPr1Fd5LJ9mlurOerPyK7Yej3j5mbeAFfy
Wwt0ef57D9UPK8smT+jrPxADCnYNG3kQ9+gXVuhD+TdhAy0NuAGh7KJBW9x1DLfF
/0uM6hTue64qcGHYVSM3b0dvPIQcM+XUDutRJ1VT9izhvuSnsDqxcAIjTjDSpI6o
9PSf1JaIsRaC8Lfgz23B3vAabBZj7mN9odThAyI9CgnAOtKlTAHd7tF6od5iKQhg
JPvLu9JY4R7QpeLz3p+uXwg/20Sl143/3s1LKt2MIgc/O1jvWHYsKB/5TVm59Oud
XnC8Y147pvxAr8iqz6Sm2Wj4soWZ07vM5f2aPMgaFFCmkH8X9yYAAz94qDmffg/z
aSu2ligysnoeSFQ80psL0bb8R5uRpdmMlJ22X7EYwEc2tcoN/oOJvhC298SX/cLS
PgFM4ekM0sFpIy+JJW8M19a0VEuhPqJ6mcwFIfDeihzIYRIYzgCcFWtrFG2o0xdJ
z4G+iE3XFvB5VPGC6Qx/
=xKT3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '4611830e-8ab5-4b6a-ab69-525e508cb244',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//X01QDClk/Y9SD63rGWYTikHm1uMMWbcvs5wrNgecZXmA
XF9iPxJyMSFI1vaWftR4KaNOxLu+dQniXkZUSwrDa7k/BMS24aHzPlRAx31FRBOK
6EgMT9CWf122lO16eOanKP9pNBJgnYOvmkBduApmfoXAzWCzBJpuTPQpjhEGWsFc
a6hjPtYWkGHydBNMkg/NzK9k7DfTLT4/+Jr+E6s3dwgCc1h3xuS96yZj5GrLKt3d
uB8isScKLdrBV84TUx9rPWDx7dSjqjAErM+cveKr1L+fDSviEY5h96aW3vTDMoNv
OHOamKtUTUnMuWWG/7SlvOX3u/DDjOuDQrDulG7jDA1F7wvcq6h1KARrEW1xspVt
a6gKUPs096WvPjX3rhPN+BXKRYrAHMrTr+ZDtqonNiz4LnLHDY07sTLqulgLD8RI
+JD5mPwHrrUrJ66OMq9pEHxfDRu8V2vFNcK42n8HBd7vJyTXWRCpxvzV9QDZW+ex
fFB0TkJkMfBQlkVnR20ZRrzp/rMrbTSxAjD4EVj/IH2CsE+OKCz4oenMDbdW5MnJ
Td3xSw2yk0voW2arLQ7oRspWkqq1tvc0CzmMjQSOwZKEl0j/LOzqZ1EG5hbPxJbh
W7bDJL+UUuy40l07u3d5bgWQBeKsP7VpSeP3hdFeqNjxFO1VMKgVc2Fzbx+OwFzS
QwG2N++bUXhHekQG8WQaiYpFrHdf8Sb9rqupByYMLec+fTdA5xq6KSILBO0nbN2i
qaXFxpyXvmMbmg0OE5h8PUUvIZE=
=plPX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '47d0f885-4fb1-4bed-a7e8-9b62df42eae1',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAsm6KqT7xNNBcG/fMoCP5e6XgpEEgTsvKkPLdvZrdpe+v
ePULU6ha7CUXZXb+BaqkEGa2h/Nc2FE7ip4Am4Isnm0NaewcW5TAfVd6y+0x+yJU
wvVXHhyRYgDFEK/lF/8RU9Y4d2mK+V8TDXSnY5RA/xm79/9lFoYnQjc4ZX4pVgo6
Ad1zoedBLTjRA619BOaqLKTbAjAw2lxahfxPqglBZUlbyp7u8moankDl7vLMkw0D
bghi9di5jlSKhMUkspITwTAAcGSRW+QJoRetMlA9Spw2wEk8C9vsz1MMqQQMX32+
tX9YiD0l+rPEpY1QZGVZP6m4RehZZKbTJmCJhcuTt5g42N41Fq6Mhint2fdnglxz
uOS5RTk3dh2AWayfVjSItSF3Dv126cwgghA8evmyupTTGxoGM67OQzyjP7gCWqz6
R5hSCw0tSJYAQrwd+uKjX0hSyT3eDdE7UzAF76iIKqgOSN1/7ju7uHFGKarJP9Yw
KeffZEYgaxecEK5TaStngu+MWPlDD912secoAkOs/oxTP/nw0yQMdKf5uwwp/un3
vd0Ip7VHXL6/lYRwrsMZksRb2xEqwjJTyvPtpIwd8B5mOEKnJseeFyFC1TT/ftsG
AO2GJ3LOJd/YB1mO4pWgd/Zix4GVOhn6vW9VFocHNuDcvLQJ2MOwsyJIxkVpyKbS
QwEJFs3bh4zXyv4fkvqksPtK7/OO1A0+yKHBFzlk4Y8urc1wg0QivrUr8/RlEdN5
2wWXXODosFTKKtyPU6Cz2pljrqw=
=7qeY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '49fab298-bfd5-4022-a7af-563925e9484e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//S1j6ruPVoj8ihcMT2KHMcItvKnSOmMHZiZmnsFUVbEHE
8bSNagCC+XtCpPI78bzpURDYu5mPBXBqaSsHgPcGVCKQ/Ix3T9DsBZi3Icoi4JBe
iFpycmgoa685z45h/ajw3N8yrsU1zt3BCTw7c5Sy6sowlsMExDgs7IT1/gBPFFJ7
4KIycVOgKSnt8rfICz/FdDoUVZ3ByCO+c/2FII3SNYrtjPazxc5F2+flkDwFri+p
+pNw8f3oypGB5nTcuytlcSdEP9Tv+/50uFZXOXZm9uwSv629B3IssEmQEDmntYRT
+HIYS/FtlkgLuDObY2l74o2wHHZaDxHCJuAqukpoem6ss0Ovp77HblMla8lIX+6B
bbEHQazqayX0BrERxfLc8yETA671YhAoOo1EsdFqF291qe54CcSC4M71s/fnzDAd
SJFYKMAG9vOgqVgxxvkCXgip+eBcOTP5T9wKpVAPM00wANsmLerCxTWiTZfynfNC
ZkIJmJiU3/6aFFOifVN/GnEuiQuAmsio4x1eTMSPXhSy9FeRQseAFqgqOiTdwiXc
6oGP8P/kQWH2egigBqKPBDOD4cXH/Hb0pE94UnIdS57qf5C2nXb7X/pmqauWxS5R
z7cyHpjFKUa37OircBPuRu7EVmyMevVjrqCvNWLMOqiBqlQI1QBq8SHm6BL0vG7S
QwFN+eR8+0DcACtKD66oZ2HRYqggmSmFh4Yad2MKwyx+k6LIBY2P1dBM1xH41IcN
RJdodDRTQmtC6fWDt+uPBW90sx0=
=RKlc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '58f35529-36db-4661-a8ba-aa9683d8aac0',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+OU/swfr4JdIXae3V61V2AJ912N3MjtD9OjKhs9uYFBDF
QxcFeS41r9XMMOIkPi3PjI28/GZxpm8knb0HRYPy9Z8dPHIAbMNIRqXH5iWensMI
KpfZKsFABWdV0XvpCIDCIP0zHgr6i+Ds/to3rAtb10wstQ8I6ph/Zc4B3sefMrkf
Fu1adZsu8lGNkYBGX9+Zy6DMg8iPMdCT6iukJxvujg9uq2napxwfYiPc+wnaCENo
QCANrK1F9kJVBb7MsW3wzlSfNWWpCjIYTgVkMRzDF70AupF8rOARv9b+3Pn6SqdQ
V9bk8SwxaEUNX+7NbaSsML9SFiphuCNfedEc5PvsHFCOBH3NPDgEu2Y5lPjwNOya
LoPDY5GwmAlhI8VfmPydYQ/mTg2R4eeJo44NiQy0CcEUSVsXMO3Z/B1/Idw8PKb2
6zpCfMYVloJEO1hZby/Yv3vNmmwLjTk3JXAz/QWPFbmngGuz/Btb/YXmoJIwIkbt
6z0s9gq1c0Xt8fF31yC4mvnzFR/HbR0i8F6YijZWTfUYyCIPEhHhXMRgvJkDEdJm
aeIP/RMTNA3eerBLiHON0wok5HR+n2iks9FFzt5+u9mhdBoU1E4gyGtmCq0vP1cS
Fu9/Y886CMDDnkpROdg+pdyj1siIbNF5hUOtwdeiVuJrtdo85BdHZs0s8VOiMffS
QAGmw5WGM7clZWHyWjOlYdgOYAaWBDhZiHg8i3S9JLBy982uz69uXH5wwhV3uxBG
s/snSareiCOFyJ0Cdi3+TCw=
=+SVk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '64f5f469-9ff9-45d9-a955-5f44ffe33408',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAlxalsnp/GcpeEsPiqwIicWJcDSWih/HlzaFV2G8EZlur
hcOu6XC+qEU63XdQhS0GELrmhS+HzN6qwCkNIu+1a8zrCqBrnlN85zvW12v0mM8S
k7aqBTZn87Q7JVEitMMMgdcgL6r35Qu2LtSrokxZZITHufGUv5Y34gCZNNgmUH8n
sJGhZA3ItGioqdh/qpZJHuRVgQWr5IoAd09V10+RlMWdEhnx1Mpfp7cGzvFNbD0G
v99SMMt6ZMSjMxO1OipnklIVKTGO2SQdyPxnY1R2bYdjr1wPDb/x5tvKKqDfQgfy
L/iWKfpEFZD7/D5Abw5wTVAWpzHra0AN3bT9/Cv+MSqo9GyNv6k1CNDRWLf5mX8u
XTPCLCFkGCl1p6XLFp6nHMT8j+qqpY+p0XYC+EJoS5W6zT0NPsQj7U9THcmjrTip
hVntAS0UY+/b4oOP+eU+wp1Y/Z7UQpso/LHA40VZ3fKbvw5ts113sl+PURqjm7l9
pCuZjiq8akknD3CGlNCF3KtUloXwfQXkVcMgLj6Lyg5kfTYgiO140YY7jtEGNCUR
H90pNf26fvNMwVSihvL1X72I7rt4/PnnsJWG0PfdNUFliOz73gUyLzi0ma5B4VoT
RmJpRxollCt2XCjC2cvpVEiQIBLCMQxpMkGsT33YNupN1iAalLZbnf3++4E0QGDS
QQEafn9AbjU2Dn8t0gGWwiAkXBapxrERFDUbPNJvkdFTmZY8E4s1sejxy9PYjDoj
5nIO0+w3k95y+fAzb09Glxv/
=94mQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '66e3b9f3-1fcb-4823-a41f-b94d0de03159',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9FYv28PqkLuoxSikahkyYSd52Mkg0K4LeRXGNyCPxj9y/
Hl7KVyH9exA379GfRvd9V6aB32rMUv2AAnRQHa4YLvXlhrkfHG0fwCjFBish+R1q
cJu8bIbj9IlWSat9urZIlS3EDNRO9aVjFutMB3/6q6f+NVIqrLE5JJYspbABemnw
sVdIbH5pHnGi35JwCjQA8MWdO8/5yP2S0Yaf+dklTp6GdEOdQpbsaROoeVmTXShG
YfeDP+qAk0c8OrbKfaKOxLaUDzvKpauiErRHXnuu1nlATu1R7fB70abBzMHFgvLs
9sEXkWQjpw80zzX3ohc43r3w1b+/k6yAXsFvyXNg1eV914FffohwpAOhtEEW7ovz
owbmxDDrRzkICGo3jB4dTJYB1Ak8Q005Uh6wGOIZ9WPGk5X7dDS67q1sryXrB38W
kB8OI6SLMklJMArX73oud6WipRvuzWrcKE62qqw0BRW/t086BjY8kM2WHSf7FZdn
zBrO+wm3N2+0TRGHwhmnUWhbqA/zYLTgesjH3kejnePsffbZ7QNCd+49nvtd8QiQ
iT+9hS7izPTqBxtJhrbcCT9ByMGUVSXbrcSxnhup8A0+/OUK8qIk/dX9dNAD14Dt
kZyJGoP4gX+NqaAcWC5BNs7uMG7GHCMiDvQPVI8Hp6x8qfjkB3e707K5EcY0NMvS
QgGTbWqSNf0OMFemwZIZ8X4DOTsh62hMW61ViH2tENdevVo18pz/p2+5ZWatGBfO
Y2jKApfamLeQ3CFdWrBxsl9b1A==
=RFHD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '771d77a5-40e5-40fb-a7cb-d619ae696145',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//T7a4+q/8p3PpvTqwkFsoZZv9HtRTiotxRb5s8ad4zWiW
IvmvafkFGhCIDLM8JJF7SSVIk2WBJP1qw8S++oQFcAIL8b0RcQdRv/CZyNZZ9wGG
IKF0TxmNrKHHrHViUZx7jNDmVg4BFyk3Inj6EsTpK0z0JNuHguJVYE1xze9pDBdZ
Kz3/4ty6JCXraLZ9eQqG5TLGROd8OIC4QGmU3MQ3dTrgfx7Cpnwd6r6OqSr277hB
8iewwqoj7/D3htDpmPkCDyVmJ/tSHs6lYydX3rZDXZpSU3MZs4YWuMrAW+ppPFvG
qrSg0bwbop8vL9u6kqX9d5LCxSTWfqtEq5zgv5zzYgt4v9dW1M2WpBHwACMKz+EQ
2oMgto7edGlmpgiRDHdZhLuqvLYL8sBFAzUJqr1yM4FN3a1xTR7C6ZmujqTs7rZf
q06UvYXZdxxR7Pw39mtrQopiNK9MujuqNPAsWcdVIc/9Y3OG/2PfhhbC58KJUetZ
HDzJDeFQUQObQ+LqEiwMwr8xFhdUdK55Mil6qFgO7pxxnX/CYQ9M1RIbhdB6XrQl
I8eV5KXv2F4vdJv6o5W1JwYBss+9NIUku3RQdSuaJugomur+IDoWQvfGgH4F6KN7
JAd+eYCaHSOaXw+sZVzWJxxrWLw3lN9V9DSYxHduygcgx+/2b/6vrzgzH8TcjfPS
QQH/YvXOKrR36uT8h6RsU/OvpMfwcChgUq+BMZCFKORRWWwuMrhYJ2/C8DmQBqsL
1m8onwpOa4V/7GbsKbYw+MM9
=G4BM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '84ef457d-c997-42ae-a2bb-53be8261f81b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAnHsR/KtC/YSgX2ZxDN8S01FdgCWq6gmAs0C//cLtyzWA
ncKLH7OQQFrd4zRFu8wfGN4sJgoKKtRkIRyBaBDGdCLwMc+4RD8tj21Xhiea/+aN
F0EVYYFIa4qptFBubG9aH3QHst8eO8YblNCd2Lm5252KS42QlOk0zOSYTS8ZpX/t
bBlUoYgi+IpUMiR0YZlvpOOQfGfKPT7CxKtcxP/5uWrrGhRsCwgNgXioc7qCmDTM
qdpsAS/HQomLZCwQr7RQVpsQbH6a1vGWGmmLMj5dltd5a8551EqxC0y4h64tdFoq
rgfqAnrpe4qXsmMFv7RxtTQn78uPfY1drg8UVphS+evG7U7AhmNEfxPKFhSiol4W
N38ZD9JHb7YQNpTIAVYe9OecGScLNXKnNHswTg6Bn9b1NoR/OnP4x/dS5vz88RUJ
CtDnZZNAnnc3fyajKQolwvWKs8pZqap2VH9CXSSDePyTHZCDxPLIGbTu2GN+H7li
0CvxJdCFgUDVv7CbM66NrlShINHGBIFBBIJSTFEQ0SqJPMh3M1rz2NsEvT/6rkz1
wO13dP9/L2dSSlasiC9XB1/YdWQiBVGBt/wAQFlqwqIqSvxVvL+J0lPA/aaCeM2Y
i3Wn4kbSEwFTqqFj1MqK7PfgWSyOESHKR/E+OuYmlDx4LijjM/fbsVeLM+Y3fkDS
QQGxpKhBLFurlKgsiGgXFFhqMCpe1potqlN0caTmm5fkYBZcDjatYppcLrW0H/x8
u4aAM+kNqWu3yYUdCOjaQ1pc
=tYdk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '86fda25d-ced9-4657-a414-3949ec47ccc7',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//QDWitu14xf/trbjJJk6KlQEfzrNRo79rP6oOgVq2NtWt
ck6BYOFTbGij6pLviq5xgQftl5plf506lRxQLnPxulMFgHiMX7two50awwCQX4V/
D3b23PQ9oQ1adzSvoCeLUb/QqwbZIHm1pD2eP3nQcs6X1/EjxxbJv1r/jzhN3SWS
PSixFT/1miN1tEG7hZdBfBPt2lmcBqTRKKlPfVppEP+erhOAAA6ulHcVvcA9ik+H
aatuXwe97/K0ZKLoBoLLiXVwnajuhKJQq7QckYEYWMxNbhB1JtzrVmV/Ta4XYe5L
lNKHUiYn7TuWEut+k7pFRsrUMi/JNovnhXOmc8VEvAM3Iynk/bxxRPbZcwoQm8GS
Cva+g+dpJFu1/wtWkr38tbA3Bs3nO8TzP8BDksAQpnippkdiFuvgkfv3pfwp3qJi
URHnjSC75eNY3+c4zSI5QXFz2daNjww9UWxn8cJLC1bcwqquL6HuBHVB6/sBU/ps
cnOcX/KBvefJ4GDK8IrYa6II4rqilUmQ+4sN7WXzNZkkpr3QJgta3H6yLYu1Wdfg
41sOSnB1xRDvEDTFqZbm6j5niV6OCY4nicgfgi1Zp167g9Pndk04MT3EmgWOjFrf
LnpGI/7Ypi30uVLOHhcnj5xKendD2TQ9xKZZSSt3Z8Uyep2L7CG2oVDXKMtagnHS
QQGSLBtfsLJON0lNZCTqZ/MBKqf6GBkwFmwxB8G4RQklsAyESjlj7WjmsgN8Xwrz
3wnyrYVDJrBuKycFOwPxTr3u
=sWMw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8a186075-c1fe-4621-affe-976bfa84151f',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//fL5WaORsZnPS5/SPFJPyzFfcGc8bj3I5JiSX0RLoTcTU
cmvBG7JxqFPECTP2gN9O8F8VbYS9orL0laTl9S+M/6HqQ9rK4Au9Tj1Lh0rfjyf/
HpzsIQBcDDHl76zayptMEQVJbmPq9hbwO9sf4QdBuR+BI+3/TBD9A11QBzcHLq1L
FAZk+Aw3WLHBpjwspozSGvJIAyANuG2ePlu8q63ItK+wn5nrpejkrlSjs2RSLit8
Gkv+1cmxmAubT4ve1PRC+ZHFukaLbbepKnYoPBQEPHCMXNWdh1qHoGfTnmRBYn2N
7GAU8A4632NbBvPEO4bwEeEm98IUCrjl768BeCLFUkl5iJXLzQAoasKlJHLFR+UN
2NRr+sYRu3eeNcVScAaDUCt2IpwsbLiDLSbu4x2rzuTahk5yNvhG1DZWXYvc8Idq
cd+sLU7mq8mWbDPSwm4mKbKvWMNPU/c6px+5zc9t9wsBINEDdOQlqoheRwyx1xNY
c6OYma24P6AOukSVrmL+4RmOl0MqUzB+F3iKPnzA5i6AnBRAYHnKGiqMto+ZtnTy
aa3Z8Bj2PjOczP7z/jmhM/idjP1YN96zXanVMRtq/AsUaysO9uvLDBP/4GT6qiRL
ge0pP9tRHx6ALgUndQylaM0xYUmSxshVyGB9vAS2pMeHbfvsSL8Xv/udW1F+kU/S
QQFFzPs86Uv37HyOl5/oYn3BMvS1tAe+zfNmO1vhn7YPbWiSn2G9ybeXHwKJ4mgz
6qkvv/U9u9oVAEPYr7DCNU86
=SQFJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '8c5012aa-3cc1-4c3a-afcb-5a4ea2c16801',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAtA5CuhAWf8XSGSPWsO14CmtX356z3yAJa1H3Rfe7O7UB
26COOQkG2KPDmDv2Zeg2Fc11RvWLFoEfzlX4FRVJ5qCiAz2w+IFFEKd7AvhnhrH4
1IoyzwdK6MIdcLWkMJIJ9wegrOuSd/Xa4U0Em9G8+eK6koqzoEkKCoMmdY+Q2qIF
koC/8qri7ZLHQwlkmea/4m1QxLaDvmT6wo74y7tumSI4KRlIX5Gi5WJAqQpTkdhx
jjmM/ur6GGVau2Yd36yNldXiPJoazAy8JA15QqTCW3L9dmCQm/RbRYPHePb+0SRa
XW/ezum/8D1k9JCmT32hhf32bkljWo5PbDgtbhTGn0tJOJFrUERCpDvsm7KuLASy
vyAIxR6jI5t5pRAFLTvP3hSmHwGkalqHzWevDTsTOFHn+JCHS8HLIHmp+4QkOzLg
Qzf+z6ygs0e/LePKK2YtQy96wIw0B/WGGt1yJQJPzwty8aKxSZNLt+eT291qvvIO
pNhpxyaV3vy+YE55wKOwKhUHte+MZzSKTR5jRkXH7APPPhU6oWJ4P/i6nLPt4PQ4
kEwKdFPGdi+iPV6Br7eE11q3p2dYaqKsliKQ7KTwyT99OpsX/7UnzL283nBTamVw
Q3Cn+AdwHB8qqf3yusdsc+KE4KJ6XvSF3uUQvu33iw2Bs8g4Bk0T+EfSx8Y5JYzS
QAGqgeGFLm+YWxkTK/rDt9ULaWqRZjmM49aNJjcFvgVFIY5AN4D/cB29XIkVRUZ8
C/3r4S3DOmddB4B+Gi1L+1w=
=ZXSY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '91639169-0a3b-4be4-af5e-e78336e4264e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/7Bcse0EtGGka40wcd2kq2BeuWGHHRarQBDAmqI59AOQr+
C1rG1oOgTUhZSGAJut9kKylWD3uGiU7CTpiJAItgq2nLxFhF0uTxJ1lT0ZXCtzhb
jvmsGMns7v1s9W5BIL5QyUDTimmpS/fKOzD6CqSCyP+17tlfgmGdMScw2Yp79PS+
94MxMspW17bEPGt4AeiyvVYA7ADE5OEUbx6s0Max++d65l//s/2854suoNrxqQdt
0tIHArhaQQKObRE6bvmvB0thX0cu7wkhvFzltWIunZu5w93aI7idF4hNf91X8uwQ
uH37VvHrEspj/JlzRAQcqCwFDtGJWUh2H/NNQbHIsKwDHSUZarnmcD/y2FfT1whK
1PRwCwfbbKNGPHVttihmd7Gkk+IopoZyG3I9BidjV2M2+U7K4sZ2A40FvcVMRsE6
SyfQuadoHakBrsQenCcLe38zJJ0aqz+J6dw0oV9T5TPnmzioyTwQ7+0cytwUHJd/
xE6IHHeNebxOyya7DKckXw9tnc6J+qKhP0GxbiNOuZ6nD6xqsJdhrgGfLbv7occH
9x4DPMfGQOnnU0s/sTAPz0UZg5bhNUmEJwLMaiFWU8ykrDMp5Z7e8A/fc0F7fjFE
9zPQPqU25a1m7NrDGz08TuOX0aoEOCjmdP4dWs8McnyZvZBcaOFgeL6h19+kgV3S
QQHqLkvECKMnf2bFQowl4SDvo/hsNDzgEOFG8w/lSbCHU1MgbJu1HU6LguB+zgzB
3EpqNHEf0vufxmHQU6DidigE
=VYVX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '92b3539f-2c16-4c24-a563-9218bc0e0d08',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/UQNIWUPpvqasamglcpGWYcpOKHezDWhlHnFqbi0i59la
CgQGpWwuSa4vPKaDmAHDQUrGdh/Qz1Wqx3Zux/YQuJCMgm+dgCvOUE979cVhTfN1
zy7R5reMhvgVQytZhD+GSbwvkcjpNC83ALij0u08US5zArqCuk+oBSVfsR8vDpoz
9DOpJQtYtg4QOvc8YXPIGi1oUL7nD1/zIVwBf3PiQ3srCNfCd0GL3DAZZniqH/O3
NaMoj0HKt0oPnZ9xat5tMRkjhhjfT4x80bkdLFLh0vewymXfs0bXLWroDCXKZ9ip
foiZqxhkv1Wr/N3R8CROICI+8SFOxzLojjXCLr6h8NI+AS53KMwljaiiuAwEMdaD
9jklb3aeaQMuOn2uDU4tOhA0GlYSOGa0l/jNDaLOusH2//YP8xyD6X522EoTwvs=
=Uz2C
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96531300-27f9-48a4-a9be-becd6bf7003f',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+MnewEK8snYALuwaMlMQBCzQnmdNgWXTYQKf4K7+g/M+U
AFHgB8n/NHhURpHEw0Fww0JtIghrGznKLD5qY2x0TwWKvFp9QMdIGd/RTATZ7C/7
QbWgadQb8STn6iZktQiB6ruyuAzxKEjfW6liT4oFOSmk1EBAFr02d/TMC5YiIYA1
0Rsk6ZoyJCL9lHAZVEnmibCAQNnzIbp5zTC1kkI8evV1tfsFR0BtVxp9DE1nJsWS
/8anYW9Eu+fbkAidYxW7RF8mIzX+TcoHPIPqNTghOMQbmI6fqIl1MdfRK6a6EpAr
6rsZNCh3QNFI3Ps5PpenLZGUcdwFU5AJHiOqlShtVcpPiifvEWSCZkNDWvGD89gk
fc6Pcw+PqAzVxx4IZ4LbKOveloqt9ibhlOrktaZIiPJIDXtkB7yZBsodIaDWeEak
M26qioYfDYnqZdXPBElk0Hf6Ttg+soJbpO+50R5qOZgPAWi8/rkzRZup3Tcq2ter
nL/Q1jjbp1ISP4NIHyYrMs9fRP6NUgrxhKbsSzTJbp9/AtQX39u7nVtl3TCsG/up
SpqAQ76pO0D30/Fz+H04qPOzTBzYritUQeMEw8QG2sj68hlr6A88eiXszfGI9+Pt
srPch8vqtvsyni2jpHkSjQWPuXc3TiG7+lOeasfu/0l/Q2CRCV4CRVw7jrQack7S
QQHHgTCM1qNMwaxf+08XYAUV5v0GiQCvtXqJ18mWkQOb168qgfUdO+fPfy8sinen
u1W7yJaJwx5vWDQIBXxY1aqo
=+DvA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96c2165f-a0f1-45ca-a58e-952ad01bcd08',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAwzyjhRjUmIMWTxP2fm0u74WCsIfUp9zESxrAlnieS5sh
jdpdGye+F3ayudP+wxTW2BFLqvgN2JZTT1oph8dhVSO0q+MVm9k4KWkvfU9cLYv3
V45E9oIuXERKzJEt7UsrO8nLQDDBRWZAmmznXWTeknigUHqjWiybQOszx0x+pshF
XNshgBfdkM4V5GTMFd+2Tro5Pi9nvzGA45ehoiIecbT3YjH3kVzUDlTLeaiF8bHH
shxWY8QHF7bZyWiTwaLUe1y3nw0KJlIPORKHzAaKwASMj5u/5NCM6Y9KeOjJmn3t
yoq9tC2f8jqLruEzO+1q8z45nmCz/SG3jtvdGg6ReMbXv56W61h9Du6NDODQ1ogH
z6TRykx8G/mJrdULb49zcR12w7KeYUWvqPm4g9frWqi3GHaeFXIaVY4NwmuFyD5N
THBwviGnP48bTl/j4Hi9OgNm82eBlqt8n8HMwkkfiiu0m2pLXzZw/tceBakbvHKc
M36016tGmmIliwo6z6yfTDq1Kqf6SVEKRylWCrsrCRfuCA5BZPM2yNAYr5YBLyj8
xlUuIAIkvBq8c/tTk1PXpOr6v4QFfLUkUmwWocTK4JvwZMU0c9809tR0I0yTa142
ii3wATLvHrH6nl1gMYZ4w+J7WAGvRgiStmrilq4Ti8pRleTe6zTW0j53bBYxK5HS
QwHZVbM/xcH4a1Kj2lxZWQs8eesIPDKEaMR1PosN9WEJg82sWkLcPGKiQ96avzB+
nw/jml5MR0djoZLWU/OigE/S5a8=
=pDbl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => '96d7494d-7d0e-4dc7-adc5-8ef968045a07',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAkNMIYscNdr4k4qsijGZndwEynFfkcnyZaFv/BVSgNqIy
V4HHXmvLzE6IqzFN/z5b3eVQaYdvIpGFDLrVvRG5XbwEoV7JT/T7tU1twXMrMHep
VgCNJX52NgbONRzaC9CsfeJofOEeUlZPZuX2yWwwZ8T8xgD1n4JHoWBscGmufPz0
a9Lgfc+w8Iuc0h7oHvV9ru+7Ws3FmLTtuOKC0Cp3XjIIqcqL4i5mI1ZbHghTiHxR
Nztj5FzZ1ykIjKCGhiss9R9+Uw6EnFncgYozlrMBSbZORL6Vv9pDJB7EnVOJ6agl
S9Dm5HSvMdpi8m8oe0zjbbVykeC7hA45U/T5aEEnF0sRi7e6wtoYHS75lYD30a0C
2ZlwW/xN195gB4tLfoOjEXKdHZFBqZDRVxfJQTxIB5DaExuA1BwPW3b92ahNlzTS
I0EHNhITphak3IyajpHHZOT0ALOkxWCGu8QdVJx9KalLU7IYgr9W/s0RhActg+Wj
vTj6ssHaObmoa5cDuaW3G4sckxP0IHtvuex+S9EfA6dMvQ+Oe8IdhANA27ALVo37
PHWGDuvoAcpIPBRHxZW4CVY3lng2q8dPaPLL72f1T4CW8IGoY4Q1VqgPVTsHE1Bc
M2lsVvAHDIeHEeY1ZWn2vBU6oQUrawIQpLHwHIado5f9yjwOZvknDt3GhyfPeSzS
QQGWXaQWjlB81d1rVdHCecU2vqLIJLPm+cI+NjHHO1a/slb9E0DGodZFBt3tFnvf
Ot1r8WtREpdAgtSRUTtMhezk
=7EDf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a0a81c47-84c9-4ab5-a2de-69f897b48955',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAzY/n2PYKWGqs6eX5TeepZ2YvExwRMVluG4RDj9E3m8UV
Xgnz5Amoi+FUPw7hYWFcDQc4MD1X4PxcbBEry41bAewxKacORY/aooRM6ULYn+LJ
s2ltg+dE8VT9a4qgm0S3eKbLsapomAsUl2zjMPqPHk9v8LeQkgJ3q0Gbrr4zKV0Q
lZguFv2MbyKbOJhLA4jt1FKYZKKp4mScaij1aKvq27InZATfWTUTJTyRqHvRyvA2
ESgTTPlr4x0GVBEyb/fI07jQvxY4nOYL6pRIeS8gcDHUtv/tKwpBSLtIszm/c44b
tjotl0XCUYkNgx0s91u7KCZWI3HOmQmM/oTksJv8RJE64+oZ/RsECyuvFz6YBiCc
JTMDX5Cd6fE565Cg5cr0z9g4/38QFg+7UNOuklsZoGopkn8cUFPno7XVlRfJwcxO
aeON490gXLL7yH/Gwh+VBybEHZB1A5c5fp9AGWHqTbrJ2PX8hhE+V8lZM3xqpRvV
nsrUKo4Gj6S9ByZ+WIuYXSe7ekehUcFVDwAtfVKtBEIYYkyLiiDAGh2osFvCn1I2
LgX5qEywHwp/qWzHACGvOAsn+eHloafsy8GaPRUY04yIz2oBvca/ETqmuJnjqHDu
rVQdbBHZSz7E/8KUGuDjfliEWxJ/XcsGEQF6vlW8ttE+BEgrkuqDzfcLiNoJOJnS
QQFHYTmP6Zp981DpBlhhrR9MvjlYZDbCBqTuiAA/Yek3pXxYetyOL8QehiZzT/XS
ruvHNMKPh9VGF4zrEZ318GHu
=wVdJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'a93a2a55-aa84-4d98-a4a7-53b7c221274a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//R98zN58Whb57aehff9vkNUhxFw5f/v/nvEQLgbcQZXHE
LKZAGp8DfDI89omDebvYULxenbLHKWUh4MMCFmp/7PuSFoRBjoHGp7CrKQM0XfyC
tJ+85gKrs2u6rH2O31rTe5NqdHKG5ek025oe7EM0AnF9cKjik3GlFLMRTPXJ3ejU
B3AGuzhZT70V7uxvMzvOWK1oalHQAK6eK/qUh7M2wb6BuPlwc/tMvHXHfIzLsNIO
A2lmrQL7Ft9atbZ4T5ANo8H8A1AqCuis222l7EHxzvW+UjHvzsZXVPhrReU3hn8m
/R0Yu+gNXuXPaswxNMb57oJhkD0BV6lzfFrCZPfzBrlVX1rJxzmBLlEIjNWOpwnM
T2+rC18ss7p7YyfaNptcvfxM4kt+ZnDFhWeYMtUbnld/diPKHa47dsoVYRI2JjI0
GyK7wdRygfiySfArK8Ko2lqno8sX0HR4eZSjlAWyuDopxeSXOdtxb37LQtMrw4H5
9E5j12L5SbpXdVeHbFklKZtnC//EaQPGfA5ngLYtLMMF5qent2pq3v4/JU5iObm4
OFzlaPJoi5JwWhyn/mpDX2yUICtOrv4921T0u0klCA+Gq3YoejMzyW5Db8is8RWa
qEiH48PVkJ9j5mPQxr/Uqc9BFOAY/xzAvHnctG3dIcn5Q/Uzn3BhyemXPYaxpU7S
QQE8O/j959W7UBGjyTbB7SQ1KGngq3PeZ/5KC0ieDXf5NqqATpxn1QuDMD41w5ZL
0ZOHRuwHPT4nIezyXe08Rr3s
=KTIH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'acf07138-1cc1-4769-ae93-0efa629a56ad',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Viy0kK1xIXZHUSvdRmkoorHnIlyHypRB/sTBfgb/Nps2
MqFRZUYa/uVMmgmhw/aYENFQ0DHJKOE3ixBmbm7fQUrzhHyGFqgnEf5RXseGk7rA
jU6YQdnmDzaN4vKwCBLt4JH3YZsiekWflg1aY7IKX+bomJ3HU3n5E2M6FT14tIQ2
SWdEqM0DblnepobnualS+vbuIoIoo2wPdsS2yZKtnz1KZm9aIDHSJU5yD59e3DwI
segLEEGkKjOL48NzdWbsvoZ6W1t2ii7S54GW3vSHdr+6N/5smt/xn1NLwLDG65iv
dTRGKCQRmeLYJumvahDZdQMjRsDi+O5HypXFFrRcu1AqwXgGHrWSEUBpjXNa0lSW
Q063I8HgYhSPKEb2t0d9pvzD3/iP+Z7FliPlPGndk44Zx9zyQte4XSLKqg+MIOoA
NTx87lrmQhd0Kwsa/DeFZ4EAPjkSgXw4P7anjDV8uAADSnDWiFo6HXA3hyZbackt
MAuE3UsFrfcHQdZnJWR+RPWDiTvUln++tV5YWT2ez2BoH+0u0bQlK202YgfH0VKn
Gu/itlM9GApR++oXnmYPWy+0f6yOQnOPenD+/NX66ueuwPEwIqxOokn5j37c+rQH
FQgZxicMMtDkILklsflp8u9dXFSykC80kAeWutsjuSkoF0Mz93jQjfNMYcLYe7LS
QQHRfwz/HU2YCh0Fc8IBFibpD1+lAIfwPFcX09N+UeikyX6X/dOczACvwOxC+YF+
CX0wtjJqbg8QjNUsHAPesHoV
=r4VI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'af69baef-cb5d-40dd-a6db-c32ffe8b762d',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/euH0esNbefm41LAH9XS4hKgdO8cE7C2yukxUM8IBh0rP
59Hu3iuiq0l3jQ+pMzstRvhFn/ITMg0xbmEigZJHX1t3bKvejprc3ecxPNtcyif5
hJTKJhxXqciH15DCsDybKP8k3UAoPkSoC4eP5dRwANkmgyRQNam86mcpuLwkGOqg
SajjT14mw5QbsyN2fHOYO6nAEJ+Ecvg7GaQjj6/eo1L636eD3TBxEE01DrOn9z29
jx44shLrJlkF51mtVdKtAQEUS6moWGcvcxz1kmjOeHV9ZFf4A+2AHEvcuAylycdf
+tIklSOfNbuqlW7B1t4d0BEobobSuJAzSwkrU97bt9JBAWeGpiKP9zbsfw/u1QeE
6f8Sa+xuH0Mal9WHugsTMBA/q62Vkswln/MYQF/nyPxac1oqNKPI2rWtj/bWjZOF
GZo=
=SbZI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b6420818-448c-4391-ad6f-3c7f05163974',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQILA4Djy8VF6QYUAQ/3QPVV5M2toCxduoojlbL2fnfWHE2JxxGZkvIodciKY66R
xN/GDNuewl5ILBp8RhLXWY8PrCOzCOnP02gTE4TJ9zv7UVVnhb+UnjtG3073ruAD
MmYYsU/a671i5eJ70OG4Mwv7MXwQ3hPKh2a1biE+DORT50SLCa6wRyb4kKzO86TZ
1MrGLYAppwICrHfB6Tey9cbhlEQMM5EBVOn/0HH5Uwa0n/dTLMRQBGZ6a7oq0akQ
+6w4YiReCrnT/UiWhOvuJVOUt51as/QRmb6jo2JhR0IDoVVwqdYsLi7++2Sql5r/
sSc/cP4YlOcTfigoCIbnq1dDW3dvw0moxzzzIYjcJE5oTmI3rCw3V8S8Jw8Usqly
724iEqv45zuNeV+PqKAGUAzGzqhLNDg2UqyBGXf2wyxs8slfIyUdvTgz/pRMp7UR
TqMtImE30+Nt7cs2R+fqjDChcI3QFC1qu8k49jRCexHLUyFL/O/NnWfz0DX0EsAm
DfaayaklFwfr4OKme4ZP+wz/LZgyYPrdFGaXDuYIvWua8K7NhjtCqyqpUlW+5/Rv
hNNwMv0k13J4o49KtTQdm5kQC8h0YkKw9XXNwQjwXb+8/TR1///ZQjU0H8eaefMi
eg5iH39sGwrw3uHiyd2bGBw27Q83NP2X1lO2qqw1Rz7QZVvRaa+2iRwYf2fj7dJB
ASpA6lNlkGlDszWqKTwTMynw5U0Helhm55n2OFHllKxOXxLKJVYWqBmQPBWM/Sl1
3cCgJFP5FyJivZSej1jxW74=
=96za
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'b7afcfee-1a8a-4c87-aa79-60489191915e',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsrRaWKssbLuP9nSMe0iZ5HX/L7h/sjkqlEqHFvhJxJGP
vPzeYo4cHR5lQcRK5FSsBOsh8xDRN4FGDSiSwvhvgowsmOcezIKkOQVL44p00WHt
jlSUNp1pDD9sjeRTJQAPa/NZEQgBILt9jQMTnBKg7MqXafZkk+PT5rCyljYaFwsi
AUugdydgRvgI1h2wUiwvCPdOEoz4K4f7a3strk9Nqkn0H/wV4el3D3pdRM942E3g
W+VkGOZepK5qNzfET8QnlvW2bzy4aF8foBjGWGZAasxJ0YZAG9gdRBKWm7P8ZJHh
BHt44EYku1XX3dNEedg4AXIsWCY6qQVCZgVFiPwHyWY2YfsJImyQfph+ljgHsovM
T4YY053AgVEQ/vKbrHXrE0bQGpCV8ewuqgAX+sSLzCVGw4vamF0Jr2+zQaac3dET
3LMQ4YY7U2WEEwhDG45kK3k/GaUk8Ujh8WuMDPYI3jpjfbSrFL7aPKvBTPQ7XyFq
2hyDvGYKcoAsCZynYjy+e1RCb5dlzngrGrfyU0dy16+x/0moqQ6q+n0rLdJ6A9xl
5u2tBkRzZi84yPMk5mMXAtlsfNkHBImH2Dui42yDAjN2qNAccQ1JwIgmhS+YZxR/
/CNayTtNxw2VtysQpqcpJEBjgflBpYO8Ck6msQBgclQefUNmmY/02K/YW1S6vO3S
QwGXajVVkD6VP7pvxxs6IOldsz22Lwy+kap4IUcW2tMwlOFFJGTEg2NIcojie8LZ
xeW6rWXUOLIpcSVa+e06E+hDVAU=
=ScOQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bb22de07-24bd-4165-a00c-afe488ba14fa',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAhLxm7tWGAGgT9A+IMYGuLoZM+8+qqqBQV1NhxLGEZlYd
07/HZnr0IQMgennBDwmZp6nFGY9M3YpMuU5PBeRQgnppcPrEhKSKgA9udz51u5wm
pXGkK6rWb9U/h3+k9jF4GsNqGprYaNA99ZZoKNZOSizxyR67budrcDHZt5QDaSWO
T0uMB81kYbEN78fShPWu6behSMmqw/k66w9GJfAZEY1PgSgpH1YLeV/BOetv8QPM
kbCkGDJxHL9eeUPuwLUGrHiumuBwlz/1hOAzmk1h+HUCHBvQ7ZahES4XWK2aGPkS
gAR72sZCAQ4BraD/+SZSvqpdVvLIBG1Zk5AqOpgW1A407TY75FyTuUwn8dqnh83V
CYPmV0n7wcRzi7u94QpKF1HQTCkwOs5pC7k5hW8UBioDFnwoZyh4vzzcLuwOMmpo
kBRuvw4KKMZUvMuRGi8UqSsq5izuVyHHp2EgndIcBb8p1KpF4cLuf32NE5Hsw2rw
yuuoYBcY1KAFiwAJE8yqZgGcCjbOwY+v7rOZLmIlJIe7wNF2Rp68OvWK6eZ5IeQM
0Lv+gB9q6uYfuQ/s9JwWkuwW0/5A1yqd5lAKE2B0ua3LVS4LHfqwOO/vUM7gxIwt
PsCuzhHEQarMy51cJEMsO25R/lIidMxEZNl2Cd+mb9Uo/9eJPkocxN3mHS769VfS
QAFHTb0xqhkLWkIGR7ABpauOXSu5bshTbe9AHCSJ9RnqarJFj3sLTztUl5/IiFpE
lTXLeiUiWaEWErh5hCPV+J4=
=OEkA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'bdcfe786-a1c5-4c56-a6bf-38d104bc0d56',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//WvJwkIMv+0hFuF7JCNiZElgXl1UiZLXEbCnnBIlVCI9X
gLlpRDdxaBdZjawsrJmOnbh7FQnJjBkOCvax31aI32ztm1jKY2funO7Tsc5alzzS
PYkpGTz250F6Pf4xXiITXNWUhIJdmtk7CbFBcj8vjA0UWLH+X0Rzpb6CMBqhISM+
dOsPFve9g96FfB3M4QeD2G/FWJlIEbF2t3IQE26hLfCCA1k12J/pUknWK/pK2Q6c
znL2aINonIAyX4yb97Ka5MUluFA1tJhASgH+LY1bdXXS4cxWmChlGkJ2S/kN+naY
H6kCpIDomTqJSRUtVG0QZJ6HQOGkVeYghpwKhvoLiwpSbK6UuGH7or95mwGI3MyU
+b/d9rgdZkB4jx7roZEN1oOt2WjA/QL/25VqqVwPaFrC0X3uqgNYb2NtVDdfQk0Y
QePYPykQ3gdfpKD3YFEc/qCfnxT/inkSYX31Z5aNmBFtpOI9sexDmhKR7fjYURRb
J1Q0v7YT6PpTKML9qM4JZ62usrST98P6A/st8t/Cp1BW2dgZnUJsmrRWoxDD8w1e
VaRfzLrHkJwwGCb5rKS+8e6/gJBUErseD3JNtRgDkYZBE+0t9tWX9J5pC4z9MXOI
0w+ahBD/iULusj9RsR2D3RZ5+c0PVYhjBHhM7HOh/5ihQn+ycUrDDA4zUU2aiXDS
QwGo074gIJLtG+5BRrbOjo58hTLOP2pLoha3YkP0vLmPJfS+/H7QUJP/aFGbl1hm
FSvnwitTOaAOxyZaaRfkrzcsF9k=
=SpFF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'c2e47b18-c34b-4c14-aaad-f074edee2d9b',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAk4scZ7CXFopxiQdIv9DmoSNx7QHg9z2dEgR90CmgqTEQ
srDrJ0H1Ky4lAlUUCwJDxS7C6QYOgrs5n+XGTqUvJtYRyS17ZonKSXYSKLgLRwGV
TZViRDWfMpleI+tOzkzSHI1ka40atb4BYSJhIrgdXkQL7JCbf3dRn9L14Ut5Id+U
0SXCJb6YfZ3y2z+WRNjpxsvH9KNXKTJ9FQX9rJ4yFlaSXWyLrshw4jrGc0jDRuLU
izEJjvCZ1fc+Ao/BZW3F7lsrR+sFHAvZqjrXOnPo4hDuPOl1Hd1IH3sARW8Oi9FX
VIEidOy+khkomo0ic905SMFOWIcCBFQ4BFE53sLCxjR7rrV04rizbWfDwfccidvO
SAnwizQzy+lAHmGCscKPeYrhT7OtyWPclIIK8pTLFIRYM0J65NXabb6lM0ohNhjh
YhBHBHjMlAss47ztQN26VRxlgrfOeM1SYEY5E5NcV1lM4V3tK7A4vGUO8fSC8ZrJ
ugLaJAVLCYoEY1Y2mIyJFiE2whL/4CqMx5pJo5uUkJ8u/DPjzwFv6a8ZK8RvC05y
9VoRmH0okvVnS8/fll86/Kss6XHqxYe82iABOhGMo33pvVR5jdI9vSiIALpam1Y8
aQRd6jaoO3v02GmZemsUE9BTWEcTkPjugGiDuNxqTD25qYwVy4UlJQElCGgcxvvS
QQGfCjQT+Ra52X+yBTbecjOMFMi3y+BJGw9WpEPuzbLtlOkbrXF0MYSZMAXHuS16
4xzt3QETSENILIrbSmoRtLh8
=eedv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd3f7ea5f-9926-4b0a-a728-2ba3680c6bf0',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+JR90ueKWOPHIFNjd6r0drwKQpgEa9cA3MAf5vRf9f9Eg
uFK+dl3Wjk8GcfJMAHD9T6nXqjBLQcBaHYFDeaVI+4dfHjqujHg0oxefQwhInydj
iIkaVtQgoEy+SsKH++VQdF0+FUaai0C8QbeSDaRW5G3fl7Yo/iiDS8uKUIrrZdrv
DgQPKxajvKWQjVbJZbwk/1DCwNu/8JM0kTbr9JhtBspYo6DHGtnM12ysTozaYZd9
dLI3+Ir9eqgSXJOLQBRwYpPnNdK/n+/6AEcfBcFvZCDdZ39xLELUtyJoH+KBseGf
YU7qmtCAfdEqG3sUww/Y4cTB0o/GIZ5i4B1X4tN1W50cFQFB2isl/364tjXKdVak
7BnlomfMuNv3Cr627OwpjoJ8Ro2QSUQ5iQPamgyJE1l4xh8FTHj3vtaDACuFxgVN
ZfXtW/AAd+0/9aipqJRGXY/W5VQV8LaAW1wAKWxQdrJxKwBPNyYhzAYRMguxz2oa
H8eI1G82tw5D3klBOyiUK5J0ncWQDDbei33fdmyIHNgnIx1OOKAF2gFGLwNfixEn
ycFZIp/pg26PmqebNJixXJPrKJcfz5F3VKmiNEpQvwdXpdNGrbXLGvypgUmyYTjO
1UAdjAxi2lDuC7TKp5HwMyi9Y0SlxOXr09fLaDst/w2s+8Ohd/+tFFVJ1/atf3rS
QgGuEiTutpXX7C2WxtLX+ge47QMjxDbM6b6WRSBfiHk1+K/H0n0zljlWVs/wHKsy
DSzU7heuUmuv5P5ZLMxcFh9zTA==
=3FWQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd5adfb3f-e82f-4799-ab08-b737ed850555',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+O2bV+GeR29xvdGmmgMYS/s5sq7JstorJeDF/XpUCvQ0Z
xyzmobflz2syZZ6j25ubhM09EObP7uoy2mEqWMcqLRCipec17WaH6LZ0GADl8g80
RqwNy9Y2HlqDfKw3vuvn+Fckd0DXYc9l2DapCaFk1Es6YxvoUQgByl4tDjnpVWGa
6Ck5iIcFRUaDtRsLT9oAxfW5PL+MTaFVrVpWiVF2ExdOPwrBppJ8nYGnVCG0H4jm
slGt8VaGNzaUVuPmJS2IDNyjYKchHIwHL1XKQQQrjY/6RBhaMpSspJZfiKqMTNIH
qH6y/3p0jnb5oLblkHMYeoJMJaS0zGSrPq/y1bwcQQ3vqk3TeKsT2q9EG1PthCAW
H7zFPrKzLcAvafZmvlZ1Btd772KxnljSFLSL230sovZEFkRGKfOW2DCn5SE/+o8b
lVuN+3IlNjgEJ/bCnb7jTzuQSIUsymcIZmBp7GJiqrk8VGRlhUwgkD4vPkSPgUGy
kqKtaQPEnRWxgRjpAOPISb2/xHG/iWT/CrFSxVj+rSQnjhDik3CUrq4YYJZAJVt7
U9EUOKBIfquPPjVOMKwvfuHxWDa/NAHKgPMWbzmOs9GY36rz14xSf8R8yDRzRMwP
fvomnVq+d3fISmw+oUGkwhMgJo78BQbQVROUWcNabgNy9mevqmLPAZblUcOtEIzS
QwGlHuIu0g1dsynRUBQUMR+Rn5bFIuV18QkQObR1xfQ7o6tbVGYLFjp34HABi1yn
lDX/EjCA73JqQLUcu4+1pt2H66c=
=aFUN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd5bfa47a-ac37-408d-ab0b-77e30048b94d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8DpiujS3fg2W7r2eMC6JU3CV/EaSz3rkswh+zsXsXwd6w
TPWi0RNxiOHjYwRiqi6Z3+r8iPqi4r56XHoKdmFZdhXv67RMOObDNQKermp+0d8N
RzOdMC3n24qNlpJnZRTMeBSX9G9DqVDLgot1+4ohntxQKYvSFGFBQMYmNtMZn5no
pNo9uWJaxcHvf4gn+gdanlMrTGZO65A1Xr+vrgbe1CjyHmNLj140tcoM15uLAIev
2GDwL0QkbgL0cagkIDowGR2KxtCB3OaNiS0OIRWTMz9wYoVgdYrMeNwR5Uy/EMhN
HIXmo0VyC16cEQW9MVLwbK0998h6dWopN5Zd4brI+xIEsNupCj6miqLrDGxAXHjx
9haXmot5YpNpgGmLuSEOqBoTdydJyezEwxzU3I7wGaamSuGs/0lOXPcloyNpKY4i
yh0QHHrzBo/PKARkQjJn6OTE02hkRsahzVhNTmiSuF51lDHeYtlBBbGmrvB7jmGR
6u+x+K29uH5i/Og4NoFyMXbXgXXRiGuZb0o5LYqGd1n/AaUwZsIG5N5lJ+eUFnhR
oiyIj2j+w7TjSk4u+LyIgyLBeHNMsFNNP43fD11PoKeRp5sqEDy6Knnud8Ibno3c
8jlSlcxC3NB9zuNxIEdyHIQWulaZrcZ0j8G4Oq28Jdmkn2es+G4X/g022ET4barS
QwH6OqzemlKI2YwqHVjalUb0paO3xuV915KEnXtvSt0eGL41IIB36UMqQlaArIFZ
iN2TlA6W9R/GfGGJYSxYvwyyG40=
=XpmN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd764fc71-9ecb-4b97-ab82-98dbebde4d90',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+KCCPBynQgpZ1mYuU+CvtHl2VZnSDx5VGa50fcC62q2ya
+ZWwB0DkP73t8jsomhvxGEM101QHS9Z+ypXJOgZgqGykWlWsxG0Ehn20cUuaXeoa
fKTlssjce2jnxxlp7aseD7xMRKn/RTTDg0AobUV0bc3YpvqXVtIKkcdJkg6CPiPo
dhFBlwqIOduuN+FuB9FOE2/rH5d2nsx9S4+7oo8+3ms1uwh9BfSFE4SscmA6U4AZ
op5QMxY5u2+av4c9DQ+hv3rHu3w+g1r5leu5SJKpk71W1X0lRfOJm+e5gtmwud4l
MuAdIVffznC7+WRumewIGmJb9k+vqHw232lqAME5Zd7hrAzBiaBZ/jXCkjCYMu7z
2DXaK7XKqhMcTKyuRXf8aC6IH7v310AY7yLTmChmwKhvNxqkJsgitv7dN445KDbT
S14i7stLeJHpl0BFQXxnpwlHNtYad/NWCENc5t+laTqTpz/r1aYsnYr4CZdpX+AB
L8qWG+63qlO20aRGvzVx5pbfnrGZ76MY799ItmFBu/JM+5dHUErKmaZ3puc77lJ4
ga1LdwDfFu6ezeVk38L0IQ35fsSfQftdubzTa0Dtaj5w75Onypdl7wlasrFZc09P
LH5cJ8MbReMG2yeRcQokzJUELMaglVq1LCmio0Q/ThkK9LXTsTzm9mnA7aOuYn7S
QAGGjxsJAqzvbJUtocTdigEp1Dx+UF5tkxiiSFgP4DOqT9eCj5AY7uKWzLG+zSPw
mxTajkOn2Lf/jAfzKbgRmuA=
=ia6w
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'd950bcc3-c75c-4785-a422-8c4f0fb441c6',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAoYc7lydtkwq/sx+kspgG9/a6qXDLhodH3/IpSxtljXIX
i2hy4FKGQpGRzTX6li2P9n6hXF0LooE/DJmnzqCBPoj9rONL16hxMGcqPajJi8ol
jAgDnIvSy8OEx9FLyXL/s8d9QYuOnLiRS8rylJl7GrqCUuCg3pLB5K96rkiOW7Pa
FfPK8zQYECIhk9eqYD1lI0mNMseZM2C5tpcdG7jt7+ufSsWRY4bsUXs+RTusQm+T
AZeBawfC+80cqDo5VIv0QDUoL5tAfRrM1awHP+2VSvyIJJA6euepT4RcTi7n/MPB
dL8HljfofbmatD09+gN8ZAWTr3+vjUUIDKAnrItElIr8BFSePkNoxMA9mIXQrO+Q
JyMJba9yVutZcJ389IoCUPWOLlGSe4zmCdYWg4ECpMBSY7bdEvS4QfxRKMKsXMkI
/xdUhu0juIoa3CQqCmEAEoNZBUvI7r+XVWQftALur4r92/agjlWprLF5fDUYUtkC
ZPeDatspLFrMiE4QTJMsuQzRsO4uj/8wq7UUe/3dZkpSehcRXkSjfMJU20c47LXf
OI9FcqF+sUJ8TBpYNaCsmaOLQyAHv96dMC5JZhGN4S/7UF7V9URBdQKbVSv4p8YC
5okDetJSY/2vzA8wsCaFMeIJfTXGzGUT3L2pkx1h4HZx88o/X0QftVNfPnH+7eDS
QgGjqUubk/RDCKsGgn8nBBylO0E6ZB0eBEoR3s3eSIl5SRTT9HZW6EGG92JFBhgg
rUCZ2NKPG45XJgewy3mCq19Fwg==
=Z7qm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'deeede5b-b268-42e8-a302-35acf50861ec',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+IF/Vaq88GdicCKVFt4NYg2/JLtNRGKrg9pCzUolFjw7E
MhsR6VeMsYYK8u3XwGbP68KkGqzU0n1nb7zjo2fv80Hplis6wQ4MpX9iFIlINckb
6L0c6XrU2q6Nf5k3aXgJ2iiiqM4Ugb/7HIiEk/JYz1wU8Np8hRzR6wz+NaHEc6ll
DrZwwoGur3zSZJ5NPFiIroo7dsW8hqmkEQ3+wcBxu/b11K1/IAQvIFHQclFcLUel
fi+XmIzHPFmHQm09LhdPvv9z4SoUqQVo7FCmkF9ELkLpnga8V7x+Gm62nBqOZ052
lc51/euvIz4CQ9LHg8phZB5aTo4yAQcBq9hOwKDTwJ7GGEvUjd65/1/E+V1GCF2x
6O/z7WmX8xRlp40by6RCTTuXoVAHe/FdOspg+qqEJ+tPwiPCloxAlzX+IU+czC+I
p7mAMxHMYuJDB9Jlq0XdvVVluAJX6HW2trMeVlZntoDvf+EhjF/MWCpi5ipAbmVM
x8T70SZFeS7RTycpm4p1XT0olIYevKWfVcJY1sEI+Z4MKj1iO4DNpH9a2Vrn4W0T
HTucoCksOI+gxPOOSDXiFCdAUpQrn3N/Wza3avR0rNneEwc76ZPNf/4FXY0BulV5
ywlekNQg1LvlkbHLG3btKVlXP3mZi8vgJtWj0GwMD7bMBqIpapyyQT9babneoiPS
QQFsKX3y36wDJfAdkqFW6zxZi8hL035mqh2Bg/uOfgoknS7q8l1MNVOweI3ZfMzb
44zH1QDemZRPvQI7IWTQDOAc
=lfy/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e78acb3a-0064-417d-a5a4-549873c2ff27',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/7BFWGpnHsgTtiwTs/o2nUNwbLDfuLV78hhBz8YaH75sLa
a/uk65L8c67Eh23VvEXmytEYwbFWGKhidGqOUsZ6mWAuF6GcRFrWkIuaDyhvaARE
l4J9muux9NWSSSvoMGN2JFPPv1zjpg98GEem3MFtd49udfN5wNiCVTS67gDbyeio
3gqVjki6dwg0/wpzCUl/aF6xT/ptQSK1sVEljS7srzS6CITSWJdl7J7/YGNTXdgj
0xTEsyNtd4NF7b7W5VejUnsW2QC/ZObR8a46yePlpZYg6my7cajrhFYjHkVi6EpQ
5qKGto2qNK/QwKIkli6jqD1V27OeTIRQ2pWhulU7B2xZxfNJJhun97rr4dDEuCCo
jzsfOlO4xurfAYT0/OJ6QtQ7scfrP19n9/R23Hc0kd08WHGbZWzyZosFSH+knLV3
56iLveFbIDQCYUE+HaUvhS7SWsLEjiYZ8k8ZeTg7eYfCEghF/wiTFIy8Er+RANCz
p8MrKEEn0+V/ZPWhkaANIl22WBFEoIcad4bb9tCKZci3Uh8WcbNF9RGD1Id4VAwG
NqhCNPO2tRfbyPj7EtBAnPiUnWRB2VB6KXUALw9aWn+EF+fdueNusZaw8yD8MzDo
eTnQsB+mAgO7iH0mhyD1EsyZ8W47C8ySrUfWqZ2mMtSgq6WgJyh/5s5HvymOQUfS
QQExml1ZMOKDZqs5iYNECmtfg49q5qBYH5xrkRPpVD2F901JITTOaENTOzb+nEUn
w+skXx7XAZE5BbfCy4qRgQgk
=jxiI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e7e3a40c-c41e-41cb-a477-bd9a0bc6ee9c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//YJXSAxu/TFh9rQJQKrPZZP4IHP8/MQ6sRUpYuIPf4SwX
tWEAPZXa0YWr9bf2ti6uy7FQKW3vU8hDm+WY7KDpKuAbyKkJNg4UuTn+EwAZynZc
QG+j2x8+u4fTCvZHP1jIOGl9hj3bTmx/MpljwP4KXI30JemfBgbQYMVKE1CFRjvR
K41Y6slvGqWBzSqt98JWAIxXjluLCUev1IuH6EjDU4dgNAiaLF4oIM0m6sF6zYhD
2kGGSP2ORkZxCRc49yBf9/yTBDmOoilmfamJD2ucfXxOJ6/+eBjBxW8XtkLeHRdm
f00kWLNevKR3OJ6pLgH9Zud7/h/I2N8WT53sRyjFSfb8VMVcfKWcdlWBnPEifYfX
F9s+E2IfDuphbCchmCCJVtm/njLKwZTKAokyBbgBH2DFNWp2ki3g9nS82AE8cdGq
5V8B9TvHxpHXUo2XKYc+HB35LQf2wD/r+LRKmhZmIOjgh5QT4fHMwbEvQfUyaAvW
bR0GZa3hfSKFOnNqlogoCovSdOZLaqCRVBNuKvsd34zV0URz3mSP9l5pzXWa0WK3
Ip1BNYZSHh3isoODrix7+wmpv/GG8Csavzb5rlKX0EJN1O3FIN7UFOTVdTyr85pF
sVk7oqXwdTPfshedWzxlOpw7RRk3cYyi2P5CIyqw1/y9fS2LS+rF5eXKEhj8TkDS
QwFztrqNkqNNzxdb4Scg1AEjwcUB4QH5XDXqbN6n+PxsbmzlmSXHW7BlttYLIjM/
Vu4rOYQ2yqBEyxNAYrYkUMvEDMc=
=1APl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'e9f6dc4a-363f-4ab5-afd2-20cf5ed8e460',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtARAAqy0Ab00d2rNOVbHhxZSs+77yXWSbh+jgE3hxUkOhq7Tz
sBDR89DAOVQ+G7evJmpJLLTicjBgBgiUKpnYplZYgW/pclTVgii2Er/ivZJQErZb
E/dI1kUk0kLOyKhpARp+g4gE/ddVfaVgBQ5Edl67x96A3GXv9XfPAW48c3xdTyZr
t4AdOX8tiwknC4FF4DOHRnNFjOmDO7bXUVr1EY0jmJ17se3q7SFuBu/HC2J9FufL
7qfJjej5dlptjf76uQguyAcQX3pCWj59S1YD074gzT3hMcUtftcnljug+n/6Mo5q
Xa5V9egGYe5fcWfARWa9Udjx8FxoByzntByzgSDLSADOw+QMo6GQHRZEI48m94Jp
rRqVuIzurdK32pSrzdprijE5lmnC8QYPKNuXfrUjoprIlGYVpTsawShQ63CwLwKU
bkdetum5vSeKRJrHediA8RiP4wW+LeBAOmj3o2em48zxQ/38ipXSahMCc/ljoihG
5r5XRrTDAnkKpfi6o+NbJD5sk87hHbfqGZBFs/H/rCv7FpW/Geg6StWHsxt2pRmJ
rRlTxxw9le8vRj01I5H8fvGB7hW8nKl+fEwUKS6KFZA4sPw9AHnbzw58TUhxfA5L
O5RZx0gWa7rcMCrCWHWRiqrEdDRkTtYMHeJK76NnOZQsCIzZBZPaVovVKlNCdZnS
QAGxGawNFLPCBUnPweQXQDIonbjATRio/3xjb1RCWeFVeNNZRefB7lBe3o/k46HH
XZK+tgNrzMbuynqfinPTX4E=
=MToI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'eef7bf0f-49f8-4645-a20b-14383a625f2e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9Gzusd/eWEAZcPLLeer2enm8OFfvanCzzOhg0TJLuEG5+
5jkYGyUTmbiwlBrE2ZCNKlgW2ZNcFLQRKI98Na461+ykNBMpXjdpZi/8LfYWAsJo
DSxfz+yFNq8rJbci2vSLCwGOev/+t+YnsC4w/RJLaGwpxW1Xb9nwvqipjTb+pNI3
X4eF3muhp9cNiHJKT8YczJUpzTaixejC8B+meHsYs2kK5/atiABILmPjA3+hgmgS
VxakGWYZ9L+k0ULUOTXDHXAJGB/YkyzfC4KCMBwn1ggS9JKMj20LZlPK4UU02jd1
JLamy96TxiZArSizKEHqA2KbsRJqNXdMiQChEV9siCQsJhfOxwAAMWTaponu85Cm
zop7HhCh0VSaEKzrpnY2SzO9H/R7oz9kwCQzTr7SonBvmnWYawSk185mJHOZ9AKW
8gBR4aX0Qs+Kf6xrKqB2fpf5LFeoGKe3JVOeVdy/+a2Yy4OsbFJfc3uTIA6UlmcT
iJLz+232D0nFhC+RYaxyc9WCJ3aUjifZI21ymCJjwdHFrq/HInk4Od4fdz1uaJEc
uzAUgzx9KECea6W+INms+ldUdYY9qH8OYjAKK9pzoV6SC7MEA+7knOdaX0nsASth
uT1W4O8lYaUupXVzV49WC0wF2TpCgzDNJ0paepqdeGgcXo29fxxZFZDzuUa/fUTS
QwEZe9OG11QkMwqfTPJFQs9OHvHgcYYwUr2eHvjNSdY3s0+6lGMMG6n8NKRjM56S
hce/tSMOUlNA+5gQB/97p6qTZhc=
=v8eq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'f5270ab7-d5de-47cc-ad68-db31daa522bc',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf9HhOV6FlawcBtZFbm4tXESrxliboAoogWDMHhV3eYhd4M
GRY3uUqTS1HnUy0J33a2Mb2IilmRBlxeeerhPtdrTjCDquNcbyl74gswaAAYE0ld
jv7xru/3wXE9Ndv+Bwb9hlJ50+HokO4p6x4yemERUE37HgVUBwTUIGneNv+Rl6SF
jm1eR12SJyvVujhkaughWeN/NaL9hx8bm2372Y9oTJVWWTQg+8kz43iN1ASUz1VB
EVtGqE/1NBf4EOVzNSVSw2jz6+snSsdGe10ldI9LWVqgkTRD+xyKV8/i4aiRIhkI
eJb4HoeSjDskVrFdktbpQfDun9KhhAtiVghIrkTjVNJDAUlEF9NtGRCUjK33ZoCE
OzoADJBuqbtOKyL7VZu0cm11+PbGwJAn203ex149I6jGacGROTXB9GzwMTkKduHV
bIHhiQ==
=+Fk4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'fb8051d9-ca3f-4a28-ab35-6452a4374e68',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//Yvkq324nt2MUSKg6hL1nO8yQAIXxxyHKUE+4I5n+SghI
CZWe/FKkwfAVvUmzMBWomFT+COsbQjxiHRKNWLzhM4ZwZ7nppOEK59FIVScBI3UJ
Q43NYe0hGt7v37eb49yND5D7X2ZVbx2j7/yzEpn0/BxQ6xKLgLb8PfxModtIpKS+
OuVv/zkwSPVUvz4GFCaM/d0xSvKUvReoKe1Xc2+K3L9WhKhV/xdsxj3hdwBroniS
IYKqGyOvzE1YUJko2opJg0kULpOfdByXsfUzUii5puBR7wuRFEfOzerm2FIee0Cl
Al6EW54VgHqHKzU8BLqJfsymyJF+4HPKRw1DrCJ0V4WTz79OjYl/UFU690ebEQRw
Smf6bd43W912Ahs9GCg0Wcnmjz4Y0XtqKYfE3L5r9cVdo30rpt6RL3jllxZSuz9d
rLBUOyU0J+q8DgpCwMhJZqIbC9pQGE2ye9l9h9E+rxF02PsQD5EexUcfJitc0cly
uSaqpWnr62chW91s7MeJLhPpy6forWCUwFlW6t1DOrfs3hL7143DTVFF5CuP/0cN
UxbLYs8VYq3+jYV5rjEblOmAm6XWiwZOlIWjuXaDALaO0skHrvw3VumNxNOK+MmZ
V2/z7kufr2hrZ45gQDVEfbg8EFFq7O1ziv4TDs6c06xSSNzDSX7HhOs+P9YseubS
PgFqzO1WJPC/HB+f6OTNYz4Fn/zXMOTNnJpje2GcmmrAogD/BbmHN9H3ko6rF+9Z
x6BAfki48k0fiGaVVoA6
=U3KI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
