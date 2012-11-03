steal('can/util', 'can/model', function(can, Model) {
	
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
		
	can.extend(can.Model.prototype,{
		/**
		 * Returns a unique identifier for the model instance.  For example:
		 *
		 * @codestart
		 * new Todo({id: 5}).identity() //-> 'todo_5'
		 * @codeend
		 *
		 * Typically this is used in an element's shortName property so you can find all elements
		 * for a model with [$.Model.prototype.elements elements].
		 *
		 * If your model id has special characters that are not permitted as CSS class names,
		 * you can set the `escapeIdentity` on the model instance's constructor
		 * which will `encodeURIComponent` the `id` of the model.
		 *
		 * @return {String}
		 */
		identity: function() {
			var id = this[this.constructor.id],
				constructor = this.constructor;
			return (constructor._fullName + '_' + (constructor.escapeIdentity ? encodeURIComponent(id) : id)).replace(/ /g, '_');
		},
		/**
		 * Returns elements that represent this model instance.  For this to work, your element's should
		 * us the [$.Model.prototype.identity identity] function in their class name.  Example:
		 * 
		 *     <div class='todo <%= todo.identity() %>'> ... </div>
		 * 
		 * This also works if you hooked up the model:
		 * 
		 *     <div <%= todo %>> ... </div>
		 *     
		 * Typically, you'll use this as a response to a Model Event:
		 * 
		 *     "{Todo} destroyed": function(Todo, event, todo){
		 *       todo.elements(this.element).remove();
		 *     }
		 * 
		 * 
		 * @param {String|jQuery|element} context If provided, only elements inside this element
		 * that represent this model will be returned.
		 * 
		 * @return {jQuery} Returns a jQuery wrapped nodelist of elements that have this model instances
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
			var shortName = this.constructor._shortName,
				$el = can.$(el),
				models;
				
			(models = can.data($el, "models") )|| can.data($el, "models", models = {});
			can.addClass($el,shortName + " " + this.identity());
			models[shortName] = this;
		}
	});


	/**
	 *  @add jQuery.fn
	 */
	// break
	/**
	 * @function models
	 * Returns a list of models.  If the models are of the same
	 * type, and have a [$.Model.List], it will return 
	 * the models wrapped with the list.
	 * 
	 * @codestart
	 * $(".recipes").models() //-> [recipe, ...]
	 * @codeend
	 * 
	 * @param {jQuery.Class} [type] if present only returns models of the provided type.
	 * @return {Array|$.Model.List} returns an array of model instances that are represented by the contained elements.
	 */
	$.fn.models = function( type ) {
		//get it from the data
		var collection = [],
			kind, ret, retType;
		this.each(function() {
			can.each($.data(this, "models") || {}, function( instance, name ) {
				//either null or the list type shared by all classes
				kind = kind === undefined ? instance.constructor.List || null : (instance.constructor.List === kind ? kind : null);
				collection.push(instance);
			});
		});

		ret = kind ? new kind : new can.Model.List;

		ret.push.apply(ret, unique(collection));
		return ret;
	};
	/**
	 * @function model
	 * 
	 * Returns the first model instance found from [jQuery.fn.models] or
	 * sets the model instance on an element.
	 * 
	 *     //gets an instance
	 *     ".edit click" : function(el) {
	 *       el.closest('.todo').model().destroy()
	 *     },
	 *     // sets an instance
	 *     list : function(items){
	 *        var el = this.element;
	 *        $.each(item, function(item){
	 *          $('<div/>').model(item)
	 *            .appendTo(el)
	 *        })
	 *     }
	 * 
	 * @param {Object} [type] The type of model to return.  If a model instance is provided
	 * it will add the model to the element.
	 */
	$.fn.model = function( type ) {
		if ( type && type instanceof can.Model ) {
			type.hookup(this[0]);
			return this;
		} else {
			return this.models.apply(this, arguments)[0];
		}

	};

	return can;
})
