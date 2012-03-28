<?php 
class NestedTreeHelper extends AppHelper{
	function getCategories($key, $categories, &$mainList) {
	    $result = '<ul>';
	    foreach($categories as $catKey => $name) {
	        $result .= $this->getCategory($catKey, $name, $mainList);
	    }
	    $result .= '</ul>';
	    return $result;
	}
	
	function getCategory($key, $value, &$mainList) {
	    $result = '<li id="'. $key .'"><a href="#">';
	    $result .= $value;
	    if(array_key_exists($key, $mainList)) {
	        $result .= $this->getCategories($key, $mainList[$key], $mainList);
	    }
	    $result .= '</a></li>';
	    return $result;
	}
}
?> 