<?php
/**
 * v1.4.0 Migration script
 * see. https://www.passbolt.com/release/notes#v1.4.0
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Migration_1_4_0 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'Migration_1_4_0';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_table' => array(
				'categories',
				'categories_resources',
				'category_types',
				'positions',
				'items_tags',
				'tags',
				'addresses',
				'authentication_blacklists',
				'authentication_logs',
				'emails',
				'phone_numbers'
			),
			'drop_field' => array(
				'permissions_types' => array(
					'binary',
					'_admin',
					'_update',
					'_create',
					'_read'
				)
			)
		),
		'down' => array(

		)
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		if ($direction === 'up') {
			$Permission = ClassRegistry::init('Permission');

			// Remove views related to groups and categories.
			$Permission->query('
				DROP VIEW users_resources_permissions;
				DROP VIEW users_categories_permissions;
				DROP VIEW groups_resources_permissions;
				DROP VIEW groups_categories_permissions;
				DROP VIEW categories_parents;
			');

			// Remove permissions types that are not used.
			$PermissionType = ClassRegistry::init('PermissionType');
			$PermissionType->deleteAll(array(
				"serial IN('0', '2', '3', '4', '5', '6', '8', '9', '10', '11', '12', '13', '14')"
			));
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
			$permissionsSchema = new PermissionsSchema();
			$permissionsSchema->init();
		} elseif ($direction === 'down') {

		}
		return true;
	}
}
