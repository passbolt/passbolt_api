//steal/js passbolt/scripts/compress.js

load("steal/rhino/rhino.js");
steal('steal/clean',function(){
	steal.clean('passbolt/passbolt.html',{
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
