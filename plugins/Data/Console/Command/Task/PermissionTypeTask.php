<?php
/**
 * Insert Permission Type Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.Data.Console.Command.Task.PermissionTypeTask
 * @since        version 2.12.11
 */

require_once ('plugins' . DS . 'Data' . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');

class PermissionTypeTask extends ModelTask {

	public $model = 'PermissionType';

	protected function getData() {
		$pds = array();
		$pds[] = array(
			'serial' => 0,
			'name' => '----',
			'binary' => '0000',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '0',
			'_read' => '0',
			'active' => '1'
		);
		$pds[] = array(
			'serial' => 1,
			'binary' => '0001',
			'name' => '---r',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '0',
			'_read' => '1',
			'active' => '1'
		);
		$pds[] = array(
			'serial' => 2,
			'binary' => '0010',
			'name' => '--c-',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 3,
			'binary' => '0011',
			'name' => '--cr',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '1',
			'_read' => '1',
			'active' => '1'
		);
		$pds[] = array(
			'serial' => 4,
			'binary' => '0100',
			'name' => '-u--',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '0',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 5,
			'binary' => '0101',
			'name' => '-u-r',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '0',
			'_read' => '1',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 6,
			'binary' => '0110',
			'name' => '-uc-',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 7,
			'binary' => '0111',
			'name' => '-ucr',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '1',
			'_read' => '1',
			'active' => '1'
		);
		$pds[] = array(
			'serial' => 8,
			'binary' => '1000',
			'name' => 'a---',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '0',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 9,
			'binary' => '1001',
			'name' => 'a--r',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '0',
			'_read' => '1',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 10,
			'binary' => '1010',
			'name' => 'a-c-',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 11,
			'binary' => '1011',
			'name' => 'a-cr',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '1',
			'_read' => '1',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 12,
			'binary' => '1100',
			'name' => 'au--',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '0',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 13,
			'binary' => '1101',
			'name' => 'au-r',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '0',
			'_read' => '1',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 14,
			'binary' => '1110',
			'name' => 'auc-',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pds[] = array(
			'serial' => 15,
			'binary' => '1111',
			'name' => 'aucr',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '1',
			'_read' => '1',
			'active' => '1'
		);

		return $pds;
	}
}
