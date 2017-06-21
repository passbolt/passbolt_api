<?php
/**
 * v1.6.0 Migration script
 * see. https://www.passbolt.com/release/notes#v1.6.0
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Migration_1_6_0 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'Migration_1_6_0';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = [
		'up' => []
	];

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
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		$Secret = ClassRegistry::init('Secret');
		$Resource = ClassRegistry::init('Resource');

		/**
		 * Delete all the secrets that should not be stored anymore in db.
		 * By instance, the secrets that haven't been deleted after an unshare operation, see PASSBOLT-1959.
		 */
		$secrets = $Secret->find('all');
		foreach ($secrets as $secret) {
			if(!$Resource->isAuthorized($secret['Secret']['resource_id'], PermissionType::READ, $secret['Secret']['user_id'])) {
				$Secret->delete($secret['Secret']['id']);
			}
		}

		return true;
	}
}
