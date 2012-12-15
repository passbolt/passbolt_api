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
require_once (APP . 'Test' . DS . 'Fixture' . DS . 'CategoryFixture.php');
App::uses('Category', 'Model');

class DummyCategoryFixture extends CategoryFixture {

	public $useDbConfig = 'test';

	public $import = 'Category';

	public function init() {
		parent::init();

		$this->Category = ClassRegistry::init('Category');
		$this->Category->useDbConfig = 'test';
	}

	public function insert() {
		$model = new Category(null, null, 'test');
		$this->db = $model->getDataSource();
		parent::insert($this->db);
		$this->populateCategories(1000);
	}

	public function randomString($length = 10) {
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

	public function populateCategories($nbCategories) {
		$i = 0;
		$cat = array(
				'name' => 'root',
				'parent_id' => null
			);
		$this->Category->create();
		$this->Category->save($cat);
		for ($i = 0; $i < $nbCategories; $i++) {
			// Choose a random parent
			$catRandom = $this->Category->find('first',array('order' => 'rand()'));
			$cat = array(
				'name' => $this->randomString(),
				'parent_id' => $catRandom['Category']['id']
			);
			$this->Category->create();
			$this->Category->save($cat);
		}
	}
}
