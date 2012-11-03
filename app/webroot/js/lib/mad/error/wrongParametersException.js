steal(
	'jquery/lang/string'
).then(function () {

	$.String.getObject('mad.error', null, true);
	mad.error.WrongParametersException = function (name) {
		name = name || 'N/A';
		this.name = "WrongParametersException";
		this.message = ("Wrong parameters [" + name + "]");
	};

	mad.error.WrongParametersException.prototype = new Error();

});