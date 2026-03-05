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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Gpgkeys;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\GpgkeysModelTrait;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;

class GetByFingerprintAndUserIdTest extends AppTestCase
{
    use GpgkeysModelTrait;

    public $fingerprint;
    public $Gpgkeys;

    public function setUp(): void
    {
        parent::setUp();
        $this->Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');
        $this->fingerprint = '03F60E958F4CB29723ACDF761353B5B15D9B054F'; // ada's key fingerprint
    }

    public function testGetByFingerPrintAndUserIdInvalidUserId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->Gpgkeys->getByFingerPrintAndUserId($this->fingerprint, 'nope');
    }

    public function testGetByFingerPrintAndUserIdInvalidFingerprint()
    {
        $user = UserFactory::make()->persist();

        $this->expectException(InvalidArgumentException::class);
        $this->Gpgkeys->getByFingerPrintAndUserId('nope', $user->id);
    }

    public function testGetByFingerPrintAndUserIdWrongUser()
    {
        [$ada, $betty] = UserFactory::make(2)->persist();
        GpgkeyFactory::make()->withAdaKey()->setField('user_id', $ada->id)->persist();

        $k = $this->Gpgkeys->getByFingerPrintAndUserId(
            $this->fingerprint,
            $betty->id
        );
        $this->assertEmpty($k);
    }

    public function testGetByFingerPrintAndUserIdWrongFingerprint()
    {
        $ada = UserFactory::make()->persist();
        GpgkeyFactory::make()->withAdaKey()->setField('user_id', $ada->id)->persist();

        $k = $this->Gpgkeys->getByFingerPrintAndUserId(
            '03F60E958F4CB29723ACDF761353B5B15D9B054C',
            $ada->id
        );
        $this->assertEmpty($k);
    }

    public function testGetByFingerPrintAndUserIdSuccess()
    {
        $ada = UserFactory::make()->persist();
        GpgkeyFactory::make()->withAdaKey()->setField('user_id', $ada->id)->persist();

        $k = $this->Gpgkeys->getByFingerPrintAndUserId($this->fingerprint, $ada->id);
        $this->assertNotEmpty($k);
        $this->assertGpgkeyAttributes($k);
    }
}
