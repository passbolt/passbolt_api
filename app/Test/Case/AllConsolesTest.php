<?php
/**
 * All Passbolt Consoles Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package       app.Test.Case.AllConsolesTest
 * @since         version 1.13.3
 */
class AllConsolesTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All consoles tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Console' . DS . 'Command');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Console' . DS . 'Command' . DS . 'Task');
		return $suite;
	}
}
