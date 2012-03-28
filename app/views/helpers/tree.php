<?php 
/* SVN FILE: $Id: tree.php 205 2008-08-13 14:13:32Z ad7six $ */
/**
 * Tree Helper.
 *
 * Used the generate nested representations of hierarchial data
 *
 * PHP versions 4 and 5
 *
 * Copyright (c) 2008, Andy Dawson
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright            Copyright (c) 2008, Andy Dawson
 * @link                 www.ad7six.com
 * @package              cake-base
 * @subpackage           cake-base.app.views.helpers
 * @since                v 1.0
 * @version              $Revision: 205 $
 * @modifiedBy           $LastChangedBy: ad7six $
 * @lastModified         $Date: 2008-08-13 16:13:32 +0200 (Wed, 13 Aug 2008) $
 * @license              http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Tree helper
 *
 * Helper to generate tree representations of MPTT or recursively nested data
 */
class TreeHelper extends AppHelper {
/**
 * name property
 *
 * @var string 'Tree'
 * @access public
 */
    var $name = 'Tree';
/**
 * settings property
 *
 * @var array
 * @access private
 */
    var $__settings = array();
/**
 * typeAttributes property
 *
 * @var array
 * @access private
 */
    var $__typeAttributes = array();
/**
 * typeAttributesNext property
 *
 * @var array
 * @access private
 */
    var $__typeAttributesNext = array();
/**
 * itemAttributes property
 *
 * @var array
 * @access private
 */
    var $__itemAttributes = array();
/**
 * helpers variable
 *
 * @var array
 * @access public
 */
    var $helpers = array ('Html');
/**
 * Tree generation method.
 *
 * Accepts the results of
 *     find('all', array('fields' => array('lft', 'rght', 'whatever'), 'order' => 'lft ASC'));
 *     children(); // if you have the tree behavior of course!
 * or     findAllThreaded(); and generates a tree structure of the data.
 *
 * Settings (2nd parameter):
 *    'model' => name of the model (key) to look for in the data array. defaults to the first model for the current
 * controller. If set to false 2d arrays will be allowed/expected.
 *    'alias' => the array key to output for a simple ul (not used if element or callback is specified)
 *    'type' => type of output defaults to ul
 *    'itemType => type of item output default to li
 *    'id' => id for top level 'type'
 *    'class' => class for top level 'type'
 *    'element' => path to an element to render to get node contents.
 *    'callback' => callback to use to get node contents. e.g. array(&$anObject, 'methodName') or 'floatingMethod'
 *    'autoPath' =>  array($left, $right [$classToAdd = 'active']) if set any item in the path will have the class $classToAdd added. MPTT only.
 *    'left' => name of the 'lft' field if not lft. only applies to MPTT data
 *    'right' => name of the 'rght' field if not lft. only applies to MPTT data
 *    'depth' => used internally when running recursively, can be used to override the depth in either mode.
 *    'firstChild' => used internally when running recursively.
 *    'splitDepth' => if multiple "parallel" types are required, instead of one big type, nominate the depth to do so here
 *        example: useful if you have 30 items to display, and you'd prefer they appeared in the source as 3 lists of 10 to be able to
 *        style/float them.
 *    'splitCount' => the number of "parallel" types. defaults to 3
 *
 * @param array $data data to loop on
 * @param array $settings
 * @return string html representation of the passed data
 * @access public
 */
    function generate ($data, $settings = array ()) {
        $this->__settings = array_merge(array(
                'model' => null,
                'alias' => 'name',
                'type' => 'ul',
                'itemType' => 'li',
                'id' => false,
                'class' => false,
                'element' => false,
                'callback' => false,
                'autoPath' => false,
                'left' => 'lft',
                'right' => 'rght',
                'depth' => 0,
                'firstChild' => true,
                'indent' => null,
                'splitDepth' => false,
                'splitCount' => 3,
        		'elementIdField' => false,
                'elementClassField' => false,
        		'elementRelField' => false
            ), (array)$settings);
        if ($this->__settings['autoPath'] && !isset($this->__settings['autoPath'][2])) {
            $this->__settings['autoPath'][2] = 'active';
        }
        extract($this->__settings);
        if ($indent === null && Configure::read()) {
            $indent = true;
        }
        $view =& ClassRegistry:: getObject('view');
        if ($model === null) {
            $model = Inflector::classify($view->params['models'][0]);
            $this->__settings['model'] = $model; 
        }
        if (!$model) {
            $model = '_NULL_';
        }
        $stack = array();
        if ($depth == 0) {
            if ($class) {
                $this->addTypeAttribute('class', $class, null, 'previous');
            }
            if ($id) {
                $this->addTypeAttribute('id', $id, null, 'previous');
            }
        }
        $return = '';
        if ($indent) {
            $return = "\r\n";
        }
        $__addType = true;
        foreach ($data as $i => $result) {
            /* Allow 2d data arrays */
            if ($model == '_NULL_') {
                $_result = $result;
                $result[$model] = $_result;
            }
            /* BulletProof */
            if (!isset($result[$model][$left]) && !isset($result['children'])) {
                $result['children'] = array();
            }
            /* Close open items as appropriate */
            while ($stack && ($stack[count($stack)-1] < $result[$model][$right])) {
                array_pop($stack);
                if ($indent) {
                    $whiteSpace = str_repeat("\t",count($stack));
                    $return .= "\r\n" . $whiteSpace . "\t";
                }
                if ($type) {
                    $return .= '</' . $type . '>';
                }
                if ($itemType) {
                    $return .= '</' . $itemType . '>';
                }
            }
            /* Some useful vars */
            $hasChildren = $firstChild = $lastChild = $hasVisibleChildren = false;
            $numberOfDirectChildren = $numberOfTotalChildren = null;
            if (isset($result['children'])) {
                if ($result['children']) {
                    $hasChildren = $hasVisibleChildren = true;
                    $numberOfDirectChildren = count($result['children']);
                }
                $prevRow = prev($data);
                if (!$prevRow) {
                    $firstChild = true;
                }
                next($data);
                $nextRow = next($data);
                if (!$nextRow) {
                    $lastChild = true;
                }
                prev($data);
            } elseif (isset($result[$model][$left])) {
                if ($result[$model][$left] != ($result[$model][$right] - 1)) {
                    $hasChildren = true;
                    $numberOfTotalChildren = ($result[$model][$right] - $result[$model][$left] - 1) / 2;
                    if (isset($data[$i + 1]) && $data[$i + 1][$model][$right] < $result[$model][$right]) {
                        $hasVisibleChildren = true;
                    }
                }
                if (!isset($data[$i - 1]) || ($data[$i - 1][$model][$left] == ($result[$model][$left] - 1))) {
                    $firstChild = true;
                }
                if (!isset($data[$i + 1]) || ($stack && $stack[count($stack) - 1] == ($result[$model][$right] + 1))) {
                    $lastChild = true;
                }
            }
            $elementData = array(
                'data' => $result,
                'depth' => $depth?$depth:count($stack),
                'hasChildren' => $hasChildren,
                'numberOfDirectChildren' => $numberOfDirectChildren,
                'numberOfTotalChildren' => $numberOfTotalChildren,
                'firstChild' => $firstChild,
                'lastChild' => $lastChild,
                'hasVisibleChildren' => $hasVisibleChildren
            );
            $this->__settings = array_merge($this->__settings, $elementData);
            /* Main Content */
            if ($element) {
                $content = $view->element($element,$elementData);
            } elseif ($callback) {
                list($content) = array_map($callback, array($elementData));
            } else {
                $content = $result[$model][$alias];
            }
            if (!$content) {
                continue;
            }
            $whiteSpace = str_repeat("\t", $depth);
            if ($indent && strpos($content, "\r\n", 1)) {
                $content = str_replace("\r\n", "\n" . $whiteSpace . "\t", $content);
            }
            /* Prefix */
            if ($__addType) {
                if ($indent) {
                    $return .= "\r\n" . $whiteSpace;
                }
                if ($type) {
                    $typeAttributes = $this->__attributes($type, array('data' => $elementData));
                    $return .= '<' . $type .  $typeAttributes . '>';
                }
            }
            if ($indent) {
                $return .= "\r\n" . $whiteSpace . "\t";
            }
            if ($itemType) {
                $itemAttributes = $this->__attributes($itemType, $elementData);
                $return .= '<' . $itemType . $itemAttributes . '>';
            }
            $return .= $content;
            /* Suffix */
            $__addType = false;
            if ($hasVisibleChildren) {
                if ($numberOfDirectChildren) {
                    $settings['depth'] = $depth + 1;
                    $return .= $this->__suffix();
                    $return .= $this->generate($result['children'], $settings);
                    if ($itemType) {
                        $return .= '</' . $itemType . '>';
                    }
                } elseif ($numberOfTotalChildren) {
                    $__addType = true;
                    $stack[] = $result[$model][$right];
                }
            } else {
                if ($itemType) {
                    $return .= '</' . $itemType . '>';
                }
                $return .= $this->__suffix();
            }
        }
        /* Cleanup */
        while ($stack) {
            array_pop($stack);
            if ($indent) {
                $whiteSpace = str_repeat("\t",count($stack));
                $return .= "\r\n" . $whiteSpace . "\t";
            }
            if ($type) {
                $return .= '</' . $type . '>';
            }
            if ($itemType) {
                $return .= '</' . $itemType . '>';
            }
        }
        if ($indent) {
            $return .= "\r\n";
        }
        if ($type) {
            $return .= '</' . $type . '>';
            if ($indent) {
                $return .= "\r\n";
            }
        }
        return $return;
    }
/**
 * addItemAttribute function
 *
 * Called to modify the attributes of the next <item> to be processed
 * Note that the content of a 'node' is processed before generating its wrapping <item> tag
 *
 * @param string $id
 * @param string $key
 * @param mixed $value
 * @access public
 * @return void
 */
    function addItemAttribute($id = '', $key = '', $value = null) {
        if (!is_null($value)) {
            $this->__itemAttributes[$id][$key] = $value;
        } elseif (!(isset($this->__itemAttributes[$id]) && in_array($key, $this->__itemAttributes[$id]))) {
            $this->__itemAttributes[$id][] = $key;
        }
    }
/**
 * addTypeAttribute function
 *
 * Called to modify the attributes of the next <type> to be processed
 * Note that the content of a 'node' is processed before generating its wrapping <type> tag (if appropriate)
 * An 'interesting' case is that of a first child with children. To generate the output
 * <ul> (1)
 *      <li>XYZ (3)
 *              <ul> (2)
 *                      <li>ABC...
 *                      ...
 *              </ul>
 *              ...
 * The processing order is indicated by the numbers in brackets.
 * attributes are allways applied to the next type (2) to be generated
 * to set properties of the holding type - pass 'previous' for the 4th param
 * i.e.
 * // Hide children (2)
 * $tree->addTypeAttribute('style', 'display', 'hidden');
 * // give top level type (1) a class
 * $tree->addTypeAttribute('class', 'hasHiddenGrandChildren', null, 'previous');
 *
 * @param string $id
 * @param string $key
 * @param mixed $value
 * @access public
 * @return void
 */
    function addTypeAttribute($id = '', $key = '', $value = null, $previousOrNext = 'next') {
        $var = '__typeAttributes';
        $firstChild = isset($this->__settings['firstChild'])?$this->__settings['firstChild']:true;
        if ($previousOrNext == 'next' && $firstChild) {
            $var = '__typeAttributesNext';
        }
        if (!is_null($value)) {
            $this->{$var}[$id][$key] = $value;
        } elseif (!(isset($this->{$var}[$id]) && in_array($key, $this->{$var}[$id]))) {
            $this->{$var}[$id][] = $key;
        }
    }

/**
 * supressChildren method
 *
 * @return void
 * @access public
 */
    function supressChildren() {
    }
/**
 * suffix method
 *
 * Used to close and reopen a ul/ol to allow easier listings
 *
 * @access private
 * @return void
 */
    function __suffix() {
        static $__splitCount = 0;
        static $__splitCounter = 0;
        extract($this->__settings);
        if ($splitDepth) {
            if ($depth == $splitDepth -1) {
                $total = $numberOfDirectChildren?$numberOfDirectChildren:$numberOfTotalChildren;
                if ($total) {
                    $__splitCounter = 0;
                    $__splitCount = $total / $splitCount;
                    $rounded = (int)$__splitCount;
                    if ($rounded < $__splitCount) {
                        $__splitCount = $rounded + 1;
                    }
                }
            }
            if ($depth == $splitDepth) {
                $__splitCounter++;
                if ($type && ($__splitCounter % $__splitCount) == 0) {
                    return '</' . $type . '><' . $type . '>';
                }
            }
        }
        return;
    }
/**
 * attributes function
 *
 * Logic to apply styles to tags.
 *
 * @param mixed $rType
 * @param array $elementData
 * @access private
 * @return void
 */
    function __attributes($rType, $elementData = array(), $clear = true) {
    	extract($this->__settings);
        
        if ($rType == $type) {
            $attributes = $this->__typeAttributes;
            if ($clear) {
                $this->__typeAttributes = $this->__typeAttributesNext;
                $this->__typeAttributesNext = array();
            }
        } else {
            $attributes = $this->__itemAttributes;
            $this->__itemAttributes = array();
            if ($clear) {
                $this->__itemAttributes = array();
            }
        }
		if ($elementIdField && $depth && $rType == $itemType) {
            $attributes['id'][] = $this->__settings['data'][$model][$elementIdField];
        }
        if ($elementClassField && $depth && $rType == $itemType) {
            $attributes['class'][] = $this->__settings['data'][$model][$elementClassField];
        } 
        if ($elementRelField && $depth && $rType == $itemType) {
        	if(isset($this->__settings['data'][$model][$elementRelField]))
        		$attributes['rel'][] = $this->__settings['data'][$model][$elementRelField];
        } 
        if ($autoPath && $depth) {
            if ($this->__settings['data'][$model][$left] < $autoPath[0] && $this->__settings['data'][$model][$right] > $autoPath[1]) {
                $attributes['class'][] = $autoPath[2];
            } elseif (isset($autoPath[3]) && $this->__settings['data'][$model][$left] == $autoPath[0]) {
                $attributes['class'][] = $autoPath[3];
            }
        }
        
        if ($attributes) {
            foreach ($attributes as $type => $values) {
                foreach ($values as $key => $val) {
                    if (is_array($val)) {
                        $attributes[$type][$key] = '';
                        foreach ($val as $vKey => $v) {
                            $attributes[$type][$key][$vKey] .= $vKey . ':' . $v;
                        }
                        $attributes[$type][$key] = implode(';', $attributes[$type][$key]);
                    }
                    if (is_string($key)) {
                        $attributes[$type][$key] = $key . ':' . $val . ';';
                    }
                }
                $attributes[$type] = $type . '="' . implode(' ', $attributes[$type]) . '"';
            }
            
            return ' ' . implode(' ', $attributes);
        }
        
        return '';
    }
}
