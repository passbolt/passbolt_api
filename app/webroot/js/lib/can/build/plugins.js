load("build/underscore.js");
var _ = this._;

load("steal/rhino/rhino.js");
steal('steal/build/pluginify', 'can/build/settings.js', function () {
	// Use with ./js can/build/dist.js <outputfolder> <version> <library1> <library2> <libraryN>
	var version = _args[1] || 'edge';
	var outFolder = (_args[0] || 'can/dist/') + version + '/';
	var wrapjQuery = {
		wrapInner : ['(function(window, $, can, undefined) {\n', '\n})(this, jQuery, can);']
	};

	steal.File(outFolder).mkdirs();

	var plugins = {
		"construct/proxy/proxy" : {
			name : "construct.proxy"
		},
		"construct/super/super" : {
			name : "construct.super"
		},
		"control/plugin/plugin" : {
			name : "control.plugin",
			options : wrapjQuery
		},
		"control/view/view" : {
			name : "control.view"
		},
		"observe/attributes/attributes" : {
			name : "observe.attributes"
		},
		"observe/delegate/delegate" : {
			name : "observe.delegate"
		},
		"observe/setter/setter" : {
			name : "observe.setter"
		},
		"observe/validations/validations" : {
			name : "observe.validations"
		},
		"view/mustache/mustache" : {
			name : "view.mustache"
		},
		"view/modifiers/modifiers" : {
			name : "view.modifiers",
			options : wrapjQuery
		},
		"observe/backup/backup" : {
			name : "observe.backup",
			standAlone : ['can/util/object/object.js']
		},
		"util/fixture/fixture" : {
			name : "fixture",
			standAlone : ['can/util/object/object.js']
		}
	}

	_.each(plugins, function (config, module) {
		var fileName = "can/" + module + ".js";
		var pluginName = outFolder + "/can." + config.name + ".js"
		console.log("Building plugin " + fileName + " to " + pluginName);
		steal.build.pluginify(fileName, _.extend({
			out : pluginName,
			onefunc: true,
			compress : false,
			standAlone : config.standAlone || true,
			wrapInner : ['(function(can, window, undefined) {\n', '\n})(can, this);\n']
		}, config.options));
	});
});
