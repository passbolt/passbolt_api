<?php
/**
 * Categories controller.
 *
 * This file will define how categories are managed
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller.CategoriesController
 * @since         Passbolt v2.12.7
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 
class CategoriesController extends AppController {
  	
	/**
	 * get a category
	 * Renders a json object with the nested categories
	 * @param uuid $id the id of the category
	 * @param bool $children whether or not we want the children returned
	 * @return void
	 */
	public function get($id, $children=false) {
		if (!isset($id)) {
			$this->Message->error("Id is not provided");
		} else {
			$category = $this->Category->findById($id);
			if ($category) {
				if ($children == true) {
					$children = $this->Category->children($id);
					$tree = array_merge(array(0=>$category), $children);
					$tree = $this->Category->results2Tree($tree, array('fields'=>Category::getFindFields('get'), 'position'=>true));
					pr($tree[0]);
					$this->set('data', $tree[0]);
          $this->Message->success("");
				}
				else {
				  $this->set("data", $category);
          $this->Message->success("");
				}
			}
      else {
        $this->Message->error("Category doesn't exist");
      }
		}
	}
	
	
	/**
	 * get the children for a corresponding category
	 * @param $id, the id of the parent category
	 * @return all the children in json objects
	 */
	public function getChildren($id) {
		if (!isset($id)) {
			$this->Message->error("Id is not provided");
		} else {
			$category = $this->Category->findById($id);
			if ($category) {
				$children = $this->Category->children($id);
				$childrenres = array();
				$childrenres[0] = array();
				$childrenres[0][] = $this->Category->results2Tree($children, array('fields'=>Category::getFindFields('getChildren'), 'position'=>true));
				$this->set('data', $childrenres);
        $this->Message->success("");
			}
      else {
        $this->Message->error("Category doesn't exist");
      } 
		}
	}
	
	/**
	 * add a category inside the tree
	 * @param $parent_id, the parent id of the category
	 * @param $name, the name of the category
	 * @param $position (optional), the position of the category from the parent (Counting starts from 1, not from 0)
	 * @param $type (optional), the type of the category (default is set is missing)
	 * @return the added category object is success, 0 if failure
	 */
	public function add() {
		//$cat = array("name"=>'testchildrengoa', "parent_id"=>'4ff6111b-efb8-4a26-aab4-2184cbdd56cb', "position"=>'1', "type"=>'default');
		$cat = $this->request->data['Post'];
		$category = array('Category'=>$cat);
		
		$this->Category->create();
		if ($category = $this->Category->save($category)) {
			// Manage the position
			if (isset($category['Category']['position']) && $category['Category']['position'] > 0) {
				$nbChildren = $this->Category->childCount($category['Category']['parent_id']);
				if ($category['Category']['position'] < $nbChildren) {
					$this->Category->moveUp($category['Category']['id'], $nbChildren - $category['Category']['position']);
				}
			}
			$this->set('data', array('status'=>1));
		} else {
			/*$errors = $this->Category->invalidFields();
			pr($errors);*/
			$this->set('data', array('status'=>0, 'data'=>array('error'=>'error in saving the data')));
		}
	}

	/**
	 * Delete a category in the tree
	 * @param $id, the Category id
	 * @return, 1 if success, 0 otherwise
	 */
	public function delete($id) {
		if (!isset($id)) {
			// Do something - Exception ?
		} else {
			if (!$this->Category->delete($id)) {
				$this->set('data', array('status'=>0, 'data'=>array('error'=>'error in deleting the category')));
			} else {
				$this->set('data', array('status'=>1));
			}
		}
	}

	/**
	 * Rename a category
	 * @param $id, the id of the category
	 * @param $name, the name of the category
	 * @return 1 if success, 0 otherwise
	 */
	public function rename($id, $name) {
		if (!isset($id)) {
			$this->set('data', array('status'=>0, 'data'=>array('error'=>'ID not provided')));
		} else {
			$category = $this->Category->findById($id);
			if ($category) {
				$category['Category']['name'] = $name;
				if ($this->Category->save($category)) {
					$this->set('data', array('status'=>1));
				} else {
					$this->set('data', array('status'=>0, 'data'=>array('error'=>'database error')));
				}
			} else {
				$this->set('data', array('status'=>0, 'data'=>array('error'=>'the category doesnt exist')));
			}
		}
	}

	/**
	 * Move a category in the tree
	 * @param $id, the id of the category to move
	 * @param $position, the position among the sieblings
	 * @param $parent_id, the new parent
	 */
	public function move($id, $position, $parent_id=null) {
		if (!isset($id)) {
			$this->set('data', array('status'=>0, 'data'=>array('error'=>'ID not provided')));
		} else {
			$category = $this->Category->findById($id);
			if ($category) {
				// First, manage the parent
				if ($parent_id != null && $category['Category']['parent_id'] != $parent_id) {
					$category['Category']['parent_id'] = $parent_id;
					$category = $this->Category->save($category);
					if (!$category) {
						$this->set('data', array('status'=>0, 'data'=>array('error'=>'database error')));
						return;
					}
				}
				// then, manage the position
				if ($position > 0) {
					$nbChildren = $this->Category->childCount($parent_id);
					if ($position < $nbChildren) {
						if ($this->Category->moveUp($id, $nbChildren - $position)) {
							$this->set('data', array('status'=>1));
						}
					}
				}
			}
		}
	}
	
	/**
	 * Set the type of a category
	 * @param $id, the id of the category
	 * @param $type, the type
	 * @return 1 if success, 0 if failure
	 */
	public function setType($id, $type) {
		
	}

  /* @todo this should be moved to fixture */
	public function populate() {
		$this->layout = 'html5';
		 
		/*	
		Goa
		-Hippies places
		--Anjuna
		---UV Bar
		---Curlie's
		----Dance on the beach
		----Play pool table
		---The Hippies
		--Palolem
		-drug places
		--Calangute
		---Le Nepalais
		-Disco places
		--Baga
		--Mapusa
		 */
		$goa = $this->Category->save(array('Category'=>array('name'=>'Goa')));
		$this->Category->create();
		$hippies = $this->Category->save(array('Category'=>array('name'=>'Hippies places', 'parent_id'=>$goa['Category']['id'])));
		$this->Category->create();
		$anjuna = $this->Category->save(array('Category'=>array('name'=>'Anjuna', 'parent_id'=>$hippies['Category']['id'])));
		$this->Category->create();
		$uvbar = $this->Category->save(array('Category'=>array('name'=>'UV Bar', 'parent_id'=>$anjuna['Category']['id'])));
		$this->Category->create();
		$curlies = $this->Category->save(array('Category'=>array('name'=>'Curlie\'s', 'parent_id'=>$anjuna['Category']['id'])));
		$this->Category->create();
		$thehippies = $this->Category->save(array('Category'=>array('name'=>'The Hippies', 'parent_id'=>$anjuna['Category']['id'])));
		$this->Category->create();
		$beach = $this->Category->save(array('Category'=>array('name'=>'Dance on the beach', 'parent_id'=>$curlies['Category']['id'])));
		$this->Category->create();
		$pool = $this->Category->save(array('Category'=>array('name'=>'Play pool table', 'parent_id'=>$curlies['Category']['id'])));
		$this->Category->create();
		$drug = $this->Category->save(array('Category'=>array('name'=>'Drug places', 'parent_id'=>$goa['Category']['id'])));
		$this->Category->create();
		$disco = $this->Category->save(array('Category'=>array('name'=>'Disco places', 'parent_id'=>$goa['Category']['id'])));
		$this->Category->create();
		$calangute = $this->Category->save(array('Category'=>array('name'=>'Calangute', 'parent_id'=>$drug['Category']['id'])));
		$this->Category->create();
		$nepalais = $this->Category->save(array('Category'=>array('name'=>'Le Nepalais', 'parent_id'=>$calangute['Category']['id'])));
		$this->Category->create();
		$baga = $this->Category->save(array('Category'=>array('name'=>'Baga', 'parent_id'=>$disco['Category']['id'])));
		$this->Category->create();
		$mapusa = $this->Category->save(array('Category'=>array('name'=>'Mapusa', 'parent_id'=>$disco['Category']['id'])));
	}
}
