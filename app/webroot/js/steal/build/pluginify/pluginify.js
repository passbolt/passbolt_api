// usage: 
// js steal\scripts\pluginify.js funcunit/functional -out funcunit/dist/funcunit.js
// js steal\scripts\pluginify.js jquery/controller
// js steal\scripts\pluginify.js jquery/event/drag -exclude jquery/lang/vector/vector.js jquery/event/livehack/livehack.js

// _args = ['jquery/controller']; load('steal/pluginifyjs')

steal('steal', 'steal/parse','steal/build',
 function(s, parse) {
	var isArray = function(arr){
		return Object.prototype.toString.call(arr)=== "[object Array]"
	}
	/**
	 * @function steal.build.pluginify
	 * @parent steal.build
	 * 
	 * Builds a 'steal-less' version of your application.  To use this, files that use steal must
	 * have their code within a callback function.
	 * 
	 *     js steal\pluginify jquery\controller -nojquery
	 *   
	 * @param {Object} plugin
	 * @param {Object} opts options include:
	 * 
	 *   - out - where to put the generated file
	 *   - exclude - an array of files to exclude
	 *   - nojquery - exclude jquery
	 *   - compress - compress the file
	 *   - wrapInner - an array containing code you want to wrap the output in [before, after]
	 *   - skipAll - don't run any of the code in steal callbacks (used for canjs build)
	 *   - shim - add existing global object to modules collection
	 *   - standAlone - Only stip
	 */
	s.build.pluginify = function(plugin, opts){
		s.print("" + plugin + " >");
		var jq = true, 
			othervar, 
			opts = steal.opts(opts, {
				"out": 1,
				"exclude": -1,
				"nojquery": 0,
				"compress": 0,
				"onefunc": 0,
				"wrapInner": 0,
				"skipAll": 0,
				"standAlone": 0,
				"shim": {},
				"exports": {}
			}),
			where = opts.out || plugin + "/" + plugin.replace(/\//g, ".") + ".js";

		opts.exclude = !opts.exclude ? [] : (isArray(opts.exclude) ? opts.exclude : [opts.exclude]);
		opts.namespace = opts.namespace || "namespace";

		if (opts.nojquery) {
			jq = false;
			//othervar = opts.nojquery;
			opts.exclude.push("jquery.js");
		}
		opts.exclude.push("steal/dev/");
		opts.exclude.push("stealconfig.js");
		rhinoLoader = {
			callback: function(s){
				s.pluginify = true;
			}
		};
		var out = '', 
			str, 
			i, 
			inExclude = function(stl){
				var path = ""+stl.id;
				for (var i = 0; i < opts.exclude.length; i++) {
					if ((opts.exclude[i].substr(-1) === "/" && path.indexOf(opts.exclude[i]) === 0
						|| path == opts.exclude[i])
						|| stl._skip) {
						return true;
					}
				}
				return false;
			}, 
			pageSteal, 
			steals = [], 
			fns = {};

		steal.build.open("steal/rhino/blank.html", {
			startFile : plugin, 
			skipAll: opts.skipAll
		}, function(opener){
			opener.each(function(stl, resource, i){
				print("> ",stl.id)
				if(stl.buildType === "fn") {
					fns[stl.id] = true;
				}
				else if(fns[stl.id] && stl.buildType === "js"){ // if its a js type and we already had a function, ignore it
					return;
				}
				var id = ( ""+stl.id );
				var inStandAlone = (opts.standAlone &&  id === plugin) ||
					(opts.standAlone && opts.standAlone.indexOf && opts.standAlone.indexOf(id) !== -1);
				if ( inStandAlone || (!opts.standAlone && !inExclude(stl)) ) {

					var content = s.build.pluginify.content(stl, opts, resource, opener.steal);
					if (content) {
						out += '// ## ' + stl.id + '\n';
						if(stl.buildType === 'fn' && !opts.onefunc) {
							out += '\nmodule[\'' + stl.id + '\'] = ';
						}

						if(opts.onefunc) {
							content = content.substring(0, content.lastIndexOf('return'));
						}

						out += s.build.js.clean(content);
					}
				}
				else {
					s.print("  Ignoring " + stl.id)
				}
			}, true);
		}, true, false);

		var output = '';

		if(opts.onefunc) {
			output = opts.wrapInner && opts.wrapInner.length ? opts.wrapInner[0] : '(function(window, undefined) {';
			output += out;
			output += opts.wrapInner && opts.wrapInner.length ? opts.wrapInner[1] : '\n\n})(window);';
		}
		else {
			output = 'module = { _orig: window.module, _define: window.define };\n';

			for(key in opts.shim) {
				output += 'module[\'' + key + '\'] = ' + opts.shim[key] + ';\n';
			}

			output += 'define = function(id, deps, value) {\n';
			output += '\tmodule[id] = value();\n';
			output += '};\ndefine.amd = { jQuery: true };\n' + out + '\n';

			for(key in opts.exports) {
				output += 'window[\'' + opts.exports[key] + '\'] = module[\'' + key + '\'];\n';
			}

			output += '\nwindow.define = module._define;\n';
			output += '\nwindow.module = module._orig;';
		}

		if (opts.compress) {
			var compressorName = (typeof(opts.compress) == "string") ? opts.compress : "localClosure";
			var compressor = steal.build.js.minifiers[compressorName]()
			output = compressor(output);
		}

		s.print("--> " + where);
		new steal.File(where).save(output);
	}
	var funcCount = {};
	//gets content from a steal
	s.build.pluginify.content = function(resourceOpts, opts, resource, stl){
		var param = [],
		deps = stl.resources[resourceOpts.id].dependencies;

		for(var i = 0; i < deps.length - 1; i++) {
			if(deps[i]) {
				param.push(deps[i].options.id);
			}
		}

		if(param.length) {
			param = 'module["' + param.join('"], module["') + '"]';
		}

		if (resourceOpts.buildType == "fn") {
			// if it's a function, go to the file it's in ... pull out the content
			var index = funcCount[resourceOpts.id] || 0, 
				contents = readFile(resourceOpts.id);
			funcCount[resourceOpts.id]++;

			var declaration = '\nvar ' + resourceOpts.id.toString().replace(/\//g, '_') + ' = ';
			declaration = declaration.replace(/\.js/, '');

			contents = s.build.pluginify.getFunction(contents, index, opts.onefunc);

			return opts.onefunc ? contents : "(" + contents + ")(" + param + ");";
		}
		else {
			var content = readFile( s.idToUri( resourceOpts.id, true)   );
			if (/steal[.\(]/.test(content)) {
				content = s.build.pluginify.getFunction(content, 0, opts.onefunc)
				if(content && !opts.onefunc){
					content =  "(" + content + ")(" + param + ");";
				}
			}
			//make sure steal isn't in here
			return content;
		}
	};
	s.build.pluginify.getFunction = function(content, ith, onewrap){
		var p = parse(content), 
			token, 
			funcs = [];
		while (token = p.moveNext()) {
			//print(token.value)
			if (token.type !== "string") {
				switch (token.value) {
					case "steal":
						stealPull(p, content, function(func){
							funcs.push(func)
						}, onewrap);
						break;
				}
			}
		}

		return funcs[ith || 0];
	};
	//gets a function from steal
	var stealPull = function(p, content, cb, onewrap){
		var token = p.next(), startToken, endToken;
		if (!token || (token.value != "." && token.value != "(")) {
			// we said steal .. but we don't care
			return;
		}
		else {
			p.moveNext();
		}
		if (token.value == ".") {
			p.until("(")
		}
		var tokens = p.until("function", ")");
		if (tokens && tokens[0].value == "function") {
			
			token = tokens[0];
			
			startToken = p.until("{")[0];
			
			endToken = p.partner("{");
			cb(content.substring(onewrap ? startToken.from+1 :token.from, onewrap ? endToken.to-1 : endToken.to))
			//print("CONTENT\n"+  );
			p.moveNext();
		}
		else {
		
		}
		stealPull(p, content, cb, onewrap);
		
	};
});
