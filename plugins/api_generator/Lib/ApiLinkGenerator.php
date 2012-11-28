<?php
/**
 * An implementation of ApiDocLinkParser.
 *
 * creates links that work within the ApiGenerator plugin.
 *
 * @package api_generator
 */
App::import('Helper', 'Html');

class ApiLinkGenerator extends ApiDocLinkGenerator {

	public $Html;

/**
 * default Urls
 *
 * @var array
 **/
	protected $_defaultUrl = array(
		'class' => array(
			'controller' => 'api_classes',
			'action' => 'view_class',
			'plugin' => 'api_generator',
		),
	);

/**
 * constructor.
 *
 * @return void
 */
	public function __construct($View) {
		$this->Html = new HtmlHelper($View);
	}

/**
 * Handles the creation of Class::method() links.
 *
 * @return string Generated link text
 */
	public function classMethodLink($matches) {
		$listFlip = array_flip($this->_classList);
		if (array_key_exists($matches[1], $listFlip)) {
			$link = array($this->slug($matches[1]), '#' => 'method-' . $matches[1] . $matches[2]);
			$url = array_merge($this->_defaultUrl['class'], $link);
			return $this->Html->link($matches[0], $url);
		}
		return $matches[0];
	}

/**
 * Handle the creation of Class::$property links
 *
 * @return string Generated link text.
 */
	public function classPropertyLink($matches) {
		$listFlip = array_flip($this->_classList);
		if (array_key_exists($matches[1], $listFlip)) {
			$link = array($this->slug($matches[1]), '#' => 'property-' . $matches[1] . $matches[2]);
			$url = array_merge($this->_defaultUrl['class'], $link);
			return $this->Html->link($matches[0], $url);
		}
		return $matches[0];
	}

}