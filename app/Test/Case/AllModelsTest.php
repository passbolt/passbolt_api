<?php
/**
 * All Passbolt Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package       app.Test.Case.AllModelTest
 * @since         version 2.12.7
 */
class AllModelsTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All models tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model' . DS . 'User');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model' . DS . 'Behavior');
		return $suite;
	}
}
