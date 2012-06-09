//what we need from javascriptmvc or other places
steal('funcunit/qunit')
	.then('./browser/resources/jquery.js', function(){
		if(!window.FuncUnit){
			window.FuncUnit = {};
		}
		FuncUnit.jQuery = jQuery.noConflict(true);
	})
	.then('./browser/resources/json.js', 'funcunit/syn')
	.then('./browser/core.js')
	.then('./browser/open.js', './browser/actions.js', 
		'./browser/getters.js', './browser/traversers.js', './browser/queue.js', 
		'./browser/waits.js')