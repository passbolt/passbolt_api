$(function(){
	$('#UserUsername, #UserPassword').each(function(){
		if($(this).val() != ''){
			$(this).prev('label').addClass('hidden');
		}
	});
	
	$('#UserUsername, #UserPassword').focus(function(){
		$(this).prev('label').addClass('focus');
	})
	.blur(function(){
		$(this).prev('label').removeClass('focus');
	})
	.bind('keyup mouseup change', function(){
		if($(this).val() != ''){
			$(this).prev('label').addClass('hidden');
		}
		else{
			$(this).prev('label').removeClass('hidden');
		}
	});
});