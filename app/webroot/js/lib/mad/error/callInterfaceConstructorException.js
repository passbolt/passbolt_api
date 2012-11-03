steal(
	'jquery/lang/string'
).then(function () {

	$.String.getObject('mad.error', null, true);
	mad.error.CallInterfaceConstructorException = function (message) {
		this.name = "CallInterfaceConstructorException";
		this.message = (message || "This constructor is an interface constructor");
	};

	mad.error.CallInterfaceConstructorException.prototype = new Error();

});