<?php
/**
 * All Passbolt Controllers Tests
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 * 				  2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class AllControllersTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All controllers tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Groups');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Permissions');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Resources');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Users');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Setup');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Share');
		return $suite;
	}
}
