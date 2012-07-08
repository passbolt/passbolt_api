<?php
class Category extends AppModel {
    public $name = 'Category';
    public $actsAs = array('Tree');
	
	public $validate = array(
        'id' => array(
            'uuid' => array(
                'rule'     => 'uuid',
                'message'  => 'UUID must be in correct format'
            )
        ),
        'name' => array(
            'alphaNumeric' => array(
                'rule'     => '/^.{2,50}$/i',
                'required' => true,
                'message'  => 'Alphabets, numbers, - and _ only'
            )
        ),
        'parent_id' => array(
        	'exist' => array(
	            'rule'    => array('parentExists', null),
	            'allowEmpty' => true,
	            'message' => 'The parent provided doesnt exist'
	        ),
	        'uuid' => array(
                'rule'     => 'uuid',
                'allowEmpty' => true,
                'required' => false,
                'message'  => 'UUID must be in correct format'
            )
		),
		'position' => array(
        	'number' => array(
	            'rule'    => 'numeric',
	            'message' => 'The position must be a number'
	        )
		)
    );
	
	/**
	 * Check if a category with same id exists
	 */
	public function parentExists($check) {
		if($check['parent_id'] == null){
			 return true;
		}
		else{
	        $exists = $this->find('count', array(
	            'conditions' => array('Category.id'=>$check['parent_id']),
	      		'recursive' => -1
	        ));
	        return $exists > 0;
		}
    }
	
	/**
	 * Converts listed elements into a proper nested tree
	 * @param : $list, the list as it is returned by find() or children()
	 * @return : Nested objects representing the category :
	 * category{
	 *    id : char[36]
	 * 	  name:string
	 *    parent_id:int
	 *    position:int
	 * 	  children:categories array
	 * }
	 */
	public function list2Tree($categories){
		$stack = array();
		$clones = $categories;
		
		foreach ($categories as $i => $cat) {
			$clones[$i] = $clones[$i]['Category']; // remove the 'Category' level
			unset($clones[$i]['Category']); 
			$clones[$i]['children'] = array(); // add the children row
			unset($clones[$i]['lft'], $clones[$i]['rght']);
			$categories[$i]['Category']['copyref'] = & $clones[$i];
			
			while ($stack && !$this->isChild($cat, $stack[count($stack) - 1])) { // if element is not a child of the stack
				array_pop($stack); // we remove previous stack from the array
			}
			
			if(!$this->isTopLevelElement($cat, $categories)){
				$stack[count($stack) - 1]['Category']['copyref']['children'][] = &$clones[$i];
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
			if($this->isTopLevelElement($r, $clones, 'json')){
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
	public function isChild($elt, $parent){
		return ($elt['Category']['rght'] < $parent['Category']['rght']);
	}
	
	/**
	 * Check if an element is a leaf (no more children)
	 * @param $category, the category
	 * @return true if the category is a leaf. false otherwise.
	 */
	public function isLeaf($category){
		if($category['Category']['lft'] + 1 == $category['Category']['rght']){
			return true;
		}
		return false;
	}
	
	/**
	 * Check if an element is at the top level of the given branch
	 * @param $objectType, the type of object given, whether a default cakePHP object or a Json converted one : 'default' or 'json'
	 */
	public function isTopLevelElement($category, $categories, $objectType='default'){
		$parent_id = ($objectType=='default' ? $category['Category']['parent_id'] : $category['parent_id']);
		foreach($categories as $c){
			$eltId = ($objectType=='default' ? $c['Category']['id'] : $c['id']);
			if($eltId == $parent_id){
				return false;
			}
		}
		return true;
	}
}
