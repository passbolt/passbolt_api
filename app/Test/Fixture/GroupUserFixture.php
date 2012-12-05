<?php
/**
 * GroupUser Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.GroupUserFixture
 * @since       version 2.12.11
 */
App::uses('Group', 'Model');
App::uses('User', 'Model');
App::uses('GroupUser', 'Model');

class GroupUserFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'GroupUser';

	public function init() {
		$this->records = array(
			array('id' => '1','group_id' => '50bda571-32e4-4b30-ba9a-a7c58cebc04d','user_id' => '50bda571-db84-4a61-9b0e-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '2','group_id' => '50bda571-1268-456d-b792-a7c58cebc04d','user_id' => '50bda571-c4f0-4fa5-b4cc-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '3','group_id' => '50bda571-be50-4553-9920-a7c58cebc04d','user_id' => '50bda571-f0b8-4c5f-aa54-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '4','group_id' => '50bda571-be50-4553-9920-a7c58cebc04d','user_id' => '50bda571-fdf4-4240-a0a4-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '5','group_id' => '50bda571-c404-4575-9935-a7c58cebc04d','user_id' => '50bda571-1e18-4695-aa20-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '6','group_id' => '50bda571-c404-4575-9935-a7c58cebc04d','user_id' => '50bda571-9c78-4106-b089-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '7','group_id' => '50bda571-c404-4575-9935-a7c58cebc04d','user_id' => '50bda571-5874-4f95-9427-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '8','group_id' => '50bda571-3a00-4788-86f0-a7c58cebc04d','user_id' => '50bda571-1e18-4695-aa20-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '9','group_id' => '50bda571-9b40-4c93-b738-a7c58cebc04d','user_id' => '50bda571-9c78-4106-b089-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '10','group_id' => '50bda571-9b40-4c93-b738-a7c58cebc04d','user_id' => '50bda571-5874-4f95-9427-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '11','group_id' => '50bda571-e2e8-4e57-bce2-a7c58cebc04d','user_id' => '50bda571-1e18-4695-aa20-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '12','group_id' => '50bda571-db78-420e-a3e5-a7c58cebc04d','user_id' => '50bda571-2eb0-47e0-80cf-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '13','group_id' => '50bda571-db78-420e-a3e5-a7c58cebc04d','user_id' => '50bda571-2cb0-4224-b1de-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '14','group_id' => '50bda571-db78-420e-a3e5-a7c58cebc04d','user_id' => '50bda571-1e30-4342-b358-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '15','group_id' => '50bda571-db78-420e-a3e5-a7c58cebc04d','user_id' => '50bda571-1f90-4078-acc3-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '16','group_id' => '50bda571-6cec-45df-a6d1-a7c58cebc04d','user_id' => '50bda571-2eb0-47e0-80cf-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '17','group_id' => '50bda571-6cec-45df-a6d1-a7c58cebc04d','user_id' => '50bda571-2cb0-4224-b1de-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '18','group_id' => '50bda571-eae0-40f5-b60a-a7c58cebc04d','user_id' => '50bda571-1e30-4342-b358-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '19','group_id' => '50bda571-eae0-40f5-b60a-a7c58cebc04d','user_id' => '50bda571-1f90-4078-acc3-a7c58cebc04d','created' => '2012-12-04 08:25:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
		parent::init();
	}
}