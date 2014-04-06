$.fn.authThrottle = function( options ) {

	var defaults = {
		// Put defaults values here
		eltNextLogin : "#nextLogin",
		eltThrottlerContainer : '.auththrottler',
		eltThrottlerCountdown : '.auththrottler .countdown'
	};

	var settings = $.extend( {}, defaults, options );

	/**
	 * Countdown and update the value on the display till we reach 0.
	 */
	function throttleCountdown() {
		var currval = parseInt($(settings.eltThrottlerCountdown).text());
		$(settings.eltThrottlerCountdown).text(currval - 1);
		if(currval - 1 > 0){
			setTimeout(throttleCountdown, 1000);
		}
		else {
			// Reactivate button
			throttleEnd();
		}
	}

	/**
	 * End the countdown. Hide the countdown element and reactivate the login button.
	 */
	function throttleEnd() {
		$('.login.form input[type=submit]').removeAttr("disabled").removeClass('disabled');
		$(settings.eltThrottlerContainer).css('display', 'none');
	}

	/**
	 * Init the throttle by initializing the countdown if any.
	 */
	function throttleInit() {
		var nbSeconds = $(settings.eltNextLogin).val();
		if(nbSeconds >= 0) {
			$(settings.eltThrottlerCountdown).text(parseInt(nbSeconds));
			setTimeout(throttleCountdown, 1000);
			$('.login.form input[type=submit]').attr("disabled", "disabled").addClass('disabled');
		}
	}

	return this.each(function() {
		var $this = $(this);
		throttleInit();
	});
};


$(function(){
	// Start Throttle.
	$('#UserLoginForm').authThrottle();
});
