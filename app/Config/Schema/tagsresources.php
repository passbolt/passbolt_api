<?php
/**
 * Tag Resource Schema
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Config.Schema.tagsresources
 * @since        version 2.12.11
 */
App::uses('Tag', 'Model');
App::uses('TagResource', 'Model');

class TagsResourcesSchema {

	public function init() {
		$tagResource = ClassRegistry::init('TagResource');
		$rts = $this->_getDefaultResourceTags();
		foreach ($rts as $rt) {
			$tagResource->create();
			$tagResource->save($rt);
		}
	}

	protected function _getDefaultResourceTags() {
		$rts[] = array('TagResource' => array(
			'id' => 'zzz00001-c5cd-11e1-a0c5-080027796c4c',
			'tag_id' => 'aaa00003-c5cd-11e1-a0c5-080027796c4c',      // banking
			'resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d', // bank
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rts[] = array('TagResource' => array(
			'id' => 'zzz00002-c5cd-11e1-a0c5-080027796c4c',
			'tag_id' => 'aaa00001-c5cd-11e1-a0c5-080027796c4c',      // facebook
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d', // facebook
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rts[] = array('TagResource' => array(
			'id' => 'zzz00003-c5cd-11e1-a0c5-080027796c4c',
			'tag_id' => 'aaa00000-c5cd-11e1-a0c5-080027796c4c',      // social
			'resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d', // facebook
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $rts;
	}
}
