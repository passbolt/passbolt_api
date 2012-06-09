steal.then(function() {
	/**
	 * @class DocumentJS.tags.plugin
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Adds to another plugin. 
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 * /**
	 *  * @tag core
	 *  * @plugin jquery/controller
	 *  * @download jquery/dist/jquery.controller.js
	 *  * @test jquery/controller/qunit.html
	 *  * ...
	 *  *|
	 *  $.Class.extend("jQuery.Controller",
	 * @codeend
	 * 
	 * ###End Result:
	 * 
	 * @image jmvc/images/plugin_tag_example.png
	 */
	DocumentJS.tags.plugin = {
		add: function( line ) {
			this.plugin = line.match(/@plugin ([^ ]+)/)[1];
		}
	}
})