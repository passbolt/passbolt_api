<?php
/**
 * Query String Component Test
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Component', 'Controller');
App::uses('QueryStringComponent', 'Controller/Component');

// Test Class
class QueryStringComponentTest extends CakeTestCase {

/**
 * FILTER QUERY STRING VALIDATION TESTS
 */
/**
 * Check validation fails with wrong filter
 */
	public function testValidateFiltersFails() {
		$filters = [
			'has-users' => ['javascript:alert("ok")']
		];
		$str = sprintf('"%s" is not a valid user id for filter %s.', $filters['has-users'][0], 'has-users');
		$this->setExpectedException('ValidationException', $str);
		QueryStringComponent::validateFilters($filters);
	}

/**
 * Check validation fails with one right and one wrong filter
 */
	public function testValidateMultipleFiltersFails() {
		$filters = [
			'has-users' => [Common::uuid('user.id.ada'), 'javascript:alert("ok")']
		];
		$str = sprintf('"%s" is not a valid user id for filter %s.', $filters['has-users'][1], 'has-users');
		$this->setExpectedException('ValidationException', $str);
		QueryStringComponent::validateFilters($filters);
	}

/**
 * Check validation fails with one right and one wrong filter
 */
	public function testValidateMultipleFiltersSuccess() {
		$filters = [
			'has-users' => [Common::uuid('user.id.ada'), Common::uuid('user.id.betty')]
		];
		$this->assertTrue(QueryStringComponent::validateFilters($filters));
	}

/**
 * An empty filter is a valid filter
 */
	public function testFilterValidationEmptySuccess () {
		$filters = [];
		$this->assertTrue(QueryStringComponent::validateFilters($filters));
	}

/**
 * CONTAIN QUERY STRING VALIDATION TESTS
 */
/**
 * Check validate order fails with wrong parameters
 */
	public function testValidateContainsFails() {
		$contain = [
			'user' => 'javascript:alert("ok")'
		];
		$this->setExpectedException('ValidationException', sprintf('"%s" is not a valid contain value.', $contain['user']));
		QueryStringComponent::validateContain($contain);
	}

/**
 * More regexp checks
 */
	public function testContainsValidationMultipleFails () {
		$contain = [
			'group' => '1',
			'user' => 'javascript:alert("ok")'
		];
		$this->setExpectedException('ValidationException', sprintf('"%s" is not a valid contain value.', $contain['user']));
		QueryStringComponent::validateContain($contain);
	}

/**
 * Check order validate success
 */
	public function testContainsOrderSuccess() {
		$contain = [
			'user' => '1'
		];
		$this->assertTrue(QueryStringComponent::validateContain($contain));
	}

/**
 * More regexp checks
 */
	public function testContainsValidationMultipleSuccess () {
		$contain = [
			'user' => '1',
			'group' => '0'
		];
		$this->assertTrue(QueryStringComponent::validateContain($contain));
	}

/**
 * An empty contain is a valid contain
 */
	public function testContainsValidationEmptySuccess () {
		$contain = [];
		$this->assertTrue(QueryStringComponent::validateContain($contain));
	}

/**
 * ORDER QUERY STRING VALIDATION TESTS
 */
/**
 * Check validate order fails with wrong parameters
 */
	public function testValidateOrderFails() {
		$order = [
			0 => 'javascript:alert("ok")'
		];
		$this->setExpectedException('ValidationException', sprintf('"%s" is not a valid order', $order[0]));
		QueryStringComponent::validateOrders($order);
	}

/**
 * Check validation fails if one order is wrong but the other one is fine
 */
	public function testOrderValidationMultipleFails () {
		$order = [
			'User.modified_by ASC',
			'Group DESC',
		];
		$this->setExpectedException('ValidationException', sprintf('Group DESC" is not a valid order'));
		QueryStringComponent::validateOrders($order);
	}

/**
 * Check order validate success
 */
	public function testValidateOrderSuccess() {
		$order = [
			0 => 'Group.name ASC'
		];
		$this->assertTrue(QueryStringComponent::validateOrders($order));
	}

/**
 * Check success with multiple valid orders
 */
 	public function testOrderValidationMultipleSuccess () {
		$order = [
 			'User.modified_by ASC',
 			'Group.name DESC',
		];
		$this->assertTrue(QueryStringComponent::validateOrders($order));
 	}

/**
 * An empty order is a valid order
 */
	public function testOrderValidationEmptySuccess () {
		$order = [];
		$this->assertTrue(QueryStringComponent::validateOrders($order));
	}

/**
 * More failure regexp checks
 */
	public function testOrderValidationRegexFails () {
		$orders = [
			'',
			'b',
			'b.c AU',
			'User.modified_by',
			'Group.name',
			'Group.name',
			'Group.name JUNK',
		];
		foreach ($orders as $order) {
			$this->assertTrue((preg_match(QueryStringComponent::ORDER_REGEXP, $order) !==1 ), "$order order should not validate");
		}
	}

/**
 * More success regexp checks
 */
	public function testOrderValidationRegexSuccess () {
		$orders = [
			'User.modified_by ASC',
		];
		foreach ($orders as $order) {
			$this->assertTrue((preg_match(QueryStringComponent::ORDER_REGEXP, $order) === 1), "$order order should validate");
		}
	}
}
