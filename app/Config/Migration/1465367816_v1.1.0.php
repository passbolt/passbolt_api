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
	public $migration = [
		'up' => [
			'drop_field' => [
				'users' => [
					'indexes' => [
						'username'
					]
				],
				'roles' => [
					'created_by',
					'modified_by'
				],
				'profiles' => [
					'created_by',
					'modified_by',
					'avatar'
				]
			],
			'alter_field' => [
				'controller_logs' => [
					'scope' => [
						'null' => true
					]
				],
				'file_storage' => [
					'filename' => [
						'null' => true
					],
					'mime_type' => [
						'size' => 128
					],
					'model' => [
						'size' => 128
					],
					'path' => [
						'null' => true
					]
				],
				'profiles' => [
					'title' => [
						'null' => true
					],
					'timezone' => [
						'null' => true
					],
					'locale' => [
						'null' => true
					]
				]
			]
		]
	];

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down]
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down]
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
