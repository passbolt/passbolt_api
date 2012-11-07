if(steal.config().browser){
	steal('steal/browser/test/trigger.js');
};
steal('jquery', function(){
	$(document).ready(function(){
		MyCo = {};
		MyCo.foo = "bla";
		steal.client.trigger('myevent', {foo: "bar"});
		setTimeout(function(){
			steal.client.trigger('done')
		}, 4000)
	})
})