<?php
/**
 * v1.2.2 Migration script
 * see. https://www.passbolt.com/release/notes#v1.2.2
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class Migration_1_2_2 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'Migration_1_2_2';

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
			// Get Cake Shell fror prompt.
			$CakeShell = new AppShell();

			// Ask user.
			$input = $CakeShell->in(__d('cake_console', 'Do you want to help make passbolt better by sending anonymous usage statistics ?'), array('y', 'n'), 'y');
			$choice = $input == 'y' ? true : false;

			// Write file.
			$instanceId = Common::uuid();
			$InstanceStatistic = Common::getModel('InstanceStatistic');
			$InstanceStatistic->writeConfigFile($instanceId, $choice);

			// If anonymous usage statistics are activated, send them
			if ($choice == true) {
				$InstanceStatistic->send(InstanceStatistic::CONTEXT_INSTALL);
			}
		}
		return true;
	}
}
