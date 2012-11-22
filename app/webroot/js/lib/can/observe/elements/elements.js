steal('can/util', 'can/observe', function(can, Observe) {
	
var unique = function( items ) {
		var collect = [];
		// check unique property, if it isn't there, add to collect
		can.each(items, function( item ) {
			if (!item["__u Nique"] ) {
				collect.push(item);
				item["__u Nique"] = 1;
			}
		});
		// remove unique 
		return can.each(collect, function( item ) {
			delete item["__u Nique"];
		});
	}
		
	can.extend(can.Observe.prototype,{
		/**
		 * Returns a unique identifier for the observe instance.  For example:
		 *
		 * @codestart
		 * new Todo({id: 5}).identity() //-> 'todo_5'
		 * @codeend
		 *
		 * Typically this is used in an element's shortName property so you can find all elements
		 * for a observe with [$.Observe.prototype.elements elements].
		 *
		 * If your observe id has special characters that are not permitted as CSS class names,
		 * you can set the `escapeIdentity` on the observe instance's constructor
		 * which will `encodeURIComponent` the `id` of the observe.
		 *
		 * @return {String}
		 */
		identity: function() {
			var constructor = this.constructor,
				id = this[constructor.id] || this._cid.replace(/./, ''),
				name = constructor._fullName ? constructor._fullName + '_' : '';

			return (name + (constructor.escapeIdentity ? encodeURIComponent(id) : id)).replace(/ /g, '_');
		},
		/**
		 * Returns elements that represent this observe instance.  For this to work, your element's should
		 * us the [$.Observe.prototype.identity identity] function in their class name.  Example:
		 * 
		 *     <div class='todo <%= todo.identity() %>'> ... </div>
		 * 
		 * This also works if you hooked up the observe:
		 * 
		 *     <div <%= todo %>> ... </div>
		 *     
		 * Typically, you'll use this as a response to a Observe Event:
		 * 
		 *     "{Todo} destroyed": function(Todo, event, todo){
		 *       todo.elements(this.element).remove();
		 *     }
		 * 
		 * 
		 * @param {String|jQuery|element} context If provided, only elements inside this element
		 * that represent this observe will be returned.
		 * 
		 * @return {jQuery} Returns a jQuery wrapped nodelist of elements that have this observe instances
		 *  identity in their class name.
		 */
		elements: function( context ) {
			var id = this.identity();
			if( this.constructor.escapeIdentity ) {
				id = id.replace(/([ #;&,.+*~\'%:"!^$[\]()=>|\/])/g,'\\$1')
			}
			
			return can.$("." + id, context);
		},
		hookup: function( el ) {
			var shortName = this.constructor._shortName || '',
				$el = can.$(el),
				observes;
				
			(observes = can.data($el, "instances") )|| can.data($el, "instances", observes = {});
			can.addClass($el,shortName + " " + this.identity());
			observes[shortName] = this;
		}
	});


	/**
	 *  @add jQuery.fn
	 */
	// break
	/**
	 * @function instances
	 * Returns a list of observes.  If the observes are of the same
	 * type, and have a [$.Observe.List], it will return 
	 * the observes wrapped with the list.
	 * 
	 * @codestart
	 * $(".recipes").instances() //-> [recipe, ...]
	 * @codeend
	 * 
	 * @param {jQuery.Class} [type] if present only returns observes of the provided type.
	 * @return {Array|$.Observe.List} returns an array of observes instances that are represented by the contained elements.
	 */
	$.fn.instances = function( type ) {
		//get it from the data
		var collection = [],
			kind, ret, retType;
		this.each(function() {
			can.each($.data(this, "instances") || {}, function( instance, name ) {
				//either null or the list type shared by all classes
				kind = kind === undefined ? instance.constructor.List || null : (instance.constructor.List === kind ? kind : null);
				collection.push(instance);
			});
		});

		ret = kind ? new kind : new can.Observe.List;

		ret.push.apply(ret, unique(collection));
		return ret;
	};
	/**
	 * @function instance
	 * 
	 * Returns the first observe instance found from [jQuery.fn.instances] or
	 * sets the instance on an element.
	 * 
	 *     //gets an instance
	 *     ".edit click" : function(el) {
	 *       el.closest('.todo').instance().destroy()
	 *     },
	 *     // sets an instance
	 *     list : function(items){
	 *        var el = this.element;
	 *        $.each(item, function(item){
	 *          $('<div/>').instance(item)
	 *            .appendTo(el)
	 *        })
	 *     }
	 * 
	 * @param {Object} [type] The type of instance to return.  If a instance is provided
	 * it will add the instance to the element.
	 */
	$.fn.instance = function( type ) {
		if ( type && type instanceof can.Observe ) {
			type.hookup(this[0]);
			return this;
		} else {
			return this.instances.apply(this, arguments)[0];
		}

	};

	return can.Observe;
})
