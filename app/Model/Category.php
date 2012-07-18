<?php
/**
 * 
 * 
 * @package     app.Controller.UsersController
 * @copyright   Copyright 2012, ActionAid Association India 
 * @link        http://actionaid.org/india
 * @author      Remy Bertot / Kevin Muller
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Category extends AppModel {
  public $actsAs = array('Tree');

  /**
   * Constructor
   * @link http://api20.cakephp.org/class/app-model#method-AppModel__construct
   */
  public function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->setValidationRules();
  }

  /**
   * Set the validation rules upon context
   * @param 
   */
  function setValidationRules($context='default') {
    $this->validate = Category::getValidationRules($context);
    return true;
  }

  static function getValidationRules($context='default') {
    $rules = array(
      'id' => array(
        'uuid' => array(
          'rule'     => 'uuid',
          'message'  => __('UUID must be in correct format')
        )
      ),
      'name' => array(
        'alphaNumeric' => array(
          'rule'     => '/^.{2,64}$/i',
          'required' => true,
          'message'  => __('Alphanumeric only')
        )
      ),
      'parent_id' => array(
        'exist' => array(
          'rule'    => array('parentExists', null),
          'allowEmpty' => true,
          'message' => __('The parent provided does not exist')
        ),
        'uuid' => array(
          'rule'     => 'uuid',
          'allowEmpty' => true,
          'required' => false,
          'message'  => __('UUID must be in correct format')
        )
      ),
      'position' => array(
        'number' => array(
          'rule'    => 'numeric',
          'message' => __('The position must be a number')
        )
      )
    );

    /*
     * @todo a context switch if needed
     *
    switch ($context) {
      default:
        unset($rules['rule']);
      break;
    }
    */

    return $rules;
  }

  /**
   * Check if a category with same id exists
   */
  public function parentExists($check) {
    if ($check['parent_id'] == null) {
      return true;
    } else {
      $exists = $this->find('count', array(
        'conditions' => array('Category.id' => $check['parent_id']),
         'recursive' => -1
      ));
      return $exists > 0;
    }
  }
	
	/**
	 * Converts an array of categories into a proper nested tree
	 * @param array $categories, the list as it is returned by find() or children()
   * @param array $options the options.
   *        - array fields , the required fields (will remove unnecessary fields at the output)
   *        - bool position, whether or not to add a position field to specify the position of the categories among the sieblings
	 * @return : Nested objects representing the category :
	 * category{
	 *    id : char[36]
	 * 	  name:string
	 *    parent_id:int
	 *    position:int
	 * 	  children:categories array
	 * }
	 */
	public function results2Tree($categories, $options=null){
		$stack = array();
		$clones = $categories;
    
    // define which keys are extra to remove them later
    $extraFields = array();
    foreach($categories[0]['Category'] as $key=>$V){
      if(!in_array("Category.$key", $options['fields']['fields'])){
        $extraFields[] = $key;
      }
    }
		
		foreach ($categories as $i => $cat) {
			//$clones[$i] = $clones[$i]['Category']; // remove the 'Category' level
			//unset($clones[$i]['Category']); 
			$clones[$i]['Category']['children'] = array(); // add the children row
      // process here the format of the element so we keep only the required fields
      foreach($extraFields as $extraF){
        unset($clones[$i]['Category'][$extraF]);
      }
      
			$categories[$i]['Category']['copyref'] = & $clones[$i];
			
			while ($stack && !$this->isChild($cat, $stack[count($stack) - 1])) { // if element is not a child of the stack
				array_pop($stack); // we remove previous stack from the array
			}
			
			if(!$this->isTopLevelElement($cat, $categories)){
				$stack[count($stack) - 1]['Category']['copyref']['Category']['children'][] = &$clones[$i];
			}
			$stack[] = $categories[$i];
		} 

		if (empty($clones[0])) {
			return array();
		}
		// Put the final result in $res
		$res = array();
		// Place only the top level elements, and ignore the 
		foreach($clones as $k=>$r){
			if($this->isTopLevelElement($r, $clones)){
				$res[]=$r;
			}
		}
		return $res;
	}
	
	  /**
   * Check if an element is a child of a parent (not necessarily an immediate child. can be several levels below)
   * Useful when parsing an array of results
   * @param $elt, the element to check
   * @param $parent, the parent
   * @return true if element is a child, false otherwise
   */
  public function isChild($elt, $parent) {
    return ($elt['Category']['rght'] < $parent['Category']['rght']);
  }

  /**
   * Check if an element is a leaf (no more children)
   * @param $category, the category
   * @return true if the category is a leaf. false otherwise.
   */
  public function isLeaf($category){
    if ($category['Category']['lft'] + 1 == $category['Category']['rght']) {
      return true;
    }
    return false;
  }

  /**
   * Check if an element is at the top level of the given branch
   * @param $objectType, the type of object given, whether a default cakePHP object or a Json converted one : 'default' or 'json'
   */
  public function isTopLevelElement($category, $categories){
    $parent_id = $category['Category']['parent_id'];
    foreach ($categories as $c) {
      if ($c['Category']['id'] == $parent_id) {
        return false;
      }
    }
    return true;
  }
  
  /**
   * Return the list of field to fetch for given context
   * @param string $case context ex: login, activation
   * @return $condition array
   */
  static function getFindFields($case="get"){
    switch($case){
      case 'get':
      case 'getChildren':
        $fields = array(
          'fields' => array(
            'Category.id', 'Category.name', 'Category.parent_id'
          )
        );
      break;
      default:
        throw new exception('ERROR: Category::GetFindFields case undefined');
      break;
    }
    return $fields;
  }
}