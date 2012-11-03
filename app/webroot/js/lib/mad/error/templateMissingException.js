steal(
	'jquery/lang/string'
).then(function () {

	$.String.getObject('mad.error', null, true);
	mad.error.TemplateMissingException = function (message) {
		this.name = "TemplateMissingException";
		this.message = (message || "Template missing");
	};

	mad.error.TemplateMissingException.prototype = new Error();

});