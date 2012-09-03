<?php
/**
 * Resource Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.ResourceFixture
 * @since       version 2.12.9
 */
App::uses('Resource', 'Model');

class ResourceFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Resource';

	public function init() {
		parent::init();
		$this->records = array(
			array('id' => '50210bfb-cec8-417f-87fe-270cb4e000c3', 'name' => 'festival du cinema', 'username' => 'festival', 'expiry_date' => null, 'uri' => 'http://www.iffigoa.org/', 'description' => 'description of the Goa Film Festival'),
			array('id' => '50210bfb-84b4-4136-a8a9-270cb4e000c3', 'name' => 'Church Square', 'username' => 'priest1', 'expiry_date' => null, 'uri' => '', 'description' => 'this is a description test'),
			array('id' => '50210bfb-1554-433e-b5f2-270cb4e000c3', 'name' => 'hill door', 'username' => 'hippie', 'expiry_date' => null, 'uri' => 'http://www.hippiehill.com', 'description' => 'never underestimate the power of Anjuna Hills'),
			array('id' => '50210bfb-ab90-4181-81e0-270cb4e000c3', 'name' => 'washroom', 'username' => 'sousouchaie', 'expiry_date' => null, 'uri' => '', 'description' => 'How to get inside the washroom at Hippie ?'),
			array('id' => '50210bfb-dda8-4a60-a45b-270cb4e000c3', 'name' => 'random', 'username' => 'user1', 'expiry_date' => '2014-07-01', 'uri' => 'http://www.enova-tech.net', 'description' => 'sample entry')
		);
	}
}
