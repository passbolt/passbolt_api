steal(
	'jquery/lang/string'
).then(function () {

	$.String.getObject('mad.error', null, true);
	mad.error.MissingParameterException = function (id, message) {
		console
		this.name = "MissingParameterException";
		this.message = (message || "The (" + id + ") parameter of the function ( " + arguments.callee.caller.toString() + " ) is missing");
	};

	mad.error.MissingParameterException.prototype = new Error();

});