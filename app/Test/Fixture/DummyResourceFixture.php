<?php
/**
 * DummyCategory Fixture
 * Creates an variable amount of categories in the database. This will be mainly used for benchmarking
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.DummyCategoryFixture
 * @since       version 2.12.11
 */
require_once (APP . 'Test' . DS . 'Fixture' . DS . 'ResourceFixture.php');
App::uses('Resource', 'Model');
App::uses('CategoryResource', 'Model');
App::uses('Category', 'Model');
App::uses('Permission', 'Model');
App::uses('RandomTool', 'Vendor');

class DummyResourceFixture extends ResourceFixture {

	private $config = array(
		'nbCategories' => 100,
		'nbResources' => 100,
		'rateCatDirectGrpPerm' => 25,
		'rateCatDirectUsrPerm' => 5,
		'rateResDirectGrpPerm' => 25,
		'rateResDirectUsrPerm' => 5,
	);
	
	public $useDbConfig = 'test';

	public $import = 'Resource';

	public function init() {
		parent::init();

		$this->Resource = ClassRegistry::init('Resource');
		$this->Resource->useDbConfig = 'test';
		$this->Category = ClassRegistry::init('Category');
		$this->Category->useDbConfig = 'test';
		$this->User = ClassRegistry::init('User');
		$this->User->useDbConfig = 'test';
		$this->Group = ClassRegistry::init('Group');
		$this->Group->useDbConfig = 'test';
		$this->Permission = ClassRegistry::init('Permission');
		$this->Permission->useDbConfig = 'test';
	}

	public function insert() {
		$model = new Resource(null, null, 'test');
		$this->db = $model->getDataSource();
		parent::insert($this->db);
		
		// generate dummies data
		CategoryFixture::generateCategories($this->config['nbCategories'], array('parentCategory'=>'RAND'));
		ResourceFixture::generateResources($this->config['nbResources'], array('category'=>'RAND'));
		$catLength = $this->Category->find('count');
		$resLength = $this->Resource->find('count');
		
		// Apply direct group permissions on categories
		$n = (int) ($catLength * $this->config['rateCatDirectGrpPerm'] / 100);
		for ($i=0; $i<$n; $i++) {
			$randCat = $this->Category->find('first', array('order' => 'rand()'));
			$randGrp = $this->Group->find('first', array('order' => 'rand()'));
			$this->Permission->create();
			$this->Permission->save(array(
				'aco' => 'Category',
				'aco_foreign_key' => $randCat['Category']['id'],
				'aro' => 'Group',
				'aro_foreign_key' => $randGrp['Group']['id'],
				'type' => RandomTool::permissionType()
			));
		}
		
		// Apply direct user permissions on categories
		$n = (int) ($catLength * $this->config['rateCatDirectUsrPerm'] / 100);
		for ($i=0; $i<$n; $i++) {
			$randCat = $this->Category->find('first', array('order' => 'rand()'));
			$randUsr = $this->User->find('first', array('order' => 'rand()'));
			$this->Permission->create();
			$this->Permission->save(array(
				'aco' => 'Category',
				'aco_foreign_key' => $randCat['Category']['id'],
				'aro' => 'User',
				'aro_foreign_key' => $randUsr['User']['id'],
				'type' => RandomTool::permissionType()
			));
		}
		
		// Apply direct group permission on resources
		$n = (int) ($resLength * $this->config['rateResDirectGrpPerm'] / 100);
		for ($i=0; $i<$n; $i++) {
			$randRes = $this->Resource->find('first', array('order' => 'rand()'));
			$randGrp = $this->Group->find('first', array('order' => 'rand()'));
			$this->Permission->create();
			$this->Permission->save(array(
				'aco' => 'Resource',
				'aco_foreign_key' => $randRes['Resource']['id'],
				'aro' => 'Group',
				'aro_foreign_key' => $randGrp['Group']['id'],
				'type' => RandomTool::permissionType()
			));
		}
		
		// Apply direct user permission on resources
		$n = (int) ($resLength * $this->config['rateResDirectUsrPerm'] / 100);
		for ($i=0; $i<$n; $i++) {
			$randRes = $this->Resource->find('first', array('order' => 'rand()'));
			$randUsr = $this->User->find('first', array('order' => 'rand()'));
			$this->Permission->create();
			$this->Permission->save(array(
				'aco' => 'Resource',
				'aco_foreign_key' => $randRes['Resource']['id'],
				'aro' => 'User',
				'aro_foreign_key' => $randUsr['User']['id'],
				'type' => RandomTool::permissionType()
			));
		}
		
	}
}
