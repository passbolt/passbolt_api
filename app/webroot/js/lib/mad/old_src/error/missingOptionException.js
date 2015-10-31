steal(
	'jquery/lang/string'
).then(function () {

	$.String.getObject('mad.error', null, true);
	mad.error.MissingOptionException = function (optionName, className, message) {
		this.name = "MissingOptionException";
		this.message = (message || "The option (" + optionName + ") is missing when the class (" + className + ") is instantiated");
	};

	mad.error.MissingOptionException.prototype = new Error();

});