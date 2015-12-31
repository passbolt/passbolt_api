<?php

namespace CakePHP;

use Other\Crap;
use Other\Error as OtherError;

class Throws {

/**
 * Test throws
 *
 * @throws Exception
 * @throws CakePHP\Boom
 * @throws CakePHP\Error\Boom
 * @throws Other\Crap
 * @throws Other\Error\Issue
 * @return void
 */
	public function test() {
		switch ($a) {
			case 1:
				throw new Boom();
			case 2:
				throw new Error\Boom();
			case 3:
				throw new OtherError\Issue();
			case 4:
				throw new Crap();
			default:
				throw new \Exception();
		}
	}

}
