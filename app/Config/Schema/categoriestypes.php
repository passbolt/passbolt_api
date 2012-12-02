<?php
App::uses('Category', 'Model');
App::uses('CategoryTypes', 'Model');

class CategoryTypeSchema {
	public function init() {
		$categoryType = ClassRegistry::init('CategoryType');
		$type['name'] = "default";
		$categoryType->create();
		$categoryType->save($type);
		$type['name'] = "database";
		$categoryType->create();
		$categoryType->save($type);
		$type['name'] = "ssh";
		$categoryType->create();
		$categoryType->save($type);
	}
}
