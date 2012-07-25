<?php
/**
 * Categories controller
 * This file will define how categories are managed
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Controller.CategoriesController
 * @since         version 2.12.7
 */
class CategoriesController extends AppController {

  /**
   * get a category
   * Renders a json object with the nested categories
   * @param uuid $id the id of the category
   * @param bool $children whether or not we want the children returned
   * @return void
   */
  public function get($id=null, $children=false) {
    if (!isset($id)) {
      $this->Message->error(__('The category id is missing'));
    } else {
      $categoryModel = Common::getModel('Category');
      $fields = $categoryModel::getFindFields('get');
      $category = $this->Category->findById($id);
      if ($category) {
        if ($children == true) {
          $category = $this->Category->findById($id);
          $conditions = array('conditions'=>array( 
            'Category.lft >='=>$category['Category']['lft'] , 
            'Category.rght <='=>$category['Category']['rght']
            ),
            'order' => 'lft ASC'
          );
          $body = $this->Category->find('threaded', array_merge($conditions, $fields));
        }
        else {
          $body = $this->Category->findById($id, $fields['fields']);
        }
        $this->Message->success();
        $this->Message->appendBody($body);
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
  public function getChildren($id=null) {
    if (!isset($id)) {
      $this->Message->error(__('The category id is missing'));
    } else {
      $category = $this->Category->findById($id);
      if ($category) {
        $categoryModel = Common::getModel('Category');
        $fields = $categoryModel::getFindFields('getChildren');
        $conditions = array('conditions'=>array( 
            'Category.lft >'=>$category['Category']['lft'] , 
            'Category.rght <'=>$category['Category']['rght']
            ),
            'order' => 'lft ASC'
          );
        $this->Message->success();
        $this->Message->appendBody($this->Category->find('threaded', array_merge($conditions, $fields)));
      }
      else {
        $this->Message->error(__('The category doesn\'t exist'));
      } 
    }
  }

  /**
   * Add a category inside the tree, and return a success object with the added category
   * @param $parent_id, the parent id of the category
   * @param $name, the name of the category
   * @param $position (optional), the position of the category from the parent (Counting starts from 1, not from 0)
   * if position is not available (example : position 2 when there are no children, the category will be inserted in last)
   * if position is 0, it will not be handled. Count starts from 1.
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
    $catpost = $this->request->data;
    $this->Category->set($catpost);

    // check if the data is valid
    if (!$this->Category->validates()) {
      $this->Message->error(__('Could not validate category data'));
      return;
    }

    // trye to save
    $this->Category->create();
    $category = $this->Category->save($catpost);
    if ($category === false) {
      $this->Message->error(__('The category could not be saved'));
      return;
    }

    // Manage the position
    if (isset($catpost['Category']['position']) && $catpost['Category']['position'] > 0) {
      $nbChildren = $this->Category->childCount($catpost['Category']['parent_id'], true);
      if ($catpost['Category']['position'] < $nbChildren) {
        $steps = ($catpost['Category']['position'] == 1 ? true : $nbChildren - $catpost['Category']['position']);
        $this->Category->moveUp($category['Category']['id'], $steps);
      }
    }
    $categoryModel = Common::getModel('Category');
    $fields = $categoryModel::getFindFields('add');
    $this->Message->success(__('The category was sucessfully added'));
    $this->Message->appendBody($this->Category->findById($category['Category']['id'], $fields['fields']));

  }

  /**
   * Delete a category in the tree
   * @param $id, the Category id
   * @return void
   */
  public function delete($id=null) {
    if (!isset($id)) {
      $this->Message->error(__('The category id is missing'));
    } else {
      if (!$this->Category->delete($id)) {
        $this->Message->error(__('The category could not be deleted.'));
      } else {
        $this->Message->success(__('The category was succesfully deleted'));
      }
    }
  }

  /**
   * Rename a category
   * @param $id, the id of the category
   * @param $name, the name of the category
   * @return void
   */
  public function rename($id=null, $name="") {
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
  public function move($id=null, $position=null, $parent_id=null) {
    if (!isset($id)) {
      $this->Message->error(__('The category id is not provided'));
    } else {
      $category = $this->Category->findById($id);
      if ($category) {
        // First, manage the parent
        $parent_id = ($parent_id == null ? $category['Category']['parent_id'] : $parent_id);
        if ($category['Category']['parent_id'] != $parent_id) {
          $category['Category']['parent_id'] = $parent_id;
          $category = $this->Category->save($category);
          if (!$category) {
            $this->Message->error(__('The category could not be moved'));
            return;
          }
        }
        // then, manage the position
        if ($position >= 0) {
          $nbChildren = $this->Category->childCount($parent_id, true);
          // if the position is first one or last one
          if($position == 1) {
            $result = $this->Category->moveUp($id, true);
          }
          elseif($position >= $nbChildren) {
            $result = $this->Category->moveDown($id, true);
          }
          else {
            $currentPosition = $this->Category->getPosition($id);
            $steps = $currentPosition - $position;
            echo "position = $currentPosition, steps = $steps";
            if($steps > 0) {
              $result = $this->Category->moveUp($id, $steps);
            }
            else {
              $result = $this->Category->moveDown($id, -($steps));
            }
          }
          if($result)
            $this->Message->success(__('The category was sucessfully moved'));
          else
            $this->Message->error(__('The category could not be moved'));
          return;
        }
        else{
          $this->Message->error(__('Wrong position. Must be greated than 0'));
          return;
        }
      }
      else{
        $this->Message->error(__('The category doesnt exist'));
        return;
      }
    }
  }
  
  /**
   * Set the type of a category
   * @param $id, the id of the category
   * @param $type, the type
   * @return 1 if success, 0 if failure
   */
  public function setType($id=null, $type=null) {
    
  }
}
