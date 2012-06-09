steal.then(function() {
	/**
	 * @class DocumentJS.tags.image
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Adds an image.
	 * 
	 * ###Example:
	 * 
	 * @codestart
	 * /* 
	 *  * @image jmvc/images/page_type_example.png 640 480
	 *  *|
	 * @codeend
	 */
	DocumentJS.tags.image = {
		add: function( line ) {
			var m = line.match(/^\s*@image\s*([^\s]+)\s*([\w]*)\s*([\w]*)\s*(.*)/)

			if ( m ) {
				var src = m[1] ? m[1] : '';
				this.comment += "<img class='image_tag' ";
				this.comment += "src='" + src + "' ";
				m[2] ? this.comment += "width='" + m[2] + "' " : true;
				m[3] ? this.comment += "height='" + m[3] + "' " : true;
				this.comment += "/>";
			}
		}
	};
})