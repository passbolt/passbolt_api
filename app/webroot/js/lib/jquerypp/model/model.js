/*global OpenAjax: true */

steal('jquery', 'can/util', 'can/model','can/observe/attributes','can/observe/setter','can/observe/elements', function($, can){
	$.Model = can.Model;
	var get = $.Model.prototype.__get;
	$.Model.prototype.__get = function(attr) {
		var getter = attr && ("get" + can.classize(attr));
		return typeof this[getter] === 'function' ? this[getter]() : get.apply(this,arguments);
	};

	$.Model.prototype.update = function( attrs, success, error ) {
		steal.dev.log('$.Model.update is deprecated. You can use attr + save instead.');
		this.attr(attrs);
		return this.save(success, error);
	};
});
