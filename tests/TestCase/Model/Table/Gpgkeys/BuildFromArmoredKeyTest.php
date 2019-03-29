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

use App\Error\Exception\ValidationException;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\GpgkeysModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class BuildFromArmoredKeyTest extends AppTestCase
{
    use GpgkeysModelTrait;

    public $Gpgkeys;

    public $fixtures = ['app.Base/Users', 'app.Base/Gpgkeys'];

    public function setUp()
    {
        parent::setUp();
        $this->Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');
    }

    public function tearDown()
    {
        unset($this->Gpgkeys);
        parent::tearDown();
    }

    public function testbuildEntityFromArmoredKeyWrongUserId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->Gpgkeys->buildEntityFromArmoredKey('nope', 'nope');
    }

    public function testbuildEntityFromArmoredKeyWrongKey()
    {
        $this->expectException(ValidationException::class);
        $this->Gpgkeys->buildEntityFromArmoredKey('nope', UuidFactory::uuid('user.id.ada'));
    }

    public function testbuildEntityFromArmoredKeySuccess()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');
        $k = $this->Gpgkeys->buildEntityFromArmoredKey($armoredKey, UuidFactory::uuid('user.id.ada'));
        $this->assertNotEmpty($k);
        $attributes = [
            // id, user_id, created, modified are not present yet, will be added on save
            'armored_key', 'bits', 'uid', 'key_id',
            'fingerprint', 'type', 'expires', 'key_created', 'deleted',
        ];
        $this->assertObjectHasAttributes($attributes, $k);
    }

    public function testbuildEntityFromArmoredKeyInvalidKeyError()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');

        // mess up the key a little bit
        $armoredKey = str_replace('0', 'F', $armoredKey);
        $armoredKey = str_replace('F', '0', $armoredKey);
        $armoredKey = str_replace('A', '1', $armoredKey);

        $this->expectException(ValidationException::class);
        $k = $this->Gpgkeys->buildEntityFromArmoredKey($armoredKey, UuidFactory::uuid('user.id.ada'));
    }
}
