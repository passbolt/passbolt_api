<?php
/**
 * Insert Category Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataUnitTests.Console.Command.Task.CategoryTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Category', 'Model');

class CategoryTask extends ModelTask {

	public $model = 'Category';

	public function execute() {
		$Model = ClassRegistry::init($this->model);
		// @todo work on permissionable and save
		//$Model->hasOne = array();
		$Model->Behaviors->disable('Permissionable');
		$data = $this->getData();
		foreach ($data as $item) {
			$Model->create();
			if (!$m = $Model->save($item)) {
				pr($Model->validationErrors);
				$errorMsg = mysql_error();
				echo $errorMsg;
			}
		}
	}

	public static function getAlias() {
		$Category = ClassRegistry::init('Category');
		$aliases = array(
			// Passbolt case, mainly used to test permission system inheritance
			'root' => $Category->findByName("Bolt Softwares Pvt. Ltd."),
			'adm' => $Category->findByName("administration"),
			'acc' => $Category->findByName("accounts"),
			'mar' => $Category->findByName("marketing"),
			'hr' => $Category->findByName("human resource"),
			'mis' => $Category->findByName("misc"),
			'pro' => $Category->findByName("projects"),
			'cak' => $Category->findByName("cakephp"),
			'cp1' => $Category->findByName("cp-project1"),
			'cp2' => $Category->findByName("cp-project2"),
			'cp3' => $Category->findByName("cp-project3"),
			'dru' => $Category->findByName("drupal"),
			'dp1' => $Category->findByName("d-project1"),
			'dp2' => $Category->findByName("d-project2"),
			'oth' => $Category->findByName("others"),
			'op1' => $Category->findByName("o-project1"),
			'op2' => $Category->findByName("o-project2"),
			'pvt' => $Category->findByName("private"),
			'pjr' => $Category->findByName("pv-jean_bartik"),

			// Unit tests sandbox category
			'utt' => $Category->findByName("utest")
		);
		foreach ($aliases as $name => $obj) {
			$aliases[$name] = $obj['Category']['id'];
		}
		return $aliases;
	}
	
	protected function getData() {
		// Passbolt main Use case
		$c[] = array('Category'=>array(
			'id' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'parent_id' => null,
			'name' => 'Bolt Softwares Pvt. Ltd.',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:39',
			'modified' => '2012-12-24 03:34:39',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '533d3a6b-fc74-4fde-b19f-0aafc0a895dc',
			'parent_id' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'name' => 'empty',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2014-03-18 03:34:39',
			'modified' => '2014-03-18 03:34:39',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'parent_id' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'name' => 'administration',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:39',
			'modified' => '2012-12-24 03:34:39',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ff8-40ec-451a-b96e-1b63d7a10fce',
			'parent_id' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'name' => 'accounts',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ff8-9084-4f21-bc2f-1b63d7a10fce',
			'parent_id' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'name' => 'marketing',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:40',
			'modified' => '2012-12-24 03:34:40',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ff9-42d8-43d5-beee-1b63d7a10fce',
			'parent_id' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'name' => 'human resource',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ff9-98f0-4378-9b7a-1b63d7a10fce',
			'parent_id' => '50d77ff7-bcac-4c03-8687-1b63d7a10fce',
			'name' => 'misc',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:41',
			'modified' => '2012-12-24 03:34:41',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'parent_id' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'name' => 'projects',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ffa-5144-4b95-badd-1b63d7a10fce',
			'parent_id' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'name' => 'cakephp',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ffa-4030-49e1-990d-1b63d7a10fce',
			'parent_id' => '50d77ffa-5144-4b95-badd-1b63d7a10fce',
			'name' => 'cp-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ffa-c25c-4d92-aa35-1b63d7a10fce',
			'parent_id' => '50d77ffa-5144-4b95-badd-1b63d7a10fce',
			'name' => 'cp-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:42',
			'modified' => '2012-12-24 03:34:42',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ffb-b9a0-415d-ba6a-1b63d7a10fce',
			'parent_id' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'name' => 'drupal',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ffb-8008-42d2-8811-1b63d7a10fce',
			'parent_id' => '50d77ffb-b9a0-415d-ba6a-1b63d7a10fce',
			'name' => 'd-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ffb-d488-4217-9e2f-1b63d7a10fce',
			'parent_id' => '50d77ffa-5144-4b95-badd-1b63d7a10fce',
			'name' => 'cp-project3',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:43',
			'modified' => '2012-12-24 03:34:43',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ffc-08ac-42a8-b185-1b63d7a10fce',
			'parent_id' => '50d77ffa-094c-4d4c-9dd7-1b63d7a10fce',
			'name' => 'others',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ffc-0414-49dd-9959-1b63d7a10fce',
			'parent_id' => '50d77ffc-08ac-42a8-b185-1b63d7a10fce',
			'name' => 'o-project1',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ffc-8608-422a-8456-1b63d7a10fce',
			'parent_id' => '50d77ffb-b9a0-415d-ba6a-1b63d7a10fce',
			'name' => 'd-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:44',
			'modified' => '2012-12-24 03:34:44',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '50d77ffd-cf28-460e-b35e-1b63d7a10fce',
			'parent_id' => '50d77ffc-08ac-42a8-b185-1b63d7a10fce',
			'name' => 'o-project2',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2012-12-24 03:34:45',
			'modified' => '2012-12-24 03:34:45',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '444d3a7b-fc90-4faa-a19f-1aafc0a895dc',
			'parent_id' => '50d77ff7-5208-4dc2-94d1-1b63d7a10fce',
			'name' => 'private',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2014-05-06 03:34:39',
			'modified' => '2014-05-06 03:34:39',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$c[] = array('Category'=>array(
			'id' => '222d3a7b-fc70-4faa-a19f-1aafc0a800dc',
			'parent_id' => null,
			'name' => 'pv-jean_bartik',
			'category_type_id' => null,
			'deleted' => 0,
			'created' => '2014-05-06 03:35:39',
			'modified' => '2014-05-06 03:35:39',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));

		// Sand box unit test
		$c[] = array('Category'=>array(
			'id' => '10d11ff1-5208-4dc2-94d1-1b63d7a10fce',
			'parent_id' => null,
			'name' => 'utest',
			'category_type_id' => null,
			'deleted' => 0
		));
		$c[] = array('Category'=>array(
			'id' => '10d11ff2-5208-4dc2-94d1-1b63d7a10fce',
			'parent_id' => '10d11ff1-5208-4dc2-94d1-1b63d7a10fce',
			'name' => 'utest1',
			'category_type_id' => null,
			'deleted' => 0
		));
		$c[] = array('Category'=>array(
			'id' => '10d11ff3-5208-4dc2-94d1-1b63d7a10fce',
			'parent_id' => '10d11ff1-5208-4dc2-94d1-1b63d7a10fce',
			'name' => 'utest2',
			'category_type_id' => null,
			'deleted' => 0
		));
		
		return $c;
	}
}
