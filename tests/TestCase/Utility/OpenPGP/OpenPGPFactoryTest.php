<?php
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
namespace App\Test\TestCase\Utility\OpenPGP;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;

class OpenPGPFactoryTest extends TestCase
{
    public function testOpenPGPFactoryGetError()
    {
        OpenPGPBackendFactory::reset();
        $this->expectException(InternalErrorException::class);
        Configure::write('passbolt.gpg.backend', 'nope');
        OpenPGPBackendFactory::get();
    }

    public function testOpenPGPFactoryGetSuccess()
    {
        OpenPGPBackendFactory::reset();
        Configure::write('passbolt.gpg.backend', OpenPGPBackendFactory::GNUPG);
        $gpg = OpenPGPBackendFactory::get();
        Configure::write('passbolt.gpg.backend', OpenPGPBackendFactory::HTTP);
        $this->assertNotEmpty($gpg);
        $this->assertEquals($gpg, OpenPGPBackendFactory::get());
    }

    public function testOpenPGPFactoryCreateError()
    {
        $this->expectException(InternalErrorException::class);
        OpenPGPBackendFactory::create('error');
    }
}
