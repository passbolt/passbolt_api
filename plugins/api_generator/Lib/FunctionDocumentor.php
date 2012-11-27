<?php

App::import('Lib', 'ApiGenerator.DocblockTools');

/**
 * Function Documentor Class
 *
 * Used for parsing and extracting documentation and introspecting on functions
 *
 * PHP 5.2+
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2008-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2008-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org
 * @package       api_generator
 * @subpackage    api_generator.vendors
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class FunctionDocumentor extends ReflectionFunction {
/**
 * Information about the function
 *
 * @var array
 **/
	public $info;
/**
 * Params the function has
 *
 * @var string
 **/
	public $params;
/**
 * get General information about the function 
 * doc block, declared line/file etc.
 *
 * @return array
 **/
	public function getInfo() {
		$info = array(
			'name' => $this->getName(),
			'comment' => $this->_parseComment($this->getDocComment()),
			'declaredInFile' => $this->getFileName(),
			'startLine' => $this->getStartLine(),
			'endLine' => $this->getEndLine(),
			'internal' => $this->isInternal(), 
		);
		$this->info = $info;
		$this->info['signature'] = DocblockTools::makeFunctionSignature($this);
		return $this->info;
	}
/**
 * Get all the information for each parameter the function has
 *
 * @return array
 **/
	public function getParams() {
		$params = parent::getParameters();
		if (!isset($this->info['comment']['tags']['param'])) {
			$this->getInfo();
		}
		foreach ($params as $param) {
			$type = $description = null;
			if (isset($this->info['comment']['tags']['param'][$param->name])) {
				extract($this->info['comment']['tags']['param'][$param->name]);
			}
			$this->params[$param->name] = array(
				'optional' => $param->isOptional(),
				'default' => null,
				'position' => $param->getPosition(),
				'type' => $type,
				'comment' => $description,
				'hasDefault' => $param->isDefaultValueAvailable(),
				'default' => null
			);
			if ($param->isDefaultValueAvailable()) {
				$this->params[$param->name]['default'] = $param->getDefaultValue();
			}
		}
		return $this->params;
	}
/**
 * getAll docs for the current function documentor
 *
 * @return object
 **/
	public function getAll() {
		$this->getInfo();
		$this->getParams();
	}
/**
 * _parseComment
 *
 * @param string $comment Comment string to parse
 * @return string
 **/
	protected function _parseComment($comment) {
		return DocblockTools::parseDocBlock($comment);
	}
}