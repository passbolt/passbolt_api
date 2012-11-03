steal('jquery', function(jQuery) {
	var FuncUnit = window.FuncUnit || {};
	// TODO: if FuncUnit needs its own jQuery, add a steal.config here to make that happen.
	FuncUnit.jQuery = jQuery//.noConflict(true);
	return FuncUnit;
});