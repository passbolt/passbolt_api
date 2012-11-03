steal({
	id : "http://download.dojotoolkit.org/release-1.8.1/dojo.js.uncompressed.js",
	_skip: true
}, '../event.js').then(
	'./trigger',
	'can/util/array/each.js',
	'can/util/object/isplain',
	function(){

	// dojo.js
	// ---------
	// _dojo node list._
	//  
	// These are pre-loaded by `steal` -> no callback.
	require(["dojo", "dojo/query", "plugd/trigger", "dojo/NodeList-dom"]);
	
	// Map string helpers.
	can.trim = function(s){
		return s && dojo.trim(s);
	}
	
	// Map array helpers.
	can.makeArray = function(arr){
		array = [];
		dojo.forEach(arr, function(item){ array.push(item)});
		return array;
	};
	can.isArray = dojo.isArray;
	can.inArray = function(item,arr){
		return dojo.indexOf(arr, item);
	};
	can.map = function(arr, fn){
		return dojo.map(can.makeArray(arr||[]), fn);
	};
	// Map object helpers.
	can.extend = function(first){
		if(first === true){
			var args = can.makeArray(arguments);
			args.shift();
			return dojo.mixin.apply(dojo, args)
		}
		return dojo.mixin.apply(dojo, arguments)
	}
	can.isEmptyObject = function(object){
		var prop;
		for(prop in object){
			break;
		}
		return prop === undefined;;
	}

	// Use a version of param similar to jQuery's param that
	// handles nested data instead of dojo.objectToQuery which doesn't
	can.param = function(object){
		var pairs = [],
			add = function( key, value ){
				pairs.push(encodeURIComponent(key) + "=" + encodeURIComponent(value))
			};

		for(var name in object){
			can.buildParam(name, object[name], add);
		}
		return pairs.join("&").replace(/%20/g, "+");
	}
	can.buildParam = function(prefix, obj, add){
        if(can.isArray(obj)){
            for(var i = 0, l = obj.length; i < l; ++i){
                add(prefix + "[]", obj[i])
            }
        } else if( dojo.isObject(obj) ){
        	for (var name in obj){
        		can.buildParam(prefix + "[" + name + "]", obj[name], add);
        	}
        } else {
        	add(prefix, obj);
        }
	}
	
	// Map function helpers.
	can.proxy = function(func, context){
		return dojo.hitch(context, func)
	}
	can.isFunction = function(f){
		return dojo.isFunction(f);
	}
	/**
	 * EVENTS
	 * 
	 * Dojo does not use the callback handler when unbinding.  Instead
	 * when binding (dojo.connect or dojo.on) an object with a remove
	 * method is returned.
	 * 
	 * Because of this, we have to map each callback to the "remove"
	 * object to it can be passed to dojo.disconnect.
	 */
	
	// The id of the `function` to be bound, used as an expando on the `function`
	// so we can lookup it's `remove` object.
	var id = 0,
		// Takes a node list, goes through each node
		// and adds events data that has a map of events to 
		// callbackId to `remove` object.  It looks like
		// `{click: {5: {remove: fn}}}`. 
		addBinding = function( nodelist, ev, cb ) {
			nodelist.forEach(function(node){
				// Converting a raw select node to a node list
				// returns a node list of its options due to a
				// bug in Dojo 1.7.1, this is sovled by wrapping
				// it in an array.
				node = new dojo.NodeList(node.nodeName === "SELECT" ? [node] : node)
				var events = can.data(node,"events");
				if(!events){
					can.data(node,"events", events = {})
				}
				if(!events[ev]){
					events[ev] = {};
				}
				if(cb.__bindingsIds === undefined) {
					cb.__bindingsIds=id++;
				} 
				events[ev][cb.__bindingsIds] = node.on(ev, cb)[0]
			});
		},
		// Removes a binding on a `nodelist` by finding
		// the remove object within the object's data.
		removeBinding = function(nodelist,ev,cb){
			nodelist.forEach(function(node){
				var node = new dojo.NodeList(node),
					events = can.data(node,"events"),
					handlers = events[ev],
					handler = handlers[cb.__bindingsIds];
				
				dojo.disconnect(handler);
				delete handlers[cb.__bindingsIds];
				
				if(can.isEmptyObject(handlers)){
					delete events[ev]
				}
			});
		}
	
	can.bind = function( ev, cb){
		// If we can bind to it...
		if(this.bind && this.bind !== can.bind){
			this.bind(ev, cb)
			
		// Otherwise it's an element or `nodeList`.
		} else if(this.on || this.nodeType){
			// Converting a raw select node to a node list
			// returns a node list of its options due to a
			// bug in Dojo 1.7.1, this is sovled by wrapping
			// it in an array.
			addBinding( new dojo.NodeList(this.nodeName === "SELECT" ? [this] : this), ev, cb)
		} else if(this.addEvent) {
			this.addEvent(ev, cb)
		} else {
			// Make it bind-able...
			can.addEvent.call(this, ev, cb)
		}
		return this;
	}
	can.unbind = function(ev, cb){
		// If we can bind to it...
		if(this.unbind && this.unbind !== can.unbind){
			this.unbind(ev, cb)
		} 
		
		else if(this.on || this.nodeType) {
			removeBinding(new dojo.NodeList(this), ev, cb);
		} else {
			// Make it bind-able...
			can.removeEvent.call(this, ev, cb)
		}
		return this;
	}
	
	can.trigger = function(item, event, args, bubble){
		if(item.trigger){
			if(bubble === false){
				if(!item[0] || item[0].nodeType === 3){
					return;
				}
				// Force stop propagation by
				// listening to `on` and then immediately disconnecting.
				var connect = item.on(event, function(ev){
					
					ev.stopPropagation && ev.stopPropagation();
					ev.cancelBubble = true;
					ev._stopper && ev._stopper();
					
					dojo.disconnect(connect);
				})
				item.trigger(event,args)
			} else {
				item.trigger(event,args)
			}
			
		} else {
			if(typeof event === 'string'){
				event = {type: event}
			}
			event.data = args
			event.target = event.target || item;
			can.dispatch.call(item, event)
		}
	}
	
	can.delegate = function(selector, ev , cb){
		if(this.on || this.nodeType){
			addBinding( new dojo.NodeList(this), selector+":"+ev, cb)
		} else if(this.delegate) {
			this.delegate(selector, ev , cb)
		} 
		return this;
	}
	can.undelegate = function(selector, ev , cb){
		if(this.on || this.nodeType){
			removeBinding(new dojo.NodeList(this), selector+":"+ev, cb);
		} else if(this.undelegate) {
			this.undelegate(selector, ev , cb)
		}

		return this;
	}


	/**
	 * Ajax
	 */
	var optionsMap = {
		type:"method",
		success : undefined,
		error: undefined
	}
	var updateDeferred = function(xhr, d){
		for(var prop in xhr){
			if(typeof d[prop] == 'function'){
				d[prop] = function(){
					xhr[prop].apply(xhr, arguments)
				}
			} else {
				d[prop] = prop[xhr]
			}
		}
	}

	
	can.ajax = function(options){
		var type = can.capitalize( (options.type || "get").toLowerCase() ),
			method = dojo["xhr"+type];
		var success = options.success,
			error = options.error,
			d = new can.Deferred();
			
		var def = method({
			url : options.url,
			handleAs : options.dataType,
			sync : !options.async,
			headers : options.headers,
			content: options.data
		})
		def.then(function(data, ioargs){
			updateDeferred(xhr, d);
			d.resolve(data,"success",xhr);
			success && success(data,"success",xhr);
		},function(data,ioargs){
			updateDeferred(xhr, d);
			d.reject(xhr,"error");
			error(xhr,"error");
		})
		
		var xhr = def.ioArgs.xhr;
		

		updateDeferred(xhr, d);
		return d;
			
	}
	// Element - get the wrapped helper.
	can.$ = function(selector){
		if(selector === window){
			return window;
		}
		if(typeof selector === "string"){
			return dojo.query(selector)
		} else {
			return new dojo.NodeList(selector);
		}

		
	}
	can.buildFragment = function(frag, node){
		var owner = node && node.ownerDocument,
			frag = dojo.toDom(frag, owner );
		if(frag.nodeType !== 11){
			var tmp = document.createDocumentFragment();
			tmp.appendChild(frag)
			frag = tmp;
		}
		return frag
	}
	
	can.append = function(wrapped, html){
		return wrapped.forEach(function(node){
			dojo.place( html, node)
		});
	}
	
	/**
	 * can.data
	 * 
	 * can.data is used to store arbitrary data on an element.
	 * Dojo does not support this, so we implement it itself.
	 * 
	 * The important part is to call cleanData on any elements 
	 * that are removed from the DOM.  For this to happen, we
	 * overwrite 
	 * 
	 *   -dojo.empty
	 *   -dojo.destroy
	 *   -dojo.place when "replace" is used TODO!!!!
	 * 
	 * For can.Control, we also need to trigger a non bubbling event
	 * when an element is removed.  We do this also in cleanData.
	 */
	var data = {},
	    uuid = can.uuid = +new Date(),
	    exp  = can.expando = 'can' + uuid;
	
	function getData(node, name) {
	    var id = node[exp], store = id && data[id];
	    return name === undefined ? store || setData(node) :
	      (store && store[name]);
	}
	
	function setData(node, name, value) {
	    var id = node[exp] || (node[exp] = ++uuid),
	      store = data[id] || (data[id] = {});
	    if (name !== undefined) store[name] = value;
	    return store;
	};
	
	var cleanData = function(elems){
	  	can.trigger(new dojo.NodeList(elems),"destroyed",[],false)
	  	for ( var i = 0, elem;
			(elem = elems[i]) !== undefined; i++ ) {
				var id = elem[exp]
				delete data[id];
			}
	};
	
	can.data = function(wrapped, name, value){
		return value === undefined ?
			wrapped.length == 0 ? undefined : getData(wrapped[0], name) :
			wrapped.forEach(function(node){
				setData(node, name, value);
			});
	};
	
	// Overwrite `dojo.destroy`, `dojo.empty` and `dojo.place`.
	var empty = dojo.empty;
	dojo.empty = function(){
		for(var c; c = node.lastChild;){ // Intentional assignment.
			dojo.destroy(c);
		} 
	}
	
	var destroy = dojo.destroy;
	dojo.destroy = function(node){
		node = dojo.byId(node);
		cleanData([node]);
		node.getElementsByTagName && cleanData(node.getElementsByTagName('*'))
		
		return destroy.apply(dojo, arguments);
	};
	
	can.addClass = function(wrapped, className){
		return wrapped.addClass(className);
	}
	
	can.remove = function(wrapped){
		// We need to remove text nodes ourselves.
		wrapped.forEach(dojo.destroy);
	}

	can.get = function(wrapped, index){
		return wrapped[index];
	}

	// Add pipe to `dojo.Deferred`.
	can.extend(dojo.Deferred.prototype, {
		pipe : function(done, fail){
			var d = new dojo.Deferred();
			this.addCallback(function(){
				d.resolve( done.apply(this, arguments) );
			});
			
			this.addErrback(function(){
				if(fail){
					d.reject( fail.apply(this, arguments) );
				} else {
					d.reject.apply(d, arguments);
				}
			});
			return d;
		}
	});

}).then('../deferred.js')
