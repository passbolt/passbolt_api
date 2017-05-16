<?php
/**
 * Query String Component Test
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Component', 'Controller');
App::uses('QueryStringComponent', 'Controller/Component');
App::uses('ComponentCollection', 'Controller');
App::uses('Controller', 'Controller');

// Test Class
class QueryStringComponentTest extends CakeTestCase {

/**
 * Main check
 */
	public function testGetSuccess() {
		$componentCollection = new ComponentCollection();
		$controller = new Controller();
		$controller->request = new stdClass();
		$controller->request->query = [
			'filter' => [
				'has-managers' => 'cf3d0099-e814-4413-ac8c-45a84917372d',
				'is-favorited' => 'true'
			],
			'order' => ['User.name DESC', 'Group.name'],
			'contain' => ['group' => 'true'],
			'modified_after' => '1272509157' // legacy item
		];
		$allowedQueryItems = [
			'filter' => ['has-managers', 'modified-after'],
			'contain' => ['group', 'user'],
			'order' => ['Group.name', 'User.name']
		];
		$expectedQuery = [
			'filter' => [
				'has-managers' => [
					0 => 'cf3d0099-e814-4413-ac8c-45a84917372d'
				],
				'modified-after' => '1272509157'
			],
			'order' => [
				0 => 'User.name DESC',
				1 => 'Group.name'
			],
			'contain' => [
				'group' => true
			]
		];
		$queryString = new QueryStringComponent($componentCollection);
		$queryString->initialize($controller);
		$sanitizedQuery = $queryString->get($allowedQueryItems);
		$this->assertTrue(($sanitizedQuery === $expectedQuery));
	}

/**
 * REWRITE Legacy Rules
 */
/**
 * modified_after should be filter[modified-after]
 */
	public function testRewriteModified() {
		$query['modified_after'] = strtotime('now');
		$results = QueryStringComponent::rewriteLegacyItems($query);
		$this->assertNotEmpty($results['filter']['modified-after']);
		$this->assertTrue(!isset($results['modified_after']));
	}

/**
 * Keywords should be treated as filter[keywords]
 */
	public function testRewriteKeywords() {
		$query['keywords'] = 'something';
		$results = QueryStringComponent::rewriteLegacyItems($query);
		$this->assertNotEmpty($results['filter']['keywords']);
		$this->assertTrue(!isset($results['keywords']));
	}

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
			'group' => true,
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
			'user' => true
		];
		$this->assertTrue(QueryStringComponent::validateContain($contain));
	}

/**
 * More regexp checks
 */
	public function testContainsValidationMultipleSuccess () {
		$contain = [
			'user' => true,
			'group' => false
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
		$allowed = ['order' => ['User.name']];
		$this->setExpectedException('ValidationException', sprintf('"%s" is not a valid order', $order[0]));
		QueryStringComponent::validateOrders($order, $allowed);
	}

/**
 * Check validation fails if one order is wrong but the other one is fine
 */
	public function testOrderValidationMultipleFails () {
		$order = [
			'User.modified_by ASC',
			'Group DESC',
		];
		$allowed = ['order' => ['User.modified_by']];
		$this->setExpectedException('ValidationException', sprintf('Group DESC" is not a valid order'));
		QueryStringComponent::validateOrders($order, $allowed);
	}

/**
 * Check order validate success
 */
	public function testValidateOrderSuccess() {
		$order = [
			0 => 'Group.name ASC'
		];
		$allowed = ['order' => ['Group.name']];
		$this->assertTrue(QueryStringComponent::validateOrders($order, $allowed));
	}

/**
 * Check success with multiple valid orders
 */
 	public function testOrderValidationMultipleSuccess () {
		$order = [
 			'User.modified_by ASC',
 			'Group.name DESC',
		];
		$allowed = ['order' => ['Group.name', 'User.modified_by']];
		$this->assertTrue(QueryStringComponent::validateOrders($order, $allowed));
 	}

/**
 * An empty order is a valid order
 */
	public function testOrderValidationEmptySuccess () {
		$order = [];
		$this->assertTrue(QueryStringComponent::validateOrders($order, []));
	}

/**
 * More failure regexp checks
 */
	public function testOrderValidationRegexFails () {
		$orders = [
			'',
			'b',
			'b.c AU',
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
			'Group.name',
			'User.modified_by ASC',
		];
		foreach ($orders as $order) {
			$this->assertTrue((preg_match(QueryStringComponent::ORDER_REGEXP, $order) === 1), "$order order should validate");
		}
	}
}
