<?php
/**
 * Tag Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.TagFixture
 * @since       version 2.12.11
 */
class TagFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Tag';

	public function init() {
		$this->records = array(
			array('id' => 'aaa00000-c5cd-11e1-a0c5-080027796c4c', 'name' => 'social', 'created' => '2012-11-25 13:39:25', 'modified' => '2012-11-25 13:39:25', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => 'aaa00001-c5cd-11e1-a0c5-080027796c4c', 'name' => 'facebook', 'created' => '2012-11-25 13:39:25', 'modified' => '2012-11-25 13:39:25', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => 'aaa00002-c5cd-11e1-a0c5-080027796c4c', 'name' => 'twitter', 'created' => '2012-11-25 13:39:25', 'modified' => '2012-11-25 13:39:25', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array( 'id' => 'aaa00003-c5cd-11e1-a0c5-080027796c4c', 'name' => 'banking', 'created' => '2012-11-25 13:39:25', 'modified' => '2012-11-25 13:39:25', 'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
		parent::init();
	}
}
