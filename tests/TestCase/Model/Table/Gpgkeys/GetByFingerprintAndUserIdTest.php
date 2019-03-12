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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Gpgkeys;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\GpgkeysModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class GetByFingerprintAndUserIdTest extends AppTestCase
{
    use GpgkeysModelTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Gpgkeys'];
    public $fingerprint;
    public $Gpgkeys;

    public function setUp()
    {
        parent::setUp();
        $this->Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');
        $this->fingerprint = '03F60E958F4CB29723ACDF761353B5B15D9B054F'; // ada's key fingerprint
    }

    public function testGetByFingerPrintAndUserIdInvalidUserId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->Gpgkeys->getByFingerPrintAndUserId($this->fingerprint, 'nope');
    }

    public function testGetByFingerPrintAndUserIdInvalidFingerprint()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->Gpgkeys->getByFingerPrintAndUserId('nope', UuidFactory::uuid('user.id.ada'));
    }

    public function testGetByFingerPrintAndUserIdWrongUser()
    {
        $k = $this->Gpgkeys->getByFingerPrintAndUserId(
            $this->fingerprint,
            UuidFactory::uuid('user.id.betty')
        );
        $this->assertEmpty($k);
    }

    public function testGetByFingerPrintAndUserIdWrongFingerprint()
    {
        $k = $this->Gpgkeys->getByFingerPrintAndUserId(
            '03F60E958F4CB29723ACDF761353B5B15D9B054C',
            UuidFactory::uuid('user.id.ada')
        );
        $this->assertEmpty($k);
    }

    public function testGetByFingerPrintAndUserIdSuccess()
    {
        $k = $this->Gpgkeys->getByFingerPrintAndUserId($this->fingerprint, UuidFactory::uuid('user.id.ada'));
        $this->assertNotEmpty($k);
        $this->assertGpgkeyAttributes($k);
    }
}
