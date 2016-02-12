<?php
/**
 * All Passbolt Controllers Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package       app.Test.Case.AllControllerTest
 * @since         version 2.12.7
 */
class AllControllersTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All controllers tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
		return $suite;
	}
}
