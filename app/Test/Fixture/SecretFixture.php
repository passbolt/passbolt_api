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
			'id' => '55c75362-0ee4-497f-a070-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/d2hcvYOIP2dWGUBvicKE7OjOTEX3C50XDjJoJCASRcA7
INN5gTITG0CcXlsOj2ZhA54rGirQamkdoC8qO852tgXR9AaRVhuXe6yUqaSpOAeH
x3/Lr/doG0ATzNRuzEPmQrj7mHg7Haahczjix8ISvhKF6XBftoyEgQ5V/oy5jzd3
lc4Nx/l1sUfC8Mr7gb2EVSE/JEQaGQOjPQbpTZGGHuwnoXOzf6OOzGaPN4AE4lQf
07EO5jKgyNTy58+m2sd1gdwo7izrv7kwfLwv3hNrs5VNE8DcplN3f2vbmgxgU8tx
uAOKHr98IXaf03h402bHFoEPeIc7Ux6w6ezBg+ZuGdJBAbkCvQf9tkJrGhuPXU93
1svxbyZ9Ta7M6HGK8SoJHCjQ6E6VwmPTe5PLsNITN0ZMEs8PRDXHKvHQ7E79ZgQ6
LPA=
=KKrl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-3c2c-4ba9-9aed-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9HXbuWdBM8OyalTSbTF99V/J72XW/3LMH7jUqn1U9ekL9
HtHEgQl5x0y93UIcRSn5bPq0AgO9Z1DWd3SMAKUaZBs2DwcuoTziT4VG8Mat9z6/
VAhabNq9vyeXsTcV70scoAX0hq4XWXpQIHaYWuwj4y3cGUKC5r8EmLoKjHnyCabd
/6UIKFX1a13ZhCr/eLO/bmq2OLpsT4sKvuAoec9kaAjjRQ18TQzFS/3mXJREUd5C
NuO0qpWwnmSIFLt16JwzEg7uDCk8aOc6vFVgjuflwbUpIa6z+KywqKEOO4VBwimy
6PiG+ZAfpkBCj/Ir92Yfh35+XNGY3PbtKFJuvprCrveHBCIWW+1BcAqRrLXbnygD
Itv6awEvQfCRvQizMyTo+4ZgC0Ln1k9byD4g3l2FECi2fRCurJ1qFxBEavHETqIN
7UrlwnGlU3S/m4vSeTRIui5GuxhGGAVurCh5zduLr4ENM4B+Hfyhs3+e2BRd4uoe
0h43+x5nnBE3/bUGOrWJoTu8uH4pW39pY3Qgy4+cbnqM4PID2UdDGAKBNNwacENv
WwMDR3z1Kebyp0dgxImq0E+jfaSVkgrpbKquoG1E7Fu+iEnwLehLw1cNBJHWNFrD
tOS6nrGcoZs8h50Xj/eXO3uHOjvh5x1UzIPrC0iVWbvWgj3lUfOkTJlsOxDlj0jS
QQESuVvvWBzSMbWzWARj5O2wknpz8yjTFTrA27RfKVbCKZCQws/GrBHFUb8gjNkn
F6s01iYkvbMGkgRWeZLEau4T
=GKhK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-405c-428a-b25f-5939dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/+OcrrOd3AiehX7nrBZn89aP/42uYgoJDRj24g9ofdS/8o
Y1gV2LoG7Af6L/nUntccyKfcSEE/JEsUgkeygdRB+0Mbu8HNhDOrcb+owjR3siAX
caubVBGnPmWYMDizG4E1M/gPlXkXr7Qrbh0PBb1c1XYGCiLTSqJUom4kzWv1SBm8
5kt3t1taImjKeJL10fNKtpnJvAZGPmWsYx9CNkfpSUP+i1f9Yi4YEyVMCtmA31Xf
3aeW4/eNMFjSO0bfEwDiqSXTfBEmSywRR8KBWvMor98yliXusRN8CqNP7cFk3bXY
sNjYVZGmI/WBtvmJdX0XOcoPxlasAQioM3u+NF+dP//IS1iXK+MUYOfleFgmlrdV
drqlXm7Ua/Gb9VFsI9EohRTAlozPtn0OfKrekwqbZaInjKexGQmm1CutfCkQkMo8
//qzgjDD7bI90627AGrwWHb1HBp4ETtH+P7zfAh+xwbfoaLY43QGEPNXE+VEPFC4
HJyR4ObDrnnitDkszrnc7Tq8oytsdi/C2vhy+Mdrv+vTepAvPF6cnPBaM/8xbO1/
wUvF1M8l6oKWsTQGkgamX+23EFLwqRfZO2PghUf+ATXkoJqvmJXgI0gsRQI4ALMt
+b6txgTyyZIMmxXPn0eYhCwuv/Ta2aGSgHrNMgGzNB8i5ocJvyIPi1F6XkUiC4DS
RAEpEwpx89sYtJxN6QjT7NT8SlFvmFKZaqg8ttDCJgK4lgTAakjcOYbAPUYOsjzM
2x/GtXSDpbPwUpKq59nR8pGDBmyX
=uL/q
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-50c0-4086-95b1-5939dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//SQ2+z2SokBlN6LcCML4lDQXSD/5FJ7Ixvj36X1+RJP2t
zPb4pnM36W5fSgQC0PSd/FwrFRaxnyHE5t5ebsJ4BjAwPcJrkWK6CO1H5mnjUFPl
bNrlnXef1VFBlg88W1qzoUp9vAGvBo6ryNb7SoHvKfg4AHOUtPyT9+IlQs4nzgRh
t6owcaJ6ZikmyXYNzZspE1ExkplTlGm4vE2l+2BZ7+Whr8v/YGs/L9XbicuEOHSD
OugOt6TIxTfdqYAQWH+BksHkLsXiYE0sHrNCgc3qLR9aFSm/K+hExPNiaW/lENH5
NEbmd8HArzo/psCmDdv7Tw3TggdXNlOK67zEGgI8kFZ2WT3BF97MHP1sZEGa0P3B
b0fqXN4IejPSCOlaHa2uLhUSVk+VkATyIqQ6zUtUjKx+KgLskqNEd9ClvVgte35F
ddTV1pYrMjLdmaA8xWZ14Qi/WMTC00O/K0l5BRscGzG7B5XrcRAyoMmYdxBacB1b
l8HGaySy4xYoIKod7u18yrSQw6kspipMKbWoDgQlSHGm9QW1L2SPmzGuTgybMLsF
zQlEe7Ww3XA1KSkh0GwMDXMN52H65xmi+61nmpoh4t00dORY2O69hBjOpSiM40MY
uPn4Li5L6n6hPBq+W+hjULkOFfTFDOFDrK60R0dhXQaCnkVN/BszKaFudKRJ3HjS
RAF0RF/xF3TANor3Pm1JY2gdQjyUomoFp4lwL2ouHKpkc77RINWHxzwcw+dOBGuh
DLfvPIIDoI/wA4wi8Df9Kj5vCpPx
=MP+R
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-a9b0-4e4b-a426-5939dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9HzLInEnOxBKX2MPJ3aJyZJW9FZJhz/M/qrRdm7DiVp1f
fSQKpvPZQU5H9BFAlXM/9O3586qeM/F1KU4JRBlTkqhp+8bKZ+crkeIK3afIM9Ud
twTz5l1wPdJSq8PuClxSQomRo79BkXUjOFOeFTljeZ0414Wp3kWPhk3H55S8klWx
12C2geoQ38hcvYsmf2Nq8huumJOlVCBFRWjsQq86KgpJaj1Xhsq2l8OaDc8zXhc9
YbUvHtetFJb0QvyVjri7jLK6eH1OXwbiZmrkykExfxkRsdpPSuJziOXq1Kf+2RPp
3DPmtnTr956UaPR8XN4Dj68P0GvDCHOAAVnTG9xLR65cOgJ8BALM5pEJR5lMK692
XGganFyDiNS4Tq4NmglpQ206wNpzoAJDC+aC5oJ6pbMHExhtbs+JwYzXRzZ/aChY
4HPHGG5x/skhTQ2GJS5rfPnctdIkH3B6eDM1AuyX+nsxz6a0S//g1Pd9Qn84cxwO
sKT6Y2eKVtAsrMzF/oRRl5Mgwo7InQ4ganiTKJwlWjy9gXbJ/4+506Zn1I6vag0m
eIWqStbuifYdEijIQ+HEkyS7jSA0B5IDkSSjhDorX2X1f3QwsHy0fqNWwh+Ea0Kj
UOmSSoiwUSv8Jsy1iomk0BLDsL8immLHDAmwX24/IUHcSEu+ensfJCopMI17vPXS
PQHF2cMic6vlawwMqoJK7wcPC7aPrr/BRhS5D2SZyzglSDxy1dImO176hd4ZtX0K
95uuQxfVxyB0TCZ/VWI=
=7EH/
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-a9e0-4f1a-b99d-5939dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oAQ/9HOOQmUDFdv3AucHNN6ZunkF/+iUffCpVpXXlrle43Tq8
07bBtVwIsuLcpF4FhHiFepZ7TNYmbkPPHk0aXiQ18UoTuWTvD/reO6XHyck6NvOd
OiTSEkWFsbXiCQItStWnNM34nB5FtLIj8kF/8S2vOdkUNuofmKJAD5nZFSHGl8gH
QyWNQUbFZ9Dn2x1pIfsmU9+TTGQqc027Zjkp2y2vUCujcmnImWN04JpnplyzYLqy
hb1AtWwipaD+mtoCc1BcAFzp+SlUhZoVzBF2ERSDly6lhmhpzpXKTYc7R1hETOtH
HWDTGG3SCfgAlbS1VzBtHK0+HCAbs0ncE/Gdp+w05Kh3BfgNhlIet6DlRNVBH2Wr
altOvRLSbS4ZPkfyu+mQ/EXQ6iygwF8yg9BohHACddX3S55N8QlFFal/SjrzIIq1
wSacB/5DoAHtGdVSKUx/DkLFvwCGex9RA6RD7yQ9kB38hMstVyFS0uOEzkaYUWCm
gqQ/TazeEj8VjVIuJEyuCM/oiAAqgSqdCIdPol3/uullM2FXKM0F7+eUDeUpOADl
rOzVQ6pum5OhyB6QI3J11moBo4coamVuzMJCYFvtNQ30hIDWU6EGE7qXThRnEOq5
gpTJaMN3f3KWZuRkfWBJ8LCgv+DFpYdOOBX3+UrqnQdlZYcRbDfoA/xyGtx+HNDS
QwGrewmLWJ8vhCBIzZGPsxqQs5/2kWNYvJpJpv1iM05zRDHd3v7YfLOEGx+xDIRB
NkRXCHmgiCQz8Gfzl3invyIcjRs=
=FcBd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-b388-4574-8722-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQELAwvNmZMMcWZiAQf4787liNC+arfvDxAYe4KonrJfjd1X6+WV1gKHi8Gwkzjx
2gpUuflEJMNJbt4dWxE1hp9wwLWGbG+Qucj4J3fSzR+NB6iXyVnISkjMJ16nznZU
iDR+k6ER3Grsh3hjqq+hnJcssas4Zyy7g9QBlt0vGSvRQ6J+D/jyEKuqMsUqj0us
A31++w4jyhP40CNP1XroG91jBfSKTGw6ZyMyqqv4bnPkmdlPTOKuU7XTeL9yjmo+
OeHp+axYgCh1JfyrtUgSQNJGq4LJ1G1S30OKOKde/7vfDwPfNGxHaD2ct/BQsUWx
zsAKSUUPm4E/kT6UgWJtNMbmub5pNGUZ0FPFKgoU0kQBztEgueZaCGy4mkBSc4De
tmRDOqvpCRTSUBO9Kym1fqSiA/Sngy4U5VOUEvp4V65XIAzIMcjIRKe62SDKmw1S
7nogMw==
=7d5+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-b7e0-4107-b8f6-5939dbeb2d5e',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'resource_id' => '408bb871-5168-49d4-a676-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAzlz3zlJcBT3AQ/9EjTxfn9Mn+/qNyIgAMawFbcHkDtGuaZ+tCU3XMtP6hIv
oUJ92RqAiIog9yErPtLKhjO646GGXyymnKEvb7850PGMAaziqOaOqosiT5S0bY/N
FMuoA7r+sLjh9iWLBzB29ocgEBsA9azz89xCyek1rTkLMvewlelOmPGAP78yu8Wr
yiQlQuul0xRNsQJ5mJuvEpVIB6zuP0wBqNQBTrHrVnD4AHPhETKt8ZxrbKa9gogg
HjHhO6JjrgtC1VlSEiyupKWzTA7q4NOjVmwE2vBvNhEOs02pDLRHpfTB7k69q+y0
IaRJKOZqNocnKJalAsSF49DrkNMH2elcBBx7Ja3cREARHDpiOMCJ4/ynB6YbmU9E
Al/QTdYREWhEPRRRsN8hSxdf2jN3It6Lk3JtVpmYmFJ4V0I2FphOGNwKZEV2CTIO
nXFUdWRFVprz+gKyxb9eJPSGxkEnIq/VeSfzyJ3dLBBEaphMaB7gAXDF5eO6qlcD
gGO9yWyzIuGntdiyBlllv9b3eGuRWgFZ5cPksO2Q4LZd3+9JsrnbN8jazFhPrHB7
YinUr9RkS/yE5Kq95aFQs7/d0b3p5Ygaa4SZxLMsAL81g99qH/EpxHlH+ygKDCra
byKaqi1Lu1E8poxO/0XiG7admYBhwTFq5HScRYQsWw0ewYDZuq1uE7wm00hwc6XS
QQEzMj0zIu0KAqBqkDXpWvg1GPIZrSzv/3xmoOeOmUieD1PQ+tEUBxU4i+8m2sPc
lV3iJNRO5Av0bH6ZallzeaBz
=2lcM
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-bc24-4d5f-9673-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAm+BWUW6Tvgj+B3/b6kVaWRC5HfjzpoTZkJkCvtDouLCy
PeZPsbxO0R2BJ+d+XGiCNd+zvrSvP/hA3qHAkXz03knbw9Drwx6wHxz17JU9eedE
DaQfb0uYG5hcjLChURnyzaYDQZ1UZgyvclR+kTf3NGSW8ozepwKu8YWb0v9FnWas
o3TxirH0IeoWh1MnXjT8c6OUOzbM2UTkT7b1AKNitQtkDiD16Ezx7V/ZALZ84wm6
Zt5mm4DaKiOZcNrUlAcGqX3hQUdaWwuXgqru6X/jPuHJHCn228i+GQEpbZx9F9YH
0ZONp8AiMg2nlT7iq9TZVt/wKe2HF6qqsz+4ll0sntJDAaYd7+SazEp3Ks4K4ihj
i+bkZ54kO0FEVpQE1Ps2LwFhQEtLtmx2auJhiAjHm6JveW9u7FS4a8juKsJKgTTN
yv/1Sg==
=x1Oy
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-c848-4b7c-b0d0-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+PwOVIQXzYlVyN9REH1JtcGHgTlxq7vnzV0jYpaOn4V2K
jCZgEeePwP1KOZfS6oT0jSnG1ni6SX+YByst1hrIA8Ci57J+7LR8VDMMuTmwJ0X6
YPRkYq5Cc9uYncbnOgKxRYW+43qwEDeyZawO/nEt+eGt9cxBKx2KGHWoHA3FsPOC
Xv1KIG/kj6EI5/cjLbCSJX0JW1Akj2UTVru+QaKkZKGbigm/tKUxbPu717S8a1+4
jFZr08NpzNhrb2opUKudPWq47B7HjCd2tpxa7lxJ5/8jIn0LWNSR0uPmzDVazZff
2E9TjPG5wKic+1uVUG0hUDJpCGYeeaesSpkotHTUeBs6ii2CxxWx7ChbRRDuGWnS
xAWWLvBQmR1mlNQLVHm3K6IK26w0QxBhefiHXTnwokAEfj330u/Z0wyXLYYDJM0c
hdOHaMAvDdA73muN186cieY7rIDGhU2KPy8FrtRfqANIyOdBAckcuxGX7AkqyVjo
LRn0cWeS+hyjT6n0vL2qpZqWCS9AqxFkQzwGXVBgs1rrOrhQKM9OIXy2xm/Iffil
JUgRyCzMP/exMTjFBPjwCtlUAeAyQzIg/uu/vpN8Wir+f5fqNpVoQ71sWh/tIMbe
Vhxb5opiC0Z7+SqoK4zLLYu6oGckUvJmrwBfukFC6bX2tpZnimQCXLnnxLb2mITS
QwFkdH+cOLk2A7S/p7ywouJKCZJWkPilV57FyvBeJiDXaYqIAc0Qa0yrIgI3bdWB
7YXQOcvjHBi2zGqOv8EEqFPdCwk=
=lBZE
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-d524-4cf0-9acd-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAoeXadW3jOhmh7AimCLkrMYPVwHOVjPoJWPdU1XGIPBQL
JRX6Zjq//od3Ca4ZfPXXujAhR22MLwJMDZJ4zexLGwVuOM+rWr6p9SGxW/v13Mjs
U5vKaHP24R+WtrLI6cXVdHOn1qiEbccN+P2loCm/ssEgGPy6qHb3HIFk3Nq3qZm4
Qn/2pIPaIRmCVUR49KofwpI9lWmN5xZXwSHSxIBWoZOV0WLawVHv+6ChNhNHiLND
Mk0oqFp13fvC3efYP3uNkLwu3VZj0sl6ZTWJXjlqLy0dO2UQHvRsGYmHQ2eUMZWt
RMHX0aHdIh7+ZTZNavbJXDiKn/M9P9H74uZhqWc/t9I9AeoG9ykV84rDuWolxg4z
WG5AqyIqgDtL60oRG/zqA5B0O4OdWqmt9I1MImf34J55w5w++4dHfHHxijXgmQ==
=/PTk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-dafc-4ced-90f0-5939dbeb2d5e',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA9nJydJ7HCYGAQ/+MM0yXyvet377XsJu9R2J4c767BmI79YTJzEScofBuLbq
YlURZmqNgdSwXe3fsoRwCDgS7Iim1XfwBdwHjfWIrwT9Vc0YniIFKOWr9SxxRPy+
KnpjLR7N5fnauSrPMVjBVPT6tQVW9e/tnf0JP4G9etYAvPEIfEJQazXe4s9jMkBt
v8bHXEQnuILfdZkN4JHFUGVS7VXU94sV0h7E/rMYWDuhARV/1mfJw2xgwISXMrJ6
sLMFCZpNCksRJ2hxRnyIP5r/vTKS56xkics8LMJWymftO8bJJ39rUFB6yvJZS6CH
+KqdkvSvwHXo9WKo1dbaICaiotabPxfL5m4z1O2ZUJaO2Dc3Ha/3AKIi66yd/HJg
AJKHQX8muu1OEZHbuRNNpSrYi5nECc/hjjUer7bpRP7lMQeQQS7wwtoAjZ3NAwR+
bNJMnNfxD6sJwBR5ZfvjHpT3+NQoXK7dAo+0WePgce6WjhIG/EsKN9gfUclNGM06
kwBiPadCsfXiwO2bMc5f96UC1NS3LMtMMg3J4oOkhFCA5yDp4rsFWJFjdk/mO4Xq
khe77NkaGsfT0tQD9VZxPOfsvUVoD06MJ77de4syUkeU0YF/e8NuybzODc9umq99
t76G1fWfYKhXh9scBOVKxWK7z0RGTOGCg7kUxVU5bo/PEH0ylPsaP9QNDDjjD77S
PQGLHSnrgM9sfiNOoMTpGNF7u+kQ9lqcdawVx54aAGWCatDeeDxFElvUGt1R7jzY
Kt/QcjALtZ5POju4p94=
=/+X2
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-e3e0-406e-b433-5939dbeb2d5e',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ//fcMTklm7imnyKTwmt+WOyj+FbqGtI3dgR7HDtRsvCWqx
4RB5lUh4Xvac9JudS02ML9bA9G3fXUM5Ar36RSQlbRet7wuK79cBMwq8r5WXt+Wv
Yuyo+tt5En4+/Xph6J5WlMnD2x6jgqYQtOvloDCxuT++4Hm6MWbXNv+t6PEq5Q81
LJfg9wBF7Q8x7r+xbMHmuZueZmqOpLp5BPEVtU8Zhh1WnsVacl8VoImydTYk++Hr
2cqZ9eHqK/OmHvrlV/ziyCsgYtGuAt+Lj3FclBN3l599pyA+47Hfd+ppNIhC1isf
bEwi8O6o21x3jUiZfmLQmm/na7Tt3w3tjdp6PL2dOpT49aUdtJLa0zAMmGK9AsUr
uHXNGSLzSYPThxGPtsKYLJ1JIOluBhnx7IftIdZ4NQxnWT1kYNwpjzVIvQbrcZfu
cKriT7sBR/HaLQe833lma5VkuIcoLutHEaH/AY50TnTlMIuyupvGJhuppbzcvPhG
7x49nvcJ0k1g1Bi+eJ7oEVUi/LAeE/XoE/IGVD1j747Ds4qY28SkpqKe07IkEJBq
AHcn4mB4YGzGO2Qq6/Ad0ecMD/Y17uJo7STH0u/z6dDd/IIEp9guZ82QLZFQbvfR
jer2cx3/2oAX0qD/wlPnA/A8IwdK4SZF3eoYkftZA+jKnM7g2Ly2hf9LhZJgYpfS
QwFRJ5EdBxUGa6bsTeKXEQlNtxM7FI1T/KPMEBdGZ23KLdkNKU4TOzJHoF1FJfKw
Ns0sIRmXNW8E+8GpCCBDVbF4XAM=
=cIWK
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-eb70-489e-bd52-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAApVf0V/g73grpbuYqk2vkXeV11JozPp1kBc4V+r03Mvhc
lvx7TdIPO+p+KvIzzxg9M8U2c8RR7b7tZz1/3mXoRC59Wht1vNKs4ex9mOrOw4Tr
Vg5NIcN68TxS0C9aBQRmvUlg1MYe/si4h9zw4kkRhxwuEhP8gtR6VYIeXAUTybDl
EPdRg/Bel9+u4FPKo7j6nbRB39vgXx2N7xZx+qWYaIqyLjPzLVerVFufeJK2x/MB
sjhbGOrhebhKD1bSPRvOv8qDJNznZ1GCZ5703gBzPAmOyadn857hM1RWYlldFcJ2
Ky90P1GgKVBlYxem3JaNqyoWxfnO1k4hVgQ3R9BBP3XLMmsj7Q6ZU3kpzUQkkjiq
gpKSkUeLNH/BXcU2v/Sp1Rm4gnnXOkuVjbhp+GD8Lpl/iUzqw/R8HXGOf0uOaxOG
z3+IUjBYZMoSnY8JKJUTzdI34LuhyXVHlIuXBOUWufq5RuOcxTkcn7ara7Bs6/s6
RUow2bcyZf0HOP5qDe5RggPS1f82aGQLwB4/ZX/GhkZwj+cPkx3fJLghaLqNOwzX
UvXpcz8uymnBUs1bLhe5PBl3v08Pe5x/GNwVmVDFbLqMdPkC1+YNQ+Aio37Y37nO
Q8RUBqPDz7CWDwcPfIUMM0BlVXz850hEUjYQqElV/eJ/YCdSaDB33S5LJU59pwTS
PQEqyzqnxzX0rt3aOkddbPlfsqt0RX4d2P4LcTG0PouIdZlZvrCtvPfIUYHtQM51
dt7aRjBVxHweDXxQ7B4=
=mLPR
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75362-fa50-4974-bd9a-5939dbeb2d5e',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA+p38wQEIh7oARAAzmpHkvaEpO67e/1QuheDob8d+9Hq9/ogGUEA3OZLm2xu
4L4ZtAwKGRj6rqlQDUS7ShEC8qSCOu6FRs6ctxVP4o728aBP0OuhAgz2AD9wWfHo
KmROOdrLMhx27N1G4qrCWnbeYFKbx3oaknbPtufGZDAiSveP9lj0zQI5WvYmvr+K
rQPpRKZ7Vq648mIWqN/9KWOpPpvUMmof7nA//BJphUqOFOh3VUS60lFCPwntzPXQ
Th77Gz6vyT0XmvdaG64VjHWWRVacbau/K1DqHCOQR0693I/3Gsb4PlxEm4tUHfu6
GqoSIgpmf66zFIBsnW0OE4ys75XEQEKQgme18hxURmZgL9v3zD9kKvBOTeDxkUhI
/8A+Dt1hEA4GFh3Sw467WnJgcNqY1Tj2DWMePmwR0oN0aMldEZHXT3k3hfGTojl9
phsOqgu/vfNd2kx6Shrc1ntgeO27+UBFwBT5yIOSBC+iIkIDhzJ3F+49UmGkVio7
eCBNjAlU/GbKSirjuxkvy/5JKgzhs+HKKaGZrTZCiDW19swlGEnDmhv9gnI1zF9L
L/JkHmEe8p6xAcYnWHgWWrkz19IOlwh9SbEkj7AdXY8x4r5+w/dhAOpxEZ01Cidc
FEvgkkUMTsMjd6l9Sq8bzJiXOPdGKDODlY60I+gSB+m8NwSR7vtO/wN4MLUbG4fS
PQEYfNwI+WYXE+EwzFL7BaIorSfwqCQ2J+E6bXZel+DzkRWOBLLpEIeXDTQyh2LR
lxI63lHjBAkYoRHaMvM=
=KQUk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-0498-4050-8036-5939dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//cnk38gUcq09U6p4/8M5TqXrJ0kaUuhwaIqsdzXyvZceU
tdq6jKcSTK6UZLZWgylgHcyVKgsmqMnxaDeQJOxTX1lXcPxZhNvuTi2fPbCb/HD8
ZCRfnWvin/5iriuH46Nc5x/SSmJTqD4NzZwIvZjrolpT2/NHd/tzrAju/S3c0ECQ
Y1vHhT8B3Kh8v8QJ9Mk2ZL/2FjAo2hq8rqAoWCMJCQV5QViuLk0GTapAwIDDqpv0
yal7VuBR3oXvzR/xQp8DVr+By/IrjZZ2DmYyYQuqs3CFxOmyA4h9nFslO8Rj93qW
CdoYNvPbZD2Y81LiDMneEmOGAZC/RN1ljSWDoTXnuytoXh0QBoiKInJXXzGma+NE
vNrJYMZIb7C7oTTWscASh66PoyraMYjeEmntGRpUotTJo45v4LF5A8MUfnGqX4+k
+1zoqcFfHnRkqebvFgWb3UCsBspxxSYzoGx30oAmaWxtn4oYjWER24q9yGkzcTtu
O8+ASCrDuD+D6OCu/JpfzlZ6i1lOPDg5dAhWQc3lXyMdoI8tlF4sd/5dNzIHpNR2
pQ+MQTuB8BBkSK3gIzUMqnHP/KXXvVP0/ThpZ0Oq30AMkBC17FMQrHC4PsKvAVlW
G8VhvwgFPkXyHN9ZcK1btxy903roEwhzN3AbLFWbq0ml/O9QfdtYg6KymiKf6d/S
QAFKBsrHVY0JUr4S0d+2nrRich0Dyx16OHi0IgIIZWh0anoSNLKnd0+PkJXLB8QW
0qjGe5gogEF31MCP9qLHGrY=
=AhiY
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-13f0-4ce3-ab63-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//WElJpNZ+JLQfaWgEZLgz7cIDBiZz6lv27VeHSC9VL1NL
Fw3bQ84QzC2YJkBcL0hkkji7ojrjf3omLPNOv2HxE2fELF5u+wHpsjEIjlHVz0xF
GogKVW7J48NazUsuhynSTjPHi8HO2Hv482XpCPLktkxab++LC97FM//+jhVtRNgb
Cj2WUdFBIDRp7U9yu7rxb6AYOpuJx901Slju9oKCLN8m9pYvJP4/jLOXN5Hp2+Ia
qqEtewu4NPCgxvUhovV9NVdPX1UV11ejdNF3Dn+KZwTee+7keYPhQAu5Vd0sneGB
PlTo8WJp+fWxUEs2WLJGCwnQUNcjfmoav0IYq+jPlYNrsVNnymSBHJj20weBA2ns
Zz3b1Yo9znp+lqDKnse5Rj8pNobCpHXwQNsT5eRpJ77SqOvcW1vPJOFcaGJ3TjFL
PgbbT3XjvrPtR8zHhfZGSbNQWBKEeCo4rj2Ryymq8Rkr1Px38AclX8g2FC/Jnl+1
hPzsWKSQT2lTXYWwtmaVCtzqRCTp0klEVEsWxAlPeIlMjN87ZCr4ywMKNhz5vXLI
cuaCIIAkppp4T6YCuAdoljmMRHiceVTbpVQWpMQ5JQcYXmDQu7OCa+u5spdgE/1w
LPkGld2lMVtryGAGWiXoldH6X60NqrqlKWqR/ZgMf/1D13Uy8+pmK2pdZGElmnTS
QAEe9tIBPVgY44hRZahLsJbVHGMakgeskT5kDcpUBxAdD2D+Gxbkp4fJNhbHtDi7
s6fuwQ7R/9JVmQDmYzAr0LY=
=iLXB
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-20d0-470f-a49a-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/YX65+NTAVp9BhcZYrQ5AZB6/vyNi8F0TdINcXfZGCJ1F
/zQTEEji7drPAOFoa6v9sF2mLUf6KFLdh+Uv84IH8LrF7zuVtPkBNClFjY1FUJyy
pPY0HSMeNTrZFTBIuQhDoswFDa6eOCE73r3ypGpobzQZ09AAqYIDHfOa9YDxgcPn
chnkwUWHzVMSaPj19XS2skrr+stCpS1KyWDUMHIseCKJzZzta1+0bgn9bmXsf2Lp
7ii1q2UN2JyptA84+PsoUX2ytIZF4AeRWLZYvJzY7sRPyKEru/eMyRPHCC/7NJDn
v9i6mcOJSKME5md++ZeP8WaHkCWd8KbwkZR2olcKbdJDAWuYQtbLoxLzZN/kmHod
oEPwEsjMT1i6fqio2KSmrxACT9aKkjYyWkXyrZ92ycbWXbvKXqac5e1wShjfjIfO
frOljA==
=TBGW
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-4d34-4e72-917f-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+O2rMMUN3LwdL7Yj8w46tLQUUos2ltecr0cHaaN4Suwsy
eh6O+/9z7+B2Sbdje9db+QZHdLAPAIe/9r7FeS90UNCEW1BUIQLRYmYJQ3bAzCIE
frZUYi4HKvyFRXZRXGXzI0XKtcBSjET9D2z+Z2NRvCe1JO5k26exzOz421HBPU1I
6kEud0bbPFN/PX+RbRo1pUc5NxnUFnh0D4nz4rGfvoTuGlxFmyFemdi4uLA3TSoB
Gu4h8AvqAvvoYEE+zGsGnzyo2DTs1cJvV+HWndygbKXl6IQYodP1LeD1bWNsWuZc
6QClrQqq1RjsFbXF5ofozlvQZuVc4KEuknDIf5CYW9JCAUxx0rH1InDVGLyAk+1u
7yZl8EFmVEROPsR/DKuxIMEGdr/1r3ejgr4eaUKyLMyWjU1cQ80Jbi1xHsyxY2Rl
T5w8
=kDwn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-51f8-4b09-bb5b-5939dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAwP1NDgx+7AfIgBui1Oio8MfqILI/+jU3m45+jD6Zcz+8
VcBKk1qQkH1GU9RPZlWaiu1zBMsAFPgiCPFG6xs1isAkvmu0rLryVCYYvAChKexp
kbHuBxXGWvo9NT5T9TXFE8NHLGTb8O5lb8dTRbR7UZI4IJjVWapPi/xeT7rEpekY
raiS92y/CNhqbiztC+3GUSv3p/uhax32dp5YoMrZhS7M81QF8jmRWXw2LbErq5Qh
3rQG4DXKzo0VXtZbi6dyfoQrb1VHn6i8iQ9AjcvWV+TUGZ/CrVpCs/+W+GSGxY/k
ZdgGRQde8DSFwmfkeZa2Vki6v8yqCgu9DPG9H6Ddt+tEyADHpMWTxSeaqNTTKSWo
6vFYYWkG3lSN45XQ9rn2+ZzRIWOhvge73dlgt0PQtLSEvomMyVuG9DuAP2eQOAL7
qHvpbMttu99r0pSEOz9AuJGJBpJgxx3qpLKdehmLcCysX8NHG/DBS+zuQxiXsc9s
J+TUz4mVr5yzSc4ryC2MW9vxXTpl7Jzh7TwQU74Zh4f/4iMykHKZTD8Azo6kAEEh
VSc53Nd/D45iQq0KTX86E56dksVPxlg2/SQBeftr6d6RGWwUkKWNeUJYKQzz6vtk
/2kpbVquyt3JzCujeEQWrSEcigZdDY+QhL/97/kUkR0jTyDOX9tVfAd9xs0ggj/S
QgFTfeL0wFuusLPic853dgRyisnEZrEGSujZFZoqjf20anFvq3WrO0XQknxx/usL
M935RllJU8L/rWCnkOW02F6uDw==
=60fd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-5920-4119-8241-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+OoToBnDpJAYPPzDDKtXjgOnjwGvAJ6NBBjzXnKpbQx54
U+FtXNCzx9y0lN/O/OlTlVGL4FRgWVQzHDNpWw5NLqR54qRxTIuh3M1UqYf4KFiG
0l1bRshQi1iSasdh8nengOaexobz1nC1jzpks4NaW3sNcP11lcZ1evB+/8XjMhAF
aPSGi95AQYa1nIZMzZ67ef+wtaeTtVRLH7sqf661mPjUtH2PgcJq2SdrV7S4d5bR
wnlKrXef4OctO97IkET+ypc78kNZIQdGn3cB4UaSljoSzPI2wSy8gmaPoKKpCQIj
BiIIbhVjEk0JWNzG4NXDgS+3k0F7O28TmzvPpTo8e+b53uW86wM3x3xX488rGgaA
F0B0/QsHw/C4A/SilfnQi4ERtc8CRuiLBWvzoaUh1X58bMGQAAsYc2zJM01C/Yb2
L2LeG2eBxz3rGdM921Zt6OPApIDdK9xiA1fQN3IWohZwEFzNMH+rkPCxfD90f2gf
pzqb2nW+5KSjYIKb9CzD9WV35h5Iko0RImSeXeWDXtq3X1+ds+IDMRk8TesD9tWP
w4bkURFsr1Jns46d5Q7Z3SS6mZrBgxoGxv4zz10CeLm2taL0cN99UTllkQFPTvsk
04Uzd3TsSiJoeRgG/r8udLD61g/9DFRnrN8bWhY/MXEzkxyOJwTVgJ/YvsvyKVnS
RAE89jo1z1eBQLDd+WAmTQ79NnVj0+D8Yeq//npF0fFy04euYJQZoOjPbqFsYMLX
KqI52eFTssI6M9y1XSsE3mrFWWky
=ftSU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-5d14-4884-a331-5939dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAopDpd7EawCVnmM4l155sZ2X9+oiNdu73+Edo+m4mNvti
/QvSxebaDXfyvx4Daax8lDqdG7UazZ6jg4hKw+EzFLG68nvXwrQE5d+uswEtPHAR
QeuOeIc9xCwae8ZQyAhD2y4ts1h08ASSSEqijrW+XGkW/iZ1Gdrs6P0L88KS+apD
dNneKgif//nuVmOqZU6y0CTPKwocdBgFAogQgNZi/25fblGwiIzkMNoeRbx1GJCS
51xYgGZbNXywC8IruSwFRfOpF5nL+/r/9tt0fRPRNRF+TsQErEomJ9/id5B/AUdF
DH5a0bHM67qLW6S28JvAo6SwyndCM5lJf/Wpquh/rjEII2v+qRYQBwvOOqIq3/Hz
d8KbVuCiwrOqv2tXkI+7utzGzWhLuusNJC2fXWHMFkwr7LCRCuX3H9boBcBMyC2E
fUukWyxeo2kqc2IVauTAX0+p99PAp9iNJfNV/FG2nxgBlxYYgb1MqusOdESRBYB8
cgPEandMLLNptYJjZw1VB0H9MoRM1phE4XeQo+kbhozb4738uvpZiXYY/xQSC6ej
uFWE5BaQPJIHf097rRIf9UbALzBsYPqniVY9l2zJtE16un+qdTMr2r5dlBh1VnIC
ngGIs2Zxtckytz+bJF8yw6n09mruCDZByqIFrDCm+9sDUV+TOq5Zs70SauVV/L3S
QAFbxTLlne51oAwap6nOLZXzw1fZaotRg+QPGyC8Y5jZ9vK/Q3s1lKkLC3oYeOzq
brMbjVsyh1i01k/NzTJe7C4=
=AAhA
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-6338-4558-a9ee-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf9F8STWybDmOkaz/Fabk0vKfnkAqpBSZmS/ThqlY+J1ybr
jEB4x8DTw8OMADay5yrTBpv/0JgWOqZ8p9svGwlstX+zYNZs3GZDkR2r/KPqHw4b
o951KYZ9AzBeLOwimuvriREFuSQhJx+sG6LvhnoXEIdYsJzzb7MRi4zdZvsgAyz4
I2T0TCaOjlUVaNknmMaj0gLBp3VwfZe2GGAXkbkdJSSS7KJrr2Mls0T0xSeenw2W
YkhOBrgZTV6TkBC+Fg/4fahEL2JNK4qZ6ilAobiGwz6JgxtGA3wSHRn9aHUSFwTh
bM5wUCxbdE533I6c9QZX/RpemMGNUEs7iBmst58wO9JBAbmb1V+Hwc+tRrHVdlWf
3yvOjpazZQfpahXNHCZv7bsqgtKKvDjgcgY32xh/rGJKHLADHB3qCRpP4K3mcfyB
Mcc=
=tWAq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-8278-4761-8ad2-5939dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAkFV6qEdGT5cNRNr+Ln7OQExXS2OfZaZ/3Cls1h8onk85
lVxcv67C8BM6u4YfmEMEZAetaP/BA4qVIeBgzZbEWO2+kTjd4MD0nMpAPHqjkI8j
GHCgtcSPZHrhxJVw5W2Uo/jTbElT9FXvsWqRZz/3OSJ06yNahNuFrVmxFiJ8Vw4n
AVA9Tjlp7hdkEmRNwC6XRyxAHeYxNNdMOBUWhfVE9L33nD7SIAk9es/O1Y5QXdoN
jrXiliHIv9f8PZHin4cNT2koiy+UM2a9+cEKNjxAjyoOz3R8vuF/Nn06WK7bECP2
bRhM9bfhng1kLa16JIfStn8xBD9ScCIfdNNfJ/2hBlNgq5+vbgW5dK1oYZDNbIqY
1ZDGjVnEcjJAhpGvB1Mh8OFEC+z84KbEpeB7XXgoYX+4apCQsnkUbSSEFx4+i0JT
FVB48LyCiThif2/y+GGKBBFeyOtIUUiLwDEDxxzTTYEwuuoBeBM2P4C7ZC3uxZAc
LtkFfZQpyLrUNgUtt4ds3Xz/FVxwptdO/Xf4CAXt6F5l6Q89YGZR34pQufdjucxO
2XXkekhB9vFDJBLLPUWi0OE+fQdNJZjJYScqNg2hE2daIs+Y8En9sIORX4WTAFA8
84xTKCP6scLvr54v6uhCNu5+feDFwFGOoyLpym0CT97zrF7FjVIAqf2lffjAfeDS
QwHQWMc4wr8+AILtq6wJtk6b6NqCMxuoIL/CpXvMNZiR8xTWEBtb2p/ORnAgtPju
DIjKuW+LJ6qJQgvy0PMeg/zKleU=
=nNwx
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-aa38-4de8-98c4-5939dbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/Tu6ADmdzk4o6rfi4s0F5DZC7f8jL61i7rRGD5Pr57jpD
yqEh2NE36W5Ew+x1YKz/gpXTD4+47L8v4Qvcmd/5RqgX2wa9dswU5cEIg2WqwGd8
EGXRfDSOTDtPQwh1woUW1DzMZdFTIdkJt2e1U7rvomDmNbGgffhSvupDfqsjWylp
hPi0bYrGGqx47i8wbLIbgnQzWLUIhHJrecYG9gJsoqd+MiGmBHiZVOdLBmI8ANKk
TLuwp7bxhN3AGD2vkaYU8WDYWlqHR3R4zaxPwJCwGyI/mdT5udmNmCNZ6Q9Y/Mpm
Ftpsb6Tko0/E4jjWO4I/pBfBNKExmYpUjKFd0/07AdJDAcWehjPS9Yb9hqNxpo2P
cXcfkuW/hk/G6VG2Hu12Jt0icE8+0cywpFqZefRL9TTWT61Q/wK5aTy6Mr+XxcPq
33v+QA==
=G9LO
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-b0c4-4f2a-bade-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/W9JGsQSrg6zCfh8dq4f71qXaPivJYzVxyNWj/j89D+E0
6ZOOsMcKdp6F3gacfBBXwvO3E5Rzi24cRz9mml4pDqDumYExEhXejo/PQGiN0bY5
GgaeCnlKrAhgid7M2i1qYCfJDu7agjhtoJ8AaTniOqGbiRu/rhNiDZodBUfN6hXr
BFYv3dwzb7ob0te8iiwY1Erhcb89bimtrNyBGn2dssSCSOH+JVZ1ztl92qdcsSP/
y1S8G5VNiyP0AknbFnau6a95KsuCoqE/Wt74sUEPgOQ+65xkhT76upUbCnU8lPcU
hKjIVa+8qW2fyIW8Wnt2NVDHRyxcDh/YoXTKr0BzGNJAAc9BpVo+9gkbEa0hsHXZ
G49QlVABUwBdN1Rd5BrsWVedb+7MMY07xl4a1JPdzQkTClzYFhB/s/7UVCmuoCJ8
uQ==
=2TRU
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-b424-46ac-8111-5939dbeb2d5e',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMAxkA6B9Z4y2kAQ/9EvAT3XKawn+Xt4aeGto3LINrDkX2tqJhPh0HfKhM36Hr
UhXUBvVLmfYF2Q/mELYS3owIb2Tx1kKMuZsEURvv3pq/vNG+Sry5W1HL9bGlFCis
GzWGeirCoQ+L1BKCItrZ+3fM7EOdWUyYBcGJjAQ0MK8ZFuwA3Y+mvMqfTx0YGqM+
34Syq3AQ8WwTzHSRBa1vTVEYjJ497dnEv/MGTZujJuKDefMBXmTuWtr0jip/OoRq
PbGNoEIXwVaPHj7AQAuNpn9p/hEYDGiEMRh/CoAanl/y+Y1MVBuVniyjNvJAIFga
X7MHSOWhRa3LWKYitAfrxDJpl9vBLg3pEKwNIbt4ttpVxl/PLxWuNUtrAYN0NvNy
xS+OJcMa72AxYg/wSEyMltSgqcSrak+RM1HtFoay2qz2YFxNS6VHnOX0pxMNyaV5
V8g6fLFIejkdO7gA78WQd8POzzLsTMOkI/93+2EpDV7XSa8lnrpkA7kRNQ8CAceP
ej3WPQylMDHUCt17KDaKvB69HSguvb23oJr7Qle+vv39pDMYcDRSbbWLYOczjXJ7
lc+2OeMPfPaZIvqIm9TYVm6s+tskhLcm9FrhAvs93GQcSCuBfJNyFAXqdRbUiv5b
RqJZ3/DcjuwHgsqwefoyDRfntVa6LC5NlMn2MbZMi0ksqkSBilEzpBW2MdRipjjS
QwHASnydv2lzzucKwQmhUOdxDcAK15bYoUW/BBAQxW6bSu2ZSFiYmkzBDX+TKxny
KDNTOaspOQQQxbKB2XDVmXYijtg=
=FMh4
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-b724-406f-bf4d-5939dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+Pb72EF5j/5VleS3xwmKpAKV3TNTyT0inKsmHFYYPPvhg
NUjgPG5Rl38v7Cc6bxuIsJnsWHq1G5FmpijVCq2lZkrALrbE0VGcOv17985/IVKY
jY+lLqTmJMNpUZVIIF+erHzgqIdjPhpeR+LCx+cyzBbs8RCJWF4tHNv/wahXFO/t
6utA2W9uwAV9rvTVcTGRh4PK4UCQZGxUi+r29S02otrBy23EvdPQdIvpGeKvl9dP
7Vavf7qN6dWZAcIncbO+m11z8+sum4qT1UDe/NrzPpt1p5bLB/Cx1AhH+9hYV/rn
+hwMsCHbjXiXriZOntRu2BaN8OZBtNCpR1L5VLOTqAEvpvg0PahNXw9CAc8tlg1P
7bPkwmFGOBQph2W8xJkZXBpimtcJ3j1vMU6LDSIZpxaCOxayIhG2zKGTailZ0qje
eRW0peaAz8rcuDVqJ87++tJlGpqepNcK4iRm9hQIbpuDosJragBHQD7unbab6OG9
9o7PZoaP0NPOwfiFpUswInXWwyp5WyZONQD5nGxb5qbbZvXdsv/QlNliKgfhomtl
nwwKwTtMEwObNZKIwIj47r4OUXGmQ/CXU9+nSyIF1EVln2SdNSJyCIEzMY4uKZpu
s2a3Je/b/jYUFJEjekEbhAZECf+1Vvrf/hWzoynnZ+yk3vQCaX81ppRUOSvdFovS
QQFB0Ht4q1A14p/2BCQmKqmifEUthEN7Xwtp14tdmi+C/O32Rxa1AnN0nwYpbFDw
BcCSkB6Qszr4iJEGCx1Xggk5
=HHT+
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-bd60-4ff7-b969-5939dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAk8hX120DHJg30J4+2shyszCG5WOmGucRszqaltUvtRF6
zcteG55H4ojQAcqYshJmUveWRpsPK5c/AmolZkbw+O3l2IIHt5JB4tQFhRDzninC
6MbsV3mCOZ2qcLKUy7LysoRnYpIJvB5y7ZE4zynTKpBlQxwR+3xQ/Xe9A6NJ/XaA
YlQElQe/lJCCwB/xfC8GvpFZGulyrqUEgZ9HijVo13eCxBB1ego7ZOy1YaNR1t6B
tEiHVuVsNP+Dbz50onPi9jQ671tFSHrf2IvhUdsR4J1UenUfEtNzzUE3+vIlkDEN
PUdOgJgNRfNhuQzlhy2IJFuVQA8KA0XPmOwpck9M13RpYvKudWZ4aWut+E1AjaqI
dCogTloeZTdjCcvRMgb0O0vI1fIJVTlUKlM3WhKykzJYqZLladU1IiGblYBfWTue
gI8+236gOlAX8G1fU+M4P++r4cK88poF8HHqsSCNyIbAelmnqkaJy/SO4fwKiY6k
gY/+4x79jZoE2jF2M3p1b8Yi+ZVoYgcKsPc5LnUnmZjq4tBV9SGMTsnGy52odwC4
CQWF0ybrW/YJ78O44SxqCRnfjA6aTr+FRvBcWTx4SbKrF8eGPVPL1h8xnaMPz7Gp
c3eYlb0SAScTQb0q2GsanRmh1ulbJ9hM70hSUcG2Qod4eHfTxkJ4lPKdrbDT3bPS
QAHF563BNoiIFbwDwR2YM2WuEWlNsOM0/vmS6FF30YbsQE2kdJRb28AslMr7CdWr
WXW04fop9gmE0J6KxN8J4Yg=
=Qcqr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-d75c-4483-8a8a-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+Izsjf+r3VqqrYUYflVxq5fha39W+jBmoWdlZkCfxnBfp
kHOd2SF0X3Jatp2vfZlOYHwpL+kFhWJEdMEO4TTy6GF546vDHnP5NAXTcToJ5g5Q
NeyIIV1cRBhUaPdIxDz1fmFtpemnW18hNq7c2ZFMcqiTsg8u4w0F+wJaLZZ72XvZ
pB/3pzCufU+ghyi1FtVObANKrGXkjZQfhYre7zG6OF02xOyvd0OGD8OOfkIUS9Ts
vutvEplf68fAn06yO0wHyhau33G+t9xyXKgYUgS0qClJvhjn4khDfdhovOqdzAcs
1dhkK4jtf6ntVNje7ewacvl/IdjwyPxOzgZzXFW/2dJAAZLQgN0daf4PDOeuaUuB
+Msq/0HHNKOR1iLAKwMYb1vezxOII4MKA+J1h/fpsCiFavILijRRfLZSC119Rlf4
FQ==
=a1/u
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-d80c-4b2e-9a28-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NYkImRv4PODqGprcHyg/ZrD4+Qhhb9aZLGvn+tCL4NfI
LEfa7nSZnCv4OsTBbytzz5NjmZgXu/nzgRaiKjNUm68nOZVxShH+A2R6WHEwb4Iu
k1tGGfdNH+bQGaATuSFVlJct5DAx2MBYPnWGUw5PV8tjBQ0ysQrl4E16cImean5E
j5Zf1BzCHvb6tZo57anIWNrP4N/dFKLZBSsn6/OMvoH1kNWwF61yQ34if0vIudAt
EZf3/AFKBe7OWD8d7Ympc8HT1KWu6QiEh3lmj+6k3wymjixnuh2gY3SUSAVCR0K7
j5lZaW9Pg+os+UBI7TzDEPNLO6PuyAgVC/7VBoSHTRvGD41iTxT/ycm2pcynu+jr
NtgpMofmrACjdUjQEIrVUQQyheO46osV4HywjWj7sCoqZJYFemeigy4lK+dhHpJx
KmSt32Y1uf6NOj/qeWlT+b9epH2PVa31Afsp2h1SUF4huP4MDqHdIXxQ6y+VugvA
8ZmX6p0Zz2q92kJOtmqFsTYmf25GrPCIfsKYywE6PssmiyxdDBzOHqLWyUZ6iJSS
vFhxwNl3hqMIs1ztdmISz1oKMycDfSHLWt+5IDEXF6QmhQ4y+t442NFkqfoftw58
qDA5Cyd8qHQJz57h8w2Ey7DMZkqyLcmNa/K5P7/DPbLii8zu2OL7yABjQERVK3LS
QwGjChPZjEDgkuERnOvjbQFZe3490F9HgSnyVRlCabEyjEXc2NrGbcj2lw0N6bae
JbpHfNiHdYUA19B+FH7+Iu0ekGg=
=QFbl
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-d820-4044-8a91-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+KN0PP5EOYHlZE2AhdWHWcYunKR14CJUChyR1XGhFsidA
acCmUMo5LITzcAOiRMJ6r0xHPwx1JPhHg1UtsDNdSvcthpc4UF1qYLKvbjsu+dCr
4wAlatx1/WcLK9/FMXb/jVl50EY2QNc5pSbpnPlbZIltwvfHoW0/XFAjrewfNSkM
WXG5BBwV++s2Ub0K0+N0Q9QkBM5jdPWk5OU9JaWUZ38N3ZQOabF1z4/fLC/RdeUm
dobj8Lg8zC7GZyv+puHbZ6eURF5r1PdxjLx0MB4vy81G3bpDGI17If9k8aIDtI6W
/s0edj3tUtOpJEyHXd3TfwCS0gilA1ujUru2XzhEPJpthE5sqHngS87yafjloxVI
W2tL/9pT9bOWg+Fe6YNTyx+mk4Aod0V7aFJ76s1xushSNEs9WHSNDISEK+b/RjrR
PR6i4IUK04q8vEhYaWO2s66DjYguv3s6+0nGFfdP5A+5H60oB5M58Ir0KTOAdMCZ
1bQDjZfNC/Y4YHo2mulO477KiJ2DrcAL7dyjRfy08v1PJr3NlOGz9O1iaNxnyXtO
D6Nc2WuBf98RuxsUeUzA4FCs0dwEZhh/EFBixy3sxvh0dynIHNe6jzTlYH6C1kZb
X7R/l0m6CIst8aIuhQxy7oO+dkJ/MCdzFmBcPfoja+oxTrBUFcShHojQlacb0d/S
QgEBbPxTyKqbaKum2x9ULji5POE27nTG5Q2OkxqrM6rY0nMtULDtZLuuBpEATLjU
j7frJa4azWmtHrWg1XqQsvbkRg==
=5Blu
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-e3a8-4f19-b29f-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+PowyWl9HowywevviA9NsaOWipw6L+lNj5DFWX8N3kz+v
WiQdSZvAtAipyjcyzAdzyCFbCqTW3I4W/j0ETkG2M6/FO85o4lfN2ZpdHdfmOGAV
pSZFSKrpbiHntjyfueq5WjRTdZReANpGTU7szwYOs2YhEB7AusFdVM67VbX4qecK
bpgHG0A7sTzvt0SZXnv0XCIXT750XmFcigRJCOEL5AdHNrEFpwGO2ELhDNVlgyfo
3zUin8656JldQCSnXzaSRMGLLNgn2eLpnldYQKcd4+G8VkEL5sb52CSpti9uJaEU
7+I7ccmLZRvSD7WOJ4hrjWqyyqlwPSIsLMScj1v3yIxpaYzpynTIQidmb4ZbR/rZ
O6/cJZIAU4D/6p4IBBJGKfP7iRaDrP3DNEeUmriuptEg810liDvXdhiFZ1fVxPh5
ROVaGjCFh5BSd66W4J2Nif2vaktekA1IWTG60S5k+kdN7+Ac88ratm2NxoRJsnY9
AxVstxVdX48dh5w5al+HUkqw2TfJ24g7NWEvUV+DIY3iyVPXBjL3YBgYYGLInKMx
ntyj6Y1TsGPdp/EicuQcVdM12RG/af8Dst6OmUvxwv1KlcgaXrPJjVqsePIcMUkc
wH7jkdlRXTavXRakb0+cBLE9qq2pMQ3sW6iKP2vtuLzc0GCSu0ZplrCF89FY9XPS
QAEMkgnXSKS4/MvvWHKtuJmFiloL95qhLZKsmJ3w9xhpx3oEMIc/IA8t0zeUSQ5u
jNI3RBjh6XuR4oTTjQceTw4=
=t6Y8
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-e820-475e-8d1d-5939dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ//bPRlcbJDZKDfM0knpO3CvgWT/3UwUkmb1194CBrAKDit
XXJaAx0SeHSM1G003N6M5ggaQpOx7f6NB6B1PNqG5nmij34mUnwlXEzjjog3SpDO
8Wl7uVYHpZIuOotTVsnhIxL5YhkOsNK89YzsnYjgUf9i5SoZtu8cQDZXflLPI0GB
vZqOU/waXulzdRlQYkNlOgYYnNTU07skVwSBtz1yemCMRC5c3Qa79uIM74mVSEJo
mROtGzBVtled5KGBZhDYfNLyApaSQ6eicXIqHXeNqB8sXnEvCyAKuCnBCwAp3Fhu
iI0f9rcpAmpGrSfbwPrPO+V4VRSa/FRjTBWTPtyZ6AQZYhyxH4DFvvXSNYIcoGFv
4yoYpPPpIQrTWURktnAaMIsPL+fQ37XewIi5mpvEy2aSw9kOAt4cQuY483oSvE3z
wyiU8CAuQLsZTunNfl4e0EUDu1pUs8TzmwgpKfnGqmiZS6qNCdl6cOu71SOyQvhj
7Nz/hQ2YVHglOxlbZNqd0cswmTGTMUFhdNXOeI3CClWG50yWpl4Lcy9/YtnI9uMU
XucPk4YKXE10aqDO7lCDJCE+JL54WM4oq+5chhRDyKogyimQEGcw6mOcyyq8qvMS
3uYf7sgmnIY3hPkaxCpasCL2LaS2CWiXUi3WOa/s4VV5XvWfnP0eXhS116bdEkTS
QAGAB3B8GiUhTIHnTazfhPmAsTf8QYkgURDB9wAyvAx0P8oXRyKT8On3I42JfvKL
8f3WtwpEmO23tIwzw0a2bL0=
=skAo
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-ebe8-423d-8a66-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+OOwLpI3wlaouFJ8dGQure8pRkKkAVYp1oEgaUFRRp9oO
qZhkfIpSXV1oS48zSYxNXtnBfa2EKEHjXttmhtGCEsPHA4B9Bm2MLsOT9A0io+lw
mlEZgrBVpGx8wd4QdD8DJRknVoe6wlYjx3RTVKOOo5FH9iP4tQpSkFtLiMw4Ve1w
0wkXIf3JQTK4IfByb+pR9F6enMREaX6Jo7C40pVXNqMIhRQMUTi+JPZwB+chvznc
JT4YnOUrO46Ti1N7t8+4rEGRcQHqiSpCRm8W+cPdJNPD9WqGmE5eGu6adNL4cXlY
LocIX8+UvTyK2Wz5Wefpg5zy3ejj/jPJuWffNuO1PnT/5jdp6q/JCwa49ZUB1mJs
mBCRfXYB7EDkZT01jqTQ6SIUWnC/i6UUPVpM5gNqPgrUHFsvpfgDoG8gp+pXIIIJ
PSyS+g5r1r710QTPwMbKoYrUXO/blRblA0hT0NGQ9QKFYDszm7jqlCp8AmCnajZp
FnIuLVXodJGMzQQzmEeCR2JQlZBG3lcPmCvzkcJKayQCl60IWGvxlZQyZvtTfV+n
Q58E4fhK7Pw2QtRyNw8L+fnRusMHaC7hmS2watA3Fw9l++x5+WeC8NTtCIIUOvGL
uesNDa5iHjuAGxjRmVWrD90GXn5a48Zql3FK7OCawEs2L9SWcGF1k/9SGzKc3nvS
QQGU72hEdxUqRI5TIo4od1Jb4madOeu2KXxq4eJ5GOosqaRo7mpgnbMo+6pfkqf5
R98w7Cw4165LMvZtqT60/nOE
=Pued
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75363-fe64-4606-bad2-5939dbeb2d5e',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'resource_id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4e/DeCIHsAzAQ/9E+wEqMVTdD6BCL8PuU5CNoPo8oesZAwIgYHdBv4fxHg1
3XV2EiN0vO33xU4UJOAKBpfZ8w7dhw3u/oJab7L6b9E1hL4pQ48gt9yuogafKNTm
rkM7iiZZ6FqGqF9kbHb/deYuAnn0Ro2ZX9ReOPduOcPoKQeuDqVI0dmQgZTQCt49
8tVs10xxt3ChRod2/6qSUK+8YCLIehUY+hI76cjTzCQmdGniSkWz+ikYfJKzyScf
rkzAEUSohcPia+OUTLmfh94k4LBCNvCO93l84BXEzX3IA9QRxFqnF8pXevE8pqPt
aBU08SQwY5PpTUuR7KXT5+LmBpI+OM1gC7HC8JJmG6nCsR2X2m7cAwuHkUxiCJAO
mOmqYm3cm0KE983W+idBOc3B8xSPg4jgmN8jxqdiw63LMX6HJDL2RhKqrZxT4yUo
QMXGjHu9570X4xh9VSKh22mWn8mkgsAi+Sq/0V9vcpntjnSil7xVkzczoHVE8mu1
2nGrQiKsjFWAjRFxjxSfycqSAOWG/2RN0GdEO/bm4JgZbBjKV8xsAQbEXZkEW8xU
WxyWzT4a/wyzE+YeRLUFjdu4neSTcSl6aua7Xmv2UiXHdJSlrcCaXj3g2y4FXyW3
m5+MCfcqqIm49hWVd3WRHLfuVkI45OWaU8yjNNBdcY0fKedgyWYNxY7tsdaEMQXS
QQH5T/PsbNOBvMHsOtwZIgQ7Vo8CuxRKvcRgBHIfRv/DoUcbRLAiWdPk/ES1xji9
h7G08fsZJuBAs8VC6zcnOZGr
=JE3G
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-06ac-44e8-94df-5939dbeb2d5e',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/cqj3aByMwRMeRhC8gWyW1t+G6B9XPWb5zTEoqOkXxqGh
U2UNGKdEDa4N4Wpg88PM86i84+u2uLAfyQl43pZTI7PYNhOCKPoWD0jB2n14lEie
Cey+AvDRZ7ZUQ/Waai3DBH9RFHJlanRR6bpaHQA5cqjZw2pythpOcPcyy/6LmlDq
ANSv9ibARx3ix2has/f7pEV4G+OnJKqdiY/fUuiv8uSZXtH5vsBQJqhjtxUjh1E9
MYpO5EJd+FABktEmRaSqB12W56vVxTot79Zj0NzXzUBY1OHhtvWi4EyHML9FcvIH
nOuuGC1U8MHr8OmeymtIw0PVOuJsSTP+RcU6lUhZItJDAep1ZeAvCNNBetB5xQKG
2HMocLStFBDD3Xicp9igPydJxRXIaw48AxrocfAb/bFwxFHONadbyexqrKet9/ZM
1+CYkg==
=1h4G
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-0f34-4cec-b6a6-5939dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ/+O4oBz7tcjkaInbj7HB/sDJN/KCEderH5rzcAFHmmoAuY
LQwFyBBRLc/9TiE1Jx3kcOHtoI4EGdXbTOiqlbin5NPCgTnSadPfZ+s9sSUlQV/0
LUAJM0g2X2X2lSxwKhuKfB6rDjdTlVRudhc07C6774Mut29F0V+EyBaJaVj1HbaG
aRDqQH3mshxa5U6S+KRPJAklk8FTO9GYJRnV6+Z/OJa2CT1p8/tXAWJZN6UEwIB9
q3OBjasg+ObFX+9suKCa6rVWVemiVmWNJCCbWnSdTgI9Ce5F+CDLSaLNvDKePKUX
AnPKDJto13/QhcEuqCRVTKScpxvJE8PRchqEL1lz+rkaPyj13K9UFKGWCFhMaPYF
JdhHd3TXL/fUzQcZylnhq229butHnReKpzYhipAWmDQA3rsIb1t2WOQ0OOYqEv1W
RZv++y5i8L/Ky36b+5iPa/0DVPvN8opNHSyPpldr3GO8SmkLaXBthKWO/pMELS/3
I1lNfYYosVtieOv0cVknEbpFSv/EkDbge745i8ixs8RW3GDQO1KWDmTjn1IT9Iik
73UPmRozJvyAYsWGC+ZGiB5qVAVrmlekPMTZ5/zCD0JLVoiOU1dloiPHL79kCc2k
lrqTyD5kWz5A9IeKpWa50v5TobddBdNU3IvyxSnnFaaT6J3LIs3TK5jzqTlQfJjS
QQEZAURXsJwOsCd4E7NGGvO6NXTYRgKfIJQSuLDjKlR5D3mDEq/cStezCpt8r4D4
0lE8foXlRnN74WJ2NbnQBb6n
=cLqS
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-1db8-41d2-ae19-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAskPERsJNHkryMQQW7q//lLflP3vKjSnBQm65i9Q5Mr82
0u6Tr+2wPe+ddFYLn3DTla9xuOAUd/IAI+lK/60rPxKyZXi7yWST4htRDi0aIbwD
WLL5lrKeBi44VueCaB+t5wk+eVr6J6yoFI35BDX3GMuunKrnjjLPR3iY7ydK8BIg
OIUZKzEHU+YCAlzYW1aTvnQWDF+BsOd7YK5jZRq6Du/cpIHBXR2MOKdHCek9M206
v39Qqynt1k4VYqdvVz4IvlCMMbC9lA2VvI8foQ/WNvLVjNI12f9VD8QoNF/vm6OQ
80fMUUuD79qz8QHf7q72a36jGPCQy2Hijv9Ri0cb39JBAYQS6ELl64yLCdqtwHrj
HCqjrThv2xqa3gTmP9F3xxzU/sp1RjtFve8ZqC3hGBbSiYSoXpiOU2fbJZ8p/B15
MYI=
=xLar
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-2380-48ff-9671-5939dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//SLlbDQyOyyg9iB8KImV2UaXGYPYJqEf4WzgnQSOcYW+b
7WpLNciBLtFGXJpvokDXrG34gxSTj0d38F4JDTkzY03XzgCEqh5TgriUJMed1KHo
sIv3+qU9riqHd7VUMGAKrGw/bpIw4Ldtax6aF4ANym/n/ZzjEf8oLG6jQPWuNrT4
mTuDr92LKoF2+p57wHXY0XjdsddTrsEm4e+3J9C8ZnTqop8gH+pEtBgg/AtDO9NM
13gitQ4ulmsgsoiJer2SLi5eFtOkZ28cReJkUxVV7Z07Fgj7cH/wfDYO/6DolPQe
yNSKVsxcZraGN4TKEVexf1KP5TlAqRNWYo7/iPnyPdgg5rZqbqmZpsssq3FFjpQl
KZY1oWExYQMstLbgvEw7uA0tqCCCZDI0UjIqp1M3hXPC89fXD+oeLjM0eqWNsRvX
+TCwTq3ARwILxikYiCMri4QDzQiHjJ/K6Sp3/XjyWoNADUN0aOGllZGxFRSHqHcU
XuiIkWND0nroqS57N+3XhEggdJjhjIop5M5jDbQ+iFlR7mLerOaFzwWKqqA24OKS
wWSVM2UmWLnuDzLSrNvN6BrpoJk2WTU51KvCWvNy+fTglCBLr2u1x+eM6unthEKJ
yi5HXk36W6b79iNd4oTtjjVgjaQZFJZCpbPoVgl4yH/kKnbiAhaeqgqhrfxbEsnS
QwGYJ2h7wEaLCNBJTZ25iH23qsjDuo5pnRnYqz7ygvfn1TMpaNPyA0JzlF4n9Piu
rJoui2hMUh1/ooi+m7aQwB/SGzE=
=BTcX
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-38e4-4f7b-bfe7-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgARAAqFNQ19GrzZaPLKFHYI/vVjFh6cn/bHacgBQhrBtcaiEt
0XD6Yl+vTx3mp1LZfxf0tPN5gDr7dv/fbOFsIJZwdxu+rvpY4UFghhb72mn7VxFU
Zc18ZfygW2taIc3BJqTKYcUVGH60bk1DQuzpYHkoYJGUrrUIDC/Xa8eMMl0GQy2U
P/b9dVaCCIwGnAScpn3kxOVK3YYYVG3JfejOUxfjIfniuUKL9fHn14fJtSY3Y0i0
udrkmNvkZejArlwY8Dpvyu/MUSaSe8hZ+e7v98R5zNZlIF8rsG4OKLfWC7JMlRaK
QzEc0BxDOzljHqDcXnAPJWlD3og3a8O/twvaHexquMdjGKevx5iGRDiQWo9qNjVU
+uw4qa7qNu2hsgT0n8mV+vgN3apzjjmhgVrUQiWe0AZ50ay/u1q8LMXePoQ4TDLp
Afsjsg86p1hL5J4t9Ju4iW6LnfabUdv4jrs/bPbJSARHyu+uikpMMO1i99DZgOVA
I/Z6E+4dpZW+Mg//3XSqYfnb8/l1JxffPhC+YjxDzoC+fBMTKDKumwhFF21b7L7u
dSSF5jafTWk/156vvnco5TQKrpEua8z8Ur8m6WsGJb8I+PUwywmKRebRcuMj7WXk
XVGLz7hBTrkaqcOhGmImNJ8w1YVWVacYz5C4qTq7Rd1HRxgOODyNnbncfHTabNrS
QQFroafKbrbFZ5mC30rvbFwGohuREUWEEF+Cd9yslGdNikfxeX11PCGjf5Cod1Pt
efD6QZ832AqN3nYF1BVdhqbc
=8L3S
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-7030-4b3e-badd-5939dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+AQ//VFEztXbNfVQHgcT1yYWPvLWEO09gz2vR4p55ZLLtjTd8
qwDxKYuSHMt632QDpD5/KIiyWQ3tQ8DoVWEtK4DrCI3hjNRGbtr9VH8omVSb/eV1
8HBsiggEWIwaxb88BPG3txCIXJI1PbYcPrOA/ozY+vpAmr1QluHwRupwMBTtXYRP
7+zAfbuqjzp9BQzNeVa/xvsFDPvNnrfdaZ8fhC9YPkzNKMdJAuszVVsUiyVligia
DbASTSqEj1GNZsVpTWeXaI9JjTnh8zAhL4OMfxWiVuxhQB/hIlm2vZYg9OO+Iq8d
Cf8xcKxTqT4yNIZS6kRRi6IB+YzGTv1nPW50x0rrsTowYa7F93O8CClj6o3xn/6w
/d8bc5PYTYFWUeabKTNkQSWbNlvrR0jwAIRtrXobJc1ZY0/8Z3k/H/AoHu5ni34p
GdrxScEoL3xLnbf+veK5GIkuHgzpeJEPwZEHIsRZZloZEnmPrDhI+oeiu7beeC+3
AHC2h+nn9NDQZ3Ql7hL1BkNdJNzBlyQ12ZjzWf4iFwS4GFsvTzLUYuqBhHdMaljP
skpubXDZiauQFBa5EQ1TkS6KYn6hIWe1C48MBlbbwGBQEGeQmiwEAlDvb7g6R8Wr
CeLhGFH+mbMtNNZ8KYCsMm3KryzsxzbXD7uqVen1hZdtbyBXTM19qD/vo4eXkrrS
QwEoig5nF35UmcWsz9HKNT/T/Y4D6pDCCyy+nKgqinApKZmqC93GGxshvuzEO+DN
vYVYrFMLzdCJUIMAEIa6XchMqp0=
=8+Ft
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-7c60-4e7e-bf21-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQgAoyNn3jpdl7qNDZH9na+b5Ry8Bzp90LCmmrSc+VPbKnQW
FY7CZ80njZDmUk2fZPG1i60z7sMZ0g5R7Q5oe3AWmVkIEGM38NK27SYpmUXe0cQJ
to08I1OlJOCcIjJVtTPxE9/DzxAhXfHThpWqOLrLwhL1LMny0lFnEvFm7rUFdOjj
Cd4tg6BuSOVl+0Ugd+D+kMIQ9iU09xfGsycbzigfix+k+OgrJO4w9aBrLmwcpcJo
1VqcuRkYXhaDP3YnplSs4MQ+XgfzX89GH35zZ23hMUGSC8vWPidztKvMZltVlQ5M
Zrkm7NOCnCFh6l6yJVeX0wSPsrN6J1eJ25F+vbqSDdJDAQea1dUesxjEtvGXjrmY
fSd2uX9Xn/GEaYZSewoAbDSrtuuEpfxRh4YbUH2jNj//FmTJz4sZqVywZYhiegNX
oZHHYw==
=VArd
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-848c-4758-9501-5939dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/8CB2xULLExLi6Ju5ggYTuWoTxrT73CmGyxtrkqKzB9c49
cy4EWxikobRxMGGDmqbKF7fSUFd7LILNKYRnP98Y3UwKt7UVQ7Ek+FcWE3UuZupL
CTA2ls5NgUWvEsn8ui5OZY8dfNeCg04EpGIzp1Pfy6adMp6bJY4BI1cB1s/GSVu9
tLqdJVGxaopr91EA/ailmMeEqBPHRaGetFRw+QSwajAAftVrxxfzOeXEHFBIpkhU
Vx5RcGlJSmP7yA0hgP8pzv9x51oMers5TlGQIVnxUXurzU+Mz6Bb8zhif4I6tXh2
0bhDEQXnVju7bxg0itwNfPESSe9JA4fd4RdXYYAVTdsC7ZTSdXwzf3gE5yO3Qe42
O9kgE9Cu4gewVG9NywkKai74AZpE/qNNvPbzmrg0wNBX+Af8IH1BasxwjDXsckQo
6dnHzxP/wFrH19/fCKMQYeMJMseSeaF8wKzKudbnP9vha02+ZCiHrxjgvUBbKSn3
mYWm02DDP68zhuQteu3jB1KdpZ7dMTW4KCI2T4Y1ZTQ0yFYbVHXFcyGK7sBf6uNa
alkCB43qxkDwqaj4oU7OjMFpk4Z3Yj1Bdw//HPGIbYNkos0nLCwLXjsyJuVb/ADe
bucXaIR87vb2K7lHHArc9UmnhwJYgZjsSELr0BGN/s4k7JNXP1izVIde38f5iiLS
QwEHHcfyHfPx1T/s8UXas3Fo83URflRGEEMO8n9Zzvx5icyH2mPFVoBlRxRY0uZB
Zev4r35w4L2wcuX9q8FxKN6u1cw=
=OkMC
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-9998-494f-8a51-5939dbeb2d5e',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA1P90Qk1JHA+ARAAlu5TcGytn/jMiyyW++VY6ms3jgS2BwlF0vUVqd/HAvzC
gFQsoNhKcEAHKUsSY+yHAaT1AqRGjBDmxN08oqkiRJx2lW0NIyQiVH+4PcqaLMdy
CP9301Cg2EI1ndtf5pb6zpBZ13vtfuxqHHNZ/vrg4/pql5u41zw+Qh1S5pFqidU2
kQNnxnCY6Fr/zKemqzmugfC9Jz1aHzRTZH55pfn82aOhMIRT6mvB3PAj4DSO+kgf
xvobftBLL74q2usNmFCj/MxzmpcJAtVeo+Njxh/93wtas4EwpkdypCrgf1JRU9U9
2ERX0XjPVAssohRcB6VI2cGXRZG03mY8Fr4jfgDD2NYwneMAWY0Jd3KUHwKWClnm
nBZywrhKJNEDJBvtkB+mVy1vurVCWi3JD0+/0mlqdwv6tJXeIDwRyEnDlej3vmZt
/y8/W5js/r16iNfuGX3RDHV1fH18y+mIkmwxx4Q+bcMMEUUa7BFSyn9SnVKwi53W
5Som6Ls6B+0hbeSlHwwOUsm/hXtcB7HZ4Lo8rVRYAPerOx3Dy4WVIjFC/OxkEBgo
7lGYkYZFL4UKZ4qBSQAoEUuBR3B1gwvrTy4moMX2csKf9W6TnenQrjal6PCLI84Q
25yCCZbdWMecYq29haUdVT8/TwOb7P5E1RCa7WRq7Z8gXDPoQKDEwb7uRC7dRIbS
QQEa8jycObgqTAHzaHx7RzpnahtRu7KGUH2sBKxBk0DodJVuG78lpJBjBL9ulUFm
yXf6rRYx97Okc4t7muzbLdwE
=3PPq
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-99d0-461d-9185-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ//YoWQ2nFgarIQat8YNnvL7hB/owLutoDxCY93ijxIvQzA
tUE3EAa/FJnShO1o/4GBbf2wZdGYQRuyfPQJ79qyqcS+gxlPXoMew+3uOyUfMogg
0pMh2roPD05K1zJyfprm0VuPsxOip6bFm3XS1wZG1azOQkOXXrsT7gINYPHHnYSn
dbQhqs8RURUEXXtKeyO7zNAMqj2IWtsW2e1sM+Spg2SkP1apVB/jUqd3a1Yaw2yM
P2dHaA56yvjZ6tK8KzMhT1Y4LD+UPwOKBYq2Wu6j/IUm586DnqJMGm9w/rkR9+oC
YasQz4VDxrt/v3hYXTwB6o+dvP163WjrVDvtoi90YH782fKZ23iFRKSXz0n4YT71
i0WI9ouxmbHgTk+eeQnzymKFI4BmiVIXW7bzoDiWMUGcfAJPkYOEzAShWxzcYvZc
0cug7XE64ggqrn7mK5RcFTUb/31QJCCLnxEUaZyrhuDbZ+EOOkhH/H/+24OQMzxe
bWhePfcgi+8swo7MQmhMj8tD+qqWwNleoFXsdedWAJQ9GtMHPLoASnQ6ccrjth3X
EgwavPvCuyijRIh89cMQWvdFUmMGY+MRuDeVVfOmOcl3L/Xff3D9K5Jd0x1IvPEY
7qd2vHxTSgeNa9jTQ6eu85RpDAKrOy15JElPZCH/OOfjFUMiUmQ+gBrmMrl5UiXS
QwGQzvOJ0rCFrg58Mw95uToRWZeXhb4GPMRDHsmaV3r8H+1jvo1K+68GXIYqWrS3
64fBEfeQ+d10Fs+yxH/fMphTpn8=
=L0Ck
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-9a8c-46e2-81d5-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/9H7gTau5U8KQJrfqBsqUZ3KNe5/2CSFH8kCbsAIOEeHS+
bV4fjKQjnmxWjVZUZlN9JpbQx36gxiqhlm/i/eH+plaWeL6j36Jhyz26PfoKp0dN
assIdr3SRoGTHThHUflYulCkJQlJvEXF055mWvpNijAx0JLULNrfuiIjDtDalqZT
PGfca7VCaNrNe2+24zglF3G6Xmtasl73apJVFICMEN9TjOKMuS9qABbIUA9Srszn
ZcnFEITsI50UQjVFDTVXso6Vhnjqeua1bYPBa2LW/h7E7+jp+K2eTZ+xLIm26o4D
+WQJrE4qtbfrHUlifuCVu7HbooyirK0z8e1plg//tw6i7z3tqNF25ceI5RuWZ3Gn
HtR+JM80d/8nKCrTF2J6oD2smBAy9j7r/E8A0uh92VG3NJJJk0G2Y5zPsT7xmzvs
rILTb3pH6fcBc12T55pmYbCc/VlStq6TyKLglemf3HmbnWqEAZvgfap0SyJmANTz
jtTnEmCtx3X+gt+SjJT7kPi647PoPYj1VpqguP5ltY2+/qCJeAJ82yh5c59nNs02
ggg2bl8POUV97Hb9/sbIrUnEEjqJoQwkHcJVDhtklk1ZaauFND7LthKyK3j7B096
pupLN3rpQ+axzdNwp6G8eVUQVQWqOFGlm1lQ3eQsxNYXrW1EDBexnULmCYN+sUvS
QQGswSg+ql2/3EESXbwqOSOIKxf5Hhwnt1Ndzh0Wyjcl/yl7cAbQYCWGPj/CfVI2
OkZMuZil0Y4KKPERjTwn5AzM
=xbHN
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-c0a4-4ad8-8d0b-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf/VsoUELxJFlx0IKo87R6VgpULJ092/gxHeKH8zx7PjFdC
KPZpQDQCqCJc72JegLFKbKsQXKz+Mca6A4kCLdLmBohnspfKfzSALagD0/fbnVef
R3qDi8Pi2rR7A/wq0M7dwTeYWUBZ5B/G5cVsDtYd+L4Fj/SXYYcg2Ud0OiUOIfCr
KO+WWuDTuWfSQPH54KcLjGVNFeXXvBqxm70rhjMc1Ukc3x/G9vJFI4bxitTHP9Be
Qx9Nc7qqPKLhkhsq0IbHEu1iKnTroDsNQDG83raGHNJF+DdZBgSp6APQ7fvsEp83
WFyI2OlOlbyKZUUSCNF/a8qzmbdholIfDfHxOFBCpNJBAYtYlvOoCpNRhBhJJHHT
DQYBsGKhemJcQxAUjkSmF8wCNRpJ3kEAOi2pZroXI8IoKcr2iYO/UaUMjpHgEmqH
TNs=
=4Rbn
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-cb68-40eb-a945-5939dbeb2d5e',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAwvNmZMMcWZiAQf+NeiHrZKgj3D2kMsrr/q05HO/hC4P2Nr1YK9cgEGITA0C
na7NN9MfiIgtaZmwIRIBceda5o5OBgXhpeqwUL/IkGoS84OuquovmOOzF65mdFvc
Jkbexv0HHe/CXBmyaHD8FXMzk4AdWf4mvz9X1bhbE/PqQAy4a2dJ35E8/3pvnasv
DGjIQSdwJxsUuNoSRP4SAq90m3HYPRcXt4Qn+mWbcMjJjYoIcDNP/3O/opHQDTgh
t9yL2ni6xjknpyLkrau1wDyQFgYPhcTUxYM2jk9ET9454yGgffz8XBHNy38Iz9oI
anhUS13u5rWIT74GVCEK5JRsJ+WXPmKIfyPJ71hRh9JDAckCthsY7EORzYEtghTM
VdsSr3tnQa7S+OXBkd3/CSleR2cWxz4GlkO9UHLKHu6xV3mrRxfBxJLQiQdTWFq4
L/kf2A==
=BcGh
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-db78-4897-9099-5939dbeb2d5e',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'resource_id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4NlM/alsYWgAQ/+NIgmTo1yHRQiYZkIFmGHjHf0kAax9DzCEn8ubaB8JzsU
KnhRsw2Rx3E6+BeeSSaKM8AhhzYwDMzxsCXyG7DnsSBrsLGkrXN1nJXzpeIxorQT
A8HNzXu9MijK/C2XZ1Wg/ZmQPAK25Ot62Q8NHVuf9iqkURFVTMBkSB4Lp1ogd1K3
daW2HZxZSK/yv7uyjBt7mRWNzUYhh8x+QBhoi7Z7PfevAKIjp1Mn7sE1mnHe2am/
iwClq7i8ebCKbIILMwiLKNzwFAgr/LWhZdEt2J2oRIPOCsbUWZqgEEtAt0ab/g5t
xYROKjq9eJVrjwli6AElH7xjUlk0EjATUowKau2aHVnyhpdlLv/MtU1sTv4NYuQL
czREwpeuk7SUbpjKBN3EWccvxPnDS/DHNgJFtCTKJnEI58Roqgoz6UxUWe7fZPbF
JY6R/LIaCEF3V26zBUCzvn29vtp81cFiGFAS+TA3I30v13HZefHBWLo/cNy+ffiO
aaFL1zhpmeB3EYtBQoEjKj+jF8W8yLdD0eBvSfG3crnggM+X+c9jImo//AsDgNkM
eu5I9a31EXZvWQKEgErUldOBjAuTivZ/bG3sg0Sd1V9FfQRyS3bya8HxAyX4FMmw
9j1S+HL/Nps2HYGh6YpdJVDTxILaTaQewfrDUD0IaM083nPLyY8JGoqkL5VlFtjS
QwE3f/b6YZc/raN90/5oozaufmNV07dYLhoMotJ7FNbNtklRrk07F+PgYTenOvad
kO0tW/IF5bL2Blu1N93q+hF3S7Y=
=8Mqk
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-e70c-456f-b5d4-5939dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUAQ/9Fuq1oavUKkhBwZqAKT0ktjvMl8XWVr4kE8W8/isasHcX
Ug9L8Hy7y0bKtEiPGSVn8ULvUhaFVI1DMsfrQJ05w+Y0dfHhDx42ASIzJCNFuEc6
tyWJ8fipFu+eQ6LAzKeFURCUxPlzIifl3SsJEd5H8UoGhzkZI/yhBHBgqJfy938w
HTUFXCfCoNVIMypDybWES+AITF+6kffstGyDjcW1kxe4R5ELOtxTxVTARZqZfg/k
VIBKcNJLFv/BCJchQQb9MAl/3+ganFNPyi3Px9BlLjbvOD2AEvlZ3Me8fTVOmt5l
3HZTAkzQRWb4l1Ax0/6BYUsvYnYWo7UqkaAikrf2NTL+VimyKiB7kODPYAGZakEU
pqBXdT0ig/MtjCy62D1SkORmHWGE8CY8BVJ1WQtObbC9dW9AXCt3NWwQpu1Df2CR
qcQJTRABecvPrRtgw7RjEnBggya0tU+umfjdS6AMUJaFPYEJGSUua5t/PI71Vl6U
xO8XzyiJ1phZKdgQzpgjIDHEeFoXsXMPgSsGl3K1F16azQh3y9Lz/p/ubYAqX2q/
N8JCZb9o0n+jsLr/ab3gO+qE2oIvv+l+kf0kpaGkW1uNt7J1Tsw5ioJv3np5FA73
o9vRoRblFQpBkuYjLAv1y129zQok0GzA/h8aT9hMGpwyfTygKUHcJ4hDKCdX7bHS
QQHL5tzafp7a94pVgs/ap+7uNDuoUlIylXxD0I2uxU7IGMU4Mppa65HpW9dUg76O
emhXI1Jgrq5/90LvMtfr+vVy
=lOkr
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55c75364-fdac-4729-a655-5939dbeb2d5e',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'resource_id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQIMA4Djy8VF6QYUARAAg06zLts0jZC2DSpJYSiU5xSRTqNSapaj2ePZJxovlmRA
i0uwUPOzvTIJeJiUrtbnFe6AWhdGYxTdF56oax6Grcz+ptZMGeGBnP/Sw7H4WFD0
4pfJHkY6rICznvEVS+hyE5Aoq4E01JOIkBkwMih9Q6ZpvNVJcB7F3PHCYftOunWg
rrQc11zlw7mOopuSNtuvqlpc9ZNWEq8kNcRzxKZ+ykwrRTZtGOm6soPqPP3lmgFS
fxDrKqInUnMozUyNMuK6gdd4DTWZGOlzP1poLy7CeTKBGKg+0ue5sy+RlbUf4+H0
WAGM0xPHlrQO3MZT2CAywBheQAPrzrq7AYIWs2uyqr+muS77XuBJL8ycUnFZCxCw
K4ygUl+M+L9g98tdiE+7mgOHLGBf7FKa6KTe89HEX1rcdJlizVaX0g+EIF4PgnE5
+iz/CwzB4ztQw37iMulQFefTO5vWpXd7yb0XTY7aPmRlGBIftCJlXlAGH/mWxZXc
V5WNsZr9q9GJYDdLVtXwLE5bEPjZIafCaQ2PpN9qwHLb6g4EZab6mQV7cbowKXyq
HmOfLqATaGKzcQjdkbjYLDmUzklxNSOnEzxLrZ7qSvAsiSyJsJECARJDZNbd1CFe
nsN6ATmFyd7rHD1vZHMQ9Wa4YEplgzgrEN9zDvDwfcvvh/eba7BDL4u1zxXPWQTS
QQFaMd1wqe7xoYVSVqsHASD6KnRBocdGTB+ZShh0mpMc/BLdDH3Tr1rqPqLeRCJU
vCxh3z3JJCU0uagkHX0F3F5m
=ssF5
-----END PGP MESSAGE-----
',
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
	);

}
