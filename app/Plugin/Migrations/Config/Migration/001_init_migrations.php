<?php
class InitMigrations extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'Init migrations tables';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'schema_migrations' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
					'class' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 33),
					'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1)
					)
				)
			)
		),
		'down' => array(
			'drop_table' => array(
				'schema_migrations'
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
