<?php
App::uses('Category', 'Model');

class CategoryFixture extends CakeTestFixture {
	public $useDbConfig = 'test';
	public $import = 'Category';
	 
	public function init() {
		//$this->Category = ClassRegistry::init('Category');
		$this->records = array(
			array('id'=>'4ff6111b-efb8-4a26-aab4-2184cbdd56cb','name'=>'Goa', 'lft'=>'1', 'rght'=>'28', 'parent_id'=>null, 'deleted'=>0),
			array('id'=>'4ff6111b-9090-44d2-ba5a-2184cbdd56cb','name'=>'Hippies places', 'lft'=>'2', 'rght'=>'15', 'parent_id'=>'4ff6111b-efb8-4a26-aab4-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff6111c-8534-4d17-869c-2184cbdd56cb','name'=>'Anjuna', 'lft'=>'3', 'rght'=>'14', 'parent_id'=>'4ff6111b-9090-44d2-ba5a-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff6111c-dac0-4b39-81b7-2184cbdd56cb','name'=>'UV Bar', 'lft'=>'4', 'rght'=>'5', 'parent_id'=>'4ff6111c-8534-4d17-869c-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff6111c-4ea0-4232-ae8d-2184cbdd56cb','name'=>'Curlie\'s', 'lft'=>'6', 'rght'=>'11', 'parent_id'=>'4ff6111c-8534-4d17-869c-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff6111d-9e6c-4d71-80ee-2184cbdd56cb','name'=>'The Hippies', 'lft'=>'12', 'rght'=>'13', 'parent_id'=>'4ff6111c-8534-4d17-869c-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff6111e-c81c-43cc-b848-2184cbdd56cb','name'=>'Dance on the beach', 'lft'=>'7', 'rght'=>'8', 'parent_id'=>'4ff6111c-4ea0-4232-ae8d-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff6111e-47c8-45f3-8f5c-2184cbdd56cb','name'=>'Play pool table', 'lft'=>'9', 'rght'=>'10', 'parent_id'=>'4ff6111c-4ea0-4232-ae8d-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff6111f-594c-4aaf-8a25-2184cbdd56cb','name'=>'Drug places', 'lft'=>'16', 'rght'=>'21', 'parent_id'=>'4ff6111b-efb8-4a26-aab4-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff6111f-26b0-4815-a16c-2184cbdd56cb','name'=>'Disco places', 'lft'=>'22', 'rght'=>'27', 'parent_id'=>'4ff6111b-efb8-4a26-aab4-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff6111f-8748-4fea-aad1-2184cbdd56cb','name'=>'Calangute', 'lft'=>'17', 'rght'=>'20', 'parent_id'=>'4ff6111f-594c-4aaf-8a25-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff61120-04dc-4590-9510-2184cbdd56cb','name'=>'Le Nepalais', 'lft'=>'18', 'rght'=>'19', 'parent_id'=>'4ff6111f-8748-4fea-aad1-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff61120-0934-40d5-8cde-2184cbdd56cb','name'=>'Baga', 'lft'=>'23', 'rght'=>'24', 'parent_id'=>'4ff6111f-26b0-4815-a16c-2184cbdd56cb', 'deleted'=>0),
			array('id'=>'4ff61120-fbbc-47da-bf6b-2184cbdd56cb','name'=>'Mapusa', 'lft'=>'25', 'rght'=>'26', 'parent_id'=>'4ff6111f-26b0-4815-a16c-2184cbdd56cb', 'deleted'=>0)
		);
		parent::init();
	}	
}
