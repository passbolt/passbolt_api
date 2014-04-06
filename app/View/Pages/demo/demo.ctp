<?php
/**
 * Demo Password Workspace Page 
 *
 * @copyright		 copyright 2013 passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.View.Elements.demo.tree
 * @since				 version 2.13.02
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>		<html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>		<html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Password Workspace Static Demo</title>
	<base href="<?php echo Router::url('/',true);?>">
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<link href="css/default/main.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/lib/compat/modernizr-2.6.2.min.js"></script>
	<script type="text/javascript" src="js/lib/jquery/jquery-1.11.0.min.js"></script>
</head>
<body>
<div id="container" class="page">
<?php //echo $this->element('demo/loading-screen'); ?>
<?php echo $this->element('demo/loading-bar'); ?>
<?php echo $this->element('demo/notification'); ?>
	<div class="header first">
<?php echo $this->element('demo/nav'); ?>
	</div>
	<div class="header second">
<?php echo $this->element('demo/header'); ?>
	</div>
	<div class="header third">
<?php echo $this->element('demo/actions'); ?>
	</div>
	<div class="panel main">
		<div class="panel left">
<?php echo $this->element('demo/tree'); ?>
		</div>
		<div class="panel middle">
<?php echo $this->element('demo/table'); ?>
		</div>
		<div class="panel aside">
<?php echo $this->element('demo/sidebar'); ?>
		</div>
	</div>
	<div class="footer">
<?php echo $this->element('demo/footer'); ?>
	</div>
</div>
<script>
	// if there is not css styling for scrollbar
	// uses jScrollPane with mousewheel
	Modernizr.load({
		test: Modernizr.cssscrollbar,
		nope: [
			'js/lib/jquery/jquery-1.11.0.min.js',
			'js/lib/compat/jquery.mousewheel.js',
			'js/lib/compat/jquery.jscrollpane.min.js'
		],
		complete : function() {
			if (!Modernizr.cssscrollbar) {
				var settings = {
					showArrows: false,
					autoReinitialise: true
				};
				$('.scroll').jScrollPane(settings);
			}
		}
	});
	// if there is support for html5 input placeholder
	// TODO polyfill for html5 placeholder
  
	// TODO This is for demo only
	$(function() {
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

		/* faking initial loading screen 
		//$( ".loading-screen" ).click(function() {
			$('.initial-loading-bar .progress-bar span').animate({width:'20%'},function(){
				$('.loading-screen .details').html('doing something else');
				$('.initial-loading-bar .progress-bar span').animate({width:'100%'},function(){
					$('.loading-screen .details').html('and we\'re done!');
					$(".loading-screen").fadeTo( "slow" , 0, function() {
						$(".loading-screen").css( "display",'none');
					});
				});
			});*/
		//});

	});
</script>
</body>
</html>
