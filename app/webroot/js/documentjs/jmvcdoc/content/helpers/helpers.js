steal('can/control','can/view/ejs','can/view/modifiers').then('./demo.ejs',function(){
	
/**
 * Handles the presentation of @demo tags by:
 * 
 *  - making the iframe url correct no matter where the page is
 *  - making the iframe hight correct
 *  - enabiling code highlighting
 * 
 * Works by listening for docUpdated events and goes looking for .demo
 */
can.Control('candoc.content.helpers.Demo',
/* @Static */
{},
/* @Prototype */
{
	"{document.body} docUpdated" : function(){
		// hookup demo ui
		this.element.find(".demo_wrapper").each(function(){
			var $el = $(this),
				// default values
				height = 320,
				html = "",
				source = "",
				standbySource;
	
			$el.html("//documentjs/jmvcdoc/content/helpers/demo.ejs",{});
	
			var demoSrc = steal.config().root.join( $el.attr("data-demo-src") ),
				$iframe = $el.find("iframe");
	
			// when the iframe has loaded
			$iframe.one("load", function() {
				
				// get the body content
				var $body = $(this.contentWindow.document.body);
	
				// add some padding?
				$el.find(".demo_content").css({
					"padding": "5px"
				});
	
				// get the HTML content
				html = this.contentWindow.DEMO_HTML || $body.find("#demo-html").html();
				
				// set and highlight the html content
				$el.find(".html_content").html("<pre><code class=\"html\"></code></pre>").find("code").text($.trim(html)).highlight();
				
				// hide the instructions
				$body.find("#demo-instructions").hide();
				
				// get the source code
				source = $body.find("#demo-source").html();
				
				// set and highlight source code
				$el.find(".source_content").html("<pre><code class=\"javascript\"></code></pre>").find("code").text($.trim(source)).highlight();
	
				// keep trying to find a height
				var run = function(){
					setTimeout(function() {
						height = $body.outerHeight();
						$iframe.height(height + 50);
						$el.find(".demo_content").height(height + 55);
					}, 200)
				}
				if(this.contentWindow.jQuery){
					this.contentWindow.jQuery(run)
				} else {
					run()
				}
				
	
			})
			
			.attr("src", demoSrc);
		});
	},

	".header click": function( el, ev ) {
		el.next().toggle("slow")
	}
});

can.Control('candoc.content.helpers.Iframe',
/* @Static */
{},
/* @Prototype */
{
	"{document.body} docUpdated" : function(){
		this.element.find(".iframe_wrapper").each(function(){
			var $el = $(this),
				height = 320,
				html = "",
				source = "";
			var scripts = [];
	
			$el.append("<iframe></iframe>")
			//this.element.html("//documentjs/jmvcdoc/views/iframe/init.ejs", {});
	
			var src = steal.config().root.join($el.attr("data-iframe-src"));
			height = !$el.attr("data-iframe-height") ? height : $el.attr("data-iframe-height");
			var $iframe = $el.find("iframe");
			$iframe.attr("src", src);
			$iframe.attr("height", height);
	
			$iframe.bind('load', function() {
				
			});
		})
	}
});

can.Control('candoc.content.helpers.API', {
	"{document.body} docUpdated" : function(){
		// API
		if ( $("#api").length ) {
			var names = [];
			for ( var name in Search._data.list ) {
				names.push(name)
			}
			$("#api").html(
			DocumentationHelpers.link("[" + names.sort(Search.sortJustStrings).join("]<br/>[") + "]", true))
		}
	}
});

can.Control('candoc.content.helpers.Highlight', {
	"{document.body} docUpdated" : function(){
		// API
		this.element.find("code").highlight();
	}
});


// add absolute paths to image tags
can.Control('candoc.content.helpers.Image', {
	"{document.body} docUpdated" : function(){
		// API
		this.element.find(".image_tag").each(function() {
			var imageTagEl = $(this),
				relativePath = imageTagEl.attr("src"),
				absolutePath = steal.config().root.join(relativePath);
			imageTagEl.attr("src", absolutePath);
		});
	}
});
		





can.Control('candoc.content.helpers.Disqus', {
	init : function(){
		this.disqusIsLoaded = false;
	},
	"{document.body} docUpdated" : function(ev, docData){
		// Hide disqus
		$('#disqus_thread').hide();
		var target = this.element;
		
		// favorite link
		target.find("h1.addFavorite").
				append('&nbsp;<span class="favorite favorite' + (docData.isFavorite ? 'on' : 'off') + '">&nbsp;&nbsp;&nbsp;</span>');
		
		if ( steal.config().env == 'production' && docData.name != "index" && typeof(COMMENTS_LOCATION) != "undefined" && $("#disqus_thread").length ){
			
			if(!this.disqusIsLoaded){
				//window.disqus_developer = 1;
				window.disqus_shortname = 'jmvcs3';
			    window.disqus_identifier = window.location.hash;
			    window.disqus_url = window.location.toString();
				$.getScript(COMMENTS_LOCATION);
				disqusIsLoaded = true;
				$('#disqus_thread').show();
			}else{
				// wait a second to load comments
				clearTimeout(this.commentsTimeout);
				this.commentsTimeout = setTimeout(function(){
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
	}
});

	$(document).bind('docUpdated', function(ev, docData){
		
		
		
	})

})
