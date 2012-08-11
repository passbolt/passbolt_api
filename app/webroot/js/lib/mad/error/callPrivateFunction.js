steal('jquery/lang/string')
.then( function ($) {
	$.String.getObject('mad.error', null, true);
	mad.error.CallPrivateFunction = function (message) {
		this.name = "CallPrivateFunction";
		this.message = (message || "This function is private");
	}
	mad.error.CallPrivateFunction.prototype = new Error();
});