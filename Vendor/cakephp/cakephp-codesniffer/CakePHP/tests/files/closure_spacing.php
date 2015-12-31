<?php
$derp = 'Bryan Crowe';

$pass = function ($one, $two, $three) use ($derp) {
	echo $derp;
};

$passArray = array(
	'hello' => 'Beakman',
	'whatsup' => function ($one, $two) use ($derp) {
		echo $one . $two;
	}
);

$fail = function($one, $two, $three) use ($derp) {
	echo $derp . $one;
};

$failTooManySpaces = array(
	'hello' => 'Beakman',
	'whatsup' => function  ($one, $two) use ($derp) {
		echo $one . $two;
	}
);
