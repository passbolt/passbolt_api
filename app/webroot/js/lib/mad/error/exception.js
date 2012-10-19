steal(
	MAD_ROOT + '/error/callAbstractFunctionException.js',
	MAD_ROOT + '/error/callInterfaceConstructorException.js',
	MAD_ROOT + '/error/callInterfaceFunctionException.js',
	MAD_ROOT + '/error/callPrivateFunctionException.js',
	MAD_ROOT + '/error/missingOptionException.js',
	MAD_ROOT + '/error/noConstructorException.js',
	MAD_ROOT + '/error/templateMissingException.js',
	MAD_ROOT + '/error/wrongParametersException.js'
).then(function ($) {

	$.String.getObject('mad.error', null, true);
	mad.error.Exception = function (message, title) {
		this.name = "Exception";
		this.title = (title || "An " + this.name + " occured");
		this.message = (message || this.title);
	};

	mad.error.Exception.prototype = new Error();

});