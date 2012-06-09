steal.then(function() {
	/**
	 * @class DocumentJS.tags.tag
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Tags for searching.
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 * /**
	 *  * @tag core
	 *  * @plugin jquery/controller
	 *  * @download jquery/dist/jquery.controller.js
	 *  *`@test jquery/controller/qunit.html
	 *  * ...
	 *  *|
	 *  $.Class.extend("jQuery.Controller", 
	 * @codeend
	 * 
	 * ###End Result:
	 * 
	 * @image jmvc/images/tag_tag_example.png
	 */
	DocumentJS.tags.tag = {
		add: function( line ) {
			var parts = line.match(/^\s*@tag\s*(.+)/);

			if (!parts ) {
				return;
			}
			this.tags = parts[1].split(/\s*,\s*/g)
			//return this.ret;
		} //,
		//add_more : function(line){
		//    this.tags.concat(line.split(/\s*,\s*/g))
		//}
	};
})