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
namespace Burzum\Imagine\Test\TestCase\Lib;

use Burzum\Imagine\Lib\ImageProcessor;
use Cake\Core\Plugin;
use Cake\TestSuite\TestCase;
use Burzum\Imagine\Lib\ImagineUtility;

class ImagineUtilityTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * testOperationsToString
 *
 * @return void
 */
	public function testOperationsToString() {
		$operations = array(
			'thumbnail' => array(
				'width' => 200,
				'height' => 150
			)
		);
		$result = ImagineUtility::operationsToString($operations);
		$this->assertEquals($result, '.thumbnail+width-200+height-150');
	}

/**
 * testHashImageOperations
 *
 * @return void
 */
	public function testHashImageOperations() {
		$operations = array(
			'SomeModel' => array(
				't200x150' => array(
					'thumbnail' => array(
						'width' => 200,
						'height' => 150
					)
				)
			)
		);
		$result = ImagineUtility::hashImageOperations($operations);
		$this->assertEquals($result, [
			'SomeModel' => ['t200x150' => '38b1868f']
		]);
	}

/**
 * testGetImageOrientation
 *
 * @return void
 */
	public function testGetImageOrientation() {
		$image = Plugin::path('Burzum/Imagine') . 'tests' . DS . 'Fixture' . DS . 'titus.jpg';
		$result = ImagineUtility::getImageOrientation($image);
		$this->assertEquals($result, 0);

		$image = Plugin::path('Burzum/Imagine') . 'tests' . DS . 'Fixture' . DS . 'Portrait_6.jpg';
		$result = ImagineUtility::getImageOrientation($image);
		$this->assertEquals($result, -90);

		try {
			ImagineUtility::getImageOrientation('does-not-exist');
			$this->fail('No \RuntimeException thrown as expected!');
		} catch (\RuntimeException $e) {
		}
	}
}
