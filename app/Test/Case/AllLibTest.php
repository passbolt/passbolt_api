<?php
/**
 * All Passbolt libs Test
 *
 * @copyright (c) 2017 Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class AllLibTest extends CakeTestSuite {

	public static function suite() {
		$suite = new CakeTestSuite('All libs tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Lib');
		return $suite;
	}
}
