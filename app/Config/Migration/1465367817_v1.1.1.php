<?php
class v1_1_1 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'https://www.passbolt.com/release/notes#v1.1.1';
	public $since = 'v1.1.1';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'role' => array(
					'created_by',
					'modified_by'
				),
				'profile' => array(
					'created_by',
					'modified_by'
				)
			)
		)
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
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
