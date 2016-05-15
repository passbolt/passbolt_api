<?php
/**
 * Copyright 2011-2015, Florian Krämer
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * Copyright 2011-2015, Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Burzum\Imagine\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ImageFixture extends TestFixture {

/**
 * fields property
 *
 * @var array
 */
	public $fields = [
		'id' => ['type' => 'integer'],
		'title' => ['type' => 'string', 'null' => false],
		'_constraints' => [
			'primary' => ['type' => 'primary', 'columns' => ['id']]
		]
	];

/**
 * Records
 *
 * @var array
 */
	public $records = [
		['title' => 'First Image'],
		['title' => 'Second Image']
	];
}
