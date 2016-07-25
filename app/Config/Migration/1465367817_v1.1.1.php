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
				'roles' => array(
					'created_by',
					'modified_by'
				),
				'profiles' => array(
					'created_by',
					'modified_by',
					'avatar'
				)
			),
			'alter_field' => array(
				'controller_logs' => array(
					'scope' => array(
						'null' => true
					)
				),
				'file_storage' => array(
					'filename' => array(
						'null' => true
					),
					'mime_type' => array(
						'size' => 128
					),
					'model' => array(
						'size' => 128
					)
				),
				'profiles' => array(
					'title' => array(
						'null' => true
					),
					'timezone' => array(
						'null' => true
					),
					'locale' => array(
						'null' => true
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
