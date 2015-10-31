steal(
	'jquery/lang/string'
).then(function () {

	$.String.getObject('mad.error', null, true);
	mad.error.NoConstructorException = function (message) {
		this.name = "NoConstructorException";
		this.message = (message || "This class has no constructor");
	};

	mad.error.NoConstructorException.prototype = new Error();

});