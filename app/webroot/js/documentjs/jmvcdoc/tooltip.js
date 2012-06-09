steal('jquery/controller', 'mxui/layout/positionable', function(){
	$.Controller('Tooltip',{
		init : function(){
			this.element.mxui_layout_positionable({
				my: "left top",
				at : "right top",
				offset: "0 -5"
			})
		},
		update : function(options){
			this._super(options);
			this.element.html(options.message);
			this.element.trigger("show",options.of)
		}
	})
	
	//$('<div id="tooltip"/>').appendTo(document.body).tooltip();
	
	
})
