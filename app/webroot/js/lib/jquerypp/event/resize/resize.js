steal('jquery/event/reverse', function($) {
	var
		// bind on the window window resizes to happen
		win = $(window),
		windowWidth = 0,
		windowHeight = 0,
		timer;

	$(function() {
		windowWidth = win.width();
		windowHeight = win.height();
	});

	$.event.reverse('resize', {
		handler : function(ev, data) {
			var isWindow = this === window;

			// if we are the window and a real resize has happened
			// then we check if the dimensions actually changed
			// if they did, we will wait a brief timeout and
			// trigger resize on the window
			// this is for IE, to prevent window resize 'infinate' loop issues
			if ( isWindow && ev.originalEvent ) {
				var width = win.width(),
					height = win.height();


				if ((width != windowWidth || height != windowHeight)) {
					//update the new dimensions
					windowWidth = width;
					windowHeight = height;
					clearTimeout(timer)
					timer = setTimeout(function() {
						win.trigger("resize");
					}, 1);

				}
				return true;
			}
		}
	});

	return $;
});
