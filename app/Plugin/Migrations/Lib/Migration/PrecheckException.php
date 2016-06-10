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

App::uses('PrecheckBase', 'Migrations.Lib/Migration');

class PrecheckException extends PrecheckBase {

/**
 * Check if table does not exist.
 *
 * @param string $table Table to look for
 * @throws MigrationException
 * @return bool
 */
	public function checkDropTable($table) {
		if (!$this->tableExists($table)) {
			throw new MigrationException($this->_migration,
				__d('migrations', 'Table "%s" does not exist in database.', $this->_migration->db->fullTableName($table, false, false))
			);
		}
		return true;
	}

/**
 * Check if table already exists.
 *
 * @param string $table Table to look for
 * @throws MigrationException
 * @return bool
 */
	public function checkCreateTable($table) {
		if ($this->tableExists($table)) {
			throw new MigrationException($this->_migration,
				__d('migrations', 'Table "%s" already exists in database.', $this->_migration->db->fullTableName($table, false, false))
			);
		}
		return true;
	}

/**
 * Perform check before field drop.
 *
 * @param string $table Table to look in
 * @param string $field Field to look for
 * @throws MigrationException
 * @return bool
 */
	public function checkDropField($table, $field) {
		if ($this->tableExists($table) && !$this->fieldExists($table, $field)) {
			throw new MigrationException($this->_migration, sprintf(
				__d('migrations', 'Field "%s" does not exist in "%s".'), $field, $table
			));
		}
		return true;
	}

/**
 * Perform check before field create.
 *
 * @param string $table Table to look in
 * @param string $field Field to look for
 * @throws MigrationException
 * @return bool
 */
	public function checkAddField($table, $field) {
		if ($this->tableExists($table) && $this->fieldExists($table, $field)) {
			throw new MigrationException($this->_migration, sprintf(
				__d('migrations', 'Field "%s" already exists in "%s".'), $field, $table
			));
		}
		return true;
	}

}
