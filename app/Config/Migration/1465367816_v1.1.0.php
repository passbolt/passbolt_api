<?php
class v1_1_0 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'https://www.passbolt.com/release/notes#v1.1.0';
	public $since = 'v1.1.0';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'users' => array(
					'indexes' => array(
						'username'
					)
				)
			)
		),
		'down' => array(
			'create_field' => array(
				'users' => array(
					'indexes' => array(
						'username' => array(
							'column' => 'username',
							'unique' => true
						)
					)
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
