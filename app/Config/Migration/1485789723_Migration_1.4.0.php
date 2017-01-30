<?php
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
				'tags'
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
			$model = ClassRegistry::init('Permission');
			$model->query('
				DROP VIEW users_resources_permissions;
				DROP VIEW users_categories_permissions;
				DROP VIEW groups_resources_permissions;
				DROP VIEW groups_categories_permissions;
				DROP VIEW categories_parents;
			');
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
