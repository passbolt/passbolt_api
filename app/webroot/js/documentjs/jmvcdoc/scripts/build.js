load("steal/rhino/rhino.js");
steal('steal/build',function() {
	steal.build('documentjs/jmvcdoc/jmvcdoc.html', {
		to: 'documentjs/jmvcdoc'
	});
});