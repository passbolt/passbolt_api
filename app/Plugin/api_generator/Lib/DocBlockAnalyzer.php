<?php
/**
 * Docs analyzer - Uses a simple extensible rules system
 * to analzye doc block arrays and evaluate their 'goodness'
 *
 *
 * PHP 5
 *
 * CakePHP :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2006-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright       Copyright 2006-2008, Cake Software Foundation, Inc.
 * @link            http://www.cakefoundation.org/projects/info/cakephp CakePHP Project
 * @package         api_generator
 * @subpackage      api_generator.vendors
 * @license         http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class DocBlockAnalyzer {
/**
 * Constructed Rules objects.
 *
 * @var array
 **/
	public $rules = array();
/**
 * Rules classes that are going to be used
 *
 * @var array
 **/
	protected $_ruleNames = array();
/**
 * Final score for analyzation
 *
 * @var int
 **/
	public $_finalScore = 0;
/**
 * Total elements counted this run
 *
 * @var int
 **/
	protected $_totalElements = 0;
/**
 * Running score for the current property
 *
 * @var int
 **/
	protected $_contentScore = 0;
/**
 * Running total of all objects in the current property
 *
 * @var int
 **/
	protected $_contentObjectCount = 0;
/**
 * Default rules
 *
 * @var string
 **/
	protected $_defaultRules = array(
		'MissingLink', 'Empty', 'MissingParams', 'IncompleteTags'
	);
/**
 * Current reflection objects being inspected.
 *
 * @var object
 **/
	protected $_reflection;
/**
 * Constructor
 *
 * @param array $rules Names of DocBlockRule Classes you want to use.
 * @return void
 **/
	public function __construct($rules = array()) {
		if (empty($rules)) {
			$rules = $this->_defaultRules;
		}
		$this->_ruleNames = $rules;
		$this->_buildRules();
	}
/**
 * Build the rules objects
 *
 * @return void
 **/
	protected function _buildRules() {
		foreach ($this->_ruleNames as $rule) {
			$className = $rule . 'DocBlockRule';
			if (!class_exists($className, false)) {
				trigger_error('Missing Rule Class ' . $className, E_USER_WARNING);
				continue;
			}
			$ruleObj = new $className();
			if ($ruleObj instanceof DocBlockRule) {
				$this->rules[$rule] = $ruleObj;
			}
		}
	}
/**
 * Get the Descriptions and Names of the Rules being used.
 *
 * @return array
 **/
	public function getRules() {
		$out = array();
		foreach ($this->rules as $name => $rule) {
			$out[$name] = $rule->description;
		}
		return $out;
	}
/**
 * Set the source for the Analyzation.  Expects either a ClassDocumentor or FunctionDocumentor instance.
 *
 * @param object $reflector Reflection Object to be inspected.
 * @return boolean Success of setting source.
 * @throws RuntimeException
 **/
	public function setSource($reflector) {
		if (!($reflector instanceof ClassDocumentor) && !($reflector instanceof FunctionDocumentor)) {
			throw new RuntimeException(sprintf(
				'DocBlockAnalyzer::setSource() - Expects an instance of ClassDocumentor or FunctionDocumentor, %s was given', 
				get_class($reflector)
			));
			return false;
		}
		$reflector->getAll();
		$this->_reflection = $reflector;
		return true;
	}
/**
 * Analyze a Reflection object.
 * 
 * Options
 * - includeParent Whether or not you want to include parent methods/properties. (default to false)
 *
 * @param object $reflector Reflection Object to be inspected (optional)
 * @param array $options Array of options to use.
 * @return array Array of scoring information
 **/
	public function analyze($reflector = null, $options = array()) {
		$this->reset();
		$options = array_merge(array('includeParent' => false), $options);
		if ($reflector !== null) {
			$valid = $this->setSource($reflector);
			if (!$valid) {
				return array();
			}
		}
		$lookAt = array('classInfo', 'properties', 'methods');
		$results = array();
		$totalElements = $finalScore = 0;
		foreach ($lookAt as $property) {
			$content = $this->_reflection->{$property};
			if (!is_array($content)) {
				continue;
			}
			$this->resetCounts();
			$results[$property] = array();

			if ($property == 'classInfo') {
				$result = $this->_scoreElement($content);
				$results[$property] = array(
					'subject' => $property,
					'scores' => $result['scores'],
					'totalScore' => $result['totalScore']
				);
			} else {
				foreach ($content as $element) {
					$isParent = (isset($element['declaredInClass']) && 
						$element['declaredInClass'] != $this->_reflection->getName()
					);
					if (!$options['includeParent'] && $isParent) {
						continue;
					}
					$results[$property][] = $this->_scoreElement($element);
				}
			}
			$this->_calculateSectionTotals($results, $property);
		}
		$results['finalScore'] = ($this->_finalScore / $this->_totalElements);
		return $results;
	}
/**
 * Reset the docblock analyzer
 *
 * @return boolean true;
 **/
	public function reset() {
		$this->_finalScore = 0;
		$this->_totalElements = 0;
		$this->resetCounts();
		return true;
	}
/**
 * Reset the internal counters.
 *
 * @return boolean true
 **/
	public function resetCounts() {
		$this->_contentScore = 0;
		$this->_contentObjectCount = 0;
		return true;
	}

/**
 * Calculate the section totals for a specific property.
 * Modify the results array and return via reference
 *
 * @param array $results Array of inprogress results
 * @param string $property Name of property being looked at
 * @access public
 * @return array
 **/
	protected function _calculateSectionTotals(&$results, $property) {
		$results['sectionTotals'][$property] = array(
			'elementCount' => 0,
			'score' => 0,
			'average' => 0,
		);
		if ($this->_contentObjectCount != 0) {
			$results['sectionTotals'][$property] = array(
				'elementCount' => $this->_contentObjectCount,
				'score' => $this->_contentScore,
				'average' => $this->_contentScore / $this->_contentObjectCount,
			);
		}
		$this->_totalElements += $this->_contentObjectCount;
		$this->_finalScore += $this->_contentScore;
	}
/**
 * Score an element and generate results.
 *
 * @return array
 **/
	protected function _scoreElement($element) {
		$scores = $this->_runRules($element);
		$result = array(
			'subject' => $element['name'],
			'scores' => $scores,
			'totalScore' => $scores['totalScore'],
		);
		unset($result['scores']['totalScore']);

		$this->_contentObjectCount++;
		$this->_contentScore += $result['totalScore'];
		return $result;
	}
/**
 * _runRules against an element set
 *
 * @return array
 **/
	protected function _runRules($subject) {
		$results = array();
		$totalScore = 0;
		foreach ($this->rules as $name => $rule) {
			$rule->setSubject($subject);
			$score = $rule->score();
			if ($score < 1) {
				$results[] = array(
					'rule' => $name,
					'score' => $score,
					'description' => $rule->description
				);
			}
			$totalScore += $score;
		}
		$results['totalScore'] = ($totalScore  / count($this->rules));
		return $results;
	}
}


/**
 * Abstract Base Class for DocBlock Rules
 *
 * @package api_generator.vendors.doc_block_analyzer
 **/
abstract class DocBlockRule {
/**
 * Description of the rule
 *
 * @var string
 **/
	public $description = '';
/**
 * setSubject - Set the array of doc block info to be looked at.
 * 
 * @param array $docArray Array of parsed docblock info to evaluate.
 * @return void
 **/
	public function setSubject($docArray) {
		$this->_subject = $docArray;
	}
/**
 * Score - Run the scoring system for this rule
 *
 * @access public
 * @return float Returns the float value (between 0 - 1) of the rule.
 **/
	abstract public function score();
}


/**
 * Check for missing @link tags
 *
 * @package default
 **/
class MissingLinkDocBlockRule extends DocBlockRule {
	public $description = 'Check for a missing @link tag';
/**
 * Check for a @link tag in the tags array.
 *
 * @return float
 **/
	public function score() {
		if (empty($this->_subject['comment']['tags']['link'])) {
			return 0;
		}
		return 1;
	}
}

/**
 * Check that the doc block has a description string.
 *
 **/
class EmptyDocBlockRule extends DocBlockRule {
/**
 * description
 *
 * @var string
 **/
	public $description = 'Check for empty doc string';
/**
 * score method
 *
 * @return float
 **/
	public function score() {
		if (empty($this->_subject['comment']['description'])) {
			return 0;
		}
		if (strlen($this->_subject['comment']['description']) > (2 * $this->_subject['name'])) {
			return 1;
		}
		return 0.5;
	}
}

/**
 * Check that every argument has all the param tags filled out.
 *
 **/
class MissingParamsDocBlockRule extends DocBlockRule {
/**
 * description
 *
 * @var string
 **/
	public $description = 'Check for any empty @param tags';
/**
 * score method
 *
 * @return float
 **/
	public function score() {
		if (empty($this->_subject['args'])) {
			return 1;
		}
		$good = 0;
		$totalArgs = count($this->_subject['args']);
		foreach ($this->_subject['args'] as $arg) {
			if (!empty($arg['comment'])) {
				$good += 0.5;
			}
			if (!empty($arg['type'])) {
				$good += 0.5;
			}
		}
		return $good / $totalArgs;
	}
}

/**
 * Check that tags requiring a value have them.
 * 
 **/
class IncompleteTagsDocBlockRule extends DocBlockRule {
/**
 * description
 *
 * @var string
 **/
	public $description = 'Check for incomplete tag strings.';
/**
 * tags that don't require text
 *
 * @var array
 **/
	protected $_singleTags = array(
		'deprecated', 'abstract', 'ignore', 'final',
	);
/**
 * score method
 *
 * @return float
 **/
	public function score() {
		if (empty($this->_subject['comment']['tags'])) {
			return 0;
		}
		$total = count($this->_subject['comment']['tags']);
		$good = 0;
		foreach ($this->_subject['comment']['tags'] as $tag => $value) {
			if (!empty($value) || !in_array($tag, $this->_singleTags)) {
				$good++;
			}
		}
		return $good / $total;
	}
}
