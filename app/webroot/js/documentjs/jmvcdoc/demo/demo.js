steal('jquery/controller','jquery/view/ejs').then('./demo.ejs',function(){
	
/**
 * @tag home
 * 
 * Handles @demo logic
 */
jQuery.Controller('DemoController',
/* @Static */
{},
/* @Prototype */
{
	init: function() {
		var self = this;
		var height = 320,
			html = "",
			source = "",
			standbySource;


		this.element.html("//documentjs/jmvcdoc/demo/demo.ejs",{});

		var demoSrc = steal.root.join(this.element.attr("data-demo-src"));
		var $iframe = this.find("iframe");


		$iframe.bind("load", function() {
			var $body = $(this.contentWindow.document.body);

			self.find(".demo_content").css({
				"padding": "5px"
			});

			html = this.contentWindow.DEMO_HTML || $body.find("#demo-html").html();
			self.find(".html_content").html("<pre><code class=\"html\"></code></pre>").find("code").text($.trim(html)).highlight();
			$body.find("#demo-instructions").hide();
			source = $body.find("#demo-source").html();


			self.find(".source_content").html("<pre><code class=\"javascript\"></code></pre>").find("code").text($.trim(source)).highlight();

			// save second script(to show when we can't find #demo-source
			if (!source ) {
				$('script', $iframe[0].contentWindow.document).each(function( i, script ) {
					if (!script.text.match(/steal.end()/) ) {
						standbySource = script.text;
						// break if it's not steal.js
						if (!script.src.match(/steal.js/) ) return false;
					}
				});

				self.find(".source_content").html("<pre><code class=\"javascript\"></code></pre>").find("code").text($.trim(standbySource)).highlight();
			}
			var run = function(){
				setTimeout(function() {
					height = $body.outerHeight();
					$iframe.height(height + 50);
					self.find(".demo_content").height(height + 55);
				}, 200)
			}
			if(this.contentWindow.jQuery){
				this.contentWindow.jQuery(run)
			} else {
				run()
			}
			

		})
		$iframe.attr("src", demoSrc);
	},

	".header click": function( el, ev ) {
		el.next().toggle("slow")
		el.find("span").toggleClass("ui-icon-triangle-1-s").toggleClass("ui-icon-triangle-1-e");
	}
});
	
	
})
