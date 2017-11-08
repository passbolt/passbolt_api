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
namespace App\Test\TestCase\Controller\Setup;

use App\Utility\UuidFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\TableRegistry;

class SetupCompleteControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.users', 'app.profiles', 'app.gpgkeys', 'app.roles', 'app.authentication_tokens'];
    public $AuthenticationTokens;

    public function setUp()
    {
        $this->AuthenticationTokens = TableRegistry::get('AuthenticationTokens');
        parent::setUp();
    }

    public function testSetupCompleteSuccess()
    {
        $t = $this->AuthenticationTokens->find()
            ->where(['id' => UuidFactory::uuid('token.id.ruth')])
            ->first();
        $url = '/setup/complete/' . UuidFactory::uuid('user.id.ruth') . '.json';
        $armoredKey = file_get_contents(ROOT . '/plugins/PassboltTestData/config/gpg/ruth_public.key');
        $data = [
            'AuthenticationToken' => [
                'token' => $t->token
            ],
            'Gpgkey' => [
                'armored_key' => $armoredKey
            ]
        ];
//        $dataJson = json_encode($data);
        $this->postJson($url, $data);
        $this->assertSuccess();
    }
}
