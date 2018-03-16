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
namespace Passbolt\Tags\Test\TestCase\Controller;

use Cake\Utility\Hash;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class TagIndexControllerTest extends TagPluginIntegrationTestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/roles', 'app.Base/resources', 'app.Base/groups',
        'app.Alt0/groups_users', 'app.Alt0/permissions',
        'plugin.passbolt/tags.Base/tags', 'plugin.passbolt/tags.Alt0/resourcesTags'];

    // A user not logged in should not be able to see tags
    public function testTagIndexNotLoggedIn()
    {
        $this->getJson('/tags.json?api-version=v2');
        $this->assertResponseError();
        $response = json_decode($this->_getBodyAsString());
        $this->assertTextContains('error', $response->header->status);
        $this->assertTextContains('You need to login to access this location.', $response->header->message);
    }

    // A user should see personal and shared tags or resources via direct and group permissions
    public function testTagIndexSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/tags.json?api-version=v2');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $expected = ['alpha', '#echo', '#bravo', 'fox-trot', '#charlie', 'hotel', '#golf', 'firefox'];
        foreach ($expected as $result) {
            $this->assertTrue(in_array($result, $results));
        }
    }

    // A user should not see other users personal tags or shared tags of resource they don't have access to
    public function testTagIndexSuccessDoubleCheck()
    {
        $this->authenticateAs('betty');
        $this->getJson('/tags.json?api-version=v2');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $notExpected = ['fox-trot', '#echo'];
        $expected = ['alpha', '#bravo'];
        foreach ($expected as $result) {
            $this->assertTrue(in_array($result, $results));
        }
        foreach ($notExpected as $result) {
            $this->assertFalse(in_array($result, $results));
        }
    }
}
