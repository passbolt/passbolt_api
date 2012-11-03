steal('jquery', function(){
	$(document).ready(function(){
		$(document.body).append("<a class='clickme' href='#'>clickme</a><div class='clickresult'></div>")
		$('.clickme').click(function(){
			$('.clickresult').text("clicked");
		})
	})
})
