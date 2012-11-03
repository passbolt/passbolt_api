steal(function(){

	// Test: See if 'dep_all.js' is on the page

	if (window.location.hash == '#a') {
		steal('steal/build/packages/test/packages_test/app_a.js', function(){
			// alert('Package A was stolen.')

			// Test: See if 'app_a.js' is on the page
			// Test: See if 'dep_a_b.js' is on the page

		});
	}
	
}).packages('steal/build/packages/test/packages_test/app_a.js', 
			'steal/build/packages/test/packages_test/app_b.js',
			'steal/build/packages/test/packages_test/app_c.js',
			'steal/build/packages/test/packages_test/app_d.js');