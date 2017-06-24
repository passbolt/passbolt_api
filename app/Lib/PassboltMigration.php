<?php
/**
 * Passbolt Migration Component
 *
 * This is the top class for migration scripts.
 *
 * @copyright   2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('CakeMigration', 'Migrations.Lib');
App::uses('AnonymousStatistic', 'Model');

/**
 * Base Class for Passbolt Migration management
 */
class PassboltMigration extends CakeMigration {

	public function before($direction) {
		// Do nothing.
		return true;
	}

	public function after($direction) {
		AnonymousStatistic::reloadConfigFile();
		if (Configure::read('AnonymousStatistics.send') === true) {
			$AnonymousStatistic = Common::getModel('AnonymousStatistic');
			$AnonymousStatistic->send(AnonymousStatistic::CONTEXT_INSTALL);
		}
		return true;
	}
}
