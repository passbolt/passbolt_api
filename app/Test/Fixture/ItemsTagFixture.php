<?php
/**
 * ItemsTagFixture
 *
 */
class ItemsTagFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'tag_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'foreign_model' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'foreign_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'tag_id' => array('column' => array('tag_id', 'foreign_model', 'foreign_id'), 'unique' => 0)
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
			'id' => '21ef31b6-8a50-37b0-a4ef-09a77f43d83d',
			'tag_id' => '9bc58fb6-3b95-35da-a8e8-ae7aa5dd7abe',
			'foreign_model' => 'Resource',
			'foreign_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'cfc1630c-a36c-3a97-a803-143fe54fdfa8',
			'tag_id' => '6ea4ba42-ae97-3910-a2f7-a7dd9cfc1619',
			'foreign_model' => 'Resource',
			'foreign_id' => '96e26ceb-b288-39b7-a515-3b1b45cb2673',
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ddac9724-6419-3093-ac66-d277ca17a361',
			'tag_id' => 'aeaa6327-cf98-37c2-a20b-c96f79aff277',
			'foreign_model' => 'Resource',
			'foreign_id' => '5c67e5d8-29dd-3719-aab5-5a151226255d',
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
		array(
			'id' => 'ebb49e9e-8ff2-3518-a74a-f459b0a9b493',
			'tag_id' => '6732c5fa-02f4-37fd-ace3-2ac5a668570b',
			'foreign_model' => 'Resource',
			'foreign_id' => '6b00d6e5-8584-3db4-a24d-7179b8dd44d5',
			'created' => '2012-11-25 13:39:25',
			'created_by' => 'c3b37726-7483-37fd-a185-a3f6b9ed0df1'
		),
	);

}
