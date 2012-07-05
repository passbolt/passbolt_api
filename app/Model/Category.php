<?php
class Category extends AppModel {
    public $name = 'Category';
    public $actsAs = array('Tree');
	
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
			
			if($i >0){
				$stack[count($stack) - 1]['Category']['copyref']['children'][] = &$clones[$i];
			}
			$stack[] = $categories[$i];
		} 

		if (empty($clones[0])) {
			return array();
		}
		return $clones[0];
	}
	
	/**
	 * Check if an element is a child of a parent
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
}
