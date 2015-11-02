steal(
	'jquery/lang/string'
).then(function () {

	$.String.getObject('mad.error', null, true);
	mad.error.WrongParametersException = function (name, type) {
		name = name || 'N/A';
		this.name = "WrongParametersException";
		this.message = ("Wrong parameter [" + name + "] expected type is [" + type + "]");
	};

	mad.error.WrongParametersException.prototype = new Error();

});
