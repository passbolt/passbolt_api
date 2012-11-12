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
		// Todo : remove old tests once we will be sure that new ones are stable
		/*$this->records = array(
			array('id' => '50210bfb-cec8-417f-87fe-270cb4e000c3', 'name' => 'festival du cinema', 'username' => 'festival', 'expiry_date' => null, 'uri' => 'http://www.iffigoa.org/', 'description' => 'description of the Goa Film Festival'),
			array('id' => '50210bfb-84b4-4136-a8a9-270cb4e000c3', 'name' => 'Church Square', 'username' => 'priest1', 'expiry_date' => null, 'uri' => '', 'description' => 'this is a description test'),
			array('id' => '50210bfb-1554-433e-b5f2-270cb4e000c3', 'name' => 'hill door', 'username' => 'hippie', 'expiry_date' => null, 'uri' => 'http://www.hippiehill.com', 'description' => 'never underestimate the power of Anjuna Hills'),
			array('id' => '50210bfb-ab90-4181-81e0-270cb4e000c3', 'name' => 'washroom', 'username' => 'sousouchaie', 'expiry_date' => null, 'uri' => '', 'description' => 'How to get inside the washroom at Hippie ?'),
			array('id' => '50210bfb-dda8-4a60-a45b-270cb4e000c3', 'name' => 'random', 'username' => 'user1', 'expiry_date' => '2014-07-01', 'uri' => 'http://www.enova-tech.net', 'description' => 'sample entry')
		);*/
		$this->records = array(
			array('id' => '509bb871-3b14-4877-8a88-fb098cebc04d','name' => 'cpp2-pwd2','username' => 'admin','expiry_date' => null,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-5168-49d4-a676-fb098cebc04d','name' => 'facebook account','username' => 'passbolt','expiry_date' => null,'uri' => 'https://95.142.173.61/deploy','description' => 'this is a description test','deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-63d0-4292-8207-fb098cebc04d','name' => 'cpp1-pwd1','username' => 'admin','expiry_date' => null,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-8280-41f9-a18f-fb098cebc04d','name' => 'cpp1-pwd2','username' => 'admin','expiry_date' => null,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-94e8-4a61-9cf7-fb098cebc04d','name' => 'tetris license','username' => 'passbolt','expiry_date' => null,'uri' => 'https://95.142.173.61/deploy','description' => 'this is a description test','deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-b964-48ab-94fe-fb098cebc04d','name' => 'bank password','username' => 'passbolt','expiry_date' => null,'uri' => 'https://95.142.173.61/deploy','description' => 'this is a description test','deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-db58-48a4-94c1-fb098cebc04d','name' => 'cpp2-pwd1','username' => 'admin','expiry_date' => null,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-f4d8-4ab8-ac83-fb098cebc04d','name' => 'salesforce account','username' => 'passbolt','expiry_date' => null,'uri' => 'https://95.142.173.61/deploy','description' => 'this is a description test','deleted' => '0','created' => '2012-11-08 14:49:37','modified' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb872-6f90-42c5-93a7-fb098cebc04d','name' => 'op1-pwd2','username' => 'admin','expiry_date' => null,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-11-08 14:49:38','modified' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb872-e7bc-4fdc-9b94-fb098cebc04d','name' => 'op1-pwd1','username' => 'admin','expiry_date' => null,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-11-08 14:49:38','modified' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb872-f6fc-4cd5-b115-fb098cebc04d','name' => 'dp1-pwd1','username' => 'admin','expiry_date' => null,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-11-08 14:49:38','modified' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
	}
}
