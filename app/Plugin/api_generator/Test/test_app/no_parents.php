<?php
class ApiFileNoParent {
	
}

abstract class ApiFileNoParentTwo {
	function test() {
		ClassRegistry::init('ApiClass');
	}
}