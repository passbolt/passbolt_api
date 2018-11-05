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

namespace App\Test\TestCase\Model\Table\Users;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;

    public $Users;

    public $fixtures = ['app.Base/users', 'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/roles', 'app.Base/groups', 'app.Base/groups_users', 'app.Base/resources', 'app.Base/permissions'];

    protected function getEntityDefaultOptions()
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                'username' => true,
                'role_id' => true,
                'deleted' => true,
                'active' => true,
                'profile' => true
            ]
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->Users = TableRegistry::get('Users');
    }

    public function testUsersSaveCreateSuccess()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testUsersSaveUpdateSuccess()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testUsersSaveValidationEmailError()
    {
        Configure::write('passbolt.email.validate.mx', true);
        $user = self::getDummyUser();
        $testCases = [
            'email' => self::getEmailTestCases(true)
        ];
        $this->assertFieldFormatValidation($this->Users, 'username', $user, self::getEntityDefaultOptions(), $testCases);
    }

    public function testUsersSaveValidationEmailNoMxError()
    {
        Configure::write('passbolt.email.validate.mx', false);
        $user = self::getDummyUser();
        $testCases = [
            'email' => self::getEmailTestCases(false)
        ];
        $this->assertFieldFormatValidation($this->Users, 'username', $user, self::getEntityDefaultOptions(), $testCases);
    }

    public function testSaveCheckRulesError()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
