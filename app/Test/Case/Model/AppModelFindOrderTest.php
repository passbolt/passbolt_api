<?php
/**
 * Application Model Test
 *
 * @copyright		(c) 2015-present Bolt Softwares Pvt Ltd
 * @package			app.Test.Case.Model.CommentTest
 * @since			version 2.12.12
 * @license			http://www.passbolt.com/license
 */
App::uses('AppModel', 'Model');
App::uses('AppTestCase', 'Test');

// Model used to test find order feature.
class AppModelFindOrder extends AppModel {
	public $useTable = false;

	public static function getFindAllowedOrder($case = null, $role = null) {
		return [
			'AppModelFindOrder.field_1',
			'AppModelFindOrder.field_2',
			'AssociatedModel.field_1',
			'AssociatedModel.field_2',
		];
	}

	public static function getFindContain($case = 'view', $role = null) {
		if ($case == 'no-contain') {
			return [];
		}

		$contain = [
			'AssociatedModel' => 1
		];
		return $contain;
	}

	public static function getFindFields($case = null, $role = null, $data = null) {
		$contain['contain'] = [];

		if (in_array('AssociatedModel', $data['contain'])) {
			$contain['contain'] = [
				'AssociatedModel' => [
					'fields' => [
						'AssociatedModel.field_1',
						'AssociatedModel.field_2',
					]
				]
			];
		}
		return $contain;
	}
}

class AppModelFindOrderTest extends AppTestCase {

	public $fixtures = array();
	public $autoFixtures = false;

/**
 * Setup
 *
 * @return void
 */
	public function setup() {
		parent::setUp();
		$this->AppModelFindOrder = ClassRegistry::init('AppModelFindOrder');
	}

/**
 * Test getFindOrder empty when no order requested.
 *
 * @return void
 */
	public function testNoOrderRequested() {
		$findData = [];
		$o = $this->AppModelFindOrder->getFindOptions('default', 'role', $findData);
		$this->assertEmpty($o['order']);
	}

/**
 * Test getFindOrder not allowed order.
 *
 * @return void
 */
	public function testNotAllowedOrderRequested() {
		$findData = ['order' => [
			'AppModelFindOrder.not_allowed_field'
		]];
		$o = $this->AppModelFindOrder->getFindOptions('default', 'role', $findData);
		$this->assertEmpty($o['order']);
	}

/**
 * Test getFindOrder empty when no order requested.
 *
 * @return void
 */
	public function testOrderWrongFormat() {
		$findData = ['order' => [
			'AppModelFindOrder.field_1 TOP'
		]];
		$o = $this->AppModelFindOrder->getFindOptions('default', 'role', $findData);
		$this->assertEmpty($o['order']);
	}

/**
 * Test getFindOrder allowed order.
 *
 * @return void
 */
	public function testAllowedOrderRequested() {
		$findData = ['order' => [
			'AppModelFindOrder.field_1 ASC',
			'AppModelFindOrder.field_2 desc',
		]];
		$o = $this->AppModelFindOrder->getFindOptions('default', 'role', $findData);
		$this->assertCount(2, $o['order']);
		$this->assertContains('AppModelFindOrder.field_1 ASC', $o['order']);
		$this->assertContains('AppModelFindOrder.field_2 desc', $o['order']);
	}

/**
 * Test getFindOrder allowed order.
 *
 * @return void
 */
	public function testNotAllowedContainOrder() {
		$findData = ['order' => [
			'AssociatedModel.field_1',
			'AssociatedModel.field_2',
		]];
		$o = $this->AppModelFindOrder->getFindOptions('no-contain', 'role', $findData);
		$this->assertEmpty($o['order']);
	}

/**
 * Test getFindOrder allowed order.
 *
 * @return void
 */
	public function testAllowedContainOrder() {
		$findData = [
			'contain' => [
				'AssociatedModel'
			],
			'order' => [
				'AssociatedModel.field_1',
				'AssociatedModel.field_2',
			]
		];
		$o = $this->AppModelFindOrder->getFindOptions('default', 'role', $findData);
		$this->assertCount(2, $o['order']);
		$this->assertContains('AssociatedModel.field_1', $o['order']);
		$this->assertContains('AssociatedModel.field_2', $o['order']);
	}

}
