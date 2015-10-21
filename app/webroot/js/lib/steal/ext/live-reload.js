var loader = require("@loader");

/**
 * A map of modules names to parents like:
 * {
 *	 "child": {
 *	   "parentA": true,
 *	   "parentB": true
 *	 },
 *	 "parentA": false
 * }
 *
 * This is used to recursively delete parent modules
 *
 */
loader._liveMap = {};

// This is a map of listeners, those who have registered reload callbacks.
loader._liveListeners = {};

// A simple emitter
function E () {
	// Keep this empty so it's easier to inherit from
  // (via https://github.com/lipsmack from https://github.com/scottcorgan/tiny-emitter/issues/3)
}

E.prototype = {
	on: function (name, callback, ctx) {
		var e = this.e || (this.e = {});
		
		(e[name] || (e[name] = [])).push({
			fn: callback,
			ctx: ctx
		});
		
		return this;
	},

	once: function (name, callback, ctx) {
		var self = this;
		var fn = function () {
			self.off(name, fn);
			callback.apply(ctx, arguments);
		};
		
		return this.on(name, fn, ctx);
	},

	each: function(cb){
		var e = this.e || (this.e = {});
		for(var name in e) {
			cb(e[name], name);
		}
	},

	emit: function (name) {
		var data = [].slice.call(arguments, 1);
		var evtArr = ((this.e || (this.e = {}))[name] || []).slice();
		var i = 0;
		var len = evtArr.length;
		
		for (i; i < len; i++) {
			evtArr[i].fn.apply(evtArr[i].ctx, data);
		}
		
		return this;
	},

	off: function (name, callback) {
		var e = this.e || (this.e = {});
		var evts = e[name];
		var liveEvents = [];
	
		if (evts && callback) {
			for (var i = 0, len = evts.length; i < len; i++) {
				if (evts[i].fn !== callback) liveEvents.push(evts[i]);
			}
		}

		(liveEvents.length) 
			? e[name] = liveEvents
			: delete e[name];
	
		return this;
	}
};

// Our instance
loader._liveEmitter = new E();

// Put a hook on `normalize` so we can keep a reverse map of modules to parents.
// We'll use this to recursively reload modules.
var normalize = loader.normalize;
loader.normalize = function(name, parentName){
	var loader = this;

	if(name === "live-reload") {
		name = "live-reload/" + parentName;
		if(!loader.has(name)) {
			loader.set(name, loader.newModule({
				default: makeReload(parentName),
				__useDefault: true
			}));
		}
		return name;
	}

	var done = Promise.resolve(normalize.apply(this, arguments));
	
	if(!parentName) {
		return done.then(function(name){
			// We need to keep modules without parents to so we can know
			// if they need to have their `onLiveReload` callbacks called.
			loader._liveMap[name] = false;
			return name;
		});
	}
	
	// Once we have the fully normalized module name mark who its parent is.
	return done.then(function(name){
		var parents = loader._liveMap[name];
		if(!parents) {
			parents = loader._liveMap[name] = {};
		}

		parents[parentName] = true;

		return name;
	});
};

// Teardown a module name by deleting it and all of its parent modules.
function teardown(moduleName, e, moduleNames) {
	var moduleNames = moduleNames || {};

	var mod = loader.get(moduleName);
	if(mod) {
		moduleNames[moduleName] = true;
		e.emit("!dispose/" + moduleName);
		loader.delete(moduleName);
		if(loader._liveListeners[moduleName]) {
			loader.delete("live-reload/" + moduleName);
			delete loader._liveListeners[moduleName];
		}

		// Delete the module and call teardown on its parents as well.
		var parents = loader._liveMap[moduleName];

		for(var parentName in parents) {
			teardown(parentName, e, moduleNames);
		}
	}
	return moduleNames;
}

function makeReload(moduleName, listeners){
	loader._liveListeners[moduleName] = true;
	var e = loader._liveEmitter;

	function reload(moduleName, callback){
		// 3 forms
		// reload(callback); -> after full cycle
		// reload("foo", callback); -> after "foo" is imported.
		// reload("*", callback); -> after each module imports.
		if(arguments.length === 2) {
			reload.on(moduleName, callback);
			setupUnbind(moduleName, callback);
			return;
		}
		reload.on("!cycleComplete", moduleName);
		setupUnbind("!cycleComplete", moduleName);
	}

	reload.on = bind(e.on, e);
	reload.off = bind(e.off, e);
	reload.once = bind(e.once, e);

	// This allows modules to dispose themselves
	reload.dispose = function(name, callback){
		if(!callback) {
			callback = name;
			name = moduleName;
		}
		var event = "!dispose/" + name;
		reload.on(event, callback);
		setupUnbind(event, callback);
	};

	function setupUnbind(event, callback){
		e.once("!dispose/" + moduleName, function(){
			e.off(event, callback);
		});
	}
	
	return reload;
}

function bind(fn, ctx){
	return fn.bind ? fn.bind(ctx) : function(){
		return fn.apply(ctx, arguments);
	};
}

function reload(moduleName) {
	//var e = startCycle();
	var e = loader._liveEmitter;

	// Call teardown to recursively delete all parents, then call `import` on the
	// top-level parents.
	var moduleNames = teardown(moduleName, e);

	var imports = [];
	function importModule(moduleName){
		return loader["import"](moduleName).then(function(val){
			e.emit(moduleName, val);
			e.emit("*", moduleName, val);
		});
	}

	for(var moduleName in moduleNames) {
		imports.push(importModule(moduleName));
	}
	// Once everything is imported call the global listener callback functions.
	Promise.all(imports).then(function(){
		e.emit("!cycleComplete");
	});
}

function setup(){
	if(loader.liveReload === "false") {
		return;
	}

	var port = loader.liveReloadPort || 8012;
	
	var host = window.document.location.host.replace(/:.*/, '');
	var ws = new WebSocket("ws://" + host + ":" + port);

	ws.onmessage = function(ev){
		var moduleName = ev.data;
		reload(moduleName);
	};
}


var isBrowser = typeof window !== "undefined";

if(isBrowser) {
	if(typeof steal !== "undefined") {
		steal.done().then(setup);
	} else {
		setTimeout(setup);
	}
} else {
	var metaConfig = loader.meta["live-reload"];
	if(!metaConfig) {
		metaConfig = loader.meta["live-reload"] = {};
	}
	// For the build, translate to a noop.
	metaConfig.translate = function(load){
		load.metadata.format = "amd";
		return "def" + "ine([], function(){\n" +
			"return function(){};\n});";
	};
}
