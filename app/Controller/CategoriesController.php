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
    public $name = 'Categories';

    public function index() {
        //$data = $this->Category->generateTreeList(null, null, null, '-');
        $data = $this->Category->children('1');
        debug($data); die;
    }
	
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
	public function get($id=1, $children=false){
		$children = true;
		
		$category = $this->Category->findById($id);
		if($children == true){
			$children = $this->Category->children($id);
			$tree = array_merge(array(0=>$category), $children);
			$tree = $this->Category->list2Tree($tree);
			$this->set('json', $tree);
		}
		$this->render('/json/json');
	}
	
	public function a2Tree($array){
		
	}
	
	/**
	 * get the children for a corresponding category
	 * @param $id, the id of the parent category
	 * @return all the children in json objects
	 */
	public function getChildren($id){
		
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
}
