steal('documentjs/showdown.js',function(converter) {
	/**
	 * @class DocumentJS.tags.constructor
	 * @parent DocumentJS.tags
	 *   
	 * Documents a constructor function and its parameters. 
	 * 
	 * ###Example:
	 * 
	 * @codestart
     * /*
     *  * @@class Customer 
     *  * @@constructor
     *  * Creates a new customer.
     *  * @param {String} name
     *  *|
     *  var Customer = function(name) {
	 *     this.name = name;
     *  }
	 * @codeend
	 */
	return {
		add: function( line ) {
			var parts = line.match(/\s?@constructor(.*)?/);

			this.construct = parts && parts[1] ? " " + parts[1] + "\n" : ""
			this.ret = {
				type: this.alias ? this.alias.toLowerCase() : this.name.toLowerCase(),
				description: ""
			}
			return ["default", 'construct'];
		},

		done : function(){
			if(this.construct ){
				this.construct = converter.makeHtml(this.construct)
			}
		}
	};
})