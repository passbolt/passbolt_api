<?php
/**
 * Tag Resource Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.ResourceTagFixture
 * @since       version 2.12.11
 */
class ResourceTagFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'ResourceTag';

	public function init() {
		parent::init();
		$this->records = array(
			array('id' => 'bbb00001-c5cd-11e1-a0c5-080027796c4c', 'tag_id' => 'aaa00003-c5cd-11e1-a0c5-080027796c4c', 'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d', 'created' => '2012-11-25 13:39:25', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => 'bbb00002-c5cd-11e1-a0c5-080027796c4c', 'tag_id' => 'aaa00001-c5cd-11e1-a0c5-080027796c4c', 'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d', 'created' => '2012-11-25 13:39:25', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => 'bbb00003-c5cd-11e1-a0c5-080027796c4c', 'tag_id' => 'aaa00000-c5cd-11e1-a0c5-080027796c4c', 'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d', 'created' => '2012-11-25 13:39:25', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
	}
}
