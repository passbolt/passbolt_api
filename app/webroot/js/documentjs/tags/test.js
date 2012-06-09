steal.then(function() {
	/**
	 * @class DocumentJS.tags.test
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Link to test cases.
	 * 
	 * #Example
	 * 
	 * @codestart
	 * /*
	 *  * @constructor jQuery.Drag
	 *  * @parent specialevents
	 *  * @plugin jquery/event/drag
	 *  * @download jquery/dist/jquery.event.drag.js
	 *  * @test jquery/event/drag/qunit.html
	 *  * ...
	 *  *|
	 *  $.Drag = function(){}
	 * @codeend
	 * 
	 * ###End Result:
	 * @image jmvc/images/test_tag_example.png
	 * @image jmvc/images/test_tag_test_example.png
	 */
	DocumentJS.tags.test = {
		add: function( line ) {
			this.test = line.match(/@test ([^ ]+)/)[1];
		}
	};
})