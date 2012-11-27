<?php
/**
 * ApiPackage Model Works with Package Strings in the API docs
 *
 * 
 *
 * PHP versions 4 and 5
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
 * @subpackage    api_generator.models
 * @since         ApiGenerator v 0.5
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('ApiGeneratorAppModel', 'ApiGenerator.Model');

class ApiPackage extends ApiGeneratorAppModel {
/**
 * name
 *
 * @var string
 **/
	public $name = 'ApiPackage';
/**
 * actsAs
 *
 * @var array
 **/
	public $actsAs = array(
		'Tree'
	);
/**
 * hasMany assocs
 *
 * @var array
 **/
	public $hasMany = array(
		'ApiClass' => array(
			'className' => 'ApiGenerator.ApiClass',
			'foreignKey' => 'api_package_id',
		),
		'ChildPackage' => array(
			'className' => 'ApiGenerator.ApiPackage',
			'foreignKey' => 'parent_id',
		)
	);
/**
 * belongsTo assocs
 *
 * @var string
 **/
	public $belongsTo = array(
		'ParentPackage' => array(
			'className' => 'ApiGenerator.ApiPackage',
			'foreignKey' => 'parent_id',
		)
	);
/**
 * get the package index tree.
 *
 * @return array Array of nested packages.
 **/
	public function getPackageIndex() {
		return $this->find('threaded', array(
			'recursive' => -1
		));
	}
/**
 * Parse the Package strings out of a docBlock from a ClassDocumentor/FunctionDocumentor.
 *
 * @param array $docBlock Array of doc block information
 * @return array Array of packages found in the order they were found.
 * @throws InvalidArugmentException
 **/
	public function parsePackage($docBlock) {
		if (empty($docBlock['tags']['package']) && empty($docBlock['tags']['subpackage'])) {
			throw new InvalidArgumentException('Missing package/subpackage keys in $docBlock');
		}
		$packages = array();
		foreach (array('package', 'subpackage') as $key) {
			if (isset($docBlock['tags'][$key])) {
				$newPackages = array_map('trim', explode('.', $docBlock['tags'][$key]));
				$packages = array_merge($packages, $newPackages);
			}
		}
		return array_values(array_unique($packages));
	}
/**
 * Updates the package tree with new entries if they exist.
 * Requires a full package array.
 *
 * @param array $packages Array of packages to check / insert into the tree.
 * @return boolean
 **/
	public function updatePackageTree($packages) {
		$parentId = null;
		$path = array();
		foreach ($packages as $package) {
			$slug = $this->_makeSlug($package);
			$path[] = $slug;
			$existing = $this->findBySlugAndParentId($slug, $parentId, null, null, -1);
			if ($existing) {
				$parentId = $existing['ApiPackage']['id'];
				continue;
			}
			$new = array(
				'ApiPackage' => array(
					'parent_id' => $parentId,
					'path' => $this->makePath($path),
					'slug' => $slug,
					'name' => $package
				)
			);
			$this->create($new);
			if (!$this->save()) {
				return false;
			}
			$parentId = $this->id;
		}
		return $parentId;
	}
/**
 * Find The last package's id value.
 *
 * @return mixed Id of last package.
 **/
	public function findEndPackageId($packages) {
		$lastPackage = array_pop($packages);
		$last = $this->findBySlug($this->_makeSlug($lastPackage));
		return $last['ApiPackage']['id'];
	}
/**
 * Make a package path.
 *
 * @param mixed $path Package path
 * @return string
 **/
 	public function makePath($path){
		return implode('/', $path);
	}

}