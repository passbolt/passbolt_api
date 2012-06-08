
steal.then(function() {
	
	
	/**
	 * @attribute DocumentJS.tags
	 * @parent DocumentJS
	 * A tag adds additional information to the comment being processed.
	 * For example, if you want the current comment to include the author,
	 * include an @@author tag.
	 * 
	 * ## Creating your own tag
	 * 
	 * To create a tag, you need to add to DocumentJS.tags an object with an add and an optional
	 * addMore method like:
	 * 
	 * @codestart
	 * DocumentJS.tags.mytag = {
	 *   add : function(line){ ... },
	 *   addMore : function(line, last){ ... }
	 * }
	 * @codeend 
	 */
	
	
	DocumentJS.tags = {};

}).then('documentjs/tags/alias.js', 
'documentjs/tags/author.js', 
'documentjs/tags/codeend.js', 
'documentjs/tags/codestart.js', 
'documentjs/tags/constructor.js', 
'documentjs/tags/demo.js', 
'documentjs/tags/description.js',
'documentjs/tags/download.js',
 'documentjs/tags/hide.js', 
 //'//documentjs/tags/iframe.js', 
 'documentjs/tags/inherits.js', 'documentjs/tags/page.js', 
 'documentjs/tags/param.js', 'documentjs/tags/parent.js',
  'documentjs/tags/plugin.js', 'documentjs/tags/return.js', 
  'documentjs/tags/scope.js', 'documentjs/tags/tag.js', 'documentjs/tags/test.js', 
  'documentjs/tags/type.js', 'documentjs/tags/image.js')