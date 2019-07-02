<?php
namespace App\Test\Fixture\Base;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GpgkeysFixture
 */
class GpgkeysFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'armored_key' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'bits' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '2048', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'uid' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'key_id' => ['type' => 'string', 'length' => 16, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fingerprint' => ['type' => 'string', 'length' => 51, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'type' => ['type' => 'string', 'length' => 16, 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'expires' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'key_created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fingerprint' => ['type' => 'index', 'columns' => ['fingerprint'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => '0239c721-8b7d-59fc-9bff-69e75aff349c',
                'user_id' => 'af5e1f70-a0ee-5b76-935b-c846f8a6a190',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

mQENBFv/eJgBCADDkW8IYwHmaQXWw5Dce9OzPH4N5NMuhdgli286ADBH3/Tkfi96
2SBtcf3sOfw0yNXlFU9F2yv9c+pAsjL+TUveTotCcKp3GflT4qCKbTTj2Vt09m8z
8nrZUwe05szcWqnCKCh7sBGQlFG3GkiH42QQ7kqE0vuEa08eSYUhBWYsK28hBtUJ
sXC2iP4sNymC+0DmzpdJ6DjZJUpoHnk77B1IvPAPTDo/jFH4/PwAMoi4khPvFjMJ
gKq40exIL/a60osYZN1D2KrawEjPRo3jJslrr6F2OwBJ77bTLCScHLxRmE3LOULp
YhkHx1A6GmVzZoF0BIBTKfWk21lM9BhI7wXxABEBAAG0I1Bhc3Nib2x0IGR1bW15
IDxkdW1teUBwYXNzYm9sdC5jb20+iQFUBBMBCAA+FiEENlfUAuY5Y5ZX4xTR7Hu+
/5sJExsFAlv/eJgCGwMFCQeGH4AFCwkIBwIGFQoJCAsCBBYCAwECHgECF4AACgkQ
7Hu+/5sJExsefQgAkW+m4AAE1skaUol2StivuwDaO5ncpo25YC9+jg8TTRlUq7qq
cM1Dfys+7G5leOLNrIA98e+Rv3gtlLy3UevGVRN4R3iRhV7A9bgb3o/rQR2dVI3P
XEkB2iKGY/YsmayebzaMwY2rWhYrqJC4VDkAiLP7nC1xFDkBvzGvIxg/fJWi0eiv
NbQ/ztZla1ZctxttNRejDyLWzDgvR0aruv2+rRbO++QzrLEXv/NThD4Iy8diHM48
QoVWKwKOgHNorNYi4zeBOycP6KJ3No0oOOvnQ1dMa8EUee7FEgDp9pZ7TKpcC2P0
FEkjd4HDiLYZ0ppci0VAd4eLKddUbtEoseEYKrkBDQRb/3iYAQgA1SxFmNm4Byys
7MFXebJsh9TfYDcS0wnAXKy6frABFS1O/e35djH5Emr9xKTFVQn9VouJ6jd5WDCg
oplssKLC1izItQePe2p6JLP4p+Zv23MfsluyEEjlHviT/VOwGCYXuYjKgqrHd/Uj
XPKijsrLKH2BIXWB1Zt8gHxS8StL+632SXT3ZONETf7nKKnHosIxa8ATBm9Ncr1Z
aqahQmuOFqqyVw1U34vznBz8Xx009h39oKkJTymUXEzb/rYCdo6iKLSO6NqpG2Gz
0H8wl2q6KiG84hcSEFiJ6t9m8U9iq08PxSyV8AUaY950Pa0yI/8AkX+yxLEXkHNF
tbptB0fKPQARAQABiQE8BBgBCAAmFiEENlfUAuY5Y5ZX4xTR7Hu+/5sJExsFAlv/
eJgCGwwFCQeGH4AACgkQ7Hu+/5sJExvluggApQcvGqkfyD4Eb115LUmi549IKKWq
8FFf85MWoZJ0BLNpIiWLBZFIs8dC4GJYSc1TaBlqlPtaHVh4kxlMvmAWGvDJ0AiE
GVhwE8B5T7pMkFZBIzKPpOPMxBSIue//K2XzxN0yXz+Rae7wpdQlgbcHByZZPnp3
/9E46AOwf5WDWu9J3081jIspeoAl4XOOncVi4azCNX8iwPcJVERQnInnpqBEV9qf
H7sFPO+a9XpBJWjB8mMJzoA3ICWzb0u5YyUpBU6LmHHCGWY+gBDaNKMbRoRUUYyK
eZOICKSe4NoPeN03QbqyJsSV1vynpafS+G+AFfbCGnj0dy6DvWldiSR6kA==
=OtIW
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2048,
                'uid' => 'Passbolt dummy <dummy@passbolt.com>',
                'key_id' => '9B09131B',
                'fingerprint' => '3657D402E639639657E314D1EC7BBEFF9B09131B',
                'type' => 'RSA',
                'expires' => '2022-11-29 05:26:48',
                'key_created' => '2018-11-29 05:26:48',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => '04481719-5d9d-5e22-880a-a6b9270601d2',
                'user_id' => 'f848277c-5398-58f8-a82a-72397af2d450',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

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
=Kwft
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Ada Lovelace <ada@passbolt.com>',
                'key_id' => '5D9B054F',
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-08-09 12:48:31',
                'deleted' => false,
                'created' => '2019-07-02 18:51:45',
                'modified' => '2019-07-02 18:51:45'
            ],
            [
                'id' => '0cb9e76f-4994-5e43-b3eb-848815042a03',
                'user_id' => '92946500-2940-54ff-889a-3da69afe5078',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

mQENBFv/eJgBCADDkW8IYwHmaQXWw5Dce9OzPH4N5NMuhdgli286ADBH3/Tkfi96
2SBtcf3sOfw0yNXlFU9F2yv9c+pAsjL+TUveTotCcKp3GflT4qCKbTTj2Vt09m8z
8nrZUwe05szcWqnCKCh7sBGQlFG3GkiH42QQ7kqE0vuEa08eSYUhBWYsK28hBtUJ
sXC2iP4sNymC+0DmzpdJ6DjZJUpoHnk77B1IvPAPTDo/jFH4/PwAMoi4khPvFjMJ
gKq40exIL/a60osYZN1D2KrawEjPRo3jJslrr6F2OwBJ77bTLCScHLxRmE3LOULp
YhkHx1A6GmVzZoF0BIBTKfWk21lM9BhI7wXxABEBAAG0I1Bhc3Nib2x0IGR1bW15
IDxkdW1teUBwYXNzYm9sdC5jb20+iQFUBBMBCAA+FiEENlfUAuY5Y5ZX4xTR7Hu+
/5sJExsFAlv/eJgCGwMFCQeGH4AFCwkIBwIGFQoJCAsCBBYCAwECHgECF4AACgkQ
7Hu+/5sJExsefQgAkW+m4AAE1skaUol2StivuwDaO5ncpo25YC9+jg8TTRlUq7qq
cM1Dfys+7G5leOLNrIA98e+Rv3gtlLy3UevGVRN4R3iRhV7A9bgb3o/rQR2dVI3P
XEkB2iKGY/YsmayebzaMwY2rWhYrqJC4VDkAiLP7nC1xFDkBvzGvIxg/fJWi0eiv
NbQ/ztZla1ZctxttNRejDyLWzDgvR0aruv2+rRbO++QzrLEXv/NThD4Iy8diHM48
QoVWKwKOgHNorNYi4zeBOycP6KJ3No0oOOvnQ1dMa8EUee7FEgDp9pZ7TKpcC2P0
FEkjd4HDiLYZ0ppci0VAd4eLKddUbtEoseEYKrkBDQRb/3iYAQgA1SxFmNm4Byys
7MFXebJsh9TfYDcS0wnAXKy6frABFS1O/e35djH5Emr9xKTFVQn9VouJ6jd5WDCg
oplssKLC1izItQePe2p6JLP4p+Zv23MfsluyEEjlHviT/VOwGCYXuYjKgqrHd/Uj
XPKijsrLKH2BIXWB1Zt8gHxS8StL+632SXT3ZONETf7nKKnHosIxa8ATBm9Ncr1Z
aqahQmuOFqqyVw1U34vznBz8Xx009h39oKkJTymUXEzb/rYCdo6iKLSO6NqpG2Gz
0H8wl2q6KiG84hcSEFiJ6t9m8U9iq08PxSyV8AUaY950Pa0yI/8AkX+yxLEXkHNF
tbptB0fKPQARAQABiQE8BBgBCAAmFiEENlfUAuY5Y5ZX4xTR7Hu+/5sJExsFAlv/
eJgCGwwFCQeGH4AACgkQ7Hu+/5sJExvluggApQcvGqkfyD4Eb115LUmi549IKKWq
8FFf85MWoZJ0BLNpIiWLBZFIs8dC4GJYSc1TaBlqlPtaHVh4kxlMvmAWGvDJ0AiE
GVhwE8B5T7pMkFZBIzKPpOPMxBSIue//K2XzxN0yXz+Rae7wpdQlgbcHByZZPnp3
/9E46AOwf5WDWu9J3081jIspeoAl4XOOncVi4azCNX8iwPcJVERQnInnpqBEV9qf
H7sFPO+a9XpBJWjB8mMJzoA3ICWzb0u5YyUpBU6LmHHCGWY+gBDaNKMbRoRUUYyK
eZOICKSe4NoPeN03QbqyJsSV1vynpafS+G+AFfbCGnj0dy6DvWldiSR6kA==
=OtIW
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2048,
                'uid' => 'Passbolt dummy <dummy@passbolt.com>',
                'key_id' => '9B09131B',
                'fingerprint' => '3657D402E639639657E314D1EC7BBEFF9B09131B',
                'type' => 'RSA',
                'expires' => '2022-11-29 05:26:48',
                'key_created' => '2018-11-29 05:26:48',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => '118fd79b-b683-5595-98d5-fe622c679711',
                'user_id' => '904bcd9f-ff51-5cfd-9de8-d2c876ade498',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
RQKw
=8Nu4
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2048,
                'uid' => 'Irene Greif <irene@passbolt.com>',
                'key_id' => '74FE8D31',
                'fingerprint' => 'FDC7DF9AB0C61C33B2D871C25207C56474FE8D31',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2016-05-25 13:25:07',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => '1d3d0565-f075-50d4-b58a-cbb82700e79b',
                'user_id' => '640ebc06-5ec1-5322-a1ae-6120ed2f3a74',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

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
=Rjw8
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Carol Shaw <carol@passbolt.com>',
                'key_id' => '82945D3E',
                'fingerprint' => '57DE7D79ABE733A235EB1F84CDF8FC8682945D3E',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-07-02 11:25:12',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => '2111bdd2-b19c-55a1-94ae-13e9ae67c1e9',
                'user_id' => '8d04cf98-716b-5f6d-9fe8-c130f8992646',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

mQINBFWWaTMBEADRzy5PKpWKGNnNJO5JpaV1050Tmjmo+zXOth6Ta/cZ+1kgeBun
IbyRfE25p7mIyfrR/TDHfgnW/OwUEARhngFlt6B0dxxWALHA8mZyv3eLAXqIMei9
b5m98506KXx1hsZDL2Io3SJa4C8fp/lb6NoY/YajDrTifUjtdQwo3AYp8bGPqkpk
10R2ZrmD+xol1FHcImk2ySxavIVht+72cWlHm1i9EoXG0XiCEIwm9gepFjux+3FX
zJ3otihOgExxAyxa5cyonn3dkAKfFUHrMMtRfm+6C7ETtdsDtaH1J2NdYwbH/r1o
NIh32M4RZPA66hrBg1YRVs5O81vo4Ut7DNZVmiKhQwA1b3OK7nSAH4r/AlbReaH2
nFACv8/lyoLN5hFnUIa9vO4FHwsM7X4aHmzydT6qgbUvXdfCLV2P6p9bg9RpNuEu
8ymJjpkKJWVlcQZWoabfx8WwQ2eTNh8Q42345T2/moYBpcL0a4AULywXpKYswaGX
WrK4fUX1P8aCR0R/zQBPrSE8t+vx9n2nVa6RnseIIe45h9vSoF6cezeJGZ4BMbg5
1D9d+qPJYdcj2GSJrEjO6dktMTYY9IB+VGCLAs/7Sfwr0VQH0bru9Y22uywJ/faO
MoluZ6NTSlmAlM4WpNuQVMXkg4eJ5ZN+QyClAFug9ArorZi1eo/qHQ3B9wARAQAB
tB9IZWR5IExhbWFyciA8aGVkeUBwYXNzYm9sdC5jb20+iQJOBBMBCgA4AhsDBQsJ
CAcDBRUKCQgLBRYCAwEAAh4BAheAFiEE7Tn6HRXAsqgTWahykoCI2qgSph4FAl0b
nPYACgkQkoCI2qgSph4W9A//Uvqb6HiS6CEE9z8sv8qDkUStK5s32K0UzS96pM7G
6AyXAxTRiPVCpPX9zPFn/n9P9eJiRM4UGbOXsM6tszeijjI8dmy5ykVZyQu3SlBW
mHJCIJVFtLcQl0RJgYnkRgyfetUV8VbOR4PpgRveB8NlOzKt1vpqa9J3eDbPAl0i
wEus4RKbnI1FKdur0BM8bGNAi8MW1NAe7fO6d+13bqnW8lekv3eFP2WHY0vEAaDt
MZntQcFFCmSVzNqBGR2tyASm137mZsaoWZFnJtBdpXN9vnDQBGDKf6fWk2GW/1/B
22bgGf9++mDnwP6oDHaqJGVueVaUdyEQjdPAXIje0BXPbXspalwv0mndwbC5f27G
Zqeh3uYzZUybHDZZtx7aISmAv2CuA8f9w/wjl7jH8BS2miAXnZiTMdtC4ESFesAm
C8/8BXwOZEjsdJFa5s90AvbYdqRNWfoPXx3zWZ3Zmyn/ncEaCkjROJPjBYIbeXDt
7KqgG1rFoHX+xxlpW4/C0Gn3a4nd7Emb3FwXO2R9OP2v0OK3gPfZzSnFSxs5jS95
7jQQcYvCCOtqgoXwxTTTox+8NLCMkIQXZRWc2rLZBsLPt4GnZkvR40SVBaP+46/K
6uAXh+BV7f89zDvrVoJv7GA8ci1bUXF71EZdaX6RfaQ8/LwXES1dIakJHm/fEvJt
EjK5Ag0EVZZpMwEQAKbNx8KGrEkdt3RAjy0+AncC8/H36KozDn81eLti4fmxKTUf
u/2ysC3cbFaxCbUVAZvOWfS8WMRZuLBVkVVqTjJ4v7bq/D3h2fjqZsct/qsxMBJJ
K2qWklTt2ekdTkk3cUmQHlR2pBce+3VimtbHcs5K0atQ/BgnAVN8w8tKbKSUCbDy
WPozLTtJ28eEMWtEXMaBl1WDb6FwF7f+zcLG3pAcAz2ludopRLt1O/uOkKJ8VV6R
a4GdRq6U3QthxlGJen3yW+Ju6lhPYaFANJ1pyvKaz4Xt27J/vGfhP9ihylkKtM2n
kV3VM5seUozapvKQGMfyln8f2QeC++y3nv0bLFj6ANvE9LXjChYDkjKJU3GM53D3
G4SHYdXee6sEv/Qw1dNBc+9CEDnzcnVXwedD7gwSQ8SPmeLMSTFf1G6+JM78Hj4W
L6r/59QNowQobVjNWUkZcW/ZPxPepsI64GqWH6fGsJGajpILuccUuz4qveDSrTbO
LHj1oj4bKfQeZZ0DDPg9WMH3aaoz3v0FtrNAnPLFs9tYI/U/L7bk/9OvUztsrgmt
y/k3VLW0E6lcokeRuMFPNrieK8QZXWTtdyRCM6t08IOZVpQqmUJROmH9i6/b3SL0
K2f7ZOZObwlRt3Dd4bry1lS6CCG0Vka35KRc11ps0hmXOz5UfNgu2TjlY+nrABEB
AAGJAjYEGAEKACACGwwWIQTtOfodFcCyqBNZqHKSgIjaqBKmHgUCXRudAQAKCRCS
gIjaqBKmHsJcEAC+hjas2JxLYZNOGlsJYit+AvLNQDoQuc0bn6Bln2AVWdNLkpBI
hoCzDqASx9rb8NSc0CM1i2gewke3/zjrhUbu7sptkPFQQF5UV8Eqczfe8L9u1sDS
0o51C7s4WzbQs1ooHRWkcn5XgU3Yn26yRVfiCowOR2O4ttEeiLC2upUi0XwjhpI9
NV/lPDV4nd6QlhZyk6jlfMNc5vN0W2Rr7GJTgxcriaXuvJrssPHgrqj9bjsjao4r
NRXvEKLjYHWvaL/TKVdefhHxngSLa4dLzcBLDh6evlW0iroKL4LfSqAWIUMxlrZm
ZrqYoLhHMGqDjnQJu5GycNTjNlge8GI/GWu77SMkppYtdKwERPjvCJ1vx+eVSNOG
xDvQ6OUoSiiwrjp1OkNeB3C9Gb0De1/32XMvCBATKIqibX7WWJj/WUL8Z+M8nMFb
fp0ZLN107A9x3RWnEbSMC5OJq+NDZFa2dqXPHewtoYyifW+dSUDoaeit7aKNaMqI
OwoBszYWnO0Y2gQ9JjO/4foJZPDYPzaasr6NIy0OAQQonr4KC0SZbCBkA2aJ45Tq
u8J4Azkwu5Dw09l+UHwPqSb/3EJLI0Sc72M/fNrbVkk8o4r+SbxfZOUwqV5mCox7
nJk6Cf187yErhiX/ozLwXOCDDIDlT0vqPdgO6K2jbr8AoZpF+Zd5CzcZFA==
=jTGL
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Hedy Lamarr <hedy@passbolt.com>',
                'key_id' => 'A812A61E',
                'fingerprint' => 'ED39FA1D15C0B2A81359A872928088DAA812A61E',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-07-03 10:51:31',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => '37691d6a-a057-57bf-80e1-485b59d790fa',
                'user_id' => '742554b6-2940-5b7d-a8e7-b03a19f78b8e',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

mQENBFv/eJgBCADDkW8IYwHmaQXWw5Dce9OzPH4N5NMuhdgli286ADBH3/Tkfi96
2SBtcf3sOfw0yNXlFU9F2yv9c+pAsjL+TUveTotCcKp3GflT4qCKbTTj2Vt09m8z
8nrZUwe05szcWqnCKCh7sBGQlFG3GkiH42QQ7kqE0vuEa08eSYUhBWYsK28hBtUJ
sXC2iP4sNymC+0DmzpdJ6DjZJUpoHnk77B1IvPAPTDo/jFH4/PwAMoi4khPvFjMJ
gKq40exIL/a60osYZN1D2KrawEjPRo3jJslrr6F2OwBJ77bTLCScHLxRmE3LOULp
YhkHx1A6GmVzZoF0BIBTKfWk21lM9BhI7wXxABEBAAG0I1Bhc3Nib2x0IGR1bW15
IDxkdW1teUBwYXNzYm9sdC5jb20+iQFUBBMBCAA+FiEENlfUAuY5Y5ZX4xTR7Hu+
/5sJExsFAlv/eJgCGwMFCQeGH4AFCwkIBwIGFQoJCAsCBBYCAwECHgECF4AACgkQ
7Hu+/5sJExsefQgAkW+m4AAE1skaUol2StivuwDaO5ncpo25YC9+jg8TTRlUq7qq
cM1Dfys+7G5leOLNrIA98e+Rv3gtlLy3UevGVRN4R3iRhV7A9bgb3o/rQR2dVI3P
XEkB2iKGY/YsmayebzaMwY2rWhYrqJC4VDkAiLP7nC1xFDkBvzGvIxg/fJWi0eiv
NbQ/ztZla1ZctxttNRejDyLWzDgvR0aruv2+rRbO++QzrLEXv/NThD4Iy8diHM48
QoVWKwKOgHNorNYi4zeBOycP6KJ3No0oOOvnQ1dMa8EUee7FEgDp9pZ7TKpcC2P0
FEkjd4HDiLYZ0ppci0VAd4eLKddUbtEoseEYKrkBDQRb/3iYAQgA1SxFmNm4Byys
7MFXebJsh9TfYDcS0wnAXKy6frABFS1O/e35djH5Emr9xKTFVQn9VouJ6jd5WDCg
oplssKLC1izItQePe2p6JLP4p+Zv23MfsluyEEjlHviT/VOwGCYXuYjKgqrHd/Uj
XPKijsrLKH2BIXWB1Zt8gHxS8StL+632SXT3ZONETf7nKKnHosIxa8ATBm9Ncr1Z
aqahQmuOFqqyVw1U34vznBz8Xx009h39oKkJTymUXEzb/rYCdo6iKLSO6NqpG2Gz
0H8wl2q6KiG84hcSEFiJ6t9m8U9iq08PxSyV8AUaY950Pa0yI/8AkX+yxLEXkHNF
tbptB0fKPQARAQABiQE8BBgBCAAmFiEENlfUAuY5Y5ZX4xTR7Hu+/5sJExsFAlv/
eJgCGwwFCQeGH4AACgkQ7Hu+/5sJExvluggApQcvGqkfyD4Eb115LUmi549IKKWq
8FFf85MWoZJ0BLNpIiWLBZFIs8dC4GJYSc1TaBlqlPtaHVh4kxlMvmAWGvDJ0AiE
GVhwE8B5T7pMkFZBIzKPpOPMxBSIue//K2XzxN0yXz+Rae7wpdQlgbcHByZZPnp3
/9E46AOwf5WDWu9J3081jIspeoAl4XOOncVi4azCNX8iwPcJVERQnInnpqBEV9qf
H7sFPO+a9XpBJWjB8mMJzoA3ICWzb0u5YyUpBU6LmHHCGWY+gBDaNKMbRoRUUYyK
eZOICKSe4NoPeN03QbqyJsSV1vynpafS+G+AFfbCGnj0dy6DvWldiSR6kA==
=OtIW
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2048,
                'uid' => 'Passbolt dummy <dummy@passbolt.com>',
                'key_id' => '9B09131B',
                'fingerprint' => '3657D402E639639657E314D1EC7BBEFF9B09131B',
                'type' => 'RSA',
                'expires' => '2022-11-29 05:26:48',
                'key_created' => '2018-11-29 05:26:48',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => '596201e6-8d3b-54b9-84e6-3ed6ef99113d',
                'user_id' => '1e73e104-d53e-579d-a0c4-e9aeaca76c56',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

mQINBFWWanIBEADEXgN8jBKhjQJhvuRhKL/iiqtNetH2Y1UL4ObPjVz5Sk6E2oKQ
B8eVotWDa4Hp65P3wJDnO29wwKXCSOwYsvIMp/q6hDvUzdf/toYWWiZSVRn2nG36
cL7nSu4opcTROxILT+jc7Gcs6JNm77MbhoNXppuKF0tCBWPtx9KNLmNhvg6WMQQs
2LgmxrJitiAJfqbVgGFvtLQyWD6gpoxbcnEo0ScymzF8l9gzDid0wHPap2izRaMJ
PUbhUQPT5IHwKA30xHmu4PVJ7iN0PdvGERXvDmf7xzPMJ9FH7dQqhlfwwKE+KQab
oQ3EI3OcAPDuXqFLApNDAHTqMa32/oKJSlD2VFkznmQmCIHbuhyHnLucB5d019qA
kBupor3ovKkPHxj6wg4w45tDn0xiG4Nv25E2EbWQBQIVgjjnVVRrqXAMeUSXO9R+
lgTo66moJUYnForNTKovS8jKe+aafu6DkxGOFfk1Bnb4XvYZoEXpHcuAtGVSYlny
IOglToWO1Ix4P2qTnsRy2Hrv3uQNVYK+PRuxAt5XLx5m9wdDVDGBItMA5L0iZwdF
BuEjVH+LF8AtsPX3Wgrlxn750nHImjdYZKfvtSiU1VCqbQY3CGyckL0CnkzRZ+7R
Pv+QWPdYTh/8LNKSms/buvrZeS+g/u2/vsDT4LwprxyLu6Ru8A9AwrORMQARAQAB
tB9MeW5uIEpvbGl0eiA8bHlubkBwYXNzYm9sdC5jb20+iQJOBBMBCgA4AhsDBQsJ
CAcDBRUKCQgLBRYCAwEAAh4BAheAFiEEtdNk7Nq1s/ecaHm4X2TRPo6ljPwFAl0b
nogACgkQX2TRPo6ljPxWZxAArUUavay5PqrZ5uyIptzJ/HvHsKKsMPXQPMjy81i7
Ci2XSKgpFh2s4XcfybmzCSw52HDdOTXev+TM4xhwKyT+Zel2f6Aq0GUU31sjrtHT
rUpbTxNiEcyyvmEmQlWIlEeuRcDFvABhqO5kp2Q7xkNmntWbNJGT27nC2jA8lKbC
YyN52CPsElWc5DxvnqyJqJGdge34pXL/aJB1QPhU3StS/diUXeAu1wNvyEk2ivWi
i1ymo4z5FY7hOr6Y2f20Effj7G/+Ps0RPJVLOw9aIauuVjL4qlOGpF039BuvEcPB
XY2dZiONsgsNdGnZ1aPCQWGxyX4nhIP0FFflVS0OOF7T/G2GdXLG/s6CHHPCpM90
XPaipbE/ABX/lKP6e/ek8tRoOjEIv1m2vQqF5ZJYFnnIJFPOCZm4SMfhFQYAlzSL
Jp+Y+Ib4t/R1xsQQDJCQONlCUN8QZ3BnbPd36hC56Br2q9htWYHcCvYKNrh5udIa
g94yNa2dDjMNnSO6Tt6cZNkFRk92Y5HStxo7v5nPGBy1m1YLQiSyiVBCDGivMLHr
DQwwVrikz6EypQSKwYwYIIgYoRJU8y8+P+it/qCfQP9aYTMUS6ElyeZCp2a1boh7
tM2w5AciZ0ex1tZCEY7sILfmOB452eAMxXIM0g83OEh0Hk2BsRDg578h4Lnt/F6c
0zu0IUx5bm5lIEpvbGl0eiA8bHlubmVAcGFzc2JvbHQuY29tPokCTgQTAQoAOAIb
AwULCQgHAwUVCgkICwUWAgMBAAIeAQIXgBYhBLXTZOzatbP3nGh5uF9k0T6OpYz8
BQJdG56EAAoJEF9k0T6OpYz8J1YQALPpVan50ocroz4/7P1Kzd40OKLbOlFtplc5
9tfjywDCdXA3Y7sH4COOlIa/ZCd8GPQz7PYmEv7KxE4SSgK86CLl8bteAdM7Wk9m
uGr1VzChuM/N5CmO0PLRUbnRgkA3CjXY+MNNrZmmfSu46tul0Ec7mSu5DUvFfLLO
x39fm4IACi5ymN6W8Y4aPrlW06dOuIx0ZE4QOheVq1MKHUIv2Fr8R45BVMzsaMaP
JZqOUd8X4OS1VZPJ9dWeLdObHyW2xCt6X+lLfbbsdfruvyZH9O9nRLykPa0kXZA2
VdmGlgpx1LJmMpN/HwfzRfJXFPQbb6FFMAcqiJGc+Dkb5Ph1fF/PeyX7Jod3jghB
5hYg3uCz/VyXBO/bPen/RU8Wg79SzZKfArC6k3bRReWTs9HscDV+1CkNd8aRsABe
WuLa0erIZdBU9gCF8fQLWORsy4py50Kjf5w5VAd7oyuuWtajSuY1P/kQ4X/5yNdW
F8LiPID0rUakAic4Mbvat7isQhqTAfNcaKj4u6QqMyQXPUSjX4pZXBbwUKyUjAtV
EVXg5cXL5i/Sqy7uBtNE4UFToxeL72KovwaPjw/+d/p3x0rj8wfbw1Q3WqtpDmuI
syE9F3rl+yxv+jfW9hfYh3NZmNZ+wX6Om2EHy81lwSF67DkHu4Q0DgHykj0jQWdr
zYkI+XoBuQINBFWWanIBEADv81fpcHsJr5Ah2519BRZHPL8R4XswmFGtWoLrAMbq
k7/FIcb9FbrhoOTLiAxc40+wgkBwvP/rhVp4K+qyOrw+ycyYfF+U7wWlnxvyKg8H
xos2fntccN9OXLoKqeO2FyOsE+acira/Q+IYLx2+lszbebTadThIuU4dFk+yqbeN
3JGPTf4CixE8tN3k1OdbTksGENhWBAPzY6Kfbi91O0LsIedsm7XolYzTZDsCSjdg
TtyyaeoJMh1ptdEX5DM5kIWv1dtQat2xhn51NhPD0VTY8CrQJLyYuFe0sV5Vu/+l
2/s/HQfSV+f77i+4/aLwDipuHoqAkblZQno1KoqoMpjlgFkZRlwqPgKpWgz4xvf0
N9uK5GqvGDH4qxS4/rye8M1efcwTsH1DZqiv1NQd1wwZKGfQzXOhvq6kkEQ+TCOl
n8uMRsMvIWKXRq7MtC7d1i2Qjp4MRffUziAahW3VyTeQntnYyDYac7zTp3inHTbs
suJReBHHDUihh2iVCnasvuo/inCZxQB1C4O1qH0/KWs6viBMUcanNn+cIDA+5Fy5
IEMmXVMHdmN6j84BHjZ1EzHIwJjig4RQGD4Hb2v5h03rLg/F4fnbkpDqDetC/9DI
cVT2ZJk0yE4T6K7Y/UheVfXn2dF/UR2HR8l3wDIgNwFppKfLgKbvzFlTaaMMNEDk
vwARAQABiQI2BBgBCgAgAhsMFiEEtdNk7Nq1s/ecaHm4X2TRPo6ljPwFAl0bnpAA
CgkQX2TRPo6ljPyLUQ//eBF7g0+Wn1EQPTWVZmDEoOzK3GJsV2rgVGhyg2rMCLWJ
7znl1TTQh/jaGa9j8vFBBGNc3NbUdVz6J3vdl6HPfXIWlEXgT59kF7N/8jjBfsYE
53RdAw3R5sLE5vn7qqIzBBtYkFl7KsKsSjh+U96LOO4I/Rhaen6T9yTVOy82c3o+
kbDkAlKIFZ/tEKsmcyGVwWYIXE3Np8ECq1zJoFpSO4KZ84zz3Egn9U+cjKvbWGEh
8/irHrWMhdJO/RBit00m7igHCzg9pxpkeuPP1xQkr1QNo1Esf0Q+RkN8lUgCeS1R
E5hQxlDFCAeyI0lm0aaeOULqjK37O98so0RhyNDvDeyUWWGSDuInhvJQfD3gxXsn
OsAoC3NdLO8geEY4TqnLIC5vQP6d3Tak4PEcP5r3aYeivbQLP/7itz3naBlwGne3
nl4UFUhrTFMxxcu8FLIKAhM8cREedbCzNosbf3tOl5qB0+t2I4hMV+DIC2xzOlkK
uz9HMfmFRqAKRorBPYykK6gU8RZmyjSolkZGUrNjXqF/IhV51vMJbjqHWzdfgrZ0
2J4uSMYcZdsgWTqhAvNJMIEna0rv5HnPGgaL0vE6CY6LM2A9SvLi7Wsafzu/aG4W
c+LkVBCVV14NzzHmUm+4pjM/dVxyQCfURrT1rxB+9y5sy2F6SpCX4RkbZcsn+Ho=
=O082
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Lynn Jolitz <lynn@passbolt.com>',
                'key_id' => '8EA58CFC',
                'fingerprint' => 'B5D364ECDAB5B3F79C6879B85F64D13E8EA58CFC',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-07-03 10:56:50',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => '82eaeed6-32a9-5e56-af93-6bc362a9d62b',
                'user_id' => '887422c0-bef6-59a7-bbda-84c253ee0848',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

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
=82jm
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Frances Allen <frances@passbolt.com>',
                'key_id' => '477FB14C',
                'fingerprint' => '98DA33350692F21BD5F83A17E8DC5617477FB14C',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-07-03 10:48:31',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => '91d8a7fd-3ab3-5e98-a4a5-0d8694ff23b9',
                'user_id' => 'd57c10f5-639d-5160-9c81-8a0c6c4ec856',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

mQINBFY06pcBEADjYRuq05Zatu4qYtXmexbrwtUdakNJJHPlWxcusohdTLUmSXrt
7LegXBE3OjvV9HbdBQfbpjitFp8eJw5krYQmh1+w/UYjb5Jy/A7ma3oawzbVwNpL
wuAafYma5LLLloZD/OpYKprhWfW9FHKyq6t+AcH5CFs/HvixdrdbAO7K1/z6mgWc
T6HBP5/dGTseAlrvUDTsW1kzo6qsrOWoUunrqm31umsvcfNROtDKM16zgZl+GlYY
1BxNcRKr1/AcZUrp4zdSSc6IXrYjJ+1kgHz/ZoSrKn5QiqEn7wQEveJu+jNGSv8j
MvQgjq+AmzveJ/4f+RQirbe9JOeDgzX7NqloRil3I0FPFoivbRU0PHi4N2q7sN8e
YpXxXzuL+OEq1GQe5fTsSotQTRZUJxbdUS8DfPckQaK79HoybTQAgA6mgQf/C+U0
X2TiBUzgBuhayiW12kHmKyK02htDeRNOYs4bBMdeZhAFm+5C74LJ3FGQOHe+/o2o
Bktk0rAZScjizijzNzJviRB/3nAJSBW6NSNYcbnosk0ET2osg2tLvzegRI6+NQJE
b0EpByTMypUDhCNKgg5aEDUVWcq4iucps/1e6/2vg2XVB7xdphT4/K44ZeBHdFuf
hGQvs8rkAPzpkpsEWKgpTR+hdhbMmNiL984Ywk98nNuzgfkgpcP57xawNwARAQAB
tCtQYXNzYm9sdCBEZWZhdWx0IEFkbWluIDxhZG1pbkBwYXNzYm9sdC5jb20+iQJO
BBMBCgA4AhsDBQsJCAcDBRUKCQgLBRYCAwEAAh4BAheAFiEEDB0XYRENHjPJAG0a
WxszLtBkJtMFAl0bmoYACgkQWxszLtBkJtPnxg//Q9WOWUGf7VOnfbaIix3NGAON
I7rgXuLFc1E0vG20XWT2+C6xGskFwjoJbiyDrbMYnILGn7vDIn3MSoITemLjtt97
/lEXK7AgbJEWQWF1lxpXm0nCvjJ6h+qatGK96ncjcua6ecUut10A/CACpuqxfKOh
D6CaM5l/ksEDtwvrv2MIaVajuCvwg+yUx0I0rfAQv0YTXbJ5MRn1pvOo3c6n5Q0z
5eu/iiG0UNNIE3Tk5KpT02MTMv5ullpt3mtNjMHH0/TdPxCtUKVh4q34x3syiYLe
paddf5Ctv9CL52VWfsG3qFPHp7euOFY8lfzuemoqD9jcE7QIJnkCmwtLXLQrE0O2
RW/y/oXqrETXu2sFyHMr1Xw//QeJgIv63LBGmcPOj93VyHIlcUDarM2oq2+DXKxr
Ds2xfnFKVCZwpSvecIfKXUKsnX3AGrpetoZdfw0jAUVI3nt6YCu8KvczXxetfjOV
3HHXa40gtOZk5OoKbfuTjzQlpc1oaDyLH8PT1GYsN3wWoDs4zulh6uKDpSt+4z58
H1BfPFlrO2uhZSfk3E83uBQXZcABeXNxCdrTCJm8P90sbjLu1TlaeOnrWwVT7Yq8
i8LE7lbAXnT1HjQlDi8GB2+2EnZZmOX+Z84a16jDElZazUNsE8zT7OmyjuB7GGDb
QEFYzkb9dr1j1sukzty5Ag0EVjTqlwEQAJ37C9s4pq4jvcEF3bJgL+q3SBolgBIp
N1g1/woi9vEiOh+7e08Kr8mEhF04cpRDbhY6dcZ8OIXIQ99fgdNXfehlAWnI56NE
/FOIyif8TvGBfO6yE35fKSskwGNdUZWIZ0U0pxSXQvB+KEGWlq2c3Uf/jhTZDnLN
vfDjnYmn5ycp5sVWhtAmKFha9NJ6LGA0D1MC+jcCJCKtQRGgVvlqOESFDmQ7Pu8/
ayr2BO0URHJ0Ob30lHluCnoKIv50qGpL9BYuGAdCfLBHXzRQhHIbfc/cTPkK1kTX
X5x/MkiEl88TeGN+yjNVS7qqdxYgs+QYnDDZqevhWEvVyXVQjcCWSIHfjL1x5Ndq
YL6+ci/OxyIFoPs4K2umN3JPmpFi+fIPh2CexKy6BnyE8oAgNvgdDb6ZOfAtvShZ
PM7QG4LZal2+nYp4n7gJRh6kepTQT/4Bua0xOtRQhgcI4nGtcCxEDRMMzjqbGYlc
nciMjsiMg9LPpWPDA+xKrRZKYwVFy8vLx/alOz/h1BZjx2u7YmuaGENxE62Lfyh0
xeoCBDTdnWEOQTH6LVsomVtUO1FVap1t5jkYSdpxBuHf8/2Ye7N3FTMRKe9n4e75
sAJ00utnMl6P2Zca9mM4T29PK+LPFx2G2h35DQ7MbEid1cAZ8QVR3UyoiR8+u9jM
ek+9uFCm+nAxABEBAAGJAjYEGAEKACACGwwWIQQMHRdhEQ0eM8kAbRpbGzMu0GQm
0wUCXRuamQAKCRBbGzMu0GQm004PD/9sFmFkdoSqwU/En77+h0gt4knlgZbgj0iR
romnknIwLKBbJJXksHmMPXJB9b3WZ/gGV3pPVtDWDKg3NZW4HLK13w3s3wQ2ViVV
A6FzABDSkI3YBqkkasLRZU7oN9XajdFfph5wLhDSgTCjSncGfcjVzPugWKLqPPih
ZO6mpqxSFYEhx+p/O80Tlj90UsOFRdot7cqn5wOhXZtKsQ0RwaA/uq/sFe6UNKHG
2RBgQfoj5JbazJbvlgMiWxhBalwZKQWs8IBh/4ag8AFwwoJN+gOtNM9C4UCHu+yt
0Tv2/Tu+Apcj0oyFaKJD4uQUmChQ2fDRysqJEIhee+yL29mrdcB4jG7Q2rt8HbhY
wlsHKgas0YIHdR6dUOCiyw72i0khwrd2PDgxKRu5+cob6wMSqXbIIxFLLLACHy2s
Kd6fQcg8FxoivEiF0lRfMi32A/YWGJ/k1OoFCzW55KFXqqBMptYZWh2Jezhttmid
YHPc7jas7HEPnw3SvVM0gYAcmEVWWvjKfUpOhSYYkk/B71w9RuIpPyyI7G2XI8Db
G2ttngDIOL8njS6ybU9Og6yTNUoHL1wWEZN1b3fznKHcC9lyr8MIg00QNeDItt9i
ILCOkjoEdUdauqlRIa+EmUu+AL+JobrlQTzyrCIm7aaT3Hp9EyaEx5xvJDWtmjgf
FYNCFtV1fw==
=amwR
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Passbolt Default Admin <admin@passbolt.com>',
                'key_id' => 'D06426D3',
                'fingerprint' => '0C1D1761110D1E33C9006D1A5B1B332ED06426D3',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-10-31 16:21:43',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => '9801f2a6-a0f6-5cf6-8f81-df16855d07e9',
                'user_id' => 'a0559bb5-050b-50a3-ad39-c6756a46dbb7',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

mQENBFv/eJgBCADDkW8IYwHmaQXWw5Dce9OzPH4N5NMuhdgli286ADBH3/Tkfi96
2SBtcf3sOfw0yNXlFU9F2yv9c+pAsjL+TUveTotCcKp3GflT4qCKbTTj2Vt09m8z
8nrZUwe05szcWqnCKCh7sBGQlFG3GkiH42QQ7kqE0vuEa08eSYUhBWYsK28hBtUJ
sXC2iP4sNymC+0DmzpdJ6DjZJUpoHnk77B1IvPAPTDo/jFH4/PwAMoi4khPvFjMJ
gKq40exIL/a60osYZN1D2KrawEjPRo3jJslrr6F2OwBJ77bTLCScHLxRmE3LOULp
YhkHx1A6GmVzZoF0BIBTKfWk21lM9BhI7wXxABEBAAG0I1Bhc3Nib2x0IGR1bW15
IDxkdW1teUBwYXNzYm9sdC5jb20+iQFUBBMBCAA+FiEENlfUAuY5Y5ZX4xTR7Hu+
/5sJExsFAlv/eJgCGwMFCQeGH4AFCwkIBwIGFQoJCAsCBBYCAwECHgECF4AACgkQ
7Hu+/5sJExsefQgAkW+m4AAE1skaUol2StivuwDaO5ncpo25YC9+jg8TTRlUq7qq
cM1Dfys+7G5leOLNrIA98e+Rv3gtlLy3UevGVRN4R3iRhV7A9bgb3o/rQR2dVI3P
XEkB2iKGY/YsmayebzaMwY2rWhYrqJC4VDkAiLP7nC1xFDkBvzGvIxg/fJWi0eiv
NbQ/ztZla1ZctxttNRejDyLWzDgvR0aruv2+rRbO++QzrLEXv/NThD4Iy8diHM48
QoVWKwKOgHNorNYi4zeBOycP6KJ3No0oOOvnQ1dMa8EUee7FEgDp9pZ7TKpcC2P0
FEkjd4HDiLYZ0ppci0VAd4eLKddUbtEoseEYKrkBDQRb/3iYAQgA1SxFmNm4Byys
7MFXebJsh9TfYDcS0wnAXKy6frABFS1O/e35djH5Emr9xKTFVQn9VouJ6jd5WDCg
oplssKLC1izItQePe2p6JLP4p+Zv23MfsluyEEjlHviT/VOwGCYXuYjKgqrHd/Uj
XPKijsrLKH2BIXWB1Zt8gHxS8StL+632SXT3ZONETf7nKKnHosIxa8ATBm9Ncr1Z
aqahQmuOFqqyVw1U34vznBz8Xx009h39oKkJTymUXEzb/rYCdo6iKLSO6NqpG2Gz
0H8wl2q6KiG84hcSEFiJ6t9m8U9iq08PxSyV8AUaY950Pa0yI/8AkX+yxLEXkHNF
tbptB0fKPQARAQABiQE8BBgBCAAmFiEENlfUAuY5Y5ZX4xTR7Hu+/5sJExsFAlv/
eJgCGwwFCQeGH4AACgkQ7Hu+/5sJExvluggApQcvGqkfyD4Eb115LUmi549IKKWq
8FFf85MWoZJ0BLNpIiWLBZFIs8dC4GJYSc1TaBlqlPtaHVh4kxlMvmAWGvDJ0AiE
GVhwE8B5T7pMkFZBIzKPpOPMxBSIue//K2XzxN0yXz+Rae7wpdQlgbcHByZZPnp3
/9E46AOwf5WDWu9J3081jIspeoAl4XOOncVi4azCNX8iwPcJVERQnInnpqBEV9qf
H7sFPO+a9XpBJWjB8mMJzoA3ICWzb0u5YyUpBU6LmHHCGWY+gBDaNKMbRoRUUYyK
eZOICKSe4NoPeN03QbqyJsSV1vynpafS+G+AFfbCGnj0dy6DvWldiSR6kA==
=OtIW
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2048,
                'uid' => 'Passbolt dummy <dummy@passbolt.com>',
                'key_id' => '9B09131B',
                'fingerprint' => '3657D402E639639657E314D1EC7BBEFF9B09131B',
                'type' => 'RSA',
                'expires' => '2022-11-29 05:26:48',
                'key_created' => '2018-11-29 05:26:48',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'a1dd3f22-30b1-51f1-a22d-5aeeb00fee1e',
                'user_id' => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi6+SUBCADM9AAyXMBp32rNl4Hz2KqIsBYO7Fi9zFANzSM2jBCEuZVAE0pw
wVRgKOn3DAcis2RtUyd5p1n3v7okuYOEzATDxtRq33vmRX3U39Zr5UzeDD6rM3rd
T7RuaAjXhQA/O+ODw0eOATJf0sYftabVD6BRv93iKmOo1r8SEU56+GX8NrqcpD9R
p40wxcb0fRLfEie9vCex8VcusUaKXzqMARy9hctmFykoKl9cvvHkW0XYYnHWGr9G
KEZEytex2fpHNlkjVv6Ic1PUY+PxZyZ+6V0RgwMqztRdX8yrj07FoYgBi5+yJbBF
WI4LrYMAlQp+/vOsQuPkX2+6vI0RFHuNHehTABEBAAG0IFdhbmcgWGlhb3l1biA8
d2FuZ0BwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJYuvklAhsDBQsJCAcDBRUKCQgL
BRYCAwEAAh4BAheAAAoJEEdQAVv8B5UxId4H/0OD+N+I8+0go+FqWSNiLBKrFVDl
brqwrdcGMBFDWCCy95zQX2xxIpAhtLr0Dnn80ecu6UklzHsw8LbybsHf66v8daa8
Cr9yC5raNQfVGifdB77AkDuA8rjRP4cotAI3CsKlvV2YQRrCGWCRyIFI/u+UtOea
rdM3yv/JasLAHw8ExhDXwxhYkKgNwn3SZciFSaDLL0UwldBS93qi6pjYsmOVA1sq
OgT61Y2dvqgiNhIO5cmiVxlDNJcrHnCEcSepU5nmv0A72kNfmE4mOTnG4rtU3NwF
GdTntH26K5BfyQCDw9aHZklaGofuxVGeZ7ZwPyvSj5HZrPP1WgD7LnMHVJ25AQ0E
WLr5JQEIAKeyT+yEhWyjCQhrH5QUgieFUu6IV2npC+ErafvrtVnUeWLprWPBnDul
qoiTu7QbzEXT/7MbwlgticNq9QwINWsNSHKBvMjOXEFRhOltF4AXYTBJThnyFR/o
huGQezmjV4TpN8yXtpgiy21W+/XYjrY8PaIRM1Adc1hSmMSiuzLu+VtKIs+8tFFa
s8acWmskgKww2vHHJkxQ+ubWl5W1Vxa1Y/kOGjMc+qSHZ0v6jEnkUjaxLd0DW0hH
G8weEunPzBBSJKf7NstZ6c91+4LUCVoNqCYMkq3ymKRITN3vmJSfU+jIXNOd7OoA
+TP8Bsj/RS4opQ6kRrL3tficrX6QzcEAEQEAAYkBHwQYAQoACQUCWLr5JQIbDAAK
CRBHUAFb/AeVMbTTCADCdL5UyXBfCOp4D/T2L0uUL/2d37nyfOU7c2FCbVuviG+s
BAVypL7KaXNF6gp/FGidSUZDVIr+UzD9cv3s+7J0h5OeZZigyPlUbWaNy56CBJEr
YZH5u3+i6S9cc3udbP5DaD9EpASWUbsREi9t37O6hTS/OGBTGxH5KpFsVBIWBTL/
+XQPsd+YMdoBVka3yKSzkFMLRLHt3K0/miwXj7IK1g43mcYHUCrWxjjQFVjUZPBL
QiL18WhPkMO2lCHdGR9skSycwGDnt5WVtGnn0x7+JV0Yk8RpuopfOWo83+oBzq3c
erR4fuZF//Io+VikJe6mKufSflOpCDYfSOscdAA9
=q+Sl
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2048,
                'uid' => 'Wang Xiaoyun <wang@passbolt.com>',
                'key_id' => 'FC079531',
                'fingerprint' => '28C3C228F27C892A0583AF534750015BFC079531',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2017-03-04 17:28:05',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'b2581347-f1e0-5296-99af-baee3240dcf6',
                'user_id' => '8d038399-ecac-55b4-8ad3-b7f0650de2a2',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
w9E+yhN8gIA=
=qtKg
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4096,
                'uid' => 'Orna Berry <orna@passbolt.com>',
                'key_id' => '397EEAB6',
                'fingerprint' => 'E2E98DCC84FB41F69603C346EA62E0B3397EEAB6',
                'type' => 'RSA',
                'expires' => '2020-05-27 09:33:17',
                'key_created' => '2016-05-27 09:33:17',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'b3cdb8ae-c5ab-52e6-a394-8cd800bfaed6',
                'user_id' => 'c92a1885-1644-5bdb-8486-12d751b976ff',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi6+KABCACr10aZS+MSkQAuyducjLJ5Pb9rShxlmIXClTYry2RaoeOe7Pp3
jNcaNLtXNUhxI56AhGSuiRvnKC+yKTYWm4DmOEtLD4o1hEJ1SZSOKx4QLqCEW7dz
gK6+S9fOuZTpQuo78P5rAjtXo7PvI5LfSsZDjZZtlzkaB1i50bpr8u2uzVOC/2O+
TPK04iY+/EXF5kOt6qCgUYJibk1hnzAxQUZ6ORawNzoPAI5OPZwnwXlq1BB1YDSt
5vWgHJjhq+7V086CW8csgWBZwfQHXLH/Wt19NgTkmAkn4Znz/CA7b9fQivxFNZuU
UeS4IV8u8hX59rx0Vlfk2/abaeF+Hbyp+1C5ABEBAAG0I1RoZWxtYSBFc3RyaW4g
PHRoZWxtYUBwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJYuvigAhsDBQsJCAcDBRUK
CQgLBRYCAwEAAh4BAheAAAoJEKcRoHMXh+VNqtYH/i+pb0FDVEtO01HbK05CMQtP
1boWBnO5vv50zLHmAbWRcUTZoQ7KR2lOAhWAH8mAaqL5TEr85haU0Qa9MMRfm8x3
p344cxJrZkViXBxqTCUSaHj53v2npDlkD6o41yHazzhIdZgWKWjM4zdvhNM7Swop
THswRjXp2S/QwzmiFRhwR6rgAEBAxrU4lMxVsCHiam/Hy+U0mJSK3jN0/D5FOcMU
laFaVSC23Gdh6K4ho1dmWhUczMp8avgaqP4M+rk6/8AxKrprdBykkHvvvKFIcWDp
zYdyYYzXq8w4evCRDNpvjEA6CQZIwYvtIsZ3aIFjbbN2UpQuDwJJ4uzAzhE73lK5
AQ0EWLr4oAEIALzPqR1QG576wp/P+uDvrJ0EXqGD4rEG+2F3xclfkWmpRkCKQBWl
Kc40j4J0aNnPbRwclIa19C6GUNBaTIHEQ73tYhdREwku+7h5YO64XWrPH6bHLDCK
+dBpwf42bcCTQzfL5P+M35UGDai32f361xzOwbsXEJDBSkN+5MJlumN/LyPN/Vb0
xAMpx1uBb3qVgIUfq/UacjTJZjc8GjpqNm4vRw6M4/BVoBKPzyfUB5sxMkQum7yr
I+VO0gP+Md6FO5lp4tsXpSR0RSCd1roHdH7+sjy8OG/xXPG+05xitO2Ji3eumBM0
vhK4iynQCU1FHkhC1rzVVICr7UkXjvdJtsUAEQEAAYkBHwQYAQoACQUCWLr4oAIb
DAAKCRCnEaBzF4flTVM/B/9DbHQuNH7t+dKurPFHoJNTXIieMKzGSeoeRIr1R8ap
P/G6+xT4pV6UobPBH70SxzT8mMO591hnuRJ7qRA1Gpmq3EV+hSHD7SmczgjLbC2m
KgvL0uHHtV/a8qDh3CLDrW2/jsIHKGfDHkUP71+bC8wJ6mmRysP7LLJRcy8mtQuS
ZDFzwL3PcSJHaBjjxsk59FCv7zEyVrOSNwq6NOuYNLf6VDp6LSGsGPm4Bjb0odcp
lw8+0yy1CqS6XDpWdxSpAadsy3Fwv2pWZhHcaEw/HK4ppQUy3Rb5CYFoDnxAyZAL
wlPmpnNFp1iLbkFM2+/gVSR+9Ty2DNFPgRAov58Cb+oQ
=eX9A
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2048,
                'uid' => 'Thelma Estrin <thelma@passbolt.com>',
                'key_id' => '1787E54D',
                'fingerprint' => '522E59EAC81C5D8470C45077A711A0731787E54D',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2017-03-04 17:25:52',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'b4ac2e2f-2764-51e6-82c9-2066943733ff',
                'user_id' => 'e97b14ba-8957-57c9-a357-f78a6e1e1a46',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

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
=KJsT
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Betty Holberton <betty@passbolt.com>',
                'key_id' => 'E61D7009',
                'fingerprint' => 'A754860C3ADE5AB04599025ED3F1FE4BE61D7009',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-07-02 11:28:17',
                'deleted' => false,
                'created' => '2019-07-02 18:51:45',
                'modified' => '2019-07-02 18:51:45'
            ],
            [
                'id' => 'c4834439-b383-58bc-8386-a2e475d85318',
                'user_id' => 'e1ebc592-b90d-5e22-9f40-50e52911673b',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

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
=yEE2
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Marlyn Wescoff <marlyn@passbolt.com>',
                'key_id' => '0735B1C0',
                'fingerprint' => 'E4400EE5E49B86B96FB7D7F48BB0AD490735B1C0',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-07-03 10:58:34',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'cf85775a-cfb0-5ad5-90b2-ecfde355f7de',
                'user_id' => '0da907bd-5c57-5acc-ba39-c6ebe091f613',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

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
=h0zv
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Jean Bartik <jean@passbolt.com>',
                'key_id' => '24AA1193',
                'fingerprint' => '8F758E3BDD8445361A8A6AD073BAC28524AA1193',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-07-03 10:54:25',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'd7c9f849-71ba-5940-a3ca-ab26472c06fb',
                'user_id' => '620de627-8f07-5427-9149-e2c43219c5aa',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

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
=7xas
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Grace Hopper <grace@passbolt.com>',
                'key_id' => '9BC6A569',
                'fingerprint' => '63452C7A0AE6FAE8C8C309640BD9E2409BC6A569',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-07-03 10:49:21',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'da315c73-bf77-5aa6-8f10-faa47a579f15',
                'user_id' => '32d29702-85e2-539d-98ac-6abfa7aadf01',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

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
xp1ZRg5j
=X6r2
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Kathleen Antonelli <kathleen@passbolt.com>',
                'key_id' => 'A73AE279',
                'fingerprint' => '14D07AFFDE916BC904F17AFB4D203642A73AE279',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-07-03 10:55:55',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'e0a978e1-fa74-5cf1-803d-25818671e886',
                'user_id' => 'f7e9754a-2f64-5cdd-8ba2-178b33383505',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi69+MBCAC01FHt73wQwudYIPpD6XUtJXOoRWIMnLYFiBy4Y4VSpzySrtr4
TDMmcJFclLHt8eDGPanxMrIZjqVyjy7xPXN5PUpwOZjuqjvBofiVikkDP/wHcyUC
IvAbRw7IQvGF9hAMKEbBL9Yi4+31ZoxNOYbHrRxhW2gzUIPCo5GbKw+aRKCpEd3E
Y/Pp0biqpnfinumBMSrSDFCHg4/xtxr0cmrQrZEmlBwUiN68SsCXtayyg4zkPKNh
5I9BSY3esIqkHedzbZw/uoWBe1a1XEUEDbtme/jxGbphs51eaYJ++y8hBdJXsQ2N
ovl/U6RVQZejtD5RSpeFBIGmn75ch4XPcneNABEBAAG0G1BpbmcgRnUgPHBpbmdA
cGFzc2JvbHQuY29tPokBNwQTAQoAIQUCWLr34wIbAwULCQgHAwUVCgkICwUWAgMB
AAIeAQIXgAAKCRCs5l4WutVKivLEB/4j7cJpZTjtjjUTUNPoqfPPII1JnXk1seMc
fuHE/NDuiHHNhd+7GdbnKJzD39yIAHbecLJgFaCJX0/BqSkWSutihllUi5iq5YyF
0voJeSJWittUOmqV9MmWTNppe8RpPYnoB5eYqz1AvP+0ZyHc3Xn9yHFWjRvmeASX
tO+badiCo7W2hjRrD8K8UxL6stUVAmtLUgTb81LD1E9NKDMKlPH+GGOXdWuaBGOx
5YPdaLaNoDYv679c9JSgPqQ9Y6KsgQOQ/H74B+cZyqmjzB1/uPfVElhyI2q9tz/g
AsVNNGIoe0VOx4cY4veegMleHBFlTGKhi0gMQZF1RwbGs1e5BSRLuQENBFi69+MB
CADWwFXTvotgQn18YVUj0D59i4jJxaI3r2wbEinsz0hzEvN3wjCyRVuuVS67LZuW
eX18hKgO74hAdiiFq1nKfgDsRSywyjcyIVLP97AvUQYFqDZ4aNNWQBrXEyTTPlqz
3ujbXVPJntAArrkpTdl8i+oVmAruz/cSqX2YRmXWm/GWNRU0M3ndrNT6qCi/+1yQ
L2sYeYKG89ujQRbX/H0xFmGDgHEuPgLrc9CrpbcF24FivLW/Sh3Ux6VkORKG9Cku
h878xX8+CMfRgg4rSdFAmnpUeb2n6w03EHJsQKUwlaxYmUZoOIeU95i7QTvHfLnx
8c5fniAEH2CEy+4hP9BHdcfbABEBAAGJAR8EGAEKAAkFAli69+MCGwwACgkQrOZe
FrrVSopDOAf/dbSB9A+o3JD2UEJmO2E8RuxXFIYSH5/veH0yLOLGSnfg3OHcwAaZ
EvxDmCIetr1EvZQoJV2w63l4szm554cIRN0E9quISvqgKMMaE82OUFAhoOBClQEp
46RxmUWP+FTiqIxUdIdVsmOJ8LRYk+NobG9JOFUMQtTxUSiE9AE1TWFUu2/ZdKA2
BAP4d5fxuGlCGwXxJg42p9cw5xCcC8GTmvqemO8iVlkLqRrihMjHYW3R9DBOVUAe
3ZdBR0McqR6ZhvSARWBQG80VjlvMt8/7SzDdeeXgBlCo0US81hAa+Yxmk8yiyEYC
cOv3QJcgi02rfOMbtFrfHqeEPITCru9nZg==
=Aszm
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2048,
                'uid' => 'Ping Fu <ping@passbolt.com>',
                'key_id' => 'BAD54A8A',
                'fingerprint' => 'AABF40FA29BA54073E8BB956ACE65E16BAD54A8A',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2017-03-04 17:22:43',
                'deleted' => false,
                'created' => '2019-07-02 18:51:45',
                'modified' => '2019-07-02 18:51:45'
            ],
            [
                'id' => 'e152cd5e-f4fb-593c-aebc-9b06a434cb39',
                'user_id' => 'e7fa0375-61df-5dbc-9e42-e0d363bd0ecf',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFdFk8gBCADsd3uX1zydSNTc3V1G74qXiMTrDi30X4O5SBaFM/UUG7mQcu34
HHqWt6I1YeH87Z7Dxq3hetvFbaWR8ZfvlPAruXIzFHzJEZzlCLU5YnRBFM5gvGkJ
VbeKSKTOr+8oVeXnio+Z44T+UtBxXS+2G7wLYYTNd2UaM2WsiJaATeFiD9jZq22h
TakgoOWKIpUrg4eKD7K8Iu+qm2l4btm7QLRyCxhP4x+d1MtwcXArmMXenUvnr/2a
6MDwfddmIcf23I216sJmNjEDWgWHjHoQibs3tx2pBKzY/GI0y0NzhPZCvTdbNqTZ
XAsOfDHm9OuziuNRCgIrx6WtekGB+iZkon6bABEBAAG0IE5hbmN5IE5hbmN5IDxu
YW5jeUBwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJXRZPIAhsDBQsJCAcDBRUKCQgL
BRYCAwEAAh4BAheAAAoJEA7FR62ef6FSlucIAJzL2Jzzka2FWpSPpC0ijp2uhugZ
GkUpbFqBTx9twO992/BvAJeyU6Kfcss0obqNZP1c5sLl2RMvc35WtpBvjkedSUEQ
poET0FGhNUi2ZyqiU8bdbbm8EnDuLv0GG06x2ZSuQ4AXE+JKn8H4saFnPmX2h8a+
YfhKbtS01msQmMviVaF1ZpxTugTqgFYU6wp39O7IR6DMmeKgSwElW8Gd/gJMWx8H
7pizEq5SQCFzR0soBpIOHg2QMDAIWS91zAHdQQoiaI5wAlAm4IeIIY90KaurRQC0
sTziZcdrp0m65FGxZ6790nGhhqLc7u8N69CcxKNkipong4yKkFqyIErFv7e5AQ0E
V0WTyAEIANdrlla22hZ1rg8wFPgeXKJ06JJRS8SRt9WUXLAuIuO1Xqi5uRKXpril
gcC6t6QuipRg0ypwICcBEI2Iem9hgksKG82GVz0LSWSENAF35EG0eE8fFtssBiop
avvLKjiJa+tm4hqDYPHP1SvCf3Wl1PKNTjwuXwH2gX4bOpGF86rWUIriIgmfVVsH
Fy/A0pQoynuYwKr+C+i4CtmZFl0F/WTahVhVTi+KqxMU4z8a90b4gG52ZWGQ0LAS
OahnyHXnicPQQBm80KdC1Exw6Z9QYMXj+ZMWSUvmKb/58S0PBXRapN2KDyhHovvd
ZGHazOFTLnwZvyCmsReb8SfpyE0dtC0AEQEAAYkBHwQYAQoACQUCV0WTyAIbDAAK
CRAOxUetnn+hUqjUB/9EqjSSK789BAFVnY5fymq/vMnCBkE4U/wEVp3/4/e7c6Vf
dyLmT0ULORfOVOyRPZCaohs3+2mUZJcySRHrK0SCI33H7dWXTAj8wYTE+neznZrW
hI/7COYcdEzRdYmFEe1qRJvmXWiSW6C6TjARZTdF7ScBzGhzRUmcyr7h9KcqJ2N/
bSYDlRHigAbDq57S1aa+cN92RHyAvQkJ8S1TBF3/uqSFz41hY4GekegcEg9h5ATl
X9fv56KCnrIdY4NZx8iYqEmjMOZ/FRz9fpwxWapqn+V7eQl7mpSybNEt3gevuTmc
/RFZsK5btBeTdj8jzgg83/jvsasJnTLgAoOdSv2j
=1k/p
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 2048,
                'uid' => 'Nancy Nancy <nancy@passbolt.com>',
                'key_id' => '9E7FA152',
                'fingerprint' => '459B102D43F683E7EFC523610EC547AD9E7FA152',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2016-05-25 12:00:08',
                'deleted' => false,
                'created' => '2019-07-02 18:51:45',
                'modified' => '2019-07-02 18:51:45'
            ],
            [
                'id' => 'e290ed74-a470-5903-8e80-ee25c16fe47f',
                'user_id' => '1ebc0060-9274-5451-aa12-ad0f31bc29dd',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

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
=d1r3
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Edith Clarke <edith@passbolt.com>',
                'key_id' => '9E67396C',
                'fingerprint' => 'D5FDE007B7B4B9816ECE25F61D67BAA69E67396C',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-07-03 10:47:27',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'e6ed0cb7-b31d-5b82-85fa-41d74abb6cd0',
                'user_id' => '610b4c1c-3c08-5451-a163-5b2adba8a5cd',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
eUNE1fxScsE6UBlfdvKJapc=
=s/2R
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 4096,
                'uid' => 'Sofia Kovalevskaya <sofia@passbolt.com>',
                'key_id' => '08D8B763',
                'fingerprint' => '252B91CB28A96C6D67E8FC139020576F08D8B763',
                'type' => 'RSA',
                'expires' => '2020-05-27 09:34:04',
                'key_created' => '2016-05-27 09:34:04',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'f0df9afb-2f0a-5273-aa1e-1f625f2920a0',
                'user_id' => '54c6278e-f824-5fda-91ff-3e946b18d994',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

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
UXjj0w==
=ikRL
-----END PGP PUBLIC KEY BLOCK-----',
                'bits' => 4096,
                'uid' => 'Dame \'Steve\' Shirley <dame@passbolt.com>',
                'key_id' => '959CF1D0',
                'fingerprint' => '03E6535C52AFD7544C555829DD8E26DB959CF1D0',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2015-07-02 11:58:41',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'f2b76b3a-892a-5b4d-9b27-2c051d4d6f4a',
                'user_id' => '5302c3cb-5d33-53b1-82cd-57df36e13acc',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFi6+NoBCADDDLnrYnzqJJCCPRtsMy27QNnb13eD69xv15D5vHWzXjl0HoNb
FpxRQ6dBIsfkaxfmEPb8wC8ExGd4/IDB+69wMOy8x387kLEBDHFlwzC9ocRlLBKc
M7Fny8vLluh8ybrzuvqYreJUdBhzQPyYtVlOniU6P+jxnGOItp5Kcuv7fEMazVBo
oZj84ddo3yUuzq2Dc1t+lX6buJ3gMMalAMJSBjuyRabGy1EGod5o33FOtVdncs4D
x1xxAllB85a8voyfju30Xd8fhzGvfSg089D5Zwn6Ceb/WT6wZGdYbQh1pQTQZ2Ja
Lu3FQzpVh9QHLUmgV9llWZkyo7YArUlzGjl5ABEBAAG0I1Vyc3VsYSBNYXJ0aW4g
PHVyc3VsYUBwYXNzYm9sdC5jb20+iQE3BBMBCgAhBQJYuvjaAhsDBQsJCAcDBRUK
CQgLBRYCAwEAAh4BAheAAAoJEEA+DkoSiUh0lDsH/jpRKnBrNjy0U/c4zoM71S+x
icFdZ5YONLhN+/vNVEaVhx2w5FZYLUufgR2F2SwsvZ8vmCztPQyArUpSz0QbhTXb
PdTwMdtjJt1xqOgo9O07l904KMSDnpaWAfTp0LjMPOyVAdcM1+NVG6j8TC5547Vh
vlB/WSoge0+dhVyzmkDO4gQA6CPQiJfzuiItYkuX0FWUU7MrkOjL72zUot1uHuZH
GNGBYDtLcONzwauMdfblcZYH8YMW/IXCX0EEY6U8gRC8af0ruanWeY25W0/sJ/H7
MHTQ5/2rAOhRLQb7WiK/nNjGvLwwUiRNwzVZfXWRBeX7hgSvJFZb1QaIHQUCINW5
AQ0EWLr42gEIAMILiYSI8UVcU8LTuRSi2l7ZIMVymTAF1RBcMnaVW4rDIdY/FyNb
4ZoYmcxP9fkbNAedqmrloNJx0/ZnT6CYl1Fu7KQRM8kuunuD7xi3/0Xwp3OMMAns
mUUHkY4GKMiDeZ1+1QGVMN8ACRJioAmK0aTHSdFhx0jOfU+HDwG7/Hj8qo8HmTiE
sZ6SWi8wp2kdItdc+pEt4JO1nxwfHxXdBVgPJaxkWuCyZoEeuaoLz2N8YWbLTTy7
ElHcmcHAXC3noRd0p80Nvb+109H8lwv3pFkzFcrqsdM2y6+dYjtI/9SCDtBSZghX
CelFyDJ0msVfujGBoa1HU8RsivAR64sMBlcAEQEAAYkBHwQYAQoACQUCWLr42gIb
DAAKCRBAPg5KEolIdMSqCADDBO/i8DiNbsKtJwHM9y7dXDPpb8W/Hu3S4EIx68V7
snoUn6E9S/sOdqubq5kuFk+S/a1sE84iq8T/kDBT8i3d2e6LGXcYdxQXRcUEKawI
f8VYyA4J8UKXPLLo1J5A1MN/xc8Duuw7mdLDnbXEjjSFG7G7BBYNC7UBHmdDxOK2
+yt46o8ogqI59iREelIiVg+n1gU1mnhbFIdrhonv1bjr7Fu8nGz/iLynAMlxV4TF
Om211avkc+sUYjZF8k3zWGsNOEz3Cng3kaUFRZ0rmqVRZTRcAJuU1k7M9eo6I2j4
C1bPERQcYXABssLYtGmFVdoC3DLBcCY+tjrJRz7DXtpo
=Z4Om
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2048,
                'uid' => 'Ursula Martin <ursula@passbolt.com>',
                'key_id' => '12894874',
                'fingerprint' => 'DD6A88103741A623F8AB8F43403E0E4A12894874',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2017-03-04 17:26:50',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
            [
                'id' => 'fc8d6501-9391-5cd4-ad17-f46df3443d6f',
                'user_id' => '6aabffc9-f788-58f8-9bc9-f4c102ad2f53',
                'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

mQENBFwOJQQBCADRAFkj8r4nB2XBDkZjIZC5Bom5U6Rf914guuXul8Qvr62RDpdK
FFPEKurVEzPikI7ZFg6n3fAPsvPl90WJIvXlUX9tXJaPY/OO3A0AepTkglAYpgKT
PIEDmmDneYtt7lyRKYmrfw1jYKllA6+MUVEvy+bLj2y93eHPCq/DKnk0K6ce84Kg
TuWL9aU8p8ylz2EYi5RNTqhvAUuaEen3kurVrcKka9jGY765UzCVdRtW2iX0MWK/
muLThWgis8jD7P6Ojms/X12zU2wjC+TSyrhHg379UC2xsyyr/RCzBBIJ+jPS+Q4D
Lv+qyVBPAQNKpzbhlmboacQ/2Jfu9Q62F5XZABEBAAG0J0Fub255bW91cyBVc2Vy
IDxhbm9ueW1vdXNAcGFzc2JvbHQuY29tPokBTgQTAQoAOBYhBHSIv+86HnHDbqfH
zaivjPebwNB2BQJcDiUEAhsDBQsJCAcCBhUKCQgLAgQWAgMBAh4BAheAAAoJEKiv
jPebwNB2kDoIAJBAdjosPI49MAEPdHl8KWZ9CjRqpycxkr72mw8bnSMwj/+NtveL
HhPMIq9HDhljefMCEZuEPv5zCPlHiq5Tsci+qZaUg59P5jELeSF9coIhE4vf/0qt
TZuokpxHnbWTpEW6dgYOqC08mHVAC8eaeirnVQKhw8iBWqLrEJDUAPvhNDsjq9xd
V7gkMQH0CTMcvm34efUIzF7LgqCoY5y0QH9LYAnQtiBeMjc74wH6lRVboCVkpAxw
PvNhdM+a+6qq5s2IhJUbOBx+NqgTz2Y9eGSzg7YpWFSscpWKLVgYF7VmD91dQl1L
v4lX8JcYppcoICiIZgUYwKV5GR8MlaA03sq5AQ0EXA4lBAEIALy4jNGklWVA0CVj
B9jryGGJMIzjxnNMjn9jgLJXkBx3y64/OoTW9gmUSzZYeQgDItz9Kcs5vaR7pHYN
6VossH7v+tAOKh4jjbMjNjFwo28K9wfR3BtHdxdg+yjFTEXkD7EYh8Q1vzSid9V1
k+FvNRY79XIN5IfG1d0O6g0AmHocuq+S7oRUt1nRfbPnolDVGPopBCLExGZevmBL
qSXBYJzh2+QARvhEL8P57sPrUgyBK6GqEInpWyzdnrQDhYrWMmk15ySiAG7V/ic1
JzKaiN0gJmCnxQUO0xLMqMLC0MlihB6HgAiquc0Ia/cTAaojFxm5zcVWwKpj+GUp
V6zWMqUAEQEAAYkBNgQYAQoAIBYhBHSIv+86HnHDbqfHzaivjPebwNB2BQJcDiUE
AhsMAAoJEKivjPebwNB2BKkIALZFZgq3MnbpsXzl1+Vyp40/c5agxjAmndqB3dVx
Yhwo+i5Xqk/DTwWuVu288ZxohXrqUTVDYIeB2k+xAH853ByWuoq0YYTcsZTpHicb
N3NLRcy+Gi/F+4mDfdgWEIhFRiTUyZxb7aCnKcMFr7ibu/KCas1IX3D/eXN725AC
iWptsicD+4JoCsZRNEnlZFWbacsOOnmjdfLlWbGn2a0KkKgsd3JPKMcA8AoKMdJ6
C5KUCIC8mpLTwhvLwQS27k3nBkVAinYBBX1zCfrVYgnBjC35Ho7ftR9mT+RYJmsH
Px4aG63zJWA7DjnzYJS0sAoNJxBrXU3TS530f5b1owOAogg=
=qspo
-----END PGP PUBLIC KEY BLOCK-----
',
                'bits' => 2048,
                'uid' => 'Anonymous User <anonymous@passbolt.com>',
                'key_id' => '9BC0D076',
                'fingerprint' => '7488BFEF3A1E71C36EA7C7CDA8AF8CF79BC0D076',
                'type' => 'RSA',
                'expires' => null,
                'key_created' => '2018-12-10 08:34:12',
                'deleted' => false,
                'created' => '2019-07-02 18:51:44',
                'modified' => '2019-07-02 18:51:44'
            ],
        ];
        parent::init();
    }
}
