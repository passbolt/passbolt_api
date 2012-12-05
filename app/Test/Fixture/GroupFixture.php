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

class GroupFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Group';

	public function init() {
		$this->records = array(
			array('id' => '50bda571-1268-456d-b792-a7c58cebc04d','name' => 'accounting dpt','deleted' => '0','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-32e4-4b30-ba9a-a7c58cebc04d','name' => 'management','deleted' => '0','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-3a00-4788-86f0-a7c58cebc04d','name' => 'developers team leads','deleted' => '0','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-6cec-45df-a6d1-a7c58cebc04d','name' => 'company a','deleted' => '0','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-9b40-4c93-b738-a7c58cebc04d','name' => 'developers drupal','deleted' => '0','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-be50-4553-9920-a7c58cebc04d','name' => 'human resources','deleted' => '0','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-c404-4575-9935-a7c58cebc04d','name' => 'developers','deleted' => '0','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-db78-420e-a3e5-a7c58cebc04d','name' => 'freelancers','deleted' => '0','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-e2e8-4e57-bce2-a7c58cebc04d','name' => 'developers cakephp','deleted' => '0','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda571-eae0-40f5-b60a-a7c58cebc04d','name' => 'company b','deleted' => '0','created' => '2012-12-04 08:25:37','modified' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
		parent::init();
	}
}