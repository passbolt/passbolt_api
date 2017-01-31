<?php
/**
 * Group Fixture
 */
class GroupFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '012568d6-9300-385b-a22a-e27d191764eb',
			'name' => 'Sales',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '14153251-abcb-3c00-a2d1-b4fdb1423d26',
			'name' => 'Management',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '1ec15696-e564-3e60-ad76-d73415b73afd',
			'name' => 'Human resource',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '24537609-6db5-31bb-af0d-f7f0494dd184',
			'name' => 'Creative',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '563a4b8f-e7ac-31ff-a3e8-f1b6d3ff222a',
			'name' => 'Accounting',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5',
			'modified_by' => '1c137bd7-2838-3c3d-a021-d2986d9126f5'
		),
		array(
			'id' => '5deebe9f-8e83-354c-a035-4e79353a0957',
			'name' => 'Developer',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '6da9af58-6e84-3a66-a1da-e11454751a45',
			'name' => 'Administration',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '73c705f1-919d-3916-a5b7-990b3a517d14',
			'name' => 'Traffic',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => '7fb692ab-2631-35bb-ab34-7f69b2e7f0a2',
			'name' => 'Freelancer',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'd6b15a4e-4fdf-3026-ac49-5a8de3fc49a0',
			'name' => 'Board',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'da776e4a-73b7-3a58-a047-f31614bd15bb',
			'name' => 'Marketing',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
		array(
			'id' => 'fea0c76e-046c-33ab-ab27-809ce35c0cdb',
			'name' => 'Network',
			'deleted' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5',
			'modified_by' => '2e18717e-e2b4-3c55-a6b2-586b0f17dba5'
		),
	);

}
