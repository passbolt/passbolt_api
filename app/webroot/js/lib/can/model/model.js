// this file should not be stolen directly
steal('can/util','can/observe', function( can ) {
	
	// ## model.js  
	// `can.Model`  
	// _A `can.Observe` that connects to a RESTful interface._
	//  
	// Generic deferred piping function
	/**
	 * @add can.Model
	 */
	var	pipe = function( def, model, func ) {
		var d = new can.Deferred();
		def.then(function(){
			var args = can.makeArray( arguments );
			args[0] = model[func](args[0]);
			d.resolveWith(d, args);
		},function(){
			d.rejectWith(this, arguments);
		});

		if(typeof def.abort === 'function') {
			d.abort = function() {
				return def.abort();
			}
		}

		return d;
	},
		modelNum = 0,
		ignoreHookup = /change.observe\d+/,
		getId = function( inst ) {
			// Instead of using attr, use __get for performance.
			// Need to set reading
			can.Observe.__reading && can.Observe.__reading(inst, inst.constructor.id)
			return inst.__get(inst.constructor.id);
		},
		// Ajax `options` generator function
		ajax = function( ajaxOb, data, type, dataType, success, error ) {

			var params = {};
			
			// If we get a string, handle it.
			if ( typeof ajaxOb == "string" ) {
				// If there's a space, it's probably the type.
				var parts = ajaxOb.split(/\s/);
				params.url = parts.pop();
				if ( parts.length ) {
					params.type = parts.pop();
				}
			} else {
				can.extend( params, ajaxOb );
			}

			// If we are a non-array object, copy to a new attrs.
			params.data = typeof data == "object" && ! can.isArray( data ) ?
				can.extend(params.data || {}, data) : data;
	
			// Get the url with any templated values filled out.
			params.url = can.sub(params.url, params.data, true);

			return can.ajax( can.extend({
				type: type || "post",
				dataType: dataType ||"json",
				success : success,
				error: error
			}, params ));
		},
		makeRequest = function( self, type, success, error, method ) {
			var deferred,
				args = [self.serialize()],
				// The model.
				model = self.constructor,
				jqXHR;

			// `destroy` does not need data.
			if ( type == 'destroy' ) {
				args.shift();
			}
			// `update` and `destroy` need the `id`.
			if ( type !== 'create' ) {
				args.unshift(getId(self));
			}

			
			jqXHR = model[type].apply(model, args);
			
			deferred = jqXHR.pipe(function(data){
				self[method || type + "d"](data, jqXHR);
				return self;
			});

			// Hook up `abort`
			if(jqXHR.abort){
				deferred.abort = function(){
					jqXHR.abort();
				};
			}

			deferred.then(success,error);
			return deferred;
		},
	
	// This object describes how to make an ajax request for each ajax method.  
	// The available properties are:
	//		`url` - The default url to use as indicated as a property on the model.
	//		`type` - The default http request type
	//		`data` - A method that takes the `arguments` and returns `data` used for ajax.
	/** 
	 * @Static
	 */
	//
		/**
		 * @function bind
		 * `bind(eventType, handler(event, instance))` listens to
		 * __created__, __updated__, __destroyed__ events on all 
		 * instances of the model.
		 * 
		 *     Task.bind("created", function(ev, createdTask){
		 * 	     this //-> Task
		 *       createdTask.attr("name") //-> "Dishes"
		 *     })
		 *     
		 *     new Task({name: "Dishes"}).save();
		 * 
		 * @param {String} eventType The type of event.  It must be
		 * `"created"`, `"udpated"`, `"destroyed"`.
		 * 
		 * @param {Function} handler(event,instance) A callback function
		 * that gets called with the event and instance that was
		 * created, destroyed, or updated.
		 * 
		 * @return {can.Model} the model constructor function.
		 */
		// 
		/**
		 * @function unbind
		 * `unbind(eventType, handler)` removes a listener
		 * attached with [can.Model.bind].
		 * 
		 *     var handler = function(ev, createdTask){
		 * 	     
		 *     }
		 *     Task.bind("created", handler)
		 *     Task.unbind("created", handler)
		 * 
		 * You have to pass the same function to `unbind` that you
		 * passed to `bind`.
		 * 
		 * @param {String} eventType The type of event.  It must be
		 * `"created"`, `"udpated"`, `"destroyed"`.
		 * 
		 * @param {Function} handler(event,instance) A callback function
		 * that was passed to `bind`.
		 * 
		 * @return {can.Model} the model constructor function.
		 */
		// 
		/**
		 * @attribute id
		 * The name of the id field.  Defaults to 'id'. Change this if it is something different.
		 * 
		 * For example, it's common in .NET to use Id.  Your model might look like:
		 * 
		 *     Friend = can.Model({
		 *       id: "Id"
		 *     },{});
		 */
	ajaxMethods = {
		/**
		 * @function create
		 * `create(attributes) -> Deferred` is used by [can.Model::save save] to create a 
		 * model instance on the server. 
		 * 
		 * ## Implement with a URL
		 * 
		 * The easiest way to implement create is to give it the url 
		 * to post data to:
		 * 
		 *     var Recipe = can.Model({
		 *       create: "/recipes"
		 *     },{})
		 *     
		 * This lets you create a recipe like:
		 *  
		 *     new Recipe({name: "hot dog"}).save();
		 * 
		 * 
		 * ## Implement with a Function
		 * 
		 * You can also implement create by yourself. Create gets called 
		 * with `attrs`, which are the [can.Observe::serialize serialized] model 
		 * attributes.  Create returns a `Deferred` 
		 * that contains the id of the new instance and any other 
		 * properties that should be set on the instance.
		 *  
		 * For example, the following code makes a request 
		 * to `POST /recipes.json {'name': 'hot+dog'}` and gets back
		 * something that looks like:
		 *  
		 *     { 
		 *       "id": 5,
		 *       "createdAt": 2234234329
		 *     }
		 * 
		 * The code looks like:
		 * 
		 *     can.Model("Recipe", {
		 *       create : function( attrs ){
		 *         return $.post("/recipes.json",attrs, undefined ,"json");
		 *       }
		 *     },{})
		 * 
		 * 
		 * @param {Object} attrs Attributes on the model instance
		 * @return {Deferred} A deferred that resolves to 
		 * an object with the id of the new instance and
		 * other properties that should be set on the instance.
		 */
		create : {
			url : "_shortName",
			type :"post"
		},
		/**
		 * @function update
		 * `update( id, attrs ) -> Deferred` is used by [can.Model::save save] to 
		 * update a model instance on the server. 
		 * 
		 * ## Implement with a URL
		 * 
		 * The easist way to implement update is to just give it the url to `PUT` data to:
		 * 
		 *     Recipe = can.Model({
		 *       update: "/recipes/{id}"
		 *     },{});
		 *     
		 * This lets you update a recipe like:
		 *  
		 *     Recipe.findOne({id: 1}, function(recipe){
		 *       recipe.attr('name','salad');
		 *       recipe.save();
		 *     })
		 * 
		 * This will make an XHR request like:
		 * 
		 *     PUT /recipes/1 
		 *     name=salad
		 *  
		 * If your server doesn't use PUT, you can change it to post like:
		 * 
		 *     $.Model("Recipe",{
		 *       update: "POST /recipes/{id}"
		 *     },{});
		 * 
		 * The server should send back an object with any new attributes the model 
		 * should have.  For example if your server udpates the "updatedAt" property, it
		 * should send back something like:
		 * 
		 *     // PUT /recipes/4 {name: "Food"} ->
		 *     {
		 *       updatedAt : "10-20-2011"
		 *     }
		 * 
		 * ## Implement with a Function
		 * 
		 * You can also implement update by yourself.  Update takes the `id` and
		 * `attributes` of the instance to be udpated.  Update must return
		 * a [can.Deferred Deferred] that resolves to an object that contains any 
		 * properties that should be set on the instance.
		 *  
		 * For example, the following code makes a request 
		 * to '/recipes/5.json?name=hot+dog' and gets back
		 * something that looks like:
		 *  
		 *     { 
		 *       updatedAt: "10-20-2011"
		 *     }
		 * 
		 * The code looks like:
		 * 
		 *     Recipe = can.Model({
		 *       update : function(id, attrs ) {
		 *         return $.post("/recipes/"+id+".json",attrs, null,"json");
		 *       }
		 *     },{});
		 * 
		 * 
		 * @param {String} id the id of the model instance
		 * @param {Object} attrs Attributes on the model instance
		 * @return {Deferred} A deferred that resolves to
		 * an object of attribute / value pairs of property changes the client doesn't already 
		 * know about. For example, when you update a name property, the server might 
		 * update other properties as well (such as updatedAt). The server should send 
		 * these properties as the response to updates.  
		 */
		update : {
			data : function(id, attrs){
				attrs = attrs || {};
				var identity = this.id;
				if ( attrs[identity] && attrs[identity] !== id ) {
					attrs["new" + can.capitalize(id)] = attrs[identity];
					delete attrs[identity];
				}
				attrs[identity] = id;
				return attrs;
			},
			type : "put"
		},
		/**
		 * @function destroy
		 * `destroy(id) -> Deferred` is used by [can.Model::destroy] remove a model 
		 * instance from the server.
		 * 
		 * ## Implement with a URL
		 * 
		 * You can implement destroy with a string like:
		 * 
		 *     Recipe = can.Model({
		 *       destroy : "/recipe/{id}"
		 *     },{})
		 * 
		 * And use [can.Model::destroy] to destroy it like:
		 * 
		 *     Recipe.findOne({id: 1}, function(recipe){
		 * 	      recipe.destroy();
		 *     });
		 * 
		 * This sends a `DELETE` request to `/thing/destroy/1`.
		 * 
		 * If your server does not support `DELETE` you can override it like:
		 * 
		 *     Recipe = can.Model({
		 *       destroy : "POST /recipe/destroy/{id}"
		 *     },{})
		 * 
		 * ## Implement with a function
		 * 
		 * Implement destroy with a function like:
		 * 
		 *     Recipe = can.Model({
		 *       destroy : function(id){
		 *         return $.post("/recipe/destroy/"+id,{});
		 *       }
		 *     },{})
		 * 
		 * Destroy just needs to return a deferred that resolves.
		 * 
		 * @param {String|Number} id the id of the instance you want destroyed
		 * @return {Deferred} a deferred that resolves when the model instance is destroyed.
		 */
		destroy : {
			type : "delete",
			data : function(id){
				var args = {};
				args.id = args[this.id] = id;
				return args;
			}
		},
		/**
		 * @function findAll
		 * `findAll( params, success(instances), error(xhr) ) -> Deferred` is used to retrieve model 
		 * instances from the server. Before you can use `findAll`, you must implement it.
		 * 
		 * ## Implement with a URL
		 * 
		 * Implement findAll with a url like:
		 * 
		 *     Recipe = can.Model({
		 *       findAll : "/recipes.json"
		 *     },{});
		 * 
		 * The server should return data that looks like:
		 * 
		 *     [
		 *       {"id" : 57, "name": "Ice Water"},
		 *       {"id" : 58, "name": "Toast"}
		 *     ]
		 * 
		 * ## Implement with an Object
		 * 
		 * Implement findAll with an object that specifies the parameters to
		 * `can.ajax` (jQuery.ajax) like:
		 * 
		 *     Recipe = can.Model({
		 *       findAll : {
		 *         url: "/recipes.xml",
		 *         dataType: "xml"
		 *       }
		 *     },{})
		 * 
		 * ## Implement with a Function
		 * 
		 * To implement with a function, `findAll` is passed __params__ to filter
		 * the instances retrieved from the server and it should return a
		 * deferred that resolves to an array of model data. For example:
		 * 
		 *     Recipe = can.Model({
		 *       findAll : function(params){
		 *         return $.ajax({
		 *           url: '/recipes.json',
		 *           type: 'get',
		 *           dataType: 'json'})
		 *       }
		 *     },{})
		 * 
		 * ## Use
		 * 
		 * After implementing `findAll`, you can use it to retrieve instances of the model
		 * like:
		 * 
		 *     Recipe.findAll({favorite: true}, function(recipes){
		 *       recipes[0].attr('name') //-> "Ice Water"
		 *     }, function( xhr ){
		 *       // called if an error
		 *     }) //-> Deferred
		 * 
		 * The following API details the use of `findAll`.
		 * 
		 * @param {Object} params data to refine the results.  An example might be passing {limit : 20} to
		 * limit the number of items retrieved.
		 * 
		 *     Recipe.findAll({limit: 20})
		 * 
		 * @param {Function} [success(items)] called with a [can.Model.List] of model 
		 * instances.  The model isntances are created from the Deferred's resolved data.
		 * 
		 *     Recipe.findAll({limit: 20}, function(recipes){
		 *       recipes.constructor //-> can.Model.List
		 *     })
		 * 
		 * @param {Function} error(xhr) `error` is called if the Deferred is rejected with the
		 * xhr handler.
		 * 
		 * @return {Deferred} a [can.Deferred Deferred] that __resolves__ to
		 * a [can.Model.List] of the model instances and __rejects__ to the XHR object.
		 * 
		 *     Recipe.findAll()
		 *           .then(function(recipes){
		 * 	
		 *           }, function(xhr){
		 * 	
		 *           })
		 */
		findAll : {
			url : "_shortName"
		},
		/**
		 * @function findOne
		 * `findOne( params, success(instance), error(xhr) ) -> Deferred` is used to retrieve a model 
		 * instance from the server. Before you can use `findOne`, you must implement it.
		 * 
		 * ## Implement with a URL
		 * 
		 * Implement findAll with a url like:
		 * 
		 *     Recipe = can.Model({
		 *       findOne : "/recipes/{id}.json"
		 *     },{});
		 * 
		 * If `findOne` is called like:
		 * 
		 *     Recipe.findOne({id: 57});
		 * 
		 * The server should return data that looks like:
		 * 
		 *     {"id" : 57, "name": "Ice Water"}
		 * 
		 * ## Implement with an Object
		 * 
		 * Implement `findOne` with an object that specifies the parameters to
		 * `can.ajax` (jQuery.ajax) like:
		 * 
		 *     Recipe = can.Model({
		 *       findOne : {
		 *         url: "/recipes/{id}.xml",
		 *         dataType: "xml"
		 *       }
		 *     },{})
		 * 
		 * ## Implement with a Function
		 * 
		 * To implement with a function, `findOne` is passed __params__ to specify
		 * the instance retrieved from the server and it should return a
		 * deferred that resolves to the model data.  Also notice that you now need to
		 * build the URL manually. For example:
		 * 
		 *     Recipe = can.Model({
		 *       findOne : function(params){
		 *         return $.ajax({
		 *           url: '/recipes/' + params.id,
		 *           type: 'get',
		 *           dataType: 'json'})
		 *       }
		 *     },{})
		 * 
		 * ## Use
		 * 
		 * After implementing `findOne`, you can use it to retrieve an instance of the model
		 * like:
		 * 
		 *     Recipe.findOne({id: 57}, function(recipe){
		 * 	     recipe.attr('name') //-> "Ice Water"
		 *     }, function( xhr ){
		 * 	     // called if an error
		 *     }) //-> Deferred
		 * 
		 * The following API details the use of `findOne`.
		 * 
		 * @param {Object} params data to specify the instance. 
		 * 
		 *     Recipe.findAll({id: 20})
		 *
		 * @param {Function} [success(item)] called with a model 
		 * instance.  The model isntance is created from the Deferred's resolved data.
		 * 
		 *     Recipe.findOne({id: 20}, function(recipe){
		 *       recipe.constructor //-> Recipe
		 *     })
		 * 
		 * @param {Function} error(xhr) `error` is called if the Deferred is rejected with the
		 * xhr handler.
		 * 
		 * @return {Deferred} a [can.Deferred Deferred] that __resolves__ to
		 * the model instance and __rejects__ to the XHR object.
		 * 
		 *     Recipe.findOne({id: 20})
		 *           .then(function(recipe){
		 * 	
		 *           }, function(xhr){
		 * 	
		 *           })
		 */
		findOne: {}
	},
		// Makes an ajax request `function` from a string.
		//		`ajaxMethod` - The `ajaxMethod` object defined above.
		//		`str` - The string the user provided. Ex: `findAll: "/recipes.json"`.
		ajaxMaker = function(ajaxMethod, str){
			// Return a `function` that serves as the ajax method.
			return function(data){
				// If the ajax method has it's own way of getting `data`, use that.
				data = ajaxMethod.data ? 
					ajaxMethod.data.apply(this, arguments) :
					// Otherwise use the data passed in.
					data;
				// Return the ajax method with `data` and the `type` provided.
				return ajax(str || this[ajaxMethod.url || "_url"], data, ajaxMethod.type || "get")
			}
		}


	
	
	can.Model = can.Observe({
		fullName: "can.Model",
		setup : function(base){
			// create store here if someone wants to use model without inheriting from it
			this.store = {};
			can.Observe.setup.apply(this, arguments);
			// Set default list as model list
			if(!can.Model){
				return;
			}
			this.List = ML({Observe: this},{});
			var self = this,
				clean = can.proxy(this._clean, self);
			
			
			// go through ajax methods and set them up
			can.each(ajaxMethods, function(method, name){
				// if an ajax method is not a function, it's either
				// a string url like findAll: "/recipes" or an
				// ajax options object like {url: "/recipes"}
				if ( ! can.isFunction( self[name] )) {
					// use ajaxMaker to convert that into a function
					// that returns a deferred with the data
					self[name] = ajaxMaker(method, self[name]);
				}
				// check if there's a make function like makeFindAll
				// these take deferred function and can do special
				// behavior with it (like look up data in a store)
				if (self["make"+can.capitalize(name)]){
					// pass the deferred method to the make method to get back
					// the "findAll" method.
					var newMethod = self["make"+can.capitalize(name)](self[name]);
					can.Construct._overwrite(self, base, name,function(){
						// increment the numer of requests
						this._reqs++;
						var def = newMethod.apply(this, arguments);
						var then = def.then(clean, clean);
						then.abort = def.abort;

						// attach abort to our then and return it
						return then;
					})
				}
			});

			if(self.fullName == "can.Model" || !self.fullName){
				self.fullName = "Model"+(++modelNum);
			}
			// Add ajax converters.
			this._reqs = 0;
			this._url = this._shortName+"/{"+this.id+"}"
		},
		_ajax : ajaxMaker,
		_clean : function(){
			this._reqs--;
			if(!this._reqs){
				for(var id in this.store) {
					if(!this.store[id]._bindings){
						delete this.store[id];
					}
				}
			}
			return arguments[0];
		},
		/**
		 * `can.Model.models(data, xhr)` is used to 
		 * convert the raw response of a [can.Model.findAll] request 
		 * into a [can.Model.List] of model instances.  
		 * 
		 * This method is rarely called directly. Instead the deferred returned
		 * by findAll is piped into `models`.  This creates a new deferred that
		 * resolves to a [can.Model.List] of instances instead of an array of
		 * simple JS objects.
		 * 
		 * If your server is returning data in non-standard way,
		 * overwriting `can.Model.models` is the best way to normalize it.
		 * 
		 * ## Quick Example
		 * 
		 * The following uses models to convert to a [can.Model.List] of model
		 * instances.
		 * 
		 *     Task = can.Model({},{})
		 *     var tasks = Task.models([
		 *       {id: 1, name : "dishes", complete : false},
		 *       {id: 2, name: "laundry", compelte: true}
		 *     ])
		 *     
		 *     tasks.attr("0.complete", true)
		 * 
		 * ## Non-standard Services
		 * 
		 * `can.Model.models` expects data to be an array of name-value pair 
		 * objects like:
		 * 
		 *     [{id: 1, name : "dishes"},{id:2, name: "laundry"}, ...]
		 *     
		 * It can also take an object with additional data about the array like:
		 * 
		 *     {
		 *       count: 15000 //how many total items there might be
		 *       data: [{id: 1, name : "justin"},{id:2, name: "brian"}, ...]
		 *     }
		 * 
		 * In this case, models will return a [can.Model.List] of instances found in 
		 * data, but with additional properties as expandos on the list:
		 * 
		 *     var tasks = Task.models({
		 *       count : 1500,
		 *       data : [{id: 1, name: 'dishes'}, ...]
		 *     })
		 *     tasks.attr("name") // -> 'dishes'
		 *     tasks.count // -> 1500
		 * 
		 * ### Overwriting Models
		 * 
		 * If your service returns data like:
		 * 
		 *     {thingsToDo: [{name: "dishes", id: 5}]}
		 * 
		 * You will want to overwrite models to pass the base models what it expects like:
		 * 
		 *     Task = can.Model({
		 *       models : function(data){
		 *         return can.Model.models.call(this,data.thingsToDo);
		 *       }
		 *     },{})
		 * 
		 * `can.Model.models` passes each intstance's data to `can.Model.model` to
		 * create the individual instances.
		 * 
		 * @param {Array|Objects} instancesRawData An array of raw name - value pairs objects like:
		 * 
		 *      [{id: 1, name : "dishes"},{id:2, name: "laundry"}, ...]
		 * 
		 * Or an Object with a data property and other expando properties like:
		 * 
		 *     {
		 *       count: 15000 //how many total items there might be
		 *       data: [{id: 1, name : "justin"},{id:2, name: "brian"}, ...]
		 *     }
		 *
		 * @param {can.Observe.List} [oldList] If passed, updates oldList with the converted instancesRawData
		 * instead of returning a new model list.
		 * @return {Array} a [can.Model.List] of instances.  Each instance is created with
		 * [can.Model.model].
		 */
		models: function( instancesRawData, oldList ) {

			if ( ! instancesRawData ) {
				return;
			}
      
			if ( instancesRawData instanceof this.List ) {
				return instancesRawData;
			}

			// Get the list type.
			var self = this,
				tmp = [],
				res = oldList instanceof can.Observe.List ? oldList : new( self.List || ML),
				// Did we get an `array`?
				arr = can.isArray(instancesRawData),
				
				// Did we get a model list?
				ml = (instancesRawData instanceof ML),

				// Get the raw `array` of objects.
				raw = arr ?

				// If an `array`, return the `array`.
				instancesRawData :

				// Otherwise if a model list.
				(ml ?

				// Get the raw objects from the list.
				instancesRawData.serialize() :

				// Get the object's data.
				instancesRawData.data),
				i = 0;

			//!steal-remove-start
			if ( ! raw.length ) {
				steal.dev.warn("model.js models has no data.")
			}
			//!steal-remove-end

			if(res.length > 0) {
				res.splice(0);
			}

			can.each(raw, function( rawPart ) {
				tmp.push( self.model( rawPart ));
			});

			// We only want one change event so push everything at once
			res.push.apply(res, tmp);

			if ( ! arr ) { // Push other stuff onto `array`.
				can.each(instancesRawData, function(val, prop){
					if ( prop !== 'data' ) {
						res.attr(prop, val);
					}
				})
			}
			return res;
		},
		/**
		 * `can.Model.model(attributes)` is used to convert data from the server into
		 * a model instance.  It is rarely called directly.  Instead it is invoked as 
		 * a result of [can.Model.findOne] or [can.Model.findAll].  
		 * 
		 * If your server is returning data in non-standard way,
		 * overwriting `can.Model.model` is a good way to normalize it.
		 * 
		 * ## Example
		 * 
		 * The following uses `model` to convert to a model
		 * instance.
		 * 
		 *     Task = can.Model({},{})
		 *     var task = Task.model({id: 1, name : "dishes", complete : false})
		 *     
		 *     tasks.attr("complete", true)
		 * 
		 * `Task.model(attrs)` is very similar to simply calling `new Model(attrs)` except
		 * that it checks the model's store if the instance has already been created.  The model's 
		 * store is a collection of instances that have event handlers.  
		 * 
		 * This means that if the model's store already has an instance, you'll get the same instance
		 * back.  Example:
		 * 
		 *     // create a task
		 *     var taskA = new Task({id: 5, complete: true});
		 * 
		 *     // bind to it, which puts it in the store
		 * 	   taskA.bind("complete", function(){});
		 *     
		 *     // use model to create / retrieve a task
		 *     var taskB = Task.model({id: 5, complete: true});
		 *     
		 *     taskA === taskB //-> true
		 * 
		 * ## Non-standard Services
		 * 
		 * `can.Model.model` expects to retreive attributes of the model 
		 * instance like:
		 * 
		 * 
		 *     {id: 5, name : "dishes"}
		 *     
		 * 
		 * If the service returns data formatted differently, like:
		 * 
		 *     {todo: {name: "dishes", id: 5}}
		 * 
		 * Overwrite `model` like:
		 * 
		 *     Task = can.Model({
		 *       model : function(data){
		 *         return can.Model.model.call(this,data.todo);
		 *       }
		 *     },{});
		 * 
		 * @param {Object} attributes An object of property name and values like:
		 * 
		 *      {id: 1, name : "dishes"}
		 * 
		 * @return {model} a model instance.
		 */
		model: function( attributes ) {
			if ( ! attributes ) {
				return;
			}
			if ( attributes instanceof this ) {
				attributes = attributes.serialize();
			}
			var id = attributes[ this.id ],
			    model = id && this.store[id] ? this.store[id].attr(attributes) : new this( attributes );
			if(this._reqs){
				this.store[attributes[this.id]] = model;
			}
			return model;
		}
	},
	/**
	 * @prototype
	 */
	{
		/**
		 * `isNew()` returns if the instance is has been created 
		 * on the server.  
		 * This is essentially if the [can.Model.id] property is null or undefined.
		 * 
		 *     new Recipe({id: 1}).isNew() //-> false
		 * @return {Boolean} false if an id is set, true if otherwise.
		 */
		isNew: function() {
			var id = getId(this);
			return ! ( id || id === 0 ); // If `null` or `undefined`
		},
		/**
		 * `model.save([success(model)],[error(xhr)])` creates or updates 
		 * the model instance using [can.Model.create] or
		 * [can.Model.update] depending if the instance
		 * [can.Model::isNew has an id or not].
		 * 
		 * ## Using `save` to create an instance.
		 * 
		 * If `save` is called on an instance that does not have 
		 * an [can.Model.id id] property, it calls [can.Model.create]
		 * with the instance's properties.  It also [can.trigger triggers]
		 * a "created" event on the instance and the model.
		 * 
		 *     // create a model instance
		 *     var todo = new Todo({name: "dishes"})
		 *     
		 *     // listen when the instance is created
		 *     todo.bind("created", function(ev){
		 * 	     this //-> todo
		 *     })
		 *     
		 *     // save it on the server
		 *     todo.save(function(todo){
		 * 	     console.log("todo", todo, "created")
		 *     });
		 * 
		 * ## Using `save` to update an instance.
		 * 
		 * If save is called on an instance that has 
		 * an [can.Model.id id] property, it calls [can.Model.create]
		 * with the instance's properties.  When the save is complete,
		 * it triggers an "updated" event on the instance and the instance's model.
		 * 
		 * Instances with an
		 * __id__ are typically retrieved with [can.Model.findAll] or
		 * [can.Model.findOne].  
		 * 
		 *  
		 *     // get a created model instance
		 *     Todo.findOne({id: 5},function(todo){
		 *       	     
		 *       // listen when the instance is updated
		 *       todo.bind("updated", function(ev){
		 * 	       this //-> todo
		 *       })
		 * 
		 *       // update the instance's property
		 *       todo.attr("complete", true)
		 *       
		 *       // save it on the server
		 *       todo.save(function(todo){
		 * 	       console.log("todo", todo, "updated")
		 *       });
		 * 
		 *     });
		 * 
		 * 
		 * @param {Function} [success(instance,data)]  Called if a successful save.
		 * 
		 * @param {Function} [error(xhr)] Called with (jqXHR) if the 
		 * save was not successful. It is passed the ajax request's jQXHR object.
		 * 
		 * @return {can.Deferred} a deferred that resolves to the instance
		 * after it has been created or updated.
		 */
		save: function( success, error ) {
			return makeRequest(this, this.isNew() ? 'create' : 'update', success, error);
		},
		/**
		 * Destroys the instance by calling 
		 * [Can.Model.destroy] with the id of the instance.
		 * 
		 *     recipe.destroy(success, error);
		 * 
		 * This triggers "destroyed" events on the instance and the 
		 * Model constructor function which can be listened to with
		 * [can.Model::bind] and [can.Model.bind]. 
		 * 
		 *     Recipe = can.Model({
		 *       destroy : "DELETE /services/recipes/{id}",
		 *       findOne : "/services/recipes/{id}"
		 *     },{})
		 *     
		 *     Recipe.bind("destroyed", function(){
		 *       console.log("a recipe destroyed");	
		 *     });
		 * 
		 *     // get a recipe
		 *     Recipe.findOne({id: 5}, function(recipe){
		 *       recipe.bind("destroyed", function(){
		 *         console.log("this recipe destroyed")	
		 *       })
		 *       recipe.destroy();
		 *     })
		 * 
		 * @param {Function} [success(instance)] called if a successful destroy
		 * @param {Function} [error(xhr)] called if an unsuccessful destroy
		 * @return {can.Deferred} a deferred that resolves with the destroyed instance
		 */
		destroy: function( success, error ) {
			return makeRequest(this, 'destroy', success, error, 'destroyed');
		},
		/**
		 * @function bind
		 * 
		 * `bind(eventName, handler(ev, args...) )` is used to listen
		 * to events on this model instance.  Example:
		 * 
		 *     Task = can.Model()
		 *     var task = new Task({name : "dishes"})
		 *     task.bind("name", function(ev, newVal, oldVal){})
		 * 
		 * Use `bind` the
		 * same as [can.Observe::bind] which should be used as
		 * a reference for listening to property changes.
		 * 
		 * Bind on model can be used to listen to when 
		 * an instance is:
		 * 
		 *  - created
		 *  - updated
		 *  - destroyed
		 * 
		 * like:
		 * 
		 *     Task = can.Model()
		 *     var task = new Task({name : "dishes"})
		 * 
		 *     task.bind("created", function(ev, newTask){
		 * 	     console.log("created", newTask)
		 *     })
		 *     .bind("updated", function(ev, updatedTask){
		 *       console.log("updated", updatedTask)
		 *     })
		 *     .bind("destroyed", function(ev, destroyedTask){
		 * 	     console.log("destroyed", destroyedTask)
		 *     })
		 * 
		 *     // create, update, and destroy
		 *     task.save(function(){
		 *       task.attr('name', "do dishes")
		 *           .save(function(){
		 * 	            task.destroy()
		 *           })
		 *     }); 
		 *     
		 * 
		 * `bind` also extends the inherited 
		 * behavior of [can.Observe::bind] to track the number
		 * of event bindings on this object which is used to store
		 * the model instance.  When there are no bindings, the 
		 * model instance is removed from the store, freeing memory.  
		 * 
		 */
		bind : function(eventName){
			if ( ! ignoreHookup.test( eventName )) { 
				if ( ! this._bindings ) {
					this.constructor.store[getId(this)] = this;
					this._bindings = 0;
				}
				this._bindings++;
			}
			
			return can.Observe.prototype.bind.apply( this, arguments );
		},
		/**
		 * @function unbind
		 * `unbind(eventName, handler)` removes a listener
		 * attached with [can.Model::bind].
		 * 
		 *     var handler = function(ev, createdTask){
		 * 	     
		 *     }
		 *     task.bind("created", handler)
		 *     task.unbind("created", handler)
		 * 
		 * You have to pass the same function to `unbind` that you
		 * passed to `bind`.
		 * 
		 * Unbind will also remove the instance from the store
		 * if there are no other listeners.
		 * 
		 * @param {String} eventName The type of event.  
		 * 
		 * @param {Function} handler(event,args...) A callback function
		 * that was passed to `bind`.
		 * 
		 * @return {model} the model instance.
		 */
		unbind : function(eventName){
			if(!ignoreHookup.test(eventName)) { 
				this._bindings--;
				if(!this._bindings){
					delete this.constructor.store[getId(this)];
				}
			}
			return can.Observe.prototype.unbind.apply(this, arguments);
		},
		// Change `id`.
		___set: function( prop, val ) {
			can.Observe.prototype.___set.call(this,prop, val)
			// If we add an `id`, move it to the store.
			if(prop === this.constructor.id && this._bindings){
				this.constructor.store[getId(this)] = this;
			}
		}
	});
	
	can.each({
		makeFindAll : "models",
		makeFindOne: "model"
	}, function( method, name ) {
		can.Model[name] = function( oldFind ) {
			return function( params, success, error ) {
				var def = pipe( oldFind.call( this, params ), this, method );
				def.then( success, error );
				// return the original promise
				return def;
			};
		};
	});
				
		can.each([
	/**
	 * @function created
	 * @hide
	 * Called by save after a new instance is created.  Publishes 'created'.
	 * @param {Object} attrs
	 */
	"created",
	/**
	 * @function updated
	 * @hide
	 * Called by save after an instance is updated.  Publishes 'updated'.
	 * @param {Object} attrs
	 */
	"updated",
	/**
	 * @function destroyed
	 * @hide
	 * Called after an instance is destroyed.  
	 *   - Publishes "shortName.destroyed".
	 *   - Triggers a "destroyed" event on this model.
	 *   - Removes the model from the global list if its used.
	 * 
	 */
	"destroyed"], function( funcName ) {
		can.Model.prototype[funcName] = function( attrs ) {
			var stub, 
				constructor = this.constructor;

			// Update attributes if attributes have been passed
			stub = attrs && typeof attrs == 'object' && this.attr(attrs.attr ? attrs.attr() : attrs);

			// Call event on the instance
			can.trigger(this,funcName);
			can.trigger(this,"change",funcName)
			//!steal-remove-start
			steal.dev.log("Model.js - "+ constructor.shortName+" "+ funcName);
			//!steal-remove-end

			// Call event on the instance's Class
			can.trigger(constructor,funcName, this);
		};
	});
  
  // Model lists are just like `Observe.List` except that when their items are 
  // destroyed, it automatically gets removed from the list.
  /**
   * @class can.Model.List
   * @inherits can.Observe.List
   * @parent canjs
   *
   * Works exactly like [can.Observe.List] and has all of the same properties,
   * events, and functions as an observable list. The only difference is that 
   * when an item from the list is destroyed, it will automatically get removed
   * from the list.
   *
   * ## Creating a new Model List
   *
   * To create a new model list, just use `new {model_name}.List(ARRAY)` like:
   *
   *     var todo1 = new Todo( { name: "Do the dishes" } ),
   *         todo2 = new Todo( { name: "Wash floors" } )
   *     var todos = new Todo.List( [todo1, todo2] );
   *
   * ### Model Lists in `can.Model`
   * [can.Model.static.findAll can.Model.findAll] or [can.Model.models] will
   * almost always be used to return a `can.Model.List` object, even though it
   * is possible to create new lists like below:
   *
   *     var todos = Todo.models([
   *         new Todo( { name: "Do the dishes" } ),
   *         new Todo( { name: "Wash floors" } )
   *     ])
   *     
   *     todos.constructor // -> can.Model.List
   *
   *     // the most correct way to get a can.Model.List
   *     Todo.findAll({}, function(todos) {
   *         todos.constructor // -> can.Model.List
   *     })
   *
   * ### Extending `can.Model.List`
   *
   * Creating custom `can.Model.Lists` allows you to extend lists with helper
   * functions for a list of a specific type. So, if you wanted to be able to
   * see how many todos were completed and remaining something could be written
   * like:
   *
   *     Todo.List = can.Model.List({
   *         completed: function() {
   *             var completed = 0;
   *             this.each(function(i, todo) {
   *                 completed += todo.attr('complete') ? 1 : 0
   *             })
   *             return completed;
   *         },
   *         remaining: function() {
   *             return this.attr('length') - this.completed();
   *         }
   *     })
   *
   *     Todo.findAll({}, function(todos) {
   *         todos.completed() // -> 0
   *         todos.remaining() // -> 2
   *     });
   *
   * ## Removing models from model list
   *
   * The advantage that `can.Model.List` has over a traditional `can.Observe.List`
   * is that when you destroy a model, if it is in that list, it will automatically
   * be removed from the list. 
   *
   *     // Listen for when something is removed from the todos list.
   *     todos.bind("remove", function( ev, oldVals, indx ) {
   *         console.log(oldVals[indx].attr("name") + " removed")
   *     })
   *
   *     todo1.destory(); // console shows "Do the dishes removed"
   *
   *
   */
	var ML = can.Model.List = can.Observe.List({
		setup : function(){
			can.Observe.List.prototype.setup.apply(this, arguments );
			// Send destroy events.
			var self = this;
			this.bind('change', function(ev, how){
				if(/\w+\.destroyed/.test(how)){
					var index = self.indexOf(ev.target);
					if (index != -1) {
						self.splice(index, 1);
					}
				}
			})
		}
	})

	return can.Model;
})