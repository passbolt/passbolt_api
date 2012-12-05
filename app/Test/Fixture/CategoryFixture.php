<?php
/**
 * CategoryResource Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.CategoryFixture
 * @since       version 2.12.11
 */
App::uses('Category', 'Model');

class CategoryFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Category';

	public function init() {
		$this->records = array(
			array('id' => '50bda570-261c-4ba1-99f8-a7c58cebc04d','parent_id' => '50bda570-f724-4a09-90f6-a7c58cebc04d','lft' => '3','rght' => '4','name' => 'accounts','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-4ac8-4423-8e05-a7c58cebc04d','parent_id' => '50bda570-f724-4a09-90f6-a7c58cebc04d','lft' => '9','rght' => '10','name' => 'misc','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-4d54-4910-acd2-a7c58cebc04d','parent_id' => '50bda570-d59c-4cec-9b9c-a7c58cebc04d','lft' => '30','rght' => '31','name' => 'o-project2','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-6fa4-4abd-9be5-a7c58cebc04d','parent_id' => '50bda570-d59c-4cec-9b9c-a7c58cebc04d','lft' => '28','rght' => '29','name' => 'o-project1','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-7d90-4ed6-8b3d-a7c58cebc04d','parent_id' => '50bda570-8454-4117-a8f2-a7c58cebc04d','lft' => '22','rght' => '23','name' => 'd-project1','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-8454-4117-a8f2-a7c58cebc04d','parent_id' => '50bda570-f320-4a69-86d2-a7c58cebc04d','lft' => '21','rght' => '26','name' => 'drupal','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-8adc-4e4c-9ee3-a7c58cebc04d','parent_id' => '50bda570-f724-4a09-90f6-a7c58cebc04d','lft' => '7','rght' => '8','name' => 'hr','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-b5e0-4ca9-8879-a7c58cebc04d','parent_id' => '50bda570-f724-4a09-90f6-a7c58cebc04d','lft' => '5','rght' => '6','name' => 'marketing','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-c7ec-4245-961d-a7c58cebc04d','parent_id' => '50bda570-f870-44e0-b787-a7c58cebc04d','lft' => '18','rght' => '19','name' => 'cp-project3','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-d474-4700-a0ab-a7c58cebc04d','parent_id' => '50bda570-8454-4117-a8f2-a7c58cebc04d','lft' => '24','rght' => '25','name' => 'd-project2','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-d59c-4cec-9b9c-a7c58cebc04d','parent_id' => '50bda570-f320-4a69-86d2-a7c58cebc04d','lft' => '27','rght' => '32','name' => 'others','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-f300-4729-b9bb-a7c58cebc04d','parent_id' => NULL,'lft' => '1','rght' => '34','name' => 'Bolt Softwares Pvt. Ltd.','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-f320-4a69-86d2-a7c58cebc04d','parent_id' => '50bda570-f300-4729-b9bb-a7c58cebc04d','lft' => '12','rght' => '33','name' => 'projects','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-f724-4a09-90f6-a7c58cebc04d','parent_id' => '50bda570-f300-4729-b9bb-a7c58cebc04d','lft' => '2','rght' => '11','name' => 'administration','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-f870-44e0-b787-a7c58cebc04d','parent_id' => '50bda570-f320-4a69-86d2-a7c58cebc04d','lft' => '13','rght' => '20','name' => 'cakephp','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-f8cc-4b70-850c-a7c58cebc04d','parent_id' => '50bda570-f870-44e0-b787-a7c58cebc04d','lft' => '16','rght' => '17','name' => 'cp-project2','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-fb70-4cfc-9d18-a7c58cebc04d','parent_id' => '50bda570-f870-44e0-b787-a7c58cebc04d','lft' => '14','rght' => '15','name' => 'cp-project1','category_type_id' => NULL,'deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
		parent::init();
	}
}
