<?php

class RandomTool {
	
	public static function randomString($length = 10) {
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
	
	public static function randomPermissionType() {
		$mask = array('0', '1', '3', '7', '15');
		return $mask[rand(0, 4)];
	}
}
