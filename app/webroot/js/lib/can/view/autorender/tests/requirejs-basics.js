define(["can/component", "can"], function(Component, can){
	return Component.extend({
		tag: "my-component",
		// call can.stache b/c it should be imported auto-magically
		template: can.stache("{{message}}"),
		scope: {
			message: "Hello World"
		},
		events: {
			"inserted": function(){
				this.element[0].className = "inserted";
			}
		}
	});
});
