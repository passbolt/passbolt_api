// This is for demo page only
$(function() {
	var state = window.location.hash.substring(1);
	
	/* fake dropdown */
	$('.dropdown a').click(function(c) {
		var button = $(this);
		var dropdown = $(this).closest('.dropdown');
		var content;

		// take the next item after the link as dropdown content
		// if data-dropdown-content-id attribute is empty
		if ($(this).attr('data-dropdown-content-id') == undefined) {
			content = $(this).next();
		} else {
			content = $("#"+$(this).attr('data-dropdown-content-id'));
		}

		dropdown.toggleClass('pressed');
		if(dropdown.hasClass('pressed')) {
		  content.addClass('visible');
		} else {
			content.removeClass('visible'); 
		}
		$('body').click(function () {
			content.removeClass('visible'); 
			dropdown.removeClass('pressed');
		});
		return false;
	});

	/* update loading bar */
	var count = 0;
	$( ".header.first" ).click(function() {
		if (count < 1) {
			$('.notification').html('<span class="message success animated fadeInUp"><strong>Success!</strong> yeah, looks pretty good</span>');
			count++;
		} else if (count < 2) {
			$('.notification').html('<span class="message error animated fadeInUp"><strong>Oops</strong>, something went wrong and here is a pretty long message to example what.</span>');
			count++;
		} else {
			$('.notification').html('<span class="message warning animated fadeInUp"><strong>warning</strong>, does not look that good</span>');
			count=0;
		}
		$('.update-loading-bar .progress-bar span').animate({width:'100%'},function(){
			$('.update-loading-bar .progress-bar span').css('width','0%');
		});
	});

	/* faking initial loading screen */
	if(state == 'load') {
	//$( ".loading-screen" ).click(function() {
		$('.initial-loading-bar .progress-bar span').animate({width:'20%'},function(){
			$('.loading-screen .details').html('doing something else');
			$('.initial-loading-bar .progress-bar span').animate({width:'100%'},function(){
				$('.loading-screen .details').html('and we\'re done!');
				$(".loading-screen").fadeTo( "slow" , 0, function() {
					$(".loading-screen").css( "display",'none');
				});
			});
		});
	//});
	} else {
		$(".loading-screen").css( "display",'none');
	}
	/* faking contextual menu interactions */
	$('.navigation.tree').bind("contextmenu", function () {
    	return false;
    });
	$('.navigation.tree .row').mousedown(function(event) {
		var o = $(this).offset();
		if (event.which == 3) { /* right click */
			$('#js_contextual_menu').css("display","block").css('top',o.top);
			event.stopPropagation();
			return false;
		}
	});
	$('.navigation.tree .more-ctrl a').click(function(){
		var o = $(this).offset();
		$('#js_contextual_menu').css("display","block").css('top',o.top);
		return false;
	});
	$('body').mousedown(function(event) {
		if (event.which) { 
			$('#js_contextual_menu').css('display','none');
		}
	});

	/* fake dialog interactions */
	if(state != 'edit') {
		$('#js_new_dialog').css('display','none');
	}
	$('#js_action_create').click(function() {
		$('#js_new_dialog').css('display','block');
		return false;
	});
	$('#js_dialog_close').click(function() {
		$('#js_new_dialog').css('display','none');
		return false;
	});
	
});
