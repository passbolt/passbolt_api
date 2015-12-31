<?php

for ($i = 0; $i < 10; $i++) {
	echo 'hello';
}

if ($i < 10) {
	echo 'hello2';
} elseif ($i > 100) {
	echo 'i > 100';
}

while (false) {
	echo 'false';
}

do {
	echo 'dowhile test';
} while (false);
