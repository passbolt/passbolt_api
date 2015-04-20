(function($) {
	// The user wants to recheck if the passbolt plugin is well installed on his computer.
	$('#js_plugin_check_retry').click(function(ev) {
		ev.preventDefault();
		window.location.reload('true');
	});
})(jQuery);
