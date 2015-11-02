steal(
	'mad/error/callAbstractFunctionException.js',
	'mad/error/callInterfaceConstructorException.js',
	'mad/error/callInterfaceFunctionException.js',
	'mad/error/callPrivateFunctionException.js',
	'mad/error/missingOptionException.js',
	'mad/error/missingParameterException.js',
	'mad/error/noConstructorException.js',
	'mad/error/templateMissingException.js',
	'mad/error/wrongParametersException.js',
	'mad/error/wrongConfigException.js'
).then(function () {

	$.String.getObject('mad.error', null, true);
	mad.error.Exception = function (message, title) {
		this.name = "Exception";
		this.title = (title || "An " + this.name + " occured");
		this.message = (message || this.title);
	};

	mad.error.Exception.prototype = new Error();

});