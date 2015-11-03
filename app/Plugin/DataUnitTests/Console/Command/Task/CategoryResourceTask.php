<?php
/**
 * Insert CategoryResource Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataUnitTests.Console.Command.Task.CategoryResourceTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('CategoryResource', 'Model');

class CategoryResourceTask extends ModelTask {
	
	public $model = 'CategoryResource';
	
	protected function getData() {
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d99ef8-3fd4-4e62-8159-1b63d7a10fce',
			'category_id' => '10d11ff2-5208-4dc2-94d1-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.utest1-pwd1')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ff8-3fd4-4e62-8159-1b63d7a10fce',
			'category_id' => '50d77ff8-40ec-451a-b96e-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.bank-password'),
			'created' => '2012-12-24 03:34:40',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ff9-beb4-4398-90fd-1b63d7a10fce',
			'category_id' => '50d77ff8-9084-4f21-bc2f-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.facebook-account'),
			'created' => '2012-12-24 03:34:41',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ff9-dc9c-442e-a5e5-1b63d7a10fce',
			'category_id' => '50d77ff9-42d8-43d5-beee-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.salesforce-account'),
			'created' => '2012-12-24 03:34:41',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ffa-37e0-4672-b31e-1b63d7a10fce',
			'category_id' => '50d77ff9-98f0-4378-9b7a-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.tetris-license'),
			'created' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ffa-45fc-423c-a5b1-1b63d7a10fce',
			'category_id' => '50d77ffa-4030-49e1-990d-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.cpp1-pwd2'),
			'created' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ffa-5a3c-4423-9c38-1b63d7a10fce',
			'category_id' => '50d77ffa-4030-49e1-990d-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.cpp1-pwd1'),
			'created' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ffb-46f8-4d9a-8dcb-1b63d7a10fce',
			'category_id' => '50d77ffa-c25c-4d92-aa35-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.cpp2-pwd1'),
			'created' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ffb-dbdc-44b9-8110-1b63d7a10fce',
			'category_id' => '50d77ffa-c25c-4d92-aa35-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.cpp2-pwd2'),
			'created' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ffb-dd48-4397-9440-1b63d7a10fce',
			'category_id' => '50d77ffb-8008-42d2-8811-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.dp1-pwd1'),
			'created' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ffd-24c0-4656-bb48-1b63d7a10fce',
			'category_id' => '50d77ffc-0414-49dd-9959-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.op1-pwd2'),
			'created' => '2012-12-24 03:34:45',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ffd-8f8c-48c8-ab42-1b63d7a10fce',
			'category_id' => '50d77ffd-cf28-460e-b35e-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.shared-resource'),
			'created' => '2012-12-24 03:34:45',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ffd-a910-4b9d-afd9-1b63d7a10fce',
			'category_id' => '50d77ffc-0414-49dd-9959-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.shared-resource'),
			'created' => '2012-12-24 03:34:45',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d77ffd-f31c-44be-b36e-1b63d7a10fce',
			'category_id' => '50d77ffc-0414-49dd-9959-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.op1-pwd1'),
			'created' => '2012-12-24 03:34:45',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		$rc[] = array('CategoryResource'=>array(
			'id' => '50d88eed-f31c-44be-b36e-1b63d7a10fce',
			'category_id' => '50d77ffc-8608-422a-8456-1b63d7a10fce',
			'resource_id' => Common::uuid('resource.id.dp2-pwd1'),
			'created' => '2013-01-24 03:34:45',
			'created_by' => Common::uuid('user.id.anonymous')
		));
		return $rc;
	}
}
