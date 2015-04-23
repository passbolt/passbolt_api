(function($) {
	// The user wants to recheck if the passbolt plugin is well installed on his computer.
	$('#js_setup_plugin_check').click(function(ev) {
		ev.preventDefault();
		window.location.reload('true');
	});
})(jQuery);
