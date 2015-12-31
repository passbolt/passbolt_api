<?php

class VariablenamePass {

	public $passing;

	public $passingPublic = 'defined';

	protected $_underScoredStart = 'OK';

	protected $_underScored;

	private $__doubleUnderscore = 'applications';

	public static $publicStatic = true;

	protected static $_protectedStatic = true;

	private static $__privateStatic = true;

/**
 * [setVariables description]
 *
 * @return void
 */
	public function setVariables() {
		$this->passingPublic = 'changed';
		$this->_underscored = 'has value now';
		$this->__doubleUnderscore = 'not recommended';
	}

/**
 * [setStatics description]
 *
 * @return void
 */
	public static function setStatics() {
		self::$publicStatic = true;
		self::$_protectedStatic = true;
		self::$__privateStatic = true;
	}
}