steal(function() {
	/**
	 * @class DocumentJS.tags.event
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
	 *  * @event MyEvent
	 *  * ...
	 *  *|
	 * @codeend
	 */
	return {
		add: function( line ) {
			var m = line.match(/^\s*@\w+ ([\w\.\$]+)/)
			if ( m ) {
				if(typeof this.exception == 'undefined'){
					this.exception = [];
				}
				this.exception.push(m[1]);
			}
		}
	};
})
