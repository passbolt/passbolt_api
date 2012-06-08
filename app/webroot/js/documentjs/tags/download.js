steal.then(function() {
	/**
	 * @class DocumentJS.tags.download
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Adds a download link.
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 * /**
	 *  * @constructor jQuery.Drag
	 *  * ...
	 *  * @plugin jquery/event/drag
	 *  * @download jquery/dist/jquery.event.drag.js
	 *  * @test jquery/event/drag/qunit.html
	 *  * ...
	 *  *|
	 *  $.Drag = function(){}
	 * @codeend
	 * 
	 * You can click the example download link on this [jQuery.Drag | page]
     *
	 */
	DocumentJS.tags.download = {
		add: function( line ) {
			var parts = line.match(/^\s*@download\s*([^ ]*)\s*([.]*)/)
			this.download = parts[1];
			if(parts[2]){
				this.downloadName = parts[2]
			}
		}
	};
})