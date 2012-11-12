<?php
/**
 * CategoryResource Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.CategoryFixture
 * @since       version 2.12.9
 */
App::uses('Category', 'Model');

class CategoryFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Category';

	public function init() {
		//$this->Category = ClassRegistry::init('Category');
		// Todo : remove old tests once we will be sure that new ones are stable
		/*$this->records = array(
			array('id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56cb','name' => 'Goa', 'lft' => '1', 'rght' => '28', 'parent_id' => null, 'deleted' => 0),
			array('id' => '4ff6111b-9090-44d2-ba5a-2184cbdd56cb','name' => 'Hippies places', 'lft' => '2', 'rght' => '15', 'parent_id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff6111c-8534-4d17-869c-2184cbdd56cb','name' => 'Anjuna', 'lft' => '3', 'rght' => '14', 'parent_id' => '4ff6111b-9090-44d2-ba5a-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff6111c-dac0-4b39-81b7-2184cbdd56cb','name' => 'UV Bar', 'lft' => '4', 'rght' => '5', 'parent_id' => '4ff6111c-8534-4d17-869c-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff6111c-4ea0-4232-ae8d-2184cbdd56cb','name' => 'Curlie\'s', 'lft' => '6', 'rght' => '11', 'parent_id' => '4ff6111c-8534-4d17-869c-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff6111d-9e6c-4d71-80ee-2184cbdd56cb','name' => 'The Hippies', 'lft' => '12', 'rght' => '13', 'parent_id' => '4ff6111c-8534-4d17-869c-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff6111e-c81c-43cc-b848-2184cbdd56cb','name' => 'Dance on the beach', 'lft' => '7', 'rght' => '8', 'parent_id' => '4ff6111c-4ea0-4232-ae8d-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff6111e-47c8-45f3-8f5c-2184cbdd56cb','name' => 'Play pool table', 'lft' => '9', 'rght' => '10', 'parent_id' => '4ff6111c-4ea0-4232-ae8d-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff6111f-594c-4aaf-8a25-2184cbdd56cb','name' => 'Drug places', 'lft' => '16', 'rght' => '21', 'parent_id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff6111f-26b0-4815-a16c-2184cbdd56cb','name' => 'Disco places', 'lft' => '22', 'rght' => '27', 'parent_id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff6111f-8748-4fea-aad1-2184cbdd56cb','name' => 'Calangute', 'lft' => '17', 'rght' => '20', 'parent_id' => '4ff6111f-594c-4aaf-8a25-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff61120-04dc-4590-9510-2184cbdd56cb','name' => 'Le Nepalais', 'lft' => '18', 'rght' => '19', 'parent_id' => '4ff6111f-8748-4fea-aad1-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff61120-0934-40d5-8cde-2184cbdd56cb','name' => 'Baga', 'lft' => '23', 'rght' => '24', 'parent_id' => '4ff6111f-26b0-4815-a16c-2184cbdd56cb', 'deleted' => 0),
			array('id' => '4ff61120-fbbc-47da-bf6b-2184cbdd56cb','name' => 'Mapusa', 'lft' => '25', 'rght' => '26', 'parent_id' => '4ff6111f-26b0-4815-a16c-2184cbdd56cb', 'deleted' => 0)
		);*/
		$this->records = array(
			array('id' => '509bb871-13d0-4d82-b6ea-fb098cebc04d','parent_id' => '509bb871-d1ac-4d9d-b1e4-fb098cebc04d','lft' => '13','rght' => '20','name' => 'cakephp','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-2a6c-4a84-b2db-fb098cebc04d','parent_id' => null,'lft' => '1','rght' => '34','name' => 'Bolt Softwares Pvt. Ltd.','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-3878-4ab0-9a7e-fb098cebc04d','parent_id' => '509bb871-a18c-47b5-8a30-fb098cebc04d','lft' => '5','rght' => '6','name' => 'marketing','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-3fcc-4cdd-8668-fb098cebc04d','parent_id' => '509bb871-a18c-47b5-8a30-fb098cebc04d','lft' => '7','rght' => '8','name' => 'hr','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-3fd0-408a-af5f-fb098cebc04d','parent_id' => '509bb871-a18c-47b5-8a30-fb098cebc04d','lft' => '9','rght' => '10','name' => 'misc','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-539c-4032-b35e-fb098cebc04d','parent_id' => '509bb871-13d0-4d82-b6ea-fb098cebc04d','lft' => '14','rght' => '15','name' => 'cp-project1','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-66f0-4b07-9b71-fb098cebc04d','parent_id' => '509bb871-a18c-47b5-8a30-fb098cebc04d','lft' => '3','rght' => '4','name' => 'accounts','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-a18c-47b5-8a30-fb098cebc04d','parent_id' => '509bb871-2a6c-4a84-b2db-fb098cebc04d','lft' => '2','rght' => '11','name' => 'administration','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-be08-4617-8816-fb098cebc04d','parent_id' => '509bb871-13d0-4d82-b6ea-fb098cebc04d','lft' => '16','rght' => '17','name' => 'cp-project2','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-d1ac-4d9d-b1e4-fb098cebc04d','parent_id' => '509bb871-2a6c-4a84-b2db-fb098cebc04d','lft' => '12','rght' => '33','name' => 'projects','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb872-3038-43c6-895b-fb098cebc04d','parent_id' => '509bb872-a828-481b-8b5e-fb098cebc04d','lft' => '22','rght' => '23','name' => 'd-project1','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:38','modified' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb872-3404-48a5-8948-fb098cebc04d','parent_id' => '509bb872-b060-4e4e-beb1-fb098cebc04d','lft' => '30','rght' => '31','name' => 'o-project2','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:38','modified' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb872-616c-49de-a183-fb098cebc04d','parent_id' => '509bb872-b060-4e4e-beb1-fb098cebc04d','lft' => '28','rght' => '29','name' => 'o-project1','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:38','modified' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb872-8b00-4b23-800b-fb098cebc04d','parent_id' => '509bb872-a828-481b-8b5e-fb098cebc04d','lft' => '24','rght' => '25','name' => 'd-project2','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:38','modified' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb872-8b44-499c-8019-fb098cebc04d','parent_id' => '509bb871-13d0-4d82-b6ea-fb098cebc04d','lft' => '18','rght' => '19','name' => 'cp-project3','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:38','modified' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb872-a828-481b-8b5e-fb098cebc04d','parent_id' => '509bb871-d1ac-4d9d-b1e4-fb098cebc04d','lft' => '21','rght' => '26','name' => 'drupal','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:38','modified' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb872-b060-4e4e-beb1-fb098cebc04d','parent_id' => '509bb871-d1ac-4d9d-b1e4-fb098cebc04d','lft' => '27','rght' => '32','name' => 'others','category_type_id' => null,'deleted' => '0','created' => '2012-11-08 14:49:38','modified' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
		parent::init();
	}
}
