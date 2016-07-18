<?php
/**
 * Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

abstract class PrecheckBase {

/**
 * CakeMigration instance
 *
 * @var CakeMigration
 */
	protected $_migration;

/**
 * Perform check before field create.
 *
 * @param string $table Table to look for
 * @param string $field Field to look for
 * @return bool
 */
	abstract public function checkAddField($table, $field);

/**
 * Perform check before table create.
 *
 * @param string $table Table to look for
 * @return bool
 */
	abstract public function checkCreateTable($table);

/**
 * Perform check before table drop.
 *
 * @param string $table Table to look for
 * @return bool
 */
	abstract public function checkDropTable($table);

/**
 * Perform check before field drop.
 *
 * @param string $table Table to look for
 * @param string $field Field to look for
 * @return bool
 */
	abstract public function checkDropField($table, $field);

/**
 * Check that table exists.
 *
 * @param string $table Table to look for
 * @return bool
 */
	public function tableExists($table) {
		$this->_migration->db->cacheSources = false;
		$tables = $this->_migration->db->listSources();
		return in_array($this->_migration->db->fullTableName($table, false, false), $tables);
	}

/**
 * Check that field exists.
 *
 * @param string $table Table to look for
 * @param string $field Field to look for
 * @return bool
 */
	public function fieldExists($table, $field) {
		if (!$this->tableExists($table)) {
			return false;
		}
		$fields = $this->_migration->db->describe($table);
		return !empty($fields[$field]);
	}

/**
 * Before action precheck callback.
 *
 * @param CakeMigration $migration Migration to perform
 * @param string $type Type of action being performed
 * @param array $data Data passed to action
 * @throws MigrationException
 * @return bool
 */
	public function beforeAction($migration, $type, $data) {
		$this->_migration = $migration;
		switch ($type) {
			case 'create_table':
				return $this->checkCreateTable($data['table']);
			case 'drop_table':
				return $this->checkDropTable($data['table']);
			case 'rename_table':
				return $this->checkCreateTable($data['new_name']) && $this->checkDropTable($data['old_name']);
			case 'add_field':
				return $this->checkAddField($data['table'], $data['field']);
			case 'drop_field':
				return $this->checkDropField($data['table'], $data['field']);
			case 'change_field':
				return true;
			case 'rename_field':
				return $this->checkAddField($data['table'], $data['new_name']) && $this->checkDropField($data['table'], $data['old_name']);
			case 'add_index':
			case 'drop_index':
				return true;
			default:
				throw new MigrationException($this->_migration, sprintf(
					__d('migrations', 'Migration action type (%s) is not one of valid actions type.'), $type
				), E_USER_NOTICE);
		}
	}

}
