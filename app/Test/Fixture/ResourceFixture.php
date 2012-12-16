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
App::uses('CategoryResource', 'Model');

class ResourceFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'Resource';

	public function init() {
		parent::init();
		$this->records = array(
			array('id' => '509bb871-5168-49d4-a676-fb098cebc04d','name' => 'facebook account','username' => 'passbolt','expiry_date' => NULL,'uri' => 'https://95.142.173.61/deploy','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => '','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '509bb871-b964-48ab-94fe-fb098cebc04d','name' => 'bank password','username' => 'passbolt','expiry_date' => NULL,'uri' => 'https://95.142.173.61/deploy','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => '','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-1164-40ee-90d7-a7c58cebc04d','name' => 'salesforce account','username' => 'passbolt','expiry_date' => NULL,'uri' => 'https://95.142.173.61/deploy','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-50dc-4d21-a2c7-a7c58cebc04d','name' => 'cpp1-pwd2','username' => 'admin','expiry_date' => NULL,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-5ea0-4efd-bd98-a7c58cebc04d','name' => 'cpp2-pwd2','username' => 'admin','expiry_date' => NULL,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-6188-4da6-840e-a7c58cebc04d','name' => 'dp1-pwd1','username' => 'admin','expiry_date' => NULL,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-7178-4e82-8f92-a7c58cebc04d','name' => 'tetris license','username' => 'passbolt','expiry_date' => NULL,'uri' => 'https://95.142.173.61/deploy','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-7364-4499-865d-a7c58cebc04d','name' => 'shared resource','username' => 'admin','expiry_date' => NULL,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-890c-4f9d-8d14-a7c58cebc04d','name' => 'op1-pwd1','username' => 'admin','expiry_date' => NULL,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-a3f4-42c2-b898-a7c58cebc04d','name' => 'cpp1-pwd1','username' => 'admin','expiry_date' => NULL,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-f140-4861-8763-a7c58cebc04d','name' => 'cpp2-pwd1','username' => 'admin','expiry_date' => NULL,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '50bda570-f790-4603-ab0c-a7c58cebc04d','name' => 'op1-pwd2','username' => 'admin','expiry_date' => NULL,'uri' => 'http://ecpat.prod2.enova-tech.net/','description' => 'this is a description test','deleted' => '0','created' => '2012-12-04 08:25:36','modified' => '2012-12-04 08:25:36','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c','modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
	}
	
	public static function generateResources($n, $options = array()) {
		$returnValue = array();
		
		$Resource = ClassRegistry::init('Resource');
		$Resource->useDbConfig = 'test';
		$Resource->Behaviors->load('ModelTestCase');
		
		$options = array(
			'category' => 'RAND'
		);
		
		for ($i = 0; $i < $n; $i++) {
			$Resource->create();
			$Resource->setTestData($options);
			$returnValue[] =  $Resource->save();
			
			if (isset($options['category'])) {
				if (is_a($options['category'], 'Category')) {
					$category = $options['category'];
				} else if ($options['category'] == 'RAND') {
					$category = $Resource->CategoryResource->Category->find('first', array('order' => 'rand()'));
				}
				
				$categoryResourceData = array(
					'CategoryResource' => array(
						'category_id' => $category['Category']['id'],
						'resource_id' => $Resource->id
					)
				);
				$Resource->CategoryResource->create();
				$Resource->CategoryResource->save($categoryResourceData);
			}
		}
		
		return $returnValue;
	}
}
