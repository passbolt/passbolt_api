<?php

App::import('Lib', 'ApiGenerator.DocMarkdown');
App::import('Lib', 'ApiGenerator.ApiLinkGenerator');
App::uses('View', 'View');
App::uses('Controller', 'Controller');

class DocMarkdownTestCase extends CakeTestCase {

/**
 * setup
 *
 * @return void
 */
	function setUp() {
		parent::setUp();
		$this->Parser = new DocMarkdown();
	}

/**
 * test emphasis and bold elements.
 *
 * @return void
 */
	function testEmphasisAndBold() {
		$text = 'Normal text *emphasis text* normal *emphasis* normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <em>emphasis text</em> normal <em>emphasis</em> normal.</p>';
		$this->assertEqual($result, $expected);

		$text = 'Normal text **bold** normal *emphasis* normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <strong>bold</strong> normal <em>emphasis</em> normal.</p>';
		$this->assertEqual($result, $expected);

		$text = 'Normal text ***bold*** normal *emphasis* normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <strong><em>bold</em></strong> normal <em>emphasis</em> normal.</p>';
		$this->assertEqual($result, $expected);

		$text = 'Normal text _emphasis text_ normal _emphasis_ normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <em>emphasis text</em> normal <em>emphasis</em> normal.</p>';
		$this->assertEqual($result, $expected);

		$text = 'Normal text __bold__ normal _emphasis_ normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <strong>bold</strong> normal <em>emphasis</em> normal.</p>';
		$this->assertEqual($result, $expected);

		$text = 'Normal text ___bold___ normal _emphasis_ normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <strong><em>bold</em></strong> normal <em>emphasis</em> normal.</p>';
		$this->assertEqual($result, $expected);
	}

/**
 * test inline code elements.
 *
 * @return void
 */
	function testInlineCode() {
		$text = 'Normal text `code text` normal `code` normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <code>code text</code> normal <code>code</code> normal.</p>';
		$this->assertEqual($result, $expected);

		$text = 'Normal text ``code text` normal `code`` normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <code>code text` normal `code</code> normal.</p>';
		$this->assertEqual($result, $expected);

		$text = 'Normal text ``code text` < > & normal `code`` normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <code>code text` &lt; > &amp; normal `code</code> normal.</p>';
		$this->assertEqual($result, $expected);
		
		$text = 'Normal text ``code text some_variable_here_code text`` normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <code>code text some_variable_here_code text</code> normal.</p>';
		$this->assertEqual($result, $expected);
	}

/**
 * test inline code elements.
 *
 * @return void
 */
	function testAutoLink() {
		$text = 'Normal text www.foo.com normal code normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <a href="http://www.foo.com">www.foo.com</a> normal code normal.</p>';
		$this->assertEqual($result, $expected);

		$text = 'Normal text www.foo.com/page/foo:bar normal code normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <a href="http://www.foo.com/page/foo:bar">www.foo.com/page/foo:bar</a> normal code normal.</p>';
		$this->assertEqual($result, $expected);

		$text = 'Normal text http://www.foo.com normal code normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <a href="http://www.foo.com">http://www.foo.com</a> normal code normal.</p>';
		$this->assertEqual($result, $expected);
	}

/**
 * test inline links
 *
 * @return void
 */
	function testInlineLinks() {
		$text = 'Normal text [test link](http://www.foo.com) normal code normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <a href="http://www.foo.com">test link</a> normal code normal.</p>';
		$this->assertEqual($result, $expected);

		$text = 'Normal text [test link](http://www.foo.com "some title") normal code normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal text <a href="http://www.foo.com" title="some title">test link</a> normal code normal.</p>';
		$this->assertEqual($result, $expected);
	}

/**
 * test entity conversion
 *
 * @return void
 */
	function testEntityConversion() {
		$text = 'Normal < text [test link](http://www.foo.com) normal & code normal.';
		$result = $this->Parser->parse($text);
		$expected = '<p>Normal &lt; text <a href="http://www.foo.com">test link</a> normal &amp; code normal.</p>';
		$this->assertEqual($result, $expected);
	}

/**
 * Test Headings
 *
 * @return void
 */
	function testHeadings() {
		$text = <<<TEXT
# H1
## H2 ##
### heading 3
#### heading 4
##### Imbalanced ##
######## There is no heading 8
TEXT;
		$result = $this->Parser->parse($text);
		$expected = <<<HTML
<h1>H1</h1>
<h2>H2</h2>
<h3>heading 3</h3>
<h4>heading 4</h4>
<h5>Imbalanced</h5>
<h6>There is no heading 8</h6>
HTML;
		$this->assertEqual($result, $expected);
	}

/**
 * test horizontal rules.
 *
 * @return void
 */
	function testHorizontalRule() {
		$expected = <<<HTML
<p>this is some</p>

<hr />

<p>text</p>
HTML;

		foreach (array('-', '*', '_') as $char) {
			$text = <<<TEXT
this is some
{$char}{$char}{$char}
text
TEXT;
			$result = $this->Parser->parse($text);
			$this->assertEqual($result, $expected);

			$text = <<<TEXT
this is some
{$char}  {$char}  {$char}
text
TEXT;
			$result = $this->Parser->parse($text);
			$this->assertEqual($result, $expected);

			$text = <<<TEXT
this is some
{$char}{$char}{$char}{$char}{$char}{$char}
text
TEXT;
			$result = $this->Parser->parse($text);
			$this->assertEqual($result, $expected);
		}
	}

/**
 * test multiline code blocks
 *
 * @return void
 */
	function testCodeBlockWithDelimiters() {
		$text = <<<TEXT
this is some
@@@
function test() {
	echo '<test>';
}
@@@
more text
TEXT;
		$expected = <<<HTML
<p>this is some</p>

<pre><code>function test() {
    echo '&lt;test&gt;';
}</code></pre>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);

		$text = <<<TEXT
this is some
{{{
function test() {
	echo '<test>';
}
}}}
more text
TEXT;
		$expected = <<<HTML
<p>this is some</p>

<pre><code>function test() {
    echo '&lt;test&gt;';
}</code></pre>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * test two code blocks with delimiters.
 *
 * @return void
 */
	function testMultipleCodeBlocksWithDelimiters() {
		$text = <<<TEXT
this is some
{{{
function test() {
	echo '<test>';
}
}}}

more text goes here.

{{{
function test() {
	echo '<test>';
}
}}}

Additional text
TEXT;
		$expected = <<<HTML
<p>this is some</p>

<pre><code>function test() {
    echo '&lt;test&gt;';
}</code></pre>

<p>more text goes here.</p>

<pre><code>function test() {
    echo '&lt;test&gt;';
}</code></pre>

<p>Additional text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * test that code blocks work with no newlines
 *
 * @return void
 */
	function testCodeBlockNoNewLines() {
		$text = <<<TEXT
this is some

{{{ Router::connectNamed(false, array('default' => true)); }}}

more text
TEXT;
		$expected = <<<HTML
<p>this is some</p>

<pre><code>Router::connectNamed(false, array('default' =&gt; true));</code></pre>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * test indented code blocks
 *
 * @return void
 */
	function testCodeBlockWithIndents() {
		$text = <<<TEXT
this is some

	function test() {
		echo '<test>';
	}

more text
TEXT;
		$expected = <<<HTML
<p>this is some</p>

<pre><code>function test() {
    echo '&lt;test&gt;';
}</code></pre>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);

		$text = <<<TEXT
this is some

    function test() {
    	echo '<test>';
    }

more text
TEXT;
		$expected = <<<HTML
<p>this is some</p>

<pre><code>function test() {
    echo '&lt;test&gt;';
}</code></pre>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);

		$text = <<<TEXT
this is some

    function test() {
    	echo '<test>';
    }
    
    \$foo->bar();

more text
TEXT;
		$expected = <<<HTML
<p>this is some</p>

<pre><code>function test() {
    echo '&lt;test&gt;';
}

\$foo-&gt;bar();</code></pre>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * Test simple ordered list parsing
 *
 * @return void
 */
	function testSimpleOrderedList() {
		$text = <<<TEXT
Some text here.

 - Line 1
 - Line 2
 - Line 3

more text
TEXT;

		$expected = <<<HTML
<p>Some text here.</p>

<ul>
<li>Line 1</li>
<li>Line 2</li>
<li>Line 3</li>
</ul>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);

		$text = <<<TEXT
Some text here.

 - Line `with code`
 + Line 2
 * Line **bold**

more text
TEXT;

		$expected = <<<HTML
<p>Some text here.</p>

<ul>
<li>Line <code>with code</code></li>
<li>Line 2</li>
<li>Line <strong>bold</strong></li>
</ul>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * test that lists ending on the last line of the text are handled properly
 *
 * @return void
 */
	function testUnorderedListAtEndOfText() {
		$text = <<<TEXT
### Attributes:

 - `empty` - If true, the empty select option is shown.  If a string, 
    that string is displayed as the empty element.
 - this is another line
TEXT;

		$expected = <<<HTML
<h3>Attributes:</h3>

<ul>
<li><code>empty</code> - If true, the empty select option is shown.  If a string, 
that string is displayed as the empty element.</li>
<li>this is another line</li>
</ul>

<p></p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * Test simple ordered list parsing
 *
 * @return void
 */
	function testSimpleUnorderedList() {
		$text = <<<TEXT
Some text here.

 1. Line 1
 2. Line 2
 3. Line 3

more text
TEXT;

		$expected = <<<HTML
<p>Some text here.</p>

<ol>
<li>Line 1</li>
<li>Line 2</li>
<li>Line 3</li>
</ol>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);

		$text = <<<TEXT
Some text here.

 8. Line `with code`
 100. Line 2
 5. Line **bold**

more text
TEXT;

		$expected = <<<HTML
<p>Some text here.</p>

<ol>
<li>Line <code>with code</code></li>
<li>Line 2</li>
<li>Line <strong>bold</strong></li>
</ol>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * test nested one line lists
 *
 * @return void
 */
	function testNestedLists() {
		$text = <<<TEXT
Some text here.

 - Line 1
    - Indented 1
    - Indented 2
    - Indented 3
 - Line 2
 - Line 3

more text
TEXT;

		$expected = <<<HTML
<p>Some text here.</p>

<ul>
<li>Line 1
<ul>
<li>Indented 1</li>
<li>Indented 2</li>
<li>Indented 3</li>
</ul></li>
<li>Line 2</li>
<li>Line 3</li>
</ul>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);

		$text = <<<TEXT
Some text here.

 - Line 1
    - Indented 1
    - Indented 2
        - Indented 3
 - Line 2
 - Line 3

more text
TEXT;

		$expected = <<<HTML
<p>Some text here.</p>

<ul>
<li>Line 1
<ul>
<li>Indented 1</li>
<li>Indented 2
<ul>
<li>Indented 3</li>
</ul></li>
</ul></li>
<li>Line 2</li>
<li>Line 3</li>
</ul>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * test mixed lists.
 *
 * @return void
 */
	function testMixedList() {
		$text = <<<TEXT
Some text here.

 - Line 1
    1. Indented 1
    2. Indented 2
 - Line 2
 - Line 3

more text
TEXT;

		$expected = <<<HTML
<p>Some text here.</p>

<ul>
<li>Line 1
<ol>
<li>Indented 1</li>
<li>Indented 2</li>
</ol></li>
<li>Line 2</li>
<li>Line 3</li>
</ul>

<p>more text</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * test generation of class links
 *
 * @return void
 */
	function testClassLinks() {
		$generator = new ApiLinkGenerator(new View(new Controller));
		$generator->setClassIndex(array('model' => 'Model'));
		$this->Parser->setLinkGenerator($generator);
		$text = <<<TEXT
This is some text Model::save() more here
TEXT;

		$expected = <<<HTML
<p>This is some text <a href="/api_generator/api_classes/view_class/model#method-Modelsave">Model::save()</a> more here</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * test generation of class links
 *
 * @return void
 */
	function testPropertyLinks() {
		$generator = new ApiLinkGenerator(new View(new Controller));
		$generator->setClassIndex(array('model' => 'Model'));
		$this->Parser->setLinkGenerator($generator);
		$text = <<<TEXT
This is some text Model::\$order more here
TEXT;

		$expected = <<<HTML
<p>This is some text <a href="/api_generator/api_classes/view_class/model#property-Modelorder">Model::\$order</a> more here</p>
HTML;
		$result = $this->Parser->parse($text);
		$this->assertEqual($result, $expected);
	}

/**
 * tearDown
 *
 * @return void
 */
	function tearDown() {
		parent::tearDown();
		unset($this->Parser);
	}
}
