<?php
/**
 * v1.5.0 Migration script
 * see. https://www.passbolt.com/release/notes#v1.5.0
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Migration_1_5_0 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'Migration_1_5_0';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = [
		'up' => [
			'alter_field' => [
				'resources' => [
					'username' => ['null' => true],
					'uri' => ['null' => true],
				]
			],
			'create_field' => [
				'groups_users' => [
					'is_admin' => ['type' => 'boolean', 'null' => false, 'default' => '0']
				]
			]
		]
	];

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		if ($direction == 'up') {
			$GroupUser = ClassRegistry::init('GroupUser');
			$fields = $GroupUser->getColumnTypes();
			if (isset($fields['is_admin'])) {
				$this->migration = ['up' => []];
			}
		}
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		if ($direction === 'up') {
			require_once( APP . 'Config/Schema/permissions.php');
			$Permission = new PermissionsSchema();
			$Permission->init();
		}
		return true;
	}
}
