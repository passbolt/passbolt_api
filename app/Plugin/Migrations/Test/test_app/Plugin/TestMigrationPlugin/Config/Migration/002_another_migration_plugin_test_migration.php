<?php
class AnotherMigrationPluginTestMigration extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'Version 002 (another test) of TestMigrationPlugin';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array()
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
