<?php
/**
 * Insert Resource Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataUnitTests.Console.Command.Task.ResourceTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Resource', 'Model');

class ResourceTask extends ModelTask {

	public $model = 'Resource';

	public function execute() {
		$Model = ClassRegistry::init($this->model);
		// @todo work on permissionable and save
		//$Model->hasOne = array();
		$Model->Behaviors->disable('Permissionable');
		$data = $this->getData();
		foreach ($data as $item) {
			$Model->create();
			$Model->set($item);
			if (!$Model->validates()) {
				var_dump($Model->validationErrors);
			}
			$instance = $Model->save();
			if (!$instance) {
				$this->out('<error>Unable to insert ' . $item[$this->model]['name'] . '</error>');
			}
		}
	}

	protected function getData() {
		$r[] = array('Resource'=>array(
			'id' => '408bb871-5168-49d4-a676-fb098cebc04d',
			'name' => 'utest1-pwd1',
			'username' => 'unitTest1',
			'expiry_date' => null,
			'uri' => 'https://unit-test.com',
			'description' => 'description',
			'deleted' => 0
		));
		$r[] = array('Resource'=>array(
			'id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'name' => 'facebook account',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://facebook.com',
			'description' => 'facebook account description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce'
		));
		$r[] = array('Resource'=>array(
			'id' => '509bb871-b964-48ab-94fe-fb098cebc04d',
			'name' => 'bank password',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://bank.com',
			'description' => 'bank password description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce'
		));
		$r[] = array('Resource'=>array(
			'id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'name' => 'salesforce account',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://salesforce.com',
			'description' => 'salesforce account description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$r[] = array('Resource'=>array(
			'id' => '50d77ff9-fdd8-4035-b7c6-1b63d7a10fce',
			'name' => 'tetris license',
			'username' => 'passbolt',
			'expiry_date' => null,
			'uri' => 'https://tetris.com',
			'description' => 'tetris license description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$r[] = array('Resource'=>array(
			'id' => '50d77ffa-7278-41fc-a4bb-1b63d7a10fce',
			'name' => 'cpp1-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project1.net/',
			'description' => 'description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$r[] = array('Resource'=>array(
			'id' => '50d77ffa-9b04-42e9-9974-1b63d7a10fce',
			'name' => 'cpp1-pwd2',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project1.net/',
			'description' => 'cpp1-pwd2 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$r[] = array('Resource'=>array(
			'id' => '50d77ffb-40fc-472b-9fc6-1b63d7a10fce',
			'name' => 'cpp2-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project2.net/',
			'description' => 'cpp2-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$r[] = array('Resource'=>array(
			'id' => '50d77ffb-afb4-4a73-85fd-1b63d7a10fce',
			'name' => 'cpp2-pwd2',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://cake.project2.net/',
			'description' => 'cpp2-pwd2 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$r[] = array('Resource'=>array(
			'id' => '50d77ffb-d254-49e4-ac86-1b63d7a10fce',
			'name' => 'dp1-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://drupal.project1.net/',
			'description' => 'dp1-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$r[] = array('Resource'=>array(
			'id' => '50d77ffb-d290-49e4-ac86-1b63d7a10fce',
			'name' => 'dp2-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://drupal.project1.net/',
			'description' => 'dp2-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));		
		$r[] = array('Resource'=>array(
			'id' => '50d77ffd-3294-4db8-89f6-1b63d7a10fce',
			'name' => 'op1-pwd2',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://other.project1.net/',
			'description' => 'op1-pwd2 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$r[] = array('Resource'=>array(
			'id' => '50d77ffd-5624-492c-842e-1b63d7a10fce',
			'name' => 'shared resource',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://shared.resource.net/',
			'description' => 'shared resource description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$r[] = array('Resource'=>array(
			'id' => '50d77ffd-d54c-4bd3-b947-1b63d7a10fce',
			'name' => 'op1-pwd1',
			'username' => 'admin',
			'expiry_date' => null,
			'uri' => 'http://other.project1.net/',
			'description' => 'op1-pwd1 description',
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $r;
	}
}
