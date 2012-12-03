<?php
/**
 * Category Schema
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Config.Schema.categories
 * @since        version 2.12.11
 */
App::uses('Category', 'Model');
App::uses('Resource', 'Model');
App::uses('CategoryResource', 'Model');

class CategorySchema {

	public function init() {
		$this->Category = ClassRegistry::init('Category');
		$this->Resource = ClassRegistry::init('Resource');
		$this->CategoryResource = ClassRegistry::init('CategoryResource');
		$this->insertCategories($this->_getDefaultCategories());
	}

	public function insertCategories ($categories, $parentCategory=null) {
		foreach ($categories as $categoryId => $subCategories) {
			// Insert Category
			if ($categoryId != 'Resources') {
				$this->Category->create();
				$category = $this->Category->save(array(
					'Category' => array(
						'name' => $categoryId,
						'parent_id' => isset($parentCategory) ? $parentCategory['Category']['id'] : null
					)
				));
				$this->insertCategories ($subCategories, $category);
			} else {
				$resources = $subCategories;
				foreach ($resources as $value) {
					$resource = null;
					if (!($resource = $this->Resource->findByName($value['Resource']['name']))) {
						$this->Resource->create();
						$resource = $this->Resource->save($value);
					}
					$this->CategoryResource->create();
					$this->CategoryResource->save(array(
						'CategoryResource' => array( 'category_id' => $parentCategory['Category']['id'], 'resource_id' => $resource['Resource']['id'] )
					));
				}
			}
		}
	}

	protected function _getDefaultCategories() {
		$categories = array (
			'Bolt Softwares Pvt. Ltd.' => array(
				'administration' => array(
					'accounts' => array(
						'Resources' => array(
							array('Resource' => array('id' => '509bb871-b964-48ab-94fe-fb098cebc04d','name' => 'bank password', 'username' => 'passbolt', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' )),
						)
					),
					'marketing' => array(
						'Resources' => array(
							array('Resource' => array('id' => '509bb871-5168-49d4-a676-fb098cebc04d', 'name' => 'facebook account', 'username' => 'passbolt', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' )),
						)
					),
					'hr' => array(
						'Resources' => array(
							array('Resource' => array('name' => 'salesforce account', 'username' => 'passbolt', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' )),
						)
					),
					'misc' => array(
						'Resources' => array(
							array('Resource' => array('name' => 'tetris license', 'username' => 'passbolt', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' )),
						)
					)
				),
				'projects' => array(
					'cakephp' => array(
						'cp-project1' => array(
							'Resources' => array(
								array('Resource' => array( 'name' => 'cpp1-pwd1', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' )),
								array('Resource' => array( 'name' => 'cpp1-pwd2', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' ))
							)
						),
						'cp-project2' => array(
							'Resources' => array(
								array('Resource' => array( 'name' => 'cpp2-pwd1', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' )),
								array('Resource' => array( 'name' => 'cpp2-pwd2', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' ))
							)
						),
						'cp-project3' => array()
					),
					'drupal' => array(
						'd-project1' => array(
							'Resources' => array(
								array('Resource' => array( 'name' => 'dp1-pwd1', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' )),
							)
						),
						'd-project2' => array()
					),
					'others' => array(
						'o-project1' => array(
							'Resources' => array(
								array('Resource' => array( 'name' => 'op1-pwd1', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' )),
								array('Resource' => array( 'name' => 'op1-pwd2', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' )),
								array('Resource' => array( 'name' => 'shared resource', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' ))
							)
						),
						'o-project2' => array(
							'Resources' => array(
								array('Resource' => array( 'name' => 'shared resource')),
							)
						)
					)
				),
			)
		);
		return $categories;
	}
}
