steal('jquery/controller', function(){
	$.Controller('MyWidget',
	{
		vals: [1,2,3,4,5,6,7,8,9,10],
		index: 0
	},
	{
		init: function() {
			this.render();
			this.update();
		},
		render: function(){
			this.element.html('<ul><li class="next"><a href="javascript://">Next</a></li><li class="prev"><a href="javascript://">Prev</a></li></ul><p class="current"></p>');
		},
		update: function(){
			this.find('.current').text(MyWidget.vals[MyWidget.index]);	
		},
		'.next a click' : function(el, ev){
			if(MyWidget.index == 9) {
				MyWidget.index = 0;
			} else {
				MyWidget.index++;
			}
			this.update();
		},
		'.prev a click' : function(el, ev){
			if(MyWidget.index == 0) {
				MyWidget.index = 9;
			} else {
				MyWidget.index--;
			}
			this.update();
		}
	});
});