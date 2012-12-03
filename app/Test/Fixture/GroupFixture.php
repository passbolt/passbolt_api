<?php
/**
 * Group Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.GroupFixture
 * @since       version 2.12.11
 */
App::uses('Group', 'Model');

class CategoryFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Group';

	public function init() {
		$this->records = array(
			array('id' => '50bd038e-2b64-4e17-9268-89b38cebc04d','name' => 'accounting dpt','deleted' => '0','created' => '2012-12-03 20:54:54','modified' => '2012-12-03 20:54:54','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bd038e-41ac-4bbb-9b2a-89b38cebc04d','name' => 'developers','deleted' => '0','created' => '2012-12-03 20:54:54','modified' => '2012-12-03 20:54:54','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bd038e-5cf4-4f8a-8971-89b38cebc04d','name' => 'management','deleted' => '0','created' => '2012-12-03 20:54:54','modified' => '2012-12-03 20:54:54','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bd038e-9930-4dd5-b768-89b38cebc04d','name' => 'human resources','deleted' => '0','created' => '2012-12-03 20:54:54','modified' => '2012-12-03 20:54:54','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bd038f-2f20-43b6-9e6a-89b38cebc04d','name' => 'developers drupal','deleted' => '0','created' => '2012-12-03 20:54:55','modified' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bd038f-6ab0-449e-8de2-89b38cebc04d','name' => 'company a','deleted' => '0','created' => '2012-12-03 20:54:55','modified' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bd038f-954c-49a8-ab9f-89b38cebc04d','name' => 'company b','deleted' => '0','created' => '2012-12-03 20:54:55','modified' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bd038f-de24-4421-88f6-89b38cebc04d','name' => 'developers team leads','deleted' => '0','created' => '2012-12-03 20:54:55','modified' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bd038f-e16c-4311-9f67-89b38cebc04d','name' => 'developers cakephp','deleted' => '0','created' => '2012-12-03 20:54:55','modified' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bd038f-e254-42bd-92d9-89b38cebc04d','name' => 'freelancers','deleted' => '0','created' => '2012-12-03 20:54:55','modified' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
		parent::init();
	}
}