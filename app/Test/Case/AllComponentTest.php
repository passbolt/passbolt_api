<?php
/**
 * All Passbolt Components Tests
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package       app.Test.Case.AllControllerTest
 * @since         version 2.12.7
 */
class AllComponentTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All components tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller' . DS . 'Component');
		return $suite;
	}
}
