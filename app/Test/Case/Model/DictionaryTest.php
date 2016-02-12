<?php
/**
 * Dictionary Model Test
 *
 * @copyright	 (c) 2015-present Bolt Softwares Pvt Ltd
 * @package	   app.Test.Case.Model.DictionaryTest
 * @since		 version 2.12.7
 * @license	   http://www.passbolt.com/license
 */
App::uses('Dictionary', 'Model');

class DictionaryTest extends CakeTestCase {

/**
 * Test Get
 * @return void
 */
	public function testGet() {
		$d = Dictionary::get('totestifonedictionaryisnotthereofcourse');
		$this->assertEquals($d, false, 'Dictionary::get() with non existing dictionary should return false');
		$d = Dictionary::get('en-EN');
		$this->assertEquals(is_array($d), true, 'Dictionary::get() with existing dictionary should return an array');
	}
}
