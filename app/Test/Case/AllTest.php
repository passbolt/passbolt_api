<?php
/**
 * All Passbolt Model Test
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 * 				  2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class AllTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Console' . DS . 'Command');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Console' . DS . 'Command' . DS . 'Task');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Component');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Groups');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Permissions');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Resources');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Users');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Setup');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Share');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model' . DS . 'Behavior');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model' . DS . 'User');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model' . DS . 'Utility');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Lib');
		return $suite;
	}
}
