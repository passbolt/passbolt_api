steal('can/util','can/view/stache', 'steal-benchmark', function (can, stache, b) {

	/* jshint ignore:start */
	b.suite("can/view/stache spinning circle").add(
		"basics",
		function () {
			can.batch.start();
			for( var n = 0 ; n < boxes.length; n++ ) {
        		boxes[n].tick();
        	}
        	can.batch.stop();
		},
		{
			setup: function(){
				var template = can.stache(
					"{{#each boxes}}"+
						"<div class='box-view'>"+
							"<div class='box' id='box-{{number}}'  style='{{style}}'>"+
								"{{content}}"+
							"</div>"+
						"</div>"+
					"{{/each}}");
					
					
				var boxes = new can.List(),
					Box = can.Map.extend({
						top: 0,
					    left: 0,
					    content: 0,
					    count: 0,
					
					    tick: function() {
					        var count = this.attr('count') + 1;
					        
					        this.attr('count', count);
					        this.attr('top', Math.sin(count / 10) * 10);
					        this.attr('left', Math.cos(count / 10) * 10);
					        this.attr('color', count % 255);
					        this.attr('content', count % 100);
					        this.attr('style', this.computeStyle());
					        
					    },
					
					    computeStyle: function() {
					        return 'top: ' + this.attr('top') + 'px; left: ' +  this.attr('left') +'px; background: rgb(0,0,' + this.attr('color') + ');';
					    }
					});
				
				for(var i =0; i < 100; i++) {
					var box = new Box({ number: i });
					box.tick();
					boxes.push( box );
				}
				
				var frag = template({boxes: boxes});
				var div = document.createElement("div");
				div.appendChild(frag);
				$(document.body).append(frag);
			},
			teardown: function(){
				$(div).remove();
			}
		});
		
	/* jshint ignore:end */
});
