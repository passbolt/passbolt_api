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
 * @since         4.10.0
 */

namespace App\Test\TestCase\Model\Table\Gpgkeys;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Core\Exception\CakeException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;

class GpgKeysTableFindTest extends AppTestCase
{
    /**
     * @var GpgkeysTable|null
     */
    private $Gpgkeys = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');
    }

    public function tearDown(): void
    {
        unset($this->Gpgkeys);
        parent::tearDown();
    }

    public function testGpgKeysTableFindCurrent_Success(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $key1 = GpgkeyFactory::make()->withAdaKey()->setField('user_id', $user->get('id'))->persist();
        GpgkeyFactory::make()->withBettyKey()->deleted()->setField('user_id', $user->get('id'))->persist();

        /** @var Gpgkey $key3 */
        $key3 = $this->Gpgkeys->find('current', ['user_id' => $user->get('id')])->first();
        $this->assertEquals($key1->id, $key3->id);
    }

    public function testGpgKeysTableFindCurrent_Error_NoUserId(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        GpgkeyFactory::make()->withAdaKey()->setField('user_id', $user->get('id'))->persist();

        $this->expectException(CakeException::class);
        $this->Gpgkeys->find('current', ['id' => $user->get('id')])->first();
    }

    public function testGpgKeysTableFindCurrent_Error_NoKeys(): void
    {
        $user = UserFactory::make()->user()->active()->persist();

        $this->expectException(RecordNotFoundException::class);
        $this->Gpgkeys->find('current', ['user_id' => $user->get('id')])->firstOrFail();
    }
}
