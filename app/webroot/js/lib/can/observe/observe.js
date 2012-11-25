// 1.69
steal('can/util','can/construct', function(can) {
	// ## observe.js  
	// `can.Observe`  
	// _Provides the observable pattern for JavaScript Objects._  
	//  
	// Returns `true` if something is an object with properties of its own.
	var canMakeObserve = function( obj ) {
			return obj && (can.isArray(obj) || can.isPlainObject( obj ) || ( obj instanceof can.Observe ));
		},
		
		// Removes all listeners.
		unhookup = function(items, namespace){
			return can.each(items, function(item){
				if(item && item.unbind){
					item.unbind("change" + namespace);
				}
			});
		},
		// Listens to changes on `val` and "bubbles" the event up.  
		// `val` - The object to listen for changes on.  
		// `prop` - The property name is at on.  
		// `parent` - The parent object of prop.
		// `ob` - (optional) The Observe object constructor
		// `list` - (optional) The observable list constructor
		hookupBubble = function( val, prop, parent, Ob, List ) {
			Ob = Ob || Observe;
			List = List || Observe.List;

			// If it's an `array` make a list, otherwise a val.
			if (val instanceof Observe){
				// We have an `observe` already...
				// Make sure it is not listening to this already
				unhookup([val], parent._cid);
			} else if ( can.isArray(val) ) {
				val = new List(val);
			} else {
				val = new Ob(val);
			}
			
			// Listen to all changes and `batchTrigger` upwards.
			val.bind("change" + parent._cid, function( /* ev, attr */ ) {
				// `batchTrigger` the type on this...
				var args = can.makeArray(arguments),
					ev = args.shift();
					args[0] = (prop === "*" ? 
						[ parent.indexOf( val ), args[0]] :
						[ prop, args[0]] ).join(".");

				// track objects dispatched on this observe		
				ev.triggeredNS = ev.triggeredNS || {};

				// if it has already been dispatched exit
				if (ev.triggeredNS[parent._cid]) {
					return;
				}

				ev.triggeredNS[parent._cid] = true;
				// send change event with modified attr to parent	
				can.trigger(parent, ev, args);
				// send modified attr event to parent
				//can.trigger(parent, args[0], args);
			});

			return val;
		},
		
		// An `id` to track events for a given observe.
		observeId = 0,
		// A helper used to serialize an `Observe` or `Observe.List`.  
		// `observe` - The observable.  
		// `how` - To serialize with `attr` or `serialize`.  
		// `where` - To put properties, in an `{}` or `[]`.
		serialize = function( observe, how, where ) {
			// Go through each property.
			observe.each(function( val, name ) {
				// If the value is an `object`, and has an `attrs` or `serialize` function.
				where[name] = canMakeObserve(val) && can.isFunction( val[how] ) ?
				// Call `attrs` or `serialize` to get the original data back.
				val[how]() :
				// Otherwise return the value.
				val;
			});
			return where;
		},
		$method = function( name ) {
			return function() {
				return can[name].apply(this, arguments );
			};
		},
		bind = $method('addEvent'),
		unbind = $method('removeEvent'),
		attrParts = function(attr){
			return can.isArray(attr) ? attr : (""+attr).split(".");
		},
		// Which batch of events this is for -- might not want to send multiple
		// messages on the same batch.  This is mostly for event delegation.
		batchNum = 1,
		// how many times has start been called without a stop
		transactions = 0,
		// an array of events within a transaction
		batchEvents = [],
		stopCallbacks = [];
	
	var cid = 0;
	can.cid = function(object, name){
		if(object._cid){
			return object._cid
		} else{
			return object._cid = (name ||"" ) + (++cid)
		}
	}
		
	/**
	 * @add can.Observe
	 */
	var Observe = can.Observe = can.Construct( {
	/**
	 * @static
	 */
		// keep so it can be overwritten
		bind : bind,
		unbind: unbind,
		id: "id",
		canMakeObserve : canMakeObserve,
		// starts collecting events
		// takes a callback for after they are updated
		// how could you hook into after ejs
		/**
		 * `can.Observe.startBatch([batchStopHandler])` starts a 
		 * transaction. 
		 * @param {Function} [batchStopHandler]
		 */
		startBatch: function( batchStopHandler ) {
			transactions++;
			batchStopHandler && stopCallbacks.push(batchStopHandler);
		},
		/**
		 * `can.Observe.stopBatch([force,] [callStart])`
		 */
		stopBatch: function(force, callStart){
			if(force){
				transactions = 0;
			} else {
				transactions--;
			}
			
			if(transactions == 0){
				var items = batchEvents.slice(0),
					callbacks = stopCallbacks.slice(0);
				batchEvents= [];
				stopCallbacks = [];
				batchNum++;
				callStart && this.startBatch();
				can.each(items, function( args ) {
					can.trigger.apply(can, args);
				});
				can.each(callbacks, function( cb ) {
					cb;
				});
			}
		},
		/**
		 * Creates an event on item, but will not send immediately if collecting events.  
		 * @param {can.Observe} item The item the event should happen on.  
		 * @param {String|Object} event - The event name, ex: `change`, or an object with an event type, ex: `{type: 'change'}`.  
		 * @param {Array} - The arguments to call back the function with.
		 */
		triggerBatch: function( item, event, args ) {
			// Don't send events if initalizing.
			if ( ! item._init) {
				if (transactions == 0 ) {
					return can.trigger(item, event, args);
				} else {
					batchEvents.push([
					item,
					{
						type: event,
						batchNum : batchNum
					}, 
					args ] );
				}
			}
		},
		/**
			* Iterates over an observable object to get an array of its keys.
			*
			*     o =  new can.Observe({ foo: 'bar', baz: 'qux' });
			*     can.Observe.keys(o); //-> ['foo', 'baz']
			*
			* @param {can.Observe} observe The observe to iterate over
			* @return {Array} array An array of the keys on the object.
		 */
		keys: function(observe) {
			var keys = [];
			Observe.__reading && Observe.__reading(observe, '__keys');
			for(var keyName in observe._data) {
				keys.push(keyName);
			}
			return keys;
		}
	},
	/**
	 * @prototype
	 */
	{
		setup: function( obj ) {
			// `_data` is where we keep the properties.
			this._data = {};
			// The namespace this `object` uses to listen to events.
			can.cid(this, ".observe");
			// Sets all `attrs`.
			this._init = 1;
			this.attr(obj);
			this.bind('change'+this._cid,can.proxy(this._changes,this));
			delete this._init;
		},
		_changes: function(ev, attr, how,newVal, oldVal){
			Observe.triggerBatch(this, {type:attr, batchNum: ev.batchNum}, [newVal,oldVal]);
		},
		/**
		 * @attribute _cid
		 *
		 * A globally unique ID for this Observe instance.
		 */

		/**
		 * Get or set an attribute or attributes on the observe.
		 * 
		 *     o = new can.Observe({});
		 *     
		 *     // sets a user property
		 *     o.attr( 'user', { name: 'hank' } );
		 *     
		 *     // read the user's name
		 *     o.attr( 'user.name' ) //-> 'hank'
		 * 
		 *     // merge multiple properties
		 *     o.attr({
		 *        grade: 'A'
		 *     });
		 * 
		 *     // get properties
		 *     o.attr()           //-> {user: {name: 'hank'}, grade: "A"}
		 * 
		 *     // set multiple properties and remove absent attrs
		 *     o.attr( { foo: 'bar' }, true );
		 * 
		 *     o.attr()           //-> {foo: 'bar'}
		 * 
		 * ## Setting Properties
		 * 
		 * `attr( PROPERTY, VALUE )` sets the observable's PROPERTY to VALUE.  For example:
		 * 
		 *     o = new can.Observe({});
		 *     o.attr( 'user', 'Justin' );
		 * 
		 * This call to attr fires two events on __o__ immediately after the value is set, the first is a "change" event that can be 
		 * listened to like:
		 * 
		 *     o.bind( 'change', function( ev, attr, how, newVal, oldVal ) {} );
		 * 
		 * where:
		 * 
		 *  - ev - the "change" event
		 *  - attr - the name of the attribute changed: `"user"`
		 *  - how - how the attribute was changed: `"add"` because the property was set for the first time
		 *  - newVal - the new value of the property: `"Justin"`
		 *  - oldVal - the old value of the property: `undefined`
		 * 
		 * "change" events are the generic event that gets fired on all changes to an 
		 * observe's properties. The second event shares the name of the property being changed
		 * and can be bound to like:
		 * 
		 *     o.bind( 'name', function( ev, newVal, oldVal ) {} );
		 * 
		 * where:
		 * 
		 *   - ev - the "name" event
		 *   - newVal - the new value of the name property: `'Justin'`
		 *   - oldVal - the old value of the name property: `undefined`
		 * 
		 * `attr( PROPERTY, VALUE )` allows setting of deep properties like:
		 * 
		 *      o = new can.Observe({ person: { name: { first: 'Just' } } });
		 *      o.attr( 'person.name.first', 'Justin' );
		 * 
		 *  All property names should be seperated with a __"."__.
		 * 
		 * `attr( PROPERTIES )` sets multiple properties at once and removes
		 * properties not in `PROPERTIES`.  For example:
		 * 
		 *     o = new can.Observe({ first: 'Just', middle: 'B' });
		 *     o.attr({
		 *       first: 'Justin',
		 *       last: 'Meyer'
		 *     });
		 * 
		 * This results in an object that looks like:
		 * 
		 *     { first: 'Justin', last: 'Meyer' }
		 * 
		 * Notice that the `middle` property is removed.  This results in
		 * 3 change events (and the corresponding property-named events) that
		 * are triggered after all properties have been set:
		 * 
		 * <table>
		 *   <tr><th>attr</th><th>how</th><th>newVal</th><th>oldVal</th></tr>
		 *   <tr>
		 * 	   <td>"first"</td><td>"set"</td><td>"Justin"</td><td>"Just"</td>
		 *   </tr>
		 *   <tr>
		 * 	   <td>"last"</td><td>"add"</td><td>"Meyer"</td><td>undefined</td>
		 *   </tr>
		 *   <tr>
		 * 	   <td>"middle"</td><td>"remove"</td><td>undefined</td><td>"B"</td>
		 *   </tr>
		 * </table>
		 * 
		 * `attr( PROPERTIES , true )` merges properties into existing 
		 * properties. For example:
		 * 
		 *     o = new can.Observe({ first: 'Just', middle: 'B' });
		 *     o.attr({
		 *       first: 'Justin',
		 *       last: 'Meyer'
		 *     })
		 * 
		 * This results in an object that looks like:
		 * 
		 *     { first: 'Justin', middle: 'B', last: 'Meyer' }
		 * 
		 * and results in 2 change events (and the corresponding 
		 * property-named events):
		 * 
		 * <table>
		 *   <tr><th>attr</th><th>how</th><th>newVal</th><th>oldVal</th></tr>
		 *   <tr>
		 * 	   <td>"first"</td><td>"set"</td><td>"Justin"</td><td>"Just"</td>
		 *   </tr>
		 *   <tr>
		 * 	   <td>"last"</td><td>"add"</td><td>"Meyer"</td><td>undefined</td>
		 *   </tr>
		 * </table>
		 * 
		 * Use [can.Observe::removeAttr removeAttr] to remove an attribute.
		 * 
		 * ## Reading Properties
		 * 
		 * `attr( PROPERTY )` returns a property value.  For example:
		 * 
		 *     o = new can.Observe({ first: 'Justin' });
		 *     o.attr( 'first' ) //-> "Justin"
		 * 
		 * You can also read properties that don't conflict with Observe's inherited
		 * methods direclty like:
		 * 
		 *     o.first //-> "Justin"
		 * 
		 * `attr( PROPERTY )` can read nested properties like:
		 * 
		 *      o = new can.Observe({ person: { name: { first: 'Justin' } } });
		 *      o.attr( 'person.name.first' ) //-> "Justin"
		 * 
		 * If `attr( PROPERTY )` returns an object or an array, it returns
		 * the Observe wrapped object or array. For example:
		 * 
		 *      o = new can.Observe({ person: { name: { first: 'Justin' } } });
		 *      o.attr( 'person' ).attr( 'name.first' ) //-> "Justin"
		 * 
		 * 
		 * `attr()` returns all properties in the observe, for example:
		 * 
		 *     o = new can.Observe({ first: 'Justin' });
		 *     o.attr() //-> { first: "Justin" }
		 * 
		 * If the observe has nested objects, `attr()` returns the 
		 * data as plain JS objects, not as observes.  Example:
		 * 
		 *      o = new can.Observe({ person: { name: { first: 'Justin' } } });
		 *      o.attr() //-> { person: { name: { first: 'Justin' } } }
		 * 
		 * @param {String} attr the attribute to read or write.
		 * 
		 *     o.attr( 'name' ) //-> reads the name
		 *     o.attr( 'name', 'Justin' ) //-> writes the name
		 *     
		 * You can read or write deep property names.  For example:
		 * 
		 *     o.attr( 'person', { name: 'Justin' } );
		 *     o.attr( 'person.name' ) //-> 'Justin'
		 * 
		 * @param {Object} [val] if provided, sets the value.
		 * @return {Object} the observable or the attribute property.
		 * 
		 * If you are reading, the property value is returned:
		 * 
		 *     o.attr( 'name' ) //-> Justin
		 *     
		 * If you are writing, the observe is returned for chaining:
		 * 
		 *     o.attr( 'name', 'Brian' ).attr( 'name' ) //-> Brian
		 */
		attr: function( attr, val ) {
			// This is super obfuscated for space -- basically, we're checking
			// if the type of the attribute is not a `number` or a `string`.
			var type = typeof attr;
			if ( type !== "string" && type !== "number" ) {
				return this._attrs(attr, val)
			} else if ( val === undefined ) {// If we are getting a value.
				// Let people know we are reading.
				Observe.__reading && Observe.__reading(this, attr)
				return this._get(attr)
			} else {
				// Otherwise we are setting.
				this._set(attr, val);
				return this;
			}
		},
		/**
		 * Iterates through each attribute, calling handler 
		 * with each attribute name and value.
		 * 
		 *     new Observe({ foo: 'bar' })
		 *       .each( function( value, name ) {
		 *         equals( name, 'foo' );
		 *         equals( value,'bar' );
		 *       });
		 * 
		 * @param {function} handler( attrName, value ) A function that will get 
		 * called back with the name and value of each attribute on the observe.
		 * 
		 * Returning `false` breaks the looping. The following will never
		 * log 3:
		 * 
		 *     new Observe({ a: 1, b: 2, c: 3 })
		 *       .each( function( value, name ) {
		 *         console.log(value);
		 *         if ( name == 2 ) {
		 *           return false;
		 *         }
		 *       });
		 * 
		 * @return {can.Observe} the original observable.
		 */
		each: function() {
			Observe.__reading && Observe.__reading(this, '__keys');
			return can.each.apply(undefined, [this.__get()].concat(can.makeArray(arguments)))
		},
		/**
		 * Removes a property by name from an observe.
		 * 
		 *     o =  new can.Observe({ foo: 'bar' });
		 *     o.removeAttr('foo'); //-> 'bar'
		 * 
		 * This creates a `'remove'` change event. Learn more about events
		 * in [can.Observe.prototype.bind bind] and [can.Observe.prototype.delegate delegate].
		 * 
		 * @param {String} attr the attribute name to remove.
		 * @return {Object} the value that was removed.
		 */
		removeAttr: function( attr ) {
			// Convert the `attr` into parts (if nested).
			var parts = attrParts(attr),
				// The actual property to remove.
				prop = parts.shift(),
				// The current value.
				current = this._data[prop];

			// If we have more parts, call `removeAttr` on that part.
			if ( parts.length ) {
				return current.removeAttr(parts)
			} else {
				if( prop in this._data ){
					// Otherwise, `delete`.
					delete this._data[prop];
					// Create the event.
					if (!(prop in this.constructor.prototype)) {
						delete this[prop]
					}
					// Let others know the number of keys have changed
					Observe.triggerBatch(this, "__keys", undefined);
					Observe.triggerBatch(this, "change", [prop, "remove", undefined, current]);
					Observe.triggerBatch(this, prop, [undefined, current]);
				}
				return current;
			}
		},
		// Reads a property from the `object`.
		_get: function( attr ) {
			// break up the attr (`"foo.bar"`) into `["foo","bar"]`
			var parts = attrParts(attr),
				// get the value of the first attr name (`"foo"`)
				current = this.__get(parts.shift());
			// if there are other attributes to read
			return parts.length ? 
				// and current has a value
				current ?
					// lookup the remaining attrs on current
					current._get(parts) : 
					// or if there's no current, return undefined
					undefined 	
				: 
				// if there are no more parts, return current
				current;
		},
		// Reads a property directly if an `attr` is provided, otherwise
		// returns the "real" data object itself.
		__get: function( attr ) {
			return attr ? this._data[attr] : this._data;
		},
		// Sets `attr` prop as value on this object where.
		// `attr` - Is a string of properties or an array  of property values.
		// `value` - The raw value to set.
		_set: function( attr, value ) {
			// Convert `attr` to attr parts (if it isn't already).
			var parts = attrParts(attr),
				// The immediate prop we are setting.
				prop = parts.shift(),
				// The current value.
				current = this.__get(prop);

			// If we have an `object` and remaining parts.
			if ( canMakeObserve(current) && parts.length ) {
				// That `object` should set it (this might need to call attr).
				current._set(parts, value)
			} else if (!parts.length ) {
				// We're in "real" set territory.
				if(this.__convert){
					value = this.__convert(prop, value)
				}
				// If there is no current value, let others know that
				// the the number of keys have changed
				if(!current) {
					Observe.triggerBatch(this, "__keys", undefined);
				}
				this.__set(prop, value, current)
			} else {
				throw "can.Observe: Object does not exist"
			}
		},
		__set : function(prop, value, current){
		
			// Otherwise, we are setting it on this `object`.
			// TODO: Check if value is object and transform
			// are we changing the value.
			if ( value !== current ) {
				// Check if we are adding this for the first time --
				// if we are, we need to create an `add` event.
				var changeType = this.__get().hasOwnProperty(prop) ? "set" : "add";

				// Set the value on data.
				this.___set(prop,

				// If we are getting an object.
				canMakeObserve(value) ?

				// Hook it up to send event.
				hookupBubble(value, prop, this) :
				// Value is normal.
				value);

				// `batchTrigger` the change event.
				Observe.triggerBatch(this, "change", [prop, changeType, value, current]);
				//Observe.triggerBatch(this, prop, [value, current]);
				// If we can stop listening to our old value, do it.
				current && unhookup([current], this._cid);
			}

		},
		// Directly sets a property on this `object`.
		___set: function( prop, val ) {
			this._data[prop] = val;
			// Add property directly for easy writing.
			// Check if its on the `prototype` so we don't overwrite methods like `attrs`.
			if (!(prop in this.constructor.prototype)) {
				this[prop] = val
			}
		},
		/**
		 * @function bind
		 * `bind( eventType, handler )` Listens to changes on a can.Observe.
		 * 
		 * When attributes of an observe change, two types of events are produced
		 * 
		 *   - "change" events - a generic event so you can listen to any property changes
		 *   - ATTR_NAME events - bind to specific attribute changes
		 * 
		 * Example:
		 * 
		 *     o = new can.Observe({ name: 'Payal' });
		 *     o.bind( 'change', function( ev, attr, how, newVal, oldVal ) {
		 *       
		 *     }).bind( 'name', function( ev, newVal, oldVal ) {
		 *     	
		 *     });
		 *     
		 *     o.attr( 'name', 'Justin' ); 
		 * 
		 * ## Change Events
		 * 
		 * A `'change'` event is triggered on the observe.  These events come
		 * in three flavors:
		 * 
		 *   - `add` - a attribute is added
		 *   - `set` - an existing attribute's value is changed
		 *   - `remove` - an attribute is removed
		 * 
		 * The change event is fired with:
		 * 
		 *  - the attribute changed
		 *  - how it was changed
		 *  - the newValue of the attribute
		 *  - the oldValue of the attribute
		 * 
		 * Example:
		 * 
		 *     o = new can.Observe({ name: 'Payal' });
		 *     o.bind( 'change', function( ev, attr, how, newVal, oldVal ) {
		 *       // ev    -> {type: 'change'}
		 *       // attr  -> "name"
		 *       // how   -> "add"
		 *       // newVal-> "Justin"
		 *       // oldVal-> "Payal"
		 *     });
		 *     
		 *     o.attr( 'name', 'Justin' );
		 * 
		 * ## ATTR_NAME events
		 * 
		 * When a attribute value is changed, an event with the name of the attribute
		 * is triggered on the observable with the new value and old value as 
		 * parameters. For example:
		 * 
		 *     o = new can.Observe({ name: 'Payal' });
		 *     o.bind( 'name', function( ev, newVal, oldVal ) {
		 *       // ev    -> {type : "name"}
		 *       // newVal-> "Justin"
		 *       // oldVal-> "Payal"
		 *     });
		 *     
		 *     o.attr( 'name', 'Justin' );
		 * 
		 * 
		 * @param {String} eventType the event name.  Currently,
		 * only `'change'`  and `ATTR_NAME` events are supported. 
		 * 
		 * @param {Function} handler(event, attr, how, newVal, oldVal) A 
		 * callback function where
		 * 
		 *   - event - the event
		 *   - attr - the name of the attribute changed
		 *   - how - how the attribute was changed (add, set, remove)
		 *   - newVal - the new value of the attribute
		 *   - oldVal - the old value of the attribute
		 * 
		 * @return {can.Observe} the observe for chaining.
		 */
		bind: bind,
		/**
		 * @function unbind
		 * Unbinds an event listener.  This works similar to jQuery's unbind.  This means you can 
		 * use namespaces or unbind all event handlers for a given event:
		 * 
		 *     // unbind a specific event handler
		 *     o.unbind( 'change', handler );
		 *     
		 *     // unbind all change event handlers bound with the
		 *     // foo namespace
		 *     o.unbind( 'change.foo' );
		 *     
		 *     // unbind all change event handlers
		 *     o.unbind( 'change' );
		 * 
		 * @param {String} eventType - the type of event with
		 * any optional namespaces. 
		 * 
		 * @param {Function} [handler] - The original handler function passed
		 * to [can.Observe.prototype.bind bind].
		 * 
		 * @return {can.Observe} the original observe for chaining.
		 */
		unbind: unbind,
		/**
		 * @hide
		 * Get the serialized Object form of the observe.  Serialized
		 * data is typically used to send back to a server.
		 * 
		 *     o.serialize() //-> { name: 'Justin' }
		 *     
		 * Serialize currently returns the same data 
		 * as [can.Observe.prototype.attrs].  However, in future
		 * versions, serialize will be able to return serialized
		 * data similar to [can.Model].  The following will work:
		 * 
		 *     new Observe({time: new Date()})
		 *       .serialize() //-> { time: 1319666613663 }
		 * 
		 * @return {Object} a JavaScript Object that can be 
		 * serialized with `JSON.stringify` or other methods. 
		 * 
		 */
		serialize: function() {
			return serialize(this, 'serialize', {});
		},
		/**
		 * @hide
		 * Set multiple properties on the observable
		 * @param {Object} props
		 * @param {Boolean} remove true if you should remove properties that are not in props
		 */
		_attrs: function( props, remove ) {

			if ( props === undefined ) {
				return serialize(this, 'attr', {})
			}

			props = can.extend(true, {}, props);
			var prop,
				self = this,
				newVal;
			Observe.startBatch();
			this.each(function(curVal, prop, toRemove){
				newVal = props[prop];

				// If we are merging...
				if ( newVal === undefined ) {
					remove && self.removeAttr(prop);
					return;
				}
				if ( self.__convert ) {
					newVal = self.__convert(prop, newVal);
				}

				if ( curVal !== newVal ) {
					if ( curVal instanceof can.Observe && newVal instanceof can.Observe ) {
						unhookup([curVal], self._cid);
					}

					if ( newVal instanceof can.Observe ) {
						self._set(prop, newVal)
					}
					else if ( canMakeObserve(curVal) && canMakeObserve(newVal) ) {
						curVal.attr(newVal, toRemove)
					} else if ( curVal != newVal ) {
						self._set(prop, newVal)
					}
				}
				delete props[prop];
			})
			// Add remaining props.
			for ( var prop in props ) {
				newVal = props[prop];
				this._set(prop, newVal)
			}
			Observe.stopBatch()
			return this;
		}
	});
	// Helpers for `observable` lists.
	/**
	 * @class can.Observe.List
	 * @inherits can.Observe
	 * @parent canjs
	 * 
	 * `new can.Observe.List([items])` provides the observable pattern for JavaScript arrays.  It lets you:
	 * 
	 *   - change the structure of an array
	 *   - listen to changes in the array
	 * 
	 * ## Creating an observe list
	 * 
	 * To create an observable list, use `new can.Observe.List( ARRAY )` like:
	 * 
	 *     var hobbies = new can.Observe.List(
	 * 		 			['programming', 'basketball', 'nose picking'])
	 * 
	 * can.Observe.List inherits from [can.Observe], including it's 
	 * [can.Observe.prototype.bind bind], [can.Observe.prototype.each each], and [can.Observe.prototype.unbind unbind] 
	 * methods.
	 * 
	 * can.Observe.List is inherited by [can.Model.List].
	 * 
	 * ## Getting and Setting Properties
	 * 
	 * Similar to an array, use the index operator to access items of a list:
	 * 
	 * 
	 *     list = new can.Observe.List(["a","b"])
	 *     list[1] //-> "b"
	 * 
	 * Or, use the `attr( PROPERTY )` method like:
	 * 
	 *     list = new can.Observe.List(["a","b"])
	 *     list.attr(1)  //-> "b"
	 *
	 * __WARNING:__ while using the index operator with [] is possible, 
	 * it should be noted that changing properties of objects that way
	 * will not call bound events to the observed list that would let
	 * it know that an object in the list has changed. In almost every
	 * case you will use [can.Model.static.findAll findAll].
	 * 
	 * Using the 'attr' method lets Observe know you accessed the 
	 * property. This is used by [can.EJS] for live-binding.
	 * 
	 * Get back a js Array with `attr()`:
	 * 
	 *     list = new can.Observe.List(["a","b"])
	 *     list.attr()  //-> ["a","b"]
	 * 
	 * Change the structure of the array with:
	 * 
	 *    - [can.Observe.List::attr attr]
	 *    - [can.Observe.List::pop pop]
	 *    - [can.Observe.List::push push]
	 *    - [can.Observe.List::shift shift]
	 *    - [can.Observe.List::unshift unshift]
	 *    - [can.Observe.List::splice splice]
	 * 
	 * ## Events
	 * 
	 * When an item is added, removed, or updated in a list, it triggers
	 * events that can be [can.Observe::bind bind]ed to for changes.
	 * 
	 * There are 5 types of events: add, remove, set, length, and change.
	 * 
	 * ### add events
	 * 
	 * Add events are fired when items are added to the list. Listen 
	 * to them like:
	 * 
	 *     list.bind("add", handler(ev, newVals, index) )
	 * 
	 * where:
	 * 
	 *  - `newVals` - the values added to the list
	 *  - `index` - where the items were added
	 * 
	 * ### remove events
	 * 
	 * Removes events are fired when items are removed from the list. Listen 
	 * to them like:
	 * 
	 *     list.bind("remove", handler(ev, oldVals, index) )
	 * 
	 * where:
	 * 
	 *   - `oldVals` - the values removed from the list
	 *   - `index` - where the items were removed
	 * 
	 * ### set events
	 * 
	 * Set events happen when an item in the list is updated. Listen to 
	 * these events with:
	 * 
	 *     list.bind("set", handler(ev, newVal, index) )
	 * 
	 * where:
	 * 
	 *   - `newVal` - the new value at index
	 *   - `index` - where the items were removed
	 * 
	 * ### length events
	 * 
	 * Anytime the length is changed a length attribute event is
	 * fired.
	 * 
	 *     list.bind("length", handler(ev, length) )
	 * 
	 * where:
	 * 
	 * - `length` - the new length of the array.
	 * 
	 * ### change events
	 * 
	 * Change events are fired when any type of change 
	 * happens on the array.  They get called with:
	 * 
	 *     .bind("change", handler(ev, attr, how, newVal, oldVal) )
	 * 
	 * Where:
	 * 
	 *   - `attr` - the index of the item changed
	 *   - `how` - how the item was changed (add, remove, set)
	 *   - `newVal` - For set, a single item. For add events, an array 
	 *     of items. For remove event, undefined.
	 *   - `oldVal` - the old values at `attr`.
	 * 
	 * @constructor Creates a new observable list from an array
	 * 
	 * @param {Array} [items...] the array of items to create the list with
	 */
	var splice = [].splice,
		list = Observe(
	/**
	 * @prototype
	 */
	{
		setup: function( instances, options ) {
			this.length = 0;
			can.cid(this, ".observe")
			this._init = 1;
			this.push.apply(this, can.makeArray(instances || []));
			this.bind('change'+this._cid,can.proxy(this._changes,this));
			can.extend(this, options);
			delete this._init;
		},
		_changes : function(ev, attr, how, newVal, oldVal){
			// `batchTrigger` direct add and remove events...
			if ( !~ attr.indexOf('.')){
				
				if( how === 'add' ) {
					Observe.triggerBatch(this, how, [newVal,+attr]);
					Observe.triggerBatch(this,'length',[this.length]);
				} else if( how === 'remove' ) {
					Observe.triggerBatch(this, how, [oldVal, +attr]);
					Observe.triggerBatch(this,'length',[this.length]);
				} else {
					Observe.triggerBatch(this,how,[newVal, +attr])
				}
				
			}
			Observe.prototype._changes.apply(this,arguments)
		},
		__get : function(attr){
			return attr ? this[attr] : this;
		},
		___set : function(attr, val){
			this[attr] = val;
			if(+attr >= this.length){
				this.length = (+attr+1)
			}
		},
		// Returns the serialized form of this list.
		/**
		 * @hide
		 * Returns the serialized form of this list.
		 */
		serialize: function() {
			return serialize(this, 'serialize', []);
		},
		/**
		 * Iterates through each item of the list, calling handler 
		 * with each index and value.
		 * 
		 *     new Observe.List(['a'])
		 *       .each(function( value , index ){
		 *         equals(index, 1)
		 *         equals(value,'a')
		 *       })
		 * 
		 * @param {function} handler(value,index) A function that will get 
		 * called back with the index and value of each item on the list.
		 * 
		 * Returning `false` breaks the looping.  The following will never
		 * log 'c':
		 * 
		 *     new Observe(['a','b','c'])
		 *       .each(function(value, index){
		 *         console.log(value)
		 *         if(index == 1){
		 *           return false;
		 *         }
		 *       })
		 * 
		 * @return {can.Observe.List} the original observable.
		 */
		//  
		/**
		 * `splice(index, [ howMany, elements... ] )` remove or add items 
		 * from a specific point in the list.
		 * 
		 * ### Example
		 * 
		 * The following creates a list of numbers and replaces 2 and 3 with
		 * "a", and "b".
		 * 
		 *     var list = new can.Observe.List([0,1,2,3]);
		 *     
		 *     list.splice(1,2, "a", "b"); // results in [0,"a","b",3]
		 *     
		 * This creates 2 change events.  The first event is the removal of 
		 * numbers one and two where it's callback is 
		 * `bind('change', function( ev, attr, how, newVals, oldVals, where ) )`
		 * and it's values are:
		 * 
		 *   - attr - "1" - indicates where the remove event took place
		 *   - how - "remove"
		 *   - newVals - undefined
		 *   - oldVals - [1,2] -the array of removed values
		 *   - where - 1 - the location of where these items were removed
		 * 
		 * The second change event is the addition of the "a", and "b" values where 
		 * the callback values will be:
		 * 
		 *   - attr - "1" - indicates where the add event took place
		 *   - how - "added"
		 *   - newVals - ["a","b"]
		 *   - oldVals - [1, 2] - the array of removed values
		 *   - where - 1 - the location of where these items were added
		 * 
		 * @param {Number} index where to start removing or adding items
		 * @param {Object} [howMany=0] the number of items to remove
		 * @param {Object} [elements...] items to add to the array
		 */
		splice: function( index, howMany ) {
			var args = can.makeArray(arguments),
				i;

			for ( i = 2; i < args.length; i++ ) {
				var val = args[i];
				if ( canMakeObserve(val) ) {
					args[i] = hookupBubble(val, "*", this)
				}
			}
			if ( howMany === undefined ) {
				howMany = args[1] = this.length - index;
			}
			var removed = splice.apply(this, args);
			if ( howMany > 0 ) {
				Observe.triggerBatch(this, "change", [""+index, "remove", undefined, removed]);
				unhookup(removed, this._cid);
			}
			if ( args.length > 2 ) {
				Observe.triggerBatch(this, "change", [""+index, "add", args.slice(2), removed]);
			}
			return removed;
		},
		/**
		 * @function attr
		 * Gets or sets an item or items in the observe list.  Examples:
		 * 
		 *     list = new can.Observe.List(["a","b","c"]);
		 *      
		 *     // sets an array item
		 *     list.attr(3,'d')
		 *     
		 *     // read an array's item
		 *     list.attr(3) //-> 'd'
		 * 
		 *     // merge array's properties
		 *     list.attr( ["b","BOO"] )
		 * 
		 *     // get properties
		 *     o.attr()           //-> ["b","BOO","c","d"]
		 *     
		 *     // set array
		 *     o.attr(["item"])
		 *     o.attr() //-> ["item"]
		 * 
		 * ## Setting Properties
		 * 
		 * `attr( array , true )` updates the list to look like array.  For example:
		 * 
		 *     list = new can.Observe.List(["a","b","c"])
		 *     list.attr(["foo"], true)
		 *     
		 *     list.attr() //-> ["foo"]
		 * 
		 * 
		 * When the array is changed, it produces events that detail the changes
		 * in the list. They are listed in the
		 * order they are produced for the above example:
		 * 
		 *   1. `.bind( "change", handler(ev, attr, how, newVal, oldVal) )` where:
		 *       
		 *      - ev = {type: "change"}
		 *      - attr = "0"
		 *      - how = "set"
		 *      - newVal = "foo"
		 *      - oldVal = "a"
		 * 
		 *   2. `.bind( "set", handler(ev, newVal, index) )` where:
		 *       
		 *      - ev = {type: "set"}
		 *      - newVal = "foo"
		 *      - index = 0
		 * 
		 *   3. `.bind( "change", handler(ev, attr, how, newVal, oldVal) )` where:
		 *       
		 *      - ev = {type: "change"}
		 *      - attr = "1"
		 *      - how = "remove"
		 *      - newVal = undefined
		 *      - oldVal = ["b","c"]
		 * 
		 *   4. `.bind( "remove", handler(ev, newVal, index) )` where:
		 *       
		 *      - ev = {type: "remove"}
		 *      - newVal = undefined
		 *      - index = 1
		 * 
		 *   5. `.bind( "length", handler(ev, length) )` where:
		 *       
		 *      - ev = {type: "length"}
		 *      - length = 1
		 * 
		 * In general, it is possible to listen to events and reproduce the
		 * changes in a facsimile of the list.  This is useful for implementing 
		 * high-performance widgets that need to reflect the contents of the list without
		 * redrawing the entire list.  Here's an example of how that would look:
		 * 
		 *     list.bind("set", function(ev, newVal, index){
		 * 	     // update the item at index with newVal
		 *     }).bind("remove", function(ev, oldVals, index){
		 * 	     // remove oldVals.length items at index
		 *     }).bind("add", function(ev, newVals, index){
		 *       // insert newVals at index
		 *     })
		 * 
		 * `attr( array )` merges items into the beginning of the array.  For example:
		 * 
		 *     list = new can.Observe.List(["a","b"])
		 *     list.attr(["foo"])
		 *     
		 *     list.attr() //-> ["foo","b"]
		 * 
		 * `attr( INDEX, VALUE )` sets or updates an item at `INDEX`.  Example:
		 * 
		 *     list.attr(0, "ITEM")
		 * 
		 * ## Reading Properties
		 * 
		 * `attr()` returns the lists content as an array.  For example:
		 * 
		 *      list = new can.Observe.List(["a", {foo: "bar"}])
		 *      list.attr()  //-> ["a", {foo: "bar"}]
		 * 
		 * `attr( INDEX )` reads a property at `INDEX` like:
		 * 
		 *      list = new can.Observe.List(["a", {foo: "bar"}])
		 *      list.attr(0)  //-> "a",
		 * 
		 * @param {Array|Number} items
		 * @param {Boolean|Object} {optional:remove} 
		 * @return {list|Array} returns the props on a read or the observe
		 * list on a write.
		 */
		_attrs: function( items, remove ) {
			if ( items === undefined ) {
				return serialize(this, 'attr', []);
			}

			// Create a copy.
			items = can.makeArray( items );

      		Observe.startBatch();
			this._updateAttrs(items, remove);
			Observe.stopBatch()
		},

	    _updateAttrs : function( items, remove ){
	      var len = Math.min(items.length, this.length);
	
	      for ( var prop = 0; prop < len; prop++ ) {
	        var curVal = this[prop],
	          newVal = items[prop];
	
	        if ( canMakeObserve(curVal) && canMakeObserve(newVal) ) {
	          curVal.attr(newVal, remove)
	        } else if ( curVal != newVal ) {
	          this._set(prop, newVal)
	        } else {
	
	        }
	      }
	      if ( items.length > this.length ) {
	        // Add in the remaining props.
	        this.push.apply( this, items.slice( this.length ) );
	      } else if ( items.length < this.length && remove ) {
	        this.splice(items.length)
	      }
	    }
	}),


		// Converts to an `array` of arguments.
		getArgs = function( args ) {
			return args[0] && can.isArray(args[0]) ?
				args[0] :
				can.makeArray(args);
		};
	// Create `push`, `pop`, `shift`, and `unshift`
	can.each({
		/**
		 * @function push
		 * Add items to the end of the list.
		 * 
		 *     var list = new can.Observe.List([]);
		 *     
		 *     list.attr() // -> []
		 *     
		 *     list.bind('change', function( 
		 *         ev,        // the change event
		 *         attr,      // the attr that was changed,
		 *     			   // for multiple items, "*" is used 
		 *         how,       // "add"
		 *         newVals,   // an array of new values pushed
		 *         oldVals,   // undefined
		 *         where      // the location where these items were added
		 *         ) {
		 *     
		 *     })
		 *     
		 *     list.push('0','1','2'); 
		 *     list.attr() // -> ['0', '1', '2']
		 * 
		 * @return {Number} the number of items in the array
		 */
		push: "length",
		/**
		 * @function unshift
		 * Add items to the start of the list.  This is very similar to
		 * [can.Observe.List::push can.Observe.prototype.List].  Example:
		 * 
		 *     var list = new can.Observe.List(["a","b"]);
		 *     list.unshift(1,2,3) //-> 5
		 *     .attr() //-> [1,2,3,"a","b"]
		 * 
		 * @param {Object} [items...] items to add to the start of the list.
		 * @return {Number} the length of the array.
		 */
		unshift: 0
	},
	// Adds a method
	// `name` - The method name.
	// `where` - Where items in the `array` should be added.
	function( where, name ) {
		list.prototype[name] = function() {
			// Get the items being added.
			var args = getArgs(arguments),
				// Where we are going to add items.
				len = where ? this.length : 0;

			// Go through and convert anything to an `observe` that needs to be converted.
			for ( var i = 0; i < args.length; i++ ) {
				var val = args[i];
				if ( canMakeObserve(val) ) {
					args[i] = hookupBubble(val, "*", this, this.constructor.Observe, this.constructor);
				}
			}
			
			// Call the original method.
			var res = [][name].apply(this, args);
			
			if ( !this.comparator || !args.length ) {
				Observe.triggerBatch(this, "change", [""+len, "add", args, undefined])
			}
						
			return res;
		}
	});

	can.each({
		/**
		 * @function pop
		 * 
		 * Removes an item from the end of the list. Example:
		 * 
		 *     var list = new can.Observe.List([0,1,2]);
		 *     list.pop() //-> 2;
		 *     list.attr() //-> [0,1]
		 * 
		 * This produces a change event like
		 * 
		 *     list.bind('change', function( 
		 *         ev,        // the change event
		 *         attr,      // the attr that was changed, 
		 *     			   // for multiple items, "*" is used 
		 *         how,       // "remove"
		 *         newVals,   // undefined
		 *         oldVals,   // 2
		 *         where      // the location where these items were added
		 *         ) {
		 *     
		 *     })
		 * 
		 * @return {Object} the element at the end of the list or undefined if the
		 * list is empty.
		 */
		pop: "length",
		/**
		 * @function shift
		 * Removes an item from the start of the list.  This is very similar to
		 * [can.Observe.List::pop]. Example:
		 * 
		 *     var list = new can.Observe.List([0,1,2]);
		 *     list.shift() //-> 0;
		 *     list.attr() //-> [1,2]
		 * 
		 * @return {Object} the element at the start of the list
		 */
		shift: 0
	},
	// Creates a `remove` type method
	function( where, name ) {
		list.prototype[name] = function() {
			
			var args = getArgs(arguments),
				len = where && this.length ? this.length - 1 : 0;


			var res = [][name].apply(this, args)

			// Create a change where the args are
			// `*` - Change on potentially multiple properties.
			// `remove` - Items removed.
			// `undefined` - The new values (there are none).
			// `res` - The old, removed values (should these be unbound).
			// `len` - Where these items were removed.
			Observe.triggerBatch(this, "change", [""+len, "remove", undefined, [res]])

			if ( res && res.unbind ) {
				res.unbind("change" + this._cid)
			}
			return res;
		}
	});
	
	can.extend(list.prototype, {
		/**
		 * @function indexOf
		 * Returns the position of the item in the array.  Returns -1 if the
		 * item is not in the array.  Examples:
		 *
		 *     list = new can.Observe.List(["a","b","c"]);
		 *     list.indexOf("b") //-> 1
		 *     list.indexOf("f") //-> -1
		 *
		 * @param {Object} item the item to look for
		 * @return {Number} the index of the object in the array or -1.
		 */
		indexOf: function(item) {
			this.attr('length')
			return can.inArray(item, this)
		},

		/**
		 * @function join
		 *
		 * Joins the string representation of all elements into a string.
		 *
		 *      list = new can.Observe.List(["a","b","c"]);
		 *      list.join(',') // -> "a, b, c"
		 *
		 * [MDN reference](https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/join)
		 *
		 * @param {String} separator The element separator
		 * @return {String} The joined string
		 */
		join : [].join,

		/**
		 * @function slice
		 *
		 * Creates a flat copy of a section of the observable list and returns
		 * a new observable list.
		 *
		 * [MDN reference](https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/slice)
		 *
		 * @param {Integer} start The beginning index of the section to extract.
		 * @param {Integer} [end] The end index of the section to extract.
		 * @return {can.Observe.List} The sliced list
		 */
		slice : function() {
			var temp = Array.prototype.slice.apply(this, arguments);
			return new this.constructor( temp );
		},

		/**
		 * @function concat
		 *
		 * Returns a new can.Observe.List comprised of this list joined with other
		 * array(s), value(s) and can.Observe.Lists.
		 *
		 * [MDN reference](https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/concat)
		 *
		 * @param {Array|can.Observe.List} args... One or more arrays or observable lists to concatenate
		 * @return {can.Observe.List} The concatenated list
		 */
		concat : function() {
			var args = [];
			can.each( can.makeArray( arguments ), function( arg, i ) {
				args[i] = arg instanceof can.Observe.List ? arg.serialize() : arg ;
			});
			return new this.constructor(Array.prototype.concat.apply(this.serialize(), args));
		},

		/**
		 * @function forEach
		 *
		 * Calls a function for each element in the list.
		 *
		 * > Note that [each can.Observe.each] will iterate over the actual properties.
		 *
		 * [MDN reference](https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/forEach)
		 *
		 * @param {Function} callback The callback to execute.
		 * It gets passed the element and the index in the list.
		 * @param {Object} [thisarg] Object to use as `this` when executing `callback`
		 */
		forEach : function( cb, thisarg ) {
			can.each(this, cb, thisarg || this );
		}
	});

	Observe.List = list;
	Observe.setup = function(){
		can.Construct.setup.apply(this, arguments);
		// I would prefer not to do it this way. It should
		// be using the attributes plugin to do this type of conversion.
		this.List = Observe.List({ Observe : this }, {});
	}
	return Observe;
});
