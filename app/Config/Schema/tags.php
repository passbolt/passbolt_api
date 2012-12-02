<?php
App::uses('Tag', 'Model');

class TagSchema {

	public function init() {
		$tag = ClassRegistry::init('Tag');
		$ts = $this->_getDefaultTags();
		foreach ($ts as $t) {
			$tag->create();
			$tag->save($t);
		}
	}

	protected function _getDefaultTags() {
		$ts[] = array('Tag' => array(
			'id' => 'aaa00000-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'social',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$ts[] = array('Tag' => array(
			'id' => 'aaa00001-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'facebook',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$ts[] = array('Tag' => array(
			'id' => 'aaa00002-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'twitter',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$ts[] = array('Tag' => array(
			'id' => 'aaa00003-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'banking',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $ts;
	}

}
