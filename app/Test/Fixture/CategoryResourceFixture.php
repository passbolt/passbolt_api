<?php
/**
 * CategoryResource Fixture
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Fixture.CategoryResourceFixture
 * @since       version 2.12.9
 */
class CategoryResourceFixture extends CakeTestFixture {

	public $useDbConfig = 'test';

	public $import = 'CategoryResource';

	public function init() {
		parent::init();
		// Todo : remove old tests once we will be sure that new ones are stable
		/*$this->records = array(
				array('category_id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56cb', 'resource_id' => '50210bfb-84b4-4136-a8a9-270cb4e000c3'), // Church square resource is in Goa Category
				array('category_id' => '4ff6111f-594c-4aaf-8a25-2184cbdd56cb', 'resource_id' => '50210bfb-84b4-4136-a8a9-270cb4e000c3'), // Church square resource is also in Drug Places category
				array('category_id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56cb', 'resource_id' => '50210bfb-cec8-417f-87fe-270cb4e000c3'), // festival du cinema sis in Goa
				array('category_id' => '4ff6111c-8534-4d17-869c-2184cbdd56cb', 'resource_id' => '50210bfb-1554-433e-b5f2-270cb4e000c3'), // hill door is in Anjuna
				array('category_id' => '4ff6111d-9e6c-4d71-80ee-2184cbdd56cb', 'resource_id' => '50210bfb-ab90-4181-81e0-270cb4e000c3') //  washroom is at The Hippies
		);*/
		$this->records = array(
			array('id' => '1','category_id' => '509bb871-66f0-4b07-9b71-fb098cebc04d','resource_id' => '509bb871-b964-48ab-94fe-fb098cebc04d','created' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '2','category_id' => '509bb871-3878-4ab0-9a7e-fb098cebc04d','resource_id' => '509bb871-5168-49d4-a676-fb098cebc04d','created' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '3','category_id' => '509bb871-3fcc-4cdd-8668-fb098cebc04d','resource_id' => '509bb871-f4d8-4ab8-ac83-fb098cebc04d','created' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '4','category_id' => '509bb871-3fd0-408a-af5f-fb098cebc04d','resource_id' => '509bb871-94e8-4a61-9cf7-fb098cebc04d','created' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '5','category_id' => '509bb871-539c-4032-b35e-fb098cebc04d','resource_id' => '509bb871-63d0-4292-8207-fb098cebc04d','created' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '6','category_id' => '509bb871-539c-4032-b35e-fb098cebc04d','resource_id' => '509bb871-8280-41f9-a18f-fb098cebc04d','created' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '7','category_id' => '509bb871-be08-4617-8816-fb098cebc04d','resource_id' => '509bb871-db58-48a4-94c1-fb098cebc04d','created' => '2012-11-08 14:49:37','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '8','category_id' => '509bb871-be08-4617-8816-fb098cebc04d','resource_id' => '509bb871-3b14-4877-8a88-fb098cebc04d','created' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '9','category_id' => '509bb872-3038-43c6-895b-fb098cebc04d','resource_id' => '509bb872-f6fc-4cd5-b115-fb098cebc04d','created' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '10','category_id' => '509bb872-616c-49de-a183-fb098cebc04d','resource_id' => '509bb872-e7bc-4fdc-9b94-fb098cebc04d','created' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
			array('id' => '11','category_id' => '509bb872-616c-49de-a183-fb098cebc04d','resource_id' => '509bb872-6f90-42c5-93a7-fb098cebc04d','created' => '2012-11-08 14:49:38','created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c')
		);
	}
}
