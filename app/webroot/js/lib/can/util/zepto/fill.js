steal(function(){
	var isFn = $.isFunction;
	
	$.makeArray = function(arr){
		var ret = []
		$.each(arr, function(i,a){
			ret[i] = a
		})
		return ret;
	};
	
	var Deferred = function( func ) {
		if ( ! ( this instanceof Deferred ))
			return new Deferred();

		this._doneFuncs = [];
		this._failFuncs = [];
		this._resultArgs = null;
		this._status = "";

		// check for option function: call it with this as context and as first 
		// parameter, as specified in jQuery api
		func && func.call(this, this);
	};
	
	$.when = Deferred.when = function() {
		var args = $.makeArray( arguments );
		if (args.length < 2) {
			var obj = args[0];
			if (obj && ( isFn( obj.isResolved ) && isFn( obj.isRejected ))) {
				return obj;			
			} else {
				return Deferred().resolve(obj);
			}
		} else {
			
			var df = Deferred(),
				done = 0,
				// resolve params: params of each resolve, we need to track down 
				// them to be able to pass them in the correct order if the master 
				// needs to be resolved
				rp = [];

			$.each(args, function(j, arg){
				arg.done(function() {
					rp[j] = (arguments.length < 2) ? arguments[0] : arguments;
					if (++done == args.length) {
						df.resolve.apply(df, rp);
					}
				}).fail(function() {
					df.reject(arguments);
				});
			});

			return df;
			
		}
	}
	
	var resolveFunc = function(type, _status){
		return function(context){
			var args = this._resultArgs = (arguments.length > 1) ? arguments[1] : [];
			return this.exec(context, this[type], args, _status);
		}
	},
	doneFunc = function(type, _status){
		return function(){
			var self = this;
			$.each(arguments, function( i, v, args ) {
				if ( ! v )
					return;
				if ( v.constructor === Array ) {
					args.callee.apply(self, v)
				} else {
					// immediately call the function if the deferred has been resolved
					if (self._status === _status)
						v.apply(self, self._resultArgs || []);
	
					self[type].push(v);
				}
			});
			return this;
		}
	};

	$.extend( Deferred.prototype, {
		pipe : function(done, fail){
			var d = $.Deferred();
			this.done(function(){
				d.resolve( done.apply(this, arguments) );
			});
			
			this.fail(function(){
				if(fail){
					d.reject( fail.apply(this, arguments) );
				} else {
					d.reject.apply(d, arguments);
				}
			});
			return d;
		},
		resolveWith : resolveFunc("_doneFuncs","rs"),
		rejectWith : resolveFunc("_failFuncs","rj"),
		done : doneFunc("_doneFuncs","rs"),
		fail : doneFunc("_failFuncs","rj"),
		always : function() {
			var args = $.makeArray(arguments);
			if (args.length && args[0])
				this.done(args[0]).fail(args[0]);

			return this;
		},

		then : function() {
			var args = $.makeArray( arguments );
			// fail function(s)
			if (args.length > 1 && args[1])
				this.fail(args[1]);

			// done function(s)
			if (args.length && args[0])
				this.done(args[0]);

			return this;
		},

		isResolved : function() {
			return this._status === "rs";
		},

		isRejected : function() {
			return this._status === "rj";
		},

		reject : function() {
			return this.rejectWith(this, arguments);
		},

		resolve : function() {
			return this.resolveWith(this, arguments);
		},

		exec : function(context, dst, args, st) {
			if (this._status !== "")
				return this;

			this._status = st;

			$.each(dst, function(i, d){
				d.apply(context, args);
			});

			return this;
		}
	});
	var XHR = $.ajaxSettings.xhr;
	$.ajaxSettings.xhr = function(){
		var xhr = XHR()
		var open = xhr.open;
		xhr.open = function(type, url, async){
			open.call(this, type, url, ASYNC === undefined ? true : ASYNC)
		}
		return xhr;
	}
	var ASYNC;
	var AJAX = $.ajax;
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
	$.ajax = function(options){
		
		var success = options.success,
			error = options.error;
		var d = Deferred();
		
		options.success = function(){
			
			updateDeferred(xhr, d);
			d.resolve.apply(d, arguments);
			success && success.apply(this,arguments);
		}
		options.error = function(){
			updateDeferred(xhr, d);
			d.reject.apply(d, arguments);
			error && error.apply(this,arguments);
		}
		if(options.async === false){
			ASYNC = false
		}
		var xhr = AJAX(options);
		ASYNC = undefined;
		updateDeferred(xhr, d);
		return d;
	};
	
	$.Deferred = Deferred;
	can.addEvent = function(event, fn){
		if(!this.__bindEvents){
			this.__bindEvents = {};
		}
		if(!this.__bindEvents[event]){
			this.__bindEvents[event] = [];
		}
		this.__bindEvents[event].push(fn);
	};
	can.removeEvent = function(event, fn){
		var i = this.__bindEvents[event].indexOf(fn);
		this.__bindEvents[event].splice(i, 1);
	};
	can.dispatch = function(event){
		if(!this.__bindEvents){
			return;
		}
		var handlers = this.__bindEvents[event.type] || [],
			self= this,
			args = [event].concat(event.data || []);
		$.each(handlers, function(i, handler){
			event.data = args.slice(1);
			handler.apply(self, args);
		});
	}
	$.proxy = function(f, ctx){
		return function(){
			return f.apply(ctx, arguments)
		}
	}
	$.inArray =function(item, arr){
		return arr.indexOf(item)
	}
	
	
	
	$.fn.empty = function(){
		return this.each(function(){ 
			$.cleanData(this.getElementsByTagName('*'))
			this.innerHTML = '' 
		}) 
	}
	
	$.fn.remove= function () {
		$.cleanData(this);
		this.each(function () {
			if (this.parentNode != null) {
				// might be a text node
				this.getElementsByTagName && $.cleanData(this.getElementsByTagName('*'))
				this.parentNode.removeChild(this);
			}
		});
		return this;
    }
    $.trim = function(str){
    	return str.trim();
    }
	$.isEmptyObject = function(object){
		var name;
		for(name in object){};
		return name !== undefined;
	}
	// make extend handle true for deep
	var old = $.extend;
	$.extend = function(first){
		if(first === true){
			var args = $.makeArray(arguments);
			args.shift();
			return old.apply($, args)
		}
		return old.apply($, arguments)
	}
	var table = document.createElement('table'),
    	tableRow = document.createElement('tr'),
		containers = {
		  'tr': document.createElement('tbody'),
		  'tbody': table, 'thead': table, 'tfoot': table,
		  'td': tableRow, 'th': tableRow,
		  '*': document.createElement('div')
		},
   		fragmentRE = /^\s*<(\w+)[^>]*>/,
   		fragment  = function(html, name) {
		    if (name === undefined) {
		    	name = fragmentRE.test(html) && RegExp.$1;
		    }
		    if (!(name in containers)) name = '*';
		    var container = containers[name];
		    container.innerHTML = '' + html;
		    return [].slice.call(container.childNodes);
		}
	
	$.buildFragment = function(html, node){
		var parts = fragment(html),
			frag = document.createDocumentFragment();
		parts.forEach(function(part){
			frag.appendChild(part);
		})
		return frag
	}
	
})
