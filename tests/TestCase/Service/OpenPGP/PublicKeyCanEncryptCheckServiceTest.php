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
 * @since         3.6.0
 */
namespace App\Test\TestCase\Service\OpenPGP;

use App\Service\OpenPGP\PublicKeyCanEncryptCheckService;
use App\Test\Lib\AppTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;

class PublicKeyCanEncryptCheckServiceTest extends AppTestCase
{
    public function testPublicKeyCanEncryptCheckService_Success()
    {
        // Delete possible revoked key from keying
        OpenPGPBackendFactory::get()->deleteKey('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');

        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $this->assertTrue(PublicKeyCanEncryptCheckService::check($armoredKey, '67BFFCB7B74AF4C85E81AB26508850525CD78BAA'));

        OpenPGPBackendFactory::get()->deleteKey('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
    }

    public function testPublicKeyCanEncryptCheckService_ErrorRevoked()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key');
        $this->assertFalse(PublicKeyCanEncryptCheckService::check($armoredKey, '67BFFCB7B74AF4C85E81AB26508850525CD78BAA'));

        // Delete revoked key from keying
        OpenPGPBackendFactory::get()->deleteKey('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
    }

    public function testPublicKeyCanEncryptCheckService_ErrorExpired()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_expired_public.key');
        $this->assertFalse(PublicKeyCanEncryptCheckService::check($armoredKey, '67BFFCB7B74AF4C85E81AB26508850525CD78BAA'));

        OpenPGPBackendFactory::get()->deleteKey('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
    }

    public function testPublicKeyCanEncryptCheckService_ErrorFuturamaKey()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'fry_public.key');
        $this->assertFalse(PublicKeyCanEncryptCheckService::check($armoredKey, '67BFFCB7B74AF4C85E81AB26508850525CD78BAA'));
    }
}
