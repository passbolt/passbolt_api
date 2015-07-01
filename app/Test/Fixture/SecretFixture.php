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
			'id' => '5593d18f-0224-4bcd-a5fe-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA9QpT7WOXdtjAQgAoKXLqIXLoEPNzVm2oMOyPiBK2Ss+NXvdGAYKlJqL06GF
1/oGwcULZG0PBjNCQqNHAZ3QN1YbdIHaz1ReuVWutHcHOp8hXxUorvQPXL/EWHpN
+4xx6CLxMaStM9NrAlk2O58HuofFlsNHrWP9+laHpQEYS3kEQDdGcqcKgSr2DzmQ
HcoJ9NaUC1Q87/PmfHBrpwKwCB8i7RdaQtCtD77ntFbMMmQmDHAg0lOhmu85k9RS
iDdqzhgjFgU2x1F/d10Mq3PtVcPnJUvzy0qy/VPDJJcwQAAEW13fq0ESo2JadqNj
ZbIrUT9qlAWOPes1OrnQV4cS7NCufpen2cBN5HYFiNJHAYHDUebf25xmS09ILIPB
nU8+Mmw8afQ1Blaza4Qz4AaplzcGB+iWFH9eovTdPgt56cNnAqTyQogWDFRChN9X
uiXmUaVsTYc=
=Hd/4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-16f8-451e-9c1f-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQgArkq7MWxfKnb24zmxWHij0WMU+Uc4pyoBhcp+RjEZ8qmc
w99MQmGZHTR7a49nvkoDfbJspluAEqYpvyf2WCsnP9KVkP49aBGyPo9tqSreksiR
n8803dRGTJhWw3ZKFwBk/sf8/qqNKR8OgdcIYNkACB68bFxWp2dZlxTilul6DveO
1+gYq3eC1yvNiTTnUWHpGugqRg/sN6qeLACFguvcUrUzdNKJOvJWBtRd9h7j+GCv
KVqFCnOwxn/FVByYUATxAzKC8LCaUC5voXiQqmxIBkTaV/sqQdEVIJbLz85O1cys
HfvjapQrsUpG4e5wfP0wfoS1T7CiKZv/KzPgN/2nPtJIAYlVOnitXIgliVVeP95s
XcCx63RZWpGgsHFZdo0lJVlGA7GV//xOkaOkGID0LlF5V8etZ4L3uEuqYj3vzTcn
Nwaj+/8NrnpU
=+hup
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-3ccc-4d68-a177-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQgAmpDES+ey0FU3gYEzsj2sV4u/C171P4tVqqkS0LisocgM
J4VndWNqZyM7JS7bacHV1tdqv6JXzoH/lmHZBZgypTdPCg3ehHaydSvRMJ/QzREK
hshUBpPSZglkt6GG7YLBoREaciYT7dgB4yMbJ4JLQxAxwknEdkbT/7m8nyA9zVXc
n2XunGNV9iCB1yFzXex8dRh/Vk2tzSK6l6tXJmFz4ueernAybgYTbSz5SPyGEhc4
VL/nMG6cxvbsMvXyu4cc5JH4NMH7zLRpCmli4D4CyG4FYeMyn+s9hT6rh6QuK0Sn
Kik5hmwHiuvYZkotIWHDBR9ILn4NkbKelSs09dU3b9JHAQjP4M+7HMKK7VT6DV74
dMAYvi3CcscKPxHXOn4UkTiJwxrn1Jq0fddjxynEzYQoEMsRJL6jMROZCuEKRlsY
T+/UeqF2mlU=
=G9YY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-4194-4d0f-bea0-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3k+UPTRIoz2AQf/eimSL1AJj6tb82SON9BR1dRdfJSdVsqzVsr49+EBzHSP
AX/pTMZY0qXzAQcBpaQ3wrqxWQ1LHi63/5xz4XJIL7UWigXMwUoTYJUhy8oDLvdH
8jEvQT2UKyfBFnjrO1eOwK0z6qIEl+3kuVxF9RpdVJXsPDIj4yoVA+gfm2zy0v7y
AszVDya79ePmET9gvUNrRVX6eXQbZR0vhPEJSW4MR0ASeA68a2XE1jlkgjjy2gcU
ThPNrPlCWe79Zh+4cBBVj9v41kTJ0ItO95/0RCNQn0YYpuTM3eGWhugK2R8e1tdH
fN//Rc+suSFEviFwBTI+OdNMz6g3jlSLKdIuUnzAg9JCAZ0gRXr5q6pcHDyzy9xB
AUwt5+f6NVvzS8s+47QMnAuSP9Al3mhpY1rqTwQXLjanG0gRv8Xo+ug+tWWZW3Rc
2FTw
=udpv
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-6f54-40bd-baa8-1cf3dbeb2d5e',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '408bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+MjRYWGmF+qlRj4kBSVhNMkAJnO0d6pGYmSLUjQ6gnw6T
T0ZOZ2GKpCfT7rya3i3R0AApHHNxnxgr9cdsvt+k2Q6AoBYR4L4eXnFxIc94cnhu
9eb14HUsS8spcrN0cABCZbmaPVt381yQDHTafNwG4tVKErP/TetvsTl6fEMXTVF0
E0E0q0KErpqMXnOSVoaVpQeNZcvd8gj8Imhy+uYRjiZezfuMz1i8N1Ypgi8n277w
MDV38ihfOZFroAEtQ+tWCs/y7NSepNDRgr3KGp1nfX82NVltivmadPIkCnZ5RJN0
RXrNrE6ZTJLRj4UzviY6vTiyiU5RINtCH+NsFtKVSNJFAevBZsH7L91joO013jt0
wMR5y60tyOzVhZsoMavN4CqO6yuIpZgB9rPkn2QLUQMC5zUc4N5bXRMV/sGb4XVU
Zvh23uRJ
=BxH7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-7df8-4cc0-94cc-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf8CX7ka0KW2QLLMOlegUCMo7xKGYn4lPMLX5Bsq/WPbTPQ
pX1Yg5JPzJrV8UHwWTQjguUw35T9WdRX+zO/k33KnZ/leGz8jD1sfcUo9k8pddXa
3CyVNgN0LG7McVQbWk2+xj2Xxj8rarKGLMfIL15YuYiU4CDYHmACVYpV/NuF7/6x
xpCANKG7jQwAxNxJSZlLnk7P4zNdN1Rit2/pVOqsqPbeTQkU/eUOBddiq6mqjPF2
npH8UNkrLq+qD9YMRnRoL4ot0iFN+raX7eCWzqF0tF9GeUePlu2fRteV70c3IKZo
tpyEEomt/NKTYISu68G8aQ6OPmJNXhgTWV+KMTMrhNJCAe8jkQRdFeJ6TqBkHqvj
fNam4M0a3a26aGmazEPFYuI7RdGlL+mnVJAlGkK4xTSJLqCqfTppKWOjjSFeRJSD
g/Bx
=QtdF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-9b58-4003-924c-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf8DJLfiZbaPsEfLShR8Brxc6qZZ5c/c6Dm7eU62OmN0jfw
mOhhFwStpYj5JMulbOwKvVoe0VhhLWE9iEKC5p7TWF1OKpArO1bdmutjALlygAuI
AZ0+QDgDhwtpLuIBujsQZc5TQ9DoxFlO/I2yGqByzJVjPwFJflv4ikE0z2XOJsNb
iAEgwP7CPJaGXTStZADyR5IRUNc5YmhHcqcovS+aZOVCm93G6XZPqQqe+b6kE4rf
GQLSUXqiMT550z3R1Mo8J2BLsXNti2Nlrs6+sw65fUbotk/ePPYeLka2U7Q2O4wA
S1ZYSFbl+r0yRmyQ+Wd7OnEQwAk6CcEZ9vVwSp5s0NJIATJy4vSkGaSUiW93xnHr
BVMzb+YTE8PVJb5411l02kOkvEG4tZyx4rk+r4eAgkBxKlp+QbNZSGhG/2wMc9Hc
OrJBr8MTggoY
=0zYO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-9f70-41a5-96b9-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf+M73DkcNlCs66qv5+wkbhjZUefx5Ve0KVVHgpsQNxhFRB
ta7migmsj6MxmNcOIc3kRFKNC/qHUnXHepLGxvZZ9HyT4psBwQDPgjpNkB/AF9GN
VDyp7BQZidhwhjNtY9dgh8RoQ+sbRioeB6FChB25+XxVTlfEZ+JlsXoOSPvwTHAI
TkHW4m23KUnWHCOEkbUZSrTKYjSKgbhffLxD0kV2A76kxT5LbSbrFeVQ1YUU8vkv
3lK+xlPo2W+MC95L7UDi+4p/tOgFjiC1asIG1iOSdvq9L+WqjTDLw9Akite2zXVg
PyUgSa7nLcsIuuiSiC6PrjpvznPuzeR7HLVF7Iv1dtJHAfP2aIOn+42mZPbxbhMg
kL2T3NZliUMI1c/H8ucSuuJXNhJDLdLmSstwYZwFu0Xsfji8ShUyRnJxBpekg7PI
myY2Tv4R9b0=
=0DpZ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-b5c4-4474-931b-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3k+UPTRIoz2AQf/fLsHcdoa33QSP67xNRTt09+Ha0sJTi/1Fhaq3UBHLjUp
i87MKtdLyyToBwb+4sw3hGPm/mmUbdMaJaCtrTk/YB9FTQYuGf+UoGNjjKX28Nyh
cxAd7yPpS0/ai+i5wsUNLChabYNHuxKC+/yK+87iFsr2QL0eLy0cH4dL1hfjkiOC
Kopf2xlEiiinMB3aPrVfE04enhoI0cWX4cYJuQVrcNJK5iYBT7/72TzYqSEbsvMa
oL43300Y/uL+JNzw+z1ykbinfGWb7fsWruJcQA2WyCTgji9vNJMJ67TXu4hO5cGl
mmv8zv9YSRovvZrj90emFauAZTtshg71oWNGRXuHsNJHAbjSO3kAexBZwky/OPDG
fGfiBEe8QstRe+2ciM5NzaE038hOShTmx0sNGR074HjY3MiFRi8XJ9TlMByeYFqX
NlHvZX1IKkc=
=TC1C
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-b73c-46fb-9931-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf+JhxdRd2c7LGVx4rfG2rWG9p2WPZZQnodPWBbduuBuozc
6tVrqI83W6s6m1YWeeCdbyGQaFcZsWF/0sbiLYcDittiAck7qZiQEJekr1OfV2KS
WyhU5cW913Sdt85f9gx5UGMgGEnzDjhGaeHnRxCOmEZeyCia9vr3SZxge13pPfrE
kkp6YAInp6XVgMTtGG6pPjJOgI8YOFLe2/DxIF64ayVuWiqN/UTWodkT5NNOzBHj
gEaj8zAD8brTxFZ3dUA/ucZ2WREtDy3QR+FVxuUkmbMFFcWN/6HfkOFgdSo77eCd
N7qwF4v1vTVY6T8zUgB1jdBF45x8x5XbulNZ13rnTtJFAQzq3J0xtAXJW/4hsJRw
p3OOC8AqqZg4WLav9JQwH4bRnwUflRScrZyWa5Vx/PlFozCy99c2MxssVdvA6jCo
p/Vmz/zO
=HzCO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-b800-4e46-931d-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3k+UPTRIoz2AQf/TT3HI6J/YdYKPPraRdYjZA6/G47vsvHqAz9sOHgcQ8mT
lNpKzISy+yk9v0vpnA8ij1YWDjuXMhck9B+F7HujE+qbYB4CP5GofztgmkHlxMty
0rLgS8O/GG3R49MLu6ulewIpjk2PCK6xIdFp2vDFdFiPdbEkjNeQyPV6IsGRy/cS
HeWQ4DxeDEM13t6j4n40mPXlkmsE0HFNffoPdYFge2FAND65kCik0uCZgfHcYZaf
1UZKylopWUrrK3wb9zzzd8w7pAWLgPEhULoWp2Anm8Fy/kc8SFAKf5fZHTW1Fagc
NwheE4fMqu6fHNzrZCvs3i+uWZ4BLgaj4frIH0IMVNJIATddjfEv/2ve46wrVE9U
onhCxMDLZ1sgUIetmMKGdOvsmL8EmlbuobjgOx7HBD3QdLb4jHEWnP/ZklD8rCmf
lCyiuXT/LQqF
=Aow8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-bac0-4927-bb99-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf/XZPlRiEQpPMZk/8cOL1lFUlFdkpvBMbTQUhJBp+RoviB
OAd+75wcnY2ienAOeR3J83i6ATnj2M3A8sztLttfX0vLjKAp0PFzViWgZK6aQG5g
jzgZju4zaw92u4lAsOwx9BE17bn+VM19ANvLtvsGOxF2aTKi1P/GRmpOCrA2OiP9
nPlD350NHdLDD1DjXi5WYo1XQNSYwhb7r3/43cVShZLvTg/PwIp99dESlj/TUyD2
CzQok+Apw+TUKPD//6qpkWbBWiejYVQoWBNiNT1E101+2k3CaWZtTnlusypN2Axs
1dxLYA/BAPese/OlxHy75c7fu+2p5R+OcP5v1h+hidJCAVROKW+pgPJ8CWzDPNf1
Wkj5J0WNnZ3E8SAaJip6KGapeGouziL2RFLdTDec6wQAP/32d3Apz3LYtZxKfAmF
+jJu
=JnfE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-c584-48dc-baf7-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA9QpT7WOXdtjAQf8CdFjRmOGel2CXr88xRvCCwJwz5jFeCCN7KZHSBvTKLPF
Anik/JS9NpDVhnZuWoXHG8yXROxuGFY72+2NQAufl0LX/huJiAm2sjBWhnib+4f0
6oNRj9FxUHS3yrPS2Zgs9dpqoVJzqLc9Mh614MFDhJmBu4n4OzHILF226dalNRZU
pFcrX/ahhFHyrOPRt0jIEzt2CKnPUEORrQa6nPrR8Smo1d8Kg3KlsPwEfDQeDO5K
2xhetmP8UM2ujV671xVo20yGiNY2kHQ38n2+PBPvgiXGCEr7X8LziK1YNxRq2dfL
m4LeEbO/gyIpX1O4dXzZY2Jvqpmern6zpxqD6tpzJNJIAfptfCaYGKzM1oMlc0VW
xA0sfyIvfwChWDitgitxmGF6W4vFlkQ+Y8LiafAzOpp1DbmRtZodZEJHvR95nfey
QpReggN40WlC
=XoYx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-c844-4095-b617-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAh3SqTZ3S4jKHW6BkKEvoEhCp99lij4SP/mrmFZZfjd3S
8OZ4/vhifeO8XuneFuhqzihhYTEA+sGFTsLMrKWAEbAgLRoSh9NTJQfigog4SN1c
WVQMhQTK5ZGVTfCB9+pGzxWR6gBoyyoSSNvjJMtOAAgWYZQ3GtANrT+iejOFMWFs
DTZ6NfayWRZc3MWYXx91tqpV3luxqXk4MfZxLRTPhVWA5o5DHsicnSxggWQ5m/vM
Rk7P22XSedoFVQMm/yiVD0qvwNUoOB3/0LLpM+6GnLWniLiaX8T8OvS0gxH5xpCY
DvZrBSbSAf5zGK74+4sYjMSHSvhyLafmmLAJ1hAmatJEAadD3qZcKkp2nQsvVwu1
RvK43vwE/L4E8TVfvKfn7ymFJqhl5Vc58gamPL6uZeNkyYTV9Ajhae2bN9LIsCB4
1vKEpic=
=90HQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-ca00-4b08-8ea5-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf+JhKSZj4YKNthh4FDFERsvUtjuG9F/1JlftrBWXO9Qam8
xRaF4zTv0wL60YCruSUe3a5O6gI4i33vynuNfbqytNnWT/bkRkwrdWOYvl1zUYs4
H0i/hcisT/fSK8pSuaynFpwZaZZcvrenKX0u1PALCnJ+BvbNVD4/l+77cg5EbOtA
Y3++luIfUavl0Ybw+0tk5CNfjeBDx7scMXDxnglNR7SIjoqdaIDug5A/UZ6CZK2+
FeYbKo7tkfMmoIgEtUoRZsFj/paZhCnfcepVGs6t+MuKgQ6/4Sweb1DuRV+WL6vr
JikeoWPT9FDnStfsPLv1qhc4z/Cr+NLU8Bi+r/tjYNJEAWVUVE0DgfUOo2UJ/qXc
wCT+fUMjji+giyTas4l9U5jELHrmU6v/uwpSNQ2OXos5HK0OiFsZD3CRKXkJdHOu
vx+SXws=
=Qsxd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-cf64-4a70-9c10-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf9HWhdJehtDzPS7v9Yl8cnu5D5gEPpwcsydsR7XOhM+pWu
NsgkJgpH4myMk/jmhlDsJY6HwEP2uTWJln4jC2BxnvpxY9ubfIjGR7dWaW0NAG6Y
sN+qXPJqupEow90GT4J704gwX+7th5YRG+kEdC2Eo8P2ffo9uWczLtKXVliLOmnJ
93XUST2E+g7fngnK5h4HCDlbEjxjg7c0U2JFqdVAgbjyG0dIwh1o/5J+xUamZlYX
WC1n1WfzgOwg70UEZMK2Qymc7S0e5tuOwraaXC8aYWHkRu3+bxdQF6WMV8/BVQOi
vxqQjdonV7wgv4dDedUAGxxgOdgj4RBDPuuP8DDzptJFAYpt0bRqIpJJa7+laHJW
daEF1ulcOQe9+Q3OCA/kh6hRe6PbrewDvSdpHqZwaubt0bb5WbS0MVkJ9h9dkNXE
dKtbyrAM
=M7pR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-e8ac-4d51-a522-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/dnJ1wGYnKvXjnt5ti3VdaEFWK+lW75syma6tl8QAvFFB
jFBOII7P04BWfZs72dYUILaJaU0VVaU1ZU9ZUX2Hgt/cf1pJ3SPoaPJDESIlH2gO
coAQurfCTpgO0G8qNSYGCVp98voHK40t7SolZH+81Tj4pVXwlDaMtl6SpyAbjuas
KgUmDCiMolMITBOdv9ZGTJ4+w1ahU3eNISAh6GDiB9Tp7DGFhONjCFH/dkRH9vkg
yKndiFIr/krKDNkSiZ5CkkGuiwF8RQAe7DNnSaBJIJi8D1CDPOZZ3eYromwDdnoM
S8rVNoNyJ/Vcp1Vo9F6wl6nKdEPras8cLZnJz2KFDdJCAf7XKWNdvlL6DsnwxxPk
EfVdL41lC/ii1tUFuGfQ1rzw6f+DL/5hFwzJXPfZekB51/YjvRNIT0GCbnXwCBoZ
/R2o
=SGgf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d18f-fe2c-43e2-ab8f-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA9QpT7WOXdtjAQf/dNfWopQxqWuHOcwVd5cZwOE2p/TSywxK48XrACb9NKa3
V7xRVEcCpJaRSOH99NLjo5GOR01UhUjHKFHysNC8p3Ko6Ps/d9ZURqYDu3afpHkv
GTAAYsvDhTCzQzKnQqAcUm9rBvdrgLbrYcMB2AvmVtVEKZ6LcZs24vN+UnkmjPux
Nfopgp9xb/IsljZgTgA+zO0GDk1rrYiMZeAtpuzzpDhVxK5lbOp+kZJDzbi80bye
2DFtbuyqIZQ+TVHiLWxnmDxKbKJtYSz4qTNG6k0HRFlD775aamJXkPAWPmi/0yF+
654uShF0f4X6SDF7vajLn29q8oivG31LCKBmy87ZfdJCAU54QhtNENIRSRxeMAWC
gBEfkh3atQ1kzRqKXO3UHxeyG+3/K28GsDb2Iq/R9UyEfk8tfcSsI+/muF4wFYpO
Tli5
=4A9t
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-0554-4ccd-810a-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf+PBH1szYek4zh7VWHTi5wyzl1JmiQpzMdpIVbj3ltAteN
lTLgUDsFJznm7iV851r8kGUqbCBSQiDyyPM3DsR5Gag6v2iHEoorLJBRt7/NNgJq
QC2fvAhcvSSAe2j+PXu/FgAH1CEPZgqJk8jhUS/aYH+3ND1LOuLejmVzK/YhI2C2
f+r1FPDhedXm1sTz0MnrIyUYd2AQ/UnBFLiKJ7iu17BPR8ZQNqW7KAAn3rTFfLIw
Z2dEZSg5UKHHjYbkaBvIxpD9GaSADYSypak8mdApL/6WLVqHIOV17ArAPTR7jPyP
IxyOjNwTkbvohaDfAz1WyQNbqDTv/qtYhd+j/j2WSNJGAds1yL46sHAuYqhNU3iy
wiz1i2aGBn2+AH93wv0dOTnc3xwzFW5uIGiu1ELlAefgAeI8v/dKnBlkF+spAJpg
FC3r1IUt9Q==
=2wlQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-076c-4300-9d0e-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf+JREOSU/pAqk0dwnPonUdSnG1plCYsQMiHNm6Z8B+rGs9
/vODgAGtjsST1IaSrnKuIjRY28gvod1XGt/mkIrjwm6g/U1uwqdGxYl2HyMEJc2S
PV1uD/Mtk9IM/MYUCx/A29BL31NUrLy0Jjr6b3M4O2WdhzRaVuf17WPZoNsX+PvS
sO7cOCTizJvVxfqGZ/1zAjnKnuH/ZkKYsyQeZxIXzOFbyVQ+nTjz1t3o41nAbRT+
beUd4wFryehK1QQeZjp+PaXi1TrwcUsSCTsQvjf01SDeg+WTadZ6iws1Ee4g3swF
zPyn47kLmkLoBvJ8SdlOa5GWR1YDLbjOjV3raKGKcdJEAdSG2ZBMScorQm0zVejG
aQMbXhg4xxOv2IK+e13lsAKHjDqs4oaQALDFxmuwNkTtBmFNliJMLZjahQ+vRAPF
mrzonwI=
=7w+q
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-0940-414a-a884-1cf3dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAkTRC2k7aAR1RQJ+80GZABTuEK1sxLSXlFMDFXNOTCAxo
KJMP16EXC8uVGAvgIWMaRnsJtw1jVBz5DDgfap5fW7FyJSAtpjchDIF3IwjyLUec
4+tjy5xWemRMFIBr7pw0iWUz3bptqSmkTGPRxk4gJskuTmph7Dfj0eMm63StW4hp
fgZI81FHrSw0A9rgP2iLm9uZw3EiqH6tD7MRnUrqvfYhZZIziN8eIO+9H5GNYuVU
6+q3HfS8+LJmu11kYU+o/c8c/O2aO6u/Uuhn4aVGEXpFRUxzXKOw/eUubwQaVkeJ
7jq6NhAH9hMTTwU8HJw9+fwxpXRToBZFrzVyueMsM9JHAXA5bKzplj3xoYw/XdsX
GFM7R/4hlIn4YTFFsccz7l8eCY4SeCs7lbdLBgxr3b0qqTsAOw5IkRlGZDrE/4R3
Fj++2oXKZEQ=
=jtil
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-09c4-4dcc-8a61-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf5AZO1j/79/6wUJJ0k5M5j1278HIjqfuV9fDMULb79V+Y5
wTdUqTUxExF85tMjoo1s+TXLowo7xa31f0/Sjy05C98S9nl4IYIzyf2tGKVuC770
rwnRFP+meEQW/AnFjANxKua+jxHL6Mt0c6kQ6qaByIMXssd/1WUbQVIbfx4uvmn+
oFmEGlsjX25rjv3hINjyFsKjGfebUoaSl1grMEkFYmnhwj03Q1X3Ptf+iILDtTnF
LNcdKiT0Vlcicz53gsJ3g4ugGrjQ9jOfOiwIrA66iR+ENiA4jAa7ZeN3D+tbU3Nk
0uTqvOOAUxLFu491X6ALhOes6FcEgtS9lw2MqjFWytJHAcE5j70vudsgPi9G1nNn
gA2qG3LjwbpqM2crXdZ71Pyb+nepheewmUVrlvA2VeMtGnGFgHVGCVFlMA6tkC+l
xcL6EY1nJnc=
=ccis
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-0fa4-4ec6-b138-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf/RAQ6U5BggW/7Z9p4TXSV89DaohCAN8mwJ4+TRV5Gvzvw
OkjXjcw7rJgoh5PHwFKtunoGqqZF1f9/Vwo/PlOirr8XrR+x3+gayGVCARVQkDfI
BRidXr3xlD0I6sng8LsGEgOGjOMWGn+sU601V6tNmTTQFR5TrXO3QQcSvYGYyuMY
A4xNEjdkJDV75vkAdiU1YgrQWvbxQmuO4Hsdd36xbAr0g3dm7sqS5bkJUzWWI6wa
QZfhIY0rH70IW++agCCDv219glIyF2J6Yd2FFy5LLbpB1+i/QtgMA/+RM2xMz90i
dngP7c1jqtiehiKhKRHZNEG7bOogoIqHFO/3ktmU4dJEAb4pWhDrSXRp1WT9kDM2
fToGTKqIT11fjkWk0KducqCWvGB1/LhdUJEfDzQr7H4h9YO+/nrpSscdlU4EzPbl
D2mjRjU=
=VlNT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-13fc-4af7-a740-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf+PG7tOTwaONdJwA1N69CaLiXD7adrTWaD3x2vKvby0E9+
Jy3DfxTkxbnjPAE0uGTdwvtz+M5qVoMn/T8T0UKzFd2iDOTYOfVsg12qZMzGd0M7
t7dk1g1G2InJasrqsepX5TOiFrbPdWVUX7dcLLMa2RWZPMpkPdCrHJFjnbdi9gHU
iSsHpeJbKNEd9202VROwi8jzP7MeScXAvWtkGyelxTM05Jeowv7tu30bkJGKgx2E
kChwptQf3kJsp8D7HUueY1kppBzHd5uKEsShgyADL45MdfNQ9vYDqJKv8s1do9kC
z5ogPl2U3tV6IzyXVU0+vgiZbQClS4tPmL2wo5TisNJHAQwK1XUPW4lrB8kOnECQ
apooTV6W9WZ1h5QrsZyEoiZRfBHiauQcBbkraxAdXwBcZjYHluZPFpZ9dqxafc5P
qtDH54By2dY=
=Wh59
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-2090-45bb-a003-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/WBaWoS5Bxh5kgrTwEDn6qLCxC15vnYiAxU823Txyj/dw
o/u4n9WvAICByys0nOWiZ8tQ4x53ot3cyF/aKBQVX/2E31D2GUN1UednLePDV5bg
hVVyLpglOlAnZSMdAlRiBC7o8GGZXlIDmxr54M99eCZo0C2p2mOSDFu/IHIrH17I
pz+tX0OLZSHQTUOiW/Xt0MfrPvwLZDZaJwnFsPcWCWQNkDSMdYD6JQx1sfwe4Kiu
JvffMgk7LpvMN/x8aB5j9SUythqCC4oIn/f4ay4HTte8aNETMxfwYHpF0KFo/j/Z
xXtFH1lxHyJ5CWiCxEAxBh1poROySBaKezkFY78BWNJEAS23gsbP9OuyzIte3r5V
xatoWIsLrCFEVXOtZ3w4Sd3Ms39A8a9gqfS7awSdkK55An7bEOsp9QiDlKQeliK5
NSe1kdQ=
=Ys14
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-2d3c-4466-8d43-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf/bQoAxwf8zQ8e4HqUH1SCNHhDmmwnytuHpgYc4DKMKp3m
LEi6EdssGlARn+SZDouUt3lsrwQGf10wwNSpBxH4Ep+9Y9Xwx6ZUmFJykoCYgzTB
LNq1TX8H8xcQXO6Aj+DKrifH1ekROlzogX+LbIcVKfjL0+pqPvZxw5I5dtv59HIh
q7eJs+VTHLq84A8vCO5Qg5j1zBobjj6+4y/Hr4yzFGNJ3sSXzmbJb/tyVskWkPxF
X4Z1spM27pW1rJD6tW3s7qiz3tVXkBfee5GeBixN0UgIpBxmooG+5oJvtuxzKkLX
5LqVZFFJHzDgrWLhnARyzI5USpCj4RReaL5Q6zsZndJHARKazxPCElR1VlWc5pZq
bvFvZAFYHDkPvPyYoDezA5es53zYnPdZjNXm+Io1FMUC036SjsKRNX272WF74OQ8
rTI6zXqzQJY=
=ChCK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-3d1c-412c-961b-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf/SDeUHGSdbaSD0ZwbNfOcaiCaGKErUlwA9g6m7230XOyY
N5bhqQ+EZeMUQJ/LlH5F0Q18rEwFj5PTMLEGBvR53mxsVJhtY8SFnQhM1piybgDC
M1KUIMnCwewnFx5WEOckYGoj9ppzg39u5Q2nps4zskKdiRiz2XMnhUFLMECruQCR
/xqXov42A349O7R88L35Qpb0qsxPl6iROwtG7FVYjoyT1mpS//fbpqMVnXVn3l5z
iKc3VtxrajHpZoBTsOuSrTNMYGliWtw/dLGKgdEWIzpfqE56YyQ2RS09Tm5WyDJW
tJYC0m+Td5IanfhdZXvDxAKQRE3SxjAImWzBq5yBOtJEASL+wY8N4tHZR/t145mm
YbtyhhzjJXvmW6qhHZ5G/6MQKOD8eJgzOYE4l/ZZAwC0H3qEnddhgiT//EGT2h+/
gn4h0yc=
=0k9L
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-4b40-42ba-a090-1cf3dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAoSjR5K5U/kmdlIszuxVbEBvBIiXEX2PghmVnOvQsAWfE
HMxpbgBGsLCN46FtZ/3WXFBVO9cSf1msj5cnMKNVswoL16R3BI6mDhubUwmQ61z1
Wev7C0ncp7yKi6MdjgwJxbpOS6orDwtNgfvhL6yOrjvGGJ0GBXp4uWPlBEgwrjtN
pWngUEIpst1rI336OtmdtUk1N2Pw/4A5JIU6rjo4HvNxltx02sF4Uhp/UwC10Bg7
nJ863Qe0zJ+2qNoAyhD2RmExHaWqjlAZb6G/Grqvzg549A6vHVoKY5k3IipHbngq
oNSYBYKBPgcsupLqmPcjoIqrb3sI1Fx4961vutTX09JFARRhoWrYRVjp1zn9FTit
1o/fozyQ+6fTheTlYe3ZKJCvz7y/curlQ/wxS1xu1HAMrVwcqV4hD9n1KL62Qxm2
EMQmLZtF
=3VpU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-5c90-4c5f-9100-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAyFwuC5Agdg+AQf7BqfiKNOh9vQ+P15VNtvBdHRvFCa0QUsjtM6BG65hsG9O
geKflMhQSTCeWa+954dXmEs69+5ZRe+g4SARHp72Pvvj1gSVz7MFEUQF8KCOHhZe
OQJ0CjmA4NIo/16uvcowgtayltXCZHJIkIFbJKrc3M88QoNcTviyVYlrqkDns+RG
yA13mjFBMu6uBHBfg0S8MHFe/jhaqeZLLiRMx+xSNZGwVM3DbFbbdU4nAs7fcppg
9MRocZdYK7PFeX5hBh7yiMA2ohcJQErRhvMXxZkrzq+Xfv4ki9YuCFWnHhXAstGM
Z9wy0cpxbKKpQLkPKHnSVp3l15ioMeU9gypIB9+EEdJHAZFg+MRhqUGBgG5NLYdL
nfNJLnIuADbcZkaVu/CPyVsFG3P5dyz39Zk1B62a7fosxAhLipZrilPvMaAvcdhF
mThuh3St+IQ=
=faXO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-8d78-4e3d-91f9-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf/RVuKmKDDqDK+w7tUPUEHTc2YNrZHBg0YNFqSECu/XFW3
rYUmnGYIE2ECKiCWO4nIFSUCOfDhxwkoc5yyeMa2Wiah6QYGRuiBEt9H1Idl4yV7
nQ/1ofB47rH7JOaBkRNHG7II+1U1VGSuR3aLZyNywALCP/xcd0APY+KZfwWDeQnu
lO5Td6d/Skjo8K2c+zVYlqhKlY8A5nkVMA8ZKg+yxT20oKr2n7B8+b8fUzmn+1+X
pam1I1hK/yU1JY8wzl6V1OJIfjxBaW3KXCS9mSjAuEhzN097DbxoCeqP0y9Jm44V
VoNzmdTV06pbQYZZkybEZiys99xAkFVpdGQ7nH5YMdJFAVfiVfU2LWVGmQ2JCmW7
raVqvQGXVkdvEbceTU7PLlwg6tD4FeOFUk7tjLjf7gmSJPuaPEg27ZQgV/aQsalW
h9YWNXsU
=10lN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-9124-4913-ad19-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQgAtp9/l7lR2LnWMNUF8/Zdcg0B9eLj6WJO1PIthBP161K2
Ww4P5gzj/EmxwSs4i63Sek+Nh2I/XYEWEaAl4j6jZHLouZ6VtSz1gzm41cknvGBs
VZtOdYOFIwyxBhL8jtVt7V7i4WYWCII+gWwJztRGn6edRGdvvuDBHgRIwkW30fIO
76I18fDyIn11GH4fHjuVarLIDtPmv5oxyBr167yZiQmN8kJZotTYgGZE8Se+UiyR
lHpHMBqd4QlKIvzrF/fgt8Zms8MojWubI2/obV05/7SXTPuvfnp2mLGvSZkIjhjy
kV7oeYuQ+0b9kd8bsgmIrd817Ydk5J6fctvPC49quNJGAbP9VWd2CAzojFHNCBK7
i9gG3zmBkjYMboaa6QODuG9NtP4rNmV9nbCuuljNQWiI555uJ/X34L4Gv0FD+iNX
R6C+oIjjcA==
=1E4A
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-9768-43a7-a0f2-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQgAjctNl2Ny4SGZgzfZH7QQybc5piZWmfkRw1wk0InMcS5O
v2jvbaeA4O8DQ9PzYkkj8mGTwir2IRamMbIOLawhZupN89dOCYxXGmyxmefcjUDO
4usnud4xhpZgeJbaSfFtTP4fPentgopBuUVHBKYoMY3yVqGSPCGw5adCxbtOlF0l
b5bZyhGFEuY6lVSwo1DR0lC7Fum2qwTU/5lKtlbm4GdPVAS5tOyqi1YxIHdgDiZ2
Atv5rdADp/aKeUpgTWZ/k6WJAvdlOw15BWTAWxDIr40Ub5R/X3ysjOQjtYJThpGb
5yyFAnhLXjl6SYzOS/mhgpDgpHNsPL1WNROHzEIQN9JHAcWCVwxvSsCNqtY+aI9L
fznYFiFBaUTrztrItQkCdfu58fpzS9f/AlN261lnzMQZytMgu4XtGumOxkY7FkwV
fH0zKB+qAKY=
=usq7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-9e54-455e-aabf-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf9FiUxgh23HQhjOY7fPieykAzK9peISJkuj/+ADRSFob1M
n0bTq7ansewSnpwcZOwuxTAyQA+3CQmMep8RzHEm8tix7KI8tqKzdChIqSYFcsJP
/WEE4mAQE+xiebTfwF0eJw6bJ4k37MTzzHZtyxh/raXKSm+84eRACWcOAZKIOsl5
lNNQNbrvX4sB4LRbCp0KX8o4F6kAZT64WDmzO3EVXPquTFSMpBv0igNqfL8RRTaA
GXtiDKCLSPuLiQN52ofRAHK4Oof4IpTwroz4XcF2wjqH7NtFlvH8nsIRp8+K8Nmj
xETntkrqaZRJTmnSkfqc4LqAbsZzbGBRyhi0wY8/ONJEARo1GvWnGnHGSWzmsGR0
9HEeWGsV4dFrY8rKAg8coMdhlagZIsAnnEWTkNcQEn0xqs1grrAo6cW9xc2aRzav
bz9lXJk=
=ZcQp
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-a594-4383-b4e3-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA+H7eZEmilqCAQgAvEN1hLm1+BjIUTs9eAbAplIrrGZJBU/nKqoPJS4mZrI9
0OOOzBVgf+funiM+9LmTJwIxGSEM9SyfX9nvvyJe3jVxZiX1qxXte0jW8ViZfzoT
zYm4kuECguHF55fKwp8BKSLlQ84JQnb84eyNnfx5TOryvwwiFkl98YoU4UNpCmiO
xNuBNFDDYBUlNQ7YcEtm54051iv/nemKpkBKGWpMEY0MVrf/4CAFt2qdeHVHnk7c
oJO/CMnBJ6dYpi76aDQ65azqHLY2ARKpU/qbDwDTxgWGarr65LgJXbdhmpph/Hq2
5gBHAOSyjh9fK1WB/e3X4UrUDJo6RxS8nThuVVxW29JHAUfDQ1m2Jfxy/yUn37U0
C+RgaR+t1j+ZMchDcas6XAtO200KeLExkOLPa/mtfhZtSTNyv1xkwQbVbXVNpn8M
bFQaJl+y6Gc=
=NDwM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-af8c-47da-89e8-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf/bCXP0syvGVNC7EluHHgBwFRgYWfssN2EQImaj2P+tZXj
ikrscMODemOhNCbu9bcnWdtQv5uxhM24EZLVGwWnmCOlCH1jeHreAo/mOQOd4IjQ
s6iNZWJOEDuOAEJXF6SPtdsIRDiAWMnvqsAbZ2yae74NUHtyUXhz46L4hpJSm7PZ
UaVM/bW3Bg8qzpv1edOU8yr8SjCJNVR0qf/IuqB57uISsedMhiFNWb6APfuuC2FQ
CVBnPVjyuK45MpPzjNGICbfHSfyBfJtr9R+hzcfAHxwIQdOL+h3kUVG4SadeHBUB
mrbc509ZKHcIqtI3yzq4U10CGq4Pfx2nxKuqs3hM5NJHAULqYHHYRHxN8qRAQmOd
Usz2VgV0oK1/NS6RNlcgv6mURfo0T71uvsLaAHf1eo0vrkwnDTxsbQbGHCNPR/il
qn1warqODqw=
=coKk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-ce00-497b-a20a-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAyFwuC5Agdg+AQgAovoQ+rHM+r/1dSjDeaQvGsQjuVhNTX+n9xrxA3uhsY+w
aurTnP/pYVnuvBGipQIFhUFkQ0+yN1vlM4/gcPSWpZHhU/oflNeL4RQdXIlcrBeG
Qx3eYUkecLQNn016Gydf9weqlMDyA9ABH3Cy/C0cpg/8REinuvJDjU4JmweyU3n6
gBjRIW21ijRhKpsDt8C5XmS86cNKYmGD50vg8oRE5UI7SEXW1srs/6wpVrZV2oR5
PEmoxR3kkdINtA3as1/GV/wVfCVqUgnfHWEA1xGC3qOr+UzfFYJ5Ez3oVy9Q6e8l
EHGxyz0oMYrsIQQbLfNIIdmp2+nv7FAIXiO3pP7K4tJHAbXavcwP1D2OZ+5Atsf5
JHa1d9niTFst1FT3ipO5n5w62QP7Rl5uEL/1s8ARdaXaR4JXnR+rgBObxWracKve
M60e8QoDeao=
=CvaX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-ce94-405e-936e-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf7BDAlcUwNUYOutIDMwNSC/DLt+pnwxPSaPusGRAgYStuh
K5K5qf8nPBpwNQmBCNPzhuXrIT9jz3B8tLZj3frpPyAws/6semlUnNLkbGt8CS1W
6oGncDceTe+F2eJO3TRH32WyuMMD6PKsS6biX668tBlIy9Kkvuam/+Jlo1/SKS3f
WpjiU0l7JpK3Du5mgWhGih7PLmwYZ1gi2bFpQFrYaOju9x7P14COkG4mhXBJkWK/
zDFZVwk+KJWcHRDGpRdcKTPUVV7XgYQY9olzvBXCPUbssIjLuid1PQ8r6VKOIvVc
h1AqMz0qtJh90LAdTyQLL5hOEOP05k4lmsPujKF9VNJFAfC2L6QM8t8NBWvhN6+x
PP8lOzpac4sCajl/OG1MZb0l6oEKpkXEo544/3b+Wy1apG/KQCPNoqVNiHJIEab5
pTXs441D
=NosV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-d578-490b-98ba-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQgArT3UcWgMJPieCayCN8QKE264+IYhvY4v70gNUAshaaj8
or6IH+1aSM2cQ7k5iMw8NA6/iPsnc0FKZA6gAUhEC5FMIQXlB8cFgyd+4nPWMDDa
pH/oJdF9rbqDnemtBvDrf7wdPLOxS+vVtUDTnkjCLqkUAq0P0rZlu1b0JCs4B7FW
pjzG3389PhTaCXX0LJ/H++Fl/7TtigVUbnym/QVvIFDv8L0HkGQ9XCdfabNCv6Hs
Mg39qorvj/7G3Ty4VxWO1lVExauZ6nGYO+SgGDyyx/Cnccu76UiR4QSC4KQtZWTT
GQAF1YGxGWygcYD2+N/g2w929Y7fcw62VO3z4fjR8dJHAegNCnbFJCDTOwGFAHlf
3adWYC5x+1iBb3f+iTYp3bUswVy86KkVvyb0WSH2PRtqFqq8KVo8REirIzPpDntB
Rk+gPSujJ70=
=wOlz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-d898-4e24-9d24-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf+KrS1d2Hq1ZSfrZ6vwQNJYWLOJARjUtCJUkanAWM5RTV3
hvuJYxgciAz99mHtt5XZx+TEjyt42UcFkG2HXiFLakeyGz52L9fZY42vYk3r4iLM
kq5s5zOT0gDy4Y7KUw1FLKIp76RI8PfIGBFov+J58TmGASeNFk2J0X4RK1MyEgLm
ZThOHWvz/uyf0tol6vrNB4ZwaYrZD+W0bsjJJE3MsCpk+Cxwerv3CptboHaTeVDK
VsoFD/MsADRvHEx3CwHGKLXWaIPt1sj6Pt+6PZz4wwfT5Ph4u9NgUQR3Xdacqce6
d0nLg0qXjIEOucmouGoo91jI5mHepcOZSfc5xEHk4NJFAbRwnSZ8171/oepBpDR1
ev3GP4oWEjwOBwwXeeFkxPXHZD9IUQ5WHpXKRtiT9ro1Zt5FU+jQgIyi/oPbaXHv
S8AJBbyf
=Tqy4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-dc88-47e9-8381-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf9EqV6rCku1cK5LxrjuZm3W/50abCqNuD2EvLldqJgs/B6
ycf/BgwGBElWi6SrSbiGHq97lqRdjN7Xw9odN8Pw2ZEAW2GakGgJ+JsEPio5aw2u
AcfPB4EJfO0PPhCdl9bF1ai9yUZ/ff6JlqEVC3/ULkvUreG0/dWjA2rkMGs30ewx
PR8tfJcKapMAM6M2egwpdg4+9a6tADsyH7DaaXOaq3am+9MjATN+Q5+F10SvT4z5
W8pQEO6eymA3QIlvpLdvW6Okz9THavzcOr3CsdzWXem0rsfG2qiRCfM5FPFq5ahT
Klp9oYkwsDyH6TQ6hKgWs0umnBmK6eRmsFXeakvxQdJFAazrpbMFtRPRI1R6+1/e
ZYGwN1UsXMDo8SbBiNWGmgYfJtvYarWrFHviz72Y4h87NnKCClg0Cv2D6nskRLPU
rzyAaBrh
=0E7i
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-ddf4-4347-a909-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf/QQ0ezWgySLNZsGadW3Q2FlBihoi04fF9kOD86KdZqB73
dfKVg0Ea7L0Z5TsYd9uynaydFHuG3MfVoEPdTO2/tE8Fzr+Omrbd9mHGpixmQjZj
LbIr6KWD0pb59ou+rGs1TlS4tFykD4tpZdNR6h1VAz2Nb73Yp8l+LvjbUL6y30qb
1k3qKT3xl5aQ8W9d7tbywPMOUeD9kBvcatrhLd6viueMlpDHOZgjX3rBgOzF+zLu
81ivUC37PXmEqT4u3WDjn356Ti4tl2JXXHyIsVEOjZQ5S0e7w2epAC3M4fJzhxbP
MAlmCdaDAwqYggqMQ9j/Azcr+DfK9QSdEZlGYcMwmNJGAXy+mDxbMwDzpiyHg/vf
lhu5Qbiloz4MWcwIRQ4qT+2c/JZ4JMJ0zgqkCD0MNOr7TgElofaXzv8bnbKz5Qm4
gY5Bxc/7bg==
=U/Yt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-e104-4d8d-9f82-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf/YMX7fzGDeoy9nCiFcF9kxtoVLIsOwm46Aqwm674Jjd/N
RItt059Ur1s7q03aZW3l7VwaFLQhxCZGV8rlrxn0rwfR5DvnRm+/8kBnAFEIcImN
iVfL91sCHvxLcqMSnoq8TusMI+6AlhiAO0vqSp/98zWbKtH3o8OuLm/FGoxgzcpd
Bi8wLbe7VfU5dSyxKrZ7qA5/x6UJlLaE5G/JR016PphaYiHHDgzuXiXb20bEXXxN
TlywM9ZcK266YOaw97+/WaOG3K2zjpa+M9iBTSjYrsv/Dmycdhmvxn/mjQgtv56O
U/U3z8XLeaQRfHFLEgw8IYKmdl/srubXpIyY7+Rel9JEAQZMvFCW8x9i39SdHrXM
B8R+w1nm9zUvkIywDeyEoecZII9SxJH5OyltnDx+wniL2LcUW0M31esD1ngyOQ16
J0YafZ4=
=1SG6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-e1e4-45d8-bdce-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+KtCouh0oPiT0+177fbGl1cFe3dPNXN9By1MfBmur2Ayc
pqQTe5Dd8NCLYPQ/n1y9SnewU6rcMA40IcQASIVgr3HJpU7z9/ZXu1WxpiCsOITh
nKCyTN0HqQnhTAWgQccftpbQFM5FcSuo0452DyQnHNChTAy1VOY+WLOWePCp/nG5
om5dvcersDZyx7vukOt5FDOnOHosD7ZbHWyx8QT3Ts2JsoUnYxqW75t/VBg/40m2
IkQ9a0Z+jG7TH9j6QJyAd6JnnLoa8s4zMbrnJQIAaYyXY95s7ik/CY7bgP++9gKP
EZ7wLeusUB2tdyuNmrqnD4649T2Yan3E0jHlfRbgbtJFARMd0MTi2z3Uh+iX67Da
7LHoiOZl1cGo8O9v7lIps/S1dernmiduRQCWggPEUVIbRLRP11JdMkOpa5Cmqaf/
kQISWBrd
=qPKz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-e4ec-4ebe-9255-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQgAvt3C4COwnaocIsXQcUJYb1xBLAZWIgfIiwccid0bNaaR
XegRMDjzTdQzB8pXQ3kuQBfxEql0ptaSjCC+a5BrMufOB7RM0iXFOwNA9BPF+pWk
4zX3dvma4UpPtXg3tlyZH184MEnNw6/MQedtnVRVjkX67N7JuqILkaErerxArDQY
30dgW0f7HpI5lHwLHDoHqMDFbICh5D4akjuMJAj6vFQqrnSvC72vM62puKW+Ex3k
qo5Tlf/SW0NamYvrFHwZ0CP07WfRIxK20nHeNQ9FR+y9atzl+JjN/ZYf8q1Qfyqo
nohsmDULNWpNM40jRk10RTQZfSKlvdW9Wqu9DIh+W9JHAfT3HF8BWcqRuKpUe8A0
8sdqlg146v9mJrBynZnt1gOF5GLLRhhkFfu1VhZijTN4nBo/i32GC0lU62eas9pa
qE/zZNVRpLs=
=tpsb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-e6b4-42d3-be21-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQgAokz5r2IR9wHmjccYlqnr5YFo7POmMDkdPO2NPH8CUQkk
rzcm9vSgahoBVzbj4Cv2GHMYZfxsHceAsMunQN4m7umOx5+skBo3Sijsh/hroIfA
2KY5CrC9woHybyYDZhgA2xkJ6FUfeS38ZlG1Phc+IZlQuCfbhbKQHBVNCMVOG+lX
DY/nB7+sRVkjrx17ibQLU+AekJGe8QYJVdqLpRy91R92uTUXdacxgpmq8pbyvUFm
fYqpI2RRfJFSLqsCiaoh2/E2+HtjwXo+X5DAui3xpXbT1foEf2ab82irNPcRfy98
xwGyb/5iWJIJIqCg/IoOWUMouMSYe2eToOtKyqYEBtJFAaw/0dsPi8BYCtU/mghf
pdGJfmJeRSqsPB5DUJoUbC0erayW9AND4TU0EuuV3vHjYBdE7/zqrj//IXEo6owA
WJSu9SRd
=4bZ8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-eb84-4547-b866-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf+PrGRWb6fJ5fsaESftFZ3cqo28wV5/njg45hA1V7x/T2O
sdSuYhYpnCIswIgYRx+w64mUtPbL5eQLsmwFaYBuHtL9Wp5GiFzQQmQkcvWGijxo
SaWd74LToyqp4+fX/zP+JzmSPq31/dYNvrrAkCSHWC/rcM4ZwsQtAUR33/J74CNS
NNwmTIk/DB/tj/7jNlpHEdUaJpEd/ZrbVNBqUeIDdsuAZttoqiCUywfsYqP4Fbd/
+YRTA2axEYPEUF3ftbWoB1eLp/Wy4Da89Qpz4VWzRmDUR2EWHmCP2m4sPwpY6RLx
xbi0DVkxZfnKkkVsdiOu60TIGRFydptNQTm8jNmaoNJHAcf9cbaIRNSt7O+MBqFn
ZNBZ3tb6S0z+eKnUYuBlvZYiLJGN/HOxm5TnMB8NjwF+5z72dLLttQVZa9ATKwyF
///6Jdii/xM=
=F+Co
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-f0b4-43d8-9aa0-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf9GAgVbeWX7zLX5VtTty19uI7zh7xri+LhnHOsKaiR3nYz
LB3ldPevbm90fYHF909eJLj0zKLVCiheCc9l5eg4CYYirRQxPREfBNjrITyoYBMX
1KlxMhGVuahESPU9tEok3GMdnVZmPdPxZAgJnEsbEKXvzICemYNSn8Yof4kBXbFX
Ia6wdTvvI6UNAMOowniUa9zyxYAOKlMYMThg6RGUjF1sIDu3HMvgvzuGU5E9ClLF
W2ER9ZonoXw4uGvTjdhBF5xuU4CmnzzIwMicFPLAulSmgJ78vG8WADNs4tHTVi16
hjLw7MK7/aW4jv1YVbFreekgxM55TGKbudC10+yDodJFAXNCyHdJUZrL0/H+ACaF
8LVEBM4mWFiXpVf2R3dqMCrKuop8jn/StisMkVCFL1GQEbaszeofd5PmwRUfILQR
gR+T1TMq
=K2Sb
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-f68c-49ee-acb4-1cf3dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAxze6z9p8rK1AQf/QMUswnlzzxjROhCEdEmTitAJ+6MMY4KzmHQahjDPgIs6
R6Io0ENzkl8E2++fU2ONRJEJykHodkrPv3S7Y8RThf9SenKVXLfY7SGlV0YyJoVF
/rw7Dk+pddUWOq3/W/CicKpe9LCgXz589oZH1twXa9I5iVccbN0y5DeTSzEMDzVB
n8sbbRa5aSyCsPH5REnmTzeGszC3VeFbRLNpKwZpBLZSiTgCULwpzwnfU1TLvE4u
VwUa3D4CMYQMcCsaqUuhcIZcKyBKrIcL3oobum8cS+h1p4ZH33oPWOqMmUW9qtz4
z/kU1k1FyecOdc9FdA9AFIu2lLdpEjGShTjkF5CVpdJFAaQZprBj52HwlMAZmI8X
XCbIIINgo7vgRGOqEesFrpXt9fIK5UUv+1RLu4uyGeI5oRUG+qxU+lPrQfwhnQc3
wZBTB1Qf
=l3JK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d190-fb64-45f7-b7fb-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf8DtNUTbU8+52Iv1bwhtGaxsEX5vX31eAgMWcI5loxCj62
VxwvCqywLsjHZ8GuhYU64yZY/tBlMPGQHTUZ2WLzKkk+pWw7qcxitxU7QOisfh6s
s3nmdctK08JD/z3E3dClXMlMlUGPGHjeY3LwFCoG1GqGihF1CurEI4NryrV2Ynnz
VkGWbzmbopD7ZrXr4wHGioDGDkAH1EK85EjanrWcUvKw5pB+F6BEOrNEoqvXqoLs
raPX4MEpGB/rVxEu/Cs+eRuEUieH6XIbtFo1S3uaTCHwNDXoLmPjeY+a+OuXA75q
AY297X7mEnABLZOTbGRVSTKfSvGhXKELaMvtj2ZmHdJHAeJ6TQ0lWOMbh6dNZlk+
0aF/vlnkrwWRbmsLepHuvUoi2ade/SeduavG6j4gsGLI8a2Wx51FS8vYH/UIOnaa
oY9JUrwD7Xg=
=Z6Nq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d191-775c-4348-8ce2-1cf3dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+IQaXBl1ubeW06oGU35xXX49GtAfSjCOJ36A/FmyxgOmE
5QyuogxIH3zM8GWnGl/y3S5ge+JQ9BGCBZVB7vhnp65NFT17S4NLJI1p9OtY0sFQ
i3UKi4Gc7SZa0Al2d05Fc42f9P2dkluf+xKNDfD4NTC/D6PQjUW6+6byjyEPuqvC
btBm5+Cwj3Tk+zo1gLNChX99wg4WTmXKkUD2JyQ9h9hWVsIPIRNIDD7wLsdDLSm7
M33OiYjcJn57whMMmcag8s5WteZq0OcpTExBKNyeCysJR/L18ZKBfd/Ur7QqN/jM
x+AhbQHCqSqFOyg7n9reQ494d7ZUi6pCZpi0c2xFrdJFAfU8glMQ1WWQqtmhpx6j
5pH72lnU4lfn8nJ+sqH1XXXzbqoqFBVBQMsVMfyVDl9U78nnwkhLPplbHrcYhr8A
xIZOn23C
=P09l
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d191-80c4-4c21-b443-1cf3dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQgAhSLI9EYUOI8dv0176uheIZJ0ASLg2PZKwXsKszi3+ZEe
kRGuWRl6G+QdriqrMw+40uSV/T8BVQ0NAA2+B1l4h4Y4WdxhwELZad3/u9sQFmL0
gCqgf0u90fl35sge9/4eFLdjJx6M0wZs5H3vRhb5Csi9cUC3cGAaKspfd5XWtXOI
fOu1FQHOy2MWHd/jer0POyXAnYc00yCvyGkaF+hV0ICHNst7TbHo1igLM9Ni/NCn
BVYSo/wGsxYiIlY8HpRPwcqzQyGmco1w0pdNtskn1kGrE8Eu+lgOkOY9H761tuo1
2TLnfhSIIE7ZVWwe4kccRQtEa3+fASNoFdktxkj8xNJFAT9wV8CS+qGHMW/IaPnl
ymCWSmXJbaWFENGiGzjP+SFYGrOvKaa0nJunJBbTwR7+1GN6Lk5IkBJ5QrJ73D75
QcK0cr/t
=KFmt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '5593d191-fe48-4f9b-94b8-1cf3dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf9EPEXGZvJMCSysZ3s6sZXKnhlj4609sf+lJXlMBLm983V
VgoQWvPi9bUZaFCHpJmGAN1RBvXjNqNm01/INoqbanaQO0yFQeXmHD4PHGoZsF3r
Um3V83BJ2meO3m/66q2wVhlQyfWfv+UrHnl5MM3VPfYg00GOnrQDC6e5NxvXWPV3
O5ZOFpIeRGjePXMPWEpH7BFGYyKGY7hMmMQ+D5w4RBptIra4y+PmuWQcenOo5l+/
ljbgt+fHdczefwD5rJ5s37N0Y1Yg9vU7m8sPA+8Fm6hZd6pxgsUDS6VmlRqJpXzy
AdhyqP4gyy7c0ujujrBpqq27lsze2soKDSVdrwzef9JFARlycF3TK/26SljqgKpa
afGH9uNHXDhOCpuF+91UCe7XM4Pqh+jbNct/zSiXrzZyMwt6MmAnQM6yPSFYw8KK
fO76ybSZ
=r1t2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}
