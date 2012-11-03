steal('../can.js',{
	id: './zepto.1.0rc1.js',
	_skip: true
}).then('./data', '../event', '../fragment.js', function() {
	// zepto.js
	// ---------
	// _Zepto node list._

// Extend what you can out of Zepto.
$.extend(can, Zepto);

var arrHas = function(obj, name){
	return obj[0] && obj[0][name] || obj[name]
}

// Do what's similar for jQuery.
can.trigger = function(obj, event, args, bubble){
	if(obj.trigger){
		obj.trigger(event, args)
	} else if(arrHas(obj, "dispatchEvent")){
		if(bubble === false){
			$([obj]).triggerHandler(event, args)
		} else {
			$([obj]).trigger(event, args)
		}

	} else {
		if(typeof event == "string"){
			event = {type: event}
		}
		event.target = event.target || obj;
		event.data = args;
		can.dispatch.call(obj, event)
	}

}

can.$ = Zepto

	can.bind = function( ev, cb){
		// If we can bind to it...
		if(this.bind){
			this.bind(ev, cb)
		} else if(arrHas(this, "addEventListener")){
			$([this]).bind(ev, cb)
		} else {
			can.addEvent.call(this, ev, cb)
		}
		return this;
	}
	can.unbind = function(ev, cb){
		// If we can bind to it...
		if(this.unbind){
			this.unbind(ev, cb)
		} else if(arrHas(this, "addEventListener")){
			$([this]).unbind(ev, cb)
		} else {
			can.removeEvent.call(this, ev, cb)
		}
		return this;
	}
	can.delegate = function(selector,ev, cb){
		if(this.delegate){
			this.delegate(selector, ev, cb)
		} else {
			$([this]).delegate(selector,ev, cb)
		}
	}
	can.undelegate = function(selector,ev, cb){
		if(this.undelegate){
			this.undelegate(selector, ev, cb)
		} else {
			$([this]).undelegate(selector,ev, cb)
		}
	}

	$.each(["append","filter","addClass","remove","data"], function(i,name){
		can[name] = function(wrapped){
			return wrapped[name].apply(wrapped, can.makeArray(arguments).slice(1))
		}
	})


	can.makeArray = function(arr){
		var ret = []
		can.each(arr, function(a, i){
			ret[i] = a
		})
		return ret;
	};

	can.proxy = function(f, ctx){
		return function(){
			return f.apply(ctx, arguments)
		}
	}

	// Make ajax.
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
	can.ajax = function(options){

		var success = options.success,
			error = options.error;
		var d = can.Deferred();

		options.success = function(data) {

			updateDeferred(xhr, d);
			d.resolve.call(d, data);
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






	// Make destroyed and empty work.
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


    can.trim = function(str){
    	return str.trim();
    }
	can.isEmptyObject = function(object){
		var name;
		for(name in object){};
		return name === undefined;
	}

	// Make extend handle `true` for deep.
	can.extend = function(first){
		if(first === true){
			var args = can.makeArray(arguments);
			args.shift();
			return $.extend.apply($, args)
		}
		return $.extend.apply($, arguments)
	}

	can.get = function(wrapped, index){
		return wrapped[index];
	}


	return can;
}).then('../deferred.js','can/util/array/each.js')
