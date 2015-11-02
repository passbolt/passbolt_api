steal('jquery/lang/string').then(function () {

	$.String.getObject('mad.error', null, true);

	mad.error.CallPrivateFunctionException = function (message) {
		this.name = "CallPrivateFunctionException";
		this.message = (message || "This function is private");
	};

	mad.error.CallPrivateFunctionException.prototype = new Error();

});