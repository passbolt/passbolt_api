<?php
/**
 * Comment Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.CommentFixture
 * @since       version 2.12.11
 */
class CommentFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Comment';

	public function init() {
		$this->records = array(
			array(
				'id' => 'aaa00000-cccc-11d1-a0c5-080027796c4c',
				'parent_id' => null,
				'foreign_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
				'foreign_model' => 'Resource',
				'content' => 'this is a short comment',
				'created' => '2012-11-25 13:39:25',
				'modified' => '2012-11-25 13:39:25',
				'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
				'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
			),
			array(
				'id' => 'aaa00001-cccc-11d1-a0c5-080027796c4c',
				'parent_id' => 'aaa00000-cccc-11d1-a0c5-080027796c4c',
				'foreign_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
				'foreign_model' => 'Resource',
				'content' => 'this is a reply to the short comment',
				'created' => '2012-11-25 13:39:26',
				'modified' => '2012-11-25 13:39:26',
				'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
				'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
			)
		);
		parent::init();
	}
}
