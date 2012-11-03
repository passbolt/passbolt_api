steal(
	'jquery/lang/string'
).then(function () {

	$.String.getObject('mad.error', null, true);
	mad.error.CallInterfaceFunctionException = function (message) {
		this.name = "CallInterfaceFunctionException";
		this.message = (message || "This function is an interface function");
	};

	mad.error.CallInterfaceFunctionException.prototype = new Error();

});