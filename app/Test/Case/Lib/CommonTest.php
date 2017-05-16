<?php
/**
 * Common lib Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('Common', 'Lib');

class CommonTest extends CakeTestCase {

    public function setUp($complete=true) {
        parent::setUp();
    }

/**
 * Test get model
 */
	public function testGetModel() {
		$this->assertTrue(get_class(Common::getModel('User')) === 'User');
		$this->assertTrue(get_class(Common::getModel('User')) === 'User');
		$this->assertTrue(get_class(Common::getModel('User',true)) == 'User');
	}

/**
 * Test uuid generation
 */
    public function testUuid() {
        $uuid = Common::Uuid();
        $this->assertTrue(
            (preg_match('/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/', $uuid) == true),
            'Common::uuid() does not return a valid uuid:' . $uuid
        );

        $uuid1 = Common::Uuid('test');
        $uuid2 = Common::Uuid('test');
        $this->assertTrue(($uuid1===$uuid2), 'UUIDs should match');
        $this->assertTrue(
            (preg_match('/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/', $uuid1) == true),
            'Common::uuid() does not return a valid uuid:' . $uuid1
        );
    }

/**
 * Test return value of isCli since this can be run in Cli or not...
 */
	public function testIsCli() {
		$result = Common::isCli();
		$this->assertTrue(is_bool($result));
	}

/**
 * Test UUID validation
 */
    public function testIsUuid() {
		$allowedUuids = [
			'b0d8f275-42dc-45a7-b5f8-b6311d4dcc64',
			Common::uuid('user.id.ada')
		];
		foreach ($allowedUuids as $uuid) {
			$this->assertTrue(Common::isUuid($uuid), 'Uuid should validate: ' . $uuid);
		}
    }

/**
 * Test Timestamp validation
 */
	public function testIsTimestamp() {
		$allowedTimestamps = [
			'-2',
			'1493215961' //  (ISO 8601: 2017-04-26T14:12:41Z)
		];
		foreach($allowedTimestamps as $t) {
			$this->assertTrue(Common::isTimestamp($t), 'Timestamp should be allowed: ' . $t);
		}
		$notAllowedTimestamps = [
			'b0d8f275-42dc-45a7-b5f8-b6311d4dcc64',
			'test',
			''
		];
		foreach($notAllowedTimestamps as $t) {
			$this->assertFalse(Common::isTimestamp($t), 'Timestamp should not be allowed: ' . $t);
		}
	}

/**
 * Test is json url validation
 */
    public function testIsJsonUrl() {
		$baseUrl = 'http://passbolt.dev/';
		$allowedUrls = [
			'groups.json',
			'groups.json?',
			'groups.json?test',
			'groups.json?param=test',
			'groups.json?param[test]=value',
			'groups.json?param[test]=value&',
			'groups.json?param[test]=value&order',
			'groups.json?param[test]=value&order=1'
		];
		foreach($allowedUrls as $url) {
			$this->assertTrue(Common::isJsonUrl($baseUrl . $url), 'Url should be allowed: ' . $url);
		}
		$notallowedUrls = [
			'',
			'?',
			'?test=groups',
			'?test=groups.json'
		];
		foreach($notallowedUrls as $url) {
			$this->assertFalse(Common::isJsonUrl($baseUrl . $url), 'Url should not be allowed: ' . $url);
		}
    }
}