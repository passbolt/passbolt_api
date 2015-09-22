<?php
/**
 * Insert Permission Type Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataDefault.Console.Command.Task.PermissionTypeTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');

class PermissionTypeTask extends ModelTask {

	public $model = 'PermissionType';

	protected function getData() {
		$pts = array();
		$pts[] = array(
            'id' => '5204e74b-e8fc-46dd-b980-75198cebc04d',
			'serial' => '0',
			'name' => '----',
			'binary' => '0000',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '0',
			'_read' => '0',
			'active' => '1'
		);
		$pts[] = array(
            'id' => '5204e74b-00d0-4d4b-b335-75198cebc04d',
			'serial' => '1',
			'binary' => '0001',
			'name' => '---r',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '0',
			'_read' => '1',
			'active' => '1'
		);
		$pts[] = array(
            'id' => '5204e74b-a8c8-441d-9ba8-75198cebc04d',
			'serial' => '2',
			'binary' => '0010',
			'name' => '--c-',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pts[] = array(
            'id' => '5204e74b-bb88-4d4f-8b93-75198cebc04d',
			'serial' => '3',
			'binary' => '0011',
			'name' => '--cr',
			'_admin' => '0',
			'_update' => '0',
			'_create' => '1',
			'_read' => '1',
			'active' => '1'
		);
		$pts[] = array(
            'id' => '5204e74b-ce48-46c6-8abc-75198cebc04d',
			'serial' => '4',
			'binary' => '0100',
			'name' => '-u--',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '0',
			'_read' => '0',
			'active' => '0'
		);
		$pts[] = array(
            'id' => '5204e74b-dfdc-49a9-8602-75198cebc04d',
			'serial' => '5',
			'binary' => '0101',
			'name' => '-u-r',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '0',
			'_read' => '1',
			'active' => '0'
		);
		$pts[] = array(
            'id' => '5204e74b-f29c-4564-9c9c-75198cebc04d',
			'serial' => '6',
			'binary' => '0110',
			'name' => '-uc-',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pts[] = array(
            'id' => '5204e74b-055c-4473-8f1b-75198cebc04d',
			'serial' => '7',
			'binary' => '0111',
			'name' => '-ucr',
			'_admin' => '0',
			'_update' => '1',
			'_create' => '1',
			'_read' => '1',
			'active' => '1'
		);
		$pts[] = array(
            'id' => '5204e74b-13f4-4c8e-9ef7-75198cebc04d',
			'serial' => '8',
			'binary' => '1000',
			'name' => 'a---',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '0',
			'_read' => '0',
			'active' => '0'
		);
		$pts[] = array(
            'id' => '5204e74b-28a8-4685-9d06-75198cebc04d',
			'serial' => '9',
			'binary' => '1001',
			'name' => 'a--r',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '0',
			'_read' => '1',
			'active' => '0'
		);
		$pts[] = array(
            'id' => '5204e74b-3b04-4383-ba32-75198cebc04d',
			'serial' => '10',
			'binary' => '1010',
			'name' => 'a-c-',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pts[] = array(
            'id' => '5204e74b-4e28-4266-a2ba-75198cebc04d',
			'serial' => '11',
			'binary' => '1011',
			'name' => 'a-cr',
			'_admin' => '1',
			'_update' => '0',
			'_create' => '1',
			'_read' => '1',
			'active' => '0'
		);
		$pts[] = array(
            'id' => '5204e74b-5fbc-43f5-b64f-75198cebc04d',
			'serial' => '12',
			'binary' => '1100',
			'name' => 'au--',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '0',
			'_read' => '0',
			'active' => '0'
		);
		$pts[] = array(
            'id' => '5204e74b-72e0-4b36-9c8e-75198cebc04d',
			'serial' => '13',
			'binary' => '1101',
			'name' => 'au-r',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '0',
			'_read' => '1',
			'active' => '0'
		);
		$pts[] = array(
            'id' => '5204e74b-8474-484e-b52a-75198cebc04d',
			'serial' => '14',
			'binary' => '1110',
			'name' => 'auc-',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '1',
			'_read' => '0',
			'active' => '0'
		);
		$pts[] = array(
            'id' => '5204e74b-9734-4548-ac57-75198cebc04d',
			'serial' => '15',
			'binary' => '1111',
			'name' => 'aucr',
			'_admin' => '1',
			'_update' => '1',
			'_create' => '1',
			'_read' => '1',
			'active' => '1'
		);

		return $pts;
	}
}
