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
namespace Burzum\Imagine\Model;

use Cake\ORM\Table;

/**
 * @deprecated Use the ImageProcessor class instead.
 */
class Imagine extends Table {

/**
 * Name
 *
 * @var string
 */
	public $name = 'Imagine';

/**
 * Table
 *
 * @var boolean|string
 */
	public $useTable = false;

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Imagine.Imagine'
	);

	public function schema($schema = []) {
		return [
			'id' => ['type' => 'integer'],
			'title' => ['type' => 'string', 'null' => false],
			'_constraints' => [
				'primary' => ['type' => 'primary', 'columns' => ['id']]
			]
		];
	}
}