<?php
/**
 * All Passbolt Behavior Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package       app.Test.Case.AllBehaviorsTest
 * @since         version 1.13.3
 */
class AllBehaviorsTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All behaviors tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model' . DS . 'Behavior');
		return $suite;
	}
}
