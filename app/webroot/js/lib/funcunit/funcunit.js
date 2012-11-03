//what we need from javascriptmvc or other places
steal('funcunit/syn',
	'./browser/core.js', 
	'./browser/adapters/adapters.js',
	'./browser/open.js', 
	'./browser/actions.js', 
	'./browser/getters.js',
	'./browser/traversers.js', 
	'./browser/queue.js', 
	'./browser/waits.js',
function(Syn, FuncUnit) {
	window.FuncUnit = window.S = FuncUnit;
	
	return FuncUnit;
});
