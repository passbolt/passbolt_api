// load('can/util/make.js')

/**
 * How this all works:
 * 
 * There are 4 scenarios to account for with canjs library loading: 
 * development, building with env.js, using the standalone canjs scripts, 
 * and using a fully built production.js file.
 * 
 * 1) development mode - the libaries will be loaded by their corresponding 
 *		util plugin (the _skip option is ignored in dev mode)
 * 2) while building with env.js - skipCallbacks is set in steal and _skip 
 *		option causes env.js not load the libary files (because isBuilding is 
 *		set in open.js).  All this is to avoid loading the libraries in env.js, 
 *		because env throws errors while loading them.  skipCallbacks allows no 
 *		steal callbacks to be called, so the build finishes without error.  
 *		This is mainly for use in this make.js script. The libs other than 
 *		jQuery can't be used yet in a full production.js build because of this 
 *		env weakness.
 * 3) using the standalone canjs scripts - these scripts are build with 
 *		pluginify, so they don't include any reference to steal.  They require 
 *		the user to load the actual libarary file first, then the corresponding 
 *		canjs script.
 * 4) using a fully built production.js file - This is only really relevant for 
 *		jQuery until the env build problem is fixed. The libs are built into 
 *		the production.js file.
 */

var each = function( obj, fn ) {

		var i, len;

		if ( obj instanceof Array ) {
			for ( i = 0, len = obj.length; i < len; i++ ) {
				if ( fn.call( obj, obj[ i ], i, obj ) === false ) {
					break;
				}
			}
		} else {
			for ( i in obj ) {
				if ( fn.call( obj, obj[ i ], i, obj ) === false ) {
					break;
				}
			}
		}

	},
	extend = function( d, s ) {
		for ( var p in s ) {
			d[p] = s[p];
		}
		return d;
	};

load("steal/rhino/rhino.js");
steal('steal/build/pluginify', function() {

	
	var libs = {
			"jquery" : {
				exclude : "jquery",
				shim : { 
					'jquery' : 'jQuery'
				}
			},
			"mootools" : {},
			"zepto" : {},
			"dojo" : {
				wrapInner: [
					'\ndefine("can/dojo", ["dojo/query", "dojo/NodeList-dom", "dojo/NodeList-traverse"], function(){\n',
					'\nreturn can;\n});\n'
				]
			},
			"yui" : {
				wrapInner: [
					'\nYUI().add("can", function(Y) {\ncan.Y = Y;\n',
					'}, "0.0.1", {\nrequires: ["node", "io-base", "querystring", "event-focus", "array-extras"],\n optional: ["selector-css2", "selector-css3"]\n});\n'
				]
			}
			
		},	
		types = {
			".min" : true,
			"" : false
		},	
		plugins = {
			standAlone: {
				"construct/proxy/proxy" : "construct.proxy",
				"construct/super/super" : "construct.super",
				"control/plugin/plugin" : "control.plugin",
				"control/view/view" : "control.view",
				"observe/attributes/attributes" : "observe.attributes",
				"observe/delegate/delegate" : "observe.delegate",
				"observe/setter/setter" : "observe.setter",
				"observe/validations/validations" : "observe.validations",
				"view/modifiers/modifiers" : "view.modifiers"
			},
			can_util_object: {
				"observe/backup/backup" : "observe.backup",
				"util/fixture/fixture" : "fixture"

			}
		},
		version = readFile( "can/build/version" ),
		temp, lib;


	if ( _args[0] && _args[0] in libs ) {
		lib = _args[0];
		temp = {};
		temp[ lib ] = libs[ lib ];
		libs = temp;
	}

	steal.File("can/dist").mkdirs();
	steal.File("can/dist/edge").mkdirs();

	// Build libraries
	each( libs, function( options, lib ) {
		each( types, function( compress, type ) {

			var code;

			steal.build.pluginify("can/util/make/" + lib + ".js", extend({
				out : "can/dist/edge/can." + lib + type + ".js",
				// global : "can = {}",
				// namespace : "can",
				// onefunc : true,
				exports : { 'can/util/can.js' : 'can' },
				compress: compress,
				skipCallbacks: true
			}, options ));

			// TODO Strip multiline comments from uncompressed files
//			if ( ! compress ) {
//
//				// Put new index.html into production mode
//				code = readFile( "can/dist/edge/can." + lib + type + ".js" );
//
//				// Remove multiline comments
//				code = code.replace( /\/\*(?:.*)(?:\n\s+\*.*)*\n/gim, "");
//
//				// Remove double semicolons from steal pluginify
//				code = code.replace( /;[\s]*;/gim, ";");
//				code = code.replace( /(\/\/.*)\n[\s]*;/gi, "$1");
//
//				// Only single new lines
//				code = code.replace( /(\n){3,}/gim, "\n\n");
//
//				// Save the file.
//				steal.File( "can/dist/edge/can." + lib + type + ".js" ).save( code );
//			}

			// Replace version
			code = readFile( "can/dist/edge/can." + lib + type + ".js" );
			code = code.replace( /\#\{VERSION\}/gim, version );
			steal.File( "can/dist/edge/can." + lib + type + ".js" ).save( code );
		});
	});

	steal.build.pluginify("can/util/can-all.js", {
		out : "can/dist/edge/can.jquery.all.js",
		exports : { 'can/util/can.js' : 'can' },
		compress: false,
		skipCallbacks: true,
		exclude : "jquery",
		shim : { 'jquery' : 'jQuery' }
	});

	// Build standalone plugins
	STEALDOJO = STEALMOO = STEALYUI = STEALZEPTO = false;
	STEALJQUERY = true;

	if ( ! lib ) {
		each( plugins.standAlone, function( output, input ) {

			var code;

			steal.build.pluginify("can/" + input + ".js", {
				out: "can/dist/edge/can." + output + ".js",
	//			global: "this.can",
	//			onefunc: true,
				compress: false,
	//			skipCallbacks: true,
				namespace : "can",
				standAlone: true
			});

		});

		// Build can.fixture and can.observe.backup seperately
		// They need can/util/object, so we can't use the standAlone option
		each( plugins.can_util_object, function( output, input ) {

			steal.build.pluginify("can/" + input + ".js", {
				out: "can/dist/edge/can." + output + ".js",
				shim : { 'can/util' : 'can' },
				exclude: [
					'jquery',
					'can/util/preamble.js',
					'can/util/jquery/jquery.js',
					'can/util/array/each.js',
					'can/util/string/string.js',
					'can/construct/construct.js',
					'can/observe/observe.js'
				],
				compress: false,
				skipCallbacks: true,
				standAlone: false
			});

		});

		// Build can.fixture and can.observe.backup seperately
		// They need can/util/object, so we can't use the standAlone option
		each( plugins.can_util_object, function( output, input ) {
			
			steal.build.pluginify("can/" + input + ".js", {
				out: "can/dist/edge/can." + output + ".js",
				global: "this.can",
				onefunc: true,
				exclude: [
					'can/util/jquery/jquery.1.8.2.js',
					'can/util/preamble.js',
					'can/util/jquery/jquery.js',
					'can/util/array/each.js',
					'can/util/string/string.js',
					'can/construct/construct.js',
					'can/observe/observe.js'
				],
				compress: false,
				skipCallbacks: true,
				namespace: "can",
				standAlone: false
			});


		});
	}
});
