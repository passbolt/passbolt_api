steal('jquery/lang/string')
.then( function ($) {
	$.String.getObject('mad.error', null, true);
	mad.error.NoConstructor = function (message) {
		this.name = "NoConstructor";
		this.message = (message || "This class has no constructor");
	}
	mad.error.NoConstructor.prototype = new Error();
});