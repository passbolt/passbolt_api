function throttleCountdown() {
	var currval = parseInt($('.auththrottler .countdown').text());
	$('.auththrottler .countdown').text(currval - 1);
	if(currval - 1 > 0){
		setTimeout(throttleCountdown, 1000);
	}
	else {
		// Reactivate button
		throttleEnd();
	}
}

function throttleInit() {
	var d = new Date();
	var now = d.getTime() / 1000;
	var loginAllowed = $("#nextLogin").val();
	var diff = loginAllowed - now;
	if(diff > 0) {
		$('.auththrottler .countdown').text(parseInt(diff));
		setTimeout(throttleCountdown, 1000);
		$('.login.form input[type=submit]').attr("disabled", "disabled").addClass('disabled');
	}
}

function throttleEnd() {
	$('.login.form input[type=submit]').removeAttr("disabled").removeClass('disabled');
	$('.auththrottler').css('display', 'none');
}

$(function(){
	throttleInit();
});