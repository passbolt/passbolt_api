var css = require("$css");
var lessEngine = require("less");

exports.instantiate = css.instantiate;

var options = steal.config('lessOptions') || {};

// default optimization value.
options.optimization |= lessEngine.optimization;

exports.translate = function(load) {
	var address = load.address.replace(/^file\:/,"");

	var pathParts = (address+'').split('/');
		pathParts[pathParts.length - 1] = ''; // Remove filename

	if (typeof window !== 'undefined') {
		pathParts = (load.address+'').split('/');
		pathParts[pathParts.length - 1] = ''; // Remove filename
	}

	return new Promise(function(resolve, reject){
		var renderOptions = {filename: address};
		for (var prop in options){
		   	renderOptions[prop] = options[prop]
		}
		renderOptions.paths = (options.paths || []).concat(pathParts.join('/'))

		var done = function(output) {
			// Put the source map on metadata if one was created.
			load.metadata.map = output.map;
			resolve(output.css);
		};

		var fail = function(error) {
			reject(error);
		};

		lessEngine.render(load.source, renderOptions).then(done, fail);
	});
};

exports.buildType = "css";
