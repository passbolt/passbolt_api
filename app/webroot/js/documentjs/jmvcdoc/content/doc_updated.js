steal('jquery','documentjs/jmvcdoc/demo', function($){
	
	var disqusIsLoaded = false,
	    commentsTimeout;
	$(document).bind('docUpdated', function(ev, docData){
		
		
		$('#disqus_thread').hide();
		var target = $(ev.target);
		
		// favorite link
		target.find("h1.addFavorite").
				append('&nbsp;<span class="favorite favorite' + (docData.isFavorite ? 'on' : 'off') + '">&nbsp;&nbsp;&nbsp;</span>');
		
		
		// scroll to the top
		
		
		// highlighting
		target.find("code").highlight();
		
		
		// API
		if ( $("#api").length ) {
			var names = [];
			for ( var name in Search._data.list ) {
				names.push(name)
			}
			$("#api").html(
			DocumentationHelpers.link("[" + names.sort(Search.sortJustStrings).join("]<br/>[") + "]", true))
		}


		// cleanup iframe menu when navigating to another page
		$(".iframe_menu_wrapper").remove();
		

		// hookup iframe ui
		//$(".iframe_wrapper").iframe();

		// hookup demo ui
		$(".demo_wrapper").demo();

		// add absolute paths to image tags
		$(".image_tag").each(function() {
			var imageTagEl = $(this),
				relativePath = imageTagEl.attr("src"),
				absolutePath = steal.root.join(relativePath);
			imageTagEl.attr("src", absolutePath);
		});
		
		if ( steal.options.env == 'production' && docData.name != "index" && typeof(COMMENTS_LOCATION) != "undefined" && $("#disqus_thread").length ){
			
			if(!disqusIsLoaded){
				//window.disqus_developer = 1;
	      window.disqus_identifier = window.location.hash;
	      window.disqus_url = window.location.toString();
				$.getScript(COMMENTS_LOCATION);
				disqusIsLoaded = true;
				$('#disqus_thread').show();
			}else{
				clearTimeout(commentsTimeout);
				commentsTimeout = setTimeout(function(){
					DISQUS.reset({
		        reload: true,
		        config: function () {  
		          this.page.identifier = window.location.hash;  
		          this.page.url = window.location.toString();
		        }
		      });
					$('#disqus_thread').show();
				}, 1500);
			}
		}
	})
	
	
})
