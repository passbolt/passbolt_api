<?php
/**
 * All Passbolt Model Test
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package       app.Test.Case.AllTest
 * @since         version 2.12.7
 */
class AllTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Console' . DS . 'Command');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Console' . DS . 'Command' . DS . 'Task');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Component');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model' . DS . 'Behavior');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model' . DS . 'Utility');
		return $suite;
	}
}
