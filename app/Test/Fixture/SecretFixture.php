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
			'id' => '01073c0d-bf7e-42c3-a301-291e0ad09114',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAzPfbfnZ01R9sATUizNFcu+xlymad6CEwYBtM0kOm+tXL
rvUCR/c3wsHhwgSKTVmbREQaa0mAL+tXnp9dHGrT8DVhiusaSMxV6hvcoGdFTlyI
zwiiGNmAsIOqoHw314N8H375ecD8e52AdUEN43fg5sLjxB7/VPChimzs55l58uIM
5JL1XrJhCzRaffVagLuFpKpo1LvSurle1KJF3F4QIz9rTXIlqSrPTB26rHQ/HVNk
lOpUDT1dH3ZpGHCvXBE+xLw4vn7TyofDseqd7EJtmzCE2/wUyvjcBT+ELxUSumER
wfeupBdUB2LsCrQB5Kzoh7+ZPFsy5hvArJlv6tWEwVxdZP1E0wDbvRKHxATX+IQ8
rEZPaLC1Yy6fittKf9z3a2mhWv3tS/nbM/WJOZwid10GL9Ibtz7HuUC7TLOKl99q
SK2dXwBUFV3QrSTPiBytNkbZ3XdLmeTptdqOay5FJ+Qd1YOaFzdg5xWBUVbyxtkF
qGdnLZF4xpxJ1jMNcFHme7Ueg8jKQ7EaC14ScOcVGTqfQcpsAlgIIcNSspM/rRr7
W8z1VUrGnO53RvaDLAWXJWywVlL6tY2GO9OHD7mOrmdJpr3oTCbf3MnJYztjKpx7
6DLxP1+75G96ZFUWiaX+m9BsoaVcqZJMKRrMFrOxwiOb7sOdzk+06BiWeSN8c7fS
QAFgvbAwgGs/64TPBw03cj5/BMceedvOhkFr0kMVICFi5VCxfmLfoGxUAW38k+Xt
k3gDQvmkLVx8nEitGj6RtUc=
=t9K9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '050ac1cc-5cd6-4f6e-ab54-4e6eb82293ba',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf/bEAvxcjTatOVUOEQqCh4xOCYmd9PsrW3r/L+nhDZNHRB
wRWL9uaU/H9+yunHqnikHiL+WtIilJM3jaZafxLcqy7sZuWzAWKAm3EAMIdUM6cd
zO3UwbBjv5ZGLQ1bGudLFAXF3Mxm27Z63UHUzZse1NHELfm+VNK/hyAL0cck3Ji3
SZSv0ir2qiBJ7lYfDT+hAoE8ZtsQmzFBDxPK+UI40213t4RqywEqtOyEpWqq9Yri
vNQiZj1jDVP5p8QLyhl/8XrtCMwE0vJYfuLj1wfQ3GmTrDsyMZ6xKxG1QHCIF3Ya
KR/jErNVPdQvLQTwDKA8td4PC6tjTaPLYABpA0AALtJBAbM9NhFsFjL94A6tKV8T
gP0DePLzp9RBeJ1xm0GpXtkPfzGgvO8acSAScMiSM843iBAuMt3aSy3kirbh8Qmk
FQE=
=miX6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => '17b3a8ff-d66b-480d-a1aa-237fd95758ea',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAgV7YFtfPqQxxupnUSL03BIWfS1WFYZC/pZBEUJAjPjyw
4/3cpWr1dff+GLgVgsQuFA+INFaemItf6vxZ6qhYlbqB7RPtNFJExNguE4N+FdYS
g+r45qzhfVCIssUxsshsZV/GEgmwsl/jYP1iXKIqlGJxcQGJy62fY5lh63MlRgCZ
kUtYenLP/h00KIY795vyhu4rObhZpBV+4wMSczvqdoYJhUpbMKPqP4Yt+mGpk59D
G3onDGevLV9+gY18cWz2FX5cSZ3kAwIFYtGa5SMgmqYk/b3hkZ9Z5bErlnvWKmAd
STjqWb3kpPOLr1QhE3xtbtYOH1Yi9k4mFYVbuBigTBHwu8pK0c6xx7+jVIbBziT7
mvW0jd4OoHxhYsSuPZaTi4GhTYCDDWq6nbJ2hTzXelj/O+yrawq74mRPUQYQjEbA
A1AvqJuaJszR+tjapRqpCF0fGP8dkZpsj4nAfArmFfC/9bg/hMLPxLtRdyVEc3V0
gf1F9OeKh1nb07ksL7YDLSV3c43fLIv85RK35OvjYZ3XtwCSDH06U3lfmXYvGHzg
xdDguTocgTAmT2AvpIJ986WRv1TQ812KAHfuH5pxojU6MUTvEN9yUHlFOC0nPy1G
iQZCBNONupmfpvaf37KvENv1Ktq/U7Jh1wpPr33XMwoCYC4lzRl8GlMLoRmuhoLS
QwESMoSZIAP+SItbVUwQfYdycjWtaow1OnZxIiwwarVs8kGuej73hpmglffLs0Yf
Xv4a4oFn+HsF0Y51SQ92Ix+KF0E=
=p9hz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '1c5b47cc-fef4-4cb5-af85-61218cd04e89',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/6AnRP6/A9liuFlZfM3oFXMbr9xKtYlxzX19EIZvjUZhPB
J5Fpg47M9DF/G18IMQbgFb/jfemOJ6xrVzxmU13v1w/CQbMOyUhWqGyAaGkdwmaz
QkH1w6MYaUv/TW6Qd5GBAbZQEtGQ7yRsG0hK/SNNDPtH+y+Au9fJ908UQSKmslVD
thELoqy870zpNoul+7gpuHqwGGIcZkSxjodEH1MmFTXYSP8N4ORS70aEjRjtzftU
ZFTaC3dqVh0HphS8DlwfP4vkYY2GETwhQjBVx8rY2S0rLBkbWPnAKyijT2oKNzLl
r2WQSHQ2cOv2LAM0Ji0dvk9o3DkcDqe5Q4e/wClUCgUK+1Rw3YMDwNqPVFHBb8ZJ
l5BX7t5f+Sbyy8NiPgbpSnqDAQCgn6Zlr+8vaWK6Lj8l5F7XSJq+kgnpb+w6FGrc
luDQu+ckF2Lvl0lnka2K/kqUkBtl36V7m1wcR7Nb0fBW329/yACBbXC5FwrFK0Zk
IpX+Pm8HWgyxgR7Coz4kQAsyUpJQTQluPBfZsv0IXQjSWXj+KBGJxf5FAyLPXRs8
kdMRljhsO6f9by0r1dlQKN2PLqPaCY4UgYUBfMAuyf0UZG7z+sH0cjzkNgxYrumE
yaWFe8f/feOQA5NB5j7Y3m40ertqBtMHmBNe62lAgeTfiuy9TgHzix2G9A5fs5jS
QQGewodbrAs6gNGJ+2w7ARd72Ob9b8mlratJg/yNk3ervcgMqSrfXMFAM3l+69Fb
MFaRB/SqnHmuF3ucgxIT37Ki
=GJbr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '1fb80fea-b804-43e9-a50f-af50cf879a7e',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//c48HNsh0d6PNV600mnTIsEvEX8+Ia/0fQivfpLQdKWNH
4zXv28DOqbhdNFqmo6Ug67MfTtKzuFRZmHrHyH+Ee6wpb16Hkio0FR0wkKndk82i
lKu4RTkE6IWQrMrstt5rVt1AV1SuvwZ2xstzSH2N/WsZsTt5BJMzkkRtx6Wrc66D
Ozfulw3712O9gcKO9vElD+h1TJSzWAzfrf7AUuH5syMlgtYxcSlbPlGZ65L0lK9e
cZSGGKzvKVWDyK+G0V+wHq6nctIdpSiK21Y3QilONxl1LKFsa2LfH7s+DdVeqtcc
nKPuuPWmEa43YJK4k52qh8uiEqSphp4Vu3nlZc5BN4Fo5TbgiWIn31doHyZn+qba
U/Z/2bA0xyrK5CC+E6TneD9jFWO6xJRTuHJgPusZhQvUi7nMxY+IldrInsYYDG/f
t/4px/qZtSwCiDSYzkq293EEYXb49+uIYIcd5kLd9nHQVkvQyRADliUR2hbJii8C
3iK1VAOWS91sONv3ThYdVX9m/a4ZlZ0PfCUlnLwACvATurmcSfrVuIfhzzM5Z1Hc
34byCAMy4dx/LaygO9YGLJ+vK52gRWYrSwdpjEBZ//kOD4uvbiyuNuU4uhlyKaNg
LrBtqBPLszmqy9LgQ8ia2eUP1WjHHVFt9BEXQM9w6mgvKp+YZzaQjy1HSh02PonS
QQH1/dKSjIdgEJ6m+rTd8PlGgIblYuWbdgyHcuBzb70G+JmcdDAnnRfgMVUi9cmj
Xy4U5uMBYnRwC/Mx6GYqzxhn
=PA10
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => '1fe91f29-11ee-46da-abe6-d9bc44d2043d',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAiWlE0qVZKpTDSabrh1F83hpOsN0tNVOBCu6bKlgQu7Hv
/e24KwtO21V/vhaQIwjfPnyzjzKz8cz3Z+xDAe+BWZHiYHWSdRe25PE66anegbfC
EmhTV3/zf2MY/+/QhXom8oot8kt5t/TvW875NKOwQ42qqloxTwDNRuKOSNPAPaX5
gPGrbN+4nLFo5X0CQgjHssStavTWEs67XxQJisMHkAhoC0AMBNvqzMa8xHZmMwob
K9cvSBU1kUf/fcdj0rBfwZcUyYKb1F9rEEgOHNJamg1jaiIus3Az63FeqEvfFP4R
0X0DrG/7eu0fwh9HWYidwkZ0zWFB7zhMzsBOzTsz+fJM9XlIyNwxjK7w4Zm2EC7l
Ps5Ugtm5upn64okhruYIGI/AcYNaGDjLAFWoOMFKS90Y/hxsMslSO05yGIkCRszI
AVskDZFmtAjSj2CHN6PKrlwjB+WO1JvIOKRrwzD6aG3/CiUJeRPy2JtMiWM8FD1X
oPBYMXvZFYkS50cpedZWQ9bwy2iEJEl+8ciT0zGKLNsw93NzLlIKbMjDgiMOehoz
9fazLLG1MI6CJRnpt0vS9KzlyfRp1NW6eVNNi6rpPcNvVDXvcX1N5CCGwpplLVcP
K8PpXcTIzVoiFIVFRUDUv1W1WQSGO9frxmS456SiJICYE5JcCF8Jt7wl7YZlv9nS
QwGeg5xUL5YByAEaov8lEEEQXUJiJmnYp+S4NHORHjTpQuIhki9l8f6muSF0lc1B
taucEqKvzKbPmvhLtk7gOyb9T8o=
=Of6R
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '232aab5c-4cc4-4062-a587-4ab05387bcde',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+Kba3yCCNOPepC68XhFrJCQyfib9PPf6c0Gk7X3XCyDH2
GgOe9Dq8z84/1mbFbnPhALVssGukgpMDad4OGePE1GCDpoL1C2TBw2seVVghbqBX
ZrbiJ0Sb4kDpJCwPQSC0nwKjVLA1t1vnmQe8bwvRkswit2lvnoBPEnmXTF4hZuUL
909Ub/fJOFIQUSjCH2iQKwcMEhtoEmdDO7sfvGIp+UX2Mncitu1wsnAk23s7VVCC
7UDZq1f5Knsy0gabMmxT0Wybw/qdHNRrIT8yz8SIdT8uGMdL9YrU7B5C95ndpIvm
vhD0J7goR6shlNIS1zcinT6frl2MZAwcWUsriR4y4DbbUt06VDCKoseNxAKWSzNb
HDff49hO0H/PIGVy50aAYjf1qP1YX2H40S0I6f0PXTCA2A5CjKkuv14lUWIZP/fg
ki1rF07Yi9eWMCiGYpwnCXmMkBiGzFYDFSOjDRghfpLbenoPhFbo9+KBCvHyIeGz
y9tDcerSCK142eAm4ZylVf2isI8XUxmMv9pcE4RxysBAP2wQ6MRIffCklkxle56f
iTWsqbhv3M31cQtejAF9cb9sCN5neTxo6LPEAs0/cyf1H70tVHnosOdpefqNl11v
4SG933g83eZU0o+1dJHLwB93/tg3N4USghARyKZrAdFfebfu/MFA/k9k3DyFBXrS
QwF6cK+k8Bayx+ZtCkVQW0OtnNd4BcPv/FSeajIWDici8Vu1Pi2lFWTumFiCtHZj
Q7tMyNjqzTdHSdG2bqFK0FXyO3g=
=3BhX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => '287bb310-c9b0-42a7-a674-ac1c564b5206',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//RG1FpB90jf/Gfjfdew1rkPCOYVpMsqhqiY4X2Aojlrys
THPpBCJENwIopp6uMz7+gjn/WH93MnFZ7ORQe+5bZIVH8btUSKg3E+i2aK/HLt2Y
S4M7HA3w5D4y+VHF8AybKBjbVGF4x1ij6xJRXNsh5U3UbBGYbon7v1OsNJ2LKzB6
bTk1BMtQiuHIpMKl992mzUbotizrTPi9M5W4I55jCAGpXqFrR0qgIgVbOZ+KxSoV
j6yhIisA78MnxAs9ZfKScYvoplImCr44v+nYCl8A2KF90M5MW8eQuWOHPJdJIeHn
w0OCOi/boJ29a36Uas5dFyilBb8g09GiOPgBxQowuDmbBlF71L/J53+p/MPDjaoD
Ds3wHMihyWXg1+pP4b1IDa8QCGxc8qRyUBHFtB+2M5SwDKURmXHgjYj2INLLCx8u
d2IZcAnuH6hqphYAU/u8mx1ue+IqrpurvpCVkobyHDM2xIwmGCyUJk9lG9Xdne4f
50YQReB0KuHHIuU3YNweVMbzUe7VzEKEKaQ8sIED9k66g2tJH1+QEoORf6dMinDw
4gBvl/PFJMyVGuH+7WRi9oSPJIoomZ7PfQR9DbME7v2+broG7IU9gPdzZHXoYoRp
EH0mcg+2sLMsfP9hjHk4MCnfVu3d43HbucTf0VgqmbpcX1UOz59VUr9/1nVDVmfS
QwHT0QvUGcWrfQKzy0705VCDSnDRJd9TEqs8/CBpcmZk6bEIKqpo5ASGNGwW2oNi
YYRjZVFSqXjDx/Qftj8vMgihlM4=
=2VPz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '298a9470-acdb-4d04-adef-bc02d6ead2f9',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/9FKB48FrWyqwXhbypp7Aib9szi5L5LfWMu0ZN6/L9HZFL
4I4SyUyMDnBRzW4maI0JR8RlLma2kup1rY6Nqu7Sypi25Sxnid9thGZxitL8IzEC
YAkSd6DeOgTZJw1xP8O8ec8tmkX23fxmlTJ48E1JgO7dojtxGE9qoR+IyUt/WSa3
/Y0KQBBt3IFEVkj8SuMhsG8XUyW/1rklA+KlMbpXGXFNhPgxE4tZEngYrjsPS6HP
6RCftIYQ8eUHENn5C4vg166WTbTn9LNAZY6naRXcUyG1WY49FbM8Ton0i9cn3lEz
sZjXW/DyHu1o1mHur9wu5TBFtN6zLL2tXIGUtALrs0Gy8BBF47iy+1NE5xFbr8vV
u2Jyevwe39EIzR8MUUdmqSD6KwywEq0VrqQH2tkKJvjfQOr0tDcIFximwRmBe0yH
dvXdVfzb6M+7AAnikX8zrrFa3XOeo8XrO6gXu94nsCsOPpUAnRRhKhSSbDu37jwz
deAPYSGX0Yrbs9adaYumM8A42UajhX+9f6LqV0j26v1b+/Qa+d4qPHmCHex/cyop
mz9fOoD/IzhqVE1vbrXxyhkm1THeAQ6p71O4wgdxfSCgo/0VYHUiEubzyenmwzW2
OtqxuXHrRLeHng8t1NX6/Es6Z57diA8Q31peRcBk70Chrr1lUsDwQ2Cv5qgtsGHS
QAHUvwDpGt3JMMF3z5IyGtjmUTm1zJ5ILdivYaLAoxJyl12fKz8eB2P0AOFRUd1u
vDqFb6h/w3mPnpbxDRpE35A=
=BrLu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
		),
		array(
			'id' => '2f87b929-841f-46b8-a9b0-9a2980a6cde1',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAApvtXiBfcv6gXht9dctsoYSVjAK6DkM3NnA8eM7Elx53h
Eekz+Am+BRkn2Pai1wn6kwNoBwl8ZfjcTB0TJJ+JwqMYOAWQ/b0ZyIN17NCinF5M
JW8rTgSJTlWj2bqiHFbDKa8jzF1gjFKazi70pBE6LD+9IgPLJ4yMSPdqYpGgoSHA
Nd2FKwigJ6KbOMMXr5YZQZgninFENdfrX2ZzmAHAFCFwc0Mi6nHePmt3lK6hJ0q1
Dh8U7GCDerPmCjrji/6gQrKP4/hcb8NXwA7fLNauCepXtm13pcrfbgFzqNvqJm4a
+JiU1TtnoWgAjWPSNpo8/bWX7JzcVXMgugDykjcWqMmoZVbK5PW6p4ewvpKTUEEh
GhI521oEa5R+cxLr7JlaYhc4xiO8Q8evePSabvsGu5Y2q7mSFZiM8m9wOuvxA+1b
eB28yEID/VW+SMF9S9HkOOUsvlj9U40Dh77JLzK/K/KB8cHiFxh9RFIjgKeRjDst
t4ozsfarJ2eKoauaxfQOG94fOibkBEztWUHvxCFoIt9cvAE0B6ZH0v1DSh2beZI3
oylTKpeuX0gLgEQ1kHS77z3LRbzXuLYombu5PuThTguWKCNlNEPuhqDoITBxathb
yp/P8CDSdLIzxseLA2BviztTDSsIFGf3AvT0tboXW4n/6a1ipE/RzfDT4blwixzS
QQH2X0J7Hs5AejFoESw8OPGWqvQ7fX2Sd9N5QXeHfANpKQ6gRJ+1ULpMRzxgVA5E
M4cMxUW57KRiLXYWu+855kp/
=xp9+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '33952ed6-d3ea-4332-a2f9-3805f8b0601c',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+LZCa+IsvvoPhteS1V9nCvZwEGaPx4gFmF5OrUk2alNyl
PfWMInSBSZsRpI/zkiNB9Uhev/5rm4uvftiRFrKtL2UAZth+kpgXqN+QG3HFZujU
MA/rXQBs1J036MD+9zNfv4SvD6YQHwhsxaaAO/J1E9nU1QlCTVq9D/UUBEbdNqQb
r2CeMBpCeMKnSJFxYpSe5RVEi0pCHf/SMSYPb5r5xZGEM9ETzHrqt4BrCVshFEG9
g1Pzm5G+TNxu1lbTlXetr2+y1c53WPwO6fRlXulcT/6warcu8lrUo0muAVQOcPHy
Pty+rGct90BsjgFZDyzOm0MMhFTtcm72S3/IcgxqUOx9uybLBG8QDzGKMdC6z8PP
LbFhWs2n8Pzjr09H0hhhrlinaJ1F+L33k+Qv/wCN7VMPSaTgrrKyXeMmZw7sgIdD
ILzpFu8E8C6Rw0BNHLvy04D21JgM4dgB0Yychfg3X0SePIXDU4+OrGZbAr1V3dSy
cUmRgGugnPFB1SP+KwaD8/dNVIVJ0Xll1zl2Fk3D3u4yzXq3ir0R+eJCicdzCAGU
l0SyEkpt8ieSeZJALvLAQNizsEK+k/hQfZtwhlNJrG2CRnpC8F7oLwGVv+zZhbaP
b6JRY67darHQQytk/raxb7nMgRDz3nkL3BKFSbKcDVyjQcRT6WtjjJizfh8PCB3S
QQFhCU6keYrAPqYWfDRyIuR8c289sG7Hjn9/YYDPITFUMqZARaeyZeKGyeCbqyI9
VYSycZSzt/2l0kDzwqZD61yH
=juP4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '3a4aa713-0a2c-4ba6-a02d-82a915b47e02',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAmZ+2TrdygGPX7CZVDBe/Bw8ZMQgqcu7CAHtBo8jfrfLs
V5+NcQ0jEkOG/HoCAjRcKuUiUz+JLfxKXRNAwG2f0ZJkJqsXCHmufnHpAOzZBJSu
gv1WBljgCs5pQ4Mbv8LFhEdvT/MlGGApQ5wihSCYwz0QeG7lDVNqzQxbtGigdEzl
OSEm0rTTQ4eLsl4Ue/Gm421h1dIX+0igEh/b4UqRKaKZ6fKEr+AzX8MhDQ7ldoBv
dQFG/CR16lhK3+uhc88A8pXGPS4AWJ+EFYEEXjKb4yxhNhwBl4qWQi7uyHl8jSub
dipaUI02F7fxqpK7jul0KkYqvYmui7MQYI83t049RqBkKMmaS5tCf0Q8A9QBicLC
hLS9kwFgNmEdAMGKzGA5GGPGlV6NgiGWvqGyTzQm+HMbeyela10+R0e9vvn7u8tT
PaBT2S9122m9Tk2BOu1wiApv0a4iKBtAt7t+aHe8pq+gTp3n9dw4AMNYtknMPfde
0AAd5jW0quKv1QxN/6vuHl7scYxoslYbAVaP6eXcEpFYNp3ywDxLOX+uBsaTUHQI
ahIn5e5IsDSbeTQeKbqVZQ8RGvP2TnakLOvc6VJHOtxiRzTxjcVN6RoRj+JBPO1j
jdU2aYYXMMkR7YmmrhPKV3cIXV8ynJ3cwNA4tmiB7b+nB1TZQGPvLV2zIskPNdnS
QQG1aBBHTD7nsyaGzyausu+WIorTM5g6hGM18BFGHZ99KwR49m3MjMLlyG5fjpKJ
gi3zB4zm/fMqT3PJ7TRTl6yD
=XZOh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '42ab5144-eb16-49d4-afb8-e92660767eb6',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//dJghWA6OTWcvvuYCqpsSCsZsgkCJ+GEseoyVIlITRpiA
fOKiZnoOir9dvsEkpiMoh/vk1hjarUNNMs9PtEZAMLzdKoLNJmyAekoEg62SSKyN
mMH+pk5I/8I6mxHMcAiPAEoC4effmrsgkoryqdtGjGVQPl6CGcB6USDuKXMrqTvH
3CbN+3IEmnpgelISDOGj72LPurGs3lQOKx8PHHi7n85QiBetVaZRK5068//dkOIi
pWaUz214LOrdkuZloYrtoTKuMGbhL9Nin8z5y3aEVf2QpOZpAZDRzWGOaMdEbp6O
b2ytj4C8P3QrrWLtb8DQJSbctc2Yc3QBxXUvuNfVt0zn3jJg0Yd6/2paTFKQY9CS
mGq+3XA81rAa9U4EweTqo14YF6nbwJmQNGv78FOlliSg5ukk4pkVUEg8bFTmIip/
rkLUxAiUphGGfNEa0U6P1MCuVcaIkpwD+whicM7OI5EtVX0nEIyquKGvanpE5hS0
8OZi31n2uO30G4TeetQsU9fIREffFqSYA944RBfOEREst66Wiw+drmoVTOllrVmh
up9XQx+SdiiR5a98uf8HGuBelwyJQ/p7PgeQuz0NjgVKdEPTPdfU9Cqq4G0GuC7p
5qY9icx6g7nPRz50G5w2sm1q9ZgHcOi3sAs0m/fbpatHoeuyux3ZTy/Yk3Zkeq3S
QQH9yRbgANotidTsLLVgtXTNnEjaYZnj2iH7Gsc+aq8GB3/XyRPhF5aCocQmXu8c
gDcyf0+agZvP1kh13J+uuqAa
=MN7g
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => '43128ed0-37c3-4929-a31b-901a777db299',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//UqOJ7BRxUhnJ3r2vvi4ZQlsh9ZdJQQvCyUSAkxaNaYMe
xvGGK8VEcg01R48TX7aaXiYoEO4Pzp+9ebZHB4lzwtzyaiVZJHjBGCVxIno8+0LP
ghizEoFZy/6IFN+MmUgIG6a0UneWtxB+Pdm1EwTQS6ukL3UulR1+E7uSADrhg0Xh
sO45XQzVl/P4mhqy9O3MHdByVj7E3y4QJ8h7MG9SAT2XnbNsZWPtfq0IueUzxbVN
c3c9z4uXxtEkVlcXKuQBCs04wlTp3t1bZVJNX8/vTfcAN1xuIzjFkeFWVCEbdi0T
1ivTeU6Umegcw4+nWWhmhFAE86pxrCFU9f3V3l1cpE8pRHK4KazOjbVTXFhqvv00
pMLp0jiM43hx8Ox7usklr+I3ttF5SNVaixVSXvmRTaNXf4dGlp8DZPrZIXMVtzcg
5iaCbyRT7D34/p5EvgqvEV0m6py9u9zhu5WKOPlT9rd49IRPvd3FkjSqmoyEhGFI
Flvx7Q49oWyAXhd7+od9yrCaCor+MGQeJrThhu6qSC8KW3xxoWDkctbtEVKFR8Ae
x6eySynDKe8SlQ3es5PYWhRkjs27Frbz73zEYzjm91M4VIc2al8WWnmlAWb1Ez7s
yd+ivtIa7wO7QMYJ197hTM/8s6r1NWFz8VG1NDN9bnfGGaKaHkuIXlDliGPA67DS
PgEKxOYx3rKswj+VjnVdgzmmXB8GQ55zFvixvamS+zSmwYMFiQZRzMha+V69/+hg
XDntnAh6gGmYJn2z22XC
=jVYd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '433755ae-dc54-416e-a2dd-90d2e0dabc7f',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//aNSytuWcEElrNBfKMxkc3wtR+O6E/tkMEcylQvYGEA9C
OF2GnRDtwvo+0SVPuy9s74ggtgIgRMmT56vvf8HxkjeByGAS0d6toFq9OGCjs9fB
ZuWQiDYdOUi+lDTSnMkjPe8Hv560U0eEOpj0ntTdhavlPQdzYd0IQTBwTG8R0jQR
HjpQVE6kJl5Mm2AHF03sW786wzmvVOAIKJWsLuD8XKvSB6f5VLBodE30YwXhgUJD
btmOpee/SEz5ST9sfnsftoVZwMwx93BXuQDoui4NOSrMr5KopUO8CJjfTEpCwTsa
yt4xGicwz/Q0PdZXJEaXAuK4flQ+wIR3wLHV39hPmpRWYX4yiUt9/7S6zuwHDbyH
aYkBK1Lp+XvW4RIUTVR+q3HX0XBQkon0l8PfyJwJvEZqsbkKzHWIkJyuBZX0xYQH
v4HaVtwg7UA/ElcDNcYhuh2LLRXsfRs8BqnOdaEyI5I9B6fYo9J47cKxWWeaAHtT
hjcJTc2hlVhl/mWPD8OxyRlNDqr324NLVkn8uULbl4TNmVhradTlgudhFxcVP5e/
PE15cwnduWCmVhBFHxDrSB+O2OABi8Ey7FLeuOjTjGWq7TFMU+4GU1WQ045LS5Pn
1fQnzMGB61Uw6AAeVTGs3BPB2AF6ufKxBSB6pNe16dHIoab9Pw4vOOD6DN7ceY/S
QwEQG/Z4F3mxIBP5YN7hGDRLs8yKzBV9ZsKkEhoWvv7eu5kAPITJQx04MUAIDTI1
jx3fwwIdfiLMEGe9TNYuFtv+XWo=
=I+Tp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '4acf0a54-7c6a-4859-acae-7e87c31adb5f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//aQFIssGI8HddyN9wWtHva8d5WKWvOrh1uB4lZhyT4mkp
T+FwhmJrLv3elynTEVnEO8DdnHR3O4DzoNNvoCjGZ0pFZySwGTgKCKqu6Mg/hejw
1WSYsIMDTPPtO6ux9FTg/+Bh4Skp/6ePpGslzNbWbfcJ94LCUlwHWDeCaFEG4AWZ
B8eDGCJ75kE7b/bXiWgp02bT1L0bmW7Qr6DWcoVsLcdC2DL0u1K5wvJSdQ2+u1St
iHzB6EsHZRnkOQFOxZnprloRPjbMqcP4keM/t4GvyrvwGZ0ayipEGOb5RIbvnKK0
LFOe8EnBy0xt+9R4LLfdxj5zfYg9ROUFgjDkYZptokMjRbe16pBi3M7i9DEBY25h
JxJMk7EwBPtIYWZ33acGt735o8LA+wlSa2LpbYI00Emb/rHbZbCihJDDxo7HcWGX
59tJpTyTR4mukxiwB9prEn+K8wwlSaN7WljkQzXsmDjOYWfm+zPdsTim1ETUdaQb
OIPJnDmkZI+9ZlSY1BSH120kHYYYTJv8LikQATj8mPhFfKMVRBdzF1tATQku6KVz
+CQHe2gFHHPBJmCtJredWVxkcvpyEW2vCT7xyLkzfz23tcavbr6Sp3ye/rd1FEa4
M+j1R0owwUfx+uH8dXLd8pqO+KLbMX+1ykemW48/BKMMgqdrTD/yp/E5gyz9RlTS
QAF2PigR23PDpTSshrCOERVPmi+iocSaBDIK0FCgTzFVGI7MTBhJS2tgqjLC9vaW
7DohtfcCIi7A2eZ7fcpClEU=
=F5Jq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '4d893232-f512-4da9-aab8-ddf8b2df14fb',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//ZDafC3HmSgoAvVwbawr0mCLMoyTxPN6MOkLv0b3Z2w78
JhteoES3GEe6mxuNTEdNJwEd3jIXOWGTtwO9H8i4J9zir3gGNY5ivJrHzD+80DYl
69Ye8IilHt0IzjYtB/Kqk5+EpRwzksE2KHaeLeml9GIHUNJV/WNzf5RW4H1WPZea
o7ZmP1iJkYbyd1wobQPXW3XHUn9DN9gu4UNzFX0Nd0a/UYCBPjMs7WvMRHGmQs0I
HtGoj1rFsFXHrOJITRGo+QYCo7RCRpNEqWkEf70i5HYpa/9nB31CcDZpdRwfVSWd
1djDhQ7ec9JIXQYAvEvC1aCoJQDSCaHn2huPbawEgQAHrfsyM0mZOrHMDut9zo6k
uafeEkrnBerOq268hOXSXHjdyA8wdlb5kSGmsKRUT+OnSmDMz1dnJdZvEHmEUTyg
752cLXV888NSsrGSGNCdz4se4vaINng78lZ8flGWE9+gV8x6BHDVsw6LhZ3Tu5Su
Qjq2TN+8Da0dyw6udUpnL5ONdh04hVadtNEnLM6HLEyXoCgq99RRspB1xTJIs7jx
voCRjEAHjaZnHGX0wpab/BLVnmJK82/v3ce5hHHj8PT0BrfFmp70Sq7Gp+uegSgA
M+CguRTto+i4ZsZTVPOcii5bdQCc+KALsTi/g+X6eMN51yt0jxYBsJ/piqbUGqrS
QQGTxBtbOcF9lQ9m9iCr9D+Bj6ZoC3B1ikq1cbWgZcmTbCeVnf5n5AlD+k81399G
5KSR9k7eee9shyh6E68TBXgS
=ilTt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '56e9a45b-07cb-4e6a-aa0d-64465ac503ce',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAkb6KiWKY7UiVQriCAaf7uGc8Utx71bkeUv0nsjBdKIby
OPdETZ6i31wby00Qx5j453H/AfjcSI9CEYatne6RnoioOMafGrahSBU59xkUCR5f
QOLQm3m5fyQ73RUe8GM5typmAz1ljoHzmMK4LblwefmT1ENRUQtybcKC882ryLq1
f++XOjxJJQVbZxz1g3ZxDG9f1U+rW9hXyExA5c5+FIw1WqSESSV5oUi5YNe4uqWT
ZZXE+8oVFxS90Jlge5Y2ICYLTCWbJ2fXvpsUsAckBxiMCdviDa6gs8uE6iRgoCmK
mTb6J/W3hminTsn25rGTPa1IzjPLK8W+4rDjH4+8CyVRPtEf3d86KDJZHdw8piub
fH0TNKxjINcdT1d8JL9n28Mtp8E/5zir/CvHv6/zcvSE/RAjjwHqQb6adhVd+yZM
4wrfhD+XhxCRL3lhwxU2FopOLLkGGw3Vrs4arWUx0nPr6LTU/VZuV7kCFuT99tXO
qLgNE3cxHyRo/sjpR2Vs7i3m0irT4WcuVxVCub3SF+M3VPCAp/cZ0SDP+3EOprt3
Wx7LQfw3E012+tW9cZmOM1+NYwSvnyORCVUkXyNbu9DgDxgvB2drOhmZtNfFZDCr
OST1KJ/20oL1Alciltm0E4zk2dqJyZuvp+JdqGF64heynf2aYP2CWXbfYHhjS9PS
QwGaG0SHgwDv6P/68eRrbd/RjaVqo6zbtTWvrkjsCkaxMOIXwRPemmHlbDVNXkxS
Tv4QgbS0ZoJHR7+aY8qCJ15IbzE=
=+Fnc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '571b945d-eefd-4c2a-a7d1-83a21ebce7d3',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAsLQ1KIPvSUYaAmg8StDTJV2wwy1ieALm6l3MyqAPP06E
Lq8ZJDm9iyECbHNM87vOt1iWSxQVGd4PwtjYksx935ZX5+1Admqu0MHbNhu3NWpH
iAOB46cNLChofk1oRzeuGel8ENmDunsHKdAK6ktqmU4osWgImLzKSKNpqwZl3iFb
2DqLmeEfXpg+J8S6fYlPMkaSti7o8DogEbxsBybynHkDkIxr8AXUhuEfEdtlonPu
WR6o1eS9pyXJYfp+S3CbTOuQNIkH0hAwcFADgWvqmJWhsAIMfNo1Hf6dyVlTo91a
HJJiKNOIbWBepP8vXbuI8l4F+jyvnBN2S1yevGqPiBTINV8hCpu0xL/17Po8l4vU
9kXrhWNS/T1AFawV6N1MW8Y+6VR3jLQDRMF2iXDajjqWG1KjDuP0hniGZceSdSSI
tzV5kCtPzMvqaidtPQc0ESf8JdQFAwFCLHv+28w36eDGiwo2CDvaSvUgDc2bXrRU
+rtFcNJBGnM30NpyGeXF8lFTHOQ6p+vk6HfP0m1bzefGbrg8GZkSmg0AlQkZqd7g
wPMWNgaO+Wtx2pn3bxwlwKWKAEh4ISjeX5Jp/HzWNgQDZ37IY8Vuubohr98T1Qoz
sIWp3qDwcl3n5iTQ4hBFpSEcJH+hVvImC6j52+e+v6HBhVZBSUM6+OQPcgYdxH3S
QwEz0vbDPS788C8pLWf7pdB0/eH+OHL7cTWn/asuhmac9BOjBZI8ksmEIXtixYSL
t5AN06Lw8VHGzjDYGn7cMl2u7Wo=
=2vjW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '5ebb7ffb-32fb-4be6-a03b-0af72ddb1e6d',
			'user_id' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAw0P12ReHhxtAQ/+ItKi5aBGxYrCl2uNoM73RPh+Xk3TS9FuU2AWeNucVLJJ
lB932DMqTy/6we7FrIdfZo5HTSNNIfrUKC9DnSlUbCXW2FpPkYybOo7tP4+u61MR
feO0Br3SBwnxuDP5ocZCHKe5/YDOXM9VyjyQblGTL1gK3r+mL8FulrIrnVj19gVR
9rodbeYv86B6kCH1K/FKc8YKkmAkvyeErunAA/hB+qBytiAckgz1Ze721X3MYyn4
KfqiARWDCjz+3mT3/k0t/sJyS5MwTsAPiuGFFPRqsoEDpj3nzTPlF5b0GS4zSuyR
vUFswBy++wL5KNB+ovniqNh33mFrWlA8OdeM+IalG2MFNOnPw9eIZpwAFl95+gGd
XU52F3T31nCd8SiadQ+rW5h/4guyQsvS8D892TbghoRXhVREJLDfDMufpPlyiTXL
TJZKhQAYw0jRFt7655c+5MFGEtpi2wI1J1CtbsjXaOSRzLBWWUrov6sBnn9qLWm+
b7Uahi6urAQZE7O/o2U1NDJ6nTSUxpINi9Pez30oVCx4GKdxR/FBJZ7UKqwbUErQ
78rwsd1nXeHE/xyFW1efdo2U2e2+4NxOSXGYBDPyERv2HFJRmzB5Z9wUsQLq2+/5
3NC2teugUsdOzYU9tSrZxKMMRKlm8SCYNA7ZCUwdPd2Fa2Q+P83KBLhUQjQkycjS
QAH2Dn/W00CRVtHjek2zybFh7uv/3AZvRxzsfsH2fxiH7r9fNPj8W5Rn9EaK5/HQ
0GIyj72cFUY6eaQ06+QYUaw=
=i2by
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b',
			'modified_by' => 'dc8d196f-f7e9-3531-a9de-b15a3b48b11b'
		),
		array(
			'id' => '69c360e4-d8f4-4bd9-afae-204afdaba624',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAr4FfD07ACO2j+HQ+Dc5nRxEp5O+l9CLlveLz0vzhQLJW
PwC+4XCHoZngnOqIwsT0DdaSqeE9LHVQ1zquXb1k/emn5ayObb9unBU2PmD6s87G
oaSVUmnQCrYgRTEnUn8t02VE0FPTih875APeii2l2Y1oChFGpee6yXSmECcCdFJl
tDgH+R+o8/JA+2O5GZb3S+5BNGTXX/XQ23naWUbcik6lmPTxGWPicD8pYj3Tlh98
k7iPFhfY7gbzwLh21XTOSO+b+Q6i/rvpw9n9jWLKOzHG+CsCg8hWEnsjIagWGfm4
omJa9CM4JjEMq2hzWW3JjbwIu1NpBgk16a1OKduhVTMwEYVdL4x9vZZCG9umva4g
s0HvFOiHHOB275rdCw+0K14nAkB36TqRwJYomI0ikWEQO+kCViJslcAk/aMoGNIo
AlxVF9RbJXk1en+jsLo38O93xL3PCYufnvl634S/nuMh/s64ykcG4Lk2hqH+2ZHU
RVn1ywCkI0M/F+1gp0vHeogU2oEC6tN1Y2JIAUo8lEM42c7+jXznBfIrEhfBZx0V
hFW7MkEGx5J0C2k02AgprRMmxjC+pNL6rCTx0frdpGugupZ9D2GfinAvJet316lx
ILhRC3Las1BY+qjjeXIQ7wbqnB1tId6cyALShcdojMzGRx7xFCK6hDw+L9+sfuvS
QwHy3ZI17XpqjiMVA4gxYZ0ZBh1wzBrrvEn8rzDSUko0LVelUr+0jvFyGZrpCg8M
A1FcBAw59aum9qNvx+joBmAsgXw=
=kve1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '7031f2d0-9fe5-47b0-a406-d8942703c4a2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAAgGRYnGME29ZXpXih2wDNQp+TiYkeXR9Axm8l0VYWIUni
hV0zXbV/8yOQIXiY9dsH7h+0rqHTpOzF4Cdw8ITiahig34wiQQYeCxXnfUwizg/X
vJyh/orPnQr76R8O11QXWIKqfPgVOYETi5jS+YA+RHC2HAH4p+BpQ8rvJogDRcU0
9n1Zai2CSCb9Xu5miTdmDLSUYC5LZXJcrfSPTq3Murh4PhrlFrKd/hvyZUwISVFe
HVtIA6gov0v0EQ+ZFkLE29uUWDYpeVJ48GtJMkCxTh45XK7D022ThLxUW9N/K59l
b9FyJqQYLvO64uJ4ZsJVugPGk59G4RKQqYhrn9Gf/Jt2XhKl2VQubEwIjsupThdl
iZK2GRRCjXf2gdYUJWZpLykv4RpuFltPLiD8MEPG71tJ4Q2PMj/Xlwd2AgLJXLmq
O/rOQVUvmod9t2ltp0ddwMGVdf+qbBghmEQDxJHrz2mZwAb1ZYkyFNUOxDC84iPv
Ty6ZgzforOlzUSwECFf7WhI7ayHycgnfqQYkLfGeSzBufGfupmgfUHmovsSljPfX
68M5v59hv+8MZM/MjRySfxix/M5ZiMlcrdAjh7YA8o8HxxUJ82RCtDBPFAM0Ov7O
reQHotaWhdbiwKVlkKdUppZwrhJ5n+PDoXwaYi6dk6f1G59Uzh/5FiQ0CAdCO+HS
QAGgpeQ/jpTdPARbtJBpCKLDHkXwHIwloiku5N/h1z6I3KW+tGxvOyiFjJ2gWk8c
+MSa35GT51JrLlwJJToxltM=
=JQ0W
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => '75133ebd-bf4b-433b-afe6-03faf17ebe47',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '7fc9dbec-a5a0-3780-abef-97d97c4aaf46',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+NhoIcfSQUoIGNek538rUNE4GKrmFebe2EShi637oca1j
9qllceF2WbGnPqW2j6DfBJXOZpn33PwbmZk6xEmYGSGEBu8jfVU8g7AzHdGZ3oSF
OUXX57dInfwypXA+zkqT4HsG+dHg0L4fdm5o8ezbt2yiWMaA43e15RIvHn9Ai2YO
W2CJLCQSbp36d/P42BP+/4Yw3lRGEaNIrmy/5b2gTJugxVCHR3kzIg71lwZQbVlr
m3mAKtgha82Unk9V3k3UmbsrjFO0rPs2afkaWAX0s7Mnx/lDnH8LTl/8HpByjNo/
Ac95CO4x45B7xdlICatk7vef2SokYieikCY/UTVcCEbKk95TkPlAbs4S7kANM1Cy
2NLsNYMc4KkTcZJRM+k2icVB3GUM9LQaIQJkAV1sOp+OJRUYkukNNdfkQzzhlYxw
15zJlmd9lEPXOzNhsIW3/q4eUaj2ID5pdTUJwQba+KBrNNIr2RaKSJkyEtB5BhIg
8ZLSlPpSbySSCsGd9JUa5Fmc0G7mk8gjAh69gD4ytkSI1Y6ZBj51zdCKKOhwHyFk
BqYrQVLOMhEtnQrcI5XCasj6ZDZlGOGPLkE9SkzjyWnzJAQmgYqX2ZQC+l1INR/x
JydkyAZjqkAcmxNR37eEjZsbVei508WeLCD8y9WgMXIvSJKgu0xkBlHZ2FuIrfzS
QAF3RDAM4XSUMEEuMS7zZYYcRBH+ffL5vDubbNyIGcqYNeU3fJTVIEeq779vHIQA
0FwOinNDJcSoWuv/0ROknM4=
=FDdk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '7e0cda97-37b9-4ae3-af00-7ace4dcab7c5',
			'user_id' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/+OkJTSNn29Rpr3XlAs1W4WG7uWqB+PgiXBNVv/Bamcuea
X7JAcxtKjXbWXlxmSFxkpRZfZG3sWl+PoZflfQ7M/UgaBQcUsTnTNJXj9Eg5GCb6
EHFYhdQdx/Y5/xVztIY2HJjlpw5ED9PYKL+wew4rCXcQuCqvmVnV/lr0pduY5SM1
vXIHVZ05KSZfYry3F/eJ/zQ6ZaYb9Az6ex64kIqFlA5GulYFXzXvYH5L8p/OTE4U
JrayfC4GZXx7nfZK+7AloJmOrY+lIyhoXdZk55lfgMYETqTfzpUXW08HeDGdCj5C
ETeuT5aCmEn+pEv/Yy5rCL6zAMrGaqFJ6H6PPgUewygKJMtbCVN6/WhYmJ2AbXMJ
wDTi1FVajOQ0cdwF5X434VfP25y2K8UZ41/UduAriqkRRWwDvu7RLeMGYuPhmZd6
TW4olnNHPfXz5xtG5A397TbSdGm8l8J/BLgOgzvpagZ+j6sNtLk54ku5CdhlSlF4
3nruMGwM9Mt7uerD9LiVyrcieyzpRW9tdFZHZwiqn7MIuQkvZxfqfR3EOpHJcHKC
/B5sc2tqHIdMkRpHcts7nFxyeNS2rDXIvLlmzrXea+jXhr3osfxovDaFzes5Kwyb
PNz+tSRdmBF2WFF49MaMMft0khrQFHi/OUty5B3+lyKNRbL8uIolN0QZUOSrAGvS
QQEOKHbTcksA1sxYS3rFVacXWga5GxsStL0Wo99LaU2JUr4otG3q9IpnakIMWn4r
jOTH2Icjao2VWm6EzpQ5hrjt
=jtoA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0',
			'modified_by' => '5f924faf-8090-30b0-aed6-aa82c5e580a0'
		),
		array(
			'id' => '8179a450-b255-48bf-a9fb-57005ec50d5c',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAr7b/x+7JSWZGgq3O9D0GIFd4zXfXC/IluMow89rNzRmI
XzHhBsm0nA4r2Xprt66QMI4nEm7bjiD1E7+eX6p6lee2cotb8BZXcGPuI7z+TiUe
XJ4o21E+4OJ1Y1QTbcprbJczA7JFpEeZWEEwt5D1Rry56bu0mSiRnYHeYkfSwWPU
1zWtdhbCSkENKPOgXr+ibzB/3cXpICaIg0d84iBY9ADuJ9T+3e97Og5jGNsQKzQJ
kvVimJEwhIHwr6waXM9i4lSxiOE4FPYIK/nB0fxe8Q7F2R+Mke0oOHs/ZDiut5UH
IKI1WTc6VKbMYv/pqcTdYMoCLGEhNOPgnzzJxtZX0+67OobJ0VsfwkeHTaosPk1e
nEOb2GS7u5fj1c7A3IiIJ2jd+f3cx9LS12ZngYgG1zYBlb13iB7DLxlf8/MQw3e/
DAZsNPJDhaNxiapuwyOqBzMwrhym8Jdn2vhw5YFDkw3l04aLQcLZb9JVqIyKcA/y
dsZ6/pilHz+/UOBFqfrUyIof8/iDWo20HdORwwlhipcl35YE7Hmd5iQy7I2tWFmg
qmAvEQpVGRExtK381JPrVpjBJfFUP5XLoGaB1/Cy+HaGw+P8qc95tLzqHjkYNmQH
RjQf3WGvWYoQkjIRAuP/PUs55KI7dnvH5sfM3s79bi+HXXMBmSpS1Q+foxVm2C3S
QQEkbK9G9HwqkNV32YshRXkXNJebPZ8nRFPwD4bOhCCwUHcIteOFbx2iW0hKnqEu
PIrcF8kmiFANDmeRckhUK7cK
=0+P0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
		array(
			'id' => '8240ebeb-a686-495c-a622-48b888e5fee9',
			'user_id' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kARAAiSY0MEk3C5gv0IWvXLYMQR6595hJ3+ZH0hR/5/6dA0UT
RajON76gJ1a45mpD4VZENXLb6CaSQ3P9pJkG4h/BobdyAX5vRdWGNkLmY4NP3iza
LvbuHgNpR9mfLDiWL1hGYuYoeYHHThmuBiT7VgYWmNvQv6jejuIbUHArzMGSU0rb
0k9aIIBromE06Gqd9RW4fCEBU1iaYa0zmDXQkKl6cMiZ/Ev2LKtVsH62nPwvRFiz
6VNFmGYylDbecEvpN+TrJfGOU9pYYMbvmP+yn62GQp8OGDni5Vxm9k6FE3Gky2Wb
9KDFYzY/5HvdkbpE/rSFy7E2Yp3VlWd0/W/TmThKe1D/s1WoEFczvbSsxfRvlCWl
1DCvKkcwo0K7Sy7g9WMW0pzFTnRQ5h7RV7UEB+zJpXoj3GbVDGre59odmXFoQFb+
kvvuyjnSlNujfxBUK2EvPhnMSJ1R/9oYK3AtKPYfLOXfKZBU0ILn3eCPmRi0v2xc
ndqwD0xAPmtUD82IhKLcIiJcU8LQ2/MeYlD83fAN1bz35iYj1gPjDeF6ib1eeiez
1O0RRZ9sNGE/C31N55cNpL3lNM8rzkEM8Bw06Gjf6v+a970imqFCwJQgwn1JVJGm
TVif5ieOGCjUvxzAO5lncv1RedlswDwkT1ouyV7mCyRUtA4ER/6hdrJwzmt20/zS
QAGeI8BnTy2hUypIogBKo1muk4I7DuRQXSD0WmhVscgDmZiERedJX1PQtcopBUq6
V7b0VdwwDIeRdOquSYEs0xk=
=1Onr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05',
			'modified_by' => '25cb8c02-0973-3c2f-a0f1-41e6f5e06d05'
		),
		array(
			'id' => '83c77dd0-4002-4564-a6d1-3cd014c73ec2',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//Qq60cNB1sbNGxTwI7vyEscNOjUdukwNHJGZmG7SRJeW5
LWzWr/CEIryoWneWBhKQer8yUXzlRtK1gEgnhTLKr1KCySbT8oLd11K37NZL2DCp
GjkNeWsdmx4INNRcGJRPYn6g3SRMmWDUYHi3OsyaRMMiVWssgb8mTqsO56JygVKl
Bqg2Fw72cP7mT684UFOdlbGYtnZXtezHotyQRqAiMtb7Rie2mZ/Pz1XZMp57EXjo
WIeEa/lQduGtF7fjG/WRhBMc9X2RIMJ0RePz8SwDwFE0u4X0dQ/KYns3dxl/AwhQ
eY8wWgBwqdgOrRbufo60n/GuvCamoJhW+mFpTDENLEjZwv7tMzwo8pOhipiiYp9x
RPLngtfdFL/GA0qoM+tzIkWcLbCtDLJBLatL8fTMd20AoSYnXGCX0WvriGq5hvae
QSXLtUFrtaWgE0BG8mscUu+LnBfuPC0RcH+ilZK586nTcdN6mDNsyO7i9DjxlL5F
mk2nVuyuqY7yURlczqXqoZjFyYbHNBhTIhUG2nTBC3u+XmIml+EVelALE9xdhRGZ
Un3/jG6nTr8qwATo3b7HdoGT4DsSIrNoCwT6gkgsjaYGg8gvcZRTb85tuEMkkI0q
DMdI79fiq8w9h9Nv1sVGEMedifbanUeJDE0O9Gg18h5T/AnPu2b5ZMOBcXG4kzfS
QAE96g+YJoOTS3n6ROMRXozHM2cgMy0noUcrGNqJ/6KoRKR4jWKfsfILMbxyw9Cu
SCrI53A+Ze8/5PX4X8IHqhw=
=Tfx1
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '8ab54999-83ec-467f-a7f0-4343d15f3bfc',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '3e45ee73-6a50-380b-af39-71d259a7bf1e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//e7q2Ng8QG6M2qBvGMOInQOUbWcaDrO+GmJVrav/MVnze
LSZXpLiAkoa6dGIqoeqYB3/1yK1UG6WfzYCEffAvLje2UzzulGtAm5mUmQDEEjJG
xH8Ua/Go4DwO7xMLoFUrmaY5gg3ROJEWD1l88Atv0yj5QL2wZjW6H35KZaYED9rg
tC5caFtuxuah27Vh0sdEj0bzbd3zDNKoeN4Cf8/+gSZSGp9deyiqn4Zb3gnaXUOH
rRLyJkkBFlyBGMS9/gd0E9NQknjXx5Glry/cJVIbJEuP/K6jK6F2n3bCOo9aMg0Y
GqsjlvbiudP80FNtb2QLx6BWsWO0c80rPsuRxNQGaoVHl1OOWLJDy1w68z1GmLoS
hyct3QhnIqFVB+A8cBya18jRdeXoym75a1lAtqOTHfCDVHymFZWsvuybL+Xu+rhc
E2rPZbPIi+nQ9wJVLgNtQXTR375fhUf5ePvzhfTL7v3gmJHR6noS2UF5XrdoMiuG
eMOK/ztIGWa787rFh+6qKFuVQarkpFpkBC8AFWDbm4Vu326PExNUEY0CpMcsBzam
Txgebl3QZE7+fFcR5Y0OAicJarJu6afPh+XOWk1sILbgSs9J2sG8uZYnriIMBW4o
Pjk1HAjAqaOSsQRLzAhGU52/20xdpfPV8Z9KwDxwetagmKWj7aCGHXj4eWX5X7rS
QwHf38BayvP8uyRtz50+6fVkjyNE1vaQXZ/3ko+nb4mUI1i4MuMgwG5lAHCYXVcd
sx9emo+mggs4ADGHLt5lOfJoy1k=
=pUgD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '8bde3394-06b0-4fda-a8a4-504c5f95f822',
			'user_id' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+O7QYBP7T4BPTMav6EpPN9nYmDX9DfM7umx7WLai7xZCc
k1hTiZ3XfwkQkNenEgeg1V3LymCVPO8PrjI6j/MibWrqIm9wi8MDQkpXjhpVLSNr
3H/nz++pjQbtBrsquUiQ1CmqeIkwA0YQVOAJklFlBJNx/QIZ884nYeG2f4Hz9hSP
d0pUujkSHmR3k/z749RXMwztWreoRPPI+11V/Dvf0H9XWAUZ3BrNyVmZPqCBWCBc
s0IDUf31xmcOefMyOoQTZRoCA50hEoyFP+bPxV+05fQEhuXLL92EcxgSXfHoitYv
iqAoqEtanrH2yEbeSfCcFZ4fkxC2pcuNqH7sQL4Ee6IJh0dirzvap7e9ZdA3q0Er
weEe7+fti43a6vcXL8FZe+tsTUfGnezsL8ygmuOyEz0HsjEGvE2sN/kOClLT06tk
p1CaP6xdweI4c5kpXr4HYXWm+FUSr7o1CI46mQptGuYI4jl/DXvdSzHK46qKgC95
P+LKMGdiH3Q0CpeqOI5UvKaw7Wa3/D/IZZ1ntSV6Q7WDrHZx8uG4DdUmdxpTsCr7
7DG6pUuZV7vDa+eJfFJr9FA47sjBqHa8VAOJzgm8xDDgQPpZXt6bx1+JSlLelR51
0z2KHV/rCrijyUxG9o8HtbwCr0nZYtDn8pcUOA85zkdE3hx4ewKmArNQHNgUvobS
QQElTs0IK+IWpswDSGdAOV/H0gL2pQXC4MEKjqm159nEy1dKlUOAMo1Q48GOFyFg
tOROD3ozirVrk6hzqNuaL2iJ
=AF8N
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03',
			'modified_by' => 'c6855b6d-6cec-3f2f-ade5-398a8c4a7c03'
		),
		array(
			'id' => '901fce0e-9fd9-4e22-a904-2627d21d2097',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+PwUgKOjiGGtMuFBOIKi5MtYCAO3Kr3t9/XqfTkGz0jIe
4/v8rrRp+9D4uyDSaYbfFuWMNdqVGKAIjqcJ0RWvoT/Vc8qiAhTdfGY6tk27xuvC
NVAMxnwQSzBom6Ad9hbqTEqI72Z9WaPkHySPYUsNpqOLxeIawgWuKL5bEhhWJ5Zf
MZ0dJcRhQ5xJtc/A1B3Yixe9Z6bOOCYwJXFklbounGKYJVTMR2lw/UPBqPM4quyB
kiSWgOPLyfL964ugkiUM2bHUXVYcov4EngJE/gdNeXKTeqMrvKkg/n+0Zz6wUoAE
LySkfTEP3mfo8MS+ReayzIUTUuqrOwl4xh4/Y1OSJ1cygVkB9inM6LSACFLoDrCk
WzbeAg9kYv8fZFNemn+MHcv1Jv/Kh3DLL6aIwOK4ogY1hVGLaR9deAh3hfo4CWtg
4f4K9woR7+9aCISd5C7RM1JosSHdlrLcKxXZUqwDVpUMuXomx3TH54YnGv0BBtPL
h04qDDJoSCAAJo/+FDb3Ntaqt30Bv9jIB/b2Q0jFNb5evY9gGliV/MpKqHDFy2eb
99j6IEZhK/awxRTOwAV2EtBnB/mmm+S8E4OSub0W0bkbI8pcjDuJIdx5q2XbtKUr
R0LbqDxAAmwvYFsb1wgxVt2B1feNmQTXyVe9yCDeU+O+5xDfItRRTjsLdSFrb33S
QwGFoLOk1QW7tPZ/iMbpzE7MGRX6DKfNQVePgSfQ6QZUTMtMhb/DkRcdelOlxJ17
1cnuimgbRoYgogcM7VDMq3j7YHs=
=hFc4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => '989d7d62-25d2-45fd-a55a-d004ce27766f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '093ed892-fd0c-3c82-a2e1-5bccfdadad18',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAs7hcPhFmLLxDyPD8Abd9h8DNc5MCikThzfZKwEUqAur5
BAZzSdpvtZK3qX2QAq1rNQWhaPBSnoUGMdsHbTjbeHV8C7AEWkygrz/uEWc6MpXI
RxpxcL4HTmtPqsj5XB4ZYHrfnFcvd6225YsEXnvTqul9Obrx5c0jF5Lrngr+g9v0
zGkpY98e1jz/FdvSDKLovs2IAo1DEquLCXtsgoRRN3aUtA0qTNNEgaC90SsG/4Q2
e7m80gUbTqxNmZzx0r7E4mmTDFs16XlQj0mgJVOrfJoGrr9yY4ZOkXlUrswp4b4j
zTcw2liNZQQrO0d3EbaxneHyQN4XrpYCQWO1GA+OTl9sM+6MGVpe7ZLRXxECnfCg
2vj7lqfMsDGlaKan8VCaJhu2/XqS6J3wQWt1vJ6F6y14XxfPf9+KzETQK/FvGKp+
XGibCQH9+Qkd676SRP8Oz0S3CazPI7+wwITA3DXHfvAWyYx6qqzEasLx3muUCugS
NGdVTxL7G4hVk2qkymDnOSTIyzxxjaDnX0RQ7wKB6yWVTX80gQbcXmg3Obd32iHj
wAxlcBwyFySX4NoZ5PyxJMd3G+te31/v7GZYKovBEYOQbNJTVzNIUavImz5govaP
DW7w2QgWa+nul30z+IehA0Ig7d7qM2bZP950jvUa7ROQRLshfe93l9XygisqF+fS
QQGy4ARUi7Tb1DmC18YLSNcB6f7XRrB8vKEzgZd5E9uKHfICr4ZT3jjqc9muoBNj
CTg2Zug/rsbwXhyXGSGY8Sxg
=QPTB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => '9b49cbc8-eff7-4617-a160-9de13725127d',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+IW0DDKNIQTp2a7eWQmb9TFXYB1rhB+qhuO71UuDt6Loc
QfS+nJSCp6XrMZjviwq7kJcAXytDkvXgE3xFMFMSQhSDwjU2yxOSIR+v6z2HjIvp
o8Cq0rpO7au9rhcFmOgn54m6qNKX8jvVanhV0xoPhr4BwmMRL0Jk+6vigLwCYZxI
OKakXCfizsTNUmkLaUYUg1oVm+GP5fpEOQXu8ZE7TzDte1xvr/MghcEpTAqN3pc8
iZoB9XwCHCD0xRe7bbqSJksHcWtZWTlassZo5RD8NI7UCS6a06ca+yDqDudURkkX
BlDNugUR5tX6K2ObybWqMkl0ms5M9lpVt77DWMCoqEKuo7TpgFKilX4SZNTSYX/a
k4ElL2kMaPwTAVc6jAH2/VqNm4Aq/LCrSZHQ0K4rCw1ylnZAH1t3Jl2vK1AecSvH
erurXh6d8wEDWRoCT7cRdYAAQdsHLv7MqoZmFLpF1efuZIbSCDWaB8tRZj4RNiXf
M1JTbslCjuWZKuNscvj7Xhqz6PeVKGbymzoogLdbFieU/P5AVvruMO9l5GqehLjB
fgy4VkN56Hj3BbuNWkqVJVl6qzv+23Cuf4GzYovXIaJN8k4BZPFSW3ndQmjc06aW
Kr+OWuPK7if5m1OA/Xxxoz+PG+T0c0sKe2b95sNuHwXL5brZZUNOGeClqYnntBjS
QQG3ZpVh3YCBrYFlPBhreNNupy1GQ3o7EW4OeA4I3L598XjEMiNilR5Zs1VsYyHB
6Lpxl9YqbFh9Cvi+XwOrt1df
=Zten
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'a2bf1569-fdc8-41bc-abf0-082ac9be4bc9',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAA2v4Zq++bS0K7OIaBU/6kwiyhy7iQSX5QpSjjyIyTD3KE
887YSFCz3F2oEynXwOgDd7pFN/oUmhF+5bYXKKO5BGQN4UIvVyTJ3k9lNXAHAn1w
CS6LTegEDFkPdmAJh+MK0AP0m2IOogMY2ZcOoBqZGdPRFYPsYLBfdIEUtWkGV+hW
duOHoIp/XXsYeRgu2kF/coI/+YN+wAwjO7Q8cpmMy8ow9AwrGRbMjLud9wm/uh7h
rREHXOfpw+EAOc//kAF8XnvlCDL/eRWrPe7AihbVGA0SpBvoTLTJSotTJ+Ki5CSx
OhKo0Kw6XUET8SyVJMTXSCmEJcPphR75Gb0gqYWjLF321Sslogk/i23LbMncMI8u
yrEo6ZhdNWWNl6bOwXe7hOoIz3P2WvQ/nQ866jCPcC6w6q+awPIVPX8l8smJ3Lox
BcaGUINuok3i8/kXdtEOzasCu8h6NT8D2RIZn9/3mV3NznHf4CfIn7HP8VL4zsrQ
BJQV9WYKgZiwd3aXslhrjuAxucOzen34TTJ4ayOgzGK7//B6BcC4m/vRR3klUUJl
FpfcMx/DUg/7aKQ7bsZ/Y33KuYkkbtFmBStd3hlr8CNK4sIoD1it2R2MvvwWLoAD
gi+vQIQQw/Btg+fLxPldFL5MiBgfEMCbYeXUjIia0m3xNlIETGpcDYFfdkXyeC/S
QwExqAEsB/QY8LVCF4jZivOFMaRQUsHJMhOk0GzRaHPdqa2FtBNEiocFJ0W6Wmbr
BeZ9ukCLYy9omuY3dOmGgLFl/a8=
=rctc
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'aad379fe-2c4d-4949-a2f0-022b6dacad7b',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9EkoV4bQrFojwTPNhMQ3/PHNWXI1VVgyJDJlfmamaDquA
tyOqL00BW1My1U9RwgueALOH9ZDh/t831LJbj6nxbZ+PzkgLoE/vOXJiut2kzRpX
BBz+KDuQp/Z1Vy5LyjowrECHFuIO6hdcVa9F4MRCi4htNCbgnd7v/DFCq1v/ShLv
ybRId/SbxNt9TaIENYf3Wk8g/DLCxhu+H2OfjYrmJJrRCSI20vKABWOJpVTVHMmn
GSZaSi0EiZvSpT0dV4aSVyi1kNmQzBwUabJnFowtIcPePOeo6exQp6bmXL7jnarr
Nu1zUWtSghz/SRPHmiR/6dcN5XkOVO4/imuoymbMD1d8xNgsDoyxYeHi1YBCmJcP
XmNLOjIOxs4/mJ/LpCANIwmVeQ0VNZ7KrUqDgdpvs7n56huN0qrnZinecjJqgQa5
K1d42i+h0qIZJSVHWSS5XL9WeWoQZVNkt9kLdk3abOCb6VjLog2AbO2NHvzWYB9y
Dpuy49TEj1asTieOoIhaOFFYKQbzj6GBJkZksATaqI6qd0voJRTqXY15HpMO9OuK
z7AXwTrb4TVKWRVuZJ7WpGPfqDGB1MdA74eG/cSoxhvwnWhW1myKuUd5elPgApjp
kVeulEdBhxYwwiwFnWBpasZwQNpwf8H2dW8YsYW4V0p+NYtf92KICY/9YP36if7S
QAG35IGIG9as0iL+gxR2xneRgLlty2v/gv+SUErzQbNoDnBTSTdqqmo9t95tGqvC
AObpl7+hfPNqwfodWXqMooo=
=NnmL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'ac21c016-312f-42bf-a4b8-f5b16372df9f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//TBEx/mSVD4uPuj7HCYG81kW68bbvlcpUs/uTD01bi0x/
tBl2/4Z7vwQkIRIIHAact2P7pjbCljryAWs2XSpKOg/2gkfs+GWXAwOVH7mzgfKM
bY3Ty5P00+pSEBHUd82anZF7q/pxC7S9pIg7w4fSBgt+6BdQAYlj5OBXpbbNyUAo
sWG4sUh7UmCpHWO9GQkioC9Nz879CGMmXYOxuNCzY9hToyDJqmdXA1MCPAtlwVDq
vLg+uLsrnM9wZSjQrnqh1GuWb9nh1TMbzaK4zmBV5jtuMIYQ65g9CmTAvMv7U8ne
0IN55oTBtIDTPQHVxm+KGeaQe6Gm6s5RN/AzGtv/OdqUR5TlPn+ZTxW2rezWxZEH
2j2QCvMSQZeVDD/eGSrp32M/xQjuz2DIKL7rr6vBGqk+VcxFbLrqUuY+wIF5EweS
XxXCH/HTe1JAMobuZlHkQxPL/xL2sMoUJMyaZK5z13NdwYFwAp6SIHTmyIDHpo/m
ufm+5z/plLmbiPYRscl8TTIdF7DY7K+GJJcQzIWVpe81A61f8dHt6I/at4ZOz+tO
i2YQvbJEaMKHJXrLanBg2mDu0D4ThyCPgYzsWR80P+BmKl7+7hb9w2VMfSveBz1Z
yquMxifzV5/xuPuvCHOte9bwQSxxzZtMoZnT+yVUvblgsEKZp6whvzv9OBf8vHrS
QQG1Us1mbqwMc3A+rAi9+v2BqD94r4TOBH9K+bFHRvaV6opYMje+wviTdeXC+QbM
l4vwhWvoZOzmONjvRWPwI0mM
=iX7H
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'b096745d-0840-404a-a42e-cd0606cfd151',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'a1d6913c-e9bd-3b8c-a4a5-d8d30349b4e7',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAo19sWrC9vZbq4sa0RfAvCXcGLmkMwS0ShiJiCV+uvX8L
x+V7ZYFESN73ETY6ZzIWTY5AhbKCA8yCn46NAgayQQljK3gcgPXG6qwhgnjgf1vu
rbZg5aSOGcbt1qZEE40Fcjwh9V/TTLGstq29sdJNcF4fNXJnq43sJDATUdPnZDAl
CyRD25gLxrgodwcc3fX+66BLMWA0Vm5AqZYCHIE4uU9ZhwsuQA4/IdMqhZ0FzTs7
ym9fbQlQ68IANtxoVyl39GxfTMKSAxIPWYBEYv80pNLmi+6RwdvFXMXzid+ffGsw
uG/4dkUHiaBEbitVXLoZpOSENxjyjNZNNBKH68bkHDoGv69NYoHneyIqVei73Sj0
r1zxHixtPoXnsQ+eZumyN790tdbLRI3RmbNY4hU/xsBOdua/49H2ZgL2G7JsMZKh
DomB1XM1UKbV5B7s5bLofSuxtX+/8oWpzilnpINndrBEmp/yfQ7V8Q03CJk0LK9Z
PWBl2BxnBPTATynCLYPumzqwV7Y0/IvcApqqanVYyiM4Wl0TV4WEURGOj3oZAoc8
YmaNliV3l6MfzZe5JTXCYcRer7w4bZVmOL2kyPBEsGM+oW4jxxwrupch7kVAHSnj
PfBZMoJBbnXw9An+Xtzv6tJoiGIq0qC7acZM69ik1A4q7GA//nqaDlx1WxUIBcPS
QwEMnuopxIewd9MM4c5I/XFPfz92/fVQDqrcRG5yN5yDXW2H3l32mCT94jhufxea
Pjup0dPQTS0YfT706lf5pD4Zel4=
=gwJu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'b225ac12-5853-4bdb-a0e6-0ce2796fa142',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => 'c4c89e1a-5ae2-3ecc-a2f8-d77156c5c74d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/+Nr+Ouphngy7LLky31B+mns/ro7Halg1AshQXEPn7CtX6
vr2T3mLnybspJZ0yq6cPDyluoy7p8PR/Aq1EFlWY07u0IQ0t9OLsaR7JYFR0ERUa
V+tzUmZWZRzWgImr1fK7Dw7R6UlBOjymAWeF99T1da17A/R3k/mKV2Yz23urlb6i
pEU/LLRkJn8+hAmIOI22GTXqyV1+mSl3GSJxvlnHllW61vZf2Xxu65Qkp1WIvcPQ
kbJZlBF8hja06hXKMtwZ6vFOTH0ICe5mmqu7xeN/2euyhHTvfkZH2ZKri5c6BJtA
QbjfpPBdjXmX8wZeDNiDw0KDruQtuBO0WuYIYCDyVTsURUE23T9funosKTcd6b5+
iCkYZLJQF34cmMsNiRbwhsGgWs7DBedtHg4b3LeQNWVXOnlMQ34esBPYmKlIZgTW
DMt07VqEyNNCgwkS/dXebfkNfuwfFupapxx3klE54ElYw2CK6qymjwAWKR0y1x/P
ZYGsmOuvUeseAKtLg3SxAd1MdM/H0CBpbNxBMzQCmgJEsb3RyHSH+qcxqdWzFUNy
6FplU94iv/bakUZRBmKf8ReemktiZSGRD3/zpSEtznPYKo4uSd973NFzgD09Iu73
ZGziLw3lx9JUV/IGChHXArBj3PjUMOkmSq2VgcUmJ8vK4rbVShRN5uvLN4FWsH3S
QwGJKKLhWnJB0OYMuUwB3r1UzGHcrZwBGQ7fmokYL+QxN9ieF8REebI91Pg0KG8f
E1iC6fu9ll2WW95TdHxPFmbZlYg=
=6Y9Q
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => 'b407eb06-8904-4bc2-a8a3-6bad4dca6be6',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => 'dc990bd2-9a2d-3bd5-af30-a91bbff83b3f',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//UoWTxn6+He2c3VRMH9Bi9UX+6B3umRQA+8gvTzCXZg62
L/uuZIbZU9u68yI2NyHAl8w/KpQK7j2TMBOGM+odvKZbuWR4PVzMXX6WRJnsZNUf
G1tOxLoFqB4LSuQdTnhdVizcvpILjwZyVmSw76S/IcConJ6HvXaUbDAhkDdske89
2PSb/U+Mk50eDR3/7WO9iMmxATSt8G3huArqaeB97rAmnOJ6f2rt8OSs95Ee4xp/
KgKx7wAb2pIOF20yl+08xSIaL9TTXUyH3H300mc0SfEsX7Nz/oP4ux+Jjqj+Jn41
EuFAqq2K/yVtFpzSu9Lj36HmpSaXsxOED39etIZEHn+uqsQuEUfEL6JSXw1Iyobb
EKIY6sRt5ZroLG6TKty2elJWqGKwkQAwbOrMjXvQ5ACIATlrSHp00sOl7CPEl+lI
3H1BN22Di0c9fnB1LFVgBpQqfmyUtY6QSalJVnsAJSBoBMFH+EdA9A51bb5yM5/A
HRUqQEY1gZvMj60GGlMi8iM/3AmRdwaU2skwPDnHbdsSbPEE35Qogyl8cqCxgaw5
9hXca5cB5GeFwG3qZqiAklI568tismQlNrugE9w5d/VchDoJbUdNxX7rD12jrVhO
/azx7Yt3wMdkOSDjW2uFCZvMXgDkkk1ywChsDgIcgTMXU/E9gwdKKT1+3UHI0C3S
QQG4uFMlhbzJOTpsAmXdLn4IkquqfnM24SlkYDOdqDu5i00GwEBSTBut2H8wMwMz
N+NYwooY2ayfUrWU35vKyRK/
=6hyo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'c14ffaaa-6eb6-4593-acc4-7708e9ebcf46',
			'user_id' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//aSTwcWAm5xKVh+sZMTaNgjvWQiVMck/hiLCxXa9Iu4Qn
Nu3oLMKVWHwWwil1P47iQRjAq+VryVPC5bDIaqXggrgRn1oanKxARYG0wE3CtSL5
dTvF4AtKTMC4ByCq3toynjbvluu1uy1lDvSa3TCx5mJeUCTdw6EbiywicDo1qNSj
scJhV+s70p7kX3swW/mnab9j/kPjn9lFLXoVRrssMZMHcL6kOQrhnQcjgvjTA/MK
+AAhTQnvRf6L2Aac7TX8VVjhoZ2PLTVbxqa9uFnv9JQZqz5MeCjXGgVijkio1vKP
fcIqnHiuXMhmwBdHM/ujR3jtiTCWPS8kgLMspApTGJHrpftmRlTBB0RBZ48TSrnY
/d+oD5rEFu/5THopajYcW7EFawIJqK1H6VfmCS5MQeVCfTrjvw86AXYiW8UWexv+
qBELVIDP+QwF+6OerEIGl1JnRIqXtQBc4G6eiFPh7odAyBRMb1gH6GokbhZNOGlY
RU+YO8CPjHeshKzHI0cANSmvR7WIXp9vJ5BvU6FyhauRP8FzVouwnR59NFPRyTtM
dJHtnhxwCEgJh0K+gfqCXdtXG3g5guY/5XeKFVRVQTdyKrO4QABjedqld/9WV1e0
/egQbQncDzzaOGMvFziqVI+UOSIlC97XQbpKk0psX4ZIrssUcUTQpw3chFB/QanS
QgGsYBWwFiFwn6uvo3sBlABAXMLUb1WVd0Bk8XVBlYqbGL4rYbhLyQU5/0FAAMKF
xi6GFdaaqURzV7B4Eb7ZeMwBzA==
=tJo3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b',
			'modified_by' => '7c7afd29-1b98-3c3e-ae55-adedc333fb4b'
		),
		array(
			'id' => 'c78adb32-23d7-497d-a41a-0a9b939850b2',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigARAApWwT7aPaubQOEYjXcqwBeSBic6coR1O8x1xbGUjqySOj
RosQFtv1hXSIcVCHZiSRRfOjxyM0ulNdwPqSiAWmxoiNqh3TuQ4+S1f99jGoY+Mz
6qXI9fra03tfZKSMnvKkLQyhGM920SBvFIPYj4Uw32a8u/fVsAYwlL/jGlZHPscB
8f+xTh7Xn0umFugab1Fj/nYefN62wR64MRwvyXIy/crJ8XHoYsNVkAETwmUhj7T7
Ur8DHp+d6itX3DCdkz0+YZyY3L+AStsHLLmTpMzw+TIZIWu0rjlJ+hemOwp6TYRX
7YyuWdAJSoQ8WtMkbZaa3mSOaaJEIubwTFphDAMP0VBwt0Y/WsM1Giinb9K6oio0
vhhWuSs86/ghqfa2UdTZi3roJ8IgfFh3iSTQSlFSWnnvpRUI3PXLM75VpCt1qvZ8
NsoAW8UblIoaf6urRyTUq8J0XGjApKq1qOlMY22IyijSukpyR8O9Bp4O+lIMB/j/
V305aOcInh0WfRQVXBEAGRlxuSYtHMOmar0/ExzFqtCGB3Qt7pcFi4Vj01BcceGQ
1F3FvQh4EW/HBb2svVbtDhZWQjaY+uEc78UI9dv2Z3loUfCTTzcSIzjM4xJ17OBP
IukottCteDBSFKM3AqdI4O2BCmDYvaFa70OlvaXdoqETgJjX/jqtAOSwjza3DjjS
QgGdYxoeDVD4b8LRACsF1+etTBitEEeUYaWrxvtVoloxxasRJnzHHxr/7e6fXMud
lA5KCnFYDOYre06hZ1AXoCRceA==
=wa1p
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'cc8ff705-d9c8-445e-a761-3dc1e0922dd2',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQf+P47EdEefGxdvFb+1nJNrsCJR2I3J4eII+uIcDZNpuzjk
3kJaNkHfAj703Jop7vh8Aqtm3NjTK51KHuWyWM0i3svBEUi6isio/SNwnDQwqaxW
0rV3WfLejwLsD0yEu0MUXsrCGIq440ZXhbrWGugHypvS/kRia7TCqMVAeEz/WjHa
Uz6qZvnYXqgVZLCgVb6E2PHkXko02M6Ja4URvbjRInMuyW8fLVn1MTP65LedMbsx
28f4HmTjvkeJMDGjJv75juRCpiWISWAo81dtZu8I1OWjkb1+W2cPHTHbMDv/pEnm
0JRIhLPTmZeftsB3+VNv8+dLqiFmi/pxE/4v+C7sytJDAW6OILf+xGnD5CzBPRDY
ed72XQhJ39CMaAg4/rP9hZe+S+/PSRvwiNPD0qjXGfJBVnTN1gyptgoutKK3VXuj
1shG0g==
=ehnT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => 'cd165ea1-49bf-4d6d-a4d1-aae30086a018',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//d9kcJRAiPRVDB9hzLwoicL7bYcI5kWj8T1RjUM2TYR7h
IO6cdbAMy5PofZsyrkBh+7eXOrmXLxkb+IdnYVQKQ4NYUqS2hg+IuOVhi9Fs0Tu2
3x/SJ+AP2yMya/A3FpG4dHzUT+9H/jqNiaZKcMeOgJi3IXr+g3VbRFPCXidAPPsj
lo8Cb+ldVfxakzwjTTtS08SbcwXdaOFmPk7zpaSDWLO9p7QSfe3oa3xvuc1ReAmG
reSO2s4zpXLA+jXJtbS4ohT6iWavQOIpTaMxD201Z+I4lYR/CW+ECINusekWNSEr
TvOrESMWCYkipvxCldqGH4ukZymEvLzc3Hf/Z+jvzd9a6THT3Z/3W6ufCALpzsmC
8ZXbChq3FRfncn4rjgZc3+apgX3a2rwtm+oqzGB/rNp5bgpA/q1Uq1Q0q2nhT1WU
RfAFwGiu1wOIlEk/xg0YL+plNbhcotAGal4RcXx7Wge0hbGrWFIvcOdljBOoJ1Tj
L2GZl9SPiqDTBHB/bmRW/z+2rCm6SDgbtR1WkvIBB6ZWZUGcOqTiCiQBhJKgKk6v
0kVXRPaGMfaaWfE9QoOv9rP2anNhfhvkjErM/nmnk7pDNChtz+qJr9k0n6HGx2NK
t9PRx4iz3V+8Duh/uWrD6VcQPvShAHhibowVIrZDeWn/YD0XD+eiftSiN0SUj5bS
QQEYPwxdMg1Qc+/81QntkunISFETXt931A3rhFe/SPLgIGVYQg+LS+qXaO7xtAuw
OQ/aINY42zX4RsFSiKoZgnc/
=Aw9x
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'dc7af9e2-8e7d-4dd9-a01d-af50a00404d0',
			'user_id' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'resource_id' => '41099784-c61c-3172-a66d-93f2a4702a6e',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9FDOcp0o4Rn4CzkLG5HOgWmNTBTSeYUg9BfKQBQN12WNE
Sm+YdmQLQHNbrUak+/dj2gKUaD/zs9OQQSf8RNGiYQS9EzjqgLkZrZPaz9l0uLo7
cslIvrR457QqfkjW4RVJxfgduFDFpLrAQshzvbRt6xOFE1wv3TauLqIhd1OdrOlh
9ql1oiMrwxml8NfL10pXGO9rTP9wEwA8a4cY0XUKh1hNsiqABSe0UHDBMGp1wju+
MTGF6bgzH8g+HfmjkQdHdVEy9CC8c0LBo/UfwoM1TBqpjAct7y17Ggi/mkaLD9vb
xk60OPT9+RVUBb4SKn6+mRio7GPkelZWDx0/yzTPy1EnGPXST+5Io5IdnvTY28sE
LPheCmn/h4wy5/fIkfpPPmSodJiH+/pQjUenFZMIqY6dGApsAJ9RudfnpEEJUloZ
x1ZpdJCys6sXRdrOQho7l9w4a2L5mrCY3tV27b5E9sY2nGXg0aib1tusTd0BbRxh
rKNP70Kqqnp7Hm39Ayh2QC6B1Ckg6awCLbp/V/udGyG+coHmSNgwKgzS8iPO7m+C
506JZq70TejmlkD59sFIvMSPKSK4/cIOcaqhQ69I5hwRKwR8Eiu5+0aPP6xB/t01
GVmpEUPriN5Ny+rmJHZ1z5lQ3NAPt695J64QgEEijkO/GKulZ4uYdK/oTL8haQTS
RAH8BFMNPCu5+/UINav/7duP3HAdGZaE8pGVHBW6AjEF23MUZhLSey5/PfgokHvH
3QXUpjPxi9KEiFYRjkctp7gIQVW4
=xeMt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e',
			'modified_by' => '201b442c-d6ca-3ee6-a443-ce669ca0ec6e'
		),
		array(
			'id' => 'e0bdb0e2-2396-4bf6-a9e8-686b54f278ba',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ//ZnBooXhd9iufNUzAGDOX8dWNko8INwbBaVDNzBzruabY
3kPymiP4683N5ad/angX9oef1jKFdu5eGBkZdOaa/KxddnCOOrsmWiZU5IOVj7nD
es0hG6O6C1GJtYWvfgafjkV6E9dccM/5LBlS8spm28lrYC6M/IuscBOm/i04xknO
oa2JJ0jLkqdzGqydo+KntkgLGH8SXB4s6h0ayXoB5CiQNAfe8Fu+5Il3I5Szwzw0
OZxMCDbZch48dEg6q4iHXgf4R/S2dBDUdJuxbg2H+aumXEn3Fo+tzKbMT9rvteT4
iGefwJaU24WJD+dJMk3bRO5aONGKq2OB75pRkvyVq1IMWhzN6z03TmyM/Fxgkmux
F8VDmVi+e9rG44G3b2ZTcKDseRPlaqjKwLb5luPyMjaSae+Rr7dLGLp2Bq8G4ghQ
KRYSal+olJfkjqh77ykEUufdpJ95OP3Kwg4O5y9Pe29tAuQYD9pLybO0LV0RfDjK
d+j/nxCAimuNikj6TIhdGXqkQwfh27gstbMBK7x10/VIFynS0vcP9QSvtZhoQlkK
haJyQ72++7onW5/Lt/1pmF5Zyyt7TcGwYFzbzQwo/jSgDA8l0EwH/iIgIOTd1zkM
ra2lwI1VW1wBmpOrcAnLGzQSM/SqvmZzQ5qVIb7yFbPWnd/pUEqvJyO11VXI4CTS
PgHdBzyiBT/lY/folHB0M2Zf/rBX/Ziq3ooJ+xtWZE2AQ+5QXVxP3Xjq84wEDAA7
BNnqAClLNHj3c4TEpUFk
=iGEe
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'ea89b65f-94b6-4364-af27-16aa3899851f',
			'user_id' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+KFZhC0bTUcW+ytzIOLjzNNKYxnbdblK5m1WyHts5HCLj
5vkaQmPuijFF6f9RHrT5uJ5R8EVEs0tW+LOgHnmO5xCKHzsOW4Lt3E9oxvNOmiey
ChU8a5g/OFOI0B0OF4tdrL3yO9Uy9MxUtyrw5LzYnildrtOkeFPlSpuKPp+lj3Nc
hXfaRlhqpg7KyrzxjR+S1JhTmnrBZIK8EfDrgBZDbhqMQz9Yc4FQCwzGXT7aW2Oi
Xd+bxhKk8vvAwTdNtZQqysY8rXpf2KNcPXsiJN64hLS/PJofi6BlivwCVbakBMFU
TeR98QRShWndz+k+HU1eYTY50+UY0q3Ygc0Q1Dh6ga5pbEwvmXqkEhlL/Ho6as6T
ZGLFjDyVqOdQoiiFup+2o+Su2cgw/jhvtinMdSGgGmKe1l3oaub4EWxi9OkIef8G
QG+vBI1n0UfPTw2eAgMMbZM0aD0ulQk5xn61uSmsRBHuaQsv6Fio6vC0No07hv/h
7onvRSzrv6megEQ7DzYm6ip54p05NXG86kRxYqSxevUAUo4YaL2+w6tRjzzO9qmB
auGW7WNSe4ywTnRlCBUBsED6mGlnnxyNvFvA7JzpY4bKhfeXBHXE4Nmkgn1WetK3
sU/VTsMlhTBjbLVi9fLEP1wc7a/oXywlLlfJAwc1HF1n4Q6sQDEUFAAZRscMB3bS
QgGXB+wvQHPCl9lW5CfAhcZTdRg5Ptp7yxgt0oUiyUmZQnMVb3hZ/xDe+/CjBYRV
4xGwHOrQoHm7m/9EzjGcDUUbdQ==
=lqpa
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8',
			'modified_by' => 'cd49eb9e-73a2-3433-a018-6ed993d421e8'
		),
		array(
			'id' => 'ead85442-54fb-42a9-a776-deee355bfd5f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '63701f29-e6fb-3e3f-a549-fb7c3cf9a1ac',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//Uut9n3Gxdyfbg38g0cf9p9gjpaz67Xp+oj60VONu+k8m
oXwYH4P0fA4kuHa/GHKmLs4r4s3dJnpKYyZTmac5RIRlUjlU0+OAmfLfmxbw5VK5
BHpEfF8jtNPID1mWvEwGWnLLU2TM4vRRbdEbCfzboCgtRw2eNrIhssna8e2Y9FjY
dqXDhdY0saZiDLSNxj3E+ToKvVKlA1zHornbLGMWrmSNccUlOE9RXK7dtQ9dq3Q5
gYtkSvr6zvgWRaLNCeT3v3lANQpV6IpDB1cbAdMgb9AByYQk6jq1stCbCcVCs4Wn
E8OYvdQIYmjxPwT8ZBVou9pYR7L69kvsUXDv/8ZWt6e7TgWwEq9TSRsgIJ0qS14f
4FIzorMgrrRWFhJLwb60ZudXV5zk+hmhpsiBQlNsgGDzXZ6wC3KVhtUg+XFfgwBo
ePcVXYUDU/LbMqfU+A8EgDTFTzvAutSVJorV4UHyQyQm+WXzWLo7rJKTpOu1wNnJ
MdapSPneXK9cNUdQLlQj6yQ1HLkn+aDWz2fn3rW4FRPetHSHs21tdr3pdBoFAXPE
vS2wAmAQiovSx5fmsKU9ZBUvB/aDrklxlhoT3NBjcJbutzIDcjchIXH7OxGK7cxL
/GlaOK359N6oTo8YkfdqHFdd6LWsSxSX8Dl0xidv1+pPzawQTx5CJpo+3qvf+6HS
QgHoOYp6sZTfu8AHhHvNAq0BzKO2CymJe0kH/YkQn3Ap5nF//Hg2cFu3bCaS44HX
3QdpR3pYUSRtpclFS+60TW41RQ==
=tJK6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'ebdd231b-f216-4e05-acc6-2e74aa72981f',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAgnTJY6aq9rDxatYlx+Ej99wh/9BtSUL9T8TPvevRro75
yozOw5sgfFIKpt1AoZND/+rJ3h7c2FbPL2yXbQXFrtpf4yUF3fT03G5yhI1jvhCN
4KyuuAouWmD9glDnxzzUucS6485Bi98wbza3bCFVYJl2CJsRCEM/Ig48g9LpSLyl
Y/9fTA15YXD9FT2+Is9CHKlTxOjEcLf+YCW724dozddR8RpzpAHl8q8+BloMgqbG
ree8BnpIJc+Oi9OELQ+QZMfkAYghtID9B/piOKXaVAMM1NY29C52/eAeh/fhEqpX
GzRxYyYsA/mYcfX9+EjE3KxkqpQi8gKA3eBEwaR/r9JBAfVgEWMZblFaYuUdDdsc
LWaLlmwH8PCKCyJY12otG5GFPXfQAYAb9wONCKgzoShPzi1lTxxyTVsRhC+4Ov+U
J84=
=M2t+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => 'ee9ee14c-e359-4059-a81e-ff0e07fa10e4',
			'user_id' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAyQe9uW5MLigAQ/6AgjkVTV94DJKc6ARSytxayeSs236lMsK7dcCKATNaSrh
sA8Bp0oeeXcbWnOryeEv+leF9ol1pVb4F8YOc/ZvSHzyftDoboQywxi06pPF89XL
ZMwOXorP6ZIJVMAicsi+LNVg43x3/MuQ6uxYVpd21RpXn23ThPQppK/9zpoQBnub
SrL/IgoZVH+Dg+db9rWBcopfrjcESQ9QvzxyQJUw9hvEZcdreLypIA6DcStsGTAi
tc76peHrf5qaUrWb6b+Yghjq8XCX5zmSsGTkZlBvsPRSZNuB/mLakLPYTr+Cvke2
s5f/SVGcIfEEIcHxQC82+fjU0SyvApUrNUXT7ADsrf6qGdy0SCBszUNTk5Vu0hLr
q4OCnXE9boOMDxNtoIyw31CMuRxsP8CdUwWlRp3EBWKcbZY6j9jW7PVphJxst6Ui
mtsyJFaRNAqWPVcssgr1tB6OvAMYYi1NCco1Y+9wNxAhLbpvALI5K64FvMt69N+6
H9sRIzoa1HJXzQRKLN0qSt7OduB/SpBymAxR7hYcEE9yuJGwCKlL7wdLNWbZchJs
BJEhSgCPoHPSTo+KFKJ+FUVg+OLo0M8TIm3p+2FH2zcJ3satKntqzcsRybTfYGZg
5J/zGmv5TT3x2EJtDYJBOqbbgguy6OHCMjmwVU/NasssvCVGT/JGKqdg7aWHNYbS
QQEhiGnIdpV6MRwqNSKhbxs4CsFRa2bF6lccLKVUlDGHbdaEMUo65wbuVvo44ggn
DGKZs5J0131vIE9DMwnTful7
=Pw7y
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3',
			'modified_by' => 'fd5afe41-1c02-3e14-a706-fe6dac8503b3'
		),
		array(
			'id' => 'f274c759-6afa-4841-a413-6b19fa2f1db0',
			'user_id' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMA3nDbC/XKAhSAQgAppEwW5ZzK0lHXZx4BPqnQ4D6gXXjrbD00Y8zso/MOh3u
dqA1lm93P3tv0eUyabAL+ZMpDUygGzPeHFLngDvvo+2s5aD/p4xVSg+aYeYrQtrZ
0qlesRQkJejMqqbwFOp1/xbzzcimff7GX1O/FO5Rnm5gMGRZiR/QkzInVr2nhyp8
k4TCZOXC49yoZSgU5GGAemZQ8RRGG7CsLzxvLNBPJMOw8jsPiXjQquptjsNqykDW
YPLdQRUEhbxyvIez70Q+ZRfGbm3BBL1Jad3/zzCV5WfOngvDNfT1AGuAlRnkgJ1P
i4VVL7SstpoK3RHmhu79nvPloICwhk0U1+gHCJyt/9I+AV288DV6ZAXKlmuPDgc2
tURiUZQum/dHvYSMoqxGFI6n7S8lEtgsS/vcToIwC12K+3Cq76Zpgk8F2ZyJ0H4=
=y6xi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123',
			'modified_by' => 'abfd50cb-ff86-36c4-a8c8-af176b201123'
		),
		array(
			'id' => 'f4b65b7e-0ea4-4634-ad7d-4eeb6c701dbf',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAvdmb1kWWNMzm3kwS1VWVTDWYDE0wQULkXPloLUwhrRY3
F2hZz9CfZ2bPikZhuyIpYmWGKssTSqzVwE6PJktBUaZxNYR4b8pAq4APZ7aTgOx8
CZOq1LOKpIfmoGhwxrztSzHeDSl6cly1scbtNxA8CPHKtSu5JcbJWar5JORwLiPV
wH0AmX9uTbsVptCuifiuZIl0eQj74fuZ0urQ2gNE2bqZHuXR8DncDD+yqajCo8xy
DcjpQV5D3q/H1didBznLNFGcTaXMk/5Mvs5/NNkzInsZ5T1n3cVRBUZQpwFtg6kT
vxsJ1e+eKAc+pmjN9+ZeSM5/+gwjNkeCWdtRJjQehaZJfEbgAomeDiQph+mnDot1
i6VY9ows0xystYvHTvYXcpDRSI+auuKKW5h15H33g3lSWM2bp+hKMHHdW9yXQ2bq
Ura4GHYY2qSicYQatoa8sFTcD8iEjOCkk7/LGADGbPeAOgcKyrY3hJwY7Bl9yDBk
D3E1o2Ec8yfaxhwEZC45svnO2nCpejamOsDrgD1QLlUfY41TNz3IMW5Ev+bwfUoj
HU21pIbfp0UiUW62uppPnJD1hVoKFJhqQkIXUIjMutDLVM9ItmUt6MlQHE+Rdnei
eXM0od0ICAFDfAeTZht1f/bHPJPQqsk3QjnrWgS90BAU/bGwG7WJ+vzOzTj7kfbS
QQECVx0xnLVEeWOfbNlfnyTC6AREDrTTgo85Ag8wEeFJWsSmaE+5nJXwV+IUFD36
WIWZLtwTUNYZYhmiaj8SUaU1
=P+ce
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'f6e94bc5-befa-45cc-a905-6c373240f07f',
			'user_id' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'resource_id' => 'b46ff445-4f22-3f52-a070-995e6f4be909',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAsLL8xgeLMYO1AGpYDU8mkId5V/7QAIQwFxivOUMA/YD5
ba5F9n2CWXc390TPCq2grVqYsj3ChHwIGv+cdL5gB8I6vsAsUiMM1wZArI0txgzY
CAzG26J4FfNV/VRq0e/SD5y4jJXCzBlZwQAa96I50tvKDgtJYKcpc2T7WRTmojSf
GJgotdIhmGsGnfQ83iQGkdi633ES04LfP4+PqgEqOulBUeDxExVLOffoSU5ee5Kf
T8d8L55DvX5r/YkEh9Jrsz/v+198DvLyRhWb6IAKzoeYFZ6SjIoUdfxUNofe4rpE
njomstO1DzfYRNbvOJLRVL/Xq81jnrquXi0Ase5b8mMGxg8MCm7LbG7wLYr49av6
nzBaj5R6OwHxTZ7QC57q4Q7pB31TIO9G2E6nvVBEZAhbYpABKEhinAsBgPKk6es8
6zwrlUJXkNZ7ZvDSK+9yxEQ4fMaTVSLFLnggPf3xPDiPod9y1ugL9gRir+6Z/YfI
0YqD5bdj3h9JyCxYgdGsUoqqQ/aj+TuYEvAw+YYS664JQHEfb+ASCGBj92fHNv0b
zW5PW7VWQSGZrS/ZPBIetZ+47YGgXRXNq+QQFlHC7UJ8z1ZQsEubf40O3hnLtaMg
PIxnR5NwEMRU01xyAYKEVZn+HrhmMQvjmn2LtdGzm2tbiBd3hY6DhstY/Ydh6mrS
QQHYRLnL/2IFjZcF66pDCHSx4QVoVEUqnEH4sf9K1cxGLqeVmVQk3qGe+ess8eOO
324192ZSrF0b9u6yuoZzvdT1
=0Phh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028',
			'modified_by' => 'fe8848fe-97f2-373b-a984-6a3eeb2dd028'
		),
		array(
			'id' => 'f763e963-30d1-422e-a822-8953f4332681',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '36c00be4-64f3-3471-aa0c-feb1bf6ed79b',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ//QkeWN2PItM45ZmdRosOm1pwZmnV5KxkUkxIX6QnhYYOn
EpzXIgl4699gR1wkXHF7ha1ZbI+5LojznpmsD/plFqgNb6EVNzSGN9aOWyqTdP2R
TMiY8jfjCe59yE7FofIVpRx5L8WZzEUrbKPZa+MRleE7e0AVw3mttM5rQVBiWga1
knqR3cOIkdcPZMWQfzlOQbb6ahzGpJ69itTUi89viI0HLrb68xlMhSg9vNzKzna1
bJkA47mjdDhi6mXuOgr1IN1XDBQlPYO75/K5jy2j6tygMoRhK8zQuSf6diXifJDy
keQ3hz6S/arY73blrRnoaGSmnD98vq4EXfhuz3OSCx0eXcI/zu60Rtk7FpZ0PjuF
rj4UMdSm4qH9d8iw1+w+h4FosWb7g9omPoyNMN8HEJD9mSL17laMZ96xCN0pq2Na
ZpaJtVaGRyjsFz4mHOxTZIWjBLcAu5zoXV0KQyBy2DIJhQ2J769QfJK7LZuOycXK
zpEDwzcjEmtWHUDNHRj0rMqAIuS7L4hRNnke8fDrgin8aXdsNIKVfQ9L6kRhxJZ8
uikHKksYJrWp0zcs+OLeILnKhbpM14QjHQDJm1o2eEMzuCvcyTWC+/KtbbZYuo2F
CH/wiOu6LIY8NDOALusZsTKKEs1G8Nb10zysqblBpHH3E9sQ+MSjiU0R78mQF7zS
PgE2rj+7FMv5yB4Ib0HwHfDPPn4R91D62DtDnDfaMq6epX4Ggw4E+LRghRqtD13w
l97I9XEPQ/iykvcgeeFk
=VRlL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
		array(
			'id' => 'ffc10f63-e2f7-4825-a572-6e46e51279eb',
			'user_id' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'resource_id' => '349c17f6-0cbe-3c13-a19d-87452de6a811',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAppwoTYMd0vsTIKN1YkMdIjsaUDrwxJBcNkwQRH3KeCnW
6p1LpSDfqMPhlJQH24HmDkTZnxcn13SU85k5nf1lSlOqzmoTy35TpnjsV5VCUutB
tAR1APbp3Wkx0wWpw3mk+junfZv7KIjL7TDJTAk8uSdwAGf5/pS8Dde2/HM5Al7e
Nd41vLGnn1oc+qZztdVWj8ReAy7cOyrHRWMTxFV8UfSoo83OfKOr+6XNEJyAP2S9
7seqEdTY3G6AHMvb52gPN3rUcj2ryfPLvd7v9znrGqMh+cUOjrLbYrDMxBFa18on
H0q/tZpqdBZYbru/199WyOkeuLlk6oeBdmd8fz4cCHPrEJJZ0+4cELZYPSZ8q4/+
T4x1EydYAiMstFYBHFyCGIxmmHkuQKpfyuhOq6cZLtg5XH3WOnJtADX2jLvKJbLK
S4jBVqEGl8EdUkEVoAqRRLakq+1wS9xM1JycSo5IU4g3z5gMKiA6EjasNgi4eZwU
Zy8Vd65Pybfdvu8D+OwJ5akK7oB4TI9FGYf5wYoG6uOgpRO+GPdGTu+vCmRW2mJK
wljgimTiY/tmZzugdR9WVumhycH652wzsfAC0poRGeyfT6aghaGQEcn5Kq3oNrFZ
EQq4f0HFUAZzTIwLky0CPFUnDM58ImIsLxEGnB+treU3FFNAdJsEbjU5KGAuKyzS
QQFwQKJFqn96gOQ+pDgUn9nY0HHg37lNJNFH8NZ/RlE0EFuQHMlSli5koPr7waeZ
/4LhW6gBMx+r2qdNUtrGQvYm
=LVOn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f',
			'modified_by' => '917c2b89-d573-3c7c-ac4f-51f519d0164f'
		),
	);

}
