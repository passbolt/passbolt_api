<?php
/**
 * Item Fixture
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class ItemFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'Item';

/**
 * Table
 *
 * @var array $table
 */
	public $table = 'items';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
			'id' => array('type'=>'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
			'name' => array('type'=>'string', 'null' => false, 'default' => NULL),
			'indexes' => array(
				'PRIMARY' => array('column' => 'id', 'unique' => 1))
			);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id'  => 'item-1',
			'name' => 'Cake',
		),
		array(
			'id'  => 'item-2',
			'name' => 'More Cake',
		),
		array(
			'id'  => 'item-3',
			'name' => 'A lot Cake',
		),
	);

}
