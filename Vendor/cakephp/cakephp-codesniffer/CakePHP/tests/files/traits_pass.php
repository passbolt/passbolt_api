<?php

use Cake\Last;
use Cake\More;

class Foo {

	use BarTrait;
	use FirstTrait {
		foo as bar;
		config as protected _config;
	}

}
