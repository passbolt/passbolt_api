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
			'id' => '55842f00-47c8-4aeb-9ed8-192edbeb2d5e',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA+H7eZEmilqCAQf9E+mztreNVCnVAI9VjxKbHjuX0/TgRrzJya9xgnAMuZuE
6bAAqd9F91w+LdJ4mXmbjiUiVL0nYPg984saf3Jb8I8GsGBKYS1Px/qFdnVHGVbs
rd374eGXFu2Gss7s/41PUtf0aFzzlLTKKgtJOpMbsbN1u91WhvOqrwB7VbUxqOKg
YFc6gBU2E16zzRy6X5Lg5T9xKIGF+5pUsgsege7424+3+NCq6Xny5n67Cf7+uZUd
DUxqULYs9Qk/iDmCyT9A6oJgA2+I2Xswukj40df4Pv5EJ+KHAqY0XlaAaNcFzW83
IdFX5iLgzM6YuRG/nx+1ZEMlhG01Vcmg1c1IjPZ+jNJFAQqkYc0vnUUHNZaHDPzm
qr/ktT++0GilXGdtK0ljtW/4wT2FK9sQ88U/hBucUZB3Y38okGNi7dGPCV+65yGM
J7/PQ/Bb
=k13Q
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f00-4fd8-4e05-9779-192edbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQf6A5RRNKfBu8uD3N8LQL9tI3vgZt85ma8DgtvcjpEHKp0p
/jEDqjy3ZDZJyn53kSQZw502WdjYIxfRgDc/GyBbMkO4MVgDE4EqJ8vi8aXvC6qs
kr26N7xuSWbvjurJooURJGzUHUC3D3NbU9ubV/rtxNz3gWKdjNjoNHiiEtXSK7c5
ktOy1mIXqEuTaNXaj4sMEmTyIAJKOxIyPq8BxDbvMn6TtlZ8Zk2ehaSh64e1NYqn
SjJCSLBSnZXcyZ039V1RzBZ4GSVOaGpFwdHs3PO7cqptuOfp4NZ0IO7hsf94bilK
9y2Dhg/faJL3ESAzbzvlE3z4zQLgA2oi9d2hsfiz4tJFARPrWzGB1yVDg7GKW7BO
zkZUcyBiENQWVL0qSlBPizSKDjWx22wf8jNpZLwSS9FesEKC9RH+hkrjf8HuJNgj
sjVfoiiS
=nPK0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f00-5290-4e49-a413-192edbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAyFwuC5Agdg+AQf8DZ2Mp6Zr+hOU5gdqoWysoNhr8/qf/YzkgQtXGmnfwico
2y4quC/odgP+cTNW1DaVHCaS7pmaI5IRIPShc5LajgPwKoShkIJ993w5Mcgp2kkg
g26zcxxRthStew5wwHIj8Wwh6Bg3BSsac5ZhegnXuzGN1hqJ7tR8DQS7dNDoQY14
WCEVhqyVF9mCGc1l4Z2ltTI8wKGGQE/s3wJgzYBklsJBRXZnRyke4Fa9V8PX0TC0
UGUJyDCOxSoqvWWQO+uUmBdsToj/zWwLI/D8i//+X3VlGeOyMCiZlpjIpYYIb6sR
4r/67QSFA5sWdUxpKxtPIM1tmOojUTz5sD/WA/7jitJFAVUpm7aXqigZahXRZD5v
/70XFLftjlOLjr3lITE0aAWBVnHErmOpyzlTZljuvNBd6eNYCpGHnEDbZzbIRPbK
4iHFE9/H
=bNOi
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f00-6e54-46cf-9492-192edbeb2d5e',
			'user_id' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAolMwv4+zuWVVnoeSL6Tv5mqHaEYO7jJzfQ6ndID42Er9
jqhVnu9GxWqmMczn0O8bIIM0ptivm9nm5yKUAG1iZ7QTwOus6Mtho4NMcQhy9rO/
8kCgyNJVgxiQTvf7MTwi+Y30Vkaz0sIyULwjVVHEE0zJaAQNHGp69vLe6AupPry/
rYqddwEj4P4JsRAK4tJF5xWUGNXpZ3FX0q7cRUSGbeS0ivQJCJ6EOaZLnLzmcXJK
ysWB4FnbRGdROnzehpMfBwAT7idxIzd0e/VN9Np6mlrQEW/mlo/HKnmjU+LCVQZF
uuoDtsta5MB/dQfaeulLpEclwVuDQc2AwfD6qAL5R9JFAfyWpPRBYmwqTUYuQCYe
c3b3w+xtFoo40t/GpQnS+cyXTsZMHHyhukeKkYdzJv3bLCWUTxdg70YGzGAh294V
0yHJIp7o
=GIje
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f00-b948-4ae2-bf59-192edbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAncg6/Dxc3GyisMXj1lVrzjQmKUaK0qF+I/04TtKLZCp6
2bbyLweIWI34Fq/5TxSPIuHv6EhwEIwpgtt8OH6JtH7sGIe1knT1qHM2Jy6R5Svh
HJL2AIAjaSb4zbjO1RvqI25mW7/AymWUd0obPlx2/r1RhhE23S1gEZL1xSqfXwE4
bl1WEz/8hH0eg715q6nKU7mprhp2JwynBmaJzu7kz1RZ2vI+uUTr8iFaRDo+zXsS
NI4+ZFas7ykfu02hBX7zArqWyHrARumUFmuAMj3GYKUvVhSNALZMXvakam0yvW+H
S64ZR9Lj46H55SWBEgUABZU2MgxYG02JruddKlDntNJFATF0RQ7+mnOJP3Yqo4Nb
nkYNWrjNseYWpke6iB0VjsqCfYpHWocm7XU1kGhlxT5Iv8y7w9O6/VSgsePsKt/N
Rz7gpdtU
=QGZw
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-087c-4585-a97e-192edbeb2d5e',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA+H7eZEmilqCAQf/fj2zCLKFW74aEgbRudtw6K6WticPBNhV1Xb9BzqNmL39
ZrMyAmwP9PZ0wYWma9FhsZKxb2S677Nw0o6AEIhw1BHFob0WuE+S6D2Dayj4Grxv
csWrmb3VclQ1Bx5p3Zli32hSZic82k6qeWEROOZTLNIajve/jBahY18U0e4P6EE0
0khppWBmNmkxuXMvGBfBAo3DRiApdHwtLKJesGCzJHqm4pvv8QEG8VZwAthb1fbC
QUW2okiDLAcyY1TCd9/r+QBJBey60EGL1+cLp/LMBgOS/ryDO+gC7pBAlrAqb6b+
n3030V8wYzXt8UoxSrTYDmtd1Vb2aTZ9/CF/mO/B69JIAZf8MdhfKokeRNj2gce6
bJ3xmYl1gG1vpxv/t6JMhrHJcHteLIMaQqxWNCl9oQ2Xw6Ub/Nq2Ku1W7AgNKC+h
IWUpx+TCoZCg
=L3CS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-0cf0-4d93-bcd1-192edbeb2d5e',
			'user_id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA7CocOAhB5MkAQgA1fORk7v8/qcTNZXAwVWuQyVCaBZzX4ovnGEDGg9AKSzJ
TGEUbXgMBssVGCE79Roo6nUs+n0XENml+4U8ao4WTE1BffOGAtMeTGUO04OI+x74
dWwax7P+hS+u2IPfy/K2+9yHKF2NB0NliUBNgG+IbLPqz5f+Jtxzfk2OZ5rIaAm5
H6Gg5HgiM8IUYKvXz1m2+8C3ZFlL1S6prLhCrJIeoOqK4QiZQpSocf/JaMbFEaMG
HHDYCEpgLnlljbRPrKsPpSxG2qzYHQy+4TBT+dZoLf+iDGy+DRKn4MsvpOduSaXv
VkTGjt4kw8ccoAp3ltYN29YxRC3QxaZv8mNaOHK6rdJBAfvz5BzVfF49KBPQeg1D
JBoBAlqa56xED6+1Rbhk7+ykXZWa+oDrcjh0NMuukbWMGcmPuiq4Jp2RiCw8Q5Kl
AkA=
=mqle
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-0cf8-4b64-9969-192edbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAyFwuC5Agdg+AQgAtXHjbpYbvHzobID8Etw3zO2ZhDOtTZVTUJu9pX6oK+I7
I9OH3KxnEMqfBy03PXKaL1Zgs2ThT+19mpO9obTvepfvKGV5J4ppHWGokkKyr3vI
dZr2MoXM7Q+dcwe7weq7VjwZdHQlgwqOdYuHRvWE4KuNqG1nZli8Dvi+imrXwV9J
WJhXsAh3BYbIjyeGNyZXSa712HyD8F52qyGWGRmjeEpTt2BWzBzxhrm8fb7zM0zo
fzazN7/5kwTay6zfSBmnyU1hM6/vB0sXNPhIoAWPxWw26b5GBlcDVKCQLn4yZ5Ut
FHDn4gkvFj3lqr58EFgLnUiD94Ld0KwEYPNgOiwltdJIAbJT1H0DCFupYdu0FJ69
VbAObryKW8PbTXRg+OTTTiA63LExz5GWTAtBx1URBPABliFN92ZepkLYauUoj5Ji
es+2Jq+ivZHF
=kEZ9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-1d10-4190-aa6c-192edbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf/TcD+bl83Qu9VfXj2ZwWJ4AEDzxntkMXjqZQvoVR856nL
VX/NfWATJPIxO7JRnpcE7cD1NlIqSYjSDRgfZV0Q+zceYi9uMP3hAsxaacr2yZpH
JMYp/YzxCZEAyIHJ3yi2vPieB/1unMzA0SfP5Rk8SDGaJUJrn/v69Ln4cxB6fmyN
VPKPMhibDJi7aixw/VVGDQgw2wQB3iL8/ve66Et+kxLvNHKkvTrrhjLWJWvXdX9s
SkvaLTFnUTZd0LuXLqsU24WANScSzA7Ei1q/U7soY7wWw2JyOgHY7hG9dIYcNclX
EQh6ZJK1p0hFNOGwG/D1eoA/6P98V9GyRIpTjD87JNJBActZd3HxnMXdKI+KXjrA
TLvfJtCrI5/NKY4Nm9+d0g4kFXgti+AdDXioclknmlCpRF/Rq+ZXyGG2uFjbR2hW
HyY=
=zvOR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-2820-43b6-8d85-192edbeb2d5e',
			'user_id' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/RGJTg71QtxR3N862pqDrdMRUaaCIYCKLTa6B4ujGqRjS
GznXiNhuJjSME7D+DLtpqDzQWV/O42QO/x0+Px13YSdYnsig5efQtnTrTO+2wQrY
6J+0eKRmQXHbQzVtwsUK9WfAlADhDfd1IBw+YIx9fx5l9ZU9Yqxe+Rhy8OnSY68a
c7DuvaDnosCMF8qxGxzqRC6FTO4ihYGT1dEnlgDegWmobDsNG00cAFo8zKVXnMBt
BYv8OrAE/do5fWN1xBgOaOsWrJ+zOa6yQNRSSRWcB8sE9ZkKavwFI5U8OAr1A+aY
hwFQEUdWWbGFX8lNk1vzvISY4iyhk4myXFyvhoMDSNJIAbdDzIm6+aOsyVIEfVLX
y+kliKeL4RiGXRw+VrSDjVI/qs/DJpFsw2CXAcdhLOQ7Lo4ypHkDaXYnsRQqDpaN
V9JTsPIYD2hv
=x+kR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-2c58-4b6b-b797-192edbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAk8Oy7qUx7HiCKNkDKzl6AeBFIS78YmdFj6HCixXyYgkC
p/gsxKVuLbwQsE97t7VFLCcUrIKyY5Xp2e1QKd8VUNYIkFJ+/7RXpmAI5UARv3r1
t3GzsstwaJJM4tm3rc8b/V17RpY17hh6AoNnnEqUA/uRsIxbLBmSCVa0WoHaEX3e
+0He1vdEQJPZF1pNyyo9FUrhWoBGMOUO2qbX1YfEGbpF739xL7NVysiQDq3Rm9XB
2QBuJXfQQO2V7xOiDBpEm/aGBzX3Cz2IKGKQREyNQ9Uczxyv/ApXrBLbBuKL+7/L
gqm3DtwOOr1Ud8j3AliHrhWXXAoPlV9Dex2e7gkycdJFAXtXKkXqdDz1pA70CdUv
jHJorL8zqmBATLZHDbCFJjWNWAMdpDemGmGv7jOyRiOzDeFJikh9Q5srUS3kW2Lk
a9RT3rHT
=L9pm
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-3018-4af2-bbfa-192edbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQgAlice8QkB7ZIFMDcc8ijhsegPqMezFJDCLxAZTLL9TUc3
vGuR5bibOyRfO2ym8yYjnKlCpuzz0TqbBaTYzJWHPDTZ/TTI7Rd/Cz4NoyZFovHT
xfFkFEeHMNb1kP8jvApzOd+O0LQMnOdvTSsaKriv4D9bBuewFY37rfBscAFUPMm3
WhMkHloV5SHGkTiZ+SIFEx1I0xDKxtmyR9NMhi9EIIkXkZsycB1QdskgrPDZHizq
J4ZWYer0gyfNz+h0bFZgq/Lm5PKk0e822F97rWLejziohgC1gwBrFe+FmCpFYv19
RzZ2RVBKYWKJc6tQk1IMuOYA4mNmuD0XedMXwhPuDNJHAbUNjx5ZgX+WrNratsac
o6OXOC3QldUCbavFt/mN1UW/aj/A9rb3OFTccmijoG4hx/qwl1Oz0ff0ejKXHM9L
gaYkMfA0O44=
=Q5y7
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-3054-4080-9491-192edbeb2d5e',
			'user_id' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/dDFvbAijXkwJ9k2TwmcHzHlq46YCf/fjsScF1TCQHc2t
2uBlR/ZDgkWIWhMj3pXnWaz5Vlnkc0g4v7UD46XtN+86DpqImAOMCkUyyt9GTQF4
78/hM8dfwPwzjNsuZZIdT6m6NJ1Q4zeLBi1pYXWueKu6d+wC2tl0+KyX25HhgoYa
2JSNYw3SF2UHNgnqLEtceouMGzclyDnumEnQJiatIn/t3yNjBM7CffMrwQFcjac7
Ki0SJXOTVgj4D1cczFBXsD6uspqFsfnHIGg1Rtptfvo8vgGJJnX374u/8cX2K6p8
KQBOnaxe72RoCvN3er+0rvcxsLtnnac1K3WdbqRVkNJHAaG7ab4bp6MEROqiDOIH
987KBJ9sX7pKrxDsMiwaYUeKNZI83hBExFM+f+fousHkPck8pas73KXBZQE80Ndi
X/zQZlh+yLU=
=5iO9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-3988-42eb-b283-192edbeb2d5e',
			'user_id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAgopVxR1ZotQLwpwedXIDY1d8uJnmoG5DE45lWf3C4mYa
h+c5Mq47HouZbwcCDPJhCg2fZJx1dGbXt8Cc/HYFl9zKAaf4yB2I9ZVHTyzlG7kz
CjI2husnpKQCkaUbmsvw5NY9zLkfjEjBZZxMCUQ0iPoV4wJ0vISfdtGQqbi/NNv7
tqcysUXoRgmVhZlgEp3MK/bT+TU2zXRGSu7rz/itORFR0j4aKa56TmDb+Z1vy5zS
OjcrN53IDBZOfl2KMu4Jvo2N/tfp3VPw5psjN+KMwJyEsMH35kVxCd7WeCIGXQQL
ZPmPVicoMPTBeDEjOypWSnVUe/XjfKVT4U8XQyc3tdJFAS7wmKBNf3jKiEOX/ZBC
f0Gvs3Bd+sS8/5Hft7lMWJoDzj4iFCpLV3QDLxu0VGhWW/y+bKYZA72MgYIHqnia
lWQN/Iw0
=ZQfI
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-3ed8-4062-91a2-192edbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAuN6pvr/x3u3jGVb/SMISIT9MgtKDiwbGaqAApIcx43Xx
w7OMZLrIFzzRUVEXJvwqmLTwXGKCP4m0kUsgHeof+KcrRibRIJG6ezAeOQQGnkTt
BFas1BbRu0ccOBuGe1rwm0SU3PN6m94i9rKhiLg62OB/KvhGEOz/+Jin9s94QsWS
AzwbK7AxzinD9jy8mja8rOnxh2gSQJiQTFxlmCvaENJeyeibJcVM8y6pnV1uIPbm
kgBQ8WtRoWktkaRP9+TdlFq5eGLEbZxnxfHl6fMEQtS0tePoGokfYDudWpm19M2l
qZW2Z4GQt2FK5rlVro3dQGX9rWS98hvUfHTJJieTLtJHAXlojdJYy6sSM8tdjbcp
Z0K5kaGhw5mPt69J5KJrN3upgMo3d3M8ZIR/7u9Dfl3LZQFt+aMDRTu1ecUdu5MM
QQK8AieC4Eg=
=sNhL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-41b4-4658-ba3c-192edbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAnpfECHiQetCNmBPVDBwyhs++1HcPsIZi8wEqpLRu6bsB
QOAH8ekc2kj4hKmp8WHFQDq/FGxKzfrAQY+ZSDJYf73AhSWxNZ8PzuMRoxV1F43Z
mT517Lxd3N1ii2Mj0CUad0EUc+qi67nM4H1s3NgUL0IQ+FGWMytDZ9Ynax+fb8Am
TjxvjDRLENNQLzcSTvpw0taAW7wdb1ylrjLXnEkS8Psovq39fTjgWHvo/vqUNeHs
BTZVdell8I3YYSulSr+sf5JakC7gpjGK8pwFUb+knMqjHS7JM/AiS4Iuk9vPCm4i
DawCLyFmFw5R4f0gifhlUPca4baYY0Qa5VJ8X4BTEdJFAbo0RRhEUS94SsBtbUpZ
56fbUKdL1zsq86AhBNp3gr3I7i3fbtBtJrM9tPRSHnLWuicAGsbmGtaGoLWn7Pt+
efzQQkx2
=cyx4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-41bc-4e70-9f89-192edbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQELA3t2bcQE+LFjAQf4l2PGegVwJjdJFy0Hv6prl1cB9205hlfs4hazmJu43cHB
FCWDrB3lvxllaRWACy1BAeQ/I841TzwRe8/7PsXh9IfLACNPaAjrMU8Zkb/88/DK
jEgnFAtNHtIxo+uhZRcWb5+w74HCltO03UtoQ/rMzd8tl+JSc9EmQbssMGssBph8
riJGzXz136c8pL1ofTw3jJGRHe+HB7c8QYc0jCIYSmzZifGBpwTzmU5MhUBaOHUf
0FXJeHj0QEdZM4zMmGQVoqCOdCUYrpk573ZOYK0IXXQ586Ksr6QrCBjxkTs4YUwV
2GeBA1Ro982SJdXk4u8gGcgKCk3+q5TdKl9CQ3Og0kcBsjk/ijGrMGgH8sGRo77Z
aL6sFhwif0hJhV1ubFjKrZ0ZkeMdvqRDpi+vJ+ehoN+G/wgmfRKXims5MMXV5IdP
U619foYIhw==
=+Xg2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-4cc8-44f3-9bb3-192edbeb2d5e',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/XLvpDm+LdWHfQpCLvVMeLcsklaYlOZGUzO437YdzkZUH
543kFnCn2k4RTu/ZsS5OUXdcM6d32fmTTRjrOr+V1q4uavHMCMloBfUYVBQ0Kopo
iVLCh7V6cHQVrJEPI7S8t9yLHZe7mRUi1zuXkp4xl6tOMtng6KLBouZSxORBa5ix
arWL3GDETvxdXFC7CQ1Z/8oYI66CyDoFfEf4Ir4IaP2xV6ZNUU/O7E2jIescWp25
BtE1d3mWmK+nu3/smpYoBLfrzlK1EsQgrBJZeOf6dt0eXiChLWnx7KlkvAr2L1zx
ikJ04NjVDP8KweRBquRjvv3XyyiuAmg2Zq4M954b/dJFATrSdcGD9lt9iEZr20oL
xE9fbhWJypwX2BOvcfp3S/N9rLWnPNTcZTsY59gPDXm5FBgWlV59I09HftaNXcI2
Ct/KYte0
=gP4b
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-4ddc-413d-9dba-192edbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/Um0Ek6Ll9MdrRoDm0ZTsygD9uJhIWVgwoBWzf9HiUf3N
dc3P1MODeADbO4sIEqCqVO5NyIIhxh0+gIvNC7naj0pi3vp6HZWGDINMSVMz3vav
0gxkcUlHB1B5OjMfEEImNFGhyMM6hoXEdEnc3WSoYBvO0biEPyMSC+HmuRzouid6
qmp+YaDJWYkan2i0iWEf5gDvErf5V/9kjAWf2shsLOONDd/ZOCAUqMGKUtFnStFn
OhH95vakZ90BI/xMIapn4VFRwxKxJHVtdhY67jPuJZCxBJSDqMTVYgDMnagtTXiL
zBvOItCvmShmXGA8ZFF1oJs/q8s8hi2H7oG0bzx6M9JHAfhcTtik1MTpUeW9DxNP
V4Jt13bo9xA2sN2ehuujD5LGTIQjQIlVt5oGFQzQJVDvD6L1wHfg3w4BjAH7u9fQ
YSf7Otx85xg=
=gy/3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-5e80-4e4b-86bf-192edbeb2d5e',
			'user_id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA7CocOAhB5MkAQf/QnfyOYFx/5/TXB9QeG1uhTujmJUsa08Wlaik5xMLLeUQ
023YBtAzGXxMCfU2oq/TfhcRbm0kQCA5HI9r3qXjn0ZLQtRWhs3H8OtayY5Z+Mvh
42hwZOUPmNmUFtAmYicTffEdbWNynnhEV30NMSSi+heosNwQPVbPNfd0QILO7wvQ
ZL1xRGqioFXSGmc27vqur7jj1jg0026/AZo2z5YPp8MgsgOmieNkESVfNHDzWEAn
gvFmTUp4NmppiChle2oGbO5fHFiwds6DR97wRhze1C1wfgGUQ7+ysUvALfkxsJOM
wHnAH2+63yvjzQ6CQ+t8DWx4wvgITqNCbpDXAuXsGdJHAVOGVfoDNxtT3NjEJrvY
qU8GcI4Ex7qJdjna76WDfWl9heX8aocXaYn3wHbi88hQ7gX5bXPQXSIbbRRLQuVO
zNRAYRazXRw=
=IvG4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-6b10-4d85-8f8a-192edbeb2d5e',
			'user_id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAijFmAdcwI22IEyvhukHbXRv7mj+XgGCFrzl7vKyWCZfL
Xq+uTY+uq3LDno0udIYG686cQ/Mg2rV3hjfSGqZhLY/QRSbkN78kvAuPl5Gu1cBo
R5JcjwHI1EMH32gIlfbhmjUNyLhP63CJ2SuFodvAvJJ0qbiQCp60sD8kIe0I4wK0
DG2WkJ05IHPQR/Dx7wSr0iy4B2J50V/IqK6MVuZHznj/R4Si329teZWHD1AEXK/6
bgh5mKCBF9jFwoKzmxxqDzFhmNHGKF/lOYCTPzkkRlemFl2H4l5x4Ghvp+Nr1gJ+
bhxexqhCGzUHk3GuRZazYu9BuYWrtSuRWuprdayPWNJHARqTf7yGar+3MfQrCzJi
o2SUOAHdygzfCJvq87vTkUT41lle0g7K5WjtZVTLa+JQTJkmJ4WWaKDcaaJVGeq1
67EsMSdUN5s=
=4BS3
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-8504-4627-b9ee-192edbeb2d5e',
			'user_id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+LD9QQ2yH01Sro1KU4bOuFs2mYYNAOAW2vAIrsTmVanMt
zDZxZIuPaFPQD3jC4DuAW4BpXhqw3R3ACwXZbLAycHkKnPjkWOPVJgVmIRERtYE6
Ckl4v6g6mpMvSSb8TKz/xsf130E3Rz6dA7pJOlgpsQHHX3U2z8TZFxtARxyx6eFM
TALQyjaIym6S1uZPAZPoG/OYbKID8EX5JFMvtCD/18mXxBHaj3psEfVsCrsX3lxG
E0qo7Vq4LfE0+ovaiZsnUAw3QI5/az9SWZMmla1nFYAxuX3HIQDxKQmGvTUKyJxN
fGHvySTPx1fr1/ZyC5+CRdzZtkruWGB2umsXBPBmq9JHAe1zJa9TP8jg9/tt10j/
j5QrtcDFEkhawmq/h9VMyQs90VZFuf08gjDpi4T461yFNJNKjKp4eONt6hdNGMkJ
pIk6IZzh7qk=
=uTE/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-8b84-49c4-8248-192edbeb2d5e',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf7BzkG0aUMzMY1NrnBUVAfa+xgR/O7uiheq0YVQSjf1YHW
hVmOKxHNvMwtpxcoMuS7EbhHtaMTggxaIjpGTuhhfPQKVCA4zPJ/mPv/V2UQ2pp2
ayeA12Jop3whxJ/Zw/LovLNQGmaURpVg5g06+Y32Rh7C3lLq8o2u+NkJPTfZ4pkz
RYtbjf2QHopEHpFUnP0AzAsc8BXUdoYZ9MNV3H4R1Mqm5oJSkY8tb5Rc6r0miNPU
jk4XXDZ5C2SAeYCE9GosmPsSoN5/hWMbHes5+TQwARXS1XiROX9U6b4R2M5Mex6g
MXhxADj9UGw9zmde2oJnQCnGZD63rBWEEh0t/x0+O9JFAQbu2L+Z4XNdX5YqWDIl
ZJW1waXB2YahASvLQW4he/AqiEnq42D9Y6Ez6kEr9wh1fbuGPeV9fgOWFbZ1NkmI
aEHrGfFG
=MK/0
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-9528-4cbb-a2a4-192edbeb2d5e',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/eT7DXTLO8v31zpn7cgs4vMQf2A5HY3zxWuLkJSV1eyYf
y1+josOSQk3OCJjIoIEvD8KdKL1Su7MymucO/TUKEW11xy+jAUiKR5VLzQ7SWCjS
1dJVbSfQrLx1L/prA/1y8BJBpzhbX8WaZSuPcEldZjOknPCrLgrzZM+Rl2Oi23MP
5FDt77O+u7DddJNSL7w1LneyhV96Qy3bmVQorqkwqKjnqsnxoznBLiR6GvlF1Dlw
f3YWrDvaGeWImMWykGnTaKpWxLCmVe9oLjS8i/HfiOjDIQl3v8+lPAA1VO8VoZFz
rDq0Msc2EW+H6m6lgv5uJxLuQtLSX4wSmMwdY5USt9JHAdxGOWZ3CLFDfARd3lfO
ayzpwIWMb6hZ3fksyBex58G/jSu1q+H6M2lELATByw+PWWBi+IHJk9icIUIrayV9
hR7UVK3qg+M=
=GOlM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-99c0-403e-85b0-192edbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQgArgngyjzgeMZPW8w3j7V6YKFoO1MncdOGrABFge9rI6IR
Gue8M3UfLp7UdJMYuqjBhr2UpCNdq0miLmAgYciD5ccI95U2fttj5riOkJmNvA4v
kBi8DhLGYEIv93HF7suxxa1MiGoYbWNhk1N/qtWIbXeIwIrbbVUT3b3VyrZVAd80
WaNjQ+KlLzA3A7dwZ4HqrsjuC9wcY1UjC4Pz9DLzDTdht8cTFMMDxKaarY+7waXh
CfkZdzowQPxicPd+wn5Ar8fSS2JDcVxm2E9DzLZfKiaEE0BrB/f+0BleOqYlEpZ/
sI0mmNJeQb/5Eqz3TI9MI2Ux921PacYhvcqyxLZXy9JHAf4wIcdulunB4guOo1W0
wCm7ByfIMg/0CViU1tAcsCG3cotx1OOJoy2+JjsqXrcXiN2LVSaucC+ZOG+JLncz
4wEn+jhFh2A=
=X1Ug
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-9ed0-45a4-9c18-192edbeb2d5e',
			'user_id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/Sxex6bXNyl9lDe2TNi/Zr+/3371iEX7ECXJ5C/SH/Ts9
5UT91LhfTlR2iHmble58PgmIBLzKDycvye6FYX+rOuVauFaP+3E34jr9gdO6BFNe
MwNhL3WmjRcbkCs3TzrqK71zkltdhY800/NV1c/WvHXNnL80XcivniFrqrKuSvr2
pyur9kHbIpqyzly8nC9hh/McW0K5oI2Piu75ZmgjwYwYSRGbkfNkacLKnagzDlTg
L76su5CdEUpMcMCFcbs9QSa+NskiOJPyBVwC/6UJSM4FkwiaU16lYEy0X2ojv5qz
ubRkve0P5u2U09CHoWB+2IE50zekDSTVaSiQAAf1WdJFAbtl2dsmMZr6iTZAblpA
82lFQxVhjP4/Dsh+Yq47+Y+07zaUR8Lu7orKFAJ0cFVYT0WaFjC/6yFiIlSJHwAR
y7v4u8Hs
=bmQO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-9f00-47f2-8f10-192edbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3k+UPTRIoz2AQf/c9Qry3Km49jRcn65qgf4BJvFSvbqpvXpQFx9e5hw3b+t
jAri6KhHeuyo06fnQCVnH5+iq7U26Trt1+/YbgsNU2GAq+0J0qFutELZXGcCNrIP
mfSsfabUcyIQOQcKKUEUOGTqNr03LCXKY/eaDINKu6IXMJbcLrVxugqB/+BIeDsj
2t3HG2yrOkcTeCqU7Tv25U8uQj0ovwEf+eeLQBx3ymp+bp+a7T/wUCtp4rczUxhn
NwU/P2gnYA0QxIbLgiUmq1Lb3C4YB+QNDEuZlXstcrVoqP2r4wJOYgHSY4S9Bmng
ThgpN7CMy0bqs5Df2M+JUldAdhIn35Wab0tXU0bxNtJHAR6zNGoXN2JGKHSkNE/Z
LM0O2ADPcOBgAHbqb3s+rJvCncnKyhkh4f/SJEhUf/iSbh1ClTHFnPzznti6Boyg
0oIRaOTcsFo=
=amLM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-9f08-4bb0-b07b-192edbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA9QpT7WOXdtjAQgAonzYoKZkUndsaRQaZirCxX/plw1zqLyMe+Mp4I96MplQ
ZErbNI/xvVQFZ7sfOGDVQJTNGx/GNl+QUkzNGIYaqPyBw1LmZUkrWOR6rgpkw3zF
ckUNwH+B9oR9mtlfJnKD+hD/PtmCPm24LP9MGgL2NXr2h4/gTbv14T0I4ILXxAUp
ZpfG4D7zJNKQ9rIeAgKxvAHiO1Gi+og15cdsuhA2xoE52lznzjkpOfQGpF5Ym8fj
p4lpDpYmsVsKrThokUby2oJ4zoY2qp4Ccu//6Mj0xPhPDtuOaKiU7ihVKLDbM1YL
VXESG/VvR4p6ulxL4IcJOrlGxdp1f9bSN0oDZx2EZtJHAZmhOtpIWjJH+i8V6twA
4FpKuKXLZeQidMZjHSNqMYTjxXLp+shOfHboL2sl7PVIL6LG4sD/ouw3hKmyg94Z
wUuptw02aPE=
=FKaT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-a244-418b-9724-192edbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf/f47zT/zl8KDIwjupPPIOOz9r99s8p36p8joxjPrBEm5X
XaklSPJM8wU1cCtDxUwOQgDc9jrN5r0mTHJ9HPp5NxTX0pMgjigpp7A56Q5tyjXq
gpDXKIlX/XMRSDU+531QBGtBmJvs3JMUnegrGIuDCw3bNwodPO9CBlwdUzLpDFOD
9bIGtDi0ilJpGhOKFyqQ0Pe2qCGLT1YuebepIA32V8yAh3xAOh1prSxe9qIyng/J
FC9a6VxmpiL+4eCtcqtiodxDU3A1WS5+8VjxwEE084xuHaTN7IRmU7r/kMZNNRsx
r/Kapuqq2VAMC8KsgH/90BO+aMAQxJVmINtDWPhKh9JFAfS77xq7YYfYQb+II4//
iUq89zdsYTGLtug7fmUNoyi/8LZI4cLL8QJBd8ll6lJyOMAunwldDQPCvr5IgMXc
paw/PXgT
=F+6e
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-ae68-4407-a0af-192edbeb2d5e',
			'user_id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA7CocOAhB5MkAQf+JOM5pIekjDz2bcaXFlIcyhOOzNTlDJOBkf9VA3WSAe1A
rTu3lVGLQ+lQrxz994ickN6ttMGHaXO7wRQ5wIQyCCEoavywrOgn35CrPh8NbnV5
ChQYdsuR3/mrpAf0J3tTnuPqmlCVh1dF22kkc0574yMQ0ZL7IRzCuoQBOzSlrn9f
EC2yIBwZjrzyaIfNPianFHbMOef1Y+3V58/hTqm0LyC6ilZNoNU55hFef6GsEB4Q
yJH6UTtG65tiLGAZjVERqy3uquOyVMWy18vYjCC5yiN4fy49UaPjG0d1cak5/CJS
FB5LNRuOwM4QwKSAlUBhbVMdb5lg3j11RaVjr1H2fdJFAa9POEZe8On7XOQcLUXj
YmOTZdE1tYoljHxxJndmHJMEv8xChEk2gyvbGX3eZKxsO6ngaWKAM6O8kL6O+X2v
Ug4uR3R/
=vrqD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-af68-4ca5-9639-192edbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAyFwuC5Agdg+AQf8Cr/Zt4TG1ZmZbt527IDrGYx+7h2rCsdwbBDHWqWDtLTc
KzE1bzES+nSRLHKeHL3LLIqoA9C1BM97OavmUoIlTH9BHPd51g0Pfqds/RlFReg2
azrq/YU8jB0dwzZ5LjBkpnlG02kFgIJ9Lq6/jCOdp/eGxYYbwfZWj24t7peBreeX
UsRnc4DE/fIigRWuJ3NeZ0AQB1rVYgxwbWVpcLYWe/yb5XzGKvdOomMG4W2vVFxO
U3MGimHWlewcYbF9+ymMfDzKoeotXb+NeRnNaeoMQ7ENiKdv7zSOECWchHOUo2+b
BtYakj74QnyfA0/engGSU8Vly0vPbRmkH6k20RtXA9JHAWWA+ZEaQ/QksNlcwYOn
F22sAXw9RADvLZh+b6KR7rTjl4ylkIQcJ8sYwcErkyzvHmgUUeFVSxgkRXthpvkB
ma8i0ogFKEA=
=4EMr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-b778-4fa9-a104-192edbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAie5CkMKSv8OMf+iElb3HtPXUANa1NelTYUboflmurC9E
C/BgnRypar8KIrwRVaYkhr23nBreZQJwfZN4oRHe5lGCLwOiZ4iILn0F84Z9f/JD
Rdx6i/EallzpcJdIsRwNO7pWBVwJcOJfFb9WgdtePXnb8fviWTG9ky4j/kV27VE6
ExYz3YnAVz6UaO5y60rHXaMAEHLl/9YutmLMR1qp5rXEmxb0FLvEuE2+QQeyl8nN
MxtOBvr4mwmgdYPaXCLvMLKbKpMXenN2o+E6RGdktDvFFny7nGya71uQhtMnxIdr
vAFws2qLy+AVJiMAaMrVlDJOd9g5TjogHrPIu7UTUdJIATXBgd2Qwaf+OZyB6o9J
jZARQqIMj3tmTA9ndeDH2M8616DSpnZJWwbdnXDE/WLHETg5Mg30OjtV/yOSELTx
XH92LL+/xp0L
=Jasz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-c78c-4072-b3a8-192edbeb2d5e',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/e9hJgRzGcXY7X5dWgRAwHSiykZP6iZi6P2sR9JENluTQ
2S0RLvZO0oWJtzKO4QE6ZgEou086zucity1j5JUz9ms9FtNsuc5VblEC13LQfnxk
JRTQ72gHc6Et4aHzgGqF/m6Rq6DHAw94oo/M13o0/2IkJJRoKsbCEyiEnRvN08o1
XDEdwrG/mRG00uk22KGCthOGL7NMBNqSeTc7aslhJMf9If6hMTrGbXZXSCF23DtS
LiomW4NnZzz9oaAJGo8Y4jroTK9FgmSkScs3cqaCRsXzv/cPoSzMY8aIOW+I+UbK
iDh5qOL2xQywU9hqg/HNgdMJk13yyAGe9V8Ai198ONJHAR/ApeHRalgHaZRcwu7F
4elzfLQ5W3NbobGaQUPjzi8JqNy/sXBQvZuWsOS/9i8F6eeoWSDzGLZqwm+ZRWCR
p2QHdo0LnCE=
=ZK7V
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-c7e8-48fc-93be-192edbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQgAqDnihegjIxfDO2a9L4Q+skPh+43QvrWFkjBnA5aoTol9
RJdjrEG9SnViiLMAVz7FO9ZUIwyJULIoDTm17MwFhl84ovM+mRswEMKmYU5WnWIn
wL++KwIZNgUF9sbRXZt00OdWALp6nsEgvi9wKlJQOoyeXqSvo7S7rNwv1MEE0D2q
ZLDk2IHN3gb/MRk5WnzcqUVMtWUZDM9AYkQ02xoztla/i6JnHPLrv3tk5XsWRIGa
R98EAfFgs+YKtF6T10KBt/GqvGSDS0fFIVzua1mDQfguxYC6DYUj4Xww3XNNN62E
83nlnBUlgwrbv0aA+NKmPj4SYLqAITdfaP0YbVBd19JIAYumY6h2KDkDomZz2I7W
KLb/ylmGnofCKiixn2UFgkxoe3w1hquD6YNAzJteKolFn0lmwJeL/1XsHx+1K5U5
pHGwMGLbqfGp
=++Xx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-ceac-4dcd-9945-192edbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA9QpT7WOXdtjAQgAsHIKzkUMSWcfph4larzO7qxIXDsP9790vyBFkrRCbPqt
aMCGWDiNx4e4wFQ8QLx5eEvfFnJfBfX045HlZ660jyXCmUN804W0+IEevvFxrxU6
elaTYLVjIbDb2tgcDUm+/I/AfG51Uecd1dALtcTty+6CLJNFB6w+tDv3K6Ss5Pym
jBbPb1uwPI5gmcqWApY13bYKIMZ4RthlPqRvsZnkmOwrqIpmEl9U1Ehg8pPALXSl
YUH3BD47rJ7ZaQlIANJ/QMX7RdRE2qpsfvyFgme9z52AYys/fEtPWJdXbG3olO53
RAkQycVLDX1tt9nu0laWTvZ8rLVPou1yulnmNvfhfdJFAVVejoo3lbt7Iht3F6vq
EUTANxpwy1EzTxeePyYdNUpCgyF92MeIkl5BfE3WAIces1DDAUM1s03BEnbK+q5f
hYzt9Liy
=1U8c
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-dc30-4558-922c-192edbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3k+UPTRIoz2AQf/UcBrJa/TmTZe4nvxszKn0EE4Fq9vli9jPhaod9D7aOvF
BCtL9MOGCd8OuiK/GkxJ9fUekNWMn61N9Ie63z2unZmxkiLUnedtSNV5UhYern/l
fwwiimn1VXxP4gFtjVmRzPIYGN8jez0PC+GjgkeTb1LFuNp2ZDNKyFxVH0obgoUC
PQp6V6L8t1/zgQJ2WMr3t6cho5zrmuCEjRhwNGxmPc5Od3S4Er0vluya5tl/4OwM
SqOJTdUu4U5MttO1aQkljpj+PJsnpNqnE2plJm09HZ6WXibkn8jdd6sdEBvupLhr
PgDq6tr6ZtCP3ZFCsu8XSb3heiFBc+Ew0ZnD5agd2tJFAa6F68DJGic8D86m/WEP
PqJXV8PJ7rmHWMJJCQcT7GeSOfZBjeHV5GMocl5KxuUZ5Ay9yb/knKHogz3vMr9D
CaNSlZxW
=Le5O
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-f15c-49dd-bab3-192edbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQgArWgj3ei+K61kSG6dGIe/n+WOLOvj5B63gH/O88ipHw3K
2e0oM/Oh0w83ZN7lQZavWl/0+PgmQNRHHoANM68Tnz0OFVE78ff1J6p7vMD7wBFv
XXasWuqlbjr1/O1NN1BxKTGCncl2SMVYX6Ziw9PV+VwTJ9VkKpugnhWxmHEp2g17
GZ+7aeEcjiHYAfoRwfeVa/ULPN1p/tPviKKwVwVSkvipQXmS13XcUZIJjYjgTSsA
zIbg/BSZnZRBazUEXbQLE1yIJK2RR/SjmWIECfU0f0D/BQZWjevAlEolFCQKykft
hs44qES7zFN+7tKnWNd+gk6fGxsDE5irz0x1o035yNJFAZg7kqXlq334LY8wEBrX
NdzSApySkRX/6TVVOwnNHbpTZ6J6VxD+om3QGNfKV/Qb/yQ/eCJVdJ2Xf1Z/fQyG
RzsWBzDO
=wcTT
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-f5d0-4b71-81e2-192edbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/ThcCrGmSnZlYU59+KJ0QwA7hXPwnUazcTw9sw34b27RJ
PcpOvCnNexVT9HGg5XhhxOp+1R5Fz2+kpeW9K0e23w+HXgm/kIbpcvoBEQQgLbPg
rtcHpBSnc0B/aKJs2/C1gArtlLjBEqsupdgwjw/YKXaZuAhivnnQeEQf0qM3Yj63
ts2RxsiaLroAlgYuoEIbeUPWR5YZ0KXU8DMlv3YtSXIKl6eIzNuD/t43eG9AEb9z
RivWAtUdxqCMAu61wB8IZ+y18TqVxow1S+WOY+CefLkFTSMKwSZMHRUZBw5mUJIc
wTmY+7kD72H3gWH2UlRg6IqJSBbLlA6uzDGB8+F0gNJHAd2QRIPxw4oBneqh1dOs
GF0uws4fktDy4kZRff0LkxylByTZUL2x9oTziY1OfWHRofQ7m5Ru1XVxELqfjbzv
LDj/L+5CT50=
=wsjC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f01-fa18-4e21-b850-192edbeb2d5e',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA+H7eZEmilqCAQf+OvAq4UL0DlDaVPvmbVO0hvTsfHrw0CqP/bAE5oC4V15m
xq0jmcw/qZzMutP1EGckj/lB4GeUYDxmuVJ/mXlwy+90UJKkdDAnVnWL4fWUam8R
vUTXgBAonUz+97sU1t2L03ULzgQ4xaJjZ5WSnJElPLYkAY5tAzynxKFyVyBMyYoT
rN/j9uIrQURsPAcSUVQdNc9zjgyxrKZgO4/YKNZjOohHRmEdBeuVJpqjhiNcZ+26
sUkAL1W8q3MUMmDdYgmWIXMiPWuQ9YSRe/aJgELA0EuxJCNbNTLMzaS8G+NPz4kv
afTLki1gxPZFgIFMhOUEfE5iMbHCgT9LEVbtFf+WWtJHAf3i2EMQuw0RDVEWMRZm
qT/7fIQy+EdCGpZrfcG0QOxEgCutzZUubTKSXBhgWvLiRUzOOZkM+JUFmhcNwsqb
FSau6/nPTd0=
=Tjqz
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-0444-4bae-b090-192edbeb2d5e',
			'user_id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAl4UKpxJ3uua7OwnW1P3OXlrZC17kqwsbBsUuVrl+3Eez
UXHMgUtlR8lYhaG3Z7UleEwGADhxBzQvII+ihTyXnYV8soK4Qw8TpbzNj1uiH3Wf
v4CyAsRv4d/QptdHoYTyNTQ+BnJMCG8Xjqf+cofGqxGMETh3p9AVgYBnZN535W47
6L4LSQV/ZwugzbcJMvR6J4nyOvHNOjCIPxJqE9OUcZimSWL3JCCyFsaOkiO5h5oV
eWleMKUrmd4FEpMJufGaVMWF/Ju4hr2fFG+BoMO+e0eou9qQ/HDMfN2xzoz/QooW
UhY9lmw2Ty/Iu7YExk2xl0mzZ3bopsjQ54aebkVbotJIAe6uNTVxXAehuxrujEtt
UUdu1bPAqfajnWBio+xnmOxeyZ7NQ6UbqnbeYjbgSu7d3NH8J/DaPnJVdTiG2PsF
hOMWlXQkc9qW
=Cnz6
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-044c-457d-be35-192edbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+LwRJ/d4gpNR18Tp6SxkeG7lTREr/q3NtpG6AuZ7coKX+
/k5pqIK0dSpJX+UX+zN+tvE/cO5IF4cYMkL3DjtuMLxgZVj5D5AC+h3kMBDr8Vry
oXqjs07o393DPTKr4Ja4BIV1lab5cZjJAJu+W6N077qXXgAXL+8NdPhNBGuqQlOb
GpQfRogWxTao7m4fCfvUfRRfg8BoHFatLBc87G26ViMpVTdjcY0z8FmhG/5SD2cU
/UcUBanwEttW246agDZBsStVAg/QGz1pj5iaYom3Js0y9rc1Rnc556/FP3wJ+JSj
oDBJTh93y0qQJY4vgGGHVZU9hfBAV/0n9TlVO4oLqdJIAcpgHl2O6PSYNwkB/VWf
GZ+hGEkQ15IEIxnnQklTy6DW+V7ay3vkQRcUwa5U+G77Zx7K8YfMCkWy3rhRrm8W
0HaL3zR4YeAr
=/Rr4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-0d30-41c0-b2d5-192edbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQgAluoamXkcy08h0mvs9EAV8OByqNx4FbQwAasgiSkmhAgM
HjRnsTsuAWOiONRSFTjhal29VU0ZybUEqlkKEUDL7Zcma5+0OOYyG5nPA6YdT6o0
SYOYqKZ5EqQ4mdesp73q3N77p0CHulUd39Ya6RvlfdsTSrcezmmfFELRx/AXDzN1
qjycqLYgCwMwphS3SvZ5+p+SaWvubeb/CJsrKsUrb3mKWChzK7x5l1ibswP4gpr2
uiS4I5/VeaCFk2WgakbqJOpFi88C+UxKeh4lan0n0mJJFygz03yIYvCNJ+kngRb5
KNNrAmGxgv7dvOIBVEyXUV97o+o3440dATtbS6C5RtJIAfrIW9wEf/mYBJ4aYnn/
MlKhnp+XQ5oQDGJelJwsEWnTItWPefvMyzHya2XGk9zJqXeFbxiLxOuSOMGIrm/T
7xbgl+3UNBRg
=D/SN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-2b34-40e6-89bd-192edbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3k+UPTRIoz2AQf/agI9nd0jp5dEZwCFysuxTt97jmsGHQcDcil1TuQSEX9Q
UDhB2yy6vSvu3aOKk/ExjUQu8jGf6tUT/AQ40o5xsslqY3VN/8Ww4Tp0/5Altf9E
GxJauowyqQdTKyWpS6G0nz909mO/fwxQahPqaXi3XzEgFUVOjScQxoh+6grZNPUd
fJnuYMiFxQfLRRhpI3ZNtjQVPp4UI5B60Cx6SdiRISjJzqVoyJSAan9TuF3gkwJX
X9C5Uib0XkzVpRzmABmMvH4g3OQQ68GILdRnj5SHstctQthLhuqY1aqWi+jBsA7n
ablUMno9lvGvNecUkryFyJz5ExEiEUhhbO/jHUsYR9JHATLA4xber7Osj8Hm13of
y2pkciIzwI+xn94nRDmCXTKdlh60zK3/SLC1fjNifYHN/Zl0QkvwwzS7QB86V8WM
7WzgMInztbk=
=l5WJ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-37ac-4a84-882d-192edbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA02SO1yBnB4ZAQf/dgKcwnDvHQmbUgp5M0jyO0A6glho1KNhMi7L7kw/T+Am
8AEelSX3ERPgCgiaWUbfCC7T2MO1gV0XT6Br+Oi7jbpWfWW0JKKOeo6DYtiVM8CM
jBXaohztcpD5yNqU7KN20ynKTShH5zvotg6dI0Tq4pIU4tsLB8EEndKdpz6untg1
R2IVcgIAu1E5Ywx32Tj/u+g5H6X5KQz7gx00NLRNPgpeNxGu1Lr6OZeGP1AJumiM
78nCvQZ71hbEpEB2FfwNX8MxCWRbu9PrK87DRpGGG+CBF4ENZ79MRxBO0BMPmPr5
VgnXjOCPQiSKTRiphzMsLwAFAdsdA64wwSuThAwsSdJHAcLSMNZ7r1qOB2jTbY4O
zbXcexKNi7l9kzp1A9ANW4nM077CsB/wNCjB741gQxZoTYSbpIFPqCsfdjh0cdNg
pi7Iu0XvDlA=
=SPl9
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-3cc0-4aed-a215-192edbeb2d5e',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf7ByuHrIoNThnA3Wr32agVxlYaBOBcH8ZdNhPWKLaPd8Jb
/Y+AaDSrmBjH3za/JXDnr4/swFCCwIL6QohzgzRfQqoujsZdE1YWZXr3i8HdGclV
zyPRGJJS6Oxsjt9AydjiBxdgWAd9NJHDLgJNOWqIHfBAOtn5dkEsXv7k7+f4v2HI
PjWj9RoAzHBL0v5lutbg0zLjodj/XT4Zg2J5ya6k+kJAihAbP4GT61jiPl1jl5Wy
6sA7ta+gN1TTKs0sefQespDVcaOmR8mjn//Pi28yELQYjwVqCQJyHoFrh7PesVny
6o9mpeVuw4oX1Qum1MzHQGEDlzwHw7SBXb2Ri7LkS9JIAYPIQyqzaPmOuc1F9g2o
jbhbCt71ULq64SBwjoEp8vTbbPoFBzthlMEcFL/Km433F1qnRkZXMKD2u1EoVo12
Z0bjOXszvDVZ
=NfuR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-45e4-4887-919c-192edbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAo2tX/p5yaZkTonSJucgzztWMEZohPUhyQbXCtONM5svd
j8d67KoTvPUl9kAmdobnaIGCslrejjgK6ZiLoS7AGmXJFGkQrTRXi9tebLxDhz+L
rr5XrkCBczHXyZOz22PiOIJ3uQGxcPSZrwE+9PyaUs9Tzsk+oFKoPIlC6uiFrNhj
SqKbO5fqmvqjBg4Zcoyv0BGtj9EQwpB6erScjwKAwlK17kGhfc1t1yWjb/1OqQ2/
LDMjbK7UbAkF8d+16KTVpk8G2LAy9scwcgE2PJBwS/hqXOXfZHLNSi4PzWYo1sSX
uSc7hRxVDapflTu2Krl2pNg2JZfHHsjfkQMdsRIE1tJHAbnnoP9MJxO7LIp4N255
bIzrVaH+oUZWwfUloi8b7NYHeZG6zZdHbTFk0jZHaRd1JKYJMnF8L+BSLH8/8VjD
tGdf8kLu+Xc=
=3cWx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-47cc-4bbf-bfa4-192edbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA9QpT7WOXdtjAQgAlO3BqXOBB+5ykAL5kguO7Ra9mDccHlT6SeMjIUFnT1le
jRkcEmbETxyMoHYHR9Mcx1XgK/HLF67JhBa2hoCYCUilLiVrZ/Akt9RcB+SIkABP
SNKZ9nl79RRjwla6+gpcVDg5G1Yie3IUm2Cf0unki7i6xXUMcjYjQJ7mrEUxEVRL
ikDipwhzGE7sojomRvRAVs9ujQaMsreyao76PQ3LncoyO2w2brSI+FGg3F4M5oGh
1GranoLTOIWZzH0MRRfPSmEhBg6Cmns6a/WUQOSYd4WGyrIDELtUxiercCIw2Hf/
XSJ6dJeFk0Lu8YkrHYDUSqc2Ev11ZfBAj7Lul7CQhNJIAeykWGaGm4fjtp5Y5lh+
FQNhs2P+wmYZf9PyrVmFgj8sjl6bJWfIpEfb0R8P9kr4VUkIAxOLoeArUg6Jl/2q
jKe8OmMEYsD5
=opze
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-5250-4c34-863e-192edbeb2d5e',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/T/Ob2Nk2lxW50YOtWGYB9+7rKlCZXULQPzb4byyLw1nj
tjdvQv6BtvBNNyCHYRYisibES0A9kNoylqrNk6qq9gSlSrNrqdp52cjZVbVPioc6
nV2aUZIcOGbqn26Y+RnAgXk/S4EBItCEdWfQEjemZ8ZkJ+03EbcNFHwrkA9KCcWn
nIV6uuKmN57Ps5NcJAEHW8pO7RPjrRZPOZGFduEzTdyQIiR8nqTPr9Atey1v6Yl9
Yg+U70s2FeO2sX0aZRYwb7D5g0CoM5vCgYXUSuNyOCX0lzIqaMGO297rewj3Jtet
ueSTR7Nfnk16VKR/pMtB559lzHi0780bKzWuVid2fNJHAQlrMrEIcF6SVCrYAxPx
Ydt25dYEgffg6OEOqxFOqP6uTPJu7NqNZImUPv6fxucdnPks3SEKuLi+tcnedZ7/
gwTA0mIml2Y=
=2tNQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-525c-4805-bc97-192edbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA9QpT7WOXdtjAQgAhGJtNz/QUxIBKBu8ocGfivYMAPnZs5betkbipOeJUF4+
bH5YzfqLMJ8ga9B8a+9fvG/LVfYi7R1fjKKaoFmZNQUbYvi/ItFb9RMXM9pZRNEX
IcAjWqJEKZD57JMe66usx0ERI6CSJ2AEcojLg9rtTIbPRA3hYuIPL+9VvkFoMtdR
NJMTwe2OJ4FQgQ0yy6Pd7geJASMp4PYm3AlOOFFH+yCGhJIilaWeIHMt8nbIf3aA
Vpx++UFdlcMu+ejxVZMg5HwSheu5xncUyJaXAZB+xr52EcYZ9Tb2TxIiUhgEGp/K
X2sSec4DvZfRw6OJrH0+N2j7td78KNJMjogbhEpg5dJHAc1++WSNkjuU58UM1Asn
DHRkCKWOf7cZQy1cJ1qQd3VfQva+Hq/h+RpQi6aNjtV0RRMW+nh4pcwYBFT6qZIO
NEeLlgWx++o=
=SzjW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-52f0-473c-ad0c-192edbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/ctx7lVIme9IN8QfTdKnKW3xEopMU/U3208oHVaVCh99r
stiflK6pv6QKhzOlazOkzsB0SDF1eJ/866FlsNWjJTX1BJhA+M1cg7fTGvMMw48W
tBHa9P+iaPo1x+FPupaFS9n2BJOgbs98akVRN8a/XmUU5N1uPgZuaWl80GfYuyt5
BxdJMcaKrt8pHmjn2GpzA1IMezv06Y+hRsAayzZBoYkMVgqe4xfbNd7HLPFlkfXt
l/d0s0ujSoJrJ1zhFNhmRMyELiKLtRi3cfUX8fe0Z9Cjt6UntC/pl2P3jUysZguh
/l+fZo8hNZNFG4ptMMiFmMhBvUJJj94ReVt+tgwmv9JIAa3dI7YTHbxzmFAtmWpD
TRbGyVrtGoGQPSXapg4sV9ueSVMGVaRFm0ZNdCkrvxSvfmWaowSYLRaEFDRoIO1y
U0AMOsYmDYQ6
=4sTy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-558c-45b9-9d68-192edbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf9FGTTQNC26SKzCcTIupAHvS/MLjIsw8ZNLSgk1wB2Vix6
3lk3TuL8FZ98URWQbExEMeMXaTFojxOop9cHWUZOtyXG7InRV6JCJwYhNhwijElS
NAhCTQjx7rKBKbJmyHRmuAHnf/1om+Y+/QztIfHrMSHjuK2qjgW/hDNwUK9MXhtS
llXkahszHJSTgs4jBol6FUMAIVQUatppAoRVp/6DLczisIJFAbVfdEmflAQk4R9z
QG6kbQTLo/GK7dyECZxmhm3OzPT5xw/xo9sNuEWq4mfX+/+7GQTzuiBjB1Q87N3f
pOnpAfGmDvvWDguzvhgzY8hOWfnnxMGDmNg0uo3uQtJHAbMvoaEALOlJq9Se0n9b
/jb5SL5vZsPg/kz2wOeixuvm3lVeif2gQndg6L0Nkf+NoArOlZ+XS08AwRgHLIkW
fPJV+06KTas=
=wvlK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-63bc-44f3-a13d-192edbeb2d5e',
			'user_id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA7CocOAhB5MkAQf/aq7jHW2QDsgttol7+lAR038iLRbI6xBoBMElMDWm9l2i
DfgBOo0NzQ32eyvanLNaAcHSw21rFUqy3h2zaFs4qrPyXanaDaO1GzEeNGdvNhkp
D/vaf8tRTJ0EqOJgJTwiv9wcQwk5/RHiPjJdmr/tHkZn98ExXOTv8fOxv3+Gpkx8
ApxdaD88pfiZcXhAI94KRwZFicf8mk9+iXjy8OQA9cUFxFAhh4W7sSEZTcXnD1jI
iJjk8GFD5A10JWPSor0/P1XtkSdEc+AwMNYOiCrMsMwhqjxA7KDGJm0vChYZ9mbx
3Vcmn2Y6Dpc7g0oE1zJbk34WGs/Uz+9nBVsAGSpbuNJIAUIWrmMDDVc1+M1U1A9G
Wp4wVZii9DKJZ2FbOQPdToou+0r7vOvcp6UxNfCEoztCUua6xkPxE68T0jiVQ6Mj
DvEWqY6qswrX
=70rF
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-71c8-4e13-b6ba-192edbeb2d5e',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/Qtm2+h7+6s0BE1CGojM9CyGfKgQdHKEEmc8EUkD+9S9q
us/LxKiSwyvqs+G+0RcyVD17+zO0h6GucNRiNMzEBnCochm/airoXkpy0FPCRDQU
escYLtUdKPkNVCNgTpSVTl+0HOC9QUVtbkPOhyYNRMpqArCk7h+5WDRvDB2ABwu5
2/a9oekRdG5QTjQMTpehO63kwssCMVCUtRneipdRJd9CIaLmk2dS1NVe2rV4awd1
1bRSkaTZNv4UQwUqXzj6K1ZSzZgFbUF8UCqHEK2hm9bubaL8Ggu/fQNWLp5ndkWK
YFiJ4JVUR9SIKpW2pqi67m8urx7voDjXnpKFRzPK/9JHAboeccEfE6RfbvENQR7F
7rfYOWFArZKdPyiJLC8Cm8vj8x2Tm5ODz5jFHB5/0vrfniTg69UjwAdk23KvcFnH
BYI8WKliVDU=
=1Nve
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-7f64-4d97-b489-192edbeb2d5e',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAn4/oxgtx/MYdP5cupCxh7+oE7lNpj4SE1v6Nt4ZilKlA
yqiunjuycwnS1JKhg67yBH4UO/47aWbrajj3BVkcqmxQNPSwoIH9seNHju6PuuCM
EpXjhSO6Vzd1wgH9zU5PEg3kVB1zS6hHJbYBFRbhMvARXF310cUpE459fq0Wuue6
z9esS2ykKDbAMGPAZTb7XvisuJtzzPm4rbgwupFl+thQkGrGYm11qXxb2Mfsl9JT
1V9uvVrZJTkb+/T5FGb/EJozMIoHVR5JocGCdgI0LCx5s1uynykcbXJ1SRI8qXsN
qvSa3wqMVRO1rvzwj3SFVYn0mGB/WVZtXMEkvmVLR9JIATN2Kgt+CiGb1NxSSrpF
3eji6kDatFUeix7jdE/zQHSy9IzA+GiBD/z3FsiEuMDzyEN0lNDOfz4OMVcn/kDT
0C0LF6EqWwMd
=zrYt
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-9ba0-43bf-ae80-192edbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf8DPzXddA3Kg2wARpWREYlp0uSA1u7ronTwDBMD+f3Zm2j
MQjaNuCFGSrGdfpXwJduliP2dzY/qJjHlR8SPkRpohX9fMVjgsBmIU7NqgtcG2yT
o0Fbmn23OxY68dtFD7yNxhBDMRNm9GXf4q7m/lP1f6hOksLQrv+VgcjjYo0/39H+
9P799HL0n7nzRFNjRfAhYcKZppTnFhasGIvk0hjySzsTrS/omxWfCLR4azbEngYS
GoCxvjRlMjxVotN/mQbpurW/ZHcy618wvDvovwr00+j42flJWU39vPxTHzMb31wO
XE1rbAt7l0oDJ/MseKpD/Fjvr8lEbciEP61XQYP4aNJHAUYUOlkpgmFHBVjZ+qvK
vh0C2cW+3ZBQctj2zQWNMnPzqQr5yjeBBuP/YDwhw2X9tuRcA51eTflhaV99TY4u
6ddwlDr5/gU=
=6cSg
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-9f58-4396-8c7f-192edbeb2d5e',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA+H7eZEmilqCAQgAwk2YxOi9MiC0fzrJJREJzuhkfktILqWJT5WTWrNqDrhR
lAPJoDDHCJc2QKg7aJOZ66ne0slXbH6ipUPVfE9+5WYPzeyIZu/taILbn9Rz7doD
OVFNIeDTod7KMITHxOLnlE3yO6tLE8QZf/LmRX7tLDWQ6S+EFbGdAJOuJO0TufXo
qCpeUy2FAEuttB0E/foIUApW1Cg1QDT6ru5fkM4BXR2Glj3fFmloBjspgxVeHC66
1gmUX2qKxcGgpw6Nn7CKqcBogNcw5eC+YIt++gwhk7oNT+eJOjSgvlFWJ7uQRdOg
UIN059yANBGy7aaOtXD3AFC5RH24xDAPGZ9EX2X+CNJHAZTwSrdBU4+oJlRSTfFb
0tdcFTaUjcU5QPVPD18zL60NUovyfBq9V/5JKWiEMbl4+/six0sxvYXLwqepP2rl
Nh9eQYrANFw=
=fMXV
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-a260-474a-8c6f-192edbeb2d5e',
			'user_id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAkyWkKsoOrYgyETG8tOd1JVruh3M7HUTf4Th6qdzLxokR
yxgFk9MDdtCb4cTSCaA6dupdOAfL8qtCyy2Wr8i3ksJjp114AeSpnL2eMjRLo5BC
i1oZgMWfp3Zj1E/7UpZbre0wKYYm/bebQtIHCo9PgGdyPImqZi/kUIwPvdjFo272
ozDF/BiJ6yq8FyF/rywDBwyzR0LKDe6dZOvkt9trUWs6mvCzeUEAPWxiE6/xHHVe
bPbqN1DgPxOvO4YM/tFW+krk1EqdKqKbfY0QU3LU5juxKIfQc13aXuxq08NfwAf4
Dauay0LpfpSXhF6cfz/DI2vyg5v//RwTUijKPkv3XNJHATCvBMZr6/IDD1FesFlg
AUDJsRHTJyRQZf1tHt9Z7P5nB0G6jp4CuQxCbhe609Qj/1KFiWTQkNu/EHLQt5Qg
5P3SZpFr44Q=
=ZUmL
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-b688-4cc2-8c1f-192edbeb2d5e',
			'user_id' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/V3abyPCDSrQGedq0VQg6fSJ3AwcPnVQRaubm/Zz56+MZ
uwhnnbGU/gKSHHZSMmilhvcV0H2mgqfNAWYMTPxRCA0SlVv6djJ3UGjZtgN1HjZP
yu2+vtwE8S2qV7wInEn5IX1SX8TDbRGhXpFM18zDauuwLUo1JgX/Ntg6ec95MUFJ
pK/6zNqC9M5CJ0MAnqI93XMtIH4+EQx4Nct6/6hutoB1urxt7Ixemi6fW7+pkuGx
F7uccoZoiXS3NUvqKYHZ4yXUetLyq/o9512fmVJYUPYrt8E5hwOTue/diyTOXc46
kJh1pUiJTvcqOCb/3nAOfYM7z9/uCUWi1WSlJ8UmudJHASBh2s4yM7QKkRp0IN10
FmYvfKvfMibW54LKXg6zoMCUsY9KpW8qIu/WDtmzxX3PGiV2s1TvnZq+VQfNnqIO
Zwzl5BcBsIg=
=7nmQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-be9c-4386-bd2e-192edbeb2d5e',
			'user_id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA7CocOAhB5MkAQgA2XV3L0oPqSVJk/S0VpwnpHKK0adqf3N0906nbKp544J0
IjT3GAPe3XXMRpH40VpDT3lKvtZ9ZA+fvC08gDD6eL82wlsHS5av7yNJ6Z2Wdv22
RgtnJxJbgG5maqyUYM/CDpho0b35o7ASQVZniMrULcYc03ZJolua9UnNOB9/f8Cu
lfjJp5fpZI+jNh++PD9pokMSDQFhJmF0if/BwbxOUEU8/3BMhZr0RkpIE8S+6iBJ
jQzIWq24KdjpOys1GGdJghRrFLx8gdx/2daaZ4brdJs+Y7zi+n2E9zifIGCFEdw+
7WdfTZcY2qUXrylxsldThaYQSnKo+u93Cx3bP1R0sNJHAfyKHs2sJxxH0geQxh1L
SLpOQKAOH3D3GSxkTGTWpqmOtPrsFZuLcEXRoV7pDmVC8QCSJgwWNvxBMpEm8sAP
FQf36etUCcg=
=QThQ
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-cb78-45b4-be29-192edbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA/j8075xVanzAQf+IFZ1L1CyfQpouUXgjtHwDwhb/0EHHFqMh2KbQaPXrgPH
hzM60akHytRmMgHbQopRoPvMcVIrIWtANCwbjpL8yj0V4eS5lDyhBuuwSiPwoNHM
X7bMrL4d+n5YlKj80Kbwv+WTXpUHeR1ZGUCM/xlFTeCWd5J+UvFrJBY0nm00RPKx
dNP9/nEbZ85SBIUoYM1Z5pLDGuZFv+sU+s7eed2sHQVbe2c2uKfVPLpHG3UZIexH
WVbJ0B+/7ri/nNX73L9UnjUzutk/kGwQ0O65rqW7fR7M1ESze9BDBfx+e1Hu3EI/
xQF0A+RoF4GBK6yDwYXLHZXJSM9kHagCqabpwGPdkNJIAYWV6ff1kkefxWcvN2x5
QcdCRJN6GWSTHPnQvwwqe08bZNs7nUVtO/9QwQ9J5D/sfaQCn/d3eHZ6Rq9FmkdH
vkqymiSxsWE8
=PN3a
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-cd1c-4d68-a750-192edbeb2d5e',
			'user_id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf/cLKDCFbYmlJoeHrpkQ2t0RdU4vt4anRG77GEvqiss63N
xwqUeONFRBGiGxEnvFBXsILGObJJfyk2bn84GPRRjWKMCPmsdp0i6Jvos6JPCJs1
C2AhpSbOqmGJv0JdjUrutIjEp4IDNRIoB/1ndovkKvHJ76pTQzV2s3DCv68vYfLq
Kt2LRk9xlyWkrjp0FBcEWnDzoDp2l4M9XfojIt2HEHuivFwrYd2Wq7FLz9O0f4nV
+tDwVgA46axcG3xdFnKaxlGqV67AxdA7qiKA6J5ymbf3IBTChFKA0DSDl4hmxgdJ
LYShdTYlIdtf+7aboChND+B4NmVynSiFAFxC5KConNJIASh8jYLbK40cg1X5y1tZ
CUrvWTG4CMtgDPLJzQtyWzZQnVWvSOe/fBjaA1e9idvCDLXj8cgfrlXZ5ZaWAs9F
iy/3S4MyNO6K
=btNX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-cf24-4881-8cc9-192edbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMA3t2bcQE+LFjAQf9EWWNCJ21wziTooMozDT5YmI2g4Z6jZIIA76gTr15KSBZ
BkxDU+g6b+5IeiGWHBhLAvqrRm1Rpq81Fftzdqw+ydvdbFib3zoSI4BhHrc85Oem
kiCGUH3uLSHhgDj76tX1GGfG2HQar5BgCDngf0RBHtSpb3G9myLyPTEpkxdRniJV
/1glc250g7ogncPBf3Xt9ljvpNYNX362NoulLBl1wX/sSBNSxOch4GgLo71t5rSe
5/js9Cj8nHgHrZnr1Xay94O8/chuQgjdCuwc6XqejT1vpkCbwieCGpfnnbZJm7nd
NOH/tj1T88bMi7l2zIZyD3bXvRGvl2MiEz4YcsAr8dJHAWKu1NnWjRUuir0f0q8f
WTCysuy1JERG46DbiuL5XPGGgXG/Bh+nHlkj3pUse46lQtV+lD+Ze6+/GP991zR+
9F/3rfuGmNQ=
=CTYs
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-e63c-4be0-b2fe-192edbeb2d5e',
			'user_id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf+KJaNYqy6nh2kC6aBUZZD6PGBffy+QIvqNdZMQUZAfjAk
Hu1UlSlBIUSTwVfkW/42cNrBd39tTGFlq7nzfsnMUzbEz8nFOOsD2zy/TNuWcCFg
IenO5w5I6Q8NDaGINRnvUbCo2wIhmIuH0RqoWxBwEVHUCWrC556c5B2b66fNqTpa
i6NUTGGh0fd9TcEnQDZNjHnzMzi4VOGLrzZNIT6007YXIkRz4m0JsJEE+nuoony/
yBggrFccjz/cQ7W1qi+UW4olf7Xwcdfm/OJ+BYXS2S2qlU/dLL7f6zm5BxHLxwD0
1hvQ7Jwku1lRRa6eOLWtPImYjV6PPnSW4AT4hdIulNJHAXB47vvLoEtw6MCuBeiq
5E0R07bcUwdw3vFoqChLt2haG0Zumcbd6RuW6CfBoyQBA1a4FHDEwBMJ1QmwzDUt
RqK6bkvcES4=
=sXN4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-edac-405f-b26c-192edbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQELA3k+UPTRIoz2AQf4o3DUlIpKpItmi5y60SNmNFujHMDl5j+w8iX20VohudwZ
cjG32go7e8dgmgkPUcFuMoJb0fM6jiq0GLEiDlvcMsIfKcFrxdENLwmIAjH8Yapb
idnj6otFCv5a+eEUKuxM4FcFzIVh+7j/Mhf6uzyNZjvDafqCHshY2FXAiSxN/zld
v8CaWnY8KycSz2qHWgTgoL+aTMUk4k3p3aCBvF7pppfazv6HugtyO2vsDfS77gmJ
UpUz2FUbbvV3sZG7lP7wcZAVEEK/DEBJCF46N6FRcoH/fOH9ZRVbQNRXd/RbiDnP
ZVrNWbW/Tdi96JzaD/c8rqM/4rGr4jjcZW4M4AgV0kgBQ3Ai+1JH+GCHm/H0cWcJ
j9jYyJINJmZQnTT5eggkS5BB7+WXy3QS4D377/NIFVWC2cDjbCf1fQ0mkcYQf1zi
iz7Wy1PiK8I=
=xrJD
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-f074-48ec-b7b9-192edbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAyFwuC5Agdg+AQgAkx5Kh4HAbf3ZtHI8GexG+g0+puxCD/dD8h5DS+iPRgdP
O27M+6mhRwG8/lZ831UAifI4+eq4uc8Z1oqAD1snPalwAJSCWe9col2CqivTJiC3
MxVRYkxGhUibCEa0OAk+hqtILm4ZgI5mm3o0Tj9OGumegup7lL5TQYIgDWRmSMTV
gfdobIoo7qbZYd+XSAC2llkOPIemEJPooWUa55cenPeAIh8h+g7l5i4mHRqgLMVm
D5fQigu/5lxU2Ewsj+jBlclLTdg9yIN+hyQrbJ/lAx9x+IgFdkOS+DWMu82kPbAh
07jnpbmnzHuIwz8d+cT5DU5eDialu5YkPAGvR48DwNJHATkXPY93nt1kl2Lx1OlM
vfbakUGQbwxd5ggDz7LF1Y9SFxyHMkzmWFZDPSBkaarYU7IOjrKgpE5t5nqRm6pZ
NAq9abD9niw=
=JZsf
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55842f02-f918-44b9-bd14-192edbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQgAgjO4iJIgY7DwcIv84iICJj7LUB8rxi8hclt0cp3gEQOS
uqaUytGSCVDA0F1owpbtklomfn5hBrruwHrMSY1SLZ7g0kJpLLqXIi+YdlQkHyXD
WtQ2WyRMtZP5PNm9IVzvJPaZAnsEnmZAfXoE2/R1nuCAx4L6sRubTBQd5ezsJ+x+
ukGZjMkI/dxT1gAqFi7bhhXSXq12j1GaCVJFrcvKfkJ7JHt5WA5Rx8/Rf+Ynt3mW
rZ57pZobqTTWRdiWXCNLi3wR/JSbrpleQKJYRJCbx1s7BO6FM1zTtbiCzDxjJEZl
ubU7UWRGUlKXngTZi120FJ3VEiDk3D7xR2pzLNI2adJHATau7iJ8pD/dHT4m7ToP
8gZlQFjZKs5rNo+8s1ZIWao8lb6jMFFYVR30ZrqkiGRdznexoE+clwVLZyGeB8pD
tYg6b6TGKbc=
=4N4k
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}
