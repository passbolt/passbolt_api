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

class ImageFixture extends CakeTestFixture {

/**
 * name property
 *
 * @var string
 */
	public $name = 'Image';

/**
 * fields property
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false));

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array('title' => 'First Image'),
		array('title' => 'Second Image'));
}
