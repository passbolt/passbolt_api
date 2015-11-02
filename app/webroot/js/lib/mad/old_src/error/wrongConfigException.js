steal(
	'jquery/lang/string'
).then(function () {

	$.String.getObject('mad.error', null, true);
	mad.error.WrongConfigException = function (name, message) {
		this.name = "WrongConfigException";
		this.message = (message || "The config (" + name + ") is not well defined");
	};

	mad.error.WrongConfigException.prototype = new Error();

});