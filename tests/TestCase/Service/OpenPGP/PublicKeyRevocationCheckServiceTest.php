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

use App\Service\OpenPGP\PublicKeyRevocationCheckService;
use App\Test\Lib\AppTestCase;

class PublicKeyRevocationCheckServiceTest extends AppTestCase
{
    public function testPublicKeyRevocationCheckService_Check_Success()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key');
        $this->assertTrue(PublicKeyRevocationCheckService::check($armoredKey));
    }

    public function testPublicKeyRevocationCheckService_Check_ErrorNotRevoked()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $this->assertFalse(PublicKeyRevocationCheckService::check($armoredKey));
    }

    public function testPublicKeyRevocationCheckService_Check_ErrorRevokedSigOnly()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'revoked_sig_public.key');
        $this->assertFalse(PublicKeyRevocationCheckService::check($armoredKey));
    }

    public function testPublicKeyRevocationCheckService_Check_SuccessECC()
    {
        // See @TODO crypto check not implemented for non RSA keys
        $this->markTestIncomplete();
    }
}
