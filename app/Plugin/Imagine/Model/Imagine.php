<?php
/**
 * Copyright 2011-2014, Florian Krämer
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * Copyright 2011-2014, Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Imagine extends AppModel {

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

}