load("steal/rhino/rhino.js");
steal('steal/build/packages', function(){
	steal.build.packages('steal/build/packages/test/packages_test/scripts/build.html', { 
		to: 'steal/build/packages/test/packages_test', 
		minify: true,
		depth: 3 
	});
});
