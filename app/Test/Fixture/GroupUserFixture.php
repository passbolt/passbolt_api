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
			array('id' => '1','group_id' => '50bd038e-5cf4-4f8a-8971-89b38cebc04d','user_id' => '50bd038e-d3d4-45bc-8d21-89b38cebc04d','created' => '2012-12-03 20:54:54','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '2','group_id' => '50bd038e-2b64-4e17-9268-89b38cebc04d','user_id' => '50bd038e-afd4-4ea8-87b4-89b38cebc04d','created' => '2012-12-03 20:54:54','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '3','group_id' => '50bd038e-9930-4dd5-b768-89b38cebc04d','user_id' => '50bd038e-e04c-428c-ab3b-89b38cebc04d','created' => '2012-12-03 20:54:54','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '4','group_id' => '50bd038e-9930-4dd5-b768-89b38cebc04d','user_id' => '50bd038e-42d4-457a-95a2-89b38cebc04d','created' => '2012-12-03 20:54:54','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '5','group_id' => '50bd038e-41ac-4bbb-9b2a-89b38cebc04d','user_id' => '50bd038e-111c-4ed3-a962-89b38cebc04d','created' => '2012-12-03 20:54:54','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '6','group_id' => '50bd038e-41ac-4bbb-9b2a-89b38cebc04d','user_id' => '50bd038f-9434-46b9-9dfc-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '7','group_id' => '50bd038e-41ac-4bbb-9b2a-89b38cebc04d','user_id' => '50bd038f-bf44-45ab-bf04-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '8','group_id' => '50bd038f-de24-4421-88f6-89b38cebc04d','user_id' => '50bd038e-111c-4ed3-a962-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '9','group_id' => '50bd038f-2f20-43b6-9e6a-89b38cebc04d','user_id' => '50bd038f-9434-46b9-9dfc-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '10','group_id' => '50bd038f-2f20-43b6-9e6a-89b38cebc04d','user_id' => '50bd038f-bf44-45ab-bf04-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '11','group_id' => '50bd038f-e16c-4311-9f67-89b38cebc04d','user_id' => '50bd038e-111c-4ed3-a962-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '12','group_id' => '50bd038f-e254-42bd-92d9-89b38cebc04d','user_id' => '50bd038f-08a0-4a63-b7ad-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '13','group_id' => '50bd038f-e254-42bd-92d9-89b38cebc04d','user_id' => '50bd038f-ed3c-4a5c-9def-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '14','group_id' => '50bd038f-e254-42bd-92d9-89b38cebc04d','user_id' => '50bd038f-6dd8-4681-a1b4-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '15','group_id' => '50bd038f-e254-42bd-92d9-89b38cebc04d','user_id' => '50bd038f-63a4-4a21-8775-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '16','group_id' => '50bd038f-6ab0-449e-8de2-89b38cebc04d','user_id' => '50bd038f-08a0-4a63-b7ad-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '17','group_id' => '50bd038f-6ab0-449e-8de2-89b38cebc04d','user_id' => '50bd038f-ed3c-4a5c-9def-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '18','group_id' => '50bd038f-954c-49a8-ab9f-89b38cebc04d','user_id' => '50bd038f-6dd8-4681-a1b4-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '19','group_id' => '50bd038f-954c-49a8-ab9f-89b38cebc04d','user_id' => '50bd038f-63a4-4a21-8775-89b38cebc04d','created' => '2012-12-03 20:54:55','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
		parent::init();
	}
}