<?php
/**
 * Category Type Schema
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Config.Schema.categoriestypes
 * @since        version 2.12.11
 */
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
