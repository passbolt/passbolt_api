<?php
/**
 * Resource Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.ResourceFixture
 * @since       version 2.12.11
 */
App::uses('Secret', 'Model');

class SecretFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Secret';

	public function init() {
		parent::init();
	}
}
