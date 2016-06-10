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

class PrecheckCondition extends PrecheckBase {

/**
 * Perform check before table drop.
 *
 * @param string $table Table to look for
 * @return bool
 */
	public function checkDropTable($table) {
		return $this->tableExists($table);
	}

/**
 * Perform check before table create.
 *
 * @param string $table Table to look for
 * @return bool
 */
	public function checkCreateTable($table) {
		return !$this->tableExists($table);
	}

/**
 * Perform check before field drop.
 *
 * @param string $table Table to look for
 * @param string $field Field to look for
 * @return bool
 */
	public function checkDropField($table, $field) {
		return $this->tableExists($table) && $this->fieldExists($table, $field);
	}

/**
 * Perform check before field create.
 *
 * @param string $table Table to look for
 * @param string $field Field to look for
 * @return bool
 */
	public function checkAddField($table, $field) {
		return $this->tableExists($table) && !$this->fieldExists($table, $field);
	}

}
