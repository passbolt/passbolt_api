steal(function() {
	/**
	 * @class DocumentJS.tags.see
	 * @tag documentation
	 * @parent DocumentJS.tags 
	 * 
	 * Says current class is linked to another class
	 *
	 * ###Example:
	 * 
	 * @codestart
	 * /*
	 *  * @class Client
	 *  * @see Person
	 *  * ...
	 *  *|
	 * @codeend
	 */
	return {
		add: function( line ) {
			// var m = line.match(/^\s*@\w+ ([\w\.\$]+)/)
			// if ( m ) {
				// if(typeof this.sees == 'undefined'){
					// this.sees = [];
				// }
				// this.sees.push(m[1]);
			// }
		}
	};
})
