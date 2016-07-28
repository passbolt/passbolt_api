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
			'id' => '0109c76d-a2d9-4c37-a91e-96d3b34a0e2f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//QtYSNDJCjJsC4vuEDZ11OVIuD1joFpGebb9FSPa9nQ9S
zBv3Up6lIxRkE4tIIsyTX6HEL5v+9aCrdnOqehDGD2OKngnn8k2SaMAsfyijSiJI
2E5hVcWs1gmk5TiBQUzKrGN+IXrlUNC+8LUD6OVIkiiS1rEDW5oS4NcnFKjg8Uzz
5KP8l/I0uCHAx5aSJ1/vsXjRYHYnWsbMzojVnd3Pj2nHeYTrL5cmHruV13vuoZVk
Cp+VRiBNiwmg2/dd6SQHUygtAFMOJFAfe5Jd5ZH9yhaHtyGE+g8hvHJcP4qEjEfu
VsNnaOQ/gpNK0CuB6+agKAYZ852BMH4q4gZtLL88WQofNjJ4mEdOfEkSAAmmiS6o
RKT2dRZomjaGkTedXXKLi4LSQTi2S8X6EBimGu3Wf5pU7Sqe8oYpnezza3BValMj
MW0ieemTbCMh2bRL5WQvoUzNCCiLbays98GvVVwK6xRQmK62Zp1bh2Z4fivtljZc
xQunXf473I9ZHrKce+440lbNcrCm8MFk/JmGccJOShNtj14FmQaE3t0V+9artNlt
a+NupX9blJ6EUy3AP24I7zLwSsaKlGcqHDdD8FIhAjQYxG06c5hEiQJtMVwBGp9A
+EzEWPevBMhNNn16XYFDDibDroRk2R5V6x/N6pJtne8BMZCjco50jMUgSfqc/nbS
QgH0eVgd+h37ULqwzIT5ZkiQs/HWr67LqLx5dlunYDBFX5/dj0tLy+qpy6jixQVC
QlwAHpoNpb327GRnTPvUl+fdJQ==
=6yGr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '03047a08-e280-4d4f-aedb-0657be7b6d4d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9GrnQWxmx8eeCCcnmcHSNtK8snlaCrx4JTLVPu40NOaV+
a5LT9gLOobgTVo+okMmlplI1t+T/ghvkTzmoA4CwjWZFBQWZWW1pGpI4351UQSO9
xBpc1Cedc4hvBDq6masx6lM1m7LBLC6cjBRPRb4azu60eeY9XbmuHQDc/PJSrDf5
b3NV8yu2W6gy1NafnIVZ7Zs035p4o2gEqRB9mkb4T03fsAPBNMJG0dnlndzKxF2O
qOEGu++czYmjoc4ZsVEccA3uQL57zlB1Wk6sMf7BNwFzzeuGSdGYeboXPBNCU4L+
ivvOABm5UJEZB3AU1jS1RJwvUKzaQoxbTdvdg2SLebhJdBeqgOZCl8Ev9Kto4uYx
KAFyckvh4qNJ2ufr8nVBNwMuK9m2a4SW8DTf4d8kxX5PiH7qCG7qvAk8B1FB0I/r
vm0t0REHW55EDp4Ca3DFN3C8UsQEqiJtXkLlc1ADEongMGr3dMtbwM83tzzJnmjC
ET6sJEkb6GunoOGccYkdeIrxZLaC308vwy0hN2QWeKe8wZCYf7FMK6fahMM+ZBfo
WUytRt2D06PmGuM4xhSXsDcSM56fEfM6Q+132jDFtbwaPngGi1dOW4m8ygvgRD06
5sYc/+2NytN57hRiTZJnA2wKsCfmCG26SpJA+P3fis91Wy1YAnyZrp9ERED7JJfS
QgGsPXjtCYkRB9pHE5qL+Rej9ECLyqummomLf5Ly7pD3K04Lxy1ryqSJqN9ObNSG
vRfnyEELYMs4nQlH2oPG/4Uvyw==
=aXw/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '036ceb93-3c9a-4b94-a0a5-83d7029fa524',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/9ETPdQl7ZGDjiGV6i5d1zncFdkRBNjWdMqavJYNLUIyMN
1WbLuxTo40U3SetWRB6lOTHuZLq4wRI0NM/aq9fD5bHaHvZe5YDBpDJ4oOysWBCJ
1Jic0vqpSM0r54hf9339KY7sqtg01ADToMmXnz7YZW7SEpwLIQLDNN0f4PfSpnb5
ZkL8WFPfktMkfpMZMBeWtLHjMqPFr6oJyk3IGp5H/PV11+xhxEFunSeAlR3H+T6p
zfKiUi24YrttA528fsdbU7KR2l3b8UfuCANxHxZpeb0GYhGl98Io6qGLK2JYO05b
124SRE3xabQWsm/zmb5ezKkI+KakQbFmCkU4+jtvvs6iPX5/Q1Pc7ah07drALU71
hm7xFR02OBtbihqOFwj4zk7TCs8iOLQe9ej9F390PrzyouMv31zWO7Sr42T6aa6j
NFfw64FYeFZz/3xn6Tx5lC1+j8eBcUxhXJvNV/0KGf3CF/2t2uT2TmYyvx0CSHNQ
+Hy0B2vJvIFv2wTbQBBgbtY1muL49iai1XML43RIlKm7ehkcsIlzNy1soPwMIHwS
j0XmkAwhHiq6LmhebXo2n7qXbQ35wETVbwXdZtTLhXpR7qj6zula1o0v3wphAQAF
r09BHUKH2deIYVOw7t3URV7Gwbb9lkvnCujposplT2gE+VeUx7nQ/aXnRJKyWBvS
QAGBnQpyic4MSobgJQWYKuvILZaDFE4cnOP1qFIXpP4OeXawAI1By9fnWY9+Qp5i
ATdAZJ/uf+GQM0NU3hjf/L0=
=TYJc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '0bf8d992-bf1d-4867-a1b9-39d72244163f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAgx2BGpb0IzQFwSEBe2KAzfeYqk5ue+j/XUH0l8YOf353
tjqC65HHJ9oK6WS61ID0k3FMPi3NnsGao+z9EN0I+VXlAfXdsqsOHF2zNRGiB1cQ
vDh6l9gPSBwt2k9pqn/xh9fV2lyDR/xZ6QRkucetOr9DAT6HZwazLbaQSlVx16gv
2IGRNOfgJg57Gk77ormLVGo4yIeUrpJ/+/CupDdZaz1s72bie7Epc+nTzFOr16jD
6d11xfVtgrOiTgvwnd/+nZ16fWvp0ipHq0hHCJMSBTXP3yvDXtm76AaI3Bz/mZRV
et6x87ExsUEOdxJgKVCw+2dwSUEQG6UN2eBYFZ7H9PBxKkZ+TyQAFXikCaSLWEoR
82UH6vlZADf92Ib111hVvukqY3igeqoSgr3GmJwZNFDKp22YIGvFv8EJk8mZR33y
koJBVRls8qfpHKeXX2/nLIVRUqZXG8ipiylPMXDjr9CrxRPTWlbAAXIQ+NgewvqI
ctpYDHzBCXrWUVUlkb/bGN8vasKjRM7+t3Cxo084NzRvow7TRN66bQ0U8UYEGV8n
+FITNnu3TfVDeeTNXPE22mPVRBx2z6BHlF3xJ0j+FMq6avaSsKMuogStAHEkHJOx
D9BBIvexxnxkVOdpSxsJcc4n9ms+Ekg0W3OR8svebePObmss8ykXAfP1Ym/nEYLS
QAH1Jeui/2QIF+CxDoC4DipgkcNJSKFsFez4ThC7arRtMSPrynPKjZC4Y8+ggLAW
2R6VMv5EExR2H2Hr9Nd409Y=
=wvFD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '108d90df-9677-4413-aa69-12155947ce4c',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/aITXuVyeYtngm6kR/Ua+06Tfwpsw/acFVVBk5SuRsMpO
3UoBsWlgLR6J5ijy4y8gC++ZDfP7GYVywglLa+se23/PZMv2tBMDRbkrOiSBVlrn
OeZqhrUHKm9+AMmKQHHpmoc3IDKxYsrjxyoQGpF1Zp4d+PE7Fmsna9doTS97cEC6
e4Lz69kJRiBShMIBLVhWey7J9lW9085HMgIEjXdy5QTbZoqc8SJl03Bz60FA0j5E
5RG1FYA/Pl/J4YC5ft9RC/EWkCSphO9BjTUrQJzNHzt/re8cVku46n+gYs7DPW1N
L5Vw7CbPV4TzGpml/rFynwyWS5a/3qBZFBnjKd5q7dI+ATDvQ7u7QX9rplKAVPvb
w1nlzUkseQQ6hXTvCwxdwq7ojLs6QSRPBdv1jPm+gs5/IPANlCcKKuJNqmt6PGY=
=2uRv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '149c0951-6f75-4d49-a329-9d136c51d699',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAoZToqZp2bF9I7NEDwGZFz3mD8J6aDzU5o1zQbuW515r7
hkJeVi59wr+1F7CAi28Tx8a33ET3wHVJyfwOEFxR6TGalRWZruw2yVvlJTFDQUf4
16pfVVtRMxPoL1CDlyGJRfADzIuHXX1wkM9iFwVhqpR+3sRik+5IQzFivVIGh/VU
0tYLNHuSPxILD8XEJ/k/Ek2BMDCKcPug3LHEpwukJOtfK0z2x/F1J3ONNndbS3vz
cbGnD1pHRmtCAoT9typXohx2YRlbwN3nTOcyoi50V5vl9ffu+RfFzZanGacW5SPV
K5DLSMTq+XxxLX3p6chiIUM2ibpc7BWJXJDdzlRQnLN9PoztXMCJu2e1SgTvvRek
weFhvbuaFsHi7ik+3jMTbplM0C/TciwQ7xKVk2E7HhwoiOuX3QpdDSb1dEUIxooq
NniDbsXctc2vSlzldd9db7bQYukeR+/BfGL2ZUWlj+DIPEreA4kmnSsQjDdFAe1H
5X0R4IzySAqJfQNo4jvGoit81Y+Glfc/Y4fjSSJa5RofGZGkl1y3NaQdvbHC3Hd8
pK8Jbp4x45/o3J/sl0q1wxgvp+DYy6IxrtPZoIVKyKRVoKFbUis/0GY3aJSy7JR7
bHiMcEHSBVH+ai318aYVVTY1tOGmb3QH4MkdNvnGf7sYGTnAqxX6fBf7+LiUsS3S
QwGKvDIq1GXJTYo2x2hGtznBIxfGb+uWdQYyVg6kkjF0Qwd0Ss0DsXTwiD2UNPIv
H6LhCBTeP8SCQcd3UXwEWCwa0o8=
=EfLF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => '1a1a4453-e385-4a47-a76e-e95a01a4a767',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ//a5fUaqNXP/7LSaF3Y9TKVzcrz+5zdr8szMMusU8Cfhcf
9hHDtA3XHO4F+Twyen3XTPmpkz/2Hn064rQyfEnKfdG6f3mwIqXHSqqr0/IaIGu/
cyzb1VGO+bLLmExx4pbou+5h+2b5Cuq8FB3lDGX/gCcrgDFJ1qaaiTr7O0x6CnY9
BdIdbWKzqxgDV1p/MbQUqCUxsv1r/co1YTUNLKnvdIFxLCP4/V5qpwNumE4GhEgF
S/xtiHChvy4t6a6ZzvRidj3TGplyKUFxfW/8Qh5xqTKADOJY6EGiyki3paGih8w2
eRe3CfkmGqFrcd2gZeCmZ5H42q3Yn+rDE+05ZFrvvBo3Wq0J2AaxT8qyvIP6cwOw
Oy6Kgzz00JRu9xDHKAG1gB50cqhtvHacr7JjW2h/ix1794E6wt82zbvXR9pS1Z3K
BR3WSZwAPb5BxKIAS0uRVHkCG443MOtZnxMJEL7HzwzBP4aGbUn95pQXkhqbhujg
VsI5IT6BeJRpXTivF9Rj5MJrdHpiUsQEcktDxbOGY/d62nOpia8qno/crkNv+Hyp
xzWwquNngdI2meAQdoJ0fVPvYobURsqwdYkPumkZLHAekwY8Ta8gtcPdcnJ3xenc
mWjwMYjTwzhuu/l/EWuc+cmD7sM0eHO8RctfVCTOWwddQgMEJSw9JVDe4WBBWajS
QQF7zCFKEUw2jWaCHbl5FGWRngHpvALl8J1InhkMLl5usyV+Teo+uX1jNgAs+8xj
ZLHpHtZsGBHZ8vXloMIrL6h+
=owbZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03'
		),
		array(
			'id' => '1f275842-545a-467c-a1dc-2effd0fa1e1b',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/9HJ/8eS+IbZlivTODCZgSfSCv6Id55Te/gQgj6fRMc2LM
kFZUNugll0ElU8flh0vZ+VEk0+QdDPDRNbSRfhn1J3s311L4tt4QfW6k9Zm8rB/o
VMbrEB7h697xeGlwJHPkFmEVd9q/NvR664oRKGdxPe2VsNaZbJgrpsVJxUlueq2r
xOllLo45PO+rTuAM/cMU9zu9UPeB0SrYMndeqsH17ygM5idXgmKymNm/uvi135EY
JgCtCIuqjhnpza1ZnrUmO8SmhSiHQ5YzXa7rO+YuoZbEiwv/Uz2vuv/YNtGyjfXF
p1WvdyqkxOR158LDJu8x/YOfni1dZ5cYex9XNUb2dfFuQxJcWRGKX4fIpS1sLBDS
3EnPPDie0Lwtv8YKVN/H07axsj5CtY7SVMCC7yfhYTOgWrpj/OYJHlX4kWhYRs4S
aYmXaJej0l+q7UJMRsY3TmrIqa2RA2BYS+NmxxaiwDAmXz7OOhCKAvQDlD99foIJ
LNCgsFcr4vhV7tIxD2Jv48KWP0QYK9fNQ6+3+/S8Pu2MTQQ3wj/1Tck/mPaZaMeF
Unqx4tZHcTVwzUF+DMVboie26nuguM+aRb3fmmeY2rqvi2G6zC6X8h8DTuFgTqkU
5/n74mUKooZwHQ5WYMW7MMF1PN1/Y5FAU1cJsTIFso2HYi1UeeP4fjReMgbGkyfS
QQELfADGLpD3fZq40muFWzCieTUL4nyxPBfPTJh2totf1u3wzcm/q3uTFgRoUJND
UhgGAQyYU068h5LO2vrKYmXw
=Cwv/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '2064d3ae-f2e1-46cd-a962-7db43d1a19cc',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HT9wo0e//xSUWKcTfd3ydaFRgzkIODkkIfcz/6E5r/CV
pTw+37ndwvPQ3NsdYbw+XztRWnJXR3C0AaLzqPXOdKyILXY7KuTWKY+Z8oTcz5yy
OhYV/MzJ51Gdz+k4arZB/Qy8OpHunFV3o2XGI1GrfqyhW7VvjbxSPcmjLBobn+qX
hVTa8uXHiyi8PKJMGBqRdkjT6FNMPQS8FRZP6Q2hA1c2VmsEMKo5myaqqULkT+Bj
knARvL9H6tCUsC+qSpAHOusDu8C0isycGkkhAg/oDS+63NOv0wEI0Qj6zC/W8+K4
8hZqIbY94xUJ/M6LVCx5eq34AoxR+sZmskm2/JWjw6zmPhdhpjB4osTUshi+gF7X
rJnkSoRbDgO3bpr8+HVdCDRzs2as9piiR4ynBRq/+mWz423DFYPdJPPIokQlrDyI
e8c4S8GhMtpgdepdg1vgtlT2/hhtKvR/evvWauAkn4nk4xb9U4Rh2dmCYG4WcNql
xsQ6sJzrbU5gLa2e+FNtKV5ZXe7XSFyFhvyNV7OCa+UXDGGiRI0ZbZRYc4V7y3bs
JbwRiG4ii2ZWlge1y54+JoLCo4QWD9MU5/Cd1yk3pZj8ZAk6z2BHPyXCRUvUtPST
9EDYSm2RiWdn5pgumuTNxogSgu/DiFVtDcIsFgCxJmZJWXTnWn5HkOUu+3ck2qfS
QQFXmLdxA58fNa3KqkGGTjbeZRCCqV2dXxmtplxKnT7TGQ2spC7rUg2NkEDQGi27
tL7PcUqQeEMZnS+oX/krnJp5
=uCtv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '20c48c9e-9bf1-45c1-a084-0154f3a38529',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+M/SVBAAF4twSOw+ATidiJKi3gQZqLYClQpCKb7HPfCdX
ouN0XczFYSWxXl0hQ9Veo/Ur9aderyg4Ms4SLEUoNx5mUlR4LTAB0Pq93MOJi74d
mATrUUappmxQdMB1uPXHkHZlzbNFOGBMgM1ZXiFqhBN1Zw/ztyNfGf/jOB2yszVi
p1P213YVNgd8HH/WpaC5xT4XsmXfGtC55KZE3dGNF9jKdQrkSdIJYPGWFXfSovJa
NPbbdsFwPTmowhVX+D4s1aPyWJY/9KNEdM2LimM4/eu9aGMIlyFljAqxYkGG8yH5
8COOm76pgTyL1I+edAtFeuWbNZBI/qxHjIN6MS8YmQLBtTWg2qRmjv3sJoTX0TLB
6amPM2CNu7ZiiG8Pq4C4ZAaEM4MBrEYCH0NrYDqyC1jNpYSeD1tsgnPjEIKlWpm5
pS0djneOaV2AJt116GFR6FoElMKydk7PwkgSKRaeBnoO0QnRXULIjzk2tGZyakey
Yf4QBzJOiz27SB3V0DeBlc5L8KSANtxIqyA+f0eBQDdYumOpHR0NCPqa7i7kzq6W
TU0ajVCPO12Rt0ZdnMtXEvWMS/pj+rvsaQwNfHZDrs6D45J/urSU+mgnshQCCTcv
56CEzjZ74ACseB2kmrmIGqVQl2mrrDpLhKiT7IOHovqlZBdO7M8ysLHdQX/KEgzS
QQHlMXQkdB7Rt5k8ISytrcK/l2NV0pYBwl8pIWaHzSzoERLb8z0biNFTXYOAhQQu
UnAqFcQYemvmIARvwxu43srP
=4VPR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '212f18d6-2276-4454-a6c2-d9c3ba705c47',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+MYIyqnCWGjSiYAnQL4hoPVRnZL7mM7Gh+g+ZEU6X7QaV
hhRUuFWhIyiPZMHyzfKCuhvOVjUCE7pJKv3tq6XvywJ6GhsQpLGxG5SENUrdHxbD
Y5PSXpOMdREU5Dqzh2Z5zb9T7KyDKcfGgkVCgnRnQxMdU2I8PK7VCzrU0heu8zz8
Ey/MjiIvD1S6MsW5npc9HpBVTe1fJ2V3WwJufgibiwfAhXqVRs5EoWN2pIOhhimC
r6Tn3iwL8Q7cAF3zBoq1glP8429EtiuQgzkzfw4MPd9ZHHVTEk9/UYe69qaGJjP3
RZ8go2CT22gY3EiP28I+ODzSZ6BsBtxk3cLaxZNWcgQonBi47+HZorBHYlbTVqiE
3IMtUvEaZ0DL9XuczcOFR1UbtI+mMysqTnwAoKZ/GEq10mJin/DtjFRSatKe+2qg
+cEbm4ac1klemgFmFcJibbXm+vo9XOVLdKfrkn+R+2/lvOMfNIzuQdCucuECoOiq
+yX0Ig0i0adDoiAM9LORz/dnEYtlctazGoLHURKEj0QlzhZYZe4UdGIm395+vbZc
nx5NHVo6sz4ljak806kDCE99sfNcRxDZQHb9Ri5yLMSlB/wrNyNz0FVB3hbmfSyf
RvHru4tRbUxJxXa7mAcRb5GiX/tMs0GhyDxd0UXt34E/3SOWRBjftJpg5Cl8z1XS
QAGYMJk4b51PkeiHm6tGDvsA2q0IYGf73b5mehLtGCp3YjcRQe04tfeyY/yVVvUJ
ItRoBQ7eyvlCcCusQEv1VIg=
=bzOU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
		),
		array(
			'id' => '23e805c8-6411-4dd5-ac2e-5370e5f8121c',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//WFnBScOCEhrnvZPY+mniwCYvVCcLEPvCx1L/rNE1YhJz
VQWdo0X4FaROgsgPbdTPHa7JEleFq2Q6IoBCWHqBrXmZ07IE2UxY7EmnOsYzvLdS
ILaXh7ysciOm8fwmMEiM9w/HGXSgDZomtSOffLtmvAId9G8u2k52UkJsdwS/y7N5
GgtCbZCN18s0HWyOnqyLSLK9UKP48mMcyQwSfKdYGiX29DDe4EgovNACafHhnPOY
j1uV1zY+qvus+78k1QH4hrLIHY07337d+FY5p0zCsYNS3TvvpeIqPnTd0hF0N93J
9oUKbDYvxZGtAyF0EoyFCnWl45rj9AamkkRlRaUhsno+EhAJVXzYhR8fNg2DSSLr
Q5sdWi1ogBNTsGkD0m5yXPJ0toc+XVDmgKFvPJgwCTA4CZQKKIv3NbL7Z4LXMRMn
/CIC40aJlrgxSQ90Kq932EJcwH6wunypPEHgSxZUdZJdu+g4DUCl8hxiRcKiOA7s
Fp8/0gpeY26IigzcwqmQyNNUz0CdzSqfhZLef4zXNGeE2qRfC3ITXCNG8o61hYJ6
WU/qOwKLUPpmLmRm7tnuYchzqileNfYjxC/Oxb1UY1LoACh9RgNOUKdq9qICyZYs
THhEFGKOU1zLfR5sQGp47EbSgLTKXghGbZAKruSUB2n1JphLoPezBoJCZtgH6GTS
QQGM/FMbYhYu2H/ueAhI/6wYsIzx65Z2C6TTVol0k1UxxEMnKzb8tU6ugeTcvM22
OLBfMTdVcsvFpNfMQXeoY4Cu
=tzVo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => '261354af-d030-4a78-a676-abb7df839187',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAm2BKj8q6vPeUnHIKtND1Rq92YIUp7GDzgKnJHxmSXksQ
O2Xem3ZkmbqkhkU5lYxJJJk6/hKCMu4YRi1TcY0yDiA2uXphCGFXNifGZPf+OKCq
Esoy+/YB08UuKv1IUj/ZImFXhyxtQ2u7+r1yL0WkXAKiaaKSOgmCyTLFj93rb5Gc
Z9dKa+EzlRTO67RCjXMeplSCorpOZIWb0oHvtilKelGcBswY0wxIrzmD/3flGQJR
fcCBaLtOpLoJMa/YO2u0CU4ZWAxeOUW8eCF6PXFZusgOhUyommLK3UuhI3/AX9ba
MfZjHTtvwIngA8pXRliYfllTapmI5Px+VHuda1AGaNJBAUioLql8+GywGHX7vS9r
PegTm1C77Qu842jx7qLC6rIfAHgKjqRLnOwYKw5xfmExVTzt8ytn/md1m2Pb9A6I
AIs=
=R6Ic
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '26da5a33-6cc7-4c13-a972-36b95ad4a57a',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//aHQhwAFe8NMgNztP0v12+tUcClkQfVuILMBMuHcQCCfz
QX8X41jifIprtq8ralRM7iqRnDyQOmauzYIDo6nRLc2ag56pnsqoxgbbI7xkco7z
a2rTjdi44D9XAY0wJn6sV96okzSYWNmfKkCeXab8EhqPyeS3QXVSNcPmkuxe/hWY
4YVdfJ9m2kTxCzRmCK+fOpjZj68pqVj873YgWRizJIac0QYQ3erpyMs1T0kiS8yu
ulKIA0f67EZcZLS+gXWQVpJNqIJB0igeaYnVPw0fazlaBEo6YQYS4W/aKea5lqZo
j/FfN1LUncA/toDd33y+k7rGza5IcgvJv9jpwBNZSO9H62Dc7U4dqfWPAG5Pr52F
qi8WTHK09a//ZPoRs5WYldqlGgazEX5phLg8BDro6eYCPHX2C8u9y/AFlv1yD4kZ
tGwsHC05qdi1A8DyZ51XL5kC4Y2NwfN3jVuEqSmVZhvFddAQ+VSqGmzZnSZFeux8
44kS+7l60z+euczDUgCFY6nwkzF/QGnzay9SzQenQ2gfxhIZNe7I7CcY4l6vZRnz
yhb8GB6fKi8Ynsl981XklOWhgpOXf2eaie/d+WUDmpIVuH/sCc/qeK3WRbXSf7RK
0SDEfcZPfKXnb2XwcMTesnJxPILvurA+IMv8lEJmqXeAMb9S70HsLMAossojFEHS
QQHLS4hns6SLjSsQJDsK0kCrFaAvFk0UNd8bmwu4IcJcRIyL6vt6OQBhZ3kFvsbU
DFBmbVL7T5nHO8hOt7PcxGT1
=cUhj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '38e79977-f6a9-4f00-a45e-6fc1cd218a4c',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+PeL/xdGsI3skLLDWH8PZNHCJL/nL+GIHb4JYV0BisA8l
1tP9PQ7Sm7aOsFSvK1ItKnQsmY5+fxifnmbOHw0pI0qAUrb5GdjGw0sqHU6UHdNA
sVRQNIFELDGqewkzUKySeafL34I8tKIWCPgPioKHo2wXLyFzDoaK27ooAOF8BFky
pv1oe8XBOXwp3S03Tq+hiiOw5ATIyvIeU9Az+QUO6iWCO7EeEYomifK4gQXD2pvA
acuagpTFSiXy8fv3lkNEKhiDrOeF8ujuR2gkAS4afOkm8j0+NXnF3xhgw02/6i92
xUYj9ZLUjwx9r2Xo95Lf0BBj1v14/sL7w28Iwa2f8+y9OHs4WsYQirSyBBxY5Rog
dqx80kT3DmpVSyWpxC8ygxQt3UYEeQH9MD0InR4kGNIc1VC/pu+Uu3P1/tPeRH05
GLJBMFFzvyuvel8zUwSgHaQENi60RAhqQccMv8Tc1YFTSZ8gf5ZQNvgSQ5funJkv
aRRwZ+2OZZEiF9R946T+JOTvdaWrbv7bfRdq6ZwkZp/zrZL4+3faxP7hdA8ABQYV
HqbjcpPk4KXXB0vA0HQjcPdE52E4gVpfasNRBF+5pRWUox9e0KmiA+Up3SQqRuZ9
Gra6P86p7VulMYgX/NIWZya4j1bBhQxtpoOXtt3Wi9ZJB2LtxrZGSjGhcYWpCWPS
QQEcWRVXZAxBIt9eh+F9HvBNCHc5rWV/tpddgmaT1cdo9/lxrQhG6J7bs9YVDr/b
2xKvsFTCF+ccnyHScJf5LVFw
=H5Xa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
		array(
			'id' => '3eabaa2a-9431-4b2e-adcd-d2b63a9eb2cc',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA33x8ZjrBd862RThOgyUZSiL5N9Zkbc0z565DBwbySqHP
CsP1FmZHfZvGedSZH8xhaCibZ8kkdf1bRLkLjyj7iLQZdkDgtmD3AbH4fsWlROIB
OFXUKU5gnNy8gxeT8vXM6bYt80LpDEBdY/UxVEpT7NZXy/hZnEmaIqrvSU+WdYDw
tN35LWu2C7nJ5fKqASl3JZj9rS6ZkjK29rIz+c+ThC5LWscSgDeqsyKi6+X4ANNq
T9EsfV3aIWuyy7oXlMWXFC9Wdp2flhFSLIxGng7KgVN+8KVKJaYWeAh6Fr/zcoHE
xgmFcJoh/SDyg9kFF6jHIgNky+e/idEoitzUGX4SfFhOJtkz6ngwVuvyQ938sqrz
iXC0I9JBdIC0H8l6hI2Nbcad9KXYjjQQwXWogojWJjZKQOLCGbYaQosdCPbBjvGO
CcF60ICKUAxPsS5EA4wWSPY353ry1c/6WqhFBWzv8msHIQUivIpQ2yMEH86upeL2
a78dsAz7WkoTcXcyRqNMScwd2b1MubstI53zL7xmS+I4WXpI3KMEuH1ArM0WEYdq
ek75+zhi9bMZACuT1K2CEx3qp2/2/7ame3etqpjIdOUyBv2OSoF+ffUH3u5gw7zX
DdtfVOQi0Ilfb6RxqNjS7ZrHA6aimXE0Kk91xO9GD2TPIFE0MCRYUWim8PPVKOXS
QwFXFP2Ax+f+GZoLTbdky/0+eXhMjmt5byt0fCPGT82/B+leqI5DCi1H3JlWgjTp
8Y8FQnE1ElDHCZYho7J2Ngi7hJQ=
=vfW7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '3fc1de0d-0430-49ff-a674-ef327e3cadad',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//bifZ41Hew0qTwRY1jdCYdgg/yuE6QvgEOGBMozmwZAh0
G2vc8d5K8a1T6yu/ErAoJoosa8bV8scCESS4xVsiXvISowoZBLW1JSqef0ccKxKW
sq4oIgR5QzpZHoIXSMtww3EHfMcdhiiLKB3BEJtD5di5QJTyoKlIQA6+w+mJUETR
jMDg+OfdsSAH/++/TUK0asVtaZKb2IcIB3nWy9ZT3OlJvkZzObdlkcnr0Wd7qqwQ
x2ukgl+PHCFc3gG4egO5WtGTdu/oyyfOMRVmiTOXFgv9J57hM1unpVitE4OiIJ8C
Txny9h5XlyJzc08T5ypexJfpLSJn9IA9Ky4yTsMj5a98+3a/lCfH28msrkYJc50E
V/8MGhVOZeC0QYtMPL3gteBMzMClqyGOz0VyaWBnDXFoRlDlSwm7XwTT3FcE8iV7
k4MnZqIPise3UxMLXGS1X4tKgIZ+SIVR8FiEP+g7Qy0nuV3meae8PA26wr+RWyzS
o2VmPrhnasiLndq3raWt7w6wGSb0epVS2lnkBsFkgpgseLsDYnaVxNnJtqRsaSvh
U6YVvpxFfA72D4QT7CjeLyQO6+/RxxSStjOhSqCVbEL1BcbYzPrRxfyIqPbiN/Bz
qmBUyqIbGUyRNgRMuhufdEeVHamldqVEzdyjC7JhN+TJgfxP6VRZ8I7O0g9HgtXS
PgELIJ2o9tZtT254XASpkXNqazM+IJjbT8mdjsP6HlRAkZRqLtuEeDgHQvCXQkAd
0HMVXmxHo1R1ys8xjhq5
=WO+U
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '4495c213-756e-475f-a7a5-48b084ca34d6',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//VSqHLmdT/EF/Ufwew2o0LLFwXw/pf+YKOXxsu7ROwLxQ
z5Vyo4/1AluI8gzmGCwDC+52GoLlKe12x94eMMEiVIPtBr8O6QNtq4UPGOrl7GIu
2ls2M2Gpp2zcSv3hGgYWiLTtQhAtc0D7Zn5b3lSey5kAB4UoC2oxkvrVs8OsDBeN
CJDD/TxefuNNtiJxpgLDtt/N8Wd3VVSPTswJdpqjAiqndJv2OaGbHrQ2zFG7GTDe
49gSOneJsUQuO4czV2NAzx+/9PFyQfjuNTYhaqWyDWYRJvYxvSv2uCWSwd/0YsiT
/iW4562tXKCneaPg4ThsXkwvRHmcMJn29dfpQ0cjb1aR9b+PlK2GyhK1cfke84Fu
ol6kXh+jRG/KMSkHsSHPh6HlVwqeX6/8eOPHdxFQ7nuCr08XAPpoUl0iTEQsq7e5
S+1v7w/m2kUpmWOT1vWTuTxZpJa+jmydsE9S2E0VuJ5qWCh4gc8Y9oFhYfgK78PG
Ol8CQAk/3zx56nXG+L62c+ElAvr9y677lcESurkp4Y3fMUg+w9V2tVVxi7rjow64
BK8w7pzTYp8hVMtByS/Z2mQUAJTPKZk5JScHM6mLWJSejwgZVombXcEk4WhnnLt/
NpbTW2fbMLjuMbekKVF64SgwNclDE6yu+d/E/lMlSWcPjxVtM1/99MsCxkzj/MTS
QQE/IV4t4qxuuj4srpdXRVb7JxsJ/DUE7eWgzYpvIWWoxuQ6q2TiE3MOmRkwJY4Q
Eho8IMpieL5zJfDT0wRoiHgf
=DDqT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '489a872a-8e14-4b65-a5ef-487257706dee',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/8DJn5vRuqoC7f8y6wFFaGtDl6rmMqPjw/rV4XhJbDHLfk
fdBpRODtgm1wJ5WoF+fNdV9ztDMmYSkJoLD1suFGzGJxQtzwC0yz00mHSXXn7n9n
KkOKwjIZq3S8hA4fZ8bFk2I3bAIBjuIC2INH/bs1kGhNJ4qR7EY+aBlrBha9sRTW
BhUomNuufetZM249oNPoj5tK2zj9q+XR8pDEOxaWrup9LZvYEVOjjR1yw94pvEuy
9fO0DCgls+jYhS42cZAU8QIsNSANj9e2XIon/JBoMpAwMrTKZDPYDOpmckhjDhGd
6fmOTLXfLpCf/ahBLPnb9FhreBL4uJqhgMHRViZc4JcHIJUYBcDmO22btqgZvtbG
UNe2o9BAmO640qIU01BmIjNQ1aRPGdZHny3Pd8QKHvSIj+SjI5x/5ZzxBnUOGshp
B43Zf0FS0LebJWHMCjQyPn+xVFjaZ7YBTbQ3IoAhBaAJFzC02St8W14birFzn/LI
P6OPc9H8rNqod2BWm4RbZsUOU7CuP02LSPea7bYfaxnJkKP3dXv2AetiFEs4ja+8
YxJ4jYkrPsIiHF5pYfJ/51azOKpJbvjeN3M/1TGgO771jF+WrlwwImaybL4gjl/z
ddeNHmoy9a21+oXsc3pmOjmZQY9NVV6hXloB1KTSqi3c+42I1LbKaBTxxgpqbfLS
QgFJ85ex25Nc95F7J70ZfFwvCyfYp0pGOF/dq3FRlnLWYKGi9qH+nMRlxN5V0GBu
JdXZrUQWHBwwRRJ/GYPa90kAAA==
=CUPp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => '506f5e90-6c7b-4d02-a41f-4d856d3707c2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+LI/FQXs91WjVbORtCsUEzngT+IK/48NNFFRxG/M704M8
nEZtHhdvq5tVWyqy189n4Q7gjlzi3FmWvntsNKoHF7l8bifc5QZpkMUfOxzywRuJ
7YXB1dfzHSdRGCZtesgU7tVzAjF/MOA2Blq/Lv8OXHYJ5qtsAvstAQzkGN6lzvRV
5SPVkVJKCokTtnv+NLsqetjxzVB+Bd/MzT2+W2GYmHUchcFBNHHp1iGyCcLLQ/Kl
FVDNyTN2WjkMZ/7iSQryrhdj0UKtHaMkW9z+TWG++DsYXdtv9Q/Pyky+eV/JY2AD
9Yx9RH1uwFgqojN4ZM1EZ65Wwr2EIzOPCWqLonzLiE1959OVmoN3tnPdkH82HnNF
qnmj/FFo0YUEvg9iPzOYXNHQDtFUwLNHfddbqT88B08lgR6MfcInbqXskJHOTk5Z
rfqzB8yC6ZwgYXmTypjwnYLOafLOF8B+bsbC4kUvTjqqOCR/OKtc/lY0an165shD
dU8jZ2LSxBnjCYYWsnw2Z2xTrJkqo5d9NGb7hkpXwSshASohTOwSWDwnvQpbT17z
7dHgZeotzkuIvy476meVNja4VnE6+UT2IJQO4wW66hbNgTMXRdkzFHD3mwvJoSEJ
7aA18goexHJ650P8VtJofK/9DUChnUb3d8YLrU2J2TrVauKknVEX+fFEXX2P0zLS
PgGqA8Yhq1btnhg/S18Hi4dS2Bn7mlLs1qwliEXpkNDRYYcXCnoimkDjpT46VlAF
FV16DucI6VkSKeu+xRxS
=cwbi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '52e8bd36-ac09-44c0-adf7-064b12b06df2',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//R7TZeafN6+k775PdN4xfII3uyCsbNngGtHlZIOebS/O8
tjrspT/Umm7HgBXeVu7CaUyyVw4LyRWlRay3fh3MNC7GATfZxgDboH8bcMtaBi7J
nDLE5wqvw9BoQlVoysvNCvJnzVqGf/mGaStKuK6q5Mch/Vd5E59AUAGazf5Guu+s
JoVNjJ/47FIHxj0pjvbaDuwQujvUqp7ore3CIblbCvxc9N3jLIF31eQ4CAHJz+lK
FwnuiDUDBN37q0LP3j+E+l0C84SV8RFk9mCXv3hPs4v3d/ryR/p1WjrhzhwbCnC9
s3lBX0Aocxe9aNCaQGqXD17NWTHQRwHeJ9hBGFMMsmdKrEqwrPecGth0W4YCCkjT
GB8g0xJguLqoS6W92d19f3FMirZjpK1cQ2/pQQlKCUZqkke5aJG45ppEg3QNw8rA
eRD9YgtZuYTJ75MVz42AsJ3S+JewrwWIq2PqhGIxN+WV9HQUxNnDrGUxuxVDiE4P
L80HsWLS0Mtk7dblYd2FcpAeJBNX14ekSvJlQ4cVWc7kpU0kQqsN8FzPnh7v/ALt
lNdRXZkguEHNMsFZnp/LimT9ykPhA9HbNckyLExnoi55FMIO6mUN/f4ntJBzS+f6
W9kFqfTi7BGKTAMR/GJom8ksOuv9kKTmEpsmijIg6ezlSN7P5iKVszyu04YrLKvS
QQGIwO0s2OHSS3a/3Gh7Yeu+hTkjkOIFO/KFBzbCOtm0FI/fOynTYR6wzBM9F29+
mhcJnpa3pVjRuZWeDtFpKwMl
=9u/O
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => '5a51f622-1d5d-46a2-a203-647a9da9a3eb',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAsJGsX9qWEM9UpDgM0WdDEt1wKI3fQeCZQz0rmodkesK4
K7EJq1VTtouCC9ESgP312fnz0Gmzds/gOpDIveH9J4bAU4guirX5l+o5Saofaumx
8WZ7OjM100OFQmOfzFnuZZEwEhlO+Y7Xu0/xIPvzuZaUqStT7yW1h8TvYANGBVr8
e0csFRV6NXkp2ZrI7dSitGQOi+A68EqshaL9xQLbVkDKBxYcVSVFaix2maITHY6O
H4pvgR1bJvSSaZI3CXI2JvMYDpkrX9RlzjCLnOPMwFSPLCqeiTusbRPSqFVyCZiE
r9BpbFIm1ul5gjZ4iAF8xktVq4CfUx3/HviRg5YLeh+2a0XPDxx3AlBoR+4dISCh
W4YZN9E8s5faT6FXxd0/jh1f4Fdyr9tZ0ElA9oBMMDHERUPuAF9ZbPMOcFYEgvre
TfOSEEEFPuJlu3c219n8BaSDJs8s4xPuW0lYPQTHRQx7OcVFEbNVrEIqxht/Rly8
y+APAbGbP1fstABi/gqiXEMYKP84a2BDSGvtvuUbnb9Lr+EyjNteizmpM64t4WCP
rsAqxOC6pUowIBBbji7j0dAJUOPFyafxbBFHYUiwHkRGcFeQySU1pFCbHAFZK4vO
wDJaeak4tlzjbW0g0dDWZJL35KpyGBvX53jvWYY497BnMZzWq4iXMWbgQ2z1YIjS
QgHGkABzrs0HzWihX7I/h2qtcGdGkdZD7YEUemO0czR01SqcgK4uRi1JN3/dE0vI
vcPH70U+/HgoBWymuaJsQFZDcA==
=uINz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '6a816d6e-5219-415a-a3c8-de2e537e4a17',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/8Cplct01UfjjU8Hk8IPj3/Kwf8XHutTZGbYI/scg5YN3V
IZ+CS3DOb0LP+IUJGtjS16YqUBqcoUgi784k8RL9Q5+2cQ0R1SdZP3BBVkIDoz6Y
4TqlOtl0J0SPPD72UEnzjGNB/GBhTWvQgp2U63sCooDAYdDHyey5K8FTV8cHTww+
0ip8gJjNrnHKteKEAX2DnnBR2ke9zBLWZpgVe4zJYHxsbJ3EIJnkPpFcv5yaxLXN
nRcMLug8VKwA8d+wxvhJ4R/BZDqenOFiJnsNoC00ZlC9HFqyjhn/0+xwbCAzRzZG
2PyVTsn4NvfVvKQqdJtfLBa1SIl2E+EpcpzzHpkhwttyafeYgzk31PfrN29dVREF
RCR9/mSoAVx7vCz6BJj2HO6/rbVvnDqx71hlwfSW1+gu7guEWifxExqfCHFZQe0F
IqOJnvPi5QyzwvULYT5MuMrZcO4FMeM/EmGxdI3YGHdH+WT1eRvYsmxTPuix+MW2
lD+eyNit6nSZVMQxgpp5W5b3Uc6jrg/wTPTOxJKpnxkSyIEbopLLbM3Zmio1s9e8
zg18kEMKqpQyPzs/KWCa4L/vsllbA0YRsKI3zMTIn1tAwXTnfqCpxTZ8o8gBJQ8e
zSxm2PuuKIJImqKYofnG3i1myiceIseU09uXnWDnTMpy9tSaAv6ItRgTD/mprQfS
QwGFsz1AJZW6BXk3pPkVeICoXrKt0eC+cKV6eKOyscJBd7ZiD77ThGemmVO4REFf
aX7oZ+trHMbn3eV02mcAGZ8n3FA=
=ozVP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '6b18d77d-0371-4d66-a02a-e60e791fd705',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAujNF50F+peBFeE/kPF2Bc4W6YfTu85OZ4SMGpX0+FdRJ
ysabBa7Md3a6Gnaei5eEYwPJiX7mt72gGg8jNSj5UBLQtZT5oriy9VRG/8l2G4dP
8Z5eTqnHAVI3zIUGt6WLMBSmUv7kWpSDI4b7kCTf6VH48m/oCN0IP/5eaR3FTHH7
qyhjl0ly0OU3n3UhiWhO3ty+/NRm0r6akYI0kvoAl01H9PYEb9SjLqEMBTssVcIz
Xz5+bAfTonyF5rnF/KecA52QLlJma+tVrSFYvIOzg7WQ1p6ZUpzjsz3cddG5Hz4m
Yu+pGRxWIW9Gokwtb3X/JhXdqNnuUtcEHwEfsNBfJ9n+MRg0fyag7c4lzDA5Kg/H
ZphysWRfAqTqF86ZpNX6Kx8uNZgQAKoAUbM5rgupw4u2mJr+FaE+H4fgnKkskhAT
EdPXEJEGp0H/xK0ABhvU0+J1koIRIH9b3FRd+NAlBs2qPRh2FCUntDohGFV3Hxam
yHsKRlTVmOI4j61xfWfPbdwEnL2CsFr32I6jWKXGpYv9krI1hh5US1d8ISsv83yt
EBtjX+ehLX7eSod7byV56L03GzoMVjQfgRvSYZEMmdzf4/GQKFlElUhulOMlwd2k
2ertNaQ96v9/boABijy0Xc7sqr7oXQkOvtddmovMUtIojuSzorctvBwkVSPRZeXS
QAHhYV5ApHbeNlOEIJ/37mYhvGseRpJrDj3ZNoJCKC14pxtHE/o7M5euD50ckys6
8JWsvU//ALeoLcmKXgouKQU=
=q/r9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '6f28586b-c4b2-4dea-a94f-d3d15e17d735',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9FZDv4wvNwB0bX9v8MpweKBnD23LTbDnKR/8lUjDsfSkg
Itx3L8jxmfHW33JW8w6Tfvezx0ESuPR88lQPYvr+JgEByCLhvrGkIXarH7yGTNyu
ajw0A7AQGwSUBxk5d9Rr+W3EoE7OG8WfOG3mgUyjmMf8Y84moyBUhq7t+1YXjfWX
Q5zmGItq52yT30G+Gu6q9tFv/yjxDWCurGUrYgiK5Qi8vmYkUZkYHlYUtKql3dYb
NFW5vgYSIyJVBoU51GPyxhJ6iDmKSaH1v8Ac/ytnpUEl+AQQyGYD6dUDl8DVGDHk
CqqRBNZ83Mv53nW8+Gofby8TbobXxJfp2wTi/yPSzJO1ZzP2r92tVknH/zi1JDPS
t66/foqusF5lzO8PLzEaAzWg5S791oHiiokwq6rdY5W5KivHCmal8TuLeux2BaL8
MdbadzLSKTsq5fdDSMl3yzyWXp9mVWnhCuES7JWfALtUDi3dBrMaEqgjDNjgELQv
lfAvU7kXoRCl7mX8kdvPo6TokXPNTreIYRa50kdwgSL1V81i9M/EPWow7PSKxn4C
ifMUY/5QZ5lXlmZFylM4TYM9N6PeQKvvztF3GECT37RzTtimia0xyMu3yaRLz+N9
IMMF5bjJo7U20lD2uwh1CSXJWq/K580jx/2M2mNMfjN6PhJLYlgHFkrG8hfrxbDS
QwHMusTsHrXRMFUNJFP1kn5HxcDDJPMrMafQUo9BHmPQvW8mci13FMaAukOMiOi+
sitPS1EFF+IStD97KIpZnTjPNJM=
=ULNb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => '7b37eaa9-2378-4a94-a854-00fe8e06884c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//XdhbQoZk5JSGAEkJyLtXJTos8KMv3RXQ3xbdv6U1qGur
ZPU1FFYiV5x0weYViCDtRr/5JyOjAd7E8weedDGpLd6nVSH8SQdMhTaIk1UpeLck
fe8Lr+t3dUTQ/zT3fGF0r1TcYEbKo3hMiCHdSIqtoe1YAgomkBwTS1oQON0eREtk
v8oiC5uu3aaRLhrJe7U5fC6nAhYtb+P/RT3Or5rk3isutUEzlOo4LsaWRzqx8HOg
l3v+WMxDYHC391apjf0pGPtfKKHepCdEV1I8LtndDkYPRseXrYZQFcoPvx5oq1hv
pGn8Zz/8LT/wO4EC/UY7DsLR5Lbd3tQhQCrgJYQMlNRquWWsh8en83V8eoK646kz
BK5UyOWacq7ZUMjajOI7hj4tRChL6XFmqZAz31zE4P4DwC6H+YlkF1DwvErS3hz4
wdnfsHMRLDlAotW9Rlff4UhYqwxIbGJs1VAQ7LIUQ61Mdu86RsWcSM4QWBP2gqfH
CJFFrpreCZnVyCJidyg7wFlLyQAYm4ESYccxeBHvVmkWWB0zQY0RXWmGX1XSx27X
/XTZn1eQcz2FVjASBgmJcb5u0OJmDl7bST60itjFPk796QjiUGY3A9yVoZXZaTU+
heVVydd/2VG5UdOWYh3yHj3tSjLhGQMMGJ7agq16V5X5pharqQu6igRwnKWbNN/S
QwEfksByGYRvZf2BWTtCdcO9raVe7fNCNgMGg+N0uSVxEN/VeS9PJl+cN3Gkgjd1
R415ga5fEGkfHY8XfODQ7w8PUCU=
=USYL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '7bbe462d-6b66-49d3-af59-8486dc0ae182',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9EfAF0eOQoPGDQGt56oaK/1Xd64TtoUbJpEEIxFs3acqG
EZAhCPVSwSjYahmSA3E57uUoa4Lc0elGISVzSZ0Fo6FWYNPDc5xKpomiC63OlSW6
r6I3l8Bywo6GvamlQb3i+Yo4EvUyP7xDoNmkKS1d8eI3PuiOlgq2lizaAh+W2tfK
ZyTlVp+rQqaGgQSjL0a6yHswKxhcRZcYOuQjB2OHGw7yehiOtQgCRDk59reIYqpU
B4qVsM9Fz/pClpm3gi0vFxEK7/l99LaIXuoKsIlgu+f/kxvT9U1rQ0cGdIGibZKe
Tmuc2wkYdmAgxxQV/MJCelBRU2PwvCRrUfTDjA1AEhQscl4NON9tPG1SCrfmWgvp
Op9ouaUgXpz6B6bflqDFTZgWQumYxqk9xi5IAbyRgEsoceOsjOhEFYS5NQEiTOkL
kKnDKV+csjZ8rnfMxCqPzhmUlUQ1MTSGvbaUr6PAuLad9MjUaHdSB3GRJH4Tvx8n
RQj8dHEn/bAV3QJwCKm1mz0/ouUm4bUDopTcG7Vrwvl1YDMQzqTVA15QsPhfFpJJ
/nqksJqr+DmeTGwjEQRwXZFb5I+Zi4xahBamB+9hZMmVDs9Y2SMEiv9zvG3GdU+4
PP8FPFAvL1Qnc4sTYlwy4E5/uwG5JVNTNGCMcpQAbm2iOd5U5Im/6AT9t9SB61DS
QAGhlreJ+RxrAU13dy9muyhjpTe8f6blsbh3IuNM+5vwcKlQPu7Ec4p+7gc4ELu2
wgA4qfsGW/gGYdAdurcQpsE=
=DnYz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
		),
		array(
			'id' => '7ece0279-38d3-48b7-a80b-9b249eeb1346',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/6AuDE/x54tylJovgY6FkqLDwkqr3E/bndDx45y6tsD9JV
DP9D6vBIxxSJbTxPzxVr4xCuUPaO0Kmq4XjcRozUZ12r2wCQT2EJmXRQugiSfmEW
x5ts3W4i3y3Olw54cKFDXnNlltvSlqPghPLeKS6UhhjjO/ngvzYjaGSOPOcfeySd
8L/SceHXV71lTjfHVFrdXkNqcuLIVkAeQli9fx5AXBRr1MoWdhf+Gei7T9kBlJUX
l+HvGjwck+zQtzdd7826uJcWTAp95tFJeZ1R/U91PzmVsmkMmphVCRUVyq3Hiikq
reLy8NsmVRG4izawovX0v4zo6tEGI+i8tZlljZnxPDciswzaO78DeHQzeHjQMMn/
TQ7F6wAKgLGsz+gp7aKe518KMdiRM/mk7llSkOYCzSh2yISRmuAm8uH6/yHOLnwB
nknlS2Ud+OJU14gaBrdzzwJ+WNMZFuABdEDk9lFP9jffmSshuxSl8spBzejbwa9s
8aPvEutcearhbKkwG0Ffvtj4s2rEx1fb0AEdkl3tJDKkpzbtRRJTqofxsj3v9zFW
JW1ZJN+GUMPsndbGtrR1jIVSpvtkWihjvAKMMhamr0ue99ZoRaM8rMfst8g9tS1i
bvl3F6nhI7Ls+c8JLTUUBokqB8pd10Bx+oaTq2tiVCeAl6p1FeQEIQsCP5fcK+fS
QQGRY/pZv4Fxx8w1rD341LinxRCX/eeivnnaTl99S6rFBca4CppjW6xcFc6vsr12
aXxKUSLWChkHMFRD0oyyJsY7
=IVCu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '83c028d2-7122-43ce-a5cc-1256c957c6f1',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+NwCRDfOKfNYRTkg8N2ERuZpBHqXX5SlIdl7qSiKvIEZD
ziS9p9cmP3bgefJ99BWuICawr0YuBIWyJgNv4TOFAguFFI7nCKI8e+8DOPblfT/B
u3koOsRhxYQwJPhdIdR1HZ9A4OcZNmN/e09KxlZmXl8vXLd6VGkNzyeA6oyYtshz
HyZjFLc45uNCi8Hrw2wVwbmsMLjnb+1fSY9rhwji9RM517c/0ZLf7nkmcJGiGHrH
TMsQSACNFQfwh8z/z26neHUbH700S8MIL+E8P2P8ezTG/6vQZLHgIK5Z+/LQpKWx
59LKn11xiqXGGtzvA41iS+ceQCfn/+ZUnP4ID3TffNJDAUU6W7zIhxWBOMn4Zj4I
3flbu0p+nEtQRMzlokJCH56wQY9jgXWdKjpKVJm9EwQ9YkwWv1f0qZLz3n31mfWz
2dAtww==
=6bL0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '866710ef-6c1a-4fbc-aefd-d658717fa9ef',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3ARAAo4kRuudHyJnhGclEGZdgpT8BYuS1sB3GCSFlzdGThhLt
dmf+v3FZdyDnyL/l8CEr0ULwZln5oaxO45XAWJmNBa0GiJZ5v0vyqZqp7mVUx+xd
fJBMEiMCuvAPWTdGNAPX1fDdOAQjI+eR2mZnVtb1uiybtnPgWDKyKX4mRfA8kPLK
I+t6nHPqcG/wiBdAYxE5s9aqZ9fCSzhd1PWVOWONvCjf6D+sGv/aIEerEE4IlvSK
IMXqv4E657cWr0N9I5YvEFq+3vpleXU5xSRyMDc0bEppKExmOhIBPjr3mhKDuSsn
UREzjQi7CtYY6uFjabV8sEN7eucfUtnrmaw3pY5Y5p/jFZU5HYljNt6MRNDGhwWv
eiwbPjHbv3/YjoFGyaqblApvQ8c4kF9GVK0hULBTZ6x4Dg3AMvultriYhIzpGIRM
yB0gSDxwtzols2D7cehU+zSeyftrbee/lanIY+SXT7VMfuytwvLyxdhwv/s9yB73
ocox8S7+CAHIW176hncOJ7ac5+2NL3RQidGZKdVX0nxjsRcbs6CFLRizrenR9c/i
eLNgjvEo51j8fCbg1DpUV3PgY44rCr3ND62ALl2WwCHDFVs9ArMiaOT7IxULlgih
mPkL+kYi/Ntrn0Ok11qh8LWvPwPqg7YvUKb2orJhU5IiMpiSnYe8ix56O/t2QxnS
RAFaGB/sPaGeVibjXe+DrTx6aoodDa/+uHJ5toSGjPa3+Uu6pxuV72xBDE4bGSTV
riOs1x3DRGuivw1OnZXzPzvlmVrL
=4OWC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e'
		),
		array(
			'id' => '890da342-edfd-4a28-a7a8-f99c7fdba30c',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAlXiTx5+kERZSzgvjpUOT10Z/WqGPQQNgq/NH6RmcvOv3
9jtHXdxD6fy+vNgnxr1dlhns7xoZRq8UZTfI/KRpLksoDqo4f21X8zFd5PaiCV0R
UJVOiFQBpDZFJAUSWOEnm4CtD/4tn0hXRuRanouH70klMImyMXvX9kLNNUm0wbBW
tk3bWu7AnlPnXbT7ECYL4LqQivXePdnmilWo0Ygic4pBW0Tp86wjanmWpBuJI8qw
r0fLsnqym2z5+2SIUWlrOv62kjoeTaGR1hWyypyMPgBfka/7PaGC+tj1YqUKglKj
olYt8KbBZmPsMDFUVQqdXNOkhO7XFEKLjqwSs0iNOdWzNvwriuOt5KWbNHfHpfHy
cKwwkGiw/g8YCc9jZrZXcpNV+R82AGjdwNRkbG7laaeQcBFHb/UFzMBuTZPPEBAh
NNV7g8pJ/Or2KuMis3hzL4Y+kUE+okqLsu35m7t4r8I2vZXYID+FymZThBhPR+vK
Gt2iYPVCXr7Vs3bcxvZ1CyGHtO2HDJU/xRMxWTvvT0R8KrkOM/qkmai+234WPATU
LI4xUCcKQmhg4BKrCYgAgDRMuPFqsQ4iqz4pYMXPj5BJirfXWlW3koSHUhM21GCW
uQO7zUGrBby8VqQCsdzCUSCm0PPJlm+amMCE84JTXE1B8saVhivQtAC6jwXRYknS
QQFMyEVydrynZ8XUekf/XJWLrTOHT29K9XEJXc62sn/hxGZ8NK+LHET4selMM6QY
7TwajcWJIPwEy/tbtU7dvPXy
=h2XH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '8ce39d5e-1f9b-4765-a392-7f5a24b0909c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAgnPsXaPQBAwe8KSVvKUwd3Y2rJsjf+ZAOLJizMj2wmTk
5YU7Ein6u+lef0+NM7X4OFwaM9iejlXShkElNNx7K0m1MDd8r1LS1JTZ6xxLKAZY
TIiEBv/jk8eFFT1kyurt2y63MXuGv9Qt4Zru/sMEKzGkqPAWnKX8NTtOi/AtDebN
MVnrY1rjOmq7XdHfuAWkgKnXFuErNW4hdk64G6EJK/1bM+/iK8mbjkSLF2BOmu4u
LVh3TXYNsDzHWMagAbe9UE5IpaO0EJykFZJavlCOQZRPcMpzP+5/M2zKqanvS1Rq
5/KgtLOnPnp7rhCy/Hgv8ALaUBHvszNq3cL2hfRKaudZu2EmXmetggkY20UEfBfW
cN5zCDdKOLnZ3rWsCo7NtDQcrtVqgIkIg71+JPuz3h/77dOVP809ZBtud3V4aNqi
DRx0RtasL5pCcmOGAZM2zDWxYnNb2z2yjYeqgoGNmYl1gclN+6YoaLFzmA3cqWwo
DptkW6PJ4qdT5yP2ki1EGC+sl8xPG3MZ+oEbD1p26KNzYA2L9/xGy270XUuHUn5H
kDyfSDg259w24G+r06aeeTIs94Q19N9adVHMxG7XMEDxknEP4BOXGB3T1/HRtugZ
XhtL59fJWliMaqVgPP1kl1UxbWg0f7r7UKFzXyOPIS3rLFQUtgUmkGoJIqLHC5bS
QwF3J6M3eWOgPIhhV80Jwbtf8RUYm9z84M7p8AfSLpHeYXGl2vfTM/zQ0Z3aAqtG
2gL4TBmdkU7EcuNQQ0bwkafwcpY=
=MkeF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '8e7d3ee4-0e33-47e8-a3d5-ae32b79499a4',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//QB0rvUtmtorFQ++rBDdJ99GQXe7KwR2FqdDlns38UfFf
ABjbx3tS6uRApamBxGxS/1uuB6lShC8h5Gy3iT6g5gg11UzU7cn7sf1oXIbmX8H3
/vKvRf1YZ/nvcAIv5LuMd4iUtgxSG0wYiXh0w932HfRirmRDWU2YPwXynPTC5cfB
NKYPynPfNJ2CLu96Cfk+m0iIn3plx/z3B6qrkVx20MG9ccGoEpqnVz9NTRzkqm47
A+vNNxNvmJtnBygwcqMvk/6FuoPLwP0rQNqxoJclS+unGbH6lvvis8zjD8V9kizv
ZJtMtxyx9ybn4eVeRUrkAqrR8Hh7j8/0VjGyEhlUlisp8+Z9Lksr54tbbm8S8vy9
bXZHMdPOzFckz784P+8/g2W5C9ZaUQuVqgqrWNDBFLcI9aRb0yI1HBoVwhq2/vtg
HJVdOkC9tkWEYA4E3jCEz6k3Wt6BkELLoft79MC/LRpdFXJI/akIecXBtW8Q+A1g
J9EXvE+QoEOkkt4dqJoPHt6kgILkFKCGMo7tCS805a+//RXeOW+STX5QW5VjL0VY
Otw5A3QTwgFwv48k4NRlsF1bMms4N+StLmB4zcq0kxxm6kKnjpBNJwB/Drd9mhBp
ZFfYKc9suyPOJUWtlt+JZ33riMZxIcfL0PjDcq0AveyX9rYAfp32XTZENT7e8HHS
PgGeevDBw0P/7PHZCd4qBCGB8VJLA906f6L/ZMEC3IS0w88uwqplF2sYzSxQHICq
ocqtjKdSh5NcIdVPMLEk
=6VYH
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
		array(
			'id' => '96ce0b04-e71d-4579-a5be-7c60b1d06db5',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAggPN5sibx2JFoVjz0YDNE4n2YiytbBvKiuRY2bkyO9Tp
tWi/21edYwu5owbI67+yPyeIyhWx9KLo4tExi0YA9grb2buK+hOE8O7AqAhXlbg0
24aUkaKflmRH0Lguhc7EEc08g+coeZi3r93pHlv0bZE0v7HpBT2u+esmHb91FDO5
hNchUTteiYVYKg9XKmc9bpMyAS4WJwdVIweB7knf8p6yFyFv7AX3oYIjTf08C20K
PiTPS/dAQJ9aTebc0OQVGgKc41fGHNuyjE/A7FKHYOc5C3YX5dXZAlLB6RkJnxU1
1PePyKbWGi7yC9S+7EYyHiCxij7Pc6pSnY+B1bkFlHHpfjRKqk41w5pzbrEfx0v/
xctVuzfS40hebzrO04t1Wn9p9HQO8GKZXTEInRtvroc0KTqYAaQY7gWEthmSkncJ
BbcgmCYw2TTeTLgV6CXAYbLSUUg9bt0gK5+7algoHoONM7tVu0BCYrTE+YuReTsd
vTAj0BylmPudQKiu+Qh1Tk8wLDTCsBe1wwEYB+vB5HzYzDpmrv3FMbz7H56nD7JY
+MuZV0XH1TaxohQ4tDXngmE+8jITpKb+gmwWckPq5gEKP+eecObfK1iz1/1aq9X2
Kif6CKKW+45DVpfk1XTu6eGA4JRbqPkKUWLtQeSUvFOtDUQ8Fa0c96a72m2PcQ/S
QQE1sLY8BuLSCat7xMZYdGfrVone5GlnhnivMkLm6jT6Gfs8BvnXd9Qq1txNYXQ/
GvWdrBJ7agKnJOS5gy4F334k
=Ht8F
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '9aaa5e65-0df9-43b9-a36d-3cd3f663c579',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAoUUFLBrnzCL/b5rYe597wUwdHimSG7AJXcE11/Dq26Ru
G4jUJhfLi1hHxO7kfBvHTNTBVbZWc6wKl1CvnIOw4R/NapvpsZdmIWZ459amGqgD
90UXUv7HXbMIu+lzr1eGq5Vxks2PQO4rqDbkkqdeQZmEBO4v2JsWbzSVA34pn4jw
U6sgocwS4MO6HLaOiE8hjMQNqYHfv2qicl9J3apOC5K1jObaDBDVqDXFkKcwv8/E
oKfkOPAs0E4nWbkmb8FxJ8fsjynBdCIpsdTKFjZXGcFi0vQV8duyfDjNc6zJqxgv
qmx43IY0DCn4Nx+ArLODJTdxXx7i4i+YTzLIGe+B4Fken/Urhfzlm8B3w9DAvGCe
aGOWYY3PIYCcDqlrupQJXlwU7iVx2zX07cskNLQyYbv6B69YVFClzE5LgwPmSiRy
N62JHVlMrFHQ7y9JNkEcaenhTtHvAKxeTmfxJ90NaUVwXVqBjmyGO6W7/Jjym5aa
EoG/OLaQAIgXeqjf7pmiL+9eaKMPuDqgexjV+wjZiI1EHbQM2/X52sZ5XEXbigYw
9KCdmWG9klQKYmb88hZXVjrHChhB33EgbPBg5X/rYglU2+aJsmXXtRQ/DJrL3d4x
+/xCKXC78rKrxUpgbLA9Jn28YYmEbR2WX8KDL15hm11nrNKX4co8yrHBmct/hb/S
QwHPjvuHnaYNDWiYarXRZlQUgJ5P6uxMlLmQlcf8SGPfIbjcROf/t35CPe2oY1x/
x4MEmxW8EwBLJZCG9ltAlxEa1zw=
=2YJ5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '9b82e497-2d6b-4c2d-a869-d48b49f0aad3',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAuXVr4P6qLErAA/40UeBAZ+ab1wZ8PKUXaIhZNXUvoUuy
gUMggSDS/8toIjDG2LOIsjxjNTTz043EzcflM6rtRAM1CISRAZPMgA5cF+ia53Yl
YU7UxGJFsY0IQVKyeyEDuIBvCDmhaf0iHxsG8Rn8l3m7xa0C7JEso29aIlL4sZ4J
VlqrQsWeQjiliMjN0bUkHvhUxj70Yliu93zmkX41U/QQbItTl7LG1Rd2jERKa4Md
jGrvF2WXUFdEjrXUE6sOBlqQD5EFU9kFqCUwfSFHLIQiulxydPXbo+GOrumBIe7i
8M7gveG+PzBqOmivN8/mmUFa+zTnpVD0ibKb+FtqOID0MIYi8oQSyHLymjUJhzaa
xIhV8TBBA25M9tR1k2qvxsJnZd/kfireOqbovUQE/F9LDnCz4Du/B1UnlLiXGQhP
TS0bwSKPYahisgLscUSedj2QNHWmXp13E10xSTrWXFe3ZINZ3M6wsYWV/oiVvARn
+54hom2ZL1fA+i5qc5Mdqt8Xr5xeXvnqXnRti0QkBDnsrWH0Z1TQVJqgXfCknWYL
kMMTf5Z2886dq9HpNl1oDm38zXpEmeJNzfmCBJ3qT59MNJDiF+3Oz1kPezzEPtfP
AelLq45VCQEDOsAfCHytknUJJ2031r8awWiL1TIdS2J8Bsz4rdr8OtRUKnJZkWrS
QQFByDwO+S/pr9/dTS05L59YGqnAxGHwHSzZjNgzxp4CYvdSOQnFklIBKWL/CV1x
yNudmHNvkOQBEmLt+CcFhOeh
=i30X
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '9d754a7f-9e55-4a9e-a9a7-2485cb50d969',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//ZMqUOcwdb9ukS+XfqgGBjSI+1+wSLb6qNtlW6CqppgEH
d2z75ORkSPPGQUjjo0bjeAaOUIHAADnNNttByt9HIC0G8XiO1v/bSHsW4eFp81vn
cvc9Z03/QiL++A6o/c/88lju4Azx9c6S6qGUksyCchuagRj6rHAt8NXlT4yYMKEj
glixrhoYEtewN9YVVH1JIVsZtznm1p2QRpr2hWJBCeDwkagyQNmicAunvPvMcgFO
b1xscDTN/M7y7cRGTQ+1mYBUi39KBaDVDiAsBCV8cxFDietYKBq6YvtfjQOaQeS5
1Boq+b6nOEQSqQvmeWinimKSU3TBeywSIoXPPj1VIlNWCzyyKnxYHcN7JW8TSxOk
Ge+rkaKvtDlDpC+fpxmb+1S2NpWXYQngBfAoy20+O5KB955HNa53+RJt8kXs6elj
0TAy10uvLXWf+dcF1ihfg4PoR1l3pUGvqh3lW0Ppx/1PRJt0yX9nuxG7G/GhzC45
+vjT6cZ/+hbrFRtz/wkAZFkiQUA34GDyt3edG6q+Q65gsQ2tx631k0T1cL/xkHtX
gBz0/vBwpNcU2x+bqcyjbEFXRGrp+6MwZ/UbUw8PtQo8/XY4maWBX3trSZYba2I2
fTGXHoUfOgMi2F5r7q40jZ+o020MGZFSU8eR/m6hWJ2FyJJlh8kw8lqsTyWFjc7S
QwGjdyk11jQaMHer8M1Av/NUFvYa/OVb5sr4H+LVFvm+emUd6Jur78Ys6oQMnTJt
dJ8I5xosLGA4HSXHyk3bMC4tRZI=
=JaOu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'a042dbe1-9371-4582-aa13-443baeebdb95',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9Ha7zzmeB04YAkm7T72/OaF4W3VOAqGIVSWm2KP6+RJQz
aeDSlReEk94H+Gd9MZE7IAh+dHuUV4y7zj1LFn23aJAndK0nziQmJ7N8oAtcKHcy
VOGf/xv5VDWg/6kOu9YDOvNyj/6rZ4Aj3TTvcjwoWflzhr6+4MOXt5MecASB1c7/
hBfoWGkiKk2Z5b5xIza1+f4MPQaQu4PsiIlDc2Bpm3exvTlv2UEDPM8uOda4uWKd
brNsDQAD+KYDMpqSWiCEP151mtADPSfOvEN8w/9iEGeglsQTNNMAQiPSp+VtAee7
1RIjSqp3lk6HUx4K4aNlpgGD0lGsgfTr/Va0XroOQSliCWlg2Axb+TDLVyjRop3Q
pQYixwXuu+wmlyKcXu1kjUV3/oSFOXJ4l9RZI1yA0cd6bjUqDy3iyvCE2qpTVjzu
OihqdcLPbtIm2zEOwsJ9QP7IE3yufgNssj/I8csZTYPc3wH6cm+vFqLVI7BT87I7
5Adht+GM0Tuqa3dQL1KVtW9LLigYcQ4nF75EeG10K7YOvLrjNdoZzUWCDkeObZzi
p9V6itjoWzBJXyouaCWKC8Ftq+g43fJR7pMDIHcLdDdHJ/2g5BiNwNh5/Az8JI6u
bMy5cBgf8jXWN39HiZ5NwlnSCckBlb5RZlAh2aqGetafIfDeQMFHRIUZceCKui7S
QQFhUCDXY8L9Rrv8W2pLMrS1pGUqENdRNR8rQVWxOYCHiaDZJXpfPUkeO0Tx7qnO
xc7nYzvFhq6GZ4GYyFwbe+MF
=Hbmj
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'a8b29a47-3692-44ec-ab05-36e8c0a2590e',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//QNH/iMf5CYRUYWC3LThmeIhInrrXcW2TUFWsu+3oZZuM
QrosvkYLYo6jqc52qTW8cBQZzYQA37DN5E25xWBYz+4R724H7os9kvnQRHkdr9sZ
NDV8fl9OPSpINhv8aFXuDTp2Oscak03pKL0DAEdxCuRI9BEhgF7jKinWYhB5znhu
Y17/NyqjUiS/HypHaoHW9I9w+kZWx6BjAdD/xvm8XTy8JaH+z1T+Ha9ROofAl7Tq
qWU9GEZIm/LBirmm9t6yuMD7ELL+TSo1BormeifpiVSyX48B4u2RdpPbcm57y1PX
ux9luO+2FVsEtFkXOg3BGR0hviOCHpDlZNyu+q4P2dyB+qp7hgnNN6Vupifjpvim
AFeMaia0HGd++obJI8Z37xXhAALICuifYtvgZJ2xkTXY+amtQuCkotfLFZEn9JJs
WQLSzh8zPXNm7FMJJsHzOxJyBUQBHESKzbvt9otlfvLw7LVg6RNADzqNln8kB1Ob
MnZdA4yhIK+OpUSGOFAxn5tJJh66kz0pGRgpE63MfY4hlB7kGAI2wkDvAjRQMxOK
WV/YaGsXzjSmWpXGMexURmfJHrz3JKN2A55AX6BbGFGDnY1pD3OelNf9dXZ+TTBq
GL1+b8IlqBSsdqTQ5JqJ7JvFe7RVhXg/en/vFeCKrs/yHdW5NvdkNFVV22u2YW/S
QAE50zzUKvA+NSybrqe7ka+9d+aiddBrPRuDaj1yxDeX1rBVGEZfE2j+6WeOB0N8
1FdaMZUMMV5ChNcuwPAjeHA=
=OsSZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'aea2bd08-b1fc-48ef-a9ca-62950ffeb24d',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9H1DCO9I0mwPXQYfwVu0iaClJi07SlvVYQsDzS5UIwXxB
m8gzY8CIGGtPjcyU2D6lOg+hhtZxd1q2MIiuVninPTE1ZMAgbv8jmnVqq3vLwTEY
XwOe+4jdyC4jeaukHAbAKjxtbQggm3C71scoAnLT4+ifzxSbPRnofOWKHDAQR1nO
6AQtuvSk4zCYRgVlE/Duip1cI0cOOfTh5NeCbInM1g4y7wv/NRZOCoyAe4fxXzqj
KRbyDoTGkOAu/3F2SQidnwmt6bKuASbjqsLAEyV0laR8I16smehaqqc7Y1rJV6Vx
Je5JFnEtI9f+KNjHyreMnjN9dR+QZCxF5Zgz1MeGsokW68sx70/fQZh3c2hdc2M8
2aR+Cf2dyEIevYrbOi42R6SKUaxOrZT0noDtTcyuceQxJHTDX8e6EHd8mKAsizCA
oN4Kr2d6aRcRncGFXL2wM9umgp1c1TVwvwSI/hexlE1CEyvs3RsxQ8DPcGLkVz57
fs9UcO3g/bdc+6iVs/qH5laqX9JJCtKZ2Im4V+vgAuYmQgaXGRmOGXuoIB5DDuwQ
ALoqoWargXWoMuyaKjFEaT2NVzcQt/e7157dLjSF4fVePOhE6r/mPGp4Kpu3MpHU
fyHepcRFLhmNz0wET6fFKiXjgxfSPmUjbEES0KluFabHeda5IHlBYgtnZA2G/9XS
QQHa9nTzKaAbbLKPoIYivQ5FT+hbHg4YE24rDTHzdIeQvjdMP1vDIAGpboH0HeVe
ccq2/iOon3wmvQGtt0TR8ggc
=axFv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
		array(
			'id' => 'b6fef6f4-49da-42c6-aba1-bb4880ee1e15',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAmjIs+TotLrvmrMMEi1hw77rREE4t/l/5w0GNy6mwuUCW
M0eObDJIFwvq43st1Mth7bIHZWQ68JdFFRYEgGKmZISyvAB24UxagyHYP7qclqmV
NFWOfaVJ2NcEFmnPMa4tMh3q0DB3qPtc1JSY4RLta6NXkWX3IbMSTQb+Fg36gISX
TT0aRgt1jh4TlDyqMKLha9pE6gsOa85oIlu+UD8bp/VGoKnR2WOutIEw1GEGL3E5
3TRu1iEgISjjOl1Mv0cvUaRyQb1N/iUX7lkKqvKg0Gk2uRPF+Sgvr/AcQ/VzN+LR
drfZzJqjI4IcyZyOeTkPXEKAnNa34uK97frC2BGbMxyLLpTjIzqpaSZoH/2pJggh
8iPVB0pAbugCryAFZTNLXFrGBf58rv6ohk0/To9LteU5CuSe3vVwCn+nwuo/z5y+
EqQkAvLTObnRpLSu1NMgFdLDQPkRWE6ml4t4GBxhpna+fpgrxcWbij6bwQ9FO4Fq
fNr7cj2zg7RoJ4otdP3txK9pc51qbQHU956en/Q1WzLi2wYJiTPAiqviC+geR7LK
P3BLix2Gz1ooEFzFBq8Et853tgysgfntu2LTXQKDZfAHjwG4GnSIjVqMzg1QBGP6
7JogIzJnap/IWu++IwAsj3mOxcVsiyFs6EWe8GVEj5awIwamjEYQUaLIYFm5qVHS
QAHzJ55VdKrAmD+pyUrfmxZsIfEnP3AO+V10+VYwQqYx0eegjg4HqvLrQVLN4ORh
kPamlh1SSdh9Wzbqc/XwQvM=
=LL1J
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05'
		),
		array(
			'id' => 'bc64963a-d5ea-4827-a3ac-e55a77592522',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/+JunEpkENmj0mCXih2wtqIePLw7jZKsd9UoNJkOBbp+Ps
w8FwrQDzGrBDBbwGhAg4RUoy+71k+P0zSRtgn5+g1Sy2HbGnhaxq1pN3hB1R/5ev
F687Ku+/AzGhD6jAkJl1DcuP4LVbQms31XPnBiHEkEBvJeml0VuNWacyRRnvRp5/
TKASZwERU9HGru0Gc8Q9SXRBEimZfDO3gk4w0ZVz6WBNPsDxvypBMRZpTdho7VGV
MLqTS3xkQsqB7xAdyCzxdt7kGGq3VOYLDILh5n0xBiGcys85Ni1f8YGRYZCqDsyD
yFeaIKJeHds3TWrFq3Tpznhcuyd36BgmQZkMCTJOJ9AOLvnyFl6jxbk8RA+TrL/w
oz1mv1Aizm2jvahE+Op8hT/xG0n81lQzI8LqH4bnlcU7WGKSVgGro2j25hriGbhz
L+zcaRnVaX9xpOTPhgCG12BZ1fSDdxz+d4nUFvqMuF5geArwJYZAFu4szUsaE6YL
oJ6IgOet9deMChSUcCayK5U9Lf5LK6rEmNCL54aWbdi1NgTxx2l/x4YZtJ+9cAmC
PcNJjpC4HuBoztV4RCNhxRPLopPBY8yM8vQ77H6XycMpRqAlYr/OARe7/IOVrHhq
sCVNSIzGixbn6bO19y3KvAW+mbWMEwoKTmK10vr9tylj3KkhcSZVEtFXMvDJm+jS
QQFL38+Znh8tRSqv5zHuCRb3pdMoKkrO+AcROLNtq2/Zt99E5KF9SBEt7PS9sZ8o
ZLrxVsG0rEdCOjTpzwxJw9nU
=9Yjk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'bc6e04ce-d551-4117-a4e6-7d325dbc5daf',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzARAAoyhXoaD9RP5NCws1MUiLNb4mU3YLez0gNcVMEVL5IiMf
ELOEwA1lT7yetFgtxTOIHi4VSmPmslISbc9X7si14O1/lV6UvrgzvWpTU+LJMFuP
Rrv/rikqg0/avioMCfA2vibhsYC1Wi8JjXH0PgpdGdvR3N/owSoEKlAavBL6svRt
JEuwrndwkp9T1FnHnWlXs99Qq/IL+jKgyUU/HEg2rrLjAjiY6Rid5hkjCJOBLv8H
KlEEUpVB1fZv+Kt7sdYgAspmz5/FGO9+7bqhGAtPTd/gah+B4yvIobQ7/2fiJmtv
j9gU0NlLyGaIJnFSEA8qfAdCJTt4YgIjqLO6bLEchgoh+Qyl1YP+9d4/B4dtXo5g
FHkw8I48/63mlPronW/8fs5Ar4f0niypv4C5AgEgZJ0sRvLn2THc6D0oiuPw5Imx
hMfcQWZ49kaFDVX7+v9VUx36F+n/xJpl1CFItWkVaHMHYwPLbEwNqueyb2o9DK+r
Hjauis6xDlbwitMfSc+E25AnwAKK+bctTv+n2SpyrwEFOA/LqWz9F8Hc7+7VQRpN
gnoRzatLlR4mhZAdr/w/aIyx2okuqBapmGj/PLLqM5bN890W+UvATi12+PIAfGiB
oWgBnxjcZ4BGahcVYUF9QXQS8aT/7yFv6FWJO7dBR7ID26IasaWhRmyI/MO3bBLS
QQHwecDnE9JQuIVuTAvtokLzRGWl8sdbwAmfq8QbslisAEU5ybU8h8P91rDCi8pq
sx1cbm0DrmnQCh/qwYgLpcTK
=gqcP
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => 'bceee8ea-af05-450f-ac4a-303d7c87a93d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAsWVIVMDqjQ4a21oVBn2HpMgr1rSTkcCQYjiCQ7RAgsWO
r+sEB6NVehJIz9kRjb4+zvESTZNtpP396lfenOSPPsEyXXRWaGQYvVUEMFLX35uD
42yPvGyv1oDyQN6RLiRcBVBBMQr0IQFga/kNms8Rz9LNaEYWZflMq0v8gG4t4ys0
9Yj/IxS/hnTX8EnXe6gHXXThj5/PrjEdnY9Qfkyf9dr5zVlHc6o3XOFDkR9I6/UG
XlAz8i2tGm0ic9mfNR5UqHoKolnMO062K7fvusoLrC3Zwv6J9eEb4XIVRleHjcaw
C1nKbnbp3r7XLw1s7qcGo02xRMrx7cqyifE1oLezdjgodj3h9YJY5EoSZdBYfIeO
gR5qRmry2+NHZQUZA7BZSdxaAolhR9WgDoN799Ih/BbzIPcsKqwnJMVNa2L1i84K
9/3eiGQ4ngcUnllPkCXvr+a2hb/rIHxQoJeGBfWt0iUyhmd9ClIXIv/i+p2isVgG
Gr4voX8tOO5VReEFdQr/Gk7nLCbZdCzazzFOFte/jc2uFODettT5Cb8LxqnxQyLy
CZRwjWQUAdEgCgrCWKgFXoDPpJjkXQQeVb0kFiguVTGCcnbiTmoHUEWIAgqrgNOe
V10Tqdz26Gdo8O+7byJaiJLQX6fjPAx3VykEXYOxPi9BxJP5lpq8xNBPZYNVcJvS
QwGCqQ/Uuh8VJdfut3QS8BVS6wzdH+I4oorObMB7XaGHhDZurzGZUjWTovO7PDvG
vM6aom91DH+R5FLEJ4M9MVk8sR0=
=bgEQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'c012300a-5169-411e-a268-5f0a60573e3e',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Rr7Ns/GWLSN3Kz0F+dxO/ubI0u3q39GfCL3+1iAPiG4T
frA90cooZQc4cbLh3h5wkqZJIUy0HyE8sJxta3/+U7A+H2CZ4Ern7D07o++k6VoL
VeQzCYl05YqbolgoJ2E/oLeTSJy/ghHnvHQ8MzrEMI5KfvrB5pPMu2bm80jks3xC
pRU7FYlwkmlHckI2ftWdmLY5f1Gf5oJOfy2QrYJhhIB00nSEiZw4On9cQv+rbvRO
y/KlsBL8SamuuxlhrhixhqsFvx2CbrYKGVt9cYMpMy/Zgb9wg+0JSlMz6Erv0pfq
DJOy/iEuRNyp1vdx64A6WvK6OkfJUL9RMkpayGoRILx08VF2ZKpe1AVPauAXaFD3
38VG/33Nl1Q0wXifVvRBdnEdj7C/r+05IHLcfc3Jl4CxttWvRPj1XWLF60dS5RoO
6bRkxiq7aFPifdXMg6xQrQt2HW9+E3TWf0piyGifRn6vqU7HwqRECGQ5mHhNUEmv
xpQykW4wOi50TGk1UP+0tPofLlSHYEk0myO4TDDzYQofjRBHdCdghQgmrYXSanNH
Y/wUtC8W6cf3CABQBDfpxziQ22ggWGRD+LAkVyYMcSrS6MI5tbHRX2e6T08lH0R7
mB0lfh4Fzd+FXLYb9iMMOtu01oS6p1sFecOz09VSpJiMuWa50WK+5Q9bxU0IsJXS
QwEK3feIbp7b+/CUws1R8p/LjYRlL+YDvT4gy7Rc1/aCXrRKEnST3mw7JnUIqBBq
NlriU7Y0CtVcAWkcgsYuHfUEvcM=
=a88t
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'c4139ddc-a8cd-4aac-ac82-a047b38369dc',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAmhMjQE4U5Yurcamovu8YLIVGMZMEW0Vy1rSaLKmzWyUx
NLXXZcS08u6O82jET3Hu09/DajIVkE32+ZggLG0SX29wo0w/KugF72xtSIsG28y1
A8pEVzamdDdfz8KQToMoVWztmpKDdYa5G91jsNzaswbc/QX3WeJ0s5TEAluu4sMx
h6myAz3F4DCPIor8xkFix+mhVDhPRj6r8PHF9dFMrIa3Nlpyz8nN07G0QO9kBr+D
pq9PS09yJYMT/jGQxzZB2INBCsIXIk4m+wuFxAe41TkmCDe2o2SKPuVFR7kwjZPl
gdeBuzd2AuO2pWMU/XsSJER5NziQIKwEMV4FmboYDFQU9Jl0gRh/dUkEEWJeLh9X
CJEH7awvV9rzIMvDLhTn5e0SSEUEv98X3Ld6Jpg0dec/I9SwMAJ0HnsGQ8sGlZGh
juz4HDAo65Mk80ZlGM9xa/7TVtvN3a9FSqgUpA7Hs37sjK2QvRogqroEV4i/5KS/
S1eRH4Uu6sG0i9zqVSRBizRLVMLLm79bcslXlJVZweHAl9jm+BCGOzghE3/hmslv
BVi54bd1we8MHFlcErA4Xcosf1+A46CjEN8q9JvBhclcheXoWQgytepjapPqnvMB
toah/JUCehYSaXc8jsZUicGwGyvkPUhjt8BhAhlNcg0bzpe1V/WOl4ACNbF5/YnS
QAGkTZSKr05NqVV3NcJXxz3JuCG+MzE/s9nx2uE2BQhQIIBU4/sGL/ne1h3RV4ZO
Unykph+6env6cXkXmH1ILLk=
=f1P7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'c78603fb-53c2-46cb-a5e8-c43c3c56241d',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//bZalOvpxQtPx++E/8VKXpvEHxCDt0HH557Ylm/5JrGKA
p8Whj3MPPQAe6Rtw95U9QUlnM159BOHYgSIvuBCVqqnrsqU6z41G8DlK8fTmDfNO
EyxtLvCtLq420sTrWuX4vrExDaGX5tOCY2HXa5Qbe4mFwdLOpyubazznFUFRxgoj
3qB0hv1VI/zpW7UXUYPJTUtgEagF3EjBNClAoPiHxYcl2cmgT9OQ2vVv4giUTk8j
x3btKlHIABHfZfRmWjFS2v6ktBTISJtRqIH4xQq1sAbPZMgtcUFp/OEj9Byw6w55
+vOHcKe9GH1lBpIz9m6JTmnBLjaUwU0li78hkU4Rvnt9L9NdlfQ6ParB7yao9eTx
6dBHdHFpIxJlMT7hOFAtzsmag2sQnUZZuVmvv5dfdNBAWc0NpDdj5D6ZxuDHnMQG
jZSQij+1yPTLWGDreq6GwwFEKjj/5iPvwjITgyJJuyhvkyR1i/togq+XmYzqlASU
YxXywECgTMMZlvMVY7IifZ+b31TiaeY43Vk8/ZA42G2bUDNsZlHM6pkm+jBB25L8
OZwPGreXqAxFWgknSTEbQEt/MJQb7EXDCpaV+xXSRxHnIH25WorP+3KhDN2hnxEN
PekdAwQ0uFgBkCoyXpY2arsTB1KkcBoYZIK3M0HiEKq5UBD5/xioWJiiQVr829zS
QwF4PUluISQy/KYUFFKhjX9mL7dKXgPmtskl1IjwqqpUsl8uPurVsLslnVSuXeyO
m0fj44BthOgNsr4e7OL2imutBaE=
=6X/1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'cbae4cca-18da-43df-af49-a816b75bfa96',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//SPKisaKAZpWxrytAxuGqA7BBvih6VAon3mLfyqv6du1l
/p1RjPVbKAgt44CfqilDm94h61a6P4SVaKxFfBChpzfNaJSNMCiEo87IG45fhDGS
frtM5fWXmKtscaHRnvPPRF03dW40q9BToFV0yGnDHwPFBZqW1FWG1PgDt7eUEsyr
BuFMohywUjn2dXsP0hDUALpdjYDj8bzTl/DI+m9L7q+GCSoJlWhEslMOdGOQkrV6
bvKUg9fatg5arfH0GRSfHMv7/XnVKNi1wtPe/n5aO5GYw8XUa131bRc/ic0KOQUx
wU9Q7zZEiFwBS+MmGTl3Gj6D2Yy/KO5ebVjSjxXuT6ONzIS7XV9U0We4aOugS5/g
CGFBDynOUsV6oTg11MabXAi2LDSlkyNM4BDTdKVZu5yqVWYQjhyzI9vXqlmR7Mg4
jpp7KjgkzbnI+SObkC3BsqtRmwXaPxbznkrA2RVNl0XWxUCd9KD6cZAbWr6WTvW0
C3055WHJpB/8skvVFjTDYLY534cSKeOGbbJM2TSrEnpnmNi7rdEyBDFnrrADhIHK
kFmk3Stjq4EiMWdGTllkodYX1DRj33rjVvkkIUMtIoOP4PDTARgI3S25zD/Iuq53
eiR3m5LRYXZPkqZaE4en34WUF9LiuI94x6hEWET4ajDQrIpFWSL2WqcSKfmh6cLS
QAEs5M9E8uFod7SZYx9YRNOSgEeXXfC1FEDHDroFlZPxdM3ARDztRlKL8i1cnkMH
RP1cHO0GG07C+y551huyBHY=
=Vn23
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'dc7d5ee2-a8bf-4092-a337-7ffb23be5e62',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NVOHv5uFyyfHv6Cq2gpDVs++wSFWIDqbYObzU91aeI21
eYGP4HZP3cbaRq9nbpnhp3xZx0ZiZeC48GWdn82P57dQ73tp8+B8GSehOZeAUdzM
XAAS/32M4Kuvf5ybK+RwcvuQlvZBZs7dqKKGEJM7FjVd1ms8m1pkXNCRnhK6WBQt
KUCgUDQrQeJdUrAzQk1tQSQFKCVNCC1vwTraM7Vg/SlE66Sxt4VlGhKrKGXHNo0K
VcdWGNCffI9UKFiWL074VXFq8HyXDWSwJJas2krE0QqKbTcH+Vbs9N4UNxu8Bk4q
Jw1vvrphrhrVg5iuQNyCc/N/h7FeHvGoQ8mUkiRgHIdW23R2IPfmdGOMfiuCAA6g
t6Ddlf5yPleqTsS04C0f7cUzqhl0Lt42pU4Gne/GYVvGFQuHLm+YVxewpLPvwEJ1
1dCxyxT6N5bALb2gKSumGSfkZU0qoG8Y38ShMG1OUOO+Tb1EX3o9X34CSa62iWJ8
CDEJKPuECj6jdVJRSvmzVyBn6ATV98t+k31GKd5RkuLPzTWzZk2Qj1fszALMwROw
4w95EuvKjeXINJX1+eerD8Y5L2F9R9NHYqDX/tmWH1GKNQD8mFwnkHDKsBGeX1VN
Yodl2rngt0jFCJeoEeZf8wZW1xhkKjLVlwEzmSeLawCOdC9s+EwjR4W/pcjMZ6zS
QwF3y0onyyJnTKwIACLgGhzs8/dxXLZF1pa++gU8TssCWnqHzsMD6ILhqi3Dn5Cd
KjE3uAn77bT2PnYSWRcpAl2a0tc=
=UYtr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'ea08b66e-0d8e-4168-a5d5-f85b8b74d3ac',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAnVNsRJ8lfjnPOYejUPH3mE5sThZMuLXT3vE6qayD0rex
zau61XmRV5yl16KNYYjMIWXD1tXDFJ4FLlyeKImz5H9ypYFgt28A2ICXY/uEUCIb
fZeNyT2iCB1ZFutHdTH0tYLGP4hudPqhfN10c8e77h9lIfoxdePM3eyXnyhoI9/n
j+yowRXN4f2QpCV+i6ackxudtoegjExmiF4wHfJmT4EAyhatHEjZBFI9xTFQsdNm
JsJJMSTVfdIasz9xSv5s7SEvr+YMsKHZbbylmuX6nft6nP6pRkVJMdk7ioTi9UIU
M0a699kbgW4zRBvKWnmn2r+yGAXkFYX9hwwR17nbTTfnBkZgoc8QNbWz2fJDFoat
Rb0bl/Gy5mpbM2hwWOIez6FP/zZAZmKtgs7tQv4rg+//wTJqYju6TOa/DaT64zXT
54gqq37DV8yy/WEsnW++JuqYZqEfywER8cSc8R8AzO8gbonxb4WJ1oPJkDbS0R0/
8U8I+qzRV9BSZ/Yii9Wk6xsAG2vJA13lSFkelOUbRvX57Rkw8u6o/AhBbQmkuFa7
rO0dTi2rR+MOPZOIaqofU4OT4LxI3T1aKnV8GSJK5UtT5TmbJfe8FYATJgZ8yIXR
pq3NOxVE710Q6wzqOqNnfeyVVFHQ/ekCnqtPk5VAPiNfjYO9J0mXRhSf+3DqnC/S
QQHhy8KRpu53YxyCFEyG5fhCAOFVrU+eqs/VTlpeaBWaf9s1XwoGZonPAjJ04tYh
qtEBtfGF6WxEuI/z0WyZsQ2x
=bFPV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'ef64094e-09d2-4a93-aeaa-23b86767a949',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAl3L2IhnpMhR4FMDb9+yoSJZt1Fk9QVD/ok+lzI5AkS82
qKAFWcNA2TW9FIjQIR7eEqCltWRBLi20C/+K1ql8UHy1jzWgl80pVat8Of/h2S+T
DpSay0TYbSUILuPFCN8smrHPeBqkvanWOzHPzrLX4gOXXP0X5G+fpNH9+E44MBoN
GFinBc0RoPSVnrEJxDFe/+OPr15yOe9lpsT4PKNtmJ9+E0x+wWWWIVsTnYS0FE6o
OCgME51ohaspAqHGNzaNKacVxu1pzw9IJN7MjsrisgAOF1JivFkQhK2XbE6/mtMQ
eh+qYnnebouOgBwOA1ssEwnqheqiT/DVb5bru8l8JNJBAapPtdgltKsGe0yb8uF2
KE+I5txOGOhHeqe63ZkqE67TdVaOmKciV7EJCyj/Cd7kJsb8HUkv2P3wfT+Xxgtm
b/Q=
=iYip
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => 'efb52418-83f9-448d-a58d-37d40d65a017',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAtpFV6dWYz6c1AtR8+uk155uBvod33EbLXHoS+fj4u4M7
GH46NN+2WAYJaFN6rYNxagHhsI90SLGLJPb7T9e/njqdO/7voavIHRIijWcZbq+C
rSIbe1nwB87IeteQgC8FuUR3SE83sdCrGd2nGaVuZyvfTyyeqDs9h+B7ygclB94T
kvCMLWwmZ27wbLavfazPs826SFlUjuZFdw415Nd3PIfv69hymLTnwtiBQ8dtyalN
wNXMjNtYnq07H6Y7bT4tHEpW+5ssxXGm8RYlBc5EP9GO4x2KwgjUR62+YgMDzDUN
vVkF2Klg0+PRbp9tRInmpIfW1X4CGGHa53rwYohTiUAxq0Ngp11SJT/die3apIg0
cCr/Cr52Uhf6XesJnZgeseBY7gh/RBspLDyNu6bXb6tSCVQa3uRGdx1TFrD5EoMT
OaOmg7Flo2Rxeynnk04Q6YPWf6BCr0oeFHJ1hFPvEX39OO7Yz6dQujzKhOWMuvbm
r0S+YXLzQIJ7ypOMmfwM3e7iJIY7oxni/A7KS2uSxBJ5jTE2aJzhR//GTWcZ1t+r
QLoBjEYUNS6FYQUmp1vh8otCdeZRlOx4P2Yv/q1V1ISSO7d36TA9vi2o17yLASka
gvvQVOBmPAf41cch1gGrpmfPBqzRtW0X8JoJXoatreCuk0cOIirhDD0bBY/MuW3S
QQHHYegiIVVYg1YlCMcoDoZaM9JMviO49R4zluykoCrNIHZq6abVniaUQBCptNNl
K354kBFbYx7BCEnAYpmU63zZ
=B8nv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'f63772c8-d28d-4fb1-a0aa-da7db6acefc2',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//S/lZV+GfhXT1VBQxcrOXubNy1F4Ob6epUlUqOELETLK2
aE87NKVKxdg2OuxmBLwSDNxbfmFoYKnweUMwOU65EexqXb/Qh9mr2e0avDxNHiC+
JOIUbeGvuVIpbF2iCFWnYwQFPQi93kI0si8r4wRDutQ0OzaTUmytSrIqkJmrol6O
IO4chvvE4usTV6qBH8wOmz7AFHrplJV2h1RTw5PtweqlcEsZdi+d3RXusWhabSKW
H2DZ4msIzWchBLTyroNJ0HqXyG2uHSzcca0m91dDkdahNuJ6IGGeJphppxd2dQDY
zwIRS3e6qP68DKXsr0CYlGh7Z2Z+Kx1/zTfIN8xi4YKZn2SilYjzydi37fokypuG
rQXs8QyfQzfRIxFzy03aA24HsChFu4dullJznOqpRCvdcbGNBm8wvUZ7lt77bItx
dXe9jQM3KYK16VgKZjjiqCT1R/BNNeWU7+vVvC/rtC9o3QSFS4S+wZXeqkIlaGcp
/rgl2X+E7AMzNC6Vug3UoWHtFY/uDYrrhvvsqUJlktRYmeMWZZPgTl9+6mh2QmSG
Z4SXy5XHgSb2c1N5OQZLaeLe+h/wqhX7yAk4OqgLDDApc1qDzUI5gK3Lz6bLGWdB
0vHjmZtopJD/u0a6D82hmDVpjSQWPwK+2Ue3w03qPSASZ6ZrXlSGQOwT2TwZUhXS
QwH7B63HQlVV9fr1AsKTVmuOTqKpKehT7ekicQATwsKn3MmvdNobQiI7tZV8OeWj
ba6lfzrhWCSvwjug8ruKC2sJunk=
=AOzs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
	);

}
