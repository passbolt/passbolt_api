steal(
	'jquery/lang/string'
).then(function () {

	$.String.getObject('mad.error', null, true);
	mad.error.CallAbstractFunctionException = function (message) {
		this.name = "CallAbstractFunctionException";
		this.message = (message || "This function is abstract");
	};

	mad.error.CallAbstractFunctionException.prototype = new Error();

});