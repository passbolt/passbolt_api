//steal/js lb/scripts/compress.js

load("steal/rhino/rhino.js");
steal('steal/clean',function(){
	steal.clean('lb/lb.html',{
		indent_size: 1, 
		indent_char: '\t', 
		jslint : false,
		ignore: /jquery\/jquery.js/,
		predefined: {
			steal: true, 
			jQuery: true, 
			$ : true,
			window : true
			}
	});
});
