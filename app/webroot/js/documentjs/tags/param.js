steal.then(function() {
	(function() {

		var ordered = function( params ) {
			var arr = [];
			for ( var n in params ) {
				var param = params[n];
				arr[param.order] = param;
			}
			return arr;
		}


		/**
		 * @class DocumentJS.tags.param
		 * @tag documentation
		 * @parent DocumentJS.tags 
		 * 
		 * Adds parameter information.
		 * 
		 * ###Use cases:
		 * 
		 * 1. Common use:
		 * 
		 *      __@@params {TYPE} name description__
		 * 
		 * 2. Optional parameters use case:
		 * 
         *     __@@params {TYPE} [name] description__
         * 
         * 3. Default value use case:
         * 
         *     __@@params {TYPE} [name=default] description__
		 *
		 * ###Example:
		 * 
		 * @codestart
	     * /*
	     *  * Finds an order by id.
	     *  * @@param {String} id Order identification number.
	     *  * @@param {Date} [date] Filter order search by this date.
	     *  *|
	     *  findById: function(id, date) {
         *      // looks for an order by id
	     *  }   
    	 *  @codeend
    	 *  
    	 * 
		 */
		DocumentJS.tags.param = {

			addMore: function( line, last ) {
				if ( last ) last.description += "\n" + line;
			},
			/**
			 * Adds @param data to the constructor function
			 * @param {String} line
			 */
			add: function( line ) {
				if (!this.params ) {
					this.params = {};
				}
				var parts = line.match(/\s*@param\s+(?:\{?([^}]+)\}?)?\s+([^\(\s]+(?:\([^\)]+\))?) ?(.*)?/);
				if (!parts ) {
					print("LINE: \n" + line + "\n does not match @params {TYPE} NAME DESCRIPTION")
					return;
				}
				var description = parts.pop();
				var n = parts.pop(),
					optional = false,
					defaultVal;
				
				//check if it has anything ...
				var nameParts = n.match(/\[([\w\.\$]+)(?:=([^\]]*))?\]/)
				if ( nameParts ) {
					optional = true;
					defaultVal = nameParts[2]
					n = nameParts[1]
				}
				// check if parens 

				var nameParts = n.match(/([^\(\s]+)(\([^\)]+\))/) 

				if ( nameParts && this.params[nameParts[1]]) {
					var order = this.params[nameParts[1]].order;
					delete this.params[nameParts[1]];
				}
				var param = this.params[n] ? 
					this.params[n] : 
					this.params[n] = {
							order: order === undefined ? ordered(this.params).length : order
						};

				
				param.description = description || "";
				param.name = n;
				param.type = parts.pop() || "";


				param.optional = optional;
				if ( defaultVal ) {
					param["default"] = defaultVal;
				}

				return this.params[n];
			},
			done : function(){
				if(this.ret && this.ret.description && this.ret.description ){
					this.ret.description = DocumentJS.converter.makeHtml(this.ret.description)
				}
				if(this.params){
					for(var paramName in this.params){
						if(this.params[paramName].description  ){
							this.params[paramName].description = DocumentJS.converter.makeHtml(this.params[paramName].description)
						}
					}
				}
			}
		};

	})()
})