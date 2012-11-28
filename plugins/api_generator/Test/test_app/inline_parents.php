<?php
interface SomeOtherInterface {
	const FOOO = 'foo';
}
class BazTestFile implements Countable, SomeOtherInterface {
	public function count() {

	}
}
class BarTestFile extends BazTestFile {
	
}
class FooBarTestFile extends BarTestFile {
	
}
