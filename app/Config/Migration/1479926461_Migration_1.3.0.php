<?php
/**
 * v1.3.0 Migration script
 * see. https://www.passbolt.com/release/notes#v1.3.0
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class Migration_1_3_0 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'Migration_1_3_0';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * Will take care of setting the anonymous usage statistics and sending
 * them to the server if user agrees.
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		// Check if anonymous statistic is already configured (this should not happen, but who knows..)
		$isConfigured = !empty($instanceId) && Common::isUuid($instanceId);
		if (!$isConfigured) {
			// Get Cake Shell for prompt.
			$CakeShell = new AppShell();

			// Ask user.
			$input = $CakeShell->in(__d(
				'cake_console',
				__("We need you to help make passbolt better by sending anonymous usage statistics. Ok?\n(see: %s)", Configure::read('AnonymousStatistics.help'))),
				array('y', 'n'), 'n');
			$choice = $input == 'y' ? true : false;

			// Write file.
			$instanceId = Common::uuid();
			$AnonymousStatistic = Common::getModel('AnonymousStatistic');
			$AnonymousStatistic->writeConfigFile($instanceId, $choice);

			// If anonymous usage statistics are activated, send them
			if ($choice == true) {
				$AnonymousStatistic->send(AnonymousStatistic::CONTEXT_UPDATE);
			}
		}
		return true;
	}
}
