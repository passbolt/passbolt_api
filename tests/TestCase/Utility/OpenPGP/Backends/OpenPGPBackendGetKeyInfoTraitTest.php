<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.10.0
 */
namespace App\Test\TestCase\Utility\OpenPGP\Backends;

use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Model\GpgkeysModelTrait;
use App\Utility\OpenPGP\Backends\Gnupg;
use App\Utility\OpenPGP\OpenPGPBackend;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Utility\OpenPGP\OpenPGPBackend
 */
class OpenPGPBackendGetKeyInfoTraitTest extends TestCase
{
    use FormatValidationTrait;
    use GpgkeysModelTrait;

    public string|false $originalErrorSettings;

    public function setUp(): void
    {
        parent::setUp();
        $this->originalErrorSettings = ini_get('error_reporting');
    }

    public function tearDown(): void
    {
        $settings = ini_get('error_reporting');
        if ($settings != $this->originalErrorSettings) {
            ini_set('error_reporting', $this->originalErrorSettings);
        }
        parent::tearDown();
    }

    public static function openPGPBackendProvider(): array
    {
        return [
            [new Gnupg()],
        ];
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendGetKeyInfoTrait_ExpiredKeyButNotReally(OpenPGPBackend $gnupg): void
    {
        // This key has multiple signature one old one that says the key expires
        // and another one more recent that says the key doesn't expire
        $expiredKeyFile = FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'expired_notexpired_public.key';
        $armored = file_get_contents($expiredKeyFile);
        $keyInfo = $gnupg->getKeyInfo($armored);

        $this->assertNull($keyInfo['expires']);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendGetKeyInfoTrait_ExpiredKeySubKeyIsntAnymore(OpenPGPBackend $gnupg): void
    {
        $expiredKeyFile = FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'expired_but_subkey_isnt_public.key';
        $armored = file_get_contents($expiredKeyFile);
        $keyInfo = $gnupg->getKeyInfo($armored);

        $this->assertSame($keyInfo['expires'], 1724323989);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendGetKeyInfoTrait_ExpiredKey(OpenPGPBackend $gnupg): void
    {
        $expiredKeyFile = FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_expired_public.key';
        $armored = file_get_contents($expiredKeyFile);
        $keyInfo = $gnupg->getKeyInfo($armored);

        $this->assertSame($keyInfo['expires'], 1644162951);
    }
}
