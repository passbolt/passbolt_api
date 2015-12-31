<?php
	echo implode('', array_map('chr', array_map('hexdec' , array_filter(explode($delimiter, $string)))));
