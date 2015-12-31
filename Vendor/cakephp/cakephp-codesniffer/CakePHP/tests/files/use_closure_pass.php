<?php

$foo = 'bar';
$bar = 'foo';

$zum = function () use ($foo, $bar) {
	return $foo;
};
