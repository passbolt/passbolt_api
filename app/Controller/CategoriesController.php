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
      $this->Message->error(__('The category id is missing'));
    } else {
      $category = $this->Category->findById($id);
      if ($category) {
        if ($children == true) {
          $children = $this->Category->children($id);
          $tree = array_merge(array(0=>$category), $children);
          $tree = $this->Category->list2Tree($tree);
          $this->set('data', $tree[0]);
          $this->Message->success($tree[0]);
        }
        else {
          $this->set('data', $category['Category']);
          $this->Message->success();
        }
      }
      else {
        $this->Message->error(__('The category doesn\'t exist'));
      }
    }
  }
  
  
  /**
   * get the children for a corresponding category
   * @param $id, the id of the parent category
   * @return void
   */
  public function getChildren($id) {
    if (!isset($id)) {
      $this->Message->error(__('The category id is missing'));
    } else {
      $category = $this->Category->findById($id);
      if ($category) {
        $children = $this->Category->children($id);
        $childrenres = array();
        $childrenres[0] = array();
        $childrenres[0][] = $this->Category->list2Tree($children);
        $this->set('data', $childrenres);
        $this->Message->success();
      }
      else {
        $this->Message->error(__('Category doesn\'t exist'));
      } 
    }
  }

  /**
   * Add a category inside the tree
   * @param $parent_id, the parent id of the category
   * @param $name, the name of the category
   * @param $position (optional), the position of the category from the parent (Counting starts from 1, not from 0)
   * @param $type (optional), the type of the category (default is set is missing)
   * @return void
   */
  public function add() {
    // @todo should be moved to test/fixtures
    // $cat = array("name"=>'testchildrengoa', "parent_id"=>'4ff6111b-efb8-4a26-aab4-2184cbdd56cb', "position"=>'1', "type"=>'default');
    
    // check the HTTP request method
    if (!$this->request->is('post')) {
      $this->Message->error(__('Invalid request method, should be POST'));
      return;
    }

    // check if data was provided
    if (!isset($this->request->data)) {
      $this->Message->error(__('No data were provided'));
      return;
    }

    // set the data for validation and save
    $category = $this->request->data;
    $this->Category->set($category);

    // check if the data is valid
    if (!$this->Category->validates()) {
      $this->Message->error(__('Could not validate category data'));
      return;
    }

    // trye to save
    $category = $this->Category->save($category);
    if ($category === false) {
      $this->Message->error(__('The category could not be saved'));
      return;
    }

    // Manage the position
    if (isset($category['Category']['position']) && $category['Category']['position'] > 0) {
      $nbChildren = $this->Category->childCount($category['Category']['parent_id']);
      if ($category['Category']['position'] < $nbChildren) {
        $this->Category->moveUp($category['Category']['id'], $nbChildren - $category['Category']['position']);
      }
    }

    $this->Message->success(__('The category was sucessfully added'));

  }

  /**
   * Delete a category in the tree
   * @param $id, the Category id
   * @return void
   */
  public function delete($id) {
    if (!isset($id)) {
      $this->Message->error(__('The category id is missing'));
    } else {
      if (!$this->Category->delete($id)) {
        $this->Message->error(__('The category could not be deleted.'));
      } else {
        $this->Message->sucess();
      }
    }
  }

  /**
   * Rename a category
   * @param $id, the id of the category
   * @param $name, the name of the category
   * @return void
   */
  public function rename($id, $name) {
    if (!isset($id)) {
      $this->Message->error(__('The category id is not provided'));
    } else {
      $category = $this->Category->findById($id);
      if ($category) {
        $category['Category']['name'] = $name;
        if ($this->Category->save($category)) {
          $this->Message->success();
        } else {
          $this->Message->error(__('The category could not be saved'));
        }
      } else {
        $this->Message->error(__('The category could not be found'));
      }
    }
  }

  /**
   * Move a category in the tree
   * @param $id, the id of the category to move
   * @param $position, the position among the sieblings
   * @param $parent_id, the new parent
   * @return void
   */
  public function move($id, $position, $parent_id=null) {
    if (!isset($id)) {
      $this->Message->error(__('The category id is not provided'));
    } else {
      $category = $this->Category->findById($id);
      if ($category) {
        // First, manage the parent
        if ($parent_id != null && $category['Category']['parent_id'] != $parent_id) {
          $category['Category']['parent_id'] = $parent_id;
          $category = $this->Category->save($category);
          if (!$category) {
            $this->Message->error(__('The category could not be moved'));
            return;
          }
        }
        // then, manage the position
        if ($position > 0) {
          $nbChildren = $this->Category->childCount($parent_id);
          if ($position < $nbChildren) {
            if ($this->Category->moveUp($id, $nbChildren - $position)) {
              $this->Message->sucess(__('The category was sucessfully moved'));
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
