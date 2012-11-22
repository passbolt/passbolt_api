// steal's deferred library. It is used through steal
// to support jQuery like API for file loading.

var Deferred = function( func ) {
	if (!(this instanceof Deferred)) return new Deferred();
	// arrays for `done` and `fail` callbacks
	this.doneFuncs = [];
	this.failFuncs = [];

	this.resultArgs = null;
	this.status = "";

	// check for option function: call it with this as context and as first
	// parameter, as specified in jQuery api
	func && func.call(this, this);
}

Deferred.when = function() {
	var args = h.map(arguments);
	if ( args.length < 2 ) {
		var obj = args[0];
		if ( obj && (h.isFn(obj.isResolved) && h.isFn(obj.isRejected)) ) {
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

		h.each(args, function( j, arg ) {
			arg.done(function() {
				rp[j] = (arguments.length < 2) ? arguments[0] : arguments;
				if (++done == args.length ) {
					df.resolve.apply(df, rp);
				}
			}).fail(function() {
				df.reject(arguments);
			});
		});

		return df;

	}
}
// call resolve functions
var resolveFunc = function( type, status ) {
	return function( context ) {
		var args = this.resultArgs = (arguments.length > 1) ? arguments[1] : [];
		return this.exec(context, this[type], args, status);
	}
},

	doneFunc = function( type, status ) {
		return function() {
			var self = this;
			h.each(arguments, function( i, v, args ) {
				if (!v ) return;
				if ( v.constructor === Array ) {
					args.callee.apply(self, v)
				} else {
					// immediately call the function if the deferred has been resolved
					if ( self.status === status ) v.apply(this, self.resultArgs || []);

					self[type].push(v);
				}
			});
			return this;
		}
	};

h.extend(Deferred.prototype, {
	resolveWith: resolveFunc("doneFuncs", "rs"),
	rejectWith: resolveFunc("failFuncs", "rj"),
	done: doneFunc("doneFuncs", "rs"),
	fail: doneFunc("failFuncs", "rj"),
	always: function() {
		var args = h.map(arguments);
		if ( args.length && args[0] ) this.done(args[0]).fail(args[0]);

		return this;
	},
	then: function() {
		var args = h.map(arguments);
		// fail function(s)
		if ( args.length > 1 && args[1] ) this.fail(args[1]);

		// done function(s)
		if ( args.length && args[0] ) this.done(args[0]);

		return this;
	},
	isResolved: function() {
		return this.status === "rs";
	},
	isRejected: function() {
		return this.status === "rj";
	},
	reject: function() {
		return this.rejectWith(this, arguments);
	},
	resolve: function() {
		return this.resolveWith(this, arguments);
	},
	exec: function( context, dst, args, st ) {
		if ( this.status !== "" ) return this;

		this.status = st;

		h.each(dst, function( i, d ) {
			d.apply(context, args);
		});

		return this;
	}
});
// ## HELPER METHODS FOR DEFERREDS
// Used to call a method on an object or resolve a
// deferred on it when a group of deferreds is resolved.
//
//     whenEach(resources,"complete",resource,"execute")
var whenEach = function( arr, func, obj, func2 ) {
	
	var deferreds = h.map(arr, func)
	return Deferred.when.apply(Deferred, deferreds).then(function() {
		if ( h.isFn(obj[func2]) ) {
			obj[func2]()
		} else {
			obj[func2].resolve();
		}

	});
};