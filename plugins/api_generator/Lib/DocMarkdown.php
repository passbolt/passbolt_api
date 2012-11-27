<?php
/**
 * DocMarkdown is a simple Markdown Parser.  It provides a set of syntax
 * parsing for documentation blocks.  It does not support the full markdown feature set
 * and implements several additional elements that have extra utility in documentation
 * for PHP projects.
 *
 * ### Unsuported syntax items:
 *
 * - reference style links are not supported, only inline links work.
 * - Setext style headers are not supported, only ATX style headers work.
 * - Block quotes are not implemented at this time.
 *
 * ### Additional syntax items:
 *
 * - Class::method() links. These are links to other class + methods in your code base.
 * - Class::$property links. These are links to other class properties in your code base.
 * - Code blocks - Code blocks can be indicated with either {{{ code }}} or @@@ code @@@ or indented.
 *
 * DocMarkdown also implements the more 'strict' italic and bold flavours, so under_scored_variables
 * are not interpreted as italicized text.
 *
 * Several patterns and ideas like list processing were adopted from
 * MarkdownSharp (http://code.google.com/p/markdownsharp/)
 *
 * @package api_generator.libs
 */
class DocMarkdown {

/**
 * The text being parsed.
 *
 * @var string
 */
	protected $_text = null;

/**
 * Contains a hash map of placeholders => content
 *
 * @var array
 */
	protected $_placeHolders = array();

/**
 * indented code block flag used to signal an outdent
 *
 * @var boolean
 */
	protected $_indentedCode = false;

/**
 * Current list level
 *
 * @var string
 */
	protected $_listDepth = 0;

/**
 * Number of spaces per tab char
 *
 * @var string
 */
	public $spacesPerTab = 4;

/**
 * pattern for ordered lists
 *
 * @var string
 */
	protected $_orderedListPattern = '\d+\.';

/**
 * unordered list marker
 *
 * @var string
 */
	protected $_unorderedListPattern = '[-+*]';

/**
 * A link parser for special API links.
 *
 * @var string
 */
	protected $_LinkGenerator;

/**
 * Parses $text containing doc-markdown text and generates the correct
 * HTML
 *
 * ### Options:
 *
 * - stripHtml - remove any HTML before parsing.
 *
 * @param string $text Text to be converted
 * @param array $options Array of options for converting
 * @return string Parsed HTML
 */
	public function parse($text, $options = array()) {
		if (!empty($options['stripHtml'])) {
			$text = strip_tags($text);
		}
		$this->_placeHolders = array();
		$text = str_replace("\r\n", "\n", $text);
		$text = str_replace("\t", str_repeat(' ', $this->spacesPerTab), $text);
		$text = $this->_runBlocks($text);
		return $text;
	}

/**
 * Set the active linkParser to be used for special API links.
 *
 * @return void
 */
	public function setLinkGenerator(ApiDocLinkGenerator $linkGenerator) {
		$this->_LinkGenerator = $linkGenerator;
	}

/**
 * Runs the block syntax elements in the correct order.
 * The following block syntaxes are supported
 *
 * - ATX style headers
 * - lists
 * - horizontal rules.
 * - Code blocks
 * - paragraph
 *
 * @param string $text Text to transform
 * @return string Transformed text.
 */
	protected function _runBlocks($text) {
		$text = $this->_doHeaders($text);
		$text = $this->_doHorizontalRule($text);
		$text = $this->_doLists($text);
		$text = $this->_doCodeBlocksDelimited($text);
		$text = $this->_doCodeBlocksIndented($text);
		$text = $this->_doParagraphs($text);
		return $text;
	}

/**
 * Generate code blocks.
 *
 * @param string $text Text to be transformed
 * @return string Transformed text
 */
	protected function _doCodeBlocksDelimited($text) {
		$codePattern = '/(@{3}|\{{3})\n*(.+?)\n*(\1|\}{3})/s';
		return preg_replace_callback($codePattern, array($this, '_codeBlockHelper'), $text);
	}

/**
 * Generate code blocks using indented style.
 *
 * @param string $text Text to be transformed
 * @return string Transformed text
 */
	protected function _doCodeBlocksIndented($text) {
		$codePattern = sprintf(
			'/(\n\n|\A)((?:[ ]{%s}.*\n+)+)((?=[ ]{0,%s}|\Z))/',
			$this->spacesPerTab,$this->spacesPerTab
		);
		$this->_indentedCode = true;
		$return = preg_replace_callback($codePattern, array($this, '_codeBlockHelper'), $text);
		$this->_indentedCode = false;
		return $return;
	}

/**
 * code block assist
 *
 * @return string
 */
	protected function _codeBlockHelper($matches) {
		if ($this->_indentedCode) {
			$matches[2] = $this->_outdent($matches[2]);
		}
		return "\n\n" . $this->_makePlaceHolder(
			'<pre><code>' . htmlspecialchars(trim($matches[2])) . '</code></pre>'
		) . "\n\n";
	}

/**
 * Outdents code one $spacesPerTab amount.
 *
 * @param string $text Text to outdent
 * @return string
 */
	protected function _outdent($text) {
		return preg_replace(sprintf('/^[ ]{1,%s}/ms', $this->spacesPerTab), '', $text);
	}

/**
 * Run the header elements
 *
 * @param string $text Text to be transformed
 * @return string Transformed text
 */
	protected function _doHeaders($text) {
		$headingPattern = '/(#+)\s([^#\n]+)(#*)/';
		return preg_replace_callback($headingPattern, array($this, '_headingHelper'), $text);
	}

/**
 * Heading callback method
 *
 * @param array $matches array of matches from _doHeaders()
 * @return string Transformed text
 */
	protected function _headingHelper($matches) {
		$count = strlen($matches[1]);
		if ($count > 6) {
			$count = 6;
		}
		return $this->_makePlaceHolder(sprintf('<h%s>%s</h%s>', $count, trim($matches[2]), $count));
	}

/**
 * Converts various horizontal rule markers into <hr /> elements.
 *
 * @param string $text Text to be transformed
 * @return string Transformed text
 */
	protected function _doHorizontalRule($text) {
		$hrPattern = '/\n+([-_*])(?>[ ]*\1){2,}\n+/';
		return preg_replace($hrPattern, "\n\n" . $this->_makePlaceHolder("<hr />") . "\n\n", $text);
	}

/**
 * Create elements for UL and OL lists.
 * UL is indicated with -, +, *
 * OL is indicated with 1.
 *
 * @param string $text Text to be transformed
 * @return string Transformed text
 */
	protected function _doLists($text) {
		$listMarkers = sprintf('(?:%s|%s)', $this->_orderedListPattern, $this->_unorderedListPattern);

		//1 = leading space, 2 = marker, 3 = text, 4=end
		$listPattern = sprintf(
			'(([ ]{0,%s})(%s[ ]+)(.+?)(\Z|\n{2,}(?=\S)(?!%s)))',
			$this->spacesPerTab, $listMarkers, $listMarkers
		);
		if ($this->_listDepth == 0) {
			$listPattern = '/(?:(?<=\n\n)|\A\n?)' . $listPattern . '/s';
		} else {
			$listPattern = '/^' . $listPattern . '/sm';
		}
		return preg_replace_callback($listPattern, array($this, '_processList'), $text);
	}

/**
 * Process list items and generate nested list elements
 *
 * @return string Processed text
 */
	protected function _processList($matches) {
		$listType = preg_match('/'. $this->_orderedListPattern . '/', $matches[3]) ? 'ol' : 'ul';
		$markerPattern = $listType == 'ol' ? $this->_orderedListPattern : $this->_unorderedListPattern;

		$items = $this->_processListItems($matches[1], $markerPattern);

		$list = sprintf("<%s>\n%s\n</%s>", $listType, $items, $listType);
		if ($this->_listDepth == 0) {
			return $this->_makePlaceHolder($list) . "\n\n";
		}
		return $list;
	}

/**
 * Generates list items and recurses into nested list structrures.
 *
 * @return string
 */
	protected function _processListItems($list, $markerPattern) {
		$list .= "\n";
		$listPattern = sprintf('/
			(\n)?
			(^[ ]*)
			(%s[ ]+) # list marker
			((.+?)(\n{1,2})) # text
			(?=\n*(\z|\2(%s)[ ]+))
		/smx', $markerPattern, $markerPattern);
		$this->_listDepth++;

		$out = preg_replace_callback($listPattern, array($this, '_listItem'), $list);

		$this->_listDepth--;
		return trim($out);
	}

/**
 * Make a single list item
 *
 * @return string
 */
	protected function _listItem($matches) {
		$item = $matches[4];
		$leadingLine = $matches[1];
		if (!empty($leadingLine)) {
			$item = $this->_runBlocks($this->_outdent($item) . "\n");
		} else {
			$item = rtrim($item, "\n");
			$item = $this->_runInline($item);
			$item = $this->_doLists($this->_outdent($item) . "\n");
		}
		return '<li>' . trim($item) . "</li>\n";
	}

/**
 * Create paragraphs
 *
 * @return void
 */
	protected function _doParagraphs($text) {
		$blocks = preg_split('/\n{2,}/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
		for ($i = 0, $len = count($blocks); $i < $len; $i++) {
			if (substr($blocks[$i], 0, 5) === 'B0x1A') {
				$blocks[$i] = $this->_replacePlaceHolders($blocks[$i]);
			} else {
				$blocks[$i] = '<p>' . $this->_runInline($blocks[$i]) . '</p>';
			}
		}
		return implode("\n\n", $blocks);
	}

/**
 * Run the inline syntax elements against $text
 * The following Inline elements are supported:
 *
 * - em
 * - strong
 * - code
 * - inline link
 * - autolink
 * - entity encoding
 *
 * In addition two special elements are parsed by a helper class specific to the
 * API generation being used.
 *
 * - Class::method()
 * - Class::$property
 *
 * @param string $text Text to convert.
 * @return string Transformed text.
 */
	protected function _runInline($text) {
		$text = $this->_encodeEntities($text);
		$text = $this->_doItalicAndBold($text);
		$text = $this->_doInlineCode($text);
		$text = $this->_doInlineLink($text);
		$text = $this->_doAutoLink($text);
		$text = $this->_doSpecialLinks($text);
		$text = $this->_replacePlaceHolders($text);
		return $text;
	}

/**
 * Converts < and & as they are the most dangerous characters to leave behind.
 *
 * @param string $text Text to transform
 * @return string Transformed text.
 */
	protected function _encodeEntities($text) {
		return str_replace(array('&', '<'), array('&amp;', '&lt;'), $text);
	}

/**
 * Transform `*italic* and **bold**` into `<em>italic</em> and <strong>bold</strong>`
 *
 * @param string $text Text to transform
 * @return string Transformed text.
 */
	protected function _doItalicAndBold($text) {
		$boldPattern = '/(\W|\A)(\*\*|__)(?=\S)([^\n]*?\S[\*_]*)\2(\W|\Z)/';
		$italicPattern = '/(\W|\A)(\*|_)(?=\S)([^\n_\*]*?\S)\2(\W|\Z)/';
		$text = preg_replace($boldPattern, '\1<strong>\3</strong>\4', $text);
		$text = preg_replace($italicPattern, '\1<em>\3</em>\4', $text);
		return $text;
	}

/**
 * Transform `text` into <code>text</code>
 *
 * @param string $text Text to transform
 * @return string Transformed text.
 */
	protected function _doInlineCode($text) {
		$codePattern = '/(`+)(?=\S)(.+?[`]*)(?=\S)\1/';
		return preg_replace($codePattern, '<code>\2</code>', $text);
	}

/**
 * Convert url into anchor elements.  Converts both
 * http://www.foo.com  and www.foo.com
 *
 * @param string $text Text to transform
 * @return string Transformed text.
 */
	protected function _doAutoLink($text) {
		$wwwPattern = '/((https?:\/\/|www\.)[^\s]+)\s/';
		return preg_replace_callback($wwwPattern, array($this, '_autoLinkHelper'), $text);
	}

/**
 * Helper callback method for autoLink replacement.
 *
 * @return void
 */
	protected function _autoLinkHelper($matches) {
		if ($matches[2] == 'www.') {
			return sprintf('<a href="http://%s">%s</a> ', $matches[1], $matches[1]);
		}
		return sprintf('<a href="%s">%s</a> ', $matches[1], $matches[1]);
	}

/**
 * Replace inline links [foo bar](http://foo.com) with <a href="http://foo.com">foo bar</a>
 *
 * @param string $text Text to transform
 * @return string Transformed text.
 */
	protected function _doInlineLink($text) {
		// 1 = name, 2 = url , 3 = title + quotes, 4 = quote, 5 = title
		$linkPattern = '/\[([^\]]+)\]\s*\(([^ \t]+)([\s\t]*([\"|\'])(.+)\4)?\)/';
		return preg_replace_callback($linkPattern, array($this, '_inlineLinkHelper'), $text);
	}

/**
 * Helper function for replacing of inline links
 *
 * @return string Text
 * @see DocMarkdown::_doInlineLink()
 */
	protected function _inlineLinkHelper($matches) {
		$title = null;
		if (isset($matches[5])) {
			$title = ' title="' . $matches[5] . '"';
		}
		return $this->_makePlaceHolder(sprintf('<a href="%s"%s>%s</a>', $matches[2], $title, $matches[1]));
	}

/**
 * Process any special links that are in the text.
 *
 * @param string $text Text to transform
 * @return string Transformed text.
 */
	protected function _doSpecialLinks($text) {
		if (empty($this->_LinkGenerator)) {
			return $text;
		}
		$linkPattern = '/([A-Z-_0-9]+)\:\:([A-Z-_0-9]+)\(\)/i';
		$text = preg_replace_callback($linkPattern, array($this->_LinkGenerator, 'classMethodLink'), $text);

		$propertyPattern = '/([A-Z-_0-9]+)\:\:\$([A-Z-_0-9]+)/i';
		$text = preg_replace_callback($propertyPattern, array($this->_LinkGenerator, 'classPropertyLink'), $text);
		return $text;
	}

/**
 * Replace placeholders in $text with the literal values in the _placeHolders array.
 *
 * @param string $text Text to have placeholders replaced in.
 * @return string Text with placeholders replaced.
 */
	protected function _replacePlaceHolders($text) {
		foreach ($this->_placeHolders as $marker => $replacement) {
			$replaced = 0;
			$text = str_replace($marker, $replacement, $text, $replaced);
			if ($replaced > 0) {
				unset($this->_placeHolders[$marker]);
			}
		}
		return $text;
	}

/**
 * Convert $text into a placeholder text string
 *
 * @param string $text Text to convert into a placeholder marker
 * @return string
 */
	protected function _makePlaceHolder($text) {
		$count = count($this->_placeHolders);
		$marker = 'B0x1A' . $count;
		$this->_placeHolders[$marker] = $text;
		return $marker;
	}
}


/**
 * Interface for object link generator implementations
 *
 * @package api_generator
 */
abstract class ApiDocLinkGenerator {

	protected $_classList = array();

/**
 * Generate links for Methods.  Uses Class::method() as a template
 *
 * @param array $matches
 * @return string Completed text link
 */
	public abstract function classMethodLink($matches);

/**
 * Generate links for properties.  Uses Class::$property as a template
 *
 * @param array $matches
 * @return string Completed text link
 */
	public abstract function classPropertyLink($matches);

/**
 * Set the Class list so that linkClassName will know which classes are in the index.
 *
 * @param array $classList The list of classes to use when making links.
 * @return void
 */
	public function setClassIndex($classList) {
		$this->_classList = $classList;
	}

/**
 * Slugs a classname to match the format in the database.
 *
 * @param string $className Name of class to sluggify.
 * @return string
 */
	public function slug($className) {
		return str_replace('_', '-', Inflector::underscore($className));
	}

}