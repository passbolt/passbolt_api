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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 
class CategoriesController extends AppController {

  /* this should move to a test case */
  public function index() {
    //$data = $this->Category->generateTreeList(null, null, null, '-');
    $data = $this->Category->children('4ff6111b-efb8-4a26-aab4-2184cbdd56cb');
    debug($data); die;
  }

  /* this should move to a test case */
	public function newroot(){
		$data['Category']['parent_id'] = null;
		$data['Category']['name'] = 'root3';
		$this->Category->save($data);
	}
	
	/**
	 * get a category
	 * @param $id, the id of the category
	 * @param $children, whether or not we want the children returned
	 * @return : either the category or the whole tree in a transformed object {id, name, position, type}
	 */
	public function get($id, $children=false){
		if(!isset($id)){
			// Do something - Exception ?
		}
		else{
			$category = $this->Category->findById($id);
			if($category){
				if($children == true){
					$children = $this->Category->children($id);
					$tree = array_merge(array(0=>$category), $children);
					$tree = $this->Category->list2Tree($tree);
					$this->set('data', $tree);
				}
				else{
					$this->set('data', $category);
				}
			}
		}
	}
	
	
	/**
	 * get the children for a corresponding category
	 * @param $id, the id of the parent category
	 * @return all the children in json objects
	 */
	public function getChildren($id){
		if(!isset($id)){
			// Do something - Exception ?
		}
		else{
			$category = $this->Category->findById($id);
			if($category){
				$children = $this->Category->children($id);
				$childrenres = array();
				$childrenres[0] = array();
				$childrenres[0][] = $this->Category->list2Tree($children);
				$this->set('data', $childrenres);
			}
		}
	}
	
	/**
	 * add a category inside the tree
	 * @param $parent_id, the parent id of the category
	 * @param $name, the name of the category
	 * @param $position, the position of the category
	 * @param $type, the type of the category (default is set is missing)
	 * @return the added category object is success, 0 if failure
	 */
	public function add($parent_id, $name, $position, $type=null){
	
	}

	/**
	 * Delete a category in the tree
	 * @param $id, the Category id
	 * @return, 1 if success, 0 otherwise
	 */
	public function delete($id){
		
	}

	/**
	 * Rename a category
	 * @param $id, the id of the category
	 * @param $name, the name of the category
	 * @return 1 if success, 0 otherwise
	 */
	public function rename($id, $name){
		
	}
	
	/**
	 * Move a category in the tree
	 * @param $id, the id of the category to move
	 * @param $parent_id, the new parent
	 * @param $position, the position among the sieblings
	 */
	public function move($id, $parent_id, $position){
		
	}
	
	/**
	 * Set the type of a category
	 * @param $id, the id of the category
	 * @param $type, the type
	 * @return 1 if success, 0 if failure
	 */
	public function setType($id, $type){
		
	}
	
	public function populate(){
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
