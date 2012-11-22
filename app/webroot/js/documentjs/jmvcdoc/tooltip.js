steal('can/control', 'canui/layout/positionable', function(){
	can.Control('Tooltip',{
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
})
