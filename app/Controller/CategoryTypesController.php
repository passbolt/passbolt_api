<?php
/**
 * Category Types controller
 * This file will define how categories' types are managed
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.CategoryTypesController
 * @since        version 2.12.7
 */
class CategoryTypesController extends AppController {
	function populate(){
		$type['name'] = "default";
		$this->CategoryType->create();
		$cat = $this->CategoryType->save($type);
		$type['name'] = "database";
		$this->CategoryType->create();
		$this->CategoryType->save($type);
		$type['name'] = "ssh";
		$this->CategoryType->create();
		$this->CategoryType->save($type);
	}
}