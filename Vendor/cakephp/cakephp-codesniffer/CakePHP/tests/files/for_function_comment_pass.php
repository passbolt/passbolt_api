<?php
class Foo {

/**
 * [doThing description]
 *
 * @param string $foo Foo
 * @return void
 */
	public function doThing($foo) {
	}

}

class Bar extends Foo {

/**
 * {@inheritDoc}
 */
	public function doThing($foo) {
	}

}
