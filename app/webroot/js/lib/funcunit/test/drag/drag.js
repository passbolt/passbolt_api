//load a lot of stuff ...
steal('funcunit/browser/resources/jquery.js')
	.then('funcunit/test/dragdrop.js', function(){
	
	var hoveredOnce = false;
	$(".over").bind('mouseover',function(){
		if (!hoveredOnce) {
			$(this).addClass('hover')
			$(document.body).append("<input type='text' id='typer' />")
			hoveredOnce = true;
		}
	})
	
	$('#drag')
		.live("draginit", function(){})
		
	$('#drop')
		.live("dropover", function(){
			$(document.body).append("<a href='#' id='clicker'>click</a>")
			$("#clicker").click(function(){
				$(".status").html("dragged")
			})
		})
	
})