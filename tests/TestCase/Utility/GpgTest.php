<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Test\TestCase\Utility;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\Gpg;
use Cake\Core\Configure;
use PassboltTestData\Shell\Task\Base\GpgkeysDataTask;

class GpgTest extends AppIntegrationTestCase
{

    public function setUp()
    {
        $this->gpg = new Gpg();
        parent::setUp();
    }

    protected function getDummySignedMessage($userAlias = '')
    {
        return '-----BEGIN PGP SIGNED MESSAGE-----
Hash: SHA512

Signed message
-----BEGIN PGP SIGNATURE-----

iQJFBAEBCgAvFiEEA/YOlY9MspcjrN92E1O1sV2bBU8FAlqzfJARHGFkYUBwYXNz
Ym9sdC5jb20ACgkQE1O1sV2bBU+yIQ/9FYgjgvbwag9Cxyv4y0wQMOFGC21v8raE
LqT2mH8g7mYt/4n2qQKslMZCKjwraUwPMPyRiAEyt52aWfjh9fIfwvczV3TerqoN
0vtDCv65UY7SNItIuGYFDBrYl9d1a92I1LO3p6bD1mS0FXT1Zg7VPKBtmZHY3Iqr
pRlhtkssgWYtvOsnnO9qnuyH8xXeYbzRO2oDuQrsHnHqQXs+J6Aha7b2W1VqsdQm
jnUwl9Unxb7d2eEOO8Y2w9jV86V88u6qDGpGeDCOXu4M/FZqVbOZyH0PQztQyOH8
SCqW/Q5wGxey42dKOmxHEmroly8ljkd1pMOdAsYU4+8Zjog6h7BmiVQUYKQj+V63
/RnXGH5bCExKmsA7VMEbEruI+6lVIw19iuXikr6s+nwr4m2tmZYro2RMqxBqw+ZH
1wLexpnJ5y5qhKB7b5Nhg6UCIJeUNiFz1yE4C3B9qiO8lmhoNoa2+bPATI/PbKZq
fXMCQ9cC88YoVX6SLv9uV+oErfZ/vp2d59JiUz3/PHNKKr4wG/BDQsa37WLrcAs3
gsv1OnsWRlfCzm417Nvg0mZ+uqTM3lC8B1T9zd6vTaVHyX0xs6qjDNhVuGncFUGW
19OfL7XtvDaK4aR/fMaAM6Vz+cxeFOJEGBGFNJkeU18jIE1EwsmcLt5q7+n+j9Mq
0wIBq1JnEVs=
=l/7T
-----END PGP SIGNATURE-----';
    }

    public function testIsParsableArmoredSignedMessageRuleSuccess()
    {
        $armoredSignedMessage = $this->getDummySignedMessage('betty');
        $result = $this->gpg->isParsableArmoredSignedMessageRule($armoredSignedMessage);
        $this->assertTrue($result);
    }

    public function testIsParsableArmoredSignedMessageRuleError()
    {
        $armoredSignedMessages = [
            'empty message' => '',
            'no gpg signed mark' => '---- invalid format ----',
        ];

        foreach ($armoredSignedMessages as $message) {
            $result = $this->gpg->isParsableArmoredSignedMessageRule($message);
            $this->assertFalse($result);
        }
    }

    public function testVerifySuccess()
    {
        $armoredSignedMessage = $this->getDummySignedMessage('betty');
        $armoredKey = file_get_contents(PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'ada_public.key');
        $fingerprint = $this->gpg->importKeyIntoKeyring($armoredKey);
        $message = null;
        $this->gpg->verify($armoredSignedMessage, $fingerprint, $message);
        $this->assertRegExp('/^Signed message/', $message);
    }

    public function testVerifyError()
    {
        $armoredSignedMessage = $this->getDummySignedMessage('betty');
        $armoredKey = file_get_contents(PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'betty_public.key');
        $fingerprint = $this->gpg->importKeyIntoKeyring($armoredKey);
        try {
            $this->gpg->verify($armoredSignedMessage, $fingerprint);
        } catch (\Exception $e) {
            $this->assertEquals('The message cannot be verified.', $e->getMessage());
        }
    }
}
